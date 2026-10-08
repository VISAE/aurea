<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2025 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 3.0.14 sábado, 22 de marzo de 2025
*/
/** Archivo plansegmeta.php.
 * Modulo 3655 plan44metamat.
 * @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
 * @param debug = 1  (Opcional), bandera para indicar si se generan datos de depuración
 * @date sábado, 22 de marzo de 2025
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
require 'libmetamattab.php';
if (($bPeticionXAJAX) && ($_SESSION['unad_id_tercero'] == 0)) {
	// viene por xajax.
	$xajax = new xajax();
	$xajax->configure('javascript URI', $APP->rutacomun . 'xajax/');
	$xajax->register(XAJAX_FUNCTION, 'sesion_abandona_V2');
	$xajax->processRequest();
	die();
}
$iConsecutivoMenu = 1;
$iMinVerDB = 8439;
$iCodModulo = 3655;
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
$mensajes_3600 = 'lg/lg_3600_' . $_SESSION['unad_idioma'] . '.php';
if (!file_exists($mensajes_3600)) {
	$mensajes_3600 = 'lg/lg_3600_es.php';
}
require $mensajes_3600;
*/
$mensajes_3655 = 'lg/lg_3644_' . $_SESSION['unad_idioma'] . '.php';
if (!file_exists($mensajes_3655)) {
	$mensajes_3655 = 'lg/lg_3644_es.php';
}
require $mensajes_todas;
require $mensajes_3655;
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
$sTituloModulo = $ETI['titulo_3655'];
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
	list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModuloConsulta, 1, $_SESSION['unad_id_tercero'], $objDB);
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
		echo $sDebug;
	}
	echo $objForma->htmlFinMarco();
	forma_piedepagina();
	die();
}
if (!$bPeticionXAJAX) {
	if (noticias_pendientes($objDB)) {
		$objDB->CerrarConexion();
		header('Location:noticia.php?ret=plansegmeta.php');
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
// -- 3655 plan44metamat
require 'lib3655.php';
$xajax = new xajax();
$xajax->configure('javascript URI', $APP->rutacomun . 'xajax/');
$xajax->register(XAJAX_FUNCTION, 'f3655_Comboplan44idciclo');
$xajax->register(XAJAX_FUNCTION, 'f3655_Comboplan44idbloque');
$xajax->register(XAJAX_FUNCTION, 'f3655_Comboplan44idprograma');
$xajax->register(XAJAX_FUNCTION, 'f3655_Comboplan44idcentro');
$xajax->register(XAJAX_FUNCTION, 'sesion_abandona_V2');
$xajax->register(XAJAX_FUNCTION, 'sesion_mantenerV4');
$xajax->register(XAJAX_FUNCTION, 'f3655_HtmlTabla');
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
if (isset($_REQUEST['paginaf3655']) == 0) {
	$_REQUEST['paginaf3655'] = 1;
}
if (isset($_REQUEST['lppf3655']) == 0) {
	$_REQUEST['lppf3655'] = 20;
}
if (isset($_REQUEST['boculta3655']) == 0) {
	$_REQUEST['boculta3655'] = 0;
}
// -- Inicializar variables de datos.
$bTraerEntorno = false;
if (isset($_REQUEST['plan44vigencia']) == 0) {
	$_REQUEST['plan44vigencia'] = '';
	$bTraerEntorno = true;
}
if (isset($_REQUEST['plan44idciclo']) == 0) {
	$_REQUEST['plan44idciclo'] = '';
}
if (isset($_REQUEST['plan44idbloque']) == 0) {
	$_REQUEST['plan44idbloque'] = '';
}
if (isset($_REQUEST['plan44unidadacad']) == 0) {
	$_REQUEST['plan44unidadacad'] = '';
}
if (isset($_REQUEST['plan44idescuela']) == 0) {
	$_REQUEST['plan44idescuela'] = '';
}
if (isset($_REQUEST['plan44idnivelforma']) == 0) {
	$_REQUEST['plan44idnivelforma'] = '';
}
if (isset($_REQUEST['plan44idprograma']) == 0) {
	$_REQUEST['plan44idprograma'] = '';
}
if (isset($_REQUEST['plan44idzona']) == 0) {
	$_REQUEST['plan44idzona'] = '';
}
if (isset($_REQUEST['plan44idcentro']) == 0) {
	$_REQUEST['plan44idcentro'] = '';
}
if (isset($_REQUEST['plan44tipodet']) == 0) {
	$_REQUEST['plan44tipodet'] = 0;
}
$_REQUEST['plan44vigencia'] = numeros_validar($_REQUEST['plan44vigencia']);
$_REQUEST['plan44idciclo'] = numeros_validar($_REQUEST['plan44idciclo']);
$_REQUEST['plan44idbloque'] = numeros_validar($_REQUEST['plan44idbloque']);
$_REQUEST['plan44unidadacad'] = numeros_validar($_REQUEST['plan44unidadacad']);
$_REQUEST['plan44idescuela'] = numeros_validar($_REQUEST['plan44idescuela']);
$_REQUEST['plan44idnivelforma'] = numeros_validar($_REQUEST['plan44idnivelforma']);
$_REQUEST['plan44idprograma'] = numeros_validar($_REQUEST['plan44idprograma']);
$_REQUEST['plan44idzona'] = numeros_validar($_REQUEST['plan44idzona']);
$_REQUEST['plan44idcentro'] = numeros_validar($_REQUEST['plan44idcentro']);
$_REQUEST['plan44tipodet'] = numeros_validar($_REQUEST['plan44tipodet']);
// Espacio para inicializar otras variables
if (isset($_REQUEST['csv_separa']) == 0) {
	$_REQUEST['csv_separa'] = ';';
}
if (isset($_REQUEST['bnombre']) == 0) {
	$_REQUEST['bnombre'] = '';
}
if ($_REQUEST['paso'] == 21) {
	$_REQUEST['paso'] = 0;
	list($sError, $sDebugT) = f3655_TotalizarVigencia($_REQUEST['plan44vigencia'], $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugT;
	if ($sError == '') {
		$sError = 'Proceso completo';
		$iTipoError = 1;
	}
}
//limpiar la pantalla
if ($_REQUEST['paso'] == -1) {
	$_REQUEST['plan44vigencia'] = '';
	$_REQUEST['plan44idciclo'] = '';
	$_REQUEST['plan44idbloque'] = '';
	$_REQUEST['plan44unidadacad'] = '';
	$_REQUEST['plan44idescuela'] = '';
	$_REQUEST['plan44idnivelforma'] = '';
	$_REQUEST['plan44idprograma'] = '';
	$_REQUEST['plan44idzona'] = '';
	$_REQUEST['plan44idcentro'] = '';
	$_REQUEST['paso'] = 0;
}
if ($bTraerEntorno) {
	$sSQL = 'SELECT * FROM unad95entorno WHERE unad95id=' . $idTercero . '';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		if ($fila['unad95escuela'] != 0) {
			$_REQUEST['plan44idescuela'] = $fila['unad95escuela'];
		}
		if ($fila['unad95programa'] != 0) {
			$_REQUEST['plan44idprograma'] = $fila['unad95programa'];
		}
		if ($fila['unad95zona'] != 0) {
			$_REQUEST['plan44idzona'] = $fila['unad95zona'];
		}
		if ($fila['unad95centro'] != 0) {
			$_REQUEST['plan44idcentro'] = $fila['unad95centro'];
		}
	}
}
//AQUI SE DEBEN CARGAR TODOS LOS DATOS QUE LA FORMA NECESITE.
$bPuedeGuardar = false;
$bConEliminar = false;
$bHayImprimir = false;
$bHayImprimir2 = false;
$bHayInfo = false;

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
list($bHayImprimir, $sDebugP, $seg_6) = seg_revisa_permisoV3($iCodModulo, 6, $idTercero, $objDB);
/*
//list($bHayImprimir2, $sDebugP, $seg_5) = seg_revisa_permisoV3($iCodModulo, 5, $idTercero, $objDB);
*/
if ((int)$_REQUEST['plan44vigencia'] != 0) {
	$bHayInfo = true;
}
//DATOS PARA COMPLETAR EL FORMULARIO
$iAgno = fecha_agno();
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
$html_plan44vigencia = f3655_HTMLComboV2_plan44vigencia($objDB, $objCombos, $_REQUEST['plan44vigencia']);
$html_plan44idciclo = f3655_HTMLComboV2_plan44idciclo($objDB, $objCombos, $_REQUEST['plan44idciclo'], $_REQUEST['plan44vigencia']);
$html_plan44idbloque = f3655_HTMLComboV2_plan44idbloque($objDB, $objCombos, $_REQUEST['plan44idbloque'], $_REQUEST['plan44idciclo']);
$html_plan44unidadacad = f3655_HTMLComboV2_plan44unidadacad($objDB, $objCombos, $_REQUEST['plan44unidadacad']);
$html_plan44idescuela = f3655_HTMLComboV2_plan44idescuela($objDB, $objCombos, $_REQUEST['plan44idescuela']);
$html_plan44idnivelforma = f3655_HTMLComboV2_plan44idnivelforma($objDB, $objCombos, $_REQUEST['plan44idnivelforma']);
$html_plan44idprograma = f3655_HTMLComboV2_plan44idprograma($objDB, $objCombos, $_REQUEST['plan44idprograma'], $_REQUEST['plan44idescuela'], $_REQUEST['plan44idnivelforma'], $_REQUEST['plan44unidadacad']);
$html_plan44idzona = f3655_HTMLComboV2_plan44idzona($objDB, $objCombos, $_REQUEST['plan44idzona']);
$html_plan44idcentro = f3655_HTMLComboV2_plan44idcentro($objDB, $objCombos, $_REQUEST['plan44idcentro'], $_REQUEST['plan44idzona']);
//Alistar datos adicionales
$id_rpt = 0;
//$id_rpt=reportes_id(_Identificador_Tipo_Reporte_, $objDB);
$objCombos->nuevo('plan44tipodet', $_REQUEST['plan44tipodet'], true, $aplan44tipodet[0]);
$objCombos->addArreglo($aplan44tipodet, $iplan44tipodet);
$objCombos->sAccion = 'paginarf3655()';
$sSQL = '';
$html_plan44tipodet = $objCombos->html($sSQL, $objDB);
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
if ($bHayInfo) {
	if ((int)$_REQUEST['plan44tipodet'] != 0) {
		$sTipoDetallado = $aplan44tipodet[$_REQUEST['plan44tipodet']];
	} else {
		$sTipoDetallado = $aplan44tipodet[0];
	}
	//Cargar las tablas de datos
	$aParametros[0] = ''; //$_REQUEST['p1_3655'];
	$aParametros[100] = $idTercero;
	$aParametros[101] = $_REQUEST['paginaf3655'];
	$aParametros[102] = $_REQUEST['lppf3655'];
	$aParametros[103] = $_REQUEST['plan44vigencia'];
	$aParametros[104] = $_REQUEST['plan44idciclo'];
	$aParametros[105] = $_REQUEST['plan44idbloque'];
	$aParametros[106] = $_REQUEST['plan44unidadacad'];
	$aParametros[107] = $_REQUEST['plan44idescuela'];
	$aParametros[108] = $_REQUEST['plan44idnivelforma'];
	$aParametros[109] = $_REQUEST['plan44idprograma'];
	$aParametros[110] = $_REQUEST['plan44idzona'];
	$aParametros[111] = $_REQUEST['plan44idcentro'];
	$aParametros[112] = $_REQUEST['plan44tipodet'];
	list($sTabla3655, $sDebugTabla, $iNumTerceros, $iNumTerceroSNIES, $iNumMatriculas) = f3655_TablaDetalleV2($aParametros, $objDB, $bDebug);
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
<link rel="stylesheet" href="./css/dashboard-metas.css">
<script language="javascript">
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
			window.document.frmlista.consulta.value = window.document.frmedita.consulta_3655.value;
			window.document.frmlista.titulos.value = window.document.frmedita.titulos_3655.value;
			window.document.frmlista.nombrearchivo.value = 'Tablero de seguimiento de metas';
			window.document.frmlista.submit();
		} else {
			ModalMensaje("<?php echo $ERR['6']; ?>");
		}
	}

	function asignarvariables() {
		window.document.frmimpp.v3.value = window.document.frmedita.plan44vigencia.value;
		window.document.frmimpp.v4.value = window.document.frmedita.plan44idciclo.value;
		window.document.frmimpp.v5.value = window.document.frmedita.plan44idbloque.value;
		window.document.frmimpp.v6.value = window.document.frmedita.plan44unidadacad.value;
		window.document.frmimpp.v7.value = window.document.frmedita.plan44idescuela.value;
		window.document.frmimpp.v8.value = window.document.frmedita.plan44idnivelforma.value;
		window.document.frmimpp.v9.value = window.document.frmedita.plan44idprograma.value;
		window.document.frmimpp.v10.value = window.document.frmedita.plan44idzona.value;
		window.document.frmimpp.v11.value = window.document.frmedita.plan44idcentro.value;
		window.document.frmimpp.v12.value = window.document.frmedita.plan44tipodet.value;
	}

	function imprimeexcel() {
		let sError = '';
		if (window.document.frmedita.seg_6.value != 1) {
			sError = "<?php echo $ERR['6']; ?>";
		}
		if (sError == '') {
			/*Agregar validaciones*/
			if (window.document.frmedita.plan44vigencia.value == 0) {
				sError = 'No se ha seleccionado una vigencia';
			}
		}
		if (sError == '') {
			asignarvariables();
			window.document.frmimpp.action = 'e3655_ss.php';
			window.document.frmimpp.submit();
		} else {
			ModalMensaje(sError);
		}
	}

	function imprimep() {
		if (window.document.frmedita.seg_5.value == 1) {
			asignarvariables();
			window.document.frmimpp.action = 'p3655.php';
			window.document.frmimpp.submit();
		} else {
			ModalMensaje("<?php echo $ERR['5']; ?>");
		}
	}
<?php
if ($bHayInfo) {
?>
	function carga_combo_plan44idciclo() {
		cambiapagina();
	}

	function carga_combo_plan44idbloque() {
		cambiapagina();
	}

	function carga_combo_plan44idprograma() {
		cambiapagina();
	}

	function carga_combo_plan44idcentro() {
		cambiapagina();
	}

	function paginarf3655() {
		cambiapagina();
	}
<?php
} else {
?>
	function carga_combo_plan44idciclo() {
		let params = new Array();
		params[0] = window.document.frmedita.plan44vigencia.value;
		params[1] = '';
		params[2] = '';
		document.getElementById('div_plan44idciclo').innerHTML = '<b>Procesando datos, por favor espere...</b><input id="plan44idciclo" name="plan44idciclo" type="hidden" value="" />';
		document.getElementById('div_plan44idbloque').innerHTML = '<b>Procesando datos, por favor espere...</b><input id="plan44idbloque" name="plan44idbloque" type="hidden" value="" />';
		xajax_f3655_Comboplan44idciclo(params);
	}

	function carga_combo_plan44idbloque() {
		let params = new Array();
		params[0] = window.document.frmedita.plan44idciclo.value;
		document.getElementById('div_plan44idbloque').innerHTML = '<b>Procesando datos, por favor espere...</b><input id="plan44idbloque" name="plan44idbloque" type="hidden" value="" />';
		xajax_f3655_Comboplan44idbloque(params);
	}

	function carga_combo_plan44idprograma() {
		let params = new Array();
		params[0] = window.document.frmedita.plan44idescuela.value;
		params[1] = window.document.frmedita.plan44idnivelforma.value;
		params[2] = window.document.frmedita.plan44unidadacad.value;
		document.getElementById('div_plan44idprograma').innerHTML = '<b>Procesando datos, por favor espere...</b><input id="plan44idprograma" name="plan44idprograma" type="hidden" value="" />';
		xajax_f3655_Comboplan44idprograma(params);
	}

	function carga_combo_plan44idcentro() {
		let params = new Array();
		params[0] = window.document.frmedita.plan44idzona.value;
		document.getElementById('div_plan44idcentro').innerHTML = '<b>Procesando datos, por favor espere...</b><input id="plan44idcentro" name="plan44idcentro" type="hidden" value="" />';
		xajax_f3655_Comboplan44idcentro(params);
	}

	function paginarf3655() {
		if (window.document.frmedita.plan44vigencia.value != '') {
			cambiapagina();
		}
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
		document.getElementById("plan44vigencia").focus();
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

	function procesar() {
		if (window.document.frmedita.plan44vigencia.value != '') {
			MensajeAlarmaV2('<?php echo $ETI['msg_ejecutando']; ?>', 2);
			expandesector(98);
			window.document.frmedita.paso.value = 21;
			window.document.frmedita.submit();
		} else {
			ModalMensaje('Debe seleccionar una vigencia.');
		}
	}

</script>
<form id="frmimpp" name="frmimpp" method="post" action="p3655.php" target="_blank" style="display:none">
<input id="r" name="r" type="hidden" value="3655" />
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
$bConExpande = false;
if ($bConExpande) {
?>
<div class="ir_derecha"<?php echo $sAnchoExpandeContrae; ?>>
<?php
echo $objForma->htmlExpande(3655, $_REQUEST['boculta3655'], $ETI['bt_mostrar'], $ETI['bt_ocultar']);
$sEstiloDiv = '';
if ($_REQUEST['boculta3655'] != 0) {
	$sEstiloDiv = ' style="display:none;"';
}
?>
</div>
<div id="div_p3655"<?php echo $sEstiloDiv; ?>>
<?php
}
//Mostrar formulario para editar
?>

<div class="dashboard">
<div class="dashboard__title">
<h1>Tablero de Seguimiento de Metas de Matr&iacute;cula</h1>
</div>

<div class="dashboard__filter">
<fieldset>
<legend>Lapso temporal</legend>

<div class="field">
<label class="Label130"><b>
<?php
echo $ETI['plan44vigencia'];
?>
</b></label>
<?php
echo $html_plan44vigencia;
if ($bHayInfo && $bDebug) {
	echo $objForma->htmlBotonSolo('btProcesaVig', 'btMiniActualizar', 'procesar()', 'Procesar datos de la vigencia');
}
?>
</div>
<div class="field">
<label class="Label130"><b>
<?php
echo $ETI['plan44idciclo'];
?>
</b></label>
<div id="div_plan44idciclo">
<?php
echo $html_plan44idciclo;
?>
</div>
</div>
<div class="field">
<label class="Label130"><b>
<?php
echo $ETI['plan44idbloque'];
?>
</b></label>
<div id="div_plan44idbloque">
<?php
echo $html_plan44idbloque;
?>
</div>
</div>
</fieldset>


<fieldset>
<legend>Acad&eacute;mia</legend>
<div class="field">
<label class="Label130"><b>
<?php
echo $ETI['plan44unidadacad'];
?>
</b></label>
<?php
echo $html_plan44unidadacad;
?>
</div>
<div class="field">
<label class="Label130"><b>
<?php
echo $ETI['plan44idescuela'];
?>
</b></label>
<?php
echo $html_plan44idescuela;
?>
</div>
<div class="field">
<label class="Label130"><b>
<?php
echo $ETI['plan44idnivelforma'];
?>
</b></label>
<?php
echo $html_plan44idnivelforma;
?>
</div>
<div class="field">
<label class="Label130"><b>
<?php
echo $ETI['plan44idprograma'];
?>
</b></label>
<div id="div_plan44idprograma">
<?php
echo $html_plan44idprograma;
?>
</div>
</div>
</fieldset>

<fieldset>
<legend>Geogr&aacute;fico</legend>
<div class="field">
<label class="Label130"><b>
<?php
echo $ETI['plan44idzona'];
?>
</b></label>
<?php
echo $html_plan44idzona;
?>
</div>
<div class="field">
<label class="Label130"><b>
<?php
echo $ETI['plan44idcentro'];
?>
</b></label>
<div id="div_plan44idcentro">
<?php
echo $html_plan44idcentro;
?>
</div>
</div>
<div class="field">
<label class="Label130"><b>
<?php
echo $ETI['plan44tipodet'];
?>
</b></label>
<?php
echo $html_plan44tipodet;
?>
</div>
</fieldset>

</div>

<?php
if ($bHayInfo) {
?>				
<div class="dashboard__datos">

<div class="dashboard__dato">
<div class="container--lower">
<h2>Estudiantes</h2>
</div>
<div class="container--lower">
<strong><?php echo formato_numero($iNumTerceros); ?></strong>
<p>N&deg; Total de  Estudiantes</p>
</div>
</div>


<div class="dashboard__dato">
<div class="container--lower">
<h2>Estudiantes - SNIES</h2>
</div>
<div class="container--lower">
<strong><?php echo formato_numero($iNumTerceroSNIES); ?></strong>
<p>N&deg; Total de  Estudiantes - SNIES</p>
</div>
</div>

<div class="dashboard__dato">
<div class="container--lower">
<h2>Eventos de  Matricula</h2>
</div>
<div class="container--lower">
<strong><?php echo formato_numero($iNumMatriculas); ?></strong>
<p>Total Matriculas</p>
</div>
</div>
</div>

<div class="dashboard__title">
<h2><?php echo $sTipoDetallado; ?></h2>
<p>Las columnas de Concertado y Ejecutados reflejan cantidad de Cr&eacute;ditos acad&eacute;micos.</p>
</div>

<div class="dashboard__table">
<div id="div_f3655detalle">
<?php
echo $sTabla3655;
?>
</div>
</div>

<?php
if (false) {
?>
<div class="dashboard__charts">
<div class="dashboard__bar">
<div class="overflow-x-auto">
<div>
<canvas id="bar-1"></canvas>
</div>
</div>
</div>
</div>

<div class="dashboard__charts">
<div class="dashboard__pie">
<canvas id="pie-1"></canvas>
</div>
<div class="dashboard__pie">
<canvas id="pie-2"></canvas>
</div>
</div>
<?php
}
?>

<?php
} else {
	echo '<div class=""dashboard__title"">' . html_Alerta('No se ha seleccionado una vigencia') . '</div>';
}
?>				

</div>


<?php
if (false) {
	//Ejemplo de boton de ayuda
	//echo html_BotonAyuda('NombreCampo');
	//echo html_DivAyudaLocal('NombreCampo');
}
if ($bConExpande) {
	//Este es el cierre del div_p3655
?>
<div class="salto1px"></div>
</div>
<?php
}
//Mostrar el contenido de la tabla
?>
<?php
echo ' ' . $csv_separa;
?>
<div class="salto1px"></div>
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
<input id="titulo_3655" name="titulo_3655" type="hidden" value="<?php echo $sTituloModulo; ?>" />
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
<script language="javascript">
	$().ready(function() {
		$("#plan44vigencia").chosen({width:"100%"});
		$("#plan44idciclo").chosen({width:"100%"});
		$("#plan44idbloque").chosen({width:"100%"});
		$("#plan44idnivelforma").chosen({width:"100%"});
		$("#plan44idprograma").chosen({width:"100%"});
		$("#plan44idzona").chosen({width:"100%"});
		$("#plan44idcentro").chosen({width:"100%"});
		$("#plan44idescuela").chosen({width:"100%"});
	});
</script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>unad_todas2024v2.js"></script>
    <script src="./js/graficas-metas.js"></script>
<?php
forma_piedepagina();

