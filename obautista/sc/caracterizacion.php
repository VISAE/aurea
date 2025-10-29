<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2018 - 2024 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.21.0 viernes, 22 de junio de 2018
--- Modelo Versión 2.22.0 martes, 26 de junio de 2018
--- Modelo Versión 2.22.1 martes, 3 de julio de 2018
--- Modelo Versión 2.22.2 martes, 17 de julio de 2018
--- Modelo Versión 2.25.0 viernes, 3 de abril de 2020
--- Modelo Versión 2.25.5 domingo, 16 de agosto de 2020
--- Modelo Versión 3.0.11b viernes, 9 de agosto de 2024
--- Modelo Versión 3.0.11b viernes, 16 de agosto de 2024
--- Modelo Versión 3.0.12 viernes, 6 de septiembre de 2024
*/

/** Archivo caracterizacion.php.
 * Modulo 2301 cara01encuesta.
 * @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
 * @param debug=1 (Opcional), bandera para indicar si se generan datos de depuración
 * @date viernes, 3 de abril de 2020
 * Cambios 12 de junio de 2020
 * 1. interpretación a valores cualitativos de los niveles y riesgo de cada una de las fichas evaluadas.
 * 2. Visualización de los resultados de la encuesta en el momento en que el estudiante finaliza y da cierre.
 * Omar Augusto Bautista Mora - UNAD - 2020
 * omar.bautista@unad.edu.co
 */
if (file_exists('./err_control.php')) {
	require './err_control.php';
}
$bDebug = false;
$bVerIntro = false;
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
if (isset($_REQUEST['intro']) != 0) {
	$bVerIntro = true;
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
$iMinVerDB = 7964;
$iCodModulo = 2301;
$iCodModuloConsulta = $iCodModulo;
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
$mensajes_2301 = $APP->rutacomun . 'lg/lg_2301_' . $sIdioma . '.php';
if (!file_exists($mensajes_2301)) {
	$mensajes_2301 = $APP->rutacomun . 'lg/lg_2301_es.php';
}
$mensajes_2344 = $APP->rutacomun . 'lg/lg_2344_' . $sIdioma . '.php';
if (!file_exists($mensajes_2344)) {
	$mensajes_2344 = $APP->rutacomun . 'lg/lg_2344_es.php';
}
$mensajes_2310 = 'lg/lg_2310_' . $sIdioma . '.php';
if (!file_exists($mensajes_2310)) {
	$mensajes_2310 = 'lg/lg_2310_es.php';
}
require $mensajes_todas;
require $mensajes_2301;
require $mensajes_2344;
require $mensajes_2310;
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
$bEstudiante = true;
$bTienePeriodo = true;
if ($APP->idsistema == 23) {
	$bEstudiante = false;
}
$sOcultaId = ' style="display:none;"';
if ($bEstudiante) {
	$sGrupoModulo = '';
	$sPaginaModulo = '';
	$sTituloApp = 'Caracterizaci&oacute;n';
} else {
	list($sGrupoModulo, $sPaginaModulo) = f109_GrupoModulo($iCodModuloConsulta, $iConsecutivoMenu, $objDB);
	$sTituloApp = $APP->siglasistema; //f101_SiglaModulo($APP->idsistema, $objDB);
}
$sTituloModulo = $ETI['titulo_2301'];
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
	if ($bEstudiante) {
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
				if ($bEstudiante) {
					list($et_menu, $sDebugM) = html_menuCampusV2($objDB, $iPiel, $bDebugMenu, $idTercero);
				} else {
					list($et_menu, $sDebugM) = html_Menu2023($APP->idsistema, $objDB, $iPiel, $bDebugMenu, $idTercero);
				}
				break;
			default:
				if ($bEstudiante) {
					list($et_menu, $sDebugM) = html_menuCampus($APP->idsistema, $objDB, $iPiel, $bDebugMenu, $idTercero);
				} else {
					list($et_menu, $sDebugM) = html_menuV2($APP->idsistema, $objDB, $iPiel, $bDebugMenu, $idTercero);
				}
				break;
		}
	}
	$objDB->CerrarConexion();
	switch ($iPiel) {
		case 2:
			require $APP->rutacomun . 'unad_forma2024.php';
			forma_InicioV4($xajax, $sTituloModulo);
			if ($bEstudiante) {
				$aRutas = array(
					array('', $sTituloModulo)
				);
			} else {
				$aRutas = array(
					array('./', $sTituloApp),
					array('./' . $sPaginaModulo, $sGrupoModulo),
					array('', $sTituloModulo)
				);
			}
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
		header('Location:noticia.php?ret=caracterizacion.php');
		die();
	}
}
$seg_1707 = 0;
$bDevuelve = false;
if ($bEstudiante) {
	list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 1707, $_SESSION['unad_id_tercero'], $objDB, $bDebug);
}
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
// -- Si esta cargando la pagina por primer vez se revisa si requiere auditar y se manda a hacer un limpiar (paso -1)
if (isset($_REQUEST['paso']) == 0) {
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' No se recibe la variable marcador<br>';
		if (isset($_POST['paso']) != 0) {
			$sDebug = $sDebug . fecha_microtiempo() . ' La variable marcador viene por post ' . $_POST['paso'] . '<br>';
		}
		if (isset($_GET['r']) != 0) {
			$sDebug = $sDebug . fecha_microtiempo() . ' La variable marcador de registro esta vigente ' . $_GET['r'] . '<br>';
		}
	}
	$_REQUEST['paso'] = -1;
	if ($audita[1]) {
		seg_auditaingreso($iCodModulo, $_SESSION['unad_id_tercero'], $objDB);
	}
}
if ($bEstudiante) {
	if ($_REQUEST['paso'] == -1) {
		$_REQUEST['paso'] = 1;
	}
}
// -- 2301 cara01encuesta
require $APP->rutacomun . 'lib2301.php';
// -- 2310 Preguntas de la prueba
require 'lib2310.php';
$xajax = new xajax();
$xajax->configure('javascript URI', $APP->rutacomun . 'xajax/');
$xajax->register(XAJAX_FUNCTION, 'unad11_Mostrar_v2');
$xajax->register(XAJAX_FUNCTION, 'unad11_TraerXid');
$xajax->register(XAJAX_FUNCTION, 'f2301_Combocara01depto');
$xajax->register(XAJAX_FUNCTION, 'f2301_Combocara01ciudad');
$xajax->register(XAJAX_FUNCTION, 'f2301_Combocara01idcead');
$xajax->register(XAJAX_FUNCTION, 'f2301_Busqueda_cara01idconsejero');
$xajax->register(XAJAX_FUNCTION, 'sesion_abandona_V2');
$xajax->register(XAJAX_FUNCTION, 'sesion_mantenerV4');
$xajax->register(XAJAX_FUNCTION, 'f2301_HtmlTabla');
$xajax->register(XAJAX_FUNCTION, 'f2301_ExisteDato');
$xajax->register(XAJAX_FUNCTION, 'f2301_Busquedas');
$xajax->register(XAJAX_FUNCTION, 'f2301_HtmlBusqueda');
if ($bEstudiante) {
	$xajax->register(XAJAX_FUNCTION, 'f2310_GuardarRespuesta');
} else {
	$xajax->register(XAJAX_FUNCTION, 'f2301_MarcarConsejero');
	$xajax->register(XAJAX_FUNCTION, 'f2301_Combobprograma');
	$xajax->register(XAJAX_FUNCTION, 'f2301_Combobcead');
	$xajax->register(XAJAX_FUNCTION, 'f2301_Actualizaedad');
}
$xajax->register(XAJAX_FUNCTION, 'elimina_archivo_cara01discv2archivoorigen');
$xajax->processRequest();
if ($bPeticionXAJAX) {
	die(); // Esto hace que las llamadas por xajax terminen aquí.
}
$sUrlTablero = 'miscursos.php';
$sMensaje = '';
$sHTMLHistorial = '';
$bErrorInicioEnc = false;
$sErrorInicioEnc = '';
if (isset($APP->urltablero) != 0) {
	if (file_exists($APP->urltablero)) {
		$sUrlTablero = $APP->urltablero;
	}
}
if ($bEstudiante) {
	if ($_REQUEST['paso'] == 21) {
		$_REQUEST['paso'] = 1;
		list($sError, $sDebugE) = f2301_IniciarEncuesta($idTercero, 0, $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugE;
		if ($sError == '') {
			header('Refresh:0');
		} else {
			$bErrorInicioEnc = true;
			$sErrorInicioEnc = $sErrorInicioEnc . $sError;
		}
	}
	//Verificar que tenga una caracterizacion
	if (isset($_REQUEST['cara01idperaca']) == 0) {
		$_REQUEST['cara01idperaca'] = '';
		$sSQL = 'SELECT core01peracainicial FROM core01estprograma WHERE core01idtercero=' . $idTercero . ' ORDER BY core01peracainicial DESC';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' Consulta de verificacion ' . $sSQL . '<br>';
		}
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			$_REQUEST['cara01idperaca'] = $fila['core01peracainicial'];
			//Ver si ya tiene una caracterizacion.
			$sSQL = 'SELECT TB.cara01id, TB.cara01idperaca, TB.cara01completa, T2.exte02titulo 
			FROM cara01encuesta AS TB, exte02per_aca AS T2 
			WHERE TB.cara01idtercero=' . $idTercero . ' AND TB.cara01idperaca=T2.exte02id
			ORDER BY TB.cara01completa, TB.cara01idperaca DESC';
			$tabla = $objDB->ejecutasql($sSQL);
			$iNumFilas = $objDB->nf($tabla);
			if ($iNumFilas == 0) {
				$sMensaje = $ETI['msg_intro_nuevos'];
				if ($_REQUEST['cara01idperaca'] == 87) {
					$sMensaje = $ETI['msg_intro_antiguos'];
				}
				$objDB->CerrarConexion();
				$bVerIntro = true;
			} else {
				$fila = $objDB->sf($tabla);
				$_REQUEST['cara01idperaca'] = $fila['cara01idperaca'];
			}
		} else {
			// Sin periodo - Sin matricula?
			$bTienePeriodo = false;
		}
	}
}
//25 de Septiembre de 2024 - Se perdió el objeto DB, ?????
$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
if ($APP->dbpuerto != '') {
	$objDB->dbPuerto = $APP->dbpuerto;
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
if (isset($_REQUEST['paginaf2301']) == 0) {
	$_REQUEST['paginaf2301'] = 1;
}
if (isset($_REQUEST['lppf2301']) == 0) {
	$_REQUEST['lppf2301'] = 20;
}
if (isset($_REQUEST['boculta2301']) == 0) {
	if ($bEstudiante) {
		$_REQUEST['boculta2301'] = 0;
	} else {
		$_REQUEST['boculta2301'] = 1;
	}
}
if (isset($_REQUEST['paginaf2310']) == 0) {
	$_REQUEST['paginaf2310'] = 1;
}
if (isset($_REQUEST['lppf2310']) == 0) {
	$_REQUEST['lppf2310'] = 20;
}
if (isset($_REQUEST['boculta101']) == 0) {
	$_REQUEST['boculta101'] = 0;
}
if (isset($_REQUEST['boculta102']) == 0) {
	$_REQUEST['boculta102'] = 0;
}
if (isset($_REQUEST['boculta103']) == 0) {
	$_REQUEST['boculta103'] = 0;
}
if (isset($_REQUEST['boculta104']) == 0) {
	$_REQUEST['boculta104'] = 0;
}
if (isset($_REQUEST['boculta105']) == 0) {
	$_REQUEST['boculta105'] = 0;
}
if (isset($_REQUEST['boculta106']) == 0) {
	$_REQUEST['boculta106'] = 0;
}
if (isset($_REQUEST['boculta107']) == 0) {
	$_REQUEST['boculta107'] = 0;
}
if (isset($_REQUEST['boculta108']) == 0) {
	$_REQUEST['boculta108'] = 0;
}
if (isset($_REQUEST['boculta109']) == 0) {
	$_REQUEST['boculta109'] = 0;
}
if (isset($_REQUEST['boculta110']) == 0) {
	$_REQUEST['boculta110'] = 0;
}
if (isset($_REQUEST['boculta111']) == 0) {
	$_REQUEST['boculta111'] = 0;
}
if (isset($_REQUEST['boculta112']) == 0) {
	$_REQUEST['boculta112'] = 0;
}
if (isset($_REQUEST['boculta113']) == 0) {
	$_REQUEST['boculta113'] = 0;
}
if (isset($_REQUEST['boculta114']) == 0) {
	$_REQUEST['boculta114'] = 0;
}
if (isset($_REQUEST['bocultaResultados']) == 0) {
	$_REQUEST['bocultaResultados'] = 0;
}
// -- Inicializar variables de datos.
if (isset($_REQUEST['cara01idperaca']) == 0) {
	$_REQUEST['cara01idperaca'] = '';
}
if ($bEstudiante) {
	$_REQUEST['cara01idtercero'] = $idTercero;
} else {
	if (isset($_REQUEST['cara01idtercero']) == 0) {
		$_REQUEST['cara01idtercero'] = 0;
	}
}
if (isset($_REQUEST['cara01idtercero_td']) == 0) {
	$_REQUEST['cara01idtercero_td'] = $APP->tipo_doc;
}
if (isset($_REQUEST['cara01idtercero_doc']) == 0) {
	$_REQUEST['cara01idtercero_doc'] = '';
}
if (isset($_REQUEST['cara01id']) == 0) {
	$_REQUEST['cara01id'] = '';
}
if (isset($_REQUEST['cara01completa']) == 0) {
	$_REQUEST['cara01completa'] = 'N';
}
if (isset($_REQUEST['cara01fichaper']) == 0) {
	$_REQUEST['cara01fichaper'] = 0;
}
if (isset($_REQUEST['cara01fichafam']) == 0) {
	$_REQUEST['cara01fichafam'] = 0;
}
if (isset($_REQUEST['cara01fichaaca']) == 0) {
	$_REQUEST['cara01fichaaca'] = 0;
}
if (isset($_REQUEST['cara01fichalab']) == 0) {
	$_REQUEST['cara01fichalab'] = 0;
}
if (isset($_REQUEST['cara01fichabien']) == 0) {
	$_REQUEST['cara01fichabien'] = 0;
}
if (isset($_REQUEST['cara01fichapsico']) == 0) {
	$_REQUEST['cara01fichapsico'] = 0;
}
if (isset($_REQUEST['cara01fichadigital']) == 0) {
	$_REQUEST['cara01fichadigital'] = 0;
}
if (isset($_REQUEST['cara01fichalectura']) == 0) {
	$_REQUEST['cara01fichalectura'] = 0;
}
if (isset($_REQUEST['cara01ficharazona']) == 0) {
	$_REQUEST['cara01ficharazona'] = '';
}
if (isset($_REQUEST['cara01fichaingles']) == 0) {
	$_REQUEST['cara01fichaingles'] = '';
}
if (isset($_REQUEST['cara01fechaencuesta']) == 0) {
	$_REQUEST['cara01fechaencuesta'] = '';
} //{fecha_hoy();}
if (isset($_REQUEST['cara01agnos']) == 0) {
	$_REQUEST['cara01agnos'] = 0;
}
if (isset($_REQUEST['cara01sexo']) == 0) {
	$_REQUEST['cara01sexo'] = '';
}
if (isset($_REQUEST['cara01pais']) == 0) {
	$_REQUEST['cara01pais'] = $_SESSION['unad_pais'];
}
if (isset($_REQUEST['cara01depto']) == 0) {
	$_REQUEST['cara01depto'] = '';
}
if (isset($_REQUEST['cara01ciudad']) == 0) {
	$_REQUEST['cara01ciudad'] = '';
}
if (isset($_REQUEST['cara01nomciudad']) == 0) {
	$_REQUEST['cara01nomciudad'] = '';
}
if (isset($_REQUEST['cara01direccion']) == 0) {
	$_REQUEST['cara01direccion'] = '';
}
if (isset($_REQUEST['cara01estrato']) == 0) {
	$_REQUEST['cara01estrato'] = '';
}
if (isset($_REQUEST['cara01zonares']) == 0) {
	$_REQUEST['cara01zonares'] = '';
}
if (isset($_REQUEST['cara01estcivil']) == 0) {
	$_REQUEST['cara01estcivil'] = '';
}
if (isset($_REQUEST['cara01nomcontacto']) == 0) {
	$_REQUEST['cara01nomcontacto'] = '';
}
if (isset($_REQUEST['cara01parentezcocontacto']) == 0) {
	$_REQUEST['cara01parentezcocontacto'] = '';
}
if (isset($_REQUEST['cara01celcontacto']) == 0) {
	$_REQUEST['cara01celcontacto'] = '';
}
if (isset($_REQUEST['cara01correocontacto']) == 0) {
	$_REQUEST['cara01correocontacto'] = '';
}
if (isset($_REQUEST['cara01idzona']) == 0) {
	$_REQUEST['cara01idzona'] = '';
}
if (isset($_REQUEST['cara01idcead']) == 0) {
	$_REQUEST['cara01idcead'] = '';
}
if (isset($_REQUEST['cara01matconvenio']) == 0) {
	$_REQUEST['cara01matconvenio'] = 'N';
}
if (isset($_REQUEST['cara01raizal']) == 0) {
	$_REQUEST['cara01raizal'] = '';
}
if (isset($_REQUEST['cara01palenquero']) == 0) {
	$_REQUEST['cara01palenquero'] = '';
}
if (isset($_REQUEST['cara01afrocolombiano']) == 0) {
	$_REQUEST['cara01afrocolombiano'] = '';
}
if (isset($_REQUEST['cara01otracomunnegras']) == 0) {
	$_REQUEST['cara01otracomunnegras'] = '';
}
if (isset($_REQUEST['cara01rom']) == 0) {
	$_REQUEST['cara01rom'] = '';
}
if (isset($_REQUEST['cara01indigenas']) == 0) {
	$_REQUEST['cara01indigenas'] = '';
}
if (isset($_REQUEST['cara01victimadesplazado']) == 0) {
	$_REQUEST['cara01victimadesplazado'] = '';
}
if (isset($_REQUEST['cara01idconfirmadesp']) == 0) {
	$_REQUEST['cara01idconfirmadesp'] = $idTercero;
}
if (isset($_REQUEST['cara01idconfirmadesp_td']) == 0) {
	$_REQUEST['cara01idconfirmadesp_td'] = $APP->tipo_doc;
}
if (isset($_REQUEST['cara01idconfirmadesp_doc']) == 0) {
	$_REQUEST['cara01idconfirmadesp_doc'] = '';
}
if (isset($_REQUEST['cara01fechaconfirmadesp']) == 0) {
	$_REQUEST['cara01fechaconfirmadesp'] = '';
} //{fecha_hoy();}
if (isset($_REQUEST['cara01victimaacr']) == 0) {
	$_REQUEST['cara01victimaacr'] = '';
}
if (isset($_REQUEST['cara01idconfirmacr']) == 0) {
	$_REQUEST['cara01idconfirmacr'] = $idTercero;
}
if (isset($_REQUEST['cara01idconfirmacr_td']) == 0) {
	$_REQUEST['cara01idconfirmacr_td'] = $APP->tipo_doc;
}
if (isset($_REQUEST['cara01idconfirmacr_doc']) == 0) {
	$_REQUEST['cara01idconfirmacr_doc'] = '';
}
if (isset($_REQUEST['cara01fechaconfirmacr']) == 0) {
	$_REQUEST['cara01fechaconfirmacr'] = '';
} //{fecha_hoy();}
if (isset($_REQUEST['cara01inpecfuncionario']) == 0) {
	$_REQUEST['cara01inpecfuncionario'] = 'N';
}
if (isset($_REQUEST['cara01inpecrecluso']) == 0) {
	$_REQUEST['cara01inpecrecluso'] = 'N';
}
if (isset($_REQUEST['cara01inpectiempocondena']) == 0) {
	$_REQUEST['cara01inpectiempocondena'] = '';
}
if (isset($_REQUEST['cara01centroreclusion']) == 0) {
	$_REQUEST['cara01centroreclusion'] = '';
}
if (isset($_REQUEST['cara01discsensorial']) == 0) {
	$_REQUEST['cara01discsensorial'] = 'N';
}
if (isset($_REQUEST['cara01discfisica']) == 0) {
	$_REQUEST['cara01discfisica'] = 'N';
}
if (isset($_REQUEST['cara01disccognitiva']) == 0) {
	$_REQUEST['cara01disccognitiva'] = 'N';
}
if (isset($_REQUEST['cara01idconfirmadisc']) == 0) {
	$_REQUEST['cara01idconfirmadisc'] = $idTercero;
}
if (isset($_REQUEST['cara01idconfirmadisc_td']) == 0) {
	$_REQUEST['cara01idconfirmadisc_td'] = $APP->tipo_doc;
}
if (isset($_REQUEST['cara01idconfirmadisc_doc']) == 0) {
	$_REQUEST['cara01idconfirmadisc_doc'] = '';
}
if (isset($_REQUEST['cara01fechaconfirmadisc']) == 0) {
	$_REQUEST['cara01fechaconfirmadisc'] = '';
} //{fecha_hoy();}
if (isset($_REQUEST['cara01fam_tipovivienda']) == 0) {
	$_REQUEST['cara01fam_tipovivienda'] = '';
}
if (isset($_REQUEST['cara01fam_vivecon']) == 0) {
	$_REQUEST['cara01fam_vivecon'] = '';
}
if (isset($_REQUEST['cara01fam_numpersgrupofam']) == 0) {
	$_REQUEST['cara01fam_numpersgrupofam'] = '';
}
if (isset($_REQUEST['cara01fam_hijos']) == 0) {
	$_REQUEST['cara01fam_hijos'] = '';
}
if (isset($_REQUEST['cara01fam_personasacargo']) == 0) {
	$_REQUEST['cara01fam_personasacargo'] = '';
}
if (isset($_REQUEST['cara01fam_dependeecon']) == 0) {
	$_REQUEST['cara01fam_dependeecon'] = '';
}
if (isset($_REQUEST['cara01fam_escolaridadpadre']) == 0) {
	$_REQUEST['cara01fam_escolaridadpadre'] = '';
}
if (isset($_REQUEST['cara01fam_escolaridadmadre']) == 0) {
	$_REQUEST['cara01fam_escolaridadmadre'] = '';
}
if (isset($_REQUEST['cara01fam_numhermanos']) == 0) {
	$_REQUEST['cara01fam_numhermanos'] = '';
}
if (isset($_REQUEST['cara01fam_posicionherm']) == 0) {
	$_REQUEST['cara01fam_posicionherm'] = '';
}
if (isset($_REQUEST['cara01fam_familiaunad']) == 0) {
	$_REQUEST['cara01fam_familiaunad'] = '';
}
if (isset($_REQUEST['cara01acad_tipocolegio']) == 0) {
	$_REQUEST['cara01acad_tipocolegio'] = '';
}
if (isset($_REQUEST['cara01acad_modalidadbach']) == 0) {
	$_REQUEST['cara01acad_modalidadbach'] = '';
}
if (isset($_REQUEST['cara01acad_estudioprev']) == 0) {
	$_REQUEST['cara01acad_estudioprev'] = '';
}
if (isset($_REQUEST['cara01acad_ultnivelest']) == 0) {
	$_REQUEST['cara01acad_ultnivelest'] = '';
}
if (isset($_REQUEST['cara01acad_obtubodiploma']) == 0) {
	$_REQUEST['cara01acad_obtubodiploma'] = '';
}
if (isset($_REQUEST['cara01acad_hatomadovirtual']) == 0) {
	$_REQUEST['cara01acad_hatomadovirtual'] = '';
}
if (isset($_REQUEST['cara01acad_tiemposinest']) == 0) {
	$_REQUEST['cara01acad_tiemposinest'] = '';
}
if (isset($_REQUEST['cara01acad_razonestudio']) == 0) {
	$_REQUEST['cara01acad_razonestudio'] = '';
}
if (isset($_REQUEST['cara01acad_primeraopc']) == 0) {
	$_REQUEST['cara01acad_primeraopc'] = '';
}
if (isset($_REQUEST['cara01acad_programagusto']) == 0) {
	$_REQUEST['cara01acad_programagusto'] = '';
}
if (isset($_REQUEST['cara01acad_razonunad']) == 0) {
	$_REQUEST['cara01acad_razonunad'] = '';
}
if (isset($_REQUEST['cara01campus_compescrito']) == 0) {
	$_REQUEST['cara01campus_compescrito'] = '';
}
if (isset($_REQUEST['cara01campus_portatil']) == 0) {
	$_REQUEST['cara01campus_portatil'] = '';
}
if (isset($_REQUEST['cara01campus_tableta']) == 0) {
	$_REQUEST['cara01campus_tableta'] = '';
}
if (isset($_REQUEST['cara01campus_telefono']) == 0) {
	$_REQUEST['cara01campus_telefono'] = '';
}
if (isset($_REQUEST['cara01campus_energia']) == 0) {
	$_REQUEST['cara01campus_energia'] = '';
}
if (isset($_REQUEST['cara01campus_internetreside']) == 0) {
	$_REQUEST['cara01campus_internetreside'] = '';
}
if (isset($_REQUEST['cara01campus_expvirtual']) == 0) {
	$_REQUEST['cara01campus_expvirtual'] = '';
}
if (isset($_REQUEST['cara01campus_ofimatica']) == 0) {
	$_REQUEST['cara01campus_ofimatica'] = '';
}
if (isset($_REQUEST['cara01campus_foros']) == 0) {
	$_REQUEST['cara01campus_foros'] = '';
}
if (isset($_REQUEST['cara01campus_conversiones']) == 0) {
	$_REQUEST['cara01campus_conversiones'] = '';
}
if (isset($_REQUEST['cara01campus_usocorreo']) == 0) {
	$_REQUEST['cara01campus_usocorreo'] = '';
}
if (isset($_REQUEST['cara01campus_aprendtexto']) == 0) {
	$_REQUEST['cara01campus_aprendtexto'] = '';
}
if (isset($_REQUEST['cara01campus_aprendvideo']) == 0) {
	$_REQUEST['cara01campus_aprendvideo'] = '';
}
if (isset($_REQUEST['cara01campus_aprendmapas']) == 0) {
	$_REQUEST['cara01campus_aprendmapas'] = '';
}
if (isset($_REQUEST['cara01campus_aprendeanima']) == 0) {
	$_REQUEST['cara01campus_aprendeanima'] = '';
}
if (isset($_REQUEST['cara01campus_mediocomunica']) == 0) {
	$_REQUEST['cara01campus_mediocomunica'] = '';
}
if (isset($_REQUEST['cara01lab_situacion']) == 0) {
	$_REQUEST['cara01lab_situacion'] = '';
}
if (isset($_REQUEST['cara01lab_sector']) == 0) {
	$_REQUEST['cara01lab_sector'] = '';
}
if (isset($_REQUEST['cara01lab_caracterjuri']) == 0) {
	$_REQUEST['cara01lab_caracterjuri'] = '';
}
if (isset($_REQUEST['cara01lab_cargo']) == 0) {
	$_REQUEST['cara01lab_cargo'] = '';
}
if (isset($_REQUEST['cara01lab_antiguedad']) == 0) {
	$_REQUEST['cara01lab_antiguedad'] = '';
}
if (isset($_REQUEST['cara01lab_tipocontrato']) == 0) {
	$_REQUEST['cara01lab_tipocontrato'] = '';
}
if (isset($_REQUEST['cara01lab_rangoingreso']) == 0) {
	$_REQUEST['cara01lab_rangoingreso'] = '';
}
if (isset($_REQUEST['cara01lab_tiempoacadem']) == 0) {
	$_REQUEST['cara01lab_tiempoacadem'] = '';
}
if (isset($_REQUEST['cara01lab_tipoempresa']) == 0) {
	$_REQUEST['cara01lab_tipoempresa'] = '';
}
if (isset($_REQUEST['cara01lab_tiempoindepen']) == 0) {
	$_REQUEST['cara01lab_tiempoindepen'] = '';
}
if (isset($_REQUEST['cara01lab_debebusctrab']) == 0) {
	$_REQUEST['cara01lab_debebusctrab'] = '';
}
if (isset($_REQUEST['cara01lab_origendinero']) == 0) {
	$_REQUEST['cara01lab_origendinero'] = '';
}
if (isset($_REQUEST['cara01bien_baloncesto']) == 0) {
	$_REQUEST['cara01bien_baloncesto'] = '';
}
if (isset($_REQUEST['cara01bien_voleibol']) == 0) {
	$_REQUEST['cara01bien_voleibol'] = '';
}
if (isset($_REQUEST['cara01bien_futbolsala']) == 0) {
	$_REQUEST['cara01bien_futbolsala'] = '';
}
if (isset($_REQUEST['cara01bien_artesmarc']) == 0) {
	$_REQUEST['cara01bien_artesmarc'] = '';
}
if (isset($_REQUEST['cara01bien_tenisdemesa']) == 0) {
	$_REQUEST['cara01bien_tenisdemesa'] = '';
}
if (isset($_REQUEST['cara01bien_ajedrez']) == 0) {
	$_REQUEST['cara01bien_ajedrez'] = '';
}
if (isset($_REQUEST['cara01bien_juegosautoc']) == 0) {
	$_REQUEST['cara01bien_juegosautoc'] = '';
}
if (isset($_REQUEST['cara01bien_interesrepdeporte']) == 0) {
	$_REQUEST['cara01bien_interesrepdeporte'] = '';
}
if (isset($_REQUEST['cara01bien_deporteint']) == 0) {
	$_REQUEST['cara01bien_deporteint'] = '';
}
if (isset($_REQUEST['cara01bien_teatro']) == 0) {
	$_REQUEST['cara01bien_teatro'] = '';
}
if (isset($_REQUEST['cara01bien_danza']) == 0) {
	$_REQUEST['cara01bien_danza'] = '';
}
if (isset($_REQUEST['cara01bien_musica']) == 0) {
	$_REQUEST['cara01bien_musica'] = '';
}
if (isset($_REQUEST['cara01bien_circo']) == 0) {
	$_REQUEST['cara01bien_circo'] = '';
}
if (isset($_REQUEST['cara01bien_artplast']) == 0) {
	$_REQUEST['cara01bien_artplast'] = '';
}
if (isset($_REQUEST['cara01bien_cuenteria']) == 0) {
	$_REQUEST['cara01bien_cuenteria'] = '';
}
if (isset($_REQUEST['cara01bien_interesreparte']) == 0) {
	$_REQUEST['cara01bien_interesreparte'] = '';
}
if (isset($_REQUEST['cara01bien_arteint']) == 0) {
	$_REQUEST['cara01bien_arteint'] = '';
}
if (isset($_REQUEST['cara01bien_interpreta']) == 0) {
	$_REQUEST['cara01bien_interpreta'] = '';
}
if (isset($_REQUEST['cara01bien_nivelinter']) == 0) {
	$_REQUEST['cara01bien_nivelinter'] = '';
}
if (isset($_REQUEST['cara01bien_danza_mod']) == 0) {
	$_REQUEST['cara01bien_danza_mod'] = '';
}
if (isset($_REQUEST['cara01bien_danza_clas']) == 0) {
	$_REQUEST['cara01bien_danza_clas'] = '';
}
if (isset($_REQUEST['cara01bien_danza_cont']) == 0) {
	$_REQUEST['cara01bien_danza_cont'] = '';
}
if (isset($_REQUEST['cara01bien_danza_folk']) == 0) {
	$_REQUEST['cara01bien_danza_folk'] = '';
}
if (isset($_REQUEST['cara01bien_niveldanza']) == 0) {
	$_REQUEST['cara01bien_niveldanza'] = '';
}
if (isset($_REQUEST['cara01bien_emprendedor']) == 0) {
	$_REQUEST['cara01bien_emprendedor'] = '';
}
if (isset($_REQUEST['cara01bien_nombreemp']) == 0) {
	$_REQUEST['cara01bien_nombreemp'] = '';
}
if (isset($_REQUEST['cara01bien_capacempren']) == 0) {
	$_REQUEST['cara01bien_capacempren'] = '';
}
if (isset($_REQUEST['cara01bien_tipocapacita']) == 0) {
	$_REQUEST['cara01bien_tipocapacita'] = '';
}
if (isset($_REQUEST['cara01bien_impvidasalud']) == 0) {
	$_REQUEST['cara01bien_impvidasalud'] = '';
}
if (isset($_REQUEST['cara01bien_estraautocuid']) == 0) {
	$_REQUEST['cara01bien_estraautocuid'] = '';
}
if (isset($_REQUEST['cara01bien_pv_personal']) == 0) {
	$_REQUEST['cara01bien_pv_personal'] = '';
}
if (isset($_REQUEST['cara01bien_pv_familiar']) == 0) {
	$_REQUEST['cara01bien_pv_familiar'] = '';
}
if (isset($_REQUEST['cara01bien_pv_academ']) == 0) {
	$_REQUEST['cara01bien_pv_academ'] = '';
}
if (isset($_REQUEST['cara01bien_pv_labora']) == 0) {
	$_REQUEST['cara01bien_pv_labora'] = '';
}
if (isset($_REQUEST['cara01bien_pv_pareja']) == 0) {
	$_REQUEST['cara01bien_pv_pareja'] = '';
}
if (isset($_REQUEST['cara01bien_amb']) == 0) {
	$_REQUEST['cara01bien_amb'] = '';
}
if (isset($_REQUEST['cara01bien_amb_agu']) == 0) {
	$_REQUEST['cara01bien_amb_agu'] = '';
}
if (isset($_REQUEST['cara01bien_amb_bom']) == 0) {
	$_REQUEST['cara01bien_amb_bom'] = '';
}
if (isset($_REQUEST['cara01bien_amb_car']) == 0) {
	$_REQUEST['cara01bien_amb_car'] = '';
}
if (isset($_REQUEST['cara01bien_amb_info']) == 0) {
	$_REQUEST['cara01bien_amb_info'] = '';
}
if (isset($_REQUEST['cara01bien_amb_temas']) == 0) {
	$_REQUEST['cara01bien_amb_temas'] = '';
}
if (isset($_REQUEST['cara01psico_costoemocion']) == 0) {
	$_REQUEST['cara01psico_costoemocion'] = '';
}
if (isset($_REQUEST['cara01psico_reaccionimpre']) == 0) {
	$_REQUEST['cara01psico_reaccionimpre'] = '';
}
if (isset($_REQUEST['cara01psico_estres']) == 0) {
	$_REQUEST['cara01psico_estres'] = '';
}
if (isset($_REQUEST['cara01psico_pocotiempo']) == 0) {
	$_REQUEST['cara01psico_pocotiempo'] = '';
}
if (isset($_REQUEST['cara01psico_actitudvida']) == 0) {
	$_REQUEST['cara01psico_actitudvida'] = '';
}
if (isset($_REQUEST['cara01psico_duda']) == 0) {
	$_REQUEST['cara01psico_duda'] = '';
}
if (isset($_REQUEST['cara01psico_problemapers']) == 0) {
	$_REQUEST['cara01psico_problemapers'] = '';
}
if (isset($_REQUEST['cara01psico_satisfaccion']) == 0) {
	$_REQUEST['cara01psico_satisfaccion'] = '';
}
if (isset($_REQUEST['cara01psico_discusiones']) == 0) {
	$_REQUEST['cara01psico_discusiones'] = '';
}
if (isset($_REQUEST['cara01psico_atencion']) == 0) {
	$_REQUEST['cara01psico_atencion'] = '';
}
if (isset($_REQUEST['cara01niveldigital']) == 0) {
	$_REQUEST['cara01niveldigital'] = 0;
}
if (isset($_REQUEST['cara01nivellectura']) == 0) {
	$_REQUEST['cara01nivellectura'] = 0;
}
if (isset($_REQUEST['cara01nivelrazona']) == 0) {
	$_REQUEST['cara01nivelrazona'] = 0;
}
if (isset($_REQUEST['cara01nivelingles']) == 0) {
	$_REQUEST['cara01nivelingles'] = 0;
}
if (isset($_REQUEST['cara01idconsejero']) == 0) {
	$_REQUEST['cara01idconsejero'] = $idTercero;
}
if (isset($_REQUEST['cara01idconsejero_td']) == 0) {
	$_REQUEST['cara01idconsejero_td'] = $APP->tipo_doc;
}
if (isset($_REQUEST['cara01idconsejero_doc']) == 0) {
	$_REQUEST['cara01idconsejero_doc'] = '';
}
if (isset($_REQUEST['cara01fechainicio']) == 0) {
	$_REQUEST['cara01fechainicio'] = '';
} //{fecha_hoy();}
if (isset($_REQUEST['cara01telefono1']) == 0) {
	$_REQUEST['cara01telefono1'] = '';
}
if (isset($_REQUEST['cara01telefono2']) == 0) {
	$_REQUEST['cara01telefono2'] = '';
}
if (isset($_REQUEST['cara01correopersonal']) == 0) {
	$_REQUEST['cara01correopersonal'] = '';
}
if (isset($_REQUEST['cara01idprograma']) == 0) {
	$_REQUEST['cara01idprograma'] = 0;
}
if (isset($_REQUEST['cara01idescuela']) == 0) {
	$_REQUEST['cara01idescuela'] = 0;
}
if (isset($_REQUEST['cara01fichabiolog']) == 0) {
	$_REQUEST['cara01fichabiolog'] = '';
}
if (isset($_REQUEST['cara01nivelbiolog']) == 0) {
	$_REQUEST['cara01nivelbiolog'] = 0;
}
if (isset($_REQUEST['cara01fichafisica']) == 0) {
	$_REQUEST['cara01fichafisica'] = '';
}
if (isset($_REQUEST['cara01nivelfisica']) == 0) {
	$_REQUEST['cara01nivelfisica'] = 0;
}
if (isset($_REQUEST['cara01fichaquimica']) == 0) {
	$_REQUEST['cara01fichaquimica'] = 0;
}
if (isset($_REQUEST['cara01nivelquimica']) == 0) {
	$_REQUEST['cara01nivelquimica'] = 0;
}
if (isset($_REQUEST['cara01fichaciudad']) == 0) {
	$_REQUEST['cara01fichaciudad'] = 0;
}
if (isset($_REQUEST['cara01nivelciudad']) == 0) {
	$_REQUEST['cara01nivelciudad'] = 0;
}
if (isset($_REQUEST['cara01tipocaracterizacion']) == 0) {
	$_REQUEST['cara01tipocaracterizacion'] = 0;
}
if (isset($_REQUEST['cara01perayuda']) == 0) {
	$_REQUEST['cara01perayuda'] = '';
}
if (isset($_REQUEST['cara01perotraayuda']) == 0) {
	$_REQUEST['cara01perotraayuda'] = '';
}
if (isset($_REQUEST['cara01discsensorialotra']) == 0) {
	$_REQUEST['cara01discsensorialotra'] = '';
}
if (isset($_REQUEST['cara01discfisicaotra']) == 0) {
	$_REQUEST['cara01discfisicaotra'] = '';
}
if (isset($_REQUEST['cara01disccognitivaotra']) == 0) {
	$_REQUEST['cara01disccognitivaotra'] = '';
}
if (isset($_REQUEST['cara01idcursocatedra']) == 0) {
	$_REQUEST['cara01idcursocatedra'] = 0;
}
if (isset($_REQUEST['cara01idgrupocatedra']) == 0) {
	$_REQUEST['cara01idgrupocatedra'] = 0;
}
if (isset($_REQUEST['cara01factordescper']) == 0) {
	$_REQUEST['cara01factordescper'] = 0;
}
if (isset($_REQUEST['cara01factordescpsico']) == 0) {
	$_REQUEST['cara01factordescpsico'] = 0;
}
if (isset($_REQUEST['cara01factordescinsti']) == 0) {
	$_REQUEST['cara01factordescinsti'] = 0;
}
if (isset($_REQUEST['cara01factordescacad']) == 0) {
	$_REQUEST['cara01factordescacad'] = 0;
}
if (isset($_REQUEST['cara01factordesc']) == 0) {
	$_REQUEST['cara01factordesc'] = 0;
}
if (isset($_REQUEST['cara01criteriodesc']) == 0) {
	$_REQUEST['cara01criteriodesc'] = '';
}
if (isset($_REQUEST['cara01desertor']) == 0) {
	$_REQUEST['cara01desertor'] = 'N';
}
if (isset($_REQUEST['cara01factorprincipaldesc']) == 0) {
	$_REQUEST['cara01factorprincipaldesc'] = '';
}
if (isset($_REQUEST['cara01psico_puntaje']) == 0) {
	$_REQUEST['cara01psico_puntaje'] = 0;
}
if (isset($_REQUEST['cara01discversion']) == 0) {
	$_REQUEST['cara01discversion'] = 2;
}
if (isset($_REQUEST['cara01discv2sensorial']) == 0) {
	$_REQUEST['cara01discv2sensorial'] = '';
}
if (isset($_REQUEST['cara02discv2intelectura']) == 0) {
	$_REQUEST['cara02discv2intelectura'] = '';
}
if (isset($_REQUEST['cara02discv2fisica']) == 0) {
	$_REQUEST['cara02discv2fisica'] = '';
}
if (isset($_REQUEST['cara02discv2psico']) == 0) {
	$_REQUEST['cara02discv2psico'] = '';
}
if (isset($_REQUEST['cara02discv2sistemica']) == 0) {
	$_REQUEST['cara02discv2sistemica'] = '';
}
if (isset($_REQUEST['cara02discv2sistemicaotro']) == 0) {
	$_REQUEST['cara02discv2sistemicaotro'] = '';
}
if (isset($_REQUEST['cara02discv2multiple']) == 0) {
	$_REQUEST['cara02discv2multiple'] = '';
}
if (isset($_REQUEST['cara02discv2multipleotro']) == 0) {
	$_REQUEST['cara02discv2multipleotro'] = '';
}
if (isset($_REQUEST['cara02talentoexcepcional']) == 0) {
	$_REQUEST['cara02talentoexcepcional'] = '';
}
if (isset($_REQUEST['cara01discv2tiene']) == 0) {
	$_REQUEST['cara01discv2tiene'] = '';
}
if (isset($_REQUEST['cara01discv2trastaprende']) == 0) {
	$_REQUEST['cara01discv2trastaprende'] = '';
}
if (isset($_REQUEST['cara01discv2soporteorigen']) == 0) {
	$_REQUEST['cara01discv2soporteorigen'] = 0;
}
if (isset($_REQUEST['cara01discv2archivoorigen']) == 0) {
	$_REQUEST['cara01discv2archivoorigen'] = 0;
}
if (isset($_REQUEST['cara01discv2trastornos']) == 0) {
	$_REQUEST['cara01discv2trastornos'] = '';
}
if (isset($_REQUEST['cara01discv2contalento']) == 0) {
	$_REQUEST['cara01discv2contalento'] = '';
}
if (isset($_REQUEST['cara01discv2condicionmedica']) == 0) {
	$_REQUEST['cara01discv2condicionmedica'] = '';
}
if (isset($_REQUEST['cara01discv2condmeddet']) == 0) {
	$_REQUEST['cara01discv2condmeddet'] = '';
}
if (isset($_REQUEST['cara01discv2pruebacoeficiente']) == 0) {
	$_REQUEST['cara01discv2pruebacoeficiente'] = '';
}
if (isset($_REQUEST['cara44campesinado']) == 0) {
	$_REQUEST['cara44campesinado'] = '';
}
if (isset($_REQUEST['cara44sexoversion']) == 0) {
	$_REQUEST['cara44sexoversion'] = 1;
}
if (isset($_REQUEST['cara44sexov1identidadgen']) == 0) {
	$_REQUEST['cara44sexov1identidadgen'] = '';
}
if (isset($_REQUEST['cara44sexov1orientasexo']) == 0) {
	$_REQUEST['cara44sexov1orientasexo'] = '';
}
if (isset($_REQUEST['cara44bienversion']) == 0) {
	$_REQUEST['cara44bienversion'] = 3;
}
if (isset($_REQUEST['cara44bienv2altoren']) == 0) {
	$_REQUEST['cara44bienv2altoren'] = '';
}
if (isset($_REQUEST['cara44bienv2atletismo']) == 0) {
	$_REQUEST['cara44bienv2atletismo'] = '';
}
if (isset($_REQUEST['cara44bienv2baloncesto']) == 0) {
	$_REQUEST['cara44bienv2baloncesto'] = '';
}
if (isset($_REQUEST['cara44bienv2futbol']) == 0) {
	$_REQUEST['cara44bienv2futbol'] = '';
}
if (isset($_REQUEST['cara44bienv2gimnasia']) == 0) {
	$_REQUEST['cara44bienv2gimnasia'] = '';
}
if (isset($_REQUEST['cara44bienv2natacion']) == 0) {
	$_REQUEST['cara44bienv2natacion'] = '';
}
if (isset($_REQUEST['cara44bienv2voleibol']) == 0) {
	$_REQUEST['cara44bienv2voleibol'] = '';
}
if (isset($_REQUEST['cara44bienv2tenis']) == 0) {
	$_REQUEST['cara44bienv2tenis'] = '';
}
if (isset($_REQUEST['cara44bienv2paralimpico']) == 0) {
	$_REQUEST['cara44bienv2paralimpico'] = '';
}
if (isset($_REQUEST['cara44bienv2otrodeporte']) == 0) {
	$_REQUEST['cara44bienv2otrodeporte'] = '';
}
if (isset($_REQUEST['cara44bienv2otrodeportedetalle']) == 0) {
	$_REQUEST['cara44bienv2otrodeportedetalle'] = '';
}
if (isset($_REQUEST['cara44bienv2activdanza']) == 0) {
	$_REQUEST['cara44bienv2activdanza'] = '';
}
if (isset($_REQUEST['cara44bienv2activmusica']) == 0) {
	$_REQUEST['cara44bienv2activmusica'] = '';
}
if (isset($_REQUEST['cara44bienv2activteatro']) == 0) {
	$_REQUEST['cara44bienv2activteatro'] = '';
}
if (isset($_REQUEST['cara44bienv2activartes']) == 0) {
	$_REQUEST['cara44bienv2activartes'] = '';
}
if (isset($_REQUEST['cara44bienv2activliteratura']) == 0) {
	$_REQUEST['cara44bienv2activliteratura'] = '';
}
if (isset($_REQUEST['cara44bienv2activculturalotra']) == 0) {
	$_REQUEST['cara44bienv2activculturalotra'] = '';
}
if (isset($_REQUEST['cara44bienv2activculturalotradetalle']) == 0) {
	$_REQUEST['cara44bienv2activculturalotradetalle'] = '';
}
if (isset($_REQUEST['cara44bienv2evenfestfolc']) == 0) {
	$_REQUEST['cara44bienv2evenfestfolc'] = '';
}
if (isset($_REQUEST['cara44bienv2evenexpoarte']) == 0) {
	$_REQUEST['cara44bienv2evenexpoarte'] = '';
}
if (isset($_REQUEST['cara44bienv2evenhistarte']) == 0) {
	$_REQUEST['cara44bienv2evenhistarte'] = '';
}
if (isset($_REQUEST['cara44bienv2evengalfoto']) == 0) {
	$_REQUEST['cara44bienv2evengalfoto'] = '';
}
if (isset($_REQUEST['cara44bienv2evenliteratura']) == 0) {
	$_REQUEST['cara44bienv2evenliteratura'] = '';
}
if (isset($_REQUEST['cara44bienv2eventeatro']) == 0) {
	$_REQUEST['cara44bienv2eventeatro'] = '';
}
if (isset($_REQUEST['cara44bienv2evencine']) == 0) {
	$_REQUEST['cara44bienv2evencine'] = '';
}
if (isset($_REQUEST['cara44bienv2evenculturalotro']) == 0) {
	$_REQUEST['cara44bienv2evenculturalotro'] = '';
}
if (isset($_REQUEST['cara44bienv2evenculturalotrodetalle']) == 0) {
	$_REQUEST['cara44bienv2evenculturalotrodetalle'] = '';
}
if (isset($_REQUEST['cara44bienv2emprendimiento']) == 0) {
	$_REQUEST['cara44bienv2emprendimiento'] = '';
}
if (isset($_REQUEST['cara44bienv2empresa']) == 0) {
	$_REQUEST['cara44bienv2empresa'] = '';
}
if (isset($_REQUEST['cara44bienv2emprenrecursos']) == 0) {
	$_REQUEST['cara44bienv2emprenrecursos'] = '';
}
if (isset($_REQUEST['cara44bienv2emprenconocim']) == 0) {
	$_REQUEST['cara44bienv2emprenconocim'] = '';
}
if (isset($_REQUEST['cara44bienv2emprenplan']) == 0) {
	$_REQUEST['cara44bienv2emprenplan'] = '';
}
if (isset($_REQUEST['cara44bienv2emprenejecutar']) == 0) {
	$_REQUEST['cara44bienv2emprenejecutar'] = '';
}
if (isset($_REQUEST['cara44bienv2emprenfortconocim']) == 0) {
	$_REQUEST['cara44bienv2emprenfortconocim'] = '';
}
if (isset($_REQUEST['cara44bienv2emprenidentproblema']) == 0) {
	$_REQUEST['cara44bienv2emprenidentproblema'] = '';
}
if (isset($_REQUEST['cara44bienv2emprenotro']) == 0) {
	$_REQUEST['cara44bienv2emprenotro'] = '';
}
if (isset($_REQUEST['cara44bienv2emprenotrodetalle']) == 0) {
	$_REQUEST['cara44bienv2emprenotrodetalle'] = '';
}
if (isset($_REQUEST['cara44bienv2emprenmarketing']) == 0) {
	$_REQUEST['cara44bienv2emprenmarketing'] = '';
}
if (isset($_REQUEST['cara44bienv2emprenplannegocios']) == 0) {
	$_REQUEST['cara44bienv2emprenplannegocios'] = '';
}
if (isset($_REQUEST['cara44bienv2emprenideas']) == 0) {
	$_REQUEST['cara44bienv2emprenideas'] = '';
}
if (isset($_REQUEST['cara44bienv2emprencreacion']) == 0) {
	$_REQUEST['cara44bienv2emprencreacion'] = '';
}
if (isset($_REQUEST['cara44bienv2saludfacteconom']) == 0) {
	$_REQUEST['cara44bienv2saludfacteconom'] = '';
}
if (isset($_REQUEST['cara44bienv2saludpreocupacion']) == 0) {
	$_REQUEST['cara44bienv2saludpreocupacion'] = '';
}
if (isset($_REQUEST['cara44bienv2saludconsumosust']) == 0) {
	$_REQUEST['cara44bienv2saludconsumosust'] = '';
}
if (isset($_REQUEST['cara44bienv2saludinsomnio']) == 0) {
	$_REQUEST['cara44bienv2saludinsomnio'] = '';
}
if (isset($_REQUEST['cara44bienv2saludclimalab']) == 0) {
	$_REQUEST['cara44bienv2saludclimalab'] = '';
}
if (isset($_REQUEST['cara44bienv2saludalimenta']) == 0) {
	$_REQUEST['cara44bienv2saludalimenta'] = '';
}
if (isset($_REQUEST['cara44bienv2saludemocion']) == 0) {
	$_REQUEST['cara44bienv2saludemocion'] = '';
}
if (isset($_REQUEST['cara44bienv2saludestado']) == 0) {
	$_REQUEST['cara44bienv2saludestado'] = '';
}
if (isset($_REQUEST['cara44bienv2saludmedita']) == 0) {
	$_REQUEST['cara44bienv2saludmedita'] = '';
}
if (isset($_REQUEST['cara44bienv2crecimedusexual']) == 0) {
	$_REQUEST['cara44bienv2crecimedusexual'] = '';
}
if (isset($_REQUEST['cara44bienv2crecimcultciudad']) == 0) {
	$_REQUEST['cara44bienv2crecimcultciudad'] = '';
}
if (isset($_REQUEST['cara44bienv2crecimrelpareja']) == 0) {
	$_REQUEST['cara44bienv2crecimrelpareja'] = '';
}
if (isset($_REQUEST['cara44bienv2crecimrelinterp']) == 0) {
	$_REQUEST['cara44bienv2crecimrelinterp'] = '';
}
if (isset($_REQUEST['cara44bienv2crecimdinamicafam']) == 0) {
	$_REQUEST['cara44bienv2crecimdinamicafam'] = '';
}
if (isset($_REQUEST['cara44bienv2crecimautoestima']) == 0) {
	$_REQUEST['cara44bienv2crecimautoestima'] = '';
}
if (isset($_REQUEST['cara44bienv2creciminclusion']) == 0) {
	$_REQUEST['cara44bienv2creciminclusion'] = '';
}
if (isset($_REQUEST['cara44bienv2creciminteliemoc']) == 0) {
	$_REQUEST['cara44bienv2creciminteliemoc'] = '';
}
if (isset($_REQUEST['cara44bienv2crecimcultural']) == 0) {
	$_REQUEST['cara44bienv2crecimcultural'] = '';
}
if (isset($_REQUEST['cara44bienv2crecimartistico']) == 0) {
	$_REQUEST['cara44bienv2crecimartistico'] = '';
}
if (isset($_REQUEST['cara44bienv2crecimdeporte']) == 0) {
	$_REQUEST['cara44bienv2crecimdeporte'] = '';
}
if (isset($_REQUEST['cara44bienv2crecimambiente']) == 0) {
	$_REQUEST['cara44bienv2crecimambiente'] = '';
}
if (isset($_REQUEST['cara44bienv2crecimhabsocio']) == 0) {
	$_REQUEST['cara44bienv2crecimhabsocio'] = '';
}
if (isset($_REQUEST['cara44bienv2ambienbasura']) == 0) {
	$_REQUEST['cara44bienv2ambienbasura'] = '';
}
if (isset($_REQUEST['cara44bienv2ambienreutiliza']) == 0) {
	$_REQUEST['cara44bienv2ambienreutiliza'] = '';
}
if (isset($_REQUEST['cara44bienv2ambienluces']) == 0) {
	$_REQUEST['cara44bienv2ambienluces'] = '';
}
if (isset($_REQUEST['cara44bienv2ambienfrutaverd']) == 0) {
	$_REQUEST['cara44bienv2ambienfrutaverd'] = '';
}
if (isset($_REQUEST['cara44bienv2ambienenchufa']) == 0) {
	$_REQUEST['cara44bienv2ambienenchufa'] = '';
}
if (isset($_REQUEST['cara44bienv2ambiengrifo']) == 0) {
	$_REQUEST['cara44bienv2ambiengrifo'] = '';
}
if (isset($_REQUEST['cara44bienv2ambienbicicleta']) == 0) {
	$_REQUEST['cara44bienv2ambienbicicleta'] = '';
}
if (isset($_REQUEST['cara44bienv2ambientranspub']) == 0) {
	$_REQUEST['cara44bienv2ambientranspub'] = '';
}
if (isset($_REQUEST['cara44bienv2ambienducha']) == 0) {
	$_REQUEST['cara44bienv2ambienducha'] = '';
}
if (isset($_REQUEST['cara44bienv2ambiencaminata']) == 0) {
	$_REQUEST['cara44bienv2ambiencaminata'] = '';
}
if (isset($_REQUEST['cara44bienv2ambiensiembra']) == 0) {
	$_REQUEST['cara44bienv2ambiensiembra'] = '';
}
if (isset($_REQUEST['cara44bienv2ambienconferencia']) == 0) {
	$_REQUEST['cara44bienv2ambienconferencia'] = '';
}
if (isset($_REQUEST['cara44bienv2ambienrecicla']) == 0) {
	$_REQUEST['cara44bienv2ambienrecicla'] = '';
}
if (isset($_REQUEST['cara44bienv2ambienotraactiv']) == 0) {
	$_REQUEST['cara44bienv2ambienotraactiv'] = '';
}
if (isset($_REQUEST['cara44bienv2ambienotraactivdetalle']) == 0) {
	$_REQUEST['cara44bienv2ambienotraactivdetalle'] = '';
}
if (isset($_REQUEST['cara44bienv2ambienreforest']) == 0) {
	$_REQUEST['cara44bienv2ambienreforest'] = '';
}
if (isset($_REQUEST['cara44bienv2ambienmovilidad']) == 0) {
	$_REQUEST['cara44bienv2ambienmovilidad'] = '';
}
if (isset($_REQUEST['cara44bienv2ambienclimatico']) == 0) {
	$_REQUEST['cara44bienv2ambienclimatico'] = '';
}
if (isset($_REQUEST['cara44bienv2ambienecofemin']) == 0) {
	$_REQUEST['cara44bienv2ambienecofemin'] = '';
}
if (isset($_REQUEST['cara44bienv2ambienbiodiver']) == 0) {
	$_REQUEST['cara44bienv2ambienbiodiver'] = '';
}
if (isset($_REQUEST['cara44bienv2ambienecologia']) == 0) {
	$_REQUEST['cara44bienv2ambienecologia'] = '';
}
if (isset($_REQUEST['cara44bienv2ambieneconomia']) == 0) {
	$_REQUEST['cara44bienv2ambieneconomia'] = '';
}
if (isset($_REQUEST['cara44bienv2ambienrecnatura']) == 0) {
	$_REQUEST['cara44bienv2ambienrecnatura'] = '';
}
if (isset($_REQUEST['cara44bienv2ambienreciclaje']) == 0) {
	$_REQUEST['cara44bienv2ambienreciclaje'] = '';
}
if (isset($_REQUEST['cara44bienv2ambienmascota']) == 0) {
	$_REQUEST['cara44bienv2ambienmascota'] = '';
}
if (isset($_REQUEST['cara44bienv2ambiencartohum']) == 0) {
	$_REQUEST['cara44bienv2ambiencartohum'] = '';
}
if (isset($_REQUEST['cara44bienv2ambienespiritu']) == 0) {
	$_REQUEST['cara44bienv2ambienespiritu'] = '';
}
if (isset($_REQUEST['cara44bienv2ambiencarga']) == 0) {
	$_REQUEST['cara44bienv2ambiencarga'] = '';
}
if (isset($_REQUEST['cara44bienv2ambienotroenfoq']) == 0) {
	$_REQUEST['cara44bienv2ambienotroenfoq'] = '';
}
if (isset($_REQUEST['cara44bienv2ambienotroenfoqdetalle']) == 0) {
	$_REQUEST['cara44bienv2ambienotroenfoqdetalle'] = '';
}
if (isset($_REQUEST['cara44fam_madrecabeza']) == 0) {
	$_REQUEST['cara44fam_madrecabeza'] = '';
}
if (isset($_REQUEST['cara44acadhatenidorecesos']) == 0) {
	$_REQUEST['cara44acadhatenidorecesos'] = '';
}
if (isset($_REQUEST['cara44acadrazonreceso']) == 0) {
	$_REQUEST['cara44acadrazonreceso'] = 0;
}
if (isset($_REQUEST['cara44acadrazonrecesodetalle']) == 0) {
	$_REQUEST['cara44acadrazonrecesodetalle'] = '';
}
if (isset($_REQUEST['cara44campus_usocorreounad']) == 0) {
	$_REQUEST['cara44campus_usocorreounad'] = 0;
}
if (isset($_REQUEST['cara44campus_usocorreounadno']) == 0) {
	$_REQUEST['cara44campus_usocorreounadno'] = 0;
}
if (isset($_REQUEST['cara44campus_usocorreounadnodetalle']) == 0) {
	$_REQUEST['cara44campus_usocorreounadnodetalle'] = '';
}
if (isset($_REQUEST['cara44campus_medioactivunad']) == 0) {
	$_REQUEST['cara44campus_medioactivunad'] = 0;
}
if (isset($_REQUEST['cara44campus_medioactivunaddetalle']) == 0) {
	$_REQUEST['cara44campus_medioactivunaddetalle'] = '';
}
if (isset($_REQUEST['cara44frontera']) == 0) {
	$_REQUEST['cara44frontera'] = 0;
}
if (isset($_REQUEST['cara44med_tratamiento']) == 0) {
	$_REQUEST['cara44med_tratamiento'] = 0;
}
if (isset($_REQUEST['cara44med_trat_cual']) == 0) {
	$_REQUEST['cara44med_trat_cual'] = '';
}
if (isset($_REQUEST['cara44bienv3emprenetapa']) == 0) {
    $_REQUEST['cara44bienv3emprenetapa'] = 0; 
}
if (isset($_REQUEST['cara44bienv3emprennecesita']) == 0) {
    $_REQUEST['cara44bienv3emprennecesita'] = 0; 
}
if (isset($_REQUEST['cara44bienv3emprenanioini']) == 0) {
    $_REQUEST['cara44bienv3emprenanioini'] = 0; 
}
if (isset($_REQUEST['cara44bienv3emprensector']) == 0) {
    $_REQUEST['cara44bienv3emprensector'] = 0; 
}
if (isset($_REQUEST['cara44bienv3emprensectorotro']) == 0) {
    $_REQUEST['cara44bienv3emprensectorotro'] = ''; 
}
if (isset($_REQUEST['cara44bienv3emprentemas']) == 0) {
    $_REQUEST['cara44bienv3emprentemas'] = 0; 
}
if (isset($_REQUEST['cara44bienv3ambienclima']) == 0) {
    $_REQUEST['cara44bienv3ambienclima'] = 0; 
}
if (isset($_REQUEST['cara44bienv3ambienjusticia']) == 0) {
    $_REQUEST['cara44bienv3ambienjusticia'] = 0; 
}
if (isset($_REQUEST['cara44bienv3ambienagroeco']) == 0) {
    $_REQUEST['cara44bienv3ambienagroeco'] = 0; 
}
if (isset($_REQUEST['cara44bienv3ambieneconomia']) == 0) {
    $_REQUEST['cara44bienv3ambieneconomia'] = 0; 
}
if (isset($_REQUEST['cara44bienv3ambieneducacion']) == 0) {
    $_REQUEST['cara44bienv3ambieneducacion'] = 0; 
}
if (isset($_REQUEST['cara44bienv3ambienbiodiverso']) == 0) {
    $_REQUEST['cara44bienv3ambienbiodiverso'] = 0; 
}
if (isset($_REQUEST['cara44bienv3ambienecoturismo']) == 0) {
    $_REQUEST['cara44bienv3ambienecoturismo'] = 0; 
}
if (isset($_REQUEST['cara44bienv3ambienotro']) == 0) {
    $_REQUEST['cara44bienv3ambienotro'] = 0; 
}
if (isset($_REQUEST['cara44bienv3ambienotrodetalle']) == 0) {
    $_REQUEST['cara44bienv3ambienotrodetalle'] = ''; 
}
if (isset($_REQUEST['cara44bienv3ambienexper']) == 0) {
    $_REQUEST['cara44bienv3ambienexper'] = 0; 
}
if (isset($_REQUEST['cara44bienv3ambienaprende']) == 0) {
    $_REQUEST['cara44bienv3ambienaprende'] = 0; 
}
if (isset($_REQUEST['cara44bienv3ambienestudiante']) == 0) {
    $_REQUEST['cara44bienv3ambienestudiante'] = 0; 
}
if (isset($_REQUEST['cara44bienv3ambienactividad']) == 0) {
    $_REQUEST['cara44bienv3ambienactividad'] = 0; 
}
if (isset($_REQUEST['cara44bienv3pyphabitoalim']) == 0) {
    $_REQUEST['cara44bienv3pyphabitoalim'] = 0; 
}
if (isset($_REQUEST['cara44bienv3pypsustanciapsico']) == 0) {
    $_REQUEST['cara44bienv3pypsustanciapsico'] = 0; 
}
if (isset($_REQUEST['cara44bienv3pypsaludvisual']) == 0) {
    $_REQUEST['cara44bienv3pypsaludvisual'] = 0; 
}
if (isset($_REQUEST['cara44bienv3pypsaludbucal']) == 0) {
    $_REQUEST['cara44bienv3pypsaludbucal'] = 0; 
}
if (isset($_REQUEST['cara44bienv3pypsaludsexual']) == 0) {
    $_REQUEST['cara44bienv3pypsaludsexual'] = 0; 
}
if (isset($_REQUEST['cara44bienv3deportenivel']) == 0) {
    $_REQUEST['cara44bienv3deportenivel'] = 0; 
}
if (isset($_REQUEST['cara44bienv3deportefrec']) == 0) {
    $_REQUEST['cara44bienv3deportefrec'] = 0; 
}
if (isset($_REQUEST['cara44bienv3deportecual']) == 0) {
    $_REQUEST['cara44bienv3deportecual'] = 0; 
}
if (isset($_REQUEST['cara44bienv3deportecualotro']) == 0) {
    $_REQUEST['cara44bienv3deportecualotro'] = ''; 
}
if (isset($_REQUEST['cara44bienv3deporterecrea']) == 0) {
    $_REQUEST['cara44bienv3deporterecrea'] = 0; 
}
if (isset($_REQUEST['cara44bienv3deporteunad']) == 0) {
    $_REQUEST['cara44bienv3deporteunad'] = 0; 
}
if (isset($_REQUEST['cara44bienv3creciminclusion']) == 0) {
    $_REQUEST['cara44bienv3creciminclusion'] = 0; 
}
if (isset($_REQUEST['cara44bienv3crecimfamilia']) == 0) {
    $_REQUEST['cara44bienv3crecimfamilia'] = 0; 
}
if (isset($_REQUEST['cara44bienv3crecimhabilidad']) == 0) {
    $_REQUEST['cara44bienv3crecimhabilidad'] = 0; 
}
if (isset($_REQUEST['cara44bienv3crecimempleable']) == 0) {
    $_REQUEST['cara44bienv3crecimempleable'] = 0; 
}
if (isset($_REQUEST['cara44bienv3crecimhabilvida']) == 0) {
    $_REQUEST['cara44bienv3crecimhabilvida'] = 0; 
}
if (isset($_REQUEST['cara44bienv3crecimespiritual']) == 0) {
    $_REQUEST['cara44bienv3crecimespiritual'] = 0; 
}
if (isset($_REQUEST['cara44bienv3crecimpractica']) == 0) {
    $_REQUEST['cara44bienv3crecimpractica'] = 0; 
}
if (isset($_REQUEST['cara44bienv3crecimliderazgo']) == 0) {
    $_REQUEST['cara44bienv3crecimliderazgo'] = 0; 
}
if (isset($_REQUEST['cara44bienv3crecimtrabequipo']) == 0) {
    $_REQUEST['cara44bienv3crecimtrabequipo'] = 0; 
}
if (isset($_REQUEST['cara44bienv3crecimasertiva']) == 0) {
    $_REQUEST['cara44bienv3crecimasertiva'] = 0; 
}
if (isset($_REQUEST['cara44bienv3crecimgesttiempo']) == 0) {
    $_REQUEST['cara44bienv3crecimgesttiempo'] = 0; 
}
if (isset($_REQUEST['cara44bienv3crecimconflictos']) == 0) {
    $_REQUEST['cara44bienv3crecimconflictos'] = 0; 
}
if (isset($_REQUEST['cara44bienv3crecimadapcambio']) == 0) {
    $_REQUEST['cara44bienv3crecimadapcambio'] = 0; 
}
if (isset($_REQUEST['cara44bienv3crecimempatia']) == 0) {
    $_REQUEST['cara44bienv3crecimempatia'] = 0; 
}
if (isset($_REQUEST['cara44bienv3crecimgestionser']) == 0) {
    $_REQUEST['cara44bienv3crecimgestionser'] = 0; 
}
if (isset($_REQUEST['cara44bienv3crecimtomadecide']) == 0) {
    $_REQUEST['cara44bienv3crecimtomadecide'] = 0; 
}
if (isset($_REQUEST['cara44bienv3crecimpenscreativo']) == 0) {
    $_REQUEST['cara44bienv3crecimpenscreativo'] = 0; 
}
if (isset($_REQUEST['cara44bienv3crecimpenscritico']) == 0) {
    $_REQUEST['cara44bienv3crecimpenscritico'] = 0; 
}
if (isset($_REQUEST['cara44bienv3crecimhabilotro']) == 0) {
    $_REQUEST['cara44bienv3crecimhabilotro'] = 0; 
}
if (isset($_REQUEST['cara44bienv3crecimhabilotrodetalle']) == 0) {
    $_REQUEST['cara44bienv3crecimhabilotrodetalle'] = ''; 
}
if (isset($_REQUEST['cara44bienv3crecimalcancemeta']) == 0) {
    $_REQUEST['cara44bienv3crecimalcancemeta'] = 0; 
}
if (isset($_REQUEST['cara44bienv3crecimsatifpersonal']) == 0) {
    $_REQUEST['cara44bienv3crecimsatifpersonal'] = 0; 
}
if (isset($_REQUEST['cara44bienv3crecimaccesolaboral']) == 0) {
    $_REQUEST['cara44bienv3crecimaccesolaboral'] = 0; 
}
if (isset($_REQUEST['cara44bienv3crecimotramotiv']) == 0) {
    $_REQUEST['cara44bienv3crecimotramotiv'] = 0; 
}
if (isset($_REQUEST['cara44bienv3crecimotramotivdetalle']) == 0) {
    $_REQUEST['cara44bienv3crecimotramotivdetalle'] = ''; 
}
if (isset($_REQUEST['cara44bienv3crecimapoyo']) == 0) {
    $_REQUEST['cara44bienv3crecimapoyo'] = 0; 
}
if (isset($_REQUEST['cara44bienv3crecimlaboral']) == 0) {
    $_REQUEST['cara44bienv3crecimlaboral'] = 0; 
}
if (isset($_REQUEST['cara44bienv3mentalcuidado']) == 0) {
    $_REQUEST['cara44bienv3mentalcuidado'] = 0; 
}
if (isset($_REQUEST['cara44bienv3mentalestrategia']) == 0) {
    $_REQUEST['cara44bienv3mentalestrategia'] = 0; 
}
if (isset($_REQUEST['cara44bienv3mentalestres']) == 0) {
    $_REQUEST['cara44bienv3mentalestres'] = 0; 
}
if (isset($_REQUEST['cara44bienv3mentalansiedad']) == 0) {
    $_REQUEST['cara44bienv3mentalansiedad'] = 0; 
}
if (isset($_REQUEST['cara44bienv3mentaldepresion']) == 0) {
    $_REQUEST['cara44bienv3mentaldepresion'] = 0; 
}
if (isset($_REQUEST['cara44bienv3mentalautoconoc']) == 0) {
    $_REQUEST['cara44bienv3mentalautoconoc'] = 0; 
}
if (isset($_REQUEST['cara44bienv3mentalmindfulness']) == 0) {
    $_REQUEST['cara44bienv3mentalmindfulness'] = 0; 
}
if (isset($_REQUEST['cara44bienv3mentalautoestima']) == 0) {
    $_REQUEST['cara44bienv3mentalautoestima'] = 0; 
}
if (isset($_REQUEST['cara44bienv3mentalcrisis']) == 0) {
    $_REQUEST['cara44bienv3mentalcrisis'] = 0; 
}
if (isset($_REQUEST['cara44bienv3mentalburnout']) == 0) {
    $_REQUEST['cara44bienv3mentalburnout'] = 0; 
}
if (isset($_REQUEST['cara44bienv3mentalsexualidad']) == 0) {
    $_REQUEST['cara44bienv3mentalsexualidad'] = 0; 
}
if (isset($_REQUEST['cara44bienv3mentalusoredes']) == 0) {
    $_REQUEST['cara44bienv3mentalusoredes'] = 0; 
}
if (isset($_REQUEST['cara44bienv3mentalinclusion']) == 0) {
    $_REQUEST['cara44bienv3mentalinclusion'] = 0; 
}
if (isset($_REQUEST['cara44bienv3mentalactividad']) == 0) {
    $_REQUEST['cara44bienv3mentalactividad'] = 0; 
}
if (isset($_REQUEST['cara44bienv3mentalacompana']) == 0) {
    $_REQUEST['cara44bienv3mentalacompana'] = 0; 
}
if (isset($_REQUEST['cara44bienv3mentaldiagnostico']) == 0) {
    $_REQUEST['cara44bienv3mentaldiagnostico'] = 0; 
}
if (isset($_REQUEST['cara44bienv3mentaldiagcual']) == 0) {
    $_REQUEST['cara44bienv3mentaldiagcual'] = 0; 
}
if (isset($_REQUEST['cara44bienv3mentaldiagotro']) == 0) {
    $_REQUEST['cara44bienv3mentaldiagotro'] = ''; 
}
if (isset($_REQUEST['cara44bienv3arteintegrar']) == 0) {
    $_REQUEST['cara44bienv3arteintegrar'] = 0; 
}
if (isset($_REQUEST['cara44bienv3arteformacion']) == 0) {
    $_REQUEST['cara44bienv3arteformacion'] = 0; 
}
if (isset($_REQUEST['cara44bienv3arteunad']) == 0) {
    $_REQUEST['cara44bienv3arteunad'] = 0; 
}
if (isset($_REQUEST['cara44bienv3arteinformacion']) == 0) {
    $_REQUEST['cara44bienv3arteinformacion'] = 0; 
}
$_REQUEST['cara44campesinado'] = cadena_Validar($_REQUEST['cara44campesinado']);
$_REQUEST['cara44campus_medioactivunad'] = numeros_validar($_REQUEST['cara44campus_medioactivunad']);
$_REQUEST['cara44campus_medioactivunaddetalle'] = cadena_Validar($_REQUEST['cara44campus_medioactivunaddetalle']);
$_REQUEST['cara44frontera'] = numeros_validar($_REQUEST['cara44frontera']);
$_REQUEST['cara44med_tratamiento'] = numeros_validar($_REQUEST['cara44med_tratamiento']);
$_REQUEST['cara44med_trat_cual'] = cadena_Validar($_REQUEST['cara44med_trat_cual']);
$_REQUEST['cara44bienv3emprenetapa'] = numeros_validar($_REQUEST['cara44bienv3emprenetapa']);
$_REQUEST['cara44bienv3emprennecesita'] = numeros_validar($_REQUEST['cara44bienv3emprennecesita']);
$_REQUEST['cara44bienv3emprenanioini'] = numeros_validar($_REQUEST['cara44bienv3emprenanioini']);
$_REQUEST['cara44bienv3emprensector'] = numeros_validar($_REQUEST['cara44bienv3emprensector']);
$_REQUEST['cara44bienv3emprensectorotro'] = cadena_Validar($_REQUEST['cara44bienv3emprensectorotro']);
$_REQUEST['cara44bienv3emprentemas'] = numeros_validar($_REQUEST['cara44bienv3emprentemas']);
$_REQUEST['cara44bienv3ambienclima'] = numeros_validar($_REQUEST['cara44bienv3ambienclima']);
$_REQUEST['cara44bienv3ambienjusticia'] = numeros_validar($_REQUEST['cara44bienv3ambienjusticia']);
$_REQUEST['cara44bienv3ambienagroeco'] = numeros_validar($_REQUEST['cara44bienv3ambienagroeco']);
$_REQUEST['cara44bienv3ambieneconomia'] = numeros_validar($_REQUEST['cara44bienv3ambieneconomia']);
$_REQUEST['cara44bienv3ambieneducacion'] = numeros_validar($_REQUEST['cara44bienv3ambieneducacion']);
$_REQUEST['cara44bienv3ambienbiodiverso'] = numeros_validar($_REQUEST['cara44bienv3ambienbiodiverso']);
$_REQUEST['cara44bienv3ambienecoturismo'] = numeros_validar($_REQUEST['cara44bienv3ambienecoturismo']);
$_REQUEST['cara44bienv3ambienotro'] = numeros_validar($_REQUEST['cara44bienv3ambienotro']);
$_REQUEST['cara44bienv3ambienotrodetalle'] = cadena_Validar($_REQUEST['cara44bienv3ambienotrodetalle']);
$_REQUEST['cara44bienv3ambienexper'] = numeros_validar($_REQUEST['cara44bienv3ambienexper']);
$_REQUEST['cara44bienv3ambienaprende'] = numeros_validar($_REQUEST['cara44bienv3ambienaprende']);
$_REQUEST['cara44bienv3ambienestudiante'] = numeros_validar($_REQUEST['cara44bienv3ambienestudiante']);
$_REQUEST['cara44bienv3ambienactividad'] = numeros_validar($_REQUEST['cara44bienv3ambienactividad']);
$_REQUEST['cara44bienv3pyphabitoalim'] = numeros_validar($_REQUEST['cara44bienv3pyphabitoalim']);
$_REQUEST['cara44bienv3pypsustanciapsico'] = numeros_validar($_REQUEST['cara44bienv3pypsustanciapsico']);
$_REQUEST['cara44bienv3pypsaludvisual'] = numeros_validar($_REQUEST['cara44bienv3pypsaludvisual']);
$_REQUEST['cara44bienv3pypsaludbucal'] = numeros_validar($_REQUEST['cara44bienv3pypsaludbucal']);
$_REQUEST['cara44bienv3pypsaludsexual'] = numeros_validar($_REQUEST['cara44bienv3pypsaludsexual']);
$_REQUEST['cara44bienv3deportenivel'] = numeros_validar($_REQUEST['cara44bienv3deportenivel']);
$_REQUEST['cara44bienv3deportefrec'] = numeros_validar($_REQUEST['cara44bienv3deportefrec']);
$_REQUEST['cara44bienv3deportecual'] = numeros_validar($_REQUEST['cara44bienv3deportecual']);
$_REQUEST['cara44bienv3deportecualotro'] = cadena_Validar($_REQUEST['cara44bienv3deportecualotro']);
$_REQUEST['cara44bienv3deporterecrea'] = numeros_validar($_REQUEST['cara44bienv3deporterecrea']);
$_REQUEST['cara44bienv3deporteunad'] = numeros_validar($_REQUEST['cara44bienv3deporteunad']);
$_REQUEST['cara44bienv3creciminclusion'] = numeros_validar($_REQUEST['cara44bienv3creciminclusion']);
$_REQUEST['cara44bienv3crecimfamilia'] = numeros_validar($_REQUEST['cara44bienv3crecimfamilia']);
$_REQUEST['cara44bienv3crecimhabilidad'] = numeros_validar($_REQUEST['cara44bienv3crecimhabilidad']);
$_REQUEST['cara44bienv3crecimempleable'] = numeros_validar($_REQUEST['cara44bienv3crecimempleable']);
$_REQUEST['cara44bienv3crecimhabilvida'] = numeros_validar($_REQUEST['cara44bienv3crecimhabilvida']);
$_REQUEST['cara44bienv3crecimespiritual'] = numeros_validar($_REQUEST['cara44bienv3crecimespiritual']);
$_REQUEST['cara44bienv3crecimpractica'] = numeros_validar($_REQUEST['cara44bienv3crecimpractica']);
$_REQUEST['cara44bienv3crecimliderazgo'] = numeros_validar($_REQUEST['cara44bienv3crecimliderazgo']);
$_REQUEST['cara44bienv3crecimtrabequipo'] = numeros_validar($_REQUEST['cara44bienv3crecimtrabequipo']);
$_REQUEST['cara44bienv3crecimasertiva'] = numeros_validar($_REQUEST['cara44bienv3crecimasertiva']);
$_REQUEST['cara44bienv3crecimgesttiempo'] = numeros_validar($_REQUEST['cara44bienv3crecimgesttiempo']);
$_REQUEST['cara44bienv3crecimconflictos'] = numeros_validar($_REQUEST['cara44bienv3crecimconflictos']);
$_REQUEST['cara44bienv3crecimadapcambio'] = numeros_validar($_REQUEST['cara44bienv3crecimadapcambio']);
$_REQUEST['cara44bienv3crecimempatia'] = numeros_validar($_REQUEST['cara44bienv3crecimempatia']);
$_REQUEST['cara44bienv3crecimgestionser'] = numeros_validar($_REQUEST['cara44bienv3crecimgestionser']);
$_REQUEST['cara44bienv3crecimtomadecide'] = numeros_validar($_REQUEST['cara44bienv3crecimtomadecide']);
$_REQUEST['cara44bienv3crecimpenscreativo'] = numeros_validar($_REQUEST['cara44bienv3crecimpenscreativo']);
$_REQUEST['cara44bienv3crecimpenscritico'] = numeros_validar($_REQUEST['cara44bienv3crecimpenscritico']);
$_REQUEST['cara44bienv3crecimhabilotro'] = numeros_validar($_REQUEST['cara44bienv3crecimhabilotro']);
$_REQUEST['cara44bienv3crecimhabilotrodetalle'] = cadena_Validar($_REQUEST['cara44bienv3crecimhabilotrodetalle']);
$_REQUEST['cara44bienv3crecimalcancemeta'] = numeros_validar($_REQUEST['cara44bienv3crecimalcancemeta']);
$_REQUEST['cara44bienv3crecimsatifpersonal'] = numeros_validar($_REQUEST['cara44bienv3crecimsatifpersonal']);
$_REQUEST['cara44bienv3crecimaccesolaboral'] = numeros_validar($_REQUEST['cara44bienv3crecimaccesolaboral']);
$_REQUEST['cara44bienv3crecimotramotiv'] = numeros_validar($_REQUEST['cara44bienv3crecimotramotiv']);
$_REQUEST['cara44bienv3crecimotramotivdetalle'] = cadena_Validar($_REQUEST['cara44bienv3crecimotramotivdetalle']);
$_REQUEST['cara44bienv3crecimapoyo'] = numeros_validar($_REQUEST['cara44bienv3crecimapoyo']);
$_REQUEST['cara44bienv3crecimlaboral'] = numeros_validar($_REQUEST['cara44bienv3crecimlaboral']);
$_REQUEST['cara44bienv3mentalcuidado'] = numeros_validar($_REQUEST['cara44bienv3mentalcuidado']);
$_REQUEST['cara44bienv3mentalestrategia'] = numeros_validar($_REQUEST['cara44bienv3mentalestrategia']);
$_REQUEST['cara44bienv3mentalestres'] = numeros_validar($_REQUEST['cara44bienv3mentalestres']);
$_REQUEST['cara44bienv3mentalansiedad'] = numeros_validar($_REQUEST['cara44bienv3mentalansiedad']);
$_REQUEST['cara44bienv3mentaldepresion'] = numeros_validar($_REQUEST['cara44bienv3mentaldepresion']);
$_REQUEST['cara44bienv3mentalautoconoc'] = numeros_validar($_REQUEST['cara44bienv3mentalautoconoc']);
$_REQUEST['cara44bienv3mentalmindfulness'] = numeros_validar($_REQUEST['cara44bienv3mentalmindfulness']);
$_REQUEST['cara44bienv3mentalautoestima'] = numeros_validar($_REQUEST['cara44bienv3mentalautoestima']);
$_REQUEST['cara44bienv3mentalcrisis'] = numeros_validar($_REQUEST['cara44bienv3mentalcrisis']);
$_REQUEST['cara44bienv3mentalburnout'] = numeros_validar($_REQUEST['cara44bienv3mentalburnout']);
$_REQUEST['cara44bienv3mentalsexualidad'] = numeros_validar($_REQUEST['cara44bienv3mentalsexualidad']);
$_REQUEST['cara44bienv3mentalusoredes'] = numeros_validar($_REQUEST['cara44bienv3mentalusoredes']);
$_REQUEST['cara44bienv3mentalinclusion'] = numeros_validar($_REQUEST['cara44bienv3mentalinclusion']);
$_REQUEST['cara44bienv3mentalactividad'] = numeros_validar($_REQUEST['cara44bienv3mentalactividad']);
$_REQUEST['cara44bienv3mentalacompana'] = numeros_validar($_REQUEST['cara44bienv3mentalacompana']);
$_REQUEST['cara44bienv3mentaldiagnostico'] = numeros_validar($_REQUEST['cara44bienv3mentaldiagnostico']);
$_REQUEST['cara44bienv3mentaldiagcual'] = numeros_validar($_REQUEST['cara44bienv3mentaldiagcual']);
$_REQUEST['cara44bienv3mentaldiagotro'] = cadena_Validar($_REQUEST['cara44bienv3mentaldiagotro']);
$_REQUEST['cara44bienv3arteintegrar'] = numeros_validar($_REQUEST['cara44bienv3arteintegrar']);
$_REQUEST['cara44bienv3arteformacion'] = numeros_validar($_REQUEST['cara44bienv3arteformacion']);
$_REQUEST['cara44bienv3arteunad'] = numeros_validar($_REQUEST['cara44bienv3arteunad']);
$_REQUEST['cara44bienv3arteinformacion'] = numeros_validar($_REQUEST['cara44bienv3arteinformacion']);
if ((int)$_REQUEST['paso'] > 0) {
	//Preguntas de la prueba
}
// Espacio para inicializar otras variables
$bTraerEntorno = false;
$bActualizarEdad = false;
if (isset($_REQUEST['csv_separa']) == 0) {
	$_REQUEST['csv_separa'] = ',';
}
if (isset($_REQUEST['ficha']) == 0) {
	$_REQUEST['ficha'] = 1;
}
if (!$bEstudiante) {
	if (isset($_REQUEST['bdoc']) == 0) {
		$_REQUEST['bdoc'] = '';
	}
	if (isset($_REQUEST['bnombre']) == 0) {
		$_REQUEST['bnombre'] = '';
	}
	if (isset($_REQUEST['bperiodo']) == 0) {
		$_REQUEST['bperiodo'] = '';
		$bTraerEntorno = true;
	}
	if (isset($_REQUEST['blistar']) == 0) {
		$_REQUEST['blistar'] = 1;
	}
	if (isset($_REQUEST['bescuela']) == 0) {
		$_REQUEST['bescuela'] = '';
	}
	if (isset($_REQUEST['bprograma']) == 0) {
		$_REQUEST['bprograma'] = '';
	}
	if (isset($_REQUEST['bzona']) == 0) {
		$_REQUEST['bzona'] = '';
	}
	if (isset($_REQUEST['bcead']) == 0) {
		$_REQUEST['bcead'] = '';
	}
	if (isset($_REQUEST['btipocara']) == 0) {
		$_REQUEST['btipocara'] = '';
	}
	if (isset($_REQUEST['bpoblacion']) == 0) {
		$_REQUEST['bpoblacion'] = '';
	}
	if (isset($_REQUEST['bconvenio']) == 0) {
		$_REQUEST['bconvenio'] = '';
	}
}
if ($bTraerEntorno) {
	$sSQL = 'SELECT * FROM unad95entorno WHERE unad95id=' . $idTercero . '';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		if ($fila['unad95periodo'] != 0) {
			$_REQUEST['bperiodo'] = $fila['unad95periodo'];
		}
		if ($fila['unad95escuela'] != 0) {
			$_REQUEST['bescuela'] = $fila['unad95escuela'];
		}
		if ($fila['unad95programa'] != 0) {
			$_REQUEST['bprograma'] = $fila['unad95programa'];
		}
		if ($fila['unad95zona'] != 0) {
			$_REQUEST['bzona'] = $fila['unad95zona'];
		}
		if ($fila['unad95centro'] != 0) {
			$_REQUEST['bcead'] = $fila['unad95centro'];
		}
	}
}
if ($bDebug) {
	$sDebug = $sDebug . fecha_microtiempo() . ' Corriendo el paso ' . $_REQUEST['paso'] . ' antes de entrar a leer instrucciones.<br>';
}
if ($bEstudiante) {
	if ($_REQUEST['paso'] == -1) {
		$_REQUEST['paso'] = 3;
	}
	//2022 - 08 - 19 - Le armamos el historial
	$sSQL = 'SELECT TB.cara01id, TB.cara01idperaca, TB.cara01completa, T2.exte02titulo 
	FROM cara01encuesta AS TB, exte02per_aca AS T2 
	WHERE TB.cara01idtercero=' . $idTercero . ' AND TB.cara01idperaca=T2.exte02id
	ORDER BY TB.cara01completa, TB.cara01idperaca DESC';
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' Listado de encuestas: ' . $sSQL . '<br>';
	}
	$tabla = $objDB->ejecutasql($sSQL);
	$iNumFilas = $objDB->nf($tabla);
	if ($iNumFilas > 1) {
		$sHTMLHistorial = '<div class="GrupoCamposAyuda">
		<b>Periodos en los que debe diligenciar encuesta de caracterizaci&oacute;n:</b>
		' . html_salto() . '
		<div class="tabuladores">';
		$bMarcaInicial = false;
		while ($fila = $objDB->sf($tabla)) {
			$sNomPeriodo = cadena_notildes($fila['exte02titulo']);
			$k = $fila['cara01idperaca'];
			if ($k != $_REQUEST['cara01idperaca']) {}
			if (true){
				if ($fila['cara01completa'] == 'S') {
					$sHTMLHistorial = $sHTMLHistorial . html_BotonVerde('p_' . $k, $sNomPeriodo, 'javascript:cargaridf2301(' . $fila['cara01id'] . ');', $sNomPeriodo);
				} else {
					$sHTMLHistorial = $sHTMLHistorial . html_BotonRojo('p_' . $k, $sNomPeriodo, 'javascript:cargaridf2301(' . $fila['cara01id'] . ');', $sNomPeriodo);
				}
			}
		}
		$sHTMLHistorial = $sHTMLHistorial . html_salto().'</div></div>';
	}
}
//Si Modifica o Elimina Cargar los campos
if (($_REQUEST['paso'] == 1) || ($_REQUEST['paso'] == 3)) {
	$_REQUEST['cara01idtercero_td'] = $APP->tipo_doc;
	$_REQUEST['cara01idtercero_doc'] = '';
	$_REQUEST['cara01idconfirmadesp_td'] = $APP->tipo_doc;
	$_REQUEST['cara01idconfirmadesp_doc'] = '';
	$_REQUEST['cara01idconfirmacr_td'] = $APP->tipo_doc;
	$_REQUEST['cara01idconfirmacr_doc'] = '';
	$_REQUEST['cara01idconfirmadisc_td'] = $APP->tipo_doc;
	$_REQUEST['cara01idconfirmadisc_doc'] = '';
	$_REQUEST['cara01idconsejero_td'] = $APP->tipo_doc;
	$_REQUEST['cara01idconsejero_doc'] = '';
	if ($_REQUEST['paso'] == 1) {
		$sSQLcondi = 'cara01idperaca=' . $_REQUEST['cara01idperaca'] . ' AND cara01idtercero=' . $_REQUEST['cara01idtercero'] . '';
	} else {
		$sSQLcondi = 'cara01id=' . $_REQUEST['cara01id'] . '';
	}
	$sSQL = 'SELECT * FROM cara01encuesta WHERE ' . $sSQLcondi;
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' Cargando encuesta: ' . $sSQL . '<br>';
	}
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$_REQUEST['cara01idperaca'] = $fila['cara01idperaca'];
		$_REQUEST['cara01idtercero'] = $fila['cara01idtercero'];
		$_REQUEST['cara01id'] = $fila['cara01id'];
		$_REQUEST['cara01completa'] = $fila['cara01completa'];
		$_REQUEST['cara01fichaper'] = $fila['cara01fichaper'];
		$_REQUEST['cara01fichafam'] = $fila['cara01fichafam'];
		$_REQUEST['cara01fichaaca'] = $fila['cara01fichaaca'];
		$_REQUEST['cara01fichalab'] = $fila['cara01fichalab'];
		$_REQUEST['cara01fichabien'] = $fila['cara01fichabien'];
		$_REQUEST['cara01fichapsico'] = $fila['cara01fichapsico'];
		$_REQUEST['cara01fichadigital'] = $fila['cara01fichadigital'];
		$_REQUEST['cara01fichalectura'] = $fila['cara01fichalectura'];
		$_REQUEST['cara01ficharazona'] = $fila['cara01ficharazona'];
		$_REQUEST['cara01fichaingles'] = $fila['cara01fichaingles'];
		$_REQUEST['cara01fechaencuesta'] = $fila['cara01fechaencuesta'];
		$_REQUEST['cara01agnos'] = $fila['cara01agnos'];
		$_REQUEST['cara01sexo'] = $fila['cara01sexo'];
		$_REQUEST['cara01pais'] = $fila['cara01pais'];
		$_REQUEST['cara01depto'] = $fila['cara01depto'];
		$_REQUEST['cara01ciudad'] = $fila['cara01ciudad'];
		$_REQUEST['cara01nomciudad'] = $fila['cara01nomciudad'];
		$_REQUEST['cara01direccion'] = $fila['cara01direccion'];
		$_REQUEST['cara01estrato'] = $fila['cara01estrato'];
		$_REQUEST['cara01zonares'] = $fila['cara01zonares'];
		$_REQUEST['cara01estcivil'] = $fila['cara01estcivil'];
		$_REQUEST['cara01nomcontacto'] = $fila['cara01nomcontacto'];
		$_REQUEST['cara01parentezcocontacto'] = $fila['cara01parentezcocontacto'];
		$_REQUEST['cara01celcontacto'] = $fila['cara01celcontacto'];
		$_REQUEST['cara01correocontacto'] = $fila['cara01correocontacto'];
		$_REQUEST['cara01idzona'] = $fila['cara01idzona'];
		$_REQUEST['cara01idcead'] = $fila['cara01idcead'];
		$_REQUEST['cara01matconvenio'] = $fila['cara01matconvenio'];
		$_REQUEST['cara01raizal'] = $fila['cara01raizal'];
		$_REQUEST['cara01palenquero'] = $fila['cara01palenquero'];
		$_REQUEST['cara01afrocolombiano'] = $fila['cara01afrocolombiano'];
		$_REQUEST['cara01otracomunnegras'] = $fila['cara01otracomunnegras'];
		$_REQUEST['cara01rom'] = $fila['cara01rom'];
		$_REQUEST['cara01indigenas'] = $fila['cara01indigenas'];
		$_REQUEST['cara01victimadesplazado'] = $fila['cara01victimadesplazado'];
		$_REQUEST['cara01idconfirmadesp'] = $fila['cara01idconfirmadesp'];
		$_REQUEST['cara01fechaconfirmadesp'] = $fila['cara01fechaconfirmadesp'];
		$_REQUEST['cara01victimaacr'] = $fila['cara01victimaacr'];
		$_REQUEST['cara01idconfirmacr'] = $fila['cara01idconfirmacr'];
		$_REQUEST['cara01fechaconfirmacr'] = $fila['cara01fechaconfirmacr'];
		$_REQUEST['cara01inpecfuncionario'] = $fila['cara01inpecfuncionario'];
		$_REQUEST['cara01inpecrecluso'] = $fila['cara01inpecrecluso'];
		$_REQUEST['cara01inpectiempocondena'] = $fila['cara01inpectiempocondena'];
		$_REQUEST['cara01centroreclusion'] = $fila['cara01centroreclusion'];
		$_REQUEST['cara01discsensorial'] = $fila['cara01discsensorial'];
		$_REQUEST['cara01discfisica'] = $fila['cara01discfisica'];
		$_REQUEST['cara01disccognitiva'] = $fila['cara01disccognitiva'];
		$_REQUEST['cara01idconfirmadisc'] = $fila['cara01idconfirmadisc'];
		$_REQUEST['cara01fechaconfirmadisc'] = $fila['cara01fechaconfirmadisc'];
		$_REQUEST['cara01fam_tipovivienda'] = $fila['cara01fam_tipovivienda'];
		$_REQUEST['cara01fam_vivecon'] = $fila['cara01fam_vivecon'];
		$_REQUEST['cara01fam_numpersgrupofam'] = $fila['cara01fam_numpersgrupofam'];
		$_REQUEST['cara01fam_hijos'] = $fila['cara01fam_hijos'];
		$_REQUEST['cara01fam_personasacargo'] = $fila['cara01fam_personasacargo'];
		$_REQUEST['cara01fam_dependeecon'] = $fila['cara01fam_dependeecon'];
		$_REQUEST['cara01fam_escolaridadpadre'] = $fila['cara01fam_escolaridadpadre'];
		$_REQUEST['cara01fam_escolaridadmadre'] = $fila['cara01fam_escolaridadmadre'];
		$_REQUEST['cara01fam_numhermanos'] = $fila['cara01fam_numhermanos'];
		$_REQUEST['cara01fam_posicionherm'] = $fila['cara01fam_posicionherm'];
		$_REQUEST['cara01fam_familiaunad'] = $fila['cara01fam_familiaunad'];
		$_REQUEST['cara01acad_tipocolegio'] = $fila['cara01acad_tipocolegio'];
		$_REQUEST['cara01acad_modalidadbach'] = $fila['cara01acad_modalidadbach'];
		$_REQUEST['cara01acad_estudioprev'] = $fila['cara01acad_estudioprev'];
		$_REQUEST['cara01acad_ultnivelest'] = $fila['cara01acad_ultnivelest'];
		$_REQUEST['cara01acad_obtubodiploma'] = $fila['cara01acad_obtubodiploma'];
		$_REQUEST['cara01acad_hatomadovirtual'] = $fila['cara01acad_hatomadovirtual'];
		$_REQUEST['cara01acad_tiemposinest'] = $fila['cara01acad_tiemposinest'];
		$_REQUEST['cara01acad_razonestudio'] = $fila['cara01acad_razonestudio'];
		$_REQUEST['cara01acad_primeraopc'] = $fila['cara01acad_primeraopc'];
		$_REQUEST['cara01acad_programagusto'] = $fila['cara01acad_programagusto'];
		$_REQUEST['cara01acad_razonunad'] = $fila['cara01acad_razonunad'];
		$_REQUEST['cara01campus_compescrito'] = $fila['cara01campus_compescrito'];
		$_REQUEST['cara01campus_portatil'] = $fila['cara01campus_portatil'];
		$_REQUEST['cara01campus_tableta'] = $fila['cara01campus_tableta'];
		$_REQUEST['cara01campus_telefono'] = $fila['cara01campus_telefono'];
		$_REQUEST['cara01campus_energia'] = $fila['cara01campus_energia'];
		$_REQUEST['cara01campus_internetreside'] = $fila['cara01campus_internetreside'];
		$_REQUEST['cara01campus_expvirtual'] = $fila['cara01campus_expvirtual'];
		$_REQUEST['cara01campus_ofimatica'] = $fila['cara01campus_ofimatica'];
		$_REQUEST['cara01campus_foros'] = $fila['cara01campus_foros'];
		$_REQUEST['cara01campus_conversiones'] = $fila['cara01campus_conversiones'];
		$_REQUEST['cara01campus_usocorreo'] = $fila['cara01campus_usocorreo'];
		$_REQUEST['cara01campus_aprendtexto'] = $fila['cara01campus_aprendtexto'];
		$_REQUEST['cara01campus_aprendvideo'] = $fila['cara01campus_aprendvideo'];
		$_REQUEST['cara01campus_aprendmapas'] = $fila['cara01campus_aprendmapas'];
		$_REQUEST['cara01campus_aprendeanima'] = $fila['cara01campus_aprendeanima'];
		$_REQUEST['cara01campus_mediocomunica'] = $fila['cara01campus_mediocomunica'];
		$_REQUEST['cara01lab_situacion'] = $fila['cara01lab_situacion'];
		$_REQUEST['cara01lab_sector'] = $fila['cara01lab_sector'];
		$_REQUEST['cara01lab_caracterjuri'] = $fila['cara01lab_caracterjuri'];
		$_REQUEST['cara01lab_cargo'] = $fila['cara01lab_cargo'];
		$_REQUEST['cara01lab_antiguedad'] = $fila['cara01lab_antiguedad'];
		$_REQUEST['cara01lab_tipocontrato'] = $fila['cara01lab_tipocontrato'];
		$_REQUEST['cara01lab_rangoingreso'] = $fila['cara01lab_rangoingreso'];
		$_REQUEST['cara01lab_tiempoacadem'] = $fila['cara01lab_tiempoacadem'];
		$_REQUEST['cara01lab_tipoempresa'] = $fila['cara01lab_tipoempresa'];
		$_REQUEST['cara01lab_tiempoindepen'] = $fila['cara01lab_tiempoindepen'];
		$_REQUEST['cara01lab_debebusctrab'] = $fila['cara01lab_debebusctrab'];
		$_REQUEST['cara01lab_origendinero'] = $fila['cara01lab_origendinero'];
		$_REQUEST['cara01bien_baloncesto'] = $fila['cara01bien_baloncesto'];
		$_REQUEST['cara01bien_voleibol'] = $fila['cara01bien_voleibol'];
		$_REQUEST['cara01bien_futbolsala'] = $fila['cara01bien_futbolsala'];
		$_REQUEST['cara01bien_artesmarc'] = $fila['cara01bien_artesmarc'];
		$_REQUEST['cara01bien_tenisdemesa'] = $fila['cara01bien_tenisdemesa'];
		$_REQUEST['cara01bien_ajedrez'] = $fila['cara01bien_ajedrez'];
		$_REQUEST['cara01bien_juegosautoc'] = $fila['cara01bien_juegosautoc'];
		$_REQUEST['cara01bien_interesrepdeporte'] = $fila['cara01bien_interesrepdeporte'];
		$_REQUEST['cara01bien_deporteint'] = $fila['cara01bien_deporteint'];
		$_REQUEST['cara01bien_teatro'] = $fila['cara01bien_teatro'];
		$_REQUEST['cara01bien_danza'] = $fila['cara01bien_danza'];
		$_REQUEST['cara01bien_musica'] = $fila['cara01bien_musica'];
		$_REQUEST['cara01bien_circo'] = $fila['cara01bien_circo'];
		$_REQUEST['cara01bien_artplast'] = $fila['cara01bien_artplast'];
		$_REQUEST['cara01bien_cuenteria'] = $fila['cara01bien_cuenteria'];
		$_REQUEST['cara01bien_interesreparte'] = $fila['cara01bien_interesreparte'];
		$_REQUEST['cara01bien_arteint'] = $fila['cara01bien_arteint'];
		$_REQUEST['cara01bien_interpreta'] = $fila['cara01bien_interpreta'];
		$_REQUEST['cara01bien_nivelinter'] = $fila['cara01bien_nivelinter'];
		$_REQUEST['cara01bien_danza_mod'] = $fila['cara01bien_danza_mod'];
		$_REQUEST['cara01bien_danza_clas'] = $fila['cara01bien_danza_clas'];
		$_REQUEST['cara01bien_danza_cont'] = $fila['cara01bien_danza_cont'];
		$_REQUEST['cara01bien_danza_folk'] = $fila['cara01bien_danza_folk'];
		$_REQUEST['cara01bien_niveldanza'] = $fila['cara01bien_niveldanza'];
		$_REQUEST['cara01bien_emprendedor'] = $fila['cara01bien_emprendedor'];
		$_REQUEST['cara01bien_nombreemp'] = $fila['cara01bien_nombreemp'];
		$_REQUEST['cara01bien_capacempren'] = $fila['cara01bien_capacempren'];
		$_REQUEST['cara01bien_tipocapacita'] = $fila['cara01bien_tipocapacita'];
		$_REQUEST['cara01bien_impvidasalud'] = $fila['cara01bien_impvidasalud'];
		$_REQUEST['cara01bien_estraautocuid'] = $fila['cara01bien_estraautocuid'];
		$_REQUEST['cara01bien_pv_personal'] = $fila['cara01bien_pv_personal'];
		$_REQUEST['cara01bien_pv_familiar'] = $fila['cara01bien_pv_familiar'];
		$_REQUEST['cara01bien_pv_academ'] = $fila['cara01bien_pv_academ'];
		$_REQUEST['cara01bien_pv_labora'] = $fila['cara01bien_pv_labora'];
		$_REQUEST['cara01bien_pv_pareja'] = $fila['cara01bien_pv_pareja'];
		$_REQUEST['cara01bien_amb'] = $fila['cara01bien_amb'];
		$_REQUEST['cara01bien_amb_agu'] = $fila['cara01bien_amb_agu'];
		$_REQUEST['cara01bien_amb_bom'] = $fila['cara01bien_amb_bom'];
		$_REQUEST['cara01bien_amb_car'] = $fila['cara01bien_amb_car'];
		$_REQUEST['cara01bien_amb_info'] = $fila['cara01bien_amb_info'];
		$_REQUEST['cara01bien_amb_temas'] = $fila['cara01bien_amb_temas'];
		$_REQUEST['cara01psico_costoemocion'] = $fila['cara01psico_costoemocion'];
		$_REQUEST['cara01psico_reaccionimpre'] = $fila['cara01psico_reaccionimpre'];
		$_REQUEST['cara01psico_estres'] = $fila['cara01psico_estres'];
		$_REQUEST['cara01psico_pocotiempo'] = $fila['cara01psico_pocotiempo'];
		$_REQUEST['cara01psico_actitudvida'] = $fila['cara01psico_actitudvida'];
		$_REQUEST['cara01psico_duda'] = $fila['cara01psico_duda'];
		$_REQUEST['cara01psico_problemapers'] = $fila['cara01psico_problemapers'];
		$_REQUEST['cara01psico_satisfaccion'] = $fila['cara01psico_satisfaccion'];
		$_REQUEST['cara01psico_discusiones'] = $fila['cara01psico_discusiones'];
		$_REQUEST['cara01psico_atencion'] = $fila['cara01psico_atencion'];
		$_REQUEST['cara01niveldigital'] = $fila['cara01niveldigital'];
		$_REQUEST['cara01nivellectura'] = $fila['cara01nivellectura'];
		$_REQUEST['cara01nivelrazona'] = $fila['cara01nivelrazona'];
		$_REQUEST['cara01nivelingles'] = $fila['cara01nivelingles'];
		$_REQUEST['cara01idconsejero'] = $fila['cara01idconsejero'];
		$_REQUEST['cara01fechainicio'] = $fila['cara01fechainicio'];
		$_REQUEST['cara01telefono1'] = $fila['cara01telefono1'];
		$_REQUEST['cara01telefono2'] = $fila['cara01telefono2'];
		$_REQUEST['cara01correopersonal'] = $fila['cara01correopersonal'];
		$_REQUEST['cara01idprograma'] = $fila['cara01idprograma'];
		$_REQUEST['cara01idescuela'] = $fila['cara01idescuela'];
		$_REQUEST['cara01fichabiolog'] = $fila['cara01fichabiolog'];
		$_REQUEST['cara01nivelbiolog'] = $fila['cara01nivelbiolog'];
		$_REQUEST['cara01fichafisica'] = $fila['cara01fichafisica'];
		$_REQUEST['cara01nivelfisica'] = $fila['cara01nivelfisica'];
		$_REQUEST['cara01fichaquimica'] = $fila['cara01fichaquimica'];
		$_REQUEST['cara01nivelquimica'] = $fila['cara01nivelquimica'];
		$_REQUEST['cara01fichaciudad'] = $fila['cara01fichaciudad'];
		$_REQUEST['cara01nivelciudad'] = $fila['cara01nivelciudad'];
		$_REQUEST['cara01tipocaracterizacion'] = $fila['cara01tipocaracterizacion'];
		$_REQUEST['cara01perayuda'] = $fila['cara01perayuda'];
		$_REQUEST['cara01perotraayuda'] = $fila['cara01perotraayuda'];
		$_REQUEST['cara01discsensorialotra'] = $fila['cara01discsensorialotra'];
		$_REQUEST['cara01discfisicaotra'] = $fila['cara01discfisicaotra'];
		$_REQUEST['cara01disccognitivaotra'] = $fila['cara01disccognitivaotra'];
		$_REQUEST['cara01idcursocatedra'] = $fila['cara01idcursocatedra'];
		$_REQUEST['cara01idgrupocatedra'] = $fila['cara01idgrupocatedra'];
		$_REQUEST['cara01factordescper'] = $fila['cara01factordescper'];
		$_REQUEST['cara01factordescpsico'] = $fila['cara01factordescpsico'];
		$_REQUEST['cara01factordescinsti'] = $fila['cara01factordescinsti'];
		$_REQUEST['cara01factordescacad'] = $fila['cara01factordescacad'];
		$_REQUEST['cara01factordesc'] = $fila['cara01factordesc'];
		$_REQUEST['cara01criteriodesc'] = $fila['cara01criteriodesc'];
		$_REQUEST['cara01desertor'] = $fila['cara01desertor'];
		$_REQUEST['cara01factorprincipaldesc'] = $fila['cara01factorprincipaldesc'];
		$_REQUEST['cara01psico_puntaje'] = $fila['cara01psico_puntaje'];
		$_REQUEST['cara01discversion'] = $fila['cara01discversion'];
		$_REQUEST['cara01discv2sensorial'] = $fila['cara01discv2sensorial'];
		$_REQUEST['cara02discv2intelectura'] = $fila['cara02discv2intelectura'];
		$_REQUEST['cara02discv2fisica'] = $fila['cara02discv2fisica'];
		$_REQUEST['cara02discv2psico'] = $fila['cara02discv2psico'];
		$_REQUEST['cara02discv2sistemica'] = $fila['cara02discv2sistemica'];
		$_REQUEST['cara02discv2sistemicaotro'] = $fila['cara02discv2sistemicaotro'];
		$_REQUEST['cara02discv2multiple'] = $fila['cara02discv2multiple'];
		$_REQUEST['cara02discv2multipleotro'] = $fila['cara02discv2multipleotro'];
		$_REQUEST['cara02talentoexcepcional'] = $fila['cara02talentoexcepcional'];
		$_REQUEST['cara01discv2tiene'] = $fila['cara01discv2tiene'];
		$_REQUEST['cara01discv2trastaprende'] = $fila['cara01discv2trastaprende'];
		$_REQUEST['cara01discv2soporteorigen'] = $fila['cara01discv2soporteorigen'];
		$_REQUEST['cara01discv2archivoorigen'] = $fila['cara01discv2archivoorigen'];
		$_REQUEST['cara01discv2trastornos'] = $fila['cara01discv2trastornos'];
		$_REQUEST['cara01discv2contalento'] = $fila['cara01discv2contalento'];
		$_REQUEST['cara01discv2condicionmedica'] = $fila['cara01discv2condicionmedica'];
		$_REQUEST['cara01discv2condmeddet'] = $fila['cara01discv2condmeddet'];
		$_REQUEST['cara01discv2pruebacoeficiente'] = $fila['cara01discv2pruebacoeficiente'];
		$_REQUEST['cara44sexoversion'] = 0;
		$_REQUEST['cara44bienversion'] = 0;
		if ($idEntidad == 0) {
			if ($_REQUEST['cara01idperaca'] > 1142){
				$_REQUEST['cara44sexoversion'] = 1;
				$_REQUEST['cara44bienversion'] = 2;
			}
			if ($_REQUEST['cara01idperaca'] > 2034){
				$_REQUEST['cara44bienversion'] = 3;
			}
		}
		if ($bDebug){
			$sDebug = $sDebug . fecha_microtiempo() . ' Cargando datos Version '.$_REQUEST['cara44sexoversion'].'<br>';
		}
		$sSQLcondi = 'cara44id=' . $_REQUEST['cara01id'] . '';
		$sSQL = 'SELECT * FROM cara44encuesta WHERE ' . $sSQLcondi;
		if ($bDebug){
			$sDebug = $sDebug . fecha_microtiempo() . ' Cargando complemento '.$sSQL.'<br>';
		}
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila1 = $objDB->sf($tabla);
			$_REQUEST['cara44campesinado'] = $fila1['cara44campesinado'];
			$_REQUEST['cara44sexoversion'] = $fila1['cara44sexoversion'];
			$_REQUEST['cara44sexov1identidadgen'] = $fila1['cara44sexov1identidadgen'];
			$_REQUEST['cara44sexov1orientasexo'] = $fila1['cara44sexov1orientasexo'];
			$_REQUEST['cara44bienversion'] = $fila1['cara44bienversion'];
			$_REQUEST['cara44bienv2altoren'] = $fila1['cara44bienv2altoren'];
			$_REQUEST['cara44bienv2atletismo'] = $fila1['cara44bienv2atletismo'];
			$_REQUEST['cara44bienv2baloncesto'] = $fila1['cara44bienv2baloncesto'];
			$_REQUEST['cara44bienv2futbol'] = $fila1['cara44bienv2futbol'];
			$_REQUEST['cara44bienv2gimnasia'] = $fila1['cara44bienv2gimnasia'];
			$_REQUEST['cara44bienv2natacion'] = $fila1['cara44bienv2natacion'];
			$_REQUEST['cara44bienv2voleibol'] = $fila1['cara44bienv2voleibol'];
			$_REQUEST['cara44bienv2tenis'] = $fila1['cara44bienv2tenis'];
			$_REQUEST['cara44bienv2paralimpico'] = $fila1['cara44bienv2paralimpico'];
			$_REQUEST['cara44bienv2otrodeporte'] = $fila1['cara44bienv2otrodeporte'];
			$_REQUEST['cara44bienv2otrodeportedetalle'] = $fila1['cara44bienv2otrodeportedetalle'];
			$_REQUEST['cara44bienv2activdanza'] = $fila1['cara44bienv2activdanza'];
			$_REQUEST['cara44bienv2activmusica'] = $fila1['cara44bienv2activmusica'];
			$_REQUEST['cara44bienv2activteatro'] = $fila1['cara44bienv2activteatro'];
			$_REQUEST['cara44bienv2activartes'] = $fila1['cara44bienv2activartes'];
			$_REQUEST['cara44bienv2activliteratura'] = $fila1['cara44bienv2activliteratura'];
			$_REQUEST['cara44bienv2activculturalotra'] = $fila1['cara44bienv2activculturalotra'];
			$_REQUEST['cara44bienv2activculturalotradetalle'] = $fila1['cara44bienv2activculturalotradetalle'];
			$_REQUEST['cara44bienv2evenfestfolc'] = $fila1['cara44bienv2evenfestfolc'];
			$_REQUEST['cara44bienv2evenexpoarte'] = $fila1['cara44bienv2evenexpoarte'];
			$_REQUEST['cara44bienv2evenhistarte'] = $fila1['cara44bienv2evenhistarte'];
			$_REQUEST['cara44bienv2evengalfoto'] = $fila1['cara44bienv2evengalfoto'];
			$_REQUEST['cara44bienv2evenliteratura'] = $fila1['cara44bienv2evenliteratura'];
			$_REQUEST['cara44bienv2eventeatro'] = $fila1['cara44bienv2eventeatro'];
			$_REQUEST['cara44bienv2evencine'] = $fila1['cara44bienv2evencine'];
			$_REQUEST['cara44bienv2evenculturalotro'] = $fila1['cara44bienv2evenculturalotro'];
			$_REQUEST['cara44bienv2evenculturalotrodetalle'] = $fila1['cara44bienv2evenculturalotrodetalle'];
			$_REQUEST['cara44bienv2emprendimiento'] = $fila1['cara44bienv2emprendimiento'];
			$_REQUEST['cara44bienv2empresa'] = $fila1['cara44bienv2empresa'];
			$_REQUEST['cara44bienv2emprenrecursos'] = $fila1['cara44bienv2emprenrecursos'];
			$_REQUEST['cara44bienv2emprenconocim'] = $fila1['cara44bienv2emprenconocim'];
			$_REQUEST['cara44bienv2emprenplan'] = $fila1['cara44bienv2emprenplan'];
			$_REQUEST['cara44bienv2emprenejecutar'] = $fila1['cara44bienv2emprenejecutar'];
			$_REQUEST['cara44bienv2emprenfortconocim'] = $fila1['cara44bienv2emprenfortconocim'];
			$_REQUEST['cara44bienv2emprenidentproblema'] = $fila1['cara44bienv2emprenidentproblema'];
			$_REQUEST['cara44bienv2emprenotro'] = $fila1['cara44bienv2emprenotro'];
			$_REQUEST['cara44bienv2emprenotrodetalle'] = $fila1['cara44bienv2emprenotrodetalle'];
			$_REQUEST['cara44bienv2emprenmarketing'] = $fila1['cara44bienv2emprenmarketing'];
			$_REQUEST['cara44bienv2emprenplannegocios'] = $fila1['cara44bienv2emprenplannegocios'];
			$_REQUEST['cara44bienv2emprenideas'] = $fila1['cara44bienv2emprenideas'];
			$_REQUEST['cara44bienv2emprencreacion'] = $fila1['cara44bienv2emprencreacion'];
			$_REQUEST['cara44bienv2saludfacteconom'] = $fila1['cara44bienv2saludfacteconom'];
			$_REQUEST['cara44bienv2saludpreocupacion'] = $fila1['cara44bienv2saludpreocupacion'];
			$_REQUEST['cara44bienv2saludconsumosust'] = $fila1['cara44bienv2saludconsumosust'];
			$_REQUEST['cara44bienv2saludinsomnio'] = $fila1['cara44bienv2saludinsomnio'];
			$_REQUEST['cara44bienv2saludclimalab'] = $fila1['cara44bienv2saludclimalab'];
			$_REQUEST['cara44bienv2saludalimenta'] = $fila1['cara44bienv2saludalimenta'];
			$_REQUEST['cara44bienv2saludemocion'] = $fila1['cara44bienv2saludemocion'];
			$_REQUEST['cara44bienv2saludestado'] = $fila1['cara44bienv2saludestado'];
			$_REQUEST['cara44bienv2saludmedita'] = $fila1['cara44bienv2saludmedita'];
			$_REQUEST['cara44bienv2crecimedusexual'] = $fila1['cara44bienv2crecimedusexual'];
			$_REQUEST['cara44bienv2crecimcultciudad'] = $fila1['cara44bienv2crecimcultciudad'];
			$_REQUEST['cara44bienv2crecimrelpareja'] = $fila1['cara44bienv2crecimrelpareja'];
			$_REQUEST['cara44bienv2crecimrelinterp'] = $fila1['cara44bienv2crecimrelinterp'];
			$_REQUEST['cara44bienv2crecimdinamicafam'] = $fila1['cara44bienv2crecimdinamicafam'];
			$_REQUEST['cara44bienv2crecimautoestima'] = $fila1['cara44bienv2crecimautoestima'];
			$_REQUEST['cara44bienv2creciminclusion'] = $fila1['cara44bienv2creciminclusion'];
			$_REQUEST['cara44bienv2creciminteliemoc'] = $fila1['cara44bienv2creciminteliemoc'];
			$_REQUEST['cara44bienv2crecimcultural'] = $fila1['cara44bienv2crecimcultural'];
			$_REQUEST['cara44bienv2crecimartistico'] = $fila1['cara44bienv2crecimartistico'];
			$_REQUEST['cara44bienv2crecimdeporte'] = $fila1['cara44bienv2crecimdeporte'];
			$_REQUEST['cara44bienv2crecimambiente'] = $fila1['cara44bienv2crecimambiente'];
			$_REQUEST['cara44bienv2crecimhabsocio'] = $fila1['cara44bienv2crecimhabsocio'];
			$_REQUEST['cara44bienv2ambienbasura'] = $fila1['cara44bienv2ambienbasura'];
			$_REQUEST['cara44bienv2ambienreutiliza'] = $fila1['cara44bienv2ambienreutiliza'];
			$_REQUEST['cara44bienv2ambienluces'] = $fila1['cara44bienv2ambienluces'];
			$_REQUEST['cara44bienv2ambienfrutaverd'] = $fila1['cara44bienv2ambienfrutaverd'];
			$_REQUEST['cara44bienv2ambienenchufa'] = $fila1['cara44bienv2ambienenchufa'];
			$_REQUEST['cara44bienv2ambiengrifo'] = $fila1['cara44bienv2ambiengrifo'];
			$_REQUEST['cara44bienv2ambienbicicleta'] = $fila1['cara44bienv2ambienbicicleta'];
			$_REQUEST['cara44bienv2ambientranspub'] = $fila1['cara44bienv2ambientranspub'];
			$_REQUEST['cara44bienv2ambienducha'] = $fila1['cara44bienv2ambienducha'];
			$_REQUEST['cara44bienv2ambiencaminata'] = $fila1['cara44bienv2ambiencaminata'];
			$_REQUEST['cara44bienv2ambiensiembra'] = $fila1['cara44bienv2ambiensiembra'];
			$_REQUEST['cara44bienv2ambienconferencia'] = $fila1['cara44bienv2ambienconferencia'];
			$_REQUEST['cara44bienv2ambienrecicla'] = $fila1['cara44bienv2ambienrecicla'];
			$_REQUEST['cara44bienv2ambienotraactiv'] = $fila1['cara44bienv2ambienotraactiv'];
			$_REQUEST['cara44bienv2ambienotraactivdetalle'] = $fila1['cara44bienv2ambienotraactivdetalle'];
			$_REQUEST['cara44bienv2ambienreforest'] = $fila1['cara44bienv2ambienreforest'];
			$_REQUEST['cara44bienv2ambienmovilidad'] = $fila1['cara44bienv2ambienmovilidad'];
			$_REQUEST['cara44bienv2ambienclimatico'] = $fila1['cara44bienv2ambienclimatico'];
			$_REQUEST['cara44bienv2ambienecofemin'] = $fila1['cara44bienv2ambienecofemin'];
			$_REQUEST['cara44bienv2ambienbiodiver'] = $fila1['cara44bienv2ambienbiodiver'];
			$_REQUEST['cara44bienv2ambienecologia'] = $fila1['cara44bienv2ambienecologia'];
			$_REQUEST['cara44bienv2ambieneconomia'] = $fila1['cara44bienv2ambieneconomia'];
			$_REQUEST['cara44bienv2ambienrecnatura'] = $fila1['cara44bienv2ambienrecnatura'];
			$_REQUEST['cara44bienv2ambienreciclaje'] = $fila1['cara44bienv2ambienreciclaje'];
			$_REQUEST['cara44bienv2ambienmascota'] = $fila1['cara44bienv2ambienmascota'];
			$_REQUEST['cara44bienv2ambiencartohum'] = $fila1['cara44bienv2ambiencartohum'];
			$_REQUEST['cara44bienv2ambienespiritu'] = $fila1['cara44bienv2ambienespiritu'];
			$_REQUEST['cara44bienv2ambiencarga'] = $fila1['cara44bienv2ambiencarga'];
			$_REQUEST['cara44bienv2ambienotroenfoq'] = $fila1['cara44bienv2ambienotroenfoq'];
			$_REQUEST['cara44bienv2ambienotroenfoqdetalle'] = $fila1['cara44bienv2ambienotroenfoqdetalle'];
			$_REQUEST['cara44fam_madrecabeza'] = $fila1['cara44fam_madrecabeza'];
			$_REQUEST['cara44acadhatenidorecesos'] = $fila1['cara44acadhatenidorecesos'];
			$_REQUEST['cara44acadrazonreceso'] = $fila1['cara44acadrazonreceso'];
			$_REQUEST['cara44acadrazonrecesodetalle'] = $fila1['cara44acadrazonrecesodetalle'];
			$_REQUEST['cara44campus_usocorreounad'] = $fila1['cara44campus_usocorreounad'];
			$_REQUEST['cara44campus_usocorreounadno'] = $fila1['cara44campus_usocorreounadno'];
			$_REQUEST['cara44campus_usocorreounadnodetalle'] = $fila1['cara44campus_usocorreounadnodetalle'];
			$_REQUEST['cara44campus_medioactivunad'] = $fila1['cara44campus_medioactivunad'];
			$_REQUEST['cara44campus_medioactivunaddetalle'] = $fila1['cara44campus_medioactivunaddetalle'];
			$_REQUEST['cara44frontera'] = $fila1['cara44frontera'];
			$_REQUEST['cara44med_tratamiento'] = $fila1['cara44med_tratamiento'];
			$_REQUEST['cara44med_trat_cual'] = $fila1['cara44med_trat_cual'];
			$_REQUEST['cara44bienv3emprenetapa'] = $fila1['cara44bienv3emprenetapa'];
			$_REQUEST['cara44bienv3emprennecesita'] = $fila1['cara44bienv3emprennecesita'];
			$_REQUEST['cara44bienv3emprenanioini'] = $fila1['cara44bienv3emprenanioini'];
			$_REQUEST['cara44bienv3emprensector'] = $fila1['cara44bienv3emprensector'];
			$_REQUEST['cara44bienv3emprensectorotro'] = $fila1['cara44bienv3emprensectorotro'];
			$_REQUEST['cara44bienv3emprentemas'] = $fila1['cara44bienv3emprentemas'];
			$_REQUEST['cara44bienv3ambienclima'] = $fila1['cara44bienv3ambienclima'];
			$_REQUEST['cara44bienv3ambienjusticia'] = $fila1['cara44bienv3ambienjusticia'];
			$_REQUEST['cara44bienv3ambienagroeco'] = $fila1['cara44bienv3ambienagroeco'];
			$_REQUEST['cara44bienv3ambieneconomia'] = $fila1['cara44bienv3ambieneconomia'];
			$_REQUEST['cara44bienv3ambieneducacion'] = $fila1['cara44bienv3ambieneducacion'];
			$_REQUEST['cara44bienv3ambienbiodiverso'] = $fila1['cara44bienv3ambienbiodiverso'];
			$_REQUEST['cara44bienv3ambienecoturismo'] = $fila1['cara44bienv3ambienecoturismo'];
			$_REQUEST['cara44bienv3ambienotro'] = $fila1['cara44bienv3ambienotro'];
			$_REQUEST['cara44bienv3ambienotrodetalle'] = $fila1['cara44bienv3ambienotrodetalle'];
			$_REQUEST['cara44bienv3ambienexper'] = $fila1['cara44bienv3ambienexper'];
			$_REQUEST['cara44bienv3ambienaprende'] = $fila1['cara44bienv3ambienaprende'];
			$_REQUEST['cara44bienv3ambienestudiante'] = $fila1['cara44bienv3ambienestudiante'];
			$_REQUEST['cara44bienv3ambienactividad'] = $fila1['cara44bienv3ambienactividad'];
			$_REQUEST['cara44bienv3pyphabitoalim'] = $fila1['cara44bienv3pyphabitoalim'];
			$_REQUEST['cara44bienv3pypsustanciapsico'] = $fila1['cara44bienv3pypsustanciapsico'];
			$_REQUEST['cara44bienv3pypsaludvisual'] = $fila1['cara44bienv3pypsaludvisual'];
			$_REQUEST['cara44bienv3pypsaludbucal'] = $fila1['cara44bienv3pypsaludbucal'];
			$_REQUEST['cara44bienv3pypsaludsexual'] = $fila1['cara44bienv3pypsaludsexual'];
			$_REQUEST['cara44bienv3deportenivel'] = $fila1['cara44bienv3deportenivel'];
			$_REQUEST['cara44bienv3deportefrec'] = $fila1['cara44bienv3deportefrec'];
			$_REQUEST['cara44bienv3deportecual'] = $fila1['cara44bienv3deportecual'];
			$_REQUEST['cara44bienv3deportecualotro'] = $fila1['cara44bienv3deportecualotro'];
			$_REQUEST['cara44bienv3deporterecrea'] = $fila1['cara44bienv3deporterecrea'];
			$_REQUEST['cara44bienv3deporteunad'] = $fila1['cara44bienv3deporteunad'];
			$_REQUEST['cara44bienv3creciminclusion'] = $fila1['cara44bienv3creciminclusion'];
			$_REQUEST['cara44bienv3crecimfamilia'] = $fila1['cara44bienv3crecimfamilia'];
			$_REQUEST['cara44bienv3crecimhabilidad'] = $fila1['cara44bienv3crecimhabilidad'];
			$_REQUEST['cara44bienv3crecimempleable'] = $fila1['cara44bienv3crecimempleable'];
			$_REQUEST['cara44bienv3crecimhabilvida'] = $fila1['cara44bienv3crecimhabilvida'];
			$_REQUEST['cara44bienv3crecimespiritual'] = $fila1['cara44bienv3crecimespiritual'];
			$_REQUEST['cara44bienv3crecimpractica'] = $fila1['cara44bienv3crecimpractica'];
			$_REQUEST['cara44bienv3crecimliderazgo'] = $fila1['cara44bienv3crecimliderazgo'];
			$_REQUEST['cara44bienv3crecimtrabequipo'] = $fila1['cara44bienv3crecimtrabequipo'];
			$_REQUEST['cara44bienv3crecimasertiva'] = $fila1['cara44bienv3crecimasertiva'];
			$_REQUEST['cara44bienv3crecimgesttiempo'] = $fila1['cara44bienv3crecimgesttiempo'];
			$_REQUEST['cara44bienv3crecimconflictos'] = $fila1['cara44bienv3crecimconflictos'];
			$_REQUEST['cara44bienv3crecimadapcambio'] = $fila1['cara44bienv3crecimadapcambio'];
			$_REQUEST['cara44bienv3crecimempatia'] = $fila1['cara44bienv3crecimempatia'];
			$_REQUEST['cara44bienv3crecimgestionser'] = $fila1['cara44bienv3crecimgestionser'];
			$_REQUEST['cara44bienv3crecimtomadecide'] = $fila1['cara44bienv3crecimtomadecide'];
			$_REQUEST['cara44bienv3crecimpenscreativo'] = $fila1['cara44bienv3crecimpenscreativo'];
			$_REQUEST['cara44bienv3crecimpenscritico'] = $fila1['cara44bienv3crecimpenscritico'];
			$_REQUEST['cara44bienv3crecimhabilotro'] = $fila1['cara44bienv3crecimhabilotro'];
			$_REQUEST['cara44bienv3crecimhabilotrodetalle'] = $fila1['cara44bienv3crecimhabilotrodetalle'];
			$_REQUEST['cara44bienv3crecimalcancemeta'] = $fila1['cara44bienv3crecimalcancemeta'];
			$_REQUEST['cara44bienv3crecimsatifpersonal'] = $fila1['cara44bienv3crecimsatifpersonal'];
			$_REQUEST['cara44bienv3crecimaccesolaboral'] = $fila1['cara44bienv3crecimaccesolaboral'];
			$_REQUEST['cara44bienv3crecimotramotiv'] = $fila1['cara44bienv3crecimotramotiv'];
			$_REQUEST['cara44bienv3crecimotramotivdetalle'] = $fila1['cara44bienv3crecimotramotivdetalle'];
			$_REQUEST['cara44bienv3crecimapoyo'] = $fila1['cara44bienv3crecimapoyo'];
			$_REQUEST['cara44bienv3crecimlaboral'] = $fila1['cara44bienv3crecimlaboral'];
			$_REQUEST['cara44bienv3mentalcuidado'] = $fila1['cara44bienv3mentalcuidado'];
			$_REQUEST['cara44bienv3mentalestrategia'] = $fila1['cara44bienv3mentalestrategia'];
			$_REQUEST['cara44bienv3mentalestres'] = $fila1['cara44bienv3mentalestres'];
			$_REQUEST['cara44bienv3mentalansiedad'] = $fila1['cara44bienv3mentalansiedad'];
			$_REQUEST['cara44bienv3mentaldepresion'] = $fila1['cara44bienv3mentaldepresion'];
			$_REQUEST['cara44bienv3mentalautoconoc'] = $fila1['cara44bienv3mentalautoconoc'];
			$_REQUEST['cara44bienv3mentalmindfulness'] = $fila1['cara44bienv3mentalmindfulness'];
			$_REQUEST['cara44bienv3mentalautoestima'] = $fila1['cara44bienv3mentalautoestima'];
			$_REQUEST['cara44bienv3mentalcrisis'] = $fila1['cara44bienv3mentalcrisis'];
			$_REQUEST['cara44bienv3mentalburnout'] = $fila1['cara44bienv3mentalburnout'];
			$_REQUEST['cara44bienv3mentalsexualidad'] = $fila1['cara44bienv3mentalsexualidad'];
			$_REQUEST['cara44bienv3mentalusoredes'] = $fila1['cara44bienv3mentalusoredes'];
			$_REQUEST['cara44bienv3mentalinclusion'] = $fila1['cara44bienv3mentalinclusion'];
			$_REQUEST['cara44bienv3mentalactividad'] = $fila1['cara44bienv3mentalactividad'];
			$_REQUEST['cara44bienv3mentalacompana'] = $fila1['cara44bienv3mentalacompana'];
			$_REQUEST['cara44bienv3mentaldiagnostico'] = $fila1['cara44bienv3mentaldiagnostico'];
			$_REQUEST['cara44bienv3mentaldiagcual'] = $fila1['cara44bienv3mentaldiagcual'];
			$_REQUEST['cara44bienv3mentaldiagotro'] = $fila1['cara44bienv3mentaldiagotro'];
			$_REQUEST['cara44bienv3arteintegrar'] = $fila1['cara44bienv3arteintegrar'];
			$_REQUEST['cara44bienv3arteformacion'] = $fila1['cara44bienv3arteformacion'];
			$_REQUEST['cara44bienv3arteunad'] = $fila1['cara44bienv3arteunad'];
			$_REQUEST['cara44bienv3arteinformacion'] = $fila1['cara44bienv3arteinformacion'];
		} else {
			if ($_REQUEST['cara44sexoversion'] == 1) {
				//Insertamos el registro inicial.
				$sSQL= 'INSERT INTO cara44encuesta (cara44id, cara44sexoversion, cara44bienversion) VALUES (' . $_REQUEST['cara01id'] . ', 1, 2)';
				if ($bDebug){
					$sDebug = $sDebug . fecha_microtiempo() . ' Insertando complemento: '.$sSQL.'<br>';
				}
				$result = $objDB->ejecutasql($sSQL);
			}
		}
		$bcargo = true;
		$_REQUEST['paso'] = 2;
		$_REQUEST['boculta2301'] = 0;
		if (!$bEstudiante) {
			$_REQUEST['boculta101'] = $fila['cara01fichaper'];
			$_REQUEST['boculta102'] = $fila['cara01fichafam'];
			$_REQUEST['boculta103'] = $fila['cara01fichaaca'];
			$_REQUEST['boculta104'] = $fila['cara01fichalab'];
			$_REQUEST['boculta105'] = $fila['cara01fichabien'];
			$_REQUEST['boculta106'] = $fila['cara01fichapsico'];
			$_REQUEST['boculta107'] = $fila['cara01fichadigital'];
			$_REQUEST['boculta108'] = $fila['cara01fichalectura'];
			$_REQUEST['boculta109'] = $fila['cara01ficharazona'];
			$_REQUEST['boculta110'] = $fila['cara01fichaingles'];
			$_REQUEST['boculta111'] = $fila['cara01fichabiolog'];
			$_REQUEST['boculta112'] = $fila['cara01fichafisica'];
			$_REQUEST['boculta113'] = $fila['cara01fichaquimica'];
			$_REQUEST['boculta114'] = $fila['cara01fichaciudad'];
			$bDiscapacitado = false;
			if ($_REQUEST['cara01discsensorial'] != 'N') {
				$bDiscapacitado = true;
			}
			if ($_REQUEST['cara01discfisica'] != 'N') {
				$bDiscapacitado = true;
			}
			if ($_REQUEST['cara01disccognitiva'] != 'N') {
				$bDiscapacitado = true;
			}
			if ($_REQUEST['cara01perayuda'] != 0) {
				$bDiscapacitado = true;
			}
			if ($_REQUEST['cara01fechaconfirmadisc'] == 0) {
				if ($bDiscapacitado) {
					$_REQUEST['boculta101'] = 0;
				}
			}
		} else {
		}
		$bLimpiaHijos = true;
		if ($_REQUEST['cara01agnos'] <= 0) {
			$bActualizarEdad = true;
		}
	} else {
		if ($bEstudiante) {
			$_REQUEST['paso'] = -1;
		} else {
			$_REQUEST['paso'] = 0;
		}
	}
}
if ($bDebug) {
	//echo 'Termina Bloque de cargue de del registro <br>';
}
//Actualizar la edad en la encuesta
if ($bActualizarEdad) {
	$sSQL = 'SELECT unad11fechanace FROM unad11terceros WHERE unad11id =' . $_REQUEST['cara01idtercero'] . '';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		if (fecha_esvalida($fila['unad11fechanace'])) {
			list($cara01agnos, $iMedida) = fecha_edad($fila['unad11fechanace']);
			$_REQUEST['cara01agnos'] = $cara01agnos;
			$sSQL = 'UPDATE cara01encuesta SET cara01agnos="' . $_REQUEST['cara01agnos'] . '" WHERE cara01id=' . $_REQUEST['cara01id'] . '';
			$tabla = $objDB->ejecutasql($sSQL);
		}
	}
}
//Cerrar
$bCerrando = false;
if ($_REQUEST['paso'] == 16) {
	$_REQUEST['paso'] = 12;
	$_REQUEST['cara01completa'] = 'S';
	$_REQUEST['cara01fechaencuesta'] = fecha_DiaMod();
	$bCerrando = true;
}
//Abrir
if ($_REQUEST['paso'] == 17) {
	$_REQUEST['paso'] = 2;
	//Es posible que deba definir el codigo de permiso para abrir.
	if (!seg_revisa_permiso($iCodModulo, 17, $objDB)) {
		$sError = $ERR['3'];
	}
	//Otras restricciones para abrir.
	if ($sError == '') {
		//$sError='Motivo por el que no se pueda abrir, no se permite modificar.';
	}
	if ($sError != '') {
		$_REQUEST['cara01completa'] = 'S';
	} else {
		$sSQL = 'UPDATE cara01encuesta SET cara01completa="N" WHERE cara01id=' . $_REQUEST['cara01id'] . '';
		$tabla = $objDB->ejecutasql($sSQL);
		seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 3, $_REQUEST['cara01id'], 'Abre Encuesta', $objDB);
		$_REQUEST['cara01completa'] = 'N';
		$sError = '<b>El documento ha sido abierto</b>';
		$iTipoError = 1;
	}
}
//Insertar o modificar un elemento
function SiguienteFicha($DATA)
{
	$iRes = 1;
	if ($DATA['ficha'] == 13) {
		$iRes = 1;
	} else {
		$iRes = $DATA['ficha'] + 1;
		if ($iRes == 2) {
			if ($DATA['cara01fichafam'] == -1) {
				$iRes = 7;
			}
		}
		if ($iRes == 3) {
			if ($DATA['cara01fichaaca'] == -1) {
				$iRes = 7;
			}
		}
		if ($iRes == 4) {
			if ($DATA['cara01fichalab'] == -1) {
				$iRes = 7;
			}
		}
		if ($iRes == 5) {
			if ($DATA['cara01fichabien'] == -1) {
				$iRes = 7;
			}
		}
		if ($iRes == 6) {
			if ($DATA['cara01fichapsico'] == -1) {
				$iRes = 7;
			}
		}
		if ($iRes == 7) {
			if ($DATA['cara01fichadigital'] == -1) {
				$iRes = 8;
			}
		}
		if ($iRes == 8) {
			if ($DATA['cara01fichalectura'] == -1) {
				$iRes = 9;
			}
		}
		if ($iRes == 9) {
			if ($DATA['cara01ficharazona'] == -1) {
				$iRes = 10;
			}
		}
		if ($iRes == 10) {
			if ($DATA['cara01fichaingles'] == -1) {
				$iRes = 11;
			}
		}
		if ($iRes == 11) {
			if ($DATA['cara01fichabiolog'] == -1) {
				$iRes = 12;
			}
		}
		if ($iRes == 12) {
			if ($DATA['cara01fichafisica'] == -1) {
				$iRes = 13;
			}
		}
		if ($iRes == 13) {
			if ($DATA['cara01fichaquimica'] == -1) {
				$iRes = 14;
			}
		}
		if ($iRes == 14) {
			if ($DATA['cara01fichaciudad'] == -1) {
				$iRes = 1;
			}
		}
	}
	return $iRes;
}
function html_2201ContinuarCerrar($id, $objForma)
{	
	$sRes = '';
	$sRes = $sRes . '<div class="salto1px"></div>';
	$sRes = $sRes . '<label class="Label300">&nbsp;</label>';
	$sRes = $sRes . $objForma->htmlBotonSolo('cmdContinuar' . $id, 'botonContinuar azul', 'enviaguardar()', 'Continuar', 130);
	$sRes = $sRes . '<label class="Label60">&nbsp;</label>';
	$sRes = $sRes . $objForma->htmlBotonSolo('cmdCerrar' . $id, 'botonTerminar azul', 'enviacerrar()', 'Terminar', 130);
	return $sRes;
}
function html_2201Tablero($id, $objForma)
{
	$sRes = '';
	$sRes = $sRes . '<div class="salto1px"></div>';
	$sRes = $sRes . '<label class="Label300">&nbsp;</label>';
	$sRes = $sRes . $objForma->htmlBotonSolo('cmdTablero' . $id, 'btSupVolver azul', 'irtablero()', 'Mis Cursos', 130);
	return $sRes;
}
if ($bDebug) {
	$sDebug = $sDebug . fecha_microtiempo() . ' Corriendo el paso ' . $_REQUEST['paso'] . ' antes de guardar<br>';
}
if (($_REQUEST['paso'] == 10) || ($_REQUEST['paso'] == 12)) {
	$bMueveScroll = true;
	list($_REQUEST, $sError, $iTipoError, $bCerrando, $sErrorCerrando, $sDebugGuardar) = f2301_db_GuardarV2($_REQUEST, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugGuardar;
	if ($sError == '') {
		$sError = '<b>' . $ETI['msg_itemguardado'] . '</b>';
		$iTipoError = 1;
		if (!$bCerrando) {
			$_REQUEST['ficha'] = SiguienteFicha($_REQUEST);
		}
		if ($sErrorCerrando != '') {
			$iTipoError = 0;
			$sError = '<b>' . $ETI['msg_itemguardado'] . '</b><br>' . $sErrorCerrando;
		}
		if ($bCerrando) {
			$sError = '<b>' . $ETI['msg_itemcerrado'] . '</b>';
		}
	}
}
if ($bCerrando) {
	//acciones del cerrado
}
//Eliminar un elemento
if ($_REQUEST['paso'] == 13) {
	$_REQUEST['paso'] = 2;
	list($sError, $iTipoError, $sDebugElimina) = f2301_db_Eliminar($_REQUEST['cara01id'], $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugElimina;
	if ($sError == '') {
		$_REQUEST['paso'] = -1;
		$sError = $ETI['msg_itemeliminado'];
		$iTipoError = 1;
	}
}
//Actualizar las preguntas, por alguna razon ha fallado o es la primer carga...
if ($_REQUEST['paso'] == 22) {
	$_REQUEST['paso'] = 2;
	list($sError, $sDebugP) = f2301_IniciarPreguntas($_REQUEST['cara01id'], $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugP;
	if ($sError == '') {
		$sError = 'Se ha actualizado las preguntas para la prueba';
		$iTipoError = 1;
	}
}
//Confirmar la discapacidad...
if ($_REQUEST['paso'] == 23) {
	require 'lib2301consejero.php';
	$_REQUEST['paso'] = 2;
	$bMueveScroll = true;
	list($_REQUEST, $sError, $iTipoError, $bCerrando, $sErrorCerrando, $sDebugGuardar) = f2301_db_GuardarDiscapacidad($_REQUEST, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugGuardar;
	if ($sError == '') {
		$sError = 'Se ha ejecutado la confirmacion de los datos de discapacidad';
		$iTipoError = 1;
	}
}
//Verificar los datos de la matricula .. Febrero 24 de 2022
if ($_REQUEST['paso'] == 31) {
	$_REQUEST['paso'] = -1;
	$bMueveScroll = true;
	if ($_REQUEST['bperiodo'] == '') {
		$sError = 'No se ha definido el periodo a verificar';
	}
	if ($sError == '') {
		require 'lib2301consejero.php';
		list($iProcesados, $sError, $sDebugV) = f2301_VerificarMatricula($_REQUEST['bperiodo'], $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugV;
	}
	if ($sError == '') {
		$sError = 'Se ha Verificado los datos de matricula';
		if ($iProcesados > 0) {
			$sError = $sError . ', Encuestas ajustadas: ' . formato_numero($iProcesados) . '';
		}
		$iTipoError = 1;
	}
}
//limpiar la pantalla
if ($_REQUEST['paso'] == -1) {
	$_REQUEST['cara01idperaca'] = '';
	if ($bEstudiante) {
		$_REQUEST['cara01idtercero'] = $idTercero;
	} else {
		$_REQUEST['cara01idtercero'] = 0;
	}
	$_REQUEST['cara01id'] = '';
	$_REQUEST['cara01idtercero_td'] = $APP->tipo_doc;
	$_REQUEST['cara01idtercero_doc'] = '';
	$_REQUEST['cara01completa'] = 'N';
	$_REQUEST['cara01fichaper'] = 0;
	$_REQUEST['cara01fichafam'] = -1;
	$_REQUEST['cara01fichaaca'] = -1;
	$_REQUEST['cara01fichalab'] = -1;
	$_REQUEST['cara01fichabien'] = -1;
	$_REQUEST['cara01fichapsico'] = -1;
	$_REQUEST['cara01fichadigital'] = -1;
	$_REQUEST['cara01fichalectura'] = -1;
	$_REQUEST['cara01ficharazona'] = -1;
	$_REQUEST['cara01fichaingles'] = -1;
	$_REQUEST['cara01fechaencuesta'] = ''; //fecha_hoy();
	$_REQUEST['cara01agnos'] = 0;
	$_REQUEST['cara01sexo'] = '';
	$_REQUEST['cara01pais'] = $_SESSION['unad_pais'];
	$_REQUEST['cara01depto'] = '';
	$_REQUEST['cara01ciudad'] = '';
	$_REQUEST['cara01nomciudad'] = '';
	$_REQUEST['cara01direccion'] = '';
	$_REQUEST['cara01estrato'] = '';
	$_REQUEST['cara01zonares'] = '';
	$_REQUEST['cara01estcivil'] = '';
	$_REQUEST['cara01nomcontacto'] = '';
	$_REQUEST['cara01parentezcocontacto'] = '';
	$_REQUEST['cara01celcontacto'] = '';
	$_REQUEST['cara01correocontacto'] = '';
	$_REQUEST['cara01idzona'] = '';
	$_REQUEST['cara01idcead'] = '';
	$_REQUEST['cara01matconvenio'] = 'N';
	$_REQUEST['cara01raizal'] = '';
	$_REQUEST['cara01palenquero'] = '';
	$_REQUEST['cara01afrocolombiano'] = '';
	$_REQUEST['cara01otracomunnegras'] = '';
	$_REQUEST['cara01rom'] = '';
	$_REQUEST['cara01indigenas'] = 0;
	$_REQUEST['cara01victimadesplazado'] = 'N';
	$_REQUEST['cara01idconfirmadesp'] = 0;
	$_REQUEST['cara01idconfirmadesp_td'] = $APP->tipo_doc;
	$_REQUEST['cara01idconfirmadesp_doc'] = '';
	$_REQUEST['cara01fechaconfirmadesp'] = ''; //fecha_hoy();
	$_REQUEST['cara01victimaacr'] = 'N';
	$_REQUEST['cara01idconfirmacr'] = 0;
	$_REQUEST['cara01idconfirmacr_td'] = $APP->tipo_doc;
	$_REQUEST['cara01idconfirmacr_doc'] = '';
	$_REQUEST['cara01fechaconfirmacr'] = ''; //fecha_hoy();
	$_REQUEST['cara01inpecfuncionario'] = 'N';
	$_REQUEST['cara01inpecrecluso'] = 'N';
	$_REQUEST['cara01inpectiempocondena'] = '';
	$_REQUEST['cara01centroreclusion'] = 0;
	$_REQUEST['cara01discsensorial'] = 'N';
	$_REQUEST['cara01discfisica'] = 'N';
	$_REQUEST['cara01disccognitiva'] = 'N';
	$_REQUEST['cara01idconfirmadisc'] = 0;
	$_REQUEST['cara01idconfirmadisc_td'] = $APP->tipo_doc;
	$_REQUEST['cara01idconfirmadisc_doc'] = '';
	$_REQUEST['cara01fechaconfirmadisc'] = 0; //fecha_hoy();
	$_REQUEST['cara01fam_tipovivienda'] = '';
	$_REQUEST['cara01fam_vivecon'] = '';
	$_REQUEST['cara01fam_numpersgrupofam'] = '';
	$_REQUEST['cara01fam_hijos'] = '';
	$_REQUEST['cara01fam_personasacargo'] = '';
	$_REQUEST['cara01fam_dependeecon'] = '';
	$_REQUEST['cara01fam_escolaridadpadre'] = '';
	$_REQUEST['cara01fam_escolaridadmadre'] = '';
	$_REQUEST['cara01fam_numhermanos'] = '';
	$_REQUEST['cara01fam_posicionherm'] = '';
	$_REQUEST['cara01fam_familiaunad'] = '';
	$_REQUEST['cara01acad_tipocolegio'] = '';
	$_REQUEST['cara01acad_modalidadbach'] = '';
	$_REQUEST['cara01acad_estudioprev'] = '';
	$_REQUEST['cara01acad_ultnivelest'] = '';
	$_REQUEST['cara01acad_obtubodiploma'] = '';
	$_REQUEST['cara01acad_hatomadovirtual'] = '';
	$_REQUEST['cara01acad_tiemposinest'] = '';
	$_REQUEST['cara01acad_razonestudio'] = '';
	$_REQUEST['cara01acad_primeraopc'] = '';
	$_REQUEST['cara01acad_programagusto'] = '';
	$_REQUEST['cara01acad_razonunad'] = '';
	$_REQUEST['cara01campus_compescrito'] = '';
	$_REQUEST['cara01campus_portatil'] = '';
	$_REQUEST['cara01campus_tableta'] = '';
	$_REQUEST['cara01campus_telefono'] = '';
	$_REQUEST['cara01campus_energia'] = '';
	$_REQUEST['cara01campus_internetreside'] = '';
	$_REQUEST['cara01campus_expvirtual'] = '';
	$_REQUEST['cara01campus_ofimatica'] = '';
	$_REQUEST['cara01campus_foros'] = '';
	$_REQUEST['cara01campus_conversiones'] = '';
	$_REQUEST['cara01campus_usocorreo'] = '';
	$_REQUEST['cara01campus_aprendtexto'] = '';
	$_REQUEST['cara01campus_aprendvideo'] = '';
	$_REQUEST['cara01campus_aprendmapas'] = '';
	$_REQUEST['cara01campus_aprendeanima'] = '';
	$_REQUEST['cara01campus_mediocomunica'] = '';
	$_REQUEST['cara01lab_situacion'] = '';
	$_REQUEST['cara01lab_sector'] = '';
	$_REQUEST['cara01lab_caracterjuri'] = '';
	$_REQUEST['cara01lab_cargo'] = '';
	$_REQUEST['cara01lab_antiguedad'] = '';
	$_REQUEST['cara01lab_tipocontrato'] = '';
	$_REQUEST['cara01lab_rangoingreso'] = '';
	$_REQUEST['cara01lab_tiempoacadem'] = '';
	$_REQUEST['cara01lab_tipoempresa'] = '';
	$_REQUEST['cara01lab_tiempoindepen'] = '';
	$_REQUEST['cara01lab_debebusctrab'] = '';
	$_REQUEST['cara01lab_origendinero'] = '';
	$_REQUEST['cara01bien_baloncesto'] = '';
	$_REQUEST['cara01bien_voleibol'] = '';
	$_REQUEST['cara01bien_futbolsala'] = '';
	$_REQUEST['cara01bien_artesmarc'] = '';
	$_REQUEST['cara01bien_tenisdemesa'] = '';
	$_REQUEST['cara01bien_ajedrez'] = '';
	$_REQUEST['cara01bien_juegosautoc'] = '';
	$_REQUEST['cara01bien_interesrepdeporte'] = '';
	$_REQUEST['cara01bien_deporteint'] = '';
	$_REQUEST['cara01bien_teatro'] = '';
	$_REQUEST['cara01bien_danza'] = '';
	$_REQUEST['cara01bien_musica'] = '';
	$_REQUEST['cara01bien_circo'] = '';
	$_REQUEST['cara01bien_artplast'] = '';
	$_REQUEST['cara01bien_cuenteria'] = '';
	$_REQUEST['cara01bien_interesreparte'] = '';
	$_REQUEST['cara01bien_arteint'] = '';
	$_REQUEST['cara01bien_interpreta'] = '';
	$_REQUEST['cara01bien_nivelinter'] = '';
	$_REQUEST['cara01bien_danza_mod'] = '';
	$_REQUEST['cara01bien_danza_clas'] = '';
	$_REQUEST['cara01bien_danza_cont'] = '';
	$_REQUEST['cara01bien_danza_folk'] = '';
	$_REQUEST['cara01bien_niveldanza'] = '';
	$_REQUEST['cara01bien_emprendedor'] = '';
	$_REQUEST['cara01bien_nombreemp'] = '';
	$_REQUEST['cara01bien_capacempren'] = '';
	$_REQUEST['cara01bien_tipocapacita'] = '';
	$_REQUEST['cara01bien_impvidasalud'] = '';
	$_REQUEST['cara01bien_estraautocuid'] = '';
	$_REQUEST['cara01bien_pv_personal'] = '';
	$_REQUEST['cara01bien_pv_familiar'] = '';
	$_REQUEST['cara01bien_pv_academ'] = '';
	$_REQUEST['cara01bien_pv_labora'] = '';
	$_REQUEST['cara01bien_pv_pareja'] = '';
	$_REQUEST['cara01bien_amb'] = '';
	$_REQUEST['cara01bien_amb_agu'] = '';
	$_REQUEST['cara01bien_amb_bom'] = '';
	$_REQUEST['cara01bien_amb_car'] = '';
	$_REQUEST['cara01bien_amb_info'] = '';
	$_REQUEST['cara01bien_amb_temas'] = '';
	$_REQUEST['cara01psico_costoemocion'] = '';
	$_REQUEST['cara01psico_reaccionimpre'] = '';
	$_REQUEST['cara01psico_estres'] = '';
	$_REQUEST['cara01psico_pocotiempo'] = '';
	$_REQUEST['cara01psico_actitudvida'] = '';
	$_REQUEST['cara01psico_duda'] = '';
	$_REQUEST['cara01psico_problemapers'] = '';
	$_REQUEST['cara01psico_satisfaccion'] = '';
	$_REQUEST['cara01psico_discusiones'] = '';
	$_REQUEST['cara01psico_atencion'] = '';
	$_REQUEST['cara01niveldigital'] = '';
	$_REQUEST['cara01nivellectura'] = '';
	$_REQUEST['cara01nivelrazona'] = '';
	$_REQUEST['cara01nivelingles'] = '';
	$_REQUEST['cara01idconsejero'] = 0;
	$_REQUEST['cara01idconsejero_td'] = $APP->tipo_doc;
	$_REQUEST['cara01idconsejero_doc'] = '';
	$_REQUEST['cara01fechainicio'] = ''; //fecha_hoy();
	$_REQUEST['cara01telefono1'] = '';
	$_REQUEST['cara01telefono2'] = '';
	$_REQUEST['cara01correopersonal'] = '';
	$_REQUEST['cara01idprograma'] = 0;
	$_REQUEST['cara01idescuela'] = 0;
	$_REQUEST['cara01fichabiolog'] = -1;
	$_REQUEST['cara01nivelbiolog'] = 0;
	$_REQUEST['cara01fichafisica'] = -1;
	$_REQUEST['cara01nivelfisica'] = 0;
	$_REQUEST['cara01fichaquimica'] = -1;
	$_REQUEST['cara01nivelquimica'] = 0;
	$_REQUEST['cara01fichaciudad'] = -1;
	$_REQUEST['cara01nivelciudad'] = 0;
	$_REQUEST['cara01tipocaracterizacion'] = 0;
	$_REQUEST['cara01perayuda'] = '';
	$_REQUEST['cara01perotraayuda'] = '';
	$_REQUEST['cara01discsensorialotra'] = '';
	$_REQUEST['cara01discfisicaotra'] = '';
	$_REQUEST['cara01disccognitivaotra'] = '';
	$_REQUEST['cara01idcursocatedra'] = 0;
	$_REQUEST['cara01idgrupocatedra'] = 0;
	$_REQUEST['cara01factordescper'] = 0;
	$_REQUEST['cara01factordescpsico'] = 0;
	$_REQUEST['cara01factordescinsti'] = 0;
	$_REQUEST['cara01factordescacad'] = 0;
	$_REQUEST['cara01factordesc'] = 0;
	$_REQUEST['cara01criteriodesc'] = 0;
	$_REQUEST['cara01desertor'] = 'N';
	$_REQUEST['cara01factorprincipaldesc'] = 0;
	$_REQUEST['cara01psico_puntaje'] = 0;
	$_REQUEST['cara01discversion'] = 2;
	$_REQUEST['cara01discv2sensorial'] = '';
	$_REQUEST['cara02discv2intelectura'] = '';
	$_REQUEST['cara02discv2fisica'] = '';
	$_REQUEST['cara02discv2psico'] = '';
	$_REQUEST['cara02discv2sistemica'] = '';
	$_REQUEST['cara02discv2sistemicaotro'] = '';
	$_REQUEST['cara02discv2multiple'] = '';
	$_REQUEST['cara02discv2multipleotro'] = '';
	$_REQUEST['cara02talentoexcepcional'] = '';
	$_REQUEST['cara01discv2tiene'] = 0;
	$_REQUEST['cara01discv2trastaprende'] = 0;
	$_REQUEST['cara01discv2soporteorigen'] = 0;
	$_REQUEST['cara01discv2archivoorigen'] = 0;
	$_REQUEST['cara01discv2trastornos'] = 0;
	$_REQUEST['cara01discv2contalento'] = 0;
	$_REQUEST['cara01discv2condicionmedica'] = 0;
	$_REQUEST['cara01discv2condmeddet'] = '';
	$_REQUEST['cara01discv2pruebacoeficiente'] = 0;
	$_REQUEST['cara44campesinado'] = '';
	$_REQUEST['cara44sexoversion'] = 1;
	$_REQUEST['cara44sexov1identidadgen'] = '';
	$_REQUEST['cara44sexov1orientasexo'] = '';
	$_REQUEST['cara44bienversion'] = 3;
	$_REQUEST['cara44bienv2altoren'] = '';
	$_REQUEST['cara44bienv2atletismo'] = '';
	$_REQUEST['cara44bienv2baloncesto'] = '';
	$_REQUEST['cara44bienv2futbol'] = '';
	$_REQUEST['cara44bienv2gimnasia'] = '';
	$_REQUEST['cara44bienv2natacion'] = '';
	$_REQUEST['cara44bienv2voleibol'] = '';
	$_REQUEST['cara44bienv2tenis'] = '';
	$_REQUEST['cara44bienv2paralimpico'] = '';
	$_REQUEST['cara44bienv2otrodeporte'] = '';
	$_REQUEST['cara44bienv2otrodeportedetalle'] = '';
	$_REQUEST['cara44bienv2activdanza'] = '';
	$_REQUEST['cara44bienv2activmusica'] = '';
	$_REQUEST['cara44bienv2activteatro'] = '';
	$_REQUEST['cara44bienv2activartes'] = '';
	$_REQUEST['cara44bienv2activliteratura'] = '';
	$_REQUEST['cara44bienv2activculturalotra'] = '';
	$_REQUEST['cara44bienv2activculturalotradetalle'] = '';
	$_REQUEST['cara44bienv2evenfestfolc'] = '';
	$_REQUEST['cara44bienv2evenexpoarte'] = '';
	$_REQUEST['cara44bienv2evenhistarte'] = '';
	$_REQUEST['cara44bienv2evengalfoto'] = '';
	$_REQUEST['cara44bienv2evenliteratura'] = '';
	$_REQUEST['cara44bienv2eventeatro'] = '';
	$_REQUEST['cara44bienv2evencine'] = '';
	$_REQUEST['cara44bienv2evenculturalotro'] = '';
	$_REQUEST['cara44bienv2evenculturalotrodetalle'] = '';
	$_REQUEST['cara44bienv2emprendimiento'] = '';
	$_REQUEST['cara44bienv2empresa'] = '';
	$_REQUEST['cara44bienv2emprenrecursos'] = '';
	$_REQUEST['cara44bienv2emprenconocim'] = '';
	$_REQUEST['cara44bienv2emprenplan'] = '';
	$_REQUEST['cara44bienv2emprenejecutar'] = '';
	$_REQUEST['cara44bienv2emprenfortconocim'] = '';
	$_REQUEST['cara44bienv2emprenidentproblema'] = '';
	$_REQUEST['cara44bienv2emprenotro'] = '';
	$_REQUEST['cara44bienv2emprenotrodetalle'] = '';
	$_REQUEST['cara44bienv2emprenmarketing'] = '';
	$_REQUEST['cara44bienv2emprenplannegocios'] = '';
	$_REQUEST['cara44bienv2emprenideas'] = '';
	$_REQUEST['cara44bienv2emprencreacion'] = '';
	$_REQUEST['cara44bienv2saludfacteconom'] = '';
	$_REQUEST['cara44bienv2saludpreocupacion'] = '';
	$_REQUEST['cara44bienv2saludconsumosust'] = '';
	$_REQUEST['cara44bienv2saludinsomnio'] = '';
	$_REQUEST['cara44bienv2saludclimalab'] = '';
	$_REQUEST['cara44bienv2saludalimenta'] = '';
	$_REQUEST['cara44bienv2saludemocion'] = '';
	$_REQUEST['cara44bienv2saludestado'] = '';
	$_REQUEST['cara44bienv2saludmedita'] = '';
	$_REQUEST['cara44bienv2crecimedusexual'] = '';
	$_REQUEST['cara44bienv2crecimcultciudad'] = '';
	$_REQUEST['cara44bienv2crecimrelpareja'] = '';
	$_REQUEST['cara44bienv2crecimrelinterp'] = '';
	$_REQUEST['cara44bienv2crecimdinamicafam'] = '';
	$_REQUEST['cara44bienv2crecimautoestima'] = '';
	$_REQUEST['cara44bienv2creciminclusion'] = '';
	$_REQUEST['cara44bienv2creciminteliemoc'] = '';
	$_REQUEST['cara44bienv2crecimcultural'] = '';
	$_REQUEST['cara44bienv2crecimartistico'] = '';
	$_REQUEST['cara44bienv2crecimdeporte'] = '';
	$_REQUEST['cara44bienv2crecimambiente'] = '';
	$_REQUEST['cara44bienv2crecimhabsocio'] = '';
	$_REQUEST['cara44bienv2ambienbasura'] = '';
	$_REQUEST['cara44bienv2ambienreutiliza'] = '';
	$_REQUEST['cara44bienv2ambienluces'] = '';
	$_REQUEST['cara44bienv2ambienfrutaverd'] = '';
	$_REQUEST['cara44bienv2ambienenchufa'] = '';
	$_REQUEST['cara44bienv2ambiengrifo'] = '';
	$_REQUEST['cara44bienv2ambienbicicleta'] = '';
	$_REQUEST['cara44bienv2ambientranspub'] = '';
	$_REQUEST['cara44bienv2ambienducha'] = '';
	$_REQUEST['cara44bienv2ambiencaminata'] = '';
	$_REQUEST['cara44bienv2ambiensiembra'] = '';
	$_REQUEST['cara44bienv2ambienconferencia'] = '';
	$_REQUEST['cara44bienv2ambienrecicla'] = '';
	$_REQUEST['cara44bienv2ambienotraactiv'] = '';
	$_REQUEST['cara44bienv2ambienotraactivdetalle'] = '';
	$_REQUEST['cara44bienv2ambienreforest'] = '';
	$_REQUEST['cara44bienv2ambienmovilidad'] = '';
	$_REQUEST['cara44bienv2ambienclimatico'] = '';
	$_REQUEST['cara44bienv2ambienecofemin'] = '';
	$_REQUEST['cara44bienv2ambienbiodiver'] = '';
	$_REQUEST['cara44bienv2ambienecologia'] = '';
	$_REQUEST['cara44bienv2ambieneconomia'] = '';
	$_REQUEST['cara44bienv2ambienrecnatura'] = '';
	$_REQUEST['cara44bienv2ambienreciclaje'] = '';
	$_REQUEST['cara44bienv2ambienmascota'] = '';
	$_REQUEST['cara44bienv2ambiencartohum'] = '';
	$_REQUEST['cara44bienv2ambienespiritu'] = '';
	$_REQUEST['cara44bienv2ambiencarga'] = '';
	$_REQUEST['cara44bienv2ambienotroenfoq'] = '';
	$_REQUEST['cara44bienv2ambienotroenfoqdetalle'] = '';
	$_REQUEST['cara44fam_madrecabeza'] = '';
	$_REQUEST['cara44acadhatenidorecesos'] = '';
	$_REQUEST['cara44acadrazonreceso'] = 0;
	$_REQUEST['cara44acadrazonrecesodetalle'] = '';
	$_REQUEST['cara44campus_usocorreounad'] = 0;
	$_REQUEST['cara44campus_usocorreounadno'] = 0;
	$_REQUEST['cara44campus_usocorreounadnodetalle'] = '';
	$_REQUEST['cara44campus_medioactivunad'] = 0;
	$_REQUEST['cara44campus_medioactivunaddetalle'] = '';
	$_REQUEST['cara44frontera'] = 0;
	$_REQUEST['cara44med_tratamiento'] = 0;
	$_REQUEST['cara44med_trat_cual'] = '';
	$_REQUEST['cara44bienv3emprenetapa'] = 0;
	$_REQUEST['cara44bienv3emprennecesita'] = 0;
	$_REQUEST['cara44bienv3emprenanioini'] = 0;
	$_REQUEST['cara44bienv3emprensector'] = 0;
	$_REQUEST['cara44bienv3emprensectorotro'] = '';
	$_REQUEST['cara44bienv3emprentemas'] = 0;
	$_REQUEST['cara44bienv3ambienclima'] = 0;
	$_REQUEST['cara44bienv3ambienjusticia'] = 0;
	$_REQUEST['cara44bienv3ambienagroeco'] = 0;
	$_REQUEST['cara44bienv3ambieneconomia'] = 0;
	$_REQUEST['cara44bienv3ambieneducacion'] = 0;
	$_REQUEST['cara44bienv3ambienbiodiverso'] = 0;
	$_REQUEST['cara44bienv3ambienecoturismo'] = 0;
	$_REQUEST['cara44bienv3ambienotro'] = 0;
	$_REQUEST['cara44bienv3ambienotrodetalle'] = '';
	$_REQUEST['cara44bienv3ambienexper'] = 0;
	$_REQUEST['cara44bienv3ambienaprende'] = 0;
	$_REQUEST['cara44bienv3ambienestudiante'] = 0;
	$_REQUEST['cara44bienv3ambienactividad'] = 0;
	$_REQUEST['cara44bienv3pyphabitoalim'] = 0;
	$_REQUEST['cara44bienv3pypsustanciapsico'] = 0;
	$_REQUEST['cara44bienv3pypsaludvisual'] = 0;
	$_REQUEST['cara44bienv3pypsaludbucal'] = 0;
	$_REQUEST['cara44bienv3pypsaludsexual'] = 0;
	$_REQUEST['cara44bienv3deportenivel'] = 0;
	$_REQUEST['cara44bienv3deportefrec'] = 0;
	$_REQUEST['cara44bienv3deportecual'] = 0;
	$_REQUEST['cara44bienv3deportecualotro'] = '';
	$_REQUEST['cara44bienv3deporterecrea'] = 0;
	$_REQUEST['cara44bienv3deporteunad'] = 0;
	$_REQUEST['cara44bienv3creciminclusion'] = 0;
	$_REQUEST['cara44bienv3crecimfamilia'] = 0;
	$_REQUEST['cara44bienv3crecimhabilidad'] = 0;
	$_REQUEST['cara44bienv3crecimempleable'] = 0;
	$_REQUEST['cara44bienv3crecimhabilvida'] = 0;
	$_REQUEST['cara44bienv3crecimespiritual'] = 0;
	$_REQUEST['cara44bienv3crecimpractica'] = 0;
	$_REQUEST['cara44bienv3crecimliderazgo'] = 0;
	$_REQUEST['cara44bienv3crecimtrabequipo'] = 0;
	$_REQUEST['cara44bienv3crecimasertiva'] = 0;
	$_REQUEST['cara44bienv3crecimgesttiempo'] = 0;
	$_REQUEST['cara44bienv3crecimconflictos'] = 0;
	$_REQUEST['cara44bienv3crecimadapcambio'] = 0;
	$_REQUEST['cara44bienv3crecimempatia'] = 0;
	$_REQUEST['cara44bienv3crecimgestionser'] = 0;
	$_REQUEST['cara44bienv3crecimtomadecide'] = 0;
	$_REQUEST['cara44bienv3crecimpenscreativo'] = 0;
	$_REQUEST['cara44bienv3crecimpenscritico'] = 0;
	$_REQUEST['cara44bienv3crecimhabilotro'] = 0;
	$_REQUEST['cara44bienv3crecimhabilotrodetalle'] = '';
	$_REQUEST['cara44bienv3crecimalcancemeta'] = 0;
	$_REQUEST['cara44bienv3crecimsatifpersonal'] = 0;
	$_REQUEST['cara44bienv3crecimaccesolaboral'] = 0;
	$_REQUEST['cara44bienv3crecimotramotiv'] = 0;
	$_REQUEST['cara44bienv3crecimotramotivdetalle'] = '';
	$_REQUEST['cara44bienv3crecimapoyo'] = 0;
	$_REQUEST['cara44bienv3crecimlaboral'] = 0;
	$_REQUEST['cara44bienv3mentalcuidado'] = 0;
	$_REQUEST['cara44bienv3mentalestrategia'] = 0;
	$_REQUEST['cara44bienv3mentalestres'] = 0;
	$_REQUEST['cara44bienv3mentalansiedad'] = 0;
	$_REQUEST['cara44bienv3mentaldepresion'] = 0;
	$_REQUEST['cara44bienv3mentalautoconoc'] = 0;
	$_REQUEST['cara44bienv3mentalmindfulness'] = 0;
	$_REQUEST['cara44bienv3mentalautoestima'] = 0;
	$_REQUEST['cara44bienv3mentalcrisis'] = 0;
	$_REQUEST['cara44bienv3mentalburnout'] = 0;
	$_REQUEST['cara44bienv3mentalsexualidad'] = 0;
	$_REQUEST['cara44bienv3mentalusoredes'] = 0;
	$_REQUEST['cara44bienv3mentalinclusion'] = 0;
	$_REQUEST['cara44bienv3mentalactividad'] = 0;
	$_REQUEST['cara44bienv3mentalacompana'] = 0;
	$_REQUEST['cara44bienv3mentaldiagnostico'] = 0;
	$_REQUEST['cara44bienv3mentaldiagcual'] = 0;
	$_REQUEST['cara44bienv3mentaldiagotro'] = '';
	$_REQUEST['cara44bienv3arteintegrar'] = 0;
	$_REQUEST['cara44bienv3arteformacion'] = 0;
	$_REQUEST['cara44bienv3arteunad'] = 0;
	$_REQUEST['cara44bienv3arteinformacion'] = 0;
	$_REQUEST['paso'] = 0;
}
if ($bLimpiaHijos) {
}
//AQUI SE DEBEN CARGAR TODOS LOS DATOS QUE LA FORMA NECESITE.
$bPuedeGuardar = false;
$bConEliminar = false;
$bConBotonCerrar = false;
$bPuedeAbrir = false;
$bPintarTablero = false;
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
$iNivelFormacion = 2;
$bPasoBachiller = true;
$bMenorDeEdad = false;
$et_cara01agnos = '&nbsp;';
if ($_REQUEST['paso'] != 0) {
	$sSQL = 'SELECT cara09nivelformacion FROM core09programa WHERE core09id=' . $_REQUEST['cara01idprograma'] . '';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila1 = $objDB->sf($tabla);
		switch ($fila1['cara09nivelformacion']) {
			case 11: // Bachillerato
				$bPasoBachiller = false;
				$iNivelFormacion = 1;
				break;
			case 3:
			case 4:
			case 5:
			case 6:
				$iNivelFormacion = $fila1['cara09nivelformacion'];
				break;
		}
	}
	if ($_REQUEST['cara01completa'] == 'S') {
		if ($bEstudiante){
			$bPintarTablero = true;
		}
		//Definir las condiciones que permitirán abrir el registro.
		list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 17, $idTercero, $objDB);
		if ($bDevuelve) {
			$bPuedeAbrir = true;
		}
	}else{
		if ($bEstudiante) {
			$bPuedeGuardar = true;
			$bPintarTablero = false;
		} else {
			if ($_REQUEST['paso'] == 2){
				$bConEliminar = true;		
			}
		}
	}
	// --
	if ($_REQUEST['cara01agnos'] > 0) {
		$et_cara01agnos = $_REQUEST['cara01agnos'];
		if ($_REQUEST['cara01agnos'] < 18) {
			$bMenorDeEdad = true;
		}
	} else {
		$et_cara01agnos = '<span class="rojo">Sin Fecha de Nacimiento</span>';
	}
}
//
$iSexoVersion=$_REQUEST['cara44sexoversion'];
$bAntiguo = false;
$bMuestraTiempoSinEstudiar =  true;
if ($_REQUEST['cara01tipocaracterizacion'] == 3) {
	$bAntiguo = true;
	$bMuestraTiempoSinEstudiar = !$bPasoBachiller;
}
//Permisos adicionales
$seg_5 = 0;
$seg_6 = 0;
if (!$bEstudiante) {
	list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 6, $idTercero, $objDB);
	if ($bDevuelve) {
		$seg_6 = 1;
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
list($cara01idtercero_rs, $_REQUEST['cara01idtercero'], $_REQUEST['cara01idtercero_td'], $_REQUEST['cara01idtercero_doc']) = html_tercero($_REQUEST['cara01idtercero_td'], $_REQUEST['cara01idtercero_doc'], $_REQUEST['cara01idtercero'], 0, $objDB);
$bOculto = true;
if ($bEstudiante) {
    $bOculto = true;
} else {
    if ($_REQUEST['paso'] != 2) {
        $bOculto = false;
    }
}
$html_cara01idtercero = html_DivTerceroV8('cara01idtercero', $_REQUEST['cara01idtercero_td'], $_REQUEST['cara01idtercero_doc'], $bOculto, $objDB, $objCombos, 1, $ETI['ing_doc']);
$objCombos->nuevo('cara01sexo', $_REQUEST['cara01sexo'], true, '{' . $ETI['msg_seleccione'] . '}');
$objCombos->sAccion = 'ajustar_fam_hijos()';
$html_cara01sexo = $objCombos->html('SELECT unad22codopcion AS id, unad22nombre AS nombre FROM unad22combos WHERE unad22idmodulo=111 AND unad22consec=1 AND unad22activa="S" ORDER BY unad22orden', $objDB);
if ($iSexoVersion == 1) {
	$objCombos->nuevo('cara44sexov1identidadgen', $_REQUEST['cara44sexov1identidadgen'], true, '{' . $ETI['msg_seleccione'] . '}', 0);
	$objCombos->addArreglo($acara44sexov1identidadgen, $icara44sexov1identidadgen);
	$html_cara44sexov1identidadgen = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44sexov1orientasexo', $_REQUEST['cara44sexov1orientasexo'], true, '{' . $ETI['msg_seleccione'] . '}', 0);
	$objCombos->addArreglo($acara44sexov1orientasexo, $icara44sexov1orientasexo);
	$html_cara44sexov1orientasexo = $objCombos->html('', $objDB);
}
$objCombos->nuevo('cara01pais', $_REQUEST['cara01pais'], true, '{' . $ETI['msg_seleccione'] . '}');
$objCombos->sAccion = 'carga_combo_cara01depto();';
$html_cara01pais = $objCombos->html('SELECT unad18codigo AS id, unad18nombre AS nombre FROM unad18pais ORDER BY unad18nombre', $objDB);
$html_cara01depto = f2301_HTMLComboV2_cara01depto($objDB, $objCombos, $_REQUEST['cara01depto'], $_REQUEST['cara01pais']);
$html_cara01ciudad = f2301_HTMLComboV2_cara01ciudad($objDB, $objCombos, $_REQUEST['cara01ciudad'], $_REQUEST['cara01depto']);
$objCombos->nuevo('cara01estrato', $_REQUEST['cara01estrato'], true, '{' . $ETI['msg_seleccione'] . '}');
$objCombos->addArreglo($aestrato, $iestrato);
$html_cara01estrato = $objCombos->html('', $objDB);
$objCombos->nuevo('cara01zonares', $_REQUEST['cara01zonares'], true);
$objCombos->addItem('U', 'Urbana');
$objCombos->addItem('R', 'Rural');
$html_cara01zonares = $objCombos->html('', $objDB);
$objCombos->nuevo('cara01estcivil', $_REQUEST['cara01estcivil'], true, '{' . $ETI['msg_seleccione'] . '}');
$html_cara01estcivil = $objCombos->html('SELECT unad21codigo AS id, unad21nombre AS nombre FROM unad21estadocivil ORDER BY unad21nombre', $objDB);
$objCombos->nuevo('cara01idzona', $_REQUEST['cara01idzona'], true, '{' . $ETI['msg_seleccione'] . '}');
$objCombos->sAccion = 'carga_combo_cara01idcead();';
$html_cara01idzona = $objCombos->html('SELECT unad23id AS id, unad23nombre AS nombre FROM unad23zona ORDER BY unad23nombre', $objDB);
$html_cara01idcead = f2301_HTMLComboV2_cara01idcead($objDB, $objCombos, $_REQUEST['cara01idcead'], $_REQUEST['cara01idzona']);
$objCombos->nuevo('cara01matconvenio', $_REQUEST['cara01matconvenio'], false);
$objCombos->sino();
$html_cara01matconvenio = $objCombos->html('', $objDB);
$objCombos->nuevo('cara01raizal', $_REQUEST['cara01raizal'], true, '');
$objCombos->sino();
$html_cara01raizal = $objCombos->html('', $objDB);
$objCombos->nuevo('cara01palenquero', $_REQUEST['cara01palenquero'], true, '');
$objCombos->sino();
$html_cara01palenquero = $objCombos->html('', $objDB);
$objCombos->nuevo('cara01afrocolombiano', $_REQUEST['cara01afrocolombiano'], true, '');
$objCombos->sino();
$html_cara01afrocolombiano = $objCombos->html('', $objDB);
$objCombos->nuevo('cara01otracomunnegras', $_REQUEST['cara01otracomunnegras'], true, '');
$objCombos->sino();
$html_cara01otracomunnegras = $objCombos->html('', $objDB);
$objCombos->nuevo('cara01rom', $_REQUEST['cara01rom'], true, '');
$objCombos->sino();
$html_cara01rom = $objCombos->html('', $objDB);
$objCombos->nuevo('cara44campesinado', $_REQUEST['cara44campesinado'], true, '');
$objCombos->sino($ETI['si'], $ETI['no']); //, $sValorSi='S', $sValorNo='N'
$html_cara44campesinado = $objCombos->html('', $objDB);
$objCombos->nuevo('cara01indigenas', $_REQUEST['cara01indigenas'], true, 'No', 0);
$html_cara01indigenas = $objCombos->html('SELECT cara02id AS id, cara02nombre AS nombre FROM cara02indigenas ORDER BY cara02nombre', $objDB);
$objCombos->nuevo('cara01victimadesplazado', $_REQUEST['cara01victimadesplazado'], false);
$objCombos->sino();
$html_cara01victimadesplazado = $objCombos->html('', $objDB);
list($cara01idconfirmadesp_rs, $_REQUEST['cara01idconfirmadesp'], $_REQUEST['cara01idconfirmadesp_td'], $_REQUEST['cara01idconfirmadesp_doc']) = html_tercero($_REQUEST['cara01idconfirmadesp_td'], $_REQUEST['cara01idconfirmadesp_doc'], $_REQUEST['cara01idconfirmadesp'], 0, $objDB);
$bOculto = true;
$html_cara01idconfirmadesp = html_DivTerceroV8('cara01idconfirmadesp', $_REQUEST['cara01idconfirmadesp_td'], $_REQUEST['cara01idconfirmadesp_doc'], $bOculto, $objDB, $objCombos, 0, $ETI['ing_doc']);
$objCombos->nuevo('cara01victimaacr', $_REQUEST['cara01victimaacr'], false);
$objCombos->sino();
$html_cara01victimaacr = $objCombos->html('', $objDB);
list($cara01idconfirmacr_rs, $_REQUEST['cara01idconfirmacr'], $_REQUEST['cara01idconfirmacr_td'], $_REQUEST['cara01idconfirmacr_doc']) = html_tercero($_REQUEST['cara01idconfirmacr_td'], $_REQUEST['cara01idconfirmacr_doc'], $_REQUEST['cara01idconfirmacr'], 0, $objDB);
$bOculto = true;
$html_cara01idconfirmacr = html_DivTerceroV8('cara01idconfirmacr', $_REQUEST['cara01idconfirmacr_td'], $_REQUEST['cara01idconfirmacr_doc'], $bOculto, $objDB, $objCombos, 0, $ETI['ing_doc']);
$objCombos->nuevo('cara01inpecfuncionario', $_REQUEST['cara01inpecfuncionario'], false);
$objCombos->sino();
$html_cara01inpecfuncionario = $objCombos->html('', $objDB);
$objCombos->nuevo('cara01inpecrecluso', $_REQUEST['cara01inpecrecluso'], false);
$objCombos->sino();
$objCombos->sAccion = 'verrecluso()';
$html_cara01inpecrecluso = $objCombos->html('', $objDB);
$objCombos->nuevo('cara01inpectiempocondena', $_REQUEST['cara01inpectiempocondena'], true, '{' . $ETI['msg_seleccione'] . '}');
$objCombos->addItem(1, 'Menos de un a&ntilde;o');
$objCombos->addItem(3, 'Entre uno y tres a&ntilde;os');
$objCombos->addItem(5, 'Entre cuatro y cinco a&ntilde;os');
$objCombos->addItem(6, 'Mas de cinco a&ntilde;os');
//$objCombos->addArreglo($acara01inpectiempocondena, $icara01inpectiempocondena);
$html_cara01inpectiempocondena = $objCombos->html('', $objDB);
$objCombos->nuevo('cara01centroreclusion', $_REQUEST['cara01centroreclusion'], true, '{' . $ETI['msg_ninguno'] . '}', 0);
$html_cara01centroreclusion = $objCombos->html('SELECT cara03id AS id, cara03nombre AS nombre FROM cara03centroreclusion ORDER BY cara03nombre', $objDB);
if ($_REQUEST['cara01discversion'] == 0) {
	$objCombos->nuevo('cara01discsensorial', $_REQUEST['cara01discsensorial'], true, $ETI['no'], 'N');
	$objCombos->addArreglo($acara01discsensorial, $icara01discsensorial);
	$objCombos->sAccion = 'ajustar_discsensorial()';
	$html_cara01discsensorial = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara01discfisica', $_REQUEST['cara01discfisica'], true, $ETI['no'], 'N');
	$objCombos->addArreglo($acara01discfisica, $icara01discfisica);
	$objCombos->sAccion = 'ajustar_discfisica()';
	$html_cara01discfisica = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara01disccognitiva', $_REQUEST['cara01disccognitiva'], true, $ETI['no'], 'N');
	$objCombos->addArreglo($acara01disccognitiva, $icara01disccognitiva);
	$objCombos->sAccion = 'ajustar_disccognitiva()';
	$html_cara01disccognitiva = $objCombos->html('', $objDB);
}
list($cara01idconfirmadisc_rs, $_REQUEST['cara01idconfirmadisc'], $_REQUEST['cara01idconfirmadisc_td'], $_REQUEST['cara01idconfirmadisc_doc']) = html_tercero($_REQUEST['cara01idconfirmadisc_td'], $_REQUEST['cara01idconfirmadisc_doc'], $_REQUEST['cara01idconfirmadisc'], 0, $objDB);
$bOculto = true;
$html_cara01idconfirmadisc = html_DivTerceroV8('cara01idconfirmadisc', $_REQUEST['cara01idconfirmadisc_td'], $_REQUEST['cara01idconfirmadisc_doc'], $bOculto, $objDB, $objCombos, 0, $ETI['ing_doc']);
$objCombos->nuevo('cara01fam_tipovivienda', $_REQUEST['cara01fam_tipovivienda'], true, '{' . $ETI['msg_seleccione'] . '}');
$objCombos->addArreglo($afam_tipovivienda, $ifam_tipovivienda);
$html_cara01fam_tipovivienda = $objCombos->html('', $objDB);
$objCombos->nuevo('cara01fam_vivecon', $_REQUEST['cara01fam_vivecon'], true, '{' . $ETI['msg_seleccione'] . '}');
$objCombos->addArreglo($afam_vivecon, $ifam_vivecon);
$html_cara01fam_vivecon = $objCombos->html('', $objDB);
$objCombos->nuevo('cara01fam_numpersgrupofam', $_REQUEST['cara01fam_numpersgrupofam'], true, '{' . $ETI['msg_seleccione'] . '}');
$objCombos->addArreglo($afam_numpersgrupofam, $ifam_numpersgrupofam);
$html_cara01fam_numpersgrupofam = $objCombos->html('', $objDB);
$objCombos->nuevo('cara01fam_hijos', $_REQUEST['cara01fam_hijos'], true, '{' . $ETI['msg_seleccione'] . '}');
$objCombos->addArreglo($afam_hijos, $ifam_hijos);
$objCombos->sAccion = 'ajustar_fam_hijos()';
$html_cara01fam_hijos = $objCombos->html('', $objDB);
$objCombos->nuevo('cara44fam_madrecabeza', $_REQUEST['cara44fam_madrecabeza'], true, '');
$objCombos->sino();
$html_cara44fam_madrecabeza = $objCombos->html('', $objDB);
$objCombos->nuevo('cara01fam_personasacargo', $_REQUEST['cara01fam_personasacargo'], true, '{' . $ETI['msg_seleccione'] . '}');
$objCombos->addArreglo($afam_personasacargo, $ifam_personasacargo);
$html_cara01fam_personasacargo = $objCombos->html('', $objDB);
$objCombos->nuevo('cara01fam_dependeecon', $_REQUEST['cara01fam_dependeecon'], true, '');
$objCombos->sino();
$html_cara01fam_dependeecon = $objCombos->html('', $objDB);
$objCombos->nuevo('cara01fam_escolaridadpadre', $_REQUEST['cara01fam_escolaridadpadre'], true, '{' . $ETI['msg_seleccione'] . '}');
$objCombos->addArreglo($aescolaridad, $iescolaridad);
$html_cara01fam_escolaridadpadre = $objCombos->html('', $objDB);
$objCombos->nuevo('cara01fam_escolaridadmadre', $_REQUEST['cara01fam_escolaridadmadre'], true, '{' . $ETI['msg_seleccione'] . '}');
$objCombos->addArreglo($aescolaridad, $iescolaridad);
$html_cara01fam_escolaridadmadre = $objCombos->html('', $objDB);
$objCombos->nuevo('cara01fam_numhermanos', $_REQUEST['cara01fam_numhermanos'], true, '{' . $ETI['msg_seleccione'] . '}');
$objCombos->addArreglo($afam_numhermanos, $ifam_numhermanos);
$html_cara01fam_numhermanos = $objCombos->html('', $objDB);
$objCombos->nuevo('cara01fam_posicionherm', $_REQUEST['cara01fam_posicionherm'], true, '{' . $ETI['msg_seleccione'] . '}');
$objCombos->addArreglo($afam_posicionherm, $ifam_posicionherm);
$html_cara01fam_posicionherm = $objCombos->html('', $objDB);
$objCombos->nuevo('cara01fam_familiaunad', $_REQUEST['cara01fam_familiaunad'], true, '');
$objCombos->sino();
$html_cara01fam_familiaunad = $objCombos->html('', $objDB);
$objCombos->nuevo('cara01acad_tipocolegio', $_REQUEST['cara01acad_tipocolegio'], true, '{' . $ETI['msg_seleccione'] . '}');
$objCombos->addArreglo($aacad_tipocolegio, $iacad_tipocolegio);
$html_cara01acad_tipocolegio = $objCombos->html('', $objDB);
$objCombos->nuevo('cara01acad_modalidadbach', $_REQUEST['cara01acad_modalidadbach'], true, '{' . $ETI['msg_seleccione'] . '}');
$objCombos->addArreglo($aacad_modalidadbach, $iacad_modalidadbach);
$html_cara01acad_modalidadbach = $objCombos->html('', $objDB);
$objCombos->nuevo('cara01acad_estudioprev', $_REQUEST['cara01acad_estudioprev'], true, '');
$objCombos->sino();
$html_cara01acad_estudioprev = $objCombos->html('', $objDB);
$objCombos->nuevo('cara01acad_ultnivelest', $_REQUEST['cara01acad_ultnivelest'], true, '{' . $ETI['msg_seleccione'] . '}');
if ($bPasoBachiller) {
	$objCombos->addArreglo($aacad_ultnivelest, $iacad_ultnivelest);
} else {
	for ($k = 10; $k <= 20; $k++) {
		$objCombos->addItem($k, $aacad_ultnivelest[$k]);
	}
}
$html_cara01acad_ultnivelest = $objCombos->html('', $objDB);
if ($bPasoBachiller) {
	$objCombos->nuevo('cara01acad_obtubodiploma', $_REQUEST['cara01acad_obtubodiploma'], true, '');
	$objCombos->sino();
	$html_cara01acad_obtubodiploma = $objCombos->html('', $objDB);
}
$objCombos->nuevo('cara01acad_hatomadovirtual', $_REQUEST['cara01acad_hatomadovirtual'], true, '');
$objCombos->sino();
$html_cara01acad_hatomadovirtual = $objCombos->html('', $objDB);
$objCombos->nuevo('cara01acad_tiemposinest', $_REQUEST['cara01acad_tiemposinest'], true, '{' . $ETI['msg_seleccione'] . '}');
$objCombos->addArreglo($acara01acad_tiemposinest, $icara01acad_tiemposinest);
$html_cara01acad_tiemposinest = $objCombos->html('', $objDB);
$objCombos->nuevo('cara01acad_razonestudio', $_REQUEST['cara01acad_razonestudio'], true, '{' . $ETI['msg_seleccione'] . '}');
$sCondiRazon = 'cara04profesional=1';
if (!$bPasoBachiller) {
	$sCondiRazon = 'cara04bachiller=1';
}
$sSQL = 'SELECT cara04id AS id, cara04nombre AS nombre 
FROM cara04razonestudio 
WHERE ' . $sCondiRazon . ' 
ORDER BY cara04orden, cara04nombre';
$html_cara01acad_razonestudio = $objCombos->html($sSQL, $objDB);
$objCombos->nuevo('cara01acad_primeraopc', $_REQUEST['cara01acad_primeraopc'], true, '');
$objCombos->sino();
$objCombos->sAccion = 'ajustarprimeraopc()';
$html_cara01acad_primeraopc = $objCombos->html('', $objDB);
$objCombos->nuevo('cara01acad_razonunad', $_REQUEST['cara01acad_razonunad'], true, '{' . $ETI['msg_seleccione'] . '}');
$html_cara01acad_razonunad = $objCombos->html('SELECT cara05id AS id, cara05nombre AS nombre FROM cara05razonunad ORDER BY cara05orden, cara05nombre', $objDB);
$objCombos->nuevo('cara44acadhatenidorecesos', $_REQUEST['cara44acadhatenidorecesos'], true, '');
$objCombos->sino();
$objCombos->sAccion = 'ajustar_acadhatenidorecesos()';
$html_cara44acadhatenidorecesos = $objCombos->html('', $objDB);
$objCombos->nuevo('cara44acadrazonreceso', $_REQUEST['cara44acadrazonreceso'], true, '{' . $ETI['msg_seleccione'] . '}', 0);
$objCombos->addArreglo($acara44acadrazonreceso, $icara44acadrazonreceso);
$objCombos->sAccion = 'ajustar_acadrazonreceso()';
$html_cara44acadrazonreceso = $objCombos->html('', $objDB);
$objCombos->nuevo('cara01campus_compescrito', $_REQUEST['cara01campus_compescrito'], true, '');
$objCombos->sino();
$html_cara01campus_compescrito = $objCombos->html('', $objDB);
$objCombos->nuevo('cara01campus_portatil', $_REQUEST['cara01campus_portatil'], true, '');
$objCombos->sino();
$html_cara01campus_portatil = $objCombos->html('', $objDB);
$objCombos->nuevo('cara01campus_tableta', $_REQUEST['cara01campus_tableta'], true, '');
$objCombos->sino();
$html_cara01campus_tableta = $objCombos->html('', $objDB);
$objCombos->nuevo('cara01campus_telefono', $_REQUEST['cara01campus_telefono'], true, '');
$objCombos->sino();
$html_cara01campus_telefono = $objCombos->html('', $objDB);
$objCombos->nuevo('cara01campus_energia', $_REQUEST['cara01campus_energia'], true, '{' . $ETI['msg_seleccione'] . '}');
$objCombos->addArreglo($acara01campus_energia, $icara01campus_energia);
$html_cara01campus_energia = $objCombos->html('', $objDB);
$objCombos->nuevo('cara01campus_internetreside', $_REQUEST['cara01campus_internetreside'], true, '{' . $ETI['msg_seleccione'] . '}');
$objCombos->addArreglo($acara01campus_internetreside, $icara01campus_internetreside);
$html_cara01campus_internetreside = $objCombos->html('', $objDB);
$objCombos->nuevo('cara01campus_expvirtual', $_REQUEST['cara01campus_expvirtual'], true, '');
$objCombos->sino();
$html_cara01campus_expvirtual = $objCombos->html('', $objDB);
$objCombos->nuevo('cara01campus_ofimatica', $_REQUEST['cara01campus_ofimatica'], true, '');
$objCombos->sino();
$html_cara01campus_ofimatica = $objCombos->html('', $objDB);
$objCombos->nuevo('cara01campus_foros', $_REQUEST['cara01campus_foros'], true, '');
$objCombos->sino();
$html_cara01campus_foros = $objCombos->html('', $objDB);
$objCombos->nuevo('cara01campus_conversiones', $_REQUEST['cara01campus_conversiones'], true, '');
$objCombos->sino();
$html_cara01campus_conversiones = $objCombos->html('', $objDB);
$objCombos->nuevo('cara01campus_usocorreo', $_REQUEST['cara01campus_usocorreo'], true, '{' . $ETI['msg_seleccione'] . '}');
$objCombos->addArreglo($acara01campus_usocorreo, $icara01campus_usocorreo);
$html_cara01campus_usocorreo = $objCombos->html('', $objDB);
$objCombos->nuevo('cara44campus_usocorreounad', $_REQUEST['cara44campus_usocorreounad'], true, '{' . $ETI['msg_seleccione'] . '}', 0);
$objCombos->addArreglo($acara44campus_usocorreounad, $icara44campus_usocorreounad);
$objCombos->sAccion = 'ajustar_campus_usocorreounad()';
$html_cara44campus_usocorreounad = $objCombos->html('', $objDB);
$objCombos->nuevo('cara44campus_usocorreounadno', $_REQUEST['cara44campus_usocorreounadno'], true, '{' . $ETI['msg_seleccione'] . '}', 0);
$objCombos->addArreglo($acara44campus_usocorreounadno, $icara44campus_usocorreounadno);
$objCombos->sAccion = 'ajustar_campus_usocorreounadno()';
$html_cara44campus_usocorreounadno = $objCombos->html('', $objDB);
$objCombos->nuevo('cara01campus_aprendtexto', $_REQUEST['cara01campus_aprendtexto'], true, '');
$objCombos->sino();
$html_cara01campus_aprendtexto = $objCombos->html('', $objDB);
$objCombos->nuevo('cara01campus_aprendvideo', $_REQUEST['cara01campus_aprendvideo'], true, '');
$objCombos->sino();
$html_cara01campus_aprendvideo = $objCombos->html('', $objDB);
$objCombos->nuevo('cara01campus_aprendmapas', $_REQUEST['cara01campus_aprendmapas'], true, '');
$objCombos->sino();
$html_cara01campus_aprendmapas = $objCombos->html('', $objDB);
$objCombos->nuevo('cara01campus_aprendeanima', $_REQUEST['cara01campus_aprendeanima'], true, '');
$objCombos->sino();
$html_cara01campus_aprendeanima = $objCombos->html('', $objDB);
$objCombos->nuevo('cara01campus_mediocomunica', $_REQUEST['cara01campus_mediocomunica'], true, '{' . $ETI['msg_seleccione'] . '}');
$objCombos->addArreglo($acara01campus_mediocomunica, $icara01campus_mediocomunica);
$html_cara01campus_mediocomunica = $objCombos->html('', $objDB);
$objCombos->nuevo('cara44campus_medioactivunad', $_REQUEST['cara44campus_medioactivunad'], true, '{' . $ETI['msg_seleccione'] . '}', 0);
$objCombos->addArreglo($acara44campus_medioactivunad, $icara44campus_medioactivunad);
$objCombos->sAccion = 'ajustar_campus_medioactivunad()';
$sSQL = '';
$html_cara44campus_medioactivunad = $objCombos->html($sSQL, $objDB);
$objCombos->nuevo('cara44frontera', $_REQUEST['cara44frontera'], true, $ETI['no'], 0);
$objCombos->addItem(1, $ETI['si']);
//$objCombos->addArreglo($acara44frontera, $icara44frontera);
$sSQL = '';
$html_cara44frontera = $objCombos->html($sSQL, $objDB);
$objCombos->nuevo('cara44med_tratamiento', $_REQUEST['cara44med_tratamiento'], true, $ETI['no'], 0);
$objCombos->sAccion = 'ajustar_cara44med_tratamiento()';
$objCombos->addItem(1, $ETI['si']);
//$objCombos->addArreglo($acara44med_tratamiento, $icara44med_tratamiento);
$sSQL = '';
$html_cara44med_tratamiento = $objCombos->html($sSQL, $objDB);
$bOculto = !$bMenorDeEdad;
$objCombos->nuevo('cara01lab_situacion', $_REQUEST['cara01lab_situacion'], $bOculto, '{' . $ETI['msg_seleccione'] . '}');
if ($bMenorDeEdad) {
	$objCombos->addItem('0', '{' . $ETI['msg_na'] . '}');
}
$objCombos->addArreglo($acara01lab_situacion, $icara01lab_situacion);
$objCombos->sAccion = 'ajustarlaboral()';
$html_cara01lab_situacion = $objCombos->html('', $objDB);
$objCombos->nuevo('cara01lab_sector', $_REQUEST['cara01lab_sector'], true, '{' . $ETI['msg_seleccione'] . '}');
$objCombos->addArreglo($acara01lab_sector, $icara01lab_sector);
$html_cara01lab_sector = $objCombos->html('', $objDB);
$objCombos->nuevo('cara01lab_caracterjuri', $_REQUEST['cara01lab_caracterjuri'], true, '{' . $ETI['msg_seleccione'] . '}');
$objCombos->addArreglo($acara01lab_caracterjuri, $icara01lab_caracterjuri);
$html_cara01lab_caracterjuri = $objCombos->html('', $objDB);
$objCombos->nuevo('cara01lab_cargo', $_REQUEST['cara01lab_cargo'], true, '{' . $ETI['msg_seleccione'] . '}');
$objCombos->addArreglo($acara01lab_cargo, $icara01lab_cargo);
$html_cara01lab_cargo = $objCombos->html('', $objDB);
$objCombos->nuevo('cara01lab_antiguedad', $_REQUEST['cara01lab_antiguedad'], true, '{' . $ETI['msg_seleccione'] . '}');
$objCombos->addArreglo($acara01lab_antiguedad, $icara01lab_antiguedad);
$html_cara01lab_antiguedad = $objCombos->html('', $objDB);
$objCombos->nuevo('cara01lab_tipocontrato', $_REQUEST['cara01lab_tipocontrato'], true, '{' . $ETI['msg_seleccione'] . '}');
$objCombos->addArreglo($acara01lab_tipocontrato, $icara01lab_tipocontrato);
$html_cara01lab_tipocontrato = $objCombos->html('', $objDB);
$objCombos->nuevo('cara01lab_rangoingreso', $_REQUEST['cara01lab_rangoingreso'], true, '{' . $ETI['msg_seleccione'] . '}');
$objCombos->addArreglo($acara01lab_rangoingreso, $icara01lab_rangoingreso);
$html_cara01lab_rangoingreso = $objCombos->html('', $objDB);
$objCombos->nuevo('cara01lab_tiempoacadem', $_REQUEST['cara01lab_tiempoacadem'], true, '{' . $ETI['msg_seleccione'] . '}');
$objCombos->addArreglo($acara01lab_tiempoacadem, $icara01lab_tiempoacadem);
$html_cara01lab_tiempoacadem = $objCombos->html('', $objDB);
$objCombos->nuevo('cara01lab_tipoempresa', $_REQUEST['cara01lab_tipoempresa'], true, '{' . $ETI['msg_seleccione'] . '}');
$objCombos->addArreglo($acara01lab_tipoempresa, $icara01lab_tipoempresa);
$html_cara01lab_tipoempresa = $objCombos->html('', $objDB);
$objCombos->nuevo('cara01lab_tiempoindepen', $_REQUEST['cara01lab_tiempoindepen'], true, '{' . $ETI['msg_seleccione'] . '}');
$objCombos->addArreglo($acara01lab_tiempoindepen, $icara01lab_tiempoindepen);
$html_cara01lab_tiempoindepen = $objCombos->html('', $objDB);
$objCombos->nuevo('cara01lab_debebusctrab', $_REQUEST['cara01lab_debebusctrab'], true, '');
$objCombos->sino();
$html_cara01lab_debebusctrab = $objCombos->html('', $objDB);
$objCombos->nuevo('cara01lab_origendinero', $_REQUEST['cara01lab_origendinero'], true, '{' . $ETI['msg_seleccione'] . '}');
$objCombos->addArreglo($acara01lab_origendinero, $icara01lab_origendinero);
$html_cara01lab_origendinero = $objCombos->html('', $objDB);
if ($_REQUEST['cara44bienversion'] == 0) {
	$objCombos->nuevo('cara01bien_baloncesto', $_REQUEST['cara01bien_baloncesto'], true, '');
	$objCombos->sino();
	$html_cara01bien_baloncesto = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bien_voleibol', $_REQUEST['cara01bien_voleibol'], true, '');
	$objCombos->sino();
	$html_cara01bien_voleibol = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bien_futbolsala', $_REQUEST['cara01bien_futbolsala'], true, '');
	$objCombos->sino();
	$html_cara01bien_futbolsala = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bien_artesmarc', $_REQUEST['cara01bien_artesmarc'], true, '');
	$objCombos->sino();
	$html_cara01bien_artesmarc = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bien_tenisdemesa', $_REQUEST['cara01bien_tenisdemesa'], true, '');
	$objCombos->sino();
	$html_cara01bien_tenisdemesa = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bien_ajedrez', $_REQUEST['cara01bien_ajedrez'], true, '');
	$objCombos->sino();
	$html_cara01bien_ajedrez = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bien_juegosautoc', $_REQUEST['cara01bien_juegosautoc'], true, '');
	$objCombos->sino();
	$html_cara01bien_juegosautoc = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bien_interesrepdeporte', $_REQUEST['cara01bien_interesrepdeporte'], true, '');
	$objCombos->sino();
	$objCombos->sAccion = 'ajustarinteresrepdeporte();';
	$html_cara01bien_interesrepdeporte = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bien_teatro', $_REQUEST['cara01bien_teatro'], true, '');
	$objCombos->sino();
	$html_cara01bien_teatro = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bien_danza', $_REQUEST['cara01bien_danza'], true, '');
	$objCombos->sino();
	$html_cara01bien_danza = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bien_musica', $_REQUEST['cara01bien_musica'], true, '');
	$objCombos->sino();
	$html_cara01bien_musica = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bien_circo', $_REQUEST['cara01bien_circo'], true, '');
	$objCombos->sino();
	$html_cara01bien_circo = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bien_artplast', $_REQUEST['cara01bien_artplast'], true, '');
	$objCombos->sino();
	$html_cara01bien_artplast = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bien_cuenteria', $_REQUEST['cara01bien_cuenteria'], true, '');
	$objCombos->sino();
	$html_cara01bien_cuenteria = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bien_interesreparte', $_REQUEST['cara01bien_interesreparte'], true, '');
	$objCombos->sino();
	$objCombos->sAccion = 'ajustarinteresreparte()';
	$html_cara01bien_interesreparte = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bien_interpreta', $_REQUEST['cara01bien_interpreta'], true, '{' . $ETI['msg_seleccione'] . '}');
	$objCombos->addArreglo($acara01bien_interpreta, $icara01bien_interpreta);
	$objCombos->addItem('-1', 'Ninguno de estos');
	$objCombos->sAccion = 'ajustarnivelinterpreta()';
	$html_cara01bien_interpreta = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bien_nivelinter', $_REQUEST['cara01bien_nivelinter'], true, '{' . $ETI['msg_seleccione'] . '}');
	$objCombos->addArreglo($acara01bien_nivelinter, $icara01bien_nivelinter);
	$html_cara01bien_nivelinter = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bien_danza_mod', $_REQUEST['cara01bien_danza_mod'], true, '');
	$objCombos->sino();
	$objCombos->sAccion = 'ajustardanza()';
	$html_cara01bien_danza_mod = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bien_danza_clas', $_REQUEST['cara01bien_danza_clas'], true, '');
	$objCombos->sino();
	$objCombos->sAccion = 'ajustardanza()';
	$html_cara01bien_danza_clas = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bien_danza_cont', $_REQUEST['cara01bien_danza_cont'], true, '');
	$objCombos->sino();
	$objCombos->sAccion = 'ajustardanza()';
	$html_cara01bien_danza_cont = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bien_danza_folk', $_REQUEST['cara01bien_danza_folk'], true, '');
	$objCombos->sino();
	$objCombos->sAccion = 'ajustardanza()';
	$html_cara01bien_danza_folk = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bien_niveldanza', $_REQUEST['cara01bien_niveldanza'], true, '{' . $ETI['msg_seleccione'] . '}');
	$objCombos->addArreglo($acara01bien_niveldanza, $icara01bien_niveldanza);
	$html_cara01bien_niveldanza = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bien_emprendedor', $_REQUEST['cara01bien_emprendedor'], true, '');
	$objCombos->sino();
	$objCombos->sAccion = 'ajustarnombreemp()';
	$html_cara01bien_emprendedor = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bien_capacempren', $_REQUEST['cara01bien_capacempren'], true, '{' . $ETI['msg_ninguna'] . '}', 0);
	//$objCombos->sino();
	//$objCombos->sAccion='ajustartipocapacita()';
	$objCombos->addArreglo($acara01bien_capacempren, $icara01bien_capacempren);
	$html_cara01bien_capacempren = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bien_impvidasalud', $_REQUEST['cara01bien_impvidasalud'], true, '');
	$objCombos->addArreglo($acara01bien_impvidasalud, $icara01bien_impvidasalud);
	$html_cara01bien_impvidasalud = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bien_estraautocuid', $_REQUEST['cara01bien_estraautocuid'], true, '');
	$objCombos->addArreglo($acara01bien_estraautocuid, $icara01bien_estraautocuid);
	$html_cara01bien_estraautocuid = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bien_pv_personal', $_REQUEST['cara01bien_pv_personal'], true, '');
	$objCombos->addArreglo($acara01bien_pv_personal, $icara01bien_pv_personal);
	$html_cara01bien_pv_personal = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bien_pv_familiar', $_REQUEST['cara01bien_pv_familiar'], true, '');
	$objCombos->sino();
	$html_cara01bien_pv_familiar = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bien_pv_academ', $_REQUEST['cara01bien_pv_academ'], true, '');
	$objCombos->sino();
	$html_cara01bien_pv_academ = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bien_pv_labora', $_REQUEST['cara01bien_pv_labora'], true, '');
	$objCombos->sino();
	$html_cara01bien_pv_labora = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bien_pv_pareja', $_REQUEST['cara01bien_pv_pareja'], true, '');
	$objCombos->sino();
	$html_cara01bien_pv_pareja = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bien_amb', $_REQUEST['cara01bien_amb'], true, '');
	$objCombos->addArreglo($acara01bien_amb, $icara01bien_amb);
	$html_cara01bien_amb = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bien_amb_agu', $_REQUEST['cara01bien_amb_agu'], true, '');
	$objCombos->sino();
	$html_cara01bien_amb_agu = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bien_amb_bom', $_REQUEST['cara01bien_amb_bom'], true, '');
	$objCombos->sino();
	$html_cara01bien_amb_bom = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bien_amb_car', $_REQUEST['cara01bien_amb_car'], true, '');
	$objCombos->sino();
	$html_cara01bien_amb_car = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara01bien_amb_info', $_REQUEST['cara01bien_amb_info'], true, '');
	$objCombos->sino();
	//$objCombos->sAccion='ajustaramb_temas()';
	$html_cara01bien_amb_info = $objCombos->html('', $objDB);
}
$bEntra = false;
if ($_REQUEST['cara44bienversion'] == 2) {
	$bEntra = true;
}
if ($bEntra) {
	$objCombos->nuevo('cara44bienv2altoren', $_REQUEST['cara44bienv2altoren'], true, '');
	$objCombos->sino();
	$html_cara44bienv2altoren = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv2atletismo', $_REQUEST['cara44bienv2atletismo'], true, '');
	$objCombos->sino();
	$html_cara44bienv2atletismo = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv2baloncesto', $_REQUEST['cara44bienv2baloncesto'], true, '');
	$objCombos->sino();
	$html_cara44bienv2baloncesto = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv2futbol', $_REQUEST['cara44bienv2futbol'], true, '');
	$objCombos->sino();
	$html_cara44bienv2futbol = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv2gimnasia', $_REQUEST['cara44bienv2gimnasia'], true, '');
	$objCombos->sino();
	$html_cara44bienv2gimnasia = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv2natacion', $_REQUEST['cara44bienv2natacion'], true, '');
	$objCombos->sino();
	$html_cara44bienv2natacion = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv2voleibol', $_REQUEST['cara44bienv2voleibol'], true, '');
	$objCombos->sino();
	$html_cara44bienv2voleibol = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv2tenis', $_REQUEST['cara44bienv2tenis'], true, '');
	$objCombos->sino();
	$html_cara44bienv2tenis = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv2paralimpico', $_REQUEST['cara44bienv2paralimpico'], true, '');
	$objCombos->sino();
	$html_cara44bienv2paralimpico = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv2otrodeporte', $_REQUEST['cara44bienv2otrodeporte'], true, '');
	$objCombos->sino();
	$objCombos->sAccion = 'ajustarbienv2otrodeporte()';
	$html_cara44bienv2otrodeporte = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv2activdanza', $_REQUEST['cara44bienv2activdanza'], true, '');
	$objCombos->sino();
	$html_cara44bienv2activdanza = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv2activmusica', $_REQUEST['cara44bienv2activmusica'], true, '');
	$objCombos->sino();
	$html_cara44bienv2activmusica = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv2activteatro', $_REQUEST['cara44bienv2activteatro'], true, '');
	$objCombos->sino();
	$html_cara44bienv2activteatro = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv2activartes', $_REQUEST['cara44bienv2activartes'], true, '');
	$objCombos->sino();
	$html_cara44bienv2activartes = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv2activliteratura', $_REQUEST['cara44bienv2activliteratura'], true, '');
	$objCombos->sino();
	$html_cara44bienv2activliteratura = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv2activculturalotra', $_REQUEST['cara44bienv2activculturalotra'], true, '');
	$objCombos->sino();
	$objCombos->sAccion = 'ajustarcara44bienv2activculturalotra()';
	$html_cara44bienv2activculturalotra = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv2evenfestfolc', $_REQUEST['cara44bienv2evenfestfolc'], true, '');
	$objCombos->sino();
	$html_cara44bienv2evenfestfolc = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv2evenexpoarte', $_REQUEST['cara44bienv2evenexpoarte'], true, '');
	$objCombos->sino();
	$html_cara44bienv2evenexpoarte = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv2evenhistarte', $_REQUEST['cara44bienv2evenhistarte'], true, '');
	$objCombos->sino();
	$html_cara44bienv2evenhistarte = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv2evengalfoto', $_REQUEST['cara44bienv2evengalfoto'], true, '');
	$objCombos->sino();
	$html_cara44bienv2evengalfoto = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv2evenliteratura', $_REQUEST['cara44bienv2evenliteratura'], true, '');
	$objCombos->sino();
	$html_cara44bienv2evenliteratura = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv2eventeatro', $_REQUEST['cara44bienv2eventeatro'], true, '');
	$objCombos->sino();
	$html_cara44bienv2eventeatro = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv2evencine', $_REQUEST['cara44bienv2evencine'], true, '');
	$objCombos->sino();
	$html_cara44bienv2evencine = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv2evenculturalotro', $_REQUEST['cara44bienv2evenculturalotro'], true, '');
	$objCombos->sino();
	$objCombos->sAccion = 'ajustarcara44bienv2evenculturalotro()';
	$html_cara44bienv2evenculturalotro = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv2emprendimiento', $_REQUEST['cara44bienv2emprendimiento'], true, '');
	$objCombos->sino();
	$html_cara44bienv2emprendimiento = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv2empresa', $_REQUEST['cara44bienv2empresa'], true, '');
	$objCombos->sino();
	$html_cara44bienv2empresa = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv2emprenrecursos', $_REQUEST['cara44bienv2emprenrecursos'], true, '');
	$objCombos->sino();
	$html_cara44bienv2emprenrecursos = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv2emprenconocim', $_REQUEST['cara44bienv2emprenconocim'], true, '');
	$objCombos->sino();
	$html_cara44bienv2emprenconocim = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv2emprenplan', $_REQUEST['cara44bienv2emprenplan'], true, '');
	$objCombos->sino();
	$html_cara44bienv2emprenplan = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv2emprenejecutar', $_REQUEST['cara44bienv2emprenejecutar'], true, '');
	$objCombos->sino();
	$html_cara44bienv2emprenejecutar = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv2emprenfortconocim', $_REQUEST['cara44bienv2emprenfortconocim'], true, '');
	$objCombos->sino();
	$html_cara44bienv2emprenfortconocim = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv2emprenidentproblema', $_REQUEST['cara44bienv2emprenidentproblema'], true, '');
	$objCombos->sino();
	$html_cara44bienv2emprenidentproblema = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv2emprenotro', $_REQUEST['cara44bienv2emprenotro'], true, '');
	$objCombos->sino();
	$objCombos->sAccion = 'ajustarcara44bienv2emprenotro()';
	$html_cara44bienv2emprenotro = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv2emprenmarketing', $_REQUEST['cara44bienv2emprenmarketing'], true, '');
	$objCombos->sino();
	$html_cara44bienv2emprenmarketing = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv2emprenplannegocios', $_REQUEST['cara44bienv2emprenplannegocios'], true, '');
	$objCombos->sino();
	$html_cara44bienv2emprenplannegocios = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv2emprenideas', $_REQUEST['cara44bienv2emprenideas'], true, '');
	$objCombos->sino();
	$html_cara44bienv2emprenideas = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv2emprencreacion', $_REQUEST['cara44bienv2emprencreacion'], true, '');
	$objCombos->sino();
	$html_cara44bienv2emprencreacion = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv2saludfacteconom', $_REQUEST['cara44bienv2saludfacteconom'], true, '');
	$objCombos->sino();
	$html_cara44bienv2saludfacteconom = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv2saludpreocupacion', $_REQUEST['cara44bienv2saludpreocupacion'], true, '');
	$objCombos->sino();
	$html_cara44bienv2saludpreocupacion = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv2saludconsumosust', $_REQUEST['cara44bienv2saludconsumosust'], true, '');
	$objCombos->sino();
	$html_cara44bienv2saludconsumosust = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv2saludinsomnio', $_REQUEST['cara44bienv2saludinsomnio'], true, '');
	$objCombos->sino();
	$html_cara44bienv2saludinsomnio = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv2saludclimalab', $_REQUEST['cara44bienv2saludclimalab'], true, '');
	$objCombos->sino();
	$html_cara44bienv2saludclimalab = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv2saludalimenta', $_REQUEST['cara44bienv2saludalimenta'], true, '');
	$objCombos->sino();
	$html_cara44bienv2saludalimenta = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv2saludemocion', $_REQUEST['cara44bienv2saludemocion'], true, '');
	$objCombos->sino();
	$html_cara44bienv2saludemocion = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv2saludestado', $_REQUEST['cara44bienv2saludestado'], true, '');
	$objCombos->sino();
	$html_cara44bienv2saludestado = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv2saludmedita', $_REQUEST['cara44bienv2saludmedita'], true, '');
	$objCombos->sino();
	$html_cara44bienv2saludmedita = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv2crecimedusexual', $_REQUEST['cara44bienv2crecimedusexual'], true, '');
	$objCombos->sino();
	$html_cara44bienv2crecimedusexual = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv2crecimcultciudad', $_REQUEST['cara44bienv2crecimcultciudad'], true, '');
	$objCombos->sino();
	$html_cara44bienv2crecimcultciudad = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv2crecimrelpareja', $_REQUEST['cara44bienv2crecimrelpareja'], true, '');
	$objCombos->sino();
	$html_cara44bienv2crecimrelpareja = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv2crecimrelinterp', $_REQUEST['cara44bienv2crecimrelinterp'], true, '');
	$objCombos->sino();
	$html_cara44bienv2crecimrelinterp = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv2crecimdinamicafam', $_REQUEST['cara44bienv2crecimdinamicafam'], true, '');
	$objCombos->sino();
	$html_cara44bienv2crecimdinamicafam = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv2crecimautoestima', $_REQUEST['cara44bienv2crecimautoestima'], true, '');
	$objCombos->sino();
	$html_cara44bienv2crecimautoestima = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv2creciminclusion', $_REQUEST['cara44bienv2creciminclusion'], true, '');
	$objCombos->sino();
	$html_cara44bienv2creciminclusion = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv2creciminteliemoc', $_REQUEST['cara44bienv2creciminteliemoc'], true, '');
	$objCombos->sino();
	$html_cara44bienv2creciminteliemoc = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv2crecimcultural', $_REQUEST['cara44bienv2crecimcultural'], true, '');
	$objCombos->sino();
	$html_cara44bienv2crecimcultural = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv2crecimartistico', $_REQUEST['cara44bienv2crecimartistico'], true, '');
	$objCombos->sino();
	$html_cara44bienv2crecimartistico = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv2crecimdeporte', $_REQUEST['cara44bienv2crecimdeporte'], true, '');
	$objCombos->sino();
	$html_cara44bienv2crecimdeporte = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv2crecimambiente', $_REQUEST['cara44bienv2crecimambiente'], true, '');
	$objCombos->sino();
	$html_cara44bienv2crecimambiente = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv2crecimhabsocio', $_REQUEST['cara44bienv2crecimhabsocio'], true, '');
	$objCombos->sino();
	$html_cara44bienv2crecimhabsocio = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv2ambienbasura', $_REQUEST['cara44bienv2ambienbasura'], true, '');
	$objCombos->sino();
	$html_cara44bienv2ambienbasura = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv2ambienreutiliza', $_REQUEST['cara44bienv2ambienreutiliza'], true, '');
	$objCombos->sino();
	$html_cara44bienv2ambienreutiliza = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv2ambienluces', $_REQUEST['cara44bienv2ambienluces'], true, '');
	$objCombos->sino();
	$html_cara44bienv2ambienluces = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv2ambienfrutaverd', $_REQUEST['cara44bienv2ambienfrutaverd'], true, '');
	$objCombos->sino();
	$html_cara44bienv2ambienfrutaverd = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv2ambienenchufa', $_REQUEST['cara44bienv2ambienenchufa'], true, '');
	$objCombos->sino();
	$html_cara44bienv2ambienenchufa = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv2ambiengrifo', $_REQUEST['cara44bienv2ambiengrifo'], true, '');
	$objCombos->sino();
	$html_cara44bienv2ambiengrifo = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv2ambienbicicleta', $_REQUEST['cara44bienv2ambienbicicleta'], true, '');
	$objCombos->sino();
	$html_cara44bienv2ambienbicicleta = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv2ambientranspub', $_REQUEST['cara44bienv2ambientranspub'], true, '');
	$objCombos->sino();
	$html_cara44bienv2ambientranspub = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv2ambienducha', $_REQUEST['cara44bienv2ambienducha'], true, '');
	$objCombos->sino();
	$html_cara44bienv2ambienducha = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv2ambiencaminata', $_REQUEST['cara44bienv2ambiencaminata'], true, '');
	$objCombos->sino();
	$html_cara44bienv2ambiencaminata = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv2ambiensiembra', $_REQUEST['cara44bienv2ambiensiembra'], true, '');
	$objCombos->sino();
	$html_cara44bienv2ambiensiembra = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv2ambienconferencia', $_REQUEST['cara44bienv2ambienconferencia'], true, '');
	$objCombos->sino();
	$html_cara44bienv2ambienconferencia = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv2ambienrecicla', $_REQUEST['cara44bienv2ambienrecicla'], true, '');
	$objCombos->sino();
	$html_cara44bienv2ambienrecicla = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv2ambienotraactiv', $_REQUEST['cara44bienv2ambienotraactiv'], true, '');
	$objCombos->sino();
	$objCombos->sAccion = 'ajustarcara44bienv2ambienotraactiv()';
	$html_cara44bienv2ambienotraactiv = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv2ambienreforest', $_REQUEST['cara44bienv2ambienreforest'], true, '');
	$objCombos->sino();
	$html_cara44bienv2ambienreforest = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv2ambienmovilidad', $_REQUEST['cara44bienv2ambienmovilidad'], true, '');
	$objCombos->sino();
	$html_cara44bienv2ambienmovilidad = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv2ambienclimatico', $_REQUEST['cara44bienv2ambienclimatico'], true, '');
	$objCombos->sino();
	$html_cara44bienv2ambienclimatico = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv2ambienecofemin', $_REQUEST['cara44bienv2ambienecofemin'], true, '');
	$objCombos->sino();
	$html_cara44bienv2ambienecofemin = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv2ambienbiodiver', $_REQUEST['cara44bienv2ambienbiodiver'], true, '');
	$objCombos->sino();
	$html_cara44bienv2ambienbiodiver = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv2ambienecologia', $_REQUEST['cara44bienv2ambienecologia'], true, '');
	$objCombos->sino();
	$html_cara44bienv2ambienecologia = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv2ambieneconomia', $_REQUEST['cara44bienv2ambieneconomia'], true, '');
	$objCombos->sino();
	$html_cara44bienv2ambieneconomia = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv2ambienrecnatura', $_REQUEST['cara44bienv2ambienrecnatura'], true, '');
	$objCombos->sino();
	$html_cara44bienv2ambienrecnatura = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv2ambienreciclaje', $_REQUEST['cara44bienv2ambienreciclaje'], true, '');
	$objCombos->sino();
	$html_cara44bienv2ambienreciclaje = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv2ambienmascota', $_REQUEST['cara44bienv2ambienmascota'], true, '');
	$objCombos->sino();
	$html_cara44bienv2ambienmascota = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv2ambiencartohum', $_REQUEST['cara44bienv2ambiencartohum'], true, '');
	$objCombos->sino();
	$html_cara44bienv2ambiencartohum = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv2ambienespiritu', $_REQUEST['cara44bienv2ambienespiritu'], true, '');
	$objCombos->sino();
	$html_cara44bienv2ambienespiritu = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv2ambiencarga', $_REQUEST['cara44bienv2ambiencarga'], true, '');
	$objCombos->sino();
	$html_cara44bienv2ambiencarga = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv2ambienotroenfoq', $_REQUEST['cara44bienv2ambienotroenfoq'], true, '');
	$objCombos->sino();
	$objCombos->sAccion = 'ajustarcara44bienv2ambienotroenfoq()';
	$html_cara44bienv2ambienotroenfoq = $objCombos->html('', $objDB);
}
$bEntra = false;
if ($_REQUEST['cara44bienversion'] == 3) {
	$bEntra = true;
}
if ($bEntra) {
	$objCombos->nuevo('cara44bienv3emprenetapa', $_REQUEST['cara44bienv3emprenetapa'], true, '{' . $ETI['msg_seleccione'] . '}', 0);
	$objCombos->addArreglo($acara44bienv3emprenetapa, $icara44bienv3emprenetapa);
	$html_cara44bienv3emprenetapa = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv3emprennecesita', $_REQUEST['cara44bienv3emprennecesita'], true, '{' . $ETI['msg_seleccione'] . '}', 0);
	$objCombos->addArreglo($acara44bienv3emprennecesita, $icara44bienv3emprennecesita);
	$html_cara44bienv3emprennecesita = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv3emprenanioini', $_REQUEST['cara44bienv3emprenanioini'], true, '{' . $ETI['msg_seleccione'] . '}', 0);
	$objCombos->addArreglo($acara44bienv3emprenanioini, $icara44bienv3emprenanioini);
	$html_cara44bienv3emprenanioini = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv3emprensector', $_REQUEST['cara44bienv3emprensector'], true, '{' . $ETI['msg_seleccione'] . '}', 0);
	$objCombos->addArreglo($acara44bienv3emprensector, $icara44bienv3emprensector);
	$objCombos->sAccion = 'ajustar_cara44bienv3emprensector()';
	$html_cara44bienv3emprensector = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv3emprentemas', $_REQUEST['cara44bienv3emprentemas'], true, '{' . $ETI['msg_seleccione'] . '}', 0);
	$objCombos->addArreglo($acara44bienv3emprentemas, $icara44bienv3emprentemas);
	$html_cara44bienv3emprentemas = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv3ambienclima', $_REQUEST['cara44bienv3ambienclima'], true, '');
	$objCombos->sino($ETI['si'], $ETI['no'], 1, 0);
	$html_cara44bienv3ambienclima = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv3ambienjusticia', $_REQUEST['cara44bienv3ambienjusticia'], true, '');
	$objCombos->sino($ETI['si'], $ETI['no'], 1, 0);
	$html_cara44bienv3ambienjusticia = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv3ambienagroeco', $_REQUEST['cara44bienv3ambienagroeco'], true, '');
	$objCombos->sino($ETI['si'], $ETI['no'], 1, 0);
	$html_cara44bienv3ambienagroeco = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv3ambieneconomia', $_REQUEST['cara44bienv3ambieneconomia'], true, '');
	$objCombos->sino($ETI['si'], $ETI['no'], 1, 0);
	$html_cara44bienv3ambieneconomia = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv3ambieneducacion', $_REQUEST['cara44bienv3ambieneducacion'], true, '');
	$objCombos->sino($ETI['si'], $ETI['no'], 1, 0);
	$html_cara44bienv3ambieneducacion = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv3ambienbiodiverso', $_REQUEST['cara44bienv3ambienbiodiverso'], true, '');
	$objCombos->sino($ETI['si'], $ETI['no'], 1, 0);
	$html_cara44bienv3ambienbiodiverso = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv3ambienecoturismo', $_REQUEST['cara44bienv3ambienecoturismo'], true, '');
	$objCombos->sino($ETI['si'], $ETI['no'], 1, 0);
	$html_cara44bienv3ambienecoturismo = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv3ambienotro', $_REQUEST['cara44bienv3ambienotro'], true, '');
	$objCombos->sino($ETI['si'], $ETI['no'], 1, 0);
	$objCombos->sAccion = 'ajustar_cara44bienv3ambienotro()';
	$html_cara44bienv3ambienotro = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv3ambienexper', $_REQUEST['cara44bienv3ambienexper'], true, '{' . $ETI['msg_seleccione'] . '}', 0);
	$objCombos->addArreglo($acara44bienv3ambienexper, $icara44bienv3ambienexper);
	$html_cara44bienv3ambienexper = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv3ambienaprende', $_REQUEST['cara44bienv3ambienaprende'], true, '{' . $ETI['msg_seleccione'] . '}', 0);
	$objCombos->addArreglo($acara44bienv3ambienaprende, $icara44bienv3ambienaprende);
	$html_cara44bienv3ambienaprende = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv3ambienestudiante', $_REQUEST['cara44bienv3ambienestudiante'], true, '{' . $ETI['msg_seleccione'] . '}', 0);
	$objCombos->addArreglo($acara44bienv3ambienestudiante, $icara44bienv3ambienestudiante);
	$html_cara44bienv3ambienestudiante = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv3ambienactividad', $_REQUEST['cara44bienv3ambienactividad'], true, '{' . $ETI['msg_seleccione'] . '}', 0);
	$objCombos->addArreglo($acara44bienv3ambienactividad, $icara44bienv3ambienactividad);
	$html_cara44bienv3ambienactividad = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv3pyphabitoalim', $_REQUEST['cara44bienv3pyphabitoalim'], true, '{' . $ETI['msg_seleccione'] . '}', 0);
	$objCombos->addArreglo($acara44bienv3pyphabitoalim, $icara44bienv3pyphabitoalim);
	$html_cara44bienv3pyphabitoalim = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv3pypsustanciapsico', $_REQUEST['cara44bienv3pypsustanciapsico'], true, '{' . $ETI['msg_seleccione'] . '}', 0);
	$objCombos->addArreglo($acara44bienv3pypsustanciapsico, $icara44bienv3pypsustanciapsico);
	$html_cara44bienv3pypsustanciapsico = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv3pypsaludvisual', $_REQUEST['cara44bienv3pypsaludvisual'], true, '{' . $ETI['msg_seleccione'] . '}', 0);
	$objCombos->addArreglo($acara44bienv3pypsaludvisual, $icara44bienv3pypsaludvisual);
	$html_cara44bienv3pypsaludvisual = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv3pypsaludbucal', $_REQUEST['cara44bienv3pypsaludbucal'], true, '{' . $ETI['msg_seleccione'] . '}', 0);
	$objCombos->addArreglo($acara44bienv3pypsaludbucal, $icara44bienv3pypsaludbucal);
	$html_cara44bienv3pypsaludbucal = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv3pypsaludsexual', $_REQUEST['cara44bienv3pypsaludsexual'], true, '{' . $ETI['msg_seleccione'] . '}', 0);
	$objCombos->addArreglo($acara44bienv3pypsaludsexual, $icara44bienv3pypsaludsexual);
	$html_cara44bienv3pypsaludsexual = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv3deportenivel', $_REQUEST['cara44bienv3deportenivel'], true, '{' . $ETI['msg_seleccione'] . '}', 0);
	$objCombos->addArreglo($acara44bienv3deportenivel, $icara44bienv3deportenivel);
	$objCombos->sAccion = 'ajustar_cara44bienv3deportenivel()';
	$html_cara44bienv3deportenivel = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv3deportefrec', $_REQUEST['cara44bienv3deportefrec'], true, '{' . $ETI['msg_seleccione'] . '}', 0);
	$objCombos->addArreglo($acara44bienv3deportefrec, $icara44bienv3deportefrec);
	$html_cara44bienv3deportefrec = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv3deportecual', $_REQUEST['cara44bienv3deportecual'], true, '{' . $ETI['msg_seleccione'] . '}', 0);
	$objCombos->addArreglo($acara44bienv3deportecual, $icara44bienv3deportecual);
	$objCombos->sAccion = 'ajustar_cara44bienv3deportecual()';
	$html_cara44bienv3deportecual = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv3deporterecrea', $_REQUEST['cara44bienv3deporterecrea'], true, '{' . $ETI['msg_seleccione'] . '}', 0);
	$objCombos->addArreglo($acara44bienv3deporterecrea, $icara44bienv3deporterecrea);
	$html_cara44bienv3deporterecrea = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv3deporteunad', $_REQUEST['cara44bienv3deporteunad'], true, '');
	$objCombos->sino($ETI['si'], $ETI['no'], 1, 0);
	$html_cara44bienv3deporteunad = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv3creciminclusion', $_REQUEST['cara44bienv3creciminclusion'], true, '');
	$objCombos->sino($ETI['si'], $ETI['no'], 1, 0);
	$html_cara44bienv3creciminclusion = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv3crecimfamilia', $_REQUEST['cara44bienv3crecimfamilia'], true, '');
	$objCombos->sino($ETI['si'], $ETI['no'], 1, 0);
	$html_cara44bienv3crecimfamilia = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv3crecimhabilidad', $_REQUEST['cara44bienv3crecimhabilidad'], true, '');
	$objCombos->sino($ETI['si'], $ETI['no'], 1, 0);
	$html_cara44bienv3crecimhabilidad = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv3crecimempleable', $_REQUEST['cara44bienv3crecimempleable'], true, '');
	$objCombos->sino($ETI['si'], $ETI['no'], 1, 0);
	$html_cara44bienv3crecimempleable = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv3crecimhabilvida', $_REQUEST['cara44bienv3crecimhabilvida'], true, '');
	$objCombos->sino($ETI['si'], $ETI['no'], 1, 0);
	$html_cara44bienv3crecimhabilvida = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv3crecimespiritual', $_REQUEST['cara44bienv3crecimespiritual'], true, '');
	$objCombos->sino($ETI['si'], $ETI['no'], 1, 0);
	$html_cara44bienv3crecimespiritual = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv3crecimpractica', $_REQUEST['cara44bienv3crecimpractica'], true, '');
	$objCombos->sino($ETI['si'], $ETI['no'], 1, 0);
	$html_cara44bienv3crecimpractica = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv3crecimliderazgo', $_REQUEST['cara44bienv3crecimliderazgo'], true, '');
	$objCombos->sino($ETI['si'], $ETI['no'], 1, 0);
	$html_cara44bienv3crecimliderazgo = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv3crecimtrabequipo', $_REQUEST['cara44bienv3crecimtrabequipo'], true, '');
	$objCombos->sino($ETI['si'], $ETI['no'], 1, 0);
	$html_cara44bienv3crecimtrabequipo = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv3crecimasertiva', $_REQUEST['cara44bienv3crecimasertiva'], true, '');
	$objCombos->sino($ETI['si'], $ETI['no'], 1, 0);
	$html_cara44bienv3crecimasertiva = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv3crecimgesttiempo', $_REQUEST['cara44bienv3crecimgesttiempo'], true, '');
	$objCombos->sino($ETI['si'], $ETI['no'], 1, 0);
	$html_cara44bienv3crecimgesttiempo = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv3crecimconflictos', $_REQUEST['cara44bienv3crecimconflictos'], true, '');
	$objCombos->sino($ETI['si'], $ETI['no'], 1, 0);
	$html_cara44bienv3crecimconflictos = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv3crecimadapcambio', $_REQUEST['cara44bienv3crecimadapcambio'], true, '');
	$objCombos->sino($ETI['si'], $ETI['no'], 1, 0);
	$html_cara44bienv3crecimadapcambio = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv3crecimempatia', $_REQUEST['cara44bienv3crecimempatia'], true, '');
	$objCombos->sino($ETI['si'], $ETI['no'], 1, 0);
	$html_cara44bienv3crecimempatia = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv3crecimgestionser', $_REQUEST['cara44bienv3crecimgestionser'], true, '');
	$objCombos->sino($ETI['si'], $ETI['no'], 1, 0);
	$html_cara44bienv3crecimgestionser = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv3crecimtomadecide', $_REQUEST['cara44bienv3crecimtomadecide'], true, '');
	$objCombos->sino($ETI['si'], $ETI['no'], 1, 0);
	$html_cara44bienv3crecimtomadecide = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv3crecimpenscreativo', $_REQUEST['cara44bienv3crecimpenscreativo'], true, '');
	$objCombos->sino($ETI['si'], $ETI['no'], 1, 0);
	$html_cara44bienv3crecimpenscreativo = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv3crecimpenscritico', $_REQUEST['cara44bienv3crecimpenscritico'], true, '');
	$objCombos->sino($ETI['si'], $ETI['no'], 1, 0);
	$html_cara44bienv3crecimpenscritico = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv3crecimhabilotro', $_REQUEST['cara44bienv3crecimhabilotro'], true, '');
	$objCombos->sino($ETI['si'], $ETI['no'], 1, 0);
	$objCombos->sAccion = 'ajustar_cara44bienv3crecimhabilotro()';
	$html_cara44bienv3crecimhabilotro = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv3crecimalcancemeta', $_REQUEST['cara44bienv3crecimalcancemeta'], true, '');
	$objCombos->sino($ETI['si'], $ETI['no'], 1, 0);
	$html_cara44bienv3crecimalcancemeta = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv3crecimsatifpersonal', $_REQUEST['cara44bienv3crecimsatifpersonal'], true, '');
	$objCombos->sino($ETI['si'], $ETI['no'], 1, 0);
	$html_cara44bienv3crecimsatifpersonal = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv3crecimaccesolaboral', $_REQUEST['cara44bienv3crecimaccesolaboral'], true, '');
	$objCombos->sino($ETI['si'], $ETI['no'], 1, 0);
	$html_cara44bienv3crecimaccesolaboral = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv3crecimotramotiv', $_REQUEST['cara44bienv3crecimotramotiv'], true, '');
	$objCombos->sino($ETI['si'], $ETI['no'], 1, 0);
	$objCombos->sAccion = 'ajustar_cara44bienv3crecimotramotiv()';
	$html_cara44bienv3crecimotramotiv = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv3crecimapoyo', $_REQUEST['cara44bienv3crecimapoyo'], true, '{' . $ETI['msg_seleccione'] . '}', 0);
	$objCombos->addArreglo($acara44bienv3crecimapoyo, $icara44bienv3crecimapoyo);
	$html_cara44bienv3crecimapoyo = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv3crecimlaboral', $_REQUEST['cara44bienv3crecimlaboral'], true, '{' . $ETI['msg_seleccione'] . '}', 0);
	$objCombos->addArreglo($acara44bienv3crecimlaboral, $icara44bienv3crecimlaboral);
	$html_cara44bienv3crecimlaboral = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv3mentalcuidado', $_REQUEST['cara44bienv3mentalcuidado'], true, '{' . $ETI['msg_seleccione'] . '}', 0);
	$objCombos->addArreglo($acara44bienv3mentalcuidado, $icara44bienv3mentalcuidado);
	$html_cara44bienv3mentalcuidado = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv3mentalestrategia', $_REQUEST['cara44bienv3mentalestrategia'], true, '{' . $ETI['msg_seleccione'] . '}', 0);
	$objCombos->addArreglo($acara44bienv3mentalestrategia, $icara44bienv3mentalestrategia);
	$html_cara44bienv3mentalestrategia = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv3mentalestres', $_REQUEST['cara44bienv3mentalestres'], true, '');
	$objCombos->sino($ETI['si'], $ETI['no'], 1, 0);
	$html_cara44bienv3mentalestres = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv3mentalansiedad', $_REQUEST['cara44bienv3mentalansiedad'], true, '');
	$objCombos->sino($ETI['si'], $ETI['no'], 1, 0);
	$html_cara44bienv3mentalansiedad = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv3mentaldepresion', $_REQUEST['cara44bienv3mentaldepresion'], true, '');
	$objCombos->sino($ETI['si'], $ETI['no'], 1, 0);
	$html_cara44bienv3mentaldepresion = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv3mentalautoconoc', $_REQUEST['cara44bienv3mentalautoconoc'], true, '');
	$objCombos->sino($ETI['si'], $ETI['no'], 1, 0);
	$html_cara44bienv3mentalautoconoc = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv3mentalmindfulness', $_REQUEST['cara44bienv3mentalmindfulness'], true, '');
	$objCombos->sino($ETI['si'], $ETI['no'], 1, 0);
	$html_cara44bienv3mentalmindfulness = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv3mentalautoestima', $_REQUEST['cara44bienv3mentalautoestima'], true, '');
	$objCombos->sino($ETI['si'], $ETI['no'], 1, 0);
	$html_cara44bienv3mentalautoestima = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv3mentalcrisis', $_REQUEST['cara44bienv3mentalcrisis'], true, '');
	$objCombos->sino($ETI['si'], $ETI['no'], 1, 0);
	$html_cara44bienv3mentalcrisis = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv3mentalburnout', $_REQUEST['cara44bienv3mentalburnout'], true, '');
	$objCombos->sino($ETI['si'], $ETI['no'], 1, 0);
	$html_cara44bienv3mentalburnout = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv3mentalsexualidad', $_REQUEST['cara44bienv3mentalsexualidad'], true, '');
	$objCombos->sino($ETI['si'], $ETI['no'], 1, 0);
	$html_cara44bienv3mentalsexualidad = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv3mentalusoredes', $_REQUEST['cara44bienv3mentalusoredes'], true, '');
	$objCombos->sino($ETI['si'], $ETI['no'], 1, 0);
	$html_cara44bienv3mentalusoredes = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv3mentalinclusion', $_REQUEST['cara44bienv3mentalinclusion'], true, '');
	$objCombos->sino($ETI['si'], $ETI['no'], 1, 0);
	$html_cara44bienv3mentalinclusion = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv3mentalactividad', $_REQUEST['cara44bienv3mentalactividad'], true, '{' . $ETI['msg_seleccione'] . '}', 0);
	$objCombos->addArreglo($acara44bienv3mentalactividad, $icara44bienv3mentalactividad);
	$html_cara44bienv3mentalactividad = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv3mentalacompana', $_REQUEST['cara44bienv3mentalacompana'], true, '');
	$objCombos->sino($ETI['si'], $ETI['no'], 1, 0);
	$html_cara44bienv3mentalacompana = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv3mentaldiagnostico', $_REQUEST['cara44bienv3mentaldiagnostico'], true, '');
	$objCombos->sino($ETI['si'], $ETI['no'], 1, 0);
	$objCombos->sAccion = 'ajustar_cara44bienv3mentaldiagnostico()';
	$html_cara44bienv3mentaldiagnostico = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv3mentaldiagcual', $_REQUEST['cara44bienv3mentaldiagcual'], true, '{' . $ETI['msg_seleccione'] . '}', 0);
	$objCombos->addArreglo($acara44bienv3mentaldiagcual, $icara44bienv3mentaldiagcual);
	$objCombos->sAccion = 'ajustar_cara44bienv3mentaldiagcual()';
	$html_cara44bienv3mentaldiagcual = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv3arteintegrar', $_REQUEST['cara44bienv3arteintegrar'], true, '{' . $ETI['msg_seleccione'] . '}', 0);
	$objCombos->addArreglo($acara44bienv3arteintegrar, $icara44bienv3arteintegrar);
	$html_cara44bienv3arteintegrar = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv3arteformacion', $_REQUEST['cara44bienv3arteformacion'], true, '{' . $ETI['msg_seleccione'] . '}', 0);
	$objCombos->addArreglo($acara44bienv3arteformacion, $icara44bienv3arteformacion);
	$html_cara44bienv3arteformacion = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv3arteunad', $_REQUEST['cara44bienv3arteunad'], true, '');
	$objCombos->sino($ETI['si'], $ETI['no'], 1, 0);
	$html_cara44bienv3arteunad = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara44bienv3arteinformacion', $_REQUEST['cara44bienv3arteinformacion'], true, '{' . $ETI['msg_seleccione'] . '}', 0);
	$objCombos->addArreglo($acara44bienv3arteinformacion, $icara44bienv3arteinformacion);
	$html_cara44bienv3arteinformacion = $objCombos->html('', $objDB);
}
$objCombos->nuevo('cara01psico_costoemocion', $_REQUEST['cara01psico_costoemocion'], true, '{' . $ETI['msg_seleccione'] . '}');
$objCombos->addArreglo($aCAEN, $iCAEN);
$html_cara01psico_costoemocion = $objCombos->html('', $objDB);
$objCombos->nuevo('cara01psico_reaccionimpre', $_REQUEST['cara01psico_reaccionimpre'], true, '{' . $ETI['msg_seleccione'] . '}');
$objCombos->addArreglo($apsico_reaccionimpre, $ipsico_reaccionimpre);
$html_cara01psico_reaccionimpre = $objCombos->html('', $objDB);
$objCombos->nuevo('cara01psico_estres', $_REQUEST['cara01psico_estres'], true, '{' . $ETI['msg_seleccione'] . '}');
$objCombos->addArreglo($apsico_estres, $ipsico_estres);
$html_cara01psico_estres = $objCombos->html('', $objDB);
$objCombos->nuevo('cara01psico_pocotiempo', $_REQUEST['cara01psico_pocotiempo'], true, '{' . $ETI['msg_seleccione'] . '}');
$objCombos->addArreglo($apsico_pocotiempo, $ipsico_pocotiempo);
$html_cara01psico_pocotiempo = $objCombos->html('', $objDB);
$objCombos->nuevo('cara01psico_actitudvida', $_REQUEST['cara01psico_actitudvida'], true, '{' . $ETI['msg_seleccione'] . '}');
$objCombos->addArreglo($apsico_actitudvida, $ipsico_actitudvida);
$html_cara01psico_actitudvida = $objCombos->html('', $objDB);
$objCombos->nuevo('cara01psico_duda', $_REQUEST['cara01psico_duda'], true, '{' . $ETI['msg_seleccione'] . '}');
$objCombos->addArreglo($apsico_duda, $ipsico_duda);
$html_cara01psico_duda = $objCombos->html('', $objDB);
$objCombos->nuevo('cara01psico_problemapers', $_REQUEST['cara01psico_problemapers'], true, '{' . $ETI['msg_seleccione'] . '}');
$objCombos->addArreglo($apsico_problemapers, $ipsico_problemapers);
$html_cara01psico_problemapers = $objCombos->html('', $objDB);
$objCombos->nuevo('cara01psico_satisfaccion', $_REQUEST['cara01psico_satisfaccion'], true, '{' . $ETI['msg_seleccione'] . '}');
$objCombos->addArreglo($apsico_satisfaccion, $ipsico_satisfaccion);
$html_cara01psico_satisfaccion = $objCombos->html('', $objDB);
$objCombos->nuevo('cara01psico_discusiones', $_REQUEST['cara01psico_discusiones'], true, '{' . $ETI['msg_seleccione'] . '}');
$objCombos->addArreglo($apsico_discusiones, $ipsico_discusiones);
$html_cara01psico_discusiones = $objCombos->html('', $objDB);
$objCombos->nuevo('cara01psico_atencion', $_REQUEST['cara01psico_atencion'], true, '{' . $ETI['msg_seleccione'] . '}');
$objCombos->addArreglo($apsico_atencion, $ipsico_atencion);
$html_cara01psico_atencion = $objCombos->html('', $objDB);
list($cara01idconsejero_rs, $_REQUEST['cara01idconsejero'], $_REQUEST['cara01idconsejero_td'], $_REQUEST['cara01idconsejero_doc']) = html_tercero($_REQUEST['cara01idconsejero_td'], $_REQUEST['cara01idconsejero_doc'], $_REQUEST['cara01idconsejero'], 0, $objDB);
$bOculto = true;
$html_cara01idconsejero = html_DivTerceroV8('cara01idconsejero', $_REQUEST['cara01idconsejero_td'], $_REQUEST['cara01idconsejero_doc'], $bOculto, $objDB, $objCombos, 0, $ETI['ing_doc']);
$objCombos->nuevo('cara01perayuda', $_REQUEST['cara01perayuda'], true, '{' . $ETI['msg_ninguno'] . '}', 0);
$iVersion = 1;
if ($_REQUEST['cara01discversion'] == 2) {
	$iVersion = 2;
}
$sAdd = ' cara14version=' . $iVersion . ' AND cara14activa="S"';
if ($_REQUEST['cara01completa'] == 'S') {
	$sAdd = 'cara14id=' . $_REQUEST['cara01perayuda'] . '';
}
$sSQL = 'SELECT cara14id AS id, cara14nombre AS nombre FROM cara14ayudaajuste WHERE ' . $sAdd . ' ORDER BY cara14orden, cara14nombre';
$tabla = $objDB->ejecutasql($sSQL);
while ($fila = $objDB->sf($tabla)) {
	$objCombos->addItem($fila['id'], $fila['nombre']);
}
$objCombos->addItem('-1', $ETI['msg_otro']);
$objCombos->sAccion = 'ajustar_cara01criteriodesc()';
$html_cara01perayuda = $objCombos->html('', $objDB);
$objCombos->nuevo('cara01criteriodesc', $_REQUEST['cara01criteriodesc'], true, '{' . $ETI['msg_seleccione'] . '}');
//$objCombos->addArreglo($acara01criteriodesc, $icara01criteriodesc);
$html_cara01criteriodesc = $objCombos->html('', $objDB);
//$objCombos->nuevo('cara01desertor', $_REQUEST['cara01desertor'], false);
//$objCombos->sino();
//$html_cara01desertor=$objCombos->html('', $objDB);
$cara01idprograma_nombre = '&nbsp;';
if ($_REQUEST['cara01idprograma'] > 0) {
	list($cara01idprograma_nombre, $sErrorDet) = tabla_campoxid('core09programa', 'core09nombre', 'core09id', $_REQUEST['cara01idprograma'], '{' . $ETI['msg_sindato'] . '}', $objDB);
}
$html_cara01idprograma = html_oculto('cara01idprograma', $_REQUEST['cara01idprograma'], $cara01idprograma_nombre);
$cara01idescuela_nombre = '&nbsp;';
if ($_REQUEST['cara01idprograma'] > 0) {
	list($cara01idescuela_nombre, $sErrorDet) = tabla_campoxid('core12escuela', 'core12nombre', 'core12id', $_REQUEST['cara01idescuela'], '{' . $ETI['msg_sindato'] . '}', $objDB);
}
$html_cara01idescuela = html_oculto('cara01idescuela', $_REQUEST['cara01idescuela'], $cara01idescuela_nombre);
$et_cara01desertor = $ETI['no'];
if ($_REQUEST['cara01desertor'] == 'S') {
	$et_cara01desertor = $ETI['si'];
}
$html_cara01desertor = html_oculto('cara01desertor', $_REQUEST['cara01desertor'], $et_cara01desertor);
$objCombos->nuevo('cara01factorprincipaldesc', $_REQUEST['cara01factorprincipaldesc'], true, '{' . $ETI['msg_seleccione'] . '}');
//$objCombos->addArreglo($acara01factorprincipaldesc, $icara01factorprincipaldesc);
$html_cara01factorprincipaldesc = $objCombos->html('', $objDB);
$bEntra = false;
if ($_REQUEST['cara01discversion'] == 1) {
	$bEntra = true;
}
if ($_REQUEST['cara01discversion'] == 2) {
	$bEntra = true;
}
if ($bEntra) {
	$sAdd = ' AND cara37version=' . $_REQUEST['cara01discversion'] . ' AND cara37activa="S"';
	if ($bEstudiante) {
		if ($_REQUEST['cara01completa'] == 'S') {
			$sAdd = ' AND cara37id=' . $_REQUEST['cara01discv2sensorial'] . '';
		}
	}
	$objCombos->nuevo('cara01discv2sensorial', $_REQUEST['cara01discv2sensorial'], true, '{' . $ETI['msg_ninguna'] . '}', 0);
	$sSQL = 'SELECT cara37id AS id, cara37nombre AS nombre FROM cara37discapacidades WHERE cara37tipodisc=1' . $sAdd . ' ORDER BY cara37orden, cara37nombre';
	$html_cara01discv2sensorial = $objCombos->html($sSQL, $objDB);
	if ($bEstudiante) {
		if ($_REQUEST['cara01completa'] == 'S') {
			$sAdd = ' AND cara37id=' . $_REQUEST['cara02discv2intelectura'] . '';
		}
	}
	$objCombos->nuevo('cara02discv2intelectura', $_REQUEST['cara02discv2intelectura'], true, '{' . $ETI['msg_ninguna'] . '}', 0);
	$sSQL = 'SELECT cara37id AS id, cara37nombre AS nombre FROM cara37discapacidades WHERE cara37tipodisc=2' . $sAdd . ' ORDER BY cara37orden, cara37nombre';
	$html_cara02discv2intelectura = $objCombos->html($sSQL, $objDB);
	if ($bEstudiante) {
		if ($_REQUEST['cara01completa'] == 'S') {
			$sAdd = ' AND cara37id=' . $_REQUEST['cara02discv2fisica'] . '';
		}
	}
	$objCombos->nuevo('cara02discv2fisica', $_REQUEST['cara02discv2fisica'], true, '{' . $ETI['msg_ninguna'] . '}', 0);
	$sSQL = 'SELECT cara37id AS id, cara37nombre AS nombre FROM cara37discapacidades WHERE cara37tipodisc=3' . $sAdd . ' ORDER BY cara37orden, cara37nombre';
	$html_cara02discv2fisica = $objCombos->html($sSQL, $objDB);
	if ($bEstudiante) {
		if ($_REQUEST['cara01completa'] == 'S') {
			$sAdd = ' AND cara37id=' . $_REQUEST['cara02discv2psico'] . '';
		}
	}
	$objCombos->nuevo('cara02discv2psico', $_REQUEST['cara02discv2psico'], true, '{' . $ETI['msg_ninguna'] . '}', 0);
	$sSQL = 'SELECT cara37id AS id, cara37nombre AS nombre FROM cara37discapacidades WHERE cara37tipodisc=4' . $sAdd . ' ORDER BY cara37orden, cara37nombre';
	$html_cara02discv2psico = $objCombos->html($sSQL, $objDB);

	$sAdd = ' AND cara28version=' . $_REQUEST['cara01discversion'] . ' AND cara38activa="S"';
	if ($bEstudiante) {
		if ($_REQUEST['cara01completa'] == 'S') {
			$sAdd = ' AND cara38id=' . $_REQUEST['cara02talentoexcepcional'] . '';
		}
	}
	$objCombos->nuevo('cara02talentoexcepcional', $_REQUEST['cara02talentoexcepcional'], true, '{' . $ETI['msg_ninguno'] . '}', 0);
	$sSQL = 'SELECT cara38id AS id, cara38nombre AS nombre FROM cara38talentos WHERE cara38id>0' . $sAdd . ' ORDER BY cara38orden, cara38nombre';
	$html_cara02talentoexcepcional = $objCombos->html($sSQL, $objDB);
}
if ($_REQUEST['cara01discversion'] == 1) {
	$objCombos->nuevo('cara02discv2sistemica', $_REQUEST['cara02discv2sistemica'], true, '' . $ETI['no'] . '', 0);
	$objCombos->addItem(1, $ETI['si']);
	//$objCombos->addArreglo($acara02discv2sistemica, $icara02discv2sistemica);
	$html_cara02discv2sistemica = $objCombos->html('', $objDB);
}
$bEntra = false;
if ($_REQUEST['cara01discversion'] == 1) {
	$bEntra = true;
}
if ($_REQUEST['cara01discversion'] == 2) {
	$bEntra = true;
}
if ($bEntra) {
	$objCombos->nuevo('cara02discv2multiple', $_REQUEST['cara02discv2multiple'], true, '' . $ETI['no'] . '', 0);
	$objCombos->addItem(1, $ETI['si']);
	if ($_REQUEST['cara01discversion'] == 2) {
		$objCombos->sAccion = 'ajustar_cara02discv2multiple()';
	}
	//$objCombos->addArreglo($acara02discv2multiple, $icara02discv2multiple);
	$html_cara02discv2multiple = $objCombos->html('', $objDB);
}
list($cara01idzona_nombre, $sErrorDet) = tabla_campoxid('unad23zona', 'unad23nombre', 'unad23id', $_REQUEST['cara01idzona'], '{' . $ETI['msg_sindato'] . '}', $objDB);
$html_cara01idzona = html_oculto('cara01idzona', $_REQUEST['cara01idzona'], $cara01idzona_nombre);
list($cara01tipocaracterizacion_nombre, $sErrorDet) = tabla_campoxid('cara11tipocaract', 'cara11nombre', 'cara11id', $_REQUEST['cara01tipocaracterizacion'], '{' . $ETI['msg_sindato'] . '}', $objDB);
$html_cara01tipocaracterizacion = html_oculto('cara01tipocaracterizacion', $_REQUEST['cara01tipocaracterizacion'], $cara01tipocaracterizacion_nombre);
$bEntra = false;
if ($_REQUEST['cara01discversion'] == 2) {
	$bEntra = true;
}
if ($bEntra) {
	$objCombos->nuevo('cara01discv2tiene', $_REQUEST['cara01discv2tiene'], true, $ETI['no'], 0);
	$objCombos->sAccion = 'ajustar_cara01discv2tiene()';
	$objCombos->addItem(1, $ETI['si']);
	//$objCombos->addArreglo($acara01discv2tiene, $icara01discv2tiene);
	$html_cara01discv2tiene = $objCombos->html('', $objDB);


	$objCombos->nuevo('cara01discv2trastornos', $_REQUEST['cara01discv2trastornos'], true, $ETI['no'], 0);
	//$objCombos->addArreglo($acara01discv2trastornos, $icara01discv2trastornos);
	$objCombos->sAccion = 'ajustar_cara01discv2trastornos()';
	$objCombos->addItem(1, $ETI['si']);
	$html_cara01discv2trastornos = $objCombos->html('', $objDB);
	$sAdd = ' AND cara37version=' . $_REQUEST['cara01discversion'] . ' AND cara37activa="S"';
	if ($bEstudiante) {
		if ($_REQUEST['cara01completa'] == 'S') {
			$sAdd = ' AND cara37id=' . $_REQUEST['cara01discv2trastornos'] . '';
		}
	}
	$objCombos->nuevo('cara01discv2trastaprende', $_REQUEST['cara01discv2trastaprende'], true, '{' . $ETI['msg_ninguno'] . '}', 0);
	//$objCombos->addArreglo($acara01discv2trastaprende, $icara01discv2trastaprende);
	$sSQL = 'SELECT cara37id AS id, cara37nombre AS nombre FROM cara37discapacidades WHERE cara37tipodisc=6' . $sAdd . ' ORDER BY cara37orden, cara37nombre';
	$html_cara01discv2trastaprende = $objCombos->html($sSQL, $objDB);

	$objCombos->nuevo('cara01discv2contalento', $_REQUEST['cara01discv2contalento'], true, $ETI['no'], 0);
	$objCombos->addItem(1, $ETI['si']);
	//$objCombos->addArreglo($acara01discv2contalento, $icara01discv2contalento);
	$html_cara01discv2contalento = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara01discv2condicionmedica', $_REQUEST['cara01discv2condicionmedica'], true, $ETI['no'], 0);
	$objCombos->sAccion = 'ajustar_cara01discv2condicionmedica()';
	$objCombos->addItem(1, $ETI['si']);
	//$objCombos->addArreglo($acara01discv2condicionmedica, $icara01discv2condicionmedica);
	$html_cara01discv2condicionmedica = $objCombos->html('', $objDB);
	$objCombos->nuevo('cara01discv2pruebacoeficiente', $_REQUEST['cara01discv2pruebacoeficiente'], true, $ETI['no'], 0);
	$objCombos->addArreglo($acara01discv2pruebacoeficiente, $icara01discv2pruebacoeficiente);
	$html_cara01discv2pruebacoeficiente = $objCombos->html('', $objDB);
} else {
	$html_cara01discv2trastaprende = html_oculto('cara01discv2trastaprende', $_REQUEST['cara01discv2trastaprende']);
}
if ((int)$_REQUEST['paso'] == 0) {
	$idTerceroFuncion = 0;
	if ($bEstudiante) {
		$idTerceroFuncion = $idTercero;
	}
	$html_cara01idperaca = f2301_HTMLComboV2_cara01idperaca($objDB, $objCombos, $_REQUEST['cara01idperaca'], $idTerceroFuncion, $bEstudiante);
} else {
	list($cara01idperaca_nombre, $sErrorDet) = tabla_campoxid('exte02per_aca', 'exte02nombre', 'exte02id', $_REQUEST['cara01idperaca'], '{' . $ETI['msg_sindato'] . '}', $objDB);
	$html_cara01idperaca = html_oculto('cara01idperaca', $_REQUEST['cara01idperaca'], $cara01idperaca_nombre);
}
//Alistar datos adicionales
$id_rpt = 0;
//$id_rpt=reportes_id(_Identificador_Tipo_Reporte_, $objDB);
if (!$bEstudiante) {
	$objCombos->nuevo('blistar', $_REQUEST['blistar'], true, '{' . $ETI['msg_todos'] . '}');
	$objCombos->sAccion = 'paginarf2301()';
	$objCombos->addItem(1, 'Donde soy consejero');
	$objCombos->addItem(11, 'Encuestas terminadas');
	$objCombos->addItem(12, 'Encuestas incompletas');
	$objCombos->addItem(13, 'Estudiantes Nuevos');
	$objCombos->addItem(14, 'Estudiantes Antiguos');
	$objCombos->addItem(15, 'Estudiantes Reingreso');
	$html_blistar = $objCombos->html('', $objDB);
	$objCombos->nuevo('bperiodo', $_REQUEST['bperiodo'], true, '{' . $ETI['msg_todos'] . '}');
	$objCombos->iAncho = 600;
	$objCombos->sAccion = 'paginarf2301()';
	$sIds = '-99';
	$sSQL = 'SELECT cara01idperaca FROM cara01encuesta GROUP BY cara01idperaca';
	$tabla = $objDB->ejecutasql($sSQL);
	while ($fila = $objDB->sf($tabla)) {
		$sIds = $sIds . ',' . $fila['cara01idperaca'];
	}
	$sSQL = f146_ConsultaCombo('exte02id IN (' . $sIds . ')', $objDB);
	$html_bperiodo = $objCombos->html($sSQL, $objDB);
	$objCombos->nuevo('bescuela', $_REQUEST['bescuela'], true, '{' . $ETI['msg_todas'] . '}');
	$objCombos->addItem('0', '{' . $ETI['msg_ninguna'] . '}');
	$objCombos->sAccion = 'carga_combo_bprograma()';
	$sSQL = 'SELECT core12id AS id, core12nombre AS nombre FROM core12escuela WHERE core12tieneestudiantes="S" ORDER BY core12nombre';
	$html_bescuela = $objCombos->html($sSQL, $objDB);
	$html_bprograma = f2301_HTMLComboV2_bprograma($objDB, $objCombos, $_REQUEST['bprograma'], $_REQUEST['bescuela']);
	$objCombos->nuevo('bzona', $_REQUEST['bzona'], true, '{' . $ETI['msg_todas'] . '}');
	$objCombos->sAccion = 'carga_combo_bcead()';
	$sSQL = 'SELECT unad23id AS id, unad23nombre AS nombre FROM unad23zona WHERE unad23conestudiantes="S" ORDER BY unad23nombre';
	$html_bzona = $objCombos->html($sSQL, $objDB);
	$html_bcead = f2301_HTMLComboV2_bcead($objDB, $objCombos, $_REQUEST['bcead'], $_REQUEST['bzona']);
	$objCombos->nuevo('btipocara', $_REQUEST['btipocara'], true, '{' . $ETI['msg_todas'] . '}');
	$objCombos->sAccion = 'paginarf2301()';
	$sSQL = 'SELECT cara11id AS id, cara11nombre AS nombre FROM cara11tipocaract ORDER BY cara11nombre';
	$html_btipocara = $objCombos->html($sSQL, $objDB);
	$objCombos->nuevo('bpoblacion', $_REQUEST['bpoblacion'], true, '{' . $ETI['msg_todas'] . '}');
	$objCombos->sAccion = 'paginarf2301()';
	$objCombos->addItem(1, 'Con necesidades especiales');
	$objCombos->addItem(2, 'Con necesidades especiales [Por confirmar]');
	$html_bpoblacion = $objCombos->html('', $objDB);
	$objCombos->nuevo('bconvenio', $_REQUEST['bconvenio'], true, '{' . $ETI['msg_todos'] . '}');
	$objCombos->iAncho = 600;
	$objCombos->sAccion = 'paginarf2301()';
	$sSQL = 'SELECT core50id AS id, core50nombre AS nombre FROM core50convenios ORDER BY core50estado DESC, core50nombre';
	$html_bconvenio = $objCombos->html($sSQL, $objDB);
	//$html_blistar=$objCombos->comboSistema(2301, 1, $objDB, 'paginarf2301()');
}
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
$iModeloReporte = 2301;
$html_iFormatoImprime = '<input id="iformatoimprime" name="iformatoimprime" type="hidden" value="0" />
';
if ($_REQUEST['paso'] > 0) {
	$bDevuelve = false;
	//list($bDevuelve, $sDebugP)=seg_revisa_permisoV3($iCodModulo, 5, $idTercero, $objDB);
	if ($bDevuelve) {
		$seg_5 = 1;
	}
}
if ($bEstudiante) {
	//2022 - 08 - 19 : Ver si tiene mas de una encuesta cargada y que pueda cambiar entre ellas
} else {
	//Cargar las tablas de datos
	$aParametros[0] = ''; //$_REQUEST['p1_2301'];
	$aParametros[100] = $idTercero;
	$aParametros[101] = $_REQUEST['paginaf2301'];
	$aParametros[102] = $_REQUEST['lppf2301'];
	$aParametros[103] = $_REQUEST['bdoc'];
	$aParametros[104] = $_REQUEST['bnombre'];
	$aParametros[105] = $_REQUEST['bperiodo'];
	$aParametros[106] = $_REQUEST['blistar'];
	$aParametros[107] = $_REQUEST['bescuela'];
	$aParametros[108] = $_REQUEST['bprograma'];
	$aParametros[109] = $_REQUEST['bzona'];
	$aParametros[110] = $_REQUEST['bcead'];
	$aParametros[111] = $_REQUEST['btipocara'];
	$aParametros[112] = $_REQUEST['bpoblacion'];
	$aParametros[113] = $_REQUEST['bconvenio'];
	list($sTabla2301, $sDebugTabla) = f2301_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugTabla;
}
$sTabla2310_7 = '';
$sTabla2310_8 = '';
$sTabla2310_9 = '';
$sTabla2310_10 = '';
$sTabla2310_11 = '';
$sTabla2310_12 = '';
$sTabla2310_13 = '';
$sTabla2310_14 = '';
if ($_REQUEST['paso'] != 0) {
	//Preguntas de la prueba
	$iParaEditar = 0;
	if ($bEstudiante) {
		$iParaEditar = 1;
	}
	$aParametros2310[0] = $_REQUEST['cara01id'];
	$aParametros2310[101] = $_REQUEST['paginaf2310'];
	$aParametros2310[102] = $_REQUEST['lppf2310'];
	$aParametros2310[103] = $iParaEditar;
	if ($_REQUEST['cara01fichadigital'] != -1) {
		$aParametros2310[100] = 1;
		list($sTabla2310_7, $sDebugTabla) = f2310_TablaDetalleV2($aParametros2310, $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugTabla;
	}
	if ($_REQUEST['cara01fichalectura'] != -1) {
		$aParametros2310[100] = 2;
		list($sTabla2310_8, $sDebugTabla) = f2310_TablaDetalleV2($aParametros2310, $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugTabla;
	}
	if ($_REQUEST['cara01ficharazona'] != -1) {
		$aParametros2310[100] = 3;
		list($sTabla2310_9, $sDebugTabla) = f2310_TablaDetalleV2($aParametros2310, $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugTabla;
	}
	if ($_REQUEST['cara01fichaingles'] != -1) {
		$aParametros2310[100] = 4;
		list($sTabla2310_10, $sDebugTabla) = f2310_TablaDetalleV2($aParametros2310, $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugTabla;
	}
	if ($_REQUEST['cara01fichabiolog'] != -1) {
		$aParametros2310[100] = 5;
		list($sTabla2310_11, $sDebugTabla) = f2310_TablaDetalleV2($aParametros2310, $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugTabla;
	}
	if ($_REQUEST['cara01fichafisica'] != -1) {
		$aParametros2310[100] = 6;
		list($sTabla2310_12, $sDebugTabla) = f2310_TablaDetalleV2($aParametros2310, $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugTabla;
	}
	if ($_REQUEST['cara01fichaquimica'] != -1) {
		$aParametros2310[100] = 7;
		list($sTabla2310_13, $sDebugTabla) = f2310_TablaDetalleV2($aParametros2310, $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugTabla;
	}
	if ($_REQUEST['cara01fichaciudad'] != -1) {
		$aParametros2310[100] = 8;
		list($sTabla2310_14, $sDebugTabla) = f2310_TablaDetalleV2($aParametros2310, $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugTabla;
	}
}
$bDebugMenu = false;
switch ($iPiel) {
	case 2:
		if ($bEstudiante) {
			list($et_menu, $sDebugM) = html_menuCampusV2($objDB, $iPiel, $bDebugMenu, $idTercero);
		} else {
			list($et_menu, $sDebugM) = html_Menu2023($APP->idsistema, $objDB, $iPiel, $bDebugMenu, $idTercero);
		}
		break;
	default:
		if ($bEstudiante) {
			list($et_menu, $sDebugM) = html_menuCampus($APP->idsistema, $objDB, $iPiel, $bDebugMenu, $idTercero);
		} else {
			list($et_menu, $sDebugM) = html_menuV2($APP->idsistema, $objDB, $iPiel, $bDebugMenu, $idTercero);
		}
		break;
}
$sDebug = $sDebug . $sDebugM;
$objDB->CerrarConexion();
//FORMA
switch ($iPiel) {
	case 2:
		require $APP->rutacomun . 'unad_forma2024.php';
		forma_InicioV4($xajax, $sTituloModulo);
		if ($bEstudiante) {
			$aRutas = array(
				array('', $sTituloModulo)
			);
		} else {
			$aRutas = array(
				array('./', $sTituloApp),
				array('./' . $sPaginaModulo, $sGrupoModulo),
				array('', $sTituloModulo)
			);
		}
		$iNumBoton = 0;
		$aBotones[$iNumBoton] = array('muestraayuda(' . $APP->idsistema . ', ' . $iCodModulo . ')', $ETI['bt_ayuda'], 'iHelp');
		$iNumBoton++;
		if (!$bVerIntro && $bTienePeriodo){
			if ($bConEliminar) {
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
			if ($bConBotonCerrar) {
				$aBotones[$iNumBoton] = array('enviacerrar()', $ETI['bt_cerrar'], 'iTask');
				$iNumBoton++;
			}
			if ($bPuedeAbrir) {
				$aBotones[$iNumBoton] = array('enviaabrir()', $ETI['bt_abrir'], 'iOpen');
				$iNumBoton++;
			}
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
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.0.1/css/font-awesome.min.css">
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>css/botones_resultado.css">
<script language="javascript">
	function expandesector(codigo) {
		document.getElementById('div_sector1').style.display = 'none';
		document.getElementById('div_sector2').style.display = 'none';
		document.getElementById('div_sector10').style.display = 'none';
		document.getElementById('div_sector11').style.display = 'none';
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
		if ($bPuedeGuardar && $bBloqueTitulo && $bEstudiante) {
?>
		let sEst = 'none';
		if (codigo == 1) {
			sEst = 'block';
		}
		if (window.document.frmedita.cara01completa.value != 'S') {
			document.getElementById('cmdGuardarf').style.display = sEst;
		}
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
			if (idcampo == 'cara01idtercero') {
				params[4] = 'RevisaLlave';
			}
			xajax_unad11_TraerXid(params);
		}
	}

	function imprimelista() {
		if (window.document.frmedita.seg_6.value == 1) {
			window.document.frmlista.consulta.value = window.document.frmedita.consulta_2301.value;
			window.document.frmlista.titulos.value = window.document.frmedita.titulos_2301.value;
			window.document.frmlista.nombrearchivo.value = 'Encuesta';
			window.document.frmlista.submit();
		} else {
			window.alert("<?php echo $ERR['6']; ?>");
		}
	}

	function asignarvariables() {
		window.document.frmimpp.iformato94.value = window.document.frmedita.iformatoimprime.value;
		//window.document.frmimpp.v3.value=window.document.frmedita.bnombre.value;
		//window.document.frmimpp.v4.value=window.document.frmedita.bcodigo.value;
		//window.document.frmimpp.separa.value=window.document.frmedita.csv_separa.value.trim();
	}

	function imprimeexcel() {
		let sError = '';
		if (window.document.frmedita.seg_6.value != 1) {
			sError = "<?php echo $ERR['6']; ?>";
		}
		//if (sError==''){/*Agregar validaciones*/}
		if (sError == '') {
			asignarvariables();
			window.document.frmimpp.action = 'e2301.php';
			window.document.frmimpp.submit();
		} else {
			window.alert(sError);
		}
	}

	function imprimep() {
		if (window.document.frmedita.seg_5.value == 1) {
			asignarvariables();
			window.document.frmimpp.action = 'p2301.php';
			window.document.frmimpp.submit();
			<?php
			if ($iNumFormatosImprime > 0) {
			?>
				expandesector(1);
			<?php
			}
			?>
		} else {
			window.alert("<?php echo $ERR['5']; ?>");
		}
	}

	function verrpt() {
		window.document.frmimprime.submit();
	}

	function eliminadato() {
		if (confirm("<?php echo $ETI['confirma_eliminar']; ?>?")) {
			expandesector(98);
			window.document.frmedita.paso.value = 13;
			window.document.frmedita.submit();
		}
	}

	function RevisaLlave() {
		let datos = new Array();
		datos[1] = window.document.frmedita.cara01idperaca.value;
		datos[2] = window.document.frmedita.cara01idtercero.value;
		if ((datos[1] != '') && (datos[2] != '')) {
			xajax_f2301_ExisteDato(datos);
		}
	}

	function cargadato(llave1, llave2) {
		window.document.frmedita.cara01idperaca.value = String(llave1);
		window.document.frmedita.cara01idtercero.value = String(llave2);
		window.document.frmedita.paso.value = 1;
		window.document.frmedita.submit();
	}

	function cargaridf2301(llave1) {
		window.document.frmedita.cara01id.value = String(llave1);
		window.document.frmedita.paso.value = 3;
		window.document.frmedita.submit();
	}

	function carga_combo_cara01depto() {
		let params = new Array();
		params[0] = window.document.frmedita.cara01pais.value;
		document.getElementById('div_cara01depto').innerHTML = '<b>Procesando datos, por favor espere...</b><input id="cara01depto" name="cara01depto" type="hidden" value="" />';
		xajax_f2301_Combocara01depto(params);
		sMuestra = 'none';
		if (params[0] != '057') {
			sMuestra = 'block';
		}
		document.getElementById('div_ciudad').style.display = sMuestra;
	}

	function carga_combo_cara01ciudad() {
		let params = new Array();
		params[0] = window.document.frmedita.cara01depto.value;
		document.getElementById('div_cara01ciudad').innerHTML = '<b>Procesando datos, por favor espere...</b><input id="cara01ciudad" name="cara01ciudad" type="hidden" value="" />';
		xajax_f2301_Combocara01ciudad(params);
	}

	function carga_combo_cara01idcead() {
		let params = new Array();
		params[0] = window.document.frmedita.cara01idzona.value;
		document.getElementById('div_cara01idcead').innerHTML = '<b>Procesando datos, por favor espere...</b><input id="cara01idcead" name="cara01idcead" type="hidden" value="" />';
		xajax_f2301_Combocara01idcead(params);
	}

	function limpia_cara01discv2archivoorigen() {
		window.document.frmedita.cara01discv2soporteorigen.value = 0;
		window.document.frmedita.cara01discv2archivoorigen.value = 0;
		let da_Discv2archivoorigen = document.getElementById('div_cara01discv2archivoorigen');
		da_Discv2archivoorigen.innerHTML = '&nbsp;';
		verboton('beliminacara01discv2archivoorigen', 'none');
		//paginarf0000();
	}

	function carga_cara01discv2archivoorigen() {
		window.document.frmedita.iscroll.value = window.pageYOffset;
		window.document.frmedita.div96v1.value = '';
		window.document.frmedita.div96v2.value = '';
		window.document.frmedita.div96v3.value = '';
		if (window.document.frmedita.ipiel.value == 2) {
			document.getElementById('div_96titulo').innerHTML = '' + window.document.frmedita.titulo_2301.value + ' - Cargar archivo';
		} else {
			document.getElementById('div_96titulo').innerHTML = '<h2>' + window.document.frmedita.titulo_2301.value + ' - Cargar archivo</h2>';
		}
		document.getElementById('div_96cuerpo').innerHTML = '<iframe id="iframe96" src="framearchivo.php?ref=2301&id=' + window.document.frmedita.cara01id.value + '" height="400px" width="100%" frameborder="0"></iframe>';
		expandesector(96);
		window.scrollTo(0, 150);
	}
	<?php
	if (!$bEstudiante) {
	?>

		function eliminacara01discv2archivoorigen() {
			let did = window.document.frmedita.cara01id;
			if (confirm("Esta seguro de eliminar el archivo?")) {
				xajax_elimina_archivo_cara01discv2archivoorigen(did.value);
				//paginarf0000();
			}
		}
	<?php
	}
	?>

	function paginarf2301() {
		let params = new Array();
		params[99] = window.document.frmedita.debug.value;
		params[100] = <?php echo $idTercero; ?>;
		params[101] = window.document.frmedita.paginaf2301.value;
		params[102] = window.document.frmedita.lppf2301.value;
		params[103] = window.document.frmedita.bdoc.value;
		params[104] = window.document.frmedita.bnombre.value;
		params[105] = window.document.frmedita.bperiodo.value;
		params[106] = window.document.frmedita.blistar.value;
		params[107] = window.document.frmedita.bescuela.value;
		params[108] = window.document.frmedita.bprograma.value;
		params[109] = window.document.frmedita.bzona.value;
		params[110] = window.document.frmedita.bcead.value;
		params[111] = window.document.frmedita.btipocara.value;
		params[112] = window.document.frmedita.bpoblacion.value;
		params[113] = window.document.frmedita.bconvenio.value;
		//document.getElementById('div_f2301detalle').innerHTML='<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf2301" name="paginaf2301" type="hidden" value="'+params[101]+'" /><input id="lppf2301" name="lppf2301" type="hidden" value="'+params[102]+'" />';
		xajax_f2301_HtmlTabla(params);
	}

	function enviacerrar() {
		window.document.frmedita.iscroll.value = window.scrollY;
		ModalConfirmV2('¿Esta seguro de cerrar el registro?<br>Luego de cerrado no se permite modificar', () => {
			ejecuta_enviacerrar();
		});
	}

	function ejecuta_enviacerrar() {
		MensajeAlarmaV2('<?php echo $ETI['msg_ejecutando']; ?>', 2);
		window.document.frmedita.paso.value = 16;
		window.document.frmedita.submit();
	}

	function enviaabrir() {
		window.document.frmedita.iscroll.value = window.scrollY;
		ModalConfirmV2('¿Esta seguro de abrir el registro?<br>Esto le permite volver a modificar', () => {
			ejecuta_enviaabrir();
		});
	}

	function ejecuta_enviaabrir() {
		MensajeAlarmaV2('<?php echo $ETI['msg_ejecutando']; ?>', 2);
		window.document.frmedita.paso.value = 17;
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

	function objinicial() {
		document.getElementById("cara01idperaca").focus();
	}

	function buscarV2016(sCampo) {
		window.document.frmedita.iscroll.value = window.pageYOffset;
		expandesector(98);
		window.document.frmedita.scampobusca.value = sCampo;
		let params = new Array();
		params[1] = sCampo;
		//params[2]=window.document.frmedita.iagno.value;
		//params[3]=window.document.frmedita.itipo.value;
		xajax_f2301_Busquedas(params);
	}

	function retornacontrol() {
		expandesector(1);
		window.scrollTo(0, window.document.frmedita.iscroll.value);
	}

	function Devuelve(sValor) {
		let sCampo = window.document.frmedita.scampobusca.value;
		if (sCampo == 'cara01idtercero') {
			ter_traerxid('cara01idtercero', sValor);
		}
		if (sCampo == 'cara01idconfirmadesp') {
			ter_traerxid('cara01idconfirmadesp', sValor);
		}
		if (sCampo == 'cara01idconfirmacr') {
			ter_traerxid('cara01idconfirmacr', sValor);
		}
		if (sCampo == 'cara01idconfirmadisc') {
			ter_traerxid('cara01idconfirmadisc', sValor);
		}
		if (sCampo == 'cara01idconsejero') {
			ter_traerxid('cara01idconsejero', sValor);
		}
		retornacontrol();
	}

	function mantener_sesion() {
		xajax_sesion_mantenerV4();
	}
	setInterval('xajax_sesion_abandona_V2();', 60000);

	function AyudaLocal(sCampo) {
		let divAyuda = document.getElementById('div_ayuda_' + sCampo);
		if (typeof divAyuda === 'undefined') {} else {
			verboton('cmdAyuda_' + sCampo, 'none');
			// var sMensaje='Lo que quiera decir.';
			//if (sCampo=='sNombreCampo'){sMensaje='Mensaje para otro campo.';}
			// divAyuda.innerHTML=sMensaje;
			divAyuda.style.display = 'block';
		}
	}

	function cierraDiv96(ref) {
		let sRetorna = window.document.frmedita.div96v2.value;
		if (ref == 2301) {
			if (sRetorna != '') {
				window.document.frmedita.cara01discv2soporteorigen.value = window.document.frmedita.div96v1.value;
				window.document.frmedita.cara01discv2archivoorigen.value = sRetorna;
				verboton('beliminacara01discv2archivoorigen', 'block');
			}
			archivo_lnk(window.document.frmedita.cara01discv2soporteorigen.value, window.document.frmedita.cara01discv2archivoorigen.value, 'div_cara01discv2archivoorigen');
			// paginarf0();
		}
		MensajeAlarmaV2('', 0);
		retornacontrol();
	}
	<?php
	if ($bVerIntro){
	?>
		function iniciar(){
			MensajeAlarmaV2('<?php echo $ETI['msg_ejecutando']; ?>', 2);
			window.document.frmedita.paso.value = 21;
			window.document.frmedita.submit();
		}
	<?php
	}
	?>
	<?php
	if (!$bTienePeriodo){
	?>
		function irtablero() {
			window.document.frmtablero.submit();
		}
	<?php
	}
	?>
	<?php
	if ($bEstudiante) {
	?>

		function verficha(num) {
			document.getElementById('div_ficha1').style.display = 'none';
			document.getElementById('div_ficha2').style.display = 'none';
			document.getElementById('div_ficha3').style.display = 'none';
			document.getElementById('div_ficha4').style.display = 'none';
			document.getElementById('div_ficha5').style.display = 'none';
			document.getElementById('div_ficha6').style.display = 'none';
			document.getElementById('div_ficha7').style.display = 'none';
			document.getElementById('div_ficha8').style.display = 'none';
			document.getElementById('div_ficha9').style.display = 'none';
			document.getElementById('div_ficha10').style.display = 'none';
			document.getElementById('div_ficha11').style.display = 'none';
			document.getElementById('div_ficha12').style.display = 'none';
			document.getElementById('div_ficha13').style.display = 'none';
			document.getElementById('div_ficha14').style.display = 'none';
			document.getElementById('div_ficha' + num).style.display = 'block';
			window.document.frmedita.ficha.value = num;
		}

		function irtablero() {
			window.document.frmtablero.submit();
		}
		<?php
		if ($_REQUEST['cara01completa'] != 'S') {
		?>
			function guardarpregunta(id10, iVr) {
				var aValores = new Array();
				aValores[0] = id10;
				aValores[1] = iVr;
				/*
				var objFila = document.getElementById('fila_rpta_' . id10);
				if (iVr == 0) {
					objFila.style.backgroundColor = 'red';
				} else {
					objFila.style.backgroundColor = 'green';
				}
				*/
				xajax_f2310_GuardarRespuesta(aValores)
			}
	<?php
		}
	}
	?>

	function verrecluso() {
		let sMuestra = 'none';
		if (window.document.frmedita.cara01inpecrecluso.value == 'S') {
			sMuestra = 'block';
		}
		document.getElementById('div_recluso').style.display = sMuestra;
	}

	function ajustarinteresrepdeporte() {
		let sMuestra = 'none';
		if (window.document.frmedita.cara01bien_interesrepdeporte.value == 'S') {
			sMuestra = 'block';
		}
		document.getElementById('label_interesrepdeporte').style.display = sMuestra;
	}

	function ajustarinteresreparte() {
		let sMuestra = 'none';
		if (window.document.frmedita.cara01bien_interesreparte.value == 'S') {
			sMuestra = 'block';
		}
		document.getElementById('label_interesreparte').style.display = sMuestra;
	}

	function ajustarprimeraopc() {
		let sMuestra = 'none';
		if (window.document.frmedita.cara01acad_primeraopc.value == 'N') {
			sMuestra = 'block';
		}
		document.getElementById('label_programagusto').style.display = sMuestra;
	}

	function ajustarnivelinterpreta() {
		let sMuestra = 'none';
		if (window.document.frmedita.cara01bien_interpreta.value != '') {
			if (window.document.frmedita.cara01bien_interpreta.value != '-1') {
				sMuestra = 'block';
			}
		}
		document.getElementById('div_bien_nivelinter').style.display = sMuestra;
	}

	function ajustardanza() {
		let sMuestra = 'none';
		if (window.document.frmedita.cara01bien_danza_mod.value == 'S') {
			sMuestra = 'block';
		}
		if (window.document.frmedita.cara01bien_danza_clas.value == 'S') {
			sMuestra = 'block';
		}
		if (window.document.frmedita.cara01bien_danza_cont.value == 'S') {
			sMuestra = 'block';
		}
		if (window.document.frmedita.cara01bien_danza_folk.value == 'S') {
			sMuestra = 'block';
		}
		document.getElementById('div_bien_niveldanza').style.display = sMuestra;
	}

	function ajustarnombreemp() {
		let sMuestra = 'none';
		if (window.document.frmedita.cara01bien_emprendedor.value == 'S') {
			sMuestra = 'block';
		}
		document.getElementById('label_nombreemp').style.display = sMuestra;
	}

	function ajustartipocapacita() {
		let sMuestra = 'none';
		if (window.document.frmedita.cara01bien_capacempren.value == 'S') {
			sMuestra = 'block';
		}
		document.getElementById('lbl_bien_capacempren').style.display = sMuestra;
	}

	function ajustaramb_temas() {
		let sMuestra = 'none';
		if (window.document.frmedita.cara01bien_amb_info.value == 'S') {
			sMuestra = 'block';
		}
		document.getElementById('lbl_bien_amb_temas').style.display = sMuestra;
	}

	function ajustarlaboral() {
		let sMuestra1 = 'none';
		let sMuestra2 = 'none';
		let sMuestra3 = 'none';
		let sMuestra4 = 'none';
		let sMuestra5 = 'none';
		let sMuestra6 = 'none';
		if (window.document.frmedita.cara01lab_situacion.value == 1) {
			sMuestra1 = 'block';
			sMuestra2 = 'block';
			sMuestra3 = 'block';
			sMuestra6 = 'block';
		}
		if (window.document.frmedita.cara01lab_situacion.value == 2) {
			sMuestra1 = 'block';
			sMuestra4 = 'block';
			sMuestra6 = 'block';
		}
		if (window.document.frmedita.cara01lab_situacion.value == 3) {
			sMuestra3 = 'block';
			sMuestra5 = 'block';
			sMuestra6 = 'block';
		}
		if (window.document.frmedita.cara01lab_situacion.value == 4) {
			sMuestra5 = 'block';
			sMuestra6 = 'block';
		}
		document.getElementById('div_lab1').style.display = sMuestra1;
		document.getElementById('div_lab2').style.display = sMuestra2;
		document.getElementById('div_lab3').style.display = sMuestra3;
		document.getElementById('div_lab4').style.display = sMuestra4;
		document.getElementById('div_lab5').style.display = sMuestra5;
		document.getElementById('div_lab6').style.display = sMuestra6;
	}

	function ajustar_cara01criteriodesc() {
		let sMuestra1 = 'none';
		if (window.document.frmedita.cara01perayuda.value == -1) {
			sMuestra1 = 'block';
		}
		if (window.document.frmedita.cara01perayuda.value == 11) {
			sMuestra1 = 'block';
		}
		document.getElementById('lbl_cara01perotraayuda').style.display = sMuestra1;
	}

	function ajustar_discsensorial() {
		let sMuestra1 = 'none';
		if (window.document.frmedita.cara01discsensorial.value == 9) {
			sMuestra1 = 'block';
		}
		document.getElementById('lbl_cara01discsensorialotra').style.display = sMuestra1;
	}

	function ajustar_discfisica() {
		let sMuestra1 = 'none';
		if (window.document.frmedita.cara01discfisica.value == 9) {
			sMuestra1 = 'block';
		}
		document.getElementById('lbl_cara01discfisicaotra').style.display = sMuestra1;
	}

	function ajustar_disccognitiva() {
		let sMuestra1 = 'none';
		if (window.document.frmedita.cara01disccognitiva.value == 9) {
			sMuestra1 = 'block';
		}
		document.getElementById('lbl_cara01disccognitivaotra').style.display = sMuestra1;
	}

	function ajustarbienv2otrodeporte() {
		let sMuestra1 = 'none';
		if (window.document.frmedita.cara44bienv2otrodeporte.value == 'S') {
			sMuestra1 = 'block';
		}
		document.getElementById('label_cara44bienv2otrodeporte').style.display = sMuestra1;
	}

	function ajustarcara44bienv2activculturalotra() {
		let sMuestra1 = 'none';
		if (window.document.frmedita.cara44bienv2activculturalotra.value == 'S') {
			sMuestra1 = 'block';
		}
		document.getElementById('label_cara44bienv2activculturalotra').style.display = sMuestra1;
	}

	function ajustarcara44bienv2evenculturalotro() {
		let sMuestra1 = 'none';
		if (window.document.frmedita.cara44bienv2evenculturalotro.value == 'S') {
			sMuestra1 = 'block';
		}
		document.getElementById('label_cara44bienv2evenculturalotro').style.display = sMuestra1;
	}

	function ajustarcara44bienv2emprenotro() {
		let sMuestra1 = 'none';
		if (window.document.frmedita.cara44bienv2emprenotro.value == 'S') {
			sMuestra1 = 'block';
		}
		document.getElementById('label_cara44bienv2emprenotro').style.display = sMuestra1;
	}

	function ajustarcara44bienv2ambienotraactiv() {
		let sMuestra1 = 'none';
		if (window.document.frmedita.cara44bienv2ambienotraactiv.value == 'S') {
			sMuestra1 = 'block';
		}
		document.getElementById('label_cara44bienv2ambienotraactiv').style.display = sMuestra1;
	}

	function ajustarcara44bienv2ambienotroenfoq() {
		let sMuestra1 = 'none';
		if (window.document.frmedita.cara44bienv2ambienotroenfoq.value == 'S') {
			sMuestra1 = 'block';
		}
		document.getElementById('label_cara44bienv2ambienotroenfoq').style.display = sMuestra1;
	}

	function ajustar_fam_hijos() {
		let sMuestra1 = 'none';
		if (window.document.frmedita.cara01sexo.value == 'F' && window.document.frmedita.cara01fam_hijos.value >= 1 && window.document.frmedita.cara01fam_hijos.value <= 4) {
			sMuestra1 = 'block';
		}
		document.getElementById('div_cara44fam_madrecabeza').style.display = sMuestra1;
	}

	function ajustar_acadhatenidorecesos() {
		let sMuestra1 = 'none';
		if (window.document.frmedita.cara44acadhatenidorecesos.value == 'S') {
			sMuestra1 = 'block';
		}
		document.getElementById('div_cara44acadrazonreceso').style.display = sMuestra1;
	}

	function ajustar_acadrazonreceso() {
		let sMuestra1 = 'none';
		if (window.document.frmedita.cara44acadrazonreceso.value == 10) {
			sMuestra1 = 'block';
		}
		document.getElementById('lbl_cara44acadrazonrecesodetalle').style.display = sMuestra1;
	}

	function ajustar_campus_usocorreounad() {
		let sMuestra1 = 'none';
		if (window.document.frmedita.cara44campus_usocorreounad.value == 4) {
			sMuestra1 = 'block';
		}
		document.getElementById('div_cara44campus_usocorreounadno').style.display = sMuestra1;
	}

	function ajustar_campus_usocorreounadno() {
		let sMuestra1 = 'none';
		if (window.document.frmedita.cara44campus_usocorreounadno.value == 5) {
			sMuestra1 = 'block';
		}
		document.getElementById('lbl_cara44campus_usocorreounadnodetalle').style.display = sMuestra1;
	}

	function ajustar_campus_medioactivunad() {
		let sMuestra1 = 'none';
		if (window.document.frmedita.cara44campus_medioactivunad.value == 6) {
			sMuestra1 = 'block';
		}
		document.getElementById('lbl_cara44campus_medioactivunaddetalle').style.display = sMuestra1;
	}

	function ajustar_cara44med_tratamiento() {
		let sMuestra1 = 'none';
		if (window.document.frmedita.cara44med_tratamiento.value != 0) {
			sMuestra1 = 'block';
		}
		document.getElementById('lbl_cara44med_trat_cual').style.display = sMuestra1;
	}
		function ajustar_cara44bienv3emprensector() {
		let sMuestra1 = 'none';
		if (window.document.frmedita.cara44bienv3emprensector.value == 9) {
			sMuestra1 = 'block';
		}
		document.getElementById('label_cara44bienv3emprensector').style.display = sMuestra1;
	}

	function ajustar_cara44bienv3ambienotro() {
		let sMuestra1 = 'none';
		if (window.document.frmedita.cara44bienv3ambienotro.value == 1) {
			sMuestra1 = 'block';
		}
		document.getElementById('label_cara44bienv3ambienotro').style.display = sMuestra1;
	}

	function ajustar_cara44bienv3deportenivel() {
		let sMuestra1 = 'none';
		if (window.document.frmedita.cara44bienv3deportenivel.value != 4) {
			sMuestra1 = 'block';
		}
		document.getElementById('label_cara44bienv3deportenivel').style.display = sMuestra1;
	}
	
	function ajustar_cara44bienv3deportecual() {
		let sMuestra1 = 'none';
		if (window.document.frmedita.cara44bienv3deportecual.value == 13) {
			sMuestra1 = 'block';
		}
		document.getElementById('label_cara44bienv3deportecual').style.display = sMuestra1;
	}

	function ajustar_cara44bienv3crecimhabilotro() {
		let sMuestra1 = 'none';
		if (window.document.frmedita.cara44bienv3crecimhabilotro.value == 1) {
			sMuestra1 = 'block';
		}
		document.getElementById('label_cara44bienv3crecimhabilotro').style.display = sMuestra1;
	}

	function ajustar_cara44bienv3crecimotramotiv() {
		let sMuestra1 = 'none';
		if (window.document.frmedita.cara44bienv3crecimotramotiv.value == 1) {
			sMuestra1 = 'block';
		}
		document.getElementById('label_cara44bienv3crecimotramotiv').style.display = sMuestra1;
	}

	function ajustar_cara44bienv3mentaldiagnostico() {
		let sMuestra1 = 'none';
		if (window.document.frmedita.cara44bienv3mentaldiagnostico.value == 1) {
			sMuestra1 = 'block';
		}
		document.getElementById('label_cara44bienv3mentaldiagnostico').style.display = sMuestra1;
	}

	function ajustar_cara44bienv3mentaldiagcual() {
		let sMuestra1 = 'none';
		if (window.document.frmedita.cara44bienv3mentaldiagcual.value == 11) {
			sMuestra1 = 'block';
		}
		document.getElementById('label_cara44bienv3mentaldiagcual').style.display = sMuestra1;
	}
<?php
if (!$bEstudiante) {
?>
		function actualizapreguntas() {
			expandesector(98);
			window.document.frmedita.paso.value = 22;
			window.document.frmedita.submit();
		}

		function carga_combo_bprograma() {
			let params = new Array();
			params[0] = window.document.frmedita.bescuela.value;
			xajax_f2301_Combobprograma(params);
		}

		function carga_combo_bcead() {
			let params = new Array();
			params[0] = window.document.frmedita.bzona.value;
			xajax_f2301_Combobcead(params);
		}

		function soyconsejeroidf2301(cara01id) {
			let params = new Array();
			params[0] = cara01id;
			params[1] = <?php echo $idTercero; ?>;
			xajax_f2301_MarcarConsejero(params);
		}

		function confirmadisc() {
			window.document.frmedita.iscroll.value = window.pageYOffset;
			MensajeAlarmaV2('Confirmando datos...', 2);
			expandesector(98);
			window.document.frmedita.paso.value = 23;
			window.document.frmedita.submit();
		}

		function vermatricula() {
			let sError = '';
			if (window.document.frmedita.bperiodo.value == '') {
				sError = 'Por favor seleccione un periodo para revisar los datos de matricula.';
			}
			if (sError == '') {
				window.document.frmedita.iscroll.value = window.pageYOffset;
				MensajeAlarmaV2('<?php echo $ETI['msg_ejecutando']; ?>...', 2);
				expandesector(98);
				window.document.frmedita.paso.value = 31;
				window.document.frmedita.submit();
			}
		}

		function actualizaedad() {
			let params = new Array();
			params[0] = window.document.frmedita.cara01id.value;
			params[1] = window.document.frmedita.cara01idtercero.value;
			xajax_f2301_Actualizaedad(params);
		}
	<?php
	}
	if ($_REQUEST['cara01discversion'] == 2) {
	?>

		function ajustar_cara01discv2tiene() {
			let sMuestra1 = 'none';
			if (window.document.frmedita.cara01discv2tiene.value == 1) {
				sMuestra1 = 'block';
			}
			document.getElementById('div_cara01disc').style.display = sMuestra1;
		}

		function ajustar_cara02discv2multiple() {
			let sMuestra1 = 'none';
			if (window.document.frmedita.cara02discv2multiple.value == 1) {
				sMuestra1 = 'block';
			}
			document.getElementById('lbl_cara02discv2multipleotro').style.display = sMuestra1;
		}

		function ajustar_cara01discv2trastornos() {
			let sMuestra1 = 'none';
			if (window.document.frmedita.cara01discv2trastornos.value == 1) {
				sMuestra1 = 'block';
			}
			document.getElementById('div_cara01discv2trastornos').style.display = sMuestra1;
		}

		function ajustar_cara01discv2condicionmedica() {
			let sMuestra1 = 'none';
			if (window.document.frmedita.cara01discv2condicionmedica.value == 1) {
				sMuestra1 = 'block';
			}
			document.getElementById('lbl_cara01discv2condmeddet').style.display = sMuestra1;
		}
	<?php
	}
	?>
</script>
<script type="text/x-mathjax-config">
	MathJax.Hub.Config({
    tex2jax: {inlineMath: [["$","$"],["\\(","\\)"]]}
  });
</script>
<script type="text/javascript" src="<?php echo $APP->rutacomun; ?>latex/MathJax.js?config=TeX-AMS_HTML-full"></script>
<?php
if ($bEstudiante) {
?>
<form id="frmtablero" name="frmtablero" method="post" action="<?php echo $sUrlTablero; ?>" style="display:none;">
</form>
<?php
}
if ($_REQUEST['paso'] != 0) {
?>
<form id="frmimpp" name="frmimpp" method="post" action="p2301.php" target="_blank" style="display:none;">
<input id="r" name="r" type="hidden" value="2301" />
<input id="id2301" name="id2301" type="hidden" value="<?php echo $_REQUEST['cara01id']; ?>" />
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
<?php
if (!$bTienePeriodo){
?>
<form id="frmtablero" name="frmtablero" method="post" action="<?php echo $sUrlTablero; ?>" style="display:none;">
</form>
<?php
}
?>
<form id="frmlista" name="frmlista" method="post" action="listados.php" target="_blank" style="display:none;">
<input id="titulos" name="titulos" type="hidden" value="" />
<input id="consulta" name="consulta" type="hidden" value="" />
<input id="nombrearchivo" name="nombrearchivo" type="hidden" value="" />
</form>
<div id="interna">
<?php
$sComplemento = '';
//if ($bDebug){$sComplemento='caracterizacion.php?debug=1&r=1';}
?>
<form id="frmedita" name="frmedita" method="post" action="<?php echo $sComplemento; ?>" autocomplete="off">
<input id="bNoAutocompletar" name="bNoAutocompletar" type="password" value="" style="display:none;" />
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
<?php
$sEstiloSector1 = 'display:block';
$sEstiloSector10 = 'display:none';
$sEstiloSector11 = 'display:none';
$sEstiloSector12 = 'display:none';
if ($bVerIntro){
	$sEstiloSector1 = 'display:none';
	$sEstiloSector10 = 'display:block';
}
if (!$bTienePeriodo){
	$sEstiloSector1 = 'display:none';
	$sEstiloSector11 = 'display:block';
}
if ($bErrorInicioEnc){
	$sEstiloSector1 = 'display:none';
	$sEstiloSector12 = 'display:block';
}
?>
<div id="div_sector1" style="<?php echo $sEstiloSector1; ?>">
<?php
if ($bBloqueTitulo) {
	$objForma->addBoton('cmdAyuda', 'btSupAyuda', 'muestraayuda(' . $APP->idsistema . ', ' . $iCodModulo . ');', $ETI['bt_ayuda']);
	if ($bConEliminar) {
		$objForma->addBoton('cmdEliminar', 'btUpEliminar', 'eliminadato();', $ETI['bt_eliminar']);
	}
	if ($bHayImprimir) {
		$objForma->addBoton('cmdImprimir', $sClaseImprime, $sScriptImprime, $ETI['bt_imprimir']);
	}
	$objForma->addBoton('cmdLimpiar', 'btUpLimpiar', 'limpiapagina();', $ETI['bt_limpiar']);
	if ($bPuedeGuardar) {
		$objForma->addBoton('cmdGuardar', 'btUpGuardar', 'enviaguardar();', $ETI['bt_guardar']);
	}
	if ($bConBotonCerrar) {
		$objForma->addBoton('cmdCerrar', 'btSupCerrar', 'enviacerrar();', $ETI['bt_cerrar']);
	}
	if ($bPuedeAbrir) {
		$objForma->addBoton('cmdAbrir', 'btSupAbrir', 'enviaabrir();', $ETI['bt_abrir']);
	}
	if (false) {
		$objForma->addBoton('cmdAnular', 'btSupAnular', 'expandesector(2);', $ETI['bt_anular']);
	}
	echo $objForma->htmlTitulo($sTituloModulo, $iCodModulo);
}
echo $objForma->htmlInicioMarco();
//Termina el bloque titulo
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

$bGrupo1 = true;
$bGrupo2 = true;
$bGrupo3 = true;
$bGrupo4 = true;
$bGrupo5 = true;
$bGrupo6 = true;
$bGrupo7 = true;
$bGrupo8 = true;
$bGrupo9 = true;
$bGrupo10 = true;
$bGrupo11 = true;
$bGrupo12 = true;
$bGrupo13 = true;
$bGrupo14 = false;
if ($_REQUEST['cara01idperaca'] > 2035) {
	$bGrupo14 = true;
}
echo $sHTMLHistorial;
//Div para ocultar
$bConExpande = true;
if ($bEstudiante) {
	$bConExpande = false;
}
?>
<input id="ficha" name="ficha" type="hidden" value="<?php echo $_REQUEST['ficha']; ?>" />
<?php
if ($bConExpande) {
?>
<div class="ir_derecha"<?php echo $sAnchoExpandeContrae; ?>>
<?php
echo $objForma->htmlExpande(2301, $_REQUEST['boculta2301'], $ETI['bt_mostrar'], $ETI['bt_ocultar']);
$sEstiloDiv = '';
if ($_REQUEST['boculta2301'] != 0) {
	$sEstiloDiv = ' style="display:none;"';
}
?>
</div>
<div id="div_p2301"<?php echo $sEstiloDiv; ?>>
<?php
}
//Mostrar formulario para editar
?>
<label class="Label130">
<?php
echo $ETI['cara01idperaca'];
?>
</label>
<label class="Label500">
<?php
echo $html_cara01idperaca;
?>
</label>
<div class="salto1px"></div>
<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo $ETI['cara01idtercero'];
?>
</label>
<div class="salto1px"></div>
<input id="cara01idtercero" name="cara01idtercero" type="hidden" value="<?php echo $_REQUEST['cara01idtercero']; ?>" />
<div id="div_cara01idtercero_llaves">
<?php
echo $html_cara01idtercero;
?>
</div>
<div class="salto1px"></div>
<div id="div_cara01idtercero" class="L"><?php echo $cara01idtercero_rs; ?></div>
<div class="salto1px"></div>
</div>

<div class="GrupoCampos450">
<label class="Label60"<?php echo $sOcultaId; ?>>
<?php
echo $ETI['cara01id'];
?>
</label>
<label class="Label60"<?php echo $sOcultaId; ?>>
<?php
echo html_oculto('cara01id', $_REQUEST['cara01id']);
?>
</label>
<label class="Label90">
<?php
$et_cara01completa = $ETI['msg_abierto'];
$et_cara01fechaencuesta = '&nbsp;';
if ($_REQUEST['cara01completa'] == 'S') {
	$et_cara01completa = $ETI['msg_cerrado'];
	$et_cara01fechaencuesta = fecha_desdenumero($_REQUEST['cara01fechaencuesta']);
}
echo html_oculto('cara01completa', $_REQUEST['cara01completa'], $et_cara01completa);
?>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['cara01fechaencuesta'];
?>
</label>
<div class="Campo220">
<?php
echo html_oculto('cara01fechaencuesta', $_REQUEST['cara01fechaencuesta'], $et_cara01fechaencuesta);
?>
</div>
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
<?php
if ($bEstudiante) {
	$aTitulo = array('', 'cara01fichaper', 'cara01fichafam', 'cara01fichaaca', 'cara01fichalab', 'cara01fichabien', 'cara01fichapsico', 'cara01fichadigital', 'cara01fichalectura', 'cara01ficharazona', 'cara01fichaingles', 'cara01fichabiolog', 'cara01fichafisica', 'cara01fichaquimica', 'cara01fichaciudad');
	$sPendiente = 'Pendiente';
	$sHecho = 'Completo';
	echo '<div class="tabuladores">';
	$aEstado = array('', $sPendiente, $sPendiente, $sPendiente, $sPendiente, $sPendiente, $sPendiente, $sPendiente, $sPendiente, $sPendiente, $sPendiente, $sPendiente, $sPendiente, $sPendiente, $sPendiente);
	for ($k = 1; $k <= 14; $k++) {
		if ($_REQUEST[$aTitulo[$k]] != -1) {
			//febrero 17 de 2022 - Se cambian los botones.
			if ($_REQUEST[$aTitulo[$k]] == 1) {
				echo html_BotonVerde('', $ETI[$aTitulo[$k]], 'javascript:verficha(' . $k . ');', $ETI[$aTitulo[$k]] . ', ' . $aEstado[$k]);
			} else {
				echo html_BotonRojo('', $ETI[$aTitulo[$k]], 'javascript:verficha(' . $k . ');', $ETI[$aTitulo[$k]] . ', ' . $aEstado[$k]);
			}
		}
	}
	echo '</div>';
}
?>
<div class="salto1px"></div>
<?php
if ($bEstudiante) {
	if ($_REQUEST['paso'] == 2) {
		if ($_REQUEST['cara01completa'] == 'S') {
			$_REQUEST['bocultaResultados'] = 0;
?>
<?php
// -- Inicia Grupo campos Resultados
?>
<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo $ETI['titulo_resultados'];
?>
</label>
<div class="ir_derecha"<?php echo $sAnchoExpandeContrae; ?>>
<?php
echo $objForma->htmlExpande('Resultados', $_REQUEST['bocultaResultados'], $ETI['bt_mostrar'], $ETI['bt_ocultar']);
$sEstiloDiv = '';
if ($_REQUEST['bocultaResultados'] != 0) {
	$sEstiloDiv = ' style="display:none;"';
}
?>
</div>
<div id="div_pResultados"<?php echo $sEstiloDiv; ?>>
<?php
if (false) {
?>
<label class="Label220">
<?php
echo $ETI['cara01psico_puntaje'];
?>
</label>
<label class="Label60">
<div id="div_cara01psico_puntaje">
<?php
echo html_oculto('cara01psico_puntaje', $_REQUEST['cara01psico_puntaje'], f2301_NombrePuntaje('puntaje', $_REQUEST['cara01psico_puntaje']));
?>
</div>
</label>
<?php
}else{
?>
<input id="cara01psico_puntaje" name="cara01psico_puntaje" type="hidden" value="<?php echo $_REQUEST['cara01psico_puntaje']; ?>" />
<?php
}
?>
<div class="salto1px"></div>
<div class="GrupoCamposAyuda">
<?php
if ($_REQUEST['cara01psico_puntaje'] < 17) {
	echo $ETI['msg_psico_alto'];
} else {
	if ($_REQUEST['cara01psico_puntaje'] < 24) {
		echo $ETI['msg_psico_medio'];
	} else {
		echo $ETI['msg_psico_bajo'];
	}
}
?>
<div class="salto1px"></div>
</div>
<?php
if ($_REQUEST['cara01fichadigital'] != -1){
?>
<div class="salto1px"></div>
<label class="Label220">
<?php
echo $ETI['cara01fichadigital'];
?>
</label>
<label class="Label60">
<?php
echo $ETI['msg_nivel'];
?>
</label>
<label class="Label60">
<?php
echo html_oculto('cara01niveldigital', $_REQUEST['cara01niveldigital'], f2301_NombrePuntaje('digital', $_REQUEST['cara01niveldigital']));
?>
</label>
<?php
} else {
?>
<input id="cara01niveldigital" name="cara01niveldigital" type="hidden" value="<?php echo $_REQUEST['cara01niveldigital']; ?>" />
<?php
}
if ($_REQUEST['cara01fichalectura'] != -1){
?>
<div class="salto1px"></div>
<label class="Label220">
<?php
echo $ETI['cara01fichalectura'];
?>
</label>
<label class="Label60">
<?php
echo $ETI['msg_nivel'];
?>
</label>
<label class="Label60">
<?php
echo html_oculto('cara01nivellectura', $_REQUEST['cara01nivellectura'], f2301_NombrePuntaje('lectura', $_REQUEST['cara01nivellectura']));
?>
</label>
<?php
} else {
?>
<input id="cara01nivellectura" name="cara01nivellectura" type="hidden" value="<?php echo $_REQUEST['cara01nivellectura']; ?>" />
<?php
}
if ($_REQUEST['cara01ficharazona'] != -1){
?>
<div class="salto1px"></div>
<label class="Label220">
<?php
echo $ETI['cara01ficharazona'];
?>
</label>
<label class="Label60">
<?php
echo $ETI['msg_nivel'];
?>
</label>
<label class="Label60">
<?php
echo html_oculto('cara01nivelrazona', $_REQUEST['cara01nivelrazona'], f2301_NombrePuntaje('razona', $_REQUEST['cara01nivelrazona']));
?>
</label>
<?php
} else {
?>
<input id="cara01nivelrazona" name="cara01nivelrazona" type="hidden" value="<?php echo $_REQUEST['cara01nivelrazona']; ?>" />
<?php
}
if ($_REQUEST['cara01fichaingles'] != -1){
?>
<div class="salto1px"></div>
<label class="Label220">
<?php
echo $ETI['cara01fichaingles'];
?>
</label>
<label class="Label60">
<?php
echo $ETI['msg_nivel'];
?>
</label>
<label class="Label60">
<?php
echo html_oculto('cara01nivelingles', $_REQUEST['cara01nivelingles'], f2301_NombrePuntaje('ingles', $_REQUEST['cara01nivelingles']));
?>
</label>
<?php
} else {
?>
<input id="cara01nivelingles" name="cara01nivelingles" type="hidden" value="<?php echo $_REQUEST['cara01nivelingles']; ?>" />
<?php
}
if ($_REQUEST['cara01fichabiolog'] != -1){
?>
<div class="salto1px"></div>
<label class="Label220">
<?php
echo $ETI['cara01fichabiolog'];
?>
</label>
<label class="Label60">
<?php
echo $ETI['msg_nivel'];
?>
</label>
<label class="Label60">
<?php
echo html_oculto('cara01nivelbiolog', $_REQUEST['cara01nivelbiolog'], f2301_NombrePuntaje('biolog', $_REQUEST['cara01nivelbiolog']));
?>
</label>
<?php
} else {
?>
<input id="cara01nivelbiolog" name="cara01nivelbiolog" type="hidden" value="<?php echo $_REQUEST['cara01nivelbiolog']; ?>" />
<?php
}
if ($_REQUEST['cara01fichafisica'] != -1){
?>
<div class="salto1px"></div>
<label class="Label220">
<?php
echo $ETI['cara01fichafisica'];
?>
</label>
<label class="Label60">
<?php
echo $ETI['msg_nivel'];
?>
</label>
<label class="Label60">
<?php
echo html_oculto('cara01nivelfisica', $_REQUEST['cara01nivelfisica'], f2301_NombrePuntaje('fisica', $_REQUEST['cara01nivelfisica']));
?>
</label>
<?php
} else {
?>
<input id="cara01nivelfisica" name="cara01nivelfisica" type="hidden" value="<?php echo $_REQUEST['cara01nivelfisica']; ?>" />
<?php
}
if ($_REQUEST['cara01fichaquimica'] != -1){
?>
<div class="salto1px"></div>
<label class="Label220">
<?php
echo $ETI['cara01fichaquimica'];
?>
</label>
<label class="Label60">
<?php
echo $ETI['msg_nivel'];
?>
</label>
<label class="Label60">
<?php
echo html_oculto('cara01nivelquimica', $_REQUEST['cara01nivelquimica'], f2301_NombrePuntaje('quimica', $_REQUEST['cara01nivelquimica']));
?>
</label>
<?php
} else {
?>
<input id="cara01nivelquimica" name="cara01nivelquimica" type="hidden" value="<?php echo $_REQUEST['cara01nivelquimica']; ?>" />
<?php
}
if (($bGrupo14) && ($_REQUEST['cara01fichaciudad'] != -1)){
?>
<div class="salto1px"></div>
<label class="Label220">
<?php
echo $ETI['cara01fichaciudad'];
?>
</label>
<label class="Label60">
<?php
echo $ETI['msg_nivel'];
?>
</label>
<label class="Label60">
<?php
echo html_oculto('cara01nivelciudad', $_REQUEST['cara01nivelciudad'], f2301_NombrePuntaje('ciudadanas', $_REQUEST['cara01nivelciudad']));
?>
</label>
<?php
} else {
?>
<input id="cara01nivelciudad" name="cara01nivelciudad" type="hidden" value="<?php echo $_REQUEST['cara01nivelciudad']; ?>" />
<?php
}
?>
<div class="salto1px"></div>
</div>
</div>
<?php
// -- Termina Grupo campos Resultados
?>
<?php 
}
}
}
?>
<?php
if ($bGrupo1) {
	$sEstilo = '';
	if ($bEstudiante) {
		$sEstilo = ' style="display:none"';
		if ($_REQUEST['ficha'] == 1) {
			$sEstilo = '';
		}
	}
?>
<div class="salto1px"></div>
<div class="GrupoCampos" id="div_ficha1" <?php echo $sEstilo; ?>>
<?php
$sEstiloDiv = '';
if ($bConExpande) {
?>
<div class="ir_derecha"<?php echo $sAnchoExpandeContrae; ?>>
<?php
echo $objForma->htmlExpande(101, $_REQUEST['boculta101'], $ETI['bt_mostrar'], $ETI['bt_ocultar']);
if ($_REQUEST['boculta101'] != 0) {
	$sEstiloDiv = ' style="display:none;"';
}
?>
</div>
<?php
}
?>
<label class="TituloGrupo">
<?php
echo $ETI['cara01fichaper'];
?>
</label>
<label class="Label130">
<div id="div_cara01fichaper">
<?php
$sMuestra = '&nbsp;';
if ($_REQUEST['cara01fichaper'] == 1) {
	$sMuestra = 'COMPLETA';
}
echo html_oculto('cara01fichaper', $_REQUEST['cara01fichaper'], $sMuestra);
?>
</div>
</label>
<div class="salto1px"></div>
<div id="div_p101"<?php echo $sEstiloDiv; ?>>
<div class="GrupoCampos450">
<label class="Label130">
<?php
echo $ETI['cara01agnos'];
?>
</label>
<label class="Label90">
<div id="div_cara01agnos">
<?php
echo html_oculto('cara01agnos', $_REQUEST['cara01agnos'], $et_cara01agnos);
?>
</div>
</label>
<?php
if (!$bEstudiante) {
?>
<label class="Label30">
<input id="btRevisaEdad" name="btRevisaEdad" type="button" value="Actualizar" class="btMiniActualizar" onclick="actualizaedad()" title="Actualizar Edad" />
</label>
<?php
}
?>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['cara01sexo'];
?>
</label>
<label class="Label160">
<?php
echo $html_cara01sexo;
?>
</label>
<?php
echo html_BotonAyudaV2('cara01sexo', $objForma, 'Informaci&oacute;n acerca del sexo');
?>
<div class="salto1px"></div>
<div id='div_ayuda_cara01sexo' class='GrupoCamposAyuda' style='display:none;'>
<?php
echo $ETI['ayuda_cara01sexo'];
?>
</div>
<input id="cara44sexoversion" name="cara44sexoversion" type="hidden" value="<?php echo $_REQUEST['cara44sexoversion']; ?>" />
<?php
if ($iSexoVersion == 1) {
?>
<div class="salto1px"></div>
<label class="L">
<?php
echo $ETI['cara44sexov1identidadgen'];
?>
</label>
<label class="Label130"></label>
<label class="Label160">
<?php
echo $html_cara44sexov1identidadgen;
?>
</label>
<?php
echo html_BotonAyudaV2('cara44sexov1identidadgen', $objForma, 'Informaci&oacute;n acerca de identidad de g&eacute;nero');
?>
<div class="salto1px"></div>
<div id='div_ayuda_cara44sexov1identidadgen' class='GrupoCamposAyuda' style='display:none;'>
<?php
echo $ETI['ayuda_cara44sexov1identidadgen'];
?>
</div>
<div class="salto1px"></div>
<label class="L">
<?php
echo $ETI['cara44sexov1orientasexo'];
?>
</label>
<label class="Label130"></label>
<label class="Label200">
<?php
echo $html_cara44sexov1orientasexo;
?>
</label>
<?php
echo html_BotonAyudaV2('cara44sexov1orientasexo', $objForma, 'Informaci&oacute;n acerca de orientaci&oacute;n del sexo');
?>
<div class="salto1px"></div>
<div id='div_ayuda_cara44sexov1orientasexo' class='GrupoCamposAyuda' style='display:none;'>
<?php
echo $ETI['ayuda_cara44sexov1orientasexo'];
?>
</div>
<?php
} else {
?>
<input id="cara44sexov1identidadgen" name="cara44sexov1identidadgen" type="hidden" value="<?php echo $_REQUEST['cara44sexov1identidadgen']; ?>" />
<input id="cara44sexov1orientasexo" name="cara44sexov1orientasexo" type="hidden" value="<?php echo $_REQUEST['cara44sexov1orientasexo']; ?>" />
<?php
}
?>
<div class="salto5px"></div>
<label class="Label130">
<?php
echo $ETI['cara01estcivil'];
?>
</label>
<label class="Label200">
<?php
echo $html_cara01estcivil;
?>
</label>
<div class="salto1px"></div>
<?php
echo $ETI['msg_grupospobla'];
?>
<div class="salto1px"></div>
<label class="Label90">
<?php
echo $ETI['cara01raizal'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01raizal;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara01palenquero'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01palenquero;
?>
</label>
<div class="salto1px"></div>
<label class="Label90">
<?php
echo $ETI['cara01rom'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01rom;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara01afrocolombiano'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01afrocolombiano;
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['cara44campesinado'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44campesinado;
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['cara44frontera'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44frontera;
?>
</label>
<div class="salto1px"></div>
<label class="Label250">
<?php
echo $ETI['cara01otracomunnegras'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01otracomunnegras;
?>
</label>
<div class="salto1px"></div>
<label class="Label90">
<?php
echo $ETI['cara01indigenas'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01indigenas;
?>
</label>
<div class="salto1px"></div>
</div>

<div class="GrupoCampos450">
<label class="Label130">
<?php
echo $ETI['cara01pais'];
?>
</label>
<label class="Label200">
<?php
echo $html_cara01pais;
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['cara01depto'];
?>
</label>
<label class="Label200">
<div id="div_cara01depto">
<?php
echo $html_cara01depto;
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['cara01ciudad'];
?>
</label>
<label class="Label200">
<div id="div_cara01ciudad">
<?php
echo $html_cara01ciudad;
?>
</div>
</label>
<div class="salto1px"></div>
<?php
$sMuestra = 'none';
if ($_REQUEST['cara01pais'] != '057') {
	$sMuestra = 'block';
}
?>
<div id="div_ciudad" style="display:<?php echo $sMuestra; ?>">
<label class="L">
<?php
echo $ETI['cara01nomciudad'];
?>

<input id="cara01nomciudad" name="cara01nomciudad" type="text" value="<?php echo $_REQUEST['cara01nomciudad']; ?>" maxlength="100" class="L" placeholder="<?php echo $ETI['ing_campo'] . $ETI['cara01nomciudad']; ?>" />
</label>
</div>
<label class="L">
<?php
echo $ETI['cara01direccion'];
?>

<input id="cara01direccion" name="cara01direccion" type="text" value="<?php echo $_REQUEST['cara01direccion']; ?>" maxlength="100" class="L" placeholder="<?php echo $ETI['ing_campo'] . $ETI['cara01direccion']; ?>" />
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['cara01estrato'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara01estrato;
?>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['cara01zonares'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara01zonares;
?>
</label>
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
<div class="GrupoCampos450">
<label class="Label90">
<?php
echo $ETI['cara01idzona'];
?>
</label>
<label class="Label200">
<?php
echo $html_cara01idzona;
?>
</label>
<div class="salto1px"></div>
<label class="Label90">
<?php
echo $ETI['cara01idcead'];
?>
</label>
<label class="Label200">
<div id="div_cara01idcead">
<?php
echo $html_cara01idcead;
?>
</div>
</label>
<div class="salto1px"></div>
<label>
<?php
echo $ETI['cara01matconvenio'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01matconvenio;
?>
</label>
<div class="salto1px"></div>
</div>
<div class="GrupoCampos450">
<label class="Label250">
<?php
echo $ETI['cara01victimadesplazado'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01victimadesplazado;
?>
</label>
<div class="salto1px"></div>
<?php
if ($bEstudiante) {
}
if (true) {
?>
<input id="cara01idconfirmadesp" name="cara01idconfirmadesp" type="hidden" value="<?php echo $_REQUEST['cara01idconfirmadesp']; ?>" />
<input id="cara01idconfirmadesp_td" name="cara01idconfirmadesp_td" type="hidden" value="<?php echo $_REQUEST['cara01idconfirmadesp_td']; ?>" />
<input id="cara01idconfirmadesp_doc" name="cara01idconfirmadesp_doc" type="hidden" value="<?php echo $_REQUEST['cara01idconfirmadesp_doc']; ?>" />
<input id="cara01fechaconfirmadesp" name="cara01fechaconfirmadesp" type="hidden" value="<?php echo $_REQUEST['cara01fechaconfirmadesp']; ?>" />
<?php
} else {
?>
<div class="salto1px"></div>
<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo $ETI['cara01idconfirmadesp'];
?>
</label>
<div class="salto1px"></div>
<input id="cara01idconfirmadesp" name="cara01idconfirmadesp" type="hidden" value="<?php echo $_REQUEST['cara01idconfirmadesp']; ?>" />
<div id="div_cara01idconfirmadesp_llaves">
<?php
echo $html_cara01idconfirmadesp;
?>
</div>
<div class="salto1px"></div>
<div id="div_cara01idconfirmadesp" class="L"><?php echo $cara01idconfirmadesp_rs; ?></div>
<div class="salto1px"></div>
</div>
<label class="Label130">
<?php
echo $ETI['cara01fechaconfirmadesp'];
?>
</label>
<div class="Campo220">
<?php
echo html_oculto('cara01fechaconfirmadesp', $_REQUEST['cara01fechaconfirmadesp']); //$bvacio=false,$accion=",$iagnoini=0,$iagnofin=0
?>
</div>
<div class="salto1px"></div>
<?php
}
?>
<label class="Label250" title="Agencia Colombia para la Reintegraci&oacute;n">
<?php
echo $ETI['cara01victimaacr'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01victimaacr;
?>
</label>
<div class="salto1px"></div>
<?php
if ($bEstudiante) {
}
if (true) {
?>
<input id="cara01idconfirmacr" name="cara01idconfirmacr" type="hidden" value="<?php echo $_REQUEST['cara01idconfirmacr']; ?>" />
<input id="cara01idconfirmacr_td" name="cara01idconfirmacr_td" type="hidden" value="<?php echo $_REQUEST['cara01idconfirmacr_td']; ?>" />
<input id="cara01idconfirmacr_doc" name="cara01idconfirmacr_doc" type="hidden" value="<?php echo $_REQUEST['cara01idconfirmacr_doc']; ?>" />
<input id="cara01fechaconfirmacr" name="cara01fechaconfirmacr" type="hidden" value="<?php echo $_REQUEST['cara01fechaconfirmacr']; ?>" />
<?php
} else {
?>
<div class="salto1px"></div>
<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo $ETI['cara01idconfirmacr'];
?>
</label>
<div class="salto1px"></div>
<input id="cara01idconfirmacr" name="cara01idconfirmacr" type="hidden" value="<?php echo $_REQUEST['cara01idconfirmacr']; ?>" />
<div id="div_cara01idconfirmacr_llaves">
<?php
echo $html_cara01idconfirmacr;
?>
</div>
<div class="salto1px"></div>
<div id="div_cara01idconfirmacr" class="L"><?php echo $cara01idconfirmacr_rs; ?></div>
<div class="salto1px"></div>
</div>
<label class="Label130">
<?php
echo $ETI['cara01fechaconfirmacr'];
?>
</label>
<div class="Campo220">
<?php
echo html_oculto('cara01fechaconfirmacr', $_REQUEST['cara01fechaconfirmacr']); //$bvacio=false,$accion=",$iagnoini=0,$iagnofin=0
?>
</div>
<div class="salto1px"></div>
<?php
}
?>
<label class="Label250">
<?php
echo $ETI['cara01inpecfuncionario'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01inpecfuncionario;
?>
</label>
<div class="salto1px"></div>
<label class="Label250">
<?php
echo $ETI['cara01inpecrecluso'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01inpecrecluso;
$sMuestra = '';
if ($_REQUEST['cara01inpecrecluso'] != 'S') {
	$sMuestra = ' style="display:none"';
}
?>
</label>
<div id="div_recluso" <?php echo $sMuestra; ?>>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['cara01inpectiempocondena'];
?>
</label>
<label class="Label200">
<?php
echo $html_cara01inpectiempocondena;
?>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['cara01centroreclusion'];
?>
</label>
<label class="Label200">
<?php
echo $html_cara01centroreclusion;
?>
</label>
</div>
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo $ETI['msg_contacto'];
?>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['cara01telefono1'];
?>
</label>
<label>
<input id="cara01telefono1" name="cara01telefono1" type="text" value="<?php echo $_REQUEST['cara01telefono1']; ?>" maxlength="20" placeholder="<?php echo $ETI['ing_campo'] . $ETI['cara01telefono1']; ?>" />
</label>
<label class="Label130">
<?php
echo $ETI['cara01telefono2'];
?>
</label>
<label>
<input id="cara01telefono2" name="cara01telefono2" type="text" value="<?php echo $_REQUEST['cara01telefono2']; ?>" maxlength="20" placeholder="<?php echo $ETI['ing_campo'] . $ETI['cara01telefono2']; ?>" />
</label>
<label class="L">
<?php
echo $ETI['cara01correopersonal'];
?>
<input id="cara01correopersonal" name="cara01correopersonal" type="text" value="<?php echo $_REQUEST['cara01correopersonal']; ?>" maxlength="50" placeholder="<?php echo $ETI['ing_campo'] . $ETI['cara01correopersonal']; ?>" class="L" />
</label>
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo" style="width:100%">
<?php
echo '<b>' . $ETI['msg_infocontacto'] . '</b>';
?>
</label>
<div class="salto1px"></div>
<label class="L">
<?php
echo $ETI['cara01nomcontacto'];
?>
<input id="cara01nomcontacto" name="cara01nomcontacto" type="text" value="<?php echo $_REQUEST['cara01nomcontacto']; ?>" maxlength="100" class="L" placeholder="<?php echo $ETI['ing_campo'] . $ETI['cara01nomcontacto']; ?>" />
</label>
<label class="L">
<?php
echo $ETI['cara01parentezcocontacto'];
?>
<input id="cara01parentezcocontacto" name="cara01parentezcocontacto" type="text" value="<?php echo $_REQUEST['cara01parentezcocontacto']; ?>" maxlength="100" class="L" placeholder="<?php echo $ETI['ing_campo'] . $ETI['cara01parentezcocontacto']; ?>" />
</label>
<label class="L">
<?php
echo $ETI['cara01celcontacto'];
?>
<input id="cara01celcontacto" name="cara01celcontacto" type="text" value="<?php echo $_REQUEST['cara01celcontacto']; ?>" maxlength="50" class="L" placeholder="<?php echo $ETI['ing_campo'] . $ETI['cara01celcontacto']; ?>" />
</label>
<label class="L">
<?php
echo $ETI['cara01correocontacto'];
?>
<input id="cara01correocontacto" name="cara01correocontacto" type="text" value="<?php echo $_REQUEST['cara01correocontacto']; ?>" maxlength="50" class="L" placeholder="<?php echo $ETI['ing_campo'] . $ETI['cara01correocontacto']; ?>" />
</label>
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo" style="width:500px">
<?php
if ($_REQUEST['cara01discversion'] == 2) {
	echo $ETI['msg_discapacidades_v2'];
	} else {
	echo $ETI['msg_discapacidades'];
}
?>
</label>
<?php
if ($bEstudiante) {
?>
<input id="cara01discversion" name="cara01discversion" type="hidden" value="<?php echo $_REQUEST['cara01discversion']; ?>" />
<?php
} else {
?>
<label class="Label250">
<?php
echo $ETI['cara01discversion'];
?>
</label>
<label class="Label30">
<?php
echo html_oculto('cara01discversion', $_REQUEST['cara01discversion']);
?>
</label>
<?php
}
?>
<div class="salto1px"></div>
<?php
if ($_REQUEST['cara01discversion'] != 0) {
?>
<input id="cara01discsensorial" name="cara01discsensorial" type="hidden" value="<?php echo $_REQUEST['cara01discsensorial']; ?>" />
<input id="cara01discsensorialotra" name="cara01discsensorialotra" type="hidden" value="<?php echo $_REQUEST['cara01discsensorialotra']; ?>" />
<input id="cara01discfisica" name="cara01discfisica" type="hidden" value="<?php echo $_REQUEST['cara01discfisica']; ?>" />
<input id="cara01discfisicaotra" name="cara01discfisicaotra" type="hidden" value="<?php echo $_REQUEST['cara01discfisicaotra']; ?>" />
<input id="cara01disccognitiva" name="cara01disccognitiva" type="hidden" value="<?php echo $_REQUEST['cara01disccognitiva']; ?>" />
<input id="cara01disccognitivaotra" name="cara01disccognitivaotra" type="hidden" value="<?php echo $_REQUEST['cara01disccognitivaotra']; ?>" />
<?php
} else {
?>
<label class="Label130">
<?php
echo $ETI['cara01discsensorial'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01discsensorial;
$sEstilo = ' style="display:none"';
if ($_REQUEST['cara01discsensorial'] == 9) {
	$sEstilo = '';
}
?>
</label>
<label id="lbl_cara01discsensorialotra" class="L" <?php echo $sEstilo; ?>>
<?php
echo $ETI['msg_especifique'];
?>
<input id="cara01discsensorialotra" name="cara01discsensorialotra" type="text" value="<?php echo $_REQUEST['cara01discsensorialotra']; ?>" maxlength="100" class="L" placeholder="<?php echo $ETI['ing_campo'] . $ETI['cara01discsensorialotra']; ?>" />
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['cara01discfisica'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01discfisica;
$sEstilo = ' style="display:none"';
if ($_REQUEST['cara01discfisica'] == 9) {
	$sEstilo = '';
}
?>
</label>
<label id="lbl_cara01discfisicaotra" class="L" <?php echo $sEstilo; ?>>
<?php
echo $ETI['msg_especifique'];
?>
<input id="cara01discfisicaotra" name="cara01discfisicaotra" type="text" value="<?php echo $_REQUEST['cara01discfisicaotra']; ?>" maxlength="100" class="L" placeholder="<?php echo $ETI['ing_campo'] . $ETI['cara01discfisicaotra']; ?>" />
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['cara01disccognitiva'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01disccognitiva;
$sEstilo = ' style="display:none"';
if ($_REQUEST['cara01disccognitiva'] == 9) {
	$sEstilo = '';
}
?>
</label>
<label id="lbl_cara01disccognitivaotra" class="L" <?php echo $sEstilo; ?>>
<?php
echo $ETI['msg_especifique'];
?>
<input id="cara01disccognitivaotra" name="cara01disccognitivaotra" type="text" value="<?php echo $_REQUEST['cara01disccognitivaotra']; ?>" maxlength="100" class="L" placeholder="<?php echo $ETI['ing_campo'] . $ETI['cara01disccognitivaotra']; ?>" />
</label>
<?php
}
?>
<?php
$bEntra = false;
if ($_REQUEST['cara01discversion'] == 2) {
	$bEntra = true;
}
if ($bEntra) {
?>
<label class="Label500">
<?php
echo $ETI['cara01discv2tiene'];
?>
</label>
<label>
<?php
echo $html_cara01discv2tiene;
?>
</label>
<?php
} else {
?>
<input id="cara01discv2tiene" name="cara01discv2tiene" type="hidden" value="<?php echo $_REQUEST['cara01discv2tiene']; ?>" />
<?php
}
?>
<?php
$bEntra = false;
if ($_REQUEST['cara01discversion'] == 1 || $_REQUEST['cara01discversion'] == 2) {
	$bEntra = true;
}
if ($bEntra) {
?>
<div class="salto1px"></div>
<?php
$sEstilo = '';
if ($_REQUEST['cara01discversion'] == 2) {
	$sEstilo = ' style="display:none"';
	if ($_REQUEST['cara01discv2tiene'] != 0) {
		$sEstilo = '';
	}
}
?>
<div id="div_cara01disc" <?php echo $sEstilo; ?>>
<label class="Label220">
<?php
echo $ETI['cara01discv2sensorial'];
?>
</label>
<label>
<?php
echo $html_cara01discv2sensorial;
?>
</label>
<div class="salto1px"></div>
<label class="Label220">
<?php
echo $ETI['cara02discv2intelectura'];
?>
</label>
<label>
<?php
echo $html_cara02discv2intelectura;
?>
</label>
<div class="salto1px"></div>
<label class="Label220">
<?php
echo $ETI['cara02discv2fisica'];
?>
</label>
<label>
<?php
echo $html_cara02discv2fisica;
?>
</label>
<div class="salto1px"></div>
<?php
if ($_REQUEST['cara01discversion'] == 1) {
	echo '<label class="Label350">' . $ETI['cara02discv2psico'] . '</label>';
} else {
	echo '<label class="Label220">' . $ETI['cara02discv2psico_v2'] . '</label>';
}
?>
<label>
<?php
echo $html_cara02discv2psico;
?>
</label>
<?php
} else {
?>
<input id="cara01discv2sensorial" name="cara01discv2sensorial" type="hidden" value="<?php echo $_REQUEST['cara01discv2sensorial']; ?>" />
<input id="cara02discv2intelectura" name="cara02discv2intelectura" type="hidden" value="<?php echo $_REQUEST['cara02discv2intelectura']; ?>" />
<input id="cara02discv2fisica" name="cara02discv2fisica" type="hidden" value="<?php echo $_REQUEST['cara02discv2fisica']; ?>" />
<input id="cara02discv2psico" name="cara02discv2psico" type="hidden" value="<?php echo $_REQUEST['cara02discv2psico']; ?>" />
<?php
}
$bEntra = false;
if ($_REQUEST['cara01discversion'] == 1) {
	$bEntra = true;
}
if ($bEntra) {
?>
<div class="salto1px"></div>
<label class="Label220">
<?php
echo $ETI['cara02discv2sistemica'];
?>
</label>
<label>
<?php
echo $html_cara02discv2sistemica;
?>
</label>
<label class="L">
<?php
$sEtiqueta = $ETI['cara02discv2sistemicaotro'];
echo $sEtiqueta;
?>
<input id="cara02discv2sistemicaotro" name="cara02discv2sistemicaotro" type="text" value="<?php echo $_REQUEST['cara02discv2sistemicaotro']; ?>" maxlength="250" class="L" placeholder="<?php echo $ETI['ing_campo'] . $sEtiqueta; ?>" />
</label>
<?php
} else {
?>
<input id="cara02discv2sistemica" name="cara02discv2sistemica" type="hidden" value="<?php echo $_REQUEST['cara02discv2sistemica']; ?>" />
<input id="cara02discv2sistemicaotro" name="cara02discv2sistemicaotro" type="hidden" value="<?php echo $_REQUEST['cara02discv2sistemicaotro']; ?>" />
<?php
}
$bEntra = false;
if ($_REQUEST['cara01discversion'] == 1 || $_REQUEST['cara01discversion'] == 2) {
	$bEntra = true;
}
if ($bEntra) {
?>
<div class="salto1px"></div>
<label class="Label220">
<?php
echo $ETI['cara02discv2multiple'];
?>
</label>
<label>
<?php
echo $html_cara02discv2multiple;
?>
</label>
<?php
$sEstilo = '';
if ($_REQUEST['cara01discversion'] == 2) {
	$sEstilo = ' style="display:none"';
	if ($_REQUEST['cara02discv2multiple'] == 1) {
		$sEstilo = '';
	}
}
?>
<label id="lbl_cara02discv2multipleotro" class="L" <?php echo $sEstilo; ?>>
<?php
$sEtiqueta = $ETI['cara02discv2multipleotro_v2'];
if ($_REQUEST['cara01discversion'] == 2) {
	$sEtiqueta = $ETI['cara02discv2multipleotro_v2'];
}
echo $sEtiqueta;
?>
<input id="cara02discv2multipleotro" name="cara02discv2multipleotro" type="text" value="<?php echo $_REQUEST['cara02discv2multipleotro']; ?>" maxlength="250" class="L" placeholder="<?php echo $ETI['ing_campo'] . $sEtiqueta; ?>" />
</label>
<?php
} else {
?>
<input id="cara02discv2multiple" name="cara02discv2multiple" type="hidden" value="<?php echo $_REQUEST['cara02discv2multiple']; ?>" />
<input id="cara02discv2multipleotro" name="cara02discv2multipleotro" type="hidden" value="<?php echo $_REQUEST['cara02discv2multipleotro']; ?>" />
<?php
}
?>
<div class="salto1px"></div>
<label class="L">
<?php
echo $ETI['cara01perayuda'];
?>
</label>
<label class="Label220">&nbsp;</label>
<label>
<?php
echo $html_cara01perayuda;
$sEstilo = ' style="display:none"';
if ($_REQUEST['cara01perayuda'] == -1 || $_REQUEST['cara01perayuda'] == 11) {
	$sEstilo = '';
}
?>
</label>
<label id="lbl_cara01perotraayuda" class="L" <?php echo $sEstilo; ?>>
<?php
echo $ETI['cara01perotraayuda'];
?>
<input id="cara01perotraayuda" name="cara01perotraayuda" type="text" value="<?php echo $_REQUEST['cara01perotraayuda']; ?>" maxlength="200" class="L" placeholder="<?php echo $ETI['ing_campo'] . $ETI['cara01perotraayuda']; ?>" />
</label>
<div class="salto1px"></div>
<input id="cara01discv2soporteorigen" name="cara01discv2soporteorigen" type="hidden" value="<?php echo $_REQUEST['cara01discv2soporteorigen']; ?>" />
<input id="cara01discv2archivoorigen" name="cara01discv2archivoorigen" type="hidden" value="<?php echo $_REQUEST['cara01discv2archivoorigen']; ?>" />
<?php
$sEstilo = ' style="display:none"';
if ($_REQUEST['cara01discversion'] == 2) {
	$sEstilo = '';
}
?>
<div class="GrupoCampos300" <?php echo $sEstilo; ?>>
<div class="salto1px"></div>
<label class="TituloGrupo">
<?php
echo $ETI['msg_certificaciondisc']
?>
</label>
<div class="salto1px"></div>
<div id="div_cara01discv2archivoorigen" class="Campo250">
<?php
echo html_lnkarchivo((int)$_REQUEST['cara01discv2soporteorigen'], (int)$_REQUEST['cara01discv2archivoorigen']);
?>
</div>
<div class="salto1px"></div>
<?php
$sEstiloBoton = 'display:none;';
if ((int)$_REQUEST['cara01id'] != 0) {
    $sEstiloBoton = 'display:block;';
}
$sEstiloElimina = 'display:none;';
if (!$bEstudiante && (int)$_REQUEST['cara01discv2archivoorigen'] != 0) {
    $sEstiloElimina = 'display:block;';
}
echo $objForma->htmlBotonSolo('banexacara01discv2archivoorigen', 'btMiniAnexar', 'carga_cara01discv2archivoorigen()', 'Cargar archivo', 30, $sEstiloBoton);
echo $objForma->htmlBotonSolo('beliminacara01discv2archivoorigen', 'btMiniEliminar', 'eliminacara01discv2archivoorigen()', 'Eliminar archivo', 30, $sEstiloElimina);
?>
<div class="salto1px"></div>
</div>
<?php
//Termina el div que muestra las incapacidades. div_cara01disc
?>
</div>
<?php
$bEntra = false;
if ($_REQUEST['cara01discversion'] == 2) {
	$bEntra = true;
}
if ($bEntra) {
?>
<div class="salto1px"></div>
<hr />
<div class="salto1px"></div>
<label class="Label500">
<?php
echo $ETI['cara01discv2trastornos'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01discv2trastornos;
?>
</label>
<?php
} else {
?>
<input id="cara01discv2trastornos" name="cara01discv2trastornos" type="hidden" value="<?php echo $_REQUEST['cara01discv2trastornos']; ?>" />
<?php
}
?>
<div class="salto1px"></div>
<?php
$sEstilo = ' style="display:none"';
if ($_REQUEST['cara01discversion'] == 2) {
if ($_REQUEST['cara01discv2trastornos'] != 0) {
$sEstilo = '';
}
}
?>
<div id="div_cara01discv2trastornos" <?php echo $sEstilo; ?>>
<label class="Label220">
<?php
echo $ETI['cara01discv2trastaprende'];
?>
</label>
<label>
<?php
echo $html_cara01discv2trastaprende;
?>
</label>
</div>
<div class="salto1px"></div>

<?php
$bEntra = false;
if ($_REQUEST['cara01discversion'] == 2) {
$bEntra = true;
}
if ($bEntra) {
?>
<div class="salto1px"></div>
<div class="GrupoCamposAyuda">
<?php
echo $AYU['cara01discv2trastornos'];
?>
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>

<div class="salto1px"></div>
<hr />
<div class="salto1px"></div>
<label class="Label500">
<?php
echo $ETI['cara01discv2contalento'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01discv2contalento;
?>
</label>
<?php
} else {
?>
<input id="cara01discv2contalento" name="cara01discv2contalento" type="hidden" value="<?php echo $_REQUEST['cara01discv2contalento']; ?>" />
<?php
}
?>

<div class="salto1px"></div>
<?php
$sEstilo = ' style="display:none"';
if ($_REQUEST['cara01discversion'] == 2) {
if ($_REQUEST['cara01discv2contalento'] != 0) {
$sEstilo = '';
}
}
?>
<div id="div_cara01discv2contalento" <?php echo $sEstilo; ?>>
<?php
$bEntra = false;
if ($_REQUEST['cara01discversion'] == 1) {
$bEntra = true;
}
if ($_REQUEST['cara01discversion'] == 2) {
$bEntra = true;
}
if ($bEntra) {
?>
<div class="salto1px"></div>
<label class="Label220">
<?php
echo $ETI['cara02talentoexcepcional'];
?>
</label>
<label>
<?php
echo $html_cara02talentoexcepcional;
?>
</label>
<?php
} else {
?>
<input id="cara02talentoexcepcional" name="cara02talentoexcepcional" type="hidden" value="<?php echo $_REQUEST['cara02talentoexcepcional']; ?>" />
<?php
}
?>
<div class="salto1px"></div>
</div>

<?php
$bEntra = false;
if ($_REQUEST['cara01discversion'] == 2) {
	$bEntra = true;
}
if ($bEntra) {
?>
<div class="salto1px"></div>
<div class="GrupoCamposAyuda">
<?php
echo $AYU['cara01discv2contalento'];
?>
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
<hr />
<div class="salto1px"></div>
<label class="Label500">
<?php
echo $ETI['cara01discv2pruebacoeficiente'];
?>
</label>
<label>
<?php
echo $html_cara01discv2pruebacoeficiente;
?>
</label>

<div class="salto1px"></div>
<label class="Label500">
<?php
echo $ETI['cara01discv2condicionmedica'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01discv2condicionmedica;
?>
</label>
<?php
$sEstilo = ' style="display:none"';
if ($_REQUEST['cara01discv2condicionmedica'] != 0) {
	$sEstilo = '';
}
?>
<label id="lbl_cara01discv2condmeddet" class="L" <?php echo $sEstilo; ?>>
<?php
echo $ETI['cara01discv2condmeddet'];
?>

<input id="cara01discv2condmeddet" name="cara01discv2condmeddet" type="text" value="<?php echo $_REQUEST['cara01discv2condmeddet']; ?>" maxlength="250" class="L" placeholder="<?php echo $ETI['ing_campo'] . $ETI['cara01discv2condmeddet']; ?>" />
</label>
<div class="salto1px"></div>
<div class="GrupoCamposAyuda">
<?php
echo $AYU['cara01discv2condicionmedica'];
?>
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
<?php
} else {
?>
<input id="cara01discv2condicionmedica" name="cara01discv2condicionmedica" type="hidden" value="<?php echo $_REQUEST['cara01discv2condicionmedica']; ?>" />
<input id="cara01discv2condmeddet" name="cara01discv2condmeddet" type="hidden" value="<?php echo $_REQUEST['cara01discv2condmeddet']; ?>" />
<?php
}
?>
<div class="salto1px"></div>
<?php
$sEstilo = ' style="display:none"';
if ($_REQUEST['cara44med_tratamiento'] != 0) {
	$sEstilo = '';
}
?>
<label class="Label500">
<?php
echo $ETI['cara44med_tratamiento'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44med_tratamiento;
?>
</label>
<label id="lbl_cara44med_trat_cual" class="L" <?php echo $sEstilo; ?>>
<?php
echo $ETI['cara44med_trat_cual'];
?>
<input id="cara44med_trat_cual" name="cara44med_trat_cual" type="text" value="<?php echo $_REQUEST['cara44med_trat_cual']; ?>" maxlength="200" class="L" placeholder="<?php echo $ETI['cara44med_trat_cual_place']; ?>" />
</label>

<div class="salto1px"></div>
</div>
<?php
$bConConfirmarDiscapacidad = false;
if (!$bEstudiante) {
$bConConfirmarDiscapacidad = true;
/*
switch($_REQUEST['cara01discversion']){
case 0:
break;
case 1:
break;
case 2:
break;
}
*/
}
if (!$bConConfirmarDiscapacidad) {
?>
<input id="cara01idconfirmadisc" name="cara01idconfirmadisc" type="hidden" value="<?php echo $_REQUEST['cara01idconfirmadisc']; ?>" />
<input id="cara01idconfirmadisc_td" name="cara01idconfirmadisc_td" type="hidden" value="<?php echo $_REQUEST['cara01idconfirmadisc_td']; ?>" />
<input id="cara01idconfirmadisc_doc" name="cara01idconfirmadisc_doc" type="hidden" value="<?php echo $_REQUEST['cara01idconfirmadisc_doc']; ?>" />
<input id="cara01fechaconfirmadisc" name="cara01fechaconfirmadisc" type="hidden" value="<?php echo $_REQUEST['cara01fechaconfirmadisc']; ?>" />
<?php
} else {
?>
<div class="salto1px"></div>
<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo $ETI['cara01idconfirmadisc'];
?>
</label>
<div class="salto1px"></div>
<input id="cara01idconfirmadisc" name="cara01idconfirmadisc" type="hidden" value="<?php echo $_REQUEST['cara01idconfirmadisc']; ?>" />
<div id="div_cara01idconfirmadisc_llaves">
<?php
echo $html_cara01idconfirmadisc;
?>
</div>
<div class="salto1px"></div>
<div id="div_cara01idconfirmadisc" class="L"><?php echo $cara01idconfirmadisc_rs; ?></div>
<div class="salto1px"></div>
</div>
<div class="GrupoCampos450">
<label class="Label130">
<?php
echo $ETI['cara01fechaconfirmadisc'];
?>
</label>
<label class="Label220">
<?php
echo html_oculto('cara01fechaconfirmadisc', $_REQUEST['cara01fechaconfirmadisc'], formato_FechaLargaDesdeNumero($_REQUEST['cara01fechaconfirmadisc']));
?>
</label>
<?php
if ($_REQUEST['paso'] != 0) {
?>
<div class="salto1px"></div>
<label class="Label130">&nbsp;</label>
<?php
echo $objForma->htmlBotonSolo('cmdConfirmarDisc', 'botonAprobado azul', 'confirmadisc()', 'Confirmar datos de discapacidad', 130, '', 'Confirmar');
?>
<?php
}
?>
<div class="salto1px"></div>
</div>
<?php
}
?>
<?php
if ($bEstudiante) {
if ($_REQUEST['paso'] == 2){
	if ($bPintarTablero){
		echo html_2201Tablero(1, $objForma);
	} else {
		echo html_2201ContinuarCerrar(1, $objForma);
	}
}
}
?>
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
</div>
<?php
}
if ($bGrupo2) {
	$sEstilo = '';
	if ($_REQUEST['cara01fichafam'] == -1) {
		$sEstilo = ' style="display:none"';
	}
	if ($bEstudiante) {
		$sEstilo = ' style="display:none"';
		if ($_REQUEST['ficha'] == 2) {
			$sEstilo = '';
		}
	}
?>
<div class="salto1px"></div>
<div class="GrupoCampos" id="div_ficha2" <?php echo $sEstilo; ?>>
<?php
if ($bConExpande) {
?>
<div class="ir_derecha"<?php echo $sAnchoExpandeContrae; ?>>
<?php
echo $objForma->htmlExpande(102, $_REQUEST['boculta102'], $ETI['bt_mostrar'], $ETI['bt_ocultar']);
$sEstiloDiv = '';
if ($_REQUEST['boculta102'] != 0) {
	$sEstiloDiv = ' style="display:none;"';
}
?>
</div>
<?php
}
?>
<label class="TituloGrupo">
<?php
echo $ETI['cara01fichafam'];
?>
</label>
<label class="Label130">
<div id="div_cara01fichafam">
<?php
$sMuestra = '&nbsp;';
if ($_REQUEST['cara01fichafam'] == 1) {
$sMuestra = 'COMPLETA';
}
echo html_oculto('cara01fichafam', $_REQUEST['cara01fichafam'], $sMuestra);
?>
</div>
</label>
<div class="salto1px"></div>
<div id="div_p102"<?php echo $sEstiloDiv; ?>>
<label class="Label450">
<?php
echo $ETI['cara01fam_tipovivienda'];
?>
</label>
<label>
<?php
echo $html_cara01fam_tipovivienda;
?>
</label>
<div class="salto1px"></div>
<label class="Label450">
<?php
echo $ETI['cara01fam_vivecon'];
?>
</label>
<label>
<?php
echo $html_cara01fam_vivecon;
?>
</label>
<div class="salto1px"></div>
<label class="Label450">
<?php
echo $ETI['cara01fam_numpersgrupofam'];
?>
</label>
<label>
<?php
echo $html_cara01fam_numpersgrupofam;
?>
</label>
<div class="salto1px"></div>
<label class="Label450">
<?php
echo $ETI['cara01fam_hijos'];
?>
</label>
<label>
<?php
echo $html_cara01fam_hijos;
$sMuestra = ' style="display:none"';
if ($_REQUEST['cara01sexo'] == 'F' && $_REQUEST['cara01fam_hijos'] >= 1 && $_REQUEST['cara01fam_hijos'] <= 4) {
	$sMuestra = '';
}
?>
</label>
<div class="salto1px"></div>
<div id="div_cara44fam_madrecabeza" <?php echo $sMuestra; ?>>
<label class="Label450">
<?php
echo $ETI['cara44fam_madrecabeza'];
?>
</label>
<label class="Label90">
<?php
echo $html_cara44fam_madrecabeza;
?>
</label>
</div>
<div class="salto1px"></div>
<label class="Label450">
<?php
echo $ETI['cara01fam_personasacargo'];
?>
</label>
<label>
<?php
echo $html_cara01fam_personasacargo;
?>
</label>
<div class="salto1px"></div>
<label class="Label450">
<?php
echo $ETI['cara01fam_dependeecon'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara01fam_dependeecon;
?>
</label>
<div class="salto1px"></div>
<label class="Label450">
<?php
echo $ETI['cara01fam_escolaridadpadre'];
?>
</label>
<label>
<?php
echo $html_cara01fam_escolaridadpadre;
?>
</label>
<div class="salto1px"></div>
<label class="Label450">
<?php
echo $ETI['cara01fam_escolaridadmadre'];
?>
</label>
<label>
<?php
echo $html_cara01fam_escolaridadmadre;
?>
</label>
<div class="salto1px"></div>
<label class="Label450">
<?php
echo $ETI['cara01fam_numhermanos'];
?>
</label>
<label>
<?php
echo $html_cara01fam_numhermanos;
?>
</label>
<div class="salto1px"></div>
<label class="Label450">
<?php
echo $ETI['cara01fam_posicionherm'];
?>
</label>
<label>
<?php
echo $html_cara01fam_posicionherm;
?>
</label>
<div class="salto1px"></div>
<label class="Label450">
<?php
echo $ETI['cara01fam_familiaunad'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara01fam_familiaunad;
?>
</label>
<?php
if ($bEstudiante) {
if ($_REQUEST['paso'] == 2){
	if ($bPintarTablero){
		echo html_2201Tablero(2, $objForma);
	} else {
		echo html_2201ContinuarCerrar(2, $objForma);
	}
}
}
?>
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
</div>
<?php
}
if ($bGrupo3) {
	$sEstilo = '';
	if ($_REQUEST['cara01fichaaca'] == -1) {
		$sEstilo = ' style="display:none"';
	}
	if ($bEstudiante) {
		$sEstilo = ' style="display:none"';
		if ($_REQUEST['ficha'] == 3) {
			$sEstilo = '';
		}
	}
?>
<div class="salto1px"></div>
<div class="GrupoCampos" id="div_ficha3" <?php echo $sEstilo; ?>>
<?php
if ($bConExpande) {
?>
<div class="ir_derecha"<?php echo $sAnchoExpandeContrae; ?>>
<?php
echo $objForma->htmlExpande(103, $_REQUEST['boculta103'], $ETI['bt_mostrar'], $ETI['bt_ocultar']);
$sEstiloDiv = '';
if ($_REQUEST['boculta103'] != 0) {
	$sEstiloDiv = ' style="display:none;"';
}
?>
</div>
<?php
}
?>
<label class="TituloGrupo">
<?php
echo $ETI['cara01fichaaca'];
?>
</label>
<label class="Label130">
<div id="div_cara01fichaaca">
<?php
$sMuestra = '&nbsp;';
if ($_REQUEST['cara01fichaaca'] == 1) {
$sMuestra = 'COMPLETA';
}
echo html_oculto('cara01fichaaca', $_REQUEST['cara01fichaaca'], $sMuestra);
?>
</div>
</label>
<div class="salto1px"></div>
<div id="div_p103"<?php echo $sEstiloDiv; ?>>
<?php
if (!$bAntiguo) {
?>
<label class="Label450">
<?php
echo $ETI['cara01acad_tipocolegio'];
?>
</label>
<label>
<?php
echo $html_cara01acad_tipocolegio;
?>
</label>
<div class="salto1px"></div>
<label class="Label450">
<?php
echo $ETI['cara01acad_modalidadbach'];
?>
</label>
<label>
<?php
echo $html_cara01acad_modalidadbach;
?>
</label>
<div class="salto1px"></div>
<?php
} else {
?>
<input id="cara01acad_tipocolegio" name="cara01acad_tipocolegio" type="hidden" value="<?php echo $_REQUEST['cara01acad_tipocolegio']; ?>" />
<input id="cara01acad_modalidadbach" name="cara01acad_modalidadbach" type="hidden" value="<?php echo $_REQUEST['cara01acad_modalidadbach']; ?>" />
<?php
}
?>
<label class="Label450">
<?php
echo $ETI['cara01acad_estudioprev'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara01acad_estudioprev;
?>
</label>
<div class="salto1px"></div>
<label class="Label450">
<?php
echo $ETI['cara01acad_ultnivelest'];
?>
</label>
<label>
<?php
echo $html_cara01acad_ultnivelest;
?>
</label>
<div class="salto1px"></div>
<?php
if ($bMuestraTiempoSinEstudiar) {
?>
<label class="Label450">
<?php
echo $ETI['cara01acad_tiemposinest'];
?>
</label>
<label>
<?php
echo $html_cara01acad_tiemposinest;
?>
</label>
<div class="salto1px"></div>
<?php
} else {
?>
<input id="cara01acad_tiemposinest" name="cara01acad_tiemposinest" type="hidden" value="<?php echo $_REQUEST['cara01acad_tiemposinest']; ?>" />
<?php
}
if ($bPasoBachiller) {
?>
<label class="Label450">
<?php
echo $ETI['cara01acad_obtubodiploma'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara01acad_obtubodiploma;
?>
</label>
<div class="salto1px"></div>
<?php
} else {
?>
<input id="cara01acad_obtubodiploma" name="cara01acad_obtubodiploma" type="hidden" value="N" />
<?php
}
if (!$bAntiguo) {
?>
<label class="Label450">
<?php
echo $ETI['cara01acad_hatomadovirtual'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara01acad_hatomadovirtual;
?>
</label>
<div class="salto1px"></div>
<?php
} else {
?>
<input id="cara01acad_hatomadovirtual" name="cara01acad_hatomadovirtual" type="hidden" value="<?php echo $_REQUEST['cara01acad_hatomadovirtual']; ?>" />
<?php
}
?>
<label class="Label450">
<?php
echo $ETI['cara01acad_razonestudio'];
?>
</label>
<label>
<?php
echo $html_cara01acad_razonestudio;
?>
</label>
<div class="salto1px"></div>
<label class="Label450">
<?php
echo $ETI['cara01acad_primeraopc'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara01acad_primeraopc;
$sMuestra = ' style="display:none"';
if ($_REQUEST['cara01acad_primeraopc'] == 'N') {
$sMuestra = '';
}
?>
</label>
<label id="label_programagusto" class="L" <?php echo $sMuestra; ?>>
<?php
echo $ETI['cara01acad_programagusto'];
?>

<input id="cara01acad_programagusto" name="cara01acad_programagusto" type="text" value="<?php echo $_REQUEST['cara01acad_programagusto']; ?>" maxlength="250" class="L" placeholder="<?php echo $ETI['ing_campo'] . $ETI['cara01acad_programagusto']; ?>" />
</label>
<div class="salto1px"></div>
<label class="Label450">
<?php
echo $ETI['cara01acad_razonunad'];
?>
</label>
<label>
<?php
echo $html_cara01acad_razonunad;
?>
</label>
<div class="salto1px"></div>
<?php
if ($bAntiguo) {
?>
<label class="Label450">
<?php
echo $ETI['cara44acadhatenidorecesos'];
?>
</label>
<label class="Label90">
<?php
echo $html_cara44acadhatenidorecesos;
$sMuestra = ' style="display:none"';
if ($_REQUEST['cara44acadhatenidorecesos'] == 'S') {
$sMuestra = '';
}
?>
</label>
<div class="salto1px"></div>
<div id="div_cara44acadrazonreceso" <?php echo $sMuestra; ?>>
<label class="Label450">
<?php
echo $ETI['cara44acadrazonreceso'];
?>
</label>
<label>
<?php
echo $html_cara44acadrazonreceso;
$sMuestra = ' style="display:none"';
if ($_REQUEST['cara44acadrazonreceso'] == 'S') {
$sMuestra = '';
}
?>
</label>
<label id="lbl_cara44acadrazonrecesodetalle" class="L" <?php echo $sMuestra; ?>>
<?php
echo $ETI['cara44acadrazonrecesodetalle'];
?>
<input id="cara44acadrazonrecesodetalle" name="cara44acadrazonrecesodetalle" type="text" value="<?php echo $_REQUEST['cara44acadrazonrecesodetalle']; ?>" maxlength="200" class="L" placeholder="<?php echo $ETI['ing_campo'] . $ETI['cara44acadrazonrecesodetalle']; ?>" />
</label>
</div>
<div class="salto1px"></div>
<?php
} else {
?>
<input id="cara44acadhatenidorecesos" name="cara44acadhatenidorecesos" type="hidden" value="<?php echo $_REQUEST['cara44acadhatenidorecesos']; ?>" />
<input id="cara44acadrazonreceso" name="cara44acadrazonreceso" type="hidden" value="<?php echo $_REQUEST['cara44acadrazonreceso']; ?>" />
<input id="cara44acadrazonrecesodetalle" name="cara44acadrazonrecesodetalle" type="hidden" value="<?php echo $_REQUEST['cara44acadrazonrecesodetalle']; ?>" />
<?php
}
?>
<?php
echo '<b>' . $ETI['cara01campus'] . '</b>';
?>
<div class="salto1px"></div>
<label class="Label200">
<?php
echo $ETI['cara01campus_compescrito'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01campus_compescrito;
?>
</label>
<label class="Label160">
<?php
echo $ETI['cara01campus_portatil'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01campus_portatil;
?>
</label>
<label class="Label90">
<?php
echo $ETI['cara01campus_tableta'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01campus_tableta;
?>
</label>
<label class="Label200">
<?php
echo $ETI['cara01campus_telefono'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01campus_telefono;
?>
</label>

<div class="salto1px"></div>
<div class="salto1px"></div>
<label class="Label450">
<?php
echo $ETI['cara01campus_energia'];
?>
</label>
<label>
<?php
echo $html_cara01campus_energia;
?>
</label>
<div class="salto1px"></div>
<label class="Label450">
<?php
echo $ETI['cara01campus_internetreside'];
?>
</label>
<label>
<?php
echo $html_cara01campus_internetreside;
?>
</label>
<div class="salto1px"></div>
<?php
if (!$bAntiguo) {
?>
<label class="Label450">
<?php
echo $ETI['cara01campus_expvirtual'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara01campus_expvirtual;
?>
</label>
<div class="salto1px"></div>
<?php
} else {
?>
<input id="cara01campus_expvirtual" name="cara01campus_expvirtual" type="hidden" value="<?php echo $_REQUEST['cara01campus_expvirtual']; ?>" />
<?php
}
?>
<label class="Label450">
<?php
echo $ETI['cara01campus_ofimatica'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara01campus_ofimatica;
?>
</label>
<div class="salto1px"></div>
<?php
if (!$bAntiguo) {
?>
<label class="Label450">
<?php
echo $ETI['cara01campus_foros'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara01campus_foros;
?>
</label>
<div class="salto1px"></div>
<?php
} else {
?>
<input id="cara01campus_foros" name="cara01campus_foros" type="hidden" value="<?php echo $_REQUEST['cara01campus_foros']; ?>" />
<?php
}
?>
<label class="Label450">
<?php
echo $ETI['cara01campus_conversiones'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara01campus_conversiones;
?>
</label>
<div class="salto1px"></div>
<?php
if (!$bAntiguo) {
?>
<label class="Label450">
<?php
echo $ETI['cara01campus_usocorreo'];
?>
</label>
<label>
<?php
echo $html_cara01campus_usocorreo;
?>
</label>
<div class="salto1px"></div>
<input id="cara44campus_usocorreounad" name="cara44campus_usocorreounad" type="hidden" value="<?php echo $_REQUEST['cara44campus_usocorreounad']; ?>" />
<input id="cara44campus_usocorreounadno" name="cara44campus_usocorreounadno" type="hidden" value="<?php echo $_REQUEST['cara44campus_usocorreounadno']; ?>" />
<input id="cara44campus_usocorreounadnodetalle" name="cara44campus_usocorreounadnodetalle" type="hidden" value="<?php echo $_REQUEST['cara44campus_usocorreounadnodetalle']; ?>" />
<?php
} else {
?>
<input id="cara01campus_usocorreo" name="cara01campus_usocorreo" type="hidden" value="<?php echo $_REQUEST['cara01campus_usocorreo']; ?>" />
<label class="Label450">
<?php
echo $ETI['cara44campus_usocorreounad'];
?>
</label>
<label class="Label90">
<?php
echo $html_cara44campus_usocorreounad;
$sMuestra = ' style="display:none"';
if ($_REQUEST['cara44campus_usocorreounad'] == 4) {
$sMuestra = '';
}
?>
</label>
<div class="salto1px"></div>
<div id="div_cara44campus_usocorreounadno" <?php echo $sMuestra; ?>>
<label class="Label450">
<?php
echo $ETI['cara44campus_usocorreounadno'];
?>
</label>
<label>
<?php
echo $html_cara44campus_usocorreounadno;
$sMuestra = ' style="display:none"';
if ($_REQUEST['cara44campus_usocorreounadno'] == 5) {
$sMuestra = '';
}
?>
</label>
<label id="lbl_cara44campus_usocorreounadnodetalle" class="L" <?php echo $sMuestra; ?>>
<?php
echo $ETI['cara44campus_usocorreounadnodetalle'];
?>
<input id="cara44campus_usocorreounadnodetalle" name="cara44campus_usocorreounadnodetalle" type="text" value="<?php echo $_REQUEST['cara44campus_usocorreounadnodetalle']; ?>" maxlength="200" class="L" placeholder="<?php echo $ETI['ing_campo'] . $ETI['cara44campus_usocorreounadnodetalle']; ?>" />
</label>
</div>
<div class="salto1px"></div>
<?php
}
?>
<?php
echo '<b>' . $ETI['cara01campus_aprend'] . '</b>';
?>
<div class="salto1px"></div>
<label class="Label90">
<?php
echo $ETI['cara01campus_aprendtexto'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01campus_aprendtexto;
?>
</label>
<label class="Label90">
<?php
echo $ETI['cara01campus_aprendvideo'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01campus_aprendvideo;
?>
</label>
<label class="Label200">
<?php
echo $ETI['cara01campus_aprendmapas'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01campus_aprendmapas;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara01campus_aprendeanima'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01campus_aprendeanima;
?>
</label>
<div class="salto1px"></div>
<label class="Label450">
<?php
echo $ETI['cara01campus_mediocomunica'];
?>
</label>
<label>
<?php
echo $html_cara01campus_mediocomunica;
?>
</label>
<?php
if ($bAntiguo) {
?>
<div class="salto1px"></div>
<label class="Label450">
<?php
echo $ETI['cara44campus_medioactivunad'];
?>
</label>
<label>
<?php
echo $html_cara44campus_medioactivunad;
$sMuestra = ' style="display:none"';
if ($_REQUEST['cara44campus_medioactivunad'] == 6) {
$sMuestra = '';
}
?>
</label>
<label id="lbl_cara44campus_medioactivunaddetalle" class="L" <?php echo $sMuestra; ?>>
<?php
echo $ETI['cara44campus_medioactivunaddetalle'];
?>
<input id="cara44campus_medioactivunaddetalle" name="cara44campus_medioactivunaddetalle" type="text" value="<?php echo $_REQUEST['cara44campus_medioactivunaddetalle']; ?>" maxlength="200" class="L" placeholder="<?php echo $ETI['ing_campo'] . $ETI['cara44campus_medioactivunaddetalle']; ?>" />
</label>
<?php
} else {
?>
<input id="cara44campus_medioactivunad" name="cara44campus_medioactivunad" type="hidden" value="<?php echo $_REQUEST['cara44campus_medioactivunad']; ?>" />
<input id="cara44campus_medioactivunaddetalle" name="cara44campus_medioactivunaddetalle" type="hidden" value="<?php echo $_REQUEST['cara44campus_medioactivunaddetalle']; ?>" />
<?php
}
?>
<?php
if ($bEstudiante) {
if ($_REQUEST['paso'] == 2){
	if ($bPintarTablero){
		echo html_2201Tablero(3, $objForma);
	} else {
		echo html_2201ContinuarCerrar(3, $objForma);
	}
}
}
?>
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
</div>
<?php
}
if ($bGrupo4) {
	$sEstilo = '';
	if ($_REQUEST['cara01fichalab'] == -1) {
		$sEstilo = ' style="display:none"';
	}
	if ($bEstudiante) {
		$sEstilo = ' style="display:none"';
		if ($_REQUEST['ficha'] == 4) {
			$sEstilo = '';
		}
	}
?>
<div class="salto1px"></div>
<div class="GrupoCampos" id="div_ficha4" <?php echo $sEstilo; ?>>
<?php
if ($bConExpande) {
?>
<div class="ir_derecha"<?php echo $sAnchoExpandeContrae; ?>>
<?php
echo $objForma->htmlExpande(104, $_REQUEST['boculta104'], $ETI['bt_mostrar'], $ETI['bt_ocultar']);
$sEstiloDiv = '';
if ($_REQUEST['boculta104'] != 0) {
	$sEstiloDiv = ' style="display:none;"';
}
?>
</div>
<?php
}
?>
<label class="TituloGrupo">
<?php
echo $ETI['cara01fichalab'];
?>
</label>
<label class="Label130">
<div id="div_cara01fichalab">
<?php
$sMuestra = '&nbsp;';
if ($_REQUEST['cara01fichalab'] == 1) {
$sMuestra = 'COMPLETA';
}
echo html_oculto('cara01fichalab', $_REQUEST['cara01fichalab'], $sMuestra);
?>
</div>
</label>
<div class="salto1px"></div>
<div id="div_p104"<?php echo $sEstiloDiv; ?>>
<label class="Label450">
<?php
echo $ETI['cara01lab_situacion'];
?>
</label>
<label>
<?php
echo $html_cara01lab_situacion;
$sMuestra1 = ' style="display:none"';
$sMuestra2 = ' style="display:none"';
$sMuestra3 = ' style="display:none"';
$sMuestra4 = ' style="display:none"';
$sMuestra5 = ' style="display:none"';
$sMuestra6 = ' style="display:none"';
if ($_REQUEST['cara01lab_situacion'] == 1) {
$sMuestra1 = '';
$sMuestra2 = '';
$sMuestra3 = '';
$sMuestra6 = '';
}
if ($_REQUEST['cara01lab_situacion'] == 2) {
$sMuestra1 = '';
$sMuestra4 = '';
$sMuestra6 = '';
}
if ($_REQUEST['cara01lab_situacion'] == 3) {
$sMuestra3 = '';
$sMuestra5 = '';
$sMuestra6 = '';
}
if ($_REQUEST['cara01lab_situacion'] == 4) {
$sMuestra5 = '';
$sMuestra6 = '';
}
?>
</label>
<div class="salto1px"></div>
<div id="div_lab1" <?php echo $sMuestra1; ?>>
<label class="Label450">
<?php
echo $ETI['cara01lab_sector'];
?>
</label>
<label>
<?php
echo $html_cara01lab_sector;
?>
</label>
<div class="salto1px"></div>
<label class="Label450">
<?php
echo $ETI['cara01lab_caracterjuri'];
?>
</label>
<label>
<?php
echo $html_cara01lab_caracterjuri;
?>
</label>
<div class="salto1px"></div>
<label class="Label450">
<?php
echo $ETI['cara01lab_cargo'];
?>
</label>
<label>
<?php
echo $html_cara01lab_cargo;
?>
</label>
<div class="salto1px"></div>
<label class="Label450">
<?php
echo $ETI['cara01lab_antiguedad'];
?>
</label>
<label>
<?php
echo $html_cara01lab_antiguedad;
?>
</label>
</div>
<div class="salto1px"></div>
<div id="div_lab2" <?php echo $sMuestra2; ?>>
<label class="Label450">
<?php
echo $ETI['cara01lab_tipocontrato'];
?>
</label>
<label>
<?php
echo $html_cara01lab_tipocontrato;
?>
</label>
</div>
<div class="salto1px"></div>
<div id="div_lab3" <?php echo $sMuestra3; ?>>
<label class="Label450">
<?php
echo $ETI['cara01lab_rangoingreso'];
?>
</label>
<label>
<?php
echo $html_cara01lab_rangoingreso;
?>
</label>
</div>
<div class="salto1px"></div>
<label class="Label450">
<?php
echo $ETI['cara01lab_tiempoacadem'];
?>
</label>
<label>
<?php
echo $html_cara01lab_tiempoacadem;
?>
</label>
<div class="salto1px"></div>
<div id="div_lab4" <?php echo $sMuestra4; ?>>
<label class="Label450">
<?php
echo $ETI['cara01lab_tipoempresa'];
?>
</label>
<label>
<?php
echo $html_cara01lab_tipoempresa;
?>
</label>
<div class="salto1px"></div>
<label class="Label450">
<?php
echo $ETI['cara01lab_tiempoindepen'];
?>
</label>
<label>
<?php
echo $html_cara01lab_tiempoindepen;
?>
</label>
</div>
<div class="salto1px"></div>
<div id="div_lab5" <?php echo $sMuestra5; ?>>
<label class="Label450">
<?php
echo $ETI['cara01lab_debebusctrab'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara01lab_debebusctrab;
?>
</label>
</div>
<div class="salto1px"></div>
<div id="div_lab6" <?php echo $sMuestra6; ?>>
<label class="Label450">
<?php
if (!$bAntiguo) {
echo $ETI['cara01lab_origendinero'];
} else {
echo $ETI['cara44lab_origendineroantiguo'];
}
?>
</label>
<label>
<?php
echo $html_cara01lab_origendinero;
?>
</label>
</div>
<?php
if ($bEstudiante) {
if ($_REQUEST['paso'] == 2){
	if ($bPintarTablero){
		echo html_2201Tablero(4, $objForma);
	} else {
		echo html_2201ContinuarCerrar(4, $objForma);
	}
}
}
?>
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
</div>
<?php
}
if ($bGrupo5) {
	$sEstilo = '';
	if ($_REQUEST['cara01fichabien'] == -1) {
		$sEstilo = ' style="display:none"';
	}
	if ($bEstudiante) {
		$sEstilo = ' style="display:none"';
	if ($_REQUEST['ficha'] == 5) {
		$sEstilo = '';
		}
	}
?>
<div class="salto1px"></div>
<div class="GrupoCampos" id="div_ficha5" <?php echo $sEstilo; ?>>
<?php
if ($bConExpande) {
?>
<div class="ir_derecha"<?php echo $sAnchoExpandeContrae; ?>>
<?php
echo $objForma->htmlExpande(105, $_REQUEST['boculta105'], $ETI['bt_mostrar'], $ETI['bt_ocultar']);
$sEstiloDiv = '';
if ($_REQUEST['boculta105'] != 0) {
	$sEstiloDiv = ' style="display:none;"';
}
?>
</div>
<?php
}
?>
<label class="TituloGrupo">
<?php
echo $ETI['cara01fichabien'];
?>
</label>
<label class="Label130">
<div id="div_cara01fichabien">
<?php
$sMuestra = '&nbsp;';
if ($_REQUEST['cara01fichabien'] == 1) {
$sMuestra = 'COMPLETA';
}
echo html_oculto('cara01fichabien', $_REQUEST['cara01fichabien'], $sMuestra);
?>
</div>
</label>
<div class="salto1px"></div>
<div id="div_p105"<?php echo $sEstiloDiv; ?>>
<?php
$bEntra = false;
if ($_REQUEST['cara44bienversion'] == 0) {
$bEntra = true;
}
if ($bEntra) {
?>
<div class="GrupoCampos">
<label class="TituloGrupo" style="width:100%">
<?php
echo $ETI['cara01bien_activ'];
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['cara01bien_baloncesto'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bien_baloncesto;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara01bien_voleibol'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bien_voleibol;
?>
</label>
<label class="Label200">
<?php
echo $ETI['cara01bien_futbolsala'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bien_futbolsala;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara01bien_artesmarc'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bien_artesmarc;
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['cara01bien_tenisdemesa'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bien_tenisdemesa;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara01bien_ajedrez'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bien_ajedrez;
?>
</label>
<label class="Label200">
<?php
echo $ETI['cara01bien_juegosautoc'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bien_juegosautoc;
?>
</label>
<div class="salto1px"></div>
<label class="Label600">
<?php
echo $ETI['cara01bien_interesrepdeporte'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bien_interesrepdeporte;
$sMuestra = ' style="display:none"';
if ($_REQUEST['cara01bien_interesrepdeporte'] == 'S') {
$sMuestra = '';
}
?>
</label>
<label id="label_interesrepdeporte" class="L" <?php echo $sMuestra; ?>>
<?php
echo $ETI['cara01bien_deporteint'];
?>

<input id="cara01bien_deporteint" name="cara01bien_deporteint" type="text" value="<?php echo $_REQUEST['cara01bien_deporteint']; ?>" maxlength="200" class="L" placeholder="<?php echo $ETI['ing_campo'] . $ETI['cara01bien_deporteint']; ?>" />
</label>
<div class="salto1px"></div>
</div>

<div class="GrupoCampos">
<label class="TituloGrupo" style="width:100%">
<?php
echo '' . $ETI['cara01bien_recr'] . '';
?>
</label>
<div class="salto1px"></div>
<label class="Label90">
<?php
echo $ETI['cara01bien_teatro'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bien_teatro;
?>
</label>
<label class="Label90">
<?php
echo $ETI['cara01bien_danza'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bien_danza;
?>
</label>
<label class="Label90">
<?php
echo $ETI['cara01bien_musica'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bien_musica;
?>
</label>
<label class="Label90">
<?php
echo $ETI['cara01bien_circo'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bien_circo;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara01bien_artplast'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bien_artplast;
?>
</label>
<label class="Label90">
<?php
echo $ETI['cara01bien_cuenteria'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bien_cuenteria;
?>
</label>
<div class="salto1px"></div>
<label class="Label600">
<?php
echo $ETI['cara01bien_interesreparte'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bien_interesreparte;
$sMuestra = ' style="display:none"';
if ($_REQUEST['cara01bien_interesreparte'] == 'S') {
$sMuestra = '';
}
?>
</label>
<label id="label_interesreparte" class="L" <?php echo $sMuestra; ?>>
<?php
echo $ETI['cara01bien_arteint'];
?>

<input id="cara01bien_arteint" name="cara01bien_arteint" type="text" value="<?php echo $_REQUEST['cara01bien_arteint']; ?>" maxlength="200" class="L" placeholder="<?php echo $ETI['ing_campo'] . $ETI['cara01bien_arteint']; ?>" />
</label>
<div class="salto1px"></div>
<label class="Label450">
<?php
echo $ETI['cara01bien_interpreta'];
?>
</label>
<label>
<?php
echo $html_cara01bien_interpreta;
$sMuestra = ' style="display:none"';
$bEntra = false;
if ($_REQUEST['cara01bien_interpreta'] != '') {
if ($_REQUEST['cara01bien_interpreta'] != -1) {
$bEntra = true;
}
}
if ($bEntra) {
$sMuestra = '';
}
?>
</label>
<div class="salto1px"></div>
<div id="div_bien_nivelinter" <?php echo $sMuestra; ?>>
<label class="Label450">
<?php
echo $ETI['cara01bien_nivelinter'];
?>
</label>
<label>
<?php
echo $html_cara01bien_nivelinter;
?>
</label>
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
<?php
echo '' . $ETI['cara01bien_danzatipo'] . '';
?>
<div class="salto1px"></div>
<label>
<?php
echo $ETI['cara01bien_danza_mod'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bien_danza_mod;
?>
</label>
<label>
<?php
echo $ETI['cara01bien_danza_clas'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bien_danza_clas;
?>
</label>
<div class="salto1px"></div>
<label>
<?php
echo $ETI['cara01bien_danza_cont'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bien_danza_cont;
?>
</label>
<label>
<?php
echo $ETI['cara01bien_danza_folk'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bien_danza_folk;
$sMuestra = ' style="display:none"';
if ($_REQUEST['cara01bien_danza_mod'] == 'S') {
$sMuestra = '';
}
if ($_REQUEST['cara01bien_danza_clas'] == 'S') {
$sMuestra = '';
}
if ($_REQUEST['cara01bien_danza_cont'] == 'S') {
$sMuestra = '';
}
if ($_REQUEST['cara01bien_danza_folk'] == 'S') {
$sMuestra = '';
}
?>
</label>
<div class="salto1px"></div>
<div id="div_bien_niveldanza" <?php echo $sMuestra; ?>>
<label class="Label450">
<?php
echo $ETI['cara01bien_niveldanza'];
?>
</label>
<label>
<?php
echo $html_cara01bien_niveldanza;
?>
</label>
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
</div>

<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo $ETI['msg_emprendimiento'];
?>
</label>
<div class="salto1px"></div>
<label class="Label450">
<?php
echo $ETI['cara01bien_emprendedor'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bien_emprendedor;
$sMuestra = ' style="display:none"';
if ($_REQUEST['cara01bien_emprendedor'] == 'S') {
$sMuestra = '';
}
?>
</label>
<label class="L" id="label_nombreemp" <?php echo $sMuestra; ?>>
<?php
echo $ETI['cara01bien_nombreemp'];
?>

<input id="cara01bien_nombreemp" name="cara01bien_nombreemp" type="text" value="<?php echo $_REQUEST['cara01bien_nombreemp']; ?>" maxlength="200" class="L" placeholder="<?php echo $ETI['ing_campo'] . $ETI['cara01bien_nombreemp']; ?>" />
</label>
<div class="salto1px"></div>
<label class="Label450">
<?php
echo $ETI['cara01bien_capacempren'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bien_capacempren;
$sMuestra = ' style="display:none"';
if ($_REQUEST['cara01bien_capacempren'] == 'S') {
$sMuestra = '';
}
?>
</label>
<label class="L" id="lbl_bien_capacempren" <?php echo $sMuestra; ?>>
<?php
echo $ETI['cara01bien_tipocapacita'];
?>

<input id="cara01bien_tipocapacita" name="cara01bien_tipocapacita" type="text" value="<?php echo $_REQUEST['cara01bien_tipocapacita']; ?>" maxlength="200" class="L" placeholder="<?php echo $ETI['ing_campo'] . $ETI['cara01bien_tipocapacita']; ?>" />
</label>
<div class="salto1px"></div>
</div>

<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo $ETI['msg_estilodevida'];
?>
</label>
<div class="salto1px"></div>
<label class="Label450">
<?php
echo $ETI['cara01bien_impvidasalud'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bien_impvidasalud;
?>
</label>
<div class="salto1px"></div>
<label class="Label450">
<?php
echo $ETI['cara01bien_estraautocuid'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bien_estraautocuid;
?>
</label>
<div class="salto1px"></div>
</div>


<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo '' . $ETI['cara01bien_pv'] . '';
?>
</label>
<div class="salto1px"></div>
<label class="Label450">
<?php
echo $ETI['cara01bien_pv_personal'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bien_pv_personal;
?>
</label>
<div class="salto1px"></div>
</div>

<?php
if (false) {
?>
<div class="salto1px"></div>
<label class="Label90">
<?php
echo $ETI['cara01bien_pv_familiar'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bien_pv_familiar;
?>
</label>
<label class="Label90">
<?php
echo $ETI['cara01bien_pv_academ'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bien_pv_academ;
?>
</label>
<label class="Label90">
<?php
echo $ETI['cara01bien_pv_labora'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bien_pv_labora;
?>
</label>
<label class="Label90">
<?php
echo $ETI['cara01bien_pv_pareja'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara01bien_pv_pareja;
?>
</label>
<?php
} else {
?>
<input id="cara01bien_pv_familiar" name="cara01bien_pv_familiar" type="hidden" value="<?php echo $_REQUEST['cara01bien_pv_familiar']; ?>" />
<input id="cara01bien_pv_academ" name="cara01bien_pv_academ" type="hidden" value="<?php echo $_REQUEST['cara01bien_pv_academ']; ?>" />
<input id="cara01bien_pv_labora" name="cara01bien_pv_labora" type="hidden" value="<?php echo $_REQUEST['cara01bien_pv_labora']; ?>" />
<input id="cara01bien_pv_pareja" name="cara01bien_pv_pareja" type="hidden" value="<?php echo $_REQUEST['cara01bien_pv_pareja']; ?>" />
<?php
}
?>
<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo $ETI['msg_medioambiente'];
?>
</label>
<div class="salto1px"></div>
<label class="Label450">
<?php
echo $ETI['cara01bien_amb'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara01bien_amb;
?>
</label>
<div class="salto1px"></div>
<label class="TituloGrupo" style="width:100%">
<?php
echo $ETI['cara01bien_ambitem'];
?>
</label>
<div class="salto1px"></div>
<label class="Label450">
<?php
echo $ETI['cara01bien_amb_agu'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara01bien_amb_agu;
?>
</label>
<div class="salto1px"></div>
<label class="Label450">
<?php
echo $ETI['cara01bien_amb_bom'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara01bien_amb_bom;
?>
</label>
<div class="salto1px"></div>
<label class="Label450">
<?php
echo $ETI['cara01bien_amb_car'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara01bien_amb_car;
?>
</label>
<div class="salto1px"></div>
<label class="Label450">
<?php
echo $ETI['cara01bien_amb_info'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara01bien_amb_info;
$sMuestra = ' style="display:none"';
if ($_REQUEST['cara01bien_amb_info'] == 'S') {
//$sMuestra='';
}
?>
</label>
<label class="L" id="lbl_bien_amb_temas" <?php echo $sMuestra; ?>>
<?php
echo $ETI['cara01bien_amb_temas'];
?>

<input id="cara01bien_amb_temas" name="cara01bien_amb_temas" type="text" value="<?php echo $_REQUEST['cara01bien_amb_temas']; ?>" maxlength="200" class="L" placeholder="<?php echo $ETI['ing_campo'] . $ETI['cara01bien_amb_temas']; ?>" />
</label>

<div class="salto1px"></div>
</div>
<?php
} else {
?>
<input id="cara01bien_baloncesto" name="cara01bien_baloncesto" type="hidden" value="<?php echo $_REQUEST['cara01bien_baloncesto']; ?>" />
<input id="cara01bien_voleibol" name="cara01bien_voleibol" type="hidden" value="<?php echo $_REQUEST['cara01bien_voleibol']; ?>" />
<input id="cara01bien_futbolsala" name="cara01bien_futbolsala" type="hidden" value="<?php echo $_REQUEST['cara01bien_futbolsala']; ?>" />
<input id="cara01bien_artesmarc" name="cara01bien_artesmarc" type="hidden" value="<?php echo $_REQUEST['cara01bien_artesmarc']; ?>" />
<input id="cara01bien_tenisdemesa" name="cara01bien_tenisdemesa" type="hidden" value="<?php echo $_REQUEST['cara01bien_tenisdemesa']; ?>" />
<input id="cara01bien_ajedrez" name="cara01bien_ajedrez" type="hidden" value="<?php echo $_REQUEST['cara01bien_ajedrez']; ?>" />
<input id="cara01bien_juegosautoc" name="cara01bien_juegosautoc" type="hidden" value="<?php echo $_REQUEST['cara01bien_juegosautoc']; ?>" />
<input id="cara01bien_interesrepdeporte" name="cara01bien_interesrepdeporte" type="hidden" value="<?php echo $_REQUEST['cara01bien_interesrepdeporte']; ?>" />
<input id="cara01bien_deporteint" name="cara01bien_deporteint" type="hidden" value="<?php echo $_REQUEST['cara01bien_deporteint']; ?>" />
<input id="cara01bien_teatro" name="cara01bien_teatro" type="hidden" value="<?php echo $_REQUEST['cara01bien_teatro']; ?>" />
<input id="cara01bien_danza" name="cara01bien_danza" type="hidden" value="<?php echo $_REQUEST['cara01bien_danza']; ?>" />
<input id="cara01bien_musica" name="cara01bien_musica" type="hidden" value="<?php echo $_REQUEST['cara01bien_musica']; ?>" />
<input id="cara01bien_circo" name="cara01bien_circo" type="hidden" value="<?php echo $_REQUEST['cara01bien_circo']; ?>" />
<input id="cara01bien_artplast" name="cara01bien_artplast" type="hidden" value="<?php echo $_REQUEST['cara01bien_artplast']; ?>" />
<input id="cara01bien_cuenteria" name="cara01bien_cuenteria" type="hidden" value="<?php echo $_REQUEST['cara01bien_cuenteria']; ?>" />
<input id="cara01bien_interesreparte" name="cara01bien_interesreparte" type="hidden" value="<?php echo $_REQUEST['cara01bien_interesreparte']; ?>" />
<input id="cara01bien_arteint" name="cara01bien_arteint" type="hidden" value="<?php echo $_REQUEST['cara01bien_arteint']; ?>" />
<input id="cara01bien_interpreta" name="cara01bien_interpreta" type="hidden" value="<?php echo $_REQUEST['cara01bien_interpreta']; ?>" />
<input id="cara01bien_nivelinter" name="cara01bien_nivelinter" type="hidden" value="<?php echo $_REQUEST['cara01bien_nivelinter']; ?>" />
<input id="cara01bien_danza_mod" name="cara01bien_danza_mod" type="hidden" value="<?php echo $_REQUEST['cara01bien_danza_mod']; ?>" />
<input id="cara01bien_danza_clas" name="cara01bien_danza_clas" type="hidden" value="<?php echo $_REQUEST['cara01bien_danza_clas']; ?>" />
<input id="cara01bien_danza_cont" name="cara01bien_danza_cont" type="hidden" value="<?php echo $_REQUEST['cara01bien_danza_cont']; ?>" />
<input id="cara01bien_danza_folk" name="cara01bien_danza_folk" type="hidden" value="<?php echo $_REQUEST['cara01bien_danza_folk']; ?>" />
<input id="cara01bien_niveldanza" name="cara01bien_niveldanza" type="hidden" value="<?php echo $_REQUEST['cara01bien_niveldanza']; ?>" />
<input id="cara01bien_emprendedor" name="cara01bien_emprendedor" type="hidden" value="<?php echo $_REQUEST['cara01bien_emprendedor']; ?>" />
<input id="cara01bien_nombreemp" name="cara01bien_nombreemp" type="hidden" value="<?php echo $_REQUEST['cara01bien_nombreemp']; ?>" />
<input id="cara01bien_capacempren" name="cara01bien_capacempren" type="hidden" value="<?php echo $_REQUEST['cara01bien_capacempren']; ?>" />
<input id="cara01bien_tipocapacita" name="cara01bien_tipocapacita" type="hidden" value="<?php echo $_REQUEST['cara01bien_tipocapacita']; ?>" />
<input id="cara01bien_impvidasalud" name="cara01bien_impvidasalud" type="hidden" value="<?php echo $_REQUEST['cara01bien_impvidasalud']; ?>" />
<input id="cara01bien_estraautocuid" name="cara01bien_estraautocuid" type="hidden" value="<?php echo $_REQUEST['cara01bien_estraautocuid']; ?>" />
<input id="cara01bien_pv_personal" name="cara01bien_pv_personal" type="hidden" value="<?php echo $_REQUEST['cara01bien_pv_personal']; ?>" />
<input id="cara01bien_pv_familiar" name="cara01bien_pv_familiar" type="hidden" value="<?php echo $_REQUEST['cara01bien_pv_familiar']; ?>" />
<input id="cara01bien_pv_academ" name="cara01bien_pv_academ" type="hidden" value="<?php echo $_REQUEST['cara01bien_pv_academ']; ?>" />
<input id="cara01bien_pv_labora" name="cara01bien_pv_labora" type="hidden" value="<?php echo $_REQUEST['cara01bien_pv_labora']; ?>" />
<input id="cara01bien_pv_pareja" name="cara01bien_pv_pareja" type="hidden" value="<?php echo $_REQUEST['cara01bien_pv_pareja']; ?>" />
<input id="cara01bien_amb" name="cara01bien_amb" type="hidden" value="<?php echo $_REQUEST['cara01bien_amb']; ?>" />
<input id="cara01bien_amb_agu" name="cara01bien_amb_agu" type="hidden" value="<?php echo $_REQUEST['cara01bien_amb_agu']; ?>" />
<input id="cara01bien_amb_bom" name="cara01bien_amb_bom" type="hidden" value="<?php echo $_REQUEST['cara01bien_amb_bom']; ?>" />
<input id="cara01bien_amb_car" name="cara01bien_amb_car" type="hidden" value="<?php echo $_REQUEST['cara01bien_amb_car']; ?>" />
<input id="cara01bien_amb_info" name="cara01bien_amb_info" type="hidden" value="<?php echo $_REQUEST['cara01bien_amb_info']; ?>" />
<input id="cara01bien_amb_temas" name="cara01bien_amb_temas" type="hidden" value="<?php echo $_REQUEST['cara01bien_amb_temas']; ?>" />
<?php
}
?>
<?php
$bEntra = false;
if ($_REQUEST['cara44bienversion'] == 2) {
$bEntra = true;
}
if ($bEntra) {
?>
<div class="GrupoCampos">
<label class="TituloGrupo" style="width:100%">
<?php
echo $ETI['titulo_cara44biendeporte'];
?>
</label>
<div class="salto1px"></div>
<label class="Label600">
<?php
echo $ETI['cara44bienv2altoren'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv2altoren;
?>
</label>
<div class="salto1px"></div>
<label class="TituloGrupo" style="width:100%">
<?php
echo $ETI['cara44bienv2depitem'];
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['cara44bienv2atletismo'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv2atletismo;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara44bienv2baloncesto'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv2baloncesto;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara44bienv2futbol'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv2futbol;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara44bienv2gimnasia'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv2gimnasia;
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['cara44bienv2natacion'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv2natacion;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara44bienv2voleibol'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv2voleibol;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara44bienv2tenis'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv2tenis;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara44bienv2paralimpico'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv2paralimpico;
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['cara44bienv2otrodeporte'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv2otrodeporte;
$sMuestra = ' style="display:none"';
if ($_REQUEST['cara44bienv2otrodeporte'] == 'S') {
$sMuestra = '';
}
?>
</label>
<label id="label_cara44bienv2otrodeporte" class="L" <?php echo $sMuestra; ?>>
<?php
echo $ETI['cara44bienv2otrodeportedetalle'];
?>
<input id="cara44bienv2otrodeportedetalle" name="cara44bienv2otrodeportedetalle" type="text" value="<?php echo $_REQUEST['cara44bienv2otrodeportedetalle']; ?>" maxlength="200" class="L" placeholder="<?php echo $ETI['ing_campo'] . $ETI['cara44bienv2otrodeportedetalle']; ?>" />
</label>
<div class="salto1px"></div>
</div>
<?php
// Cierre grupo campos bienestar deportes
?>
<div class="GrupoCampos">
<label class="TituloGrupo" style="width:100%">
<?php
echo $ETI['titulo_cara44biencultura'];
?>
</label>
<div class="salto1px"></div>
<label class="TituloGrupo" style="width:100%">
<?php
echo $ETI['cara44bienv2culturaactivitem'];
?>
</label>
<div class="salto1px"></div>
<label class="Label600">
<?php
echo $ETI['cara44bienv2activartes'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv2activartes;
?>
</label>
<div class="salto1px"></div>
<label class="Label600">
<?php
echo $ETI['cara44bienv2activliteratura'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv2activliteratura;
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['cara44bienv2activdanza'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv2activdanza;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara44bienv2activmusica'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv2activmusica;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara44bienv2activteatro'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv2activteatro;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara44bienv2activculturalotra'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv2activculturalotra;
$sMuestra = ' style="display:none"';
if ($_REQUEST['cara44bienv2activculturalotra'] == 'S') {
$sMuestra = '';
}
?>
</label>
<label id="label_cara44bienv2activculturalotra" class="L" <?php echo $sMuestra; ?>>
<?php
echo $ETI['cara44bienv2activculturalotradetalle'];
?>
<input id="cara44bienv2activculturalotradetalle" name="cara44bienv2activculturalotradetalle" type="text" value="<?php echo $_REQUEST['cara44bienv2activculturalotradetalle']; ?>" maxlength="200" class="L" placeholder="<?php echo $ETI['ing_campo'] . $ETI['cara44bienv2activculturalotradetalle']; ?>" />
</label>
<div class="salto1px"></div>
<label class="TituloGrupo" style="width:100%">
<?php
echo $ETI['cara44bienv2culturaeventitem'];
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['cara44bienv2evenliteratura'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv2evenliteratura;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara44bienv2eventeatro'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv2eventeatro;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara44bienv2evencine'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv2evencine;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara44bienv2evenhistarte'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv2evenhistarte;
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['cara44bienv2evenfestfolc'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv2evenfestfolc;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara44bienv2evenexpoarte'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv2evenexpoarte;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara44bienv2evengalfoto'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv2evengalfoto;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara44bienv2evenculturalotro'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv2evenculturalotro;
$sMuestra = ' style="display:none"';
if ($_REQUEST['cara44bienv2evenculturalotro'] == 'S') {
$sMuestra = '';
}
?>
</label>
<label id="label_cara44bienv2evenculturalotro" class="L" <?php echo $sMuestra; ?>>
<?php
echo $ETI['cara44bienv2evenculturalotrodetalle'];
?>
<input id="cara44bienv2evenculturalotrodetalle" name="cara44bienv2evenculturalotrodetalle" type="text" value="<?php echo $_REQUEST['cara44bienv2evenculturalotrodetalle']; ?>" maxlength="200" class="L" placeholder="<?php echo $ETI['ing_campo'] . $ETI['cara44bienv2evenculturalotrodetalle']; ?>" />
</label>
<div class="salto1px"></div>
</div>
<?php
// Cierre grupo campos arte y cultura
?>
<div class="GrupoCampos">
<label class="TituloGrupo" style="width:100%">
<?php
echo $ETI['msg_emprendimiento'];
?>
</label>
<div class="salto1px"></div>
<label class="TituloGrupo" style="width:100%">
<?php
echo $ETI['cara44bienv2emprensituaitem'];
?>
</label>
<div class="salto1px"></div>
<label class="Label190">
<?php
echo $ETI['cara44bienv2emprendimiento'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv2emprendimiento;
?>
</label>
<div class="salto1px"></div>
<label class="Label190">
<?php
echo $ETI['cara44bienv2empresa'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv2empresa;
?>
</label>
<div class="salto1px"></div>
<label class="TituloGrupo" style="width:100%">
<?php
echo $ETI['cara44bienv2emprenestadoitem'];
?>
</label>
<div class="salto1px"></div>
<label class="Label600">
<?php
echo $ETI['cara44bienv2emprenrecursos'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv2emprenrecursos;
?>
</label>
<div class="salto1px"></div>
<label class="Label600">
<?php
echo $ETI['cara44bienv2emprenconocim'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv2emprenconocim;
?>
</label>
<div class="salto1px"></div>
<label class="Label600">
<?php
echo $ETI['cara44bienv2emprenplan'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv2emprenplan;
?>
</label>
<div class="salto1px"></div>
<label class="Label600">
<?php
echo $ETI['cara44bienv2emprenejecutar'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv2emprenejecutar;
?>
</label>
<div class="salto1px"></div>
<label class="Label600">
<?php
echo $ETI['cara44bienv2emprenfortconocim'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv2emprenfortconocim;
?>
</label>
<div class="salto1px"></div>
<label class="Label600">
<?php
echo $ETI['cara44bienv2emprenidentproblema'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv2emprenidentproblema;
?>
</label>
<div class="salto1px"></div>
<label class="Label90">
<?php
echo $ETI['cara44bienv2emprenotro'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv2emprenotro;
$sMuestra = ' style="display:none"';
if ($_REQUEST['cara44bienv2emprenotro'] == 'S') {
$sMuestra = '';
}
?>
</label>
<label id="label_cara44bienv2emprenotro" class="L" <?php echo $sMuestra; ?>>
<?php
echo $ETI['cara44bienv2emprenotrodetalle'];
?>
<input id="cara44bienv2emprenotrodetalle" name="cara44bienv2emprenotrodetalle" type="text" value="<?php echo $_REQUEST['cara44bienv2emprenotrodetalle']; ?>" maxlength="200" class="L" placeholder="<?php echo $ETI['ing_campo'] . $ETI['cara44bienv2emprenotrodetalle']; ?>" />
</label>
<div class="salto1px"></div>
<label class="TituloGrupo" style="width:100%">
<?php
echo $ETI['cara44bienv2emprentemasitem'];
?>
</label>
<div class="salto1px"></div>
<label class="Label190">
<?php
echo $ETI['cara44bienv2emprenmarketing'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv2emprenmarketing;
?>
</label>
<label class="Label190">
<?php
echo $ETI['cara44bienv2emprenplannegocios'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv2emprenplannegocios;
?>
</label>
<div class="salto1px"></div>
<label class="Label190">
<?php
echo $ETI['cara44bienv2emprenideas'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv2emprenideas;
?>
</label>
<label class="Label190">
<?php
echo $ETI['cara44bienv2emprencreacion'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv2emprencreacion;
?>
</label>
<div class="salto1px"></div>
</div>
<?php
// Cierre grupo campos bienestar emprendimiento
?>
<div class="GrupoCampos">
<label class="TituloGrupo" style="width:100%">
<?php
echo $ETI['msg_estilodevida'];
?>
</label>
<div class="salto1px"></div>
<label class="TituloGrupo" style="width:100%">
<?php
echo $ETI['cara44bienv2saludestresitem'];
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['cara44bienv2saludfacteconom'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv2saludfacteconom;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara44bienv2saludpreocupacion'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv2saludpreocupacion;
?>
</label>
<label class="Label190">
<?php
echo $ETI['cara44bienv2saludconsumosust'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv2saludconsumosust;
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['cara44bienv2saludinsomnio'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv2saludinsomnio;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara44bienv2saludclimalab'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv2saludclimalab;
?>
</label>
<div class="salto1px"></div>
<label class="TituloGrupo" style="width:100%">
<?php
echo $ETI['cara44bienv2saludautocuiditem'];
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['cara44bienv2saludalimenta'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv2saludalimenta;
?>
</label>
<label class="Label190">
<?php
echo $ETI['cara44bienv2saludemocion'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv2saludemocion;
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['cara44bienv2saludmedita'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv2saludmedita;
?>
</label>
<label class="Label190">
<?php
echo $ETI['cara44bienv2saludestado'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv2saludestado;
?>
</label>
<div class="salto1px"></div>
</div>
<?php
// Cierre grupo campos bienestar salud
?>
<div class="GrupoCampos">
<label class="TituloGrupo" style="width:100%">
<?php
echo $ETI['titulo_cara44biencrecim'];
?>
</label>
<div class="salto1px"></div>
<label class="TituloGrupo" style="width:100%">
<?php
echo $ETI['cara44bienv2crecimtemaitem'];
?>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['cara44bienv2crecimedusexual'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv2crecimedusexual;
?>
</label>
<label class="Label350">
<?php
echo $ETI['cara44bienv2creciminclusion'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv2creciminclusion;
?>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['cara44bienv2crecimcultciudad'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv2crecimcultciudad;
?>
</label>
<label class="Label350">
<?php
echo $ETI['cara44bienv2crecimrelinterp'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv2crecimrelinterp;
?>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['cara44bienv2crecimrelpareja'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv2crecimrelpareja;
?>
</label>
<label class="Label350">
<?php
echo $ETI['cara44bienv2creciminteliemoc'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv2creciminteliemoc;
?>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['cara44bienv2crecimautoestima'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv2crecimautoestima;
?>
</label>
<label class="Label350">
<?php
echo $ETI['cara44bienv2crecimdinamicafam'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv2crecimdinamicafam;
?>
</label>
<div class="salto1px"></div>
<label class="TituloGrupo" style="width:100%">
<?php
echo $ETI['cara44bienv2crecimgrupoitem'];
?>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['cara44bienv2crecimcultural'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv2crecimcultural;
?>
</label>
<label class="Label160">
<?php
echo $ETI['cara44bienv2crecimartistico'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv2crecimartistico;
?>
</label>
<label class="Label160">
<?php
echo $ETI['cara44bienv2crecimdeporte'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv2crecimdeporte;
?>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['cara44bienv2crecimambiente'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv2crecimambiente;
?>
</label>
<label class="Label350">
<?php
echo $ETI['cara44bienv2crecimhabsocio'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv2crecimhabsocio;
?>
</label>
<div class="salto1px"></div>
</div>
<?php
// Cierre grupo campos bienestar crecimiento personal
?>
<div class="GrupoCampos">
<label class="TituloGrupo" style="width:100%">
<?php
echo $ETI['msg_medioambiente'];
?>
</label>
<div class="salto1px"></div>
<label class="TituloGrupo" style="width:100%">
<?php
echo $ETI['cara44bienv2ambienaccionitem'];
?>
</label>
<div class="salto1px"></div>
<label class="Label190">
<?php
echo $ETI['cara44bienv2ambienbasura'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv2ambienbasura;
?>
</label>
<label class="Label190">
<?php
echo $ETI['cara44bienv2ambienreutiliza'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv2ambienreutiliza;
?>
</label>
<div class="salto1px"></div>
<label class="Label190">
<?php
echo $ETI['cara44bienv2ambienluces'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv2ambienluces;
?>
</label>
<label class="Label190">
<?php
echo $ETI['cara44bienv2ambienenchufa'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv2ambienenchufa;
?>
</label>
<div class="salto1px"></div>
<label class="Label190">
<?php
echo $ETI['cara44bienv2ambienducha'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv2ambienducha;
?>
</label>
<label class="Label190">
<?php
echo $ETI['cara44bienv2ambiengrifo'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv2ambiengrifo;
?>
</label>
<div class="salto1px"></div>
<label class="Label190">
<?php
echo $ETI['cara44bienv2ambienbicicleta'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv2ambienbicicleta;
?>
</label>
<label class="Label190">
<?php
echo $ETI['cara44bienv2ambientranspub'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv2ambientranspub;
?>
</label>
<div class="salto1px"></div>
<label class="Label190">
<?php
echo $ETI['cara44bienv2ambienfrutaverd'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv2ambienfrutaverd;
?>
</label>
<div class="salto1px"></div>
<label class="TituloGrupo" style="width:100%">
<?php
echo $ETI['cara44bienv2ambienactivitem'];
?>
</label>
<div class="salto1px"></div>
<label class="Label190">
<?php
echo $ETI['cara44bienv2ambiencaminata'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv2ambiencaminata;
?>
</label>
<label class="Label190">
<?php
echo $ETI['cara44bienv2ambiensiembra'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv2ambiensiembra;
?>
</label>
<div class="salto1px"></div>
<label class="Label190">
<?php
echo $ETI['cara44bienv2ambienrecicla'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv2ambienrecicla;
?>
</label>
<label class="Label190">
<?php
echo $ETI['cara44bienv2ambienconferencia'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv2ambienconferencia;
?>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['cara44bienv2ambienotraactiv'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv2ambienotraactiv;
$sMuestra = ' style="display:none"';
if ($_REQUEST['cara44bienv2ambienotraactiv'] == 'S') {
$sMuestra = '';
}
?>
</label>
<label id="label_cara44bienv2ambienotraactiv" class="L" <?php echo $sMuestra; ?>>
<?php
echo $ETI['cara44bienv2ambienotraactivdetalle'];
?>
<input id="cara44bienv2ambienotraactivdetalle" name="cara44bienv2ambienotraactivdetalle" type="text" value="<?php echo $_REQUEST['cara44bienv2ambienotraactivdetalle']; ?>" maxlength="200" class="L" placeholder="<?php echo $ETI['ing_campo'] . $ETI['cara44bienv2ambienotraactivdetalle']; ?>" />
</label>
<div class="salto1px"></div>
<label class="TituloGrupo" style="width:100%">
<?php
echo $ETI['cara44bienv2ambienenfoqueitem'];
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['cara44bienv2ambienreforest'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv2ambienreforest;
?>
</label>
<label class="Label160">
<?php
echo $ETI['cara44bienv2ambienclimatico'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv2ambienclimatico;
?>
</label>
<label class="Label190">
<?php
echo $ETI['cara44bienv2ambienmovilidad'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv2ambienmovilidad;
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['cara44bienv2ambienecofemin'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv2ambienecofemin;
?>
</label>
<label class="Label160">
<?php
echo $ETI['cara44bienv2ambieneconomia'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv2ambieneconomia;
?>
</label>
<label class="Label190">
<?php
echo $ETI['cara44bienv2ambienmascota'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv2ambienmascota;
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['cara44bienv2ambienbiodiver'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv2ambienbiodiver;
?>
</label>
<label class="Label160">
<?php
echo $ETI['cara44bienv2ambienecologia'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv2ambienecologia;
?>
</label>
<label class="Label190">
<?php
echo $ETI['cara44bienv2ambiencarga'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv2ambiencarga;
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['cara44bienv2ambienreciclaje'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv2ambienreciclaje;
?>
</label>
<label class="Label160">
<?php
echo $ETI['cara44bienv2ambienrecnatura'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv2ambienrecnatura;
?>
</label>
<label class="Label190">
<?php
echo $ETI['cara44bienv2ambienespiritu'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv2ambienespiritu;
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['cara44bienv2ambiencartohum'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv2ambiencartohum;
?>
</label>
<label class="Label160">
<?php
echo $ETI['cara44bienv2ambienotroenfoq'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv2ambienotroenfoq;
$sMuestra = ' style="display:none"';
if ($_REQUEST['cara44bienv2ambienotroenfoq'] == 'S') {
$sMuestra = '';
}
?>
</label>
<label id="label_cara44bienv2ambienotroenfoq" class="L" <?php echo $sMuestra; ?>>
<?php
echo $ETI['cara44bienv2ambienotroenfoqdetalle'];
?>
<input id="cara44bienv2ambienotroenfoqdetalle" name="cara44bienv2ambienotroenfoqdetalle" type="text" value="<?php echo $_REQUEST['cara44bienv2ambienotroenfoqdetalle']; ?>" maxlength="200" class="L" placeholder="<?php echo $ETI['ing_campo'] . $ETI['cara44bienv2ambienotroenfoqdetalle']; ?>" />
</label>
<div class="salto1px"></div>
</div>
<?php
// Cierre grupo campos bienestar medio ambiente
?>
<?php
} else {
?>
<input id="cara44bienv2altoren" name="cara44bienv2altoren" type="hidden" value="<?php echo $_REQUEST['cara44bienv2altoren']; ?>" />
<input id="cara44bienv2atletismo" name="cara44bienv2atletismo" type="hidden" value="<?php echo $_REQUEST['cara44bienv2atletismo']; ?>" />
<input id="cara44bienv2baloncesto" name="cara44bienv2baloncesto" type="hidden" value="<?php echo $_REQUEST['cara44bienv2baloncesto']; ?>" />
<input id="cara44bienv2futbol" name="cara44bienv2futbol" type="hidden" value="<?php echo $_REQUEST['cara44bienv2futbol']; ?>" />
<input id="cara44bienv2gimnasia" name="cara44bienv2gimnasia" type="hidden" value="<?php echo $_REQUEST['cara44bienv2gimnasia']; ?>" />
<input id="cara44bienv2natacion" name="cara44bienv2natacion" type="hidden" value="<?php echo $_REQUEST['cara44bienv2natacion']; ?>" />
<input id="cara44bienv2voleibol" name="cara44bienv2voleibol" type="hidden" value="<?php echo $_REQUEST['cara44bienv2voleibol']; ?>" />
<input id="cara44bienv2tenis" name="cara44bienv2tenis" type="hidden" value="<?php echo $_REQUEST['cara44bienv2tenis']; ?>" />
<input id="cara44bienv2paralimpico" name="cara44bienv2paralimpico" type="hidden" value="<?php echo $_REQUEST['cara44bienv2paralimpico']; ?>" />
<input id="cara44bienv2otrodeporte" name="cara44bienv2otrodeporte" type="hidden" value="<?php echo $_REQUEST['cara44bienv2otrodeporte']; ?>" />
<input id="cara44bienv2otrodeportedetalle" name="cara44bienv2otrodeportedetalle" type="hidden" value="<?php echo $_REQUEST['cara44bienv2otrodeportedetalle']; ?>" />
<input id="cara44bienv2activdanza" name="cara44bienv2activdanza" type="hidden" value="<?php echo $_REQUEST['cara44bienv2activdanza']; ?>" />
<input id="cara44bienv2activmusica" name="cara44bienv2activmusica" type="hidden" value="<?php echo $_REQUEST['cara44bienv2activmusica']; ?>" />
<input id="cara44bienv2activteatro" name="cara44bienv2activteatro" type="hidden" value="<?php echo $_REQUEST['cara44bienv2activteatro']; ?>" />
<input id="cara44bienv2activartes" name="cara44bienv2activartes" type="hidden" value="<?php echo $_REQUEST['cara44bienv2activartes']; ?>" />
<input id="cara44bienv2activliteratura" name="cara44bienv2activliteratura" type="hidden" value="<?php echo $_REQUEST['cara44bienv2activliteratura']; ?>" />
<input id="cara44bienv2activculturalotra" name="cara44bienv2activculturalotra" type="hidden" value="<?php echo $_REQUEST['cara44bienv2activculturalotra']; ?>" />
<input id="cara44bienv2activculturalotradetalle" name="cara44bienv2activculturalotradetalle" type="hidden" value="<?php echo $_REQUEST['cara44bienv2activculturalotradetalle']; ?>" />
<input id="cara44bienv2evenfestfolc" name="cara44bienv2evenfestfolc" type="hidden" value="<?php echo $_REQUEST['cara44bienv2evenfestfolc']; ?>" />
<input id="cara44bienv2evenexpoarte" name="cara44bienv2evenexpoarte" type="hidden" value="<?php echo $_REQUEST['cara44bienv2evenexpoarte']; ?>" />
<input id="cara44bienv2evenhistarte" name="cara44bienv2evenhistarte" type="hidden" value="<?php echo $_REQUEST['cara44bienv2evenhistarte']; ?>" />
<input id="cara44bienv2evengalfoto" name="cara44bienv2evengalfoto" type="hidden" value="<?php echo $_REQUEST['cara44bienv2evengalfoto']; ?>" />
<input id="cara44bienv2evenliteratura" name="cara44bienv2evenliteratura" type="hidden" value="<?php echo $_REQUEST['cara44bienv2evenliteratura']; ?>" />
<input id="cara44bienv2eventeatro" name="cara44bienv2eventeatro" type="hidden" value="<?php echo $_REQUEST['cara44bienv2eventeatro']; ?>" />
<input id="cara44bienv2evencine" name="cara44bienv2evencine" type="hidden" value="<?php echo $_REQUEST['cara44bienv2evencine']; ?>" />
<input id="cara44bienv2evenculturalotro" name="cara44bienv2evenculturalotro" type="hidden" value="<?php echo $_REQUEST['cara44bienv2evenculturalotro']; ?>" />
<input id="cara44bienv2evenculturalotrodetalle" name="cara44bienv2evenculturalotrodetalle" type="hidden" value="<?php echo $_REQUEST['cara44bienv2evenculturalotrodetalle']; ?>" />
<input id="cara44bienv2emprendimiento" name="cara44bienv2emprendimiento" type="hidden" value="<?php echo $_REQUEST['cara44bienv2emprendimiento']; ?>" />
<input id="cara44bienv2empresa" name="cara44bienv2empresa" type="hidden" value="<?php echo $_REQUEST['cara44bienv2empresa']; ?>" />
<input id="cara44bienv2emprenrecursos" name="cara44bienv2emprenrecursos" type="hidden" value="<?php echo $_REQUEST['cara44bienv2emprenrecursos']; ?>" />
<input id="cara44bienv2emprenconocim" name="cara44bienv2emprenconocim" type="hidden" value="<?php echo $_REQUEST['cara44bienv2emprenconocim']; ?>" />
<input id="cara44bienv2emprenplan" name="cara44bienv2emprenplan" type="hidden" value="<?php echo $_REQUEST['cara44bienv2emprenplan']; ?>" />
<input id="cara44bienv2emprenejecutar" name="cara44bienv2emprenejecutar" type="hidden" value="<?php echo $_REQUEST['cara44bienv2emprenejecutar']; ?>" />
<input id="cara44bienv2emprenfortconocim" name="cara44bienv2emprenfortconocim" type="hidden" value="<?php echo $_REQUEST['cara44bienv2emprenfortconocim']; ?>" />
<input id="cara44bienv2emprenidentproblema" name="cara44bienv2emprenidentproblema" type="hidden" value="<?php echo $_REQUEST['cara44bienv2emprenidentproblema']; ?>" />
<input id="cara44bienv2emprenotro" name="cara44bienv2emprenotro" type="hidden" value="<?php echo $_REQUEST['cara44bienv2emprenotro']; ?>" />
<input id="cara44bienv2emprenotrodetalle" name="cara44bienv2emprenotrodetalle" type="hidden" value="<?php echo $_REQUEST['cara44bienv2emprenotrodetalle']; ?>" />
<input id="cara44bienv2emprenmarketing" name="cara44bienv2emprenmarketing" type="hidden" value="<?php echo $_REQUEST['cara44bienv2emprenmarketing']; ?>" />
<input id="cara44bienv2emprenplannegocios" name="cara44bienv2emprenplannegocios" type="hidden" value="<?php echo $_REQUEST['cara44bienv2emprenplannegocios']; ?>" />
<input id="cara44bienv2emprenideas" name="cara44bienv2emprenideas" type="hidden" value="<?php echo $_REQUEST['cara44bienv2emprenideas']; ?>" />
<input id="cara44bienv2emprencreacion" name="cara44bienv2emprencreacion" type="hidden" value="<?php echo $_REQUEST['cara44bienv2emprencreacion']; ?>" />
<input id="cara44bienv2saludfacteconom" name="cara44bienv2saludfacteconom" type="hidden" value="<?php echo $_REQUEST['cara44bienv2saludfacteconom']; ?>" />
<input id="cara44bienv2saludpreocupacion" name="cara44bienv2saludpreocupacion" type="hidden" value="<?php echo $_REQUEST['cara44bienv2saludpreocupacion']; ?>" />
<input id="cara44bienv2saludconsumosust" name="cara44bienv2saludconsumosust" type="hidden" value="<?php echo $_REQUEST['cara44bienv2saludconsumosust']; ?>" />
<input id="cara44bienv2saludinsomnio" name="cara44bienv2saludinsomnio" type="hidden" value="<?php echo $_REQUEST['cara44bienv2saludinsomnio']; ?>" />
<input id="cara44bienv2saludclimalab" name="cara44bienv2saludclimalab" type="hidden" value="<?php echo $_REQUEST['cara44bienv2saludclimalab']; ?>" />
<input id="cara44bienv2saludalimenta" name="cara44bienv2saludalimenta" type="hidden" value="<?php echo $_REQUEST['cara44bienv2saludalimenta']; ?>" />
<input id="cara44bienv2saludemocion" name="cara44bienv2saludemocion" type="hidden" value="<?php echo $_REQUEST['cara44bienv2saludemocion']; ?>" />
<input id="cara44bienv2saludestado" name="cara44bienv2saludestado" type="hidden" value="<?php echo $_REQUEST['cara44bienv2saludestado']; ?>" />
<input id="cara44bienv2saludmedita" name="cara44bienv2saludmedita" type="hidden" value="<?php echo $_REQUEST['cara44bienv2saludmedita']; ?>" />
<input id="cara44bienv2crecimedusexual" name="cara44bienv2crecimedusexual" type="hidden" value="<?php echo $_REQUEST['cara44bienv2crecimedusexual']; ?>" />
<input id="cara44bienv2crecimcultciudad" name="cara44bienv2crecimcultciudad" type="hidden" value="<?php echo $_REQUEST['cara44bienv2crecimcultciudad']; ?>" />
<input id="cara44bienv2crecimrelpareja" name="cara44bienv2crecimrelpareja" type="hidden" value="<?php echo $_REQUEST['cara44bienv2crecimrelpareja']; ?>" />
<input id="cara44bienv2crecimrelinterp" name="cara44bienv2crecimrelinterp" type="hidden" value="<?php echo $_REQUEST['cara44bienv2crecimrelinterp']; ?>" />
<input id="cara44bienv2crecimdinamicafam" name="cara44bienv2crecimdinamicafam" type="hidden" value="<?php echo $_REQUEST['cara44bienv2crecimdinamicafam']; ?>" />
<input id="cara44bienv2crecimautoestima" name="cara44bienv2crecimautoestima" type="hidden" value="<?php echo $_REQUEST['cara44bienv2crecimautoestima']; ?>" />
<input id="cara44bienv2creciminclusion" name="cara44bienv2creciminclusion" type="hidden" value="<?php echo $_REQUEST['cara44bienv2creciminclusion']; ?>" />
<input id="cara44bienv2creciminteliemoc" name="cara44bienv2creciminteliemoc" type="hidden" value="<?php echo $_REQUEST['cara44bienv2creciminteliemoc']; ?>" />
<input id="cara44bienv2crecimcultural" name="cara44bienv2crecimcultural" type="hidden" value="<?php echo $_REQUEST['cara44bienv2crecimcultural']; ?>" />
<input id="cara44bienv2crecimartistico" name="cara44bienv2crecimartistico" type="hidden" value="<?php echo $_REQUEST['cara44bienv2crecimartistico']; ?>" />
<input id="cara44bienv2crecimdeporte" name="cara44bienv2crecimdeporte" type="hidden" value="<?php echo $_REQUEST['cara44bienv2crecimdeporte']; ?>" />
<input id="cara44bienv2crecimambiente" name="cara44bienv2crecimambiente" type="hidden" value="<?php echo $_REQUEST['cara44bienv2crecimambiente']; ?>" />
<input id="cara44bienv2crecimhabsocio" name="cara44bienv2crecimhabsocio" type="hidden" value="<?php echo $_REQUEST['cara44bienv2crecimhabsocio']; ?>" />
<input id="cara44bienv2ambienbasura" name="cara44bienv2ambienbasura" type="hidden" value="<?php echo $_REQUEST['cara44bienv2ambienbasura']; ?>" />
<input id="cara44bienv2ambienreutiliza" name="cara44bienv2ambienreutiliza" type="hidden" value="<?php echo $_REQUEST['cara44bienv2ambienreutiliza']; ?>" />
<input id="cara44bienv2ambienluces" name="cara44bienv2ambienluces" type="hidden" value="<?php echo $_REQUEST['cara44bienv2ambienluces']; ?>" />
<input id="cara44bienv2ambienfrutaverd" name="cara44bienv2ambienfrutaverd" type="hidden" value="<?php echo $_REQUEST['cara44bienv2ambienfrutaverd']; ?>" />
<input id="cara44bienv2ambienenchufa" name="cara44bienv2ambienenchufa" type="hidden" value="<?php echo $_REQUEST['cara44bienv2ambienenchufa']; ?>" />
<input id="cara44bienv2ambiengrifo" name="cara44bienv2ambiengrifo" type="hidden" value="<?php echo $_REQUEST['cara44bienv2ambiengrifo']; ?>" />
<input id="cara44bienv2ambienbicicleta" name="cara44bienv2ambienbicicleta" type="hidden" value="<?php echo $_REQUEST['cara44bienv2ambienbicicleta']; ?>" />
<input id="cara44bienv2ambientranspub" name="cara44bienv2ambientranspub" type="hidden" value="<?php echo $_REQUEST['cara44bienv2ambientranspub']; ?>" />
<input id="cara44bienv2ambienducha" name="cara44bienv2ambienducha" type="hidden" value="<?php echo $_REQUEST['cara44bienv2ambienducha']; ?>" />
<input id="cara44bienv2ambiencaminata" name="cara44bienv2ambiencaminata" type="hidden" value="<?php echo $_REQUEST['cara44bienv2ambiencaminata']; ?>" />
<input id="cara44bienv2ambiensiembra" name="cara44bienv2ambiensiembra" type="hidden" value="<?php echo $_REQUEST['cara44bienv2ambiensiembra']; ?>" />
<input id="cara44bienv2ambienconferencia" name="cara44bienv2ambienconferencia" type="hidden" value="<?php echo $_REQUEST['cara44bienv2ambienconferencia']; ?>" />
<input id="cara44bienv2ambienrecicla" name="cara44bienv2ambienrecicla" type="hidden" value="<?php echo $_REQUEST['cara44bienv2ambienrecicla']; ?>" />
<input id="cara44bienv2ambienotraactiv" name="cara44bienv2ambienotraactiv" type="hidden" value="<?php echo $_REQUEST['cara44bienv2ambienotraactiv']; ?>" />
<input id="cara44bienv2ambienotraactivdetalle" name="cara44bienv2ambienotraactivdetalle" type="hidden" value="<?php echo $_REQUEST['cara44bienv2ambienotraactivdetalle']; ?>" />
<input id="cara44bienv2ambienreforest" name="cara44bienv2ambienreforest" type="hidden" value="<?php echo $_REQUEST['cara44bienv2ambienreforest']; ?>" />
<input id="cara44bienv2ambienmovilidad" name="cara44bienv2ambienmovilidad" type="hidden" value="<?php echo $_REQUEST['cara44bienv2ambienmovilidad']; ?>" />
<input id="cara44bienv2ambienclimatico" name="cara44bienv2ambienclimatico" type="hidden" value="<?php echo $_REQUEST['cara44bienv2ambienclimatico']; ?>" />
<input id="cara44bienv2ambienecofemin" name="cara44bienv2ambienecofemin" type="hidden" value="<?php echo $_REQUEST['cara44bienv2ambienecofemin']; ?>" />
<input id="cara44bienv2ambienbiodiver" name="cara44bienv2ambienbiodiver" type="hidden" value="<?php echo $_REQUEST['cara44bienv2ambienbiodiver']; ?>" />
<input id="cara44bienv2ambienecologia" name="cara44bienv2ambienecologia" type="hidden" value="<?php echo $_REQUEST['cara44bienv2ambienecologia']; ?>" />
<input id="cara44bienv2ambieneconomia" name="cara44bienv2ambieneconomia" type="hidden" value="<?php echo $_REQUEST['cara44bienv2ambieneconomia']; ?>" />
<input id="cara44bienv2ambienrecnatura" name="cara44bienv2ambienrecnatura" type="hidden" value="<?php echo $_REQUEST['cara44bienv2ambienrecnatura']; ?>" />
<input id="cara44bienv2ambienreciclaje" name="cara44bienv2ambienreciclaje" type="hidden" value="<?php echo $_REQUEST['cara44bienv2ambienreciclaje']; ?>" />
<input id="cara44bienv2ambienmascota" name="cara44bienv2ambienmascota" type="hidden" value="<?php echo $_REQUEST['cara44bienv2ambienmascota']; ?>" />
<input id="cara44bienv2ambiencartohum" name="cara44bienv2ambiencartohum" type="hidden" value="<?php echo $_REQUEST['cara44bienv2ambiencartohum']; ?>" />
<input id="cara44bienv2ambienespiritu" name="cara44bienv2ambienespiritu" type="hidden" value="<?php echo $_REQUEST['cara44bienv2ambienespiritu']; ?>" />
<input id="cara44bienv2ambiencarga" name="cara44bienv2ambiencarga" type="hidden" value="<?php echo $_REQUEST['cara44bienv2ambiencarga']; ?>" />
<input id="cara44bienv2ambienotroenfoq" name="cara44bienv2ambienotroenfoq" type="hidden" value="<?php echo $_REQUEST['cara44bienv2ambienotroenfoq']; ?>" />
<input id="cara44bienv2ambienotroenfoqdetalle" name="cara44bienv2ambienotroenfoqdetalle" type="hidden" value="<?php echo $_REQUEST['cara44bienv2ambienotroenfoqdetalle']; ?>" />
<?php
}
?>
<?php
$bEntra = false;
if ($_REQUEST['cara44bienversion'] == 3) {
$bEntra = true;
}
if ($bEntra) {
?>
<div class="GrupoCampos">
<label class="TituloGrupo" style="width:100%">
<?php
echo $ETI['titulo_cara44bienv3emprendimiento'];
?>
</label>
<div class="salto1px"></div>
<div class="GrupoCamposAyuda">
<?php
echo $ETI['cara44bienv3intraemprendedor'];
?>
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
<label class="Label450">
<?php
echo $ETI['cara44bienv3emprenetapa'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv3emprenetapa;
?>
</label>
<div class="salto1px"></div>
<label class="Label450">
<?php
echo $ETI['cara44bienv3emprennecesita'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv3emprennecesita;
?>
</label>
<div class="salto1px"></div>
<label class="Label450">
<?php
echo $ETI['cara44bienv3emprenanioini'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv3emprenanioini;
?>
</label>
<div class="salto1px"></div>
<label class="Label450">
<?php
echo $ETI['cara44bienv3emprensector'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv3emprensector;
$sMuestra = ' style="display:none"';
if ($_REQUEST['cara44bienv3emprensector'] == 9) {
$sMuestra = '';
}
?>
</label>
<label id="label_cara44bienv3emprensector" class="L" <?php echo $sMuestra; ?>>
<?php
echo $ETI['cara44bienv3emprensectorotro'];
?>
<input id="cara44bienv3emprensectorotro" name="cara44bienv3emprensectorotro" type="text" value="<?php echo $_REQUEST['cara44bienv3emprensectorotro']; ?>" maxlength="200" class="L" placeholder="<?php echo $ETI['ing_campo'] . $ETI['cara44bienv3emprensectorotro']; ?>" />
</label>
<div class="salto1px"></div>
<label class="Label450">
<?php
echo $ETI['cara44bienv3emprentemas'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv3emprentemas;
?>
</label>
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo" style="width:100%">
<?php
echo $ETI['msg_medioambiente'];
?>
</label>
<div class="salto1px"></div>
<label class="Label600">
<?php
echo $ETI['cara44bienv3ambientema'];
?>
</label>
<div class="salto1px"></div>
<label class="Label190">
<?php
echo $ETI['cara44bienv3ambienclima'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv3ambienclima;
?>
</label>
<label class="Label190">
<?php
echo $ETI['cara44bienv3ambienjusticia'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv3ambienjusticia;
?>
</label>
<label class="Label190">
<?php
echo $ETI['cara44bienv3ambienagroeco'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv3ambienagroeco;
?>
</label>
<div class="salto1px"></div>
<label class="Label190">
<?php
echo $ETI['cara44bienv3ambieneconomia'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv3ambieneconomia;
?>
</label>
<label class="Label190">
<?php
echo $ETI['cara44bienv3ambieneducacion'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv3ambieneducacion;
?>
</label>
<label class="Label190">
<?php
echo $ETI['cara44bienv3ambienbiodiverso'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv3ambienbiodiverso;
?>
</label>
<div class="salto1px"></div>
<label class="Label190">
<?php
echo $ETI['cara44bienv3ambienecoturismo'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv3ambienecoturismo;
?>
</label>
<label class="Label190">
<?php
echo $ETI['cara44bienv3ambienotro'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv3ambienotro;
$sMuestra = ' style="display:none"';
if ($_REQUEST['cara44bienv3ambienotro'] == 1) {
$sMuestra = '';
}
?>
</label>
<label id="label_cara44bienv3ambienotro" class="L" <?php echo $sMuestra; ?>>
<?php
echo $ETI['cara44bienv3ambienotrodetalle'];
?>
<input id="cara44bienv3ambienotrodetalle" name="cara44bienv3ambienotrodetalle" type="text" value="<?php echo $_REQUEST['cara44bienv3ambienotrodetalle']; ?>" maxlength="200" class="L" placeholder="<?php echo $ETI['ing_campo'] . $ETI['cara44bienv3ambienotrodetalle']; ?>" />
</label>
<div class="salto1px"></div>
<label class="Label450">
<?php
echo $ETI['cara44bienv3ambienexper'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv3ambienexper;
?>
</label>
<div class="salto1px"></div>
<label class="Label450">
<?php
echo $ETI['cara44bienv3ambienaprende'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv3ambienaprende;
?>
</label>
<div class="salto1px"></div>
<label class="Label450">
<?php
echo $ETI['cara44bienv3ambienestudiante'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv3ambienestudiante;
?>
</label>
<div class="salto1px"></div>
<label class="Label450">
<?php
echo $ETI['cara44bienv3ambienactividad'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv3ambienactividad;
?>
</label>
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo" style="width:100%">
<?php
echo $ETI['titulo_cara44bienv3pyp'];
?>
</label>
<div class="salto1px"></div>
<label class="Label450">
<?php
echo $ETI['cara44bienv3pyphabitoalim'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv3pyphabitoalim;
?>
</label>
<div class="salto1px"></div>
<label class="Label450">
<?php
echo $ETI['cara44bienv3pypsustanciapsico'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv3pypsustanciapsico;
?>
</label>
<div class="salto1px"></div>
<label class="Label450">
<?php
echo $ETI['cara44bienv3pypsaludvisual'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv3pypsaludvisual;
?>
</label>
<div class="salto1px"></div>
<label class="Label450">
<?php
echo $ETI['cara44bienv3pypsaludbucal'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv3pypsaludbucal;
?>
</label>
<div class="salto1px"></div>
<label class="Label450">
<?php
echo $ETI['cara44bienv3pypsaludsexual'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv3pypsaludsexual;
?>
</label>
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo" style="width:100%">
<?php
echo $ETI['titulo_cara44biendeporte'];
?>
</label>
<div class="salto1px"></div>
<label class="Label450">
<?php
echo $ETI['cara44bienv3deportenivel'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv3deportenivel;
$sMuestra = ' style="display:none"';
if ($_REQUEST['cara44bienv3deportenivel'] != 4) {
$sMuestra = '';
}
?>
</label>
<label id="label_cara44bienv3deportenivel" class="L" <?php echo $sMuestra; ?>>
<label class="Label450">
<?php
echo $ETI['cara44bienv3deportefrec'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv3deportefrec;
?>
</label>
<div class="salto1px"></div>
<label class="Label450">
<?php
echo $ETI['cara44bienv3deportecual'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv3deportecual;
$sMuestra = ' style="display:none"';
if ($_REQUEST['cara44bienv3deportecual'] == 13) {
$sMuestra = '';
}
?>
</label>
<label id="label_cara44bienv3deportecual" class="L" <?php echo $sMuestra; ?>>
<?php
echo $ETI['cara44bienv3deportecualotro'];
?>
<input id="cara44bienv3deportecualotro" name="cara44bienv3deportecualotro" type="text" value="<?php echo $_REQUEST['cara44bienv3deportecualotro']; ?>" maxlength="200" class="L" placeholder="<?php echo $ETI['ing_campo'] . $ETI['cara44bienv3deportecualotro']; ?>" />
</label>
<div class="salto1px"></div>
</label>
<div class="salto1px"></div>
<label class="Label450">
<?php
echo $ETI['cara44bienv3deporterecrea'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv3deporterecrea;
?>
</label>
<div class="salto1px"></div>
<label class="Label450">
<?php
echo $ETI['cara44bienv3deporteunad'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv3deporteunad;
?>
</label>
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo" style="width:100%">
<?php
echo $ETI['titulo_cara44biencrecim'];
?>
</label>
<div class="salto1px"></div>
<label class="Label700">
<?php
echo $ETI['cara44bienv3crecimtemas'];
?>
</label>
<div class="salto1px"></div>
<label class="Label190">
<?php
echo $ETI['cara44bienv3creciminclusion'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv3creciminclusion;
?>
</label>
<label class="Label190">
<?php
echo $ETI['cara44bienv3crecimfamilia'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv3crecimfamilia;
?>
</label>
<label class="Label190">
<?php
echo $ETI['cara44bienv3crecimhabilidad'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv3crecimhabilidad;
?>
</label>
<div class="salto1px"></div>
<label class="Label190">
<?php
echo $ETI['cara44bienv3crecimempleable'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv3crecimempleable;
?>
</label>
<label class="Label190">
<?php
echo $ETI['cara44bienv3crecimhabilvida'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv3crecimhabilvida;
?>
</label>
<label class="Label190">
<?php
echo $ETI['cara44bienv3crecimespiritual'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv3crecimespiritual;
?>
</label>
<div class="salto1px"></div>
<label class="Label190">
<?php
echo $ETI['cara44bienv3crecimpractica'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv3crecimpractica;
?>
</label>
<div class="salto5px"></div>
<label class="Label700">
<?php
echo $ETI['cara44bienv3crecimhabil'];
?>
</label>
<div class="salto1px"></div>
<label class="Label190">
<?php
echo $ETI['cara44bienv3crecimliderazgo'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv3crecimliderazgo;
?>
</label>
<label class="Label190">
<?php
echo $ETI['cara44bienv3crecimtrabequipo'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv3crecimtrabequipo;
?>
</label>
<label class="Label190">
<?php
echo $ETI['cara44bienv3crecimasertiva'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv3crecimasertiva;
?>
</label>
<div class="salto1px"></div>
<label class="Label190">
<?php
echo $ETI['cara44bienv3crecimgesttiempo'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv3crecimgesttiempo;
?>
</label>
<label class="Label190">
<?php
echo $ETI['cara44bienv3crecimconflictos'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv3crecimconflictos;
?>
</label>
<label class="Label190">
<?php
echo $ETI['cara44bienv3crecimadapcambio'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv3crecimadapcambio;
?>
</label>
<div class="salto1px"></div>
<label class="Label190">
<?php
echo $ETI['cara44bienv3crecimempatia'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv3crecimempatia;
?>
</label>
<label class="Label190">
<?php
echo $ETI['cara44bienv3crecimgestionser'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv3crecimgestionser;
?>
</label>
<label class="Label190">
<?php
echo $ETI['cara44bienv3crecimtomadecide'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv3crecimtomadecide;
?>
</label>
<div class="salto1px"></div>
<label class="Label190">
<?php
echo $ETI['cara44bienv3crecimpenscreativo'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv3crecimpenscreativo;
?>
</label>
<label class="Label190">
<?php
echo $ETI['cara44bienv3crecimpenscritico'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv3crecimpenscritico;
?>
</label>
<label class="Label190">
<?php
echo $ETI['cara44bienv3crecimhabilotro'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv3crecimhabilotro;
$sMuestra = ' style="display:none"';
if ($_REQUEST['cara44bienv3crecimhabilotro'] == 1) {
$sMuestra = '';
}
?>
</label>
<label id="label_cara44bienv3crecimhabilotro" class="L" <?php echo $sMuestra; ?>>
<?php
echo $ETI['cara44bienv3crecimhabilotrodetalle'];
?>
<input id="cara44bienv3crecimhabilotrodetalle" name="cara44bienv3crecimhabilotrodetalle" type="text" value="<?php echo $_REQUEST['cara44bienv3crecimhabilotrodetalle']; ?>" maxlength="200" class="L" placeholder="<?php echo $ETI['ing_campo'] . $ETI['cara44bienv3crecimhabilotrodetalle']; ?>" />
</label>
<div class="salto5px"></div>
<label class="Label600">
<?php
echo $ETI['cara44bienv3crecimmotiva'];
?>
</label>
<div class="salto1px"></div>
<label class="Label190">
<?php
echo $ETI['cara44bienv3crecimalcancemeta'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv3crecimalcancemeta;
?>
</label>
<label class="Label190">
<?php
echo $ETI['cara44bienv3crecimsatifpersonal'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv3crecimsatifpersonal;
?>
</label>
<label class="Label190">
<?php
echo $ETI['cara44bienv3crecimaccesolaboral'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv3crecimaccesolaboral;
?>
</label>
<div class="salto1px"></div>
<label class="Label190">
<?php
echo $ETI['cara44bienv3crecimotramotiv'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv3crecimotramotiv;
$sMuestra = ' style="display:none"';
if ($_REQUEST['cara44bienv3crecimotramotiv'] == 1) {
$sMuestra = '';
}
?>
</label>
<label id="label_cara44bienv3crecimotramotiv" class="L" <?php echo $sMuestra; ?>>
<?php
echo $ETI['cara44bienv3crecimotramotivdetalle'];
?>
<input id="cara44bienv3crecimotramotivdetalle" name="cara44bienv3crecimotramotivdetalle" type="text" value="<?php echo $_REQUEST['cara44bienv3crecimotramotivdetalle']; ?>" maxlength="200" class="L" placeholder="<?php echo $ETI['ing_campo'] . $ETI['cara44bienv3crecimotramotivdetalle']; ?>" />
</label>
<div class="salto1px"></div>
<label class="Label450">
<?php
echo $ETI['cara44bienv3crecimapoyo'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv3crecimapoyo;
?>
</label>
<div class="salto1px"></div>
<label class="Label450">
<?php
echo $ETI['cara44bienv3crecimlaboral'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv3crecimlaboral;
?>
</label>
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo" style="width:100%">
<?php
echo $ETI['titulo_cara44bienv3mental'];
?>
</label>
<div class="salto1px"></div>
<label class="Label450">
<?php
echo $ETI['cara44bienv3mentalcuidado'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv3mentalcuidado;
?>
</label>
<div class="salto1px"></div>
<label class="Label450">
<?php
echo $ETI['cara44bienv3mentalestrategia'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv3mentalestrategia;
?>
</label>
<div class="salto1px"></div>
<label class="Label450">
<?php
echo $ETI['cara44bienv3mentaltemas'];
?>
</label>
<div class="salto1px"></div>
<label class="Label190">
<?php
echo $ETI['cara44bienv3mentalestres'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv3mentalestres;
?>
</label>
<label class="Label190">
<?php
echo $ETI['cara44bienv3mentalansiedad'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv3mentalansiedad;
?>
</label>
<label class="Label190">
<?php
echo $ETI['cara44bienv3mentaldepresion'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv3mentaldepresion;
?>
</label>
<div class="salto1px"></div>
<label class="Label190">
<?php
echo $ETI['cara44bienv3mentalautoconoc'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv3mentalautoconoc;
?>
</label>
<label class="Label190">
<?php
echo $ETI['cara44bienv3mentalmindfulness'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv3mentalmindfulness;
?>
</label>
<label class="Label190">
<?php
echo $ETI['cara44bienv3mentalautoestima'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv3mentalautoestima;
?>
</label>
<div class="salto1px"></div>
<label class="Label190">
<?php
echo $ETI['cara44bienv3mentalcrisis'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv3mentalcrisis;
?>
</label>
<label class="Label190">
<?php
echo $ETI['cara44bienv3mentalburnout'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv3mentalburnout;
?>
</label>
<label class="Label190">
<?php
echo $ETI['cara44bienv3mentalsexualidad'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv3mentalsexualidad;
?>
</label>
<div class="salto1px"></div>
<label class="Label190">
<?php
echo $ETI['cara44bienv3mentalusoredes'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv3mentalusoredes;
?>
</label>
<label class="Label190">
<?php
echo $ETI['cara44bienv3mentalinclusion'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv3mentalinclusion;
?>
</label>
<div class="salto1px"></div>
<label class="Label450">
<?php
echo $ETI['cara44bienv3mentalactividad'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv3mentalactividad;
?>
</label>
<div class="salto1px"></div>
<label class="Label450">
<?php
echo $ETI['cara44bienv3mentalacompana'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv3mentalacompana;
?>
</label>
<div class="salto1px"></div>
<label class="Label450">
<?php
echo $ETI['cara44bienv3mentaldiagnostico'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv3mentaldiagnostico;
$sMuestra = ' style="display:none"';
if ($_REQUEST['cara44bienv3mentaldiagnostico'] == 1) {
$sMuestra = '';
}
?>
</label>
<label id="label_cara44bienv3mentaldiagnostico" class="L" <?php echo $sMuestra; ?>>
<label class="Label450">
<?php
echo $ETI['cara44bienv3mentaldiagcual'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv3mentaldiagcual;
$sMuestra = ' style="display:none"';
if ($_REQUEST['cara44bienv3mentaldiagcual'] == 11) {
$sMuestra = '';
}
?>
</label>
<label id="label_cara44bienv3mentaldiagcual" class="L" <?php echo $sMuestra; ?>>
<?php
echo $ETI['cara44bienv3mentaldiagotro'];
?>
<input id="cara44bienv3mentaldiagotro" name="cara44bienv3mentaldiagotro" type="text" value="<?php echo $_REQUEST['cara44bienv3mentaldiagotro']; ?>" maxlength="200" class="L" placeholder="<?php echo $ETI['ing_campo'] . $ETI['cara44bienv3mentaldiagotro']; ?>" />
</label>
<div class="salto1px"></div>
</label>
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo" style="width:100%">
<?php
echo $ETI['titulo_cara44biencultura'];
?>
</label>
<div class="salto1px"></div>
<label class="Label450">
<?php
echo $ETI['cara44bienv3arteintegrar'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv3arteintegrar;
?>
</label>
<div class="salto1px"></div>
<label class="Label450">
<?php
echo $ETI['cara44bienv3arteformacion'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv3arteformacion;
?>
</label>
<div class="salto1px"></div>
<label class="Label450">
<?php
echo $ETI['cara44bienv3arteunad'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv3arteunad;
?>
</label>
<div class="salto1px"></div>
<label class="Label450">
<?php
echo $ETI['cara44bienv3arteinformacion'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara44bienv3arteinformacion;
?>
</label>
<div class="salto1px"></div>
</div>
<?php
} else {
?>
<input id="cara44bienv3emprenetapa" name="cara44bienv3emprenetapa" type="hidden" value="<?php echo $_REQUEST['cara44bienv3emprenetapa']; ?>" />
<input id="cara44bienv3emprennecesita" name="cara44bienv3emprennecesita" type="hidden" value="<?php echo $_REQUEST['cara44bienv3emprennecesita']; ?>" />
<input id="cara44bienv3emprenanioini" name="cara44bienv3emprenanioini" type="hidden" value="<?php echo $_REQUEST['cara44bienv3emprenanioini']; ?>" />
<input id="cara44bienv3emprensector" name="cara44bienv3emprensector" type="hidden" value="<?php echo $_REQUEST['cara44bienv3emprensector']; ?>" />
<input id="cara44bienv3emprensectorotro" name="cara44bienv3emprensectorotro" type="hidden" value="<?php echo $_REQUEST['cara44bienv3emprensectorotro']; ?>" />
<input id="cara44bienv3emprentemas" name="cara44bienv3emprentemas" type="hidden" value="<?php echo $_REQUEST['cara44bienv3emprentemas']; ?>" />
<input id="cara44bienv3ambienclima" name="cara44bienv3ambienclima" type="hidden" value="<?php echo $_REQUEST['cara44bienv3ambienclima']; ?>" />
<input id="cara44bienv3ambienjusticia" name="cara44bienv3ambienjusticia" type="hidden" value="<?php echo $_REQUEST['cara44bienv3ambienjusticia']; ?>" />
<input id="cara44bienv3ambienagroeco" name="cara44bienv3ambienagroeco" type="hidden" value="<?php echo $_REQUEST['cara44bienv3ambienagroeco']; ?>" />
<input id="cara44bienv3ambieneconomia" name="cara44bienv3ambieneconomia" type="hidden" value="<?php echo $_REQUEST['cara44bienv3ambieneconomia']; ?>" />
<input id="cara44bienv3ambieneducacion" name="cara44bienv3ambieneducacion" type="hidden" value="<?php echo $_REQUEST['cara44bienv3ambieneducacion']; ?>" />
<input id="cara44bienv3ambienbiodiverso" name="cara44bienv3ambienbiodiverso" type="hidden" value="<?php echo $_REQUEST['cara44bienv3ambienbiodiverso']; ?>" />
<input id="cara44bienv3ambienecoturismo" name="cara44bienv3ambienecoturismo" type="hidden" value="<?php echo $_REQUEST['cara44bienv3ambienecoturismo']; ?>" />
<input id="cara44bienv3ambienotro" name="cara44bienv3ambienotro" type="hidden" value="<?php echo $_REQUEST['cara44bienv3ambienotro']; ?>" />
<input id="cara44bienv3ambienotrodetalle" name="cara44bienv3ambienotrodetalle" type="hidden" value="<?php echo $_REQUEST['cara44bienv3ambienotrodetalle']; ?>" />
<input id="cara44bienv3ambienexper" name="cara44bienv3ambienexper" type="hidden" value="<?php echo $_REQUEST['cara44bienv3ambienexper']; ?>" />
<input id="cara44bienv3ambienaprende" name="cara44bienv3ambienaprende" type="hidden" value="<?php echo $_REQUEST['cara44bienv3ambienaprende']; ?>" />
<input id="cara44bienv3ambienestudiante" name="cara44bienv3ambienestudiante" type="hidden" value="<?php echo $_REQUEST['cara44bienv3ambienestudiante']; ?>" />
<input id="cara44bienv3ambienactividad" name="cara44bienv3ambienactividad" type="hidden" value="<?php echo $_REQUEST['cara44bienv3ambienactividad']; ?>" />
<input id="cara44bienv3pyphabitoalim" name="cara44bienv3pyphabitoalim" type="hidden" value="<?php echo $_REQUEST['cara44bienv3pyphabitoalim']; ?>" />
<input id="cara44bienv3pypsustanciapsico" name="cara44bienv3pypsustanciapsico" type="hidden" value="<?php echo $_REQUEST['cara44bienv3pypsustanciapsico']; ?>" />
<input id="cara44bienv3pypsaludvisual" name="cara44bienv3pypsaludvisual" type="hidden" value="<?php echo $_REQUEST['cara44bienv3pypsaludvisual']; ?>" />
<input id="cara44bienv3pypsaludbucal" name="cara44bienv3pypsaludbucal" type="hidden" value="<?php echo $_REQUEST['cara44bienv3pypsaludbucal']; ?>" />
<input id="cara44bienv3pypsaludsexual" name="cara44bienv3pypsaludsexual" type="hidden" value="<?php echo $_REQUEST['cara44bienv3pypsaludsexual']; ?>" />
<input id="cara44bienv3deportenivel" name="cara44bienv3deportenivel" type="hidden" value="<?php echo $_REQUEST['cara44bienv3deportenivel']; ?>" />
<input id="cara44bienv3deportefrec" name="cara44bienv3deportefrec" type="hidden" value="<?php echo $_REQUEST['cara44bienv3deportefrec']; ?>" />
<input id="cara44bienv3deportecual" name="cara44bienv3deportecual" type="hidden" value="<?php echo $_REQUEST['cara44bienv3deportecual']; ?>" />
<input id="cara44bienv3deportecualotro" name="cara44bienv3deportecualotro" type="hidden" value="<?php echo $_REQUEST['cara44bienv3deportecualotro']; ?>" />
<input id="cara44bienv3deporterecrea" name="cara44bienv3deporterecrea" type="hidden" value="<?php echo $_REQUEST['cara44bienv3deporterecrea']; ?>" />
<input id="cara44bienv3deporteunad" name="cara44bienv3deporteunad" type="hidden" value="<?php echo $_REQUEST['cara44bienv3deporteunad']; ?>" />
<input id="cara44bienv3creciminclusion" name="cara44bienv3creciminclusion" type="hidden" value="<?php echo $_REQUEST['cara44bienv3creciminclusion']; ?>" />
<input id="cara44bienv3crecimfamilia" name="cara44bienv3crecimfamilia" type="hidden" value="<?php echo $_REQUEST['cara44bienv3crecimfamilia']; ?>" />
<input id="cara44bienv3crecimhabilidad" name="cara44bienv3crecimhabilidad" type="hidden" value="<?php echo $_REQUEST['cara44bienv3crecimhabilidad']; ?>" />
<input id="cara44bienv3crecimempleable" name="cara44bienv3crecimempleable" type="hidden" value="<?php echo $_REQUEST['cara44bienv3crecimempleable']; ?>" />
<input id="cara44bienv3crecimhabilvida" name="cara44bienv3crecimhabilvida" type="hidden" value="<?php echo $_REQUEST['cara44bienv3crecimhabilvida']; ?>" />
<input id="cara44bienv3crecimespiritual" name="cara44bienv3crecimespiritual" type="hidden" value="<?php echo $_REQUEST['cara44bienv3crecimespiritual']; ?>" />
<input id="cara44bienv3crecimpractica" name="cara44bienv3crecimpractica" type="hidden" value="<?php echo $_REQUEST['cara44bienv3crecimpractica']; ?>" />
<input id="cara44bienv3crecimliderazgo" name="cara44bienv3crecimliderazgo" type="hidden" value="<?php echo $_REQUEST['cara44bienv3crecimliderazgo']; ?>" />
<input id="cara44bienv3crecimtrabequipo" name="cara44bienv3crecimtrabequipo" type="hidden" value="<?php echo $_REQUEST['cara44bienv3crecimtrabequipo']; ?>" />
<input id="cara44bienv3crecimasertiva" name="cara44bienv3crecimasertiva" type="hidden" value="<?php echo $_REQUEST['cara44bienv3crecimasertiva']; ?>" />
<input id="cara44bienv3crecimgesttiempo" name="cara44bienv3crecimgesttiempo" type="hidden" value="<?php echo $_REQUEST['cara44bienv3crecimgesttiempo']; ?>" />
<input id="cara44bienv3crecimconflictos" name="cara44bienv3crecimconflictos" type="hidden" value="<?php echo $_REQUEST['cara44bienv3crecimconflictos']; ?>" />
<input id="cara44bienv3crecimadapcambio" name="cara44bienv3crecimadapcambio" type="hidden" value="<?php echo $_REQUEST['cara44bienv3crecimadapcambio']; ?>" />
<input id="cara44bienv3crecimempatia" name="cara44bienv3crecimempatia" type="hidden" value="<?php echo $_REQUEST['cara44bienv3crecimempatia']; ?>" />
<input id="cara44bienv3crecimgestionser" name="cara44bienv3crecimgestionser" type="hidden" value="<?php echo $_REQUEST['cara44bienv3crecimgestionser']; ?>" />
<input id="cara44bienv3crecimtomadecide" name="cara44bienv3crecimtomadecide" type="hidden" value="<?php echo $_REQUEST['cara44bienv3crecimtomadecide']; ?>" />
<input id="cara44bienv3crecimpenscreativo" name="cara44bienv3crecimpenscreativo" type="hidden" value="<?php echo $_REQUEST['cara44bienv3crecimpenscreativo']; ?>" />
<input id="cara44bienv3crecimpenscritico" name="cara44bienv3crecimpenscritico" type="hidden" value="<?php echo $_REQUEST['cara44bienv3crecimpenscritico']; ?>" />
<input id="cara44bienv3crecimhabilotro" name="cara44bienv3crecimhabilotro" type="hidden" value="<?php echo $_REQUEST['cara44bienv3crecimhabilotro']; ?>" />
<input id="cara44bienv3crecimhabilotrodetalle" name="cara44bienv3crecimhabilotrodetalle" type="hidden" value="<?php echo $_REQUEST['cara44bienv3crecimhabilotrodetalle']; ?>" />
<input id="cara44bienv3crecimalcancemeta" name="cara44bienv3crecimalcancemeta" type="hidden" value="<?php echo $_REQUEST['cara44bienv3crecimalcancemeta']; ?>" />
<input id="cara44bienv3crecimsatifpersonal" name="cara44bienv3crecimsatifpersonal" type="hidden" value="<?php echo $_REQUEST['cara44bienv3crecimsatifpersonal']; ?>" />
<input id="cara44bienv3crecimaccesolaboral" name="cara44bienv3crecimaccesolaboral" type="hidden" value="<?php echo $_REQUEST['cara44bienv3crecimaccesolaboral']; ?>" />
<input id="cara44bienv3crecimotramotiv" name="cara44bienv3crecimotramotiv" type="hidden" value="<?php echo $_REQUEST['cara44bienv3crecimotramotiv']; ?>" />
<input id="cara44bienv3crecimotramotivdetalle" name="cara44bienv3crecimotramotivdetalle" type="hidden" value="<?php echo $_REQUEST['cara44bienv3crecimotramotivdetalle']; ?>" />
<input id="cara44bienv3crecimapoyo" name="cara44bienv3crecimapoyo" type="hidden" value="<?php echo $_REQUEST['cara44bienv3crecimapoyo']; ?>" />
<input id="cara44bienv3crecimlaboral" name="cara44bienv3crecimlaboral" type="hidden" value="<?php echo $_REQUEST['cara44bienv3crecimlaboral']; ?>" />
<input id="cara44bienv3mentalcuidado" name="cara44bienv3mentalcuidado" type="hidden" value="<?php echo $_REQUEST['cara44bienv3mentalcuidado']; ?>" />
<input id="cara44bienv3mentalestrategia" name="cara44bienv3mentalestrategia" type="hidden" value="<?php echo $_REQUEST['cara44bienv3mentalestrategia']; ?>" />
<input id="cara44bienv3mentalestres" name="cara44bienv3mentalestres" type="hidden" value="<?php echo $_REQUEST['cara44bienv3mentalestres']; ?>" />
<input id="cara44bienv3mentalansiedad" name="cara44bienv3mentalansiedad" type="hidden" value="<?php echo $_REQUEST['cara44bienv3mentalansiedad']; ?>" />
<input id="cara44bienv3mentaldepresion" name="cara44bienv3mentaldepresion" type="hidden" value="<?php echo $_REQUEST['cara44bienv3mentaldepresion']; ?>" />
<input id="cara44bienv3mentalautoconoc" name="cara44bienv3mentalautoconoc" type="hidden" value="<?php echo $_REQUEST['cara44bienv3mentalautoconoc']; ?>" />
<input id="cara44bienv3mentalmindfulness" name="cara44bienv3mentalmindfulness" type="hidden" value="<?php echo $_REQUEST['cara44bienv3mentalmindfulness']; ?>" />
<input id="cara44bienv3mentalautoestima" name="cara44bienv3mentalautoestima" type="hidden" value="<?php echo $_REQUEST['cara44bienv3mentalautoestima']; ?>" />
<input id="cara44bienv3mentalcrisis" name="cara44bienv3mentalcrisis" type="hidden" value="<?php echo $_REQUEST['cara44bienv3mentalcrisis']; ?>" />
<input id="cara44bienv3mentalburnout" name="cara44bienv3mentalburnout" type="hidden" value="<?php echo $_REQUEST['cara44bienv3mentalburnout']; ?>" />
<input id="cara44bienv3mentalsexualidad" name="cara44bienv3mentalsexualidad" type="hidden" value="<?php echo $_REQUEST['cara44bienv3mentalsexualidad']; ?>" />
<input id="cara44bienv3mentalusoredes" name="cara44bienv3mentalusoredes" type="hidden" value="<?php echo $_REQUEST['cara44bienv3mentalusoredes']; ?>" />
<input id="cara44bienv3mentalinclusion" name="cara44bienv3mentalinclusion" type="hidden" value="<?php echo $_REQUEST['cara44bienv3mentalinclusion']; ?>" />
<input id="cara44bienv3mentalactividad" name="cara44bienv3mentalactividad" type="hidden" value="<?php echo $_REQUEST['cara44bienv3mentalactividad']; ?>" />
<input id="cara44bienv3mentalacompana" name="cara44bienv3mentalacompana" type="hidden" value="<?php echo $_REQUEST['cara44bienv3mentalacompana']; ?>" />
<input id="cara44bienv3mentaldiagnostico" name="cara44bienv3mentaldiagnostico" type="hidden" value="<?php echo $_REQUEST['cara44bienv3mentaldiagnostico']; ?>" />
<input id="cara44bienv3mentaldiagcual" name="cara44bienv3mentaldiagcual" type="hidden" value="<?php echo $_REQUEST['cara44bienv3mentaldiagcual']; ?>" />
<input id="cara44bienv3mentaldiagotro" name="cara44bienv3mentaldiagotro" type="hidden" value="<?php echo $_REQUEST['cara44bienv3mentaldiagotro']; ?>" />
<input id="cara44bienv3arteintegrar" name="cara44bienv3arteintegrar" type="hidden" value="<?php echo $_REQUEST['cara44bienv3arteintegrar']; ?>" />
<input id="cara44bienv3arteformacion" name="cara44bienv3arteformacion" type="hidden" value="<?php echo $_REQUEST['cara44bienv3arteformacion']; ?>" />
<input id="cara44bienv3arteunad" name="cara44bienv3arteunad" type="hidden" value="<?php echo $_REQUEST['cara44bienv3arteunad']; ?>" />
<input id="cara44bienv3arteinformacion" name="cara44bienv3arteinformacion" type="hidden" value="<?php echo $_REQUEST['cara44bienv3arteinformacion']; ?>" />
<?php
}
?>
<?php
if ($bEstudiante) {
if ($_REQUEST['paso'] == 2){
	if ($bPintarTablero){
		echo html_2201Tablero(5, $objForma);
	} else {
		echo html_2201ContinuarCerrar(5, $objForma);
	}
}
}
?>
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
</div>

<?php
}
if ($bGrupo6) {
	$sEstilo = '';
	if ($_REQUEST['cara01fichapsico'] == -1) {
		$sEstilo = ' style="display:none"';
	}
	if ($bEstudiante) {
		$sEstilo = ' style="display:none"';
		if ($_REQUEST['ficha'] == 6) {
			$sEstilo = '';
		}
	}
?>
<div class="salto1px"></div>
<div class="GrupoCampos" id="div_ficha6" <?php echo $sEstilo; ?>>
<?php
if ($bConExpande) {
?>
<div class="ir_derecha"<?php echo $sAnchoExpandeContrae; ?>>
<?php
echo $objForma->htmlExpande(106, $_REQUEST['boculta106'], $ETI['bt_mostrar'], $ETI['bt_ocultar']);
$sEstiloDiv = '';
if ($_REQUEST['boculta106'] != 0) {
	$sEstiloDiv = ' style="display:none;"';
}
?>
</div>
<?php
}
?>
<label class="TituloGrupo">
<?php
echo $ETI['cara01fichapsico'];
?>
</label>
<label class="Label130">
<div id="div_cara01fichapsico">
<?php
$sMuestra = '&nbsp;';
if ($_REQUEST['cara01fichapsico'] == 1) {
$sMuestra = 'COMPLETA';
}
echo html_oculto('cara01fichapsico', $_REQUEST['cara01fichapsico'], $sMuestra);
?>
</div>
</label>
<?php
$bMuestra = false;
if ($_REQUEST['cara01completa'] == 'S') {
$bMuestra = true;
}
if ($bMuestra) {
?>
<label class="Label200">
<?php
echo $ETI['cara01psico_puntaje'];
?>
</label>
<label class="Label130">
<div id="div_cara01psico_puntaje">
<?php
//echo html_oculto('cara01psico_puntaje', $_REQUEST['cara01psico_puntaje']);
echo html_oculto('cara01psico_puntaje', $_REQUEST['cara01psico_puntaje'], f2301_NombrePuntaje('puntaje', $_REQUEST['cara01psico_puntaje']));
?>
</div>
</label>
<?php
} else {
?>
<input id="cara01psico_puntaje" name="cara01psico_puntaje" type="hidden" value="<?php echo $_REQUEST['cara01psico_puntaje']; ?>" />
<?php
}
?>
<div class="salto1px"></div>
<div id="div_p106"<?php echo $sEstiloDiv; ?>>
<?php
if ($_REQUEST['cara01completa'] == 'S') {
?>
<div class="salto1px"></div>
<div class="GrupoCamposAyuda">
<?php
if ($_REQUEST['cara01psico_puntaje'] < 17) {
echo $ETI['msg_psico_alto'];
} else {
if ($_REQUEST['cara01psico_puntaje'] < 24) {
echo $ETI['msg_psico_medio'];
} else {
echo $ETI['msg_psico_bajo'];
}
}
?>
<div class="salto1px"></div>
</div>
<?php
}
?>
<div class="salto1px"></div>
<label class="Label450">
<?php
echo $ETI['cara01psico_costoemocion'];
?>
</label>
<label>
<?php
echo $html_cara01psico_costoemocion;
?>
</label>
<div class="salto1px"></div>
<label class="Label450">
<?php
echo $ETI['cara01psico_reaccionimpre'];
?>
</label>
<label>
<?php
echo $html_cara01psico_reaccionimpre;
?>
</label>
<div class="salto1px"></div>
<label class="Label450">
<?php
echo $ETI['cara01psico_estres'];
?>
</label>
<label>
<?php
echo $html_cara01psico_estres;
?>
</label>
<div class="salto1px"></div>
<label class="Label450">
<?php
echo $ETI['cara01psico_pocotiempo'];
?>
</label>
<label>
<?php
echo $html_cara01psico_pocotiempo;
?>
</label>
<div class="salto1px"></div>
<label class="Label450">
<?php
echo $ETI['cara01psico_actitudvida'];
?>
</label>
<label>
<?php
echo $html_cara01psico_actitudvida;
?>
</label>
<div class="salto1px"></div>
<label class="Label450">
<?php
echo $ETI['cara01psico_duda'];
?>
</label>
<label>
<?php
echo $html_cara01psico_duda;
?>
</label>
<div class="salto1px"></div>
<label class="Label450">
<?php
echo $ETI['cara01psico_problemapers'];
?>
</label>
<label>
<?php
echo $html_cara01psico_problemapers;
?>
</label>
<div class="salto1px"></div>
<label class="Label450">
<?php
echo $ETI['cara01psico_satisfaccion'];
?>
</label>
<label>
<?php
echo $html_cara01psico_satisfaccion;
?>
</label>
<div class="salto1px"></div>
<label class="Label450">
<?php
echo $ETI['cara01psico_discusiones'];
?>
</label>
<label>
<?php
echo $html_cara01psico_discusiones;
?>
</label>
<div class="salto1px"></div>
<label class="Label450">
<?php
echo $ETI['cara01psico_atencion'];
?>
</label>
<label>
<?php
echo $html_cara01psico_atencion;
?>
</label>
<?php
if ($bEstudiante) {
if ($_REQUEST['paso'] == 2){
	if ($bPintarTablero){
		echo html_2201Tablero(6, $objForma);
	} else {
		echo html_2201ContinuarCerrar(6, $objForma);
	}
}
}
?>
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
</div>
<?php
}
//Competencias digitales...
if ($bGrupo7) {
	$sEstilo = '';
	if ($_REQUEST['cara01fichadigital'] == -1) {
		$sEstilo = ' style="display:none"';
	}
	if ($bEstudiante) {
		$sEstilo = ' style="display:none"';
		if ($_REQUEST['ficha'] == 7) {
			$sEstilo = '';
		}
	}
?>
<div class="salto1px"></div>
<div class="GrupoCampos" id="div_ficha7" <?php echo $sEstilo; ?>>
<?php
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
<?php
}
?>
<label class="TituloGrupo">
<?php
echo $ETI['cara01fichadigital'];
?>
</label>
<label class="Label130">
<div id="div_cara01fichadigital">
<?php
$sMuestra = '&nbsp;';
if ($_REQUEST['cara01fichadigital'] == 1) {
$sMuestra = 'COMPLETA';
}
echo html_oculto('cara01fichadigital', $_REQUEST['cara01fichadigital'], $sMuestra);
?>
</div>
</label>
<?php
if ($bEstudiante) {
?>
<input id="cara01niveldigital" name="cara01niveldigital" type="hidden" value="<?php echo $_REQUEST['cara01niveldigital']; ?>" />
<?php
} else {
?>
<label class="Label60">
<?php
echo $ETI['msg_nivel'];
?>
</label>
<label class="Label130">
<?php
//echo html_oculto('cara01niveldigital', f2301_NombrePuntaje('digital',$_REQUEST['cara01niveldigital']));
echo html_oculto('cara01niveldigital', $_REQUEST['cara01niveldigital'], f2301_NombrePuntaje('digital', $_REQUEST['cara01niveldigital']));
?>
</label>
<?php
if ($_REQUEST['cara01completa'] != 'S') {
	echo $objForma->htmlBotonSolo('brevisadv', 'btMiniActualizar', 'actualizapreguntas()', 'Actualizar preguntas', 30);
}
}
?>
<div class="salto1px"></div>
<div id="div_p107"<?php echo $sEstiloDiv; ?>>
<div id="div_f2310detalle_7">
<?php
echo $sTabla2310_7;
?>
</div>

<?php
if ($bEstudiante) {
if ($_REQUEST['paso'] == 2){
	if ($bPintarTablero){
		echo html_2201Tablero(7, $objForma);
	} else {
		echo html_2201ContinuarCerrar(7, $objForma);
	}
}
}
?>
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
</div>
<?php
}
if ($bGrupo8) {
	$sEstilo = '';
	if ($_REQUEST['cara01fichalectura'] == -1) {
		$sEstilo = ' style="display:none"';
	}
	if ($bEstudiante) {
		$sEstilo = ' style="display:none"';
		if ($_REQUEST['ficha'] == 8) {
			$sEstilo = '';
		}
	}
?>
<div class="salto1px"></div>
<div class="GrupoCampos" id="div_ficha8" <?php echo $sEstilo; ?>>
<?php
if ($bConExpande) {
?>
<div class="ir_derecha"<?php echo $sAnchoExpandeContrae; ?>>
<?php
echo $objForma->htmlExpande(108, $_REQUEST['boculta108'], $ETI['bt_mostrar'], $ETI['bt_ocultar']);
$sEstiloDiv = '';
if ($_REQUEST['boculta108'] != 0) {
	$sEstiloDiv = ' style="display:none;"';
}
?>
</div>
<?php
}
?>
<label class="TituloGrupo">
<?php
echo $ETI['cara01fichalectura'];
?>
</label>
<label class="Label130">
<div id="div_cara01fichalectura">
<?php
$sMuestra = '&nbsp;';
if ($_REQUEST['cara01fichalectura'] == 1) {
$sMuestra = 'COMPLETA';
}
echo html_oculto('cara01fichalectura', $_REQUEST['cara01fichalectura'], $sMuestra);
?>
</div>
</label>
<?php
if ($bEstudiante) {
?>
<input id="cara01nivellectura" name="cara01nivellectura" type="hidden" value="<?php echo $_REQUEST['cara01nivellectura']; ?>" />
<?php
} else {
?>
<label class="Label60">
<?php
echo $ETI['msg_nivel'];
?>
</label>
<label class="Label130">
<?php
echo html_oculto('cara01nivellectura', $_REQUEST['cara01nivellectura'], f2301_NombrePuntaje('lectura', $_REQUEST['cara01nivellectura']));
//echo html_oculto('cara01nivellectura', $_REQUEST['cara01nivellectura']);
?>
</label>
<?php
}
?>
<div class="salto1px"></div>
<div id="div_p108"<?php echo $sEstiloDiv; ?>>
<div id="div_f2310detalle_8">
<?php
echo $sTabla2310_8;
?>
</div>
<?php
if ($bEstudiante) {
if ($_REQUEST['paso'] == 2){
	if ($bPintarTablero){
		echo html_2201Tablero(8, $objForma);
	} else {
		echo html_2201ContinuarCerrar(8, $objForma);
	}
}
}
?>
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
</div>
<?php
}
if ($bGrupo9) {
	$sEstilo = '';
	if ($_REQUEST['cara01ficharazona'] == -1) {
		$sEstilo = ' style="display:none"';
	}
	if ($bEstudiante) {
		$sEstilo = ' style="display:none"';
		if ($_REQUEST['ficha'] == 9) {
			$sEstilo = '';
		}
	}
?>
<div class="salto1px"></div>
<div class="GrupoCampos" id="div_ficha9" <?php echo $sEstilo; ?>>
<?php
if ($bConExpande) {
?>
<div class="ir_derecha"<?php echo $sAnchoExpandeContrae; ?>>
<?php
echo $objForma->htmlExpande(109, $_REQUEST['boculta109'], $ETI['bt_mostrar'], $ETI['bt_ocultar']);
$sEstiloDiv = '';
if ($_REQUEST['boculta109'] != 0) {
	$sEstiloDiv = ' style="display:none;"';
}
?>
</div>
<?php
}
?>
<label class="TituloGrupo">
<?php
echo $ETI['cara01ficharazona'];
?>
</label>
<label class="Label130">
<div id="div_cara01ficharazona">
<?php
$sMuestra = '&nbsp;';
if ($_REQUEST['cara01ficharazona'] == 1) {
$sMuestra = 'COMPLETA';
}
echo html_oculto('cara01ficharazona', $_REQUEST['cara01ficharazona'], $sMuestra);
?>
</div>
</label>
<?php
if ($bEstudiante) {
?>
<input id="cara01nivelrazona" name="cara01nivelrazona" type="hidden" value="<?php echo $_REQUEST['cara01nivelrazona']; ?>" />
<?php
} else {
?>
<label class="Label60">
<?php
echo $ETI['msg_nivel'];
?>
</label>
<label class="Label130">
<?php
//echo html_oculto('cara01nivelrazona', $_REQUEST['cara01nivelrazona']);
echo html_oculto('cara01nivelrazona', $_REQUEST['cara01nivelrazona'], f2301_NombrePuntaje('razona', $_REQUEST['cara01nivelrazona']));
?>
</label>
<?php
}
?>
<div class="salto1px"></div>
<div id="div_p109"<?php echo $sEstiloDiv; ?>>
<div id="div_f2310detalle_9">
<?php
echo $sTabla2310_9;
?>
</div>

<?php
if ($bEstudiante) {
if ($_REQUEST['paso'] == 2){
	if ($bPintarTablero){
		echo html_2201Tablero(9, $objForma);
	} else {
		echo html_2201ContinuarCerrar(9, $objForma);
	}
}
}
?>
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
</div>
<?php
}
if ($bGrupo10) {
	$sEstilo = '';
	if ($_REQUEST['cara01fichaingles'] == -1) {
		$sEstilo = ' style="display:none"';
	}
	if ($bEstudiante) {
		$sEstilo = ' style="display:none"';
		if ($_REQUEST['ficha'] == 10) {
			$sEstilo = '';
		}
	}
?>
<div class="salto1px"></div>
<div class="GrupoCampos" id="div_ficha10" <?php echo $sEstilo; ?>>
<?php
if ($bConExpande) {
?>
<div class="ir_derecha"<?php echo $sAnchoExpandeContrae; ?>>
<?php
echo $objForma->htmlExpande(110, $_REQUEST['boculta110'], $ETI['bt_mostrar'], $ETI['bt_ocultar']);
$sEstiloDiv = '';
if ($_REQUEST['boculta110'] != 0) {
	$sEstiloDiv = ' style="display:none;"';
}
?>
</div>
<?php
}
?>
<label class="TituloGrupo">
<?php
echo $ETI['cara01fichaingles'];
?>
</label>
<label class="Label130">
<div id="div_cara01fichaingles">
<?php
$sMuestra = '&nbsp;';
if ($_REQUEST['cara01fichaingles'] == 1) {
$sMuestra = 'COMPLETA';
}
echo html_oculto('cara01fichaingles', $_REQUEST['cara01fichaingles'], $sMuestra);
?>
</div>
</label>
<?php
if ($bEstudiante) {
?>
<input id="cara01nivelingles" name="cara01nivelingles" type="hidden" value="<?php echo $_REQUEST['cara01nivelingles']; ?>" />
<?php
} else {
?>
<label class="Label60">
<?php
echo $ETI['msg_nivel'];
?>
</label>
<label class="Label130">
<?php
//echo html_oculto('cara01nivelingles', $_REQUEST['cara01nivelingles']);
echo html_oculto('cara01nivelingles', $_REQUEST['cara01nivelingles'], f2301_NombrePuntaje('ingles', $_REQUEST['cara01nivelingles']));
?>
</label>
<?php
}
?>
<div class="salto1px"></div>
<div id="div_p110"<?php echo $sEstiloDiv; ?>>
<div id="div_f2310detalle_10">
<?php
echo $sTabla2310_10;
?>
</div>

<?php
if ($bEstudiante) {
if ($_REQUEST['paso'] == 2){
	if ($bPintarTablero){
		echo html_2201Tablero(10, $objForma);
	} else {
		echo html_2201ContinuarCerrar(10, $objForma);
	}
}
}
?>
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
</div>
<?php
}
if ($bGrupo11) {
	$sEstilo = '';
	if ($_REQUEST['cara01fichabiolog'] == -1) {
		$sEstilo = ' style="display:none"';
	}
	if ($bEstudiante) {
		$sEstilo = ' style="display:none"';
		if ($_REQUEST['ficha'] == 11) {
			$sEstilo = '';
		}
	}
?>
<div class="salto1px"></div>
<div class="GrupoCampos" id="div_ficha11" <?php echo $sEstilo; ?>>
<?php
if ($bConExpande) {
?>
<div class="ir_derecha"<?php echo $sAnchoExpandeContrae; ?>>
<?php
echo $objForma->htmlExpande(111, $_REQUEST['boculta111'], $ETI['bt_mostrar'], $ETI['bt_ocultar']);
$sEstiloDiv = '';
if ($_REQUEST['boculta111'] != 0) {
	$sEstiloDiv = ' style="display:none;"';
}
?>
</div>
<?php
}
?>
<label class="TituloGrupo">
<?php
echo $ETI['cara01fichabiolog'];
?>
</label>
<label class="Label130">
<div id="div_cara01fichabiolog">
<?php
$sMuestra = '&nbsp;';
if ($_REQUEST['cara01fichabiolog'] == 1) {
$sMuestra = 'COMPLETA';
}
echo html_oculto('cara01fichabiolog', $_REQUEST['cara01fichabiolog'], $sMuestra);
?>
</div>
</label>
<?php
if ($bEstudiante) {
?>
<input id="cara01nivelbiolog" name="cara01nivelbiolog" type="hidden" value="<?php echo $_REQUEST['cara01nivelbiolog']; ?>" />
<?php
} else {
?>
<label class="Label60">
<?php
echo $ETI['msg_nivel'];
?>
</label>
<label class="Label130">
<?php
//echo html_oculto('cara01nivelbiolog', $_REQUEST['cara01nivelbiolog']);
echo html_oculto('cara01nivelbiolog', $_REQUEST['cara01nivelbiolog'], f2301_NombrePuntaje('biolog', $_REQUEST['cara01nivelbiolog']));
?>
</label>
<?php
}
?>
<div class="salto1px"></div>
<div id="div_p111"<?php echo $sEstiloDiv; ?>>
<div id="div_f2310detalle_11">
<?php
echo $sTabla2310_11;
?>
</div>
<?php
if ($bEstudiante) {
if ($_REQUEST['paso'] == 2){
	if ($bPintarTablero){
		echo html_2201Tablero(11, $objForma);
	} else {
		echo html_2201ContinuarCerrar(11, $objForma);
	}
}
}
?>
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
</div>
<?php
}
if ($bGrupo12) {
	$sEstilo = '';
	if ($_REQUEST['cara01fichafisica'] == -1) {
		$sEstilo = ' style="display:none"';
	}
	if ($bEstudiante) {
		$sEstilo = ' style="display:none"';
		if ($_REQUEST['ficha'] == 12) {
			$sEstilo = '';
		}
	}
?>
<div class="salto1px"></div>
<div class="GrupoCampos" id="div_ficha12" <?php echo $sEstilo; ?>>
<?php
if ($bConExpande) {
?>
<div class="ir_derecha"<?php echo $sAnchoExpandeContrae; ?>>
<?php
echo $objForma->htmlExpande(112, $_REQUEST['boculta112'], $ETI['bt_mostrar'], $ETI['bt_ocultar']);
$sEstiloDiv = '';
if ($_REQUEST['boculta112'] != 0) {
	$sEstiloDiv = ' style="display:none;"';
}
?>
</div>
<?php
}
?>
<label class="TituloGrupo">
<?php
echo $ETI['cara01fichafisica'];
?>
</label>
<label class="Label130">
<div id="div_cara01fichafisica">
<?php
$sMuestra = '&nbsp;';
if ($_REQUEST['cara01fichafisica'] == 1) {
$sMuestra = 'COMPLETA';
}
echo html_oculto('cara01fichafisica', $_REQUEST['cara01fichafisica'], $sMuestra);
?>
</div>
</label>
<?php
if ($bEstudiante) {
?>
<input id="cara01nivelfisica" name="cara01nivelfisica" type="hidden" value="<?php echo $_REQUEST['cara01nivelfisica']; ?>" />
<?php
} else {
?>
<label class="Label60">
<?php
echo $ETI['msg_nivel'];
?>
</label>
<label class="Label130">
<?php
//echo html_oculto('cara01nivelfisica', $_REQUEST['cara01nivelfisica']);
echo html_oculto('cara01nivelfisica', $_REQUEST['cara01nivelfisica'], f2301_NombrePuntaje('fisica', $_REQUEST['cara01nivelfisica']));
?>
</label>
<?php
}
?>
<div class="salto1px"></div>
<div id="div_p112"<?php echo $sEstiloDiv; ?>>
<div id="div_f2310detalle_12">
<?php
echo $sTabla2310_12;
?>
</div>

<?php
if ($bEstudiante) {
if ($_REQUEST['paso'] == 2){
	if ($bPintarTablero){
		echo html_2201Tablero(12, $objForma);
	} else {
		echo html_2201ContinuarCerrar(12, $objForma);
	}
}
}
?>
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
</div>
<?php
}
if ($bGrupo13) {
	$sEstilo = '';
	if ($_REQUEST['cara01fichaquimica'] == -1) {
		$sEstilo = ' style="display:none"';
	}
	if ($bEstudiante) {
		$sEstilo = ' style="display:none"';
		if ($_REQUEST['ficha'] == 13) {
			$sEstilo = '';
		}
	}
?>
<div class="salto1px"></div>
<div class="GrupoCampos" id="div_ficha13" <?php echo $sEstilo; ?>>
<?php
if ($bConExpande) {
?>
<div class="ir_derecha"<?php echo $sAnchoExpandeContrae; ?>>
<?php
echo $objForma->htmlExpande(113, $_REQUEST['boculta113'], $ETI['bt_mostrar'], $ETI['bt_ocultar']);
$sEstiloDiv = '';
if ($_REQUEST['boculta113'] != 0) {
	$sEstiloDiv = ' style="display:none;"';
}
?>
</div>
<?php
}
?>
<label class="TituloGrupo">
<?php
echo $ETI['cara01fichaquimica'];
?>
</label>
<label class="Label130">
<div id="div_cara01fichaquimica">
<?php
$sMuestra = '&nbsp;';
if ($_REQUEST['cara01fichaquimica'] == 1) {
$sMuestra = 'COMPLETA';
}
echo html_oculto('cara01fichaquimica', $_REQUEST['cara01fichaquimica'], $sMuestra);
?>
</div>
</label>
<?php
if ($bEstudiante) {
?>
<input id="cara01nivelquimica" name="cara01nivelquimica" type="hidden" value="<?php echo $_REQUEST['cara01nivelquimica']; ?>" />
<?php
} else {
?>
<label class="Label60">
<?php
echo $ETI['msg_nivel'];
?>
</label>
<label class="Label130">
<?php
//echo html_oculto('cara01nivelquimica', $_REQUEST['cara01nivelquimica']);
echo html_oculto('cara01nivelquimica', $_REQUEST['cara01nivelquimica'], f2301_NombrePuntaje('quimica', $_REQUEST['cara01nivelquimica']));
?>
</label>
<?php
}
?>
<div class="salto1px"></div>
<div id="div_p113"<?php echo $sEstiloDiv; ?>>
<div id="div_f2310detalle_13">
<?php
echo $sTabla2310_13;
?>
</div>
<?php
if ($bEstudiante) {
if ($_REQUEST['paso'] == 2){
	if ($bPintarTablero){
		echo html_2201Tablero(13, $objForma);
	} else {
		echo html_2201ContinuarCerrar(13, $objForma);
	}
}
}
?>
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
</div>
<?php
}
if ($bGrupo14) {
	$sEstilo = '';
	if ($_REQUEST['cara01fichaciudad'] == -1) {
		$sEstilo = ' style="display:none"';
	}
	if ($bEstudiante) {
		$sEstilo = ' style="display:none"';
		if ($_REQUEST['ficha'] == 14) {
			$sEstilo = '';
		}
	}
?>
<div class="salto1px"></div>
<div class="GrupoCampos" id="div_ficha14" <?php echo $sEstilo; ?>>
<?php
if ($bConExpande) {
?>
<div class="ir_derecha"<?php echo $sAnchoExpandeContrae; ?>>
<?php
echo $objForma->htmlExpande(114, $_REQUEST['boculta114'], $ETI['bt_mostrar'], $ETI['bt_ocultar']);
$sEstiloDiv = '';
if ($_REQUEST['boculta114'] != 0) {
	$sEstiloDiv = ' style="display:none;"';
}
?>
</div>
<?php
}
?>
<label class="TituloGrupo">
<?php
echo $ETI['cara01fichaciudad'];
?>
</label>
<label class="Label130">
<div id="div_cara01fichaciudad">
<?php
$sMuestra = '&nbsp;';
if ($_REQUEST['cara01fichaciudad'] == 1) {
$sMuestra = 'COMPLETA';
}
echo html_oculto('cara01fichaciudad', $_REQUEST['cara01fichaciudad'], $sMuestra);
?>
</div>
</label>
<?php
if ($bEstudiante) {
?>
<input id="cara01nivelciudad" name="cara01nivelciudad" type="hidden" value="<?php echo $_REQUEST['cara01nivelciudad']; ?>" />
<?php
} else {
?>
<label class="Label60">
<?php
echo $ETI['msg_nivel'];
?>
</label>
<label class="Label130">
<?php
//echo html_oculto('cara01nivelquimica', $_REQUEST['cara01nivelquimica']);
echo html_oculto('cara01nivelciudad', $_REQUEST['cara01nivelciudad'], f2301_NombrePuntaje('ciudadanas', $_REQUEST['cara01nivelciudad']));
?>
</label>
<?php
}
?>
<div class="salto1px"></div>
<div id="div_p114"<?php echo $sEstiloDiv; ?>>
<div id="div_f2310detalle_14">
<?php
echo $sTabla2310_14;
?>
</div>
<?php
if ($bEstudiante) {
if ($_REQUEST['paso'] == 2){
	if ($bPintarTablero){
		echo html_2201Tablero(14, $objForma);
	} else {
		echo html_2201ContinuarCerrar(14, $objForma);
	}
}
}
?>
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
</div>
<?php
}
if (!$bEstudiante) {
?>
<div class="salto1px"></div>
<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo $ETI['cara01idconsejero'];
?>
</label>
<div class="salto1px"></div>
<input id="cara01idconsejero" name="cara01idconsejero" type="hidden" value="<?php echo $_REQUEST['cara01idconsejero']; ?>" />
<div id="div_cara01idconsejero_llaves">
<?php
echo $html_cara01idconsejero;
?>
</div>
<div class="salto1px"></div>
<div id="div_cara01idconsejero" class="L"><?php echo $cara01idconsejero_rs; ?></div>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['cara01idcursocatedra'];
?>
</label>
<label class="Label130">
<div id="div_cara01idcursocatedra">
<?php
echo html_oculto('cara01idcursocatedra', $_REQUEST['cara01idcursocatedra']);
?>
</div>
</label>
<label class="Label60">
<?php
echo $ETI['cara01idgrupocatedra'];
?>
</label>
<label class="Label30">
<div id="div_cara01idgrupocatedra">
<?php
echo html_oculto('cara01idgrupocatedra', $_REQUEST['cara01idgrupocatedra']);
?>
</div>
</label>
<div class="salto1px"></div>
</div>


<div class="GrupoCampos520">
<label class="Label130">
<?php
echo $ETI['cara01fechainicio'];
?>
</label>
<div class="Campo250">
<?php
echo html_FechaEnNumero('cara01fechainicio', $_REQUEST['cara01fechainicio']); //$bvacio=false,$accion=",$iagnoini=0,$iagnofin=0
?>
</div>
<!--
<label class="Label30">
<input id="bcara01fechainicio_hoy" name="bcara01fechainicio_hoy" type="button" value="Hoy" class="btMiniHoy" onclick="fecha_AsignarNum('cara01fechainicio','<?php echo fecha_DiaMod(); ?>')" title="<?php echo $ETI['bt_hoy']; ?>"/>
</label>
-->
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['cara01idescuela'];
?>
</label>
<label class="Label350">
<div id="div_cara01idescuela">
<?php
echo $html_cara01idescuela;
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['cara01idprograma'];
?>
</label>
<label class="Label350">
<div id="div_cara01idprograma">
<?php
echo $html_cara01idprograma;
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label200">
<?php
echo $ETI['cara01tipocaracterizacion'];
?>
</label>
<label class="Label300">
<?php
echo $html_cara01tipocaracterizacion;
?>
</label>
<div class="salto1px"></div>
</div>
<?php
} else {
?>
<input id="cara01idconsejero" name="cara01idconsejero" type="hidden" value="<?php echo $_REQUEST['cara01idconsejero']; ?>" />
<input id="cara01idconsejero_td" name="cara01idconsejero_td" type="hidden" value="<?php echo $_REQUEST['cara01idconsejero_td']; ?>" />
<input id="cara01idconsejero_doc" name="cara01idconsejero_doc" type="hidden" value="<?php echo $_REQUEST['cara01idconsejero_doc']; ?>" />
<input id="cara01fechainicio" name="cara01fechainicio" type="hidden" value="<?php echo $_REQUEST['cara01fechainicio']; ?>" />
<input id="cara01idprograma" name="cara01idprograma" type="hidden" value="<?php echo $_REQUEST['cara01idprograma']; ?>" />
<input id="cara01idescuela" name="cara01idescuela" type="hidden" value="<?php echo $_REQUEST['cara01idescuela']; ?>" />
<input id="cara01tipocaracterizacion" name="cara01tipocaracterizacion" type="hidden" value="<?php echo $_REQUEST['cara01tipocaracterizacion']; ?>" />
<input id="cara01idcursocatedra" name="cara01idcursocatedra" type="hidden" value="<?php echo $_REQUEST['cara01idcursocatedra']; ?>" />
<input id="cara01idgrupocatedra" name="cara01idgrupocatedra" type="hidden" value="<?php echo $_REQUEST['cara01idgrupocatedra']; ?>" />
<?php
}
$sEstilo = ' style="display:none"';
if (!$bEstudiante) {
$sEstilo = '';
}
?>
<div class="salto1px"></div>

<div class="GrupoCampos" <?php echo $sEstilo; ?>>
<label class="TituloGrupo">
<?php
echo $ETI['msg_factores'];
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['cara01factordescper'];
?>
</label>
<label class="Label30">
<div id="div_cara01factordescper">
<?php
echo html_oculto('cara01factordescper', $_REQUEST['cara01factordescper']);
?>
</div>
</label>
<label class="Label130">
<?php
echo $ETI['cara01factordescpsico'];
?>
</label>
<label class="Label30">
<div id="div_cara01factordescpsico">
<?php
echo html_oculto('cara01factordescpsico', $_REQUEST['cara01factordescpsico']);
?>
</div>
</label>
<label class="Label130">
<?php
echo $ETI['cara01factordescinsti'];
?>
</label>
<label class="Label30">
<div id="div_cara01factordescinsti">
<?php
echo html_oculto('cara01factordescinsti', $_REQUEST['cara01factordescinsti']);
?>
</div>
</label>
<label class="Label130">
<?php
echo $ETI['cara01factordescacad'];
?>
</label>
<label class="Label30">
<div id="div_cara01factordescacad">
<?php
echo html_oculto('cara01factordescacad', $_REQUEST['cara01factordescacad']);
?>
</div>
</label>
<label class="Label130">
<?php
echo $ETI['cara01factordesc'];
?>
</label>
<label class="Label30">
<div id="div_cara01factordesc">
<?php
echo html_oculto('cara01factordesc', $_REQUEST['cara01factordesc']);
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label250">
<?php
echo $ETI['cara01criteriodesc'];
?>
</label>
<label class="Label220">
<?php
echo $html_cara01criteriodesc;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara01desertor'];
?>
</label>
<label class="Label130">
<?php
echo $html_cara01desertor;
?>
</label>
<?php
if ($_REQUEST['cara01desertor'] == 'S') {
?>
<label class="Label130">
<?php
echo $ETI['cara01factorprincipaldesc'];
?>
</label>
<label>
<?php
echo $html_cara01factorprincipaldesc;
?>
</label>
<?php
} else {
?>
<input id="cara01factorprincipaldesc" name="cara01factorprincipaldesc" type="hidden" value="<?php echo $_REQUEST['cara01factorprincipaldesc']; ?>" />
<?php
}
?>
<div class="salto1px"></div>
</div>

<?php
if (false) {
//Ejemplo de boton de ayuda
//echo html_BotonAyuda('NombreCampo');
//echo html_DivAyudaLocal('NombreCampo');
}
if ($bConExpande) {
//Este es el cierre del div_p2301
?>
<div class="salto1px"></div>
</div>
<?php
}
//Mostrar el contenido de la tabla
// CIERRA EL DIV areatrabajo
if (!$bEstudiante) {
?>
</div><!-- CIERRA EL DIV areatrabajo -->
</div><!-- CIERRA EL DIV areaform -->
<div class="areaform">
<div class="areatitulo">
<?php
echo '<h3>' . $ETI['bloque1'] . '</h3>';
?>
</div>
<div class="areatrabajo">
<div class="ir_derecha">
<label class="Label90">
Documento
</label>
<label>
<input id="bdoc" name="bdoc" type="text" value="<?php echo $_REQUEST['bdoc']; ?>" onchange="paginarf2301()" autocomplete="off" />
</label>
<label class="Label90">
Nombre
</label>
<label class="Label250">
<input id="bnombre" name="bnombre" type="text" value="<?php echo $_REQUEST['bnombre']; ?>" onchange="paginarf2301()" autocomplete="off" />
</label>
<label class="Label60">
Listar
</label>
<label class="Label200">
<?php
echo $html_blistar;
?>
</label>
<div class="salto1px"></div>
<label class="Label90">
Periodo
</label>
<label class="Label130">
<?php
echo $html_bperiodo;
?>
</label>
<div class="salto1px"></div>
<label class="Label90">
Escuela
</label>
<label class="Label600">
<?php
echo $html_bescuela;
?>
</label>
<label class="Label60">
Tipo
</label>
<label>
<?php
echo $html_btipocara;
?>
</label>
<div class="salto1px"></div>
<label class="Label90">
Programa
</label>
<label class="Label130">
<div id="div_bprograma">
<?php
echo $html_bprograma;
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label90">
Zona
</label>
<label class="Label350">
<?php
echo $html_bzona;
?>
</label>
<label class="Label90">
Poblaci&oacute;n
</label>
<label class="Label130">
<?php
echo $html_bpoblacion;
?>
</label>
<div class="salto1px"></div>
<label class="Label90">
CEAD
</label>
<label class="Label300">
<div id="div_bcead">
<?php
echo $html_bcead;
?>
</div>
</label>
<?php
echo $objForma->htmlBotonSolo('bRevMatricula', 'btMiniActualizar', 'vermatricula()', 'Verificar datos de matricula', 30);
?>
<div class="salto1px"></div>
<label class="Label90">
Convenio
</label>
<label class="Label130">
<?php
echo $html_bconvenio;
?>
</label>
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
<?php
echo ' ' . $csv_separa;
?>
<div id="div_f2301detalle">
<?php
echo $sTabla2301;
?>
</div>
<?php
//Termina el modo estudiante...
}
?>
</div><!-- /div_areatrabajo -->
</div><!-- /DIV_areaform -->
</div><!-- /DIV_Sector1 -->


<div id="div_sector2" style="display:none">
<?php
$objForma = new clsHtmlForma($iPiel);
if ($bBloqueTitulo) {
	$objForma->addBoton('cmdAyuda2', 'btSupAyuda', 'muestraayuda(' . $iCodModulo . ');', $ETI['bt_ayuda']);
	$objForma->addBoton('cmdVolverSec2', 'btSupVolver', 'retornacontrol();', $ETI['bt_volver']);
	echo $objForma->htmlTitulo($sTituloModulo, $iCodModulo);
}
echo $objForma->htmlInicioMarco();
echo $objForma->htmlFinMarco();
?>
</div><!-- /DIV_Sector2 -->


<div id="div_sector10" style="<?php echo $sEstiloSector10; ?>">
<?php
$objForma = new clsHtmlForma($iPiel);
if ($bBloqueTitulo) {
	$objForma->addBoton('cmdAyuda10', 'btSupAyuda', 'muestraayuda(' . $iCodModulo . ');', $ETI['bt_ayuda']);
	$objForma->addBoton('cmdVolverSec10', 'btSupVolver', 'retornacontrol();', $ETI['bt_volver']);
	echo $objForma->htmlTitulo($sTituloModulo, $iCodModulo);
}
echo $objForma->htmlInicioMarco();
?>
<div class="GrupoCampos">
<div class="salto1px"></div>
<?php
if ($sMensaje == '') {
	$sMensaje = $ETI['msg_intro_nuevos'];
}
echo $sMensaje;
?>
<div class="salto5px"></div>
<label class="Label130">&nbsp;</label>
<?php
echo $objForma->htmlBotonSolo('cmdIniciar', 'btUpVisto azul', 'iniciar()', 'Iniciar', 130);
?>
<div class="salto1px"></div>
</div>
<?php
echo $objForma->htmlFinMarco();
?>
</div><!-- /DIV_Sector10 -->


<div id="div_sector11" style="<?php echo $sEstiloSector11; ?>">
<?php
$objForma = new clsHtmlForma($iPiel);
if ($bBloqueTitulo) {
	$objForma->addBoton('cmdAyuda11', 'btSupAyuda', 'muestraayuda(' . $iCodModulo . ');', $ETI['bt_ayuda']);
	$objForma->addBoton('cmdVolverSec11', 'btSupVolver', 'retornacontrol();', $ETI['bt_volver']);
	echo $objForma->htmlTitulo($sTituloModulo, $iCodModulo);
}
echo $objForma->htmlInicioMarco();
?>
<div class="GrupoCampos">
<div class="salto1px"></div>
<?php
echo $ETI['msg_noconsiderado'];
?>
<div class="salto5px"></div>
<label class="Label300">&nbsp;</label>
<?php
echo $objForma->htmlBotonSolo('cmdTablero', 'botonContinuar azul', 'irtablero()', 'Mis Cursos', 130);
?>
<div class="salto1px"></div>
</div>
<?php
echo $objForma->htmlFinMarco();
?>
</div><!-- /DIV_Sector11 -->

<div id="div_sector12" style="<?php echo $sEstiloSector12; ?>">
<?php
$objForma = new clsHtmlForma($iPiel);
if ($bBloqueTitulo) {
	$objForma->addBoton('cmdAyuda12', 'btSupAyuda', 'muestraayuda(' . $iCodModulo . ');', $ETI['bt_ayuda']);
	$objForma->addBoton('cmdVolverSec12', 'btSupVolver', 'retornacontrol();', $ETI['bt_volver']);
	echo $objForma->htmlTitulo($sTituloModulo, $iCodModulo);
}
echo $objForma->htmlInicioMarco();
?>
<div class="GrupoCampos">
<div class="salto1px"></div>
<?php
echo '<h3>' . $sErrorInicioEnc . '</h3>';
?>
<div class="salto5px"></div>
<label class="Label300">&nbsp;</label>
<?php
echo $objForma->htmlBotonSolo('cmdTablero', 'botonContinuar azul', 'irtablero()', 'Mis Cursos', 130);
?>
<div class="salto1px"></div>
</div>
<?php
echo $objForma->htmlFinMarco();
?>
</div><!-- /DIV_Sector12 -->

<div id="div_sector95" style="display:none">
<?php
echo $objForma->htmlInicioMarco();
echo '<div id="div_95cuerpo"></div>';
echo $objForma->htmlFinMarco();
?>
</div><!-- /DIV_Sector95 -->


<div id="div_sector96" style="display:none">
<input id="div96v1" name="div96v1" type="hidden" value="" />
<input id="div96v2" name="div96v2" type="hidden" value="" />
<input id="div96v3" name="div96v3" type="hidden" value="" />
<input id="div96campo" name="div96campo" type="hidden" value="" />
<input id="div96llave" name="div96llave" type="hidden" value="" />
<input id="titulo_2301" name="titulo_2301" type="hidden" value="<?php echo $ETI['titulo_2301']; ?>" />
<div class="titulos">
<div class="titulosD">
<input id="cmdAyuda96" name="cmdAyuda96" type="button" class="btSupAyuda" onclick="muestraayuda(<?php echo $iCodModulo; ?>);" title="<?php echo $ETI['bt_ayuda']; ?>" value="<?php echo $ETI['bt_ayuda']; ?>" />
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
<input id="cmdAyuda97" name="cmdAyuda97" type="button" class="btSupAyuda" onclick="muestraayuda(<?php echo $iCodModulo; ?>);" title="<?php echo $ETI['bt_ayuda']; ?>" value="<?php echo $ETI['bt_ayuda']; ?>" />
<input id="cmdVolverSec97" name="cmdVolverSec97" type="button" class="btSupVolver" onclick="retornacontrol();" title="<?php echo $ETI['bt_volver']; ?>" value="<?php echo $ETI['bt_volver']; ?>" />
</div>
<div class="titulosI" id="div_97titulo">
<?php
echo '<h2>' . $ETI['titulo_2301'] . '</h2>';
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
<input id="cmdAyuda98" name="cmdAyuda98" type="button" class="btSupAyuda" onclick="muestraayuda(<?php echo $iCodModulo; ?>);" title="<?php echo $ETI['bt_ayuda']; ?>" value="<?php echo $ETI['bt_ayuda']; ?>" />
</div>
<div class="titulosI">
<?php
echo '<h2>' . $ETI['titulo_2301'] . '</h2>';
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
	if ($bPuedeGuardar && $bEstudiante) {
?>
<div class="flotante">
<?php
echo $objForma->htmlBotonSolo('cmdGuardarf', 'btSoloGuardar', 'enviaguardar()', $ETI['bt_guardar']);
?>
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
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>js/chosen.css" type="text/css" />
<script language="javascript" src="<?php echo $APP->rutacomun; ?>js/chosen.jquery.js"></script>
<?php
if (!$bEstudiante) {
?>
<script language="javascript">
$().ready(function() {
<?php
if ($_REQUEST['paso'] == 0) {
?>
$("#cara01idperaca").chosen({width: "600px"});
<?php
}
?>
$("#bperiodo").chosen();
$("#bconvenio").chosen();
});
</script>
<?php
}
?>
<script language="javascript" src="ac_2301.js"></script>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>unad_todas2024v2.js"></script>
<?php
forma_piedepagina();
