<?php
/**
 * Form endpoints. The original front-end JavaScript posts to:
 *   POST /api/newsletter   JSON {name,email,phone,source}               (30-day trial form, every page)
 *   POST /api/contact      JSON {name,email,interest,message,type,...}  (contact, challenge, retreat assessment)
 *   POST /inner-circle     multipart form, expects {"success":true}      (Inner Circle application)
 * Those URLs are kept so the original scripts run unchanged. Each submission is:
 *   1. stored as a private "Form submission" post (never lost),
 *   2. forwarded to Go High Level (inc/ghl.php),
 *   3. optionally emailed to the site team.
 *
 * @package iammastermind
 */

defined( 'ABSPATH' ) || exit;

add_action( 'init', function () {
	register_post_type( 'iam_submission', array(
		'labels'          => array(
			'name'          => __( 'Form submissions', 'iammastermind' ),
			'singular_name' => __( 'Form submission', 'iammastermind' ),
		),
		'public'          => false,
		'show_ui'         => true,
		'show_in_menu'    => true,
		'menu_icon'       => 'dashicons-email-alt',
		'supports'        => array( 'title', 'editor', 'custom-fields' ),
		'capability_type' => 'post',
		'capabilities'    => array( 'create_posts' => 'do_not_allow' ),
		'map_meta_cap'    => true,
	) );
} );

// /api/* endpoints - handled before WordPress resolves the query.
add_action( 'parse_request', function () {
	$path = iam_request_path();
	if ( ! preg_match( '#^api/(contact|newsletter)$#', $path, $m ) ) {
		return;
	}
	if ( 'POST' !== ( $_SERVER['REQUEST_METHOD'] ?? '' ) ) { // phpcs:ignore
		wp_send_json( array( 'error' => 'Method not allowed' ), 405 );
	}
	iam_handle_form_request( $m[1] );
	exit;
}, 0 );

// Same handler also reachable over the REST API: POST /wp-json/iam/v1/submit/<form>.
add_action( 'rest_api_init', function () {
	register_rest_route( 'iam/v1', '/submit/(?P<form>[a-z0-9_-]+)', array(
		'methods'             => 'POST',
		'permission_callback' => '__return_true',
		'callback'            => function ( WP_REST_Request $req ) {
			$res = iam_process_submission( $req->get_param( 'form' ), $req->get_json_params() ?: $req->get_body_params() );
			return new WP_REST_Response( $res['body'], $res['status'] );
		},
	) );
} );

/** Read the request body (JSON or form-encoded), process, respond with JSON, exit. */
function iam_handle_form_request( $endpoint ) {
	$raw  = file_get_contents( 'php://input' ); // phpcs:ignore
	$data = json_decode( (string) $raw, true );
	if ( ! is_array( $data ) ) {
		$data = wp_unslash( $_POST ); // phpcs:ignore WordPress.Security.NonceVerification
	}
	$res = iam_process_submission( $endpoint, $data );
	wp_send_json( $res['body'], $res['status'] );
}

/** Flatten nested values to strings. */
function iam_clean_fields( $data ) {
	$out = array();
	foreach ( (array) $data as $k => $v ) {
		$k = sanitize_text_field( (string) $k );
		if ( is_array( $v ) ) {
			$v = implode( ', ', array_map( 'sanitize_text_field', array_map( 'strval', array_filter( $v, 'is_scalar' ) ) ) );
		} else {
			$v = sanitize_textarea_field( (string) $v );
		}
		$out[ rtrim( $k, '[]' ) ] = $v;
	}
	return $out;
}

/** Pick the first non-empty field among aliases (forms use different names). */
function iam_pick( $f, $names ) {
	foreach ( $names as $n ) {
		foreach ( $f as $k => $v ) {
			if ( 0 === strcasecmp( $k, $n ) && '' !== trim( $v ) ) {
				return trim( $v );
			}
		}
	}
	return '';
}

/**
 * Core pipeline. Returns ['status' => int, 'body' => array].
 *
 * @param string $endpoint newsletter|contact|page:<key>|<rest form name>.
 * @param array  $data     Raw submitted fields.
 */
function iam_process_submission( $endpoint, $data ) {
	$f = iam_clean_fields( $data );

	// Honeypot used by the original forms.
	if ( ! empty( $f['website'] ) ) {
		return array( 'status' => 200, 'body' => array( 'success' => true ) );
	}
	unset( $f['website'] );

	$lead = array(
		'endpoint' => $endpoint,
		'type'     => iam_pick( $f, array( 'type' ) ) ?: ( 0 === strpos( $endpoint, 'page:' ) ? 'application' : $endpoint ),
		'name'     => iam_pick( $f, array( 'name', 'FullName', 'full_name', 'fullname' ) ),
		'email'    => sanitize_email( iam_pick( $f, array( 'email', 'Email' ) ) ),
		'phone'    => iam_pick( $f, array( 'phone', 'PhoneNumber', 'tel' ) ),
		'source'   => iam_pick( $f, array( 'source', 'interest' ) ),
		'message'  => iam_pick( $f, array( 'message' ) ),
		'lang'     => iam_pick( $f, array( 'lang' ) ) ?: iam_lang(),
		'page'     => isset( $_SERVER['HTTP_REFERER'] ) ? esc_url_raw( wp_unslash( $_SERVER['HTTP_REFERER'] ) ) : '', // phpcs:ignore
		'fields'   => $f,
	);

	if ( ! is_email( $lead['email'] ) ) {
		$msg = 'es' === $lead['lang'] ? 'Por favor ingresa un correo válido.' : 'Please enter a valid email address.';
		return array( 'status' => 400, 'body' => array( 'success' => false, 'error' => $msg ) );
	}

	// Basic flood control: 8 submissions / 10 min / IP.
	$ip  = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '';
	$rk  = 'iam_rl_' . md5( $ip );
	$cnt = (int) get_transient( $rk );
	if ( $cnt >= 8 ) {
		return array( 'status' => 429, 'body' => array( 'success' => false, 'error' => 'Too many requests. Please try again later.' ) );
	}
	set_transient( $rk, $cnt + 1, 10 * MINUTE_IN_SECONDS );

	$lead = apply_filters( 'iam_lead', $lead );

	// 1. Store.
	$lines = array();
	foreach ( $lead['fields'] as $k => $v ) {
		$lines[] = $k . ': ' . $v;
	}
	$post_id = wp_insert_post( array(
		'post_type'    => 'iam_submission',
		'post_status'  => 'private',
		'post_title'   => sprintf( '[%s] %s (%s)', $lead['type'], $lead['name'] ?: '-', $lead['email'] ),
		'post_content' => implode( "\n", $lines ),
	) );
	if ( $post_id && ! is_wp_error( $post_id ) ) {
		update_post_meta( $post_id, '_iam_lead', wp_json_encode( $lead ) );
	}

	// 2. Go High Level.
	$ghl = iam_ghl_send_lead( $lead );
	if ( $post_id && ! is_wp_error( $post_id ) ) {
		update_post_meta( $post_id, '_iam_ghl_result', wp_json_encode( $ghl ) );
	}

	// 3. Email.
	$to = iam_opt( 'notify_email' );
	if ( $to ) {
		wp_mail( $to, sprintf( '[%s] New %s submission from %s', wp_specialchars_decode( get_bloginfo( 'name' ) ), $lead['type'], $lead['email'] ),
			implode( "\n", $lines ) . "\n\nPage: " . $lead['page'], array( 'Reply-To: ' . $lead['email'] ) );
	}

	do_action( 'iam_lead_processed', $lead, $ghl, $post_id );

	return array( 'status' => 200, 'body' => array( 'success' => true, 'message' => 'ok' ) );
}
