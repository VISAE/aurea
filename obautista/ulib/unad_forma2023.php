<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2023 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- © Edwin Leandro Moreno Guerra - UNAD - 2023 ---
--- edwin.moreno@unad.edu.co - http://www.unad.edu.co
*/
function forma_InicioV4($XAJAX=NULL, $sNomModulo = '', $bIncluyePWA=false){
	require './app.php';
	$sRes = '<!DOCTYPE html><html><head>';
	if ($XAJAX != NULL){
		echo $sRes;
		$sRes = '';
		$XAJAX->printJavascript();
	}
	if (isset($APP->ihost)!=0){
		if ($APP->ihost!=0){
			$sHost=gethostname();
			$sNomModulo='['.$sHost.'] '.$sNomModulo;
			}
		}
		if ($sNomModulo != '') {
			$sNomModulo = $sNomModulo . ' - ';
		}
		$sRes = $sRes . '<title>'.$sNomModulo.'Universidad Nacional Abierta y a Distancia - UNAD</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="'.$APP->rutacomun.'css/favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon" />
<link rel="stylesheet" href="'.$APP->rutacomun.'css/formav2.css?v=2" type="text/css" />';
	if ($bIncluyePWA){
		$sRes = $sRes . '<meta name="MobileOptimized" content="width">
<meta name="HandheldFriendly" content="true">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<meta name="theme-color" content="#024D65">
<link rel="apple-touch-icon" href="./pwa/icons/icon_192.png">
<link rel="apple-touch-startup-image" href="./pwa/icons/icon_1024.png">
<link rel="manifest" href="./manifest.json">
<script type="text/javascript" src="pwa/js/browser_detect.js"></script>
<link rel="stylesheet" href="./pwa/css/site.css">';
	}
	echo $sRes;
}
function forma_cabeceraV4($aRutas = array(), $aBotones = array(), $bConMenu=true, $bDebug = false, $bCompatibilidad = false){
	require './app.php';
	$sRes = '';
	$sBaseTitulo = ' ';
	$iRutas = 0;
	$iBotones = 0;
	if (is_array($aRutas)){
		for ($k=0; $k <= 2; $k++) {
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
		$sBaseTitulo=$sBaseTitulo.' - ';
	}
	if (is_array($aBotones)) {
		$iCajas = count($aBotones);
		$iPasos = $iCajas - 1;
		for ($k=0; $k <= $iPasos; $k++) {
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
	$sRes = $sRes . '<link rel="stylesheet" href="'.$APP->rutacomun.'css/general.css?v=1">';
	if (!$bCompatibilidad) {
		//$sRes = $sRes . '<link rel="stylesheet" href="'.$APP->rutacomun.'css/style-2023.css?v=1">';
	}
	if ($bCompatibilidad) {
		$sRes = $sRes . '<link rel="stylesheet" href="'.$APP->rutacomun.'css/aurea2023-noaccess.css?v=2c">';
	} else {
		$sRes = $sRes . '<link rel="stylesheet" href="'.$APP->rutacomun.'css/aurea2023.css?v=1">';
	}
	$sLogoSistema = '<img src="'.$APP->rutacomun.'img/logoAUREA.svg" alt=""><p>AUREA</p>';
	$idSistema = 0;
	if (isset($APP->idsistema) != 0) {
		$idSistema = $APP->idsistema;
	}
	switch ($idSistema){
		case 5: //PRESUPUESTO
			$sLogoSistema = '<img src="'.$APP->rutacomun.'img/2023/presupuesto.svg" alt=""><p>PRESUPUESTO</p>';
			break;
		case 11: //CONTABILIDAD
			$sLogoSistema = '<img src="'.$APP->rutacomun.'img/2023/contabilidad.svg" alt=""><p>CONTABILIDAD</p>';
			break;
		case 17: //OAI
			$sLogoSistema = '<img src="'.$APP->rutacomun.'img/2023/oai.svg" alt=""><p>OAI</p>';
			break;
		case 21: //OIL
			$sLogoSistema = '<img src="'.$APP->rutacomun.'img/2023/oil.svg" alt=""><p>OIL</p>';
			break;
		case 22: //CORE
			$sLogoSistema = '<img src="'.$APP->rutacomun.'img/2023/core.svg" alt=""><p>CORE</p>';
			break;
		case 24: //C2
			$sLogoSistema = '<img src="'.$APP->rutacomun.'img/2023/c2.svg" alt=""><p>C2</p>';
			break;
		case 36: //SIGMA
			$sLogoSistema = '<img src="'.$APP->rutacomun.'img/2023/sigma.svg" alt=""><p>SIGMA</p>';
			break;
		case 40: //INVENTARIOS
			$sLogoSistema = '<img src="'.$APP->rutacomun.'img/2023/inventarios.svg" alt=""><p>INVENTARIOS</p>';
			break;
		case 41: //CONTRATACION
			$sLogoSistema = '<img src="'.$APP->rutacomun.'img/2023/contratacion.svg" alt=""><p>CONTRATACION</p>';
			break;
		case 39: //COMPRAS
			$sLogoSistema = '<img src="'.$APP->rutacomun.'img/2023/compras.svg" alt=""><p>COMPRAS</p>';
			break;
		case 45: //SUEV
			$sLogoSistema = '<img src="'.$APP->rutacomun.'img/2023/suev.svg" alt=""><p>SUEV</p>';
			break;
		case 49: //SINEP
			$sLogoSistema = '<img src="'.$APP->rutacomun.'img/2023/sinep.svg" alt=""><p>SINEP</p>';
			break;
	}
	$sRes = $sRes . '</head>
<body>
<header>
<section id="banner" class="banner">
<div class="banner__left"></div>
<div class="banner__center"></div>
<div class="banner__right"></div>
</section>
<section id="breadcrumb">
<div class="breadcrumb--mobile">
<a href="#" class="system__icon">' . $sLogoSistema . '</a>
<button class="back-route--mobile">
<i class="iNavigateBefore"></i>    
</button>
</div>
<div class="breadcrumb--desktop">
<div class="breadcrumb__route">
<button class="route--mobile">
<i class="iMenu"></i>        
</button>
<div class="route--desktop">';
	if ($iRutas > 0) {
		$sRes = $sRes . '';
		for ($k=0; $k < $iRutas; $k++) {
			if ($k > 0) {
				$sRes = $sRes . '<span>/</span>';
			}
			if ($aRutas[$k][0] == '') {
				$sRes = $sRes . '<span>' . $aRutas[$k][1] . '</span>';
			} else {
				$sRes = $sRes . '<a href="' . $aRutas[$k][0] . '">' . $aRutas[$k][1] . '</a>';
			}
		}
	}
	$sRes = $sRes . '</div></div>';
	$sRes = $sRes . '<div id="botones_sup"><div class="breadcrumb__buttons">';
	for ($k=0; $k < $iBotones; $k++) {
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
		$sRes = $sRes . '<button' . $sLink . $sTitulo . '><i class="' . $sClase . '"></i></button>';
	}
	$sRes = $sRes . '</div></div>
</section>
</header>';
	$sRes = $sRes . '<script language="JavaScript">
function muestraayuda(app, modulo){
window.document.frmayuda.com.value=modulo;
window.document.frmayuda.submit();
}
</script>
<form id="frmayuda" name="frmayuda" action="unadayudas.php" method="post" target="_blank">
<input id="com" name="com" type="hidden" value="0" />
</form>';
	//<!-- Modal Advertencia Bootstrap -->
	$sRes = $sRes . '<div class="modal fade" id="ModalAdvertencia" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" style="display:none;">
<div class="modal-dialog modal-dialog-centered" role="document">
<div class="modal-content">
<div class="modal-header bg-primary text-light">
<h5 class="modal-title" id="exampleModalLongTitle">Advertencia!</h5>
<button type="button" class="close" data-dismiss="modal" aria-label="Close">
<span aria-hidden="true">&times;</span>
</button>
</div>
<div class="modal-body" id="ModalCuerpoAdv">
...
</div>
<div class="modal-footer">
<button type="button" class="btn btn-primary" style="font-size: 16px;" data-dismiss="modal">Aceptar</button>
</div>
</div>
</div>
</div>
<!-- Modal Confirm Bootstrap -->
<div class="modal fade" id="ModalConfirm" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" style="display:none;">
<div class="modal-dialog modal-dialog-centered" role="document">
<div class="modal-content">
<div class="modal-header bg-primary text-light">
<h5 class="modal-title" id="exampleModalLongTitle">Advertencia!</h5>
<button type="button" class="close" data-dismiss="modal" aria-label="Close">
<span aria-hidden="true">&times;</span>
</button>
</div>
<div class="modal-body" id="ModalCuerpoCon">
...
</div>
<div class="modal-footer">
<button id="modal-btn-si" type="button" class="btn btn-success" style="font-size: 16px;" data-dismiss="modal">Aceptar</button>
<button id="modal-btn-no" type="button" class="btn btn-danger" style="font-size: 16px;" data-dismiss="modal">Cancelar</button>
</div>
</div>
</div>
</div>';
	if ($bConMenu){
		//$sRes = $sRes . '<div class="menuSuperior"><ul>';
	}
	$sRes = $sRes . '<nav class="nav" id="nav">
<div id="navbar">
<section id="menu-options">
<div class="system__icon">
<div>' . $sLogoSistema . '</div>
<i id="anchor-menu" class="iPin" title="Anclar menu"></i>
</div>
<ul class="navbar__options">';
	echo $sRes;
	}
function forma_mitad($idTercero = 0){
	$sRes = '</ul>
</section>
<section id="menu-help">
<ul class="navbar__options">
<li class="options__item">
<a class="option">
<i class="iLightModeFill"></i>
<p>Accesibilidad</p>
<i class="iNavigateNext"></i></a>
<div class="option-drop accesibilidad">
<ul>
<li>
<a id="boton-reinicio">
<i class="iRestart"></i>
Reiniciar
</a>
</li>
<li>
<a id="boton-menos">
<i class="iZoomOut"></i>
Reducir
</a>
</li>
<li>
<a id="boton-mas">
<i class="iZoomIn"></i>
Aumentar
</a>
</li>
<li>
<a id="boton-contraste">
<i class="iContrastMode"></i>
Contrast
</a>
</li>
<li>
<a id="boton-oscuro">
<i class="iDarkModeFill"></i>
Oscuro
</a>
</li>
<li>
<a id="boton-claro">
<i class="iLightModeFill"></i>
Claro
</a>
</li>
</ul>
</div>
</li>
<li class="options__item">
<a class="option">
<i class="iHelpFill"></i>
<p>Ayuda</p>
<i class="iNavigateNext"></i>
</a>
<div class="option-drop ayuda">
<ul>
<li><a href="./unadayudas.php">Manuales</a></li>
<li><a href="./acercade.php">Acerca de...</a></li>
</ul>
</div>
</li>';
	if ((int)$idTercero != 0) {
		$sRes = $sRes . '<li class="options__item">
<a class="option" href="./salir.php">
<i class="iLogout"></i>
<p>Salir</p>
</a>
</li>';
	}
	$sRes = $sRes . '</ul>
</section>
</div>
</nav>
<main>';
	echo $sRes;
	}
function forma_piedepagina($bConTiempo=true, $sAdd = ''){
	require './app.php';
	$sMuestra='';
	if (!$bConTiempo){
		$sMuestra='display:none;';
	}
	$sRes = '<div class="salto1px"></div>
<div id="div_tiempo" style="width:150px;'.$sMuestra.'" class="ir_derecha"></div>
<div class="salto1px"></div>
</main>
<footer>
<span></span>
<div>
<p>
Sede nacional Jos&eacute; Celestino Mutis: <a href="https://goo.gl/maps/9vSBgNcPa6SKYo7HA" target="_blank">Calle 14 sur No. 14 - 23</a>
</p>
<p>
PBX: <a href="tel:+576013443700">(+57 601) 344 3700</a> Bogotá D.C., Colombia</p>
<p>
Línea nacional gratuita desde Colombia: <a href="tel:018000115223">01 8000 115 223</a>
</p>
<p>
Atenci&oacute;n al usuario: <a href="mailto:atencionalusuario@unad.edu.co">atencionalusuario@unad.edu.co</a>
</p>
<p>
Instituci&oacute;n de Educaci&oacute;n Superior sujeta a inspecci&oacute;n y vigilancia por el Ministerio de Educaci&oacute;n Nacional
</p>
</div>
</footer>';
$sRes = $sRes . '<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
<script type="text/javascript">
// Script del Modal Confirm
var ModalDialogConfirm = function(callback){
	// Respuestas
	jQuery.noConflict();
	jQuery("#modal-btn-si").on("click", function(){
		callback(true);
		jQuery("#ModalConfirm").modal("hide");
		});
	jQuery("#modal-btn-no").on("click", function(){
		callback(false);
		jQuery("#ModalConfirm").modal("hide");
		}); 
	};
</script>
<script src="' . $APP->rutacomun . 'js/aurea2023.js"></script>' . $sAdd . '
</body>
</html>';
	echo $sRes;
}
