<?php
/**
 * Abraham Carranza — expanded bio (feeds a Google knowledge panel).
 * Draft built from the existing About page; facts to confirm are marked in docs/CONTENT-REVIEW.md.
 *
 * @package iammastermind
 */

// Person structured data for search engines.
add_action( 'wp_head', function () {
	$same = array_values( array_filter( array_map( 'trim', preg_split( '/\s+/', (string) iam_opt( 'abraham_profiles' ) ) ) ) );
	$data = array(
		'@context'    => 'https://schema.org',
		'@type'       => 'Person',
		'name'        => 'Abraham Carranza',
		'url'         => home_url( '/abraham-carranza' ),
		'image'       => 'https://res.cloudinary.com/dqaq65lmd/image/upload/f_auto,q_auto/v1778710466/mastermind_coach_abraham_carranza_event_rng2ap.jpg',
		'jobTitle'    => 'Founder & CEO',
		'description' => iam_l( iam_native_pages()['abraham-carranza'][1] ),
		'worksFor'    => array( '@type' => 'Organization', 'name' => 'I AM Mastermind', 'url' => home_url( '/' ) ),
		'knowsLanguage' => array( 'en', 'es' ),
		'knowsAbout'  => array( 'Applied Neuroscience', 'Neurofeedback', 'Executive Coaching', 'Leadership', 'Longevity', 'Business Strategy' ),
	);
	if ( $same ) {
		$data['sameAs'] = $same;
	}
	echo '<script type="application/ld+json">' . wp_json_encode( $data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";
} );

get_header();
$iam_photo = 'https://res.cloudinary.com/dqaq65lmd/image/upload/f_auto,q_auto/v1778710466/mastermind_coach_abraham_carranza_event_rng2ap.jpg';
?>
<main class="tw:bg-white tw:dark:bg-[#020617] selection:bg-[#BBA87A] selection:text-white">
	<?php
	iam_hero( array(
		'eyebrow' => iam_t( 'Founder & CEO, I AM Mastermind', 'Fundador y CEO, I AM Mastermind' ),
		'title'   => 'Abraham',
		'accent'  => 'Carranza',
		'text'    => iam_t( 'Entrepreneur, coach, speaker and investor. Founder of I AM Mastermind and the Alexandria Research & Development Center, working where neuroscience, business and personal development meet.', 'Emprendedor, coach, conferencista e inversionista. Fundador de I AM Mastermind y del Centro Alexandria de Investigación y Desarrollo, trabajando donde se unen la neurociencia, los negocios y el desarrollo personal.' ),
		'image'   => $iam_photo,
		'actions' => array(
			array( iam_t( 'Book Abraham to speak', 'Invita a Abraham a hablar' ), '/speaker', true ),
			array( iam_t( 'Work with Abraham', 'Trabaja con Abraham' ), '/programs', false ),
		),
	) );
	?>

	<section class="tw:py-24 tw:md:py-32">
		<div class="tw:max-w-7xl tw:mx-auto tw:px-4 tw:md:px-8 tw:grid tw:grid-cols-1 tw:lg:grid-cols-[1fr_1.2fr] tw:gap-12 tw:lg:gap-20 tw:items-start">
			<div class="tw:lg:sticky tw:lg:top-32">
				<img src="<?php echo esc_url( $iam_photo ); ?>" alt="Abraham Carranza" class="tw:w-full tw:aspect-[4/5] tw:object-cover tw:rounded-4xl tw:shadow-xl">
				<dl class="tw:mt-8 tw:grid tw:grid-cols-2 tw:gap-4">
					<?php
					$iam_facts = array(
						array( iam_t( 'Role', 'Rol' ), iam_t( 'Founder & CEO', 'Fundador y CEO' ) ),
						array( iam_t( 'Languages', 'Idiomas' ), iam_t( 'English, Spanish', 'Inglés, español' ) ),
						array( iam_t( 'Experience', 'Experiencia' ), iam_t( '20+ years', 'Más de 20 años' ) ),
						array( iam_t( 'Based in', 'Con sede en' ), 'Texas' ),
					);
					foreach ( $iam_facts as $iam_f ) :
						?>
						<div class="tw:p-5 tw:rounded-2xl tw:bg-slate-50 tw:dark:bg-[#0A101F] tw:border tw:border-slate-200 tw:dark:border-white/5">
							<dt class="tw:text-[10px] tw:font-black tw:uppercase tw:tracking-[0.2em] tw:text-[#BBA87A]"><?php echo esc_html( $iam_f[0] ); ?></dt>
							<dd class="tw:mt-1 tw:font-bold tw:text-slate-900 tw:dark:text-white"><?php echo esc_html( $iam_f[1] ); ?></dd>
						</div>
					<?php endforeach; ?>
				</dl>
			</div>
			<div class="tw:space-y-12">
				<?php
				$iam_story = array(
					array(
						iam_t( 'The builder', 'El constructor' ),
						iam_t( 'Abraham built one company after another, learning not only how to grow a business but how to make it last. Along the way he saw the same pattern in every founder he worked with: results follow the person, and the person follows their programming.', 'Abraham construyó una empresa tras otra, aprendiendo no solo a hacer crecer un negocio sino a hacerlo perdurar. En el camino vio el mismo patrón en cada fundador con el que trabajó: los resultados siguen a la persona, y la persona sigue a su programación.' ),
					),
					array(
						iam_t( 'The coach', 'El coach' ),
						iam_t( 'That insight became I AM Mastermind: an institute for reprogramming the beliefs and habits that hold people back. Its programs — Growth Society, Business Matrix, Mastery Lifestyle and the Inner Circle — have helped entrepreneurs and executives grow their income, their companies and themselves.', 'Esa idea se convirtió en I AM Mastermind: un instituto para reprogramar las creencias y hábitos que frenan a las personas. Sus programas —Growth Society, Business Matrix, Mastery Lifestyle y el Inner Circle— han ayudado a emprendedores y ejecutivos a crecer en ingresos, en sus empresas y como personas.' ),
					),
					array(
						iam_t( 'The researcher', 'El investigador' ),
						iam_t( 'Abraham wanted to see the change, not only feel it. He founded the Alexandria Research & Development Center to bring brain mapping and neurofeedback technology into coaching and retreats, so progress can be measured.', 'Abraham quería ver el cambio, no solo sentirlo. Fundó el Centro Alexandria de Investigación y Desarrollo para llevar el mapeo cerebral y la tecnología de neurofeedback al coaching y a los retiros, para que el progreso pueda medirse.' ),
					),
					array(
						iam_t( 'Bringing the body along', 'Llevar el cuerpo contigo' ),
						iam_t( 'Today his work joins mind and body. Through the retreats in the Peruvian Amazon and the upcoming longevity platform, he helps high performers build lives they have the energy and the years to enjoy.', 'Hoy su trabajo une mente y cuerpo. A través de los retiros en la Amazonía peruana y la próxima plataforma de longevidad, ayuda a personas de alto rendimiento a construir vidas que tengan la energía y los años para disfrutar.' ),
					),
				);
				foreach ( $iam_story as $iam_s ) :
					?>
					<div>
						<h2 class="tw:text-3xl tw:font-black tw:text-slate-900 tw:dark:text-white tw:uppercase tw:tracking-tighter tw:mb-4"><?php echo esc_html( $iam_s[0] ); ?></h2>
						<p class="tw:text-lg tw:text-slate-600 tw:dark:text-slate-400 tw:font-light tw:leading-relaxed"><?php echo esc_html( $iam_s[1] ); ?></p>
					</div>
				<?php endforeach; ?>
				<blockquote class="tw:p-8 tw:rounded-3xl tw:border-l-4 tw:border-[#BBA87A] tw:bg-slate-50 tw:dark:bg-white/5 tw:text-xl tw:italic tw:text-slate-700 tw:dark:text-slate-300 tw:font-light tw:leading-relaxed">
					<?php echo esc_html( iam_t( '"At the end of the day, it is not just what we do in life, it is the people we touch."', '"Al final del día, no se trata solo de lo que hacemos en la vida, sino de las personas que tocamos."' ) ); ?>
				</blockquote>
				<div class="tw:flex tw:flex-wrap tw:gap-3">
					<?php iam_button( iam_t( 'Book Abraham to speak', 'Invita a Abraham a hablar' ), '/speaker' ); ?>
					<?php iam_button( iam_t( 'Explore the programs', 'Conoce los programas' ), '/programs', false ); ?>
				</div>
			</div>
		</div>
	</section>
</main>
<?php
get_footer();
