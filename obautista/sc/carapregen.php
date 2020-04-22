<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2018 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.22.1 jueves, 5 de julio de 2018
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
$iCodModulo=2353;
$audita[1]=false;
$audita[2]=true;
$audita[3]=true;
$audita[4]=true;
$audita[5]=false;
// -- Se cargan los archivos de idioma
$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
$mensajes_2308='lg/lg_2308_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_2308)){$mensajes_2308='lg/lg_2308_es.php';}
require $mensajes_todas;
require $mensajes_2308;
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
		header('Location:noticia.php?ret=carapregcd.php');
		die();
		}
	}
//PROCESOS DE LA PAGINA
$mensajes_2309='lg/lg_2309_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_2309)){$mensajes_2309='lg/lg_2309_es.php';}
$mensajes_2317='lg/lg_2317_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_2317)){$mensajes_2317='lg/lg_2317_es.php';}
require $mensajes_2309;
require $mensajes_2317;
// -- Si esta cargando la pagina por primer vez se revisa si requiere auditar y se manda a hacer un limpiar (paso -1)
if (isset($_REQUEST['paso'])==0){
	$_REQUEST['paso']=-1;
	if ($audita[1]){seg_auditaingreso($iCodModulo, $_SESSION['unad_id_tercero'], $objDB);}
	}
// -- 2308 cara08pregunta
require 'lib2308.php';
// -- 2309 Respuestas
require 'lib2309.php';
// -- 2317 Anexos
require 'lib2317.php';
$xajax=new xajax();
$xajax->configure('javascript URI', $APP->rutacomun.'xajax/');
$xajax->register(XAJAX_FUNCTION,'sesion_abandona_V2');
$xajax->register(XAJAX_FUNCTION,'sesion_mantener');
$xajax->register(XAJAX_FUNCTION,'f2308_HtmlTabla');
$xajax->register(XAJAX_FUNCTION,'f2308_ExisteDato');
$xajax->register(XAJAX_FUNCTION,'f2309_Guardar');
$xajax->register(XAJAX_FUNCTION,'f2309_Traer');
$xajax->register(XAJAX_FUNCTION,'f2309_Eliminar');
$xajax->register(XAJAX_FUNCTION,'f2309_HtmlTabla');
$xajax->register(XAJAX_FUNCTION,'f2309_PintarLlaves');
$xajax->register(XAJAX_FUNCTION,'elimina_archivo_cara17idanexo');
$xajax->register(XAJAX_FUNCTION,'f2317_Guardar');
$xajax->register(XAJAX_FUNCTION,'f2317_Traer');
$xajax->register(XAJAX_FUNCTION,'f2317_Eliminar');
$xajax->register(XAJAX_FUNCTION,'f2317_HtmlTabla');
$xajax->register(XAJAX_FUNCTION,'f2317_PintarLlaves');
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
$cara08idbloque=4;
// -- Se inicializan las variables, primero las que controlan la visualización de la página.
if (isset($_REQUEST['iscroll'])==0){$_REQUEST['iscroll']=0;}
if (isset($_REQUEST['paginaf2308'])==0){$_REQUEST['paginaf2308']=1;}
if (isset($_REQUEST['lppf2308'])==0){$_REQUEST['lppf2308']=20;}
if (isset($_REQUEST['boculta2308'])==0){$_REQUEST['boculta2308']=0;}
if (isset($_REQUEST['paginaf2309'])==0){$_REQUEST['paginaf2309']=1;}
if (isset($_REQUEST['lppf2309'])==0){$_REQUEST['lppf2309']=20;}
if (isset($_REQUEST['boculta2309'])==0){$_REQUEST['boculta2309']=0;}
if (isset($_REQUEST['paginaf2317'])==0){$_REQUEST['paginaf2317']=1;}
if (isset($_REQUEST['lppf2317'])==0){$_REQUEST['lppf2317']=20;}
if (isset($_REQUEST['boculta2317'])==0){$_REQUEST['boculta2317']=0;}
// -- Inicializar variables de datos.
if (isset($_REQUEST['cara08idbloque'])==0){$_REQUEST['cara08idbloque']=$cara08idbloque;}
if (isset($_REQUEST['cara08consec'])==0){$_REQUEST['cara08consec']='';}
if (isset($_REQUEST['cara08consec_nuevo'])==0){$_REQUEST['cara08consec_nuevo']='';}
if (isset($_REQUEST['cara08id'])==0){$_REQUEST['cara08id']='';}
if (isset($_REQUEST['cara08activa'])==0){$_REQUEST['cara08activa']='S';}
if (isset($_REQUEST['cara08idgrupo'])==0){$_REQUEST['cara08idgrupo']='';}
if (isset($_REQUEST['cara08titulo'])==0){$_REQUEST['cara08titulo']='';}
if (isset($_REQUEST['cara08cuerpo'])==0){$_REQUEST['cara08cuerpo']='';}
if (isset($_REQUEST['cara08tipopreg'])==0){$_REQUEST['cara08tipopreg']='';}
if (isset($_REQUEST['cara08usosiniciales'])==0){$_REQUEST['cara08usosiniciales']='';}
if (isset($_REQUEST['cara08usostotales'])==0){$_REQUEST['cara08usostotales']=0;}
if (isset($_REQUEST['cara08nivelpregunta'])==0){$_REQUEST['cara08nivelpregunta']='';}
if ((int)$_REQUEST['paso']>0){
	//Respuestas
	if (isset($_REQUEST['cara09consec'])==0){$_REQUEST['cara09consec']='';}
	if (isset($_REQUEST['cara09id'])==0){$_REQUEST['cara09id']='';}
	if (isset($_REQUEST['cara09valor'])==0){$_REQUEST['cara09valor']='';}
	if (isset($_REQUEST['cara09contenido'])==0){$_REQUEST['cara09contenido']='';}
	//Anexos
	if (isset($_REQUEST['cara17consec'])==0){$_REQUEST['cara17consec']='';}
	if (isset($_REQUEST['cara17id'])==0){$_REQUEST['cara17id']='';}
	if (isset($_REQUEST['cara17idorigen'])==0){$_REQUEST['cara17idorigen']=0;}
	if (isset($_REQUEST['cara17idanexo'])==0){$_REQUEST['cara17idanexo']=0;}
	if (isset($_REQUEST['cara17nombre'])==0){$_REQUEST['cara17nombre']='';}
	}
// Espacio para inicializar otras variables
if (isset($_REQUEST['csv_separa'])==0){$_REQUEST['csv_separa']=',';}
if (isset($_REQUEST['bnombre'])==0){$_REQUEST['bnombre']='';}
//if (isset($_REQUEST['blistar'])==0){$_REQUEST['blistar']='';}
if ((int)$_REQUEST['paso']>0){
	//Respuestas
	if (isset($_REQUEST['bnombre2309'])==0){$_REQUEST['bnombre2309']='';}
	//if (isset($_REQUEST['blistar2309'])==0){$_REQUEST['blistar2309']='';}
	}
//Si Modifica o Elimina Cargar los campos
if (($_REQUEST['paso']==1)||($_REQUEST['paso']==3)){
	if ($_REQUEST['paso']==1){
		$sSQLcondi='cara08idbloque='.$cara08idbloque.' AND cara08consec='.$_REQUEST['cara08consec'].'';
		}else{
		$sSQLcondi='cara08id='.$_REQUEST['cara08id'].'';
		}
	$sSQL='SELECT * FROM cara08pregunta WHERE '.$sSQLcondi;
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$_REQUEST['cara08idbloque']=$fila['cara08idbloque'];
		$_REQUEST['cara08consec']=$fila['cara08consec'];
		$_REQUEST['cara08id']=$fila['cara08id'];
		$_REQUEST['cara08activa']=$fila['cara08activa'];
		$_REQUEST['cara08idgrupo']=$fila['cara08idgrupo'];
		$_REQUEST['cara08titulo']=$fila['cara08titulo'];
		$_REQUEST['cara08cuerpo']=$fila['cara08cuerpo'];
		$_REQUEST['cara08tipopreg']=$fila['cara08tipopreg'];
		$_REQUEST['cara08usosiniciales']=$fila['cara08usosiniciales'];
		$_REQUEST['cara08usostotales']=$fila['cara08usostotales'];
		$_REQUEST['cara08nivelpregunta']=$fila['cara08nivelpregunta'];
		$bcargo=true;
		$_REQUEST['paso']=2;
		$_REQUEST['boculta2308']=0;
		$bLimpiaHijos=true;
		if ($_REQUEST['cara08idbloque']!=$cara08idbloque){
			$_REQUEST['paso']=-1;
			$sError='No es posible cargar preguntas que no pertenezcan al bloque '.$cara08idbloque.'';
			}
		}else{
		$_REQUEST['paso']=0;
		}
	}
//Insertar o modificar un elemento
if (($_REQUEST['paso']==10)||($_REQUEST['paso']==12)){
	$bMueveScroll=true;
	list($_REQUEST, $sError, $iTipoError, $sDebugGuardar)=f2308_db_GuardarV2($_REQUEST, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugGuardar;
	if ($sError==''){
		$sError='<b>'.$ETI['msg_itemguardado'].'</b>';
		$iTipoError=1;
		}
	}
// Cambio de consecutivo.
if ($_REQUEST['paso']==93){
	$_REQUEST['paso']=2;
	$_REQUEST['cara08consec_nuevo']=numeros_validar($_REQUEST['cara08consec_nuevo']);
	if ($_REQUEST['cara08consec_nuevo']==''){$sError=$ERR['cara08consec'];}
	if ($sError==''){
		if (!seg_revisa_permiso($iCodModulo, 8, $objDB)){
			$sError=$ERR['8'];
			}
		}
	if ($sError==''){
		//Ver que el consecutivo no exista.
		$sSQL='SELECT cara08id FROM cara08pregunta WHERE cara08consec='.$_REQUEST['cara08consec_nuevo'].' AND cara08idbloque='.$_REQUEST['cara08idbloque'].'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$sError='El consecutivo '.$_REQUEST['cara08consec_nuevo'].' ya existe';
			}
		}
	if ($sError==''){
		//Aplicar el cambio.
		$sSQL='UPDATE cara08pregunta SET cara08consec='.$_REQUEST['cara08consec_nuevo'].' WHERE cara08id='.$_REQUEST['cara08id'].'';
		$tabla=$objDB->ejecutasql($sSQL);
		$sDetalle='Cambia el consecutivo de '.$_REQUEST['cara08consec'].' a '.$_REQUEST['cara08consec_nuevo'].'';
		$_REQUEST['cara08consec']=$_REQUEST['cara08consec_nuevo'];
		$_REQUEST['cara08consec_nuevo']='';
		seg_auditar($iCodModulo, $_SESSION['u_idtercero'], 8, $_REQUEST['cara08id'], $sDetalle, $objDB);
		$sError='<b>Se ha aplicado el cambio de consecutivo.</b>';
		$iTipoError=1;
		}else{
		$iSector=93;
		}
	}
//Eliminar un elemento
if ($_REQUEST['paso']==13){
	$_REQUEST['paso']=2;
	list($sError, $iTipoError, $sDebugElimina)=f2308_db_Eliminar($_REQUEST['cara08id'], $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugElimina;
	if ($sError==''){
		$_REQUEST['paso']=-1;
		$sError=$ETI['msg_itemeliminado'];
		$iTipoError=1;
		}
	}
//limpiar la pantalla
if ($_REQUEST['paso']==-1){
	$_REQUEST['cara08idbloque']=$cara08idbloque;
	$_REQUEST['cara08consec']='';
	$_REQUEST['cara08consec_nuevo']='';
	$_REQUEST['cara08id']='';
	$_REQUEST['cara08activa']='S';
	$_REQUEST['cara08idgrupo']='';
	$_REQUEST['cara08titulo']='';
	$_REQUEST['cara08cuerpo']='';
	$_REQUEST['cara08tipopreg']='';
	$_REQUEST['cara08usosiniciales']='';
	$_REQUEST['cara08usostotales']=0;
	$_REQUEST['cara08nivelpregunta']=0;
	$_REQUEST['paso']=0;
	}
if ($bLimpiaHijos){
	$_REQUEST['cara09idpregunta']='';
	$_REQUEST['cara09consec']='';
	$_REQUEST['cara09id']='';
	$_REQUEST['cara09valor']='';
	$_REQUEST['cara09contenido']='';
	$_REQUEST['cara17idpregunta']='';
	$_REQUEST['cara17consec']='';
	$_REQUEST['cara17id']='';
	$_REQUEST['cara17idorigen']=0;
	$_REQUEST['cara17idanexo']=0;
	$_REQUEST['cara17nombre']='';
	}
//AQUI SE DEBEN CARGAR TODOS LOS DATOS QUE LA FORMA NECESITE.
//DATOS PARA COMPLETAR EL FORMULARIO
//Crear los controles que requieran llamado a base de datos
$objCombos=new clsHtmlCombos('n');
$objCombos->nuevo('cara08activa', $_REQUEST['cara08activa'], false);
$objCombos->sino();
$html_cara08activa=$objCombos->html('', $objDB);
$objCombos->nuevo('cara08idgrupo', $_REQUEST['cara08idgrupo'], true, '{'.$ETI['msg_ninguno'].'}', 0);
$sSQL='SELECT cara06id AS id, cara06nombre AS nombre FROM cara06grupopreg WHERE cara06idgrupo='.$cara08idbloque.' ORDER BY cara06nombre';
$html_cara08idgrupo=$objCombos->html($sSQL, $objDB);
$objCombos->nuevo('cara08tipopreg', $_REQUEST['cara08tipopreg'], false, '{'.$ETI['msg_seleccione'].'}');
$objCombos->addItem(0, 'Seleccion M&uacute;ltiple, &Uacute;nica Respuesta');
//$objCombos->addArreglo($acara08tipopreg, $icara08tipopreg);
$html_cara08tipopreg=$objCombos->html('', $objDB);
if ($_REQUEST['cara08usostotales']==0){
	$objCombos->nuevo('cara08nivelpregunta', $_REQUEST['cara08nivelpregunta'], false, '{'.$ETI['msg_seleccione'].'}');
	$objCombos->addItem(0, $ETI['msg_basica']);
	$objCombos->addItem(1, $ETI['msg_profundizacion']);
	//$objCombos->addArreglo($acara08nivelpregunta, $icara08nivelpregunta);
	$html_cara08nivelpregunta=$objCombos->html('', $objDB);
	}else{
	$et_cara08nivelpregunta=$ETI['msg_basica'];
	if ($_REQUEST['cara08nivelpregunta']==1){$et_cara08nivelpregunta=$ETI['msg_basica'];}
	$html_cara08nivelpregunta=html_oculto('cara08nivelpregunta', $_REQUEST['cara08nivelpregunta'], $et_cara08nivelpregunta);
	}
if ((int)$_REQUEST['paso']==0){
	}else{
	}
//Alistar datos adicionales
$id_rpt=0;
//$id_rpt=reportes_id(_Identificador_Tipo_Reporte_, $objDB);
/*
$objCombos->nuevo('blistar', $_REQUEST['blistar'], true, '{'.$ETI['msg_todos'].'}');
$objCombos->sAccion='paginarf2308()';
$html_blistar=$objCombos->html('', $objDB);
//$html_blistar=$objCombos->comboSistema(2308, 1, $objDB, 'paginarf2308()');
$objCombos->nuevo('blistar2309', $_REQUEST['blistar2309'], true, '{'.$ETI['msg_todos'].'}');
$html_blistar2309=$objCombos->comboSistema(2309, 1, $objDB, 'paginarf2309()');
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
$iModeloReporte=2308;
$html_iFormatoImprime='<input id="iformatoimprime" name="iformatoimprime" type="hidden" value="0" />
';
if ($_REQUEST['paso']>0){
	if (seg_revisa_permiso($iCodModulo, 5, $objDB)){
		$seg_5=1;
		}
	if (seg_revisa_permiso($iCodModulo, 8, $objDB)){$seg_8=1;}
	}
//Cargar las tablas de datos
$aParametros[0]='';//$_REQUEST['p1_2308'];
$aParametros[101]=$_REQUEST['paginaf2308'];
$aParametros[102]=$_REQUEST['lppf2308'];
$aParametros[103]=$cara08idbloque;
$aParametros[104]=$_REQUEST['bnombre'];
list($sTabla2308, $sDebugTabla)=f2308_TablaDetalleV2($aParametros, $objDB, $bDebug);
$sDebug=$sDebug.$sDebugTabla;
$sTabla2309='';
$sTabla2317='';
if ($_REQUEST['paso']!=0){
	//Respuestas
	$aParametros2309[0]=$_REQUEST['cara08id'];
	$aParametros2309[101]=$_REQUEST['paginaf2309'];
	$aParametros2309[102]=$_REQUEST['lppf2309'];
	//$aParametros2309[103]=$_REQUEST['bnombre2309'];
	//$aParametros2309[104]=$_REQUEST['blistar2309'];
	list($sTabla2309, $sDebugTabla)=f2309_TablaDetalleV2($aParametros2309, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	//Anexos
	$aParametros2317[0]=$_REQUEST['cara08id'];
	$aParametros2317[101]=$_REQUEST['paginaf2317'];
	$aParametros2317[102]=$_REQUEST['lppf2317'];
	//$aParametros2317[103]=$_REQUEST['bnombre2317'];
	//$aParametros2317[104]=$_REQUEST['blistar2317'];
	list($sTabla2317, $sDebugTabla)=f2317_TablaDetalleV2($aParametros2317, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	}
list($et_menu, $sDebugM)=html_menuV2($APP->idsistema, $objDB, $iPiel, $bDebug);
$objDB->CerrarConexion();
//FORMA
require $APP->rutacomun.'unad_forma_v2.php';
forma_cabeceraV3($xajax, $ETI['titulo_2353']);
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
		window.document.frmlista.consulta.value=window.document.frmedita.consulta_2308.value;
		window.document.frmlista.titulos.value=window.document.frmedita.titulos_2308.value;
		window.document.frmlista.nombrearchivo.value='Preguntas Competencias digitales';
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
		window.document.frmimpp.action='e2308.php';
		window.document.frmimpp.submit();
		}else{
		window.alert("<?php echo $ERR['6']; ?>");
		}
	}
function imprimep(){
	if (window.document.frmedita.seg_5.value==1){
		asignarvariables();
		window.document.frmimpp.action='p2308.php';
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
	datos[1]=window.document.frmedita.cara08idbloque.value;
	datos[2]=window.document.frmedita.cara08consec.value;
	if ((datos[1]!='')&&(datos[2]!='')){
		xajax_f2308_ExisteDato(datos);
		}
	}
function cargadato(llave1, llave2){
	window.document.frmedita.cara08idbloque.value=String(llave1);
	window.document.frmedita.cara08consec.value=String(llave2);
	window.document.frmedita.paso.value=1;
	window.document.frmedita.submit();
	}
function cargaridf2308(llave1){
	window.document.frmedita.cara08id.value=String(llave1);
	window.document.frmedita.paso.value=3;
	window.document.frmedita.submit();
	}
function paginarf2308(){
	var aParametros=new Array();
	aParametros[99]=window.document.frmedita.debug.value;
	aParametros[101]=window.document.frmedita.paginaf2308.value;
	aParametros[102]=window.document.frmedita.lppf2308.value;
	aParametros[103]=window.document.frmedita.cara08idbloque.value;
	aParametros[104]=window.document.frmedita.bnombre.value;
	//document.getElementById('div_f2308detalle').innerHTML='<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf2308" name="paginaf2308" type="hidden" value="'+aParametros[101]+'" /><input id="lppf2308" name="lppf2308" type="hidden" value="'+aParametros[102]+'" />';
	xajax_f2308_HtmlTabla(aParametros);
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
	document.getElementById("cara08consec").focus();
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
	if (ref==2317){
		if (sRetorna!=''){
			window.document.frmedita.cara17idorigen.value=window.document.frmedita.div96v1.value;
			window.document.frmedita.cara17idanexo.value=sRetorna;
			verboton('beliminacara17idanexo','block');
			}
		archivo_lnk(window.document.frmedita.cara17idorigen.value, window.document.frmedita.cara17idanexo.value, 'div_cara17idanexo');
		paginarf2317();
		}
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
function verpregunta(id){
	window.document.frmvisor.id.value=id;
	window.document.frmvisor.submit();
	}
// -->
</script>
<?php
if ($_REQUEST['paso']!=0){
?>
<script language="javascript" src="jsi/js2309.js"></script>
<script language="javascript" src="jsi/js2317.js"></script>
<?php
	}
?>
<?php
if ($_REQUEST['paso']!=0){
?>
<form id="frmimpp" name="frmimpp" method="post" action="p2308.php" target="_blank">
<input id="r" name="r" type="hidden" value="2308" />
<input id="id2308" name="id2308" type="hidden" value="<?php echo $_REQUEST['cara08id']; ?>" />
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
<form id="frmvisor" name="frmvisor" method="post" action="verpregunta.php" target="_blank">
<input id="id" name="id" type="hidden" value="" />
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
echo '<h2>'.$ETI['titulo_2353'].'</h2>';
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
<input id="boculta2308" name="boculta2308" type="hidden" value="<?php echo $_REQUEST['boculta2308']; ?>" />
<label class="Label30">
<input id="btexpande2308" name="btexpande2308" type="button" value="Mostrar" class="btMiniExpandir" onclick="expandepanel(2308,'block',0);" title="<?php echo $ETI['bt_mostrar']; ?>" style="display:<?php if ($_REQUEST['boculta2308']==0){echo 'none'; }else{echo 'block';} ?>;"/>
</label>
<label class="Label30">
<input id="btrecoge2308" name="btrecoge2308" type="button" value="Ocultar" class="btMiniRecoger" onclick="expandepanel(2308,'none',1);" title="<?php echo $ETI['bt_ocultar']; ?>" style="display:<?php if ($_REQUEST['boculta2308']==0){echo 'block'; }else{echo 'none';} ?>;"/>
</label>
</div>
<div id="div_p2308" style="display:<?php if ($_REQUEST['boculta2308']==0){echo 'block'; }else{echo 'none';} ?>;">
<?php
	}
//Mostrar formulario para editar
?>
<input id="cara08idbloque" name="cara08idbloque" type="hidden" value="<?php echo $cara08idbloque; ?>"/>
<label class="Label90">
<?php
echo $ETI['cara08consec'];
?>
</label>
<label class="Label90">
<?php
if ($_REQUEST['paso']!=2){
?>
<input id="cara08consec" name="cara08consec" type="text" value="<?php echo $_REQUEST['cara08consec']; ?>" onchange="RevisaLlave()" class="cuatro"/>
<?php
	}else{
	echo html_oculto('cara08consec', $_REQUEST['cara08consec']);
	}
?>
</label>
<?php
/*
if ($seg_8==1){
	$objForma=new clsHtmlForma($iPiel);
	echo $objForma->htmlBotonSolo('cmdCambiaConsec', 'btMiniActualizar', 'expandesector(93);', $ETI['bt_cambiar'], 30);
	echo '<label class="Label30">&nbsp;</label>';
	}
*/
?>
<label class="Label60">
<?php
echo $ETI['cara08id'];
?>
</label>
<label class="Label60">
<?php
echo html_oculto('cara08id', $_REQUEST['cara08id']);
?>
</label>
<label class="Label60">
<?php
echo $ETI['cara08activa'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara08activa;
?>
</label>
<label class="Label60">
<?php
echo $ETI['cara08nivelpregunta'];
?>
</label>
<label class="Label160">
<?php
echo $html_cara08nivelpregunta;
?>
</label>
<label class="Label90">
<?php
echo $ETI['cara08idgrupo'];
?>
</label>
<label>
<?php
echo $html_cara08idgrupo;
?>
</label>
<label class="L">
<?php
echo $ETI['cara08titulo'];
?>

<input id="cara08titulo" name="cara08titulo" type="text" value="<?php echo $_REQUEST['cara08titulo']; ?>" maxlength="100" class="L" placeholder="<?php echo $ETI['ing_campo'].$ETI['cara08titulo']; ?>"/>
</label>
<label class="txtAreaM">
<?php
echo $ETI['cara08cuerpo'];
?>
<textarea id="cara08cuerpo" name="cara08cuerpo" placeholder="<?php echo $ETI['ing_campo'].$ETI['cara08cuerpo']; ?>"><?php echo $_REQUEST['cara08cuerpo']; ?></textarea>
</label>
<label class="Label160">
<?php
echo $ETI['cara08tipopreg'];
?>
</label>
<label>
<?php
echo $html_cara08tipopreg;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara08usosiniciales'];
?>
</label>
<label class="Label130">
<input id="cara08usosiniciales" name="cara08usosiniciales" type="text" value="<?php echo $_REQUEST['cara08usosiniciales']; ?>" class="cuatro" maxlength="10" placeholder="0"/>
</label>
<label class="Label130">
<?php
echo $ETI['cara08usostotales'];
?>
</label>
<label class="Label130">
<?php
echo html_oculto('cara08usostotales', $_REQUEST['cara08usostotales']);
?>
</label>
<?php
if ($_REQUEST['paso']==2){
	echo '<label class="Label130"><a href="javascript:verpregunta('.$_REQUEST['cara08id'].')" class="lnkresalte">'.$ETI['lnk_visor'].'</a></label>';
	}
// -- Inicia Grupo campos 2309 Respuestas
?>
<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo $ETI['titulo_2309'];
?>
</label>
<input id="boculta2309" name="boculta2309" type="hidden" value="<?php echo $_REQUEST['boculta2309']; ?>" />
<?php
if ($_REQUEST['paso']==2){
	//if ($bCondicion){
?>
<div class="ir_derecha" style="width:62px;">
<!--
<label class="Label30">
<input id="btexcel2309" name="btexcel2309" type="button" value="Exportar" class="btMiniExcel" onclick="imprime2309();" title="Exportar"/>
</label>
-->
<label class="Label30">
<input id="btexpande2309" name="btexpande2309" type="button" value="Expandir" class="btMiniExpandir" onclick="expandepanel(2309,'block',0);" title="<?php echo $ETI['bt_mostrar']; ?>" style="display:<?php if ($_REQUEST['boculta2309']==0){echo 'none'; }else{echo 'block';} ?>;"/>
</label>
<label class="Label30">
<input id="btrecoge2309" name="btrecoge2309" type="button" value="Recoger" class="btMiniRecoger" onclick="expandepanel(2309,'none',1);" title="<?php echo $ETI['bt_ocultar']; ?>" style="display:<?php if ($_REQUEST['boculta2309']==0){echo 'block'; }else{echo 'none';} ?>;"/>
</label>
</div>
<div class="salto1px"></div>
<div id="div_p2309" style="display:<?php if ($_REQUEST['boculta2309']==0){echo 'block'; }else{echo 'none';} ?>;">
<label class="Label90">
<?php
echo $ETI['cara09consec'];
?>
</label>
<label class="Label90"><div id="div_cara09consec">
<?php
if ((int)$_REQUEST['cara09id']==0){
?>
<input id="cara09consec" name="cara09consec" type="text" value="<?php echo $_REQUEST['cara09consec']; ?>" onchange="revisaf2309()" class="cuatro"/>
<?php
	}else{
	echo html_oculto('cara09consec', $_REQUEST['cara09consec']);
	}
?>
</div></label>
<label class="Label60">
<?php
echo $ETI['cara09id'];
?>
</label>
<label class="Label60"><div id="div_cara09id">
<?php
	echo html_oculto('cara09id', $_REQUEST['cara09id']);
?>
</div></label>
<label class="Label130">
<?php
echo $ETI['cara09valor'];
?>
</label>
<label class="Label130">
<input id="cara09valor" name="cara09valor" type="text" value="<?php echo $_REQUEST['cara09valor']; ?>" class="cuatro" maxlength="2" placeholder="0 - 10"/>
</label>
<label class="txtAreaS">
<?php
echo $ETI['cara09contenido'];
?>
<textarea id="cara09contenido" name="cara09contenido" placeholder="<?php echo $ETI['ing_campo'].$ETI['cara09contenido']; ?>"><?php echo $_REQUEST['cara09contenido']; ?></textarea>
</label>
<div class="salto1px"></div>
<label class="Label130">&nbsp;</label>
<label class="Label30">
<input id="bguarda2309" name="bguarda2309" type="button" value="Guardar" class="btMiniGuardar" onclick="guardaf2309()" title="<?php echo $ETI['bt_mini_guardar_2309']; ?>"/>
</label>
<label class="Label30">
<input id="blimpia2309" name="blimpia2309" type="button" value="Limpiar" class="btMiniLimpiar" onclick="limpiaf2309()" title="<?php echo $ETI['bt_mini_limpiar_2309']; ?>"/>
</label>
<label class="Label30">
<input id="belimina2309" name="belimina2309" type="button" value="Eliminar" class="btMiniEliminar" onclick="eliminaf2309()" title="<?php echo $ETI['bt_mini_eliminar_2309']; ?>" style="display:<?php if ((int)$_REQUEST['cara09id']!=0){echo 'block';}else{echo 'none';} ?>;"/>
</label>
<?php
//Este es el cierre del div_p2309
?>
<div class="salto1px"></div>
</div>
<?php
		//} //Termina el segundo bloque  condicional - bloque editar.
?>
<div class="salto1px"></div>
<?php
if (false){
?>
<div class="ir_derecha">
<label class="Label130">
Nombre
</label>
<label>
<input id="bnombre2309" name="bnombre2309" type="text" value="<?php echo $_REQUEST['bnombre2309']; ?>" onchange="paginarf2309()"/>
</label>
<label class="Label130">
Listar
</label>
<label>
<?php
echo $html_blistar2309;
?>
</label>
<div class="salto1px"></div>
</div>
<?php
	}
?>
<div id="div_f2309detalle">
<?php
echo $sTabla2309;
?>
</div>
<?php
	}
?>
<div class="salto1px"></div>
</div>
<?php
// -- Termina Grupo campos 2309 Respuestas
?>
<?php
// -- Inicia Grupo campos 2317 Anexos
?>
<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo $ETI['titulo_2317'];
?>
</label>
<input id="boculta2317" name="boculta2317" type="hidden" value="<?php echo $_REQUEST['boculta2317']; ?>" />
<?php
if ($_REQUEST['paso']==2){
	//if ($bCondicion){
?>
<div class="ir_derecha" style="width:62px;">
<!--
<label class="Label30">
<input id="btexcel2317" name="btexcel2317" type="button" value="Exportar" class="btMiniExcel" onclick="imprime2317();" title="Exportar"/>
</label>
-->
<label class="Label30">
<input id="btexpande2317" name="btexpande2317" type="button" value="Expandir" class="btMiniExpandir" onclick="expandepanel(2317,'block',0);" title="<?php echo $ETI['bt_mostrar']; ?>" style="display:<?php if ($_REQUEST['boculta2317']==0){echo 'none'; }else{echo 'block';} ?>;"/>
</label>
<label class="Label30">
<input id="btrecoge2317" name="btrecoge2317" type="button" value="Recoger" class="btMiniRecoger" onclick="expandepanel(2317,'none',1);" title="<?php echo $ETI['bt_ocultar']; ?>" style="display:<?php if ($_REQUEST['boculta2317']==0){echo 'block'; }else{echo 'none';} ?>;"/>
</label>
</div>
<div class="salto1px"></div>
<div id="div_p2317" style="display:<?php if ($_REQUEST['boculta2317']==0){echo 'block'; }else{echo 'none';} ?>;">
<label class="Label90">
<?php
echo $ETI['cara17consec'];
?>
</label>
<label class="Label90"><div id="div_cara17consec">
<?php
if ((int)$_REQUEST['cara17id']==0){
?>
<input id="cara17consec" name="cara17consec" type="text" value="<?php echo $_REQUEST['cara17consec']; ?>" onchange="revisaf2317()" class="cuatro"/>
<?php
	}else{
	echo html_oculto('cara17consec', $_REQUEST['cara17consec']);
	}
?>
</div></label>
<label class="Label60">
<?php
echo $ETI['cara17id'];
?>
</label>
<label class="Label60"><div id="div_cara17id">
<?php
	echo html_oculto('cara17id', $_REQUEST['cara17id']);
?>
</div></label>
<input id="cara17idorigen" name="cara17idorigen" type="hidden" value="<?php echo $_REQUEST['cara17idorigen']; ?>"/>
<input id="cara17idanexo" name="cara17idanexo" type="hidden" value="<?php echo $_REQUEST['cara17idanexo']; ?>"/>
<div class="GrupoCampos300">
<div class="salto1px"></div>
<div id="div_cara17idanexo" class="Campo220">
<?php
echo html_lnkarchivo((int)$_REQUEST['cara17idorigen'], (int)$_REQUEST['cara17idanexo']);
?>
</div>
<label class="Label30">
<input type="button" id="banexacara17idanexo" name="banexacara17idanexo" value="Anexar" class="btAnexarS" onclick="carga_cara17idanexo()" title="Cargar archivo" style="display:<?php if ((int)$_REQUEST['cara17id']!=0){echo 'block';}else{echo 'none';} ?>;"/>
</label>
<label class="Label30">
<input type="button" id="beliminacara17idanexo" name="beliminacara17idanexo" value="Eliminar" class="btBorrarS" onclick="eliminacara17idanexo()" title="Eliminar archivo" style="display:<?php if ((int)$_REQUEST['cara17idanexo']!=0){echo 'block';}else{echo 'none';} ?>;"/>
</label>
<div class="salto1px"></div>
</div>
<label class="L">
<?php
echo $ETI['cara17nombre'];
?>

<input id="cara17nombre" name="cara17nombre" type="text" value="<?php echo $_REQUEST['cara17nombre']; ?>" maxlength="100" class="L" placeholder="<?php echo $ETI['ing_campo'].$ETI['cara17nombre']; ?>"/>
</label>
<div class="salto1px"></div>
<label class="Label130">&nbsp;</label>
<label class="Label30">
<input id="bguarda2317" name="bguarda2317" type="button" value="Guardar" class="btMiniGuardar" onclick="guardaf2317()" title="<?php echo $ETI['bt_mini_guardar_2317']; ?>"/>
</label>
<label class="Label30">
<input id="blimpia2317" name="blimpia2317" type="button" value="Limpiar" class="btMiniLimpiar" onclick="limpiaf2317()" title="<?php echo $ETI['bt_mini_limpiar_2317']; ?>"/>
</label>
<label class="Label30">
<input id="belimina2317" name="belimina2317" type="button" value="Eliminar" class="btMiniEliminar" onclick="eliminaf2317()" title="<?php echo $ETI['bt_mini_eliminar_2317']; ?>" style="display:<?php if ((int)$_REQUEST['cara17id']!=0){echo 'block';}else{echo 'none';} ?>;"/>
</label>
<?php
//Este es el cierre del div_p2317
?>
<div class="salto1px"></div>
</div>
<?php
		//} //Termina el segundo bloque  condicional - bloque editar.
?>
<div class="salto1px"></div>
<?php
if (false){
?>
<div class="ir_derecha">
<label class="Label130">
Nombre
</label>
<label>
<input id="bnombre2317" name="bnombre2317" type="text" value="<?php echo $_REQUEST['bnombre2317']; ?>" onchange="paginarf2317()"/>
</label>
<label class="Label130">
Listar
</label>
<label>
<?php
echo $html_blistar2317;
?>
</label>
<div class="salto1px"></div>
</div>
<?php
	}
?>
<div id="div_f2317detalle">
<?php
echo $sTabla2317;
?>
</div>
<?php
	}
?>
<div class="salto1px"></div>
</div>
<?php
// -- Termina Grupo campos 2317 Anexos
?>
<?php
if (false){
	//Ejemplo de boton de ayuda
	//echo html_BotonAyuda('NombreCampo');
	//echo html_DivAyudaLocal('NombreCampo');
	}
if ($bconexpande){
	//Este es el cierre del div_p2308
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
Titulo
</label>
<label>
<input id="bnombre" name="bnombre" type="text" value="<?php echo $_REQUEST['bnombre']; ?>" onchange="paginarf2308()" autocomplete="off"/>
</label>
<?php
if (false){
?>
<label class="Label90">
Listar
</label>
<label class="Label130">
<?php
echo $html_blistar;
?>
</label>
<?php
	}
?>
</div>
<div class="salto1px"></div>
<?php
echo ' '.$csv_separa;
?>
<div id="div_f2308detalle">
<?php
echo $sTabla2308;
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
echo $ETI['msg_cara08consec'];
?>
</label>
<label class="Label90">
<?php
echo '<b>'.$_REQUEST['cara08consec'].'</b>';
?>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['msg_cara08consec_nuevo'];
// onchange="RevisaConsec()"
?>
</label>
<label class="Label90">
<input id="cara08consec_nuevo" name="cara08consec_nuevo" type="text" value="<?php echo $_REQUEST['cara08consec_nuevo']; ?>" class="cuatro"/>
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
<input id="titulo_2308" name="titulo_2308" type="hidden" value="<?php echo $ETI['titulo_2353']; ?>" />
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
echo '<h2>'.$ETI['titulo_2353'].'</h2>';
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
<script language="javascript" src="<?php echo $APP->rutacomun; ?>js/jquery-3.3.1.min.js"></script>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>js/popper.min.js"></script>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>js/bootstrap.min.js"></script>
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>js/bootstrap.min.css" type="text/css"/>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>unad_todas.js?ver=8"></script>
<?php
forma_piedepagina();
?>