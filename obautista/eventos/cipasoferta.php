<?php
/*
--- © Cristhiam Dario Silva Chavez - UNAD - 2022 ---
--- cristhiam.silva@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.28.4b viernes, 21 de octubre de 2022
--- Modelo Versión 2.29.4 sábado, 27 de mayo de 2023
*/
/** Archivo cipasoferta.php.
 * Modulo 3801 cipa01oferta.
 * @author Cristhiam Dario Silva Chavez - cristhiam.silva@unad.edu.co
 * @param debug = 1  (Opcional), bandera para indicar si se generan datos de depuración
 * @date viernes, 21 de octubre de 2022
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
require $APP->rutacomun . 'libnombres.php';
require $APP->rutacomun . 'lib19.php';
if (($bPeticionXAJAX) && ($_SESSION['unad_id_tercero'] == 0)) {
	// viene por xajax.
	$xajax = new xajax();
	$xajax->configure('javascript URI', $APP->rutacomun . 'xajax/');
	$xajax->register(XAJAX_FUNCTION, 'sesion_abandona_V2');
	$xajax->processRequest();
	die();
}
$grupo_id = 1; //Necesita ajustarlo...
$iCodModulo = 3801;
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
$mensajes_3800 = 'lg/lg_3800_' . $_SESSION['unad_idioma'] . '.php';
if (!file_exists($mensajes_3800)) {
	$mensajes_3800 = 'lg/lg_3800_es.php';
}
require $mensajes_3800;
*/
$mensajes_3801 = 'lg/lg_3801_' . $_SESSION['unad_idioma'] . '.php';
if (!file_exists($mensajes_3801)) {
	$mensajes_3801 = 'lg/lg_3801_es.php';
}
require $mensajes_todas;
require $mensajes_3801;
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
	forma_cabeceraV3($xajax, $ETI['titulo_3801']);
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
		header('Location:noticia.php?ret=cipasoferta.php');
		die();
	}
}
$idTercero = $_SESSION['unad_id_tercero'];
$bOtroUsuario = false;
$seg_1707 = 0;
$bDevuelve = false;
list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 1707, $_SESSION['unad_id_tercero'], $objDB, $bDebug);
$sDebug = $sDebug . $sDebugP;
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
$mensajes_3802 = 'lg/lg_3802_' . $_SESSION['unad_idioma'] . '.php';
if (!file_exists($mensajes_3802)) {
	$mensajes_3802 = 'lg/lg_3802_es.php';
}
$mensajes_3803 = 'lg/lg_3803_' . $_SESSION['unad_idioma'] . '.php';
if (!file_exists($mensajes_3803)) {
	$mensajes_3803 = 'lg/lg_3803_es.php';
}
require $mensajes_3802;
require $mensajes_3803;
// -- Si esta cargando la pagina por primer vez se revisa si requiere auditar y se manda a hacer un limpiar (paso -1)
if (isset($_REQUEST['paso']) == 0) {
	$_REQUEST['paso'] = -1;
	if ($audita[1]) {
		seg_auditaingreso($iCodModulo, $_SESSION['unad_id_tercero'], $objDB);
	}
}
// -- 3801 cipa01oferta
require 'lib3801.php';
// -- 3802 Jornadas
require 'lib3802.php';
// -- 3803 Cupos
require 'lib3803.php';
$xajax = new xajax();
$xajax->configure('javascript URI', $APP->rutacomun . 'xajax/');
$xajax->register(XAJAX_FUNCTION, 'unad11_Mostrar_v2');
$xajax->register(XAJAX_FUNCTION, 'unad11_TraerXid');
$xajax->register(XAJAX_FUNCTION, 'formatear_moneda');
$xajax->register(XAJAX_FUNCTION, 'f3801_Combocipa01programa');
$xajax->register(XAJAX_FUNCTION, 'f3801_Combocipa01centro');
$xajax->register(XAJAX_FUNCTION, 'f3801_Combocipa01idcurso');
$xajax->register(XAJAX_FUNCTION, 'elimina_archivo_cipa01idarchivoeviden');
$xajax->register(XAJAX_FUNCTION, 'sesion_abandona_V2');
$xajax->register(XAJAX_FUNCTION, 'sesion_mantenerV4');
$xajax->register(XAJAX_FUNCTION, 'f3801_HtmlTabla');
$xajax->register(XAJAX_FUNCTION, 'f3801_ExisteDato');
$xajax->register(XAJAX_FUNCTION, 'f3801_Busquedas');
$xajax->register(XAJAX_FUNCTION, 'f3801_HtmlBusqueda');
$xajax->register(XAJAX_FUNCTION, 'f3802_Guardar');
$xajax->register(XAJAX_FUNCTION, 'f3802_Traer');
$xajax->register(XAJAX_FUNCTION, 'f3802_Eliminar');
$xajax->register(XAJAX_FUNCTION, 'f3802_HtmlTabla');
$xajax->register(XAJAX_FUNCTION, 'f3802_PintarLlaves');
$xajax->register(XAJAX_FUNCTION, 'f3803_Guardar');
$xajax->register(XAJAX_FUNCTION, 'f3803_Traer');
$xajax->register(XAJAX_FUNCTION, 'f3803_Eliminar');
$xajax->register(XAJAX_FUNCTION, 'f3803_HtmlTabla');
$xajax->register(XAJAX_FUNCTION, 'f3803_PintarLlaves');
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
if (isset($_REQUEST['paginaf3801']) == 0) {
	$_REQUEST['paginaf3801'] = 1;
}
if (isset($_REQUEST['lppf3801']) == 0) {
	$_REQUEST['lppf3801'] = 20;
}
if (isset($_REQUEST['boculta3801']) == 0) {
	$_REQUEST['boculta3801'] = 0;
}
if (isset($_REQUEST['paginaf3802']) == 0) {
	$_REQUEST['paginaf3802'] = 1;
}
if (isset($_REQUEST['lppf3802']) == 0) {
	$_REQUEST['lppf3802'] = 20;
}
if (isset($_REQUEST['boculta3802']) == 0) {
	$_REQUEST['boculta3802'] = 0;
}
if (isset($_REQUEST['paginaf3803']) == 0) {
	$_REQUEST['paginaf3803'] = 1;
}
if (isset($_REQUEST['lppf3803']) == 0) {
	$_REQUEST['lppf3803'] = 20;
}
if (isset($_REQUEST['boculta3803']) == 0) {
	$_REQUEST['boculta3803'] = 0;
}
// -- Inicializar variables de datos.
if (isset($_REQUEST['cipa01periodo']) == 0) {
	$_REQUEST['cipa01periodo'] = '';
}
if (isset($_REQUEST['cipa01consec']) == 0) {
	$_REQUEST['cipa01consec'] = '';
}
if (isset($_REQUEST['cipa01consec_nuevo']) == 0) {
	$_REQUEST['cipa01consec_nuevo'] = '';
}
if (isset($_REQUEST['cipa01id']) == 0) {
	$_REQUEST['cipa01id'] = '';
}
if (isset($_REQUEST['cipa01nombre']) == 0) {
	$_REQUEST['cipa01nombre'] = '';
}
if (isset($_REQUEST['cipa01alcance']) == 0) {
	$_REQUEST['cipa01alcance'] = '';
}
if (isset($_REQUEST['cipa01clase']) == 0) {
	$_REQUEST['cipa01clase'] = '';
}
if (isset($_REQUEST['cipa01estado']) == 0) {
	$_REQUEST['cipa01estado'] = 0;
}
if (isset($_REQUEST['cipa01escuela']) == 0) {
	$_REQUEST['cipa01escuela'] = '';
}
if (isset($_REQUEST['cipa01programa']) == 0) {
	$_REQUEST['cipa01programa'] = '';
}
if (isset($_REQUEST['cipa01zona']) == 0) {
	$_REQUEST['cipa01zona'] = '';
}
if (isset($_REQUEST['cipa01centro']) == 0) {
	$_REQUEST['cipa01centro'] = '';
}
if (isset($_REQUEST['cipa01idcurso']) == 0) {
	$_REQUEST['cipa01idcurso'] = '';
}
if (isset($_REQUEST['cipa01tematica']) == 0) {
	$_REQUEST['cipa01tematica'] = '';
}
if (isset($_REQUEST['cipa01est_proyectados']) == 0) {
	$_REQUEST['cipa01est_proyectados'] = '';
}
if (isset($_REQUEST['cipa01est_asistentes']) == 0) {
	$_REQUEST['cipa01est_asistentes'] = 0;
}
if (isset($_REQUEST['cipa01iddocente']) == 0) {
	$_REQUEST['cipa01iddocente'] = 0;
	//$_REQUEST['cipa01iddocente'] = $idTercero;
}
if (isset($_REQUEST['cipa01iddocente_td']) == 0) {
	$_REQUEST['cipa01iddocente_td'] = $APP->tipo_doc;
}
if (isset($_REQUEST['cipa01iddocente_doc']) == 0) {
	$_REQUEST['cipa01iddocente_doc'] = '';
}
if (isset($_REQUEST['cipa01iddocente2']) == 0) {
	$_REQUEST['cipa01iddocente2'] = 0;
	//$_REQUEST['cipa01iddocente2'] = $idTercero;
}
if (isset($_REQUEST['cipa01iddocente2_td']) == 0) {
	$_REQUEST['cipa01iddocente2_td'] = $APP->tipo_doc;
}
if (isset($_REQUEST['cipa01iddocente2_doc']) == 0) {
	$_REQUEST['cipa01iddocente2_doc'] = '';
}
if (isset($_REQUEST['cipa01iddocente3']) == 0) {
	$_REQUEST['cipa01iddocente3'] = 0;
	//$_REQUEST['cipa01iddocente3'] = $idTercero;
}
if (isset($_REQUEST['cipa01iddocente3_td']) == 0) {
	$_REQUEST['cipa01iddocente3_td'] = $APP->tipo_doc;
}
if (isset($_REQUEST['cipa01iddocente3_doc']) == 0) {
	$_REQUEST['cipa01iddocente3_doc'] = '';
}
if (isset($_REQUEST['cipa01idmonitor']) == 0) {
	$_REQUEST['cipa01idmonitor'] = 0;
	//$_REQUEST['cipa01idmonitor'] = $idTercero;
}
if (isset($_REQUEST['cipa01idmonitor_td']) == 0) {
	$_REQUEST['cipa01idmonitor_td'] = $APP->tipo_doc;
}
if (isset($_REQUEST['cipa01idmonitor_doc']) == 0) {
	$_REQUEST['cipa01idmonitor_doc'] = '';
}
if (isset($_REQUEST['cipa01idsupervisor']) == 0) {
	$_REQUEST['cipa01idsupervisor'] = 0;
	//$_REQUEST['cipa01idsupervisor'] = $idTercero;
}
if (isset($_REQUEST['cipa01idsupervisor_td']) == 0) {
	$_REQUEST['cipa01idsupervisor_td'] = $APP->tipo_doc;
}
if (isset($_REQUEST['cipa01idsupervisor_doc']) == 0) {
	$_REQUEST['cipa01idsupervisor_doc'] = '';
}
if (isset($_REQUEST['cipa01num_valoraciones']) == 0) {
	$_REQUEST['cipa01num_valoraciones'] = 0;
}
if (isset($_REQUEST['cipa01valoracion']) == 0) {
	$_REQUEST['cipa01valoracion'] = 0;
}
if (isset($_REQUEST['cipa01fechatermina']) == 0) {
	$_REQUEST['cipa01fechatermina'] = 0;
	//$_REQUEST['cipa01fechatermina'] = $iHoy;
}
if (isset($_REQUEST['cipa01proximafecha']) == 0) {
	$_REQUEST['cipa01proximafecha'] = 0;
	//$_REQUEST['cipa01proximafecha'] = $iHoy;
}
if (isset($_REQUEST['cipa01horatermina']) == 0) {
	$_REQUEST['cipa01horatermina'] = fecha_hora();
}
if (isset($_REQUEST['cipa01mintermina']) == 0) {
	$_REQUEST['cipa01mintermina'] = fecha_minuto();
}
if (isset($_REQUEST['cipa01idorigeneviden']) == 0) {
	$_REQUEST['cipa01idorigeneviden'] = 0;
}
if (isset($_REQUEST['cipa01idarchivoeviden']) == 0) {
	$_REQUEST['cipa01idarchivoeviden'] = 0;
}
if (isset($_REQUEST['cipa01convocados']) == 0) {
	$_REQUEST['cipa01convocados'] = 0;
}
if (isset($_REQUEST['cipa01noasiste']) == 0) {
	$_REQUEST['cipa01noasiste'] = 0;
}
$_REQUEST['cipa01periodo'] = numeros_validar($_REQUEST['cipa01periodo']);
$_REQUEST['cipa01consec'] = numeros_validar($_REQUEST['cipa01consec']);
$_REQUEST['cipa01id'] = numeros_validar($_REQUEST['cipa01id']);
$_REQUEST['cipa01nombre'] = cadena_Validar($_REQUEST['cipa01nombre']);
$_REQUEST['cipa01alcance'] = numeros_validar($_REQUEST['cipa01alcance']);
$_REQUEST['cipa01clase'] = numeros_validar($_REQUEST['cipa01clase']);
$_REQUEST['cipa01estado'] = numeros_validar($_REQUEST['cipa01estado']);
$_REQUEST['cipa01escuela'] = numeros_validar($_REQUEST['cipa01escuela']);
$_REQUEST['cipa01programa'] = numeros_validar($_REQUEST['cipa01programa']);
$_REQUEST['cipa01zona'] = numeros_validar($_REQUEST['cipa01zona']);
$_REQUEST['cipa01centro'] = numeros_validar($_REQUEST['cipa01centro']);
$_REQUEST['cipa01idcurso'] = numeros_validar($_REQUEST['cipa01idcurso']);
$_REQUEST['cipa01tematica'] = cadena_Validar($_REQUEST['cipa01tematica']);
$_REQUEST['cipa01est_proyectados'] = numeros_validar($_REQUEST['cipa01est_proyectados']);
$_REQUEST['cipa01est_asistentes'] = numeros_validar($_REQUEST['cipa01est_asistentes']);
$_REQUEST['cipa01iddocente'] = numeros_validar($_REQUEST['cipa01iddocente']);
$_REQUEST['cipa01iddocente_td'] = cadena_Validar($_REQUEST['cipa01iddocente_td']);
$_REQUEST['cipa01iddocente_doc'] = cadena_Validar($_REQUEST['cipa01iddocente_doc']);
$_REQUEST['cipa01iddocente2'] = numeros_validar($_REQUEST['cipa01iddocente2']);
$_REQUEST['cipa01iddocente2_td'] = cadena_Validar($_REQUEST['cipa01iddocente2_td']);
$_REQUEST['cipa01iddocente2_doc'] = cadena_Validar($_REQUEST['cipa01iddocente2_doc']);
$_REQUEST['cipa01iddocente3'] = numeros_validar($_REQUEST['cipa01iddocente3']);
$_REQUEST['cipa01iddocente3_td'] = cadena_Validar($_REQUEST['cipa01iddocente3_td']);
$_REQUEST['cipa01iddocente3_doc'] = cadena_Validar($_REQUEST['cipa01iddocente3_doc']);
$_REQUEST['cipa01idmonitor'] = numeros_validar($_REQUEST['cipa01idmonitor']);
$_REQUEST['cipa01idmonitor_td'] = cadena_Validar($_REQUEST['cipa01idmonitor_td']);
$_REQUEST['cipa01idmonitor_doc'] = cadena_Validar($_REQUEST['cipa01idmonitor_doc']);
$_REQUEST['cipa01idsupervisor'] = numeros_validar($_REQUEST['cipa01idsupervisor']);
$_REQUEST['cipa01idsupervisor_td'] = cadena_Validar($_REQUEST['cipa01idsupervisor_td']);
$_REQUEST['cipa01idsupervisor_doc'] = cadena_Validar($_REQUEST['cipa01idsupervisor_doc']);
$_REQUEST['cipa01num_valoraciones'] = numeros_validar($_REQUEST['cipa01num_valoraciones']);
$_REQUEST['cipa01valoracion'] = numeros_validar($_REQUEST['cipa01valoracion'], true, 2);
$_REQUEST['cipa01fechatermina'] = numeros_validar($_REQUEST['cipa01fechatermina']);
$_REQUEST['cipa01proximafecha'] = numeros_validar($_REQUEST['cipa01proximafecha']);
$_REQUEST['cipa01horatermina'] = numeros_validar($_REQUEST['cipa01horatermina']);
$_REQUEST['cipa01mintermina'] = numeros_validar($_REQUEST['cipa01mintermina']);
$_REQUEST['cipa01idorigeneviden'] = numeros_validar($_REQUEST['cipa01idorigeneviden']);
$_REQUEST['cipa01idarchivoeviden'] = numeros_validar($_REQUEST['cipa01idarchivoeviden']);
$_REQUEST['cipa01convocados'] = numeros_validar($_REQUEST['cipa01convocados']);
$_REQUEST['cipa01noasiste'] = numeros_validar($_REQUEST['cipa01noasiste']);
if ((int)$_REQUEST['paso'] > 0) {
	//Jornadas
	if (isset($_REQUEST['cipa02idoferta']) == 0) {
		$_REQUEST['cipa02idoferta'] = '';
	}
	if (isset($_REQUEST['cipa02consec']) == 0) {
		$_REQUEST['cipa02consec'] = '';
	}
	if (isset($_REQUEST['cipa02id']) == 0) {
		$_REQUEST['cipa02id'] = '';
	}
	if (isset($_REQUEST['cipa02forma']) == 0) {
		$_REQUEST['cipa02forma'] = '';
	}
	if (isset($_REQUEST['cipa02lugar']) == 0) {
		$_REQUEST['cipa02lugar'] = '';
	}
	if (isset($_REQUEST['cipa02link']) == 0) {
		$_REQUEST['cipa02link'] = '';
	}
	if (isset($_REQUEST['cipa02fecha']) == 0) {
		$_REQUEST['cipa02fecha'] = '';
		//$_REQUEST['cipa02fecha'] = $iHoy;
	}
	if (isset($_REQUEST['cipa02horaini']) == 0) {
		$_REQUEST['cipa02horaini'] = '';
	}
	if (isset($_REQUEST['cipa02minini']) == 0) {
		$_REQUEST['cipa02minini'] = '';
	}
	if (isset($_REQUEST['cipa02horafin']) == 0) {
		$_REQUEST['cipa02horafin'] = '';
	}
	if (isset($_REQUEST['cipa02minfin']) == 0) {
		$_REQUEST['cipa02minfin'] = '';
	}
	if (isset($_REQUEST['cipa02numinscritos']) == 0) {
		$_REQUEST['cipa02numinscritos'] = '';
	}
	if (isset($_REQUEST['cipa02numparticipantes']) == 0) {
		$_REQUEST['cipa02numparticipantes'] = '';
	}
	if (isset($_REQUEST['cipa02tematica']) == 0) {
		$_REQUEST['cipa02tematica'] = '';
	}
	$_REQUEST['cipa02idoferta'] = numeros_validar($_REQUEST['cipa02idoferta']);
	$_REQUEST['cipa02consec'] = numeros_validar($_REQUEST['cipa02consec']);
	$_REQUEST['cipa02id'] = numeros_validar($_REQUEST['cipa02id']);
	$_REQUEST['cipa02forma'] = numeros_validar($_REQUEST['cipa02forma']);
	$_REQUEST['cipa02lugar'] = cadena_Validar($_REQUEST['cipa02lugar']);
	$_REQUEST['cipa02link'] = cadena_Validar($_REQUEST['cipa02link']);
	$_REQUEST['cipa02fecha'] = numeros_validar($_REQUEST['cipa02fecha']);
	$_REQUEST['cipa02horaini'] = numeros_validar($_REQUEST['cipa02horaini']);
	$_REQUEST['cipa02minini'] = numeros_validar($_REQUEST['cipa02minini']);
	$_REQUEST['cipa02horafin'] = numeros_validar($_REQUEST['cipa02horafin']);
	$_REQUEST['cipa02minfin'] = numeros_validar($_REQUEST['cipa02minfin']);
	$_REQUEST['cipa02numinscritos'] = numeros_validar($_REQUEST['cipa02numinscritos']);
	$_REQUEST['cipa02numparticipantes'] = numeros_validar($_REQUEST['cipa02numparticipantes']);
	$_REQUEST['cipa02tematica'] = cadena_Validar($_REQUEST['cipa02tematica']);
	//Cupos
	if (isset($_REQUEST['cipa03idoferta']) == 0) {
		$_REQUEST['cipa03idoferta'] = '';
	}
	if (isset($_REQUEST['cipa03idinscrito']) == 0) {
		$_REQUEST['cipa03idinscrito'] = 0;
		//$_REQUEST['cipa03idinscrito'] =  $idTercero;
	}
	if (isset($_REQUEST['cipa03idinscrito_td']) == 0) {
		$_REQUEST['cipa03idinscrito_td'] = $APP->tipo_doc;
	}
	if (isset($_REQUEST['cipa03idinscrito_doc']) == 0) {
		$_REQUEST['cipa03idinscrito_doc'] = '';
	}
	if (isset($_REQUEST['cipa03id']) == 0) {
		$_REQUEST['cipa03id'] = '';
	}
	if (isset($_REQUEST['cipa03asistencia']) == 0) {
		$_REQUEST['cipa03asistencia'] = '';
	}
	if (isset($_REQUEST['cipa03jornada_1']) == 0) {
		$_REQUEST['cipa03jornada_1'] = '';
	}
	if (isset($_REQUEST['cipa03jornada_2']) == 0) {
		$_REQUEST['cipa03jornada_2'] = '';
	}
	if (isset($_REQUEST['cipa03jornada_3']) == 0) {
		$_REQUEST['cipa03jornada_3'] = '';
	}
	if (isset($_REQUEST['cipa03jornada_4']) == 0) {
		$_REQUEST['cipa03jornada_4'] = '';
	}
	if (isset($_REQUEST['cipa03jornada_5']) == 0) {
		$_REQUEST['cipa03jornada_5'] = '';
	}
	if (isset($_REQUEST['cipa03idmatricula']) == 0) {
		$_REQUEST['cipa03idmatricula'] = '';
	}
	if (isset($_REQUEST['cipa03valoracion']) == 0) {
		$_REQUEST['cipa03valoracion'] = '';
	}
	if (isset($_REQUEST['cipa03retroalimentacion']) == 0) {
		$_REQUEST['cipa03retroalimentacion'] = '';
	}
	$_REQUEST['cipa03idoferta'] = numeros_validar($_REQUEST['cipa03idoferta']);
	$_REQUEST['cipa03idinscrito'] = numeros_validar($_REQUEST['cipa03idinscrito']);
	$_REQUEST['cipa03idinscrito_td'] = cadena_Validar($_REQUEST['cipa03idinscrito_td']);
	$_REQUEST['cipa03idinscrito_doc'] = cadena_Validar($_REQUEST['cipa03idinscrito_doc']);
	$_REQUEST['cipa03id'] = numeros_validar($_REQUEST['cipa03id']);
	$_REQUEST['cipa03asistencia'] = numeros_validar($_REQUEST['cipa03asistencia']);
	$_REQUEST['cipa03jornada_1'] = numeros_validar($_REQUEST['cipa03jornada_1']);
	$_REQUEST['cipa03jornada_2'] = numeros_validar($_REQUEST['cipa03jornada_2']);
	$_REQUEST['cipa03jornada_3'] = numeros_validar($_REQUEST['cipa03jornada_3']);
	$_REQUEST['cipa03jornada_4'] = numeros_validar($_REQUEST['cipa03jornada_4']);
	$_REQUEST['cipa03jornada_5'] = numeros_validar($_REQUEST['cipa03jornada_5']);
	$_REQUEST['cipa03idmatricula'] = numeros_validar($_REQUEST['cipa03idmatricula']);
	$_REQUEST['cipa03valoracion'] = numeros_validar($_REQUEST['cipa03valoracion']);
	$_REQUEST['cipa03retroalimentacion'] = cadena_Validar($_REQUEST['cipa03retroalimentacion']);
}
// Espacio para inicializar otras variables
$bTraerEntorno = false;
if (isset($_REQUEST['csv_separa']) == 0) {
	$_REQUEST['csv_separa'] = ';';
}
if (isset($_REQUEST['bnombre']) == 0) {
	$_REQUEST['bnombre'] = '';
}
if (isset($_REQUEST['blistar']) == 0) {
	$_REQUEST['blistar'] = 0; //Dejar en 1 al finalizar
}
if (isset($_REQUEST['bperiodo']) == 0) {
	$_REQUEST['bperiodo'] = '';
	$bTraerEntorno = true;
}
if (isset($_REQUEST['bcurso']) == 0) {
	$_REQUEST['bcurso'] = '';
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
if (isset($_REQUEST['bcentro']) == 0) {
	$_REQUEST['bcentro'] = '';
}
if (isset($_REQUEST['bclase']) == 0) {
	$_REQUEST['bclase'] = '';
}
if (isset($_REQUEST['balcance']) == 0) {
	$_REQUEST['balcance'] = '';
}
if (isset($_REQUEST['bestado']) == 0) {
	$_REQUEST['bestado'] = '';
}
if ((int)$_REQUEST['paso'] > 0) {
	//Jornadas
/*
	if (isset($_REQUEST['bnombre3802']) == 0) {
		$_REQUEST['bnombre3802'] = '';
	}
*/
	if (isset($_REQUEST['blistar3802']) == 0) {
		$_REQUEST['blistar3802'] = '';
	}
//Cupos
	if (isset($_REQUEST['bdoc3803']) == 0) {
		$_REQUEST['bdoc3803'] = '';
	}
	if (isset($_REQUEST['bnombre3803']) == 0) {
		$_REQUEST['bnombre3803'] = '';
	}
	if (isset($_REQUEST['blistar3803']) == 0) {
		$_REQUEST['blistar3803'] = '';
	}
}
if ($bTraerEntorno) {
	$sSQL = 'SELECT * FROM unad95entorno WHERE unad95id=' . $idTercero . '';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		if ($fila['unad95periodo'] != 0) {
			$_REQUEST['bperiodo'] = $fila['unad95periodo'];
		}
		if ($fila['unad95escuela'] != 0) {
			$_REQUEST['bescuela'] = $fila['unad95escuela'];
		}
		if ($fila['unad95programa'] != 0) {
			$_REQUEST['bprograma'] = $fila['unad95programa'];
		}
		if ($fila['unad95zona'] != 0) {
			$_REQUEST['bzona'] = $fila['unad95zona'];
		}
		if ($fila['unad95centro'] != 0) {
			$_REQUEST['bcentro'] = $fila['unad95centro'];
		}
	}
}
$seg_12 = 0;
$bProcesarEstadistica = false;
list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 12, $idTercero, $objDB);
if ($bDevuelve) {
	$seg_12 = 1;
}
if ($_REQUEST['paso'] == 21) {
	$_REQUEST['paso'] = 3;
	/*
	$iEstadoDestino = 7; //Por defecto se termina, pero si no hay asistencias queda nulo...
	$cipa01est_asistentes = 0;
	$cipa01num_valoraciones = 0;
	$iSumaValoraciones = 0;
	$cipa01valoracion = 0;
	if ($sError == '') {
	}
	if ($sError == '') {
		$sTabla = 'cipa03cupos_' . $_REQUEST['cipa01periodo'];
		//Primero los confirmados los pasamos a Inasistentes...
		$sSQL = 'UPDATE ' . $sTabla . ' SET cipa03asistencia=0 WHERE cipa03idoferta=' . $_REQUEST['cipa01id'] . ' AND cipa03asistencia=-1';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' Contando estudiantes: ' . $sSQL . '<br>';
		}
		$result = $objDB->ejecutasql($sSQL);
		// Totalizar la tabla...
		$sSQL = 'SELECT cipa03asistencia, cipa03valoracion, COUNT(1) AS Total
		FROM ' . $sTabla . ' 
		WHERE cipa03idoferta=' . $_REQUEST['cipa01id'] . '
		GROUP BY cipa03asistencia, cipa03valoracion';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' Contando estudiantes: ' . $sSQL . '<br>';
		}
		$tabla = $objDB->ejecutasql($sSQL);
		while ($fila = $objDB->sf($tabla)){
			switch($fila['cipa03asistencia']){
				case -2: // Convocado
				case -1: // Confirmado
				case 0: // No asiste
					break;
				case 1: // Asistente
					$cipa01est_asistentes = $cipa01est_asistentes + $fila['Total'];
					// ahora la valoración
					if ($fila['cipa03valoracion'] > 0) {
						$iSumaValoraciones = $iSumaValoraciones + ($fila['cipa03valoracion'] * $fila['Total']);
					}
					break;
			}
		}
		// ---- Termina de totalizar
		if ($cipa01est_asistentes == 0) {
			$iEstadoDestino = 9;
		} else {
			if ($iSumaValoraciones > 0) {
				$cipa01valoracion = round($iSumaValoraciones / $cipa01est_asistentes, 2);
			}
		}
	}
	if ($sError == '') {
		$sSQL = 'UPDATE cipa01oferta SET cipa01estado=' . $iEstadoDestino . ', cipa01est_asistentes=' . $cipa01est_asistentes . ',
		cipa01num_valoraciones=' . $cipa01num_valoraciones . ', cipa01valoracion=' . $cipa01valoracion . ' 
		WHERE cipa01id=' . $_REQUEST['cipa01id'] . ';';
		$result = $objDB->ejecutasql($sSQL);
		if ($iEstadoDestino == 7){
			$sError = $ETI['msg_terminado'];
			$sResultado = 'Resultado: ' . $cipa01est_asistentes . ' asistentes';
		} else {
			$sError = $ETI['msg_nulo'];
			$sResultado = 'Resultado: NULO';
		}
		seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 3, $_REQUEST['cipa01id'], 'Termina el CIPAS - ' . $sResultado, $objDB);
		$iTipoError = 1;
		$bProcesarEstadistica = true;
	} else {
		$_REQUEST['paso'] = 2;
	}
	*/
	$bProcesarEstadistica = true;
}
//Procesar la estadistica
if ($_REQUEST['paso'] == 22) {
	$_REQUEST['paso'] = 2;
	$bProcesarEstadistica = true;
}
if ($bProcesarEstadistica) {
	$_REQUEST['paso'] = 2;
	if ($sError == '') {
		list($iEstado, $sError, $sDebugE) = f3801_ProcesarEstadistica($_REQUEST['cipa01id'], $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugE;
	}
	if ($sError == '') {
		$sError = 'Se ha procesado la estadistica.';
		$iTipoError = 1;
		$_REQUEST['paso'] = 3;
	}
	//-- Termina de procesar la estadistica.
}
//Si Modifica o Elimina Cargar los campos
if (($_REQUEST['paso'] == 1) || ($_REQUEST['paso'] == 3)) {
	$_REQUEST['cipa01iddocente_td'] = $APP->tipo_doc;
	$_REQUEST['cipa01iddocente_doc'] = '';
	$_REQUEST['cipa01iddocente2_td'] = $APP->tipo_doc;
	$_REQUEST['cipa01iddocente2_doc'] = '';
	$_REQUEST['cipa01iddocente3_td'] = $APP->tipo_doc;
	$_REQUEST['cipa01iddocente3_doc'] = '';
	$_REQUEST['cipa01idmonitor_td'] = $APP->tipo_doc;
	$_REQUEST['cipa01idmonitor_doc'] = '';
	$_REQUEST['cipa01idsupervisor_td'] = $APP->tipo_doc;
	$_REQUEST['cipa01idsupervisor_doc'] = '';
	if ($_REQUEST['paso'] == 1) {
		$sSQLcondi = 'cipa01periodo=' . $_REQUEST['cipa01periodo'] . ' AND cipa01consec=' . $_REQUEST['cipa01consec'] . '';
	} else {
		$sSQLcondi = 'cipa01id=' . $_REQUEST['cipa01id'] . '';
	}
	$sSQL = 'SELECT * FROM cipa01oferta WHERE ' . $sSQLcondi;
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$_REQUEST['cipa01periodo'] = $fila['cipa01periodo'];
		$_REQUEST['cipa01consec'] = $fila['cipa01consec'];
		$_REQUEST['cipa01id'] = $fila['cipa01id'];
		$_REQUEST['cipa01nombre'] = $fila['cipa01nombre'];
		$_REQUEST['cipa01alcance'] = $fila['cipa01alcance'];
		$_REQUEST['cipa01clase'] = $fila['cipa01clase'];
		$_REQUEST['cipa01estado'] = $fila['cipa01estado'];
		$_REQUEST['cipa01escuela'] = $fila['cipa01escuela'];
		$_REQUEST['cipa01programa'] = $fila['cipa01programa'];
		$_REQUEST['cipa01zona'] = $fila['cipa01zona'];
		$_REQUEST['cipa01centro'] = $fila['cipa01centro'];
		$_REQUEST['cipa01idcurso'] = $fila['cipa01idcurso'];
		$_REQUEST['cipa01tematica'] = $fila['cipa01tematica'];
		$_REQUEST['cipa01est_proyectados'] = $fila['cipa01est_proyectados'];
		$_REQUEST['cipa01est_asistentes'] = $fila['cipa01est_asistentes'];
		$_REQUEST['cipa01iddocente'] = $fila['cipa01iddocente'];
		$_REQUEST['cipa01iddocente2'] = $fila['cipa01iddocente2'];
		$_REQUEST['cipa01iddocente3'] = $fila['cipa01iddocente3'];
		$_REQUEST['cipa01idmonitor'] = $fila['cipa01idmonitor'];
		$_REQUEST['cipa01idsupervisor'] = $fila['cipa01idsupervisor'];
		$_REQUEST['cipa01num_valoraciones'] = $fila['cipa01num_valoraciones'];
		$_REQUEST['cipa01valoracion'] = $fila['cipa01valoracion'];
		$_REQUEST['cipa01fechatermina'] = $fila['cipa01fechatermina'];
		$_REQUEST['cipa01proximafecha'] = $fila['cipa01proximafecha'];
		$_REQUEST['cipa01horatermina'] = $fila['cipa01horatermina'];
		$_REQUEST['cipa01mintermina'] = $fila['cipa01mintermina'];
		$_REQUEST['cipa01idorigeneviden'] = $fila['cipa01idorigeneviden'];
		$_REQUEST['cipa01idarchivoeviden'] = $fila['cipa01idarchivoeviden'];
		$_REQUEST['cipa01convocados'] = $fila['cipa01convocados'];
		$_REQUEST['cipa01noasiste'] = $fila['cipa01noasiste'];
		$bcargo = true;
		$_REQUEST['paso'] = 2;
		$_REQUEST['boculta3801'] = 0;
		$bLimpiaHijos = true;
		if ($seg_12 == 0){
			$bPertenece = false;
			$aListaCampos = array('cipa01iddocente', 'cipa01iddocente2', 'cipa01iddocente3', 'cipa01idmonitor', 'cipa01idsupervisor');
			for ($k = 0; $k <= 4; $k++){
				if (!$bPertenece) {
					if ($_REQUEST[$aListaCampos[$k]] == $_SESSION['unad_id_tercero']){
						$bPertenece = true;
					}
				}
			}
			if (!$bPertenece) {
				$sError = 'No tiene permiso para consultar este CIPAS';
				$_REQUEST['paso'] = -1;
			}
		}
	} else {
		$_REQUEST['paso'] = 0;
	}
}
//Cerrar
$bCerrando = false;
if ($_REQUEST['paso'] == 16) {
	$_REQUEST['paso'] = 12;
	$_REQUEST['cipa01estado'] = 1;
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
		//$_REQUEST['cipa01estado'] = 7;
	} else {
		$sSQL = 'UPDATE cipa01oferta SET cipa01estado = 0 WHERE cipa01id=' . $_REQUEST['cipa01id'] . '';
		$tabla = $objDB->ejecutasql($sSQL);
		seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 3, $_REQUEST['cipa01id'], 'Abre Oferta de CIPAS', $objDB);
		$_REQUEST['cipa01estado'] = 0;
		$sError = '<b>' . $ETI['msg_itemabierto'] . '</b>';
		$iTipoError = 1;
	}
}
//Insertar o modificar un elemento
if (($_REQUEST['paso'] == 10) || ($_REQUEST['paso'] == 12)) {
	$bMueveScroll = true;
	list($_REQUEST, $sError, $iTipoError, $bCerrando, $sErrorCerrando, $sDebugGuardar) = f3801_db_GuardarV2($_REQUEST, $objDB, $bDebug, $idTercero);
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
	$_REQUEST['cipa01consec_nuevo'] = numeros_validar($_REQUEST['cipa01consec_nuevo']);
	if ($_REQUEST['cipa01consec_nuevo'] == '') {
		$sError = $ERR['cipa01consec'];
	}
	if ($sError == '') {
		list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 8, $idTercero, $objDB);
		if (!$bDevuelve) {
			$sError = $ERR['8'];
		}
	}
	if ($sError == '') {
		//Ver que el consecutivo no exista.
		$sSQL = 'SELECT cipa01id FROM cipa01oferta WHERE cipa01consec=' . $_REQUEST['cipa01consec_nuevo'] . ' AND cipa01periodo=' . $_REQUEST['cipa01periodo'] . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$sError = 'El consecutivo ' . $_REQUEST['cipa01consec_nuevo'] . ' ya existe';
		}
	}
	if ($sError == '') {
		//Aplicar el cambio.
		$sSQL = 'UPDATE cipa01oferta SET cipa01consec=' . $_REQUEST['cipa01consec_nuevo'] . ' WHERE cipa01id=' . $_REQUEST['cipa01id'] . '';
		$tabla = $objDB->ejecutasql($sSQL);
		$sDetalle = 'Cambia el consecutivo de ' . $_REQUEST['cipa01consec'] . ' a ' . $_REQUEST['cipa01consec_nuevo'] . '';
		$_REQUEST['cipa01consec'] = $_REQUEST['cipa01consec_nuevo'];
		$_REQUEST['cipa01consec_nuevo'] = '';
		seg_auditar($iCodModulo, $_SESSION['u_idtercero'], 8, $_REQUEST['cipa01id'], $sDetalle, $objDB);
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
		list($sError, $iTipoError, $sDebugElimina) = f3801_db_Eliminar($_REQUEST['cipa01id'], $objDB, $bDebug);
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
	$_REQUEST['cipa01periodo'] = '';
	$_REQUEST['cipa01consec'] = '';
	$_REQUEST['cipa01consec_nuevo'] = '';
	$_REQUEST['cipa01id'] = '';
	$_REQUEST['cipa01nombre'] = '';
	$_REQUEST['cipa01alcance'] = '';
	$_REQUEST['cipa01clase'] = '';
	$_REQUEST['cipa01estado'] = 0;
	$_REQUEST['cipa01escuela'] = '';
	$_REQUEST['cipa01programa'] = '';
	$_REQUEST['cipa01zona'] = '';
	$_REQUEST['cipa01centro'] = '';
	$_REQUEST['cipa01idcurso'] = '';
	$_REQUEST['cipa01tematica'] = '';
	$_REQUEST['cipa01est_proyectados'] = '';
	$_REQUEST['cipa01est_asistentes'] = 0;
	$_REQUEST['cipa01iddocente'] = 0; //$idTercero;
	$_REQUEST['cipa01iddocente_td'] = $APP->tipo_doc;
	$_REQUEST['cipa01iddocente_doc'] = '';
	$_REQUEST['cipa01iddocente2'] = 0; //$idTercero;
	$_REQUEST['cipa01iddocente2_td'] = $APP->tipo_doc;
	$_REQUEST['cipa01iddocente2_doc'] = '';
	$_REQUEST['cipa01iddocente3'] = 0; //$idTercero;
	$_REQUEST['cipa01iddocente3_td'] = $APP->tipo_doc;
	$_REQUEST['cipa01iddocente3_doc'] = '';
	$_REQUEST['cipa01idmonitor'] = 0; //$idTercero;
	$_REQUEST['cipa01idmonitor_td'] = $APP->tipo_doc;
	$_REQUEST['cipa01idmonitor_doc'] = '';
	$_REQUEST['cipa01idsupervisor'] = 0; //$idTercero;
	$_REQUEST['cipa01idsupervisor_td'] = $APP->tipo_doc;
	$_REQUEST['cipa01idsupervisor_doc'] = '';
	$_REQUEST['cipa01num_valoraciones'] = 0;
	$_REQUEST['cipa01valoracion'] = 0;
	$_REQUEST['cipa01fechatermina'] = 0;
	$_REQUEST['cipa01proximafecha'] = 0;
	$_REQUEST['cipa01horatermina'] = 0;
	$_REQUEST['cipa01mintermina'] = 0;
	$_REQUEST['cipa01idorigeneviden'] = 0;
	$_REQUEST['cipa01idarchivoeviden'] = 0;
	$_REQUEST['cipa01convocados'] = 0;
	$_REQUEST['cipa01noasiste'] = 0;
	$_REQUEST['paso'] = 0;
}
if ($bLimpiaHijos) {
	$_REQUEST['cipa02idoferta'] = '';
	$_REQUEST['cipa02consec'] = '';
	$_REQUEST['cipa02id'] = '';
	$_REQUEST['cipa02forma'] = 0;
	$_REQUEST['cipa02lugar'] = '';
	$_REQUEST['cipa02link'] = '';
	$_REQUEST['cipa02fecha'] = 0;
	$_REQUEST['cipa02horaini'] = 0;
	$_REQUEST['cipa02minini'] = 0;
	$_REQUEST['cipa02horafin'] = 0;
	$_REQUEST['cipa02minfin'] = 0;
	$_REQUEST['cipa02numinscritos'] = '';
	$_REQUEST['cipa02numparticipantes'] = '';
	$_REQUEST['cipa02tematica'] = '';
	$_REQUEST['cipa03idoferta'] = '';
	$_REQUEST['cipa03idinscrito'] = 0; //$idTercero;
	$_REQUEST['cipa03idinscrito_td'] = $APP->tipo_doc;
	$_REQUEST['cipa03idinscrito_doc'] = '';
	$_REQUEST['cipa03id'] = '';
	$_REQUEST['cipa03asistencia'] = 0;
	$_REQUEST['cipa03jornada_1'] = 0;
	$_REQUEST['cipa03jornada_2'] = 0;
	$_REQUEST['cipa03jornada_3'] = 0;
	$_REQUEST['cipa03jornada_4'] = 0;
	$_REQUEST['cipa03jornada_5'] = 0;
	$_REQUEST['cipa03idmatricula'] = 0;
	$_REQUEST['cipa03valoracion'] = '';
	$_REQUEST['cipa03retroalimentacion'] = '';
}
//AQUI SE DEBEN CARGAR TODOS LOS DATOS QUE LA FORMA NECESITE.
//DATOS PARA COMPLETAR EL FORMULARIO
$iAgnoIni = 2022;
$iAgno = fecha_agno();
$iAgnoFin = $iAgno + 1;
$bBorrador = true;
$bConBotonTerminar = false;
$bEditaJornadas = false;
$bConBotonEstadistica = false;
if ($_REQUEST['paso'] != 0) {
	$bBorrador = false;
	switch($_REQUEST['cipa01estado']){
		case 0:
			$bBorrador = true;
			$bEditaJornadas = true;
			break;
		case 1:
			$bEditaJornadas = true;
			$bConBotonTerminar = true;
			break;
		case 7: // Terminado
			$bConBotonEstadistica = true;
			break;
		case 9: // Nulo
			$bConBotonEstadistica = true;
			break;
	}
}
$iPeriodoBase = 0;
if ($idEntidad == 0) {
	$iPeriodoBase = 1140;
}
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
//Crear los controles que requieran llamado a base de datos
$objCombos = new clsHtmlCombos();
$objTercero = new clsHtmlTercero();
list($cipa01estado_nombre, $sErrorDet) = tabla_campoxid('cipa11estado', 'cipa11nombre', 'cipa11id', $_REQUEST['cipa01estado'], '{' . $ETI['msg_sindato'] . '}', $objDB);
$html_cipa01estado = html_oculto('cipa01estado', $_REQUEST['cipa01estado'], $cipa01estado_nombre);
$objCombos->nuevo('cipa01escuela', $_REQUEST['cipa01escuela'], true, '{' . $ETI['msg_todas'] . '}', 0);
$objCombos->iAncho = 400;
$objCombos->sAccion = 'carga_combo_cipa01programa();';
$sSQL = 'SELECT core12id AS id, core12nombre AS nombre FROM core12escuela WHERE core12tieneestudiantes="S" ORDER BY core12nombre';
$html_cipa01escuela = $objCombos->html($sSQL, $objDB);
$html_cipa01programa = f3801_HTMLComboV2_cipa01programa($objDB, $objCombos, $_REQUEST['cipa01programa'], $_REQUEST['cipa01escuela']);
$objCombos->nuevo('cipa01zona', $_REQUEST['cipa01zona'], true, '{' . $ETI['msg_todas'] . '}', 0);
$objCombos->sAccion = 'carga_combo_cipa01centro();';
$sSQL = 'SELECT unad23id AS id, unad23nombre AS nombre FROM unad23zona WHERE unad23conestudiantes="S" ORDER BY unad23nombre';
$html_cipa01zona = $objCombos->html($sSQL, $objDB);
$html_cipa01centro = f3801_HTMLComboV2_cipa01centro($objDB, $objCombos, $_REQUEST['cipa01centro'], $_REQUEST['cipa01zona']);
$html_cipa01idcurso = f3801_HTMLComboV2_cipa01idcurso($objDB, $objCombos, $_REQUEST['cipa01idcurso'], $_REQUEST['cipa01periodo']);
list($cipa01iddocente_rs, $_REQUEST['cipa01iddocente'], $_REQUEST['cipa01iddocente_td'], $_REQUEST['cipa01iddocente_doc']) = html_tercero($_REQUEST['cipa01iddocente_td'], $_REQUEST['cipa01iddocente_doc'], $_REQUEST['cipa01iddocente'], 0, $objDB);
list($cipa01iddocente2_rs, $_REQUEST['cipa01iddocente2'], $_REQUEST['cipa01iddocente2_td'], $_REQUEST['cipa01iddocente2_doc']) = html_tercero($_REQUEST['cipa01iddocente2_td'], $_REQUEST['cipa01iddocente2_doc'], $_REQUEST['cipa01iddocente2'], 0, $objDB);
list($cipa01iddocente3_rs, $_REQUEST['cipa01iddocente3'], $_REQUEST['cipa01iddocente3_td'], $_REQUEST['cipa01iddocente3_doc']) = html_tercero($_REQUEST['cipa01iddocente3_td'], $_REQUEST['cipa01iddocente3_doc'], $_REQUEST['cipa01iddocente3'], 0, $objDB);
list($cipa01idmonitor_rs, $_REQUEST['cipa01idmonitor'], $_REQUEST['cipa01idmonitor_td'], $_REQUEST['cipa01idmonitor_doc']) = html_tercero($_REQUEST['cipa01idmonitor_td'], $_REQUEST['cipa01idmonitor_doc'], $_REQUEST['cipa01idmonitor'], 0, $objDB);
list($cipa01idsupervisor_rs, $_REQUEST['cipa01idsupervisor'], $_REQUEST['cipa01idsupervisor_td'], $_REQUEST['cipa01idsupervisor_doc']) = html_tercero($_REQUEST['cipa01idsupervisor_td'], $_REQUEST['cipa01idsupervisor_doc'], $_REQUEST['cipa01idsupervisor'], 0, $objDB);
if ($bBorrador) {
	$objCombos->nuevo('cipa01alcance', $_REQUEST['cipa01alcance'], true, '{' . $ETI['msg_seleccione'] . '}');
	$sSQL = 'SELECT cipa13id AS id, cipa13nombre AS nombre FROM cipa13alcance ORDER BY cipa13id';
	$html_cipa01alcance = $objCombos->html($sSQL, $objDB);
	$objCombos->nuevo('cipa01clase', $_REQUEST['cipa01clase'], true, '{' . $ETI['msg_seleccione'] . '}');
	$objCombos->iAncho = 210;
	$sSQL = 'SELECT cipa14id AS id, cipa14nombre AS nombre FROM cipa14clasecipas ORDER BY cipa14nombre';
	$html_cipa01clase = $objCombos->html($sSQL, $objDB);
} else {
	list($cipa01alcance_nombre, $sErrorDet) = tabla_campoxid('cipa13alcance', 'cipa13nombre', 'cipa13id', $_REQUEST['cipa01alcance'], '{' . $ETI['msg_sindato'] . '}', $objDB);
	$html_cipa01alcance = html_oculto('cipa01alcance', $_REQUEST['cipa01alcance'], $cipa01alcance_nombre);
	list($cipa01clase_nombre, $sErrorDet) = tabla_campoxid('cipa14clasecipas', 'cipa14nombre', 'cipa14id', $_REQUEST['cipa01clase'], '{' . $ETI['msg_sindato'] . '}', $objDB);
	$html_cipa01clase = html_oculto('cipa01clase', $_REQUEST['cipa01clase'], $cipa01clase_nombre);
}
if ((int)$_REQUEST['paso'] == 0) {
	$html_cipa01periodo = f3801_HTMLComboV2_cipa01periodo($objDB, $objCombos, $_REQUEST['cipa01periodo']);
} else {
	$cipa01periodo_nombre = '&nbsp;';
	if ((int)$_REQUEST['cipa01periodo'] != 0) {
		list($cipa01periodo_nombre, $sErrorDet) = tabla_campoxid('exte02per_aca', 'exte02nombre', 'exte02id', $_REQUEST['cipa01periodo'], '{' . $ETI['msg_sindato'] . '}', $objDB);
	}
	$html_cipa01periodo = html_oculto('cipa01periodo', $_REQUEST['cipa01periodo'], $cipa01periodo_nombre);
	$objCombos->nuevo('cipa02forma', $_REQUEST['cipa02forma'], true, $acipa02forma[0], 0);
	$objCombos->sAccion = 'forma02()';
	$objCombos->addItem(1, $acipa02forma[1]);
	//$objCombos->addArreglo($acipa02forma, $icipa02forma);
	$html_cipa02forma = $objCombos->html('', $objDB);
	list($cipa03idinscrito_rs, $_REQUEST['cipa03idinscrito'], $_REQUEST['cipa03idinscrito_td'], $_REQUEST['cipa03idinscrito_doc']) = html_tercero($_REQUEST['cipa03idinscrito_td'], $_REQUEST['cipa03idinscrito_doc'], $_REQUEST['cipa03idinscrito'], 0, $objDB);
}
//Alistar datos adicionales
$bPuedeAbrir = false;
if ($_REQUEST['paso'] != 0) {
	if ($_REQUEST['cipa01estado'] == 1) {
		//Definir las condiciones que permitirán abrir el registro.
		list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 17, $idTercero, $objDB);
		if ($bDevuelve) {
			$bPuedeAbrir = true;
		}
	}
}
$id_rpt = 0;
//$id_rpt=reportes_id(_Identificador_Tipo_Reporte_, $objDB);
$bTodos = false;
if ($seg_12 == 1) {
	$bTodos = true;
}
$objCombos->nuevo('blistar', $_REQUEST['blistar'], $bTodos, '{' . $ETI['msg_todos'] . '}');
$objCombos->sAccion = 'paginarf3801()';
$objCombos->addItem(1 , 'Donde soy responsable');
$sSQL = '';
$html_blistar = $objCombos->html($sSQL, $objDB);
$objCombos->nuevo('bperiodo', $_REQUEST['bperiodo'], true, '{' . $ETI['msg_todos'] . '}');
$objCombos->iAncho = 600;
$objCombos->sAccion = 'cambiapagina()';
$sSQL = f146_ConsultaCombo('exte02id>'.$iPeriodoBase);
$html_bperiodo = $objCombos->html($sSQL, $objDB);
$objCombos->nuevo('bcurso', $_REQUEST['bcurso'], true, '{' . $ETI['msg_todos'] . '}');
$objCombos->iAncho = 600;
$objCombos->sAccion = 'paginarf3801()';
$sSQL = '';
if ((int)$_REQUEST['bperiodo'] != 0){
	$sIds = '-99';
	$sSQL = 'SELECT ofer08idcurso FROM ofer08oferta WHERE ofer08idper_aca=' . $_REQUEST['bperiodo'] . ' AND ofer08estadooferta=1';
	$tabla = $objDB->ejecutasql($sSQL);
	while ($fila = $objDB->sf($tabla)) {
		$sIds = $sIds . ',' . $fila['ofer08idcurso'];
	}
	$sSQL = 'SELECT unad40id AS id, CONCAT(unad40titulo, " - ", unad40nombre) AS nombre 
	FROM unad40curso 
	WHERE unad40id IN (' . $sIds . ')
	ORDER BY unad40nombre';
}
$html_bcurso = $objCombos->html($sSQL, $objDB);

$objCombos->nuevo('bescuela', $_REQUEST['bescuela'], true, '{' . $ETI['msg_todas'] . '}');
$objCombos->sAccion = 'paginarf3801()';
$sSQL = 'SELECT core12id AS id, core12nombre AS nombre FROM core12escuela WHERE core12id>0 ORDER BY core12tieneestudiantes DESC, core12nombre';
$html_bescuela = $objCombos->html($sSQL, $objDB);
$objCombos->nuevo('bprograma', $_REQUEST['bprograma'], true, '{' . $ETI['msg_todos'] . '}');
$objCombos->sAccion = 'paginarf3801()';
$objCombos->iAncho=600;
$sSQL = 'SELECT core09id AS id, CONCAT(core09nombre, " [", core09codigo, "]") AS nombre FROM core09programa WHERE core09id>0 ORDER BY core09nombre';
$html_bprograma = $objCombos->html($sSQL, $objDB);
$objCombos->nuevo('bzona', $_REQUEST['bzona'], true, '{' . $ETI['msg_todos'] . '}');
$objCombos->sAccion = 'paginarf3801()';
$sSQL = 'SELECT unad23id AS id, unad23nombre AS nombre FROM unad23zona WHERE unad23id>0 ORDER BY unad23conestudiantes DESC, unad23nombre';
$html_bzona = $objCombos->html($sSQL, $objDB);
$objCombos->nuevo('bcentro', $_REQUEST['bcentro'], true, '{' . $ETI['msg_todos'] . '}');
$objCombos->sAccion = 'paginarf3801()';
$sSQL = 'SELECT unad24id AS id, unad24nombre AS nombre FROM unad24sede WHERE unad24id>0 ORDER BY unad24nombre';
$html_bcentro = $objCombos->html($sSQL, $objDB);
$objCombos->nuevo('bclase', $_REQUEST['bclase'], true, '{' . $ETI['msg_todos'] . '}');
$objCombos->sAccion = 'paginarf3801()';
$sSQL = 'SELECT cipa14id AS id, cipa14nombre AS nombre FROM cipa14clasecipas ORDER BY cipa14nombre';
$html_bclase = $objCombos->html($sSQL, $objDB);
$objCombos->nuevo('balcance', $_REQUEST['balcance'], true, '{' . $ETI['msg_todos'] . '}');
$objCombos->sAccion = 'paginarf3801()';
$sSQL = 'SELECT cipa13id AS id, cipa13nombre AS nombre FROM cipa13alcance ORDER BY cipa13id';
$html_balcance = $objCombos->html($sSQL, $objDB);
$objCombos->nuevo('bestado', $_REQUEST['bestado'], true, '{' . $ETI['msg_todos'] . '}');
$objCombos->sAccion = 'paginarf3801()';
$sSQL = 'SELECT cipa11id AS id, cipa11nombre AS nombre FROM cipa11estado ORDER BY cipa11id';
$html_bestado = $objCombos->html($sSQL, $objDB);

/*
//$html_blistar = $objCombos->comboSistema(3801, 1, $objDB, 'paginarf3801()');
$html_blistar3802 = $objCombos->comboSistema(3802, 1, $objDB, 'paginarf3802()');
*/

if ($_REQUEST['paso'] > 0) {
	$objCombos->nuevo('blistar3802', $_REQUEST['blistar3802'], true, 'Sin detalles');
	$objCombos->sAccion = 'paginarf3802()';
	$objCombos->addItem(1, 'Detallado');
	$sSQL = '';
	$html_blistar3802 = $objCombos->html($sSQL, $objDB);

	$objCombos->nuevo('blistar3803', $_REQUEST['blistar3803'], true, '{' . $ETI['msg_todos'] . '}');
	$objCombos->sAccion = 'paginarf3803()';
	$objCombos->addItem('-2', $ETI['msg_preinscrito']);
	$objCombos->addItem('-1', $ETI['msg_inscrito']);
	$objCombos->addItem('0', $ETI['msg_noasistente']);
	$objCombos->addItem('1', $ETI['msg_asistente']);
	$sSQL = '';
	$html_blistar3803 = $objCombos->html($sSQL, $objDB);
}
if (false) {
	$objCombos->nuevo('csv_separa', $_REQUEST['csv_separa'], false);
	$objCombos->addItem(',', $ETI['msg_coma']);
	$objCombos->addItem(';', $ETI['msg_puntoycoma']);
	$csv_separa = '<label class="Label90">' . $ETI['msg_separador'] . '</label><label class="Label130">' . $objCombos->html('', $objDB) . '</label>';
} else {
	$csv_separa = '<input id="csv_separa" name="csv_separa" type="hidden" value="," />';
}
$iNumFormatosImprime = 0;
$iModeloReporte = 3801;
$html_iFormatoImprime = '<input id="iformatoimprime" name="iformatoimprime" type="hidden" value="0" />
';

if ($_REQUEST['paso'] > 0) {
	$bDevuelve = false;
	//list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 5, $idTercero, $objDB);
	if ($bDevuelve) {
		$seg_5 = 1;
	}
	if ($_REQUEST['cipa01estado'] == 0) {
		$bDevuelve = false;
		//list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 8, $idTercero, $objDB);
		if ($bDevuelve) {
			$seg_8 = 1;
		}
	}
}
//Cargar las tablas de datos
$aParametros[0] = ''; //$_REQUEST['p1_3801'];
$aParametros[100] = $idTercero;
$aParametros[101] = $_REQUEST['paginaf3801'];
$aParametros[102] = $_REQUEST['lppf3801'];
$aParametros[103] = $_REQUEST['bnombre'];
$aParametros[104] = $_REQUEST['blistar'];
$aParametros[105] = $_REQUEST['bperiodo'];
$aParametros[106] = $_REQUEST['bcurso'];
$aParametros[107] = $_REQUEST['bescuela'];
$aParametros[108] = $_REQUEST['bprograma'];
$aParametros[109] = $_REQUEST['bzona'];
$aParametros[110] = $_REQUEST['bcentro'];
$aParametros[111] = $_REQUEST['bclase'];
$aParametros[112] = $_REQUEST['balcance'];
$aParametros[113] = $_REQUEST['bestado'];

list($sTabla3801, $sDebugTabla) = f3801_TablaDetalleV2($aParametros, $objDB, $bDebug);
$sDebug = $sDebug . $sDebugTabla;
$sTabla3802 = '';
$sTabla3803 = '';
if ($_REQUEST['paso'] != 0) {
	//Jornadas
	$aParametros3802[0] = $_REQUEST['cipa01id'];
	$aParametros3802[100] = $idTercero;
	$aParametros3802[101] = $_REQUEST['paginaf3802'];
	$aParametros3802[102] = $_REQUEST['lppf3802'];
	//$aParametros3802[103] = $_REQUEST['bnombre3802'];
	$aParametros3802[104] = $_REQUEST['blistar3802'];
	list($sTabla3802, $sDebugTabla) = f3802_TablaDetalleV2($aParametros3802, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugTabla;
	//Cupos
	$aParametros3803[0] = $_REQUEST['cipa01id'];
	$aParametros3803[100] = $idTercero;
	$aParametros3803[101] = $_REQUEST['paginaf3803'];
	$aParametros3803[102] = $_REQUEST['lppf3803'];
	$aParametros3803[103] = $_REQUEST['bdoc3803'];
	$aParametros3803[104] = $_REQUEST['bnombre3803'];
	$aParametros3803[105] = $_REQUEST['blistar3803'];
	list($sTabla3803, $sDebugTabla) = f3803_TablaDetalleV2($aParametros3803, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugTabla;
}
$bDebugMenu = false;
list($et_menu, $sDebugM) = html_menuV2($APP->idsistema, $objDB, $iPiel, $bDebugMenu, $idTercero);
$sDebug = $sDebug . $sDebugM;
$objDB->CerrarConexion();
//FORMA
require $APP->rutacomun . 'unad_forma_v2.php';
forma_cabeceraV3($xajax, $ETI['titulo_3801']);
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
if ($iPiel == 2) {
?>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>js/access.js"></script>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>js/mogu-menu-access.js"></script>
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>style-2023.css?v=2" type="text/css" />
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>css/mogu-menu-access.css" type="text/css" />
<?php
}
?>
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
		if (window.document.frmedita.cipa01estado.value == 0) {
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
			window.document.frmlista.consulta.value = window.document.frmedita.consulta_3801.value;
			window.document.frmlista.titulos.value = window.document.frmedita.titulos_3801.value;
			window.document.frmlista.nombrearchivo.value = 'Oferta de CIPAS';
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
			window.document.frmimpp.action = 'e3801.php';
			window.document.frmimpp.submit();
		} else {
			ModalMensaje(sError);
		}
	}

	function imprimep() {
		if (window.document.frmedita.seg_5.value == 1) {
			asignarvariables();
			window.document.frmimpp.action = 'p3801.php';
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
		datos[1] = window.document.frmedita.cipa01periodo.value;
		datos[2] = window.document.frmedita.cipa01consec.value;
		if ((datos[1] != '') && (datos[2] != '')) {
			xajax_f3801_ExisteDato(datos);
		}
	}

	function cargadato(llave1, llave2) {
		window.document.frmedita.cipa01periodo.value = String(llave1);
		window.document.frmedita.cipa01consec.value = String(llave2);
		window.document.frmedita.paso.value = 1;
		window.document.frmedita.submit();
	}

	function cargaridf3801(llave1) {
		window.document.frmedita.cipa01id.value = String(llave1);
		window.document.frmedita.paso.value = 3;
		window.document.frmedita.submit();
	}

	function carga_combo_cipa01programa() {
		let params = new Array();
		params[0] = window.document.frmedita.cipa01escuela.value;
		document.getElementById('div_cipa01programa').innerHTML = '<b>Procesando datos, por favor espere...</b><input id="cipa01programa" name="cipa01programa" type="hidden" value="" />';
		xajax_f3801_Combocipa01programa(params);
	}

	function carga_combo_cipa01centro() {
		let params = new Array();
		params[0] = window.document.frmedita.cipa01zona.value;
		document.getElementById('div_cipa01centro').innerHTML = '<b>Procesando datos, por favor espere...</b><input id="cipa01centro" name="cipa01centro" type="hidden" value="" />';
		xajax_f3801_Combocipa01centro(params);
	}

	function carga_combo_cipa01idcurso() {
		let params = new Array();
		params[0] = window.document.frmedita.cipa01periodo.value;
		document.getElementById('div_cipa01idcurso').innerHTML = '<b>Procesando datos, por favor espere...</b><input id="cipa01idcurso" name="cipa01idcurso" type="hidden" value="" />';
		xajax_f3801_Combocipa01idcurso(params);
	}

	function limpia_cipa01idarchivoeviden() {
		window.document.frmedita.cipa01idorigeneviden.value = 0;
		window.document.frmedita.cipa01idarchivoeviden.value = 0;
		let da_Archivoeviden = document.getElementById('div_cipa01idarchivoeviden');
		da_Archivoeviden.innerHTML = '&nbsp;';
		verboton('beliminacipa01idarchivoeviden', 'none');
		//paginarf3801();
	}

	function carga_cipa01idarchivoeviden() {
		window.document.frmedita.iscroll.value = window.pageYOffset;
		window.document.frmedita.div96v1.value = '';
		window.document.frmedita.div96v2.value = '';
		window.document.frmedita.div96v3.value = '';
		document.getElementById('div_96titulo').innerHTML = '<h2>' + window.document.frmedita.titulo_3801.value + ' - Cargar archivo</h2>';
		document.getElementById('div_96cuerpo').innerHTML = '<iframe id="iframe96" src="framearchivo.php?ref=3801&id=' + window.document.frmedita.cipa01id.value+'" height="400px" width="100%" frameborder="0"></iframe>';
		expandesector(96);
		window.scrollTo(0, 150);
	}

	function eliminacipa01idarchivoeviden() {
		let did = window.document.frmedita.cipa01id;
		ModalConfirmV2('&iquest;<?php echo $ETI['eliminar_cipa01idarchivoeviden']; ?>?', () => {
			xajax_elimina_archivo_cipa01idarchivoeviden(did.value);
			//paginarf3801();
		});
	}

	function paginarf3801() {
		let params = new Array();
		params[99] = window.document.frmedita.debug.value;
		params[100] = <?php echo $idTercero; ?>;
		params[101] = window.document.frmedita.paginaf3801.value;
		params[102] = window.document.frmedita.lppf3801.value;
		params[103] = window.document.frmedita.bnombre.value;
		params[104] = window.document.frmedita.blistar.value;
		params[105] = window.document.frmedita.bperiodo.value;
		params[106] = window.document.frmedita.bcurso.value;
		params[107] = window.document.frmedita.bescuela.value;
		params[108] = window.document.frmedita.bprograma.value;
		params[109] = window.document.frmedita.bzona.value;
		params[110] = window.document.frmedita.bcentro.value;
		params[111] = window.document.frmedita.bclase.value;
		params[112] = window.document.frmedita.balcance.value;
		params[113] = window.document.frmedita.bestado.value;

		document.getElementById('div_f3801detalle').innerHTML = '<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf3801" name="paginaf3801" type="hidden" value="' + params[101] + '" /><input id="lppf3801" name="lppf3801" type="hidden" value="' + params[102] + '" />';
		xajax_f3801_HtmlTabla(params);
	}

	function enviacerrar() {
		ModalConfirmV2('<?php echo $ETI['msg_cierre3801']; ?>', () => {
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
		document.getElementById("cipa01periodo").focus();
	}

	function buscarV2016(sCampo) {
		window.document.frmedita.iscroll.value = window.pageYOffset;
		expandesector(98);
		window.document.frmedita.scampobusca.value = sCampo;
		let params = new Array();
		params[1] = sCampo;
		//params[2] = window.document.frmedita.iagno.value;
		//params[3] = window.document.frmedita.itipo.value;
		xajax_f3801_Busquedas(params);
	}

	function retornacontrol() {
		expandesector(1);
		window.scrollTo(0, window.document.frmedita.iscroll.value);
	}

	function Devuelve(sValor) {
		let sCampo = window.document.frmedita.scampobusca.value;
		if (sCampo == 'cipa01iddocente') {
			ter_traerxid('cipa01iddocente', sValor);
		}
		if (sCampo == 'cipa01iddocente2') {
			ter_traerxid('cipa01iddocente2', sValor);
		}
		if (sCampo == 'cipa01iddocente3') {
			ter_traerxid('cipa01iddocente3', sValor);
		}
		if (sCampo == 'cipa01idmonitor') {
			ter_traerxid('cipa01idmonitor', sValor);
		}
		if (sCampo == 'cipa01idsupervisor') {
			ter_traerxid('cipa01idsupervisor', sValor);
		}
		if (sCampo == 'cipa03idinscrito') {
			ter_traerxid('cipa03idinscrito', sValor);
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
		if (ref == 3801) {
			if (sRetorna != '') {
				window.document.frmedita.cipa01idorigeneviden.value = window.document.frmedita.div96v1.value;
				window.document.frmedita.cipa01idarchivoeviden.value = sRetorna;
				verboton('beliminacipa01idarchivoeviden', 'block');
			}
			archivo_lnk(window.document.frmedita.cipa01idorigeneviden.value, window.document.frmedita.cipa01idarchivoeviden.value, 'div_cipa01idarchivoeviden');
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

	function forma02(){
		let sLugar = 'none';
		let sLink = 'block';
		if (window.document.frmedita.cipa02forma.value == 1){
			sLugar = 'block';
			sLink = 'none';
		}
		document.getElementById('lbl_cipa02lugar').style.display = sLugar;
		document.getElementById('lbl_cipa02link').style.display = sLink;
	}
<?php
if ($bConBotonTerminar) {
?>
	function terminar(){
	ModalConfirmV2('<?php echo $ETI['msg_confirmaterminar']; ?>', () => {
			ejecuta_terminar();
		});
	}

	function ejecuta_terminar() {
		MensajeAlarmaV2('<?php echo $ETI['msg_ejecutando']; ?>', 2);
		expandesector(98);
		window.document.frmedita.paso.value = 21;
		window.document.frmedita.submit();	
	}
<?php
}
if ($bConBotonEstadistica){
?>
	function armar_estadistica() {
		MensajeAlarmaV2('<?php echo $ETI['msg_estadistica']; ?>', 2);
		expandesector(98);
		window.document.frmedita.paso.value = 22;
		window.document.frmedita.submit();	
	}
<?php
}
?>
</script>
<?php
if ($_REQUEST['paso'] != 0) {
?>
<script language="javascript" src="jsi/js3802.js?v=2"></script>
<script language="javascript" src="jsi/js3803.js?v=1"></script>
<form id="frmimpp" name="frmimpp" method="post" action="p3801.php" target="_blank">
<input id="r" name="r" type="hidden" value="3801" />
<input id="id3801" name="id3801" type="hidden" value="<?php echo $_REQUEST['cipa01id']; ?>" />
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
	if ($_REQUEST['cipa01estado'] == 0) {
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
		if ($_REQUEST['cipa01estado'] == 7) {
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
if ($_REQUEST['cipa01estado'] == 0) {
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
echo '<h2>' . $ETI['titulo_3801'] . '</h2>';
?>
</div>
</div>
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
echo html_tipodocV2('deb_tipodoc', $_REQUEST['deb_tipodoc']);
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
<label class="Label30"></label>
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
if ($_REQUEST['boculta3801'] != 0) {
	$sEstiloExpande = '';
	$sEstiloRecoje = ' style="display:none;"';
	$sEstiloDiv = ' style="display:none;"';
}
?>
<input id="boculta3801" name="boculta3801" type="hidden" value="<?php echo $_REQUEST['boculta3801']; ?>" />
<label class="Label30">
<input id="btexpande3801" name="btexpande3801" type="button" value="Mostrar" class="btMiniExpandir" onclick="expandepanel(3801, 'block', 0);" title="<?php echo $ETI['bt_mostrar']; ?>"<?php echo $sEstiloExpande; ?>/>
</label>
<label class="Label30">
<input id="btrecoge3801" name="btrecoge3801" type="button" value="Ocultar" class="btMiniRecoger" onclick="expandepanel(3801, 'none', 1);" title="<?php echo $ETI['bt_ocultar']; ?>" <?php echo $sEstiloRecoje; ?>/>
</label>
</div>
<div id="div_p3801"<?php echo $sEstiloDiv; ?>>
<?php
}
//Mostrar formulario para editar
?>
<label class="Label130">
<?php
echo $ETI['cipa01periodo'];
?>
</label>
<label class="Label600">
<?php
echo $html_cipa01periodo;
?>
</label>
<div class="salto1px"></div>
<label class="Label130"<?php echo $sOcultaConsec; ?>>
<?php
echo $ETI['cipa01consec'];
?>
</label>
<label class="Label130"<?php echo $sOcultaConsec; ?>>
<?php
if ($_REQUEST['paso'] != 2) {
?>
<input id="cipa01consec" name="cipa01consec" type="text" value="<?php echo $_REQUEST['cipa01consec']; ?>" onchange="RevisaLlave()" class="cuatro" />
<?php
} else {
	echo html_oculto('cipa01consec', $_REQUEST['cipa01consec'], formato_numero($_REQUEST['cipa01consec']));
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
<label class="Label60"<?php echo $sOcultaId; ?>>
<?php
echo $ETI['cipa01id'];
?>
</label>
<label class="Label60"<?php echo $sOcultaId; ?>>
<?php
	echo html_oculto('cipa01id', $_REQUEST['cipa01id'], formato_numero($_REQUEST['cipa01id']));
?>
</label>
<label class="Label90">
<?php
echo $ETI['cipa01alcance'];
?>
</label>
<label class="Label220">
<?php
echo $html_cipa01alcance;
?>
</label>
<label class="Label90">
<?php
echo $ETI['cipa01clase'];
?>
</label>
<label class="Label220">
<?php
echo $html_cipa01clase;
?>
</label>
<label class="Label90">
<?php
echo $ETI['cipa01estado'];
?>
</label>
<label class="Label130">
<?php
echo $html_cipa01estado;
?>
</label>
<label class="L">
<?php
echo $ETI['cipa01nombre'];
?>

<input id="cipa01nombre" name="cipa01nombre" type="text" value="<?php echo $_REQUEST['cipa01nombre']; ?>" maxlength="200" class="L" placeholder="<?php echo $ETI['ing_campo'] . $ETI['cipa01nombre']; ?>" />
</label>
<div class="salto1px"></div>

<div class="GrupoCampos520">
<div class="salto5px"></div>
<label class="Label90">
<?php
echo $ETI['cipa01escuela'];
?>
</label>
<label>
<?php
echo $html_cipa01escuela;
?>
</label>
<div class="salto1px"></div>
<label class="Label90">
<?php
echo $ETI['cipa01programa'];
?>
</label>
<label>
<div id="div_cipa01programa">
<?php
echo $html_cipa01programa;
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label90">
<?php
echo $ETI['cipa01idcurso'];
?>
</label>
<label>
<div id="div_cipa01idcurso">
<?php
echo $html_cipa01idcurso;
?>
</div>
</label>
<div class="salto1px"></div>
</div>

<div class="GrupoCampos520">
<div class="salto5px"></div>
<label class="Label90">
<?php
echo $ETI['cipa01zona'];
?>
</label>
<label>
<?php
echo $html_cipa01zona;
?>
</label>
<div class="salto1px"></div>
<label class="Label90">
<?php
echo $ETI['cipa01centro'];
?>
</label>
<label>
<div id="div_cipa01centro">
<?php
echo $html_cipa01centro;
?>
</div>
</label>
<?php
if ($bConBotonTerminar) {
?>
<div class="salto1px"></div>
<label class="Label90">&nbsp;</label>
<label class="Label130">
<input id="cmdTermina" name="cmdTermina" type="button" class="BotonAzul" value="Terminar" onclick="javascript:terminar()" title="Terminar el CIPAS" />
</label>
<?php
}
if ($bConBotonEstadistica){
?>
<div class="salto1px"></div>
<label class="Label90">&nbsp;</label>
<label class="Label160">
<input id="cmdEstadistica" name="cmdEstadistica" type="button" class="BotonAzul160" value="Armar estadistica" onclick="javascript:armar_estadistica()" title="Armar estadistica" />
</label>
<?php
}
?>
<div class="salto1px"></div>
</div>

<div class="salto1px"></div>
<label class="txtAreaS">
<?php
echo $ETI['cipa01tematica'];
?>
<textarea id="cipa01tematica" name="cipa01tematica" placeholder="<?php echo $ETI['ing_campo'] . $ETI['cipa01tematica']; ?>"><?php echo $_REQUEST['cipa01tematica']; ?></textarea>
</label>
<label class="Label220">
<?php
echo $ETI['cipa01est_proyectados'];
?>
</label>
<label class="Label90">
<?php
if ($bBorrador) {
?>
<input id="cipa01est_proyectados" name="cipa01est_proyectados" type="text" value="<?php echo $_REQUEST['cipa01est_proyectados']; ?>" onchange="RevisaLlave()" class="diez" maxlength="10" placeholder="<?php echo $ETI['ing_vr']; ?>" />
<?php
} else {
	echo html_oculto('cipa01est_proyectados', $_REQUEST['cipa01est_proyectados']);
}
?>
</label>
<label class="Label160">
<?php
echo $ETI['cipa01num_valoraciones'];
?>
</label>
<label class="Label90">
<div id="div_cipa01num_valoraciones">
<?php
echo html_oculto('cipa01num_valoraciones', $_REQUEST['cipa01num_valoraciones']);
?>
</div>
</label>
<label class="Label90">
<?php
echo $ETI['cipa01valoracion'];
?>
</label>
<label class="Label90">
<?php
echo html_oculto('cipa01valoracion', $_REQUEST['cipa01valoracion'], formato_numero($_REQUEST['cipa01valoracion'], 2));
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['cipa01convocados'];
?>
</label>
<label class="Label130">
<div id="div_cipa01convocados">
<?php
echo html_oculto('cipa01convocados', $_REQUEST['cipa01convocados']);
?>
</div>
</label>
<label class="Label130">
<?php
echo $ETI['cipa01noasiste'];
?>
</label>
<label class="Label130">
<div id="div_cipa01noasiste">
<?php
echo html_oculto('cipa01noasiste', $_REQUEST['cipa01noasiste']);
?>
</div>
</label>
<label class="Label130">
<?php
echo $ETI['cipa01est_asistentes'];
?>
</label>
<label class="Label90">
<div id="div_cipa01est_asistentes">
<?php
echo html_oculto('cipa01est_asistentes', $_REQUEST['cipa01est_asistentes']);
?>
</div>
</label>
<label class="Label90">
<?php
echo $ETI['msg_total'];
?>
</label>
<label class="Label90">
<b>
<?php
echo formato_numero($_REQUEST['cipa01convocados'] + $_REQUEST['cipa01noasiste'] + $_REQUEST['cipa01est_asistentes']);
?>
</b>
</label>

<div class="salto1px"></div>
<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo $ETI['cipa01iddocente'];
?>
</label>
<div class="salto1px"></div>
<input id="cipa01iddocente" name="cipa01iddocente" type="hidden" value="<?php echo $_REQUEST['cipa01iddocente']; ?>" />
<div id="div_cipa01iddocente_llaves">
<?php
$bOculto = !$bBorrador;
echo html_DivTerceroV2('cipa01iddocente', $_REQUEST['cipa01iddocente_td'], $_REQUEST['cipa01iddocente_doc'], $bOculto, 0, $ETI['ing_doc']);
$iCajones = 1;
?>
</div>
<div class="salto1px"></div>
<div id="div_cipa01iddocente" class="L"><?php echo $cipa01iddocente_rs; ?></div>
<div class="salto1px"></div>
</div>
<?php
$bEntra = true;
if (!$bBorrador){
	if ($_REQUEST['cipa01iddocente2'] == 0) {
		$bEntra = false;
	}
}
if ($bEntra){
	$iCajones++;
?>
<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo $ETI['cipa01iddocente2'];
?>
</label>
<div class="salto1px"></div>
<input id="cipa01iddocente2" name="cipa01iddocente2" type="hidden" value="<?php echo $_REQUEST['cipa01iddocente2']; ?>" />
<div id="div_cipa01iddocente2_llaves">
<?php
$bOculto = !$bBorrador;
echo html_DivTerceroV2('cipa01iddocente2', $_REQUEST['cipa01iddocente2_td'], $_REQUEST['cipa01iddocente2_doc'], $bOculto, 0, $ETI['ing_doc']);
?>
</div>
<div class="salto1px"></div>
<div id="div_cipa01iddocente2" class="L"><?php echo $cipa01iddocente2_rs; ?></div>
<div class="salto1px"></div>
</div>
<?php
} else {
?>
<input id="cipa01iddocente2" name="cipa01iddocente2" type="hidden" value="<?php echo $_REQUEST['cipa01iddocente2']; ?>" />
<input id="cipa01iddocente2_td" name="cipa01iddocente2_td" type="hidden" value="<?php echo $_REQUEST['cipa01iddocente2_td']; ?>" />
<input id="cipa01iddocente2_doc" name="cipa01iddocente2_doc" type="hidden" value="<?php echo $_REQUEST['cipa01iddocente2_doc']; ?>" />
<?php	
}
$bEntra = true;
if (!$bBorrador){
	if ($_REQUEST['cipa01iddocente3'] == 0) {
		$bEntra = false;
	}
}
if ($bEntra){
	$iCajones++;
?>
<div class="salto1px"></div>
<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo $ETI['cipa01iddocente3'];
?>
</label>
<div class="salto1px"></div>
<input id="cipa01iddocente3" name="cipa01iddocente3" type="hidden" value="<?php echo $_REQUEST['cipa01iddocente3']; ?>" />
<div id="div_cipa01iddocente3_llaves">
<?php
$bOculto = !$bBorrador;
echo html_DivTerceroV2('cipa01iddocente3', $_REQUEST['cipa01iddocente3_td'], $_REQUEST['cipa01iddocente3_doc'], $bOculto, 0, $ETI['ing_doc']);
?>
</div>
<div class="salto1px"></div>
<div id="div_cipa01iddocente3" class="L"><?php echo $cipa01iddocente3_rs; ?></div>
<div class="salto1px"></div>
</div>
<?php
} else {
?>
<input id="cipa01iddocente3" name="cipa01iddocente3" type="hidden" value="<?php echo $_REQUEST['cipa01iddocente3']; ?>" />
<input id="cipa01iddocente3_td" name="cipa01iddocente3_td" type="hidden" value="<?php echo $_REQUEST['cipa01iddocente3_td']; ?>" />
<input id="cipa01iddocente3_doc" name="cipa01iddocente3_doc" type="hidden" value="<?php echo $_REQUEST['cipa01iddocente3_doc']; ?>" />
<?php
}
$bEntra = true;
if (!$bBorrador){
	if ($_REQUEST['cipa01idmonitor'] == 0) {
		$bEntra = false;
	}
}
if ($bEntra){
	$iCajones++;
?>
<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo $ETI['cipa01idmonitor'];
?>
</label>
<div class="salto1px"></div>
<input id="cipa01idmonitor" name="cipa01idmonitor" type="hidden" value="<?php echo $_REQUEST['cipa01idmonitor']; ?>" />
<div id="div_cipa01idmonitor_llaves">
<?php
$bOculto = !$bBorrador;
echo html_DivTerceroV2('cipa01idmonitor', $_REQUEST['cipa01idmonitor_td'], $_REQUEST['cipa01idmonitor_doc'], $bOculto, 0, $ETI['ing_doc']);
?>
</div>
<div class="salto1px"></div>
<div id="div_cipa01idmonitor" class="L"><?php echo $cipa01idmonitor_rs; ?></div>
<div class="salto1px"></div>
</div>
<?php
} else {
?>
<input id="cipa01idmonitor" name="cipa01idmonitor" type="hidden" value="<?php echo $_REQUEST['cipa01idmonitor']; ?>" />
<input id="cipa01idmonitor_td" name="cipa01idmonitor_td" type="hidden" value="<?php echo $_REQUEST['cipa01idmonitor_td']; ?>" />
<input id="cipa01idmonitor_doc" name="cipa01idmonitor_doc" type="hidden" value="<?php echo $_REQUEST['cipa01idmonitor_doc']; ?>" />
<?php
}
if ($iCajones > 1){
	echo html_salto();
}
?>
<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo $ETI['cipa01idsupervisor'];
?>
</label>
<div class="salto1px"></div>
<input id="cipa01idsupervisor" name="cipa01idsupervisor" type="hidden" value="<?php echo $_REQUEST['cipa01idsupervisor']; ?>" />
<div id="div_cipa01idsupervisor_llaves">
<?php
$bOculto = !$bBorrador;
echo html_DivTerceroV2('cipa01idsupervisor', $_REQUEST['cipa01idsupervisor_td'], $_REQUEST['cipa01idsupervisor_doc'], $bOculto, 0, $ETI['ing_doc']);
?>
</div>
<div class="salto1px"></div>
<div id="div_cipa01idsupervisor" class="L"><?php echo $cipa01idsupervisor_rs; ?></div>
<div class="salto1px"></div>
</div>

<input id="cipa01idorigeneviden" name="cipa01idorigeneviden" type="hidden" value="<?php echo $_REQUEST['cipa01idorigeneviden']; ?>" />
<input id="cipa01idarchivoeviden" name="cipa01idarchivoeviden" type="hidden" value="<?php echo $_REQUEST['cipa01idarchivoeviden']; ?>" />
<div class="GrupoCampos300">
<div class="salto1px"></div>
<div id="div_cipa01idarchivoeviden" class="Campo300">
<?php
echo html_lnkarchivo((int)$_REQUEST['cipa01idorigeneviden'], (int)$_REQUEST['cipa01idarchivoeviden']);
?>
</div>
<?php
$sEstiloAnexa = '';
$sEstiloElimina = ' style="display:none;"';
if ((int)$_REQUEST['cipa01id'] == 0) {
	$sEstiloAnexa = ' style="display:none;"';
}
if ((int)$_REQUEST['cipa01idarchivoeviden'] != 0) {
	$sEstiloElimina = ' style="inline-block;"';
}
?>
<label class="Label130"></label>
<label class="Label30">
<input id="banexacipa01idarchivoeviden" name="banexacipa01idarchivoeviden" type="button" value="Anexar" class="btMiniAnexar" onclick="carga_cipa01idarchivoeviden()" title="Cargar archivo"<?php echo $sEstiloAnexa; ?>/>
</label>
<label class="Label30">
<input id="beliminacipa01idarchivoeviden" name="beliminacipa01idarchivoeviden" type="button" value="Eliminar" class="btMiniEliminar" onclick="eliminacipa01idarchivoeviden()" title="Eliminar archivo"<?php echo $sEstiloElimina; ?>/>
</label>
<div class="salto1px"></div>
</div>
<?php
// -- Inicia Grupo campos 3802 Jornadas
?>
<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo $ETI['titulo_3802'];
?>
</label>
<input id="boculta3802" name="boculta3802" type="hidden" value="<?php echo $_REQUEST['boculta3802']; ?>" />
<?php
if ($_REQUEST['paso'] == 2) {
	if ($bEditaJornadas) {
?>
<div class="ir_derecha"<?php echo $sAnchoExpandeContrae; ?>>
<?php
if (false) {
?>
<label class="Label30">
<input id="btexcel3802" name="btexcel3802" type="button" value="Exportar" class="btMiniExcel" onclick="imprime3802();" title="Exportar" />
</label>
<?php
}
$sEstiloExpande = ' style="display:none;"';
$sEstiloRecoje = '';
$sEstiloDiv = '';
if ($_REQUEST['boculta3802'] != 0) {
	$sEstiloExpande = '';
	$sEstiloRecoje = ' style="display:none;"';
	$sEstiloDiv = ' style="display:none;"';
}
?>
<label class="Label30">
<input id="btexpande3802" name="btexpande3802" type="button" value="Expandir" class="btMiniExpandir" onclick="expandepanel(3802, 'block', 0);" title="<?php echo $ETI['bt_mostrar']; ?>"<?php echo $sEstiloExpande; ?>/>
</label>
<label class="Label30">
<input id="btrecoge3802" name="btrecoge3802" type="button" value="Recoger" class="btMiniRecoger" onclick="expandepanel(3802, 'none', 1);" title="<?php echo $ETI['bt_ocultar']; ?>"<?php echo $sEstiloRecoje; ?>/>
</label>
</div>
<div class="salto1px"></div>
<div id="div_p3802"<?php echo $sEstiloDiv; ?>>
<label class="Label130"<?php echo $sOcultaConsec; ?>>
<?php
echo $ETI['cipa02consec'];
?>
</label>
<label class="Label130"<?php echo $sOcultaConsec; ?>>
<div id="div_cipa02consec">
<?php
if ((int)$_REQUEST['cipa02id'] == 0) {
?>
<input id="cipa02consec" name="cipa02consec" type="text" value="<?php echo $_REQUEST['cipa02consec']; ?>" onchange="revisaf3802()" class="cuatro" />
<?php
} else {
	echo html_oculto('cipa02consec', $_REQUEST['cipa02consec'], formato_numero($_REQUEST['cipa02consec']));
}
?>
</div>
</label>
<label class="Label60"<?php echo $sOcultaId; ?>>
<?php
echo $ETI['cipa02id'];
?>
</label>
<label class="Label60"<?php echo $sOcultaId; ?>>
<div id="div_cipa02id">
<?php
	echo html_oculto('cipa02id', $_REQUEST['cipa02id'], formato_numero($_REQUEST['cipa02id']));
?>
</div>
</label>
<label class="Label130">
<?php
echo $ETI['cipa02forma'];
?>
</label>
<label>
<?php
echo $html_cipa02forma;
?>
</label>
<?php
$sLugar = ' style="display:none;"';
$sLink = '';
if ($_REQUEST['cipa02forma'] == 1) {
	$sLugar = '';
	$sLink = ' style="display:none;"';
}
?>
<label class="txtAreaS" id="lbl_cipa02lugar"<?php echo $sLugar; ?>>
<?php
echo $ETI['cipa02lugar'] . '<br>';
?>
<textarea id="cipa02lugar" name="cipa02lugar" placeholder="<?php echo $ETI['ing_campo'] . $ETI['cipa02lugar']; ?>"><?php echo $_REQUEST['cipa02lugar']; ?></textarea>
</label>
<label class="txtAreaS" id="lbl_cipa02link"<?php echo $sLink; ?>>
<?php
echo $ETI['cipa02link'] . '<br>';
?>
<textarea id="cipa02link" name="cipa02link" placeholder="<?php echo $ETI['ing_campo'] . $ETI['cipa02link']; ?>"><?php echo $_REQUEST['cipa02link']; ?></textarea>
</label>
<label class="Label130">
<?php
echo $ETI['cipa02fecha'];
?>
</label>
<div class="Campo220">
<?php
echo html_FechaEnNumero('cipa02fecha', $_REQUEST['cipa02fecha'], true, '', $iAgnoIni, $iAgnoFin); //$bvacio, $accion
?>
</div>
<label class="Label30">
<input id="bcipa02fecha_hoy" name="bcipa02fecha_hoy" type="button" value="Hoy" class="btMiniHoy" onclick="fecha_AsignarNum('cipa02fecha', '<?php echo $iHoy; ?>')" title="<?php echo $ETI['bt_hoy']; ?>" />
</label>
<label class="Label30"></label>
<label class="Label60">
<?php
echo $ETI['msg_de'];
?>
</label>
<div class="campo_HoraMin" id="div_cipa02horaini">
<?php
echo html_HoraMin('cipa02horaini', $_REQUEST['cipa02horaini'], 'cipa02minini', $_REQUEST['cipa02minini']);
?>
</div>
<label class="Label30"></label>
<label class="Label30">
<?php
echo $ETI['msg_a'];
?>
</label>
<div class="campo_HoraMin" id="div_cipa02horafin">
<?php
echo html_HoraMin('cipa02horafin', $_REQUEST['cipa02horafin'], 'cipa02minfin', $_REQUEST['cipa02minfin']);
?>
</div>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['cipa02numinscritos'];
?>
</label>
<label class="Label130">
<div id="div_cipa02numinscritos">
<?php
echo html_oculto('cipa02numinscritos', $_REQUEST['cipa02numinscritos']);
?>
</div>
</label>
<label class="Label130">
<?php
echo $ETI['cipa02numparticipantes'];
?>
</label>
<label class="Label130">
<div id="div_cipa02numparticipantes">
<?php
echo html_oculto('cipa02numparticipantes', $_REQUEST['cipa02numparticipantes']);
?>
</div>
</label>
<div class="salto1px"></div>
<label class="txtAreaS">
<?php
echo $ETI['cipa02tematica'];
?>
<textarea id="cipa02tematica" name="cipa02tematica" placeholder="<?php echo $ETI['ing_campo'] . $ETI['cipa02tematica']; ?>"><?php echo $_REQUEST['cipa02tematica']; ?></textarea>
</label>
<div class="salto1px"></div>
<label class="Label130">&nbsp;</label>
<?php
$sEstiloElimina = ' style="display:none;"';
if ((int)$_REQUEST['cipa02id'] != 0) {
	$sEstiloElimina = ' style="inline-block;"';
}
?>
<label class="Label30">
<input id="bguarda3802" name="bguarda3802" type="button" value="Guardar" class="btMiniGuardar" onclick="guardaf3802()" title="<?php echo $ETI['bt_mini_guardar_3802']; ?>" />
</label>
<label class="Label30">
<input id="blimpia3802" name="blimpia3802" type="button" value="Limpiar" class="btMiniLimpiar" onclick="limpiaf3802()" title="<?php echo $ETI['bt_mini_limpiar_3802']; ?>" />
</label>
<label class="Label30">
<input id="belimina3802" name="belimina3802" type="button" value="Eliminar" class="btMiniEliminar" onclick="eliminaf3802()" title="<?php echo $ETI['bt_mini_eliminar_3802']; ?>"<?php echo $sEstiloElimina; ?>/>
</label>
<?php
//Este es el cierre del div_p3802
?>
<div class="salto1px"></div>
</div>
<?php
		} //Termina el segundo bloque  condicional - bloque editar.
?>
<div class="salto1px"></div>
<div class="ir_derecha GrupoCamposAyuda">
<?php
if (false) {
?>
<label class="Label130">
<?php
echo $ETI['msg_nombre'];
?>
</label>
<label>
<input id="bnombre3802" name="bnombre3802" type="text" value="<?php echo $_REQUEST['bnombre3802']; ?>" onchange="paginarf3802()" />
</label>
<?php
}
?>
<label class="Label130">
<?php
echo 'Visualizar';
//echo $ETI['msg_Listar'];
?>
</label>
<label>
<?php
echo $html_blistar3802;
?>
</label>
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
<div id="div_f3802detalle">
<?php
echo $sTabla3802;
?>
</div>
<?php
}
?>
<div class="salto1px"></div>
</div>
<?php
// -- Termina Grupo campos 3802 Jornadas
?>
<?php
// -- Inicia Grupo campos 3803 Cupos
?>
<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo $ETI['titulo_3803'];
?>
</label>
<input id="boculta3803" name="boculta3803" type="hidden" value="<?php echo $_REQUEST['boculta3803']; ?>" />
<?php
if ($_REQUEST['paso'] == 2) {
	if (false) {
?>
<div class="ir_derecha"<?php echo $sAnchoExpandeContrae; ?>>
<?php
if (false) {
?>
<label class="Label30">
<input id="btexcel3803" name="btexcel3803" type="button" value="Exportar" class="btMiniExcel" onclick="imprime3803();" title="Exportar" />
</label>
<?php
}
$sEstiloExpande = ' style="display:none;"';
$sEstiloRecoje = '';
$sEstiloDiv = '';
if ($_REQUEST['boculta3803'] != 0) {
	$sEstiloExpande = '';
	$sEstiloRecoje = ' style="display:none;"';
	$sEstiloDiv = ' style="display:none;"';
}
?>
<label class="Label30">
<input id="btexpande3803" name="btexpande3803" type="button" value="Expandir" class="btMiniExpandir" onclick="expandepanel(3803, 'block', 0);" title="<?php echo $ETI['bt_mostrar']; ?>"<?php echo $sEstiloExpande; ?>/>
</label>
<label class="Label30">
<input id="btrecoge3803" name="btrecoge3803" type="button" value="Recoger" class="btMiniRecoger" onclick="expandepanel(3803, 'none', 1);" title="<?php echo $ETI['bt_ocultar']; ?>"<?php echo $sEstiloRecoje; ?>/>
</label>
</div>
<div class="salto1px"></div>
<div id="div_p3803"<?php echo $sEstiloDiv; ?>>
<div class="salto1px"></div>
<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo $ETI['cipa03idinscrito'];
?>
</label>
<div class="salto1px"></div>
<input id="cipa03idinscrito" name="cipa03idinscrito" type="hidden" value="<?php echo $_REQUEST['cipa03idinscrito']; ?>" />
<div id="div_cipa03idinscrito_llaves">
<?php
$bOculto = true;
if ((int)$_REQUEST['cipa03id'] == 0) {
	$bOculto = false;
}
echo html_DivTerceroV2('cipa03idinscrito', $_REQUEST['cipa03idinscrito_td'], $_REQUEST['cipa03idinscrito_doc'], $bOculto, 1, $ETI['ing_doc']);
?>
</div>
<div class="salto1px"></div>
<div id="div_cipa03idinscrito" class="L"><?php echo $cipa03idinscrito_rs; ?></div>
<div class="salto1px"></div>
</div>
<label class="Label60"<?php echo $sOcultaId; ?>>
<?php
echo $ETI['cipa03id'];
?>
</label>
<label class="Label60"<?php echo $sOcultaId; ?>>
<div id="div_cipa03id">
<?php
	echo html_oculto('cipa03id', $_REQUEST['cipa03id'], formato_numero($_REQUEST['cipa03id']));
?>
</div>
</label>
<label class="Label130">
<?php
echo $ETI['cipa03asistencia'];
?>
</label>
<label class="Label130">
<div id="div_cipa03asistencia">
<?php
echo html_oculto('cipa03asistencia', $_REQUEST['cipa03asistencia']);
?>
</div>
</label>
<label class="Label130">
<?php
echo $ETI['cipa03jornada_1'];
?>
</label>
<label class="Label130">
<div id="div_cipa03jornada_1">
<?php
echo html_oculto('cipa03jornada_1', $_REQUEST['cipa03jornada_1']);
?>
</div>
</label>
<label class="Label130">
<?php
echo $ETI['cipa03jornada_2'];
?>
</label>
<label class="Label130">
<div id="div_cipa03jornada_2">
<?php
echo html_oculto('cipa03jornada_2', $_REQUEST['cipa03jornada_2']);
?>
</div>
</label>
<label class="Label130">
<?php
echo $ETI['cipa03jornada_3'];
?>
</label>
<label class="Label130">
<div id="div_cipa03jornada_3">
<?php
echo html_oculto('cipa03jornada_3', $_REQUEST['cipa03jornada_3']);
?>
</div>
</label>
<label class="Label130">
<?php
echo $ETI['cipa03jornada_4'];
?>
</label>
<label class="Label130">
<div id="div_cipa03jornada_4">
<?php
echo html_oculto('cipa03jornada_4', $_REQUEST['cipa03jornada_4']);
?>
</div>
</label>
<label class="Label130">
<?php
echo $ETI['cipa03jornada_5'];
?>
</label>
<label class="Label130">
<div id="div_cipa03jornada_5">
<?php
echo html_oculto('cipa03jornada_5', $_REQUEST['cipa03jornada_5']);
?>
</div>
</label>
<label class="Label130">
<?php
echo $ETI['cipa03idmatricula'];
?>
</label>
<label class="Label130">
<div id="div_cipa03idmatricula">
<?php
echo html_oculto('cipa03idmatricula', $_REQUEST['cipa03idmatricula']);
?>
</div>
</label>
<label class="Label130">
<?php
echo $ETI['cipa03valoracion'];
?>
</label>
<label class="Label130">

<input id="cipa03valoracion" name="cipa03valoracion" type="text" value="<?php echo $_REQUEST['cipa03valoracion']; ?>" class="diez" maxlength="10" placeholder="<?php echo $ETI['ing_vr']; ?>" />
</label>
<label class="L">
<?php
echo $ETI['cipa03retroalimentacion'];
?>

<input id="cipa03retroalimentacion" name="cipa03retroalimentacion" type="text" value="<?php echo $_REQUEST['cipa03retroalimentacion']; ?>" maxlength="200" class="L" placeholder="<?php echo $ETI['ing_campo'] . $ETI['cipa03retroalimentacion']; ?>" />
</label>
<div class="salto1px"></div>
<label class="Label130">&nbsp;</label>
<?php
$sEstiloElimina = ' style="display:none;"';
if ((int)$_REQUEST['cipa03id'] != 0) {
	$sEstiloElimina = ' style="inline-block;"';
}
?>
<label class="Label30">
<input id="bguarda3803" name="bguarda3803" type="button" value="Guardar" class="btMiniGuardar" onclick="guardaf3803()" title="<?php echo $ETI['bt_mini_guardar_3803']; ?>" />
</label>
<label class="Label30">
<input id="blimpia3803" name="blimpia3803" type="button" value="Limpiar" class="btMiniLimpiar" onclick="limpiaf3803()" title="<?php echo $ETI['bt_mini_limpiar_3803']; ?>" />
</label>
<label class="Label30">
<input id="belimina3803" name="belimina3803" type="button" value="Eliminar" class="btMiniEliminar" onclick="eliminaf3803()" title="<?php echo $ETI['bt_mini_eliminar_3803']; ?>"<?php echo $sEstiloElimina; ?>/>
</label>
<?php
//Este es el cierre del div_p3803
?>
<div class="salto1px"></div>
</div>
<?php
		} //Termina el segundo bloque  condicional - bloque editar.
?>
<div class="salto1px"></div>
<?php
?>
<div class="ir_derecha">
<label class="Label130">
<?php
//echo $ETI['msg_nombre'];
echo 'Documento';
?>
</label>
<label>
<input id="bdoc3803" name="bdoc3803" type="text" value="<?php echo $_REQUEST['bdoc3803']; ?>" onchange="paginarf3803()" />
</label>
<label class="Label130">
<?php
echo $ETI['msg_nombre'];
?>
</label>
<label>
<input id="bnombre3803" name="bnombre3803" type="text" value="<?php echo $_REQUEST['bnombre3803']; ?>" onchange="paginarf3803()" />
</label>
<label class="Label90">
<?php
//echo $ETI['msg_Listar'];
echo 'Estado';
?>
</label>
<label class="Label130">
<?php
echo $html_blistar3803;
?>
</label>
<div class="salto1px"></div>
</div>
<?php
?>
<div class="salto1px"></div>
<div id="div_f3803detalle">
<?php
echo $sTabla3803;
?>
</div>
<?php
}
?>
<div class="salto1px"></div>
</div>
<?php
// -- Termina Grupo campos 3803 Cupos
?>
<?php
if (false) {
	//Ejemplo de boton de ayuda
	//echo html_BotonAyuda('NombreCampo');
	//echo html_DivAyudaLocal('NombreCampo');
}
if ($bConExpande) {
	//Este es el cierre del div_p3801
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
<input id="bnombre" name="bnombre" type="text" value="<?php echo $_REQUEST['bnombre']; ?>" onchange="paginarf3801()" autocomplete="off" />
</label>
<label class="Label90">
<?php
echo $ETI['msg_blistar'];
?>
</label>
<label class="Label160">
<?php
echo $html_blistar;
?>
</label>
<div class="salto1px"></div>
<label class="Label90">
<?php
echo $ETI['cipa01periodo'];
?>
</label>
<label class="Label600">
<?php
echo $html_bperiodo;
?>
</label>
<div class="salto1px"></div>
<label class="Label90">
<?php
echo $ETI['cipa01idcurso'];
?>
</label>
<label class="Label600">
<?php
echo $html_bcurso;
?>
</label>
<div class="salto1px"></div>
<label class="Label90">
<?php
echo $ETI['cipa01escuela'];
?>
</label>
<label class="Label600">
<?php
echo $html_bescuela;
?>
</label>
<div class="salto1px"></div>
<label class="Label90">
<?php
echo $ETI['cipa01programa'];
?>
</label>
<label class="Label600">
<?php
echo $html_bprograma;
?>
</label>
<div class="salto1px"></div>
<label class="Label90">
<?php
echo $ETI['cipa01zona'];
?>
</label>
<label class="Label600">
<?php
echo $html_bzona;
?>
</label>
<div class="salto1px"></div>
<label class="Label90">
<?php
echo $ETI['cipa01centro'];
?>
</label>
<label class="Label600">
<?php
echo $html_bcentro;
?>
</label>
<div class="salto1px"></div>
<label class="Label90">
<?php
echo $ETI['cipa01clase'];
?>
</label>
<label class="Label600">
<?php
echo $html_bclase;
?>
</label>
<div class="salto1px"></div>
<label class="Label90">
<?php
echo $ETI['cipa01alcance'];
?>
</label>
<label class="Label600">
<?php
echo $html_balcance;
?>
</label>
<div class="salto1px"></div>
<label class="Label90">
<?php
echo $ETI['cipa01estado'];
?>
</label>
<label class="Label600">
<?php
echo $html_bestado;
?>
</label>

</div>
<div class="salto1px"></div>
<?php
echo ' ' . $csv_separa;
?>
<div id="div_f3801detalle">
<?php
echo $sTabla3801;
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
echo $ETI['msg_cipa01consec'];
?>
</label>
<label class="Label90">
<?php
echo '<b>' . $_REQUEST['cipa01consec'] . '</b>';
?>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['msg_cipa01consec_nuevo'];
// onchange="RevisaConsec()"
?>
</label>
<label class="Label90">
<input id="cipa01consec_nuevo" name="cipa01consec_nuevo" type="text" value="<?php echo $_REQUEST['cipa01consec_nuevo']; ?>" class="cuatro" />
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
<input id="titulo_3801" name="titulo_3801" type="hidden" value="<?php echo $ETI['titulo_3801']; ?>" />
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
echo '<h2>' . $ETI['titulo_3801'] . '</h2>';
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
echo '<h2>' . $ETI['titulo_3801'] . '</h2>';
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
if ($_REQUEST['cipa01estado'] == 0) {
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
<script language="javascript">
	$().ready(function() {
<?php
if ($_REQUEST['paso'] == 0) {
?>
		$("#cipa01periodo").chosen();
<?php
} 
//cipa01programa
?>
		$("#cipa01programa").chosen();
		$("#cipa01idcurso").chosen();
		$("#bperiodo").chosen();
		$("#bcurso").chosen();
	});
</script>
<script language="javascript" src="ac_3801.js"></script>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>unad_todas.js?ver=8"></script>
<?php
forma_piedepagina();

