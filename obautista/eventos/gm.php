<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2017 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.18.1 miércoles, 17 de mayo de 2017
*/
if (file_exists('./err_control.php')){require './err_control.php';}
$bDebug=false;
$sDebug='';
if (isset($_REQUEST['debug'])!=0){
	if ($_REQUEST['debug']==1){$bDebug=true;}
	}else{
	$_REQUEST['debug']=0;
	}
if ($bDebug){
	$iSegIni=microtime(true);
	$iSegundos=floor($iSegIni);
	$sMili=floor(($iSegIni-$iSegundos)*1000);
	if ($sMili<100){if ($sMili<10){$sMili=':00'.$sMili;}else{$sMili=':0'.$sMili;}}else{$sMili=':'.$sMili;}
	$sDebug=$sDebug.''.date('H:i:s').$sMili.' Inicia pagina <br>';
	}
if (!file_exists('./app.php')){
	echo '<b>Error N 1 de instalaci&oacute;n</b><br>No se ha establecido un archivo de configuraci&oacute;n, por favor comuniquese con el administrador del sistema.';
	die();
	}
require_once '../config.php';
mb_internal_encoding('UTF-8');
require './app.php';
//if (!file_exists('./opts.php')){require './opts.php';if ($OPT->opcion==1){$bOpcion=true;}}
$bPeticionXAJAX=false;
if ($_SERVER['REQUEST_METHOD']=='POST'){if (isset($_POST['xjxfun'])){$bPeticionXAJAX=true;}}
if (!$bPeticionXAJAX){$_SESSION['u_ultimominuto']=(date('W')*1440)+(date('H')*60)+date('i');}
require $APP->rutacomun.'unad_todas.php';
require $APP->rutacomun.'libs/clsdbadmin.php';
require $APP->rutacomun.'unad_librerias.php';
require $APP->rutacomun.'libhtml.php';
require $APP->rutacomun.'xajax/xajax_core/xajax.inc.php';
require $APP->rutacomun.'unad_xajax.php';
if (($bPeticionXAJAX)&&($_SESSION['unad_id_tercero']==0)){
	// viene por xajax.
	$xajax=new xajax();
	$xajax->configure('javascript URI', $APP->rutacomun.'xajax/');
	$xajax->register(XAJAX_FUNCTION,'sesion_abandona_V2');
	$xajax->processRequest();
	die();
	}
require_login();
$grupo_id=1;//Necesita ajustarlo...
$icodmodulo=0;
// -- Se cargan los archivos de idioma
$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
require $mensajes_todas;
$xajax=NULL;
$objdb=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
if ($APP->dbpuerto!=''){$objdb->dbPuerto=$APP->dbpuerto;}
if (isset($APP->piel)==0){$APP->piel=1;}
$iPiel=$APP->piel;
$xajax=new xajax();
$xajax->configure('javascript URI', $APP->rutacomun.'xajax/');
$xajax->register(XAJAX_FUNCTION,'sesion_abandona_V2');
$xajax->register(XAJAX_FUNCTION,'sesion_mantener');
$xajax->register(XAJAX_FUNCTION,'f0_HtmlTabla');
$xajax->register(XAJAX_FUNCTION,'f0_ExisteDato');
$xajax->processRequest();
if ($bPeticionXAJAX){
	die(); // Esto hace que las llamadas por xajax terminen aquí.
	}
$bcargo=false;
$sError='';
$sErrorCerrando='';
$iTipoError=0;
$bLimpiaHijos=false;
$bMueveScroll=false;
$iSector=1;
//include '../ulib/unad_compatibilidad.php';
$modnombre='Gesti&oacute;n de eventos';
$modsigla='Bitacora';
$et_menu=html_menu($APP->idsistema, $objdb, $iPiel);
$id=0;
if (isset($_REQUEST['id'])!=0){
	$id=$_REQUEST['id'];
	}
if ($id==-1){
	$html_menugrupo=html_menu_grupo(-1, 99, $objdb, false);
	}else{
	$html_menugrupo=html_menu_grupo($id, $APP->idsistema, $objdb, false);
	}
$objdb->CerrarConexion();
//FORMA
if ($_SESSION['cfg_movil']==1){
	require $APP->rutacomun.'unad_formamovil.php';
	}else{
	require $APP->rutacomun.'unad_forma.php';
	}
$navigation=build_navigation(array(array('name'=>'Bitacora', 'link'=>'index.php', 'type'=>'misc'), array('name'=>'Menu', 'link'=>'', 'type'=>'misc')));
forma_cabecera($CFG, $SITE, $modnombre, $navigation);
//forma_cabeceraV2($CFG, $SITE, $xajax, $ETI['titulo_0'], $ETI['app_nombre'].'|index.php@'.$ETI['grupo_nombre'].'|gm.php?id='.$grupo_id.'@'.$ETI['titulo_0'].'|');
echo $et_menu;
forma_mitad();
if (false){
?>
<link rel="stylesheet" href="../ulib/unad_estilos.css" type="text/css"/>
<?php
	}
?>
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>unad_estilos.css" type="text/css"/>
<?php
?>
<script language="javascript" type="text/javascript" charset="UTF-8" src="<?php echo $APP->rutacomun; ?>unad_todas.js?ver=8"></script>
<div id="interna">
<form id="frmedita" name="frmedita" method="post" action="">
<input id="paso" name="paso" type="hidden" value="<?php echo $_REQUEST['paso']; ?>" />
<div id="div_sector1">
<div class="titulos">
<div class="titulosD">
</div>
<div class="titulosI">
</div>
</div>
<div class="areaform">
<div class="areatrabajo">
<?php
echo $html_menugrupo;
?>
</div><!-- CIERRA EL DIV areatrabajo -->
</div><!-- CIERRA EL DIV areaform -->
</div><!-- /DIV_Sector1 -->
</form>
</div><!-- /DIV_interna -->
<?php
forma_piedepagina();
?>