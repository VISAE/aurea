<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2021 - 2024 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.25.10c lunes, 3 de mayo de 2021
--- Modelo Versión 2.26.2 martes, 15 de junio de 2021
--- Modelo Versión 2.28.2 martes, 24 de mayo de 2022
--- Modelo Versión 2.28.2 viernes, 22 de julio de 2022
--- Modelo Versión 3.0.8 jueves, 16 de mayo de 2024
--- Modelo Versión 3.0.13b jueves, 27 de febrero de 2025
*/

/** Archivo saiutramites.php.
 * Modulo 3047 saiu47tramites.
 * @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
 * @param debug=1 (Opcional), bandera para indicar si se generan datos de depuración
 * @date lunes, 3 de mayo de 2021
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
$iConsecutivoMenu = 1;
$iMinVerDB = 8322;
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
list($sGrupoModulo, $sPaginaModulo) = f109_GrupoModulo($iCodModuloConsulta, $iConsecutivoMenu, $objDB);
$sOcultaId = ' style="display:none;"';
$sTituloApp = $APP->siglasistema; //f101_SiglaModulo($APP->idsistema, $objDB);
$sTituloModulo = $ETI['titulo_3047'];
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
		header('Location:noticia.php?ret=saiutramites.php');
		die();
	}
}
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
$mensajes_3000 = $APP->rutacomun . 'lg/lg_3000_' . $_SESSION['unad_idioma'] . '.php';
if (!file_exists($mensajes_3000)) {
	$mensajes_3000 = $APP->rutacomun . 'lg/lg_3000_es.php';
}
$mensajes_3048 = $APP->rutacomun . 'lg/lg_3048_' . $_SESSION['unad_idioma'] . '.php';
if (!file_exists($mensajes_3048)) {
	$mensajes_3048 = $APP->rutacomun . 'lg/lg_3048_es.php';
}
$mensajes_3049 = $APP->rutacomun . 'lg/lg_3049_' . $_SESSION['unad_idioma'] . '.php';
if (!file_exists($mensajes_3049)) {
	$mensajes_3049 = $APP->rutacomun . 'lg/lg_3049_es.php';
}
$mensajes_3059 = $APP->rutacomun . 'lg/lg_3059_' . $_SESSION['unad_idioma'] . '.php';
if (!file_exists($mensajes_3059)) {
	$mensajes_3059 = $APP->rutacomun . 'lg/lg_3059_es.php';
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
$xajax->register(XAJAX_FUNCTION, 'f3047_Combosaiu47idgrupotrabajo');
$xajax->register(XAJAX_FUNCTION, 'f3047_Combosaiu47idresponsable');
$xajax->register(XAJAX_FUNCTION, 'f3047_Combosaiu47t707idcuenta');
$xajax->register(XAJAX_FUNCTION, 'sesion_abandona_V2');
$xajax->register(XAJAX_FUNCTION, 'sesion_mantenerV4');
$xajax->register(XAJAX_FUNCTION, 'f3047_HtmlTabla');
$xajax->register(XAJAX_FUNCTION, 'f3047_ExisteDato');
$xajax->register(XAJAX_FUNCTION, 'f3047_Busquedas');
$xajax->register(XAJAX_FUNCTION, 'f3047_HtmlBusqueda');
$xajax->register(XAJAX_FUNCTION, 'f3047_Combobmotivo');
$xajax->register(XAJAX_FUNCTION, 'f3000_HtmlTabla');
$xajax->register(XAJAX_FUNCTION, 'f3048_Guardar');
$xajax->register(XAJAX_FUNCTION, 'f3048_Traer');
$xajax->register(XAJAX_FUNCTION, 'f3048_Eliminar');
$xajax->register(XAJAX_FUNCTION, 'f3048_HtmlTabla');
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
$xajax->register(XAJAX_FUNCTION, 'f3059_HtmlTabla');
$xajax->register(XAJAX_FUNCTION, 'f3059_PintarLlaves');
$xajax->register(XAJAX_FUNCTION, 'f3059_AprobarDocumento');
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
if (isset($_REQUEST['saiu47prob_abandono']) == 0) {
	$_REQUEST['saiu47prob_abandono'] = 0;
	//$_REQUEST['saiu47prob_abandono'] = $iHoy;
}
if (isset($_REQUEST['saiu47tem_radicado']) == 0) {
	$_REQUEST['saiu47tem_radicado'] = 0;
	//$_REQUEST['saiu47tem_radicado'] = $iHoy;
}
if (isset($_REQUEST['saiu48anotacion_b']) == 0) {
	$_REQUEST['saiu48anotacion_b'] = '';
}
if (isset($_REQUEST['saiu48anotacion_c']) == 0) {
	$_REQUEST['saiu48anotacion_c'] = '';
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
$_REQUEST['saiu47prob_abandono'] = numeros_validar($_REQUEST['saiu47prob_abandono']);
$_REQUEST['saiu47tem_radicado'] = numeros_validar($_REQUEST['saiu47tem_radicado']);
$_REQUEST['saiu48anotacion_b'] = cadena_Validar($_REQUEST['saiu48anotacion_b']);
$_REQUEST['saiu48anotacion_c'] = cadena_Validar($_REQUEST['saiu48anotacion_c']);
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
		$_REQUEST['saiu48visiblealinteresado'] = '';
	}
	if (isset($_REQUEST['saiu48anotacion']) == 0) {
		$_REQUEST['saiu48anotacion'] = '';
	}
	if (isset($_REQUEST['saiu48idusuario']) == 0) {
		$_REQUEST['saiu48idusuario'] = 0;
	}
	if (isset($_REQUEST['saiu48idusuario_td']) == 0) {
		$_REQUEST['saiu48idusuario_td'] = $APP->tipo_doc;
	}
	if (isset($_REQUEST['saiu48idusuario_doc']) == 0) {
		$_REQUEST['saiu48idusuario_doc'] = '';
	}
	if (isset($_REQUEST['saiu48fecha']) == 0) {
		$_REQUEST['saiu48fecha'] = '';
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
	if (isset($_REQUEST['saiu59idtramite']) == 0) {
		$_REQUEST['saiu59idtramite'] = '';
	}
	if (isset($_REQUEST['saiu59consec']) == 0) {
		$_REQUEST['saiu59consec'] = '';
	}
	if (isset($_REQUEST['saiu59id']) == 0) {
		$_REQUEST['saiu59id'] = '';
	}
	if (isset($_REQUEST['saiu59idtipodoc']) == 0) {
		$_REQUEST['saiu59idtipodoc'] = '';
	}
	if (isset($_REQUEST['saiu59opcional']) == 0) {
		$_REQUEST['saiu59opcional'] = 0;
	}
	if (isset($_REQUEST['saiu59idestado']) == 0) {
		$_REQUEST['saiu59idestado'] = '';
	}
	if (isset($_REQUEST['saiu59idorigen']) == 0) {
		$_REQUEST['saiu59idorigen'] = 0;
	}
	if (isset($_REQUEST['saiu59idarchivo']) == 0) {
		$_REQUEST['saiu59idarchivo'] = 0;
	}
	if (isset($_REQUEST['saiu59idusuario']) == 0) {
		$_REQUEST['saiu59idusuario'] = 0;
		//$_REQUEST['saiu59idusuario'] =  $idTercero;
	}
	if (isset($_REQUEST['saiu59idusuario_td']) == 0) {
		$_REQUEST['saiu59idusuario_td'] = $APP->tipo_doc;
	}
	if (isset($_REQUEST['saiu59idusuario_doc']) == 0) {
		$_REQUEST['saiu59idusuario_doc'] = '';
	}
	if (isset($_REQUEST['saiu59fecha']) == 0) {
		$_REQUEST['saiu59fecha'] = '';
		//$_REQUEST['saiu59fecha'] = $iHoy;
	}
	if (isset($_REQUEST['saiu59idrevisa']) == 0) {
		$_REQUEST['saiu59idrevisa'] = 0;
		//$_REQUEST['saiu59idrevisa'] =  $idTercero;
	}
	if (isset($_REQUEST['saiu59idrevisa_td']) == 0) {
		$_REQUEST['saiu59idrevisa_td'] = $APP->tipo_doc;
	}
	if (isset($_REQUEST['saiu59idrevisa_doc']) == 0) {
		$_REQUEST['saiu59idrevisa_doc'] = '';
	}
	if (isset($_REQUEST['saiu59fecharevisa']) == 0) {
		$_REQUEST['saiu59fecharevisa'] = '';
		//$_REQUEST['saiu59fecharevisa'] = $iHoy;
	}
	$_REQUEST['saiu59idtramite'] = numeros_validar($_REQUEST['saiu59idtramite']);
	$_REQUEST['saiu59consec'] = numeros_validar($_REQUEST['saiu59consec']);
	$_REQUEST['saiu59id'] = numeros_validar($_REQUEST['saiu59id']);
	$_REQUEST['saiu59idtipodoc'] = numeros_validar($_REQUEST['saiu59idtipodoc']);
	$_REQUEST['saiu59opcional'] = numeros_validar($_REQUEST['saiu59opcional']);
	$_REQUEST['saiu59idestado'] = numeros_validar($_REQUEST['saiu59idestado']);
	$_REQUEST['saiu59idorigen'] = numeros_validar($_REQUEST['saiu59idorigen']);
	$_REQUEST['saiu59idarchivo'] = numeros_validar($_REQUEST['saiu59idarchivo']);
	$_REQUEST['saiu59idusuario'] = numeros_validar($_REQUEST['saiu59idusuario']);
	$_REQUEST['saiu59idusuario_td'] = cadena_Validar($_REQUEST['saiu59idusuario_td']);
	$_REQUEST['saiu59idusuario_doc'] = cadena_Validar($_REQUEST['saiu59idusuario_doc']);
	$_REQUEST['saiu59fecha'] = numeros_validar($_REQUEST['saiu59fecha']);
	$_REQUEST['saiu59idrevisa'] = numeros_validar($_REQUEST['saiu59idrevisa']);
	$_REQUEST['saiu59idrevisa_td'] = cadena_Validar($_REQUEST['saiu59idrevisa_td']);
	$_REQUEST['saiu59idrevisa_doc'] = cadena_Validar($_REQUEST['saiu59idrevisa_doc']);
	$_REQUEST['saiu59fecharevisa'] = numeros_validar($_REQUEST['saiu59fecharevisa']);
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
	$_REQUEST['blistar2'] = '';
}
if (isset($_REQUEST['bdoc']) == 0) {
	$_REQUEST['bdoc'] = '';
}
if (isset($_REQUEST['btipo']) == 0) {
	$_REQUEST['btipo'] = $iTipoTramite;
}
if (isset($_REQUEST['bmotivo']) == 0) {
	$_REQUEST['bmotivo'] = '';
}
if (isset($_REQUEST['bunidad']) == 0) {
	$_REQUEST['bunidad'] = '';
}
if (isset($_REQUEST['bresponsable']) == 0) {
	$_REQUEST['bresponsable'] = 1;
}
if (isset($_REQUEST['bzona']) == 0) {
	$_REQUEST['bzona'] = '';
}
if (isset($_REQUEST['bagno']) == 0) {
	$_REQUEST['bagno'] = '';
}
	//Anexos
$_REQUEST['bnombre'] = cadena_Validar($_REQUEST['bnombre']);
$_REQUEST['blistar'] = numeros_validar($_REQUEST['blistar']);
$_REQUEST['blistar2'] = numeros_validar($_REQUEST['blistar2']);
$_REQUEST['bdoc'] = cadena_Validar($_REQUEST['bdoc']);
$_REQUEST['btipo'] = numeros_validar($_REQUEST['btipo']);
$_REQUEST['bmotivo'] = numeros_validar($_REQUEST['bmotivo']);
$_REQUEST['bunidad'] = numeros_validar($_REQUEST['bunidad']);
$_REQUEST['bresponsable'] = numeros_validar($_REQUEST['bresponsable']);
$_REQUEST['bzona'] = numeros_validar($_REQUEST['bzona']);
$_REQUEST['bagno'] = numeros_validar($_REQUEST['bagno']);
	//Anexos
//Si Modifica o Elimina Cargar los campos
if (($_REQUEST['paso'] == 1) || ($_REQUEST['paso'] == 3)) {

	if ($bDebug) {
		list($sErrorT, $sDebugT) = f3000_TablasMes($_REQUEST['saiu47agno'], $_REQUEST['saiu47mes'], $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugT;
	}
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
		$_REQUEST['saiu47prob_abandono'] = $fila['saiu47prob_abandono'];
		$_REQUEST['saiu47tem_radicado'] = $fila['saiu47tem_radicado'];
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
$sMensajeMail = '';
$bCerrando = false;
$bMarcaDevolver = false;
$bMarcarNoProcede = false;
$bGuardarAnotacion = false;
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
		list($iPendientes, $sErrorV, $sDebugE) = f3047_ValidarArchivosAnexos($sTabla59, $_REQUEST['saiu47id'], $objDB, $bDebug);
		if ($iPendientes > 0) {
			$sError = 'Existen ' . $iPendientes . ' anexos que no han sido cargados, no se permite iniciar la radicaci&oacute;n.';
		}
	}
	if ($sError == '') {
		$bCerrando = true;
	} else {
		$_REQUEST['paso'] = 2;
	}
}
//Abrir
if ($_REQUEST['paso'] == 17) {
	$_REQUEST['paso'] = 2;
	//Es posible que deba definir el codigo de permiso para abrir.
	if ($sError == '') {
		list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 10, $idTercero, $objDB);
		if (!$bDevuelve) {
			$sError = $ERR['3'];
		}
	}
	//Otras restricciones para abrir.
	if ($sError == '') {
		if ($_REQUEST['saiu47estado'] != 1) {
			$sError = 'Estado de origen no corresponde.';
		}
	}
	if ($sError == '') {
		list($sErrorC, $sDebugE, $sMensajeMail) = f3047_CambiaEstado($_REQUEST['saiu47agno'], $_REQUEST['saiu47id'], $_REQUEST['saiu47estado'], 0, '', $_SESSION['unad_id_tercero'], $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugE;
		$_REQUEST['saiu47estado'] = 0;
		$sError = '<b>La solicitud ha sido pasada a borrador</b>';
		$iTipoError = 1;
	}
}
//Insertar o modificar un elemento
if (($_REQUEST['paso'] == 10) || ($_REQUEST['paso'] == 12)) {
	$bMueveScroll = true;
	list($_REQUEST, $sError, $iTipoError, $bCierraGuardar, $sErrorCerrando, $sDebugGuardar) = f3047_db_GuardarV2($_REQUEST, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugGuardar;
	if ($sError == '') {
		$sError = '<b>' . $ETI['msg_itemguardado'] . '</b>';
		$iTipoError = 1;
		if ($sErrorCerrando != '') {
			$iTipoError = 0;
			$sError = '<b>' . $ETI['msg_itemguardado'] . '</b><br>' . $sErrorCerrando;
		}
		if ($bCierraGuardar) {
			$sError = '<b>' . $ETI['msg_itemcerrado'] . '</b>';
		}
	}
}
//Ejecutar el cerrar
if ($bCerrando) {
	//acciones del cerrado
	$sErrorC = '';
	if ($sErrorC == '') {
		list($sErrorC, $sDebugE, $sMensajeMail) = f3047_CambiaEstado($_REQUEST['saiu47agno'], $_REQUEST['saiu47id'], $_REQUEST['saiu47estado'], 1, '', $_SESSION['unad_id_tercero'], $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugE;
	}
	if ($sErrorC == '') {
		$_REQUEST['saiu47estado'] = 1;
		list($_REQUEST['saiu47idunidad'], $_REQUEST['saiu47idgrupotrabajo'], $_REQUEST['saiu47idresponsable'], $sErrorE, $sDebugE) = f3047_ConsultaAsignado($_REQUEST['saiu47id'], $_REQUEST['saiu47agno'], $objDB, $bDebug);
		$sError = $sError . $sErrorE;
		if ($sError != '') {
			$sError = $sError . '<br>';
		}
		$sError = $sError . $sMensajeMail;
	} else {
		$sError = $sError . '<br>' . $sErrorC;
	}
}
//Cambiar a en revision.
if ($_REQUEST['paso'] == 21) {
	$_REQUEST['paso'] = 2;
	$bMueveScroll = true;
	if ($sError == '') {
		list($sError, $sDebugE, $sMensajeMail) = f3047_CambiaEstado($_REQUEST['saiu47agno'], $_REQUEST['saiu47id'], $_REQUEST['saiu47estado'], 3, '', $_SESSION['unad_id_tercero'], $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugE;
		if ($sError == '') {
			$_REQUEST['saiu47estado'] = 3;
			list($_REQUEST['saiu47idunidad'], $_REQUEST['saiu47idgrupotrabajo'], $_REQUEST['saiu47idresponsable'], $sErrorE, $sDebugE) = f3047_ConsultaAsignado($_REQUEST['saiu47id'], $_REQUEST['saiu47agno'], $objDB, $bDebug);
			$sError = $sError . $sErrorE;
			if ($sError != '') {
				$sError = $sError . '<br>';
			}
			$sError = $sError . 'La solicitud ha sido puesta En Revisi&oacute;n de Documentos.';
			$iTipoError = 1;
		}
	}
}
//Devolver
if ($_REQUEST['paso'] == 22) {
	$_REQUEST['paso'] = 2;
	$bMueveScroll = true;
	if ($sError == '') {
		if (trim($_REQUEST['saiu48anotacion_b']) == '') {
			$sError = $ERR['saiu48anotacion'];
			$iSector = 3;
		}
	}
	if ($sError == '') {
		$bMarcaDevolver = true;
		$bGuardarAnotacion = true;
		$saiu48anotacion = $_REQUEST['saiu48anotacion_b'];
	}
}
//Notificar un evento.
if ($_REQUEST['paso'] == 23) {
	$_REQUEST['paso'] = 2;
	list($sError, $sDebugN, $sMensajeNotifica) = f3047_NotificarEvento($_REQUEST['saiu47agno'], $_REQUEST['saiu47id'], $objDB, $bDebug, true);
	if ($sError == '') {
		$sError = 'Proceso completo ' . $sMensajeNotifica;
		$iTipoError = 1;
	}
}
//En concepto juridico
if ($_REQUEST['paso'] == 30) {
	$_REQUEST['paso'] = 2;
	$bMueveScroll = true;
	if ($sError == '') {
		$sSQL = 'SELECT 1 FROM ' . $sTabla59 . ' AS TB, saiu51tramitedoc AS T51 
		WHERE TB.saiu59idtramite=' . $_REQUEST['saiu47id'] . ' AND TB.saiu59idarchivo!=0 AND saiu59fecharevisa=0 
		AND TB.saiu59idtipodoc=T51.saiu51id AND T51.saiu51opcional=0 AND T51.saiu51proveedor=0';
		//$sSQL = 'SELECT 1 FROM ' . $sTabla59 . ' WHERE saiu59idtramite=' . $_REQUEST['saiu47id'] . ' AND saiu59fecharevisa=0';
		$tabla = $objDB->ejecutasql($sSQL);
		$iPendientes = $objDB->nf($tabla);
		if ($iPendientes > 0) {
			$sError = 'Existen ' . $iPendientes . ' anexos que no han sido aprobados, no es posible continuar.';
		}
	}
	if ($sError == '') {
		list($sError, $sDebugE, $sMensajeMail) = f3047_CambiaEstado($_REQUEST['saiu47agno'], $_REQUEST['saiu47id'], $_REQUEST['saiu47estado'], 4, '', $_SESSION['unad_id_tercero'], $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugE;
		if ($sError == '') {
			$_REQUEST['saiu47estado'] = 4;
			list($_REQUEST['saiu47idunidad'], $_REQUEST['saiu47idgrupotrabajo'], $_REQUEST['saiu47idresponsable'], $sErrorE, $sDebugE) = f3047_ConsultaAsignado($_REQUEST['saiu47id'], $_REQUEST['saiu47agno'], $objDB, $bDebug);
			$sError = $sError . $sErrorE;
			if ($sError != '') {
				$sError = $sError . '<br>';
			}
			$sError = $sError . 'La solicitud ha sido pasada a Concepto Jur&iacute;dico.';
			$iTipoError = 1;
		}
	}
}
//En Concepto tecnico
if ($_REQUEST['paso'] == 24) {
	$_REQUEST['paso'] = 2;
	$bMueveScroll = true;
	switch ($_REQUEST['saiu47estado']) {
		case 3: // Validacion de documentos
		case 4: // Concepto juridico
			break;
		default:
			$sError = 'No se reconoce el estado de origen.';
			break;
	}
	if ($sError == '') {
		$sSQL = 'SELECT 1 FROM ' . $sTabla59 . ' AS TB, saiu51tramitedoc AS T51 
		WHERE TB.saiu59idtramite=' . $_REQUEST['saiu47id'] . ' AND TB.saiu59idarchivo!=0 AND saiu59fecharevisa=0 
		AND TB.saiu59idtipodoc=T51.saiu51id AND T51.saiu51opcional=0 AND T51.saiu51proveedor=0';
		$tabla = $objDB->ejecutasql($sSQL);
		$iPendientes = $objDB->nf($tabla);
		if ($iPendientes > 0) {
			$sError = 'Existen ' . $iPendientes . ' anexos que no han sido aprobados, no es posible continuar.';
		}
	}
	if ($sError == '') {
		list($sError, $sDebugE, $sMensajeMail) = f3047_CambiaEstado($_REQUEST['saiu47agno'], $_REQUEST['saiu47id'], $_REQUEST['saiu47estado'], 6, '', $_SESSION['unad_id_tercero'], $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugE;
		if ($sError == '') {
			$_REQUEST['saiu47estado'] = 6;
			list($_REQUEST['saiu47idunidad'], $_REQUEST['saiu47idgrupotrabajo'], $_REQUEST['saiu47idresponsable'], $sErrorE, $sDebugE) = f3047_ConsultaAsignado($_REQUEST['saiu47id'], $_REQUEST['saiu47agno'], $objDB, $bDebug);
			$sError = $sError . $sErrorE;
			if ($sError != '') {
				$sError = $sError . '<br>';
			}
			$sError = $sError . 'La solicitud ha sido pasada a En estudio.';
			$iTipoError = 1;
		}
	}
}
//En Proyeccion de respuesta
if ($_REQUEST['paso'] == 34) {
	$_REQUEST['paso'] = 2;
	$bMueveScroll = true;
	if ($sError == '') {
		list($iPendientes, $sError, $sDebugE) = f3047_ValidarArchivosAnexos($sTabla59, $_REQUEST['saiu47id'], $objDB, $bDebug);
	}
	if ($sError == '') {
		list($sError, $sDebugE, $sMensajeMail) = f3047_CambiaEstado($_REQUEST['saiu47agno'], $_REQUEST['saiu47id'], $_REQUEST['saiu47estado'], 21, '', $_SESSION['unad_id_tercero'], $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugE;
		if ($sError == '') {
			$_REQUEST['saiu47idaprueba'] = $_SESSION['unad_id_tercero'];
			$_REQUEST['saiu47fechaaprueba'] = fecha_DiaMod();
			$_REQUEST['saiu47horaaprueba'] = fecha_hora();
			$_REQUEST['saiu47minutoaprueba'] = fecha_minuto();
			$_REQUEST['saiu47estado'] = 21;
			$sError = 'La solicitud ha sido pasada a Proyecci&oacute;n de Respuesta.';
			$iTipoError = 1;
		}
	}
}
//En Proyeccion de acto -- Procedente
if ($_REQUEST['paso'] == 25) {
	$_REQUEST['paso'] = 2;
	$bMueveScroll = true;
	if ($sError == '') {
		list($iPendientes, $sError, $sDebugE) = f3047_ValidarArchivosAnexos($sTabla59, $_REQUEST['saiu47id'], $objDB, $bDebug);
	}
	if ($sError == '') {
		list($sError, $sDebugE, $sMensajeMail) = f3047_CambiaEstado($_REQUEST['saiu47agno'], $_REQUEST['saiu47id'], $_REQUEST['saiu47estado'], 7, '', $_SESSION['unad_id_tercero'], $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugE;
		if ($sError == '') {
			$_REQUEST['saiu47idaprueba'] = $_SESSION['unad_id_tercero'];
			$_REQUEST['saiu47fechaaprueba'] = fecha_DiaMod();
			$_REQUEST['saiu47horaaprueba'] = fecha_hora();
			$_REQUEST['saiu47minutoaprueba'] = fecha_minuto();
			$_REQUEST['saiu47estado'] = 7;
			$sError = 'La solicitud ha sido pasada a Proyecci&oacute;n de Resoluci&oacute;n.';
			$iTipoError = 1;
		}
	}
}
//No procedente
if ($_REQUEST['paso'] == 26) {
	$_REQUEST['paso'] = 2;
	$bMueveScroll = true;
	if ($sError == '') {
		list($iPendientes, $sError, $sDebugE) = f3047_ValidarArchivosAnexos($sTabla59, $_REQUEST['saiu47id'], $objDB, $bDebug);
	}
	if ($sError == '') {
		if (trim($_REQUEST['saiu48anotacion_c']) == '') {
			$sError = $ERR['saiu48anotacion'];
			$iSector = 4;
		}
	}
	if ($sError == '') {
		$bMarcarNoProcede = true;
		$bGuardarAnotacion = true;
		$saiu48anotacion = $_REQUEST['saiu48anotacion_c'];
	}
	
}
if ($bGuardarAnotacion) {
	$valores = array();
	$valores[1] = $_REQUEST['saiu47id'];
	$valores[2] = '';
	$valores[3] = '';
	$valores[4] = 1;
	$valores[5] = $saiu48anotacion;
	$valores[6] = $_REQUEST['saiu48idusuario'];
	$valores[7] = $_REQUEST['saiu48fecha'];
	$valores[8] = $_REQUEST['saiu48hora'];
	$valores[9] = $_REQUEST['saiu48minuto'];
	$valores[98] = $_REQUEST['saiu47agno'];
	list($sError, $iAccion, $saiu48id, $sDebugGuardar) = f3048_db_Guardar($valores, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugGuardar;
	// Datos para los siguientes procesos
	if ($sError == '') {
		$_REQUEST['saiu48anotacion_b'] = '';
		$_REQUEST['saiu48anotacion_c'] = '';
	} else {
		if ($bMarcaDevolver) {
			$iSector = 3;
		}
		if ($bMarcarNoProcede) {
			$iSector = 4;
		}
	}
}
if ($bMarcaDevolver) {
	if ($sError == '') {
		list($sError, $sDebugE, $sMensajeMail) = f3047_CambiaEstado($_REQUEST['saiu47agno'], $_REQUEST['saiu47id'], $_REQUEST['saiu47estado'], 5, '', $_SESSION['unad_id_tercero'], $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugE;
		if ($sError == '') {
			$_REQUEST['saiu47estado'] = 5;
			$sError = 'La solicitud ha sido devuelta.';
			$iTipoError = 1;
			if ($sError != '') {
				$sError = $sError . '<br>';
			}
			$sError = $sError . $sMensajeMail;
		}
	}
}
if ($bMarcarNoProcede) {
	if ($sError == '') {
		list($sError, $sDebugE, $sMensajeMail) = f3047_CambiaEstado($_REQUEST['saiu47agno'], $_REQUEST['saiu47id'], $_REQUEST['saiu47estado'], 9, '', $_SESSION['unad_id_tercero'], $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugE;
		if ($sError == '') {
			$_REQUEST['saiu47estado'] = 9;
			$sError = 'La solicitud ha sido pasada a No Procedente.';
			$iTipoError = 1;
			if ($sError != '') {
				$sError = $sError . '<br>';
			}
			$sError = $sError . $sMensajeMail;
		}
	}
}
//Aplicar
if ($_REQUEST['paso'] == 27) {
	$_REQUEST['paso'] = 2;
	$bMueveScroll = true;
	if ($sError == '') {
		list($iPendientes, $sError, $sDebugE) = f3047_ValidarArchivosAnexos($sTabla59, $_REQUEST['saiu47id'], $objDB, $bDebug);
	}
	if ($sError == '') {
		list($sError, $sDebugE, $sMensajeMail) = f3047_CambiaEstado($_REQUEST['saiu47agno'], $_REQUEST['saiu47id'], $_REQUEST['saiu47estado'], 10, '', $_SESSION['unad_id_tercero'], $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugE;
		if ($sError == '') {
			$_REQUEST['saiu47estado'] = 10;
			$sError = 'La solicitud ha sido marcada como Resuelta.';
			$iTipoError = 1;
			if ($sError != '') {
				$sError = $sError . '<br>';
			}
			$sError = $sError . $sMensajeMail;
		}
	}
}
//Devolver el cambio de estado
if ($_REQUEST['paso'] == 28) {
	$_REQUEST['paso'] = 2;
	$bMueveScroll = true;
	if ($sError == '') {
		//Traemos el ultimo estado que es el que se va a borrar.
		$sSQL = 'SELECT saiu49idestadorigen, saiu49id FROM ' . $sTabla49 . ' WHERE saiu49idtramite=' . $_REQUEST['saiu47id'] . ' ORDER BY saiu49consec DESC LIMIT 0,1';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			$iEstadoFin = $fila['saiu49idestadorigen'];
			$sSQL = 'DELETE FROM ' . $sTabla49 . ' WHERE saiu49id=' . $fila['saiu49id'] . '';
			$result = $objDB->ejecutasql($sSQL);
		} else {
			$sError = 'No se registran m&aacute;s cambios de estado';
			$iEstadoFin = 0;
		}
		$sSQL = 'UPDATE ' . $sTabla47 . ' SET saiu47estado=' . $iEstadoFin . ' WHERE saiu47id=' . $_REQUEST['saiu47id'] . '';
		$result = $objDB->ejecutasql($sSQL);
		seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 3, $_REQUEST['saiu47id'], 'Reversa el estado del tramite [vuelve a ' . $iEstadoFin . ']', $objDB);
		$_REQUEST['saiu47estado'] = $iEstadoFin;
	}
	if ($sError == '') {
		$sError = 'Se ha reversado el estado de la solicitud..';
		$iTipoError = 1;
	}
}
// Reasignar responsable.
if ($_REQUEST['paso'] == 29) {
	$_REQUEST['paso'] = 2;
	$bMueveScroll = true;
	$iAgno = $_REQUEST['saiu47agno'];
	$iResponsable = $_REQUEST['saiu47idresponsable'];
	$sTabla47 = 'saiu47tramites_' . $iAgno;
	if (!$objDB->bexistetabla($sTabla47)) {
		$sError = 'No ha sido posible acceder al contenedor de datos';
	}
	if ($sError == '') {
		$sDatos = '';
		$sDatos = 'saiu47idunidad=' . $_REQUEST['saiu47idunidad'] . ', saiu47idgrupotrabajo=' . $_REQUEST['saiu47idgrupotrabajo'] . ', saiu47idresponsable=' . $iResponsable . '';
		if ($sDatos != '') {
			$sSQL = 'UPDATE ' . $sTabla47 . ' SET ' . $sDatos . ' WHERE saiu47id=' . $_REQUEST['saiu47id'] . '';
			$result = $objDB->ejecutasql($sSQL);
		}
		seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 3, $_REQUEST['saiu47id'], 'Reasigna el responsable :' . $sDatos, $objDB);
		list($_REQUEST['saiu47idunidad'], $_REQUEST['saiu47idgrupotrabajo'], $_REQUEST['saiu47idresponsable'], $sErrorE, $sDebugE) = f3047_ConsultaAsignado($_REQUEST['saiu47id'], $_REQUEST['saiu47agno'], $objDB, $bDebug);
		$sError = $sError . $sErrorE;
	}
}
// A aprobación de la GAF
if ($_REQUEST['paso'] == 31) {
	$_REQUEST['paso'] = 2;
	$bMueveScroll = true;
	if ($sError == '') {
		list($iPendientes, $sError, $sDebugE) = f3047_ValidarArchivosAnexos($sTabla59, $_REQUEST['saiu47id'], $objDB, $bDebug);
	}
	if ($sError == '') {
		list($sError, $sDebugE, $sMensajeMail) = f3047_CambiaEstado($_REQUEST['saiu47agno'], $_REQUEST['saiu47id'], $_REQUEST['saiu47estado'], 8, '', $_SESSION['unad_id_tercero'], $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugE;
		if ($sError == '') {
			$_REQUEST['saiu47estado'] = 8;
			list($_REQUEST['saiu47idunidad'], $_REQUEST['saiu47idgrupotrabajo'], $_REQUEST['saiu47idresponsable'], $sErrorE, $sDebugE) = f3047_ConsultaAsignado($_REQUEST['saiu47id'], $_REQUEST['saiu47agno'], $objDB, $bDebug);
			$sError = $sError . $sErrorE;
			if ($sError != '') {
				$sError = $sError . '<br>';
			}
			$sError = $sError . 'La solicitud ha sido pasada a Aprobaci&oacute;n de la GAF.';
			$iTipoError = 1;
		}
	}
}
// A firma de la VISAE
if ($_REQUEST['paso'] == 32) {
	$_REQUEST['paso'] = 2;
	$bMueveScroll = true;
	if ($sError == '') {
		list($iPendientes, $sError, $sDebugE) = f3047_ValidarArchivosAnexos($sTabla59, $_REQUEST['saiu47id'], $objDB, $bDebug);
	}
	if ($sError == '') {
		list($sError, $sDebugE, $sMensajeMail) = f3047_CambiaEstado($_REQUEST['saiu47agno'], $_REQUEST['saiu47id'], $_REQUEST['saiu47estado'], 11, '', $_SESSION['unad_id_tercero'], $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugE;
		if ($sError == '') {
			$_REQUEST['saiu47estado'] = 11;
			list($_REQUEST['saiu47idunidad'], $_REQUEST['saiu47idgrupotrabajo'], $_REQUEST['saiu47idresponsable'], $sErrorE, $sDebugE) = f3047_ConsultaAsignado($_REQUEST['saiu47id'], $_REQUEST['saiu47agno'], $objDB, $bDebug);
			$sError = $sError . $sErrorE;
			if ($sError != '') {
				$sError = $sError . '<br>';
			}
			$sError = $sError . 'La solicitud ha sido pasada a Firma de la VISAE.';
			$iTipoError = 1;
		}
	}
}
// A Proceso de Pago
if ($_REQUEST['paso'] == 33) {
	$_REQUEST['paso'] = 2;
	$bMueveScroll = true;
	if ($sError == '') {
		list($iPendientes, $sError, $sDebugE) = f3047_ValidarArchivosAnexos($sTabla59, $_REQUEST['saiu47id'], $objDB, $bDebug);
	}
	if ($sError == '') {
		list($sError, $sDebugE, $sMensajeMail) = f3047_CambiaEstado($_REQUEST['saiu47agno'], $_REQUEST['saiu47id'], $_REQUEST['saiu47estado'], 12, '', $_SESSION['unad_id_tercero'], $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugE;
		if ($sError == '') {
			$_REQUEST['saiu47estado'] = 12;
			list($_REQUEST['saiu47idunidad'], $_REQUEST['saiu47idgrupotrabajo'], $_REQUEST['saiu47idresponsable'], $sErrorE, $sDebugE) = f3047_ConsultaAsignado($_REQUEST['saiu47id'], $_REQUEST['saiu47agno'], $objDB, $bDebug);
			$sError = $sError . $sErrorE;
			if ($sError != '') {
				$sError = $sError . '<br>';
			}
			$sError = $sError . 'La solicitud ha sido pasada a Proceso de pago.';
			$iTipoError = 1;
		}
	}
}
// Devolver a RCONT
if ($_REQUEST['paso'] == 35) {
	$_REQUEST['paso'] = 2;
	$bMueveScroll = true;
	if ($sError == '') {
	}
	if ($sError == '') {
		list($sError, $sDebugE, $sMensajeMail) = f3047_CambiaEstado($_REQUEST['saiu47agno'], $_REQUEST['saiu47id'], $_REQUEST['saiu47estado'], 31, '', $_SESSION['unad_id_tercero'], $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugE;
		if ($sError == '') {
			$_REQUEST['saiu47estado'] = 31;
			list($_REQUEST['saiu47idunidad'], $_REQUEST['saiu47idgrupotrabajo'], $_REQUEST['saiu47idresponsable'], $sErrorE, $sDebugE) = f3047_ConsultaAsignado($_REQUEST['saiu47id'], $_REQUEST['saiu47agno'], $objDB, $bDebug);
			$sError = $sError . $sErrorE;
			if ($sError != '') {
				$sError = $sError . '<br>';
			}
			$sError = $sError . 'La solicitud ha sido devuelta para ajustes.';
			$iTipoError = 1;
		}
	}
}
// En ajustes de resolución-acto
if ($_REQUEST['paso'] == 36) {
	$_REQUEST['paso'] = 2;
	$bMueveScroll = true;
	if ($sError == '') {
	}
	if ($sError == '') {
		list($sError, $sDebugE, $sMensajeMail) = f3047_CambiaEstado($_REQUEST['saiu47agno'], $_REQUEST['saiu47id'], $_REQUEST['saiu47estado'], 36, '', $_SESSION['unad_id_tercero'], $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugE;
		if ($sError == '') {
			$_REQUEST['saiu47estado'] = 36;
			list($_REQUEST['saiu47idunidad'], $_REQUEST['saiu47idgrupotrabajo'], $_REQUEST['saiu47idresponsable'], $sErrorE, $sDebugE) = f3047_ConsultaAsignado($_REQUEST['saiu47id'], $_REQUEST['saiu47agno'], $objDB, $bDebug);
			$sError = $sError . $sErrorE;
			if ($sError != '') {
				$sError = $sError . '<br>';
			}
			$sError = $sError . 'La solicitud ha sido devuelta para ajustes.';
			$iTipoError = 1;
		}
	}
}
// Numeración SGENERAL
if ($_REQUEST['paso'] == 37) {
	$_REQUEST['paso'] = 2;
	$bMueveScroll = true;
	if ($sError == '') {
	}
	if ($sError == '') {
		list($sError, $sDebugE, $sMensajeMail) = f3047_CambiaEstado($_REQUEST['saiu47agno'], $_REQUEST['saiu47id'], $_REQUEST['saiu47estado'], 41, '', $_SESSION['unad_id_tercero'], $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugE;
		if ($sError == '') {
			$_REQUEST['saiu47estado'] = 41;
			list($_REQUEST['saiu47idunidad'], $_REQUEST['saiu47idgrupotrabajo'], $_REQUEST['saiu47idresponsable'], $sErrorE, $sDebugE) = f3047_ConsultaAsignado($_REQUEST['saiu47id'], $_REQUEST['saiu47agno'], $objDB, $bDebug);
			$sError = $sError . $sErrorE;
			if ($sError != '') {
				$sError = $sError . '<br>';
			}
			$sError = $sError . 'La solicitud ha sido enviada para numeraci&oacute;n.';
			$iTipoError = 1;
		}
	}
}
// En firma de la VISAE
if ($_REQUEST['paso'] == 38) {
	$_REQUEST['paso'] = 2;
	$bMueveScroll = true;
	if ($sError == '') {
	}
	if ($sError == '') {
		list($sError, $sDebugE, $sMensajeMail) = f3047_CambiaEstado($_REQUEST['saiu47agno'], $_REQUEST['saiu47id'], $_REQUEST['saiu47estado'], 46, '', $_SESSION['unad_id_tercero'], $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugE;
		if ($sError == '') {
			$_REQUEST['saiu47estado'] = 46;
			list($_REQUEST['saiu47idunidad'], $_REQUEST['saiu47idgrupotrabajo'], $_REQUEST['saiu47idresponsable'], $sErrorE, $sDebugE) = f3047_ConsultaAsignado($_REQUEST['saiu47id'], $_REQUEST['saiu47agno'], $objDB, $bDebug);
			$sError = $sError . $sErrorE;
			if ($sError != '') {
				$sError = $sError . '<br>';
			}
			$sError = $sError . 'La solicitud ha sido enviada para firma de la VISAE.';
			$iTipoError = 1;
		}
	}
}
// En proceso contable
if ($_REQUEST['paso'] == 39) {
	$_REQUEST['paso'] = 2;
	$bMueveScroll = true;
	if ($sError == '') {
	}
	if ($sError == '') {
		list($sError, $sDebugE, $sMensajeMail) = f3047_CambiaEstado($_REQUEST['saiu47agno'], $_REQUEST['saiu47id'], $_REQUEST['saiu47estado'], 51, '', $_SESSION['unad_id_tercero'], $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugE;
		if ($sError == '') {
			$_REQUEST['saiu47estado'] = 51;
			list($_REQUEST['saiu47idunidad'], $_REQUEST['saiu47idgrupotrabajo'], $_REQUEST['saiu47idresponsable'], $sErrorE, $sDebugE) = f3047_ConsultaAsignado($_REQUEST['saiu47id'], $_REQUEST['saiu47agno'], $objDB, $bDebug);
			$sError = $sError . $sErrorE;
			if ($sError != '') {
				$sError = $sError . '<br>';
			}
			$sError = $sError . 'La solicitud ha sido enviada a contabilidad.';
			$iTipoError = 1;
		}
	}
}
// Procesar los tiempos de respuesta
if ($_REQUEST['paso'] == 91) {
	$_REQUEST['paso'] = 2;
	list($_REQUEST['saiu47prob_abandono'], $_REQUEST['saiu47tem_radicado'], $sDebugT) = f3047_TiemposProceso($_REQUEST['saiu47agno'], $_REQUEST['saiu47id'], $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugT;
	$sError = 'Proceso terminado';
	$iTipoError = 1;
}
// Cambio de consecutivo.
if ($_REQUEST['paso'] == 93) {
	$_REQUEST['paso'] = 2;
	$_REQUEST['saiu47consec_nuevo'] = numeros_validar($_REQUEST['saiu47consec_nuevo']);
	if ($_REQUEST['saiu47consec_nuevo'] == '') {
		$sError = $ERR['saiu47consec'];
	}
	if ($sError == '') {
		list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 8, $idTercero, $objDB);
		if (!$bDevuelve) {
			$sError = $ERR['8'];
		}
	}
	if ($sError == '') {
		//Ver que el consecutivo no exista.
		$sSQL = 'SELECT saiu47id FROM saiu47tramites_' . $_REQUEST['saiu47agno'] . ' WHERE saiu47consec=' . $_REQUEST['saiu47consec_nuevo'] . ' AND saiu47tipotramite=' . $_REQUEST['saiu47tipotramite'] . ' AND saiu47tiporadicado=' . $_REQUEST['saiu47tiporadicado'] . ' AND saiu47mes=' . $_REQUEST['saiu47mes'] . ' AND saiu47agno=' . $_REQUEST['saiu47agno'] . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$sError = 'El consecutivo ' . $_REQUEST['saiu47consec_nuevo'] . ' ya existe';
		}
	}
	if ($sError == '') {
		//Aplicar el cambio.
		$sSQL = 'UPDATE saiu47tramites_' . $_REQUEST['saiu47agno'] . ' SET saiu47consec=' . $_REQUEST['saiu47consec_nuevo'] . ' WHERE saiu47id=' . $_REQUEST['saiu47id'] . '';
		$tabla = $objDB->ejecutasql($sSQL);
		$sDetalle = 'Cambia el consecutivo de ' . $_REQUEST['saiu47consec'] . ' a ' . $_REQUEST['saiu47consec_nuevo'] . '';
		$_REQUEST['saiu47consec'] = $_REQUEST['saiu47consec_nuevo'];
		$_REQUEST['saiu47consec_nuevo'] = '';
		seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 8, $_REQUEST['saiu47id'], $sDetalle, $objDB);
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
		list($sError, $iTipoError, $sDebugElimina) = f3047_db_Eliminar($_REQUEST['saiu47agno'], $_REQUEST['saiu47id'], $objDB, $bDebug);
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
	$_REQUEST['saiu47idsolicitante'] = 0; //$idTercero;
	$_REQUEST['saiu47idsolicitante_td'] = $APP->tipo_doc;
	$_REQUEST['saiu47idsolicitante_doc'] = '';
	$_REQUEST['saiu47idperiodo'] = 0;
	$_REQUEST['saiu47idescuela'] = 0;
	$_REQUEST['saiu47idprograma'] = 0;
	$_REQUEST['saiu47idzona'] = 0;
	$_REQUEST['saiu47idcentro'] = 0;
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
	$_REQUEST['saiu47prob_abandono'] = 0;
	$_REQUEST['saiu47tem_radicado'] = 0;
	$_REQUEST['paso'] = 0;
}
if ($bLimpiaHijos) {
	$_REQUEST['saiu48idtramite'] = '';
	$_REQUEST['saiu48consec'] = '';
	$_REQUEST['saiu48id'] = '';
	$_REQUEST['saiu48visiblealinteresado'] = 1;
	$_REQUEST['saiu48anotacion'] = '';
	$_REQUEST['saiu48anotacion_b'] = '';
	$_REQUEST['saiu48anotacion_c'] = '';
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
$bAplicaAbandono = false;
$bApruebaDocumentos = false;
$bPuedeGuardar = true;
$bConEliminar = false;
$bConBotonACptoTecnico = false;
$bConBotonAplicar = false;
$bConBotonApruebaGAF = false;
$bConBotonCptoJuridico = false;
$bConBotonCerrar = false;
$bConBotonDevolver = false;
$bConBotonDevolverARCA = false;
$bConBotonFirma = false;
$bConBotonNoProcedente = false;
$bConBotonNotificar = false;
$bConBotonPago = false;
$bConBotonActoAdmin = false;
$bConBotonAContabilidad = false;
$bConBotonRadicar = false;
$bConBotonReasignar = false;
$bConBotonRevisar = false;
$bConBotonAProyeccionDeRespuesta = false;
$bConBotonDevolverActo = false;
$bConBotonNumeracion = false;
$bConBotonFirmaVISAE = false;

$bPuedeAbrir = false;
$bPuedeGuardar2 = false;
$bHayImprimir = false;
$bHayImprimir2 = false;
$sScriptImprime = 'imprimeexcel()';
$sScriptImprime2 = 'imprimep()';
$sClaseImprime = 'iExcel';
$sClaseImprime2 = 'iPdf';
if ($iPiel == 0) {
	$sClaseImprime = 'btEnviarExcel';
	$sClaseImprime2 = 'btEnviarPdf';
}
$bConBeneficiario = false;
$bMostrarSaldos = false;
$bEditaDetalle = true;
$bEnProceso = true;
$bConAprueba = false;
$bPuedeAgregarArchivos = false;
$bPuedeDevolver = false;
$bGestionaResponsables = false;
$iAgno = fecha_agno();
$sTabla = 'saiu47tramites_' . $iAgno;
if (!$objDB->bexistetabla($sTabla)) {
	list($sErrorT, $sDebugT) = f3000_TablasMes($iAgno, fecha_mes(), $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugT;
}
$bAplicaPeriodo = true;
$bConFechaTramite = false;
$bConHistorial = false;
$bConSaltoEstadoDeCuenta = false;
$bPuedeDesaprobar = false;
//Permisos adicionales
$seg_5 = 0;
$seg_6 = 0;
$seg_8 = 0;
switch ($iTipoTramite) {
	case 1:
		$bAplicaPeriodo = true;
		break;
	case 707:
		$bConFechaTramite = true;
		break;
}
list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 6, $idTercero, $objDB);
if ($bDevuelve) {
	$seg_6 = 1;
	$bHayImprimir = true;
}
if ((int)$_REQUEST['paso'] != 0) {
	$bConSaltoEstadoDeCuenta = true;
	$bConBotonNotificar = true;
	$bEditaDetalle = false;
	$bEnProceso = false;
	$bPuedeGuardar = false;
	$bDevuelve = false;
	//list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 5, $idTercero, $objDB);
	if ($bDevuelve) {
		$seg_5 = 1;
	}
	if ($_REQUEST['saiu47estado'] > 6) {
		list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 8, $idTercero, $objDB);
		if ($bDevuelve) {
			$seg_8 = 1;
		}
	}
	/*
	99	Anulado	 
	*/
	switch ($_REQUEST['saiu47estado']) {
		case 0: //Borrador
			$bConBotonRadicar = true;
			$bConEliminar = true;
			$bEditaDetalle = true;
			$bEnProceso = true;
			$bPuedeGuardar = true;
			$bAplicaAbandono = true;
			break;
		case 1: //Radicado
			$bConBotonRevisar = true;
			$bConBotonReasignar = true;
			$bPuedeGuardar = true;
			list($bPuedeAbrir, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 10, $idTercero, $objDB);
			break;
		case 3: //En revisión de documentos
			$bConBotonDevolver = true;
			$bConBotonCptoJuridico = true;
			$bConBotonACptoTecnico = true;
			$bConBotonReasignar = true;
			$bApruebaDocumentos = true;
			break;
		case 4: // En concepto Jurídico
			//$bConBotonDevolver = true;
			$bConBotonACptoTecnico = true;
			$bConBotonReasignar = true;
			$bPuedeAgregarArchivos = true;
			//$bConBotonNoProcedente = true;
			$bPuedeGuardar = true;
			$bConBotonAProyeccionDeRespuesta = true;
			break;
		case 21:// Proyección de Respuesta.
			//$bConBotonDevolver = true;
			$bConBotonACptoTecnico = true;
			$bConBotonReasignar = true;
			$bPuedeAgregarArchivos = true;
			$bConBotonNoProcedente = true;
			$bPuedeGuardar = true;
			break;
		case 5: //Devuelto
			$bConBotonRadicar = true;
			$bEnProceso = true;
			$bAplicaAbandono = true;
			break;
		case 6: // En Concepto técnico
		case 31: // En ajustes RCA
			$bMostrarSaldos = true;
			$bPuedeAgregarArchivos = true;
			$bConBotonActoAdmin = true;
			$bConBotonReasignar = true;
			$bConBeneficiario = true;
			//Mayo 9 de 2025
			//$bConBotonNoProcedente = true;
			$bConBotonAProyeccionDeRespuesta = true;
			$bPuedeGuardar = true;
			$bPuedeGuardar2 = true;
			break;
		case 7: //En proyección de resolució
		case 36: // Ajustes acto
			$bConBotonDevolverARCA = true;
			$bConBotonApruebaGAF = true;
			$bConBotonReasignar = true;
			$bMostrarSaldos = true;
			$bPuedeAgregarArchivos = true;
			$bConAprueba = true;
			$bConBeneficiario = true;
			$bPuedeGuardar = true;
			break;
		case 8: // En aprobación de la GAF
			$bConBotonReasignar = true;
			$bPuedeAgregarArchivos = true;
			$bMostrarSaldos = true;
			$bConBeneficiario = true;
			$bPuedeGuardar = true;
			$bConBotonDevolverActo = true;
			$bConBotonAContabilidad = true;
			break;
		case 51: // En proceso contable
			$bConBotonReasignar = true;
			$bPuedeAgregarArchivos = true;
			$bMostrarSaldos = true;
			$bConBeneficiario = true;
			$bPuedeGuardar = true;
			$bConBotonDevolverActo = true;
			$bConBotonNumeracion = true;
			break;
		case 41: // Numeración SGENERAL
			$bConBotonFirmaVISAE = true;
			break;
		case 46: // En firma de la VISAE
			$bConBotonFirma = true;
			break;
		case 9: //No procede
			if ($bDebug) {
				list($bConBotonRadicar, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 10, $idTercero, $objDB);
			}
			break;
		case 10: // Solicitud resuelta
			$bMostrarSaldos = true;
			$bConAprueba = true;
			$bRevisaAbrir = true;
			$bConBeneficiario = true;
			if ($bDebug) {
				list($bConBotonRadicar, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 10, $idTercero, $objDB);
			}
			break;
		case 11: // En firma de la GAF
			$bConBotonReasignar = true;
			$bPuedeAgregarArchivos = true;
			$bMostrarSaldos = true;
			$bConBeneficiario = true;
			$bConBotonPago = true;
			break;
		case 12: // En proceso de pago
			$bConBotonAplicar = true;
			$bConBotonReasignar = true;
			$bPuedeAgregarArchivos = true;
			$bMostrarSaldos = true;
			$bConBeneficiario = true;
			$bPuedeGuardar2 = true;
			break;
		case 99: //Anulado
			break;
	}
	if ($bConBotonReasignar) {
		list($bGestionaResponsables, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 10, $idTercero, $objDB);
	}
	if ($_REQUEST['saiu47estado'] != 0) {
		list($bPuedeDevolver, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 10, $idTercero, $objDB);
	}
}
$sInfoNoProcedente = '';
if ($bConBotonNoProcedente) {
	list($iPendientes, $sErrorV, $sDebugE) = f3047_ValidarArchivosAnexos($sTabla59, $_REQUEST['saiu47id'], $objDB, $bDebug);
	if ($iPendientes > 0) {
		$sInfoNoProcedente = 'Tenga en cuenta que para declarar una solicitud como NO PROCEDENTE debe haber anexado los anexos obligatorios. {Tiene ' . $iPendientes . ' anexos que no han sido cargados}.';
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
list($saiu47estado_nombre, $sErrorDet) = tabla_campoxid('saiu60estadotramite', 'saiu60nombre', 'saiu60id', $_REQUEST['saiu47estado'], '{' . $ETI['msg_sindato'] . '}', $objDB);
$html_saiu47estado = html_oculto('saiu47estado', $_REQUEST['saiu47estado'], $saiu47estado_nombre);
list($saiu47idsolicitante_rs, $_REQUEST['saiu47idsolicitante'], $_REQUEST['saiu47idsolicitante_td'], $_REQUEST['saiu47idsolicitante_doc']) = html_tercero($_REQUEST['saiu47idsolicitante_td'], $_REQUEST['saiu47idsolicitante_doc'], $_REQUEST['saiu47idsolicitante'], 0, $objDB);
$bOculto = !$bEnProceso;
$html_saiu47idsolicitante = html_DivTerceroV8('saiu47idsolicitante', $_REQUEST['saiu47idsolicitante_td'], $_REQUEST['saiu47idsolicitante_doc'], $bOculto, $objDB, $objCombos, 0, $ETI['ing_doc']);
if ($bAplicaPeriodo) {
	if (false) {
		list($saiu47idperiodo_nombre, $sErrorDet) = tabla_campoxid('exte02per_aca', 'exte02nombre', 'exte02id', $_REQUEST['saiu47idperiodo'], '{' . $ETI['msg_sindato'] . '}', $objDB);
		$html_saiu47idperiodo = html_oculto('saiu47idperiodo', $_REQUEST['saiu47idperiodo'], $saiu47idperiodo_nombre);
	} else {
		$objCombos->nuevo('saiu47idperiodo', $_REQUEST['saiu47idperiodo'], true, '{' . $ETI['msg_ninguno'] . '}', 0);
		$objCombos->iAncho = 380;
		$sSQL = f146_ConsultaCombo('exte02id>0');
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

$html_saiu47t1idmotivo = f3047_HTMLComboV2_saiu47t1idmotivo($objDB, $objCombos, $_REQUEST['saiu47t1idmotivo'], $_REQUEST['saiu47tipotramite']);
list($saiu47idbenefdevol_rs, $_REQUEST['saiu47idbenefdevol'], $_REQUEST['saiu47idbenefdevol_td'], $_REQUEST['saiu47idbenefdevol_doc']) = html_tercero($_REQUEST['saiu47idbenefdevol_td'], $_REQUEST['saiu47idbenefdevol_doc'], $_REQUEST['saiu47idbenefdevol'], 0, $objDB);
$bOculto = !$bPuedeGuardar2;
$html_saiu47idbenefdevol = html_DivTerceroV8('saiu47idbenefdevol', $_REQUEST['saiu47idbenefdevol_td'], $_REQUEST['saiu47idbenefdevol_doc'], $bOculto, $objDB, $objCombos, 0, $ETI['ing_doc']);
list($saiu47idaprueba_rs, $_REQUEST['saiu47idaprueba'], $_REQUEST['saiu47idaprueba_td'], $_REQUEST['saiu47idaprueba_doc']) = html_tercero($_REQUEST['saiu47idaprueba_td'], $_REQUEST['saiu47idaprueba_doc'], $_REQUEST['saiu47idaprueba'], 0, $objDB);

$saiu47idunidad_nombre = '&nbsp;';
if ($_REQUEST['saiu47idunidad'] != '') {
	if ((int)$_REQUEST['saiu47idunidad'] == 0) {
		$saiu47idunidad_nombre = '{' . $ETI['msg_sindato'] . '}';
	} else {
		list($saiu47idunidad_nombre, $sErrorDet) = tabla_campoxid('unae26unidadesfun', 'unae26nombre', 'unae26id', $_REQUEST['saiu47idunidad'], '{' . $ETI['msg_sindato'] . '}', $objDB);
	}
}
$bLiderGrupo = false;
$et_saiu47idunidad = '<b>' . $saiu47idunidad_nombre . '</b>';
if ($bGestionaResponsables) {
	$objCombos->nuevo('saiu47idunidad', $_REQUEST['saiu47idunidad'], true, '{' . $ETI['msg_seleccione'] . '}');
	$objCombos->sAccion = 'carga_combo_saiu47idgrupotrabajo();';
	$objCombos->iAncho = 600;
	$sSQL = f226_ConsultaCombo();
	$html_saiu47idunidad = $objCombos->html($sSQL, $objDB);
} else {
	$html_saiu47idunidad = html_oculto('saiu47idunidad', $_REQUEST['saiu47idunidad'], $saiu47idunidad_nombre);
}
$saiu47idgrupotrabajo_nombre = '&nbsp;';
if ($_REQUEST['saiu47idgrupotrabajo'] != '') {
	if ((int)$_REQUEST['saiu47idgrupotrabajo'] == 0) {
		$saiu47idgrupotrabajo_nombre = '{' . $ETI['msg_sindato'] . '}';
	} else {
		list($saiu47idgrupotrabajo_nombre, $sErrorDet) = tabla_campoxid('bita27equipotrabajo', 'bita27nombre', 'bita27id', $_REQUEST['saiu47idgrupotrabajo'], '{' . $ETI['msg_sindato'] . '}', $objDB);
		$sSQL = 'SELECT TB.bita27idlider AS idlider
		FROM bita27equipotrabajo AS TB
		WHERE (TB.bita27idlider=' . $idTercero . ' OR TB.bita27propietario=' . $idTercero . ') AND TB.bita27id=' . $_REQUEST['saiu47idgrupotrabajo'] . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$bLiderGrupo = true;
		}
	}
}
$et_saiu47idgrupotrabajo = '<b>' . $saiu47idgrupotrabajo_nombre . '</b>';
if ($bGestionaResponsables) {
	$html_saiu47idgrupotrabajo = f3047_HTMLComboV2_saiu47idgrupotrabajo($objDB, $objCombos, $_REQUEST['saiu47idgrupotrabajo'], $_REQUEST['saiu47idunidad']);
} else {
	$html_saiu47idgrupotrabajo = html_oculto('saiu47idgrupotrabajo', $_REQUEST['saiu47idgrupotrabajo'], $saiu47idgrupotrabajo_nombre);
}
$saiu47idresponsable_nombre = '&nbsp;';
if ($_REQUEST['saiu47idresponsable'] != '') {
	if ((int)$_REQUEST['saiu47idresponsable'] == 0) {
		$saiu47idresponsable_nombre = '{' . $ETI['msg_sindato'] . '}';
	} else {
		$sSQL = 'SELECT TB.bita28id AS id, T2.unad11razonsocial AS nombre
		FROM bita28eqipoparte AS TB, unad11terceros AS T2 
		WHERE TB.bita28idtercero=T2.unad11id AND TB.bita28id=' . $_REQUEST['saiu47idresponsable'] . '';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' Consulta responsable tramite ' . $sSQL . '<br>';
		}
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			$saiu47idresponsable_nombre = $fila['nombre'];
		} else {
			$saiu47idresponsable_nombre = '{' . $ETI['msg_sindato'] . '}';
		}
	}
}
$et_saiu47idresponsable = '<b>' . $saiu47idresponsable_nombre . '</b>';
if ($bConBotonReasignar) {
	$html_saiu47idresponsable = f3047_HTMLComboV2_saiu47idresponsable($objDB, $objCombos, $_REQUEST['saiu47idresponsable'], $_REQUEST['saiu47idgrupotrabajo']);
} else {
	$html_saiu47idresponsable = html_oculto('saiu47idresponsable', $_REQUEST['saiu47idresponsable'], $saiu47idresponsable_nombre);
}

if ($iTipoTramite == 707) {
	$objCombos->nuevo('saiu47t707formarecaudo', $_REQUEST['saiu47t707formarecaudo'], true, '{' . $ETI['msg_seleccione'] . '}');
	$sSQL = 'SELECT saiu69id AS id, saiu69nombre AS nombre FROM saiu69formarecaudo WHERE saiu69id>0 ORDER BY saiu69id';
	$html_saiu47t707formarecaudo = $objCombos->html($sSQL, $objDB);
	$objCombos->nuevo('saiu47t707identidadconv', $_REQUEST['saiu47t707identidadconv'], true, '{' . $ETI['msg_ninguno'] . '}', 0);
	$objCombos->iAncho = 360;
	$sSQL = 'SELECT cart11idtercero AS id, CONCAT(T11.unad11razonsocial, CASE TB.cart11vigente WHEN 0 THEN " [INACTIVO]" ELSE "" END) AS nombre 
	FROM cart11entidadconv AS TB, unad11terceros AS T11
	WHERE  TB.cart11idtercero=T11.unad11id
	ORDER BY TB.cart11vigente DESC, T11.unad11razonsocial';
	$html_saiu47t707identidadconv = $objCombos->html($sSQL, $objDB);
	$objCombos->nuevo('saiu47t707idbanco', $_REQUEST['saiu47t707idbanco'], true, '{' . $ETI['msg_seleccione'] . '}');
	$objCombos->sAccion = 'carga_combo_saiu47t707idcuenta();';
	$sSQL = 'SELECT TB.fact12id AS id, TB.fact12nombre AS nombre 
	FROM fact08cuenta AS TD, fact12banco AS TB 
	WHERE TD.fact08activa="S" AND TD.fact08tipo IN ("A", "C") AND TD.fact08banco=TB.fact12id
	GROUP BY TB.fact12id, TB.fact12nombre
	ORDER BY TB.fact12publico DESC, TB.fact12nombre';
	$html_saiu47t707idbanco = $objCombos->html($sSQL, $objDB);
	$html_saiu47t707idcuenta = f3047_HTMLComboV2_saiu47t707idcuenta($objDB, $objCombos, $_REQUEST['saiu47t707idcuenta'], $_REQUEST['saiu47t707idbanco']);
}
$bOculto = false;
if ($_REQUEST['paso'] != 0) {
	if ($_REQUEST['saiu47estado'] != 0) {
		$bOculto = true;
	}
}
if ($bOculto) {
	$saiu47agno_nombre = $_REQUEST['saiu47agno'];
	$html_saiu47agno = html_oculto('saiu47agno', $_REQUEST['saiu47agno'], $saiu47agno_nombre);
	$saiu47mes_nombre = strtoupper(fecha_mes_nombre((int)$_REQUEST['saiu47mes']));
	$html_saiu47mes = html_oculto('saiu47mes', $_REQUEST['saiu47mes'], $saiu47mes_nombre);
	$saiu47dia_nombre = $_REQUEST['saiu47dia'];
	$html_saiu47dia = html_oculto('saiu47dia', $_REQUEST['saiu47dia'], $saiu47dia_nombre);
} else {
	$html_saiu47agno = f3047_HTMLComboV2_saiu47agno($objDB, $objCombos, $_REQUEST['saiu47agno']);
	$html_saiu47mes = f3047_HTMLComboV2_saiu47mes($objDB, $objCombos, $_REQUEST['saiu47mes']);
	$html_saiu47dia = html_ComboDia('saiu47dia', $_REQUEST['saiu47dia'], false);
}
if ((int)$_REQUEST['paso'] == 0) {
	//$html_saiu47tiporadicado = f3047_HTMLComboV2_saiu47tiporadicado($objDB, $objCombos, $_REQUEST['saiu47tiporadicado']);
	//$html_saiu47tipotramite = f3047_HTMLComboV2_saiu47tipotramite($objDB, $objCombos, $_REQUEST['saiu47tipotramite']);
} else {
	/*
	list($saiu47tiporadicado_nombre, $sErrorDet) = tabla_campoxid('saiu16tiporadicado', 'saiu16nombre', 'saiu16id', $_REQUEST['saiu47tiporadicado'], '{' . $ETI['msg_sindato'] . '}', $objDB);
	$html_saiu47tiporadicado = html_oculto('saiu47tiporadicado', $_REQUEST['saiu47tiporadicado'], $saiu47tiporadicado_nombre);
	list($saiu47tipotramite_nombre, $sErrorDet) = tabla_campoxid('saiu46tipotramite', 'saiu46nombre', 'saiu46id', $_REQUEST['saiu47tipotramite'], '{' . $ETI['msg_sindato'] . '}', $objDB);
	$html_saiu47tipotramite = html_oculto('saiu47tipotramite', $_REQUEST['saiu47tipotramite'], $saiu47tipotramite_nombre);
	*/
	$objCombos->nuevo('saiu48visiblealinteresado', $_REQUEST['saiu48visiblealinteresado'], true, $ETI['no'], 0);
	$objCombos->addItem(1, $ETI['si']);
	//$objCombos->addArreglo($asaiu48visiblealinteresado, $isaiu48visiblealinteresado);
	$html_saiu48visiblealinteresado = $objCombos->html('', $objDB);
	list($saiu48idusuario_rs, $_REQUEST['saiu48idusuario'], $_REQUEST['saiu48idusuario_td'], $_REQUEST['saiu48idusuario_doc']) = html_tercero($_REQUEST['saiu48idusuario_td'], $_REQUEST['saiu48idusuario_doc'], $_REQUEST['saiu48idusuario'], 0, $objDB);
	/*
	list($saiu49idresponsable_rs, $_REQUEST['saiu49idresponsable'], $_REQUEST['saiu49idresponsable_td'], $_REQUEST['saiu49idresponsable_doc'])=html_tercero($_REQUEST['saiu49idresponsable_td'], $_REQUEST['saiu49idresponsable_doc'], $_REQUEST['saiu49idresponsable'], 0, $objDB);
	$objCombos->nuevo('saiu49idestadorigen', $_REQUEST['saiu49idestadorigen'], true, '{'.$ETI['msg_seleccione'].'}');
	$sSQL='SELECT saiu60id AS id, saiu60nombre AS nombre FROM saiu60estadotramite ORDER BY saiu60nombre';
	$html_saiu49idestadorigen=$objCombos->html($sSQL, $objDB);
	$objCombos->nuevo('saiu49idestadofin', $_REQUEST['saiu49idestadofin'], true, '{'.$ETI['msg_seleccione'].'}');
	$sSQL='SELECT saiu60id AS id, saiu60nombre AS nombre FROM saiu60estadotramite ORDER BY saiu60nombre';
	$html_saiu49idestadofin=$objCombos->html($sSQL, $objDB);
	list($saiu49usuario_rs, $_REQUEST['saiu49usuario'], $_REQUEST['saiu49usuario_td'], $_REQUEST['saiu49usuario_doc'])=html_tercero($_REQUEST['saiu49usuario_td'], $_REQUEST['saiu49usuario_doc'], $_REQUEST['saiu49usuario'], 0, $objDB);
	$saiu49correterminos_nombre=$_REQUEST['saiu49correterminos'];
	//list($saiu49correterminos_nombre, $sErrorDet)=tabla_campoxid('','','',$_REQUEST['saiu49correterminos'],'{'.$ETI['msg_sindato'].'}', $objDB);
	$html_saiu49correterminos=html_oculto('saiu49correterminos', $_REQUEST['saiu49correterminos'], $saiu49correterminos_nombre);
	*/
	if ($bPuedeAgregarArchivos) {
		$objCombos->nuevo('saiu59idtipodoc', $_REQUEST['saiu59idtipodoc'], true, '{' . $ETI['msg_seleccione'] . '}');
		$sSQL = 'SELECT saiu51id AS id, saiu51nombre AS nombre 
		FROM saiu51tramitedoc 
		WHERE saiu51idtipotram=' . $_REQUEST['saiu47tipotramite'] . ' AND saiu51vigente=1 AND saiu51proveedor<>0  
		ORDER BY saiu51nombre';
		$html_saiu59idtipodoc = $objCombos->html($sSQL, $objDB);
	}
	/*
	$objCombos->nuevo('saiu59opcional', $_REQUEST['saiu59opcional'], true, $ETI['no'], 0);
	$objCombos->addItem(1, $ETI['si']);
	//$objCombos->addArreglo($asaiu59opcional, $isaiu59opcional);
	$html_saiu59opcional=$objCombos->html('', $objDB);
	list($saiu59idestado_nombre, $sErrorDet)=tabla_campoxid('saiu60estadotramite','saiu60nombre','saiu60id',$_REQUEST['saiu59idestado'],'{'.$ETI['msg_sindato'].'}', $objDB);
	$html_saiu59idestado=html_oculto('saiu59idestado', $_REQUEST['saiu59idestado'], $saiu59idestado_nombre);
	list($saiu59idusuario_rs, $_REQUEST['saiu59idusuario'], $_REQUEST['saiu59idusuario_td'], $_REQUEST['saiu59idusuario_doc'])=html_tercero($_REQUEST['saiu59idusuario_td'], $_REQUEST['saiu59idusuario_doc'], $_REQUEST['saiu59idusuario'], 0, $objDB);
	list($saiu59idrevisa_rs, $_REQUEST['saiu59idrevisa'], $_REQUEST['saiu59idrevisa_td'], $_REQUEST['saiu59idrevisa_doc'])=html_tercero($_REQUEST['saiu59idrevisa_td'], $_REQUEST['saiu59idrevisa_doc'], $_REQUEST['saiu59idrevisa'], 0, $objDB);
	*/
}
//Alistar datos adicionales
$id_rpt = 0;
//$id_rpt=reportes_id(_Identificador_Tipo_Reporte_, $objDB);

$objCombos->nuevo('blistar', $_REQUEST['blistar'], true, '{' . $ETI['msg_todos'] . '}');
$objCombos->sAccion = 'paginarf3047()';
$sSQL = 'SHOW TABLES LIKE "saiu47tramites%"';
$tablac = $objDB->ejecutasql($sSQL);
while ($filac = $objDB->sf($tablac)) {
	$sAgno = substr($filac[0], 15);
	$objCombos->addItem($sAgno, $sAgno);
}
$html_blistar = $objCombos->html('', $objDB);
$objCombos->nuevo('blistar2', $_REQUEST['blistar2'], true, '{' . $ETI['msg_todos'] . '}');
$objCombos->sAccion = 'paginarf3047()';
//$objCombos->addArreglo($aListar2, $iListar2);
$sSQL = 'SELECT saiu60id AS id, saiu60nombre AS nombre FROM saiu60estadotramite WHERE saiu60tipo1>0 ORDER BY saiu60tipo1';
$html_blistar2 = $objCombos->html($sSQL, $objDB);
if (false) {
	$objCombos->nuevo('btipo', $_REQUEST['btipo'], true, '{' . $ETI['msg_todos'] . '}');
	$objCombos->sAccion = 'carga_combo_bmotivo()';
	$sSQL = 'SELECT saiu46id AS id, saiu46nombre AS nombre FROM saiu46tipotramite';
	$html_btipo = $objCombos->html($sSQL, $objDB);
}
$html_bmotivo = f3047_HTMLComboV2_bmotivo($objDB, $objCombos, $_REQUEST['bmotivo'], $_REQUEST['btipo']);
$objCombos->nuevo('bunidad', $_REQUEST['bunidad'], true, '{' . $ETI['msg_todas'] . '}');
$objCombos->iAncho = 600;
$objCombos->sAccion = 'paginarf3047()';
//$sSQL = 'SELECT unae26id AS id, CONCAT(unae26prefijo, "", unae26nombre) AS nombre FROM unae26unidadesfun WHERE unae26idzona=0 AND unae26id>0 ORDER BY unae26lugar, unae26nombre';
$sSQL = f226_ConsultaCombo();
$html_bunidad = $objCombos->html($sSQL, $objDB);
$objCombos->nuevo('bresponsable', $_REQUEST['bresponsable'], true, '{' . $ETI['msg_todos'] . '}');
$objCombos->sAccion = 'paginarf3047()';
$objCombos->addItem(1, 'Trámites a mi cargo');
$sSQL = '';
$html_bresponsable = $objCombos->html($sSQL, $objDB);
$objCombos->nuevo('bzona', $_REQUEST['bzona'], true, '{' . $ETI['msg_todos'] . '}');
$objCombos->sAccion = 'paginarf3047()';
$objCombos->addItem('0', '{' . $ETI['msg_ninguna'] . '}');
$sSQL = 'SELECT unad23id AS id, unad23nombre AS nombre FROM unad23zona ORDER BY unad23conestudiantes DESC, unad23nombre';
$html_bzona = $objCombos->html($sSQL, $objDB);
$objCombos->nuevo('bagno', $_REQUEST['bagno'], true, '{' . $ETI['msg_todos'] . '}');
$objCombos->sAccion = 'paginarf3047()';
$sIds = '-99';
$sSQL = 'SHOW TABLES LIKE "saiu47tramites%"';
$tabla = $objDB->ejecutasql($sSQL);
while ($fila = $objDB->sf($tabla)) {
	$sTabla = substr($fila[0], 15);
	$sIds = $sIds . ',' . $sTabla;
}
$sSQL = 'SELECT unad10codigo AS id, unad10codigo AS nombre FROM unad10vigencia WHERE unad10codigo IN (' . $sIds . ') ORDER BY unad10codigo DESC';
$html_bagno = $objCombos->html($sSQL, $objDB);
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
$aParametros[100] = $idTercero;
$aParametros[101] = $_REQUEST['paginaf3047'];
$aParametros[102] = $_REQUEST['lppf3047'];
$aParametros[103] = $_REQUEST['bnombre'];
$aParametros[104] = $_REQUEST['blistar'];
$aParametros[105] = $_REQUEST['blistar2'];
$aParametros[106] = $_REQUEST['bdoc'];
$aParametros[107] = $_REQUEST['btipo'];
$aParametros[108] = $_REQUEST['bmotivo'];
$aParametros[109] = $_REQUEST['bunidad'];
$aParametros[110] = $_REQUEST['bresponsable'];
$aParametros[111] = $_REQUEST['bzona'];
$aParametros[112] = $_REQUEST['bagno'];
list($sTabla3047, $sDebugTabla) = f3047_TablaDetalleV2($aParametros, $objDB, $bDebug);
$sDebug = $sDebug . $sDebugTabla;
$sTabla3000 = '';
if ($bConHistorial) {
	$aParametros3000[0] = $idTercero;
	$aParametros3000[1] = $iCodModulo;
	$aParametros3000[2] = $_REQUEST['saiu47agno'];
	$aParametros3000[3] = $_REQUEST['saiu47id'];
	$aParametros3000[100] = $_REQUEST['saiu47idsolicitante'];
	$aParametros3000[101] = $_REQUEST['paginaf3000'];
	$aParametros3000[102] = $_REQUEST['lppf3000'];
	//$aParametros3000[103]=$_REQUEST['bnombre3000'];
	//$aParametros3000[104]=$_REQUEST['blistar3000'];
	list($sTabla3000, $sDebugTabla) = f3000_TablaDetalleV2($aParametros3000, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugTabla;
}
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
	list($sTabla3048, $sDebugTabla) = f3048_TablaDetalleV2($aParametros3048, $objDB, $bDebug);
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
	list($sTabla3059, $sDebugTabla) = f3059_TablaDetalleV2($aParametros3059, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugTabla;
}
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
		if ($bConBotonCerrar) {
			$aBotones[$iNumBoton] = array('enviacerrar()', $ETI['bt_cerrar'], 'iTask');
			$iNumBoton++;
		}
		if ($bPuedeAbrir) {
			$aBotones[$iNumBoton] = array('enviaabrir()', $ETI['bt_abrir'], 'iOpen');
			$iNumBoton++;
		}
		if ($bConBotonReasignar) {
			$aBotones[$iNumBoton] = array('enviareasignar()', $ETI['bt_guardar'], 'iSaveFill', 2);
			$iNumBoton++;
		}
		$aBotones[$iNumBoton] = array('expandesector(1)', $ETI['bt_volver'], 'iArrowBack', 2);
		$iNumBoton++;
		$aBotones[$iNumBoton] = array('expandesector(1)', $ETI['bt_volver'], 'iArrowBack', 3);
		$iNumBoton++;
		$aBotones[$iNumBoton] = array('expandesector(1)', $ETI['bt_volver'], 'iArrowBack', 4);
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
		document.getElementById('div_sector3').style.display = 'none';
		document.getElementById('div_sector4').style.display = 'none';
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
		document.getElementById('botones_sector2').style.display = 'none';
		document.getElementById('botones_sector3').style.display = 'none';
		document.getElementById('botones_sector4').style.display = 'none';
		document.getElementById('botones_sector97').style.display = 'none';
		switch (codigo) {
			case 1:
				break;
			case 2:
			case 3:
			case 4:
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
			if (idcampo == 'saiu47idsolicitante') {
				params[6] = 3047;
				xajax_unad11_Mostrar_v2(params);
			} else {
				xajax_unad11_Mostrar_v2(params);
			}
		} else {
			document.getElementById(idcampo).value = 0;
			document.getElementById('div_' + idcampo).innerHTML = '&nbsp;';
			paginarf3000();
		}
	}

	function ter_traerxid(idcampo, vrcampo) {
		let params = new Array();
		params[0] = vrcampo;
		params[1] = idcampo;
		if (params[0] != 0) {
			params[6] = 3047;
			xajax_unad11_TraerXidSAI(params);
		}
	}

	function imprimelista() {
		if (window.document.frmedita.seg_6.value == 1) {
			window.document.frmlista.consulta.value = window.document.frmedita.consulta_3047.value;
			window.document.frmlista.titulos.value = window.document.frmedita.titulos_3047.value;
			window.document.frmlista.nombrearchivo.value = 'Tramites';
			window.document.frmlista.submit();
		} else {
			ModalMensaje("<?php echo $ERR['6']; ?>");
		}
	}

	function asignarvariables() {
		window.document.frmimpp.v3.value = window.document.frmedita.bnombre.value;
		window.document.frmimpp.v4.value = window.document.frmedita.blistar.value;
		window.document.frmimpp.v5.value = window.document.frmedita.blistar2.value;
		window.document.frmimpp.v6.value = window.document.frmedita.bdoc.value;
		window.document.frmimpp.v7.value = window.document.frmedita.btipo.value;
		window.document.frmimpp.v8.value = window.document.frmedita.bmotivo.value;
		window.document.frmimpp.v9.value = window.document.frmedita.bunidad.value;
		window.document.frmimpp.v10.value = window.document.frmedita.bresponsable.value;
		window.document.frmimpp.v11.value = window.document.frmedita.bzona.value;
		window.document.frmimpp.v12.value = window.document.frmedita.bagno.value;
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
			window.document.frmimpp.action = '<?php echo $APP->rutacomun; ?>e3047_ss.php';
			window.document.frmimpp.submit();
		} else {
			ModalMensaje(sError);
		}
	}

	function imprimep() {
		if (window.document.frmedita.seg_5.value == 1) {
			asignarvariables();
			window.document.frmimpp.action = 'p3047.php';
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
		datos[1] = window.document.frmedita.saiu47agno.value;
		datos[2] = window.document.frmedita.saiu47mes.value;
		datos[3] = window.document.frmedita.saiu47tiporadicado.value;
		datos[4] = window.document.frmedita.saiu47tipotramite.value;
		datos[5] = window.document.frmedita.saiu47consec.value;
		if ((datos[1] != '') && (datos[2] != '') && (datos[3] != '') && (datos[4] != '') && (datos[5] != '')) {
			xajax_f3047_ExisteDato(datos);
		}
	}

	function cargadato(llave1, llave2, llave3, llave4, llave5) {
		window.document.frmedita.saiu47agno.value = String(llave1);
		window.document.frmedita.saiu47mes.value = String(llave2);
		window.document.frmedita.saiu47tiporadicado.value = String(llave3);
		window.document.frmedita.saiu47tipotramite.value = String(llave4);
		window.document.frmedita.saiu47consec.value = String(llave5);
		window.document.frmedita.paso.value = 1;
		window.document.frmedita.submit();
	}

	function cargaridf3047(llave1, llave2) {
		document.getElementById('div_saiu47agno').innerHTML = '<input id="saiu47agno" name="saiu47agno" type="hidden" value="' + llave1 + '" />';
		window.document.frmedita.saiu47id.value = String(llave2);
		window.document.frmedita.paso.value = 3;
		window.document.frmedita.submit();
	}

	function carga_combo_saiu47t1idmotivo() {
		let params = new Array();
		params[0] = window.document.frmedita.saiu47tipotramite.value;
		document.getElementById('div_saiu47t1idmotivo').innerHTML = '<b>Procesando datos, por favor espere...</b><input id="saiu47t1idmotivo" name="saiu47t1idmotivo" type="hidden" value="" />';
		xajax_f3047_Combosaiu47t1idmotivo(params);
	}

	function carga_combo_bmotivo() {
		let params = new Array();
		params[0] = window.document.frmedita.btipo.value;
		document.getElementById('div_bmotivo').innerHTML = '<b>Procesando datos, por favor espere...</b><input id="bmotivo" name="bmotivo" type="hidden" value="" />';
		xajax_f3047_Combobmotivo(params);
	}

	function carga_combo_saiu47idgrupotrabajo() {
		let params = new Array();
		params[0] = window.document.frmedita.saiu47idunidad.value;
		document.getElementById('div_saiu47idgrupotrabajo').innerHTML = '<b>Procesando datos, por favor espere...</b><input id="saiu47idgrupotrabajo" name="saiu47idgrupotrabajo" type="hidden" value="" />';
		document.getElementById('div_saiu47idresponsable').innerHTML = '<b>Procesando datos, por favor espere...</b><input id="saiu47idresponsable" name="saiu47idresponsable" type="hidden" value="" />';
		xajax_f3047_Combosaiu47idgrupotrabajo(params);
	}

	function carga_combo_saiu47idresponsable() {
		let params = new Array();
		params[0] = window.document.frmedita.saiu47idgrupotrabajo.value;
		document.getElementById('div_saiu47idresponsable').innerHTML = '<b>Procesando datos, por favor espere...</b><input id="saiu47idresponsable" name="saiu47idresponsable" type="hidden" value="" />';
		xajax_f3047_Combosaiu47idresponsable(params);
	}

	function carga_combo_saiu47t707idcuenta() {
		let params = new Array();
		params[0] = window.document.frmedita.saiu47t707idbanco.value;
		document.getElementById('div_saiu47t707idcuenta').innerHTML = '<b>Procesando datos, por favor espere...</b><input id="saiu47t707idcuenta" name="saiu47t707idcuenta" type="hidden" value="" />';
		xajax_f3047_Combosaiu47t707idcuenta(params);
	}

	function paginarf3047() {
		let params = new Array();
		params[98] = <?php echo $idTercero; ?>;
		params[99] = window.document.frmedita.debug.value;
		params[100] = <?php echo $idTercero; ?>;
		params[101] = window.document.frmedita.paginaf3047.value;
		params[102] = window.document.frmedita.lppf3047.value;
		params[103] = window.document.frmedita.bnombre.value;
		params[104] = window.document.frmedita.blistar.value;
		params[105] = window.document.frmedita.blistar2.value;
		params[106] = window.document.frmedita.bdoc.value;
		params[107] = window.document.frmedita.btipo.value;
		params[108] = window.document.frmedita.bmotivo.value;
		params[109] = window.document.frmedita.bunidad.value;
		params[110] = window.document.frmedita.bresponsable.value;
		params[111] = window.document.frmedita.bzona.value;
		params[112] = window.document.frmedita.bagno.value;
		document.getElementById('div_f3047detalle').innerHTML = '<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf3047" name="paginaf3047" type="hidden" value="' + params[101] + '" /><input id="lppf3047" name="lppf3047" type="hidden" value="' + params[102] + '" />';
		xajax_f3047_HtmlTabla(params);
	}
<?php
if ($bConBotonRevisar) {
?>
	function enviarevision() {
		window.document.frmedita.iscroll.value = window.pageYOffset;
		ModalConfirmV2('<?php echo $ETI['msg_enrevision']; ?>', () => {
				ejecuta_enviarevision();
		});
	}

	function ejecuta_enviarevision() {
		MensajeAlarmaV2('<?php echo $ETI['msg_ejecutando']; ?>', 2);
		expandesector(98);
		window.document.frmedita.paso.value = 21;
		window.document.frmedita.submit();
	}
<?php
}
if ($bConBotonRadicar) {
?>
	function enviacerrar() {
		ModalConfirmV2('<?php echo $ETI['msg_cierre3047']; ?>', () => {
			ejecuta_enviacerrar();
		});
	}

	function ejecuta_enviacerrar() {
		MensajeAlarmaV2('<?php echo $ETI['msg_ejecutando']; ?>', 2);
		expandesector(98);
		window.document.frmedita.paso.value = 16;
		window.document.frmedita.submit();
	}
<?php
}
if ($bPuedeAbrir) {
?>
	function enviaabrir() {
		ModalConfirmV2('<?php echo $ETI['msg_confirmaabrir']; ?>', () => {
			ejecuta_enviaabrir();
		});
	}

	function ejecuta_enviaabrir() {
		MensajeAlarmaV2('<?php echo $ETI['msg_ejecutando']; ?>', 2);
		expandesector(98);
		window.document.frmedita.paso.value = 17;
		window.document.frmedita.submit();
	}
<?php
}
if ($bConBotonDevolver) {
?>
	function enviadevolver() {
		window.document.frmedita.iscroll.value = window.pageYOffset;
		ModalConfirmV2('<?php echo $ETI['msg_devolver']; ?>', () => {
			ejecuta_enviadevolver();
		});
	}

	function ejecuta_enviadevolver() {
		MensajeAlarmaV2('<?php echo $ETI['msg_ejecutando']; ?>', 2);
		expandesector(98);
		window.document.frmedita.paso.value = 22;
		window.document.frmedita.submit();
	}

<?php
}
if ($bConBotonCptoJuridico) {
?>
	function enviacptojuridico() {
		window.document.frmedita.iscroll.value = window.pageYOffset;
		ModalConfirmV2('<?php echo $ETI['msg_confirmacptojur']; ?>', () => {
			ejecuta_enviacptojuridico();
		});
	}

	function ejecuta_enviacptojuridico() {
		MensajeAlarmaV2('<?php echo $ETI['msg_ejecutando']; ?>', 2);
		expandesector(98);
		window.document.frmedita.paso.value = 30;
		window.document.frmedita.submit();
	}
<?php
}
if ($bConBotonACptoTecnico) {
?>
	function enviacptotecnico() {
		window.document.frmedita.iscroll.value = window.pageYOffset;
		ModalConfirmV2('<?php echo $ETI['msg_confirmacptotecnico']; ?>', () => {
			ejecuta_enviacptotecnico();
		});
	}

	function ejecuta_enviacptotecnico() {
		MensajeAlarmaV2('<?php echo $ETI['msg_ejecutando']; ?>', 2);
		expandesector(98);
		window.document.frmedita.paso.value = 24;
		window.document.frmedita.submit();
	}
<?php
}
if ($bConBotonNoProcedente) {
?>
	function envianoprocede() {
		window.document.frmedita.iscroll.value = window.pageYOffset;
		ModalConfirmV2('<?php echo $ETI['msg_confirmarnoprocedente']; ?>', () => {
			ejecuta_envianoprocede();
		});
	}

	function ejecuta_envianoprocede() {
		MensajeAlarmaV2('<?php echo $ETI['msg_ejecutando']; ?>', 2);
		expandesector(98);
		window.document.frmedita.paso.value = 26;
		window.document.frmedita.submit();
	}

<?php
}
if ($bConBotonDevolverARCA) {
?>
	function devolverrca() {
		window.document.frmedita.iscroll.value = window.pageYOffset;
		ModalConfirmV2('<?php echo $ETI['msg_confirmadevrca']; ?>', () => {
			ejecuta_devolverrca();
		});
	}

	function ejecuta_devolverrca() {
		MensajeAlarmaV2('<?php echo $ETI['msg_ejecutando']; ?>', 2);
		expandesector(98);
		window.document.frmedita.paso.value = 35;
		window.document.frmedita.submit();
	}
<?php
}
if ($bConBotonDevolverActo) {
?>
	function devolver_acto() {
		window.document.frmedita.iscroll.value = window.pageYOffset;
		ModalConfirmV2('<?php echo $ETI['msg_confirma36']; ?>', () => {
			ejecuta_devolver_acto();
		});
	}

	function ejecuta_devolver_acto() {
		MensajeAlarmaV2('<?php echo $ETI['msg_ejecutando']; ?>', 2);
		expandesector(98);
		window.document.frmedita.paso.value = 36;
		window.document.frmedita.submit();
	}
<?php
}
if ($bConBotonAContabilidad) {
?>
	function enviaracontabilidad() {
		window.document.frmedita.iscroll.value = window.pageYOffset;
		ModalConfirmV2('<?php echo $ETI['msg_confirma51']; ?>', () => {
			ejecuta_enviaracontabilidad();
		});
	}

	function ejecuta_enviaracontabilidad() {
		MensajeAlarmaV2('<?php echo $ETI['msg_ejecutando']; ?>', 2);
		expandesector(98);
		window.document.frmedita.paso.value = 39;
		window.document.frmedita.submit();
	}
<?php
}
if ($bConBotonNumeracion) {
?>
	function enviaranumeracion() {
		window.document.frmedita.iscroll.value = window.pageYOffset;
		ModalConfirmV2('<?php echo $ETI['msg_confirma41']; ?>', () => {
			ejecuta_enviaranumeracion();
		});
	}

	function ejecuta_enviaranumeracion() {
		MensajeAlarmaV2('<?php echo $ETI['msg_ejecutando']; ?>', 2);
		expandesector(98);
		window.document.frmedita.paso.value = 37;
		window.document.frmedita.submit();
	}
<?php
}
if ($bConBotonFirmaVISAE) {
?>
	function afirmavisae() {
		window.document.frmedita.iscroll.value = window.pageYOffset;
		ModalConfirmV2('<?php echo $ETI['msg_confirma46']; ?>', () => {
			ejecuta_afirmavisae();
		});
	}

	function ejecuta_afirmavisae() {
		MensajeAlarmaV2('<?php echo $ETI['msg_ejecutando']; ?>', 2);
		expandesector(98);
		window.document.frmedita.paso.value = 38;
		window.document.frmedita.submit();
	}
<?php
}
if ($bConBotonApruebaGAF) {
?>
	function enviagaf() {
		window.document.frmedita.iscroll.value = window.pageYOffset;
		ModalConfirmV2('<?php echo $ETI['msg_confirmagaf']; ?>', () => {
			ejecuta_enviagaf();
		});
	}

	function ejecuta_enviagaf() {
		MensajeAlarmaV2('<?php echo $ETI['msg_ejecutando']; ?>', 2);
		expandesector(98);
		window.document.frmedita.paso.value = 31;
		window.document.frmedita.submit();
	}
<?php
}
if ($bConBotonFirma) {
?>
	function enviafirma() {
		window.document.frmedita.iscroll.value = window.pageYOffset;
		ModalConfirmV2('<?php echo $ETI['msg_confirmafirma']; ?>', () => {
			ejecuta_enviafirma();
		});
	}

	function ejecuta_enviafirma() {
		MensajeAlarmaV2('<?php echo $ETI['msg_ejecutando']; ?>', 2);
		expandesector(98);
		window.document.frmedita.paso.value = 32;
		window.document.frmedita.submit();
	}
<?php
}
if ($bConBotonPago) {
?>
	function enviapago() {
		window.document.frmedita.iscroll.value = window.pageYOffset;
		ModalConfirmV2('<?php echo $ETI['msg_confirmapago']; ?>', () => {
			ejecuta_enviapago();
		});
	}

	function ejecuta_enviapago() {
		MensajeAlarmaV2('<?php echo $ETI['msg_ejecutando']; ?>', 2);
		expandesector(98);
		window.document.frmedita.paso.value = 33;
		window.document.frmedita.submit();
	}
<?php
}
if ($bConBotonAplicar) {
?>
	function enviaaplicar() {
		window.document.frmedita.iscroll.value = window.pageYOffset;
		ModalConfirmV2('<?php echo $ETI['msg_confirmaresuelto']; ?>', () => {
			ejecuta_enviaaplicar();
		});
	}

	function ejecuta_enviaaplicar() {
		MensajeAlarmaV2('<?php echo $ETI['msg_ejecutando']; ?>', 2);
		expandesector(98);
		window.document.frmedita.paso.value = 27;
		window.document.frmedita.submit();
	}
<?php
}
if ($bConBotonActoAdmin) {
?>
	function enviaactoadmin() {
		window.document.frmedita.iscroll.value = window.pageYOffset;
		ModalConfirmV2('<?php echo $ETI['msg_confirmaproyeccion']; ?>', () => {
			ejecuta_enviaactoadmin();
		});
	}

	function ejecuta_enviaactoadmin() {
		MensajeAlarmaV2('<?php echo $ETI['msg_ejecutando']; ?>', 2);
		expandesector(98);
		window.document.frmedita.paso.value = 25;
		window.document.frmedita.submit();
	}
<?php
}
if ($bConBotonAProyeccionDeRespuesta) {
?>
	function enviaproyrta() {
		window.document.frmedita.iscroll.value = window.pageYOffset;
		ModalConfirmV2('<?php echo $ETI['msg_confirmaproyrta']; ?>', () => {
			ejecuta_enviaproyrta();
		});
	}

	function ejecuta_enviaproyrta() {
		MensajeAlarmaV2('<?php echo $ETI['msg_ejecutando']; ?>', 2);
		expandesector(98);
		window.document.frmedita.paso.value = 34;
		window.document.frmedita.submit();
	}
<?php
}
if ($bPuedeAgregarArchivos) {
?>
	function carga_saiu59idarchivob() {
		carga_saiu59idarchivo(window.document.frmedita.saiu59id.value);
	}

	function eliminasaiu59idarchivob() {
		eliminasaiu59idarchivo(window.document.frmedita.saiu59id.value);
	}
<?php
}
if ($bApruebaDocumentos) {
?>
	function apruebaidf3059(id59) {
		ModalConfirmV2('<?php echo $ETI['msg_aprobardoc']; ?>', () => {
			ejecuta_apruebaidf3059(id59, 1);
		});
	}
<?php
}
if ($bPuedeDesaprobar) {
?>

	function retiraidf3059(id59) {
		ModalConfirmV2('El documento ser&aacute; desaprobado,<br>Esta seguro de continuar?', () => {
			ejecuta_apruebaidf3059(id59, 0);
		});
	}
<?php
}
if ($bApruebaDocumentos || $bPuedeDesaprobar) {
?>
	function ejecuta_apruebaidf3059(id59, idProceso) {
		var params = new Array();
		params[0] = window.document.frmedita.saiu47id.value;
		params[1] = idProceso;
		params[2] = '';
		params[3] = id59;
		//params[14]=window.document.frmedita.p1_3059.value;
		params[98] = window.document.frmedita.saiu47agno.value;
		params[99] = window.document.frmedita.debug.value;
		params[100] = window.document.frmedita.id11.value;
		params[101] = window.document.frmedita.paginaf3059.value;
		params[102] = window.document.frmedita.lppf3059.value;
		document.getElementById('div_f3059detalle').innerHTML = '<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf3059" name="paginaf3059" type="hidden" value="' + params[101] + '" /><input id="lppf3059" name="lppf3059" type="hidden" value="' + params[102] + '" />';
		MensajeAlarmaV2('<?php echo $ETI['msg_ejecutando']; ?>', 2);
		xajax_f3059_AprobarDocumento(params);
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
		document.getElementById("saiu47agno").focus();
	}

	function buscarV2016(sCampo) {
		window.document.frmedita.iscroll.value = window.scrollY;
		expandesector(98);
		window.document.frmedita.scampobusca.value = sCampo;
		let params = new Array();
		params[1] = sCampo;
		//params[2] = window.document.frmedita.iagno.value;
		//params[3] = window.document.frmedita.itipo.value;
		xajax_f3047_Busquedas(params);
	}

	function Devuelve(sValor) {
		let sCampo = window.document.frmedita.scampobusca.value;
		if (sCampo == 'saiu47idsolicitante') {
			ter_traerxid('saiu47idsolicitante', sValor);
		}
		if (sCampo == 'saiu47idbenefdevol') {
			ter_traerxid('saiu47idbenefdevol', sValor);
		}
		if (sCampo == 'saiu47idaprueba') {
			ter_traerxid('saiu47idaprueba', sValor);
		}
		if (sCampo == 'saiu48idusuario') {
			ter_traerxid('saiu48idusuario', sValor);
		}
		if (sCampo == 'saiu49idresponsable') {
			ter_traerxid('saiu49idresponsable', sValor);
		}
		if (sCampo == 'saiu49usuario') {
			ter_traerxid('saiu49usuario', sValor);
		}
		if (sCampo == 'saiu59idusuario') {
			ter_traerxid('saiu59idusuario', sValor);
		}
		if (sCampo == 'saiu59idrevisa') {
			ter_traerxid('saiu59idrevisa', sValor);
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
		if (ref == 3059) {
			if (sRetorna != '') {
				window.document.frmedita.saiu59idorigen.value = window.document.frmedita.div96v1.value;
				window.document.frmedita.saiu59idarchivo.value = sRetorna;
				verboton('beliminasaiu59idarchivo', 'block');
			}
			//archivo_lnk(window.document.frmedita.saiu59idorigen.value, window.document.frmedita.saiu59idarchivo.value, 'div_saiu59idarchivo');
			paginarf3059();
		}
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

	function paginarf3000() {
		var params = new Array();
		params[0] = window.document.frmedita.id11.value;
		params[1] = 3047;
		params[2] = window.document.frmedita.saiu47agno.value;
		params[3] = window.document.frmedita.saiu47id.value;
		params[99] = window.document.frmedita.debug.value;
		params[100] = window.document.frmedita.saiu47idsolicitante.value;
		params[101] = window.document.frmedita.paginaf3000.value;
		params[102] = window.document.frmedita.lppf3000.value;
		//params[103]=window.document.frmedita.bnombre3000.value;
		//params[104]=window.document.frmedita.blistar3000.value;
		document.getElementById('div_f3000detalle').innerHTML = '<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf3000" name="paginaf3000" type="hidden" value="' + params[101] + '" /><input id="lppf3000" name="lppf3000" type="hidden" value="' + params[102] + '" />';
		xajax_f3000_HtmlTabla(params);
	}
<?php
if ($bConBotonNotificar) {
?>

	function notificar() {
		window.document.frmedita.iscroll.value = window.pageYOffset;
		ModalConfirmV2('Se va a enviar notificar al usuario, desea continuar?', () => {
			ejecuta_notificar();
		});
	}

	function ejecuta_notificar() {
		expandesector(98);
		window.document.frmedita.paso.value = 23;
		window.document.frmedita.submit();
	}
<?php
}
if ($bPuedeDevolver) {
?>
function devolverestado() {
	window.document.frmedita.iscroll.value = window.pageYOffset;
	ModalConfirmV2('Se va a reversar el estado de la solicitud, esta seguro?', () => {
		ejecuta_devolverestado();
	});
}

function ejecuta_devolverestado() {
	expandesector(98);
	window.document.frmedita.paso.value = 28;
	window.document.frmedita.submit();
}
<?php
}
?>
function estadocuenta(){
	window.document.frmestadocuenta.fact07idtercero.value = window.document.frmedita.saiu47idsolicitante.value;
	window.document.frmestadocuenta.submit();
}
function enviareasignar() {
	window.document.frmedita.iscroll.value = window.pageYOffset;
	ModalConfirm('¿Esta seguro de hacer la reasignación?');
	ModalDialogConfirm(function(confirm) {
		if (confirm) {
			ejecuta_enviareasignar();
		}
	});
}

function ejecuta_enviareasignar() {
	MensajeAlarmaV2('<?php echo $ETI['msg_ejecutando']; ?>', 2);
	expandesector(98);
	window.document.frmedita.paso.value = 29;
	window.document.frmedita.submit();
}

function calculartiempos() {
	MensajeAlarmaV2('<?php echo $ETI['msg_ejecutando']; ?>', 2);
	expandesector(98);
	window.document.frmedita.paso.value = 91;
	window.document.frmedita.submit();
}

</script>
<?php
if ($_REQUEST['paso'] != 0) {
?>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>jsi/js3048.js?v=2"></script>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>jsi/js3049.js"></script>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>jsi/js3059.js?v=6"></script>
<?php
}
?>
<form id="frmimpp" name="frmimpp" method="post" action="p3047.php" target="_blank" style="display:none">
<input id="r" name="r" type="hidden" value="3047" />
<input id="id3047" name="id3047" type="hidden" value="<?php echo $_REQUEST['saiu47id']; ?>" />
<input id="v3" name="v3" type="hidden" value="" />
<input id="v4" name="v4" type="hidden" value="" />
<input id="v5" name="v5" type="hidden" value="" />
<input id="v6" name="v6" type="hidden" value="" />
<input id="v7" name="v7" type="hidden" value="" />
<input id="v8" name="v8" type="hidden" value="" />
<input id="v9" name="v9" type="hidden" value="" />
<input id="v10" name="v10" type="hidden" value="" />
<input id="v11" name="v11" type="hidden" value="" />
<input id="v12" name="v12" type="hidden" value="" />
<input id="iformato94" name="iformato94" type="hidden" value="0" />
<input id="separa" name="separa" type="hidden" value="," />
<input id="rdebug" name="rdebug" type="hidden" value="<?php echo $_REQUEST['debug']; ?>" />
<input id="clave" name="clave" type="hidden" value="" />
</form>
<form id="frmestadocuenta" name="frmestadocuenta" method="post" action="../facturacion/factestcuenta.php" target="_blank" style="display:none">
<input id="fact07idtercero" name="fact07idtercero" type="hidden" value="" />
<input id="paso" name="paso" type="hidden" value="0" />
</form>
<form id="frmlista" name="frmlista" method="post" action="listados.php" target="_blank" style="display:none">
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
<input id="saiu59idorigen" name="saiu59idorigen" type="hidden" value="" />
<input id="saiu59idarchivo" name="saiu59idarchivo" type="hidden" value="" />
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
?>
<input id="cmdLimpiar" name="cmdLimpiar" type="button" class="btUpLimpiar" onclick="limpiapagina();" title="<?php echo $ETI['bt_limpiar']; ?>" value="<?php echo $ETI['bt_limpiar']; ?>" />
<?php
if ($bPuedeGuardar) {
?>
<input id="cmdGuardar" name="cmdGuardar" type="button" class="btUpGuardar" onclick="enviaguardar();" title="<?php echo $ETI['bt_guardar']; ?>" value="<?php echo $ETI['bt_guardar']; ?>" />
<?php
}
if ($bConBotonCerrar) {
?>
<input id="cmdCerrar" name="cmdCerrar" type="button" class="btSupCerrar" onclick="enviacerrar();" title="<?php echo $ETI['bt_cerrar']; ?>" value="<?php echo $ETI['bt_cerrar']; ?>" />
<?php
}
if ($bPuedeAbrir) {
?>
<input id="cmdAbrir" name="cmdAbrir" type="button" class="btSupAbrir" onclick="enviaabrir();" title="<?php echo $ETI['bt_abrir']; ?>" value="<?php echo $ETI['bt_abrir']; ?>" />
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
<?php
//Div para ocultar
$bConExpande = true;
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
$bOculto = false;
if ($_REQUEST['paso'] != 0) {
	if ($_REQUEST['saiu47estado'] != 0) {
		$bOculto = true;
	}
}
if (!$bOculto) {
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
	$bBloqueaFecha = true;
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
echo html_HoraMin('saiu47hora', $_REQUEST['saiu47hora'], 'saiu47minuto', $_REQUEST['saiu47minuto'], $bOculto);
?>
</div>
<div class="salto1px"></div>
<input id="saiu47tiporadicado" name="saiu47tiporadicado" type="hidden" value="<?php echo $_REQUEST['saiu47tiporadicado']; ?>" />
<?php
if (false) {
?>
<label class="Label130">
<?php
echo $ETI['saiu47tipotramite'];
?>
</label>
<label>
<div id="div_saiu47tipotramite">
<?php
echo $html_saiu47tipotramite;
?>
</div>
</label>
<?php
} else {
?>
<input id="saiu47tipotramite" name="saiu47tipotramite" type="hidden" value="<?php echo $_REQUEST['saiu47tipotramite']; ?>" />
<?php
}
?>
<label class="Label130"<?php echo $sOcultaConsec; ?>>
<?php
echo $ETI['saiu47consec'];
?>
</label>
<label class="Label130"<?php echo $sOcultaConsec; ?>>
<?php
if ($_REQUEST['paso'] != 2) {
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
<label style="width:400px;">
<div id="div_saiu47estado">
<?php
echo $html_saiu47estado;
?>
</div>
</label>
<?php
if ($bAplicaAbandono) {
?>
<label class="Label160">
<?php
echo $ETI['saiu47prob_abandono'];
?>
</label>
<label class="Label160">
<div id="div_saiu47prob_abandono">
<?php
echo html_oculto('saiu47prob_abandono', $_REQUEST['saiu47prob_abandono'], fecha_desdenumero($_REQUEST['saiu47prob_abandono'])); //formato_FechaLargaDesdeNumero
?>
</div>
</label>
<?php
} else {
?>
<input id="saiu47prob_abandono" name="saiu47prob_abandono" type="hidden" value="<?php echo $_REQUEST['saiu47prob_abandono']; ?>" />
<?php
}
if ((int)$_REQUEST['saiu47tem_radicado'] != 0) {
?>
<label class="Label130">
<?php
echo $ETI['saiu47tem_radicado'];
?>
</label>
<label class="Label160">
<div id="div_saiu47tem_radicado">
<?php
echo html_oculto('saiu47tem_radicado', $_REQUEST['saiu47tem_radicado'], fecha_desdenumero($_REQUEST['saiu47tem_radicado'])); //formato_FechaLargaDesdeNumero
?>
</div>
</label>
<?php
} else {
?>
<input id="saiu47tem_radicado" name="saiu47tem_radicado" type="hidden" value="<?php echo $_REQUEST['saiu47tem_radicado']; ?>" />
<?php
}
if ($_REQUEST['paso'] != 0) {
	echo $objForma->htmlBotonSolo('btTiempos', 'btMiniActualizar', 'calculartiempos()', 'Calcular tiempos', 30);
}
?>

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
<?php
if ($bPuedeGuardar) {
?>
<input id="saiu47t1vrsolicitado" name="saiu47t1vrsolicitado" type="text" value="<?php echo formato_numero($_REQUEST['saiu47t1vrsolicitado'], 2); ?>" class="veinte" maxlength="15" style="text-align:right" onchange="formatea_moneda(this);" placeholder="<?php echo $ETI['ing_vr']; ?>" />
<?php
} else {
	echo html_oculto('saiu47t1vrsolicitado', $_REQUEST['saiu47t1vrsolicitado'], formato_moneda($_REQUEST['saiu47t1vrsolicitado']));
}
?>
</label>
<?php
if ($bConSaltoEstadoDeCuenta) {
?>
<div class="salto1px"></div>
<label class="Label130"></label>
<label class="Label160">
<input id="cmdEstadoCuenta" name="cmdEstadoCuenta" type="button" class="BotonAzul160" value="Estado de cuenta" onclick="javascript:estadocuenta()" title="Ir al estado de cuenta" />
</label>
<?php
}
?>
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
echo html_FechaEnNumero('saiu47t707fecha', $_REQUEST['saiu47t707fecha'], false, 2011, $iAgno);
?>
</div>
<?php
} else {
?>
<input id="saiu47t707fecha" name="saiu47t707fecha" type="hidden" value="<?php echo $_REQUEST['saiu47t707fecha']; ?>" />
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
<?php
//Para reconocimiento de recaudos el proceso se arma con los complementos aqui.
if ($iTipoTramite == 707) {
?>
<div class="salto1px"></div>
<div class="GrupoCampos520">
<label class="Label130">
<?php
echo $ETI['saiu47t707formarecaudo'];
?>
</label>
<label>
<?php
echo $html_saiu47t707formarecaudo;
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu47t707idbanco'];
?>
</label>
<label>
<?php
echo $html_saiu47t707idbanco;
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu47t707idcuenta'];
?>
</label>
<label>
<div id="div_saiu47t707idcuenta">
<?php
echo $html_saiu47t707idcuenta;
?>
</div>
</label>
<div class="salto1px"></div>
</div>

<div class="GrupoCampos520">
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu47t707identidadconv'];
?>
</label>
<label>
<?php
echo $html_saiu47t707identidadconv;
?>
</label>
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
<?php
} else {
?>
<input id="saiu47t707formarecaudo" name="saiu47t707formarecaudo" type="hidden" value="<?php echo $_REQUEST['saiu47t707formarecaudo']; ?>" />
<input id="saiu47t707idbanco" name="saiu47t707idbanco" type="hidden" value="<?php echo $_REQUEST['saiu47t707idbanco']; ?>" />
<input id="saiu47t707idcuenta" name="saiu47t707idcuenta" type="hidden" value="<?php echo $_REQUEST['saiu47t707idcuenta']; ?>" />
<input id="saiu47t707identidadconv" name="saiu47t707identidadconv" type="hidden" value="<?php echo $_REQUEST['saiu47t707identidadconv']; ?>" />
<?php
}
if ($bConHistorial) {
?>
<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo $ETI['titulo_3000'];
?>
</label>
<input id="boculta3000" name="boculta3000" type="hidden" value="<?php echo $_REQUEST['boculta3000']; ?>" />
<div class="salto1px"></div>
<div id="div_f3000detalle">
<?php
echo $sTabla3000;
?>
</div>
<div class="salto1px"></div>
</div>
<?php
} else {
?>
<input id="boculta3000" name="boculta3000" type="hidden" value="<?php echo $_REQUEST['boculta3000']; ?>" />
<?php
}
?>
<div class="salto1px"></div>
<div class="GrupoCampos520">
<label class="TituloGrupo">
<?php
echo $ETI['saiu47detalle'];
?>
</label>
<?php
if ($bEditaDetalle) {
?>
<label class="txtAreaM">
<textarea id="saiu47detalle" name="saiu47detalle" placeholder="<?php echo $ETI['ing_campo'] . $ETI['saiu47detalle']; ?>"><?php echo $_REQUEST['saiu47detalle']; ?></textarea>
</label>
<?php
} else {
?>
<label class="L">
<?php
echo html_oculto('saiu47detalle', $_REQUEST['saiu47detalle']);
?>
</label>
<?php
}
?>
</div>
<?php
if ($_REQUEST['saiu47estado'] == 0 || $_REQUEST['saiu47estado'] == 5) {
	$sEstiloDiv = ' style="display:none;"';
} else {
	$sEstiloDiv = ' style="display:block;"';
}
if (true) {
?>
<div class="GrupoCampos520" <?php echo $sEstiloDiv; ?>>
<label class="TituloGrupo">
<?php
echo $ETI['saiu47asignado'];
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu47idunidad'];
?>
</label>
<label style="width:360px">
<?php
echo $et_saiu47idunidad;
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu47idgrupotrabajo'];
?>
</label>
<label style="width:360px">
<?php
echo $et_saiu47idgrupotrabajo;
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu47idresponsable'];
?>
</label>
<label style="width:360px">
<?php 
echo $et_saiu47idresponsable; 
?>
</label>
<div class="salto1px"></div>
<?php
if ($bConBotonReasignar) {
?>
<label class="Label130">&nbsp;</label>
<label class="Label160">
<input id="cmdReasignar" name="cmdReasignar" type="button" class="BotonAzul160" value="Reasignar" onclick="expandesector(2);" title="Realizar Reasignaci&oacute;n" />
</label>
<?php
}
?>
<div class="salto1px"></div>
</div>
<?php
} else {
?>
<input id="saiu47idunidad" name="saiu47idunidad" type="hidden" value="<?php echo $_REQUEST['saiu47idunidad']; ?>" />
<input id="saiu47idgrupotrabajo" name="saiu47idgrupotrabajo" type="hidden" value="<?php echo $_REQUEST['saiu47idgrupotrabajo']; ?>" />
<input id="saiu47idresponsable" name="saiu47idresponsable" type="hidden" value="<?php echo $_REQUEST['saiu47idresponsable']; ?>" />
<?php
}
if ($bMostrarSaldos) {
?>
<div class="GrupoCampos450">
<label class="Label130">
<?php
echo $ETI['saiu47t1vraprobado'];
?>
</label>
<label class="Label160">
<?php
if ($bPuedeGuardar2) {
?>
<input id="saiu47t1vraprobado" name="saiu47t1vraprobado" type="text" value="<?php echo formato_numero($_REQUEST['saiu47t1vraprobado'], 2); ?>" class="veinte" maxlength="15" style="text-align:right" onchange="formatea_moneda(this);" placeholder="<?php echo $ETI['ing_vr']; ?>" />
<?php
} else {
	echo html_oculto('saiu47t1vraprobado', $_REQUEST['saiu47t1vraprobado'], formato_moneda($_REQUEST['saiu47t1vraprobado']));
}
?>
</label>
<?php
} else {
?>
<input id="saiu47t1vraprobado" name="saiu47t1vraprobado" type="hidden" value="<?php echo $_REQUEST['saiu47t1vraprobado']; ?>" />
<?php
}
if ($bConBeneficiario) {
?>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu47t1vrsaldoafavor'];
?>
</label>
<label class="Label160">
<?php
if ($bPuedeGuardar2) {
?>
<input id="saiu47t1vrsaldoafavor" name="saiu47t1vrsaldoafavor" type="text" value="<?php echo formato_numero($_REQUEST['saiu47t1vrsaldoafavor'], 2); ?>" class="veinte" maxlength="15" style="text-align:right" onchange="formatea_moneda(this);" placeholder="<?php echo $ETI['ing_vr']; ?>" />
<?php
} else {
	echo html_oculto('saiu47t1vrsaldoafavor', $_REQUEST['saiu47t1vrsaldoafavor'], formato_moneda($_REQUEST['saiu47t1vrsaldoafavor']));
}
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
if ($bPuedeGuardar2) {
?>
<input id="saiu47t1vrdevolucion" name="saiu47t1vrdevolucion" type="text" value="<?php echo formato_numero($_REQUEST['saiu47t1vrdevolucion'], 2); ?>" class="veinte" maxlength="15" style="text-align:right" onchange="formatea_moneda(this);" placeholder="<?php echo $ETI['ing_vr']; ?>" />
<?php
} else {
	echo html_oculto('saiu47t1vrdevolucion', $_REQUEST['saiu47t1vrdevolucion'], formato_moneda($_REQUEST['saiu47t1vrdevolucion']));
}
?>
</label>
<?php
if ($bPuedeGuardar2) {
?>
<label class="Label30"></label>
<label class="Label30">
<input id="btguarda2" name="btguarda2" type="button" value="Guardar" class="btMiniGuardar" onclick="enviaguardar()" title="<?php echo $ETI['bt_guardar']; ?>" />
</label>
<?php
}
?>
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
<?php
} else {
	if ($bPuedeGuardar2) {
?>
<label class="Label30"></label>
<label class="Label30">
<input id="btguarda2" name="btguarda2" type="button" value="Guardar" class="btMiniGuardar" onclick="enviaguardar()" title="<?php echo $ETI['bt_guardar']; ?>" />
</label>
<?php
	}
?>
<input id="saiu47t1vrsaldoafavor" name="saiu47t1vrsaldoafavor" type="hidden" value="<?php echo $_REQUEST['saiu47t1vrsaldoafavor']; ?>" />
<input id="saiu47t1vrdevolucion" name="saiu47t1vrdevolucion" type="hidden" value="<?php echo $_REQUEST['saiu47t1vrdevolucion']; ?>" />
<input id="saiu47idbenefdevol" name="saiu47idbenefdevol" type="hidden" value="<?php echo $_REQUEST['saiu47idbenefdevol']; ?>" />
<input id="saiu47idbenefdevol_td" name="saiu47idbenefdevol_td" type="hidden" value="<?php echo $_REQUEST['saiu47idbenefdevol_td']; ?>" />
<input id="saiu47idbenefdevol_doc" name="saiu47idbenefdevol_doc" type="hidden" value="<?php echo $_REQUEST['saiu47idbenefdevol_doc']; ?>" />
<?php
}
if ($bMostrarSaldos) {
?>
<div class="salto1px"></div>
</div>
<?php
}
// --
?>
<div class="salto5px"></div>
<?php
$bCajaBotones = $bConBotonRadicar || $bConBotonRevisar || $bConBotonDevolver || $bConBotonCptoJuridico || $bConBotonACptoTecnico;
if (!$bCajaBotones) {
	$bCajaBotones = $bConBotonActoAdmin || $bConBotonApruebaGAF || $bConBotonFirma || $bConBotonPago || $bConBotonNoProcedente;
}
if (!$bCajaBotones) {
	$bCajaBotones = $bConBotonAplicar || $bConBotonAProyeccionDeRespuesta || $bConBotonDevolverARCA || $bConBotonDevolverActo;
}
if (!$bCajaBotones) {
	$bCajaBotones = $bConBotonNumeracion || $bConBotonFirmaVISAE || $bConBotonAContabilidad;
}
if ($bCajaBotones) {
?>
<div class="GrupoCamposAyuda">
<label class="Label60">&nbsp;</label>
<?php
}
if ($bConBotonRadicar) {
?>
<label class="Label160">
<input id="cmdRadicar" name="cmdRadicar" type="button" class="BotonAzul160" value="Radicar Solicitud" onclick="enviacerrar();" title="Radicar Solicitud" />
</label>
<?php
}
if ($bConBotonRevisar) {
?>
<label class="Label160">
<input id="cmdRevisar" name="cmdRevisar" type="button" class="BotonAzul160" value="Iniciar Revisi&oacute;n" onclick="enviarevision();" title="Iniciar a Revisi&oacute;n de Documentos" />
</label>
<?php
}
if ($bConBotonDevolver) {
?>
<label class="Label160">
<input id="cmdPanelDevolver" name="cmdPanelDevolver" type="button" class="BotonAzul160" value="Devolver" onclick="expandesector(3);" title="Devolver tramite" />
</label>
<?php
}
if ($bConBotonCptoJuridico) {
?>
<label class="Label60">&nbsp;</label>
<label class="Label160">
<input id="cmdCptoJuridico" name="cmdCptoJuridico" type="button" class="BotonAzul160" value="Concepto Jur&iacute;dico" onclick="enviacptojuridico();" title="Pasar A Concepto Jur&iacute;dico" />
</label>
<?php
}
if ($bConBotonACptoTecnico) {
?>
<label class="Label60">&nbsp;</label>
<label class="Label160">
<input id="cmdCptoTecnico" name="cmdCptoTecnico" type="button" class="BotonAzul160" value="Concepto T&eacute;cnico" onclick="enviacptotecnico();" title="Pasar A Concepto T&eacute;cnico" />
</label>
<?php
}
if ($bConBotonActoAdmin) {
?>
<label class="Label160">
<input id="cmdProcedente" name="cmdProcedente" type="button" class="BotonAzul160" value="Proyectar" onclick="enviaactoadmin();" title="Proyectar Acto Administrativo" />
</label>
<?php
}
if ($bConBotonNoProcedente) {
?>
<label class="Label60">&nbsp;</label>
<label class="Label160">
<input id="cmdNoProcedente" name="cmdNoProcedente" type="button" class="BotonAzul160" value="No Procedente" onclick="expandesector(4);" title="No Procedente" />
</label>
<?php
}

if ($bConBotonDevolverActo) {
	echo html_Contenedor('&nbsp;', 60);
	echo $objForma->htmlBotonSolo('cmdDevolverActo', 'BotonAzul', 'devolver_acto()', 'Devolver Acto Admin', 160);
}
if ($bConBotonNumeracion) {
	echo html_Contenedor('&nbsp;', 60);
	echo $objForma->htmlBotonSolo('cmdNumerar', 'BotonAzul', 'enviaranumeracion()', 'Pasar a Numeraci&oacute;n', 160);
}
if ($bConBotonFirmaVISAE) {
	echo html_Contenedor('&nbsp;', 60);
	echo $objForma->htmlBotonSolo('cmdFirmaVISAE', 'BotonAzul', 'afirmavisae()', 'Firma VISAE', 160);
}

if ($bConBotonAProyeccionDeRespuesta) {
	echo html_Contenedor('&nbsp;', 60);
	//$sEtiqueta = 'Proyecci&oacute;n de respuesta';
	//$iAnchoBoton = 160;
	$sEtiqueta = 'Proyecci&oacute;n de respuesta no procedente';
	$iAnchoBoton = 220;
	echo $objForma->htmlBotonSolo('cmdProyRta', 'BotonAzul', 'enviaproyrta()', $sEtiqueta, $iAnchoBoton);
}
if ($bConBotonDevolverARCA) {
	echo html_Contenedor('&nbsp;', 60);
	echo $objForma->htmlBotonSolo('cmdDevolverRCA', 'BotonAzul', 'devolverrca()', 'Devolver a RCONT', 160);
}
if ($bConBotonApruebaGAF) {
?>
<label class="Label60">&nbsp;</label>
<label class="Label160">
<input id="cmdGAF" name="cmdGAF" type="button" class="BotonAzul160" value="Aprobaci&oacute;n GAF" onclick="enviagaf();" title="Enviar a Aprobaci&oacute;n de la GAF" />
</label>
<?php
}
if ($bConBotonFirma) {
	echo html_Contenedor('&nbsp;', 60);
	echo $objForma->htmlBotonSolo('cmdFirma', 'BotonAzul', 'enviafirma()', 'Firma GAF', 160);
}
if ($bConBotonAContabilidad) {
	echo html_Contenedor('&nbsp;', 60);
	echo $objForma->htmlBotonSolo('cmdContabilidad', 'BotonAzul', 'enviaracontabilidad()', 'Enviar a contabilidad', 160);
}
if ($bConBotonPago) {
?>
<label class="Label60">&nbsp;</label>
<label class="Label160">
<input id="cmdPago" name="cmdPago" type="button" class="BotonAzul160" value="Enviar a pago" onclick="enviapago();" title="Enviar a Proceso de Pago" />
</label>
<?php
}
if ($bConBotonAplicar) {
?>
<label class="Label60">&nbsp;</label>
<label class="Label160">
<input id="cmdAplicar" name="cmdAplicar" type="button" class="BotonAzul160" value="Aplicar" onclick="enviaaplicar();" title="Aplicar" />
</label>
<?php
}
if ($bCajaBotones) {
?>
<div class="salto1px"></div>
</div>
<?php
}

// --
if ($bConAprueba) {
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
$bOculto = true;
echo html_DivTerceroV2('saiu47idaprueba', $_REQUEST['saiu47idaprueba_td'], $_REQUEST['saiu47idaprueba_doc'], $bOculto, 0, $ETI['ing_doc']);
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
echo html_oculto('saiu47fechaaprueba', $_REQUEST['saiu47fechaaprueba'], fecha_desdenumero($_REQUEST['saiu47fechaaprueba']));
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
<div class="salto1px"></div>
<input id="boculta3048" name="boculta3048" type="hidden" value="<?php echo $_REQUEST['boculta3048']; ?>" />
<?php
// -- Inicia Grupo campos 3048 Anotaciones
?>
<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo $ETI['titulo_3048'];
?>
</label>
<?php
if ($_REQUEST['paso'] == 2) {
//if ($bCondicion){
?>
<div class="ir_derecha"<?php echo $sAnchoExpandeContrae; ?>>
<?php
if ($bConBotonNotificar) {
?>
<label class="Label130">
<input id="btNotificar" name="btNotificar" type="button" value="Notificar" class="BotonAzul" onclick="notificar();" title="Notificar al usuario" />
</label>
<?php
}
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
<label class="Label220">
<?php
echo $ETI['saiu48visiblealinteresado'];
?>
</label>
<label class="Label30">
<?php
echo $html_saiu48visiblealinteresado;
?>
</label>
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
echo html_oculto('saiu48fecha', $_REQUEST['saiu48fecha'], fecha_desdenumero($_REQUEST['saiu48fecha'])); //formato_FechaLargaDesdeNumero
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
}
?>
<div class="salto1px"></div>
</div>
<?php
// -- Termina Grupo campos 3048 Anotaciones
?>
<input id="boculta3059" name="boculta3059" type="hidden" value="<?php echo $_REQUEST['boculta3059']; ?>" />
<?php
// -- Inicia Grupo campos 3059 Anexos
?>
<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo $ETI['titulo_3059'];
?>
</label>
<?php
if ($_REQUEST['paso'] == 2) {
if ($bPuedeAgregarArchivos) {
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
echo $objForma->htmlExpande(3059, $_REQUEST['boculta3059'], $ETI['bt_mostrar'], $ETI['bt_ocultar']);
$sEstiloDiv = '';
if ($_REQUEST['boculta3059'] != 0) {
	$sEstiloDiv = ' style="display:none;"';
}
?>
</div>
<div class="salto1px"></div>
<div id="div_p3059"<?php echo $sEstiloDiv; ?>>

<div class="GrupoCampos520">
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
<div class="salto1px"></div>
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
<div class="salto1px"></div>
</div>

<input id="saiu59idorigen" name="saiu59idorigen" type="hidden" value="<?php echo $_REQUEST['saiu59idorigen']; ?>" />
<input id="saiu59idarchivo" name="saiu59idarchivo" type="hidden" value="<?php echo $_REQUEST['saiu59idarchivo']; ?>" />
<div class="GrupoCampos300">
<div class="salto1px"></div>
<div id="div_saiu59idarchivo" class="Campo300">
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
<label class="Label130"></label>
<label class="Label30">
<input id="banexasaiu59idarchivo" name="banexasaiu59idarchivo" type="button" value="Anexar" class="btMiniAnexar" onclick="carga_saiu59idarchivob()" title="Cargar archivo"<?php echo $sEstiloAnexa; ?>/>
</label>
<label class="Label30">
<input id="beliminasaiu59idarchivo" name="beliminasaiu59idarchivo" type="button" value="Eliminar" class="btMiniEliminar" onclick="eliminasaiu59idarchivob()" title="Eliminar archivo"<?php echo $sEstiloElimina; ?>/>
</label>
<div class="salto1px"></div>
</div>

<?php
if (false) {
?>
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
<?php
} else {
?>
<input id="saiu59opcional" name="saiu59opcional" type="hidden" value="<?php echo $_REQUEST['saiu59opcional']; ?>" />
<input id="saiu59idestado" name="saiu59idestado" type="hidden" value="<?php echo $_REQUEST['saiu59idestado']; ?>" />
<input id="saiu59idusuario" name="saiu59idusuario" type="hidden" value="<?php echo $_REQUEST['saiu59idusuario']; ?>" />
<input id="saiu59fecha" name="saiu59fecha" type="hidden" value="<?php echo $_REQUEST['saiu59fecha']; ?>" />
<input id="saiu59idrevisa" name="saiu59idrevisa" type="hidden" value="<?php echo $_REQUEST['saiu59idrevisa']; ?>" />
<input id="saiu59idrevisa_td" name="saiu59idrevisa_td" type="hidden" value="<?php echo $_REQUEST['saiu59idrevisa_td']; ?>" />
<input id="saiu59idrevisa_doc" name="saiu59idrevisa_doc" type="hidden" value="<?php echo $_REQUEST['saiu59idrevisa_doc']; ?>" />
<input id="saiu59fecharevisa" name="saiu59fecharevisa" type="hidden" value="<?php echo $_REQUEST['saiu59fecharevisa']; ?>" />
<?php
}
?>
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
}
?>
<div class="salto1px"></div>
</div>
<?php
// -- Termina Grupo campos 3059 Anexos
?>
<input id="boculta3049" name="boculta3049" type="hidden" value="<?php echo $_REQUEST['boculta3049']; ?>" />
<?php
// -- Inicia Grupo campos 3049 Cambios de estado
?>
<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo $ETI['titulo_3049'];
?>
</label>
<?php
if ($_REQUEST['paso'] == 2) {
	if ($bPuedeDevolver) {
?>
<label class="Label60"></label>
<label class="Label130">
<input id="cmdDevolver" name="cmdDevolver" type="button" class="BotonAzul" value="Devolver" onclick="devolverestado();" title="Devolver estado" />
</label>
<?php
	}
?>
<div class="salto1px"></div>
<div id="div_f3049detalle">
<?php
echo $sTabla3049;
?>
</div>
<?php
}
?>
<div class="salto1px"></div>
</div>
<?php
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
echo '<h3>' . $ETI['bloque1'] . '</h3>';
?>
</div>
<div class="areatrabajo">
<div class="ir_derecha">
<label class="Label130">
<?php
echo $ETI['msg_bdoc'];
?>
</label>
<label>
<input id="bdoc" name="bdoc" type="text" value="<?php echo $_REQUEST['bdoc']; ?>" onchange="paginarf3047()" autocomplete="off" />
</label>
<label class="Label90">
<?php
echo $ETI['msg_bnombre'];
?>
</label>
<label>
<input id="bnombre" name="bnombre" type="text" value="<?php echo $_REQUEST['bnombre']; ?>" onchange="paginarf3047()" autocomplete="off" />
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu47estado'];
?>
</label>
<label class="Label130">
<?php
echo $html_blistar2;
?>
</label>
<?php
if (false) {
?>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu47tipotramite'];
?>
</label>
<label>
<?php
echo $html_btipo;
?>
</label>
<?php
} else {
?>
<input id="btipo" name="btipo" type="hidden" value="<?php echo $iTipoTramite; ?>" />
<?php
}
?>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu47t1idmotivo'];
?>
</label>
<label>
<div id="div_bmotivo">
<?php
echo $html_bmotivo;
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu47idunidad'];
?>
</label>
<label>
<div id="div_bunidad">
<?php
echo $html_bunidad;
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu47asignado'];
?>
</label>
<label>
<div id="div_bresponsable">
<?php
echo $html_bresponsable;
?>
</div>
</label>
<label class="Label60">
<?php
echo $ETI['msg_bagno'];
?>
</label>
<label class="Label130">
<?php
echo $html_bagno;
?>
</label>
<?php
if (false) {
?>
<label class="Label90">
<?php
echo $ETI['saiu47estado'];
?>
</label>
<label class="Label130">
<?php
echo $html_blistar;
?>
</label>
<?php
} else {
?>
<input id="blistar" name="blistar" type="hidden" value="<?php echo $_REQUEST['blistar']; ?>" />
<?php
}
?>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu47idzona'];
?>
</label>
<label>
<?php
echo $html_bzona;
?>
</label>
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
<?php
echo ' ' . $csv_separa;
?>
<div class="salto1px"></div>
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
echo '<h2>' . $ETI['titulo_sector2_saiu47'] . '</h2>';
?>
</div>
</div>
<?php
}
?>
<div id="cargaForm">
<div id="area">
<?php
if ($bConBotonReasignar) {
	$sEstiloDiv = ' style="display:block;"';
} else {
	$sEstiloDiv = ' style="display:none;"';
}
?>
<div class="GrupoCampos" <?php echo $sEstiloDiv; ?>>
<label class="TituloGrupo">
<?php
echo $ETI['msg_reasignar'];
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu47idunidad'];
?>
</label>
<label class="Label600">
<?php
echo $html_saiu47idunidad;
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu47idgrupotrabajo'];
?>
</label>
<label class="Label600">
<div id="div_saiu47idgrupotrabajo">
<?php
echo $html_saiu47idgrupotrabajo;
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu47idresponsable'];
?>
</label>
<label class="Label600">
<div id="div_saiu47idresponsable">
<?php 
echo $html_saiu47idresponsable; 
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label130">&nbsp;</label>
<label class="Label160">
<input id="cmdGuardarR" name="cmdGuardarR" type="button" class="BotonAzul160" value="Guardar" onclick="enviareasignar();" title="Guardar Reasignaci&oacute;n" />
</label>
<label class="Label30">&nbsp;</label>
<label class="Label130">
<input id="cmdVolverR" name="cmdVolverR" type="button" class="BotonAzul" value="Volver" onclick="expandesector(1);" title="Volver" />
</label>
<div class="salto1px"></div>
</div>
</div>
</div>
<?php
// Termina el div_sector2
?>
</div>


<div id="div_sector3" style="display:none">
<?php
if ($bBloqueTitulo) {
?>
<div class="titulos">
<div class="titulosD">
<input id="cmdAyuda3" name="cmdAyuda3" type="button" class="btSupAyuda" onclick="muestraayuda(<?php echo $iCodModulo; ?>);" title="<?php echo $ETI['bt_ayuda']; ?>" value="<?php echo $ETI['bt_ayuda']; ?>" />
<input id="cmdVolverSec3" name="cmdVolverSec3" type="button" class="btSupVolver" onclick="expandesector(1);" title="<?php echo $ETI['bt_volver']; ?>" value="<?php echo $ETI['bt_volver']; ?>" />
</div>
<div class="titulosI">
<?php
echo '<h2>' . $ETI['titulo_sector3'] . '</h2>';
?>
</div>
</div>
<?php
}
?>
<div id="cargaForm">
<div id="area">
<div class="GrupoCampos">
<label class="txtAreaS">
<?php
echo $ETI['msg_notadevol'];
?>
<textarea id="saiu48anotacion_b" name="saiu48anotacion_b" placeholder="<?php echo $ETI['ing_campoa'] . $ETI['saiu48anotacion']; ?>"><?php echo $_REQUEST['saiu48anotacion_b']; ?></textarea>
</label>
<div class="salto1px"></div>
</div>
<label class="Label130"></label>
<?php
if ($bConBotonDevolver) {
?>
<label class="Label160">
<input id="cmdDevolver" name="cmdDevolver" type="button" class="BotonAzul160" value="Devolver" onclick="enviadevolver();" title="Devolver tramite" />
</label>
<?php
}
?>
<label class="Label160">
<input id="cmdCancelar3" name="cmdCancelar3" type="button" class="BotonAzul" value="Cancelar" onclick="expandesector(1);" title="Cancelar" />
</label>
</div>
</div>
<?php
// Termina el div_sector3
?>
</div>


<div id="div_sector4" style="display:none">
<?php
if ($bBloqueTitulo) {
?>
<div class="titulos">
<div class="titulosD">
<input id="cmdAyuda4" name="cmdAyuda4" type="button" class="btSupAyuda" onclick="muestraayuda(<?php echo $iCodModulo; ?>);" title="<?php echo $ETI['bt_ayuda']; ?>" value="<?php echo $ETI['bt_ayuda']; ?>" />
<input id="cmdVolverSec4" name="cmdVolverSec4" type="button" class="btSupVolver" onclick="expandesector(1);" title="<?php echo $ETI['bt_volver']; ?>" value="<?php echo $ETI['bt_volver']; ?>" />
</div>
<div class="titulosI">
<?php
echo '<h2>' . $ETI['titulo_sector4'] . '</h2>';
?>
</div>
</div>
<?php
}
?>
<div id="cargaForm">
<div id="area">
<div class="GrupoCampos">
<?php
echo $sInfoNoProcedente;
?>
<div class="salto1px"></div>
<label class="txtAreaS">
<?php
echo $ETI['msg_notanoprocede'];
?>
<textarea id="saiu48anotacion_c" name="saiu48anotacion_c" placeholder="<?php echo $ETI['ing_campoa'] . $ETI['saiu48anotacion']; ?>"><?php echo $_REQUEST['saiu48anotacion_c']; ?></textarea>
</label>
<div class="salto1px"></div>
</div>
<label class="Label130"></label>
<?php
if ($bConBotonNoProcedente) {
?>
<label class="Label160">
<input id="cmdNoProcedente" name="cmdNoProcedente" type="button" class="BotonAzul160" value="No Procedente" onclick="envianoprocede();" title="No Procedente" />
</label>
<?php
}
?>
<label class="Label160">
<input id="cmdCancelar3" name="cmdCancelar3" type="button" class="BotonAzul" value="Cancelar" onclick="expandesector(1);" title="Cancelar" />
</label>
</div>
</div>
<?php
// Termina el div_sector4
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
echo $ETI['msg_saiu47consec'];
?>
</label>
<label class="Label90">
<?php
echo '<b>' . $_REQUEST['saiu47consec'] . '</b>';
?>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['msg_saiu47consec_nuevo'];
// onchange="RevisaConsec()"
?>
</label>
<label class="Label90">
<input id="saiu47consec_nuevo" name="saiu47consec_nuevo" type="text" value="<?php echo $_REQUEST['saiu47consec_nuevo']; ?>" class="cuatro" />
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
if ($bAplicaPeriodo){
?>
$("#saiu47idperiodo").chosen();
<?php
}
?>
$("#bunidad").chosen();
});
</script>
<script language="javascript" src="ac_3047.js"></script>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>unad_todas2024v2.js"></script>
<?php
forma_piedepagina();

