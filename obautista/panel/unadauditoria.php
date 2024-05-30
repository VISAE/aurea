<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2015 - 2020 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 1.2.16 lunes, 02 de febrero de 2015
--- Modelo Versión 2.24.0 Tuesday, November 26, 2019
--- Modelo Versión 2.24.1 lunes, 10 de febrero de 2020
*/
/** Archivo unadauditoria.php.
* Modulo 152 unad52auditoria.
* @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
* @param debug=1 (Opcional), bandera para indicar si se generan datos de depuración
* @date Tuesday, November 26, 2019
*/
if (file_exists('./err_control.php')) {
	require './err_control.php';
}
$bDebug = false;
$sDebug = '';
if (isset($_REQUEST['deb_doc']) != 0) {
	if (trim($_REQUEST['deb_doc']) != '') {
		$bDebug = true;
	}
} else {
	$_REQUEST['deb_doc'] = '';
}
if (isset($_REQUEST['debug']) != 0) {
	if ($_REQUEST['debug'] == 1) {
		$bDebug = true;
	}
}
if ($bDebug) {
	$iSegIni = microtime(true);
	$iSegundos = floor($iSegIni);
	$sMili = floor(($iSegIni - $iSegundos) * 1000);
	if ($sMili < 100) {
		if ($sMili < 10) {
			$sMili = ':00' . $sMili;
		} else {
			$sMili = ':0' . $sMili;
		}
	} else {
		$sMili = ':' . $sMili;
	}
	$sDebug = $sDebug . date('H:i:s') . $sMili . ' Inicia pagina <br>';
}
if (!file_exists('./app.php')) {
	echo '<b>Error N 1 de instalaci&oacute;n</b><br>No se ha establecido un archivo de configuraci&oacute;n, por favor comuniquese con el administrador del sistema.';
	die();
}
mb_internal_encoding('UTF-8');
require './app.php';
require $APP->rutacomun . 'unad_sesion.php';
if (isset($APP->https) == 0) {
	$APP->https = 0;
}
if ($APP->https == 2) {
	$bObliga = false;
	if (isset($_SERVER['HTTPS']) == 0) {
		$bObliga = true;
	} else {
		if ($_SERVER['HTTPS'] != 'on') {
			$bObliga = true;
		}
	}
	if ($bObliga) {
		$pageURL = 'https://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
		header('Location:' . $pageURL);
		die();
	}
}
/*
if (!file_exists('./opts.php')) {
	require './opts.php';
	if ($OPT->opcion == 1) {
		$bOpcion = true;
	}
}
*/
$bPeticionXAJAX = false;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	if (isset($_POST['xjxfun'])) {
		$bPeticionXAJAX = true;
	}
}
if (!$bPeticionXAJAX) {
	$_SESSION['u_ultimominuto'] = (date('W') * 1440) + (date('H') * 60) + date('i');
}
require $APP->rutacomun . 'unad_todas.php';
require $APP->rutacomun . 'libs/clsdbadmin.php';
require $APP->rutacomun . 'unad_librerias.php';
require $APP->rutacomun . 'libhtml.php';
require $APP->rutacomun . 'xajax/xajax_core/xajax.inc.php';
require $APP->rutacomun . 'unad_xajax.php';
require $APP->rutacomun . 'lib34.php';
if (($bPeticionXAJAX) && ($_SESSION['unad_id_tercero'] == 0)) {
	// viene por xajax.
	$xajax = new xajax();
	$xajax->configure('javascript URI', $APP->rutacomun . 'xajax/');
	$xajax->register(XAJAX_FUNCTION, 'sesion_abandona_V2');
	$xajax->processRequest();
	die();
}
$iConsecutivoMenu = 1;
$iMinVerDB = 7171;
$iCodModulo = 152;
$audita[1]=true;
$audita[2]=false;
$audita[3]=false;
$audita[4]=true;
$audita[5]=false;
// -- Se cargan los archivos de idioma
$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
if (!file_exists($mensajes_todas)) {
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
}
$mensajes_152='lg/lg_152_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_152)){$mensajes_152='lg/lg_152_es.php';}
require $mensajes_todas;
require $mensajes_152;
$xajax = NULL;
$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
if (isset($APP->piel)==0){$APP->piel=1;}
$iPiel=$APP->piel;
$iPiel=1; //Piel 2018.
if ($bDebug){
	$sDebug=$sDebug.''.fecha_microtiempo().' Probando conexi&oacute;n con la base de datos <b>'.$APP->dbname.'</b> en <b>'.$APP->dbhost.'</b><br>';
	}
if (!$objDB->Conectar()){
	$bCerrado=true;
	if ($bDebug){
		$sDebug=$sDebug.''.fecha_microtiempo().' Error al intentar conectar con la base de datos <b>'.$objDB->serror.'</b><br>';
		}
	}
list($bDevuelve, $sDebugP)=seg_revisa_permisoV3($iCodModulo, 1, $_SESSION['unad_id_tercero'], $objDB);
if (!$bDevuelve){
	header('Location:nopermiso.php');
	die();
	}
if (!$bPeticionXAJAX){
	if (noticias_pendientes($objDB)){
		$objDB->CerrarConexion();
		header('Location:noticia.php?ret=unadauditoria.php');
		die();
		}
	}
$idTercero=$_SESSION['unad_id_tercero'];
$bOtroUsuario=false;
if (isset($_REQUEST['debug'])!=0){
	if ($_REQUEST['debug']==1){$bDebug=true;}
	} else {
	$_REQUEST['debug']=0;
	}
//PROCESOS DE LA PAGINA
//$idEntidad = Traer_Entidad();
// -- Si esta cargando la pagina por primer vez se revisa si requiere auditar y se manda a hacer un limpiar (paso -1)
if (isset($_REQUEST['paso']) == 0) {
	$_REQUEST['paso'] = -1;
	if ($audita[1]) {
		seg_auditaingreso($iCodModulo, $_SESSION['unad_id_tercero'], $objDB);
	}
}
// -- 152 unad52auditoria
require 'lib152.php';
$xajax = new xajax();
$xajax->configure('javascript URI', $APP->rutacomun . 'xajax/');
$xajax->register(XAJAX_FUNCTION, 'Cargar_unad52codmodulo');
$xajax->register(XAJAX_FUNCTION, 'sesion_abandona_V2');
$xajax->register(XAJAX_FUNCTION, 'sesion_mantenerV4');
$xajax->register(XAJAX_FUNCTION, 'f152_HtmlTabla');
$xajax->register(XAJAX_FUNCTION, 'f152_ExisteDato');
$xajax->processRequest();
if ($bPeticionXAJAX) {
	die(); // Esto hace que las llamadas por xajax terminen aquí.
}
$bcargo = false;
$sError = '';
$sErrorCerrando = '';
$iTipoError = 0;
$bLimpiaHijos = false;
$bMueveScroll = false;
$iSector = 1;
$iHoy = fecha_DiaMod();
// -- Se inicializan las variables, primero las que controlan la visualización de la página.
if (isset($_REQUEST['iscroll'])==0){$_REQUEST['iscroll']=0;}
if (isset($_REQUEST['paginaf152'])==0){$_REQUEST['paginaf152']=1;}
if (isset($_REQUEST['lppf152'])==0){$_REQUEST['lppf152']=20;}
if (isset($_REQUEST['boculta152'])==0){$_REQUEST['boculta152']=0;}
// -- Inicializar variables de datos.
if (isset($_REQUEST['unad52fecha'])==0){$_REQUEST['unad52fecha']='';}//fecha_hoy();}
if (isset($_REQUEST['unad52fechafin'])==0){$_REQUEST['unad52fechafin']='';}//fecha_hoy();}
/*
if (isset($_REQUEST['unad52id'])==0){$_REQUEST['unad52id']='';}
if (isset($_REQUEST['unad52idtercero'])==0){$_REQUEST['unad52idtercero']='';}
if (isset($_REQUEST['unad52fecha'])==0){$_REQUEST['unad52fecha']='';}//fecha_hoy();}
if (isset($_REQUEST['unad52hora'])==0){$_REQUEST['unad52hora']=date("H");}
if (isset($_REQUEST['unad52minuto'])==0){$_REQUEST['unad52minuto']=date("i");}
if (isset($_REQUEST['unad52segundo'])==0){$_REQUEST['unad52segundo']='';}
if (isset($_REQUEST['unad52codaccion'])==0){$_REQUEST['unad52codaccion']='';}
if (isset($_REQUEST['unad52idregistro'])==0){$_REQUEST['unad52idregistro']='';}
if (isset($_REQUEST['unad52detalle'])==0){$_REQUEST['unad52detalle']='';}
*/
// Espacio para inicializar otras variables
if (isset($_REQUEST['csv_separa'])==0){$_REQUEST['csv_separa']=',';}
if (isset($_REQUEST['bnombre'])==0){$_REQUEST['bnombre']='';}
if (isset($_REQUEST['bvigencia'])==0){$_REQUEST['bvigencia']=fecha_agno().fecha_mes();}
if (isset($_REQUEST['bsistema'])==0){$_REQUEST['bsistema']='';}
if (isset($_REQUEST['bmodulo'])==0){$_REQUEST['bmodulo']=0;}
if (isset($_REQUEST['bpermiso'])==0){$_REQUEST['bpermiso']=0;}
if (isset($_REQUEST['bdoc'])==0){$_REQUEST['bdoc']='';}
if (isset($_REQUEST['brazonsocial'])==0){$_REQUEST['brazonsocial']='';}
if (isset($_REQUEST['numref'])==0){$_REQUEST['numref']='';}
//Si Modifica o Elimina Cargar los campos
//Insertar o modificar un elemento
//Eliminar un elemento
//limpiar la pantalla
if ($_REQUEST['paso']==-1){
	$_REQUEST['unad52id']='';
/*
	$_REQUEST['unad52idistema']='';
	$_REQUEST['unad52codmodulo']='';
	$_REQUEST['unad52idtercero']='';
	$_REQUEST['unad52fecha']='';//fecha_hoy();
	$_REQUEST['unad52hora']=date("H");
	$_REQUEST['unad52minuto']=date("i");
	$_REQUEST['unad52segundo']='';
	$_REQUEST['unad52codaccion']='';
	$_REQUEST['unad52idregistro']='';
	$_REQUEST['unad52detalle']='';
	$_REQUEST['paso']=0;
	*/
	}
if ($bLimpiaHijos){
	}
//AQUI SE DEBEN CARGAR TODOS LOS DATOS QUE LA FORMA NECESITE.
//DATOS PARA COMPLETAR EL FORMULARIO
//Permisos adicionales
$seg_5=0;
$seg_6=0;
//list($bDevuelve, $sDebugP)=seg_revisa_permisoV3($iCodModulo, 6, $idTercero, $objDB, $bDebug);
//if ($bDevuelve){$seg_6=1;}
//$sDebug = $sDebug . $sDebugP;
//Crear los controles que requieran llamado a base de datos
$objCombos=new clsHtmlCombos();
	$html_unad52idistema=html_combo('bsistema', 'unad01id', 'unad01nombre', 'unad01sistema', '', 'unad01nombre', $_REQUEST['bsistema'], $objDB, 'carga_combo_unad52codmodulo();', true, '{'.$ETI['msg_todos'].'}|{Modulos comunes}', '|0');
	$html_unad52codmodulo=html_combo_unad52codmodulo($objDB, $_REQUEST['bmodulo'], $_REQUEST['bsistema']);
$html_bpermiso=html_combo("bpermiso","unad03id","unad03nombre","unad03permisos","","unad03id",$_REQUEST['bpermiso'],$objDB, "",true,"{Todos}","0");
if ((int)$_REQUEST['paso']==0){
	} else {
	}
//Alistar datos adicionales
$sHTMLTablas='';
$sSel='';
if ($_REQUEST['bvigencia']=='-1'){$sSel=' Selected';}
$sHTMLTablas=$sHTMLTablas.'<option value="-1"'.$sSel.'>'.'Historicos'.'</option>';
$sSQL='SHOW TABLES LIKE "unad52auditoria%"';
$res=$objDB->ejecutasql($sSQL);
while($fila=$objDB->sf($res)){
	$valor=substr($fila[0],15);
	$sSel='';
	if ($valor==$_REQUEST['bvigencia']){$sSel=' Selected';}
	$sHTMLTablas=$sHTMLTablas.'<option value="'.$valor.'"'.$sSel.'>'.$valor.'</option>';
	}
$id_rpt=0;
//$id_rpt=reportes_id(_Identificador_Tipo_Reporte_, $objDB);
/*
$objCombos->nuevo('blistar', $_REQUEST['blistar'], true, '{'.$ETI['msg_todos'].'}');
$objCombos->sAccion='paginarf152()';
$html_blistar=$objCombos->html('', $objDB);
//$html_blistar=$objCombos->comboSistema(152, 1, $objDB, 'paginarf152()');
*/
if (false) {
	$objCombos->nuevo('csv_separa', $_REQUEST['csv_separa'], false);
	$objCombos->addItem(',', $ETI['msg_coma']);
	$objCombos->addItem(';', $ETI['msg_puntoycoma']);
	$sClaseLabel = 'Label90';
	if ($iPiel == 2) {
		$sClaseLabel = 'w-15';
	}
	$csv_separa = '<label class="' . $sClaseLabel . '">' . $ETI['msg_separador'] . '</label><label class="' . $sClaseLabel . '">' . $objCombos->html('', $objDB) . '</label>';
} else {
	$csv_separa = '<input id="csv_separa" name="csv_separa" type="hidden" value="," />';
}
 $iNumFormatosImprime = 0;
$iModeloReporte=152;
$html_iFormatoImprime = '<input id="iformatoimprime" name="iformatoimprime" type="hidden" value="0" />
';
if ($_REQUEST['paso']>0){
	list($bDevuelve, $sDebugP)=seg_revisa_permisoV3($iCodModulo, 5, $idTercero, $objDB);
	if ($bDevuelve){
		$seg_5=1;
		}
	}
//Cargar las tablas de datos
$aParametros[0]='';//$_REQUEST['p1_152'];
$aParametros[101]=$_REQUEST['paginaf152'];
$aParametros[102]=$_REQUEST['lppf152'];
$aParametros[103]=$_REQUEST['bvigencia'];
$aParametros[104]=$_REQUEST['bsistema'];
$aParametros[105]=$_REQUEST['bmodulo'];
$aParametros[106]=$_REQUEST['bpermiso'];
$aParametros[107]=$_REQUEST['bnombre'];
$aParametros[108]=$_REQUEST['bdoc'];
$aParametros[109]=$_REQUEST['brazonsocial'];
$aParametros[110]=$_REQUEST['numref'];
$aParametros[111]=$_REQUEST['unad52fecha'];
$aParametros[112]=$_REQUEST['unad52fechafin'];
list($sTabla152, $sDebugTabla)=f152_TablaDetalleV2($aParametros, $objDB, $bDebug);
$sDebug = $sDebug . $sDebugTabla;
switch ($iPiel) {
	case 2:
		list($et_menu, $sDebugM) = html_Menu2023($APP->idsistema, $objDB, $iPiel, $bDebugMenu, $idTercero);
		break;
	default:
		list($et_menu, $sDebugM) = html_menuV2($APP->idsistema, $objDB, $iPiel, $bDebugMenu, $idTercero);
		break;
}
$sDebug = $sDebug . $sDebugM;
$objDB->CerrarConexion();
//FORMA
$sTituloModulo = $ETI['titulo_152'];
require $APP->rutacomun . 'unad_forma_v2.php';
forma_cabeceraV3($xajax, $sTituloModulo);
echo $et_menu;
forma_mitad();
if (false) {
?>
<link rel="stylesheet" href="../ulib/css/criticalPath.css" type="text/css"/>
<link rel="stylesheet" href="../ulib/css/principal.css" type="text/css"/>
<link rel="stylesheet" href="../ulib/unad_estilos2018.css" type="text/css"/>
<?php
	}
?>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>js/jquery-3.3.1.min.js"></script>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>js/popper.min.js"></script>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>js/bootstrap.min.js"></script>
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>js/bootstrap.min.css" type="text/css"/>
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>css/criticalPath.css" type="text/css"/>
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>css/principal.css" type="text/css"/>
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>unad_estilos2018.css" type="text/css"/>
<?php
?>
<script language="javascript">
function expandesector(codigo){
	document.getElementById('div_sector1').style.display='none';
	document.getElementById('div_sector2').style.display='none';
	document.getElementById('div_sector95').style.display='none';
	document.getElementById('div_sector96').style.display='none';
	document.getElementById('div_sector98').style.display='none';
	document.getElementById('div_sector'+codigo).style.display='block';
	}
function imprimelista(){
	if (window.document.frmedita.seg_6.value==1){
		window.document.frmlista.consulta.value=window.document.frmedita.consulta_152.value;
		window.document.frmlista.titulos.value=window.document.frmedita.titulos_152.value;
		window.document.frmlista.nombrearchivo.value='Auditoria';
		window.document.frmlista.submit();
		} else {
		window.alert("<?php echo $ERR['6']; ?>");
		}
	}
function asignarvariables(){
	//window.document.frmimpp.v3.value=window.document.frmedita.bnombre.value;
	//window.document.frmimpp.v4.value=window.document.frmedita.bcodigo.value;
	//window.document.frmimpp.separa.value=window.document.frmedita.csv_separa.value.trim();
	}

	function imprimeexcel() {
		let sError = '';
		if (window.document.frmedita.seg_6.value != 1) {
			sError = "<?php echo $ERR['6']; ?>";
		}
		// if (sError == '') {
		// 	Agregar validaciones
		// }
		if (sError == '') {
			asignarvariables();
			window.document.frmimpp.action = 'e152.php';
			window.document.frmimpp.submit();
		} else {
			ModalMensaje(sError);
		}
	}

	function imprimep() {
		if (window.document.frmedita.seg_5.value == 1) {
			asignarvariables();
			window.document.frmimpp.action = 'p152.php';
		window.document.frmimpp.submit();
<?php
if ($iNumFormatosImprime>0){
?>
		expandesector(1);
<?php
	}
?>
		} else {
		window.alert("<?php echo $ERR['5']; ?>");
		}
	}

	function verrpt() {
		window.document.frmimprime.submit();
	}

	function eliminadato() {
		ModalConfirmV2('<?php echo $ETI['msg_confirmaeliminar']; ?>', () => {
			ejecuta_eliminadato();
		});
	}

	function ejecuta_eliminadato() {
		MensajeAlarmaV2('<?php echo $ETI['msg_ejecutando']; ?>', 2);
		expandesector(98);
		window.document.frmedita.paso.value = 13;
		window.document.frmedita.submit();
	}

	function RevisaLlave() {
		let datos = new Array();
		datos[1] = window.document.frmedita.unad52id.value;
	if ((datos[1]!='')){
		xajax_f152_ExisteDato(datos);
		}
	}

	function cargadato(llave1){
	window.document.frmedita.unad52id.value=String(llave1);
	window.document.frmedita.paso.value=1;
	window.document.frmedita.submit();
	}
function carga_combo_unad52codmodulo(){
	let params=new Array();
	params[0]=window.document.frmedita.bsistema.value;
	xajax_Cargar_unad52codmodulo(params);
	}
function paginarf152(){
	let params=new Array();
	params[99]=window.document.frmedita.debug.value;
	params[101]=window.document.frmedita.paginaf152.value;
	params[102]=window.document.frmedita.lppf152.value;
	params[103]=window.document.frmedita.bvigencia.value;
	params[104]=window.document.frmedita.bsistema.value;
	params[105]=window.document.frmedita.bmodulo.value;
	params[106]=window.document.frmedita.bpermiso.value;
	params[107]=window.document.frmedita.bnombre.value;
	params[108]=window.document.frmedita.bdoc.value;
	params[109]=window.document.frmedita.brazonsocial.value;
	params[110]=window.document.frmedita.numref.value;
	params[111]=window.document.frmedita.unad52fecha.value;
	params[112]=window.document.frmedita.unad52fechafin.value;
	//document.getElementById('div_f152detalle').innerHTML = '<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf152" name="paginaf152" type="hidden" value="' + params[101] + '" /><input id="lppf152" name="lppf152" type="hidden" value="' + params[102] + '" />';
	xajax_f152_HtmlTabla(params);
	}
function revfoco(objeto){
	setTimeout(function(){objeto.focus();},10);
	}
	function siguienteobjeto() {}
	document.onkeydown = function(e) {
		if (document.all) {
			if (event.keyCode == 13) {
				event.keyCode = 9;
			}
		} else {
			if (e.which == 13) {
				siguienteobjeto();
			}
		}
	}

	function objinicial() {
		document.getElementById("unad52id").focus();
	}
function retornacontrol(){
	expandesector(1);
	window.scrollTo(0, window.document.frmedita.iscroll.value);
	}

	function mantener_sesion() {
		xajax_sesion_mantenerV4();
	}
	setInterval('xajax_sesion_abandona_V2();', 60000);

	function AyudaLocal(sCampo) {
		let divAyuda = document.getElementById('div_ayuda_' + sCampo);
		if (typeof divAyuda === 'undefined') {
		} else {
			verboton('cmdAyuda_' + sCampo, 'none');
			let sMensaje = 'Lo que quiera decir.';
			//if (sCampo == 'sNombreCampo') {
			//sMensaje = 'Mensaje para otro campo.';
			//}
			divAyuda.innerHTML = sMensaje;
			divAyuda.style.display = 'block';
		}
	}

	function cierraDiv96(ref) {
		let sRetorna = window.document.frmedita.div96v2.value;
		MensajeAlarmaV2('', 0);
		retornacontrol();
	}
function traerauditoria(){
	MensajeAlarmaV2('Consultando auditoria.', 2);
	cambiapagina();
	}
// -->
</script>
<form id="frmimpp" name="frmimpp" method="post" action="p152.php" target="_blank" style="display:none">
<input id="r" name="r" type="hidden" value="152" />
<input id="v3" name="v3" type="hidden" value="" />
<input id="v4" name="v4" type="hidden" value="" />
<input id="v5" name="v5" type="hidden" value="" />
<input id="iformato94" name="iformato94" type="hidden" value="0" />
<input id="separa" name="separa" type="hidden" value="," />
<input id="rdebug" name="rdebug" type="hidden" value="<?php echo $_REQUEST['debug']; ?>" />
<input id="clave" name="clave" type="hidden" value="" />
</form>
<form id="frmlista" name="frmlista" method="post" action="listados.php" target="_blank" style="display:none">
<input id="titulos" name="titulos" type="hidden" value="" />
<input id="consulta" name="consulta" type="hidden" value="" />
<input id="nombrearchivo" name="nombrearchivo" type="hidden" value="" />
</form>
<div id="interna">
<form id="frmedita" name="frmedita" method="post" action="" autocomplete="off">
<input id="bNoAutocompletar" name="bNoAutocompletar" type="password" value="" style="display:none;" />
<input id="paso" name="paso" type="hidden" value="<?php echo $_REQUEST['paso']; ?>" />
<input id="shoy" name="shoy" type="hidden" value="<?php echo fecha_hoy(); ?>" />
<input id="ihoy" name="ihoy" type="hidden" value="<?php echo $iHoy; ?>" />
<input id="shora" name="shora" type="hidden" value="<?php echo fecha_hora(); ?>" />
<input id="stipodoc" name="stipodoc" type="hidden" value="<?php echo $APP->tipo_doc; ?>" />
<input id="idusuario" name="idusuario" type="hidden" value="<?php echo $_SESSION['unad_id_tercero']; ?>" />
<input id="id11" name="id11" type="hidden" value="<?php echo $idTercero; ?>" />
<input id="ipiel" name="ipiel" type="hidden" value="<?php echo $iPiel; ?>" />
<input id="seg_5" name="seg_5" type="hidden" value="<?php echo $seg_5; ?>" />
<input id="seg_6" name="seg_6" type="hidden" value="<?php echo $seg_6; ?>" />
<div id="div_sector1">
<?php
if ($bBloqueTitulo) {
	$objForma->addBoton('cmdAyuda', 'btSupAyuda', 'muestraayuda(' . $APP->idsistema . ', ' . $iCodModulo . ');', $ETI['bt_ayuda']);
	if ($bConEliminar) {
		$objForma->addBoton('cmdEliminar', 'btUpEliminar', 'eliminadato();', $ETI['bt_eliminar']);
	}
	if ($bHayImprimir) {
		$objForma->addBoton('cmdImprimir', $sClaseImprime, $sScriptImprime, $ETI['bt_imprimir']);
	}
?>
<input id="cmdLimpiar" name="cmdLimpiar" type="button" class="btUpLimpiar" onclick="limpiapagina();" title="<?php echo $ETI['bt_limpiar']; ?>" value="<?php echo $ETI['bt_limpiar']; ?>"/>
</div>
<div class="titulosI">
<?php
echo '<h2>'.$sTituloModulo.'</h2>';
?>
</div>
</div>
<div class="areaform">
<div class="areatrabajo">
<?php
//Div para ocultar
$bConExpande = false;
if ($bConExpande) {
?>
<div class="ir_derecha"<?php echo $sAnchoExpandeContrae; ?>>
<input id="boculta152" name="boculta152" type="hidden" value="<?php echo $_REQUEST['boculta152']; ?>" />
<label class="Label30">
<input id="btexpande152" name="btexpande152" type="button" value="Mostrar" class="btMiniExpandir" onclick="expandepanel(152,'block',0);" title="<?php echo $ETI['bt_mostrar']; ?>" style="display:<?php if ($_REQUEST['boculta152']==0){echo 'none'; } else {echo 'block';} ?>;"/>
</label>
<label class="Label30">
<input id="btrecoge152" name="btrecoge152" type="button" value="Ocultar" class="btMiniRecoger" onclick="expandepanel(152,'none',1);" title="<?php echo $ETI['bt_ocultar']; ?>" style="display:<?php if ($_REQUEST['boculta152']==0){echo 'block'; } else {echo 'none';} ?>;"/>
</label>
</div>
<div id="div_p152" style="display:<?php if ($_REQUEST['boculta152']==0){echo 'block'; } else {echo 'none';} ?>;">
<?php
}
//Mostrar formulario para editar
?>
<label class="Label90">
Vigencia
</label>
<label class="Label130">
<select name="bvigencia" id="bvigencia" onchange="traerauditoria()">
<?php
echo $sHTMLTablas;
?>
</select>
</label>
<label class="Label60">
<?php
echo $ETI['unad52idistema'];
?>
</label>
<label>
<?php
echo $html_unad52idistema;
?>
</label>
<div class="salto1px"></div>
<label class="Label90">
<?php
echo $ETI['unad52codmodulo'];
?>
</label>
<label class="Label450"><div id="div_unad52codmodulo">
<?php
echo $html_unad52codmodulo;
?>
</div></label>
<label class="Label60">
Acci&oacute;n
</label>
<label class="Label130">
<?php
echo $html_bpermiso;
?>
</label>
<div class="salto1px"></div>
<label class="Label90">
<?php
echo $ETI['unad52fecha'];
?>
</label>
<div class="Campo220">
<?php
echo html_fecha("unad52fecha", $_REQUEST['unad52fecha'], true);//$bvacio=false,$accion=",$iagnoini=0,$iagnofin=0
?>
</div>
<label class="Label30">
<input id="bunad52fecha_hoy" name="bunad52fecha_hoy" type="button" value="Hoy" class="btMiniHoy" onclick="fecha_asignar('unad52fecha','<?php echo date('d/m/Y'); ?>')" title="<?php echo $ETI['bt_hoy']; ?>"/>
</label>

<label class="Label30"></label>
<label class="Label60">
<?php
echo $ETI['unad52fechafin'];
?>
</label>
<div class="Campo220">
<?php
echo html_fecha("unad52fechafin", $_REQUEST['unad52fechafin'], true);//$bvacio=false,$accion=",$iagnoini=0,$iagnofin=0
?>
</div>
<label class="Label30">
<input id="bunad52fechafin_hoy" name="bunad52fechafin_hoy" type="button" value="Hoy" class="btMiniHoy" onclick="fecha_asignar('unad52fechafin','<?php echo date('d/m/Y'); ?>')" title="<?php echo $ETI['bt_hoy']; ?>"/>
</label>

<div class="salto1px"></div>
<label class="Label90">
Detalle
</label>
<label>
<input name="bnombre" type="text" id="bnombre" value="<?php echo $_REQUEST['bnombre']; ?>"/>
</label>
<label class="Label90">
N&deg; Ref
</label>
<label>
<input name="numref" type="text" id="numref" value="<?php echo $_REQUEST['numref']; ?>" class="diez"/>
</label>
<div class="salto1px"></div>
<label class="Label90">
Documento
</label>
<label>
<input name="bdoc" type="text" id="bdoc" value="<?php echo $_REQUEST['bdoc']; ?>"/>
</label>
<label class="Label90">
Nombres
</label>
<label>
<input name="brazonsocial" type="text" id="brazonsocial" value="<?php echo $_REQUEST['brazonsocial']; ?>"/>
</label>
<label class="Label30"></label>
<label class="Label130">
<input id="cmdBuscar" name="cmdBuscar" type="button" class="BotonAzul" value="Consultar" onclick="traerauditoria()" />
</label>
<div class="salto1px"></div>
<?php
if (false) {
	//Ejemplo de boton de ayuda
	//echo html_BotonAyuda('NombreCampo');
	//echo html_DivAyudaLocal('NombreCampo');
}
if ($bConExpande) {
	//Este es el cierre del div_p152
?>
<div class="salto1px"></div>
</div>
<?php
	}
//Mostrar el contenido de la tabla
?>
<?php
echo ' ' . $csv_separa;
?>
<div class="salto1px"></div>
<div id="div_f152detalle">
<?php
echo $sTabla152;
?>
</div>
<?php
// CIERRA EL DIV areatrabajo
?>
<?php
echo $objForma->htmlFinMarco();
?>
</div><!-- /DIV_Sector1 -->


<div id="div_sector2" style="display:none">
<?php
$objForma = new clsHtmlForma($iPiel);
if ($bBloqueTitulo) {
	$objForma->addBoton('cmdAyuda2', 'btSupAyuda', 'muestraayuda(' . $iCodModulo . ');', $ETI['bt_ayuda']);
	$objForma->addBoton('cmdVolverSec2', 'btSupVolver', 'retornacontrol();', $ETI['bt_volver']);
	echo $objForma->htmlTitulo($sTituloModulo, $iCodModulo);
}
echo $objForma->htmlInicioMarco();
echo $objForma->htmlFinMarco();
?>
</div><!-- /DIV_Sector2 -->


<div id="div_sector95" style="display:none">
<?php
echo $objForma->htmlInicioMarco();
echo '<div id="div_95cuerpo"></div>';
echo $objForma->htmlFinMarco();
?>
</div><!-- /DIV_Sector95 -->


<div id="div_sector96" style="display:none">
<input id="div96v1" name="div96v1" type="hidden" value="" />
<input id="div96v2" name="div96v2" type="hidden" value="" />
<input id="div96v3" name="div96v3" type="hidden" value="" />
<input id="div96campo" name="div96campo" type="hidden" value="" />
<input id="div96llave" name="div96llave" type="hidden" value="" />
<input id="titulo_152" name="titulo_152" type="hidden" value="<?php echo $sTituloModulo; ?>" />
<?php
$objForma=new clsHtmlForma($iPiel);
if ($bBloqueTitulo) {
	$objForma->addBoton('cmdAyuda96', 'btSupAyuda', 'muestraayuda(' . $iCodModulo . ');', $ETI['bt_ayuda']);
	$objForma->addBoton('cmdVolverSec96', 'btSupVolver', 'retornacontrol();', $ETI['bt_volver']);
	echo $objForma->htmlTitulo($sTituloModulo, $iCodModulo, 'div_96titulo');
}
echo $objForma->htmlInicioMarco();
echo '<div id="div_96cuerpo"></div>';
echo $objForma->htmlFinMarco();
?>
</div><!-- /DIV_Sector96 -->


<div id="div_sector97" style="display:none">
<?php
$objForma = new clsHtmlForma($iPiel);
if ($bBloqueTitulo) {
	$objForma->addBoton('cmdAyuda97', 'btSupAyuda', 'muestraayuda(' . $iCodModulo . ');', $ETI['bt_ayuda']);
	$objForma->addBoton('cmdVolverSec97', 'btSupVolver', 'retornacontrol();', $ETI['bt_volver']);
	echo $objForma->htmlTitulo($sTituloModulo, $iCodModulo, 'div_97titulo');
}
echo $objForma->htmlInicioMarco();
?>
<div id="div_97params"></div>
<div class="salto1px"></div>
<div id="div_97tabla"></div>
<?php
echo $objForma->htmlFinMarco();
?>
</div><!-- /DIV_Sector97 -->


<div id="div_sector98" style="display:none">
<?php
$objForma=new clsHtmlForma($iPiel);
$objForma->addBoton('cmdAyuda98', 'btSupAyuda', 'muestraayuda(' . $iCodModulo . ');', $ETI['bt_ayuda']);
echo $objForma->htmlTitulo($sTituloModulo, $iCodModulo);
echo $objForma->htmlInicioMarco();
echo $objForma->htmlEspere($ETI['msg_espere']);
echo $objForma->htmlFinMarco();
?>
</div><!-- /DIV_Sector98 -->


<?php
if ($sDebug != '') {
	$iSegFin = microtime(true);
	if (isset($iSegIni) == 0) {
		$iSegIni = $iSegFin;
	}
	$iSegundos = $iSegFin-$iSegIni;
	echo '<div class="salto1px"></div><div class="GrupoCampos" id="div_debug">' . $sDebug . fecha_microtiempo() . ' Tiempo total del proceso: <b>' . $iSegundos . '</b> Segundos' . '<div class="salto1px"></div></div>';
}
?>
<input id="scampobusca" name="scampobusca" type="hidden" value="" />
<input id="iscroll" name="iscroll" type="hidden" value="<?php echo $_REQUEST['iscroll']; ?>" />
<input id="itipoerror" name="itipoerror" type="hidden" value="<?php echo $iTipoError; ?>" />
<input id="debug" name="debug" type="hidden" value="<?php echo $_REQUEST['debug']; ?>" />
</form>
<?php
// Termina el bloque div_interna
?>
</div>
<?php
echo html_DivAlarmaV2($sError, $iTipoError);
//El script que cambia el sector que se muestra
?>

<script language="javascript">
<!--
<?php
if ($iSector!=1){
	echo 'setTimeout(function(){expandesector('.$iSector.');}, 10);
';
	}
if ($bMueveScroll){
	echo 'setTimeout(function(){retornacontrol();}, 2);
';
	}
?>
-->
</script>
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>js/chosen.css" type="text/css"/>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>js/chosen.jquery.js"></script>
<script language="javascript" src="ac_152.js"></script>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>unad_todas2024.js?ver=2"></script>
<?php
forma_piedepagina();