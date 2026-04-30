<?php
/*
--- © Omar Augusto Bautista - UNAD - 2026 ---
--- omar.bautista@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 3.1.5 viernes, 27 de febrero de 2026
*/
/** Archivo visaepruebasres.php.
 * Modulo 2945 visa45convpruebares.
 * @author Omar Augusto Bautista - omar.bautista@unad.edu.co
 * @param debug = 1  (Opcional), bandera para indicar si se generan datos de depuración
 * @date viernes, 27 de febrero de 2026
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
$iMinVerDB = 9359;
$iCodModulo = 2945;
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
/*
$mensajes_2900 = $APP->rutacomun . 'lg/lg_2900_' . $sIdioma . '.php';
if (!file_exists($mensajes_2900)) {
	$mensajes_2900 = $APP->rutacomun . 'lg/lg_2900_es.php';
}
require $mensajes_2900;
*/
$mensajes_2945 = $APP->rutacomun . 'lg/lg_2945_' . $sIdioma . '.php';
if (!file_exists($mensajes_2945)) {
	$mensajes_2945 = $APP->rutacomun . 'lg/lg_2945_es.php';
}
require $mensajes_todas;
require $mensajes_2945;
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
$sTituloModulo = $ETI['titulo_2945'];
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
		echo console_debug($sDebug);
	}
	echo $objForma->htmlFinMarco();
	forma_piedepagina();
	die();
}
if (!$bPeticionXAJAX) {
	if (noticias_pendientes($objDB)) {
		$objDB->CerrarConexion();
		header('Location:noticia.php?ret=visaepruebasres.php');
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
// -- 2945 visa45convpruebares
require $APP->rutacomun . 'lib2945.php';
$xajax = new xajax();
$xajax->configure('javascript URI', $APP->rutacomun . 'xajax/');
$xajax->register(XAJAX_FUNCTION, 'sesion_abandona_V2');
$xajax->register(XAJAX_FUNCTION, 'sesion_mantenerV4');
$xajax->register(XAJAX_FUNCTION, 'f2945_HtmlTabla');
$xajax->register(XAJAX_FUNCTION, 'f2945_ExisteDato');
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
if (isset($_REQUEST['paginaf2945']) == 0) {
	$_REQUEST['paginaf2945'] = 1;
}
if (isset($_REQUEST['lppf2945']) == 0) {
	$_REQUEST['lppf2945'] = 20;
}
if (isset($_REQUEST['boculta2945']) == 0) {
	$_REQUEST['boculta2945'] = 0;
}
// -- Inicializar variables de datos.
if (isset($_REQUEST['visa45idinscripcion']) == 0) {
	$_REQUEST['visa45idinscripcion'] = '';
}
if (isset($_REQUEST['visa45idprueba']) == 0) {
	$_REQUEST['visa45idprueba'] = '';
}
if (isset($_REQUEST['visa45id']) == 0) {
	$_REQUEST['visa45id'] = '';
}
if (isset($_REQUEST['visa45puntaje']) == 0) {
	$_REQUEST['visa45puntaje'] = '';
}
if (isset($_REQUEST['visa45idconvocatoria']) == 0) {
	$_REQUEST['visa45idconvocatoria'] = '';
}
if (isset($_REQUEST['visa45fechainsc']) == 0) {
	$_REQUEST['visa45fechainsc'] = '';
}
if (isset($_REQUEST['visa45puntajemaximo']) == 0) {
	$_REQUEST['visa45puntajemaximo'] = '';
}
if (isset($_REQUEST['visa45puntajeaproba']) == 0) {
	$_REQUEST['visa45puntajeaproba'] = '';
}
if (isset($_REQUEST['visa45idtercero_td']) == 0) {
	$_REQUEST['visa45idtercero_td'] = '';
}
if (isset($_REQUEST['visa45idtercero_doc']) == 0) {
	$_REQUEST['visa45idtercero_doc'] = '';
}
if (isset($_REQUEST['visa45idtercero']) == 0) {
	$_REQUEST['visa45idtercero'] = '';
}
if (isset($_REQUEST['visa45idperiodo']) == 0) {
	$_REQUEST['visa45idperiodo'] = '';
}
if (isset($_REQUEST['visa45idescuela']) == 0) {
	$_REQUEST['visa45idescuela'] = '';
}
if (isset($_REQUEST['visa45idprograma']) == 0) {
	$_REQUEST['visa45idprograma'] = '';
}
if (isset($_REQUEST['visa45idzona']) == 0) {
	$_REQUEST['visa45idzona'] = '';
}
if (isset($_REQUEST['visa45idcentro']) == 0) {
	$_REQUEST['visa45idcentro'] = '';
}
$_REQUEST['visa45idinscripcion'] = numeros_validar($_REQUEST['visa45idinscripcion']);
$_REQUEST['visa45idprueba'] = numeros_validar($_REQUEST['visa45idprueba']);
$_REQUEST['visa45id'] = numeros_validar($_REQUEST['visa45id']);
$_REQUEST['visa45puntaje'] = numeros_validar($_REQUEST['visa45puntaje']);
$_REQUEST['visa45idconvocatoria'] = numeros_validar($_REQUEST['visa45idconvocatoria']);
$_REQUEST['visa45fechainsc'] = cadena_Validar($_REQUEST['visa45fechainsc']);
$_REQUEST['visa45puntajemaximo'] = numeros_validar($_REQUEST['visa45puntajemaximo']);
$_REQUEST['visa45puntajeaproba'] = numeros_validar($_REQUEST['visa45puntajeaproba']);
$_REQUEST['visa45idtercero_td'] = cadena_Validar($_REQUEST['visa45idtercero_td']);
$_REQUEST['visa45idtercero_doc'] = cadena_Validar($_REQUEST['visa45idtercero_doc']);
$_REQUEST['visa45idtercero'] = numeros_validar($_REQUEST['visa45idtercero']);
$_REQUEST['visa45idperiodo'] = numeros_validar($_REQUEST['visa45idperiodo']);
$_REQUEST['visa45idescuela'] = numeros_validar($_REQUEST['visa45idescuela']);
$_REQUEST['visa45idprograma'] = numeros_validar($_REQUEST['visa45idprograma']);
$_REQUEST['visa45idzona'] = numeros_validar($_REQUEST['visa45idzona']);
$_REQUEST['visa45idcentro'] = numeros_validar($_REQUEST['visa45idcentro']);
// Espacio para inicializar otras variables
if (isset($_REQUEST['csv_separa']) == 0) {
	$_REQUEST['csv_separa'] = ';';
}
if (isset($_REQUEST['bdocumento']) == 0) {
	$_REQUEST['bdocumento'] = '';
}
if (isset($_REQUEST['bnombre']) == 0) {
	$_REQUEST['bnombre'] = '';
}
if (isset($_REQUEST['bresultado']) == 0) {
	$_REQUEST['bresultado'] = '';
}
/*
if (isset($_REQUEST['blistar']) == 0) {
	$_REQUEST['blistar'] = '';
}
*/
$_REQUEST['bdocumento'] = cadena_Validar($_REQUEST['bdocumento']);
$_REQUEST['bnombre'] = cadena_Validar($_REQUEST['bnombre']);
$_REQUEST['bresultado'] = numeros_validar($_REQUEST['bresultado']);
//Si Modifica o Elimina Cargar los campos
if (($_REQUEST['paso'] == 1) || ($_REQUEST['paso'] == 3)) {
	if ($_REQUEST['paso'] == 1) {
		$sSQLcondi = 'AND TB.visa40id=' . $_REQUEST['visa45idinscripcion'] . ' AND T2.visa38id=' . $_REQUEST['visa45idprueba'] . '';
	} else {
		$sSQLcondi = 'AND T3.visa45id=' . $_REQUEST['visa45id'] . '';
	}
	$sSQL = 'SELECT TB.visa40id, TB.visa40idconvocatoria, T2.visa38id, TB.visa40fechainsc, T3.visa45puntaje, 
	T2.visa38puntajemaximo, T2.visa38puntajeaproba, T3.visa45id, TB.visa40idperiodo, TB.visa40idescuela, 
	TB.visa40idprograma, TB.visa40idzona, TB.visa40idcentro, TB.visa40idtercero 
	FROM visa40inscripcion AS TB JOIN visa35convocatoria AS T1 ON TB.visa40idconvocatoria=T1.visa35id JOIN visa38convpruebas AS T2 ON T1.visa35idtipo=T2.visa38idtipo 
	LEFT JOIN visa45convpruebares AS T3 ON T2.visa38id=T3.visa45idprueba AND TB.visa40id=T3.visa45idinscripcion
	WHERE TB.visa40idconvocatoria=T1.visa35id AND T1.visa35idtipo=T2.visa38idtipo ' . $sSQLcondi . '';
	if ($bDebug) {
		$sDebug = $sDebug . log_debug('Consulta SQL: ' . $sSQL . '');
	}
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$_REQUEST['visa45idinscripcion'] = $fila['visa40id'];
		$_REQUEST['visa45idprueba'] = $fila['visa38id'];
		$_REQUEST['visa45id'] = $fila['visa45id'];
		$_REQUEST['visa45puntaje'] = $fila['visa45puntaje'];
		$_REQUEST['visa45idconvocatoria'] = $fila['visa40idconvocatoria'];
		$_REQUEST['visa45fechainsc'] = $fila['visa40fechainsc'];
		$_REQUEST['visa45puntajemaximo'] = $fila['visa38puntajemaximo'];
		$_REQUEST['visa45puntajeaproba'] = $fila['visa38puntajeaproba'];
		$_REQUEST['visa45idtercero'] = $fila['visa40idtercero'];
		$_REQUEST['visa45idperiodo'] = $fila['visa40idperiodo'];
		$_REQUEST['visa45idescuela'] = $fila['visa40idescuela'];
		$_REQUEST['visa45idprograma'] = $fila['visa40idprograma'];
		$_REQUEST['visa45idzona'] = $fila['visa40idzona'];
		$_REQUEST['visa45idcentro'] = $fila['visa40idcentro'];
		$bcargo = true;
		$_REQUEST['paso'] = 2;
		if ($fila['visa45puntaje'] === null) {
			$_REQUEST['paso'] = 0;
		}
		$_REQUEST['boculta2945'] = 0;
		$bLimpiaHijos = true;
	} else {
		$_REQUEST['paso'] = 0;
	}
}
//Insertar o modificar un elemento
if (($_REQUEST['paso'] == 10) || ($_REQUEST['paso'] == 12)) {
	$bMueveScroll = true;
	list($_REQUEST, $sError, $iTipoError, $sDebugGuardar) = f2945_db_GuardarV2b($_REQUEST, $objDB, $bDebug, $idTercero);
	$sDebug = $sDebug . $sDebugGuardar;
	if ($sError == '') {
		$sError = '<b>' . $ETI['msg_itemguardado'] . '</b>';
		$iTipoError = 1;
	}
}
//Eliminar un elemento
if ($_REQUEST['paso'] == 13) {
	$_REQUEST['paso'] = 2;
	if ($sError == '') {
		list($sError, $iTipoError, $sDebugElimina) = f2945_db_Eliminar($_REQUEST['visa45id'], $objDB, $bDebug);
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
	$_REQUEST['visa45idinscripcion'] = '';
	$_REQUEST['visa45idprueba'] = '';
	$_REQUEST['visa45id'] = '';
	$_REQUEST['visa45puntaje'] = '';
	$_REQUEST['visa45idconvocatoria'] = '';
	$_REQUEST['visa45fechainsc'] = '';
	$_REQUEST['visa45puntajemaximo'] = '';
	$_REQUEST['visa45puntajeaproba'] = '';
	$_REQUEST['visa45idtercero_td'] = '';
	$_REQUEST['visa45idtercero_doc'] = '';
	$_REQUEST['visa45idtercero'] = '';
	$_REQUEST['visa45idperiodo'] = '';
	$_REQUEST['visa45idescuela'] = '';
	$_REQUEST['visa45idprograma'] = '';
	$_REQUEST['visa45idzona'] = '';
	$_REQUEST['visa45idcentro'] = '';
	$_REQUEST['paso'] = 0;
}
if ($bLimpiaHijos) {
}
//AQUI SE DEBEN CARGAR TODOS LOS DATOS QUE LA FORMA NECESITE.
$bPuedeGuardar = true;
$bConEliminar = false;
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
//Permisos adicionales
$seg_5 = 0;
$seg_6 = 0;
/*
list($bHayImprimir, $sDebugP, $seg_6) = seg_revisa_permisoV3($iCodModulo, 6, $idTercero, $objDB);
*/
if ((int)$_REQUEST['paso'] != 0) {
	//list($bHayImprimir2, $sDebugP, $seg_5) = seg_revisa_permisoV3($iCodModulo, 5, $idTercero, $objDB);
	$bConEliminar = true;
}
//DATOS PARA COMPLETAR EL FORMULARIO
$sNombreUsuario = '';
$sEtiSinDato = '{' . $ETI['msg_sindato'] . '}';
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
list($visa45idtercero_rs, $_REQUEST['visa45idtercero'], $_REQUEST['visa45idtercero_td'], $_REQUEST['visa45idtercero_doc']) = html_tercero($_REQUEST['visa45idtercero_td'], $_REQUEST['visa45idtercero_doc'], $_REQUEST['visa45idtercero'], 0, $objDB);
$html_visa45idtercero = $sEtiSinDato;
if ((int) $_REQUEST['visa45idtercero'] != 0) {
	$html_visa45idtercero = $_REQUEST['visa45idtercero_td'] . ' ' . $_REQUEST['visa45idtercero_doc'];
}
list($visa45idconvocatoria_nombre, $sErrorDet) = tabla_campoxid('visa35convocatoria', 'visa35nombre', 'visa35id', $_REQUEST['visa45idconvocatoria'], $sEtiSinDato, $objDB);
$html_visa45idconvocatoria = html_oculto('visa45idconvocatoria', $_REQUEST['visa45idconvocatoria'], $visa45idconvocatoria_nombre);
$visa45fechainsc_nombre = formato_fechalarga(fecha_desdenumero($_REQUEST['visa45fechainsc']), true);
$html_visa45fechainsc = html_oculto('visa45fechainsc', $_REQUEST['visa45fechainsc'], $visa45fechainsc_nombre);
list($visa45idperiodo_nombre, $sErrorDet) = tabla_campoxid('exte02per_aca', 'exte02nombre', 'exte02id', $_REQUEST['visa45idperiodo'], $sEtiSinDato, $objDB);
$html_visa45idperiodo = html_oculto('visa45idperiodo', $_REQUEST['visa45idperiodo'], $visa45idperiodo_nombre);
list($visa45idescuela_nombre, $sErrorDet) = tabla_campoxid('core12escuela', 'core12nombre', 'core12id', $_REQUEST['visa45idescuela'], $sEtiSinDato, $objDB);
$html_visa45idescuela = html_oculto('visa45idescuela', $_REQUEST['visa45idescuela'], $visa45idescuela_nombre);
list($visa45idprograma_nombre, $sErrorDet) = tabla_campoxid('core09programa', 'core09nombre', 'core09id', $_REQUEST['visa45idprograma'], $sEtiSinDato, $objDB);
$html_visa45idprograma = html_oculto('visa45idprograma', $_REQUEST['visa45idprograma'], $visa45idprograma_nombre);
list($visa45idzona_nombre, $sErrorDet) = tabla_campoxid('unad23zona', 'unad23nombre', 'unad23id', $_REQUEST['visa45idzona'], $sEtiSinDato, $objDB);
$html_visa45idzona = html_oculto('visa45idzona', $_REQUEST['visa45idzona'], $visa45idzona_nombre);
list($visa45idcentro_nombre, $sErrorDet) = tabla_campoxid('unad24sede', 'unad24nombre', 'unad24id', $_REQUEST['visa45idcentro'], $sEtiSinDato, $objDB);
$html_visa45idcentro = html_oculto('visa45idcentro', $_REQUEST['visa45idcentro'], $visa45idcentro_nombre);
list($visa45idprueba_nombre, $sErrorDet) = tabla_campoxid('visa38convpruebas', 'visa38nombre', 'visa38id', $_REQUEST['visa45idprueba'], $sEtiSinDato, $objDB);
$html_visa45idprueba = html_oculto('visa45idprueba', $_REQUEST['visa45idprueba'], $visa45idprueba_nombre);
$html_visa45puntajemaximo = html_oculto('visa45puntajemaximo', $_REQUEST['visa45puntajemaximo'], $_REQUEST['visa45puntajemaximo']);
$html_visa45puntajeaproba = html_oculto('visa45puntajeaproba', $_REQUEST['visa45puntajeaproba'], $_REQUEST['visa45puntajeaproba']);
//Alistar datos adicionales
$id_rpt = 0;
//$id_rpt=reportes_id(_Identificador_Tipo_Reporte_, $objDB);
$objCombos->nuevo('bresultado', $_REQUEST['bresultado'], true, '{' . $ETI['msg_todos'] . '}');
$objCombos->addArreglo($avisa45resultado, $ivisa45resultado);
$objCombos->sAccion = 'paginarf2945()';
$sSQL = '';
$html_bresultado = $objCombos->html($sSQL, $objDB);
//$html_blistar = $objCombos->comboSistema(2945, 1, $objDB, 'paginarf2945()');
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
$aParametros[0] = ''; //$_REQUEST['p1_2945'];
$aParametros[100] = $idTercero;
$aParametros[101] = $_REQUEST['paginaf2945'];
$aParametros[102] = $_REQUEST['lppf2945'];
$aParametros[103] = $_REQUEST['bdocumento'];
$aParametros[104] = $_REQUEST['bnombre'];
$aParametros[105] = $_REQUEST['bresultado'];
list($sTabla2945, $sDebugTabla) = f2945_TablaDetalleV2($aParametros, $objDB, $bDebug);
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

	function imprimelista() {
		if (window.document.frmedita.seg_6.value == 1) {
			window.document.frmlista.consulta.value = window.document.frmedita.consulta_2945.value;
			window.document.frmlista.titulos.value = window.document.frmedita.titulos_2945.value;
			window.document.frmlista.nombrearchivo.value = 'Resultados pruebas';
			window.document.frmlista.submit();
		} else {
			ModalMensaje("<?php echo $ERR['6']; ?>");
		}
	}

	function asignarvariables() {
		window.document.frmimpp.v3.value = window.document.frmedita.bdocumento.value;
		window.document.frmimpp.v4.value = window.document.frmedita.bnombre.value;
		window.document.frmimpp.v5.value = window.document.frmedita.bresultado.value;
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
			window.document.frmimpp.action = 'e2945_ss.php';
			window.document.frmimpp.submit();
		} else {
			ModalMensaje(sError);
		}
	}

	function imprimep() {
		if (window.document.frmedita.seg_5.value == 1) {
			asignarvariables();
			window.document.frmimpp.action = 'p2945.php';
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
		datos[1] = window.document.frmedita.visa45idinscripcion.value;
		datos[2] = window.document.frmedita.visa45idprueba.value;
		if ((datos[1] != '') && (datos[2] != '')) {
			xajax_f2945_ExisteDato(datos);
		}
	}

	function cargadato(llave1, llave2) {
		window.document.frmedita.visa45idinscripcion.value = String(llave1);
		window.document.frmedita.visa45idprueba.value = String(llave2);
		window.document.frmedita.paso.value = 1;
		window.document.frmedita.submit();
	}

	function cargaridf2945(llave1) {
		window.document.frmedita.visa45id.value = String(llave1);
		window.document.frmedita.paso.value = 3;
		window.document.frmedita.submit();
	}

	function paginarf2945() {
		let params = new Array();
		params[99] = window.document.frmedita.debug.value;
		params[100] = <?php echo $idTercero; ?>;
		params[101] = window.document.frmedita.paginaf2945.value;
		params[102] = window.document.frmedita.lppf2945.value;
		params[103] = window.document.frmedita.bdocumento.value;
		params[104] = window.document.frmedita.bnombre.value;
		params[105] = window.document.frmedita.bresultado.value;
		document.getElementById('div_f2945detalle').innerHTML = '<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf2945" name="paginaf2945" type="hidden" value="' + params[101] + '" /><input id="lppf2945" name="lppf2945" type="hidden" value="' + params[102] + '" />';
		xajax_f2945_HtmlTabla(params);
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
		document.getElementById("visa45idinscripcion").focus();
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
</script>
<?php
if ($_REQUEST['paso'] != 0) {
?>
<?php
}
?>
<form id="frmimpp" name="frmimpp" method="post" action="p2945.php" target="_blank" style="display:none">
<input id="r" name="r" type="hidden" value="2945" />
<input id="id2945" name="id2945" type="hidden" value="<?php echo $_REQUEST['visa45id']; ?>" />
<input id="v3" name="v3" type="hidden" value="" />
<input id="v4" name="v4" type="hidden" value="" />
<input id="v5" name="v5" type="hidden" value="" />
<input id="iformato94" name="iformato94" type="hidden" value="0" />
<input id="separa" name="separa" type="hidden" value="," />
<input id="rdebug" name="rdebug" type="hidden" value="<?php echo $_REQUEST['debug']; ?>" />
<input id="clave" name="clave" type="hidden" value="" />
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
if (false) {
?>
<input id="cmdAnular" name="cmdAnular" type="button" class="btSupAnular" onclick="expandesector(2);" title="<?php echo $ETI['bt_anular']; ?>" value="<?php echo $ETI['bt_anular']; ?>" />
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
echo $objForma->htmlExpande(2945, $_REQUEST['boculta2945'], $ETI['bt_mostrar'], $ETI['bt_ocultar']);
$sEstiloDiv = '';
if ($_REQUEST['boculta2945'] != 0) {
	$sEstiloDiv = ' style="display:none;"';
}
?>
</div>
<div id="div_p2945"<?php echo $sEstiloDiv; ?>>
<?php
}
//Mostrar formulario para editar
?>
<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo $ETI['visa45idtercero'];
?>
</label>
<div class="salto1px"></div>
<input id="visa45idtercero" name="visa45idtercero" type="hidden" value="<?php echo $_REQUEST['visa45idtercero']; ?>"/>
<div id="div_visa45idtercero_llaves">
<?php
echo '<b>' . $html_visa45idtercero . '</b>';
?>
</div>
<div class="salto1px"></div>
<div id="div_visa45idtercero" class="L"><?php echo $visa45idtercero_rs; ?></div>
<div class="salto5px"></div>
<label class="Label130">
<?php
echo $ETI['visa45convocatoria'];
?>
</label>
<label class="Label250">
<?php
echo $html_visa45idconvocatoria;
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['visa45fechainsc'];
?>
</label>
<label class="Label250">
<?php
echo $html_visa45fechainsc;
?>
</label>
<input id="visa45idinscripcion" name="visa45idinscripcion" type="hidden" value="<?php echo $_REQUEST['visa45idinscripcion']; ?>" />
<div class="salto1px"></div>
</div>
<div class="GrupoCampos520">
<label class="Label130">
<?php
echo $ETI['visa45idperiodo'];
?>
</label>
<label>
<?php
echo $html_visa45idperiodo;
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['visa45idzona'];
?>
</label>
<label>
<?php
echo $html_visa45idzona;
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['visa45idcentro'];
?>
</label>
<label>
<?php
echo $html_visa45idcentro;
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['visa45idescuela'];
?>
</label>
<label>
<?php
echo $html_visa45idescuela;
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['visa45idprograma'];
?>
</label>
<label>
<?php
echo $html_visa45idprograma;
?>
</label>
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="Label60"<?php echo $sOcultaId; ?>>
<?php
echo $ETI['visa45id'];
?>
</label>
<label class="Label60"<?php echo $sOcultaId; ?>>
<?php
	echo html_oculto('visa45id', $_REQUEST['visa45id'], formato_numero($_REQUEST['visa45id']));
?>
</label>
<label class="Label90">
<?php
echo $ETI['visa45idprueba'];
?>
</label>
<label>
<?php
echo $html_visa45idprueba;
?>
</label>
<div class="salto1px"></div>
<label class="Label90">
<?php
echo $ETI['visa45puntaje'];
?>
</label>
<label class="Label130">
<input id="visa45puntaje" name="visa45puntaje" type="number" value="<?php echo $_REQUEST['visa45puntaje']; ?>" class="diez" maxlength="10" placeholder="<?php echo $ETI['ing_vr']; ?>" />
</label>
<label class="Label130">
<?php
echo $ETI['visa45puntajemaximo'];
?>
</label>
<label class="Label90">
<?php
echo $html_visa45puntajemaximo;
?>
</label>
<label class="Label160">
<?php
echo $ETI['visa45puntajeaproba'];
?>
</label>
<label class="Label90">
<?php
echo $html_visa45puntajeaproba;
?>
</label>
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
<?php
if (false) {
	//Ejemplo de boton de ayuda
	//echo html_BotonAyuda('NombreCampo');
	//echo html_DivAyudaLocal('NombreCampo');
}
if ($bConExpande) {
	//Este es el cierre del div_p2945
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
echo $ETI['msg_bdocumento'];
?>
</label>
<label>
<input id="bdocumento" name="bdocumento" type="text" value="<?php echo $_REQUEST['bdocumento']; ?>" onchange="paginarf2945()" autocomplete="off" />
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['msg_bnombre'];
?>
</label>
<label>
<input id="bnombre" name="bnombre" type="text" value="<?php echo $_REQUEST['bnombre']; ?>" onchange="paginarf2945()" autocomplete="off" />
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['msg_bresultado'];
?>
</label>
<label>
<?php
echo $html_bresultado;
?>
</label>
</div>
<div class="salto1px"></div>
<?php
echo ' ' . $csv_separa;
?>
<div class="salto1px"></div>
<div id="div_f2945detalle">
<?php
echo $sTabla2945;
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
<input id="titulo_2945" name="titulo_2945" type="hidden" value="<?php echo $sTituloModulo; ?>" />
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
<script language="javascript" src="<?php echo $APP->rutacomun; ?>js/chosen.jquery.js"></script>
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>js/chosen.css" type="text/css" />
<?php
if (false) {
//}
//if ($_REQUEST['paso'] == 0) {
?>
<script language="javascript">
	$().ready(function() {
		$("#visa45idprueba").chosen({width:"100%"});
	});
</script>
<?php
}
?>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>unad_todas2024v2.js"></script>
<?php
forma_piedepagina();

