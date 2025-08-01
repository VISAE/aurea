<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2021 - 2024 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.28.2 lunes, 13 de junio de 2022
*/

/** Archivo saisolconsulta.php.
 * Modulo 3005 saiu05solicitud.
 * @author Omar Augusto Bautista Mora - omar.bautista@unad.edu.co
 * @param debug=1 (Opcional), bandera para indicar si se generan datos de depuración
 * @date viernes, 25 de junio de 2024
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
require $APP->rutacomun . 'libdatos.php';
require $APP->rutacomun . 'libhtml.php';
require $APP->rutacomun . 'xajax/xajax_core/xajax.inc.php';
require $APP->rutacomun . 'unad_xajax.php';
require $APP->rutacomun . 'libsai.php';
require $APP->rutacomun . 'libtiempo.php';
require $APP->rutacomun . 'libmail.php';
require $APP->rutacomun . 'libaurea.php';
if (($bPeticionXAJAX) && ($_SESSION['unad_id_tercero'] == 0)) {
	// viene por xajax.
	$xajax = new xajax();
	$xajax->configure('javascript URI', $APP->rutacomun . 'xajax/');
	$xajax->register(XAJAX_FUNCTION, 'sesion_abandona_V2');
	$xajax->processRequest();
	die();
}
$bEnSesion = false;
if ((int)$_SESSION['unad_id_tercero'] > 0) {
	$bEnSesion = true;
}
if (!$bEnSesion) {
	$_SESSION['unad_redir'] = 'saisolconsulta.php';
	header('Location:index.php');
	die();
}
$iConsecutivoMenu = 1;
$iMinVerDB = 7555;
$iCodModulo = 3005;
$iCodModuloConsulta = $iCodModulo;
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
$mensajes_3005 = $APP->rutacomun . 'lg/lg_3005_' . $_SESSION['unad_idioma'] . '.php';
if (!file_exists($mensajes_3005)) {
	$mensajes_3005 = $APP->rutacomun . 'lg/lg_3005_es.php';
}
require $mensajes_todas;
require $mensajes_3005;
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
$sOcultaId = ' style="display:none;"';
$sTituloApp = 'SII';
$sTituloModulo = $ETI['titulo_3005'];
switch ($iPiel) {
	case 2:
		$sAnchoExpandeContrae = '';
		$bBloqueTitulo = false;
		break;
}
// --- Final de las variables para la forma
if ($bDebug) {
	$sDebug = $sDebug . fecha_microtiempo() . ' Probando conexi&oacute;n con la base de datos <b>' . $APP->dbname . '</b> en <b>' . $APP->dbhost . '</b><br>';
}
$bCargaMenu = true;
if (!$objDB->Conectar()) {
	$bCargaMenu = false;
	$bCerrado = true;
	$sMsgCierre = '<div class="MarquesinaGrande">Disculpe las molestias estamos en este momento nuestros servicios no estas disponibles.<br>Por favor intente acceder mas tarde.<br>Si el problema persiste por favor informe al administrador del sistema.</div>';
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' Error al intentar conectar con la base de datos <b>' . $objDB->serror . '</b><br>';
	}
}
if (!$bCerrado) {
	$iVerDB = version_upd($objDB);
	if ($iMinVerDB > $iVerDB) {
		$bCerrado = true;
		$sMsgCierre = '<div class="MarquesinaGrande">La base de datos se encuentra desactualizada para este modulo.<br>Por favor informe al administrador del sistema.</div>';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' <b>DB DESACTUALIZADA [Requerida:' . $iMinVerDB . ' - Encontrada:' . $iVerDB . ']</b><br>';
		}
	} else {
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' Versi&oacute;n DB <b>' . $iVerDB . '</b> [Requerida:' . $iMinVerDB . ']<br>';
		}
	}
}
if (!$bCerrado) {
	if ($idTercero == $_SESSION['unad_id_tercero']){
		$bDevuelve = true;
	} else {
		list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModuloConsulta, 1, $_SESSION['unad_id_tercero'], $objDB);
	}
	if (!$bDevuelve) {
		$bCerrado = true;
		$sMsgCierre = '<div class="MarquesinaGrande">No cuenta con permiso para acceder a este modulo [' . $iCodModuloConsulta . '].</div>';
	}
}
if ($bCerrado) {
	if ($bCargaMenu) {
		switch ($iPiel) {
			case 2:
				list($et_menu, $sDebugM) = html_menuCampusV2($objDB, $iPiel, $bDebugMenu, $idTercero);
				break;
			default:
				list($et_menu, $sDebugM) = html_menuCampus($APP->idsistema, $objDB, $iPiel, $bDebugMenu, $idTercero);
				break;
		}
	}
	$objDB->CerrarConexion();
	switch ($iPiel) {
		case 2:
			require $APP->rutacomun . 'unad_forma2023.php';
			forma_InicioV4($xajax, $sTituloModulo);
			$aRutas = array(
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
		$objForma->addBoton('cmdAyuda98', 'btSupAyuda', 'muestraayuda('.$iCodModulo.');', $ETI['bt_ayuda']);
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
$seg_1707 = 0;
$bDevuelve = false;
list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 1707, $_SESSION['unad_id_tercero'], $objDB, $bDebug);
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
$idEntidad = Traer_Entidad();
$mensajes_3000 = $APP->rutacomun . 'lg/lg_3000_' . $_SESSION['unad_idioma'] . '.php';
if (!file_exists($mensajes_3000)) {
	$mensajes_3000 = $APP->rutacomun . 'lg/lg_3000_es.php';
}
$mensajes_3006 = $APP->rutacomun . 'lg/lg_3006_' . $_SESSION['unad_idioma'] . '.php';
if (!file_exists($mensajes_3006)) {
	$mensajes_3006 = $APP->rutacomun . 'lg/lg_3006_es.php';
}
$mensajes_3007 = $APP->rutacomun . 'lg/lg_3007_' . $_SESSION['unad_idioma'] . '.php';
if (!file_exists($mensajes_3007)) {
	$mensajes_3007 = $APP->rutacomun . 'lg/lg_3007_es.php';
}
require $mensajes_3000;
require $mensajes_3006;
require $mensajes_3007;
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
// -- 3005 saiu05solicitudcampus
require 'lib3005campus.php';
$xajax = new xajax();
$xajax->configure('javascript URI', $APP->rutacomun . 'xajax/');
$xajax->register(XAJAX_FUNCTION, 'unad11_Mostrar_v2');
$xajax->register(XAJAX_FUNCTION, 'unad11_Mostrar_v2SAI');
$xajax->register(XAJAX_FUNCTION, 'unad11_TraerXid');
$xajax->register(XAJAX_FUNCTION, 'unad11_TraerXidSAI');
$xajax->register(XAJAX_FUNCTION, 'formatear_moneda');
$xajax->register(XAJAX_FUNCTION, 'f3005_Combosaiu05idtemaorigen');
$xajax->register(XAJAX_FUNCTION, 'f3005_Combosaiu05idequiporesp');
$xajax->register(XAJAX_FUNCTION, 'sesion_abandona_V2');
$xajax->register(XAJAX_FUNCTION, 'sesion_mantenerV4');
$xajax->register(XAJAX_FUNCTION, 'f3005_HtmlTablaCampus');
$xajax->register(XAJAX_FUNCTION, 'f3005_ExisteDato');
$xajax->register(XAJAX_FUNCTION, 'f3005_Busquedas');
$xajax->register(XAJAX_FUNCTION, 'f3005_HtmlBusqueda');
$xajax->register(XAJAX_FUNCTION, 'f3000_HtmlTabla');
$xajax->register(XAJAX_FUNCTION, 'f3000pqrs_HtmlTabla');
$xajax->register(XAJAX_FUNCTION, 'f3006_Traer');
$xajax->register(XAJAX_FUNCTION, 'f3006_HtmlTabla');
$xajax->register(XAJAX_FUNCTION, 'f3006_PintarLlaves');
$xajax->register(XAJAX_FUNCTION, 'f3007_Traer');
$xajax->register(XAJAX_FUNCTION, 'f3007_HtmlTabla');
$xajax->register(XAJAX_FUNCTION, 'f3007_PintarLlaves');
$xajax->register(XAJAX_FUNCTION, 'f236_TraerInfoPersonal');
$xajax->register(XAJAX_FUNCTION, 'f3005_Combobtema');
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
$iTipoTramite = 1;
$iHoy = fecha_DiaMod();
// -- Se inicializan las variables, primero las que controlan la visualización de la página.
if (isset($_REQUEST['iscroll']) == 0) {
	$_REQUEST['iscroll'] = 0;
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
	$_REQUEST['saiu05agno'] = 0;
}
if (isset($_REQUEST['saiu05mes']) == 0) {
	$_REQUEST['saiu05mes'] = 0;
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
	$_REQUEST['saiu05idmoduloproc'] = '';
}
if (isset($_REQUEST['saiu05identificadormod']) == 0) {
	$_REQUEST['saiu05identificadormod'] = '';
}
if (isset($_REQUEST['saiu05numradicado']) == 0) {
	$_REQUEST['saiu05numradicado'] = '';
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
		$_REQUEST['saiu06visible'] = '';
	}
	if (isset($_REQUEST['saiu06descartada']) == 0) {
		$_REQUEST['saiu06descartada'] = '';
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
// Espacio para inicializar otras variables
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
	$_REQUEST['blistar'] = '';
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
if (isset($_REQUEST['bagnopqrs']) == 0) {
	$_REQUEST['bagnopqrs'] = fecha_agno();
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
	$sTabla = 'saiu05solicitud' . f3000_Contenedor($_REQUEST['saiu05origenagno'], $_REQUEST['saiu05origenmes']);
	if ($objDB->bexistetabla($sTabla)) {
		list($sErrorR, $sDebugR) = f3005_RevTabla_saiu05solicitud(fecha_ArmarAgnoMes($_REQUEST['saiu05origenagno'], $_REQUEST['saiu05origenmes']), $objDB);
		$sError = $sError . $sErrorR;
		if ($_REQUEST['paso'] == 1) {
			$sSQLcondi = 'saiu05agno=' . $_REQUEST['saiu05agno'] . ' AND saiu05mes=' . $_REQUEST['saiu05mes'] . ' AND saiu05tiporadicado=' . $_REQUEST['saiu05tiporadicado'] . ' AND saiu05consec=' . $_REQUEST['saiu05consec'] . '';
		} else {
			$sSQLcondi = 'saiu05id=' . $_REQUEST['saiu05id'] . '';
		}
		$sSQL = 'SELECT * FROM ' . $sTabla . ' WHERE ' . $sSQLcondi;
		// $sDebug=$sDebug.fecha_microtiempo().' SQL CARGA LISTA '.$sSQL.'<br>';
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
		if ($sError=='') {
			$_REQUEST['saiu05idorigen'] = $fila['saiu05idorigen'];
			$_REQUEST['saiu05idarchivo'] = $fila['saiu05idarchivo'];
		}
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
	} else {
		$sError = 'No ha sido posible encontrar el contenedor para ' . $_REQUEST['saiu05agno'] . ' - ' . $_REQUEST['saiu05mes'] . '';
		$_REQUEST['paso'] = -1;
	}
}
//Cerrar
$bCerrando = false;
$saiu05estado = $_REQUEST['saiu05estado'];
//limpiar la pantalla
$iViaWeb = 3;
if ($_REQUEST['paso'] == -1) {
	$_REQUEST['saiu05agno'] = fecha_agno();
	$_REQUEST['saiu05mes'] = fecha_mes();
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
	$_REQUEST['saiu05raddesphab'] = '';
	$_REQUEST['saiu05estado'] = -1;
	$_REQUEST['saiu05idmedio'] = 0;
	$_REQUEST['saiu05idtiposolorigen'] = '';
	$_REQUEST['saiu05idtemaorigen'] = '';
	$_REQUEST['saiu05idtiposolfin'] = '';
	$_REQUEST['saiu05idtemafin'] = '';
	$_REQUEST['saiu05idsolicitante'] = $idTercero;
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
	$_REQUEST['saiu05idmoduloproc'] = '';
	$_REQUEST['saiu05identificadormod'] = '';
	$_REQUEST['saiu05numradicado'] = '';
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
	$_REQUEST['saiu06visible'] = '';
	$_REQUEST['saiu06descartada'] = '';
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
$bConMedio = false;
$bMuestraAdicionales = false;
$bPuedeGuardar = false;
$bEsBorrador = false;
$bExisteNumRef = false;
if ((int)$_REQUEST['paso'] > 0) {
	if ($_REQUEST['saiu05idmedio'] != $iViaWeb) {
		$bConMedio = false;
	}
}
if ($_REQUEST['saiu05estado'] == -1) {
	$bEsBorrador = true;
}
if ($_REQUEST['saiu05numref'] != '') {
	$bExisteNumRef = true;
}
$iAgno = fecha_agno();
$iAgnoFin = fecha_agno();
$iContenedor = $iAgno . fecha_mes();
$sTabla='saiu05solicitud_' . $iContenedor;
if (!$objDB->bexistetabla($sTabla)) {
	list($sErrorT, $sDebugT) = f3000_TablasMes($iAgno, fecha_mes(), $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugT;
}
// - AJUSTAR LAS TABLAS
//f3000_AjustarTablas($objDB, $bDebug);
//Permisos adicionales
$seg_4 = 0;
$seg_5 = 0;
$seg_6 = 0;
$seg_8 = 0;
$seg_12 = 0;
list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 6, $idTercero, $objDB);
if ($bDevuelve) {
	$seg_6 = 1;
}
list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 12, $idTercero, $objDB);
if ($bDevuelve) {
	$seg_12 = 1;
}
//Crear los controles que requieran llamado a base de datos
$objCombos = new clsHtmlCombos();
$objForma = new clsHtmlForma($iPiel);
$objTercero = new clsHtmlTercero();
$html_InfoContacto = '';
$html_personal = '';
$saiu05estado_nombre = '';
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
if ($_REQUEST['saiu05estado'] == -1) {
	$saiu05estado_nombre = 'Borrador';
} else {
	list($saiu05estado_nombre, $sErrorDet) = tabla_campoxid('saiu11estadosol', 'saiu11nombre', 'saiu11id', $_REQUEST['saiu05estado'], '{' . $ETI['msg_sindato'] . '}', $objDB);
}
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
list($saiu05idtiposolorigen_nombre, $sErrorDet) = tabla_campoxid('saiu02tiposol', 'saiu02titulo', 'saiu02id', $_REQUEST['saiu05idtiposolorigen'], '{' . $ETI['msg_sindato'] . '}', $objDB);
$html_saiu05idtiposolorigen = html_oculto('saiu05idtiposolorigen', $_REQUEST['saiu05idtiposolorigen'], $saiu05idtiposolorigen_nombre);
list($saiu05idtemaorigen_nombre, $sErrorDet) = tabla_campoxid('saiu03temasol', 'saiu03titulo', 'saiu03id', $_REQUEST['saiu05idtemaorigen'], '{' . $ETI['msg_sindato'] . '}', $objDB);
$html_saiu05idtemaorigen = html_oculto('saiu05idtemaorigen', $_REQUEST['saiu05idtemaorigen'], $saiu05idtemaorigen_nombre);
list($saiu05idsolicitante_rs, $_REQUEST['saiu05idsolicitante'], $_REQUEST['saiu05idsolicitante_td'], $_REQUEST['saiu05idsolicitante_doc']) = html_tercero($_REQUEST['saiu05idsolicitante_td'], $_REQUEST['saiu05idsolicitante_doc'], $_REQUEST['saiu05idsolicitante'], 0, $objDB);
list($saiu05idinteresado_rs, $_REQUEST['saiu05idinteresado'], $_REQUEST['saiu05idinteresado_td'], $_REQUEST['saiu05idinteresado_doc']) = html_tercero($_REQUEST['saiu05idinteresado_td'], $_REQUEST['saiu05idinteresado_doc'], $_REQUEST['saiu05idinteresado'], 0, $objDB);

$objCombos->nuevo('saiu05costogenera', $_REQUEST['saiu05costogenera'], false, '{' . $ETI['msg_seleccione'] . '}');
$objCombos->sAccion = 'ajustarcosto()';
$objCombos->SiNo($ETI['si'], $ETI['no'], 1, 0);
$html_saiu05costogenera = $objCombos->html('', $objDB);

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
if ($_REQUEST['saiu05estado'] < 7) {
	if ($idTercero == $_REQUEST['saiu05idsupervisor'] || $seg_1707) {
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
list($saiu05tipointeresado_nombre, $sErrorDet) = tabla_campoxid('bita07tiposolicitante', 'bita07nombre', 'bita07id', $_REQUEST['saiu05tipointeresado'], '{' . $ETI['msg_sindato'] . '}', $objDB);
$html_saiu05tipointeresado = html_oculto('saiu05tipointeresado', $_REQUEST['saiu05tipointeresado'], $saiu05tipointeresado_nombre);
list($saiu05idcategoria_nombre, $sErrorDet) = tabla_campoxid('saiu68categoria', 'saiu68nombre', 'saiu68id', $_REQUEST['saiu05idcategoria'], '{' . $ETI['msg_sindato'] . '}', $objDB);
$html_saiu05idcategoria = html_oculto('saiu05idcategoria', $_REQUEST['saiu05idcategoria'], $saiu05idcategoria_nombre);
list($saiu05rptaforma_nombre, $sErrorDet) = tabla_campoxid('saiu12formarespuesta', 'saiu12nombre', 'saiu12id', $_REQUEST['saiu05rptaforma'], '{' . $ETI['msg_sindato'] . '}', $objDB);
$html_saiu05rptaforma = html_oculto('saiu05rptaforma', $_REQUEST['saiu05rptaforma'], $saiu05rptaforma_nombre);
if ($bExisteNumRef) {
	$objCombos->nuevo('saiu06visible', $_REQUEST['saiu06visible'], false);
	$objCombos->sino();
	$html_saiu06visible = $objCombos->html('', $objDB);
	$objCombos->nuevo('saiu06descartada', $_REQUEST['saiu06descartada'], false);
	$objCombos->sino();
	$html_saiu06descartada = $objCombos->html('', $objDB);
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
$objCombos->numeros(2020, $iAgnoFin, 1);
$html_bagno = $objCombos->html('', $objDB);
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
$iModeloReporte = 3005;
$html_iFormatoImprime = '<input id="iformatoimprime" name="iformatoimprime" type="hidden" value="0" />
';
if ($_REQUEST['paso'] > 0) {
	$bDevuelve = false;
	if ($bDevuelve) {
		$seg_5 = 1;
	}
	list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 4, $idTercero, $objDB);
	if ($bDevuelve) {
		$seg_4 = 1;
	}
}
// list($html_InfoContacto, $html_personal, $sDebugC) = f3000_InfoContacto($_REQUEST['saiu05idsolicitante'], $idTercero, $objDB, $bDebug);
//Cargar las tablas de datos
$aParametros[0] = ''; //$_REQUEST['p1_3005'];
$aParametros[100] = $idTercero;
$aParametros[101] = $_REQUEST['paginaf3005'];
$aParametros[102] = $_REQUEST['lppf3005'];
$aParametros[104] = $_REQUEST['bagno'];
$aParametros[105] = '';
$aParametros[106] = '';
$aParametros[107] = '';
list($sTabla3005, $sErrorT, $sDebugTabla) = f3005_TablaDetalleCampus($aParametros, $objDB, $bDebug);
$sError = $sError . $sErrorT;
$sDebug = $sDebug . $sDebugTabla;
$sTabla3006 = '';
$sTabla3007 = '';
$sNumSol = '';
if ($_REQUEST['paso'] != 0) {
	//Anotaciones
	$sNumSol = f3000_NumSolicitud($_REQUEST['saiu05agno'], $_REQUEST['saiu05mes'], $_REQUEST['saiu05consec']);
	$aParametros3006[0] = $_REQUEST['saiu05id'];
	$aParametros3006[97] = $_REQUEST['saiu05agno'];
	$aParametros3006[98] = $_REQUEST['saiu05mes'];
	$aParametros3006[99] = true;
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
	$aParametros3007[99] = true;
	$aParametros3007[100] = $idTercero;
	$aParametros3007[101] = $_REQUEST['paginaf3007'];
	$aParametros3007[102] = $_REQUEST['lppf3007'];
	//$aParametros3007[103]=$_REQUEST['bnombre3007'];
	//$aParametros3007[104]=$_REQUEST['blistar3007'];
	list($sTabla3007, $sDebugTabla) = f3007_TablaDetalleV2($aParametros3007, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugTabla;
}
switch ($iPiel) {
	case 2:
		list($et_menu, $sDebugM) = html_menuCampusV2($objDB, $iPiel, $bDebugMenu, $idTercero);
		break;
	default:
		list($et_menu, $sDebugM) = html_menuCampus($APP->idsistema, $objDB, $iPiel, $bDebugMenu, $idTercero);
		break;
}
$sDebug = $sDebug . $sDebugM;
$objDB->CerrarConexion();
//FORMA
switch ($iPiel) {
	case 2:
		require $APP->rutacomun . 'unad_forma2023.php';
		forma_InicioV4($xajax, $sTituloModulo);
		$aRutas = array(
			array('', $sTituloModulo)
		);
		$iNumBoton = 0;
		$aBotones[$iNumBoton] = array('muestraayuda(' . $APP->idsistema . ', ' . $iCodModulo . ')', $ETI['bt_ayuda'], 'iHelp');
		$iNumBoton++;
		$aBotones[$iNumBoton] = array('limpiapagina()', $ETI['bt_limpiar'], 'iDocument');
		$iNumBoton++;
		if ($bPuedeGuardar) {
			$aBotones[$iNumBoton] = array('enviaguardar()', $ETI['bt_guardar'], 'iSaveFill');
			$iNumBoton++;
		}
		$aBotones[$iNumBoton] = array('volver()', $ETI['bt_volver'], 'iArrowBack');
		$iNumBoton++;
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
	function limpiapagina() {
		expandesector(98);
		window.document.frmedita.paso.value = -1;
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
		var objdiv = document.getElementById('div_p' + codigo);
		var objban = document.getElementById('boculta' + codigo);
		var otroestado = 'none';
		if (estado == 'none') {
			otroestado = 'block';
		}
		objdiv.style.display = estado;
		objban.value = valor;
		verboton('btrecoge' + codigo, estado);
		verboton('btexpande' + codigo, otroestado);
	}

	function verboton(idboton, estado) {
		var objbt = document.getElementById(idboton);
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
		var sEst = 'none';
		if (codigo == 1) {
			sEst = 'block';
		}
<?php
switch ($iPiel) {
	case 2:
?>
		document.getElementById('botones_sector1').style.display = 'flex';
		document.getElementById('botones_sector97').style.display = 'none';
		switch (codigo) {
			case 1:
				break;
			case 97:
				document.getElementById('botones_sector1').style.display = 'none';
				document.getElementById('botones_sector' + codigo).style.display = 'flex';
				break;
			default:
				document.getElementById('botones_sector1').style.display = 'none';
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
		}
	}

	function ter_traerxid(idcampo, vrcampo) {
		var params = new Array();
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
			window.document.frmlista.nombrearchivo.value = 'Solicitudes';
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
		var sError = '';
		if (window.document.frmedita.seg_6.value != 1) {
			sError = "<?php echo $ERR['6']; ?>";
		}
		if (sError == '') {
			/*Agregar validaciones*/
		}
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
	function RevisaLlave() {
		var datos = new Array();
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
		window.document.frmedita.saiu05origenagno.value = String(llave1);
		window.document.frmedita.saiu05origenmes.value = String(llave2);
		window.document.frmedita.saiu05id.value = String(llave3);
		window.document.frmedita.paso.value = 3;
		window.document.frmedita.submit();
	}

	function paginarf3005() {
		var params = new Array();
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
		xajax_f3005_HtmlTablaCampus(params);
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
		document.getElementById("saiu05agno").focus();
	}

	function buscarV2016(sCampo) {
		window.document.frmedita.iscroll.value = window.pageYOffset;
		expandesector(98);
		window.document.frmedita.scampobusca.value = sCampo;
		var params = new Array();
		params[1] = sCampo;
		//params[2]=window.document.frmedita.iagno.value;
		//params[3]=window.document.frmedita.itipo.value;
		xajax_f3005_Busquedas(params);
	}

	function retornacontrol() {
		expandesector(1);
		window.scrollTo(0, window.document.frmedita.iscroll.value);
	}

	function Devuelve(sValor) {
		var sCampo = window.document.frmedita.scampobusca.value;
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
		var divAyuda = document.getElementById('div_ayuda_' + sCampo);
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
		var sRetorna = window.document.frmedita.div96v2.value;
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
		var iForma = window.document.frmedita.saiu05rptaforma.value;
		var sMuestra = 'none';
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
		var iForma = window.document.frmedita.saiu05costogenera.value;
		var sMuestra = 'none';
		if (iForma == 1) {
			sMuestra = 'block';
		}
		document.getElementById('div_saiu05costovalor').style.display = sMuestra;
	}

	function verinfopersonal(id) {
		var params = new Array();
		params[1] = id;
		document.getElementById('div_infopersonal').innerHTML = '<b>Procesando datos, por favor espere...</b>';
		xajax_f236_TraerInfoPersonal(params);
	}
<?php
if ($bEsBorrador) {
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
?>
</script>
<form id="frmvolver" name="frmvolver" method="post" action="sai.php" autocomplete="off" style="display:none">
</form>
<?php
if ($_REQUEST['paso'] != 0) {
?>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>jsi/js3006.js?v=2"></script>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>jsi/js3007.js?v=1"></script>
<?php
}
?>
<form id="frmimpp" name="frmimpp" method="post" action="<?php echo $APP->rutacomun; ?>p3005.php" target="_blank" style="display:none">
<input id="r" name="r" type="hidden" value="3005" />
<input id="id3005" name="id3005" type="hidden" value="<?php echo $_REQUEST['saiu05id']; ?>" />
<input id="v0" name="v0" type="hidden" value="" />
<input id="v3" name="v3" type="hidden" value="" />
<input id="v4" name="v4" type="hidden" value="" />
<input id="v5" name="v5" type="hidden" value="" />
<input id="iformato94" name="iformato94" type="hidden" value="0" />
<input id="separa" name="separa" type="hidden" value="," />
<input id="rdebug" name="rdebug" type="hidden" value="<?php echo $_REQUEST['debug']; ?>" />
<input id="clave" name="clave" type="hidden" value="" />
</form>
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
<input id="bCambiaEst" name="bCambiaEst" type="hidden" value="" />
<div id="div_sector1">
<?php
if ($bBloqueTitulo) {
?>
<div class="titulos">
<div class="titulosD">
<input id="cmdAyuda" name="cmdAyuda" type="button" class="btUpAyuda" onclick="muestraayuda(<?php echo $APP->idsistema . ', ' . $iCodModulo; ?>);" title="<?php echo $ETI['bt_ayuda']; ?>" value="<?php echo $ETI['bt_ayuda']; ?>" />
<input id="cmdLimpiar" name="cmdLimpiar" type="button" class="btUpLimpiar" onclick="limpiapagina();" title="<?php echo $ETI['bt_limpiar']; ?>" value="<?php echo $ETI['bt_limpiar']; ?>" />
<input id="cmdVolverSec2" name="cmdVolverSec2" type="button" class="btSupVolver" onclick="volver();" title="<?php echo $ETI['bt_volver']; ?>" value="<?php echo $ETI['bt_volver']; ?>" />
</div>
<div class="titulosI">
<?php
echo '<h2>' . $ETI['titulo_3005'] . '</h2>';
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
<label class="Label30">
<input id="btRevisaDoc" name="btRevisaDoc" type="button" value="Actualizar" class="btMiniActualizar" onclick="limpiapagina()" title="Consultar documento" />
</label>
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
$sEstiloExpande = ' style="display:none;"';
$sEstiloRecoje = '';
$sEstiloDiv = '';
if ($_REQUEST['boculta3005'] != 0) {
	$sEstiloExpande = '';
	$sEstiloRecoje = ' style="display:none;"';
	$sEstiloDiv = ' style="display:none;"';
}
?>
<input id="boculta3005" name="boculta3005" type="hidden" value="<?php echo $_REQUEST['boculta3005']; ?>" />
<label class="Label30">
<input id="btexpande3005" name="btexpande3005" type="button" value="Mostrar" class="btMiniExpandir" onclick="expandepanel(3005, 'block', 0);" title="<?php echo $ETI['bt_mostrar']; ?>"<?php echo $sEstiloExpande; ?>/>
</label>
<label class="Label30">
<input id="btrecoge3005" name="btrecoge3005" type="button" value="Ocultar" class="btMiniRecoger" onclick="expandepanel(3005, 'none', 1);" title="<?php echo $ETI['bt_ocultar']; ?>" <?php echo $sEstiloRecoje; ?>/>
</label>
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
	if ($_REQUEST['saiu05estado'] == 7) {
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
$bOculto = true;
// if ((int)$_REQUEST['paso'] == 0) {$bOculto = false;}
echo html_DivTerceroV2('saiu05idsolicitante', $_REQUEST['saiu05idsolicitante_td'], $_REQUEST['saiu05idsolicitante_doc'], $bOculto, 0, $ETI['ing_doc']);
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
<input id="saiu05idmoduloproc" name="saiu05idmoduloproc" type="hidden" value="<?php echo $_REQUEST['saiu05idmoduloproc']; ?>" />
<input id="saiu05identificadormod" name="saiu05identificadormod" type="hidden" value="<?php echo $_REQUEST['saiu05identificadormod']; ?>" />
<input id="saiu05numradicado" name="saiu05numradicado" type="hidden" value="<?php echo $_REQUEST['saiu05numradicado']; ?>" />
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
echo $_REQUEST['saiu05rptacorreo'];
?>
<input id="saiu05rptacorreo" name="saiu05rptacorreo" type="hidden" value="<?php echo $_REQUEST['saiu05rptacorreo']; ?>" />
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
echo $_REQUEST['saiu05rptadireccion'];
?>
<input id="saiu05rptadireccion" name="saiu05rptadireccion" type="hidden" value="<?php echo $_REQUEST['saiu05rptadireccion']; ?>" />
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
<?php echo $_REQUEST['saiu05detalle']; ?>
<div class="salto1px"></div>
</div>
<input id="saiu05detalle" name="saiu05detalle" type="hidden" value="<?php echo $_REQUEST['saiu05detalle']; ?>" />
<div class="salto1px"></div>
<?php
if ($bEsBorrador) {
	if ($bExisteNumRef) {
		echo $objForma->htmlBotonSolo('cmdSolicitar', 'BotonAzul160', "enviacerrar();", $ETI['bt_radicar'], 160);
	}
}
?>
<input id="saiu05infocomplemento" name="saiu05infocomplemento" type="hidden" value="<?php echo $_REQUEST['saiu05infocomplemento']; ?>" />
<input id="saiu05tiemprespdias" name="saiu05tiemprespdias" type="hidden" value="<?php echo $_REQUEST['saiu05tiemprespdias']; ?>" />
<input id="saiu05tiempresphoras" name="saiu05tiempresphoras" type="hidden" value="<?php echo $_REQUEST['saiu05tiempresphoras']; ?>" />
<input id="saiu05fecharespprob" name="saiu05fecharespprob" type="hidden" value="<?php echo $_REQUEST['saiu05fecharespprob']; ?>" />
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
if ($_REQUEST['saiu05estado']==7) {
?>
<label class="Label60">
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
<?php
}
?>
<div class="salto1px"></div>
<div class="GrupoCampos520">
<label class="txtAreaS">
<textarea id="saiu05respuesta" name="saiu05respuesta" placeholder="<?php echo $ETI['ing_campo'] . $ETI['saiu05respuesta']; ?>"><?php echo $_REQUEST['saiu05respuesta']; ?></textarea>
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
?>
<label class="Label30">
<input type="button" id="banexasaiu05idarchivo" name="banexasaiu05idarchivo" value="Anexar" class="btAnexarS" onclick="carga_saiu05idarchivo()" title="Cargar archivo" style="display:<?php if ((int)$_REQUEST['saiu05id'] != 0) {
echo 'block';
} else {
echo 'none';
} ?>;" />
</label>
<label class="Label30">
<input type="button" id="beliminasaiu05idarchivo" name="beliminasaiu05idarchivo" value="Eliminar" class="btBorrarS" onclick="eliminasaiu05idarchivo()" title="Eliminar archivo" style="display:<?php if ((int)$_REQUEST['saiu05idarchivo'] != 0) {
echo 'block';
} else {
echo 'none';
} ?>;" />
</label>
<?php
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
$bCondicion = false;
if ($bCondicion){
?>
<div class="ir_derecha" style="width:62px;">
<!--
<label class="Label30">
<input id="btexcel3007" name="btexcel3007" type="button" value="Exportar" class="btMiniExcel" onclick="imprime3007();" title="Exportar"/>
</label>
-->
<label class="Label30">
<input id="btexpande3007" name="btexpande3007" type="button" value="Expandir" class="btMiniExpandir" onclick="expandepanel(3007,'block',0);" title="<?php echo $ETI['bt_mostrar']; ?>" style="display:<?php if ($_REQUEST['boculta3007'] == 0) {
echo 'none';
} else {
echo 'block';
} ?>;" />
</label>
<label class="Label30">
<input id="btrecoge3007" name="btrecoge3007" type="button" value="Recoger" class="btMiniRecoger" onclick="expandepanel(3007,'none',1);" title="<?php echo $ETI['bt_ocultar']; ?>" style="display:<?php if ($_REQUEST['boculta3007'] == 0) {
echo 'block';
} else {
echo 'none';
} ?>;" />
</label>
</div>
<div class="salto1px"></div>
<div id="div_p3007" style="display:<?php if ($_REQUEST['boculta3007'] == 0) {
echo 'block';
} else {
echo 'none';
} ?>;">
<label class="Label130">
<?php
echo $ETI['saiu07consec'];
?>
</label>
<label class="Label130">
<div id="div_saiu07consec">
<?php
if ((int)$_REQUEST['saiu07id'] == 0) {
?>
<input id="saiu07consec" name="saiu07consec" type="text" value="<?php echo $_REQUEST['saiu07consec']; ?>" onchange="revisaf3007()" class="cuatro" />
<?php
} else {
echo html_oculto('saiu07consec', $_REQUEST['saiu07consec'], formato_numero($_REQUEST['saiu07consec']));
}
?>
</div>
</label>
<label class="Label60">
<?php
echo $ETI['saiu07id'];
?>
</label>
<label class="Label60">
<div id="div_saiu07id">
<?php
echo html_oculto('saiu07id', $_REQUEST['saiu07id'], formato_numero($_REQUEST['saiu07id']));
?>
</div>
</label>
<label class="Label130">
<?php
echo $ETI['saiu07idtipoanexo'];
?>
</label>
<label>
<?php
echo $html_saiu07idtipoanexo;
?>
</label>
<label class="L">
<?php
echo $ETI['saiu07detalle'];
?>

<input id="saiu07detalle" name="saiu07detalle" type="text" value="<?php echo $_REQUEST['saiu07detalle']; ?>" maxlength="100" class="L" placeholder="<?php echo $ETI['ing_campo'] . $ETI['saiu07detalle']; ?>" />
</label>
<input id="saiu07idorigen" name="saiu07idorigen" type="hidden" value="<?php echo $_REQUEST['saiu07idorigen']; ?>" />
<input id="saiu07idarchivo" name="saiu07idarchivo" type="hidden" value="<?php echo $_REQUEST['saiu07idarchivo']; ?>" />
<div class="GrupoCampos300">
<div class="salto1px"></div>
<div id="div_saiu07idarchivo" class="Campo220">
<?php
echo html_lnkarchivo((int)$_REQUEST['saiu07idorigen'], (int)$_REQUEST['saiu07idarchivo']);
?>
</div>
<label class="Label30">
<input type="button" id="banexasaiu07idarchivo" name="banexasaiu07idarchivo" value="Anexar" class="btAnexarS" onclick="carga_saiu07idarchivo()" title="Cargar archivo" style="display:<?php if ((int)$_REQUEST['saiu07id'] != 0) {
echo 'block';
} else {
echo 'none';
} ?>;" />
</label>
<label class="Label30">
<input type="button" id="beliminasaiu07idarchivo" name="beliminasaiu07idarchivo" value="Eliminar" class="btBorrarS" onclick="eliminasaiu07idarchivo()" title="Eliminar archivo" style="display:<?php if ((int)$_REQUEST['saiu07idarchivo'] != 0) {
echo 'block';
} else {
echo 'none';
} ?>;" />
</label>
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo $ETI['saiu07idusuario'];
?>
</label>
<div class="salto1px"></div>
<input id="saiu07idusuario" name="saiu07idusuario" type="hidden" value="<?php echo $_REQUEST['saiu07idusuario']; ?>" />
<div id="div_saiu07idusuario_llaves">
<?php
$bOculto = false;
echo html_DivTerceroV2('saiu07idusuario', $_REQUEST['saiu07idusuario_td'], $_REQUEST['saiu07idusuario_doc'], $bOculto, 0, $ETI['ing_doc']);
?>
</div>
<div class="salto1px"></div>
<div id="div_saiu07idusuario" class="L"><?php echo $saiu07idusuario_rs; ?></div>
<div class="salto1px"></div>
</div>
<label class="Label130">
<?php
echo $ETI['saiu07fecha'];
?>
</label>
<div class="Campo220">
<?php
echo html_FechaEnNumero('saiu07fecha', $_REQUEST['saiu07fecha']); //$bvacio=false,$accion=",$iagnoini=0,$iagnofin=0
?>
</div>
<!--
<label class="Label30">
<input id="bsaiu07fecha_hoy" name="bsaiu07fecha_hoy" type="button" value="Hoy" class="btMiniHoy" onclick="fecha_AsignarNum('saiu07fecha','<?php echo fecha_DiaMod(); ?>')" title="<?php echo $ETI['bt_hoy']; ?>"/>
</label>
-->
<label class="Label130">
<?php
echo $ETI['saiu07hora'];
?>
</label>
<div class="campo_HoraMin" id="div_saiu07hora">
<?php
echo html_HoraMin('saiu07hora', $_REQUEST['saiu07hora'], 'saiu07minuto', $_REQUEST['saiu07minuto']);
?>
</div>
<label class="Label130">
<?php
echo $ETI['saiu07estado'];
?>
</label>
<label>
<div id="div_saiu07estado">
<?php
echo $html_saiu07estado;
?>
</div>
</label>
<div class="salto1px"></div>
<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo $ETI['saiu07idvalidad'];
?>
</label>
<div class="salto1px"></div>
<input id="saiu07idvalidad" name="saiu07idvalidad" type="hidden" value="<?php echo $_REQUEST['saiu07idvalidad']; ?>" />
<div id="div_saiu07idvalidad_llaves">
<?php
$bOculto = false;
echo html_DivTerceroV2('saiu07idvalidad', $_REQUEST['saiu07idvalidad_td'], $_REQUEST['saiu07idvalidad_doc'], $bOculto, 0, $ETI['ing_doc']);
?>
</div>
<div class="salto1px"></div>
<div id="div_saiu07idvalidad" class="L"><?php echo $saiu07idvalidad_rs; ?></div>
<div class="salto1px"></div>
</div>
<label class="Label130">
<?php
echo $ETI['saiu07fechavalida'];
?>
</label>
<div class="Campo220">
<?php
echo html_FechaEnNumero('saiu07fechavalida', $_REQUEST['saiu07fechavalida']); //$bvacio=false,$accion=",$iagnoini=0,$iagnofin=0
?>
</div>
<!--
<label class="Label30">
<input id="bsaiu07fechavalida_hoy" name="bsaiu07fechavalida_hoy" type="button" value="Hoy" class="btMiniHoy" onclick="fecha_AsignarNum('saiu07fechavalida','<?php echo fecha_DiaMod(); ?>')" title="<?php echo $ETI['bt_hoy']; ?>"/>
</label>
-->
<label class="Label130">
<?php
echo $ETI['saiu07horavalida'];
?>
</label>
<div class="campo_HoraMin" id="div_saiu07horavalida">
<?php
echo html_HoraMin('saiu07horavalida', $_REQUEST['saiu07horavalida'], 'saiu07minvalida', $_REQUEST['saiu07minvalida']);
?>
</div>
<div class="salto1px"></div>
<?php
//Este es el cierre del div_p3007
?>
<div class="salto1px"></div>
</div>
<?php
} //Termina el segundo bloque  condicional - bloque editar.
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
<input id="boculta3006" name="boculta3006" type="hidden" value="<?php echo $_REQUEST['boculta3006']; ?>" />
<?php
if ($_REQUEST['paso'] == 2) {
//if ($bCondicion){
?>
<div class="ir_derecha" style="width:62px;">
<!--
<label class="Label30">
<input id="btexcel3006" name="btexcel3006" type="button" value="Exportar" class="btMiniExcel" onclick="imprime3006();" title="Exportar"/>
</label>
-->
<label class="Label30">
<input id="btexpande3006" name="btexpande3006" type="button" value="Expandir" class="btMiniExpandir" onclick="expandepanel(3006,'block',0);" title="<?php echo $ETI['bt_mostrar']; ?>" style="display:<?php if ($_REQUEST['boculta3006'] == 0) {
echo 'none';
} else {
echo 'block';
} ?>;" />
</label>
<label class="Label30">
<input id="btrecoge3006" name="btrecoge3006" type="button" value="Recoger" class="btMiniRecoger" onclick="expandepanel(3006,'none',1);" title="<?php echo $ETI['bt_ocultar']; ?>" style="display:<?php if ($_REQUEST['boculta3006'] == 0) {
echo 'block';
} else {
echo 'none';
} ?>;" />
</label>
</div>
<div class="salto1px"></div>
<div id="div_p3006" style="display:<?php if ($_REQUEST['boculta3006'] == 0) {
echo 'block';
} else {
echo 'none';
} ?>;">
<label class="Label130">
<?php
echo $ETI['saiu06consec'];
?>
</label>
<label class="Label130">
<div id="div_saiu06consec">
<?php
echo html_oculto('saiu06consec', $_REQUEST['saiu06consec'], formato_numero($_REQUEST['saiu06consec']));
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
<label class="Label60">
<input id="saiu06visible" name="saiu06visible" type="hidden" value="<?php echo $_REQUEST['saiu06visible']; ?>" />
</label>
<label class="Label60">
<input id="saiu06descartada" name="saiu06descartada" type="hidden" value="<?php echo $_REQUEST['saiu06descartada']; ?>" />
</label>
<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="txtAreaS">
<textarea id="saiu06anotacion" name="saiu06anotacion" placeholder="<?php echo $ETI['saiu06anotacion']; ?>" readonly disabled><?php echo $_REQUEST['saiu06anotacion']; ?></textarea>
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
<div class="salto1px"></div>
</div>
<?php
}
?>
<div class="salto5px"></div>
</div>
<input id="saiu06idusuario" name="saiu06idusuario" type="hidden" value="<?php echo $_REQUEST['saiu06idusuario']; ?>" />
<input id="saiu06fecha" name="saiu06fecha" type="hidden" value="<?php echo $_REQUEST['saiu06fecha']; ?>" />
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
<input id="saiu05idsupervisor" name="saiu05idsupervisor" type="hidden" value="<?php echo $_REQUEST['saiu05idsupervisor']; ?>" />
<input id="saiu05idresponsable" name="saiu05idresponsable" type="hidden" value="<?php echo $_REQUEST['saiu05idresponsable']; ?>" />
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
<input id="bdoc" name="bdoc" type="hidden" value="<?php echo $_REQUEST['bdoc']; ?>" />
<input id="bnombre" name="bnombre" type="hidden" value="<?php echo $_REQUEST['bnombre']; ?>" />
<input id="bref" name="bref" type="hidden" value="<?php echo $_REQUEST['bref']; ?>" />
<input id="btipo" name="btipo" type="hidden" value="<?php echo $_REQUEST['btipo']; ?>" />
<input id="bcategoria" name="bcategoria" type="hidden" value="<?php echo $_REQUEST['bcategoria']; ?>" />
<input id="btema" name="btema" type="hidden" value="<?php echo $_REQUEST['btema']; ?>" />
<input id="blistar" name="blistar" type="hidden" value="<?php echo $_REQUEST['blistar']; ?>" />
<input id="bestado" name="bestado" type="hidden" value="<?php echo $_REQUEST['bestado']; ?>" />
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
// $objForma->addBoton('cmdAyuda93', 'btSupAyuda', 'muestraayuda(' . $iCodModulo . ');', $ETI['bt_ayuda']);
// $objForma->addBoton('cmdVolverSec93', 'btSupVolver', 'expandesector(1);', $ETI['bt_volver']);
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
<input id="titulo_3005" name="titulo_3005" type="hidden" value="<?php echo $ETI['titulo_3005']; ?>" />
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
<script language="javascript" src="<?php echo $APP->rutacomun; ?>unad_todas2024.js"></script>
<?php
forma_piedepagina();

