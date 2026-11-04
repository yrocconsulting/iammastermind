<?php
/**
 * Default page template for pages built in the WordPress editor.
 *
 * @package iammastermind
 */

get_header();
while ( have_posts() ) :
	the_post();
	?>
<main class="bg-white dark:bg-[#020617] selection:bg-[#BBA87A] selection:text-white">
	<section class="pt-40 pb-20 bg-[#020617] text-white">
		<div class="max-w-4xl mx-auto px-4">
			<h1 class="text-4xl md:text-6xl font-black text-white tracking-tighter uppercase leading-none"><?php the_title(); ?></h1>
		</div>
	</section>
	<article class="max-w-4xl mx-auto px-4 py-20">
		<div class="prose prose-lg md:prose-xl dark:prose-invert prose-slate max-w-none prose-headings:font-black prose-headings:uppercase prose-headings:tracking-tighter prose-p:font-light prose-p:leading-relaxed prose-strong:text-[#BBA87A]">
			<?php the_content(); ?>
		</div>
	</article>
</main>
	<?php
endwhile;
get_footer();
