<?php
/**
 * Shared building blocks for the new pages (hero, headings, lead form).
 * All styling uses tw:-prefixed classes (compiled into assets/iam.css).
 *
 * @package iammastermind
 */

defined( 'ABSPATH' ) || exit;

const IAM_GOLD_TEXT = 'tw:text-transparent tw:bg-clip-text tw:bg-[linear-gradient(to_right,#C8B88D,#BBA87A,#927E5A)]';

/**
 * Full-width page hero.
 *
 * @param array $a eyebrow, title, accent (gold part of title), text, image, badge, actions [[label, href, primary]].
 */
function iam_hero( $a ) {
	?>
	<section class="tw:relative tw:min-h-[70vh] tw:flex tw:items-end tw:overflow-hidden tw:bg-[#020617] tw:pt-40 tw:pb-20 tw:md:pb-28">
		<?php if ( ! empty( $a['image'] ) ) : ?>
			<img src="<?php echo esc_url( $a['image'] ); ?>" alt="" class="tw:absolute tw:inset-0 tw:w-full tw:h-full tw:object-cover tw:opacity-40">
		<?php endif; ?>
		<div class="tw:absolute tw:inset-0 tw:bg-[linear-gradient(to_top,#020617_10%,rgba(2,6,23,0.7)_55%,rgba(2,6,23,0.35))]"></div>
		<div class="tw:relative tw:z-10 tw:max-w-7xl tw:mx-auto tw:px-4 tw:md:px-8 tw:w-full">
			<div class="tw:flex tw:flex-wrap tw:items-center tw:gap-3 tw:mb-6">
				<?php if ( ! empty( $a['badge'] ) ) : ?>
					<span class="tw:px-3 tw:py-1 tw:rounded-full tw:bg-[#BBA87A] tw:text-[#020617] tw:text-[10px] tw:font-black tw:uppercase tw:tracking-widest"><?php echo esc_html( $a['badge'] ); ?></span>
				<?php endif; ?>
				<span class="tw:text-[#BBA87A] tw:font-black tw:text-xs tw:uppercase tw:tracking-[0.3em]"><?php echo esc_html( $a['eyebrow'] ); ?></span>
			</div>
			<h1 class="tw:max-w-5xl tw:text-5xl tw:md:text-7xl tw:font-black tw:text-white tw:uppercase tw:tracking-tighter tw:leading-[0.95] tw:mb-8">
				<?php echo esc_html( $a['title'] ); ?>
				<?php if ( ! empty( $a['accent'] ) ) : ?>
					<span class="<?php echo esc_attr( IAM_GOLD_TEXT ); ?>"><?php echo esc_html( $a['accent'] ); ?></span>
				<?php endif; ?>
			</h1>
			<p class="tw:max-w-2xl tw:text-lg tw:md:text-xl tw:text-slate-300 tw:font-light tw:leading-relaxed"><?php echo esc_html( $a['text'] ); ?></p>
			<?php if ( ! empty( $a['actions'] ) ) : ?>
				<div class="tw:mt-10 tw:flex tw:flex-wrap tw:gap-3">
					<?php foreach ( $a['actions'] as $b ) : ?>
						<?php iam_button( $b[0], $b[1], ! empty( $b[2] ), true ); ?>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
	</section>
	<?php
}

/** Pill button. $on_dark switches the secondary style for dark backgrounds. */
function iam_button( $label, $href, $primary = true, $on_dark = false ) {
	$external = 0 !== strpos( $href, '/' ) && 0 !== strpos( $href, '#' );
	$url      = 0 === strpos( $href, '/' ) ? home_url( $href ) : $href;
	if ( $primary ) {
		$cls = $on_dark ? 'tw:bg-white tw:text-[#020617]' : 'tw:bg-[#020617] tw:text-white tw:dark:bg-white tw:dark:text-[#020617]';
	} else {
		$cls = $on_dark ? 'tw:border tw:border-white/30 tw:text-white tw:hover:border-[#BBA87A]' : 'tw:border tw:border-slate-300 tw:dark:border-white/20 tw:text-slate-900 tw:dark:text-white tw:hover:border-[#BBA87A]';
	}
	printf(
		'<a href="%s"%s class="tw:inline-flex tw:items-center tw:gap-2 tw:font-black tw:px-7 tw:py-4 tw:rounded-full tw:uppercase tw:tracking-tighter tw:text-sm tw:hover:scale-105 tw:transition-transform %s">%s <span aria-hidden="true">%s</span></a>',
		esc_url( $url ),
		$external ? ' target="_blank" rel="noopener"' : '',
		esc_attr( $cls ),
		esc_html( $label ),
		$external ? '&nearr;' : '&rarr;'
	);
}

/** Section heading: eyebrow + title with an optional gold accent + intro. */
function iam_heading( $eyebrow, $title, $accent = '', $intro = '', $center = true ) {
	?>
	<div class="<?php echo $center ? 'tw:text-center tw:mx-auto' : ''; ?> tw:max-w-3xl tw:mb-14">
		<p class="tw:text-[#BBA87A] tw:font-black tw:text-xs tw:uppercase tw:tracking-[0.3em] tw:mb-4"><?php echo esc_html( $eyebrow ); ?></p>
		<h2 class="tw:text-4xl tw:md:text-5xl tw:font-black tw:text-slate-900 tw:dark:text-white tw:uppercase tw:tracking-tighter tw:leading-none">
			<?php echo esc_html( $title ); ?>
			<?php if ( $accent ) : ?>
				<span class="<?php echo esc_attr( IAM_GOLD_TEXT ); ?>"><?php echo esc_html( $accent ); ?></span>
			<?php endif; ?>
		</h2>
		<?php if ( $intro ) : ?>
			<p class="tw:mt-6 tw:text-lg tw:text-slate-600 tw:dark:text-slate-400 tw:font-light tw:leading-relaxed"><?php echo esc_html( $intro ); ?></p>
		<?php endif; ?>
	</div>
	<?php
}

/** A card in a feature grid. */
function iam_card( $icon, $title, $text ) {
	?>
	<div class="tw:p-8 tw:rounded-3xl tw:bg-slate-50 tw:dark:bg-[#0A101F] tw:border tw:border-slate-200 tw:dark:border-white/5 tw:hover:border-[#BBA87A]/40 tw:transition-colors">
		<div class="tw:w-12 tw:h-12 tw:mb-6 tw:rounded-2xl tw:bg-white tw:dark:bg-white/5 tw:border tw:border-slate-200 tw:dark:border-white/10 tw:flex tw:items-center tw:justify-center">
			<?php echo iam_icon( $icon, 'tw:w-6 tw:h-6 tw:text-[#BBA87A]' ); // phpcs:ignore -- static SVG ?>
		</div>
		<h3 class="tw:text-xl tw:font-black tw:text-slate-900 tw:dark:text-white tw:uppercase tw:tracking-tighter tw:mb-3"><?php echo esc_html( $title ); ?></h3>
		<p class="tw:text-slate-600 tw:dark:text-slate-400 tw:font-light tw:leading-relaxed"><?php echo esc_html( $text ); ?></p>
	</div>
	<?php
}

/**
 * Lead form posting JSON to /api/contact (same pipeline as the original forms:
 * stored in WP, sent to GHL with form-<type> tags).
 *
 * @param array $a type, fields [[name, label, input type, required, options?]], submit, success, source.
 */
function iam_lead_form( $a ) {
	$id = 'iam-form-' . sanitize_title( $a['type'] );
	$in = 'tw:w-full tw:px-5 tw:py-4 tw:rounded-2xl tw:bg-white tw:dark:bg-[#0A101F] tw:border tw:border-slate-200 tw:dark:border-white/10 tw:text-slate-900 tw:dark:text-white tw:placeholder:text-slate-400 tw:focus:outline-none tw:focus:border-[#BBA87A]';
	?>
	<form id="<?php echo esc_attr( $id ); ?>" class="iam-lead-form tw:grid tw:grid-cols-1 tw:md:grid-cols-2 tw:gap-4" data-success="<?php echo esc_attr( $a['success'] ); ?>" data-error="<?php echo esc_attr( iam_t( 'Something went wrong. Please try again.', 'Ocurrió un error. Inténtalo de nuevo.' ) ); ?>" novalidate>
		<input type="hidden" name="type" value="<?php echo esc_attr( $a['type'] ); ?>">
		<input type="hidden" name="source" value="<?php echo esc_attr( $a['source'] ?? '' ); ?>">
		<input type="hidden" name="lang" value="<?php echo esc_attr( iam_lang() ); ?>">
		<input type="text" name="website" tabindex="-1" autocomplete="off" class="tw:hidden" aria-hidden="true">
		<?php foreach ( $a['fields'] as $f ) : ?>
			<?php
			list( $name, $label, $type, $req ) = $f;
			$wide = in_array( $type, array( 'textarea', 'select' ), true ) || ! empty( $f[5] );
			?>
			<label class="<?php echo $wide ? 'tw:md:col-span-2' : ''; ?> tw:flex tw:flex-col tw:gap-2">
				<span class="tw:text-[10px] tw:font-black tw:uppercase tw:tracking-[0.2em] tw:text-[#BBA87A]"><?php echo esc_html( $label ); ?><?php echo $req ? ' *' : ''; ?></span>
				<?php if ( 'textarea' === $type ) : ?>
					<textarea name="<?php echo esc_attr( $name ); ?>" rows="4" <?php echo $req ? 'required' : ''; ?> class="<?php echo esc_attr( $in ); ?>"></textarea>
				<?php elseif ( 'select' === $type ) : ?>
					<select name="<?php echo esc_attr( $name ); ?>" <?php echo $req ? 'required' : ''; ?> class="<?php echo esc_attr( $in ); ?>">
						<option value=""><?php echo esc_html( iam_t( 'Select…', 'Selecciona…' ) ); ?></option>
						<?php foreach ( $f[4] as $opt ) : ?>
							<option><?php echo esc_html( $opt ); ?></option>
						<?php endforeach; ?>
					</select>
				<?php else : ?>
					<input type="<?php echo esc_attr( $type ); ?>" name="<?php echo esc_attr( $name ); ?>" <?php echo $req ? 'required' : ''; ?> class="<?php echo esc_attr( $in ); ?>">
				<?php endif; ?>
			</label>
		<?php endforeach; ?>
		<div class="tw:md:col-span-2 tw:flex tw:flex-col tw:sm:flex-row tw:sm:items-center tw:gap-4 tw:mt-2">
			<button type="submit" class="tw:inline-flex tw:justify-center tw:items-center tw:gap-2 tw:bg-[#020617] tw:dark:bg-white tw:text-white tw:dark:text-[#020617] tw:font-black tw:px-8 tw:py-4 tw:rounded-full tw:uppercase tw:tracking-tighter tw:text-sm tw:hover:scale-105 tw:transition-transform tw:disabled:opacity-60"><?php echo esc_html( $a['submit'] ); ?></button>
			<p class="iam-form-msg tw:text-sm tw:font-bold" role="status" aria-live="polite"></p>
		</div>
	</form>
	<?php
	static $script_done = false;
	if ( $script_done ) {
		return;
	}
	$script_done = true;
	?>
	<script>
	document.addEventListener('submit', async function (e) {
		var form = e.target;
		if (!form.classList || !form.classList.contains('iam-lead-form')) return;
		e.preventDefault();
		var msg = form.querySelector('.iam-form-msg'), btn = form.querySelector('button[type=submit]');
		if (!form.checkValidity()) { form.reportValidity(); return; }
		btn.disabled = true; msg.textContent = '…'; msg.className = 'iam-form-msg tw:text-sm tw:font-bold tw:text-slate-500';
		try {
			var res = await fetch('<?php echo esc_js( home_url( '/api/contact' ) ); ?>', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(Object.fromEntries(new FormData(form))) });
			var data = await res.json().catch(function () { return {}; });
			if (!res.ok) throw new Error(data.error || '');
			form.reset(); msg.textContent = form.dataset.success; msg.className = 'iam-form-msg tw:text-sm tw:font-bold tw:text-green-600';
		} catch (err) {
			msg.textContent = err.message || form.dataset.error; msg.className = 'iam-form-msg tw:text-sm tw:font-bold tw:text-red-600';
		} finally { btn.disabled = false; }
	});
	</script>
	<?php
}

/** Compliance note shown on health and research pages. */
function iam_disclaimer() {
	?>
	<p class="tw:max-w-4xl tw:mx-auto tw:px-4 tw:pb-16 tw:text-center tw:text-xs tw:text-slate-400 tw:leading-relaxed">
		<?php echo esc_html( iam_t( 'I AM Mastermind programs and technologies are educational and performance-focused. They are not medical treatment and do not diagnose, treat, cure or prevent any disease. Consult a qualified healthcare professional before changing your health routine.', 'Los programas y tecnologías de I AM Mastermind son educativos y orientados al rendimiento. No son tratamiento médico y no diagnostican, tratan, curan ni previenen ninguna enfermedad. Consulta a un profesional de la salud calificado antes de cambiar tu rutina de salud.' ) ); ?>
	</p>
	<?php
}
