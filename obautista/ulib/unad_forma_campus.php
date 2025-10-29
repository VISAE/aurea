<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2024 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
*/
function forma_cabecera($XAJAX = NULL, array $options = [])
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
	$sRes = $sRes . '<link rel="stylesheet" href="' . $APP->rutacomun . 'css/campus2025.css?v=2">';
	$sRes = $sRes . '<script language="javascript" src="' . $APP->rutacomun . 'js/jquery-3.3.1.min.js"></script>';
	// Modal
	if (true) {
		$sRes = $sRes . '<dialog id="div_modal" class="modal-bg">';
		$sRes = $sRes . '<div id="modal-type" class="modal">';
		$sRes = $sRes . '<div class="modal__header">';
		$sRes = $sRes . '<button id="modal_close" class="modal-close" type="button" aria-label="Cerrar este mensaje">';
		$sRes = $sRes . 'Cerrar';
		$sRes = $sRes . '<i class="icon-closed" aria-hidden="true"></i>';
		$sRes = $sRes . '</button>';
		$sRes = $sRes . '<div class="modal__title">';
		$sRes = $sRes . '<i id="modal_icon" class="icon-info" aria-hidden="true"></i>';
		$sRes = $sRes . '<p id="modal_title">Advertencia</p>';
		$sRes = $sRes . '</div>';
		$sRes = $sRes . '</div>';
		$sRes = $sRes . '<div id="modal_body" class="modal__body">';
		$sRes = $sRes . '</div>';
		$sRes = $sRes . '<div id="modal_footer" class="modal__footer">';
		$sRes = $sRes . '<button id="boton_aceptar" class="btn btn--tertiary modal-close" type="button" aria-label="Confirmar elecci&oacute;n">';
		$sRes = $sRes . '<i id="modal_aceptar_icon" class="icon-check" aria-hidden="true"></i>';
		$sRes = $sRes . '<p id="boton_aceptar_title">Aceptar</p>';
		$sRes = $sRes . '</button>';
		$sRes = $sRes . '<button id="boton_cancelar" class="btn btn--container modal-close" type="button" aria-label="Cancelar elecci&oacute;n">';
		$sRes = $sRes . '<i id="modal_cancelar_icon" class="icon-closed" aria-hidden="true"></i>';
		$sRes = $sRes . '<p id="boton_cancelar_title">Cancelar</p>';
		$sRes = $sRes . '</button>';
		$sRes = $sRes . '</div>';
		$sRes = $sRes . '</div>';
		$sRes = $sRes . '</dialog>';
	}
	$sRes = $sRes . '</head>';
	$sRes = $sRes . '<body>';
	if (true) {
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
function forma_mitad($objForma = null, array $options = [])
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
		'rutas' => []
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
	$sRes = $sRes . '<header>';
	$sRes = $sRes . '<section class="banner" role="img" aria-label="' . $ETI['label_logo_campus_unad'] . '">';
	$sRes = $sRes . '</section>';
	$sRes = $sRes . '</header>';
	// Acá el breadcrum
	$iRutas = count($opts['rutas']);
	if ($iRutas) {
		$sRes = $sRes . '<section class="breadcrumb" role="navigation" aria-label="Ruta de navegación">';
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
function forma_piedepagina($bConTiempo = true)
{
	require './app.php';
	if (!function_exists('Traer_Entidad')) {
		require_once $APP->rutacomun . 'libdatos.php';
	}
	$idEntidad = Traer_Entidad();
	$sMuestra = 'display:none;';
	if ($bConTiempo) {
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
			$sRes = $sRes . '<p> PBX: <a href="tel:+5713443700" aria-label="Llamar a PBX Bogotá DC Colombia +57 1 344 37 00">(+57 1) 344 3700</a> Bogot&aacute; D.C., Colombia</p>';
			$sRes = $sRes . '<p> L&iacute;nea nacional gratuita desde Colombia: <a href="tel:018000115223" aria-label="Llamar a Línea nacional gratuita desde Colombia 01 8000 115 223">01 8000 115 223</a></p>';
			$sRes = $sRes . '<p> Atenci&oacute;n al usuario: <a href="mailto:atencionalusuario@unad.edu.co" aria-label="Enviar correo a atencionalusuario@unad.edu.co">atencionalusuario@unad.edu.co</a></p>';
			$sRes = $sRes . '<p> Instituci&oacute;n de Educaci&oacute;n Superior sujeta a inspecci&oacute;n y vigilancia por el Ministerio de Educaci&oacute;n Nacional</p>';
			break;
	}
	$sRes = $sRes . '</address>';
	$sRes = $sRes . '</footer>';
	// Scripts
	$sRes = $sRes . '<script src="' . $APP->rutacomun . 'js/campus2025.js"></script>';
	$sRes = $sRes . '<script type="text/javascript">';
	$sRes = $sRes . 'if (window.history.replaceState) {';
	$sRes = $sRes . 'window.history.replaceState(null, null, window.location.href);';
	$sRes = $sRes . '}';
	$sRes = $sRes . '</script>';
	// Fin Scripts
	$sRes = $sRes . '</body>';
	$sRes = $sRes . '</html>';
	echo $sRes;
}
