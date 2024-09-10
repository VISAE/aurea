<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2023 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- © Edwin Leandro Moreno Guerra - UNAD - 2023 ---
--- edwin.moreno@unad.edu.co - http://www.unad.edu.co
*/
function forma_InicioV4($XAJAX = NULL, $sNomModulo = '', $bIncluyePWA = false, $idSistema = 0)
{
	require './app.php';
	$iPiel = iDefinirPiel($APP, 2);
	$sRes = '<!DOCTYPE html><html><head>';
	if ($XAJAX != NULL) {
		echo $sRes;
		$sRes = '';
		$XAJAX->printJavascript();
	}
	if (isset($APP->ihost) != 0) {
		if ($APP->ihost != 0) {
			$sHost = gethostname();
			$sNomModulo = '[' . $sHost . '] ' . $sNomModulo;
		}
	}
	if ($sNomModulo != '') {
		$sNomModulo = $sNomModulo . ' - ';
	}
	$sRes = $sRes . '<title>' . $sNomModulo . 'Universidad Nacional Abierta y a Distancia - UNAD</title>';
	$sRes = $sRes . '<meta charset="UTF-8">';
	$sRes = $sRes . '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
	$sRes = $sRes . '<link href="' . $APP->rutacomun . 'css/favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon" />';
	$sRes = $sRes . '<link rel="stylesheet" href="' . $APP->rutacomun . 'css/formav2.css?v=2" type="text/css" />';
	if ($bIncluyePWA) {
		$sRes = $sRes . '<meta name="MobileOptimized" content="width">';
		$sRes = $sRes . '<meta name="HandheldFriendly" content="true">';
		$sRes = $sRes . '<meta name="apple-mobile-web-app-capable" content="yes">';
		$sRes = $sRes . '<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">';
		$sRes = $sRes . '<meta name="theme-color" content="#024D65">';
		$sRes = $sRes . '<link rel="apple-touch-icon" href="./pwa/icons/icon_192.png">';
		$sRes = $sRes . '<link rel="apple-touch-startup-image" href="./pwa/icons/icon_1024.png">';
		$sRes = $sRes . '<link rel="manifest" href="./manifest.json">';
		$sRes = $sRes . '<script type="text/javascript" src="pwa/js/browser_detect.js"></script>';
		$sRes = $sRes . '<link rel="stylesheet" href="./pwa/css/site.css">';
	}
	$sRes = $sRes . '<script language="javascript" src="' . $APP->rutacomun . 'js/jquery-3.3.1.min.js"></script>';
	if (false) {
		$sRes = $sRes . '<script language="javascript" src="' . $APP->rutacomun . 'js/popper.min.js"></script>';
		$sRes = $sRes . '<script language="javascript" src="' . $APP->rutacomun . 'js/bootstrap.min.js"></script>';
		$sRes = $sRes . '<link rel="stylesheet" href="' . $APP->rutacomun . 'js/bootstrap.min.css" type="text/css" />';
	}
	$sRes = $sRes . '<link rel="stylesheet" href="' . $APP->rutacomun . 'css/criticalPath.css" type="text/css" />';
	$sRes = $sRes . '<link rel="stylesheet" href="' . $APP->rutacomun . 'css/principal.css" type="text/css" />';
	$sRes = $sRes . '<link rel="stylesheet" href="' . $APP->rutacomun . 'unad_estilos2018.css" type="text/css" />';
	$sRes = $sRes . '<link rel="stylesheet" href="' . $APP->rutacomun . 'css/general.css?v=1">';
	$sRes = $sRes . '<link rel="stylesheet" href="' . $APP->rutacomun . 'js/chosen.css">';
	$sRes = $sRes . '<link rel="stylesheet" href="' . $APP->rutacomun . 'css/aurea2024.css">';
	echo $sRes;
}
function forma_cabeceraV4($aRutas = array(), $aBotones = array(), $bConMenu = true, $bDebug = false, $bCompatibilidad = false)
{
	$sRes = forma_cabeceraV4b($aRutas, $aBotones, $bConMenu, 1);
	echo $sRes;
}
function forma_cabeceraV4b($aRutas = array(), $aBotones = array(), $bConMenu = true, $iSectorForma = 1)
{
	require './app.php';
	$sRes = '';
	$sBaseTitulo = ' ';
	$iRutas = 0;
	$iBotones = 0;
	$iSecciones = 1;
	$aSeccion = array('', '1');
	if (is_array($aRutas)) {
		for ($k = 0; $k <= 2; $k++) {
			if (isset($aRutas[$k][0]) != 0) {
				if (isset($aRutas[$k][1]) != 0) {
					$iRutas++;
				} else {
					$k = 4;
				}
			} else {
				$k = 4;
			}
		}
	}
	if ($iRutas > 2) {
		$sBaseTitulo = $aRutas[2][1];
	}
	if ($sBaseTitulo != '') {
		$sBaseTitulo = $sBaseTitulo . ' - ';
	}
	if (is_array($aBotones)) {
		$iCajas = count($aBotones);
		$iPasos = $iCajas - 1;
		for ($k = 0; $k <= $iPasos; $k++) {
			if (isset($aBotones[$k][0]) != 0) {
				if (isset($aBotones[$k][1]) != 0) {
					$iBotones++;
					if (isset($aBotones[$k][3]) == 0) {
						$aBotones[$k][3] = 1;
					}
				} else {
					$k = $iCajas;
				}
			} else {
				$k = $iCajas;
			}
		}
	}
	$sLogoSistema = '<img src="' . $APP->rutacomun . 'img/logoAUREA.svg" alt=""><p>AUREA</p>';
	$idSistema = 0;
	if (isset($APP->idsistema) != 0) {
		$idSistema = $APP->idsistema;
	}
	$aSistema = array('', '', '', '', '', 'PRESUPUESTO', '', 'FACTURACION', 'TESORERIA', 'CARTERA', 
	'', '', '', '', '', '', 'CONSECUTIVOS', '', '', '', 
	'', '', '', '', '', '', '', 'GRADOS', '', '', 
	'SAI', '', '', '', '', '', '', 'SIGTools', '', '', 
	'', '', '', '', '', '', 'GAF', '', '', '', 
	'MUMO17', '', '', '', '', '', '', '', '', '', 
	'', '', '', '', '', '', '', '', '', '');
	$aLogo = array('', '', '', '', '', 'presupuesto', '', 'facturacion', 'tesoreria', 'cartera', 
	'', '', '', '', '', '', 'consecutivos', '', '', '', 
	'', '', '', '', '', '', '', 'grados', '', '', 
	'sai', '', '', '', '', '', '', 'sig', '', '', 
	'', '', '', '', '', '', 'aurea', '', '', '', 
	'aurea', '', '', '', '', '', '', '', '', '', 
	'', '', '', '', '', '', '', '', '', '');
	switch ($idSistema) {
		case 11: //CONTABILIDAD
			$sLogoSistema = '<img src="' . $APP->rutacomun . 'img/2023/contabilidad.svg" alt=""><p>CONTABILIDAD</p>';
			break;
		case 17: //OAI
			$sLogoSistema = '<img src="' . $APP->rutacomun . 'img/2023/oai.svg" alt=""><p>OAI</p>';
			break;
		case 19: // EVENTOS
			$sLogoSistema = '<img src="' . $APP->rutacomun . 'img/2023/eventos.svg" alt=""><p>EVENTOS</p>';
			break;
		case 21: //OIL
			$sLogoSistema = '<img src="' . $APP->rutacomun . 'img/2023/oil.svg" alt=""><p>OIL</p>';
			break;
		case 22: //CORE
			$sLogoSistema = '<img src="' . $APP->rutacomun . 'img/2023/core.svg" alt=""><p>CORE</p>';
			break;
		case 24: //C2
			$sLogoSistema = '<img src="' . $APP->rutacomun . 'img/2023/c2.svg" alt=""><p>C2</p>';
			break;
		case 28: //ANALITICA
			$sLogoSistema = '<img src="' . $APP->rutacomun . 'img/2023/analitica.svg" alt=""><p>ANALITICA</p>';
			break;
		case 36: //SIGMA
			$sLogoSistema = '<img src="' . $APP->rutacomun . 'img/2023/sigma.svg" alt=""><p>SIGMA</p>';
			break;
		case 39: //COMPRAS
			$sLogoSistema = '<img src="' . $APP->rutacomun . 'img/2023/compras.svg" alt=""><p>COMPRAS</p>';
			break;
		case 40: //INVENTARIOS
			$sLogoSistema = '<img src="' . $APP->rutacomun . 'img/2023/inventarios.svg" alt=""><p>INVENTARIOS</p>';
			break;
		case 41: //CONTRATACION
			$sLogoSistema = '<img src="' . $APP->rutacomun . 'img/2023/contratacion.svg" alt=""><p>CONTRATACION</p>';
			break;
		case 45: //SUEV
			$sLogoSistema = '<img src="' . $APP->rutacomun . 'img/2023/suev.svg" alt=""><p>SUEV</p>';
			break;
		case 49: //SINEP
			$sLogoSistema = '<img src="' . $APP->rutacomun . 'img/2023/sinep.svg" alt=""><p>SINEP</p>';
			break;
		default: // 
			$sLogo = '';
			$sSigla = '';
			if (isset($aLogo[$idSistema]) != 0) {
				if ($aLogo[$idSistema] != '') {
					$sLogo = $aLogo[$idSistema];
					$sSigla = $aSistema[$idSistema];
				}
			}
			if ($sLogo != '') {
				$sLogoSistema = '<img src="' . $APP->rutacomun . 'img/2023/' . $sLogo . '.svg" alt=""><p>' . $sSigla . '</p>';
			}
			break;
	}
	$sClassBody = 'light-theme';
	$sRes = $sRes . '</head>';
	$sRes = $sRes . '<body class="">';
	$sRes = $sRes . '<header>';
	$sRes = $sRes . '<section id="banner" class="banner">';
	$sRes = $sRes . '<div class="banner__left"></div>';
	$sRes = $sRes . '<div class="banner__center"></div>';
	$sRes = $sRes . '<div class="banner__right"></div>';
	$sRes = $sRes . '</section>';
	$sRes = $sRes . '<section id="breadcrumb">';
	$sRes = $sRes . '<div class="breadcrumb--mobile">';
	$sRes = $sRes . '<a href="#" class="system__icon">' . $sLogoSistema . '</a>';
	$sRes = $sRes . '<button class="back-route--mobile">';
	$sRes = $sRes . '<i class="iNavigateBefore"></i>';
	$sRes = $sRes . '</button>';
	$sRes = $sRes . '</div>';
	$sRes = $sRes . '<div class="breadcrumb--desktop">';
	$sRes = $sRes . '<div class="breadcrumb__route">';
	$sRes = $sRes . '<button class="route--mobile">';
	$sRes = $sRes . '<i class="iMenu"></i>';
	$sRes = $sRes . '</button>';
	$sRes = $sRes . '<div class="route--desktop">';
	// Revisar rutas
	if ($iRutas > 0) {
		$sRes = $sRes . '';
		for ($k = 0; $k < $iRutas; $k++) {
			if ($k > 0) {
				$sRes = $sRes . '<span>/</span>';
			}
			if ($aRutas[$k][0] == '') {
				$sId = '';
				if ($k == 2 || ($k == 1 && $iRutas == 2)) {
					$sId = ' id="div_titulo_modulo"';
				}
				$sRes = $sRes . '<span' . $sId . '>' . $aRutas[$k][1] . '</span>';
			} else {
				// $sRes = $sRes . '<a href="' . $aRutas[$k][0] . '">' . $aRutas[$k][1] . '</a>';
				$sRes = $sRes . '<a href="' . $aRutas[$k][0] . '">' . cadena_decodificar($aRutas[$k][1]) . '</a>';
			}
		}
	}
	$sRes = $sRes . '</div></div>';
	$sRes = $sRes . '<div id="botones_sup"><div class="breadcrumb__buttons">';
	$sVisualSeccion = '';
	if ($iSectorForma != 1) {
		$sVisualSeccion = ' style="display:none"';
	}
	$sRes = $sRes . '<section id="botones_sector1"' . $sVisualSeccion . '>';
	$iSeccionActual = 1;
	for ($k = 0; $k < $iBotones; $k++) {
		if ($iSeccionActual != $aBotones[$k][3]) {
			$iSeccionActual = $aBotones[$k][3];
			$sVisualSeccion = ' style="display:none"';
			if ($iSectorForma == $iSeccionActual) {
				$sVisualSeccion = '';
			}
			$sRes = $sRes . '</section><section id="botones_sector' . $iSeccionActual . '"' . $sVisualSeccion . '>';
		}
		$sClase = 'iHelp';
		$sTitulo = '';
		if (isset($aBotones[$k][2]) != 0) {
			$sClase = $aBotones[$k][2];
		}
		$sLink = '';
		if (isset($aBotones[$k][0]) != 0) {
			$sLink = ' onclick="' . $aBotones[$k][0] . '"';
		}
		if (isset($aBotones[$k][1]) != 0) {
			$sTitulo = ' title="' . $aBotones[$k][1] . '"';
		}
		$sRes = $sRes . '<button' . $sLink . $sTitulo . '>';
		$sRes = $sRes . '<i class="' . $sClase . '"></i>';
		$sRes = $sRes . '</button>';
	}
	$sRes = $sRes . '</section>';
	$sRes = $sRes . '</div></div></div>';
	$sRes = $sRes . '</section>';
	$sRes = $sRes . '</header>';
	$sRes = $sRes . '<script language="JavaScript">';
	$sRes = $sRes . 'function muestraayuda(app, modulo){';
	$sRes = $sRes . 'window.document.frmayuda.com.value=modulo;';
	$sRes = $sRes . 'window.document.frmayuda.submit();';
	$sRes = $sRes . '}';
	$sRes = $sRes . '</script>';
	$sRes = $sRes . '<form id="frmayuda" name="frmayuda" action="unadayudas.php" method="post" target="_blank">';
	$sRes = $sRes . '<input id="com" name="com" type="hidden" value="0" />';
	$sRes = $sRes . '</form>';
	// Modal bootstrap
	if (false) {
		$sRes = $sRes . '<div class="modal fade" id="ModalAdvertencia" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" style="display:none;">';
		$sRes = $sRes . '<div class="modal-dialog modal-dialog-centered" role="document">';
		$sRes = $sRes . '<div class="modal-content">';
		$sRes = $sRes . '<div class="modal-header bg-primary text-light">';
		$sRes = $sRes . '<h5 class="modal-title" id="exampleModalLongTitle">Advertencia!</h5>';
		$sRes = $sRes . '<button type="button" class="close" data-dismiss="modal" aria-label="Close">';
		$sRes = $sRes . '<span aria-hidden="true">&times;</span>';
		$sRes = $sRes . '</button>';
		$sRes = $sRes . '</div>';
		$sRes = $sRes . '<div class="modal-body" id="ModalCuerpoAdv">';
		$sRes = $sRes . '...';
		$sRes = $sRes . '</div>';
		$sRes = $sRes . '<div class="modal-footer">';
		$sRes = $sRes . '<button type="button" class="btn btn-primary" style="font-size: 16px;" data-dismiss="modal">Aceptar</button>';
		$sRes = $sRes . '</div>';
		$sRes = $sRes . '</div>';
		$sRes = $sRes . '</div>';
		$sRes = $sRes . '</div>';
		$sRes = $sRes . '<div class="modal fade" id="ModalConfirm" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" style="display:none;">';
		$sRes = $sRes . '<div class="modal-dialog modal-dialog-centered" role="document">';
		$sRes = $sRes . '<div class="modal-content">';
		$sRes = $sRes . '<div class="modal-header bg-primary text-light">';
		$sRes = $sRes . '<h5 class="modal-title" id="exampleModalLongTitle">Advertencia!</h5>';
		$sRes = $sRes . '<button type="button" class="close" data-dismiss="modal" aria-label="Close">';
		$sRes = $sRes . '<span aria-hidden="true">&times;</span>';
		$sRes = $sRes . '</button>';
		$sRes = $sRes . '</div>';
		$sRes = $sRes . '<div class="modal-body" id="ModalCuerpoCon">';
		$sRes = $sRes . '...';
		$sRes = $sRes . '</div>';
		$sRes = $sRes . '<div class="modal-footer">';
		$sRes = $sRes . '<button id="modal-btn-si" type="button" class="btn btn-success" style="font-size: 16px;" data-dismiss="modal">Aceptar</button>';
		$sRes = $sRes . '<button id="modal-btn-no" type="button" class="btn btn-danger" style="font-size: 16px;" data-dismiss="modal">Cancelar</button>';
		$sRes = $sRes . '</div>';
		$sRes = $sRes . '</div>';
		$sRes = $sRes . '</div>';
		$sRes = $sRes . '</div>';
	}
	if (true) {
		// Modal nuevo
		$sRes = $sRes . '<div id="div_modal" class="modal">';
		$sRes = $sRes . '<div class="div_bloque-confirmacion">';
		$sRes = $sRes . '<div class="modal__header">';
		$sRes = $sRes . '<div class="modal__title">';
		$sRes = $sRes . '<button class="modal-close" type="button">';
		$sRes = $sRes . 'Cerrar';
		$sRes = $sRes . '<i class="icon-closed"></i>';
		$sRes = $sRes . '</button>';
		$sRes = $sRes . '<i class="icon-warning"></i>';
		$sRes = $sRes . '<p id="modal__title">Advertencia</p>';
		$sRes = $sRes . '</div>';
		$sRes = $sRes . '</div>';
		$sRes = $sRes . '<div id="modal__body" class="modal__body">';
		$sRes = $sRes . '</div>';
		$sRes = $sRes . '<div class="modal__footer">';
		$sRes = $sRes . '<button id="boton__aceptar" class="btn-tertiary modal-close" type="button">';
		$sRes = $sRes . '<i class="icon-check"></i>';
		$sRes = $sRes . '<p id="boton__aceptar__title">Aceptar</p>';
		$sRes = $sRes . '</button>';
		$sRes = $sRes . '<button id="boton__cancelar" class="btn-container modal-close" type="button">';
		$sRes = $sRes . '<i class="icon-closed"></i>';
		$sRes = $sRes . '<p id="boton__cancelar__title">Cancelar</p>';
		$sRes = $sRes . '</button>';
		$sRes = $sRes . '</div>';
		$sRes = $sRes . '</div>';
		$sRes = $sRes . '</div>';
	}
	if ($bConMenu) {
		//$sRes = $sRes . '<div class="menuSuperior"><ul>';
	}
	$sRes = $sRes . '<nav class="nav" id="nav">';
	$sRes = $sRes . '<div id="navbar">';
	$sRes = $sRes . '<section id="menu-options">';
	$sRes = $sRes . '<div class="system__icon">';
	$sRes = $sRes . '<div>' . $sLogoSistema . '</div>';
	$sRes = $sRes . '<i id="anchor-menu" class="iPin" title="Anclar menu"></i>';
	$sRes = $sRes . '</div>';
	$sRes = $sRes . '<ul class="navbar__options">';
	//
	echo $sRes;
}
function forma_mitad($idTercero = 0)
{
	require './app.php';
	$iPiel = iDefinirPiel($APP, 2);
	$sRes = '';
	$sRes = $sRes . '</ul>';
	$sRes = $sRes . '</section>';
	$sRes = $sRes . '<section id="menu-help">';
	$sRes = $sRes . '<ul class="navbar__options">';
	$sRes = $sRes . '<li class="options__item">';
	$sRes = $sRes . '<a class="option">';
	$sRes = $sRes . '<i class="iLightModeFill"></i>';
	$sRes = $sRes . '<p>Accesibilidad</p>';
	$sRes = $sRes . '<i class="iNavigateNext"></i></a>';
	$sRes = $sRes . '<div class="option-drop accesibilidad">';
	$sRes = $sRes . '<ul>';
	$sRes = $sRes . '<li>';
	$sRes = $sRes . '<a id="boton-reinicio">';
	$sRes = $sRes . '<i class="iRestart"></i>';
	$sRes = $sRes . 'Reiniciar';
	$sRes = $sRes . '</a>';
	$sRes = $sRes . '</li>';
	$sRes = $sRes . '<li>';
	$sRes = $sRes . '<a id="boton-menos">';
	$sRes = $sRes . '<i class="iZoomOut"></i>';
	$sRes = $sRes . 'Reducir';
	$sRes = $sRes . '</a>';
	$sRes = $sRes . '</li>';
	$sRes = $sRes . '<li>';
	$sRes = $sRes . '<a id="boton-mas">';
	$sRes = $sRes . '<i class="iZoomIn"></i>';
	$sRes = $sRes . 'Aumentar';
	$sRes = $sRes . '</a>';
	$sRes = $sRes . '</li>';
	$sRes = $sRes . '<li>';
	$sRes = $sRes . '<a id="boton-contraste">';
	$sRes = $sRes . '<i class="iContrastMode"></i>';
	$sRes = $sRes . 'Contrast';
	$sRes = $sRes . '</a>';
	$sRes = $sRes . '</li>';
	$sRes = $sRes . '<li>';
	$sRes = $sRes . '<a id="boton-oscuro">';
	$sRes = $sRes . '<i class="iDarkModeFill"></i>';
	$sRes = $sRes . 'Oscuro';
	$sRes = $sRes . '</a>';
	$sRes = $sRes . '</li>';
	$sRes = $sRes . '<li>';
	$sRes = $sRes . '<a id="boton-claro">';
	$sRes = $sRes . '<i class="iLightModeFill"></i>';
	$sRes = $sRes . 'Claro';
	$sRes = $sRes . '</a>';
	$sRes = $sRes . '</li>';
	$sRes = $sRes . '</ul>';
	$sRes = $sRes . '</div>';
	$sRes = $sRes . '</li>';
	$sRes = $sRes . '<li class="options__item">';
	$sRes = $sRes . '<a class="option">';
	$sRes = $sRes . '<i class="iHelpFill"></i>';
	$sRes = $sRes . '<p>Ayuda</p>';
	$sRes = $sRes . '<i class="iNavigateNext"></i>';
	$sRes = $sRes . '</a>';
	$sRes = $sRes . '<div class="option-drop ayuda">';
	$sRes = $sRes . '<ul>';
	$sRes = $sRes . '<li><a href="./unadayudas.php">Manuales</a></li>';
	$sRes = $sRes . '<li><a href="./acercade.php">Acerca de...</a></li>';
	$sRes = $sRes . '</ul>';
	$sRes = $sRes . '</div>';
	$sRes = $sRes . '</li>';
	if ((int)$idTercero != 0) {
		$sRes = $sRes . '<li class="options__item">';
		$sRes = $sRes . '<a class="option" href="./salir.php">';
		$sRes = $sRes . '<i class="iLogout"></i>';
		$sRes = $sRes . '<p>Salir</p>';
		$sRes = $sRes . '</a>';
		$sRes = $sRes . '</li>';
	}
	$sRes = $sRes . '</ul>';
	$sRes = $sRes . '</section>';
	$sRes = $sRes . '</div>';
	$sRes = $sRes . '</nav>';
	$sRes = $sRes . '<main>';
	//
	echo $sRes;
}
function forma_piedepagina($bConTiempo = true, $sAdd = '')
{
	require './app.php';
	$idEntidad = Traer_Entidad();
	$sMuestra = '';
	$sRes = '';
	if (!$bConTiempo) {
		$sMuestra = 'display:none;';
	}
	$sRes = $sRes . '<div class="salto1px"></div>';
	$sRes = $sRes . '<div id="div_tiempo" style="width:150px;' . $sMuestra . '" class="ir_derecha"></div>';
	$sRes = $sRes . '<div class="salto1px"></div>';
	$sRes = $sRes . '</main>';
	$sRes = $sRes . '<footer>';
	$sRes = $sRes . '<span></span>';
	$sRes = $sRes . '<div>';
	switch ($idEntidad) {
		case 1: // Florida
			break;
		case 2: // Union Europea
			$sRes = $sRes . '<p>';
			$sRes = $sRes . 'Edificio Universidad Rey Juan Carlos. Plaza de Manuel Becerra, 14. 28028 Madrid';
			$sRes = $sRes . '</p>';
			$sRes = $sRes . '<p>';
			$sRes = $sRes . 'Atenci&oacute;n al usuario: <a href="mailto:informacion@unad-ue.es">informacion@unad-ue.es</a>';
			$sRes = $sRes . '</p>';
			break;
		default:
			$sRes = $sRes . '<p>';
			$sRes = $sRes . 'Sede nacional Jos&eacute; Celestino Mutis: <a href="https://goo.gl/maps/9vSBgNcPa6SKYo7HA" target="_blank">Calle 14 sur No. 14 - 23</a>';
			$sRes = $sRes . '</p>';
			$sRes = $sRes . '<p>';
			$sRes = $sRes . 'PBX: <a href="tel:+576013443700">(+57 601) 344 3700</a> Bogotá D.C., Colombia';
			$sRes = $sRes . '</p>';
			$sRes = $sRes . '<p>';
			$sRes = $sRes . 'Línea nacional gratuita desde Colombia: <a href="tel:018000115223">01 8000 115 223</a>';
			$sRes = $sRes . '</p>';
			$sRes = $sRes . '<p>';
			$sRes = $sRes . 'Atenci&oacute;n al usuario: <a href="mailto:atencionalusuario@unad.edu.co">atencionalusuario@unad.edu.co</a>';
			$sRes = $sRes . '</p>';
			$sRes = $sRes . '<p>';
			$sRes = $sRes . 'Instituci&oacute;n de Educaci&oacute;n Superior sujeta a inspecci&oacute;n y vigilancia por el Ministerio de Educaci&oacute;n Nacional';
			$sRes = $sRes . '</p>';
			break;
	}
	$sRes = $sRes . '</div>';
	$sRes = $sRes . '</footer>';
	$sRes = $sRes . '<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">';
	$sRes = $sRes . '<script type="text/javascript">';
	// Script del Modal Confirm
	$sRes = $sRes . 'var ModalDialogConfirm = function(callback){';
	$sRes = $sRes . 'jQuery.noConflict();';
	$sRes = $sRes . 'jQuery("#modal-btn-si").on("click", function(){';
	$sRes = $sRes . 'callback(true);';
	$sRes = $sRes . 'jQuery("#ModalConfirm").modal("hide");';
	$sRes = $sRes . '});';
	$sRes = $sRes . 'jQuery("#modal-btn-no").on("click", function(){';
	$sRes = $sRes . 'callback(false);';
	$sRes = $sRes . 'jQuery("#ModalConfirm").modal("hide");';
	$sRes = $sRes . '});';
	$sRes = $sRes . '};';
	$sRes = $sRes . '</script>';
	$sRes = $sRes . '<script src="' . $APP->rutacomun . 'js/aurea2024.js"></script>';
	$sRes = $sRes . '<script type="text/javascript">';
	$sRes = $sRes . 'if (window.history.replaceState) {';
	$sRes = $sRes . 'window.history.replaceState(null, null, window.location.href);';
	$sRes = $sRes . '}';
	$sRes = $sRes . '</script>';
	$sRes = $sRes . $sAdd;
	$sRes = $sRes . '</body>';
	$sRes = $sRes . '</html>';
	// Fin
	echo $sRes;
}

