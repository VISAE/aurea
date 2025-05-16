<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2024 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 3.0.11b miércoles, 14 de agosto de 2024
*/
/** Archivo saiusolusuario.php.
 * Modulo 3073 saiu73solusuario
 * @author Omar Augusto Bautista Mora - omar.bautista@unad.edu.co
 * @param debug = 1  (Opcional), bandera para indicar si se generan datos de depuración
 * @date jueves, 17 de octubre de 2024
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
	if ($bDebug) {
		echo 'No se encuentra una sesi&oacute;n. [' . $APP->rutacomun . ']-[' . $_SESSION['unad_id_tercero'] . ']';
		die();
	}
	$_SESSION['unad_redir'] = 'saisolusuario.php';
	header('Location:index.php');
	die();
}
$iConsecutivoMenu = 2;
$iMinVerDB = 7774;
$iCodModulo = 3073;
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
/*
$mensajes_000 = 'lg/lg_000_' . $_SESSION['unad_idioma'] . '.php';
if (!file_exists($mensajes_000)) {
	$mensajes_000 = 'lg/lg_000_es.php';
}
require $mensajes_000;
*/
$mensajes_3000 = $APP->rutacomun . 'lg/lg_3000_' . $_SESSION['unad_idioma'] . '.php';
if (!file_exists($mensajes_3000)) {
	$mensajes_3000 = $APP->rutacomun . 'lg/lg_3000_es.php';
}
$mensajes_3073 = $APP->rutacomun . 'lg/lg_3073_' . $_SESSION['unad_idioma'] . '.php';
if (!file_exists($mensajes_3073)) {
	$mensajes_3073 = $APP->rutacomun . 'lg/lg_3073_es.php';
}
require $mensajes_todas;
require $mensajes_3000;
require $mensajes_3073;
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
$sTituloModulo = $ETI['titulo_3073'];
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
	$bDevuelve = true;
	//list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModuloConsulta, 1, $_SESSION['unad_id_tercero'], $objDB);
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
			require $APP->rutacomun . 'unad_forma2024.php';
			forma_InicioV4($xajax, $sTituloModulo);
			$aRutas = array(
				array('./sai.php', $sTituloApp),
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
		echo $sDebug;
	}
	echo $objForma->htmlFinMarco();
	forma_piedepagina();
	die();
}
if (!$bPeticionXAJAX) {
	if (noticias_pendientes($objDB)) {
		$objDB->CerrarConexion();
		header('Location:noticia.php?ret=saiusolusuario.php');
		die();
	}
}
$bOtroUsuario = false;
$seg_1707 = 0;
$bDevuelve = false;
list($bDevuelve, $sDebugP, $seg_1707) = seg_revisa_permisoV3($iCodModulo, 1707, $_SESSION['unad_id_tercero'], $objDB, $bDebug);
//$sDebug = $sDebug . $sDebugP;
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
// -- Si esta cargando la pagina por primer vez se revisa si requiere auditar y se manda a hacer un limpiar (paso -1)
if (isset($_REQUEST['paso']) == 0) {
	$_REQUEST['paso'] = -1;
	if ($audita[1]) {
		seg_auditaingreso($iCodModulo, $_SESSION['unad_id_tercero'], $objDB);
	}
}
// -- 3073 saiu73solusuario
require $APP->rutacomun . 'lib3073.php';
// -- 3000 Historial de solicitudes
require $APP->rutacomun . 'lib3000.php';
$xajax = new xajax();
$xajax->configure('javascript URI', $APP->rutacomun . 'xajax/');
$xajax->register(XAJAX_FUNCTION, 'unad11_Mostrar_v2');
$xajax->register(XAJAX_FUNCTION, 'unad11_Mostrar_v2SAI');
$xajax->register(XAJAX_FUNCTION, 'unad11_TraerXid');
$xajax->register(XAJAX_FUNCTION, 'unad11_TraerXidSAI');
$xajax->register(XAJAX_FUNCTION, 'f3073_Combosaiu73tiposolicitud');
$xajax->register(XAJAX_FUNCTION, 'f3073_Combosaiu73temasolicitud');
$xajax->register(XAJAX_FUNCTION, 'f3073_Combosaiu73idcentro');
$xajax->register(XAJAX_FUNCTION, 'f3073_Combosaiu73coddepto');
$xajax->register(XAJAX_FUNCTION, 'f3073_Combosaiu73codciudad');
$xajax->register(XAJAX_FUNCTION, 'f3073_Combosaiu73idprograma');
$xajax->register(XAJAX_FUNCTION, 'sesion_abandona_V2');
$xajax->register(XAJAX_FUNCTION, 'sesion_mantenerV4');
$xajax->register(XAJAX_FUNCTION, 'f3073_HtmlTabla');
$xajax->register(XAJAX_FUNCTION, 'f3073_ExisteDato');
$xajax->register(XAJAX_FUNCTION, 'f3073_Busquedas');
$xajax->register(XAJAX_FUNCTION, 'f3073_HtmlBusqueda');
$xajax->register(XAJAX_FUNCTION, 'elimina_archivo_saiu73idarchivo');
$xajax->register(XAJAX_FUNCTION, 'elimina_archivo_saiu73idarchivorta');
$xajax->register(XAJAX_FUNCTION, 'f3073_Combobtema');
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
if (isset($_REQUEST['paginaf3073']) == 0) {
	$_REQUEST['paginaf3073'] = 1;
}
if (isset($_REQUEST['lppf3073']) == 0) {
	$_REQUEST['lppf3073'] = 20;
}
if (isset($_REQUEST['boculta3073']) == 0) {
	$_REQUEST['boculta3073'] = 0;
}
// -- Inicializar variables de datos.
if (isset($_REQUEST['saiu73agno']) == 0) {
	$_REQUEST['saiu73agno'] = fecha_agno();
}
if (isset($_REQUEST['saiu73mes']) == 0) {
	$_REQUEST['saiu73mes'] = '';
}
if (isset($_REQUEST['saiu73tiporadicado']) == 0) {
	$_REQUEST['saiu73tiporadicado'] = 1;
}
if (isset($_REQUEST['saiu73consec']) == 0) {
	$_REQUEST['saiu73consec'] = '';
}
if (isset($_REQUEST['saiu73consec_nuevo']) == 0) {
	$_REQUEST['saiu73consec_nuevo'] = '';
}
if (isset($_REQUEST['saiu73id']) == 0) {
	$_REQUEST['saiu73id'] = '';
}
if (isset($_REQUEST['saiu73dia']) == 0) {
	$_REQUEST['saiu73dia'] = fecha_dia();
}
if (isset($_REQUEST['saiu73hora']) == 0) {
	$_REQUEST['saiu73hora'] = fecha_hora();
}
if (isset($_REQUEST['saiu73minuto']) == 0) {
	$_REQUEST['saiu73minuto'] = fecha_minuto();
}
if (isset($_REQUEST['saiu73estado']) == 0) {
	$_REQUEST['saiu73estado'] = -1;
}
if (isset($_REQUEST['saiu73estadoorigen']) == 0) {
	$_REQUEST['saiu73estadoorigen'] = -1;
}
if (isset($_REQUEST['saiu73idsolicitante']) == 0) {
	$_REQUEST['saiu73idsolicitante'] = $_SESSION['unad_id_tercero'];
}
if (isset($_REQUEST['saiu73idsolicitante_td']) == 0) {
	$_REQUEST['saiu73idsolicitante_td'] = $APP->tipo_doc;
}
if (isset($_REQUEST['saiu73idsolicitante_doc']) == 0) {
	$_REQUEST['saiu73idsolicitante_doc'] = '';
}
if (isset($_REQUEST['saiu73tipointeresado']) == 0) {
	$_REQUEST['saiu73tipointeresado'] = '';
}
if (isset($_REQUEST['saiu73clasesolicitud']) == 0) {
	$_REQUEST['saiu73clasesolicitud'] = 0;
}
if (isset($_REQUEST['saiu73tiposolicitud']) == 0) {
	$_REQUEST['saiu73tiposolicitud'] = 58;
}
if (isset($_REQUEST['saiu73temasolicitud']) == 0) {
	$_REQUEST['saiu73temasolicitud'] = 0;
}
if (isset($_REQUEST['saiu73temasolicitudorigen']) == 0) {
	$_REQUEST['saiu73temasolicitudorigen'] = '';
}
if (isset($_REQUEST['saiu73idzona']) == 0) {
	$_REQUEST['saiu73idzona'] = '';
}
if (isset($_REQUEST['saiu73idcentro']) == 0) {
	$_REQUEST['saiu73idcentro'] = '';
}
if (isset($_REQUEST['saiu73codpais']) == 0) {
	$_REQUEST['saiu73codpais'] = $_SESSION['unad_pais'];
}
if (isset($_REQUEST['saiu73coddepto']) == 0) {
	$_REQUEST['saiu73coddepto'] = '';
}
if (isset($_REQUEST['saiu73codciudad']) == 0) {
	$_REQUEST['saiu73codciudad'] = '';
}
if (isset($_REQUEST['saiu73idescuela']) == 0) {
	$_REQUEST['saiu73idescuela'] = '';
}
if (isset($_REQUEST['saiu73idprograma']) == 0) {
	$_REQUEST['saiu73idprograma'] = '';
}
if (isset($_REQUEST['saiu73idperiodo']) == 0) {
	$_REQUEST['saiu73idperiodo'] = '';
}
if (isset($_REQUEST['saiu73idpqrs']) == 0) {
	$_REQUEST['saiu73idpqrs'] = 0;
}
if (isset($_REQUEST['saiu73detalle']) == 0) {
	$_REQUEST['saiu73detalle'] = '';
}
if (isset($_REQUEST['saiu73fechafin']) == 0) {
	$_REQUEST['saiu73fechafin'] = '';
}
if (isset($_REQUEST['saiu73horafin']) == 0) {
	$_REQUEST['saiu73horafin'] = '';
}
if (isset($_REQUEST['saiu73minutofin']) == 0) {
	$_REQUEST['saiu73minutofin'] = '';
}
if (isset($_REQUEST['saiu73paramercadeo']) == 0) {
	$_REQUEST['saiu73paramercadeo'] = 0;
}
if (isset($_REQUEST['saiu73idresponsable']) == 0) {
	$_REQUEST['saiu73idresponsable'] = $_SESSION['unad_id_tercero'];
}
if (isset($_REQUEST['saiu73idresponsable_td']) == 0) {
	$_REQUEST['saiu73idresponsable_td'] = $APP->tipo_doc;
}
if (isset($_REQUEST['saiu73idresponsable_doc']) == 0) {
	$_REQUEST['saiu73idresponsable_doc'] = '';
}
if (isset($_REQUEST['saiu73tiemprespdias']) == 0) {
	$_REQUEST['saiu73tiemprespdias'] = '';
}
if (isset($_REQUEST['saiu73tiempresphoras']) == 0) {
	$_REQUEST['saiu73tiempresphoras'] = '';
}
if (isset($_REQUEST['saiu73tiemprespminutos']) == 0) {
	$_REQUEST['saiu73tiemprespminutos'] = '';
}
if (isset($_REQUEST['saiu73solucion']) == 0) {
	$_REQUEST['saiu73solucion'] = 0;
}
if (isset($_REQUEST['saiu73idcaso']) == 0) {
	$_REQUEST['saiu73idcaso'] = 0;
}
if (isset($_REQUEST['saiu73respuesta']) == 0) {
	$_REQUEST['saiu73respuesta'] = '';
}
if (isset($_REQUEST['saiu73idorigen']) == 0) {
	$_REQUEST['saiu73idorigen'] = 0;
}
if (isset($_REQUEST['saiu73idarchivo']) == 0) {
	$_REQUEST['saiu73idarchivo'] = 0;
}
if (isset($_REQUEST['saiu73idorigenrta']) == 0) {
	$_REQUEST['saiu73idorigenrta'] = 0;
}
if (isset($_REQUEST['saiu73idarchivorta']) == 0) {
	$_REQUEST['saiu73idarchivorta'] = 0;
}
if (isset($_REQUEST['saiu73fecharespcaso']) == 0) {
	$_REQUEST['saiu73fecharespcaso'] = '';
}
if (isset($_REQUEST['saiu73horarespcaso']) == 0) {
	$_REQUEST['saiu73horarespcaso'] = 0;
}
if (isset($_REQUEST['saiu73minrespcaso']) == 0) {
	$_REQUEST['saiu73minrespcaso'] = 0;
}
if (isset($_REQUEST['saiu73idunidadcaso']) == 0) {
	$_REQUEST['saiu73idunidadcaso'] = 0;
}
if (isset($_REQUEST['saiu73idequipocaso']) == 0) {
	$_REQUEST['saiu73idequipocaso'] = 0;
}
if (isset($_REQUEST['saiu73idsupervisorcaso']) == 0) {
	$_REQUEST['saiu73idsupervisorcaso'] = 0;
}
if (isset($_REQUEST['saiu73idresponsablecaso']) == 0) {
	$_REQUEST['saiu73idresponsablecaso'] = 0;
}
if (isset($_REQUEST['saiu73numref']) == 0) {
	$_REQUEST['saiu73numref'] = '';
}
if (isset($_REQUEST['saiu73idresponsablecaso_td']) == 0) {
	$_REQUEST['saiu73idresponsablecaso_td'] = $APP->tipo_doc;
}
if (isset($_REQUEST['saiu73idresponsablecaso_doc']) == 0) {
	$_REQUEST['saiu73idresponsablecaso_doc'] = '';
}
if (isset($_REQUEST['saiu73idcanal']) == 0) {
	$_REQUEST['saiu73idcanal'] = $iCodModulo;
}
if (isset($_REQUEST['saiu73idtelefono']) == 0) {
	$_REQUEST['saiu73idtelefono'] = 0;
}
if (isset($_REQUEST['saiu73numtelefono']) == 0) {
	$_REQUEST['saiu73numtelefono'] = '';
}
if (isset($_REQUEST['saiu73numorigen']) == 0) {
	$_REQUEST['saiu73numorigen'] = '';
}
if (isset($_REQUEST['saiu73idchat']) == 0) {
	$_REQUEST['saiu73idchat'] = 0;
}
if (isset($_REQUEST['saiu73numsesionchat']) == 0) {
	$_REQUEST['saiu73numsesionchat'] = '';
}
if (isset($_REQUEST['saiu73idcorreo']) == 0) {
	$_REQUEST['saiu73idcorreo'] = 0;
}
if (isset($_REQUEST['saiu73idcorreootro']) == 0) {
	$_REQUEST['saiu73idcorreootro'] = '';
}
if (isset($_REQUEST['saiu73correoorigen']) == 0) {
	$_REQUEST['saiu73correoorigen'] = '';
}
$_REQUEST['saiu73agno'] = numeros_validar($_REQUEST['saiu73agno']);
$_REQUEST['saiu73mes'] = numeros_validar($_REQUEST['saiu73mes']);
$_REQUEST['saiu73tiporadicado'] = numeros_validar($_REQUEST['saiu73tiporadicado']);
$_REQUEST['saiu73consec'] = numeros_validar($_REQUEST['saiu73consec']);
$_REQUEST['saiu73id'] = numeros_validar($_REQUEST['saiu73id']);
$_REQUEST['saiu73dia'] = numeros_validar($_REQUEST['saiu73dia']);
$_REQUEST['saiu73hora'] = numeros_validar($_REQUEST['saiu73hora']);
$_REQUEST['saiu73minuto'] = numeros_validar($_REQUEST['saiu73minuto']);
$_REQUEST['saiu73estado'] = numeros_validar($_REQUEST['saiu73estado']);
$_REQUEST['saiu73estadoorigen'] = numeros_validar($_REQUEST['saiu73estadoorigen']);
$_REQUEST['saiu73idsolicitante'] = numeros_validar($_REQUEST['saiu73idsolicitante']);
$_REQUEST['saiu73idsolicitante_td'] = cadena_Validar($_REQUEST['saiu73idsolicitante_td']);
$_REQUEST['saiu73idsolicitante_doc'] = cadena_Validar($_REQUEST['saiu73idsolicitante_doc']);
$_REQUEST['saiu73tipointeresado'] = numeros_validar($_REQUEST['saiu73tipointeresado']);
$_REQUEST['saiu73clasesolicitud'] = numeros_validar($_REQUEST['saiu73clasesolicitud']);
$_REQUEST['saiu73tiposolicitud'] = numeros_validar($_REQUEST['saiu73tiposolicitud']);
$_REQUEST['saiu73temasolicitud'] = numeros_validar($_REQUEST['saiu73temasolicitud']);
$_REQUEST['saiu73temasolicitudorigen'] = numeros_validar($_REQUEST['saiu73temasolicitudorigen']);
$_REQUEST['saiu73idzona'] = numeros_validar($_REQUEST['saiu73idzona']);
$_REQUEST['saiu73idcentro'] = numeros_validar($_REQUEST['saiu73idcentro']);
$_REQUEST['saiu73codpais'] = cadena_Validar($_REQUEST['saiu73codpais']);
$_REQUEST['saiu73coddepto'] = cadena_Validar($_REQUEST['saiu73coddepto']);
$_REQUEST['saiu73codciudad'] = cadena_Validar($_REQUEST['saiu73codciudad']);
$_REQUEST['saiu73idescuela'] = numeros_validar($_REQUEST['saiu73idescuela']);
$_REQUEST['saiu73idprograma'] = numeros_validar($_REQUEST['saiu73idprograma']);
$_REQUEST['saiu73idperiodo'] = numeros_validar($_REQUEST['saiu73idperiodo']);
$_REQUEST['saiu73idpqrs'] = numeros_validar($_REQUEST['saiu73idpqrs']);
$_REQUEST['saiu73detalle'] = cadena_Validar($_REQUEST['saiu73detalle']);
$_REQUEST['saiu73idorigen'] = numeros_validar($_REQUEST['saiu73idorigen']);
$_REQUEST['saiu73idarchivo'] = numeros_validar($_REQUEST['saiu73idarchivo']);
$_REQUEST['saiu73fechafin'] = numeros_validar($_REQUEST['saiu73fechafin']);
$_REQUEST['saiu73horafin'] = numeros_validar($_REQUEST['saiu73horafin']);
$_REQUEST['saiu73minutofin'] = numeros_validar($_REQUEST['saiu73minutofin']);
$_REQUEST['saiu73paramercadeo'] = numeros_validar($_REQUEST['saiu73paramercadeo']);
$_REQUEST['saiu73idresponsable'] = numeros_validar($_REQUEST['saiu73idresponsable']);
$_REQUEST['saiu73idresponsable_td'] = cadena_Validar($_REQUEST['saiu73idresponsable_td']);
$_REQUEST['saiu73idresponsable_doc'] = cadena_Validar($_REQUEST['saiu73idresponsable_doc']);
$_REQUEST['saiu73tiemprespdias'] = numeros_validar($_REQUEST['saiu73tiemprespdias']);
$_REQUEST['saiu73tiempresphoras'] = numeros_validar($_REQUEST['saiu73tiempresphoras']);
$_REQUEST['saiu73tiemprespminutos'] = numeros_validar($_REQUEST['saiu73tiemprespminutos']);
$_REQUEST['saiu73solucion'] = numeros_validar($_REQUEST['saiu73solucion']);
$_REQUEST['saiu73idcaso'] = numeros_validar($_REQUEST['saiu73idcaso']);
$_REQUEST['saiu73respuesta'] = cadena_Validar($_REQUEST['saiu73respuesta']);
$_REQUEST['saiu73idorigenrta'] = numeros_validar($_REQUEST['saiu73idorigenrta']);
$_REQUEST['saiu73idarchivorta'] = numeros_validar($_REQUEST['saiu73idarchivorta']);
$_REQUEST['saiu73fecharespcaso'] = numeros_validar($_REQUEST['saiu73fecharespcaso']);
$_REQUEST['saiu73horarespcaso'] = numeros_validar($_REQUEST['saiu73horarespcaso']);
$_REQUEST['saiu73minrespcaso'] = numeros_validar($_REQUEST['saiu73minrespcaso']);
$_REQUEST['saiu73idunidadcaso'] = numeros_validar($_REQUEST['saiu73idunidadcaso']);
$_REQUEST['saiu73idequipocaso'] = numeros_validar($_REQUEST['saiu73idequipocaso']);
$_REQUEST['saiu73idsupervisorcaso'] = numeros_validar($_REQUEST['saiu73idsupervisorcaso']);
$_REQUEST['saiu73idresponsablecaso'] = numeros_validar($_REQUEST['saiu73idresponsablecaso']);
$_REQUEST['saiu73numref'] = cadena_Validar($_REQUEST['saiu73numref']);
$_REQUEST['saiu73idcanal'] = numeros_validar($_REQUEST['saiu73idcanal']);
$_REQUEST['saiu73idtelefono'] = numeros_validar($_REQUEST['saiu73idtelefono']);
$_REQUEST['saiu73numtelefono'] = cadena_Validar($_REQUEST['saiu73numtelefono']);
$_REQUEST['saiu73numorigen'] = cadena_Validar($_REQUEST['saiu73numorigen']);
$_REQUEST['saiu73idchat'] = numeros_validar($_REQUEST['saiu73idchat']);
$_REQUEST['saiu73numsesionchat'] = cadena_Validar($_REQUEST['saiu73numsesionchat']);
$_REQUEST['saiu73idcorreo'] = numeros_validar($_REQUEST['saiu73idcorreo']);
$_REQUEST['saiu73idcorreootro'] = cadena_Validar($_REQUEST['saiu73idcorreootro']);
$_REQUEST['saiu73correoorigen'] = cadena_Validar($_REQUEST['saiu73correoorigen']);
// Espacio para inicializar otras variables
if (isset($_REQUEST['csv_separa']) == 0) {
	$_REQUEST['csv_separa'] = ',';
}
if (isset($_REQUEST['bagno']) == 0) {
	$_REQUEST['bagno'] = fecha_agno();
}
if (isset($_REQUEST['bcampus']) == 0) {
	$_REQUEST['bcampus'] = 1;
}
if (isset($_REQUEST['vdtipointeresado']) == 0) {
	$sVr = '';
	$sSQL = 'SELECT bita07id FROM bita07tiposolicitante WHERE bita07predet="S" ORDER BY bita07orden, bita07nombre';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$sVr = $fila['bita07id'];
	}
	$_REQUEST['vdtipointeresado'] = $sVr;
}
if (isset($_REQUEST['vdidtelefono']) == 0) {
	$sVr = '';
	$sSQL = 'SELECT saiu22id FROM saiu22telefonos WHERE saiu22predet=1 ORDER BY saiu22orden, saiu22consec';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$sVr = $fila['saiu22id'];
	}
	$_REQUEST['vdidtelefono'] = $sVr;
}
if (isset($_REQUEST['saiucanal']) == 0) {
	$_REQUEST['saiucanal'] = 5;
}
$sTabla73 = 'saiu73solusuario_' . $_REQUEST['saiu73agno'];
$bExiste = $objDB->bexistetabla($sTabla73);
if (!$bExiste) {
	$sError = $ERR['msg_contenedor'] . ' ' . $sTabla73 . '';
	$_REQUEST['paso'] = -1;
}
//Si Modifica o Elimina Cargar los campos
if (($_REQUEST['paso'] == 1) || ($_REQUEST['paso'] == 3)) {
	$_REQUEST['saiu73idsolicitante_td'] = $APP->tipo_doc;
	$_REQUEST['saiu73idsolicitante_doc'] = '';
	$_REQUEST['saiu73idresponsable_td'] = $APP->tipo_doc;
	$_REQUEST['saiu73idresponsable_doc'] = '';
	$_REQUEST['saiu73idresponsablecaso_td'] = $APP->tipo_doc;
	$_REQUEST['saiu73idresponsablecaso_doc'] = '';
	if ($_REQUEST['paso'] == 1) {
		$sSQLcondi = 'saiu73agno=' . $_REQUEST['saiu73agno'] . ' AND saiu73mes=' . $_REQUEST['saiu73mes'] . ' AND saiu73tiporadicado=' . $_REQUEST['saiu73tiporadicado'] . ' AND saiu73consec=' . $_REQUEST['saiu73consec'] . '';
	} else {
		$sSQLcondi = 'saiu73id=' . $_REQUEST['saiu73id'] . '';
	}
	$sSQL = 'SELECT * FROM ' . $sTabla73 . ' WHERE ' . $sSQLcondi;
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' Consulta registro: ' . $sSQL . '<br>';
	}
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$_REQUEST['saiu73agno'] = $fila['saiu73agno'];
		$_REQUEST['saiu73mes'] = $fila['saiu73mes'];
		$_REQUEST['saiu73tiporadicado'] = $fila['saiu73tiporadicado'];
		$_REQUEST['saiu73consec'] = $fila['saiu73consec'];
		$_REQUEST['saiu73id'] = $fila['saiu73id'];
		$_REQUEST['saiu73dia'] = $fila['saiu73dia'];
		$_REQUEST['saiu73hora'] = $fila['saiu73hora'];
		$_REQUEST['saiu73minuto'] = $fila['saiu73minuto'];
		$_REQUEST['saiu73estado'] = $fila['saiu73estado'];
		$_REQUEST['saiu73estadoorigen'] = $fila['saiu73estado'];
		$_REQUEST['saiu73idsolicitante'] = $fila['saiu73idsolicitante'];
		$_REQUEST['saiu73tipointeresado'] = $fila['saiu73tipointeresado'];
		$_REQUEST['saiu73clasesolicitud'] = $fila['saiu73clasesolicitud'];
		$_REQUEST['saiu73tiposolicitud'] = $fila['saiu73tiposolicitud'];
		$_REQUEST['saiu73temasolicitud'] = $fila['saiu73temasolicitud'];
		$_REQUEST['saiu73temasolicitudorigen'] = $fila['saiu73temasolicitud'];
		$_REQUEST['saiu73idzona'] = $fila['saiu73idzona'];
		$_REQUEST['saiu73idcentro'] = $fila['saiu73idcentro'];
		$_REQUEST['saiu73codpais'] = $fila['saiu73codpais'];
		$_REQUEST['saiu73coddepto'] = $fila['saiu73coddepto'];
		$_REQUEST['saiu73codciudad'] = $fila['saiu73codciudad'];
		$_REQUEST['saiu73idescuela'] = $fila['saiu73idescuela'];
		$_REQUEST['saiu73idprograma'] = $fila['saiu73idprograma'];
		$_REQUEST['saiu73idperiodo'] = $fila['saiu73idperiodo'];
		$_REQUEST['saiu73idpqrs'] = $fila['saiu73idpqrs'];
		$_REQUEST['saiu73detalle'] = $fila['saiu73detalle'];
		$_REQUEST['saiu73fechafin'] = $fila['saiu73fechafin'];
		$_REQUEST['saiu73horafin'] = $fila['saiu73horafin'];
		$_REQUEST['saiu73minutofin'] = $fila['saiu73minutofin'];
		$_REQUEST['saiu73paramercadeo'] = $fila['saiu73paramercadeo'];
		$_REQUEST['saiu73idresponsable'] = $fila['saiu73idresponsable'];
		$_REQUEST['saiu73tiemprespdias'] = $fila['saiu73tiemprespdias'];
		$_REQUEST['saiu73tiempresphoras'] = $fila['saiu73tiempresphoras'];
		$_REQUEST['saiu73tiemprespminutos'] = $fila['saiu73tiemprespminutos'];
		$_REQUEST['saiu73solucion'] = $fila['saiu73solucion'];
		$_REQUEST['saiu73idcaso'] = $fila['saiu73idcaso'];
		$_REQUEST['saiu73respuesta'] = $fila['saiu73respuesta'];
		$_REQUEST['saiu73idorigen'] = $fila['saiu73idorigen'];
		$_REQUEST['saiu73idarchivo'] = $fila['saiu73idarchivo'];
		$_REQUEST['saiu73idorigenrta'] = $fila['saiu73idorigenrta'];
		$_REQUEST['saiu73idarchivorta'] = $fila['saiu73idarchivorta'];
		$_REQUEST['saiu73fecharespcaso'] = $fila['saiu73fecharespcaso'];
		$_REQUEST['saiu73horarespcaso'] = $fila['saiu73horarespcaso'];
		$_REQUEST['saiu73minrespcaso'] = $fila['saiu73minrespcaso'];
		$_REQUEST['saiu73idunidadcaso'] = $fila['saiu73idunidadcaso'];
		$_REQUEST['saiu73idequipocaso'] = $fila['saiu73idequipocaso'];
		$_REQUEST['saiu73idsupervisorcaso'] = $fila['saiu73idsupervisorcaso'];
		$_REQUEST['saiu73idresponsablecaso'] = $fila['saiu73idresponsablecaso'];
		$_REQUEST['saiu73numref'] = $fila['saiu73numref'];
		$_REQUEST['saiu73idcanal'] = $fila['saiu73idcanal'];
		$_REQUEST['saiu73idtelefono'] = $fila['saiu73idtelefono'];
		$_REQUEST['saiu73numtelefono'] = $fila['saiu73numtelefono'];
		$_REQUEST['saiu73numorigen'] = $fila['saiu73numorigen'];
		$_REQUEST['saiu73idchat'] = $fila['saiu73idchat'];
		$_REQUEST['saiu73numsesionchat'] = $fila['saiu73numsesionchat'];
		$_REQUEST['saiu73idcorreo'] = $fila['saiu73idcorreo'];
		$_REQUEST['saiu73idcorreootro'] = $fila['saiu73idcorreootro'];
		$_REQUEST['saiu73correoorigen'] = $fila['saiu73correoorigen'];
		$bcargo = true;
		$_REQUEST['paso'] = 2;
		$_REQUEST['boculta3073'] = 0;
		$bLimpiaHijos = true;
	} else {
		$_REQUEST['paso'] = 0;
	}
}
//Cerrar
$bCerrando = false;
if ($_REQUEST['paso'] == 16) {
	$_REQUEST['paso'] = 12;
	$_REQUEST['saiu73estado'] = 7;
	$bCerrando = true;
}
if ($_REQUEST['paso'] == 21) {
	$_REQUEST['paso'] = 12;
	$_REQUEST['saiu73estado'] = 8;
	$bCerrando = true;
}
if ($_REQUEST['paso'] == 22) {
	$_REQUEST['paso'] = 12;
	$_REQUEST['saiu73estado'] = 9;
	$bCerrando = true;
}
//Abrir
if ($_REQUEST['paso'] == 17) {
	$_REQUEST['paso'] = 2;
	//Es posible que deba definir el codigo de permiso para abrir.
	if (!seg_revisa_permiso($iCodModulo, 17, $objDB)) {
		$sError = $ERR['3'];
	}
	//Otras restricciones para abrir.
	if ($sError == '') {
		//$sError='Motivo por el que no se pueda abrir, no se permite modificar.';
	}
	if ($sError != '') {
		$_REQUEST['saiu73estado'] = 7;
	} else {
		$saiu73estado = 2;
		if ($_REQUEST['saiu73idcaso'] != 0) {
			$saiu73estado = 1;
		}
		$sSQL = 'UPDATE ' . $sTabla73 . ' SET saiu73estado=' . $saiu73estado . ' WHERE saiu73id=' . $_REQUEST['saiu73id'] . '';
		$tabla = $objDB->ejecutasql($sSQL);
		seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 3, $_REQUEST['saiu73id'], 'Abre Registro de atencion a usuario', $objDB);
		$_REQUEST['saiu73estado'] = $saiu73estado;
		$sError = '<b>El registro ha sido abierto</b>';
		$iTipoError = 1;
	}
}
//Insertar o modificar un elemento
if (($_REQUEST['paso'] == 10) || ($_REQUEST['paso'] == 12)) {
	$bMueveScroll = true;
	list($_REQUEST, $sError, $iTipoError, $sDebugGuardar) = f3073_db_GuardarV2($_REQUEST, $objDB, $bDebug, $idTercero, $iCodModulo);
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
	} else {
		if ($_REQUEST['paso'] == 0) {
			if ($_REQUEST['saiu73estado'] != 1) {
				$_REQUEST['saiu73estado'] = -1;
			}
		}
	}
}
// Cambio de consecutivo.
if ($_REQUEST['paso'] == 93) {
	$_REQUEST['paso'] = 2;
	$_REQUEST['saiu73consec_nuevo'] = numeros_validar($_REQUEST['saiu73consec_nuevo']);
	if ($_REQUEST['saiu73consec_nuevo'] == '') {
		$sError = $ERR['saiu73consec'];
	}
	if ($sError == '') {
		list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 8, $idTercero, $objDB);
		if (!$bDevuelve) {
			$sError = $ERR['8'];
		}
	}
	if ($sError == '') {
		//Ver que el consecutivo no exista.
		$sSQL = 'SELECT saiu73id FROM ' . $sTabla73 . ' WHERE saiu73consec=' . $_REQUEST['saiu73consec_nuevo'] . ' AND saiu73tiporadicado=' . $_REQUEST['saiu73tiporadicado'] . ' AND saiu73mes=' . $_REQUEST['saiu73mes'] . ' AND saiu73agno=' . $_REQUEST['saiu73agno'] . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$sError = 'El consecutivo ' . $_REQUEST['saiu73consec_nuevo'] . ' ya existe';
		}
	}
	if ($sError == '') {
		//Aplicar el cambio.
		$sSQL = 'UPDATE ' . $sTabla73 . ' SET saiu73consec=' . $_REQUEST['saiu73consec_nuevo'] . ' WHERE saiu73id=' . $_REQUEST['saiu73id'] . '';
		$tabla = $objDB->ejecutasql($sSQL);
		$sDetalle = 'Cambia el consecutivo de ' . $_REQUEST['saiu73consec'] . ' a ' . $_REQUEST['saiu73consec_nuevo'] . '';
		$_REQUEST['saiu73consec'] = $_REQUEST['saiu73consec_nuevo'];
		$_REQUEST['saiu73consec_nuevo'] = '';
		seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 8, $_REQUEST['saiu73id'], $sDetalle, $objDB);
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
		list($sError, $iTipoError, $sDebugElimina)=f3073_db_Eliminar($_REQUEST['saiu73agno'], $_REQUEST['saiu73id'], $objDB, $bDebug);
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
	$_REQUEST['saiu73agno'] = fecha_agno();
	$_REQUEST['saiu73mes'] = fecha_mes();
	//$_REQUEST['saiu73tiporadicado']='';
	$_REQUEST['saiu73consec'] = '';
	$_REQUEST['saiu73consec_nuevo'] = '';
	$_REQUEST['saiu73id'] = '';
	$_REQUEST['saiu73dia'] = fecha_dia();
	$_REQUEST['saiu73hora'] = fecha_hora();
	$_REQUEST['saiu73minuto'] = fecha_minuto();
	$_REQUEST['saiu73estado'] = -1;
	$_REQUEST['saiu73estadoorigen'] = -1;
	$_REQUEST['saiu73idsolicitante'] = $idTercero;
	$_REQUEST['saiu73idsolicitante_td'] = $APP->tipo_doc;
	$_REQUEST['saiu73idsolicitante_doc'] = '';
	$_REQUEST['saiu73tipointeresado'] = 0;
	$_REQUEST['saiu73clasesolicitud'] = 0;
	$_REQUEST['saiu73tiposolicitud'] = 58;
	$_REQUEST['saiu73temasolicitud'] = 0;
	$_REQUEST['saiu73temasolicitudorigen'] = '';
	$_REQUEST['saiu73idzona'] = '';
	$_REQUEST['saiu73idcentro'] = '';
	$_REQUEST['saiu73codpais'] = $_SESSION['unad_pais'];
	$_REQUEST['saiu73coddepto'] = '';
	$_REQUEST['saiu73codciudad'] = '';
	$_REQUEST['saiu73idescuela'] = 0;
	$_REQUEST['saiu73idprograma'] = 0;
	$_REQUEST['saiu73idperiodo'] = 0;
	$_REQUEST['saiu73idpqrs'] = 0;
	$_REQUEST['saiu73detalle'] = '';
	$_REQUEST['saiu73fechafin'] = '';
	$_REQUEST['saiu73horafin'] = '';
	$_REQUEST['saiu73minutofin'] = '';
	$_REQUEST['saiu73paramercadeo'] = 0;
	$_REQUEST['saiu73idresponsable'] = $idTercero;
	$_REQUEST['saiu73idresponsable_td'] = $APP->tipo_doc;
	$_REQUEST['saiu73idresponsable_doc'] = '';
	$_REQUEST['saiu73tiemprespdias'] = '';
	$_REQUEST['saiu73tiempresphoras'] = '';
	$_REQUEST['saiu73tiemprespminutos'] = '';
	$_REQUEST['saiu73solucion'] = 0;
	$_REQUEST['saiu73idcaso'] = 0;
	$_REQUEST['saiu73respuesta'] = '';
	$_REQUEST['saiu73idorigen'] = 0;
	$_REQUEST['saiu73idarchivo'] = 0;
	$_REQUEST['saiu73idorigenrta'] = 0;
	$_REQUEST['saiu73idarchivorta'] = 0;
	$_REQUEST['saiu73fecharespcaso'] = '';
	$_REQUEST['saiu73horarespcaso'] = 0;
	$_REQUEST['saiu73minrespcaso'] = 0;
	$_REQUEST['saiu73idunidadcaso'] = 0;
	$_REQUEST['saiu73idequipocaso'] = 0;
	$_REQUEST['saiu73idsupervisorcaso'] = 0;
	$_REQUEST['saiu73idresponsablecaso'] = 0;
	$_REQUEST['saiu73numref'] = '';
	$_REQUEST['saiu73idresponsablecaso_td'] = $APP->tipo_doc;
	$_REQUEST['saiu73idresponsablecaso_doc'] = '';
	$_REQUEST['saiu73idtelefono'] = 0;
	$_REQUEST['saiu73numtelefono'] = '';
	$_REQUEST['saiu73numorigen'] = '';
	$_REQUEST['saiu73idchat'] = 0;
	$_REQUEST['saiu73numsesionchat'] = '';
	$_REQUEST['saiu73idcorreo'] = 0;
	$_REQUEST['saiu73idcorreootro'] = '';
	$_REQUEST['saiu73correoorigen'] = '';
	$_REQUEST['paso'] = 0;
}
if ($bLimpiaHijos) {
}
//AQUI SE DEBEN CARGAR TODOS LOS DATOS QUE LA FORMA NECESITE.
$bPuedeAbrir = false;
$bPuedeEliminar = false;
$bPuedeGuardar = true;
$bPuedeCerrar = false;
$bHayImprimir = false;
$bEnTramite = false;
$bResuelto = false;
if ($_REQUEST['saiu73estado'] == -1) {
	$bEnTramite = true;
}
if ($_REQUEST['saiu73estado'] == 2) {
	$bEnTramite = true;
}
//DATOS PARA COMPLETAR EL FORMULARIO
$iAgno = fecha_agno();
$sNombreUsuario = '';
if ($seg_1707 == 1) {
	$sSQL = 'SELECT unad11razonsocial FROM unad11terceros WHERE unad11id=' . $idTercero . '';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$sNombreUsuario = cadena_notildes($fila['unad11razonsocial']);
	}
}
$sTabla = 'saiu73solusuario_' . $iAgno;
if (!$objDB->bexistetabla($sTabla)) {
	list($sErrorT, $sDebugT) = f3000_TablasMes($iAgno, fecha_mes(), $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugT;
}
//Permisos adicionales
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
if ((int)$_REQUEST['paso'] == 0) {
	$bPuedeGuardar = true;
} else {
	switch ($_REQUEST['saiu73estado']) {
		case -1: // Borrador
			$bPuedeGuardar = true;
			if ($_REQUEST['saiu73id'] > 0) {
				$bPuedeCerrar = true;
				$_REQUEST['saiu73solucion']=3; // Se inicia caso
			}
			break;
		case 1: // Caso asignado
			break;
		case 7: // Radicada
			$bPuedeGuardar = false;
			$bResuelto = true;
			break;
		default:
			$bPuedeGuardar = false;
			break;
	}
}
//DATOS PARA COMPLETAR EL FORMULARIO
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
$iAgnoIni = 2024;
$iAgnoFin = fecha_agno();
list($saiu73estado_nombre, $sErrorDet) = tabla_campoxid('saiu11estadosol', 'saiu11nombre', 'saiu11id', $_REQUEST['saiu73estado'], '{' . $ETI['msg_sindato'] . '}', $objDB);
$html_saiu73estado = html_oculto('saiu73estado', $_REQUEST['saiu73estado'], $saiu73estado_nombre);
list($saiu73idsolicitante_rs, $_REQUEST['saiu73idsolicitante'], $_REQUEST['saiu73idsolicitante_td'], $_REQUEST['saiu73idsolicitante_doc']) = html_tercero($_REQUEST['saiu73idsolicitante_td'], $_REQUEST['saiu73idsolicitante_doc'], $_REQUEST['saiu73idsolicitante'], 0, $objDB);
list($saiu73idresponsable_rs, $_REQUEST['saiu73idresponsable'], $_REQUEST['saiu73idresponsable_td'], $_REQUEST['saiu73idresponsable_doc']) = html_tercero($_REQUEST['saiu73idresponsable_td'], $_REQUEST['saiu73idresponsable_doc'], $_REQUEST['saiu73idresponsable'], 0, $objDB);
if ($bEnTramite) {
	$objCombos->nuevo('saiu73tipointeresado', $_REQUEST['saiu73tipointeresado'], true, '{' . $ETI['msg_seleccione'] . '}');
	$sSQL = 'SELECT bita07id AS id, bita07nombre AS nombre FROM bita07tiposolicitante ORDER BY bita07orden, bita07nombre';
	$html_saiu73tipointeresado = $objCombos->html($sSQL, $objDB);
	$html_saiu73tiposolicitud = f3073_HTMLComboV2_saiu73tiposolicitud($objDB, $objCombos, $_REQUEST['saiu73tiposolicitud']);
	$html_saiu73temasolicitud = f3073_HTMLComboV2_saiu73temasolicitud($objDB, $objCombos, $_REQUEST['saiu73temasolicitud'], $_REQUEST['saiu73tiposolicitud']);
	$objCombos->nuevo('saiu73idzona', $_REQUEST['saiu73idzona'], true, '{' . $ETI['msg_seleccione'] . '}');
	$objCombos->sAccion = 'carga_combo_saiu73idcentro();';
	$sSQL = 'SELECT unad23id AS id, unad23nombre AS nombre FROM unad23zona WHERE unad23conestudiantes="S" ORDER BY unad23nombre';
	$html_saiu73idzona = $objCombos->html($sSQL, $objDB);
	$html_saiu73idcentro = f3073_HTMLComboV2_saiu73idcentro($objDB, $objCombos, $_REQUEST['saiu73idcentro'], $_REQUEST['saiu73idzona']);
	$objCombos->nuevo('saiu73codpais', $_REQUEST['saiu73codpais'], true, '{' . $ETI['msg_seleccione'] . '}');
	$objCombos->sAccion = 'carga_combo_saiu73coddepto();';
	$sSQL = 'SELECT unad18codigo AS id, unad18nombre AS nombre FROM unad18pais ORDER BY unad18nombre';
	$html_saiu73codpais = $objCombos->html($sSQL, $objDB);
	$html_saiu73coddepto = f3073_HTMLComboV2_saiu73coddepto($objDB, $objCombos, $_REQUEST['saiu73coddepto'], $_REQUEST['saiu73codpais']);
	$html_saiu73codciudad = f3073_HTMLComboV2_saiu73codciudad($objDB, $objCombos, $_REQUEST['saiu73codciudad'], $_REQUEST['saiu73coddepto']);
	$objCombos->nuevo('saiu73idescuela', $_REQUEST['saiu73idescuela'], true, '{' . $ETI['msg_seleccione'] . '}');
	$objCombos->sAccion = 'carga_combo_saiu73idprograma();';
	$objCombos->addItem('0', $ETI['msg_na']);
	$sSQL = 'SELECT core12id AS id, core12nombre AS nombre FROM core12escuela WHERE core12id>0 AND core12tieneestudiantes="S" ORDER BY core12tieneestudiantes DESC, core12nombre';
	$html_saiu73idescuela = $objCombos->html($sSQL, $objDB);
	$html_saiu73idprograma = f3073_HTMLComboV2_saiu73idprograma($objDB, $objCombos, $_REQUEST['saiu73idprograma'], $_REQUEST['saiu73idescuela']);
	$objCombos->nuevo('saiu73idperiodo', $_REQUEST['saiu73idperiodo'], true, '{' . $ETI['msg_seleccione'] . '}');
	$objCombos->addItem('0', $ETI['msg_na']);
	$sSQL = f146_ConsultaCombo('exte02id>0');
	$html_saiu73idperiodo = $objCombos->html($sSQL, $objDB);
	$objCombos->nuevo('saiu73paramercadeo', $_REQUEST['saiu73paramercadeo'], true, $ETI['no'], 0);
	$objCombos->addItem(1, $ETI['si']);
	//$objCombos->addArreglo($asaiu73paramercadeo, $isaiu73paramercadeo);
	$html_saiu73paramercadeo = $objCombos->html('', $objDB);
} else {
	list($saiu73tipointeresado_nombre, $sErrorDet) = tabla_campoxid('bita07tiposolicitante', 'bita07nombre', 'bita07id', $_REQUEST['saiu73tipointeresado'], '{' . $ETI['msg_sindato'] . '}', $objDB);
	$html_saiu73tipointeresado = html_oculto('saiu73tipointeresado', $_REQUEST['saiu73tipointeresado'], $saiu73tipointeresado_nombre);
	list($saiu73tiposolicitud_nombre, $sErrorDet) = tabla_campoxid('saiu02tiposol', 'saiu02titulo', 'saiu02id', $_REQUEST['saiu73tiposolicitud'], '{' . $ETI['msg_sindato'] . '}', $objDB);
	$html_saiu73tiposolicitud = html_oculto('saiu73tiposolicitud', $_REQUEST['saiu73tiposolicitud'], $saiu73tiposolicitud_nombre);
	list($saiu73temasolicitud_nombre, $sErrorDet) = tabla_campoxid('saiu03temasol', 'saiu03titulo', 'saiu03id', $_REQUEST['saiu73temasolicitud'], '{' . $ETI['msg_sindato'] . '}', $objDB);
	$html_saiu73temasolicitud = html_oculto('saiu73temasolicitud', $_REQUEST['saiu73temasolicitud'], $saiu73temasolicitud_nombre);
	list($saiu73idzona_nombre, $sErrorDet) = tabla_campoxid('unad23zona', 'unad23nombre', 'unad23id', $_REQUEST['saiu73idzona'], '{' . $ETI['msg_sindato'] . '}', $objDB);
	$html_saiu73idzona = html_oculto('saiu73idzona', $_REQUEST['saiu73idzona'], $saiu73idzona_nombre);
	list($saiu73idcentro_nombre, $sErrorDet) = tabla_campoxid('unad24sede', 'unad24nombre', 'unad24id', $_REQUEST['saiu73idcentro'], '{' . $ETI['msg_sindato'] . '}', $objDB);
	$html_saiu73idcentro = html_oculto('saiu73idcentro', $_REQUEST['saiu73idcentro'], $saiu73idcentro_nombre);
	list($saiu73codpais_nombre, $sErrorDet) = tabla_campoxid('unad18pais', 'unad18nombre', 'unad18codigo', $_REQUEST['saiu73codpais'], '{' . $ETI['msg_sindato'] . '}', $objDB);
	$html_saiu73codpais = html_oculto('saiu73codpais', $_REQUEST['saiu73codpais'], $saiu73codpais_nombre);
	list($saiu73coddepto_nombre, $sErrorDet) = tabla_campoxid('unad19depto', 'unad19nombre', 'unad19codigo', $_REQUEST['saiu73coddepto'], '{' . $ETI['msg_sindato'] . '}', $objDB);
	$html_saiu73coddepto = html_oculto('saiu73coddepto', $_REQUEST['saiu73coddepto'], $saiu73coddepto_nombre);
	list($saiu73codciudad_nombre, $sErrorDet) = tabla_campoxid('unad20ciudad', 'unad20nombre', 'unad20codigo', $_REQUEST['saiu73codciudad'], '{' . $ETI['msg_sindato'] . '}', $objDB);
	$html_saiu73codciudad = html_oculto('saiu73codciudad', $_REQUEST['saiu73codciudad'], $saiu73codciudad_nombre);
	list($saiu73idescuela_nombre, $sErrorDet) = tabla_campoxid('core12escuela', 'core12nombre', 'core12id', $_REQUEST['saiu73idescuela'], '{' . $ETI['msg_sindato'] . '}', $objDB);
	$html_saiu73idescuela = html_oculto('saiu73idescuela', $_REQUEST['saiu73idescuela'], $saiu73idescuela_nombre);
	list($saiu73idprograma_nombre, $sErrorDet) = tabla_campoxid('core09programa', 'core09nombre', 'core09id', $_REQUEST['saiu73idprograma'], '{' . $ETI['msg_sindato'] . '}', $objDB);
	$html_saiu73idprograma = html_oculto('saiu73idprograma', $_REQUEST['saiu73idprograma'], $saiu73idprograma_nombre);
	list($saiu73idperiodo_nombre, $sErrorDet) = tabla_campoxid('exte02per_aca', 'exte02nombre', 'exte02id', $_REQUEST['saiu73idperiodo'], '{' . $ETI['msg_sindato'] . '}', $objDB);
	$html_saiu73idperiodo = html_oculto('saiu73idperiodo', $_REQUEST['saiu73idperiodo'], $saiu73idperiodo_nombre);
}
$saiu73agno_nombre = $_REQUEST['saiu73agno'];
$html_saiu73agno = html_oculto('saiu73agno', $_REQUEST['saiu73agno'], $saiu73agno_nombre);
$saiu73mes_nombre = strtoupper(fecha_mes_nombre((int)$_REQUEST['saiu73mes']));
$html_saiu73mes = html_oculto('saiu73mes', $_REQUEST['saiu73mes'], $saiu73mes_nombre);
$saiu73dia_nombre = $_REQUEST['saiu73dia'];
$html_saiu73dia = html_oculto('saiu73dia', $_REQUEST['saiu73dia'], $saiu73dia_nombre);
list($saiu73tiporadicado_nombre, $sErrorDet) = tabla_campoxid('saiu16tiporadicado', 'saiu16nombre', 'saiu16id', $_REQUEST['saiu73tiporadicado'], '{' . $ETI['msg_sindato'] . '}', $objDB);
$html_saiu73tiporadicado = html_oculto('saiu73tiporadicado', $_REQUEST['saiu73tiporadicado'], $saiu73tiporadicado_nombre);
list($saiu73tiposolicitud_nombre, $sErrorDet) = tabla_campoxid('saiu02tiposol', 'saiu02titulo', 'saiu02id', $_REQUEST['saiu73tiposolicitud'], '{' . $ETI['msg_sindato'] . '}', $objDB);
$html_saiu73tiposolicitud = html_oculto('saiu73tiposolicitud', $_REQUEST['saiu73tiposolicitud'], $saiu73tiposolicitud_nombre);
//Alistar datos adicionales
$bPuedeAbrir = false;
if ($_REQUEST['paso'] != 0) {
	if ($bResuelto) {
		//Definir las condiciones que permitirán abrir el registro.
		list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 17, $idTercero, $objDB);
		if ($bDevuelve) {
			$bPuedeAbrir = true;
		}
	} else {
	}
}
$id_rpt = 0;
//$id_rpt=reportes_id(_Identificador_Tipo_Reporte_, $objDB);
$objCombos->nuevo('bagno', $_REQUEST['bagno'], false, '{' . $ETI['msg_todos'] . '}');
$objCombos->sAccion = 'paginarf3073()';
$objCombos->numeros($iAgnoIni, $iAgnoFin, 1);
$sSQL = '';
$html_bagno = $objCombos->html($sSQL, $objDB);
//$html_blistar = $objCombos->comboSistema(3073, 1, $objDB, 'paginarf3073()');
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
$iModeloReporte = 3073;
$html_iFormatoImprime = '<input id="iformatoimprime" name="iformatoimprime" type="hidden" value="0" />';
if ($_REQUEST['paso'] > 0) {
	$bDevuelve = false;
	//list($bDevuelve, $sDebugP)=seg_revisa_permisoV3($iCodModulo, 5, $idTercero, $objDB);
	if ($bDevuelve) {
		$seg_5 = 1;
	}
	if ($_REQUEST['saiu73estado'] > 6) {
		list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 8, $idTercero, $objDB);
		if ($bDevuelve) {
			$seg_8 = 1;
		}
	}
}
//Cargar las tablas de datos
$aParametros[0] = ''; //$_REQUEST['p1_3073'];
$aParametros[100] = $idTercero;
$aParametros[101] = $_REQUEST['paginaf3073'];
$aParametros[102] = $_REQUEST['lppf3073'];
$aParametros[104] = $_REQUEST['bagno'];
$aParametros[110] = $_REQUEST['bcampus'];
$aParametros[113] = $_REQUEST['saiu73idcanal'];
list($sTabla3073, $sDebugTabla) = f3073_TablaDetalleV2($aParametros, $objDB, $bDebug);
$sDebug = $sDebug . $sDebugTabla;
$bDebugMenu = false;
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
		require $APP->rutacomun . 'unad_forma2024.php';
		forma_InicioV4($xajax, $sTituloModulo);
		$aRutas = array(
			array('./sai.php', $sTituloApp),
			array('', $sTituloModulo)
		);
		$iNumBoton = 0;
		$aBotones[$iNumBoton] = array('muestraayuda(' . $APP->idsistema . ', ' . $iCodModulo . ')', $ETI['bt_ayuda'], 'iHelp');
		$iNumBoton++;
		$aBotones[$iNumBoton] = array('limpiapagina()', $ETI['bt_limpiar'], 'iDocument');
		$iNumBoton++;
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
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>css/css_tabs.css" type="text/css" />
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
					if (window.document.frmedita.saiu73estado.value < 7) {
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
		let saiu73idsolicitante = 0;
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
			if (idcampo == 'saiu73idsolicitante') {
				params[6] = 3073;
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
		let params = new Array();
		params[0] = vrcampo;
		params[1] = idcampo;
		if (params[0] != 0) {
			params[6] = 3073;
			xajax_unad11_TraerXidSAI(params);
		}
	}


	function asignarvariables() {
		window.document.frmimpp.v0.value = <?php echo $idTercero; ?>;
		window.document.frmimpp.v3.value = window.document.frmedita.bagno.value;
		window.document.frmimpp.v4.value = window.document.frmedita.bestado.value;
		window.document.frmimpp.v5.value = window.document.frmedita.blistar.value;
		window.document.frmimpp.separa.value = window.document.frmedita.csv_separa.value.trim();
	}





	function RevisaLlave() {
		let datos = new Array();
		datos[1] = window.document.frmedita.saiu73agno.value;
		datos[2] = window.document.frmedita.saiu73mes.value;
		datos[3] = window.document.frmedita.saiu73tiporadicado.value;
		datos[4] = window.document.frmedita.saiu73consec.value;
		if ((datos[1] != '') && (datos[2] != '') && (datos[3] != '') && (datos[4] != '')) {
			xajax_f3073_ExisteDato(datos);
		}
	}

	function cargadato(llave1, llave2, llave3, llave4) {
		window.document.frmedita.saiu73agno.value = String(llave1);
		window.document.frmedita.saiu73mes.value = String(llave2);
		window.document.frmedita.saiu73tiporadicado.value = String(llave3);
		window.document.frmedita.saiu73consec.value = String(llave4);
		window.document.frmedita.paso.value = 1;
		window.document.frmedita.submit();
	}

	function cargaridf3073(llave1, llave2) {
		window.document.frmedita.saiu73agno.value = String(llave1);
		window.document.frmedita.saiu73id.value = String(llave2);
		window.document.frmedita.paso.value = 3;
		window.document.frmedita.submit();
	}

	function carga_combo_saiu73tiposolicitud() {
		let params = new Array();
		params[0] = window.document.frmedita.saiu73temasolicitud.value;
		document.getElementById('div_saiu73tiposolicitud').innerHTML = '<b>Procesando datos, por favor espere...</b><input id="saiu73tiposolicitud" name="saiu73tiposolicitud" type="hidden" value="" />';
		document.getElementById('div_saiu73temasolicitud').innerHTML = '<b>Procesando datos, por favor espere...</b><input id="saiu73temasolicitud" name="saiu73temasolicitud" type="hidden" value="" />';
		xajax_f3073_Combosaiu73tiposolicitud(params);
	}

	function carga_combo_saiu73temasolicitud() {
		let params = new Array();
		params[0] = window.document.frmedita.saiu73tiposolicitud.value;
		document.getElementById('div_saiu73temasolicitud').innerHTML = '<b>Procesando datos, por favor espere...</b><input id="saiu73temasolicitud" name="saiu73temasolicitud" type="hidden" value="" />';
		xajax_f3073_Combosaiu73temasolicitud(params);
	}

	function carga_combo_saiu73idcentro() {
		let params = new Array();
		params[0] = window.document.frmedita.saiu73idzona.value;
		document.getElementById('div_saiu73idcentro').innerHTML = '<b>Procesando datos, por favor espere...</b><input id="saiu73idcentro" name="saiu73idcentro" type="hidden" value="" />';
		xajax_f3073_Combosaiu73idcentro(params);
	}

	function carga_combo_saiu73coddepto() {
		let params = new Array();
		params[0] = window.document.frmedita.saiu73codpais.value;
		document.getElementById('div_saiu73coddepto').innerHTML = '<b>Procesando datos, por favor espere...</b><input id="saiu73coddepto" name="saiu73coddepto" type="hidden" value="" />';
		xajax_f3073_Combosaiu73coddepto(params);
	}

	function carga_combo_saiu73codciudad() {
		let params = new Array();
		params[0] = window.document.frmedita.saiu73coddepto.value;
		document.getElementById('div_saiu73codciudad').innerHTML = '<b>Procesando datos, por favor espere...</b><input id="saiu73codciudad" name="saiu73codciudad" type="hidden" value="" />';
		xajax_f3073_Combosaiu73codciudad(params);
	}

	function carga_combo_saiu73idprograma() {
		let params = new Array();
		params[0] = window.document.frmedita.saiu73idescuela.value;
		document.getElementById('div_saiu73idprograma').innerHTML = '<b>Procesando datos, por favor espere...</b><input id="saiu73idprograma" name="saiu73idprograma" type="hidden" value="" />';
		xajax_f3073_Combosaiu73idprograma(params);
	}

	function paginarf3073() {
		let params = new Array();
		params[99] = window.document.frmedita.debug.value;
		params[100] = <?php echo $idTercero; ?>;
		params[101] = window.document.frmedita.paginaf3073.value;
		params[102] = window.document.frmedita.lppf3073.value;
		params[104] = window.document.frmedita.bagno.value;
		params[110]=window.document.frmedita.bcampus.value;
		params[113] = window.document.frmedita.saiu73idcanal.value;
		document.getElementById('div_f3073detalle').innerHTML = '<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf3073" name="paginaf3073" type="hidden" value="' + params[101] + '" /><input id="lppf3073" name="lppf3073" type="hidden" value="' + params[102] + '" />';
		xajax_f3073_HtmlTabla(params);
	}

	function enviacerrar() {
		ModalMensajeV2('<?php echo $ETI['msg_cerrar']; ?>', () => {
			expandesector(98);
			window.document.frmedita.paso.value = 16;
			window.document.frmedita.submit();
		}, {
			botonAceptar: 'Cerrar'
		});
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
		document.getElementById("saiu73agno").focus();
	}

	function buscarV2016(sCampo) {
		window.document.frmedita.iscroll.value = window.scrollY;
		expandesector(98);
		window.document.frmedita.scampobusca.value = sCampo;
		var params = new Array();
		params[1] = sCampo;
		//params[2]=window.document.frmedita.iagno.value;
		//params[3]=window.document.frmedita.itipo.value;
		xajax_f3073_Busquedas(params);
	}

	function Devuelve(sValor) {
		let sCampo = window.document.frmedita.scampobusca.value;
		if (sCampo == 'saiu73idsolicitante') {
			ter_traerxid('saiu73idsolicitante', sValor);
		}
		if (sCampo == 'saiu73idresponsable') {
			ter_traerxid('saiu73idresponsable', sValor);
		}
		retornacontrol();
	}

	function mantener_sesion() {
		xajax_sesion_mantenerV4();
	}
	setInterval('xajax_sesion_abandona_V2();', 60000);

	function AyudaLocal(sCampo) {
		let divAyuda = document.getElementById('div_ayuda_' + sCampo);
		if (typeof divAyuda === 'undefined') {} else {
			verboton('cmdAyuda_' + sCampo, 'none');
			var sMensaje = 'Lo que quiera decir.';
			//if (sCampo=='sNombreCampo'){sMensaje='Mensaje para otro campo.';}
			divAyuda.innerHTML = sMensaje;
			divAyuda.style.display = 'block';
		}
	}

	function cierraDiv96(ref) {
		let sRetorna = window.document.frmedita.div96v2.value;
		if (ref == '3073') {
			if (sRetorna != '') {
				window.document.frmedita.saiu73idorigen.value = window.document.frmedita.div96v1.value;
				window.document.frmedita.saiu73idarchivo.value = sRetorna;
				verboton('beliminasaiu73idarchivo', 'block');
			}
			archivo_lnk(window.document.frmedita.saiu73idorigen.value, window.document.frmedita.saiu73idarchivo.value, 'div_saiu73idarchivo');
		}
		if (ref == '3073rta') {
			if (sRetorna != '') {
				window.document.frmedita.saiu73idorigenrta.value = window.document.frmedita.div96v1.value;
				window.document.frmedita.saiu73idarchivorta.value = sRetorna;
				verboton('beliminasaiu73idarchivorta', 'block');
			}
			archivo_lnk(window.document.frmedita.saiu73idorigenrta.value, window.document.frmedita.saiu73idarchivorta.value, 'div_saiu73idarchivorta');
		}
		retornacontrol();
	}

	function mod_consec() {
		ModalConfirm('<?php echo $ETI['msg_confirmamodconsec']; ?>');
		ModalDialogConfirm(function(confirm) {
			if (confirm) {
				ejecuta_modconsec();
			}
		});
	}

	function ejecuta_modconsec() {
		MensajeAlarmaV2('<?php echo $ETI['msg_ejecutando']; ?>', 2);
		expandesector(98);
		window.document.frmedita.paso.value = 93;
		window.document.frmedita.submit();
	}

		function limpia_saiu73idarchivo() {
			window.document.frmedita.saiu73idorigen.value = 0;
			window.document.frmedita.saiu73idarchivo.value = 0;
			let da_Archivo = document.getElementById('div_saiu73idarchivo');
			da_Archivo.innerHTML = '&nbsp;';
			verboton('beliminasaiu73idarchivo', 'none');
			//paginarf0000();
		}

		function carga_saiu73idarchivo() {
			window.document.frmedita.iscroll.value = window.pageYOffset;
			window.document.frmedita.div96v1.value = '';
			window.document.frmedita.div96v2.value = '';
			window.document.frmedita.div96v3.value = '';
			let saiu73id = window.document.frmedita.saiu73id.value;
			let agno = window.document.frmedita.saiu73agno.value;
			document.getElementById('div_96titulo').innerHTML = '<h2>' + window.document.frmedita.titulo_3073.value + ' - Cargar archivo detalle</h2>';
			document.getElementById('div_96cuerpo').innerHTML = '<iframe id="iframe96" src="framearchivodis.php?ref=3073&id=' + saiu73id + '&tabla=_' + agno + '&tipo=0" height="400px" width="100%" frameborder="0"></iframe>';
			expandesector(96);
			window.scrollTo(0, 150);
		}

		function eliminasaiu73idarchivo() {
			let did = window.document.frmedita.saiu73id;
			let agno = window.document.frmedita.saiu73agno.value;
			if (confirm("Esta seguro de eliminar el archivo?")) {
				xajax_elimina_archivo_saiu73idarchivo(did.value, agno);
				//paginarf0000();
			}
		}

		function limpia_saiu73idarchivorta() {
			window.document.frmedita.saiu73idorigenrta.value = 0;
			window.document.frmedita.saiu73idarchivorta.value = 0;
			let da_Archivo = document.getElementById('div_saiu73idarchivorta');
			da_Archivo.innerHTML = '&nbsp;';
			verboton('beliminasaiu73idarchivorta', 'none');
			//paginarf0000();
		}

		function carga_saiu73idarchivorta() {
			window.document.frmedita.iscroll.value = window.pageYOffset;
			window.document.frmedita.div96v1.value = '';
			window.document.frmedita.div96v2.value = '';
			window.document.frmedita.div96v3.value = '';
			let saiu73id = window.document.frmedita.saiu73id.value;
			let agno = window.document.frmedita.saiu73agno.value;
			document.getElementById('div_96titulo').innerHTML = '<h2>' + window.document.frmedita.titulo_3073.value + ' - Cargar archivo respuesta</h2>';
			document.getElementById('div_96cuerpo').innerHTML = '<iframe id="iframe96" src="framearchivodis.php?ref=3073rta&id=' + saiu73id + '&tabla=_' + agno + '&tipo=0" height="400px" width="100%" frameborder="0"></iframe>';
			expandesector(96);
			window.scrollTo(0, 150);
		}

		function eliminasaiu73idarchivorta() {
			let did = window.document.frmedita.saiu73id;
			let agno = window.document.frmedita.saiu73agno.value;
			if (confirm("Esta seguro de eliminar el archivo?")) {
				xajax_elimina_archivo_saiu73idarchivorta(did.value, agno);
				//paginarf0000();
			}
		}

	function carga_combo_btema() {
		let params = new Array();
		params[0] = window.document.frmedita.bcategoria.value;
		document.getElementById('div_btema').innerHTML = '<b>Procesando datos, por favor espere...</b><input id="btema" name="btema" type="hidden" value="" />';
		xajax_f3073_Combobtema(params);
	}
	function volver() {
		window.document.frmvolver.action = 'sai.php';
		window.document.frmvolver.submit();
	}
</script>
<form id="frmimpp" name="frmimpp" method="post" action="p3073.php" target="_blank" style="display:none;">
<input id="r" name="r" type="hidden" value="3073" />
<input id="id3073" name="id3073" type="hidden" value="<?php echo $_REQUEST['saiu73id']; ?>" />
<input id="v0" name="v0" type="hidden" value="" />
<input id="v3" name="v3" type="hidden" value="" />
<input id="v4" name="v4" type="hidden" value="" />
<input id="v5" name="v5" type="hidden" value="" />
<input id="iformato94" name="iformato94" type="hidden" value="0" />
<input id="separa" name="separa" type="hidden" value="," />
<input id="rdebug" name="rdebug" type="hidden" value="<?php echo $_REQUEST['debug']; ?>"/>
<input id="clave" name="clave" type="hidden" value="" />
</form>
<form id="frmvolver" name="frmvolver" method="post" action="sai.php" autocomplete="off" style="display:none">
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
<input id="vdtipointeresado" name="vdtipointeresado" type="hidden" value="<?php echo $_REQUEST['vdtipointeresado']; ?>" />
<input id="vdidtelefono" name="vdidtelefono" type="hidden" value="<?php echo $_REQUEST['vdidtelefono']; ?>" />
<input id="bcampus" name="bcampus" type="hidden" value="<?php echo $_REQUEST['bcampus']; ?>" />
<input id="saiu73idcanal" name="saiu73idcanal" type="hidden" value="<?php echo $_REQUEST['saiu73idcanal']; ?>" />
<div id="div_sector1">
<?php
if ($bBloqueTitulo) {
?>
<div class="titulos">
<div class="titulosD">
<input id="cmdAyuda" name="cmdAyuda" type="button" class="btUpAyuda" onclick="muestraayuda(<?php echo $APP->idsistema . ', ' . $iCodModulo; ?>);" title="<?php echo $ETI['bt_ayuda']; ?>" value="<?php echo $ETI['bt_ayuda']; ?>" />
<input id="cmdLimpiar" name="cmdLimpiar" type="button" class="btUpLimpiar" onclick="limpiapagina();" title="<?php echo $ETI['bt_limpiar']; ?>" value="<?php echo $ETI['bt_limpiar']; ?>"/>
<input id="cmdVolverSec2" name="cmdVolverSec2" type="button" class="btSupVolver" onclick="volver();" title="<?php echo $ETI['bt_volver']; ?>" value="<?php echo $ETI['bt_volver']; ?>" />
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
echo $objForma->htmlExpande(3073, $_REQUEST['boculta3073'], $ETI['bt_mostrar'], $ETI['bt_ocultar']);
$sEstiloDiv = '';
if ($_REQUEST['boculta3073'] != 0) {
	$sEstiloDiv = ' style="display:none;"';
}
?>
</div>
<div id="div_p3073"<?php echo $sEstiloDiv; ?>>
<?php
}
//Mostrar formulario para editar
?>
<label class="Label130">
<?php
echo $ETI['msg_fecha'];
?>
</label>
<label class="Label220">
<?php
echo $html_saiu73dia.'/'.$html_saiu73mes.'/'.$html_saiu73agno;
?>
</label>
<label class="Label60">
<?php
echo $ETI['saiu73hora'];
?>
</label>
<div class="campo_HoraMin" id="div_saiu73hora">
<?php
$bOculto=True;
echo html_HoraMin('saiu73hora', $_REQUEST['saiu73hora'], 'saiu73minuto', $_REQUEST['saiu73minuto'], $bOculto);
?>
</div>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu73consec'];
?>
</label>
<label class="Label130">
<?php
echo html_oculto('saiu73consec', $_REQUEST['saiu73consec'], formato_numero($_REQUEST['saiu73consec']));
?>
</label>
<?php
/*
if ($seg_8==1){
	$objForma=new clsHtmlForma($iPiel);
	echo $objForma->htmlBotonSolo('cmdCambiaConsec', 'btMiniActualizar', 'expandesector(93);', $ETI['bt_cambiar'], 30);
	echo '<label class="Label30">&nbsp;</label>';
	}
*/
?>
<label class="Label60">
<?php
echo $ETI['saiu73id'];
?>
</label>
<label class="Label60">
<?php
	echo html_oculto('saiu73id', $_REQUEST['saiu73id'], formato_numero($_REQUEST['saiu73id']));
?>
</label>
<label class="Label130">
<?php
echo $ETI['saiu73estado'];
?>
</label>
<label>
<div id="div_saiu73estado">
<?php
echo $html_saiu73estado;
?>
</div>
<input id="saiu73estadoorigen" name="saiu73estadoorigen" type="hidden" value="<?php echo $_REQUEST['saiu73estadoorigen']; ?>" />
</label>
<div class="salto1px"></div>
<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo $ETI['saiu73idsolicitante'];
?>
</label>
<div class="salto1px"></div>
<input id="saiu73idsolicitante" name="saiu73idsolicitante" type="hidden" value="<?php echo $_REQUEST['saiu73idsolicitante']; ?>"/>
<div id="div_saiu73idsolicitante_llaves">
<?php
$bOculto=True;
echo html_DivTerceroV2('saiu73idsolicitante', $_REQUEST['saiu73idsolicitante_td'], $_REQUEST['saiu73idsolicitante_doc'], $bOculto, 0, $ETI['ing_doc']);
?>
</div>
<div class="salto1px"></div>
<div id="div_saiu73idsolicitante" class="L"><?php echo $saiu73idsolicitante_rs; ?></div>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['saiu73tipointeresado'];
?>
</label>
<label class="Label200">
<?php
echo $html_saiu73tipointeresado;
?>
</label>
<div class="salto1px"></div>
</div>
<div class="GrupoCampos520">
<label class="Label130">
<?php
echo $ETI['saiu73idzona'];
?>
</label>
<label>
<?php
echo $html_saiu73idzona;
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu73idcentro'];
?>
</label>
<label>
<div id="div_saiu73idcentro">
<?php
echo $html_saiu73idcentro;
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu73codpais'];
?>
</label>
<label>
<?php
echo $html_saiu73codpais;
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu73coddepto'];
?>
</label>
<label>
<div id="div_saiu73coddepto">
<?php
echo $html_saiu73coddepto;
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu73codciudad'];
?>
</label>
<label>
<div id="div_saiu73codciudad">
<?php
echo $html_saiu73codciudad;
?>
</div>
</label>
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
<input id="saiu73clasesolicitud" name="saiu73clasesolicitud" type="hidden" value="<?php echo $_REQUEST['saiu73clasesolicitud']; ?>"/>
<label class="Label130">
<?php
echo $ETI['saiu73tiposolicitud'];
?>
</label>
<label>
<div id="div_saiu73tiposolicitud">
<?php
echo $html_saiu73tiposolicitud;
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu73temasolicitud'];
?>
</label>
<label>
<div id="div_saiu73temasolicitud">
<?php
echo $html_saiu73temasolicitud;
?>
</div>
<input id="saiu73temasolicitudorigen" name="saiu73temasolicitudorigen" type="hidden" value="<?php echo $_REQUEST['saiu73temasolicitudorigen']; ?>"/>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu73idescuela'];
?>
</label>
<label>
<?php
echo $html_saiu73idescuela;
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu73idprograma'];
?>
</label>
<label>
<div id="div_saiu73idprograma">
<?php
echo $html_saiu73idprograma;
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu73idperiodo'];
?>
</label>
<label>
<?php
echo $html_saiu73idperiodo;
?>
</label>
<div class="salto1px"></div>
<div class="GrupoCampos520">
<label class="txtAreaS">
<?php
echo $ETI['saiu73detalle'];
?>
<?php
$sInactivo='';
if (!$bEnTramite){
	$sInactivo='readonly disabled';
}
?>
<textarea id="saiu73detalle" name="saiu73detalle" placeholder="<?php echo $ETI['ing_campo'].$ETI['saiu73detalle']; ?>" <?php echo $sInactivo; ?>><?php echo $_REQUEST['saiu73detalle']; ?></textarea>
</label>
</div>
<div class="GrupoCampos300">
<div class="salto1px"></div>
<input id="saiu73idorigen" name="saiu73idorigen" type="hidden" value="<?php echo $_REQUEST['saiu73idorigen']; ?>" />
<input id="saiu73idarchivo" name="saiu73idarchivo" type="hidden" value="<?php echo $_REQUEST['saiu73idarchivo']; ?>" />
<div id="div_saiu73idarchivo" class="Campo220">
<?php
echo html_lnkarchivo((int)$_REQUEST['saiu73idorigen'], (int)$_REQUEST['saiu73idarchivo']);
?>
</div>
<?php
if ($bPuedeCerrar) {
	$sEstiloAnexa = 'style="display:none;"';
	$sEstiloElimina = 'style="display:none;"';
	if ((int)$_REQUEST['saiu73id'] != 0) {
		$sEstiloAnexa = 'style="display:block;"';
	}
	if ((int)$_REQUEST['saiu73idarchivo'] != 0) {
		$sEstiloElimina = 'style="display:block;"';
	}
	echo $objForma->htmlBotonSolo('banexasaiu73idarchivo', 'btAnexarS', 'carga_saiu73idarchivo()', 'Cargar archivo', 30, $sEstiloAnexa, 'Anexar');
	echo $objForma->htmlBotonSolo('beliminasaiu73idarchivo', 'btBorrarS', 'eliminasaiu73idarchivo()', 'Eliminar archivo', 30, $sEstiloElimina, 'Eliminar');
}
?>
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
<?php
if ($bResuelto) {
?>
<div id="div_saiu73respuesta" style="display:block;">
<div class="GrupoCampos520">
<label class="txtAreaS">
<?php
echo $ETI['saiu73respuesta'];
?>
<label class="Label220 ir_derecha">
<?php
$saiu73fecharespcaso = fecha_desdenumero($_REQUEST['saiu73fecharespcaso']);
$saiu73horarespcaso = html_HoraMin('saiu73horarespcaso', $_REQUEST['saiu73horarespcaso'], 'saiu73minrespcaso', $_REQUEST['saiu73minrespcaso'], true);
echo $saiu73fecharespcaso . ' ' . $saiu73horarespcaso;
?>
</label>
<?php
$sInactivo = '';
if (!$bPuedeCerrar) {
	$sInactivo = 'readonly disabled';
}
?>
<textarea id="saiu73respuesta" name="saiu73respuesta" placeholder="<?php echo $ETI['ing_campo'].$ETI['saiu73respuesta']; ?>" <?php echo $sInactivo; ?>><?php echo $_REQUEST['saiu73respuesta']; ?></textarea>
</label>
</div>
<div class="GrupoCampos300">
<div class="salto1px"></div>
<input id="saiu73idorigenrta" name="saiu73idorigenrta" type="hidden" value="<?php echo $_REQUEST['saiu73idorigenrta']; ?>" />
<input id="saiu73idarchivorta" name="saiu73idarchivorta" type="hidden" value="<?php echo $_REQUEST['saiu73idarchivorta']; ?>" />
<div id="div_saiu73idarchivorta" class="Campo220">
<?php
echo html_lnkarchivo((int)$_REQUEST['saiu73idorigenrta'], (int)$_REQUEST['saiu73idarchivorta']);
?>
</div>
<div class="salto1px"></div>
</div>
</div>
<?php
}
?>
<?php
if ($bEnTramite) {
?>
<div class="salto5px"></div>
<div class="GrupoCamposAyuda">
<?php
echo $ETI['msg_alerta_solicitar'];
?>
</div>
<div class="salto5px"></div>
<?php
	echo $objForma->htmlBotonSolo('cmdGuardar', 'BotonAzul160', 'enviaguardar()', $ETI['saiu73guardar'], 130);
}
?>
<?php
if ($bPuedeCerrar){
?>
<label class="Label60"></label>
<?php
	echo $objForma->htmlBotonSolo('cmdTermina', 'BotonAzul160', 'enviacerrar()', $ETI['saiu73cerrar'], 130);
}
?>
<input id="saiu73idresponsable" name="saiu73idresponsable" type="hidden" value="<?php echo $_REQUEST['saiu73idresponsable']; ?>"/>
<input id="saiu73solucion" name="saiu73solucion" type="hidden" value="<?php echo $_REQUEST['saiu73solucion']; ?>"/>
<input id="saiu73fechafin" name="saiu73fechafin" type="hidden" value="<?php echo $_REQUEST['saiu73fechafin']; ?>"/>
<input id="saiu73horafin" name="saiu73horafin" type="hidden" value="<?php echo $_REQUEST['saiu73horafin']; ?>"/>
<input id="saiu73minutofin" name="saiu73minutofin" type="hidden" value="<?php echo $_REQUEST['saiu73minutofin']; ?>"/>
<input id="saiu73tiemprespdias" name="saiu73tiemprespdias" type="hidden" value="<?php echo $_REQUEST['saiu73tiemprespdias']; ?>"/>
<input id="saiu73tiempresphoras" name="saiu73tiempresphoras" type="hidden" value="<?php echo $_REQUEST['saiu73tiempresphoras']; ?>"/>
<input id="saiu73tiemprespminutos" name="saiu73tiemprespminutos" type="hidden" value="<?php echo $_REQUEST['saiu73tiemprespminutos']; ?>"/>
<input id="saiu73idsupervisorcaso" name="saiu73idsupervisorcaso" type="hidden" value="<?php echo $_REQUEST['saiu73idsupervisorcaso']; ?>" />
<input id="saiu73idresponsablecaso" name="saiu73idresponsablecaso" type="hidden" value="<?php echo $_REQUEST['saiu73idresponsablecaso']; ?>" />
<div class="salto1px"></div>
<?php
if (false) {
	//Ejemplo de boton de ayuda
	//echo html_BotonAyuda('NombreCampo');
	//echo html_DivAyudaLocal('NombreCampo');
}
if ($bConExpande) {
	//Este es el cierre del div_p3073
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
echo '<h3>'.$ETI['bloque1'].'</h3>';
?>
</div>
<div class="areatrabajo">
<div class="ir_derecha">
<label class="Label60">
<?php
echo $ETI['saiu73agno'];
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
echo ' '.$csv_separa;
?>
<div id="div_f3073detalle">
<?php
echo $sTabla3073;
?>
</div>
</div><!-- /div_areatrabajo -->
</div><!-- /DIV_areaform -->
</div>


<div id="div_sector2" style="display:none">
<?php
// if ($bBloqueTitulo) {
?>
<div class="titulos">
<div class="titulosD">
<input id="cmdAyuda2" name="cmdAyuda2" type="button" class="btSupAyuda" onclick="muestraayuda(<?php echo $iCodModulo; ?>);" title="<?php echo $ETI['bt_ayuda']; ?>" value="<?php echo $ETI['bt_ayuda']; ?>" />
<input id="cmdVolverSec2" name="cmdVolverSec2" type="button" class="btSupVolver" onclick="expandesector(1);" title="<?php echo $ETI['bt_volver']; ?>" value="<?php echo $ETI['bt_volver']; ?>" />
</div>
<div class="titulosI">
</div>
</div>
<?php
// }
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
$objForma=new clsHtmlForma($iPiel);
echo $objForma->htmlTitulo(''.$ETI['titulo_sector93'].'', $iCodModulo);
echo $objForma->htmlInicioMarco();
?>
<label class="Label160">
<?php
echo $ETI['msg_saiu73consec'];
?>
</label>
<label class="Label90">
<?php
echo '<b>'.$_REQUEST['saiu73consec'].'</b>';
?>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['msg_saiu73consec_nuevo'];
// onchange="RevisaConsec()"
?>
</label>
<label class="Label90">
<input id="saiu73consec_nuevo" name="saiu73consec_nuevo" type="text" value="<?php echo $_REQUEST['saiu73consec_nuevo']; ?>" class="cuatro"/>
</label>
<div class="salto1px"></div>
<label class="Label160">&nbsp;</label>
<?php
echo $objForma->htmlBotonSolo('cmdCambiaConsecFinal', 'botonProceso', 'mod_consec();', $ETI['bt_cambiar'], 130);
echo $objForma->htmlFinMarco();
?>
</div><!-- /DIV_Sector93 -->
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
<input id="titulo_3073" name="titulo_3073" type="hidden" value="<?php echo $sTituloModulo; ?>" />
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
</div><!-- /DIV_Sector97 -->

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
	if ($bPuedeGuardar) {
		if ($_REQUEST['saiu73estado']<7) {
?>
<div class="flotante">
<input id="cmdGuardarf" name="cmdGuardarf" type="button" class="btSoloGuardar" onClick="enviaguardar();" value="<?php echo $ETI['bt_guardar']; ?>" />
</div>
<?php
		}
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
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>js/chosen.css" type="text/css"/>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>js/chosen.jquery.js"></script>
<script language="javascript">
$().ready(function(){
<?php
if ($bEnTramite) {
?>
$("#saiu73idcentro").chosen({width:"100%"});
$("#saiu73coddepto").chosen({width:"100%"});
$("#saiu73codciudad").chosen({width:"100%"});
$("#saiu73temasolicitud").chosen({width:"100%"});
$("#saiu73idprograma").chosen({width:"100%"});
$("#saiu73idperiodo").chosen({width:"100%"});
ter_muestra('saiu73idsolicitante',0);
<?php
}
?>
$("#bcategoria").chosen({width:"100%"});
$("#btema").chosen({width:"100%"});
});
</script>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>unad_todas2024v2.js"></script>
<?php
forma_piedepagina();

