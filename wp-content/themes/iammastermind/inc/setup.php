<?php
/**
 * One-time site setup: creates a WordPress Page for every original URL (content left
 * empty so the snapshot shows), sets the static front page and /blog/%postname%/
 * permalinks. Runs on theme activation; re-run with `wp iam setup` or
 * Settings > I AM Mastermind is not needed - it is idempotent.
 *
 * @package iammastermind
 */

defined( 'ABSPATH' ) || exit;

function iam_setup_site() {
	$created = 0;
	$keys    = array_keys( iam_snapshot_manifest() );
	sort( $keys ); // parents before children.
	foreach ( $keys as $key ) {
		// Blog articles stay snapshot-only; new articles are regular Posts.
		if ( 0 === strpos( $key, 'blog/' ) ) {
			continue;
		}
		$path = 'index' === $key ? 'home' : $key;
		if ( get_page_by_path( $path ) ) {
			continue;
		}
		$parent = 0;
		if ( false !== strpos( $path, '/' ) ) {
			$pp = get_page_by_path( dirname( $path ) );
			if ( ! $pp ) {
				continue;
			}
			$parent = $pp->ID;
		}
		$id = wp_insert_post( array(
			'post_type'    => 'page',
			'post_status'  => 'publish',
			'post_title'   => iam_snapshot_title( $key ),
			'post_name'    => basename( $path ),
			'post_parent'  => $parent,
			'post_content' => '',
		) );
		if ( $id && ! is_wp_error( $id ) ) {
			$created++;
		}
	}

	$home = get_page_by_path( 'home' );
	if ( $home ) {
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $home->ID );
	}
	if ( ! get_option( 'permalink_structure' ) || '/%postname%/' === get_option( 'permalink_structure' ) ) {
		update_option( 'permalink_structure', '/blog/%postname%/' );
	}
	flush_rewrite_rules();
	update_option( 'iam_setup_version', IAM_THEME_VERSION );
	return $created;
}

/** Page title from the English snapshot's <title>, minus the brand suffix. */
function iam_snapshot_title( $key ) {
	$head = iam_file( "snapshots/en/{$key}.head.html" );
	if ( preg_match( '#<title>(.*?)</title>#s', $head, $m ) ) {
		$t = html_entity_decode( trim( $m[1] ), ENT_QUOTES );
		$t = preg_replace( '#\s*\|\s*(I AM Mastermind|The Mastermind Ascension)\s*$#i', '', $t );
		if ( 'index' === $key ) {
			return 'Home';
		}
		return $t ?: ucwords( str_replace( '-', ' ', basename( $key ) ) );
	}
	return ucwords( str_replace( '-', ' ', basename( $key ) ) );
}

add_action( 'after_switch_theme', 'iam_setup_site' );

if ( defined( 'WP_CLI' ) && WP_CLI ) {
	WP_CLI::add_command( 'iam setup', function () {
		$n = iam_setup_site();
		WP_CLI::success( "Setup complete; {$n} pages created." );
	} );
}
