<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2018 - 2020 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
*/
function forma_cabeceraV2($CFG, $SITE, $XAJAX=NULL, $modnombre='', $sLinks=''){
	forma_cabeceraV3($XAJAX, $modnombre);
	}
function forma_cabeceraV3($XAJAX=NULL, $modnombre='', $bConMenu=true, $bIncluyePWA=false){
	require './app.php';
	echo '<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">';
	if ($XAJAX!=NULL){$XAJAX->printJavascript();}
	$sBaseTitulo=$modnombre;
	if ($modnombre!=''){$sBaseTitulo=$modnombre.' - ';}
	//Julio 7 de 2020 se agrega el host si el parametro esta marcado.
	if (isset($APP->ihost)!=0){
		if ($APP->ihost!=0){
			$sHost=gethostname();
			$sBaseTitulo='['.$sHost.'] '.$sBaseTitulo;
			}
		}
	$sAddMenu='<div class="menuSuperior"><ul>';
	if (!$bConMenu){$sAddMenu='';}
	echo '<title>'.$sBaseTitulo.'Universidad Nacional Abierta y a Distancia - UNAD</title>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<link href="'.$APP->rutacomun.'css/favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon" />
<link rel="stylesheet" href="'.$APP->rutacomun.'css/formav2.css?v=2" type="text/css" />';
	if ($bIncluyePWA){
		echo '<meta name="MobileOptimized" content="width">
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
	echo '</head>
<body>
<script language="JavaScript">
function muestraayuda(app, modulo){
	window.document.frmayuda.com.value=modulo;
	window.document.frmayuda.submit();
	}
</script>
<form id="frmayuda" name="frmayuda" action="unadayudas.php" method="post" target="_blank">
<input id="com" name="com" type="hidden" value="0" />
</form>
<!-- Modal Advertencia Bootstrap -->
<div class="modal fade" id="ModalAdvertencia" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" style="display:none;">
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
<button id="modal-btn-si" type="button" class="btn btn-success" style="font-size: 16px;">Aceptar</button>
<button id="modal-btn-no" type="button" class="btn btn-danger" style="font-size: 16px;" data-dismiss="modal">Cancelar</button>
</div>
</div>
</div>
</div>
'.$sAddMenu.'';
	}
function forma_mitad($bConMenu=true){
	$sAddMenu='</ul></div>';
	if (!$bConMenu){$sAddMenu='';}
	echo $sAddMenu.'
<header>
<div class="barraHeader">
</div>
</header>
<main>
<div class="salto1px"></div>
<div class="cuerpo">
';
	}
function forma_piedepagina($bConTiempo=true){
	require './app.php';
	$sMuestra='';
	if (!$bConTiempo){$sMuestra='display:none;';}
	echo '
</div>
<div class="salto1px"></div>
<div id="div_tiempo" style="width:150px;'.$sMuestra.'" class="ir_derecha"></div>
<div class="salto1px"></div>
</main>
<footer id="footer">
<p>Sede nacional Jos&eacute; Celestino Mutis: Calle 14 sur No. 14 - 23<br>
PBX:<a style="color:#FFF;" href="tel:+5713443700"> ( +57 1 ) 344 3700</a> Bogot&aacute; D.C., Colombia<br>
Línea nacional gratuita desde Colombia: <a style="color:#FFF;" href="tel:018000115223">018000115223</a><br>
Atenci&oacute;n al usuario: atencionalusuario@unad.edu.co<br>
Institución de Educaci&oacute;n Superior sujeta a inspecci&oacute;n y vigilancia por el Ministerio de Educación Nacional
</p>
</footer>
<link rel="stylesheet" href="'.$APP->rutacomun.'css/general.css">
<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
<script type="text/javascript">
// Script del Modal Confirm
var ModalDialogConfirm = function(callback){
	// Respuestas
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
</body>
</html>';
	}
?>