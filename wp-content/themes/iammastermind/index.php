<?php
/**
 * Fallback listing (archives, search, posts index if one is configured).
 *
 * @package iammastermind
 */

get_header();
?>
<main class="bg-white dark:bg-[#020617] selection:bg-[#BBA87A] selection:text-white">
	<section class="pt-40 pb-16 bg-[#020617] text-white">
		<div class="max-w-7xl mx-auto px-4">
			<h1 class="text-4xl md:text-6xl font-black text-white tracking-tighter uppercase leading-none">
				<?php echo esc_html( is_search() ? sprintf( iam_t( 'Search: %s', 'Búsqueda: %s' ), get_search_query() ) : ( is_archive() ? wp_strip_all_tags( get_the_archive_title() ) : iam_t( 'Journal', 'Journal' ) ) ); ?>
			</h1>
		</div>
	</section>
	<section class="max-w-7xl mx-auto px-4 py-20">
		<?php if ( have_posts() ) : ?>
			<div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
				<?php
				while ( have_posts() ) :
					the_post();
					?>
					<a href="<?php the_permalink(); ?>" class="group block rounded-3xl overflow-hidden border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-white/5 hover:border-[#BBA87A] transition-colors">
						<?php if ( has_post_thumbnail() ) : ?>
							<div class="aspect-video overflow-hidden"><?php the_post_thumbnail( 'large', array( 'class' => 'w-full h-full object-cover group-hover:scale-105 transition-transform duration-700' ) ); ?></div>
						<?php endif; ?>
						<div class="p-8">
							<p class="text-[10px] text-[#BBA87A] font-black uppercase tracking-widest mb-3"><?php echo esc_html( get_the_date() ); ?></p>
							<h2 class="text-xl font-black text-slate-900 dark:text-white uppercase tracking-tighter mb-3"><?php the_title(); ?></h2>
							<p class="text-slate-600 dark:text-slate-400 font-light line-clamp-3"><?php echo esc_html( wp_strip_all_tags( get_the_excerpt() ) ); ?></p>
						</div>
					</a>
				<?php endwhile; ?>
			</div>
			<div class="mt-16 flex justify-center gap-4 text-sm font-bold uppercase tracking-widest text-[#BBA87A]">
				<?php posts_nav_link( ' ', iam_t( '&larr; Newer', '&larr; Recientes' ), iam_t( 'Older &rarr;', 'Anteriores &rarr;' ) ); ?>
			</div>
		<?php else : ?>
			<p class="text-slate-600 dark:text-slate-400"><?php echo esc_html( iam_t( 'Nothing found.', 'No se encontró nada.' ) ); ?></p>
		<?php endif; ?>
	</section>
</main>
<?php
get_footer();
