<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2021 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.25.10c domingo, 7 de marzo de 2021
*/
/** Archivo rpttotalacom.php.
* Modulo 3045 saiu41docente.
* @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
* @param debug=1 (Opcional), bandera para indicar si se generan datos de depuración
* @date domingo, 7 de marzo de 2021
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
require $APP->rutacomun.'libdatos.php';
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
$grupo_id=1;//Necesita ajustarlo...
$iCodModulo=3045;
$audita[1]=false;
$audita[2]=true;
$audita[3]=true;
$audita[4]=true;
$audita[5]=false;
// -- Se cargan los archivos de idioma
$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
$mensajes_3045='lg/lg_3045_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_3045)){$mensajes_3045='lg/lg_3045_es.php';}
require $mensajes_todas;
require $mensajes_3045;
$xajax=NULL;
$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
if (isset($APP->piel)==0){$APP->piel=1;}
$sAnchoExpandeContrae=' style="width:62px;"';
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
		header('Location:noticia.php?ret=rpttotalacom.php');
		die();
		}
	}
$idTercero=$_SESSION['unad_id_tercero'];
$bOtroUsuario=false;
if (isset($_REQUEST['deb_doc'])!=0){
	list($bDevuelve, $sDebugP)=seg_revisa_permisoV3($iCodModulo, 1707, $_SESSION['unad_id_tercero'], $objDB, $bDebug);
	//$sDebug=$sDebug.$sDebugP;
	if ($bDevuelve){
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
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' No cuenta con permiso de ingreso como otro usuario [Modulo '.$iCodModulo.' Permiso 1707].<br>';}
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
// -- Si esta cargando la pagina por primer vez se revisa si requiere auditar y se manda a hacer un limpiar (paso -1)
if (isset($_REQUEST['paso'])==0){
	$_REQUEST['paso']=-1;
	if ($audita[1]){seg_auditaingreso($iCodModulo, $_SESSION['unad_id_tercero'], $objDB);}
	}
// -- 3045 saiu41docente
require 'lib3045.php';
$xajax=new xajax();
$xajax->configure('javascript URI', $APP->rutacomun.'xajax/');
$xajax->register(XAJAX_FUNCTION,'f3045_Combosaiu41idprograma');
$xajax->register(XAJAX_FUNCTION,'f3045_Combosaiu41idcurso');
$xajax->register(XAJAX_FUNCTION,'f3045_Combosaiu41idcead');
$xajax->register(XAJAX_FUNCTION,'sesion_abandona_V2');
$xajax->register(XAJAX_FUNCTION,'sesion_mantenerV4');
$xajax->register(XAJAX_FUNCTION,'f3045_HtmlTabla');
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
if (isset($_REQUEST['paginaf3045'])==0){$_REQUEST['paginaf3045']=1;}
if (isset($_REQUEST['lppf3045'])==0){$_REQUEST['lppf3045']=20;}
if (isset($_REQUEST['boculta3045'])==0){$_REQUEST['boculta3045']=0;}
// -- Inicializar variables de datos.
if (isset($_REQUEST['saiu41idperiodo'])==0){$_REQUEST['saiu41idperiodo']='';}
if (isset($_REQUEST['saiu41idescuela'])==0){$_REQUEST['saiu41idescuela']='';}
if (isset($_REQUEST['saiu41idprograma'])==0){$_REQUEST['saiu41idprograma']='';}
if (isset($_REQUEST['saiu41idcurso'])==0){$_REQUEST['saiu41idcurso']='';}
if (isset($_REQUEST['saiu41idzona'])==0){$_REQUEST['saiu41idzona']='';}
if (isset($_REQUEST['saiu41idcead'])==0){$_REQUEST['saiu41idcead']='';}
if (isset($_REQUEST['saiu41tipocontacto'])==0){$_REQUEST['saiu41tipocontacto']='';}
if (isset($_REQUEST['saiu41fecha'])==0){$_REQUEST['saiu41fecha']='';}//{fecha_hoy();}
if (isset($_REQUEST['saiu41fechafin'])==0){$_REQUEST['saiu41fechafin']='';}//{fecha_hoy();}
// Espacio para inicializar otras variables
if (isset($_REQUEST['csv_separa'])==0){$_REQUEST['csv_separa']=',';}
if (isset($_REQUEST['bnombre'])==0){$_REQUEST['bnombre']='';}
//if (isset($_REQUEST['blistar'])==0){$_REQUEST['blistar']='';}
//limpiar la pantalla
if ($_REQUEST['paso']==-1){
	$_REQUEST['saiu41idperiodo']='';
	$_REQUEST['saiu41idescuela']='';
	$_REQUEST['saiu41idprograma']='';
	$_REQUEST['saiu41idcurso']='';
	$_REQUEST['saiu41idzona']='';
	$_REQUEST['saiu41idcead']='';
	$_REQUEST['saiu41tipocontacto']='';
	$_REQUEST['saiu41fecha']=fecha_ArmarNumero(1, 1, fecha_agno());//fecha_hoy();
	$_REQUEST['saiu41fechafin']='';//fecha_hoy();
	$_REQUEST['paso']=0;
	}
//AQUI SE DEBEN CARGAR TODOS LOS DATOS QUE LA FORMA NECESITE.
//DATOS PARA COMPLETAR EL FORMULARIO
$iAgnoIni=2020;
$iAgnoFin=fecha_agno();
//Permisos adicionales
$seg_5=0;
$seg_6=0;
//list($bDevuelve, $sDebugP)=seg_revisa_permisoV3($iCodModulo, 6, $idTercero, $objDB);
//if ($bDevuelve){$seg_6=1;}
//if ($seg_6==1){}
//Crear los controles que requieran llamado a base de datos
$objCombos=new clsHtmlCombos();
$objCombos->nuevo('saiu41idperiodo', $_REQUEST['saiu41idperiodo'], true, '{'.$ETI['msg_todos'].'}');
$objCombos->sAccion='carga_combo_saiu41idcurso();';
$objCombos->addItem('-99', '{Sin periodo}');
$sSQL=f146_ConsultaCombo('exte02id>760', $objDB);
$html_saiu41idperiodo=$objCombos->html($sSQL, $objDB);
$objCombos->nuevo('saiu41idescuela', $_REQUEST['saiu41idescuela'], true, '{'.$ETI['msg_todas'].'}');
$objCombos->sAccion='carga_combo_saiu41idprograma();';
$sSQL='SELECT core12id AS id, core12nombre AS nombre FROM core12escuela WHERE core12tieneestudiantes="S" ORDER BY core12nombre';
$html_saiu41idescuela=$objCombos->html($sSQL, $objDB);
$html_saiu41idprograma=f3045_HTMLComboV2_saiu41idprograma($objDB, $objCombos, $_REQUEST['saiu41idprograma'], $_REQUEST['saiu41idescuela']);
$html_saiu41idcurso=f3045_HTMLComboV2_saiu41idcurso($objDB, $objCombos, $_REQUEST['saiu41idcurso'], $_REQUEST['saiu41idperiodo']);
$objCombos->nuevo('saiu41idzona', $_REQUEST['saiu41idzona'], true, '{'.$ETI['msg_todas'].'}');
$objCombos->sAccion='carga_combo_saiu41idcead();';
$sSQL='SELECT unad23id AS id, unad23nombre AS nombre FROM unad23zona WHERE unad23conestudiantes="S" ORDER BY unad23nombre';
$html_saiu41idzona=$objCombos->html($sSQL, $objDB);
$html_saiu41idcead=f3045_HTMLComboV2_saiu41idcead($objDB, $objCombos, $_REQUEST['saiu41idcead'], $_REQUEST['saiu41idzona']);
$objCombos->nuevo('saiu41tipocontacto', $_REQUEST['saiu41tipocontacto'], true, '{'.$ETI['msg_todos'].'}');
$objCombos->sAccion='paginarf3045()';
$sSQL='SELECT ceca26id AS id, ceca26nombre AS nombre FROM ceca26tipoacompana ORDER BY ceca26nombre';
$html_saiu41tipocontacto=$objCombos->html($sSQL, $objDB);
if ((int)$_REQUEST['paso']==0){
	}else{
	}
//Alistar datos adicionales
$id_rpt=0;
//$id_rpt=reportes_id(_Identificador_Tipo_Reporte_, $objDB);
/*
$objCombos->nuevo('blistar', $_REQUEST['blistar'], true, '{'.$ETI['msg_todos'].'}');
$objCombos->sAccion='paginarf3045()';
$html_blistar=$objCombos->html('', $objDB);
//$html_blistar=$objCombos->comboSistema(3045, 1, $objDB, 'paginarf3045()');
*/
if (false){
	$objCombos->nuevo('csv_separa', $_REQUEST['csv_separa'], false);
	$objCombos->addItem(',', $ETI['msg_coma']);
	$objCombos->addItem(';', $ETI['msg_puntoycoma']);
	$csv_separa='<label class="Label90">'.$ETI['msg_separador'].'</label><label class="Label130">'.$objCombos->html('', $objDB).'</label>';
	}else{
	$csv_separa='<input id="csv_separa" name="csv_separa" type="hidden" value="," />';
	}
$iNumFormatosImprime=0;
$iModeloReporte=3045;
$html_iFormatoImprime='<input id="iformatoimprime" name="iformatoimprime" type="hidden" value="0" />
';
$bDevuelve=false;
//list($bDevuelve, $sDebugP)=seg_revisa_permisoV3($iCodModulo, 5, $idTercero, $objDB);
if ($bDevuelve){
	$seg_5=1;
	}
//Cargar las tablas de datos
$aParametros[0]='';//$_REQUEST['p1_3045'];
$aParametros[100]=$idTercero;
$aParametros[101]=$_REQUEST['paginaf3045'];
$aParametros[102]=$_REQUEST['lppf3045'];
$aParametros[103]=$_REQUEST['saiu41idperiodo'];
$aParametros[104]=$_REQUEST['saiu41idescuela'];
$aParametros[105]=$_REQUEST['saiu41idprograma'];
$aParametros[106]=$_REQUEST['saiu41idcurso'];
$aParametros[107]=$_REQUEST['saiu41idzona'];
$aParametros[108]=$_REQUEST['saiu41idcead'];
$aParametros[109]=$_REQUEST['saiu41tipocontacto'];
$aParametros[110]=$_REQUEST['saiu41fecha'];
$aParametros[111]=$_REQUEST['saiu41fechafin'];
list($sTabla3045, $sDebugTabla)=f3045_TablaDetalleV2($aParametros, $objDB, $bDebug);
$sDebug=$sDebug.$sDebugTabla;
$bDebugMenu=false;
list($et_menu, $sDebugM)=html_menuV2($APP->idsistema, $objDB, $iPiel, $bDebugMenu, $idTercero);
$sDebug=$sDebug.$sDebugM;
$objDB->CerrarConexion();
//FORMA
require $APP->rutacomun.'unad_forma_v2.php';
forma_cabeceraV3($xajax, $ETI['titulo_3045']);
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
	document.getElementById('div_sector98').style.display='none';
	document.getElementById('div_sector'+codigo).style.display='block';
	}
function imprimelista(){
	if (window.document.frmedita.seg_6.value==1){
		window.document.frmlista.consulta.value=window.document.frmedita.consulta_3045.value;
		window.document.frmlista.titulos.value=window.document.frmedita.titulos_3045.value;
		window.document.frmlista.nombrearchivo.value='Total acompanamientos';
		window.document.frmlista.submit();
		}else{
		window.alert("<?php echo $ERR['6']; ?>");
		}
	}
function asignarvariables(){
	window.document.frmimpp.v3.value=window.document.frmedita.saiu41idperiodo.value;
	window.document.frmimpp.v4.value=window.document.frmedita.saiu41idescuela.value;
	window.document.frmimpp.v5.value=window.document.frmedita.saiu41idprograma.value;
	window.document.frmimpp.v6.value=window.document.frmedita.saiu41idcurso.value;
	window.document.frmimpp.v7.value=window.document.frmedita.saiu41idzona.value;
	window.document.frmimpp.v8.value=window.document.frmedita.saiu41idcead.value;
	window.document.frmimpp.v9.value=window.document.frmedita.saiu41tipocontacto.value;
	window.document.frmimpp.v10.value=window.document.frmedita.saiu41fecha.value;
	window.document.frmimpp.v11.value=window.document.frmedita.saiu41fechafin.value;
	//window.document.frmimpp.separa.value=window.document.frmedita.csv_separa.value.trim();
	}
function imprimeexcel(){
	var sError='';
	if (window.document.frmedita.seg_6.value!=1){sError="<?php echo $ERR['6']; ?>";}
	//if (sError==''){/*Agregar validaciones*/}
	if (sError==''){
		asignarvariables();
		window.document.frmimpp.action='e3045.php';
		window.document.frmimpp.submit();
		}else{
		window.alert(sError);
		}
	}
function imprimep(){
	if (window.document.frmedita.seg_5.value==1){
		asignarvariables();
		window.document.frmimpp.action='p3045.php';
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
function carga_combo_saiu41idprograma(){
	var params=new Array();
	params[0]=window.document.frmedita.saiu41idescuela.value;
	document.getElementById('div_saiu41idprograma').innerHTML='<b>Procesando datos, por favor espere...</b><input id="saiu41idprograma" name="saiu41idprograma" type="hidden" value="" />';
	xajax_f3045_Combosaiu41idprograma(params);
	}
function carga_combo_saiu41idcurso(){
	var params=new Array();
	params[0]=window.document.frmedita.saiu41idperiodo.value;
	document.getElementById('div_saiu41idcurso').innerHTML='<b>Procesando datos, por favor espere...</b><input id="saiu41idcurso" name="saiu41idcurso" type="hidden" value="" />';
	xajax_f3045_Combosaiu41idcurso(params);
	}
function carga_combo_saiu41idcead(){
	var params=new Array();
	params[0]=window.document.frmedita.saiu41idzona.value;
	document.getElementById('div_saiu41idcead').innerHTML='<b>Procesando datos, por favor espere...</b><input id="saiu41idcead" name="saiu41idcead" type="hidden" value="" />';
	xajax_f3045_Combosaiu41idcead(params);
	}
function paginarf3045(){
	var params=new Array();
	params[99]=window.document.frmedita.debug.value;
	params[100]=<?php echo $idTercero; ?>;
	params[101]=window.document.frmedita.paginaf3045.value;
	params[102]=window.document.frmedita.lppf3045.value;
	params[103]=window.document.frmedita.saiu41idperiodo.value;
	params[104]=window.document.frmedita.saiu41idescuela.value;
	params[105]=window.document.frmedita.saiu41idprograma.value;
	params[106]=window.document.frmedita.saiu41idcurso.value;
	params[107]=window.document.frmedita.saiu41idzona.value;
	params[108]=window.document.frmedita.saiu41idcead.value;
	params[109]=window.document.frmedita.saiu41tipocontacto.value;
	params[110]=window.document.frmedita.saiu41fecha.value;
	params[111]=window.document.frmedita.saiu41fechafin.value;
	//document.getElementById('div_f3045detalle').innerHTML='<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf3045" name="paginaf3045" type="hidden" value="'+params[101]+'" /><input id="lppf3045" name="lppf3045" type="hidden" value="'+params[102]+'" />';
	xajax_f3045_HtmlTabla(params);
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
	document.getElementById("saiu41idperiodo").focus();
	}
function retornacontrol(){
	expandesector(1);
	window.scrollTo(0, window.document.frmedita.iscroll.value);
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
// -->
</script>
<form id="frmimpp" name="frmimpp" method="post" action="p3045.php" target="_blank">
<input id="r" name="r" type="hidden" value="3045" />
<input id="v3" name="v3" type="hidden" value="" />
<input id="v4" name="v4" type="hidden" value="" />
<input id="v5" name="v5" type="hidden" value="" />
<input id="v6" name="v6" type="hidden" value="" />
<input id="v7" name="v7" type="hidden" value="" />
<input id="v8" name="v8" type="hidden" value="" />
<input id="v9" name="v9" type="hidden" value="" />
<input id="v10" name="v10" type="hidden" value="" />
<input id="v11" name="v11" type="hidden" value="" />
<input id="iformato94" name="iformato94" type="hidden" value="0" />
<input id="separa" name="separa" type="hidden" value="," />
<input id="rdebug" name="rdebug" type="hidden" value="<?php echo $_REQUEST['debug']; ?>"/>
<input id="clave" name="clave" type="hidden" value="" />
</form>
<form id="frmlista" name="frmlista" method="post" action="listados.php" target="_blank">
<input id="titulos" name="titulos" type="hidden" value="" />
<input id="consulta" name="consulta" type="hidden" value="" />
<input id="nombrearchivo" name="nombrearchivo" type="hidden" value="" />
</form>
<div id="interna">
<form id="frmedita" name="frmedita" method="post" action="" autocomplete="off">
<input id="bNoAutocompletar" name="bNoAutocompletar" type="password" value="" style="display:none;"/>
<input id="paso" name="paso" type="hidden" value="<?php echo $_REQUEST['paso']; ?>" />
<input id="shoy" name="shoy" type="hidden" value="<?php echo fecha_hoy(); ?>" />
<input id="ihoy" name="ihoy" type="hidden" value="<?php echo fecha_DiaMod(); ?>" />
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
<?php
$bHayImprimir=false;
$sScript='imprimelista()';
$sClaseBoton='btEnviarExcel';
if ($seg_6==1){$bHayImprimir=true;}
if ($bHayImprimir){
?>
<input id="cmdImprimir" name="cmdImprimir" type="button" class="<?php echo $sClaseBoton; ?>" onclick="<?php echo $sScript; ?>" title="<?php echo $ETI['bt_imprimir']; ?>" value="<?php echo $ETI['bt_imprimir']; ?>"/>
<?php
	}
$bHayImprimir2=false;
$sScript='imprimep()';
$sClaseBoton='btEnviarPDF'; //btUpPrint
if ($seg_5==1){$bHayImprimir2=true;}
if ($bHayImprimir2){
?>
<input id="cmdImprimir2" name="cmdImprimir2" type="button" class="<?php echo $sClaseBoton; ?>" onclick="<?php echo $sScript; ?>" title="<?php echo $ETI['bt_imprimir']; ?>" value="<?php echo $ETI['bt_imprimir']; ?>"/>
<?php
	}
?>
<input id="cmdLimpiar" name="cmdLimpiar" type="button" class="btUpLimpiar" onclick="limpiapagina();" title="<?php echo $ETI['bt_limpiar']; ?>" value="<?php echo $ETI['bt_limpiar']; ?>"/>
<?php
?>
</div>
<div class="titulosI">
<?php
echo '<h2>'.$ETI['titulo_3045'].'</h2>';
?>
</div>
</div>
<div class="areaform">
<div class="areatrabajo">
<?php
//Div para ocultar
$bConExpande=false;
if ($bConExpande){
?>
<div class="ir_derecha"<?php echo $sAnchoExpandeContrae; ?>>
<input id="boculta3045" name="boculta3045" type="hidden" value="<?php echo $_REQUEST['boculta3045']; ?>" />
<label class="Label30">
<input id="btexpande3045" name="btexpande3045" type="button" value="Mostrar" class="btMiniExpandir" onclick="expandepanel(3045,'block',0);" title="<?php echo $ETI['bt_mostrar']; ?>" style="display:<?php if ($_REQUEST['boculta3045']==0){echo 'none'; }else{echo 'block';} ?>;"/>
</label>
<label class="Label30">
<input id="btrecoge3045" name="btrecoge3045" type="button" value="Ocultar" class="btMiniRecoger" onclick="expandepanel(3045,'none',1);" title="<?php echo $ETI['bt_ocultar']; ?>" style="display:<?php if ($_REQUEST['boculta3045']==0){echo 'block'; }else{echo 'none';} ?>;"/>
</label>
</div>
<div id="div_p3045" style="display:<?php if ($_REQUEST['boculta3045']==0){echo 'block'; }else{echo 'none';} ?>;">
<?php
	}
//Mostrar formulario para editar
?>
<label class="Label130">
<?php
echo $ETI['saiu41idperiodo'];
?>
</label>
<label>
<?php
echo $html_saiu41idperiodo;
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu41idescuela'];
?>
</label>
<label>
<?php
echo $html_saiu41idescuela;
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu41idprograma'];
?>
</label>
<label>
<div id="div_saiu41idprograma">
<?php
echo $html_saiu41idprograma;
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu41idcurso'];
?>
</label>
<label>
<div id="div_saiu41idcurso">
<?php
echo $html_saiu41idcurso;
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu41idzona'];
?>
</label>
<label>
<?php
echo $html_saiu41idzona;
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu41idcead'];
?>
</label>
<label>
<div id="div_saiu41idcead">
<?php
echo $html_saiu41idcead;
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu41tipocontacto'];
?>
</label>
<label>
<?php
echo $html_saiu41tipocontacto;
?>
</label>
<label class="Label60">
<?php
echo $ETI['msg_desde'];
?>
</label>
<div class="Campo220">
<?php
echo html_FechaEnNumero('saiu41fecha', $_REQUEST['saiu41fecha'], true, 'paginarf3045()', $iAgnoIni, $iAgnoFin);//$bvacio=false,$accion=",$iagnoini=0,$iagnofin=0
?>
</div>
<!--
<label class="Label30">
<input id="bsaiu41fecha_hoy" name="bsaiu41fecha_hoy" type="button" value="Hoy" class="btMiniHoy" onclick="fecha_AsignarNum('saiu41fecha','<?php echo fecha_DiaMod(); ?>')" title="<?php echo $ETI['bt_hoy']; ?>"/>
</label>
-->
<label class="Label60">
<?php
echo $ETI['msg_hasta'];
?>
</label>
<div class="Campo220">
<?php
echo html_FechaEnNumero('saiu41fechafin', $_REQUEST['saiu41fechafin'], true, 'paginarf3045()', $iAgnoIni, $iAgnoFin);//$bvacio=false,$accion=",$iagnoini=0,$iagnofin=0
?>
</div>
<!--
<label class="Label30">
<input id="bsaiu41fechafin_hoy" name="bsaiu41fechafin_hoy" type="button" value="Hoy" class="btMiniHoy" onclick="fecha_AsignarNum('saiu41fechafin','<?php echo fecha_DiaMod(); ?>')" title="<?php echo $ETI['bt_hoy']; ?>"/>
</label>
-->
<div class="salto1px"></div>
<?php
if (false){
	//Ejemplo de boton de ayuda
	//echo html_BotonAyuda('NombreCampo');
	//echo html_DivAyudaLocal('NombreCampo');
	}
if ($bConExpande){
	//Este es el cierre del div_p3045
?>
<div class="salto1px"></div>
</div>
<?php
	}
//Mostrar el contenido de la tabla
?>
<?php
if (false){
?>
<div class="ir_derecha">
<label class="Label90">
<?php
echo $ETI['msg_bnombre'];
?>
</label>
<label>
<input id="bnombre" name="bnombre" type="text" value="<?php echo $_REQUEST['bnombre']; ?>" onchange="paginarf3045()" autocomplete="off"/>
</label>
<label class="Label90">
<?php
echo $ETI['msg_blistar'];
?>
</label>
<label class="Label130">
<?php
echo $html_blistar;
?>
</label>
</div>
<div class="salto1px"></div>
<?php
	}
?>
<?php
echo ' '.$csv_separa;
?>
<div id="div_f3045detalle">
<?php
echo $sTabla3045;
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
<input id="titulo_3045" name="titulo_3045" type="hidden" value="<?php echo $ETI['titulo_3045']; ?>" />
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


<div id="div_sector98" style="display:none">
<div class="titulos">
<div class="titulosD">
<input id="cmdAyuda98" name="cmdAyuda98" type="button" class="btSupAyuda" onclick="muestraayuda(<?php echo $iCodModulo; ?>);" title="<?php echo $ETI['bt_ayuda']; ?>" value="<?php echo $ETI['bt_ayuda']; ?>"/>
</div>
<div class="titulosI">
<?php
echo '<h2>'.$ETI['titulo_3045'].'</h2>';
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
<?php
?>
<script language="javascript">
<!--
$().ready(function(){
$("#saiu41idperiodo").chosen();
$("#saiu41idprograma").chosen();
$("#saiu41idcurso").chosen();
});
-->
</script>
<?php
?>
<script language="javascript" src="ac_3045.js"></script>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>unad_todas.js?ver=8"></script>
<?php
forma_piedepagina();
?>