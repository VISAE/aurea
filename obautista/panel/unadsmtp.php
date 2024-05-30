<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2016 - 2021 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.12.1 viernes, 22 de enero de 2016
--- Modelo Versión 2.27.5 martes, 14 de diciembre de 2021
*/
/** Archivo unadsmtp.php.
* Modulo 169 unad69smtp.
* @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
* @param debug=1 (Opcional), bandera para indicar si se generan datos de depuración
* @date martes, 14 de diciembre de 2021
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
$iCodModulo = 169;
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
$mensajes_169='lg/lg_169_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_169)){$mensajes_169='lg/lg_169_es.php';}
require $mensajes_todas;
require $mensajes_169;
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
		header('Location:noticia.php?ret=unadsmtp.php');
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
// -- 169 unad69smtp
require 'lib169.php';
$xajax = new xajax();
$xajax->configure('javascript URI', $APP->rutacomun . 'xajax/');
$xajax->register(XAJAX_FUNCTION, 'sesion_abandona_V2');
$xajax->register(XAJAX_FUNCTION, 'sesion_mantenerV4');
$xajax->register(XAJAX_FUNCTION, 'f169_HtmlTabla');
$xajax->register(XAJAX_FUNCTION, 'f169_ExisteDato');
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
if (isset($_REQUEST['paginaf169'])==0){$_REQUEST['paginaf169']=1;}
if (isset($_REQUEST['lppf169'])==0){$_REQUEST['lppf169']=20;}
if (isset($_REQUEST['boculta169'])==0){$_REQUEST['boculta169']=0;}
// -- Inicializar variables de datos.
if (isset($_REQUEST['unad69consec'])==0){$_REQUEST['unad69consec']='';}
if (isset($_REQUEST['unad69id'])==0){$_REQUEST['unad69id']='';}
if (isset($_REQUEST['unad69vigente'])==0){$_REQUEST['unad69vigente']='S';}
if (isset($_REQUEST['unad69modelo'])==0){$_REQUEST['unad69modelo']='SMTP';}
if (isset($_REQUEST['unad69titulo'])==0){$_REQUEST['unad69titulo']='';}
if (isset($_REQUEST['unad69servidorsmtp'])==0){$_REQUEST['unad69servidorsmtp']='';}
if (isset($_REQUEST['unad69puertomail'])==0){$_REQUEST['unad69puertomail']='';}
if (isset($_REQUEST['unad69autenticacion'])==0){$_REQUEST['unad69autenticacion']='SSL';}
if (isset($_REQUEST['unad69usuariomail'])==0){$_REQUEST['unad69usuariomail']='';}
if (isset($_REQUEST['unad69pwdmail'])==0){$_REQUEST['unad69pwdmail']='';}
if (isset($_REQUEST['unad69topehora'])==0){$_REQUEST['unad69topehora']='';}
if (isset($_REQUEST['unad69topedia'])==0){$_REQUEST['unad69topedia']='';}
if (isset($_REQUEST['unad69confirmado'])==0){$_REQUEST['unad69confirmado']='N';}
// Espacio para inicializar otras variables
if (isset($_REQUEST['csv_separa'])==0){$_REQUEST['csv_separa']=';';}
if (isset($_REQUEST['bnombre'])==0){$_REQUEST['bnombre']='';}
/*
if (isset($_REQUEST['blistar']) == 0) {
	$_REQUEST['blistar'] = '';
}
*/
//Si Modifica o Elimina Cargar los campos
if (($_REQUEST['paso']==1)||($_REQUEST['paso']==3)){
	if ($_REQUEST['paso'] == 1) {
		$sSQLcondi='unad69consec='.$_REQUEST['unad69consec'].'';
		} else {
		$sSQLcondi='unad69id='.$_REQUEST['unad69id'].'';
		}
	$sSQL='SELECT * FROM unad69smtp WHERE '.$sSQLcondi;
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$_REQUEST['unad69consec']=$fila['unad69consec'];
		$_REQUEST['unad69id']=$fila['unad69id'];
		$_REQUEST['unad69vigente']=$fila['unad69vigente'];
		$_REQUEST['unad69modelo']=$fila['unad69modelo'];
		$_REQUEST['unad69titulo']=$fila['unad69titulo'];
		$_REQUEST['unad69servidorsmtp']=$fila['unad69servidorsmtp'];
		$_REQUEST['unad69puertomail']=$fila['unad69puertomail'];
		$_REQUEST['unad69autenticacion']=$fila['unad69autenticacion'];
		$_REQUEST['unad69usuariomail']=$fila['unad69usuariomail'];
		$_REQUEST['unad69pwdmail']=$fila['unad69pwdmail'];
		$_REQUEST['unad69topehora']=$fila['unad69topehora'];
		$_REQUEST['unad69topedia']=$fila['unad69topedia'];
		$_REQUEST['unad69confirmado']=$fila['unad69confirmado'];
		$bcargo=true;
		$_REQUEST['paso']=2;
		$_REQUEST['boculta169']=0;
		$bLimpiaHijos=true;
		} else {
		$_REQUEST['paso']=0;
		}
	}
$bConfirma=false;
//Guardamos antes de confirmar
if ($_REQUEST['paso']==21){
	$_REQUEST['paso']=12;
	$_REQUEST['unad69confirmado']='N';
	$sWhere='unad69id='.$_REQUEST['unad69id'].'';
	$sSQL='UPDATE unad69smtp SET unad69confirmado="N" WHERE '.$sWhere.';';
	$result=$objDB->ejecutasql($sSQL);
	$bConfirma=true;
	}
//Insertar o modificar un elemento
if (($_REQUEST['paso'] == 10) || ($_REQUEST['paso'] == 12)) {
	$bMueveScroll = true;
	list($_REQUEST, $sError, $iTipoError, $sDebugGuardar)=f169_db_GuardarV2($_REQUEST, $objDB, $bDebug, $idTercero);
	$sDebug=$sDebug.$sDebugGuardar;
	if ($sError==''){
		$sError='<b>'.$ETI['msg_itemguardado'].'</b>';
		$iTipoError=1;
		}
	}
//Confirmar que se puedan enviar correos.
if ($bConfirma){
	require $APP->rutacomun . 'PHPmailer/class.phpmailer.php';
	require $APP->rutacomun . 'PHPmailer/class.smtp.php';
	$mail=new PHPMailer();
	$mail->IsSMTP();
	if ($_REQUEST['unad69autenticacion']!=''){
		$mail->SMTPAuth=true;
		$mail->SMTPSecure=strtolower($_REQUEST['unad69autenticacion']);
		} else {
		$mail->SMTPAuth=false;
		}
	$mail->Port=$_REQUEST['unad69puertomail'];
	$mail->Username=$_REQUEST['unad69usuariomail']; // Cuenta de e-mail
	$mail->Password=$_REQUEST['unad69pwdmail']; // Password
	$mail->Host=$_REQUEST['unad69servidorsmtp'];
	$mail->From=$_REQUEST['unad69usuariomail'];
	$mail->FromName=$_REQUEST['unad69titulo'];
	$mail->Subject=utf8_decode('Test de verificación de correo');
	$mail->AddAddress('soporte.campus@unad.edu.co','Soporte UNAD');
	$mail->WordWrap = 50;
	$body='Esta es una Prueba de Correo desde Plataforma Aurea.';
	$mail->Body = $body;
	if(!$mail->Send()){
		$sError='No fue posible verificar la cuenta. {'.$mail->ErrorInfo.'}';
		//Puede perder la conexion a la base de datos porque pasa mucho tiempo.
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		} else {
		$sWhere='unad69id='.$_REQUEST['unad69id'].'';
		$sSQL='UPDATE unad69smtp SET unad69confirmado="S" WHERE '.$sWhere.';';
		$result=$objDB->ejecutasql($sSQL);
		$_REQUEST['unad69confirmado']='S';
		}
	}
//Eliminar un elemento
if ($_REQUEST['paso'] == 13) {
	$_REQUEST['paso'] = 2;
	list($sError, $iTipoError, $sDebugElimina)=f169_db_Eliminar($_REQUEST['unad69id'], $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugElimina;
	if ($sError==''){
		$_REQUEST['paso']=-1;
		$sError=$ETI['msg_itemeliminado'];
		$iTipoError=1;
		}
	}
//limpiar la pantalla
if ($_REQUEST['paso']==-1){
	$_REQUEST['unad69consec']='';
	$_REQUEST['unad69id']='';
	$_REQUEST['unad69vigente']='S';
	$_REQUEST['unad69modelo']='SMTP';
	$_REQUEST['unad69titulo']='';
	$_REQUEST['unad69servidorsmtp']='';
	$_REQUEST['unad69puertomail']='';
	$_REQUEST['unad69autenticacion']='SSL';
	$_REQUEST['unad69usuariomail']='';
	$_REQUEST['unad69pwdmail']='';
	$_REQUEST['unad69topehora']='';
	$_REQUEST['unad69topedia']='';
	$_REQUEST['unad69confirmado']='N';
	$_REQUEST['paso']=0;
	}
if ($bLimpiaHijos){
	}
//AQUI SE DEBEN CARGAR TODOS LOS DATOS QUE LA FORMA NECESITE.
//DATOS PARA COMPLETAR EL FORMULARIO
//Permisos adicionales
$seg_5=0;
$seg_6=0;
list($bDevuelve, $sDebugP)=seg_revisa_permisoV3($iCodModulo, 6, $idTercero, $objDB);
if ($bDevuelve){$seg_6=1;}
//if ($seg_6==1){}
//Crear los controles que requieran llamado a base de datos
$objCombos=new clsHtmlCombos();
$html_unad69vigente=html_sino('unad69vigente', $_REQUEST['unad69vigente']);
$html_unad69modelo=html_combo('unad69modelo', 'unad22codopcion', 'unad22nombre', 'unad22combos', 'unad22idmodulo=169 AND unad22consec=1 AND unad22activa="S"', 'unad22orden', $_REQUEST['unad69modelo'], $objDB, '', false, '{'.$ETI['msg_seleccione'].'}', '');
$html_unad69autenticacion=html_combo('unad69autenticacion', 'unad22codopcion', 'unad22nombre', 'unad22combos', 'unad22idmodulo=169 AND unad22consec=2 AND unad22activa="S"', 'unad22orden', $_REQUEST['unad69autenticacion'], $objDB, '', true, '{'.$ETI['msg_ninguna'].'}', '');
$unad69confirmado_nombre=$ETI['no'];
if ($_REQUEST['unad69confirmado']=='S'){$unad69confirmado_nombre=$ETI['si'];}
$html_unad69confirmado=html_oculto('unad69confirmado', $_REQUEST['unad69confirmado'], $unad69confirmado_nombre);
if ((int)$_REQUEST['paso']==0){
	} else {
	}
//Alistar datos adicionales
$id_rpt=0;
//$id_rpt=reportes_id(_Identificador_Tipo_Reporte_, $objDB);
/*
$objCombos->nuevo('blistar', $_REQUEST['blistar'], true, '{'.$ETI['msg_todos'].'}');
$objCombos->sAccion='paginarf169()';
$html_blistar=$objCombos->html('', $objDB);
//$html_blistar=$objCombos->comboSistema(169, 1, $objDB, 'paginarf169()');
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
$iModeloReporte=169;
$html_iFormatoImprime = '<input id="iformatoimprime" name="iformatoimprime" type="hidden" value="0" />
';
if ($_REQUEST['paso']>0){
	$bDevuelve=false;
	//list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 5, $idTercero, $objDB);
	if ($bDevuelve){
		$seg_5=1;
		}
	$bDevuelve=false;
	//list($bDevuelve, $sDebugP)=seg_revisa_permisoV3($iCodModulo, 8, $idTercero, $objDB);
	if ($bDevuelve){$seg_8=1;}
	}
//Cargar las tablas de datos
$aParametros[0]='';//$_REQUEST['p1_169'];
$aParametros[100]=$idTercero;
$aParametros[101]=$_REQUEST['paginaf169'];
$aParametros[102]=$_REQUEST['lppf169'];
//$aParametros[103]=$_REQUEST['bnombre'];
//$aParametros[104]=$_REQUEST['blistar'];
list($sTabla169, $sDebugTabla)=f169_TablaDetalleV2($aParametros, $objDB, $bDebug);
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
$sTituloModulo = $ETI['titulo_169'];
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
	document.getElementById('div_sector98').style.display='none';
	document.getElementById('div_sector'+codigo).style.display='block';
	var sEst='none';
	if (codigo==1){sEst='block';}
	document.getElementById('cmdGuardarf').style.display=sEst;
	}
function imprimelista(){
	if (window.document.frmedita.seg_6.value==1){
		window.document.frmlista.consulta.value=window.document.frmedita.consulta_169.value;
		window.document.frmlista.titulos.value=window.document.frmedita.titulos_169.value;
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
			window.document.frmimpp.action = 'e169.php';
			window.document.frmimpp.submit();
		} else {
			ModalMensaje(sError);
		}
	}

	function imprimep() {
		if (window.document.frmedita.seg_5.value == 1) {
			asignarvariables();
			window.document.frmimpp.action = 'p169.php';
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
		datos[1] = window.document.frmedita.unad69consec.value;
	if ((datos[1]!='')){
		xajax_f169_ExisteDato(datos);
		}
	}

	function cargadato(llave1){
	window.document.frmedita.unad69consec.value=String(llave1);
	window.document.frmedita.paso.value=1;
	window.document.frmedita.submit();
	}
function cargaridf169(llave1){
	window.document.frmedita.unad69id.value=String(llave1);
	window.document.frmedita.paso.value=3;
	window.document.frmedita.submit();
	}
function paginarf169(){
	let params=new Array();
	params[99]=window.document.frmedita.debug.value;
	params[100]=<?php echo $idTercero; ?>;
	params[101]=window.document.frmedita.paginaf169.value;
	params[102]=window.document.frmedita.lppf169.value;
	//params[103]=window.document.frmedita.bnombre.value;
	//params[104]=window.document.frmedita.blistar.value;
	xajax_f169_HtmlTabla(params);
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
		document.getElementById("unad69consec").focus();
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
function mod_consec() {
    ModalConfirmV2('<?php echo $ETI['msg_confirmamodconsec']; ?>', () => {
        ejecuta_modconsec();
    });
}

function ejecuta_modconsec() {
    MensajeAlarmaV2('<?php echo $ETI['msg_ejecutando']; ?>', 2);
    expandesector(98);
    window.document.frmedita.paso.value = 93;
    window.document.frmedita.submit();
}
function confirmar(){
	expandesector(98);
	window.document.frmedita.paso.value=21;
	window.document.frmedita.submit();
	}
</script>
<form id="frmimpp" name="frmimpp" method="post" action="p169.php" target="_blank" style="display:none">
<input id="r" name="r" type="hidden" value="169" />
<input id="id169" name="id169" type="hidden" value="<?php echo $_REQUEST['unad69id']; ?>" />
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
//Div para ocultar
$bConExpande = true;
if ($bConExpande) {
?>
<div class="ir_derecha"<?php echo $sAnchoExpandeContrae; ?>>
<input id="boculta169" name="boculta169" type="hidden" value="<?php echo $_REQUEST['boculta169']; ?>" />
<label class="Label30">
<input id="btexpande169" name="btexpande169" type="button" value="Mostrar" class="btMiniExpandir" onclick="expandepanel(169,'block',0);" title="<?php echo $ETI['bt_mostrar']; ?>" style="display:<?php if ($_REQUEST['boculta169']==0){echo 'none'; } else {echo 'block';} ?>;"/>
</label>
<label class="Label30">
<input id="btrecoge169" name="btrecoge169" type="button" value="Ocultar" class="btMiniRecoger" onclick="expandepanel(169,'none',1);" title="<?php echo $ETI['bt_ocultar']; ?>" style="display:<?php if ($_REQUEST['boculta169']==0){echo 'block'; } else {echo 'none';} ?>;"/>
</label>
</div>
<div id="div_p169" style="display:<?php if ($_REQUEST['boculta169']==0){echo 'block'; } else {echo 'none';} ?>;">
<?php
}
//Mostrar formulario para editar
?>
<label class="Label90">
<?php
echo $ETI['unad69consec'];
?>
</label>
<label class="Label90">
<?php
if ($_REQUEST['paso']!=2){
?>
<input id="unad69consec" name="unad69consec" type="text" value="<?php echo $_REQUEST['unad69consec']; ?>" onchange="RevisaLlave()" class="cuatro"/>
<?php
	} else {
	echo html_oculto('unad69consec', $_REQUEST['unad69consec']);
	}
?>
</label>
<label class="Label60">
<?php
echo $ETI['unad69id'];
?>
</label>
<label class="Label60">
<?php
	echo html_oculto('unad69id', $_REQUEST['unad69id']);
?>
</label>
<label class="Label60">
<?php
echo $ETI['unad69vigente'];
?>
</label>
<label class="Label60">
<?php
echo $html_unad69vigente;
?>
</label>
<label class="Label60">
<?php
echo $ETI['unad69modelo'];
?>
</label>
<label class="Label130">
<?php
echo $html_unad69modelo;
?>
</label>
<label class="L">
<?php
echo $ETI['unad69titulo'];
?>

<input id="unad69titulo" name="unad69titulo" type="text" value="<?php echo $_REQUEST['unad69titulo']; ?>" maxlength="100" class="L" placeholder="<?php echo $ETI['ing_campo'].$ETI['unad69titulo']; ?>"/>
</label>
<label class="Label130">
<?php
echo $ETI['unad69servidorsmtp'];
?>
</label>
<label>
<input id="unad69servidorsmtp" name="unad69servidorsmtp" type="text" value="<?php echo $_REQUEST['unad69servidorsmtp']; ?>" maxlength="50" placeholder="<?php echo $ETI['ing_campo'].$ETI['unad69servidorsmtp']; ?>"/>
</label>
<label class="Label60">
<?php
echo $ETI['unad69puertomail'];
?>
</label>
<label class="Label130">
<input id="unad69puertomail" name="unad69puertomail" type="text" value="<?php echo $_REQUEST['unad69puertomail']; ?>" maxlength="4" placeholder="465" class="cuatro"/>
</label>
<label class="Label90">
<?php
echo $ETI['unad69autenticacion'];
?>
</label>
<label class="Label130">
<?php
echo $html_unad69autenticacion;
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['unad69usuariomail'];
?>
</label>
<label>
<input id="unad69usuariomail" name="unad69usuariomail" type="text" value="<?php echo $_REQUEST['unad69usuariomail']; ?>" maxlength="50" placeholder="<?php echo $ETI['ing_campo'].$ETI['unad69usuariomail']; ?>"/>
</label>
<label class="Label130">
<?php
echo $ETI['unad69pwdmail'];
?>
</label>
<label>
<input id="unad69pwdmail" name="unad69pwdmail" type="password" value="<?php echo $_REQUEST['unad69pwdmail']; ?>" maxlength="50" placeholder="Clave"/>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['unad69topehora'];
?>
</label>
<label class="Label130">
<input id="unad69topehora" name="unad69topehora" type="text" value="<?php echo $_REQUEST['unad69topehora']; ?>" class="cuatro" maxlength="10" placeholder="0"/>
</label>
<label class="Label90">
<?php
echo $ETI['unad69topedia'];
?>
</label>
<label class="Label130">
<input id="unad69topedia" name="unad69topedia" type="text" value="<?php echo $_REQUEST['unad69topedia']; ?>" class="cuatro" maxlength="10" placeholder="0"/>
</label>
<label class="Label130">
<?php
echo $ETI['unad69confirmado'];
?>
</label>
<label class="Label60">
<div id="div_unad69confirmado">
<?php
echo $html_unad69confirmado;
?>
</div>
</label>
<?php
if ($_REQUEST['paso']>0){
?>
<label class="Label130">
<input id="cmdConfirmar" name="cmdConfirmar" type="button" class="BotonAzul" value="Confirmar" title="Confirmar" onclick="confirmar();" />
</label>
<?php
	}
if (false) {
	//Ejemplo de boton de ayuda
	//echo html_BotonAyuda('NombreCampo');
	//echo html_DivAyudaLocal('NombreCampo');
}
if ($bConExpande) {
	//Este es el cierre del div_p169
?>
<div class="salto1px"></div>
</div>
<?php
}
//Mostrar el contenido de la tabla
// CIERRA EL DIV areatrabajo
echo $objForma->htmlFinMarco();
?>
<?php
echo $objForma->htmlInicioMarco($ETI['bloque1']);
?>
<?php
echo ' ' . $csv_separa;
?>
<div class="salto1px"></div>
<div id="div_f169detalle">
<?php
echo $sTabla169;
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


<div id="div_sector93" style="display:none">
<?php
$objForma=new clsHtmlForma($iPiel);
if ($bBloqueTitulo) {
	$objForma->addBoton('cmdAyuda93', 'btSupAyuda', 'muestraayuda(' . $iCodModulo . ');', $ETI['bt_ayuda']);
	$objForma->addBoton('cmdVolverSec93', 'btSupVolver', 'retornacontrol();', $ETI['bt_volver']);
	echo $objForma->htmlTitulo($sTituloModulo, $iCodModulo);
	echo $objForma->htmlInicioMarco();
} else {
	echo $objForma->htmlInicioMarco('', 'div_93titulo');
}
?>
<label class="Label160">
<?php
echo $ETI['msg_unad69consec'];
?>
</label>
<label class="Label90">
<?php
echo '<b>'.$_REQUEST['unad69consec'].'</b>';
?>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['msg_unad69consec_nuevo'];
// onchange="RevisaConsec()"
?>
</label>
<label class="Label90">
<input id="unad69consec_nuevo" name="unad69consec_nuevo" type="text" value="<?php echo $_REQUEST['unad69consec_nuevo']; ?>" class="cuatro"/>
</label>
<div class="salto1px"></div>
<label class="Label160">&nbsp;</label>
<?php
echo $objForma->htmlBotonSolo('cmdCambiaConsecFinal', 'botonProceso', 'mod_consec();', $ETI['bt_cambiar'], 130);
echo $objForma->htmlFinMarco();
?>
</div>


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
<input id="titulo_169" name="titulo_169" type="hidden" value="<?php echo $sTituloModulo; ?>" />
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
<script language="javascript" src="<?php echo $APP->rutacomun; ?>js/chosen.jquery.js"></script>
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>js/chosen.css" type="text/css"/>
<?php
if ($_REQUEST['paso']==0){
?>
<script language="javascript">
$().ready(function(){
//$("#bperiodo").chosen();
});
</script>
<?php
	}
?>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>unad_todas2024.js?ver=2"></script>
<?php
forma_piedepagina();