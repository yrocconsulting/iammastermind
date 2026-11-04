<?php
/**
 * Not found.
 *
 * @package iammastermind
 */

get_header();
?>
<main class="bg-[#020617] text-white selection:bg-[#BBA87A] selection:text-white">
	<section class="min-h-screen flex items-center justify-center px-4 pt-40 pb-20 text-center">
		<div class="max-w-4xl mx-auto">
			<p class="text-[#BBA87A] font-black text-xs uppercase tracking-widest mb-6">404</p>
			<h1 class="text-4xl md:text-7xl font-black tracking-tighter uppercase leading-none mb-8"><?php echo esc_html( iam_t( 'This path does not exist', 'Esta ruta no existe' ) ); ?></h1>
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="inline-block bg-white text-[#020617] font-black px-8 py-4 rounded-full uppercase tracking-tighter text-sm hover:scale-105 transition-transform"><?php echo esc_html( iam_t( 'Back to home', 'Volver al inicio' ) ); ?></a>
		</div>
	</section>
</main>
<?php
get_footer();
