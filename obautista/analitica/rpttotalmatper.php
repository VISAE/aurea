<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2025 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 3.0.14 miércoles, 5 de marzo de 2025
*/
/** Archivo rpttotalmatperv2.php.
 * Modulo 2809 core16actamatricula.
 * @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
 * @param debug = 1  (Opcional), bandera para indicar si se generan datos de depuración
 * @date miércoles, 5 de marzo de 2025
 */
error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
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
$iConsecutivoMenu = 1;
$iMinVerDB = 8439;
$iCodModulo = 2809;
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
$mensajes_2800 = 'lg/lg_2800_' . $_SESSION['unad_idioma'] . '.php';
if (!file_exists($mensajes_2800)) {
	$mensajes_2800 = 'lg/lg_2800_es.php';
}
require $mensajes_2800;
*/
$mensajes_2809 = 'lg/lg_2809_' . $_SESSION['unad_idioma'] . '.php';
if (!file_exists($mensajes_2809)) {
	$mensajes_2809 = 'lg/lg_2809_es.php';
}
$mensajes_2216 = $APP->rutacomun . 'lg/lg_2216_' . $_SESSION['unad_idioma'] . '.php';
if (!file_exists($mensajes_2216)) {
	$mensajes_2216 = $APP->rutacomun . 'lg/lg_2216_es.php';
}
require $mensajes_todas;
require $mensajes_2809;
require $mensajes_2216;
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
$sTituloModulo = $ETI['titulo_2809'];
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
		header('Location:noticia.php?ret=rpttotalmatper.php');
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
// -- 2809 core16actamatricula
require 'lib2809v2.php';
$xajax = new xajax();
$xajax->configure('javascript URI', $APP->rutacomun . 'xajax/');
$xajax->register(XAJAX_FUNCTION, 'f2809_Combocore16idciclo');
$xajax->register(XAJAX_FUNCTION, 'f2809_Combocore16idbloque');
$xajax->register(XAJAX_FUNCTION, 'f2809_Combocore16peraca');
$xajax->register(XAJAX_FUNCTION, 'f2809_Combocore16idsnies');
$xajax->register(XAJAX_FUNCTION, 'f2809_Combocore16idprograma');
$xajax->register(XAJAX_FUNCTION, 'f2809_Combocore16idplanestudio');
$xajax->register(XAJAX_FUNCTION, 'f2809_Combocore16idcead');
$xajax->register(XAJAX_FUNCTION, 'sesion_abandona_V2');
$xajax->register(XAJAX_FUNCTION, 'sesion_mantenerV4');
$xajax->register(XAJAX_FUNCTION, 'f2809_HtmlTabla');
$xajax->register(XAJAX_FUNCTION, 'f2809_TraerInfoPeriodo');
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
if (isset($_REQUEST['paginaf2809']) == 0) {
	$_REQUEST['paginaf2809'] = 1;
}
if (isset($_REQUEST['lppf2809']) == 0) {
	$_REQUEST['lppf2809'] = 20;
}
if (isset($_REQUEST['boculta2809']) == 0) {
	$_REQUEST['boculta2809'] = 0;
}
// -- Inicializar variables de datos.
$bTraerEntorno = false;
if (isset($_REQUEST['core16idagno']) == 0) {
	$_REQUEST['core16idagno'] = '';
	$bTraerEntorno = true;
}
if (isset($_REQUEST['core16idciclo']) == 0) {
	$_REQUEST['core16idciclo'] = '';
}
if (isset($_REQUEST['core16idbloque']) == 0) {
	$_REQUEST['core16idbloque'] = '';
}
if (isset($_REQUEST['core16peraca']) == 0) {
	$_REQUEST['core16peraca'] = '';
}
if (isset($_REQUEST['core16idescuela']) == 0) {
	$_REQUEST['core16idescuela'] = '';
}
if (isset($_REQUEST['core16nivelforma']) == 0) {
	$_REQUEST['core16nivelforma'] = '';
}
if (isset($_REQUEST['core16idsnies']) == 0) {
	$_REQUEST['core16idsnies'] = '';
}
if (isset($_REQUEST['core16idprograma']) == 0) {
	$_REQUEST['core16idprograma'] = '';
}
if (isset($_REQUEST['core16idplanestudio']) == 0) {
	$_REQUEST['core16idplanestudio'] = '';
}
if (isset($_REQUEST['core16idzona']) == 0) {
	$_REQUEST['core16idzona'] = '';
}
if (isset($_REQUEST['core16idcead']) == 0) {
	$_REQUEST['core16idcead'] = '';
}
if (isset($_REQUEST['core16nuevo']) == 0) {
	$_REQUEST['core16nuevo'] = '';
}
$_REQUEST['core16idagno'] = numeros_validar($_REQUEST['core16idagno']);
$_REQUEST['core16idciclo'] = numeros_validar($_REQUEST['core16idciclo']);
$_REQUEST['core16idbloque'] = numeros_validar($_REQUEST['core16idbloque']);
$_REQUEST['core16peraca'] = numeros_validar($_REQUEST['core16peraca']);
$_REQUEST['core16idescuela'] = numeros_validar($_REQUEST['core16idescuela']);
$_REQUEST['core16nivelforma'] = numeros_validar($_REQUEST['core16nivelforma']);
$_REQUEST['core16idsnies'] = numeros_validar($_REQUEST['core16idsnies']);
$_REQUEST['core16idprograma'] = numeros_validar($_REQUEST['core16idprograma']);
$_REQUEST['core16idplanestudio'] = numeros_validar($_REQUEST['core16idplanestudio']);
$_REQUEST['core16idzona'] = numeros_validar($_REQUEST['core16idzona']);
$_REQUEST['core16idcead'] = numeros_validar($_REQUEST['core16idcead']);
$_REQUEST['core16nuevo'] = numeros_validar($_REQUEST['core16nuevo']);
// Espacio para inicializar otras variables
if (isset($_REQUEST['csv_separa']) == 0) {
	$_REQUEST['csv_separa'] = ';';
}
if (isset($_REQUEST['bnombre']) == 0) {
	$_REQUEST['bnombre'] = '';
}
//Procesar la matricula del periodo
if ($_REQUEST['paso'] == 23) {
	$_REQUEST['paso'] = 0;
	if ($sError == '') {
		//la funcion esta en la libcore y la lib2216
		require $APP->rutacomun . 'libcore.php';
		require $APP->rutacomun . 'lib2216.php';
		set_time_limit(0);
		$idPeriodo = $_REQUEST['core16peraca'];
		$iTotal = 0;
		if (isset($_REQUEST['total']) == 0) {
			$_REQUEST['total'] = 0;
		}
		if ($_REQUEST['total'] == 1) {
			$iTotal = 1;
		}
		list($sError, $iProcesados, $sDebugM) = f2216_MatricularPeriodo($idPeriodo, $objDB, $bDebug, $iTotal);
		$sDebug = $sDebug . $sDebugM;
		if ($sError == '') {
			$sError = 'Se ha revisado la matricula, ' . formato_numero($iProcesados) . ' registros procesados.';
			$iTipoError = 1;
		}
	}
}
//limpiar la pantalla
if ($_REQUEST['paso'] == -1) {
	/*
	$_REQUEST['core16idagno'] = '';
	$_REQUEST['core16idciclo'] = '';
	$_REQUEST['core16idbloque'] = '';
	$_REQUEST['core16peraca'] = '';
	$_REQUEST['core16idescuela'] = '';
	$_REQUEST['core16nivelforma'] = '';
	$_REQUEST['core16idsnies'] = '';
	*/
	$_REQUEST['core16idprograma'] = '';
	$_REQUEST['core16idplanestudio'] = '';
	$_REQUEST['core16idzona'] = '';
	$_REQUEST['core16idcead'] = '';
	$_REQUEST['core16nuevo'] = '';
	$_REQUEST['paso'] = 0;
}
if ($bTraerEntorno) {
	$sSQL = 'SELECT * FROM unad95entorno WHERE unad95id=' . $idTercero . '';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		if ($fila['unad95periodo'] != 0) {
			$_REQUEST['core16peraca'] = $fila['unad95periodo'];
		}
		if ($fila['unad95escuela'] != 0) {
			$_REQUEST['core16idescuela'] = $fila['unad95escuela'];
		}
		if ($fila['unad95programa'] != 0) {
			$_REQUEST['core16idprograma'] = $fila['unad95programa'];
		}
		if ($fila['unad95zona'] != 0) {
			$_REQUEST['core16idzona'] = $fila['unad95zona'];
		}
		if ($fila['unad95centro'] != 0) {
			$_REQUEST['core16idcead'] = $fila['unad95centro'];
		}
	}
}
//AQUI SE DEBEN CARGAR TODOS LOS DATOS QUE LA FORMA NECESITE.
$bPuedeGuardar = false;
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
$sInfoPeriodo = '';
//Permisos adicionales
$seg_5 = 0;
$seg_6 = 0;
list($bHayImprimir, $sDebugP, $seg_6) = seg_revisa_permisoV3($iCodModulo, 6, $idTercero, $objDB);
//list($bHayImprimir2, $sDebugP, $seg_5) = seg_revisa_permisoV3($iCodModulo, 5, $idTercero, $objDB);
if ((int)$_REQUEST['paso'] != 0) {
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
$objCombos->nuevo('core16idagno', $_REQUEST['core16idagno'], true, '{' . $ETI['msg_seleccione'] . '}');
$objCombos->sAccion = 'carga_combo_core16idciclo();';
$sSQL = 'SELECT unad10codigo AS id, unad10codigo AS nombre FROM unad10vigencia WHERE unad10codigo>2017 AND unad10codigo<=' . ($iAgno + 1) . ' ORDER BY unad10codigo DESC';
$html_core16idagno = $objCombos->html($sSQL, $objDB);
$html_core16idciclo = f2809_HTMLComboV2_core16idciclo($objDB, $objCombos, $_REQUEST['core16idciclo'], $_REQUEST['core16idagno']);
$html_core16idbloque = f2809_HTMLComboV2_core16idbloque($objDB, $objCombos, $_REQUEST['core16idbloque'], $_REQUEST['core16idciclo']);
$html_core16peraca = f2809_HTMLComboV2_core16peraca($objDB, $objCombos, $_REQUEST['core16peraca'], $_REQUEST['core16idagno'], $_REQUEST['core16idciclo'], $_REQUEST['core16idbloque']);
$objCombos->nuevo('core16idescuela', $_REQUEST['core16idescuela'], true, '{' . $ETI['msg_todas'] . '}');
$objCombos->iAncho = 370;
$objCombos->addItem('DET', $ETI['msg_detallar']);
$objCombos->sAccion = 'carga_combo_core16idsnies();';
$sSQL = 'SELECT core12id AS id, core12nombre AS nombre FROM core12escuela WHERE core12tieneestudiantes="S" ORDER BY core12nombre';
$html_core16idescuela = $objCombos->html($sSQL, $objDB);
$objCombos->nuevo('core16nivelforma', $_REQUEST['core16nivelforma'], true, '{' . $ETI['msg_todos'] . '}');
$objCombos->sAccion = 'carga_combo_core16idsnies()';
$sSQL = 'SELECT core22id AS id, core22nombre AS nombre FROM core22nivelprograma ORDER BY core22orden';
$html_core16nivelforma = $objCombos->html($sSQL, $objDB);
$html_core16idsnies = f2809_HTMLComboV2_core16idsnies($objDB, $objCombos, $_REQUEST['core16idsnies'], $_REQUEST['core16idescuela'], $_REQUEST['core16nivelforma']);
$html_core16idprograma = f2809_HTMLComboV2_core16idprograma($objDB, $objCombos, $_REQUEST['core16idprograma'], $_REQUEST['core16idescuela'], $_REQUEST['core16nivelforma'], $_REQUEST['core16idsnies']);
$html_core16idplanestudio = f2809_HTMLComboV2_core16idplanestudio($objDB, $objCombos, $_REQUEST['core16idplanestudio'], $_REQUEST['core16idprograma']);
$objCombos->nuevo('core16idzona', $_REQUEST['core16idzona'], true, '{' . $ETI['msg_todas'] . '}');
$objCombos->addItem('DET', $ETI['msg_detallar']);
$objCombos->sAccion = 'carga_combo_core16idcead();';
$sSQL = 'SELECT unad23id AS id, unad23nombre AS nombre FROM unad23zona WHERE unad23conestudiantes="S" ORDER BY unad23nombre';
$html_core16idzona = $objCombos->html($sSQL, $objDB);
$html_core16idcead = f2809_HTMLComboV2_core16idcead($objDB, $objCombos, $_REQUEST['core16idcead'], $_REQUEST['core16idzona']);
$objCombos->nuevo('core16nuevo', $_REQUEST['core16nuevo'], true, '{' . $ETI['msg_todas'] . '}');
$objCombos->sAccion = 'paginarf2809();';
$objCombos->addItem('0', $acore16nuevo[0]);
$objCombos->addArreglo($acore16nuevo, 9);
//$objCombos->addItem('1', $ETI['msg_nuevo']);
$html_core16nuevo = $objCombos->html('', $objDB);
if ((int)$_REQUEST['paso'] == 0) {
} else {
}
//Alistar datos adicionales
if ((int)$_REQUEST['core16peraca'] != 0) {
	if ($idEntidad == 0) {
		list($sInfoPeriodo, $sDebugI) = f2809_InfoPeriodo($_REQUEST['core16peraca'], $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugI;
	}
}
$id_rpt = 0;
//$id_rpt=reportes_id(_Identificador_Tipo_Reporte_, $objDB);
/*
$objCombos->nuevo('blistar', $_REQUEST['blistar'], true, '{' . $ETI['msg_todos'] . '}');
$objCombos->sAccion = 'paginarf2809()';
$sSQL = '';
$html_blistar = $objCombos->html($sSQL, $objDB);
//$html_blistar = $objCombos->comboSistema(2809, 1, $objDB, 'paginarf2809()');
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
$iModeloReporte = 2809;
$html_iFormatoImprime = '<input id="iformatoimprime" name="iformatoimprime" type="hidden" value="0" />
';
//Cargar las tablas de datos
$aParametros[0] = ''; //$_REQUEST['p1_2809'];
$aParametros[100] = $idTercero;
$aParametros[101] = $_REQUEST['paginaf2809'];
$aParametros[102] = $_REQUEST['lppf2809'];
$aParametros[103] = $_REQUEST['core16idagno'];
$aParametros[104] = $_REQUEST['core16idciclo'];
$aParametros[105] = $_REQUEST['core16idbloque'];
$aParametros[106] = $_REQUEST['core16peraca'];
$aParametros[107] = $_REQUEST['core16idescuela'];
$aParametros[108] = $_REQUEST['core16nivelforma'];
$aParametros[109] = $_REQUEST['core16idsnies'];
$aParametros[110] = $_REQUEST['core16idprograma'];
$aParametros[111] = $_REQUEST['core16idplanestudio'];
$aParametros[112] = $_REQUEST['core16idzona'];
$aParametros[113] = $_REQUEST['core16idcead'];
$aParametros[114] = $_REQUEST['core16nuevo'];
list($sTabla2809, $sDebugTabla) = f2809_TablaDetalleV2($aParametros, $objDB, $bDebug);
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
			window.document.frmlista.consulta.value = window.document.frmedita.consulta_2809.value;
			window.document.frmlista.titulos.value = window.document.frmedita.titulos_2809.value;
			window.document.frmlista.nombrearchivo.value = 'Totales de matricula por periodo';
			window.document.frmlista.submit();
		} else {
			ModalMensaje("<?php echo $ERR['6']; ?>");
		}
	}

	function asignarvariables() {
		window.document.frmimpp.v3.value = window.document.frmedita.core16peraca.value;
		window.document.frmimpp.v9.value = window.document.frmedita.core16idescuela.value;
		window.document.frmimpp.v10.value = window.document.frmedita.core16idprograma.value;
		window.document.frmimpp.v11.value = window.document.frmedita.core16idplanestudio.value;
		window.document.frmimpp.v12.value = window.document.frmedita.core16idzona.value;
		window.document.frmimpp.v13.value = window.document.frmedita.core16idcead.value;
		window.document.frmimpp.v14.value = window.document.frmedita.core16nuevo.value;
		window.document.frmimpp.v15.value = window.document.frmedita.core16idagno.value;
		window.document.frmimpp.v16.value = window.document.frmedita.core16idciclo.value;
		window.document.frmimpp.v17.value = window.document.frmedita.core16idbloque.value;
		window.document.frmimpp.v18.value = window.document.frmedita.core16nivelforma.value;
		window.document.frmimpp.v19.value = window.document.frmedita.core16idsnies.value;
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
			window.document.frmimpp.action = 'e2809_ss.php';
			window.document.frmimpp.submit();
		} else {
			ModalMensaje(sError);
		}
	}

	function imprimep() {
		if (window.document.frmedita.seg_5.value == 1) {
			asignarvariables();
			window.document.frmimpp.action = 'p2809.php';
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

	function carga_combo_core16idciclo() {
		let params = new Array();
		params[0] = window.document.frmedita.core16idagno.value;
		params[1] = '';
		params[2] = '';
		document.getElementById('div_InfoPeriodo').innerHTML = '';
		document.getElementById('div_core16idciclo').innerHTML = '<b>Procesando datos, por favor espere...</b><input id="core16idciclo" name="core16idciclo" type="hidden" value="" />';
		document.getElementById('div_core16idbloque').innerHTML = '<b>Procesando datos, por favor espere...</b><input id="core16idbloque" name="core16idbloque" type="hidden" value="" />';
		document.getElementById('div_core16peraca').innerHTML = '<b>Procesando datos, por favor espere...</b><input id="core16peraca" name="core16peraca" type="hidden" value="" />';
		xajax_f2809_Combocore16idciclo(params);
	}

	function carga_combo_core16idbloque() {
		let params = new Array();
		params[0] = window.document.frmedita.core16idagno.value;
		params[1] = window.document.frmedita.core16idciclo.value;
		params[2] = window.document.frmedita.core16idbloque.value;
		document.getElementById('div_InfoPeriodo').innerHTML = '';
		document.getElementById('div_core16idbloque').innerHTML = '<b>Procesando datos, por favor espere...</b><input id="core16idbloque" name="core16idbloque" type="hidden" value="" />';
		document.getElementById('div_core16peraca').innerHTML = '<b>Procesando datos, por favor espere...</b><input id="core16peraca" name="core16peraca" type="hidden" value="" />';
		xajax_f2809_Combocore16idbloque(params);
	}

	function carga_combo_core16peraca() {
		let params = new Array();
		params[0] = window.document.frmedita.core16idagno.value;
		params[1] = window.document.frmedita.core16idciclo.value;
		params[2] = window.document.frmedita.core16idbloque.value;
		document.getElementById('div_InfoPeriodo').innerHTML = '';
		document.getElementById('div_core16peraca').innerHTML = '<b>Procesando datos, por favor espere...</b><input id="core16peraca" name="core16peraca" type="hidden" value="" />';
		xajax_f2809_Combocore16peraca(params);
	}

	function carga_combo_core16idsnies() {
		let params = new Array();
		params[0] = window.document.frmedita.core16idescuela.value;
		params[1] = window.document.frmedita.core16nivelforma.value;
		document.getElementById('div_core16idsnies').innerHTML = '<b>Procesando datos, por favor espere...</b><input id="core16idsnies" name="core16idsnies" type="hidden" value="" />';
		document.getElementById('div_core16idprograma').innerHTML = '<b>Procesando datos, por favor espere...</b><input id="core16idprograma" name="core16idprograma" type="hidden" value="" />';
		xajax_f2809_Combocore16idsnies(params);
	}

	function carga_combo_core16idprograma() {
		let params = new Array();
		params[0] = window.document.frmedita.core16idescuela.value;
		params[1] = window.document.frmedita.core16nivelforma.value;
		params[2] = window.document.frmedita.core16idsnies.value;
		document.getElementById('div_core16idprograma').innerHTML = '<b>Procesando datos, por favor espere...</b><input id="core16idprograma" name="core16idprograma" type="hidden" value="" />';
		xajax_f2809_Combocore16idprograma(params);
	}

	function carga_combo_core16idplanestudio() {
		let params = new Array();
		params[0] = window.document.frmedita.core16idprograma.value;
		document.getElementById('div_core16idplanestudio').innerHTML = '<b>Procesando datos, por favor espere...</b><input id="core16idplanestudio" name="core16idplanestudio" type="hidden" value="" />';
		xajax_f2809_Combocore16idplanestudio(params);
	}

	function carga_combo_core16idcead() {
		let params = new Array();
		params[0] = window.document.frmedita.core16idzona.value;
		document.getElementById('div_core16idcead').innerHTML = '<b>Procesando datos, por favor espere...</b><input id="core16idcead" name="core16idcead" type="hidden" value="" />';
		xajax_f2809_Combocore16idcead(params);
	}

	function paginarf2809() {
		let params = new Array();
		params[99] = window.document.frmedita.debug.value;
		params[100] = <?php echo $idTercero; ?>;
		params[101] = window.document.frmedita.paginaf2809.value;
		params[102] = window.document.frmedita.lppf2809.value;
		params[103] = window.document.frmedita.core16idagno.value;
		params[104] = window.document.frmedita.core16idciclo.value;
		params[105] = window.document.frmedita.core16idbloque.value;
		params[106] = window.document.frmedita.core16peraca.value;
		params[107] = window.document.frmedita.core16idescuela.value;
		params[108] = window.document.frmedita.core16nivelforma.value;
		params[109] = window.document.frmedita.core16idsnies.value;
		params[110] = window.document.frmedita.core16idprograma.value;
		params[111] = window.document.frmedita.core16idplanestudio.value;
		params[112] = window.document.frmedita.core16idzona.value;
		params[113] = window.document.frmedita.core16idcead.value;
		params[114] = window.document.frmedita.core16nuevo.value;
		document.getElementById('div_f2809detalle').innerHTML = '<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf2809" name="paginaf2809" type="hidden" value="' + params[101] + '" /><input id="lppf2809" name="lppf2809" type="hidden" value="' + params[102] + '" />';
		xajax_f2809_HtmlTabla(params);
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
		document.getElementById("core16idagno").focus();
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

	function traerinfoperiodo() {
		if (window.document.frmedita.core16peraca.value != '') {
			document.getElementById('div_InfoPeriodo').innerHTML = 'Actualizando...';
			var params = new Array();
			params[1] = window.document.frmedita.core16peraca.value;
			params[99] = window.document.frmedita.debug.value;
			xajax_f2809_TraerInfoPeriodo(params);
		} else {
			document.getElementById('div_InfoPeriodo').innerHTML = '';
		}
		paginarf2809();
	}

	function procesarmatricula(iObliga = 0) {
		if (confirm('Este proceso podra tomar bastante tiempo, desea continuar?')) {
			MensajeAlarmaV2('Procesando matricula...', 2);
			expandesector(98);
			window.document.frmedita.total.value = iObliga;
			window.document.frmedita.paso.value = 23;
			window.document.frmedita.submit();
		}
	}

	function imprimematricula(sEscuela, sPrograma, sZona, sCentro) {
		window.document.frmimpp.v3.value = '';
		window.document.frmimpp.v4.value = window.document.frmedita.core16nuevo.value;
		window.document.frmimpp.v5.value = '';
		window.document.frmimpp.v6.value = window.document.frmedita.core16peraca.value;
		window.document.frmimpp.v7.value = '';
		window.document.frmimpp.v8.value = ''; //window.document.frmedita.bconvenio.value;
		window.document.frmimpp.v9.value = sZona;
		window.document.frmimpp.v10.value = sCentro;
		window.document.frmimpp.v11.value = sEscuela;
		window.document.frmimpp.v12.value = sPrograma;
		window.document.frmimpp.v13.value = '';
		window.document.frmimpp.v14.value = '';

		window.document.frmimpp.v15.value = window.document.frmedita.core16idagno.value;
		window.document.frmimpp.v16.value = window.document.frmedita.core16idciclo.value;
		window.document.frmimpp.v17.value = window.document.frmedita.core16idbloque.value;
		window.document.frmimpp.v18.value = window.document.frmedita.core16nivelforma.value;
		window.document.frmimpp.v19.value = window.document.frmedita.core16idsnies.value;
		
		window.document.frmimpp.action = '<?php echo $APP->rutacomun; ?>e2216.php';
		window.document.frmimpp.submit();
	}
</script>
<form id="frmimpp" name="frmimpp" method="post" action="p2809.php" target="_blank" style="display:none">
<input id="r" name="r" type="hidden" value="2809" />
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
<input id="v16" name="v16" type="hidden" value="" />
<input id="v17" name="v17" type="hidden" value="" />
<input id="v18" name="v18" type="hidden" value="" />
<input id="v19" name="v19" type="hidden" value="" />

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
<input id="total" name="total" type="hidden" value="0" />
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
echo $objForma->htmlExpande(2809, $_REQUEST['boculta2809'], $ETI['bt_mostrar'], $ETI['bt_ocultar']);
$sEstiloDiv = '';
if ($_REQUEST['boculta2809'] != 0) {
	$sEstiloDiv = ' style="display:none;"';
}
?>
</div>
<div id="div_p2809"<?php echo $sEstiloDiv; ?>>
<?php
}
//Mostrar formulario para editar
?>
<div class="GrupoCampos520">
<label class="Label130">
<?php
echo $ETI['core16idagno'];
?>
</label>
<label>
<?php
echo $html_core16idagno;
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['core16idciclo'];
?>
</label>
<label>
<div id="div_core16idciclo">
<?php
echo $html_core16idciclo;
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['core16idbloque'];
?>
</label>
<label>
<div id="div_core16idbloque">
<?php
echo $html_core16idbloque;
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['core16peraca'];
?>
</label>
<label>
<div id="div_core16peraca">
<?php
echo $html_core16peraca;
?>
</div>
</label>
<div class="salto1px"></div>
<?php
echo '<div id="div_InfoPeriodo">' . $sInfoPeriodo . html_salto() . '</div>';
?>
<div class="salto1px"></div>
</div>

<div class="GrupoCampos520">
<label class="Label130">
<?php
echo $ETI['core16idescuela'];
?>
</label>
<label>
<?php
echo $html_core16idescuela;
?>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['core16nivelforma'];
?>
</label>
<label>
<?php
echo $html_core16nivelforma;
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['core16idsnies'];
?>
</label>
<label>
<div id="div_core16idsnies">
<?php
echo $html_core16idsnies;
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['core16idprograma'];
?>
</label>
<label>
<div id="div_core16idprograma">
<?php
echo $html_core16idprograma;
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['core16idplanestudio'];
?>
</label>
<label>
<div id="div_core16idplanestudio">
<?php
echo $html_core16idplanestudio;
?>
</div>
</label>
<div class="salto1px"></div>
</div>

<div class="GrupoCampos520">
<label class="Label130">
<?php
echo $ETI['core16idzona'];
?>
</label>
<label>
<?php
echo $html_core16idzona;
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['core16idcead'];
?>
</label>
<label>
<div id="div_core16idcead">
<?php
echo $html_core16idcead;
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['core16nuevo'];
?>
</label>
<label>
<div id="div_core16nuevo">
<?php
echo $html_core16nuevo;
?>
</div>
</label>
<div class="salto1px"></div>
</div>
<?php
if (false) {
	//Ejemplo de boton de ayuda
	//echo html_BotonAyuda('NombreCampo');
	//echo html_DivAyudaLocal('NombreCampo');
}
if ($bConExpande) {
	//Este es el cierre del div_p2809
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
<div id="div_f2809detalle">
<?php
echo $sTabla2809;
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
<input id="titulo_2809" name="titulo_2809" type="hidden" value="<?php echo $sTituloModulo; ?>" />
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
		$("#core16idagno").chosen();
		$("#core16idciclo").chosen();
		$("#core16idbloque").chosen();
		$("#core16peraca").chosen();
		$("#core16idescuela").chosen();
		$("#core16nivelforma").chosen();
		$("#core16idsnies").chosen();
		$("#core16idprograma").chosen();
		$("#core16idplanestudio").chosen();
		$("#core16idzona").chosen();
		$("#core16idcead").chosen();
	});
</script>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>unad_todas2024v2.js"></script>
<?php
forma_piedepagina();

