<?php
/**
 * Blog article - mirrors the original /blog/<slug> design.
 *
 * @package iammastermind
 */

get_header();
while ( have_posts() ) :
	the_post();
	$iam_cats  = get_the_category();
	$iam_words = str_word_count( wp_strip_all_tags( get_the_content() ) );
	$iam_mins  = max( 1, (int) ceil( $iam_words / 200 ) );
	$iam_img   = get_the_post_thumbnail_url( null, 'full' );
	?>
<main class="bg-white dark:bg-[#020617] selection:bg-[#BBA87A] selection:text-white">
	<header class="relative h-[70vh] min-h-125 flex items-end pb-16 overflow-hidden">
		<?php if ( $iam_img ) : ?>
			<img src="<?php echo esc_url( $iam_img ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>" class="absolute inset-0 w-full h-full object-cover">
		<?php else : ?>
			<div class="absolute inset-0 bg-[#020617]"></div>
		<?php endif; ?>
		<div class="absolute inset-0 bg-linear-to-t from-[#020617] via-[#020617]/60 to-transparent"></div>
		<div class="max-w-4xl mx-auto px-4 relative z-10 w-full">
			<a href="<?php echo esc_url( home_url( '/blog' ) ); ?>" class="inline-flex items-center gap-2 text-[#BBA87A] font-bold text-xs uppercase tracking-widest mb-8 hover:-translate-x-1 transition-transform">
				<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" class="lucide lucide-arrow-left w-4 h-4"><path d="m12 19-7-7 7-7"></path><path d="M19 12H5"></path></svg>
				<?php echo esc_html( iam_t( 'Back to Journal', 'Volver al Journal' ) ); ?>
			</a>
			<div class="flex items-center gap-3 mb-6">
				<?php if ( $iam_cats ) : ?>
					<span class="px-3 py-1 bg-[#BBA87A] text-[#020617] text-[10px] font-black uppercase tracking-widest rounded-full"><?php echo esc_html( $iam_cats[0]->name ); ?></span>
				<?php endif; ?>
				<span class="text-slate-300 text-xs font-light flex items-center gap-1.5">
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" class="lucide lucide-clock w-4 h-4"><circle cx="12" cy="12" r="10"></circle><path d="M12 6v6l4 2"></path></svg>
					<?php echo esc_html( $iam_mins . ' ' . iam_t( 'min read', 'min de lectura' ) ); ?>
				</span>
			</div>
			<h1 class="text-4xl md:text-6xl font-black text-white tracking-tighter uppercase leading-none mb-6"><?php the_title(); ?></h1>
			<div class="flex items-center gap-6 border-t border-white/10 pt-8">
				<div class="flex items-center gap-3">
					<div class="w-12 h-12 rounded-full bg-white/5 border border-white/10 flex items-center justify-center">
						<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" class="lucide lucide-user w-6 h-6 text-[#BBA87A]"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
					</div>
					<div>
						<p class="text-[10px] text-slate-400 uppercase tracking-widest"><?php echo esc_html( iam_t( 'By', 'Por' ) ); ?></p>
						<p class="text-sm font-bold text-white"><?php the_author(); ?></p>
					</div>
				</div>
				<div class="hidden sm:block">
					<p class="text-[10px] text-slate-400 uppercase tracking-widest"><?php echo esc_html( iam_t( 'Published', 'Publicado' ) ); ?></p>
					<p class="text-sm font-bold text-white"><?php echo esc_html( get_the_date() ); ?></p>
				</div>
			</div>
		</div>
	</header>
	<article class="max-w-7xl mx-auto px-4 py-20">
		<div class="flex flex-col lg:flex-row gap-16">
			<aside class="hidden lg:block w-16 shrink-0">
				<div class="sticky top-32 flex flex-col gap-4">
					<button class="share-btn w-12 h-12 rounded-full border border-slate-200 dark:border-white/10 flex items-center justify-center text-slate-400 hover:text-[#BBA87A] hover:border-[#BBA87A] transition-all group" title="Share Article">
						<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" class="lucide lucide-share-2 w-5 h-5 group-hover:scale-110 transition-transform"><circle cx="18" cy="5" r="3"></circle><circle cx="6" cy="12" r="3"></circle><circle cx="18" cy="19" r="3"></circle><line x1="8.59" x2="15.42" y1="13.51" y2="17.49"></line><line x1="15.41" x2="8.59" y1="6.51" y2="10.49"></line></svg>
					</button>
				</div>
			</aside>
			<div class="flex-1 min-w-0">
				<div class="prose prose-lg md:prose-xl dark:prose-invert prose-slate max-w-none
					prose-headings:text-slate-900 dark:prose-headings:text-white
					prose-headings:font-black prose-headings:uppercase prose-headings:tracking-tighter
					prose-p:text-slate-600 dark:prose-p:text-slate-400 prose-p:font-light prose-p:leading-relaxed
					prose-strong:text-[#BBA87A] prose-strong:font-bold
					prose-h3:text-2xl md:prose-h3:text-3xl
					prose-blockquote:border-[#BBA87A] prose-blockquote:bg-slate-50 dark:prose-blockquote:bg-white/5
					prose-blockquote:py-2 prose-blockquote:rounded-r-2xl">
					<?php the_content(); ?>
				</div>
				<div class="mt-20 pt-10 border-t border-slate-200 dark:border-white/10 flex flex-col md:flex-row items-center justify-between gap-8">
					<div class="flex items-center gap-4">
						<h4 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-widest"><?php echo esc_html( iam_t( 'Share this shift:', 'Comparte este cambio:' ) ); ?></h4>
						<div class="flex gap-2">
							<button class="share-btn p-3 rounded-full bg-slate-100 dark:bg-white/5 text-slate-500 hover:text-[#BBA87A] transition-colors" title="Share Article"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" class="lucide lucide-share-2 w-5 h-5"><circle cx="18" cy="5" r="3"></circle><circle cx="6" cy="12" r="3"></circle><circle cx="18" cy="19" r="3"></circle><line x1="8.59" x2="15.42" y1="13.51" y2="17.49"></line><line x1="15.41" x2="8.59" y1="6.51" y2="10.49"></line></svg></button>
						</div>
					</div>
					<a href="<?php echo esc_url( home_url( '/contact' ) ); ?>" class="bg-[#020617] dark:bg-white text-white dark:text-[#020617] font-black px-8 py-4 rounded-full uppercase tracking-tighter text-sm hover:scale-105 transition-transform"><?php echo esc_html( iam_t( 'Speak with a Coach', 'Habla con un Coach' ) ); ?></a>
				</div>
			</div>
			<div class="hidden xl:block w-64 shrink-0"></div>
		</div>
	</article>
</main>
	<?php
	// Share-button behaviour from the original article page (ends with the </div> closing .flex-1).
	$iam_scripts = iam_file( 'layouts/site/' . iam_lang() . '.post-scripts.html' );
	echo preg_replace( '#\s*</div>\s*$#', '', $iam_scripts ); // phpcs:ignore WordPress.Security.EscapeOutput -- trusted theme file
endwhile;
get_footer();
