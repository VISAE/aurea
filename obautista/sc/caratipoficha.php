<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2018 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.22.2 lunes, 16 de julio de 2018
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
if (($bPeticionXAJAX)&&($_SESSION['unad_id_tercero']==0)){
	// viene por xajax.
	$xajax=new xajax();
	$xajax->configure('javascript URI', $APP->rutacomun.'xajax/');
	$xajax->register(XAJAX_FUNCTION,'sesion_abandona_V2');
	$xajax->processRequest();
	die();
	}
$grupo_id=1;//Necesita ajustarlo...
$iCodModulo=2311;
$audita[1]=false;
$audita[2]=true;
$audita[3]=true;
$audita[4]=true;
$audita[5]=false;
// -- Se cargan los archivos de idioma
$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
$mensajes_2311='lg/lg_2311_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_2311)){$mensajes_2311='lg/lg_2311_es.php';}
require $mensajes_todas;
require $mensajes_2311;
$xajax=NULL;
$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
if (isset($APP->piel)==0){$APP->piel=1;}
$iPiel=$APP->piel;
$iPiel=1; //Piel 2018.
if (!seg_revisa_permiso($iCodModulo, 1, $objDB)){
	header('Location:nopermiso.php');
	die();
	}
if (!$bPeticionXAJAX){
	if (noticias_pendientes($objDB)){
		$objDB->CerrarConexion();
		header('Location:noticia.php?ret=caratipoficha.php');
		die();
		}
	}
//PROCESOS DE LA PAGINA
// -- Si esta cargando la pagina por primer vez se revisa si requiere auditar y se manda a hacer un limpiar (paso -1)
if (isset($_REQUEST['paso'])==0){
	$_REQUEST['paso']=-1;
	if ($audita[1]){seg_auditaingreso($iCodModulo, $_SESSION['unad_id_tercero'], $objDB);}
	}
// -- 2311 cara11tipocaract
require 'lib2311.php';
$xajax=new xajax();
$xajax->configure('javascript URI', $APP->rutacomun.'xajax/');
$xajax->register(XAJAX_FUNCTION,'sesion_abandona_V2');
$xajax->register(XAJAX_FUNCTION,'sesion_mantener');
$xajax->register(XAJAX_FUNCTION,'f2311_HtmlTabla');
$xajax->register(XAJAX_FUNCTION,'f2311_ExisteDato');
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
if (isset($_REQUEST['paginaf2311'])==0){$_REQUEST['paginaf2311']=1;}
if (isset($_REQUEST['lppf2311'])==0){$_REQUEST['lppf2311']=20;}
if (isset($_REQUEST['boculta2311'])==0){$_REQUEST['boculta2311']=0;}
// -- Inicializar variables de datos.
if (isset($_REQUEST['cara11consec'])==0){$_REQUEST['cara11consec']='';}
if (isset($_REQUEST['cara11consec_nuevo'])==0){$_REQUEST['cara11consec_nuevo']='';}
if (isset($_REQUEST['cara11id'])==0){$_REQUEST['cara11id']='';}
if (isset($_REQUEST['cara11nombre'])==0){$_REQUEST['cara11nombre']='';}
if (isset($_REQUEST['cara11ficha1'])==0){$_REQUEST['cara11ficha1']='';}
if (isset($_REQUEST['cara11ficha1pregbas'])==0){$_REQUEST['cara11ficha1pregbas']='';}
if (isset($_REQUEST['cara11ficha1pregprof'])==0){$_REQUEST['cara11ficha1pregprof']='';}
if (isset($_REQUEST['cara11ficha2'])==0){$_REQUEST['cara11ficha2']='';}
if (isset($_REQUEST['cara11ficha2pregbas'])==0){$_REQUEST['cara11ficha2pregbas']='';}
if (isset($_REQUEST['cara11ficha2pregprof'])==0){$_REQUEST['cara11ficha2pregprof']='';}
if (isset($_REQUEST['cara11ficha3'])==0){$_REQUEST['cara11ficha3']='';}
if (isset($_REQUEST['cara11ficha3pregbas'])==0){$_REQUEST['cara11ficha3pregbas']='';}
if (isset($_REQUEST['cara11ficha3pregprof'])==0){$_REQUEST['cara11ficha3pregprof']='';}
if (isset($_REQUEST['cara11ficha4'])==0){$_REQUEST['cara11ficha4']='';}
if (isset($_REQUEST['cara11ficha4pregbas'])==0){$_REQUEST['cara11ficha4pregbas']='';}
if (isset($_REQUEST['cara11ficha4pregprof'])==0){$_REQUEST['cara11ficha4pregprof']='';}
if (isset($_REQUEST['cara11ficha5'])==0){$_REQUEST['cara11ficha5']='';}
if (isset($_REQUEST['cara11ficha5pregbas'])==0){$_REQUEST['cara11ficha5pregbas']='';}
if (isset($_REQUEST['cara11ficha5pregprof'])==0){$_REQUEST['cara11ficha5pregprof']='';}
if (isset($_REQUEST['cara11ficha6'])==0){$_REQUEST['cara11ficha6']='';}
if (isset($_REQUEST['cara11ficha6pregbas'])==0){$_REQUEST['cara11ficha6pregbas']='';}
if (isset($_REQUEST['cara11ficha6pregprof'])==0){$_REQUEST['cara11ficha6pregprof']='';}
if (isset($_REQUEST['cara11ficha7'])==0){$_REQUEST['cara11ficha7']='';}
if (isset($_REQUEST['cara11ficha7pregbas'])==0){$_REQUEST['cara11ficha7pregbas']='';}
if (isset($_REQUEST['cara11ficha7pregprof'])==0){$_REQUEST['cara11ficha7pregprof']='';}
if (isset($_REQUEST['cara11fichafamilia'])==0){$_REQUEST['cara11fichafamilia']='S';}
// Espacio para inicializar otras variables
if (isset($_REQUEST['csv_separa'])==0){$_REQUEST['csv_separa']=',';}
if (isset($_REQUEST['bnombre'])==0){$_REQUEST['bnombre']='';}
//if (isset($_REQUEST['blistar'])==0){$_REQUEST['blistar']='';}
//Si Modifica o Elimina Cargar los campos
if (($_REQUEST['paso']==1)||($_REQUEST['paso']==3)){
	if ($_REQUEST['paso']==1){
		$sSQLcondi='cara11consec='.$_REQUEST['cara11consec'].'';
		}else{
		$sSQLcondi='cara11id='.$_REQUEST['cara11id'].'';
		}
	$sSQL='SELECT * FROM cara11tipocaract WHERE '.$sSQLcondi;
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$_REQUEST['cara11consec']=$fila['cara11consec'];
		$_REQUEST['cara11id']=$fila['cara11id'];
		$_REQUEST['cara11nombre']=$fila['cara11nombre'];
		$_REQUEST['cara11ficha1']=$fila['cara11ficha1'];
		$_REQUEST['cara11ficha1pregbas']=$fila['cara11ficha1pregbas'];
		$_REQUEST['cara11ficha1pregprof']=$fila['cara11ficha1pregprof'];
		$_REQUEST['cara11ficha2']=$fila['cara11ficha2'];
		$_REQUEST['cara11ficha2pregbas']=$fila['cara11ficha2pregbas'];
		$_REQUEST['cara11ficha2pregprof']=$fila['cara11ficha2pregprof'];
		$_REQUEST['cara11ficha3']=$fila['cara11ficha3'];
		$_REQUEST['cara11ficha3pregbas']=$fila['cara11ficha3pregbas'];
		$_REQUEST['cara11ficha3pregprof']=$fila['cara11ficha3pregprof'];
		$_REQUEST['cara11ficha4']=$fila['cara11ficha4'];
		$_REQUEST['cara11ficha4pregbas']=$fila['cara11ficha4pregbas'];
		$_REQUEST['cara11ficha4pregprof']=$fila['cara11ficha4pregprof'];
		$_REQUEST['cara11ficha5']=$fila['cara11ficha5'];
		$_REQUEST['cara11ficha5pregbas']=$fila['cara11ficha5pregbas'];
		$_REQUEST['cara11ficha5pregprof']=$fila['cara11ficha5pregprof'];
		$_REQUEST['cara11ficha6']=$fila['cara11ficha6'];
		$_REQUEST['cara11ficha6pregbas']=$fila['cara11ficha6pregbas'];
		$_REQUEST['cara11ficha6pregprof']=$fila['cara11ficha6pregprof'];
		$_REQUEST['cara11ficha7']=$fila['cara11ficha7'];
		$_REQUEST['cara11ficha7pregbas']=$fila['cara11ficha7pregbas'];
		$_REQUEST['cara11ficha7pregprof']=$fila['cara11ficha7pregprof'];
		$_REQUEST['cara11fichafamilia']=$fila['cara11fichafamilia'];
		$bcargo=true;
		$_REQUEST['paso']=2;
		$_REQUEST['boculta2311']=0;
		$bLimpiaHijos=true;
		}else{
		$_REQUEST['paso']=0;
		}
	}
//Insertar o modificar un elemento
if (($_REQUEST['paso']==10)||($_REQUEST['paso']==12)){
	$bMueveScroll=true;
	list($_REQUEST, $sError, $iTipoError, $sDebugGuardar)=f2311_db_GuardarV2($_REQUEST, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugGuardar;
	if ($sError==''){
		$sError='<b>'.$ETI['msg_itemguardado'].'</b>';
		$iTipoError=1;
		}
	}
// Cambio de consecutivo.
if ($_REQUEST['paso']==93){
	$_REQUEST['paso']=2;
	$_REQUEST['cara11consec_nuevo']=numeros_validar($_REQUEST['cara11consec_nuevo']);
	if ($_REQUEST['cara11consec_nuevo']==''){$sError=$ERR['cara11consec'];}
	if ($sError==''){
		if (!seg_revisa_permiso($iCodModulo, 8, $objDB)){
			$sError=$ERR['8'];
			}
		}
	if ($sError==''){
		//Ver que el consecutivo no exista.
		$sSQL='SELECT cara11id FROM cara11tipocaract WHERE cara11consec='.$_REQUEST['cara11consec_nuevo'].'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$sError='El consecutivo '.$_REQUEST['cara11consec_nuevo'].' ya existe';
			}
		}
	if ($sError==''){
		//Aplicar el cambio.
		$sSQL='UPDATE cara11tipocaract SET cara11consec='.$_REQUEST['cara11consec_nuevo'].' WHERE cara11id='.$_REQUEST['cara11id'].'';
		$tabla=$objDB->ejecutasql($sSQL);
		$sDetalle='Cambia el consecutivo de '.$_REQUEST['cara11consec'].' a '.$_REQUEST['cara11consec_nuevo'].'';
		$_REQUEST['cara11consec']=$_REQUEST['cara11consec_nuevo'];
		$_REQUEST['cara11consec_nuevo']='';
		seg_auditar($iCodModulo, $_SESSION['u_idtercero'], 8, $_REQUEST['cara11id'], $sDetalle, $objDB);
		$sError='<b>Se ha aplicado el cambio de consecutivo.</b>';
		$iTipoError=1;
		}else{
		$iSector=93;
		}
	}
//Eliminar un elemento
if ($_REQUEST['paso']==13){
	$_REQUEST['paso']=2;
	list($sError, $iTipoError, $sDebugElimina)=f2311_db_Eliminar($_REQUEST['cara11id'], $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugElimina;
	if ($sError==''){
		$_REQUEST['paso']=-1;
		$sError=$ETI['msg_itemeliminado'];
		$iTipoError=1;
		}
	}
//limpiar la pantalla
if ($_REQUEST['paso']==-1){
	$_REQUEST['cara11consec']='';
	$_REQUEST['cara11consec_nuevo']='';
	$_REQUEST['cara11id']='';
	$_REQUEST['cara11nombre']='';
	$_REQUEST['cara11ficha1']='S';
	$_REQUEST['cara11ficha1pregbas']='7';
	$_REQUEST['cara11ficha1pregprof']='3';
	$_REQUEST['cara11ficha2']='S';
	$_REQUEST['cara11ficha2pregbas']='7';
	$_REQUEST['cara11ficha2pregprof']='3';
	$_REQUEST['cara11ficha3']='S';
	$_REQUEST['cara11ficha3pregbas']='7';
	$_REQUEST['cara11ficha3pregprof']='3';
	$_REQUEST['cara11ficha4']='S';
	$_REQUEST['cara11ficha4pregbas']='7';
	$_REQUEST['cara11ficha4pregprof']='3';
	$_REQUEST['cara11ficha5']='N';
	$_REQUEST['cara11ficha5pregbas']='7';
	$_REQUEST['cara11ficha5pregprof']='3';
	$_REQUEST['cara11ficha6']='N';
	$_REQUEST['cara11ficha6pregbas']='7';
	$_REQUEST['cara11ficha6pregprof']='3';
	$_REQUEST['cara11ficha7']='N';
	$_REQUEST['cara11ficha7pregbas']='7';
	$_REQUEST['cara11ficha7pregprof']='3';
	$_REQUEST['cara11fichafamilia']='S';
	$_REQUEST['paso']=0;
	}
if ($bLimpiaHijos){
	}
//AQUI SE DEBEN CARGAR TODOS LOS DATOS QUE LA FORMA NECESITE.
//DATOS PARA COMPLETAR EL FORMULARIO
//Crear los controles que requieran llamado a base de datos
$objCombos=new clsHtmlCombos('n');
$objCombos->nuevo('cara11ficha1', $_REQUEST['cara11ficha1'], false);
$objCombos->sino();
$html_cara11ficha1=$objCombos->html('', $objDB);
$objCombos->nuevo('cara11ficha2', $_REQUEST['cara11ficha2'], false);
$objCombos->sino();
$html_cara11ficha2=$objCombos->html('', $objDB);
$objCombos->nuevo('cara11ficha3', $_REQUEST['cara11ficha3'], false);
$objCombos->sino();
$html_cara11ficha3=$objCombos->html('', $objDB);
$objCombos->nuevo('cara11ficha4', $_REQUEST['cara11ficha4'], false);
$objCombos->sino();
$html_cara11ficha4=$objCombos->html('', $objDB);
$objCombos->nuevo('cara11ficha5', $_REQUEST['cara11ficha5'], false);
$objCombos->sino();
$html_cara11ficha5=$objCombos->html('', $objDB);
$objCombos->nuevo('cara11ficha6', $_REQUEST['cara11ficha6'], false);
$objCombos->sino();
$html_cara11ficha6=$objCombos->html('', $objDB);
$objCombos->nuevo('cara11ficha7', $_REQUEST['cara11ficha7'], false);
$objCombos->sino();
$html_cara11ficha7=$objCombos->html('', $objDB);
$objCombos->nuevo('cara11fichafamilia', $_REQUEST['cara11fichafamilia'], false);
$objCombos->sino();
$html_cara11fichafamilia=$objCombos->html('', $objDB);
if ((int)$_REQUEST['paso']==0){
	}else{
	}
//Alistar datos adicionales
$id_rpt=0;
//$id_rpt=reportes_id(_Identificador_Tipo_Reporte_, $objDB);
/*
$objCombos->nuevo('blistar', $_REQUEST['blistar'], true, '{'.$ETI['msg_todos'].'}');
$objCombos->sAccion='paginarf2311()';
$html_blistar=$objCombos->html('', $objDB);
//$html_blistar=$objCombos->comboSistema(2311, 1, $objDB, 'paginarf2311()');
*/
//Permisos adicionales
$seg_5=0;
$seg_6=0;
$seg_8=0;
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
$iModeloReporte=2311;
$html_iFormatoImprime='<input id="iformatoimprime" name="iformatoimprime" type="hidden" value="0" />
';
if ($_REQUEST['paso']>0){
	if (seg_revisa_permiso($iCodModulo, 5, $objDB)){
		$seg_5=1;
		}
	if (seg_revisa_permiso($iCodModulo, 8, $objDB)){$seg_8=1;}
	}
//Cargar las tablas de datos
$aParametros[0]='';//$_REQUEST['p1_2311'];
$aParametros[101]=$_REQUEST['paginaf2311'];
$aParametros[102]=$_REQUEST['lppf2311'];
//$aParametros[103]=$_REQUEST['bnombre'];
//$aParametros[104]=$_REQUEST['blistar'];
list($sTabla2311, $sDebugTabla)=f2311_TablaDetalleV2($aParametros, $objDB, $bDebug);
$sDebug=$sDebug.$sDebugTabla;
list($et_menu, $sDebugM)=html_menuV2($APP->idsistema, $objDB, $iPiel, $bDebug);
$objDB->CerrarConexion();
//FORMA
require $APP->rutacomun.'unad_forma_v2.php';
forma_cabeceraV3($xajax, $ETI['titulo_2311']);
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
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>css/criticalPath.css" type="text/css"/>
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>css/principal.css" type="text/css"/>
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>unad_estilos2018.css" type="text/css"/>
<?php
?>
<script language="javascript" type="text/javascript" charset="UTF-8" src="<?php echo $APP->rutacomun; ?>unad_todas.js?ver=8"></script>
<script language="javascript" type="text/javascript" charset="UTF-8">
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
	document.getElementById('div_sector93').style.display='none';
	document.getElementById('div_sector95').style.display='none';
	document.getElementById('div_sector96').style.display='none';
	document.getElementById('div_sector98').style.display='none';
	document.getElementById('div_sector'+codigo).style.display='block';
	var sEst='none';
	if (codigo==1){sEst='block';}
	document.getElementById('cmdGuardarf').style.display=sEst;
	}
function imprimelista(){
	if (window.document.frmedita.seg_6.value==1){
		window.document.frmlista.consulta.value=window.document.frmedita.consulta_2311.value;
		window.document.frmlista.titulos.value=window.document.frmedita.titulos_2311.value;
		window.document.frmlista.nombrearchivo.value='Tipos de ficha';
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
		window.document.frmimpp.action='e2311.php';
		window.document.frmimpp.submit();
		}else{
		window.alert("<?php echo $ERR['6']; ?>");
		}
	}
function imprimep(){
	if (window.document.frmedita.seg_5.value==1){
		asignarvariables();
		window.document.frmimpp.action='p2311.php';
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
	datos[1]=window.document.frmedita.cara11consec.value;
	if ((datos[1]!='')){
		xajax_f2311_ExisteDato(datos);
		}
	}
function cargadato(llave1){
	window.document.frmedita.cara11consec.value=String(llave1);
	window.document.frmedita.paso.value=1;
	window.document.frmedita.submit();
	}
function cargaridf2311(llave1){
	window.document.frmedita.cara11id.value=String(llave1);
	window.document.frmedita.paso.value=3;
	window.document.frmedita.submit();
	}
function paginarf2311(){
	var params=new Array();
	params[99]=window.document.frmedita.debug.value;
	params[101]=window.document.frmedita.paginaf2311.value;
	params[102]=window.document.frmedita.lppf2311.value;
	//params[103]=window.document.frmedita.bnombre.value;
	//params[104]=window.document.frmedita.blistar.value;
	//document.getElementById('div_f2311detalle').innerHTML='<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf2311" name="paginaf2311" type="hidden" value="'+params[101]+'" /><input id="lppf2311" name="lppf2311" type="hidden" value="'+params[102]+'" />';
	xajax_f2311_HtmlTabla(params);
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
	document.getElementById("cara11consec").focus();
	}
function retornacontrol(){
	expandesector(1);
	window.scrollTo(0, window.document.frmedita.iscroll.value);
	}
function mantener_sesion(){xajax_sesion_mantener();}
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
function mod_consec(){
	if (confirm("Esta seguro de cambiar el consecutivo?")){
		expandesector(98);
		window.document.frmedita.paso.value=93;
		window.document.frmedita.submit();
		}
	}
// -->
</script>
<?php
if ($_REQUEST['paso']!=0){
?>
<form id="frmimpp" name="frmimpp" method="post" action="p2311.php" target="_blank">
<input id="r" name="r" type="hidden" value="2311" />
<input id="id2311" name="id2311" type="hidden" value="<?php echo $_REQUEST['cara11id']; ?>" />
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
<form id="frmedita" name="frmedita" method="post" action="" autocomplete="off">
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
<?php
if (false){
?>
<input id="cmdAnular" name="cmdAnular" type="button" class="btSupAnular" onclick="expandesector(2);" title="<?php echo $ETI['bt_anular']; ?>" value="<?php echo $ETI['bt_anular']; ?>"/>
<?php
	}
?>
</div>
<div class="titulosI">
<?php
echo '<h2>'.$ETI['titulo_2311'].'</h2>';
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
<input id="boculta2311" name="boculta2311" type="hidden" value="<?php echo $_REQUEST['boculta2311']; ?>" />
<label class="Label30">
<input id="btexpande2311" name="btexpande2311" type="button" value="Mostrar" class="btMiniExpandir" onclick="expandepanel(2311,'block',0);" title="<?php echo $ETI['bt_mostrar']; ?>" style="display:<?php if ($_REQUEST['boculta2311']==0){echo 'none'; }else{echo 'block';} ?>;"/>
</label>
<label class="Label30">
<input id="btrecoge2311" name="btrecoge2311" type="button" value="Ocultar" class="btMiniRecoger" onclick="expandepanel(2311,'none',1);" title="<?php echo $ETI['bt_ocultar']; ?>" style="display:<?php if ($_REQUEST['boculta2311']==0){echo 'block'; }else{echo 'none';} ?>;"/>
</label>
</div>
<div id="div_p2311" style="display:<?php if ($_REQUEST['boculta2311']==0){echo 'block'; }else{echo 'none';} ?>;">
<?php
	}
//Mostrar formulario para editar
?>
<label class="Label90">
<?php
echo $ETI['cara11consec'];
?>
</label>
<label class="Label90">
<?php
if ($_REQUEST['paso']!=2){
?>
<input id="cara11consec" name="cara11consec" type="text" value="<?php echo $_REQUEST['cara11consec']; ?>" onchange="RevisaLlave()" class="cuatro"/>
<?php
	}else{
	echo html_oculto('cara11consec', $_REQUEST['cara11consec']);
	}
?>
</label>
<?php
/*
if ($seg_8==1){
	echo $objForma->htmlBotonSolo('cmdCambiaConsec', 'btMiniActualizar', 'expandesector(93);', $ETI['bt_cambiar'], 30);
	echo '<label class="Label30">&nbsp;</label>';
	}
*/
?>
<label class="Label60">
<?php
echo $ETI['cara11id'];
?>
</label>
<label class="Label60">
<?php
	echo html_oculto('cara11id', $_REQUEST['cara11id']);
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara11fichafamilia'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara11fichafamilia;
?>
</label>
<label class="L">
<?php
echo $ETI['cara11nombre'];
?>

<input id="cara11nombre" name="cara11nombre" type="text" value="<?php echo $_REQUEST['cara11nombre']; ?>" maxlength="100" class="L" placeholder="<?php echo $ETI['ing_campo'].$ETI['cara11nombre']; ?>"/>
</label>

<label class="Label250">&nbsp;</label>
<label class="Label130">&nbsp;</label>
<label class="Label60">&nbsp;</label>
<label>
<?php
echo $ETI['msg_preguntas'];
?>
</label>
<div class="salto1px"></div>
<label class="Label250">&nbsp;</label>
<label class="Label130">
<?php
echo $ETI['msg_incluir'];
?>
</label>
<label class="Label90">
<?php
echo $ETI['msg_basicas'];
?>
</label>
<label class="Label160">
<?php
echo $ETI['msg_profundizacion'];
?>
</label>
<div class="salto1px"></div>
<label class="Label250">
<?php
echo $ETI['cara11ficha1'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara11ficha1;
?>
</label>
<label class="Label130">
<input id="cara11ficha1pregbas" name="cara11ficha1pregbas" type="text" value="<?php echo $_REQUEST['cara11ficha1pregbas']; ?>" class="cuatro" maxlength="10" placeholder="7"/>
</label>
<label class="Label130">
<input id="cara11ficha1pregprof" name="cara11ficha1pregprof" type="text" value="<?php echo $_REQUEST['cara11ficha1pregprof']; ?>" class="cuatro" maxlength="10" placeholder="3"/>
</label>

<div class="salto1px"></div>
<label class="Label250">
<?php
echo $ETI['cara11ficha2'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara11ficha2;
?>
</label>
<label class="Label130">
<input id="cara11ficha2pregbas" name="cara11ficha2pregbas" type="text" value="<?php echo $_REQUEST['cara11ficha2pregbas']; ?>" class="cuatro" maxlength="10" placeholder="7"/>
</label>
<label class="Label130">
<input id="cara11ficha2pregprof" name="cara11ficha2pregprof" type="text" value="<?php echo $_REQUEST['cara11ficha2pregprof']; ?>" class="cuatro" maxlength="10" placeholder="3"/>
</label>

<div class="salto1px"></div>
<label class="Label250">
<?php
echo $ETI['cara11ficha3'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara11ficha3;
?>
</label>
<label class="Label130">
<input id="cara11ficha3pregbas" name="cara11ficha3pregbas" type="text" value="<?php echo $_REQUEST['cara11ficha3pregbas']; ?>" class="cuatro" maxlength="10" placeholder="7"/>
</label>
<label class="Label130">
<input id="cara11ficha3pregprof" name="cara11ficha3pregprof" type="text" value="<?php echo $_REQUEST['cara11ficha3pregprof']; ?>" class="cuatro" maxlength="10" placeholder="3"/>
</label>

<div class="salto1px"></div>
<label class="Label250">
<?php
echo $ETI['cara11ficha4'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara11ficha4;
?>
</label>
<label class="Label130">
<input id="cara11ficha4pregbas" name="cara11ficha4pregbas" type="text" value="<?php echo $_REQUEST['cara11ficha4pregbas']; ?>" class="cuatro" maxlength="10" placeholder="7"/>
</label>
<label class="Label130">
<input id="cara11ficha4pregprof" name="cara11ficha4pregprof" type="text" value="<?php echo $_REQUEST['cara11ficha4pregprof']; ?>" class="cuatro" maxlength="10" placeholder="3"/>
</label>

<div class="salto1px"></div>
<label class="Label250">
<?php
echo $ETI['cara11ficha5'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara11ficha5;
?>
</label>
<label class="Label130">
<input id="cara11ficha5pregbas" name="cara11ficha5pregbas" type="text" value="<?php echo $_REQUEST['cara11ficha5pregbas']; ?>" class="cuatro" maxlength="10" placeholder="7"/>
</label>
<label class="Label130">
<input id="cara11ficha5pregprof" name="cara11ficha5pregprof" type="text" value="<?php echo $_REQUEST['cara11ficha5pregprof']; ?>" class="cuatro" maxlength="10" placeholder="3"/>
</label>

<div class="salto1px"></div>
<label class="Label250">
<?php
echo $ETI['cara11ficha6'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara11ficha6;
?>
</label>
<label class="Label130">
<input id="cara11ficha6pregbas" name="cara11ficha6pregbas" type="text" value="<?php echo $_REQUEST['cara11ficha6pregbas']; ?>" class="cuatro" maxlength="10" placeholder="7"/>
</label>
<label class="Label130">
<input id="cara11ficha6pregprof" name="cara11ficha6pregprof" type="text" value="<?php echo $_REQUEST['cara11ficha6pregprof']; ?>" class="cuatro" maxlength="10" placeholder="3"/>
</label>

<div class="salto1px"></div>
<label class="Label250">
<?php
echo $ETI['cara11ficha7'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara11ficha7;
?>
</label>
<label class="Label130">
<input id="cara11ficha7pregbas" name="cara11ficha7pregbas" type="text" value="<?php echo $_REQUEST['cara11ficha7pregbas']; ?>" class="cuatro" maxlength="10" placeholder="7"/>
</label>
<label class="Label130">
<input id="cara11ficha7pregprof" name="cara11ficha7pregprof" type="text" value="<?php echo $_REQUEST['cara11ficha7pregprof']; ?>" class="cuatro" maxlength="10" placeholder="3"/>
</label>
<?php
if (false){
	//Ejemplo de boton de ayuda
	//echo html_BotonAyuda('NombreCampo');
	//echo html_DivAyudaLocal('NombreCampo');
	}
if ($bconexpande){
	//Este es el cierre del div_p2311
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
<?php
if (false){
?>
<div class="ir_derecha">
<label class="Label90">
Nombre
</label>
<label>
<input id="bnombre" name="bnombre" type="text" value="<?php echo $_REQUEST['bnombre']; ?>" onchange="paginarf2311()" autocomplete="off"/>
</label>
<label class="Label90">
Listar
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
<div id="div_f2311detalle">
<?php
echo $sTabla2311;
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


<div id="div_sector93" style="display:none">
<?php
$objForma=new clsHtmlForma($iPiel);
$objForma->addBoton('cmdAyuda93', 'btSupAyuda', 'muestraayuda('.$iCodModulo.');', $ETI['bt_ayuda']);
$objForma->addBoton('cmdVolverSec93', 'btSupVolver', 'expandesector(1);', $ETI['bt_volver']);
echo $objForma->htmlTitulo(''.$ETI['titulo_sector93'].'', $iCodModulo);
echo $objForma->htmlInicioMarco();
?>
<label class="Label160">
<?php
echo $ETI['msg_cara11consec'];
?>
</label>
<label class="Label90">
<?php
echo '<b>'.$_REQUEST['cara11consec'].'</b>';
?>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['msg_cara11consec_nuevo'];
// onchange="RevisaConsec()"
?>
</label>
<label class="Label90">
<input id="cara11consec_nuevo" name="cara11consec_nuevo" type="text" value="<?php echo $_REQUEST['cara11consec_nuevo']; ?>" class="cuatro"/>
</label>
<div class="salto1px"></div>
<label class="Label160">&nbsp;</label>
<?php
echo $objForma->htmlBotonSolo('cmdCambiaConsecFinal', 'botonProceso', 'mod_consec();', $ETI['bt_cambiar'], 130);
echo $objForma->htmlFinMarco();
?>
</div><!-- /DIV_Sector93 -->


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
<input id="titulo_2311" name="titulo_2311" type="hidden" value="<?php echo $ETI['titulo_2311']; ?>" />
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
echo '<h2>'.$ETI['titulo_2311'].'</h2>';
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

<script language="javascript" type="text/javascript" charset="UTF-8">
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
<script language="javascript" type="text/javascript" charset="UTF-8" src="<?php echo $APP->rutacomun; ?>js/jquery-3.3.1.min.js"></script>
<script language="javascript" type="text/javascript" charset="UTF-8" src="<?php echo $APP->rutacomun; ?>js/popper.min.js"></script>
<script language="javascript" type="text/javascript" charset="UTF-8" src="<?php echo $APP->rutacomun; ?>js/bootstrap.min.js"></script>
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>js/bootstrap.min.css" type="text/css"/>
<?php
forma_piedepagina();
?>