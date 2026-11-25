<?php
/**
 * Language + layout helpers.
 *
 * @package iammastermind
 */

defined( 'ABSPATH' ) || exit;

/**
 * Current language. The original site stores it in a `lang` cookie (en|es)
 * which the header toggle sets. `?lang=es` is accepted too and persisted.
 */
function iam_lang() {
	static $lang = null;
	if ( null !== $lang ) {
		return $lang;
	}
	$lang = 'en';
	if ( isset( $_GET['lang'] ) && in_array( $_GET['lang'], array( 'en', 'es' ), true ) ) { // phpcs:ignore WordPress.Security.NonceVerification
		$lang = $_GET['lang']; // phpcs:ignore
		if ( ! headers_sent() ) {
			setcookie( 'lang', $lang, array( 'expires' => time() + YEAR_IN_SECONDS, 'path' => '/', 'samesite' => 'Lax' ) );
		}
	} elseif ( isset( $_COOKIE['lang'] ) && 'es' === $_COOKIE['lang'] ) {
		$lang = 'es';
	}
	return $lang;
}

/** Translate a small string pair for native templates. */
function iam_t( $en, $es ) {
	return 'es' === iam_lang() ? $es : $en;
}

/** Resolve snapshot placeholders (asset/home URLs, theme-rendered menus). */
function iam_fill( $html ) {
	$map = array(
		'{{IAM_ASSETS}}' => IAM_THEME_URI . '/assets',
		'{{IAM_HOME}}'   => untrailingslashit( home_url() ),
	);
	if ( false !== strpos( $html, '{{IAM_NAV_' ) ) {
		$map['{{IAM_NAV_DESKTOP}}'] = iam_nav_html( 'desktop' );
		$map['{{IAM_NAV_MOBILE}}']  = iam_nav_html( 'mobile' );
	}
	return strtr( $html, $map );
}

/** Read a theme file (relative path) with placeholders resolved. */
function iam_file( $rel ) {
	$path = IAM_THEME_DIR . '/' . ltrim( $rel, '/' );
	if ( ! is_readable( $path ) ) {
		return '';
	}
	return iam_fill( file_get_contents( $path ) ); // phpcs:ignore WordPress.WP.AlternativeFunctions
}

/** Layout metadata (html/body classes) for native templates. */
function iam_layout_meta( $layout = 'site' ) {
	$json = iam_file( "layouts/{$layout}/" . iam_lang() . '.json' );
	$meta = json_decode( $json, true );
	return is_array( $meta ) ? $meta : array( 'html_class' => '', 'body_class' => '' );
}

/**
 * Printed in every <head> after the original CSS: the inline script the patched
 * Astro client uses to locate its chunks, and the stylesheet compiled from the
 * theme's own new templates (tools/tailwind -> assets/iam.css).
 */
function iam_asset_base_script() {
	echo '<script>window.IAM_ASSET_BASE=' . wp_json_encode( IAM_THEME_URI . '/assets/' ) . ';</script>' . "\n";
	$css = IAM_THEME_DIR . '/assets/iam.css';
	if ( is_readable( $css ) ) {
		printf( '<link rel="stylesheet" href="%s">' . "\n", esc_url( IAM_THEME_URI . '/assets/iam.css?v=' . filemtime( $css ) ) );
	}
}

/**
 * The request path relative to the WordPress home, without slashes.
 * '' for the front page.
 */
function iam_request_path() {
	$uri  = isset( $_SERVER['REQUEST_URI'] ) ? wp_unslash( $_SERVER['REQUEST_URI'] ) : '/'; // phpcs:ignore
	$path = (string) wp_parse_url( $uri, PHP_URL_PATH );
	$home = (string) wp_parse_url( home_url( '/' ), PHP_URL_PATH );
	if ( $home && 0 === strpos( $path, $home ) ) {
		$path = substr( $path, strlen( $home ) );
	}
	return trim( rawurldecode( $path ), '/' );
}
