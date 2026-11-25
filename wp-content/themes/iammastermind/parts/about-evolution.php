<?php
/**
 * About page: addition to "Our Story" covering the neuroscience work and
 * "bringing your body with you" (2026-11-25 call). Draft copy for review.
 *
 * @package iammastermind
 */

defined( 'ABSPATH' ) || exit;
?>
<section id="evolution" class="tw:py-24 tw:md:py-32 tw:bg-white tw:dark:bg-[#020617]">
	<div class="tw:max-w-7xl tw:mx-auto tw:px-4 tw:md:px-8 tw:grid tw:grid-cols-1 tw:lg:grid-cols-2 tw:gap-12 tw:lg:gap-20 tw:items-center">
		<div>
			<p class="tw:text-[#BBA87A] tw:font-black tw:text-xs tw:uppercase tw:tracking-[0.3em] tw:mb-4"><?php echo esc_html( iam_t( 'Our story continues', 'Nuestra historia continúa' ) ); ?></p>
			<h2 class="tw:text-4xl tw:md:text-5xl tw:font-black tw:text-slate-900 tw:dark:text-white tw:uppercase tw:tracking-tighter tw:leading-none tw:mb-8">
				<?php echo esc_html( iam_t( 'From mindset to', 'De la mentalidad a la' ) ); ?>
				<span class="<?php echo esc_attr( IAM_GOLD_TEXT ); ?>"><?php echo esc_html( iam_t( 'measurable change', 'transformación medible' ) ); ?></span>
			</h2>
			<div class="tw:space-y-5 tw:text-lg tw:text-slate-600 tw:dark:text-slate-400 tw:font-light tw:leading-relaxed">
				<p><?php echo esc_html( iam_t( 'Reprogramming the mind was always the foundation. The next step was to see that change, not only feel it. That is why we founded the Alexandria Research & Development Center, bringing brain mapping and neurofeedback technology into our coaching and retreats.', 'Reprogramar la mente siempre fue la base. El siguiente paso era ver ese cambio, no solo sentirlo. Por eso fundamos el Centro Alexandria de Investigación y Desarrollo, llevando el mapeo cerebral y la tecnología de neurofeedback a nuestro coaching y retiros.' ) ); ?></p>
				<p><?php echo esc_html( iam_t( 'And the mind does not travel alone. To live the life you are building, you have to bring your body with you. Our retreats and our upcoming longevity platform add health, recovery and biohacking to the work, so you have the energy and the years to enjoy what you create.', 'Y la mente no viaja sola. Para vivir la vida que estás construyendo, tienes que llevar tu cuerpo contigo. Nuestros retiros y nuestra próxima plataforma de longevidad suman salud, recuperación y biohacking al trabajo, para que tengas la energía y los años para disfrutar lo que creas.' ) ); ?></p>
			</div>
			<div class="tw:mt-10 tw:flex tw:flex-wrap tw:gap-3">
				<?php iam_button( iam_t( 'Alexandria R&D Center', 'Centro Alexandria de I+D' ), '/alexandria-research-center' ); ?>
				<?php iam_button( iam_t( 'Longevity', 'Longevidad' ), '/longevity', false ); ?>
			</div>
		</div>
		<div class="tw:grid tw:grid-cols-2 tw:gap-4">
			<img src="https://res.cloudinary.com/dqaq65lmd/image/upload/f_auto,q_auto/v1784184895/iammastermind_blog_julio_mas_all_del_coaching_pzwywa.jpg" alt="" loading="lazy" class="tw:w-full tw:h-80 tw:object-cover tw:rounded-3xl tw:shadow-lg">
			<img src="https://res.cloudinary.com/dqaq65lmd/image/upload/f_auto,q_auto/v1790401410/mastermind_ascension_retreat_into_the_forest_cwxxll.jpg" alt="" loading="lazy" class="tw:w-full tw:h-80 tw:object-cover tw:rounded-3xl tw:shadow-lg tw:mt-12">
		</div>
	</div>
</section>
