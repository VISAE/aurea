<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2023 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.29.2 lunes, 13 de febrero de 2023
*/
/** Archivo aurebitacora.php.
 * Modulo 251 aure51bitacora.
 * @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
 * @param debug = 1  (Opcional), bandera para indicar si se generan datos de depuración
 * @date lunes, 13 de febrero de 2023
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
//require $APP->rutacomun . 'libdatos.php';
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
$grupo_id = 1; //Necesita ajustarlo...
$iCodModulo = 251;
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
$mensajes_200 = 'lg/lg_200_' . $_SESSION['unad_idioma'] . '.php';
if (!file_exists($mensajes_200)) {
	$mensajes_200 = 'lg/lg_200_es.php';
}
require $mensajes_200;
*/
$mensajes_251 = 'lg/lg_251_' . $_SESSION['unad_idioma'] . '.php';
if (!file_exists($mensajes_251)) {
	$mensajes_251 = 'lg/lg_251_es.php';
}
require $mensajes_todas;
require $mensajes_251;
$xajax = NULL;
$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
if ($APP->dbpuerto != '') {
	$objDB->dbPuerto = $APP->dbpuerto;
}
$iPiel = iDefinirPiel($APP, 1);
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
	list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 1, $_SESSION['unad_id_tercero'], $objDB);
	if (!$bDevuelve) {
		$bCerrado = true;
		$sMsgCierre = '<div class="MarquesinaGrande">No cuenta con permiso para acceder a este modulo [' . $iCodModulo . '].</div>';
		list($et_menu, $sDebugM) = html_menuV2($APP->idsistema, $objDB, $iPiel, false, $_SESSION['unad_id_tercero']);
	}
}
if ($bCerrado) {
	$objDB->CerrarConexion();
	require $APP->rutacomun . 'unad_forma_v2.php';
	forma_cabeceraV3($xajax, $ETI['titulo_251']);
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
if (!$bPeticionXAJAX) {
	if (noticias_pendientes($objDB)) {
		$objDB->CerrarConexion();
		header('Location:noticia.php?ret=aurebitacora.php');
		die();
	}
}
$idTercero = $_SESSION['unad_id_tercero'];
$bOtroUsuario = false;
$seg_1707 = 0;
$bDevuelve = false;
//list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 1707, $_SESSION['unad_id_tercero'], $objDB, $bDebug);
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
//$idEntidad = Traer_Entidad();
$mensajes_252 = 'lg/lg_252_' . $_SESSION['unad_idioma'] . '.php';
if (!file_exists($mensajes_252)) {
	$mensajes_252 = 'lg/lg_252_es.php';
}
$mensajes_253 = 'lg/lg_253_' . $_SESSION['unad_idioma'] . '.php';
if (!file_exists($mensajes_253)) {
	$mensajes_253 = 'lg/lg_253_es.php';
}
$mensajes_257 = 'lg/lg_257_' . $_SESSION['unad_idioma'] . '.php';
if (!file_exists($mensajes_257)) {
	$mensajes_257 = 'lg/lg_257_es.php';
}
$mensajes_280 = 'lg/lg_280_' . $_SESSION['unad_idioma'] . '.php';
if (!file_exists($mensajes_280)) {
	$mensajes_280 = 'lg/lg_280_es.php';
}
$mensajes_281 = 'lg/lg_281_' . $_SESSION['unad_idioma'] . '.php';
if (!file_exists($mensajes_281)) {
	$mensajes_281 = 'lg/lg_281_es.php';
}
$mensajes_282 = 'lg/lg_282_' . $_SESSION['unad_idioma'] . '.php';
if (!file_exists($mensajes_282)) {
	$mensajes_282 = 'lg/lg_282_es.php';
}
$mensajes_283 = 'lg/lg_283_' . $_SESSION['unad_idioma'] . '.php';
if (!file_exists($mensajes_283)) {
	$mensajes_283 = 'lg/lg_283_es.php';
}
require $mensajes_252;
require $mensajes_253;
require $mensajes_257;
require $mensajes_280;
require $mensajes_281;
require $mensajes_282;
require $mensajes_283;
// -- Si esta cargando la pagina por primer vez se revisa si requiere auditar y se manda a hacer un limpiar (paso -1)
if (isset($_REQUEST['paso']) == 0) {
	$_REQUEST['paso'] = -1;
	if ($audita[1]) {
		seg_auditaingreso($iCodModulo, $_SESSION['unad_id_tercero'], $objDB);
	}
}
// -- 251 aure51bitacora
require 'lib251.php';
// -- 252 Participantes
require 'lib252.php';
// -- 253 Anexos
require 'lib253.php';
// -- 257 Riesgos
require 'lib257.php';
// -- 280 Historia de usuario
require 'lib280.php';
// -- 281 Tareas de ingenieria
require 'lib281.php';
// -- 282 Pruebas de aceptacion
require 'lib282.php';
// -- 283 Tarjetas CRC
require 'lib283.php';
$xajax = new xajax();
$xajax->configure('javascript URI', $APP->rutacomun . 'xajax/');
$xajax->register(XAJAX_FUNCTION, 'unad11_Mostrar_v2');
$xajax->register(XAJAX_FUNCTION, 'unad11_TraerXid');
$xajax->register(XAJAX_FUNCTION, 'sesion_abandona_V2');
$xajax->register(XAJAX_FUNCTION, 'sesion_mantenerV4');
$xajax->register(XAJAX_FUNCTION, 'f251_HtmlTabla');
$xajax->register(XAJAX_FUNCTION, 'f251_ExisteDato');
$xajax->register(XAJAX_FUNCTION, 'f251_Busquedas');
$xajax->register(XAJAX_FUNCTION, 'f251_HtmlBusqueda');
$xajax->register(XAJAX_FUNCTION, 'f252_Guardar');
$xajax->register(XAJAX_FUNCTION, 'f252_Traer');
$xajax->register(XAJAX_FUNCTION, 'f252_Eliminar');
$xajax->register(XAJAX_FUNCTION, 'f252_HtmlTabla');
$xajax->register(XAJAX_FUNCTION, 'f252_PintarLlaves');
$xajax->register(XAJAX_FUNCTION, 'elimina_archivo_aure58idarchivo');
$xajax->register(XAJAX_FUNCTION, 'f253_Guardar');
$xajax->register(XAJAX_FUNCTION, 'f253_Traer');
$xajax->register(XAJAX_FUNCTION, 'f253_Eliminar');
$xajax->register(XAJAX_FUNCTION, 'f253_HtmlTabla');
$xajax->register(XAJAX_FUNCTION, 'f253_PintarLlaves');
$xajax->register(XAJAX_FUNCTION, 'f257_Guardar');
$xajax->register(XAJAX_FUNCTION, 'f257_Traer');
$xajax->register(XAJAX_FUNCTION, 'f257_Eliminar');
$xajax->register(XAJAX_FUNCTION, 'f257_HtmlTabla');
$xajax->register(XAJAX_FUNCTION, 'f257_PintarLlaves');
$xajax->register(XAJAX_FUNCTION, 'f280_Guardar');
$xajax->register(XAJAX_FUNCTION, 'f280_Traer');
$xajax->register(XAJAX_FUNCTION, 'f280_Eliminar');
$xajax->register(XAJAX_FUNCTION, 'f280_HtmlTabla');
$xajax->register(XAJAX_FUNCTION, 'f280_PintarLlaves');
$xajax->register(XAJAX_FUNCTION, 'f281_Comboaure81idtipotarea');
$xajax->register(XAJAX_FUNCTION, 'f281_Guardar');
$xajax->register(XAJAX_FUNCTION, 'f281_Traer');
$xajax->register(XAJAX_FUNCTION, 'f281_Eliminar');
$xajax->register(XAJAX_FUNCTION, 'f281_HtmlTabla');
$xajax->register(XAJAX_FUNCTION, 'f281_PintarLlaves');
$xajax->register(XAJAX_FUNCTION, 'f282_Guardar');
$xajax->register(XAJAX_FUNCTION, 'f282_Traer');
$xajax->register(XAJAX_FUNCTION, 'f282_Eliminar');
$xajax->register(XAJAX_FUNCTION, 'f282_HtmlTabla');
$xajax->register(XAJAX_FUNCTION, 'f282_PintarLlaves');
$xajax->register(XAJAX_FUNCTION, 'f283_Guardar');
$xajax->register(XAJAX_FUNCTION, 'f283_Traer');
$xajax->register(XAJAX_FUNCTION, 'f283_Eliminar');
$xajax->register(XAJAX_FUNCTION, 'f283_HtmlTabla');
$xajax->register(XAJAX_FUNCTION, 'f283_PintarLlaves');
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
if (isset($_REQUEST['paginaf251']) == 0) {
	$_REQUEST['paginaf251'] = 1;
}
if (isset($_REQUEST['lppf251']) == 0) {
	$_REQUEST['lppf251'] = 20;
}
if (isset($_REQUEST['boculta251']) == 0) {
	$_REQUEST['boculta251'] = 0;
}
if (isset($_REQUEST['paginaf252']) == 0) {
	$_REQUEST['paginaf252'] = 1;
}
if (isset($_REQUEST['lppf252']) == 0) {
	$_REQUEST['lppf252'] = 20;
}
if (isset($_REQUEST['boculta252']) == 0) {
	$_REQUEST['boculta252'] = 0;
}
if (isset($_REQUEST['paginaf253']) == 0) {
	$_REQUEST['paginaf253'] = 1;
}
if (isset($_REQUEST['lppf253']) == 0) {
	$_REQUEST['lppf253'] = 20;
}
if (isset($_REQUEST['boculta253']) == 0) {
	$_REQUEST['boculta253'] = 0;
}
if (isset($_REQUEST['paginaf257']) == 0) {
	$_REQUEST['paginaf257'] = 1;
}
if (isset($_REQUEST['lppf257']) == 0) {
	$_REQUEST['lppf257'] = 20;
}
if (isset($_REQUEST['boculta257']) == 0) {
	$_REQUEST['boculta257'] = 0;
}
if (isset($_REQUEST['paginaf280']) == 0) {
	$_REQUEST['paginaf280'] = 1;
}
if (isset($_REQUEST['lppf280']) == 0) {
	$_REQUEST['lppf280'] = 20;
}
if (isset($_REQUEST['boculta280']) == 0) {
	$_REQUEST['boculta280'] = 0;
}
if (isset($_REQUEST['paginaf281']) == 0) {
	$_REQUEST['paginaf281'] = 1;
}
if (isset($_REQUEST['lppf281']) == 0) {
	$_REQUEST['lppf281'] = 20;
}
if (isset($_REQUEST['boculta281']) == 0) {
	$_REQUEST['boculta281'] = 0;
}
if (isset($_REQUEST['paginaf282']) == 0) {
	$_REQUEST['paginaf282'] = 1;
}
if (isset($_REQUEST['lppf282']) == 0) {
	$_REQUEST['lppf282'] = 20;
}
if (isset($_REQUEST['boculta282']) == 0) {
	$_REQUEST['boculta282'] = 0;
}
if (isset($_REQUEST['paginaf283']) == 0) {
	$_REQUEST['paginaf283'] = 1;
}
if (isset($_REQUEST['lppf283']) == 0) {
	$_REQUEST['lppf283'] = 20;
}
if (isset($_REQUEST['boculta283']) == 0) {
	$_REQUEST['boculta283'] = 0;
}
// -- Inicializar variables de datos.
if (isset($_REQUEST['aure51idproyecto']) == 0) {
	$_REQUEST['aure51idproyecto'] = '';
}
if (isset($_REQUEST['aure51consec']) == 0) {
	$_REQUEST['aure51consec'] = '';
}
if (isset($_REQUEST['aure51consec_nuevo']) == 0) {
	$_REQUEST['aure51consec_nuevo'] = '';
}
if (isset($_REQUEST['aure51idpadre']) == 0) {
	$_REQUEST['aure51idpadre'] = '';
}
if (isset($_REQUEST['aure51orden']) == 0) {
	$_REQUEST['aure51orden'] = '';
}
if (isset($_REQUEST['aure51id']) == 0) {
	$_REQUEST['aure51id'] = '';
}
if (isset($_REQUEST['aure51estado']) == 0) {
	$_REQUEST['aure51estado'] = 0;
}
if (isset($_REQUEST['aure51fecha']) == 0) {
	$_REQUEST['aure51fecha'] = '';
	//$_REQUEST['aure51fecha'] = $iHoy;
}
if (isset($_REQUEST['aure51horaini']) == 0) {
	$_REQUEST['aure51horaini'] = fecha_hora();
}
if (isset($_REQUEST['aure51minini']) == 0) {
	$_REQUEST['aure51minini'] = fecha_minuto();
}
if (isset($_REQUEST['aure51horafin']) == 0) {
	$_REQUEST['aure51horafin'] = fecha_hora();
}
if (isset($_REQUEST['aure51minfin']) == 0) {
	$_REQUEST['aure51minfin'] = fecha_minuto();
}
if (isset($_REQUEST['aure51idsistema']) == 0) {
	$_REQUEST['aure51idsistema'] = 0;
}
if (isset($_REQUEST['aure51actividad']) == 0) {
	$_REQUEST['aure51actividad'] = '';
}
if (isset($_REQUEST['aure51lugar']) == 0) {
	$_REQUEST['aure51lugar'] = '';
}
if (isset($_REQUEST['aure51detalleactiv']) == 0) {
	$_REQUEST['aure51detalleactiv'] = '';
}
if (isset($_REQUEST['aure51objetivo']) == 0) {
	$_REQUEST['aure51objetivo'] = '';
}
if (isset($_REQUEST['aure51resultado']) == 0) {
	$_REQUEST['aure51resultado'] = '';
}
if (isset($_REQUEST['aure51idresponsable']) == 0) {
	$_REQUEST['aure51idresponsable'] = 0;
	//$_REQUEST['aure51idresponsable'] = $idTercero;
}
if (isset($_REQUEST['aure51idresponsable_td']) == 0) {
	$_REQUEST['aure51idresponsable_td'] = $APP->tipo_doc;
}
if (isset($_REQUEST['aure51idresponsable_doc']) == 0) {
	$_REQUEST['aure51idresponsable_doc'] = '';
}
if (isset($_REQUEST['aure51tiporesultado']) == 0) {
	$_REQUEST['aure51tiporesultado'] = '';
}
$_REQUEST['aure51idproyecto'] = numeros_validar($_REQUEST['aure51idproyecto']);
$_REQUEST['aure51consec'] = numeros_validar($_REQUEST['aure51consec']);
$_REQUEST['aure51idpadre'] = numeros_validar($_REQUEST['aure51idpadre']);
$_REQUEST['aure51orden'] = cadena_Validar($_REQUEST['aure51orden']);
$_REQUEST['aure51id'] = numeros_validar($_REQUEST['aure51id']);
$_REQUEST['aure51estado'] = numeros_validar($_REQUEST['aure51estado']);
$_REQUEST['aure51fecha'] = numeros_validar($_REQUEST['aure51fecha']);
$_REQUEST['aure51horaini'] = numeros_validar($_REQUEST['aure51horaini']);
$_REQUEST['aure51minini'] = numeros_validar($_REQUEST['aure51minini']);
$_REQUEST['aure51horafin'] = numeros_validar($_REQUEST['aure51horafin']);
$_REQUEST['aure51minfin'] = numeros_validar($_REQUEST['aure51minfin']);
$_REQUEST['aure51idsistema'] = numeros_validar($_REQUEST['aure51idsistema']);
$_REQUEST['aure51actividad'] = cadena_Validar($_REQUEST['aure51actividad']);
$_REQUEST['aure51lugar'] = cadena_Validar($_REQUEST['aure51lugar']);
$_REQUEST['aure51detalleactiv'] = cadena_Validar($_REQUEST['aure51detalleactiv']);
$_REQUEST['aure51objetivo'] = cadena_Validar($_REQUEST['aure51objetivo']);
$_REQUEST['aure51resultado'] = cadena_Validar($_REQUEST['aure51resultado']);
$_REQUEST['aure51idresponsable'] = numeros_validar($_REQUEST['aure51idresponsable']);
$_REQUEST['aure51idresponsable_td'] = cadena_Validar($_REQUEST['aure51idresponsable_td']);
$_REQUEST['aure51idresponsable_doc'] = cadena_Validar($_REQUEST['aure51idresponsable_doc']);
$_REQUEST['aure51tiporesultado'] = numeros_validar($_REQUEST['aure51tiporesultado']);
if ((int)$_REQUEST['paso'] > 0) {
	//Participantes
	if (isset($_REQUEST['aure52idbitacora']) == 0) {
		$_REQUEST['aure52idbitacora'] = '';
	}
	if (isset($_REQUEST['aure52idtercero']) == 0) {
		$_REQUEST['aure52idtercero'] = 0;
		//$_REQUEST['aure52idtercero'] =  $idTercero;
	}
	if (isset($_REQUEST['aure52idtercero_td']) == 0) {
		$_REQUEST['aure52idtercero_td'] = $APP->tipo_doc;
	}
	if (isset($_REQUEST['aure52idtercero_doc']) == 0) {
		$_REQUEST['aure52idtercero_doc'] = '';
	}
	if (isset($_REQUEST['aure52id']) == 0) {
		$_REQUEST['aure52id'] = '';
	}
	if (isset($_REQUEST['aure52activo']) == 0) {
		$_REQUEST['aure52activo'] = 'S';
	}
	$_REQUEST['aure52idbitacora'] = numeros_validar($_REQUEST['aure52idbitacora']);
	$_REQUEST['aure52idtercero'] = numeros_validar($_REQUEST['aure52idtercero']);
	$_REQUEST['aure52idtercero_td'] = cadena_Validar($_REQUEST['aure52idtercero_td']);
	$_REQUEST['aure52idtercero_doc'] = cadena_Validar($_REQUEST['aure52idtercero_doc']);
	$_REQUEST['aure52id'] = numeros_validar($_REQUEST['aure52id']);
	$_REQUEST['aure52activo'] = numeros_validar($_REQUEST['aure52activo']);
	//Anexos
	if (isset($_REQUEST['aure58idbitacora']) == 0) {
		$_REQUEST['aure58idbitacora'] = '';
	}
	if (isset($_REQUEST['aure58consec']) == 0) {
		$_REQUEST['aure58consec'] = '';
	}
	if (isset($_REQUEST['aure58id']) == 0) {
		$_REQUEST['aure58id'] = '';
	}
	if (isset($_REQUEST['aure58titulo']) == 0) {
		$_REQUEST['aure58titulo'] = '';
	}
	if (isset($_REQUEST['aure58idorigen']) == 0) {
		$_REQUEST['aure58idorigen'] = 0;
	}
	if (isset($_REQUEST['aure58idarchivo']) == 0) {
		$_REQUEST['aure58idarchivo'] = 0;
	}
	if (isset($_REQUEST['aure58idusuario']) == 0) {
		$_REQUEST['aure58idusuario'] = 0;
		//$_REQUEST['aure58idusuario'] =  $idTercero;
	}
	if (isset($_REQUEST['aure58idusuario_td']) == 0) {
		$_REQUEST['aure58idusuario_td'] = $APP->tipo_doc;
	}
	if (isset($_REQUEST['aure58idusuario_doc']) == 0) {
		$_REQUEST['aure58idusuario_doc'] = '';
	}
	if (isset($_REQUEST['aure58fecha']) == 0) {
		$_REQUEST['aure58fecha'] = '';
		//$_REQUEST['aure58fecha'] = $iHoy;
	}
	$_REQUEST['aure58idbitacora'] = numeros_validar($_REQUEST['aure58idbitacora']);
	$_REQUEST['aure58consec'] = numeros_validar($_REQUEST['aure58consec']);
	$_REQUEST['aure58id'] = numeros_validar($_REQUEST['aure58id']);
	$_REQUEST['aure58titulo'] = cadena_Validar($_REQUEST['aure58titulo']);
	$_REQUEST['aure58idorigen'] = numeros_validar($_REQUEST['aure58idorigen']);
	$_REQUEST['aure58idarchivo'] = numeros_validar($_REQUEST['aure58idarchivo']);
	$_REQUEST['aure58idusuario'] = numeros_validar($_REQUEST['aure58idusuario']);
	$_REQUEST['aure58idusuario_td'] = cadena_Validar($_REQUEST['aure58idusuario_td']);
	$_REQUEST['aure58idusuario_doc'] = cadena_Validar($_REQUEST['aure58idusuario_doc']);
	$_REQUEST['aure58fecha'] = numeros_validar($_REQUEST['aure58fecha']);
	//Riesgos
	if (isset($_REQUEST['aure57idbitacora']) == 0) {
		$_REQUEST['aure57idbitacora'] = '';
	}
	if (isset($_REQUEST['aure57idriesgo']) == 0) {
		$_REQUEST['aure57idriesgo'] = '';
	}
	if (isset($_REQUEST['aure57id']) == 0) {
		$_REQUEST['aure57id'] = '';
	}
	if (isset($_REQUEST['aure57nivelriesgo']) == 0) {
		$_REQUEST['aure57nivelriesgo'] = '';
	}
	$_REQUEST['aure57idbitacora'] = numeros_validar($_REQUEST['aure57idbitacora']);
	$_REQUEST['aure57idriesgo'] = numeros_validar($_REQUEST['aure57idriesgo']);
	$_REQUEST['aure57id'] = numeros_validar($_REQUEST['aure57id']);
	$_REQUEST['aure57nivelriesgo'] = numeros_validar($_REQUEST['aure57nivelriesgo']);
	//Historia de usuario
	if (isset($_REQUEST['aure80idbitacora']) == 0) {
		$_REQUEST['aure80idbitacora'] = '';
	}
	if (isset($_REQUEST['aure80consec']) == 0) {
		$_REQUEST['aure80consec'] = '';
	}
	if (isset($_REQUEST['aure80id']) == 0) {
		$_REQUEST['aure80id'] = '';
	}
	if (isset($_REQUEST['aure80momento']) == 0) {
		$_REQUEST['aure80momento'] = '';
	}
	if (isset($_REQUEST['aure80infousuario']) == 0) {
		$_REQUEST['aure80infousuario'] = '';
	}
	if (isset($_REQUEST['aure80prioridad']) == 0) {
		$_REQUEST['aure80prioridad'] = '';
	}
	if (isset($_REQUEST['aure80semanaest']) == 0) {
		$_REQUEST['aure80semanaest'] = '';
	}
	if (isset($_REQUEST['aure80diasest']) == 0) {
		$_REQUEST['aure80diasest'] = '';
	}
	if (isset($_REQUEST['aure80iteracionasig']) == 0) {
		$_REQUEST['aure80iteracionasig'] = '';
	}
	if (isset($_REQUEST['aure80infotecnica']) == 0) {
		$_REQUEST['aure80infotecnica'] = '';
	}
	if (isset($_REQUEST['aure80observaciones']) == 0) {
		$_REQUEST['aure80observaciones'] = '';
	}
	$_REQUEST['aure80idbitacora'] = numeros_validar($_REQUEST['aure80idbitacora']);
	$_REQUEST['aure80consec'] = numeros_validar($_REQUEST['aure80consec']);
	$_REQUEST['aure80id'] = numeros_validar($_REQUEST['aure80id']);
	$_REQUEST['aure80momento'] = numeros_validar($_REQUEST['aure80momento']);
	$_REQUEST['aure80infousuario'] = cadena_Validar($_REQUEST['aure80infousuario']);
	$_REQUEST['aure80prioridad'] = numeros_validar($_REQUEST['aure80prioridad']);
	$_REQUEST['aure80semanaest'] = numeros_validar($_REQUEST['aure80semanaest']);
	$_REQUEST['aure80diasest'] = numeros_validar($_REQUEST['aure80diasest']);
	$_REQUEST['aure80iteracionasig'] = numeros_validar($_REQUEST['aure80iteracionasig']);
	$_REQUEST['aure80infotecnica'] = cadena_Validar($_REQUEST['aure80infotecnica']);
	$_REQUEST['aure80observaciones'] = cadena_Validar($_REQUEST['aure80observaciones']);
	//Tareas de ingenieria
	if (isset($_REQUEST['aure81idbitacora']) == 0) {
		$_REQUEST['aure81idbitacora'] = '';
	}
	if (isset($_REQUEST['aure81consec']) == 0) {
		$_REQUEST['aure81consec'] = '';
	}
	if (isset($_REQUEST['aure81id']) == 0) {
		$_REQUEST['aure81id'] = '';
	}
	if (isset($_REQUEST['aure81idbithistoria']) == 0) {
		$_REQUEST['aure81idbithistoria'] = '';
	}
	if (isset($_REQUEST['aure81idtipotarea']) == 0) {
		$_REQUEST['aure81idtipotarea'] = '';
	}
	if (isset($_REQUEST['aure81semanasest']) == 0) {
		$_REQUEST['aure81semanasest'] = '';
	}
	if (isset($_REQUEST['aure81diasest']) == 0) {
		$_REQUEST['aure81diasest'] = '';
	}
	if (isset($_REQUEST['aure81fechainicio']) == 0) {
		$_REQUEST['aure81fechainicio'] = '';
		//$_REQUEST['aure81fechainicio'] = $iHoy;
	}
	if (isset($_REQUEST['aure81avance']) == 0) {
		$_REQUEST['aure81avance'] = '';
	}
	if (isset($_REQUEST['aure81fechafinal']) == 0) {
		$_REQUEST['aure81fechafinal'] = '';
		//$_REQUEST['aure81fechafinal'] = $iHoy;
	}
	if (isset($_REQUEST['aure81descripcion']) == 0) {
		$_REQUEST['aure81descripcion'] = '';
	}
	$_REQUEST['aure81idbitacora'] = numeros_validar($_REQUEST['aure81idbitacora']);
	$_REQUEST['aure81consec'] = numeros_validar($_REQUEST['aure81consec']);
	$_REQUEST['aure81id'] = numeros_validar($_REQUEST['aure81id']);
	$_REQUEST['aure81idbithistoria'] = numeros_validar($_REQUEST['aure81idbithistoria']);
	$_REQUEST['aure81idtipotarea'] = numeros_validar($_REQUEST['aure81idtipotarea']);
	$_REQUEST['aure81semanasest'] = numeros_validar($_REQUEST['aure81semanasest']);
	$_REQUEST['aure81diasest'] = numeros_validar($_REQUEST['aure81diasest']);
	$_REQUEST['aure81fechainicio'] = numeros_validar($_REQUEST['aure81fechainicio']);
	$_REQUEST['aure81avance'] = numeros_validar($_REQUEST['aure81avance']);
	$_REQUEST['aure81fechafinal'] = numeros_validar($_REQUEST['aure81fechafinal']);
	$_REQUEST['aure81descripcion'] = cadena_Validar($_REQUEST['aure81descripcion']);
	//Pruebas de aceptacion
	if (isset($_REQUEST['aure82idbitacora']) == 0) {
		$_REQUEST['aure82idbitacora'] = '';
	}
	if (isset($_REQUEST['aure82consec']) == 0) {
		$_REQUEST['aure82consec'] = '';
	}
	if (isset($_REQUEST['aure82id']) == 0) {
		$_REQUEST['aure82id'] = '';
	}
	if (isset($_REQUEST['aure82condiciones']) == 0) {
		$_REQUEST['aure82condiciones'] = '';
	}
	if (isset($_REQUEST['aure82pasos']) == 0) {
		$_REQUEST['aure82pasos'] = '';
	}
	if (isset($_REQUEST['aure82asignaperfil']) == 0) {
		$_REQUEST['aure82asignaperfil'] = '';
	}
	if (isset($_REQUEST['aure82manuales']) == 0) {
		$_REQUEST['aure82manuales'] = '';
	}
	if (isset($_REQUEST['aure82capacitacion']) == 0) {
		$_REQUEST['aure82capacitacion'] = '';
	}
	if (isset($_REQUEST['aure82evaluacion']) == 0) {
		$_REQUEST['aure82evaluacion'] = '';
	}
	if (isset($_REQUEST['aure82resultadoesp']) == 0) {
		$_REQUEST['aure82resultadoesp'] = '';
	}
	if (isset($_REQUEST['aure82idtester']) == 0) {
		$_REQUEST['aure82idtester'] = 0;
		//$_REQUEST['aure82idtester'] =  $idTercero;
	}
	if (isset($_REQUEST['aure82idtester_td']) == 0) {
		$_REQUEST['aure82idtester_td'] = $APP->tipo_doc;
	}
	if (isset($_REQUEST['aure82idtester_doc']) == 0) {
		$_REQUEST['aure82idtester_doc'] = '';
	}
	$_REQUEST['aure82idbitacora'] = numeros_validar($_REQUEST['aure82idbitacora']);
	$_REQUEST['aure82consec'] = numeros_validar($_REQUEST['aure82consec']);
	$_REQUEST['aure82id'] = numeros_validar($_REQUEST['aure82id']);
	$_REQUEST['aure82condiciones'] = cadena_Validar($_REQUEST['aure82condiciones']);
	$_REQUEST['aure82pasos'] = cadena_Validar($_REQUEST['aure82pasos']);
	$_REQUEST['aure82asignaperfil'] = cadena_Validar($_REQUEST['aure82asignaperfil']);
	$_REQUEST['aure82manuales'] = cadena_Validar($_REQUEST['aure82manuales']);
	$_REQUEST['aure82capacitacion'] = cadena_Validar($_REQUEST['aure82capacitacion']);
	$_REQUEST['aure82evaluacion'] = cadena_Validar($_REQUEST['aure82evaluacion']);
	$_REQUEST['aure82resultadoesp'] = cadena_Validar($_REQUEST['aure82resultadoesp']);
	$_REQUEST['aure82idtester'] = numeros_validar($_REQUEST['aure82idtester']);
	$_REQUEST['aure82idtester_td'] = cadena_Validar($_REQUEST['aure82idtester_td']);
	$_REQUEST['aure82idtester_doc'] = cadena_Validar($_REQUEST['aure82idtester_doc']);
	//Tarjetas CRC
	if (isset($_REQUEST['aure83idbitacora']) == 0) {
		$_REQUEST['aure83idbitacora'] = '';
	}
	if (isset($_REQUEST['aure83consec']) == 0) {
		$_REQUEST['aure83consec'] = '';
	}
	if (isset($_REQUEST['aure83id']) == 0) {
		$_REQUEST['aure83id'] = '';
	}
	if (isset($_REQUEST['aure83idbithistoria']) == 0) {
		$_REQUEST['aure83idbithistoria'] = '';
	}
	if (isset($_REQUEST['aure83idtarea']) == 0) {
		$_REQUEST['aure83idtarea'] = '';
	}
	if (isset($_REQUEST['aure83vigente']) == 0) {
		$_REQUEST['aure83vigente'] = 'S';
	}
	if (isset($_REQUEST['aure83nombreclase']) == 0) {
		$_REQUEST['aure83nombreclase'] = '';
	}
	if (isset($_REQUEST['aure83responsabilidades']) == 0) {
		$_REQUEST['aure83responsabilidades'] = '';
	}
	if (isset($_REQUEST['aure83colaboradores']) == 0) {
		$_REQUEST['aure83colaboradores'] = '';
	}
	if (isset($_REQUEST['aure83idtabla']) == 0) {
		$_REQUEST['aure83idtabla'] = '';
	}
	$_REQUEST['aure83idbitacora'] = numeros_validar($_REQUEST['aure83idbitacora']);
	$_REQUEST['aure83consec'] = numeros_validar($_REQUEST['aure83consec']);
	$_REQUEST['aure83id'] = numeros_validar($_REQUEST['aure83id']);
	$_REQUEST['aure83idbithistoria'] = numeros_validar($_REQUEST['aure83idbithistoria']);
	$_REQUEST['aure83idtarea'] = numeros_validar($_REQUEST['aure83idtarea']);
	$_REQUEST['aure83vigente'] = cadena_Validar($_REQUEST['aure83vigente']);
	$_REQUEST['aure83nombreclase'] = cadena_Validar($_REQUEST['aure83nombreclase']);
	$_REQUEST['aure83responsabilidades'] = cadena_Validar($_REQUEST['aure83responsabilidades']);
	$_REQUEST['aure83colaboradores'] = cadena_Validar($_REQUEST['aure83colaboradores']);
	$_REQUEST['aure83idtabla'] = numeros_validar($_REQUEST['aure83idtabla']);
}
// Espacio para inicializar otras variables
if (isset($_REQUEST['csv_separa']) == 0) {
	$_REQUEST['csv_separa'] = ';';
}
if (isset($_REQUEST['bnombre']) == 0) {
	$_REQUEST['bnombre'] = '';
}
/*
if (isset($_REQUEST['blistar']) == 0) {
	$_REQUEST['blistar'] = '';
}
if ((int)$_REQUEST['paso'] > 0) {
	//Participantes
	if (isset($_REQUEST['bnombre252']) == 0) {
		$_REQUEST['bnombre252'] = '';
	}
	if (isset($_REQUEST['blistar252']) == 0) {
		$_REQUEST['blistar252'] = '';
	}
	//Anexos
	if (isset($_REQUEST['bnombre253']) == 0) {
		$_REQUEST['bnombre253'] = '';
	}
	if (isset($_REQUEST['blistar253']) == 0) {
		$_REQUEST['blistar253'] = '';
	}
	//Riesgos
	if (isset($_REQUEST['bnombre257']) == 0) {
		$_REQUEST['bnombre257'] = '';
	}
	if (isset($_REQUEST['blistar257']) == 0) {
		$_REQUEST['blistar257'] = '';
	}
	//Historia de usuario
	if (isset($_REQUEST['bnombre280']) == 0) {
		$_REQUEST['bnombre280'] = '';
	}
	if (isset($_REQUEST['blistar280']) == 0) {
		$_REQUEST['blistar280'] = '';
	}
	//Tareas de ingenieria
	if (isset($_REQUEST['bnombre281']) == 0) {
		$_REQUEST['bnombre281'] = '';
	}
	if (isset($_REQUEST['blistar281']) == 0) {
		$_REQUEST['blistar281'] = '';
	}
	//Pruebas de aceptacion
	if (isset($_REQUEST['bnombre282']) == 0) {
		$_REQUEST['bnombre282'] = '';
	}
	if (isset($_REQUEST['blistar282']) == 0) {
		$_REQUEST['blistar282'] = '';
	}
	//Tarjetas CRC
	if (isset($_REQUEST['bnombre283']) == 0) {
		$_REQUEST['bnombre283'] = '';
	}
	if (isset($_REQUEST['blistar283']) == 0) {
		$_REQUEST['blistar283'] = '';
	}
}
*/
//Si Modifica o Elimina Cargar los campos
if (($_REQUEST['paso'] == 1) || ($_REQUEST['paso'] == 3)) {
	$_REQUEST['aure51idresponsable_td'] = $APP->tipo_doc;
	$_REQUEST['aure51idresponsable_doc'] = '';
	if ($_REQUEST['paso'] == 1) {
		$sSQLcondi = 'aure51idproyecto=' . $_REQUEST['aure51idproyecto'] . ' AND aure51consec=' . $_REQUEST['aure51consec'] . ' AND aure51idpadre=' . $_REQUEST['aure51idpadre'] . ' AND aure51orden="' . $_REQUEST['aure51orden'] . '"';
	} else {
		$sSQLcondi = 'aure51id=' . $_REQUEST['aure51id'] . '';
	}
	$sSQL = 'SELECT * FROM aure51bitacora WHERE ' . $sSQLcondi;
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$_REQUEST['aure51idproyecto'] = $fila['aure51idproyecto'];
		$_REQUEST['aure51consec'] = $fila['aure51consec'];
		$_REQUEST['aure51idpadre'] = $fila['aure51idpadre'];
		$_REQUEST['aure51orden'] = $fila['aure51orden'];
		$_REQUEST['aure51id'] = $fila['aure51id'];
		$_REQUEST['aure51estado'] = $fila['aure51estado'];
		$_REQUEST['aure51fecha'] = $fila['aure51fecha'];
		$_REQUEST['aure51horaini'] = $fila['aure51horaini'];
		$_REQUEST['aure51minini'] = $fila['aure51minini'];
		$_REQUEST['aure51horafin'] = $fila['aure51horafin'];
		$_REQUEST['aure51minfin'] = $fila['aure51minfin'];
		$_REQUEST['aure51idsistema'] = $fila['aure51idsistema'];
		$_REQUEST['aure51actividad'] = $fila['aure51actividad'];
		$_REQUEST['aure51lugar'] = $fila['aure51lugar'];
		$_REQUEST['aure51detalleactiv'] = $fila['aure51detalleactiv'];
		$_REQUEST['aure51objetivo'] = $fila['aure51objetivo'];
		$_REQUEST['aure51resultado'] = $fila['aure51resultado'];
		$_REQUEST['aure51idresponsable'] = $fila['aure51idresponsable'];
		$_REQUEST['aure51tiporesultado'] = $fila['aure51tiporesultado'];
		$bcargo = true;
		$_REQUEST['paso'] = 2;
		$_REQUEST['boculta251'] = 0;
		$bLimpiaHijos = true;
	} else {
		$_REQUEST['paso'] = 0;
	}
}
//Cerrar
$bCerrando = false;
if ($_REQUEST['paso'] == 16) {
	$_REQUEST['paso'] = 12;
	$_REQUEST['aure51estado'] = 7;
	$bCerrando = true;
}
//Abrir
if ($_REQUEST['paso'] == 17) {
	$_REQUEST['paso'] = 2;
	//Es posible que deba definir el codigo de permiso para abrir.
	if ($sError == '') {
		list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 17, $idTercero, $objDB);
		if (!$bDevuelve) {
			$sError = $ERR['3'];
		}
	}
	//Otras restricciones para abrir.
	if ($sError == '') {
		//$sError = 'Motivo por el que no se pueda abrir, no se permite modificar.';
	}
	if ($sError != '') {
		//$_REQUEST['aure51estado'] = 7;
	} else {
		$sSQL = 'UPDATE aure51bitacora SET aure51estado = 0 WHERE aure51id=' . $_REQUEST['aure51id'] . '';
		$tabla = $objDB->ejecutasql($sSQL);
		seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 3, $_REQUEST['aure51id'], 'Abre Bitacora de desarrollo', $objDB);
		$_REQUEST['aure51estado'] = 0;
		$sError = '<b>' . $ETI['msg_itemabierto'] . '</b>';
		$iTipoError = 1;
	}
}
//Insertar o modificar un elemento
if (($_REQUEST['paso'] == 10) || ($_REQUEST['paso'] == 12)) {
	$bMueveScroll = true;
	list($_REQUEST, $sError, $iTipoError, $bCerrando, $sErrorCerrando, $sDebugGuardar) = f251_db_GuardarV2($_REQUEST, $objDB, $bDebug, $idTercero);
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
	}
}
if ($bCerrando) {
	//acciones del cerrado
}
// Cambio de consecutivo.
if ($_REQUEST['paso'] == 93) {
	$_REQUEST['paso'] = 2;
	$_REQUEST['aure51consec_nuevo'] = numeros_validar($_REQUEST['aure51consec_nuevo']);
	if ($_REQUEST['aure51consec_nuevo'] == '') {
		$sError = $ERR['aure51consec'];
	}
	if ($sError == '') {
		list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 8, $idTercero, $objDB);
		if (!$bDevuelve) {
			$sError = $ERR['8'];
		}
	}
	if ($sError == '') {
		//Ver que el consecutivo no exista.
		$sSQL = 'SELECT aure51id FROM aure51bitacora WHERE aure51orden=' . $_REQUEST['aure51orden'] . ' AND aure51idpadre=' . $_REQUEST['aure51idpadre'] . ' AND aure51consec=' . $_REQUEST['aure51consec_nuevo'] . ' AND aure51idproyecto=' . $_REQUEST['aure51idproyecto'] . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$sError = 'El consecutivo ' . $_REQUEST['aure51consec_nuevo'] . ' ya existe';
		}
	}
	if ($sError == '') {
		//Aplicar el cambio.
		$sSQL = 'UPDATE aure51bitacora SET aure51consec=' . $_REQUEST['aure51consec_nuevo'] . ' WHERE aure51id=' . $_REQUEST['aure51id'] . '';
		$tabla = $objDB->ejecutasql($sSQL);
		$sDetalle = 'Cambia el consecutivo de ' . $_REQUEST['aure51consec'] . ' a ' . $_REQUEST['aure51consec_nuevo'] . '';
		$_REQUEST['aure51consec'] = $_REQUEST['aure51consec_nuevo'];
		$_REQUEST['aure51consec_nuevo'] = '';
		seg_auditar($iCodModulo, $_SESSION['u_idtercero'], 8, $_REQUEST['aure51id'], $sDetalle, $objDB);
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
		list($sError, $iTipoError, $sDebugElimina) = f251_db_Eliminar($_REQUEST['aure51id'], $objDB, $bDebug);
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
	$_REQUEST['aure51idproyecto'] = '';
	$_REQUEST['aure51consec'] = '';
	$_REQUEST['aure51consec_nuevo'] = '';
	$_REQUEST['aure51idpadre'] = '';
	$_REQUEST['aure51orden'] = '';
	$_REQUEST['aure51id'] = '';
	$_REQUEST['aure51estado'] = 0;
	$_REQUEST['aure51fecha'] = $iHoy;
	$_REQUEST['aure51horaini'] = fecha_hora();
	$_REQUEST['aure51minini'] = fecha_minuto();
	$_REQUEST['aure51horafin'] = fecha_hora();
	$_REQUEST['aure51minfin'] = fecha_minuto();
	$_REQUEST['aure51idsistema'] = 0;
	$_REQUEST['aure51actividad'] = '';
	$_REQUEST['aure51lugar'] = '';
	$_REQUEST['aure51detalleactiv'] = '';
	$_REQUEST['aure51objetivo'] = '';
	$_REQUEST['aure51resultado'] = '';
	$_REQUEST['aure51idresponsable'] = 0; //$idTercero;
	$_REQUEST['aure51idresponsable_td'] = $APP->tipo_doc;
	$_REQUEST['aure51idresponsable_doc'] = '';
	$_REQUEST['aure51tiporesultado'] = '';
	$_REQUEST['paso'] = 0;
}
if ($bLimpiaHijos) {
	$_REQUEST['aure52idbitacora'] = '';
	$_REQUEST['aure52idtercero'] = 0; //$idTercero;
	$_REQUEST['aure52idtercero_td'] = $APP->tipo_doc;
	$_REQUEST['aure52idtercero_doc'] = '';
	$_REQUEST['aure52id'] = '';
	$_REQUEST['aure52activo'] = 1;
	$_REQUEST['aure58idbitacora'] = '';
	$_REQUEST['aure58consec'] = '';
	$_REQUEST['aure58id'] = '';
	$_REQUEST['aure58titulo'] = '';
	$_REQUEST['aure58idorigen'] = 0;
	$_REQUEST['aure58idarchivo'] = 0;
	$_REQUEST['aure58idusuario'] = $idTercero;
	$_REQUEST['aure58idusuario_td'] = $APP->tipo_doc;
	$_REQUEST['aure58idusuario_doc'] = '';
	$_REQUEST['aure58fecha'] = '';
	//$_REQUEST['aure58fecha'] = $iHoy;
	$_REQUEST['aure57idbitacora'] = '';
	$_REQUEST['aure57idriesgo'] = '';
	$_REQUEST['aure57id'] = '';
	$_REQUEST['aure57nivelriesgo'] = 0;
	$_REQUEST['aure80idbitacora'] = '';
	$_REQUEST['aure80consec'] = '';
	$_REQUEST['aure80id'] = '';
	$_REQUEST['aure80momento'] = '';
	$_REQUEST['aure80infousuario'] = '';
	$_REQUEST['aure80prioridad'] = 0;
	$_REQUEST['aure80semanaest'] = '';
	$_REQUEST['aure80diasest'] = '';
	$_REQUEST['aure80iteracionasig'] = '';
	$_REQUEST['aure80infotecnica'] = '';
	$_REQUEST['aure80observaciones'] = '';
	$_REQUEST['aure81idbitacora'] = '';
	$_REQUEST['aure81consec'] = '';
	$_REQUEST['aure81id'] = '';
	$_REQUEST['aure81idbithistoria'] = '';
	$_REQUEST['aure81idtipotarea'] = '';
	$_REQUEST['aure81semanasest'] = '';
	$_REQUEST['aure81diasest'] = '';
	$_REQUEST['aure81fechainicio'] = $iHoy;
	$_REQUEST['aure81avance'] = 0;
	$_REQUEST['aure81fechafinal'] = $iHoy;
	$_REQUEST['aure81descripcion'] = '';
	$_REQUEST['aure82idbitacora'] = '';
	$_REQUEST['aure82consec'] = '';
	$_REQUEST['aure82id'] = '';
	$_REQUEST['aure82condiciones'] = '';
	$_REQUEST['aure82pasos'] = '';
	$_REQUEST['aure82asignaperfil'] = '';
	$_REQUEST['aure82manuales'] = '';
	$_REQUEST['aure82capacitacion'] = '';
	$_REQUEST['aure82evaluacion'] = '';
	$_REQUEST['aure82resultadoesp'] = '';
	$_REQUEST['aure82idtester'] = 0; //$idTercero;
	$_REQUEST['aure82idtester_td'] = $APP->tipo_doc;
	$_REQUEST['aure82idtester_doc'] = '';
	$_REQUEST['aure83idbitacora'] = '';
	$_REQUEST['aure83consec'] = '';
	$_REQUEST['aure83id'] = '';
	$_REQUEST['aure83idbithistoria'] = '';
	$_REQUEST['aure83idtarea'] = '';
	$_REQUEST['aure83vigente'] = 'S';
	$_REQUEST['aure83nombreclase'] = '';
	$_REQUEST['aure83responsabilidades'] = '';
	$_REQUEST['aure83colaboradores'] = '';
	$_REQUEST['aure83idtabla'] = 0;
}
//AQUI SE DEBEN CARGAR TODOS LOS DATOS QUE LA FORMA NECESITE.
//DATOS PARA COMPLETAR EL FORMULARIO
$iAgnoIni = 2000;
$iAgno = fecha_agno();
$iAgnoFin = $iAgno + 5;
$sNombreUsuario = '';
if ($seg_1707 == 1) {
	$sSQL = 'SELECT unad11razonsocial FROM unad11terceros WHERE unad11id=' . $idTercero . '';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$sNombreUsuario = cadena_notildes($fila['unad11razonsocial']);
	}
}
//Permisos adicionales
$seg_5 = 0;
$seg_6 = 0;
$seg_8 = 0;
/*
list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 6, $idTercero, $objDB);
if ($bDevuelve) {
	$seg_6 = 1;
}
*/
//Crear los controles que requieran llamado a base de datos
$objCombos = new clsHtmlCombos();
$objTercero = new clsHtmlTercero();
$aure51idsistema_nombre = '&nbsp;';
if ((int)$_REQUEST['aure51idsistema'] != 0) {
	list($aure51idsistema_nombre, $sErrorDet) = tabla_campoxid('unad01sistema', 'unad01nombre', 'unad01id', $_REQUEST['aure51idsistema'], '{' . $ETI['msg_sindato'] . '}', $objDB);
}
$html_aure51idsistema = html_oculto('aure51idsistema', $_REQUEST['aure51idsistema'], $aure51idsistema_nombre);
list($aure51idresponsable_rs, $_REQUEST['aure51idresponsable'], $_REQUEST['aure51idresponsable_td'], $_REQUEST['aure51idresponsable_doc']) = html_tercero($_REQUEST['aure51idresponsable_td'], $_REQUEST['aure51idresponsable_doc'], $_REQUEST['aure51idresponsable'], 0, $objDB);
$objCombos->nuevo('aure51tiporesultado', $_REQUEST['aure51tiporesultado'], true, '{' . $ETI['msg_seleccione'] . '}');
$sSQL = 'SELECT aure59id AS id, aure59nombre AS nombre FROM aure59tiporesult ORDER BY aure59nombre';
$html_aure51tiporesultado = $objCombos->html($sSQL, $objDB);
if ((int)$_REQUEST['paso'] == 0) {
	$html_aure51idproyecto = f251_HTMLComboV2_aure51idproyecto($objDB, $objCombos, $_REQUEST['aure51idproyecto']);
} else {
	$aure51idproyecto_nombre = '&nbsp;';
	if ((int)$_REQUEST['aure51idproyecto'] != 0) {
		list($aure51idproyecto_nombre, $sErrorDet) = tabla_campoxid('bita09proyecto', 'bita09titulo', 'bita09id', $_REQUEST['aure51idproyecto'], '{' . $ETI['msg_sindato'] . '}', $objDB);
	}
	$html_aure51idproyecto = html_oculto('aure51idproyecto', $_REQUEST['aure51idproyecto'], $aure51idproyecto_nombre);
	list($aure52idtercero_rs, $_REQUEST['aure52idtercero'], $_REQUEST['aure52idtercero_td'], $_REQUEST['aure52idtercero_doc']) = html_tercero($_REQUEST['aure52idtercero_td'], $_REQUEST['aure52idtercero_doc'], $_REQUEST['aure52idtercero'], 0, $objDB);
	$objCombos->nuevo('aure52activo', $_REQUEST['aure52activo'], true, $ETI['no'], 0);
	$objCombos->addItem(1, $ETI['si']);
	//$objCombos->addArreglo($aaure52activo, $iaure52activo);
	$html_aure52activo = $objCombos->html('', $objDB);
	list($aure58idusuario_rs, $_REQUEST['aure58idusuario'], $_REQUEST['aure58idusuario_td'], $_REQUEST['aure58idusuario_doc']) = html_tercero($_REQUEST['aure58idusuario_td'], $_REQUEST['aure58idusuario_doc'], $_REQUEST['aure58idusuario'], 0, $objDB);
	$objCombos->nuevo('aure57nivelriesgo', $_REQUEST['aure57nivelriesgo'], true, $ETI['no'], 0);
	$objCombos->addItem(1, $ETI['si']);
	//$objCombos->addArreglo($aaure57nivelriesgo, $iaure57nivelriesgo);
	$html_aure57nivelriesgo = $objCombos->html('', $objDB);
	$objCombos->nuevo('aure80momento', $_REQUEST['aure80momento'], true, '{' . $ETI['msg_seleccione'] . '}');
	$sSQL = 'SELECT aure53id AS id, aure53nombre AS nombre FROM aure53hmomento ORDER BY aure53nombre';
	$html_aure80momento = $objCombos->html($sSQL, $objDB);
	$objCombos->nuevo('aure80prioridad', $_REQUEST['aure80prioridad'], true, $ETI['no'], 0);
	$objCombos->addItem(1, $ETI['si']);
	//$objCombos->addArreglo($aaure80prioridad, $iaure80prioridad);
	$html_aure80prioridad = $objCombos->html('', $objDB);
	$objCombos->nuevo('aure81idbithistoria', $_REQUEST['aure81idbithistoria'], true, '{' . $ETI['msg_seleccione'] . '}');
	$objCombos->sAccion = 'carga_combo_aure81idtipotarea();';
	$sSQL = 'SELECT aure80id AS id, aure80consec AS nombre FROM aure80historiaus ORDER BY aure80consec';
	$html_aure81idbithistoria = $objCombos->html($sSQL, $objDB);
	$html_aure81idtipotarea = f281_HTMLComboV2_aure81idtipotarea($objDB, $objCombos, $_REQUEST['aure81idtipotarea'], $_REQUEST['aure81idbithistoria']);
	$aure81avance_nombre = $_REQUEST['aure81avance'];
	//$aure81avance_nombre = '&nbsp;';
	//if ((int)$_REQUEST['aure81avance'] != 0) {
		//list($aure81avance_nombre, $sErrorDet) = tabla_campoxid('', '', '', $_REQUEST['aure81avance'], '{' . $ETI['msg_sindato'] . '}', $objDB);
	//}
	$html_aure81avance = html_oculto('aure81avance', $_REQUEST['aure81avance'], $aure81avance_nombre);
	$objCombos->nuevo('aure82asignaperfil', $_REQUEST['aure82asignaperfil'], false);
	$objCombos->sino($ETI['si'], $ETI['no']); //, $sValorSi='S', $sValorNo='N'
	$html_aure82asignaperfil = $objCombos->html('', $objDB);
	$objCombos->nuevo('aure82manuales', $_REQUEST['aure82manuales'], false);
	$objCombos->sino($ETI['si'], $ETI['no']); //, $sValorSi='S', $sValorNo='N'
	$html_aure82manuales = $objCombos->html('', $objDB);
	$objCombos->nuevo('aure82capacitacion', $_REQUEST['aure82capacitacion'], false);
	$objCombos->sino($ETI['si'], $ETI['no']); //, $sValorSi='S', $sValorNo='N'
	$html_aure82capacitacion = $objCombos->html('', $objDB);
	$objCombos->nuevo('aure82evaluacion', $_REQUEST['aure82evaluacion'], false);
	$objCombos->sino($ETI['si'], $ETI['no']); //, $sValorSi='S', $sValorNo='N'
	$html_aure82evaluacion = $objCombos->html('', $objDB);
	list($aure82idtester_rs, $_REQUEST['aure82idtester'], $_REQUEST['aure82idtester_td'], $_REQUEST['aure82idtester_doc']) = html_tercero($_REQUEST['aure82idtester_td'], $_REQUEST['aure82idtester_doc'], $_REQUEST['aure82idtester'], 0, $objDB);
	$objCombos->nuevo('aure83idbithistoria', $_REQUEST['aure83idbithistoria'], true, '{' . $ETI['msg_seleccione'] . '}');
	$sSQL = 'SELECT aure80id AS id, aure80consec AS nombre FROM aure80historiaus ORDER BY aure80consec';
	$html_aure83idbithistoria = $objCombos->html($sSQL, $objDB);
	$objCombos->nuevo('aure83idtarea', $_REQUEST['aure83idtarea'], true, '{' . $ETI['msg_seleccione'] . '}');
	$sSQL = 'SELECT aure81id AS id, aure81consec AS nombre FROM aure81tareaing ORDER BY aure81consec';
	$html_aure83idtarea = $objCombos->html($sSQL, $objDB);
	$objCombos->nuevo('aure83vigente', $_REQUEST['aure83vigente'], false);
	$objCombos->sino($ETI['si'], $ETI['no']); //, $sValorSi='S', $sValorNo='N'
	$html_aure83vigente = $objCombos->html('', $objDB);
	$objCombos->nuevo('aure83idtabla', $_REQUEST['aure83idtabla'], true, $ETI['no'], 0);
	$objCombos->addItem(1, $ETI['si']);
	//$objCombos->addArreglo($aaure83idtabla, $iaure83idtabla);
	$html_aure83idtabla = $objCombos->html('', $objDB);
}
//Alistar datos adicionales
$bPuedeAbrir = false;
if ($_REQUEST['paso'] != 0) {
	if ($_REQUEST['aure51estado'] == 7) {
		//Definir las condiciones que permitirán abrir el registro.
		list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 17, $idTercero, $objDB);
		if ($bDevuelve) {
			$bPuedeAbrir = true;
		}
	}
}
$id_rpt = 0;
//$id_rpt=reportes_id(_Identificador_Tipo_Reporte_, $objDB);
/*
$objCombos->nuevo('blistar', $_REQUEST['blistar'], true, '{' . $ETI['msg_todos'] . '}');
$objCombos->sAccion = 'paginarf251()';
$sSQL = '';
$html_blistar = $objCombos->html($sSQL, $objDB);
//$html_blistar = $objCombos->comboSistema(251, 1, $objDB, 'paginarf251()');
$objCombos->nuevo('blistar252', $_REQUEST['blistar252'], true, '{' . $ETI['msg_todos'] . '}');
$html_blistar252 = $objCombos->comboSistema(252, 1, $objDB, 'paginarf252()');
$objCombos->nuevo('blistar253', $_REQUEST['blistar253'], true, '{' . $ETI['msg_todos'] . '}');
$html_blistar253 = $objCombos->comboSistema(253, 1, $objDB, 'paginarf253()');
$objCombos->nuevo('blistar257', $_REQUEST['blistar257'], true, '{' . $ETI['msg_todos'] . '}');
$html_blistar257 = $objCombos->comboSistema(257, 1, $objDB, 'paginarf257()');
$objCombos->nuevo('blistar280', $_REQUEST['blistar280'], true, '{' . $ETI['msg_todos'] . '}');
$html_blistar280 = $objCombos->comboSistema(280, 1, $objDB, 'paginarf280()');
$objCombos->nuevo('blistar281', $_REQUEST['blistar281'], true, '{' . $ETI['msg_todos'] . '}');
$html_blistar281 = $objCombos->comboSistema(281, 1, $objDB, 'paginarf281()');
$objCombos->nuevo('blistar282', $_REQUEST['blistar282'], true, '{' . $ETI['msg_todos'] . '}');
$html_blistar282 = $objCombos->comboSistema(282, 1, $objDB, 'paginarf282()');
$objCombos->nuevo('blistar283', $_REQUEST['blistar283'], true, '{' . $ETI['msg_todos'] . '}');
$html_blistar283 = $objCombos->comboSistema(283, 1, $objDB, 'paginarf283()');
*/
if (false) {
	$objCombos->nuevo('csv_separa', $_REQUEST['csv_separa'], false);
	$objCombos->addItem(',', $ETI['msg_coma']);
	$objCombos->addItem(';', $ETI['msg_puntoycoma']);
	$csv_separa = '<label class="Label90">' . $ETI['msg_separador'] . '</label><label class="Label130">' . $objCombos->html('', $objDB) . '</label>';
} else {
	$csv_separa = '<input id="csv_separa" name="csv_separa" type="hidden" value="," />';
}
$iNumFormatosImprime = 0;
$iModeloReporte = 251;
$html_iFormatoImprime = '<input id="iformatoimprime" name="iformatoimprime" type="hidden" value="0" />
';
if ($_REQUEST['paso'] > 0) {
	$bDevuelve = false;
	//list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 5, $idTercero, $objDB);
	if ($bDevuelve) {
		$seg_5 = 1;
	}
	if ($_REQUEST['aure51estado'] == 0) {
		$bDevuelve = false;
		//list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 8, $idTercero, $objDB);
		if ($bDevuelve) {
			$seg_8 = 1;
		}
	}
}
//Cargar las tablas de datos
$aParametros[0] = ''; //$_REQUEST['p1_251'];
$aParametros[100] = $idTercero;
$aParametros[101] = $_REQUEST['paginaf251'];
$aParametros[102] = $_REQUEST['lppf251'];
//$aParametros[103] = $_REQUEST['bnombre'];
//$aParametros[104] = $_REQUEST['blistar'];
list($sTabla251, $sDebugTabla) = f251_TablaDetalleV2($aParametros, $objDB, $bDebug);
$sDebug = $sDebug . $sDebugTabla;
$sTabla252 = '';
$sTabla253 = '';
$sTabla257 = '';
$sTabla280 = '';
$sTabla281 = '';
$sTabla282 = '';
$sTabla283 = '';
if ($_REQUEST['paso'] != 0) {
	//Participantes
	$aParametros252[0] = $_REQUEST['aure51id'];
	$aParametros252[100] = $idTercero;
	$aParametros252[101] = $_REQUEST['paginaf252'];
	$aParametros252[102] = $_REQUEST['lppf252'];
	//$aParametros252[103] = $_REQUEST['bnombre252'];
	//$aParametros252[104] = $_REQUEST['blistar252'];
	list($sTabla252, $sDebugTabla) = f252_TablaDetalleV2($aParametros252, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugTabla;
	//Anexos
	$aParametros253[0] = $_REQUEST['aure51id'];
	$aParametros253[100] = $idTercero;
	$aParametros253[101] = $_REQUEST['paginaf253'];
	$aParametros253[102] = $_REQUEST['lppf253'];
	//$aParametros253[103] = $_REQUEST['bnombre253'];
	//$aParametros253[104] = $_REQUEST['blistar253'];
	list($sTabla253, $sDebugTabla) = f253_TablaDetalleV2($aParametros253, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugTabla;
	//Riesgos
	$aParametros257[0] = $_REQUEST['aure51id'];
	$aParametros257[100] = $idTercero;
	$aParametros257[101] = $_REQUEST['paginaf257'];
	$aParametros257[102] = $_REQUEST['lppf257'];
	//$aParametros257[103] = $_REQUEST['bnombre257'];
	//$aParametros257[104] = $_REQUEST['blistar257'];
	list($sTabla257, $sDebugTabla) = f257_TablaDetalleV2($aParametros257, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugTabla;
	//Historia de usuario
	$aParametros280[0] = $_REQUEST['aure51id'];
	$aParametros280[100] = $idTercero;
	$aParametros280[101] = $_REQUEST['paginaf280'];
	$aParametros280[102] = $_REQUEST['lppf280'];
	//$aParametros280[103] = $_REQUEST['bnombre280'];
	//$aParametros280[104] = $_REQUEST['blistar280'];
	list($sTabla280, $sDebugTabla) = f280_TablaDetalleV2($aParametros280, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugTabla;
	//Tareas de ingenieria
	$aParametros281[0] = $_REQUEST['aure51id'];
	$aParametros281[100] = $idTercero;
	$aParametros281[101] = $_REQUEST['paginaf281'];
	$aParametros281[102] = $_REQUEST['lppf281'];
	//$aParametros281[103] = $_REQUEST['bnombre281'];
	//$aParametros281[104] = $_REQUEST['blistar281'];
	list($sTabla281, $sDebugTabla) = f281_TablaDetalleV2($aParametros281, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugTabla;
	//Pruebas de aceptacion
	$aParametros282[0] = $_REQUEST['aure51id'];
	$aParametros282[100] = $idTercero;
	$aParametros282[101] = $_REQUEST['paginaf282'];
	$aParametros282[102] = $_REQUEST['lppf282'];
	//$aParametros282[103] = $_REQUEST['bnombre282'];
	//$aParametros282[104] = $_REQUEST['blistar282'];
	list($sTabla282, $sDebugTabla) = f282_TablaDetalleV2($aParametros282, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugTabla;
	//Tarjetas CRC
	$aParametros283[0] = $_REQUEST['aure51id'];
	$aParametros283[100] = $idTercero;
	$aParametros283[101] = $_REQUEST['paginaf283'];
	$aParametros283[102] = $_REQUEST['lppf283'];
	//$aParametros283[103] = $_REQUEST['bnombre283'];
	//$aParametros283[104] = $_REQUEST['blistar283'];
	list($sTabla283, $sDebugTabla) = f283_TablaDetalleV2($aParametros283, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugTabla;
}
$bDebugMenu = false;
list($et_menu, $sDebugM) = html_menuV2($APP->idsistema, $objDB, $iPiel, $bDebugMenu, $idTercero);
$sDebug = $sDebug . $sDebugM;
$objDB->CerrarConexion();
//FORMA
require $APP->rutacomun . 'unad_forma_v2.php';
forma_cabeceraV3($xajax, $ETI['titulo_251']);
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
<script language="javascript">
	function limpiapagina() {
		expandesector(98);
		window.document.frmedita.paso.value = -1;
		window.document.frmedita.submit();
	}

	function enviaguardar() {
		window.document.frmedita.iscroll.value = window.pageYOffset;
		expandesector(98);
		let dpaso = window.document.frmedita.paso;
		if (dpaso.value == 0) {
			dpaso.value = 10;
		} else {
			dpaso.value = 12;
		}
		window.document.frmedita.submit();
	}

	function cambiapagina() {
		expandesector(98);
		window.document.frmedita.submit();
	}

	function cambiapaginaV2() {
		expandesector(98);
		window.document.frmedita.paso.value = 1;
		window.document.frmedita.submit();
	}

	function expandepanel(codigo, estado, valor) {
		let objdiv = document.getElementById('div_p' + codigo);
		let objban = document.getElementById('boculta' + codigo);
		let otroestado = 'none';
		if (estado == 'none') {
			otroestado = 'block';
		}
		objdiv.style.display = estado;
		objban.value = valor;
		verboton('btrecoge' + codigo, estado);
		verboton('btexpande' + codigo, otroestado);
	}

	function verboton(idboton, estado) {
		let objbt = document.getElementById(idboton);
		objbt.style.display = estado;
	}

	function expandesector(codigo) {
		document.getElementById('div_sector1').style.display = 'none';
		document.getElementById('div_sector2').style.display = 'none';
		document.getElementById('div_sector93').style.display = 'none';
		document.getElementById('div_sector95').style.display = 'none';
		document.getElementById('div_sector96').style.display = 'none';
		document.getElementById('div_sector97').style.display = 'none';
		document.getElementById('div_sector98').style.display = 'none';
		document.getElementById('div_sector' + codigo).style.display = 'block';
		if (window.document.frmedita.aure51estado.value == 0) {
			let sEst = 'none';
			if (codigo == 1) {
				sEst = 'block';
			}
			document.getElementById('cmdGuardarf').style.display = sEst;
		}
	}

	function ter_crea(idcampo, illave) {
		let dtd = document.getElementById(idcampo + '_td');
		let ddoc = document.getElementById(idcampo + '_doc');
		let sURL = 'frametercero.php?td=' + String(dtd.value) + '&doc=' + String(ddoc.value);
		window.document.frmedita.iscroll.value = window.pageYOffset;
		window.document.frmedita.div96v1.value = '';
		window.document.frmedita.div96v2.value = '';
		window.document.frmedita.div96v3.value = '';
		window.document.frmedita.div96campo.value = idcampo;
		window.document.frmedita.div96llave.value = illave;
		document.getElementById('div_95cuerpo').innerHTML = '<iframe id="iframe95" src="' + sURL + '" height="400px" width="100%" frameborder="0"></iframe>';
		expandesector(95);
		window.scrollTo(0, 150);
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
			window.document.frmlista.consulta.value = window.document.frmedita.consulta_251.value;
			window.document.frmlista.titulos.value = window.document.frmedita.titulos_251.value;
			window.document.frmlista.nombrearchivo.value = 'Bitacora de desarrollo';
			window.document.frmlista.submit();
		} else {
			ModalMensaje("<?php echo $ERR['6']; ?>");
		}
	}

	function asignarvariables() {
		//window.document.frmimpp.v3.value = window.document.frmedita.bnombre.value;
		//window.document.frmimpp.v4.value = window.document.frmedita.bcodigo.value;
		//window.document.frmimpp.separa.value = window.document.frmedita.csv_separa.value.trim();
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
			window.document.frmimpp.action = 'e251_ss.php';
			window.document.frmimpp.submit();
		} else {
			ModalMensaje(sError);
		}
	}

	function imprimep() {
		if (window.document.frmedita.seg_5.value == 1) {
			asignarvariables();
			window.document.frmimpp.action = 'p251.php';
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

	function verrpt() {
		window.document.frmimprime.submit();
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
		datos[1] = window.document.frmedita.aure51idproyecto.value;
		datos[2] = window.document.frmedita.aure51consec.value;
		datos[3] = window.document.frmedita.aure51idpadre.value;
		datos[4] = window.document.frmedita.aure51orden.value;
		if ((datos[1] != '') && (datos[2] != '') && (datos[3] != '') && (datos[4] != '')) {
			xajax_f251_ExisteDato(datos);
		}
	}

	function cargadato(llave1, llave2, llave3, llave4) {
		window.document.frmedita.aure51idproyecto.value = String(llave1);
		window.document.frmedita.aure51consec.value = String(llave2);
		window.document.frmedita.aure51idpadre.value = String(llave3);
		window.document.frmedita.aure51orden.value = String(llave4);
		window.document.frmedita.paso.value = 1;
		window.document.frmedita.submit();
	}

	function cargaridf251(llave1) {
		window.document.frmedita.aure51id.value = String(llave1);
		window.document.frmedita.paso.value = 3;
		window.document.frmedita.submit();
	}

	function paginarf251() {
		let params = new Array();
		params[99] = window.document.frmedita.debug.value;
		params[100] = <?php echo $idTercero; ?>;
		params[101] = window.document.frmedita.paginaf251.value;
		params[102] = window.document.frmedita.lppf251.value;
		//params[103] = window.document.frmedita.bnombre.value;
		//params[104] = window.document.frmedita.blistar.value;
		//document.getElementById('div_f251detalle').innerHTML = '<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf251" name="paginaf251" type="hidden" value="' + params[101] + '" /><input id="lppf251" name="lppf251" type="hidden" value="' + params[102] + '" />';
		xajax_f251_HtmlTabla(params);
	}

	function enviacerrar() {
		ModalConfirmV2('<?php echo $ETI['msg_cierre251']; ?>', () => {
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
		document.getElementById("aure51idproyecto").focus();
	}

	function buscarV2016(sCampo) {
		window.document.frmedita.iscroll.value = window.pageYOffset;
		expandesector(98);
		window.document.frmedita.scampobusca.value = sCampo;
		let params = new Array();
		params[1] = sCampo;
		//params[2] = window.document.frmedita.iagno.value;
		//params[3] = window.document.frmedita.itipo.value;
		xajax_f251_Busquedas(params);
	}

	function retornacontrol() {
		expandesector(1);
		window.scrollTo(0, window.document.frmedita.iscroll.value);
	}

	function Devuelve(sValor) {
		let sCampo = window.document.frmedita.scampobusca.value;
		if (sCampo == 'aure51idresponsable') {
			ter_traerxid('aure51idresponsable', sValor);
		}
		if (sCampo == 'aure52idtercero') {
			ter_traerxid('aure52idtercero', sValor);
		}
		if (sCampo == 'aure58idusuario') {
			ter_traerxid('aure58idusuario', sValor);
		}
		if (sCampo == 'aure82idtester') {
			ter_traerxid('aure82idtester', sValor);
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
		if (ref == 253) {
			if (sRetorna != '') {
				window.document.frmedita.aure58idorigen.value = window.document.frmedita.div96v1.value;
				window.document.frmedita.aure58idarchivo.value = sRetorna;
				verboton('beliminaaure58idarchivo', 'block');
			}
			archivo_lnk(window.document.frmedita.aure58idorigen.value, window.document.frmedita.aure58idarchivo.value, 'div_aure58idarchivo');
			paginarf253();
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
<script language="javascript" src="jsi/js252.js"></script>
<script language="javascript" src="jsi/js253.js"></script>
<script language="javascript" src="jsi/js257.js"></script>
<script language="javascript" src="jsi/js280.js"></script>
<script language="javascript" src="jsi/js281.js"></script>
<script language="javascript" src="jsi/js282.js"></script>
<script language="javascript" src="jsi/js283.js"></script>
<form id="frmimpp" name="frmimpp" method="post" action="p251.php" target="_blank">
<input id="r" name="r" type="hidden" value="251" />
<input id="id251" name="id251" type="hidden" value="<?php echo $_REQUEST['aure51id']; ?>" />
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
<form id="frmlista" name="frmlista" method="post" action="listados.php" target="_blank">
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
<input id="seg_5" name="seg_5" type="hidden" value="<?php echo $seg_5; ?>" />
<input id="seg_6" name="seg_6" type="hidden" value="<?php echo $seg_6; ?>" />
<div id="div_sector1">
<div class="titulos">
<div class="titulosD">
<input id="cmdAyuda" name="cmdAyuda" type="button" class="btUpAyuda" onclick="muestraayuda(<?php echo $APP->idsistema . ', ' . $iCodModulo; ?>);" title="<?php echo $ETI['bt_ayuda']; ?>" value="<?php echo $ETI['bt_ayuda']; ?>" />
<?php
if ($_REQUEST['paso'] == 2) {
	if ($_REQUEST['aure51estado'] == 0) {
?>
<input id="cmdEliminar" name="cmdEliminar" type="button" class="btUpEliminar" onclick="eliminadato();" title="<?php echo $ETI['bt_eliminar']; ?>" value="<?php echo $ETI['bt_eliminar']; ?>" />
<?php
	}
}
$bHayImprimir = false;
$sScript = 'imprimelista()';
$sClaseBoton = 'btEnviarExcel';
if ($seg_6 == 1) {
	$bHayImprimir = true;
}
if ($_REQUEST['paso'] != 0) {
	if ($seg_5 == 1) {
		if ($_REQUEST['aure51estado'] == 7) {
			//$bHayImprimir = true;
			//$sScript = 'imprimep()';
			//if ($iNumFormatosImprime>0) {
				//$sScript = 'expandesector(94)';
				//}
			//$sClaseBoton = 'btEnviarPDF'; //btUpPrint
			//if ($id_rpt != 0) { $sScript = 'verrpt()'; }
		}
	}
}
if ($bHayImprimir) {
?>
<input id="cmdImprimir" name="cmdImprimir" type="button" class="<?php echo $sClaseBoton; ?>" onclick="<?php echo $sScript; ?>" title="<?php echo $ETI['bt_imprimir']; ?>" value="<?php echo $ETI['bt_imprimir']; ?>" />
<?php
}
?>
<input id="cmdLimpiar" name="cmdLimpiar" type="button" class="btUpLimpiar" onclick="limpiapagina();" title="<?php echo $ETI['bt_limpiar']; ?>" value="<?php echo $ETI['bt_limpiar']; ?>" />
<?php
if ($_REQUEST['aure51estado'] == 0) {
?>
<input id="cmdGuardar" name="cmdGuardar" type="button" class="btUpGuardar" onclick="enviaguardar();" title="<?php echo $ETI['bt_guardar']; ?>" value="<?php echo $ETI['bt_guardar']; ?>" />
<?php
	if ($_REQUEST['paso'] > 0) {
?>
<input id="cmdCerrar" name="cmdCerrar" type="button" class="btSupCerrar" onClick="enviacerrar();" title="Cerrar" value="Cerrar" />
<?php
	}
} else {
	if ($_REQUEST['paso'] > 0) {
		if ($bPuedeAbrir) {
?>
<input id="cmdAbrir" name="cmdAbrir" type="button" class="btSupAbrir" onclick="enviaabrir();" title="Abrir" value="Abrir" />
<?php
		}
	}
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
echo '<h2>' . $ETI['titulo_251'] . '</h2>';
?>
</div>
</div>
<div class="areaform">
<div class="areatrabajo">
<?php
if ($seg_1707 == 1) {
?>
<div class="GrupoCamposAyuda">
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
<input id="btRevisaDoc" name="btRevisaDoc" type="button" value="Actualizar" class="btMiniActualizar" onclick="cambiapagina()" title="Consultar documento" />
</label>
<label class="Label30"></label>
<b>
<?php
echo $sNombreUsuario;
?>
</b>
<div class="salto1px"></div>
</div>
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
$sEstiloExpande = ' style="display:none;"';
$sEstiloRecoje = '';
$sEstiloDiv = '';
if ($_REQUEST['boculta251'] != 0) {
	$sEstiloExpande = '';
	$sEstiloRecoje = ' style="display:none;"';
	$sEstiloDiv = ' style="display:none;"';
}
?>
<input id="boculta251" name="boculta251" type="hidden" value="<?php echo $_REQUEST['boculta251']; ?>" />
<label class="Label30">
<input id="btexpande251" name="btexpande251" type="button" value="Mostrar" class="btMiniExpandir" onclick="expandepanel(251, 'block', 0);" title="<?php echo $ETI['bt_mostrar']; ?>"<?php echo $sEstiloExpande; ?>/>
</label>
<label class="Label30">
<input id="btrecoge251" name="btrecoge251" type="button" value="Ocultar" class="btMiniRecoger" onclick="expandepanel(251, 'none', 1);" title="<?php echo $ETI['bt_ocultar']; ?>" <?php echo $sEstiloRecoje; ?>/>
</label>
</div>
<div id="div_p251"<?php echo $sEstiloDiv; ?>>
<?php
}
//Mostrar formulario para editar
?>
<label class="Label130">
<?php
echo $ETI['aure51idproyecto'];
?>
</label>
<label>
<?php
echo $html_aure51idproyecto;
?>
</label>
<label class="Label130"<?php echo $sOcultaConsec; ?>>
<?php
echo $ETI['aure51consec'];
?>
</label>
<label class="Label130"<?php echo $sOcultaConsec; ?>>
<?php
if ($_REQUEST['paso'] != 2) {
?>
<input id="aure51consec" name="aure51consec" type="text" value="<?php echo $_REQUEST['aure51consec']; ?>" onchange="RevisaLlave()" class="cuatro" />
<?php
} else {
	echo html_oculto('aure51consec', $_REQUEST['aure51consec'], formato_numero($_REQUEST['aure51consec']));
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
<input id="aure51idpadre" name="aure51idpadre" type="hidden" value="<?php echo $_REQUEST['aure51idpadre']; ?>" />
<input id="aure51orden" name="aure51orden" type="hidden" value="<?php echo $_REQUEST['aure51orden']; ?>" />
<label class="Label60"<?php echo $sOcultaId; ?>>
<?php
echo $ETI['aure51id'];
?>
</label>
<label class="Label60"<?php echo $sOcultaId; ?>>
<?php
	echo html_oculto('aure51id', $_REQUEST['aure51id'], formato_numero($_REQUEST['aure51id']));
?>
</label>
<label class="Label90">
<?php
$et_aure51estado = $ETI['msg_abierto'];
if ($_REQUEST['aure51estado'] == 7) {
	$et_aure51estado = $ETI['msg_cerrado'];
}
echo html_oculto('aure51estado', $_REQUEST['aure51estado'], $et_aure51estado);
?>
</label>
<label class="Label130">
<?php
echo $ETI['aure51fecha'];
?>
</label>
<div class="Campo220">
<?php
echo html_FechaEnNumero('aure51fecha', $_REQUEST['aure51fecha']); //, false, '', $iAgnoIni, $iAgnoFin); //$bvacio, $accion
?>
</div>
<?php
if (false) {
?>
<label class="Label30">
<input id="baure51fecha_hoy" name="baure51fecha_hoy" type="button" value="Hoy" class="btMiniHoy" onclick="fecha_AsignarNum('aure51fecha', '<?php echo $iHoy; ?>')" title="<?php echo $ETI['bt_hoy']; ?>" />
</label>
<?php
}
?>
<label class="Label130">
<?php
echo $ETI['aure51horaini'];
?>
</label>
<div class="campo_HoraMin" id="div_aure51horaini">
<?php
echo html_HoraMin('aure51horaini', $_REQUEST['aure51horaini'], 'aure51minini', $_REQUEST['aure51minini']);
?>
</div>
<label class="Label130">
<?php
echo $ETI['aure51horafin'];
?>
</label>
<div class="campo_HoraMin" id="div_aure51horafin">
<?php
echo html_HoraMin('aure51horafin', $_REQUEST['aure51horafin'], 'aure51minfin', $_REQUEST['aure51minfin']);
?>
</div>
<label class="Label130">
<?php
echo $ETI['aure51idsistema'];
?>
</label>
<label>
<div id="div_aure51idsistema">
<?php
echo $html_aure51idsistema;
?>
</div>
</label>
<label class="L">
<?php
echo $ETI['aure51actividad'];
?>

<input id="aure51actividad" name="aure51actividad" type="text" value="<?php echo $_REQUEST['aure51actividad']; ?>" maxlength="250" class="L" placeholder="<?php echo $ETI['ing_campo'] . $ETI['aure51actividad']; ?>" />
</label>
<label class="L">
<?php
echo $ETI['aure51lugar'];
?>

<input id="aure51lugar" name="aure51lugar" type="text" value="<?php echo $_REQUEST['aure51lugar']; ?>" maxlength="250" class="L" placeholder="<?php echo $ETI['ing_campo'] . $ETI['aure51lugar']; ?>" />
</label>
<label class="txtAreaS">
<?php
echo $ETI['aure51detalleactiv'];
?>
<textarea id="aure51detalleactiv" name="aure51detalleactiv" placeholder="<?php echo $ETI['ing_campo'] . $ETI['aure51detalleactiv']; ?>"><?php echo $_REQUEST['aure51detalleactiv']; ?></textarea>
</label>
<label class="txtAreaS">
<?php
echo $ETI['aure51objetivo'];
?>
<textarea id="aure51objetivo" name="aure51objetivo" placeholder="<?php echo $ETI['ing_campo'] . $ETI['aure51objetivo']; ?>"><?php echo $_REQUEST['aure51objetivo']; ?></textarea>
</label>
<label class="txtAreaS">
<?php
echo $ETI['aure51resultado'];
?>
<textarea id="aure51resultado" name="aure51resultado" placeholder="<?php echo $ETI['ing_campo'] . $ETI['aure51resultado']; ?>"><?php echo $_REQUEST['aure51resultado']; ?></textarea>
</label>
<div class="salto1px"></div>
<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo $ETI['aure51idresponsable'];
?>
</label>
<div class="salto1px"></div>
<input id="aure51idresponsable" name="aure51idresponsable" type="hidden" value="<?php echo $_REQUEST['aure51idresponsable']; ?>" />
<div id="div_aure51idresponsable_llaves">
<?php
$bOculto = false;
echo html_DivTerceroV2('aure51idresponsable', $_REQUEST['aure51idresponsable_td'], $_REQUEST['aure51idresponsable_doc'], $bOculto, 0, $ETI['ing_doc']);
?>
</div>
<div class="salto1px"></div>
<div id="div_aure51idresponsable" class="L"><?php echo $aure51idresponsable_rs; ?></div>
<div class="salto1px"></div>
</div>
<label class="Label130">
<?php
echo $ETI['aure51tiporesultado'];
?>
</label>
<label>
<?php
echo $html_aure51tiporesultado;
?>
</label>
<?php
// -- Inicia Grupo campos 252 Participantes
?>
<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo $ETI['titulo_252'];
?>
</label>
<input id="boculta252" name="boculta252" type="hidden" value="<?php echo $_REQUEST['boculta252']; ?>" />
<?php
if ($_REQUEST['paso'] == 2) {
	//if ($bCondicion) {
?>
<div class="ir_derecha"<?php echo $sAnchoExpandeContrae; ?>>
<?php
if (false) {
?>
<label class="Label30">
<input id="btexcel252" name="btexcel252" type="button" value="Exportar" class="btMiniExcel" onclick="imprime252();" title="Exportar" />
</label>
<?php
}
$sEstiloExpande = ' style="display:none;"';
$sEstiloRecoje = '';
$sEstiloDiv = '';
if ($_REQUEST['boculta252'] != 0) {
	$sEstiloExpande = '';
	$sEstiloRecoje = ' style="display:none;"';
	$sEstiloDiv = ' style="display:none;"';
}
?>
<label class="Label30">
<input id="btexpande252" name="btexpande252" type="button" value="Expandir" class="btMiniExpandir" onclick="expandepanel(252, 'block', 0);" title="<?php echo $ETI['bt_mostrar']; ?>"<?php echo $sEstiloExpande; ?>/>
</label>
<label class="Label30">
<input id="btrecoge252" name="btrecoge252" type="button" value="Recoger" class="btMiniRecoger" onclick="expandepanel(252, 'none', 1);" title="<?php echo $ETI['bt_ocultar']; ?>"<?php echo $sEstiloRecoje; ?>/>
</label>
</div>
<div class="salto1px"></div>
<div id="div_p252"<?php echo $sEstiloDiv; ?>>
<div class="salto1px"></div>
<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo $ETI['aure52idtercero'];
?>
</label>
<div class="salto1px"></div>
<input id="aure52idtercero" name="aure52idtercero" type="hidden" value="<?php echo $_REQUEST['aure52idtercero']; ?>" />
<div id="div_aure52idtercero_llaves">
<?php
$bOculto = true;
if ((int)$_REQUEST['aure52id'] == 0) {
	$bOculto = false;
}
echo html_DivTerceroV2('aure52idtercero', $_REQUEST['aure52idtercero_td'], $_REQUEST['aure52idtercero_doc'], $bOculto, 1, $ETI['ing_doc']);
?>
</div>
<div class="salto1px"></div>
<div id="div_aure52idtercero" class="L"><?php echo $aure52idtercero_rs; ?></div>
<div class="salto1px"></div>
</div>
<label class="Label60"<?php echo $sOcultaId; ?>>
<?php
echo $ETI['aure52id'];
?>
</label>
<label class="Label60"<?php echo $sOcultaId; ?>>
<div id="div_aure52id">
<?php
	echo html_oculto('aure52id', $_REQUEST['aure52id'], formato_numero($_REQUEST['aure52id']));
?>
</div>
</label>
<label class="Label130">
<?php
echo $ETI['aure52activo'];
?>
</label>
<label>
<?php
echo $html_aure52activo;
?>
</label>
<div class="salto1px"></div>
<label class="Label130">&nbsp;</label>
<?php
$sEstiloElimina = ' style="display:none;"';
if ((int)$_REQUEST['aure52id'] != 0) {
	$sEstiloElimina = ' style="inline-block;"';
}
?>
<label class="Label30">
<input id="bguarda252" name="bguarda252" type="button" value="Guardar" class="btMiniGuardar" onclick="guardaf252()" title="<?php echo $ETI['bt_mini_guardar_252']; ?>" />
</label>
<label class="Label30">
<input id="blimpia252" name="blimpia252" type="button" value="Limpiar" class="btMiniLimpiar" onclick="limpiaf252()" title="<?php echo $ETI['bt_mini_limpiar_252']; ?>" />
</label>
<label class="Label30">
<input id="belimina252" name="belimina252" type="button" value="Eliminar" class="btMiniEliminar" onclick="eliminaf252()" title="<?php echo $ETI['bt_mini_eliminar_252']; ?>"<?php echo $sEstiloElimina; ?>/>
</label>
<?php
//Este es el cierre del div_p252
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
<input id="bnombre252" name="bnombre252" type="text" value="<?php echo $_REQUEST['bnombre252']; ?>" onchange="paginarf252()" />
</label>
<label class="Label130">
<?php
echo $ETI['msg_Listar'];
?>
</label>
<label>
<?php
echo $html_blistar252;
?>
</label>
<div class="salto1px"></div>
</div>
<?php
}
?>
<div id="div_f252detalle">
<?php
echo $sTabla252;
?>
</div>
<?php
}
?>
<div class="salto1px"></div>
</div>
<?php
// -- Termina Grupo campos 252 Participantes
?>
<?php
// -- Inicia Grupo campos 253 Anexos
?>
<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo $ETI['titulo_253'];
?>
</label>
<input id="boculta253" name="boculta253" type="hidden" value="<?php echo $_REQUEST['boculta253']; ?>" />
<?php
if ($_REQUEST['paso'] == 2) {
	//if ($bCondicion) {
?>
<div class="ir_derecha"<?php echo $sAnchoExpandeContrae; ?>>
<?php
if (false) {
?>
<label class="Label30">
<input id="btexcel253" name="btexcel253" type="button" value="Exportar" class="btMiniExcel" onclick="imprime253();" title="Exportar" />
</label>
<?php
}
$sEstiloExpande = ' style="display:none;"';
$sEstiloRecoje = '';
$sEstiloDiv = '';
if ($_REQUEST['boculta253'] != 0) {
	$sEstiloExpande = '';
	$sEstiloRecoje = ' style="display:none;"';
	$sEstiloDiv = ' style="display:none;"';
}
?>
<label class="Label30">
<input id="btexpande253" name="btexpande253" type="button" value="Expandir" class="btMiniExpandir" onclick="expandepanel(253, 'block', 0);" title="<?php echo $ETI['bt_mostrar']; ?>"<?php echo $sEstiloExpande; ?>/>
</label>
<label class="Label30">
<input id="btrecoge253" name="btrecoge253" type="button" value="Recoger" class="btMiniRecoger" onclick="expandepanel(253, 'none', 1);" title="<?php echo $ETI['bt_ocultar']; ?>"<?php echo $sEstiloRecoje; ?>/>
</label>
</div>
<div class="salto1px"></div>
<div id="div_p253"<?php echo $sEstiloDiv; ?>>
<label class="Label130"<?php echo $sOcultaConsec; ?>>
<?php
echo $ETI['aure58consec'];
?>
</label>
<label class="Label130"<?php echo $sOcultaConsec; ?>>
<div id="div_aure58consec">
<?php
if ((int)$_REQUEST['aure58id'] == 0) {
?>
<input id="aure58consec" name="aure58consec" type="text" value="<?php echo $_REQUEST['aure58consec']; ?>" onchange="revisaf253()" class="cuatro" />
<?php
} else {
	echo html_oculto('aure58consec', $_REQUEST['aure58consec'], formato_numero($_REQUEST['aure58consec']));
}
?>
</div>
</label>
<label class="Label60"<?php echo $sOcultaId; ?>>
<?php
echo $ETI['aure58id'];
?>
</label>
<label class="Label60"<?php echo $sOcultaId; ?>>
<div id="div_aure58id">
<?php
	echo html_oculto('aure58id', $_REQUEST['aure58id'], formato_numero($_REQUEST['aure58id']));
?>
</div>
</label>
<label class="L">
<?php
echo $ETI['aure58titulo'];
?>

<input id="aure58titulo" name="aure58titulo" type="text" value="<?php echo $_REQUEST['aure58titulo']; ?>" maxlength="100" class="L" placeholder="<?php echo $ETI['ing_campo'] . $ETI['aure58titulo']; ?>" />
</label>
<input id="aure58idorigen" name="aure58idorigen" type="hidden" value="<?php echo $_REQUEST['aure58idorigen']; ?>" />
<input id="aure58idarchivo" name="aure58idarchivo" type="hidden" value="<?php echo $_REQUEST['aure58idarchivo']; ?>" />
<div class="GrupoCampos300">
<div class="salto1px"></div>
<div id="div_aure58idarchivo" class="Campo300">
<?php
echo html_lnkarchivo((int)$_REQUEST['aure58idorigen'], (int)$_REQUEST['aure58idarchivo']);
?>
</div>
<?php
$sEstiloAnexa = '';
$sEstiloElimina = ' style="display:none;"';
if ((int)$_REQUEST['aure58id'] == 0) {
	$sEstiloAnexa = ' style="display:none;"';
}
if ((int)$_REQUEST['aure58idarchivo'] != 0) {
	$sEstiloElimina = ' style="inline-block;"';
}
?>
<label class="Label130"></label>
<label class="Label30">
<input id="banexaaure58idarchivo" name="banexaaure58idarchivo" type="button" value="Anexar" class="btMiniAnexar" onclick="carga_aure58idarchivo()" title="Cargar archivo"<?php echo $sEstiloAnexa; ?>/>
</label>
<label class="Label30">
<input id="beliminaaure58idarchivo" name="beliminaaure58idarchivo" type="button" value="Eliminar" class="btMiniEliminar" onclick="eliminaaure58idarchivo()" title="Eliminar archivo"<?php echo $sEstiloElimina; ?>/>
</label>
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo $ETI['aure58idusuario'];
?>
</label>
<div class="salto1px"></div>
<input id="aure58idusuario" name="aure58idusuario" type="hidden" value="<?php echo $_REQUEST['aure58idusuario']; ?>" />
<div id="div_aure58idusuario_llaves">
<?php
$bOculto = true;
echo html_DivTerceroV2('aure58idusuario', $_REQUEST['aure58idusuario_td'], $_REQUEST['aure58idusuario_doc'], $bOculto, 0, $ETI['ing_doc']);
?>
</div>
<div class="salto1px"></div>
<div id="div_aure58idusuario" class="L"><?php echo $aure58idusuario_rs; ?></div>
<div class="salto1px"></div>
</div>
<label class="Label130">
<?php
echo $ETI['aure58fecha'];
?>
</label>
<label class="Label220">
<div id="div_aure58fecha">
<?php
echo html_oculto('aure58fecha', $_REQUEST['aure58fecha'], fecha_desdenumero($_REQUEST['aure58fecha'])); //formato_FechaLargaDesdeNumero
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label130">&nbsp;</label>
<?php
$sEstiloElimina = ' style="display:none;"';
if ((int)$_REQUEST['aure58id'] != 0) {
	$sEstiloElimina = ' style="inline-block;"';
}
?>
<label class="Label30">
<input id="bguarda253" name="bguarda253" type="button" value="Guardar" class="btMiniGuardar" onclick="guardaf253()" title="<?php echo $ETI['bt_mini_guardar_253']; ?>" />
</label>
<label class="Label30">
<input id="blimpia253" name="blimpia253" type="button" value="Limpiar" class="btMiniLimpiar" onclick="limpiaf253()" title="<?php echo $ETI['bt_mini_limpiar_253']; ?>" />
</label>
<label class="Label30">
<input id="belimina253" name="belimina253" type="button" value="Eliminar" class="btMiniEliminar" onclick="eliminaf253()" title="<?php echo $ETI['bt_mini_eliminar_253']; ?>"<?php echo $sEstiloElimina; ?>/>
</label>
<?php
//Este es el cierre del div_p253
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
<input id="bnombre253" name="bnombre253" type="text" value="<?php echo $_REQUEST['bnombre253']; ?>" onchange="paginarf253()" />
</label>
<label class="Label130">
<?php
echo $ETI['msg_Listar'];
?>
</label>
<label>
<?php
echo $html_blistar253;
?>
</label>
<div class="salto1px"></div>
</div>
<?php
}
?>
<div id="div_f253detalle">
<?php
echo $sTabla253;
?>
</div>
<?php
}
?>
<div class="salto1px"></div>
</div>
<?php
// -- Termina Grupo campos 253 Anexos
?>
<?php
// -- Inicia Grupo campos 257 Riesgos
?>
<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo $ETI['titulo_257'];
?>
</label>
<input id="boculta257" name="boculta257" type="hidden" value="<?php echo $_REQUEST['boculta257']; ?>" />
<?php
if ($_REQUEST['paso'] == 2) {
	//if ($bCondicion) {
?>
<div class="ir_derecha"<?php echo $sAnchoExpandeContrae; ?>>
<?php
if (false) {
?>
<label class="Label30">
<input id="btexcel257" name="btexcel257" type="button" value="Exportar" class="btMiniExcel" onclick="imprime257();" title="Exportar" />
</label>
<?php
}
$sEstiloExpande = ' style="display:none;"';
$sEstiloRecoje = '';
$sEstiloDiv = '';
if ($_REQUEST['boculta257'] != 0) {
	$sEstiloExpande = '';
	$sEstiloRecoje = ' style="display:none;"';
	$sEstiloDiv = ' style="display:none;"';
}
?>
<label class="Label30">
<input id="btexpande257" name="btexpande257" type="button" value="Expandir" class="btMiniExpandir" onclick="expandepanel(257, 'block', 0);" title="<?php echo $ETI['bt_mostrar']; ?>"<?php echo $sEstiloExpande; ?>/>
</label>
<label class="Label30">
<input id="btrecoge257" name="btrecoge257" type="button" value="Recoger" class="btMiniRecoger" onclick="expandepanel(257, 'none', 1);" title="<?php echo $ETI['bt_ocultar']; ?>"<?php echo $sEstiloRecoje; ?>/>
</label>
</div>
<div class="salto1px"></div>
<div id="div_p257"<?php echo $sEstiloDiv; ?>>
<label class="Label130"<?php echo $sOcultaConsec; ?>>
<?php
echo $ETI['aure57idriesgo'];
?>
</label>
<label class="Label130"<?php echo $sOcultaConsec; ?>>
<div id="div_aure57idriesgo">
<?php
if ((int)$_REQUEST['aure57id'] == 0) {
?>
<input id="aure57idriesgo" name="aure57idriesgo" type="text" value="<?php echo $_REQUEST['aure57idriesgo']; ?>" onchange="revisaf257()" class="cuatro" />
<?php
} else {
	echo html_oculto('aure57idriesgo', $_REQUEST['aure57idriesgo'], formato_numero($_REQUEST['aure57idriesgo']));
}
?>
</div>
</label>
<label class="Label60"<?php echo $sOcultaId; ?>>
<?php
echo $ETI['aure57id'];
?>
</label>
<label class="Label60"<?php echo $sOcultaId; ?>>
<div id="div_aure57id">
<?php
	echo html_oculto('aure57id', $_REQUEST['aure57id'], formato_numero($_REQUEST['aure57id']));
?>
</div>
</label>
<label class="Label130">
<?php
echo $ETI['aure57nivelriesgo'];
?>
</label>
<label>
<?php
echo $html_aure57nivelriesgo;
?>
</label>
<div class="salto1px"></div>
<label class="Label130">&nbsp;</label>
<?php
$sEstiloElimina = ' style="display:none;"';
if ((int)$_REQUEST['aure57id'] != 0) {
	$sEstiloElimina = ' style="inline-block;"';
}
?>
<label class="Label30">
<input id="bguarda257" name="bguarda257" type="button" value="Guardar" class="btMiniGuardar" onclick="guardaf257()" title="<?php echo $ETI['bt_mini_guardar_257']; ?>" />
</label>
<label class="Label30">
<input id="blimpia257" name="blimpia257" type="button" value="Limpiar" class="btMiniLimpiar" onclick="limpiaf257()" title="<?php echo $ETI['bt_mini_limpiar_257']; ?>" />
</label>
<label class="Label30">
<input id="belimina257" name="belimina257" type="button" value="Eliminar" class="btMiniEliminar" onclick="eliminaf257()" title="<?php echo $ETI['bt_mini_eliminar_257']; ?>"<?php echo $sEstiloElimina; ?>/>
</label>
<?php
//Este es el cierre del div_p257
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
<input id="bnombre257" name="bnombre257" type="text" value="<?php echo $_REQUEST['bnombre257']; ?>" onchange="paginarf257()" />
</label>
<label class="Label130">
<?php
echo $ETI['msg_Listar'];
?>
</label>
<label>
<?php
echo $html_blistar257;
?>
</label>
<div class="salto1px"></div>
</div>
<?php
}
?>
<div id="div_f257detalle">
<?php
echo $sTabla257;
?>
</div>
<?php
}
?>
<div class="salto1px"></div>
</div>
<?php
// -- Termina Grupo campos 257 Riesgos
?>
<?php
// -- Inicia Grupo campos 280 Historia de usuario
?>
<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo $ETI['titulo_280'];
?>
</label>
<input id="boculta280" name="boculta280" type="hidden" value="<?php echo $_REQUEST['boculta280']; ?>" />
<?php
if ($_REQUEST['paso'] == 2) {
	//if ($bCondicion) {
?>
<div class="ir_derecha"<?php echo $sAnchoExpandeContrae; ?>>
<?php
if (false) {
?>
<label class="Label30">
<input id="btexcel280" name="btexcel280" type="button" value="Exportar" class="btMiniExcel" onclick="imprime280();" title="Exportar" />
</label>
<?php
}
$sEstiloExpande = ' style="display:none;"';
$sEstiloRecoje = '';
$sEstiloDiv = '';
if ($_REQUEST['boculta280'] != 0) {
	$sEstiloExpande = '';
	$sEstiloRecoje = ' style="display:none;"';
	$sEstiloDiv = ' style="display:none;"';
}
?>
<label class="Label30">
<input id="btexpande280" name="btexpande280" type="button" value="Expandir" class="btMiniExpandir" onclick="expandepanel(280, 'block', 0);" title="<?php echo $ETI['bt_mostrar']; ?>"<?php echo $sEstiloExpande; ?>/>
</label>
<label class="Label30">
<input id="btrecoge280" name="btrecoge280" type="button" value="Recoger" class="btMiniRecoger" onclick="expandepanel(280, 'none', 1);" title="<?php echo $ETI['bt_ocultar']; ?>"<?php echo $sEstiloRecoje; ?>/>
</label>
</div>
<div class="salto1px"></div>
<div id="div_p280"<?php echo $sEstiloDiv; ?>>
<label class="Label130"<?php echo $sOcultaConsec; ?>>
<?php
echo $ETI['aure80consec'];
?>
</label>
<label class="Label130"<?php echo $sOcultaConsec; ?>>
<div id="div_aure80consec">
<?php
if ((int)$_REQUEST['aure80id'] == 0) {
?>
<input id="aure80consec" name="aure80consec" type="text" value="<?php echo $_REQUEST['aure80consec']; ?>" onchange="revisaf280()" class="cuatro" />
<?php
} else {
	echo html_oculto('aure80consec', $_REQUEST['aure80consec'], formato_numero($_REQUEST['aure80consec']));
}
?>
</div>
</label>
<label class="Label60"<?php echo $sOcultaId; ?>>
<?php
echo $ETI['aure80id'];
?>
</label>
<label class="Label60"<?php echo $sOcultaId; ?>>
<div id="div_aure80id">
<?php
	echo html_oculto('aure80id', $_REQUEST['aure80id'], formato_numero($_REQUEST['aure80id']));
?>
</div>
</label>
<label class="Label130">
<?php
echo $ETI['aure80momento'];
?>
</label>
<label>
<?php
echo $html_aure80momento;
?>
</label>
<label class="txtAreaS">
<?php
echo $ETI['aure80infousuario'];
?>
<textarea id="aure80infousuario" name="aure80infousuario" placeholder="<?php echo $ETI['ing_campo'] . $ETI['aure80infousuario']; ?>"><?php echo $_REQUEST['aure80infousuario']; ?></textarea>
</label>
<label class="Label130">
<?php
echo $ETI['aure80prioridad'];
?>
</label>
<label>
<?php
echo $html_aure80prioridad;
?>
</label>
<label class="Label130">
<?php
echo $ETI['aure80semanaest'];
?>
</label>
<label class="Label130">

<input id="aure80semanaest" name="aure80semanaest" type="text" value="<?php echo $_REQUEST['aure80semanaest']; ?>" class="diez" maxlength="10" placeholder="<?php echo $ETI['ing_vr']; ?>" />
</label>
<label class="Label130">
<?php
echo $ETI['aure80diasest'];
?>
</label>
<label class="Label130">

<input id="aure80diasest" name="aure80diasest" type="text" value="<?php echo $_REQUEST['aure80diasest']; ?>" class="diez" maxlength="10" placeholder="<?php echo $ETI['ing_vr']; ?>" />
</label>
<label class="Label130">
<?php
echo $ETI['aure80iteracionasig'];
?>
</label>
<label class="Label130">

<input id="aure80iteracionasig" name="aure80iteracionasig" type="text" value="<?php echo $_REQUEST['aure80iteracionasig']; ?>" class="diez" maxlength="10" placeholder="<?php echo $ETI['ing_vr']; ?>" />
</label>
<label class="txtAreaS">
<?php
echo $ETI['aure80infotecnica'];
?>
<textarea id="aure80infotecnica" name="aure80infotecnica" placeholder="<?php echo $ETI['ing_campo'] . $ETI['aure80infotecnica']; ?>"><?php echo $_REQUEST['aure80infotecnica']; ?></textarea>
</label>
<label class="txtAreaS">
<?php
echo $ETI['aure80observaciones'];
?>
<textarea id="aure80observaciones" name="aure80observaciones" placeholder="<?php echo $ETI['ing_campo'] . $ETI['aure80observaciones']; ?>"><?php echo $_REQUEST['aure80observaciones']; ?></textarea>
</label>
<div class="salto1px"></div>
<label class="Label130">&nbsp;</label>
<?php
$sEstiloElimina = ' style="display:none;"';
if ((int)$_REQUEST['aure80id'] != 0) {
	$sEstiloElimina = ' style="inline-block;"';
}
?>
<label class="Label30">
<input id="bguarda280" name="bguarda280" type="button" value="Guardar" class="btMiniGuardar" onclick="guardaf280()" title="<?php echo $ETI['bt_mini_guardar_280']; ?>" />
</label>
<label class="Label30">
<input id="blimpia280" name="blimpia280" type="button" value="Limpiar" class="btMiniLimpiar" onclick="limpiaf280()" title="<?php echo $ETI['bt_mini_limpiar_280']; ?>" />
</label>
<label class="Label30">
<input id="belimina280" name="belimina280" type="button" value="Eliminar" class="btMiniEliminar" onclick="eliminaf280()" title="<?php echo $ETI['bt_mini_eliminar_280']; ?>"<?php echo $sEstiloElimina; ?>/>
</label>
<?php
//Este es el cierre del div_p280
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
<input id="bnombre280" name="bnombre280" type="text" value="<?php echo $_REQUEST['bnombre280']; ?>" onchange="paginarf280()" />
</label>
<label class="Label130">
<?php
echo $ETI['msg_Listar'];
?>
</label>
<label>
<?php
echo $html_blistar280;
?>
</label>
<div class="salto1px"></div>
</div>
<?php
}
?>
<div id="div_f280detalle">
<?php
echo $sTabla280;
?>
</div>
<?php
}
?>
<div class="salto1px"></div>
</div>
<?php
// -- Termina Grupo campos 280 Historia de usuario
?>
<?php
// -- Inicia Grupo campos 281 Tareas de ingenieria
?>
<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo $ETI['titulo_281'];
?>
</label>
<input id="boculta281" name="boculta281" type="hidden" value="<?php echo $_REQUEST['boculta281']; ?>" />
<?php
if ($_REQUEST['paso'] == 2) {
	//if ($bCondicion) {
?>
<div class="ir_derecha"<?php echo $sAnchoExpandeContrae; ?>>
<?php
if (false) {
?>
<label class="Label30">
<input id="btexcel281" name="btexcel281" type="button" value="Exportar" class="btMiniExcel" onclick="imprime281();" title="Exportar" />
</label>
<?php
}
$sEstiloExpande = ' style="display:none;"';
$sEstiloRecoje = '';
$sEstiloDiv = '';
if ($_REQUEST['boculta281'] != 0) {
	$sEstiloExpande = '';
	$sEstiloRecoje = ' style="display:none;"';
	$sEstiloDiv = ' style="display:none;"';
}
?>
<label class="Label30">
<input id="btexpande281" name="btexpande281" type="button" value="Expandir" class="btMiniExpandir" onclick="expandepanel(281, 'block', 0);" title="<?php echo $ETI['bt_mostrar']; ?>"<?php echo $sEstiloExpande; ?>/>
</label>
<label class="Label30">
<input id="btrecoge281" name="btrecoge281" type="button" value="Recoger" class="btMiniRecoger" onclick="expandepanel(281, 'none', 1);" title="<?php echo $ETI['bt_ocultar']; ?>"<?php echo $sEstiloRecoje; ?>/>
</label>
</div>
<div class="salto1px"></div>
<div id="div_p281"<?php echo $sEstiloDiv; ?>>
<label class="Label130"<?php echo $sOcultaConsec; ?>>
<?php
echo $ETI['aure81consec'];
?>
</label>
<label class="Label130"<?php echo $sOcultaConsec; ?>>
<div id="div_aure81consec">
<?php
if ((int)$_REQUEST['aure81id'] == 0) {
?>
<input id="aure81consec" name="aure81consec" type="text" value="<?php echo $_REQUEST['aure81consec']; ?>" onchange="revisaf281()" class="cuatro" />
<?php
} else {
	echo html_oculto('aure81consec', $_REQUEST['aure81consec'], formato_numero($_REQUEST['aure81consec']));
}
?>
</div>
</label>
<label class="Label60"<?php echo $sOcultaId; ?>>
<?php
echo $ETI['aure81id'];
?>
</label>
<label class="Label60"<?php echo $sOcultaId; ?>>
<div id="div_aure81id">
<?php
	echo html_oculto('aure81id', $_REQUEST['aure81id'], formato_numero($_REQUEST['aure81id']));
?>
</div>
</label>
<label class="Label130">
<?php
echo $ETI['aure81idbithistoria'];
?>
</label>
<label>
<?php
echo $html_aure81idbithistoria;
?>
</label>
<label class="Label130">
<?php
echo $ETI['aure81idtipotarea'];
?>
</label>
<label>
<div id="div_aure81idtipotarea">
<?php
echo $html_aure81idtipotarea;
?>
</div>
</label>
<label class="Label130">
<?php
echo $ETI['aure81semanasest'];
?>
</label>
<label class="Label130">

<input id="aure81semanasest" name="aure81semanasest" type="text" value="<?php echo $_REQUEST['aure81semanasest']; ?>" class="diez" maxlength="10" placeholder="<?php echo $ETI['ing_vr']; ?>" />
</label>
<label class="Label130">
<?php
echo $ETI['aure81diasest'];
?>
</label>
<label class="Label130">

<input id="aure81diasest" name="aure81diasest" type="text" value="<?php echo $_REQUEST['aure81diasest']; ?>" class="diez" maxlength="10" placeholder="<?php echo $ETI['ing_vr']; ?>" />
</label>
<label class="Label130">
<?php
echo $ETI['aure81fechainicio'];
?>
</label>
<div class="Campo220">
<?php
echo html_FechaEnNumero('aure81fechainicio', $_REQUEST['aure81fechainicio']); //, false, '', $iAgnoIni, $iAgnoFin); //$bvacio, $accion
?>
</div>
<?php
if (false) {
?>
<label class="Label30">
<input id="baure81fechainicio_hoy" name="baure81fechainicio_hoy" type="button" value="Hoy" class="btMiniHoy" onclick="fecha_AsignarNum('aure81fechainicio', '<?php echo $iHoy; ?>')" title="<?php echo $ETI['bt_hoy']; ?>" />
</label>
<?php
}
?>
<label class="Label130">
<?php
echo $ETI['aure81avance'];
?>
</label>
<label>
<div id="div_aure81avance">
<?php
echo $html_aure81avance;
?>
</div>
</label>
<label class="Label130">
<?php
echo $ETI['aure81fechafinal'];
?>
</label>
<div class="Campo220">
<?php
echo html_FechaEnNumero('aure81fechafinal', $_REQUEST['aure81fechafinal']); //, false, '', $iAgnoIni, $iAgnoFin); //$bvacio, $accion
?>
</div>
<?php
if (false) {
?>
<label class="Label30">
<input id="baure81fechafinal_hoy" name="baure81fechafinal_hoy" type="button" value="Hoy" class="btMiniHoy" onclick="fecha_AsignarNum('aure81fechafinal', '<?php echo $iHoy; ?>')" title="<?php echo $ETI['bt_hoy']; ?>" />
</label>
<?php
}
?>
<label class="txtAreaS">
<?php
echo $ETI['aure81descripcion'];
?>
<textarea id="aure81descripcion" name="aure81descripcion" placeholder="<?php echo $ETI['ing_campo'] . $ETI['aure81descripcion']; ?>"><?php echo $_REQUEST['aure81descripcion']; ?></textarea>
</label>
<div class="salto1px"></div>
<label class="Label130">&nbsp;</label>
<?php
$sEstiloElimina = ' style="display:none;"';
if ((int)$_REQUEST['aure81id'] != 0) {
	$sEstiloElimina = ' style="inline-block;"';
}
?>
<label class="Label30">
<input id="bguarda281" name="bguarda281" type="button" value="Guardar" class="btMiniGuardar" onclick="guardaf281()" title="<?php echo $ETI['bt_mini_guardar_281']; ?>" />
</label>
<label class="Label30">
<input id="blimpia281" name="blimpia281" type="button" value="Limpiar" class="btMiniLimpiar" onclick="limpiaf281()" title="<?php echo $ETI['bt_mini_limpiar_281']; ?>" />
</label>
<label class="Label30">
<input id="belimina281" name="belimina281" type="button" value="Eliminar" class="btMiniEliminar" onclick="eliminaf281()" title="<?php echo $ETI['bt_mini_eliminar_281']; ?>"<?php echo $sEstiloElimina; ?>/>
</label>
<?php
//Este es el cierre del div_p281
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
<input id="bnombre281" name="bnombre281" type="text" value="<?php echo $_REQUEST['bnombre281']; ?>" onchange="paginarf281()" />
</label>
<label class="Label130">
<?php
echo $ETI['msg_Listar'];
?>
</label>
<label>
<?php
echo $html_blistar281;
?>
</label>
<div class="salto1px"></div>
</div>
<?php
}
?>
<div id="div_f281detalle">
<?php
echo $sTabla281;
?>
</div>
<?php
}
?>
<div class="salto1px"></div>
</div>
<?php
// -- Termina Grupo campos 281 Tareas de ingenieria
?>
<?php
// -- Inicia Grupo campos 282 Pruebas de aceptacion
?>
<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo $ETI['titulo_282'];
?>
</label>
<input id="boculta282" name="boculta282" type="hidden" value="<?php echo $_REQUEST['boculta282']; ?>" />
<?php
if ($_REQUEST['paso'] == 2) {
	//if ($bCondicion) {
?>
<div class="ir_derecha"<?php echo $sAnchoExpandeContrae; ?>>
<?php
if (false) {
?>
<label class="Label30">
<input id="btexcel282" name="btexcel282" type="button" value="Exportar" class="btMiniExcel" onclick="imprime282();" title="Exportar" />
</label>
<?php
}
$sEstiloExpande = ' style="display:none;"';
$sEstiloRecoje = '';
$sEstiloDiv = '';
if ($_REQUEST['boculta282'] != 0) {
	$sEstiloExpande = '';
	$sEstiloRecoje = ' style="display:none;"';
	$sEstiloDiv = ' style="display:none;"';
}
?>
<label class="Label30">
<input id="btexpande282" name="btexpande282" type="button" value="Expandir" class="btMiniExpandir" onclick="expandepanel(282, 'block', 0);" title="<?php echo $ETI['bt_mostrar']; ?>"<?php echo $sEstiloExpande; ?>/>
</label>
<label class="Label30">
<input id="btrecoge282" name="btrecoge282" type="button" value="Recoger" class="btMiniRecoger" onclick="expandepanel(282, 'none', 1);" title="<?php echo $ETI['bt_ocultar']; ?>"<?php echo $sEstiloRecoje; ?>/>
</label>
</div>
<div class="salto1px"></div>
<div id="div_p282"<?php echo $sEstiloDiv; ?>>
<label class="Label130"<?php echo $sOcultaConsec; ?>>
<?php
echo $ETI['aure82consec'];
?>
</label>
<label class="Label130"<?php echo $sOcultaConsec; ?>>
<div id="div_aure82consec">
<?php
if ((int)$_REQUEST['aure82id'] == 0) {
?>
<input id="aure82consec" name="aure82consec" type="text" value="<?php echo $_REQUEST['aure82consec']; ?>" onchange="revisaf282()" class="cuatro" />
<?php
} else {
	echo html_oculto('aure82consec', $_REQUEST['aure82consec'], formato_numero($_REQUEST['aure82consec']));
}
?>
</div>
</label>
<label class="Label60"<?php echo $sOcultaId; ?>>
<?php
echo $ETI['aure82id'];
?>
</label>
<label class="Label60"<?php echo $sOcultaId; ?>>
<div id="div_aure82id">
<?php
	echo html_oculto('aure82id', $_REQUEST['aure82id'], formato_numero($_REQUEST['aure82id']));
?>
</div>
</label>
<label class="txtAreaS">
<?php
echo $ETI['aure82condiciones'];
?>
<textarea id="aure82condiciones" name="aure82condiciones" placeholder="<?php echo $ETI['ing_campo'] . $ETI['aure82condiciones']; ?>"><?php echo $_REQUEST['aure82condiciones']; ?></textarea>
</label>
<label class="txtAreaS">
<?php
echo $ETI['aure82pasos'];
?>
<textarea id="aure82pasos" name="aure82pasos" placeholder="<?php echo $ETI['ing_campo'] . $ETI['aure82pasos']; ?>"><?php echo $_REQUEST['aure82pasos']; ?></textarea>
</label>
<label class="Label130">
<?php
echo $ETI['aure82asignaperfil'];
?>
</label>
<label class="Label130">
<?php
echo $html_aure82asignaperfil;
?>
</label>
<label class="Label130">
<?php
echo $ETI['aure82manuales'];
?>
</label>
<label class="Label130">
<?php
echo $html_aure82manuales;
?>
</label>
<label class="Label130">
<?php
echo $ETI['aure82capacitacion'];
?>
</label>
<label class="Label130">
<?php
echo $html_aure82capacitacion;
?>
</label>
<label class="Label130">
<?php
echo $ETI['aure82evaluacion'];
?>
</label>
<label class="Label130">
<?php
echo $html_aure82evaluacion;
?>
</label>
<label class="txtAreaS">
<?php
echo $ETI['aure82resultadoesp'];
?>
<textarea id="aure82resultadoesp" name="aure82resultadoesp" placeholder="<?php echo $ETI['ing_campo'] . $ETI['aure82resultadoesp']; ?>"><?php echo $_REQUEST['aure82resultadoesp']; ?></textarea>
</label>
<div class="salto1px"></div>
<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo $ETI['aure82idtester'];
?>
</label>
<div class="salto1px"></div>
<input id="aure82idtester" name="aure82idtester" type="hidden" value="<?php echo $_REQUEST['aure82idtester']; ?>" />
<div id="div_aure82idtester_llaves">
<?php
$bOculto = false;
echo html_DivTerceroV2('aure82idtester', $_REQUEST['aure82idtester_td'], $_REQUEST['aure82idtester_doc'], $bOculto, 0, $ETI['ing_doc']);
?>
</div>
<div class="salto1px"></div>
<div id="div_aure82idtester" class="L"><?php echo $aure82idtester_rs; ?></div>
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
<label class="Label130">&nbsp;</label>
<?php
$sEstiloElimina = ' style="display:none;"';
if ((int)$_REQUEST['aure82id'] != 0) {
	$sEstiloElimina = ' style="inline-block;"';
}
?>
<label class="Label30">
<input id="bguarda282" name="bguarda282" type="button" value="Guardar" class="btMiniGuardar" onclick="guardaf282()" title="<?php echo $ETI['bt_mini_guardar_282']; ?>" />
</label>
<label class="Label30">
<input id="blimpia282" name="blimpia282" type="button" value="Limpiar" class="btMiniLimpiar" onclick="limpiaf282()" title="<?php echo $ETI['bt_mini_limpiar_282']; ?>" />
</label>
<label class="Label30">
<input id="belimina282" name="belimina282" type="button" value="Eliminar" class="btMiniEliminar" onclick="eliminaf282()" title="<?php echo $ETI['bt_mini_eliminar_282']; ?>"<?php echo $sEstiloElimina; ?>/>
</label>
<?php
//Este es el cierre del div_p282
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
<input id="bnombre282" name="bnombre282" type="text" value="<?php echo $_REQUEST['bnombre282']; ?>" onchange="paginarf282()" />
</label>
<label class="Label130">
<?php
echo $ETI['msg_Listar'];
?>
</label>
<label>
<?php
echo $html_blistar282;
?>
</label>
<div class="salto1px"></div>
</div>
<?php
}
?>
<div id="div_f282detalle">
<?php
echo $sTabla282;
?>
</div>
<?php
}
?>
<div class="salto1px"></div>
</div>
<?php
// -- Termina Grupo campos 282 Pruebas de aceptacion
?>
<?php
// -- Inicia Grupo campos 283 Tarjetas CRC
?>
<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo $ETI['titulo_283'];
?>
</label>
<input id="boculta283" name="boculta283" type="hidden" value="<?php echo $_REQUEST['boculta283']; ?>" />
<?php
if ($_REQUEST['paso'] == 2) {
	//if ($bCondicion) {
?>
<div class="ir_derecha"<?php echo $sAnchoExpandeContrae; ?>>
<?php
if (false) {
?>
<label class="Label30">
<input id="btexcel283" name="btexcel283" type="button" value="Exportar" class="btMiniExcel" onclick="imprime283();" title="Exportar" />
</label>
<?php
}
$sEstiloExpande = ' style="display:none;"';
$sEstiloRecoje = '';
$sEstiloDiv = '';
if ($_REQUEST['boculta283'] != 0) {
	$sEstiloExpande = '';
	$sEstiloRecoje = ' style="display:none;"';
	$sEstiloDiv = ' style="display:none;"';
}
?>
<label class="Label30">
<input id="btexpande283" name="btexpande283" type="button" value="Expandir" class="btMiniExpandir" onclick="expandepanel(283, 'block', 0);" title="<?php echo $ETI['bt_mostrar']; ?>"<?php echo $sEstiloExpande; ?>/>
</label>
<label class="Label30">
<input id="btrecoge283" name="btrecoge283" type="button" value="Recoger" class="btMiniRecoger" onclick="expandepanel(283, 'none', 1);" title="<?php echo $ETI['bt_ocultar']; ?>"<?php echo $sEstiloRecoje; ?>/>
</label>
</div>
<div class="salto1px"></div>
<div id="div_p283"<?php echo $sEstiloDiv; ?>>
<label class="Label130"<?php echo $sOcultaConsec; ?>>
<?php
echo $ETI['aure83consec'];
?>
</label>
<label class="Label130"<?php echo $sOcultaConsec; ?>>
<div id="div_aure83consec">
<?php
if ((int)$_REQUEST['aure83id'] == 0) {
?>
<input id="aure83consec" name="aure83consec" type="text" value="<?php echo $_REQUEST['aure83consec']; ?>" onchange="revisaf283()" class="cuatro" />
<?php
} else {
	echo html_oculto('aure83consec', $_REQUEST['aure83consec'], formato_numero($_REQUEST['aure83consec']));
}
?>
</div>
</label>
<label class="Label60"<?php echo $sOcultaId; ?>>
<?php
echo $ETI['aure83id'];
?>
</label>
<label class="Label60"<?php echo $sOcultaId; ?>>
<div id="div_aure83id">
<?php
	echo html_oculto('aure83id', $_REQUEST['aure83id'], formato_numero($_REQUEST['aure83id']));
?>
</div>
</label>
<label class="Label130">
<?php
echo $ETI['aure83idbithistoria'];
?>
</label>
<label>
<?php
echo $html_aure83idbithistoria;
?>
</label>
<label class="Label130">
<?php
echo $ETI['aure83idtarea'];
?>
</label>
<label>
<?php
echo $html_aure83idtarea;
?>
</label>
<label class="Label130">
<?php
echo $ETI['aure83vigente'];
?>
</label>
<label class="Label130">
<?php
echo $html_aure83vigente;
?>
</label>
<label class="L">
<?php
echo $ETI['aure83nombreclase'];
?>

<input id="aure83nombreclase" name="aure83nombreclase" type="text" value="<?php echo $_REQUEST['aure83nombreclase']; ?>" maxlength="200" class="L" placeholder="<?php echo $ETI['ing_campo'] . $ETI['aure83nombreclase']; ?>" />
</label>
<label class="txtAreaS">
<?php
echo $ETI['aure83responsabilidades'];
?>
<textarea id="aure83responsabilidades" name="aure83responsabilidades" placeholder="<?php echo $ETI['ing_campo'] . $ETI['aure83responsabilidades']; ?>"><?php echo $_REQUEST['aure83responsabilidades']; ?></textarea>
</label>
<label class="txtAreaS">
<?php
echo $ETI['aure83colaboradores'];
?>
<textarea id="aure83colaboradores" name="aure83colaboradores" placeholder="<?php echo $ETI['ing_campo'] . $ETI['aure83colaboradores']; ?>"><?php echo $_REQUEST['aure83colaboradores']; ?></textarea>
</label>
<label class="Label130">
<?php
echo $ETI['aure83idtabla'];
?>
</label>
<label>
<?php
echo $html_aure83idtabla;
?>
</label>
<div class="salto1px"></div>
<label class="Label130">&nbsp;</label>
<?php
$sEstiloElimina = ' style="display:none;"';
if ((int)$_REQUEST['aure83id'] != 0) {
	$sEstiloElimina = ' style="inline-block;"';
}
?>
<label class="Label30">
<input id="bguarda283" name="bguarda283" type="button" value="Guardar" class="btMiniGuardar" onclick="guardaf283()" title="<?php echo $ETI['bt_mini_guardar_283']; ?>" />
</label>
<label class="Label30">
<input id="blimpia283" name="blimpia283" type="button" value="Limpiar" class="btMiniLimpiar" onclick="limpiaf283()" title="<?php echo $ETI['bt_mini_limpiar_283']; ?>" />
</label>
<label class="Label30">
<input id="belimina283" name="belimina283" type="button" value="Eliminar" class="btMiniEliminar" onclick="eliminaf283()" title="<?php echo $ETI['bt_mini_eliminar_283']; ?>"<?php echo $sEstiloElimina; ?>/>
</label>
<?php
//Este es el cierre del div_p283
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
<input id="bnombre283" name="bnombre283" type="text" value="<?php echo $_REQUEST['bnombre283']; ?>" onchange="paginarf283()" />
</label>
<label class="Label130">
<?php
echo $ETI['msg_Listar'];
?>
</label>
<label>
<?php
echo $html_blistar283;
?>
</label>
<div class="salto1px"></div>
</div>
<?php
}
?>
<div id="div_f283detalle">
<?php
echo $sTabla283;
?>
</div>
<?php
}
?>
<div class="salto1px"></div>
</div>
<?php
// -- Termina Grupo campos 283 Tarjetas CRC
?>
<?php
if (false) {
	//Ejemplo de boton de ayuda
	//echo html_BotonAyuda('NombreCampo');
	//echo html_DivAyudaLocal('NombreCampo');
}
if ($bConExpande) {
	//Este es el cierre del div_p251
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
<?php
if (false) {
?>
<div class="ir_derecha">
<label class="Label90">
<?php
echo $ETI['msg_bnombre'];
?>
</label>
<label>
<input id="bnombre" name="bnombre" type="text" value="<?php echo $_REQUEST['bnombre']; ?>" onchange="paginarf251()" autocomplete="off" />
</label>
<label class="Label90">
<?php
echo $ETI['msg_blistar'];
?>
</label>
<label class="Label130">
<?php
echo $html_blistar;
?>
</label>
</div>
<div class="salto1px"></div>
<?php
}
?>
<?php
echo ' ' . $csv_separa;
?>
<div id="div_f251detalle">
<?php
echo $sTabla251;
?>
</div>
<?php
// Termina el div_areatrabajo y DIV_areaform
?>
</div>
</div>
</div>


<div id="div_sector2" style="display:none">
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
<div id="cargaForm">
<div id="area">
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
echo $ETI['msg_aure51consec'];
?>
</label>
<label class="Label90">
<?php
echo '<b>' . $_REQUEST['aure51consec'] . '</b>';
?>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['msg_aure51consec_nuevo'];
// onchange="RevisaConsec()"
?>
</label>
<label class="Label90">
<input id="aure51consec_nuevo" name="aure51consec_nuevo" type="text" value="<?php echo $_REQUEST['aure51consec_nuevo']; ?>" class="cuatro" />
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
<input id="titulo_251" name="titulo_251" type="hidden" value="<?php echo $ETI['titulo_251']; ?>" />
<div class="titulos">
<div class="titulosD">
<input id="cmdAyuda96" name="cmdAyuda96" type="button" class="btSupAyuda" onclick="muestraayuda(<?php echo $iCodModulo; ?>);" title="<?php echo $ETI['bt_ayuda']; ?>" value="<?php echo $ETI['bt_ayuda']; ?>" />
</div>
<div class="titulosI" id="div_96titulo"></div>
</div>
<div id="cargaForm">
<div id="div_96cuerpo"></div>
</div>
</div>


<div id="div_sector97" style="display:none">
<div class="titulos">
<div class="titulosD">
<input id="cmdAyuda97" name="cmdAyuda97" type="button" class="btSupAyuda" onclick="muestraayuda(<?php echo $iCodModulo; ?>);" title="<?php echo $ETI['bt_ayuda']; ?>" value="<?php echo $ETI['bt_ayuda']; ?>" />
<input id="cmdVolverSec97" name="cmdVolverSec97" type="button" class="btSupVolver" onclick="retornacontrol();" title="<?php echo $ETI['bt_volver']; ?>" value="<?php echo $ETI['bt_volver']; ?>" />
</div>
<div class="titulosI" id="div_97titulo">
<?php
echo '<h2>' . $ETI['titulo_251'] . '</h2>';
?>
</div>
</div>
<div id="areaform">
<div id="areatrabajo">
<div id="div_97params"></div>
<div class="salto1px"></div>
<div id="div_97tabla"></div>
</div>
</div>
</div>


<div id="div_sector98" style="display:none">
<div class="titulos">
<div class="titulosD">
<input id="cmdAyuda98" name="cmdAyuda98" type="button" class="btSupAyuda" onclick="muestraayuda(<?php echo $iCodModulo; ?>);" title="<?php echo $ETI['bt_ayuda']; ?>" value="<?php echo $ETI['bt_ayuda']; ?>" />
</div>
<div class="titulosI">
<?php
echo '<h2>' . $ETI['titulo_251'] . '</h2>';
?>
</div>
</div>
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
<div class="flotante">
<?php
if ($_REQUEST['aure51estado'] == 0) {
?>
<input id="cmdGuardarf" name="cmdGuardarf" type="button" class="btSoloGuardar" onClick="enviaguardar();" value="<?php echo $ETI['bt_guardar']; ?>" />
<?php
}
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
<?php
if ($_REQUEST['paso'] == 0) {
?>
<script language="javascript">
	$().ready(function() {
		//$("#bperiodo").chosen();
	});
</script>
<?php
}
?>
<script language="javascript" src="ac_251.js"></script>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>unad_todas.js?ver=8"></script>
<?php
forma_piedepagina();
