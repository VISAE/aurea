<?php
function build_navigation2($PARAMS){
	$res='';
	return $res;
	}
function forma_cabecera($CFG, $SITE, $modulo_nombre, $modulo_sigla, $XAJAX=NULL){
/*
	if (empty($CFG->langmenu)) {
		$langmenu = '';
		}else{
		$currlang = current_language();
		$langs = get_list_of_languages();
		$langlabel = get_accesshide(get_string('language'));
		$langmenu = popup_form($CFG->wwwroot .'/index.php?lang=', $langs, 'chooselang', $currlang, '', '', '', true, 'self', $langlabel);
		}
	print_header($SITE->fullname.' - '.$modulo_nombre, $modulo_nombre, $modulo_sigla,'','',true, $langmenu);
*/
	//print_header_simple('', '',"", '', "", false);
//$PAGE->set_title($modulo_nombre);
//echo 'Titulo: '.$modulo_nombre;
//$PAGE->set_heading($modulo_nombre);
	/*
$PAGE->set_course($SITE);
*/
	//echo $OUTPUT->header();
echo '<!DOCTYPE html>
<html class="yui3-js-enabled" dir="ltr" xml:lang="es" lang="es"><div class="" id="yui3-css-stamp" style="position: absolute !important; visibility: hidden !important"></div><head>
<title>'.$SITE->fullname.' - '.$modulo_nombre.'</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="keywords" content="moodle, Campus Virtual UNAD">
<link rel="shortcut icon" href="http://66.165.175.215/eduadmin/theme/premium-1/img/favicon.png" />
<link rel="stylesheet" type="text/css" href="../ulib/recursos/yui_combo_002.css">
<link rel="stylesheet" type="text/css" href="../ulib/recursos/yui_combo_003.css">
<script type="text/javascript" src="../ulib/recursos/yui_combo_002.php"></script>
<script async="" src="../ulib/recursos/yui_combo.php" id="yui_3_17_2_2_1429018136392_14" charset="utf-8"></script>
<link href="../ulib/recursos/yui_combo.css" id="yui_3_17_2_2_1429018136392_35" rel="stylesheet" charset="utf-8">
<script async="" src="../ulib/recursos/yui_combo_003.php" id="yui_3_17_2_2_1429018136392_36" charset="utf-8"></script>
<script id="firstthemesheet" type="text/css"></script>
<link rel="stylesheet" type="text/css" href="../ulib/recursos/all.css">
<script type="text/javascript" src="../ulib/recursos/javascript-static.js"></script>
</head>
<body id="page-mod-url-view" class="Campus Virtual UNAD">
<div class="skiplinks"><a class="skip" href="#maincontent">Saltar a contenido principal</a></div>
<div id="page-wrapper">
<div id="page">
<div id="page-header">
<a class="logo" href="../" title="P&aacute;gina Principal"></a>
<div class="headermenu"><div class="logininfo">
<!--
Usted se ha identificado como <a href="../user/profile.php?id=30263" title="Ver perfil">ANGEL MAURO AVELLANEDA BARRETO</a> (<a href="../login/logout.php?sesskey=42PrFuQmwQ">Salir</a>)
-->
</div></div>
</div>
<!-- END OF HEADER -->
<!-- START CUSTOMMENU AND NAVBAR -->
<div id="navcontainer">
</div>
<div class="navbar clearfix">
<div class="breadcrumb"><span class="accesshide">Ruta a la p&aacute;gina</span><nav><ul>
<li><a href="../">P&aacute;gina Principal</a></li>
<li> <span class="accesshide "><span class="arrow_text">/</span>&nbsp;</span><span class="arrow sep">&#9658;</span> 
<span title="Campus Virtual UNAD">Inicio</span></li>
<li> <span class="accesshide "><span class="arrow_text">/</span>&nbsp;</span><span class="arrow sep">&#9658;</span> 
'.$modulo_nombre.'</li>
</ul></nav></div>
<div class="navbutton"> </div>
</div>
<!-- END OF CUSTOMMENU AND NAVBAR -->
<div id="page-content">';
//Termina de pintar el header.	
	if ($XAJAX!=NULL){$XAJAX->printJavascript();}
	echo '
<script language="JavaScript" type="text/javascript" charset="UTF-8">
<!--
function muestraayuda(app, modulo){
	window.document.frmayuda.app.value=app;
	window.document.frmayuda.com.value=modulo;
	window.document.frmayuda.nota.value=0;
	window.document.frmayuda.submit();
	}
function ayudanota(app, nota){
	window.document.frmayuda.app.value=app;
	window.document.frmayuda.com.value=0;
	window.document.frmayuda.nota.value=nota;
	window.document.frmayuda.submit();
	}
-->
</script>
<form id="frmayuda" name="frmayuda" action="http://datateca.unad.edu.co/ayuda/" method="post" target="_blank">
<input id="app" name="app" type="hidden" value="0" />
<input id="com" name="com" type="hidden" value="0" />
<input id="nota" name="nota" type="hidden" value="0" />
</form>
';
	}
function forma_mitad(){
	echo '
<div class="salto1px"></div>
<div class="cuerpo">
';
	}
function forma_piedepagina(){
	echo '
</div>
<div class="salto1px"></div>
<div id="div_tiempo" style="width:150px;" class="ir_derecha"></div>
<div class="salto1px"></div>';
	//print_footer('home');
echo '</div>
<!-- START OF FOOTER -->
<div id="page-footer" class="clearfix">
<div class="footer-left">
<a href="http://www.unad.edu.co/" title="UNAD">
<img src="../ulib/recursos/moodle-logo.png" alt="UNAD">
</a>
</div>
<div class="footer-right">
<!--
<div class="logininfo">Usted se ha identificado como <a href="../user/profile.php?id=30263" title="Ver perfil">ANGEL MAURO AVELLANEDA BARRETO</a> (<a href="../login/logout.php?sesskey=42PrFuQmwQ">Salir</a>)</div>        
-->
</div>
</div>
<div class="clearfix"></div>
</div>
</div>
</body>
</html>';
	}
?>