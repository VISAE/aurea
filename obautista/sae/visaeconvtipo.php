<?php
/*
--- © Omar Augusto Bautista - UNAD - 2026 ---
--- omar.bautista@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 3.1.5 viernes, 27 de febrero de 2026
*/
/** Archivo visaeconvtipo.php.
 * Modulo 2934 visa34convtipo.
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
$iCodModulo = 2934;
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
$mensajes_2934 = 'lg/lg_2934_' . $sIdioma . '.php';
if (!file_exists($mensajes_2934)) {
	$mensajes_2934 = 'lg/lg_2934_es.php';
}
require $mensajes_todas;
require $mensajes_2934;
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
$sTituloModulo = $ETI['titulo_2934'];
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
		header('Location:noticia.php?ret=visaeconvtipo.php');
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
$mensajes_2942 = 'lg/lg_2942_' . $sIdioma . '.php';
if (!file_exists($mensajes_2942)) {
	$mensajes_2942 = 'lg/lg_2942_es.php';
}
require $mensajes_2942;
// -- Si esta cargando la pagina por primer vez se revisa si requiere auditar y se manda a hacer un limpiar (paso -1)
if (isset($_REQUEST['paso']) == 0) {
	$_REQUEST['paso'] = -1;
	if ($audita[1]) {
		seg_auditaingreso($iCodModulo, $_SESSION['unad_id_tercero'], $objDB);
	}
}
// -- 2934 visa34convtipo
require 'lib2934.php';
// -- 2942 Configura Anexos
require 'lib2942.php';
$xajax = new xajax();
$xajax->configure('javascript URI', $APP->rutacomun . 'xajax/');
$xajax->register(XAJAX_FUNCTION, 'sesion_abandona_V2');
$xajax->register(XAJAX_FUNCTION, 'sesion_mantenerV4');
$xajax->register(XAJAX_FUNCTION, 'f2934_HtmlTabla');
$xajax->register(XAJAX_FUNCTION, 'f2934_ExisteDato');
$xajax->register(XAJAX_FUNCTION, 'f2942_Guardar');
$xajax->register(XAJAX_FUNCTION, 'f2942_Traer');
$xajax->register(XAJAX_FUNCTION, 'f2942_Eliminar');
$xajax->register(XAJAX_FUNCTION, 'f2942_HtmlTabla');
$xajax->register(XAJAX_FUNCTION, 'f2942_PintarLlaves');
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
if (isset($_REQUEST['paginaf2934']) == 0) {
	$_REQUEST['paginaf2934'] = 1;
}
if (isset($_REQUEST['lppf2934']) == 0) {
	$_REQUEST['lppf2934'] = 20;
}
if (isset($_REQUEST['boculta2934']) == 0) {
	$_REQUEST['boculta2934'] = 0;
}
// -- Inicializar variables de datos.
if (isset($_REQUEST['visa34consec']) == 0) {
	$_REQUEST['visa34consec'] = '';
}
if (isset($_REQUEST['visa34consec_nuevo']) == 0) {
	$_REQUEST['visa34consec_nuevo'] = '';
}
if (isset($_REQUEST['visa34id']) == 0) {
	$_REQUEST['visa34id'] = '';
}
if (isset($_REQUEST['visa34nombre']) == 0) {
	$_REQUEST['visa34nombre'] = '';
}
if (isset($_REQUEST['visa34rolestudiante']) == 0) {
	$_REQUEST['visa34rolestudiante'] = 0;
}
if (isset($_REQUEST['visa34roladministrativo']) == 0) {
	$_REQUEST['visa34roladministrativo'] = 0;
}
if (isset($_REQUEST['visa34rolacademico']) == 0) {
	$_REQUEST['visa34rolacademico'] = 0;
}
if (isset($_REQUEST['visa34rolaspirante']) == 0) {
	$_REQUEST['visa34rolaspirante'] = 0;
}
if (isset($_REQUEST['visa34rolegresado']) == 0) {
	$_REQUEST['visa34rolegresado'] = 0;
}
if (isset($_REQUEST['visa34rolexterno']) == 0) {
	$_REQUEST['visa34rolexterno'] = 0;
}
if (isset($_REQUEST['visa34grupotipologia']) == 0) {
	$_REQUEST['visa34grupotipologia'] = '';
}
if (isset($_REQUEST['visa34activo']) == 0) {
	$_REQUEST['visa34activo'] = 0;
}
if (isset($_REQUEST['visa34aplicazona']) == 0) {
	$_REQUEST['visa34aplicazona'] = 0;
}
if (isset($_REQUEST['visa34aplicacentro']) == 0) {
	$_REQUEST['visa34aplicacentro'] = 0;
}
if (isset($_REQUEST['visa34aplicaescuela']) == 0) {
	$_REQUEST['visa34aplicaescuela'] = 0;
}
if (isset($_REQUEST['visa34aplicaprograma']) == 0) {
	$_REQUEST['visa34aplicaprograma'] = 0;
}
$_REQUEST['visa34consec'] = numeros_validar($_REQUEST['visa34consec']);
$_REQUEST['visa34id'] = numeros_validar($_REQUEST['visa34id']);
$_REQUEST['visa34nombre'] = cadena_Validar($_REQUEST['visa34nombre']);
$_REQUEST['visa34rolestudiante'] = numeros_validar($_REQUEST['visa34rolestudiante']);
$_REQUEST['visa34roladministrativo'] = numeros_validar($_REQUEST['visa34roladministrativo']);
$_REQUEST['visa34rolacademico'] = numeros_validar($_REQUEST['visa34rolacademico']);
$_REQUEST['visa34rolaspirante'] = numeros_validar($_REQUEST['visa34rolaspirante']);
$_REQUEST['visa34rolegresado'] = numeros_validar($_REQUEST['visa34rolegresado']);
$_REQUEST['visa34rolexterno'] = numeros_validar($_REQUEST['visa34rolexterno']);
$_REQUEST['visa34grupotipologia'] = numeros_validar($_REQUEST['visa34grupotipologia']);
$_REQUEST['visa34activo'] = numeros_validar($_REQUEST['visa34activo']);
$_REQUEST['visa34aplicazona'] = numeros_validar($_REQUEST['visa34aplicazona']);
$_REQUEST['visa34aplicacentro'] = numeros_validar($_REQUEST['visa34aplicacentro']);
$_REQUEST['visa34aplicaescuela'] = numeros_validar($_REQUEST['visa34aplicaescuela']);
$_REQUEST['visa34aplicaprograma'] = numeros_validar($_REQUEST['visa34aplicaprograma']);
if ((int)$_REQUEST['paso'] > 0) {
	//Configura Anexos
	if (isset($_REQUEST['paginaf2942']) == 0) {
		$_REQUEST['paginaf2942'] = 1;
	}
	if (isset($_REQUEST['lppf2942']) == 0) {
		$_REQUEST['lppf2942'] = 20;
	}
	if (isset($_REQUEST['boculta2942']) == 0) {
		$_REQUEST['boculta2942'] = 0;
	}
	if (isset($_REQUEST['visa42idtipo']) == 0) {
		$_REQUEST['visa42idtipo'] = '';
	}
	if (isset($_REQUEST['visa42consec']) == 0) {
		$_REQUEST['visa42consec'] = '';
	}
	if (isset($_REQUEST['visa42id']) == 0) {
		$_REQUEST['visa42id'] = '';
	}
	if (isset($_REQUEST['visa42titulo']) == 0) {
		$_REQUEST['visa42titulo'] = '';
	}
	if (isset($_REQUEST['visa42descripcion']) == 0) {
		$_REQUEST['visa42descripcion'] = '';
	}
	if (isset($_REQUEST['visa42activo']) == 0) {
		$_REQUEST['visa42activo'] = 0;
	}
	if (isset($_REQUEST['visa42orden']) == 0) {
		$_REQUEST['visa42orden'] = '';
	}
	if (isset($_REQUEST['visa42obligatorio']) == 0) {
		$_REQUEST['visa42obligatorio'] = 0;
	}
	if (isset($_REQUEST['visa42tipodocumento']) == 0) {
		$_REQUEST['visa42tipodocumento'] = '';
	}
	$_REQUEST['visa42idtipo'] = numeros_validar($_REQUEST['visa42idtipo']);
	$_REQUEST['visa42consec'] = numeros_validar($_REQUEST['visa42consec']);
	$_REQUEST['visa42id'] = numeros_validar($_REQUEST['visa42id']);
	$_REQUEST['visa42titulo'] = cadena_Validar($_REQUEST['visa42titulo']);
	$_REQUEST['visa42descripcion'] = cadena_Validar($_REQUEST['visa42descripcion']);
	$_REQUEST['visa42activo'] = numeros_validar($_REQUEST['visa42activo']);
	$_REQUEST['visa42orden'] = cadena_Validar($_REQUEST['visa42orden']);
	$_REQUEST['visa42obligatorio'] = numeros_validar($_REQUEST['visa42obligatorio']);
	$_REQUEST['visa42tipodocumento'] = numeros_validar($_REQUEST['visa42tipodocumento']);
}
// Espacio para inicializar otras variables
if (isset($_REQUEST['csv_separa']) == 0) {
	$_REQUEST['csv_separa'] = ';';
}
if (isset($_REQUEST['bnombre']) == 0) {
	$_REQUEST['bnombre'] = '';
}
	//Configura Anexos
$_REQUEST['bnombre'] = cadena_Validar($_REQUEST['bnombre']);
	//Configura Anexos
//Si Modifica o Elimina Cargar los campos
if (($_REQUEST['paso'] == 1) || ($_REQUEST['paso'] == 3)) {
	if ($_REQUEST['paso'] == 1) {
		$sSQLcondi = 'visa34consec=' . $_REQUEST['visa34consec'] . '';
	} else {
		$sSQLcondi = 'visa34id=' . $_REQUEST['visa34id'] . '';
	}
	$sSQL = 'SELECT * FROM visa34convtipo WHERE ' . $sSQLcondi;
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$_REQUEST['visa34consec'] = $fila['visa34consec'];
		$_REQUEST['visa34id'] = $fila['visa34id'];
		$_REQUEST['visa34nombre'] = $fila['visa34nombre'];
		$_REQUEST['visa34rolestudiante'] = $fila['visa34rolestudiante'];
		$_REQUEST['visa34roladministrativo'] = $fila['visa34roladministrativo'];
		$_REQUEST['visa34rolacademico'] = $fila['visa34rolacademico'];
		$_REQUEST['visa34rolaspirante'] = $fila['visa34rolaspirante'];
		$_REQUEST['visa34rolegresado'] = $fila['visa34rolegresado'];
		$_REQUEST['visa34rolexterno'] = $fila['visa34rolexterno'];
		$_REQUEST['visa34grupotipologia'] = $fila['visa34grupotipologia'];
		$_REQUEST['visa34activo'] = $fila['visa34activo'];
		$_REQUEST['visa34aplicazona'] = $fila['visa34aplicazona'];
		$_REQUEST['visa34aplicacentro'] = $fila['visa34aplicacentro'];
		$_REQUEST['visa34aplicaescuela'] = $fila['visa34aplicaescuela'];
		$_REQUEST['visa34aplicaprograma'] = $fila['visa34aplicaprograma'];
		$bcargo = true;
		$_REQUEST['paso'] = 2;
		$_REQUEST['boculta2934'] = 0;
		$bLimpiaHijos = true;
	} else {
		$_REQUEST['paso'] = 0;
	}
}
//Insertar o modificar un elemento
if (($_REQUEST['paso'] == 10) || ($_REQUEST['paso'] == 12)) {
	$bMueveScroll = true;
	list($_REQUEST, $sError, $iTipoError, $sDebugGuardar) = f2934_db_GuardarV2b($_REQUEST, $objDB, $bDebug, $idTercero);
	$sDebug = $sDebug . $sDebugGuardar;
	if ($sError == '') {
		$sError = '<b>' . $ETI['msg_itemguardado'] . '</b>';
		$iTipoError = 1;
	}
}
// Cambio de consecutivo.
if ($_REQUEST['paso'] == 93) {
	$_REQUEST['paso'] = 2;
	$_REQUEST['visa34consec_nuevo'] = numeros_validar($_REQUEST['visa34consec_nuevo']);
	if ($_REQUEST['visa34consec_nuevo'] == '') {
		$sError = $ERR['visa34consec'];
	}
	if ($sError == '') {
		list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 8, $idTercero, $objDB);
		if (!$bDevuelve) {
			$sError = $ERR['8'] . ' [Mod ' . $iCodModulo . ']';
		}
	}
	if ($sError == '') {
		//Ver que el consecutivo no exista.
		$sSQL = 'SELECT visa34id FROM visa34convtipo WHERE visa34consec=' . $_REQUEST['visa34consec_nuevo'] . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$sError = 'El consecutivo ' . $_REQUEST['visa34consec_nuevo'] . ' ya existe';
		}
	}
	if ($sError == '') {
		//Aplicar el cambio.
		$sSQL = 'UPDATE visa34convtipo SET visa34consec=' . $_REQUEST['visa34consec_nuevo'] . ' WHERE visa34id=' . $_REQUEST['visa34id'] . '';
		$tabla = $objDB->ejecutasql($sSQL);
		$sDetalle = 'Cambia el consecutivo de ' . $_REQUEST['visa34consec'] . ' a ' . $_REQUEST['visa34consec_nuevo'] . '';
		$_REQUEST['visa34consec'] = $_REQUEST['visa34consec_nuevo'];
		$_REQUEST['visa34consec_nuevo'] = '';
		seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 8, $_REQUEST['visa34id'], $sDetalle, $objDB);
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
		list($sError, $iTipoError, $sDebugElimina) = f2934_db_Eliminar($_REQUEST['visa34id'], $objDB, $bDebug);
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
	$_REQUEST['visa34consec'] = '';
	$_REQUEST['visa34consec_nuevo'] = '';
	$_REQUEST['visa34id'] = '';
	$_REQUEST['visa34nombre'] = '';
	$_REQUEST['visa34rolestudiante'] = 1;
	$_REQUEST['visa34roladministrativo'] = 1;
	$_REQUEST['visa34rolacademico'] = 1;
	$_REQUEST['visa34rolaspirante'] = 1;
	$_REQUEST['visa34rolegresado'] = 1;
	$_REQUEST['visa34rolexterno'] = 1;
	$_REQUEST['visa34grupotipologia'] = '';
	$_REQUEST['visa34activo'] = 1;
	$_REQUEST['visa34aplicazona'] = 1;
	$_REQUEST['visa34aplicacentro'] = 1;
	$_REQUEST['visa34aplicaescuela'] = 1;
	$_REQUEST['visa34aplicaprograma'] = 1;
	$_REQUEST['paso'] = 0;
}
if ($bLimpiaHijos) {
	$_REQUEST['visa42idtipo'] = '';
	$_REQUEST['visa42consec'] = '';
	$_REQUEST['visa42id'] = '';
	$_REQUEST['visa42titulo'] = '';
	$_REQUEST['visa42descripcion'] = '';
	$_REQUEST['visa42activo'] = 1;
	$_REQUEST['visa42orden'] = '';
	$_REQUEST['visa42obligatorio'] = 1;
	$_REQUEST['visa42tipodocumento'] = '';
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
$bEdita2942 = false;
//Permisos adicionales
$seg_5 = 0;
$seg_6 = 0;
$seg_8 = 0;
/*
list($bHayImprimir, $sDebugP, $seg_6) = seg_revisa_permisoV3($iCodModulo, 6, $idTercero, $objDB);
*/
if ((int)$_REQUEST['paso'] != 0) {
	//list($bHayImprimir2, $sDebugP, $seg_5) = seg_revisa_permisoV3($iCodModulo, 5, $idTercero, $objDB);
	$bEdita2942 = true;
	$bConEliminar = true;
	//list($bDevuelve, $sDebugP, $seg_8) = seg_revisa_permisoV3($iCodModulo, 8, $idTercero, $objDB);
}
//DATOS PARA COMPLETAR EL FORMULARIO
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
$objCombos->nuevo('visa34rolestudiante', $_REQUEST['visa34rolestudiante'], true, $ETI['no'], 0);
$objCombos->addItem(1, $ETI['si']);
//$objCombos->addArreglo($avisa34rolestudiante, $ivisa34rolestudiante);
$sSQL = '';
$html_visa34rolestudiante = $objCombos->html($sSQL, $objDB);
$objCombos->nuevo('visa34roladministrativo', $_REQUEST['visa34roladministrativo'], true, $ETI['no'], 0);
$objCombos->addItem(1, $ETI['si']);
//$objCombos->addArreglo($avisa34roladministrativo, $ivisa34roladministrativo);
$sSQL = '';
$html_visa34roladministrativo = $objCombos->html($sSQL, $objDB);
$objCombos->nuevo('visa34rolacademico', $_REQUEST['visa34rolacademico'], true, $ETI['no'], 0);
$objCombos->addItem(1, $ETI['si']);
//$objCombos->addArreglo($avisa34rolacademico, $ivisa34rolacademico);
$sSQL = '';
$html_visa34rolacademico = $objCombos->html($sSQL, $objDB);
$objCombos->nuevo('visa34rolaspirante', $_REQUEST['visa34rolaspirante'], true, $ETI['no'], 0);
$objCombos->addItem(1, $ETI['si']);
//$objCombos->addArreglo($avisa34rolaspirante, $ivisa34rolaspirante);
$sSQL = '';
$html_visa34rolaspirante = $objCombos->html($sSQL, $objDB);
$objCombos->nuevo('visa34rolegresado', $_REQUEST['visa34rolegresado'], true, $ETI['no'], 0);
$objCombos->addItem(1, $ETI['si']);
//$objCombos->addArreglo($avisa34rolegresado, $ivisa34rolegresado);
$sSQL = '';
$html_visa34rolegresado = $objCombos->html($sSQL, $objDB);
$objCombos->nuevo('visa34rolexterno', $_REQUEST['visa34rolexterno'], true, $ETI['no'], 0);
$objCombos->addItem(1, $ETI['si']);
//$objCombos->addArreglo($avisa34rolexterno, $ivisa34rolexterno);
$sSQL = '';
$html_visa34rolexterno = $objCombos->html($sSQL, $objDB);
$objCombos->nuevo('visa34grupotipologia', $_REQUEST['visa34grupotipologia'], true, '{' . $ETI['msg_seleccione'] . '}');
$sSQL = 'SELECT visa46id AS id, visa46nombre AS nombre FROM visa46grupotipologia ORDER BY visa46nombre';
$html_visa34grupotipologia = $objCombos->html($sSQL, $objDB);
$objCombos->nuevo('visa34activo', $_REQUEST['visa34activo'], true, $ETI['no'], 0);
$objCombos->addItem(1, $ETI['si']);
//$objCombos->addArreglo($avisa34activo, $ivisa34activo);
$sSQL = '';
$html_visa34activo = $objCombos->html($sSQL, $objDB);
$objCombos->nuevo('visa34aplicazona', $_REQUEST['visa34aplicazona'], true, $ETI['no'], 0);
$objCombos->addItem(1, $ETI['si']);
//$objCombos->addArreglo($avisa34aplicazona, $ivisa34aplicazona);
$sSQL = '';
$html_visa34aplicazona = $objCombos->html($sSQL, $objDB);
$objCombos->nuevo('visa34aplicacentro', $_REQUEST['visa34aplicacentro'], true, $ETI['no'], 0);
$objCombos->addItem(1, $ETI['si']);
//$objCombos->addArreglo($avisa34aplicacentro, $ivisa34aplicacentro);
$sSQL = '';
$html_visa34aplicacentro = $objCombos->html($sSQL, $objDB);
$objCombos->nuevo('visa34aplicaescuela', $_REQUEST['visa34aplicaescuela'], true, $ETI['no'], 0);
$objCombos->addItem(1, $ETI['si']);
//$objCombos->addArreglo($avisa34aplicaescuela, $ivisa34aplicaescuela);
$sSQL = '';
$html_visa34aplicaescuela = $objCombos->html($sSQL, $objDB);
$objCombos->nuevo('visa34aplicaprograma', $_REQUEST['visa34aplicaprograma'], true, $ETI['no'], 0);
$objCombos->addItem(1, $ETI['si']);
//$objCombos->addArreglo($avisa34aplicaprograma, $ivisa34aplicaprograma);
$sSQL = '';
$html_visa34aplicaprograma = $objCombos->html($sSQL, $objDB);
if ((int)$_REQUEST['paso'] == 0) {
} else {
}
if ($bEdita2942) {
	$objCombos->nuevo('visa42activo', $_REQUEST['visa42activo'], true, $ETI['no'], 0);
	$objCombos->addItem(1, $ETI['si']);
	//$objCombos->addArreglo($avisa42activo, $ivisa42activo);
	$sSQL = '';
	$html_visa42activo = $objCombos->html($sSQL, $objDB);
	$objCombos->nuevo('visa42obligatorio', $_REQUEST['visa42obligatorio'], true, $ETI['no'], 0);
	$objCombos->addItem(1, $ETI['si']);
	//$objCombos->addArreglo($avisa42obligatorio, $ivisa42obligatorio);
	$sSQL = '';
	$html_visa42obligatorio = $objCombos->html($sSQL, $objDB);
	$objCombos->nuevo('visa42tipodocumento', $_REQUEST['visa42tipodocumento'], true, '{' . $ETI['msg_seleccione'] . '}');
	$sSQL = 'SELECT gedo02id AS id, gedo02nombre AS nombre FROM gedo02tipodoc ORDER BY gedo02nombre';
	$html_visa42tipodocumento = $objCombos->html($sSQL, $objDB);
}
//Alistar datos adicionales
$id_rpt = 0;
//$id_rpt=reportes_id(_Identificador_Tipo_Reporte_, $objDB);
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
$aParametros[0] = ''; //$_REQUEST['p1_2934'];
$aParametros[100] = $idTercero;
$aParametros[101] = $_REQUEST['paginaf2934'];
$aParametros[102] = $_REQUEST['lppf2934'];
$aParametros[103] = $_REQUEST['bnombre'];
list($sTabla2934, $sDebugTabla) = f2934_TablaDetalleV2($aParametros, $objDB, $bDebug);
$sDebug = $sDebug . $sDebugTabla;
$sTabla2942 = '';
if ($_REQUEST['paso'] != 0) {
	//Configura Anexos
	$aParametros2942[0] = $_REQUEST['visa34id'];
	$aParametros2942[100] = $idTercero;
	$aParametros2942[101] = $_REQUEST['paginaf2942'];
	$aParametros2942[102] = $_REQUEST['lppf2942'];
	//$aParametros2942[103] = $_REQUEST['bnombre2942'];
	//$aParametros2942[104] = $_REQUEST['blistar2942'];
	list($sTabla2942, $sDebugTabla) = f2942_TablaDetalleV2($aParametros2942, $objDB, $bDebug);
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
			window.document.frmlista.consulta.value = window.document.frmedita.consulta_2934.value;
			window.document.frmlista.titulos.value = window.document.frmedita.titulos_2934.value;
			window.document.frmlista.nombrearchivo.value = 'Tipo de convocatoria';
			window.document.frmlista.submit();
		} else {
			ModalMensaje("<?php echo $ERR['6']; ?>");
		}
	}

	function asignarvariables() {
		window.document.frmimpp.v3.value = window.document.frmedita.bnombre.value;
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
			window.document.frmimpp.action = 'e2934_ss.php';
			window.document.frmimpp.submit();
		} else {
			ModalMensaje(sError);
		}
	}

	function imprimep() {
		if (window.document.frmedita.seg_5.value == 1) {
			asignarvariables();
			window.document.frmimpp.action = 'p2934.php';
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
		datos[1] = window.document.frmedita.visa34consec.value;
		if ((datos[1] != '')) {
			xajax_f2934_ExisteDato(datos);
		}
	}

	function cargadato(llave1) {
		window.document.frmedita.visa34consec.value = String(llave1);
		window.document.frmedita.paso.value = 1;
		window.document.frmedita.submit();
	}

	function cargaridf2934(llave1) {
		window.document.frmedita.visa34id.value = String(llave1);
		window.document.frmedita.paso.value = 3;
		window.document.frmedita.submit();
	}

	function paginarf2934() {
		let params = new Array();
		params[99] = window.document.frmedita.debug.value;
		params[100] = <?php echo $idTercero; ?>;
		params[101] = window.document.frmedita.paginaf2934.value;
		params[102] = window.document.frmedita.lppf2934.value;
		params[103] = window.document.frmedita.bnombre.value;
		document.getElementById('div_f2934detalle').innerHTML = '<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf2934" name="paginaf2934" type="hidden" value="' + params[101] + '" /><input id="lppf2934" name="lppf2934" type="hidden" value="' + params[102] + '" />';
		xajax_f2934_HtmlTabla(params);
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
		document.getElementById("visa34consec").focus();
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
<script language="javascript" src="jsi/js2942.js"></script>
<?php
}
?>
<form id="frmimpp" name="frmimpp" method="post" action="p2934.php" target="_blank" style="display:none">
<input id="r" name="r" type="hidden" value="2934" />
<input id="id2934" name="id2934" type="hidden" value="<?php echo $_REQUEST['visa34id']; ?>" />
<input id="v3" name="v3" type="hidden" value="" />
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
echo $objForma->htmlExpande(2934, $_REQUEST['boculta2934'], $ETI['bt_mostrar'], $ETI['bt_ocultar']);
$sEstiloDiv = '';
if ($_REQUEST['boculta2934'] != 0) {
	$sEstiloDiv = ' style="display:none;"';
}
?>
</div>
<div id="div_p2934"<?php echo $sEstiloDiv; ?>>
<?php
}
//Mostrar formulario para editar
?>
<label class="Label130"<?php echo $sOcultaConsec; ?>>
<?php
echo $ETI['visa34consec'];
?>
</label>
<label class="Label130"<?php echo $sOcultaConsec; ?>>
<?php
if ($_REQUEST['paso'] != 2) {
?>
<input id="visa34consec" name="visa34consec" type="text" value="<?php echo $_REQUEST['visa34consec']; ?>" onchange="RevisaLlave()" class="cuatro" />
<?php
} else {
	echo html_oculto('visa34consec', $_REQUEST['visa34consec'], formato_numero($_REQUEST['visa34consec']));
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
echo $ETI['visa34id'];
?>
</label>
<label class="Label60"<?php echo $sOcultaId; ?>>
<?php
	echo html_oculto('visa34id', $_REQUEST['visa34id'], formato_numero($_REQUEST['visa34id']));
?>
</label>
<label class="L">
<?php
echo $ETI['visa34nombre'];
?>

<input id="visa34nombre" name="visa34nombre" type="text" value="<?php echo $_REQUEST['visa34nombre']; ?>" maxlength="100" class="L" placeholder="<?php echo $ETI['ing_campo'] . $ETI['visa34nombre']; ?>" />
</label>
<label class="Label130">
<?php
echo $ETI['visa34rolestudiante'];
?>
</label>
<label>
<?php
echo $html_visa34rolestudiante;
?>
</label>
<label class="Label130">
<?php
echo $ETI['visa34roladministrativo'];
?>
</label>
<label>
<?php
echo $html_visa34roladministrativo;
?>
</label>
<label class="Label130">
<?php
echo $ETI['visa34rolacademico'];
?>
</label>
<label>
<?php
echo $html_visa34rolacademico;
?>
</label>
<label class="Label130">
<?php
echo $ETI['visa34rolaspirante'];
?>
</label>
<label>
<?php
echo $html_visa34rolaspirante;
?>
</label>
<label class="Label130">
<?php
echo $ETI['visa34rolegresado'];
?>
</label>
<label>
<?php
echo $html_visa34rolegresado;
?>
</label>
<label class="Label130">
<?php
echo $ETI['visa34rolexterno'];
?>
</label>
<label>
<?php
echo $html_visa34rolexterno;
?>
</label>
<label class="Label130">
<?php
echo $ETI['visa34grupotipologia'];
?>
</label>
<label>
<?php
echo $html_visa34grupotipologia;
?>
</label>
<label class="Label130">
<?php
echo $ETI['visa34activo'];
?>
</label>
<label>
<?php
echo $html_visa34activo;
?>
</label>
<label class="Label130">
<?php
echo $ETI['visa34aplicazona'];
?>
</label>
<label>
<?php
echo $html_visa34aplicazona;
?>
</label>
<label class="Label130">
<?php
echo $ETI['visa34aplicacentro'];
?>
</label>
<label>
<?php
echo $html_visa34aplicacentro;
?>
</label>
<label class="Label130">
<?php
echo $ETI['visa34aplicaescuela'];
?>
</label>
<label>
<?php
echo $html_visa34aplicaescuela;
?>
</label>
<label class="Label130">
<?php
echo $ETI['visa34aplicaprograma'];
?>
</label>
<label>
<?php
echo $html_visa34aplicaprograma;
?>
</label>
<?php
// -- Inicia Grupo campos 2942 Configura Anexos
?>
<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo $ETI['titulo_2942'];
?>
</label>
<?php
if ($_REQUEST['paso'] == 2) {
	if ($bEdita2942) {
?>
<input id="boculta2942" name="boculta2942" type="hidden" value="<?php echo $_REQUEST['boculta2942']; ?>" />
<div class="ir_derecha"<?php echo $sAnchoExpandeContrae; ?>>
<?php
if (false) {
?>
<label class="Label30">
<input id="btexcel2942" name="btexcel2942" type="button" value="Exportar" class="btMiniExcel" onclick="imprime2942();" title="Exportar" />
</label>
<?php
}
echo $objForma->htmlExpande(2942, $_REQUEST['boculta2942'], $ETI['bt_mostrar'], $ETI['bt_ocultar']);
$sEstiloDiv = '';
if ($_REQUEST['boculta2942'] != 0) {
	$sEstiloDiv = ' style="display:none;"';
}
?>
</div>
<div class="salto1px"></div>
<div id="div_p2942"<?php echo $sEstiloDiv; ?>>
<label class="Label130"<?php echo $sOcultaConsec; ?>>
<?php
echo $ETI['visa42consec'];
?>
</label>
<label class="Label130"<?php echo $sOcultaConsec; ?>>
<div id="div_visa42consec">
<?php
if ((int)$_REQUEST['visa42id'] == 0) {
?>
<input id="visa42consec" name="visa42consec" type="text" value="<?php echo $_REQUEST['visa42consec']; ?>" onchange="revisaf2942()" class="cuatro" />
<?php
} else {
	echo html_oculto('visa42consec', $_REQUEST['visa42consec'], formato_numero($_REQUEST['visa42consec']));
}
?>
</div>
</label>
<label class="Label60"<?php echo $sOcultaId; ?>>
<?php
echo $ETI['visa42id'];
?>
</label>
<label class="Label60"<?php echo $sOcultaId; ?>>
<div id="div_visa42id">
<?php
	echo html_oculto('visa42id', $_REQUEST['visa42id'], formato_numero($_REQUEST['visa42id']));
?>
</div>
</label>
<label class="Label130">
<?php
echo $ETI['visa42titulo'];
?>
</label>
<label>

<input id="visa42titulo" name="visa42titulo" type="text" value="<?php echo $_REQUEST['visa42titulo']; ?>" maxlength="50" placeholder="<?php echo $ETI['ing_campo'] . $ETI['visa42titulo']; ?>" />
</label>
<label class="L">
<?php
echo $ETI['visa42descripcion'];
?>

<input id="visa42descripcion" name="visa42descripcion" type="text" value="<?php echo $_REQUEST['visa42descripcion']; ?>" maxlength="250" class="L" placeholder="<?php echo $ETI['ing_campo'] . $ETI['visa42descripcion']; ?>" />
</label>
<label class="Label130">
<?php
echo $ETI['visa42activo'];
?>
</label>
<label>
<?php
echo $html_visa42activo;
?>
</label>
<label class="Label130">
<?php
echo $ETI['visa42orden'];
?>
</label>
<label>

<input id="visa42orden" name="visa42orden" type="text" value="<?php echo $_REQUEST['visa42orden']; ?>" placeholder="<?php echo $ETI['ing_campo'] . $ETI['visa42orden']; ?>" />
</label>
<label class="Label130">
<?php
echo $ETI['visa42obligatorio'];
?>
</label>
<label>
<?php
echo $html_visa42obligatorio;
?>
</label>
<label class="Label130">
<?php
echo $ETI['visa42tipodocumento'];
?>
</label>
<label>
<?php
echo $html_visa42tipodocumento;
?>
</label>
<div class="salto1px"></div>
<label class="Label130">&nbsp;</label>
<?php
$sEstiloElimina = 'display:none;';
if ((int)$_REQUEST['visa42id'] != 0) {
	$sEstiloElimina = 'inline-block;';
}
echo $objForma->htmlBotonSolo('bguarda2942', 'btMiniGuardar', 'guardaf2942()', $ETI['bt_mini_guardar_2942'], 30);
echo $objForma->htmlBotonSolo('blimpia2942', 'btMiniLimpiar', 'limpiaf2942()', $ETI['bt_mini_limpiar_2942'], 30);
echo $objForma->htmlBotonSolo('belimina2942', 'btMiniEliminar', 'eliminaf2942()', $ETI['bt_mini_eliminar_2942'], 30, $sEstiloElimina);
//Este es el cierre del div_p2942
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
<input id="bnombre2942" name="bnombre2942" type="text" value="<?php echo $_REQUEST['bnombre2942']; ?>" onchange="paginarf2942()" />
</label>
<label class="Label130">
<?php
echo $ETI['msg_Listar'];
?>
</label>
<label>
<?php
echo $html_blistar2942;
?>
</label>
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
<?php
}
?>
<div id="div_f2942detalle">
<?php
echo $sTabla2942;
?>
</div>
<?php
}
?>
<div class="salto1px"></div>
</div>
<?php
// -- Termina Grupo campos 2942 Configura Anexos
?>
<?php
if (false) {
	//Ejemplo de boton de ayuda
	//echo html_BotonAyuda('NombreCampo');
	//echo html_DivAyudaLocal('NombreCampo');
}
if ($bConExpande) {
	//Este es el cierre del div_p2934
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
echo $ETI['msg_bnombre'];
?>
</label>
<label>
<input id="bnombre" name="bnombre" type="text" value="<?php echo $_REQUEST['bnombre']; ?>" onchange="paginarf2934()" autocomplete="off" />
</label>
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
<?php
echo ' ' . $csv_separa;
?>
<div class="salto1px"></div>
<div id="div_f2934detalle">
<?php
echo $sTabla2934;
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
echo $ETI['msg_visa34consec'];
?>
</label>
<label class="Label90">
<?php
echo '<b>' . $_REQUEST['visa34consec'] . '</b>';
?>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['msg_visa34consec_nuevo'];
// onchange="RevisaConsec()"
?>
</label>
<label class="Label90">
<input id="visa34consec_nuevo" name="visa34consec_nuevo" type="text" value="<?php echo $_REQUEST['visa34consec_nuevo']; ?>" class="cuatro" />
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
<input id="titulo_2934" name="titulo_2934" type="hidden" value="<?php echo $sTituloModulo; ?>" />
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
		$("#visa34grupotipologia").chosen({width:"100%"});
	});
</script>
<?php
}
?>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>unad_todas2024v2.js"></script>
<?php
forma_piedepagina();

