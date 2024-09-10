<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2018 - 2020 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
*/
function forma_cabeceraV2($CFG, $SITE, $XAJAX = NULL, $modnombre = '', $sLinks = '')
{
	forma_cabeceraV3($XAJAX, $modnombre);
}
function forma_cabeceraV3($XAJAX = NULL, $modnombre = '', $bConMenu = true, $bIncluyePWA = false)
{
	require './app.php';
	$sRes = '<!DOCTYPE html>';
	$sRes = $sRes . '<html>';
	$sRes = $sRes . '<head>';
	if ($XAJAX != NULL) {
		echo $sRes;
		$sRes = '';
		$sRes = $sRes . $XAJAX->printJavascript();
	}
	$sRes = $sRes . '<meta charset="utf-8">';
	$sBaseTitulo = $modnombre;
	if ($modnombre != '') {
		$sBaseTitulo = $modnombre . ' - ';
	}
	//Julio 7 de 2020 se agrega el host si el parametro esta marcado.
	if (isset($APP->ihost) != 0) {
		if ($APP->ihost != 0) {
			$sHost = gethostname();
			$sBaseTitulo = '[' . $sHost . '] ' . $sBaseTitulo;
		}
	}
	$sAddMenu = '<div class="menuSuperior"><ul>';
	if (!$bConMenu) {
		$sAddMenu = '';
	}
	$sRes = $sRes . '<title>' . $sBaseTitulo . 'Universidad Nacional Abierta y a Distancia - UNAD</title>';
	$sRes = $sRes . '<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">';
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
	$sRes = $sRes . '</head>';
	$sRes = $sRes . '<body>';
	$sRes = $sRes . '<script language="JavaScript">';
	$sRes = $sRes . 'function muestraayuda(app, modulo){';
	$sRes = $sRes . 'window.document.frmayuda.com.value=modulo;';
	$sRes = $sRes . 'window.document.frmayuda.submit();';
	$sRes = $sRes . '}';
	$sRes = $sRes . '</script>';
	$sRes = $sRes . '<form id="frmayuda" name="frmayuda" action="unadayudas.php" method="post" target="_blank" style="display: none;">';
	$sRes = $sRes . '<input id="com" name="com" type="hidden" value="0" />';
	$sRes = $sRes . '</form>';
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
	$sRes = $sRes . $sAddMenu;
	//
	echo $sRes;
}
function forma_mitad($bConMenu = true)
{
	require './app.php';
	$sAddMenu = '</ul></div>';
	if (!$bConMenu) {
		$sAddMenu = '';
	}
	$sRes = $sAddMenu;
	$sRes = $sRes . '<header>';
	$sRes = $sRes . '<div class="barraHeader">';
	$sRes = $sRes . '</div>';
	$sRes = $sRes . '</header>';
	$sRes = $sRes . '<main>';
	$sRes = $sRes . '<div class="salto1px"></div>';
	$sRes = $sRes . '<div class="cuerpo">';
	// Estilos
	$sRes = $sRes . '<script language="javascript" src="' . $APP->rutacomun . 'js/jquery-3.3.1.min.js"></script>';
	$sRes = $sRes . '<script language="javascript" src="' . $APP->rutacomun . 'js/popper.min.js"></script>';
	$sRes = $sRes . '<script language="javascript" src="' . $APP->rutacomun . 'js/bootstrap.min.js"></script>';
	$sRes = $sRes . '<link rel="stylesheet" href="' . $APP->rutacomun . 'js/bootstrap.min.css" type="text/css" />';
	$sRes = $sRes . '<link rel="stylesheet" href="' . $APP->rutacomun . 'css/criticalPath.css" type="text/css" />';
	$sRes = $sRes . '<link rel="stylesheet" href="' . $APP->rutacomun . 'css/principal.css" type="text/css" />';
	$sRes = $sRes . '<link rel="stylesheet" href="' . $APP->rutacomun . 'unad_estilos2018.css?v=3" type="text/css" />';
	echo $sRes;
}
function forma_piedepagina($bConTiempo = true)
{
	require './app.php';
	$sMuestra = '';
	if (!$bConTiempo) {
		$sMuestra = 'display:none;';
	}
	$sRes = '</div>';
	$sRes = $sRes . '<div class="salto1px"></div>';
	$sRes = $sRes . '<div id="div_tiempo" style="width:150px;' . $sMuestra . '" class="ir_derecha"></div>';
	$sRes = $sRes . '<div class="salto1px"></div>';
	$sRes = $sRes . '</main>';
	$sRes = $sRes . '<footer id="footer">';
	$sRes = $sRes . '<p>Sede nacional Jos&eacute; Celestino Mutis: Calle 14 sur No. 14 - 23<br>';
	$sRes = $sRes . 'PBX:<a style="color:#FFF;" href="tel:+5713443700"> ( +57 1 ) 344 3700</a> Bogot&aacute; D.C., Colombia<br>';
	$sRes = $sRes . 'Línea nacional gratuita desde Colombia: <a style="color:#FFF;" href="tel:018000115223">018000115223</a><br>';
	$sRes = $sRes . 'Atenci&oacute;n al usuario: atencionalusuario@unad.edu.co<br>';
	$sRes = $sRes . 'Institución de Educaci&oacute;n Superior sujeta a inspecci&oacute;n y vigilancia por el Ministerio de Educación Nacional</p>';
	$sRes = $sRes . '</footer>';
	$sRes = $sRes . '<link rel="stylesheet" href="' . $APP->rutacomun . 'css/general.css">';
	$sRes = $sRes . '<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">';
	$sRes = $sRes . '<script type="text/javascript">';
	$sRes = $sRes . ' /* Script del Modal Confirm */ ';
	$sRes = $sRes . 'var ModalDialogConfirm = function(callback) {';
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
	$sRes = $sRes . '<script src="' . $APP->rutacomun . 'js/modal2018.js?v=2"></script>';
	$sRes = $sRes . '<script type="text/javascript">';
	$sRes = $sRes . 'if (window.history.replaceState) {';
	$sRes = $sRes . 'window.history.replaceState(null, null, window.location.href);';
	$sRes = $sRes . '}';
	$sRes = $sRes . '</script>';
	$sRes = $sRes . '</body>';
	$sRes = $sRes . '</html>';
	echo $sRes;
}
