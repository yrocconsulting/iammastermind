<?php
/**
 * Additions to snapshot pages, inserted at render time.
 *
 * The snapshots stay exact copies of the original site (and can be re-synced);
 * new sections live in parts/*.php and are slotted in at anchor points here.
 * If an anchor is not found (e.g. after a re-sync changed the markup), the
 * addition is skipped and an HTML comment marks it, so nothing breaks.
 *
 * @package iammastermind
 */

defined( 'ABSPATH' ) || exit;

/** Render a template part to a string. */
function iam_part( $name ) {
	ob_start();
	include IAM_THEME_DIR . '/parts/' . $name . '.php';
	return ob_get_clean();
}

/**
 * Insert points per snapshot key. Each patch:
 *   'before' => exact substring to insert before (first occurrence), or
 *   'after_div' => [ container start substring, div start substring (nth match) ]:
 *                  insert after the end of that <div> element.
 */
function iam_snapshot_patches() {
	return apply_filters( 'iam_snapshot_patches', array(
		'index' => array(
			array( 'part' => 'home-doors', 'before' => '<section class="py-32 relative bg-[#020617] overflow-hidden">' ),
			array( 'part' => 'home-longevity', 'after_div' => array( '<section id="programs"', '<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">' ) ),
		),
		'about' => array(
			// After "Our Story", before the Mastermind Principle quote.
			array( 'part' => 'about-evolution', 'before' => '<section class="py-32 md:py-40 relative bg-[#020617] overflow-hidden">' ),
		),
	) );
}

/** End offset of the <div> element starting at $start (balanced div counting). */
function iam_div_end( $html, $start ) {
	$depth = 0;
	if ( ! preg_match_all( '#<(/?)div\b#', $html, $m, PREG_OFFSET_CAPTURE, $start ) ) {
		return false;
	}
	foreach ( $m[1] as $i => $slash ) {
		$depth += '' === $slash[0] ? 1 : -1;
		if ( 0 === $depth ) {
			return strpos( $html, '>', $m[0][ $i ][1] ) + 1;
		}
	}
	return false;
}

/** Apply this page's patches to its <main> HTML. */
function iam_apply_patches( $key, $html ) {
	$all = iam_snapshot_patches();
	if ( empty( $all[ $key ] ) ) {
		return $html;
	}
	foreach ( $all[ $key ] as $p ) {
		$pos = false;
		if ( isset( $p['before'] ) ) {
			$pos = strpos( $html, $p['before'] );
		} elseif ( isset( $p['after_div'] ) ) {
			$c = strpos( $html, $p['after_div'][0] );
			$d = false === $c ? false : strpos( $html, $p['after_div'][1], $c );
			$pos = false === $d ? false : iam_div_end( $html, $d );
		}
		if ( false === $pos ) {
			$html .= "\n<!-- iam patch '{$p['part']}' skipped: anchor not found -->";
			continue;
		}
		$html = substr( $html, 0, $pos ) . iam_part( $p['part'] ) . substr( $html, $pos );
	}
	return $html;
}
