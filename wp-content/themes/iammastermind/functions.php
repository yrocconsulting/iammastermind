<?php
/**
 * I AM Mastermind theme bootstrap.
 *
 * @package iammastermind
 */

defined( 'ABSPATH' ) || exit;

define( 'IAM_THEME_VERSION', '1.0.0' );
define( 'IAM_THEME_DIR', get_template_directory() );
define( 'IAM_THEME_URI', get_template_directory_uri() );

require IAM_THEME_DIR . '/inc/helpers.php';
require IAM_THEME_DIR . '/inc/snapshots.php';
require IAM_THEME_DIR . '/inc/settings.php';
require IAM_THEME_DIR . '/inc/forms.php';
require IAM_THEME_DIR . '/inc/ghl.php';
require IAM_THEME_DIR . '/inc/setup.php';

add_action( 'after_setup_theme', function () {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script' ) );
	register_nav_menus( array( 'primary' => __( 'Primary (reserved)', 'iammastermind' ) ) );
} );

// Front-end clean-up: the design ships its own CSS; keep WordPress' extras out of the way.
add_action( 'init', function () {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'wp_head', 'wp_generator' );
	remove_action( 'wp_head', 'rsd_link' );
	remove_action( 'wp_head', 'wlwmanifest_link' );
	remove_action( 'wp_head', 'wp_shortlink_wp_head' );
} );

add_action( 'wp_enqueue_scripts', function () {
	if ( iam_is_snapshot_request() ) {
		foreach ( array( 'wp-block-library', 'wp-block-library-theme', 'global-styles', 'classic-theme-styles', 'core-block-supports' ) as $h ) {
			wp_dequeue_style( $h );
		}
	}
}, 100 );
