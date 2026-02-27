<?php
/*
--- © Omar Augusto Bautista - UNAD - 2026 ---
--- omar.bautista@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 3.1.5 viernes, 27 de febrero de 2026
*/
/** Archivo visaeconvocatoria.php.
 * Modulo 2935 visa35convocatoria.
 * @author Omar Augusto Bautista - omar.bautista@unad.edu.co
 * @param debug = 1  (Opcional), bandera para indicar si se generan datos de depuración
 * @date viernes, 27 de febrero de 2026
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
//require $APP->rutacomun . 'libmail.php';
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
$iMinVerDB = 9359;
$iCodModulo = 2935;
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
/*
$mensajes_2900 = 'lg/lg_2900_' . $sIdioma . '.php';
if (!file_exists($mensajes_2900)) {
	$mensajes_2900 = 'lg/lg_2900_es.php';
}
require $mensajes_2900;
*/
$mensajes_2935 = 'lg/lg_2935_' . $sIdioma . '.php';
if (!file_exists($mensajes_2935)) {
	$mensajes_2935 = 'lg/lg_2935_es.php';
}
require $mensajes_todas;
require $mensajes_2935;
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
$sTituloModulo = $ETI['titulo_2935'];
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
		header('Location:noticia.php?ret=visaeconvocatoria.php');
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
// -- 2935 visa35convocatoria
require 'lib2935.php';
$xajax = new xajax();
$xajax->configure('javascript URI', $APP->rutacomun . 'xajax/');
$xajax->register(XAJAX_FUNCTION, 'f2935_Combovisa35idcentro');
$xajax->register(XAJAX_FUNCTION, 'f2935_Combovisa35idprograma');
$xajax->register(XAJAX_FUNCTION, 'f2935_Combovisa35nivelforma');
$xajax->register(XAJAX_FUNCTION, 'sesion_abandona_V2');
$xajax->register(XAJAX_FUNCTION, 'sesion_mantenerV4');
$xajax->register(XAJAX_FUNCTION, 'f2935_HtmlTabla');
$xajax->register(XAJAX_FUNCTION, 'f2935_ExisteDato');
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
if (isset($_REQUEST['paginaf2935']) == 0) {
	$_REQUEST['paginaf2935'] = 1;
}
if (isset($_REQUEST['lppf2935']) == 0) {
	$_REQUEST['lppf2935'] = 20;
}
if (isset($_REQUEST['boculta2935']) == 0) {
	$_REQUEST['boculta2935'] = 0;
}
// -- Inicializar variables de datos.
if (isset($_REQUEST['visa35consec']) == 0) {
	$_REQUEST['visa35consec'] = '';
}
if (isset($_REQUEST['visa35consec_nuevo']) == 0) {
	$_REQUEST['visa35consec_nuevo'] = '';
}
if (isset($_REQUEST['visa35id']) == 0) {
	$_REQUEST['visa35id'] = '';
}
if (isset($_REQUEST['visa35idtipo']) == 0) {
	$_REQUEST['visa35idtipo'] = '';
}
if (isset($_REQUEST['visa35nombre']) == 0) {
	$_REQUEST['visa35nombre'] = '';
}
if (isset($_REQUEST['visa35idzona']) == 0) {
	$_REQUEST['visa35idzona'] = '';
}
if (isset($_REQUEST['visa35idcentro']) == 0) {
	$_REQUEST['visa35idcentro'] = '';
}
if (isset($_REQUEST['visa35idescuela']) == 0) {
	$_REQUEST['visa35idescuela'] = '';
}
if (isset($_REQUEST['visa35idprograma']) == 0) {
	$_REQUEST['visa35idprograma'] = '';
}
if (isset($_REQUEST['visa35gruponivel']) == 0) {
	$_REQUEST['visa35gruponivel'] = 0;
}
if (isset($_REQUEST['visa35nivelforma']) == 0) {
	$_REQUEST['visa35nivelforma'] = '';
}
if (isset($_REQUEST['visa35estado']) == 0) {
	$_REQUEST['visa35estado'] = 0;
}
if (isset($_REQUEST['visa35numcupos']) == 0) {
	$_REQUEST['visa35numcupos'] = '';
}
if (isset($_REQUEST['visa35fecha_apertura']) == 0) {
	$_REQUEST['visa35fecha_apertura'] = '';
	//$_REQUEST['visa35fecha_apertura'] = $iHoy;
}
if (isset($_REQUEST['visa35fecha_liminscrip']) == 0) {
	$_REQUEST['visa35fecha_liminscrip'] = '';
	//$_REQUEST['visa35fecha_liminscrip'] = $iHoy;
}
if (isset($_REQUEST['visa35fecha_limrevdoc']) == 0) {
	$_REQUEST['visa35fecha_limrevdoc'] = '';
	//$_REQUEST['visa35fecha_limrevdoc'] = $iHoy;
}
if (isset($_REQUEST['visa35fecha_examenes']) == 0) {
	$_REQUEST['visa35fecha_examenes'] = '';
	//$_REQUEST['visa35fecha_examenes'] = $iHoy;
}
if (isset($_REQUEST['visa35fecha_seleccion']) == 0) {
	$_REQUEST['visa35fecha_seleccion'] = '';
	//$_REQUEST['visa35fecha_seleccion'] = $iHoy;
}
if (isset($_REQUEST['visa35fecha_ratificacion']) == 0) {
	$_REQUEST['visa35fecha_ratificacion'] = '';
	//$_REQUEST['visa35fecha_ratificacion'] = $iHoy;
}
if (isset($_REQUEST['visa35fecha_cierra']) == 0) {
	$_REQUEST['visa35fecha_cierra'] = '';
	//$_REQUEST['visa35fecha_cierra'] = $iHoy;
}
if (isset($_REQUEST['visa35presentacion']) == 0) {
	$_REQUEST['visa35presentacion'] = '';
}
if (isset($_REQUEST['visa35total_inscritos']) == 0) {
	$_REQUEST['visa35total_inscritos'] = '';
}
if (isset($_REQUEST['visa35total_autorizados']) == 0) {
	$_REQUEST['visa35total_autorizados'] = '';
}
if (isset($_REQUEST['visa35total_presentaex']) == 0) {
	$_REQUEST['visa35total_presentaex'] = '';
}
if (isset($_REQUEST['visa35total_aprobados']) == 0) {
	$_REQUEST['visa35total_aprobados'] = '';
}
if (isset($_REQUEST['visa35total_admitidos']) == 0) {
	$_REQUEST['visa35total_admitidos'] = '';
}
if (isset($_REQUEST['visa35idconvenio']) == 0) {
	$_REQUEST['visa35idconvenio'] = '';
}
if (isset($_REQUEST['visa35idresolucion']) == 0) {
	$_REQUEST['visa35idresolucion'] = '';
}
if (isset($_REQUEST['visa35idproducto']) == 0) {
	$_REQUEST['visa35idproducto'] = '';
}
$_REQUEST['visa35consec'] = numeros_validar($_REQUEST['visa35consec']);
$_REQUEST['visa35id'] = numeros_validar($_REQUEST['visa35id']);
$_REQUEST['visa35idtipo'] = numeros_validar($_REQUEST['visa35idtipo']);
$_REQUEST['visa35nombre'] = cadena_Validar($_REQUEST['visa35nombre']);
$_REQUEST['visa35idzona'] = numeros_validar($_REQUEST['visa35idzona']);
$_REQUEST['visa35idcentro'] = numeros_validar($_REQUEST['visa35idcentro']);
$_REQUEST['visa35idescuela'] = numeros_validar($_REQUEST['visa35idescuela']);
$_REQUEST['visa35idprograma'] = numeros_validar($_REQUEST['visa35idprograma']);
$_REQUEST['visa35gruponivel'] = numeros_validar($_REQUEST['visa35gruponivel']);
$_REQUEST['visa35nivelforma'] = numeros_validar($_REQUEST['visa35nivelforma']);
$_REQUEST['visa35estado'] = numeros_validar($_REQUEST['visa35estado']);
$_REQUEST['visa35numcupos'] = numeros_validar($_REQUEST['visa35numcupos']);
$_REQUEST['visa35fecha_apertura'] = numeros_validar($_REQUEST['visa35fecha_apertura']);
$_REQUEST['visa35fecha_liminscrip'] = numeros_validar($_REQUEST['visa35fecha_liminscrip']);
$_REQUEST['visa35fecha_limrevdoc'] = numeros_validar($_REQUEST['visa35fecha_limrevdoc']);
$_REQUEST['visa35fecha_examenes'] = numeros_validar($_REQUEST['visa35fecha_examenes']);
$_REQUEST['visa35fecha_seleccion'] = numeros_validar($_REQUEST['visa35fecha_seleccion']);
$_REQUEST['visa35fecha_ratificacion'] = numeros_validar($_REQUEST['visa35fecha_ratificacion']);
$_REQUEST['visa35fecha_cierra'] = numeros_validar($_REQUEST['visa35fecha_cierra']);
$_REQUEST['visa35presentacion'] = cadena_Validar($_REQUEST['visa35presentacion']);
$_REQUEST['visa35total_inscritos'] = numeros_validar($_REQUEST['visa35total_inscritos']);
$_REQUEST['visa35total_autorizados'] = numeros_validar($_REQUEST['visa35total_autorizados']);
$_REQUEST['visa35total_presentaex'] = numeros_validar($_REQUEST['visa35total_presentaex']);
$_REQUEST['visa35total_aprobados'] = numeros_validar($_REQUEST['visa35total_aprobados']);
$_REQUEST['visa35total_admitidos'] = numeros_validar($_REQUEST['visa35total_admitidos']);
$_REQUEST['visa35idconvenio'] = numeros_validar($_REQUEST['visa35idconvenio']);
$_REQUEST['visa35idresolucion'] = numeros_validar($_REQUEST['visa35idresolucion']);
$_REQUEST['visa35idproducto'] = numeros_validar($_REQUEST['visa35idproducto']);
// Espacio para inicializar otras variables
if (isset($_REQUEST['csv_separa']) == 0) {
	$_REQUEST['csv_separa'] = ';';
}
if (isset($_REQUEST['bnombre']) == 0) {
	$_REQUEST['bnombre'] = '';
}
if (isset($_REQUEST['bestado']) == 0) {
	$_REQUEST['bestado'] = '';
}
$_REQUEST['bnombre'] = cadena_Validar($_REQUEST['bnombre']);
$_REQUEST['bestado'] = numeros_validar($_REQUEST['bestado']);
//Si Modifica o Elimina Cargar los campos
if (($_REQUEST['paso'] == 1) || ($_REQUEST['paso'] == 3)) {
	if ($_REQUEST['paso'] == 1) {
		$sSQLcondi = 'visa35consec=' . $_REQUEST['visa35consec'] . '';
	} else {
		$sSQLcondi = 'visa35id=' . $_REQUEST['visa35id'] . '';
	}
	$sSQL = 'SELECT * FROM visa35convocatoria WHERE ' . $sSQLcondi;
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$_REQUEST['visa35consec'] = $fila['visa35consec'];
		$_REQUEST['visa35id'] = $fila['visa35id'];
		$_REQUEST['visa35idtipo'] = $fila['visa35idtipo'];
		$_REQUEST['visa35nombre'] = $fila['visa35nombre'];
		$_REQUEST['visa35idzona'] = $fila['visa35idzona'];
		$_REQUEST['visa35idcentro'] = $fila['visa35idcentro'];
		$_REQUEST['visa35idescuela'] = $fila['visa35idescuela'];
		$_REQUEST['visa35idprograma'] = $fila['visa35idprograma'];
		$_REQUEST['visa35gruponivel'] = $fila['visa35gruponivel'];
		$_REQUEST['visa35nivelforma'] = $fila['visa35nivelforma'];
		$_REQUEST['visa35estado'] = $fila['visa35estado'];
		$_REQUEST['visa35numcupos'] = $fila['visa35numcupos'];
		$_REQUEST['visa35fecha_apertura'] = $fila['visa35fecha_apertura'];
		$_REQUEST['visa35fecha_liminscrip'] = $fila['visa35fecha_liminscrip'];
		$_REQUEST['visa35fecha_limrevdoc'] = $fila['visa35fecha_limrevdoc'];
		$_REQUEST['visa35fecha_examenes'] = $fila['visa35fecha_examenes'];
		$_REQUEST['visa35fecha_seleccion'] = $fila['visa35fecha_seleccion'];
		$_REQUEST['visa35fecha_ratificacion'] = $fila['visa35fecha_ratificacion'];
		$_REQUEST['visa35fecha_cierra'] = $fila['visa35fecha_cierra'];
		$_REQUEST['visa35presentacion'] = $fila['visa35presentacion'];
		$_REQUEST['visa35total_inscritos'] = $fila['visa35total_inscritos'];
		$_REQUEST['visa35total_autorizados'] = $fila['visa35total_autorizados'];
		$_REQUEST['visa35total_presentaex'] = $fila['visa35total_presentaex'];
		$_REQUEST['visa35total_aprobados'] = $fila['visa35total_aprobados'];
		$_REQUEST['visa35total_admitidos'] = $fila['visa35total_admitidos'];
		$_REQUEST['visa35idconvenio'] = $fila['visa35idconvenio'];
		$_REQUEST['visa35idresolucion'] = $fila['visa35idresolucion'];
		$_REQUEST['visa35idproducto'] = $fila['visa35idproducto'];
		$bcargo = true;
		$_REQUEST['paso'] = 2;
		$_REQUEST['boculta2935'] = 0;
		$bLimpiaHijos = true;
	} else {
		$_REQUEST['paso'] = 0;
	}
}
//Cerrar
$bCambiaEstado = false;
if ($_REQUEST['paso'] == 16) {
	$_REQUEST['paso'] = 12;
	if ($sError == '') {
		$bCambiaEstado = true;
	} else {
		//Esto es opcional porque bloquea el guardado y el mensaje de error se pierde, considere recuperar el error.
		//$_REQUEST['paso'] = 2;
	}
}
//Abrir
if ($_REQUEST['paso'] == 17) {
	$_REQUEST['paso'] = 2;
	//Es posible que deba definir el codigo de permiso para abrir.
	if ($sError == '') {
		list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 17, $idTercero, $objDB);
		if (!$bDevuelve) {
			$sError = $ERR['3'] . ' [Mod ' . $iCodModulo . ']';
		}
	}
	//Otras restricciones para abrir.
	if ($sError == '') {
		//$sError = 'Motivo por el que no se pueda abrir, no se permite modificar.';
	}
	if ($sError == '') {
		list($sError, $sDebugE, $sMensaje) = f2935_CambiaEstado($_REQUEST['visa35id'], $_REQUEST['visa35estado'], 0, '', $_SESSION['unad_id_tercero'], $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugE;
		if ($sError == '') {
			$_REQUEST['visa35estado'] = 0;
			$sError = '<b>' . $ETI['msg_itemabierto'] . '</b>';
			$iTipoError = 1;
		}
	}
}
//Insertar o modificar un elemento
if (($_REQUEST['paso'] == 10) || ($_REQUEST['paso'] == 12)) {
	$bMueveScroll = true;
	list($_REQUEST, $sError, $iTipoError, $sDebugGuardar) = f2935_db_GuardarV2b($_REQUEST, $objDB, $bDebug, $idTercero);
	$sDebug = $sDebug . $sDebugGuardar;
	if ($sError == '') {
		if (!$bCambiaEstado) {
			$sError = '<b>' . $ETI['msg_itemguardado'] . '</b>';
			$iTipoError = 1;
		}
	} else {
		$bCambiaEstado = false;
	}
}
if ($bCambiaEstado) {
	//acciones del cerrado
	if ($sError == '') {
		list($sError, $sDebugE, $sMensaje) = f2935_CambiaEstado($_REQUEST['visa35id'], $_REQUEST['visa35estado'], 7, '', $_SESSION['unad_id_tercero'], $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugE;
		if ($sError == '') {
			$_REQUEST['visa35estado'] = 7;
			$sError = '<b>' . $ETI['msg_itemcerrado'] . '</b>';
			$iTipoError = 1;
		}
	}
}
// Cambio de consecutivo.
if ($_REQUEST['paso'] == 93) {
	$_REQUEST['paso'] = 2;
	$_REQUEST['visa35consec_nuevo'] = numeros_validar($_REQUEST['visa35consec_nuevo']);
	if ($_REQUEST['visa35consec_nuevo'] == '') {
		$sError = $ERR['visa35consec'];
	}
	if ($sError == '') {
		list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 8, $idTercero, $objDB);
		if (!$bDevuelve) {
			$sError = $ERR['8'] . ' [Mod ' . $iCodModulo . ']';
		}
	}
	if ($sError == '') {
		//Ver que el consecutivo no exista.
		$sSQL = 'SELECT visa35id FROM visa35convocatoria WHERE visa35consec=' . $_REQUEST['visa35consec_nuevo'] . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$sError = 'El consecutivo ' . $_REQUEST['visa35consec_nuevo'] . ' ya existe';
		}
	}
	if ($sError == '') {
		//Aplicar el cambio.
		$sSQL = 'UPDATE visa35convocatoria SET visa35consec=' . $_REQUEST['visa35consec_nuevo'] . ' WHERE visa35id=' . $_REQUEST['visa35id'] . '';
		$tabla = $objDB->ejecutasql($sSQL);
		$sDetalle = 'Cambia el consecutivo de ' . $_REQUEST['visa35consec'] . ' a ' . $_REQUEST['visa35consec_nuevo'] . '';
		$_REQUEST['visa35consec'] = $_REQUEST['visa35consec_nuevo'];
		$_REQUEST['visa35consec_nuevo'] = '';
		seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 8, $_REQUEST['visa35id'], $sDetalle, $objDB);
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
		list($sError, $iTipoError, $sDebugElimina) = f2935_db_Eliminar($_REQUEST['visa35id'], $objDB, $bDebug);
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
	$_REQUEST['visa35consec'] = '';
	$_REQUEST['visa35consec_nuevo'] = '';
	$_REQUEST['visa35id'] = '';
	$_REQUEST['visa35idtipo'] = '';
	$_REQUEST['visa35nombre'] = '';
	$_REQUEST['visa35idzona'] = '';
	$_REQUEST['visa35idcentro'] = '';
	$_REQUEST['visa35idescuela'] = '';
	$_REQUEST['visa35idprograma'] = '';
	$_REQUEST['visa35gruponivel'] = 1;
	$_REQUEST['visa35nivelforma'] = '';
	$_REQUEST['visa35estado'] = 0;
	$_REQUEST['visa35numcupos'] = '';
	$_REQUEST['visa35fecha_apertura'] = $iHoy;
	$_REQUEST['visa35fecha_liminscrip'] = $iHoy;
	$_REQUEST['visa35fecha_limrevdoc'] = $iHoy;
	$_REQUEST['visa35fecha_examenes'] = $iHoy;
	$_REQUEST['visa35fecha_seleccion'] = $iHoy;
	$_REQUEST['visa35fecha_ratificacion'] = $iHoy;
	$_REQUEST['visa35fecha_cierra'] = $iHoy;
	$_REQUEST['visa35presentacion'] = '';
	$_REQUEST['visa35total_inscritos'] = '';
	$_REQUEST['visa35total_autorizados'] = '';
	$_REQUEST['visa35total_presentaex'] = '';
	$_REQUEST['visa35total_aprobados'] = '';
	$_REQUEST['visa35total_admitidos'] = '';
	$_REQUEST['visa35idconvenio'] = '';
	$_REQUEST['visa35idresolucion'] = '';
	$_REQUEST['visa35idproducto'] = '';
	$_REQUEST['paso'] = 0;
}
if ($bLimpiaHijos) {
}
//AQUI SE DEBEN CARGAR TODOS LOS DATOS QUE LA FORMA NECESITE.
$bPuedeGuardar = true;
$bConEliminar = false;
$bConBotonCerrar = false;
$bPuedeAbrir = false;
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
/*
list($bHayImprimir, $sDebugP, $seg_6) = seg_revisa_permisoV3($iCodModulo, 6, $idTercero, $objDB);
*/
if ((int)$_REQUEST['paso'] != 0) {
	//list($bHayImprimir2, $sDebugP, $seg_5) = seg_revisa_permisoV3($iCodModulo, 5, $idTercero, $objDB);
	switch ($_REQUEST['visa35estado']) {
		case 0: // Abierto
			$bConEliminar = true;
			$bConBotonCerrar = true;
			$bDevuelve = false;
			//list($bDevuelve, $sDebugP, $seg_8) = seg_revisa_permisoV3($iCodModulo, 8, $idTercero, $objDB);
			break;
		case 7: // Cerrado
			$bPuedeGuardar = false;
			list($bPuedeAbrir, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 17, $idTercero, $objDB);
			break;
	}
}
//DATOS PARA COMPLETAR EL FORMULARIO
$iAgnoIni = 2000;
$iAgno = fecha_agno();
$iAgnoFin = $iAgno + 5;
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
$objCombos->nuevo('visa35idtipo', $_REQUEST['visa35idtipo'], true, '{' . $ETI['msg_seleccione'] . '}');
$sSQL = 'SELECT visa34id AS id, visa34nombre AS nombre FROM visa34convtipo ORDER BY visa34nombre';
$html_visa35idtipo = $objCombos->html($sSQL, $objDB);
$objCombos->nuevo('visa35idzona', $_REQUEST['visa35idzona'], true, '{' . $ETI['msg_seleccione'] . '}');
$objCombos->sAccion = 'carga_combo_visa35idcentro();';
$sSQL = 'SELECT unad23id AS id, unad23nombre AS nombre FROM unad23zona ORDER BY unad23nombre';
$html_visa35idzona = $objCombos->html($sSQL, $objDB);
$html_visa35idcentro = f2935_HTMLComboV2_visa35idcentro($objDB, $objCombos, $_REQUEST['visa35idcentro'], $_REQUEST['visa35idzona']);
$objCombos->nuevo('visa35idescuela', $_REQUEST['visa35idescuela'], true, '{' . $ETI['msg_seleccione'] . '}');
$objCombos->sAccion = 'carga_combo_visa35idprograma();';
$sSQL = 'SELECT core12id AS id, core12nombre AS nombre FROM core12escuela ORDER BY core12nombre';
$html_visa35idescuela = $objCombos->html($sSQL, $objDB);
$html_visa35idprograma = f2935_HTMLComboV2_visa35idprograma($objDB, $objCombos, $_REQUEST['visa35idprograma'], $_REQUEST['visa35idescuela']);
$objCombos->nuevo('visa35gruponivel', $_REQUEST['visa35gruponivel'], true, $ETI['no'], 0);
$objCombos->sAccion = 'carga_combo_visa35nivelforma();';
$objCombos->addItem(1, $ETI['si']);
//$objCombos->addArreglo($avisa35gruponivel, $ivisa35gruponivel);
$sSQL = '';
$html_visa35gruponivel = $objCombos->html($sSQL, $objDB);
$html_visa35nivelforma = f2935_HTMLComboV2_visa35nivelforma($objDB, $objCombos, $_REQUEST['visa35nivelforma'], $_REQUEST['visa35gruponivel']);
$visa35estado_nombre = '{' . $_REQUEST['visa35estado'] . '}';
$sSQL = 'SELECT unad96nombre, unad96etiqueta FROM unad96estado WHERE unad96idmodulo=2935 AND unad96id=' . $_REQUEST['visa35estado'];
$tabla = $objDB->ejecutasql($sSQL);
if ($objDB->nf($tabla) > 0) {
	$fila = $objDB->sf($tabla);
	$visa35estado_nombre = cadena_notildes($fila['unad96nombre']);
	if ($sIdioma != 'es') {
		$visa35estado_nombre = Etiqueta_Valor(4137, $fila['unad96etiqueta'], $sIdioma, $objDB);
	}
}
$html_visa35estado = html_oculto('visa35estado', $_REQUEST['visa35estado'], $visa35estado_nombre);
$objCombos->nuevo('visa35idproducto', $_REQUEST['visa35idproducto'], true, '{' . $ETI['msg_seleccione'] . '}');
$sSQL = 'SELECT cart01id AS id, cart01nombre AS nombre FROM cart01productos ORDER BY cart01nombre';
$html_visa35idproducto = $objCombos->html($sSQL, $objDB);
if ((int)$_REQUEST['paso'] == 0) {
} else {
}
//Alistar datos adicionales
$id_rpt = 0;
//$id_rpt=reportes_id(_Identificador_Tipo_Reporte_, $objDB);
$objCombos->nuevo('bestado', $_REQUEST['bestado'], true, '{' . $ETI['msg_todos'] . '}');
$objCombos->sAccion = 'paginarf2935()';
$sSQL = 'SELECT unad96id AS id, unad96nombre AS nombre FROM unad96estado WHERE unad96idmodulo=' . $_SESSION['u_identidad'] . ' ORDER BY unad96nombre';
$html_bestado = $objCombos->html($sSQL, $objDB);
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
//Cargar las tablas de datos
$aParametros[0] = ''; //$_REQUEST['p1_2935'];
$aParametros[100] = $idTercero;
$aParametros[101] = $_REQUEST['paginaf2935'];
$aParametros[102] = $_REQUEST['lppf2935'];
$aParametros[103] = $_REQUEST['bnombre'];
$aParametros[104] = $_REQUEST['bestado'];
list($sTabla2935, $sDebugTabla) = f2935_TablaDetalleV2($aParametros, $objDB, $bDebug);
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
		if ($bConBotonCerrar) {
			$aBotones[$iNumBoton] = array('enviacerrar()', $ETI['bt_cerrar'], 'iTask');
			$iNumBoton++;
		}
		if ($bPuedeAbrir) {
			$aBotones[$iNumBoton] = array('enviaabrir()', $ETI['bt_abrir'], 'iOpen');
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

	function imprimelista() {
		if (window.document.frmedita.seg_6.value == 1) {
			window.document.frmlista.consulta.value = window.document.frmedita.consulta_2935.value;
			window.document.frmlista.titulos.value = window.document.frmedita.titulos_2935.value;
			window.document.frmlista.nombrearchivo.value = 'Convocatorias';
			window.document.frmlista.submit();
		} else {
			ModalMensaje("<?php echo $ERR['6']; ?>");
		}
	}

	function asignarvariables() {
		window.document.frmimpp.v3.value = window.document.frmedita.bnombre.value;
		window.document.frmimpp.v4.value = window.document.frmedita.bestado.value;
		window.document.frmimpp.separa.value = window.document.frmedita.csv_separa.value.trim();
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
			window.document.frmimpp.action = 'e2935_ss.php';
			window.document.frmimpp.submit();
		} else {
			ModalMensaje(sError);
		}
	}

	function imprimep() {
		if (window.document.frmedita.seg_5.value == 1) {
			asignarvariables();
			window.document.frmimpp.action = 'p2935.php';
			window.document.frmimpp.submit();
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
		datos[1] = window.document.frmedita.visa35consec.value;
		if ((datos[1] != '')) {
			xajax_f2935_ExisteDato(datos);
		}
	}

	function cargadato(llave1) {
		window.document.frmedita.visa35consec.value = String(llave1);
		window.document.frmedita.paso.value = 1;
		window.document.frmedita.submit();
	}

	function cargaridf2935(llave1) {
		window.document.frmedita.visa35id.value = String(llave1);
		window.document.frmedita.paso.value = 3;
		window.document.frmedita.submit();
	}

	function carga_combo_visa35idcentro() {
		let params = new Array();
		params[0] = window.document.frmedita.visa35idzona.value;
		document.getElementById('div_visa35idcentro').innerHTML = '<b>Procesando datos, por favor espere...</b><input id="visa35idcentro" name="visa35idcentro" type="hidden" value="" />';
		xajax_f2935_Combovisa35idcentro(params);
	}

	function carga_combo_visa35idprograma() {
		let params = new Array();
		params[0] = window.document.frmedita.visa35idescuela.value;
		document.getElementById('div_visa35idprograma').innerHTML = '<b>Procesando datos, por favor espere...</b><input id="visa35idprograma" name="visa35idprograma" type="hidden" value="" />';
		xajax_f2935_Combovisa35idprograma(params);
	}

	function carga_combo_visa35nivelforma() {
		let params = new Array();
		params[0] = window.document.frmedita.visa35gruponivel.value;
		document.getElementById('div_visa35nivelforma').innerHTML = '<b>Procesando datos, por favor espere...</b><input id="visa35nivelforma" name="visa35nivelforma" type="hidden" value="" />';
		xajax_f2935_Combovisa35nivelforma(params);
	}

	function paginarf2935() {
		let params = new Array();
		params[99] = window.document.frmedita.debug.value;
		params[100] = <?php echo $idTercero; ?>;
		params[101] = window.document.frmedita.paginaf2935.value;
		params[102] = window.document.frmedita.lppf2935.value;
		params[103] = window.document.frmedita.bnombre.value;
		params[104] = window.document.frmedita.bestado.value;
		document.getElementById('div_f2935detalle').innerHTML = '<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf2935" name="paginaf2935" type="hidden" value="' + params[101] + '" /><input id="lppf2935" name="lppf2935" type="hidden" value="' + params[102] + '" />';
		xajax_f2935_HtmlTabla(params);
	}

	function enviacerrar() {
		ModalConfirmV2('<?php echo $ETI['msg_cierre2935']; ?>', () => {
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
		document.getElementById("visa35consec").focus();
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
</script>
<?php
if ($_REQUEST['paso'] != 0) {
?>
<?php
}
?>
<form id="frmimpp" name="frmimpp" method="post" action="p2935.php" target="_blank" style="display:none">
<input id="r" name="r" type="hidden" value="2935" />
<input id="id2935" name="id2935" type="hidden" value="<?php echo $_REQUEST['visa35id']; ?>" />
<input id="v3" name="v3" type="hidden" value="" />
<input id="v4" name="v4" type="hidden" value="" />
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
if ($bConBotonCerrar) {
?>
<input id="cmdCerrar" name="cmdCerrar" type="button" class="btSupCerrar" onclick="enviacerrar();" title="<?php echo $ETI['bt_cerrar']; ?>" value="<?php echo $ETI['bt_cerrar']; ?>" />
<?php
}
if ($bPuedeAbrir) {
?>
<input id="cmdAbrir" name="cmdAbrir" type="button" class="btSupAbrir" onclick="enviaabrir();" title="<?php echo $ETI['bt_abrir']; ?>" value="<?php echo $ETI['bt_abrir']; ?>" />
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
echo $objForma->htmlExpande(2935, $_REQUEST['boculta2935'], $ETI['bt_mostrar'], $ETI['bt_ocultar']);
$sEstiloDiv = '';
if ($_REQUEST['boculta2935'] != 0) {
	$sEstiloDiv = ' style="display:none;"';
}
?>
</div>
<div id="div_p2935"<?php echo $sEstiloDiv; ?>>
<?php
}
//Mostrar formulario para editar
?>
<label class="Label130"<?php echo $sOcultaConsec; ?>>
<?php
echo $ETI['visa35consec'];
?>
</label>
<label class="Label130"<?php echo $sOcultaConsec; ?>>
<?php
if ($_REQUEST['paso'] != 2) {
?>
<input id="visa35consec" name="visa35consec" type="text" value="<?php echo $_REQUEST['visa35consec']; ?>" onchange="RevisaLlave()" class="cuatro" />
<?php
} else {
	echo html_oculto('visa35consec', $_REQUEST['visa35consec'], formato_numero($_REQUEST['visa35consec']));
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
echo $ETI['visa35id'];
?>
</label>
<label class="Label60"<?php echo $sOcultaId; ?>>
<?php
	echo html_oculto('visa35id', $_REQUEST['visa35id'], formato_numero($_REQUEST['visa35id']));
?>
</label>
<label class="Label130">
<?php
echo $ETI['visa35idtipo'];
?>
</label>
<label>
<?php
echo $html_visa35idtipo;
?>
</label>
<label class="L">
<?php
echo $ETI['visa35nombre'];
?>

<input id="visa35nombre" name="visa35nombre" type="text" value="<?php echo $_REQUEST['visa35nombre']; ?>" maxlength="250" class="L" placeholder="<?php echo $ETI['ing_campo'] . $ETI['visa35nombre']; ?>" />
</label>
<label class="Label130">
<?php
echo $ETI['visa35idzona'];
?>
</label>
<label>
<?php
echo $html_visa35idzona;
?>
</label>
<label class="Label130">
<?php
echo $ETI['visa35idcentro'];
?>
</label>
<label>
<div id="div_visa35idcentro">
<?php
echo $html_visa35idcentro;
?>
</div>
</label>
<label class="Label130">
<?php
echo $ETI['visa35idescuela'];
?>
</label>
<label>
<?php
echo $html_visa35idescuela;
?>
</label>
<label class="Label130">
<?php
echo $ETI['visa35idprograma'];
?>
</label>
<label>
<div id="div_visa35idprograma">
<?php
echo $html_visa35idprograma;
?>
</div>
</label>
<label class="Label130">
<?php
echo $ETI['visa35gruponivel'];
?>
</label>
<label>
<?php
echo $html_visa35gruponivel;
?>
</label>
<label class="Label130">
<?php
echo $ETI['visa35nivelforma'];
?>
</label>
<label>
<div id="div_visa35nivelforma">
<?php
echo $html_visa35nivelforma;
?>
</div>
</label>
<label class="Label90">
<?php
echo $ETI['visa35estado'];
?>
</label>
<label class="Label220">
<?php
echo $html_visa35estado;
?>
</label>
<label class="Label130">
<?php
echo $ETI['visa35numcupos'];
?>
</label>
<label class="Label130">

<input id="visa35numcupos" name="visa35numcupos" type="text" value="<?php echo $_REQUEST['visa35numcupos']; ?>" class="diez" maxlength="10" placeholder="<?php echo $ETI['ing_vr']; ?>" />
</label>
<label class="Label130">
<?php
echo $ETI['visa35fecha_apertura'];
?>
</label>
<div class="Campo220">
<?php
echo html_FechaEnNumero('visa35fecha_apertura', $_REQUEST['visa35fecha_apertura']); //, false, '', $iAgnoIni, $iAgnoFin); //$bvacio, $accion
?>
</div>
<?php
if (false) {
	echo $objForma->htmlBotonSolo('bvisa35fecha_apertura_hoy', 'btMiniHoy', "fecha_AsignarNum('visa35fecha_apertura', " . $iHoy . ")", $ETI['bt_hoy']);
}
?>
<label class="Label130">
<?php
echo $ETI['visa35fecha_liminscrip'];
?>
</label>
<div class="Campo220">
<?php
echo html_FechaEnNumero('visa35fecha_liminscrip', $_REQUEST['visa35fecha_liminscrip']); //, false, '', $iAgnoIni, $iAgnoFin); //$bvacio, $accion
?>
</div>
<?php
if (false) {
	echo $objForma->htmlBotonSolo('bvisa35fecha_liminscrip_hoy', 'btMiniHoy', "fecha_AsignarNum('visa35fecha_liminscrip', " . $iHoy . ")", $ETI['bt_hoy']);
}
?>
<label class="Label130">
<?php
echo $ETI['visa35fecha_limrevdoc'];
?>
</label>
<div class="Campo220">
<?php
echo html_FechaEnNumero('visa35fecha_limrevdoc', $_REQUEST['visa35fecha_limrevdoc']); //, false, '', $iAgnoIni, $iAgnoFin); //$bvacio, $accion
?>
</div>
<?php
if (false) {
	echo $objForma->htmlBotonSolo('bvisa35fecha_limrevdoc_hoy', 'btMiniHoy', "fecha_AsignarNum('visa35fecha_limrevdoc', " . $iHoy . ")", $ETI['bt_hoy']);
}
?>
<label class="Label130">
<?php
echo $ETI['visa35fecha_examenes'];
?>
</label>
<div class="Campo220">
<?php
echo html_FechaEnNumero('visa35fecha_examenes', $_REQUEST['visa35fecha_examenes']); //, false, '', $iAgnoIni, $iAgnoFin); //$bvacio, $accion
?>
</div>
<?php
if (false) {
	echo $objForma->htmlBotonSolo('bvisa35fecha_examenes_hoy', 'btMiniHoy', "fecha_AsignarNum('visa35fecha_examenes', " . $iHoy . ")", $ETI['bt_hoy']);
}
?>
<label class="Label130">
<?php
echo $ETI['visa35fecha_seleccion'];
?>
</label>
<div class="Campo220">
<?php
echo html_FechaEnNumero('visa35fecha_seleccion', $_REQUEST['visa35fecha_seleccion']); //, false, '', $iAgnoIni, $iAgnoFin); //$bvacio, $accion
?>
</div>
<?php
if (false) {
	echo $objForma->htmlBotonSolo('bvisa35fecha_seleccion_hoy', 'btMiniHoy', "fecha_AsignarNum('visa35fecha_seleccion', " . $iHoy . ")", $ETI['bt_hoy']);
}
?>
<label class="Label130">
<?php
echo $ETI['visa35fecha_ratificacion'];
?>
</label>
<div class="Campo220">
<?php
echo html_FechaEnNumero('visa35fecha_ratificacion', $_REQUEST['visa35fecha_ratificacion']); //, false, '', $iAgnoIni, $iAgnoFin); //$bvacio, $accion
?>
</div>
<?php
if (false) {
	echo $objForma->htmlBotonSolo('bvisa35fecha_ratificacion_hoy', 'btMiniHoy', "fecha_AsignarNum('visa35fecha_ratificacion', " . $iHoy . ")", $ETI['bt_hoy']);
}
?>
<label class="Label130">
<?php
echo $ETI['visa35fecha_cierra'];
?>
</label>
<div class="Campo220">
<?php
echo html_FechaEnNumero('visa35fecha_cierra', $_REQUEST['visa35fecha_cierra']); //, false, '', $iAgnoIni, $iAgnoFin); //$bvacio, $accion
?>
</div>
<?php
if (false) {
	echo $objForma->htmlBotonSolo('bvisa35fecha_cierra_hoy', 'btMiniHoy', "fecha_AsignarNum('visa35fecha_cierra', " . $iHoy . ")", $ETI['bt_hoy']);
}
?>
<label class="txtAreaS">
<?php
echo $ETI['visa35presentacion'];
?>
<textarea id="visa35presentacion" name="visa35presentacion" placeholder="<?php echo $ETI['ing_campo'] . $ETI['visa35presentacion']; ?>"><?php echo $_REQUEST['visa35presentacion']; ?></textarea>
</label>
<label class="Label130">
<?php
echo $ETI['visa35total_inscritos'];
?>
</label>
<label class="Label130">

<input id="visa35total_inscritos" name="visa35total_inscritos" type="text" value="<?php echo $_REQUEST['visa35total_inscritos']; ?>" class="diez" maxlength="10" placeholder="<?php echo $ETI['ing_vr']; ?>" />
</label>
<label class="Label130">
<?php
echo $ETI['visa35total_autorizados'];
?>
</label>
<label class="Label130">

<input id="visa35total_autorizados" name="visa35total_autorizados" type="text" value="<?php echo $_REQUEST['visa35total_autorizados']; ?>" class="diez" maxlength="10" placeholder="<?php echo $ETI['ing_vr']; ?>" />
</label>
<label class="Label130">
<?php
echo $ETI['visa35total_presentaex'];
?>
</label>
<label class="Label130">

<input id="visa35total_presentaex" name="visa35total_presentaex" type="text" value="<?php echo $_REQUEST['visa35total_presentaex']; ?>" class="diez" maxlength="10" placeholder="<?php echo $ETI['ing_vr']; ?>" />
</label>
<label class="Label130">
<?php
echo $ETI['visa35total_aprobados'];
?>
</label>
<label class="Label130">

<input id="visa35total_aprobados" name="visa35total_aprobados" type="text" value="<?php echo $_REQUEST['visa35total_aprobados']; ?>" class="diez" maxlength="10" placeholder="<?php echo $ETI['ing_vr']; ?>" />
</label>
<label class="Label130">
<?php
echo $ETI['visa35total_admitidos'];
?>
</label>
<label class="Label130">

<input id="visa35total_admitidos" name="visa35total_admitidos" type="text" value="<?php echo $_REQUEST['visa35total_admitidos']; ?>" class="diez" maxlength="10" placeholder="<?php echo $ETI['ing_vr']; ?>" />
</label>
<input id="visa35idconvenio" name="visa35idconvenio" type="hidden" value="<?php echo $_REQUEST['visa35idconvenio']; ?>" />
<input id="visa35idresolucion" name="visa35idresolucion" type="hidden" value="<?php echo $_REQUEST['visa35idresolucion']; ?>" />
<label class="Label130">
<?php
echo $ETI['visa35idproducto'];
?>
</label>
<label>
<?php
echo $html_visa35idproducto;
?>
</label>
<?php
if (false) {
	//Ejemplo de boton de ayuda
	//echo html_BotonAyuda('NombreCampo');
	//echo html_DivAyudaLocal('NombreCampo');
}
if ($bConExpande) {
	//Este es el cierre del div_p2935
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
<input id="bnombre" name="bnombre" type="text" value="<?php echo $_REQUEST['bnombre']; ?>" onchange="paginarf2935()" autocomplete="off" />
</label>
<div class="salto1px"></div>
<label class="Label90">
<?php
echo $ETI['msg_bestado'];
?>
</label>
<label>
<?php
echo $html_bestado;
?>
</label>
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
<?php
echo ' ' . $csv_separa;
?>
<div class="salto1px"></div>
<div id="div_f2935detalle">
<?php
echo $sTabla2935;
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
echo $ETI['msg_visa35consec'];
?>
</label>
<label class="Label90">
<?php
echo '<b>' . $_REQUEST['visa35consec'] . '</b>';
?>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['msg_visa35consec_nuevo'];
// onchange="RevisaConsec()"
?>
</label>
<label class="Label90">
<input id="visa35consec_nuevo" name="visa35consec_nuevo" type="text" value="<?php echo $_REQUEST['visa35consec_nuevo']; ?>" class="cuatro" />
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
<input id="titulo_2935" name="titulo_2935" type="hidden" value="<?php echo $sTituloModulo; ?>" />
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
<script language="javascript" src="<?php echo $APP->rutacomun; ?>js/chosen.jquery.js"></script>
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>js/chosen.css" type="text/css" />
<?php
if (false) {
//}
//if ($_REQUEST['paso'] == 0) {
?>
<script language="javascript">
	$().ready(function() {
		$("#visa35idtipo").chosen({width:"100%"});
		$("#visa35idzona").chosen({width:"100%"});
		$("#visa35idcentro").chosen({width:"100%"});
		$("#visa35idescuela").chosen({width:"100%"});
		$("#visa35idprograma").chosen({width:"100%"});
		$("#visa35nivelforma").chosen({width:"100%"});
		$("#visa35idproducto").chosen({width:"100%"});
		$("#bestado").chosen({width:"100%"});
	});
</script>
<?php
}
?>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>unad_todas2024v2.js"></script>
<?php
forma_piedepagina();

