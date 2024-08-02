<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2021 - 2024 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.26.2 martes, 15 de junio de 2021
--- Modelo Versión 2.30.1 martes, 22 de agosto de 2023
--- Modelo Versión 3.0.8 jueves, 16 de mayo de 2024
*/

/** Archivo saidevolucion.php.
 * Modulo 3047 .
 * @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
 * @param debug=1 (Opcional), bandera para indicar si se generan datos de depuración
 * @date martes, 15 de junio de 2021
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
require $APP->rutacomun . 'libaurea.php';
require $APP->rutacomun . 'libmail.php';
if (($bPeticionXAJAX) && ($_SESSION['unad_id_tercero'] == 0)) {
	// viene por xajax.
	$xajax = new xajax();
	$xajax->configure('javascript URI', $APP->rutacomun . 'xajax/');
	$xajax->register(XAJAX_FUNCTION, 'sesion_abandona_V2');
	$xajax->processRequest();
	die();
}
$bEnSesion = false;
if ((int)$_SESSION['unad_id_tercero'] > 0) {
	$bEnSesion = true;
}
if (!$bEnSesion) {
	$_SESSION['unad_redir'] = 'saidevolucion.php';
	header('Location:index.php');
	die();
}
$iConsecutivoMenu = 1;
$iMinVerDB = 7555;
$iCodModulo = 3047;
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
$mensajes_3047 = $APP->rutacomun . 'lg/lg_3047_' . $_SESSION['unad_idioma'] . '.php';
if (!file_exists($mensajes_3047)) {
	$mensajes_3047 = $APP->rutacomun . 'lg/lg_3047_es.php';
}
require $mensajes_todas;
require $mensajes_3047;
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
$sOcultaId = ' style="display:none;"';
$sTituloApp = 'SII';
$sTituloModulo = $ETI['titulo_3047_dev'];
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
	if ($idTercero == $_SESSION['unad_id_tercero']){
		$bDevuelve = true;
	} else {
		list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModuloConsulta, 1, $_SESSION['unad_id_tercero'], $objDB);
	}
	if (!$bDevuelve) {
		$bCerrado = true;
		$sMsgCierre = '<div class="MarquesinaGrande">No cuenta con permiso para acceder a este modulo [' . $iCodModuloConsulta . '].</div>';
	}
}
if ($bCerrado) {
	if ($bCargaMenu) {
		switch ($iPiel) {
			case 2:
				list($et_menu, $sDebugM) = html_menuCampusV2($objDB, $iPiel, $bDebugMenu, $idTercero);
				break;
			default:
				list($et_menu, $sDebugM) = html_menuCampus($APP->idsistema, $objDB, $iPiel, $bDebugMenu, $idTercero);
				break;
		}
	}
	$objDB->CerrarConexion();
	switch ($iPiel) {
		case 2:
			require $APP->rutacomun . 'unad_forma2023.php';
			forma_InicioV4($xajax, $sTituloModulo);
			$aRutas = array(
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
$seg_1707 = 0;
$bDevuelve = false;
list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 1707, $_SESSION['unad_id_tercero'], $objDB, $bDebug);
//$sDebug = $sDebug . $sDebugP;
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
$mensajes_3000 = $APP->rutacomun . 'lg/lg_3000_' . $_SESSION['unad_idioma'] . '.php';
if (!file_exists($mensajes_3000)) {
	$mensajes_3000 = $APP->rutacomun . 'lg/lg_3000_es.php';
}
$mensajes_3048 = 'lg/lg_3048_' . $_SESSION['unad_idioma'] . '.php';
if (!file_exists($mensajes_3048)) {
	$mensajes_3048 = 'lg/lg_3048_es.php';
}
$mensajes_3049 = 'lg/lg_3049_' . $_SESSION['unad_idioma'] . '.php';
if (!file_exists($mensajes_3049)) {
	$mensajes_3049 = 'lg/lg_3049_es.php';
}
$mensajes_3059 = 'lg/lg_3059_' . $_SESSION['unad_idioma'] . '.php';
if (!file_exists($mensajes_3059)) {
	$mensajes_3059 = 'lg/lg_3059_es.php';
}
require $mensajes_3000;
require $mensajes_3048;
require $mensajes_3049;
require $mensajes_3059;
// -- Si esta cargando la pagina por primer vez se revisa si requiere auditar y se manda a hacer un limpiar (paso -1)
if (isset($_REQUEST['paso']) == 0) {
	$_REQUEST['paso'] = -1;
	if ($audita[1]) {
		seg_auditaingreso($iCodModulo, $_SESSION['unad_id_tercero'], $objDB);
	}
}
// -- 3000 Historial de solicitudes
require $APP->rutacomun . 'lib3000.php';
// -- 3047 saiu47tramites
require $APP->rutacomun . 'lib3047.php';
require 'lib3047devoluciones.php';
// -- 3048 Anotaciones
require $APP->rutacomun . 'lib3048.php';
// -- 3049 Cambios de estado
require $APP->rutacomun . 'lib3049.php';
// -- 3059 Anexos
require $APP->rutacomun . 'lib3059.php';
$xajax = new xajax();
$xajax->configure('javascript URI', $APP->rutacomun . 'xajax/');
$xajax->register(XAJAX_FUNCTION, 'unad11_Mostrar_v2');
$xajax->register(XAJAX_FUNCTION, 'unad11_TraerXid');
$xajax->register(XAJAX_FUNCTION, 'formatear_moneda');
$xajax->register(XAJAX_FUNCTION, 'f3047_Combosaiu47t1idmotivo');
$xajax->register(XAJAX_FUNCTION, 'sesion_abandona_V2');
$xajax->register(XAJAX_FUNCTION, 'sesion_mantenerV4');
$xajax->register(XAJAX_FUNCTION, 'f3047_HtmlTablaTercero');
$xajax->register(XAJAX_FUNCTION, 'f3047_ExisteDato');
$xajax->register(XAJAX_FUNCTION, 'f3047_Busquedas');
$xajax->register(XAJAX_FUNCTION, 'f3047_HtmlBusqueda');
$xajax->register(XAJAX_FUNCTION, 'f3047_Detsaiu47t1idmotivo');
$xajax->register(XAJAX_FUNCTION, 'f3000_HtmlTabla');
$xajax->register(XAJAX_FUNCTION, 'f3048_Guardar');
$xajax->register(XAJAX_FUNCTION, 'f3048_Traer');
$xajax->register(XAJAX_FUNCTION, 'f3048_Eliminar');
$xajax->register(XAJAX_FUNCTION, 'f3048_HtmlTablaCampus');
$xajax->register(XAJAX_FUNCTION, 'f3048_PintarLlaves');
$xajax->register(XAJAX_FUNCTION, 'f3049_Guardar');
$xajax->register(XAJAX_FUNCTION, 'f3049_Traer');
$xajax->register(XAJAX_FUNCTION, 'f3049_Eliminar');
$xajax->register(XAJAX_FUNCTION, 'f3049_HtmlTabla');
$xajax->register(XAJAX_FUNCTION, 'f3049_PintarLlaves');
$xajax->register(XAJAX_FUNCTION, 'elimina_archivo_saiu59idarchivo');
$xajax->register(XAJAX_FUNCTION, 'f3059_Guardar');
$xajax->register(XAJAX_FUNCTION, 'f3059_Traer');
$xajax->register(XAJAX_FUNCTION, 'f3059_Eliminar');
$xajax->register(XAJAX_FUNCTION, 'f3059_HtmlTablaCampus');
$xajax->register(XAJAX_FUNCTION, 'f3059_PintarLlaves');
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
$iTipoTramite = 1;
$iHoy = fecha_DiaMod();
// -- Se inicializan las variables, primero las que controlan la visualización de la página.
if (isset($_REQUEST['iscroll']) == 0) {
	$_REQUEST['iscroll'] = 0;
}
if (isset($_REQUEST['paginaf3047']) == 0) {
	$_REQUEST['paginaf3047'] = 1;
}
if (isset($_REQUEST['lppf3047']) == 0) {
	$_REQUEST['lppf3047'] = 20;
}
if (isset($_REQUEST['boculta3047']) == 0) {
	$_REQUEST['boculta3047'] = 0;
}
if (isset($_REQUEST['paginaf3000']) == 0) {
	$_REQUEST['paginaf3000'] = 1;
}
if (isset($_REQUEST['lppf3000']) == 0) {
	$_REQUEST['lppf3000'] = 10;
}
if (isset($_REQUEST['boculta3000']) == 0) {
	$_REQUEST['boculta3000'] = 0;
}
if (isset($_REQUEST['paginaf3048']) == 0) {
	$_REQUEST['paginaf3048'] = 1;
}
if (isset($_REQUEST['lppf3048']) == 0) {
	$_REQUEST['lppf3048'] = 20;
}
if (isset($_REQUEST['boculta3048']) == 0) {
	$_REQUEST['boculta3048'] = 0;
}
if (isset($_REQUEST['paginaf3049']) == 0) {
	$_REQUEST['paginaf3049'] = 1;
}
if (isset($_REQUEST['lppf3049']) == 0) {
	$_REQUEST['lppf3049'] = 20;
}
if (isset($_REQUEST['boculta3049']) == 0) {
	$_REQUEST['boculta3049'] = 0;
}
if (isset($_REQUEST['paginaf3059']) == 0) {
	$_REQUEST['paginaf3059'] = 1;
}
if (isset($_REQUEST['lppf3059']) == 0) {
	$_REQUEST['lppf3059'] = 20;
}
if (isset($_REQUEST['boculta3059']) == 0) {
	$_REQUEST['boculta3059'] = 0;
}
// -- Inicializar variables de datos.
if (isset($_REQUEST['saiu47agno']) == 0) {
	$_REQUEST['saiu47agno'] = 0;
}
if (isset($_REQUEST['saiu47mes']) == 0) {
	$_REQUEST['saiu47mes'] = 0;
}
if (isset($_REQUEST['saiu47tiporadicado']) == 0) {
	$_REQUEST['saiu47tiporadicado'] = 1;
}
if (isset($_REQUEST['saiu47tipotramite']) == 0) {
	$_REQUEST['saiu47tipotramite'] = $iTipoTramite;
}
if (isset($_REQUEST['saiu47consec']) == 0) {
	$_REQUEST['saiu47consec'] = '';
}
if (isset($_REQUEST['saiu47consec_nuevo']) == 0) {
	$_REQUEST['saiu47consec_nuevo'] = '';
}
if (isset($_REQUEST['saiu47id']) == 0) {
	$_REQUEST['saiu47id'] = '';
}
if (isset($_REQUEST['saiu47origenagno']) == 0) {
	$_REQUEST['saiu47origenagno'] = '';
}
if (isset($_REQUEST['saiu47origenmes']) == 0) {
	$_REQUEST['saiu47origenmes'] = '';
}
if (isset($_REQUEST['saiu47origenid']) == 0) {
	$_REQUEST['saiu47origenid'] = 0;
}
if (isset($_REQUEST['saiu47dia']) == 0) {
	$_REQUEST['saiu47dia'] = '';
}
if (isset($_REQUEST['saiu47hora']) == 0) {
	$_REQUEST['saiu47hora'] = fecha_hora();
}
if (isset($_REQUEST['saiu47minuto']) == 0) {
	$_REQUEST['saiu47minuto'] = fecha_minuto();
}
if (isset($_REQUEST['saiu47idsolicitante']) == 0) {
	$_REQUEST['saiu47idsolicitante'] = 0;
}
if (isset($_REQUEST['saiu47idsolicitante_td']) == 0) {
	$_REQUEST['saiu47idsolicitante_td'] = $APP->tipo_doc;
}
if (isset($_REQUEST['saiu47idsolicitante_doc']) == 0) {
	$_REQUEST['saiu47idsolicitante_doc'] = '';
}
if (isset($_REQUEST['saiu47idperiodo']) == 0) {
	$_REQUEST['saiu47idperiodo'] = 0;
}
if (isset($_REQUEST['saiu47idescuela']) == 0) {
	$_REQUEST['saiu47idescuela'] = 0;
}
if (isset($_REQUEST['saiu47idprograma']) == 0) {
	$_REQUEST['saiu47idprograma'] = 0;
}
if (isset($_REQUEST['saiu47idzona']) == 0) {
	$_REQUEST['saiu47idzona'] = 0;
}
if (isset($_REQUEST['saiu47idcentro']) == 0) {
	$_REQUEST['saiu47idcentro'] = 0;
}
if (isset($_REQUEST['saiu47estado']) == 0) {
	$_REQUEST['saiu47estado'] = 0;
}
if (isset($_REQUEST['saiu47t1idmotivo']) == 0) {
	$_REQUEST['saiu47t1idmotivo'] = '';
}
if (isset($_REQUEST['saiu47t1vrsolicitado']) == 0) {
	$_REQUEST['saiu47t1vrsolicitado'] = '';
}
if (isset($_REQUEST['saiu47t1vraprobado']) == 0) {
	$_REQUEST['saiu47t1vraprobado'] = '';
}
if (isset($_REQUEST['saiu47t1vrsaldoafavor']) == 0) {
	$_REQUEST['saiu47t1vrsaldoafavor'] = '';
}
if (isset($_REQUEST['saiu47t1vrdevolucion']) == 0) {
	$_REQUEST['saiu47t1vrdevolucion'] = '';
}
if (isset($_REQUEST['saiu47idbenefdevol']) == 0) {
	$_REQUEST['saiu47idbenefdevol'] = 0;
	//$_REQUEST['saiu47idbenefdevol'] = $idTercero;
}
if (isset($_REQUEST['saiu47idbenefdevol_td']) == 0) {
	$_REQUEST['saiu47idbenefdevol_td'] = $APP->tipo_doc;
}
if (isset($_REQUEST['saiu47idbenefdevol_doc']) == 0) {
	$_REQUEST['saiu47idbenefdevol_doc'] = '';
}
if (isset($_REQUEST['saiu47idaprueba']) == 0) {
	$_REQUEST['saiu47idaprueba'] = 0;
	//$_REQUEST['saiu47idaprueba'] = $idTercero;
}
if (isset($_REQUEST['saiu47idaprueba_td']) == 0) {
	$_REQUEST['saiu47idaprueba_td'] = $APP->tipo_doc;
}
if (isset($_REQUEST['saiu47idaprueba_doc']) == 0) {
	$_REQUEST['saiu47idaprueba_doc'] = '';
}
if (isset($_REQUEST['saiu47fechaaprueba']) == 0) {
	$_REQUEST['saiu47fechaaprueba'] = '';
	//$_REQUEST['saiu47fechaaprueba'] = $iHoy;
}
if (isset($_REQUEST['saiu47horaaprueba']) == 0) {
	$_REQUEST['saiu47horaaprueba'] = fecha_hora();
}
if (isset($_REQUEST['saiu47minutoaprueba']) == 0) {
	$_REQUEST['saiu47minutoaprueba'] = fecha_minuto();
}
if (isset($_REQUEST['saiu47detalle']) == 0) {
	$_REQUEST['saiu47detalle'] = '';
}
if (isset($_REQUEST['saiu47idunidad']) == 0) {
	$_REQUEST['saiu47idunidad'] = '';
}
if (isset($_REQUEST['saiu47idgrupotrabajo']) == 0) {
	$_REQUEST['saiu47idgrupotrabajo'] = '';
}
if (isset($_REQUEST['saiu47idresponsable']) == 0) {
	$_REQUEST['saiu47idresponsable'] = 0;
	//$_REQUEST['saiu47idresponsable'] = $idTercero;
}
if (isset($_REQUEST['saiu47idresponsable_td']) == 0) {
	$_REQUEST['saiu47idresponsable_td'] = '';
}
if (isset($_REQUEST['saiu47idresponsable_doc']) == 0) {
	$_REQUEST['saiu47idresponsable_doc'] = '';
}
if (isset($_REQUEST['saiu47t707fecha']) == 0) {
	$_REQUEST['saiu47t707fecha'] = 0;
}
if (isset($_REQUEST['saiu47t707formarecaudo']) == 0) {
	$_REQUEST['saiu47t707formarecaudo'] = '';
}
if (isset($_REQUEST['saiu47t707identidadconv']) == 0) {
	$_REQUEST['saiu47t707identidadconv'] = '';
}
if (isset($_REQUEST['saiu47t707idbanco']) == 0) {
	$_REQUEST['saiu47t707idbanco'] = '';
}
if (isset($_REQUEST['saiu47t707idcuenta']) == 0) {
	$_REQUEST['saiu47t707idcuenta'] = '';
}
$_REQUEST['saiu47agno'] = numeros_validar($_REQUEST['saiu47agno']);
$_REQUEST['saiu47mes'] = numeros_validar($_REQUEST['saiu47mes']);
$_REQUEST['saiu47tiporadicado'] = numeros_validar($_REQUEST['saiu47tiporadicado']);
$_REQUEST['saiu47tipotramite'] = numeros_validar($_REQUEST['saiu47tipotramite']);
$_REQUEST['saiu47consec'] = numeros_validar($_REQUEST['saiu47consec']);
$_REQUEST['saiu47id'] = numeros_validar($_REQUEST['saiu47id']);
$_REQUEST['saiu47origenagno'] = numeros_validar($_REQUEST['saiu47origenagno']);
$_REQUEST['saiu47origenmes'] = numeros_validar($_REQUEST['saiu47origenmes']);
$_REQUEST['saiu47origenid'] = numeros_validar($_REQUEST['saiu47origenid']);
$_REQUEST['saiu47dia'] = numeros_validar($_REQUEST['saiu47dia']);
$_REQUEST['saiu47hora'] = numeros_validar($_REQUEST['saiu47hora']);
$_REQUEST['saiu47minuto'] = numeros_validar($_REQUEST['saiu47minuto']);
$_REQUEST['saiu47idsolicitante'] = numeros_validar($_REQUEST['saiu47idsolicitante']);
$_REQUEST['saiu47idsolicitante_td'] = cadena_Validar($_REQUEST['saiu47idsolicitante_td']);
$_REQUEST['saiu47idsolicitante_doc'] = cadena_Validar($_REQUEST['saiu47idsolicitante_doc']);
$_REQUEST['saiu47idperiodo'] = numeros_validar($_REQUEST['saiu47idperiodo']);
$_REQUEST['saiu47idescuela'] = numeros_validar($_REQUEST['saiu47idescuela']);
$_REQUEST['saiu47idprograma'] = numeros_validar($_REQUEST['saiu47idprograma']);
$_REQUEST['saiu47idzona'] = numeros_validar($_REQUEST['saiu47idzona']);
$_REQUEST['saiu47idcentro'] = numeros_validar($_REQUEST['saiu47idcentro']);
$_REQUEST['saiu47estado'] = cadena_Validar($_REQUEST['saiu47estado']);
$_REQUEST['saiu47t1idmotivo'] = numeros_validar($_REQUEST['saiu47t1idmotivo']);
$_REQUEST['saiu47t1vrsolicitado'] = numeros_validar($_REQUEST['saiu47t1vrsolicitado'], true, 2);
$_REQUEST['saiu47t1vraprobado'] = numeros_validar($_REQUEST['saiu47t1vraprobado'], true, 2);
$_REQUEST['saiu47t1vrsaldoafavor'] = numeros_validar($_REQUEST['saiu47t1vrsaldoafavor'], true, 2);
$_REQUEST['saiu47t1vrdevolucion'] = numeros_validar($_REQUEST['saiu47t1vrdevolucion'], true, 2);
$_REQUEST['saiu47idbenefdevol'] = numeros_validar($_REQUEST['saiu47idbenefdevol']);
$_REQUEST['saiu47idbenefdevol_td'] = cadena_Validar($_REQUEST['saiu47idbenefdevol_td']);
$_REQUEST['saiu47idbenefdevol_doc'] = cadena_Validar($_REQUEST['saiu47idbenefdevol_doc']);
$_REQUEST['saiu47idaprueba'] = numeros_validar($_REQUEST['saiu47idaprueba']);
$_REQUEST['saiu47idaprueba_td'] = cadena_Validar($_REQUEST['saiu47idaprueba_td']);
$_REQUEST['saiu47idaprueba_doc'] = cadena_Validar($_REQUEST['saiu47idaprueba_doc']);
$_REQUEST['saiu47fechaaprueba'] = numeros_validar($_REQUEST['saiu47fechaaprueba']);
$_REQUEST['saiu47horaaprueba'] = numeros_validar($_REQUEST['saiu47horaaprueba']);
$_REQUEST['saiu47minutoaprueba'] = numeros_validar($_REQUEST['saiu47minutoaprueba']);
$_REQUEST['saiu47detalle'] = cadena_Validar($_REQUEST['saiu47detalle']);
$_REQUEST['saiu47idunidad'] = numeros_validar($_REQUEST['saiu47idunidad']);
$_REQUEST['saiu47idgrupotrabajo'] = numeros_validar($_REQUEST['saiu47idgrupotrabajo']);
$_REQUEST['saiu47idresponsable'] = numeros_validar($_REQUEST['saiu47idresponsable']);
$_REQUEST['saiu47t707fecha'] = numeros_validar($_REQUEST['saiu47t707fecha']);
$_REQUEST['saiu47t707formarecaudo'] = numeros_validar($_REQUEST['saiu47t707formarecaudo']);
$_REQUEST['saiu47t707identidadconv'] = numeros_validar($_REQUEST['saiu47t707identidadconv']);
$_REQUEST['saiu47t707idbanco'] = numeros_validar($_REQUEST['saiu47t707idbanco']);
$_REQUEST['saiu47t707idcuenta'] = numeros_validar($_REQUEST['saiu47t707idcuenta']);
if ((int)$_REQUEST['paso'] > 0) {
	//Anotaciones
	if (isset($_REQUEST['saiu48idtramite']) == 0) {
		$_REQUEST['saiu48idtramite'] = '';
	}
	if (isset($_REQUEST['saiu48consec']) == 0) {
		$_REQUEST['saiu48consec'] = '';
	}
	if (isset($_REQUEST['saiu48id']) == 0) {
		$_REQUEST['saiu48id'] = '';
	}
	if (isset($_REQUEST['saiu48visiblealinteresado']) == 0) {
		$_REQUEST['saiu48visiblealinteresado'] = 1;
	}
	if (isset($_REQUEST['saiu48anotacion']) == 0) {
		$_REQUEST['saiu48anotacion'] = '';
	}
	if (isset($_REQUEST['saiu48idusuario']) == 0) {
		$_REQUEST['saiu48idusuario'] = 0;
		//$_REQUEST['saiu48idusuario'] =  $idTercero;
	}
	if (isset($_REQUEST['saiu48idusuario_td']) == 0) {
		$_REQUEST['saiu48idusuario_td'] = $APP->tipo_doc;
	}
	if (isset($_REQUEST['saiu48idusuario_doc']) == 0) {
		$_REQUEST['saiu48idusuario_doc'] = '';
	}
	if (isset($_REQUEST['saiu48fecha']) == 0) {
		$_REQUEST['saiu48fecha'] = '';
		//$_REQUEST['saiu48fecha'] = $iHoy;
	}
	if (isset($_REQUEST['saiu48hora']) == 0) {
		$_REQUEST['saiu48hora'] = '';
	}
	if (isset($_REQUEST['saiu48minuto']) == 0) {
		$_REQUEST['saiu48minuto'] = '';
	}
	$_REQUEST['saiu48idtramite'] = numeros_validar($_REQUEST['saiu48idtramite']);
	$_REQUEST['saiu48consec'] = numeros_validar($_REQUEST['saiu48consec']);
	$_REQUEST['saiu48id'] = numeros_validar($_REQUEST['saiu48id']);
	$_REQUEST['saiu48visiblealinteresado'] = numeros_validar($_REQUEST['saiu48visiblealinteresado']);
	$_REQUEST['saiu48anotacion'] = cadena_Validar($_REQUEST['saiu48anotacion']);
	$_REQUEST['saiu48idusuario'] = numeros_validar($_REQUEST['saiu48idusuario']);
	$_REQUEST['saiu48idusuario_td'] = cadena_Validar($_REQUEST['saiu48idusuario_td']);
	$_REQUEST['saiu48idusuario_doc'] = cadena_Validar($_REQUEST['saiu48idusuario_doc']);
	$_REQUEST['saiu48fecha'] = numeros_validar($_REQUEST['saiu48fecha']);
	$_REQUEST['saiu48hora'] = numeros_validar($_REQUEST['saiu48hora']);
	$_REQUEST['saiu48minuto'] = numeros_validar($_REQUEST['saiu48minuto']);
	//Cambios de estado
	//Anexos
}
// Espacio para inicializar otras variables
if (isset($_REQUEST['csv_separa']) == 0) {
	$_REQUEST['csv_separa'] = ';';
}
if (isset($_REQUEST['bnombre']) == 0) {
	$_REQUEST['bnombre'] = '';
}
if (isset($_REQUEST['blistar']) == 0) {
	$_REQUEST['blistar'] = fecha_agno();
}
if (isset($_REQUEST['blistar2']) == 0) {
	$_REQUEST['blistar2'] = 1;
}
if ((int)$_REQUEST['paso'] > 0) {
}
//Si Modifica o Elimina Cargar los campos
if (($_REQUEST['paso'] == 1) || ($_REQUEST['paso'] == 3)) {
	$_REQUEST['saiu47idsolicitante_td'] = $APP->tipo_doc;
	$_REQUEST['saiu47idsolicitante_doc'] = '';
	$_REQUEST['saiu47idbenefdevol_td'] = $APP->tipo_doc;
	$_REQUEST['saiu47idbenefdevol_doc'] = '';
	$_REQUEST['saiu47idaprueba_td'] = $APP->tipo_doc;
	$_REQUEST['saiu47idaprueba_doc'] = '';
	if ($_REQUEST['paso'] == 1) {
		$sSQLcondi = 'saiu47agno=' . $_REQUEST['saiu47agno'] . ' AND saiu47mes=' . $_REQUEST['saiu47mes'] . ' AND saiu47tiporadicado=' . $_REQUEST['saiu47tiporadicado'] . ' AND saiu47tipotramite=' . $_REQUEST['saiu47tipotramite'] . ' AND saiu47consec=' . $_REQUEST['saiu47consec'] . '';
	} else {
		$sSQLcondi = 'saiu47id=' . $_REQUEST['saiu47id'] . '';
	}
	$sSQL = 'SELECT * FROM saiu47tramites_' . $_REQUEST['saiu47agno'] . ' WHERE ' . $sSQLcondi;
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$_REQUEST['saiu47agno'] = $fila['saiu47agno'];
		$_REQUEST['saiu47mes'] = $fila['saiu47mes'];
		$_REQUEST['saiu47tiporadicado'] = $fila['saiu47tiporadicado'];
		$_REQUEST['saiu47tipotramite'] = $fila['saiu47tipotramite'];
		$_REQUEST['saiu47consec'] = $fila['saiu47consec'];
		$_REQUEST['saiu47id'] = $fila['saiu47id'];
		$_REQUEST['saiu47origenagno'] = $fila['saiu47origenagno'];
		$_REQUEST['saiu47origenmes'] = $fila['saiu47origenmes'];
		$_REQUEST['saiu47origenid'] = $fila['saiu47origenid'];
		$_REQUEST['saiu47dia'] = $fila['saiu47dia'];
		$_REQUEST['saiu47hora'] = $fila['saiu47hora'];
		$_REQUEST['saiu47minuto'] = $fila['saiu47minuto'];
		$_REQUEST['saiu47idsolicitante'] = $fila['saiu47idsolicitante'];
		$_REQUEST['saiu47idperiodo'] = $fila['saiu47idperiodo'];
		$_REQUEST['saiu47idescuela'] = $fila['saiu47idescuela'];
		$_REQUEST['saiu47idprograma'] = $fila['saiu47idprograma'];
		$_REQUEST['saiu47idzona'] = $fila['saiu47idzona'];
		$_REQUEST['saiu47idcentro'] = $fila['saiu47idcentro'];
		$_REQUEST['saiu47estado'] = $fila['saiu47estado'];
		$_REQUEST['saiu47t1idmotivo'] = $fila['saiu47t1idmotivo'];
		$_REQUEST['saiu47t1vrsolicitado'] = $fila['saiu47t1vrsolicitado'];
		$_REQUEST['saiu47t1vraprobado'] = $fila['saiu47t1vraprobado'];
		$_REQUEST['saiu47t1vrsaldoafavor'] = $fila['saiu47t1vrsaldoafavor'];
		$_REQUEST['saiu47t1vrdevolucion'] = $fila['saiu47t1vrdevolucion'];
		$_REQUEST['saiu47idbenefdevol'] = $fila['saiu47idbenefdevol'];
		$_REQUEST['saiu47idaprueba'] = $fila['saiu47idaprueba'];
		$_REQUEST['saiu47fechaaprueba'] = $fila['saiu47fechaaprueba'];
		$_REQUEST['saiu47horaaprueba'] = $fila['saiu47horaaprueba'];
		$_REQUEST['saiu47minutoaprueba'] = $fila['saiu47minutoaprueba'];
		$_REQUEST['saiu47detalle'] = $fila['saiu47detalle'];
		$_REQUEST['saiu47idunidad'] = $fila['saiu47idunidad'];
		$_REQUEST['saiu47idgrupotrabajo'] = $fila['saiu47idgrupotrabajo'];
		$_REQUEST['saiu47idresponsable'] = $fila['saiu47idresponsable'];
		$_REQUEST['saiu47t707fecha'] = $fila['saiu47t707fecha'];
		$_REQUEST['saiu47t707formarecaudo'] = $fila['saiu47t707formarecaudo'];
		$_REQUEST['saiu47t707identidadconv'] = $fila['saiu47t707identidadconv'];
		$_REQUEST['saiu47t707idbanco'] = $fila['saiu47t707idbanco'];
		$_REQUEST['saiu47t707idcuenta'] = $fila['saiu47t707idcuenta'];
		$bcargo = true;
		$_REQUEST['paso'] = 2;
		$_REQUEST['boculta3047'] = 0;
		$bLimpiaHijos = true;
		if ($_REQUEST['saiu47tipotramite'] != $iTipoTramite) {
			$_REQUEST['paso'] = -1;
			$sError = 'Este registro no se puede gestionar en este m&oacute;dulo.';
		}
	} else {
		$_REQUEST['paso'] = -1;
		$sError = 'No se ha encontrado el registro solicitado. ' . $sSQL;
	}
}
//Cerrar
$bCerrando = false;
$bEjecutaCerrar = false;
if ((int)$_REQUEST['paso'] > 0) {
	$iAgno = $_REQUEST['saiu47agno'];
	$sTabla47 = 'saiu47tramites_' . $iAgno;
	$sTabla49 = 'saiu49cambioesttra_' . $iAgno;
	$sTabla59 = 'saiu59tramiteanexo_' . $iAgno;
	if (!$objDB->bexistetabla($sTabla47)) {
		$sError = 'No ha sido posible acceder al contenedor de datos';
	}
}
if ($_REQUEST['paso'] == 16) {
	$_REQUEST['paso'] = 12;
	if ($sError == '') {
		$sSQL = 'SELECT 1 FROM ' . $sTabla59 . ' AS TB, saiu51tramitedoc AS T51 
		WHERE TB.saiu59idtramite=' . $_REQUEST['saiu47id'] . ' AND TB.saiu59idarchivo=0 
		AND TB.saiu59idtipodoc=T51.saiu51id AND T51.saiu51opcional=0';
		$tabla = $objDB->ejecutasql($sSQL);
		$iPendientes = $objDB->nf($tabla);
		if ($iPendientes > 0) {
			$sError = 'Existen ' . $iPendientes . ' anexos que no han sido cargados, no se permite iniciar la radicaci&oacute;n.';
		}
	}
	if ($sError == '') {
		$bEjecutaCerrar = true;
	} else {
		$_REQUEST['paso'] = 2;
	}
}
//Insertar o modificar un elemento
if (($_REQUEST['paso'] == 10) || ($_REQUEST['paso'] == 12)) {
	$bMueveScroll = true;
	list($_REQUEST, $sError, $iTipoError, $bCerrandoB, $sErrorCerrando, $sDebugGuardar) = f3047_db_GuardarV2($_REQUEST, $objDB, $bDebug, $idTercero);
	$sDebug = $sDebug . $sDebugGuardar;
	if ($sError == '') {
		$sError = '<b>' . $ETI['msg_itemguardado'] . '</b>';
		$iTipoError = 1;
		if ($sErrorCerrando != '') {
			$iTipoError = 0;
			$sError = '<b>' . $ETI['msg_itemguardado'] . '</b><br>' . $sErrorCerrando;
		}
		if ($bCerrando) {
			$sError = '<b>' . $ETI['msg_itemcerrado'] . '</b>';
		}
	} else {
		$bEjecutaCerrar = false;
	}
}
//Ejecutar el cerrar
if ($bEjecutaCerrar) {
	//acciones del cerrado
	$idEstadoOrigen = 0;
	$sError = '';
	if ($_REQUEST['saiu47estado'] == 5) {
		$idEstadoOrigen = 5;
	}
	//Validaciones del cierre que las dejamos aqui...
	if ($sError == '') {
		list($sError, $sDebugE, $sMensaje) = f3047_CambiaEstado($_REQUEST['saiu47agno'], $_REQUEST['saiu47id'], $idEstadoOrigen, 1, '', $_SESSION['unad_id_tercero'], $objDB, $bDebug);
	}
	if ($sError == '') {
		$_REQUEST['saiu47estado'] = 1;
		$iTipoError = 1;
		$sError = '<b>La solicitud ha sido radicada.</b>';
		if ($sMensaje != '') {
			$sError = $sError . '<br>' . $sMensaje;
		}
	}
}
//Cambiar a en revision.
if ($_REQUEST['paso'] == 21) {
}
//Devolver
if ($_REQUEST['paso'] == 22) {
}
//Notificar un evento.
//En validación de datos
//En estudio
//No procedente
//Aplicar
//Devolver el cambio de estado
// Reasignar responsable.
//limpiar la pantalla
if ($_REQUEST['paso'] == -1) {
	$_REQUEST['saiu47agno'] = fecha_agno();
	$_REQUEST['saiu47mes'] = fecha_mes();
	$_REQUEST['saiu47tiporadicado'] = 1;
	$_REQUEST['saiu47tipotramite'] = $iTipoTramite;
	$_REQUEST['saiu47consec'] = '';
	$_REQUEST['saiu47consec_nuevo'] = '';
	$_REQUEST['saiu47id'] = '';
	$_REQUEST['saiu47origenagno'] = '';
	$_REQUEST['saiu47origenmes'] = '';
	$_REQUEST['saiu47origenid'] = 0;
	$_REQUEST['saiu47dia'] = fecha_dia();
	$_REQUEST['saiu47hora'] = '';
	$_REQUEST['saiu47minuto'] = '';
	$_REQUEST['saiu47idsolicitante'] = $idTercero;
	$_REQUEST['saiu47idsolicitante_td'] = $APP->tipo_doc;
	$_REQUEST['saiu47idsolicitante_doc'] = '';
	$_REQUEST['saiu47idperiodo'] = 0;
	list($idEscuela, $idPrograma, $idZona, $idCentro, $sDebugP) = f111_ProgramaCentro($idTercero, $objDB, $bDebug);
	$_REQUEST['saiu47idescuela'] = $idEscuela;
	$_REQUEST['saiu47idprograma'] = $idPrograma;
	$_REQUEST['saiu47idzona'] = $idZona;
	$_REQUEST['saiu47idcentro'] = $idCentro;
	$_REQUEST['saiu47estado'] = 0;
	$_REQUEST['saiu47t1idmotivo'] = '';
	$_REQUEST['saiu47t1vrsolicitado'] = '';
	$_REQUEST['saiu47t1vraprobado'] = '';
	$_REQUEST['saiu47t1vrsaldoafavor'] = '';
	$_REQUEST['saiu47t1vrdevolucion'] = '';
	$_REQUEST['saiu47idbenefdevol'] = 0; //$idTercero;
	$_REQUEST['saiu47idbenefdevol_td'] = $APP->tipo_doc;
	$_REQUEST['saiu47idbenefdevol_doc'] = '';
	$_REQUEST['saiu47idaprueba'] = 0; //$idTercero;
	$_REQUEST['saiu47idaprueba_td'] = $APP->tipo_doc;
	$_REQUEST['saiu47idaprueba_doc'] = '';
	$_REQUEST['saiu47fechaaprueba'] = ''; //fecha_hoy();
	$_REQUEST['saiu47horaaprueba'] = '';
	$_REQUEST['saiu47minutoaprueba'] = '';
	$_REQUEST['saiu47detalle'] = '';
	$_REQUEST['saiu47idunidad'] = '';
	$_REQUEST['saiu47idgrupotrabajo'] = '';
	$_REQUEST['saiu47idresponsable'] = 0; //$idTercero;
	$_REQUEST['saiu47t707fecha'] = 0;
	$_REQUEST['saiu47t707formarecaudo'] = 0;
	$_REQUEST['saiu47t707identidadconv'] = 0;
	$_REQUEST['saiu47t707idbanco'] = 0;
	$_REQUEST['saiu47t707idcuenta'] = 0;
	$_REQUEST['paso'] = 0;
}
if ($bLimpiaHijos) {
	$_REQUEST['saiu48idtramite'] = '';
	$_REQUEST['saiu48consec'] = '';
	$_REQUEST['saiu48id'] = '';
	$_REQUEST['saiu48visiblealinteresado'] = 1;
	$_REQUEST['saiu48anotacion'] = '';
	$_REQUEST['saiu48idusuario'] = 0; //$idTercero;
	$_REQUEST['saiu48idusuario_td'] = $APP->tipo_doc;
	$_REQUEST['saiu48idusuario_doc'] = '';
	$_REQUEST['saiu48fecha'] = '';
	//$_REQUEST['saiu48fecha'] = $iHoy;
	$_REQUEST['saiu48hora'] = fecha_hora();
	$_REQUEST['saiu48minuto'] = fecha_minuto();
	$_REQUEST['saiu49idtramite'] = '';
	$_REQUEST['saiu49consec'] = '';
	$_REQUEST['saiu49id'] = '';
	$_REQUEST['saiu49idresponsable'] = 0; //$idTercero;
	$_REQUEST['saiu49idresponsable_td'] = $APP->tipo_doc;
	$_REQUEST['saiu49idresponsable_doc'] = '';
	$_REQUEST['saiu49idestadorigen'] = '';
	$_REQUEST['saiu49idestadofin'] = '';
	$_REQUEST['saiu49detalle'] = '';
	$_REQUEST['saiu49usuario'] = $idTercero;
	$_REQUEST['saiu49usuario_td'] = $APP->tipo_doc;
	$_REQUEST['saiu49usuario_doc'] = '';
	$_REQUEST['saiu49fecha'] = '';
	//$_REQUEST['saiu49fecha'] = $iHoy;
	$_REQUEST['saiu49hora'] = fecha_hora();
	$_REQUEST['saiu49minuto'] = fecha_minuto();
	$_REQUEST['saiu49correterminos'] = 0;
	$_REQUEST['saiu49tiempousado'] = 0;
	$_REQUEST['saiu49tiempocalusado'] = 0;
	$_REQUEST['saiu59idtramite'] = '';
	$_REQUEST['saiu59consec'] = '';
	$_REQUEST['saiu59id'] = '';
	$_REQUEST['saiu59idtipodoc'] = '';
	$_REQUEST['saiu59opcional'] = 0;
	$_REQUEST['saiu59idestado'] = 0;
	$_REQUEST['saiu59idorigen'] = 0;
	$_REQUEST['saiu59idarchivo'] = 0;
	$_REQUEST['saiu59idusuario'] = $idTercero;
	$_REQUEST['saiu59idusuario_td'] = $APP->tipo_doc;
	$_REQUEST['saiu59idusuario_doc'] = '';
	$_REQUEST['saiu59fecha'] = '';
	//$_REQUEST['saiu59fecha'] = $iHoy;
	$_REQUEST['saiu59idrevisa'] = $idTercero;
	$_REQUEST['saiu59idrevisa_td'] = $APP->tipo_doc;
	$_REQUEST['saiu59idrevisa_doc'] = '';
	$_REQUEST['saiu59fecharevisa'] = '';
	//$_REQUEST['saiu59fecharevisa'] = $iHoy;
}
//AQUI SE DEBEN CARGAR TODOS LOS DATOS QUE LA FORMA NECESITE.
$bPuedeGuardar = true;
$bConEliminar = false;
$bConBotonCerrar = false;
$bPuedeAbrir = false;
$bHayImprimir = false;
$bHayImprimir2 = false;
$sScriptImprime = 'imprimelista()';
$sScriptImprime2 = 'imprimep()';
$sClaseImprime = 'iExcel';
$sClaseImprime2 = 'iPdf';
if ($iPiel == 0) {
	$sClaseImprime = 'btEnviarExcel';
	$sClaseImprime2 = 'btEnviarPdf';
}
$iAgno = fecha_agno();
$sTabla = 'saiu47tramites_' . $iAgno;
if (!$objDB->bexistetabla($sTabla)) {
	list($sErrorT, $sDebugT) = f3000_TablasMes($iAgno, fecha_mes(), $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugT;
}
$bAplicaPeriodo = true;
$bConFechaTramite = false;
$bEnProceso = true;
//Permisos adicionales
$seg_5 = 0;
$seg_6 = 0;
if ((int)$_REQUEST['paso'] != 0) {
	$bDevuelve = false;
	//list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 5, $idTercero, $objDB);
	if ($bDevuelve) {
		$seg_5 = 1;
	}
	switch ($_REQUEST['saiu47estado']) {
		case 0: //Borrador
		case 5: //Devuelto
			$bEnProceso = false;
			break;
	}
}
//DATOS PARA COMPLETAR EL FORMULARIO
$sNombreUsuario = '';
//Crear los controles que requieran llamado a base de datos
$objCombos = new clsHtmlCombos();
$objForma = new clsHtmlForma($iPiel);
$objTercero = new clsHtmlTercero();
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
$saiu47agno_nombre = $_REQUEST['saiu47agno'];
$html_saiu47agno = html_oculto('saiu47agno', $_REQUEST['saiu47agno'], $saiu47agno_nombre);
$saiu47mes_nombre = strtoupper(fecha_mes_nombre((int)$_REQUEST['saiu47mes']));
$html_saiu47mes = html_oculto('saiu47mes', $_REQUEST['saiu47mes'], $saiu47mes_nombre);
$saiu47dia_nombre = $_REQUEST['saiu47dia'];
$html_saiu47dia = html_oculto('saiu47dia', $_REQUEST['saiu47dia'], $saiu47dia_nombre);
list($saiu47tiporadicado_nombre, $sErrorDet) = tabla_campoxid('saiu16tiporadicado', 'saiu16nombre', 'saiu16id', $_REQUEST['saiu47tiporadicado'], '{' . $ETI['msg_sindato'] . '}', $objDB);
$html_saiu47tiporadicado = html_oculto('saiu47tiporadicado', $_REQUEST['saiu47tiporadicado'], $saiu47tiporadicado_nombre);
list($saiu47tipotramite_nombre, $sErrorDet) = tabla_campoxid('saiu46tipotramite', 'saiu46nombre', 'saiu46id', $_REQUEST['saiu47tipotramite'], '{' . $ETI['msg_sindato'] . '}', $objDB);
$html_saiu47tipotramite = html_oculto('saiu47tipotramite', $_REQUEST['saiu47tipotramite'], $saiu47tipotramite_nombre);
list($saiu47estado_nombre, $sErrorDet) = tabla_campoxid('saiu60estadotramite', 'saiu60nombre', 'saiu60id', $_REQUEST['saiu47estado'], '{' . $ETI['msg_sindato'] . '}', $objDB);
$html_saiu47estado = html_oculto('saiu47estado', $_REQUEST['saiu47estado'], $saiu47estado_nombre);
list($saiu47idsolicitante_rs, $_REQUEST['saiu47idsolicitante'], $_REQUEST['saiu47idsolicitante_td'], $_REQUEST['saiu47idsolicitante_doc']) = html_tercero($_REQUEST['saiu47idsolicitante_td'], $_REQUEST['saiu47idsolicitante_doc'], $_REQUEST['saiu47idsolicitante'], 0, $objDB);
$bOculto = true;
$html_saiu47idsolicitante = html_DivTerceroV8('saiu47idsolicitante', $_REQUEST['saiu47idsolicitante_td'], $_REQUEST['saiu47idsolicitante_doc'], $bOculto, $objDB, $objCombos, 0, $ETI['ing_doc']);
if ($bAplicaPeriodo) {
	if (false) {
		list($saiu47idperiodo_nombre, $sErrorDet) = tabla_campoxid('exte02per_aca', 'exte02nombre', 'exte02id', $_REQUEST['saiu47idperiodo'], '{' . $ETI['msg_sindato'] . '}', $objDB);
		$html_saiu47idperiodo = html_oculto('saiu47idperiodo', $_REQUEST['saiu47idperiodo'], $saiu47idperiodo_nombre);
	} else {
		$objCombos->nuevo('saiu47idperiodo', $_REQUEST['saiu47idperiodo'], true, '{' . $ETI['msg_ninguno'] . '}', 0);
		$objCombos->iAncho = 380;
		$sIds = '-99';
		$sSQL = 'SELECT core16peraca FROM core16actamatricula WHERE core16tercero=' . $idTercero . ' GROUP BY core16peraca';
		$tabla = $objDB->ejecutasql($sSQL);
		while ($fila = $objDB->sf($tabla)) {
			$sIds = $sIds . ',' . $fila['core16peraca'];
		}
		$sSQL = f146_ConsultaCombo('exte02id IN (' . $sIds . ')');
		$html_saiu47idperiodo = $objCombos->html($sSQL, $objDB);
	}
}
$saiu47idescuela_nombre = '{' . $ETI['msg_ninguna'] . '}';
if ((int)$_REQUEST['saiu47idescuela'] != 0) {
	list($saiu47idescuela_nombre, $sErrorDet) = tabla_campoxid('core12escuela', 'core12nombre', 'core12id', $_REQUEST['saiu47idescuela'], '{' . $ETI['msg_sindato'] . '}', $objDB);
}
$html_saiu47idescuela = html_oculto('saiu47idescuela', $_REQUEST['saiu47idescuela'], $saiu47idescuela_nombre);
$saiu47idprograma_nombre = '&nbsp;';
if ((int)$_REQUEST['saiu47idprograma'] != 0) {
	list($saiu47idprograma_nombre, $sErrorDet) = tabla_campoxid('core09programa', 'core09nombre', 'core09id', $_REQUEST['saiu47idprograma'], '{' . $ETI['msg_sindato'] . '}', $objDB);
}
$html_saiu47idprograma = html_oculto('saiu47idprograma', $_REQUEST['saiu47idprograma'], $saiu47idprograma_nombre);
$saiu47idzona_nombre = '&nbsp;';
if ((int)$_REQUEST['saiu47idzona'] != 0) {
	list($saiu47idzona_nombre, $sErrorDet) = tabla_campoxid('unad23zona', 'unad23nombre', 'unad23id', $_REQUEST['saiu47idzona'], '{' . $ETI['msg_sindato'] . '}', $objDB);
}
$html_saiu47idzona = html_oculto('saiu47idzona', $_REQUEST['saiu47idzona'], $saiu47idzona_nombre);
$saiu47idcentro_nombre = '&nbsp;';
if ((int)$_REQUEST['saiu47idcentro'] != 0) {
	list($saiu47idcentro_nombre, $sErrorDet) = tabla_campoxid('unad24sede', 'unad24nombre', 'unad24id', $_REQUEST['saiu47idcentro'], '{' . $ETI['msg_sindato'] . '}', $objDB);
}
$html_saiu47idcentro = html_oculto('saiu47idcentro', $_REQUEST['saiu47idcentro'], $saiu47idcentro_nombre);

$html_Detsaiu47t1idmotivo = f3047_InfoDetalle($_REQUEST['saiu47t1idmotivo'], $objDB);
$objCombos->nuevo('saiu47t1idmotivo', $_REQUEST['saiu47t1idmotivo'], true, '{' . $ETI['msg_seleccione'] . '}');
$objCombos->iAncho = 280;
$objCombos->sAccion = 'verdetallemotivo()';
$sSQL = 'SELECT saiu50id AS id, saiu50nombre AS nombre FROM saiu50motivotramite WHERE saiu50idtipotram=' . $iTipoTramite . ' ORDER BY saiu50orden, saiu50nombre';
$html_saiu47t1idmotivo = $objCombos->html($sSQL, $objDB);
list($saiu47idbenefdevol_rs, $_REQUEST['saiu47idbenefdevol'], $_REQUEST['saiu47idbenefdevol_td'], $_REQUEST['saiu47idbenefdevol_doc']) = html_tercero($_REQUEST['saiu47idbenefdevol_td'], $_REQUEST['saiu47idbenefdevol_doc'], $_REQUEST['saiu47idbenefdevol'], 0, $objDB);
$bOculto = true;
$html_saiu47idbenefdevol = html_DivTerceroV8('saiu47idbenefdevol', $_REQUEST['saiu47idbenefdevol_td'], $_REQUEST['saiu47idbenefdevol_doc'], $bOculto, $objDB, $objCombos, 0, $ETI['ing_doc']);
list($saiu47idaprueba_rs, $_REQUEST['saiu47idaprueba'], $_REQUEST['saiu47idaprueba_td'], $_REQUEST['saiu47idaprueba_doc']) = html_tercero($_REQUEST['saiu47idaprueba_td'], $_REQUEST['saiu47idaprueba_doc'], $_REQUEST['saiu47idaprueba'], 0, $objDB);
$bOculto = true;
$html_saiu47idaprueba = html_DivTerceroV8('saiu47idaprueba', $_REQUEST['saiu47idaprueba_td'], $_REQUEST['saiu47idaprueba_doc'], $bOculto, $objDB, $objCombos, 0, $ETI['ing_doc']);
if ((int)$_REQUEST['paso'] == 0) {
} else {
	list($saiu48idusuario_rs, $_REQUEST['saiu48idusuario'], $_REQUEST['saiu48idusuario_td'], $_REQUEST['saiu48idusuario_doc']) = html_tercero($_REQUEST['saiu48idusuario_td'], $_REQUEST['saiu48idusuario_doc'], $_REQUEST['saiu48idusuario'], 0, $objDB);
	/*
	list($saiu49idresponsable_rs, $_REQUEST['saiu49idresponsable'], $_REQUEST['saiu49idresponsable_td'], $_REQUEST['saiu49idresponsable_doc']) = html_tercero($_REQUEST['saiu49idresponsable_td'], $_REQUEST['saiu49idresponsable_doc'], $_REQUEST['saiu49idresponsable'], 0, $objDB);
	$objCombos->nuevo('saiu49idestadorigen', $_REQUEST['saiu49idestadorigen'], true, '{' . $ETI['msg_seleccione'] . '}');
	$sSQL = 'SELECT saiu60id AS id, saiu60nombre AS nombre FROM saiu60estadotramite ORDER BY saiu60nombre';
	$html_saiu49idestadorigen = $objCombos->html($sSQL, $objDB);
	$objCombos->nuevo('saiu49idestadofin', $_REQUEST['saiu49idestadofin'], true, '{' . $ETI['msg_seleccione'] . '}');
	$sSQL = 'SELECT saiu60id AS id, saiu60nombre AS nombre FROM saiu60estadotramite ORDER BY saiu60nombre';
	$html_saiu49idestadofin = $objCombos->html($sSQL, $objDB);
	list($saiu49usuario_rs, $_REQUEST['saiu49usuario'], $_REQUEST['saiu49usuario_td'], $_REQUEST['saiu49usuario_doc']) = html_tercero($_REQUEST['saiu49usuario_td'], $_REQUEST['saiu49usuario_doc'], $_REQUEST['saiu49usuario'], 0, $objDB);
	$saiu49correterminos_nombre = $_REQUEST['saiu49correterminos'];
	//list($saiu49correterminos_nombre, $sErrorDet)=tabla_campoxid('','','',$_REQUEST['saiu49correterminos'],'{'.$ETI['msg_sindato'].'}', $objDB);
	$html_saiu49correterminos = html_oculto('saiu49correterminos', $_REQUEST['saiu49correterminos'], $saiu49correterminos_nombre);
	$objCombos->nuevo('saiu59idtipodoc', $_REQUEST['saiu59idtipodoc'], true, '{' . $ETI['msg_seleccione'] . '}');
	$sSQL = 'SELECT saiu51id AS id, saiu51nombre AS nombre FROM saiu51tramitedoc ORDER BY saiu51nombre';
	$html_saiu59idtipodoc = $objCombos->html($sSQL, $objDB);
	$objCombos->nuevo('saiu59opcional', $_REQUEST['saiu59opcional'], true, $ETI['no'], 0);
	$objCombos->addItem(1, $ETI['si']);
	//$objCombos->addArreglo($asaiu59opcional, $isaiu59opcional);
	$html_saiu59opcional = $objCombos->html('', $objDB);
	list($saiu59idestado_nombre, $sErrorDet) = tabla_campoxid('saiu60estadotramite', 'saiu60nombre', 'saiu60id', $_REQUEST['saiu59idestado'], '{' . $ETI['msg_sindato'] . '}', $objDB);
	$html_saiu59idestado = html_oculto('saiu59idestado', $_REQUEST['saiu59idestado'], $saiu59idestado_nombre);
	list($saiu59idusuario_rs, $_REQUEST['saiu59idusuario'], $_REQUEST['saiu59idusuario_td'], $_REQUEST['saiu59idusuario_doc']) = html_tercero($_REQUEST['saiu59idusuario_td'], $_REQUEST['saiu59idusuario_doc'], $_REQUEST['saiu59idusuario'], 0, $objDB);
	list($saiu59idrevisa_rs, $_REQUEST['saiu59idrevisa'], $_REQUEST['saiu59idrevisa_td'], $_REQUEST['saiu59idrevisa_doc']) = html_tercero($_REQUEST['saiu59idrevisa_td'], $_REQUEST['saiu59idrevisa_doc'], $_REQUEST['saiu59idrevisa'], 0, $objDB);
	*/
}
//Alistar datos adicionales
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
$iNumFormatosImprime = 0;
$iModeloReporte = 3047;
$html_iFormatoImprime = '<input id="iformatoimprime" name="iformatoimprime" type="hidden" value="0" />
';
//Cargar las tablas de datos
$aParametros[0] = ''; //$_REQUEST['p1_3047'];
$aParametros[97] = $iTipoTramite;
$aParametros[98] = $idTercero;
$aParametros[100] = $idTercero;
$aParametros[101] = $_REQUEST['paginaf3047'];
$aParametros[102] = $_REQUEST['lppf3047'];
list($sTabla3047, $sDebugTabla) = f3047_TablaDetalleV2Tercero($aParametros, $objDB, $bDebug);
$sDebug = $sDebug . $sDebugTabla;
$sTabla3000 = '';
$sTabla3048 = '';
$sTabla3049 = '';
$sTabla3059 = '';
if ($_REQUEST['paso'] != 0) {
	//Anotaciones
	$aParametros3048[0] = $_REQUEST['saiu47id'];
	$aParametros3048[98] = $_REQUEST['saiu47agno'];
	$aParametros3048[100] = $idTercero;
	$aParametros3048[101] = $_REQUEST['paginaf3048'];
	$aParametros3048[102] = $_REQUEST['lppf3048'];
	//$aParametros3048[103]=$_REQUEST['bnombre3048'];
	//$aParametros3048[104]=$_REQUEST['blistar3048'];
	list($sTabla3048, $sDebugTabla) = f3048_TablaDetalleV2Campus($aParametros3048, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugTabla;
	//Cambios de estado
	$aParametros3049[0] = $_REQUEST['saiu47id'];
	$aParametros3049[98] = $_REQUEST['saiu47agno'];
	$aParametros3049[100] = $idTercero;
	$aParametros3049[101] = $_REQUEST['paginaf3049'];
	$aParametros3049[102] = $_REQUEST['lppf3049'];
	//$aParametros3049[103]=$_REQUEST['bnombre3049'];
	//$aParametros3049[104]=$_REQUEST['blistar3049'];
	list($sTabla3049, $sDebugTabla) = f3049_TablaDetalleV2($aParametros3049, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugTabla;
	//Anexos
	$aParametros3059[0] = $_REQUEST['saiu47id'];
	$aParametros3059[98] = $_REQUEST['saiu47agno'];
	$aParametros3059[100] = $idTercero;
	$aParametros3059[101] = $_REQUEST['paginaf3059'];
	$aParametros3059[102] = $_REQUEST['lppf3059'];
	//$aParametros3059[103]=$_REQUEST['bnombre3059'];
	//$aParametros3059[104]=$_REQUEST['blistar3059'];
	list($sTabla3059, $sDebugTabla) = f3059_TablaDetalleV2Campus($aParametros3059, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugTabla;
}
switch ($iPiel) {
	case 2:
		list($et_menu, $sDebugM) = html_menuCampusV2($objDB, $iPiel, $bDebugMenu, $idTercero);
		break;
	default:
		list($et_menu, $sDebugM) = html_menuCampus($APP->idsistema, $objDB, $iPiel, $bDebugMenu, $idTercero);
		break;
}
$sDebug = $sDebug . $sDebugM;
$objDB->CerrarConexion();
//FORMA
switch ($iPiel) {
	case 2:
		require $APP->rutacomun . 'unad_forma2023.php';
		forma_InicioV4($xajax, $sTituloModulo);
		$aRutas = array(
			array('', $sTituloModulo)
		);
		$iNumBoton = 0;
		$aBotones[$iNumBoton] = array('muestraayuda(' . $APP->idsistema . ', ' . $iCodModulo . ')', $ETI['bt_ayuda'], 'iHelp');
		$iNumBoton++;
		$aBotones[$iNumBoton] = array('limpiapagina()', $ETI['bt_limpiar'], 'iDocument');
		$iNumBoton++;
		if ($bPuedeGuardar) {
			$aBotones[$iNumBoton] = array('enviaguardar()', $ETI['bt_guardar'], 'iSaveFill');
			$iNumBoton++;
		}
		$aBotones[$iNumBoton] = array('volver()', $ETI['bt_volver'], 'iArrowBack');
		$iNumBoton++;
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
switch ($iPiel) {
	case 2:
?>
		document.getElementById('botones_sector1').style.display = 'flex';
		document.getElementById('botones_sector97').style.display = 'none';
		switch (codigo) {
			case 1:
				break;
			case 97:
				document.getElementById('botones_sector1').style.display = 'none';
				document.getElementById('botones_sector' + codigo).style.display = 'flex';
				break;
			default:
				document.getElementById('botones_sector1').style.display = 'none';
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

	function cargaridf3047(llave1, llave2) {
		document.getElementById('div_saiu47agno').innerHTML = '<input id="saiu47agno" name="saiu47agno" type="hidden" value="' + llave1 + '" />';
		window.document.frmedita.saiu47id.value = String(llave2);
		window.document.frmedita.paso.value = 3;
		window.document.frmedita.submit();
	}

	function paginarf3047() {
		var params = new Array();
		params[97] = <?php echo $iTipoTramite; ?>;
		params[98] = <?php echo $idTercero; ?>;
		params[99] = window.document.frmedita.debug.value;
		params[100] = <?php echo $idTercero; ?>;
		params[101] = window.document.frmedita.paginaf3047.value;
		params[102] = window.document.frmedita.lppf3047.value;
		//params[103]=window.document.frmedita.bnombre.value;
		//params[104]=window.document.frmedita.blistar.value;
		//document.getElementById('div_f3047detalle').innerHTML='<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf3047" name="paginaf3047" type="hidden" value="'+params[101]+'" /><input id="lppf3047" name="lppf3047" type="hidden" value="'+params[102]+'" />';
		xajax_f3047_HtmlTablaTercero(params);
	}

	function enviacerrar() {
		ModalConfirmV2('Esta seguro de radicar la solicitud?<br>Previamente debe haber cargado los soportes obligatorios.', () => {
			ejecuta_enviacerrar();
		});
	}

	function ejecuta_enviacerrar() {
		MensajeAlarmaV2('<?php echo $ETI['msg_ejecutando']; ?>', 2);
		expandesector(98);
		window.document.frmedita.paso.value = 16;
		window.document.frmedita.submit();
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

	function retornacontrol() {
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
		if (ref == 3059) {
			paginarf3059();
		}
		MensajeAlarmaV2('' + ref, 0);
		retornacontrol();
	}

	function volver() {
		window.document.frmvolver.submit();
	}

	function verdetallemotivo() {
		var params = new Array();
		params[1] = window.document.frmedita.saiu47t1idmotivo.value;
		xajax_f3047_Detsaiu47t1idmotivo(params);
	}
</script>
<form id="frmvolver" name="frmvolver" method="post" action="sai.php" autocomplete="off" style="display:none">
</form>
<?php
if ($_REQUEST['paso'] != 0) {
?>
<script language="javascript" src="jsi/js3048campus.js?v=2b"></script>
<script language="javascript" src="jsi/js3049.js?v=2"></script>
<script language="javascript" src="jsi/js3059campus.js"></script>
<form id="frmimpp" name="frmimpp" method="post" action="p3047.php" target="_blank" style="display:none">
<input id="r" name="r" type="hidden" value="3047" />
<input id="id3047" name="id3047" type="hidden" value="<?php echo $_REQUEST['saiu47id']; ?>" />
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
?>
<div class="titulos">
<div class="titulosD">
<input id="cmdAyuda" name="cmdAyuda" type="button" class="btUpAyuda" onclick="muestraayuda(<?php echo $APP->idsistema . ', ' . $iCodModulo; ?>);" title="<?php echo $ETI['bt_ayuda']; ?>" value="<?php echo $ETI['bt_ayuda']; ?>" />
<input id="cmdLimpiar" name="cmdLimpiar" type="button" class="btUpLimpiar" onclick="limpiapagina();" title="<?php echo $ETI['bt_limpiar']; ?>" value="<?php echo $ETI['bt_limpiar']; ?>" />
<input id="cmdVolverSec2" name="cmdVolverSec2" type="button" class="btSupVolver" onclick="volver();" title="<?php echo $ETI['bt_volver']; ?>" value="<?php echo $ETI['bt_volver']; ?>" />
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
$bConExpande = false;
if ($bConExpande) {
?>
<div class="ir_derecha"<?php echo $sAnchoExpandeContrae; ?>>
<?php
echo $objForma->htmlExpande(3047, $_REQUEST['boculta3047'], $ETI['bt_mostrar'], $ETI['bt_ocultar']);
$sEstiloDiv = '';
if ($_REQUEST['boculta3047'] != 0) {
	$sEstiloDiv = ' style="display:none;"';
}
?>
</div>
<div id="div_p3047"<?php echo $sEstiloDiv; ?>>
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
if (false) {
?>
<label class="Label60">
<?php
echo $html_saiu47dia;
?>
</label>
<label class="Label130">
<?php
echo $html_saiu47mes;
?>
</label>
<label class="Label90">
<div id="div_saiu47agno">
<?php
echo $html_saiu47agno;
?>
</div>
</label>
<?php
} else {
?>
<label class="Label220">
<div id="div_saiu47agno">
<?php
echo $html_saiu47dia . '/' . $html_saiu47mes . '/' . $html_saiu47agno;
?>
</div>
</label>
<?php
}
?>
<label class="Label60">
<?php
echo $ETI['saiu47hora'];
?>
</label>
<div class="campo_HoraMin" id="div_saiu47hora">
<?php
echo html_HoraMin('saiu47hora', $_REQUEST['saiu47hora'], 'saiu47minuto', $_REQUEST['saiu47minuto'], true);
?>
</div>
<div class="salto1px"></div>
<input id="saiu47tiporadicado" name="saiu47tiporadicado" type="hidden" value="<?php echo $_REQUEST['saiu47tiporadicado']; ?>" />
<input id="saiu47tipotramite" name="saiu47tipotramite" type="hidden" value="<?php echo $_REQUEST['saiu47tipotramite']; ?>" />
<label class="Label130">
<?php
echo $ETI['saiu47consec'];
?>
</label>
<label class="Label130">
<?php
if (false) {
?>
<input id="saiu47consec" name="saiu47consec" type="text" value="<?php echo $_REQUEST['saiu47consec']; ?>" onchange="RevisaLlave()" class="cuatro" />
<?php
} else {
echo html_oculto('saiu47consec', $_REQUEST['saiu47consec'], formato_numero($_REQUEST['saiu47consec']));
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
echo $ETI['saiu47id'];
?>
</label>
<label class="Label60"<?php echo $sOcultaId; ?>>
<?php
echo html_oculto('saiu47id', $_REQUEST['saiu47id'], formato_numero($_REQUEST['saiu47id']));
?>
</label>
<input id="saiu47origenagno" name="saiu47origenagno" type="hidden" value="<?php echo $_REQUEST['saiu47origenagno']; ?>" />
<input id="saiu47origenmes" name="saiu47origenmes" type="hidden" value="<?php echo $_REQUEST['saiu47origenmes']; ?>" />
<input id="saiu47origenid" name="saiu47origenid" type="hidden" value="<?php echo $_REQUEST['saiu47origenid']; ?>" />
<label class="Label90">
<?php
echo $ETI['saiu47estado'];
?>
</label>
<label class="Label160">
<div id="div_saiu47estado">
<?php
echo $html_saiu47estado;
?>
</div>
</label>

<div class="salto1px"></div>
<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo $ETI['saiu47idsolicitante'];
?>
</label>
<div class="salto1px"></div>
<input id="saiu47idsolicitante" name="saiu47idsolicitante" type="hidden" value="<?php echo $_REQUEST['saiu47idsolicitante']; ?>" />
<div id="div_saiu47idsolicitante_llaves">
<?php
echo $html_saiu47idsolicitante;
?>
</div>
<div class="salto1px"></div>
<div id="div_saiu47idsolicitante" class="L"><?php echo $saiu47idsolicitante_rs; ?></div>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu47t1idmotivo'];
?>
</label>
<label class="Label250">
<div id="div_saiu47t1idmotivo">
<?php
echo $html_saiu47t1idmotivo;
?>
</div>
<div class="salto1px"></div>
</label>
<label class="Label130">
<?php
echo $ETI['saiu47t1vrsolicitado'];
?>
</label>
<label class="Label160">
<input id="saiu47t1vrsolicitado" name="saiu47t1vrsolicitado" type="text" value="<?php echo formato_numero($_REQUEST['saiu47t1vrsolicitado'], 2); ?>" class="veinte" maxlength="15" style="text-align:right" onchange="formatea_moneda(this);" placeholder="<?php echo $ETI['ing_vr']; ?>" />
</label>
<div class="salto1px"></div>
</div>

<div class="GrupoCampos520">
<label class="Label90">
<?php
echo $ETI['saiu47idzona'];
?>
</label>
<label class="Label380">
<div id="div_saiu47idzona">
<?php
echo $html_saiu47idzona;
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label90">
<?php
echo $ETI['saiu47idcentro'];
?>
</label>
<label class="Label380">
<div id="div_saiu47idcentro">
<?php
echo $html_saiu47idcentro;
?>
</div>
</label>
<?php
if ($bAplicaPeriodo) {
?>
<div class="salto1px"></div>
<label class="Label90">
<?php
echo $ETI['saiu47idperiodo'];
?>
</label>
<label class="Label380">
<div id="div_saiu47idperiodo">
<?php
echo $html_saiu47idperiodo;
?>
</div>
</label>
<?php
} else {
?>
<input id="saiu47idperiodo" name="saiu47idperiodo" type="hidden" value="<?php echo $_REQUEST['saiu47idperiodo']; ?>" />
<?php
}
?>
<div class="salto1px"></div>
<label class="Label90">
<?php
echo $ETI['saiu47idescuela'];
?>
</label>
<label class="Label380">
<div id="div_saiu47idescuela">
<?php
echo $html_saiu47idescuela;
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label90">
<?php
echo $ETI['saiu47idprograma'];
?>
</label>
<label class="Label380">
<div id="div_saiu47idprograma">
<?php
echo $html_saiu47idprograma;
?>
</div>
</label>
<div class="salto1px"></div>
</div>

<div class="salto1px"></div>
<div id="div_Detsaiu47t1idmotivo">
<?php
echo $html_Detsaiu47t1idmotivo;
?>
<div class="salto1px"></div>
</div>

<?php
if ($bConFechaTramite) {
?>
<div class="salto1px"></div>
<label class="Label90">
<?php
echo $ETI['saiu47t707fecha'];
?>
</label>
<div class="Campo220">
<?php
echo html_FechaEnNumero('saiu47t707fecha', $_REQUEST['saiu47t707fecha'], true, '', 2021, $iAgno);
?>
</div>
<?php
} else {
?>
<input id="saiu47t707fecha" name="saiu47t707fecha" type="hidden" value="<?php echo $_REQUEST['saiu47t707fecha']; ?>" />
<?php
}
?>
<input id="saiu47t707formarecaudo" name="saiu47t707formarecaudo" type="hidden" value="<?php echo $_REQUEST['saiu47t707formarecaudo']; ?>" />
<input id="saiu47t707identidadconv" name="saiu47t707identidadconv" type="hidden" value="<?php echo $_REQUEST['saiu47t707identidadconv']; ?>" />
<input id="saiu47t707idbanco" name="saiu47t707idbanco" type="hidden" value="<?php echo $_REQUEST['saiu47t707idbanco']; ?>" />
<input id="saiu47t707idcuenta" name="saiu47t707idcuenta" type="hidden" value="<?php echo $_REQUEST['saiu47t707idcuenta']; ?>" />
<div class="salto1px"></div>
<label class="txtAreaS">
<?php
echo $ETI['saiu47detalle'];
?>
<textarea id="saiu47detalle" name="saiu47detalle" placeholder="<?php echo $ETI['ing_campo'] . $ETI['saiu47detalle']; ?>"><?php echo $_REQUEST['saiu47detalle']; ?></textarea>
</label>
<div class="salto1px"></div>
<?php
if (false) {
?>
<div class="GrupoCampos450">
<label class="Label130">
<?php
echo $ETI['saiu47t1vraprobado'];
?>
</label>
<label class="Label160">
<?php
echo html_oculto('saiu47t1vraprobado', $_REQUEST['saiu47t1vraprobado']);
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu47t1vrsaldoafavor'];
?>
</label>
<label class="Label160">
<?php
echo html_oculto('saiu47t1vrsaldoafavor', $_REQUEST['saiu47t1vrsaldoafavor']);
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu47t1vrdevolucion'];
?>
</label>
<label class="Label160">
<?php
echo html_oculto('saiu47t1vrdevolucion', $_REQUEST['saiu47t1vrdevolucion']);
?>
</label>
<div class="salto1px"></div>
</div>

<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo $ETI['saiu47idbenefdevol'];
?>
</label>
<div class="salto1px"></div>
<input id="saiu47idbenefdevol" name="saiu47idbenefdevol" type="hidden" value="<?php echo $_REQUEST['saiu47idbenefdevol']; ?>" />
<div id="div_saiu47idbenefdevol_llaves">
<?php
echo $html_saiu47idbenefdevol;
?>
</div>
<div class="salto1px"></div>
<div id="div_saiu47idbenefdevol" class="L"><?php echo $saiu47idbenefdevol_rs; ?></div>
<div class="salto1px"></div>
</div>
<?php
} else {
?>
<input id="saiu47t1vraprobado" name="saiu47t1vraprobado" type="hidden" value="<?php echo $_REQUEST['saiu47t1vraprobado']; ?>" />
<input id="saiu47t1vrsaldoafavor" name="saiu47t1vrsaldoafavor" type="hidden" value="<?php echo $_REQUEST['saiu47t1vrsaldoafavor']; ?>" />
<input id="saiu47t1vrdevolucion" name="saiu47t1vrdevolucion" type="hidden" value="<?php echo $_REQUEST['saiu47t1vrdevolucion']; ?>" />
<input id="saiu47idbenefdevol" name="saiu47idbenefdevol" type="hidden" value="<?php echo $_REQUEST['saiu47idbenefdevol']; ?>" />
<input id="saiu47idbenefdevol_td" name="saiu47idbenefdevol_td" type="hidden" value="<?php echo $_REQUEST['saiu47idbenefdevol_td']; ?>" />
<input id="saiu47idbenefdevol_doc" name="saiu47idbenefdevol_doc" type="hidden" value="<?php echo $_REQUEST['saiu47idbenefdevol_doc']; ?>" />
<?php
}
?>

<?php
if (false) {
?>
<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo $ETI['saiu47idaprueba'];
?>
</label>
<div class="salto1px"></div>
<input id="saiu47idaprueba" name="saiu47idaprueba" type="hidden" value="<?php echo $_REQUEST['saiu47idaprueba']; ?>" />
<div id="div_saiu47idaprueba_llaves">
<?php
echo $html_saiu47idaprueba;
?>
</div>
<div class="salto1px"></div>
<div id="div_saiu47idaprueba" class="L"><?php echo $saiu47idaprueba_rs; ?></div>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu47fechaaprueba'];
?>
</label>
<div class="Campo220">
<?php
echo html_oculto('saiu47fechaaprueba', $_REQUEST['saiu47fechaaprueba']); //$bvacio=false,$accion=",$iagnoini=0,$iagnofin=0
?>
</div>
<div class="campo_HoraMin" id="div_saiu47horaaprueba">
<?php
echo html_HoraMin('saiu47horaaprueba', $_REQUEST['saiu47horaaprueba'], 'saiu47minutoaprueba', $_REQUEST['saiu47minutoaprueba'], true);
?>
</div>
<div class="salto1px"></div>
</div>
<?php
} else {
?>
<input id="saiu47idaprueba" name="saiu47idaprueba" type="hidden" value="<?php echo $_REQUEST['saiu47idaprueba']; ?>" />
<input id="saiu47idaprueba_td" name="saiu47idaprueba_td" type="hidden" value="<?php echo $_REQUEST['saiu47idaprueba_td']; ?>" />
<input id="saiu47idaprueba_doc" name="saiu47idaprueba_doc" type="hidden" value="<?php echo $_REQUEST['saiu47idaprueba_doc']; ?>" />
<input id="saiu47fechaaprueba" name="saiu47fechaaprueba" type="hidden" value="<?php echo $_REQUEST['saiu47fechaaprueba']; ?>" />
<input id="saiu47horaaprueba" name="saiu47horaaprueba" type="hidden" value="<?php echo $_REQUEST['saiu47horaaprueba']; ?>" />
<input id="saiu47minutoaprueba" name="saiu47minutoaprueba" type="hidden" value="<?php echo $_REQUEST['saiu47minutoaprueba']; ?>" />
<?php
}
?>
<?php
if (false) {
?>
<label class="Label130">
<?php
echo $ETI['saiu47idunidad'];
?>
</label>
<label>
<?php
echo $html_saiu47idunidad;
?>
</label>
<label class="Label130">
<?php
echo $ETI['saiu47idgrupotrabajo'];
?>
</label>
<label>
<div id="div_saiu47idgrupotrabajo">
<?php
echo $html_saiu47idgrupotrabajo;
?>
</div>
</label>
<div class="salto1px"></div>
<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo $ETI['saiu47idresponsable'];
?>
</label>
<div class="salto1px"></div>
<input id="saiu47idresponsable" name="saiu47idresponsable" type="hidden" value="<?php echo $_REQUEST['saiu47idresponsable']; ?>" />
<div id="div_saiu47idresponsable_llaves">
<?php
$bOculto = false;
echo html_DivTerceroV2('saiu47idresponsable', $_REQUEST['saiu47idresponsable_td'], $_REQUEST['saiu47idresponsable_doc'], $bOculto, 0, $ETI['ing_doc']);
?>
</div>
<div class="salto1px"></div>
<div id="div_saiu47idresponsable" class="L"><?php echo $saiu47idresponsable_rs; ?></div>
<div class="salto1px"></div>
</div>
<?php
} else {
?>
<input id="saiu47idunidad" name="saiu47idunidad" type="hidden" value="<?php echo $_REQUEST['saiu47idunidad']; ?>" />
<input id="saiu47idgrupotrabajo" name="saiu47idgrupotrabajo" type="hidden" value="<?php echo $_REQUEST['saiu47idgrupotrabajo']; ?>" />
<input id="saiu47idresponsable" name="saiu47idresponsable" type="hidden" value="<?php echo $_REQUEST['saiu47idresponsable']; ?>" />
<input id="saiu47idresponsable_td" name="saiu47idresponsable_td" type="hidden" value="<?php echo $_REQUEST['saiu47idresponsable_td']; ?>" />
<input id="saiu47idresponsable_doc" name="saiu47idresponsable_doc" type="hidden" value="<?php echo $_REQUEST['saiu47idresponsable_doc']; ?>" />
<?php
}
?>

<input id="boculta3048" name="boculta3048" type="hidden" value="<?php echo $_REQUEST['boculta3048']; ?>" />
<?php
// -- Inicia Grupo campos 3048 Anotaciones
if ($_REQUEST['paso'] == 2) {
?>
<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo $ETI['titulo_3048'];
?>
</label>
<?php
//if ($bCondicion){
?>
<div class="ir_derecha"<?php echo $sAnchoExpandeContrae; ?>>
<?php
echo $objForma->htmlExpande(3048, $_REQUEST['boculta3048'], $ETI['bt_mostrar'], $ETI['bt_ocultar']);
$sEstiloDiv = '';
if ($_REQUEST['boculta3048'] != 0) {
	$sEstiloDiv = ' style="display:none;"';
}
?>
</div>
<div class="salto1px"></div>
<div id="div_p3048"<?php echo $sEstiloDiv; ?>>
<label class="Label130">
<?php
echo $ETI['saiu48consec'];
?>
</label>
<label class="Label130">
<div id="div_saiu48consec">
<?php
if ((int)$_REQUEST['saiu48id'] == 0) {
?>
<input id="saiu48consec" name="saiu48consec" type="text" value="<?php echo $_REQUEST['saiu48consec']; ?>" onchange="revisaf3048()" class="cuatro" />
<?php
} else {
echo html_oculto('saiu48consec', $_REQUEST['saiu48consec'], formato_numero($_REQUEST['saiu48consec']));
}
?>
</div>
</label>
<label class="Label60"<?php echo $sOcultaId; ?>>
<?php
echo $ETI['saiu48id'];
?>
</label>
<label class="Label60"<?php echo $sOcultaId; ?>>
<div id="div_saiu48id">
<?php
echo html_oculto('saiu48id', $_REQUEST['saiu48id'], formato_numero($_REQUEST['saiu48id']));
?>
</div>
</label>
<input id="saiu48visiblealinteresado" name="saiu48visiblealinteresado" type="hidden" value="<?php echo $_REQUEST['saiu48visiblealinteresado']; ?>" />

<div class="salto1px"></div>
<div class="GrupoCampos450">
<label class="txtAreaS">
<?php
echo $ETI['saiu48anotacion'];
?>
<textarea id="saiu48anotacion" name="saiu48anotacion" placeholder="<?php echo $ETI['ing_campoa'] . $ETI['saiu48anotacion']; ?>"><?php echo $_REQUEST['saiu48anotacion']; ?></textarea>
</label>
<div class="salto1px"></div>
</div>

<div class="GrupoCampos520">
<label class="TituloGrupo">
<?php
echo $ETI['saiu48idusuario'];
?>
</label>
<div class="salto1px"></div>
<input id="saiu48idusuario" name="saiu48idusuario" type="hidden" value="<?php echo $_REQUEST['saiu48idusuario']; ?>" />
<div id="div_saiu48idusuario_llaves">
<?php
$bOculto = true;
echo html_DivTerceroV2('saiu48idusuario', $_REQUEST['saiu48idusuario_td'], $_REQUEST['saiu48idusuario_doc'], $bOculto, 0, $ETI['ing_doc']);
?>
</div>
<div class="salto1px"></div>
<div id="div_saiu48idusuario" class="L"><?php echo $saiu48idusuario_rs; ?></div>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu48fecha'];
?>
</label>
<label class="Label220">
<div id="div_saiu48fecha">
<?php
echo html_oculto('saiu48fecha', $_REQUEST['saiu48fecha'], fecha_desdenumero($_REQUEST['saiu48fecha']));
?>
</div>
</label>
<div class="campo_HoraMin" id="div_saiu48hora">
<?php
echo html_HoraMin('saiu48hora', $_REQUEST['saiu48hora'], 'saiu48minuto', $_REQUEST['saiu48minuto'], true);
?>
</div>
<div class="salto1px"></div>
</div>

<div class="salto1px"></div>
<label class="Label130">&nbsp;</label>
<?php
$sEstiloElimina = ' style="display:none;"';
if ((int)$_REQUEST['saiu48id'] != 0) {
	$sEstiloElimina = ' style="inline-block;"';
}
?>
<label class="Label30">
<input id="bguarda3048" name="bguarda3048" type="button" value="Guardar" class="btMiniGuardar" onclick="guardaf3048()" title="<?php echo $ETI['bt_mini_guardar_3048']; ?>" />
</label>
<label class="Label30">
<input id="blimpia3048" name="blimpia3048" type="button" value="Limpiar" class="btMiniLimpiar" onclick="limpiaf3048()" title="<?php echo $ETI['bt_mini_limpiar_3048']; ?>" />
</label>
<label class="Label30">
<input id="belimina3048" name="belimina3048" type="button" value="Eliminar" class="btMiniEliminar" onclick="eliminaf3048()" title="<?php echo $ETI['bt_mini_eliminar_3048']; ?>"<?php echo $sEstiloElimina; ?>/>
</label>
<?php
//Este es el cierre del div_p3048
?>
<div class="salto1px"></div>
</div>
<?php
		//} //Termina el segundo bloque  condicional - bloque editar.
?>
<div class="salto1px"></div>
<?php
if (false) {
?>
<div class="ir_derecha">
<label class="Label130">
<?php
echo $ETI['msg_nombre'];
?>
</label>
<label>
<input id="bnombre3048" name="bnombre3048" type="text" value="<?php echo $_REQUEST['bnombre3048']; ?>" onchange="paginarf3048()" />
</label>
<label class="Label130">
<?php
echo $ETI['msg_Listar'];
?>
</label>
<label>
<?php
echo $html_blistar3048;
?>
</label>
<div class="salto1px"></div>
</div>
<?php
}
?>
<div id="div_f3048detalle">
<?php
echo $sTabla3048;
?>
</div>
<?php
?>
<div class="salto1px"></div>
</div>
<?php
}
// -- Termina Grupo campos 3048 Anotaciones
?>
<input id="boculta3059" name="boculta3059" type="hidden" value="<?php echo $_REQUEST['boculta3059']; ?>" />
<?php
// -- Inicia Grupo campos 3059 Anexos
if ($_REQUEST['paso'] == 2) {
?>
<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo $ETI['titulo_3059'];
?>
</label>
<?php
if (false) {
?>
<div class="ir_derecha"<?php echo $sAnchoExpandeContrae; ?>>
<?php
if (false) {
?>
<label class="Label30">
<input id="btexcel3059" name="btexcel3059" type="button" value="Exportar" class="btMiniExcel" onclick="imprime3059();" title="Exportar" />
</label>
<?php
}
$sEstiloExpande = ' style="display:none;"';
$sEstiloRecoje = '';
$sEstiloDiv = '';
if ($_REQUEST['boculta3059'] != 0) {
	$sEstiloExpande = '';
	$sEstiloRecoje = ' style="display:none;"';
	$sEstiloDiv = ' style="display:none;"';
}
?>
<label class="Label30">
<input id="btexpande3059" name="btexpande3059" type="button" value="Expandir" class="btMiniExpandir" onclick="expandepanel(3059, 'block', 0);" title="<?php echo $ETI['bt_mostrar']; ?>"<?php echo $sEstiloExpande; ?>/>
</label>
<label class="Label30">
<input id="btrecoge3059" name="btrecoge3059" type="button" value="Recoger" class="btMiniRecoger" onclick="expandepanel(3059, 'none', 1);" title="<?php echo $ETI['bt_ocultar']; ?>"<?php echo $sEstiloRecoje; ?>/>
</label>
</div>
<div class="salto1px"></div>
<div id="div_p3059"<?php echo $sEstiloDiv; ?>>
<label class="Label130">
<?php
echo $ETI['saiu59consec'];
?>
</label>
<label class="Label130">
<div id="div_saiu59consec">
<?php
if ((int)$_REQUEST['saiu59id'] == 0) {
?>
<input id="saiu59consec" name="saiu59consec" type="text" value="<?php echo $_REQUEST['saiu59consec']; ?>" onchange="revisaf3059()" class="cuatro" />
<?php
} else {
echo html_oculto('saiu59consec', $_REQUEST['saiu59consec'], formato_numero($_REQUEST['saiu59consec']));
}
?>
</div>
</label>
<label class="Label60"<?php echo $sOcultaId; ?>>
<?php
echo $ETI['saiu59id'];
?>
</label>
<label class="Label60"<?php echo $sOcultaId; ?>>
<div id="div_saiu59id">
<?php
echo html_oculto('saiu59id', $_REQUEST['saiu59id'], formato_numero($_REQUEST['saiu59id']));
?>
</div>
</label>
<label class="Label130">
<?php
echo $ETI['saiu59idtipodoc'];
?>
</label>
<label>
<?php
echo $html_saiu59idtipodoc;
?>
</label>
<label class="Label130">
<?php
echo $ETI['saiu59opcional'];
?>
</label>
<label>
<?php
echo $html_saiu59opcional;
?>
</label>
<label class="Label130">
<?php
echo $ETI['saiu59idestado'];
?>
</label>
<label>
<div id="div_saiu59idestado">
<?php
echo $html_saiu59idestado;
?>
</div>
</label>
<input id="saiu59idorigen" name="saiu59idorigen" type="hidden" value="<?php echo $_REQUEST['saiu59idorigen']; ?>" />
<input id="saiu59idarchivo" name="saiu59idarchivo" type="hidden" value="<?php echo $_REQUEST['saiu59idarchivo']; ?>" />
<div class="GrupoCampos300">
<div class="salto1px"></div>
<div id="div_saiu59idarchivo" class="Campo220">
<?php
echo html_lnkarchivo((int)$_REQUEST['saiu59idorigen'], (int)$_REQUEST['saiu59idarchivo']);
?>
</div>
<?php
$sEstiloAnexa = '';
$sEstiloElimina = ' style="display:none;"';
if ((int)$_REQUEST['saiu59id'] == 0) {
	$sEstiloAnexa = ' style="display:none;"';
}
if ((int)$_REQUEST['saiu59idarchivo'] != 0) {
	$sEstiloElimina = ' style="inline-block;"';
}
?>
<label class="Label30">
<input id="banexasaiu59idarchivo" name="banexasaiu59idarchivo" type="button" value="Anexar" class="btAnexarS" onclick="carga_saiu59idarchivo()" title="Cargar archivo"<?php echo $sEstiloAnexa; ?>/>
</label>
<label class="Label30">
<input id="beliminasaiu59idarchivo" name="beliminasaiu59idarchivo" type="button" value="Eliminar" class="btBorrarS" onclick="eliminasaiu59idarchivo()" title="Eliminar archivo"<?php echo $sEstiloElimina; ?>/>
</label>
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo $ETI['saiu59idusuario'];
?>
</label>
<div class="salto1px"></div>
<input id="saiu59idusuario" name="saiu59idusuario" type="hidden" value="<?php echo $_REQUEST['saiu59idusuario']; ?>" />
<div id="div_saiu59idusuario_llaves">
<?php
$bOculto = true;
echo html_DivTerceroV2('saiu59idusuario', $_REQUEST['saiu59idusuario_td'], $_REQUEST['saiu59idusuario_doc'], $bOculto, 0, $ETI['ing_doc']);
?>
</div>
<div class="salto1px"></div>
<div id="div_saiu59idusuario" class="L"><?php echo $saiu59idusuario_rs; ?></div>
<div class="salto1px"></div>
</div>
<label class="Label130">
<?php
echo $ETI['saiu59fecha'];
?>
</label>
<label class="Label220">
<div id="div_saiu59fecha">
<?php
echo html_oculto('saiu59fecha', $_REQUEST['saiu59fecha'], fecha_desdenumero($_REQUEST['saiu59fecha'])); //formato_FechaLargaDesdeNumero
?>
</div>
</label>
<div class="salto1px"></div>
<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo $ETI['saiu59idrevisa'];
?>
</label>
<div class="salto1px"></div>
<input id="saiu59idrevisa" name="saiu59idrevisa" type="hidden" value="<?php echo $_REQUEST['saiu59idrevisa']; ?>" />
<div id="div_saiu59idrevisa_llaves">
<?php
$bOculto = true;
echo html_DivTerceroV2('saiu59idrevisa', $_REQUEST['saiu59idrevisa_td'], $_REQUEST['saiu59idrevisa_doc'], $bOculto, 0, $ETI['ing_doc']);
?>
</div>
<div class="salto1px"></div>
<div id="div_saiu59idrevisa" class="L"><?php echo $saiu59idrevisa_rs; ?></div>
<div class="salto1px"></div>
</div>
<label class="Label130">
<?php
echo $ETI['saiu59fecharevisa'];
?>
</label>
<label class="Label220">
<div id="div_saiu59fecharevisa">
<?php
echo html_oculto('saiu59fecharevisa', $_REQUEST['saiu59fecharevisa'], fecha_desdenumero($_REQUEST['saiu59fecharevisa'])); //formato_FechaLargaDesdeNumero
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label130">&nbsp;</label>
<?php
$sEstiloElimina = ' style="display:none;"';
if ((int)$_REQUEST['saiu59id'] != 0) {
	$sEstiloElimina = ' style="inline-block;"';
}
?>
<label class="Label30">
<input id="bguarda3059" name="bguarda3059" type="button" value="Guardar" class="btMiniGuardar" onclick="guardaf3059()" title="<?php echo $ETI['bt_mini_guardar_3059']; ?>" />
</label>
<label class="Label30">
<input id="blimpia3059" name="blimpia3059" type="button" value="Limpiar" class="btMiniLimpiar" onclick="limpiaf3059()" title="<?php echo $ETI['bt_mini_limpiar_3059']; ?>" />
</label>
<label class="Label30">
<input id="belimina3059" name="belimina3059" type="button" value="Eliminar" class="btMiniEliminar" onclick="eliminaf3059()" title="<?php echo $ETI['bt_mini_eliminar_3059']; ?>"<?php echo $sEstiloElimina; ?>/>
</label>
<?php
//Este es el cierre del div_p3059
?>
<div class="salto1px"></div>
</div>
<?php
} //Termina el segundo bloque  condicional - bloque editar.
?>
<div class="salto1px"></div>
<div id="div_f3059detalle">
<?php
echo $sTabla3059;
?>
</div>
<?php
?>
<div class="salto1px"></div>
</div>
<?php
}
// -- Termina Grupo campos 3059 Anexos
// -- Botones de gestión
if ($_REQUEST['paso'] == 0) {
?>
<div class="salto5px"></div>
<label class="Label320"></label>
<label class="Label160">
<input id="cmdIniciar" name="cmdIniciar" type="button" class="BotonAzul160" value="Iniciar solicitud" onclick="enviaguardar();" title="Iniciar solicitud" />
</label>
<?php
} else {
switch ($_REQUEST['saiu47estado']) {
case 0: // Borrador
case 5: // Devuelta.
?>
<div class="salto5px"></div>
<label class="Label320"></label>
<label class="Label160">
<input id="cmdGuardarB" name="cmdGuardarB" type="button" class="BotonAzul160" value="Guardar" onclick="enviaguardar();" title="Guardar" />
</label>
<label class="Label60"></label>
<label class="Label160">
<input id="cmdRadicar" name="cmdRadicar" type="button" class="BotonAzul160" value="Radicar Solicitud" onclick="enviacerrar();" title="Radicar Solicitud" />
</label>
<?php
break;
}
}
// -- Botones de gestión
?>
<input id="boculta3049" name="boculta3049" type="hidden" value="<?php echo $_REQUEST['boculta3049']; ?>" />
<?php
// -- Inicia Grupo campos 3049 Cambios de estado
if ($_REQUEST['paso'] == 2) {
?>
<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo $ETI['titulo_3049'];
?>
</label>
<div class="salto1px"></div>
<div id="div_f3049detalle">
<?php
echo $sTabla3049;
?>
</div>
<div class="salto1px"></div>
</div>
<?php
}
// -- Termina Grupo campos 3049 Cambios de estado
?>
<?php
if (false) {
	//Ejemplo de boton de ayuda
	//echo html_BotonAyuda('NombreCampo');
	//echo html_DivAyudaLocal('NombreCampo');
}
if ($bConExpande) {
	//Este es el cierre del div_p3047
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
echo '<h3>' . $ETI['msg_historial'] . '</h3>';
?>
</div>
<div class="areatrabajo">
<?php
echo ' ' . $csv_separa;
?>
<div id="div_f3047detalle">
<?php
echo $sTabla3047;
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
<div id="cargaForm">
<div id="area">
</div>
</div>
<?php
// Termina el div_sector2
?>
</div>


<div id="div_sector93" style="display:none">
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
<input id="titulo_3047" name="titulo_3047" type="hidden" value="<?php echo $sTituloModulo; ?>" />
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
<div id="cargaForm">
<div id="div_96cuerpo"></div>
</div>
</div>


<div id="div_sector97" style="display:none">
<?php
if ($bBloqueTitulo) {
?>
<div class="titulos">
<div class="titulosD">
<input id="cmdAyuda97" name="cmdAyuda97" type="button" class="btSupAyuda" onclick="muestraayuda(<?php echo $iCodModulo; ?>);" title="<?php echo $ETI['bt_ayuda']; ?>" value="<?php echo $ETI['bt_ayuda']; ?>" />
<input id="cmdVolverSec97" name="cmdVolverSec97" type="button" class="btSupVolver" onclick="retornacontrol();" title="<?php echo $ETI['bt_volver']; ?>" value="<?php echo $ETI['bt_volver']; ?>" />
</div>
<div class="titulosI" id="div_97titulo">
<?php
echo '<h2>' . $sTituloModulo . '</h2>';
?>
</div>
</div>
<?php
} else {
?>
<div id="div_97titulo" style="display:none"></div>
<?php
}
?>
<div id="cargaForm">
<div id="area">
<div id="div_97params"></div>
<div class="salto1px"></div>
<div id="div_97tabla"></div>
</div>
</div>
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
<script language="javascript" src="<?php echo $APP->rutacomun; ?>unad_todas2024.js"></script>
<?php
forma_piedepagina();

