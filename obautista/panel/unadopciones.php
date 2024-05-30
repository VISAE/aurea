<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2017 - 2022 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.17.0 viernes, 03 de marzo de 2017
--- Modelo Versión 2.28.1 viernes, 8 de abril de 2022 - Comercial
*/
/** Archivo unadopciones.php.
* Modulo 188 unad88opciones.
* @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
* @param debug=1 (Opcional), bandera para indicar si se generan datos de depuración
* @date martes, 17 de agosto de 2021
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
require $APP->rutacomun . 'libdatos.php';
require $APP->rutacomun . 'libhtml.php';
require $APP->rutacomun . 'xajax/xajax_core/xajax.inc.php';
require $APP->rutacomun . 'unad_xajax.php';
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
$iCodModulo = 188;
$audita[1] = false;
$audita[2] = true;
$audita[3] = true;
$audita[4] = true;
$audita[5] = false;
// -- Se cargan los archivos de idioma
$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
if (!file_exists($mensajes_todas)) {
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
}
$mensajes_188='lg/lg_188_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_188)){$mensajes_188='lg/lg_188_es.php';}
require $mensajes_todas;
require $mensajes_188;
$xajax = NULL;
$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
$iPiel=iDefinirPiel($APP, 1);
$sAnchoExpandeContrae=' style="width:62px;"';
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
		header('Location:noticia.php?ret=unadopciones.php');
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
$mensajes_189='lg/lg_189_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_189)){$mensajes_189='lg/lg_189_es.php';}
require $mensajes_189;
// -- Si esta cargando la pagina por primer vez se revisa si requiere auditar y se manda a hacer un limpiar (paso -1)
if (isset($_REQUEST['paso'])==0){
	$_REQUEST['paso']=3;
	$_REQUEST['unad88id']=1;
	if ($audita[1]){seg_auditaingreso($iCodModulo, $_SESSION['unad_id_tercero'], $objDB);}
	}
// -- 188 unad88opciones
require 'lib188.php';
// -- 189 SMTPS para login
require 'lib189.php';
$xajax = new xajax();
$xajax->configure('javascript URI', $APP->rutacomun . 'xajax/');
$xajax->register(XAJAX_FUNCTION, 'unad11_Mostrar_v2');
$xajax->register(XAJAX_FUNCTION, 'unad11_TraerXid');
$xajax->register(XAJAX_FUNCTION, 'sesion_abandona_V2');
$xajax->register(XAJAX_FUNCTION, 'sesion_mantenerV4');
$xajax->register(XAJAX_FUNCTION, 'f188_HtmlTabla');
$xajax->register(XAJAX_FUNCTION, 'f188_ExisteDato');
$xajax->register(XAJAX_FUNCTION, 'f188_Busquedas');
$xajax->register(XAJAX_FUNCTION, 'f188_HtmlBusqueda');
$xajax->register(XAJAX_FUNCTION, 'f189_Guardar');
$xajax->register(XAJAX_FUNCTION, 'f189_Traer');
$xajax->register(XAJAX_FUNCTION, 'f189_Eliminar');
$xajax->register(XAJAX_FUNCTION, 'f189_HtmlTabla');
$xajax->register(XAJAX_FUNCTION, 'f189_PintarLlaves');
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
$iHoy=fecha_DiaMod();
// -- Se inicializan las variables, primero las que controlan la visualización de la página.
if (isset($_REQUEST['iscroll'])==0){$_REQUEST['iscroll']=0;}
if (isset($_REQUEST['paginaf189'])==0){$_REQUEST['paginaf189']=1;}
if (isset($_REQUEST['lppf189'])==0){$_REQUEST['lppf189']=20;}
if (isset($_REQUEST['boculta189'])==0){$_REQUEST['boculta189']=0;}
// -- Inicializar variables de datos.
if (isset($_REQUEST['unad88id'])==0){$_REQUEST['unad88id']='';}
if (isset($_REQUEST['unad88idtercero'])==0){$_REQUEST['unad88idtercero']=0;}// {$_SESSION['unad_id_tercero'];}
if (isset($_REQUEST['unad88idtercero_td'])==0){$_REQUEST['unad88idtercero_td']=$APP->tipo_doc;}
if (isset($_REQUEST['unad88idtercero_doc'])==0){$_REQUEST['unad88idtercero_doc']='';}
if (isset($_REQUEST['unad88nombreent'])==0){$_REQUEST['unad88nombreent']='';}
if (isset($_REQUEST['unad88siglaent'])==0){$_REQUEST['unad88siglaent']='';}
if (isset($_REQUEST['unad88tableroestado'])==0){$_REQUEST['unad88tableroestado']=0;}
if (isset($_REQUEST['unad88tableromsgcerrado'])==0){$_REQUEST['unad88tableromsgcerrado']='';}
if (isset($_REQUEST['unad88congeolocaliza'])==0){$_REQUEST['unad88congeolocaliza']='S';}
if (isset($_REQUEST['unad88loginmail'])==0){$_REQUEST['unad88loginmail']='';}
if (isset($_REQUEST['unad88rutacongresos'])==0){$_REQUEST['unad88rutacongresos']='';}
if (isset($_REQUEST['unad88idioma'])==0){$_REQUEST['unad88idioma']='';}
if (isset($_REQUEST['unad88dblogs'])==0){$_REQUEST['unad88dblogs']=0;}
if ((int)$_REQUEST['paso']>0){
	//SMTPS para login
	if (isset($_REQUEST['unad89idsmtp'])==0){$_REQUEST['unad89idsmtp']='';}
	if (isset($_REQUEST['unad89id'])==0){$_REQUEST['unad89id']='';}
	if (isset($_REQUEST['unad89activo'])==0){$_REQUEST['unad89activo']='S';}
	if (isset($_REQUEST['unad89enviosddia'])==0){$_REQUEST['unad89enviosddia']='';}
	}
// Espacio para inicializar otras variables
if (isset($_REQUEST['csv_separa'])==0){$_REQUEST['csv_separa']=',';}
if (isset($_REQUEST['bnombre'])==0){$_REQUEST['bnombre']='';}
/*
if (isset($_REQUEST['blistar']) == 0) {
	$_REQUEST['blistar'] = '';
}
*/
//Si Modifica o Elimina Cargar los campos
if ($_REQUEST['paso']==-1){
	$_REQUEST['paso']=1;
	}
if (($_REQUEST['paso']==1)||($_REQUEST['paso']==3)){
	$_REQUEST['unad88idtercero_td']=$APP->tipo_doc;
	$_REQUEST['unad88idtercero_doc']='';
	//'.$_REQUEST['unad88id'].'
	$sSQL='SELECT * FROM unad88opciones WHERE unad88id=1';
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$_REQUEST['unad88id']=$fila['unad88id'];
		$_REQUEST['unad88idtercero']=$fila['unad88idtercero'];
		$_REQUEST['unad88nombreent']=$fila['unad88nombreent'];
		$_REQUEST['unad88siglaent']=$fila['unad88siglaent'];
		$_REQUEST['unad88tableroestado']=$fila['unad88tableroestado'];
		$_REQUEST['unad88tableromsgcerrado']=$fila['unad88tableromsgcerrado'];
		$_REQUEST['unad88congeolocaliza']=$fila['unad88congeolocaliza'];
		$_REQUEST['unad88loginmail']=$fila['unad88loginmail'];
		$_REQUEST['unad88rutacongresos']=$fila['unad88rutacongresos'];
		$_REQUEST['unad88idioma']=$fila['unad88idioma'];
		$_REQUEST['unad88dblogs']=$fila['unad88dblogs'];
		$bcargo=true;
		$_REQUEST['paso']=2;
		$_REQUEST['boculta188']=0;
		$bLimpiaHijos=true;
		} else {
		$_REQUEST['paso']=0;
		}
	}
//Insertar o modificar un elemento
if (($_REQUEST['paso'] == 10) || ($_REQUEST['paso'] == 12)) {
	$bMueveScroll = true;
	list($_REQUEST, $sError, $iTipoError, $sDebugGuardar)=f188_db_GuardarV2($_REQUEST, $objDB, $bDebug, $idTercero);
	$sDebug=$sDebug.$sDebugGuardar;
	if ($sError==''){
		$sError='<b>'.$ETI['msg_itemguardado'].'</b>';
		$iTipoError=1;
		}
	}
//limpiar la pantalla
/*
if ($_REQUEST['paso']==-1){
	$_REQUEST['unad88id']='';
	$_REQUEST['unad88idtercero']=0;//$idTercero;
	$_REQUEST['unad88idtercero_td']=$APP->tipo_doc;
	$_REQUEST['unad88idtercero_doc']='';
	$_REQUEST['unad88nombreent']='';
	$_REQUEST['unad88siglaent']='';
	$_REQUEST['unad88tableroestado']='';
	$_REQUEST['unad88tableromsgcerrado']='';
	$_REQUEST['unad88congeolocaliza']='';
	$_REQUEST['unad88loginmail']='S';
	$_REQUEST['unad88rutacongresos']='';
	$_REQUEST['unad88idioma']='es';
	$_REQUEST['unad88dblogs']=0;
	$_REQUEST['paso']=0;
	}
*/
if ($bLimpiaHijos){
	$_REQUEST['unad89idopciones']='';
	$_REQUEST['unad89idsmtp']='';
	$_REQUEST['unad89id']='';
	$_REQUEST['unad89activo']='S';
	$_REQUEST['unad89enviosddia']='';
	}
//AQUI SE DEBEN CARGAR TODOS LOS DATOS QUE LA FORMA NECESITE.
//DATOS PARA COMPLETAR EL FORMULARIO
//Crear los controles que requieran llamado a base de datos
$objCombos=new clsHtmlCombos();
$objTercero=new clsHtmlTercero();
list($unad88idtercero_rs, $_REQUEST['unad88idtercero'], $_REQUEST['unad88idtercero_td'], $_REQUEST['unad88idtercero_doc'])=html_tercero($_REQUEST['unad88idtercero_td'], $_REQUEST['unad88idtercero_doc'], $_REQUEST['unad88idtercero'], 0, $objDB);
$objCombos->nuevo('unad88tableroestado', $_REQUEST['unad88tableroestado'], false, '{'.$ETI['msg_seleccione'].'}');
$objCombos->addItem(0, 'En servicio');
$objCombos->addItem(1, 'Cerrado');
$html_unad88tableroestado=$objCombos->html('', $objDB);
$objCombos->nuevo('unad88congeolocaliza', $_REQUEST['unad88congeolocaliza'], false);
$objCombos->sino($ETI['si'], $ETI['no']);//, $sValorSi='S', $sValorNo='N'
$html_unad88congeolocaliza=$objCombos->html('', $objDB);
$objCombos->nuevo('unad88loginmail', $_REQUEST['unad88loginmail'], false);
$objCombos->sino($ETI['si'], $ETI['no']); //, $sValorSi='S', $sValorNo='N'
$html_unad88loginmail=$objCombos->html('', $objDB);
$objCombos->nuevo('unad88idioma', $_REQUEST['unad88idioma'], false, '{'.$ETI['msg_seleccione'].'}');
$sSQL='SELECT unae08id AS id, unae08nombre AS nombre FROM unae08idioma ORDER BY unae08nombre';
$html_unad88idioma=$objCombos->html($sSQL, $objDB);
$objCombos->nuevo('unad88dblogs', $_REQUEST['unad88dblogs'], true, '{'.$ETI['msg_seleccione'].'}');
$sSQL='SELECT unae25id AS id, CONCAT(unae25db, " ", unae25server, ":", unae25puerto, " ", unae25fechaini) AS nombre FROM unae25dblog WHERE unae25tipouso=0 ORDER BY unae25fechaini';
$html_unad88dblogs=$objCombos->html($sSQL, $objDB);
if ((int)$_REQUEST['paso']==0){
	} else {
	$html_unad89idsmtp=f189_HTMLComboV2_unad89idsmtp($objDB, $objCombos, $_REQUEST['unad89idsmtp']);
	$objCombos->nuevo('unad89activo', $_REQUEST['unad89activo'], false);
	$objCombos->sino($ETI['si'], $ETI['no']);//, $sValorSi='S', $sValorNo='N'
	$html_unad89activo=$objCombos->html('', $objDB);
	}
//Alistar datos adicionales
$id_rpt=0;
//$id_rpt=reportes_id(_Identificador_Tipo_Reporte_, $objDB);
/*
$objCombos->nuevo('blistar', $_REQUEST['blistar'], true, '{'.$ETI['msg_todos'].'}');
$html_blistar=$objCombos->comboSistema(188, 1, $objDB, 'paginarf188()');
$objCombos->nuevo('blistar189', $_REQUEST['blistar189'], true, '{'.$ETI['msg_todos'].'}');
$html_blistar189=$objCombos->comboSistema(189, 1, $objDB, 'paginarf189()');
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
$iModeloReporte=188;
$html_iFormatoImprime = '<input id="iformatoimprime" name="iformatoimprime" type="hidden" value="0" />
';
if ($_REQUEST['paso']>0){
	$bDevuelve=false;
	//list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 5, $idTercero, $objDB);
	if ($bDevuelve){
		$seg_5=1;
		}
	}
//Cargar las tablas de datos
$sTabla189='';
if ($_REQUEST['paso']!=0){
	//SMTPS para login
	$aParametros189[0]=$_REQUEST['unad88id'];
	$aParametros189[100]=$idTercero;
	$aParametros189[101]=$_REQUEST['paginaf189'];
	$aParametros189[102]=$_REQUEST['lppf189'];
	//$aParametros189[103]=$_REQUEST['bnombre189'];
	//$aParametros189[104]=$_REQUEST['blistar189'];
	list($sTabla189, $sDebugTabla)=f189_TablaDetalleV2($aParametros189, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugTabla;
	}
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
$sTituloModulo = $ETI['titulo_188'];
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
function limpiapagina(){
	expandesector(98);
	window.document.frmedita.paso.value=-1;
	window.document.frmedita.submit();
	}
function enviaguardar(){
	window.document.frmedita.iscroll.value=window.scrollY;
	expandesector(98);
	var dpaso=window.document.frmedita.paso;
	if (dpaso.value==0){
		dpaso.value=10;
		} else {
		dpaso.value=12;
		}
	window.document.frmedita.submit();
	}
function cambiapagina(){
	expandesector(98);
	window.document.frmedita.submit();
	}
function cambiapaginaV2(){
	expandesector(98);
	window.document.frmedita.paso.value=1;
	window.document.frmedita.submit();
	}
function expandepanel(codigo,estado,valor){
	var objdiv= document.getElementById('div_p'+codigo);
	var objban= document.getElementById('boculta'+codigo);
	var otroestado='none';
	if (estado=='none'){otroestado='block';}
	objdiv.style.display=estado;
	objban.value=valor;
	verboton('btrecoge'+codigo,estado);
	verboton('btexpande'+codigo,otroestado);
	}
function verboton(idboton,estado){
	var objbt=document.getElementById(idboton);
	objbt.style.display=estado;
	}
function expandesector(codigo){
	document.getElementById('div_sector1').style.display='none';
	document.getElementById('div_sector2').style.display='none';
	document.getElementById('div_sector95').style.display='none';
	document.getElementById('div_sector96').style.display='none';
	document.getElementById('div_sector97').style.display='none';
	document.getElementById('div_sector98').style.display='none';
	document.getElementById('div_sector'+codigo).style.display='block';
	var sEst='none';
	if (codigo==1){sEst='block';}
	document.getElementById('cmdGuardarf').style.display=sEst;
	}
	function ter_retorna() {
		let sRetorna = window.document.frmedita.div96v2.value;
		if (sRetorna != '') {
			let idcampo = window.document.frmedita.div96campo.value;
			let illave = window.document.frmedita.div96llave.value;
			let did = document.getElementById(idcampo);
			let dtd = document.getElementById(idcampo + '_td');
			let ddoc = document.getElementById(idcampo + '_doc');
			dtd.value = window.document.frmedita.div96v1.value;
			ddoc.value = sRetorna;
			did.value = window.document.frmedita.div96v3.value;
			ter_muestra(idcampo, illave);
		}
		MensajeAlarmaV2('', 0);
		retornacontrol();
	}

	function ter_muestra(idcampo, illave) {
		let params = new Array();
		params[1] = document.getElementById(idcampo + '_doc').value;
		if (params[1] != '') {
			params[0] = document.getElementById(idcampo + '_td').value;
			params[2] = idcampo;
			params[3] = 'div_' + idcampo;
			if (illave == 1) {
				params[4] = 'RevisaLlave';
				//params[5] = 'FuncionCuandoNoEsta';
			}
			xajax_unad11_Mostrar_v2(params);
		} else {
			document.getElementById(idcampo).value = 0;
			document.getElementById('div_' + idcampo).innerHTML = '&nbsp;';
			//FuncionCuandoNoHayNada
		}
	}

function ter_traerxid(idcampo, vrcampo){
	let params=new Array();
	params[0]=vrcampo;
	params[1]=idcampo;
	if (params[0]!=0){
		xajax_unad11_TraerXid(params);
		}
	}
function imprimelista(){
	if (window.document.frmedita.seg_6.value==1){
		window.document.frmlista.consulta.value=window.document.frmedita.consulta_188.value;
		window.document.frmlista.titulos.value=window.document.frmedita.titulos_188.value;
		window.document.frmlista.nombrearchivo.value='Opciones';
		window.document.frmlista.submit();
		} else {
			ModalMensaje("<?php echo $ERR['6']; ?>");
		}
	}

	function asignarvariables() {
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
			window.document.frmimpp.action = 'e188.php';
			window.document.frmimpp.submit();
		} else {
			ModalMensaje(sError);
		}
	}

	function imprimep() {
		if (window.document.frmedita.seg_5.value == 1) {
			asignarvariables();
			window.document.frmimpp.action = 'p188.php';
		window.document.frmimpp.submit();
		} else {
		ModalMensaje("<?php echo $ERR['5']; ?>");
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
		datos[1] = window.document.frmedita.unad88id.value;
	if ((datos[1]!='')){
		xajax_f188_ExisteDato(datos);
		}
	}

	function cargadato(llave1){
	window.document.frmedita.unad88id.value=String(llave1);
	window.document.frmedita.paso.value=1;
	window.document.frmedita.submit();
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
		document.getElementById("unad88id").focus();
	}
function buscarV2016(sCampo){
	window.document.frmedita.iscroll.value=window.scrollY;
	expandesector(98);
	window.document.frmedita.scampobusca.value=sCampo;
	let params=new Array();
	params[1]=sCampo;
	//params[2]=window.document.frmedita.iagno.value;
	//params[3]=window.document.frmedita.itipo.value;
	xajax_f188_Busquedas(params);
	}

	function Devuelve(sValor) {
		let sCampo = window.document.frmedita.scampobusca.value;
	if (sCampo=='unad88idtercero'){
		ter_traerxid('unad88idtercero', sValor);
		}
		retornacontrol();
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
</script>
<?php
if ($_REQUEST['paso'] != 0) {
?>
<script language="javascript" src="jsi/js189.js"></script>
<form id="frmimpp" name="frmimpp" method="post" action="p188.php" target="_blank" style="display:none">
<input id="r" name="r" type="hidden" value="188" />
<input id="v3" name="v3" type="hidden" value="" />
<input id="v4" name="v4" type="hidden" value="" />
<input id="v5" name="v5" type="hidden" value="" />
<input id="iformato94" name="iformato94" type="hidden" value="0" />
<input id="separa" name="separa" type="hidden" value="," />
<input id="rdebug" name="rdebug" type="hidden" value="<?php echo $_REQUEST['debug']; ?>" />
<input id="clave" name="clave" type="hidden" value="" />
</form>
<?php
}
?>
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
<div class="titulos">
<div class="titulosD">
<input id="cmdAyuda" name="cmdAyuda" type="button" class="btUpAyuda" onclick="muestraayuda(<?php echo $APP->idsistema.', '.$iCodModulo; ?>);" title="<?php echo $ETI['bt_ayuda']; ?>" value="<?php echo $ETI['bt_ayuda']; ?>"/>
<input id="cmdLimpiar" name="cmdLimpiar" type="button" class="btUpLimpiar" onclick="limpiapagina();" title="<?php echo $ETI['bt_limpiar']; ?>" value="<?php echo $ETI['bt_limpiar']; ?>"/>
<input id="cmdGuardar" name="cmdGuardar" type="button" class="btUpGuardar" onclick="enviaguardar();" title="<?php echo $ETI['bt_guardar']; ?>" value="<?php echo $ETI['bt_guardar']; ?>"/>
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
//Mostrar formulario para editar
?>
<input id="unad88id" name="unad88id" type="hidden" value="<?php echo $_REQUEST['unad88id']; ?>"/>
<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo $ETI['unad88idtercero'];
?>
</label>
<div class="salto1px"></div>
<input id="unad88idtercero" name="unad88idtercero" type="hidden" value="<?php echo $_REQUEST['unad88idtercero']; ?>"/>
<div id="div_unad88idtercero_llaves">
<?php
$bOculto=false;
echo html_DivTerceroV2('unad88idtercero', $_REQUEST['unad88idtercero_td'], $_REQUEST['unad88idtercero_doc'], $bOculto, 0, $ETI['ing_doc']);
?>
</div>
<div class="salto1px"></div>
<div id="div_unad88idtercero" class="L"><?php echo $unad88idtercero_rs; ?></div>
<div class="salto1px"></div>
</div>
<label class="L">
<?php
echo $ETI['unad88nombreent'];
?>

<input id="unad88nombreent" name="unad88nombreent" type="text" value="<?php echo $_REQUEST['unad88nombreent']; ?>" maxlength="200" class="L" placeholder="<?php echo $ETI['ing_campo'].$ETI['unad88nombreent']; ?>"/>
</label>
<label class="Label130">
<?php
echo $ETI['unad88siglaent'];
?>
</label>
<label>
<input id="unad88siglaent" name="unad88siglaent" type="text" value="<?php echo $_REQUEST['unad88siglaent']; ?>" maxlength="30" placeholder="<?php echo $ETI['ing_campo'].$ETI['unad88siglaent']; ?>"/>
</label>
<label class="Label160">
<?php
echo $ETI['unad88tableroestado'];
?>
</label>
<label>
<?php
echo $html_unad88tableroestado;
?>
</label>
<label class="txtAreaS">
<?php
echo $ETI['unad88tableromsgcerrado'];
?>
<textarea id="unad88tableromsgcerrado" name="unad88tableromsgcerrado" placeholder="<?php echo $ETI['ing_campo'].$ETI['unad88tableromsgcerrado']; ?>"><?php echo $_REQUEST['unad88tableromsgcerrado']; ?></textarea>
</label>
<label class="L">
<?php
echo $ETI['unad88rutacongresos'];
?>

<input id="unad88rutacongresos" name="unad88rutacongresos" type="text" value="<?php echo $_REQUEST['unad88rutacongresos']; ?>" maxlength="100" class="L" placeholder="<?php echo $ETI['ing_campo'].$ETI['unad88rutacongresos']; ?>"/>
</label>

<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo" style="width:100%">
<?php
echo $ETI['msg_adicionales'];
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['unad88congeolocaliza'];
?>
</label>
<label class="Label90">
<?php
echo $html_unad88congeolocaliza;
?>
</label>
<label class="Label90">
<?php
echo $ETI['unad88idioma'];
?>
</label>
<label class="Label220">
<?php
echo $html_unad88idioma;
?>
</label>
<input id="unad88dblogs" name="unad88dblogs" type="hidden" value="<?php echo $_REQUEST['unad88dblogs']; ?>"/>
<label class="Label220">
<?php
echo $ETI['unad88loginmail'];
?>
</label>
<label class="Label60">
<?php
echo $html_unad88loginmail;
?>
</label>
<div class="salto1px"></div>
</div>
<?php
// -- Inicia Grupo campos 189 SMTPS para login
?>
<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo $ETI['titulo_189'];
?>
</label>
<input id="boculta189" name="boculta189" type="hidden" value="<?php echo $_REQUEST['boculta189']; ?>" />
<?php
if ($_REQUEST['paso']==2){
	//if ($bCondicion){
?>
<div class="ir_derecha"<?php echo $sAnchoExpandeContrae; ?>>
<label class="Label30">
<input id="btexpande189" name="btexpande189" type="button" value="Expandir" class="btMiniExpandir" onclick="expandepanel(189,'block',0);" title="<?php echo $ETI['bt_mostrar']; ?>" style="display:<?php if ($_REQUEST['boculta189']==0){echo 'none'; } else {echo 'block';} ?>;"/>
</label>
<label class="Label30">
<input id="btrecoge189" name="btrecoge189" type="button" value="Recoger" class="btMiniRecoger" onclick="expandepanel(189,'none',1);" title="<?php echo $ETI['bt_ocultar']; ?>" style="display:<?php if ($_REQUEST['boculta189']==0){echo 'block'; } else {echo 'none';} ?>;"/>
</label>
</div>
<div class="salto1px"></div>
<div id="div_p189" style="display:<?php if ($_REQUEST['boculta189']==0){echo 'block'; } else {echo 'none';} ?>;">
<label class="Label90">
<?php
echo $ETI['unad89idsmtp'];
?>
</label>
<label>
<div id="div_unad89idsmtp">
<?php
echo $html_unad89idsmtp;
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label90">
<?php
echo $ETI['unad89activo'];
?>
</label>
<label class="Label60">
<?php
echo $html_unad89activo;
?>
</label>
<label class="Label60">
<?php
echo $ETI['unad89id'];
?>
</label>
<label class="Label60"><div id="div_unad89id">
<?php
	echo html_oculto('unad89id', $_REQUEST['unad89id']);
?>
</div></label>
<label class="Label130">
<?php
echo $ETI['unad89enviosddia'];
?>
</label>
<label class="Label130">
<input id="unad89enviosddia" name="unad89enviosddia" type="text" value="<?php echo $_REQUEST['unad89enviosddia']; ?>" class="diez" maxlength="10" placeholder="<?php echo $ETI['ing_vr']; ?>"/>
</label>
<div class="salto1px"></div>
<label class="Label130">&nbsp;</label>
<label class="Label30">
<input id="bguarda189" name="bguarda189" type="button" value="Guardar" class="btMiniGuardar" onclick="guardaf189()" title="<?php echo $ETI['bt_mini_guardar_189']; ?>"/>
</label>
<label class="Label30">
<input id="blimpia189" name="blimpia189" type="button" value="Limpiar" class="btMiniLimpiar" onclick="limpiaf189()" title="<?php echo $ETI['bt_mini_limpiar_189']; ?>"/>
</label>
<label class="Label30">
<input id="belimina189" name="belimina189" type="button" value="Eliminar" class="btMiniEliminar" onclick="eliminaf189()" title="<?php echo $ETI['bt_mini_eliminar_189']; ?>" style="display:<?php if ((int)$_REQUEST['unad89id']!=0){echo 'block';} else {echo 'none';} ?>;"/>
</label>
<?php
//Este es el cierre del div_p189
?>
<div class="salto1px"></div>
</div>
<?php
		//} //Termina el segundo bloque  condicional - bloque editar.
?>
<div class="salto1px"></div>
<div id="div_f189detalle">
<?php
echo $sTabla189;
?>
</div>
<?php
	}
?>
<div class="salto1px"></div>
</div>
<?php
// -- Termina Grupo campos 189 SMTPS para login
?>
<?php
if (false) {
	//Ejemplo de boton de ayuda
	//echo html_BotonAyuda('NombreCampo');
	//echo html_DivAyudaLocal('NombreCampo');
}
//Mostrar el contenido de la tabla
?>
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
<input id="titulo_188" name="titulo_188" type="hidden" value="<?php echo $sTituloModulo; ?>" />
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
if ($bBloqueTitulo) {
	if ($bPuedeGuardar) {
?>
<div class="flotante">
<?php
echo $objForma->htmlBotonSolo('cmdGuardarf', 'btSoloGuardar', 'enviaguardar()', $ETI['bt_guardar']);
?>
</div>
<?php
	}
}
?>
<?php
echo html_DivAlarmaV2($sError, $iTipoError);
//El script que cambia el sector que se muestra
?>

<script language="javascript">
<?php
if ($iSector != 1) {
	echo 'setTimeout(function() {
		expandesector(' . $iSector . ');
	}, 10);
';
}
if ($bMueveScroll) {
	echo 'setTimeout(function() {
		retornacontrol();
	}, 2);
';
}
?>
</script>
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>js/jquery.autocomplete.css" type="text/css" />
<script language="javascript" src="<?php echo $APP->rutacomun; ?>js/jquery.autocomplete.js"></script>
<script language="javascript" src="ac_188.js"></script>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>unad_todas2024.js?ver=2"></script>
<?php
forma_piedepagina();