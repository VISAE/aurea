<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2021 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.25.10c jueves, 6 de mayo de 2021
--- Modelo Versión 2.26.3b miércoles, 21 de julio de 2021
*/
/** Archivo saicomunicados.php.
* Modulo 3061 saiu61comunicados.
* @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
* @param debug=1 (Opcional), bandera para indicar si se generan datos de depuración
* @date jueves, 6 de mayo de 2021
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
$iCodModulo=3061;
$audita[1]=false;
$audita[2]=true;
$audita[3]=true;
$audita[4]=true;
$audita[5]=false;
// -- Se cargan los archivos de idioma
$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
$mensajes_3061='lg/lg_3061_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_3061)){$mensajes_3061='lg/lg_3061_es.php';}
require $mensajes_todas;
require $mensajes_3061;
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
		header('Location:noticia.php?ret=saicomunicados.php');
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
$mensajes_3062='lg/lg_3062_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_3062)){$mensajes_3062='lg/lg_3062_es.php';}
require $mensajes_3062;
// -- Si esta cargando la pagina por primer vez se revisa si requiere auditar y se manda a hacer un limpiar (paso -1)
if (isset($_REQUEST['paso'])==0){
	$_REQUEST['paso']=-1;
	if ($audita[1]){seg_auditaingreso($iCodModulo, $_SESSION['unad_id_tercero'], $objDB);}
	}
// -- 3061 saiu61comunicados
require 'lib3061.php';
// -- 3062 Notificados
require 'lib3062.php';
$xajax=new xajax();
$xajax->configure('javascript URI', $APP->rutacomun.'xajax/');
$xajax->register(XAJAX_FUNCTION,'unad11_Mostrar_v2');
$xajax->register(XAJAX_FUNCTION,'unad11_TraerXid');
$xajax->register(XAJAX_FUNCTION,'sesion_abandona_V2');
$xajax->register(XAJAX_FUNCTION,'sesion_mantenerV4');
$xajax->register(XAJAX_FUNCTION,'f3061_HtmlTabla');
$xajax->register(XAJAX_FUNCTION,'f3061_ExisteDato');
$xajax->register(XAJAX_FUNCTION,'f3061_Busquedas');
$xajax->register(XAJAX_FUNCTION,'f3061_HtmlBusqueda');
$xajax->register(XAJAX_FUNCTION,'f3062_Combosaiu62idprograma');
$xajax->register(XAJAX_FUNCTION,'f3062_Combosaiu62idcentro');
$xajax->register(XAJAX_FUNCTION,'f3062_Guardar');
$xajax->register(XAJAX_FUNCTION,'f3062_Traer');
$xajax->register(XAJAX_FUNCTION,'f3062_Eliminar');
$xajax->register(XAJAX_FUNCTION,'f3062_HtmlTabla');
$xajax->register(XAJAX_FUNCTION,'f3062_PintarLlaves');
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
if (isset($_REQUEST['paginaf3061'])==0){$_REQUEST['paginaf3061']=1;}
if (isset($_REQUEST['lppf3061'])==0){$_REQUEST['lppf3061']=20;}
if (isset($_REQUEST['boculta3061'])==0){$_REQUEST['boculta3061']=0;}
if (isset($_REQUEST['paginaf3062'])==0){$_REQUEST['paginaf3062']=1;}
if (isset($_REQUEST['lppf3062'])==0){$_REQUEST['lppf3062']=20;}
if (isset($_REQUEST['boculta3062'])==0){$_REQUEST['boculta3062']=0;}
// -- Inicializar variables de datos.
if (isset($_REQUEST['saiu61consec'])==0){$_REQUEST['saiu61consec']='';}
if (isset($_REQUEST['saiu61consec_nuevo'])==0){$_REQUEST['saiu61consec_nuevo']='';}
if (isset($_REQUEST['saiu61id'])==0){$_REQUEST['saiu61id']='';}
if (isset($_REQUEST['saiu61orden'])==0){$_REQUEST['saiu61orden']='';}
if (isset($_REQUEST['saiu61vigente'])==0){$_REQUEST['saiu61vigente']='S';}
if (isset($_REQUEST['saiu61titulo'])==0){$_REQUEST['saiu61titulo']='';}
if (isset($_REQUEST['saiu61idunidad'])==0){$_REQUEST['saiu61idunidad']='';}
if (isset($_REQUEST['saiu61fecha'])==0){$_REQUEST['saiu61fecha']='';}//{fecha_hoy();}
if (isset($_REQUEST['saiu61fechapublica'])==0){$_REQUEST['saiu61fechapublica']='';}//{fecha_hoy();}
if (isset($_REQUEST['saiu61fechadespublica'])==0){$_REQUEST['saiu61fechadespublica']='';}//{fecha_hoy();}
if (isset($_REQUEST['saiu61cuerpo'])==0){$_REQUEST['saiu61cuerpo']='';}
if (isset($_REQUEST['saiu61poblacion'])==0){$_REQUEST['saiu61poblacion']='';}
if (isset($_REQUEST['saiu61formaentrega'])==0){$_REQUEST['saiu61formaentrega']='';}
if ((int)$_REQUEST['paso']>0){
	//Notificados
	if (isset($_REQUEST['saiu62idtercero'])==0){$_REQUEST['saiu62idtercero']=0;}//{$_SESSION['unad_id_tercero'];}
	if (isset($_REQUEST['saiu62idtercero_td'])==0){$_REQUEST['saiu62idtercero_td']=$APP->tipo_doc;}
	if (isset($_REQUEST['saiu62idtercero_doc'])==0){$_REQUEST['saiu62idtercero_doc']='';}
	if (isset($_REQUEST['saiu62id'])==0){$_REQUEST['saiu62id']='';}
	if (isset($_REQUEST['saiu62idperiodo'])==0){$_REQUEST['saiu62idperiodo']='';}
	if (isset($_REQUEST['saiu62idescuela'])==0){$_REQUEST['saiu62idescuela']='';}
	if (isset($_REQUEST['saiu62idprograma'])==0){$_REQUEST['saiu62idprograma']='';}
	if (isset($_REQUEST['saiu62idzona'])==0){$_REQUEST['saiu62idzona']='';}
	if (isset($_REQUEST['saiu62idcentro'])==0){$_REQUEST['saiu62idcentro']='';}
	if (isset($_REQUEST['saiu62estado'])==0){$_REQUEST['saiu62estado']='';}
	if (isset($_REQUEST['saiu62fecha'])==0){$_REQUEST['saiu62fecha']='';}//{fecha_hoy();}
	if (isset($_REQUEST['saiu62fhora'])==0){$_REQUEST['saiu62fhora']='';}
	if (isset($_REQUEST['saiu62min'])==0){$_REQUEST['saiu62min']='';}
	if (isset($_REQUEST['saiu62mailenviado'])==0){$_REQUEST['saiu62mailenviado']='';}
	}
// Espacio para inicializar otras variables
if (isset($_REQUEST['csv_separa'])==0){$_REQUEST['csv_separa']=';';}
if (isset($_REQUEST['bnombre'])==0){$_REQUEST['bnombre']='';}
//if (isset($_REQUEST['blistar'])==0){$_REQUEST['blistar']='';}
if ((int)$_REQUEST['paso']>0){
	//Notificados
	if (isset($_REQUEST['bdoc3062'])==0){$_REQUEST['bdoc3062']='';}
	if (isset($_REQUEST['bnombre3062'])==0){$_REQUEST['bnombre3062']='';}
	if (isset($_REQUEST['blistar3062'])==0){$_REQUEST['blistar3062']='';}
	}
//Si Modifica o Elimina Cargar los campos
if (($_REQUEST['paso']==1)||($_REQUEST['paso']==3)){
	if ($_REQUEST['paso']==1){
		$sSQLcondi='saiu61consec='.$_REQUEST['saiu61consec'].'';
		}else{
		$sSQLcondi='saiu61id='.$_REQUEST['saiu61id'].'';
		}
	$sSQL='SELECT * FROM saiu61comunicados WHERE '.$sSQLcondi;
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$_REQUEST['saiu61consec']=$fila['saiu61consec'];
		$_REQUEST['saiu61id']=$fila['saiu61id'];
		$_REQUEST['saiu61orden']=$fila['saiu61orden'];
		$_REQUEST['saiu61vigente']=$fila['saiu61vigente'];
		$_REQUEST['saiu61titulo']=$fila['saiu61titulo'];
		$_REQUEST['saiu61idunidad']=$fila['saiu61idunidad'];
		$_REQUEST['saiu61fecha']=$fila['saiu61fecha'];
		$_REQUEST['saiu61fechapublica']=$fila['saiu61fechapublica'];
		$_REQUEST['saiu61fechadespublica']=$fila['saiu61fechadespublica'];
		$_REQUEST['saiu61cuerpo']=$fila['saiu61cuerpo'];
		$_REQUEST['saiu61poblacion']=$fila['saiu61poblacion'];
		$_REQUEST['saiu61formaentrega']=$fila['saiu61formaentrega'];
		$bcargo=true;
		$_REQUEST['paso']=2;
		$_REQUEST['boculta3061']=0;
		$bLimpiaHijos=true;
		}else{
		$_REQUEST['paso']=0;
		}
	}
//Insertar o modificar un elemento
if (($_REQUEST['paso']==10)||($_REQUEST['paso']==12)){
	$bMueveScroll=true;
	list($_REQUEST, $sError, $iTipoError, $sDebugGuardar)=f3061_db_GuardarV2($_REQUEST, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugGuardar;
	if ($sError==''){
		$sError='<b>'.$ETI['msg_itemguardado'].'</b>';
		$iTipoError=1;
		}
	}
// Cambio de consecutivo.
if ($_REQUEST['paso']==93){
	$_REQUEST['paso']=2;
	$_REQUEST['saiu61consec_nuevo']=numeros_validar($_REQUEST['saiu61consec_nuevo']);
	if ($_REQUEST['saiu61consec_nuevo']==''){$sError=$ERR['saiu61consec'];}
	if ($sError==''){
		if (!seg_revisa_permiso($iCodModulo, 8, $objDB)){
			$sError=$ERR['8'];
			}
		}
	if ($sError==''){
		//Ver que el consecutivo no exista.
		$sSQL='SELECT saiu61id FROM saiu61comunicados WHERE saiu61consec='.$_REQUEST['saiu61consec_nuevo'].'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$sError='El consecutivo '.$_REQUEST['saiu61consec_nuevo'].' ya existe';
			}
		}
	if ($sError==''){
		//Aplicar el cambio.
		$sSQL='UPDATE saiu61comunicados SET saiu61consec='.$_REQUEST['saiu61consec_nuevo'].' WHERE saiu61id='.$_REQUEST['saiu61id'].'';
		$tabla=$objDB->ejecutasql($sSQL);
		$sDetalle='Cambia el consecutivo de '.$_REQUEST['saiu61consec'].' a '.$_REQUEST['saiu61consec_nuevo'].'';
		$_REQUEST['saiu61consec']=$_REQUEST['saiu61consec_nuevo'];
		$_REQUEST['saiu61consec_nuevo']='';
		seg_auditar($iCodModulo, $_SESSION['u_idtercero'], 8, $_REQUEST['saiu61id'], $sDetalle, $objDB);
		$sError='<b>Se ha aplicado el cambio de consecutivo.</b>';
		$iTipoError=1;
		}else{
		$iSector=93;
		}
	}
//Eliminar un elemento
if ($_REQUEST['paso']==13){
	$_REQUEST['paso']=2;
	list($sError, $iTipoError, $sDebugElimina)=f3061_db_Eliminar($_REQUEST['saiu61id'], $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugElimina;
	if ($sError==''){
		$_REQUEST['paso']=-1;
		$sError=$ETI['msg_itemeliminado'];
		$iTipoError=1;
		}
	}
$sInfoProceso='';
if (($_REQUEST['paso']==50)){
	$_REQUEST['paso']=2;
	if (!seg_revisa_permiso($iCodModulo, 2, $objDB)){
		$sError=$ERR['2'];
		}
	if ($sError==''){
		list($sError, $iTipoError, $sInfoProceso, $sDebugP)=f3061_ProcesarArchivo($_REQUEST, $_FILES, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugP;
		}
	}
//limpiar la pantalla
if ($_REQUEST['paso']==-1){
	$_REQUEST['saiu61consec']='';
	$_REQUEST['saiu61consec_nuevo']='';
	$_REQUEST['saiu61id']='';
	$_REQUEST['saiu61orden']='';
	$_REQUEST['saiu61vigente']=1;
	$_REQUEST['saiu61titulo']='';
	$_REQUEST['saiu61idunidad']='';
	$_REQUEST['saiu61fecha']='';//fecha_hoy();
	$_REQUEST['saiu61fechapublica']='';//fecha_hoy();
	$_REQUEST['saiu61fechadespublica']='';//fecha_hoy();
	$_REQUEST['saiu61cuerpo']='';
	$_REQUEST['saiu61poblacion']=0;
	$_REQUEST['saiu61formaentrega']=0;
	$_REQUEST['paso']=0;
	}
if ($bLimpiaHijos){
	$_REQUEST['saiu62idcomunicado']='';
	$_REQUEST['saiu62idtercero']=0;//$idTercero;
	$_REQUEST['saiu62idtercero_td']=$APP->tipo_doc;
	$_REQUEST['saiu62idtercero_doc']='';
	$_REQUEST['saiu62id']='';
	$_REQUEST['saiu62idperiodo']='';
	$_REQUEST['saiu62idescuela']='';
	$_REQUEST['saiu62idprograma']='';
	$_REQUEST['saiu62idzona']='';
	$_REQUEST['saiu62idcentro']='';
	$_REQUEST['saiu62estado']='';
	$_REQUEST['saiu62fecha']='';//fecha_hoy();
	$_REQUEST['saiu62fhora']=fecha_hora();
	$_REQUEST['saiu62min']=fecha_minuto();
	$_REQUEST['saiu62mailenviado']=0;
	}
//AQUI SE DEBEN CARGAR TODOS LOS DATOS QUE LA FORMA NECESITE.
//DATOS PARA COMPLETAR EL FORMULARIO
//Permisos adicionales
$seg_5=0;
$seg_6=0;
$seg_8=0;
//list($bDevuelve, $sDebugP)=seg_revisa_permisoV3($iCodModulo, 6, $idTercero, $objDB);
//if ($bDevuelve){$seg_6=1;}
//if ($seg_6==1){}
//Crear los controles que requieran llamado a base de datos
$objCombos=new clsHtmlCombos();
$objTercero=new clsHtmlTercero();
$objForma=new clsHtmlForma($iPiel);
$objCombos->nuevo('saiu61vigente', $_REQUEST['saiu61vigente'], true, $ETI['no'], 0);
$objCombos->addItem(1, $ETI['si']);
//$objCombos->addArreglo($asaiu61vigente, $isaiu61vigente);
$html_saiu61vigente=$objCombos->html('', $objDB);
$objCombos->nuevo('saiu61idunidad', $_REQUEST['saiu61idunidad'], true, '{'.$ETI['msg_seleccione'].'}');
//$sSQL='SELECT unae26id AS id, unae26nombre AS nombre FROM unae26unidadesfun ORDER BY unae26nombre';
$sSQL26=f226_ConsultaCombo();
$html_saiu61idunidad=$objCombos->html($sSQL26, $objDB);
$objCombos->nuevo('saiu61poblacion', $_REQUEST['saiu61poblacion'], true, $asaiu61poblacion[0], 0);
$objCombos->addArreglo($asaiu61poblacion, $isaiu61poblacion);
$html_saiu61poblacion=$objCombos->html('', $objDB);
$objCombos->nuevo('saiu61formaentrega', $_REQUEST['saiu61formaentrega'], true, $asaiu61formaentrega[0], 0);
$objCombos->addArreglo($asaiu61formaentrega, $isaiu61formaentrega);
$html_saiu61formaentrega=$objCombos->html('', $objDB);
if ((int)$_REQUEST['paso']==0){
	}else{
	if (false){
	list($saiu62idtercero_rs, $_REQUEST['saiu62idtercero'], $_REQUEST['saiu62idtercero_td'], $_REQUEST['saiu62idtercero_doc'])=html_tercero($_REQUEST['saiu62idtercero_td'], $_REQUEST['saiu62idtercero_doc'], $_REQUEST['saiu62idtercero'], 0, $objDB);
	$objCombos->nuevo('saiu62idperiodo', $_REQUEST['saiu62idperiodo'], true, '{'.$ETI['msg_seleccione'].'}');
	$sSQL='SELECT exte02id AS id, exte02nombre AS nombre FROM exte02per_aca ORDER BY exte02nombre';
	$html_saiu62idperiodo=$objCombos->html($sSQL, $objDB);
	$objCombos->nuevo('saiu62idescuela', $_REQUEST['saiu62idescuela'], true, '{'.$ETI['msg_seleccione'].'}');
	$objCombos->sAccion='carga_combo_saiu62idprograma();';
	$sSQL='SELECT core12id AS id, core12nombre AS nombre FROM core12escuela ORDER BY core12nombre';
	$html_saiu62idescuela=$objCombos->html($sSQL, $objDB);
	$html_saiu62idprograma=f3062_HTMLComboV2_saiu62idprograma($objDB, $objCombos, $_REQUEST['saiu62idprograma'], $_REQUEST['saiu62idescuela']);
	$objCombos->nuevo('saiu62idzona', $_REQUEST['saiu62idzona'], true, '{'.$ETI['msg_seleccione'].'}');
	$objCombos->sAccion='carga_combo_saiu62idcentro();';
	$sSQL='SELECT unad23id AS id, unad23nombre AS nombre FROM unad23zona ORDER BY unad23nombre';
	$html_saiu62idzona=$objCombos->html($sSQL, $objDB);
	$html_saiu62idcentro=f3062_HTMLComboV2_saiu62idcentro($objDB, $objCombos, $_REQUEST['saiu62idcentro'], $_REQUEST['saiu62idzona']);
	$objCombos->nuevo('saiu62estado', $_REQUEST['saiu62estado'], true, '{'.$ETI['msg_seleccione'].'}');
	$sSQL='SELECT saiu59id AS id, saiu59nombre AS nombre FROM saiu59estadocomunica ORDER BY saiu59nombre';
	$html_saiu62estado=$objCombos->html($sSQL, $objDB);
	$objCombos->nuevo('saiu62mailenviado', $_REQUEST['saiu62mailenviado'], true, $ETI['no'], 0);
	$objCombos->addItem(1, $ETI['si']);
	//$objCombos->addArreglo($asaiu62mailenviado, $isaiu62mailenviado);
	$html_saiu62mailenviado=$objCombos->html('', $objDB);
	}
	}
//Alistar datos adicionales
$id_rpt=0;
//$id_rpt=reportes_id(_Identificador_Tipo_Reporte_, $objDB);
/*
$objCombos->nuevo('blistar', $_REQUEST['blistar'], true, '{'.$ETI['msg_todos'].'}');
$objCombos->sAccion='paginarf3061()';
$html_blistar=$objCombos->html('', $objDB);
//$html_blistar=$objCombos->comboSistema(3061, 1, $objDB, 'paginarf3061()');
$objCombos->nuevo('blistar3062', $_REQUEST['blistar3062'], true, '{'.$ETI['msg_todos'].'}');
$html_blistar3062=$objCombos->comboSistema(3062, 1, $objDB, 'paginarf3062()');
*/
if ((int)$_REQUEST['paso']!=0){
	$objCombos->nuevo('blistar3062', $_REQUEST['blistar3062'], true, '{'.$ETI['msg_todos'].'}');
	$objCombos->sAccion='paginarf3062()';
	$sSQL='SELECT saiu59id AS id, saiu59nombre AS nombre FROM saiu59estadocomunica ORDER BY saiu59id';
	$html_blistar=$objCombos->html($sSQL, $objDB);
	}
if (false){
	$objCombos->nuevo('csv_separa', $_REQUEST['csv_separa'], false);
	$objCombos->addItem(',', $ETI['msg_coma']);
	$objCombos->addItem(';', $ETI['msg_puntoycoma']);
	$csv_separa='<label class="Label90">'.$ETI['msg_separador'].'</label><label class="Label130">'.$objCombos->html('', $objDB).'</label>';
	}else{
	$csv_separa='<input id="csv_separa" name="csv_separa" type="hidden" value="," />';
	}
$iNumFormatosImprime=0;
$iModeloReporte=3061;
$html_iFormatoImprime='<input id="iformatoimprime" name="iformatoimprime" type="hidden" value="0" />
';
if ($_REQUEST['paso']>0){
	$bDevuelve=false;
	//list($bDevuelve, $sDebugP)=seg_revisa_permisoV3($iCodModulo, 5, $idTercero, $objDB);
	if ($bDevuelve){
		$seg_5=1;
		}
	list($bDevuelve, $sDebugP)=seg_revisa_permisoV3($iCodModulo, 8, $idTercero, $objDB);
	if ($bDevuelve){$seg_8=1;}
	}
//Cargar las tablas de datos
$aParametros[0]='';//$_REQUEST['p1_3061'];
$aParametros[100]=$idTercero;
$aParametros[101]=$_REQUEST['paginaf3061'];
$aParametros[102]=$_REQUEST['lppf3061'];
//$aParametros[103]=$_REQUEST['bnombre'];
//$aParametros[104]=$_REQUEST['blistar'];
list($sTabla3061, $sDebugTabla)=f3061_TablaDetalleV2($aParametros, $objDB, $bDebug);
$sDebug=$sDebug.$sDebugTabla;
$sTabla3062='';
if ($_REQUEST['paso']!=0){
	//Notificados
	$aParametros3062[0]=$_REQUEST['saiu61id'];
	$aParametros3062[100]=$idTercero;
	$aParametros3062[101]=$_REQUEST['paginaf3062'];
	$aParametros3062[102]=$_REQUEST['lppf3062'];
	$aParametros3062[103]=$_REQUEST['bnombre3062'];
	$aParametros3062[104]=$_REQUEST['blistar3062'];
	$aParametros3062[105]=$_REQUEST['bdoc3062'];
	list($sTabla3062, $sDebugTabla)=f3062_TablaDetalleV2($aParametros3062, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	}
$bDebugMenu=false;
list($et_menu, $sDebugM)=html_menuV2($APP->idsistema, $objDB, $iPiel, $bDebugMenu, $idTercero);
$sDebug=$sDebug.$sDebugM;
$objDB->CerrarConexion();
//FORMA
require $APP->rutacomun.'unad_forma_v2.php';
forma_cabeceraV3($xajax, $ETI['titulo_3061']);
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
		xajax_unad11_TraerXid(params);
		}
	}
function imprimelista(){
	if (window.document.frmedita.seg_6.value==1){
		window.document.frmlista.consulta.value=window.document.frmedita.consulta_3061.value;
		window.document.frmlista.titulos.value=window.document.frmedita.titulos_3061.value;
		window.document.frmlista.nombrearchivo.value='Comunicados';
		window.document.frmlista.submit();
		}else{
		ModalMensaje("<?php echo $ERR['6']; ?>");
		}
	}
function asignarvariables(){
	//window.document.frmimpp.v3.value=window.document.frmedita.bnombre.value;
	//window.document.frmimpp.v4.value=window.document.frmedita.bcodigo.value;
	//window.document.frmimpp.separa.value=window.document.frmedita.csv_separa.value.trim();
	}
function imprimeexcel(){
	var sError='';
	if (window.document.frmedita.seg_6.value!=1){sError="<?php echo $ERR['6']; ?>";}
	//if (sError==''){/*Agregar validaciones*/}
	if (sError==''){
		asignarvariables();
		window.document.frmimpp.action='e3061.php';
		window.document.frmimpp.submit();
		}else{
		ModalMensaje(sError);
		}
	}
function imprimep(){
	if (window.document.frmedita.seg_5.value==1){
		asignarvariables();
		window.document.frmimpp.action='p3061.php';
		window.document.frmimpp.submit();
<?php
if ($iNumFormatosImprime>0){
?>
		expandesector(1);
<?php
	}
?>
		}else{
		ModalMensaje("<?php echo $ERR['5']; ?>");
		}
	}
function verrpt(){
	window.document.frmimprime.submit();
	}
function eliminadato(){
	ModalConfirm('<?php echo $ETI['msg_confirmaeliminar']; ?>');
	ModalDialogConfirm(function(confirm){if(confirm){ejecuta_eliminadato();}});
	}
function ejecuta_eliminadato(){
	MensajeAlarmaV2('<?php echo $ETI['msg_ejecutando']; ?>', 2);
	expandesector(98);
	window.document.frmedita.paso.value=13;
	window.document.frmedita.submit();
	}
function RevisaLlave(){
	var datos= new Array();
	datos[1]=window.document.frmedita.saiu61consec.value;
	if ((datos[1]!='')){
		xajax_f3061_ExisteDato(datos);
		}
	}
function cargadato(llave1){
	window.document.frmedita.saiu61consec.value=String(llave1);
	window.document.frmedita.paso.value=1;
	window.document.frmedita.submit();
	}
function cargaridf3061(llave1){
	window.document.frmedita.saiu61id.value=String(llave1);
	window.document.frmedita.paso.value=3;
	window.document.frmedita.submit();
	}
function paginarf3061(){
	var params=new Array();
	params[99]=window.document.frmedita.debug.value;
	params[100]=<?php echo $idTercero; ?>;
	params[101]=window.document.frmedita.paginaf3061.value;
	params[102]=window.document.frmedita.lppf3061.value;
	//params[103]=window.document.frmedita.bnombre.value;
	//params[104]=window.document.frmedita.blistar.value;
	//document.getElementById('div_f3061detalle').innerHTML='<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf3061" name="paginaf3061" type="hidden" value="'+params[101]+'" /><input id="lppf3061" name="lppf3061" type="hidden" value="'+params[102]+'" />';
	xajax_f3061_HtmlTabla(params);
	}
<?php
if ($_REQUEST['paso']==2){
?>
function f3061_cargamasiva(){
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
function eliminaidf3062(id62){
	ModalConfirm('<?php echo $ETI['msg_confirmaeliminar']; ?>');
	ModalDialogConfirm(function(confirm){if(confirm){ejecuta_eliminadato3062(id62);}});
	}
function ejecuta_eliminadato3062(id62){
	var params=new Array();
	params[0]=window.document.frmedita.saiu61id.value;
	params[1]=window.document.frmedita.saiu61id.value;
	params[2]=0;
	params[3]=id62;
	//params[15]=window.document.frmedita.p1_3062.value;
	params[99]=window.document.frmedita.debug.value;
	params[100]=window.document.frmedita.id11.value;
	params[101]=window.document.frmedita.paginaf3062.value;
	params[102]=window.document.frmedita.lppf3062.value;
	xajax_f3062_Eliminar(params);
	}
<?php
	}
?>
function verformato(){
	window.document.frmimpp.action='e3061formato.php';
	//window.document.frmimpp.v3.value=window.document.frmedita.miparametro.value;
	window.document.frmimpp.submit();
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
	document.getElementById("saiu61consec").focus();
	}
function buscarV2016(sCampo){
	window.document.frmedita.iscroll.value=window.pageYOffset;
	expandesector(98);
	window.document.frmedita.scampobusca.value=sCampo;
	var params=new Array();
	params[1]=sCampo;
	//params[2]=window.document.frmedita.iagno.value;
	//params[3]=window.document.frmedita.itipo.value;
	xajax_f3061_Busquedas(params);
	}
function retornacontrol(){
	expandesector(1);
	window.scrollTo(0, window.document.frmedita.iscroll.value);
	}
function Devuelve(sValor){
	var sCampo=window.document.frmedita.scampobusca.value;
	if (sCampo=='saiu62idtercero'){
		ter_traerxid('saiu62idtercero', sValor);
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
function mod_consec(){
	ModalConfirm('<?php echo $ETI['msg_confirmamodconsec']; ?>');
	ModalDialogConfirm(function(confirm){if(confirm){ejecuta_modconsec();}});
	}
function ejecuta_modconsec(){
	MensajeAlarmaV2('<?php echo $ETI['msg_ejecutando']; ?>', 2);
	expandesector(98);
	window.document.frmedita.paso.value=93;
	window.document.frmedita.submit();
	}
</script>
<?php
if ($_REQUEST['paso']!=0){
?>
<script language="javascript" src="jsi/js3062.js"></script>
<?php
	}
?>
<?php
if ($_REQUEST['paso']!=0){
?>
<form id="frmimpp" name="frmimpp" method="post" action="p3061.php" target="_blank">
<input id="r" name="r" type="hidden" value="3061" />
<input id="id3061" name="id3061" type="hidden" value="<?php echo $_REQUEST['saiu61id']; ?>" />
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
<input id="id11" name="id11" type="hidden" value="<?php echo $idTercero; ?>" />
<input id="ipiel" name="ipiel" type="hidden" value="<?php echo $iPiel; ?>" />
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
echo '<h2>'.$ETI['titulo_3061'].'</h2>';
?>
</div>
</div>
<div class="areaform">
<div class="areatrabajo">
<?php
//Div para ocultar
$bConExpande=true;
if ($bConExpande){
?>
<div class="ir_derecha"<?php echo $sAnchoExpandeContrae; ?>>
<input id="boculta3061" name="boculta3061" type="hidden" value="<?php echo $_REQUEST['boculta3061']; ?>" />
<label class="Label30">
<input id="btexpande3061" name="btexpande3061" type="button" value="Mostrar" class="btMiniExpandir" onclick="expandepanel(3061,'block',0);" title="<?php echo $ETI['bt_mostrar']; ?>" style="display:<?php if ($_REQUEST['boculta3061']==0){echo 'none'; }else{echo 'block';} ?>;"/>
</label>
<label class="Label30">
<input id="btrecoge3061" name="btrecoge3061" type="button" value="Ocultar" class="btMiniRecoger" onclick="expandepanel(3061,'none',1);" title="<?php echo $ETI['bt_ocultar']; ?>" style="display:<?php if ($_REQUEST['boculta3061']==0){echo 'block'; }else{echo 'none';} ?>;"/>
</label>
</div>
<div id="div_p3061" style="display:<?php if ($_REQUEST['boculta3061']==0){echo 'block'; }else{echo 'none';} ?>;">
<?php
	}
//Mostrar formulario para editar
?>
<label class="Label130">
<?php
echo $ETI['saiu61consec'];
?>
</label>
<label class="Label130">
<?php
if ($_REQUEST['paso']!=2){
?>
<input id="saiu61consec" name="saiu61consec" type="text" value="<?php echo $_REQUEST['saiu61consec']; ?>" onchange="RevisaLlave()" class="cuatro"/>
<?php
	}else{
	echo html_oculto('saiu61consec', $_REQUEST['saiu61consec'], formato_numero($_REQUEST['saiu61consec']));
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
echo $ETI['saiu61id'];
?>
</label>
<label class="Label60">
<?php
	echo html_oculto('saiu61id', $_REQUEST['saiu61id'], formato_numero($_REQUEST['saiu61id']));
?>
</label>
<label class="Label130">
<?php
echo $ETI['saiu61orden'];
?>
</label>
<label class="Label130">
<input id="saiu61orden" name="saiu61orden" type="text" value="<?php echo $_REQUEST['saiu61orden']; ?>" class="diez" maxlength="10" placeholder="<?php echo $ETI['ing_vr']; ?>"/>
</label>
<label class="Label130">
<?php
echo $ETI['saiu61vigente'];
?>
</label>
<label>
<?php
echo $html_saiu61vigente;
?>
</label>
<label class="L">
<?php
echo $ETI['saiu61titulo'];
?>
<input id="saiu61titulo" name="saiu61titulo" type="text" value="<?php echo $_REQUEST['saiu61titulo']; ?>" maxlength="100" placeholder="<?php echo $ETI['ing_campo'].$ETI['saiu61titulo']; ?>" class="L"/>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu61idunidad'];
?>
</label>
<label>
<?php
echo $html_saiu61idunidad;
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu61fecha'];
?>
</label>
<div class="Campo220">
<?php
echo html_FechaEnNumero('saiu61fecha', $_REQUEST['saiu61fecha']);//$bvacio=false,$accion=",$iagnoini=0,$iagnofin=0
?>
</div>
<!--
<label class="Label30">
<input id="bsaiu61fecha_hoy" name="bsaiu61fecha_hoy" type="button" value="Hoy" class="btMiniHoy" onclick="fecha_AsignarNum('saiu61fecha','<?php echo fecha_DiaMod(); ?>')" title="<?php echo $ETI['bt_hoy']; ?>"/>
</label>
-->
<label class="Label130">
<?php
echo $ETI['saiu61fechapublica'];
?>
</label>
<div class="Campo220">
<?php
echo html_FechaEnNumero('saiu61fechapublica', $_REQUEST['saiu61fechapublica']);//$bvacio=false,$accion=",$iagnoini=0,$iagnofin=0
?>
</div>
<!--
<label class="Label30">
<input id="bsaiu61fechapublica_hoy" name="bsaiu61fechapublica_hoy" type="button" value="Hoy" class="btMiniHoy" onclick="fecha_AsignarNum('saiu61fechapublica','<?php echo fecha_DiaMod(); ?>')" title="<?php echo $ETI['bt_hoy']; ?>"/>
</label>
-->
<label class="Label130">
<?php
echo $ETI['saiu61fechadespublica'];
?>
</label>
<div class="Campo220">
<?php
echo html_FechaEnNumero('saiu61fechadespublica', $_REQUEST['saiu61fechadespublica']);//$bvacio=false,$accion=",$iagnoini=0,$iagnofin=0
?>
</div>
<!--
<label class="Label30">
<input id="bsaiu61fechadespublica_hoy" name="bsaiu61fechadespublica_hoy" type="button" value="Hoy" class="btMiniHoy" onclick="fecha_AsignarNum('saiu61fechadespublica','<?php echo fecha_DiaMod(); ?>')" title="<?php echo $ETI['bt_hoy']; ?>"/>
</label>
-->
<label class="txtAreaL">
<?php
echo $ETI['saiu61cuerpo'];
?>
<textarea id="saiu61cuerpo" name="saiu61cuerpo" placeholder="<?php echo $ETI['ing_campo'].$ETI['saiu61cuerpo']; ?>"><?php echo $_REQUEST['saiu61cuerpo']; ?></textarea>
</label>
<label class="Label130">
<?php
echo $ETI['saiu61poblacion'];
?>
</label>
<label>
<?php
echo $html_saiu61poblacion;
?>
</label>
<label class="Label130">
<?php
echo $ETI['saiu61formaentrega'];
?>
</label>
<label>
<?php
echo $html_saiu61formaentrega;
?>
</label>
<?php
// -- Inicia la carga masiva
$bConMasivo=false;
if ($_REQUEST['paso']==2){
	if ($_REQUEST['saiu61poblacion']==0){$bConMasivo=true;}
	}
if ($bConMasivo){
?>
<div class="salto1px"></div>
<div class="GrupoCampos">
<div class="ir_derecha"<?php echo $sAnchoExpandeContrae; ?>>
<label class="Label30">
<input id="btexcel3061" name="btexcel3061" type="button" value="Formato de carga" class="btMiniExcel" onclick="verformato();" title="Formato de carga"/>
</label>
</div>
<label class="TituloGrupo">
<?php
echo $ETI['msg_plano3061'];
?>
</label>
<div class="salto1px"></div>
<input id="MAX_FILE_SIZE" name="MAX_FILE_SIZE" type="hidden" value="<?php echo (1*1024*1024); ?>" />
<label class="Label500">
<input id="archivodatos" name="archivodatos" type="file" />
</label>
<?php
echo $objForma->htmlBotonSolo('cmdanexar', 'botonAnexar', 'f3061_cargamasiva()', $ETI['msg_subir'], 130);
?>
<?php
if ($sInfoProceso!=''){
?>
<div class="salto1px"></div>
<div style="height:100px;overflow:scroll;overflow-x:hidden;">
<?php
echo $sInfoProceso;
?>
</div>
<?php
	}
?>
<div class="salto1px"></div>
<div class="GrupoCamposAyuda">
<?php
echo $ETI['msg_infoplano3061'];
?>
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
</div>
<?php
	}
//Termina la carga masiva.
// -- Inicia Grupo campos 3062 Notificados
?>
<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo $ETI['titulo_3062'];
?>
</label>
<input id="boculta3062" name="boculta3062" type="hidden" value="<?php echo $_REQUEST['boculta3062']; ?>" />
<?php
if ($_REQUEST['paso']==2){
	if (false){
?>
<div class="ir_derecha"<?php echo $sAnchoExpandeContrae; ?>>
<!--
<label class="Label30">
<input id="btexcel3062" name="btexcel3062" type="button" value="Exportar" class="btMiniExcel" onclick="imprime3062();" title="Exportar"/>
</label>
-->
<label class="Label30">
<input id="btexpande3062" name="btexpande3062" type="button" value="Expandir" class="btMiniExpandir" onclick="expandepanel(3062,'block',0);" title="<?php echo $ETI['bt_mostrar']; ?>" style="display:<?php if ($_REQUEST['boculta3062']==0){echo 'none'; }else{echo 'block';} ?>;"/>
</label>
<label class="Label30">
<input id="btrecoge3062" name="btrecoge3062" type="button" value="Recoger" class="btMiniRecoger" onclick="expandepanel(3062,'none',1);" title="<?php echo $ETI['bt_ocultar']; ?>" style="display:<?php if ($_REQUEST['boculta3062']==0){echo 'block'; }else{echo 'none';} ?>;"/>
</label>
</div>
<div class="salto1px"></div>
<div id="div_p3062" style="display:<?php if ($_REQUEST['boculta3062']==0){echo 'block'; }else{echo 'none';} ?>;">
<div class="salto1px"></div>
<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo $ETI['saiu62idtercero'];
?>
</label>
<div class="salto1px"></div>
<input id="saiu62idtercero" name="saiu62idtercero" type="hidden" value="<?php echo $_REQUEST['saiu62idtercero']; ?>"/>
<div id="div_saiu62idtercero_llaves">
<?php
$bOculto=true;
if ((int)$_REQUEST['saiu62id']==0){$bOculto=false;}
echo html_DivTerceroV2('saiu62idtercero', $_REQUEST['saiu62idtercero_td'], $_REQUEST['saiu62idtercero_doc'], $bOculto, 1, $ETI['ing_doc']);
?>
</div>
<div class="salto1px"></div>
<div id="div_saiu62idtercero" class="L"><?php echo $saiu62idtercero_rs; ?></div>
<div class="salto1px"></div>
</div>
<label class="Label60">
<?php
echo $ETI['saiu62id'];
?>
</label>
<label class="Label60"><div id="div_saiu62id">
<?php
	echo html_oculto('saiu62id', $_REQUEST['saiu62id'], formato_numero($_REQUEST['saiu62id']));
?>
</div></label>
<label class="Label130">
<?php
echo $ETI['saiu62idperiodo'];
?>
</label>
<label>
<?php
echo $html_saiu62idperiodo;
?>
</label>
<label class="Label130">
<?php
echo $ETI['saiu62idescuela'];
?>
</label>
<label>
<?php
echo $html_saiu62idescuela;
?>
</label>
<label class="Label130">
<?php
echo $ETI['saiu62idprograma'];
?>
</label>
<label>
<div id="div_saiu62idprograma">
<?php
echo $html_saiu62idprograma;
?>
</div>
</label>
<label class="Label130">
<?php
echo $ETI['saiu62idzona'];
?>
</label>
<label>
<?php
echo $html_saiu62idzona;
?>
</label>
<label class="Label130">
<?php
echo $ETI['saiu62idcentro'];
?>
</label>
<label>
<div id="div_saiu62idcentro">
<?php
echo $html_saiu62idcentro;
?>
</div>
</label>
<label class="Label130">
<?php
echo $ETI['saiu62estado'];
?>
</label>
<label>
<?php
echo $html_saiu62estado;
?>
</label>
<label class="Label130">
<?php
echo $ETI['saiu62fecha'];
?>
</label>
<div class="Campo220">
<?php
echo html_FechaEnNumero('saiu62fecha', $_REQUEST['saiu62fecha']);//$bvacio=false,$accion=",$iagnoini=0,$iagnofin=0
?>
</div>
<!--
<label class="Label30">
<input id="bsaiu62fecha_hoy" name="bsaiu62fecha_hoy" type="button" value="Hoy" class="btMiniHoy" onclick="fecha_AsignarNum('saiu62fecha','<?php echo fecha_DiaMod(); ?>')" title="<?php echo $ETI['bt_hoy']; ?>"/>
</label>
-->
<label class="Label130">
<?php
echo $ETI['saiu62fhora'];
?>
</label>
<div class="campo_HoraMin" id="div_saiu62fhora">
<?php
echo html_HoraMin('saiu62fhora', $_REQUEST['saiu62fhora'], 'saiu62min', $_REQUEST['saiu62min']);
?>
</div>
<label class="Label130">
<?php
echo $ETI['saiu62mailenviado'];
?>
</label>
<label>
<?php
echo $html_saiu62mailenviado;
?>
</label>
<div class="salto1px"></div>
<label class="Label130">&nbsp;</label>
<label class="Label30">
<input id="bguarda3062" name="bguarda3062" type="button" value="Guardar" class="btMiniGuardar" onclick="guardaf3062()" title="<?php echo $ETI['bt_mini_guardar_3062']; ?>"/>
</label>
<label class="Label30">
<input id="blimpia3062" name="blimpia3062" type="button" value="Limpiar" class="btMiniLimpiar" onclick="limpiaf3062()" title="<?php echo $ETI['bt_mini_limpiar_3062']; ?>"/>
</label>
<label class="Label30">
<input id="belimina3062" name="belimina3062" type="button" value="Eliminar" class="btMiniEliminar" onclick="eliminaf3062()" title="<?php echo $ETI['bt_mini_eliminar_3062']; ?>" style="display:<?php if ((int)$_REQUEST['saiu62id']!=0){echo 'block';}else{echo 'none';} ?>;"/>
</label>
<?php
//Este es el cierre del div_p3062
?>
<div class="salto1px"></div>
</div>
<?php
		} //Termina el segundo bloque  condicional - bloque editar.
?>
<div class="salto1px"></div>
<div class="ir_derecha">
<label class="Label130">
<?php
echo $ETI['msg_nombre'];
?>
</label>
<label>
<input id="bnombre3062" name="bnombre3062" type="text" value="<?php echo $_REQUEST['bnombre3062']; ?>" onchange="paginarf3062()"/>
</label>
<label class="Label130">
<?php
echo $ETI['msg_Listar'];
?>
</label>
<label>
<?php
echo $html_blistar3062;
?>
</label>
<div class="salto1px"></div>
</div>
<div id="div_f3062detalle">
<?php
echo $sTabla3062;
?>
</div>
<?php
	}
?>
<div class="salto1px"></div>
</div>
<?php
// -- Termina Grupo campos 3062 Notificados
?>
<?php
if (false){
	//Ejemplo de boton de ayuda
	//echo html_BotonAyuda('NombreCampo');
	//echo html_DivAyudaLocal('NombreCampo');
	}
if ($bConExpande){
	//Este es el cierre del div_p3061
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
<?php
echo $ETI['msg_bnombre'];
?>
</label>
<label>
<input id="bnombre" name="bnombre" type="text" value="<?php echo $_REQUEST['bnombre']; ?>" onchange="paginarf3061()" autocomplete="off"/>
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
<div id="div_f3061detalle">
<?php
echo $sTabla3061;
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
echo $ETI['msg_saiu61consec'];
?>
</label>
<label class="Label90">
<?php
echo '<b>'.$_REQUEST['saiu61consec'].'</b>';
?>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['msg_saiu61consec_nuevo'];
// onchange="RevisaConsec()"
?>
</label>
<label class="Label90">
<input id="saiu61consec_nuevo" name="saiu61consec_nuevo" type="text" value="<?php echo $_REQUEST['saiu61consec_nuevo']; ?>" class="cuatro"/>
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
<input id="titulo_3061" name="titulo_3061" type="hidden" value="<?php echo $ETI['titulo_3061']; ?>" />
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
echo '<h2>'.$ETI['titulo_3061'].'</h2>';
?>
</div>
</div>
<div id="areaform">
<div id="areatrabajo">
<div id="div_97params"></div>
<div class="salto1px"></div>
<div id="div_97tabla"></div>
</div><!-- /DIV_areatrabajo -->
</div><!-- /DIV_areaform -->
</div><!-- /DIV_Sector97 -->


<div id="div_sector98" style="display:none">
<div class="titulos">
<div class="titulosD">
<input id="cmdAyuda98" name="cmdAyuda98" type="button" class="btSupAyuda" onclick="muestraayuda(<?php echo $iCodModulo; ?>);" title="<?php echo $ETI['bt_ayuda']; ?>" value="<?php echo $ETI['bt_ayuda']; ?>"/>
</div>
<div class="titulosI">
<?php
echo '<h2>'.$ETI['titulo_3061'].'</h2>';
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
</script>
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>js/jquery.autocomplete.css" type="text/css"/>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>js/jquery.autocomplete.js"></script>
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
<script language="javascript" src="ac_3061.js"></script>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>unad_todas.js?ver=8"></script>
<?php
forma_piedepagina();
?>