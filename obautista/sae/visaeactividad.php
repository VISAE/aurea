<?php
/*
--- © Juan David Avellaneda Molina - UNAD - 2026 ---
--- juand.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 3.2.5 miércoles, 16 de septiembre de 2026
*/
/** Archivo visaeactividad.php.
 * Modulo 2957 visa57actividad.
 * @author Juan David Avellaneda Molina - juand.avellaneda@unad.edu.co
 * @param debug = 1  (Opcional), bandera para indicar si se generan datos de depuración
 * @date miércoles, 16 de septiembre de 2026
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
require $APP->rutacomun . 'libcombos.php';
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
$iMinVerDB = 10005;
$iCodModulo = 2957;
$iCodModuloConsulta = $iCodModulo;
$sIdioma = AUREA_Idioma();
$audita[1] = false;
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
$mensajes_2957 = 'lg/lg_2957_' . $sIdioma . '.php';
if (!file_exists($mensajes_2957)) {
	$mensajes_2957 = 'lg/lg_2957_es.php';
}
require $mensajes_todas;
require $mensajes_2957;
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
$sTituloModulo = $ETI['titulo_2957'];
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
		header('Location:noticia.php?ret=visaeactividad.php');
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
			$sError = $ETI['msg_doc_no_encontrado'] . ' &quot;' . $_REQUEST['deb_tipodoc'] . ' ' . $_REQUEST['deb_doc'] . '&quot;';
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
$mensajes_2958 = 'lg/lg_2958_' . $sIdioma . '.php';
if (!file_exists($mensajes_2958)) {
	$mensajes_2958 = 'lg/lg_2958_es.php';
}
$mensajes_2959 = 'lg/lg_2959_' . $sIdioma . '.php';
if (!file_exists($mensajes_2959)) {
	$mensajes_2959 = 'lg/lg_2959_es.php';
}
$mensajes_2960 = 'lg/lg_2960_' . $sIdioma . '.php';
if (!file_exists($mensajes_2960)) {
	$mensajes_2960 = 'lg/lg_2960_es.php';
}
$mensajes_2961 = 'lg/lg_2961_' . $sIdioma . '.php';
if (!file_exists($mensajes_2961)) {
	$mensajes_2961 = 'lg/lg_2961_es.php';
}
$mensajes_2962 = 'lg/lg_2962_' . $sIdioma . '.php';
if (!file_exists($mensajes_2962)) {
	$mensajes_2962 = 'lg/lg_2962_es.php';
}
$mensajes_2963 = 'lg/lg_2963_' . $sIdioma . '.php';
if (!file_exists($mensajes_2963)) {
	$mensajes_2963 = 'lg/lg_2963_es.php';
}
require $mensajes_2958;
require $mensajes_2959;
require $mensajes_2960;
require $mensajes_2961;
require $mensajes_2962;
require $mensajes_2963;
// -- Si esta cargando la pagina por primer vez se revisa si requiere auditar y se manda a hacer un limpiar (paso -1)
if (isset($_REQUEST['paso']) == 0) {
	$_REQUEST['paso'] = -1;
	if ($audita[1]) {
		seg_auditaingreso($iCodModulo, $_SESSION['unad_id_tercero'], $objDB);
	}
}
// -- 2957 visa57actividad
require 'lib2957.php';
// -- 2958 Resultados
require 'lib2958.php';
// -- 2959 Evidencias
require 'lib2959.php';
// -- 2960 Reprogramación
require 'lib2960.php';
// -- 2961 Dificultades
require 'lib2961.php';
// -- 2962 Compromisos
require 'lib2962.php';
// -- 2963 Solicitud de apoyo
require 'lib2963.php';
$xajax = new xajax();
$xajax->configure('javascript URI', $APP->rutacomun . 'xajax/');
$xajax->register(XAJAX_FUNCTION, 'unad11_Mostrar_v2');
$xajax->register(XAJAX_FUNCTION, 'unad11_TraerXid');
$xajax->register(XAJAX_FUNCTION, 'formatear_moneda');
$xajax->register(XAJAX_FUNCTION, 'sesion_abandona_V2');
$xajax->register(XAJAX_FUNCTION, 'sesion_mantenerV4');
$xajax->register(XAJAX_FUNCTION, 'f2957_HtmlTabla');
$xajax->register(XAJAX_FUNCTION, 'f2957_ExisteDato');
$xajax->register(XAJAX_FUNCTION, 'f2957_Busquedas');
$xajax->register(XAJAX_FUNCTION, 'f2957_HtmlBusqueda');
$xajax->register(XAJAX_FUNCTION, 'f2958_Guardar');
$xajax->register(XAJAX_FUNCTION, 'f2958_Traer');
$xajax->register(XAJAX_FUNCTION, 'f2958_Eliminar');
$xajax->register(XAJAX_FUNCTION, 'f2958_HtmlTabla');
$xajax->register(XAJAX_FUNCTION, 'f2958_PintarLlaves');
$xajax->register(XAJAX_FUNCTION, 'elimina_archivo_visa59idarchivo');
$xajax->register(XAJAX_FUNCTION, 'f2959_Guardar');
$xajax->register(XAJAX_FUNCTION, 'f2959_Traer');
$xajax->register(XAJAX_FUNCTION, 'f2959_Eliminar');
$xajax->register(XAJAX_FUNCTION, 'f2959_HtmlTabla');
$xajax->register(XAJAX_FUNCTION, 'f2959_PintarLlaves');
$xajax->register(XAJAX_FUNCTION, 'f2960_Guardar');
$xajax->register(XAJAX_FUNCTION, 'f2960_Traer');
$xajax->register(XAJAX_FUNCTION, 'f2960_Eliminar');
$xajax->register(XAJAX_FUNCTION, 'f2960_HtmlTabla');
$xajax->register(XAJAX_FUNCTION, 'f2960_PintarLlaves');
$xajax->register(XAJAX_FUNCTION, 'f2961_Guardar');
$xajax->register(XAJAX_FUNCTION, 'f2961_Traer');
$xajax->register(XAJAX_FUNCTION, 'f2961_Eliminar');
$xajax->register(XAJAX_FUNCTION, 'f2961_HtmlTabla');
$xajax->register(XAJAX_FUNCTION, 'f2961_PintarLlaves');
$xajax->register(XAJAX_FUNCTION, 'f2962_Guardar');
$xajax->register(XAJAX_FUNCTION, 'f2962_Traer');
$xajax->register(XAJAX_FUNCTION, 'f2962_Eliminar');
$xajax->register(XAJAX_FUNCTION, 'f2962_HtmlTabla');
$xajax->register(XAJAX_FUNCTION, 'f2962_PintarLlaves');
$xajax->register(XAJAX_FUNCTION, 'f2963_Guardar');
$xajax->register(XAJAX_FUNCTION, 'f2963_Traer');
$xajax->register(XAJAX_FUNCTION, 'f2963_Eliminar');
$xajax->register(XAJAX_FUNCTION, 'f2963_HtmlTabla');
$xajax->register(XAJAX_FUNCTION, 'f2963_PintarLlaves');
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
if (isset($_REQUEST['paginaf2957']) == 0) {
	$_REQUEST['paginaf2957'] = 1;
}
if (isset($_REQUEST['lppf2957']) == 0) {
	$_REQUEST['lppf2957'] = 20;
}
if (isset($_REQUEST['boculta2957']) == 0) {
	$_REQUEST['boculta2957'] = 0;
}
// -- Inicializar variables de datos.
if (isset($_REQUEST['visa57idpersemanal']) == 0) {
	$_REQUEST['visa57idpersemanal'] = '';
}
if (isset($_REQUEST['visa57idsistema']) == 0) {
	$_REQUEST['visa57idsistema'] = '';
}
if (isset($_REQUEST['visa57consec']) == 0) {
	$_REQUEST['visa57consec'] = '';
}
if (isset($_REQUEST['visa57consec_nuevo']) == 0) {
	$_REQUEST['visa57consec_nuevo'] = '';
}
if (isset($_REQUEST['visa57id']) == 0) {
	$_REQUEST['visa57id'] = '';
}
if (isset($_REQUEST['visa57titulo']) == 0) {
	$_REQUEST['visa57titulo'] = '';
}
if (isset($_REQUEST['visa57descripcion']) == 0) {
	$_REQUEST['visa57descripcion'] = '';
}
if (isset($_REQUEST['visa57tipoactividad']) == 0) {
	$_REQUEST['visa57tipoactividad'] = 0;
}
if (isset($_REQUEST['visa57estado']) == 0) {
	$_REQUEST['visa57estado'] = 0;
}
if (isset($_REQUEST['visa57prioridad']) == 0) {
	$_REQUEST['visa57prioridad'] = 0;
}
if (isset($_REQUEST['visa57fechaprogini']) == 0) {
	$_REQUEST['visa57fechaprogini'] = '';
	//$_REQUEST['visa57fechaprogini'] = $iHoy;
}
if (isset($_REQUEST['visa57fechaprogfin']) == 0) {
	$_REQUEST['visa57fechaprogfin'] = '';
	//$_REQUEST['visa57fechaprogfin'] = $iHoy;
}
if (isset($_REQUEST['visa57fechaejecini']) == 0) {
	$_REQUEST['visa57fechaejecini'] = '';
	//$_REQUEST['visa57fechaejecini'] = $iHoy;
}
if (isset($_REQUEST['visa57fechaejecfin']) == 0) {
	$_REQUEST['visa57fechaejecfin'] = '';
	//$_REQUEST['visa57fechaejecfin'] = $iHoy;
}
if (isset($_REQUEST['visa57porcavance']) == 0) {
	$_REQUEST['visa57porcavance'] = '';
}
if (isset($_REQUEST['visa57fechacrea']) == 0) {
	$_REQUEST['visa57fechacrea'] = '';
	//$_REQUEST['visa57fechacrea'] = $iHoy;
}
if (isset($_REQUEST['visa57fechaactualiza']) == 0) {
	$_REQUEST['visa57fechaactualiza'] = '';
	//$_REQUEST['visa57fechaactualiza'] = $iHoy;
}
$_REQUEST['visa57idpersemanal'] = numeros_validar($_REQUEST['visa57idpersemanal']);
$_REQUEST['visa57idsistema'] = numeros_validar($_REQUEST['visa57idsistema']);
$_REQUEST['visa57consec'] = numeros_validar($_REQUEST['visa57consec']);
$_REQUEST['visa57id'] = numeros_validar($_REQUEST['visa57id']);
$_REQUEST['visa57titulo'] = cadena_Validar($_REQUEST['visa57titulo']);
$_REQUEST['visa57descripcion'] = cadena_Validar($_REQUEST['visa57descripcion']);
$_REQUEST['visa57tipoactividad'] = numeros_validar($_REQUEST['visa57tipoactividad']);
$_REQUEST['visa57estado'] = numeros_validar($_REQUEST['visa57estado']);
$_REQUEST['visa57prioridad'] = numeros_validar($_REQUEST['visa57prioridad']);
$_REQUEST['visa57fechaprogini'] = numeros_validar($_REQUEST['visa57fechaprogini']);
$_REQUEST['visa57fechaprogfin'] = numeros_validar($_REQUEST['visa57fechaprogfin']);
$_REQUEST['visa57fechaejecini'] = numeros_validar($_REQUEST['visa57fechaejecini']);
$_REQUEST['visa57fechaejecfin'] = numeros_validar($_REQUEST['visa57fechaejecfin']);
$_REQUEST['visa57porcavance'] = numeros_validar($_REQUEST['visa57porcavance'], true, 2);
$_REQUEST['visa57fechacrea'] = numeros_validar($_REQUEST['visa57fechacrea']);
$_REQUEST['visa57fechaactualiza'] = numeros_validar($_REQUEST['visa57fechaactualiza']);
if ((int)$_REQUEST['paso'] > 0) {
	//Resultados
	if (isset($_REQUEST['paginaf2958']) == 0) {
		$_REQUEST['paginaf2958'] = 1;
	}
	if (isset($_REQUEST['lppf2958']) == 0) {
		$_REQUEST['lppf2958'] = 20;
	}
	if (isset($_REQUEST['boculta2958']) == 0) {
		$_REQUEST['boculta2958'] = 0;
	}
	if (isset($_REQUEST['visa58idactividad']) == 0) {
		$_REQUEST['visa58idactividad'] = '';
	}
	if (isset($_REQUEST['visa58consec']) == 0) {
		$_REQUEST['visa58consec'] = '';
	}
	if (isset($_REQUEST['visa58id']) == 0) {
		$_REQUEST['visa58id'] = '';
	}
	if (isset($_REQUEST['visa58descripcion']) == 0) {
		$_REQUEST['visa58descripcion'] = '';
	}
	if (isset($_REQUEST['visa58cumplimiento']) == 0) {
		$_REQUEST['visa58cumplimiento'] = 0;
	}
	if (isset($_REQUEST['visa58fecharegistro']) == 0) {
		$_REQUEST['visa58fecharegistro'] = '';
		//$_REQUEST['visa58fecharegistro'] = $iHoy;
	}
	$_REQUEST['visa58idactividad'] = numeros_validar($_REQUEST['visa58idactividad']);
	$_REQUEST['visa58consec'] = numeros_validar($_REQUEST['visa58consec']);
	$_REQUEST['visa58id'] = numeros_validar($_REQUEST['visa58id']);
	$_REQUEST['visa58descripcion'] = cadena_Validar($_REQUEST['visa58descripcion']);
	$_REQUEST['visa58cumplimiento'] = numeros_validar($_REQUEST['visa58cumplimiento']);
	$_REQUEST['visa58fecharegistro'] = numeros_validar($_REQUEST['visa58fecharegistro']);
	//Evidencias
	if (isset($_REQUEST['paginaf2959']) == 0) {
		$_REQUEST['paginaf2959'] = 1;
	}
	if (isset($_REQUEST['lppf2959']) == 0) {
		$_REQUEST['lppf2959'] = 20;
	}
	if (isset($_REQUEST['boculta2959']) == 0) {
		$_REQUEST['boculta2959'] = 0;
	}
	if (isset($_REQUEST['visa59idactividad']) == 0) {
		$_REQUEST['visa59idactividad'] = '';
	}
	if (isset($_REQUEST['visa59consec']) == 0) {
		$_REQUEST['visa59consec'] = '';
	}
	if (isset($_REQUEST['visa59id']) == 0) {
		$_REQUEST['visa59id'] = '';
	}
	if (isset($_REQUEST['visa59titulo']) == 0) {
		$_REQUEST['visa59titulo'] = '';
	}
	if (isset($_REQUEST['visa59idorigen']) == 0) {
		$_REQUEST['visa59idorigen'] = 0;
	}
	if (isset($_REQUEST['visa59idarchivo']) == 0) {
		$_REQUEST['visa59idarchivo'] = 0;
	}
	if (isset($_REQUEST['visa59tipoarchivo']) == 0) {
		$_REQUEST['visa59tipoarchivo'] = 0;
	}
	if (isset($_REQUEST['visa59descripcion']) == 0) {
		$_REQUEST['visa59descripcion'] = '';
	}
	if (isset($_REQUEST['visa59fechacarga']) == 0) {
		$_REQUEST['visa59fechacarga'] = '';
		//$_REQUEST['visa59fechacarga'] = $iHoy;
	}
	if (isset($_REQUEST['visa59idusuario']) == 0) {
		$_REQUEST['visa59idusuario'] = 0;
		//$_REQUEST['visa59idusuario'] =  $idTercero;
	}
	if (isset($_REQUEST['visa59idusuario_td']) == 0) {
		$_REQUEST['visa59idusuario_td'] = $APP->tipo_doc;
	}
	if (isset($_REQUEST['visa59idusuario_doc']) == 0) {
		$_REQUEST['visa59idusuario_doc'] = '';
	}
	$_REQUEST['visa59idactividad'] = numeros_validar($_REQUEST['visa59idactividad']);
	$_REQUEST['visa59consec'] = numeros_validar($_REQUEST['visa59consec']);
	$_REQUEST['visa59id'] = numeros_validar($_REQUEST['visa59id']);
	$_REQUEST['visa59titulo'] = cadena_Validar($_REQUEST['visa59titulo']);
	$_REQUEST['visa59idorigen'] = numeros_validar($_REQUEST['visa59idorigen']);
	$_REQUEST['visa59idarchivo'] = numeros_validar($_REQUEST['visa59idarchivo']);
	$_REQUEST['visa59tipoarchivo'] = numeros_validar($_REQUEST['visa59tipoarchivo']);
	$_REQUEST['visa59descripcion'] = cadena_Validar($_REQUEST['visa59descripcion']);
	$_REQUEST['visa59fechacarga'] = numeros_validar($_REQUEST['visa59fechacarga']);
	$_REQUEST['visa59idusuario'] = numeros_validar($_REQUEST['visa59idusuario']);
	$_REQUEST['visa59idusuario_td'] = cadena_Validar($_REQUEST['visa59idusuario_td']);
	$_REQUEST['visa59idusuario_doc'] = cadena_Validar($_REQUEST['visa59idusuario_doc']);
	//Reprogramación
	if (isset($_REQUEST['paginaf2960']) == 0) {
		$_REQUEST['paginaf2960'] = 1;
	}
	if (isset($_REQUEST['lppf2960']) == 0) {
		$_REQUEST['lppf2960'] = 20;
	}
	if (isset($_REQUEST['boculta2960']) == 0) {
		$_REQUEST['boculta2960'] = 0;
	}
	if (isset($_REQUEST['visa60idactividad']) == 0) {
		$_REQUEST['visa60idactividad'] = '';
	}
	if (isset($_REQUEST['visa60consec']) == 0) {
		$_REQUEST['visa60consec'] = '';
	}
	if (isset($_REQUEST['visa60id']) == 0) {
		$_REQUEST['visa60id'] = '';
	}
	if (isset($_REQUEST['visa60fechareproini']) == 0) {
		$_REQUEST['visa60fechareproini'] = '';
		//$_REQUEST['visa60fechareproini'] = $iHoy;
	}
	if (isset($_REQUEST['visa60fechareprofin']) == 0) {
		$_REQUEST['visa60fechareprofin'] = '';
		//$_REQUEST['visa60fechareprofin'] = $iHoy;
	}
	if (isset($_REQUEST['visa60motivo']) == 0) {
		$_REQUEST['visa60motivo'] = '';
	}
	if (isset($_REQUEST['visa60fecharegistro']) == 0) {
		$_REQUEST['visa60fecharegistro'] = '';
		//$_REQUEST['visa60fecharegistro'] = $iHoy;
	}
	if (isset($_REQUEST['visa60idusuario']) == 0) {
		$_REQUEST['visa60idusuario'] = 0;
		//$_REQUEST['visa60idusuario'] =  $idTercero;
	}
	if (isset($_REQUEST['visa60idusuario_td']) == 0) {
		$_REQUEST['visa60idusuario_td'] = $APP->tipo_doc;
	}
	if (isset($_REQUEST['visa60idusuario_doc']) == 0) {
		$_REQUEST['visa60idusuario_doc'] = '';
	}
	$_REQUEST['visa60idactividad'] = numeros_validar($_REQUEST['visa60idactividad']);
	$_REQUEST['visa60consec'] = numeros_validar($_REQUEST['visa60consec']);
	$_REQUEST['visa60id'] = numeros_validar($_REQUEST['visa60id']);
	$_REQUEST['visa60fechareproini'] = numeros_validar($_REQUEST['visa60fechareproini']);
	$_REQUEST['visa60fechareprofin'] = numeros_validar($_REQUEST['visa60fechareprofin']);
	$_REQUEST['visa60motivo'] = cadena_Validar($_REQUEST['visa60motivo']);
	$_REQUEST['visa60fecharegistro'] = numeros_validar($_REQUEST['visa60fecharegistro']);
	$_REQUEST['visa60idusuario'] = numeros_validar($_REQUEST['visa60idusuario']);
	$_REQUEST['visa60idusuario_td'] = cadena_Validar($_REQUEST['visa60idusuario_td']);
	$_REQUEST['visa60idusuario_doc'] = cadena_Validar($_REQUEST['visa60idusuario_doc']);
	//Dificultades
	if (isset($_REQUEST['paginaf2961']) == 0) {
		$_REQUEST['paginaf2961'] = 1;
	}
	if (isset($_REQUEST['lppf2961']) == 0) {
		$_REQUEST['lppf2961'] = 20;
	}
	if (isset($_REQUEST['boculta2961']) == 0) {
		$_REQUEST['boculta2961'] = 0;
	}
	if (isset($_REQUEST['visa61idactividad']) == 0) {
		$_REQUEST['visa61idactividad'] = '';
	}
	if (isset($_REQUEST['visa61consec']) == 0) {
		$_REQUEST['visa61consec'] = '';
	}
	if (isset($_REQUEST['visa61id']) == 0) {
		$_REQUEST['visa61id'] = '';
	}
	if (isset($_REQUEST['visa61descripcion']) == 0) {
		$_REQUEST['visa61descripcion'] = '';
	}
	if (isset($_REQUEST['visa61impacto']) == 0) {
		$_REQUEST['visa61impacto'] = 0;
	}
	if (isset($_REQUEST['visa61requiereapoyo']) == 0) {
		$_REQUEST['visa61requiereapoyo'] = 0;
	}
	if (isset($_REQUEST['visa61fecharegistro']) == 0) {
		$_REQUEST['visa61fecharegistro'] = '';
		//$_REQUEST['visa61fecharegistro'] = $iHoy;
	}
	$_REQUEST['visa61idactividad'] = numeros_validar($_REQUEST['visa61idactividad']);
	$_REQUEST['visa61consec'] = numeros_validar($_REQUEST['visa61consec']);
	$_REQUEST['visa61id'] = numeros_validar($_REQUEST['visa61id']);
	$_REQUEST['visa61descripcion'] = cadena_Validar($_REQUEST['visa61descripcion']);
	$_REQUEST['visa61impacto'] = numeros_validar($_REQUEST['visa61impacto']);
	$_REQUEST['visa61requiereapoyo'] = numeros_validar($_REQUEST['visa61requiereapoyo']);
	$_REQUEST['visa61fecharegistro'] = numeros_validar($_REQUEST['visa61fecharegistro']);
	//Compromisos
	if (isset($_REQUEST['paginaf2962']) == 0) {
		$_REQUEST['paginaf2962'] = 1;
	}
	if (isset($_REQUEST['lppf2962']) == 0) {
		$_REQUEST['lppf2962'] = 20;
	}
	if (isset($_REQUEST['boculta2962']) == 0) {
		$_REQUEST['boculta2962'] = 0;
	}
	if (isset($_REQUEST['visa62idactividad']) == 0) {
		$_REQUEST['visa62idactividad'] = '';
	}
	if (isset($_REQUEST['visa62consec']) == 0) {
		$_REQUEST['visa62consec'] = '';
	}
	if (isset($_REQUEST['visa62id']) == 0) {
		$_REQUEST['visa62id'] = '';
	}
	if (isset($_REQUEST['visa62descripcion']) == 0) {
		$_REQUEST['visa62descripcion'] = '';
	}
	if (isset($_REQUEST['visa62idresponsable']) == 0) {
		$_REQUEST['visa62idresponsable'] = 0;
		//$_REQUEST['visa62idresponsable'] =  $idTercero;
	}
	if (isset($_REQUEST['visa62idresponsable_td']) == 0) {
		$_REQUEST['visa62idresponsable_td'] = $APP->tipo_doc;
	}
	if (isset($_REQUEST['visa62idresponsable_doc']) == 0) {
		$_REQUEST['visa62idresponsable_doc'] = '';
	}
	if (isset($_REQUEST['visa62fechalimite']) == 0) {
		$_REQUEST['visa62fechalimite'] = '';
		//$_REQUEST['visa62fechalimite'] = $iHoy;
	}
	if (isset($_REQUEST['visa62estado']) == 0) {
		$_REQUEST['visa62estado'] = '';
	}
	if (isset($_REQUEST['visa62fechacumple']) == 0) {
		$_REQUEST['visa62fechacumple'] = '';
		//$_REQUEST['visa62fechacumple'] = $iHoy;
	}
	if (isset($_REQUEST['visa62observaciones']) == 0) {
		$_REQUEST['visa62observaciones'] = '';
	}
	if (isset($_REQUEST['visa62fecharegistro']) == 0) {
		$_REQUEST['visa62fecharegistro'] = '';
		//$_REQUEST['visa62fecharegistro'] = $iHoy;
	}
	$_REQUEST['visa62idactividad'] = numeros_validar($_REQUEST['visa62idactividad']);
	$_REQUEST['visa62consec'] = numeros_validar($_REQUEST['visa62consec']);
	$_REQUEST['visa62id'] = numeros_validar($_REQUEST['visa62id']);
	$_REQUEST['visa62descripcion'] = cadena_Validar($_REQUEST['visa62descripcion']);
	$_REQUEST['visa62idresponsable'] = numeros_validar($_REQUEST['visa62idresponsable']);
	$_REQUEST['visa62idresponsable_td'] = cadena_Validar($_REQUEST['visa62idresponsable_td']);
	$_REQUEST['visa62idresponsable_doc'] = cadena_Validar($_REQUEST['visa62idresponsable_doc']);
	$_REQUEST['visa62fechalimite'] = numeros_validar($_REQUEST['visa62fechalimite']);
	$_REQUEST['visa62estado'] = numeros_validar($_REQUEST['visa62estado']);
	$_REQUEST['visa62fechacumple'] = numeros_validar($_REQUEST['visa62fechacumple']);
	$_REQUEST['visa62observaciones'] = cadena_Validar($_REQUEST['visa62observaciones']);
	$_REQUEST['visa62fecharegistro'] = numeros_validar($_REQUEST['visa62fecharegistro']);
	//Solicitud de apoyo
	if (isset($_REQUEST['paginaf2963']) == 0) {
		$_REQUEST['paginaf2963'] = 1;
	}
	if (isset($_REQUEST['lppf2963']) == 0) {
		$_REQUEST['lppf2963'] = 20;
	}
	if (isset($_REQUEST['boculta2963']) == 0) {
		$_REQUEST['boculta2963'] = 0;
	}
	if (isset($_REQUEST['visa63idactividad']) == 0) {
		$_REQUEST['visa63idactividad'] = '';
	}
	if (isset($_REQUEST['visa63consec']) == 0) {
		$_REQUEST['visa63consec'] = '';
	}
	if (isset($_REQUEST['visa63id']) == 0) {
		$_REQUEST['visa63id'] = '';
	}
	if (isset($_REQUEST['visa63descripcion']) == 0) {
		$_REQUEST['visa63descripcion'] = '';
	}
	if (isset($_REQUEST['visa63idcolaborador']) == 0) {
		$_REQUEST['visa63idcolaborador'] = 0;
		//$_REQUEST['visa63idcolaborador'] =  $idTercero;
	}
	if (isset($_REQUEST['visa63idcolaborador_td']) == 0) {
		$_REQUEST['visa63idcolaborador_td'] = $APP->tipo_doc;
	}
	if (isset($_REQUEST['visa63idcolaborador_doc']) == 0) {
		$_REQUEST['visa63idcolaborador_doc'] = '';
	}
	if (isset($_REQUEST['visa63estado']) == 0) {
		$_REQUEST['visa63estado'] = '';
	}
	if (isset($_REQUEST['visa63fechasolicitud']) == 0) {
		$_REQUEST['visa63fechasolicitud'] = '';
		//$_REQUEST['visa63fechasolicitud'] = $iHoy;
	}
	if (isset($_REQUEST['visa63fecharespuesta']) == 0) {
		$_REQUEST['visa63fecharespuesta'] = '';
		//$_REQUEST['visa63fecharespuesta'] = $iHoy;
	}
	if (isset($_REQUEST['visa63observaciones']) == 0) {
		$_REQUEST['visa63observaciones'] = '';
	}
	$_REQUEST['visa63idactividad'] = numeros_validar($_REQUEST['visa63idactividad']);
	$_REQUEST['visa63consec'] = numeros_validar($_REQUEST['visa63consec']);
	$_REQUEST['visa63id'] = numeros_validar($_REQUEST['visa63id']);
	$_REQUEST['visa63descripcion'] = cadena_Validar($_REQUEST['visa63descripcion']);
	$_REQUEST['visa63idcolaborador'] = numeros_validar($_REQUEST['visa63idcolaborador']);
	$_REQUEST['visa63idcolaborador_td'] = cadena_Validar($_REQUEST['visa63idcolaborador_td']);
	$_REQUEST['visa63idcolaborador_doc'] = cadena_Validar($_REQUEST['visa63idcolaborador_doc']);
	$_REQUEST['visa63estado'] = numeros_validar($_REQUEST['visa63estado']);
	$_REQUEST['visa63fechasolicitud'] = numeros_validar($_REQUEST['visa63fechasolicitud']);
	$_REQUEST['visa63fecharespuesta'] = numeros_validar($_REQUEST['visa63fecharespuesta']);
	$_REQUEST['visa63observaciones'] = cadena_Validar($_REQUEST['visa63observaciones']);
}
// Espacio para inicializar otras variables
if (isset($_REQUEST['csv_separa']) == 0) {
	$_REQUEST['csv_separa'] = ';';
}
if (isset($_REQUEST['bsistema']) == 0) {
	$_REQUEST['bsistema'] = '';
}
if (isset($_REQUEST['btitulo']) == 0) {
	$_REQUEST['btitulo'] = '';
}
if (isset($_REQUEST['bestado']) == 0) {
	$_REQUEST['bestado'] = '';
}
if (isset($_REQUEST['bprioridad']) == 0) {
	$_REQUEST['bprioridad'] = '';
}
if (isset($_REQUEST['bfechaini']) == 0) {
	$_REQUEST['bfechaini'] = 0;
}
if (isset($_REQUEST['bfechafin']) == 0) {
	$_REQUEST['bfechafin'] = 0;
}
	//Resultados
	//Evidencias
	//Reprogramación
	//Dificultades
	//Compromisos
	//Solicitud de apoyo
$_REQUEST['bsistema'] = numeros_validar($_REQUEST['bsistema']);
$_REQUEST['btitulo'] = cadena_Validar($_REQUEST['btitulo']);
$_REQUEST['bestado'] = numeros_validar($_REQUEST['bestado']);
$_REQUEST['bprioridad'] = numeros_validar($_REQUEST['bprioridad']);
$_REQUEST['bfechaini'] = numeros_validar($_REQUEST['bfechaini']);
$_REQUEST['bfechafin'] = numeros_validar($_REQUEST['bfechafin']);
	//Resultados
	//Evidencias
	//Reprogramación
	//Dificultades
	//Compromisos
	//Solicitud de apoyo
//Si Modifica o Elimina Cargar los campos
if (($_REQUEST['paso'] == 1) || ($_REQUEST['paso'] == 3)) {
	if ($_REQUEST['paso'] == 1) {
		$sSQLcondi = 'visa57idpersemanal=' . $_REQUEST['visa57idpersemanal'] . ' AND visa57idsistema=' . $_REQUEST['visa57idsistema'] . ' AND visa57consec=' . $_REQUEST['visa57consec'] . '';
	} else {
		$sSQLcondi = 'visa57id=' . $_REQUEST['visa57id'] . '';
	}
	$sNomTabla2957 = f2957_NombreTabla();
	$sSQL = 'SELECT * FROM ' . $sNomTabla2957 . ' WHERE ' . $sSQLcondi;
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$_REQUEST['visa57idpersemanal'] = $fila['visa57idpersemanal'];
		$_REQUEST['visa57idsistema'] = $fila['visa57idsistema'];
		$_REQUEST['visa57consec'] = $fila['visa57consec'];
		$_REQUEST['visa57id'] = $fila['visa57id'];
		$_REQUEST['visa57titulo'] = $fila['visa57titulo'];
		$_REQUEST['visa57descripcion'] = $fila['visa57descripcion'];
		$_REQUEST['visa57tipoactividad'] = $fila['visa57tipoactividad'];
		$_REQUEST['visa57estado'] = $fila['visa57estado'];
		$_REQUEST['visa57prioridad'] = $fila['visa57prioridad'];
		$_REQUEST['visa57fechaprogini'] = $fila['visa57fechaprogini'];
		$_REQUEST['visa57fechaprogfin'] = $fila['visa57fechaprogfin'];
		$_REQUEST['visa57fechaejecini'] = $fila['visa57fechaejecini'];
		$_REQUEST['visa57fechaejecfin'] = $fila['visa57fechaejecfin'];
		$_REQUEST['visa57porcavance'] = $fila['visa57porcavance'];
		$_REQUEST['visa57fechacrea'] = $fila['visa57fechacrea'];
		$_REQUEST['visa57fechaactualiza'] = $fila['visa57fechaactualiza'];
		$bcargo = true;
		$_REQUEST['paso'] = 2;
		$_REQUEST['boculta2957'] = 0;
		$bLimpiaHijos = true;
	} else {
		$_REQUEST['paso'] = 0;
	}
}
//Cerrar
$bCambiaEstado = false;
$idEstadoDestino = 0;
if ($_REQUEST['paso'] == 16) {
	$_REQUEST['paso'] = 12;
	if ($sError == '') {
		$bCambiaEstado = true;
		$idEstadoDestino = 7;
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
		$bCambiaEstado = true;
		$idEstadoDestino = 0;
	}
}
//Insertar o modificar un elemento
if (($_REQUEST['paso'] == 10) || ($_REQUEST['paso'] == 12)) {
	$bMueveScroll = true;
	list($_REQUEST, $sError, $iTipoError, $sDebugGuardar) = f2957_db_GuardarV2b($_REQUEST, $objDB, $bDebug, $idTercero);
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
		list($sError, $sDebugE, $sMensaje) = f2957_CambiaEstado($_REQUEST['visa57id'], $_REQUEST['visa57estado'], $idEstadoDestino, '', $_SESSION['unad_id_tercero'], $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugE;
		if ($sError == '') {
			$_REQUEST['visa57estado'] = $idEstadoDestino;
			$sError = '<b>' . $ETI['msg_itemcerrado'] . '</b>';
			$iTipoError = 1;
			switch ($idEstadoDestino) {
				case 0: // Borrador - Abierto
					$sError = '<b>' . $ETI['msg_itemabierto'] . '</b>';
					break;
			}
		}
	}
}
// Cambio de consecutivo.
if ($_REQUEST['paso'] == 93) {
	$_REQUEST['paso'] = 2;
	$_REQUEST['visa57consec_nuevo'] = numeros_validar($_REQUEST['visa57consec_nuevo']);
	if ($_REQUEST['visa57consec_nuevo'] == '') {
		$sError = $ERR['visa57consec'];
	}
	if ($sError == '') {
		list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 8, $idTercero, $objDB);
		if (!$bDevuelve) {
			$sError = $ERR['8'] . ' [Mod ' . $iCodModulo . ']';
		}
	}
	if ($sError == '') {
		//Ver que el consecutivo no exista.
		$sSQL = 'SELECT visa57id FROM visa57actividad WHERE visa57consec=' . $_REQUEST['visa57consec_nuevo'] . ' AND visa57idsistema=' . $_REQUEST['visa57idsistema'] . ' AND visa57idpersemanal=' . $_REQUEST['visa57idpersemanal'] . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$sError = 'El consecutivo ' . $_REQUEST['visa57consec_nuevo'] . ' ya existe';
		}
	}
	if ($sError == '') {
		//Aplicar el cambio.
		$sSQL = 'UPDATE visa57actividad SET visa57consec=' . $_REQUEST['visa57consec_nuevo'] . ' WHERE visa57id=' . $_REQUEST['visa57id'] . '';
		$tabla = $objDB->ejecutasql($sSQL);
		$sDetalle = 'Cambia el consecutivo de ' . $_REQUEST['visa57consec'] . ' a ' . $_REQUEST['visa57consec_nuevo'] . '';
		$_REQUEST['visa57consec'] = $_REQUEST['visa57consec_nuevo'];
		$_REQUEST['visa57consec_nuevo'] = '';
		seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 8, $_REQUEST['visa57id'], $sDetalle, $objDB);
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
		list($sError, $iTipoError, $sDebugElimina) = f2957_db_Eliminar($_REQUEST['visa57id'], $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugElimina;
	}
	if ($sError == '') {
		$_REQUEST['paso'] = -1;
		$sError = $ETI['msg_itemeliminado'];
		$iTipoError = 1;
	}
}
// Proceso de pagina - ejemplo
if ($_REQUEST['paso'] == 21) {
	$_REQUEST['paso'] = 2;
	if ($sError == '') {
		// acciones a ejecutar
	}
	if ($sError == '') {
		$sError = $ETI['msg_procesoterminado'];
		$iTipoError = 1;
	}
}
//limpiar la pantalla
if ($_REQUEST['paso'] == -1) {
	$_REQUEST['visa57idpersemanal'] = '';
	$_REQUEST['visa57idsistema'] = '';
	$_REQUEST['visa57consec'] = '';
	$_REQUEST['visa57consec_nuevo'] = '';
	$_REQUEST['visa57id'] = '';
	$_REQUEST['visa57titulo'] = '';
	$_REQUEST['visa57descripcion'] = '';
	$_REQUEST['visa57tipoactividad'] = 1;
	$_REQUEST['visa57estado'] = 0;
	$_REQUEST['visa57prioridad'] = 1;
	$_REQUEST['visa57fechaprogini'] = $iHoy;
	$_REQUEST['visa57fechaprogfin'] = $iHoy;
	$_REQUEST['visa57fechaejecini'] = $iHoy;
	$_REQUEST['visa57fechaejecfin'] = $iHoy;
	$_REQUEST['visa57porcavance'] = '';
	$_REQUEST['visa57fechacrea'] = '';
	//$_REQUEST['visa57fechacrea'] = $iHoy;
	$_REQUEST['visa57fechaactualiza'] = '';
	//$_REQUEST['visa57fechaactualiza'] = $iHoy;
	$_REQUEST['paso'] = 0;
}
if ($bLimpiaHijos) {
	$_REQUEST['visa58idactividad'] = '';
	$_REQUEST['visa58consec'] = '';
	$_REQUEST['visa58id'] = '';
	$_REQUEST['visa58descripcion'] = '';
	$_REQUEST['visa58cumplimiento'] = 1;
	$_REQUEST['visa58fecharegistro'] = '';
	//$_REQUEST['visa58fecharegistro'] = $iHoy;
	$_REQUEST['visa59idactividad'] = '';
	$_REQUEST['visa59consec'] = '';
	$_REQUEST['visa59id'] = '';
	$_REQUEST['visa59titulo'] = '';
	$_REQUEST['visa59idorigen'] = 0;
	$_REQUEST['visa59idarchivo'] = 0;
	$_REQUEST['visa59tipoarchivo'] = 1;
	$_REQUEST['visa59descripcion'] = '';
	$_REQUEST['visa59fechacarga'] = '';
	//$_REQUEST['visa59fechacarga'] = $iHoy;
	$_REQUEST['visa59idusuario'] = 0; //$idTercero;
	$_REQUEST['visa59idusuario_td'] = $APP->tipo_doc;
	$_REQUEST['visa59idusuario_doc'] = '';
	$_REQUEST['visa60idactividad'] = '';
	$_REQUEST['visa60consec'] = '';
	$_REQUEST['visa60id'] = '';
	$_REQUEST['visa60fechareproini'] = $iHoy;
	$_REQUEST['visa60fechareprofin'] = $iHoy;
	$_REQUEST['visa60motivo'] = '';
	$_REQUEST['visa60fecharegistro'] = '';
	//$_REQUEST['visa60fecharegistro'] = $iHoy;
	$_REQUEST['visa60idusuario'] = 0; //$idTercero;
	$_REQUEST['visa60idusuario_td'] = $APP->tipo_doc;
	$_REQUEST['visa60idusuario_doc'] = '';
	$_REQUEST['visa61idactividad'] = '';
	$_REQUEST['visa61consec'] = '';
	$_REQUEST['visa61id'] = '';
	$_REQUEST['visa61descripcion'] = '';
	$_REQUEST['visa61impacto'] = 1;
	$_REQUEST['visa61requiereapoyo'] = 1;
	$_REQUEST['visa61fecharegistro'] = '';
	//$_REQUEST['visa61fecharegistro'] = $iHoy;
	$_REQUEST['visa62idactividad'] = '';
	$_REQUEST['visa62consec'] = '';
	$_REQUEST['visa62id'] = '';
	$_REQUEST['visa62descripcion'] = '';
	$_REQUEST['visa62idresponsable'] = 0; //$idTercero;
	$_REQUEST['visa62idresponsable_td'] = $APP->tipo_doc;
	$_REQUEST['visa62idresponsable_doc'] = '';
	$_REQUEST['visa62fechalimite'] = $iHoy;
	$_REQUEST['visa62estado'] = 0;
	$_REQUEST['visa62fechacumple'] = $iHoy;
	$_REQUEST['visa62observaciones'] = '';
	$_REQUEST['visa62fecharegistro'] = '';
	//$_REQUEST['visa62fecharegistro'] = $iHoy;
	$_REQUEST['visa63idactividad'] = '';
	$_REQUEST['visa63consec'] = '';
	$_REQUEST['visa63id'] = '';
	$_REQUEST['visa63descripcion'] = '';
	$_REQUEST['visa63idcolaborador'] = 0; //$idTercero;
	$_REQUEST['visa63idcolaborador_td'] = $APP->tipo_doc;
	$_REQUEST['visa63idcolaborador_doc'] = '';
	$_REQUEST['visa63estado'] = 0;
	$_REQUEST['visa63fechasolicitud'] = $iHoy;
	$_REQUEST['visa63fecharespuesta'] = $iHoy;
	$_REQUEST['visa63observaciones'] = '';
}
//AQUI SE DEBEN CARGAR TODOS LOS DATOS QUE LA FORMA NECESITE.
$bPuedeGuardar = true;
$bConEliminar = false;
$bConBotonCerrar = false;
$bPuedeAbrir = false;
$bEditable = true; // Esta bandera se deja a modo de ejemplo, puede requerir ser dividida muchas veces.
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
$bEdita2958 = false;
$bEdita2959 = false;
$bEdita2960 = false;
$bEdita2961 = false;
$bEdita2962 = false;
$bEdita2963 = false;
//Permisos adicionales
$seg_5 = 0;
$seg_6 = 0;
$seg_8 = 0;
/*
list($bHayImprimir, $sDebugP, $seg_6) = seg_revisa_permisoV3($iCodModulo, 6, $idTercero, $objDB);
*/
if ((int)$_REQUEST['paso'] != 0) {
	//list($bHayImprimir2, $sDebugP, $seg_5) = seg_revisa_permisoV3($iCodModulo, 5, $idTercero, $objDB);
	$bEdita2958 = true;
	$bEdita2959 = true;
	$bEdita2960 = true;
	$bEdita2961 = true;
	$bEdita2962 = true;
	$bEdita2963 = true;
	$bEditable = false;
	switch ($_REQUEST['visa57estado']) {
		case 0: // Abierto
			$bConEliminar = true;
			$bConBotonCerrar = true;
			$bDevuelve = false;
			//list($bDevuelve, $sDebugP, $seg_8) = seg_revisa_permisoV3($iCodModulo, 8, $idTercero, $objDB);
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
$objCombos->nuevo('visa57tipoactividad', $_REQUEST['visa57tipoactividad'], true, $ETI['no'], 0);
//$objCombos->bEsCombobox = true;
$objCombos->addItem(1, $ETI['si']);
//$objCombos->addArreglo($avisa57tipoactividad, $ivisa57tipoactividad);
$sSQL = '';
$html_visa57tipoactividad = $objCombos->html($sSQL, $objDB);
$visa57estado_nombre = '{' . $_REQUEST['visa57estado'] . '}';
$sSQL = 'SELECT unad96nombre, unad96etiqueta FROM unad96estado WHERE unad96idmodulo=2957 AND unad96id=' . $_REQUEST['visa57estado'];
$tabla = $objDB->ejecutasql($sSQL);
if ($objDB->nf($tabla) > 0) {
	$fila = $objDB->sf($tabla);
	$visa57estado_nombre = cadena_notildes($fila['unad96nombre']);
	if ($sIdioma != 'es') {
		$visa57estado_nombre = Etiqueta_Valor(2957, $fila['unad96etiqueta'], $sIdioma, $objDB);
	}
}
$html_visa57estado = html_oculto('visa57estado', $_REQUEST['visa57estado'], $visa57estado_nombre);
$objCombos->nuevo('visa57prioridad', $_REQUEST['visa57prioridad'], true, $ETI['no'], 0);
//$objCombos->bEsCombobox = true;
$objCombos->addItem(1, $ETI['si']);
//$objCombos->addArreglo($avisa57prioridad, $ivisa57prioridad);
$sSQL = '';
$html_visa57prioridad = $objCombos->html($sSQL, $objDB);
if ((int)$_REQUEST['paso'] == 0) {
	$html_visa57idpersemanal = f2957_HTMLComboV2_visa57idpersemanal($objDB, $objCombos, $_REQUEST['visa57idpersemanal']);
	$html_visa57idsistema = f2957_HTMLComboV2_visa57idsistema($objDB, $objCombos, $_REQUEST['visa57idsistema']);
} else {
	$visa57idpersemanal_nombre = '&nbsp;';
	if ((int)$_REQUEST['visa57idpersemanal'] != 0) {
		list($visa57idpersemanal_nombre, $sErrorDet) = tabla_campoxid('visa56persemanal', 'visa56fechaini', 'visa56id', $_REQUEST['visa57idpersemanal'], '{' . $ETI['msg_sindato'] . '}', $objDB);
	}
	$html_visa57idpersemanal = html_oculto('visa57idpersemanal', $_REQUEST['visa57idpersemanal'], $visa57idpersemanal_nombre);
	$visa57idsistema_nombre = '&nbsp;';
	if ((int)$_REQUEST['visa57idsistema'] != 0) {
		list($visa57idsistema_nombre, $sErrorDet) = tabla_campoxid('visa55sistema', 'visa55nombre', 'visa55id', $_REQUEST['visa57idsistema'], '{' . $ETI['msg_sindato'] . '}', $objDB);
	}
	$html_visa57idsistema = html_oculto('visa57idsistema', $_REQUEST['visa57idsistema'], $visa57idsistema_nombre);
}
if ($bEdita2958) {
	$objCombos->nuevo('visa58cumplimiento', $_REQUEST['visa58cumplimiento'], true, $ETI['no'], 0);
	//$objCombos->bEsCombobox = true;
	$objCombos->addItem(1, $ETI['si']);
	//$objCombos->addArreglo($avisa58cumplimiento, $ivisa58cumplimiento);
	$sSQL = '';
	$html_visa58cumplimiento = $objCombos->html($sSQL, $objDB);
}
if ($bEdita2959) {
	$objCombos->nuevo('visa59tipoarchivo', $_REQUEST['visa59tipoarchivo'], true, $ETI['no'], 0);
	//$objCombos->bEsCombobox = true;
	$objCombos->addItem(1, $ETI['si']);
	//$objCombos->addArreglo($avisa59tipoarchivo, $ivisa59tipoarchivo);
	$sSQL = '';
	$html_visa59tipoarchivo = $objCombos->html($sSQL, $objDB);
	list($visa59idusuario_rs, $_REQUEST['visa59idusuario'], $_REQUEST['visa59idusuario_td'], $_REQUEST['visa59idusuario_doc']) = html_tercero($_REQUEST['visa59idusuario_td'], $_REQUEST['visa59idusuario_doc'], $_REQUEST['visa59idusuario'], 0, $objDB);
	$bOculto = false;
	$html_visa59idusuario = html_DivTerceroV8('visa59idusuario', $_REQUEST['visa59idusuario_td'], $_REQUEST['visa59idusuario_doc'], $bOculto, $objDB, $objCombos, 0, $ETI['ing_doc']);
}
if ($bEdita2960) {
	list($visa60idusuario_rs, $_REQUEST['visa60idusuario'], $_REQUEST['visa60idusuario_td'], $_REQUEST['visa60idusuario_doc']) = html_tercero($_REQUEST['visa60idusuario_td'], $_REQUEST['visa60idusuario_doc'], $_REQUEST['visa60idusuario'], 0, $objDB);
	$bOculto = false;
	$html_visa60idusuario = html_DivTerceroV8('visa60idusuario', $_REQUEST['visa60idusuario_td'], $_REQUEST['visa60idusuario_doc'], $bOculto, $objDB, $objCombos, 0, $ETI['ing_doc']);
}
if ($bEdita2961) {
	$objCombos->nuevo('visa61impacto', $_REQUEST['visa61impacto'], true, $ETI['no'], 0);
	//$objCombos->bEsCombobox = true;
	$objCombos->addItem(1, $ETI['si']);
	//$objCombos->addArreglo($avisa61impacto, $ivisa61impacto);
	$sSQL = '';
	$html_visa61impacto = $objCombos->html($sSQL, $objDB);
	$objCombos->nuevo('visa61requiereapoyo', $_REQUEST['visa61requiereapoyo'], true, $ETI['no'], 0);
	//$objCombos->bEsCombobox = true;
	$objCombos->addItem(1, $ETI['si']);
	//$objCombos->addArreglo($avisa61requiereapoyo, $ivisa61requiereapoyo);
	$sSQL = '';
	$html_visa61requiereapoyo = $objCombos->html($sSQL, $objDB);
}
if ($bEdita2962) {
	list($visa62idresponsable_rs, $_REQUEST['visa62idresponsable'], $_REQUEST['visa62idresponsable_td'], $_REQUEST['visa62idresponsable_doc']) = html_tercero($_REQUEST['visa62idresponsable_td'], $_REQUEST['visa62idresponsable_doc'], $_REQUEST['visa62idresponsable'], 0, $objDB);
	$bOculto = false;
	$html_visa62idresponsable = html_DivTerceroV8('visa62idresponsable', $_REQUEST['visa62idresponsable_td'], $_REQUEST['visa62idresponsable_doc'], $bOculto, $objDB, $objCombos, 0, $ETI['ing_doc']);
}
if ($bEdita2963) {
	list($visa63idcolaborador_rs, $_REQUEST['visa63idcolaborador'], $_REQUEST['visa63idcolaborador_td'], $_REQUEST['visa63idcolaborador_doc']) = html_tercero($_REQUEST['visa63idcolaborador_td'], $_REQUEST['visa63idcolaborador_doc'], $_REQUEST['visa63idcolaborador'], 0, $objDB);
	$bOculto = false;
	$html_visa63idcolaborador = html_DivTerceroV8('visa63idcolaborador', $_REQUEST['visa63idcolaborador_td'], $_REQUEST['visa63idcolaborador_doc'], $bOculto, $objDB, $objCombos, 0, $ETI['ing_doc']);
}
//Alistar datos adicionales
$id_rpt = 0;
//$id_rpt=reportes_id(_Identificador_Tipo_Reporte_, $objDB);
$objCombos->nuevo('bsistema', $_REQUEST['bsistema'], true, '{' . $ETI['msg_todos'] . '}');
//$objCombos->bEsCombobox = true;
$objCombos->sAccion = 'paginarf2957()';
$sSQL = 'SELECT visa55id AS id, visa55codigo AS nombre FROM visa55sistema ORDER BY visa55codigo';
$html_bsistema = $objCombos->html($sSQL, $objDB);
$objCombos->nuevo('bestado', $_REQUEST['bestado'], true, '{' . $ETI['msg_todos'] . '}');
//$objCombos->bEsCombobox = true;
$objCombos->sAccion = 'paginarf2957()';
$sSQL = 'SELECT unad96id AS id, unad96nombre AS nombre, unad96etiqueta AS et FROM unad96estado WHERE unad96idmodulo=2957 ORDER BY unad96id';
$html_bestado = $objCombos->html($sSQL, $objDB, 0, '', 'et', 2957, $sIdioma);
$objCombos->nuevo('bprioridad', $_REQUEST['bprioridad'], true, '{' . $ETI['msg_todos'] . '}');
//$objCombos->bEsCombobox = true;
$objCombos->sAccion = 'paginarf2957()';
$sSQL = '';
$html_bprioridad = $objCombos->html($sSQL, $objDB);
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
$aParametros[0] = ''; //$_REQUEST['p1_2957'];
$aParametros[100] = $idTercero;
$aParametros[101] = $_REQUEST['paginaf2957'];
$aParametros[102] = $_REQUEST['lppf2957'];
$aParametros[103] = $_REQUEST['bsistema'];
$aParametros[104] = $_REQUEST['btitulo'];
$aParametros[105] = $_REQUEST['bestado'];
$aParametros[106] = $_REQUEST['bprioridad'];
$aParametros[107] = $_REQUEST['bfechaini'];
$aParametros[108] = $_REQUEST['bfechafin'];
list($sTabla2957, $sDebugTabla) = f2957_TablaDetalleV2($aParametros, $objDB, $bDebug);
$sDebug = $sDebug . $sDebugTabla;
$sTabla2958 = '';
$sTabla2959 = '';
$sTabla2960 = '';
$sTabla2961 = '';
$sTabla2962 = '';
$sTabla2963 = '';
if ($_REQUEST['paso'] != 0) {
	//Resultados
	$aParametros2958[0] = $_REQUEST['visa57id'];
	$aParametros2958[100] = $idTercero;
	$aParametros2958[101] = $_REQUEST['paginaf2958'];
	$aParametros2958[102] = $_REQUEST['lppf2958'];
	//$aParametros2958[103] = $_REQUEST['bnombre2958'];
	//$aParametros2958[104] = $_REQUEST['blistar2958'];
	list($sTabla2958, $sDebugTabla) = f2958_TablaDetalleV2($aParametros2958, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugTabla;
	//Evidencias
	$aParametros2959[0] = $_REQUEST['visa57id'];
	$aParametros2959[100] = $idTercero;
	$aParametros2959[101] = $_REQUEST['paginaf2959'];
	$aParametros2959[102] = $_REQUEST['lppf2959'];
	//$aParametros2959[103] = $_REQUEST['bnombre2959'];
	//$aParametros2959[104] = $_REQUEST['blistar2959'];
	list($sTabla2959, $sDebugTabla) = f2959_TablaDetalleV2($aParametros2959, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugTabla;
	//Reprogramación
	$aParametros2960[0] = $_REQUEST['visa57id'];
	$aParametros2960[100] = $idTercero;
	$aParametros2960[101] = $_REQUEST['paginaf2960'];
	$aParametros2960[102] = $_REQUEST['lppf2960'];
	//$aParametros2960[103] = $_REQUEST['bnombre2960'];
	//$aParametros2960[104] = $_REQUEST['blistar2960'];
	list($sTabla2960, $sDebugTabla) = f2960_TablaDetalleV2($aParametros2960, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugTabla;
	//Dificultades
	$aParametros2961[0] = $_REQUEST['visa57id'];
	$aParametros2961[100] = $idTercero;
	$aParametros2961[101] = $_REQUEST['paginaf2961'];
	$aParametros2961[102] = $_REQUEST['lppf2961'];
	//$aParametros2961[103] = $_REQUEST['bnombre2961'];
	//$aParametros2961[104] = $_REQUEST['blistar2961'];
	list($sTabla2961, $sDebugTabla) = f2961_TablaDetalleV2($aParametros2961, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugTabla;
	//Compromisos
	$aParametros2962[0] = $_REQUEST['visa57id'];
	$aParametros2962[100] = $idTercero;
	$aParametros2962[101] = $_REQUEST['paginaf2962'];
	$aParametros2962[102] = $_REQUEST['lppf2962'];
	//$aParametros2962[103] = $_REQUEST['bnombre2962'];
	//$aParametros2962[104] = $_REQUEST['blistar2962'];
	list($sTabla2962, $sDebugTabla) = f2962_TablaDetalleV2($aParametros2962, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugTabla;
	//Solicitud de apoyo
	$aParametros2963[0] = $_REQUEST['visa57id'];
	$aParametros2963[100] = $idTercero;
	$aParametros2963[101] = $_REQUEST['paginaf2963'];
	$aParametros2963[102] = $_REQUEST['lppf2963'];
	//$aParametros2963[103] = $_REQUEST['bnombre2963'];
	//$aParametros2963[104] = $_REQUEST['blistar2963'];
	list($sTabla2963, $sDebugTabla) = f2963_TablaDetalleV2($aParametros2963, $objDB, $bDebug);
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
			xajax_unad11_TraerXid(params);
		}
	}

	function imprimelista() {
		if (window.document.frmedita.seg_6.value == 1) {
			window.document.frmlista.consulta.value = window.document.frmedita.consulta_2957.value;
			window.document.frmlista.titulos.value = window.document.frmedita.titulos_2957.value;
			window.document.frmlista.nombrearchivo.value = 'Actividades VISAE';
			window.document.frmlista.submit();
		} else {
			ModalMensaje("<?php echo $ERR['6']; ?>");
		}
	}

	function asignarvariables() {
		window.document.frmimpp.separa.value = window.document.frmedita.csv_separa.value.trim();
		window.document.frmimpp.v3.value = window.document.frmedita.bsistema.value;
		window.document.frmimpp.v4.value = window.document.frmedita.btitulo.value;
		window.document.frmimpp.v5.value = window.document.frmedita.bestado.value;
		window.document.frmimpp.v6.value = window.document.frmedita.bprioridad.value;
		window.document.frmimpp.v7.value = window.document.frmedita.bfechaini.value;
		window.document.frmimpp.v8.value = window.document.frmedita.bfechafin.value;
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
			window.document.frmimpp.action = 'e2957_ss.php';
			window.document.frmimpp.submit();
		} else {
			ModalMensaje(sError);
		}
	}

	function imprimep() {
		if (window.document.frmedita.seg_5.value == 1) {
			asignarvariables();
			window.document.frmimpp.action = 'p2957.php';
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
		datos[1] = window.document.frmedita.visa57idpersemanal.value;
		datos[2] = window.document.frmedita.visa57idsistema.value;
		datos[3] = window.document.frmedita.visa57consec.value;
		if ((datos[1] != '') && (datos[2] != '') && (datos[3] != '')) {
			xajax_f2957_ExisteDato(datos);
		}
	}

	function cargadato(llave1, llave2, llave3) {
		window.document.frmedita.visa57idpersemanal.value = String(llave1);
		window.document.frmedita.visa57idsistema.value = String(llave2);
		window.document.frmedita.visa57consec.value = String(llave3);
		window.document.frmedita.paso.value = 1;
		window.document.frmedita.submit();
	}

	function cargaridf2957(llave1) {
		window.document.frmedita.visa57id.value = String(llave1);
		window.document.frmedita.paso.value = 3;
		window.document.frmedita.submit();
	}

	function paginarf2957() {
		let params = new Array();
		params[99] = window.document.frmedita.debug.value;
		params[100] = <?php echo $idTercero; ?>;
		params[101] = window.document.frmedita.paginaf2957.value;
		params[102] = window.document.frmedita.lppf2957.value;
		params[103] = window.document.frmedita.bsistema.value;
		params[104] = window.document.frmedita.btitulo.value;
		params[105] = window.document.frmedita.bestado.value;
		params[106] = window.document.frmedita.bprioridad.value;
		params[107] = window.document.frmedita.bfechaini.value;
		params[108] = window.document.frmedita.bfechafin.value;
		document.getElementById('div_f2957detalle').innerHTML = '<div class="GrupoCamposAyuda"><div class="MarquesinaMedia"><?php echo $ETI['msg_procesando_datos']; ?></div></div><input id="paginaf2957" name="paginaf2957" type="hidden" value="' + params[101] + '" /><input id="lppf2957" name="lppf2957" type="hidden" value="' + params[102] + '" />';
		xajax_f2957_HtmlTabla(params);
	}
<?php
if ($bConBotonCerrar) {
?>
	function enviacerrar() {
		ModalConfirmV2('<?php echo $ETI['msg_cierre2957']; ?>', () => {
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
		document.getElementById("visa57idpersemanal").focus();
	}

	function buscarV2016(sCampo) {
		window.document.frmedita.iscroll.value = window.scrollY;
		expandesector(98);
		window.document.frmedita.scampobusca.value = sCampo;
		let params = new Array();
		params[1] = sCampo;
		//params[2] = window.document.frmedita.iagno.value;
		//params[3] = window.document.frmedita.itipo.value;
		xajax_f2957_Busquedas(params);
	}

	function Devuelve(sValor) {
		let sCampo = window.document.frmedita.scampobusca.value;
		if (sCampo == 'visa59idusuario') {
			ter_traerxid('visa59idusuario', sValor);
		}
		if (sCampo == 'visa60idusuario') {
			ter_traerxid('visa60idusuario', sValor);
		}
		if (sCampo == 'visa62idresponsable') {
			ter_traerxid('visa62idresponsable', sValor);
		}
		if (sCampo == 'visa63idcolaborador') {
			ter_traerxid('visa63idcolaborador', sValor);
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
		if (ref == 2959) {
			if (sRetorna != '') {
				window.document.frmedita.visa59idorigen.value = window.document.frmedita.div96v1.value;
				window.document.frmedita.visa59idarchivo.value = sRetorna;
				verboton('beliminavisa59idarchivo', 'block');
			}
			archivo_lnk(window.document.frmedita.visa59idorigen.value, window.document.frmedita.visa59idarchivo.value, 'div_visa59idarchivo');
			paginarf2959();
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
</script>
<?php
if ($_REQUEST['paso'] != 0) {
?>
<script language="javascript" src="jsi/js2958.js"></script>
<script language="javascript" src="jsi/js2959.js"></script>
<script language="javascript" src="jsi/js2960.js"></script>
<script language="javascript" src="jsi/js2961.js"></script>
<script language="javascript" src="jsi/js2962.js"></script>
<script language="javascript" src="jsi/js2963.js"></script>
<?php
}
?>
<form id="frmimpp" name="frmimpp" method="post" action="p2957.php" target="_blank" style="display:none">
<input id="r" name="r" type="hidden" value="2957" />
<input id="id2957" name="id2957" type="hidden" value="<?php echo $_REQUEST['visa57id']; ?>" />
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
<input id="seg_1707" name="seg_1707" type="hidden" value="<?php echo $seg_1707; ?>" />
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
<?php
echo $ETI['msg_documento'];
?>
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
echo $objForma->htmlExpande(2957, $_REQUEST['boculta2957'], $ETI['bt_mostrar'], $ETI['bt_ocultar']);
$sEstiloDiv = '';
if ($_REQUEST['boculta2957'] != 0) {
	$sEstiloDiv = ' style="display:none;"';
}
?>
</div>
<div id="div_p2957"<?php echo $sEstiloDiv; ?>>
<?php
}
//Mostrar formulario para editar
?>
<label class="Label130">
<?php
echo $ETI['visa57idpersemanal'];
?>
</label>
<label>
<div id="div_visa57idpersemanal" class="field">
<?php
echo $html_visa57idpersemanal;
?>
</div>
</label>
<label class="Label130">
<?php
echo $ETI['visa57idsistema'];
?>
</label>
<label>
<div id="div_visa57idsistema" class="field">
<?php
echo $html_visa57idsistema;
?>
</div>
</label>
<label class="Label130"<?php echo $sOcultaConsec; ?>>
<?php
echo $ETI['visa57consec'];
?>
</label>
<label class="Label130"<?php echo $sOcultaConsec; ?>>
<?php
if ($_REQUEST['paso'] != 2) {
?>
<input id="visa57consec" name="visa57consec" type="text" value="<?php echo $_REQUEST['visa57consec']; ?>" onchange="RevisaLlave()" class="cuatro" />
<?php
} else {
	echo html_oculto('visa57consec', $_REQUEST['visa57consec'], formato_numero($_REQUEST['visa57consec']));
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
echo $ETI['visa57id'];
?>
</label>
<label class="Label60"<?php echo $sOcultaId; ?>>
<?php
	echo html_oculto('visa57id', $_REQUEST['visa57id'], formato_numero($_REQUEST['visa57id']));
?>
</label>
<label class="L">
<?php
echo $ETI['visa57titulo'];
?>

<input id="visa57titulo" name="visa57titulo" type="text" value="<?php echo $_REQUEST['visa57titulo']; ?>" maxlength="200" class="L" placeholder="<?php echo $ETI['ing_campo'] . $ETI['visa57titulo']; ?>" />
</label>
<label class="txtAreaS">
<?php
echo $ETI['visa57descripcion'];
?>
<textarea id="visa57descripcion" name="visa57descripcion" placeholder="<?php echo $ETI['ing_campo'] . $ETI['visa57descripcion']; ?>"><?php echo $_REQUEST['visa57descripcion']; ?></textarea>
</label>
<label class="Label130">
<?php
echo $ETI['visa57tipoactividad'];
?>
</label>
<label>
<div id="div_visa57tipoactividad" class="field">
<?php
echo $html_visa57tipoactividad;
?>
</div>
</label>
<label class="Label90">
<?php
echo $ETI['visa57estado'];
?>
</label>
<label class="Label220">
<?php
echo $html_visa57estado;
?>
</label>
<label class="Label130">
<?php
echo $ETI['visa57prioridad'];
?>
</label>
<label>
<div id="div_visa57prioridad" class="field">
<?php
echo $html_visa57prioridad;
?>
</div>
</label>
<label class="Label130">
<?php
echo $ETI['visa57fechaprogini'];
?>
</label>
<div class="Campo220">
<?php
echo html_FechaEnNumero('visa57fechaprogini', $_REQUEST['visa57fechaprogini']); //, false, '', $iAgnoIni, $iAgnoFin); //$bvacio, $accion
?>
</div>
<?php
if (false) {
	echo $objForma->htmlBotonSolo('bvisa57fechaprogini_hoy', 'btMiniHoy', "fecha_AsignarNum('visa57fechaprogini', " . $iHoy . ")", $ETI['bt_hoy']);
}
?>
<label class="Label130">
<?php
echo $ETI['visa57fechaprogfin'];
?>
</label>
<div class="Campo220">
<?php
echo html_FechaEnNumero('visa57fechaprogfin', $_REQUEST['visa57fechaprogfin']); //, false, '', $iAgnoIni, $iAgnoFin); //$bvacio, $accion
?>
</div>
<?php
if (false) {
	echo $objForma->htmlBotonSolo('bvisa57fechaprogfin_hoy', 'btMiniHoy', "fecha_AsignarNum('visa57fechaprogfin', " . $iHoy . ")", $ETI['bt_hoy']);
}
?>
<label class="Label130">
<?php
echo $ETI['visa57fechaejecini'];
?>
</label>
<div class="Campo220">
<?php
echo html_FechaEnNumero('visa57fechaejecini', $_REQUEST['visa57fechaejecini']); //, false, '', $iAgnoIni, $iAgnoFin); //$bvacio, $accion
?>
</div>
<?php
if (false) {
	echo $objForma->htmlBotonSolo('bvisa57fechaejecini_hoy', 'btMiniHoy', "fecha_AsignarNum('visa57fechaejecini', " . $iHoy . ")", $ETI['bt_hoy']);
}
?>
<label class="Label130">
<?php
echo $ETI['visa57fechaejecfin'];
?>
</label>
<div class="Campo220">
<?php
echo html_FechaEnNumero('visa57fechaejecfin', $_REQUEST['visa57fechaejecfin']); //, false, '', $iAgnoIni, $iAgnoFin); //$bvacio, $accion
?>
</div>
<?php
if (false) {
	echo $objForma->htmlBotonSolo('bvisa57fechaejecfin_hoy', 'btMiniHoy', "fecha_AsignarNum('visa57fechaejecfin', " . $iHoy . ")", $ETI['bt_hoy']);
}
?>
<label class="Label130">
<?php
echo $ETI['visa57porcavance'];
?>
</label>
<label class="Label160">

<input id="visa57porcavance" name="visa57porcavance" type="text" value="<?php echo formato_numero($_REQUEST['visa57porcavance'], 2); ?>" class="veinte" maxlength="15" style="text-align:right" onchange="formatea_moneda(this);" placeholder="<?php echo $ETI['ing_vr']; ?>" />
</label>
<label class="Label130">
<?php
echo $ETI['visa57fechacrea'];
?>
</label>
<label class="Label220">
<div id="div_visa57fechacrea">
<?php
echo html_oculto('visa57fechacrea', $_REQUEST['visa57fechacrea'], fecha_desdenumero($_REQUEST['visa57fechacrea'])); //formato_FechaLargaDesdeNumero
?>
</div>
</label>
<label class="Label130">
<?php
echo $ETI['visa57fechaactualiza'];
?>
</label>
<label class="Label220">
<div id="div_visa57fechaactualiza">
<?php
echo html_oculto('visa57fechaactualiza', $_REQUEST['visa57fechaactualiza'], fecha_desdenumero($_REQUEST['visa57fechaactualiza'])); //formato_FechaLargaDesdeNumero
?>
</div>
</label>
<?php
// -- Inicia Grupo campos 2958 Resultados
?>
<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo $ETI['titulo_2958'];
?>
</label>
<?php
if ($_REQUEST['paso'] == 2) {
?>
<input id="boculta2958" name="boculta2958" type="hidden" value="<?php echo $_REQUEST['boculta2958']; ?>" />
<?php
	if ($bEdita2958) {
?>
<div class="ir_derecha"<?php echo $sAnchoExpandeContrae; ?>>
<?php
if (false) {
	echo $objForma->htmlBotonSolo('btexcel2958', 'btMiniExcel', 'imprime2958()', 'Exportar', 30);
}
echo $objForma->htmlExpande(2958, $_REQUEST['boculta2958'], $ETI['bt_mostrar'], $ETI['bt_ocultar']);
$sEstiloDiv = '';
if ($_REQUEST['boculta2958'] != 0) {
	$sEstiloDiv = ' style="display:none;"';
}
?>
</div>
<div class="salto1px"></div>
<div id="div_p2958"<?php echo $sEstiloDiv; ?>>
<label class="Label130"<?php echo $sOcultaConsec; ?>>
<?php
echo $ETI['visa58consec'];
?>
</label>
<label class="Label130"<?php echo $sOcultaConsec; ?>>
<div id="div_visa58consec">
<?php
if ((int)$_REQUEST['visa58id'] == 0) {
?>
<input id="visa58consec" name="visa58consec" type="text" value="<?php echo $_REQUEST['visa58consec']; ?>" onchange="revisaf2958()" class="cuatro" />
<?php
} else {
	echo html_oculto('visa58consec', $_REQUEST['visa58consec'], formato_numero($_REQUEST['visa58consec']));
}
?>
</div>
</label>
<label class="Label60"<?php echo $sOcultaId; ?>>
<?php
echo $ETI['visa58id'];
?>
</label>
<label class="Label60"<?php echo $sOcultaId; ?>>
<div id="div_visa58id">
<?php
	echo html_oculto('visa58id', $_REQUEST['visa58id'], formato_numero($_REQUEST['visa58id']));
?>
</div>
</label>
<label class="txtAreaS">
<?php
echo $ETI['visa58descripcion'];
?>
<textarea id="visa58descripcion" name="visa58descripcion" placeholder="<?php echo $ETI['ing_campo'] . $ETI['visa58descripcion']; ?>"><?php echo $_REQUEST['visa58descripcion']; ?></textarea>
</label>
<label class="Label130">
<?php
echo $ETI['visa58cumplimiento'];
?>
</label>
<label>
<div id="div_visa58cumplimiento" class="field">
<?php
echo $html_visa58cumplimiento;
?>
</div>
</label>
<label class="Label130">
<?php
echo $ETI['visa58fecharegistro'];
?>
</label>
<label class="Label220">
<div id="div_visa58fecharegistro">
<?php
echo html_oculto('visa58fecharegistro', $_REQUEST['visa58fecharegistro'], fecha_desdenumero($_REQUEST['visa58fecharegistro'])); //formato_FechaLargaDesdeNumero
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label130">&nbsp;</label>
<?php
$sEstiloElimina = 'display:none;';
if ((int)$_REQUEST['visa58id'] != 0) {
	$sEstiloElimina = 'inline-block;';
}
echo $objForma->htmlBotonSolo('bguarda2958', 'btMiniGuardar', 'guardaf2958()', $ETI['bt_mini_guardar_2958'], 30);
echo $objForma->htmlBotonSolo('blimpia2958', 'btMiniLimpiar', 'limpiaf2958()', $ETI['bt_mini_limpiar_2958'], 30);
echo $objForma->htmlBotonSolo('belimina2958', 'btMiniEliminar', 'eliminaf2958()', $ETI['bt_mini_eliminar_2958'], 30, $sEstiloElimina);
//Este es el cierre del div_p2958
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
<input id="bnombre2958" name="bnombre2958" type="text" value="<?php echo $_REQUEST['bnombre2958']; ?>" onchange="paginarf2958()" />
</label>
<label class="Label130">
<?php
echo $ETI['msg_Listar'];
?>
</label>
<label>
<?php
echo $html_blistar2958;
?>
</label>
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
<?php
}
?>
<div id="div_f2958detalle">
<?php
echo $sTabla2958;
?>
</div>
<?php
}
?>
<div class="salto1px"></div>
</div>
<?php
// -- Termina Grupo campos 2958 Resultados
?>
<?php
// -- Inicia Grupo campos 2959 Evidencias
?>
<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo $ETI['titulo_2959'];
?>
</label>
<?php
if ($_REQUEST['paso'] == 2) {
?>
<input id="boculta2959" name="boculta2959" type="hidden" value="<?php echo $_REQUEST['boculta2959']; ?>" />
<?php
	if ($bEdita2959) {
?>
<div class="ir_derecha"<?php echo $sAnchoExpandeContrae; ?>>
<?php
if (false) {
	echo $objForma->htmlBotonSolo('btexcel2959', 'btMiniExcel', 'imprime2959()', 'Exportar', 30);
}
echo $objForma->htmlExpande(2959, $_REQUEST['boculta2959'], $ETI['bt_mostrar'], $ETI['bt_ocultar']);
$sEstiloDiv = '';
if ($_REQUEST['boculta2959'] != 0) {
	$sEstiloDiv = ' style="display:none;"';
}
?>
</div>
<div class="salto1px"></div>
<div id="div_p2959"<?php echo $sEstiloDiv; ?>>
<label class="Label130"<?php echo $sOcultaConsec; ?>>
<?php
echo $ETI['visa59consec'];
?>
</label>
<label class="Label130"<?php echo $sOcultaConsec; ?>>
<div id="div_visa59consec">
<?php
if ((int)$_REQUEST['visa59id'] == 0) {
?>
<input id="visa59consec" name="visa59consec" type="text" value="<?php echo $_REQUEST['visa59consec']; ?>" onchange="revisaf2959()" class="cuatro" />
<?php
} else {
	echo html_oculto('visa59consec', $_REQUEST['visa59consec'], formato_numero($_REQUEST['visa59consec']));
}
?>
</div>
</label>
<label class="Label60"<?php echo $sOcultaId; ?>>
<?php
echo $ETI['visa59id'];
?>
</label>
<label class="Label60"<?php echo $sOcultaId; ?>>
<div id="div_visa59id">
<?php
	echo html_oculto('visa59id', $_REQUEST['visa59id'], formato_numero($_REQUEST['visa59id']));
?>
</div>
</label>
<label class="L">
<?php
echo $ETI['visa59titulo'];
?>

<input id="visa59titulo" name="visa59titulo" type="text" value="<?php echo $_REQUEST['visa59titulo']; ?>" maxlength="255" class="L" placeholder="<?php echo $ETI['ing_campo'] . $ETI['visa59titulo']; ?>" />
</label>
<input id="visa59idorigen" name="visa59idorigen" type="hidden" value="<?php echo $_REQUEST['visa59idorigen']; ?>" />
<input id="visa59idarchivo" name="visa59idarchivo" type="hidden" value="<?php echo $_REQUEST['visa59idarchivo']; ?>" />
<input id="visa59idarchivo_up" name="visa59idarchivo_up" type="hidden" value="<?php echo html_lnkupload(2959, $_REQUEST['visa59id']); ?>" />
<div class="GrupoCampos300">
<div class="salto1px"></div>
<div id="div_visa59idarchivo" class="Campo300">
<?php
echo html_lnkarchivo((int)$_REQUEST['visa59idorigen'], (int)$_REQUEST['visa59idarchivo']);
?>
</div>
<?php
$sEstiloAnexa = '';
$sEstiloElimina = ' style="display:none;"';
if ((int)$_REQUEST['visa59id'] == 0) {
	$sEstiloAnexa = ' style="display:none;"';
}
if ((int)$_REQUEST['visa59idarchivo'] != 0) {
	$sEstiloElimina = '';
}
echo $objForma->htmlBotonSolo('banexavisa59idarchivo', 'btMiniAnexar', 'carga_visa59idarchivo(window.document.frmedita.visa59idarchivo_up.value)', $ETI['bt_mini_cargararchivo'], 30, $sEstiloAnexa);
echo $objForma->htmlBotonSolo('beliminavisa59idarchivo', 'btMiniEliminar', 'eliminavisa59idarchivo()', $ETI['bt_mini_eliminararchivo'], 30, $sEstiloElimina);
?>
<div class="salto1px"></div>
</div>
<label class="Label130">
<?php
echo $ETI['visa59tipoarchivo'];
?>
</label>
<label>
<div id="div_visa59tipoarchivo" class="field">
<?php
echo $html_visa59tipoarchivo;
?>
</div>
</label>
<label class="txtAreaS">
<?php
echo $ETI['visa59descripcion'];
?>
<textarea id="visa59descripcion" name="visa59descripcion" placeholder="<?php echo $ETI['ing_campo'] . $ETI['visa59descripcion']; ?>"><?php echo $_REQUEST['visa59descripcion']; ?></textarea>
</label>
<label class="Label130">
<?php
echo $ETI['visa59fechacarga'];
?>
</label>
<label class="Label220">
<div id="div_visa59fechacarga">
<?php
echo html_oculto('visa59fechacarga', $_REQUEST['visa59fechacarga'], fecha_desdenumero($_REQUEST['visa59fechacarga'])); //formato_FechaLargaDesdeNumero
?>
</div>
</label>
<div class="salto1px"></div>
<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo $ETI['visa59idusuario'];
?>
</label>
<div class="salto1px"></div>
<input id="visa59idusuario" name="visa59idusuario" type="hidden" value="<?php echo $_REQUEST['visa59idusuario']; ?>" />
<div id="div_visa59idusuario_llaves">
<?php
echo $html_visa59idusuario;
?>
</div>
<div class="salto1px"></div>
<div id="div_visa59idusuario" class="L"><?php echo $visa59idusuario_rs; ?></div>
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
<label class="Label130">&nbsp;</label>
<?php
$sEstiloElimina = 'display:none;';
if ((int)$_REQUEST['visa59id'] != 0) {
	$sEstiloElimina = 'inline-block;';
}
echo $objForma->htmlBotonSolo('bguarda2959', 'btMiniGuardar', 'guardaf2959()', $ETI['bt_mini_guardar_2959'], 30);
echo $objForma->htmlBotonSolo('blimpia2959', 'btMiniLimpiar', 'limpiaf2959()', $ETI['bt_mini_limpiar_2959'], 30);
echo $objForma->htmlBotonSolo('belimina2959', 'btMiniEliminar', 'eliminaf2959()', $ETI['bt_mini_eliminar_2959'], 30, $sEstiloElimina);
//Este es el cierre del div_p2959
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
<input id="bnombre2959" name="bnombre2959" type="text" value="<?php echo $_REQUEST['bnombre2959']; ?>" onchange="paginarf2959()" />
</label>
<label class="Label130">
<?php
echo $ETI['msg_Listar'];
?>
</label>
<label>
<?php
echo $html_blistar2959;
?>
</label>
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
<?php
}
?>
<div id="div_f2959detalle">
<?php
echo $sTabla2959;
?>
</div>
<?php
}
?>
<div class="salto1px"></div>
</div>
<?php
// -- Termina Grupo campos 2959 Evidencias
?>
<?php
// -- Inicia Grupo campos 2960 Reprogramación
?>
<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo $ETI['titulo_2960'];
?>
</label>
<?php
if ($_REQUEST['paso'] == 2) {
?>
<input id="boculta2960" name="boculta2960" type="hidden" value="<?php echo $_REQUEST['boculta2960']; ?>" />
<?php
	if ($bEdita2960) {
?>
<div class="ir_derecha"<?php echo $sAnchoExpandeContrae; ?>>
<?php
if (false) {
	echo $objForma->htmlBotonSolo('btexcel2960', 'btMiniExcel', 'imprime2960()', 'Exportar', 30);
}
echo $objForma->htmlExpande(2960, $_REQUEST['boculta2960'], $ETI['bt_mostrar'], $ETI['bt_ocultar']);
$sEstiloDiv = '';
if ($_REQUEST['boculta2960'] != 0) {
	$sEstiloDiv = ' style="display:none;"';
}
?>
</div>
<div class="salto1px"></div>
<div id="div_p2960"<?php echo $sEstiloDiv; ?>>
<label class="Label130"<?php echo $sOcultaConsec; ?>>
<?php
echo $ETI['visa60consec'];
?>
</label>
<label class="Label130"<?php echo $sOcultaConsec; ?>>
<div id="div_visa60consec">
<?php
if ((int)$_REQUEST['visa60id'] == 0) {
?>
<input id="visa60consec" name="visa60consec" type="text" value="<?php echo $_REQUEST['visa60consec']; ?>" onchange="revisaf2960()" class="cuatro" />
<?php
} else {
	echo html_oculto('visa60consec', $_REQUEST['visa60consec'], formato_numero($_REQUEST['visa60consec']));
}
?>
</div>
</label>
<label class="Label60"<?php echo $sOcultaId; ?>>
<?php
echo $ETI['visa60id'];
?>
</label>
<label class="Label60"<?php echo $sOcultaId; ?>>
<div id="div_visa60id">
<?php
	echo html_oculto('visa60id', $_REQUEST['visa60id'], formato_numero($_REQUEST['visa60id']));
?>
</div>
</label>
<label class="Label130">
<?php
echo $ETI['visa60fechareproini'];
?>
</label>
<div class="Campo220">
<?php
echo html_FechaEnNumero('visa60fechareproini', $_REQUEST['visa60fechareproini']); //, false, '', $iAgnoIni, $iAgnoFin); //$bvacio, $accion
?>
</div>
<?php
if (false) {
	echo $objForma->htmlBotonSolo('bvisa60fechareproini_hoy', 'btMiniHoy', "fecha_AsignarNum('visa60fechareproini', " . $iHoy . ")", $ETI['bt_hoy']);
}
?>
<label class="Label130">
<?php
echo $ETI['visa60fechareprofin'];
?>
</label>
<div class="Campo220">
<?php
echo html_FechaEnNumero('visa60fechareprofin', $_REQUEST['visa60fechareprofin']); //, false, '', $iAgnoIni, $iAgnoFin); //$bvacio, $accion
?>
</div>
<?php
if (false) {
	echo $objForma->htmlBotonSolo('bvisa60fechareprofin_hoy', 'btMiniHoy', "fecha_AsignarNum('visa60fechareprofin', " . $iHoy . ")", $ETI['bt_hoy']);
}
?>
<label class="txtAreaS">
<?php
echo $ETI['visa60motivo'];
?>
<textarea id="visa60motivo" name="visa60motivo" placeholder="<?php echo $ETI['ing_campo'] . $ETI['visa60motivo']; ?>"><?php echo $_REQUEST['visa60motivo']; ?></textarea>
</label>
<label class="Label130">
<?php
echo $ETI['visa60fecharegistro'];
?>
</label>
<label class="Label220">
<div id="div_visa60fecharegistro">
<?php
echo html_oculto('visa60fecharegistro', $_REQUEST['visa60fecharegistro'], fecha_desdenumero($_REQUEST['visa60fecharegistro'])); //formato_FechaLargaDesdeNumero
?>
</div>
</label>
<div class="salto1px"></div>
<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo $ETI['visa60idusuario'];
?>
</label>
<div class="salto1px"></div>
<input id="visa60idusuario" name="visa60idusuario" type="hidden" value="<?php echo $_REQUEST['visa60idusuario']; ?>" />
<div id="div_visa60idusuario_llaves">
<?php
echo $html_visa60idusuario;
?>
</div>
<div class="salto1px"></div>
<div id="div_visa60idusuario" class="L"><?php echo $visa60idusuario_rs; ?></div>
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
<label class="Label130">&nbsp;</label>
<?php
$sEstiloElimina = 'display:none;';
if ((int)$_REQUEST['visa60id'] != 0) {
	$sEstiloElimina = 'inline-block;';
}
echo $objForma->htmlBotonSolo('bguarda2960', 'btMiniGuardar', 'guardaf2960()', $ETI['bt_mini_guardar_2960'], 30);
echo $objForma->htmlBotonSolo('blimpia2960', 'btMiniLimpiar', 'limpiaf2960()', $ETI['bt_mini_limpiar_2960'], 30);
echo $objForma->htmlBotonSolo('belimina2960', 'btMiniEliminar', 'eliminaf2960()', $ETI['bt_mini_eliminar_2960'], 30, $sEstiloElimina);
//Este es el cierre del div_p2960
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
<input id="bnombre2960" name="bnombre2960" type="text" value="<?php echo $_REQUEST['bnombre2960']; ?>" onchange="paginarf2960()" />
</label>
<label class="Label130">
<?php
echo $ETI['msg_Listar'];
?>
</label>
<label>
<?php
echo $html_blistar2960;
?>
</label>
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
<?php
}
?>
<div id="div_f2960detalle">
<?php
echo $sTabla2960;
?>
</div>
<?php
}
?>
<div class="salto1px"></div>
</div>
<?php
// -- Termina Grupo campos 2960 Reprogramación
?>
<?php
// -- Inicia Grupo campos 2961 Dificultades
?>
<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo $ETI['titulo_2961'];
?>
</label>
<?php
if ($_REQUEST['paso'] == 2) {
?>
<input id="boculta2961" name="boculta2961" type="hidden" value="<?php echo $_REQUEST['boculta2961']; ?>" />
<?php
	if ($bEdita2961) {
?>
<div class="ir_derecha"<?php echo $sAnchoExpandeContrae; ?>>
<?php
if (false) {
	echo $objForma->htmlBotonSolo('btexcel2961', 'btMiniExcel', 'imprime2961()', 'Exportar', 30);
}
echo $objForma->htmlExpande(2961, $_REQUEST['boculta2961'], $ETI['bt_mostrar'], $ETI['bt_ocultar']);
$sEstiloDiv = '';
if ($_REQUEST['boculta2961'] != 0) {
	$sEstiloDiv = ' style="display:none;"';
}
?>
</div>
<div class="salto1px"></div>
<div id="div_p2961"<?php echo $sEstiloDiv; ?>>
<label class="Label130"<?php echo $sOcultaConsec; ?>>
<?php
echo $ETI['visa61consec'];
?>
</label>
<label class="Label130"<?php echo $sOcultaConsec; ?>>
<div id="div_visa61consec">
<?php
if ((int)$_REQUEST['visa61id'] == 0) {
?>
<input id="visa61consec" name="visa61consec" type="text" value="<?php echo $_REQUEST['visa61consec']; ?>" onchange="revisaf2961()" class="cuatro" />
<?php
} else {
	echo html_oculto('visa61consec', $_REQUEST['visa61consec'], formato_numero($_REQUEST['visa61consec']));
}
?>
</div>
</label>
<label class="Label60"<?php echo $sOcultaId; ?>>
<?php
echo $ETI['visa61id'];
?>
</label>
<label class="Label60"<?php echo $sOcultaId; ?>>
<div id="div_visa61id">
<?php
	echo html_oculto('visa61id', $_REQUEST['visa61id'], formato_numero($_REQUEST['visa61id']));
?>
</div>
</label>
<label class="txtAreaS">
<?php
echo $ETI['visa61descripcion'];
?>
<textarea id="visa61descripcion" name="visa61descripcion" placeholder="<?php echo $ETI['ing_campo'] . $ETI['visa61descripcion']; ?>"><?php echo $_REQUEST['visa61descripcion']; ?></textarea>
</label>
<label class="Label130">
<?php
echo $ETI['visa61impacto'];
?>
</label>
<label>
<div id="div_visa61impacto" class="field">
<?php
echo $html_visa61impacto;
?>
</div>
</label>
<label class="Label130">
<?php
echo $ETI['visa61requiereapoyo'];
?>
</label>
<label>
<div id="div_visa61requiereapoyo" class="field">
<?php
echo $html_visa61requiereapoyo;
?>
</div>
</label>
<label class="Label130">
<?php
echo $ETI['visa61fecharegistro'];
?>
</label>
<label class="Label220">
<div id="div_visa61fecharegistro">
<?php
echo html_oculto('visa61fecharegistro', $_REQUEST['visa61fecharegistro'], fecha_desdenumero($_REQUEST['visa61fecharegistro'])); //formato_FechaLargaDesdeNumero
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label130">&nbsp;</label>
<?php
$sEstiloElimina = 'display:none;';
if ((int)$_REQUEST['visa61id'] != 0) {
	$sEstiloElimina = 'inline-block;';
}
echo $objForma->htmlBotonSolo('bguarda2961', 'btMiniGuardar', 'guardaf2961()', $ETI['bt_mini_guardar_2961'], 30);
echo $objForma->htmlBotonSolo('blimpia2961', 'btMiniLimpiar', 'limpiaf2961()', $ETI['bt_mini_limpiar_2961'], 30);
echo $objForma->htmlBotonSolo('belimina2961', 'btMiniEliminar', 'eliminaf2961()', $ETI['bt_mini_eliminar_2961'], 30, $sEstiloElimina);
//Este es el cierre del div_p2961
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
<input id="bnombre2961" name="bnombre2961" type="text" value="<?php echo $_REQUEST['bnombre2961']; ?>" onchange="paginarf2961()" />
</label>
<label class="Label130">
<?php
echo $ETI['msg_Listar'];
?>
</label>
<label>
<?php
echo $html_blistar2961;
?>
</label>
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
<?php
}
?>
<div id="div_f2961detalle">
<?php
echo $sTabla2961;
?>
</div>
<?php
}
?>
<div class="salto1px"></div>
</div>
<?php
// -- Termina Grupo campos 2961 Dificultades
?>
<?php
// -- Inicia Grupo campos 2962 Compromisos
?>
<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo $ETI['titulo_2962'];
?>
</label>
<?php
if ($_REQUEST['paso'] == 2) {
?>
<input id="boculta2962" name="boculta2962" type="hidden" value="<?php echo $_REQUEST['boculta2962']; ?>" />
<?php
	if ($bEdita2962) {
?>
<div class="ir_derecha"<?php echo $sAnchoExpandeContrae; ?>>
<?php
if (false) {
	echo $objForma->htmlBotonSolo('btexcel2962', 'btMiniExcel', 'imprime2962()', 'Exportar', 30);
}
echo $objForma->htmlExpande(2962, $_REQUEST['boculta2962'], $ETI['bt_mostrar'], $ETI['bt_ocultar']);
$sEstiloDiv = '';
if ($_REQUEST['boculta2962'] != 0) {
	$sEstiloDiv = ' style="display:none;"';
}
?>
</div>
<div class="salto1px"></div>
<div id="div_p2962"<?php echo $sEstiloDiv; ?>>
<label class="Label130"<?php echo $sOcultaConsec; ?>>
<?php
echo $ETI['visa62consec'];
?>
</label>
<label class="Label130"<?php echo $sOcultaConsec; ?>>
<div id="div_visa62consec">
<?php
if ((int)$_REQUEST['visa62id'] == 0) {
?>
<input id="visa62consec" name="visa62consec" type="text" value="<?php echo $_REQUEST['visa62consec']; ?>" onchange="revisaf2962()" class="cuatro" />
<?php
} else {
	echo html_oculto('visa62consec', $_REQUEST['visa62consec'], formato_numero($_REQUEST['visa62consec']));
}
?>
</div>
</label>
<label class="Label60"<?php echo $sOcultaId; ?>>
<?php
echo $ETI['visa62id'];
?>
</label>
<label class="Label60"<?php echo $sOcultaId; ?>>
<div id="div_visa62id">
<?php
	echo html_oculto('visa62id', $_REQUEST['visa62id'], formato_numero($_REQUEST['visa62id']));
?>
</div>
</label>
<label class="txtAreaS">
<?php
echo $ETI['visa62descripcion'];
?>
<textarea id="visa62descripcion" name="visa62descripcion" placeholder="<?php echo $ETI['ing_campo'] . $ETI['visa62descripcion']; ?>"><?php echo $_REQUEST['visa62descripcion']; ?></textarea>
</label>
<div class="salto1px"></div>
<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo $ETI['visa62idresponsable'];
?>
</label>
<div class="salto1px"></div>
<input id="visa62idresponsable" name="visa62idresponsable" type="hidden" value="<?php echo $_REQUEST['visa62idresponsable']; ?>" />
<div id="div_visa62idresponsable_llaves">
<?php
echo $html_visa62idresponsable;
?>
</div>
<div class="salto1px"></div>
<div id="div_visa62idresponsable" class="L"><?php echo $visa62idresponsable_rs; ?></div>
<div class="salto1px"></div>
</div>
<label class="Label130">
<?php
echo $ETI['visa62fechalimite'];
?>
</label>
<div class="Campo220">
<?php
echo html_FechaEnNumero('visa62fechalimite', $_REQUEST['visa62fechalimite']); //, false, '', $iAgnoIni, $iAgnoFin); //$bvacio, $accion
?>
</div>
<?php
if (false) {
	echo $objForma->htmlBotonSolo('bvisa62fechalimite_hoy', 'btMiniHoy', "fecha_AsignarNum('visa62fechalimite', " . $iHoy . ")", $ETI['bt_hoy']);
}
?>
<label class="Label90">
<div id="div_visa62estado">
<?php
echo $ETI['visa62estado'];
?>
</label>
<label class="Label220">
<?php
echo $html_visa62estado;
?>
</div>
</label>
<label class="Label130">
<?php
echo $ETI['visa62fechacumple'];
?>
</label>
<div class="Campo220">
<?php
echo html_FechaEnNumero('visa62fechacumple', $_REQUEST['visa62fechacumple']); //, false, '', $iAgnoIni, $iAgnoFin); //$bvacio, $accion
?>
</div>
<?php
if (false) {
	echo $objForma->htmlBotonSolo('bvisa62fechacumple_hoy', 'btMiniHoy', "fecha_AsignarNum('visa62fechacumple', " . $iHoy . ")", $ETI['bt_hoy']);
}
?>
<label class="txtAreaS">
<?php
echo $ETI['visa62observaciones'];
?>
<textarea id="visa62observaciones" name="visa62observaciones" placeholder="<?php echo $ETI['ing_campo'] . $ETI['visa62observaciones']; ?>"><?php echo $_REQUEST['visa62observaciones']; ?></textarea>
</label>
<label class="Label130">
<?php
echo $ETI['visa62fecharegistro'];
?>
</label>
<label class="Label220">
<div id="div_visa62fecharegistro">
<?php
echo html_oculto('visa62fecharegistro', $_REQUEST['visa62fecharegistro'], fecha_desdenumero($_REQUEST['visa62fecharegistro'])); //formato_FechaLargaDesdeNumero
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label130">&nbsp;</label>
<?php
$sEstiloElimina = 'display:none;';
if ((int)$_REQUEST['visa62id'] != 0) {
	$sEstiloElimina = 'inline-block;';
}
echo $objForma->htmlBotonSolo('bguarda2962', 'btMiniGuardar', 'guardaf2962()', $ETI['bt_mini_guardar_2962'], 30);
echo $objForma->htmlBotonSolo('blimpia2962', 'btMiniLimpiar', 'limpiaf2962()', $ETI['bt_mini_limpiar_2962'], 30);
echo $objForma->htmlBotonSolo('belimina2962', 'btMiniEliminar', 'eliminaf2962()', $ETI['bt_mini_eliminar_2962'], 30, $sEstiloElimina);
//Este es el cierre del div_p2962
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
<input id="bnombre2962" name="bnombre2962" type="text" value="<?php echo $_REQUEST['bnombre2962']; ?>" onchange="paginarf2962()" />
</label>
<label class="Label130">
<?php
echo $ETI['msg_Listar'];
?>
</label>
<label>
<?php
echo $html_blistar2962;
?>
</label>
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
<?php
}
?>
<div id="div_f2962detalle">
<?php
echo $sTabla2962;
?>
</div>
<?php
}
?>
<div class="salto1px"></div>
</div>
<?php
// -- Termina Grupo campos 2962 Compromisos
?>
<?php
// -- Inicia Grupo campos 2963 Solicitud de apoyo
?>
<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo $ETI['titulo_2963'];
?>
</label>
<?php
if ($_REQUEST['paso'] == 2) {
?>
<input id="boculta2963" name="boculta2963" type="hidden" value="<?php echo $_REQUEST['boculta2963']; ?>" />
<?php
	if ($bEdita2963) {
?>
<div class="ir_derecha"<?php echo $sAnchoExpandeContrae; ?>>
<?php
if (false) {
	echo $objForma->htmlBotonSolo('btexcel2963', 'btMiniExcel', 'imprime2963()', 'Exportar', 30);
}
echo $objForma->htmlExpande(2963, $_REQUEST['boculta2963'], $ETI['bt_mostrar'], $ETI['bt_ocultar']);
$sEstiloDiv = '';
if ($_REQUEST['boculta2963'] != 0) {
	$sEstiloDiv = ' style="display:none;"';
}
?>
</div>
<div class="salto1px"></div>
<div id="div_p2963"<?php echo $sEstiloDiv; ?>>
<label class="Label130"<?php echo $sOcultaConsec; ?>>
<?php
echo $ETI['visa63consec'];
?>
</label>
<label class="Label130"<?php echo $sOcultaConsec; ?>>
<div id="div_visa63consec">
<?php
if ((int)$_REQUEST['visa63id'] == 0) {
?>
<input id="visa63consec" name="visa63consec" type="text" value="<?php echo $_REQUEST['visa63consec']; ?>" onchange="revisaf2963()" class="cuatro" />
<?php
} else {
	echo html_oculto('visa63consec', $_REQUEST['visa63consec'], formato_numero($_REQUEST['visa63consec']));
}
?>
</div>
</label>
<label class="Label60"<?php echo $sOcultaId; ?>>
<?php
echo $ETI['visa63id'];
?>
</label>
<label class="Label60"<?php echo $sOcultaId; ?>>
<div id="div_visa63id">
<?php
	echo html_oculto('visa63id', $_REQUEST['visa63id'], formato_numero($_REQUEST['visa63id']));
?>
</div>
</label>
<label class="txtAreaS">
<?php
echo $ETI['visa63descripcion'];
?>
<textarea id="visa63descripcion" name="visa63descripcion" placeholder="<?php echo $ETI['ing_campo'] . $ETI['visa63descripcion']; ?>"><?php echo $_REQUEST['visa63descripcion']; ?></textarea>
</label>
<div class="salto1px"></div>
<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo $ETI['visa63idcolaborador'];
?>
</label>
<div class="salto1px"></div>
<input id="visa63idcolaborador" name="visa63idcolaborador" type="hidden" value="<?php echo $_REQUEST['visa63idcolaborador']; ?>" />
<div id="div_visa63idcolaborador_llaves">
<?php
echo $html_visa63idcolaborador;
?>
</div>
<div class="salto1px"></div>
<div id="div_visa63idcolaborador" class="L"><?php echo $visa63idcolaborador_rs; ?></div>
<div class="salto1px"></div>
</div>
<label class="Label90">
<div id="div_visa63estado">
<?php
echo $ETI['visa63estado'];
?>
</label>
<label class="Label220">
<?php
echo $html_visa63estado;
?>
</div>
</label>
<label class="Label130">
<?php
echo $ETI['visa63fechasolicitud'];
?>
</label>
<div class="Campo220">
<?php
echo html_FechaEnNumero('visa63fechasolicitud', $_REQUEST['visa63fechasolicitud']); //, false, '', $iAgnoIni, $iAgnoFin); //$bvacio, $accion
?>
</div>
<?php
if (false) {
	echo $objForma->htmlBotonSolo('bvisa63fechasolicitud_hoy', 'btMiniHoy', "fecha_AsignarNum('visa63fechasolicitud', " . $iHoy . ")", $ETI['bt_hoy']);
}
?>
<label class="Label130">
<?php
echo $ETI['visa63fecharespuesta'];
?>
</label>
<div class="Campo220">
<?php
echo html_FechaEnNumero('visa63fecharespuesta', $_REQUEST['visa63fecharespuesta']); //, false, '', $iAgnoIni, $iAgnoFin); //$bvacio, $accion
?>
</div>
<?php
if (false) {
	echo $objForma->htmlBotonSolo('bvisa63fecharespuesta_hoy', 'btMiniHoy', "fecha_AsignarNum('visa63fecharespuesta', " . $iHoy . ")", $ETI['bt_hoy']);
}
?>
<label class="txtAreaS">
<?php
echo $ETI['visa63observaciones'];
?>
<textarea id="visa63observaciones" name="visa63observaciones" placeholder="<?php echo $ETI['ing_campo'] . $ETI['visa63observaciones']; ?>"><?php echo $_REQUEST['visa63observaciones']; ?></textarea>
</label>
<div class="salto1px"></div>
<label class="Label130">&nbsp;</label>
<?php
$sEstiloElimina = 'display:none;';
if ((int)$_REQUEST['visa63id'] != 0) {
	$sEstiloElimina = 'inline-block;';
}
echo $objForma->htmlBotonSolo('bguarda2963', 'btMiniGuardar', 'guardaf2963()', $ETI['bt_mini_guardar_2963'], 30);
echo $objForma->htmlBotonSolo('blimpia2963', 'btMiniLimpiar', 'limpiaf2963()', $ETI['bt_mini_limpiar_2963'], 30);
echo $objForma->htmlBotonSolo('belimina2963', 'btMiniEliminar', 'eliminaf2963()', $ETI['bt_mini_eliminar_2963'], 30, $sEstiloElimina);
//Este es el cierre del div_p2963
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
<input id="bnombre2963" name="bnombre2963" type="text" value="<?php echo $_REQUEST['bnombre2963']; ?>" onchange="paginarf2963()" />
</label>
<label class="Label130">
<?php
echo $ETI['msg_Listar'];
?>
</label>
<label>
<?php
echo $html_blistar2963;
?>
</label>
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
<?php
}
?>
<div id="div_f2963detalle">
<?php
echo $sTabla2963;
?>
</div>
<?php
}
?>
<div class="salto1px"></div>
</div>
<?php
// -- Termina Grupo campos 2963 Solicitud de apoyo
?>
<?php
if (false) {
	//Ejemplo de boton de ayuda
	//echo html_BotonAyuda('NombreCampo');
	//echo html_DivAyudaLocal('NombreCampo');
}
if ($bConExpande) {
	//Este es el cierre del div_p2957
?>
<div class="salto1px"></div>
</div>
<?php
}
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
echo $ETI['msg_bsistema'];
?>
</label>
<label>
<div id="div_bsistema" class="field">
<?php
echo $html_bsistema;
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label90">
<?php
echo $ETI['msg_btitulo'];
?>
</label>
<label>
<input id="btitulo" name="btitulo" type="text" value="<?php echo $_REQUEST['btitulo']; ?>" onchange="paginarf2957()" autocomplete="off" />
</label>
<div class="salto1px"></div>
<label class="Label90">
<?php
echo $ETI['msg_bestado'];
?>
</label>
<label>
<div id="div_bestado" class="field">
<?php
echo $html_bestado;
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label90">
<?php
echo $ETI['msg_bprioridad'];
?>
</label>
<label>
<div id="div_bprioridad" class="field">
<?php
echo $html_bprioridad;
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label90">
<?php
echo $ETI['msg_bfechaini'];
?>
</label>
<label class="Label250">
<?php
echo html_FechaEnNumero('bfechaini', $_REQUEST['bfechaini'], true, 'paginarf2957()');
?>
</label>
<div class="salto1px"></div>
<label class="Label90">
<?php
echo $ETI['msg_bfechafin'];
?>
</label>
<label class="Label250">
<?php
echo html_FechaEnNumero('bfechafin', $_REQUEST['bfechafin'], true, 'paginarf2957()');
?>
</label>
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
<?php
echo ' ' . $csv_separa;
?>
<div class="salto1px"></div>
<div id="div_f2957detalle">
<?php
echo $sTabla2957;
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
echo $ETI['msg_visa57consec'];
?>
</label>
<label class="Label90">
<?php
echo '<b>' . $_REQUEST['visa57consec'] . '</b>';
?>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['msg_visa57consec_nuevo'];
// onchange="RevisaConsec()"
?>
</label>
<label class="Label90">
<input id="visa57consec_nuevo" name="visa57consec_nuevo" type="text" value="<?php echo $_REQUEST['visa57consec_nuevo']; ?>" class="cuatro" />
</label>
<div class="salto1px"></div>
<label class="Label160">&nbsp;</label>
<?php
echo $objForma->htmlBotonSolo('cmdCambiaConsecFinal', 'botonProceso', 'mod_consec();', $ETI['bt_cambiar'], 130);
echo $objForma->htmlFinMarco();
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
<input id="titulo_2957" name="titulo_2957" type="hidden" value="<?php echo $sTituloModulo; ?>" />
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
	$iSegundos = $iSegFin - $iSegIni;
	$sDebug = $sDebug . log_debug('Tiempo total del proceso: <b>' . $iSegundos . '</b> Segundos');
	echo console_debug($sDebug);
}
?>
<input id="scampobusca" name="scampobusca" type="hidden" value="" />
<input id="iscroll" name="iscroll" type="hidden" value="<?php echo $_REQUEST['iscroll']; ?>" />
<input id="itipoerror" name="itipoerror" type="hidden" value="<?php echo $iTipoError; ?>" />
<input id="debug" name="debug" type="hidden" value="<?php echo $_REQUEST['debug']; ?>" />
<input id="msg_requerido" name="msg_requerido" type="hidden" value="<?php echo $ERR['requeridos']; ?>" />
<input id="msg_pendiente" name="msg_pendiente" type="hidden" value="<?php echo $ETI['msg_pendiente']; ?>" />
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
<script language="javascript" src="ac_2957.js"></script>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>unad_todas2024v2.js"></script>
<?php
forma_piedepagina();

