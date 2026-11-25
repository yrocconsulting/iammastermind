<?php
/**
 * Alexandria Research & Development Center. Built fresh (per the 2026-11-25 call),
 * draft copy for Abraham's review. Technology language only: no medical or
 * treatment claims; partners and software are not named until approved.
 *
 * @package iammastermind
 */

get_header();
?>
<main class="tw:bg-white tw:dark:bg-[#020617] selection:bg-[#BBA87A] selection:text-white">
	<?php
	iam_hero( array(
		'eyebrow' => iam_t( 'Research', 'Investigación' ),
		'title'   => iam_t( 'Alexandria Research &', 'Centro Alexandria de' ),
		'accent'  => iam_t( 'Development Center', 'Investigación y Desarrollo' ),
		'text'    => iam_t( 'The research arm of I AM Mastermind. We study how the brain changes when people train their mind, regulate their body and step into new environments, and we turn what we learn into better programs.', 'El brazo de investigación de I AM Mastermind. Estudiamos cómo cambia el cerebro cuando las personas entrenan su mente, regulan su cuerpo y entran en nuevos entornos, y convertimos lo que aprendemos en mejores programas.' ),
		'image'   => 'https://res.cloudinary.com/dqaq65lmd/image/upload/f_auto,q_auto/v1784184895/iammastermind_blog_julio_mas_all_del_coaching_pzwywa.jpg',
		'actions' => array(
			array( iam_t( 'Request information', 'Solicitar información' ), '#contact-research', true ),
			array( iam_t( 'Experience it at a retreat', 'Vívelo en un retiro' ), '/retreats', false ),
		),
	) );
	?>

	<section class="tw:py-24 tw:md:py-32">
		<div class="tw:max-w-7xl tw:mx-auto tw:px-4 tw:md:px-8">
			<?php iam_heading( iam_t( 'Our focus', 'Nuestro enfoque' ), iam_t( 'Measure what', 'Medir lo que' ), iam_t( 'changes', 'cambia' ), iam_t( 'Coaching has always been judged by how people feel. We add a second lens: what the technology can show about focus, calm and the nervous system before and after the work.', 'El coaching siempre se ha medido por cómo se sienten las personas. Nosotros añadimos un segundo lente: lo que la tecnología puede mostrar sobre el enfoque, la calma y el sistema nervioso antes y después del trabajo.' ) ); ?>
			<div class="tw:grid tw:grid-cols-1 tw:md:grid-cols-3 tw:gap-6">
				<?php
				iam_card( 'activity', iam_t( 'Brain mapping', 'Mapeo cerebral' ), iam_t( 'QEEG and EEG technology records the brain\'s electrical activity and turns it into a visual map: a baseline you can compare against over time.', 'La tecnología QEEG y EEG registra la actividad eléctrica del cerebro y la convierte en un mapa visual: una línea base con la que comparar a lo largo del tiempo.' ) );
				iam_card( 'radio', iam_t( 'Neurofeedback training', 'Entrenamiento con neurofeedback' ), iam_t( 'Real-time feedback on brain activity lets you practice states of focus and calm, the same principle used by consumer EEG headsets.', 'La retroalimentación en tiempo real de la actividad cerebral te permite practicar estados de enfoque y calma, el mismo principio que usan los dispositivos EEG de consumo.' ) );
				iam_card( 'heart-pulse', iam_t( 'Body signals', 'Señales del cuerpo' ), iam_t( 'Heart rate variability and other wearable data show how the nervous system responds to training, rest and new environments.', 'La variabilidad de la frecuencia cardíaca y otros datos de wearables muestran cómo responde el sistema nervioso al entrenamiento, al descanso y a nuevos entornos.' ) );
				?>
			</div>
		</div>
	</section>

	<section id="brain-mapping" class="tw:scroll-mt-32 tw:py-24 tw:md:py-32 tw:bg-slate-50 tw:dark:bg-[#030712] tw:border-y tw:border-slate-200 tw:dark:border-white/5">
		<div class="tw:max-w-7xl tw:mx-auto tw:px-4 tw:md:px-8 tw:grid tw:grid-cols-1 tw:lg:grid-cols-2 tw:gap-12 tw:items-center">
			<div>
				<?php iam_heading( iam_t( 'QEEG / EEG', 'QEEG / EEG' ), iam_t( 'Brain', 'Mapeo' ), iam_t( 'mapping', 'cerebral' ), '', false ); ?>
				<div class="tw:space-y-5 tw:text-lg tw:text-slate-600 tw:dark:text-slate-400 tw:font-light tw:leading-relaxed">
					<p><?php echo esc_html( iam_t( 'A brain map is a snapshot of how your brain is working right now. Sensors on the scalp record electrical activity, and software compares the patterns across different regions and frequencies.', 'Un mapa cerebral es una fotografía de cómo está funcionando tu cerebro en este momento. Sensores en el cuero cabelludo registran la actividad eléctrica y un software compara los patrones entre distintas regiones y frecuencias.' ) ); ?></p>
					<p><?php echo esc_html( iam_t( 'We use it to set a starting point and to see what shifts after coaching, a retreat or a training block. It is a way to learn about yourself, not a medical test.', 'Lo usamos para establecer un punto de partida y ver qué cambia después del coaching, un retiro o un bloque de entrenamiento. Es una forma de conocerte, no una prueba médica.' ) ); ?></p>
				</div>
			</div>
			<img src="https://res.cloudinary.com/dqaq65lmd/image/upload/f_auto,q_auto/v1790382476/mastermind_ascension_retreat_protocol_oej7bn.jpg" alt="" loading="lazy" class="tw:w-full tw:h-96 tw:object-cover tw:rounded-4xl tw:shadow-xl">
		</div>
	</section>

	<section id="neurofeedback" class="tw:scroll-mt-32 tw:py-24 tw:md:py-32">
		<div class="tw:max-w-7xl tw:mx-auto tw:px-4 tw:md:px-8 tw:grid tw:grid-cols-1 tw:lg:grid-cols-2 tw:gap-12 tw:items-center">
			<img src="https://res.cloudinary.com/dqaq65lmd/image/upload/f_auto,q_auto/v1790385102/mastermind_ascension_retreat_assitant_xrx5yw.jpg" alt="" loading="lazy" class="tw:order-last tw:lg:order-first tw:w-full tw:h-96 tw:object-cover tw:rounded-4xl tw:shadow-xl">
			<div>
				<?php iam_heading( iam_t( 'Training that continues', 'Entrenamiento que continúa' ), iam_t( 'At-home', 'Neurofeedback' ), iam_t( 'neurofeedback', 'en casa' ), '', false ); ?>
				<div class="tw:space-y-5 tw:text-lg tw:text-slate-600 tw:dark:text-slate-400 tw:font-light tw:leading-relaxed">
					<p><?php echo esc_html( iam_t( 'Neurofeedback turns brain activity into simple sounds or visuals in real time, so you can practice the states you want more of: calm attention, steady focus, recovery.', 'El neurofeedback convierte la actividad cerebral en sonidos o imágenes simples en tiempo real, para que practiques los estados que quieres cultivar: atención en calma, enfoque estable, recuperación.' ) ); ?></p>
					<p><?php echo esc_html( iam_t( 'With at-home equipment, the training doesn\'t end when the retreat does. Participants keep practicing during the 90-day integration period, guided by our team.', 'Con equipo en casa, el entrenamiento no termina cuando termina el retiro. Los participantes siguen practicando durante los 90 días de integración, guiados por nuestro equipo.' ) ); ?></p>
				</div>
			</div>
		</div>
	</section>

	<section class="tw:py-24 tw:md:py-32 tw:bg-[#020617] tw:text-white">
		<div class="tw:max-w-4xl tw:mx-auto tw:px-4 tw:md:px-8 tw:text-center">
			<p class="tw:text-[#BBA87A] tw:font-black tw:text-xs tw:uppercase tw:tracking-[0.3em] tw:mb-4"><?php echo esc_html( iam_t( 'In the field', 'En el campo' ) ); ?></p>
			<h2 class="tw:text-4xl tw:md:text-5xl tw:font-black tw:uppercase tw:tracking-tighter tw:mb-8"><?php echo esc_html( iam_t( 'A living laboratory in the Amazon', 'Un laboratorio vivo en la Amazonía' ) ); ?></h2>
			<p class="tw:text-lg tw:text-slate-300 tw:font-light tw:leading-relaxed tw:mb-10"><?php echo esc_html( iam_t( 'Our retreats double as research. With participants\' consent, we record brain and body signals during the six days to understand which conditions help people reset, and we share what we learn through the journal and our programs.', 'Nuestros retiros también son investigación. Con el consentimiento de los participantes, registramos señales del cerebro y del cuerpo durante los seis días para entender qué condiciones ayudan a las personas a reiniciarse, y compartimos lo que aprendemos en el journal y en nuestros programas.' ) ); ?></p>
			<div class="tw:flex tw:flex-wrap tw:justify-center tw:gap-3">
				<?php iam_button( iam_t( 'Read the research journal', 'Lee el journal' ), '/blog/beyond-the-peak-the-neuroscience-of-augmented-human-intelligence', true, true ); ?>
				<?php iam_button( iam_t( 'Apply for a retreat', 'Aplica a un retiro' ), '/retreats/apply', false, true ); ?>
			</div>
		</div>
	</section>

	<section id="contact-research" class="tw:scroll-mt-32 tw:py-24 tw:md:py-32">
		<div class="tw:max-w-3xl tw:mx-auto tw:px-4 tw:md:px-8">
			<?php iam_heading( iam_t( 'Get involved', 'Participa' ), iam_t( 'Request', 'Solicita' ), iam_t( 'information', 'información' ), iam_t( 'Interested in brain mapping, neurofeedback or partnering with the center? Tell us a little about yourself.', '¿Te interesa el mapeo cerebral, el neurofeedback o colaborar con el centro? Cuéntanos un poco sobre ti.' ) ); ?>
			<?php
			iam_lead_form( array(
				'type'    => 'research',
				'source'  => 'Alexandria R&D Center',
				'submit'  => iam_t( 'Send request', 'Enviar solicitud' ),
				'success' => iam_t( 'Thank you. Our team will contact you shortly.', 'Gracias. Nuestro equipo te contactará pronto.' ),
				'fields'  => array(
					array( 'name', iam_t( 'Full name', 'Nombre completo' ), 'text', true ),
					array( 'email', iam_t( 'Email', 'Correo electrónico' ), 'email', true ),
					array( 'interest', iam_t( 'I\'m interested in', 'Me interesa' ), 'select', true, array( iam_t( 'Brain mapping', 'Mapeo cerebral' ), iam_t( 'At-home neurofeedback', 'Neurofeedback en casa' ), iam_t( 'Retreat research', 'Investigación en retiros' ), iam_t( 'Partnering with the center', 'Colaborar con el centro' ) ) ),
					array( 'message', iam_t( 'Message', 'Mensaje' ), 'textarea', false ),
				),
			) );
			?>
		</div>
	</section>
	<?php iam_disclaimer(); ?>
</main>
<?php
get_footer();
