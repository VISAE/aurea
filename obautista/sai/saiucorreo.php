<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2020 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.25.4 domingo, 19 de julio de 2020
*/
/** Archivo saiucorreo.php.
* Modulo 3020 saiu20correo.
* @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
* @param debug=1 (Opcional), bandera para indicar si se generan datos de depuración
* @date domingo, 19 de julio de 2020
*/
if (file_exists('./err_control.php')){require './err_control.php';}
$bDebug=false;
$sDebug='';
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
require $APP->rutacomun.'libmail.php';
require $APP->rutacomun.'libaurea.php';
if (($bPeticionXAJAX)&&($_SESSION['unad_id_tercero']==0)){
	// viene por xajax.
	$xajax=new xajax();
	$xajax->configure('javascript URI', $APP->rutacomun.'xajax/');
	$xajax->register(XAJAX_FUNCTION,'sesion_abandona_V2');
	$xajax->processRequest();
	die();
	}
$bEnSesion = false;
if ((int)$_SESSION['unad_id_tercero'] > 0) {
	$bEnSesion = true;
}
if (!$bEnSesion) {
	if ($bDebug) {
		echo 'No se encuentra una sesi&oacute;n. [' . $APP->rutacomun . ']-[' . $_SESSION['unad_id_tercero'] . ']';
		die();
	}
	$_SESSION['unad_redir'] = 'saiucorreo.php';
	header('Location:index.php');
	die();
}
$grupo_id=1;//Necesita ajustarlo...
$iCodModulo=3020;
$audita[1]=false;
$audita[2]=true;
$audita[3]=true;
$audita[4]=true;
$audita[5]=false;
// -- Se cargan los archivos de idioma
$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
$mensajes_3020='lg/lg_3020_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_3020)){$mensajes_3020='lg/lg_3020_es.php';}
require $mensajes_todas;
require $mensajes_3020;
$xajax=NULL;
$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
$iPiel = iDefinirPiel($APP, 2);
$sAnchoExpandeContrae = ' style="width:62px;"';
$sOcultaId = ' style="display:none;"';
$sOcultaConsec = ''; //' style="display:none;"';
$bCerrado = false;
$et_menu = '';
if ($bDebug) {
	$sDebug = $sDebug . fecha_microtiempo() . ' Probando conexi&oacute;n con la base de datos <b>' . $APP->dbname . '</b> en <b>' . $APP->dbhost . '</b><br>';
}
if (!$objDB->Conectar()) {
	$bCerrado = true;
	$sMsgCierre = '<div class="MarquesinaGrande">Disculpe las molestias estamos en este momento nuestros servicios no estas disponibles.<br>Por favor intente acceder mas tarde.<br>Si el problema persiste por favor informa al administrador del sistema.</div>';
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' Error al intentar conectar con la base de datos <b>' . $objDB->serror . '</b><br>';
	}
}
if (!$bCerrado) {
	$bDevuelve = true;
	//list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 1, $_SESSION['unad_id_tercero'], $objDB);
	if (!$bDevuelve) {
		$bCerrado = true;
		$sMsgCierre = '<div class="MarquesinaGrande">No cuenta con permiso para acceder a este modulo [' . $iCodModulo . '].</div>';
		list($et_menu, $sDebugM) = html_menuV2($APP->idsistema, $objDB, $iPiel, false, $_SESSION['unad_id_tercero']);
	}
}
if ($bCerrado) {
	$objDB->CerrarConexion();
	require $APP->rutacomun . 'unad_forma_v2.php';
	forma_cabeceraV3($xajax, $ETI['titulo_3020']);
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
	echo $sMsgCierre;
	if ($bDebug) {
		echo $sDebug;
	}
	forma_piedepagina();
	die();
}
if (!$bPeticionXAJAX){
	if (noticias_pendientes($objDB)){
		$objDB->CerrarConexion();
		header('Location:noticia.php?ret=saiuchat.php');
		die();
		}
	}
$idTercero=$_SESSION['unad_id_tercero'];
$bOtroUsuario=false;
$seg_1707 = 0;
$bDevuelve = false;
list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 1707, $_SESSION['unad_id_tercero'], $objDB, $bDebug);
$sDebug = $sDebug . $sDebugP;
if ($bDevuelve) {
	$seg_1707 = 1;
}
if (isset($_REQUEST['deb_tipodoc']) == 0) {
	$_REQUEST['deb_tipodoc'] = $APP->tipo_doc;
}
if ($_REQUEST['deb_doc'] != '') {
	if ($seg_1707 == 1) {
		$sSQL = 'SELECT unad11id, unad11razonsocial FROM unad11terceros WHERE unad11doc="' . $_REQUEST['deb_doc'] . '" AND unad11tipodoc="' . $_REQUEST['deb_tipodoc'] . '"';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			$idTercero = $fila['unad11id'];
			$bOtroUsuario = true;
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . ' Se verifica la ventana de trabajo para el usuario ' . $fila['unad11razonsocial'] . '.<br>';
			}
		} else {
			$sError = 'No se ha encontrado el documento &quot;' . $_REQUEST['deb_tipodoc'] . ' ' . $_REQUEST['deb_doc'] . '&quot;';
			$_REQUEST['deb_doc'] = '';
		}
	} else {
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' No cuenta con permiso de ingreso como otro usuario [Modulo ' . $iCodModulo . ' Permiso 1707]<br>';
		}
		$_REQUEST['deb_doc'] = '';
	}
	$bDebug = false;
}
if (isset($_REQUEST['debug']) != 0) {
	if ($_REQUEST['debug'] == 1) {
		$bDebug = true;
		$sOcultaId = '';
	}
} else {
	$_REQUEST['debug'] = 0;
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
// -- 3020 saiu20correos
require 'lib3020.php';
// -- 3000 Historial de solicitudes
require $APP->rutacomun.'lib3000.php';
$xajax=new xajax();
$xajax->configure('javascript URI', $APP->rutacomun.'xajax/');
$xajax->register(XAJAX_FUNCTION,'unad11_Mostrar_v2');
$xajax->register(XAJAX_FUNCTION,'unad11_Mostrar_v2SAI');
$xajax->register(XAJAX_FUNCTION,'unad11_TraerXid');
$xajax->register(XAJAX_FUNCTION,'unad11_TraerXidSAI');
$xajax->register(XAJAX_FUNCTION,'f3020_Combosaiu20tiposolicitud');
$xajax->register(XAJAX_FUNCTION,'f3020_Combosaiu20temasolicitud');
$xajax->register(XAJAX_FUNCTION,'f3020_Combosaiu20idcentro');
$xajax->register(XAJAX_FUNCTION,'f3020_Combosaiu20coddepto');
$xajax->register(XAJAX_FUNCTION,'f3020_Combosaiu20codciudad');
$xajax->register(XAJAX_FUNCTION,'f3020_Combosaiu20idprograma');
$xajax->register(XAJAX_FUNCTION,'sesion_abandona_V2');
$xajax->register(XAJAX_FUNCTION,'sesion_mantenerV4');
$xajax->register(XAJAX_FUNCTION,'f3020_HtmlTabla');
$xajax->register(XAJAX_FUNCTION,'f3020_ExisteDato');
$xajax->register(XAJAX_FUNCTION,'f3020_Busquedas');
$xajax->register(XAJAX_FUNCTION,'f3020_HtmlBusqueda');
$xajax->register(XAJAX_FUNCTION,'f3000_HtmlTabla');
$xajax->register(XAJAX_FUNCTION,'f3000pqrs_HtmlTabla');
$xajax->register(XAJAX_FUNCTION,'elimina_archivo_saiu20idarchivo');
$xajax->register(XAJAX_FUNCTION,'elimina_archivo_saiu20idarchivorta');
$xajax->register(XAJAX_FUNCTION,'f3020_Combobtema');
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
if (isset($_REQUEST['paginaf3020'])==0){$_REQUEST['paginaf3020']=1;}
if (isset($_REQUEST['lppf3020'])==0){$_REQUEST['lppf3020']=20;}
if (isset($_REQUEST['boculta3020'])==0){$_REQUEST['boculta3020']=0;}
if (isset($_REQUEST['paginaf3000'])==0){$_REQUEST['paginaf3000']=1;}
if (isset($_REQUEST['lppf3000'])==0){$_REQUEST['lppf3000']=10;}
if (isset($_REQUEST['paginaf3000pqrs'])==0){$_REQUEST['paginaf3000pqrs']=1;}
if (isset($_REQUEST['lppf3000pqrs'])==0){$_REQUEST['lppf3000pqrs']=10;}
if (isset($_REQUEST['boculta3000'])==0){$_REQUEST['boculta3000']=0;}
// -- Inicializar variables de datos.
if (isset($_REQUEST['saiu20agno'])==0){$_REQUEST['saiu20agno']='';}
if (isset($_REQUEST['saiu20mes'])==0){$_REQUEST['saiu20mes']='';}
if (isset($_REQUEST['saiu20tiporadicado'])==0){$_REQUEST['saiu20tiporadicado']=1;}
if (isset($_REQUEST['saiu20consec'])==0){$_REQUEST['saiu20consec']='';}
if (isset($_REQUEST['saiu20consec_nuevo'])==0){$_REQUEST['saiu20consec_nuevo']='';}
if (isset($_REQUEST['saiu20id'])==0){$_REQUEST['saiu20id']='';}
if (isset($_REQUEST['saiu20dia'])==0){$_REQUEST['saiu20dia']=fecha_dia();}
if (isset($_REQUEST['saiu20hora'])==0){$_REQUEST['saiu20hora']=fecha_hora();}
if (isset($_REQUEST['saiu20minuto'])==0){$_REQUEST['saiu20minuto']=fecha_minuto();}
if (isset($_REQUEST['saiu20estado'])==0){$_REQUEST['saiu20estado']=-1;}
if (isset($_REQUEST['saiu20estadoorigen'])==0){$_REQUEST['saiu20estadoorigen']=-1;}
if (isset($_REQUEST['saiu20idcorreo'])==0){$_REQUEST['saiu20idcorreo']='';}
if (isset($_REQUEST['saiu20idcorreootro'])==0){$_REQUEST['saiu20idcorreootro']='';}
if (isset($_REQUEST['saiu20idsolicitante'])==0){$_REQUEST['saiu20idsolicitante']=0;}// {$_SESSION['unad_id_tercero'];}
if (isset($_REQUEST['saiu20idsolicitante_td'])==0){$_REQUEST['saiu20idsolicitante_td']=$APP->tipo_doc;}
if (isset($_REQUEST['saiu20idsolicitante_doc'])==0){$_REQUEST['saiu20idsolicitante_doc']='';}
if (isset($_REQUEST['saiu20tipointeresado'])==0){$_REQUEST['saiu20tipointeresado']='';}
if (isset($_REQUEST['saiu20clasesolicitud'])==0){$_REQUEST['saiu20clasesolicitud']=0;}
if (isset($_REQUEST['saiu20tiposolicitud'])==0){$_REQUEST['saiu20tiposolicitud']=0;}
if (isset($_REQUEST['saiu20temasolicitud'])==0){$_REQUEST['saiu20temasolicitud']=0;}
if (isset($_REQUEST['saiu20temasolicitudorigen'])==0){$_REQUEST['saiu20temasolicitudorigen']='';}
if (isset($_REQUEST['saiu20idzona'])==0){$_REQUEST['saiu20idzona']='';}
if (isset($_REQUEST['saiu20idcentro'])==0){$_REQUEST['saiu20idcentro']='';}
if (isset($_REQUEST['saiu20codpais'])==0){$_REQUEST['saiu20codpais']=$_SESSION['unad_pais'];}
if (isset($_REQUEST['saiu20coddepto'])==0){$_REQUEST['saiu20coddepto']='';}
if (isset($_REQUEST['saiu20codciudad'])==0){$_REQUEST['saiu20codciudad']='';}
if (isset($_REQUEST['saiu20idescuela'])==0){$_REQUEST['saiu20idescuela']='';}
if (isset($_REQUEST['saiu20idprograma'])==0){$_REQUEST['saiu20idprograma']='';}
if (isset($_REQUEST['saiu20idperiodo'])==0){$_REQUEST['saiu20idperiodo']='';}
if (isset($_REQUEST['saiu20idpqrs'])==0){$_REQUEST['saiu20idpqrs']=0;}
if (isset($_REQUEST['saiu20detalle'])==0){$_REQUEST['saiu20detalle']='';}
if (isset($_REQUEST['saiu20fechafin'])==0){$_REQUEST['saiu20fechafin']='';}
if (isset($_REQUEST['saiu20horafin'])==0){$_REQUEST['saiu20horafin']='';}
if (isset($_REQUEST['saiu20minutofin'])==0){$_REQUEST['saiu20minutofin']='';}
if (isset($_REQUEST['saiu20paramercadeo'])==0){$_REQUEST['saiu20paramercadeo']=0;}
if (isset($_REQUEST['saiu20idresponsable'])==0){$_REQUEST['saiu20idresponsable']=0;}// {$_SESSION['unad_id_tercero'];}
if (isset($_REQUEST['saiu20idresponsable_td'])==0){$_REQUEST['saiu20idresponsable_td']=$APP->tipo_doc;}
if (isset($_REQUEST['saiu20idresponsable_doc'])==0){$_REQUEST['saiu20idresponsable_doc']='';}
if (isset($_REQUEST['saiu20tiemprespdias'])==0){$_REQUEST['saiu20tiemprespdias']='';}
if (isset($_REQUEST['saiu20tiempresphoras'])==0){$_REQUEST['saiu20tiempresphoras']='';}
if (isset($_REQUEST['saiu20tiemprespminutos'])==0){$_REQUEST['saiu20tiemprespminutos']='';}
if (isset($_REQUEST['saiu20solucion'])==0){$_REQUEST['saiu20solucion']=0;}
if (isset($_REQUEST['saiu20idcaso'])==0){$_REQUEST['saiu20idcaso']=0;}
if (isset($_REQUEST['saiu20respuesta'])==0){$_REQUEST['saiu20respuesta']='';}
if (isset($_REQUEST['saiu20correoorigen'])==0){$_REQUEST['saiu20correoorigen']='';}
if (isset($_REQUEST['saiu20idorigen'])==0){$_REQUEST['saiu20idorigen']=0;}
if (isset($_REQUEST['saiu20idarchivo'])==0){$_REQUEST['saiu20idarchivo']=0;}
if (isset($_REQUEST['saiu20idorigenrta'])==0){$_REQUEST['saiu20idorigenrta']=0;}
if (isset($_REQUEST['saiu20idarchivorta'])==0){$_REQUEST['saiu20idarchivorta']=0;}
if (isset($_REQUEST['saiu20fecharespcaso'])==0){$_REQUEST['saiu20fecharespcaso']='';}
if (isset($_REQUEST['saiu20horarespcaso'])==0){$_REQUEST['saiu20horarespcaso']=0;}
if (isset($_REQUEST['saiu20minrespcaso'])==0){$_REQUEST['saiu20minrespcaso']=0;}
if (isset($_REQUEST['saiu20idunidadcaso'])==0){$_REQUEST['saiu20idunidadcaso']=0;}
if (isset($_REQUEST['saiu20idequipocaso'])==0){$_REQUEST['saiu20idequipocaso']=0;}
if (isset($_REQUEST['saiu20idsupervisorcaso'])==0){$_REQUEST['saiu20idsupervisorcaso']=0;}
if (isset($_REQUEST['saiu20idresponsablecaso'])==0){$_REQUEST['saiu20idresponsablecaso']=0;}
if (isset($_REQUEST['saiu20numref'])==0){$_REQUEST['saiu20numref']='';}
if (isset($_REQUEST['saiu20evalacepta'])==0){$_REQUEST['saiu20evalacepta']=0;}
if (isset($_REQUEST['saiu20evalfecha'])==0){$_REQUEST['saiu20evalfecha']='';}
if (isset($_REQUEST['saiu20evalamabilidad'])==0){$_REQUEST['saiu20evalamabilidad']=0;}
if (isset($_REQUEST['saiu20evalamabmotivo'])==0){$_REQUEST['saiu20evalamabmotivo']='';}
if (isset($_REQUEST['saiu20evalrapidez'])==0){$_REQUEST['saiu20evalrapidez']=0;}
if (isset($_REQUEST['saiu20evalrapidmotivo'])==0){$_REQUEST['saiu20evalrapidmotivo']='';}
if (isset($_REQUEST['saiu20evalclaridad'])==0){$_REQUEST['saiu20evalclaridad']=0;}
if (isset($_REQUEST['saiu20evalcalridmotivo'])==0){$_REQUEST['saiu20evalcalridmotivo']='';}
if (isset($_REQUEST['saiu20evalresolvio'])==0){$_REQUEST['saiu20evalresolvio']=0;}
if (isset($_REQUEST['saiu20evalsugerencias'])==0){$_REQUEST['saiu20evalsugerencias']='';}
if (isset($_REQUEST['saiu20evalconocimiento'])==0){$_REQUEST['saiu20evalconocimiento']=0;}
if (isset($_REQUEST['saiu20evalconocmotivo'])==0){$_REQUEST['saiu20evalconocmotivo']='';}
if (isset($_REQUEST['saiu20evalutilidad'])==0){$_REQUEST['saiu20evalutilidad']=0;}
if (isset($_REQUEST['saiu20evalutilmotivo'])==0){$_REQUEST['saiu20evalutilmotivo']='';}
if (isset($_REQUEST['saiu20idresponsablecaso_td'])==0){$_REQUEST['saiu20idresponsablecaso_td']=$APP->tipo_doc;}
if (isset($_REQUEST['saiu20idresponsablecaso_doc'])==0){$_REQUEST['saiu20idresponsablecaso_doc']='';}
// Espacio para inicializar otras variables
if (isset($_REQUEST['csv_separa'])==0){$_REQUEST['csv_separa']=',';}
if (isset($_REQUEST['bnombre'])==0){$_REQUEST['bnombre']='';}
if (isset($_REQUEST['bagno'])==0){$_REQUEST['bagno']=fecha_agno();}
if (isset($_REQUEST['bestado'])==0){$_REQUEST['bestado']=1;}
if (isset($_REQUEST['blistar'])==0){$_REQUEST['blistar']=2;}
if (isset($_REQUEST['bdoc'])==0){$_REQUEST['bdoc']='';}
if (isset($_REQUEST['bcategoria'])==0){$_REQUEST['bcategoria']='';}
if (isset($_REQUEST['btema'])==0){$_REQUEST['btema']='';}
if (isset($_REQUEST['bagnopqrs'])==0){$_REQUEST['bagnopqrs']=fecha_agno();}
if (isset($_REQUEST['vdtipointeresado'])==0){
	$sVr='';
	$sSQL='SELECT bita07id FROM bita07tiposolicitante WHERE bita07predet="S" ORDER BY bita07orden, bita07nombre';
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$sVr=$fila['bita07id'];
		}
	$_REQUEST['vdtipointeresado']=$sVr;
	}
if (isset($_REQUEST['vdidcorreo'])==0){
	$sVr='';
	$sSQL='SELECT saiu57id FROM saiu57correos WHERE saiu57vigente=1 ORDER BY saiu57orden, saiu57consec';
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$sVr=$fila['saiu57id'];
		}
	$_REQUEST['vdidcorreo']=$sVr;
	}
if (isset($_REQUEST['saiucanal'])==0){$_REQUEST['saiucanal']=4;}
//Si Modifica o Elimina Cargar los campos
if (($_REQUEST['paso']==1)||($_REQUEST['paso']==3)){
	$_REQUEST['saiu20idsolicitante_td']=$APP->tipo_doc;
	$_REQUEST['saiu20idsolicitante_doc']='';
	$_REQUEST['saiu20idresponsable_td']=$APP->tipo_doc;
	$_REQUEST['saiu20idresponsable_doc']='';
	$_REQUEST['saiu20idresponsablecaso_td']=$APP->tipo_doc;
	$_REQUEST['saiu20idresponsablecaso_doc']='';
	$sTabla = 'saiu20correo_'.$_REQUEST['saiu20agno'];
	if ($objDB->bexistetabla($sTabla)) {
		list($sErrorR, $sDebugR) = f3020_RevTabla_saiu20correo($_REQUEST['saiu20agno'], $objDB, $bDebug);
		$sError = $sError . $sErrorR;
		$sDebug = $sDebug . $sDebugR;
		if ($_REQUEST['paso']==1) {
			$sSQLcondi='saiu20agno='.$_REQUEST['saiu20agno'].' AND saiu20mes='.$_REQUEST['saiu20mes'].' AND saiu20tiporadicado='.$_REQUEST['saiu20tiporadicado'].' AND saiu20consec='.$_REQUEST['saiu20consec'].'';
		} else {
			$sSQLcondi='saiu20id='.$_REQUEST['saiu20id'].'';
		}
		$sSQL='SELECT * FROM ' . $sTabla . ' WHERE '.$sSQLcondi;
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta registro: '.$sSQL.'<br>';}
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0) {
			$fila=$objDB->sf($tabla);
			$_REQUEST['saiu20agno']=$fila['saiu20agno'];
			$_REQUEST['saiu20mes']=$fila['saiu20mes'];
			$_REQUEST['saiu20tiporadicado']=$fila['saiu20tiporadicado'];
			$_REQUEST['saiu20consec']=$fila['saiu20consec'];
			$_REQUEST['saiu20id']=$fila['saiu20id'];
			$_REQUEST['saiu20dia']=$fila['saiu20dia'];
			$_REQUEST['saiu20hora']=$fila['saiu20hora'];
			$_REQUEST['saiu20minuto']=$fila['saiu20minuto'];
			$_REQUEST['saiu20estado']=$fila['saiu20estado'];
			$_REQUEST['saiu20estadoorigen']=$fila['saiu20estado'];
			$_REQUEST['saiu20idcorreo']=$fila['saiu20idcorreo'];
			$_REQUEST['saiu20idcorreootro']=$fila['saiu20idcorreootro'];
			$_REQUEST['saiu20idsolicitante']=$fila['saiu20idsolicitante'];
			$_REQUEST['saiu20tipointeresado']=$fila['saiu20tipointeresado'];
			$_REQUEST['saiu20clasesolicitud']=$fila['saiu20clasesolicitud'];
			$_REQUEST['saiu20tiposolicitud']=$fila['saiu20tiposolicitud'];
			$_REQUEST['saiu20temasolicitud']=$fila['saiu20temasolicitud'];
			$_REQUEST['saiu20temasolicitudorigen']=$fila['saiu20temasolicitud'];
			$_REQUEST['saiu20idzona']=$fila['saiu20idzona'];
			$_REQUEST['saiu20idcentro']=$fila['saiu20idcentro'];
			$_REQUEST['saiu20codpais']=$fila['saiu20codpais'];
			$_REQUEST['saiu20coddepto']=$fila['saiu20coddepto'];
			$_REQUEST['saiu20codciudad']=$fila['saiu20codciudad'];
			$_REQUEST['saiu20idescuela']=$fila['saiu20idescuela'];
			$_REQUEST['saiu20idprograma']=$fila['saiu20idprograma'];
			$_REQUEST['saiu20idperiodo']=$fila['saiu20idperiodo'];
			$_REQUEST['saiu20idpqrs']=$fila['saiu20idpqrs'];
			$_REQUEST['saiu20detalle']=$fila['saiu20detalle'];
			$_REQUEST['saiu20fechafin']=$fila['saiu20fechafin'];
			$_REQUEST['saiu20horafin']=$fila['saiu20horafin'];
			$_REQUEST['saiu20minutofin']=$fila['saiu20minutofin'];
			$_REQUEST['saiu20paramercadeo']=$fila['saiu20paramercadeo'];
			$_REQUEST['saiu20idresponsable']=$fila['saiu20idresponsable'];
			$_REQUEST['saiu20tiemprespdias']=$fila['saiu20tiemprespdias'];
			$_REQUEST['saiu20tiempresphoras']=$fila['saiu20tiempresphoras'];
			$_REQUEST['saiu20tiemprespminutos']=$fila['saiu20tiemprespminutos'];
			$_REQUEST['saiu20solucion']=$fila['saiu20solucion'];
			$_REQUEST['saiu20idcaso']=$fila['saiu20idcaso'];
			$_REQUEST['saiu20respuesta']=$fila['saiu20respuesta'];
			$_REQUEST['saiu20correoorigen']=$fila['saiu20correoorigen'];
			if ($sError=='') {
				$_REQUEST['saiu20idorigen'] = $fila['saiu20idorigen'];
				$_REQUEST['saiu20idarchivo'] = $fila['saiu20idarchivo'];
				$_REQUEST['saiu20idorigenrta'] = $fila['saiu20idorigenrta'];
				$_REQUEST['saiu20idarchivorta'] = $fila['saiu20idarchivorta'];
				$_REQUEST['saiu20fecharespcaso'] = $fila['saiu20fecharespcaso'];
				$_REQUEST['saiu20horarespcaso'] = $fila['saiu20horarespcaso'];
				$_REQUEST['saiu20minrespcaso'] = $fila['saiu20minrespcaso'];
				$_REQUEST['saiu20idunidadcaso'] = $fila['saiu20idunidadcaso'];
				$_REQUEST['saiu20idequipocaso'] = $fila['saiu20idequipocaso'];
				$_REQUEST['saiu20idsupervisorcaso'] = $fila['saiu20idsupervisorcaso'];
				$_REQUEST['saiu20idresponsablecaso'] = $fila['saiu20idresponsablecaso'];
				$_REQUEST['saiu20numref'] = $fila['saiu20numref'];
				$_REQUEST['saiu20evalacepta'] = $fila['saiu20evalacepta'];
				$_REQUEST['saiu20evalfecha'] = $fila['saiu20evalfecha'];
				$_REQUEST['saiu20evalamabilidad'] = $fila['saiu20evalamabilidad'];
				$_REQUEST['saiu20evalamabmotivo'] = $fila['saiu20evalamabmotivo'];
				$_REQUEST['saiu20evalrapidez'] = $fila['saiu20evalrapidez'];
				$_REQUEST['saiu20evalrapidmotivo'] = $fila['saiu20evalrapidmotivo'];
				$_REQUEST['saiu20evalclaridad'] = $fila['saiu20evalclaridad'];
				$_REQUEST['saiu20evalcalridmotivo'] = $fila['saiu20evalcalridmotivo'];
				$_REQUEST['saiu20evalresolvio'] = $fila['saiu20evalresolvio'];
				$_REQUEST['saiu20evalsugerencias'] = $fila['saiu20evalsugerencias'];
				$_REQUEST['saiu20evalconocimiento'] = $fila['saiu20evalconocimiento'];
				$_REQUEST['saiu20evalconocmotivo'] = $fila['saiu20evalconocmotivo'];
				$_REQUEST['saiu20evalutilidad'] = $fila['saiu20evalutilidad'];
				$_REQUEST['saiu20evalutilmotivo'] = $fila['saiu20evalutilmotivo'];
			}
			$bcargo=true;
			$_REQUEST['paso']=2;
			$_REQUEST['boculta3020']=0;
			$bLimpiaHijos=true;
		} else {
			$_REQUEST['paso']=0;
		}
	} else {
		$sError = 'No ha sido posible encontrar el contenedor para ' . $sTabla . '';
		$_REQUEST['paso'] = -1;
	}
}
//Cerrar
$bCerrando=false;
if ($_REQUEST['paso']==16){
	$_REQUEST['paso']=12;
	$_REQUEST['saiu20estado']=7;
	$bCerrando=true;
	}
if ($_REQUEST['paso']==21){
	$_REQUEST['paso']=12;
	$_REQUEST['saiu20estado']=8;
	$bCerrando=true;
	}
if ($_REQUEST['paso']==22){
	$_REQUEST['paso']=12;
	$_REQUEST['saiu20estado']=9;
	$bCerrando=true;
	}
//Abrir
if ($_REQUEST['paso']==17){
	$_REQUEST['paso']=2;
	//Es posible que deba definir el codigo de permiso para abrir.
	if (!seg_revisa_permiso($iCodModulo, 17, $objDB)){
		$sError=$ERR['3'];
		}
	//Otras restricciones para abrir.
	if ($sError==''){
		//$sError='Motivo por el que no se pueda abrir, no se permite modificar.';
		}
	if ($sError!=''){
		$_REQUEST['saiu20estado']=7;
		}else{
		$saiu20estado=2;
		if ($_REQUEST['saiu20idcaso']!=0){$saiu20estado=1;}
		$sSQL='UPDATE saiu20correo_'.$_REQUEST['saiu20agno'].' SET saiu20estado='.$saiu20estado.' WHERE saiu20id='.$_REQUEST['saiu20id'].'';
		$tabla=$objDB->ejecutasql($sSQL);
		seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 3, $_REQUEST['saiu20id'], 'Abre Registro de mensaje de correo', $objDB);
		$_REQUEST['saiu20estado']=$saiu20estado;
		$sError='<b>El registro ha sido abierto</b>';
		$iTipoError=1;
		}
	}
//Insertar o modificar un elemento
if (($_REQUEST['paso']==10)||($_REQUEST['paso']==12)){
	$bMueveScroll=true;
	list($_REQUEST, $sError, $iTipoError, $bCerrando, $sErrorCerrando, $sDebugGuardar)=f3020_db_GuardarV2($_REQUEST, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugGuardar;
	if ($sError==''){
		$sError='<b>'.$ETI['msg_itemguardado'].'</b>';
		$iTipoError=1;
		if ($sErrorCerrando!=''){
			$iTipoError=0;
			$sError='<b>'.$ETI['msg_itemguardado'].'</b><br>'.$sErrorCerrando;
			}
		if ($bCerrando){
			$sError='<b>'.$ETI['msg_itemcerrado'].'</b>';
			}
		} else {
			if ($_REQUEST['paso']==0) {
				if ($_REQUEST['saiu20estado']!=1) {
					$_REQUEST['saiu20estado']=-1;
				}
			}
		}
	}
if ($bCerrando){
	//acciones del cerrado
	}
// Cambio de consecutivo.
if ($_REQUEST['paso']==93){
	$_REQUEST['paso']=2;
	$_REQUEST['saiu20consec_nuevo']=numeros_validar($_REQUEST['saiu20consec_nuevo']);
	if ($_REQUEST['saiu20consec_nuevo']==''){$sError=$ERR['saiu20consec'];}
	if ($sError==''){
		if (!seg_revisa_permiso($iCodModulo, 8, $objDB)){
			$sError=$ERR['8'];
			}
		}
	if ($sError==''){
		//Ver que el consecutivo no exista.
		$sSQL='SELECT saiu20id FROM saiu20correo_'.$_REQUEST['saiu20agno'].' WHERE saiu20consec='.$_REQUEST['saiu20consec_nuevo'].' AND saiu20tiporadicado='.$_REQUEST['saiu20tiporadicado'].' AND saiu20mes='.$_REQUEST['saiu20mes'].' AND saiu20agno='.$_REQUEST['saiu20agno'].'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$sError='El consecutivo '.$_REQUEST['saiu20consec_nuevo'].' ya existe';
			}
		}
	if ($sError==''){
		//Aplicar el cambio.
		$sSQL='UPDATE saiu20correo_'.$_REQUEST['saiu20agno'].' SET saiu20consec='.$_REQUEST['saiu20consec_nuevo'].' WHERE saiu20id='.$_REQUEST['saiu20id'].'';
		$tabla=$objDB->ejecutasql($sSQL);
		$sDetalle='Cambia el consecutivo de '.$_REQUEST['saiu20consec'].' a '.$_REQUEST['saiu20consec_nuevo'].'';
		$_REQUEST['saiu20consec']=$_REQUEST['saiu20consec_nuevo'];
		$_REQUEST['saiu20consec_nuevo']='';
		seg_auditar($iCodModulo, $_SESSION['u_idtercero'], 8, $_REQUEST['saiu20id'], $sDetalle, $objDB);
		$sError='<b>Se ha aplicado el cambio de consecutivo.</b>';
		$iTipoError=1;
		}else{
		$iSector=93;
		}
	}
//Eliminar un elemento
if ($_REQUEST['paso']==13){
	$_REQUEST['paso']=2;
	list($sError, $iTipoError, $sDebugElimina)=f3020_db_Eliminar($_REQUEST['saiu20agno'], $_REQUEST['saiu20id'], $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugElimina;
	if ($sError==''){
		$_REQUEST['paso']=-1;
		$sError=$ETI['msg_itemeliminado'];
		$iTipoError=1;
		}
	}
// Reasignar responsable.
if ($_REQUEST['paso'] == 26) {
	$_REQUEST['paso'] = 2;
	$bMueveScroll = true;
	$bPermisoSupv = $idTercero == $_REQUEST['saiu20idsupervisorcaso'];
	if ($bPermisoSupv || $seg_1707) {
		$sTabla20 = 'saiu20correo_' . $_REQUEST['saiu20agno'];
		if (!$objDB->bexistetabla($sTabla20)) {
			$sError = 'No ha sido posible acceder al contenedor de datos ' . $sTabla20 . '';
		}
		if ($sError == '') {
			$bCambiaLider = false;
			$saiu20idunidadcaso = $_REQUEST['saiu20idunidadcaso'];
			$saiu20idequipocaso = $_REQUEST['saiu20idequipocaso'];
			$saiu20idsupervisorcaso = $_REQUEST['saiu20idsupervisorcaso'];
			$sSQL = 'SELECT bita27id, bita27idlider, bita27idunidadfunc FROM bita27equipotrabajo WHERE bita27idlider=' . $_REQUEST['saiu20idresponsablecasofin'] . ' AND bita27activo=1 ';
			$tabla = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla) > 0) {
				$fila = $objDB->sf($tabla);
				$sSQL = 'UPDATE ' . $sTabla20 . ' SET saiu20idunidadcaso=' . $fila['bita27idunidadfunc'] . ', saiu20idequipocaso=' . $fila['bita27id'] . ', 
saiu20idsupervisorcaso=' . $fila['bita27idlider'] . ', saiu20idresponsablecaso=' . $_REQUEST['saiu20idresponsablecasofin'] . ' WHERE saiu20id=' . $_REQUEST['saiu20id'] . '';
				$bCambiaLider = true;
				$saiu05idunidadresp = $fila['bita27idunidadfunc'];
				$saiu20idequipocaso = $fila['bita27id'];
				$saiu20idsupervisorcaso = $fila['bita27idlider'];
			} else {
				$sSQL = 'UPDATE ' . $sTabla20 . ' SET saiu20idresponsablecaso=' . $_REQUEST['saiu20idresponsablecasofin'] . ' WHERE saiu20id=' . $_REQUEST['saiu20id'] . '';
			}
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . ' Consulta reasignación: '.$sSQL.'<br>';
			}
			$result = $objDB->ejecutasql($sSQL);
			if ($result==false){
				$sError=$sError.$ERR['saiu20idresponsablecasofin'].'';
			} else {
				seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 3, $_REQUEST['saiu20id'], 'Reasigna el responsable ', $objDB);
				if ($bCambiaLider) {
					$_REQUEST['saiu20idunidadcaso']=$saiu20idunidadcaso;
					$_REQUEST['saiu20idequipocaso']=$saiu20idequipocaso;
					$_REQUEST['saiu20idsupervisorcaso']=$saiu20idsupervisorcaso;
				}
				$_REQUEST['saiu20idresponsablecaso']=$_REQUEST['saiu20idresponsablecasofin'];
				$sError = '<b>Se ha realizado la reasignaci&oacute;n.</b>';
				$iTipoError = 1;
				$_REQUEST['saiuid']=20;
				list($sMensaje, $sErrorE, $sDebugE) = f3000_EnviaCorreosAtencion($_REQUEST, $_REQUEST['saiu20agno'], $objDB, $bDebug, true);
				$sError = $sError . $sErrorE;
				$sDebug = $sDebug . $sDebugE;
			}
		}
	} else {
		$sError=$sError.$ERR['3'].'';
	}
}
// Actualiza atiende
if ($_REQUEST['paso'] == 27) {
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' Consultando informaci&oacute;n de responsables.<br>';
	}
	if ($_REQUEST['saiu20estado'] < 7){
		list($_REQUEST, $sErrorE, $iTipoError, $sDebugGuardar) = f3020_ActualizarAtiende($_REQUEST, $objDB, $bDebug, $idTercero);
		$sError = $sError . $sErrorE;
		$sDebug = $sDebug . $sDebugGuardar;
	} else {
		$sError = $sError . $ETI['saiu20cerrada'];
	}
}
//limpiar la pantalla
if ($_REQUEST['paso']==-1){
	$_REQUEST['saiu20agno']=fecha_agno();
	$_REQUEST['saiu20mes']=fecha_mes();
	//$_REQUEST['saiu20tiporadicado']='';
	$_REQUEST['saiu20consec']='';
	$_REQUEST['saiu20consec_nuevo']='';
	$_REQUEST['saiu20id']='';
	$_REQUEST['saiu20dia']=fecha_dia();
	$_REQUEST['saiu20hora']=fecha_hora();
	$_REQUEST['saiu20minuto']=fecha_minuto();
	$_REQUEST['saiu20estado']=-1;
	$_REQUEST['saiu20estadoorigen']=-1;
	if ($_REQUEST['saiu20idcorreo']==''){
		$_REQUEST['saiu20idcorreo']=$_REQUEST['vdidcorreo'];
		}
	$_REQUEST['saiu20idcorreootro']='';
	$_REQUEST['saiu20idsolicitante']=0;//$idTercero;
	$_REQUEST['saiu20idsolicitante_td']=$APP->tipo_doc;
	$_REQUEST['saiu20idsolicitante_doc']='';
	$_REQUEST['saiu20tipointeresado']=$_REQUEST['vdtipointeresado'];
	$_REQUEST['saiu20clasesolicitud']=0;
	$_REQUEST['saiu20tiposolicitud']=0;
	$_REQUEST['saiu20temasolicitud']=0;
	$_REQUEST['saiu20temasolicitudorigen']='';
	$_REQUEST['saiu20idzona']='';
	$_REQUEST['saiu20idcentro']='';
	$_REQUEST['saiu20codpais']=$_SESSION['unad_pais'];
	$_REQUEST['saiu20coddepto']='';
	$_REQUEST['saiu20codciudad']='';
	$_REQUEST['saiu20idescuela']=0;
	$_REQUEST['saiu20idprograma']=0;
	$_REQUEST['saiu20idperiodo']=0;
	$_REQUEST['saiu20idpqrs']=0;
	$_REQUEST['saiu20detalle']='';
	$_REQUEST['saiu20fechafin']='';
	$_REQUEST['saiu20horafin']='';
	$_REQUEST['saiu20minutofin']='';
	$_REQUEST['saiu20paramercadeo']=0;
	$_REQUEST['saiu20idresponsable']=$idTercero;
	$_REQUEST['saiu20idresponsable_td']=$APP->tipo_doc;
	$_REQUEST['saiu20idresponsable_doc']='';
	$_REQUEST['saiu20tiemprespdias']='';
	$_REQUEST['saiu20tiempresphoras']='';
	$_REQUEST['saiu20tiemprespminutos']='';
	$_REQUEST['saiu20solucion']=0;
	$_REQUEST['saiu20idcaso']=0;
	$_REQUEST['saiu20respuesta']='';
	$_REQUEST['saiu20correoorigen']='';
	$_REQUEST['saiu20idorigen']=0;
	$_REQUEST['saiu20idarchivo']=0;
	$_REQUEST['saiu20idorigenrta']=0;
	$_REQUEST['saiu20idarchivorta']=0;
	$_REQUEST['saiu20fecharespcaso']='';
	$_REQUEST['saiu20horarespcaso']=0;
	$_REQUEST['saiu20minrespcaso']=0;
	$_REQUEST['saiu20idunidadcaso']=0;
	$_REQUEST['saiu20idequipocaso']=0;
	$_REQUEST['saiu20idsupervisorcaso']=0;
	$_REQUEST['saiu20idresponsablecaso']=0;
	$_REQUEST['saiu20numref']='';
	$_REQUEST['saiu20evalacepta']=0;
	$_REQUEST['saiu20evalfecha']='';
	$_REQUEST['saiu20evalamabilidad']=0;
	$_REQUEST['saiu20evalamabmotivo']='';
	$_REQUEST['saiu20evalrapidez']=0;
	$_REQUEST['saiu20evalrapidmotivo']='';
	$_REQUEST['saiu20evalclaridad']=0;
	$_REQUEST['saiu20evalcalridmotivo']='';
	$_REQUEST['saiu20evalresolvio']=0;
	$_REQUEST['saiu20evalsugerencias']='';
	$_REQUEST['saiu20evalconocimiento']=0;
	$_REQUEST['saiu20evalconocmotivo']='';
	$_REQUEST['saiu20evalutilidad']=0;
	$_REQUEST['saiu20evalutilmotivo']='';
	$_REQUEST['saiu20idresponsablecaso_td']=$APP->tipo_doc;
	$_REQUEST['saiu20idresponsablecaso_doc']='';
	$_REQUEST['paso']=0;
	}
if ($bLimpiaHijos){
	}
//AQUI SE DEBEN CARGAR TODOS LOS DATOS QUE LA FORMA NECESITE.
$bPuedeAbrir = false;
$bPuedeEliminar = false;
$bPuedeGuardar = false;
$bPuedeCerrar = false;
$bHayImprimir = false;
$bEditable = $_REQUEST['saiu20estado'] == -1 || $_REQUEST['saiu20estado'] == 2;
$bPermisoSupv = $idTercero == $_REQUEST['saiu20idsupervisorcaso'];
$bPermisoResp = $idTercero == $_REQUEST['saiu20idresponsablecaso'];
$bPermisoAsignado = $bPermisoSupv || $bPermisoResp;
//DATOS PARA COMPLETAR EL FORMULARIO
$iAgno=fecha_agno();
$sNombreUsuario = '';
if ($seg_1707 == 1) {
	$sSQL = 'SELECT unad11razonsocial FROM unad11terceros WHERE unad11id=' . $idTercero . '';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$sNombreUsuario = cadena_notildes($fila['unad11razonsocial']);
	}
}
$sTabla='saiu20correo_'.$iAgno;
if (!$objDB->bexistetabla($sTabla)){
	list($sErrorT, $sDebugT)=f3000_TablasMes($iAgno, fecha_mes(), $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugT;
}
//Permisos adicionales
$seg_5=0;
$seg_6=0;
$seg_8=0;
$seg_12 = 0;
list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 12, $idTercero, $objDB);
if ($bDevuelve) {
	$seg_12 = 1;
}
//list($bDevuelve, $sDebugP)=seg_revisa_permisoV3($iCodModulo, 6, $idTercero, $objDB);
//if ($bDevuelve){$seg_6=1;}
//if ($seg_6==1){}
if ((int)$_REQUEST['paso'] == 0) {
	$bPuedeGuardar = true;
} else {
	switch ($_REQUEST['saiu20estado']) {
		case 1: // Caso asignado
			if ($bPermisoAsignado) {
				$bPuedeGuardar = true;
				$bPuedeCerrar = true;
			}
			break;
		case 2: // En tramite
			$bPuedeEliminar = true;
			$bPuedeGuardar = true;
			$bPuedeCerrar = true;
			break;
		case 7: // Radicada
			break;
		default:
			break;

	}
}
//Crear los controles que requieran llamado a base de datos
$objCombos=new clsHtmlCombos();
$objTercero=new clsHtmlTercero();
$iAgnoFin = fecha_agno();
list($saiu20estado_nombre, $sErrorDet)=tabla_campoxid('saiu11estadosol','saiu11nombre','saiu11id',$_REQUEST['saiu20estado'],'{'.$ETI['msg_sindato'].'}', $objDB);
$html_saiu20estado=html_oculto('saiu20estado', $_REQUEST['saiu20estado'], $saiu20estado_nombre);
$objCombos->nuevo('saiu20idcorreo', $_REQUEST['saiu20idcorreo'], true, '{'.$ETI['msg_seleccione'].'}');
// $sSQL='SELECT saiu57id AS id, saiu57titulo AS nombre FROM saiu57correos WHERE saiu57vigente=1 ORDER BY saiu57orden';
$objCombos->addArreglo($asaiu20idcorreo, $isaiu20idcorreo);
$objCombos->sAccion='muestra_saiu20idcorreootro();';
$html_saiu20idcorreo=$objCombos->html('', $objDB);
list($saiu20idsolicitante_rs, $_REQUEST['saiu20idsolicitante'], $_REQUEST['saiu20idsolicitante_td'], $_REQUEST['saiu20idsolicitante_doc'])=html_tercero($_REQUEST['saiu20idsolicitante_td'], $_REQUEST['saiu20idsolicitante_doc'], $_REQUEST['saiu20idsolicitante'], 0, $objDB);
list($saiu20idresponsable_rs, $_REQUEST['saiu20idresponsable'], $_REQUEST['saiu20idresponsable_td'], $_REQUEST['saiu20idresponsable_doc'])=html_tercero($_REQUEST['saiu20idresponsable_td'], $_REQUEST['saiu20idresponsable_doc'], $_REQUEST['saiu20idresponsable'], 0, $objDB);
$saiu20idunidadcaso_nombre = '&nbsp;';
if ($_REQUEST['saiu20idunidadcaso'] != '') {
	if ((int)$_REQUEST['saiu20idunidadcaso'] == 0) {
		$saiu20idunidadcaso_nombre = '{' . $ETI['msg_sindato'] . '}';
	} else {
		list($saiu20idunidadcaso_nombre, $sErrorDet) = tabla_campoxid('unae26unidadesfun', 'unae26nombre', 'unae26id', $_REQUEST['saiu20idunidadcaso'], '{' . $ETI['msg_sindato'] . '}', $objDB);
	}
}
$html_saiu20idunidadcaso = html_oculto('saiu20idunidadcaso', $_REQUEST['saiu20idunidadcaso'], $saiu20idunidadcaso_nombre);
$saiu20idequipocaso_nombre = '&nbsp;';
if ($_REQUEST['saiu20idequipocaso'] != '') {
	if ((int)$_REQUEST['saiu20idequipocaso'] == 0) {
		$saiu20idequipocaso_nombre = '{' . $ETI['msg_sindato'] . '}';
	} else {
		list($saiu20idequipocaso_nombre, $sErrorDet) = tabla_campoxid('bita27equipotrabajo', 'bita27nombre', 'bita27id', $_REQUEST['saiu20idequipocaso'], '{' . $ETI['msg_sindato'] . '}', $objDB);
	}
}
$html_saiu20idequipocaso = html_oculto('saiu20idequipocaso', $_REQUEST['saiu20idequipocaso'], $saiu20idequipocaso_nombre);
$saiu20idsupervisorcaso_rs='&nbsp;';
$sSQL = 'SELECT T11.unad11razonsocial FROM saiu03temasol AS TB, unad11terceros AS T11 WHERE TB.saiu03idliderrespon1=T11.unad11id AND TB.saiu03id = ' . $_REQUEST['saiu20temasolicitud'] . ' AND TB.saiu03idliderrespon1 = ' . $_REQUEST['saiu20idsupervisorcaso'] . '';
$tabla = $objDB->ejecutasql($sSQL);
if ($fila = $objDB->sf($tabla)) {
	$saiu20idsupervisorcaso_rs=$fila['unad11razonsocial'];
} else {
	$saiu20idsupervisorcaso_rs = '{' . $ETI['msg_sindato'] . '}';
}
list($saiu20idresponsablecaso_rs, $_REQUEST['saiu20idresponsablecaso'], $_REQUEST['saiu20idresponsablecaso_td'], $_REQUEST['saiu20idresponsablecaso_doc']) = html_tercero($_REQUEST['saiu20idresponsablecaso_td'], $_REQUEST['saiu20idresponsablecaso_doc'], $_REQUEST['saiu20idresponsablecaso'], 0, $objDB);
if ($saiu20idresponsablecaso_rs == '') {
	$saiu20idresponsablecaso_rs = '{' . $ETI['msg_sindato'] . '}';
}
$html_saiu20idresponsablecasocombo = '<b>' . $saiu20idresponsablecaso_rs . '</b>';
if ($_REQUEST['saiu20estado'] < 7) {
	if ($idTercero == $_REQUEST['saiu20idsupervisorcaso'] || $seg_1707) {
		$objCombos->nuevo('saiu20idresponsablecasofin', $_REQUEST['saiu20idresponsablecaso'], true, '{' . $ETI['msg_seleccione'] . '}');
		$sSQL = 'SELECT TB.bita28idtercero AS id, T2.unad11razonsocial AS nombre
			FROM bita28eqipoparte AS TB, unad11terceros AS T2 
			WHERE  TB.bita28idequipotrab=' . $_REQUEST['saiu20idequipocaso'] . ' AND TB.bita28idtercero=T2.unad11id AND TB.bita28activo="S"
			ORDER BY T2.unad11razonsocial';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' Lista de responsables: '. $sSQL.'<br>ID RESPONSABLE: ' . $_REQUEST['saiu20idresponsablecaso'] .'<br>';
		}
		$html_saiu20idresponsablecasocombo = $objCombos->html($sSQL, $objDB);
	}
}
if ($bEditable || $bPermisoSupv) {
	$objCombos->nuevo('saiu20tipointeresado', $_REQUEST['saiu20tipointeresado'], true, '{'.$ETI['msg_seleccione'].'}');
	$sSQL='SELECT bita07id AS id, bita07nombre AS nombre FROM bita07tiposolicitante ORDER BY bita07orden, bita07nombre';
	$html_saiu20tipointeresado=$objCombos->html($sSQL, $objDB);
	$html_saiu20tiposolicitud=f3020_HTMLComboV2_saiu20tiposolicitud($objDB, $objCombos, $_REQUEST['saiu20tiposolicitud']);
	$html_saiu20temasolicitud=f3020_HTMLComboV2_saiu20temasolicitud($objDB, $objCombos, $_REQUEST['saiu20temasolicitud'], $_REQUEST['saiu20tiposolicitud']);
	$objCombos->nuevo('saiu20idzona', $_REQUEST['saiu20idzona'], true, '{'.$ETI['msg_seleccione'].'}');
	$objCombos->sAccion='carga_combo_saiu20idcentro();';
	$sSQL='SELECT unad23id AS id, unad23nombre AS nombre FROM unad23zona WHERE unad23conestudiantes="S" ORDER BY unad23nombre';
	$html_saiu20idzona=$objCombos->html($sSQL, $objDB);
	$html_saiu20idcentro=f3020_HTMLComboV2_saiu20idcentro($objDB, $objCombos, $_REQUEST['saiu20idcentro'], $_REQUEST['saiu20idzona']);
	$objCombos->nuevo('saiu20codpais', $_REQUEST['saiu20codpais'], true, '{'.$ETI['msg_seleccione'].'}');
	$objCombos->sAccion='carga_combo_saiu20coddepto();';
	$sSQL='SELECT unad18codigo AS id, unad18nombre AS nombre FROM unad18pais ORDER BY unad18nombre';
	$html_saiu20codpais=$objCombos->html($sSQL, $objDB);
	$html_saiu20coddepto=f3020_HTMLComboV2_saiu20coddepto($objDB, $objCombos, $_REQUEST['saiu20coddepto'], $_REQUEST['saiu20codpais']);
	$html_saiu20codciudad=f3020_HTMLComboV2_saiu20codciudad($objDB, $objCombos, $_REQUEST['saiu20codciudad'], $_REQUEST['saiu20coddepto']);
	$objCombos->nuevo('saiu20idescuela', $_REQUEST['saiu20idescuela'], true, '{'.$ETI['msg_seleccione'].'}');
	$objCombos->sAccion='carga_combo_saiu20idprograma();';
	$objCombos->addItem('0', $ETI['msg_na']);
	$sSQL='SELECT core12id AS id, core12nombre AS nombre FROM core12escuela WHERE core12id>0 AND core12tieneestudiantes="S" ORDER BY core12tieneestudiantes DESC, core12nombre';
	$html_saiu20idescuela=$objCombos->html($sSQL, $objDB);
	$html_saiu20idprograma=f3020_HTMLComboV2_saiu20idprograma($objDB, $objCombos, $_REQUEST['saiu20idprograma'], $_REQUEST['saiu20idescuela']);
	$objCombos->nuevo('saiu20idperiodo', $_REQUEST['saiu20idperiodo'], true, '{'.$ETI['msg_seleccione'].'}');
	$objCombos->addItem('0', $ETI['msg_na']);
	$sSQL=f146_ConsultaCombo('exte02id>0');
	$html_saiu20idperiodo=$objCombos->html($sSQL, $objDB);
	$objCombos->nuevo('saiu20paramercadeo', $_REQUEST['saiu20paramercadeo'], true, $ETI['no'], 0);
	$objCombos->addItem(1, $ETI['si']);
	//$objCombos->addArreglo($asaiu20paramercadeo, $isaiu20paramercadeo);
	$html_saiu20paramercadeo=$objCombos->html('', $objDB);
	$objCombos->nuevo('saiu20solucion', $_REQUEST['saiu20solucion'], true, $asaiu20solucion[0], 0);
	//$objCombos->addItem(1, $ETI['si']);
	$objCombos->sAccion='valida_combo_saiu20solucion();';
	$objCombos->addArreglo($asaiu20solucion, $isaiu20solucion);
	$html_saiu20solucion=$objCombos->html('', $objDB);
} else {
	list($saiu20tipointeresado_nombre, $sErrorDet) = tabla_campoxid('bita07tiposolicitante', 'bita07nombre', 'bita07id', $_REQUEST['saiu20tipointeresado'], '{' . $ETI['msg_sindato'] . '}', $objDB);
	$html_saiu20tipointeresado = html_oculto('saiu20tipointeresado', $_REQUEST['saiu20tipointeresado'], $saiu20tipointeresado_nombre);
	list($saiu20tiposolicitud_nombre, $sErrorDet) = tabla_campoxid('saiu02tiposol', 'saiu02titulo', 'saiu02id', $_REQUEST['saiu20tiposolicitud'], '{' . $ETI['msg_sindato'] . '}', $objDB);
	$html_saiu20tiposolicitud = html_oculto('saiu20tiposolicitud', $_REQUEST['saiu20tiposolicitud'], $saiu20tiposolicitud_nombre);
	list($saiu20temasolicitud_nombre, $sErrorDet) = tabla_campoxid('saiu03temasol', 'saiu03titulo', 'saiu03id', $_REQUEST['saiu20temasolicitud'], '{' . $ETI['msg_sindato'] . '}', $objDB);
	$html_saiu20temasolicitud = html_oculto('saiu20temasolicitud', $_REQUEST['saiu20temasolicitud'], $saiu20temasolicitud_nombre);
	list($saiu20idzona_nombre, $sErrorDet) = tabla_campoxid('unad23zona', 'unad23nombre', 'unad23id', $_REQUEST['saiu20idzona'], '{' . $ETI['msg_sindato'] . '}', $objDB);
	$html_saiu20idzona = html_oculto('saiu20idzona', $_REQUEST['saiu20idzona'], $saiu20idzona_nombre);
	list($saiu20idcentro_nombre, $sErrorDet) = tabla_campoxid('unad24sede', 'unad24nombre', 'unad24id', $_REQUEST['saiu20idcentro'], '{' . $ETI['msg_sindato'] . '}', $objDB);
	$html_saiu20idcentro = html_oculto('saiu20idcentro', $_REQUEST['saiu20idcentro'], $saiu20idcentro_nombre);
	list($saiu20codpais_nombre, $sErrorDet) = tabla_campoxid('unad18pais', 'unad18nombre', 'unad18codigo', $_REQUEST['saiu20codpais'], '{' . $ETI['msg_sindato'] . '}', $objDB);
	$html_saiu20codpais = html_oculto('saiu20codpais', $_REQUEST['saiu20codpais'], $saiu20codpais_nombre);
	list($saiu20coddepto_nombre, $sErrorDet) = tabla_campoxid('unad19depto', 'unad19nombre', 'unad19codigo', $_REQUEST['saiu20coddepto'], '{' . $ETI['msg_sindato'] . '}', $objDB);
	$html_saiu20coddepto = html_oculto('saiu20coddepto', $_REQUEST['saiu20coddepto'], $saiu20coddepto_nombre);
	list($saiu20codciudad_nombre, $sErrorDet) = tabla_campoxid('unad20ciudad', 'unad20nombre', 'unad20codigo', $_REQUEST['saiu20codciudad'], '{' . $ETI['msg_sindato'] . '}', $objDB);
	$html_saiu20codciudad = html_oculto('saiu20codciudad', $_REQUEST['saiu20codciudad'], $saiu20codciudad_nombre);
	list($saiu20idescuela_nombre, $sErrorDet) = tabla_campoxid('core12escuela', 'core12nombre', 'core12id', $_REQUEST['saiu20idescuela'], '{' . $ETI['msg_sindato'] . '}', $objDB);
	$html_saiu20idescuela = html_oculto('saiu20idescuela', $_REQUEST['saiu20idescuela'], $saiu20idescuela_nombre);
	list($saiu20idprograma_nombre, $sErrorDet) = tabla_campoxid('core09programa', 'core09nombre', 'core09id', $_REQUEST['saiu20idprograma'], '{' . $ETI['msg_sindato'] . '}', $objDB);
	$html_saiu20idprograma = html_oculto('saiu20idprograma', $_REQUEST['saiu20idprograma'], $saiu20idprograma_nombre);
	list($saiu20idperiodo_nombre, $sErrorDet) = tabla_campoxid('exte02per_aca', 'exte02nombre', 'exte02id', $_REQUEST['saiu20idperiodo'], '{' . $ETI['msg_sindato'] . '}', $objDB);
	$html_saiu20idperiodo = html_oculto('saiu20idperiodo', $_REQUEST['saiu20idperiodo'], $saiu20idperiodo_nombre);
	$html_saiu20solucion = html_oculto('saiu20solucion', $_REQUEST['saiu20solucion'], $asaiu20solucion[$_REQUEST['saiu20solucion']]);
}
if ((int)$_REQUEST['paso']==0){
	$html_saiu20agno=f3020_HTMLComboV2_saiu20agno($objDB, $objCombos, $_REQUEST['saiu20agno']);
	$html_saiu20mes=f3020_HTMLComboV2_saiu20mes($objDB, $objCombos, $_REQUEST['saiu20mes']);
	$html_saiu20dia=html_ComboDia('saiu20dia', $_REQUEST['saiu20dia'], false);
	$html_saiu20tiporadicado=f3020_HTMLComboV2_saiu20tiporadicado($objDB, $objCombos, $_REQUEST['saiu20tiporadicado']);
} else {
	$saiu20agno_nombre=$_REQUEST['saiu20agno'];
	//$saiu20agno_nombre=$asaiu20agno[$_REQUEST['saiu20agno']];
	//list($saiu20agno_nombre, $sErrorDet)=tabla_campoxid('','','',$_REQUEST['saiu20agno'],'{'.$ETI['msg_sindato'].'}', $objDB);
	$html_saiu20agno=html_oculto('saiu20agno', $_REQUEST['saiu20agno'], $saiu20agno_nombre);
	$saiu20mes_nombre=strtoupper(fecha_mes_nombre((int)$_REQUEST['saiu20mes']));
	//$saiu20mes_nombre=$asaiu20mes[$_REQUEST['saiu20mes']];
	//list($saiu20mes_nombre, $sErrorDet)=tabla_campoxid('','','',$_REQUEST['saiu20mes'],'{'.$ETI['msg_sindato'].'}', $objDB);
	$html_saiu20mes=html_oculto('saiu20mes', $_REQUEST['saiu20mes'], $saiu20mes_nombre);
	$saiu20dia_nombre=$_REQUEST['saiu20dia'];
	$html_saiu20dia=html_oculto('saiu20dia', $_REQUEST['saiu20dia'], $saiu20dia_nombre);
	list($saiu20tiporadicado_nombre, $sErrorDet)=tabla_campoxid('saiu16tiporadicado','saiu16nombre','saiu16id',$_REQUEST['saiu20tiporadicado'],'{'.$ETI['msg_sindato'].'}', $objDB);
	$html_saiu20tiporadicado=html_oculto('saiu20tiporadicado', $_REQUEST['saiu20tiporadicado'], $saiu20tiporadicado_nombre);
}
$bEnProceso=true;
if ($_REQUEST['paso']!=0){
	if ($_REQUEST['saiu20estado']>6){$bEnProceso=false;}
	}
if (true){
	}else{
	list($saiu20tiposolicitud_nombre, $sErrorDet)=tabla_campoxid('saiu02tiposol','saiu02titulo','saiu02id',$_REQUEST['saiu20tiposolicitud'],'{'.$ETI['msg_sindato'].'}', $objDB);
	$html_saiu20tiposolicitud=html_oculto('saiu20tiposolicitud', $_REQUEST['saiu20tiposolicitud'], $saiu20tiposolicitud_nombre);
	}
//Alistar datos adicionales
$bPuedeAbrir=false;
if ($_REQUEST['paso']!=0){
	if ($_REQUEST['saiu20estado']>6){
		//Definir las condiciones que permitirán abrir el registro.
		list($bDevuelve, $sDebugP)=seg_revisa_permisoV3($iCodModulo, 17, $idTercero, $objDB);
		if ($bDevuelve){$bPuedeAbrir=true;}
		}else{
		}
	}
$id_rpt=0;
//$id_rpt=reportes_id(_Identificador_Tipo_Reporte_, $objDB);
$objCombos->nuevo('bagno', $_REQUEST['bagno'], false, '{'.$ETI['msg_todos'].'}');
$objCombos->sAccion='paginarf3020()';
$sSQL='SHOW TABLES LIKE "saiu20correo%"';
$tablac=$objDB->ejecutasql($sSQL);
while($filac=$objDB->sf($tablac)){
	$sAgno=substr($filac[0], 13);
	$objCombos->addItem($sAgno, $sAgno);
}
$html_bagno=$objCombos->html('', $objDB);
$objCombos->nuevo('bestado', $_REQUEST['bestado'], true, '{' . $ETI['msg_todos'] . '}');
$objCombos->addItem('-1', 'Borrador');
$objCombos->addItem('0', 'Solicitado');
$objCombos->addItem('1', 'Asignado');
$objCombos->addItem('2', 'En tr&aacute;mite');
$objCombos->addItem('7', 'Resuelto');
$objCombos->sAccion = 'paginarf3020()';
$html_bestado = $objCombos->html('', $objDB);
$bTodos = false;
if ($seg_12 == 1) {
	$bTodos = true;
}
$objCombos->nuevo('blistar', $_REQUEST['blistar'], $bTodos, '{'.$ETI['msg_todos'].'}');
$objCombos->addItem('1', 'Mis registros');
$objCombos->addItem('2', 'Mis asignaciones');
$objCombos->addItem('3', 'Asignado a mi equipo');
$objCombos->sAccion='paginarf3020()';
$html_blistar=$objCombos->html('', $objDB);
$objCombos->nuevo('bcategoria', $_REQUEST['bcategoria'], true, '{' . $ETI['msg_todos'] . '}');
$objCombos->sAccion = 'carga_combo_btema()';
$sSQL = 'SELECT saiu02id AS id, saiu02titulo AS nombre FROM saiu02tiposol WHERE saiu02id>0 ORDER BY saiu02titulo';
$html_bcategoria = $objCombos->html($sSQL, $objDB);
$objCombos->nuevo('btema', $_REQUEST['btema'], true, '{' . $ETI['msg_todos'] . '}');
$html_btema = $objCombos->html('', $objDB);
$objCombos->nuevo('bagnopqrs', $_REQUEST['bagnopqrs'], false, '{' . $ETI['msg_todos'] . '}');
$objCombos->sAccion = 'paginarf3000pqrs()';
$objCombos->numeros(2020, $iAgnoFin, 1);
$html_bagnopqrs = $objCombos->html('', $objDB);
//$html_blistar=$objCombos->comboSistema(3020, 1, $objDB, 'paginarf3020()');
if (false){
	$objCombos->nuevo('csv_separa', $_REQUEST['csv_separa'], false);
	$objCombos->addItem(',', $ETI['msg_coma']);
	$objCombos->addItem(';', $ETI['msg_puntoycoma']);
	$csv_separa='<label class="Label90">'.$ETI['msg_separador'].'</label><label class="Label130">'.$objCombos->html('', $objDB).'</label>';
	}else{
	$csv_separa='<input id="csv_separa" name="csv_separa" type="hidden" value="," />';
	}
$iNumFormatosImprime=0;
$iModeloReporte=3020;
$html_iFormatoImprime='<input id="iformatoimprime" name="iformatoimprime" type="hidden" value="0" />';
// TODO
$objCombos->nuevo('saiucanal', $_REQUEST['saiucanal'], false, '{'.$ETI['msg_todas'].'}');
$objCombos->sAccion='cambiacanal()';
$objCombos->addArreglo($aCanal, $iCanal);
$html_saiucanal=$objCombos->html('', $objDB);
// TODO
if ($_REQUEST['paso']>0){
	$bDevuelve=false;
	//list($bDevuelve, $sDebugP)=seg_revisa_permisoV3($iCodModulo, 5, $idTercero, $objDB);
	if ($bDevuelve){
		$seg_5=1;
		}
	if ($_REQUEST['saiu20estado']>6){
		list($bDevuelve, $sDebugP)=seg_revisa_permisoV3($iCodModulo, 8, $idTercero, $objDB);
		if ($bDevuelve){$seg_8=1;}
		}
	}
//Cargar las tablas de datos
list($sErrorR, $sDebugR) = f3020_RevTabla_saiu20correo($_REQUEST['saiu20agno'], $objDB, $bDebug);
$sError = $sError . $sErrorR;
$sDebug = $sDebug . $sDebugR;
$aParametros[0]='';//$_REQUEST['p1_3020'];
$aParametros[100]=$idTercero;
$aParametros[101]=$_REQUEST['paginaf3020'];
$aParametros[102]=$_REQUEST['lppf3020'];
$aParametros[103]=$_REQUEST['bnombre'];
$aParametros[104]=$_REQUEST['bagno'];
$aParametros[105] = $_REQUEST['bestado'];
$aParametros[106] = $_REQUEST['blistar'];
$aParametros[107] = $_REQUEST['bdoc'];
$aParametros[108] = $_REQUEST['bcategoria'];
$aParametros[109] = $_REQUEST['btema'];
list($sTabla3020, $sDebugTabla)=f3020_TablaDetalleV2($aParametros, $objDB, $bDebug);
$sDebug=$sDebug.$sDebugTabla;
$sTabla3000='';
$aParametros3000[0]=$idTercero;
$aParametros3000[1]=$iCodModulo;
$aParametros3000[2]=$_REQUEST['saiu20agno'];
$aParametros3000[3]=$_REQUEST['saiu20id'];
$aParametros3000[100]=$_REQUEST['saiu20idsolicitante'];
$aParametros3000[101]=$_REQUEST['paginaf3000'];
$aParametros3000[102]=$_REQUEST['lppf3000'];
//$aParametros3000[103]=$_REQUEST['bnombre3000'];
//$aParametros3000[104]=$_REQUEST['blistar3000'];
list($sTabla3000, $sDebugTabla)=f3000_TablaDetalleV2($aParametros3000, $objDB, $bDebug);
$sDebug=$sDebug.$sDebugTabla;
$sTabla3000pqrs='';
$aParametros3000[101]=$_REQUEST['paginaf3000pqrs'];
$aParametros3000[102]=$_REQUEST['lppf3000pqrs'];
$aParametros3000[103]=$_REQUEST['bagnopqrs'];
//$aParametros3000[104]=$_REQUEST['blistar3000'];
list($sTabla3000pqrs, $sDebugTabla)=f3000pqrs_TablaDetalleV2($aParametros3000, $objDB, $bDebug);
$sDebug=$sDebug.$sDebugTabla;
$bDebugMenu=false;
switch ($iPiel) {
	case 2:
		list($et_menu, $sDebugM) = html_Menu2023($APP->idsistema, $objDB, $iPiel, $bDebugMenu, $idTercero);
		break;
	default:
		list($et_menu, $sDebugM) = html_menuV2($APP->idsistema, $objDB, $iPiel, $bDebugMenu, $idTercero);
		break;
}
$sDebug=$sDebug.$sDebugM;
$objDB->CerrarConexion();
//FORMA
switch ($iPiel) {
	case 2:
		require $APP->rutacomun . 'unad_forma2023.php';
		forma_InicioV4($xajax, $ETI['titulo_3020']);
		break;
	default:
		require $APP->rutacomun . 'unad_forma_v2.php';
		forma_cabeceraV3($xajax, $ETI['titulo_3020']);
		echo $et_menu;
		forma_mitad();
		break;
}
?>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>js/jquery-3.3.1.min.js"></script>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>js/popper.min.js"></script>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>js/bootstrap.min.js"></script>
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>js/bootstrap.min.css" type="text/css"/>
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>css/criticalPath.css" type="text/css"/>
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>css/principal.css" type="text/css"/>
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>unad_estilos2018.css" type="text/css"/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.0.1/css/font-awesome.min.css">
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>css/css_tabs.css" type="text/css" />
<?php
switch ($iPiel) {
	case 2:
		$aRutas = array(
			array('', 'SAI'), 
			array('./saiucorreo.php', 'Registro de Atenciones'), 
			array('', $ETI['titulo_3020'])
		);
		$iNumBoton = 0;
		$aBotones[$iNumBoton] = array('muestraayuda(' . $APP->idsistema . ', ' . $iCodModulo . ')', $ETI['bt_ayuda'], 'iHelp');
		$iNumBoton++;
		if ($bPuedeEliminar) {
			$aBotones[$iNumBoton] = array('eliminadato()', $ETI['bt_eliminar'], 'iDelete');
			$iNumBoton++;
		}
		if ($bHayImprimir) {
			$aBotones[$iNumBoton] = array($sScriptImprime, $ETI['bt_imprimir'], 'iPrint');
			$iNumBoton++;
		}
		$aBotones[$iNumBoton] = array('limpiapagina()', $ETI['bt_limpiar'], 'iDocument');
		$iNumBoton++;
		if ($bPuedeGuardar) {
			$aBotones[$iNumBoton] = array('enviaguardar()', $ETI['bt_guardar'], 'iSaveFill');
			$iNumBoton++;
		}
		if ($bPuedeCerrar) {
			$aBotones[$iNumBoton] = array('enviacerrar()', $ETI['bt_cerrar'], 'iTask');
			$iNumBoton++;
		}
		forma_cabeceraV4($aRutas, $aBotones, true, $bDebug);
		echo $et_menu;
		forma_mitad($idTercero);
		break;
	default:
		break;
}
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
	let sEst = 'none';
	if (codigo == 1) {
		sEst = 'block';
	}
<?php
switch($iPiel) {
	case 2:
?>
	document.getElementById('nav').style.display = sEst;
	document.getElementById('botones_sup').style.display = sEst;
<?php
		break;
	default:
		if ($bPuedeGuardar) {
?>
	if (window.document.frmedita.saiu20estado.value<7){
		document.getElementById('cmdGuardarf').style.display = sEst;
	}
<?php
		}
		break;
}
?>
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
	let saiu20idsolicitante=0;
	let params=new Array();
	params[1]=document.getElementById(idcampo+'_doc').value;
	if (params[1]!=''){
		params[0]=document.getElementById(idcampo+'_td').value;
		params[2]=idcampo;
		params[3]='div_'+idcampo;
		if (illave==1){params[4]='RevisaLlave';}
		//if (illave==1){params[5]='FuncionCuandoNoEsta';}
		if (idcampo=='saiu20idsolicitante'){
			params[6]=3020;
			xajax_unad11_Mostrar_v2SAI(params);
			}else{
			xajax_unad11_Mostrar_v2(params);
			}
		}else{
		document.getElementById(idcampo).value=0;
		document.getElementById('div_'+idcampo).innerHTML='&nbsp;';
		paginarf3000();
		paginarf3000pqrs();
	}
	saiu20idsolicitante = document.getElementById('saiu20idsolicitante').value;
	if (saiu20idsolicitante == 0) {
		document.getElementById('div_saiu20regsolicitante').style.display='none';
	} else {
		document.getElementById('div_saiu20regsolicitante').style.display='block';
	}
}
function ter_traerxid(idcampo, vrcampo){
	var params=new Array();
	params[0]=vrcampo;
	params[1]=idcampo;
	if (params[0]!=0){
		params[6]=3020;
		xajax_unad11_TraerXidSAI(params);
		}
	}
function imprimelista(){
	if (window.document.frmedita.seg_6.value==1){
		window.document.frmlista.consulta.value=window.document.frmedita.consulta_3020.value;
		window.document.frmlista.titulos.value=window.document.frmedita.titulos_3020.value;
		window.document.frmlista.nombrearchivo.value='Registro de correos';
		window.document.frmlista.submit();
		}else{
		window.alert("<?php echo $ERR['6']; ?>");
		}
	}
function asignarvariables(){
	window.document.frmimpp.v0.value = <?php echo $idTercero; ?>;
	window.document.frmimpp.v3.value = window.document.frmedita.bagno.value;
	window.document.frmimpp.v4.value = window.document.frmedita.bestado.value;
	window.document.frmimpp.v5.value = window.document.frmedita.blistar.value;
	window.document.frmimpp.separa.value = window.document.frmedita.csv_separa.value.trim();
}
function imprimeexcel(){
	var sError='';
	if (window.document.frmedita.seg_6.value!=1){sError="<?php echo $ERR['6']; ?>";}
	//if (sError==''){/*Agregar validaciones*/}
	if (sError==''){
		asignarvariables();
		window.document.frmimpp.action='e3020.php';
		window.document.frmimpp.submit();
		}else{
		ModalMensaje(sError);
		}
	}
function imprimep(){
	if (window.document.frmedita.seg_5.value==1){
		asignarvariables();
		window.document.frmimpp.action='p3020.php';
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
	ModalConfirm('&iquest;<?php echo $ETI['confirma_eliminar']; ?>?');
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
	datos[1]=window.document.frmedita.saiu20agno.value;
	datos[2]=window.document.frmedita.saiu20mes.value;
	datos[3]=window.document.frmedita.saiu20tiporadicado.value;
	datos[4]=window.document.frmedita.saiu20consec.value;
	if ((datos[1]!='')&&(datos[2]!='')&&(datos[3]!='')&&(datos[4]!='')){
		xajax_f3020_ExisteDato(datos);
		}
	}
function cargadato(llave1, llave2, llave3, llave4){
	window.document.frmedita.saiu20agno.value=String(llave1);
	window.document.frmedita.saiu20mes.value=String(llave2);
	window.document.frmedita.saiu20tiporadicado.value=String(llave3);
	window.document.frmedita.saiu20consec.value=String(llave4);
	window.document.frmedita.paso.value=1;
	window.document.frmedita.submit();
	}
function cargaridf3020(llave1, llave2){
	window.document.frmedita.saiu20agno.value=String(llave1);
	window.document.frmedita.saiu20id.value=String(llave2);
	window.document.frmedita.paso.value=3;
	window.document.frmedita.submit();
	}
function carga_combo_saiu20tiposolicitud(){
	var params=new Array();
	params[0]=window.document.frmedita.saiu20temasolicitud.value;
	document.getElementById('div_saiu20tiposolicitud').innerHTML='<b>Procesando datos, por favor espere...</b><input id="saiu20tiposolicitud" name="saiu20tiposolicitud" type="hidden" value="" />';
	document.getElementById('div_saiu20temasolicitud').innerHTML='<b>Procesando datos, por favor espere...</b><input id="saiu20temasolicitud" name="saiu20temasolicitud" type="hidden" value="" />';
	xajax_f3020_Combosaiu20tiposolicitud(params);
	}
function carga_combo_saiu20temasolicitud(){
	var params=new Array();
	params[0]=window.document.frmedita.saiu20tiposolicitud.value;
	document.getElementById('div_saiu20temasolicitud').innerHTML='<b>Procesando datos, por favor espere...</b><input id="saiu20temasolicitud" name="saiu20temasolicitud" type="hidden" value="" />';
	xajax_f3020_Combosaiu20temasolicitud(params);
	}
function carga_combo_saiu20idcentro(){
	var params=new Array();
	params[0]=window.document.frmedita.saiu20idzona.value;
	document.getElementById('div_saiu20idcentro').innerHTML='<b>Procesando datos, por favor espere...</b><input id="saiu20idcentro" name="saiu20idcentro" type="hidden" value="" />';
	xajax_f3020_Combosaiu20idcentro(params);
	}
function carga_combo_saiu20coddepto(){
	var params=new Array();
	params[0]=window.document.frmedita.saiu20codpais.value;
	document.getElementById('div_saiu20coddepto').innerHTML='<b>Procesando datos, por favor espere...</b><input id="saiu20coddepto" name="saiu20coddepto" type="hidden" value="" />';
	xajax_f3020_Combosaiu20coddepto(params);
	}
function carga_combo_saiu20codciudad(){
	var params=new Array();
	params[0]=window.document.frmedita.saiu20coddepto.value;
	document.getElementById('div_saiu20codciudad').innerHTML='<b>Procesando datos, por favor espere...</b><input id="saiu20codciudad" name="saiu20codciudad" type="hidden" value="" />';
	xajax_f3020_Combosaiu20codciudad(params);
	}
function carga_combo_saiu20idprograma(){
	var params=new Array();
	params[0]=window.document.frmedita.saiu20idescuela.value;
	document.getElementById('div_saiu20idprograma').innerHTML='<b>Procesando datos, por favor espere...</b><input id="saiu20idprograma" name="saiu20idprograma" type="hidden" value="" />';
	xajax_f3020_Combosaiu20idprograma(params);
	}
function paginarf3020(){
	var params=new Array();
	params[99]=window.document.frmedita.debug.value;
	params[100]=<?php echo $idTercero; ?>;
	params[101]=window.document.frmedita.paginaf3020.value;
	params[102]=window.document.frmedita.lppf3020.value;
	params[103]=window.document.frmedita.bnombre.value;
	params[104] = window.document.frmedita.bagno.value;
	params[105] = window.document.frmedita.bestado.value;
	params[106] = window.document.frmedita.blistar.value;
	params[107] = window.document.frmedita.bdoc.value;
	params[108] = window.document.frmedita.bcategoria.value;
	params[109] = window.document.frmedita.btema.value;
	document.getElementById('div_f3020detalle').innerHTML='<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf3020" name="paginaf3020" type="hidden" value="'+params[101]+'" /><input id="lppf3020" name="lppf3020" type="hidden" value="'+params[102]+'" />';
	xajax_f3020_HtmlTabla(params);
	}
function enviacerrar(){
	if (confirm('Esta seguro de cerrar el registro?\nluego de cerrado no se permite modificar')){
		expandesector(98);
		window.document.frmedita.paso.value=16;
		window.document.frmedita.submit();
		}
	}
function enviaabrir(){
	if (confirm('Esta seguro de abrir el registro?\nesto le permite volver a modificar')){
		expandesector(98);
		window.document.frmedita.paso.value=17;
		window.document.frmedita.submit();
		}
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
	document.getElementById("saiu20agno").focus();
	}
function buscarV2016(sCampo){
	window.document.frmedita.iscroll.value=window.pageYOffset;
	expandesector(98);
	window.document.frmedita.scampobusca.value=sCampo;
	var params=new Array();
	params[1]=sCampo;
	//params[2]=window.document.frmedita.iagno.value;
	//params[3]=window.document.frmedita.itipo.value;
	xajax_f3020_Busquedas(params);
	}
function retornacontrol(){
	expandesector(1);
	window.scrollTo(0, window.document.frmedita.iscroll.value);
	}
function Devuelve(sValor){
	var sCampo=window.document.frmedita.scampobusca.value;
	if (sCampo=='saiu20idsolicitante'){
		ter_traerxid('saiu20idsolicitante', sValor);
		}
	if (sCampo=='saiu20idresponsable'){
		ter_traerxid('saiu20idresponsable', sValor);
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
	if (ref == '3020') {
		if (sRetorna != '') {
			window.document.frmedita.saiu20idorigen.value = window.document.frmedita.div96v1.value;
			window.document.frmedita.saiu20idarchivo.value = sRetorna;
			verboton('beliminasaiu20idarchivo', 'block');
		}
		archivo_lnk(window.document.frmedita.saiu20idorigen.value, window.document.frmedita.saiu20idarchivo.value, 'div_saiu20idarchivo');
	}
	if (ref == '3020rta') {
		if (sRetorna != '') {
			window.document.frmedita.saiu20idorigenrta.value = window.document.frmedita.div96v1.value;
			window.document.frmedita.saiu20idarchivorta.value = sRetorna;
			verboton('beliminasaiu20idarchivorta', 'block');
		}
		archivo_lnk(window.document.frmedita.saiu20idorigenrta.value, window.document.frmedita.saiu20idarchivorta.value, 'div_saiu20idarchivorta');
	}
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
function paginarf3000(){
	var params=new Array();
	params[0]=window.document.frmedita.id11.value;
	params[1]=3020;
	params[2]=window.document.frmedita.saiu20agno.value;
	params[3]=window.document.frmedita.saiu20id.value;
	params[99]=window.document.frmedita.debug.value;
	params[100]=window.document.frmedita.saiu20idsolicitante.value;
	params[101]=window.document.frmedita.paginaf3000.value;
	params[102]=window.document.frmedita.lppf3000.value;
	//params[103]=window.document.frmedita.bnombre3000.value;
	//params[104]=window.document.frmedita.blistar3000.value;
	document.getElementById('div_f3000detalle').innerHTML='<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf3000" name="paginaf3000" type="hidden" value="'+params[101]+'" /><input id="lppf3000" name="lppf3000" type="hidden" value="'+params[102]+'" />';
	xajax_f3000_HtmlTabla(params);
	}
function paginarf3000pqrs(){
	var params=new Array();
	params[0]=window.document.frmedita.id11.value;
	params[1]=3020;
	params[2]=window.document.frmedita.saiu20agno.value;
	params[3]=window.document.frmedita.saiu20id.value;
	params[99]=window.document.frmedita.debug.value;
	params[100]=window.document.frmedita.saiu20idsolicitante.value;
	params[101]=window.document.frmedita.paginaf3000pqrs.value;
	params[102]=window.document.frmedita.lppf3000pqrs.value;
	params[103]=window.document.frmedita.bagnopqrs.value;
	//params[104]=window.document.frmedita.blistar3000.value;
	document.getElementById('div_f3000pqrsdetalle').innerHTML='<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf3000pqrs" name="paginaf3000pqrs" type="hidden" value="'+params[101]+'" /><input id="lppf3000pqrs" name="lppf3000pqrs" type="hidden" value="'+params[102]+'" />';
	xajax_f3000pqrs_HtmlTabla(params);
	}
// TODO
function cambiacanal(){
	let iCanal = parseInt(document.getElementById('saiucanal').value);
	let sCanal = 'saiucorreo';
	switch(iCanal) {
		case 1: sCanal='saiupresencial';
		break;
		case 2: sCanal='saiutelefonico';
		break;
		case 3: sCanal='saiuchat';
		break;
		case 4: sCanal='saiucorreo';
		break;
	}
	location.href = './' + sCanal + '.php';
}
// TODO
function valida_combo_saiu20solucion() {
	let iSolucion = parseInt(document.getElementById('saiu20solucion').value);
	let iEstado = parseInt(document.getElementById('saiu20estado').value);
	switch(iSolucion) {
		case 1:
		document.getElementById('div_saiu20respuesta').style.display='block';
		break;
		case 0:
		case 5:
		case 3:
		document.getElementById('div_saiu20respuesta').style.display='none';
		if (iEstado==1) {
			document.getElementById('div_saiu20respuesta').style.display='block';
		}
		break;		
		default:
		document.getElementById('div_saiu20respuesta').style.display='none';
		break;
	}
}
function actualizaratiende() {
	var sError = '';
	if (window.document.frmedita.saiu20id.value == '') {
		sError = 'Por favor seleccione una solicitud.';
	}
	if (sError == '') {
		window.document.frmedita.iscroll.value = window.pageYOffset;
		MensajeAlarmaV2('<?php echo $ETI['msg_ejecutando']; ?>...', 2);
		expandesector(98);
		window.document.frmedita.paso.value = 27;
		window.document.frmedita.submit();
	}
}
<?php
if ($_REQUEST['saiu20estado'] < 7) {
	if ($bPermisoSupv || $seg_1707) {
?>
	function enviareasignar() {
		window.document.frmedita.iscroll.value = window.pageYOffset;
		ModalConfirmV2('<?php echo $ETI['pregunta_reasigna']; ?>', () => {
			ejecuta_enviareasignar();
		});
	}

	function ejecuta_enviareasignar() {
		MensajeAlarmaV2('<?php echo $ETI['msg_ejecutando']; ?>', 2);
		expandesector(98);
		window.document.frmedita.paso.value = 26;
		window.document.frmedita.submit();
	}
<?php
	}
}
?>
function abrir_tab(evt, sId) {
	evt.preventDefault();
	var i, tabcontent, tablinks;
	tabcontent = document.getElementsByClassName("tabcontent");
	for (i = 0; i < tabcontent.length; i++) {
		tabcontent[i].style.display = "none";
	}
	tablinks = document.getElementsByClassName("tablinks");
	for (i = 0; i < tablinks.length; i++) {
		tablinks[i].className = tablinks[i].className.replace(" active", "");
	}
	document.getElementById(sId).style.display = "flex";
	document.getElementById(sId).style.flexWrap = "wrap";
	evt.currentTarget.className += " active";
}
<?php
// if ($_REQUEST['saiu20estado']==-1) {
if (true) {
?>
	function limpia_saiu20idarchivo(){
		window.document.frmedita.saiu20idorigen.value=0;
		window.document.frmedita.saiu20idarchivo.value=0;
		let da_Archivo=document.getElementById('div_saiu20idarchivo');
		da_Archivo.innerHTML='&nbsp;';
		verboton('beliminasaiu20idarchivo','none');
		//paginarf0000();
	}
	function carga_saiu20idarchivo(){
		window.document.frmedita.iscroll.value=window.pageYOffset;
		window.document.frmedita.div96v1.value='';
		window.document.frmedita.div96v2.value='';
		window.document.frmedita.div96v3.value='';
		let saiu20id=window.document.frmedita.saiu20id.value;
		let agno=window.document.frmedita.saiu20agno.value;
		document.getElementById('div_96titulo').innerHTML='<h2>'+window.document.frmedita.titulo_3020.value+' - Cargar archivo detalle</h2>';
		document.getElementById('div_96cuerpo').innerHTML='<iframe id="iframe96" src="framearchivodis.php?ref=3020&id='+saiu20id+'&tabla=_'+agno+'&tipo=0" height="400px" width="100%" frameborder="0"></iframe>';
		expandesector(96);
		window.scrollTo(0, 150);
	}
	function eliminasaiu20idarchivo(){
		let did=window.document.frmedita.saiu20id;
		let agno=window.document.frmedita.saiu20agno.value;
		if (confirm("Esta seguro de eliminar el archivo?")){
			xajax_elimina_archivo_saiu20idarchivo(did.value, agno);
			//paginarf0000();
		}
	}
<?php
}
?>
<?php
// if ($_REQUEST['saiu20estado']==-1) {
if (true) {
?>
	function limpia_saiu20idarchivorta(){
		window.document.frmedita.saiu20idorigenrta.value=0;
		window.document.frmedita.saiu20idarchivorta.value=0;
		let da_Archivo=document.getElementById('div_saiu20idarchivorta');
		da_Archivo.innerHTML='&nbsp;';
		verboton('beliminasaiu20idarchivorta','none');
		//paginarf0000();
	}
	function carga_saiu20idarchivorta(){
		window.document.frmedita.iscroll.value=window.pageYOffset;
		window.document.frmedita.div96v1.value='';
		window.document.frmedita.div96v2.value='';
		window.document.frmedita.div96v3.value='';
		let saiu20id=window.document.frmedita.saiu20id.value;
		let agno=window.document.frmedita.saiu20agno.value;
		document.getElementById('div_96titulo').innerHTML='<h2>'+window.document.frmedita.titulo_3020.value+' - Cargar archivo respuesta</h2>';
		document.getElementById('div_96cuerpo').innerHTML='<iframe id="iframe96" src="framearchivodis.php?ref=3020rta&id='+saiu20id+'&tabla=_'+agno+'&tipo=0" height="400px" width="100%" frameborder="0"></iframe>';
		expandesector(96);
		window.scrollTo(0, 150);
	}
	function eliminasaiu20idarchivorta(){
		let did=window.document.frmedita.saiu20id;
		let agno=window.document.frmedita.saiu20agno.value;
		if (confirm("Esta seguro de eliminar el archivo?")){
			xajax_elimina_archivo_saiu20idarchivorta(did.value, agno);
			//paginarf0000();
		}
	}
<?php
}
?>
function carga_combo_btema() {
	var params = new Array();
	params[0] = window.document.frmedita.bcategoria.value;
	document.getElementById('div_btema').innerHTML = '<b>Procesando datos, por favor espere...</b><input id="btema" name="btema" type="hidden" value="" />';
	xajax_f3020_Combobtema(params);
}
 function muestra_saiu20idcorreootro() {
	let saiu20idcorreo = document.getElementById('saiu20idcorreo').value;
	if (saiu20idcorreo == 3) {
		document.getElementById('lbl_saiu20idcorreootro').style.display = 'block';
	} else {
		document.getElementById('lbl_saiu20idcorreootro').style.display = 'none';
	}
 }
</script>
<?php
if ($_REQUEST['paso']!=0){
?>
<form id="frmimpp" name="frmimpp" method="post" action="p3020.php" target="_blank">
<input id="r" name="r" type="hidden" value="3020" />
<input id="id3020" name="id3020" type="hidden" value="<?php echo $_REQUEST['saiu20id']; ?>" />
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
<input id="id11" name="id11" type="hidden" value="<?php echo $idTercero; ?>" />
<input id="ipiel" name="ipiel" type="hidden" value="<?php echo $iPiel; ?>" />
<input id="seg_5" name="seg_5" type="hidden" value="<?php echo $seg_5; ?>" />
<input id="seg_6" name="seg_6" type="hidden" value="<?php echo $seg_6; ?>" />
<input id="vdtipointeresado" name="vdtipointeresado" type="hidden" value="<?php echo $_REQUEST['vdtipointeresado']; ?>" />
<input id="vdidcorreo" name="vdidcorreo" type="hidden" value="<?php echo $_REQUEST['vdidcorreo']; ?>" />
<div id="div_sector1">
<!-- TODO -->
<div class="areaform"> 
<div class="areatrabajo">
<div class="GrupoCamposAyuda">
<div class="salto5px"></div>
<label class="Label160">
<?php
echo $ETI['saiucanal'];
?>
</label>
<label class="Label160">
<?php
echo $html_saiucanal;
?>
</label>
<div class="salto5px"></div>
</div>
</div>
</div>
<!-- TODO -->
<?php 
switch ($iPiel) {
	case 2:
		break;
	default:
?>
<div class="titulos">
<div class="titulosD">
<input id="cmdAyuda" name="cmdAyuda" type="button" class="btUpAyuda" onclick="muestraayuda(<?php echo $APP->idsistema.', '.$iCodModulo; ?>);" title="<?php echo $ETI['bt_ayuda']; ?>" value="<?php echo $ETI['bt_ayuda']; ?>"/>
<?php
if ($_REQUEST['paso']==2){
	if ($_REQUEST['saiu20estado']<7 && $bPuedeEliminar){
?>
<input id="cmdEliminar" name="cmdEliminar" type="button" class="btUpEliminar" onclick="eliminadato();" title="<?php echo $ETI['bt_eliminar']; ?>" value="<?php echo $ETI['bt_eliminar']; ?>"/>
<?php
		}
	}
$bHayImprimir=false;
$sScript='imprimelista()';
$sClaseBoton='btEnviarExcel';
if ($seg_6==1){$bHayImprimir=true;}
if ($_REQUEST['paso']!=0){
	if ($seg_5==1){
		if ($_REQUEST['saiu20estado']>6){
			//$bHayImprimir=true;
			//$sScript='imprimep()';
			//if ($iNumFormatosImprime>0){
				//$sScript='expandesector(94)';
				//}
			//$sClaseBoton='btEnviarPDF'; //btUpPrint
			//if ($id_rpt!=0){$sScript='verrpt()';}
			}
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
if ($_REQUEST['saiu20estado']<7 && $bPuedeGuardar){
?>
<input id="cmdGuardar" name="cmdGuardar" type="button" class="btUpGuardar" onclick="enviaguardar();" title="<?php echo $ETI['bt_guardar']; ?>" value="<?php echo $ETI['bt_guardar']; ?>"/>
<?php
	if ($_REQUEST['paso']>0 && $bPuedeCerrar){
?>
<input id="cmdCerrar" name="cmdCerrar" type="button" class="btSupCerrar" onClick="enviacerrar();" title="Cerrar" value="Cerrar"/>
<?php
		}
	}else{
	if ($_REQUEST['paso']>0){
		if ($bPuedeAbrir){
?>
<input id="cmdAbrir" name="cmdAbrir" type="button" class="btSupAbrir" onclick="enviaabrir();" title="Abrir" value="Abrir"/>
<?php
			}
		}
	}
if (false){
?>
<input id="cmdAnular" name="cmdAnular" type="button" class="btSupAnular" onclick="expandesector(2);" title="<?php echo $ETI['bt_anular']; ?>" value="<?php echo $ETI['bt_anular']; ?>"/>
<?php
	}
?>
</div>
<div class="titulosI">
<?php
echo '<h2>'.$ETI['titulo_3020'].'</h2>';
?>
</div>
</div>
<?php
break;
}
?>
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
//Div para ocultar
$bConExpande=true;
if ($bConExpande){
?>
<div class="ir_derecha"<?php echo $sAnchoExpandeContrae; ?>>
<?php
$sEstiloExpande = ' style="display:none;"';
$sEstiloRecoje = '';
$sEstiloDiv = '';
if ($_REQUEST['boculta3020'] != 0) {
	$sEstiloExpande = '';
	$sEstiloRecoje = ' style="display:none;"';
	$sEstiloDiv = ' style="display:none;"';
}
?>
<input id="boculta3020" name="boculta3020" type="hidden" value="<?php echo $_REQUEST['boculta3020']; ?>" />
<label class="Label30">
<input id="btexpande3020" name="btexpande3020" type="button" value="Mostrar" class="btMiniExpandir" onclick="expandepanel(3020,'block',0);" title="<?php echo $ETI['bt_mostrar']; ?>" style="display:<?php if ($_REQUEST['boculta3020']==0){echo 'none'; }else{echo 'block';} ?>;"/>
</label>
<label class="Label30">
<input id="btrecoge3020" name="btrecoge3020" type="button" value="Ocultar" class="btMiniRecoger" onclick="expandepanel(3020,'none',1);" title="<?php echo $ETI['bt_ocultar']; ?>" style="display:<?php if ($_REQUEST['boculta3020']==0){echo 'block'; }else{echo 'none';} ?>;"/>
</label>
</div>
<div id="div_p3020" style="display:<?php if ($_REQUEST['boculta3020']==0){echo 'block'; }else{echo 'none';} ?>;">
<?php
	}
//Mostrar formulario para editar
?>
<label class="Label130">
<?php
echo $ETI['msg_fecha'];
?>
</label>
<?php
if ($_REQUEST['paso']==0){
?>
<label class="Label60">
<?php
echo $html_saiu20dia;
?>
</label>
<label class="Label130">
<?php
echo $html_saiu20mes;
?>
</label>
<label class="Label90">
<?php
echo $html_saiu20agno;
?>
</label>
<?php
	}else{
?>
<label class="Label220">
<?php
echo $html_saiu20dia.'/'.$html_saiu20mes.'/'.$html_saiu20agno;
?>
</label>
<?php
	}
?>
<label class="Label60">
<?php
echo $ETI['saiu20hora'];
?>
</label>
<div class="campo_HoraMin" id="div_saiu20hora">
<?php
$bOculto=False;
if ($_REQUEST['paso']!=0){$bOculto=True;}
echo html_HoraMin('saiu20hora', $_REQUEST['saiu20hora'], 'saiu20minuto', $_REQUEST['saiu20minuto'], $bOculto);
?>
</div>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu20consec'];
?>
</label>
<label class="Label130">
<?php
if ($_REQUEST['paso']!=2){
?>
<input id="saiu20consec" name="saiu20consec" type="text" value="<?php echo $_REQUEST['saiu20consec']; ?>" onchange="RevisaLlave()" class="cuatro"/>
<?php
	}else{
	echo html_oculto('saiu20consec', $_REQUEST['saiu20consec'], formato_numero($_REQUEST['saiu20consec']));
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
echo $ETI['saiu20id'];
?>
</label>
<label class="Label60">
<?php
	echo html_oculto('saiu20id', $_REQUEST['saiu20id'], formato_numero($_REQUEST['saiu20id']));
?>
</label>
<label class="Label130">
<?php
echo $ETI['saiu20estado'];
?>
</label>
<label>
<div id="div_saiu20estado">
<?php
echo $html_saiu20estado;
?>
</div>
<input id="saiu20estadoorigen" name="saiu20estadoorigen" type="hidden" value="<?php echo $_REQUEST['saiu20estadoorigen']; ?>" />
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu20idcorreo'];
?>
</label>
<label>
<?php
echo $html_saiu20idcorreo;
?>
</label>
<?php
if ($_REQUEST['saiu20idcorreo'] == 3) {
	$sEstilo = ' style="display:block;"';
} else {
	$sEstilo = ' style="display:none;"';
}
?>
<label id="lbl_saiu20idcorreootro" <?php echo $sEstilo; ?>>
<input id="saiu20idcorreootro" name="saiu20idcorreootro" type="text" value="<?php echo $_REQUEST['saiu20idcorreootro']; ?>" maxlength="20" placeholder="<?php echo $ETI['ing_campo'].$ETI['saiu20idcorreo']; ?>" class="L"/>
</label>
<div class="salto1px"></div>
<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo $ETI['saiu20idsolicitante'];
?>
</label>
<div class="salto1px"></div>
<input id="saiu20idsolicitante" name="saiu20idsolicitante" type="hidden" value="<?php echo $_REQUEST['saiu20idsolicitante']; ?>"/>
<div id="div_saiu20idsolicitante_llaves">
<?php
$bOculto=!$bEnProceso;
echo html_DivTerceroV2('saiu20idsolicitante', $_REQUEST['saiu20idsolicitante_td'], $_REQUEST['saiu20idsolicitante_doc'], $bOculto, 0, $ETI['ing_doc']);
?>
</div>
<div class="salto1px"></div>
<div id="div_saiu20idsolicitante" class="L"><?php echo $saiu20idsolicitante_rs; ?></div>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['saiu20tipointeresado'];
?>
</label>
<label class="Label200">
<?php
echo $html_saiu20tipointeresado;
?>
</label>
<div class="salto1px"></div>
<label class="L">
<?php
echo $ETI['saiu20correoorigen'];
?>
<input id="saiu20correoorigen" name="saiu20correoorigen" type="text" value="<?php echo $_REQUEST['saiu20correoorigen']; ?>" maxlength="20" placeholder="<?php echo $ETI['ing_campo'].$ETI['saiu20correoorigen']; ?>" class="L"/>
</label>
<div class="salto1px"></div>
<?php
if ($_REQUEST['saiu20idsolicitante'] == 0) {
	$sEstiloDiv = ' style="display:block;"';
} else {
	$sEstiloDiv = ' style="display:none;"';
}
?>
<div id="div_saiu20regsolicitante" class="L" <?php echo $sEstiloDiv; ?>>
<input id="cmdRegSolicitante" name="cmdRegSolicitante" type="button" value="<?php echo $ETI['saiu20regsolicitante']; ?>" class="BotonAzul200" onclick="window.open('unadpersonas.php', '_blank');" title="<?php echo $ETI['saiu20regsolicitante']; ?>"/>
</div>
<div class="salto1px"></div>
</div>

<div class="GrupoCampos520">
<label class="Label130">
<?php
echo $ETI['saiu20idzona'];
?>
</label>
<label>
<?php
echo $html_saiu20idzona;
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu20idcentro'];
?>
</label>
<label>
<div id="div_saiu20idcentro">
<?php
echo $html_saiu20idcentro;
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu20codpais'];
?>
</label>
<label>
<?php
echo $html_saiu20codpais;
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu20coddepto'];
?>
</label>
<label>
<div id="div_saiu20coddepto">
<?php
echo $html_saiu20coddepto;
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu20codciudad'];
?>
</label>
<label>
<div id="div_saiu20codciudad">
<?php
echo $html_saiu20codciudad;
?>
</div>
</label>
<div class="salto1px"></div>
</div>

<div class="salto1px"></div>
<div class="GrupoCampos">
<div class="tab">
<button class="tablinks" onclick="abrir_tab(event, 'solicitudes')" id="tab_inicial"><?php echo $ETI['titulo_3000']; ?></button>
<button class="tablinks" onclick="abrir_tab(event, 'pqrs')"><?php echo $ETI['titulo_3005_pqrs']; ?></button>
</div>
<input id="boculta3000" name="boculta3000" type="hidden" value="<?php echo $_REQUEST['boculta3000']; ?>" />
<div id="solicitudes" class="tabcontent">
<div id="div_f3000detalle">
<?php
echo $sTabla3000;
?>
</div>
</div>
<div id="pqrs" class="tabcontent">
<div>
<label class="Label60">
<?php
echo $ETI['saiu20agno'];
?>
</label>
<label class="Label90">
<?php
echo $html_bagnopqrs;
?>
</label>
</div>
<div class="salto1px"></div>
<div id="div_f3000pqrsdetalle">
<?php
echo $sTabla3000pqrs;
?>
</div>
</div>
<div class="salto1px"></div>
</div>

<div class="salto1px"></div>
<input id="saiu20clasesolicitud" name="saiu20clasesolicitud" type="hidden" value="<?php echo $_REQUEST['saiu20clasesolicitud']; ?>"/>
<label class="Label130">
<?php
echo $ETI['saiu20tiposolicitud'];
?>
</label>
<label>
<div id="div_saiu20tiposolicitud">
<?php
echo $html_saiu20tiposolicitud;
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu20temasolicitud'];
?>
</label>
<label>
<div id="div_saiu20temasolicitud">
<?php
echo $html_saiu20temasolicitud;
?>
</div>
<input id="saiu20temasolicitudorigen" name="saiu20temasolicitudorigen" type="hidden" value="<?php echo $_REQUEST['saiu20temasolicitudorigen']; ?>"/>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu20idescuela'];
?>
</label>
<label>
<?php
echo $html_saiu20idescuela;
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu20idprograma'];
?>
</label>
<label>
<div id="div_saiu20idprograma">
<?php
echo $html_saiu20idprograma;
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu20idperiodo'];
?>
</label>
<label>
<?php
echo $html_saiu20idperiodo;
?>
</label>
<div class="salto1px"></div>
<div class="GrupoCampos520">
<label class="txtAreaS">
<?php
echo $ETI['saiu20detalle'];
?>
<?php
$sInactivo='readonly disabled';
if ($bEditable){
	$sInactivo='';
} else {
?>
<input name="saiu20detalle" type="hidden" value="<?php echo $_REQUEST['saiu20detalle']; ?>" />
<?php
}
?>
<textarea id="saiu20detalle" name="saiu20detalle" placeholder="<?php echo $ETI['ing_campo'].$ETI['saiu20detalle']; ?>" <?php echo $sInactivo; ?>><?php echo $_REQUEST['saiu20detalle']; ?></textarea>
</label>
</div>
<div class="GrupoCampos300">
<div class="salto1px"></div>
<input id="saiu20idorigen" name="saiu20idorigen" type="hidden" value="<?php echo $_REQUEST['saiu20idorigen']; ?>" />
<input id="saiu20idarchivo" name="saiu20idarchivo" type="hidden" value="<?php echo $_REQUEST['saiu20idarchivo']; ?>" />
<div id="div_saiu20idarchivo" class="Campo220">
<?php
echo html_lnkarchivo((int)$_REQUEST['saiu20idorigen'], (int)$_REQUEST['saiu20idarchivo']);
?>
</div>
<?php
if ($_REQUEST['saiu20estado']==2) {
?>
<label class="Label30">
<input type="button" id="banexasaiu20idarchivo" name="banexasaiu20idarchivo" value="Anexar" class="btAnexarS" onclick="carga_saiu20idarchivo()" title="Cargar archivo" style="display:<?php if ((int)$_REQUEST['saiu20id'] != 0) {
echo 'block';
} else {
echo 'none';
} ?>;" />
</label>
<label class="Label30">
<input type="button" id="beliminasaiu20idarchivo" name="beliminasaiu20idarchivo" value="Eliminar" class="btBorrarS" onclick="eliminasaiu20idarchivo()" title="Eliminar archivo" style="display:<?php if ((int)$_REQUEST['saiu20idarchivo'] != 0) {
echo 'block';
} else {
echo 'none';
} ?>;" />
</label>
<?php
}
?>
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
<?php
if ($_REQUEST['saiu20estado']>0) {
	$sEstilo='display:none;';
	if ($_REQUEST['saiu20solucion']==1 || $_REQUEST['saiu20solucion']==3) {
		$sEstilo='display:block;';
	}
?>
<div id="div_saiu20respuesta" style="<?php echo $sEstilo; ?>">
<div class="GrupoCampos520">
<label class="txtAreaS">
<?php
echo $ETI['saiu20respuesta'];
?>
<?php
if ($_REQUEST['saiu20estado']==7) {
?>
<label class="Label220 ir_derecha">
<?php
if ($_REQUEST['saiu20solucion']==1) {
	$saiu20fecharesp = fecha_desdenumero($_REQUEST['saiu20fechafin']);
	$saiu20horaresp = html_HoraMin('saiu20horafin', $_REQUEST['saiu20horafin'], 'saiu20minutofin', $_REQUEST['saiu20minutofin'], true);
	echo $saiu20fecharesp . ' ' . $saiu20horaresp;
} else {
	$saiu20fecharespcaso = fecha_desdenumero($_REQUEST['saiu20fecharespcaso']);
	$saiu20horarespcaso = html_HoraMin('saiu20horarespcaso', $_REQUEST['saiu20horarespcaso'], 'saiu20minrespcaso', $_REQUEST['saiu20minrespcaso'], true);
	echo $saiu20fecharespcaso . ' ' . $saiu20horarespcaso;
}
?>
</label>
<?php
}
?>
<?php
$sInactivo='readonly disabled';
if ($bPuedeCerrar){$sInactivo='';}
?>
<textarea id="saiu20respuesta" name="saiu20respuesta" placeholder="<?php echo $ETI['ing_campo'].$ETI['saiu20respuesta']; ?>" <?php echo $sInactivo; ?>><?php echo $_REQUEST['saiu20respuesta']; ?></textarea>
</label>
</div>
<div class="GrupoCampos300">
<div class="salto1px"></div>
<input id="saiu20idorigenrta" name="saiu20idorigenrta" type="hidden" value="<?php echo $_REQUEST['saiu20idorigenrta']; ?>" />
<input id="saiu20idarchivorta" name="saiu20idarchivorta" type="hidden" value="<?php echo $_REQUEST['saiu20idarchivorta']; ?>" />
<div id="div_saiu20idarchivorta" class="Campo220">
<?php
echo html_lnkarchivo((int)$_REQUEST['saiu20idorigenrta'], (int)$_REQUEST['saiu20idarchivorta']);
?>
</div>
<?php
if ($_REQUEST['saiu20estado']>0 && $bPuedeCerrar) {
?>
<label class="Label30">
<input type="button" id="banexasaiu20idarchivorta" name="banexasaiu20idarchivorta" value="Anexar" class="btAnexarS" onclick="carga_saiu20idarchivorta()" title="Cargar archivo" style="display:<?php if ((int)$_REQUEST['saiu20id'] != 0) {
echo 'block';
} else {
echo 'none';
} ?>;" />
</label>
<label class="Label30">
<input type="button" id="beliminasaiu20idarchivorta" name="beliminasaiu20idarchivorta" value="Eliminar" class="btBorrarS" onclick="eliminasaiu20idarchivorta()" title="Eliminar archivo" style="display:<?php if ((int)$_REQUEST['saiu20idarchivorta'] != 0) {
echo 'block';
} else {
echo 'none';
} ?>;" />
</label>
<?php
}
?>
<div class="salto1px"></div>
</div>
</div>
<div class="salto1px"></div>
<div class="GrupoCampos450">
<label class="Label160">
<?php
echo $ETI['saiu20solucion'];
?>
</label>
<label class="Label130">
<?php
echo $html_saiu20solucion;
?>
</label>
<div class="salto1px"></div>
<?php
if (false) {
?>
<label class="Label200">
<?php
echo $ETI['saiu20paramercadeo'];
?>
</label>
<label class="Label130">
<?php
echo $html_saiu20paramercadeo;
?>
</label>
<div class="salto1px"></div>
<?php
} else {
?>
<input id="saiu20paramercadeo" name="saiu20paramercadeo" type="hidden" value="<?php echo $_REQUEST['saiu20paramercadeo']; ?>"/>
<?php
}
?>
<label class="Label160">
<?php
echo $ETI['saiu20idcaso'];
?>
</label>
<label class="Label130">
<?php
echo html_oculto('saiu20idcaso', $_REQUEST['saiu20idcaso']);
?>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['saiu20idpqrs'];
?>
</label>
<label class="Label160">
<?php
$html_saiu20idpqrs_cod='0';
if ($_REQUEST['saiu20idpqrs']!=0) {
	$html_saiu20idpqrs_cod=f3000_NumSolicitud($_REQUEST['saiu20agno'], $_REQUEST['saiu20mes'], $_REQUEST['saiu20idpqrs']);
}
echo html_oculto('saiu20idpqrs', $_REQUEST['saiu20idpqrs'],$html_saiu20idpqrs_cod);
if ($_REQUEST['saiu20numref']!='') {
	echo '<br><b>Ref. de consulta: <a href="saiusolcitudes.php?saiu05origenagno='.$_REQUEST['saiu20agno'].'&saiu05origenmes='.$_REQUEST['saiu20mes'].'&saiu05id='.$_REQUEST['saiu20idpqrs'].'&paso=3" target="_blank">' . $_REQUEST['saiu20numref'] . '</a></b>';
}
?>
</label>
<div class="salto1px"></div>
<?php
if ($_REQUEST['paso']==2){
	if ($_REQUEST['saiu20estado']<7 && $bPuedeCerrar){
?>
<label class="Label130">
<input id="cmdTermina" name="cmdTermina" type="button" value="<?php echo $ETI['saiu20cerrar']; ?>" class="BotonAzul160" onclick="enviacerrar()" title="<?php echo $ETI['saiu20cerrar']; ?>"/>
</label>
<?php
		}
	}
?>
<div class="salto1px"></div>
</div>
<?php
}
?>
<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo $ETI['saiu20idresponsable'];
?>
</label>
<div class="salto1px"></div>
<input id="saiu20idresponsable" name="saiu20idresponsable" type="hidden" value="<?php echo $_REQUEST['saiu20idresponsable']; ?>"/>
<div id="div_saiu20idresponsable_llaves">
<?php
$bOculto=true;
echo html_DivTerceroV2('saiu20idresponsable', $_REQUEST['saiu20idresponsable_td'], $_REQUEST['saiu20idresponsable_doc'], $bOculto, 0, $ETI['ing_doc']);
?>
</div>
<div class="salto1px"></div>
<div id="div_saiu20idresponsable" class="L"><?php echo $saiu20idresponsable_rs; ?></div>
<div class="salto1px"></div>
<label class="Label90">
<?php
echo $ETI['saiu20horafin'];
?>
</label>
<div class="campo_HoraMin" id="div_saiu20horafin">
<?php
$bOculto=False;
if ($_REQUEST['paso']!=0){$bOculto=True;}
echo html_HoraMin('saiu20horafin', $_REQUEST['saiu20horafin'], 'saiu20minutofin', $_REQUEST['saiu20minutofin'], $bOculto);
?>
<input id="saiu20fechafin" name="saiu20fechafin" type="hidden" value="<?php echo $_REQUEST['saiu20fechafin']; ?>"/>
</div>
<?php
if ($_REQUEST['saiu20estado']==1 || $_REQUEST['saiu20estado']==7){
?>
<div class="salto1px"></div>
<label class="L">
<?php
echo $ETI['saiu20tiemprespdias'].' <b>'.Tiempo_HTML($_REQUEST['saiu20tiemprespdias'], $_REQUEST['saiu20tiempresphoras'], $_REQUEST['saiu20tiemprespminutos']).'</b>';
?>
</label>
<?php
	}
?>
<div class="salto1px"></div>
</div>
<input id="saiu20tiemprespdias" name="saiu20tiemprespdias" type="hidden" value="<?php echo $_REQUEST['saiu20tiemprespdias']; ?>"/>
<input id="saiu20tiempresphoras" name="saiu20tiempresphoras" type="hidden" value="<?php echo $_REQUEST['saiu20tiempresphoras']; ?>"/>
<input id="saiu20tiemprespminutos" name="saiu20tiemprespminutos" type="hidden" value="<?php echo $_REQUEST['saiu20tiemprespminutos']; ?>"/>
<div class="salto1px"></div>
<?php
// Inicio caja - responsable caso
$sEstilo = ' style="display:none"';
if ((int)$_REQUEST['paso'] != 0) {
	if ($_REQUEST['saiu20solucion'] == 3) {
		$sEstilo = ' style="display:block"';
	}
}
?>
<div class="GrupoCampos520" <?php echo $sEstilo; ?>>
<label class="TituloGrupo">
<?php
echo $ETI['saiu20atiendecaso'];
?>
<?php
if ($_REQUEST['saiu20estado'] < 7) {
	if ($bPermisoSupv) {
?>
<div class="ir_derecha" style="width:62px;">
<input id="bRevAtiende" name="bRevAtiende" type="button" value="<?php echo $ETI['saiu20actatiendecaso']; ?>" class="btMiniActualizar" onclick="actualizaratiende()" title="<?php echo $ETI['saiu20actatiendecaso']; ?>" style="display:<?php if ((int)$_REQUEST['saiu20id'] != 0) {
echo 'block';
} else {
echo 'none';
} ?>;" />
</div>
<?php
	}
}
?>
</label>
<div class="salto1px"></div>
<input id="saiu20idsupervisorcaso" name="saiu20idsupervisorcaso" type="hidden" value="<?php echo $_REQUEST['saiu20idsupervisorcaso']; ?>" />
<input id="saiu20idresponsablecaso" name="saiu20idresponsablecaso" type="hidden" value="<?php echo $_REQUEST['saiu20idresponsablecaso']; ?>" />
<label class="Label160">
<?php echo $ETI['saiu20idunidadcaso']; ?>
</label>
<label class="Label200">
<b><?php echo $html_saiu20idunidadcaso; ?></b>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php echo $ETI['saiu20idequipocaso']; ?>
</label>
<label class="Label200">
<b><?php echo $html_saiu20idequipocaso; ?></b>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php echo $ETI['saiu20idsupervisorcaso']; ?>
</label>
<label class="Label200">
<b><?php echo $saiu20idsupervisorcaso_rs; ?></b>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php echo $ETI['saiu20idresponsablecaso']; ?>
</label>
<label id="lbl_f3020CambioResponsable" class="Label200">
<b><?php echo $saiu20idresponsablecaso_rs; ?></b>
</label>
<div class="salto1px"></div>
<?php
if ($_REQUEST['saiu20estado'] < 7) {
	if ($bPermisoSupv) {
?>
<label class="Label160">
<input id="cmdReasignar" name="cmdReasignar" type="button" class="BotonAzul200" value="<?php echo $ETI['saiu20reasignacaso']; ?>" onclick="expandesector(2);" title="<?php echo $ETI['saiu20reasignacaso']; ?>" />
</label>
<div class="salto1px"></div>
<?php
	}
}
?>
</div>
<?php
// Fin caja - responsable caso
?>
<?php
if (false){
	//Ejemplo de boton de ayuda
	//echo html_BotonAyuda('NombreCampo');
	//echo html_DivAyudaLocal('NombreCampo');
	}
if ($bConExpande){
	//Este es el cierre del div_p3020
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
<?php
echo $ETI['msg_bdoc'];
?>
</label>
<label class="Label200">
<input id="bdoc" name="bdoc" type="text" value="<?php echo $_REQUEST['bdoc']; ?>" onchange="paginarf3020()" autocomplete="off" />
</label>
<label class="Label90">
<?php
echo $ETI['msg_bnombre'];
?>
</label>
<label class="Label200">
<input id="bnombre" name="bnombre" type="text" value="<?php echo $_REQUEST['bnombre']; ?>" onchange="paginarf3020()" autocomplete="off" />
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu20tiposolicitud'];
?>
</label>
<label class="Label350">
<?php
echo $html_bcategoria;
?>
</label>
<div class="salto1px"></div>
<label class="Label90">
<?php
echo $ETI['saiu20temasolicitud'];
?>
</label>
<label class="Label350">
<div id="div_btema">
<?php
echo $html_btema;
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label60">
<?php
echo $ETI['msg_blistar'];
?>
</label>
<label class="Label200">
<?php
echo $html_blistar;
?>
</label>
<label class="Label60">
<?php
echo $ETI['saiu20estado'];
?>
</label>
<label class="Label130">
<?php
echo $html_bestado;
?>
</label>
<label class="Label60">
<?php
echo $ETI['saiu20agno'];
?>
</label>
<label class="Label130">
<?php
echo $html_bagno;
?>
</label>
</div>
<div class="salto1px"></div>
<?php
echo ' '.$csv_separa;
?>
<div id="div_f3020detalle">
<?php
echo $sTabla3020;
?>
</div>
</div><!-- /div_areatrabajo -->
</div><!-- /DIV_areaform -->
</div><!-- /DIV_Sector1 -->


<div id="div_sector2" style="display:none">
<div class="titulos">
<div class="titulosD">
<input id="cmdAyuda2" name="cmdAyuda2" type="button" class="btSupAyuda" onclick="muestraayuda(<?php echo $iCodModulo; ?>);" title="<?php echo $ETI['bt_ayuda']; ?>" value="<?php echo $ETI['bt_ayuda']; ?>" />
<input id="cmdVolverSec2" name="cmdVolverSec2" type="button" class="btSupVolver" onclick="expandesector(1);" title="<?php echo $ETI['bt_volver']; ?>" value="<?php echo $ETI['bt_volver']; ?>" />
</div>
<div class="titulosI">
<?php
echo '<h2>' . $ETI['titulo_sector2_reasigna'] . '</h2>';
?>
</div>
</div>
<div id="cargaForm">
<div id="area">
<?php
if ($_REQUEST['saiu20estado'] < 7) {
if ($idTercero == $_REQUEST['saiu20idsupervisorcaso'] || $seg_1707) {
	$sEstiloDiv = ' style="display:block;"';
} else {
	$sEstiloDiv = ' style="display:none;"';
}
}
?>
<div class="GrupoCampos520" <?php echo $sEstiloDiv; ?>>
<label class="TituloGrupo">
<?php
echo $ETI['saiu20atiendecaso'];
?>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php echo $ETI['saiu20idunidadcaso']; ?>
</label>
<label class="Label200">
<b><?php echo $html_saiu20idunidadcaso; ?></b>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php echo $ETI['saiu20idequipocaso']; ?>
</label>
<label class="Label200">
<b><?php echo $html_saiu20idequipocaso; ?></b>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php echo $ETI['saiu20idsupervisorcaso']; ?>
</label>
<label class="Label200">
<b><?php echo $saiu20idsupervisorcaso_rs; ?></b>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php echo $ETI['saiu20idresponsablecaso']; ?>
</label>
<label class="Label200">
<div id="div_saiu20idresponsablecaso">
<?php 
echo $html_saiu20idresponsablecasocombo; 
?>
</div>
</label>
<?php
if ($_REQUEST['saiu20estado'] < 7) {
if ($idTercero == $_REQUEST['saiu20idsupervisorcaso'] || $seg_1707) {
?>
<div class="salto1px"></div>
<label class="Label160">
<input id="cmdGuardarR" name="cmdGuardarR" type="button" class="BotonAzul200" value="<?php echo $ETI['guarda_reasigna']; ?>" onclick="enviareasignar();" title="<?php echo $ETI['guarda_reasigna']; ?>" />
</label>
<?php
}
}
?>
<div class="salto1px"></div>
</div>
</div>
</div>
<?php
// Termina el div_sector2
?>
</div>


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
echo $ETI['msg_saiu20consec'];
?>
</label>
<label class="Label90">
<?php
echo '<b>'.$_REQUEST['saiu20consec'].'</b>';
?>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['msg_saiu20consec_nuevo'];
// onchange="RevisaConsec()"
?>
</label>
<label class="Label90">
<input id="saiu20consec_nuevo" name="saiu20consec_nuevo" type="text" value="<?php echo $_REQUEST['saiu20consec_nuevo']; ?>" class="cuatro"/>
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
<input id="titulo_3020" name="titulo_3020" type="hidden" value="<?php echo $ETI['titulo_3020']; ?>" />
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
echo '<h2>'.$ETI['titulo_3020'].'</h2>';
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
echo '<h2>'.$ETI['titulo_3020'].'</h2>';
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
<?php
// Termina el bloque div_interna
?>
</div>
<?php
switch ($iPiel) {
	case 2:
		break;
	default:
		if ($bPuedeGuardar) {
			if ($_REQUEST['saiu20estado']<7){
?>
<div class="flotante">
<input id="cmdGuardarf" name="cmdGuardarf" type="button" class="btSoloGuardar" onClick="enviaguardar();" value="<?php echo $ETI['bt_guardar']; ?>" />
</div>
<?php
			}
		}
		break;
}
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
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>js/chosen.css" type="text/css"/>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>js/chosen.jquery.js"></script>
<script language="javascript">
document.getElementById("tab_inicial").click();
$().ready(function(){
<?php
if ($bEditable || $bPermisoSupv) {
?>
$("#saiu20idcentro").chosen({width:"100%"});
$("#saiu20coddepto").chosen({width:"100%"});
$("#saiu20codciudad").chosen({width:"100%"});
<?php
if ($bEnProceso){
?>
$("#saiu20tiposolicitud").chosen({width:"100%"});
<?php
	}
?>
$("#saiu20temasolicitud").chosen({width:"100%"});
$("#saiu20idprograma").chosen({width:"100%"});
$("#saiu20idperiodo").chosen({width:"100%"});
<?php
}
?>
$("#bcategoria").chosen({width:"100%"});
$("#btema").chosen({width:"100%"});
});
</script>
<script language="javascript" src="ac_3020.js"></script>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>unad_todas.js?ver=8"></script>
<?php
forma_piedepagina();
?>