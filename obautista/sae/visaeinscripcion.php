<?php
/*
--- © Omar Augusto Bautista - UNAD - 2026 ---
--- omar.bautista@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 3.1.5 viernes, 27 de febrero de 2026
*/
/** Archivo visaeinscripcion.php.
 * Modulo 2940 visa40inscripcion.
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
//require $APP->rutacomun . 'libmail.php';
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
$iCodModulo = 2940;
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
$mensajes_2900 = 'lg/lg_2900_' . $sIdioma . '.php';
if (!file_exists($mensajes_2900)) {
	$mensajes_2900 = 'lg/lg_2900_es.php';
}
require $mensajes_2900;
*/
$mensajes_2940 = 'lg/lg_2940_' . $sIdioma . '.php';
if (!file_exists($mensajes_2940)) {
	$mensajes_2940 = 'lg/lg_2940_es.php';
}
require $mensajes_todas;
require $mensajes_2940;
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
$sTituloModulo = $ETI['titulo_2940'];
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
		header('Location:noticia.php?ret=visaeinscripcion.php');
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
$mensajes_2943 = 'lg/lg_2943_' . $sIdioma . '.php';
if (!file_exists($mensajes_2943)) {
	$mensajes_2943 = 'lg/lg_2943_es.php';
}
$mensajes_2944 = 'lg/lg_2944_' . $sIdioma . '.php';
if (!file_exists($mensajes_2944)) {
	$mensajes_2944 = 'lg/lg_2944_es.php';
}
$mensajes_2945 = 'lg/lg_2945_' . $sIdioma . '.php';
if (!file_exists($mensajes_2945)) {
	$mensajes_2945 = 'lg/lg_2945_es.php';
}
require $mensajes_2943;
require $mensajes_2944;
require $mensajes_2945;
// -- Si esta cargando la pagina por primer vez se revisa si requiere auditar y se manda a hacer un limpiar (paso -1)
if (isset($_REQUEST['paso']) == 0) {
	$_REQUEST['paso'] = -1;
	if ($audita[1]) {
		seg_auditaingreso($iCodModulo, $_SESSION['unad_id_tercero'], $objDB);
	}
}
// -- 2940 visa40inscripcion
require 'lib2940.php';
// -- 2943 Anexos
require 'lib2943.php';
// -- 2944 Anotaciones
require 'lib2944.php';
// -- 2945 Resultados pruebas
require 'lib2945.php';
$xajax = new xajax();
$xajax->configure('javascript URI', $APP->rutacomun . 'xajax/');
$xajax->register(XAJAX_FUNCTION, 'unad11_Mostrar_v2');
$xajax->register(XAJAX_FUNCTION, 'unad11_TraerXid');
$xajax->register(XAJAX_FUNCTION, 'f2940_Combovisa40idprograma');
$xajax->register(XAJAX_FUNCTION, 'f2940_Combovisa40idcentro');
$xajax->register(XAJAX_FUNCTION, 'f2940_Combovisa40idsubtipo');
$xajax->register(XAJAX_FUNCTION, 'f2940_Combobsubtipologia');
$xajax->register(XAJAX_FUNCTION, 'sesion_abandona_V2');
$xajax->register(XAJAX_FUNCTION, 'sesion_mantenerV4');
$xajax->register(XAJAX_FUNCTION, 'f2940_HtmlTabla');
$xajax->register(XAJAX_FUNCTION, 'f2940_ExisteDato');
$xajax->register(XAJAX_FUNCTION, 'f2940_Busquedas');
$xajax->register(XAJAX_FUNCTION, 'f2940_HtmlBusqueda');
$xajax->register(XAJAX_FUNCTION, 'elimina_archivo_visa43idarchivo');
$xajax->register(XAJAX_FUNCTION, 'f2943_Guardar');
$xajax->register(XAJAX_FUNCTION, 'f2943_Traer');
$xajax->register(XAJAX_FUNCTION, 'f2943_Eliminar');
$xajax->register(XAJAX_FUNCTION, 'f2943_HtmlTabla');
$xajax->register(XAJAX_FUNCTION, 'f2943_PintarLlaves');
$xajax->register(XAJAX_FUNCTION, 'f2944_Guardar');
$xajax->register(XAJAX_FUNCTION, 'f2944_Traer');
$xajax->register(XAJAX_FUNCTION, 'f2944_Eliminar');
$xajax->register(XAJAX_FUNCTION, 'f2944_HtmlTabla');
$xajax->register(XAJAX_FUNCTION, 'f2944_PintarLlaves');
$xajax->register(XAJAX_FUNCTION, 'f2945_Guardar');
$xajax->register(XAJAX_FUNCTION, 'f2945_Traer');
$xajax->register(XAJAX_FUNCTION, 'f2945_Eliminar');
$xajax->register(XAJAX_FUNCTION, 'f2945_HtmlTabla');
$xajax->register(XAJAX_FUNCTION, 'f2945_PintarLlaves');
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
if (isset($_REQUEST['paginaf2940']) == 0) {
	$_REQUEST['paginaf2940'] = 1;
}
if (isset($_REQUEST['lppf2940']) == 0) {
	$_REQUEST['lppf2940'] = 20;
}
if (isset($_REQUEST['boculta2940']) == 0) {
	$_REQUEST['boculta2940'] = 0;
}
// -- Inicializar variables de datos.
if (isset($_REQUEST['visa40idconvocatoria']) == 0) {
	$_REQUEST['visa40idconvocatoria'] = '';
}
if (isset($_REQUEST['visa40idtercero']) == 0) {
	$_REQUEST['visa40idtercero'] = 0;
	//$_REQUEST['visa40idtercero'] = $idTercero;
}
if (isset($_REQUEST['visa40idtercero_td']) == 0) {
	$_REQUEST['visa40idtercero_td'] = $APP->tipo_doc;
}
if (isset($_REQUEST['visa40idtercero_doc']) == 0) {
	$_REQUEST['visa40idtercero_doc'] = '';
}
if (isset($_REQUEST['visa40id']) == 0) {
	$_REQUEST['visa40id'] = '';
}
if (isset($_REQUEST['visa40estado']) == 0) {
	$_REQUEST['visa40estado'] = 0;
}
if (isset($_REQUEST['visa40idperiodo']) == 0) {
	$_REQUEST['visa40idperiodo'] = '';
}
if (isset($_REQUEST['visa40idescuela']) == 0) {
	$_REQUEST['visa40idescuela'] = '';
}
if (isset($_REQUEST['visa40idprograma']) == 0) {
	$_REQUEST['visa40idprograma'] = '';
}
if (isset($_REQUEST['visa40idzona']) == 0) {
	$_REQUEST['visa40idzona'] = '';
}
if (isset($_REQUEST['visa40idcentro']) == 0) {
	$_REQUEST['visa40idcentro'] = '';
}
if (isset($_REQUEST['visa40fechainsc']) == 0) {
	$_REQUEST['visa40fechainsc'] = '';
	//$_REQUEST['visa40fechainsc'] = $iHoy;
}
if (isset($_REQUEST['visa40fechaadmision']) == 0) {
	$_REQUEST['visa40fechaadmision'] = '';
	//$_REQUEST['visa40fechaadmision'] = $iHoy;
}
if (isset($_REQUEST['visa40numcupo']) == 0) {
	$_REQUEST['visa40numcupo'] = 0;
}
if (isset($_REQUEST['visa40idtipologia']) == 0) {
	$_REQUEST['visa40idtipologia'] = '';
}
if (isset($_REQUEST['visa40idsubtipo']) == 0) {
	$_REQUEST['visa40idsubtipo'] = '';
}
if (isset($_REQUEST['visa40idminuta']) == 0) {
	$_REQUEST['visa40idminuta'] = '';
}
if (isset($_REQUEST['visa40idresolucion']) == 0) {
	$_REQUEST['visa40idresolucion'] = '';
}
$_REQUEST['visa40idconvocatoria'] = numeros_validar($_REQUEST['visa40idconvocatoria']);
$_REQUEST['visa40idtercero'] = numeros_validar($_REQUEST['visa40idtercero']);
$_REQUEST['visa40idtercero_td'] = cadena_Validar($_REQUEST['visa40idtercero_td']);
$_REQUEST['visa40idtercero_doc'] = cadena_Validar($_REQUEST['visa40idtercero_doc']);
$_REQUEST['visa40id'] = numeros_validar($_REQUEST['visa40id']);
$_REQUEST['visa40estado'] = numeros_validar($_REQUEST['visa40estado']);
$_REQUEST['visa40idperiodo'] = numeros_validar($_REQUEST['visa40idperiodo']);
$_REQUEST['visa40idescuela'] = numeros_validar($_REQUEST['visa40idescuela']);
$_REQUEST['visa40idprograma'] = numeros_validar($_REQUEST['visa40idprograma']);
$_REQUEST['visa40idzona'] = numeros_validar($_REQUEST['visa40idzona']);
$_REQUEST['visa40idcentro'] = numeros_validar($_REQUEST['visa40idcentro']);
$_REQUEST['visa40fechainsc'] = numeros_validar($_REQUEST['visa40fechainsc']);
$_REQUEST['visa40fechaadmision'] = numeros_validar($_REQUEST['visa40fechaadmision']);
$_REQUEST['visa40numcupo'] = numeros_validar($_REQUEST['visa40numcupo']);
$_REQUEST['visa40idtipologia'] = numeros_validar($_REQUEST['visa40idtipologia']);
$_REQUEST['visa40idsubtipo'] = numeros_validar($_REQUEST['visa40idsubtipo']);
$_REQUEST['visa40idminuta'] = numeros_validar($_REQUEST['visa40idminuta']);
$_REQUEST['visa40idresolucion'] = numeros_validar($_REQUEST['visa40idresolucion']);
if ((int)$_REQUEST['paso'] > 0) {
	//Anexos
	if (isset($_REQUEST['paginaf2943']) == 0) {
		$_REQUEST['paginaf2943'] = 1;
	}
	if (isset($_REQUEST['lppf2943']) == 0) {
		$_REQUEST['lppf2943'] = 20;
	}
	if (isset($_REQUEST['boculta2943']) == 0) {
		$_REQUEST['boculta2943'] = 0;
	}
	if (isset($_REQUEST['visa43idinscripcion']) == 0) {
		$_REQUEST['visa43idinscripcion'] = '';
	}
	if (isset($_REQUEST['visa43iddocumento']) == 0) {
		$_REQUEST['visa43iddocumento'] = '';
	}
	if (isset($_REQUEST['visa43id']) == 0) {
		$_REQUEST['visa43id'] = '';
	}
	if (isset($_REQUEST['visa43idorigen']) == 0) {
		$_REQUEST['visa43idorigen'] = 0;
	}
	if (isset($_REQUEST['visa43idarchivo']) == 0) {
		$_REQUEST['visa43idarchivo'] = 0;
	}
	if (isset($_REQUEST['visa43fechaaprob']) == 0) {
		$_REQUEST['visa43fechaaprob'] = '';
		//$_REQUEST['visa43fechaaprob'] = $iHoy;
	}
	if (isset($_REQUEST['visa43usuarioaprueba']) == 0) {
		$_REQUEST['visa43usuarioaprueba'] = 0;
		//$_REQUEST['visa43usuarioaprueba'] =  $idTercero;
	}
	if (isset($_REQUEST['visa43usuarioaprueba_td']) == 0) {
		$_REQUEST['visa43usuarioaprueba_td'] = $APP->tipo_doc;
	}
	if (isset($_REQUEST['visa43usuarioaprueba_doc']) == 0) {
		$_REQUEST['visa43usuarioaprueba_doc'] = '';
	}
	$_REQUEST['visa43idinscripcion'] = numeros_validar($_REQUEST['visa43idinscripcion']);
	$_REQUEST['visa43iddocumento'] = numeros_validar($_REQUEST['visa43iddocumento']);
	$_REQUEST['visa43id'] = numeros_validar($_REQUEST['visa43id']);
	$_REQUEST['visa43idorigen'] = numeros_validar($_REQUEST['visa43idorigen']);
	$_REQUEST['visa43idarchivo'] = numeros_validar($_REQUEST['visa43idarchivo']);
	$_REQUEST['visa43fechaaprob'] = numeros_validar($_REQUEST['visa43fechaaprob']);
	$_REQUEST['visa43usuarioaprueba'] = numeros_validar($_REQUEST['visa43usuarioaprueba']);
	$_REQUEST['visa43usuarioaprueba_td'] = cadena_Validar($_REQUEST['visa43usuarioaprueba_td']);
	$_REQUEST['visa43usuarioaprueba_doc'] = cadena_Validar($_REQUEST['visa43usuarioaprueba_doc']);
	//Anotaciones
	if (isset($_REQUEST['paginaf2944']) == 0) {
		$_REQUEST['paginaf2944'] = 1;
	}
	if (isset($_REQUEST['lppf2944']) == 0) {
		$_REQUEST['lppf2944'] = 20;
	}
	if (isset($_REQUEST['boculta2944']) == 0) {
		$_REQUEST['boculta2944'] = 0;
	}
	if (isset($_REQUEST['visa44idinscripcion']) == 0) {
		$_REQUEST['visa44idinscripcion'] = '';
	}
	if (isset($_REQUEST['visa44consec']) == 0) {
		$_REQUEST['visa44consec'] = '';
	}
	if (isset($_REQUEST['visa44id']) == 0) {
		$_REQUEST['visa44id'] = '';
	}
	if (isset($_REQUEST['visa44alcance']) == 0) {
		$_REQUEST['visa44alcance'] = '';
	}
	if (isset($_REQUEST['visa44nota']) == 0) {
		$_REQUEST['visa44nota'] = '';
	}
	if (isset($_REQUEST['visa44usuario']) == 0) {
		$_REQUEST['visa44usuario'] = 0;
		//$_REQUEST['visa44usuario'] =  $idTercero;
	}
	if (isset($_REQUEST['visa44usuario_td']) == 0) {
		$_REQUEST['visa44usuario_td'] = $APP->tipo_doc;
	}
	if (isset($_REQUEST['visa44usuario_doc']) == 0) {
		$_REQUEST['visa44usuario_doc'] = '';
	}
	if (isset($_REQUEST['visa44fecha']) == 0) {
		$_REQUEST['visa44fecha'] = '';
		//$_REQUEST['visa44fecha'] = $iHoy;
	}
	if (isset($_REQUEST['visa44hora']) == 0) {
		$_REQUEST['visa44hora'] = '';
	}
	if (isset($_REQUEST['visa44minuto']) == 0) {
		$_REQUEST['visa44minuto'] = '';
	}
	$_REQUEST['visa44idinscripcion'] = numeros_validar($_REQUEST['visa44idinscripcion']);
	$_REQUEST['visa44consec'] = numeros_validar($_REQUEST['visa44consec']);
	$_REQUEST['visa44id'] = numeros_validar($_REQUEST['visa44id']);
	$_REQUEST['visa44alcance'] = numeros_validar($_REQUEST['visa44alcance']);
	$_REQUEST['visa44nota'] = cadena_Validar($_REQUEST['visa44nota']);
	$_REQUEST['visa44usuario'] = numeros_validar($_REQUEST['visa44usuario']);
	$_REQUEST['visa44usuario_td'] = cadena_Validar($_REQUEST['visa44usuario_td']);
	$_REQUEST['visa44usuario_doc'] = cadena_Validar($_REQUEST['visa44usuario_doc']);
	$_REQUEST['visa44fecha'] = numeros_validar($_REQUEST['visa44fecha']);
	$_REQUEST['visa44hora'] = numeros_validar($_REQUEST['visa44hora']);
	$_REQUEST['visa44minuto'] = numeros_validar($_REQUEST['visa44minuto']);
	//Resultados pruebas
	if (isset($_REQUEST['paginaf2945']) == 0) {
		$_REQUEST['paginaf2945'] = 1;
	}
	if (isset($_REQUEST['lppf2945']) == 0) {
		$_REQUEST['lppf2945'] = 20;
	}
	if (isset($_REQUEST['boculta2945']) == 0) {
		$_REQUEST['boculta2945'] = 0;
	}
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
	$_REQUEST['visa45idinscripcion'] = numeros_validar($_REQUEST['visa45idinscripcion']);
	$_REQUEST['visa45idprueba'] = numeros_validar($_REQUEST['visa45idprueba']);
	$_REQUEST['visa45id'] = numeros_validar($_REQUEST['visa45id']);
	$_REQUEST['visa45puntaje'] = numeros_validar($_REQUEST['visa45puntaje']);
}
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
if (isset($_REQUEST['bconvocatoria']) == 0) {
	$_REQUEST['bconvocatoria'] = '';
}
if (isset($_REQUEST['bestado']) == 0) {
	$_REQUEST['bestado'] = '';
}
if (isset($_REQUEST['btipologia']) == 0) {
	$_REQUEST['btipologia'] = '';
}
if (isset($_REQUEST['bsubtipologia']) == 0) {
	$_REQUEST['bsubtipologia'] = '';
}
	//Anexos
	//Anotaciones
	//Resultados pruebas
$_REQUEST['bdocumento'] = cadena_Validar($_REQUEST['bdocumento']);
$_REQUEST['bnombre'] = cadena_Validar($_REQUEST['bnombre']);
$_REQUEST['bconvocatoria'] = numeros_validar($_REQUEST['bconvocatoria']);
$_REQUEST['bestado'] = numeros_validar($_REQUEST['bestado']);
$_REQUEST['btipologia'] = numeros_validar($_REQUEST['btipologia']);
$_REQUEST['bsubtipologia'] = numeros_validar($_REQUEST['bsubtipologia']);
	//Anexos
	//Anotaciones
	//Resultados pruebas
//Si Modifica o Elimina Cargar los campos
if (($_REQUEST['paso'] == 1) || ($_REQUEST['paso'] == 3)) {
	$_REQUEST['visa40idtercero_td'] = $APP->tipo_doc;
	$_REQUEST['visa40idtercero_doc'] = '';
	if ($_REQUEST['paso'] == 1) {
		$sSQLcondi = 'visa40idconvocatoria=' . $_REQUEST['visa40idconvocatoria'] . ' AND visa40idtercero="' . $_REQUEST['visa40idtercero'] . '"';
	} else {
		$sSQLcondi = 'visa40id=' . $_REQUEST['visa40id'] . '';
	}
	$sSQL = 'SELECT * FROM visa40inscripcion WHERE ' . $sSQLcondi;
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$_REQUEST['visa40idconvocatoria'] = $fila['visa40idconvocatoria'];
		$_REQUEST['visa40idtercero'] = $fila['visa40idtercero'];
		$_REQUEST['visa40id'] = $fila['visa40id'];
		$_REQUEST['visa40estado'] = $fila['visa40estado'];
		$_REQUEST['visa40idperiodo'] = $fila['visa40idperiodo'];
		$_REQUEST['visa40idescuela'] = $fila['visa40idescuela'];
		$_REQUEST['visa40idprograma'] = $fila['visa40idprograma'];
		$_REQUEST['visa40idzona'] = $fila['visa40idzona'];
		$_REQUEST['visa40idcentro'] = $fila['visa40idcentro'];
		$_REQUEST['visa40fechainsc'] = $fila['visa40fechainsc'];
		$_REQUEST['visa40fechaadmision'] = $fila['visa40fechaadmision'];
		$_REQUEST['visa40numcupo'] = $fila['visa40numcupo'];
		$_REQUEST['visa40idtipologia'] = $fila['visa40idtipologia'];
		$_REQUEST['visa40idsubtipo'] = $fila['visa40idsubtipo'];
		$_REQUEST['visa40idminuta'] = $fila['visa40idminuta'];
		$_REQUEST['visa40idresolucion'] = $fila['visa40idresolucion'];
		$bcargo = true;
		$_REQUEST['paso'] = 2;
		$_REQUEST['boculta2940'] = 0;
		$bLimpiaHijos = true;
	} else {
		$_REQUEST['paso'] = 0;
	}
}
//Cerrar
$bCambiaEstado = false;
if ($_REQUEST['paso'] == 16) {
	$_REQUEST['paso'] = 12;
	if ($sError == '') {
		$bCambiaEstado = true;
	} else {
		//Esto es opcional porque bloquea el guardado y el mensaje de error se pierde, considere recuperar el error.
		//$_REQUEST['paso'] = 2;
	}
}
//Abrir
if ($_REQUEST['paso'] == 17) {
	$_REQUEST['paso'] = 2;
	//Es posible que deba definir el codigo de permiso para abrir.
	if ($sError == '') {
		list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 17, $idTercero, $objDB);
		if (!$bDevuelve) {
			$sError = $ERR['3'] . ' [Mod ' . $iCodModulo . ']';
		}
	}
	//Otras restricciones para abrir.
	if ($sError == '') {
		//$sError = 'Motivo por el que no se pueda abrir, no se permite modificar.';
	}
	if ($sError == '') {
		list($sError, $sDebugE, $sMensaje) = f2940_CambiaEstado($_REQUEST['visa40id'], $_REQUEST['visa40estado'], 0, '', $_SESSION['unad_id_tercero'], $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugE;
		if ($sError == '') {
			$_REQUEST['visa40estado'] = 0;
			$sError = '<b>' . $ETI['msg_itemabierto'] . '</b>';
			$iTipoError = 1;
		}
	}
}
//Insertar o modificar un elemento
if (($_REQUEST['paso'] == 10) || ($_REQUEST['paso'] == 12)) {
	$bMueveScroll = true;
	list($_REQUEST, $sError, $iTipoError, $sDebugGuardar) = f2940_db_GuardarV2b($_REQUEST, $objDB, $bDebug, $idTercero);
	$sDebug = $sDebug . $sDebugGuardar;
	if ($sError == '') {
		if (!$bCambiaEstado) {
			$sError = '<b>' . $ETI['msg_itemguardado'] . '</b>';
			$iTipoError = 1;
		}
	} else {
		$bCambiaEstado = false;
	}
}
if ($bCambiaEstado) {
	//acciones del cerrado
	if ($sError == '') {
		list($sError, $sDebugE, $sMensaje) = f2940_CambiaEstado($_REQUEST['visa40id'], $_REQUEST['visa40estado'], 7, '', $_SESSION['unad_id_tercero'], $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugE;
		if ($sError == '') {
			$_REQUEST['visa40estado'] = 7;
			$sError = '<b>' . $ETI['msg_itemcerrado'] . '</b>';
			$iTipoError = 1;
		}
	}
}
//Eliminar un elemento
if ($_REQUEST['paso'] == 13) {
	$_REQUEST['paso'] = 2;
	if ($sError == '') {
		list($sError, $iTipoError, $sDebugElimina) = f2940_db_Eliminar($_REQUEST['visa40id'], $objDB, $bDebug);
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
	$_REQUEST['visa40idconvocatoria'] = '';
	$_REQUEST['visa40idtercero'] = 0; //$idTercero;
	$_REQUEST['visa40idtercero_td'] = $APP->tipo_doc;
	$_REQUEST['visa40idtercero_doc'] = '';
	$_REQUEST['visa40id'] = '';
	$_REQUEST['visa40estado'] = 0;
	$_REQUEST['visa40idperiodo'] = '';
	$_REQUEST['visa40idescuela'] = '';
	$_REQUEST['visa40idprograma'] = '';
	$_REQUEST['visa40idzona'] = '';
	$_REQUEST['visa40idcentro'] = '';
	$_REQUEST['visa40fechainsc'] = $iHoy;
	$_REQUEST['visa40fechaadmision'] = $iHoy;
	$_REQUEST['visa40numcupo'] = 0;
	$_REQUEST['visa40idtipologia'] = '';
	$_REQUEST['visa40idsubtipo'] = '';
	$_REQUEST['visa40idminuta'] = '';
	$_REQUEST['visa40idresolucion'] = '';
	$_REQUEST['paso'] = 0;
}
if ($bLimpiaHijos) {
	$_REQUEST['visa43idinscripcion'] = '';
	$_REQUEST['visa43iddocumento'] = 0;
	$_REQUEST['visa43id'] = '';
	$_REQUEST['visa43idorigen'] = 0;
	$_REQUEST['visa43idarchivo'] = 0;
	$_REQUEST['visa43fechaaprob'] = '';
	//$_REQUEST['visa43fechaaprob'] = $iHoy;
	$_REQUEST['visa43usuarioaprueba'] = $idTercero;
	$_REQUEST['visa43usuarioaprueba_td'] = $APP->tipo_doc;
	$_REQUEST['visa43usuarioaprueba_doc'] = '';
	$_REQUEST['visa44idinscripcion'] = '';
	$_REQUEST['visa44consec'] = '';
	$_REQUEST['visa44id'] = '';
	$_REQUEST['visa44alcance'] = '';
	$_REQUEST['visa44nota'] = '';
	$_REQUEST['visa44usuario'] = $idTercero;
	$_REQUEST['visa44usuario_td'] = $APP->tipo_doc;
	$_REQUEST['visa44usuario_doc'] = '';
	$_REQUEST['visa44fecha'] = '';
	//$_REQUEST['visa44fecha'] = $iHoy;
	$_REQUEST['visa44hora'] = fecha_hora();
	$_REQUEST['visa44minuto'] = fecha_minuto();
	$_REQUEST['visa45idinscripcion'] = '';
	$_REQUEST['visa45idprueba'] = '';
	$_REQUEST['visa45id'] = '';
	$_REQUEST['visa45puntaje'] = '';
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
$bEdita2943 = false;
$bEdita2944 = false;
$bEdita2945 = false;
//Permisos adicionales
$seg_5 = 0;
$seg_6 = 0;
/*
list($bHayImprimir, $sDebugP, $seg_6) = seg_revisa_permisoV3($iCodModulo, 6, $idTercero, $objDB);
*/
if ((int)$_REQUEST['paso'] != 0) {
	//list($bHayImprimir2, $sDebugP, $seg_5) = seg_revisa_permisoV3($iCodModulo, 5, $idTercero, $objDB);
	$bEdita2943 = true;
	$bEdita2944 = true;
	$bEdita2945 = true;
	switch ($_REQUEST['visa40estado']) {
		case 0: // Abierto
			$bConEliminar = true;
			$bConBotonCerrar = true;
			break;
		case 7: // Cerrado
			$bPuedeGuardar = false;
			list($bPuedeAbrir, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 17, $idTercero, $objDB);
			break;
	}
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
list($visa40idtercero_rs, $_REQUEST['visa40idtercero'], $_REQUEST['visa40idtercero_td'], $_REQUEST['visa40idtercero_doc']) = html_tercero($_REQUEST['visa40idtercero_td'], $_REQUEST['visa40idtercero_doc'], $_REQUEST['visa40idtercero'], 0, $objDB);
$bOculto = true;
if ((int)$_REQUEST['visa40id'] == 0) {
	$bOculto = false;
}
$html_visa40idtercero = html_DivTerceroV8('visa40idtercero', $_REQUEST['visa40idtercero_td'], $_REQUEST['visa40idtercero_doc'], $bOculto, $objDB, $objCombos, 1, $ETI['ing_doc']);
$visa40estado_nombre = '{' . $_REQUEST['visa40estado'] . '}';
$sSQL = 'SELECT unad96nombre, unad96etiqueta FROM unad96estado WHERE unad96idmodulo=2940 AND unad96id=' . $_REQUEST['visa40estado'];
$tabla = $objDB->ejecutasql($sSQL);
if ($objDB->nf($tabla) > 0) {
	$fila = $objDB->sf($tabla);
	$visa40estado_nombre = cadena_notildes($fila['unad96nombre']);
	if ($sIdioma != 'es') {
		$visa40estado_nombre = Etiqueta_Valor(4137, $fila['unad96etiqueta'], $sIdioma, $objDB);
	}
}
$html_visa40estado = html_oculto('visa40estado', $_REQUEST['visa40estado'], $visa40estado_nombre);
$objCombos->nuevo('visa40idperiodo', $_REQUEST['visa40idperiodo'], true, '{' . $ETI['msg_seleccione'] . '}');
$sSQL = f146_ConsultaCombo();
$html_visa40idperiodo = $objCombos->html($sSQL, $objDB);
$objCombos->nuevo('visa40idescuela', $_REQUEST['visa40idescuela'], true, '{' . $ETI['msg_seleccione'] . '}');
$objCombos->sAccion = 'carga_combo_visa40idprograma();';
$sSQL = 'SELECT core12id AS id, core12nombre AS nombre FROM core12escuela ORDER BY core12nombre';
$html_visa40idescuela = $objCombos->html($sSQL, $objDB);
$html_visa40idprograma = f2940_HTMLComboV2_visa40idprograma($objDB, $objCombos, $_REQUEST['visa40idprograma'], $_REQUEST['visa40idescuela']);
$objCombos->nuevo('visa40idzona', $_REQUEST['visa40idzona'], true, '{' . $ETI['msg_seleccione'] . '}');
$objCombos->sAccion = 'carga_combo_visa40idcentro();';
$sSQL = 'SELECT unad23id AS id, unad23nombre AS nombre FROM unad23zona ORDER BY unad23nombre';
$html_visa40idzona = $objCombos->html($sSQL, $objDB);
$html_visa40idcentro = f2940_HTMLComboV2_visa40idcentro($objDB, $objCombos, $_REQUEST['visa40idcentro'], $_REQUEST['visa40idzona']);
$objCombos->nuevo('visa40idtipologia', $_REQUEST['visa40idtipologia'], true, '{' . $ETI['msg_seleccione'] . '}');
$objCombos->sAccion = 'carga_combo_visa40idsubtipo();';
$sSQL = 'SELECT visa36id AS id, visa36nombre AS nombre FROM visa36convtipologia ORDER BY visa36nombre';
$html_visa40idtipologia = $objCombos->html($sSQL, $objDB);
$html_visa40idsubtipo = f2940_HTMLComboV2_visa40idsubtipo($objDB, $objCombos, $_REQUEST['visa40idsubtipo'], $_REQUEST['visa40idtipologia']);
if ((int)$_REQUEST['paso'] == 0) {
	$html_visa40idconvocatoria = f2940_HTMLComboV2_visa40idconvocatoria($objDB, $objCombos, $_REQUEST['visa40idconvocatoria']);
} else {
	$visa40idconvocatoria_nombre = '&nbsp;';
	if ((int)$_REQUEST['visa40idconvocatoria'] != 0) {
		list($visa40idconvocatoria_nombre, $sErrorDet) = tabla_campoxid('visa35convocatoria', 'visa35nombre', 'visa35id', $_REQUEST['visa40idconvocatoria'], '{' . $ETI['msg_sindato'] . '}', $objDB);
	}
	$html_visa40idconvocatoria = html_oculto('visa40idconvocatoria', $_REQUEST['visa40idconvocatoria'], $visa40idconvocatoria_nombre);
}
if ($bEdita2943) {
	$visa43iddocumento_nombre = '&nbsp;';
	if ((int)$_REQUEST['visa43iddocumento'] != 0) {
		list($visa43iddocumento_nombre, $sErrorDet) = tabla_campoxid('visa42convanexo', 'visa42nombre', 'visa42id', $_REQUEST['visa43iddocumento'], '{' . $ETI['msg_sindato'] . '}', $objDB);
	}
	$html_visa43iddocumento = html_oculto('visa43iddocumento', $_REQUEST['visa43iddocumento'], $visa43iddocumento_nombre);
	list($visa43usuarioaprueba_rs, $_REQUEST['visa43usuarioaprueba'], $_REQUEST['visa43usuarioaprueba_td'], $_REQUEST['visa43usuarioaprueba_doc']) = html_tercero($_REQUEST['visa43usuarioaprueba_td'], $_REQUEST['visa43usuarioaprueba_doc'], $_REQUEST['visa43usuarioaprueba'], 0, $objDB);
	$bOculto = true;
	$html_visa43usuarioaprueba = html_DivTerceroV8('visa43usuarioaprueba', $_REQUEST['visa43usuarioaprueba_td'], $_REQUEST['visa43usuarioaprueba_doc'], $bOculto, $objDB, $objCombos, 0, $ETI['ing_doc']);
}
if ($bEdita2944) {
	list($visa44usuario_rs, $_REQUEST['visa44usuario'], $_REQUEST['visa44usuario_td'], $_REQUEST['visa44usuario_doc']) = html_tercero($_REQUEST['visa44usuario_td'], $_REQUEST['visa44usuario_doc'], $_REQUEST['visa44usuario'], 0, $objDB);
	$bOculto = true;
	$html_visa44usuario = html_DivTerceroV8('visa44usuario', $_REQUEST['visa44usuario_td'], $_REQUEST['visa44usuario_doc'], $bOculto, $objDB, $objCombos, 0, $ETI['ing_doc']);
}
if ($bEdita2945) {
	$html_visa45idprueba = f2945_HTMLComboV2_visa45idprueba($objDB, $objCombos, $_REQUEST['visa45idprueba']);
}
//Alistar datos adicionales
$id_rpt = 0;
//$id_rpt=reportes_id(_Identificador_Tipo_Reporte_, $objDB);
$objCombos->nuevo('bconvocatoria', $_REQUEST['bconvocatoria'], true, '{' . $ETI['msg_todos'] . '}');
$objCombos->sAccion = 'paginarf2940()';
$sSQL = 'SELECT visa35id AS id, visa35nombre AS nombre FROM visa35convocatoria ORDER BY visa35nombre';
$html_bconvocatoria = $objCombos->html($sSQL, $objDB);
$objCombos->nuevo('bestado', $_REQUEST['bestado'], true, '{' . $ETI['msg_todos'] . '}');
$objCombos->sAccion = 'paginarf2940()';
$sSQL = 'SELECT unad96id AS id, unad96nombre AS nombre FROM unad96estado WHERE unad96idmodulo=' . $_SESSION['u_identidad'] . ' ORDER BY unad96nombre';
$html_bestado = $objCombos->html($sSQL, $objDB);
$objCombos->nuevo('btipologia', $_REQUEST['btipologia'], true, '{' . $ETI['msg_todos'] . '}');
$objCombos->sAccion = 'carga_combo_bsubtipologia();';
$sSQL = 'SELECT visa36id AS id, visa36nombre AS nombre FROM visa36convtipologia ORDER BY visa36nombre';
$html_btipologia = $objCombos->html($sSQL, $objDB);
$html_bsubtipologia = f2940_HTMLComboV2_bsubtipologia($objDB, $objCombos, $_REQUEST['bsubtipologia'], $_REQUEST['btipologia']);
if ((int)$_REQUEST['paso'] > 0) {
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
//Cargar las tablas de datos
$aParametros[0] = ''; //$_REQUEST['p1_2940'];
$aParametros[100] = $idTercero;
$aParametros[101] = $_REQUEST['paginaf2940'];
$aParametros[102] = $_REQUEST['lppf2940'];
$aParametros[103] = $_REQUEST['bdocumento'];
$aParametros[104] = $_REQUEST['bnombre'];
$aParametros[105] = $_REQUEST['bconvocatoria'];
$aParametros[106] = $_REQUEST['bestado'];
$aParametros[107] = $_REQUEST['btipologia'];
$aParametros[108] = $_REQUEST['bsubtipologia'];
list($sTabla2940, $sDebugTabla) = f2940_TablaDetalleV2($aParametros, $objDB, $bDebug);
$sDebug = $sDebug . $sDebugTabla;
$sTabla2943 = '';
$sTabla2944 = '';
$sTabla2945 = '';
if ($_REQUEST['paso'] != 0) {
	//Anexos
	$aParametros2943[0] = $_REQUEST['visa40id'];
	$aParametros2943[100] = $idTercero;
	$aParametros2943[101] = $_REQUEST['paginaf2943'];
	$aParametros2943[102] = $_REQUEST['lppf2943'];
	//$aParametros2943[103] = $_REQUEST['bnombre2943'];
	//$aParametros2943[104] = $_REQUEST['blistar2943'];
	list($sTabla2943, $sDebugTabla) = f2943_TablaDetalleV2($aParametros2943, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugTabla;
	//Anotaciones
	$aParametros2944[0] = $_REQUEST['visa40id'];
	$aParametros2944[100] = $idTercero;
	$aParametros2944[101] = $_REQUEST['paginaf2944'];
	$aParametros2944[102] = $_REQUEST['lppf2944'];
	//$aParametros2944[103] = $_REQUEST['bnombre2944'];
	//$aParametros2944[104] = $_REQUEST['blistar2944'];
	list($sTabla2944, $sDebugTabla) = f2944_TablaDetalleV2($aParametros2944, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugTabla;
	//Resultados pruebas
	$aParametros2945[0] = $_REQUEST['visa40id'];
	$aParametros2945[100] = $idTercero;
	$aParametros2945[101] = $_REQUEST['paginaf2945'];
	$aParametros2945[102] = $_REQUEST['lppf2945'];
	//$aParametros2945[103] = $_REQUEST['bnombre2945'];
	//$aParametros2945[104] = $_REQUEST['blistar2945'];
	list($sTabla2945, $sDebugTabla) = f2945_TablaDetalleV2($aParametros2945, $objDB, $bDebug);
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
		document.getElementById('div_sector97').style.display = 'none';
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
			if (idcampo == 'visa40idtercero') {
				params[4] = 'RevisaLlave';
			}
			xajax_unad11_TraerXid(params);
		}
	}

	function imprimelista() {
		if (window.document.frmedita.seg_6.value == 1) {
			window.document.frmlista.consulta.value = window.document.frmedita.consulta_2940.value;
			window.document.frmlista.titulos.value = window.document.frmedita.titulos_2940.value;
			window.document.frmlista.nombrearchivo.value = 'Inscripcion convocatoria';
			window.document.frmlista.submit();
		} else {
			ModalMensaje("<?php echo $ERR['6']; ?>");
		}
	}

	function asignarvariables() {
		window.document.frmimpp.v3.value = window.document.frmedita.bdocumento.value;
		window.document.frmimpp.v4.value = window.document.frmedita.bnombre.value;
		window.document.frmimpp.v5.value = window.document.frmedita.bconvocatoria.value;
		window.document.frmimpp.v6.value = window.document.frmedita.bestado.value;
		window.document.frmimpp.v7.value = window.document.frmedita.btipologia.value;
		window.document.frmimpp.v8.value = window.document.frmedita.bsubtipologia.value;
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
			window.document.frmimpp.action = 'e2940_ss.php';
			window.document.frmimpp.submit();
		} else {
			ModalMensaje(sError);
		}
	}

	function imprimep() {
		if (window.document.frmedita.seg_5.value == 1) {
			asignarvariables();
			window.document.frmimpp.action = 'p2940.php';
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
		datos[1] = window.document.frmedita.visa40idconvocatoria.value;
		datos[2] = window.document.frmedita.visa40idtercero.value;
		if ((datos[1] != '') && (datos[2] != '')) {
			xajax_f2940_ExisteDato(datos);
		}
	}

	function cargadato(llave1, llave2) {
		window.document.frmedita.visa40idconvocatoria.value = String(llave1);
		window.document.frmedita.visa40idtercero.value = String(llave2);
		window.document.frmedita.paso.value = 1;
		window.document.frmedita.submit();
	}

	function cargaridf2940(llave1) {
		window.document.frmedita.visa40id.value = String(llave1);
		window.document.frmedita.paso.value = 3;
		window.document.frmedita.submit();
	}

	function carga_combo_visa40idprograma() {
		let params = new Array();
		params[0] = window.document.frmedita.visa40idescuela.value;
		document.getElementById('div_visa40idprograma').innerHTML = '<b>Procesando datos, por favor espere...</b><input id="visa40idprograma" name="visa40idprograma" type="hidden" value="" />';
		xajax_f2940_Combovisa40idprograma(params);
	}

	function carga_combo_visa40idcentro() {
		let params = new Array();
		params[0] = window.document.frmedita.visa40idzona.value;
		document.getElementById('div_visa40idcentro').innerHTML = '<b>Procesando datos, por favor espere...</b><input id="visa40idcentro" name="visa40idcentro" type="hidden" value="" />';
		xajax_f2940_Combovisa40idcentro(params);
	}

	function carga_combo_visa40idsubtipo() {
		let params = new Array();
		params[0] = window.document.frmedita.visa40idtipologia.value;
		document.getElementById('div_visa40idsubtipo').innerHTML = '<b>Procesando datos, por favor espere...</b><input id="visa40idsubtipo" name="visa40idsubtipo" type="hidden" value="" />';
		xajax_f2940_Combovisa40idsubtipo(params);
	}

	function carga_combo_bsubtipologia() {
		let params = new Array();
		params[0] = window.document.frmedita.btipologia.value;
		document.getElementById('div_bsubtipologia').innerHTML = '<b>Procesando datos, por favor espere...</b><input id="bsubtipologia" name="bsubtipologia" type="hidden" value="" />';
		xajax_f2940_Combobsubtipologia(params);
	}

	function paginarf2940() {
		let params = new Array();
		params[99] = window.document.frmedita.debug.value;
		params[100] = <?php echo $idTercero; ?>;
		params[101] = window.document.frmedita.paginaf2940.value;
		params[102] = window.document.frmedita.lppf2940.value;
		params[103] = window.document.frmedita.bdocumento.value;
		params[104] = window.document.frmedita.bnombre.value;
		params[105] = window.document.frmedita.bconvocatoria.value;
		params[106] = window.document.frmedita.bestado.value;
		params[107] = window.document.frmedita.btipologia.value;
		params[108] = window.document.frmedita.bsubtipologia.value;
		document.getElementById('div_f2940detalle').innerHTML = '<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf2940" name="paginaf2940" type="hidden" value="' + params[101] + '" /><input id="lppf2940" name="lppf2940" type="hidden" value="' + params[102] + '" />';
		xajax_f2940_HtmlTabla(params);
	}

	function enviacerrar() {
		ModalConfirmV2('<?php echo $ETI['msg_cierre2940']; ?>', () => {
			ejecuta_enviacerrar();
		});
	}

	function ejecuta_enviacerrar() {
		MensajeAlarmaV2('<?php echo $ETI['msg_ejecutando']; ?>', 2);
		expandesector(98);
		window.document.frmedita.paso.value = 16;
		window.document.frmedita.submit();
	}

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
		document.getElementById("visa40idconvocatoria").focus();
	}

	function buscarV2016(sCampo) {
		window.document.frmedita.iscroll.value = window.scrollY;
		expandesector(98);
		window.document.frmedita.scampobusca.value = sCampo;
		let params = new Array();
		params[1] = sCampo;
		//params[2] = window.document.frmedita.iagno.value;
		//params[3] = window.document.frmedita.itipo.value;
		xajax_f2940_Busquedas(params);
	}

	function Devuelve(sValor) {
		let sCampo = window.document.frmedita.scampobusca.value;
		if (sCampo == 'visa40idtercero') {
			ter_traerxid('visa40idtercero', sValor);
		}
		if (sCampo == 'visa43usuarioaprueba') {
			ter_traerxid('visa43usuarioaprueba', sValor);
		}
		if (sCampo == 'visa44usuario') {
			ter_traerxid('visa44usuario', sValor);
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
		if (ref == 2943) {
			if (sRetorna != '') {
				window.document.frmedita.visa43idorigen.value = window.document.frmedita.div96v1.value;
				window.document.frmedita.visa43idarchivo.value = sRetorna;
				verboton('beliminavisa43idarchivo', 'block');
			}
			archivo_lnk(window.document.frmedita.visa43idorigen.value, window.document.frmedita.visa43idarchivo.value, 'div_visa43idarchivo');
			paginarf2943();
		}
		MensajeAlarmaV2('', 0);
		retornacontrol();
	}
</script>
<?php
if ($_REQUEST['paso'] != 0) {
?>
<script language="javascript" src="jsi/js2943.js"></script>
<script language="javascript" src="jsi/js2944.js"></script>
<script language="javascript" src="jsi/js2945.js"></script>
<?php
}
?>
<form id="frmimpp" name="frmimpp" method="post" action="p2940.php" target="_blank" style="display:none">
<input id="r" name="r" type="hidden" value="2940" />
<input id="id2940" name="id2940" type="hidden" value="<?php echo $_REQUEST['visa40id']; ?>" />
<input id="v3" name="v3" type="hidden" value="" />
<input id="v4" name="v4" type="hidden" value="" />
<input id="v5" name="v5" type="hidden" value="" />
<input id="v6" name="v6" type="hidden" value="" />
<input id="v7" name="v7" type="hidden" value="" />
<input id="v8" name="v8" type="hidden" value="" />
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
echo $objForma->htmlExpande(2940, $_REQUEST['boculta2940'], $ETI['bt_mostrar'], $ETI['bt_ocultar']);
$sEstiloDiv = '';
if ($_REQUEST['boculta2940'] != 0) {
	$sEstiloDiv = ' style="display:none;"';
}
?>
</div>
<div id="div_p2940"<?php echo $sEstiloDiv; ?>>
<?php
}
//Mostrar formulario para editar
?>
<label class="Label130">
<?php
echo $ETI['visa40idconvocatoria'];
?>
</label>
<label>
<?php
echo $html_visa40idconvocatoria;
?>
</label>
<div class="salto1px"></div>
<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo $ETI['visa40idtercero'];
?>
</label>
<div class="salto1px"></div>
<input id="visa40idtercero" name="visa40idtercero" type="hidden" value="<?php echo $_REQUEST['visa40idtercero']; ?>" />
<div id="div_visa40idtercero_llaves">
<?php
echo $html_visa40idtercero;
?>
</div>
<div class="salto1px"></div>
<div id="div_visa40idtercero" class="L"><?php echo $visa40idtercero_rs; ?></div>
<div class="salto1px"></div>
</div>
<label class="Label60"<?php echo $sOcultaId; ?>>
<?php
echo $ETI['visa40id'];
?>
</label>
<label class="Label60"<?php echo $sOcultaId; ?>>
<?php
	echo html_oculto('visa40id', $_REQUEST['visa40id'], formato_numero($_REQUEST['visa40id']));
?>
</label>
<label class="Label90">
<?php
echo $ETI['visa40estado'];
?>
</label>
<label class="Label220">
<?php
echo $html_visa40estado;
?>
</label>
<label class="Label130">
<?php
echo $ETI['visa40idperiodo'];
?>
</label>
<label>
<?php
echo $html_visa40idperiodo;
?>
</label>
<label class="Label130">
<?php
echo $ETI['visa40idescuela'];
?>
</label>
<label>
<?php
echo $html_visa40idescuela;
?>
</label>
<label class="Label130">
<?php
echo $ETI['visa40idprograma'];
?>
</label>
<label>
<div id="div_visa40idprograma">
<?php
echo $html_visa40idprograma;
?>
</div>
</label>
<label class="Label130">
<?php
echo $ETI['visa40idzona'];
?>
</label>
<label>
<?php
echo $html_visa40idzona;
?>
</label>
<label class="Label130">
<?php
echo $ETI['visa40idcentro'];
?>
</label>
<label>
<div id="div_visa40idcentro">
<?php
echo $html_visa40idcentro;
?>
</div>
</label>
<label class="Label130">
<?php
echo $ETI['visa40fechainsc'];
?>
</label>
<div class="Campo220">
<?php
echo html_FechaEnNumero('visa40fechainsc', $_REQUEST['visa40fechainsc']); //, false, '', $iAgnoIni, $iAgnoFin); //$bvacio, $accion
?>
</div>
<?php
if (false) {
	echo $objForma->htmlBotonSolo('bvisa40fechainsc_hoy', 'btMiniHoy', "fecha_AsignarNum('visa40fechainsc', " . $iHoy . ")", $ETI['bt_hoy']);
}
?>
<label class="Label130">
<?php
echo $ETI['visa40fechaadmision'];
?>
</label>
<div class="Campo220">
<?php
echo html_FechaEnNumero('visa40fechaadmision', $_REQUEST['visa40fechaadmision']); //, false, '', $iAgnoIni, $iAgnoFin); //$bvacio, $accion
?>
</div>
<?php
if (false) {
	echo $objForma->htmlBotonSolo('bvisa40fechaadmision_hoy', 'btMiniHoy', "fecha_AsignarNum('visa40fechaadmision', " . $iHoy . ")", $ETI['bt_hoy']);
}
?>
<label class="Label130">
<?php
echo $ETI['visa40numcupo'];
?>
</label>
<label class="Label130">
<div id="div_visa40numcupo">
<?php
echo html_oculto('visa40numcupo', $_REQUEST['visa40numcupo']);
?>
</div>
</label>
<label class="Label130">
<?php
echo $ETI['visa40idtipologia'];
?>
</label>
<label>
<?php
echo $html_visa40idtipologia;
?>
</label>
<label class="Label130">
<?php
echo $ETI['visa40idsubtipo'];
?>
</label>
<label>
<div id="div_visa40idsubtipo">
<?php
echo $html_visa40idsubtipo;
?>
</div>
</label>
<input id="visa40idminuta" name="visa40idminuta" type="hidden" value="<?php echo $_REQUEST['visa40idminuta']; ?>" />
<input id="visa40idresolucion" name="visa40idresolucion" type="hidden" value="<?php echo $_REQUEST['visa40idresolucion']; ?>" />
<?php
// -- Inicia Grupo campos 2943 Anexos
?>
<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo $ETI['titulo_2943'];
?>
</label>
<?php
if ($_REQUEST['paso'] == 2) {
	if ($bEdita2943) {
?>
<input id="boculta2943" name="boculta2943" type="hidden" value="<?php echo $_REQUEST['boculta2943']; ?>" />
<div class="ir_derecha"<?php echo $sAnchoExpandeContrae; ?>>
<?php
if (false) {
?>
<label class="Label30">
<input id="btexcel2943" name="btexcel2943" type="button" value="Exportar" class="btMiniExcel" onclick="imprime2943();" title="Exportar" />
</label>
<?php
}
echo $objForma->htmlExpande(2943, $_REQUEST['boculta2943'], $ETI['bt_mostrar'], $ETI['bt_ocultar']);
$sEstiloDiv = '';
if ($_REQUEST['boculta2943'] != 0) {
	$sEstiloDiv = ' style="display:none;"';
}
?>
</div>
<div class="salto1px"></div>
<div id="div_p2943"<?php echo $sEstiloDiv; ?>>
<label class="Label130">
<?php
echo $ETI['visa43iddocumento'];
?>
</label>
<label>
<div id="div_visa43iddocumento">
<?php
echo $html_visa43iddocumento;
?>
</div>
</label>
<label class="Label60"<?php echo $sOcultaId; ?>>
<?php
echo $ETI['visa43id'];
?>
</label>
<label class="Label60"<?php echo $sOcultaId; ?>>
<div id="div_visa43id">
<?php
	echo html_oculto('visa43id', $_REQUEST['visa43id'], formato_numero($_REQUEST['visa43id']));
?>
</div>
</label>
<input id="visa43idorigen" name="visa43idorigen" type="hidden" value="<?php echo $_REQUEST['visa43idorigen']; ?>" />
<input id="visa43idarchivo" name="visa43idarchivo" type="hidden" value="<?php echo $_REQUEST['visa43idarchivo']; ?>" />
<input id="visa43idarchivo_up" name="visa43idarchivo_up" type="hidden" value="<?php echo html_lnkupload(2943, $_REQUEST['visa43id']); ?>" />
<div class="GrupoCampos300">
<div class="salto1px"></div>
<div id="div_visa43idarchivo" class="Campo300">
<?php
echo html_lnkarchivo((int)$_REQUEST['visa43idorigen'], (int)$_REQUEST['visa43idarchivo']);
?>
</div>
<?php
$sEstiloAnexa = '';
$sEstiloElimina = ' style="display:none;"';
if ((int)$_REQUEST['visa43id'] == 0) {
	$sEstiloAnexa = ' style="display:none;"';
}
if ((int)$_REQUEST['visa43idarchivo'] != 0) {
	$sEstiloElimina = '';
}
echo $objForma->htmlBotonSolo('banexavisa43idarchivo', 'btMiniAnexar', 'carga_visa43idarchivo(window.document.frmedita.visa43idarchivo_up.value)', $ETI['bt_mini_cargararchivo'], 30, $sEstiloAnexa);
echo $objForma->htmlBotonSolo('beliminavisa43idarchivo', 'btMiniEliminar', 'eliminavisa43idarchivo()', $ETI['bt_mini_eliminararchivo'], 30, $sEstiloElimina);
?>
<div class="salto1px"></div>
</div>
<label class="Label130">
<?php
echo $ETI['visa43fechaaprob'];
?>
</label>
<label class="Label220">
<div id="div_visa43fechaaprob">
<?php
echo html_oculto('visa43fechaaprob', $_REQUEST['visa43fechaaprob'], fecha_desdenumero($_REQUEST['visa43fechaaprob'])); //formato_FechaLargaDesdeNumero
?>
</div>
</label>
<div class="salto1px"></div>
<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo $ETI['visa43usuarioaprueba'];
?>
</label>
<div class="salto1px"></div>
<input id="visa43usuarioaprueba" name="visa43usuarioaprueba" type="hidden" value="<?php echo $_REQUEST['visa43usuarioaprueba']; ?>" />
<div id="div_visa43usuarioaprueba_llaves">
<?php
echo $html_visa43usuarioaprueba;
?>
</div>
<div class="salto1px"></div>
<div id="div_visa43usuarioaprueba" class="L"><?php echo $visa43usuarioaprueba_rs; ?></div>
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
<label class="Label130">&nbsp;</label>
<?php
$sEstiloElimina = 'display:none;';
if ((int)$_REQUEST['visa43id'] != 0) {
	$sEstiloElimina = 'inline-block;';
}
echo $objForma->htmlBotonSolo('bguarda2943', 'btMiniGuardar', 'guardaf2943()', $ETI['bt_mini_guardar_2943'], 30);
echo $objForma->htmlBotonSolo('blimpia2943', 'btMiniLimpiar', 'limpiaf2943()', $ETI['bt_mini_limpiar_2943'], 30);
echo $objForma->htmlBotonSolo('belimina2943', 'btMiniEliminar', 'eliminaf2943()', $ETI['bt_mini_eliminar_2943'], 30, $sEstiloElimina);
//Este es el cierre del div_p2943
?>
<div class="salto1px"></div>
</div>
<?php
		} //Termina el segundo bloque  condicional - bloque editar.
?>
<div class="salto1px"></div>
<?php
if (false) {
?>
<div class="ir_derecha GrupoCamposAyuda">
<label class="Label130">
<?php
echo $ETI['msg_nombre'];
?>
</label>
<label>
<input id="bnombre2943" name="bnombre2943" type="text" value="<?php echo $_REQUEST['bnombre2943']; ?>" onchange="paginarf2943()" />
</label>
<label class="Label130">
<?php
echo $ETI['msg_Listar'];
?>
</label>
<label>
<?php
echo $html_blistar2943;
?>
</label>
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
<?php
}
?>
<div id="div_f2943detalle">
<?php
echo $sTabla2943;
?>
</div>
<?php
}
?>
<div class="salto1px"></div>
</div>
<?php
// -- Termina Grupo campos 2943 Anexos
?>
<?php
// -- Inicia Grupo campos 2944 Anotaciones
?>
<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo $ETI['titulo_2944'];
?>
</label>
<?php
if ($_REQUEST['paso'] == 2) {
	if ($bEdita2944) {
?>
<input id="boculta2944" name="boculta2944" type="hidden" value="<?php echo $_REQUEST['boculta2944']; ?>" />
<div class="ir_derecha"<?php echo $sAnchoExpandeContrae; ?>>
<?php
if (false) {
?>
<label class="Label30">
<input id="btexcel2944" name="btexcel2944" type="button" value="Exportar" class="btMiniExcel" onclick="imprime2944();" title="Exportar" />
</label>
<?php
}
echo $objForma->htmlExpande(2944, $_REQUEST['boculta2944'], $ETI['bt_mostrar'], $ETI['bt_ocultar']);
$sEstiloDiv = '';
if ($_REQUEST['boculta2944'] != 0) {
	$sEstiloDiv = ' style="display:none;"';
}
?>
</div>
<div class="salto1px"></div>
<div id="div_p2944"<?php echo $sEstiloDiv; ?>>
<label class="Label130"<?php echo $sOcultaConsec; ?>>
<?php
echo $ETI['visa44consec'];
?>
</label>
<label class="Label130"<?php echo $sOcultaConsec; ?>>
<div id="div_visa44consec">
<?php
if ((int)$_REQUEST['visa44id'] == 0) {
?>
<input id="visa44consec" name="visa44consec" type="text" value="<?php echo $_REQUEST['visa44consec']; ?>" onchange="revisaf2944()" class="cuatro" />
<?php
} else {
	echo html_oculto('visa44consec', $_REQUEST['visa44consec'], formato_numero($_REQUEST['visa44consec']));
}
?>
</div>
</label>
<label class="Label60"<?php echo $sOcultaId; ?>>
<?php
echo $ETI['visa44id'];
?>
</label>
<label class="Label60"<?php echo $sOcultaId; ?>>
<div id="div_visa44id">
<?php
	echo html_oculto('visa44id', $_REQUEST['visa44id'], formato_numero($_REQUEST['visa44id']));
?>
</div>
</label>
<label class="Label130">
<?php
echo $ETI['visa44alcance'];
?>
</label>
<label class="Label130">

<input id="visa44alcance" name="visa44alcance" type="text" value="<?php echo $_REQUEST['visa44alcance']; ?>" class="diez" maxlength="10" placeholder="<?php echo $ETI['ing_vr']; ?>" />
</label>
<label class="txtAreaS">
<?php
echo $ETI['visa44nota'];
?>
<textarea id="visa44nota" name="visa44nota" placeholder="<?php echo $ETI['ing_campo'] . $ETI['visa44nota']; ?>"><?php echo $_REQUEST['visa44nota']; ?></textarea>
</label>
<div class="salto1px"></div>
<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo $ETI['visa44usuario'];
?>
</label>
<div class="salto1px"></div>
<input id="visa44usuario" name="visa44usuario" type="hidden" value="<?php echo $_REQUEST['visa44usuario']; ?>" />
<div id="div_visa44usuario_llaves">
<?php
echo $html_visa44usuario;
?>
</div>
<div class="salto1px"></div>
<div id="div_visa44usuario" class="L"><?php echo $visa44usuario_rs; ?></div>
<div class="salto1px"></div>
</div>
<label class="Label130">
<?php
echo $ETI['visa44fecha'];
?>
</label>
<label class="Label220">
<div id="div_visa44fecha">
<?php
echo html_oculto('visa44fecha', $_REQUEST['visa44fecha'], fecha_desdenumero($_REQUEST['visa44fecha'])); //formato_FechaLargaDesdeNumero
?>
</div>
</label>
<label class="Label130">
<?php
echo $ETI['visa44hora'];
?>
</label>
<div class="campo_HoraMin" id="div_visa44hora">
<?php
echo html_HoraMin('visa44hora', $_REQUEST['visa44hora'], 'visa44minuto', $_REQUEST['visa44minuto'], true);
?>
</div>
<div class="salto1px"></div>
<label class="Label130">&nbsp;</label>
<?php
$sEstiloElimina = 'display:none;';
if ((int)$_REQUEST['visa44id'] != 0) {
	$sEstiloElimina = 'inline-block;';
}
echo $objForma->htmlBotonSolo('bguarda2944', 'btMiniGuardar', 'guardaf2944()', $ETI['bt_mini_guardar_2944'], 30);
echo $objForma->htmlBotonSolo('blimpia2944', 'btMiniLimpiar', 'limpiaf2944()', $ETI['bt_mini_limpiar_2944'], 30);
echo $objForma->htmlBotonSolo('belimina2944', 'btMiniEliminar', 'eliminaf2944()', $ETI['bt_mini_eliminar_2944'], 30, $sEstiloElimina);
//Este es el cierre del div_p2944
?>
<div class="salto1px"></div>
</div>
<?php
		} //Termina el segundo bloque  condicional - bloque editar.
?>
<div class="salto1px"></div>
<?php
if (false) {
?>
<div class="ir_derecha GrupoCamposAyuda">
<label class="Label130">
<?php
echo $ETI['msg_nombre'];
?>
</label>
<label>
<input id="bnombre2944" name="bnombre2944" type="text" value="<?php echo $_REQUEST['bnombre2944']; ?>" onchange="paginarf2944()" />
</label>
<label class="Label130">
<?php
echo $ETI['msg_Listar'];
?>
</label>
<label>
<?php
echo $html_blistar2944;
?>
</label>
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
<?php
}
?>
<div id="div_f2944detalle">
<?php
echo $sTabla2944;
?>
</div>
<?php
}
?>
<div class="salto1px"></div>
</div>
<?php
// -- Termina Grupo campos 2944 Anotaciones
?>
<?php
// -- Inicia Grupo campos 2945 Resultados pruebas
?>
<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo $ETI['titulo_2945'];
?>
</label>
<?php
if ($_REQUEST['paso'] == 2) {
	if ($bEdita2945) {
?>
<input id="boculta2945" name="boculta2945" type="hidden" value="<?php echo $_REQUEST['boculta2945']; ?>" />
<div class="ir_derecha"<?php echo $sAnchoExpandeContrae; ?>>
<?php
if (false) {
?>
<label class="Label30">
<input id="btexcel2945" name="btexcel2945" type="button" value="Exportar" class="btMiniExcel" onclick="imprime2945();" title="Exportar" />
</label>
<?php
}
echo $objForma->htmlExpande(2945, $_REQUEST['boculta2945'], $ETI['bt_mostrar'], $ETI['bt_ocultar']);
$sEstiloDiv = '';
if ($_REQUEST['boculta2945'] != 0) {
	$sEstiloDiv = ' style="display:none;"';
}
?>
</div>
<div class="salto1px"></div>
<div id="div_p2945"<?php echo $sEstiloDiv; ?>>
<label class="Label130">
<?php
echo $ETI['visa45idprueba'];
?>
</label>
<label>
<div id="div_visa45idprueba">
<?php
echo $html_visa45idprueba;
?>
</div>
</label>
<label class="Label60"<?php echo $sOcultaId; ?>>
<?php
echo $ETI['visa45id'];
?>
</label>
<label class="Label60"<?php echo $sOcultaId; ?>>
<div id="div_visa45id">
<?php
	echo html_oculto('visa45id', $_REQUEST['visa45id'], formato_numero($_REQUEST['visa45id']));
?>
</div>
</label>
<label class="Label130">
<?php
echo $ETI['visa45puntaje'];
?>
</label>
<label class="Label130">

<input id="visa45puntaje" name="visa45puntaje" type="text" value="<?php echo $_REQUEST['visa45puntaje']; ?>" class="diez" maxlength="10" placeholder="<?php echo $ETI['ing_vr']; ?>" />
</label>
<div class="salto1px"></div>
<label class="Label130">&nbsp;</label>
<?php
$sEstiloElimina = 'display:none;';
if ((int)$_REQUEST['visa45id'] != 0) {
	$sEstiloElimina = 'inline-block;';
}
echo $objForma->htmlBotonSolo('bguarda2945', 'btMiniGuardar', 'guardaf2945()', $ETI['bt_mini_guardar_2945'], 30);
echo $objForma->htmlBotonSolo('blimpia2945', 'btMiniLimpiar', 'limpiaf2945()', $ETI['bt_mini_limpiar_2945'], 30);
echo $objForma->htmlBotonSolo('belimina2945', 'btMiniEliminar', 'eliminaf2945()', $ETI['bt_mini_eliminar_2945'], 30, $sEstiloElimina);
//Este es el cierre del div_p2945
?>
<div class="salto1px"></div>
</div>
<?php
		} //Termina el segundo bloque  condicional - bloque editar.
?>
<div class="salto1px"></div>
<?php
if (false) {
?>
<div class="ir_derecha GrupoCamposAyuda">
<label class="Label130">
<?php
echo $ETI['msg_nombre'];
?>
</label>
<label>
<input id="bnombre2945" name="bnombre2945" type="text" value="<?php echo $_REQUEST['bnombre2945']; ?>" onchange="paginarf2945()" />
</label>
<label class="Label130">
<?php
echo $ETI['msg_Listar'];
?>
</label>
<label>
<?php
echo $html_blistar2945;
?>
</label>
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
<?php
}
?>
<div id="div_f2945detalle">
<?php
echo $sTabla2945;
?>
</div>
<?php
}
?>
<div class="salto1px"></div>
</div>
<?php
// -- Termina Grupo campos 2945 Resultados pruebas
?>
<?php
if (false) {
	//Ejemplo de boton de ayuda
	//echo html_BotonAyuda('NombreCampo');
	//echo html_DivAyudaLocal('NombreCampo');
}
if ($bConExpande) {
	//Este es el cierre del div_p2940
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
<label class="Label90">
<?php
echo $ETI['msg_bdocumento'];
?>
</label>
<label>
<input id="bdocumento" name="bdocumento" type="text" value="<?php echo $_REQUEST['bdocumento']; ?>" onchange="paginarf2940()" autocomplete="off" />
</label>
<div class="salto1px"></div>
<label class="Label90">
<?php
echo $ETI['msg_bnombre'];
?>
</label>
<label>
<input id="bnombre" name="bnombre" type="text" value="<?php echo $_REQUEST['bnombre']; ?>" onchange="paginarf2940()" autocomplete="off" />
</label>
<div class="salto1px"></div>
<label class="Label90">
<?php
echo $ETI['msg_bconvocatoria'];
?>
</label>
<label>
<?php
echo $html_bconvocatoria;
?>
</label>
<div class="salto1px"></div>
<label class="Label90">
<?php
echo $ETI['msg_bestado'];
?>
</label>
<label>
<?php
echo $html_bestado;
?>
</label>
<div class="salto1px"></div>
<label class="Label90">
<?php
echo $ETI['msg_btipologia'];
?>
</label>
<label>
<?php
echo $html_btipologia;
?>
</label>
<div class="salto1px"></div>
<label class="Label90">
<?php
echo $ETI['msg_bsubtipologia'];
?>
</label>
<label>
<div id="div_bsubtipologia">
<?php
echo $html_bsubtipologia;
?>
</div>
</label>
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
<?php
echo ' ' . $csv_separa;
?>
<div class="salto1px"></div>
<div id="div_f2940detalle">
<?php
echo $sTabla2940;
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
<input id="titulo_2940" name="titulo_2940" type="hidden" value="<?php echo $sTituloModulo; ?>" />
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
<div class="areaform">
<div class="areatrabajo">
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
<?php
if (false) {
//}
//if ($_REQUEST['paso'] == 0) {
?>
<script language="javascript">
	$().ready(function() {
		$("#visa40idconvocatoria").chosen({width:"100%"});
		$("#visa40idperiodo").chosen({width:"100%"});
		$("#visa40idescuela").chosen({width:"100%"});
		$("#visa40idprograma").chosen({width:"100%"});
		$("#visa40idzona").chosen({width:"100%"});
		$("#visa40idcentro").chosen({width:"100%"});
		$("#visa40idtipologia").chosen({width:"100%"});
		$("#visa40idsubtipo").chosen({width:"100%"});
		$("#bconvocatoria").chosen({width:"100%"});
		$("#bestado").chosen({width:"100%"});
		$("#btipologia").chosen({width:"100%"});
		$("#bsubtipologia").chosen({width:"100%"});
	});
</script>
<?php
}
?>
<script language="javascript" src="ac_2940.js"></script>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>unad_todas2024v2.js"></script>
<?php
forma_piedepagina();

