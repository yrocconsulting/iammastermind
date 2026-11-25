<?php
/**
 * Site navigation: Home · Mind · Body · Research · About · Blog · Contact.
 *
 * Replaces the original hard-coded menus (see tools/build_theme.py, which leaves
 * {{IAM_NAV_DESKTOP}} / {{IAM_NAV_MOBILE}} in the site header). The markup reuses
 * the original menu's classes, and the Mind menu is the original "Coaching
 * Programs" dropdown, unchanged apart from its label.
 *
 * Edit the menu here: iam_nav_items().
 *
 * @package iammastermind
 */

defined( 'ABSPATH' ) || exit;

/** Lucide icons (stroke icons, same set as the original site). */
function iam_icon( $name, $class = 'w-5 h-5 icon-gold' ) {
	$paths = array(
		'heart-pulse' => '<path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/><path d="M3.22 12H9.5l.5-1 2 4.5 2-7 1.5 3.5h5.27"/>',
		'tent'        => '<path d="M3.5 21 14 3"/><path d="M20.5 21 10 3"/><path d="M15.5 21 12 15l-3.5 6"/><path d="M2 21h20"/>',
		'compass'     => '<circle cx="12" cy="12" r="10"/><path d="m16.24 7.76-2.12 6.36-6.36 2.12 2.12-6.36 6.36-2.12z"/>',
		'users'       => '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>',
		'help'        => '<circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><path d="M12 17h.01"/>',
		'book-open'   => '<path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>',
		'calendar'    => '<path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/>',
		'microscope'  => '<path d="M6 18h8"/><path d="M3 22h18"/><path d="M14 22a7 7 0 1 0 0-14h-1"/><path d="M9 14h2"/><path d="M9 12a2 2 0 0 1-2-2V6h6v4a2 2 0 0 1-2 2Z"/><path d="M12 6V3a1 1 0 0 0-1-1H9a1 1 0 0 0-1 1v3"/>',
		'activity'    => '<path d="M22 12h-4l-3 9L9 3l-3 9H2"/>',
		'radio'       => '<path d="M4.9 19.1C1 15.2 1 8.8 4.9 4.9"/><path d="M7.8 16.2c-2.3-2.3-2.3-6.1 0-8.5"/><circle cx="12" cy="12" r="2"/><path d="M16.2 7.8c2.3 2.3 2.3 6.1 0 8.5"/><path d="M19.1 4.9C23 8.8 23 15.1 19.1 19"/>',
		'sparkles'    => '<path d="M9.94 14.06 8 20l-1.94-5.94L0 12l6.06-1.94L8 4l1.94 6.06L16 12z" transform="translate(4 0) scale(.85)"/><path d="M20 3v4"/><path d="M22 5h-4"/>',
		'user'        => '<path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>',
		'mic'         => '<path d="M12 2a3 3 0 0 0-3 3v7a3 3 0 0 0 6 0V5a3 3 0 0 0-3-3Z"/><path d="M19 10v2a7 7 0 0 1-14 0v-2"/><line x1="12" x2="12" y1="19" y2="22"/>',
	);
	return '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" data-astro-cid-5blmo7yk="true" class="lucide ' . esc_attr( $class ) . '">' . ( $paths[ $name ] ?? '' ) . '</svg>';
}

/**
 * The menu. Each top-level item is a link or a dropdown; labels are [en, es].
 * 'programs' => true renders the original Coaching Programs dropdown.
 */
function iam_nav_items() {
	$items = array(
		array( 'label' => array( 'Home', 'Inicio' ), 'href' => '/' ),
		array( 'label' => array( 'Mind', 'Mente' ), 'programs' => true ),
		array(
			'label'  => array( 'Body', 'Cuerpo' ),
			'width'  => 'wide',
			'links'  => array(
				array( 'href' => '/longevity', 'icon' => 'heart-pulse', 'badge' => array( 'Coming soon', 'Próximamente' ), 'title' => array( 'Longevity', 'Longevidad' ), 'desc' => array( 'Health, biohacking and longevity.', 'Salud, biohacking y longevidad.' ) ),
				array( 'href' => '/retreats', 'icon' => 'tent', 'title' => array( 'Retreats', 'Retiros' ), 'desc' => array( 'Six days in the Peruvian Amazon.', 'Seis días en la Amazonía peruana.' ) ),
				array( 'href' => '/retreats/the-mastermind-immersion', 'icon' => 'compass', 'title' => array( 'The Amazon Immersion', 'La Inmersión Amazónica' ), 'desc' => array( 'How the six days unfold.', 'Cómo se viven los seis días.' ) ),
				array( 'href' => '/retreats/who-is-this-for', 'icon' => 'users', 'title' => array( 'Who It\'s For', 'Para Quién Es' ), 'desc' => array( 'Is this retreat right for you?', '¿Es este retiro para ti?' ) ),
				array( 'href' => '/retreats/frequently-asked-questions', 'icon' => 'help', 'title' => array( 'Retreat FAQ', 'Preguntas Frecuentes' ), 'desc' => array( 'Safety, logistics and integration.', 'Seguridad, logística e integración.' ) ),
				array( 'href' => '/retreats/journal', 'icon' => 'book-open', 'title' => array( 'Retreat Journal', 'Diario del Retiro' ), 'desc' => array( 'Preparation and integration notes.', 'Notas de preparación e integración.' ) ),
				array( 'href' => '/events', 'icon' => 'calendar', 'title' => array( 'Live Events', 'Eventos en Vivo' ), 'desc' => array( 'In-person mastermind sessions.', 'Sesiones presenciales de mastermind.' ) ),
			),
			'cta'    => array( 'href' => '/retreats/apply', 'label' => array( 'Apply for the next retreat &rarr;', 'Aplica al próximo retiro &rarr;' ) ),
		),
		array(
			'label' => array( 'Research', 'Investigación' ),
			'links' => array(
				array( 'href' => '/alexandria-research-center', 'icon' => 'microscope', 'title' => array( 'Alexandria R&amp;D Center', 'Centro Alexandria de I+D' ), 'desc' => array( 'Neuroscience and human optimization.', 'Neurociencia y optimización humana.' ) ),
				array( 'href' => '/alexandria-research-center#brain-mapping', 'icon' => 'activity', 'title' => array( 'Brain Mapping', 'Mapeo Cerebral' ), 'desc' => array( 'QEEG and EEG technology.', 'Tecnología QEEG y EEG.' ) ),
				array( 'href' => '/alexandria-research-center#neurofeedback', 'icon' => 'radio', 'title' => array( 'At-Home Neurofeedback', 'Neurofeedback en Casa' ), 'desc' => array( 'Training that continues after the retreat.', 'Entrenamiento que continúa después del retiro.' ) ),
			),
		),
		array(
			'label' => array( 'About', 'Nosotros' ),
			'links' => array(
				array( 'href' => '/about#our-story', 'icon' => 'sparkles', 'title' => array( 'Our Story', 'Nuestra Historia' ), 'desc' => array( 'The vision and mission behind the institute.', 'La visión y misión detrás del instituto.' ) ),
				array( 'href' => '/abraham-carranza', 'icon' => 'user', 'title' => array( 'Abraham Carranza', 'Abraham Carranza' ), 'desc' => array( 'Founder, coach and researcher.', 'Fundador, coach e investigador.' ) ),
				array( 'href' => '/speaker', 'icon' => 'mic', 'title' => array( 'Speaking', 'Conferencias' ), 'desc' => array( 'Book Abraham for your event.', 'Invita a Abraham a tu evento.' ) ),
			),
		),
		array( 'label' => array( 'Blog', 'Blog' ), 'href' => '/blog' ),
		array( 'label' => array( 'Contact', 'Contacto' ), 'href' => '/contact' ),
	);
	return apply_filters( 'iam_nav_items', $items );
}

/** Pick the current-language string from an [en, es] pair. */
function iam_l( $pair ) {
	return is_array( $pair ) ? ( 'es' === iam_lang() ? $pair[1] : $pair[0] ) : $pair;
}

/** Full desktop or mobile menu markup (replaces {{IAM_NAV_DESKTOP}} / {{IAM_NAV_MOBILE}}). */
function iam_nav_html( $which ) {
	$cid  = ' data-astro-cid-5blmo7yk';
	$home = untrailingslashit( home_url() );
	$url  = function ( $href ) use ( $home ) {
		return esc_url( 0 === strpos( $href, '/' ) ? $home . $href : $href );
	};
	$chev = '<svg class="w-4 h-4 relative z-10 transition-transform duration-300 group-hover:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor"' . $cid . '><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"' . $cid . '></path></svg>';
	$out  = '';

	if ( 'desktop' === $which ) {
		$out .= '<div class="hidden lg:flex shrink-0 justify-center items-center gap-1 xl:gap-2 h-full"' . $cid . '>';
		foreach ( iam_nav_items() as $it ) {
			$label = iam_l( $it['label'] );
			if ( ! empty( $it['href'] ) ) {
				$out .= ' <div class="h-full flex items-center gooey-container relative px-1 xl:px-2"' . $cid . '> <a href="' . $url( $it['href'] ) . '" class="gooey-item"' . $cid . '><span class="relative z-10"' . $cid . '>' . esc_html( $label ) . '</span></a> </div>';
				continue;
			}
			if ( ! empty( $it['programs'] ) ) {
				$out .= ' ' . str_replace( '{{LABEL}}', esc_html( $label ), iam_file( 'layouts/site/' . iam_lang() . '.nav-programs.html' ) );
				continue;
			}
			$wide  = 'wide' === ( $it['width'] ?? '' );
			$out  .= ' <div class="h-full flex items-center gooey-container group relative px-1 xl:px-2 dropdown-wrapper"' . $cid . '> <button class="gooey-item flex items-center gap-1.5 focus:outline-none"' . $cid . '> <span class="relative z-10"' . $cid . '>' . esc_html( $label ) . '</span> ' . $chev . ' </button>';
			$out  .= ' <div class="absolute top-[calc(100%-15px)] left-1/2 -translate-x-1/2 pt-6 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform translate-y-4 group-hover:translate-y-0 dropdown-content"' . $cid . '>';
			$out  .= ' <div class="' . ( $wide ? 'w-150 p-5' : 'w-96 p-3' ) . ' bg-white dark:bg-[#0A101F] rounded-3xl shadow-2xl border border-slate-200 dark:border-white/10 relative overflow-hidden"' . $cid . '>';
			$out  .= ' <div class="' . ( $wide ? 'grid grid-cols-2 gap-3' : 'flex flex-col gap-1' ) . ' relative z-10"' . $cid . '>';
			foreach ( $it['links'] as $l ) {
				$badge = ! empty( $l['badge'] ) ? ' <span class="tw:ml-1.5 px-1.5 py-0.5 text-[8px] font-bold uppercase tracking-widest text-white bg-[#BBA87A] rounded-full tw:align-middle">' . esc_html( iam_l( $l['badge'] ) ) . '</span>' : '';
				$out  .= ' <a href="' . $url( $l['href'] ) . '" class="flex items-start gap-3 p-3 rounded-2xl hover:bg-slate-50 dark:hover:bg-white/5 transition-colors group/item"' . $cid . '>';
				$out  .= ' <div class="mt-0.5 w-10 h-10 rounded-xl bg-slate-50 dark:bg-white/5 flex items-center justify-center shrink-0 border border-slate-100 dark:border-white/5 group-hover/item:border-[#BBA87A]/30 transition-all"' . $cid . '>' . iam_icon( $l['icon'] ) . '</div>';
				$out  .= ' <div class="flex flex-col"' . $cid . '> <span class="text-sm font-bold text-slate-900 dark:text-white group-hover/item:text-[#BBA87A] transition-colors"' . $cid . '>' . iam_l( $l['title'] ) . $badge . '</span> <span class="text-[10px] text-slate-500 dark:text-slate-400 mt-1 leading-relaxed line-clamp-1"' . $cid . '>' . esc_html( iam_l( $l['desc'] ) ) . '</span> </div> </a>';
			}
			$out .= ' </div>';
			if ( ! empty( $it['cta'] ) ) {
				$out .= ' <a href="' . $url( $it['cta']['href'] ) . '" class="relative z-10 mt-3 flex items-center justify-center p-3 rounded-2xl border border-dashed border-slate-200 dark:border-white/10 hover:border-[#BBA87A] transition-colors group/item"' . $cid . '> <span class="text-xs font-black uppercase tracking-widest text-[#BBA87A]"' . $cid . '>' . iam_l( $it['cta']['label'] ) . '</span> </a>';
			}
			$out .= ' </div> </div> </div>';
		}
		return $out . ' </div> ';
	}

	// Mobile (off-canvas) menu.
	$row  = 'text-lg font-bold text-slate-900 dark:text-white uppercase tracking-widest py-2 border-b border-slate-100 dark:border-white/5';
	$out .= '<div class="flex flex-col gap-4 flex-1"' . $cid . '>';
	foreach ( iam_nav_items() as $it ) {
		$label = iam_l( $it['label'] );
		if ( ! empty( $it['href'] ) ) {
			$out .= ' <a href="' . $url( $it['href'] ) . '" class="' . $row . '"' . $cid . '>' . esc_html( $label ) . '</a>';
			continue;
		}
		if ( ! empty( $it['programs'] ) ) {
			$out .= ' ' . str_replace( '{{LABEL}}', esc_html( $label ), iam_file( 'layouts/site/' . iam_lang() . '.nav-programs-mobile.html' ) );
			continue;
		}
		$out .= ' <div class="mobile-accordion border-b border-slate-100 dark:border-white/5"' . $cid . '> <button class="w-full flex items-center justify-between text-lg font-bold text-slate-900 dark:text-white uppercase tracking-widest py-2"' . $cid . '> ' . esc_html( $label ) . ' <svg class="w-4 h-4 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"' . $cid . '><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"' . $cid . '></path></svg> </button>';
		$out .= ' <div class="accordion-content hidden flex-col gap-4 pl-4 pb-4 mt-2"' . $cid . '>';
		foreach ( $it['links'] as $l ) {
			$badge = ! empty( $l['badge'] ) ? ' <span class="px-1.5 py-0.5 text-[8px] font-bold uppercase tracking-widest text-white bg-[#BBA87A] rounded-full">' . esc_html( iam_l( $l['badge'] ) ) . '</span>' : '';
			$out  .= ' <a href="' . $url( $l['href'] ) . '" class="flex items-center gap-3 text-sm font-medium text-slate-600 dark:text-slate-400"' . $cid . '>' . iam_icon( $l['icon'], 'w-4 h-4 text-[#BBA87A]' ) . iam_l( $l['title'] ) . $badge . '</a>';
		}
		if ( ! empty( $it['cta'] ) ) {
			$out .= ' <a href="' . $url( $it['cta']['href'] ) . '" class="text-xs font-black uppercase tracking-widest text-[#BBA87A]"' . $cid . '>' . iam_l( $it['cta']['label'] ) . '</a>';
		}
		$out .= ' </div> </div>';
	}
	return $out . ' </div> ';
}
