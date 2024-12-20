<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2024 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 3.0.11b miércoles, 14 de agosto de 2024
*/
/** Archivo saiupresencial.php.
 * Modulo 3021 saiu21directa
 * @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
 * @param debug = 1  (Opcional), bandera para indicar si se generan datos de depuración
 * @date miércoles, 14 de agosto de 2024
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
require $APP->rutacomun . 'libsai.php';
require $APP->rutacomun . 'libtiempo.php';
require $APP->rutacomun . 'libmail.php';
require $APP->rutacomun . 'libaurea.php';
if (($bPeticionXAJAX) && ($_SESSION['unad_id_tercero'] == 0)) {
	// viene por xajax.
	$xajax = new xajax();
	$xajax->configure('javascript URI', $APP->rutacomun . 'xajax/');
	$xajax->register(XAJAX_FUNCTION, 'sesion_abandona_V2');
	$xajax->processRequest();
	die();
}
$iConsecutivoMenu = 2;
$iMinVerDB = 7774;
$iCodModulo = 3021;
$iCodModuloConsulta = $iCodModulo;
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
/*
$mensajes_000 = 'lg/lg_000_' . $_SESSION['unad_idioma'] . '.php';
if (!file_exists($mensajes_000)) {
	$mensajes_000 = 'lg/lg_000_es.php';
}
require $mensajes_000;
*/
$mensajes_3000=$APP->rutacomun.'lg/lg_3000_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_3000)) {
	$mensajes_3000=$APP->rutacomun.'lg/lg_3000_es.php';
}
$mensajes_3021 = 'lg/lg_3021_' . $_SESSION['unad_idioma'] . '.php';
if (!file_exists($mensajes_3021)) {
	$mensajes_3021 = 'lg/lg_3021_es.php';
}
require $mensajes_todas;
require $mensajes_3000;
require $mensajes_3021;
$xajax = NULL;
$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
if ($APP->dbpuerto != '') {
	$objDB->dbPuerto = $APP->dbpuerto;
}
// --- Variables para la forma
$bBloqueTitulo = true;
$bCerrado = false;
$bDebugMenu = false;
$bOtroUsuario = false;
$et_menu = '';
$idTercero = $_SESSION['unad_id_tercero'];
$iPiel = iDefinirPiel($APP, 2);
$sAnchoExpandeContrae = ' style="width:62px;"';
$sOcultaConsec = ''; //' style="display:none;"';
list($sGrupoModulo, $sPaginaModulo) = f109_GrupoModulo($iCodModuloConsulta, $iConsecutivoMenu, $objDB);
$sOcultaId = ' style="display:none;"';
$sTituloApp = $APP->siglasistema; //f101_SiglaModulo($APP->idsistema, $objDB);
$sTituloModulo = $ETI['titulo_3021'];
switch ($iPiel) {
	case 2:
		$sAnchoExpandeContrae = '';
		$bBloqueTitulo = false;
		break;
}
// --- Final de las variables para la forma
if ($bDebug) {
	$sDebug = $sDebug . fecha_microtiempo() . ' Probando conexi&oacute;n con la base de datos <b>' . $APP->dbname . '</b> en <b>' . $APP->dbhost . '</b><br>';
}
$bCargaMenu = true;
if (!$objDB->Conectar()) {
	$bCargaMenu = false;
	$bCerrado = true;
	$sMsgCierre = '<div class="MarquesinaGrande">Disculpe las molestias estamos en este momento nuestros servicios no estas disponibles.<br>Por favor intente acceder mas tarde.<br>Si el problema persiste por favor informe al administrador del sistema.</div>';
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' Error al intentar conectar con la base de datos <b>' . $objDB->serror . '</b><br>';
	}
}
if (!$bCerrado) {
	$iVerDB = version_upd($objDB);
	if ($iMinVerDB > $iVerDB) {
		$bCerrado = true;
		$sMsgCierre = '<div class="MarquesinaGrande">La base de datos se encuentra desactualizada para este modulo.<br>Por favor informe al administrador del sistema.</div>';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' <b>DB DESACTUALIZADA [Requerida:' . $iMinVerDB . ' - Encontrada:' . $iVerDB . ']</b><br>';
		}
	} else {
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' Versi&oacute;n DB <b>' . $iVerDB . '</b> [Requerida:' . $iMinVerDB . ']<br>';
		}
	}
}
if (!$bCerrado) {
	list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModuloConsulta, 1, $_SESSION['unad_id_tercero'], $objDB);
	if (!$bDevuelve) {
		$bCerrado = true;
		$sMsgCierre = '<div class="MarquesinaGrande">No cuenta con permiso para acceder a este modulo [' . $iCodModuloConsulta . '].</div>';
	}
}
if ($bCerrado) {
	if ($bCargaMenu) {
		switch ($iPiel) {
			case 2:
				list($et_menu, $sDebugM) = html_Menu2023($APP->idsistema, $objDB, $iPiel, $bDebugMenu, $idTercero);
				break;
			default:
				list($et_menu, $sDebugM) = html_menuV2($APP->idsistema, $objDB, $iPiel, $bDebugMenu, $idTercero);
				break;
		}
	}
	$objDB->CerrarConexion();
	switch ($iPiel) {
		case 2:
			require $APP->rutacomun . 'unad_forma2024.php';
			forma_InicioV4($xajax, $sTituloModulo);
			$aRutas = array(
				array('./', $sTituloApp),
				array('./' . $sPaginaModulo, $sGrupoModulo),
				array('', $sTituloModulo)
			);
			$iNumBoton = 0;
			$aBotones[$iNumBoton] = array('muestraayuda(' . $APP->idsistema . ', ' . $iCodModulo . ')', $ETI['bt_ayuda'], 'iHelp');
			$iNumBoton++;
			forma_cabeceraV4b($aRutas, $aBotones, true, 1);
			echo $et_menu;
			forma_mitad($idTercero);
			break;
		default:
			require $APP->rutacomun . 'unad_forma_v2_2024.php';
			forma_cabeceraV3($xajax, $sTituloModulo);
			echo $et_menu;
			forma_mitad();
			break;
	}
	$objForma = new clsHtmlForma($iPiel);
	if ($bBloqueTitulo) {
		$objForma->addBoton('cmdAyuda98', 'btSupAyuda', 'muestraayuda('.$iCodModulo.');', $ETI['bt_ayuda']);
		echo $objForma->htmlTitulo($sTituloModulo, $iCodModulo);
	}
	echo $objForma->htmlInicioMarco();
	echo $sMsgCierre;
	if ($bDebug) {
		echo $sDebug;
	}
	echo $objForma->htmlFinMarco();
	forma_piedepagina();
	die();
}
if (!$bPeticionXAJAX) {
	if (noticias_pendientes($objDB)) {
		$objDB->CerrarConexion();
		header('Location:noticia.php?ret=saiupresencial.php');
		die();
	}
}
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
$idEntidad = Traer_Entidad();
// -- Si esta cargando la pagina por primer vez se revisa si requiere auditar y se manda a hacer un limpiar (paso -1)
if (isset($_REQUEST['paso']) == 0) {
	$_REQUEST['paso'] = -1;
	if ($audita[1]) {
		seg_auditaingreso($iCodModulo, $_SESSION['unad_id_tercero'], $objDB);
	}
}
// -- 3021 saiu21directa
require 'lib3021.php';
// -- 3000 Historial de solicitudes
require $APP->rutacomun.'lib3000.php';
$xajax = new xajax();
$xajax->configure('javascript URI', $APP->rutacomun . 'xajax/');
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
$xajax->register(XAJAX_FUNCTION, 'sesion_abandona_V2');
$xajax->register(XAJAX_FUNCTION, 'sesion_mantenerV4');
$xajax->register(XAJAX_FUNCTION, 'f3021_HtmlTabla');
$xajax->register(XAJAX_FUNCTION, 'f3021_ExisteDato');
$xajax->register(XAJAX_FUNCTION,'f3021_Busquedas');
$xajax->register(XAJAX_FUNCTION,'f3021_HtmlBusqueda');
$xajax->register(XAJAX_FUNCTION,'f3000_HtmlTabla');
$xajax->register(XAJAX_FUNCTION,'f3000pqrs_HtmlTabla');
$xajax->register(XAJAX_FUNCTION,'elimina_archivo_saiu21idarchivo');
$xajax->register(XAJAX_FUNCTION,'elimina_archivo_saiu21idarchivorta');
$xajax->register(XAJAX_FUNCTION,'f3021_Combobtema');
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
if (isset($_REQUEST['paginaf3021'])==0){$_REQUEST['paginaf3021']=1;}
if (isset($_REQUEST['lppf3021'])==0){$_REQUEST['lppf3021']=20;}
if (isset($_REQUEST['boculta3021'])==0){$_REQUEST['boculta3021']=0;}
if (isset($_REQUEST['paginaf3000'])==0){$_REQUEST['paginaf3000']=1;}
if (isset($_REQUEST['lppf3000'])==0){$_REQUEST['lppf3000']=10;}
if (isset($_REQUEST['paginaf3000pqrs'])==0){$_REQUEST['paginaf3000pqrs']=1;}
if (isset($_REQUEST['lppf3000pqrs'])==0){$_REQUEST['lppf3000pqrs']=10;}
if (isset($_REQUEST['boculta3000'])==0){$_REQUEST['boculta3000']=0;}
// -- Inicializar variables de datos.
if (isset($_REQUEST['saiu21agno'])==0){$_REQUEST['saiu21agno']='';}
if (isset($_REQUEST['saiu21mes'])==0){$_REQUEST['saiu21mes']='';}
if (isset($_REQUEST['saiu21tiporadicado'])==0){$_REQUEST['saiu21tiporadicado']=1;}
if (isset($_REQUEST['saiu21consec'])==0){$_REQUEST['saiu21consec']='';}
if (isset($_REQUEST['saiu21consec_nuevo'])==0){$_REQUEST['saiu21consec_nuevo']='';}
if (isset($_REQUEST['saiu21id'])==0){$_REQUEST['saiu21id']='';}
if (isset($_REQUEST['saiu21dia'])==0){$_REQUEST['saiu21dia']=fecha_dia();}
if (isset($_REQUEST['saiu21hora'])==0){$_REQUEST['saiu21hora']=fecha_hora();}
if (isset($_REQUEST['saiu21minuto'])==0){$_REQUEST['saiu21minuto']=fecha_minuto();}
if (isset($_REQUEST['saiu21estado'])==0){$_REQUEST['saiu21estado']=-1;}
if (isset($_REQUEST['saiu21estadoorigen'])==0){$_REQUEST['saiu21estadoorigen']=-1;}
if (isset($_REQUEST['saiu21idsolicitante'])==0){$_REQUEST['saiu21idsolicitante']=0;}// {$_SESSION['unad_id_tercero'];}
if (isset($_REQUEST['saiu21idsolicitante_td'])==0){$_REQUEST['saiu21idsolicitante_td']=$APP->tipo_doc;}
if (isset($_REQUEST['saiu21idsolicitante_doc'])==0){$_REQUEST['saiu21idsolicitante_doc']='';}
if (isset($_REQUEST['saiu21tipointeresado'])==0){$_REQUEST['saiu21tipointeresado']='';}
if (isset($_REQUEST['saiu21clasesolicitud'])==0){$_REQUEST['saiu21clasesolicitud']=0;}
if (isset($_REQUEST['saiu21tiposolicitud'])==0){$_REQUEST['saiu21tiposolicitud']=0;}
if (isset($_REQUEST['saiu21temasolicitud'])==0){$_REQUEST['saiu21temasolicitud']=0;}
if (isset($_REQUEST['saiu21temasolicitudorigen'])==0){$_REQUEST['saiu21temasolicitudorigen']='';}
if (isset($_REQUEST['saiu21idzona'])==0){$_REQUEST['saiu21idzona']='';}
if (isset($_REQUEST['saiu21idcentro'])==0){$_REQUEST['saiu21idcentro']='';}
if (isset($_REQUEST['saiu21codpais'])==0){$_REQUEST['saiu21codpais']=$_SESSION['unad_pais'];}
if (isset($_REQUEST['saiu21coddepto'])==0){$_REQUEST['saiu21coddepto']='';}
if (isset($_REQUEST['saiu21codciudad'])==0){$_REQUEST['saiu21codciudad']='';}
if (isset($_REQUEST['saiu21idescuela'])==0){$_REQUEST['saiu21idescuela']='';}
if (isset($_REQUEST['saiu21idprograma'])==0){$_REQUEST['saiu21idprograma']='';}
if (isset($_REQUEST['saiu21idperiodo'])==0){$_REQUEST['saiu21idperiodo']='';}
if (isset($_REQUEST['saiu21idpqrs'])==0){$_REQUEST['saiu21idpqrs']=0;}
if (isset($_REQUEST['saiu21detalle'])==0){$_REQUEST['saiu21detalle']='';}
if (isset($_REQUEST['saiu21fechafin'])==0){$_REQUEST['saiu21fechafin']='';}
if (isset($_REQUEST['saiu21horafin'])==0){$_REQUEST['saiu21horafin']='';}
if (isset($_REQUEST['saiu21minutofin'])==0){$_REQUEST['saiu21minutofin']='';}
if (isset($_REQUEST['saiu21paramercadeo'])==0){$_REQUEST['saiu21paramercadeo']=0;}
if (isset($_REQUEST['saiu21idresponsable'])==0){$_REQUEST['saiu21idresponsable']=0;}// {$_SESSION['unad_id_tercero'];}
if (isset($_REQUEST['saiu21idresponsable_td'])==0){$_REQUEST['saiu21idresponsable_td']=$APP->tipo_doc;}
if (isset($_REQUEST['saiu21idresponsable_doc'])==0){$_REQUEST['saiu21idresponsable_doc']='';}
if (isset($_REQUEST['saiu21tiemprespdias'])==0){$_REQUEST['saiu21tiemprespdias']='';}
if (isset($_REQUEST['saiu21tiempresphoras'])==0){$_REQUEST['saiu21tiempresphoras']='';}
if (isset($_REQUEST['saiu21tiemprespminutos'])==0){$_REQUEST['saiu21tiemprespminutos']='';}
if (isset($_REQUEST['saiu21solucion'])==0){$_REQUEST['saiu21solucion']=0;}
if (isset($_REQUEST['saiu21idcaso'])==0){$_REQUEST['saiu21idcaso']=0;}
if (isset($_REQUEST['saiu21respuesta'])==0){$_REQUEST['saiu21respuesta']='';}
if (isset($_REQUEST['saiu21idorigen'])==0){$_REQUEST['saiu21idorigen']=0;}
if (isset($_REQUEST['saiu21idarchivo'])==0){$_REQUEST['saiu21idarchivo']=0;}
if (isset($_REQUEST['saiu21idorigenrta'])==0){$_REQUEST['saiu21idorigenrta']=0;}
if (isset($_REQUEST['saiu21idarchivorta'])==0){$_REQUEST['saiu21idarchivorta']=0;}
if (isset($_REQUEST['saiu21fecharespcaso'])==0){$_REQUEST['saiu21fecharespcaso']='';}
if (isset($_REQUEST['saiu21horarespcaso'])==0){$_REQUEST['saiu21horarespcaso']=0;}
if (isset($_REQUEST['saiu21minrespcaso'])==0){$_REQUEST['saiu21minrespcaso']=0;}
if (isset($_REQUEST['saiu21idunidadcaso'])==0){$_REQUEST['saiu21idunidadcaso']=0;}
if (isset($_REQUEST['saiu21idequipocaso'])==0){$_REQUEST['saiu21idequipocaso']=0;}
if (isset($_REQUEST['saiu21idsupervisorcaso'])==0){$_REQUEST['saiu21idsupervisorcaso']=0;}
if (isset($_REQUEST['saiu21idresponsablecaso'])==0){$_REQUEST['saiu21idresponsablecaso']=0;}
if (isset($_REQUEST['saiu21numref'])==0){$_REQUEST['saiu21numref']='';}
if (isset($_REQUEST['saiu21evalacepta'])==0){$_REQUEST['saiu21evalacepta']=0;}
if (isset($_REQUEST['saiu21evalfecha'])==0){$_REQUEST['saiu21evalfecha']='';}
if (isset($_REQUEST['saiu21evalamabilidad'])==0){$_REQUEST['saiu21evalamabilidad']=0;}
if (isset($_REQUEST['saiu21evalamabmotivo'])==0){$_REQUEST['saiu21evalamabmotivo']='';}
if (isset($_REQUEST['saiu21evalrapidez'])==0){$_REQUEST['saiu21evalrapidez']=0;}
if (isset($_REQUEST['saiu21evalrapidmotivo'])==0){$_REQUEST['saiu21evalrapidmotivo']='';}
if (isset($_REQUEST['saiu21evalclaridad'])==0){$_REQUEST['saiu21evalclaridad']=0;}
if (isset($_REQUEST['saiu21evalcalridmotivo'])==0){$_REQUEST['saiu21evalcalridmotivo']='';}
if (isset($_REQUEST['saiu21evalresolvio'])==0){$_REQUEST['saiu21evalresolvio']=0;}
if (isset($_REQUEST['saiu21evalsugerencias'])==0){$_REQUEST['saiu21evalsugerencias']='';}
if (isset($_REQUEST['saiu21evalconocimiento'])==0){$_REQUEST['saiu21evalconocimiento']=0;}
if (isset($_REQUEST['saiu21evalconocmotivo'])==0){$_REQUEST['saiu21evalconocmotivo']='';}
if (isset($_REQUEST['saiu21evalutilidad'])==0){$_REQUEST['saiu21evalutilidad']=0;}
if (isset($_REQUEST['saiu21evalutilmotivo'])==0){$_REQUEST['saiu21evalutilmotivo']='';}
if (isset($_REQUEST['saiu21idresponsablecaso_td'])==0){$_REQUEST['saiu21idresponsablecaso_td']=$APP->tipo_doc;}
if (isset($_REQUEST['saiu21idresponsablecaso_doc'])==0){$_REQUEST['saiu21idresponsablecaso_doc']='';}
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
if (isset($_REQUEST['vdidtelefono'])==0){
	$sVr='';
	$sSQL='SELECT saiu22id FROM saiu22telefonos WHERE saiu22predet=1 ORDER BY saiu22orden, saiu22consec';
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$sVr=$fila['saiu22id'];
		}
	$_REQUEST['vdidtelefono']=$sVr;
	}
if (isset($_REQUEST['saiucanal'])==0){$_REQUEST['saiucanal']=1;}
//Si Modifica o Elimina Cargar los campos
if (($_REQUEST['paso']==1)||($_REQUEST['paso']==3)){
	$_REQUEST['saiu21idsolicitante_td']=$APP->tipo_doc;
	$_REQUEST['saiu21idsolicitante_doc']='';
	$_REQUEST['saiu21idresponsable_td']=$APP->tipo_doc;
	$_REQUEST['saiu21idresponsable_doc']='';
	$_REQUEST['saiu21idresponsablecaso_td']=$APP->tipo_doc;
	$_REQUEST['saiu21idresponsablecaso_doc']='';
	$sTabla = 'saiu21directa_'.$_REQUEST['saiu21agno'];
	if ($objDB->bexistetabla($sTabla)) {
		list($sErrorR, $sDebugR) = f3021_RevTabla_saiu21directa($_REQUEST['saiu21agno'], $objDB, $bDebug);
		$sError = $sError . $sErrorR;
		$sDebug = $sDebug . $sDebugR;
		if ($_REQUEST['paso']==1) {
			$sSQLcondi='saiu21agno='.$_REQUEST['saiu21agno'].' AND saiu21mes='.$_REQUEST['saiu21mes'].' AND saiu21tiporadicado='.$_REQUEST['saiu21tiporadicado'].' AND saiu21consec='.$_REQUEST['saiu21consec'].'';
		} else {
			$sSQLcondi='saiu21id='.$_REQUEST['saiu21id'].'';
		}
		$sSQL='SELECT * FROM ' . $sTabla . ' WHERE '.$sSQLcondi;
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta registro: '.$sSQL.'<br>';}
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0) {
			$fila=$objDB->sf($tabla);
			$_REQUEST['saiu21agno']=$fila['saiu21agno'];
			$_REQUEST['saiu21mes']=$fila['saiu21mes'];
			$_REQUEST['saiu21tiporadicado']=$fila['saiu21tiporadicado'];
			$_REQUEST['saiu21consec']=$fila['saiu21consec'];
			$_REQUEST['saiu21id']=$fila['saiu21id'];
			$_REQUEST['saiu21dia']=$fila['saiu21dia'];
			$_REQUEST['saiu21hora']=$fila['saiu21hora'];
			$_REQUEST['saiu21minuto']=$fila['saiu21minuto'];
			$_REQUEST['saiu21estado']=$fila['saiu21estado'];
			$_REQUEST['saiu21estadoorigen']=$fila['saiu21estado'];
			$_REQUEST['saiu21idsolicitante']=$fila['saiu21idsolicitante'];
			$_REQUEST['saiu21tipointeresado']=$fila['saiu21tipointeresado'];
			$_REQUEST['saiu21clasesolicitud']=$fila['saiu21clasesolicitud'];
			$_REQUEST['saiu21tiposolicitud']=$fila['saiu21tiposolicitud'];
			$_REQUEST['saiu21temasolicitud']=$fila['saiu21temasolicitud'];
			$_REQUEST['saiu21temasolicitudorigen']=$fila['saiu21temasolicitud'];
			$_REQUEST['saiu21idzona']=$fila['saiu21idzona'];
			$_REQUEST['saiu21idcentro']=$fila['saiu21idcentro'];
			$_REQUEST['saiu21codpais']=$fila['saiu21codpais'];
			$_REQUEST['saiu21coddepto']=$fila['saiu21coddepto'];
			$_REQUEST['saiu21codciudad']=$fila['saiu21codciudad'];
			$_REQUEST['saiu21idescuela']=$fila['saiu21idescuela'];
			$_REQUEST['saiu21idprograma']=$fila['saiu21idprograma'];
			$_REQUEST['saiu21idperiodo']=$fila['saiu21idperiodo'];
			$_REQUEST['saiu21idpqrs']=$fila['saiu21idpqrs'];
			$_REQUEST['saiu21detalle']=$fila['saiu21detalle'];
			$_REQUEST['saiu21fechafin']=$fila['saiu21fechafin'];
			$_REQUEST['saiu21horafin']=$fila['saiu21horafin'];
			$_REQUEST['saiu21minutofin']=$fila['saiu21minutofin'];
			$_REQUEST['saiu21paramercadeo']=$fila['saiu21paramercadeo'];
			$_REQUEST['saiu21idresponsable']=$fila['saiu21idresponsable'];
			$_REQUEST['saiu21tiemprespdias']=$fila['saiu21tiemprespdias'];
			$_REQUEST['saiu21tiempresphoras']=$fila['saiu21tiempresphoras'];
			$_REQUEST['saiu21tiemprespminutos']=$fila['saiu21tiemprespminutos'];
			$_REQUEST['saiu21solucion']=$fila['saiu21solucion'];
			$_REQUEST['saiu21idcaso']=$fila['saiu21idcaso'];
			$_REQUEST['saiu21respuesta']=$fila['saiu21respuesta'];
			if ($sError=='') {
				$_REQUEST['saiu21idorigen'] = $fila['saiu21idorigen'];
				$_REQUEST['saiu21idarchivo'] = $fila['saiu21idarchivo'];
				$_REQUEST['saiu21idorigenrta'] = $fila['saiu21idorigenrta'];
				$_REQUEST['saiu21idarchivorta'] = $fila['saiu21idarchivorta'];
				$_REQUEST['saiu21fecharespcaso'] = $fila['saiu21fecharespcaso'];
				$_REQUEST['saiu21horarespcaso'] = $fila['saiu21horarespcaso'];
				$_REQUEST['saiu21minrespcaso'] = $fila['saiu21minrespcaso'];
				$_REQUEST['saiu21idunidadcaso'] = $fila['saiu21idunidadcaso'];
				$_REQUEST['saiu21idequipocaso'] = $fila['saiu21idequipocaso'];
				$_REQUEST['saiu21idsupervisorcaso'] = $fila['saiu21idsupervisorcaso'];
				$_REQUEST['saiu21idresponsablecaso'] = $fila['saiu21idresponsablecaso'];
				$_REQUEST['saiu21numref'] = $fila['saiu21numref'];
				$_REQUEST['saiu21evalacepta'] = $fila['saiu21evalacepta'];
				$_REQUEST['saiu21evalfecha'] = $fila['saiu21evalfecha'];
				$_REQUEST['saiu21evalamabilidad'] = $fila['saiu21evalamabilidad'];
				$_REQUEST['saiu21evalamabmotivo'] = $fila['saiu21evalamabmotivo'];
				$_REQUEST['saiu21evalrapidez'] = $fila['saiu21evalrapidez'];
				$_REQUEST['saiu21evalrapidmotivo'] = $fila['saiu21evalrapidmotivo'];
				$_REQUEST['saiu21evalclaridad'] = $fila['saiu21evalclaridad'];
				$_REQUEST['saiu21evalcalridmotivo'] = $fila['saiu21evalcalridmotivo'];
				$_REQUEST['saiu21evalresolvio'] = $fila['saiu21evalresolvio'];
				$_REQUEST['saiu21evalsugerencias'] = $fila['saiu21evalsugerencias'];
				$_REQUEST['saiu21evalconocimiento'] = $fila['saiu21evalconocimiento'];
				$_REQUEST['saiu21evalconocmotivo'] = $fila['saiu21evalconocmotivo'];
				$_REQUEST['saiu21evalutilidad'] = $fila['saiu21evalutilidad'];
				$_REQUEST['saiu21evalutilmotivo'] = $fila['saiu21evalutilmotivo'];
			}
			$bcargo=true;
			$_REQUEST['paso']=2;
			$_REQUEST['boculta3021']=0;
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
	$_REQUEST['saiu21estado']=7;
	$bCerrando=true;
	}
if ($_REQUEST['paso']==21){
	$_REQUEST['paso']=12;
	$_REQUEST['saiu21estado']=8;
	$bCerrando=true;
	}
if ($_REQUEST['paso']==22){
	$_REQUEST['paso']=12;
	$_REQUEST['saiu21estado']=9;
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
		$_REQUEST['saiu21estado']=7;
		}else{
		$saiu21estado=2;
		if ($_REQUEST['saiu21idcaso']!=0){$saiu21estado=1;}
		$sSQL='UPDATE saiu21directa_'.$_REQUEST['saiu21agno'].' SET saiu21estado='.$saiu21estado.' WHERE saiu21id='.$_REQUEST['saiu21id'].'';
		$tabla=$objDB->ejecutasql($sSQL);
		seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 3, $_REQUEST['saiu21id'], 'Abre Registro de atencion presencial', $objDB);
		$_REQUEST['saiu21estado']=$saiu21estado;
		$sError='<b>El registro ha sido abierto</b>';
		$iTipoError=1;
		}
	}
//Insertar o modificar un elemento
if (($_REQUEST['paso']==10)||($_REQUEST['paso']==12)){
	$bMueveScroll=true;
	list($_REQUEST, $sError, $iTipoError, $bCerrando, $sErrorCerrando, $sDebugGuardar)=f3021_db_GuardarV2($_REQUEST, $objDB, $bDebug);
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
				if ($_REQUEST['saiu21estado']!=1) {
					$_REQUEST['saiu21estado']=-1;
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
	$_REQUEST['saiu21consec_nuevo']=numeros_validar($_REQUEST['saiu21consec_nuevo']);
	if ($_REQUEST['saiu21consec_nuevo']==''){$sError=$ERR['saiu21consec'];}
	if ($sError==''){
		if (!seg_revisa_permiso($iCodModulo, 8, $objDB)){
			$sError=$ERR['8'];
			}
		}
	if ($sError==''){
		//Ver que el consecutivo no exista.
		$sSQL='SELECT saiu21id FROM saiu21directa_'.$_REQUEST['saiu21agno'].' WHERE saiu21consec='.$_REQUEST['saiu21consec_nuevo'].' AND saiu21tiporadicado='.$_REQUEST['saiu21tiporadicado'].' AND saiu21mes='.$_REQUEST['saiu21mes'].' AND saiu21agno='.$_REQUEST['saiu21agno'].'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$sError='El consecutivo '.$_REQUEST['saiu21consec_nuevo'].' ya existe';
			}
		}
	if ($sError==''){
		//Aplicar el cambio.
		$sSQL='UPDATE saiu21directa_'.$_REQUEST['saiu21agno'].' SET saiu21consec='.$_REQUEST['saiu21consec_nuevo'].' WHERE saiu21id='.$_REQUEST['saiu21id'].'';
		$tabla=$objDB->ejecutasql($sSQL);
		$sDetalle='Cambia el consecutivo de '.$_REQUEST['saiu21consec'].' a '.$_REQUEST['saiu21consec_nuevo'].'';
		$_REQUEST['saiu21consec']=$_REQUEST['saiu21consec_nuevo'];
		$_REQUEST['saiu21consec_nuevo']='';
		seg_auditar($iCodModulo, $_SESSION['u_idtercero'], 8, $_REQUEST['saiu21id'], $sDetalle, $objDB);
		$sError='<b>Se ha aplicado el cambio de consecutivo.</b>';
		$iTipoError=1;
		}else{
		$iSector=93;
		}
	}
//Eliminar un elemento
if ($_REQUEST['paso'] == 13) {
	$_REQUEST['paso'] = 2;
	if ($sError == '') {
		list($sError, $iTipoError, $sDebugElimina)=f3021_db_Eliminar($_REQUEST['saiu21agno'], $_REQUEST['saiu21id'], $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugElimina;
	}
	if ($sError == '') {
		$_REQUEST['paso'] = -1;
		$sError = $ETI['msg_itemeliminado'];
		$iTipoError = 1;
	}
}
// Reasignar responsable.
if ($_REQUEST['paso'] == 26) {
	$_REQUEST['paso'] = 2;
	$bMueveScroll = true;
	$bPermisoSupv = $idTercero == $_REQUEST['saiu21idsupervisorcaso'];
	if ($bPermisoSupv || $seg_1707) {
		$sTabla21 = 'saiu21directa_' . $_REQUEST['saiu21agno'];
		if (!$objDB->bexistetabla($sTabla21)) {
			$sError = 'No ha sido posible acceder al contenedor de datos ' . $sTabla21 . '';
		}
		if ($sError == '') {
			$bCambiaLider = false;
			$saiu21idunidadcaso = $_REQUEST['saiu21idunidadcaso'];
			$saiu21idequipocaso = $_REQUEST['saiu21idequipocaso'];
			$saiu21idsupervisorcaso = $_REQUEST['saiu21idsupervisorcaso'];
			$sSQL = 'SELECT bita27id, bita27idlider, bita27idunidadfunc FROM bita27equipotrabajo WHERE bita27idlider=' . $_REQUEST['saiu21idresponsablecasofin'] . ' AND bita27activo=1 ';
			$tabla = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla) > 0) {
				$fila = $objDB->sf($tabla);
				$sSQL = 'UPDATE ' . $sTabla21 . ' SET saiu21idunidadcaso=' . $fila['bita27idunidadfunc'] . ', saiu21idequipocaso=' . $fila['bita27id'] . ', 
saiu21idsupervisorcaso=' . $fila['bita27idlider'] . ', saiu21idresponsablecaso=' . $_REQUEST['saiu21idresponsablecasofin'] . ' WHERE saiu21id=' . $_REQUEST['saiu21id'] . '';
				$bCambiaLider = true;
				$saiu05idunidadresp = $fila['bita27idunidadfunc'];
				$saiu21idequipocaso = $fila['bita27id'];
				$saiu21idsupervisorcaso = $fila['bita27idlider'];
			} else {
				$sSQL = 'UPDATE ' . $sTabla21 . ' SET saiu21idresponsablecaso=' . $_REQUEST['saiu21idresponsablecasofin'] . ' WHERE saiu21id=' . $_REQUEST['saiu21id'] . '';
			}
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . ' Consulta reasignación: '.$sSQL.'<br>';
			}
			$result = $objDB->ejecutasql($sSQL);
			if ($result==false){
				$sError=$sError.$ERR['saiu21idresponsablecasofin'].'';
			} else {
				seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 3, $_REQUEST['saiu21id'], 'Reasigna el responsable ', $objDB);
				if ($bCambiaLider) {
					$_REQUEST['saiu21idunidadcaso']=$saiu21idunidadcaso;
					$_REQUEST['saiu21idequipocaso']=$saiu21idequipocaso;
					$_REQUEST['saiu21idsupervisorcaso']=$saiu21idsupervisorcaso;
				}
				$_REQUEST['saiu21idresponsablecaso']=$_REQUEST['saiu21idresponsablecasofin'];
				$sError = '<b>Se ha realizado la reasignaci&oacute;n.</b>';
				$iTipoError = 1;
				$_REQUEST['saiuid']=21;
				list($sMensaje, $sErrorE, $sDebugE) = f3000_EnviaCorreosAtencion($_REQUEST, $_REQUEST['saiu21agno'], $objDB, $bDebug, true);
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
	if ($_REQUEST['saiu21estado'] < 7){
		list($_REQUEST, $sErrorE, $iTipoError, $sDebugGuardar) = f3021_ActualizarAtiende($_REQUEST, $objDB, $bDebug, $idTercero);
		$sError = $sError . $sErrorE;
		$sDebug = $sDebug . $sDebugGuardar;
	} else {
		$sError = $sError . $ETI['saiu21cerrada'];
	}
}
//limpiar la pantalla
if ($_REQUEST['paso'] == -1) {
	$_REQUEST['saiu21agno']=fecha_agno();
	$_REQUEST['saiu21mes']=fecha_mes();
	//$_REQUEST['saiu21tiporadicado']='';
	$_REQUEST['saiu21consec']='';
	$_REQUEST['saiu21consec_nuevo']='';
	$_REQUEST['saiu21id']='';
	$_REQUEST['saiu21dia']=fecha_dia();
	$_REQUEST['saiu21hora']=fecha_hora();
	$_REQUEST['saiu21minuto']=fecha_minuto();
	$_REQUEST['saiu21estado']=-1;
	$_REQUEST['saiu21estadoorigen']=-1;
	$_REQUEST['saiu21idsolicitante']=0;//$idTercero;
	$_REQUEST['saiu21idsolicitante_td']=$APP->tipo_doc;
	$_REQUEST['saiu21idsolicitante_doc']='';
	$_REQUEST['saiu21tipointeresado']=$_REQUEST['vdtipointeresado'];
	$_REQUEST['saiu21clasesolicitud']=0;
	$_REQUEST['saiu21tiposolicitud']=0;
	$_REQUEST['saiu21temasolicitud']=0;
	$_REQUEST['saiu21temasolicitudorigen']='';
	$_REQUEST['saiu21idzona']='';
	$_REQUEST['saiu21idcentro']='';
	$_REQUEST['saiu21codpais']=$_SESSION['unad_pais'];
	$_REQUEST['saiu21coddepto']='';
	$_REQUEST['saiu21codciudad']='';
	$_REQUEST['saiu21idescuela']=0;
	$_REQUEST['saiu21idprograma']=0;
	$_REQUEST['saiu21idperiodo']=0;
	$_REQUEST['saiu21idpqrs']=0;
	$_REQUEST['saiu21detalle']='';
	$_REQUEST['saiu21fechafin']='';
	$_REQUEST['saiu21horafin']='';
	$_REQUEST['saiu21minutofin']='';
	$_REQUEST['saiu21paramercadeo']=0;
	$_REQUEST['saiu21idresponsable']=$idTercero;
	$_REQUEST['saiu21idresponsable_td']=$APP->tipo_doc;
	$_REQUEST['saiu21idresponsable_doc']='';
	$_REQUEST['saiu21tiemprespdias']='';
	$_REQUEST['saiu21tiempresphoras']='';
	$_REQUEST['saiu21tiemprespminutos']='';
	$_REQUEST['saiu21solucion']=0;
	$_REQUEST['saiu21idcaso']=0;
	$_REQUEST['saiu21respuesta']='';
	$_REQUEST['saiu21idorigen']=0;
	$_REQUEST['saiu21idarchivo']=0;
	$_REQUEST['saiu21idorigenrta']=0;
	$_REQUEST['saiu21idarchivorta']=0;
	$_REQUEST['saiu21fecharespcaso']='';
	$_REQUEST['saiu21horarespcaso']=0;
	$_REQUEST['saiu21minrespcaso']=0;
	$_REQUEST['saiu21idunidadcaso']=0;
	$_REQUEST['saiu21idequipocaso']=0;
	$_REQUEST['saiu21idsupervisorcaso']=0;
	$_REQUEST['saiu21idresponsablecaso']=0;
	$_REQUEST['saiu21numref']='';
	$_REQUEST['saiu21evalacepta']=0;
	$_REQUEST['saiu21evalfecha']='';
	$_REQUEST['saiu21evalamabilidad']=0;
	$_REQUEST['saiu21evalamabmotivo']='';
	$_REQUEST['saiu21evalrapidez']=0;
	$_REQUEST['saiu21evalrapidmotivo']='';
	$_REQUEST['saiu21evalclaridad']=0;
	$_REQUEST['saiu21evalcalridmotivo']='';
	$_REQUEST['saiu21evalresolvio']=0;
	$_REQUEST['saiu21evalsugerencias']='';
	$_REQUEST['saiu21evalconocimiento']=0;
	$_REQUEST['saiu21evalconocmotivo']='';
	$_REQUEST['saiu21evalutilidad']=0;
	$_REQUEST['saiu21evalutilmotivo']='';
	$_REQUEST['saiu21idresponsablecaso_td']=$APP->tipo_doc;
	$_REQUEST['saiu21idresponsablecaso_doc']='';
	$_REQUEST['paso'] = 0;
}
if ($bLimpiaHijos) {
}
//AQUI SE DEBEN CARGAR TODOS LOS DATOS QUE LA FORMA NECESITE.
$bPuedeAbrir = false;
$bPuedeEliminar = false;
$bPuedeGuardar = false;
$bPuedeCerrar = false;
$bHayImprimir = false;
$sScriptImprime = 'imprimeexcel()';
$sClaseImprime = 'iExcel';
$bEditable = $_REQUEST['saiu21estado'] == -1 || $_REQUEST['saiu21estado'] == 2;
$bPermisoSupv = $idTercero == $_REQUEST['saiu21idsupervisorcaso'];
$bPermisoResp = $idTercero == $_REQUEST['saiu21idresponsablecaso'];
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
$sTabla='saiu21directa_'.$iAgno;
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
list($bDevuelve, $sDebugP)=seg_revisa_permisoV3($iCodModulo, 6, $idTercero, $objDB);
if ($bDevuelve){$seg_6=1;}
if ($seg_6==1){$bHayImprimir=true;}
if ((int)$_REQUEST['paso'] == 0) {
	$bPuedeGuardar = true;
} else {
	switch ($_REQUEST['saiu21estado']) {
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
//DATOS PARA COMPLETAR EL FORMULARIO
$sNombreUsuario = '';
//Crear los controles que requieran llamado a base de datos
$objCombos = new clsHtmlCombos();
$objForma = new clsHtmlForma($iPiel);
$objTercero=new clsHtmlTercero();
if ($seg_1707 == 1) {
	$sSQL = 'SELECT unad11razonsocial FROM unad11terceros WHERE unad11id=' . $idTercero . '';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$sNombreUsuario = cadena_notildes($fila['unad11razonsocial']);
	}
	$objCombos->nuevo('deb_tipodoc', $_REQUEST['deb_tipodoc'], false);
	$objCombos->iAncho = 60;
	$html_deb_tipodoc = $objCombos->html('', $objDB, 145);
}
$iAgnoFin = fecha_agno();
list($saiu21estado_nombre, $sErrorDet)=tabla_campoxid('saiu11estadosol','saiu11nombre','saiu11id',$_REQUEST['saiu21estado'],'{'.$ETI['msg_sindato'].'}', $objDB);
$html_saiu21estado=html_oculto('saiu21estado', $_REQUEST['saiu21estado'], $saiu21estado_nombre);
list($saiu21idsolicitante_rs, $_REQUEST['saiu21idsolicitante'], $_REQUEST['saiu21idsolicitante_td'], $_REQUEST['saiu21idsolicitante_doc'])=html_tercero($_REQUEST['saiu21idsolicitante_td'], $_REQUEST['saiu21idsolicitante_doc'], $_REQUEST['saiu21idsolicitante'], 0, $objDB);
list($saiu21idresponsable_rs, $_REQUEST['saiu21idresponsable'], $_REQUEST['saiu21idresponsable_td'], $_REQUEST['saiu21idresponsable_doc'])=html_tercero($_REQUEST['saiu21idresponsable_td'], $_REQUEST['saiu21idresponsable_doc'], $_REQUEST['saiu21idresponsable'], 0, $objDB);
$saiu21idunidadcaso_nombre = '&nbsp;';
if ($_REQUEST['saiu21idunidadcaso'] != '') {
	if ((int)$_REQUEST['saiu21idunidadcaso'] == 0) {
		$saiu21idunidadcaso_nombre = '{' . $ETI['msg_sindato'] . '}';
	} else {
		list($saiu21idunidadcaso_nombre, $sErrorDet) = tabla_campoxid('unae26unidadesfun', 'unae26nombre', 'unae26id', $_REQUEST['saiu21idunidadcaso'], '{' . $ETI['msg_sindato'] . '}', $objDB);
	}
}
$html_saiu21idunidadcaso = html_oculto('saiu21idunidadcaso', $_REQUEST['saiu21idunidadcaso'], $saiu21idunidadcaso_nombre);
$saiu21idequipocaso_nombre = '&nbsp;';
if ($_REQUEST['saiu21idequipocaso'] != '') {
	if ((int)$_REQUEST['saiu21idequipocaso'] == 0) {
		$saiu21idequipocaso_nombre = '{' . $ETI['msg_sindato'] . '}';
	} else {
		list($saiu21idequipocaso_nombre, $sErrorDet) = tabla_campoxid('bita27equipotrabajo', 'bita27nombre', 'bita27id', $_REQUEST['saiu21idequipocaso'], '{' . $ETI['msg_sindato'] . '}', $objDB);
	}
}
$html_saiu21idequipocaso = html_oculto('saiu21idequipocaso', $_REQUEST['saiu21idequipocaso'], $saiu21idequipocaso_nombre);
$saiu21idsupervisorcaso_rs='&nbsp;';
$sSQL = 'SELECT T11.unad11razonsocial FROM saiu03temasol AS TB, unad11terceros AS T11 WHERE TB.saiu03idliderrespon1=T11.unad11id AND TB.saiu03id = ' . $_REQUEST['saiu21temasolicitud'] . ' AND TB.saiu03idliderrespon1 = ' . $_REQUEST['saiu21idsupervisorcaso'] . '';
$tabla = $objDB->ejecutasql($sSQL);
if ($fila = $objDB->sf($tabla)) {
	$saiu21idsupervisorcaso_rs=$fila['unad11razonsocial'];
} else {
	$saiu21idsupervisorcaso_rs = '{' . $ETI['msg_sindato'] . '}';
}
list($saiu21idresponsablecaso_rs, $_REQUEST['saiu21idresponsablecaso'], $_REQUEST['saiu21idresponsablecaso_td'], $_REQUEST['saiu21idresponsablecaso_doc']) = html_tercero($_REQUEST['saiu21idresponsablecaso_td'], $_REQUEST['saiu21idresponsablecaso_doc'], $_REQUEST['saiu21idresponsablecaso'], 0, $objDB);
if ($saiu21idresponsablecaso_rs == '') {
	$saiu21idresponsablecaso_rs = '{' . $ETI['msg_sindato'] . '}';
}
$html_saiu21idresponsablecasocombo = '<b>' . $saiu21idresponsablecaso_rs . '</b>';
if ($_REQUEST['saiu21estado'] < 7) {
	if ($idTercero == $_REQUEST['saiu21idsupervisorcaso'] || $seg_1707) {
		$objCombos->nuevo('saiu21idresponsablecasofin', $_REQUEST['saiu21idresponsablecaso'], true, '{' . $ETI['msg_seleccione'] . '}');
		$sSQL = 'SELECT TB.bita28idtercero AS id, T2.unad11razonsocial AS nombre
			FROM bita28eqipoparte AS TB, unad11terceros AS T2 
			WHERE  TB.bita28idequipotrab=' . $_REQUEST['saiu21idequipocaso'] . ' AND TB.bita28idtercero=T2.unad11id AND TB.bita28activo="S"
			ORDER BY T2.unad11razonsocial';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' Lista de responsables: '. $sSQL.'<br>ID RESPONSABLE: ' . $_REQUEST['saiu21idresponsablecaso'] .'<br>';
		}
		$html_saiu21idresponsablecasocombo = $objCombos->html($sSQL, $objDB);
	}
}
if ($bEditable || $bPermisoSupv) {
	$objCombos->nuevo('saiu21tipointeresado', $_REQUEST['saiu21tipointeresado'], true, '{'.$ETI['msg_seleccione'].'}');
	$sSQL='SELECT bita07id AS id, bita07nombre AS nombre FROM bita07tiposolicitante ORDER BY bita07orden, bita07nombre';
	$html_saiu21tipointeresado=$objCombos->html($sSQL, $objDB);
	$html_saiu21tiposolicitud=f3021_HTMLComboV2_saiu21tiposolicitud($objDB, $objCombos, $_REQUEST['saiu21tiposolicitud']);
	$html_saiu21temasolicitud=f3021_HTMLComboV2_saiu21temasolicitud($objDB, $objCombos, $_REQUEST['saiu21temasolicitud'], $_REQUEST['saiu21tiposolicitud']);
	$objCombos->nuevo('saiu21idzona', $_REQUEST['saiu21idzona'], true, '{'.$ETI['msg_seleccione'].'}');
	$objCombos->sAccion='carga_combo_saiu21idcentro();';
	$sSQL='SELECT unad23id AS id, unad23nombre AS nombre FROM unad23zona WHERE unad23conestudiantes="S" ORDER BY unad23nombre';
	$html_saiu21idzona=$objCombos->html($sSQL, $objDB);
	$html_saiu21idcentro=f3021_HTMLComboV2_saiu21idcentro($objDB, $objCombos, $_REQUEST['saiu21idcentro'], $_REQUEST['saiu21idzona']);
	$objCombos->nuevo('saiu21codpais', $_REQUEST['saiu21codpais'], true, '{'.$ETI['msg_seleccione'].'}');
	$objCombos->sAccion='carga_combo_saiu21coddepto();';
	$sSQL='SELECT unad18codigo AS id, unad18nombre AS nombre FROM unad18pais ORDER BY unad18nombre';
	$html_saiu21codpais=$objCombos->html($sSQL, $objDB);
	$html_saiu21coddepto=f3021_HTMLComboV2_saiu21coddepto($objDB, $objCombos, $_REQUEST['saiu21coddepto'], $_REQUEST['saiu21codpais']);
	$html_saiu21codciudad=f3021_HTMLComboV2_saiu21codciudad($objDB, $objCombos, $_REQUEST['saiu21codciudad'], $_REQUEST['saiu21coddepto']);
	$objCombos->nuevo('saiu21idescuela', $_REQUEST['saiu21idescuela'], true, '{'.$ETI['msg_seleccione'].'}');
	$objCombos->sAccion='carga_combo_saiu21idprograma();';
	$objCombos->addItem('0', $ETI['msg_na']);
	$sSQL='SELECT core12id AS id, core12nombre AS nombre FROM core12escuela WHERE core12id>0 AND core12tieneestudiantes="S" ORDER BY core12tieneestudiantes DESC, core12nombre';
	$html_saiu21idescuela=$objCombos->html($sSQL, $objDB);
	$html_saiu21idprograma=f3021_HTMLComboV2_saiu21idprograma($objDB, $objCombos, $_REQUEST['saiu21idprograma'], $_REQUEST['saiu21idescuela']);
	$objCombos->nuevo('saiu21idperiodo', $_REQUEST['saiu21idperiodo'], true, '{'.$ETI['msg_seleccione'].'}');
	$objCombos->addItem('0', $ETI['msg_na']);
	$sSQL=f146_ConsultaCombo('exte02id>0');
	$html_saiu21idperiodo=$objCombos->html($sSQL, $objDB);
	$objCombos->nuevo('saiu21paramercadeo', $_REQUEST['saiu21paramercadeo'], true, $ETI['no'], 0);
	$objCombos->addItem(1, $ETI['si']);
	//$objCombos->addArreglo($asaiu21paramercadeo, $isaiu21paramercadeo);
	$html_saiu21paramercadeo=$objCombos->html('', $objDB);
	$objCombos->nuevo('saiu21solucion', $_REQUEST['saiu21solucion'], true, $asaiu21solucion[0], 0);
	//$objCombos->addItem(1, $ETI['si']);
	$objCombos->sAccion='valida_combo_saiu21solucion();';
	$objCombos->addArreglo($asaiu21solucion, $isaiu21solucion);
	$html_saiu21solucion=$objCombos->html('', $objDB);
} else {
	list($saiu21tipointeresado_nombre, $sErrorDet) = tabla_campoxid('bita07tiposolicitante', 'bita07nombre', 'bita07id', $_REQUEST['saiu21tipointeresado'], '{' . $ETI['msg_sindato'] . '}', $objDB);
	$html_saiu21tipointeresado = html_oculto('saiu21tipointeresado', $_REQUEST['saiu21tipointeresado'], $saiu21tipointeresado_nombre);
	list($saiu21tiposolicitud_nombre, $sErrorDet) = tabla_campoxid('saiu02tiposol', 'saiu02titulo', 'saiu02id', $_REQUEST['saiu21tiposolicitud'], '{' . $ETI['msg_sindato'] . '}', $objDB);
	$html_saiu21tiposolicitud = html_oculto('saiu21tiposolicitud', $_REQUEST['saiu21tiposolicitud'], $saiu21tiposolicitud_nombre);
	list($saiu21temasolicitud_nombre, $sErrorDet) = tabla_campoxid('saiu03temasol', 'saiu03titulo', 'saiu03id', $_REQUEST['saiu21temasolicitud'], '{' . $ETI['msg_sindato'] . '}', $objDB);
	$html_saiu21temasolicitud = html_oculto('saiu21temasolicitud', $_REQUEST['saiu21temasolicitud'], $saiu21temasolicitud_nombre);
	list($saiu21idzona_nombre, $sErrorDet) = tabla_campoxid('unad23zona', 'unad23nombre', 'unad23id', $_REQUEST['saiu21idzona'], '{' . $ETI['msg_sindato'] . '}', $objDB);
	$html_saiu21idzona = html_oculto('saiu21idzona', $_REQUEST['saiu21idzona'], $saiu21idzona_nombre);
	list($saiu21idcentro_nombre, $sErrorDet) = tabla_campoxid('unad24sede', 'unad24nombre', 'unad24id', $_REQUEST['saiu21idcentro'], '{' . $ETI['msg_sindato'] . '}', $objDB);
	$html_saiu21idcentro = html_oculto('saiu21idcentro', $_REQUEST['saiu21idcentro'], $saiu21idcentro_nombre);
	list($saiu21codpais_nombre, $sErrorDet) = tabla_campoxid('unad18pais', 'unad18nombre', 'unad18codigo', $_REQUEST['saiu21codpais'], '{' . $ETI['msg_sindato'] . '}', $objDB);
	$html_saiu21codpais = html_oculto('saiu21codpais', $_REQUEST['saiu21codpais'], $saiu21codpais_nombre);
	list($saiu21coddepto_nombre, $sErrorDet) = tabla_campoxid('unad19depto', 'unad19nombre', 'unad19codigo', $_REQUEST['saiu21coddepto'], '{' . $ETI['msg_sindato'] . '}', $objDB);
	$html_saiu21coddepto = html_oculto('saiu21coddepto', $_REQUEST['saiu21coddepto'], $saiu21coddepto_nombre);
	list($saiu21codciudad_nombre, $sErrorDet) = tabla_campoxid('unad20ciudad', 'unad20nombre', 'unad20codigo', $_REQUEST['saiu21codciudad'], '{' . $ETI['msg_sindato'] . '}', $objDB);
	$html_saiu21codciudad = html_oculto('saiu21codciudad', $_REQUEST['saiu21codciudad'], $saiu21codciudad_nombre);
	list($saiu21idescuela_nombre, $sErrorDet) = tabla_campoxid('core12escuela', 'core12nombre', 'core12id', $_REQUEST['saiu21idescuela'], '{' . $ETI['msg_sindato'] . '}', $objDB);
	$html_saiu21idescuela = html_oculto('saiu21idescuela', $_REQUEST['saiu21idescuela'], $saiu21idescuela_nombre);
	list($saiu21idprograma_nombre, $sErrorDet) = tabla_campoxid('core09programa', 'core09nombre', 'core09id', $_REQUEST['saiu21idprograma'], '{' . $ETI['msg_sindato'] . '}', $objDB);
	$html_saiu21idprograma = html_oculto('saiu21idprograma', $_REQUEST['saiu21idprograma'], $saiu21idprograma_nombre);
	list($saiu21idperiodo_nombre, $sErrorDet) = tabla_campoxid('exte02per_aca', 'exte02nombre', 'exte02id', $_REQUEST['saiu21idperiodo'], '{' . $ETI['msg_sindato'] . '}', $objDB);
	$html_saiu21idperiodo = html_oculto('saiu21idperiodo', $_REQUEST['saiu21idperiodo'], $saiu21idperiodo_nombre);
	$html_saiu21solucion = html_oculto('saiu21solucion', $_REQUEST['saiu21solucion'], $asaiu21solucion[$_REQUEST['saiu21solucion']]);
}
if ((int)$_REQUEST['paso'] == 0) {
	$html_saiu21agno=f3021_HTMLComboV2_saiu21agno($objDB, $objCombos, $_REQUEST['saiu21agno']);
	$html_saiu21mes=f3021_HTMLComboV2_saiu21mes($objDB, $objCombos, $_REQUEST['saiu21mes']);
	$html_saiu21dia=html_ComboDia('saiu21dia', $_REQUEST['saiu21dia'], false);
	$html_saiu21tiporadicado=f3021_HTMLComboV2_saiu21tiporadicado($objDB, $objCombos, $_REQUEST['saiu21tiporadicado']);
} else {
	$saiu21agno_nombre=$_REQUEST['saiu21agno'];
	//$saiu21agno_nombre=$asaiu21agno[$_REQUEST['saiu21agno']];
	//list($saiu21agno_nombre, $sErrorDet)=tabla_campoxid('','','',$_REQUEST['saiu21agno'],'{'.$ETI['msg_sindato'].'}', $objDB);
	$html_saiu21agno=html_oculto('saiu21agno', $_REQUEST['saiu21agno'], $saiu21agno_nombre);
	$saiu21mes_nombre=strtoupper(fecha_mes_nombre((int)$_REQUEST['saiu21mes']));
	//$saiu21mes_nombre=$asaiu21mes[$_REQUEST['saiu21mes']];
	//list($saiu21mes_nombre, $sErrorDet)=tabla_campoxid('','','',$_REQUEST['saiu21mes'],'{'.$ETI['msg_sindato'].'}', $objDB);
	$html_saiu21mes=html_oculto('saiu21mes', $_REQUEST['saiu21mes'], $saiu21mes_nombre);
	$saiu21dia_nombre=$_REQUEST['saiu21dia'];
	$html_saiu21dia=html_oculto('saiu21dia', $_REQUEST['saiu21dia'], $saiu21dia_nombre);
	list($saiu21tiporadicado_nombre, $sErrorDet)=tabla_campoxid('saiu16tiporadicado','saiu16nombre','saiu16id',$_REQUEST['saiu21tiporadicado'],'{'.$ETI['msg_sindato'].'}', $objDB);
	$html_saiu21tiporadicado=html_oculto('saiu21tiporadicado', $_REQUEST['saiu21tiporadicado'], $saiu21tiporadicado_nombre);
}
$bEnProceso=true;
if ($_REQUEST['paso']!=0){
	if ($_REQUEST['saiu21estado']>6){$bEnProceso=false;}
	}
if (true){
	}else{
	list($saiu21tiposolicitud_nombre, $sErrorDet)=tabla_campoxid('saiu02tiposol','saiu02titulo','saiu02id',$_REQUEST['saiu21tiposolicitud'],'{'.$ETI['msg_sindato'].'}', $objDB);
	$html_saiu21tiposolicitud=html_oculto('saiu21tiposolicitud', $_REQUEST['saiu21tiposolicitud'], $saiu21tiposolicitud_nombre);
	}
//Alistar datos adicionales
$bPuedeAbrir=false;
if ($_REQUEST['paso']!=0){
	if ($_REQUEST['saiu21estado']>6){
		//Definir las condiciones que permitirán abrir el registro.
		list($bDevuelve, $sDebugP)=seg_revisa_permisoV3($iCodModulo, 17, $idTercero, $objDB);
		if ($bDevuelve){$bPuedeAbrir=true;}
		}else{
		}
	}
$id_rpt = 0;
//$id_rpt=reportes_id(_Identificador_Tipo_Reporte_, $objDB);
$objCombos->nuevo('bagno', $_REQUEST['bagno'], false, '{'.$ETI['msg_todos'].'}');
$objCombos->sAccion='paginarf3021()';
$sSQL='SHOW TABLES LIKE "saiu21directa%"';
$tablac=$objDB->ejecutasql($sSQL);
while($filac=$objDB->sf($tablac)){
	$sAgno=substr($filac[0], 14);
	$objCombos->addItem($sAgno, $sAgno);
}
$html_bagno=$objCombos->html('', $objDB);
$objCombos->nuevo('bestado', $_REQUEST['bestado'], true, '{' . $ETI['msg_todos'] . '}');
$objCombos->addItem('-1', 'Borrador');
$objCombos->addItem('0', 'Solicitado');
$objCombos->addItem('1', 'Asignado');
$objCombos->addItem('2', 'En tr&aacute;mite');
$objCombos->addItem('7', 'Resuelto');
$objCombos->sAccion = 'paginarf3021()';
$html_bestado = $objCombos->html('', $objDB);
$bTodos = false;
if ($seg_12 == 1) {
	$bTodos = true;
}
$objCombos->nuevo('blistar', $_REQUEST['blistar'], $bTodos, '{'.$ETI['msg_todos'].'}');
$objCombos->addItem('1', 'Mis registros');
$objCombos->addItem('2', 'Mis asignaciones');
$objCombos->addItem('3', 'Asignado a mi equipo');
$objCombos->sAccion='paginarf3021()';
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
//$html_blistar = $objCombos->comboSistema(3021, 1, $objDB, 'paginarf3021()');
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
$iModeloReporte = 3021;
$html_iFormatoImprime = '<input id="iformatoimprime" name="iformatoimprime" type="hidden" value="0" />';
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
	if ($_REQUEST['saiu21estado']>6){
		list($bDevuelve, $sDebugP)=seg_revisa_permisoV3($iCodModulo, 8, $idTercero, $objDB);
		if ($bDevuelve){$seg_8=1;}
		}
	}
//Cargar las tablas de datos
list($sErrorR, $sDebugR) = f3021_RevTabla_saiu21directa($_REQUEST['saiu21agno'], $objDB, $bDebug);
$sError = $sError . $sErrorR;
$sDebug = $sDebug . $sDebugR;
$aParametros[0] = ''; //$_REQUEST['p1_3021'];
$aParametros[100] = $idTercero;
$aParametros[101] = $_REQUEST['paginaf3021'];
$aParametros[102] = $_REQUEST['lppf3021'];
$aParametros[103]=$_REQUEST['bnombre'];
$aParametros[104]=$_REQUEST['bagno'];
$aParametros[105] = $_REQUEST['bestado'];
$aParametros[106] = $_REQUEST['blistar'];
$aParametros[107] = $_REQUEST['bdoc'];
$aParametros[108] = $_REQUEST['bcategoria'];
$aParametros[109] = $_REQUEST['btema'];
list($sTabla3021, $sDebugTabla) = f3021_TablaDetalleV2($aParametros, $objDB, $bDebug);
$sDebug = $sDebug . $sDebugTabla;
$sTabla3000='';
$aParametros3000[0]=$idTercero;
$aParametros3000[1]=$iCodModulo;
$aParametros3000[2]=$_REQUEST['saiu21agno'];
$aParametros3000[3]=$_REQUEST['saiu21id'];
$aParametros3000[100]=$_REQUEST['saiu21idsolicitante'];
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
$sDebug = $sDebug . $sDebugM;
$objDB->CerrarConexion();
//FORMA
switch ($iPiel) {
	case 2:
		require $APP->rutacomun . 'unad_forma2024.php';
		forma_InicioV4($xajax, $sTituloModulo);
		$aRutas = array(
			array('./', $sTituloApp),
			array('./' . $sPaginaModulo, $sGrupoModulo),
			array('', $sTituloModulo)
		);
		$iNumBoton = 0;
		$aBotones[$iNumBoton] = array('muestraayuda(' . $APP->idsistema . ', ' . $iCodModulo . ')', $ETI['bt_ayuda'], 'iHelp');
		$iNumBoton++;
		if ($bPuedeEliminar) {
			$aBotones[$iNumBoton] = array('eliminadato()', $ETI['bt_eliminar'], 'iDelete');
			$iNumBoton++;
		}
		if ($bHayImprimir) {
			$aBotones[$iNumBoton] = array($sScriptImprime, $ETI['bt_imprimir'], $sClaseImprime);
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
		$aBotones[$iNumBoton] = array('expandesector(1)', $ETI['bt_volver'], 'iArrowBack', 97);
		$iNumBoton++;
		forma_cabeceraV4b($aRutas, $aBotones, true, $iSector);
		echo $et_menu;
		forma_mitad($idTercero);
		break;
	default:
		require $APP->rutacomun . 'unad_forma_v2_2024.php';
		forma_cabeceraV3($xajax, $sTituloModulo);
		echo $et_menu;
		forma_mitad();
		break;
}
?>
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>css/css_tabs.css" type="text/css" />
<script language="javascript">
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
	let objdiv= document.getElementById('div_p'+codigo);
	let objban= document.getElementById('boculta'+codigo);
	let otroestado='none';
	if (estado=='none'){
		otroestado='block';
	}
	objdiv.style.display=estado;
	objban.value=valor;
	verboton('btrecoge'+codigo,estado);
	verboton('btexpande'+codigo,otroestado);
	}
function verboton(idboton,estado){
	let objbt=document.getElementById(idboton);
	objbt.style.display=estado;
	}
function expandesector(codigo) {
	document.getElementById('div_sector1').style.display = 'none';
	document.getElementById('div_sector2').style.display = 'none';
	document.getElementById('div_sector93').style.display='none';
	document.getElementById('div_sector95').style.display = 'none';
	document.getElementById('div_sector96').style.display = 'none';
	document.getElementById('div_sector97').style.display='none';
	document.getElementById('div_sector98').style.display = 'none';
	document.getElementById('div_sector' + codigo).style.display = 'block';
<?php
switch ($iPiel) {
	case 2:
?>
		document.getElementById('botones_sector1').style.display = 'none';
		document.getElementById('botones_sector97').style.display = 'none';
		switch (codigo) {
			case 1:
				document.getElementById('botones_sector1').style.display = 'flex';
				break;
			case 2:
				document.getElementById('botones_sector2').style.display = 'flex';
				break;
			case 97:
				document.getElementById('botones_sector1').style.display = 'none';
				document.getElementById('botones_sector' + codigo).style.display = 'flex';
				break;
			default:
				//document.getElementById('botones_sector1').style.display = 'none';
				break;
		}
		if (codigo == 1) {
			document.getElementById('nav').removeAttribute('disabled');
		} else {
			document.getElementById('nav').setAttribute('disabled', '');
		}
<?php
		break;
	default:
		if ($bPuedeGuardar && $bBloqueTitulo) {
?>
		let sEst = 'none';
		if (codigo == 1) {
			sEst = 'block';
		}
		if (window.document.frmedita.saiu21estado.value<7){
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
		let idcampo=window.document.frmedita.div96campo.value;
		let illave=window.document.frmedita.div96llave.value;
		let did=document.getElementById(idcampo);
		let dtd=document.getElementById(idcampo+'_td');
		let ddoc=document.getElementById(idcampo+'_doc');
		dtd.value=window.document.frmedita.div96v1.value;
		ddoc.value=sRetorna;
		did.value=window.document.frmedita.div96v3.value;
		ter_muestra(idcampo, illave);
		}
	MensajeAlarmaV2('', 0);
	retornacontrol();
	}
function ter_muestra(idcampo, illave){
	let saiu21idsolicitante=0;
	let params=new Array();
	params[1]=document.getElementById(idcampo+'_doc').value;
	if (params[1]!=''){
		params[0]=document.getElementById(idcampo+'_td').value;
		params[2]=idcampo;
		params[3]='div_'+idcampo;
		if (illave==1){params[4]='RevisaLlave';}
		//if (illave==1){params[5]='FuncionCuandoNoEsta';}
		if (idcampo=='saiu21idsolicitante'){
			params[6]=3021;
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
	saiu21idsolicitante = document.getElementById('saiu21idsolicitante').value;
	if (saiu21idsolicitante == 0) {
		document.getElementById('div_saiu21regsolicitante').style.display='none';
	} else {
		document.getElementById('div_saiu21regsolicitante').style.display='block';
	}
}
function ter_traerxid(idcampo, vrcampo){
	let params=new Array();
	params[0]=vrcampo;
	params[1]=idcampo;
	if (params[0]!=0){
		params[6]=3021;
		xajax_unad11_TraerXidSAI(params);
		}
	}
function imprimelista(){
	if (window.document.frmedita.seg_6.value==1){
		window.document.frmlista.consulta.value=window.document.frmedita.consulta_3021.value;
		window.document.frmlista.titulos.value=window.document.frmedita.titulos_3021.value;
		window.document.frmlista.nombrearchivo.value='Atencion presencial';
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
	let sError='';
	if (window.document.frmedita.seg_6.value!=1){sError="<?php echo $ERR['6']; ?>";}
	//if (sError==''){/*Agregar validaciones*/}
	if (sError==''){
		asignarvariables();
		window.document.frmimpp.action='e3021.php';
		window.document.frmimpp.submit();
		}else{
		ModalMensaje(sError);
		}
	}
function imprimep(){
	if (window.document.frmedita.seg_5.value==1){
		asignarvariables();
		window.document.frmimpp.action='p3021.php';
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
	let datos= new Array();
	datos[1]=window.document.frmedita.saiu21agno.value;
	datos[2]=window.document.frmedita.saiu21mes.value;
	datos[3]=window.document.frmedita.saiu21tiporadicado.value;
	datos[4]=window.document.frmedita.saiu21consec.value;
	if ((datos[1]!='')&&(datos[2]!='')&&(datos[3]!='')&&(datos[4]!='')){
		xajax_f3021_ExisteDato(datos);
		}
	}
function cargadato(llave1, llave2, llave3, llave4){
	window.document.frmedita.saiu21agno.value=String(llave1);
	window.document.frmedita.saiu21mes.value=String(llave2);
	window.document.frmedita.saiu21tiporadicado.value=String(llave3);
	window.document.frmedita.saiu21consec.value=String(llave4);
	window.document.frmedita.paso.value=1;
	window.document.frmedita.submit();
	}
function cargaridf3021(llave1, llave2){
	window.document.frmedita.saiu21agno.value=String(llave1);
	window.document.frmedita.saiu21id.value=String(llave2);
	window.document.frmedita.paso.value=3;
	window.document.frmedita.submit();
	}
function carga_combo_saiu21tiposolicitud(){
	let params=new Array();
	params[0]=window.document.frmedita.saiu21temasolicitud.value;
	document.getElementById('div_saiu21tiposolicitud').innerHTML='<b>Procesando datos, por favor espere...</b><input id="saiu21tiposolicitud" name="saiu21tiposolicitud" type="hidden" value="" />';
	document.getElementById('div_saiu21temasolicitud').innerHTML='<b>Procesando datos, por favor espere...</b><input id="saiu21temasolicitud" name="saiu21temasolicitud" type="hidden" value="" />';
	xajax_f3021_Combosaiu21tiposolicitud(params);
	}
function carga_combo_saiu21temasolicitud(){
	let params=new Array();
	params[0]=window.document.frmedita.saiu21tiposolicitud.value;
	document.getElementById('div_saiu21temasolicitud').innerHTML='<b>Procesando datos, por favor espere...</b><input id="saiu21temasolicitud" name="saiu21temasolicitud" type="hidden" value="" />';
	xajax_f3021_Combosaiu21temasolicitud(params);
	}
function carga_combo_saiu21idcentro(){
	let params=new Array();
	params[0]=window.document.frmedita.saiu21idzona.value;
	document.getElementById('div_saiu21idcentro').innerHTML='<b>Procesando datos, por favor espere...</b><input id="saiu21idcentro" name="saiu21idcentro" type="hidden" value="" />';
	xajax_f3021_Combosaiu21idcentro(params);
	}
function carga_combo_saiu21coddepto(){
	let params=new Array();
	params[0]=window.document.frmedita.saiu21codpais.value;
	document.getElementById('div_saiu21coddepto').innerHTML='<b>Procesando datos, por favor espere...</b><input id="saiu21coddepto" name="saiu21coddepto" type="hidden" value="" />';
	xajax_f3021_Combosaiu21coddepto(params);
	}
function carga_combo_saiu21codciudad(){
	let params=new Array();
	params[0]=window.document.frmedita.saiu21coddepto.value;
	document.getElementById('div_saiu21codciudad').innerHTML='<b>Procesando datos, por favor espere...</b><input id="saiu21codciudad" name="saiu21codciudad" type="hidden" value="" />';
	xajax_f3021_Combosaiu21codciudad(params);
	}
function carga_combo_saiu21idprograma(){
	let params=new Array();
	params[0]=window.document.frmedita.saiu21idescuela.value;
	document.getElementById('div_saiu21idprograma').innerHTML='<b>Procesando datos, por favor espere...</b><input id="saiu21idprograma" name="saiu21idprograma" type="hidden" value="" />';
	xajax_f3021_Combosaiu21idprograma(params);
	}
function paginarf3021() {
	let params = new Array();
	params[99]=window.document.frmedita.debug.value;
	params[100]=<?php echo $idTercero; ?>;
	params[101]=window.document.frmedita.paginaf3021.value;
	params[102]=window.document.frmedita.lppf3021.value;
	params[103]=window.document.frmedita.bnombre.value;
	params[104] = window.document.frmedita.bagno.value;
	params[105] = window.document.frmedita.bestado.value;
	params[106] = window.document.frmedita.blistar.value;
	params[107] = window.document.frmedita.bdoc.value;
	params[108] = window.document.frmedita.bcategoria.value;
	params[109] = window.document.frmedita.btema.value;
	document.getElementById('div_f3021detalle').innerHTML='<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf3021" name="paginaf3021" type="hidden" value="'+params[101]+'" /><input id="lppf3021" name="lppf3021" type="hidden" value="'+params[102]+'" />';
	xajax_f3021_HtmlTabla(params);
	}
function enviacerrar(){
	ModalMensajeV2('<?php echo $ETI['msg_cerrar']; ?>', () => {
		expandesector(98);
		window.document.frmedita.paso.value=16;
		window.document.frmedita.submit();
	}, {
		botonAceptar: 'Cerrar'
	});
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
	document.getElementById("saiu21agno").focus();
	}
function buscarV2016(sCampo){
	window.document.frmedita.iscroll.value=window.scrollY;
	expandesector(98);
	window.document.frmedita.scampobusca.value=sCampo;
	var params=new Array();
	params[1]=sCampo;
	//params[2]=window.document.frmedita.iagno.value;
	//params[3]=window.document.frmedita.itipo.value;
	xajax_f3021_Busquedas(params);
	}
function retornacontrol(){
	expandesector(1);
	window.scrollTo(0, window.document.frmedita.iscroll.value);
	}
function Devuelve(sValor){
	let sCampo=window.document.frmedita.scampobusca.value;
	if (sCampo=='saiu21idsolicitante'){
		ter_traerxid('saiu21idsolicitante', sValor);
		}
	if (sCampo=='saiu21idresponsable'){
		ter_traerxid('saiu21idresponsable', sValor);
		}
	retornacontrol();
	}
function mantener_sesion(){xajax_sesion_mantenerV4();}
setInterval ('xajax_sesion_abandona_V2();', 60000);
function AyudaLocal(sCampo){
	let divAyuda=document.getElementById('div_ayuda_'+sCampo);
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
	let sRetorna=window.document.frmedita.div96v2.value;
	if (ref == '3021') {
		if (sRetorna != '') {
			window.document.frmedita.saiu21idorigen.value = window.document.frmedita.div96v1.value;
			window.document.frmedita.saiu21idarchivo.value = sRetorna;
			verboton('beliminasaiu21idarchivo', 'block');
		}
		archivo_lnk(window.document.frmedita.saiu21idorigen.value, window.document.frmedita.saiu21idarchivo.value, 'div_saiu21idarchivo');
	}
	if (ref == '3021rta') {
		if (sRetorna != '') {
			window.document.frmedita.saiu21idorigenrta.value = window.document.frmedita.div96v1.value;
			window.document.frmedita.saiu21idarchivorta.value = sRetorna;
			verboton('beliminasaiu21idarchivorta', 'block');
		}
		archivo_lnk(window.document.frmedita.saiu21idorigenrta.value, window.document.frmedita.saiu21idarchivorta.value, 'div_saiu21idarchivorta');
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
	let params=new Array();
	params[0]=window.document.frmedita.id11.value;
	params[1]=3021;
	params[2]=window.document.frmedita.saiu21agno.value;
	params[3]=window.document.frmedita.saiu21id.value;
	params[99]=window.document.frmedita.debug.value;
	params[100]=window.document.frmedita.saiu21idsolicitante.value;
	params[101]=window.document.frmedita.paginaf3000.value;
	params[102]=window.document.frmedita.lppf3000.value;
	//params[103]=window.document.frmedita.bnombre3000.value;
	//params[104]=window.document.frmedita.blistar3000.value;
	document.getElementById('div_f3000detalle').innerHTML='<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf3000" name="paginaf3000" type="hidden" value="'+params[101]+'" /><input id="lppf3000" name="lppf3000" type="hidden" value="'+params[102]+'" />';
	xajax_f3000_HtmlTabla(params);
	}
function paginarf3000pqrs(){
	let params=new Array();
	params[0]=window.document.frmedita.id11.value;
	params[1]=3021;
	params[2]=window.document.frmedita.saiu21agno.value;
	params[3]=window.document.frmedita.saiu21id.value;
	params[99]=window.document.frmedita.debug.value;
	params[100]=window.document.frmedita.saiu21idsolicitante.value;
	params[101]=window.document.frmedita.paginaf3000pqrs.value;
	params[102]=window.document.frmedita.lppf3000pqrs.value;
	params[103]=window.document.frmedita.bagnopqrs.value;
	//params[104]=window.document.frmedita.blistar3000.value;
	document.getElementById('div_f3000pqrsdetalle').innerHTML='<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf3000pqrs" name="paginaf3000pqrs" type="hidden" value="'+params[101]+'" /><input id="lppf3000pqrs" name="lppf3000pqrs" type="hidden" value="'+params[102]+'" />';
	xajax_f3000pqrs_HtmlTabla(params);
	}
function valida_combo_saiu21solucion() {
	let iSolucion = parseInt(document.getElementById('saiu21solucion').value);
	let iEstado = parseInt(document.getElementById('saiu21estado').value);
	switch(iSolucion) {
		case 1:
		document.getElementById('div_saiu21respuesta').style.display='block';
		break;
		case 0:
		case 5:
		case 3:
		document.getElementById('div_saiu21respuesta').style.display='none';
		if (iEstado==1) {
			document.getElementById('div_saiu21respuesta').style.display='block';
		}
		break;		
		default:
		document.getElementById('div_saiu21respuesta').style.display='none';
		break;
	}
}
function actualizaratiende() {
	let sError = '';
	if (window.document.frmedita.saiu21id.value == '') {
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
if ($_REQUEST['saiu21estado'] < 7) {
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
	let i, tabcontent, tablinks;
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
// if ($_REQUEST['saiu21estado']==-1) {
if (true) {
?>
	function limpia_saiu21idarchivo(){
		window.document.frmedita.saiu21idorigen.value=0;
		window.document.frmedita.saiu21idarchivo.value=0;
		let da_Archivo=document.getElementById('div_saiu21idarchivo');
		da_Archivo.innerHTML='&nbsp;';
		verboton('beliminasaiu21idarchivo','none');
		//paginarf0000();
	}
	function carga_saiu21idarchivo(){
		window.document.frmedita.iscroll.value=window.pageYOffset;
		window.document.frmedita.div96v1.value='';
		window.document.frmedita.div96v2.value='';
		window.document.frmedita.div96v3.value='';
		let saiu21id=window.document.frmedita.saiu21id.value;
		let agno=window.document.frmedita.saiu21agno.value;
		document.getElementById('div_96titulo').innerHTML='<h2>'+window.document.frmedita.titulo_3021.value+' - Cargar archivo detalle</h2>';
		document.getElementById('div_96cuerpo').innerHTML='<iframe id="iframe96" src="framearchivodis.php?ref=3021&id='+saiu21id+'&tabla=_'+agno+'&tipo=0" height="400px" width="100%" frameborder="0"></iframe>';
		expandesector(96);
		window.scrollTo(0, 150);
	}
	function eliminasaiu21idarchivo(){
		let did=window.document.frmedita.saiu21id;
		let agno=window.document.frmedita.saiu21agno.value;
		if (confirm("Esta seguro de eliminar el archivo?")){
			xajax_elimina_archivo_saiu21idarchivo(did.value, agno);
			//paginarf0000();
		}
	}
<?php
}
?>
<?php
// if ($_REQUEST['saiu21estado']==-1) {
if (true) {
?>
	function limpia_saiu21idarchivorta(){
		window.document.frmedita.saiu21idorigenrta.value=0;
		window.document.frmedita.saiu21idarchivorta.value=0;
		let da_Archivo=document.getElementById('div_saiu21idarchivorta');
		da_Archivo.innerHTML='&nbsp;';
		verboton('beliminasaiu21idarchivorta','none');
		//paginarf0000();
	}
	function carga_saiu21idarchivorta(){
		window.document.frmedita.iscroll.value=window.pageYOffset;
		window.document.frmedita.div96v1.value='';
		window.document.frmedita.div96v2.value='';
		window.document.frmedita.div96v3.value='';
		let saiu21id=window.document.frmedita.saiu21id.value;
		let agno=window.document.frmedita.saiu21agno.value;
		document.getElementById('div_96titulo').innerHTML='<h2>'+window.document.frmedita.titulo_3021.value+' - Cargar archivo respuesta</h2>';
		document.getElementById('div_96cuerpo').innerHTML='<iframe id="iframe96" src="framearchivodis.php?ref=3021rta&id='+saiu21id+'&tabla=_'+agno+'&tipo=0" height="400px" width="100%" frameborder="0"></iframe>';
		expandesector(96);
		window.scrollTo(0, 150);
	}
	function eliminasaiu21idarchivorta(){
		let did=window.document.frmedita.saiu21id;
		let agno=window.document.frmedita.saiu21agno.value;
		if (confirm("Esta seguro de eliminar el archivo?")){
			xajax_elimina_archivo_saiu21idarchivorta(did.value, agno);
			//paginarf0000();
		}
	}
<?php
}
?>
function carga_combo_btema() {
	let params = new Array();
	params[0] = window.document.frmedita.bcategoria.value;
	document.getElementById('div_btema').innerHTML = '<b>Procesando datos, por favor espere...</b><input id="btema" name="btema" type="hidden" value="" />';
	xajax_f3021_Combobtema(params);
}
</script>
<?php
// if ($_REQUEST['paso']!=0){
?>
<form id="frmimpp" name="frmimpp" method="post" action="p3021.php" target="_blank" style="display:none;">
<input id="r" name="r" type="hidden" value="3021" />
<input id="id3021" name="id3021" type="hidden" value="<?php echo $_REQUEST['saiu21id']; ?>" />
<input id="v0" name="v0" type="hidden" value="" />
<input id="v3" name="v3" type="hidden" value="" />
<input id="v4" name="v4" type="hidden" value="" />
<input id="v5" name="v5" type="hidden" value="" />
<input id="iformato94" name="iformato94" type="hidden" value="0" />
<input id="separa" name="separa" type="hidden" value="," />
<input id="rdebug" name="rdebug" type="hidden" value="<?php echo $_REQUEST['debug']; ?>"/>
<input id="clave" name="clave" type="hidden" value="" />
</form>
<?php
// }
?>
<form id="frmlista" name="frmlista" method="post" action="listados.php" target="_blank" style="display:none;">
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
<input id="icodmodulo" name="icodmodulo" type="hidden" value="<?php echo $iCodModulo; ?>" />
<input id="seg_5" name="seg_5" type="hidden" value="<?php echo $seg_5; ?>" />
<input id="seg_6" name="seg_6" type="hidden" value="<?php echo $seg_6; ?>" />
<input id="vdtipointeresado" name="vdtipointeresado" type="hidden" value="<?php echo $_REQUEST['vdtipointeresado']; ?>" />
<input id="vdidtelefono" name="vdidtelefono" type="hidden" value="<?php echo $_REQUEST['vdidtelefono']; ?>" />
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
if ($bBloqueTitulo) {
?>
<div class="titulos">
<div class="titulosD">
<input id="cmdAyuda" name="cmdAyuda" type="button" class="btUpAyuda" onclick="muestraayuda(<?php echo $APP->idsistema . ', ' . $iCodModulo; ?>);" title="<?php echo $ETI['bt_ayuda']; ?>" value="<?php echo $ETI['bt_ayuda']; ?>" />
<?php
if ($_REQUEST['paso']==2){
	if ($_REQUEST['saiu21estado']<7 && $bPuedeEliminar){
?>
<input id="cmdEliminar" name="cmdEliminar" type="button" class="btUpEliminar" onclick="eliminadato();" title="<?php echo $ETI['bt_eliminar']; ?>" value="<?php echo $ETI['bt_eliminar']; ?>"/>
<?php
		}
	}
?>
<input id="cmdLimpiar" name="cmdLimpiar" type="button" class="btUpLimpiar" onclick="limpiapagina();" title="<?php echo $ETI['bt_limpiar']; ?>" value="<?php echo $ETI['bt_limpiar']; ?>"/>
<?php
if ($_REQUEST['saiu21estado']<7 && $bPuedeGuardar){
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
echo '<h2>' . $sTituloModulo . '</h2>';
?>
</div>
</div>
<?php
	//Termina el bloque titulo
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
echo $html_deb_tipodoc;
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
<label class="Label30">&nbsp;</label>
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
echo $objForma->htmlExpande(3021, $_REQUEST['boculta3021'], $ETI['bt_mostrar'], $ETI['bt_ocultar']);
$sEstiloDiv = '';
if ($_REQUEST['boculta3021'] != 0) {
	$sEstiloDiv = ' style="display:none;"';
}
?>
</div>
<div id="div_p3021"<?php echo $sEstiloDiv; ?>>
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
echo $html_saiu21dia;
?>
</label>
<label class="Label130">
<?php
echo $html_saiu21mes;
?>
</label>
<label class="Label90">
<?php
echo $html_saiu21agno;
?>
</label>
<?php
	}else{
?>
<label class="Label220">
<?php
echo $html_saiu21dia.'/'.$html_saiu21mes.'/'.$html_saiu21agno;
?>
</label>
<?php
	}
?>
<label class="Label60">
<?php
echo $ETI['saiu21hora'];
?>
</label>
<div class="campo_HoraMin" id="div_saiu21hora">
<?php
$bOculto=False;
if ($_REQUEST['paso']!=0){$bOculto=True;}
echo html_HoraMin('saiu21hora', $_REQUEST['saiu21hora'], 'saiu21minuto', $_REQUEST['saiu21minuto'], $bOculto);
?>
</div>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu21consec'];
?>
</label>
<label class="Label130">
<?php
if ($_REQUEST['paso']!=2){
?>
<input id="saiu21consec" name="saiu21consec" type="text" value="<?php echo $_REQUEST['saiu21consec']; ?>" onchange="RevisaLlave()" class="cuatro"/>
<?php
	}else{
	echo html_oculto('saiu21consec', $_REQUEST['saiu21consec'], formato_numero($_REQUEST['saiu21consec']));
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
echo $ETI['saiu21id'];
?>
</label>
<label class="Label60">
<?php
	echo html_oculto('saiu21id', $_REQUEST['saiu21id'], formato_numero($_REQUEST['saiu21id']));
?>
</label>
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
<input id="saiu21estadoorigen" name="saiu21estadoorigen" type="hidden" value="<?php echo $_REQUEST['saiu21estadoorigen']; ?>" />
</label>
<div class="salto1px"></div>
<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo $ETI['saiu21idsolicitante'];
?>
</label>
<div class="salto1px"></div>
<input id="saiu21idsolicitante" name="saiu21idsolicitante" type="hidden" value="<?php echo $_REQUEST['saiu21idsolicitante']; ?>"/>
<div id="div_saiu21idsolicitante_llaves">
<?php
$bOculto=!$bEnProceso;
echo html_DivTerceroV2('saiu21idsolicitante', $_REQUEST['saiu21idsolicitante_td'], $_REQUEST['saiu21idsolicitante_doc'], $bOculto, 0, $ETI['ing_doc']);
?>
</div>
<div class="salto1px"></div>
<div id="div_saiu21idsolicitante" class="L"><?php echo $saiu21idsolicitante_rs; ?></div>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['saiu21tipointeresado'];
?>
</label>
<label class="Label200">
<?php
echo $html_saiu21tipointeresado;
?>
</label>
<div class="salto1px"></div>
<?php
if ($_REQUEST['saiu21idsolicitante'] == 0) {
	$sEstiloDiv = ' style="display:block;"';
} else {
	$sEstiloDiv = ' style="display:none;"';
}
?>
<div id="div_saiu21regsolicitante" class="L" <?php echo $sEstiloDiv; ?>>
<input id="cmdRegSolicitante" name="cmdRegSolicitante" type="button" value="<?php echo $ETI['saiu21regsolicitante']; ?>" class="BotonAzul200" onclick="window.open('unadpersonas.php', '_blank');" title="<?php echo $ETI['saiu21regsolicitante']; ?>"/>
</div>
<div class="salto1px"></div>
</div>

<div class="GrupoCampos520">
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
<div class="salto1px"></div>
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
<div class="salto1px"></div>
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
<div class="salto1px"></div>
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
<div class="salto1px"></div>
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
echo $ETI['saiu21agno'];
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
<input id="saiu21clasesolicitud" name="saiu21clasesolicitud" type="hidden" value="<?php echo $_REQUEST['saiu21clasesolicitud']; ?>"/>
<label class="Label130">
<?php
echo $ETI['saiu21tiposolicitud'];
?>
</label>
<label>
<div id="div_saiu21tiposolicitud">
<?php
echo $html_saiu21tiposolicitud;
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu21temasolicitud'];
?>
</label>
<label>
<div id="div_saiu21temasolicitud">
<?php
echo $html_saiu21temasolicitud;
?>
</div>
<input id="saiu21temasolicitudorigen" name="saiu21temasolicitudorigen" type="hidden" value="<?php echo $_REQUEST['saiu21temasolicitudorigen']; ?>"/>
</label>
<div class="salto1px"></div>
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
<div class="salto1px"></div>
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
<div class="salto1px"></div>
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
<div class="salto1px"></div>
<div class="GrupoCampos520">
<label class="txtAreaS">
<?php
echo $ETI['saiu21detalle'];
?>
<?php
$sInactivo='readonly disabled';
if ($bEditable){
	$sInactivo='';
} else {
?>
<input name="saiu21detalle" type="hidden" value="<?php echo $_REQUEST['saiu21detalle']; ?>" />
<?php
}
?>
<textarea id="saiu21detalle" name="saiu21detalle" placeholder="<?php echo $ETI['ing_campo'].$ETI['saiu21detalle']; ?>" <?php echo $sInactivo; ?>><?php echo $_REQUEST['saiu21detalle']; ?></textarea>
</label>
</div>
<div class="GrupoCampos300">
<div class="salto1px"></div>
<input id="saiu21idorigen" name="saiu21idorigen" type="hidden" value="<?php echo $_REQUEST['saiu21idorigen']; ?>" />
<input id="saiu21idarchivo" name="saiu21idarchivo" type="hidden" value="<?php echo $_REQUEST['saiu21idarchivo']; ?>" />
<div id="div_saiu21idarchivo" class="Campo220">
<?php
echo html_lnkarchivo((int)$_REQUEST['saiu21idorigen'], (int)$_REQUEST['saiu21idarchivo']);
?>
</div>
<?php
if ($_REQUEST['saiu21estado']==2) {
?>
<label class="Label30">
<input type="button" id="banexasaiu21idarchivo" name="banexasaiu21idarchivo" value="Anexar" class="btAnexarS" onclick="carga_saiu21idarchivo()" title="Cargar archivo" style="display:<?php if ((int)$_REQUEST['saiu21id'] != 0) {
echo 'block';
} else {
echo 'none';
} ?>;" />
</label>
<label class="Label30">
<input type="button" id="beliminasaiu21idarchivo" name="beliminasaiu21idarchivo" value="Eliminar" class="btBorrarS" onclick="eliminasaiu21idarchivo()" title="Eliminar archivo" style="display:<?php if ((int)$_REQUEST['saiu21idarchivo'] != 0) {
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
if ($_REQUEST['saiu21estado']>0) {
	$sEstilo='display:none;';
	if ($_REQUEST['saiu21solucion']==1 || $_REQUEST['saiu21solucion']==3) {
		$sEstilo='display:block;';
	}
?>
<div id="div_saiu21respuesta" style="<?php echo $sEstilo; ?>">
<div class="GrupoCampos520">
<label class="txtAreaS">
<?php
echo $ETI['saiu21respuesta'];
?>
<?php
if ($_REQUEST['saiu21estado']==7) {
?>
<label class="Label220 ir_derecha">
<?php
if ($_REQUEST['saiu21solucion']==1) {
	$saiu21fecharesp = fecha_desdenumero($_REQUEST['saiu21fechafin']);
	$saiu21horaresp = html_HoraMin('saiu21horafin', $_REQUEST['saiu21horafin'], 'saiu21minutofin', $_REQUEST['saiu21minutofin'], true);
	echo $saiu21fecharesp . ' ' . $saiu21horaresp;
} else {
	$saiu21fecharespcaso = fecha_desdenumero($_REQUEST['saiu21fecharespcaso']);
	$saiu21horarespcaso = html_HoraMin('saiu21horarespcaso', $_REQUEST['saiu21horarespcaso'], 'saiu21minrespcaso', $_REQUEST['saiu21minrespcaso'], true);
	echo $saiu21fecharespcaso . ' ' . $saiu21horarespcaso;
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
<textarea id="saiu21respuesta" name="saiu21respuesta" placeholder="<?php echo $ETI['ing_campo'].$ETI['saiu21respuesta']; ?>" <?php echo $sInactivo; ?>><?php echo $_REQUEST['saiu21respuesta']; ?></textarea>
</label>
</div>
<div class="GrupoCampos300">
<div class="salto1px"></div>
<input id="saiu21idorigenrta" name="saiu21idorigenrta" type="hidden" value="<?php echo $_REQUEST['saiu21idorigenrta']; ?>" />
<input id="saiu21idarchivorta" name="saiu21idarchivorta" type="hidden" value="<?php echo $_REQUEST['saiu21idarchivorta']; ?>" />
<div id="div_saiu21idarchivorta" class="Campo220">
<?php
echo html_lnkarchivo((int)$_REQUEST['saiu21idorigenrta'], (int)$_REQUEST['saiu21idarchivorta']);
?>
</div>
<?php
if ($_REQUEST['saiu21estado']>0 && $bPuedeCerrar) {
?>
<label class="Label30">
<input type="button" id="banexasaiu21idarchivorta" name="banexasaiu21idarchivorta" value="Anexar" class="btAnexarS" onclick="carga_saiu21idarchivorta()" title="Cargar archivo" style="display:<?php if ((int)$_REQUEST['saiu21id'] != 0) {
echo 'block';
} else {
echo 'none';
} ?>;" />
</label>
<label class="Label30">
<input type="button" id="beliminasaiu21idarchivorta" name="beliminasaiu21idarchivorta" value="Eliminar" class="btBorrarS" onclick="eliminasaiu21idarchivorta()" title="Eliminar archivo" style="display:<?php if ((int)$_REQUEST['saiu21idarchivorta'] != 0) {
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
echo $ETI['saiu21solucion'];
?>
</label>
<label class="Label130">
<?php
echo $html_saiu21solucion;
?>
</label>
<div class="salto1px"></div>
<?php
if (false) {
?>
<label class="Label200">
<?php
echo $ETI['saiu21paramercadeo'];
?>
</label>
<label class="Label130">
<?php
echo $html_saiu21paramercadeo;
?>
</label>
<div class="salto1px"></div>
<?php
} else {
?>
<input id="saiu21paramercadeo" name="saiu21paramercadeo" type="hidden" value="<?php echo $_REQUEST['saiu21paramercadeo']; ?>"/>
<?php
}
?>
<label class="Label160">
<?php
echo $ETI['saiu21idcaso'];
?>
</label>
<label class="Label130">
<?php
echo html_oculto('saiu21idcaso', $_REQUEST['saiu21idcaso']);
?>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['saiu21idpqrs'];
?>
</label>
<label class="Label160">
<?php
$html_saiu21idpqrs_cod='0';
if ($_REQUEST['saiu21idpqrs']!=0) {
	$html_saiu21idpqrs_cod=f3000_NumSolicitud($_REQUEST['saiu21agno'], $_REQUEST['saiu21mes'], $_REQUEST['saiu21idpqrs']);
}
echo html_oculto('saiu21idpqrs', $_REQUEST['saiu21idpqrs'],$html_saiu21idpqrs_cod);
if ($_REQUEST['saiu21numref']!='') {
	echo '<br><b>Ref. de consulta: <a href="saiusolcitudes.php?saiu05origenagno='.$_REQUEST['saiu21agno'].'&saiu05origenmes='.$_REQUEST['saiu21mes'].'&saiu05id='.$_REQUEST['saiu21idpqrs'].'&paso=3" target="_blank">' . $_REQUEST['saiu21numref'] . '</a></b>';
}
?>
</label>
<div class="salto1px"></div>
<?php
if ($_REQUEST['paso']==2){
	if ($_REQUEST['saiu21estado']<7 && $bPuedeCerrar){
?>
<label class="Label130">
<input id="cmdTermina" name="cmdTermina" type="button" value="<?php echo $ETI['saiu21cerrar']; ?>" class="BotonAzul160" onclick="enviacerrar()" title="<?php echo $ETI['saiu21cerrar']; ?>"/>
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
echo $ETI['saiu21idresponsable'];
?>
</label>
<div class="salto1px"></div>
<input id="saiu21idresponsable" name="saiu21idresponsable" type="hidden" value="<?php echo $_REQUEST['saiu21idresponsable']; ?>"/>
<div id="div_saiu21idresponsable_llaves">
<?php
$bOculto=true;
echo html_DivTerceroV2('saiu21idresponsable', $_REQUEST['saiu21idresponsable_td'], $_REQUEST['saiu21idresponsable_doc'], $bOculto, 0, $ETI['ing_doc']);
?>
</div>
<div class="salto1px"></div>
<div id="div_saiu21idresponsable" class="L"><?php echo $saiu21idresponsable_rs; ?></div>
<div class="salto1px"></div>
<label class="Label90">
<?php
echo $ETI['saiu21horafin'];
?>
</label>
<div class="campo_HoraMin" id="div_saiu21horafin">
<?php
$bOculto=False;
if ($_REQUEST['paso']!=0){$bOculto=True;}
echo html_HoraMin('saiu21horafin', $_REQUEST['saiu21horafin'], 'saiu21minutofin', $_REQUEST['saiu21minutofin'], $bOculto);
?>
<input id="saiu21fechafin" name="saiu21fechafin" type="hidden" value="<?php echo $_REQUEST['saiu21fechafin']; ?>"/>
</div>
<?php
if ($_REQUEST['saiu21estado']==1 || $_REQUEST['saiu21estado']==7){
?>
<div class="salto1px"></div>
<label class="L">
<?php
echo $ETI['saiu21tiemprespdias'].' <b>'.Tiempo_HTML($_REQUEST['saiu21tiemprespdias'], $_REQUEST['saiu21tiempresphoras'], $_REQUEST['saiu21tiemprespminutos']).'</b>';
?>
</label>
<?php
	}
?>
<div class="salto1px"></div>
</div>
<input id="saiu21tiemprespdias" name="saiu21tiemprespdias" type="hidden" value="<?php echo $_REQUEST['saiu21tiemprespdias']; ?>"/>
<input id="saiu21tiempresphoras" name="saiu21tiempresphoras" type="hidden" value="<?php echo $_REQUEST['saiu21tiempresphoras']; ?>"/>
<input id="saiu21tiemprespminutos" name="saiu21tiemprespminutos" type="hidden" value="<?php echo $_REQUEST['saiu21tiemprespminutos']; ?>"/>
<div class="salto1px"></div>
<?php
// Inicio caja - responsable caso
$sEstilo = ' style="display:none"';
if ((int)$_REQUEST['paso'] != 0) {
	if ($_REQUEST['saiu21solucion'] == 3) {
		$sEstilo = ' style="display:block"';
	}
}
?>
<div class="GrupoCampos520" <?php echo $sEstilo; ?>>
<label class="TituloGrupo">
<?php
echo $ETI['saiu21atiendecaso'];
?>
<?php
if ($_REQUEST['saiu21estado'] < 7) {
	if ($bPermisoSupv) {
?>
<div class="ir_derecha" style="width:62px;">
<input id="bRevAtiende" name="bRevAtiende" type="button" value="<?php echo $ETI['saiu21actatiendecaso']; ?>" class="btMiniActualizar" onclick="actualizaratiende()" title="<?php echo $ETI['saiu21actatiendecaso']; ?>" style="display:<?php if ((int)$_REQUEST['saiu21id'] != 0) {
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
<input id="saiu21idsupervisorcaso" name="saiu21idsupervisorcaso" type="hidden" value="<?php echo $_REQUEST['saiu21idsupervisorcaso']; ?>" />
<input id="saiu21idresponsablecaso" name="saiu21idresponsablecaso" type="hidden" value="<?php echo $_REQUEST['saiu21idresponsablecaso']; ?>" />
<label class="Label160">
<?php echo $ETI['saiu21idunidadcaso']; ?>
</label>
<label class="Label200">
<b><?php echo $html_saiu21idunidadcaso; ?></b>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php echo $ETI['saiu21idequipocaso']; ?>
</label>
<label class="Label200">
<b><?php echo $html_saiu21idequipocaso; ?></b>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php echo $ETI['saiu21idsupervisorcaso']; ?>
</label>
<label class="Label200">
<b><?php echo $saiu21idsupervisorcaso_rs; ?></b>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php echo $ETI['saiu21idresponsablecaso']; ?>
</label>
<label id="lbl_f3021CambioResponsable" class="Label200">
<b><?php echo $saiu21idresponsablecaso_rs; ?></b>
</label>
<div class="salto1px"></div>
<?php
if ($_REQUEST['saiu21estado'] < 7) {
	if ($bPermisoSupv) {
?>
<label class="Label160">
<input id="cmdReasignar" name="cmdReasignar" type="button" class="BotonAzul200" value="<?php echo $ETI['saiu21reasignacaso']; ?>" onclick="expandesector(2);" title="<?php echo $ETI['saiu21reasignacaso']; ?>" />
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
<input id="bdoc" name="bdoc" type="text" value="<?php echo $_REQUEST['bdoc']; ?>" onchange="paginarf3021()" autocomplete="off" />
</label>
<label class="Label90">
<?php
echo $ETI['msg_bnombre'];
?>
</label>
<label class="Label200">
<input id="bnombre" name="bnombre" type="text" value="<?php echo $_REQUEST['bnombre']; ?>" onchange="paginarf3021()" autocomplete="off" />
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu21tiposolicitud'];
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
echo $ETI['saiu21temasolicitud'];
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
echo $ETI['saiu21estado'];
?>
</label>
<label class="Label130">
<?php
echo $html_bestado;
?>
</label>
<label class="Label60">
<?php
echo $ETI['saiu21agno'];
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
<div id="div_f3021detalle">
<?php
echo $sTabla3021;
?>
</div>
</div><!-- /div_areatrabajo -->
</div><!-- /DIV_areaform -->
</div>


<div id="div_sector2" style="display:none">
<?php
// if ($bBloqueTitulo) {
?>
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
<?php
// }
?>
<div class="areaform">
<div class="areatrabajo">
<?php
if ($_REQUEST['saiu21estado'] < 7) {
if ($idTercero == $_REQUEST['saiu21idsupervisorcaso'] || $seg_1707) {
	$sEstiloDiv = ' style="display:block;"';
} else {
	$sEstiloDiv = ' style="display:none;"';
}
}
?>
<div class="GrupoCampos520" <?php echo $sEstiloDiv; ?>>
<label class="TituloGrupo">
<?php
echo $ETI['saiu21atiendecaso'];
?>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php echo $ETI['saiu21idunidadcaso']; ?>
</label>
<label class="Label200">
<b><?php echo $html_saiu21idunidadcaso; ?></b>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php echo $ETI['saiu21idequipocaso']; ?>
</label>
<label class="Label200">
<b><?php echo $html_saiu21idequipocaso; ?></b>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php echo $ETI['saiu21idsupervisorcaso']; ?>
</label>
<label class="Label200">
<b><?php echo $saiu21idsupervisorcaso_rs; ?></b>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php echo $ETI['saiu21idresponsablecaso']; ?>
</label>
<label class="Label200">
<div id="div_saiu21idresponsablecaso">
<?php 
echo $html_saiu21idresponsablecasocombo; 
?>
</div>
</label>
<?php
if ($_REQUEST['saiu21estado'] < 7) {
if ($idTercero == $_REQUEST['saiu21idsupervisorcaso'] || $seg_1707) {
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
echo $ETI['msg_saiu21consec'];
?>
</label>
<label class="Label90">
<?php
echo '<b>'.$_REQUEST['saiu21consec'].'</b>';
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
<input id="saiu21consec_nuevo" name="saiu21consec_nuevo" type="text" value="<?php echo $_REQUEST['saiu21consec_nuevo']; ?>" class="cuatro"/>
</label>
<div class="salto1px"></div>
<label class="Label160">&nbsp;</label>
<?php
echo $objForma->htmlBotonSolo('cmdCambiaConsecFinal', 'botonProceso', 'mod_consec();', $ETI['bt_cambiar'], 130);
echo $objForma->htmlFinMarco();
?>
</div><!-- /DIV_Sector93 -->
<div id="div_sector95" style="display:none">
<div class="areaform">
<div id="div_95cuerpo"></div>
</div>
</div>


<div id="div_sector96" style="display:none">
<input id="div96v1" name="div96v1" type="hidden" value="" />
<input id="div96v2" name="div96v2" type="hidden" value="" />
<input id="div96v3" name="div96v3" type="hidden" value="" />
<input id="div96campo" name="div96campo" type="hidden" value="" />
<input id="div96llave" name="div96llave" type="hidden" value="" />
<input id="titulo_3021" name="titulo_3021" type="hidden" value="<?php echo $sTituloModulo; ?>" />
<?php
if ($bBloqueTitulo) {
?>
<div class="titulos">
<div class="titulosD">
<input id="cmdAyuda96" name="cmdAyuda96" type="button" class="btSupAyuda" onclick="muestraayuda(<?php echo $iCodModulo; ?>);" title="<?php echo $ETI['bt_ayuda']; ?>" value="<?php echo $ETI['bt_ayuda']; ?>" />
</div>
<div class="titulosI" id="div_96titulo"></div>
</div>
<?php
} else {
?>
<div id="div_96titulo" style="display:none"></div>
<?php
}
?>
<div class="areaform">
<div id="div_96cuerpo"></div>
</div>
</div>

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
if ($bBloqueTitulo) {
?>
<div class="titulos">
<div class="titulosD">
<input id="cmdAyuda98" name="cmdAyuda98" type="button" class="btSupAyuda" onclick="muestraayuda(<?php echo $iCodModulo; ?>);" title="<?php echo $ETI['bt_ayuda']; ?>" value="<?php echo $ETI['bt_ayuda']; ?>" />
</div>
<div class="titulosI">
<?php
echo '<h2>' . $sTituloModulo . '</h2>';
?>
</div>
</div>
<?php
}
?>
<div class="areaform">
<div class="areatrabajo">
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
<?php
if ($bBloqueTitulo) {
	if ($bPuedeGuardar) {
		if ($_REQUEST['saiu21estado']<7) {
?>
<div class="flotante">
<input id="cmdGuardarf" name="cmdGuardarf" type="button" class="btSoloGuardar" onClick="enviaguardar();" value="<?php echo $ETI['bt_guardar']; ?>" />
</div>
<?php
		}
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
$("#saiu21idcentro").chosen({width:"100%"});
$("#saiu21coddepto").chosen({width:"100%"});
$("#saiu21codciudad").chosen({width:"100%"});
<?php
if ($bEnProceso){
?>
$("#saiu21tiposolicitud").chosen({width:"100%"});
<?php
	}
?>
$("#saiu21temasolicitud").chosen({width:"100%"});
$("#saiu21idprograma").chosen({width:"100%"});
$("#saiu21idperiodo").chosen({width:"100%"});
<?php
}
?>
$("#bcategoria").chosen({width:"100%"});
$("#btema").chosen({width:"100%"});
});
</script>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>jsi/js3000.js"></script>
<script language="javascript" src="ac_3021.js"></script>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>unad_todas2024v2.js"></script>
<?php
forma_piedepagina();

