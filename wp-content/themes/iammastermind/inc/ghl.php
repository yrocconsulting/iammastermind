<?php
/**
 * Go High Level (LeadConnector) integration.
 *
 * Two independent delivery methods - configure either or both under
 * Settings > I AM Mastermind:
 *   A. Inbound Webhook: a GHL Workflow with the "Inbound Webhook" trigger. The full
 *      lead payload is POSTed there; map fields and build automations in GHL.
 *   B. API v2 (Private Integration token): contact is upserted directly with tags,
 *      and the message / answers are attached as a contact note.
 *
 * Also handles redirects of the original member URLs (/login, /dashboard, ...) to the
 * GHL membership portal, and prints GHL tracking / chat widget scripts.
 *
 * @package iammastermind
 */

defined( 'ABSPATH' ) || exit;

const IAM_GHL_API = 'https://services.leadconnectorhq.com';

/**
 * Send a normalised lead to GHL.
 *
 * @param array $lead See iam_process_submission().
 * @return array Result summary stored with the submission.
 */
function iam_ghl_send_lead( $lead ) {
	$result = array();

	$hook = iam_opt( 'ghl_webhook_url' );
	if ( $hook ) {
		$payload = array(
			'form_type'    => $lead['type'],
			'endpoint'     => $lead['endpoint'],
			'name'         => $lead['name'],
			'first_name'   => iam_split_name( $lead['name'] )[0],
			'last_name'    => iam_split_name( $lead['name'] )[1],
			'email'        => $lead['email'],
			'phone'        => $lead['phone'],
			'source'       => $lead['source'],
			'message'      => $lead['message'],
			'lang'         => $lead['lang'],
			'page_url'     => $lead['page'],
			'tags'         => implode( ',', iam_ghl_tags( $lead ) ),
			'submitted_at' => gmdate( 'c' ),
			'fields'       => $lead['fields'],
		);
		$r = wp_remote_post( $hook, array(
			'timeout' => 10,
			'headers' => array( 'Content-Type' => 'application/json' ),
			'body'    => wp_json_encode( apply_filters( 'iam_ghl_webhook_payload', $payload, $lead ) ),
		) );
		$result['webhook'] = is_wp_error( $r ) ? $r->get_error_message() : wp_remote_retrieve_response_code( $r );
	}

	$token = iam_opt( 'ghl_token' );
	$loc   = iam_opt( 'ghl_location_id' );
	if ( $token && $loc ) {
		list( $first, $last ) = iam_split_name( $lead['name'] );
		$contact = array_filter( array(
			'locationId' => $loc,
			'firstName'  => $first,
			'lastName'   => $last,
			'name'       => $lead['name'],
			'email'      => $lead['email'],
			'phone'      => $lead['phone'],
			'source'     => 'Website: ' . ( $lead['source'] ?: $lead['type'] ),
			'tags'       => iam_ghl_tags( $lead ),
		) );
		$r = iam_ghl_api( 'POST', '/contacts/upsert', apply_filters( 'iam_ghl_contact', $contact, $lead ) );
		$result['upsert'] = $r['code'];
		$id = $r['body']['contact']['id'] ?? '';
		if ( $id ) {
			$result['contact_id'] = $id;
			$note = array();
			foreach ( $lead['fields'] as $k => $v ) {
				if ( '' !== $v && ! in_array( strtolower( $k ), array( 'name', 'fullname', 'email', 'phone', 'phonenumber' ), true ) ) {
					$note[] = $k . ': ' . $v;
				}
			}
			if ( $note ) {
				array_unshift( $note, 'Website form: ' . $lead['type'] . ( $lead['page'] ? ' (' . $lead['page'] . ')' : '' ) );
				$n = iam_ghl_api( 'POST', '/contacts/' . rawurlencode( $id ) . '/notes', array( 'body' => implode( "\n", $note ) ) );
				$result['note'] = $n['code'];
			}
		} elseif ( ! empty( $r['error'] ) ) {
			$result['error'] = $r['error'];
		}
	}

	return $result;
}

/** Tags applied to every GHL contact created from the site. */
function iam_ghl_tags( $lead ) {
	$tags   = array_filter( array_map( 'trim', explode( ',', (string) iam_opt( 'ghl_tags', 'website-lead' ) ) ) );
	$tags[] = 'form-' . sanitize_title( $lead['type'] );
	$tags[] = 'lang-' . $lead['lang'];
	return array_values( array_unique( apply_filters( 'iam_ghl_tags', $tags, $lead ) ) );
}

/** Minimal LeadConnector API v2 client. */
function iam_ghl_api( $method, $path, $body = null ) {
	$args = array(
		'method'  => $method,
		'timeout' => 12,
		'headers' => array(
			'Authorization' => 'Bearer ' . iam_opt( 'ghl_token' ),
			'Version'       => '2021-07-28',
			'Accept'        => 'application/json',
			'Content-Type'  => 'application/json',
		),
	);
	if ( null !== $body ) {
		$args['body'] = wp_json_encode( $body );
	}
	$r = wp_remote_request( IAM_GHL_API . $path, $args );
	if ( is_wp_error( $r ) ) {
		return array( 'code' => 0, 'body' => array(), 'error' => $r->get_error_message() );
	}
	$code = wp_remote_retrieve_response_code( $r );
	$json = json_decode( wp_remote_retrieve_body( $r ), true );
	return array(
		'code'  => $code,
		'body'  => is_array( $json ) ? $json : array(),
		'error' => $code >= 300 ? substr( wp_remote_retrieve_body( $r ), 0, 300 ) : '',
	);
}

function iam_split_name( $name ) {
	$parts = preg_split( '/\s+/', trim( (string) $name ), 2 );
	return array( $parts[0] ?? '', $parts[1] ?? '' );
}

// Original member-area URLs -> GHL membership / client portal.
add_action( 'parse_request', function () {
	$portal = iam_opt( 'member_portal_url' );
	if ( ! $portal ) {
		return;
	}
	$path = iam_request_path();
	if ( preg_match( '#^(login|dashboard|reset-password|member|members|portal)(/|$)#', $path ) ) {
		wp_redirect( esc_url_raw( $portal ), 302, 'I AM Mastermind' ); // phpcs:ignore WordPress.Security.SafeRedirect -- admin-configured
		exit;
	}
}, 1 );

// GHL external tracking + custom scripts (chat widget, etc.).
add_action( 'wp_head', function () {
	$tid = iam_opt( 'ghl_tracking_id' );
	if ( $tid ) {
		printf( '<script src="https://link.msgsndr.com/js/external-tracking.js" data-tracking-id="%s" defer></script>' . "\n", esc_attr( $tid ) );
	}
	echo iam_opt( 'head_scripts' ); // phpcs:ignore -- admin-only (unfiltered_html) setting
}, 99 );

add_action( 'wp_footer', function () {
	echo iam_opt( 'footer_scripts' ); // phpcs:ignore -- admin-only (unfiltered_html) setting
}, 99 );
