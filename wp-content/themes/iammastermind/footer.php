<?php
/**
 * Footer for WordPress-native pages.
 *
 * @package iammastermind
 */

echo '</div>'; // closes <div class="flex-1"> opened by the layout header.
echo iam_file( 'layouts/site/' . iam_lang() . '.footer.html' ); // phpcs:ignore WordPress.Security.EscapeOutput -- trusted theme file
wp_footer();
?>
</body></html>
