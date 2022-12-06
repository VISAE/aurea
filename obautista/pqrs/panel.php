<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2018 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.22.6b lunes, 26 de noviembre de 2018
*/
if (file_exists('./err_control.php')){require './err_control.php';}
$bDebug=false;
$sDebug='';
if (isset($_REQUEST['deb_doc'])!=0){
	$_REQUEST['debug']=1;
	}
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
//forzar a https
$bAplicaGeo=true;
$iHTTPS=0;
if (file_exists('./opts.php')){
	require './opts.php';
	if ($OPT->geo==0){$bAplicaGeo=false;}
	$iHTTPS=$OPT->https;
	}
if ($iHTTPS==0){$bAplicaGeo=false;}
require './app.php';
require $APP->rutacomun.'unad_sesion.php';
$bPeticionXAJAX=false;
if ($_SERVER['REQUEST_METHOD']=='POST'){if (isset($_POST['xjxfun'])){$bPeticionXAJAX=true;}}
if (!$bPeticionXAJAX){$_SESSION['u_ultimominuto']=(date('W')*1440)+(date('H')*60)+date('i');}
require $APP->rutacomun.'unad_todas.php';
require $APP->rutacomun.'libs/clsdbadmin.php';
require $APP->rutacomun.'unad_librerias.php';
require $APP->rutacomun.'libhtml.php';
require $APP->rutacomun.'xajax/xajax_core/xajax.inc.php';
require $APP->rutacomun.'unad_xajax.php';
require 'libtablero.php';
require 'lib17.php';
require 'lib1926.php';
if (($bPeticionXAJAX)&&($_SESSION['unad_id_tercero']==0)){
	// viene por xajax.
	$xajax=new xajax();
	$xajax->configure('javascript URI', $APP->rutacomun.'xajax/');
	$xajax->register(XAJAX_FUNCTION,'sesion_abandona_V2');
	$xajax->processRequest();
	die();
	}
$bEnSesion=false;
if ((int)$_SESSION['unad_id_tercero']!=0){$bEnSesion=true;}
if (!$bEnSesion){
	header('Location:index.php');
	die();
	}
$iCodModulo=17;
$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
$sError='';
$iTipoError=0;
$idTercero=$_SESSION['unad_id_tercero'];
if (isset($_REQUEST['paso'])==0){$_REQUEST['paso']=0;}
if (isset($_SERVER['HTTPS'])==0){$_SERVER['HTTPS']='off';}
if (isset($_SESSION['unad_geo_lat'])==0){$_SESSION['unad_geo_lat']='';}
$sProtocolo='https';
$iFechaTerminos=0;
if ($_SESSION['unad_geo_lat']!=''){$bAplicaGeo=false;}
if ($bAplicaGeo){
	//Ver que haya aceptado los terminos y condiciones y que por tanto tengamos en la sesion la geolocalizacion
	$sql='SELECT unad11fechaterminos, unad11noubicar FROM unad11terceros WHERE unad11id='.$idTercero.'';
	$tabla=$objDB->ejecutasql($sql);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$iFechaTerminos=$fila['unad11fechaterminos'];
		if ($fila['unad11noubicar']!=0){
			$bAplicaGeo=false;
			$_SESSION['unad_geo_lat']='ND';
			if ($bDebug){$sDebug=$sDebug.''.fecha_microtiempo().' LA GEOLOCALIZACION ESTA EXCLUIDA PARA ESTE USUARIO ['.$idTercero.']<br>';}
			}
		}
	}else{
	$_SESSION['unad_geo_lat']='ND';
	}
if ($bAplicaGeo){
	if ($iFechaTerminos<20161225){
		//pasar a https...
		$bCambia=false;
		if ($sProtocolo=='https'){
			if ($_SERVER['HTTPS']!='on'){$bCambia=true;}
			}
		if ($bCambia){
			$pageURL= $sProtocolo.'://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
			header('Location:'.$pageURL);
			die();
			}
		//Fin de pasar a https.
		$xajax = new xajax();
		$xajax->configure('javascript URI', $APP->rutacomun.'xajax/');
		$xajax->register(XAJAX_FUNCTION,'f17_SesionCoordenadas');
		$xajax->processRequest();
		require $APP->rutacomun.'unad_forma_v2.php';
		forma_cabeceraV3($xajax, 'ACCESIT');
		echo '<link rel="stylesheet" href="'.$APP->rutacomun.'unad_estilos.css" type="text/css"/>';
		forma_mitad();
		require 'terminos.php';
		forma_piedepagina();
		die();
		}
	}
//El paso 61 es para ucando definitivamente no se puede establecer ubicación geográfica.
if ($_REQUEST['paso']==61){
	$_REQUEST['paso']=0;
	$_SESSION['unad_geo_lat']='ND';
	}
//Establecer la ubicación geografica...
if ($_SESSION['unad_geo_lat']==''){
	//pasar a https...
	$bCambia=false;
	if ($sProtocolo=='https'){
		if ($_SERVER['HTTPS']!='on'){$bCambia=true;}
		}
	if ($bCambia){
		$pageURL= $sProtocolo.'://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
		header('Location:'.$pageURL);
		die();
		}
	//Fin de pasar a https.
	$xajax = new xajax();
	$xajax->configure('javascript URI', $APP->rutacomun.'xajax/');
	$xajax->register(XAJAX_FUNCTION,'f17_SesionCoordenadas');
	$xajax->processRequest();
	require $APP->rutacomun.'unad_forma_v2.php';
	forma_cabeceraV3($xajax, 'ACCESIT');
	echo '<link rel="stylesheet" href="'.$APP->rutacomun.'unad_estilos.css" type="text/css"/>';
	forma_mitad();
	require 'ubicacion.php';
	forma_piedepagina();
	die();
	}
$bOtroUsuario=false;
if (isset($_REQUEST['deb_doc'])!=0){
	if (seg_revisa_permiso($iCodModulo, 1707, $objDB)){
		$sSQL='SELECT unad11id, unad11razonsocial FROM unad11terceros WHERE unad11doc="'.$_REQUEST['deb_doc'].'"';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$fila=$objDB->sf($tabla);
			$idTercero=$fila['unad11id'];
			$bOtroUsuario=true;
			if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Se verifica la ventana de trabajo para el usuario '.$fila['unad11razonsocial'].'.<br>';}
			}else{
			$sError='No se ha encontrado el documento &quot;'.$_REQUEST['deb_doc'].'&quot;';
			$_REQUEST['deb_doc']='';
			}
		}else{
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' No cuenta con permiso de ingreso como otro usuario Modulo '.$iCodModulo.' Permiso.<br>';}
		$_REQUEST['deb_doc']='';
		}
	}else{
	$_REQUEST['deb_doc']='';
	}

$xajax=new xajax();
$xajax->configure('javascript URI', $APP->rutacomun.'xajax/');
$xajax->register(XAJAX_FUNCTION,'sesion_abandona_V2');
$xajax->register(XAJAX_FUNCTION,'f17_SesionCoordenadas');
$xajax->processRequest();
if ($bPeticionXAJAX){
	die(); // Esto hace que las llamadas por xajax terminen aquí.
	}
$modnombre='ACCESIT';
$et_menu='<li><a href="salir.php">Salir</a></li>';
list($sBanner, $sDebugB)=tablero_HTMLBannerV2($objDB, $bDebug);
$sDebug=$sDebug.$sDebugB;
$objDB->CerrarConexion();
require $APP->rutacomun.'unad_forma_v2.php';
forma_cabeceraV3($xajax, $modnombre);
echo $et_menu;
forma_mitad();
?>
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>css/criticalPath.css">
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>css/principal.css">
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>unad_estilos.css" type="text/css"/>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>unad_todas.js?ver=4"></script>
<script language="javascript">
<!--
function mantener_sesion(){xajax_sesion_mantener();}
setInterval("xajax_sesion_abandona_V2();", 60000);
<?php
$bPedirCoordenadas=false;
if ($_SESSION['unad_geo_lat']==''){
	if ($_SESSION['unad_geo_intentos']<2){
?>
function showLocation(position){
	var valores=new Array();
	valores[1]=<?php echo $_SESSION['unad_id_sesion']; ?>;
	valores[2]=<?php echo $idTercero; ?>;
	valores[3]=position.coords.latitude;
	valores[4]=position.coords.longitude;
	valores[5]=position.coords.accuracy;
	xajax_f17_SesionCoordenadas(valores);
	}
function errorHandler(err){}
function getLocation(){
	if(navigator.geolocation){
		// timeout at 60000 milliseconds (60 seconds)
		var options = {timeout:60000};
		navigator.geolocation.getCurrentPosition(showLocation, errorHandler, options);
		}else{
		alert('Su navegador no soporta geolocalizaci\u00f3n le recomendamos actualizarlo.');
		}
	}
<?php
		$bPedirCoordenadas=true;
		$_SESSION['unad_geo_intentos']++;
		}
	}
?>
function ir_inicio(iDestino){
	var sAccio='shernandez/';
	if (iDestino==2){sAccio='obautista/';}
	if (iDestino==3){sAccio='prog3/';}
	if (iDestino==4){sAccio='apa/';}
	window.document.frmaurea.action=sAccio;
	window.document.frmaurea.submit();
	}
// -->
</script>

<?php
echo $sBanner;
?>
<form id="frmaurea" name="frmaurea" method="post" action="../aurea/" target="_blank">
</form>
<form id="frmedita" name="frmedita" method="post" action="">
<input id="paso" name="paso" type="hidden" value="<?php echo $_REQUEST['paso']; ?>" />
</form>
<div class="botonesPaginaInicio">
<label class="Label130">
<input id="cmdPanel" name="cmdPanel" type="button" class="BotonAzul" value="Saul Hernandez" onclick="ir_inicio(1)" title="Entorno de pruebas Saul" />
</label>
<label class="Label130"></label>
<label class="Label130">
<input id="cmdPanel2" name="cmdPanel2" type="button" class="BotonAzul" value="Omar Bautista" onclick="ir_inicio(2)" title="Entorno de pruebas Omar" />
</label>
<label class="Label130"></label>
<label class="Label130">
<input id="cmdPanel3" name="cmdPanel3" type="button" class="BotonAzul" value="William Rico" onclick="ir_inicio(3)" title="Entorno de pruebas William Rico" />
</label>
<label class="Label130"></label>
<label class="Label130">
<input id="cmdPanel4" name="cmdPanel4" type="button" class="BotonAzul" value="A P A" onclick="ir_inicio(4)" title="Entorno de proyecto A.P.A" />
</label>
</div>

<script src="js/jssor.slider-27.1.0.min.js"></script>
<script src="js/principal.js"></script>
<script>jssor_1_slider_init();</script>

<?php
if ($sDebug!=''){
	$iSegFin=microtime(true);
	$iSegundos=$iSegFin-$iSegIni;
	$sDebug=$sDebug.fecha_microtiempo().' Tiempo total del proceso: <b>'.$iSegundos.'</b> Segundos {Verificado 22 - Mayo - 2018 Rev 3}';	
	echo '<div class="salto1px"></div>
<div class="GrupoCampos">'.$sDebug.'
<div class="salto1px"></div>
</div>';
	}
echo html_DivAlarmaV2($sError, $iTipoError);
forma_piedepagina(false);
?>
