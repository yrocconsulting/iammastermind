<?php
/**
 * Snapshot router: serves the pixel-matched pages captured from the original site.
 *
 * Every URL of the original site has a snapshot in /snapshots/<lang>/<key>.{head,main}.html.
 * A snapshot is served for its URL unless a WordPress page/post at that URL has its
 * own content (or the "Use WordPress content" box is ticked) - that lets you replace
 * any page with editor-built content one page at a time.
 *
 * @package iammastermind
 */

defined( 'ABSPATH' ) || exit;

/** @return array key => [lang => [layout, html_class, body_class]] */
function iam_snapshot_manifest() {
	static $m = null;
	if ( null === $m ) {
		$m = include IAM_THEME_DIR . '/snapshots/manifest.php';
	}
	return $m;
}

/** Snapshot key for the current request, or null. */
function iam_snapshot_key_for_request() {
	$path = iam_request_path();
	$key  = '' === $path ? 'index' : $path;
	$m    = iam_snapshot_manifest();
	return isset( $m[ $key ] ) ? $key : null;
}

/** Whether a WordPress post at this URL should take over from the snapshot. */
function iam_wp_overrides_snapshot() {
	if ( ! is_singular() ) {
		return false;
	}
	$post = get_queried_object();
	if ( ! $post instanceof WP_Post ) {
		return false;
	}
	if ( get_post_meta( $post->ID, '_iam_use_wp_content', true ) ) {
		return true;
	}
	return '' !== trim( (string) $post->post_content );
}

/** True when the current front-end request is being served from a snapshot. */
function iam_is_snapshot_request() {
	static $r = null;
	if ( null === $r ) {
		$r = ! is_admin() && did_action( 'wp' ) && iam_snapshot_key_for_request() && ! iam_wp_overrides_snapshot();
	}
	return $r;
}

/** Output a full snapshot document. */
function iam_render_snapshot( $key ) {
	$lang  = iam_lang();
	$m     = iam_snapshot_manifest();
	$meta  = $m[ $key ][ $lang ];
	$base  = "snapshots/{$lang}/{$key}";

	// The snapshot <head> already carries title/description/OG/JSON-LD.
	remove_action( 'wp_head', '_wp_render_title_tag', 1 );
	remove_action( 'wp_head', 'rel_canonical' );
	iam_strip_wp_frontend_assets();

	status_header( 200 );
	nocache_headers_for_lang();
	header( 'Content-Type: text/html; charset=UTF-8' );

	echo '<!DOCTYPE html><html lang="' . esc_attr( $meta['html_lang'] ?? $lang ) . '"' . ( $meta['html_class'] ? ' class="' . esc_attr( $meta['html_class'] ) . '"' : '' ) . '> <head>';
	echo iam_file( $base . '.head.html' ); // phpcs:ignore WordPress.Security.EscapeOutput -- trusted theme file
	iam_asset_base_script();
	wp_head();
	echo '</head> <body class="' . esc_attr( $meta['body_class'] ) . '">';
	wp_body_open();
	if ( 'none' !== $meta['layout'] ) {
		echo iam_file( "layouts/{$meta['layout']}/{$lang}.header.html" ); // phpcs:ignore
	}
	echo iam_file( $base . '.main.html' ); // phpcs:ignore
	if ( 'none' !== $meta['layout'] ) {
		echo iam_file( "layouts/{$meta['layout']}/{$lang}.footer.html" ); // phpcs:ignore
	}
	echo iam_file( $base . '.tail.html' ); // phpcs:ignore -- page scripts (empty for most pages)
	wp_footer();
	echo '</body></html>';
}

/**
 * Snapshots ship their own complete CSS; drop WordPress' default front-end styles
 * (block library, global styles, oEmbed/REST discovery) so the output matches the original.
 * Plugin assets are left alone.
 */
function iam_strip_wp_frontend_assets() {
	remove_action( 'wp_enqueue_scripts', 'wp_enqueue_global_styles' );
	remove_action( 'wp_footer', 'wp_enqueue_global_styles', 1 );
	remove_action( 'wp_enqueue_scripts', 'wp_enqueue_classic_theme_styles' );
	remove_action( 'wp_head', 'wp_enqueue_img_auto_sizes_contain_css_fix', 0 );
	remove_action( 'wp_head', 'wp_print_auto_sizes_contain_css_fix', 1 );
	remove_action( 'wp_footer', 'wp_print_speculation_rules' );
	remove_action( 'wp_head', 'wp_oembed_add_discovery_links', 4 );
	remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
	remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
	remove_filter( 'wp_robots', 'wp_robots_max_image_preview_large' ); // keeps only noindex (when search engines are discouraged)
	$dequeue = function () {
		foreach ( array( 'wp-block-library', 'wp-block-library-theme', 'global-styles', 'classic-theme-styles', 'core-block-supports', 'wp-block-styles-placeholder', 'wp-global-styles-placeholder', 'wp-img-auto-sizes-contain' ) as $h ) {
			wp_dequeue_style( $h );
		}
	};
	add_action( 'wp_print_styles', $dequeue, 0 );
	add_action( 'wp_print_footer_scripts', $dequeue, 0 );
}

/**
 * Pages vary by the `lang` cookie. Tell caches so (SiteGround: see docs/DEPLOYMENT.md).
 */
function nocache_headers_for_lang() {
	header( 'Vary: Cookie', false );
}

add_action( 'template_redirect', function () {
	if ( is_feed() || is_robots() || is_trackback() || is_embed() ) {
		return;
	}
	$key = iam_snapshot_key_for_request();
	if ( ! $key || iam_wp_overrides_snapshot() ) {
		return;
	}
	// Some original forms (e.g. Inner Circle application) POST to their own page.
	if ( 'POST' === ( $_SERVER['REQUEST_METHOD'] ?? 'GET' ) ) { // phpcs:ignore
		iam_handle_form_request( 'page:' . $key );
		exit;
	}
	iam_render_snapshot( $key );
	exit;
}, 1 );

// Meta box: let editors force WordPress content on a page that has a snapshot.
add_action( 'add_meta_boxes', function () {
	add_meta_box( 'iam_snapshot', __( 'Original design', 'iammastermind' ), function ( $post ) {
		wp_nonce_field( 'iam_snapshot_box', 'iam_snapshot_nonce' );
		$path = trim( (string) wp_parse_url( get_permalink( $post ), PHP_URL_PATH ), '/' );
		$home = trim( (string) wp_parse_url( home_url( '/' ), PHP_URL_PATH ), '/' );
		if ( $home && 0 === strpos( $path, $home ) ) {
			$path = trim( substr( $path, strlen( $home ) ), '/' );
		}
		$key = '' === $path || (int) get_option( 'page_on_front' ) === $post->ID ? 'index' : $path;
		$has = isset( iam_snapshot_manifest()[ $key ] );
		echo '<p>' . ( $has
			? esc_html__( 'This URL has a pixel-matched snapshot of the original site. It is shown while this page has no content.', 'iammastermind' )
			: esc_html__( 'No snapshot exists for this URL; the WordPress content is used.', 'iammastermind' ) ) . '</p>';
		if ( $has ) {
			printf( '<label><input type="checkbox" name="iam_use_wp_content" value="1" %s> %s</label>',
				checked( (bool) get_post_meta( $post->ID, '_iam_use_wp_content', true ), true, false ),
				esc_html__( 'Use WordPress content instead of the snapshot', 'iammastermind' ) );
		}
	}, array( 'page', 'post' ), 'side' );
} );

add_action( 'save_post', function ( $post_id ) {
	if ( ! isset( $_POST['iam_snapshot_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['iam_snapshot_nonce'] ), 'iam_snapshot_box' ) ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	if ( ! empty( $_POST['iam_use_wp_content'] ) ) {
		update_post_meta( $post_id, '_iam_use_wp_content', 1 );
	} else {
		delete_post_meta( $post_id, '_iam_use_wp_content' );
	}
} );
