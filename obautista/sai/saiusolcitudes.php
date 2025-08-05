<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2020 - 2025 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.24.1 domingo, 23 de febrero de 2020
--- Modelo Versión 2.28.2 lunes, 13 de junio de 2022
--- Modelo Versión 3.0.16 jueves, 10 de julio de 2025
*/

/** Archivo saiusolcitudes.php.
 * Modulo 3005 saiu05solicitud.
 * @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
 * @param debug=1 (Opcional), bandera para indicar si se generan datos de depuración
 * @date domingo, 23 de febrero de 2020
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
require $APP->rutacomun . 'libsai.php';
require $APP->rutacomun . 'libtiempo.php';
require $APP->rutacomun . 'libmail.php';
if (($bPeticionXAJAX) && ($_SESSION['unad_id_tercero'] == 0)) {
	// viene por xajax.
	$xajax = new xajax();
	$xajax->configure('javascript URI', $APP->rutacomun . 'xajax/');
	$xajax->register(XAJAX_FUNCTION, 'sesion_abandona_V2');
	$xajax->processRequest();
	die();
}
$iConsecutivoMenu = 1;
$iMinVerDB = 7774;
$iCodModulo = 3005;
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
$mensajes_3000 = $APP->rutacomun . 'lg/lg_3000_' . $_SESSION['unad_idioma'] . '.php';
if (!file_exists($mensajes_3000)) {
	$mensajes_3000 = $APP->rutacomun . 'lg/lg_3000_es.php';
}
$mensajes_3005 = $APP->rutacomun . 'lg/lg_3005_' . $_SESSION['unad_idioma'] . '.php';
if (!file_exists($mensajes_3005)) {
	$mensajes_3005 = $APP->rutacomun . 'lg/lg_3005_es.php';
}
$mensajes_3006 = $APP->rutacomun . 'lg/lg_3006_' . $_SESSION['unad_idioma'] . '.php';
if (!file_exists($mensajes_3006)) {
	$mensajes_3006 = $APP->rutacomun . 'lg/lg_3006_es.php';
}
$mensajes_3007 = $APP->rutacomun . 'lg/lg_3007_' . $_SESSION['unad_idioma'] . '.php';
if (!file_exists($mensajes_3007)) {
	$mensajes_3007 = $APP->rutacomun . 'lg/lg_3007_es.php';
}
require $mensajes_todas;
require $mensajes_3000;
require $mensajes_3005;
require $mensajes_3006;
require $mensajes_3007;
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
$sTituloModulo = $ETI['titulo_3005'];
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
		header('Location:noticia.php?ret=saiusolcitudes.php');
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
	$_REQUEST['paso'] = -1;
	if ($audita[1]) {
		seg_auditaingreso($iCodModulo, $_SESSION['unad_id_tercero'], $objDB);
	}
}
// -- 3005 saiu05solicitud
require $APP->rutacomun . 'lib3005.php';
// -- 3000 Historial de solicitudes
require $APP->rutacomun . 'lib3000.php';
// -- 3006 Anotaciones
require $APP->rutacomun . 'lib3006.php';
// -- 3007 Anexos
require $APP->rutacomun . 'lib3007.php';
$xajax = new xajax();
$xajax->configure('javascript URI', $APP->rutacomun . 'xajax/');
$xajax->register(XAJAX_FUNCTION, 'unad11_Mostrar_v2');
$xajax->register(XAJAX_FUNCTION, 'unad11_Mostrar_v2SAI');
$xajax->register(XAJAX_FUNCTION, 'unad11_TraerXid');
$xajax->register(XAJAX_FUNCTION, 'unad11_TraerXidSAI');
$xajax->register(XAJAX_FUNCTION, 'formatear_moneda');
$xajax->register(XAJAX_FUNCTION, 'f3005_Combosaiu05idtemaorigen');
$xajax->register(XAJAX_FUNCTION, 'f3005_Combosaiu05idequiporesp');
$xajax->register(XAJAX_FUNCTION, 'f3005_Combobcentro');
$xajax->register(XAJAX_FUNCTION, 'f3005_Combobprograma');
$xajax->register(XAJAX_FUNCTION, 'sesion_abandona_V2');
$xajax->register(XAJAX_FUNCTION, 'sesion_mantenerV4');
$xajax->register(XAJAX_FUNCTION, 'f3005_HtmlTabla');
$xajax->register(XAJAX_FUNCTION, 'f3005_ExisteDato');
$xajax->register(XAJAX_FUNCTION, 'f3005_Busquedas');
$xajax->register(XAJAX_FUNCTION, 'f3005_HtmlBusqueda');
$xajax->register(XAJAX_FUNCTION, 'f3000_HtmlTabla');
$xajax->register(XAJAX_FUNCTION, 'f3000pqrs_HtmlTabla');
$xajax->register(XAJAX_FUNCTION, 'elimina_archivo_saiu06idarchivo');
$xajax->register(XAJAX_FUNCTION, 'f3006_Guardar');
$xajax->register(XAJAX_FUNCTION, 'f3006_Traer');
$xajax->register(XAJAX_FUNCTION, 'f3006_Eliminar');
$xajax->register(XAJAX_FUNCTION, 'f3006_HtmlTabla');
$xajax->register(XAJAX_FUNCTION, 'f3006_PintarLlaves');
$xajax->register(XAJAX_FUNCTION, 'elimina_archivo_saiu07idarchivo');
$xajax->register(XAJAX_FUNCTION, 'f3007_Guardar');
$xajax->register(XAJAX_FUNCTION, 'f3007_Traer');
$xajax->register(XAJAX_FUNCTION, 'f3007_Eliminar');
$xajax->register(XAJAX_FUNCTION, 'f3007_HtmlTabla');
$xajax->register(XAJAX_FUNCTION, 'f3007_PintarLlaves');
$xajax->register(XAJAX_FUNCTION, 'f3007_AprobarDocumento');
$xajax->register(XAJAX_FUNCTION, 'f236_TraerInfoPersonal');
$xajax->register(XAJAX_FUNCTION, 'elimina_archivo_saiu05idarchivo');
$xajax->register(XAJAX_FUNCTION, 'f3005_Combobtema');
$xajax->register(XAJAX_FUNCTION, 'f3005_db_EliminarBorradores');
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
$iAgno = fecha_agno();
$iMes = fecha_mes();
// -- Se inicializan las variables, primero las que controlan la visualización de la página.
if (isset($_REQUEST['iscroll']) == 0) {
	$_REQUEST['iscroll'] = 0;
}
if (isset($_REQUEST['paginaf3000']) == 0) {
	$_REQUEST['paginaf3000'] = 1;
}
if (isset($_REQUEST['lppf3000']) == 0) {
	$_REQUEST['lppf3000'] = 10;
}
if (isset($_REQUEST['paginaf3000pqrs']) == 0) {
	$_REQUEST['paginaf3000pqrs'] = 1;
}
if (isset($_REQUEST['lppf3000pqrs']) == 0) {
	$_REQUEST['lppf3000pqrs'] = 10;
}
if (isset($_REQUEST['boculta3000']) == 0) {
	$_REQUEST['boculta3000'] = 0;
}
if (isset($_REQUEST['paginaf3005']) == 0) {
	$_REQUEST['paginaf3005'] = 1;
}
if (isset($_REQUEST['lppf3005']) == 0) {
	$_REQUEST['lppf3005'] = 20;
}
if (isset($_REQUEST['boculta3005']) == 0) {
	$_REQUEST['boculta3005'] = 0;
}
if (isset($_REQUEST['paginaf3006']) == 0) {
	$_REQUEST['paginaf3006'] = 1;
}
if (isset($_REQUEST['lppf3006']) == 0) {
	$_REQUEST['lppf3006'] = 20;
}
if (isset($_REQUEST['boculta3006']) == 0) {
	$_REQUEST['boculta3006'] = 0;
}
if (isset($_REQUEST['paginaf3007']) == 0) {
	$_REQUEST['paginaf3007'] = 1;
}
if (isset($_REQUEST['lppf3007']) == 0) {
	$_REQUEST['lppf3007'] = 20;
}
if (isset($_REQUEST['boculta3007']) == 0) {
	$_REQUEST['boculta3007'] = 0;
}
// -- Inicializar variables de datos.
if (isset($_REQUEST['saiu05agno']) == 0) {
	$_REQUEST['saiu05agno'] = $iAgno;
}
if (isset($_REQUEST['saiu05mes']) == 0) {
	$_REQUEST['saiu05mes'] = $iMes;
}
if (isset($_REQUEST['saiu05tiporadicado']) == 0) {
	$_REQUEST['saiu05tiporadicado'] = 1;
}
if (isset($_REQUEST['saiu05consec']) == 0) {
	$_REQUEST['saiu05consec'] = '';
}
if (isset($_REQUEST['saiu05consec_nuevo']) == 0) {
	$_REQUEST['saiu05consec_nuevo'] = '';
}
if (isset($_REQUEST['saiu05id']) == 0) {
	$_REQUEST['saiu05id'] = '';
}
if (isset($_REQUEST['saiu05origenagno']) == 0) {
	$_REQUEST['saiu05origenagno'] = '';
}
if (isset($_REQUEST['saiu05origenmes']) == 0) {
	$_REQUEST['saiu05origenmes'] = '';
}
if (isset($_REQUEST['saiu05origenid']) == 0) {
	$_REQUEST['saiu05origenid'] = 0;
}
if (isset($_REQUEST['saiu05dia']) == 0) {
	$_REQUEST['saiu05dia'] = '';
	//$_REQUEST['saiu05dia'] = $iHoy;
}
if (isset($_REQUEST['saiu05hora']) == 0) {
	$_REQUEST['saiu05hora'] = fecha_hora();
}
if (isset($_REQUEST['saiu05minuto']) == 0) {
	$_REQUEST['saiu05minuto'] = fecha_minuto();
}
if (isset($_REQUEST['saiu05raddia']) == 0) {
	$_REQUEST['saiu05raddia'] = '';
}
if (isset($_REQUEST['saiu05radhora']) == 0) {
	$_REQUEST['saiu05radhora'] = '';
}
if (isset($_REQUEST['saiu05radmin']) == 0) {
	$_REQUEST['saiu05radmin'] = '';
}
if (isset($_REQUEST['saiu05raddespcalend']) == 0) {
	$_REQUEST['saiu05raddespcalend'] = '';
}
if (isset($_REQUEST['saiu05raddesphab']) == 0) {
	$_REQUEST['saiu05raddesphab'] = 0;
}
if (isset($_REQUEST['saiu05estado']) == 0) {
	$_REQUEST['saiu05estado'] = -1;
}
if (isset($_REQUEST['saiu05idmedio']) == 0) {
	$_REQUEST['saiu05idmedio'] = 0;
}
if (isset($_REQUEST['saiu05idtiposolorigen']) == 0) {
	$_REQUEST['saiu05idtiposolorigen'] = '';
}
if (isset($_REQUEST['saiu05idtemaorigen']) == 0) {
	$_REQUEST['saiu05idtemaorigen'] = '';
}
if (isset($_REQUEST['saiu05idtemafin']) == 0) {
	$_REQUEST['saiu05idtemafin'] = '';
}
if (isset($_REQUEST['saiu05idtiposolfin']) == 0) {
	$_REQUEST['saiu05idtiposolfin'] = '';
}
if (isset($_REQUEST['saiu05idsolicitante']) == 0) {
	$_REQUEST['saiu05idsolicitante'] = $_SESSION['unad_id_tercero'];
}
if (isset($_REQUEST['saiu05idsolicitante_td']) == 0) {
	$_REQUEST['saiu05idsolicitante_td'] = $APP->tipo_doc;
}
if (isset($_REQUEST['saiu05idsolicitante_doc']) == 0) {
	$_REQUEST['saiu05idsolicitante_doc'] = '';
}
if (isset($_REQUEST['saiu05idinteresado']) == 0) {
	$_REQUEST['saiu05idinteresado'] = 0;
	//$_REQUEST['saiu05idinteresado'] = $_SESSION['unad_id_tercero'];
}
if (isset($_REQUEST['saiu05idinteresado_td']) == 0) {
	$_REQUEST['saiu05idinteresado_td'] = $APP->tipo_doc;
}
if (isset($_REQUEST['saiu05idinteresado_doc']) == 0) {
	$_REQUEST['saiu05idinteresado_doc'] = '';
}
if (isset($_REQUEST['saiu05tipointeresado']) == 0) {
	$_REQUEST['saiu05tipointeresado'] = '';
}
if (isset($_REQUEST['saiu05rptaforma']) == 0) {
	$_REQUEST['saiu05rptaforma'] = 0;
}
if (isset($_REQUEST['saiu05rptacorreo']) == 0) {
	$_REQUEST['saiu05rptacorreo'] = '';
}
if (isset($_REQUEST['saiu05rptadireccion']) == 0) {
	$_REQUEST['saiu05rptadireccion'] = '';
}
if (isset($_REQUEST['saiu05costogenera']) == 0) {
	$_REQUEST['saiu05costogenera'] = 0;
}
if (isset($_REQUEST['saiu05costovalor']) == 0) {
	$_REQUEST['saiu05costovalor'] = 0;
}
if (isset($_REQUEST['saiu05costorefpago']) == 0) {
	$_REQUEST['saiu05costorefpago'] = '';
}
if (isset($_REQUEST['saiu05prioridad']) == 0) {
	$_REQUEST['saiu05prioridad'] = '';
}
if (isset($_REQUEST['saiu05idzona']) == 0) {
	$_REQUEST['saiu05idzona'] = '';
}
if (isset($_REQUEST['saiu05cead']) == 0) {
	$_REQUEST['saiu05cead'] = '';
}
if (isset($_REQUEST['saiu05numref']) == 0) {
	$_REQUEST['saiu05numref'] = '';
}
if (isset($_REQUEST['saiu05detalle']) == 0) {
	$_REQUEST['saiu05detalle'] = '';
}
if (isset($_REQUEST['saiu05infocomplemento']) == 0) {
	$_REQUEST['saiu05infocomplemento'] = '';
}
if (isset($_REQUEST['saiu05idunidadresp']) == 0) {
	$_REQUEST['saiu05idunidadresp'] = 0;
}
if (isset($_REQUEST['saiu05idequiporesp']) == 0) {
	$_REQUEST['saiu05idequiporesp'] = 0;
}
if (isset($_REQUEST['saiu05idsupervisor']) == 0) {
	$_REQUEST['saiu05idsupervisor'] = 0;
	//$_REQUEST['saiu05idsupervisor'] = $_SESSION['unad_id_tercero'];
}
if (isset($_REQUEST['saiu05idsupervisor_td']) == 0) {
	$_REQUEST['saiu05idsupervisor_td'] = $APP->tipo_doc;
}
if (isset($_REQUEST['saiu05idsupervisor_doc']) == 0) {
	$_REQUEST['saiu05idsupervisor_doc'] = '';
}
if (isset($_REQUEST['saiu05idresponsable']) == 0) {
	$_REQUEST['saiu05idresponsable'] = 0;
	//$_REQUEST['saiu05idresponsable'] = $_SESSION['unad_id_tercero'];
}
if (isset($_REQUEST['saiu05idresponsable_td']) == 0) {
	$_REQUEST['saiu05idresponsable_td'] = $APP->tipo_doc;
}
if (isset($_REQUEST['saiu05idresponsable_doc']) == 0) {
	$_REQUEST['saiu05idresponsable_doc'] = '';
}
if (isset($_REQUEST['saiu05idescuela']) == 0) {
	$_REQUEST['saiu05idescuela'] = '';
}
if (isset($_REQUEST['saiu05idprograma']) == 0) {
	$_REQUEST['saiu05idprograma'] = '';
}
if (isset($_REQUEST['saiu05idperiodo']) == 0) {
	$_REQUEST['saiu05idperiodo'] = '';
}
if (isset($_REQUEST['saiu05idcurso']) == 0) {
	$_REQUEST['saiu05idcurso'] = '';
}
if (isset($_REQUEST['saiu05idgrupo']) == 0) {
	$_REQUEST['saiu05idgrupo'] = '';
}
if (isset($_REQUEST['saiu05tiemprespdias']) == 0) {
	$_REQUEST['saiu05tiemprespdias'] = '';
}
if (isset($_REQUEST['saiu05tiempresphoras']) == 0) {
	$_REQUEST['saiu05tiempresphoras'] = '';
}
if (isset($_REQUEST['saiu05fecharespprob']) == 0) {
	$_REQUEST['saiu05fecharespprob'] = '';
	//$_REQUEST['saiu05fecharespprob'] = fecha_hoy();
}
if (isset($_REQUEST['saiu05respuesta']) == 0) {
	$_REQUEST['saiu05respuesta'] = '';
}
if (isset($_REQUEST['saiu05fecharespdef']) == 0) {
	$_REQUEST['saiu05fecharespdef'] = '';
	//$_REQUEST['saiu05fecharespdef'] = fecha_hoy();
}
if (isset($_REQUEST['saiu05horarespdef']) == 0) {
	$_REQUEST['saiu05horarespdef'] = fecha_hora();
}
if (isset($_REQUEST['saiu05minrespdef']) == 0) {
	$_REQUEST['saiu05minrespdef'] = fecha_minuto();
}
if (isset($_REQUEST['saiu05diasproc']) == 0) {
	$_REQUEST['saiu05diasproc'] = 0;
}
if (isset($_REQUEST['saiu05minproc']) == 0) {
	$_REQUEST['saiu05minproc'] = 0;
}
if (isset($_REQUEST['saiu05diashabproc']) == 0) {
	$_REQUEST['saiu05diashabproc'] = 0;
}
if (isset($_REQUEST['saiu05minhabproc']) == 0) {
	$_REQUEST['saiu05minhabproc'] = 0;
}
if (isset($_REQUEST['saiu05idmoduloproc']) == 0) {
	$_REQUEST['saiu05idmoduloproc'] = 0;
}
if (isset($_REQUEST['saiu05identificadormod']) == 0) {
	$_REQUEST['saiu05identificadormod'] = '';
}
if (isset($_REQUEST['saiu05numradicado']) == 0) {
	$_REQUEST['saiu05numradicado'] = 0;
}
if (isset($_REQUEST['saiu05evalacepta']) == 0) {
	$_REQUEST['saiu05evalacepta'] = 0;
}
if (isset($_REQUEST['saiu05evalfecha']) == 0) {
	$_REQUEST['saiu05evalfecha'] = '';
	//$_REQUEST['saiu05evalfecha'] = fecha_hoy();
}
if (isset($_REQUEST['saiu05evalamabilidad']) == 0) {
	$_REQUEST['saiu05evalamabilidad'] = 0;
}
if (isset($_REQUEST['saiu05evalamabmotivo']) == 0) {
	$_REQUEST['saiu05evalamabmotivo'] = '';
}
if (isset($_REQUEST['saiu05evalrapidez']) == 0) {
	$_REQUEST['saiu05evalrapidez'] = 0;
}
if (isset($_REQUEST['saiu05evalrapidmotivo']) == 0) {
	$_REQUEST['saiu05evalrapidmotivo'] = '';
}
if (isset($_REQUEST['saiu05evalclaridad']) == 0) {
	$_REQUEST['saiu05evalclaridad'] = 0;
}
if (isset($_REQUEST['saiu05evalcalridmotivo']) == 0) {
	$_REQUEST['saiu05evalcalridmotivo'] = '';
}
if (isset($_REQUEST['saiu05evalresolvio']) == 0) {
	$_REQUEST['saiu05evalresolvio'] = 0;
}
if (isset($_REQUEST['saiu05evalsugerencias']) == 0) {
	$_REQUEST['saiu05evalsugerencias'] = '';
}
if (isset($_REQUEST['saiu05idcategoria']) == 0) {
	$_REQUEST['saiu05idcategoria'] = '';
}
if (isset($_REQUEST['saiu05idorigen']) == 0) {
	$_REQUEST['saiu05idorigen'] = 0;
}
if (isset($_REQUEST['saiu05idarchivo']) == 0) {
	$_REQUEST['saiu05idarchivo'] = 0;
}
if ((int)$_REQUEST['paso'] > 0) {
	//Anotaciones
	if (isset($_REQUEST['saiu06consec']) == 0) {
		$_REQUEST['saiu06consec'] = '';
	}
	if (isset($_REQUEST['saiu06id']) == 0) {
		$_REQUEST['saiu06id'] = '';
	}
	if (isset($_REQUEST['saiu06anotacion']) == 0) {
		$_REQUEST['saiu06anotacion'] = '';
	}
	if (isset($_REQUEST['saiu06visible']) == 0) {
		$_REQUEST['saiu06visible'] = 'N';
	}
	if (isset($_REQUEST['saiu06descartada']) == 0) {
		$_REQUEST['saiu06descartada'] = 'N';
	}
	if (isset($_REQUEST['saiu06idorigen']) == 0) {
		$_REQUEST['saiu06idorigen'] = 0;
	}
	if (isset($_REQUEST['saiu06idarchivo']) == 0) {
		$_REQUEST['saiu06idarchivo'] = 0;
	}
	if (isset($_REQUEST['saiu06idusuario']) == 0) {
		$_REQUEST['saiu06idusuario'] = $idTercero;
	}
	if (isset($_REQUEST['saiu06idusuario_td']) == 0) {
		$_REQUEST['saiu06idusuario_td'] = $APP->tipo_doc;
	}
	if (isset($_REQUEST['saiu06idusuario_doc']) == 0) {
		$_REQUEST['saiu06idusuario_doc'] = '';
	}
	if (isset($_REQUEST['saiu06fecha']) == 0) {
		// $_REQUEST['saiu06fecha'] = fecha_hoy();
		$_REQUEST['saiu06fecha'] = $iHoy;
	}
	if (isset($_REQUEST['saiu06hora']) == 0) {
		$_REQUEST['saiu06hora'] = fecha_hora();
	}
	if (isset($_REQUEST['saiu06minuto']) == 0) {
		$_REQUEST['saiu06minuto'] = fecha_minuto();
	}
	//Anexos
	if (isset($_REQUEST['saiu07consec']) == 0) {
		$_REQUEST['saiu07consec'] = '';
	}
	if (isset($_REQUEST['saiu07id']) == 0) {
		$_REQUEST['saiu07id'] = '';
	}
	if (isset($_REQUEST['saiu07idtipoanexo']) == 0) {
		$_REQUEST['saiu07idtipoanexo'] = '';
	}
	if (isset($_REQUEST['saiu07detalle']) == 0) {
		$_REQUEST['saiu07detalle'] = '';
	}
	if (isset($_REQUEST['saiu07idorigen']) == 0) {
		$_REQUEST['saiu07idorigen'] = 0;
	}
	if (isset($_REQUEST['saiu07idarchivo']) == 0) {
		$_REQUEST['saiu07idarchivo'] = 0;
	}
	if (isset($_REQUEST['saiu07idusuario']) == 0) {
		$_REQUEST['saiu07idusuario'] = 0;
	} //{$_SESSION['unad_id_tercero'];}
	if (isset($_REQUEST['saiu07idusuario_td']) == 0) {
		$_REQUEST['saiu07idusuario_td'] = $APP->tipo_doc;
	}
	if (isset($_REQUEST['saiu07idusuario_doc']) == 0) {
		$_REQUEST['saiu07idusuario_doc'] = '';
	}
	if (isset($_REQUEST['saiu07fecha']) == 0) {
		$_REQUEST['saiu07fecha'] = '';
		//$_REQUEST['saiu07fecha'] = $iHoy;
	} //{fecha_hoy();}
	if (isset($_REQUEST['saiu07hora']) == 0) {
		$_REQUEST['saiu07hora'] = '';
	}
	if (isset($_REQUEST['saiu07minuto']) == 0) {
		$_REQUEST['saiu07minuto'] = '';
	}
	if (isset($_REQUEST['saiu07estado']) == 0) {
		$_REQUEST['saiu07estado'] = '';
	}
	if (isset($_REQUEST['saiu07idvalidad']) == 0) {
		$_REQUEST['saiu07idvalidad'] = 0;
	} //{$_SESSION['unad_id_tercero'];}
	if (isset($_REQUEST['saiu07idvalidad_td']) == 0) {
		$_REQUEST['saiu07idvalidad_td'] = $APP->tipo_doc;
	}
	if (isset($_REQUEST['saiu07idvalidad_doc']) == 0) {
		$_REQUEST['saiu07idvalidad_doc'] = '';
	}
	if (isset($_REQUEST['saiu07fechavalida']) == 0) {
		$_REQUEST['saiu07fechavalida'] = '';
		//$_REQUEST['saiu07fechavalida'] = $iHoy;
	} //{fecha_hoy();}
	if (isset($_REQUEST['saiu07horavalida']) == 0) {
		$_REQUEST['saiu07horavalida'] = '';
	}
	if (isset($_REQUEST['saiu07minvalida']) == 0) {
		$_REQUEST['saiu07minvalida'] = '';
	}
}
$_REQUEST['saiu05agno'] = numeros_validar($_REQUEST['saiu05agno']);
$_REQUEST['saiu05mes'] = numeros_validar($_REQUEST['saiu05mes']);
$_REQUEST['saiu05tiporadicado'] = numeros_validar($_REQUEST['saiu05tiporadicado']);
$_REQUEST['saiu05consec'] = numeros_validar($_REQUEST['saiu05consec']);
$_REQUEST['saiu05id'] = numeros_validar($_REQUEST['saiu05id']);
$_REQUEST['saiu05origenagno'] = numeros_validar($_REQUEST['saiu05origenagno']);
$_REQUEST['saiu05origenmes'] = numeros_validar($_REQUEST['saiu05origenmes']);
$_REQUEST['saiu05origenid'] = numeros_validar($_REQUEST['saiu05origenid']);
$_REQUEST['saiu05dia'] = numeros_validar($_REQUEST['saiu05dia']);
$_REQUEST['saiu05hora'] = numeros_validar($_REQUEST['saiu05hora']);
$_REQUEST['saiu05minuto'] = numeros_validar($_REQUEST['saiu05minuto']);
$_REQUEST['saiu05raddia'] = numeros_validar($_REQUEST['saiu05raddia']);
$_REQUEST['saiu05radhora'] = numeros_validar($_REQUEST['saiu05radhora']);
$_REQUEST['saiu05radmin'] = numeros_validar($_REQUEST['saiu05radmin']);
$_REQUEST['saiu05raddespcalend'] = numeros_validar($_REQUEST['saiu05raddespcalend']);
$_REQUEST['saiu05raddesphab'] = numeros_validar($_REQUEST['saiu05raddesphab']);
$_REQUEST['saiu05estado'] = numeros_validar($_REQUEST['saiu05estado']);
$_REQUEST['saiu05idmedio'] = numeros_validar($_REQUEST['saiu05idmedio']);
$_REQUEST['saiu05idtiposolorigen'] = numeros_validar($_REQUEST['saiu05idtiposolorigen']);
$_REQUEST['saiu05idtemaorigen'] = numeros_validar($_REQUEST['saiu05idtemaorigen']);
$_REQUEST['saiu05idtiposolfin'] = numeros_validar($_REQUEST['saiu05idtiposolfin']);
$_REQUEST['saiu05idtemafin'] = numeros_validar($_REQUEST['saiu05idtemafin']);
$_REQUEST['saiu05idsolicitante'] = numeros_validar($_REQUEST['saiu05idsolicitante']);
$_REQUEST['saiu05idinteresado'] = numeros_validar($_REQUEST['saiu05idinteresado']);
$_REQUEST['saiu05tipointeresado'] = numeros_validar($_REQUEST['saiu05tipointeresado']);
$_REQUEST['saiu05rptaforma'] = numeros_validar($_REQUEST['saiu05rptaforma']);
$_REQUEST['saiu05rptacorreo'] = cadena_Validar($_REQUEST['saiu05rptacorreo']);
$_REQUEST['saiu05rptadireccion'] = cadena_Validar($_REQUEST['saiu05rptadireccion']);
$_REQUEST['saiu05costogenera'] = numeros_validar($_REQUEST['saiu05costogenera']);
$_REQUEST['saiu05costovalor'] = numeros_validar($_REQUEST['saiu05costovalor']);
$_REQUEST['saiu05costorefpago'] = cadena_Validar($_REQUEST['saiu05costorefpago']);
$_REQUEST['saiu05prioridad'] = numeros_validar($_REQUEST['saiu05prioridad']);
$_REQUEST['saiu05idzona'] = numeros_validar($_REQUEST['saiu05idzona']);
$_REQUEST['saiu05cead'] = numeros_validar($_REQUEST['saiu05cead']);
$_REQUEST['saiu05numref'] = cadena_Validar($_REQUEST['saiu05numref']);
$_REQUEST['saiu05detalle'] = cadena_Validar($_REQUEST['saiu05detalle']);
$_REQUEST['saiu05infocomplemento'] = cadena_Validar($_REQUEST['saiu05infocomplemento']);
$_REQUEST['saiu05idunidadresp'] = numeros_validar($_REQUEST['saiu05idunidadresp']);
$_REQUEST['saiu05idequiporesp'] = numeros_validar($_REQUEST['saiu05idequiporesp']);
$_REQUEST['saiu05idsupervisor'] = numeros_validar($_REQUEST['saiu05idsupervisor']);
$_REQUEST['saiu05idresponsable'] = numeros_validar($_REQUEST['saiu05idresponsable']);
$_REQUEST['saiu05idescuela'] = numeros_validar($_REQUEST['saiu05idescuela']);
$_REQUEST['saiu05idprograma'] = numeros_validar($_REQUEST['saiu05idprograma']);
$_REQUEST['saiu05idperiodo'] = numeros_validar($_REQUEST['saiu05idperiodo']);
$_REQUEST['saiu05idcurso'] = numeros_validar($_REQUEST['saiu05idcurso']);
$_REQUEST['saiu05idgrupo'] = numeros_validar($_REQUEST['saiu05idgrupo']);
$_REQUEST['saiu05tiemprespdias'] = numeros_validar($_REQUEST['saiu05tiemprespdias']);
$_REQUEST['saiu05tiempresphoras'] = numeros_validar($_REQUEST['saiu05tiempresphoras']);
$_REQUEST['saiu05fecharespprob'] = numeros_validar($_REQUEST['saiu05fecharespprob']);
$_REQUEST['saiu05respuesta'] = cadena_Validar($_REQUEST['saiu05respuesta']);
$_REQUEST['saiu05fecharespdef'] = numeros_validar($_REQUEST['saiu05fecharespdef']);
$_REQUEST['saiu05horarespdef'] = numeros_validar($_REQUEST['saiu05horarespdef']);
$_REQUEST['saiu05minrespdef'] = numeros_validar($_REQUEST['saiu05minrespdef']);
$_REQUEST['saiu05diasproc'] = numeros_validar($_REQUEST['saiu05diasproc']);
$_REQUEST['saiu05minproc'] = numeros_validar($_REQUEST['saiu05minproc']);
$_REQUEST['saiu05diashabproc'] = numeros_validar($_REQUEST['saiu05diashabproc']);
$_REQUEST['saiu05minhabproc'] = numeros_validar($_REQUEST['saiu05minhabproc']);
$_REQUEST['saiu05idmoduloproc'] = numeros_validar($_REQUEST['saiu05idmoduloproc']);
$_REQUEST['saiu05identificadormod'] = numeros_validar($_REQUEST['saiu05identificadormod']);
$_REQUEST['saiu05numradicado'] = numeros_validar($_REQUEST['saiu05numradicado']);
$_REQUEST['saiu05evalacepta'] = numeros_validar($_REQUEST['saiu05evalacepta']);
$_REQUEST['saiu05evalfecha'] = numeros_validar($_REQUEST['saiu05evalfecha']);
$_REQUEST['saiu05evalamabilidad'] = numeros_validar($_REQUEST['saiu05evalamabilidad']);
$_REQUEST['saiu05evalamabmotivo'] = cadena_Validar($_REQUEST['saiu05evalamabmotivo']);
$_REQUEST['saiu05evalrapidez'] = numeros_validar($_REQUEST['saiu05evalrapidez']);
$_REQUEST['saiu05evalrapidmotivo'] = cadena_Validar($_REQUEST['saiu05evalrapidmotivo']);
$_REQUEST['saiu05evalclaridad'] = numeros_validar($_REQUEST['saiu05evalclaridad']);
$_REQUEST['saiu05evalcalridmotivo'] = cadena_Validar($_REQUEST['saiu05evalcalridmotivo']);
$_REQUEST['saiu05evalresolvio'] = numeros_validar($_REQUEST['saiu05evalresolvio']);
$_REQUEST['saiu05evalsugerencias'] = cadena_Validar($_REQUEST['saiu05evalsugerencias']);
$_REQUEST['saiu05idcategoria'] = numeros_validar($_REQUEST['saiu05idcategoria']);
$_REQUEST['saiu05idorigen'] = numeros_validar($_REQUEST['saiu05idorigen']);
$_REQUEST['saiu05idarchivo'] = numeros_validar($_REQUEST['saiu05idarchivo']);
// Espacio para inicializar otras variables
$bTraerEntorno = false;
if (isset($_REQUEST['csv_separa']) == 0) {
	$_REQUEST['csv_separa'] = ';';
}
if (isset($_REQUEST['bnombre']) == 0) {
	$_REQUEST['bnombre'] = '';
}
if (isset($_REQUEST['bagno']) == 0) {
	$_REQUEST['bagno'] = fecha_agno();
}
if (isset($_REQUEST['bestado']) == 0) {
	$_REQUEST['bestado'] = '';
}
if (isset($_REQUEST['blistar']) == 0) {
	$_REQUEST['blistar'] = 1;
}
if (isset($_REQUEST['bdoc']) == 0) {
	$_REQUEST['bdoc'] = '';
}
if (isset($_REQUEST['btipo']) == 0) {
	$_REQUEST['btipo'] = '';
}
if (isset($_REQUEST['bcategoria']) == 0) {
	$_REQUEST['bcategoria'] = '';
}
if (isset($_REQUEST['btema']) == 0) {
	$_REQUEST['btema'] = '';
}
if (isset($_REQUEST['bref']) == 0) {
	$_REQUEST['bref'] = '';
}
if (isset($_REQUEST['bzona']) == 0) {
	$_REQUEST['bzona'] = '';
	$bTraerEntorno = true;
}
if (isset($_REQUEST['bcentro']) == 0) {
	$_REQUEST['bcentro'] = '';
	$bTraerEntorno = true;
}
if (isset($_REQUEST['bescuela']) == 0) {
	$_REQUEST['bescuela'] = '';
	$bTraerEntorno = true;
}
if (isset($_REQUEST['bprograma']) == 0) {
	$_REQUEST['bprograma'] = '';
	$bTraerEntorno = true;
}
if (isset($_REQUEST['bagnopqrs']) == 0) {
	$_REQUEST['bagnopqrs'] = fecha_agno();
}
if ($bTraerEntorno) {
	$sSQL = 'SELECT unad95escuela, unad95programa, unad95zona, unad95centro FROM unad95entorno WHERE unad95id=' . $idTercero . '';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$_REQUEST['bescuela'] = $fila['unad95escuela'];
		$_REQUEST['bprograma'] = $fila['unad95programa'];
		$_REQUEST['bzona'] = $fila['unad95zona'];
		$_REQUEST['bcead'] = $fila['unad95centro'];
	}
}
if ((int)$_REQUEST['paso'] > 0) {
	//Anotaciones
	if (isset($_REQUEST['bnombre3006']) == 0) {
		$_REQUEST['bnombre3006'] = '';
	}
	//if (isset($_REQUEST['blistar3006'])==0){$_REQUEST['blistar3006']='';}
	//Anexos
	if (isset($_REQUEST['bnombre3007']) == 0) {
		$_REQUEST['bnombre3007'] = '';
	}
	//if (isset($_REQUEST['blistar3007'])==0){$_REQUEST['blistar3007']='';}
}
$_REQUEST['bnombre'] = cadena_Validar($_REQUEST['bnombre']);
$_REQUEST['bagno'] = numeros_validar($_REQUEST['bagno']);
$_REQUEST['bestado'] = numeros_validar($_REQUEST['bestado']);
$_REQUEST['blistar'] = numeros_validar($_REQUEST['blistar']);
$_REQUEST['bdoc'] = cadena_Validar($_REQUEST['bdoc']);
$_REQUEST['btipo'] = numeros_validar($_REQUEST['btipo']);
$_REQUEST['bcategoria'] = numeros_validar($_REQUEST['bcategoria']);
$_REQUEST['btema'] = numeros_validar($_REQUEST['btema']);
$_REQUEST['bref'] = cadena_Validar($_REQUEST['bref']);
$_REQUEST['bzona'] = numeros_validar($_REQUEST['bzona']);
$_REQUEST['bcentro'] = numeros_validar($_REQUEST['bcentro']);
$_REQUEST['bescuela'] = numeros_validar($_REQUEST['bescuela']);
$_REQUEST['bprograma'] = numeros_validar($_REQUEST['bprograma']);
$sTabla05 = 'saiu05solicitud' . f3000_Contenedor($_REQUEST['saiu05agno'], $_REQUEST['saiu05mes']);
$bExiste = $objDB->bexistetabla($sTabla05);
if (!$bExiste) {
	list($sErrorT, $sDebugT)=f3000_TablasMes($_REQUEST['saiu05agno'], $_REQUEST['saiu05mes'], $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugT;
	$sError = $sError . $sErrorT;
}
if ($sError == '') {
	$sAgnoMes = fecha_ArmarAgnoMes($_REQUEST['saiu05agno'], $_REQUEST['saiu05mes']);
	list($sErrorR, $sDebugR) = f3005_RevTabla_saiu05solicitud($sAgnoMes, $objDB);
	$sDebug = $sDebug . $sDebugR;
	$sError = $sError . $sErrorR;
} 
if ($sError != '') {
	$_REQUEST['paso'] = -1;
}
//Si Modifica o Elimina Cargar los campos
if (($_REQUEST['paso'] == 1) || ($_REQUEST['paso'] == 3)) {
	$_REQUEST['saiu05idsolicitante_td'] = $APP->tipo_doc;
	$_REQUEST['saiu05idsolicitante_doc'] = '';
	$_REQUEST['saiu05idinteresado_td'] = $APP->tipo_doc;
	$_REQUEST['saiu05idinteresado_doc'] = '';
	$_REQUEST['saiu05idsupervisor_td'] = $APP->tipo_doc;
	$_REQUEST['saiu05idsupervisor_doc'] = '';
	$_REQUEST['saiu05idresponsable_td'] = $APP->tipo_doc;
	$_REQUEST['saiu05idresponsable_doc'] = '';
	if ($_REQUEST['paso'] == 1) {
		$sSQLcondi = 'saiu05agno=' . $_REQUEST['saiu05agno'] . ' AND saiu05mes=' . $_REQUEST['saiu05mes'] . ' AND saiu05tiporadicado=' . $_REQUEST['saiu05tiporadicado'] . ' AND saiu05consec=' . $_REQUEST['saiu05consec'] . '';
	} else {
		$sSQLcondi = 'saiu05id=' . $_REQUEST['saiu05id'] . '';
	}
	$sSQL = 'SELECT * FROM ' . $sTabla05 . ' WHERE ' . $sSQLcondi;
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' Consulta registro: ' . $sSQL . '<br>';
	}
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$_REQUEST['saiu05agno'] = $fila['saiu05agno'];
		$_REQUEST['saiu05mes'] = $fila['saiu05mes'];
		$_REQUEST['saiu05tiporadicado'] = $fila['saiu05tiporadicado'];
		$_REQUEST['saiu05consec'] = $fila['saiu05consec'];
		$_REQUEST['saiu05id'] = $fila['saiu05id'];
		$_REQUEST['saiu05origenagno'] = $fila['saiu05origenagno'];
		$_REQUEST['saiu05origenmes'] = $fila['saiu05origenmes'];
		$_REQUEST['saiu05origenid'] = $fila['saiu05origenid'];
		$_REQUEST['saiu05dia'] = $fila['saiu05dia'];
		$_REQUEST['saiu05hora'] = $fila['saiu05hora'];
		$_REQUEST['saiu05minuto'] = $fila['saiu05minuto'];
		$_REQUEST['saiu05raddia'] = $fila['saiu05raddia'];
		$_REQUEST['saiu05radhora'] = $fila['saiu05radhora'];
		$_REQUEST['saiu05radmin'] = $fila['saiu05radmin'];
		$_REQUEST['saiu05raddespcalend'] = $fila['saiu05raddespcalend'];
		$_REQUEST['saiu05raddesphab'] = $fila['saiu05raddesphab'];
		$_REQUEST['saiu05estado'] = $fila['saiu05estado'];
		$_REQUEST['saiu05idmedio'] = $fila['saiu05idmedio'];
		$_REQUEST['saiu05idtiposolorigen'] = $fila['saiu05idtiposolorigen'];
		$_REQUEST['saiu05idtemaorigen'] = $fila['saiu05idtemaorigen'];
		$_REQUEST['saiu05idtiposolfin'] = $fila['saiu05idtiposolfin'];
		$_REQUEST['saiu05idtemafin'] = $fila['saiu05idtemafin'];
		$_REQUEST['saiu05idsolicitante'] = $fila['saiu05idsolicitante'];
		$_REQUEST['saiu05idinteresado'] = $fila['saiu05idinteresado'];
		$_REQUEST['saiu05tipointeresado'] = $fila['saiu05tipointeresado'];
		$_REQUEST['saiu05rptaforma'] = $fila['saiu05rptaforma'];
		$_REQUEST['saiu05rptacorreo'] = $fila['saiu05rptacorreo'];
		$_REQUEST['saiu05rptadireccion'] = $fila['saiu05rptadireccion'];
		$_REQUEST['saiu05costogenera'] = $fila['saiu05costogenera'];
		$_REQUEST['saiu05costovalor'] = $fila['saiu05costovalor'];
		$_REQUEST['saiu05costorefpago'] = $fila['saiu05costorefpago'];
		$_REQUEST['saiu05prioridad'] = $fila['saiu05prioridad'];
		$_REQUEST['saiu05idzona'] = $fila['saiu05idzona'];
		$_REQUEST['saiu05cead'] = $fila['saiu05cead'];
		$_REQUEST['saiu05numref'] = $fila['saiu05numref'];
		$_REQUEST['saiu05detalle'] = $fila['saiu05detalle'];
		$_REQUEST['saiu05infocomplemento'] = $fila['saiu05infocomplemento'];
		$_REQUEST['saiu05idunidadresp'] = $fila['saiu05idunidadresp'];
		$_REQUEST['saiu05idequiporesp'] = $fila['saiu05idequiporesp'];
		$_REQUEST['saiu05idsupervisor'] = $fila['saiu05idsupervisor'];
		$_REQUEST['saiu05idresponsable'] = $fila['saiu05idresponsable'];
		$_REQUEST['saiu05idescuela'] = $fila['saiu05idescuela'];
		$_REQUEST['saiu05idprograma'] = $fila['saiu05idprograma'];
		$_REQUEST['saiu05idperiodo'] = $fila['saiu05idperiodo'];
		$_REQUEST['saiu05idcurso'] = $fila['saiu05idcurso'];
		$_REQUEST['saiu05idgrupo'] = $fila['saiu05idgrupo'];
		$_REQUEST['saiu05tiemprespdias'] = $fila['saiu05tiemprespdias'];
		$_REQUEST['saiu05tiempresphoras'] = $fila['saiu05tiempresphoras'];
		$_REQUEST['saiu05fecharespprob'] = $fila['saiu05fecharespprob'];
		$_REQUEST['saiu05respuesta'] = $fila['saiu05respuesta'];
		$_REQUEST['saiu05fecharespdef'] = $fila['saiu05fecharespdef'];
		$_REQUEST['saiu05horarespdef'] = $fila['saiu05horarespdef'];
		$_REQUEST['saiu05minrespdef'] = $fila['saiu05minrespdef'];
		$_REQUEST['saiu05diasproc'] = $fila['saiu05diasproc'];
		$_REQUEST['saiu05minproc'] = $fila['saiu05minproc'];
		$_REQUEST['saiu05diashabproc'] = $fila['saiu05diashabproc'];
		$_REQUEST['saiu05minhabproc'] = $fila['saiu05minhabproc'];
		$_REQUEST['saiu05idmoduloproc'] = $fila['saiu05idmoduloproc'];
		$_REQUEST['saiu05identificadormod'] = $fila['saiu05identificadormod'];
		$_REQUEST['saiu05numradicado'] = $fila['saiu05numradicado'];
		$_REQUEST['saiu05evalacepta'] = $fila['saiu05evalacepta'];
		$_REQUEST['saiu05evalfecha'] = $fila['saiu05evalfecha'];
		$_REQUEST['saiu05evalamabilidad'] = $fila['saiu05evalamabilidad'];
		$_REQUEST['saiu05evalamabmotivo'] = $fila['saiu05evalamabmotivo'];
		$_REQUEST['saiu05evalrapidez'] = $fila['saiu05evalrapidez'];
		$_REQUEST['saiu05evalrapidmotivo'] = $fila['saiu05evalrapidmotivo'];
		$_REQUEST['saiu05evalclaridad'] = $fila['saiu05evalclaridad'];
		$_REQUEST['saiu05evalcalridmotivo'] = $fila['saiu05evalcalridmotivo'];
		$_REQUEST['saiu05evalresolvio'] = $fila['saiu05evalresolvio'];
		$_REQUEST['saiu05evalsugerencias'] = $fila['saiu05evalsugerencias'];
		$_REQUEST['saiu05idcategoria'] = $fila['saiu05idcategoria'];		
		$_REQUEST['saiu05idorigen'] = $fila['saiu05idorigen'];
		$_REQUEST['saiu05idarchivo'] = $fila['saiu05idarchivo'];
		$bcargo = true;
		$_REQUEST['paso'] = 2;
		$_REQUEST['boculta3005'] = 0;
		$bLimpiaHijos = true;
		if ($_REQUEST['saiu05tiporadicado'] != 1) {
			$sError = 'La solicitud que intenta consultar no corresponde a este m&oacute;dulo.';
			$_REQUEST['paso'] = -1;
		}
	} else {
		$_REQUEST['paso'] = 0;
	}
}
//Cerrar
$bCerrando = false;
$saiu05estado = $_REQUEST['saiu05estado'];
if ($_REQUEST['paso'] == 16) {
	$_REQUEST['paso'] = 12;
	$_REQUEST['saiu05estado'] = 0;
	$bCerrando = true;
}
//Insertar o modificar un elemento
if (($_REQUEST['paso'] == 10) || ($_REQUEST['paso'] == 12)) {
	$bMueveScroll = true;
	list($_REQUEST, $sError, $iTipoError, $sDebugGuardar) = f3005_db_GuardarV2($_REQUEST, $objDB, $bDebug, $idTercero);
	$sDebug = $sDebug . $sDebugGuardar;
	if ($sError == '') {
		$sError = '<b>' . $ETI['msg_itemguardado'] . '</b>';
		$iTipoError = 1;
	} else  {
		$_REQUEST['saiu05estado'] = $saiu05estado;
	}
}
// Cambio de consecutivo.
if ($_REQUEST['paso'] == 93) {
	$_REQUEST['paso'] = 2;
	$_REQUEST['saiu05consec_nuevo'] = numeros_validar($_REQUEST['saiu05consec_nuevo']);
	if ($_REQUEST['saiu05consec_nuevo'] == '') {
		$sError = $ERR['saiu05consec'];
	}
	if ($sError == '') {
		list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 8, $idTercero, $objDB);
		if (!$bDevuelve) {
			$sError = $ERR['8'] . ' [Mod ' . $iCodModulo . ']';
		}
	}
	if ($sError == '') {
		//Ver que el consecutivo no exista.
		$sSQL = 'SELECT saiu05id FROM ' . $sTabla05 . ' WHERE saiu05consec=' . $_REQUEST['saiu05consec_nuevo'] . ' AND saiu05tiporadicado=' . $_REQUEST['saiu05tiporadicado'] . ' AND saiu05mes=' . $_REQUEST['saiu05mes'] . ' AND saiu05agno=' . $_REQUEST['saiu05agno'] . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$sError = 'El consecutivo ' . $_REQUEST['saiu05consec_nuevo'] . ' ya existe';
		}
	}
	if ($sError == '') {
		//Aplicar el cambio.
		$sSQL = 'UPDATE ' . $sTabla05 . ' SET saiu05consec=' . $_REQUEST['saiu05consec_nuevo'] . ' WHERE saiu05id=' . $_REQUEST['saiu05id'] . '';
		$tabla = $objDB->ejecutasql($sSQL);
		$sDetalle = 'Cambia el consecutivo de ' . $_REQUEST['saiu05consec'] . ' a ' . $_REQUEST['saiu05consec_nuevo'] . '';
		$_REQUEST['saiu05consec'] = $_REQUEST['saiu05consec_nuevo'];
		$_REQUEST['saiu05consec_nuevo'] = '';
		seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 8, $_REQUEST['saiu05id'], $sDetalle, $objDB);
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
		list($sError, $iTipoError, $sDebugElimina) = f3005_db_Eliminar($_REQUEST['saiu05agno'], $_REQUEST['saiu05mes'], $_REQUEST['saiu05id'], $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugElimina;
	}
	if ($sError == '') {
		$_REQUEST['paso'] = -1;
		$sError = $ETI['msg_itemeliminado'];
		$iTipoError = 1;
	}
}
// Reasignar responsable.
if ($_REQUEST['paso'] == 17) {
	$_REQUEST['paso'] = 2;
	$bMueveScroll = true;
	$sTabla10 = 'saiu10cambioresponsable' . f3000_Contenedor($_REQUEST['saiu05agno'], $_REQUEST['saiu05mes']);	
	$bExiste = $objDB->bexistetabla($sTabla10);
	if (!$bExiste) {
		$sError = $sError . 'No ha sido posible acceder al contenedor de datos de cambio responsable '. $sTabla05;
	}
	if ($sError == '') {
		$bCambiaLider = false;
		$saiu05idunidadresp = $_REQUEST['saiu05idunidadresp'];
		$saiu05idequiporesp = $_REQUEST['saiu05idequiporesp'];
		$saiu05idsupervisor = $_REQUEST['saiu05idsupervisor'];
		$sSQL = 'SELECT bita27id, bita27idlider, bita27idunidadfunc FROM bita27equipotrabajo WHERE bita27idlider=' . $_REQUEST['saiu05idresponsablefin'] . ' AND bita27activo=1 ';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			$sSQL = 'UPDATE ' . $sTabla05 . ' SET saiu05idunidadresp=' . $fila['bita27idunidadfunc'] . ', saiu05idequiporesp=' . $fila['bita27id'] . 
			', saiu05idsupervisor=' . $fila['bita27idlider'] . ', saiu05idresponsable=' . $_REQUEST['saiu05idresponsablefin'] . 
			' WHERE saiu05id=' . $_REQUEST['saiu05id'] . '';
			$bCambiaLider = true;
			$saiu05idunidadresp = $fila['bita27idunidadfunc'];
			$saiu05idequiporesp = $fila['bita27id'];
			$saiu05idsupervisor = $fila['bita27idlider'];
		} else {
			$sSQL = 'UPDATE ' . $sTabla05 . ' SET saiu05idresponsable=' . $_REQUEST['saiu05idresponsablefin'] . ' WHERE saiu05id=' . $_REQUEST['saiu05id'] . '';
		}
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' Consulta reasignación: '.$sSQL.'<br>';
		}
		$result = $objDB->ejecutasql($sSQL);
		if ($result==false){
			$sError=$sError.$ERR['saiu05idresponsablefin'].'';
		} else {
			seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 3, $_REQUEST['saiu05id'], 'Reasigna el responsable ', $objDB);
			if ($bCambiaLider) {
				$_REQUEST['saiu05idunidadresp']=$saiu05idunidadresp;
				$_REQUEST['saiu05idequiporesp']=$saiu05idequiporesp;
				$_REQUEST['saiu05idsupervisor']=$saiu05idsupervisor;
			}
			$_REQUEST['saiu05idresponsable']=$_REQUEST['saiu05idresponsablefin'];
		}
	}
	if ($sError == '') {
		$saiu10consec=tabla_consecutivo($sTabla10, 'saiu10consec', '', $objDB);
		if ($saiu10consec==-1){$sError=$objDB->serror;}
		$saiu10id=tabla_consecutivo($sTabla10,'saiu10id', '', $objDB);
		if ($saiu10id==-1){$sError=$objDB->serror;}
		if ($sError=='') {
			$iHoy=fecha_DiaMod();
			$iHora=fecha_hora();
			$iMinuto=fecha_minuto();
			$sCampos3010='saiu10idsolicitud, saiu10consec, saiu10id, 
			saiu10idresporigen, saiu10idrespfin, saiu10idusuario, 
			saiu10fecha, saiu10hora, saiu10minuto';
			$sValores3010=''.$_REQUEST['saiu05id'].', '.$saiu10consec.', '.$saiu10id.', 
			'.$_REQUEST['saiu05idresponsable'].', '.$_REQUEST['saiu05idresponsablefin'].', '.$idTercero.', 
			'.$iHoy.', '.$iHora.', '.$iMinuto.'';
			$sSQL = 'INSERT INTO ' . $sTabla10 . ' (' . $sCampos3010 . ') VALUES (' . $sValores3010 . ');';
			$result = $objDB->ejecutasql($sSQL);
			if ($result==false){
				$sError=$sError.$ERR['falla_guardar'].' [3010] ..<!-- '.$sSQL.' -->';
			} else {
				seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 2, $saiu10id, 'Agrega cambio de responsable ', $objDB);
				$sError = '<b>Se ha realizado la reasignaci&oacute;n.</b>';
				$iTipoError = 1;
				$sContenedor = fecha_ArmarAgnoMes($_REQUEST['saiu05agno'], $_REQUEST['saiu05mes']);
				list($sMensaje, $sErrorE, $sDebugE) = f3005_EnviaCorreosSolicitud($_REQUEST, $sContenedor, $objDB, $bDebug, true, false);
				$sError = $sError . $sErrorE;
				$sDebug = $sDebug . $sDebugE;
			}
		}
	}
}
// Reenviar notificación
if ($_REQUEST['paso'] == 22) {
	$_REQUEST['paso'] = 2;
	$bMueveScroll = true;
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' Iniciando llamada al envio de la notificaci&oacute;n.<br>';
	}
	$sContenedor = fecha_ArmarAgnoMes($_REQUEST['saiu05agno'], $_REQUEST['saiu05mes']);
	list($sMensaje, $sErrorE, $sDebugE) = f3005_EnviaCorreosSolicitud($_REQUEST, $sContenedor, $objDB, $bDebug, false, true);
	$sError = $sError . $sErrorE;
	$sDebug = $sDebug . $sDebugE;
}
// Actualiza atiende
if ($_REQUEST['paso'] == 27) {
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' Consultando informaci&oacute;n de responsables.<br>';
	}
	if ($_REQUEST['saiu05estado'] < 7){
		list($_REQUEST, $sErrorE, $iTipoError, $sDebugGuardar) = f3005_ActualizarAtiende($_REQUEST, $objDB, $bDebug, $idTercero);
		$sError = $sError . $sErrorE;
		$sDebug = $sDebug . $sDebugGuardar;
	} else {
		$sError = $sError . $ETI['saiu05cerrada'];
	}
}
//limpiar la pantalla
$iViaWeb = 3;
if ($_REQUEST['paso'] == -1) {
	$_REQUEST['saiu05agno'] = $iAgno;
	$_REQUEST['saiu05mes'] = $iMes;
	$_REQUEST['saiu05tiporadicado'] = 1;
	$_REQUEST['saiu05consec'] = '';
	$_REQUEST['saiu05consec_nuevo'] = '';
	$_REQUEST['saiu05id'] = '';
	$_REQUEST['saiu05origenagno'] = '';
	$_REQUEST['saiu05origenmes'] = '';
	$_REQUEST['saiu05origenid'] = 0;
	$_REQUEST['saiu05dia'] = ''; //fecha_hoy();
	$_REQUEST['saiu05hora'] = fecha_hora();
	$_REQUEST['saiu05minuto'] = fecha_minuto();
	$_REQUEST['saiu05raddia'] = '';
	$_REQUEST['saiu05radhora'] = '';
	$_REQUEST['saiu05radmin'] = '';
	$_REQUEST['saiu05raddespcalend'] = '';
	$_REQUEST['saiu05raddesphab'] = 0;
	$_REQUEST['saiu05estado'] = -1;
	$_REQUEST['saiu05idmedio'] = 0;
	$_REQUEST['saiu05idtiposolorigen'] = '';
	$_REQUEST['saiu05idtemaorigen'] = '';
	$_REQUEST['saiu05idtiposolfin'] = '';
	$_REQUEST['saiu05idtemafin'] = '';
	$_REQUEST['saiu05idsolicitante'] = 0; //$idTercero;
	$_REQUEST['saiu05idsolicitante_td'] = $APP->tipo_doc;
	$_REQUEST['saiu05idsolicitante_doc'] = '';
	$_REQUEST['saiu05idinteresado'] = 0; //$idTercero;
	$_REQUEST['saiu05idinteresado_td'] = $APP->tipo_doc;
	$_REQUEST['saiu05idinteresado_doc'] = '';
	$_REQUEST['saiu05tipointeresado'] = '';
	$_REQUEST['saiu05rptaforma'] = 0;
	$_REQUEST['saiu05rptacorreo'] = '';
	$_REQUEST['saiu05rptadireccion'] = '';
	$_REQUEST['saiu05costogenera'] = 0;
	$_REQUEST['saiu05costovalor'] = 0;
	$_REQUEST['saiu05costorefpago'] = '';
	$_REQUEST['saiu05prioridad'] = '';
	$_REQUEST['saiu05idzona'] = '';
	$_REQUEST['saiu05cead'] = '';
	$_REQUEST['saiu05numref'] = '';
	$_REQUEST['saiu05detalle'] = '';
	$_REQUEST['saiu05infocomplemento'] = '';
	$_REQUEST['saiu05idunidadresp'] = 0;
	$_REQUEST['saiu05idequiporesp'] = 0;
	$_REQUEST['saiu05idsupervisor'] = 0; //$idTercero;
	$_REQUEST['saiu05idsupervisor_td'] = $APP->tipo_doc;
	$_REQUEST['saiu05idsupervisor_doc'] = '';
	$_REQUEST['saiu05idresponsable'] = 0; //$idTercero;
	$_REQUEST['saiu05idresponsable_td'] = $APP->tipo_doc;
	$_REQUEST['saiu05idresponsable_doc'] = '';
	$_REQUEST['saiu05idescuela'] = '';
	$_REQUEST['saiu05idprograma'] = '';
	$_REQUEST['saiu05idperiodo'] = '';
	$_REQUEST['saiu05idcurso'] = '';
	$_REQUEST['saiu05idgrupo'] = '';
	$_REQUEST['saiu05tiemprespdias'] = '';
	$_REQUEST['saiu05tiempresphoras'] = '';
	$_REQUEST['saiu05fecharespprob'] = fecha_hoy();
	$_REQUEST['saiu05respuesta'] = '';
	$_REQUEST['saiu05fecharespdef'] = 0;
	$_REQUEST['saiu05horarespdef'] = 0;
	$_REQUEST['saiu05minrespdef'] = 0;
	$_REQUEST['saiu05diasproc'] = 0;
	$_REQUEST['saiu05minproc'] = 0;
	$_REQUEST['saiu05diashabproc'] = 0;
	$_REQUEST['saiu05minhabproc'] = 0;
	$_REQUEST['saiu05idmoduloproc'] = 0;
	$_REQUEST['saiu05identificadormod'] = '';
	$_REQUEST['saiu05numradicado'] = 0;
	$_REQUEST['saiu05evalacepta'] = -1;
	$_REQUEST['saiu05evalfecha'] = fecha_hoy();
	$_REQUEST['saiu05evalamabilidad'] = 0;
	$_REQUEST['saiu05evalamabmotivo'] = '';
	$_REQUEST['saiu05evalrapidez'] = 0;
	$_REQUEST['saiu05evalrapidmotivo'] = '';
	$_REQUEST['saiu05evalclaridad'] = 0;
	$_REQUEST['saiu05evalcalridmotivo'] = '';
	$_REQUEST['saiu05evalresolvio'] = 0;
	$_REQUEST['saiu05evalsugerencias'] = '';
	$_REQUEST['saiu05idcategoria'] = '';
	$_REQUEST['saiu05idorigen'] = 0;
	$_REQUEST['saiu05idarchivo'] = 0;
	$_REQUEST['paso'] = 0;
}
if ($bLimpiaHijos) {
	$_REQUEST['saiu06idsolicitud'] = '';
	$_REQUEST['saiu06consec'] = '';
	$_REQUEST['saiu06id'] = '';
	$_REQUEST['saiu06anotacion'] = '';
	$_REQUEST['saiu06visible'] = 'N';
	$_REQUEST['saiu06descartada'] = 'N';
	$_REQUEST['saiu06idorigen'] = 0;
	$_REQUEST['saiu06idarchivo'] = 0;
	$_REQUEST['saiu06idusuario'] = $_SESSION['unad_id_tercero'];
	$_REQUEST['saiu06idusuario_td'] = $APP->tipo_doc;
	$_REQUEST['saiu06idusuario_doc'] = '';
	$_REQUEST['saiu06fecha'] = $iHoy; // fecha_hoy();
	$_REQUEST['saiu06hora'] = fecha_hora();
	$_REQUEST['saiu06minuto'] = fecha_minuto();
	$_REQUEST['saiu07idsolicitud'] = '';
	$_REQUEST['saiu07consec'] = '';
	$_REQUEST['saiu07id'] = '';
	$_REQUEST['saiu07idtipoanexo'] = '';
	$_REQUEST['saiu07detalle'] = '';
	$_REQUEST['saiu07idorigen'] = 0;
	$_REQUEST['saiu07idarchivo'] = 0;
	$_REQUEST['saiu07idusuario'] = 0; //$idTercero;
	$_REQUEST['saiu07idusuario_td'] = $APP->tipo_doc;
	$_REQUEST['saiu07idusuario_doc'] = '';
	$_REQUEST['saiu07fecha'] = ''; //fecha_hoy();
	$_REQUEST['saiu07hora'] = fecha_hora();
	$_REQUEST['saiu07minuto'] = fecha_minuto();
	$_REQUEST['saiu07estado'] = 0;
	$_REQUEST['saiu07idvalidad'] = 0; //$idTercero;
	$_REQUEST['saiu07idvalidad_td'] = $APP->tipo_doc;
	$_REQUEST['saiu07idvalidad_doc'] = '';
	$_REQUEST['saiu07fechavalida'] = ''; //fecha_hoy();
	$_REQUEST['saiu07horavalida'] = fecha_hora();
	$_REQUEST['saiu07minvalida'] = fecha_minuto();
}
//AQUI SE DEBEN CARGAR TODOS LOS DATOS QUE LA FORMA NECESITE.
$bPuedeAbrir = false;
$bPuedeEliminar = false;
$bPuedeGuardar = true;
$bPuedeCerrar = false;
$bHayImprimir = false;
$bConMedio = false;
$bPuedeEditar = false;
$bMuestraAdicionales = false;
$bEsBorrador = false;
$bEsSupervisor = false;
$sScriptImprime = 'imprimeexcel()';
$sClaseImprime = 'iExcel';
if ((int)$_REQUEST['paso'] > 0) {
	if ($_REQUEST['saiu05idmedio'] != $iViaWeb) {
		$bConMedio = false;
	}
} else {
	$bPuedeEditar = true;
}
if ($idTercero == $_REQUEST['saiu05idsupervisor']) {
	$bEsSupervisor = true;
}
if ($seg_1707 == 1) {
	$bPuedeEditar = true;
	$bEsSupervisor = true;
}
if ($_REQUEST['saiu05estado'] == -1) {
	$bEsBorrador = true;
}
if ($_REQUEST['saiu05estado'] == 7) {
	$bPuedeGuardar = false;
	$bPuedeEditar = false;
}
//DATOS PARA COMPLETAR EL FORMULARIO
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
$seg_4 = 0;
$seg_5 = 0;
$seg_6 = 0;
$seg_8 = 0;
$seg_12 = 0;
list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 12, $idTercero, $objDB);
if ($bDevuelve) {
	$seg_12 = 1;
}
list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 6, $idTercero, $objDB);
if ($bDevuelve) {
	$seg_6 = 1;
}
if ($seg_6 == 1) {
	$bHayImprimir = true;
}
if ($_REQUEST['paso'] > 0) {
	$bDevuelve = false;
	//list($bDevuelve, $sDebugP)=seg_revisa_permisoV3($iCodModulo, 5, $idTercero, $objDB);
	if ($bDevuelve) {
		$seg_5 = 1;
	}
	list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 4, $idTercero, $objDB);
	if ($bDevuelve) {
		$seg_4 = 1;
	}
	if ($seg_4 == 1) {
		if ($_REQUEST['saiu05estado'] == -1) {
			$bPuedeEliminar = true;
		}
	}
}
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
$iAgnoIni = 2020;
$iAgnoFin = fecha_agno();
$html_InfoContacto = '';
$html_personal = '';
$saiu05estado_nombre = '';
list($saiu05estado_nombre, $sErrorDet) = tabla_campoxid('saiu11estadosol', 'saiu11nombre', 'saiu11id', $_REQUEST['saiu05estado'], '{' . $ETI['msg_sindato'] . '}', $objDB);
$html_saiu05estado = html_oculto('saiu05estado', $_REQUEST['saiu05estado'], $saiu05estado_nombre);
list($saiu05idzona_nombre, $sErrorDet) = tabla_campoxid('unad23zona', 'unad23nombre', 'unad23id', $_REQUEST['saiu05idzona'], '{' . $ETI['msg_sindato'] . '}', $objDB);
$html_saiu05idzona = html_oculto('saiu05idzona', $_REQUEST['saiu05idzona'], $saiu05idzona_nombre);
list($saiu05cead_nombre, $sErrorDet) = tabla_campoxid('unad24sede', 'unad24nombre', 'unad24id', $_REQUEST['saiu05cead'], '{' . $ETI['msg_sindato'] . '}', $objDB);
$html_saiu05cead = html_oculto('saiu05cead', $_REQUEST['saiu05cead'], $saiu05cead_nombre);
list($saiu05idescuela_nombre, $sErrorDet) = tabla_campoxid('core12escuela', 'core12nombre', 'core12id', $_REQUEST['saiu05idescuela'], '{' . $ETI['msg_sindato'] . '}', $objDB);
$html_saiu05idescuela = html_oculto('saiu05idescuela', $_REQUEST['saiu05idescuela'], $saiu05idescuela_nombre);
list($saiu05idprograma_nombre, $sErrorDet) = tabla_campoxid('core09programa', 'core09nombre', 'core09id', $_REQUEST['saiu05idprograma'], '{' . $ETI['msg_sindato'] . '}', $objDB);
$html_saiu05idprograma = html_oculto('saiu05idprograma', $_REQUEST['saiu05idprograma'], $saiu05idprograma_nombre);
$html_saiu05fecharad = '<b>'. $ETI['et_estadorad'] . '</b>';
if (!$bEsBorrador) {
	$iFechaRad = fecha_ArmarNumero($_REQUEST['saiu05dia'], $_REQUEST['saiu05mes'], $_REQUEST['saiu05agno']);
	$iFechaRad = fecha_NumSumarDias($iFechaRad, $_REQUEST['saiu05raddesphab']);
	$iFechaRad = fecha_desdenumero($iFechaRad);
	$html_saiu05fecharad = '<b>'. $iFechaRad .'</b>';
}
if ($bConMedio) {
	//list($saiu05idmedio_nombre, $sErrorDet) = tabla_campoxid('bita01tiposolicitud', 'bita01nombre', 'bita01id', $_REQUEST['saiu05idmedio'], '{' . $ETI['msg_sindato'] . '}', $objDB);
	//$html_saiu05idmedio = html_oculto('saiu05idmedio', $_REQUEST['saiu05idmedio'], $saiu05idmedio_nombre);
	$objCombos->nuevo('saiu05idmedio', $_REQUEST['saiu05idmedio'], true, '{' . $ETI['msg_seleccione'] . '}');
	$sSQL = 'SELECT bita01id AS id, bita01nombre AS nombre FROM bita01tiposolicitud ORDER BY bita01nombre';
	$html_saiu05idmedio = $objCombos->html($sSQL, $objDB);
}
if ($bPuedeEditar) {
	$objCombos->nuevo('saiu05idtiposolorigen', $_REQUEST['saiu05idtiposolorigen'], true, '{' . $ETI['msg_seleccione'] . '}');
	$objCombos->sAccion = 'carga_combo_saiu05idtemaorigen();';
	$objCombos->iAncho = 370;
	$sSQL = 'SELECT saiu02id AS id, saiu02titulo AS nombre FROM saiu02tiposol WHERE saiu02id>0 ORDER BY saiu02titulo';
	$html_saiu05idtiposolorigen = $objCombos->html($sSQL, $objDB);
	$html_saiu05idtemaorigen = f3005_HTMLComboV2_saiu05idtemaorigen($objDB, $objCombos, $_REQUEST['saiu05idtemaorigen'], $_REQUEST['saiu05idtiposolorigen']);
} else {
	list($saiu05idtiposolorigen_nombre, $sErrorDet) = tabla_campoxid('saiu02tiposol', 'saiu02titulo', 'saiu02id', $_REQUEST['saiu05idtiposolorigen'], '{' . $ETI['msg_sindato'] . '}', $objDB);
	$html_saiu05idtiposolorigen = html_oculto('saiu05idtiposolorigen', $_REQUEST['saiu05idtiposolorigen'], $saiu05idtiposolorigen_nombre);
	list($saiu05idtemaorigen_nombre, $sErrorDet) = tabla_campoxid('saiu03temasol', 'saiu03titulo', 'saiu03id', $_REQUEST['saiu05idtemaorigen'], '{' . $ETI['msg_sindato'] . '}', $objDB);
	$html_saiu05idtemaorigen = html_oculto('saiu05idtemaorigen', $_REQUEST['saiu05idtemaorigen'], $saiu05idtemaorigen_nombre);
}
list($saiu05idsolicitante_rs, $_REQUEST['saiu05idsolicitante'], $_REQUEST['saiu05idsolicitante_td'], $_REQUEST['saiu05idsolicitante_doc']) = html_tercero($_REQUEST['saiu05idsolicitante_td'], $_REQUEST['saiu05idsolicitante_doc'], $_REQUEST['saiu05idsolicitante'], 0, $objDB);
list($saiu05idinteresado_rs, $_REQUEST['saiu05idinteresado'], $_REQUEST['saiu05idinteresado_td'], $_REQUEST['saiu05idinteresado_doc']) = html_tercero($_REQUEST['saiu05idinteresado_td'], $_REQUEST['saiu05idinteresado_doc'], $_REQUEST['saiu05idinteresado'], 0, $objDB);

$objCombos->nuevo('saiu05costogenera', $_REQUEST['saiu05costogenera'], false, '{' . $ETI['msg_seleccione'] . '}');
$objCombos->sAccion = 'ajustarcosto()';
$objCombos->SiNo($ETI['si'], $ETI['no'], 1, 0);
$html_saiu05costogenera = $objCombos->html('', $objDB);
$bOculto = true;
if ((int)$_REQUEST['paso'] == 0) {
	$bOculto = false;
}
$html_saiu05idsolicitante = html_DivTerceroV8('saiu05idsolicitante', $_REQUEST['saiu05idsolicitante_td'], $_REQUEST['saiu05idsolicitante_doc'], $bOculto, $objDB, $objCombos, 0, $ETI['ing_doc']);
$saiu05idunidadresp_nombre = '&nbsp;';
if ($_REQUEST['saiu05idunidadresp'] != '') {
	if ((int)$_REQUEST['saiu05idunidadresp'] == 0) {
		$saiu05idunidadresp_nombre = '{' . $ETI['msg_sindato'] . '}';
	} else {
		list($saiu05idunidadresp_nombre, $sErrorDet) = tabla_campoxid('unae26unidadesfun', 'unae26nombre', 'unae26id', $_REQUEST['saiu05idunidadresp'], '{' . $ETI['msg_sindato'] . '}', $objDB);
	}
}
$html_saiu05idunidadresp = html_oculto('saiu05idunidadresp', $_REQUEST['saiu05idunidadresp'], $saiu05idunidadresp_nombre);
$saiu05idequiporesp_nombre = '&nbsp;';
if ($_REQUEST['saiu05idequiporesp'] != '') {
	if ((int)$_REQUEST['saiu05idequiporesp'] == 0) {
		$saiu05idequiporesp_nombre = '{' . $ETI['msg_sindato'] . '}';
	} else {
		list($saiu05idequiporesp_nombre, $sErrorDet) = tabla_campoxid('bita27equipotrabajo', 'bita27nombre', 'bita27id', $_REQUEST['saiu05idequiporesp'], '{' . $ETI['msg_sindato'] . '}', $objDB);
	}
}
$html_saiu05idequiporesp = html_oculto('saiu05idequiporesp', $_REQUEST['saiu05idequiporesp'], $saiu05idequiporesp_nombre);
$saiu05idsupervisor_rs='&nbsp;';
list($saiu05idsupervisor_rs, $_REQUEST['saiu05idsupervisor'], $_REQUEST['saiu05idsupervisor_td'], $_REQUEST['saiu05idsupervisor_doc']) = html_tercero($_REQUEST['saiu05idsupervisor_td'], $_REQUEST['saiu05idsupervisor_doc'], $_REQUEST['saiu05idsupervisor'], 0, $objDB);
if ($saiu05idsupervisor_rs == '') {
	$saiu05idsupervisor_rs = '{' . $ETI['msg_sindato'] . '}';
}
list($saiu05idresponsable_rs, $_REQUEST['saiu05idresponsable'], $_REQUEST['saiu05idresponsable_td'], $_REQUEST['saiu05idresponsable_doc']) = html_tercero($_REQUEST['saiu05idresponsable_td'], $_REQUEST['saiu05idresponsable_doc'], $_REQUEST['saiu05idresponsable'], 0, $objDB);
if ($saiu05idresponsable_rs == '') {
	$saiu05idresponsable_rs = '{' . $ETI['msg_sindato'] . '}';
}
$html_saiu05idresponsablecombo = '<b>' . $saiu05idresponsable_rs . '</b>';
$html_saiu05evalacepta = '';
if ($_REQUEST['saiu05evalfecha'] > 0) {
	if ($_REQUEST['saiu05evalacepta'] == 1) {
		$html_saiu05evalacepta = $ETI['saiu05evalacepta'] . '<br>' . $ETI['saiu05evalfecha'] . ' ' . fecha_desdenumero($_REQUEST['saiu05evalfecha']);
	}
}
if ($bPuedeGuardar) {
	if ($bEsSupervisor) {
		$objCombos->nuevo('saiu05idresponsablefin', $_REQUEST['saiu05idresponsable'], true, '{' . $ETI['msg_seleccione'] . '}');
		$sSQL = 'SELECT TB.bita28idtercero AS id, T2.unad11razonsocial AS nombre
			FROM bita28eqipoparte AS TB, unad11terceros AS T2 
			WHERE  TB.bita28idequipotrab=' . $_REQUEST['saiu05idequiporesp'] . ' AND TB.bita28idtercero=T2.unad11id AND TB.bita28activo="S"
			ORDER BY T2.unad11razonsocial';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' Lista de responsables: '. $sSQL.'<br>ID RESPONSABLE: ' . $_REQUEST['saiu05idresponsable'] .'<br>';
		}
		$html_saiu05idresponsablecombo = $objCombos->html($sSQL, $objDB);
	}
}
if ($bPuedeEditar) {
	$objCombos->nuevo('saiu05tipointeresado', $_REQUEST['saiu05tipointeresado'], false, '{' . $ETI['msg_seleccione'] . '}');
	$sSQL = 'SELECT bita07id AS id, bita07nombre AS nombre FROM bita07tiposolicitante ORDER BY bita07nombre';
	$html_saiu05tipointeresado = $objCombos->html($sSQL, $objDB);
	$objCombos->nuevo('saiu05idcategoria', $_REQUEST['saiu05idcategoria'], true, '{' . $ETI['msg_seleccione'] . '}');
	$sSQL = 'SELECT saiu68id AS id, saiu68nombre AS nombre FROM saiu68categoria WHERE saiu68publica = 1 ORDER BY saiu68nombre';
	$html_saiu05idcategoria = $objCombos->html($sSQL, $objDB);
	$objCombos->nuevo('saiu05rptaforma', $_REQUEST['saiu05rptaforma'], false, '{' . $ETI['msg_seleccione'] . '}');
	$objCombos->sAccion = 'ajustarformarpta()';
	$sSQL = 'SELECT saiu12id AS id, saiu12nombre AS nombre FROM saiu12formarespuesta ORDER BY saiu12nombre';
	$html_saiu05rptaforma = $objCombos->html($sSQL, $objDB);
	if ($_REQUEST['saiu05numref'] != '') { $bMuestraAdicionales = true; }
} else {
	list($saiu05tipointeresado_nombre, $sErrorDet) = tabla_campoxid('bita07tiposolicitante', 'bita07nombre', 'bita07id', $_REQUEST['saiu05tipointeresado'], '{' . $ETI['msg_sindato'] . '}', $objDB);
	$html_saiu05tipointeresado = html_oculto('saiu05tipointeresado', $_REQUEST['saiu05tipointeresado'], $saiu05tipointeresado_nombre);
	list($saiu05idcategoria_nombre, $sErrorDet) = tabla_campoxid('saiu68categoria', 'saiu68nombre', 'saiu68id', $_REQUEST['saiu05idcategoria'], '{' . $ETI['msg_sindato'] . '}', $objDB);
	$html_saiu05idcategoria = html_oculto('saiu05idcategoria', $_REQUEST['saiu05idcategoria'], $saiu05idcategoria_nombre);
	list($saiu05rptaforma_nombre, $sErrorDet) = tabla_campoxid('saiu12formarespuesta', 'saiu12nombre', 'saiu12id', $_REQUEST['saiu05rptaforma'], '{' . $ETI['msg_sindato'] . '}', $objDB);
	$html_saiu05rptaforma = html_oculto('saiu05rptaforma', $_REQUEST['saiu05rptaforma'], $saiu05rptaforma_nombre);
	if ($_REQUEST['saiu05numref'] != '') { $bMuestraAdicionales = true; }
}
if ($bMuestraAdicionales) {
	list($saiu06idusuario_rs, $_REQUEST['saiu06idusuario'], $_REQUEST['saiu06idusuario_td'], $_REQUEST['saiu06idusuario_doc']) = html_tercero($_REQUEST['saiu06idusuario_td'], $_REQUEST['saiu06idusuario_doc'], $_REQUEST['saiu06idusuario'], 0, $objDB);
	$objCombos->nuevo('saiu07idtipoanexo', $_REQUEST['saiu07idtipoanexo'], true, '{' . $ETI['msg_seleccione'] . '}');
	$sSQL = 'SELECT saiu04id AS id, saiu04titulo AS nombre FROM saiu04temaanexo ORDER BY saiu04titulo';
	$html_saiu07idtipoanexo = $objCombos->html($sSQL, $objDB);
	list($saiu07idusuario_rs, $_REQUEST['saiu07idusuario'], $_REQUEST['saiu07idusuario_td'], $_REQUEST['saiu07idusuario_doc']) = html_tercero($_REQUEST['saiu07idusuario_td'], $_REQUEST['saiu07idusuario_doc'], $_REQUEST['saiu07idusuario'], 0, $objDB);
	list($saiu07estado_nombre, $sErrorDet) = tabla_campoxid('saiu14estadoanexo', 'saiu14nombre', 'saiu14id', $_REQUEST['saiu07estado'], '{' . $ETI['msg_sindato'] . '}', $objDB);
	$html_saiu07estado = html_oculto('saiu07estado', $_REQUEST['saiu07estado'], $saiu07estado_nombre);
	list($saiu07idvalidad_rs, $_REQUEST['saiu07idvalidad'], $_REQUEST['saiu07idvalidad_td'], $_REQUEST['saiu07idvalidad_doc']) = html_tercero($_REQUEST['saiu07idvalidad_td'], $_REQUEST['saiu07idvalidad_doc'], $_REQUEST['saiu07idvalidad'], 0, $objDB);
}
//Alistar datos adicionales
$id_rpt = 0;
//$id_rpt=reportes_id(_Identificador_Tipo_Reporte_, $objDB);
$objCombos->nuevo('bagno', $_REQUEST['bagno'], false, '{' . $ETI['msg_todos'] . '}');
$objCombos->sAccion = 'paginarf3005()';
$objCombos->numeros($iAgnoIni, $iAgnoFin, 1);
$sSQL = '';
$html_bagno = $objCombos->html($sSQL, $objDB);
$objCombos->nuevo('bestado', $_REQUEST['bestado'], true, '{' . $ETI['msg_todos'] . '}');
$objCombos->addItem('-1', 'Borrador');
$objCombos->addItem('0', 'Solicitado');
$objCombos->addItem('2', 'En tr&aacute;mite');
$objCombos->addItem('7', 'Resuelto');
$objCombos->sAccion = 'paginarf3005()';
$html_bestado = $objCombos->html($sSQL, $objDB);
$bTodos = false;
if ($seg_12 == 1) {
	$bTodos = true;
}
$objCombos->nuevo('blistar', $_REQUEST['blistar'], $bTodos, '{' . $ETI['msg_todos'] . '}');
$objCombos->addItem('1', 'Mis asignaciones');
$objCombos->addItem('2', 'Asignado a mi equipo');
$objCombos->sAccion = 'paginarf3005()';
$html_blistar = $objCombos->html('', $objDB);
$objCombos->nuevo('btipo', $_REQUEST['btipo'], true, '{' . $ETI['msg_todos'] . '}');
$objCombos->sAccion='paginarf3005()';
$sSQL = 'SELECT saiu68id AS id, saiu68nombre AS nombre FROM saiu68categoria WHERE saiu68publica = 1 ORDER BY saiu68nombre';
$html_btipo = $objCombos->html($sSQL, $objDB);
$objCombos->nuevo('bcategoria', $_REQUEST['bcategoria'], true, '{' . $ETI['msg_todos'] . '}');
$objCombos->sAccion = 'carga_combo_btema()';
$sSQL = 'SELECT saiu02id AS id, saiu02titulo AS nombre FROM saiu02tiposol WHERE saiu02id>0 ORDER BY saiu02titulo';
$html_bcategoria = $objCombos->html($sSQL, $objDB);
$objCombos->nuevo('btema', $_REQUEST['btema'], true, '{' . $ETI['msg_todos'] . '}');
$html_btema = $objCombos->html('', $objDB);
$objCombos->nuevo('bagnopqrs', $_REQUEST['bagnopqrs'], false, '{' . $ETI['msg_todos'] . '}');
$objCombos->sAccion = 'paginarf3000pqrs()';
$objCombos->numeros(2020, $iAgnoFin, 1);
$html_bagnopqrs = $objCombos->html('', $objDB);
$objCombos->nuevo('bzona', $_REQUEST['bzona'], true, '{' . $ETI['msg_todos'] . '}');
$objCombos->sAccion = 'carga_combo_bcentro();';
$sSQL = 'SELECT unad23id AS id, unad23nombre AS nombre 
FROM unad23zona 
WHERE unad23conestudiantes="S" 
ORDER BY unad23nombre';
$html_bzona = $objCombos->html($sSQL, $objDB);
$html_bcentro = f3005_HTMLComboV2_bcentro($objDB, $objCombos, $_REQUEST['bcentro'], $_REQUEST['bzona']);
$objCombos->nuevo('bescuela', $_REQUEST['bescuela'], true, '{' . $ETI['msg_todos'] . '}');
$objCombos->sAccion = 'carga_combo_bprograma();';
$sSQL = 'SELECT core12id AS id, core12nombre AS nombre 
FROM core12escuela 
WHERE core12tieneestudiantes="S" AND core12id>0 
ORDER BY core12nombre';
$html_bescuela = $objCombos->html($sSQL, $objDB);
$html_bprograma = f3005_HTMLComboV2_bprograma($objDB, $objCombos, $_REQUEST['bprograma'], $_REQUEST['bescuela']);
/*
$objCombos->nuevo('blistar3006', $_REQUEST['blistar3006'], true, '{'.$ETI['msg_todos'].'}');
$html_blistar3006=$objCombos->comboSistema(3006, 1, $objDB, 'paginarf3006()');
$objCombos->nuevo('blistar3007', $_REQUEST['blistar3007'], true, '{'.$ETI['msg_todos'].'}');
$html_blistar3007=$objCombos->comboSistema(3007, 1, $objDB, 'paginarf3007()');
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
list($html_InfoContacto, $html_personal, $sDebugC) = f3000_InfoContacto($_REQUEST['saiu05idsolicitante'], $idTercero, $objDB, $bDebug);
//Cargar las tablas de datos
$aParametros[0] = ''; //$_REQUEST['p1_3005'];
$aParametros[100] = $idTercero;
$aParametros[101] = $_REQUEST['paginaf3005'];
$aParametros[102] = $_REQUEST['lppf3005'];
$aParametros[103] = $_REQUEST['bnombre'];
$aParametros[104] = $_REQUEST['bagno'];
$aParametros[105] = $_REQUEST['bestado'];
$aParametros[106] = $_REQUEST['blistar'];
$aParametros[107] = $_REQUEST['bdoc'];
$aParametros[108] = $_REQUEST['btipo'];
$aParametros[109] = $_REQUEST['bcategoria'];
$aParametros[110] = $_REQUEST['btema'];
$aParametros[111] = $_REQUEST['bref'];
$aParametros[112] = $_REQUEST['bzona'];
$aParametros[113] = $_REQUEST['bcentro'];
$aParametros[114] = $_REQUEST['bescuela'];
$aParametros[115] = $_REQUEST['bprograma'];
list($sTabla3005, $sErrorT, $sDebugTabla) = f3005_TablaDetalleV2($aParametros, $objDB, $bDebug);
$sError = $sError . $sErrorT;
$sDebug = $sDebug . $sDebugTabla;
$sTabla3000 = '';
$sTabla3006 = '';
$sTabla3007 = '';
$sNumSol = '';
$aParametros3000[0] = $idTercero;
$aParametros3000[1] = $iCodModulo;
$aParametros3000[2] = $_REQUEST['saiu05agno'];
$aParametros3000[3] = $_REQUEST['saiu05id'];
$aParametros3000[100] = $_REQUEST['saiu05idsolicitante'];
$aParametros3000[101] = $_REQUEST['paginaf3000'];
$aParametros3000[102] = $_REQUEST['lppf3000'];
//$aParametros3000[103]=$_REQUEST['bnombre3000'];
//$aParametros3000[104]=$_REQUEST['blistar3000'];
list($sTabla3000, $sDebugTabla) = f3000_TablaDetalleV2($aParametros3000, $objDB, $bDebug);
$sDebug = $sDebug . $sDebugTabla;
$sTabla3000pqrs='';
$aParametros3000[101]=$_REQUEST['paginaf3000pqrs'];
$aParametros3000[102]=$_REQUEST['lppf3000pqrs'];
$aParametros3000[103]=$_REQUEST['bagnopqrs'];
//$aParametros3000[104]=$_REQUEST['blistar3000'];
list($sTabla3000pqrs, $sDebugTabla)=f3000pqrs_TablaDetalleV2($aParametros3000, $objDB, $bDebug);
$sDebug=$sDebug.$sDebugTabla;
if ($_REQUEST['paso'] != 0) {
	//Anotaciones
	$sNumSol = f3000_NumSolicitud($_REQUEST['saiu05agno'], $_REQUEST['saiu05mes'], $_REQUEST['saiu05consec']);
	$aParametros3006[0] = $_REQUEST['saiu05id'];
	$aParametros3006[97] = $_REQUEST['saiu05agno'];
	$aParametros3006[98] = $_REQUEST['saiu05mes'];
	$aParametros3006[100] = $idTercero;
	$aParametros3006[101] = $_REQUEST['paginaf3006'];
	$aParametros3006[102] = $_REQUEST['lppf3006'];
	//$aParametros3006[103]=$_REQUEST['bnombre3006'];
	//$aParametros3006[104]=$_REQUEST['blistar3006'];
	list($sTabla3006, $sDebugTabla) = f3006_TablaDetalleV2($aParametros3006, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugTabla;
	//Anexos
	$aParametros3007[0] = $_REQUEST['saiu05id'];
	$aParametros3007[97] = $_REQUEST['saiu05agno'];
	$aParametros3007[98] = $_REQUEST['saiu05mes'];
	$aParametros3007[100] = $idTercero;
	$aParametros3007[101] = $_REQUEST['paginaf3007'];
	$aParametros3007[102] = $_REQUEST['lppf3007'];
	//$aParametros3007[103]=$_REQUEST['bnombre3007'];
	//$aParametros3007[104]=$_REQUEST['blistar3007'];
	list($sTabla3007, $sDebugTabla) = f3007_TablaDetalleV2($aParametros3007, $objDB, $bDebug);
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
		if ($bPuedeEliminar) {
			$aBotones[$iNumBoton] = array('eliminadato()', $ETI['bt_eliminar'], 'iDelete');
			$iNumBoton++;
		}
		if ($bHayImprimir) {
			$aBotones[$iNumBoton] = array($sScriptImprime, $ETI['bt_imprimir'], $sClaseImprime);
			$iNumBoton++;
		}
		$aBotones[$iNumBoton] = array('limpiapagina()', $ETI['bt_limpiar'], 'iDocument');
		$iNumBoton++;
		// if ($bPuedeGuardar) {
		// 	$aBotones[$iNumBoton] = array('enviaguardar()', $ETI['bt_guardar'], 'iSaveFill');
		// 	$iNumBoton++;
		// }
		if ($bPuedeCerrar) {
			$aBotones[$iNumBoton] = array('enviacerrar()', $ETI['bt_cerrar'], 'iTask');
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
<?php
switch ($_REQUEST['saiu05estado']) {
	case -1:
	if ($_REQUEST['saiu05numref'] != '') {
?>
	function enviacerrar() {
		ModalConfirmV2('<?php echo $ETI['msg_radicar']; ?>', () => {
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
	break;
	case 0:
?>
	function enviatramitar() {
		ModalConfirmV2('<?php echo $ETI['msg_tramitar']; ?>', () => {
			enviaguardar();
		});
	}
<?php
	break;
	case 2:
?>
	function enviaresolver() {
		ModalConfirmV2('<?php echo $ETI['msg_resolver']; ?>', () => {
			enviaguardar();
		});
	}
<?php
	break;
}
?>
<?php
if ($bEsSupervisor) {
	if (!$bEsBorrador) {
?>
	function enviaguardarsupv() {
		window.document.frmedita.bCambiaEst.value = 0;
		enviaguardar();
	}
<?php
	}
}
?>

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
					if (window.document.frmedita.saiu05estado.value < 7) {
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
			if (idcampo == 'saiu05idsolicitante') {
				params[6] = 3005;
				xajax_unad11_Mostrar_v2SAI(params);
			} else {
				xajax_unad11_Mostrar_v2(params);
			}
		} else {
			document.getElementById(idcampo).value = 0;
			document.getElementById('div_' + idcampo).innerHTML = '&nbsp;';
			paginarf3000();
			paginarf3000pqrs();
		}
	}

	function ter_traerxid(idcampo, vrcampo) {
		let params = new Array();
		params[0] = vrcampo;
		params[1] = idcampo;
		if (params[0] != 0) {
			params[6] = 3005;
			xajax_unad11_TraerXidSAI(params);
		}
	}

	function imprimelista() {
		if (window.document.frmedita.seg_6.value == 1) {
			window.document.frmlista.consulta.value = window.document.frmedita.consulta_3005.value;
			window.document.frmlista.titulos.value = window.document.frmedita.titulos_3005.value;
			window.document.frmlista.nombrearchivo.value = '<?php echo $sTituloModulo; ?>';
			window.document.frmlista.submit();
		} else {
			ModalMensaje("<?php echo $ERR['6']; ?>");
		}
	}

	function asignarvariables() {
		window.document.frmimpp.v0.value = <?php echo $idTercero; ?>;
		window.document.frmimpp.v3.value = window.document.frmedita.bagno.value;
		window.document.frmimpp.v4.value = window.document.frmedita.bestado.value;
		window.document.frmimpp.v5.value = window.document.frmedita.blistar.value;
		window.document.frmimpp.separa.value = window.document.frmedita.csv_separa.value.trim();
	}

	function imprimeexcel() {
		let sError = '';
		if (window.document.frmedita.seg_6.value != 1) {
			sError = "<?php echo $ERR['6']; ?>";
		}
		//if (sError==''){/*Agregar validaciones*/}
		if (sError == '') {
			asignarvariables();
			window.document.frmimpp.action = 'e3005.php';
			window.document.frmimpp.submit();
		} else {
			ModalMensaje(sError);
		}
	}

	function imprimep() {
		if (window.document.frmedita.seg_5.value == 1) {
			asignarvariables();
			window.document.frmimpp.action = '<?php echo $APP->rutacomun; ?>p3005.php';
			window.document.frmimpp.submit();
		} else {
			ModalMensaje("<?php echo $ERR['5']; ?>");
		}
	}
<?php
if ($bPuedeEliminar) {
?>
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
<?php
}
?>
	function RevisaLlave() {
		let datos = new Array();
		datos[1] = window.document.frmedita.saiu05agno.value;
		datos[2] = window.document.frmedita.saiu05mes.value;
		datos[3] = window.document.frmedita.saiu05tiporadicado.value;
		datos[4] = window.document.frmedita.saiu05consec.value;
		if ((datos[1] != '') && (datos[2] != '') && (datos[3] != '') && (datos[4] != '')) {
			xajax_f3005_ExisteDato(datos);
		}
	}

	function cargadato(llave1, llave2, llave3, llave4) {
		window.document.frmedita.saiu05agno.value = String(llave1);
		window.document.frmedita.saiu05mes.value = String(llave2);
		window.document.frmedita.saiu05tiporadicado.value = String(llave3);
		window.document.frmedita.saiu05consec.value = String(llave4);
		window.document.frmedita.paso.value = 1;
		window.document.frmedita.submit();
	}

	function cargaridf3005(llave1, llave2, llave3) {
		window.document.frmedita.saiu05agno.value = String(llave1);
		window.document.frmedita.saiu05mes.value = String(llave2);
		window.document.frmedita.saiu05id.value = String(llave3);
		window.document.frmedita.paso.value = 3;
		window.document.frmedita.submit();
	}

	function carga_combo_saiu05idtemaorigen() {
		let params = new Array();
		params[0] = window.document.frmedita.saiu05idtiposolorigen.value;
		document.getElementById('div_saiu05idtemaorigen').innerHTML = '<b>Procesando datos, por favor espere...</b><input id="saiu05idtemaorigen" name="saiu05idtemaorigen" type="hidden" value="" />';
		xajax_f3005_Combosaiu05idtemaorigen(params);
	}

	function carga_combo_saiu05idtemafin() {
		let params = new Array();
		params[0] = window.document.frmedita.saiu05idtiposolfin.value;
		document.getElementById('div_saiu05idtemafin').innerHTML = '<b>Procesando datos, por favor espere...</b><input id="saiu05idtemafin" name="saiu05idtemafin" type="hidden" value="" />';
		xajax_f3005_Combosaiu05idtemafin(params);
	}

	function carga_combo_saiu05idequiporesp() {
		let params = new Array();
		params[0] = window.document.frmedita.saiu05idzona.value;
		document.getElementById('div_saiu05idequiporesp').innerHTML = '<b>Procesando datos, por favor espere...</b><input id="saiu05idequiporesp" name="saiu05idequiporesp" type="hidden" value="" />';
		xajax_f3005_Combosaiu05idequiporesp(params);
	}

	function carga_combo_bcentro() {
		let params = new Array();
		params[0] = window.document.frmedita.bzona.value;
		document.getElementById('div_bcentro').innerHTML = '<b>Procesando datos, por favor espere...</b><input id="bcentro" name="bcentro" type="hidden" value="" />';
		xajax_f3005_Combobcentro(params);
	}

	function carga_combo_bprograma() {
		let params = new Array();
		params[0] = window.document.frmedita.bescuela.value;
		document.getElementById('div_bprograma').innerHTML = '<b>Procesando datos, por favor espere...</b><input id="bprograma" name="bprograma" type="hidden" value="" />';
		xajax_f3005_Combobprograma(params);
	}

	function paginarf3005() {
		let params = new Array();
		params[99] = window.document.frmedita.debug.value;
		params[100] = <?php echo $idTercero; ?>;
		params[101] = window.document.frmedita.paginaf3005.value;
		params[102] = window.document.frmedita.lppf3005.value;
		params[103] = window.document.frmedita.bnombre.value;
		params[104] = window.document.frmedita.bagno.value;
		params[105] = window.document.frmedita.bestado.value;
		params[106] = window.document.frmedita.blistar.value;
		params[107] = window.document.frmedita.bdoc.value;
		params[108] = window.document.frmedita.btipo.value;
		params[109] = window.document.frmedita.bcategoria.value;
		params[110] = window.document.frmedita.btema.value;
		params[111] = window.document.frmedita.bref.value;
		params[112] = window.document.frmedita.bzona.value;
		params[113] = window.document.frmedita.bcentro.value;
		params[114] = window.document.frmedita.bescuela.value;
		params[115] = window.document.frmedita.bprograma.value;
		document.getElementById('div_f3005detalle').innerHTML = '<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf3005" name="paginaf3005" type="hidden" value="' + params[101] + '" /><input id="lppf3005" name="lppf3005" type="hidden" value="' + params[102] + '" />';
		xajax_f3005_HtmlTabla(params);
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
		document.getElementById("saiu05agno").focus();
	}

	function buscarV2016(sCampo) {
		window.document.frmedita.iscroll.value = window.scrollY;
		expandesector(98);
		window.document.frmedita.scampobusca.value = sCampo;
		let params = new Array();
		params[1] = sCampo;
		//params[2]=window.document.frmedita.iagno.value;
		//params[3]=window.document.frmedita.itipo.value;
		xajax_f3005_Busquedas(params);
	}

	function Devuelve(sValor) {
		let sCampo = window.document.frmedita.scampobusca.value;
		if (sCampo == 'saiu05idsolicitante') {
			ter_traerxid('saiu05idsolicitante', sValor);
		}
		if (sCampo == 'saiu05idinteresado') {
			ter_traerxid('saiu05idinteresado', sValor);
		}
		if (sCampo == 'saiu05idresponsable') {
			ter_traerxid('saiu05idresponsable', sValor);
		}
		if (sCampo == 'saiu06idusuario') {
			ter_traerxid('saiu06idusuario', sValor);
		}
		if (sCampo == 'saiu07idusuario') {
			ter_traerxid('saiu07idusuario', sValor);
		}
		if (sCampo == 'saiu07idvalidad') {
			ter_traerxid('saiu07idvalidad', sValor);
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
			var sMensaje = 'Lo que quiera decir.';
			//if (sCampo == 'sNombreCampo') {
				//sMensaje = 'Mensaje para otro campo.';
			//}
			divAyuda.innerHTML = sMensaje;
			divAyuda.style.display = 'block';
		}
	}

	function cierraDiv96(ref) {
		let sRetorna = window.document.frmedita.div96v2.value;
		<?php
		if ($_REQUEST['saiu05estado']==2) {
		?>
		if (ref == 3005) {
			if (sRetorna != '') {
				window.document.frmedita.saiu05idorigen.value = window.document.frmedita.div96v1.value;
				window.document.frmedita.saiu05idarchivo.value = sRetorna;
				verboton('beliminasaiu05idarchivo', 'block');
			}
			archivo_lnk(window.document.frmedita.saiu05idorigen.value, window.document.frmedita.saiu05idarchivo.value, 'div_saiu05idarchivo');
		}
		<?php
		}
		?>
		if (ref == 3006) {
			if (sRetorna != '') {
				window.document.frmedita.saiu06idorigen.value = window.document.frmedita.div96v1.value;
				window.document.frmedita.saiu06idarchivo.value = sRetorna;
				verboton('beliminasaiu06idarchivo', 'block');
			}
			archivo_lnk(window.document.frmedita.saiu06idorigen.value, window.document.frmedita.saiu06idarchivo.value, 'div_saiu06idarchivo');
			paginarf3006();
		}
		if (ref == 3007) {
			/*
			if (sRetorna != '') {
				window.document.frmedita.saiu07idorigen.value = window.document.frmedita.div96v1.value;
				window.document.frmedita.saiu07idarchivo.value = sRetorna;
				verboton('beliminasaiu07idarchivo', 'block');
			}
			archivo_lnk(window.document.frmedita.saiu07idorigen.value, window.document.frmedita.saiu07idarchivo.value, 'div_saiu07idarchivo');
			*/
			paginarf3007();
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

	function ajustarformarpta() {
		let iForma = window.document.frmedita.saiu05rptaforma.value;
		let sMuestra = 'none';
		if (iForma == 1) {
			sMuestra = 'block';
		}
		document.getElementById('div_saiu05rptacorreo').style.display = sMuestra;
		sMuestra = 'none';
		if (iForma == 2) {
			sMuestra = 'block';
		}
		document.getElementById('div_saiu05rptadireccion').style.display = sMuestra;
	}

	function ajustarcosto() {
		let iForma = window.document.frmedita.saiu05costogenera.value;
		let sMuestra = 'none';
		if (iForma == 1) {
			sMuestra = 'block';
		}
		document.getElementById('div_saiu05costovalor').style.display = sMuestra;
	}

	function paginarf3000() {
		let params = new Array();
		params[0] = window.document.frmedita.id11.value;
		params[1] = 3005;
		params[2] = window.document.frmedita.saiu05agno.value;
		params[3] = window.document.frmedita.saiu05id.value;
		params[99] = window.document.frmedita.debug.value;
		params[100] = window.document.frmedita.saiu05idsolicitante.value;
		params[101] = window.document.frmedita.paginaf3000.value;
		params[102] = window.document.frmedita.lppf3000.value;
		//params[103]=window.document.frmedita.bnombre3000.value;
		//params[104]=window.document.frmedita.blistar3000.value;
		document.getElementById('div_f3000detalle').innerHTML = '<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf3000" name="paginaf3000" type="hidden" value="' + params[101] + '" /><input id="lppf3000" name="lppf3000" type="hidden" value="' + params[102] + '" />';
		xajax_f3000_HtmlTabla(params);
	}

	function paginarf3000pqrs() {
		let params = new Array();
		params[0] = window.document.frmedita.id11.value;
		params[1] = 3021;
		params[2] = window.document.frmedita.saiu05agno.value;
		params[3] = window.document.frmedita.saiu05id.value;
		params[99] = window.document.frmedita.debug.value;
		params[100] = window.document.frmedita.saiu05idsolicitante.value;
		params[101] = window.document.frmedita.paginaf3000pqrs.value;
		params[102] = window.document.frmedita.lppf3000pqrs.value;
		params[103] = window.document.frmedita.bagnopqrs.value;
		//params[104]=window.document.frmedita.blistar3000.value;
		document.getElementById('div_f3000pqrsdetalle').innerHTML = '<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf3000pqrs" name="paginaf3000pqrs" type="hidden" value="' + params[101] + '" /><input id="lppf3000pqrs" name="lppf3000pqrs" type="hidden" value="' + params[102] + '" />';
		xajax_f3000pqrs_HtmlTabla(params);
	}

	function verinfopersonal(id) {
		let params = new Array();
		params[1] = id;
		document.getElementById('div_infopersonal').innerHTML = '<b>Procesando datos, por favor espere...</b>';
		xajax_f236_TraerInfoPersonal(params);
	}

<?php
if ($_REQUEST['saiu05estado'] == 2) {
?>
	function apruebaidf3007(id07) {
		ModalConfirmV2('El documento ser&aacute; aprobado,<br>Esta seguro de continuar?', () => {
			ejecuta_apruebaidf3007(id07, 1);
		});
	}

	function retiraidf3007(id07) {
		ModalConfirmV2('El documento ser&aacute; desaprobado,<br>Esta seguro de continuar?', () => {
			ejecuta_apruebaidf3007(id07, 0);
		});
	}

	function ejecuta_apruebaidf3007(id07, idProceso) {
		let params = new Array();
		params[0] = window.document.frmedita.saiu05id.value;
		params[1] = idProceso;
		params[2] = '';
		params[3] = id07;
		//params[14]=window.document.frmedita.p1_3007.value;
		params[97] = window.document.frmedita.saiu05agno.value;
		params[98] = window.document.frmedita.saiu05mes.value;
		params[99] = window.document.frmedita.debug.value;
		params[100] = window.document.frmedita.id11.value;
		params[101] = window.document.frmedita.paginaf3007.value;
		params[102] = window.document.frmedita.lppf3007.value;
		document.getElementById('div_f3007detalle').innerHTML = '<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf3007" name="paginaf3007" type="hidden" value="' + params[101] + '" /><input id="lppf3007" name="lppf3007" type="hidden" value="' + params[102] + '" />';
		MensajeAlarmaV2('<?php echo $ETI['msg_ejecutando']; ?>', 2);
		xajax_f3007_AprobarDocumento(params);
	}
<?php
}
?>
<?php
if ($bPuedeGuardar) {
	if ($bEsSupervisor) {
?>
	function enviareasignar() {
		window.document.frmedita.iscroll.value = window.pageYOffset;
		ModalConfirmV2('¿Esta seguro de hacer la reasignaci&oacute;n?', () => {
			ejecuta_enviareasignar();
		});
	}

	function ejecuta_enviareasignar() {
		MensajeAlarmaV2('<?php echo $ETI['msg_ejecutando']; ?>', 2);
		expandesector(98);
		window.document.frmedita.paso.value = 17;
		window.document.frmedita.submit();
	}
<?php
	}
}
?>
	function enviar_notificacion() {
		window.document.frmedita.iscroll.value = window.pageYOffset;
		MensajeAlarmaV2('<?php echo $ETI['msg_ejecutando']; ?>', 2);
		expandesector(98);
		window.document.frmedita.paso.value = 22;
		window.document.frmedita.submit();
	}
<?php
if ($_REQUEST['saiu05estado']==2) {
?>
	function limpia_saiu05idarchivo(){
		window.document.frmedita.saiu05idorigen.value=0;
		window.document.frmedita.saiu05idarchivo.value=0;
		let da_Archivo=document.getElementById('div_saiu05idarchivo');
		da_Archivo.innerHTML='&nbsp;';
		verboton('beliminasaiu05idarchivo','none');
		//paginarf0000();
	}
	function carga_saiu05idarchivo(){
		window.document.frmedita.iscroll.value=window.pageYOffset;
		window.document.frmedita.div96v1.value='';
		window.document.frmedita.div96v2.value='';
		window.document.frmedita.div96v3.value='';
		let saiu05id=window.document.frmedita.saiu05id.value;
		let agno=window.document.frmedita.saiu05agno.value;
		let mes=window.document.frmedita.saiu05mes.value;
		if (mes < 10) {
			mes = '0' + mes;
		}
		document.getElementById('div_96titulo').innerHTML='<h2>'+window.document.frmedita.titulo_3005.value+' - Cargar archivo</h2>';
		document.getElementById('div_96cuerpo').innerHTML='<iframe id="iframe96" src="framearchivodis.php?ref=3005&id='+saiu05id+'&tabla=_'+agno+mes+'" height="400px" width="100%" frameborder="0"></iframe>';
		expandesector(96);
		window.scrollTo(0, 150);
	}
	function eliminasaiu05idarchivo(){
		let did=window.document.frmedita.saiu05id;
		let agno=window.document.frmedita.saiu05agno.value;
		let mes=window.document.frmedita.saiu05mes.value;
		if (confirm("Esta seguro de eliminar el archivo?")){
			xajax_elimina_archivo_saiu05idarchivo(did.value, agno, mes);
			//paginarf0000();
		}
	}
<?php
}
?>
function actualizaratiende() {
	var sError = '';
	if (window.document.frmedita.saiu05id.value == '') {
		sError = 'Por favor seleccione una solicitud.';
	}
	if (sError == '') {
		window.document.frmedita.iscroll.value = window.pageYOffset;
		MensajeAlarmaV2('<?php echo $ETI['msg_ejecutando']; ?>...', 2);
		expandesector(98);
		window.document.frmedita.paso.value = 27;
		window.document.frmedita.submit();
	}
}
function carga_combo_btema() {
	let params = new Array();
	params[0] = window.document.frmedita.bcategoria.value;
	document.getElementById('div_btema').innerHTML = '<b>Procesando datos, por favor espere...</b><input id="btema" name="btema" type="hidden" value="" />';
	xajax_f3005_Combobtema(params);
}
function abrir_tab(evt, sId) {
	evt.preventDefault();
	let i, tabcontent, tablinks;
	tabcontent = document.getElementsByClassName("tabcontent");
	for (i = 0; i < tabcontent.length; i++) {
		tabcontent[i].style.display = "none";
	}
	tablinks = document.getElementsByClassName("tablinks");
	for (i = 0; i < tablinks.length; i++) {
		tablinks[i].className = tablinks[i].className.replace(" active", "");
	}
	document.getElementById(sId).style.display = "flex";
	document.getElementById(sId).style.flexWrap = "wrap";
	evt.currentTarget.className += " active";
}
<?php
if ($bEsSupervisor) {
?>
	function revisaborrador() {
		ModalConfirmV2('<?php echo $ETI['msg_revborrador']; ?>', () => {
			let params = new Array();
			params[99] = window.document.frmedita.debug.value;
			params[100] = <?php echo $idTercero; ?>;
			params[101] = window.document.frmedita.paginaf3005.value;
			params[102] = window.document.frmedita.lppf3005.value;
			params[103] = window.document.frmedita.bnombre.value;
			params[104] = window.document.frmedita.bagno.value;
			params[105] = window.document.frmedita.bestado.value;
			params[106] = window.document.frmedita.blistar.value;
			params[107] = window.document.frmedita.bdoc.value;
			params[108] = window.document.frmedita.btipo.value;
			params[109] = window.document.frmedita.bcategoria.value;
			params[110] = window.document.frmedita.btema.value;
			params[111] = window.document.frmedita.bref.value;
			document.getElementById('div_f3005detalle').innerHTML = '<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf3005" name="paginaf3005" type="hidden" value="' + params[101] + '" /><input id="lppf3005" name="lppf3005" type="hidden" value="' + params[102] + '" />';
			xajax_f3005_db_EliminarBorradores(params);
		});
	}
<?php
}
?>
</script>
<?php
if ($_REQUEST['paso'] != 0) {
?>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>jsi/js3006.js?v=3"></script>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>jsi/js3007.js?v=1"></script>
<?php
}
?>
<form id="frmimpp" name="frmimpp" method="post" action="<?php echo $APP->rutacomun; ?>p3005.php" target="_blank" style="display:none;">
<input id="r" name="r" type="hidden" value="3005" />
<input id="id3005" name="id3005" type="hidden" value="<?php echo $_REQUEST['saiu05id']; ?>" />
<input id="v0" name="v0" type="hidden" value="" />
<input id="v3" name="v3" type="hidden" value="" />
<input id="v4" name="v4" type="hidden" value="" />
<input id="v5" name="v5" type="hidden" value="" />
<input id="v6" name="v6" type="hidden" value="" />
<input id="v7" name="v7" type="hidden" value="" />
<input id="v8" name="v8" type="hidden" value="" />
<input id="v9" name="v9" type="hidden" value="" />
<input id="v10" name="v10" type="hidden" value="" />
<input id="v11" name="v11" type="hidden" value="" />
<input id="v12" name="v12" type="hidden" value="" />
<input id="v13" name="v13" type="hidden" value="" />
<input id="v14" name="v14" type="hidden" value="" />
<input id="v15" name="v15" type="hidden" value="" />
<input id="iformato94" name="iformato94" type="hidden" value="0" />
<input id="separa" name="separa" type="hidden" value="," />
<input id="rdebug" name="rdebug" type="hidden" value="<?php echo $_REQUEST['debug']; ?>"/>
<input id="clave" name="clave" type="hidden" value="" />
</form>
<form id="frmlista" name="frmlista" method="post" action="listados.php" target="_blank" style="display:none;">
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
<input id="bCambiaEst" name="bCambiaEst" type="hidden" value="" />
<div id="div_sector1">
<?php
if ($bBloqueTitulo) {
?>
<div class="titulos">
<div class="titulosD">
<input id="cmdAyuda" name="cmdAyuda" type="button" class="btUpAyuda" onclick="muestraayuda(<?php echo $APP->idsistema . ', ' . $iCodModulo; ?>);" title="<?php echo $ETI['bt_ayuda']; ?>" value="<?php echo $ETI['bt_ayuda']; ?>" />
<?php
if (false) {
if ($bPuedeEliminar) {
?>
<input id="cmdEliminar" name="cmdEliminar" type="button" class="btUpEliminar" onclick="eliminadato();" title="<?php echo $ETI['bt_eliminar']; ?>" value="<?php echo $ETI['bt_eliminar']; ?>" />
<?php
}
}
if ($bHayImprimir) {
?>
<input id="cmdImprimir" name="cmdImprimir" type="button" class="<?php echo $sClaseBoton; ?>" onclick="<?php echo $sScript; ?>" title="<?php echo $ETI['bt_imprimir']; ?>" value="<?php echo $ETI['bt_imprimir']; ?>" />
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
echo $objForma->htmlExpande(3005, $_REQUEST['boculta3005'], $ETI['bt_mostrar'], $ETI['bt_ocultar']);
$sEstiloDiv = '';
if ($_REQUEST['boculta3005'] != 0) {
	$sEstiloDiv = ' style="display:none;"';
}
?>
</div>
<div id="div_p3005"<?php echo $sEstiloDiv; ?>>
<?php
}
//Mostrar formulario para editar
?>
<input id="saiu05origenagno" name="saiu05origenagno" type="hidden" value="<?php echo $_REQUEST['saiu05origenagno']; ?>" />
<input id="saiu05origenmes" name="saiu05origenmes" type="hidden" value="<?php echo $_REQUEST['saiu05origenmes']; ?>" />
<?php
if ($_REQUEST['saiu05origenid'] != 0) {
?>
<label class="Label130">
<?php
echo $ETI['saiu05origenid'];
?>
</label>
<label class="Label130">
<div id="div_saiu05origenid">
<?php
echo html_oculto('saiu05origenid', $_REQUEST['saiu05origenid']);
?>
</div>
</label>
<?php
} else {
?>
<input id="saiu05origenid" name="saiu05origenid" type="hidden" value="<?php echo $_REQUEST['saiu05origenid']; ?>" />
<?php
}
?>
<label class="Label130">
<?php
echo $ETI['saiu05dia'];
?>
</label>
<label class="Label130">
<?php
$et_saiu05dia = fecha_armar($_REQUEST['saiu05dia'], $_REQUEST['saiu05mes'], $_REQUEST['saiu05agno']);
echo html_oculto('saiu05dia', $_REQUEST['saiu05dia'], $et_saiu05dia);
?>
</label>
<label class="Label60">
<?php
echo $ETI['saiu05hora'];
?>
</label>
<div class="campo_HoraMin Label130" id="div_saiu05hora">
<?php
echo html_HoraMin('saiu05hora', $_REQUEST['saiu05hora'], 'saiu05minuto', $_REQUEST['saiu05minuto'], true);
?>
</div>
<label class="Label60">
<?php
echo $ETI['saiu05estado'];
?>
</label>
<label class="Label160">
<div id="div_saiu05estado">
<?php
echo $html_saiu05estado;
?>
</div>
</label>
<?php
if ($bDebug) {
	if (!$bPuedeGuardar) {
?>
<label class="Label130">
<input id="cmdNotificar" name="cmdNotificar" type="button" class="BotonAzul" value="Notificar" onclick="enviar_notificacion()" title="Enviar notificaci&oacute;n" />
</label>
<?php
	}
}
?>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['msg_numsolicitud'];
?>
</label>
<label class="Label130">
<?php
echo '<b>' . $sNumSol . '</b>';
?>
</label>
<input id="saiu05agno" name="saiu05agno" type="hidden" value="<?php echo $_REQUEST['saiu05agno']; ?>" />
<input id="saiu05mes" name="saiu05mes" type="hidden" value="<?php echo $_REQUEST['saiu05mes']; ?>" />
<input id="saiu05tiporadicado" name="saiu05tiporadicado" type="hidden" value="<?php echo $_REQUEST['saiu05tiporadicado']; ?>" />
<input id="saiu05consec" name="saiu05consec" type="hidden" value="<?php echo $_REQUEST['saiu05consec']; ?>" />
<label class="Label60">
<?php
echo $ETI['saiu05id'];
?>
</label>
<label class="Label130">
<?php
echo html_oculto('saiu05id', $_REQUEST['saiu05id'], formato_numero($_REQUEST['saiu05id']));
?>
</label>
<label class="Label60">
<?php
echo $ETI['saiu05idcategoria'];
?>
</label>
<label class="Label160">
<?php
echo $html_saiu05idcategoria;
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu05fecharad'];
?>
</label>
<label class="Label130">
<?php
echo $html_saiu05fecharad;
?>
</label>
<div class="salto1px"></div>
<?php
// Inicio caja - solicitante
?>
<div class="GrupoCampos520">
<label class="TituloGrupo">
<?php
echo $ETI['saiu05idsolicitante'];
?>
</label>
<div class="salto1px"></div>
<input id="saiu05idsolicitante" name="saiu05idsolicitante" type="hidden" value="<?php echo $_REQUEST['saiu05idsolicitante']; ?>" />
<div id="div_saiu05idsolicitante_llaves">
<?php
echo $html_saiu05idsolicitante;
?>
</div>
<div class="salto1px"></div>
<div id="div_saiu05idsolicitante" class="L"><?php echo $saiu05idsolicitante_rs; ?></div>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['saiu05tipointeresado'];
?>
</label>
<label class="Label160">
<?php
echo $html_saiu05tipointeresado;
?>
</label>
<div class="salto1px"></div>
<?php
if ($bConMedio) {
?>
<label class="L">
<?php
echo $ETI['saiu05idmedio'] . ' ' . $html_saiu05idmedio;
?>
</label>
<?php
} else {
?>
<input id="saiu05idmedio" name="saiu05idmedio" type="hidden" value="<?php echo $_REQUEST['saiu05idmedio']; ?>" />
<?php
}
?>
<div class="salto1px"></div>
<div id="div_infopersonal">
<?php
echo $html_personal;
?>
</div>
<div class="salto1px"></div>
<div id="div_contacto">
<?php
echo $html_InfoContacto;
?>
</div>
<div class="salto1px"></div>
</div>
<?php
// Fin caja - solicitante
?>
<div>
<?php
// inicio caja - datos académicos
?>
<div class="GrupoCampos520">
<label class="Label130">
<?php
echo $ETI['saiu05idzona'];
?>
</label>
<label class="Label350">
<?php
echo $html_saiu05idzona;
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu05cead'];
?>
</label>
<label class="Label350">
<?php
echo $html_saiu05cead;
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu05idescuela'];
?>
</label>
<label class="Label350">
<?php
echo $html_saiu05idescuela;
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu05idprograma'];
?>
</label>
<label class="Label350">
<?php
echo $html_saiu05idprograma;
?>
</label>
<div class="salto1px"></div>
</div>
<?php
// Fin caja - datos académicos
?>
<?php
// Inicio caja - numero de referencia
?>
<div class="GrupoCampos520">
<label class="Label200">
<?php
echo $ETI['saiu05numref'];
?>
</label>
<label class="Label200">
<div id="div_saiu05numref">
<?php
echo html_oculto('saiu05numref', $_REQUEST['saiu05numref']);
?>
</div>
</label>
<div class="salto1px"></div>
<?php
if ($_REQUEST['saiu05idmoduloproc'] != 0) {
?>
<label class="Label130">
<?php
echo $ETI['saiu05idmoduloproc'];
?>
</label>
<label class="Label130">
<?php
echo html_oculto('saiu05idmoduloproc', $_REQUEST['saiu05idmoduloproc']);
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu05identificadormod'];
?>
</label>
<label class="Label130">
<?php
echo html_oculto('saiu05identificadormod', $_REQUEST['saiu05identificadormod']);
?>
</label>
<?php
} else {
?>
<input id="saiu05idmoduloproc" name="saiu05idmoduloproc" type="hidden" value="<?php echo $_REQUEST['saiu05idmoduloproc']; ?>" />
<input id="saiu05identificadormod" name="saiu05identificadormod" type="hidden" value="<?php echo $_REQUEST['saiu05identificadormod']; ?>" />
<?php
}
?>
<div class="salto1px"></div>
<?php
if ($_REQUEST['saiu05numradicado'] != 0) {
?>
<label class="Label200">
<?php
echo $ETI['saiu05numradicado'];
?>
</label>
<label class="Label130">
<?php
echo html_oculto('saiu05numradicado', $_REQUEST['saiu05numradicado']);
?>
</label>
<?php
} else {
?>
<input id="saiu05numradicado" name="saiu05numradicado" type="hidden" value="<?php echo $_REQUEST['saiu05numradicado']; ?>" />
<?php
}
?>
<div class="salto1px"></div>
</div>
<?php
// Fin caja - numero de referencia
?>
<?php
// inicio caja - categoría, tema, medio, costos
?>
<div class="GrupoCampos520">
<label class="Label130">
<?php
echo $ETI['saiu05idtiposolorigen'];
?>
</label>
<label class="Label350">
<?php
echo $html_saiu05idtiposolorigen;
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu05idtemaorigen'];
?>
</label>
<label class="Label350">
<div id="div_saiu05idtemaorigen">
<?php
echo $html_saiu05idtemaorigen;
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label250">
<?php
echo $ETI['saiu05rptaforma'];
?>
</label>
<label class="Label160">
<?php
echo $html_saiu05rptaforma;
?>
</label>
<div class="salto1px"></div>
<?php
$sEstilo = ' style="display:none"';
if ($_REQUEST['saiu05rptaforma'] == 1) {
	$sEstilo = ' style="display:block"';
}
?>
<div id="div_saiu05rptacorreo" <?php echo $sEstilo; ?>>
<label class="Label250">
<?php
echo $ETI['saiu05rptacorreo'];
?>
</label>
<label class="Label250">
<?php
if ($bPuedeEditar) {
?>
<input id="saiu05rptacorreo" name="saiu05rptacorreo" type="text" value="<?php echo $_REQUEST['saiu05rptacorreo']; ?>" maxlength="50" placeholder="<?php echo $ETI['ing_campo'] . $ETI['saiu05rptacorreo']; ?>" />
<?php
} else {
	echo $_REQUEST['saiu05rptacorreo'];
?>
<input id="saiu05rptacorreo" name="saiu05rptacorreo" type="hidden" value="<?php echo $_REQUEST['saiu05rptacorreo']; ?>" />
<?php
}
?>
</label>
</div>
<?php
$sEstilo = ' style="display:none"';
if ($_REQUEST['saiu05rptaforma'] == 2) {
	$sEstilo = ' style="display:block"';
}
?>
<div id="div_saiu05rptadireccion" <?php echo $sEstilo; ?>>
<label class="Label250">
<?php
echo $ETI['saiu05rptadireccion'];
?>
</label>
<label class="Label250">
<?php
if ($bPuedeEditar) {
?>
<input id="saiu05rptadireccion" name="saiu05rptadireccion" type="text" value="<?php echo $_REQUEST['saiu05rptadireccion']; ?>" maxlength="100" class="L" placeholder="<?php echo $ETI['ing_campo'] . $ETI['saiu05rptadireccion']; ?>" />
<?php
} else {
	echo $_REQUEST['saiu05rptadireccion'];
?>
<input id="saiu05rptadireccion" name="saiu05rptadireccion" type="hidden" value="<?php echo $_REQUEST['saiu05rptadireccion']; ?>" />
<?php
}
?>
</label>
</div>
<div class="salto1px"></div>
<?php
if (false) {
?>
<label class="Label250">
<?php
echo $ETI['saiu05costogenera'];
?>
</label>
<label class="Label160">
<?php
echo $html_saiu05costogenera;
?>
</label>
<div class="salto1px"></div>
<?php
$sEstilo = ' style="display:none"';
if ($_REQUEST['saiu05costogenera'] == 1) {
	$sEstilo = '';
}
?>
<div id="div_saiu05costovalor" <?php echo $sEstilo; ?>>
<label class="Label250">
<?php
echo $ETI['saiu05costovalor'];
?>
</label>
<label class="Label160">
<input id="saiu05costovalor" name="saiu05costovalor" type="text" value="<?php echo formato_numero($_REQUEST['saiu05costovalor'], 2); ?>" class="veinte" maxlength="15" style="text-align:right" onchange="formatea_moneda(this);" placeholder="<?php echo $ETI['ing_vr']; ?>" />
</label>

<input id="saiu05costorefpago" name="saiu05costorefpago" type="hidden" value="<?php echo $_REQUEST['saiu05costorefpago']; ?>" />
</div>
<?php
} else {
?>
<input id="saiu05costogenera" name="saiu05costogenera" type="hidden" value="<?php echo $_REQUEST['saiu05costogenera']; ?>" />
<?php
}
?>
<div class="salto1px"></div>
</div>
<input id="saiu05idtemafin" name="saiu05idtemafin" type="hidden" value="<?php echo $_REQUEST['saiu05idtemafin']; ?>" />
<input id="saiu05idtiposolfin" name="saiu05idtiposolfin" type="hidden" value="<?php echo $_REQUEST['saiu05idtiposolfin']; ?>" />
<?php
// fin caja - categoría, tema, medio, costos
?>
</div>
<div class="salto1px"></div>
<div class="GrupoCampos">
<div class="tab">
<button class="tablinks" onclick="abrir_tab(event, 'solicitudes')" id="tab_inicial"><?php echo $ETI['titulo_3000']; ?></button>
<button class="tablinks" onclick="abrir_tab(event, 'pqrs')"><?php echo $ETI['titulo_3005_pqrs']; ?></button>
</div>
<input id="boculta3000" name="boculta3000" type="hidden" value="<?php echo $_REQUEST['boculta3000']; ?>" />
<div id="solicitudes" class="tabcontent">
<div id="div_f3000detalle">
<?php
echo $sTabla3000;
?>
</div>
</div>
<div id="pqrs" class="tabcontent">
<div>
<label class="Label60">
<?php
echo $ETI['saiu05agno'];
?>
</label>
<label class="Label90">
<?php
echo $html_bagnopqrs;
?>
</label>
</div>
<div class="salto1px"></div>
<div id="div_f3000pqrsdetalle">
<?php
echo $sTabla3000pqrs;
?>
</div>
</div>
<div class="salto1px"></div>
</div>
<?php
if (false) {
?>
<div class="salto1px"></div>
<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo $ETI['saiu05idinteresado'];
?>
</label>
<div class="salto1px"></div>
<input id="saiu05idinteresado" name="saiu05idinteresado" type="hidden" value="<?php echo $_REQUEST['saiu05idinteresado']; ?>" />
<div id="div_saiu05idinteresado_llaves">
<?php
$bOculto = true;
if ($_REQUEST['paso'] != 2) {
$bOculto = false;
}
echo html_DivTerceroV2('saiu05idinteresado', $_REQUEST['saiu05idinteresado_td'], $_REQUEST['saiu05idinteresado_doc'], $bOculto, 0, $ETI['ing_doc']);
?>
</div>
<div class="salto1px"></div>
<div id="div_saiu05idinteresado" class="L"><?php echo $saiu05idinteresado_rs; ?></div>
<div class="salto1px"></div>
</div>
<?php
} else {
?>
<input id="saiu05idinteresado" name="saiu05idinteresado" type="hidden" value="<?php echo $_REQUEST['saiu05idinteresado']; ?>" />
<input id="saiu05idinteresado_td" name="saiu05idinteresado_td" type="hidden" value="<?php echo $_REQUEST['saiu05idinteresado_td']; ?>" />
<input id="saiu05idinteresado_doc" name="saiu05idinteresado_doc" type="hidden" value="<?php echo $_REQUEST['saiu05idinteresado_doc']; ?>" />
<?php
}
?>
<input id="saiu05prioridad" name="saiu05prioridad" type="hidden" value="<?php echo $_REQUEST['saiu05prioridad']; ?>" />
<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo $ETI['saiu05detalle'];
?>
</label>
<div class="salto1px"></div>
<?php
if ($_REQUEST['saiu05estado'] < 0) {
?>
<label class="txtAreaS">
<textarea id="saiu05detalle" name="saiu05detalle" placeholder="<?php echo $ETI['ing_campo'] . $ETI['saiu05detalle']; ?>"><?php echo $_REQUEST['saiu05detalle']; ?></textarea>
</label>
<?php
} else {
?>
<?php echo nl2br($_REQUEST['saiu05detalle']); ?>
<input id="saiu05detalle" name="saiu05detalle" type="hidden" value="<?php echo $_REQUEST['saiu05detalle']; ?>" />
<?php
}
?>
<div class="salto1px"></div>
</div>
<input id="saiu05infocomplemento" name="saiu05infocomplemento" type="hidden" value="<?php echo $_REQUEST['saiu05infocomplemento']; ?>" />
<div class="salto1px"></div>
<?php
if (false) {
?>
<input id="saiu05idperiodo" name="saiu05idperiodo" type="hidden" value="<?php echo $_REQUEST['saiu05idperiodo']; ?>" />
<input id="saiu05idcurso" name="saiu05idcurso" type="hidden" value="<?php echo $_REQUEST['saiu05idcurso']; ?>" />
<input id="saiu05idgrupo" name="saiu05idgrupo" type="hidden" value="<?php echo $_REQUEST['saiu05idgrupo']; ?>" />
<?php
}
?>
<input id="saiu05tiemprespdias" name="saiu05tiemprespdias" type="hidden" value="<?php echo $_REQUEST['saiu05tiemprespdias']; ?>" />
<input id="saiu05tiempresphoras" name="saiu05tiempresphoras" type="hidden" value="<?php echo $_REQUEST['saiu05tiempresphoras']; ?>" />
<input id="saiu05fecharespprob" name="saiu05fecharespprob" type="hidden" value="<?php echo $_REQUEST['saiu05fecharespprob']; ?>" />
<div class="salto1px"></div>
<?php
if ($_REQUEST['saiu05estado']>=2) {
?>
<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo $ETI['saiu05respuesta'];
?>
</label>
<div class="salto1px"></div>
<?php
if (!$bPuedeGuardar) {
?>
<label class="Label130">
<?php
echo $ETI['saiu05fecharespdef'];
?>
</label>
<label class="Label130">
<?php
echo html_oculto('saiu05fecharespdef', $_REQUEST['saiu05fecharespdef'], fecha_desdenumero($_REQUEST['saiu05fecharespdef']));
?>
</label>
<label class="Label60">
<?php
echo $ETI['saiu05hora'];
?>
</label>
<label class="Label130">
<?php
echo html_HoraMin('saiu05horarespdef', $_REQUEST['saiu05horarespdef'], 'saiu05minrespdef', $_REQUEST['saiu05minrespdef'], true);
?>
</label>
<label class="Label220">
<?php
echo $html_saiu05evalacepta;
?>
</label>
<?php
}
?>
<div class="salto1px"></div>
<div class="GrupoCampos520">
<label class="txtAreaS">
<textarea id="saiu05respuesta" name="saiu05respuesta" placeholder="<?php echo $ETI['ing_campoa'] . $ETI['saiu05respuesta']; ?>"><?php echo $_REQUEST['saiu05respuesta']; ?></textarea>
</label>
<div class="salto1px"></div>
</div>
<input id="saiu05idorigen" name="saiu05idorigen" type="hidden" value="<?php echo $_REQUEST['saiu05idorigen']; ?>" />
<input id="saiu05idarchivo" name="saiu05idarchivo" type="hidden" value="<?php echo $_REQUEST['saiu05idarchivo']; ?>" />
<div class="GrupoCampos300">
<div class="salto1px"></div>
<div id="div_saiu05idarchivo" class="Campo220">
<?php
echo html_lnkarchivo((int)$_REQUEST['saiu05idorigen'], (int)$_REQUEST['saiu05idarchivo']);
?>
</div>
<?php
if ($_REQUEST['saiu05estado']==2) {
	$sEstiloAnexa = 'style="display:none;"';
	$sEstiloElimina = 'style="display:none;"';
	if ((int)$_REQUEST['saiu05id'] != 0) {
		$sEstiloAnexa = 'style="display:block;"';
	}
	if ((int)$_REQUEST['saiu05idarchivo'] != 0) {
		$sEstiloElimina = 'style="display:block;"';
	}
	echo $objForma->htmlBotonSolo('banexasaiu05idarchivo', 'btAnexarS', 'carga_saiu05idarchivo()', 'Cargar archivo', 30, $sEstiloAnexa, 'Anexar');
	echo $objForma->htmlBotonSolo('beliminasaiu05idarchivo', 'btBorrarS', 'eliminasaiu05idarchivo()', 'Eliminar archivo', 30, $sEstiloElimina, 'Eliminar');
}
?>
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
</div>
<?php
}
?>
<div class="salto5px"></div>
<?php
if ($bEsSupervisor) {
	if (!$bEsBorrador) {
		echo $objForma->htmlBotonSolo('cmdGuardar', 'BotonAzul160', "enviaguardarsupv();", $ETI['bt_guardar'], 160);	
	}
}
?>
<?php
switch ($_REQUEST['saiu05estado']) {
	case -1:
?>
<label class="Label160"></label>
<?php
echo $objForma->htmlBotonSolo('cmdGuardar', 'BotonAzul160', "enviaguardar();", $ETI['bt_guardar'], 160);
?>
<?php
	if ($_REQUEST['saiu05numref'] != '') {
?>
<label class="Label60"></label>
<?php
echo $objForma->htmlBotonSolo('cmdSolicitar', 'BotonAzul160', "enviacerrar();", $ETI['bt_radicar'], 160);
?>
<?php
	}
	break;
	case 0:
?>
<label class="Label320"></label>
<label class="Label60"></label>
<?php
echo $objForma->htmlBotonSolo('cmdTramitar', 'BotonAzul160', "enviatramitar();", $ETI['bt_tramitar'], 160);
?>
<?php
	break;
	case 2:
?>
<label class="Label320"></label>
<label class="Label60"></label>
<?php
echo $objForma->htmlBotonSolo('cmdResolver', 'BotonAzul160', "enviaresolver();", $ETI['bt_resolver'], 160);
?>
<?php
	break;
}
?>
<div class="salto1px"></div>
<?php
// -- Inicia Grupo campos 3007 Anexos
?>
<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo $ETI['titulo_3007'];
?>
</label>
<input id="boculta3007" name="boculta3007" type="hidden" value="<?php echo $_REQUEST['boculta3007']; ?>" />
<?php
if ($_REQUEST['paso'] == 2) {
?>
<div class="salto1px"></div>
<div id="div_f3007detalle">
<?php
echo $sTabla3007;
?>
</div>
<?php
}
?>
<div class="salto1px"></div>
</div>
<?php
// -- Termina Grupo campos 3007 Anexos
?>
<?php
// -- Inicia Grupo campos 3006 Anotaciones
?>
<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo $ETI['titulo_3006'];
?>
</label>
<?php
if ($_REQUEST['paso'] == 2) {
?>
<div class="ir_derecha">
<?php
echo $objForma->htmlExpande(3006, $_REQUEST['boculta3006'], $ETI['bt_mostrar'], $ETI['bt_ocultar']);
?>
</div>
<div class="salto1px"></div>
<?php
$sEstiloDiv = 'style="display:block;"';
if ($_REQUEST['boculta3006'] != 0) {
	$sEstiloDiv = 'style="display:none;"';
}
?>
<div id="div_p3006" <?php echo $sEstiloDiv; ?>>
<div class="GrupoCamposAyuda">
<?php
echo $ETI['msg_alertaanotacion'];
?>
</div>
<div class="salto5px"></div>
<label class="Label130">
<?php
echo $ETI['saiu06consec'];
?>
</label>
<label class="Label130">
<div id="div_saiu06consec">
<?php
if ((int)$_REQUEST['saiu06id'] == 0) {
?>
<input id="saiu06consec" name="saiu06consec" type="text" value="<?php echo $_REQUEST['saiu06consec']; ?>" onchange="revisaf3006()" class="cuatro" />
<?php
} else {
echo html_oculto('saiu06consec', $_REQUEST['saiu06consec'], formato_numero($_REQUEST['saiu06consec']));
}
?>
</div>
</label>
<label class="Label60">
<?php
echo $ETI['saiu06id'];
?>
</label>
<label class="Label60">
<div id="div_saiu06id">
<?php
echo html_oculto('saiu06id', $_REQUEST['saiu06id'], formato_numero($_REQUEST['saiu06id']));
?>
</div>
</label>
<input id="saiu06visible" name="saiu06visible" type="hidden" value="<?php echo $_REQUEST['saiu06visible']; ?>" />
<input id="saiu06descartada" name="saiu06descartada" type="hidden" value="<?php echo $_REQUEST['saiu06descartada']; ?>" />
<div class="salto1px"></div>
<div class="GrupoCampos450">
<label class="txtAreaS">
<?php
echo $ETI['saiu06anotacion'];
?>
<textarea id="saiu06anotacion" name="saiu06anotacion" placeholder="<?php echo $ETI['ing_campo'] . $ETI['saiu06anotacion']; ?>"><?php echo $_REQUEST['saiu06anotacion']; ?></textarea>
</label>
<input id="saiu06idorigen" name="saiu06idorigen" type="hidden" value="<?php echo $_REQUEST['saiu06idorigen']; ?>" />
<input id="saiu06idarchivo" name="saiu06idarchivo" type="hidden" value="<?php echo $_REQUEST['saiu06idarchivo']; ?>" />
<?php
if (true) {
?>
<div class="GrupoCampos300">
<div class="salto1px"></div>
<div id="div_saiu06idarchivo" class="Campo220">
<?php
echo html_lnkarchivo((int)$_REQUEST['saiu06idorigen'], (int)$_REQUEST['saiu06idarchivo']);
?>
</div>
<?php
if ($_REQUEST['saiu05estado']!=7) {
	$sEstiloAnexa = 'style="display:none;"';
	$sEstiloElimina = 'style="display:none;"';
	if ((int)$_REQUEST['saiu06id'] != 0) {
		$sEstiloAnexa = 'style="display:block;"';
	}
	if ((int)$_REQUEST['saiu06idarchivo'] != 0) {
		$sEstiloElimina = 'style="display:block;"';
	}
	echo $objForma->htmlBotonSolo('banexasaiu06idarchivo', 'btAnexarS', 'carga_saiu06idarchivo()', 'Cargar archivo', 30, $sEstiloAnexa, 'Anexar');
	echo $objForma->htmlBotonSolo('beliminasaiu06idarchivo', 'btBorrarS', 'eliminasaiu06idarchivo()', 'Eliminar archivo', 30, $sEstiloElimina, 'Eliminar');
}
?>
<div class="salto1px"></div>
</div>
<?php
}
?>
<div class="salto5px"></div>
</div>
<div class="GrupoCampos520">
<label class="TituloGrupo">
<?php
echo $ETI['saiu06idusuario'];
?>
</label>
<div class="salto1px"></div>
<input id="saiu06idusuario" name="saiu06idusuario" type="hidden" value="<?php echo $_REQUEST['saiu06idusuario']; ?>" />
<div id="div_saiu06idusuario_llaves">
<?php
$bOculto = true;
echo html_DivTerceroV2('saiu06idusuario', $_REQUEST['saiu06idusuario_td'], $_REQUEST['saiu06idusuario_doc'], $bOculto, 0, $ETI['ing_doc']);
?>
</div>
<div class="salto1px"></div>
<div id="div_saiu06idusuario" class="L"><?php echo $saiu06idusuario_rs; ?></div>
<div class="salto1px"></div>
<label class="Label90">
<?php
echo $ETI['saiu06fecha'];
?>
</label>
<input id="saiu06fecha" name="saiu06fecha" type="hidden" value="<?php echo $_REQUEST['saiu06fecha']; ?>" />
<b>
<div id="div_saiu06fecha" class="Campo220">
<?php
echo fecha_desdenumero($_REQUEST['saiu06fecha']);
?>
</div>
</b>
<div class="campo_HoraMin" id="div_saiu06hora">
<?php
echo html_HoraMin('saiu06hora', $_REQUEST['saiu06hora'], 'saiu06minuto', $_REQUEST['saiu06minuto'], true);
?>
</div>
<div class="salto1px"></div>
</div>
<?php
if ($_REQUEST['saiu05estado'] != 7) {
?>
<div class="salto1px"></div>
<label class="Label130">&nbsp;</label>
<label class="Label30">
<input id="bguarda3006" name="bguarda3006" type="button" value="Guardar" class="btMiniGuardar" onclick="guardaf3006()" title="<?php echo $ETI['bt_mini_guardar_3006']; ?>" />
</label>
<label class="Label30">
<input id="blimpia3006" name="blimpia3006" type="button" value="Limpiar" class="btMiniLimpiar" onclick="limpiaf3006()" title="<?php echo $ETI['bt_mini_limpiar_3006']; ?>" />
</label>
<label class="Label30">
<input id="belimina3006" name="belimina3006" type="button" value="Eliminar" class="btMiniEliminar" onclick="eliminaf3006()" title="<?php echo $ETI['bt_mini_eliminar_3006']; ?>" style="display:<?php if ((int)$_REQUEST['saiu06id'] != 0) {
echo 'block';
} else {
echo 'none';
} ?>;" />
</label>
<?php
}
?>
<div class="salto1px"></div>
<div id="div_f3006detalle">
<?php
echo $sTabla3006;
?>
</div>
<div class="salto5px"></div>
<?php
//Este es el cierre del div_p3006
?>
<div class="salto1px"></div>
</div>
<?php
//} //Termina el segundo bloque  condicional - bloque editar.
?>
<?php
}
?>
<div class="salto1px"></div>
</div>
<?php
// -- Termina Grupo campos 3006 Anotaciones
?>
<?php
// Inicio caja - responsable
$sEstilo = ' style="display:none"';
if ((int)$_REQUEST['paso'] != 0) {
	$sEstilo = ' style="display:block"';
}
?>
<div class="GrupoCampos520" <?php echo $sEstilo; ?>>
<label class="TituloGrupo">
<?php
echo $ETI['saiu05atiende'];
?>
<div class="ir_derecha">
<?php
if ($bPuedeGuardar) {
	if ($seg_1707 == 1) {
		echo $objForma->htmlBotonSolo('bRevAtiende', 'btMiniActualizar', "actualizaratiende();", $ETI['bt_actatiente']);
	}
}
?>
</div>
</label>
<div class="salto1px"></div>
<input id="saiu05idsupervisor" name="saiu05idsupervisor" type="hidden" value="<?php echo $_REQUEST['saiu05idsupervisor']; ?>" />
<input id="saiu05idresponsable" name="saiu05idresponsable" type="hidden" value="<?php echo $_REQUEST['saiu05idresponsable']; ?>" />
<label class="Label160">
<?php echo $ETI['saiu05idunidadresp']; ?>
</label>
<label class="Label200">
<b><?php echo $html_saiu05idunidadresp; ?></b>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php echo $ETI['saiu05idequiporesp']; ?>
</label>
<label class="Label200">
<b><?php echo $html_saiu05idequiporesp; ?></b>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php echo $ETI['saiu05idsupervisor']; ?>
</label>
<label class="Label200">
<b><?php echo $saiu05idsupervisor_rs; ?></b>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php echo $ETI['saiu05idresponsable']; ?>
</label>
<label id="lbl_f3005CambioResponsable" class="Label200">
<b><?php echo $saiu05idresponsable_rs; ?></b>
</label>
<div class="salto1px"></div>
<?php
if ($bPuedeGuardar) {
	if ($bEsSupervisor) {
		echo $objForma->htmlBotonSolo('cmdReasignar', 'BotonAzul160', "expandesector(2);", $ETI['bt_reasigna'], 160);
?>
<div class="salto1px"></div>
<?php
	}
}
?>
</div>
<?php
// Fin caja - responsable
?>
<?php
if (false) {
	//Ejemplo de boton de ayuda
	//echo html_BotonAyuda('NombreCampo');
	//echo html_DivAyudaLocal('NombreCampo');
}
if ($bConExpande) {
	//Este es el cierre del div_p3005
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
echo $ETI['msg_bdoc'];
?>
</label>
<label class="Label220">
<input id="bdoc" name="bdoc" type="text" value="<?php echo $_REQUEST['bdoc']; ?>" onchange="paginarf3005()" autocomplete="off" />
</label>
<label class="Label90">
<?php
echo $ETI['msg_bnombre'];
?>
</label>
<label class="Label220">
<input id="bnombre" name="bnombre" type="text" value="<?php echo $_REQUEST['bnombre']; ?>" onchange="paginarf3005()" autocomplete="off" />
</label>
<div class="salto1px"></div>
<label class="Label220">
<?php
echo $ETI['saiu05numref'];
?>
</label>
<label class="Label200">
<input id="bref" name="bref" type="text" value="<?php echo $_REQUEST['bref']; ?>" onchange="paginarf3005()" autocomplete="off" />
</label>
<div class="salto1px"></div>
<label class="Label90">
<?php
echo $ETI['saiu05idcategoria'];
?>
</label>
<label class="Label350">
<?php
echo $html_btipo;
?>
</label>
<div class="salto1px"></div>
<label class="Label90">
<?php
echo $ETI['saiu05idtiposolorigen'];
?>
</label>
<label class="Label350">
<?php
echo $html_bcategoria;
?>
</label>
<div class="salto1px"></div>
<label class="Label90">
<?php
echo $ETI['saiu05idtemaorigen'];
?>
</label>
<label class="Label350">
<div id="div_btema">
<?php
echo $html_btema;
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label90">
<?php
echo $ETI['msg_blistar'];
?>
</label>
<label class="Label200">
<?php
echo $html_blistar;
?>
</label>
<label class="Label60">
<?php
echo $ETI['msg_bestado'];
?>
</label>
<label class="Label130">
<?php
echo $html_bestado;
?>
</label>
<?php
if ($bEsSupervisor) {
	echo $objForma->htmlBotonSolo('btRevBorrador', 'btMiniActualizar', 'revisaborrador()', $ETI['bt_revborrador'], 60);
}
?>
<label class="Label60">
<?php
echo $ETI['saiu05agno'];
?>
</label>
<label class="Label130">
<?php
echo $html_bagno;
?>
</label>
<div class="salto1px"></div>
<label class="Label90">
<?php
echo $ETI['saiu05idzona'];
?>
</label>
<label>
<?php
echo $html_bzona;
?>
</label>
<div class="salto1px"></div>
<label class="Label90">
<?php
echo $ETI['saiu05cead'];
?>
</label>
<label>
<div id="div_bcentro">
<?php
echo $html_bcentro;
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label90">
<?php
echo $ETI['saiu05idescuela'];
?>
</label>
<label>
<?php
echo $html_bescuela;
?>
</label>
<div class="salto1px"></div>
<label class="Label90">
<?php
echo $ETI['saiu05idprograma'];
?>
</label>
<label>
<div id="div_bprograma">
<?php
echo $html_bprograma;
?>
</div>
</label>
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
<?php
echo ' ' . $csv_separa;
?>
<div id="div_f3005detalle">
<?php
echo $sTabla3005;
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
echo '<h2>' . $ETI['titulo_sector2_PQRS'] . '</h2>';
?>
</div>
</div>
<div class="areaform">
<div class="areatrabajo">
<?php
if ($bEsSupervisor) {
?>
<div class="GrupoCampos520">
<label class="TituloGrupo">
<?php
echo $ETI['saiu05atiende'];
?>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php echo $ETI['saiu05idunidadresp']; ?>
</label>
<label class="Label200">
<b><?php echo $html_saiu05idunidadresp; ?></b>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php echo $ETI['saiu05idequiporesp']; ?>
</label>
<label class="Label200">
<b><?php echo $html_saiu05idequiporesp; ?></b>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php echo $ETI['saiu05idsupervisor']; ?>
</label>
<label class="Label200">
<b><?php echo $saiu05idsupervisor_rs; ?></b>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php echo $ETI['saiu05idresponsable']; ?>
</label>
<label class="Label200">
<div id="div_saiu05idresponsable">
<?php 
echo $html_saiu05idresponsablecombo; 
?>
</div>
</label>
<div class="salto1px"></div>
<?php
echo $objForma->htmlBotonSolo('cmdGuardarR', 'BotonAzul160', "enviareasignar();", $ETI['bt_guardaresigna'], 160);
?>
<div class="salto1px"></div>
</div>
<?php
}
?>
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
echo $ETI['msg_saiu05consec'];
?>
</label>
<label class="Label90">
<?php
echo '<b>' . $_REQUEST['saiu05consec'] . '</b>';
?>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['msg_saiu05consec_nuevo'];
// onchange="RevisaConsec()"
?>
</label>
<label class="Label90">
<input id="saiu05consec_nuevo" name="saiu05consec_nuevo" type="text" value="<?php echo $_REQUEST['saiu05consec_nuevo']; ?>" class="cuatro" />
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
<input id="titulo_3005" name="titulo_3005" type="hidden" value="<?php echo $sTituloModulo; ?>" />
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
$objForma = new clsHtmlForma($iPiel);
if ($bBloqueTitulo) {
	$objForma->addBoton('cmdAyuda97', 'btSupAyuda', 'muestraayuda(' . $iCodModulo . ');', $ETI['bt_ayuda']);
	$objForma->addBoton('cmdVolverSec97', 'btSupVolver', 'retornacontrol();', $ETI['bt_volver']);
	echo $objForma->htmlTitulo($sTituloModulo, $iCodModulo, 'div_97titulo');
}
echo $objForma->htmlInicioMarco();
?>
<div id="div_97params"></div>
<div class="salto1px"></div>
<div id="div_97tabla"></div>
<?php
echo $objForma->htmlFinMarco();
?>
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
?>
<div class="flotante">
<?php
if (false) {
?>
<input id="cmdGuardarf" name="cmdGuardarf" type="button" class="btSoloGuardar" onClick="enviaguardar();" value="<?php echo $ETI['bt_guardar']; ?>" />
<?php
}
?>
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
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>js/jquery.autocomplete.css" type="text/css" />
<script language="javascript" src="<?php echo $APP->rutacomun; ?>js/jquery.autocomplete.js"></script>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>js/chosen.jquery.js"></script>
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>js/chosen.css" type="text/css" />
<script language="javascript">
document.getElementById("tab_inicial").click();
$().ready(function() {
<?php
if ($bPuedeEditar) {
?>
$("#saiu05idtiposolorigen, #saiu05idtemaorigen, #bcategoria, #btema").chosen({
no_results_text: "No existen coincidencias: ",
width: "100%"});
<?php
}
?>
		$("#bprograma").chosen();
	});
</script>
<script language="javascript" src="ac_3005.js?v=1"></script>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>unad_todas2024v2.js"></script>
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>css/css_tabs.css" type="text/css" />
<?php
forma_piedepagina();

