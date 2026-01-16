<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2018 - 2025 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.22.3 lunes, 27 de agosto de 2018
--- Modelo Versión 3.0.17 miercoles, 17 de diciembre de 2025
*/

/** Archivo cararptresumen.php.
 * Modulo 2319 cara19rpttercero.
 * @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
 * @param debug = 1  (Opcional), bandera para indicar si se generan datos de depuración
 * @date martes, 16 de septiembre de 2025
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
require $APP->rutacomun . 'libhtml.php';
require $APP->rutacomun . 'xajax/xajax_core/xajax.inc.php';
require $APP->rutacomun . 'unad_xajax.php';
require $APP->rutacomun . 'libdatos.php';
//No conformidades.
require $APP->rutacomun . 'libs/cls203.php';
if (($bPeticionXAJAX) && ($_SESSION['unad_id_tercero'] == 0)) {
	// viene por xajax.
	$xajax = new xajax();
	$xajax->configure('javascript URI', $APP->rutacomun . 'xajax/');
	$xajax->register(XAJAX_FUNCTION, 'sesion_abandona_V2');
	$xajax->processRequest();
	die();
}
$iConsecutivoMenu = 1;
$iMinVerDB = 8963;
$iCodModulo = 2319;
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
$mensajes_2204 = $APP->rutacomun . 'lg/lg_2204_' . $sIdioma . '.php';
if (!file_exists($mensajes_2204)) {
	$mensajes_2204 = $APP->rutacomun . 'lg/lg_2204_es.php';
}
$mensajes_2319 = 'lg/lg_2319_' . $sIdioma . '.php';
if (!file_exists($mensajes_2319)) {
	$mensajes_2319 = 'lg/lg_2319_es.php';
}
$mensajes_193 = $APP->rutacomun . 'lg/lg_193_' . $sIdioma . '.php';
if (!file_exists($mensajes_193)) {
	$mensajes_193 = $APP->rutacomun . 'lg/lg_193_es.php';
}
require $mensajes_todas;
require $mensajes_2204;
require $mensajes_2319;
require $mensajes_193;
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
$sTituloModulo = $ETI['titulo_2319'];
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
		header('Location:noticia.php?ret=cararptresumen.php');
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
// -- 2301 la prueba.
require $APP->rutacomun . 'lib2301.php';
// -- 2319 cara19rpttercero
require 'lib2319.php';
// -- 2204 Actas de matricula.
require $APP->rutacomun . 'lib2204.php';
// -- 193 unad93rastros
require $APP->rutacomun . 'lib193.php';
$xajax = new xajax();
$xajax->configure('javascript URI', $APP->rutacomun . 'xajax/');
$xajax->register(XAJAX_FUNCTION, 'unad11_Mostrar_v2');
$xajax->register(XAJAX_FUNCTION, 'unad11_TraerXid');
$xajax->register(XAJAX_FUNCTION, 'sesion_abandona_V2');
$xajax->register(XAJAX_FUNCTION, 'sesion_mantener');
$xajax->register(XAJAX_FUNCTION, 'f2204_HtmlTabla');
$xajax->register(XAJAX_FUNCTION, 'f2318_HtmlTabla');
$xajax->register(XAJAX_FUNCTION, 'f2319_HtmlTabla');
$xajax->register(XAJAX_FUNCTION, 'f2319_Busquedas');
$xajax->register(XAJAX_FUNCTION, 'f2319_HtmlBusqueda');
$xajax->register(XAJAX_FUNCTION, 'f2320_HtmlTabla');
$xajax->register(XAJAX_FUNCTION, 'f193_HtmlTabla');
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
if (isset($_REQUEST['paginaf2204']) == 0) {
	$_REQUEST['paginaf2204'] = 1;
}
if (isset($_REQUEST['lppf2204']) == 0) {
	$_REQUEST['lppf2204'] = 20;
}
if (isset($_REQUEST['boculta2204']) == 0) {
	$_REQUEST['boculta2204'] = 0;
}
if (isset($_REQUEST['paginaf2319']) == 0) {
	$_REQUEST['paginaf2319'] = 1;
}
if (isset($_REQUEST['lppf2319']) == 0) {
	$_REQUEST['lppf2319'] = 20;
}
if (isset($_REQUEST['boculta2319']) == 0) {
	$_REQUEST['boculta2319'] = 0;
}
if (isset($_REQUEST['paginaf2320']) == 0) {
	$_REQUEST['paginaf2320'] = 1;
}
if (isset($_REQUEST['lppf2320']) == 0) {
	$_REQUEST['lppf2320'] = 20;
}
if (isset($_REQUEST['paginaf193']) == 0) {
	$_REQUEST['paginaf193'] = 1;
}
if (isset($_REQUEST['lppf193']) == 0) {
	$_REQUEST['lppf193'] = 20;
}
if (isset($_REQUEST['boculta193']) == 0) {
	$_REQUEST['boculta193'] = 0;
}
// -- Inicializar variables de datos.
if (isset($_REQUEST['caraidtercero']) == 0) {
	$_REQUEST['caraidtercero'] = 0;
} // {$_SESSION['unad_id_tercero'];}
if (isset($_REQUEST['caraidtercero_td']) == 0) {
	$_REQUEST['caraidtercero_td'] = $APP->tipo_doc;
}
if (isset($_REQUEST['caraidtercero_doc']) == 0) {
	$_REQUEST['caraidtercero_doc'] = '';
}
if (isset($_REQUEST['unad93fecha']) == 0) {
	$_REQUEST['unad93fecha'] = fecha_armar(1);
}
if (isset($_REQUEST['unad93fechfin']) == 0) {
	$_REQUEST['unad93fechfin'] = fecha_hoy();
}
$_REQUEST['caraidtercero'] = numeros_validar($_REQUEST['caraidtercero']);
$_REQUEST['caraidtercero_td'] = cadena_Validar($_REQUEST['caraidtercero_td']);
$_REQUEST['caraidtercero_doc'] = cadena_Validar($_REQUEST['caraidtercero_doc']);
$_REQUEST['unad93fecha'] = cadena_Validar($_REQUEST['unad93fecha']);
$_REQUEST['unad93fechfin'] = cadena_Validar($_REQUEST['unad93fechfin']);
// Espacio para inicializar otras variables
if (isset($_REQUEST['csv_separa']) == 0) {
	$_REQUEST['csv_separa'] = ';';
}
if (isset($_REQUEST['bnombre']) == 0) {
	$_REQUEST['bnombre'] = '';
}
if (isset($_REQUEST['bperiodo']) == 0) {
	$_REQUEST['bperiodo'] = '';
}
//if (isset($_REQUEST['blistar'])==0){$_REQUEST['blistar']='';}
if ($_REQUEST['paso'] == 21) {
	$_REQUEST['paso'] = 21;
	list($sErrorP, $sDebugP) = f2301_AjustarTipoEncuesta($_REQUEST['cara01id'], $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugP;
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
$resultado = '';
if ($_REQUEST['paso'] == 23) {
	$_REQUEST['paso'] = 2;
	if ($_REQUEST['caraidtercero'] == 0) {
		$sError = 'No se ha seleccionado un usuario';
	}
	if ($sError == '') {
		if ($_REQUEST['bperiodo'] == '') {
			$sError = 'No ha seleccionado el periodo de matricula';
		}
	}
	if ($sError == '') {
		require $APP->rutacomun . 'libcore.php';
		require $APP->rutacomun . 'lib2216.php';
		list($sError, $sDebugS) = f2216_TraerMatricula($_REQUEST['bperiodo'], $_REQUEST['caraidtercero_doc'], $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugS;
		$iTipoError = 2;
	}
}
//Procesar la matricula.
if ($_REQUEST['paso'] == 24) {
	$_REQUEST['paso'] = 2;
	// -- 2216 core16actamatricula
	require $APP->rutacomun . 'lib2216.php';
	$objNoConformidad = new clsT203(2216);
	list($core16procesado, $core16numcursos, $sError, $sDebugP) = f2216_ProcesarMatricula($_REQUEST['core16id'], $objDB, $objNoConformidad, $bDebug);
	$sDebug = $sDebug . $sDebugP;
}
if ($_REQUEST['paso'] == -1) {
	$_REQUEST['paso'] = 0;
	if (false) {
		$_REQUEST['caraidtercero'] = 0;
		$_REQUEST['caraidtercero_td'] = $APP->tipo_doc;
		$_REQUEST['caraidtercero_doc'] = '';
	}
}
//AQUI SE DEBEN CARGAR TODOS LOS DATOS QUE LA FORMA NECESITE.
$bPuedeGuardar = false;
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
$seg_8 = 0;
list($bHayImprimir, $sDebugP, $seg_6) = seg_revisa_permisoV3($iCodModulo, 6, $idTercero, $objDB);
if ((int)$_REQUEST['paso'] != 0) {
	list($bHayImprimir2, $sDebugP, $seg_5) = seg_revisa_permisoV3($iCodModulo, 5, $idTercero, $objDB);
	//$bConEliminar = true;
	//list($bDevuelve, $sDebugP, $seg_8) = seg_revisa_permisoV3($iCodModulo, 8, $idTercero, $objDB);
}
//DATOS PARA COMPLETAR EL FORMULARIO
//Crear los controles que requieran llamado a base de datos
$objCombos = new clsHtmlCombos('n');
$objForma = new clsHtmlForma($iPiel);
if ($bDebug) {
	$sDebug = $sDebug . fecha_microtiempo() . ' Tipo doc ' . $_REQUEST['caraidtercero_td'] . ' ' . $APP->tipo_doc . '';
}
list($caraidtercero_rs, $_REQUEST['caraidtercero'], $_REQUEST['caraidtercero_td'], $_REQUEST['caraidtercero_doc']) = html_tercero($_REQUEST['caraidtercero_td'], $_REQUEST['caraidtercero_doc'], $_REQUEST['caraidtercero'], 0, $objDB);
if ((int)$_REQUEST['paso'] == 0) {
} else {
}
if ($bDebug) {
	$sDebug = $sDebug . fecha_microtiempo() . ' Tipo doc despues ' . $_REQUEST['caraidtercero_td'] . ' ' . $APP->tipo_doc . '';
}
//Alistar datos adicionales
$id_rpt = 0;
//$id_rpt=reportes_id(_Identificador_Tipo_Reporte_, $objDB);
/*
$objCombos->nuevo('blistar', $_REQUEST['blistar'], true, '{'.$ETI['msg_todos'].'}');
$objCombos->sAccion='paginarf2319()';
$html_blistar=$objCombos->html('', $objDB);
//$html_blistar=$objCombos->comboSistema(2319, 1, $objDB, 'paginarf2319()');
*/
$objCombos->nuevo('bperiodo', $_REQUEST['bperiodo'], true, '{' . $ETI['msg_todos'] . '}');
$sSQL = f146_ConsultaCombo('exte02vigente="S"');
$html_bperiodo = $objCombos->html($sSQL, $objDB);
//Permisos adicionales
$seg_5 = 0;
$seg_6 = 0;
if (seg_revisa_permiso($iCodModulo, 6, $objDB)) {
	$seg_6 = 1;
}
if ($seg_6 == 1) {
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
$iModeloReporte = 2319;
$html_iFormatoImprime = '<input id="iformatoimprime" name="iformatoimprime" type="hidden" value="0" />
';
if (seg_revisa_permiso($iCodModulo, 5, $objDB)) {
	$seg_5 = 1;
}
//Datos generales
$aParametros[103] = $_REQUEST['caraidtercero'];
list($sTabla2318, $sDebugTabla) = f2318_TablaDetalleV2($aParametros, $objDB, $bDebug);
$sDebug = $sDebug . $sDebugTabla;
//Matricula
$aParametros2204[100] = $_REQUEST['caraidtercero'];
$aParametros2204[101] = $_REQUEST['paginaf2204'];
$aParametros2204[102] = $_REQUEST['lppf2204'];
list($sTabla2204, $sDebugTabla) = f2204_TablaDetalleV2($aParametros2204, $objDB, $bDebug);
$sDebug = $sDebug . $sDebugTabla;
//Cargar las tablas de datos
$aParametros[0] = ''; //$_REQUEST['p1_2319'];
$aParametros[101] = $_REQUEST['paginaf2319'];
$aParametros[102] = $_REQUEST['lppf2319'];
$aParametros[103] = $_REQUEST['caraidtercero'];
//$aParametros[104]=$_REQUEST['blistar'];
list($sTabla2319, $sDebugTabla) = f2319_TablaDetalleV2($aParametros, $objDB, $bDebug);
$sDebug = $sDebug . $sDebugTabla;
$aParametros[0] = ''; //$_REQUEST['p1_2319'];
$aParametros[101] = $_REQUEST['paginaf2320'];
$aParametros[102] = $_REQUEST['lppf2320'];
$aParametros[103] = $_REQUEST['caraidtercero'];
//$aParametros[104]=$_REQUEST['blistar'];
list($sTabla2320, $sDebugTabla) = f2320_TablaDetalleV2($aParametros, $objDB, $bDebug);
$sDebug = $sDebug . $sDebugTabla;
$aParametros[0] = ''; //$_REQUEST['p1_193'];
$aParametros[101] = $_REQUEST['paginaf193'];
$aParametros[102] = $_REQUEST['lppf193'];
$aParametros[103] = $_REQUEST['caraidtercero'];
$aParametros[104] = $_REQUEST['unad93fecha'];
$aParametros[105] = $_REQUEST['unad93fechfin'];
$aParametros[106] = 1;
list($sTabla193, $sDebugTabla) = f193_TablaDetalleV2($aParametros, $objDB, $bDebug);
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
		document.getElementById('div_sector97').style.display = 'none';
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
				params[4] = 'CargarInfo';
				params[5] = 'CargarInfo';
			}
			xajax_unad11_Mostrar_v2(params);
		} else {
			document.getElementById(idcampo).value = 0;
			document.getElementById('div_' + idcampo).innerHTML = '';
			CargarInfo();
		}
	}

	function ter_traerxid(idcampo, vrcampo) {
		var params = new Array();
		params[0] = vrcampo;
		params[1] = idcampo;
		if (params[0] != 0) {
			params[4] = 'CargarInfo';
			params[5] = 'CargarInfo';
			xajax_unad11_TraerXid(params);
		}
	}

	function imprimelista() {
		if (window.document.frmedita.seg_6.value == 1) {
			window.document.frmlista.consulta.value = window.document.frmedita.consulta_2319.value;
			window.document.frmlista.titulos.value = window.document.frmedita.titulos_2319.value;
			window.document.frmlista.nombrearchivo.value = 'Resumen individual';
			window.document.frmlista.submit();
		} else {
			window.alert("<?php echo $ERR['6']; ?>");
		}
	}

	function asignarvariables() {
		//window.document.frmimpp.v3.value=window.document.frmedita.bnombre.value;
		//window.document.frmimpp.v4.value=window.document.frmedita.bcodigo.value;
		//window.document.frmimpp.separa.value=window.document.frmedita.csv_separa.value.trim();
	}

	function imprimeexcel() {
		if (window.document.frmedita.seg_6.value == 1) {
			asignarvariables();
			window.document.frmimpp.action = 'e2319.php';
			window.document.frmimpp.submit();
		} else {
			window.alert("<?php echo $ERR['6']; ?>");
		}
	}

	function imprimep() {
		if (window.document.frmedita.seg_5.value == 1) {
			asignarvariables();
			window.document.frmimpp.action = 'p2319.php';
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

	function CargarInfo() {
		paginarf2318();
		paginarf2319();
		paginarf2320();
		paginarf2204();
		paginarf193();
	}

	function paginarf2318() {
		var params = new Array();
		params[99] = window.document.frmedita.debug.value;
		params[103] = window.document.frmedita.caraidtercero.value;
		xajax_f2318_HtmlTabla(params);
	}

	function paginarf2204() {
		var params = new Array();
		params[99] = window.document.frmedita.debug.value;
		params[100] = window.document.frmedita.caraidtercero.value;
		params[101] = window.document.frmedita.paginaf2204.value;
		params[102] = window.document.frmedita.lppf2204.value;
		//document.getElementById('div_f2204detalle').innerHTML='<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf2204" name="paginaf2204" type="hidden" value="'+params[101]+'" /><input id="lppf2204" name="lppf2204" type="hidden" value="'+params[102]+'" />';
		xajax_f2204_HtmlTabla(params);
	}

	function paginarf2319() {
		var params = new Array();
		params[99] = window.document.frmedita.debug.value;
		params[101] = window.document.frmedita.paginaf2319.value;
		params[102] = window.document.frmedita.lppf2319.value;
		params[103] = window.document.frmedita.caraidtercero.value;
		xajax_f2319_HtmlTabla(params);
	}

	function paginarf2320() {
		var params = new Array();
		params[99] = window.document.frmedita.debug.value;
		params[101] = window.document.frmedita.paginaf2320.value;
		params[102] = window.document.frmedita.lppf2320.value;
		params[103] = window.document.frmedita.caraidtercero.value;
		xajax_f2320_HtmlTabla(params);
	}

	function paginarf193() {
		var params = new Array();
		params[99] = window.document.frmedita.debug.value;
		params[101] = window.document.frmedita.paginaf193.value;
		params[102] = window.document.frmedita.lppf193.value;
		params[103] = window.document.frmedita.caraidtercero.value;
		params[104] = window.document.frmedita.unad93fecha.value;
		params[105] = window.document.frmedita.unad93fechfin.value;
		params[106] = 1;
		document.getElementById('div_f193detalle').innerHTML = '<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf193" name="paginaf193" type="hidden" value="' + params[101] + '" /><input id="lppf193" name="lppf193" type="hidden" value="' + params[102] + '" />';
		xajax_f193_HtmlTabla(params);
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
		document.getElementById("caraidtercero").focus();
	}

	function buscarV2016(sCampo) {
		window.document.frmedita.iscroll.value = window.pageYOffset;
		expandesector(98);
		window.document.frmedita.scampobusca.value = sCampo;
		var params = new Array();
		params[1] = sCampo;
		//params[2]=window.document.frmedita.iagno.value;
		//params[3]=window.document.frmedita.itipo.value;
		xajax_f2319_Busquedas(params);
	}

	function Devuelve(sValor) {
		var sCampo = window.document.frmedita.scampobusca.value;
		if (sCampo == 'caraidtercero') {
			ter_traerxid('caraidtercero', sValor);
		}
		retornacontrol();
	}

	function mantener_sesion() {
		xajax_sesion_mantener();
	}
	setInterval('xajax_sesion_abandona_V2();', 60000);

	function AyudaLocal(sCampo) {
		var divAyuda = document.getElementById('div_ayuda_' + sCampo);
		if (typeof divAyuda === 'undefined') {} else {
			verboton('cmdAyuda_' + sCampo, 'none');
			var sMensaje = 'Lo que quiera decir.';
			//if (sCampo=='sNombreCampo'){sMensaje='Mensaje para otro campo.';}
			divAyuda.innerHTML = sMensaje;
			divAyuda.style.display = 'block';
		}
	}

	function cierraDiv96(ref) {
		var sRetorna = window.document.frmedita.div96v2.value;
		MensajeAlarmaV2('', 0);
		retornacontrol();
	}

	function actualizarpreg(idReg) {
		window.document.frmedita.iscroll.value = window.pageYOffset;
		expandesector(98);
		window.document.frmedita.cara01id.value = idReg;
		window.document.frmedita.paso.value = 22;
		window.document.frmedita.submit();
	}

	function verencuesta(id01) {
		window.document.frmencuesta.cara01id.value = id01;
		window.document.frmencuesta.paso.value = 3;
		window.document.frmencuesta.submit();
	}

	function revmatricula() {
		window.document.frmedita.iscroll.value = window.pageYOffset;
		expandesector(98);
		window.document.frmedita.paso.value = 23;
		window.document.frmedita.submit();
	}

	function procesarmatricula(idReg) {
		window.document.frmedita.iscroll.value = window.pageYOffset;
		expandesector(98);
		window.document.frmedita.cara16id.value = idReg;
		window.document.frmedita.paso.value = 24;
		window.document.frmedita.submit();
	}
</script>
<form id="frmencuesta" name="frmencuesta" method="post" action="caracterizacion.php" target="_blank" style="display:none">
<input id="paso" name="paso" type="hidden" value="" />
<input id="cara01id" name="cara01id" type="hidden" value="" />
</form>
<form id="frmimpp" name="frmimpp" method="post" action="p2319.php" target="_blank" style="display:none">
<input id="r" name="r" type="hidden" value="2319" />
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
<input id="cara01id" name="cara01id" type="hidden" value="" />
<input id="cara16id" name="cara16id" type="hidden" value="" />
<div id="div_sector1">
<?php
if ($bBloqueTitulo) {
?>
<div class="titulos">
<div class="titulosD">
<input id="cmdAyuda" name="cmdAyuda" type="button" class="btUpAyuda" onclick="muestraayuda(<?php echo $APP->idsistema . ', ' . $iCodModulo; ?>);" title="<?php echo $ETI['bt_ayuda']; ?>" value="<?php echo $ETI['bt_ayuda']; ?>" />
<?php
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
//Div para ocultar
$bconexpande = false;
if ($bconexpande) {
?>
<div class="ir_derecha"<?php echo $sAnchoExpandeContrae; ?>>
<?php
echo $objForma->htmlExpande(2319, $_REQUEST['boculta2319'], $ETI['bt_mostrar'], $ETI['bt_ocultar']);
$sEstiloDiv = '';
if ($_REQUEST['boculta2319'] != 0) {
	$sEstiloDiv = ' style="display:none;"';
}
?>
</div>
<div id="div_p2319"<?php echo $sEstiloDiv; ?>>
<?php
	}
//Mostrar formulario para editar
?>
<div class="salto1px"></div>
<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo $ETI['caraidtercero'];
?>
</label>
<div class="salto1px"></div>
<input id="caraidtercero" name="caraidtercero" type="hidden" value="<?php echo $_REQUEST['caraidtercero']; ?>"/>
<div id="div_caraidtercero_llaves">
<?php
$bOculto=false;
echo html_DivTerceroV2('caraidtercero', $_REQUEST['caraidtercero_td'], $_REQUEST['caraidtercero_doc'], $bOculto, 1, $ETI['ing_doc']);
?>
</div>
<div class="salto1px"></div>
<div id="div_caraidtercero" class="L"><?php echo $caraidtercero_rs; ?></div>
<div class="salto1px"></div>
</div>

<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo $ETI['msg_campus'];
?>
</label>
<div class="salto1px"></div>
<div id="div_f2318detalle">
<?php
echo $sTabla2318;
?>
</div>
<div class="salto1px"></div>
</div>

<?php
if (false){
	//Ejemplo de boton de ayuda
	//echo html_BotonAyuda('NombreCampo');
	//echo html_DivAyudaLocal('NombreCampo');
	}
if ($bconexpande){
	//Este es el cierre del div_p2319
?>
<div class="salto1px"></div>
</div>
<?php
	}
//Mostrar el contenido de la tabla
?>
<?php
echo ' '.$csv_separa;
?>

<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo $ETI['msg_matricula'];
?>
</label>
<div class="salto1px"></div>
<label class="Label160">
Periodo matricula
</label>
<label class="Label600">
<?php
echo $html_bperiodo;
?>
</label>
<div class="salto1px"></div>
<label class="Label160">&nbsp;</label>
<label class="Label130">
<input id="cmdRevMatricula" name="cmdRevMatricula" type="button" value="Verificar Matricula" class="BotonAzul160" onclick="revmatricula()" title="Revisar Matricula en Registro y Control" />
</label>
<div class="salto1px"></div>
<div id="div_f2319detalle">
<?php
echo $sTabla2319;
?>
</div>
<div class="salto1px"></div>
</div>

<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo $ETI['msg_encuestas'];
?>
</label>
<div class="salto1px"></div>
<div id="div_f2320detalle">
<?php
echo $sTabla2320;
?>
</div>
<div class="salto1px"></div>
</div>

<?php
// -- Inicia Grupo campos 2204 Matricula
?>
<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo $ETI['titulo_2204'];
?>
</label>
<input id="boculta2204" name="boculta2204" type="hidden" value="<?php echo $_REQUEST['boculta2204']; ?>" />
<div class="salto1px"></div>
<div id="div_f2204detalle">
<?php
echo $sTabla2204;
?>
</div>
<div class="salto1px"></div>
</div>
<?php
// -- Termina Grupo campos 2204 Matricula
?>


<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo $ETI['msg_rastros'];
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['unad93fecha'];
?>
</label>
<div class="Campo220">
<?php
echo html_Fecha('unad93fecha', $_REQUEST['unad93fecha'], true, 'paginarf193()', 2018, fecha_agno());
?>
</div>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['unad93fechafin'];
?>
</label>
<div class="Campo220">
<?php
echo html_Fecha('unad93fechfin', $_REQUEST['unad93fechfin'], true, 'paginarf193()', 2018, fecha_agno());
?>
</div>
<div class="salto1px"></div>
<div id="div_f193detalle">
<?php
echo $sTabla193;
?>
</div>
<div class="salto1px"></div>
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
<input id="titulo_2319" name="titulo_2319" type="hidden" value="<?php echo $sTituloModulo; ?>" />
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
<input id="cmdAyuda97" name="cmdAyuda97" type="button" class="btSupAyuda" onclick="muestraayuda(<?php echo $iCodModulo; ?>);" title="<?php echo $ETI['bt_ayuda']; ?>" value="<?php echo $ETI['bt_ayuda']; ?>"/>
<input id="cmdVolverSec97" name="cmdVolverSec97" type="button" class="btSupVolver" onclick="retornacontrol();" title="<?php echo $ETI['bt_volver']; ?>" value="<?php echo $ETI['bt_volver']; ?>"/>
</div>
<div class="titulosI" id="div_97titulo">
<?php
echo '<h2>'.$ETI['titulo_2319'].'</h2>';
?>
</div>
</div>
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
?>
<div class="flotante">
</div>
<?php
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
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>js/jquery.autocomplete.css" type="text/css"/>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>js/jquery.autocomplete.js"></script>
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>js/chosen.css" type="text/css"/>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>js/chosen.jquery.js"></script>
<script language="javascript" src="ac_2319.js?v=2"></script>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>unad_todas2024v2.js"></script>
<?php
forma_piedepagina();

