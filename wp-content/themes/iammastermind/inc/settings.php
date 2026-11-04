<?php
/**
 * Settings > I AM Mastermind.
 *
 * Every value can also be pinned in wp-config.php as a constant, e.g.
 *   define( 'IAM_GHL_TOKEN', 'pit-...' );
 * which keeps secrets out of the database and overrides the screen.
 *
 * @package iammastermind
 */

defined( 'ABSPATH' ) || exit;

function iam_settings_fields() {
	return array(
		'ghl_webhook_url'   => array( 'Inbound Webhook URL', 'url', 'GHL > Automation > Workflow with an "Inbound Webhook" trigger. Every form submission is POSTed here as JSON.' ),
		'ghl_token'         => array( 'Private Integration token', 'password', 'GHL > Settings > Private Integrations. Scopes: contacts.write, contacts.readonly. Used to upsert contacts + add notes.' ),
		'ghl_location_id'   => array( 'Location (sub-account) ID', 'text', 'GHL > Settings > Business Profile.' ),
		'ghl_tags'          => array( 'Tags for every lead', 'text', 'Comma separated. form-&lt;type&gt; and lang-&lt;en|es&gt; tags are added automatically.' ),
		'ghl_tracking_id'   => array( 'External tracking ID', 'text', 'GHL > Settings > External Tracking (tk_...). Attributes page visits to contacts.' ),
		'member_portal_url' => array( 'Membership portal URL', 'url', 'Where /login and /dashboard go (GHL Memberships / Client Portal), e.g. https://members.iammastermind.com' ),
		'notify_email'      => array( 'Notification email', 'text', 'Optional. Leave empty to rely on GHL notifications only.' ),
		'head_scripts'      => array( 'Extra &lt;head&gt; code', 'code', 'Optional raw HTML.' ),
		'footer_scripts'    => array( 'Extra footer code', 'code', 'Optional raw HTML, e.g. the GHL Chat Widget snippet.' ),
	);
}

/** Read a setting (wp-config constant IAM_<KEY> wins). */
function iam_opt( $key, $default = '' ) {
	$const = 'IAM_' . strtoupper( $key );
	if ( defined( $const ) ) {
		return constant( $const );
	}
	$o = get_option( 'iam_settings', array() );
	return isset( $o[ $key ] ) && '' !== $o[ $key ] ? $o[ $key ] : $default;
}

add_action( 'admin_menu', function () {
	add_options_page( 'I AM Mastermind', 'I AM Mastermind', 'manage_options', 'iam-settings', 'iam_settings_page' );
} );

add_action( 'admin_init', function () {
	register_setting( 'iam_settings', 'iam_settings', array(
		'type'              => 'array',
		'sanitize_callback' => function ( $in ) {
			$out = array();
			foreach ( iam_settings_fields() as $k => $f ) {
				$v = isset( $in[ $k ] ) ? (string) $in[ $k ] : '';
				if ( 'code' === $f[1] ) {
					$out[ $k ] = current_user_can( 'unfiltered_html' ) ? $v : wp_kses_post( $v );
				} elseif ( 'url' === $f[1] ) {
					$out[ $k ] = esc_url_raw( trim( $v ) );
				} else {
					$out[ $k ] = sanitize_text_field( $v );
				}
			}
			return $out;
		},
	) );
} );

function iam_settings_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$notice = '';
	if ( isset( $_POST['iam_test_lead'] ) && check_admin_referer( 'iam_test_lead' ) ) {
		$user   = wp_get_current_user();
		$res    = iam_process_submission( 'test', array( 'name' => 'Website Test', 'email' => $user->user_email, 'type' => 'test', 'message' => 'Test lead sent from WordPress settings.' ) );
		$latest = get_posts( array( 'post_type' => 'iam_submission', 'post_status' => 'private', 'numberposts' => 1 ) );
		$ghl    = $latest ? get_post_meta( $latest[0]->ID, '_iam_ghl_result', true ) : '';
		$notice = 'Test lead processed (HTTP ' . (int) $res['status'] . '). GHL result: <code>' . esc_html( $ghl ?: 'not configured' ) . '</code>';
	}
	$o = get_option( 'iam_settings', array() );
	echo '<div class="wrap"><h1>I AM Mastermind</h1>';
	if ( $notice ) {
		echo '<div class="notice notice-info"><p>' . wp_kses_post( $notice ) . '</p></div>';
	}
	echo '<form method="post" action="options.php">';
	settings_fields( 'iam_settings' );
	echo '<h2>Go High Level</h2><table class="form-table" role="presentation">';
	foreach ( iam_settings_fields() as $k => $f ) {
		$v     = isset( $o[ $k ] ) ? $o[ $k ] : '';
		$const = 'IAM_' . strtoupper( $k );
		echo '<tr><th scope="row"><label for="iam_' . esc_attr( $k ) . '">' . wp_kses_post( $f[0] ) . '</label></th><td>';
		if ( defined( $const ) ) {
			echo '<em>Set in wp-config.php (' . esc_html( $const ) . ')</em>';
		} elseif ( 'code' === $f[1] ) {
			echo '<textarea class="large-text code" rows="5" id="iam_' . esc_attr( $k ) . '" name="iam_settings[' . esc_attr( $k ) . ']">' . esc_textarea( $v ) . '</textarea>';
		} else {
			echo '<input class="regular-text" type="' . esc_attr( $f[1] ) . '" id="iam_' . esc_attr( $k ) . '" name="iam_settings[' . esc_attr( $k ) . ']" value="' . esc_attr( $v ) . '" autocomplete="off">';
		}
		echo '<p class="description">' . wp_kses_post( $f[2] ) . '</p></td></tr>';
	}
	echo '</table>';
	submit_button();
	echo '</form><hr><form method="post">';
	wp_nonce_field( 'iam_test_lead' );
	echo '<p>Sends a test submission through the full pipeline (stored under <em>Form submissions</em>, forwarded to GHL).</p>';
	submit_button( 'Send test lead', 'secondary', 'iam_test_lead', false );
	echo '</form></div>';
}
