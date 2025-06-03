<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2024 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
*/
function forma_cabecera($XAJAX = NULL, $sTituloModulo = '', $bConMenu = true, $bIncluyePWA = false)
{
	require './app.php';
	$sRes = '<!DOCTYPE html><html lang="es"><head>';
	if ($XAJAX != NULL) {
		echo $sRes;
		$sRes = '';
		$XAJAX->printJavascript();
	}
	//Julio 7 de 2020 se agrega el host si el parametro esta marcado.
	if (isset($APP->ihost) != 0) {
		if ($APP->ihost != 0) {
			$sHost = gethostname();
			$sTituloModulo = '[' . $sHost . '] ' . $sTituloModulo;
		}
	}
	$sRes = $sRes . '<meta charset="UTF-8">';
	$sRes = $sRes . '<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">';
	$sRes = $sRes . '<title>' . $sTituloModulo . ' - Universidad Nacional Abierta y a Distancia - UNAD</title>';
	$sRes = $sRes . '<link rel="stylesheet" href="' . $APP->rutacomun . 'css/campus2024.css">';
	$sRes = $sRes . '<script language="javascript" src="' . $APP->rutacomun . 'js/jquery-3.3.1.min.js"></script>';
	// Modal
	if (true) {
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
	$sRes = $sRes . '</head>';
	$sRes = $sRes . '<body>';
	if ($bConMenu) {
		$sRes = $sRes . '<nav><ul class="navmenu">';
	}
	echo $sRes;
}
function forma_mitad($objForma = null, $bConNavBar = false, $sTituloModulo = '', $aBotones = array(), $bConMenu = true)
{
	require './app.php';
	$sRes = '';
	if ($bConMenu) {
		$sRes = $sRes . '</ul></nav>';
	}
	$sRes = $sRes . '<header>';
	$sRes = $sRes . '<section class="banner">';
	$sRes = $sRes . '<div class="banner__center"></div>';
	$sRes = $sRes . '</section>';
	$sRes = $sRes . '</header>';
	$sRes = $sRes . '<main>';
	if ($bConNavBar) {
		$sRes = $sRes . '<div class="navbar-bg">';
		$sRes = $sRes . '<div class="navbar">';
		$sRes = $sRes . '<h1 class="navbar__title">';
		$sRes = $sRes . $sTituloModulo;
		$sRes = $sRes . '</h1>';
		$sRes = $sRes . '<div class="navbar__options">';
		// Contar cuantos botones vienen
		$iBotones = 0;
		if (is_array($aBotones)) {
			$iCajas = count($aBotones);
			for ($k = 0; $k <= $iCajas - 1; $k++) {
				if (isset($aBotones[$k][0]) != 0) {
					if (isset($aBotones[$k][1]) != 0) {
						$iBotones++;
					} else {
						$k = $iCajas;
					}
				} else {
					$k = $iCajas;
				}
			}
		}
		// Pintar los botones
		for ($k = 0; $k < $iBotones; $k++) {
			$sClase = 'btMiniAyuda';
			$sTitulo = '';
			$sAccion = '';
			if (isset($aBotones[$k][0]) != 0) {
				$sAccion = $aBotones[$k][0];
			}
			if (isset($aBotones[$k][1]) != 0) {
				$sTitulo = $aBotones[$k][1];
			}
			if (isset($aBotones[$k][2]) != 0) {
				$sClase = $aBotones[$k][2];
			}
			$sRes = $sRes . $objForma->htmlBotonSolo('', $sClase, $sAccion, $sTitulo);
		}
		$sRes = $sRes . '</div>';
		$sRes = $sRes . '</div>';
		$sRes = $sRes . '</div>';
	}
	echo $sRes;
}
function forma_piedepagina($bConAccess = true, $bConTiempo = true)
{
	require './app.php';
	$sMuestra = 'display:none;';
	if ($bConTiempo) {
		$sMuestra = '';
	}
	$sRes = '';
	$sRes = $sRes . '<div class="salto1px"></div>';
	$sRes = $sRes . '<div id="div_tiempo" style="width:150px;' . $sMuestra . '" class="ir_derecha"></div>';
	$sRes = $sRes . '<div class="salto1px"></div>';
	$sRes = $sRes . '</main>';
	$sRes = $sRes . '<footer>';
	$sRes = $sRes . '<span></span>';
	$sRes = $sRes . '<div>';
	$sRes = $sRes . '<p> Sede nacional José Celestino Mutis: <a href="#">Calle 14 sur No. 14 - 23</a></p>';
	$sRes = $sRes . '<p> PBX: <a href="">(+57 1) 344 3700</a> Bogotá D.C., Colombia</p>';
	$sRes = $sRes . '<p> Línea nacional gratuita desde Colombia: <a href="">01 8000 115 223</a></p>';
	$sRes = $sRes . '<p> Atención al usuario: <a href="">atencionalusuario@unad.edu.co</a></p>';
	$sRes = $sRes . '<p> Institución de Educación Superior sujeta a inspección y vigilancia por el Ministerio de Educación Nacional</p>';
	$sRes = $sRes . '</div>';
	$sRes = $sRes . '</footer>';
	// Widget Accesibilidad
	if ($bConAccess) {
		$sRes = $sRes . widgetAccess();
	}
	// Scripts
	$sRes = $sRes . '<script src="' . $APP->rutacomun . 'js/campus2024.js"></script>';
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
