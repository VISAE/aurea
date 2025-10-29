<?php
/*
--- © Juan David Avellaneda Molina - UNAD - 2025 ---
--- juand.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 3.0.15 miércoles, 30 de abril de 2025
*/

/** Archivo index.php.
 * Modulo 17 Index.
 * @author Juan David Avellaneda Molina - juand.avellaneda@unad.edu.co
 * @param debug = 1  (Opcional), bandera para indicar si se generan datos de depuración
 * @date miércoles, 30 de abril de 2025
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
require $APP->rutacomun . 'unad_forma_campus.php';
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
$iMinVerDB = 8727;
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
$bConMenu = true;
$et_menu = '';
$idTercero = $_SESSION['unad_id_tercero'];
$iPiel = iDefinirPiel($APP, 2);
$sAnchoExpandeContrae = ' style="width:62px;"';
$sOcultaConsec = ''; //' style="display:none;"';
list($sGrupoModulo, $sPaginaModulo) = f109_GrupoModulo($iCodModuloConsulta, $iConsecutivoMenu, $objDB);
$sOcultaId = ' style="display:none;"';
$sTituloApp = $APP->siglasistema; //f101_SiglaModulo($APP->idsistema, $objDB);
$sTituloModulo = $ETI['msg_accesit'];
//
$aRutas = array(
	array('./', 'Inicio'),
	// array('./' . $sPaginaModulo, $sGrupoModulo),
	array('', $sTituloModulo)
);
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
	// list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModuloConsulta, 1, $idTercero, $objDB);
	$bDevuelve = true;
	if (!$bDevuelve) {
		$bCerrado = true;
		$sMsgCierre = '<div class="MarquesinaGrande">No cuenta con permiso para acceder a este modulo [' . $iCodModuloConsulta . '].</div>';
	}
}
if ($bCerrado) {
	$objDB->CerrarConexion();
	forma_cabecera($xajax, $sTituloModulo, false);
	forma_mitad();
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
		header('Location:noticia.php?ret=encuestares.php');
		die();
	}
}
$seg_1707 = 0;
$bDevuelve = false;
list($bDevuelve, $sDebugP, $seg_1707) = seg_revisa_permisoV3($iCodModulo, 1707, $_SESSION['unad_id_tercero'], $objDB, $bDebug);
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
$idEntidad = Traer_Entidad();
// -- Si esta cargando la pagina por primer vez se revisa si requiere auditar y se manda a hacer un limpiar (paso -1)
if (isset($_REQUEST['paso']) == 0) {
	$_REQUEST['paso'] = 1;
	if ($audita[1]) {
		seg_auditaingreso($iCodModulo, $_SESSION['unad_id_tercero'], $objDB);
	}
}
// -- 2301 cara01encuesta
require $APP->rutacomun . 'lib2301.php';
$xajax = new xajax();
$xajax->configure('javascript URI', $APP->rutacomun . 'xajax/');
$xajax->register(XAJAX_FUNCTION, 'unad11_Mostrar_v2');
$xajax->register(XAJAX_FUNCTION, 'unad11_TraerXid');
$xajax->register(XAJAX_FUNCTION, 'sesion_abandona_V2');
$xajax->register(XAJAX_FUNCTION, 'sesion_mantenerV4');
$xajax->processRequest();
if ($bPeticionXAJAX) {
	die(); // Esto hace que las llamadas por xajax terminen aquí.
}
$sMensaje = '';
$sHTMLHistorial = '';
if (isset($_REQUEST['cara01idperaca']) == 0) {
	$_REQUEST['cara01idperaca'] = '';
	$sSQL = 'SELECT core01peracainicial FROM core01estprograma WHERE core01idtercero=' . $idTercero . ' ORDER BY core01peracainicial DESC';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$_REQUEST['cara01idperaca'] = $fila['core01peracainicial'];
		//Ver si ya tiene una caracterizacion.
		$sSQL = 'SELECT TB.cara01id, TB.cara01idperaca, TB.cara01completa, T2.exte02titulo 
		FROM cara01encuesta AS TB, exte02per_aca AS T2 
		WHERE TB.cara01idtercero=' . $idTercero . ' AND TB.cara01idperaca=T2.exte02id AND TB.cara01completa="S" AND TB.cara01tipocaracterizacion NOT IN (3,11)
		ORDER BY TB.cara01completa, TB.cara01idperaca DESC';
		$tabla = $objDB->ejecutasql($sSQL);
		$iNumFilas = $objDB->nf($tabla);
		if ($iNumFilas == 0) {
			$sMensaje = $ETI['msg_noresultados'];
		} else {
			$fila = $objDB->sf($tabla);
			$_REQUEST['cara01idperaca'] = $fila['cara01idperaca'];
		}
	} else {
		$sMensaje = $ETI['msg_noconsiderado'];
	}
}
$sError = '';
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
	$_REQUEST['boculta2301'] = 0;
}
if (isset($_REQUEST['paginaf2310']) == 0) {
	$_REQUEST['paginaf2310'] = 1;
}
if (isset($_REQUEST['lppf2310']) == 0) {
	$_REQUEST['lppf2310'] = 20;
}
if (isset($_REQUEST['bocultaResultados']) == 0) {
	$_REQUEST['bocultaResultados'] = 0;
}
// -- Inicializar variables de datos.
if (isset($_REQUEST['cara01idperaca']) == 0) {
	$_REQUEST['cara01idperaca'] = '';
}
if (isset($_REQUEST['cara01idtercero']) == 0) {
	$_REQUEST['cara01idtercero'] = $idTercero;
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
if (isset($_REQUEST['cara01idzona']) == 0) {
	$_REQUEST['cara01idzona'] = '';
}
if (isset($_REQUEST['cara01idcead']) == 0) {
	$_REQUEST['cara01idcead'] = '';
}
if (isset($_REQUEST['cara01matconvenio']) == 0) {
	$_REQUEST['cara01matconvenio'] = 'N';
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
if (isset($_REQUEST['cara01fechainicio']) == 0) {
	$_REQUEST['cara01fechainicio'] = '';
} //{fecha_hoy();}
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
if (isset($_REQUEST['cara01psico_puntaje']) == 0) {
	$_REQUEST['cara01psico_puntaje'] = 0;
}
if ((int)$_REQUEST['paso'] > 0) {
	//Preguntas de la prueba
}
// Espacio para inicializar otras variables
if (isset($_REQUEST['ficha']) == 0) {
	$_REQUEST['ficha'] = 1;
}
if ($_REQUEST['paso'] == -1) {
	$_REQUEST['paso'] = 3;
}
//2022 - 08 - 19 - Le armamos el historial
$sSQL = 'SELECT TB.cara01id, TB.cara01idperaca, TB.cara01completa, T2.exte02titulo 
FROM cara01encuesta AS TB, exte02per_aca AS T2 
WHERE TB.cara01idtercero=' . $idTercero . ' AND TB.cara01idperaca=T2.exte02id AND TB.cara01completa="S" AND TB.cara01tipocaracterizacion NOT IN (3,11)
ORDER BY TB.cara01idperaca DESC';
$tabla = $objDB->ejecutasql($sSQL);
$iNumFilas = $objDB->nf($tabla);
if ($iNumFilas > 1) {
	$sHTMLHistorial = '<div class="GrupoCamposAyuda">
	<b>Periodos diligenciados:</b>
	' . html_salto() . '
	<div class="tabuladores">';
	$bMarcaInicial = false;
	while ($fila = $objDB->sf($tabla)) {
		$sNomPeriodo = cadena_notildes($fila['exte02titulo']);
		$k = $fila['cara01idperaca'];
		if ($fila['cara01completa'] == 'S') {
			$sHTMLHistorial = $sHTMLHistorial . html_BotonVerde('p_' . $k, $sNomPeriodo, 'javascript:cargaridf2301(' . $fila['cara01id'] . ');', $sNomPeriodo);
		} else {
			$sHTMLHistorial = $sHTMLHistorial . html_BotonRojo('p_' . $k, $sNomPeriodo, 'javascript:cargaridf2301(' . $fila['cara01id'] . ');', $sNomPeriodo);
		}
	}
	$sHTMLHistorial = $sHTMLHistorial . html_salto().'</div></div>';
}
$bGrupo14 = false;
if ($_REQUEST['cara01idperaca'] > 2035) {
	$bGrupo14 = true;
}
//Cargar los campos
if (($_REQUEST['paso'] == 1) || ($_REQUEST['paso'] == 3)) {
	$_REQUEST['cara01idtercero_td'] = $APP->tipo_doc;
	$_REQUEST['cara01idtercero_doc'] = '';
	if ($_REQUEST['paso'] == 1) {
		$sSQLcondi = 'cara01idperaca=' . $_REQUEST['cara01idperaca'] . ' AND cara01idtercero="' . $_REQUEST['cara01idtercero'] . '"';
	} else {
		$sSQLcondi = 'cara01id=' . $_REQUEST['cara01id'] . '';
	}
	$sSQL = 'SELECT * FROM cara01encuesta WHERE ' . $sSQLcondi;
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
		$_REQUEST['cara01idzona'] = $fila['cara01idzona'];
		$_REQUEST['cara01idcead'] = $fila['cara01idcead'];
		$_REQUEST['cara01matconvenio'] = $fila['cara01matconvenio'];
		$_REQUEST['cara01niveldigital'] = $fila['cara01niveldigital'];
		$_REQUEST['cara01nivellectura'] = $fila['cara01nivellectura'];
		$_REQUEST['cara01nivelrazona'] = $fila['cara01nivelrazona'];
		$_REQUEST['cara01nivelingles'] = $fila['cara01nivelingles'];
		$_REQUEST['cara01fechainicio'] = $fila['cara01fechainicio'];
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
		$_REQUEST['cara01psico_puntaje'] = $fila['cara01psico_puntaje'];
		$bcargo = true;
		$_REQUEST['paso'] = 2;
		$_REQUEST['boculta2301'] = 0;
		$bLimpiaHijos = true;
	} else {
		$_REQUEST['paso'] = 0;
	}
}
function f2301_NombrePuntajeRes($sCompetencia, $iValor, $ETI)
{
	$sValor = '';
	$dPorcentaje = 0;
	$sEstilo = 'secondary';
	$sNota = '';
	$iPuntajeMax = 0;
	switch ($sCompetencia) {
		case 'puntaje':
			if ($iValor >= 24 && $iValor <= 30) {
				$sValor = 'Bajo';
			} else {
				if ($iValor >= 17 && $iValor <= 23) {
					$sValor = 'Medio';
				} else {
					if ($iValor >= 10 && $iValor <= 16) {
						$sValor = 'Alto';
					} else {
						$sValor = 'Sin definir';
					}
				}
			}			
			break;
		case 'lectura':
			if ($iValor >= 0 && $iValor <= 40) {
				$sValor = 'Bajo';
				$sEstilo = 'danger';
				$sNota = $ETI[$sCompetencia.'_bajo'];
			} else {
				if ($iValor >= 50 && $iValor <= 90) {
					$sValor = 'Medio';
					$sEstilo = 'info';
					$sNota = $ETI[$sCompetencia.'_medio'];
				} else {
					if ($iValor >= 100 && $iValor <= 150) {
						$sValor = 'Alto';
						$sEstilo = 'success';
						$sNota = $ETI[$sCompetencia.'_alto'];
					} else {
						$sValor = 'Sin definir';
					}
				}
			}
			if ($iValor>=0) {
				$iPuntajeMax = 150;
				$dPorcentaje = round(($iValor / $iPuntajeMax)*100,2);
			}
			break;
		case 'digital':
		case 'ingles':
			if ($iValor >= 0 && $iValor <= 40) {
				$sValor = 'Bajo';
				$sEstilo = 'danger';
				$sNota = $ETI[$sCompetencia.'_bajo'];
			} else {
				if ($iValor >= 50 && $iValor <= 80) {
					$sValor = 'Medio';
					$sEstilo = 'info';
					$sNota = $ETI[$sCompetencia.'_medio'];
				} else {
					if ($iValor >= 90 && $iValor <= 120) {
						$sValor = 'Alto';
						$sEstilo = 'success';
						$sNota = $ETI[$sCompetencia.'_alto'];
					} else {
						$sValor = 'Sin definir';
					}
				}
			}
			if ($iValor>=0) {
				$iPuntajeMax = 120;
				$dPorcentaje = round(($iValor / $iPuntajeMax)*100,2);
			}
			break;
		case 'razona':
		case 'biolog':
		case 'fisica':
		case 'quimica':
		case 'ciudadanas':
			if ($iValor >= 0 && $iValor <= 30) {
				$sValor = 'Bajo';
				$sEstilo = 'danger';
				$sNota = $ETI[$sCompetencia.'_bajo'];
			} else {
				if ($iValor >= 40 && $iValor <= 60) {
					$sValor = 'Medio';
					$sEstilo = 'info';
					$sNota = $ETI[$sCompetencia.'_medio'];
				} else {
					if ($iValor >= 70 && $iValor <= 100) {
						$sValor = 'Alto';
						$sEstilo = 'success';
						$sNota = $ETI[$sCompetencia.'_alto'];
					} else {
						$sValor = 'Sin definir';
					}
				}
			}
			if ($iValor>0) {
				$iPuntajeMax = 100;
				$dPorcentaje = $iValor;
			}
			break;
	}
	return array($sValor, $sEstilo, $dPorcentaje, $sNota, $iPuntajeMax);
}
if ($bDebug) {
	$sDebug = $sDebug . fecha_microtiempo() . ' Corriendo el paso ' . $_REQUEST['paso'] . ' antes de guardar<br>';
}
//Verificar los datos de la matricula .. Febrero 24 de 2022
if ($_REQUEST['paso'] == 31) {
	$_REQUEST['paso'] = -1;
	$bMueveScroll = true;
	$iProcesados = 0;
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
	$_REQUEST['cara01idtercero'] = 0;
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
	$_REQUEST['cara01idzona'] = '';
	$_REQUEST['cara01idcead'] = '';
	$_REQUEST['cara01matconvenio'] = 'N';
	$_REQUEST['cara01niveldigital'] = '';
	$_REQUEST['cara01nivellectura'] = '';
	$_REQUEST['cara01nivelrazona'] = '';
	$_REQUEST['cara01nivelingles'] = '';
	$_REQUEST['cara01fechainicio'] = ''; //fecha_hoy();
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
	$_REQUEST['cara01psico_puntaje'] = 0;
	$_REQUEST['paso'] = 0;
}
if ($bLimpiaHijos) {
}
//AQUI SE DEBEN CARGAR TODOS LOS DATOS QUE LA FORMA NECESITE.
$bPuedeGuardar = false;
$bConEliminar = false;
$bHayImprimir = false;
$bAntiguo = false;
if ($_REQUEST['cara01tipocaracterizacion'] == 3) {
	$bAntiguo = true;
}
//Permisos adicionales
$seg_5 = 0;
$seg_6 = 0;
$seg_8 = 0;
/*
list($bHayImprimir, $sDebugP, $seg_6) = seg_revisa_permisoV3($iCodModulo, 6, $idTercero, $objDB);
*/
if ((int)$_REQUEST['paso'] != 0) {
	//list($bHayImprimir2, $sDebugP, $seg_5) = seg_revisa_permisoV3($iCodModulo, 5, $idTercero, $objDB);
	//list($bDevuelve, $sDebugP, $seg_8) = seg_revisa_permisoV3($iCodModulo, 8, $idTercero, $objDB);
}
//DATOS PARA COMPLETAR EL FORMULARIO
$iAgnoIni = 2000;
$iAgno = fecha_agno();
$iAgnoFin = $iAgno + 5;
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
if ((int)$_REQUEST['paso'] == 0) {	
	$idTerceroFuncion = $idTercero;
	$html_cara01idperaca = f2301_HTMLComboV2_cara01idperaca($objDB, $objCombos, $_REQUEST['cara01idperaca'], $idTerceroFuncion);
} else {
	list($cara01idperaca_nombre, $sErrorDet) = tabla_campoxid('exte02per_aca', 'exte02nombre', 'exte02id', $_REQUEST['cara01idperaca'], '{' . $ETI['msg_sindato'] . '}', $objDB);
	$html_cara01idperaca = html_oculto('cara01idperaca', $_REQUEST['cara01idperaca'], $cara01idperaca_nombre);
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
//Cargar las tablas de datos
// Menu
list($et_menu, $sDebugM) = html_menuCampus2025($objDB, $bDebugMenu, $idTercero);
$sDebug = $sDebug . $sDebugM;
$objDB->CerrarConexion();
//FORMA
forma_cabecera($xajax, ['tituloModulo' => $sTituloModulo]);
echo $et_menu;
forma_mitad($objForma, ['tituloModulo' => $sTituloModulo, 'rutas' => $aRutas]);
?>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>js/jquery.qtip.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.0.1/css/font-awesome.min.css">
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>css/botones_resultado.css?v=3">
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>js/jquery.qtip.min.css">
<script language="javascript">
	function expandesector(codigo) {
		document.getElementById('div_sector1').style.display = 'none';
		document.getElementById('div_sector98').style.display = 'none';
		document.getElementById('div_sector' + codigo).style.display = 'block';
	}

	function ter_retorna() {
		var sRetorna = window.document.frmedita.div96v2.value;
		if (sRetorna != '') {
			var idcampo = window.document.frmedita.div96campo.value;
			var illave = window.document.frmedita.div96llave.value;
			var did = document.getElementById(idcampo);
			var dtd = document.getElementById(idcampo + '_td');
			var ddoc = document.getElementById(idcampo + '_doc');
			dtd.value = window.document.frmedita.div96v1.value;
			ddoc.value = sRetorna;
			did.value = window.document.frmedita.div96v3.value;
			ter_muestra(idcampo, illave);
		}
		MensajeAlarmaV2('', 0);
		retornacontrol();
	}

	function ter_muestra(idcampo, illave) {
		var params = new Array();
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
		var params = new Array();
		params[0] = vrcampo;
		params[1] = idcampo;
		if (params[0] != 0) {
			if (idcampo == 'cara01idtercero') {
				params[4] = 'RevisaLlave';
			}
			xajax_unad11_TraerXid(params);
		}
	}

	function cargaridf2301(llave1) {
		window.document.frmedita.cara01id.value = String(llave1);
		window.document.frmedita.paso.value = 3;
		window.document.frmedita.submit();
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

	function mantener_sesion() {
		xajax_sesion_mantenerV4();
	}
	setInterval('xajax_sesion_abandona_V2();', 60000);

	function AyudaLocal(sCampo) {
		let divAyuda = document.getElementById('div_ayuda_' + sCampo);
		if (typeof divAyuda === 'undefined') {} else {
			verboton('cmdAyuda_' + sCampo, 'none');
			let sMensaje = 'Lo que quiera decir.';
			//if (sCampo == 'sNombreCampo') {
			//sMensaje = 'Mensaje para otro campo.';
			//}
			divAyuda.innerHTML = sMensaje;
			divAyuda.style.display = 'block';
		}
	}

</script>
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
<input id="deb_tipodoc" name="deb_tipodoc" type="hidden" value="<?php echo $_REQUEST['deb_tipodoc']; ?>" />
<input id="deb_doc" name="deb_doc" type="hidden" value="<?php echo $_REQUEST['deb_doc']; ?>" />
<div id="div_sector1" class="sector">
<div class="areaform">
<div class="areatrabajo">
<?php
if ($sMensaje != '') {
?>
<div class="GrupoCamposAyuda">
<div class="MarquesinaGrande">
<?php echo $sMensaje; ?>
</div>
</div>
<?php
} else {
echo $sHTMLHistorial;
//Mostrar formulario para editar
?>
<label class="Label130">
<?php
echo $ETI['cara01idperaca'];
?>
</label>
<label class="Label600">
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
$bOculto = true;
echo html_DivTerceroV2('cara01idtercero', $_REQUEST['cara01idtercero_td'], $_REQUEST['cara01idtercero_doc'], $bOculto, 1, $ETI['ing_doc']);
?>
</div>
<div class="salto1px"></div>
<div id="div_cara01idtercero" class="L"><?php echo $cara01idtercero_rs; ?></div>
<div class="salto1px"></div>
</div>

<div class="GrupoCampos450">
<label class="Label60">
<?php
echo $ETI['cara01id'];
?>
</label>
<label class="Label60">
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
if ($_REQUEST['paso'] == 2) {
	if ($_REQUEST['cara01completa'] == 'S') {
		$_REQUEST['bocultaResultados'] = 0;
?>
<?php
// Inicia Indicadores resultados
?>
<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo $ETI['titulo_2301'];
?>
</label>
<input id="bocultaResultados" name="bocultaResultados" type="hidden" value="<?php echo $_REQUEST['bocultaResultados']; ?>" />
<div class="ir_derecha" style="width:62px;">
<label class="Label30">
<input id="btexpandeResultados" name="btexpandeResultados" type="button" value="Expandir" class="btMiniExpandir" onclick="expandepanel('Resultados','block',0);" title="<?php echo $ETI['bt_mostrar']; ?>" style="display:<?php if ($_REQUEST['bocultaResultados'] == 0) {
echo 'none';
} else {
echo 'block';
} ?>;" />
</label>
<label class="Label30">
<input id="btrecogeResultados" name="btrecogeResultados" type="button" value="Recoger" class="btMiniRecoger" onclick="expandepanel('Resultados','none',1);" title="<?php echo $ETI['bt_ocultar']; ?>" style="display:<?php if ($_REQUEST['bocultaResultados'] == 0) {
echo 'block';
} else {
echo 'none';
} ?>;" />
</label>
</div>
<div class="salto1px"></div>


<!-- //! TODO: Aqui comienza -->


<div id="div_pResultados" style="display:<?php if ($_REQUEST['bocultaResultados'] == 0) {echo 'block';} else {echo 'none';} ?>;">
<?php
$bInvitaPAPC = false;
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
list($sValor, $sEstilo, $dPorcentaje, $sNota, $iPuntajeMax)=f2301_NombrePuntajeRes('puntaje', $_REQUEST['cara01psico_puntaje'], $ETI);
echo html_oculto('cara01psico_puntaje', $_REQUEST['cara01psico_puntaje'], $sValor);
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
<b>
<?php
echo $ETI['cara01fichapsico'];
?>
</b>
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
$bInvitaPAPC = true;
?>
<div class="salto1px"></div>



<div class="GrupoCampos450">
<div style="min-width:400px">
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
list($sValor, $sEstilo, $dPorcentaje, $sNota, $iPuntajeMax)=f2301_NombrePuntajeRes('digital', $_REQUEST['cara01niveldigital'], $ETI);
echo html_oculto('cara01niveldigital', $_REQUEST['cara01niveldigital'], $sValor);
?>
</label>
<div class="salto1px"></div>
<div class="progress" id="div_cara01fichadigital">
<div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="<?php echo $dPorcentaje; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $dPorcentaje; ?>%"><?php echo $dPorcentaje; ?>%</div>
</div>
</div>
<script>
$('#div_cara01fichadigital').qtip({
	content: '<div class="card text-white bg-<?php echo $sEstilo; ?> mb-3"><div class="card-header"><?php echo $ETI['msg_puntaje'].': '.$_REQUEST['cara01niveldigital'].'/'.$iPuntajeMax; ?></div><div class="card-body"><h5 class="card-title"><?php echo $ETI['msg_nivel'].': <b>'.$sValor.'</b>'; ?></h5><p class="card-text"><?php echo $sNota; ?></p></div></div>',
	position: {
      viewport: true,
      my: 'bottom center',
      at: 'top center',
      target: 'mouse',
    },
    style: {
      classes: 'qtip-dark qtip-personaliza'
    }
});
</script>
<div class="salto5px"></div>
</div>



<?php
} else {
?>
<input id="cara01niveldigital" name="cara01niveldigital" type="hidden" value="<?php echo $_REQUEST['cara01niveldigital']; ?>" />
<?php
}
if ($_REQUEST['cara01fichalectura'] != -1){
$bInvitaPAPC = true;
?>

<div class="GrupoCampos450">
<div style="min-width:400px">
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
list($sValor, $sEstilo, $dPorcentaje, $sNota, $iPuntajeMax)=f2301_NombrePuntajeRes('lectura', $_REQUEST['cara01nivellectura'], $ETI);
echo html_oculto('cara01nivellectura', $_REQUEST['cara01nivellectura'], $sValor);
?>
</label>
<div class="salto1px"></div>
<div class="progress" id="div_cara01fichalectura">
<div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="<?php echo $dPorcentaje; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $dPorcentaje; ?>%"><?php echo $dPorcentaje; ?>%</div>
</div>
</div>
<script>
$('#div_cara01fichalectura').qtip({
	content: '<div class="card text-white bg-<?php echo $sEstilo; ?> mb-3"><div class="card-header"><?php echo $ETI['msg_puntaje'].': '.$_REQUEST['cara01nivellectura'].'/'.$iPuntajeMax; ?></div><div class="card-body"><h5 class="card-title"><?php echo $ETI['msg_nivel'].': <b>'.$sValor.'</b>'; ?></h5><p class="card-text"><?php echo $sNota; ?></p></div></div>',
	position: {
      viewport: true,
      my: 'bottom center',
      at: 'top center',
      target: 'mouse',
    },
    style: {
      classes: 'qtip-dark qtip-personaliza'
    }
});
</script>
<div class="salto5px"></div>
</div>

<?php
} else {
?>
<input id="cara01nivellectura" name="cara01nivellectura" type="hidden" value="<?php echo $_REQUEST['cara01nivellectura']; ?>" />
<?php
}
if ($_REQUEST['cara01ficharazona'] != -1){
$bInvitaPAPC = true;
?>
<div class="salto1px"></div>

<div class="GrupoCampos450">
<div style="min-width:400px">
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
list($sValor, $sEstilo, $dPorcentaje, $sNota, $iPuntajeMax)=f2301_NombrePuntajeRes('razona', $_REQUEST['cara01nivelrazona'], $ETI);
echo html_oculto('cara01nivelrazona', $_REQUEST['cara01nivelrazona'], $sValor);
?>
</label>
<div class="salto1px"></div>
<div class="progress" id="div_cara01ficharazona">
<div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="<?php echo $dPorcentaje; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $dPorcentaje; ?>%"><?php echo $dPorcentaje; ?>%</div>
</div>
</div>
<script>
$('#div_cara01ficharazona').qtip({
	content: '<div class="card text-white bg-<?php echo $sEstilo; ?> mb-3"><div class="card-header"><?php echo $ETI['msg_puntaje'].': '.$_REQUEST['cara01nivelrazona'].'/'.$iPuntajeMax; ?></div><div class="card-body"><h5 class="card-title"><?php echo $ETI['msg_nivel'].': <b>'.$sValor.'</b>'; ?></h5><p class="card-text"><?php echo $sNota; ?></p></div></div>',
	position: {
      viewport: true,
      my: 'bottom center',
      at: 'top center',
      target: 'mouse',
    },
    style: {
      classes: 'qtip-dark qtip-personaliza'
    }
});
</script>
<div class="salto5px"></div>
</div>

<?php
} else {
?>
<input id="cara01nivelrazona" name="cara01nivelrazona" type="hidden" value="<?php echo $_REQUEST['cara01nivelrazona']; ?>" />
<?php
}
if ($_REQUEST['cara01fichaingles'] != -1){
$bInvitaPAPC = true;
?>

<div class="GrupoCampos450">
<div style="min-width:400px">
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
list($sValor, $sEstilo, $dPorcentaje, $sNota, $iPuntajeMax)=f2301_NombrePuntajeRes('ingles', $_REQUEST['cara01nivelingles'], $ETI);
echo html_oculto('cara01nivelingles', $_REQUEST['cara01nivelingles'], $sValor);
?>
</label>
<div class="salto1px"></div>
<div class="progress" id="div_cara01fichaingles">
<div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="<?php echo $dPorcentaje; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $dPorcentaje; ?>%"><?php echo $dPorcentaje; ?>%</div>
</div>
</div>
<script>
$('#div_cara01fichaingles').qtip({
	content: '<div class="card text-white bg-<?php echo $sEstilo; ?> mb-3"><div class="card-header"><?php echo $ETI['msg_puntaje'].': '.$_REQUEST['cara01nivelingles'].'/'.$iPuntajeMax; ?></div><div class="card-body"><h5 class="card-title"><?php echo $ETI['msg_nivel'].': <b>'.$sValor.'</b>'; ?></h5><p class="card-text"><?php echo $sNota; ?></p></div></div>',
	position: {
      viewport: true,
      my: 'bottom center',
      at: 'top center',
      target: 'mouse',
    },
    style: {
      classes: 'qtip-dark qtip-personaliza'
    }
});
</script>
<div class="salto5px"></div>
</div>

<?php
} else {
?>
<input id="cara01nivelingles" name="cara01nivelingles" type="hidden" value="<?php echo $_REQUEST['cara01nivelingles']; ?>" />
<?php
}
if ($_REQUEST['cara01fichabiolog'] != -1){
$bInvitaPAPC = true;
?>
<div class="salto1px"></div>

<div class="GrupoCampos450">
<div style="min-width:400px">
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
list($sValor, $sEstilo, $dPorcentaje, $sNota, $iPuntajeMax)=f2301_NombrePuntajeRes('biolog', $_REQUEST['cara01nivelbiolog'], $ETI);
echo html_oculto('cara01nivelbiolog', $_REQUEST['cara01nivelbiolog'], $sValor);
?>
</label>
<div class="salto1px"></div>
<div class="progress" id="div_cara01fichabiolog">
<div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="<?php echo $dPorcentaje; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $dPorcentaje; ?>%"><?php echo $dPorcentaje; ?>%</div>
</div>
</div>
<script>
$('#div_cara01fichabiolog').qtip({
	content: '<div class="card text-white bg-<?php echo $sEstilo; ?> mb-3"><div class="card-header"><?php echo $ETI['msg_puntaje'].': '.$_REQUEST['cara01nivelbiolog'].'/'.$iPuntajeMax; ?></div><div class="card-body"><h5 class="card-title"><?php echo $ETI['msg_nivel'].': <b>'.$sValor.'</b>'; ?></h5><p class="card-text"><?php echo $sNota; ?></p></div></div>',
	position: {
      viewport: true,
      my: 'bottom center',
      at: 'top center',
      target: 'mouse',
    },
    style: {
      classes: 'qtip-dark qtip-personaliza'
    }
});
</script>
<div class="salto5px"></div>
</div>

<?php
} else {
?>
<input id="cara01nivelbiolog" name="cara01nivelbiolog" type="hidden" value="<?php echo $_REQUEST['cara01nivelbiolog']; ?>" />
<?php
}
if ($_REQUEST['cara01fichafisica'] != -1){
$bInvitaPAPC = true;
?>

<div class="GrupoCampos450">
<div style="min-width:400px">
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
list($sValor, $sEstilo, $dPorcentaje, $sNota, $iPuntajeMax)=f2301_NombrePuntajeRes('fisica', $_REQUEST['cara01nivelfisica'], $ETI);
echo html_oculto('cara01nivelfisica', $_REQUEST['cara01nivelfisica'], $sValor);
?>
</label>
<div class="salto1px"></div>
<div class="progress" id="div_cara01fichafisica">
<div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="<?php echo $dPorcentaje; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $dPorcentaje; ?>%"><?php echo $dPorcentaje; ?>%</div>
</div>
</div>
<script>
$('#div_cara01fichafisica').qtip({
	content: '<div class="card text-white bg-<?php echo $sEstilo; ?> mb-3"><div class="card-header"><?php echo $ETI['msg_puntaje'].': '.$_REQUEST['cara01nivelfisica'].'/'.$iPuntajeMax; ?></div><div class="card-body"><h5 class="card-title"><?php echo $ETI['msg_nivel'].': <b>'.$sValor.'</b>'; ?></h5><p class="card-text"><?php echo $sNota; ?></p></div></div>',
	position: {
      viewport: true,
      my: 'bottom center',
      at: 'top center',
      target: 'mouse',
    },
    style: {
      classes: 'qtip-dark qtip-personaliza'
    }
});
</script>
<div class="salto5px"></div>
</div>

<?php
} else {
?>
<input id="cara01nivelfisica" name="cara01nivelfisica" type="hidden" value="<?php echo $_REQUEST['cara01nivelfisica']; ?>" />
<?php
}
if ($_REQUEST['cara01fichaquimica'] != -1){
$bInvitaPAPC = true;
?>
<div class="salto1px"></div>

<div class="GrupoCampos450">
<div style="min-width:400px">
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
list($sValor, $sEstilo, $dPorcentaje, $sNota, $iPuntajeMax)=f2301_NombrePuntajeRes('quimica', $_REQUEST['cara01nivelquimica'], $ETI);
echo html_oculto('cara01nivelquimica', $_REQUEST['cara01nivelquimica'], $sValor);
?>
</label>
<div class="salto1px"></div>
<div class="progress" id="div_cara01fichaquimica">
<div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="<?php echo $dPorcentaje; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $dPorcentaje; ?>%"><?php echo $dPorcentaje; ?>%</div>
</div>
</div>
<script>
$('#div_cara01fichaquimica').qtip({
	content: '<div class="card text-white bg-<?php echo $sEstilo; ?> mb-3"><div class="card-header"><?php echo $ETI['msg_puntaje'].': '.$_REQUEST['cara01nivelquimica'].'/'.$iPuntajeMax; ?></div><div class="card-body"><h5 class="card-title"><?php echo $ETI['msg_nivel'].': <b>'.$sValor.'</b>'; ?></h5><p class="card-text"><?php echo $sNota; ?></p></div></div>',
	position: {
      viewport: true,
      my: 'bottom center',
      at: 'top center',
      target: 'mouse',
    },
    style: {
      classes: 'qtip-dark qtip-personaliza'
    }
});
</script>
<div class="salto5px"></div>
</div>

<?php
} else {
?>
<input id="cara01nivelquimica" name="cara01nivelquimica" type="hidden" value="<?php echo $_REQUEST['cara01nivelquimica']; ?>" />
<?php
}
if (($bGrupo14) && ($_REQUEST['cara01fichaciudad'] != -1)) {
$bInvitaPAPC = true;
?>
<div class="salto1px"></div>

<div class="GrupoCampos450">
<div style="min-width:400px">
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
list($sValor, $sEstilo, $dPorcentaje, $sNota, $iPuntajeMax)=f2301_NombrePuntajeRes('ciudadanas', $_REQUEST['cara01nivelciudad'], $ETI);
echo html_oculto('cara01nivelciudad', $_REQUEST['cara01nivelciudad'], $sValor);
?>
</label>
<div class="salto1px"></div>
<div class="progress" id="div_cara01fichaciudad">
<div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="<?php echo $dPorcentaje; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $dPorcentaje; ?>%"><?php echo $dPorcentaje; ?>%</div>
</div>
</div>
<script>
$('#div_cara01fichaciudad').qtip({
	content: '<div class="card text-white bg-<?php echo $sEstilo; ?> mb-3"><div class="card-header"><?php echo $ETI['msg_puntaje'].': '.$_REQUEST['cara01nivelquimica'].'/'.$iPuntajeMax; ?></div><div class="card-body"><h5 class="card-title"><?php echo $ETI['msg_nivel'].': <b>'.$sValor.'</b>'; ?></h5><p class="card-text"><?php echo $sNota; ?></p></div></div>',
	position: {
      viewport: true,
      my: 'bottom center',
      at: 'top center',
      target: 'mouse',
    },
    style: {
      classes: 'qtip-dark qtip-personaliza'
    }
});
</script>
<div class="salto5px"></div>
</div>

<?php
} else {
?>
<input id="cara01nivelciudad" name="cara01nivelciudad" type="hidden" value="<?php echo $_REQUEST['cara01nivelciudad']; ?>" />
<?php
}
?>
<?php
if ($bInvitaPAPC) {
?>
<div class="salto1px"></div>
<div class="GrupoCampos700">
<img src="./img/banner_encuestares.png" alt="<?php echo $ETI['bt_invita_papc']; ?>" title="<?php echo $ETI['bt_invita_papc']; ?>" style="width:100%;" />
</div>
<?php
}
?>
<div class="salto5px"></div>
</div>
<?php
// Fin indicadores resultados
?>
</div>
<?php
// -- Termina Grupo campos Resultados
?>
<?php 
} else {
?>
<div class="salto5px"></div>
<div class="GrupoCamposAyuda">
<div class="MarquesinaMedia">
<?php echo $ETI['msg_encuestaabierta']; ?>
</div>
</div>
<?php
}
}
}
?>

<?php
// Termina el div_areatrabajo y DIV_areaform
?>

</div>
</div>
</div>


<div id="div_sector98" class="sector" style="display:none">
<div class="main__body">
<div class="flex gap-2 align-items-center">
<span class="spinner spinner--primary" aria-hidden="true"></span><p><?php echo $ETI['msg_espere']; ?></p>
</div>
</div>
</div>


<?php
if ($sDebug != '') {
	$iSegFin = microtime(true);
	if (isset($iSegIni) == 0) {
		$iSegIni = $iSegFin;
	}
	$iSegundos = $iSegFin - $iSegIni;
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
<script language="javascript">
$().ready(function() {
$(".progress").css("height","30px");
});
</script>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>unad_todas2025.js"></script>
<?php
forma_piedepagina();

