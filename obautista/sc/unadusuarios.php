<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2014 - 2024 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- © Juan David Avellaneda Molina - UNAD - 2025 ---
--- juand.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Version 0.4.0 viernes, 07 de febrero de 2014
--- Modelo Version 1.0.0 jueves, 03 de abril de 2014
--- Modelo Versión 2.7.9 domingo, 07 de junio de 2015
--- Modelo Versión 2.9.7 lunes, 23 de noviembre de 2015
--- Modelo Versión 2.18.1 viernes, 26 de mayo de 2017 
--- Modelo Versión 2.22.3 miércoles, 15 de agosto de 2018
--- Modelo Versión 2.24.0 Friday, January 10, 2020
--- Modelo Versión 2.25.6 viernes, 11 de septiembre de 2020
--- Modelo Versión 3.0.9 lunes, 8 de julio de 2024
--- Modelo Versión 3.0.16 viernes, 22 de agosto de 2025
*/
/** Archivo unadusuarios.php.
 * Modulo 107 unad07usuarios.
 * @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
 * @param debug = 1  (Opcional), bandera para indicar si se generan datos de depuración
 * @date lunes, 8 de julio de 2024
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
require $APP->rutacomun . 'libaurea.php';
require $APP->rutacomun . 'libcomp.php';
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
$iMinVerDB = 7661;
$iCodModulo = 107;
$iCodModuloConsulta = $iCodModulo;
switch ($APP->idsistema) {
	case 5: // Presupuesto
		$iCodModuloConsulta = 592;
		break;
	case 11: // Contabilidad
		$iCodModuloConsulta = 1192;
		break;
	case 17: // OAI
		$iCodModuloConsulta = 1792;
		break;
	case 21: //Laboratorios
		$iCodModuloConsulta = 2192;
		break;
	case 22: //Core
		$iCodModuloConsulta = 2292;
		break;
	case 23: // SC - Consejeria
		$iCodModuloConsulta = 2316;
		break;
	case 27: // Grados
		$iCodModuloConsulta = 2792;
		break;
	case 28: // Analitica
		$iCodModuloConsulta = 2892;
		break;
	case 29: // SAE
		$iCodModuloConsulta = 2992;
		break;
	case 30: // SAI
		$iCodModuloConsulta = 3092;
		break;
	case 34: // Biblioteca
		$iCodModuloConsulta = 3492;
		break;
	case 36: //SIGMA
		$iCodModuloConsulta = 3692;
		break;
	case 37: // SIGTools
		$iCodModuloConsulta = 3792;
		break;
	case 41: // Contratación
		$iCodModuloConsulta = 4192;
		break;
	case 49: // SINEP
		$iCodModuloConsulta = 4992;
		break;
	case 50: // MUMO17
		$iCodModuloConsulta = 5092;
		break;
	case 51: // GRAP
		$iCodModuloConsulta = 5120;
		break;	
}
$sIdioma = AUREA_Idioma();
$audita[1] = false;
$audita[2] = true;
$audita[3] = true;
$audita[4] = true;
$audita[5] = false;
// -- Se cargan los archivos de idioma
$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
if (!file_exists($mensajes_todas)) {
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
}
$mensajes_107 = $APP->rutacomun . 'lg/lg_107_' . $sIdioma . '.php';
if (!file_exists($mensajes_107)) {
	$mensajes_107 = $APP->rutacomun . 'lg/lg_107_es.php';
}
require $mensajes_todas;
require $mensajes_107;
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
$sTituloModulo = $ETI['titulo_107'];
switch ($iPiel) {
	case 2:
		$sAnchoExpandeContrae = '';
		$bBloqueTitulo = false;
		break;
}
// --- Final de las variables para la forma
if ($bDebug) {
	$sDebug = $sDebug . log_debug('Probando conexi&oacute;n con la base de datos <b>' . $APP->dbname . '</b> en <b>' . $APP->dbhost . '</b>');
}
$bCargaMenu = true;
if (!$objDB->Conectar()) {
	$bCargaMenu = false;
	$bCerrado = true;
	$sMsgCierre = '<div class="MarquesinaGrande">Disculpe las molestias estamos en este momento nuestros servicios no estas disponibles.<br>Por favor intente acceder mas tarde.<br>Si el problema persiste por favor informe al administrador del sistema.</div>';
	if ($bDebug) {
		$sDebug = $sDebug . log_debug('Error al intentar conectar con la base de datos <b>' . $objDB->serror . '</b>');
	}
}
if (!$bCerrado) {
	$iVerDB = version_upd($objDB);
	if ($iMinVerDB > $iVerDB) {
		$bCerrado = true;
		$sMsgCierre = '<div class="MarquesinaGrande">La base de datos se encuentra desactualizada para este modulo.<br>Por favor informe al administrador del sistema.</div>';
		if ($bDebug) {
			$sDebug = $sDebug . log_debug('<b>DB DESACTUALIZADA [Requerida:' . $iMinVerDB . ' - Encontrada:' . $iVerDB . ']</b>');
		}
	} else {
		if ($bDebug) {
			$sDebug = $sDebug . log_debug('Versi&oacute;n DB <b>' . $iVerDB . '</b> [Requerida:' . $iMinVerDB . ']');
		}
	}
}
if (!$bCerrado) {
	list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModuloConsulta, 1, $idTercero, $objDB);
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
		$objForma->addBoton('cmdAyuda98', 'btSupAyuda', 'muestraayuda(' . $iCodModulo . ');', $ETI['bt_ayuda']);
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
		header('Location:noticia.php?ret=unadusuarios.php');
		die();
	}
}
$seg_1707 = 0;
$bDevuelve = false;
//list($bDevuelve, $sDebugP, $seg_1707) = seg_revisa_permisoV3($iCodModulo, 1707, $_SESSION['unad_id_tercero'], $objDB, $bDebug);
//$sDebug = $sDebug . $sDebugP;
if (isset($_REQUEST['deb_tipodoc']) == 0) {
	$_REQUEST['deb_tipodoc'] = $APP->tipo_doc;
}
$_REQUEST['deb_tipodoc'] = cadena_Validar($_REQUEST['deb_tipodoc']);
$_REQUEST['deb_doc'] = cadena_Validar($_REQUEST['deb_doc']);
if ($_REQUEST['deb_doc'] != '') {
	if ($seg_1707 == 1) {
		$sSQL = 'SELECT unad11id, unad11razonsocial FROM unad11terceros WHERE unad11doc="' . $_REQUEST['deb_doc'] . '" AND unad11tipodoc="' . $_REQUEST['deb_tipodoc'] . '"';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			$idTercero = $fila['unad11id'];
			$bOtroUsuario = true;
			if ($bDebug) {
				$sDebug = $sDebug . log_debug('Se verifica la ventana de trabajo para el usuario ' . $fila['unad11razonsocial'] . '.');
			}
		} else {
			$sError = 'No se ha encontrado el documento &quot;' . $_REQUEST['deb_tipodoc'] . ' ' . $_REQUEST['deb_doc'] . '&quot;';
			$_REQUEST['deb_doc'] = '';
		}
	} else {
		if ($bDebug) {
			$sDebug = $sDebug . log_debug('No cuenta con permiso de ingreso como otro usuario [Modulo ' . $iCodModulo . ' Permiso 1707]');
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
//$idEntidad = Traer_Entidad();
// -- Si esta cargando la pagina por primer vez se revisa si requiere auditar y se manda a hacer un limpiar (paso -1)
if (isset($_REQUEST['paso']) == 0) {
	$_REQUEST['paso'] = -1;
	if ($audita[1]) {
		seg_auditaingreso($iCodModulo, $_SESSION['unad_id_tercero'], $objDB);
	}
}
// -- 107 unad07usuarios
require $APP->rutacomun . 'lib107.php';
$xajax = new xajax();
$xajax->configure('javascript URI', $APP->rutacomun . 'xajax/');
$xajax->register(XAJAX_FUNCTION, 'unad11_Mostrar_v2');
$xajax->register(XAJAX_FUNCTION, 'unad11_TraerXid');
$xajax->register(XAJAX_FUNCTION, 'sesion_abandona_V2');
$xajax->register(XAJAX_FUNCTION, 'sesion_mantenerV4');
$xajax->register(XAJAX_FUNCTION, 'f107_HtmlTabla');
$xajax->register(XAJAX_FUNCTION, 'f107_ExisteDato');
$xajax->register(XAJAX_FUNCTION, 'f107_Busquedas');
$xajax->register(XAJAX_FUNCTION, 'f107_HtmlBusqueda');
$xajax->register(XAJAX_FUNCTION, 'frevisa_HtmlTabla');
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
if (isset($_REQUEST['iscroll']) == 0) {
	$_REQUEST['iscroll'] = 0;
}
if (isset($_REQUEST['paginaf107']) == 0) {
	$_REQUEST['paginaf107'] = 1;
}
if (isset($_REQUEST['lppf107']) == 0) {
	$_REQUEST['lppf107'] = 20;
}
if (isset($_REQUEST['boculta107']) == 0) {
	$_REQUEST['boculta107'] = 1;
}
if (isset($_REQUEST['boculta99']) == 0) {
	$_REQUEST['boculta99'] = 1;
}
if (isset($_REQUEST['paginaf108']) == 0) {
	$_REQUEST['paginaf108'] = 1;
}
if (isset($_REQUEST['lppf108']) == 0) {
	$_REQUEST['lppf108'] = 20;
}
// -- Inicializar variables de datos.
if (isset($_REQUEST['unad07idperfil']) == 0) {
	$_REQUEST['unad07idperfil'] = '';
}
if (isset($_REQUEST['unad07idtercero']) == 0) {
	$_REQUEST['unad07idtercero'] = 0;
}
if (isset($_REQUEST['unad07idtercero_td']) == 0) {
	$_REQUEST['unad07idtercero_td'] = $APP->tipo_doc;
}
if (isset($_REQUEST['unad07idtercero_doc']) == 0) {
	$_REQUEST['unad07idtercero_doc'] = '';
}
if (isset($_REQUEST['unad07vigente']) == 0) {
	$_REQUEST['unad07vigente'] = 'S';
}
if (isset($_REQUEST['unad07fechavence']) == 0) {
	$_REQUEST['unad07fechavence'] = '';
}
$_REQUEST['unad07idperfil'] = numeros_validar($_REQUEST['unad07idperfil']);
$_REQUEST['unad07idtercero'] = numeros_validar($_REQUEST['unad07idtercero']);
$_REQUEST['unad07idtercero_td'] = cadena_Validar($_REQUEST['unad07idtercero_td']);
$_REQUEST['unad07idtercero_doc'] = cadena_Validar($_REQUEST['unad07idtercero_doc']);
$_REQUEST['unad07vigente'] = cadena_Validar($_REQUEST['unad07vigente']);
$_REQUEST['unad07fechavence'] = cadena_Validar($_REQUEST['unad07fechavence'], true);
// Espacio para inicializar otras variables
if (isset($_REQUEST['csv_separa']) == 0) {
	$_REQUEST['csv_separa'] = ';';
}
if (isset($_REQUEST['brevdocumento']) == 0) {
	$_REQUEST['brevdocumento'] = '';
}
if (isset($_REQUEST['brevperfil']) == 0) {
	$_REQUEST['brevperfil'] = '';
}
if (isset($_REQUEST['brevmodulo']) == 0) {
	$_REQUEST['brevmodulo'] = '';
}
if (isset($_REQUEST['brevpermiso']) == 0) {
	$_REQUEST['brevpermiso'] = '';
}
if (isset($_REQUEST['bdocumento']) == 0) {
	$_REQUEST['bdocumento'] = '';
}
if (isset($_REQUEST['brazonsocial']) == 0) {
	$_REQUEST['brazonsocial'] = '';
}
if (isset($_REQUEST['masidperfil']) == 0) {
	$_REQUEST['masidperfil'] = '';
}
if (isset($_REQUEST['bperfil']) == 0) {
	$_REQUEST['bperfil'] = '';
}
if (isset($_REQUEST['blistar']) == 0) {
	$_REQUEST['blistar'] = '';
}
//Si Modifica o Elimina Cargar los campos
if (($_REQUEST['paso'] == 1) || ($_REQUEST['paso'] == 3)) {
	$_REQUEST['unad07idtercero_td'] = $APP->tipo_doc;
	$_REQUEST['unad07idtercero_doc'] = '';
	$sSQL = 'SELECT * FROM unad07usuarios WHERE unad07idperfil=' . $_REQUEST['unad07idperfil'] . ' AND unad07idtercero="' . $_REQUEST['unad07idtercero'] . '"';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$_REQUEST['unad07idperfil'] = $fila['unad07idperfil'];
		$_REQUEST['unad07idtercero'] = $fila['unad07idtercero'];
		$_REQUEST['unad07vigente'] = $fila['unad07vigente'];
		$_REQUEST['unad07fechavence'] = $fila['unad07fechavence'];
		$bcargo = true;
		$_REQUEST['paso'] = 2;
		$_REQUEST['boculta107'] = 0;
		$bLimpiaHijos = true;
	} else {
		$_REQUEST['paso'] = 0;
	}
}
//Insertar o modificar un elemento
if (($_REQUEST['paso'] == 10) || ($_REQUEST['paso'] == 12)) {
	$bMueveScroll = true;
	list($_REQUEST, $sError, $iTipoError, $sDebugGuardar) = f107_db_GuardarV2($_REQUEST, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugGuardar;
	if ($sError == '') {
		$sError = '<b>' . $ETI['msg_itemguardado'] . '</b>';
		$iTipoError = 1;
	}
}
//Eliminar un elemento
if ($_REQUEST['paso'] == 13) {
	$_REQUEST['paso'] = 2;
	$_REQUEST['unad07idperfil'] = numeros_validar($_REQUEST['unad07idperfil']);
	if ($sError == '') {
		$bDevuelve = false;
		list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModuloConsulta, 4, $idTercero, $objDB);
		if ($bDevuelve) {
			$sError = $ERR['4'];
		}
	}
	if ($sError == '') {
		$sWhere = 'unad07idperfil=' . $_REQUEST['unad07idperfil'] . ' AND unad07idtercero="' . $_REQUEST['unad07idtercero'] . '"';
		$sSQL = 'DELETE FROM unad07usuarios WHERE ' . $sWhere . ';';
		$result = $objDB->ejecutasql($sSQL);
		if ($result == false) {
			$sError = $ERR['falla_eliminar'] . ' .. <!-- ' . $sSQL . ' -->';
		} else {
			if ($audita[4]) {
				seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, 0, $sWhere, $objDB);
			}
			$_REQUEST['paso'] = -1;
			$sError = $ETI['msg_itemeliminado'];
			$iTipoError = 1;
		}
	}
}
if (($_REQUEST['paso'] == 50)) {
	$_REQUEST['paso'] = 2;
	if (!seg_revisa_permiso($iCodModulo, 2, $objDB)) {
		$sError = $ERR['2'];
	}
	if ($sError == '') {
		list($sError, $iTipoError, $sDebugP) = f107_ProcesarArchivo($_REQUEST, $_FILES, $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugP;
	}
}
//limpiar la pantalla
if ($_REQUEST['paso'] == -1) {
	$_REQUEST['unad07idperfil'] = '';
	$_REQUEST['unad07idtercero'] = 0; //$idTercero;
	$_REQUEST['unad07idtercero_td'] = $APP->tipo_doc;
	$_REQUEST['unad07idtercero_doc'] = '';
	$_REQUEST['unad07vigente'] = 'S';
	$_REQUEST['unad07fechavence'] = ''; //fecha_hoy();
	$_REQUEST['paso'] = 0;
}
if ($bLimpiaHijos) {
}
//AQUI SE DEBEN CARGAR TODOS LOS DATOS QUE LA FORMA NECESITE.
$bPuedeGuardar = true;
$bConEliminar = false;
$bHayImprimir = false;
$bHayImprimir2 = false;
$bConMasivo = false;
$sScriptImprime = 'imprimelista()';
$sScriptImprime2 = 'imprimep()';
$sClaseImprime = 'iExcel';
$sClaseImprime2 = 'iPdf';
if ($iPiel == 0) {
	$sClaseImprime = 'btEnviarExcel';
	$sClaseImprime2 = 'btEnviarPdf';
}
//Permisos adicionales
$seg_5 = 0;
$seg_6 = 0;
$seg_8 = 0;
$seg_14 = 0;
list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 14, $idTercero, $objDB);
if ($bDevuelve) {
	$seg_14 = 1;
}
list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 6, $idTercero, $objDB, $bDebug);
if ($bDevuelve) {
	$seg_6 = 1;
}
if ((int)$_REQUEST['paso'] != 0) {
	$bDevuelve = false;
	list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 5, $idTercero, $objDB);
	if ($bDevuelve) {
		$seg_5 = 1;
	}
	$bConEliminar = true;
	/*
	if ($seg_5 == 1) {
		$bHayImprimir2 = true;
	}
	*/
}
//DATOS PARA COMPLETAR EL FORMULARIO
$iAgnoIni = 2000;
$iAgno = fecha_agno();
$iAgnoFin = $iAgno + 5;
$sNombreUsuario = '';
//Crear los controles que requieran llamado a base de datos
$objCombos = new clsHtmlCombos();
$objForma = new clsHtmlForma($iPiel);
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
$sWherePerfil = 'unad05reservado="N"';
$sWherePerfil2 = '';
if ($APP->idsistema != 1) {
	$sWherePerfil = 'unad05aplicativo=' . $APP->idsistema . ' AND unad05delegable="S" AND ' . $sWherePerfil;
	$sWherePerfil2 = ' WHERE unad05aplicativo=' . $APP->idsistema . ' AND unad05delegable="S"';
}
list($unad07idtercero_rs, $_REQUEST['unad07idtercero'], $_REQUEST['unad07idtercero_td'], $_REQUEST['unad07idtercero_doc']) = html_tercero($_REQUEST['unad07idtercero_td'], $_REQUEST['unad07idtercero_doc'], $_REQUEST['unad07idtercero'], 0, $objDB);
$bOculto = true;
if ($_REQUEST['paso'] != 2) {
	$bOculto = false;
}
$html_unad07idtercero = html_DivTerceroV8('unad07idtercero', $_REQUEST['unad07idtercero_td'], $_REQUEST['unad07idtercero_doc'], $bOculto, $objDB, $objCombos, 1, $ETI['ing_doc']);
if ((int)$_REQUEST['paso'] == 0) {
	$objCombos->nuevo('unad07idperfil', $_REQUEST['unad07idperfil'], true, '{' . $ETI['msg_seleccione'] . '}');
	$objCombos->sAccion = 'RevisaLlave();';
	$objCombos->iAncho = 350;
	$sSQL = 'SELECT unad05id AS id, unad05nombre AS nombre FROM unad05perfiles WHERE ' . $sWherePerfil . ' ORDER BY unad05nombre';
	$html_unad07idperfil = $objCombos->html($sSQL, $objDB);
} else {
	list($unad07idperfil_nombre, $sErrorDet) = tabla_campoxid('unad05perfiles', 'unad05nombre', 'unad05id', $_REQUEST['unad07idperfil'], '{' . $ETI['msg_sindato'] . '}', $objDB);
	$html_unad07idperfil = html_oculto('unad07idperfil', $_REQUEST['unad07idperfil'], $unad07idperfil_nombre);
}
//Alistar datos adicionales
if ($APP->idsistema == 1) {
	$objCombos->nuevo('masidperfil', $_REQUEST['masidperfil'], true, '{' . $ETI['msg_seleccione'] . '}');
	$objCombos->sAccion = '';
	$sSQL = 'SELECT unad05id AS id, CONCAT(unad05nombre, " [", unad05id, "]") AS nombre FROM unad05perfiles ORDER BY unad05nombre';
	$html_masidperfil = $objCombos->html($sSQL, $objDB);

	$objCombos->nuevo('brevperfil', $_REQUEST['brevperfil'], true, '{' . $ETI['msg_todos'] . '}');
	$objCombos->sAccion = 'paginarfrevisa()';
	$html_brevperfil = $objCombos->html($sSQL, $objDB);
	$objCombos->nuevo('brevpermiso', $_REQUEST['brevpermiso'], true, '{' . $ETI['msg_todos'] . '}');
	$objCombos->sAccion = 'paginarfrevisa()';
	$sSQL = 'SELECT unad03id AS id, CONCAT(unad03nombre, " [", unad03id, "]") AS nombre FROM unad03permisos ORDER BY unad03id';
	$html_brevpermiso = $objCombos->html($sSQL, $objDB);
}
if ($APP->idsistema == 1) {
	$sWherePerfil = '';
}
$objCombos->nuevo('bperfil', $_REQUEST['bperfil'], true, '{' . $ETI['msg_todos'] . '}');
$objCombos->iAncho = 350;
$objCombos->sAccion = 'paginarf107()';
$sSQL = 'SELECT unad05id AS id, CONCAT(unad05nombre, " [", unad05id, "]") AS nombre FROM unad05perfiles ' . $sWherePerfil2 . ' ORDER BY unad05nombre';
$html_bperfil = $objCombos->html($sSQL, $objDB);
$objCombos->nuevo('blistar', $_REQUEST['blistar'], true, '{' . $ETI['msg_todos'] . '}');
$objCombos->sAccion = 'paginarf107()';
$objCombos->addItem(1, 'Activos');
$objCombos->addItem(2, 'Inactivos');
$html_blistar = $objCombos->html('', $objDB);
$id_rpt = 0;
//$id_rpt=reportes_id(_Identificador_Tipo_Reporte_, $objDB);
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
//Cargar las tablas de datos
if ($APP->idsistema == 1) {
	//Cargar las tablas de datos
	$aParametros[101] = $_REQUEST['paginaf108'];
	$aParametros[102] = $_REQUEST['lppf108'];
	$aParametros[103] = $_REQUEST['brevdocumento'];
	$aParametros[104] = $_REQUEST['brevmodulo'];
	$aParametros[105] = $_REQUEST['brevpermiso'];
	$aParametros[106] = $_REQUEST['brevperfil'];
	list($sTabla107rev, $sDebugT) = frevisa_TablaDetalleV2($aParametros, $objDB);
}
$aParametros[101] = $_REQUEST['paginaf107'];
$aParametros[102] = $_REQUEST['lppf107'];
$aParametros[103] = $APP->idsistema;
$aParametros[111] = $_REQUEST['bdocumento'];
$aParametros[112] = $_REQUEST['brazonsocial'];
$aParametros[113] = $_REQUEST['bperfil'];
$aParametros[114] = $_REQUEST['blistar'];
list($sTabla107, $sDebugTabla) = f107_TablaDetalleV2($aParametros, $objDB, $bDebug);
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
		if ($bConEliminar) {
			$aBotones[$iNumBoton] = array('eliminadato()', $ETI['bt_eliminar'], 'iDelete');
			$iNumBoton++;
		}
		if ($bHayImprimir) {
			$aBotones[$iNumBoton] = array($sScriptImprime, $ETI['bt_imprimir'], $sClaseImprime);
			$iNumBoton++;
		}
		if ($bHayImprimir2) {
			$aBotones[$iNumBoton] = array($sScriptImprime2, $ETI['bt_imprimir'], $sClaseImprime2);
			$iNumBoton++;
		}
		$aBotones[$iNumBoton] = array('limpiapagina()', $ETI['bt_limpiar'], 'iDocument');
		$iNumBoton++;
		if ($bPuedeGuardar) {
			$aBotones[$iNumBoton] = array('enviaguardar()', $ETI['bt_guardar'], 'iSaveFill');
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
<script language="javascript">
	function cambiapaginaV2() {
		expandesector(98);
		window.document.frmedita.paso.value = 1;
		window.document.frmedita.submit();
	}

	function expandesector(codigo) {
		document.getElementById('div_sector1').style.display = 'none';
		document.getElementById('div_sector2').style.display = 'none';
		document.getElementById('div_sector95').style.display = 'none';
		document.getElementById('div_sector96').style.display = 'none';
		document.getElementById('div_sector98').style.display = 'none';
		document.getElementById('div_sector' + codigo).style.display = 'block';
<?php
switch ($iPiel) {
	case 2:
?>
		document.getElementById('botones_sector1').style.display = 'none';
		switch (codigo) {
			case 1:
				document.getElementById('botones_sector1').style.display = 'flex';
				break;
			case 2:
				document.getElementById('botones_sector2').style.display = 'flex';
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
		document.getElementById('cmdGuardarf').style.display = sEst;
<?php
		}
		break;
}
?>
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
			}
			//if (illave==1){params[5]='FuncionCuandoNoEsta';}
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
			if (idcampo == 'unad07idtercero') {
				params[4] = 'RevisaLlave';
			}
			xajax_unad11_TraerXid(params);
		}
	}

	function imprimelista() {
		if (window.document.frmedita.seg_6.value == 1) {
			window.document.frmlista.consulta.value = window.document.frmedita.consulta_107.value;
			window.document.frmlista.titulos.value = window.document.frmedita.titulos_107.value;
			window.document.frmlista.nombrearchivo.value = 'Usuarios';
			window.document.frmlista.submit();
		} else {
			ModalMensaje("<?php echo $ERR['6']; ?>");
		}
	}

	function asignarvariables() {
		//window.document.frmimpp.v3.value = window.document.frmedita.bnombre.value;
		//window.document.frmimpp.v4.value = window.document.frmedita.bcodigo.value;
		window.document.frmimpp.separa.value = window.document.frmedita.csv_separa.value.trim();
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
			window.document.frmimpp.action = '<?php echo $APP->rutacomun; ?>e107.php';
			window.document.frmimpp.submit();
		} else {
			ModalMensaje(sError);
		}
	}

	function imprimep() {
		if (window.document.frmedita.seg_5.value == 1) {
			asignarvariables();
			window.document.frmimpp.action = '<?php echo $APP->rutacomun; ?>p107.php';
			window.document.frmimpp.submit();
		} else {
			ModalMensaje("<?php echo $ERR['5']; ?>");
		}
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
		datos[1] = window.document.frmedita.unad07idperfil.value;
		datos[2] = window.document.frmedita.unad07idtercero.value;
		if ((datos[1] != '') && (datos[2] != '')) {
			xajax_f107_ExisteDato(datos);
		}
	}

	function cargadato(llave1, llave2) {
		window.document.frmedita.unad07idperfil.value = String(llave1);
		window.document.frmedita.unad07idtercero.value = String(llave2);
		window.document.frmedita.paso.value = 1;
		window.document.frmedita.submit();
	}

	function paginarf107() {
		let params = new Array();
		params[99] = window.document.frmedita.debug.value;
		params[100] = <?php echo $idTercero; ?>;
		params[101] = window.document.frmedita.paginaf107.value;
		params[102] = window.document.frmedita.lppf107.value;
		params[103] = <?php echo $APP->idsistema; ?>;
		params[111] = window.document.frmedita.bdocumento.value;
		params[112] = window.document.frmedita.brazonsocial.value;
		params[113] = window.document.frmedita.bperfil.value;
		params[114] = window.document.frmedita.blistar.value;
		document.getElementById('div_f107detalle').innerHTML = '<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf107" name="paginaf107" type="hidden" value="' + params[101] + '" /><input id="lppf107" name="lppf107" type="hidden" value="' + params[102] + '" />';
		xajax_f107_HtmlTabla(params);
	}
<?php
if ($bConMasivo) {
?>
	function f107_cargamasiva() {
		extensiones_permitidas = new Array(".xls", ".xlsx");
		var sError = '';
		var archivo = window.document.frmedita.archivodatos.value;
		if (sError == '') {
			if (!archivo) {
				sError = "No has seleccionado ning\u00fan archivo";
			}
		}
		if (sError == '') {
			//recupero la extensión de este nombre de archivo
			extension = (archivo.substring(archivo.lastIndexOf("."))).toLowerCase();
			//compruebo si la extensión está entre las permitidas
			permitida = false;
			for (var i = 0; i < extensiones_permitidas.length; i++) {
				if (extensiones_permitidas[i] == extension) {
					permitida = true;
					break;
				}
			}
			if (!permitida) {
				sError = "Comprueba la extensi\u00f3n de los archivos a subir. \nS\u00f3lo se pueden subir archivos con extensiones: " + extensiones_permitidas.join();
			} else {
				expandesector(98);
				window.document.frmedita.paso.value = 50;
				window.document.frmedita.submit();
				return 1;
			}
		}
		//si estoy aqui es que no se ha podido submitir
		alert(sError);
		return 0;
	}
<?php
}
?>

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
		document.getElementById("unad07idperfil").focus();
	}

	function buscarV2016(sCampo) {
		window.document.frmedita.iscroll.value = window.scrollY;
		expandesector(98);
		window.document.frmedita.scampobusca.value = sCampo;
		let params = new Array();
		params[1] = sCampo;
		//params[2] = window.document.frmedita.iagno.value;
		//params[3] = window.document.frmedita.itipo.value;
		xajax_f107_Busquedas(params);
	}

	function Devuelve(sValor) {
		let sCampo = window.document.frmedita.scampobusca.value;
		if (sCampo == 'unad07idtercero') {
			ter_traerxid('unad07idtercero', sValor);
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

	function paginarfrevisa() {
		let params = new Array();
		//params[0]=window.document.frmedita.p1_107.value;
		params[101] = window.document.frmedita.paginaf108.value;
		params[102] = window.document.frmedita.lppf108.value;
		params[103] = window.document.frmedita.brevdocumento.value;
		params[104] = window.document.frmedita.brevmodulo.value;
		params[105] = window.document.frmedita.brevpermiso.value;
		params[106] = window.document.frmedita.brevperfil.value;
		xajax_frevisa_HtmlTabla(params);
	}
</script>
<form id="frmlista" name="frmlista" method="post" action="listados.php" target="_blank" style="display:none">
<input id="titulos" name="titulos" type="hidden" value="" />
<input id="consulta" name="consulta" type="hidden" value="" />
<input id="nombrearchivo" name="nombrearchivo" type="hidden" value="" />
</form>
<div id="interna">
<form id="frmedita" name="frmedita" method="post" action="" enctype="multipart/form-data" autocomplete="off">
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
<div id="div_sector1">
<?php
if ($bBloqueTitulo) {
?>
<div class="titulos">
<div class="titulosD">
<input id="cmdAyuda" name="cmdAyuda" type="button" class="btUpAyuda" onclick="muestraayuda(<?php echo $APP->idsistema . ', ' . $iCodModulo; ?>);" title="<?php echo $ETI['bt_ayuda']; ?>" value="<?php echo $ETI['bt_ayuda']; ?>" />
<?php
if ($bConEliminar) {
?>
<input id="cmdEliminar" name="cmdEliminar" type="button" class="btUpEliminar" onclick="eliminadato();" title="<?php echo $ETI['bt_eliminar']; ?>" value="<?php echo $ETI['bt_eliminar']; ?>" />
<?php
}
if ($bHayImprimir) {
?>
<input id="cmdImprimir" name="cmdImprimir" type="button" class="<?php echo $sClaseImprime; ?>" onclick="<?php echo $sScriptImprime; ?>" title="<?php echo $ETI['bt_imprimir']; ?>" value="<?php echo $ETI['bt_imprimir']; ?>" />
<?php
}
if ($bHayImprimir2) {
?>
<input id="cmdImprimir2" name="cmdImprimir2" type="button" class="<?php echo $sClaseImprime2; ?>" onclick="<?php echo $sScriptImprime2; ?>" title="<?php echo $ETI['bt_imprimir']; ?>" value="<?php echo $ETI['bt_imprimir']; ?>" />
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
<?php
echo $objForma->htmlBotonSolo('btRevisaDoc', 'btMiniActualizar', 'limpiapagina()', 'Consultar documento', 30);
?>
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
<div class="GrupoCampos520">
<div class="salto5px"></div>
<label class="Label130">
<?php
echo $ETI['unad07idperfil'];
?>
</label>
<label class="Label350">
<?php
echo $html_unad07idperfil;
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['unad07fechavence'];
?>
</label>
<div class="Campo250">
<?php
echo html_fecha("unad07fechavence", $_REQUEST['unad07fechavence'], true);
?>
</div>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['unad07vigente'];
?>
</label>
<label class="Label60">
<?php
echo html_sino('unad07vigente', $_REQUEST['unad07vigente']);
?>
</label>
<div class="salto1px"></div>
</div>
<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo $ETI['unad07idtercero'];
?>
</label>
<div class="salto1px"></div>
<input id="unad07idtercero" name="unad07idtercero" type="hidden" value="<?php echo $_REQUEST['unad07idtercero']; ?>" />
<div id="div_unad07idtercero_llaves">
<?php
echo $html_unad07idtercero;
?>
</div>
<div class="salto1px"></div>
<div id="div_unad07idtercero" class="L"><?php echo $unad07idtercero_rs; ?></div>
<div class="salto1px"></div>
</div>
<?php
// -- Inicia la carga masiva
$bEntraEspeciales = $bConMasivo;
/*
if ($APP->idsistema == 1) {
	if ($_REQUEST['paso'] == 0) {
		$bEntraEspeciales = true;
	}
}
*/
if ($bConMasivo) {
?>
<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo $ETI['msg_plano'];
?>
</label>
<?php
//Div para ocultar
$bConExpande = true;
if ($bConExpande) {
?>
<div class="ir_derecha"<?php echo $sAnchoExpandeContrae; ?>>
<?php
echo $objForma->htmlExpande(107, $_REQUEST['boculta107'], $ETI['bt_mostrar'], $ETI['bt_ocultar']);
$sEstiloDiv = '';
if ($_REQUEST['boculta107'] != 0) {
	$sEstiloDiv = ' style="display:none;"';
}
?>
</div>
<div id="div_p107"<?php echo $sEstiloDiv; ?>>
<?php
}
//Mostrar formulario para editar
?>
<div class="salto1px"></div>
<input id="MAX_FILE_SIZE" name="MAX_FILE_SIZE" type="hidden" value="100000" />
<label class="Label90">&nbsp;</label>
<label class="Label500">
<input id="archivodatos" name="archivodatos" type="file" />
</label>
<div class="salto1px"></div>
<label class="Label90">
<?php
echo $ETI['unad07idperfil'];
?>
</label>
<label>
<?php
echo $html_masidperfil;
?>
</label>
<div class="salto1px"></div>
<label class="Label90">&nbsp;</label>
<?php
echo $objForma->htmlBotonSolo('cmdanexar', 'btSoloAnexar', 'f107_cargamasiva()', $ETI['msg_subir'], 130);
?>
<div class="salto1px"></div>
<div class="GrupoCamposAyuda">
<?php
echo $ETI['msg_infoplano'];
?>
<div class="salto1px"></div>
</div>
<?php
if ($bConExpande) {
	//Este es el cierre del div_p107
?>
<div class="salto1px"></div>
</div>
<?php
}
?>
<div class="salto1px"></div>
</div>
<?php
	}
//Termina la carga masiva.
if (false) {
	//Ejemplo de boton de ayuda
	//echo html_BotonAyuda('NombreCampo');
	//echo html_DivAyudaLocal('NombreCampo');
}
//Mostrar el contenido de la tabla
if ($APP->idsistema == 1) {
?>
<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo '' . $ETI['bloque_revisa'] . '';
?>
</label>
<div class="ir_derecha"<?php echo $sAnchoExpandeContrae; ?>>
<?php
echo $objForma->htmlExpande(99, $_REQUEST['boculta99'], $ETI['bt_mostrar'], $ETI['bt_ocultar']);
$sEstiloDiv = '';
if ($_REQUEST['boculta99'] != 0) {
	$sEstiloDiv = ' style="display:none;"';
}
?>
</div>
<div id="div_p99"<?php echo $sEstiloDiv; ?>>

<div class="salto1px"></div>
<label class="Label90">
Perfil
</label>
<label class="Label130">
<?php
echo $html_brevperfil;
?>
</label>
<div class="salto1px"></div>
<label class="Label90">
Documento
</label>
<label class="Label250">
<input name="brevdocumento" type="text" id="brevdocumento" value="<?php echo $_REQUEST['brevdocumento']; ?>" onchange="paginarfrevisa()" />
</label>
<label class="Label90">
Modulo
</label>
<label class="Label90">
<input name="brevmodulo" type="text" id="brevmodulo" value="<?php echo $_REQUEST['brevmodulo']; ?>" class="cuatro" onchange="paginarfrevisa()" />
</label>
<div class="salto1px"></div>
<label class="Label90">
Permiso
</label>
<label class="Label130">
<?php
echo $html_brevpermiso;
?>
</label>
<div class="salto1px"></div>
<div id="div_frevisadetalle">
<?php
echo $sTabla107rev;
?>
</div>
</div>
<div class="salto1px"></div>
</div>
<?php
} else {
?>
<input name="brevdocumento" id="brevdocumento" type="hidden" value="<?php echo $_REQUEST['brevdocumento']; ?>" />
<input name="brevmodulo" id="brevmodulo" type="hidden" value="<?php echo $_REQUEST['brevmodulo']; ?>" />
<input name="brevpermiso" id="brevpermiso" type="hidden" value="<?php echo $_REQUEST['brevpermiso']; ?>" />
<input name="paginaf108" id="paginaf108" type="hidden" value="<?php echo $_REQUEST['paginaf108']; ?>" />
<input name="lppf108" id="lppf108" type="hidden" value="<?php echo $_REQUEST['lppf108']; ?>" />
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
<div class="filter">
<div class="filter__option">
<label>
<?php
echo $ETI['msg_doc'];
?>
</label>
<input name="bdocumento" type="text" id="bdocumento" value="<?php echo $_REQUEST['bdocumento']; ?>" onchange="paginarf107();" />
</div>
<div class="filter__option">
<label>
<?php
echo 'Raz&oacute;n social';
?>
</label>
<input name="brazonsocial" type="text" id="brazonsocial" value="<?php echo $_REQUEST['brazonsocial']; ?>" onchange="paginarf107();" />
</div>
<div class="filter__option">
<label>
<?php
echo $ETI['msg_listar'];
?>
</label>
<?php
echo $html_blistar;
?>
</div>
<div class="filter__option">
<label>
<?php
echo $ETI['unad07idperfil'];
?>
</label>
<?php
echo $html_bperfil;
?>
</div>
</div>
<div class="salto1px"></div>
<?php
echo ' ' . $csv_separa;
?>
<div class="salto1px"></div>
<div id="div_f107detalle">
<?php
echo $sTabla107;
?>
</div>
<?php
// Termina el div_areatrabajo y DIV_areaform
?>
</div>
</div>
</div>


<div id="div_sector2" style="display:none">
<?php
if ($bBloqueTitulo) {
?>
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
<?php
}
?>
<div class="areaform">
<div class="areatrabajo">
</div>
</div>
<?php
// Termina el div_sector2
?>
</div>


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
<input id="titulo_107" name="titulo_107" type="hidden" value="<?php echo $sTituloModulo; ?>" />
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
</div>


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
	$sDebug = $sDebug . log_debug('Tiempo total del proceso: <b>' . $iSegundos . '</b> Segundos');
	echo console_debug($sDebug);
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
<input id="cmdGuardarf" name="cmdGuardarf" type="button" class="btSoloGuardar" onClick="enviaguardar();" value="<?php echo $ETI['bt_guardar']; ?>" />
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
<script language="javascript" src="<?php echo $APP->rutacomun; ?>js/chosen.jquery.js"></script>
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>js/chosen.css" type="text/css" />
<script language="javascript">
	$().ready(function() {
		<?php
		if ($_REQUEST['paso'] == 0) {
		?>
			$("#unad07idperfil").chosen();
		<?php
		}
		if ($APP->idsistema == 1) {
		?>
			$("#brevperfil").chosen({
				width: "520px"
			});
			$("#brevpermiso").chosen({
				width: "400px"
			});
		<?php
		}
		?>
		$("#bperfil").chosen();
	});
</script>
<script language="javascript" src="ac_107.js"></script>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>unad_todas2024v2.js"></script>
<?php
forma_piedepagina();

