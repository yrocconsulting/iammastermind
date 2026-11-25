<?php
/**
 * Longevity (coming soon). Draft copy for Abraham's review.
 * Compliance: lifestyle and technology language only; no treatment claims, no peptides.
 *
 * @package iammastermind
 */

get_header();
$iam_ext = iam_opt( 'longevity_url' );
?>
<main class="tw:bg-white tw:dark:bg-[#020617] selection:bg-[#BBA87A] selection:text-white">
	<?php
	iam_hero( array(
		'badge'   => iam_t( 'Coming soon', 'Próximamente' ),
		'eyebrow' => iam_t( 'Body', 'Cuerpo' ),
		'title'   => iam_t( 'Bring your body', 'Lleva tu cuerpo' ),
		'accent'  => iam_t( 'with you', 'contigo' ),
		'text'    => iam_t( 'Everyone eventually has to work on their health. Our longevity platform brings biohacking and health optimization together with the mindset work that makes change last.', 'Tarde o temprano todos tenemos que trabajar en nuestra salud. Nuestra plataforma de longevidad une el biohacking y la optimización de la salud con el trabajo de mentalidad que hace que el cambio perdure.' ),
		'image'   => 'https://res.cloudinary.com/dqaq65lmd/image/upload/f_auto,q_auto/v1790385363/Peruvian_Amazon_aerial_view_fog_20260925191552_es2ip5.jpg',
		'actions' => array_filter( array(
			array( iam_t( 'Join the waitlist', 'Únete a la lista' ), '#waitlist', true ),
			$iam_ext ? array( iam_t( 'Visit the longevity platform', 'Visita la plataforma' ), $iam_ext, false ) : null,
		) ),
	) );
	?>

	<section class="tw:py-24 tw:md:py-32">
		<div class="tw:max-w-7xl tw:mx-auto tw:px-4 tw:md:px-8">
			<?php iam_heading( iam_t( 'The approach', 'El enfoque' ), iam_t( 'Mind and body,', 'Mente y cuerpo,' ), iam_t( 'one practice', 'una sola práctica' ), iam_t( 'Personal development was always the foundation. Longevity is where it becomes visible: in your energy, your focus and the years you have to enjoy what you build.', 'El desarrollo personal siempre fue la base. La longevidad es donde se vuelve visible: en tu energía, tu enfoque y los años que tienes para disfrutar lo que construyes.' ) ); ?>
			<div class="tw:grid tw:grid-cols-1 tw:md:grid-cols-3 tw:gap-6">
				<?php
				iam_card( 'heart-pulse', iam_t( 'Biohacking', 'Biohacking' ), iam_t( 'Sleep, nutrition, movement and recovery treated as daily practices you can measure and improve.', 'Sueño, nutrición, movimiento y recuperación como prácticas diarias que puedes medir y mejorar.' ) );
				iam_card( 'activity', iam_t( 'Measurement', 'Medición' ), iam_t( 'Wearables and brain-mapping technology from the Alexandria R&D Center show you where you start and how you change.', 'Wearables y tecnología de mapeo cerebral del Centro Alexandria de I+D te muestran dónde empiezas y cómo cambias.' ) );
				iam_card( 'sparkles', iam_t( 'Mindset', 'Mentalidad' ), iam_t( 'The coaching that turns good intentions into lasting habits, so health changes stick.', 'El coaching que convierte las buenas intenciones en hábitos duraderos, para que los cambios de salud perduren.' ) );
				?>
			</div>
		</div>
	</section>

	<section class="tw:py-24 tw:md:py-32 tw:bg-slate-50 tw:dark:bg-[#030712] tw:border-y tw:border-slate-200 tw:dark:border-white/5">
		<div class="tw:max-w-7xl tw:mx-auto tw:px-4 tw:md:px-8 tw:grid tw:grid-cols-1 tw:lg:grid-cols-2 tw:gap-12 tw:items-center">
			<div>
				<?php iam_heading( iam_t( 'Where it comes together', 'Donde todo se une' ), iam_t( 'The', 'El' ), iam_t( 'retreats', 'retiro' ), iam_t( 'Six days in the Peruvian Amazon combine mind, body and research: brain mapping before and after, nervous-system regulation and a 90-day integration plan.', 'Seis días en la Amazonía peruana combinan mente, cuerpo e investigación: mapeo cerebral antes y después, regulación del sistema nervioso y un plan de integración de 90 días.' ), false ); ?>
				<?php iam_button( iam_t( 'Explore the retreats', 'Conoce los retiros' ), '/retreats' ); ?>
			</div>
			<img src="https://res.cloudinary.com/dqaq65lmd/image/upload/f_auto,q_auto/v1790401410/mastermind_ascension_retreat_into_the_forest_cwxxll.jpg" alt="<?php echo esc_attr( iam_t( 'Retreat participants in the Amazon forest', 'Participantes del retiro en la selva amazónica' ) ); ?>" loading="lazy" class="tw:w-full tw:h-96 tw:object-cover tw:rounded-4xl tw:shadow-xl">
		</div>
	</section>

	<section id="waitlist" class="tw:py-24 tw:md:py-32">
		<div class="tw:max-w-3xl tw:mx-auto tw:px-4 tw:md:px-8">
			<?php iam_heading( iam_t( 'Be first to know', 'Sé el primero en saberlo' ), iam_t( 'Join the', 'Únete a la' ), iam_t( 'waitlist', 'lista de espera' ), iam_t( 'We are opening the longevity platform in stages. Leave your details and we will invite you as soon as it launches.', 'Abriremos la plataforma de longevidad por etapas. Déjanos tus datos y te invitaremos en cuanto se lance.' ) ); ?>
			<?php
			iam_lead_form( array(
				'type'    => 'longevity-waitlist',
				'source'  => 'Longevity waitlist',
				'submit'  => iam_t( 'Join the waitlist', 'Unirme a la lista' ),
				'success' => iam_t( "You're on the list. We'll be in touch soon.", 'Ya estás en la lista. Te contactaremos pronto.' ),
				'fields'  => array(
					array( 'name', iam_t( 'Full name', 'Nombre completo' ), 'text', true ),
					array( 'email', iam_t( 'Email', 'Correo electrónico' ), 'email', true ),
					array( 'phone', iam_t( 'Phone (optional)', 'Teléfono (opcional)' ), 'tel', false, null, true ),
				),
			) );
			?>
		</div>
	</section>
	<?php iam_disclaimer(); ?>
</main>
<?php
get_footer();
