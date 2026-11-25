<?php
/**
 * Speaker page — lets event hosts book Abraham. Draft copy for review.
 *
 * @package iammastermind
 */

get_header();
?>
<main class="tw:bg-white tw:dark:bg-[#020617] selection:bg-[#BBA87A] selection:text-white">
	<?php
	iam_hero( array(
		'eyebrow' => iam_t( 'Speaking', 'Conferencias' ),
		'title'   => iam_t( 'Book Abraham', 'Invita a Abraham' ),
		'accent'  => iam_t( 'for your stage', 'a tu escenario' ),
		'text'    => iam_t( 'Keynotes, workshops and podcast conversations in English and Spanish on the neuroscience of performance, leadership and building a life that lasts.', 'Conferencias, talleres y podcasts en inglés y español sobre la neurociencia del rendimiento, el liderazgo y cómo construir una vida que perdure.' ),
		'image'   => 'https://res.cloudinary.com/dqaq65lmd/image/upload/f_auto,q_auto/v1778710466/mastermind_coach_abraham_carranza_event_rng2ap.jpg',
		'actions' => array(
			array( iam_t( 'Check availability', 'Consultar disponibilidad' ), '#book', true ),
			array( iam_t( 'About Abraham', 'Sobre Abraham' ), '/abraham-carranza', false ),
		),
	) );
	?>

	<section class="tw:py-24 tw:md:py-32">
		<div class="tw:max-w-7xl tw:mx-auto tw:px-4 tw:md:px-8">
			<?php iam_heading( iam_t( 'Signature talks', 'Conferencias principales' ), iam_t( 'What Abraham', 'De qué' ), iam_t( 'speaks about', 'habla Abraham' ), iam_t( 'Each talk can be delivered as a keynote, a half-day workshop or a full-day intensive, and adapted to your audience.', 'Cada conferencia puede darse como keynote, taller de medio día o intensivo de un día, y adaptarse a tu audiencia.' ) ); ?>
			<div class="tw:grid tw:grid-cols-1 tw:md:grid-cols-3 tw:gap-6">
				<?php
				iam_card( 'activity', iam_t( 'The neuroscience of elite performance', 'La neurociencia del alto rendimiento' ), iam_t( 'What brain mapping reveals about focus, stress and flow, and the daily practices that move high performers from burnout to clarity.', 'Lo que el mapeo cerebral revela sobre el enfoque, el estrés y el flow, y las prácticas diarias que llevan a las personas de alto rendimiento del agotamiento a la claridad.' ) );
				iam_card( 'sparkles', iam_t( 'Reprogram your paradigms', 'Reprograma tus paradigmas' ), iam_t( 'How the beliefs we inherit shape our results, and a practical framework for rewriting them in business and in life.', 'Cómo las creencias que heredamos moldean nuestros resultados, y un marco práctico para reescribirlas en los negocios y en la vida.' ) );
				iam_card( 'heart-pulse', iam_t( 'Bring your body with you', 'Lleva tu cuerpo contigo' ), iam_t( 'Why longevity is the next frontier of leadership, and how to build the energy to enjoy what you are building.', 'Por qué la longevidad es la nueva frontera del liderazgo, y cómo construir la energía para disfrutar lo que estás construyendo.' ) );
				?>
			</div>
		</div>
	</section>

	<section class="tw:py-24 tw:md:py-32 tw:bg-slate-50 tw:dark:bg-[#030712] tw:border-y tw:border-slate-200 tw:dark:border-white/5">
		<div class="tw:max-w-7xl tw:mx-auto tw:px-4 tw:md:px-8 tw:grid tw:grid-cols-1 tw:md:grid-cols-2 tw:lg:grid-cols-4 tw:gap-6">
			<?php
			$iam_formats = array(
				array( 'mic', iam_t( 'Keynotes', 'Keynotes' ), iam_t( 'Conferences, summits and company events.', 'Congresos, cumbres y eventos corporativos.' ) ),
				array( 'users', iam_t( 'Workshops', 'Talleres' ), iam_t( 'Interactive sessions for leadership teams.', 'Sesiones interactivas para equipos directivos.' ) ),
				array( 'radio', iam_t( 'Podcasts & media', 'Podcasts y medios' ), iam_t( 'Interviews in English or Spanish.', 'Entrevistas en inglés o español.' ) ),
				array( 'compass', iam_t( 'Retreat experiences', 'Experiencias de retiro' ), iam_t( 'Private retreats for teams and groups.', 'Retiros privados para equipos y grupos.' ) ),
			);
			foreach ( $iam_formats as $iam_f ) {
				iam_card( $iam_f[0], $iam_f[1], $iam_f[2] );
			}
			?>
		</div>
	</section>

	<section id="book" class="tw:scroll-mt-32 tw:py-24 tw:md:py-32">
		<div class="tw:max-w-3xl tw:mx-auto tw:px-4 tw:md:px-8">
			<?php iam_heading( iam_t( 'Booking', 'Reservas' ), iam_t( 'Check', 'Consulta' ), iam_t( 'availability', 'disponibilidad' ), iam_t( 'Tell us about your event and our team will reply within two business days.', 'Cuéntanos sobre tu evento y nuestro equipo responderá en un plazo de dos días hábiles.' ) ); ?>
			<?php
			iam_lead_form( array(
				'type'    => 'speaker',
				'source'  => 'Speaker booking',
				'submit'  => iam_t( 'Send booking request', 'Enviar solicitud' ),
				'success' => iam_t( 'Thank you. We will reply within two business days.', 'Gracias. Responderemos en un plazo de dos días hábiles.' ),
				'fields'  => array(
					array( 'name', iam_t( 'Full name', 'Nombre completo' ), 'text', true ),
					array( 'email', iam_t( 'Email', 'Correo electrónico' ), 'email', true ),
					array( 'organization', iam_t( 'Organization', 'Organización' ), 'text', true ),
					array( 'phone', iam_t( 'Phone', 'Teléfono' ), 'tel', false ),
					array( 'event_date', iam_t( 'Event date', 'Fecha del evento' ), 'date', false ),
					array( 'event_location', iam_t( 'Location (city or virtual)', 'Ubicación (ciudad o virtual)' ), 'text', false ),
					array( 'format', iam_t( 'Format', 'Formato' ), 'select', true, array( iam_t( 'Keynote', 'Keynote' ), iam_t( 'Workshop', 'Taller' ), iam_t( 'Podcast / interview', 'Podcast / entrevista' ), iam_t( 'Retreat experience', 'Experiencia de retiro' ), iam_t( 'Other', 'Otro' ) ) ),
					array( 'audience_size', iam_t( 'Audience size', 'Tamaño de la audiencia' ), 'text', false, null, true ),
					array( 'message', iam_t( 'About your event', 'Sobre tu evento' ), 'textarea', false ),
				),
			) );
			?>
		</div>
	</section>
</main>
<?php
get_footer();
