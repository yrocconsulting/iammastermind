<?php
/**
 * Home: "Longevity — coming soon" card under the program cards.
 * Links out to the telehealth site once its URL is set (Settings > I AM Mastermind),
 * otherwise to the on-site Longevity waitlist page. Draft copy for review.
 *
 * @package iammastermind
 */

defined( 'ABSPATH' ) || exit;

$iam_ext = iam_opt( 'longevity_url' );
?>
<div class="tw:mt-8 tw:relative tw:overflow-hidden tw:rounded-4xl tw:border tw:border-[#BBA87A]/30 tw:bg-[#020617] tw:shadow-lg">
	<img src="https://res.cloudinary.com/dqaq65lmd/image/upload/f_auto,q_auto/v1790385363/Peruvian_Amazon_aerial_view_fog_20260925191552_es2ip5.jpg" alt="" loading="lazy" class="tw:absolute tw:inset-0 tw:w-full tw:h-full tw:object-cover tw:opacity-25">
	<div class="tw:absolute tw:inset-0 tw:bg-[linear-gradient(to_right,#020617_35%,rgba(2,6,23,0.6))]"></div>
	<div class="tw:relative tw:z-10 tw:grid tw:grid-cols-1 tw:lg:grid-cols-[1fr_auto] tw:gap-8 tw:items-center tw:p-10 tw:lg:p-14">
		<div>
			<div class="tw:flex tw:items-center tw:gap-3 tw:mb-5">
				<span class="tw:px-3 tw:py-1 tw:rounded-full tw:bg-[#BBA87A] tw:text-[#020617] tw:text-[10px] tw:font-black tw:uppercase tw:tracking-widest"><?php echo esc_html( iam_t( 'Coming soon', 'Próximamente' ) ); ?></span>
				<span class="tw:text-[#BBA87A] tw:text-[10px] tw:font-black tw:uppercase tw:tracking-widest"><?php echo esc_html( iam_t( 'Body', 'Cuerpo' ) ); ?></span>
			</div>
			<h3 class="tw:text-3xl tw:md:text-5xl tw:font-black tw:text-white tw:uppercase tw:tracking-tighter tw:mb-4"><?php echo esc_html( iam_t( 'Longevity', 'Longevidad' ) ); ?></h3>
			<p class="tw:max-w-2xl tw:text-slate-300 tw:font-light tw:leading-relaxed"><?php echo esc_html( iam_t( 'Everyone eventually has to work on their health. Our longevity platform brings biohacking and health optimization together with the mindset work that makes change last.', 'Tarde o temprano todos tenemos que trabajar en nuestra salud. Nuestra plataforma de longevidad une el biohacking y la optimización de la salud con el trabajo de mentalidad que hace que el cambio perdure.' ) ); ?></p>
		</div>
		<div class="tw:flex tw:flex-col tw:sm:flex-row tw:lg:flex-col tw:gap-3">
			<a href="<?php echo esc_url( home_url( '/longevity' ) ); ?>" class="tw:inline-flex tw:justify-center tw:items-center tw:gap-2 tw:bg-white tw:text-[#020617] tw:font-black tw:px-7 tw:py-4 tw:rounded-full tw:uppercase tw:tracking-tighter tw:text-sm tw:hover:scale-105 tw:transition-transform"><?php echo esc_html( iam_t( 'Join the waitlist', 'Únete a la lista' ) ); ?></a>
			<?php if ( $iam_ext ) : ?>
				<a href="<?php echo esc_url( $iam_ext ); ?>" target="_blank" rel="noopener" class="tw:inline-flex tw:justify-center tw:items-center tw:gap-2 tw:border tw:border-white/30 tw:text-white tw:font-bold tw:px-7 tw:py-4 tw:rounded-full tw:uppercase tw:tracking-tighter tw:text-sm tw:hover:border-[#BBA87A] tw:transition-colors"><?php echo esc_html( iam_t( 'Visit the longevity platform', 'Visita la plataforma de longevidad' ) ); ?> <span aria-hidden="true">&nearr;</span></a>
			<?php endif; ?>
		</div>
	</div>
</div>
