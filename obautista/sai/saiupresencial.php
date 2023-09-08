<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2023 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.29.6 lunes, 31 de julio de 2023
*/
/** Archivo saiupresencial.php.
 * Modulo 3021 saiu21directa.
 * @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
 * @param debug = 1  (Opcional), bandera para indicar si se generan datos de depuración
 * @date lunes, 31 de julio de 2023
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
require $APP->rutacomun.'libsai.php';
require $APP->rutacomun.'libtiempo.php';
if (($bPeticionXAJAX)&&($_SESSION['unad_id_tercero']==0)){
	// viene por xajax.
	$xajax=new xajax();
	$xajax->configure('javascript URI', $APP->rutacomun.'xajax/');
	$xajax->register(XAJAX_FUNCTION,'sesion_abandona_V2');
	$xajax->processRequest();
	die();
	}
$grupo_id=1;//Necesita ajustarlo...
$iCodModulo = 3021;
$audita[1]=false;
$audita[2]=true;
$audita[3]=true;
$audita[4]=true;
$audita[5]=false;
// -- Se cargan los archivos de idioma
$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
$mensajes_3021='lg/lg_3021_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_3021)){$mensajes_3021='lg/lg_3021_es.php';}
require $mensajes_todas;
require $mensajes_3021;
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
		header('Location:noticia.php?ret=saiupresencial.php');
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
$mensajes_3000=$APP->rutacomun.'lg/lg_3000_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_3000)){$mensajes_3000=$APP->rutacomun.'lg/lg_3000_es.php';}
require $mensajes_3000;
// -- Si esta cargando la pagina por primer vez se revisa si requiere auditar y se manda a hacer un limpiar (paso -1)
if (isset($_REQUEST['paso'])==0){
	$_REQUEST['paso']=-1;
	if ($audita[1]){seg_auditaingreso($iCodModulo, $_SESSION['unad_id_tercero'], $objDB);}
	}
// -- 3021 saiu21directa
require 'lib3021.php';
// -- 3000 Historial de solicitudes
require $APP->rutacomun.'lib3000.php';
$xajax=new xajax();
$xajax->configure('javascript URI', $APP->rutacomun.'xajax/');
$xajax->register(XAJAX_FUNCTION,'unad11_Mostrar_v2');
$xajax->register(XAJAX_FUNCTION,'unad11_Mostrar_v2SAI');
$xajax->register(XAJAX_FUNCTION,'unad11_TraerXid');
$xajax->register(XAJAX_FUNCTION,'unad11_TraerXidSAI');
$xajax->register(XAJAX_FUNCTION,'f3021_Combosaiu21tiposolicitud');
$xajax->register(XAJAX_FUNCTION,'f3021_Combosaiu21temasolicitud');
$xajax->register(XAJAX_FUNCTION,'f3021_Combosaiu21idcentro');
$xajax->register(XAJAX_FUNCTION,'f3021_Combosaiu21coddepto');
$xajax->register(XAJAX_FUNCTION,'f3021_Combosaiu21codciudad');
$xajax->register(XAJAX_FUNCTION,'f3021_Combosaiu21idprograma');
$xajax->register(XAJAX_FUNCTION,'sesion_abandona_V2');
$xajax->register(XAJAX_FUNCTION,'sesion_mantenerV4');
$xajax->register(XAJAX_FUNCTION,'f3021_HtmlTabla');
$xajax->register(XAJAX_FUNCTION,'f3021_ExisteDato');
$xajax->register(XAJAX_FUNCTION,'f3021_Busquedas');
$xajax->register(XAJAX_FUNCTION,'f3021_HtmlBusqueda');
$xajax->register(XAJAX_FUNCTION,'f3000_HtmlTabla');
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
if (isset($_REQUEST['paginaf3018'])==0){$_REQUEST['paginaf3021']=1;}
if (isset($_REQUEST['lppf3018'])==0){$_REQUEST['lppf3021']=20;}
if (isset($_REQUEST['boculta3018'])==0){$_REQUEST['boculta3021']=0;}
if (isset($_REQUEST['paginaf3000'])==0){$_REQUEST['paginaf3000']=1;}
if (isset($_REQUEST['lppf3000'])==0){$_REQUEST['lppf3000']=10;}
if (isset($_REQUEST['boculta3000'])==0){$_REQUEST['boculta3000']=0;}
// -- Inicializar variables de datos.
if (isset($_REQUEST['saiu21agno']) == 0) {
	$_REQUEST['saiu21agno'] = 0;
}
if (isset($_REQUEST['saiu21mes']) == 0) {
	$_REQUEST['saiu21mes'] = 0;
}
if (isset($_REQUEST['saiu21tiporadicado']) == 0) {
	$_REQUEST['saiu21tiporadicado'] = 0;
}
if (isset($_REQUEST['saiu21consec']) == 0) {
	$_REQUEST['saiu21consec'] = '';
}
if (isset($_REQUEST['saiu21consec_nuevo']) == 0) {
	$_REQUEST['saiu21consec_nuevo'] = '';
}
if (isset($_REQUEST['saiu21id']) == 0) {
	$_REQUEST['saiu21id'] = '';
}
if (isset($_REQUEST['saiu21origenagno']) == 0) {
	$_REQUEST['saiu21origenagno'] = '';
}
if (isset($_REQUEST['saiu21origenmes']) == 0) {
	$_REQUEST['saiu21origenmes'] = '';
}
if (isset($_REQUEST['saiu21origenid']) == 0) {
	$_REQUEST['saiu21origenid'] = 0;
}
if (isset($_REQUEST['saiu21dia']) == 0) {
	$_REQUEST['saiu21dia'] = '';
}
if (isset($_REQUEST['saiu21hora']) == 0) {
	$_REQUEST['saiu21hora'] = fecha_hora();
}
if (isset($_REQUEST['saiu21minuto']) == 0) {
	$_REQUEST['saiu21minuto'] = fecha_minuto();
}
if (isset($_REQUEST['saiu21estado']) == 0) {
	$_REQUEST['saiu21estado'] = 0;
}
if (isset($_REQUEST['saiu21idcorreo']) == 0) {
	$_REQUEST['saiu21idcorreo'] = '';
}
if (isset($_REQUEST['saiu21idsolicitante']) == 0) {
	$_REQUEST['saiu21idsolicitante'] = 0;
	//$_REQUEST['saiu21idsolicitante'] = $idTercero;
}
if (isset($_REQUEST['saiu21idsolicitante_td']) == 0) {
	$_REQUEST['saiu21idsolicitante_td'] = $APP->tipo_doc;
}
if (isset($_REQUEST['saiu21idsolicitante_doc']) == 0) {
	$_REQUEST['saiu21idsolicitante_doc'] = '';
}
if (isset($_REQUEST['saiu21tipointeresado']) == 0) {
	$_REQUEST['saiu21tipointeresado'] = '';
}
if (isset($_REQUEST['saiu21clasesolicitud']) == 0) {
	$_REQUEST['saiu21clasesolicitud'] = '';
}
if (isset($_REQUEST['saiu21tiposolicitud']) == 0) {
	$_REQUEST['saiu21tiposolicitud'] = '';
}
if (isset($_REQUEST['saiu21temasolicitud']) == 0) {
	$_REQUEST['saiu21temasolicitud'] = '';
}
if (isset($_REQUEST['saiu21idzona']) == 0) {
	$_REQUEST['saiu21idzona'] = '';
}
if (isset($_REQUEST['saiu21idcentro']) == 0) {
	$_REQUEST['saiu21idcentro'] = '';
}
if (isset($_REQUEST['saiu21codpais']) == 0) {
	$_REQUEST['saiu21codpais'] = $_SESSION['unad_pais'];
}
if (isset($_REQUEST['saiu21coddepto']) == 0) {
	$_REQUEST['saiu21coddepto'] = '';
}
if (isset($_REQUEST['saiu21codciudad']) == 0) {
	$_REQUEST['saiu21codciudad'] = '';
}
if (isset($_REQUEST['saiu21idescuela']) == 0) {
	$_REQUEST['saiu21idescuela'] = '';
}
if (isset($_REQUEST['saiu21idprograma']) == 0) {
	$_REQUEST['saiu21idprograma'] = '';
}
if (isset($_REQUEST['saiu21idperiodo']) == 0) {
	$_REQUEST['saiu21idperiodo'] = '';
}
if (isset($_REQUEST['saiu21numorigen']) == 0) {
	$_REQUEST['saiu21numorigen'] = '';
}
if (isset($_REQUEST['saiu21idpqrs']) == 0) {
	$_REQUEST['saiu21idpqrs'] = 0;
}
if (isset($_REQUEST['saiu21detalle']) == 0) {
	$_REQUEST['saiu21detalle'] = '';
}
if (isset($_REQUEST['saiu21horafin']) == 0) {
	$_REQUEST['saiu21horafin'] = fecha_hora();
}
if (isset($_REQUEST['saiu21minutofin']) == 0) {
	$_REQUEST['saiu21minutofin'] = fecha_minuto();
}
if (isset($_REQUEST['saiu21paramercadeo']) == 0) {
	$_REQUEST['saiu21paramercadeo'] = '';
}
if (isset($_REQUEST['saiu21idresponsable']) == 0) {
	$_REQUEST['saiu21idresponsable'] = 0;
	//$_REQUEST['saiu21idresponsable'] = $idTercero;
}
if (isset($_REQUEST['saiu21idresponsable_td']) == 0) {
	$_REQUEST['saiu21idresponsable_td'] = $APP->tipo_doc;
}
if (isset($_REQUEST['saiu21idresponsable_doc']) == 0) {
	$_REQUEST['saiu21idresponsable_doc'] = '';
}
if (isset($_REQUEST['saiu21tiemprespdias']) == 0) {
	$_REQUEST['saiu21tiemprespdias'] = '';
}
if (isset($_REQUEST['saiu21tiempresphoras']) == 0) {
	$_REQUEST['saiu21tiempresphoras'] = '';
}
if (isset($_REQUEST['saiu21tiemprespminutos']) == 0) {
	$_REQUEST['saiu21tiemprespminutos'] = '';
}
if (isset($_REQUEST['saiu21solucion']) == 0) {
	$_REQUEST['saiu21solucion'] = '';
}
if (isset($_REQUEST['saiu21idcaso']) == 0) {
	$_REQUEST['saiu21idcaso'] = 0;
}
$_REQUEST['saiu21agno'] = numeros_validar($_REQUEST['saiu21agno']);
$_REQUEST['saiu21mes'] = numeros_validar($_REQUEST['saiu21mes']);
$_REQUEST['saiu21tiporadicado'] = numeros_validar($_REQUEST['saiu21tiporadicado']);
$_REQUEST['saiu21consec'] = numeros_validar($_REQUEST['saiu21consec']);
$_REQUEST['saiu21id'] = numeros_validar($_REQUEST['saiu21id']);
$_REQUEST['saiu21origenagno'] = numeros_validar($_REQUEST['saiu21origenagno']);
$_REQUEST['saiu21origenmes'] = numeros_validar($_REQUEST['saiu21origenmes']);
$_REQUEST['saiu21origenid'] = numeros_validar($_REQUEST['saiu21origenid']);
$_REQUEST['saiu21dia'] = numeros_validar($_REQUEST['saiu21dia']);
$_REQUEST['saiu21hora'] = numeros_validar($_REQUEST['saiu21hora']);
$_REQUEST['saiu21minuto'] = numeros_validar($_REQUEST['saiu21minuto']);
$_REQUEST['saiu21estado'] = numeros_validar($_REQUEST['saiu21estado']);
$_REQUEST['saiu21idcorreo'] = numeros_validar($_REQUEST['saiu21idcorreo']);
$_REQUEST['saiu21idsolicitante'] = numeros_validar($_REQUEST['saiu21idsolicitante']);
$_REQUEST['saiu21idsolicitante_td'] = cadena_Validar($_REQUEST['saiu21idsolicitante_td']);
$_REQUEST['saiu21idsolicitante_doc'] = cadena_Validar($_REQUEST['saiu21idsolicitante_doc']);
$_REQUEST['saiu21tipointeresado'] = numeros_validar($_REQUEST['saiu21tipointeresado']);
$_REQUEST['saiu21clasesolicitud'] = numeros_validar($_REQUEST['saiu21clasesolicitud']);
$_REQUEST['saiu21tiposolicitud'] = numeros_validar($_REQUEST['saiu21tiposolicitud']);
$_REQUEST['saiu21temasolicitud'] = numeros_validar($_REQUEST['saiu21temasolicitud']);
$_REQUEST['saiu21idzona'] = numeros_validar($_REQUEST['saiu21idzona']);
$_REQUEST['saiu21idcentro'] = numeros_validar($_REQUEST['saiu21idcentro']);
$_REQUEST['saiu21codpais'] = cadena_Validar($_REQUEST['saiu21codpais']);
$_REQUEST['saiu21coddepto'] = cadena_Validar($_REQUEST['saiu21coddepto']);
$_REQUEST['saiu21codciudad'] = cadena_Validar($_REQUEST['saiu21codciudad']);
$_REQUEST['saiu21idescuela'] = numeros_validar($_REQUEST['saiu21idescuela']);
$_REQUEST['saiu21idprograma'] = numeros_validar($_REQUEST['saiu21idprograma']);
$_REQUEST['saiu21idperiodo'] = numeros_validar($_REQUEST['saiu21idperiodo']);
$_REQUEST['saiu21numorigen'] = cadena_Validar($_REQUEST['saiu21numorigen']);
$_REQUEST['saiu21idpqrs'] = numeros_validar($_REQUEST['saiu21idpqrs']);
$_REQUEST['saiu21detalle'] = cadena_Validar($_REQUEST['saiu21detalle']);
$_REQUEST['saiu21horafin'] = numeros_validar($_REQUEST['saiu21horafin']);
$_REQUEST['saiu21minutofin'] = numeros_validar($_REQUEST['saiu21minutofin']);
$_REQUEST['saiu21paramercadeo'] = numeros_validar($_REQUEST['saiu21paramercadeo']);
$_REQUEST['saiu21idresponsable'] = numeros_validar($_REQUEST['saiu21idresponsable']);
$_REQUEST['saiu21idresponsable_td'] = cadena_Validar($_REQUEST['saiu21idresponsable_td']);
$_REQUEST['saiu21idresponsable_doc'] = cadena_Validar($_REQUEST['saiu21idresponsable_doc']);
$_REQUEST['saiu21tiemprespdias'] = numeros_validar($_REQUEST['saiu21tiemprespdias']);
$_REQUEST['saiu21tiempresphoras'] = numeros_validar($_REQUEST['saiu21tiempresphoras']);
$_REQUEST['saiu21tiemprespminutos'] = numeros_validar($_REQUEST['saiu21tiemprespminutos']);
$_REQUEST['saiu21solucion'] = numeros_validar($_REQUEST['saiu21solucion']);
$_REQUEST['saiu21idcaso'] = numeros_validar($_REQUEST['saiu21idcaso']);
// Espacio para inicializar otras variables
if (isset($_REQUEST['csv_separa']) == 0) {
	$_REQUEST['csv_separa'] = ';';
}
if (isset($_REQUEST['bnombre']) == 0) {
	$_REQUEST['bnombre'] = '';
}
/*
if (isset($_REQUEST['blistar']) == 0) {
	$_REQUEST['blistar'] = '';
}
*/
//Si Modifica o Elimina Cargar los campos
if (($_REQUEST['paso'] == 1) || ($_REQUEST['paso'] == 3)) {
	$_REQUEST['saiu21idsolicitante_td'] = $APP->tipo_doc;
	$_REQUEST['saiu21idsolicitante_doc'] = '';
	$_REQUEST['saiu21idresponsable_td'] = $APP->tipo_doc;
	$_REQUEST['saiu21idresponsable_doc'] = '';
	if ($_REQUEST['paso'] == 1) {
		$sSQLcondi = 'saiu21agno=' . $_REQUEST['saiu21agno'] . ' AND saiu21mes=' . $_REQUEST['saiu21mes'] . ' AND saiu21tiporadicado=' . $_REQUEST['saiu21tiporadicado'] . ' AND saiu21consec=' . $_REQUEST['saiu21consec'] . '';
	} else {
		$sSQLcondi = 'saiu21id=' . $_REQUEST['saiu21id'] . '';
	}
	$sSQL = 'SELECT * FROM saiu21directa WHERE ' . $sSQLcondi;
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$_REQUEST['saiu21agno'] = $fila['saiu21agno'];
		$_REQUEST['saiu21mes'] = $fila['saiu21mes'];
		$_REQUEST['saiu21tiporadicado'] = $fila['saiu21tiporadicado'];
		$_REQUEST['saiu21consec'] = $fila['saiu21consec'];
		$_REQUEST['saiu21id'] = $fila['saiu21id'];
		$_REQUEST['saiu21origenagno'] = $fila['saiu21origenagno'];
		$_REQUEST['saiu21origenmes'] = $fila['saiu21origenmes'];
		$_REQUEST['saiu21origenid'] = $fila['saiu21origenid'];
		$_REQUEST['saiu21dia'] = $fila['saiu21dia'];
		$_REQUEST['saiu21hora'] = $fila['saiu21hora'];
		$_REQUEST['saiu21minuto'] = $fila['saiu21minuto'];
		$_REQUEST['saiu21estado'] = $fila['saiu21estado'];
		$_REQUEST['saiu21idcorreo'] = $fila['saiu21idcorreo'];
		$_REQUEST['saiu21idsolicitante'] = $fila['saiu21idsolicitante'];
		$_REQUEST['saiu21tipointeresado'] = $fila['saiu21tipointeresado'];
		$_REQUEST['saiu21clasesolicitud'] = $fila['saiu21clasesolicitud'];
		$_REQUEST['saiu21tiposolicitud'] = $fila['saiu21tiposolicitud'];
		$_REQUEST['saiu21temasolicitud'] = $fila['saiu21temasolicitud'];
		$_REQUEST['saiu21idzona'] = $fila['saiu21idzona'];
		$_REQUEST['saiu21idcentro'] = $fila['saiu21idcentro'];
		$_REQUEST['saiu21codpais'] = $fila['saiu21codpais'];
		$_REQUEST['saiu21coddepto'] = $fila['saiu21coddepto'];
		$_REQUEST['saiu21codciudad'] = $fila['saiu21codciudad'];
		$_REQUEST['saiu21idescuela'] = $fila['saiu21idescuela'];
		$_REQUEST['saiu21idprograma'] = $fila['saiu21idprograma'];
		$_REQUEST['saiu21idperiodo'] = $fila['saiu21idperiodo'];
		$_REQUEST['saiu21numorigen'] = $fila['saiu21numorigen'];
		$_REQUEST['saiu21idpqrs'] = $fila['saiu21idpqrs'];
		$_REQUEST['saiu21detalle'] = $fila['saiu21detalle'];
		$_REQUEST['saiu21horafin'] = $fila['saiu21horafin'];
		$_REQUEST['saiu21minutofin'] = $fila['saiu21minutofin'];
		$_REQUEST['saiu21paramercadeo'] = $fila['saiu21paramercadeo'];
		$_REQUEST['saiu21idresponsable'] = $fila['saiu21idresponsable'];
		$_REQUEST['saiu21tiemprespdias'] = $fila['saiu21tiemprespdias'];
		$_REQUEST['saiu21tiempresphoras'] = $fila['saiu21tiempresphoras'];
		$_REQUEST['saiu21tiemprespminutos'] = $fila['saiu21tiemprespminutos'];
		$_REQUEST['saiu21solucion'] = $fila['saiu21solucion'];
		$_REQUEST['saiu21idcaso'] = $fila['saiu21idcaso'];
		$bcargo = true;
		$_REQUEST['paso'] = 2;
		$_REQUEST['boculta3021'] = 0;
		$bLimpiaHijos = true;
	} else {
		$_REQUEST['paso'] = 0;
	}
}
//Insertar o modificar un elemento
if (($_REQUEST['paso'] == 10) || ($_REQUEST['paso'] == 12)) {
	$bMueveScroll = true;
	list($_REQUEST, $sError, $iTipoError, $sDebugGuardar) = f3021_db_GuardarV2($_REQUEST, $objDB, $bDebug, $idTercero);
	$sDebug = $sDebug . $sDebugGuardar;
	if ($sError == '') {
		$sError = '<b>' . $ETI['msg_itemguardado'] . '</b>';
		$iTipoError = 1;
	}
}
// Cambio de consecutivo.
if ($_REQUEST['paso'] == 93) {
	$_REQUEST['paso'] = 2;
	$_REQUEST['saiu21consec_nuevo'] = numeros_validar($_REQUEST['saiu21consec_nuevo']);
	if ($_REQUEST['saiu21consec_nuevo'] == '') {
		$sError = $ERR['saiu21consec'];
	}
	if ($sError == '') {
		list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 8, $idTercero, $objDB);
		if (!$bDevuelve) {
			$sError = $ERR['8'];
		}
	}
	if ($sError == '') {
		//Ver que el consecutivo no exista.
		$sSQL = 'SELECT saiu21id FROM saiu21directa WHERE saiu21consec=' . $_REQUEST['saiu21consec_nuevo'] . ' AND saiu21tiporadicado=' . $_REQUEST['saiu21tiporadicado'] . ' AND saiu21mes=' . $_REQUEST['saiu21mes'] . ' AND saiu21agno=' . $_REQUEST['saiu21agno'] . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$sError = 'El consecutivo ' . $_REQUEST['saiu21consec_nuevo'] . ' ya existe';
		}
	}
	if ($sError == '') {
		//Aplicar el cambio.
		$sSQL = 'UPDATE saiu21directa SET saiu21consec=' . $_REQUEST['saiu21consec_nuevo'] . ' WHERE saiu21id=' . $_REQUEST['saiu21id'] . '';
		$tabla = $objDB->ejecutasql($sSQL);
		$sDetalle = 'Cambia el consecutivo de ' . $_REQUEST['saiu21consec'] . ' a ' . $_REQUEST['saiu21consec_nuevo'] . '';
		$_REQUEST['saiu21consec'] = $_REQUEST['saiu21consec_nuevo'];
		$_REQUEST['saiu21consec_nuevo'] = '';
		seg_auditar($iCodModulo, $_SESSION['u_idtercero'], 8, $_REQUEST['saiu21id'], $sDetalle, $objDB);
		$sError = '<b>Se ha aplicado el cambio de consecutivo.</b>';
		$iTipoError = 1;
	} else {
		$iSector = 93;
	}
}
//Eliminar un elemento
if ($_REQUEST['paso'] == 13) {
	$_REQUEST['paso'] = 2;
	if ($sError == '') {
		list($sError, $iTipoError, $sDebugElimina) = f3021_db_Eliminar($_REQUEST['saiu21id'], $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugElimina;
	}
	if ($sError == '') {
		$_REQUEST['paso'] = -1;
		$sError = $ETI['msg_itemeliminado'];
		$iTipoError = 1;
	}
}
//limpiar la pantalla
if ($_REQUEST['paso'] == -1) {
	$_REQUEST['saiu21agno'] = 0;
	$_REQUEST['saiu21mes'] = 0;
	$_REQUEST['saiu21tiporadicado'] = 0;
	$_REQUEST['saiu21consec'] = '';
	$_REQUEST['saiu21consec_nuevo'] = '';
	$_REQUEST['saiu21id'] = '';
	$_REQUEST['saiu21origenagno'] = '';
	$_REQUEST['saiu21origenmes'] = '';
	$_REQUEST['saiu21origenid'] = 0;
	$_REQUEST['saiu21dia'] = '';
	$_REQUEST['saiu21hora'] = fecha_hora();
	$_REQUEST['saiu21minuto'] = fecha_minuto();
	$_REQUEST['saiu21estado'] = 0;
	$_REQUEST['saiu21idcorreo'] = '';
	$_REQUEST['saiu21idsolicitante'] = 0; //$idTercero;
	$_REQUEST['saiu21idsolicitante_td'] = $APP->tipo_doc;
	$_REQUEST['saiu21idsolicitante_doc'] = '';
	$_REQUEST['saiu21tipointeresado'] = '';
	$_REQUEST['saiu21clasesolicitud'] = '';
	$_REQUEST['saiu21tiposolicitud'] = '';
	$_REQUEST['saiu21temasolicitud'] = '';
	$_REQUEST['saiu21idzona'] = '';
	$_REQUEST['saiu21idcentro'] = '';
	$_REQUEST['saiu21codpais'] = $_SESSION['unad_pais'];
	$_REQUEST['saiu21coddepto'] = '';
	$_REQUEST['saiu21codciudad'] = '';
	$_REQUEST['saiu21idescuela'] = '';
	$_REQUEST['saiu21idprograma'] = '';
	$_REQUEST['saiu21idperiodo'] = '';
	$_REQUEST['saiu21numorigen'] = '';
	$_REQUEST['saiu21idpqrs'] = 0;
	$_REQUEST['saiu21detalle'] = '';
	$_REQUEST['saiu21horafin'] = fecha_hora();
	$_REQUEST['saiu21minutofin'] = fecha_minuto();
	$_REQUEST['saiu21paramercadeo'] = 0;
	$_REQUEST['saiu21idresponsable'] = 0; //$idTercero;
	$_REQUEST['saiu21idresponsable_td'] = $APP->tipo_doc;
	$_REQUEST['saiu21idresponsable_doc'] = '';
	$_REQUEST['saiu21tiemprespdias'] = '';
	$_REQUEST['saiu21tiempresphoras'] = '';
	$_REQUEST['saiu21tiemprespminutos'] = '';
	$_REQUEST['saiu21solucion'] = 0;
	$_REQUEST['saiu21idcaso'] = 0;
	$_REQUEST['paso'] = 0;
}
if ($bLimpiaHijos) {
}
//AQUI SE DEBEN CARGAR TODOS LOS DATOS QUE LA FORMA NECESITE.
$bPuedeGuardar = true;
//DATOS PARA COMPLETAR EL FORMULARIO
$sNombreUsuario = '';
if ($seg_1707 == 1) {
	$sSQL = 'SELECT unad11razonsocial FROM unad11terceros WHERE unad11id=' . $idTercero . '';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$sNombreUsuario = cadena_notildes($fila['unad11razonsocial']);
	}
}
//Permisos adicionales
$seg_5 = 0;
$seg_6 = 0;
$seg_8 = 0;
/*
list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 6, $idTercero, $objDB);
if ($bDevuelve) {
	$seg_6 = 1;
}
*/
//Crear los controles que requieran llamado a base de datos
$objCombos = new clsHtmlCombos();
$objTercero = new clsHtmlTercero();
$saiu21estado_nombre = '&nbsp;';
if ((int)$_REQUEST['saiu21estado'] != 0) {
	list($saiu21estado_nombre, $sErrorDet) = tabla_campoxid('saiu11estadosol', 'saiu11nombre', 'saiu11id', $_REQUEST['saiu21estado'], '{' . $ETI['msg_sindato'] . '}', $objDB);
}
$html_saiu21estado = html_oculto('saiu21estado', $_REQUEST['saiu21estado'], $saiu21estado_nombre);
$objCombos->nuevo('saiu21idcorreo', $_REQUEST['saiu21idcorreo'], true, '{' . $ETI['msg_seleccione'] . '}');
$sSQL = 'SELECT saiu57id AS id, saiu57titulo AS nombre FROM saiu57correos ORDER BY saiu57titulo';
$html_saiu21idcorreo = $objCombos->html($sSQL, $objDB);
list($saiu21idsolicitante_rs, $_REQUEST['saiu21idsolicitante'], $_REQUEST['saiu21idsolicitante_td'], $_REQUEST['saiu21idsolicitante_doc']) = html_tercero($_REQUEST['saiu21idsolicitante_td'], $_REQUEST['saiu21idsolicitante_doc'], $_REQUEST['saiu21idsolicitante'], 0, $objDB);
$objCombos->nuevo('saiu21tipointeresado', $_REQUEST['saiu21tipointeresado'], true, '{' . $ETI['msg_seleccione'] . '}');
$sSQL = 'SELECT bita07id AS id, bita07nombre AS nombre FROM bita07tiposolicitante ORDER BY bita07nombre';
$html_saiu21tipointeresado = $objCombos->html($sSQL, $objDB);
$objCombos->nuevo('saiu21tiposolicitud', $_REQUEST['saiu21tiposolicitud'], true, '{' . $ETI['msg_seleccione'] . '}');
$sSQL = 'SELECT saiu02id AS id, saiu02titulo AS nombre FROM saiu02tiposol ORDER BY saiu02titulo';
$html_saiu21tiposolicitud = $objCombos->html($sSQL, $objDB);
$objCombos->nuevo('saiu21temasolicitud', $_REQUEST['saiu21temasolicitud'], true, '{' . $ETI['msg_seleccione'] . '}');
$sSQL = 'SELECT saiu03id AS id, saiu03titulo AS nombre FROM saiu03temasol ORDER BY saiu03titulo';
$html_saiu21temasolicitud = $objCombos->html($sSQL, $objDB);
$objCombos->nuevo('saiu21idzona', $_REQUEST['saiu21idzona'], true, '{' . $ETI['msg_seleccione'] . '}');
$objCombos->sAccion = 'carga_combo_saiu21idcentro();';
$sSQL = 'SELECT unad23id AS id, unad23nombre AS nombre FROM unad23zona ORDER BY unad23nombre';
$html_saiu21idzona = $objCombos->html($sSQL, $objDB);
$html_saiu21idcentro = f3021_HTMLComboV2_saiu21idcentro($objDB, $objCombos, $_REQUEST['saiu21idcentro'], $_REQUEST['saiu21idzona']);
$objCombos->nuevo('saiu21codpais', $_REQUEST['saiu21codpais'], true, '{' . $ETI['msg_seleccione'] . '}');
$objCombos->sAccion = 'carga_combo_saiu21coddepto();';
$sSQL = 'SELECT unad18codigo AS id, unad18nombre AS nombre FROM unad18pais ORDER BY unad18nombre';
$html_saiu21codpais = $objCombos->html($sSQL, $objDB);
$html_saiu21coddepto = f3021_HTMLComboV2_saiu21coddepto($objDB, $objCombos, $_REQUEST['saiu21coddepto'], $_REQUEST['saiu21codpais']);
$html_saiu21codciudad = f3021_HTMLComboV2_saiu21codciudad($objDB, $objCombos, $_REQUEST['saiu21codciudad'], $_REQUEST['saiu21coddepto']);
$objCombos->nuevo('saiu21idescuela', $_REQUEST['saiu21idescuela'], true, '{' . $ETI['msg_seleccione'] . '}');
$objCombos->sAccion = 'carga_combo_saiu21idprograma();';
$sSQL = 'SELECT core12id AS id, core12nombre AS nombre FROM core12escuela ORDER BY core12nombre';
$html_saiu21idescuela = $objCombos->html($sSQL, $objDB);
$html_saiu21idprograma = f3021_HTMLComboV2_saiu21idprograma($objDB, $objCombos, $_REQUEST['saiu21idprograma'], $_REQUEST['saiu21idescuela']);
$objCombos->nuevo('saiu21idperiodo', $_REQUEST['saiu21idperiodo'], true, '{' . $ETI['msg_seleccione'] . '}');
$sSQL = 'SELECT exte02id AS id, exte02nombre AS nombre FROM exte02per_aca ORDER BY exte02nombre';
$html_saiu21idperiodo = $objCombos->html($sSQL, $objDB);
$objCombos->nuevo('saiu21paramercadeo', $_REQUEST['saiu21paramercadeo'], true, $ETI['no'], 0);
$objCombos->addItem(1, $ETI['si']);
//$objCombos->addArreglo($asaiu21paramercadeo, $isaiu21paramercadeo);
$sSQL = '';
$html_saiu21paramercadeo = $objCombos->html($sSQL, $objDB);
list($saiu21idresponsable_rs, $_REQUEST['saiu21idresponsable'], $_REQUEST['saiu21idresponsable_td'], $_REQUEST['saiu21idresponsable_doc']) = html_tercero($_REQUEST['saiu21idresponsable_td'], $_REQUEST['saiu21idresponsable_doc'], $_REQUEST['saiu21idresponsable'], 0, $objDB);
$objCombos->nuevo('saiu21solucion', $_REQUEST['saiu21solucion'], true, $ETI['no'], 0);
$objCombos->addItem(1, $ETI['si']);
//$objCombos->addArreglo($asaiu21solucion, $isaiu21solucion);
$sSQL = '';
$html_saiu21solucion = $objCombos->html($sSQL, $objDB);
if ((int)$_REQUEST['paso'] == 0) {
	$saiu21tiporadicado_nombre = '&nbsp;';
	if ((int)$_REQUEST['saiu21tiporadicado'] != 0) {
		list($saiu21tiporadicado_nombre, $sErrorDet) = tabla_campoxid('saiu16tiporadicado', 'saiu16nombre', 'saiu16id', $_REQUEST['saiu21tiporadicado'], '{' . $ETI['msg_sindato'] . '}', $objDB);
	}
	$html_saiu21tiporadicado = html_oculto('saiu21tiporadicado', $_REQUEST['saiu21tiporadicado'], $saiu21tiporadicado_nombre);
} else {
	$saiu21tiporadicado_nombre = '&nbsp;';
	if ((int)$_REQUEST['saiu21tiporadicado'] != 0) {
		list($saiu21tiporadicado_nombre, $sErrorDet) = tabla_campoxid('saiu16tiporadicado', 'saiu16nombre', 'saiu16id', $_REQUEST['saiu21tiporadicado'], '{' . $ETI['msg_sindato'] . '}', $objDB);
	}
	$html_saiu21tiporadicado = html_oculto('saiu21tiporadicado', $_REQUEST['saiu21tiporadicado'], $saiu21tiporadicado_nombre);
}
//Alistar datos adicionales
$id_rpt = 0;
//$id_rpt=reportes_id(_Identificador_Tipo_Reporte_, $objDB);
/*
$objCombos->nuevo('blistar', $_REQUEST['blistar'], true, '{' . $ETI['msg_todos'] . '}');
$objCombos->sAccion = 'paginarf3021()';
$sSQL = '';
$html_blistar = $objCombos->html($sSQL, $objDB);
//$html_blistar = $objCombos->comboSistema(3021, 1, $objDB, 'paginarf3021()');
*/
if (false) {
	$objCombos->nuevo('csv_separa', $_REQUEST['csv_separa'], false);
	$objCombos->addItem(',', $ETI['msg_coma']);
	$objCombos->addItem(';', $ETI['msg_puntoycoma']);
	$csv_separa = '<label class="Label90">' . $ETI['msg_separador'] . '</label><label class="Label130">' . $objCombos->html('', $objDB) . '</label>';
} else {
	$csv_separa = '<input id="csv_separa" name="csv_separa" type="hidden" value="," />';
}
$iNumFormatosImprime = 0;
$iModeloReporte = 3021;
$html_iFormatoImprime = '<input id="iformatoimprime" name="iformatoimprime" type="hidden" value="0" />
';
if ($_REQUEST['paso'] > 0) {
	$bDevuelve = false;
	//list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 5, $idTercero, $objDB);
	if ($bDevuelve) {
		$seg_5 = 1;
	}
	$bDevuelve = false;
	//list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 8, $idTercero, $objDB);
	if ($bDevuelve) {
		$seg_8 = 1;
	}
}
//Cargar las tablas de datos
$aParametros[0] = ''; //$_REQUEST['p1_3021'];
$aParametros[100] = $idTercero;
$aParametros[101] = $_REQUEST['paginaf3021'];
$aParametros[102] = $_REQUEST['lppf3021'];
//$aParametros[103] = $_REQUEST['bnombre'];
//$aParametros[104] = $_REQUEST['blistar'];
list($sTabla3021, $sDebugTabla) = f3021_TablaDetalleV2($aParametros, $objDB, $bDebug);
$sDebug = $sDebug . $sDebugTabla;
$bDebugMenu = false;
list($et_menu, $sDebugM) = html_menuV2($APP->idsistema, $objDB, $iPiel, $bDebugMenu, $idTercero);
$sDebug = $sDebug . $sDebugM;
$objDB->CerrarConexion();
//FORMA
require $APP->rutacomun . 'unad_forma_v2.php';
forma_cabeceraV3($xajax, $ETI['titulo_3021']);
echo $et_menu;
forma_mitad();
?>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>js/jquery-3.3.1.min.js"></script>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>js/popper.min.js"></script>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>js/bootstrap.min.js"></script>
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>js/bootstrap.min.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>css/criticalPath.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>css/principal.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>unad_estilos2018.css" type="text/css" />
<?php
if ($iPiel == 2) {
?>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>js/access.js"></script>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>js/mogu-menu-access.js"></script>
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>style-2023.css?v=2" type="text/css" />
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>css/mogu-menu-access.css" type="text/css" />
<?php
}
?>
<script language="javascript">
	function limpiapagina() {
		expandesector(98);
		window.document.frmedita.paso.value = -1;
		window.document.frmedita.submit();
	}

	function enviaguardar() {
		window.document.frmedita.iscroll.value = window.scrollY;
		expandesector(98);
		let dpaso = window.document.frmedita.paso;
		if (dpaso.value == 0) {
			dpaso.value = 10;
		} else {
			dpaso.value = 12;
		}
		window.document.frmedita.submit();
	}

	function cambiapagina() {
		expandesector(98);
		window.document.frmedita.submit();
	}

	function cambiapaginaV2() {
		expandesector(98);
		window.document.frmedita.paso.value = 1;
		window.document.frmedita.submit();
	}

	function expandepanel(codigo, estado, valor) {
		let objdiv = document.getElementById('div_p' + codigo);
		let objban = document.getElementById('boculta' + codigo);
		let otroestado = 'none';
		if (estado == 'none') {
			otroestado = 'block';
		}
		objdiv.style.display = estado;
		objban.value = valor;
		verboton('btrecoge' + codigo, estado);
		verboton('btexpande' + codigo, otroestado);
	}

	function verboton(idboton, estado) {
		let objbt = document.getElementById(idboton);
		objbt.style.display = estado;
	}

	function expandesector(codigo) {
		document.getElementById('div_sector1').style.display = 'none';
		document.getElementById('div_sector2').style.display = 'none';
		document.getElementById('div_sector93').style.display = 'none';
		document.getElementById('div_sector95').style.display = 'none';
		document.getElementById('div_sector96').style.display = 'none';
		document.getElementById('div_sector97').style.display = 'none';
		document.getElementById('div_sector98').style.display = 'none';
		document.getElementById('div_sector' + codigo).style.display = 'block';
<?php
if ($bPuedeGuardar) {
?>
		let sEst = 'none';
		if (codigo == 1) {
			sEst = 'block';
		}
		document.getElementById('cmdGuardarf').style.display = sEst;
<?php
}
?>
	}

	function ter_crea(idcampo, illave) {
		let dtd = document.getElementById(idcampo + '_td');
		let ddoc = document.getElementById(idcampo + '_doc');
		let sURL = 'frametercero.php?td=' + String(dtd.value) + '&doc=' + String(ddoc.value);
		window.document.frmedita.iscroll.value = window.scrollY;
		window.document.frmedita.div96v1.value = '';
		window.document.frmedita.div96v2.value = '';
		window.document.frmedita.div96v3.value = '';
		window.document.frmedita.div96campo.value = idcampo;
		window.document.frmedita.div96llave.value = illave;
		document.getElementById('div_95cuerpo').innerHTML = '<iframe id="iframe95" src="' + sURL + '" height="400px" width="100%" frameborder="0"></iframe>';
		expandesector(95);
		window.scrollTo(0, 150);
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

	function ter_traerxid(idcampo, vrcampo) {
		let params = new Array();
		params[0] = vrcampo;
		params[1] = idcampo;
		if (params[0] != 0) {
			xajax_unad11_TraerXid(params);
		}
	}

	function imprimelista() {
		if (window.document.frmedita.seg_6.value == 1) {
			window.document.frmlista.consulta.value = window.document.frmedita.consulta_3021.value;
			window.document.frmlista.titulos.value = window.document.frmedita.titulos_3021.value;
			window.document.frmlista.nombrearchivo.value = 'Atención presencial';
			window.document.frmlista.submit();
		} else {
			ModalMensaje("<?php echo $ERR['6']; ?>");
		}
	}

	function asignarvariables() {
		//window.document.frmimpp.v3.value = window.document.frmedita.bnombre.value;
		//window.document.frmimpp.v4.value = window.document.frmedita.bcodigo.value;
		//window.document.frmimpp.separa.value = window.document.frmedita.csv_separa.value.trim();
	}

	function imprimeexcel() {
		let sError = '';
		if (window.document.frmedita.seg_6.value != 1) {
			sError = "<?php echo $ERR['6']; ?>";
		}
		if (sError == '') {
			/*Agregar validaciones*/
		}
		if (sError == '') {
			asignarvariables();
			window.document.frmimpp.action = 'e3021_ss.php';
			window.document.frmimpp.submit();
		} else {
			ModalMensaje(sError);
		}
	}

	function imprimep() {
		if (window.document.frmedita.seg_5.value == 1) {
			asignarvariables();
			window.document.frmimpp.action = 'p3021.php';
			window.document.frmimpp.submit();
<?php
if ($iNumFormatosImprime>0) {
	echo 'expandesector(1);';
}
?>
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
		datos[1] = window.document.frmedita.saiu21agno.value;
		datos[2] = window.document.frmedita.saiu21mes.value;
		datos[3] = window.document.frmedita.saiu21tiporadicado.value;
		datos[4] = window.document.frmedita.saiu21consec.value;
		if ((datos[1] != '') && (datos[2] != '') && (datos[3] != '') && (datos[4] != '')) {
			xajax_f3021_ExisteDato(datos);
		}
	}

	function cargadato(llave1, llave2, llave3, llave4) {
		window.document.frmedita.saiu21agno.value = String(llave1);
		window.document.frmedita.saiu21mes.value = String(llave2);
		window.document.frmedita.saiu21tiporadicado.value = String(llave3);
		window.document.frmedita.saiu21consec.value = String(llave4);
		window.document.frmedita.paso.value = 1;
		window.document.frmedita.submit();
	}

	function cargaridf3021(llave1) {
		window.document.frmedita.saiu21id.value = String(llave1);
		window.document.frmedita.paso.value = 3;
		window.document.frmedita.submit();
	}

	function carga_combo_saiu21idcentro() {
		let params = new Array();
		params[0] = window.document.frmedita.saiu21idzona.value;
		document.getElementById('div_saiu21idcentro').innerHTML = '<b>Procesando datos, por favor espere...</b><input id="saiu21idcentro" name="saiu21idcentro" type="hidden" value="" />';
		xajax_f3021_Combosaiu21idcentro(params);
	}

	function carga_combo_saiu21coddepto() {
		let params = new Array();
		params[0] = window.document.frmedita.saiu21codpais.value;
		document.getElementById('div_saiu21coddepto').innerHTML = '<b>Procesando datos, por favor espere...</b><input id="saiu21coddepto" name="saiu21coddepto" type="hidden" value="" />';
		xajax_f3021_Combosaiu21coddepto(params);
	}

	function carga_combo_saiu21codciudad() {
		let params = new Array();
		params[0] = window.document.frmedita.saiu21coddepto.value;
		document.getElementById('div_saiu21codciudad').innerHTML = '<b>Procesando datos, por favor espere...</b><input id="saiu21codciudad" name="saiu21codciudad" type="hidden" value="" />';
		xajax_f3021_Combosaiu21codciudad(params);
	}

	function carga_combo_saiu21idprograma() {
		let params = new Array();
		params[0] = window.document.frmedita.saiu21idescuela.value;
		document.getElementById('div_saiu21idprograma').innerHTML = '<b>Procesando datos, por favor espere...</b><input id="saiu21idprograma" name="saiu21idprograma" type="hidden" value="" />';
		xajax_f3021_Combosaiu21idprograma(params);
	}

	function paginarf3021() {
		let params = new Array();
		params[99] = window.document.frmedita.debug.value;
		params[100] = <?php echo $idTercero; ?>;
		params[101] = window.document.frmedita.paginaf3021.value;
		params[102] = window.document.frmedita.lppf3021.value;
		//params[103] = window.document.frmedita.bnombre.value;
		//params[104] = window.document.frmedita.blistar.value;
		//document.getElementById('div_f3021detalle').innerHTML = '<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf3021" name="paginaf3021" type="hidden" value="' + params[101] + '" /><input id="lppf3021" name="lppf3021" type="hidden" value="' + params[102] + '" />';
		xajax_f3021_HtmlTabla(params);
	}

	function revfoco(objeto) {
		setTimeout(function() {
			objeto.focus();
		}, 10);
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
		document.getElementById("saiu21agno").focus();
	}

	function buscarV2016(sCampo) {
		window.document.frmedita.iscroll.value = window.scrollY;
		expandesector(98);
		window.document.frmedita.scampobusca.value = sCampo;
		let params = new Array();
		params[1] = sCampo;
		//params[2] = window.document.frmedita.iagno.value;
		//params[3] = window.document.frmedita.itipo.value;
		xajax_f3021_Busquedas(params);
	}

	function retornacontrol() {
		expandesector(1);
		window.scrollTo(0, window.document.frmedita.iscroll.value);
	}

	function Devuelve(sValor) {
		let sCampo = window.document.frmedita.scampobusca.value;
		if (sCampo == 'saiu21idsolicitante') {
			ter_traerxid('saiu21idsolicitante', sValor);
		}
		if (sCampo == 'saiu21idresponsable') {
			ter_traerxid('saiu21idresponsable', sValor);
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
</script>
<?php
if ($_REQUEST['paso'] != 0) {
?>
<form id="frmimpp" name="frmimpp" method="post" action="p3021.php" target="_blank">
<input id="r" name="r" type="hidden" value="3021" />
<input id="id3021" name="id3021" type="hidden" value="<?php echo $_REQUEST['saiu21id']; ?>" />
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
<form id="frmlista" name="frmlista" method="post" action="listados.php" target="_blank">
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
<input id="cmdAyuda" name="cmdAyuda" type="button" class="btUpAyuda" onclick="muestraayuda(<?php echo $APP->idsistema . ', ' . $iCodModulo; ?>);" title="<?php echo $ETI['bt_ayuda']; ?>" value="<?php echo $ETI['bt_ayuda']; ?>" />
<?php
if ($_REQUEST['paso'] == 2) {
?>
<input id="cmdEliminar" name="cmdEliminar" type="button" class="btUpEliminar" onclick="eliminadato();" title="<?php echo $ETI['bt_eliminar']; ?>" value="<?php echo $ETI['bt_eliminar']; ?>" />
<?php
}
$bHayImprimir = false;
$sScript = 'imprimelista()';
$sClaseBoton = 'btEnviarExcel';
if ($seg_6 == 1) {
	$bHayImprimir = true;
}
if ($_REQUEST['paso'] != 0) {
	if ($seg_5 == 1) {
		//$bHayImprimir = true;
		//$sScript = 'imprimep()';
		//if ($iNumFormatosImprime>0) {
			//$sScript = 'expandesector(94)';
			//}
		//$sClaseBoton = 'btEnviarPDF'; //btUpPrint
		//if ($id_rpt != 0) { $sScript = 'verrpt()'; }
	}
}
if ($bHayImprimir) {
?>
<input id="cmdImprimir" name="cmdImprimir" type="button" class="<?php echo $sClaseBoton; ?>" onclick="<?php echo $sScript; ?>" title="<?php echo $ETI['bt_imprimir']; ?>" value="<?php echo $ETI['bt_imprimir']; ?>" />
<?php
}
?>
<input id="cmdLimpiar" name="cmdLimpiar" type="button" class="btUpLimpiar" onclick="limpiapagina();" title="<?php echo $ETI['bt_limpiar']; ?>" value="<?php echo $ETI['bt_limpiar']; ?>" />
<?php
if ($bPuedeGuardar) {
?>
<input id="cmdGuardar" name="cmdGuardar" type="button" class="btUpGuardar" onclick="enviaguardar();" title="<?php echo $ETI['bt_guardar']; ?>" value="<?php echo $ETI['bt_guardar']; ?>" />
<?php
}
if (false) {
?>
<input id="cmdAnular" name="cmdAnular" type="button" class="btSupAnular" onclick="expandesector(2);" title="<?php echo $ETI['bt_anular']; ?>" value="<?php echo $ETI['bt_anular']; ?>" />
<?php
}
?>
</div>
<div class="titulosI">
<?php
echo '<h2>' . $ETI['titulo_3021'] . '</h2>';
?>
</div>
</div>
<div class="areaform">
<div class="areatrabajo">
<?php
if ($seg_1707 == 1) {
?>
<div class="GrupoCamposAyuda">
<div class="salto5px"></div>
<label class="Label90">
Documento
</label>
<label class="Label60">
<?php
echo html_tipodocV2('deb_tipodoc', $_REQUEST['deb_tipodoc']);
?>
</label>
<label class="Label160">
<input id="deb_doc" name="deb_doc" type="text" value="<?php echo $_REQUEST['deb_doc']; ?>" class="veinte" maxlength="20" placeholder="Documento" title="Documento para consultar un usuario" />
</label>
<label class="Label30">
</label>
<label class="Label30">
<input id="btRevisaDoc" name="btRevisaDoc" type="button" value="Actualizar" class="btMiniActualizar" onclick="limpiapagina()" title="Consultar documento" />
</label>
<label class="Label30"></label>
<b>
<?php
echo $sNombreUsuario;
?>
</b>
<div class="salto1px"></div>
</div>
<div class="salto5px"></div>
<?php
} else {
?>
<input id="deb_tipodoc" name="deb_tipodoc" type="hidden" value="<?php echo $_REQUEST['deb_tipodoc']; ?>" />
<input id="deb_doc" name="deb_doc" type="hidden" value="<?php echo $_REQUEST['deb_doc']; ?>" />
<?php
}
?>
<?php
//Div para ocultar
$bConExpande = true;
if ($bConExpande) {
?>
<div class="ir_derecha"<?php echo $sAnchoExpandeContrae; ?>>
<?php
$sEstiloExpande = ' style="display:none;"';
$sEstiloRecoje = '';
$sEstiloDiv = '';
if ($_REQUEST['boculta3021'] != 0) {
	$sEstiloExpande = '';
	$sEstiloRecoje = ' style="display:none;"';
	$sEstiloDiv = ' style="display:none;"';
}
?>
<input id="boculta3021" name="boculta3021" type="hidden" value="<?php echo $_REQUEST['boculta3021']; ?>" />
<label class="Label30">
<input id="btexpande3021" name="btexpande3021" type="button" value="Mostrar" class="btMiniExpandir" onclick="expandepanel(3021, 'block', 0);" title="<?php echo $ETI['bt_mostrar']; ?>"<?php echo $sEstiloExpande; ?>/>
</label>
<label class="Label30">
<input id="btrecoge3021" name="btrecoge3021" type="button" value="Ocultar" class="btMiniRecoger" onclick="expandepanel(3021, 'none', 1);" title="<?php echo $ETI['bt_ocultar']; ?>" <?php echo $sEstiloRecoje; ?>/>
</label>
</div>
<div id="div_p3021"<?php echo $sEstiloDiv; ?>>
<?php
}
//Mostrar formulario para editar
?>
<label class="Label130">
<?php
echo $ETI['saiu21agno'];
?>
</label>
<label class="Label130">
<div id="div_saiu21agno">
<?php
if ($_REQUEST['paso'] != 2) {
?>
<?php
echo html_oculto('saiu21agno', $_REQUEST['saiu21agno']);
?>
<?php
} else {
	echo html_oculto('saiu21agno', $_REQUEST['saiu21agno']);
}
?>
</div>
</label>
<label class="Label130">
<?php
echo $ETI['saiu21mes'];
?>
</label>
<label class="Label130">
<div id="div_saiu21mes">
<?php
if ($_REQUEST['paso'] != 2) {
?>
<?php
echo html_oculto('saiu21mes', $_REQUEST['saiu21mes']);
?>
<?php
} else {
	echo html_oculto('saiu21mes', $_REQUEST['saiu21mes']);
}
?>
</div>
</label>
<label class="Label130">
<?php
echo $ETI['saiu21tiporadicado'];
?>
</label>
<label>
<div id="div_saiu21tiporadicado">
<?php
echo $html_saiu21tiporadicado;
?>
</div>
</label>
<label class="Label130"<?php echo $sOcultaConsec; ?>>
<?php
echo $ETI['saiu21consec'];
?>
</label>
<label class="Label130"<?php echo $sOcultaConsec; ?>>
<?php
if ($_REQUEST['paso'] != 2) {
?>
<input id="saiu21consec" name="saiu21consec" type="text" value="<?php echo $_REQUEST['saiu21consec']; ?>" onchange="RevisaLlave()" class="cuatro" />
<?php
} else {
	echo html_oculto('saiu21consec', $_REQUEST['saiu21consec'], formato_numero($_REQUEST['saiu21consec']));
}
?>
</label>
<?php
/*
if ($seg_8 == 1) {
	$objForma = new clsHtmlForma($iPiel);
	echo $objForma->htmlBotonSolo('cmdCambiaConsec', 'btMiniActualizar', 'expandesector(93);', $ETI['bt_cambiar'], 30);
	echo '<label class="Label30">&nbsp;</label>';
}
*/
?>
<label class="Label60"<?php echo $sOcultaId; ?>>
<?php
echo $ETI['saiu21id'];
?>
</label>
<label class="Label60"<?php echo $sOcultaId; ?>>
<?php
	echo html_oculto('saiu21id', $_REQUEST['saiu21id'], formato_numero($_REQUEST['saiu21id']));
?>
</label>
<input id="saiu21origenagno" name="saiu21origenagno" type="hidden" value="<?php echo $_REQUEST['saiu21origenagno']; ?>" />
<input id="saiu21origenmes" name="saiu21origenmes" type="hidden" value="<?php echo $_REQUEST['saiu21origenmes']; ?>" />
<label class="Label130">
<?php
echo $ETI['saiu21origenid'];
?>
</label>
<label class="Label130">
<div id="div_saiu21origenid">
<?php
echo html_oculto('saiu21origenid', $_REQUEST['saiu21origenid']);
?>
</div>
</label>
<label class="Label130">
<?php
echo $ETI['saiu21dia'];
?>
</label>
<label class="Label130">

<input id="saiu21dia" name="saiu21dia" type="text" value="<?php echo $_REQUEST['saiu21dia']; ?>" class="diez" maxlength="10" placeholder="<?php echo $ETI['ing_vr']; ?>" />
</label>
<label class="Label130">
<?php
echo $ETI['saiu21hora'];
?>
</label>
<div class="campo_HoraMin" id="div_saiu21hora">
<?php
echo html_HoraMin('saiu21hora', $_REQUEST['saiu21hora'], 'saiu21minuto', $_REQUEST['saiu21minuto']);
?>
</div>
<label class="Label130">
<?php
echo $ETI['saiu21estado'];
?>
</label>
<label>
<div id="div_saiu21estado">
<?php
echo $html_saiu21estado;
?>
</div>
</label>
<label class="Label130">
<?php
echo $ETI['saiu21idcorreo'];
?>
</label>
<label>
<?php
echo $html_saiu21idcorreo;
?>
</label>
<div class="salto1px"></div>
<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo $ETI['saiu21idsolicitante'];
?>
</label>
<div class="salto1px"></div>
<input id="saiu21idsolicitante" name="saiu21idsolicitante" type="hidden" value="<?php echo $_REQUEST['saiu21idsolicitante']; ?>" />
<div id="div_saiu21idsolicitante_llaves">
<?php
$bOculto = false;
echo html_DivTerceroV2('saiu21idsolicitante', $_REQUEST['saiu21idsolicitante_td'], $_REQUEST['saiu21idsolicitante_doc'], $bOculto, 0, $ETI['ing_doc']);
?>
</div>
<div class="salto1px"></div>
<div id="div_saiu21idsolicitante" class="L"><?php echo $saiu21idsolicitante_rs; ?></div>
<div class="salto1px"></div>
</div>
<label class="Label130">
<?php
echo $ETI['saiu21tipointeresado'];
?>
</label>
<label>
<?php
echo $html_saiu21tipointeresado;
?>
</label>
<input id="saiu21clasesolicitud" name="saiu21clasesolicitud" type="hidden" value="<?php echo $_REQUEST['saiu21clasesolicitud']; ?>" />
<label class="Label130">
<?php
echo $ETI['saiu21tiposolicitud'];
?>
</label>
<label>
<?php
echo $html_saiu21tiposolicitud;
?>
</label>
<label class="Label130">
<?php
echo $ETI['saiu21temasolicitud'];
?>
</label>
<label>
<?php
echo $html_saiu21temasolicitud;
?>
</label>
<label class="Label130">
<?php
echo $ETI['saiu21idzona'];
?>
</label>
<label>
<?php
echo $html_saiu21idzona;
?>
</label>
<label class="Label130">
<?php
echo $ETI['saiu21idcentro'];
?>
</label>
<label>
<div id="div_saiu21idcentro">
<?php
echo $html_saiu21idcentro;
?>
</div>
</label>
<label class="Label130">
<?php
echo $ETI['saiu21codpais'];
?>
</label>
<label>
<?php
echo $html_saiu21codpais;
?>
</label>
<label class="Label130">
<?php
echo $ETI['saiu21coddepto'];
?>
</label>
<label>
<div id="div_saiu21coddepto">
<?php
echo $html_saiu21coddepto;
?>
</div>
</label>
<label class="Label130">
<?php
echo $ETI['saiu21codciudad'];
?>
</label>
<label>
<div id="div_saiu21codciudad">
<?php
echo $html_saiu21codciudad;
?>
</div>
</label>
<label class="Label130">
<?php
echo $ETI['saiu21idescuela'];
?>
</label>
<label>
<?php
echo $html_saiu21idescuela;
?>
</label>
<label class="Label130">
<?php
echo $ETI['saiu21idprograma'];
?>
</label>
<label>
<div id="div_saiu21idprograma">
<?php
echo $html_saiu21idprograma;
?>
</div>
</label>
<label class="Label130">
<?php
echo $ETI['saiu21idperiodo'];
?>
</label>
<label>
<?php
echo $html_saiu21idperiodo;
?>
</label>
<label class="Label130">
<?php
echo $ETI['saiu21numorigen'];
?>
</label>
<label>

<input id="saiu21numorigen" name="saiu21numorigen" type="text" value="<?php echo $_REQUEST['saiu21numorigen']; ?>" maxlength="20" placeholder="<?php echo $ETI['ing_campo'] . $ETI['saiu21numorigen']; ?>" />
</label>
<label class="Label130">
<?php
echo $ETI['saiu21idpqrs'];
?>
</label>
<label class="Label130">
<div id="div_saiu21idpqrs">
<?php
echo html_oculto('saiu21idpqrs', $_REQUEST['saiu21idpqrs']);
?>
</div>
</label>
<label class="txtAreaS">
<?php
echo $ETI['saiu21detalle'];
?>
<textarea id="saiu21detalle" name="saiu21detalle" placeholder="<?php echo $ETI['ing_campo'] . $ETI['saiu21detalle']; ?>"><?php echo $_REQUEST['saiu21detalle']; ?></textarea>
</label>
<label class="Label130">
<?php
echo $ETI['saiu21horafin'];
?>
</label>
<div class="campo_HoraMin" id="div_saiu21horafin">
<?php
echo html_HoraMin('saiu21horafin', $_REQUEST['saiu21horafin'], 'saiu21minutofin', $_REQUEST['saiu21minutofin']);
?>
</div>
<label class="Label130">
<?php
echo $ETI['saiu21paramercadeo'];
?>
</label>
<label>
<?php
echo $html_saiu21paramercadeo;
?>
</label>
<div class="salto1px"></div>
<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo $ETI['saiu21idresponsable'];
?>
</label>
<div class="salto1px"></div>
<input id="saiu21idresponsable" name="saiu21idresponsable" type="hidden" value="<?php echo $_REQUEST['saiu21idresponsable']; ?>" />
<div id="div_saiu21idresponsable_llaves">
<?php
$bOculto = false;
echo html_DivTerceroV2('saiu21idresponsable', $_REQUEST['saiu21idresponsable_td'], $_REQUEST['saiu21idresponsable_doc'], $bOculto, 0, $ETI['ing_doc']);
?>
</div>
<div class="salto1px"></div>
<div id="div_saiu21idresponsable" class="L"><?php echo $saiu21idresponsable_rs; ?></div>
<div class="salto1px"></div>
</div>
<input id="saiu21tiemprespdias" name="saiu21tiemprespdias" type="hidden" value="<?php echo $_REQUEST['saiu21tiemprespdias']; ?>" />
<input id="saiu21tiempresphoras" name="saiu21tiempresphoras" type="hidden" value="<?php echo $_REQUEST['saiu21tiempresphoras']; ?>" />
<input id="saiu21tiemprespminutos" name="saiu21tiemprespminutos" type="hidden" value="<?php echo $_REQUEST['saiu21tiemprespminutos']; ?>" />
<label class="Label130">
<?php
echo $ETI['saiu21solucion'];
?>
</label>
<label>
<?php
echo $html_saiu21solucion;
?>
</label>
<label class="Label130">
<?php
echo $ETI['saiu21idcaso'];
?>
</label>
<label class="Label130">
<div id="div_saiu21idcaso">
<?php
echo html_oculto('saiu21idcaso', $_REQUEST['saiu21idcaso']);
?>
</div>
</label>
<?php
if (false) {
	//Ejemplo de boton de ayuda
	//echo html_BotonAyuda('NombreCampo');
	//echo html_DivAyudaLocal('NombreCampo');
}
if ($bConExpande) {
	//Este es el cierre del div_p3021
?>
<div class="salto1px"></div>
</div>
<?php
}
//Mostrar el contenido de la tabla
// CIERRA EL DIV areatrabajo
?>
</div>
</div>
<div class="areaform">
<div class="areatitulo">
<?php
echo '<h3>' . $ETI['bloque1'] . '</h3>';
?>
</div>
<div class="areatrabajo">
<?php
if (false) {
?>
<div class="ir_derecha">
<label class="Label90">
<?php
echo $ETI['msg_bnombre'];
?>
</label>
<label>
<input id="bnombre" name="bnombre" type="text" value="<?php echo $_REQUEST['bnombre']; ?>" onchange="paginarf3021()" autocomplete="off" />
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
echo ' ' . $csv_separa;
?>
<div class="salto1px"></div>
<div id="div_f3021detalle">
<?php
echo $sTabla3021;
?>
</div>
<?php
// Termina el div_areatrabajo y DIV_areaform
?>
</div>
</div>
</div>


<div id="div_sector2" style="display:none">
<div class="titulos">
<div class="titulosD">
<input id="cmdAyuda2" name="cmdAyuda2" type="button" class="btSupAyuda" onclick="muestraayuda(<?php echo $iCodModulo; ?>);" title="<?php echo $ETI['bt_ayuda']; ?>" value="<?php echo $ETI['bt_ayuda']; ?>" />
<input id="cmdVolverSec2" name="cmdVolverSec2" type="button" class="btSupVolver" onclick="expandesector(1);" title="<?php echo $ETI['bt_volver']; ?>" value="<?php echo $ETI['bt_volver']; ?>" />
</div>
<div class="titulosI">
<?php
echo '<h2>' . $ETI['titulo_sector2'] . '</h2>';
?>
</div>
</div>
<div id="cargaForm">
<div id="area">
</div>
</div>
<?php
// Termina el div_sector2
?>
</div>


<div id="div_sector93" style="display:none">
<?php
$objForma = new clsHtmlForma($iPiel);
$objForma->addBoton('cmdAyuda93', 'btSupAyuda', 'muestraayuda(' . $iCodModulo . ');', $ETI['bt_ayuda']);
$objForma->addBoton('cmdVolverSec93', 'btSupVolver', 'expandesector(1);', $ETI['bt_volver']);
echo $objForma->htmlTitulo('' . $ETI['titulo_sector93'] . '', $iCodModulo);
echo $objForma->htmlInicioMarco();
?>
<label class="Label160">
<?php
echo $ETI['msg_saiu21consec'];
?>
</label>
<label class="Label90">
<?php
echo '<b>' . $_REQUEST['saiu21consec'] . '</b>';
?>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['msg_saiu21consec_nuevo'];
// onchange="RevisaConsec()"
?>
</label>
<label class="Label90">
<input id="saiu21consec_nuevo" name="saiu21consec_nuevo" type="text" value="<?php echo $_REQUEST['saiu21consec_nuevo']; ?>" class="cuatro" />
</label>
<div class="salto1px"></div>
<label class="Label160">&nbsp;</label>
<?php
echo $objForma->htmlBotonSolo('cmdCambiaConsecFinal', 'botonProceso', 'mod_consec();', $ETI['bt_cambiar'], 130);
echo $objForma->htmlFinMarco();
?>
</div>


<div id="div_sector95" style="display:none">
<div id="cargaForm">
<div id="div_95cuerpo"></div>
</div>
</div>


<div id="div_sector96" style="display:none">
<input id="div96v1" name="div96v1" type="hidden" value="" />
<input id="div96v2" name="div96v2" type="hidden" value="" />
<input id="div96v3" name="div96v3" type="hidden" value="" />
<input id="div96campo" name="div96campo" type="hidden" value="" />
<input id="div96llave" name="div96llave" type="hidden" value="" />
<input id="titulo_3021" name="titulo_3021" type="hidden" value="<?php echo $ETI['titulo_3021']; ?>" />
<div class="titulos">
<div class="titulosD">
<input id="cmdAyuda96" name="cmdAyuda96" type="button" class="btSupAyuda" onclick="muestraayuda(<?php echo $iCodModulo; ?>);" title="<?php echo $ETI['bt_ayuda']; ?>" value="<?php echo $ETI['bt_ayuda']; ?>" />
</div>
<div class="titulosI" id="div_96titulo"></div>
</div>
<div id="cargaForm">
<div id="div_96cuerpo"></div>
</div>
</div>


<div id="div_sector97" style="display:none">
<div class="titulos">
<div class="titulosD">
<input id="cmdAyuda97" name="cmdAyuda97" type="button" class="btSupAyuda" onclick="muestraayuda(<?php echo $iCodModulo; ?>);" title="<?php echo $ETI['bt_ayuda']; ?>" value="<?php echo $ETI['bt_ayuda']; ?>" />
<input id="cmdVolverSec97" name="cmdVolverSec97" type="button" class="btSupVolver" onclick="retornacontrol();" title="<?php echo $ETI['bt_volver']; ?>" value="<?php echo $ETI['bt_volver']; ?>" />
</div>
<div class="titulosI" id="div_97titulo">
<?php
echo '<h2>' . $ETI['titulo_3021'] . '</h2>';
?>
</div>
</div>
<div id="areaform">
<div id="areatrabajo">
<div id="div_97params"></div>
<div class="salto1px"></div>
<div id="div_97tabla"></div>
</div>
</div>
</div>


<div id="div_sector98" style="display:none">
<div class="titulos">
<div class="titulosD">
<input id="cmdAyuda98" name="cmdAyuda98" type="button" class="btSupAyuda" onclick="muestraayuda(<?php echo $iCodModulo; ?>);" title="<?php echo $ETI['bt_ayuda']; ?>" value="<?php echo $ETI['bt_ayuda']; ?>" />
</div>
<div class="titulosI">
<?php
echo '<h2>' . $ETI['titulo_3021'] . '</h2>';
?>
</div>
</div>
<div id="cargaForm">
<div id="area">
<div class="MarquesinaMedia">
<?php
echo $ETI['msg_espere'];
?>
</div>
</div>
</div>
</div>


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
<div class="flotante">
<?php
if ($bPuedeGuardar) {
?>
<input id="cmdGuardarf" name="cmdGuardarf" type="button" class="btSoloGuardar" onClick="enviaguardar();" value="<?php echo $ETI['bt_guardar']; ?>" />
<?php
}
?>
</div>
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
<script language="javascript" src="<?php echo $APP->rutacomun; ?>js/chosen.jquery.js"></script>
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>js/chosen.css" type="text/css" />
<?php
if ($_REQUEST['paso'] == 0) {
?>
<script language="javascript">
	$().ready(function() {
		//$("#bperiodo").chosen();
	});
</script>
<?php
}
?>
<script language="javascript" src="ac_3021.js"></script>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>unad_todas.js?ver=8"></script>
<?php
forma_piedepagina();

