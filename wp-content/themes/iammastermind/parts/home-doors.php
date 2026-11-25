<?php
/**
 * Home: "Where do you want to start?" — three doors (Mind, Body, Research).
 * Inserted after the hero strip by inc/patches.php. Draft copy for review.
 *
 * @package iammastermind
 */

defined( 'ABSPATH' ) || exit;

$iam_cdn   = 'https://res.cloudinary.com/dqaq65lmd/image/upload/f_auto,q_auto/';
$iam_doors = array(
	array(
		'tag'   => iam_t( 'Mind', 'Mente' ),
		'title' => iam_t( 'Coaching', 'Coaching' ),
		'text'  => iam_t( 'Rewire how you think, lead and decide, with daily training and mentorship from Abraham Carranza.', 'Reprograma cómo piensas, lideras y decides, con entrenamiento diario y mentoría de Abraham Carranza.' ),
		'img'   => 'https://res.cloudinary.com/dkvltwyfh/image/upload/f_auto,q_auto/v1779772542/scalig_for_life_gxntnl.jpg',
		'cta'   => iam_t( 'Start with Growth Society', 'Empieza con Growth Society' ),
		'href'  => '/programs/scaling-for-life',
		'more'  => array( iam_t( 'All coaching programs', 'Todos los programas' ), '/programs' ),
	),
	array(
		'tag'   => iam_t( 'Body', 'Cuerpo' ),
		'title' => iam_t( 'Longevity &amp; Retreats', 'Longevidad y Retiros' ),
		'text'  => iam_t( 'Bring your body with you. Biohacking, longevity and six days in the Peruvian Amazon to reset how you live and perform.', 'Lleva tu cuerpo contigo. Biohacking, longevidad y seis días en la Amazonía peruana para transformar cómo vives y rindes.' ),
		'img'   => $iam_cdn . 'v1790401410/mastermind_ascension_retreat_into_the_forest_cwxxll.jpg',
		'cta'   => iam_t( 'Explore the retreats', 'Conoce los retiros' ),
		'href'  => '/retreats',
		'more'  => array( iam_t( 'Longevity — coming soon', 'Longevidad — próximamente' ), '/longevity' ),
	),
	array(
		'tag'   => iam_t( 'Research', 'Investigación' ),
		'title' => iam_t( 'Alexandria R&amp;D Center', 'Centro Alexandria de I+D' ),
		'text'  => iam_t( 'The neuroscience behind the work: brain mapping, EEG technology and neurofeedback training.', 'La neurociencia detrás del trabajo: mapeo cerebral, tecnología EEG y entrenamiento con neurofeedback.' ),
		'img'   => $iam_cdn . 'v1784184895/iammastermind_blog_julio_mas_all_del_coaching_pzwywa.jpg',
		'cta'   => iam_t( 'Visit the center', 'Visita el centro' ),
		'href'  => '/alexandria-research-center',
		'more'  => null,
	),
);
?>
<section id="start-here" class="tw:py-24 tw:md:py-32 tw:bg-white tw:dark:bg-[#020617]">
	<div class="tw:max-w-7xl tw:mx-auto tw:px-4 tw:md:px-8">
		<div class="tw:text-center tw:mb-14 tw:md:mb-20">
			<p class="tw:text-[#BBA87A] tw:font-black tw:text-xs tw:uppercase tw:tracking-[0.3em] tw:mb-4"><?php echo esc_html( iam_t( 'Start here', 'Empieza aquí' ) ); ?></p>
			<h2 class="tw:text-4xl tw:md:text-6xl tw:font-black tw:text-slate-900 tw:dark:text-white tw:tracking-tighter tw:uppercase">
				<?php echo esc_html( iam_t( 'Where do you want', '¿Por dónde quieres' ) ); ?>
				<span class="tw:text-transparent tw:bg-clip-text tw:bg-[linear-gradient(to_right,#C8B88D,#BBA87A,#927E5A)]"><?php echo esc_html( iam_t( 'to start?', 'empezar?' ) ); ?></span>
			</h2>
			<p class="tw:mt-6 tw:max-w-2xl tw:mx-auto tw:text-lg tw:text-slate-600 tw:dark:text-slate-400 tw:font-light tw:leading-relaxed"><?php echo esc_html( iam_t( 'Mind, body and research: three ways into the same work of becoming who you are capable of being.', 'Mente, cuerpo e investigación: tres caminos hacia el mismo trabajo de convertirte en quien eres capaz de ser.' ) ); ?></p>
		</div>
		<div class="tw:grid tw:grid-cols-1 tw:md:grid-cols-3 tw:gap-6 tw:lg:gap-8">
			<?php foreach ( $iam_doors as $iam_d ) : ?>
				<div class="tw:group tw:relative tw:flex tw:flex-col tw:rounded-4xl tw:overflow-hidden tw:bg-slate-900 tw:border tw:border-slate-200 tw:dark:border-white/5 tw:hover:border-[#BBA87A]/50 tw:transition-all tw:duration-700 tw:shadow-lg tw:min-h-[30rem]">
					<img src="<?php echo esc_url( $iam_d['img'] ); ?>" alt="" loading="lazy" class="tw:absolute tw:inset-0 tw:w-full tw:h-full tw:object-cover tw:opacity-40 tw:grayscale tw:group-hover:grayscale-0 tw:group-hover:opacity-60 tw:group-hover:scale-105 tw:transition-all tw:duration-1000">
					<div class="tw:absolute tw:inset-0 tw:bg-[linear-gradient(to_top,#020617_15%,rgba(2,6,23,0.75)_50%,rgba(2,6,23,0.2))]"></div>
					<div class="tw:relative tw:z-10 tw:mt-auto tw:p-8 tw:lg:p-10">
						<span class="tw:inline-block tw:mb-5 tw:px-3 tw:py-1 tw:rounded-full tw:border tw:border-[#BBA87A]/50 tw:bg-[#BBA87A]/15 tw:text-[#BBA87A] tw:text-[10px] tw:font-black tw:uppercase tw:tracking-widest"><?php echo esc_html( $iam_d['tag'] ); ?></span>
						<h3 class="tw:text-3xl tw:font-black tw:text-white tw:uppercase tw:tracking-tighter tw:mb-4"><?php echo $iam_d['title']; // phpcs:ignore -- static, entity-encoded ?></h3>
						<p class="tw:text-slate-300 tw:font-light tw:leading-relaxed tw:mb-8"><?php echo esc_html( $iam_d['text'] ); ?></p>
						<a href="<?php echo esc_url( home_url( $iam_d['href'] ) ); ?>" class="tw:inline-flex tw:items-center tw:gap-2 tw:bg-white tw:text-[#020617] tw:font-black tw:px-6 tw:py-3 tw:rounded-full tw:uppercase tw:tracking-tighter tw:text-sm tw:hover:scale-105 tw:transition-transform"><?php echo esc_html( $iam_d['cta'] ); ?> <span aria-hidden="true">&rarr;</span></a>
						<?php if ( $iam_d['more'] ) : ?>
							<a href="<?php echo esc_url( home_url( $iam_d['more'][1] ) ); ?>" class="tw:block tw:mt-5 tw:text-[#BBA87A] tw:font-bold tw:uppercase tw:tracking-widest tw:text-[11px] tw:hover:underline"><?php echo esc_html( $iam_d['more'][0] ); ?></a>
						<?php endif; ?>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
