<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2018 - 2025 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.22.3 lunes, 6 de agosto de 2018
--- Modelo Versión 2.29.2 jueves, 2 de febrero de 2023
--- Modelo Versión 3.0.17 martes, 16 de septiembre de 2025
*/
/** Archivo caraajusteenc.php.
 * Modulo 2312 cara01encuesta.
 * @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
 * @param debug = 1  (Opcional), bandera para indicar si se generan datos de depuración
 * @date jueves, 2 de febrero de 2023
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
$iMinVerDB = 8963;
$iCodModulo = 2312;
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
$mensajes_2312 = $APP->rutacomun . 'lg/lg_2301_' . $sIdioma . '.php';
if (!file_exists($mensajes_2312)) {
	$mensajes_2312 = $APP->rutacomun . 'lg/lg_2301_es.php';
}
$mensajes_2310 = 'lg/lg_2310_' . $sIdioma . '.php';
if (!file_exists($mensajes_2310)) {
	$mensajes_2310 = 'lg/lg_2310_es.php';
}
require $mensajes_todas;
require $mensajes_2312;
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
list($sGrupoModulo, $sPaginaModulo) = f109_GrupoModulo($iCodModuloConsulta, $iConsecutivoMenu, $objDB);
$sOcultaId = ' style="display:none;"';
$sTituloApp = $APP->siglasistema; //f101_SiglaModulo($APP->idsistema, $objDB);
$sTituloModulo = $ETI['titulo_2312'];
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
		header('Location:noticia.php?ret=caraajusteenc.php');
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
//La libreria origen...
require $APP->rutacomun . 'lib2301.php';
// -- 2312 cara01encuesta
require 'lib2312.php';
// -- 2310 Preguntas de la prueba
require 'lib2310.php';
$xajax = new xajax();
$xajax->configure('javascript URI', $APP->rutacomun . 'xajax/');
$xajax->register(XAJAX_FUNCTION, 'unad11_Mostrar_v2');
$xajax->register(XAJAX_FUNCTION, 'unad11_TraerXid');
$xajax->register(XAJAX_FUNCTION, 'f2312_Combocara01idcead');
$xajax->register(XAJAX_FUNCTION, 'sesion_abandona_V2');
$xajax->register(XAJAX_FUNCTION, 'sesion_mantenerV4');
$xajax->register(XAJAX_FUNCTION, 'f2312_HtmlTabla');
$xajax->register(XAJAX_FUNCTION, 'f2312_ExisteDato');
$xajax->register(XAJAX_FUNCTION, 'f2312_Busquedas');
$xajax->register(XAJAX_FUNCTION, 'f2312_HtmlBusqueda');
$xajax->register(XAJAX_FUNCTION, 'f2301_Combobprograma');
$xajax->register(XAJAX_FUNCTION, 'f2301_Combobcead');
$xajax->register(XAJAX_FUNCTION, 'f2310_HtmlTablaAjusta');
$xajax->register(XAJAX_FUNCTION, 'f2310_Quitar');
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
if (isset($_REQUEST['paginaf2312']) == 0) {
	$_REQUEST['paginaf2312'] = 1;
}
if (isset($_REQUEST['lppf2312']) == 0) {
	$_REQUEST['lppf2312'] = 20;
}
if (isset($_REQUEST['boculta2312']) == 0) {
	$_REQUEST['boculta2312'] = 0;
}
if (isset($_REQUEST['paginaf2310']) == 0) {
	$_REQUEST['paginaf2310'] = 1;
}
if (isset($_REQUEST['lppf2310']) == 0) {
	$_REQUEST['lppf2310'] = 20;
}
if (isset($_REQUEST['boculta2310']) == 0) {
	$_REQUEST['boculta2310'] = 0;
}
// -- Inicializar variables de datos.
if (isset($_REQUEST['cara01idperaca']) == 0) {
	$_REQUEST['cara01idperaca'] = '';
}
if (isset($_REQUEST['cara01idperaca_nuevo']) == 0) {
	$_REQUEST['cara01idperaca_nuevo'] = '';
}
if (isset($_REQUEST['cara01idtercero']) == 0) {
	$_REQUEST['cara01idtercero'] = 0;
} // {$_SESSION['unad_id_tercero'];}
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
if (isset($_REQUEST['cara01idzona']) == 0) {
	$_REQUEST['cara01idzona'] = '';
}
if (isset($_REQUEST['cara01idcead']) == 0) {
	$_REQUEST['cara01idcead'] = '';
}
if (isset($_REQUEST['cara01tipocaracterizacion']) == 0) {
	$_REQUEST['cara01tipocaracterizacion'] = '';
}
if (isset($_REQUEST['cara01idconsejero']) == 0) {
	$_REQUEST['cara01idconsejero'] = $_SESSION['unad_id_tercero'];
}
if (isset($_REQUEST['cara01idconsejero_td']) == 0) {
	$_REQUEST['cara01idconsejero_td'] = $APP->tipo_doc;
}
if (isset($_REQUEST['cara01idconsejero_doc']) == 0) {
	$_REQUEST['cara01idconsejero_doc'] = '';
}
$_REQUEST['cara01idperaca'] = numeros_validar($_REQUEST['cara01idperaca']);
$_REQUEST['cara01idperaca_nuevo'] = numeros_validar($_REQUEST['cara01idperaca_nuevo']);
$_REQUEST['cara01idtercero'] = numeros_validar($_REQUEST['cara01idtercero']);
$_REQUEST['cara01idtercero_td'] = cadena_Validar($_REQUEST['cara01idtercero_td']);
$_REQUEST['cara01idtercero_doc'] = cadena_Validar($_REQUEST['cara01idtercero_doc']);
$_REQUEST['cara01id'] = numeros_validar($_REQUEST['cara01id']);
$_REQUEST['cara01completa'] = numeros_validar($_REQUEST['cara01completa']);
$_REQUEST['cara01idzona'] = numeros_validar($_REQUEST['cara01idzona']);
$_REQUEST['cara01idcead'] = numeros_validar($_REQUEST['cara01idcead']);
$_REQUEST['cara01tipocaracterizacion'] = numeros_validar($_REQUEST['cara01tipocaracterizacion']);
$_REQUEST['cara01idconsejero'] = numeros_validar($_REQUEST['cara01idconsejero']);
$_REQUEST['cara01idconsejero_td'] = cadena_Validar($_REQUEST['cara01idconsejero_td']);
$_REQUEST['cara01idconsejero_doc'] = cadena_Validar($_REQUEST['cara01idconsejero_doc']);
// Espacio para inicializar otras variables
if (isset($_REQUEST['csv_separa']) == 0) {
	$_REQUEST['csv_separa'] = ';';
}
if (isset($_REQUEST['bdoc']) == 0) {
	$_REQUEST['bdoc'] = '';
}
if (isset($_REQUEST['bnombre']) == 0) {
	$_REQUEST['bnombre'] = '';
}
if (isset($_REQUEST['bperaca']) == 0) {
	$_REQUEST['bperaca'] = '';
}
if (isset($_REQUEST['blistar']) == 0) {
	$_REQUEST['blistar'] = '';
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
if ((int)$_REQUEST['paso'] > 0) {
	//Preguntas de la prueba
	if (isset($_REQUEST['blistar2310']) == 0) {
		$_REQUEST['blistar2310'] = '';
	}
}
//Si Modifica o Elimina Cargar los campos
if (($_REQUEST['paso'] == 1) || ($_REQUEST['paso'] == 3)) {
	$_REQUEST['cara01idtercero_td'] = $APP->tipo_doc;
	$_REQUEST['cara01idtercero_doc'] = '';
	$_REQUEST['cara01idconsejero_td'] = $APP->tipo_doc;
	$_REQUEST['cara01idconsejero_doc'] = '';
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
		$_REQUEST['cara01idzona'] = $fila['cara01idzona'];
		$_REQUEST['cara01idcead'] = $fila['cara01idcead'];
		$_REQUEST['cara01tipocaracterizacion'] = $fila['cara01tipocaracterizacion'];
		$_REQUEST['cara01idconsejero'] = $fila['cara01idconsejero'];
		$bcargo = true;
		$_REQUEST['paso'] = 2;
		$_REQUEST['boculta2312'] = 0;
		$bLimpiaHijos = true;
	} else {
		$_REQUEST['paso'] = 0;
	}
}
//Cerrar
$bCerrando = false;
if ($_REQUEST['paso'] == 16) {
	$_REQUEST['paso'] = 12;
	$_REQUEST['cara01completa'] = 'S';
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
		//$sError='Motivo por el que no se pueda abrir, no se permite modificar.';
	}
	if ($sError != '') {
		$_REQUEST['cara01completa'] = 'S';
	} else {
		$sSQL = 'UPDATE cara01encuesta SET cara01completa="N" WHERE cara01id=' . $_REQUEST['cara01id'] . '';
		$tabla = $objDB->ejecutasql($sSQL);
		seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 3, $_REQUEST['cara01id'], 'Abre Ajustar encuesta', $objDB);
		$_REQUEST['cara01completa'] = 'N';
		$sError = '<b>El documento ha sido abierto</b>';
		$iTipoError = 1;
	}
}
//Insertar o modificar un elemento
if (($_REQUEST['paso'] == 10) || ($_REQUEST['paso'] == 12)) {
	$bMueveScroll = true;
	list($_REQUEST, $sError, $iTipoError, $bCerrando, $sErrorCerrando, $sDebugGuardar) = f2312_db_GuardarV2($_REQUEST, $objDB, $bDebug);
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
//
if ($_REQUEST['paso'] == 22) {
	$_REQUEST['paso'] = 2;
	list($sError, $sDebugP) = f2301_IniciarPreguntas($_REQUEST['cara01id'], $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugP;
	if ($sError == '') {
		$sError = 'Se ha actualizado las preguntas para la prueba';
		$iTipoError = 1;
	}
}
// Cambio de consecutivo.
if ($_REQUEST['paso'] == 93) {
	$_REQUEST['paso'] = 2;
	$_REQUEST['cara01idperaca_nuevo'] = numeros_validar($_REQUEST['cara01idperaca_nuevo']);
	if ($_REQUEST['cara01idperaca_nuevo'] == '') {
		$sError = $ERR['cara01idperaca'];
	}
	if ($sError == '') {
		list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 8, $idTercero, $objDB);
		if (!$bDevuelve) {
			$sError = $ERR['8'] . ' [Mod ' . $iCodModulo . ']';
		}
	}
	if ($sError == '') {
		//Aplicar el cambio.
		$sSQL = 'UPDATE cara01encuesta SET cara01idperaca=' . $_REQUEST['cara01idperaca_nuevo'] . ' WHERE cara01id=' . $_REQUEST['cara01id'] . '';
		$tabla = $objDB->ejecutasql($sSQL);
		$sDetalle = 'Cambia el periodo de caracterizacion del ' . $_REQUEST['cara01idperaca'] . ' al ' . $_REQUEST['cara01idperaca_nuevo'] . '';
		$_REQUEST['cara01idperaca'] = $_REQUEST['cara01idperaca_nuevo'];
		$_REQUEST['cara01idperaca_nuevo'] = '';
		seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 3, $_REQUEST['cara01id'], $sDetalle, $objDB);
		$sError = '<b>Se ha aplicado el cambio de periodo.</b>';
		$iTipoError = 1;
	} else {
		$iSector = 93;
	}
}
//Eliminar un elemento
if ($_REQUEST['paso'] == 13) {
	$_REQUEST['paso'] = 2;
	if ($sError == '') {
		list($sError, $iTipoError, $sDebugElimina) = f2312_db_Eliminar($_REQUEST['cara01id'], $objDB, $bDebug);
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
	$_REQUEST['cara01idperaca'] = '';
	$_REQUEST['cara01idperaca_nuevo'] = '';
	$_REQUEST['cara01idtercero'] = 0; //$_SESSION['unad_id_tercero'];
	$_REQUEST['cara01idtercero_td'] = $APP->tipo_doc;
	$_REQUEST['cara01idtercero_doc'] = '';
	$_REQUEST['cara01id'] = '';
	$_REQUEST['cara01completa'] = 'N';
	$_REQUEST['cara01idzona'] = '';
	$_REQUEST['cara01idcead'] = '';
	$_REQUEST['cara01tipocaracterizacion'] = '';
	$_REQUEST['cara01idconsejero'] = $_SESSION['unad_id_tercero'];
	$_REQUEST['cara01idconsejero_td'] = $APP->tipo_doc;
	$_REQUEST['cara01idconsejero_doc'] = '';
	$_REQUEST['paso'] = 0;
}
if ($bLimpiaHijos) {
}
//AQUI SE DEBEN CARGAR TODOS LOS DATOS QUE LA FORMA NECESITE.
//DATOS PARA COMPLETAR EL FORMULARIO
$sNombreUsuario = '';
$bPuedeGuardar = false;
$bPuedeAbrir = false;
$bConEliminar = false;
$bHayImprimir = false;
$bHayImprimir2 = false;
$bEncCompleta = false;
$sScriptImprime = 'imprimelista()';
$sScriptImprime2 = 'imprimep()';
$sClaseImprime = 'iExcel';
$sClaseImprime2 = 'iPdf';
if ($iPiel == 0) {
	$sClaseImprime = 'btEnviarExcel';
	$sClaseImprime2 = 'btEnviarPdf';
}
if ($_REQUEST['cara01completa'] == 'S') {
	$bEncCompleta = true;
}
if ($_REQUEST['paso'] == 2) {
	if (!$bEncCompleta) {
		$bConEliminar = true;
	}
}
if ($_REQUEST['paso'] > 0) {
	if (!$bEncCompleta) {
		$bPuedeGuardar = true;
	} else {
		$bPuedeAbrir = true;
	}
}
//Permisos adicionales
$seg_5 = 0;
$seg_6 = 0;
$seg_8 = 0;
list($bHayImprimir, $sDebugP, $seg_6) = seg_revisa_permisoV3($iCodModulo, 6, $idTercero, $objDB);
if ((int)$_REQUEST['paso'] != 0) {
	if ($bEncCompleta) {
		//Definir las condiciones que permitirán abrir el registro.
		list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 17, $idTercero, $objDB);
		if ($bDevuelve) {
			$bPuedeAbrir = true;
		}
	}
}
if ($_REQUEST['paso'] > 0) {
	list($bDevuelve, $sDebugP, $seg_5) = seg_revisa_permisoV3($iCodModulo, 5, $idTercero, $objDB);
	if (!$bEncCompleta) {
		list($bDevuelve, $sDebugP, $seg_8) = seg_revisa_permisoV3($iCodModulo, 3, $idTercero, $objDB);
	}
}
//Crear los controles que requieran llamado a base de datos
$objCombos = new clsHtmlCombos();
$objForma = new clsHtmlForma($iPiel);
list($cara01idtercero_rs, $_REQUEST['cara01idtercero'], $_REQUEST['cara01idtercero_td'], $_REQUEST['cara01idtercero_doc']) = html_tercero($_REQUEST['cara01idtercero_td'], $_REQUEST['cara01idtercero_doc'], $_REQUEST['cara01idtercero'], 0, $objDB);
$objCombos->nuevo('cara01idzona', $_REQUEST['cara01idzona'], true, '{' . $ETI['msg_seleccione'] . '}');
$objCombos->sAccion = 'carga_combo_cara01idcead();';
$sSQL = 'SELECT unad23id AS id, unad23nombre AS nombre FROM unad23zona ORDER BY unad23nombre';
$html_cara01idzona = $objCombos->html($sSQL, $objDB);
$html_cara01idcead = f2312_HTMLComboV2_cara01idcead($objDB, $objCombos, $_REQUEST['cara01idcead'], $_REQUEST['cara01idzona']);
$objCombos->nuevo('cara01tipocaracterizacion', $_REQUEST['cara01tipocaracterizacion'], true, '{' . $ETI['msg_seleccione'] . '}');
$sSQL = 'SELECT cara11id AS id, cara11nombre AS nombre FROM cara11tipocaract ORDER BY cara11nombre';
$html_cara01tipocaracterizacion = $objCombos->html($sSQL, $objDB);
list($cara01idconsejero_rs, $_REQUEST['cara01idconsejero'], $_REQUEST['cara01idconsejero_td'], $_REQUEST['cara01idconsejero_doc']) = html_tercero($_REQUEST['cara01idconsejero_td'], $_REQUEST['cara01idconsejero_doc'], $_REQUEST['cara01idconsejero'], 0, $objDB);
$html_cara01idperaca_nuevo = '';
if ((int)$_REQUEST['paso'] == 0) {
	$html_cara01idperaca = f2312_HTMLComboV2_cara01idperaca($objDB, $objCombos, $_REQUEST['cara01idperaca']);
} else {
	list($cara01idperaca_nombre, $sErrorDet) = tabla_campoxid('exte02per_aca', 'exte02nombre', 'exte02id', $_REQUEST['cara01idperaca'], '{' . $ETI['msg_sindato'] . '}', $objDB);
	$html_cara01idperaca = html_oculto('cara01idperaca', $_REQUEST['cara01idperaca'], $cara01idperaca_nombre);
	if (!$bEncCompleta) {
		$html_cara01idperaca_nuevo = f2312_HTMLComboV2_cara01idperaca_nuevo($objDB, $objCombos, $_REQUEST['cara01idperaca_nuevo']);
	}
}
$bOculto = true;
$html_cara01idconsejero = html_DivTerceroV8('cara01idconsejero', $_REQUEST['cara01idconsejero_td'], $_REQUEST['cara01idconsejero_doc'], $bOculto, $objDB, $objCombos, 0, $ETI['ing_doc']);
//Alistar datos adicionales
$id_rpt = 0;
//$id_rpt=reportes_id(_Identificador_Tipo_Reporte_, $objDB);
$objCombos->nuevo('blistar', $_REQUEST['blistar'], true, '{' . $ETI['msg_todos'] . '}');
$objCombos->sAccion = 'paginarf2312()';
$objCombos->addItem(1, 'Donde soy consejero');
$objCombos->addItem(11, 'Encuestas terminadas');
$objCombos->addItem(12, 'Encuestas incompletas');
$html_blistar = $objCombos->html('', $objDB);
$objCombos->nuevo('bperaca', $_REQUEST['bperaca'], true, '{' . $ETI['msg_todos'] . '}');
$objCombos->iAncho = 600;
$objCombos->sAccion = 'paginarf2312()';
$sIds = '-99';
$sSQL = 'SELECT cara01idperaca FROM cara01encuesta GROUP BY cara01idperaca';
$tabla = $objDB->ejecutasql($sSQL);
while ($fila = $objDB->sf($tabla)) {
	$sIds = $sIds . ',' . $fila['cara01idperaca'];
}
$sSQL = f146_ConsultaCombo('exte02id IN (' . $sIds . ')');
$html_bperaca = $objCombos->html($sSQL, $objDB);
$objCombos->nuevo('bescuela', $_REQUEST['bescuela'], true, '{' . $ETI['msg_todas'] . '}');
$objCombos->sAccion = 'carga_combo_bprograma()';
$sSQL = 'SELECT core12id AS id, core12nombre AS nombre FROM core12escuela WHERE core12tieneestudiantes="S" ORDER BY core12nombre';
$html_bescuela = $objCombos->html($sSQL, $objDB);
$html_bprograma = f2301_HTMLComboV2_bprograma($objDB, $objCombos, $_REQUEST['bprograma'], $_REQUEST['bescuela']);
$objCombos->nuevo('bzona', $_REQUEST['bzona'], true, '{' . $ETI['msg_todas'] . '}');
$objCombos->sAccion = 'carga_combo_bcead()';
$sSQL = 'SELECT unad23id AS id, unad23nombre AS nombre FROM unad23zona ORDER BY unad23nombre';
$html_bzona = $objCombos->html($sSQL, $objDB);
$html_bcead = f2301_HTMLComboV2_bcead($objDB, $objCombos, $_REQUEST['bcead'], $_REQUEST['bzona']);
$objCombos->nuevo('btipocara', $_REQUEST['btipocara'], true, '{' . $ETI['msg_todas'] . '}');
$objCombos->sAccion = 'paginarf2312()';
$sSQL = 'SELECT cara11id AS id, cara11nombre AS nombre FROM cara11tipocaract ORDER BY cara11nombre';
$html_btipocara = $objCombos->html($sSQL, $objDB);
//$html_blistar=$objCombos->comboSistema(2312, 1, $objDB, 'paginarf2312()');
if ($_REQUEST['paso'] != 0) {
	$objCombos->nuevo('blistar2310', $_REQUEST['blistar2310'], true, '{' . $ETI['msg_todos'] . '}');
	$objCombos->sAccion = 'paginarf2310()';
	$sSQL = 'SELECT cara07id AS id, cara07nombre AS nombre FROM cara07bloqueeval ORDER BY cara07id';
	$html_blistar2310 = $objCombos->html($sSQL, $objDB);
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
$iModeloReporte = 2312;
$html_iFormatoImprime = '<input id="iformatoimprime" name="iformatoimprime" type="hidden" value="0" />';
//Cargar las tablas de datos
$aParametros[0] = ''; //$_REQUEST['p1_2312'];
$aParametros[100] = $idTercero;
$aParametros[101] = $_REQUEST['paginaf2312'];
$aParametros[102] = $_REQUEST['lppf2312'];
$aParametros[103] = $_REQUEST['bdoc'];
$aParametros[104] = $_REQUEST['bnombre'];
$aParametros[105] = $_REQUEST['bperaca'];
$aParametros[106] = $_REQUEST['blistar'];
$aParametros[107] = $_REQUEST['bescuela'];
$aParametros[108] = $_REQUEST['bprograma'];
$aParametros[109] = $_REQUEST['bzona'];
$aParametros[110] = $_REQUEST['bcead'];
$aParametros[111] = $_REQUEST['btipocara'];
list($sTabla2312, $sDebugTabla) = f2312_TablaDetalleV2($aParametros, $objDB, $bDebug);
$sDebug = $sDebug . $sDebugTabla;
$sTabla2310 = '';
if ($_REQUEST['paso'] != 0) {
	//Preguntas de la prueba
	$aParametros2310[0] = $_REQUEST['cara01id'];
	$aParametros2310[101] = $_REQUEST['paginaf2310'];
	$aParametros2310[102] = $_REQUEST['lppf2310'];
	//$aParametros2310[103]=$_REQUEST['bnombre2310'];
	$aParametros2310[104] = $_REQUEST['blistar2310'];
	list($sTabla2310, $sDebugTabla) = f2310_TablaDetalleV2Ajusta($aParametros2310, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugTabla;
}
$bDebugMenu = false;
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
			if (illave == 1) { params[4] = 'RevisaLlave'; }
			//if (illave==1){params[5]='FuncionCuandoNoEsta';}
			xajax_unad11_Mostrar_v2(params);
		} else {
			document.getElementById(idcampo).value = 0;
			document.getElementById('div_' + idcampo).innerHTML = '';
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
			window.document.frmlista.consulta.value = window.document.frmedita.consulta_2312.value;
			window.document.frmlista.titulos.value = window.document.frmedita.titulos_2312.value;
			window.document.frmlista.nombrearchivo.value = 'Ajustar encuesta';
			window.document.frmlista.submit();
		} else {
			ModalMensaje("<?php echo $ERR['6']; ?>");
		}
	}

	function asignarvariables() {
		//window.document.frmimpp.v3.value=window.document.frmedita.bnombre.value;
		//window.document.frmimpp.v4.value=window.document.frmedita.bcodigo.value;
		//window.document.frmimpp.separa.value=window.document.frmedita.csv_separa.value.trim();
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
			window.document.frmimpp.action = 'e2312_ss.php';
			window.document.frmimpp.submit();
		} else {
			ModalMensaje(sError);
		}
	}

	function imprimep() {
		if (window.document.frmedita.seg_5.value == 1) {
			asignarvariables();
			window.document.frmimpp.action = 'p2312.php';
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
		datos[1] = window.document.frmedita.cara01idperaca.value;
		datos[2] = window.document.frmedita.cara01idtercero.value;
		if ((datos[1] != '') && (datos[2] != '')) {
			xajax_f2312_ExisteDato(datos);
		}
	}

	function cargadato(llave1, llave2) {
		window.document.frmedita.cara01idperaca.value = String(llave1);
		window.document.frmedita.cara01idtercero.value = String(llave2);
		window.document.frmedita.paso.value = 1;
		window.document.frmedita.submit();
	}

	function cargaridf2312(llave1) {
		window.document.frmedita.cara01id.value = String(llave1);
		window.document.frmedita.paso.value = 3;
		window.document.frmedita.submit();
	}

	function carga_combo_cara01idcead() {
		let params = new Array();
		params[0] = window.document.frmedita.cara01idzona.value;
		xajax_f2312_Combocara01idcead(params);
	}

	function paginarf2301() {
		paginarf2312();
	}

	function paginarf2312() {
		let params = new Array();
		params[99] = window.document.frmedita.debug.value;
		params[100] = <?php echo $idTercero; ?>;
		params[101] = window.document.frmedita.paginaf2312.value;
		params[102] = window.document.frmedita.lppf2312.value;
		params[103] = window.document.frmedita.bdoc.value;
		params[104] = window.document.frmedita.bnombre.value;
		params[105] = window.document.frmedita.bperaca.value;
		params[106] = window.document.frmedita.blistar.value;
		params[107] = window.document.frmedita.bescuela.value;
		params[108] = window.document.frmedita.bprograma.value;
		params[109] = window.document.frmedita.bzona.value;
		params[110] = window.document.frmedita.bcead.value;
		params[111] = window.document.frmedita.btipocara.value;
		//document.getElementById('div_f2312detalle').innerHTML='<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf2312" name="paginaf2312" type="hidden" value="'+params[101]+'" /><input id="lppf2312" name="lppf2312" type="hidden" value="'+params[102]+'" />';
		xajax_f2312_HtmlTabla(params);
	}

	function enviacerrar() {
		ModalConfirmV2('<?php echo $ETI['msg_cierre2312']; ?>', () => {
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
		document.getElementById("cara01idperaca").focus();
	}

	function buscarV2016(sCampo) {
		window.document.frmedita.iscroll.value = window.pageYOffset;
		expandesector(98);
		window.document.frmedita.scampobusca.value = sCampo;
		var params = new Array();
		params[1] = sCampo;
		//params[2]=window.document.frmedita.iagno.value;
		//params[3]=window.document.frmedita.itipo.value;
		xajax_f2312_Busquedas(params);
	}

	function retornacontrol() {
		expandesector(1);
		window.scrollTo(0, window.document.frmedita.iscroll.value);
	}
	
	function Devuelve(sValor) {
		var sCampo = window.document.frmedita.scampobusca.value;
		if (sCampo == 'cara01idtercero') {
			ter_traerxid('cara01idtercero', sValor);
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

	function carga_combo_bprograma() {
		var params = new Array();
		params[0] = window.document.frmedita.bescuela.value;
		xajax_f2301_Combobprograma(params);
	}

	function carga_combo_bcead() {
		var params = new Array();
		params[0] = window.document.frmedita.bzona.value;
		xajax_f2301_Combobcead(params);
	}

	function actualizarpreg(idReg) {
		window.document.frmedita.iscroll.value = window.pageYOffset;
		expandesector(98);
		window.document.frmedita.cara01id.value = idReg;
		window.document.frmedita.paso.value = 22;
		window.document.frmedita.submit();
	}
</script>
<?php
if ($_REQUEST['paso'] != 0) {
?>
<script language="javascript" src="jsi/js2310ajusta.js?v=2"></script>
<form id="frmimpp" name="frmimpp" method="post" action="p2312.php" target="_blank" style="display:none">
<input id="r" name="r" type="hidden" value="2312" />
<input id="id2312" name="id2312" type="hidden" value="<?php echo $_REQUEST['cara01id']; ?>" />
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
<input id="cmdImprimir" name="cmdImprimir" type="button" class="<?php echo $sClaseBoton; ?>" onclick="<?php echo $sScript; ?>" title="<?php echo $ETI['bt_imprimir']; ?>" value="<?php echo $ETI['bt_imprimir']; ?>" />
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
if ($bPuedeAbrir) {
?>
<input id="cmdAbrir" name="cmdAbrir" type="button" class="btSupAbrir" onclick="enviaabrir();" title="Abrir" value="Abrir" />
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
<input id="deb_tipodoc" name="deb_tipodoc" type="hidden" value="<?php echo $_REQUEST['deb_tipodoc']; ?>" />
<input id="deb_doc" name="deb_doc" type="hidden" value="<?php echo $_REQUEST['deb_doc']; ?>" />
<?php
//Div para ocultar
$bConExpande = true;
if ($bConExpande) {
?>
<div class="ir_derecha"<?php echo $sAnchoExpandeContrae; ?>>
<?php
echo $objForma->htmlExpande(2312, $_REQUEST['boculta2312'], $ETI['bt_mostrar'], $ETI['bt_ocultar']);
$sEstiloDiv = '';
if ($_REQUEST['boculta2312'] != 0) {
	$sEstiloDiv = ' style="display:none;"';
}
?>
</div>
<div id="div_p2312"<?php echo $sEstiloDiv; ?>>
<?php
}
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
<?php
if ($seg_8 == 1) {
	echo $objForma->htmlBotonSolo('cmdCambiaConsec', 'btMiniActualizar', 'expandesector(93);', $ETI['bt_cambiar'], 30);
	echo '<label class="Label30">&nbsp;</label>';
}
?>
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
if ($_REQUEST['paso'] != 2) {
	$bOculto = false;
}
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
if ($bEncCompleta) {
$et_cara01completa = $ETI['msg_cerrado'];
}
echo html_oculto('cara01completa', $_REQUEST['cara01completa'], $et_cara01completa);
?>
</label>
<div class="salto1px"></div>
<label class="Label200">
<?php
echo $ETI['cara01tipocaracterizacion'];
?>
</label>
<label class="Label220">
<?php
echo $html_cara01tipocaracterizacion;
?>
</label>
<div class="salto1px"></div>
</div>

<div class="salto1px"></div>

<div class="GrupoCampos450">
<label class="Label130">
<?php
echo $ETI['cara01idzona'];
?>
</label>
<label>
<?php
echo $html_cara01idzona;
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['cara01idcead'];
?>
</label>
<label>
<div id="div_cara01idcead">
<?php
echo $html_cara01idcead;
?>
</div>
</label>
<div class="salto1px"></div>
</div>


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
</div>
<?php
// -- Inicia Grupo campos 2310 Preguntas de la prueba
?>
<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo $ETI['titulo_2310'];
?>
</label>
<input id="boculta2310" name="boculta2310" type="hidden" value="<?php echo $_REQUEST['boculta2310']; ?>" />
<?php
if ($_REQUEST['paso'] == 2) {
?>
<?php
echo $objForma->htmlBotonSolo('brevisadv', 'btMiniActualizar', 'actualizarpreg()', 'Actualizar', 30);
?>
<div class="salto1px"></div>
<div class="ir_derecha">
<?php
if (false) {
?>
<label class="Label130">
Nombre
</label>
<label>
<input id="bnombre2310" name="bnombre2310" type="text" value="<?php echo $_REQUEST['bnombre2310']; ?>" onchange="paginarf2310()" />
</label>
<?php
}
?>
<label class="Label130">
Listar
</label>
<label>
<?php
echo $html_blistar2310;
?>
</label>
<div class="salto1px"></div>
</div>
<div id="div_f2310detalle">
<?php
echo $sTabla2310;
?>
</div>
<?php
}
?>
<div class="salto1px"></div>
</div>
<?php
// -- Termina Grupo campos 2310 Preguntas de la prueba
?>
<?php
if (false) {
//Ejemplo de boton de ayuda
//echo html_BotonAyuda('NombreCampo');
//echo html_DivAyudaLocal('NombreCampo');
}
if ($bConExpande) {
	//Este es el cierre del div_p2312
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
Documento
</label>
<label>
<input id="bdoc" name="bdoc" type="text" value="<?php echo $_REQUEST['bdoc']; ?>" onchange="paginarf2312()" autocomplete="off" />
</label>
<label class="Label90">
Nombre
</label>
<label class="Label250">
<input id="bnombre" name="bnombre" type="text" value="<?php echo $_REQUEST['bnombre']; ?>" onchange="paginarf2312()" autocomplete="off" />
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
echo $html_bperaca;
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
<label class="Label130">
<?php
echo $html_bzona;
?>
</label>
<div class="salto1px"></div>
<label class="Label90">
CEAD
</label>
<label class="Label130">
<div id="div_bcead">
<?php
echo $html_bcead;
?>
</div>
</label>

</div>
<div class="salto1px"></div>
<?php
echo ' ' . $csv_separa;
?>
<div id="div_f2312detalle">
<?php
echo $sTabla2312;
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
?>
<label class="Label160">
<?php
echo $ETI['msg_cara01idperaca'];
?>
</label>
<label class="Label600">
<?php
echo '<b>' . $cara01idperaca_nombre . '</b>';
?>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['msg_cara01idperaca_nuevo'];
// onchange="RevisaConsec()"
?>
</label>
<label class="Label600">
<?php
echo $html_cara01idperaca_nuevo;
?>
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
<input id="titulo_2312" name="titulo_2312" type="hidden" value="<?php echo $sTituloModulo; ?>" />
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
	if (!$bEncCompleta) {
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
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>js/jquery.autocomplete.css" type="text/css"/>
<script language="javascript">
	$().ready(function() {
<?php
if ($_REQUEST['paso'] == 0) {
?>
		$("#cara01idperaca").chosen({width: "600px"});
<?php
}
?>
		$("#bperaca").chosen({width: "600px"});
		$("#bprograma").chosen({width: "600px"});
		$("#cara01idperaca_nuevo").chosen({width: "600px"});
	});
</script>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>js/jquery.autocomplete.js"></script>
<script language="javascript" src="ac_2312.js"></script>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>unad_todas2024v2.js"></script>
<?php
forma_piedepagina();

