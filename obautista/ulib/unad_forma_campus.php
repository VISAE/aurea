<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2024 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
*/
function forma_cabecera_campus($XAJAX = NULL, array $options = [])
{
	require './app.php';
	if (!function_exists('AUREA_Idioma()')) {
		require_once $APP->rutacomun . 'libaurea.php';
	}
	if (!function_exists('Traer_Entidad')) {
		require_once $APP->rutacomun . 'libdatos.php';
	}
	$idEntidad = Traer_Entidad();
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	// Revisamos los options
	$defaults = [
		'tituloModulo' => '',
		'conMenu' => true,
		'todosLosIdiomas' => false,
		'conCambioIdioma' => true,
		'conModal' => true,
	];
	$opts = array_replace($defaults, $options);
	//
	$sRes = '<!DOCTYPE html><html lang="' . $sIdioma . '"><head>';
	if ($XAJAX != NULL) {
		echo $sRes;
		$sRes = '';
		$XAJAX->printJavascript();
	}
	//Julio 7 de 2020 se agrega el host si el parametro esta marcado.
	if (isset($APP->ihost) != 0) {
		if ($APP->ihost != 0) {
			$sHost = gethostname();
			$opts['tituloModulo'] = '[' . $sHost . '] ' . $opts['tituloModulo'];
		}
	}
	$sRes = $sRes . '<meta charset="UTF-8">';
	$sRes = $sRes . '<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">';
	$sRes = $sRes . '<link rel="icon" type="image/png" sizes="16x16" href="' . $APP->rutacomun . '/img/favicon-16x16.png">';
	$sRes = $sRes . '<link rel="icon" type="image/png" sizes="32x32" href="' . $APP->rutacomun . '/img/favicon-32x32.png">';
	$sRes = $sRes . '<title>' . $opts['tituloModulo'] . ' - Universidad Nacional Abierta y a Distancia - UNAD</title>';
	$sRes = $sRes . '<link rel="stylesheet" href="' . $APP->rutacomun . 'css/campus2025.css?v=3">';
	$sRes = $sRes . '<script language="javascript" src="' . $APP->rutacomun . 'js/jquery-3.3.1.min.js"></script>';
	// Modal
	if ($opts['conModal']) {
		$sRes = $sRes . '<dialog id="div_modal" class="modal-bg">';
		$sRes = $sRes . '<div id="modal-type" class="modal">';
		$sRes = $sRes . '<div class="modal__header">';
		$sRes = $sRes . '<button id="modal_close" class="modal-close" type="button" aria-label="' . $ETI['label_cerrar_mensaje'] . '">';
		$sRes = $sRes . $ETI['bt_cerrar'];
		$sRes = $sRes . '<i class="icon-closed" aria-hidden="true"></i>';
		$sRes = $sRes . '</button>';
		$sRes = $sRes . '<div class="modal__title">';
		$sRes = $sRes . '<i id="modal_icon" class="icon-warning" aria-hidden="true"></i>';
		$sRes = $sRes . '<p id="modal_title">' . $ETI['msg_advertencia'] . '</p>';
		$sRes = $sRes . '</div>';
		$sRes = $sRes . '</div>';
		$sRes = $sRes . '<div id="modal_body" class="modal__body">';
		$sRes = $sRes . '</div>';
		$sRes = $sRes . '<div id="modal_footer" class="modal__footer">';
		$sRes = $sRes . '<button id="boton_aceptar" class="btn btn--tertiary modal-close" type="button" aria-label="' . $ETI['label_confirmar'] . '">';
		$sRes = $sRes . '<i id="modal_aceptar_icon" class="icon-check" aria-hidden="true"></i>';
		$sRes = $sRes . '<p id="boton_aceptar_title">' . $ETI['bt_aceptar'] . '</p>';
		$sRes = $sRes . '</button>';
		$sRes = $sRes . '<button id="boton_cancelar" class="btn btn--container modal-close" type="button" aria-label="' . $ETI['label_cancelar'] . '">';
		$sRes = $sRes . '<i id="modal_cancelar_icon" class="icon-closed" aria-hidden="true"></i>';
		$sRes = $sRes . '<p id="boton_cancelar_title">' . $ETI['bt_cancelar'] . '</p>';
		$sRes = $sRes . '</button>';
		$sRes = $sRes . '</div>';
		$sRes = $sRes . '</div>';
		$sRes = $sRes . '</dialog>';
	}
	$sRes = $sRes . '</head>';
	$sRes = $sRes . '<body>';
	if ($opts['conCambioIdioma']) {
		// Idioma
		$sRes = $sRes . '<section class="language-selector">';
		$sRes = $sRes . '<button class="language-selector__button btn" aria-label="' . $ETI['label_selector'] . '" aria-haspopup="true" onclick="toggleLanguageSelector()" aria-expanded="false" id="language-selector">';
		$sRes = $sRes . '<i class="icon-language" aria-hidden="true"></i>';
		switch ($sIdioma) {
			case 'en':
				$sRes = $sRes . '<span class="language-selected" aria-label="Idioma seleccionado Inglés">';
				$sRes = $sRes . $ETI['msg_ingles'];
				break;
			case 'es';
				$sRes = $sRes . '<span class="language-selected" aria-label="Idioma seleccionado Español">';
				$sRes = $sRes . $ETI['msg_espanol'];
				break;
		}
		$sRes = $sRes . '</span>';
		$sRes = $sRes . '</button>';
		$sRes = $sRes . '<div role="menu" class="language-selector__dropdown language-selector__dropdown--hidden">';
		$sRes = $sRes . '<ul class="language-selector__list">';
		$sRes = $sRes . '<li id="language-selector-es" role="menuitemradio" class="language-selector__item">';
		$sRes = $sRes . '<i class="icon-check" aria-hidden="true"></i>';
		$sRes = $sRes . '<span class="flex gap-1 align-items-center">';
		$sBandera = '<i class="icon-colombia-flag"></i>';
		if ($idEntidad == 2) {
			$sBandera = '<i class="icon-spain-flag"></i>';
		}
		$sRes = $sRes . $sBandera;
		$sRes = $sRes . $ETI['msg_espanol'];
		$sRes = $sRes . '</span>';
		$sRes = $sRes . '</li>';
		$sRes = $sRes . '<li id="language-selector-en" role="menuitemradio" class="language-selector__item">';
		$sRes = $sRes . '<i class="icon-check" aria-hidden="true"></i>';
		$sRes = $sRes . '<span class="flex gap-1 align-items-center">';
		$sRes = $sRes . '<i class="icon-usa-flag"></i>';
		$sRes = $sRes . $ETI['msg_ingles'];
		$sRes = $sRes . '</span>';
		$sRes = $sRes . '</li>';
		if ($opts['todosLosIdiomas']) {
			$sRes = $sRes . '<li id="language-selector-en" role="menuitemradio" class="language-selector__item">';
			$sRes = $sRes . '<i class="icon-check" aria-hidden="true"></i>';
			$sRes = $sRes . '<span class="flex gap-1 align-items-center">';
			$sRes = $sRes . '<i class="icon-brasil-flag"></i>';
			$sRes = $sRes . 'Brasil';
			$sRes = $sRes . '</span>';
			$sRes = $sRes . '</li>';
		}
		$sRes = $sRes . '</ul>';
		$sRes = $sRes . '</div>';
		$sRes = $sRes . '</section>';
	}
	if ($opts['conMenu']) {
		$sRes = $sRes . '<section class="menu-toggle">';
		$sRes = $sRes . '<button class="btn btn--tertiary" aria-label="Abrir menú principal">';
		$sRes = $sRes . '<i class="icon-menu" aria-hidden="true"></i>';
		$sRes = $sRes . '</button>';
		$sRes = $sRes . '</section>';
		$sRes = $sRes . '<nav>';
	}
	echo $sRes;
}
function forma_mitad_campus(array $options = [])
{
	require './app.php';
	$sRes = '';
	if (!function_exists('AUREA_Idioma()')) {
		require_once $APP->rutacomun . 'libaurea.php';
	}
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	// Revisamos los options
	$defaults = [
		'tituloModulo' => '',
		'conMenu' => true,
		'conAccess' => true,
		'rutas' => [],
		'conBanner' => true
	];
	$opts = array_replace($defaults, $options);
	//
	if ($opts['conMenu']) {
		$sRes = $sRes . '</nav>';
	}
	// Widget Accesibilidad
	if ($opts['conAccess']) {
		$sRes = $sRes . widgetAccess();
	}
	if ($opts['conBanner']) {
		$sRes = $sRes . '<header>';
		$sRes = $sRes . '<section class="banner" role="img" aria-label="' . $ETI['label_logo_campus_unad'] . '">';
		$sRes = $sRes . '</section>';
		$sRes = $sRes . '</header>';
	}
	// Acá el breadcrum
	$iRutas = count($opts['rutas']);
	if ($iRutas) {
		$sRes = $sRes . '<section id="div_breadcrumb" class="breadcrumb" role="navigation" aria-label="Ruta de navegación">';
		for ($i = 0; $i < $iRutas; $i++) {
			if ($i > 0) {
				$sRes = $sRes . '<span aria-hidden="true"> / </span>';
			}
			$sRes = $sRes . '<a href="' . $opts['rutas'][$i][0] . '">' . cadena_decodificar($opts['rutas'][$i][1]) . '</a>';
		}
		$sRes = $sRes . '</section>';
	}
	// Fin del breadcrum
	$sRes = $sRes . '<main>';
	$sRes = $sRes . '<section id="lang-config" class="hidden" aria-hidden="true" data-lang="' . $sIdioma . '"></section>';
	echo $sRes;
}
function forma_piedepagina_campus(array $options = [])
{
	require './app.php';
	if (!function_exists('Traer_Entidad')) {
		require_once $APP->rutacomun . 'libdatos.php';
	}
	$idEntidad = Traer_Entidad();
	// Revisamos los options
	$defaults = [
		'conTiempo' => false,
		'mostrarFooter' => true,
		'conScripts' => true,
		'conRadioUnad' => false
	];
	$opts = array_replace($defaults, $options);
	//
	$sMuestra = 'display:none;';
	if ($opts['conTiempo']) {
		$sMuestra = '';
	}
	$sRes = '';
	if (!function_exists('AUREA_Idioma()')) {
		require_once $APP->rutacomun . 'libaurea.php';
	}
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$sRes = $sRes . '<div class="salto1px"></div>';
	$sRes = $sRes . '<div id="div_tiempo" style="width:150px;' . $sMuestra . '" class="ir_derecha"></div>';
	$sRes = $sRes . '<div class="salto1px"></div>';
	$sRes = $sRes . '</main>';
	//
	if ($opts['mostrarFooter']) {
		$sRes = $sRes . '<footer>';
		$sRes = $sRes . '<span class="logo-unad" role="img" aria-label="' . $ETI['label_logo_unad'] . '"></span>';
		$sRes = $sRes . '<address class="info-unad" aria-label="' . $ETI['label_info_contacto_unad'] . '">';
		switch ($idEntidad) {
			case 1: // Floridad
				$sRes = $sRes . '<p> Unad Florida: <a href="https://maps.app.goo.gl/mC1e9Kd5mX1qLVA3A" target="_blank" aria-label="View location map of the headquarters in a new window">490 Sawgrass Corporate Parkway Suite 120 Sunrise, Florida 33325</a></p>';
				$sRes = $sRes . '<p> PBX: <a href="tel:+19543892277" aria-label="Call to PBX Florida, USA +1 954 389 2277">+1 (954) 389-2277</a> Florida, USA</p>';
				$sRes = $sRes . '<p> User support: <a href="mailto:info@unad.us" aria-label="Send email to info@unad.us">info@unad.us</a></p>';
				$sRes = $sRes . '<p> UNAD Florida (#2900) is licensed by the Commission for Independent Education, Florida Department of Education.</p>';
				break;
			case 2: // Union Europea
				break;
			default:
				$sRes = $sRes . '<p> Sede nacional Jos&eacute; Celestino Mutis: <a href="https://maps.app.goo.gl/W7eojUja2VfKHbJb7" target="_blank" aria-label="Ver mapa de ubicaci&oacute;n Sede nacional Jos&eacute; Celestino Mutis en una nueva ventana">Calle 14 sur No. 14 - 23</a></p>';
				$sRes = $sRes . '<p> PBX: <a href="tel:+573182194645" aria-label="Llamar a PBX Bogotá DC Colombia +57 318 219 46 45">(+57) 318 219 46 45</a> Bogot&aacute; D.C., Colombia</p>';
				$sRes = $sRes . '<p> Atenci&oacute;n al usuario: <a href="mailto:atencionalusuario@unad.edu.co" aria-label="Enviar correo a atencionalusuario@unad.edu.co">atencionalusuario@unad.edu.co</a></p>';
				$sRes = $sRes . '<p> Instituci&oacute;n de Educaci&oacute;n Superior sujeta a inspecci&oacute;n y vigilancia por el Ministerio de Educaci&oacute;n Nacional</p>';
				break;
		}
		$sRes = $sRes . '</address>';
		$sRes = $sRes . '</footer>';
	}
	// Radio Unad
	if ($opts['conRadioUnad']) {
		$sRes = $sRes . widget_RadioUnad();
		$sRes = $sRes . '<script>';
		$sRes = $sRes . 'function abrirRUV() {';
		$sRes = $sRes . 'window.open("https://ruv.unad.edu.co/online/", "RUV", "width=400,height=400");';
		$sRes = $sRes . '}';
		$sRes = $sRes . '</script>';
		// $sRes = $sRes . '<div class="playerContent">';
		// $sRes = $sRes . '<a id="viewPlayer" class="viewPlayer" href="javascript:abrirRUV()">';
		// $sRes = $sRes . '<img src="./player/btnRadioUnad.png" />';
		// $sRes = $sRes . '</a>';
		// $sRes = $sRes . '</div>';
		$sRes = $sRes . '<script src="./player/radio-script.js?v=5"></script>';
	}
	// Scripts
	if ($opts['conScripts']) {
		$sRes = $sRes . '<script src="' . $APP->rutacomun . 'js/campus2025.js"></script>';
		$sRes = $sRes . '<script type="text/javascript">';
		$sRes = $sRes . 'if (window.history.replaceState) {';
		$sRes = $sRes . 'window.history.replaceState(null, null, window.location.href);';
		$sRes = $sRes . '}';
		$sRes = $sRes . '</script>';
	}
	// Fin Scripts
	$sRes = $sRes . '</body>';
	$sRes = $sRes . '</html>';
	echo $sRes;
}
