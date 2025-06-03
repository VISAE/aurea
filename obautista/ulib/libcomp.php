<?php
function log_debug($sLog = '')
{
	require './app.php';
	$iPiel = iDefinirPiel($APP, 2);
	$sRes = '';
	switch ($iPiel) {
		case 2:
			$sRes = $sRes . '<div class="log">';
			$sRes = $sRes . '<span class="log__time">' . fecha_microtiempo() . '</span>';
			$sRes = $sRes . '<code class="log__description">' . cadena_notildes($sLog) . '</code>';
			$sRes = $sRes . '</div>';
			break;
		default:
			// @@@ Por revision
			$sRes = $sRes . fecha_microtiempo() . cadena_notildes($sLog);
			break;
	}
	return $sRes;
}
function console_debug($sLogs = '')
{
	require './app.php';
	$iPiel = iDefinirPiel($APP, 2);
	$sRes = '';
	switch ($iPiel) {
		case 2:
			$sRes = $sRes . '<div id="div_debug" class="console-debug">';
			$sRes = $sRes . '<h2 class="debug__title">Console Debug</h2>';
			$sRes = $sRes . $sLogs;
			$sRes = $sRes . '</div>';
			break;
		default:
			//
			$sRes = $sRes . '<div id="div_debug">';
			$sRes = $sRes . $sLogs;
			$sRes = $sRes . '</div>';
			break;
	}
	return $sRes;
}
function slider()
{
	// El JS esta cargado en campos2024.js
	require './app.php';
	$sRes = '';
	$sRes = $sRes . '<div class="slider">';
	$sRes = $sRes . '<div class="slider__images">';
	$sRes = $sRes . '<img src="' . $APP->rutacomun . 'img/slide1.jpg">';
	$sRes = $sRes . '<img src="' . $APP->rutacomun . 'img/slide2.png">';
	$sRes = $sRes . '<img src="' . $APP->rutacomun . 'img/slide3.png">';
	$sRes = $sRes . '<img src="' . $APP->rutacomun . 'img/slide4.png">';
	$sRes = $sRes . '<img src="' . $APP->rutacomun . 'img/slide5.png">';
	$sRes = $sRes . '<button class="slider__prev" type="button" onclick="plusSlides(-1)">';
	$sRes = $sRes . '<i class="icon-navigate-before"></i>';
	$sRes = $sRes . '</button>';
	$sRes = $sRes . '<button class="slider__next" type="button" onclick="plusSlides(1)">';
	$sRes = $sRes . '<i class="icon-navigate-next"></i>';
	$sRes = $sRes . '</button>';
	$sRes = $sRes . '</div>';
	$sRes = $sRes . '<div class="slider__dots">';
	$sRes = $sRes . '</div>';
	$sRes = $sRes . '</div>';
	return $sRes;
}
function release()
{
	$sRes = '';
	$sRes = $sRes . '<div class="releases">';
	$sRes = $sRes . '<div>';
	$sRes = $sRes . '<i class="iNewReleases"></i>';
	$sRes = $sRes . '<b>&iexcl;Tenemos nuevo dise&ntilde;o!</b>';
	$sRes = $sRes . '</div>';
	$sRes = $sRes . '<p>Hicimos cambios en el dise&ntilde;o de esta ventana. <b>Para cambiar de visual, haga clic en el siguiente bot&oacute;n.</b></p>';
	$sRes = $sRes . '<button class="btn-tertiary" type="button" onclick="modificavisual()">';
	$sRes = $sRes . '<i class="iSync"></i>';
	$sRes = $sRes . 'Visual 2024';
	$sRes = $sRes . '</button>';
	$sRes = $sRes . '</div>';
	return $sRes;
}
function widgetAccess()
{
	$sRes = '';
	$sRes = $sRes . '<section id="widget-access">';
	$sRes = $sRes . '<div id="open-widget">';
	$sRes = $sRes . '<i class="icon-accesibility"></i>';
	$sRes = $sRes . '<i class="icon-settings-accesibility"></i>';
	$sRes = $sRes . '</div>';
	$sRes = $sRes . '<div class="widget__header">';
	$sRes = $sRes . '<div class="header__title">';
	$sRes = $sRes . '<i class="icon-accesibility"></i>';
	$sRes = $sRes . '<h1>Men&uacute; de accesibilidad</h1>';
	$sRes = $sRes . '</div>';
	$sRes = $sRes . '<button id="closed-widget" class="btn-primary">';
	$sRes = $sRes . '<i class="icon-navigate-next"></i>';
	$sRes = $sRes . '</button>';
	$sRes = $sRes . '</div>';
	$sRes = $sRes . '<div class="widget__content">';
	$sRes = $sRes . '<div id="boton-mas">';
	$sRes = $sRes . '<i class="icon-add"></i>';
	$sRes = $sRes . 'Aumentar';
	$sRes = $sRes . '</div>';
	$sRes = $sRes . '<div id="boton-menos">';
	$sRes = $sRes . '<i class="icon-remove"></i>';
	$sRes = $sRes . 'Disminuir';
	$sRes = $sRes . '</div>';
	$sRes = $sRes . '<div id="boton-claro">';
	$sRes = $sRes . '<i class="icon-check"></i>';
	$sRes = $sRes . '<i class="icon-light-mode-fill"></i>';
	$sRes = $sRes . 'Claro';
	$sRes = $sRes . '</div>';
	$sRes = $sRes . '<div id="boton-oscuro">';
	$sRes = $sRes . '<i class="icon-check"></i>';
	$sRes = $sRes . '<i class="icon-dark-mode-fill"></i>';
	$sRes = $sRes . 'Oscuro';
	$sRes = $sRes . '</div>';
	$sRes = $sRes . '<div id="boton-contraste">';
	$sRes = $sRes . '<i class="icon-contrast-mode"></i>';
	$sRes = $sRes . 'Contraste';
	$sRes = $sRes . '<div>';
	$sRes = $sRes . '<span></span>';
	$sRes = $sRes . '<span></span>';
	$sRes = $sRes . '</div>';
	$sRes = $sRes . '</div>';
	$sRes = $sRes . '<div id="boton-saturacion">';
	$sRes = $sRes . '<i class="icon-invert-colors"></i>';
	$sRes = $sRes . 'Saturaci&oacute;n';
	$sRes = $sRes . '<div>';
	$sRes = $sRes . '<span></span>';
	$sRes = $sRes . '<span></span>';
	$sRes = $sRes . '<span></span>';
	$sRes = $sRes . '</div>';
	$sRes = $sRes . '</div>';
	$sRes = $sRes . '<div id="boton-cursor">';
	$sRes = $sRes . '<i class="icon-check"></i>';
	$sRes = $sRes . '<i class="iCursor"></i>';
	$sRes = $sRes . '</div>';
	$sRes = $sRes . '<div id="boton-lectura">';
	$sRes = $sRes . '<i class="icon-check"></i>';
	$sRes = $sRes . '<i class="icon-match-word"></i>';
	$sRes = $sRes . '</div>';
	$sRes = $sRes . '<button id="boton-reinicio" class="btn-tertiary">';
	$sRes = $sRes . '<i class="icon-restart"></i>';
	$sRes = $sRes . 'Restablecer Configuraciones';
	$sRes = $sRes . '</button>';
	$sRes = $sRes . '</div>';
	$sRes = $sRes . '</section>';
	$sRes = $sRes . '<section id="loupe">';
	$sRes = $sRes . '<div id="loupe-up"></div>';
	$sRes = $sRes . '<div id="loupe-center"></div>';
	$sRes = $sRes . '<div id="loupe-down"></div>';
	$sRes = $sRes . '</section>';
	return $sRes;
}
