<?php
/**
 * New pages built in the theme (not copies of the original site).
 * Each has a template page-<slug>.php; theme setup creates the WordPress Page.
 *
 * @package iammastermind
 */

defined( 'ABSPATH' ) || exit;

/** slug => [title [en, es], description [en, es]] */
function iam_native_pages() {
	return array(
		'longevity'                  => array(
			array( 'Longevity', 'Longevidad' ),
			array( 'Health, biohacking and longevity from I AM Mastermind. Our longevity platform is launching soon — join the waitlist.', 'Salud, biohacking y longevidad de I AM Mastermind. Nuestra plataforma de longevidad llega pronto: únete a la lista de espera.' ),
		),
		'alexandria-research-center' => array(
			array( 'Alexandria Research & Development Center', 'Centro Alexandria de Investigación y Desarrollo' ),
			array( 'The research arm of I AM Mastermind: brain mapping with QEEG/EEG technology and at-home neurofeedback training.', 'El brazo de investigación de I AM Mastermind: mapeo cerebral con tecnología QEEG/EEG y entrenamiento con neurofeedback en casa.' ),
		),
		'abraham-carranza'           => array(
			array( 'Abraham Carranza', 'Abraham Carranza' ),
			array( 'Abraham Carranza is an entrepreneur, coach, speaker and the founder of I AM Mastermind and the Alexandria Research & Development Center.', 'Abraham Carranza es emprendedor, coach, conferencista y fundador de I AM Mastermind y del Centro Alexandria de Investigación y Desarrollo.' ),
		),
		'speaker'                    => array(
			array( 'Book Abraham Carranza to Speak', 'Invita a Abraham Carranza a Hablar' ),
			array( 'Keynotes, workshops and podcast appearances in English and Spanish on neuroscience, leadership and human performance.', 'Conferencias, talleres y podcasts en inglés y español sobre neurociencia, liderazgo y alto rendimiento.' ),
		),
	);
}

/** Registry entry for the current request, or null. */
function iam_current_native_page() {
	if ( ! is_page() ) {
		return null;
	}
	$slug = get_post_field( 'post_name', get_queried_object_id() );
	$all  = iam_native_pages();
	return isset( $all[ $slug ] ) ? $all[ $slug ] : null;
}

// "<Title> | I AM Mastermind" in the visitor's language, matching the original site.
add_filter( 'document_title_separator', function () {
	return '|';
} );
add_filter( 'document_title_parts', function ( $parts ) {
	$p = iam_current_native_page();
	if ( $p ) {
		$parts['title'] = wp_specialchars_decode( iam_l( $p[0] ) );
	}
	$parts['site'] = 'I AM Mastermind';
	unset( $parts['tagline'] );
	return $parts;
} );

/** Meta description for native pages (printed by header.php). */
function iam_meta_description() {
	$p = iam_current_native_page();
	if ( $p ) {
		return iam_l( $p[1] );
	}
	return is_singular() && has_excerpt() ? wp_strip_all_tags( get_the_excerpt() ) : '';
}

// Create the pages (and any other setup) whenever the theme version changes.
add_action( 'admin_init', function () {
	if ( get_option( 'iam_setup_version' ) !== IAM_THEME_VERSION && current_user_can( 'manage_options' ) ) {
		iam_setup_site();
	}
} );
