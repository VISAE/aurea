<?php
/*
--- © Juan David Avellaneda Molina - UNAD - 2026 ---
--- juand.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 3.2.0 miércoles, 6 de mayo de 2026
*/
/** Archivo visaeemonitores.php.
 * Modulo 2931 plab31emonitoresciclo.
 * @author Juan David Avellaneda Molina - juand.avellaneda@unad.edu.co
 * @param debug = 1  (Opcional), bandera para indicar si se generan datos de depuración
 * @date miércoles, 6 de mayo de 2026
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
$iMinVerDB = 9596;
$iCodModulo = 2931;
$iCodModuloConsulta = $iCodModulo;
$sIdioma = AUREA_Idioma();
$audita[1] = false;
$audita[2]=true;
$audita[3]=true;
$audita[4]=true;
$audita[5]=false;
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
$mensajes_2931 = 'lg/lg_2931_' . $sIdioma . '.php';
if (!file_exists($mensajes_2931)) {
	$mensajes_2931 = 'lg/lg_2931_es.php';
}
require $mensajes_todas;
require $mensajes_2931;
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
$sTituloModulo = $ETI['titulo_2931'];
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
		header('Location:noticia.php?ret=visaeemonitores.php');
		die();
	}
}
$seg_1707 = 0;
$bDevuelve = false;
list($bDevuelve, $sDebugP, $seg_1707) = seg_revisa_permisoV3($iCodModulo, 1707, $_SESSION['unad_id_tercero'], $objDB, $bDebug);
$sDebug = $sDebug . $sDebugP;
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
$idEntidad = Traer_Entidad();
$mensajes_2932 = 'lg/lg_2932_' . $sIdioma . '.php';
if (!file_exists($mensajes_2932)) {
	$mensajes_2932 = 'lg/lg_2932_es.php';
}
$mensajes_2933 = 'lg/lg_2933_' . $sIdioma . '.php';
if (!file_exists($mensajes_2933)) {
	$mensajes_2933 = 'lg/lg_2933_es.php';
}
require $mensajes_2932;
require $mensajes_2933;
// -- Si esta cargando la pagina por primer vez se revisa si requiere auditar y se manda a hacer un limpiar (paso -1)
if (isset($_REQUEST['paso']) == 0) {
	$_REQUEST['paso'] = -1;
	if ($audita[1]) {
		seg_auditaingreso($iCodModulo, $_SESSION['unad_id_tercero'], $objDB);
	}
}
// -- 2931 plab31emonitoresciclo
require 'lib2931.php';
// -- 2932 Participantes
require 'lib2932.php';
// -- 2933 Acceso a cursos
require 'lib2933.php';
$xajax = new xajax();
$xajax->configure('javascript URI', $APP->rutacomun . 'xajax/');
$xajax->register(XAJAX_FUNCTION, 'unad11_Mostrar_v2');
$xajax->register(XAJAX_FUNCTION, 'unad11_TraerXid');
$xajax->register(XAJAX_FUNCTION, 'sesion_abandona_V2');
$xajax->register(XAJAX_FUNCTION, 'sesion_mantenerV4');
$xajax->register(XAJAX_FUNCTION, 'f2931_HtmlTabla');
$xajax->register(XAJAX_FUNCTION, 'f2931_ExisteDato');
$xajax->register(XAJAX_FUNCTION,'f2931_Busquedas');
$xajax->register(XAJAX_FUNCTION,'f2931_HtmlBusqueda');
$xajax->register(XAJAX_FUNCTION,'f2932_Guardar');
$xajax->register(XAJAX_FUNCTION,'f2932_Traer');
$xajax->register(XAJAX_FUNCTION,'f2932_Eliminar');
$xajax->register(XAJAX_FUNCTION,'f2932_HtmlTabla');
$xajax->register(XAJAX_FUNCTION,'f2932_PintarLlaves');
$xajax->register(XAJAX_FUNCTION,'f2933_Comboplab33idcurso');
$xajax->register(XAJAX_FUNCTION,'f2933_Guardar');
$xajax->register(XAJAX_FUNCTION,'f2933_Traer');
$xajax->register(XAJAX_FUNCTION,'f2933_Eliminar');
$xajax->register(XAJAX_FUNCTION,'f2933_HtmlTabla');
$xajax->register(XAJAX_FUNCTION,'f2933_PintarLlaves');
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
if (isset($_REQUEST['paginaf2931']) == 0) {
	$_REQUEST['paginaf2931'] = 1;
}
if (isset($_REQUEST['lppf2931']) == 0) {
	$_REQUEST['lppf2931'] = 20;
}
if (isset($_REQUEST['boculta2931']) == 0) {
	$_REQUEST['boculta2931'] = 0;
}
if (isset($_REQUEST['paginaf2932']) == 0) {
	$_REQUEST['paginaf2932'] = 1;
}
if (isset($_REQUEST['lppf2932']) == 0) {
	$_REQUEST['lppf2932'] = 20;
}
if (isset($_REQUEST['boculta2932']) == 0) {
	$_REQUEST['boculta2932'] = 0;
}
if (isset($_REQUEST['paginaf2933']) == 0) {
	$_REQUEST['paginaf2933'] = 1;
}
if (isset($_REQUEST['lppf2933']) == 0) {
	$_REQUEST['lppf2933'] = 20;
}
if (isset($_REQUEST['boculta2933']) == 0) {
	$_REQUEST['boculta2933'] = 0;
}
// -- Inicializar variables de datos.
if (isset($_REQUEST['plab31consec']) == 0) {
	$_REQUEST['plab31consec'] = '';
}
if (isset($_REQUEST['plab31consec_nuevo']) == 0) {
	$_REQUEST['plab31consec_nuevo'] = '';
}
if (isset($_REQUEST['plab31id']) == 0) {
	$_REQUEST['plab31id'] = '';
}
if (isset($_REQUEST['plab31vigente']) == 0) {
	$_REQUEST['plab31vigente'] = 'S';
}
if (isset($_REQUEST['plab31titulo']) == 0) {
	$_REQUEST['plab31titulo'] = '';
}
if (isset($_REQUEST['plab31idconvocatoria']) == 0) {
	$_REQUEST['plab31idconvocatoria'] = '';
}
if (isset($_REQUEST['plab31fechainicio']) == 0) {
	$_REQUEST['plab31fechainicio'] = '';
	//$_REQUEST['plab31fechainicio'] = $iHoy;
}
if (isset($_REQUEST['plab31fechafinal']) == 0) {
	$_REQUEST['plab31fechafinal'] = '';
	//$_REQUEST['plab31fechafinal'] = $iHoy;
}
$_REQUEST['plab31consec'] = numeros_validar($_REQUEST['plab31consec']);
$_REQUEST['plab31id'] = numeros_validar($_REQUEST['plab31id']);
$_REQUEST['plab31vigente'] = numeros_validar($_REQUEST['plab31vigente']);
$_REQUEST['plab31titulo'] = cadena_Validar($_REQUEST['plab31titulo']);
$_REQUEST['plab31idconvocatoria'] = numeros_validar($_REQUEST['plab31idconvocatoria']);
$_REQUEST['plab31fechainicio'] = numeros_validar($_REQUEST['plab31fechainicio']);
$_REQUEST['plab31fechafinal'] = numeros_validar($_REQUEST['plab31fechafinal']);
if ((int)$_REQUEST['paso'] > 0) {
	//Participantes
	if (isset($_REQUEST['plab32idtercero']) == 0) {
		$_REQUEST['plab32idtercero'] = 0;
	} //{$_SESSION['unad_id_tercero'];}
	if (isset($_REQUEST['plab32idtercero_td']) == 0) {
		$_REQUEST['plab32idtercero_td'] = $APP->tipo_doc;
	}
	if (isset($_REQUEST['plab32idtercero_doc']) == 0) {
		$_REQUEST['plab32idtercero_doc'] = '';
	}
	if (isset($_REQUEST['plab32id']) == 0) {
		$_REQUEST['plab32id'] = '';
	}
	if (isset($_REQUEST['plab32estado']) == 0) {
		$_REQUEST['plab32estado'] = '';
	}
	if (isset($_REQUEST['plab32fechaingreso']) == 0) {
		$_REQUEST['plab32fechaingreso'] = '';
	} //{fecha_hoy();}
	if (isset($_REQUEST['plab32fechafin']) == 0) {
		$_REQUEST['plab32fechafin'] = '';
	} //{fecha_hoy();}
	//Acceso a cursos
	if (isset($_REQUEST['plab33idmonitor']) == 0) {
		$_REQUEST['plab33idmonitor'] = 0;
	} //{$_SESSION['unad_id_tercero'];}
	if (isset($_REQUEST['plab33idmonitor_td']) == 0) {
		$_REQUEST['plab33idmonitor_td'] = $APP->tipo_doc;
	}
	if (isset($_REQUEST['plab33idmonitor_doc']) == 0) {
		$_REQUEST['plab33idmonitor_doc'] = '';
	}
	if (isset($_REQUEST['plab33idperiodo']) == 0) {
		$_REQUEST['plab33idperiodo'] = '';
	}
	if (isset($_REQUEST['plab33idcurso']) == 0) {
		$_REQUEST['plab33idcurso'] = '';
	}
	if (isset($_REQUEST['plab33id']) == 0) {
		$_REQUEST['plab33id'] = '';
	}
	if (isset($_REQUEST['plab33activo']) == 0) {
		$_REQUEST['plab33activo'] = 'S';
	}
	$_REQUEST['plab32idtercero'] = numeros_validar($_REQUEST['plab32idtercero']);
	$_REQUEST['plab32idtercero_td'] = cadena_Validar($_REQUEST['plab32idtercero_td']);
	$_REQUEST['plab32idtercero_doc'] = cadena_Validar($_REQUEST['plab32idtercero_doc']);
	$_REQUEST['plab32id'] = numeros_validar($_REQUEST['plab32id']);
	$_REQUEST['plab32estado'] = numeros_validar($_REQUEST['plab32estado']);
	$_REQUEST['plab32fechaingreso'] = numeros_validar($_REQUEST['plab32fechaingreso']);
	$_REQUEST['plab32fechafin'] = numeros_validar($_REQUEST['plab32fechafin']);
	$_REQUEST['plab33idmonitor'] = numeros_validar($_REQUEST['plab33idmonitor']);
	$_REQUEST['plab33idmonitor_td'] = cadena_Validar($_REQUEST['plab33idmonitor_td']);
	$_REQUEST['plab33idmonitor_doc'] = cadena_Validar($_REQUEST['plab33idmonitor_doc']);
	$_REQUEST['plab33idperiodo'] = numeros_validar($_REQUEST['plab33idperiodo']);
	$_REQUEST['plab33idcurso'] = numeros_validar($_REQUEST['plab33idcurso']);
	$_REQUEST['plab33id'] = numeros_validar($_REQUEST['plab33id']);
	$_REQUEST['plab33activo'] = numeros_validar($_REQUEST['plab33activo']);
}
// Espacio para inicializar otras variables
if (isset($_REQUEST['csv_separa']) == 0) {
	$_REQUEST['csv_separa'] = ';';
}
if (isset($_REQUEST['bnombre']) == 0) {
	$_REQUEST['bnombre'] = '';
}
//if (isset($_REQUEST['blistar'])==0){$_REQUEST['blistar']='';}
if ((int)$_REQUEST['paso'] > 0) {
	//Participantes
	if (isset($_REQUEST['bnombre2932']) == 0) {
		$_REQUEST['bnombre2932'] = '';
	}
	//if (isset($_REQUEST['blistar2932'])==0){$_REQUEST['blistar2932']='';}
	//Accesos
	if (isset($_REQUEST['bnombre2933']) == 0) {
		$_REQUEST['bnombre2933'] = '';
	}
}
//Si Modifica o Elimina Cargar los campos
if (($_REQUEST['paso'] == 1) || ($_REQUEST['paso'] == 3)) {
	if ($_REQUEST['paso'] == 1) {
		$sSQLcondi = 'plab31consec=' . $_REQUEST['plab31consec'] . '';
	} else {
		$sSQLcondi = 'plab31id=' . $_REQUEST['plab31id'] . '';
	}
	$sTabla2931 = 'plab31emonitoresciclo';
	$sSQL = 'SELECT * FROM ' . $sTabla2931 . ' WHERE ' . $sSQLcondi;
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$_REQUEST['plab31consec'] = $fila['plab31consec'];
		$_REQUEST['plab31id'] = $fila['plab31id'];
		$_REQUEST['plab31vigente'] = $fila['plab31vigente'];
		$_REQUEST['plab31titulo'] = $fila['plab31titulo'];
		$_REQUEST['plab31idconvocatoria'] = $fila['plab31idconvocatoria'];
		$_REQUEST['plab31fechainicio'] = $fila['plab31fechainicio'];
		$_REQUEST['plab31fechafinal'] = $fila['plab31fechafinal'];
		$bcargo = true;
		$_REQUEST['paso'] = 2;
		$_REQUEST['boculta2931'] = 0;
		$bLimpiaHijos = true;
	} else {
		$_REQUEST['paso'] = 0;
	}
}
//Insertar o modificar un elemento
if (($_REQUEST['paso'] == 10) || ($_REQUEST['paso'] == 12)) {
	$bMueveScroll = true;
	list($_REQUEST, $sError, $iTipoError, $sDebugGuardar) = f2931_db_GuardarV2b($_REQUEST, $objDB, $bDebug, $idTercero);
	$sDebug = $sDebug . $sDebugGuardar;
	if ($sError == '') {
		$sError = '<b>' . $ETI['msg_itemguardado'] . '</b>';
		$iTipoError = 1;
	}
}
// Cambio de consecutivo.
if ($_REQUEST['paso'] == 93) {
	$_REQUEST['paso'] = 2;
	$_REQUEST['plab31consec_nuevo'] = numeros_validar($_REQUEST['plab31consec_nuevo']);
	if ($_REQUEST['plab31consec_nuevo'] == '') {
		$sError = $ERR['plab31consec'];
	}
	if ($sError == '') {
		list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 8, $idTercero, $objDB);
		if (!$bDevuelve) {
			$sError = $ERR['8'] . ' [Mod ' . $iCodModulo . ']';
		}
	}
	if ($sError == '') {
		//Ver que el consecutivo no exista.
		$sSQL = 'SELECT plab31id FROM plab31emonitoresciclo WHERE plab31consec=' . $_REQUEST['plab31consec_nuevo'] . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$sError = 'El consecutivo ' . $_REQUEST['plab31consec_nuevo'] . ' ya existe';
		}
	}
	if ($sError == '') {
		//Aplicar el cambio.
		$sSQL = 'UPDATE plab31emonitoresciclo SET plab31consec=' . $_REQUEST['plab31consec_nuevo'] . ' WHERE plab31id=' . $_REQUEST['plab31id'] . '';
		$tabla = $objDB->ejecutasql($sSQL);
		$sDetalle = 'Cambia el consecutivo de ' . $_REQUEST['plab31consec'] . ' a ' . $_REQUEST['plab31consec_nuevo'] . '';
		$_REQUEST['plab31consec'] = $_REQUEST['plab31consec_nuevo'];
		$_REQUEST['plab31consec_nuevo'] = '';
		seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 8, $_REQUEST['plab31id'], $sDetalle, $objDB);
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
		list($sError, $iTipoError, $sDebugElimina) = f2931_db_Eliminar($_REQUEST['plab31id'], $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugElimina;
	}
	if ($sError == '') {
		$_REQUEST['paso'] = -1;
		$sError = $ETI['msg_itemeliminado'];
		$iTipoError = 1;
	}
}
$sInfoProceso = '';
if (($_REQUEST['paso'] == 50)) {
	$_REQUEST['paso'] = 2;
	if (!seg_revisa_permiso($iCodModulo, 2, $objDB)) {
		$sError = $ERR['2'];
	}
	if ($sError == '') {
		list($sError, $iTipoError, $sInfoProceso, $sDebugP) = f2931_ProcesarArchivo($_REQUEST, $_FILES, $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugP;
	}
}
//limpiar la pantalla
if ($_REQUEST['paso'] == -1) {
	$_REQUEST['plab31consec'] = '';
	$_REQUEST['plab31consec_nuevo'] = '';
	$_REQUEST['plab31id'] = '';
	$_REQUEST['plab31vigente'] = 1;
	$_REQUEST['plab31titulo'] = '';
	$_REQUEST['plab31idconvocatoria'] = 0;
	$_REQUEST['plab31fechainicio'] = $iHoy;
	$_REQUEST['plab31fechafinal'] = $iHoy;
	$_REQUEST['paso'] = 0;
}
if ($bLimpiaHijos) {
	$_REQUEST['plab32idciclo'] = '';
	$_REQUEST['plab32idtercero'] = 0; //$idTercero;
	$_REQUEST['plab32idtercero_td'] = $APP->tipo_doc;
	$_REQUEST['plab32idtercero_doc'] = '';
	$_REQUEST['plab32id'] = '';
	$_REQUEST['plab32estado'] = 0;
	$_REQUEST['plab32fechaingreso'] = ''; //fecha_hoy();
	$_REQUEST['plab32fechafin'] = ''; //fecha_hoy();
	$_REQUEST['plab33idciclo'] = '';
	$_REQUEST['plab33idmonitor'] = 0; //$idTercero;
	$_REQUEST['plab33idmonitor_td'] = $APP->tipo_doc;
	$_REQUEST['plab33idmonitor_doc'] = '';
	$_REQUEST['plab33idperiodo'] = 0;
	$_REQUEST['plab33idcurso'] = 0;
	$_REQUEST['plab33id'] = '';
	$_REQUEST['plab33activo'] = 1;
}
//AQUI SE DEBEN CARGAR TODOS LOS DATOS QUE LA FORMA NECESITE.
$bPuedeGuardar = true;
$bConEliminar = false;
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
//Permisos adicionales
$seg_5 = 0;
$seg_6 = 0;
$seg_8 = 0;
if ((int)$_REQUEST['paso'] != 0) {
	list($bHayImprimir, $sDebugP, $seg_6) = seg_revisa_permisoV3($iCodModulo, 6, $idTercero, $objDB);
	//list($bHayImprimir2, $sDebugP, $seg_5) = seg_revisa_permisoV3($iCodModulo, 5, $idTercero, $objDB);
	$bConEliminar = true;
	list($bDevuelve, $sDebugP, $seg_8) = seg_revisa_permisoV3($iCodModulo, 8, $idTercero, $objDB);
}
//DATOS PARA COMPLETAR EL FORMULARIO
$iAgnoIni = 2020;
$iAgno = fecha_agno();
$iAgnoFin = fecha_agno() + 1;
$sNombreUsuario = '';
//Crear los controles que requieran llamado a base de datos
$objCombos = new clsHtmlCombos();
$objTercero = new clsHtmlTercero();
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
$objCombos->nuevo('plab31vigente', $_REQUEST['plab31vigente'], true, $ETI['no'], 0);
$objCombos->addItem(1, $ETI['si']);
//$objCombos->addArreglo($aplab31vigente, $iplab31vigente);
$sSQL = '';
$html_plab31vigente = $objCombos->html($sSQL, $objDB);
$objCombos->nuevo('plab31idconvocatoria', $_REQUEST['plab31idconvocatoria'], true, '{' . $ETI['msg_ninguna'] . '}', 0);
$sSQL = 'SELECT visa35id AS id, visa35nombre AS nombre FROM visa35convocatoria ORDER BY visa35nombre';
$html_plab31idconvocatoria = $objCombos->html($sSQL, $objDB);
if ((int)$_REQUEST['paso'] == 0) {
} else {
	list($plab32idtercero_rs, $_REQUEST['plab32idtercero'], $_REQUEST['plab32idtercero_td'], $_REQUEST['plab32idtercero_doc']) = html_tercero($_REQUEST['plab32idtercero_td'], $_REQUEST['plab32idtercero_doc'], $_REQUEST['plab32idtercero'], 0, $objDB);
	$objCombos->nuevo('plab32estado', $_REQUEST['plab32estado'], false, $ETI['no'], 0);
	//$objCombos->addItem(1, $ETI['si']);
	$objCombos->addArreglo($aplab32estado, $iplab32estado);
	$html_plab32estado = $objCombos->html('', $objDB);
	list($plab33idmonitor_rs, $_REQUEST['plab33idmonitor'], $_REQUEST['plab33idmonitor_td'], $_REQUEST['plab33idmonitor_doc']) = html_tercero($_REQUEST['plab33idmonitor_td'], $_REQUEST['plab33idmonitor_doc'], $_REQUEST['plab33idmonitor'], 0, $objDB);
	$html_plab33idperiodo = f2933_HTMLComboV2_plab33idperiodo($objDB, $objCombos, $_REQUEST['plab33idperiodo']);
	$html_plab33idcurso = f2933_HTMLComboV2_plab33idcurso($objDB, $objCombos, $_REQUEST['plab33idcurso'], $_REQUEST['plab33idperiodo']);
	$objCombos->nuevo('plab33activo', $_REQUEST['plab33activo'], true, $ETI['no'], 0);
	$objCombos->addItem(1, $ETI['si']);
	//$objCombos->addArreglo($aplab33activo, $iplab33activo);
	$html_plab33activo = $objCombos->html('', $objDB);
}
//Alistar datos adicionales
$id_rpt = 0;
//$id_rpt=reportes_id(_Identificador_Tipo_Reporte_, $objDB);
/*
$objCombos->nuevo('blistar', $_REQUEST['blistar'], true, '{' . $ETI['msg_todos'] . '}');
$objCombos->sAccion = 'paginarf2931()';
$sSQL = '';
$html_blistar = $objCombos->html($sSQL, $objDB);
//$html_blistar = $objCombos->comboSistema(2931, 1, $objDB, 'paginarf2931()');
$objCombos->nuevo('blistar2932', $_REQUEST['blistar2932'], true, '{'.$ETI['msg_todos'].'}');
$html_blistar2932=$objCombos->comboSistema(2932, 1, $objDB, 'paginarf2932()');
*/
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
$iModeloReporte = 2931;
$html_iFormatoImprime = '<input id="iformatoimprime" name="iformatoimprime" type="hidden" value="0" />';
//Cargar las tablas de datos
$aParametros[0] = ''; //$_REQUEST['p1_2931'];
$aParametros[100] = $idTercero;
$aParametros[101] = $_REQUEST['paginaf2931'];
$aParametros[102] = $_REQUEST['lppf2931'];
//$aParametros[103] = $_REQUEST['bnombre'];
//$aParametros[104] = $_REQUEST['blistar'];
list($sTabla2931, $sDebugTabla) = f2931_TablaDetalleV2($aParametros, $objDB, $bDebug);
$sDebug = $sDebug . $sDebugTabla;
$sTabla2932 = '';
$sTabla2933 = '';
if ($_REQUEST['paso'] != 0) {
	//Participantes
	$aParametros2932[0] = $_REQUEST['plab31id'];
	$aParametros2932[100] = $idTercero;
	$aParametros2932[101] = $_REQUEST['paginaf2932'];
	$aParametros2932[102] = $_REQUEST['lppf2932'];
	//$aParametros2932[103]=$_REQUEST['bnombre2932'];
	//$aParametros2932[104]=$_REQUEST['blistar2932'];
	list($sTabla2932, $sDebugTabla) = f2932_TablaDetalleV2($aParametros2932, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugTabla;
	//Acceso a cursos
	$aParametros2933[0] = $_REQUEST['plab31id'];
	$aParametros2933[100] = $idTercero;
	$aParametros2933[101] = $_REQUEST['paginaf2933'];
	$aParametros2933[102] = $_REQUEST['lppf2933'];
	$aParametros2933[103] = $_REQUEST['bnombre2933'];
	//$aParametros2933[104]=$_REQUEST['blistar2933'];
	list($sTabla2933, $sDebugTabla) = f2933_TablaDetalleV2($aParametros2933, $objDB, $bDebug);
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
				params[4] = 'revisaf2932';
				params[5] = 'revisaf2932';
			}
			if (illave == 2) {
				params[4] = 'revisaf2933';
				params[5] = 'revisaf2933';
			}
			xajax_unad11_Mostrar_v2(params);
		} else {
			document.getElementById(idcampo).value = 0;
			document.getElementById('div_' + idcampo).innerHTML = '&nbsp;';
			if (illave == 1) {
				revisaf2932();
			}
			if (illave == 2) {
				revisaf2933();
			}
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
			window.document.frmlista.consulta.value = window.document.frmedita.consulta_2931.value;
			window.document.frmlista.titulos.value = window.document.frmedita.titulos_2931.value;
			window.document.frmlista.nombrearchivo.value = 'E-monitores';
			window.document.frmlista.submit();
		} else {
			ModalMensaje("<?php echo $ERR['6']; ?>");
		}
	}

	function asignarvariables() {
		window.document.frmimpp.separa.value = window.document.frmedita.csv_separa.value.trim();
		window.document.frmimpp.v3.value = window.document.frmedita.plab31id.value;
		window.document.frmimpp.v4.value = window.document.frmedita.plab31titulo.value;
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
			window.document.frmimpp.action = 'e2931.php';
			window.document.frmimpp.submit();
		} else {
			ModalMensaje(sError);
		}
	}

	function imprimep() {
		if (window.document.frmedita.seg_5.value == 1) {
			asignarvariables();
			window.document.frmimpp.action = 'p2931.php';
			window.document.frmimpp.submit();
			<?php
			if ($iNumFormatosImprime > 0) {
			?>
				expandesector(1);
			<?php
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
		datos[1] = window.document.frmedita.plab31consec.value;
		if ((datos[1] != '')) {
			xajax_f2931_ExisteDato(datos);
		}
	}

	function cargadato(llave1) {
		window.document.frmedita.plab31consec.value = String(llave1);
		window.document.frmedita.paso.value = 1;
		window.document.frmedita.submit();
	}

	function cargaridf2931(llave1) {
		window.document.frmedita.plab31id.value = String(llave1);
		window.document.frmedita.paso.value = 3;
		window.document.frmedita.submit();
	}

	function paginarf2931() {
		let params = new Array();
		params[99] = window.document.frmedita.debug.value;
		params[100] = <?php echo $idTercero; ?>;
		params[101] = window.document.frmedita.paginaf2931.value;
		params[102] = window.document.frmedita.lppf2931.value;
		//params[103] = window.document.frmedita.bnombre.value;
		//params[104] = window.document.frmedita.blistar.value;
		//document.getElementById('div_f2931detalle').innerHTML = '<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf2931" name="paginaf2931" type="hidden" value="' + params[101] + '" /><input id="lppf2931" name="lppf2931" type="hidden" value="' + params[102] + '" />';
		xajax_f2931_HtmlTabla(params);
	}

	<?php
	if ($_REQUEST['paso'] == 2) {
	?>

		function f2931_cargamasiva() {
			extensiones_permitidas = new Array(".xls", ".xlsx");
			let sError = '';
			let archivo = window.document.frmedita.archivodatos.value;
			if (sError == '') {
				if (!archivo) {
					sError = "No has seleccionado ning\u00fan archivo";
				}
			}
			if (sError == '') {
				//recupero la extensión de este nombre de archivo
				extension = (archivo.substring(archivo.lastIndexOf("."))).toLowerCase();
				//compruebo si la extensión está entre las permitidas
				permitida = false;
				for (var i = 0; i < extensiones_permitidas.length; i++) {
					if (extensiones_permitidas[i] == extension) {
						permitida = true;
						break;
					}
				}
				if (!permitida) {
					sError = "Comprueba la extensi\u00f3n de los archivos a subir. \nS\u00f3lo se pueden subir archivos con extensiones: " + extensiones_permitidas.join();
				} else {
					expandesector(98);
					window.document.frmedita.paso.value = 50;
					window.document.frmedita.submit();
					return 1;
				}
			}
			//si estoy aqui es que no se ha podido submitir
			ModalMensaje(sError);
			return 0;
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
		document.getElementById("plab31consec").focus();
	}

	function buscarV2016(sCampo) {
		window.document.frmedita.iscroll.value = window.pageYOffset;
		expandesector(98);
		window.document.frmedita.scampobusca.value = sCampo;
		let params = new Array();
		params[1] = sCampo;
		//params[2]=window.document.frmedita.iagno.value;
		//params[3]=window.document.frmedita.itipo.value;
		xajax_f2931_Busquedas(params);
	}

	function Devuelve(sValor) {
		let sCampo = window.document.frmedita.scampobusca.value;
		if (sCampo == 'plab32idtercero') {
			ter_traerxid('plab32idtercero', sValor);
		}
		if (sCampo == 'plab33idmonitor') {
			ter_traerxid('plab33idmonitor', sValor);
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
		var sRetorna = window.document.frmedita.div96v2.value;
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
<script language="javascript" src="jsi/js2932.js?v=2"></script>
<script language="javascript" src="jsi/js2933.js?v=2"></script>
<form id="frmimpp" name="frmimpp" method="post" action="p2931.php" target="_blank" style="display:none">
<input id="r" name="r" type="hidden" value="2931" />
<input id="id2931" name="id2931" type="hidden" value="<?php echo $_REQUEST['plab31id']; ?>" />
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
<form id="frmlista" name="frmlista" method="post" action="listados.php" target="_blank" style="display:none">
<input id="titulos" name="titulos" type="hidden" value="" />
<input id="consulta" name="consulta" type="hidden" value="" />
<input id="nombrearchivo" name="nombrearchivo" type="hidden" value="" />
</form>
<div id="interna">
<form id="frmedita" name="frmedita" method="post" action="" enctype="multipart/form-data" autocomplete="off">
<input id="bNoAutocompletar" name="bNoAutocompletar" type="password" value="" style="display:none;"/>
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
echo $objForma->htmlExpande(2931, $_REQUEST['boculta2931'], $ETI['bt_mostrar'], $ETI['bt_ocultar']);
$sEstiloDiv = '';
if ($_REQUEST['boculta2931'] != 0) {
	$sEstiloDiv = ' style="display:none;"';
}
?>
</div>
<div id="div_p2931"<?php echo $sEstiloDiv; ?>>
<?php
}
//Mostrar formulario para editar
?>
<label class="Label130"<?php echo $sOcultaConsec; ?>>
<?php
echo $ETI['plab31consec'];
?>
</label>
<label class="Label130"<?php echo $sOcultaConsec; ?>>
<?php
if ($_REQUEST['paso'] != 2) {
?>
<input id="plab31consec" name="plab31consec" type="text" value="<?php echo $_REQUEST['plab31consec']; ?>" onchange="RevisaLlave()" class="cuatro" />
<?php
} else {
	echo html_oculto('plab31consec', $_REQUEST['plab31consec'], formato_numero($_REQUEST['plab31consec']));
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
echo $ETI['plab31id'];
?>
</label>
<label class="Label60"<?php echo $sOcultaId; ?>>
<?php
	echo html_oculto('plab31id', $_REQUEST['plab31id'], formato_numero($_REQUEST['plab31id']));
?>
</label>
<label class="Label90">
<?php
echo $ETI['plab31vigente'];
?>
</label>
<label class="Label60">
<?php
echo $html_plab31vigente;
?>
</label>
<label class="Label130">
<?php
echo $ETI['plab31idconvocatoria'];
?>
</label>
<label class="Label200">
<?php
echo $html_plab31idconvocatoria;
?>
</label>
<label class="L">
<?php
echo $ETI['plab31titulo'];
?>
<input id="plab31titulo" name="plab31titulo" type="text" value="<?php echo $_REQUEST['plab31titulo']; ?>" maxlength="100" class="L" placeholder="<?php echo $ETI['ing_campo'].$ETI['plab31titulo']; ?>"/>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['plab31fechainicio'];
?>
</label>
<div class="Campo220">
<?php
echo html_FechaEnNumero('plab31fechainicio', $_REQUEST['plab31fechainicio'], true, '', $iAgnoIni, $iAgnoFin);
?>
</div>
<?php
echo $objForma->htmlBotonSolo('bplab31fechainicio_hoy', 'btMiniHoy', "fecha_AsignarNum('plab31fechainicio', " . $iHoy . ")", $ETI['bt_hoy']);
?>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['plab31fechafinal'];
?>
</label>
<div class="Campo220">
<?php
echo html_FechaEnNumero('plab31fechafinal', $_REQUEST['plab31fechafinal'], true, '', $iAgnoIni, $iAgnoFin);
?>
</div>
<?php
echo $objForma->htmlBotonSolo('bplab31fechafinal_hoy', 'btMiniHoy', "fecha_AsignarNum('plab31fechafinal', " . $iHoy . ")", $ETI['bt_hoy']);
?>
<?php
// -- Inicia Grupo campos 2932 Participantes
?>
<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo $ETI['titulo_2932'];
?>
</label>
<?php
if ($_REQUEST['paso']==2){
	//if ($bCondicion){
?>
<div class="ir_derecha"<?php echo $sAnchoExpandeContrae; ?>>
<?php
echo $objForma->htmlExpande(2932, $_REQUEST['boculta2932'], $ETI['bt_mostrar'], $ETI['bt_ocultar']);
$sEstiloDiv = '';
if ($_REQUEST['boculta2932'] != 0) {
	$sEstiloDiv = ' style="display:none;"';
}
?>
</div>
<div class="salto1px"></div>
<div id="div_p2932" style="display:<?php if ($_REQUEST['boculta2932']==0){echo 'block'; }else{echo 'none';} ?>;">
<div class="salto1px"></div>
<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo $ETI['plab32idtercero'];
?>
</label>
<div class="salto1px"></div>
<input id="plab32idtercero" name="plab32idtercero" type="hidden" value="<?php echo $_REQUEST['plab32idtercero']; ?>"/>
<div id="div_plab32idtercero_llaves">
<?php
$bOculto=true;
if ((int)$_REQUEST['plab32id']==0){$bOculto=false;}
echo html_DivTerceroV2('plab32idtercero', $_REQUEST['plab32idtercero_td'], $_REQUEST['plab32idtercero_doc'], $bOculto, 1, $ETI['ing_doc']);
?>
</div>
<div class="salto1px"></div>
<div id="div_plab32idtercero" class="L"><?php echo $plab32idtercero_rs; ?></div>
<div class="salto1px"></div>
</div>

<div class="GrupoCampos520">
<label class="Label60">
<?php
echo $ETI['plab32id'];
?>
</label>
<label class="Label60">
<div id="div_plab32id">
<?php
	echo html_oculto('plab32id', $_REQUEST['plab32id'], formato_numero($_REQUEST['plab32id']));
?>
</div>
</label>
<label class="Label90">
<?php
echo $ETI['plab32estado'];
?>
</label>
<label class="Label160">
<?php
echo $html_plab32estado;
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['plab32fechaingreso'];
?>
</label>
<div class="Campo220">
<?php
echo html_FechaEnNumero('plab32fechaingreso', $_REQUEST['plab32fechaingreso'], true, '', $iAgnoIni, $iAgnoFin);
?>
</div>
<?php
if (false){
?>
<label class="Label30">
<input id="bplab32fechaingreso_hoy" name="bplab32fechaingreso_hoy" type="button" value="Hoy" class="btMiniHoy" onclick="fecha_AsignarNum('plab32fechaingreso','<?php echo fecha_DiaMod(); ?>')" title="<?php echo $ETI['bt_hoy']; ?>"/>
</label>
<?php
	}
?>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['plab32fechafin'];
?>
</label>
<div class="Campo220">
<?php
echo html_FechaEnNumero('plab32fechafin', $_REQUEST['plab32fechafin'], true, '', $iAgnoIni, $iAgnoFin);
?>
</div>
<?php
if (false){
?>
<label class="Label30">
<input id="bplab32fechafin_hoy" name="bplab32fechafin_hoy" type="button" value="Hoy" class="btMiniHoy" onclick="fecha_AsignarNum('plab32fechafin','<?php echo fecha_DiaMod(); ?>')" title="<?php echo $ETI['bt_hoy']; ?>"/>
</label>
<?php
	}
?>
<div class="salto1px"></div>
</div>

<div class="salto1px"></div>
<label class="Label130">&nbsp;</label>
<label class="Label30">
<input id="bguarda2932" name="bguarda2932" type="button" value="Guardar" class="btMiniGuardar" onclick="guardaf2932()" title="<?php echo $ETI['bt_mini_guardar_2932']; ?>"/>
</label>
<label class="Label30">
<input id="blimpia2932" name="blimpia2932" type="button" value="Limpiar" class="btMiniLimpiar" onclick="limpiaf2932()" title="<?php echo $ETI['bt_mini_limpiar_2932']; ?>"/>
</label>
<label class="Label30">
<input id="belimina2932" name="belimina2932" type="button" value="Eliminar" class="btMiniEliminar" onclick="eliminaf2932()" title="<?php echo $ETI['bt_mini_eliminar_2932']; ?>" style="display:<?php if ((int)$_REQUEST['plab32id']!=0){echo 'block';}else{echo 'none';} ?>;"/>
</label>
<?php
		//} //Termina el segundo bloque  condicional - bloque editar.
?>
<div class="salto1px"></div>
<?php
if (false){
?>
<div class="ir_derecha">
<label class="Label130">
<?php
echo $ETI['msg_nombre'];
?>
</label>
<label>
<input id="bnombre2932" name="bnombre2932" type="text" value="<?php echo $_REQUEST['bnombre2932']; ?>" onchange="paginarf2932()"/>
</label>
<label class="Label130">
<?php
echo $ETI['msg_Listar'];
?>
</label>
<label>
<?php
echo $html_blistar2932;
?>
</label>
<div class="salto1px"></div>
</div>
<?php
	}
?>
<div id="div_f2932detalle">
<?php
echo $sTabla2932;
?>
</div>
<?php
//Este es el cierre del div_p2932
?>
<div class="salto1px"></div>
</div>
<?php
	}
?>
<div class="salto1px"></div>
</div>
<?php
// -- Termina Grupo campos 2932 Participantes
?>
<?php
// -- Inicia Grupo campos 2933 Acceso a cursos
?>
<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo $ETI['titulo_2933'];
?>
</label>
<?php
if ($_REQUEST['paso']==2){
	//if ($bCondicion){
?>
<div class="ir_derecha"<?php echo $sAnchoExpandeContrae; ?>>
<?php
echo $objForma->htmlExpande(2933, $_REQUEST['boculta2933'], $ETI['bt_mostrar'], $ETI['bt_ocultar']);
$sEstiloDiv = '';
if ($_REQUEST['boculta2933'] != 0) {
	$sEstiloDiv = ' style="display:none;"';
}
?>
</div>
<div class="salto1px"></div>
<div id="div_p2933" style="display:<?php if ($_REQUEST['boculta2933']==0){echo 'block'; }else{echo 'none';} ?>;">
<div class="salto1px"></div>
<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo $ETI['plab33idmonitor'];
?>
</label>
<div class="salto1px"></div>
<input id="plab33idmonitor" name="plab33idmonitor" type="hidden" value="<?php echo $_REQUEST['plab33idmonitor']; ?>"/>
<div id="div_plab33idmonitor_llaves">
<?php
$bOculto=true;
if ((int)$_REQUEST['plab33id']==0){$bOculto=false;}
echo html_DivTerceroV2('plab33idmonitor', $_REQUEST['plab33idmonitor_td'], $_REQUEST['plab33idmonitor_doc'], $bOculto, 2, $ETI['ing_doc']);
?>
</div>
<div class="salto1px"></div>
<div id="div_plab33idmonitor" class="L"><?php echo $plab33idmonitor_rs; ?></div>
<div class="salto1px"></div>
<label class="Label60">
<?php
echo $ETI['plab33id'];
?>
</label>
<label class="Label60">
<div id="div_plab33id">
<?php
	echo html_oculto('plab33id', $_REQUEST['plab33id'], formato_numero($_REQUEST['plab33id']));
?>
</div>
</label>
<label class="Label60">
<?php
echo $ETI['plab33activo'];
?>
</label>
<label class="Label60">
<?php
echo $html_plab33activo;
?>
</label>
<div class="salto1px"></div>
</div>

<div class="GrupoCampos520">
<label class="Label90">
<?php
echo $ETI['plab33idperiodo'];
?>
</label>
<label class="Label380">
<div id="div_plab33idperiodo">
<?php
echo $html_plab33idperiodo;
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label90">
<?php
echo $ETI['plab33idcurso'];
?>
</label>
<label class="Label380">
<div id="div_plab33idcurso">
<?php
echo $html_plab33idcurso;
?>
</div>
</label>
<div class="salto1px"></div>
</div>

<div class="salto1px"></div>
<label class="Label130">&nbsp;</label>
<label class="Label30">
<input id="bguarda2933" name="bguarda2933" type="button" value="Guardar" class="btMiniGuardar" onclick="guardaf2933()" title="<?php echo $ETI['bt_mini_guardar_2933']; ?>"/>
</label>
<label class="Label30">
<input id="blimpia2933" name="blimpia2933" type="button" value="Limpiar" class="btMiniLimpiar" onclick="limpiaf2933()" title="<?php echo $ETI['bt_mini_limpiar_2933']; ?>"/>
</label>
<label class="Label30">
<input id="belimina2933" name="belimina2933" type="button" value="Eliminar" class="btMiniEliminar" onclick="eliminaf2933()" title="<?php echo $ETI['bt_mini_eliminar_2933']; ?>" style="display:<?php if ((int)$_REQUEST['plab33id']!=0){echo 'block';}else{echo 'none';} ?>;"/>
</label>
<?php
		//} //Termina el segundo bloque  condicional - bloque editar.
?>
<div class="salto1px"></div>
<div class="ir_derecha">
<label class="Label130">
<?php
echo $ETI['msg_doc'];
?>
</label>
<label>
<input id="bnombre2933" name="bnombre2933" type="text" value="<?php echo $_REQUEST['bnombre2933']; ?>" onchange="paginarf2933()"/>
</label>
<?php
if (false){
?>
<label class="Label130">
<?php
echo $ETI['msg_Listar'];
?>
</label>
<label>
<?php
echo $html_blistar2933;
?>
</label>
<?php
	}
?>
<div class="salto1px"></div>
</div>
<div id="div_f2933detalle">
<?php
echo $sTabla2933;
?>
</div>
<?php
//Este es el cierre del div_p2933
?>
<div class="salto1px"></div>
</div>
<?php
	}
?>
<div class="salto1px"></div>
</div>
<?php
// -- Termina Grupo campos 2933 Acceso a cursos
?>
<?php
// -- Inicia la carga masiva
if ($_REQUEST['paso']==2){
?>
<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo $ETI['msg_plano2931'];
?>
</label>
<div class="salto1px"></div>
<input id="MAX_FILE_SIZE" name="MAX_FILE_SIZE" type="hidden" value="<?php echo (1*1024*1024); ?>" />
<label class="Label500">
<input id="archivodatos" name="archivodatos" type="file" />
</label>
<label class="Label130">
<?php
echo $objForma->htmlBotonSolo('cmdanexar', 'botonAnexar', 'f2931_cargamasiva()', $ETI['msg_subir']);
?>
</label>
<?php
if ($sInfoProceso!=''){
?>
<div class="salto1px"></div>
<div style="height:100px;overflow:scroll;overflow-x:hidden;">
<?php
echo $sInfoProceso;
?>
</div>
<?php
	}
?>
<div class="salto1px"></div>
<div class="GrupoCamposAyuda">
<?php
echo $ETI['msg_infoplano2931'];
?>
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
</div>
<?php
	}
//Termina la carga masiva.
if (false) {
	//Ejemplo de boton de ayuda
	//echo html_BotonAyuda('NombreCampo');
	//echo html_DivAyudaLocal('NombreCampo');
}
if ($bConExpande) {
	//Este es el cierre del div_p2931
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
<input id="bnombre" name="bnombre" type="text" value="<?php echo $_REQUEST['bnombre']; ?>" onchange="paginarf2931()" autocomplete="off" />
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
<div class="salto1px"></div>
<div id="div_f2931detalle">
<?php
echo $sTabla2931;
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
echo $ETI['msg_plab31consec'];
?>
</label>
<label class="Label90">
<?php
echo '<b>' . $_REQUEST['plab31consec'] . '</b>';
?>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['msg_plab31consec_nuevo'];
// onchange="RevisaConsec()"
?>
</label>
<label class="Label90">
<input id="plab31consec_nuevo" name="plab31consec_nuevo" type="text" value="<?php echo $_REQUEST['plab31consec_nuevo']; ?>" class="cuatro" />
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
<input id="titulo_2931" name="titulo_2931" type="hidden" value="<?php echo $sTituloModulo; ?>" />
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
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>js/jquery.autocomplete.css" type="text/css"/>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>js/jquery.autocomplete.js"></script>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>js/chosen.jquery.js"></script>
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>js/chosen.css" type="text/css" />
<?php
//if (false) {
//}
if ($_REQUEST['paso'] != 0) {
?>
<script language="javascript">
	$().ready(function() {
		$("#plab31idconvocatoria").chosen({width:"100%"});
		$("#plab33idperiodo").chosen({width:"100%"});
		$("#plab33idcurso").chosen({width:"100%"});
	});
</script>
<?php
}
?>
<script language="javascript" src="ac_2931.js"></script>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>unad_todas2024v2.js"></script>
<?php
forma_piedepagina();

