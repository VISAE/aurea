<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2023 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.29.3 martes, 4 de abril de 2023
*/
/** Archivo ciparptjornada.php.
 * Modulo 3816 cipa02jornada.
 * @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
 * @param debug = 1  (Opcional), bandera para indicar si se generan datos de depuración
 * @date martes, 4 de abril de 2023
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
if (($bPeticionXAJAX) && ($_SESSION['unad_id_tercero'] == 0)) {
	// viene por xajax.
	$xajax = new xajax();
	$xajax->configure('javascript URI', $APP->rutacomun . 'xajax/');
	$xajax->register(XAJAX_FUNCTION, 'sesion_abandona_V2');
	$xajax->processRequest();
	die();
}
$grupo_id = 1; //Necesita ajustarlo...
$iCodModulo = 3816;
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
$mensajes_3816 = 'lg/lg_3816_' . $_SESSION['unad_idioma'] . '.php';
if (!file_exists($mensajes_3816)) {
	$mensajes_3816 = 'lg/lg_3816_es.php';
}
$mensajes_3802 = 'lg/lg_3802_' . $_SESSION['unad_idioma'] . '.php';
if (!file_exists($mensajes_3802)) {
	$mensajes_3802 = 'lg/lg_3802_es.php';
}
require $mensajes_todas;
require $mensajes_3816;
require $mensajes_3802;
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
	forma_cabeceraV3($xajax, $ETI['titulo_3816']);
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
		header('Location:noticia.php?ret=ciparptjornada.php');
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
// -- Si esta cargando la pagina por primer vez se revisa si requiere auditar y se manda a hacer un limpiar (paso -1)
if (isset($_REQUEST['paso']) == 0) {
	$_REQUEST['paso'] = -1;
	if ($audita[1]) {
		seg_auditaingreso($iCodModulo, $_SESSION['unad_id_tercero'], $objDB);
	}
}
// -- 3816 cipa02jornada
require 'lib3816.php';
$xajax = new xajax();
$xajax->configure('javascript URI', $APP->rutacomun . 'xajax/');
$xajax->register(XAJAX_FUNCTION, 'f3816_Combocipa01programa');
$xajax->register(XAJAX_FUNCTION, 'f3816_Combocipa01centro');
$xajax->register(XAJAX_FUNCTION, 'f3816_Combocipa01idcurso');
$xajax->register(XAJAX_FUNCTION, 'sesion_abandona_V2');
$xajax->register(XAJAX_FUNCTION, 'sesion_mantenerV4');
$xajax->register(XAJAX_FUNCTION, 'f3816_HtmlTabla');
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
if (isset($_REQUEST['paginaf3816']) == 0) {
	$_REQUEST['paginaf3816'] = 1;
}
if (isset($_REQUEST['lppf3816']) == 0) {
	$_REQUEST['lppf3816'] = 20;
}
if (isset($_REQUEST['boculta3816']) == 0) {
	$_REQUEST['boculta3816'] = 0;
}
// -- Inicializar variables de datos.
if (isset($_REQUEST['cipa01periodo']) == 0) {
	$_REQUEST['cipa01periodo'] = '';
}
if (isset($_REQUEST['cipa01alcance']) == 0) {
	$_REQUEST['cipa01alcance'] = '';
}
if (isset($_REQUEST['cipa01clase']) == 0) {
	$_REQUEST['cipa01clase'] = '';
}
if (isset($_REQUEST['cipa01estado']) == 0) {
	$_REQUEST['cipa01estado'] = '';
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
if (isset($_REQUEST['cipa02forma']) == 0) {
	$_REQUEST['cipa02forma'] = 0;
}
if (isset($_REQUEST['cipa02fechaini']) == 0) {
	$_REQUEST['cipa02fechaini'] = '';
	//$_REQUEST['cipa02fechaini'] = $iHoy;
}
if (isset($_REQUEST['cipa02fechafin']) == 0) {
	$_REQUEST['cipa02fechafin'] = '';
	//$_REQUEST['cipa02fechafin'] = $iHoy;
}

if (isset($_REQUEST['cipa01est_proyectados']) == 0) {
	$_REQUEST['cipa01est_proyectados'] = 0;
}
if (isset($_REQUEST['cipa01est_asistentes']) == 0) {
	$_REQUEST['cipa01est_asistentes'] = 0;
}
//$_REQUEST['cipa02fecha'] = numeros_validar($_REQUEST['cipa02fecha']);
$_REQUEST['cipa01periodo'] = numeros_validar($_REQUEST['cipa01periodo']);
$_REQUEST['cipa01alcance'] = numeros_validar($_REQUEST['cipa01alcance']);
$_REQUEST['cipa01clase'] = numeros_validar($_REQUEST['cipa01clase']);
$_REQUEST['cipa01estado'] = numeros_validar($_REQUEST['cipa01estado']);
$_REQUEST['cipa01escuela'] = numeros_validar($_REQUEST['cipa01escuela']);
$_REQUEST['cipa01programa'] = numeros_validar($_REQUEST['cipa01programa']);
$_REQUEST['cipa01zona'] = numeros_validar($_REQUEST['cipa01zona']);
$_REQUEST['cipa01centro'] = numeros_validar($_REQUEST['cipa01centro']);
$_REQUEST['cipa01idcurso'] = numeros_validar($_REQUEST['cipa01idcurso']);
$_REQUEST['cipa02forma'] = numeros_validar($_REQUEST['cipa02forma']);
$_REQUEST['cipa02fechaini'] = numeros_validar($_REQUEST['cipa02fechaini']);
$_REQUEST['cipa02fechafin'] = numeros_validar($_REQUEST['cipa02fechafin']);
$_REQUEST['cipa01est_proyectados'] = numeros_validar($_REQUEST['cipa01est_proyectados']);
$_REQUEST['cipa01est_asistentes'] = numeros_validar($_REQUEST['cipa01est_asistentes']);
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
*/
//limpiar la pantalla
if ($_REQUEST['paso'] == -1) {
	$_REQUEST['cipa02fecha'] = 0;
	$_REQUEST['cipa01periodo'] = '';
	$_REQUEST['cipa01alcance'] = '';
	$_REQUEST['cipa01clase'] = '';
	$_REQUEST['cipa01estado'] = '';
	$_REQUEST['cipa01escuela'] = '';
	$_REQUEST['cipa01programa'] = '';
	$_REQUEST['cipa01zona'] = '';
	$_REQUEST['cipa01centro'] = '';
	$_REQUEST['cipa01idcurso'] = '';
	$_REQUEST['cipa02forma'] = 0;
	$_REQUEST['cipa02fechaini'] = 0;
	$_REQUEST['cipa02fechafin'] = $iHoy;
	$_REQUEST['paso'] = 0;
}
if ($bLimpiaHijos) {
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
list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 6, $idTercero, $objDB);
if ($bDevuelve) {
	$seg_6 = 1;
}
//Crear los controles que requieran llamado a base de datos
$objCombos = new clsHtmlCombos();
$idPeriodoBase = 1143;
$objCombos->nuevo('cipa01periodo', $_REQUEST['cipa01periodo'], true, '{' . $ETI['msg_todos'] . '}');
$objCombos->sAccion = 'paginarf3816()';
$sSQL = 'SELECT cipa01id AS id, cipa01periodo AS periodo FROM cipa01oferta ORDER BY cipa01oferta';
$objCombos->sAccion = 'carga_combo_cipa01idcurso();';
$sSQL = f146_ConsultaCombo('exte02id>'. $idPeriodoBase);
$html_cipa01periodo = $objCombos->html($sSQL, $objDB);
$objCombos->nuevo('cipa01estado', $_REQUEST['cipa01estado'], true, '{' . $ETI['msg_todos'] . '}');
$objCombos->sAccion = 'paginarf3816()';
$sSQL = 'SELECT cipa11id AS id, cipa11nombre AS nombre FROM cipa11estado ORDER BY cipa11nombre';
$html_cipa01estado = $objCombos->html($sSQL, $objDB);
$objCombos->nuevo('cipa01escuela', $_REQUEST['cipa01escuela'], true, '{' . $ETI['msg_todos'] . '}');
$objCombos->sAccion = 'carga_combo_cipa01programa();';
$sSQL = 'SELECT core12id AS id, core12nombre AS nombre FROM core12escuela ORDER BY core12nombre';
$html_cipa01escuela = $objCombos->html($sSQL, $objDB);
$html_cipa01programa = f3816_HTMLComboV2_cipa01programa($objDB, $objCombos, $_REQUEST['cipa01programa'], $_REQUEST['cipa01escuela']);
$objCombos->nuevo('cipa01zona', $_REQUEST['cipa01zona'], true, '{' . $ETI['msg_todos'] . '}');
$objCombos->sAccion = 'carga_combo_cipa01centro();';
$sSQL = 'SELECT unad23id AS id, unad23nombre AS nombre FROM unad23zona ORDER BY unad23nombre';
$html_cipa01zona = $objCombos->html($sSQL, $objDB);
$html_cipa01centro = f3816_HTMLComboV2_cipa01centro($objDB, $objCombos, $_REQUEST['cipa01centro'], $_REQUEST['cipa01zona']);
$html_cipa01idcurso = f3816_HTMLComboV2_cipa01idcurso($objDB, $objCombos, $_REQUEST['cipa01idcurso'], $_REQUEST['cipa01periodo']);
$objCombos->nuevo('cipa02forma', $_REQUEST['cipa02forma'], true, $acipa02forma[0], 0);
$objCombos->sAccion = 'paginarf3816()';
$objCombos->addArreglo($acipa02forma, $icipa02forma);
//$objCombos->addArreglo($acipa02forma, $icipa02forma);
$html_cipa02forma = $objCombos->html('', $objDB);
$objCombos->nuevo('cipa01alcance', $_REQUEST['cipa01alcance'], true, '{' . $ETI['msg_todos'] . '}');
$objCombos->sAccion = 'paginarf3816()';
$sSQL = 'SELECT cipa13id AS id, cipa13nombre AS nombre FROM cipa13alcance ORDER BY cipa13nombre';
$html_cipa01alcance = $objCombos->html($sSQL, $objDB);
$objCombos->nuevo('cipa01clase', $_REQUEST['cipa01clase'], true, '{' . $ETI['msg_todos'] . '}');
$objCombos->sAccion = 'paginarf3816()';
$sSQL = 'SELECT cipa14id AS id, cipa14nombre AS nombre FROM cipa14clasecipas ORDER BY cipa14nombre';
$html_cipa01clase = $objCombos->html($sSQL, $objDB);
//Alistar datos adicionales
$id_rpt = 0;
//$id_rpt=reportes_id(_Identificador_Tipo_Reporte_, $objDB);
/*
$objCombos->nuevo('blistar', $_REQUEST['blistar'], true, '{' . $ETI['msg_todos'] . '}');
$objCombos->sAccion = 'paginarf3816()';
$sSQL = '';
$html_blistar = $objCombos->html($sSQL, $objDB);
//$html_blistar = $objCombos->comboSistema(3816, 1, $objDB, 'paginarf3816()');
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
$iModeloReporte = 3816;
$html_iFormatoImprime = '<input id="iformatoimprime" name="iformatoimprime" type="hidden" value="0" />
';
if ($_REQUEST['paso'] > 0) {
	$bDevuelve = false;
	//list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 5, $idTercero, $objDB);
	if ($bDevuelve) {
		$seg_5 = 1;
	}
}
//Cargar las tablas de datos
$aParametros[0] = ''; //$_REQUEST['p1_3816'];
$aParametros[100] = $idTercero;
$aParametros[101] = $_REQUEST['paginaf3816'];
$aParametros[102] = $_REQUEST['lppf3816'];
$aParametros[103] = $_REQUEST['cipa01periodo'];
$aParametros[104] = $_REQUEST['cipa01alcance'];
$aParametros[105] = $_REQUEST['cipa01clase'];
$aParametros[106] = $_REQUEST['cipa01estado'];
$aParametros[107] = $_REQUEST['cipa01escuela'];
$aParametros[108] = $_REQUEST['cipa01programa'];
$aParametros[109] = $_REQUEST['cipa01zona'];
$aParametros[110] = $_REQUEST['cipa01centro'];
$aParametros[111] = $_REQUEST['cipa01idcurso'];
$aParametros[112] = $_REQUEST['cipa02forma'];
//$aParametros[113] = $_REQUEST['cipa02fecha'];
$aParametros[113] = $_REQUEST['cipa02fechaini'];
$aParametros[114] = $_REQUEST['cipa02fechafin'];
$aParametros[115] = $_REQUEST['cipa01est_proyectados'];
$aParametros[116] = $_REQUEST['cipa01est_asistentes'];
list($sTabla3816, $sDebugTabla) = f3816_TablaDetalleV2($aParametros, $objDB, $bDebug);
$sDebug = $sDebug . $sDebugTabla;
$bDebugMenu = false;
list($et_menu, $sDebugM) = html_menuV2($APP->idsistema, $objDB, $iPiel, $bDebugMenu, $idTercero);
$sDebug = $sDebug . $sDebugM;
$objDB->CerrarConexion();
//FORMA
require $APP->rutacomun . 'unad_forma_v2.php';
forma_cabeceraV3($xajax, $ETI['titulo_3816']);
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
		document.getElementById('div_sector95').style.display = 'none';
		document.getElementById('div_sector96').style.display = 'none';
		document.getElementById('div_sector98').style.display = 'none';
		document.getElementById('div_sector' + codigo).style.display = 'block';
	}

	function imprimelista() {
		if (window.document.frmedita.seg_6.value == 1) {
			window.document.frmlista.consulta.value = window.document.frmedita.consulta_3816.value;
			window.document.frmlista.titulos.value = window.document.frmedita.titulos_3816.value;
			window.document.frmlista.nombrearchivo.value = 'Reporte de jornadas CIPAS';
			window.document.frmlista.submit();
		} else {
			ModalMensaje("<?php echo $ERR['6']; ?>");
		}
	}

	function asignarvariables() {
		window.document.frmimpp.v3.value = window.document.frmedita.cipa01periodo.value;
		window.document.frmimpp.v4.value = window.document.frmedita.cipa01alcance.value;
		window.document.frmimpp.v5.value = window.document.frmedita.cipa01clase.value;
		window.document.frmimpp.v6.value = window.document.frmedita.cipa01estado.value;
		window.document.frmimpp.v7.value = window.document.frmedita.cipa01escuela.value;
		window.document.frmimpp.v8.value = window.document.frmedita.cipa01programa.value;
		window.document.frmimpp.v9.value = window.document.frmedita.cipa01zona.value;
		window.document.frmimpp.v10.value = window.document.frmedita.cipa01centro.value;
		window.document.frmimpp.v11.value = window.document.frmedita.cipa01idcurso.value;
		window.document.frmimpp.v12.value = window.document.frmedita.cipa02forma.value;
		//window.document.frmimpp.v13.value = window.document.frmedita.cipa02fecha.value;
		window.document.frmimpp.v13.value = window.document.frmedita.cipa02fechaini.value;
		window.document.frmimpp.v14.value = window.document.frmedita.cipa02fechafin.value;
		//window.document.frmimpp.v15.value = window.document.frmedita.cipa01est_proyectados.value;
		//window.document.frmimpp.v16.value = window.document.frmedita.cipa01est_asistentes.value;
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
			window.document.frmimpp.action = 'e3816_ss.php';
			window.document.frmimpp.submit();
		} else {
			ModalMensaje(sError);
		}
	}

	function imprimep() {
		if (window.document.frmedita.seg_5.value == 1) {
			asignarvariables();
			window.document.frmimpp.action = 'p3816.php';
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

	function carga_combo_cipa01programa() {
		let params = new Array();
		params[0] = window.document.frmedita.cipa01escuela.value;
		document.getElementById('div_cipa01programa').innerHTML = '<b>Procesando datos, por favor espere...</b><input id="cipa01programa" name="cipa01programa" type="hidden" value="" />';
		xajax_f3816_Combocipa01programa(params);
	}

	function carga_combo_cipa01centro() {
		let params = new Array();
		params[0] = window.document.frmedita.cipa01zona.value;
		document.getElementById('div_cipa01centro').innerHTML = '<b>Procesando datos, por favor espere...</b><input id="cipa01centro" name="cipa01centro" type="hidden" value="" />';
		xajax_f3816_Combocipa01centro(params);
	}

	function carga_combo_cipa01idcurso() {
		let params = new Array();
		params[0] = window.document.frmedita.cipa01periodo.value;
		document.getElementById('div_cipa01idcurso').innerHTML = '<b>Procesando datos, por favor espere...</b><input id="cipa01idcurso" name="cipa01idcurso" type="hidden" value="" />';
		xajax_f3816_Combocipa01idcurso(params);
	}

	function paginarf3816() {
		let params = new Array();
		params[99] = window.document.frmedita.debug.value;
		params[100] = <?php echo $idTercero; ?>;
		params[101] = window.document.frmedita.paginaf3816.value;
		params[102] = window.document.frmedita.lppf3816.value;
		params[103] = window.document.frmedita.cipa01periodo.value;
		params[104] = window.document.frmedita.cipa01alcance.value;
		params[105] = window.document.frmedita.cipa01clase.value;
		params[106] = window.document.frmedita.cipa01estado.value;
		params[107] = window.document.frmedita.cipa01escuela.value;
		params[108] = window.document.frmedita.cipa01programa.value;
		params[109] = window.document.frmedita.cipa01zona.value;
		params[110] = window.document.frmedita.cipa01centro.value;
		params[111] = window.document.frmedita.cipa01idcurso.value;
		params[112] = window.document.frmedita.cipa02forma.value;
		//params[113] = window.document.frmedita.cipa02fecha.value;
		params[113] = window.document.frmedita.cipa02fechaini.value;
		params[114] = window.document.frmedita.cipa02fechafin.value;
		//document.getElementById('div_f3816detalle').innerHTML = '<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf3816" name="paginaf3816" type="hidden" value="' + params[101] + '" /><input id="lppf3816" name="lppf3816" type="hidden" value="' + params[102] + '" />';
		xajax_f3816_HtmlTabla(params);
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

	function retornacontrol() {
		expandesector(1);
		window.scrollTo(0, window.document.frmedita.iscroll.value);
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
		MensajeAlarmaV2('', 0);
		retornacontrol();
	}
</script>
<form id="frmimpp" name="frmimpp" method="post" action="p3816.php" target="_blank">
<input id="r" name="r" type="hidden" value="3816" />
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
<div id="div_sector1">
<div class="titulos">
<div class="titulosD">
<input id="cmdAyuda" name="cmdAyuda" type="button" class="btUpAyuda" onclick="muestraayuda(<?php echo $APP->idsistema . ', ' . $iCodModulo; ?>);" title="<?php echo $ETI['bt_ayuda']; ?>" value="<?php echo $ETI['bt_ayuda']; ?>" />
<?php
$bHayImprimir = false;
$sScript = 'imprimeexcel()';
$sClaseBoton = 'btEnviarExcel';
if ($seg_6 == 1) {
	$bHayImprimir = true;
}
if ($bHayImprimir) {
?>
<input id="cmdImprimir" name="cmdImprimir" type="button" class="<?php echo $sClaseBoton; ?>" onclick="<?php echo $sScript; ?>" title="<?php echo $ETI['bt_imprimir']; ?>" value="<?php echo $ETI['bt_imprimir']; ?>" />
<?php
}
$bHayImprimir2 = false;
$sScript = 'imprimep()';
$sClaseBoton = 'btEnviarPDF'; //btUpPrint
if ($seg_5 == 1) {
	$bHayImprimir2 = true;
}
if ($bHayImprimir2) {
?>
<input id="cmdImprimir2" name="cmdImprimir2" type="button" class="<?php echo $sClaseBoton; ?>" onclick="<?php echo $sScript; ?>" title="<?php echo $ETI['bt_imprimir']; ?>" value="<?php echo $ETI['bt_imprimir']; ?>" />
<?php
}
?>
<input id="cmdLimpiar" name="cmdLimpiar" type="button" class="btUpLimpiar" onclick="limpiapagina();" title="<?php echo $ETI['bt_limpiar']; ?>" value="<?php echo $ETI['bt_limpiar']; ?>" />
<?php
?>
</div>
<div class="titulosI">
<?php
echo '<h2>' . $ETI['titulo_3816'] . '</h2>';
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
if ($_REQUEST['boculta3816'] != 0) {
	$sEstiloExpande = '';
	$sEstiloRecoje = ' style="display:none;"';
	$sEstiloDiv = ' style="display:none;"';
}
?>
<input id="boculta3816" name="boculta3816" type="hidden" value="<?php echo $_REQUEST['boculta3816']; ?>" />
<label class="Label30">
<input id="btexpande3816" name="btexpande3816" type="button" value="Mostrar" class="btMiniExpandir" onclick="expandepanel(3816, 'block', 0);" title="<?php echo $ETI['bt_mostrar']; ?>"<?php echo $sEstiloExpande; ?>/>
</label>
<label class="Label30">
<input id="btrecoge3816" name="btrecoge3816" type="button" value="Ocultar" class="btMiniRecoger" onclick="expandepanel(3816, 'none', 1);" title="<?php echo $ETI['bt_ocultar']; ?>" <?php echo $sEstiloRecoje; ?>/>
</label>
</div>
<div id="div_p3816"<?php echo $sEstiloDiv; ?>>
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
<label class="Label130">
<?php
echo $ETI['cipa01alcance'];
?>
</label>
<label class="Label200">
<?php
echo $html_cipa01alcance;
?>
</label>
<label class="Label90">
<?php
echo $ETI['cipa01clase'];
?>
</label>
<label class="Label380">
<?php
echo $html_cipa01clase;
?>
</label>
<label class="Label90">
<?php
echo $ETI['cipa01estado'];
?>
</label>
<label class="Label200">
<?php
echo $html_cipa01estado;
?>
</label>

<div class="salto1px"></div>
<label class="Label130">
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
<label class="Label130">
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
<label class="Label130">
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
<label class="Label130">
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
<div class="salto1px"></div>
<label class="Label130">
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
<label class="Label130">
<?php
echo $ETI['cipa02fechaini'];
?>
</label>
<div class="Campo220">
<?php
echo html_FechaEnNumero('cipa02fechaini', $_REQUEST['cipa02fechaini'], true, 'paginarf3816()', $iAgnoIni, $iAgnoFin); //$bvacio, $accion
?>
</div>
<?php
if (false) {
?>
<label class="Label30">
<input id="bcipa02fechaini_hoy" name="bcipa02fechaini_hoy" type="button" value="Hoy" class="btMiniHoy" onclick="fecha_AsignarNum('cipa02fechaini', '<?php echo $iHoy; ?>')" title="<?php echo $ETI['bt_hoy']; ?>" />
</label>
<?php
}
?>
<label class="Label130">
<?php
echo $ETI['cipa02fechafin'];
?>
</label>
<div class="Campo220">
<?php
echo html_FechaEnNumero('cipa02fechafin', $_REQUEST['cipa02fechafin'], false, 'paginarf3816()', $iAgnoIni, $iAgnoFin); //$bvacio, $accion
?>
</div>
<?php
if (false) {
?>
<label class="Label30">
<input id="bcipa02fechafin_hoy" name="bcipa02fechafin_hoy" type="button" value="Hoy" class="btMiniHoy" onclick="fecha_AsignarNum('cipa02fechafin', '<?php echo $iHoy; ?>')" title="<?php echo $ETI['bt_hoy']; ?>" />
</label>
<?php
}
?>
<?php
if (false) {
	//Ejemplo de boton de ayuda
	//echo html_BotonAyuda('NombreCampo');
	//echo html_DivAyudaLocal('NombreCampo');
}
if ($bConExpande) {
	//Este es el cierre del div_p3816
?>
<div class="salto1px"></div>
</div>
<?php
}
//Mostrar el contenido de la tabla
?>
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
<input id="bnombre" name="bnombre" type="text" value="<?php echo $_REQUEST['bnombre']; ?>" onchange="paginarf3816()" autocomplete="off" />
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
<div id="div_f3816detalle">
<?php
echo $sTabla3816;
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
<input id="titulo_3816" name="titulo_3816" type="hidden" value="<?php echo $ETI['titulo_3816']; ?>" />
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


<div id="div_sector98" style="display:none">
<div class="titulos">
<div class="titulosD">
<input id="cmdAyuda98" name="cmdAyuda98" type="button" class="btSupAyuda" onclick="muestraayuda(<?php echo $iCodModulo; ?>);" title="<?php echo $ETI['bt_ayuda']; ?>" value="<?php echo $ETI['bt_ayuda']; ?>" />
</div>
<div class="titulosI">
<?php
echo '<h2>' . $ETI['titulo_3816'] . '</h2>';
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
<script language="javascript" src="<?php echo $APP->rutacomun; ?>unad_todas.js?ver=8"></script>
<?php
forma_piedepagina();

