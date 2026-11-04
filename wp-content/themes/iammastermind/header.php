<?php
/**
 * Header for WordPress-native pages (pages/posts with their own content, archives, 404).
 * Uses the same <head>, navigation and assets as the snapshots so new content matches.
 *
 * @package iammastermind
 */

$iam_lang = iam_lang();
$iam_meta = iam_layout_meta( 'site' );
?><!DOCTYPE html><html lang="<?php echo esc_attr( $iam_lang ); ?>" class="<?php echo esc_attr( $iam_meta['html_class'] ); ?>"> <head>
<?php
echo iam_file( "layouts/site/{$iam_lang}.head.html" ); // phpcs:ignore WordPress.Security.EscapeOutput -- trusted theme file
if ( is_singular() && has_excerpt() ) {
	echo '<meta name="description" content="' . esc_attr( wp_strip_all_tags( get_the_excerpt() ) ) . '">' . "\n";
}
iam_asset_base_script();
wp_head();
?>
</head> <body class="<?php echo esc_attr( $iam_meta['body_class'] ); ?>">
<?php
wp_body_open();
echo iam_file( "layouts/site/{$iam_lang}.header.html" ); // phpcs:ignore -- ends with an open <div class="flex-1">
