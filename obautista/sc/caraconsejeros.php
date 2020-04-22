<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2018 - 2019 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.22.3 lunes, 6 de agosto de 2018
*/
/** Archivo caraconsejeros.php.
* Modulo 2313 cara13consejeros.
* @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
* @param debug=1 (Opcional), bandera para indicar si se generan datos de depuración
* @date Tuesday, October 1, 2019
*/
if (file_exists('./err_control.php')){require './err_control.php';}
$bDebug=false;
$sDebug='';
if (isset($_REQUEST['deb_doc'])!=0){
	$bDebug=true;
	}
if (isset($_REQUEST['debug'])!=0){
	if ($_REQUEST['debug']==1){$bDebug=true;}
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
mb_internal_encoding('UTF-8');
require './app.php';
require $APP->rutacomun.'unad_sesion.php';
if (isset($APP->https)==0){$APP->https=0;}
if ($APP->https==2){
	$bObliga=false;
	if (isset($_SERVER['HTTPS'])==0){
		$bObliga=true;
		}else{
		if ($_SERVER['HTTPS']!='on'){$bObliga=true;}
		}
	if ($bObliga){
		$pageURL='https://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
		header('Location:'.$pageURL);
		die();
		}
	}
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
require $APP->rutacomun.'libdatos.php';
if (($bPeticionXAJAX)&&($_SESSION['unad_id_tercero']==0)){
	// viene por xajax.
	$xajax=new xajax();
	$xajax->configure('javascript URI', $APP->rutacomun.'xajax/');
	$xajax->register(XAJAX_FUNCTION,'sesion_abandona_V2');
	$xajax->processRequest();
	die();
	}
$grupo_id=1;//Necesita ajustarlo...
$iCodModulo=2313;
$audita[1]=false;
$audita[2]=true;
$audita[3]=true;
$audita[4]=true;
$audita[5]=false;
// -- Se cargan los archivos de idioma
$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
$mensajes_2313='lg/lg_2313_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_2313)){$mensajes_2313='lg/lg_2313_es.php';}
require $mensajes_todas;
require $mensajes_2313;
$xajax=NULL;
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
if (!seg_revisa_permiso($iCodModulo, 1, $objDB)){
	header('Location:nopermiso.php');
	die();
	}
if (!$bPeticionXAJAX){
	if (noticias_pendientes($objDB)){
		$objDB->CerrarConexion();
		header('Location:noticia.php?ret=caraconsejeros.php');
		die();
		}
	}
$idTercero=$_SESSION['unad_id_tercero'];
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
	$bDebug=false;
	}else{
	$_REQUEST['deb_doc']='';
	}
if (isset($_REQUEST['debug'])!=0){
	if ($_REQUEST['debug']==1){$bDebug=true;}
	}else{
	$_REQUEST['debug']=0;
	}
//PROCESOS DE LA PAGINA
$idEntidad=0;
if (isset($APP->entidad)!=0){
	if ($APP->entidad==1){$idEntidad=1;}
	}
$mensajes_2301=$APP->rutacomun.'lg/lg_2301_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_2301)){$mensajes_2301=$APP->rutacomun.'lg/lg_2301_es.php';}
require $mensajes_2301;
// -- Si esta cargando la pagina por primer vez se revisa si requiere auditar y se manda a hacer un limpiar (paso -1)
if (isset($_REQUEST['paso'])==0){
	$_REQUEST['paso']=-1;
	if ($audita[1]){seg_auditaingreso($iCodModulo, $_SESSION['unad_id_tercero'], $objDB);}
	}
// -- 2313 cara13consejeros
require 'lib2313.php';
// -- 2301 Encuesta
require $APP->rutacomun.'lib2301.php';
$xajax=new xajax();
$xajax->configure('javascript URI', $APP->rutacomun.'xajax/');
$xajax->register(XAJAX_FUNCTION,'unad11_Mostrar_v2');
$xajax->register(XAJAX_FUNCTION,'unad11_TraerXid');
$xajax->register(XAJAX_FUNCTION,'formatear_moneda');
$xajax->register(XAJAX_FUNCTION,'f2313_Combocara01idcead');
$xajax->register(XAJAX_FUNCTION,'sesion_abandona_V2');
$xajax->register(XAJAX_FUNCTION,'sesion_mantenerV4');
$xajax->register(XAJAX_FUNCTION,'f2313_HtmlTabla');
$xajax->register(XAJAX_FUNCTION,'f2313_ExisteDato');
$xajax->register(XAJAX_FUNCTION,'f2313_Busquedas');
$xajax->register(XAJAX_FUNCTION,'f2313_HtmlBusqueda');
$xajax->register(XAJAX_FUNCTION,'f2301_HtmlTablaConsejero');
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
// -- Se inicializan las variables, primero las que controlan la visualización de la página.
if (isset($_REQUEST['iscroll'])==0){$_REQUEST['iscroll']=0;}
if (isset($_REQUEST['paginaf2313'])==0){$_REQUEST['paginaf2313']=1;}
if (isset($_REQUEST['lppf2313'])==0){$_REQUEST['lppf2313']=20;}
if (isset($_REQUEST['boculta2313'])==0){$_REQUEST['boculta2313']=0;}
if (isset($_REQUEST['paginaf2301'])==0){$_REQUEST['paginaf2301']=1;}
if (isset($_REQUEST['lppf2301'])==0){$_REQUEST['lppf2301']=20;}
if (isset($_REQUEST['boculta2301'])==0){$_REQUEST['boculta2301']=0;}
// -- Inicializar variables de datos.
if (isset($_REQUEST['cara13peraca'])==0){$_REQUEST['cara13peraca']='';}
if (isset($_REQUEST['cara13idconsejero'])==0){$_REQUEST['cara13idconsejero']=0;}// {$_SESSION['unad_id_tercero'];}
if (isset($_REQUEST['cara13idconsejero_td'])==0){$_REQUEST['cara13idconsejero_td']=$APP->tipo_doc;}
if (isset($_REQUEST['cara13idconsejero_doc'])==0){$_REQUEST['cara13idconsejero_doc']='';}
if (isset($_REQUEST['cara13id'])==0){$_REQUEST['cara13id']='';}
if (isset($_REQUEST['cara13activo'])==0){$_REQUEST['cara13activo']='S';}
if (isset($_REQUEST['cara01idzona'])==0){$_REQUEST['cara01idzona']='';}
if (isset($_REQUEST['cara01idcead'])==0){$_REQUEST['cara01idcead']='';}
if (isset($_REQUEST['cara01cargaasignada'])==0){$_REQUEST['cara01cargaasignada']='';}
if (isset($_REQUEST['cara01cargafinal'])==0){$_REQUEST['cara01cargafinal']='';}
// Espacio para inicializar otras variables
if (isset($_REQUEST['csv_separa'])==0){$_REQUEST['csv_separa']=',';}
if (isset($_REQUEST['bnombre'])==0){$_REQUEST['bnombre']='';}
if (isset($_REQUEST['blistar'])==0){$_REQUEST['blistar']='';}
if (isset($_REQUEST['bzona'])==0){$_REQUEST['bzona']='';}
//Si Modifica o Elimina Cargar los campos
if (($_REQUEST['paso']==1)||($_REQUEST['paso']==3)){
	$_REQUEST['cara13idconsejero_td']=$APP->tipo_doc;
	$_REQUEST['cara13idconsejero_doc']='';
	if ($_REQUEST['paso']==1){
		$sSQLcondi='cara13peraca='.$_REQUEST['cara13peraca'].' AND cara13idconsejero="'.$_REQUEST['cara13idconsejero'].'"';
		}else{
		$sSQLcondi='cara13id='.$_REQUEST['cara13id'].'';
		}
	$sSQL='SELECT * FROM cara13consejeros WHERE '.$sSQLcondi;
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$_REQUEST['cara13peraca']=$fila['cara13peraca'];
		$_REQUEST['cara13idconsejero']=$fila['cara13idconsejero'];
		$_REQUEST['cara13id']=$fila['cara13id'];
		$_REQUEST['cara13activo']=$fila['cara13activo'];
		$_REQUEST['cara01idzona']=$fila['cara01idzona'];
		$_REQUEST['cara01idcead']=$fila['cara01idcead'];
		$_REQUEST['cara01cargaasignada']=$fila['cara01cargaasignada'];
		$_REQUEST['cara01cargafinal']=$fila['cara01cargafinal'];
		$bcargo=true;
		$_REQUEST['paso']=2;
		$_REQUEST['boculta2313']=0;
		$bLimpiaHijos=true;
		}else{
		$_REQUEST['paso']=0;
		}
	}
//Insertar o modificar un elemento
if (($_REQUEST['paso']==10)||($_REQUEST['paso']==12)){
	$bMueveScroll=true;
	list($_REQUEST, $sError, $iTipoError, $sDebugGuardar)=f2313_db_GuardarV2($_REQUEST, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugGuardar;
	if ($sError==''){
		$sError='<b>'.$ETI['msg_itemguardado'].'</b>';
		$iTipoError=1;
		}
	}
//Eliminar un elemento
if ($_REQUEST['paso']==13){
	$_REQUEST['paso']=2;
	list($sError, $iTipoError, $sDebugElimina)=f2313_db_Eliminar($_REQUEST['cara13id'], $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugElimina;
	if ($sError==''){
		$_REQUEST['paso']=-1;
		$sError=$ETI['msg_itemeliminado'];
		$iTipoError=1;
		}
	}
if ($_REQUEST['paso']==21){
	$_REQUEST['paso']=-1;
	list($sError, $sDebugU)=f2313_ActualizarCarga($_REQUEST['blistar'], $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugU;
	}
if (($_REQUEST['paso']==50)){
	$_REQUEST['paso']=2;
	if (!seg_revisa_permiso($iCodModulo, 2, $objDB)){
		$sError=$ERR['2'];
		}
	if ($sError==''){
		list($sError, $iTipoError, $sInfoProceso, $sDebugP)=f2313_ProcesarArchivo($_REQUEST, $_FILES, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugP;
		if ($sInfoProceso!=''){
			$sError=$sError.'<br>'.$sInfoProceso;
			}
		}
	}
//limpiar la pantalla
if ($_REQUEST['paso']==-1){
	$_REQUEST['cara13peraca']='';
	$_REQUEST['cara13idconsejero']=0;//$_SESSION['unad_id_tercero'];
	$_REQUEST['cara13idconsejero_td']=$APP->tipo_doc;
	$_REQUEST['cara13idconsejero_doc']='';
	$_REQUEST['cara13id']='';
	$_REQUEST['cara13activo']='S';
	$_REQUEST['cara01idzona']='';
	$_REQUEST['cara01idcead']='';
	$_REQUEST['cara01cargaasignada']='';
	$_REQUEST['cara01cargafinal']='';
	$_REQUEST['paso']=0;
	}
if ($bLimpiaHijos){
	}
//AQUI SE DEBEN CARGAR TODOS LOS DATOS QUE LA FORMA NECESITE.
//DATOS PARA COMPLETAR EL FORMULARIO
//Crear los controles que requieran llamado a base de datos
$seg_1710=0;
list($bPermiso, $sDebugP)=seg_revisa_permisoV3($iCodModulo, 1710, $idTercero, $objDB, $bDebug);
if ($bPermiso){$seg_1710=1;}
$sCondiZona=' WHERE unad23conestudiantes="S"';
$bVacioZona=true;
if ($seg_1710==0){
	$bVacioZona=false;
	list($sIdZona, $idPrimera, $sDebugZ)=f2300_ZonasTercero($idTercero, $objDB, $bDebug);
	if ($_REQUEST['bzona']==''){$_REQUEST['bzona']=$idPrimera;}
	if ($_REQUEST['cara01idzona']==''){$_REQUEST['cara01idzona']=$idPrimera;}
	$sCondiZona=' WHERE unad23id IN ('.$sIdZona.') AND unad23conestudiantes="S"';
	}
$objCombos=new clsHtmlCombos('n');
list($cara13idconsejero_rs, $_REQUEST['cara13idconsejero'], $_REQUEST['cara13idconsejero_td'], $_REQUEST['cara13idconsejero_doc'])=html_tercero($_REQUEST['cara13idconsejero_td'], $_REQUEST['cara13idconsejero_doc'], $_REQUEST['cara13idconsejero'], 0, $objDB);

$objCombos->nuevo('cara01idzona', $_REQUEST['cara01idzona'], true, '{'.$ETI['msg_seleccione'].'}');
$objCombos->sAccion='carga_combo_cara01idcead();';
$sSQL='SELECT unad23id AS id, unad23nombre AS nombre FROM unad23zona '.$sCondiZona.' ORDER BY unad23nombre';
$html_cara01idzona=$objCombos->html($sSQL, $objDB);

$objCombos->nuevo('cara13activo', $_REQUEST['cara13activo'], false);
$objCombos->sino();
$html_cara13activo=$objCombos->html('', $objDB);
$html_cara01idcead=f2313_HTMLComboV2_cara01idcead($objDB, $objCombos, $_REQUEST['cara01idcead'], $_REQUEST['cara01idzona']);
if ((int)$_REQUEST['paso']==0){
	$html_cara13peraca=f2313_HTMLComboV2_cara13peraca($objDB, $objCombos, $_REQUEST['cara13peraca']);
	}else{
	list($cara13peraca_nombre, $sErrorDet)=tabla_campoxid('exte02per_aca','exte02nombre','exte02id',$_REQUEST['cara13peraca'],'{'.$ETI['msg_sindato'].'}', $objDB);
	$html_cara13peraca=html_oculto('cara13peraca', $_REQUEST['cara13peraca'], $cara13peraca_nombre);
	//list($cara01idzona_nombre, $sErrorDet)=tabla_campoxid('unad23zona','unad23nombre','unad23id',$_REQUEST['cara01idzona'],'{'.$ETI['msg_sindato'].'}', $objDB);
	//$html_cara01idzona=html_oculto('cara01idzona', $_REQUEST['cara01idzona'], $cara01idzona_nombre);
	}
//Alistar datos adicionales
$id_rpt=0;
//$id_rpt=reportes_id(_Identificador_Tipo_Reporte_, $objDB);

$objCombos->nuevo('blistar', $_REQUEST['blistar'], true, '{'.$ETI['msg_todos'].'}');
$objCombos->sAccion='paginarf2313()';
$sSQL=f146_ConsultaCombo(2216, $objDB);
$html_blistar=$objCombos->html($sSQL, $objDB);
$objCombos->nuevo('bzona', $_REQUEST['bzona'], $bVacioZona, '{'.$ETI['msg_todas'].'}');
if ($bVacioZona){
	$objCombos->addItem(0, '{Sin zona}');
	}
$objCombos->sAccion='paginarf2313()';
$sSQL='SELECT unad23id AS id, unad23nombre AS nombre FROM unad23zona '.$sCondiZona.' ORDER BY unad23nombre';
$html_bzona=$objCombos->html($sSQL, $objDB);
//$html_blistar=$objCombos->comboSistema(2313, 1, $objDB, 'paginarf2313()');

//Permisos adicionales
$seg_5=0;
$seg_6=0;
if (seg_revisa_permiso($iCodModulo, 6, $objDB)){$seg_6=1;}
if ($seg_6==1){}
if (false){
	$objCombos->nuevo('csv_separa', $_REQUEST['csv_separa'], false);
	$objCombos->addItem(',', $ETI['msg_coma']);
	$objCombos->addItem(';', $ETI['msg_puntoycoma']);
	$csv_separa='<label class="Label90">'.$ETI['msg_separador'].'</label><label class="Label130">'.$objCombos->html('', $objDB).'</label>';
	}else{
	$csv_separa='<input id="csv_separa" name="csv_separa" type="hidden" value="," />';
	}
$iNumFormatosImprime=0;
$iModeloReporte=2313;
$html_iFormatoImprime='<input id="iformatoimprime" name="iformatoimprime" type="hidden" value="0" />
';
if ($_REQUEST['paso']>0){
	if (seg_revisa_permiso($iCodModulo, 5, $objDB)){
		$seg_5=1;
		}
	}
//Cargar las tablas de datos
$aParametros[0]='';//$_REQUEST['p1_2313'];
$aParametros[101]=$_REQUEST['paginaf2313'];
$aParametros[102]=$_REQUEST['lppf2313'];
$aParametros[103]=$_REQUEST['bnombre'];
$aParametros[104]=$_REQUEST['blistar'];
$aParametros[105]=$_REQUEST['bzona'];
list($sTabla2313, $sDebugTabla)=f2313_TablaDetalleV2($aParametros, $objDB, $bDebug);
$sDebug=$sDebug.$sDebugTabla;
$sTabla2301='';
if ($_REQUEST['paso']!=0){
	//Encuesta
	$aParametros2301[100]=$_REQUEST['cara13idconsejero'];
	$aParametros2301[101]=$_REQUEST['paginaf2301'];
	$aParametros2301[102]=$_REQUEST['lppf2301'];
	$aParametros2301[103]=$_REQUEST['cara13peraca'];
	list($sTabla2301, $sDebugTabla)=f2301_TablaDetalleV2Consejero($aParametros2301, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	}
$bDebugMenu=false;
list($et_menu, $sDebugM)=html_menuV2($APP->idsistema, $objDB, $iPiel, $bDebugMenu, $idTercero);
$sDebug=$sDebug.$sDebugM;
$objDB->CerrarConexion();
//FORMA
$objForma=new clsHtmlForma($iPiel);
require $APP->rutacomun.'unad_forma_v2.php';
forma_cabeceraV3($xajax, $ETI['titulo_2313']);
echo $et_menu;
forma_mitad();
if (false){
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
<!--
function limpiapagina(){
	expandesector(98);
	window.document.frmedita.paso.value=-1;
	window.document.frmedita.submit();
	}
function enviaguardar(){
	window.document.frmedita.iscroll.value=window.pageYOffset;
	expandesector(98);
	var dpaso=window.document.frmedita.paso;
	if (dpaso.value==0){
		dpaso.value=10;
		}else{
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
function ter_retorna(){
	var sRetorna=window.document.frmedita.div96v2.value;
	if (sRetorna!=''){
		var idcampo=window.document.frmedita.div96campo.value;
		var illave=window.document.frmedita.div96llave.value;
		var did=document.getElementById(idcampo);
		var dtd=document.getElementById(idcampo+'_td');
		var ddoc=document.getElementById(idcampo+'_doc');
		dtd.value=window.document.frmedita.div96v1.value;
		ddoc.value=sRetorna;
		did.value=window.document.frmedita.div96v3.value;
		ter_muestra(idcampo, illave);
		}
	MensajeAlarmaV2('', 0);
	retornacontrol();
	}
function ter_muestra(idcampo, illave){
	var params=new Array();
	params[1]=document.getElementById(idcampo+'_doc').value;
	if (params[1]!=''){
		params[0]=document.getElementById(idcampo+'_td').value;
		params[2]=idcampo;
		params[3]='div_'+idcampo;
		if (illave==1){params[4]='RevisaLlave';}
		//if (illave==1){params[5]='FuncionCuandoNoEsta';}
		xajax_unad11_Mostrar_v2(params);
		}else{
		document.getElementById(idcampo).value=0;
		document.getElementById('div_'+idcampo).innerHTML='&nbsp;';
		//FuncionCuandoNoHayNada
		}
	}
function ter_traerxid(idcampo, vrcampo){
	var params=new Array();
	params[0]=vrcampo;
	params[1]=idcampo;
	if (params[0]!=0){
		if (idcampo=='cara13idconsejero'){
			params[4]='RevisaLlave';
			}
		xajax_unad11_TraerXid(params);
		}
	}
function imprimelista(){
	if (window.document.frmedita.seg_6.value==1){
		window.document.frmlista.consulta.value=window.document.frmedita.consulta_2313.value;
		window.document.frmlista.titulos.value=window.document.frmedita.titulos_2313.value;
		window.document.frmlista.nombrearchivo.value='Consejeros';
		window.document.frmlista.submit();
		}else{
		window.alert("<?php echo $ERR['6']; ?>");
		}
	}
function asignarvariables(){
	//window.document.frmimpp.v3.value=window.document.frmedita.bnombre.value;
	//window.document.frmimpp.v4.value=window.document.frmedita.bcodigo.value;
	//window.document.frmimpp.separa.value=window.document.frmedita.csv_separa.value.trim();
	}
function imprimeexcel(){
	if (window.document.frmedita.seg_6.value==1){
		asignarvariables();
		window.document.frmimpp.action='e2313.php';
		window.document.frmimpp.submit();
		}else{
		window.alert("<?php echo $ERR['6']; ?>");
		}
	}
function imprimep(){
	if (window.document.frmedita.seg_5.value==1){
		asignarvariables();
		window.document.frmimpp.action='p2313.php';
		window.document.frmimpp.submit();
<?php
if ($iNumFormatosImprime>0){
?>
		expandesector(1);
<?php
	}
?>
		}else{
		window.alert("<?php echo $ERR['5']; ?>");
		}
	}
function verrpt(){
	window.document.frmimprime.submit();
	}
function eliminadato(){
	if (confirm("<?php echo $ETI['confirma_eliminar']; ?>?")){
		expandesector(98);
		window.document.frmedita.paso.value=13;
		window.document.frmedita.submit();
		}
	}
function RevisaLlave(){
	var datos= new Array();
	datos[1]=window.document.frmedita.cara13peraca.value;
	datos[2]=window.document.frmedita.cara13idconsejero.value;
	if ((datos[1]!='')&&(datos[2]!='')){
		xajax_f2313_ExisteDato(datos);
		}
	}
function cargadato(llave1, llave2){
	window.document.frmedita.cara13peraca.value=String(llave1);
	window.document.frmedita.cara13idconsejero.value=String(llave2);
	window.document.frmedita.paso.value=1;
	window.document.frmedita.submit();
	}
function cargaridf2313(llave1){
	window.document.frmedita.cara13id.value=String(llave1);
	window.document.frmedita.paso.value=3;
	window.document.frmedita.submit();
	}
function carga_combo_cara01idcead(){
	var params=new Array();
	params[0]=window.document.frmedita.cara01idzona.value;
	xajax_f2313_Combocara01idcead(params);
	}
function paginarf2313(){
	var params=new Array();
	params[99]=window.document.frmedita.debug.value;
	params[101]=window.document.frmedita.paginaf2313.value;
	params[102]=window.document.frmedita.lppf2313.value;
	params[103]=window.document.frmedita.bnombre.value;
	params[104]=window.document.frmedita.blistar.value;
	params[105]=window.document.frmedita.bzona.value;
	//document.getElementById('div_f2313detalle').innerHTML='<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf2313" name="paginaf2313" type="hidden" value="'+params[101]+'" /><input id="lppf2313" name="lppf2313" type="hidden" value="'+params[102]+'" />';
	xajax_f2313_HtmlTabla(params);
	}
function f2313_cargamasiva(){
	extensiones_permitidas=new Array(".xls", ".xlsx");
	var sError='';
	var archivo=window.document.frmedita.archivodatos.value;
	if (sError==''){
		if (!archivo){
			sError = "No has seleccionado ning\u00fan archivo";
			}
		}
	if (sError==''){
		//recupero la extensión de este nombre de archivo
		extension = (archivo.substring(archivo.lastIndexOf("."))).toLowerCase();
		//compruebo si la extensión está entre las permitidas
		permitida=false;
		for (var i=0; i<extensiones_permitidas.length; i++){
			if (extensiones_permitidas[i] == extension){
				permitida = true;
				break;
				}
			}
	if (!permitida) {
		sError="Comprueba la extensi\u00f3n de los archivos a subir. \nS\u00f3lo se pueden subir archivos con extensiones: " + extensiones_permitidas.join();
		}else{
		expandesector(98);
		window.document.frmedita.paso.value=50;
		window.document.frmedita.submit();
		return 1;
		}
	}
	//si estoy aqui es que no se ha podido submitir
	alert (sError);
	return 0;
	}
function revfoco(objeto){
	setTimeout(function(){objeto.focus();},10);
	}
function siguienteobjeto(){}
document.onkeydown=function(e){
	if (document.all){
		if (event.keyCode==13){event.keyCode=9;}
		}else{
		if (e.which==13){siguienteobjeto();}
		}
	}
function objinicial(){
	document.getElementById("cara13peraca").focus();
	}
function buscarV2016(sCampo){
	window.document.frmedita.iscroll.value=window.pageYOffset;
	expandesector(98);
	window.document.frmedita.scampobusca.value=sCampo;
	var params=new Array();
	params[1]=sCampo;
	//params[2]=window.document.frmedita.iagno.value;
	//params[3]=window.document.frmedita.itipo.value;
	xajax_f2313_Busquedas(params);
	}
function retornacontrol(){
	expandesector(1);
	window.scrollTo(0, window.document.frmedita.iscroll.value);
	}
function Devuelve(sValor){
	var sCampo=window.document.frmedita.scampobusca.value;
	if (sCampo=='cara13idconsejero'){
		ter_traerxid('cara13idconsejero', sValor);
		}
	retornacontrol();
	}
function mantener_sesion(){xajax_sesion_mantenerV4();}
setInterval ('xajax_sesion_abandona_V2();', 60000);
function AyudaLocal(sCampo){
	var divAyuda=document.getElementById('div_ayuda_'+sCampo);
	if (typeof divAyuda==='undefined'){
		}else{
		verboton('cmdAyuda_'+sCampo, 'none');
		var sMensaje='Lo que quiera decir.';
		//if (sCampo=='sNombreCampo'){sMensaje='Mensaje para otro campo.';}
		divAyuda.innerHTML=sMensaje;
		divAyuda.style.display='block';
		}
	}
function cierraDiv96(ref){
	var sRetorna=window.document.frmedita.div96v2.value;
	MensajeAlarmaV2('', 0);
	retornacontrol();
	}
function actualizarcarga(){
	if (window.document.frmedita.blistar.value==''){
		window.alert('Por favor seleccione un periodo para ejecutar el proceso');
		window.document.frmedita.blistar.focus();
		}else{
		window.document.frmedita.iscroll.value=window.pageYOffset;
		expandesector(98);
		window.document.frmedita.paso.value=21;
		window.document.frmedita.submit();
		}
	}
function paginarf2301(){
	var params=new Array();
	params[99]=window.document.frmedita.debug.value;
	params[100]=window.document.frmedita.cara13idconsejero.value;
	params[101]=window.document.frmedita.paginaf2301.value;
	params[102]=window.document.frmedita.lppf2301.value;
	params[103]=window.document.frmedita.cara13peraca.value;
	document.getElementById('div_f2301detalle').innerHTML='<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf2301" name="paginaf2301" type="hidden" value="'+params[101]+'" /><input id="lppf2301" name="lppf2301" type="hidden" value="'+params[102]+'" />';
	xajax_f2301_HtmlTablaConsejero(params);
	}
// -->
</script>
<?php
if ($_REQUEST['paso']!=0){
?>
<form id="frmimpp" name="frmimpp" method="post" action="p2313.php" target="_blank">
<input id="r" name="r" type="hidden" value="2313" />
<input id="id2313" name="id2313" type="hidden" value="<?php echo $_REQUEST['cara13id']; ?>" />
<input id="v3" name="v3" type="hidden" value="" />
<input id="v4" name="v4" type="hidden" value="" />
<input id="v5" name="v5" type="hidden" value="" />
<input id="iformato94" name="iformato94" type="hidden" value="0" />
<input id="separa" name="separa" type="hidden" value="," />
<input id="rdebug" name="rdebug" type="hidden" value="<?php echo $_REQUEST['debug']; ?>"/>
<input id="clave" name="clave" type="hidden" value="" />
</form>
<?php
	}
?>
<form id="frmlista" name="frmlista" method="post" action="listados.php" target="_blank">
<input id="titulos" name="titulos" type="hidden" value="" />
<input id="consulta" name="consulta" type="hidden" value="" />
<input id="nombrearchivo" name="nombrearchivo" type="hidden" value="" />
</form>
<div id="interna">
<form id="frmedita" name="frmedita" method="post" action="" enctype="multipart/form-data" autocomplete="off">
<input id="bNoAutocompletar" name="bNoAutocompletar" type="password" value="" style="display:none;"/>
<input id="paso" name="paso" type="hidden" value="<?php echo $_REQUEST['paso']; ?>" />
<input id="shoy" name="shoy" type="hidden" value="<?php echo fecha_hoy(); ?>" />
<input id="ihoy" name="ihoy" type="hidden" value="<?php echo fecha_DiaMod(); ?>" />
<input id="shora" name="shora" type="hidden" value="<?php echo fecha_hora(); ?>" />
<input id="stipodoc" name="stipodoc" type="hidden" value="<?php echo $APP->tipo_doc; ?>" />
<input id="idusuario" name="idusuario" type="hidden" value="<?php echo $_SESSION['unad_id_tercero']; ?>" />
<input id="seg_5" name="seg_5" type="hidden" value="<?php echo $seg_5; ?>" />
<input id="seg_6" name="seg_6" type="hidden" value="<?php echo $seg_6; ?>" />
<div id="div_sector1">
<div class="titulos">
<div class="titulosD">
<input id="cmdAyuda" name="cmdAyuda" type="button" class="btUpAyuda" onclick="muestraayuda(<?php echo $APP->idsistema.', '.$iCodModulo; ?>);" title="<?php echo $ETI['bt_ayuda']; ?>" value="<?php echo $ETI['bt_ayuda']; ?>"/>
<?php
if ($_REQUEST['paso']==2){
?>
<input id="cmdEliminar" name="cmdEliminar" type="button" class="btUpEliminar" onclick="eliminadato();" title="<?php echo $ETI['bt_eliminar']; ?>" value="<?php echo $ETI['bt_eliminar']; ?>"/>
<?php
	}
$bHayImprimir=false;
$sScript='imprimelista()';
$sClaseBoton='btEnviarExcel';
if ($seg_6==1){$bHayImprimir=true;}
if ($_REQUEST['paso']!=0){
	if ($seg_5==1){
		//$bHayImprimir=true;
		//$sScript='imprimep()';
		//if ($iNumFormatosImprime>0){
			//$sScript='expandesector(94)';
			//}
		//$sClaseBoton='btEnviarPDF'; //btUpPrint
		//if ($id_rpt!=0){$sScript='verrpt()';}
		}
	}
if ($bHayImprimir){
?>
<input id="cmdImprimir" name="cmdImprimir" type="button" class="<?php echo $sClaseBoton; ?>" onclick="<?php echo $sScript; ?>" title="<?php echo $ETI['bt_imprimir']; ?>" value="<?php echo $ETI['bt_imprimir']; ?>"/>
<?php
	}
?>
<input id="cmdLimpiar" name="cmdLimpiar" type="button" class="btUpLimpiar" onclick="limpiapagina();" title="<?php echo $ETI['bt_limpiar']; ?>" value="<?php echo $ETI['bt_limpiar']; ?>"/>
<?php
?>
<input id="cmdGuardar" name="cmdGuardar" type="button" class="btUpGuardar" onclick="enviaguardar();" title="<?php echo $ETI['bt_guardar']; ?>" value="<?php echo $ETI['bt_guardar']; ?>"/>
</div>
<div class="titulosI">
<?php
echo '<h2>'.$ETI['titulo_2313'].'</h2>';
?>
</div>
</div>
<div class="areaform">
<div class="areatrabajo">
<?php
//Div para ocultar
$bconexpande=true;
if ($bconexpande){
?>
<div class="ir_derecha" style="width:62px;">
<input id="boculta2313" name="boculta2313" type="hidden" value="<?php echo $_REQUEST['boculta2313']; ?>" />
<label class="Label30">
<input id="btexpande2313" name="btexpande2313" type="button" value="Mostrar" class="btMiniExpandir" onclick="expandepanel(2313,'block',0);" title="<?php echo $ETI['bt_mostrar']; ?>" style="display:<?php if ($_REQUEST['boculta2313']==0){echo 'none'; }else{echo 'block';} ?>;"/>
</label>
<label class="Label30">
<input id="btrecoge2313" name="btrecoge2313" type="button" value="Ocultar" class="btMiniRecoger" onclick="expandepanel(2313,'none',1);" title="<?php echo $ETI['bt_ocultar']; ?>" style="display:<?php if ($_REQUEST['boculta2313']==0){echo 'block'; }else{echo 'none';} ?>;"/>
</label>
</div>
<div id="div_p2313" style="display:<?php if ($_REQUEST['boculta2313']==0){echo 'block'; }else{echo 'none';} ?>;">
<?php
	}
//Mostrar formulario para editar
?>
<label class="Label130">
<?php
echo $ETI['cara13peraca'];
?>
</label>
<label class="Label600">
<?php
echo $html_cara13peraca;
?>
</label>
<div class="salto1px"></div>
<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo $ETI['cara13idconsejero'];
?>
</label>
<div class="salto1px"></div>
<input id="cara13idconsejero" name="cara13idconsejero" type="hidden" value="<?php echo $_REQUEST['cara13idconsejero']; ?>"/>
<div id="div_cara13idconsejero_llaves">
<?php
$bOculto=true;
if ($_REQUEST['paso']!=2){$bOculto=false;}
echo html_DivTerceroV2('cara13idconsejero', $_REQUEST['cara13idconsejero_td'], $_REQUEST['cara13idconsejero_doc'], $bOculto, 1, $ETI['ing_doc']);
?>
</div>
<div class="salto1px"></div>
<div id="div_cara13idconsejero" class="L"><?php echo $cara13idconsejero_rs; ?></div>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['cara01cargaasignada'];
?>
</label>
<label class="Label60">
<div align="right">
<?php
echo  $_REQUEST['cara01cargafinal'].' /';
?>
</div>
</label>
<label class="Label130">
<input id="cara01cargaasignada" name="cara01cargaasignada" type="text" value="<?php echo $_REQUEST['cara01cargaasignada']; ?>" class="diez" maxlength="10" placeholder="<?php echo $ETI['ing_vr']; ?>"/>
</label>
<input id="cara01cargafinal" name="cara01cargafinal" type="hidden" value="<?php echo $_REQUEST['cara01cargafinal']; ?>"/>
<div class="salto1px"></div>
</div>

<div class="GrupoCampos450">
<label class="Label60">
<?php
echo $ETI['cara13activo'];
?>
</label>
<label class="Label90">
<?php
echo $html_cara13activo;
?>
</label>
<label class="Label60">
<?php
echo $ETI['cara13id'];
?>
</label>
<label class="Label60">
<?php
	echo html_oculto('cara13id', $_REQUEST['cara13id']);
?>
</label>
<div class="salto1px"></div>
<label class="Label60">
<?php
echo $ETI['cara01idzona'];
?>
</label>
<label>
<?php
echo $html_cara01idzona;
?>
</label>
<div class="salto1px"></div>
<label class="Label60">
<?php
echo $ETI['cara01idcead'];
?>
</label>
<label>
<div id="div_cara01idcead">
<?php
echo $html_cara01idcead;
?>
</div>
</label>
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo $ETI['titulo_2301'];
?>
</label>
<input id="boculta2301" name="boculta2301" type="hidden" value="<?php echo $_REQUEST['boculta2301']; ?>" />
<?php
if ($_REQUEST['paso']==2){
?>
<div id="div_f2301detalle">
<?php
echo $sTabla2301;
?>
</div>
<?php
	}
?>
<div class="salto1px"></div>
</div>

<div class="salto1px"></div>
<?php
// -- Inicia la carga masiva
if (true){
?>
<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo $ETI['msg_plano'];
?>
</label>
<div class="salto1px"></div>
<input id="MAX_FILE_SIZE" name="MAX_FILE_SIZE" type="hidden" value="1000000" />
<label class="Label500">
<input id="archivodatos" name="archivodatos" type="file" />
</label>
<label class="Label130">
<?php
echo $objForma->htmlBotonSolo('cmdanexar', 'botonAnexar', 'f2313_cargamasiva()', $ETI['msg_subir']);
?>
</label>
<div class="salto1px"></div>
<div class="GrupoCamposAyuda">
<?php
echo $ETI['msg_infoplano'];
?>
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
</div>
<?php
	}
//Termina la carga masiva.
if (false){
	//Ejemplo de boton de ayuda
	//echo html_BotonAyuda('NombreCampo');
	//echo html_DivAyudaLocal('NombreCampo');
	}
if ($bconexpande){
	//Este es el cierre del div_p2313
?>
<div class="salto1px"></div>
</div>
<?php
	}
//Mostrar el contenido de la tabla
?>
</div><!-- CIERRA EL DIV areatrabajo -->
</div><!-- CIERRA EL DIV areaform -->
<div class="areaform">
<div class="areatitulo">
<?php
echo '<h3>'.$ETI['bloque1'].'</h3>';
?>
</div>
<div class="areatrabajo">
<div class="ir_derecha">
<label class="Label90">
Nombre
</label>
<label>
<input id="bnombre" name="bnombre" type="text" value="<?php echo $_REQUEST['bnombre']; ?>" onchange="paginarf2313()" autocomplete="off"/>
</label>
<label class="Label60">
<input id="cmdActualizar" name="cmdActualizar" type="button" class="btMiniActualizar" onclick="actualizarcarga()" title="Actualizar carga asignada" />
</label>
<div class="salto1px"></div>
<label class="Label90">
Periodo
</label>
<label class="Label600">
<?php
echo $html_blistar;
?>
</label>
<div class="salto1px"></div>
<label class="Label90">
Zona
</label>
<label class="Label600">
<?php
echo $html_bzona;
?>
</label>
</div>
<div class="salto1px"></div>
<?php
echo ' '.$csv_separa;
?>
<div id="div_f2313detalle">
<?php
echo $sTabla2313;
?>
</div>
</div><!-- /div_areatrabajo -->
</div><!-- /DIV_areaform -->
</div><!-- /DIV_Sector1 -->


<div id="div_sector2" style="display:none">
<div class="titulos">
<div class="titulosD">
<input id="cmdAyuda2" name="cmdAyuda2" type="button" class="btSupAyuda" onclick="muestraayuda(<?php echo $iCodModulo; ?>);" title="<?php echo $ETI['bt_ayuda']; ?>" value="<?php echo $ETI['bt_ayuda']; ?>"/>
<input id="cmdVolverSec2" name="cmdVolverSec2" type="button" class="btSupVolver" onclick="expandesector(1);" title="<?php echo $ETI['bt_volver']; ?>" value="<?php echo $ETI['bt_volver']; ?>"/>
</div>
<div class="titulosI">
<?php
echo '<h2>'.$ETI['titulo_sector2'].'</h2>';
?>
</div>
</div>
<div id="cargaForm">
<div id="area">
</div><!-- /div_area -->
</div><!-- /DIV_cargaForm -->
</div><!-- /DIV_Sector2 -->


<div id="div_sector95" style="display:none">
<div id="cargaForm">
<div id="div_95cuerpo"></div>
</div><!-- /DIV_cargaForm -->
</div><!-- /DIV_Sector95 -->


<div id="div_sector96" style="display:none">
<input id="div96v1" name="div96v1" type="hidden" value="" />
<input id="div96v2" name="div96v2" type="hidden" value="" />
<input id="div96v3" name="div96v3" type="hidden" value="" />
<input id="div96campo" name="div96campo" type="hidden" value="" />
<input id="div96llave" name="div96llave" type="hidden" value="" />
<input id="titulo_2313" name="titulo_2313" type="hidden" value="<?php echo $ETI['titulo_2313']; ?>" />
<div class="titulos">
<div class="titulosD">
<input id="cmdAyuda96" name="cmdAyuda96" type="button" class="btSupAyuda" onclick="muestraayuda(<?php echo $iCodModulo; ?>);" title="<?php echo $ETI['bt_ayuda']; ?>" value="<?php echo $ETI['bt_ayuda']; ?>"/>
</div>
<div class="titulosI" id="div_96titulo"></div>
</div>
<div id="cargaForm">
<div id="div_96cuerpo"></div>
</div><!-- /DIV_cargaForm -->
</div><!-- /DIV_Sector96 -->


<div id="div_sector97" style="display:none">
<div class="titulos">
<div class="titulosD">
<input id="cmdAyuda97" name="cmdAyuda97" type="button" class="btSupAyuda" onclick="muestraayuda(<?php echo $iCodModulo; ?>);" title="<?php echo $ETI['bt_ayuda']; ?>" value="<?php echo $ETI['bt_ayuda']; ?>"/>
<input id="cmdVolverSec97" name="cmdVolverSec97" type="button" class="btSupVolver" onclick="retornacontrol();" title="<?php echo $ETI['bt_volver']; ?>" value="<?php echo $ETI['bt_volver']; ?>"/>
</div>
<div class="titulosI" id="div_97titulo">
<?php
echo '<h2>'.$ETI['titulo_2313'].'</h2>';
?>
</div>
</div>
<div id="cargaForm">
<div id="area">
<div id="div_97params"></div>
<div class="salto1px"></div>
<div id="div_97tabla"></div>
</div><!-- /div_area -->
</div><!-- /DIV_cargaForm -->
</div><!-- /DIV_Sector97 -->


<div id="div_sector98" style="display:none">
<div class="titulos">
<div class="titulosD">
<input id="cmdAyuda98" name="cmdAyuda98" type="button" class="btSupAyuda" onclick="muestraayuda(<?php echo $iCodModulo; ?>);" title="<?php echo $ETI['bt_ayuda']; ?>" value="<?php echo $ETI['bt_ayuda']; ?>"/>
</div>
<div class="titulosI">
<?php
echo '<h2>'.$ETI['titulo_2313'].'</h2>';
?>
</div>
</div>
<div id="cargaForm">
<div id="area">
<div class="MarquesinaMedia">
<?php
echo $ETI['msg_espere'];
?>
</div><!-- /Termina la marquesina -->
</div><!-- /div_area -->
</div><!-- /DIV_cargaForm -->
</div><!-- /DIV_Sector98 -->


<?php
if ($sDebug!=''){
	$iSegFin=microtime(true);
	$iSegundos=$iSegFin-$iSegIni;
	echo '<div class="salto1px"></div><div class="GrupoCampos" id="div_debug">'.$sDebug.fecha_microtiempo().' Tiempo total del proceso: <b>'.$iSegundos.'</b> Segundos'.'<div class="salto1px"></div></div>';
	}
?>
<input id="scampobusca" name="scampobusca" type="hidden" value=""/>
<input id="iscroll" name="iscroll" type="hidden" value="<?php echo $_REQUEST['iscroll']; ?>"/>
<input id="itipoerror" name="itipoerror" type="hidden" value="<?php echo $iTipoError; ?>"/>
<input id="debug" name="debug" type="hidden" value="<?php echo $_REQUEST['debug']; ?>"/>
</form>
</div><!-- /DIV_interna -->
<div class="flotante">
<?php
?>
<input id="cmdGuardarf" name="cmdGuardarf" type="button" class="btSoloGuardar" onClick="enviaguardar();" value="<?php echo $ETI['bt_guardar']; ?>"/>
<?php
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
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>js/jquery.autocomplete.css" type="text/css"/>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>js/jquery.js"></script>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>js/jquery.autocomplete.js"></script>
<script language="javascript" src="ac_2313.js"></script>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>unad_todas.js?ver=8"></script>
<?php
forma_piedepagina();
?>