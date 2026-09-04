<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2026 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 3.1.5b lunes, 23 de marzo de 2026
*/
/** Archivo unadmasivo.php.
 * Modulo 1205 masi05mensajes.
 * @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
 * @param debug = 1  (Opcional), bandera para indicar si se generan datos de depuración
 * @date lunes, 23 de marzo de 2026
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
require $APP->rutacomun . 'libmail.php';
require $APP->rutacomun . 'libhtml.php';
require $APP->rutacomun . 'xajax/xajax_core/xajax.inc.php';
require $APP->rutacomun . 'unad_xajax.php';
require $APP->rutacomun . 'libmasivos.php';
if (($bPeticionXAJAX) && ($_SESSION['unad_id_tercero'] == 0)) {
	// viene por xajax.
	$xajax = new xajax();
	$xajax->configure('javascript URI', $APP->rutacomun . 'xajax/');
	$xajax->register(XAJAX_FUNCTION, 'sesion_abandona_V2');
	$xajax->processRequest();
	die();
}
$iConsecutivoMenu = 1;
$iMinVerDB = 9575;
$iCodModulo = 1205;
$iCodModuloConsulta = $iCodModulo;
$sIdioma = AUREA_Idioma();
$audita[1] = false;
// -- Se cargan los archivos de idioma
$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
if (!file_exists($mensajes_todas)) {
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
}
/*
$mensajes_1200 = 'lg/lg_1200_' . $sIdioma . '.php';
if (!file_exists($mensajes_1200)) {
	$mensajes_1200 = 'lg/lg_1200_es.php';
}
require $mensajes_1200;
*/
$mensajes_1205 = $APP->rutacomun . 'lg/lg_1205_' . $sIdioma . '.php';
if (!file_exists($mensajes_1205)) {
	$mensajes_1205 = $APP->rutacomun . 'lg/lg_1205_es.php';
}
require $mensajes_todas;
require $mensajes_1205;
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
$sTituloModulo = $ETI['titulo_1205'];
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
		header('Location:noticia.php?ret=unadmasivo.php');
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
//$idEntidad = Traer_Entidad();
$mensajes_1206 = $APP->rutacomun . 'lg/lg_1206_' . $sIdioma . '.php';
if (!file_exists($mensajes_1206)) {
	$mensajes_1206 = $APP->rutacomun . 'lg/lg_1206_es.php';
}
$mensajes_1207 = $APP->rutacomun . 'lg/lg_1207_' . $sIdioma . '.php';
if (!file_exists($mensajes_1207)) {
	$mensajes_1207 = $APP->rutacomun . 'lg/lg_1207_es.php';
}
$mensajes_1208 = $APP->rutacomun . 'lg/lg_1208_' . $sIdioma . '.php';
if (!file_exists($mensajes_1208)) {
	$mensajes_1208 = $APP->rutacomun . 'lg/lg_1208_es.php';
}
require $mensajes_1206;
require $mensajes_1207;
require $mensajes_1208;
// -- Si esta cargando la pagina por primer vez se revisa si requiere auditar y se manda a hacer un limpiar (paso -1)
if (isset($_REQUEST['paso']) == 0) {
	$_REQUEST['paso'] = -1;
	if ($audita[1]) {
		seg_auditaingreso($iCodModulo, $_SESSION['unad_id_tercero'], $objDB);
	}
}
// -- 1205 masi05mensajes
require $APP->rutacomun . 'lib1205.php';
// -- 1206 Poblacion
require $APP->rutacomun . 'lib1206.php';
// -- 1207 Anexo
require $APP->rutacomun . 'lib1207.php';
// -- 1208 Destinatario
require $APP->rutacomun . 'lib1208.php';
$xajax = new xajax();
$xajax->configure('javascript URI', $APP->rutacomun . 'xajax/');
$xajax->register(XAJAX_FUNCTION, 'unad11_Mostrar_v2');
$xajax->register(XAJAX_FUNCTION, 'unad11_TraerXid');
$xajax->register(XAJAX_FUNCTION, 'f1205_Combomasi05centro');
$xajax->register(XAJAX_FUNCTION, 'f1205_Combomasi05programa');
$xajax->register(XAJAX_FUNCTION, 'f1205_Combomasi05curso');
$xajax->register(XAJAX_FUNCTION, 'f1205_Combobcentro');
$xajax->register(XAJAX_FUNCTION, 'f1205_Combobprograma');
$xajax->register(XAJAX_FUNCTION, 'sesion_abandona_V2');
$xajax->register(XAJAX_FUNCTION, 'sesion_mantenerV4');
$xajax->register(XAJAX_FUNCTION, 'f1205_HtmlTabla');
$xajax->register(XAJAX_FUNCTION, 'f1205_ExisteDato');
$xajax->register(XAJAX_FUNCTION, 'f1205_Busquedas');
$xajax->register(XAJAX_FUNCTION, 'f1205_HtmlBusqueda');
$xajax->register(XAJAX_FUNCTION, 'f1206_Combomasi06centro');
$xajax->register(XAJAX_FUNCTION, 'f1206_Combomasi06programa');
$xajax->register(XAJAX_FUNCTION, 'f1206_Combomasi06curso');
$xajax->register(XAJAX_FUNCTION, 'f1206_Guardar');
$xajax->register(XAJAX_FUNCTION, 'f1206_Traer');
$xajax->register(XAJAX_FUNCTION, 'f1206_Eliminar');
$xajax->register(XAJAX_FUNCTION, 'f1206_HtmlTabla');
$xajax->register(XAJAX_FUNCTION, 'f1206_PintarLlaves');
$xajax->register(XAJAX_FUNCTION, 'f1206_Reversar');
$xajax->register(XAJAX_FUNCTION, 'elimina_archivo_masi07idarchivo');
$xajax->register(XAJAX_FUNCTION, 'f1207_Guardar');
$xajax->register(XAJAX_FUNCTION, 'f1207_Traer');
$xajax->register(XAJAX_FUNCTION, 'f1207_Eliminar');
$xajax->register(XAJAX_FUNCTION, 'f1207_HtmlTabla');
$xajax->register(XAJAX_FUNCTION, 'f1207_PintarLlaves');
$xajax->register(XAJAX_FUNCTION, 'f1208_Guardar');
$xajax->register(XAJAX_FUNCTION, 'f1208_Traer');
$xajax->register(XAJAX_FUNCTION, 'f1208_Eliminar');
$xajax->register(XAJAX_FUNCTION, 'f1208_HtmlTabla');
$xajax->register(XAJAX_FUNCTION, 'f1208_PintarLlaves');
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
// Cada juego de masivo tiene que estar asociado a un proceso.
$idProceso = 0;
	/* 
0 - Ninguno
2 - Funcionarios
3 - Contratistas
11 - Aspirantes
12 - Estudiantes
13 - Estudiantes ausentes
17 - Egresados
2209 - Estudiantes del programa
2306 - Acompañamiento académico
2307 - Seguimiento académico
2741 - Postulados a grados
12229 - Convocados
	*/
if (isset($_REQUEST['iscroll']) == 0) {
	$_REQUEST['iscroll'] = 0;
}
if (isset($_REQUEST['paginaf1205']) == 0) {
	$_REQUEST['paginaf1205'] = 1;
}
if (isset($_REQUEST['lppf1205']) == 0) {
	$_REQUEST['lppf1205'] = 20;
}
if (isset($_REQUEST['boculta1205']) == 0) {
	$_REQUEST['boculta1205'] = 0;
}
// -- Inicializar variables de datos.
if (isset($_REQUEST['masi05idproceso']) == 0) {
	$_REQUEST['masi05idproceso'] = $idProceso;
}
if (isset($_REQUEST['masi05consec']) == 0) {
	$_REQUEST['masi05consec'] = '';
}
if (isset($_REQUEST['masi05consec_nuevo']) == 0) {
	$_REQUEST['masi05consec_nuevo'] = '';
}
if (isset($_REQUEST['masi05id']) == 0) {
	$_REQUEST['masi05id'] = '';
}
if (isset($_REQUEST['masi05estado']) == 0) {
	$_REQUEST['masi05estado'] = 0;
}
if (isset($_REQUEST['masi05asunto']) == 0) {
	$_REQUEST['masi05asunto'] = '';
}
if (isset($_REQUEST['masi05cuerpo']) == 0) {
	$_REQUEST['masi05cuerpo'] = '';
}
if (isset($_REQUEST['masi05admiterpta']) == 0) {
	$_REQUEST['masi05admiterpta'] = 0;
}
if (isset($_REQUEST['masi05correorpta']) == 0) {
	$_REQUEST['masi05correorpta'] = '';
}
if (isset($_REQUEST['masi05firma']) == 0) {
	$_REQUEST['masi05firma'] = '';
}
if (isset($_REQUEST['masi05idusuario']) == 0) {
	$_REQUEST['masi05idusuario'] = $idTercero;
}
if (isset($_REQUEST['masi05idusuario_td']) == 0) {
	$_REQUEST['masi05idusuario_td'] = $APP->tipo_doc;
}
if (isset($_REQUEST['masi05idusuario_doc']) == 0) {
	$_REQUEST['masi05idusuario_doc'] = '';
}
if (isset($_REQUEST['masi05fecha']) == 0) {
	$_REQUEST['masi05fecha'] = '';
	//$_REQUEST['masi05fecha'] = $iHoy;
}
if (isset($_REQUEST['masi05hora']) == 0) {
	$_REQUEST['masi05hora'] = fecha_hora();
}
if (isset($_REQUEST['masi05min']) == 0) {
	$_REQUEST['masi05min'] = fecha_minuto();
}
if (isset($_REQUEST['masi05unidadfunc']) == 0) {
	$_REQUEST['masi05unidadfunc'] = '';
}
if (isset($_REQUEST['masi05zona']) == 0) {
	$_REQUEST['masi05zona'] = '';
}
if (isset($_REQUEST['masi05centro']) == 0) {
	$_REQUEST['masi05centro'] = '';
}
if (isset($_REQUEST['masi05escuela']) == 0) {
	$_REQUEST['masi05escuela'] = '';
}
if (isset($_REQUEST['masi05programa']) == 0) {
	$_REQUEST['masi05programa'] = '';
}
if (isset($_REQUEST['masi05idperiodo']) == 0) {
	$_REQUEST['masi05idperiodo'] = 0;
}
if (isset($_REQUEST['masi05curso']) == 0) {
	$_REQUEST['masi05curso'] = 0;
}
if (isset($_REQUEST['masi05docente']) == 0) {
	$_REQUEST['masi05docente'] = 0;
}
if (isset($_REQUEST['masi05total_usuarios']) == 0) {
	$_REQUEST['masi05total_usuarios'] = 0;
}
if (isset($_REQUEST['masi05total_envios']) == 0) {
	$_REQUEST['masi05total_envios'] = 0;
}
if (isset($_REQUEST['masi05tiponotifica']) == 0) {
	$_REQUEST['masi05tiponotifica'] = 0;
}
if (isset($_REQUEST['masi05periodicidad']) == 0) {
	$_REQUEST['masi05periodicidad'] = 0;
}
if (isset($_REQUEST['masi05idrelacion']) == 0) {
	$_REQUEST['masi05idrelacion'] = 0;
}
if (isset($_REQUEST['masi05idrelacion2']) == 0) {
	$_REQUEST['masi05idrelacion2'] = 0;
}
if (isset($_REQUEST['masi05idrelacion3']) == 0) {
	$_REQUEST['masi05idrelacion3'] = 0;
}
$_REQUEST['masi05idproceso'] = numeros_validar($_REQUEST['masi05idproceso']);
$_REQUEST['masi05consec'] = numeros_validar($_REQUEST['masi05consec']);
$_REQUEST['masi05id'] = numeros_validar($_REQUEST['masi05id']);
$_REQUEST['masi05estado'] = numeros_validar($_REQUEST['masi05estado']);
$_REQUEST['masi05asunto'] = cadena_Validar($_REQUEST['masi05asunto']);
$_REQUEST['masi05cuerpo'] = cadena_Validar($_REQUEST['masi05cuerpo'],true);
$_REQUEST['masi05admiterpta'] = numeros_validar($_REQUEST['masi05admiterpta']);
$_REQUEST['masi05correorpta'] = cadena_Validar($_REQUEST['masi05correorpta']);
$_REQUEST['masi05firma'] = numeros_validar($_REQUEST['masi05firma']);
$_REQUEST['masi05idusuario'] = numeros_validar($_REQUEST['masi05idusuario']);
$_REQUEST['masi05idusuario_td'] = cadena_Validar($_REQUEST['masi05idusuario_td']);
$_REQUEST['masi05idusuario_doc'] = cadena_Validar($_REQUEST['masi05idusuario_doc']);
$_REQUEST['masi05fecha'] = numeros_validar($_REQUEST['masi05fecha']);
$_REQUEST['masi05hora'] = numeros_validar($_REQUEST['masi05hora']);
$_REQUEST['masi05min'] = numeros_validar($_REQUEST['masi05min']);
$_REQUEST['masi05unidadfunc'] = numeros_validar($_REQUEST['masi05unidadfunc']);
$_REQUEST['masi05zona'] = numeros_validar($_REQUEST['masi05zona']);
$_REQUEST['masi05centro'] = numeros_validar($_REQUEST['masi05centro']);
$_REQUEST['masi05escuela'] = numeros_validar($_REQUEST['masi05escuela']);
$_REQUEST['masi05programa'] = numeros_validar($_REQUEST['masi05programa']);
$_REQUEST['masi05idperiodo'] = numeros_validar($_REQUEST['masi05idperiodo']);
$_REQUEST['masi05curso'] = numeros_validar($_REQUEST['masi05curso']);
$_REQUEST['masi05docente'] = numeros_validar($_REQUEST['masi05docente']);
$_REQUEST['masi05total_usuarios'] = numeros_validar($_REQUEST['masi05total_usuarios']);
$_REQUEST['masi05total_envios'] = numeros_validar($_REQUEST['masi05total_envios']);
$_REQUEST['masi05tiponotifica'] = numeros_validar($_REQUEST['masi05tiponotifica']);
$_REQUEST['masi05periodicidad'] = numeros_validar($_REQUEST['masi05periodicidad']);
$_REQUEST['masi05idrelacion'] = numeros_validar($_REQUEST['masi05idrelacion']);
$_REQUEST['masi05idrelacion2'] = numeros_validar($_REQUEST['masi05idrelacion2']);
$_REQUEST['masi05idrelacion3'] = numeros_validar($_REQUEST['masi05idrelacion3']);
if ((int)$_REQUEST['paso'] > 0) {
	//Poblacion
	if (isset($_REQUEST['paginaf1206']) == 0) {
		$_REQUEST['paginaf1206'] = 1;
	}
	if (isset($_REQUEST['lppf1206']) == 0) {
		$_REQUEST['lppf1206'] = 20;
	}
	if (isset($_REQUEST['boculta1206']) == 0) {
		$_REQUEST['boculta1206'] = 0;
	}
	if (isset($_REQUEST['masi06idmensaje']) == 0) {
		$_REQUEST['masi06idmensaje'] = '';
	}
	if (isset($_REQUEST['masi06consec']) == 0) {
		$_REQUEST['masi06consec'] = '';
	}
	if (isset($_REQUEST['masi06id']) == 0) {
		$_REQUEST['masi06id'] = '';
	}
	if (isset($_REQUEST['masi06zona']) == 0) {
		$_REQUEST['masi06zona'] = '';
	}
	if (isset($_REQUEST['masi06centro']) == 0) {
		$_REQUEST['masi06centro'] = '';
	}
	if (isset($_REQUEST['masi06escuela']) == 0) {
		$_REQUEST['masi06escuela'] = '';
	}
	if (isset($_REQUEST['masi06nivelforma']) == 0) {
		$_REQUEST['masi06nivelforma'] = '';
	}
	if (isset($_REQUEST['masi06programa']) == 0) {
		$_REQUEST['masi06programa'] = 0;
	}
	if (isset($_REQUEST['masi06est_condicion']) == 0) {
		$_REQUEST['masi06est_condicion'] = 0;
	}
	if (isset($_REQUEST['masi06sexo']) == 0) {
		$_REQUEST['masi06sexo'] = 0;
	}
	if (isset($_REQUEST['masi06idperiodo']) == 0) {
		$_REQUEST['masi06idperiodo'] = '';
	}
	if (isset($_REQUEST['masi06curso']) == 0) {
		$_REQUEST['masi06curso'] = '';
	}
	if (isset($_REQUEST['masi06docente']) == 0) {
		$_REQUEST['masi06docente'] = 0;
	}
	if (isset($_REQUEST['masi06unidadfunc']) == 0) {
		$_REQUEST['masi06unidadfunc'] = 0;
	}
	if (isset($_REQUEST['masi06agnogrado']) == 0) {
		$_REQUEST['masi06agnogrado'] = 0;
	}
	$_REQUEST['masi06idmensaje'] = numeros_validar($_REQUEST['masi06idmensaje']);
	$_REQUEST['masi06consec'] = numeros_validar($_REQUEST['masi06consec']);
	$_REQUEST['masi06id'] = numeros_validar($_REQUEST['masi06id']);
	$_REQUEST['masi06zona'] = numeros_validar($_REQUEST['masi06zona']);
	$_REQUEST['masi06centro'] = numeros_validar($_REQUEST['masi06centro']);
	$_REQUEST['masi06escuela'] = numeros_validar($_REQUEST['masi06escuela']);
	$_REQUEST['masi06nivelforma'] = numeros_validar($_REQUEST['masi06nivelforma']);
	$_REQUEST['masi06programa'] = numeros_validar($_REQUEST['masi06programa']);
	$_REQUEST['masi06est_condicion'] = numeros_validar($_REQUEST['masi06est_condicion']);
	$_REQUEST['masi06sexo'] = numeros_validar($_REQUEST['masi06sexo']);
	$_REQUEST['masi06idperiodo'] = numeros_validar($_REQUEST['masi06idperiodo']);
	$_REQUEST['masi06curso'] = numeros_validar($_REQUEST['masi06curso']);
	$_REQUEST['masi06docente'] = numeros_validar($_REQUEST['masi06docente']);
	$_REQUEST['masi06unidadfunc'] = numeros_validar($_REQUEST['masi06unidadfunc']);
	$_REQUEST['masi06agnogrado'] = numeros_validar($_REQUEST['masi06agnogrado']);
	//Anexo
	if (isset($_REQUEST['paginaf1207']) == 0) {
		$_REQUEST['paginaf1207'] = 1;
	}
	if (isset($_REQUEST['lppf1207']) == 0) {
		$_REQUEST['lppf1207'] = 20;
	}
	if (isset($_REQUEST['boculta1207']) == 0) {
		$_REQUEST['boculta1207'] = 0;
	}
	if (isset($_REQUEST['masi07idmensaje']) == 0) {
		$_REQUEST['masi07idmensaje'] = '';
	}
	if (isset($_REQUEST['masi07consec']) == 0) {
		$_REQUEST['masi07consec'] = '';
	}
	if (isset($_REQUEST['masi07id']) == 0) {
		$_REQUEST['masi07id'] = '';
	}
	if (isset($_REQUEST['masi07titulo']) == 0) {
		$_REQUEST['masi07titulo'] = '';
	}
	if (isset($_REQUEST['masi07idorigen']) == 0) {
		$_REQUEST['masi07idorigen'] = 0;
	}
	if (isset($_REQUEST['masi07idarchivo']) == 0) {
		$_REQUEST['masi07idarchivo'] = 0;
	}
	$_REQUEST['masi07idmensaje'] = numeros_validar($_REQUEST['masi07idmensaje']);
	$_REQUEST['masi07consec'] = numeros_validar($_REQUEST['masi07consec']);
	$_REQUEST['masi07id'] = numeros_validar($_REQUEST['masi07id']);
	$_REQUEST['masi07titulo'] = cadena_Validar($_REQUEST['masi07titulo']);
	$_REQUEST['masi07idorigen'] = numeros_validar($_REQUEST['masi07idorigen']);
	$_REQUEST['masi07idarchivo'] = numeros_validar($_REQUEST['masi07idarchivo']);
	//Destinatario
	if (isset($_REQUEST['paginaf1208']) == 0) {
		$_REQUEST['paginaf1208'] = 1;
	}
	if (isset($_REQUEST['lppf1208']) == 0) {
		$_REQUEST['lppf1208'] = 20;
	}
	if (isset($_REQUEST['boculta1208']) == 0) {
		$_REQUEST['boculta1208'] = 0;
	}
	if (isset($_REQUEST['masi08idmensaje']) == 0) {
		$_REQUEST['masi08idmensaje'] = '';
	}
	if (isset($_REQUEST['masi08idtercero']) == 0) {
		$_REQUEST['masi08idtercero'] = 0;
		//$_REQUEST['masi08idtercero'] =  $idTercero;
	}
	if (isset($_REQUEST['masi08idtercero_td']) == 0) {
		$_REQUEST['masi08idtercero_td'] = $APP->tipo_doc;
	}
	if (isset($_REQUEST['masi08idtercero_doc']) == 0) {
		$_REQUEST['masi08idtercero_doc'] = '';
	}
	if (isset($_REQUEST['masi08idfecha']) == 0) {
		$_REQUEST['masi08idfecha'] = '';
		//$_REQUEST['masi08idfecha'] = $iHoy;
	}
	if (isset($_REQUEST['masi08id']) == 0) {
		$_REQUEST['masi08id'] = '';
	}
	if (isset($_REQUEST['masi08idpoblacion']) == 0) {
		$_REQUEST['masi08idpoblacion'] = '';
	}
	if (isset($_REQUEST['masi08fechaenvio']) == 0) {
		$_REQUEST['masi08fechaenvio'] = '';
		//$_REQUEST['masi08fechaenvio'] = $iHoy;
	}
	if (isset($_REQUEST['masi08horaenvio']) == 0) {
		$_REQUEST['masi08horaenvio'] = '';
	}
	if (isset($_REQUEST['masi08minenvio']) == 0) {
		$_REQUEST['masi08minenvio'] = '';
	}
	if (isset($_REQUEST['masi08idsmtp']) == 0) {
		$_REQUEST['masi08idsmtp'] = 0;
	}
	$_REQUEST['masi08idmensaje'] = numeros_validar($_REQUEST['masi08idmensaje']);
	$_REQUEST['masi08idtercero'] = numeros_validar($_REQUEST['masi08idtercero']);
	$_REQUEST['masi08idtercero_td'] = cadena_Validar($_REQUEST['masi08idtercero_td']);
	$_REQUEST['masi08idtercero_doc'] = cadena_Validar($_REQUEST['masi08idtercero_doc']);
	$_REQUEST['masi08idfecha'] = numeros_validar($_REQUEST['masi08idfecha']);
	$_REQUEST['masi08id'] = numeros_validar($_REQUEST['masi08id']);
	$_REQUEST['masi08idpoblacion'] = numeros_validar($_REQUEST['masi08idpoblacion']);
	$_REQUEST['masi08fechaenvio'] = numeros_validar($_REQUEST['masi08fechaenvio']);
	$_REQUEST['masi08horaenvio'] = numeros_validar($_REQUEST['masi08horaenvio']);
	$_REQUEST['masi08minenvio'] = numeros_validar($_REQUEST['masi08minenvio']);
	$_REQUEST['masi08idsmtp'] = numeros_validar($_REQUEST['masi08idsmtp']);
}
// Espacio para inicializar otras variables
if (isset($_REQUEST['csv_separa']) == 0) {
	$_REQUEST['csv_separa'] = ';';
}
if (isset($_REQUEST['bmes']) == 0) {
	$_REQUEST['bmes'] = date("Ym");
}
if (isset($_REQUEST['bloque']) == 0) {
	$_REQUEST['bloque'] = '';
}
if (isset($_REQUEST['basunto']) == 0) {
	$_REQUEST['basunto'] = '';
}
if (isset($_REQUEST['bcuerpo']) == 0) {
	$_REQUEST['bcuerpo'] = '';
}
if (isset($_REQUEST['bfechainicia']) == 0) {
	$_REQUEST['bfechainicia'] = 0;
}
if (isset($_REQUEST['bfechafinal']) == 0) {
	$_REQUEST['bfechafinal'] = 0;
}
if (isset($_REQUEST['bunidadfunc']) == 0) {
	$_REQUEST['bunidadfunc'] = '';
}
if (isset($_REQUEST['bzona']) == 0) {
	$_REQUEST['bzona'] = '';
}
if (isset($_REQUEST['bcentro']) == 0) {
	$_REQUEST['bcentro'] = '';
}
if (isset($_REQUEST['bescuela']) == 0) {
	$_REQUEST['bescuela'] = '';
}
if (isset($_REQUEST['bprograma']) == 0) {
	$_REQUEST['bprograma'] = '';
}
if (isset($_REQUEST['bcurso']) == 0) {
	$_REQUEST['bcurso'] = '';
}
if (isset($_REQUEST['bproceso']) == 0) {
	$_REQUEST['bproceso'] = $idProceso;
}
	//Poblacion
	//Anexo
	//Destinatario
$_REQUEST['bmes'] = numeros_validar($_REQUEST['bmes']);
$_REQUEST['basunto'] = cadena_Validar($_REQUEST['basunto']);
$_REQUEST['bcuerpo'] = cadena_Validar($_REQUEST['bcuerpo']);
$_REQUEST['bfechainicia'] = numeros_validar($_REQUEST['bfechainicia']);
$_REQUEST['bfechafinal'] = numeros_validar($_REQUEST['bfechafinal']);
$_REQUEST['bunidadfunc'] = numeros_validar($_REQUEST['bunidadfunc']);
$_REQUEST['bzona'] = numeros_validar($_REQUEST['bzona']);
$_REQUEST['bcentro'] = numeros_validar($_REQUEST['bcentro']);
$_REQUEST['bescuela'] = numeros_validar($_REQUEST['bescuela']);
$_REQUEST['bprograma'] = numeros_validar($_REQUEST['bprograma']);
$_REQUEST['bcurso'] = cadena_Validar($_REQUEST['bcurso']);
$_REQUEST['bproceso'] = numeros_validar($_REQUEST['bproceso']);
	//Poblacion
	//Anexo
	//Destinatario
//Si Modifica o Elimina Cargar los campos
if (($_REQUEST['paso'] == 1) || ($_REQUEST['paso'] == 3)) {
	$_REQUEST['masi05idusuario_td'] = $APP->tipo_doc;
	$_REQUEST['masi05idusuario_doc'] = '';
	if ($_REQUEST['paso'] == 1) {
		$sSQLcondi = 'masi05idproceso=' . $_REQUEST['masi05idproceso'] . ' AND masi05consec=' . $_REQUEST['masi05consec'] . '';
	} else {
		$sSQLcondi = 'masi05id=' . $_REQUEST['masi05id'] . '';
	}
	list($sTabla1205, $sErrorT) = f1205_NombreTabla($_REQUEST['bmes'], $objDB);
	$sError = $sError . $sErrorT;
	if ($sError == '') {
		$sSQL = 'SELECT * FROM ' . $sTabla1205 . ' WHERE ' . $sSQLcondi;
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			$_REQUEST['masi05idproceso'] = $fila['masi05idproceso'];
			$_REQUEST['masi05consec'] = $fila['masi05consec'];
			$_REQUEST['masi05id'] = $fila['masi05id'];
			$_REQUEST['masi05estado'] = $fila['masi05estado'];
			$_REQUEST['masi05asunto'] = $fila['masi05asunto'];
			$_REQUEST['masi05cuerpo'] = $fila['masi05cuerpo'];
			$_REQUEST['masi05admiterpta'] = $fila['masi05admiterpta'];
			$_REQUEST['masi05correorpta'] = $fila['masi05correorpta'];
			$_REQUEST['masi05firma'] = $fila['masi05firma'];
			$_REQUEST['masi05idusuario'] = $fila['masi05idusuario'];
			$_REQUEST['masi05fecha'] = $fila['masi05fecha'];
			$_REQUEST['masi05hora'] = $fila['masi05hora'];
			$_REQUEST['masi05min'] = $fila['masi05min'];
			$_REQUEST['masi05unidadfunc'] = $fila['masi05unidadfunc'];
			$_REQUEST['masi05zona'] = $fila['masi05zona'];
			$_REQUEST['masi05centro'] = $fila['masi05centro'];
			$_REQUEST['masi05escuela'] = $fila['masi05escuela'];
			$_REQUEST['masi05programa'] = $fila['masi05programa'];
			$_REQUEST['masi05idperiodo'] = $fila['masi05idperiodo'];
			$_REQUEST['masi05curso'] = $fila['masi05curso'];
			$_REQUEST['masi05docente'] = $fila['masi05docente'];
			$_REQUEST['masi05total_usuarios'] = $fila['masi05total_usuarios'];
			$_REQUEST['masi05total_envios'] = $fila['masi05total_envios'];
			$_REQUEST['masi05tiponotifica'] = $fila['masi05tiponotifica'];
			$_REQUEST['masi05periodicidad'] = $fila['masi05periodicidad'];
			$_REQUEST['masi05idrelacion'] = $fila['masi05idrelacion'];
			$_REQUEST['masi05idrelacion2'] = $fila['masi05idrelacion2'];
			$_REQUEST['masi05idrelacion3'] = $fila['masi05idrelacion3'];
			$bcargo = true;
			$_REQUEST['paso'] = 2;
			$_REQUEST['boculta1205'] = 0;
			$bLimpiaHijos = true;
			if ($idProceso != 0) {
				if ($idProceso != $fila['masi05idproceso']) {
					$sError = 'No es posible consultar este registro en esta visual.';
					$_REQUEST['paso'] = -1;
				}
			}
		} else {
			$_REQUEST['paso'] = 0;
		}
	} else {
		$_REQUEST['paso'] = 0;
	}
}
//Cerrar
$bCambiaEstado = false;
$iEstadoDestino = 0;
if ($_REQUEST['paso'] == 16) {
	$_REQUEST['paso'] = 12;
	if ($sError == '') {
		$bCambiaEstado = true;
		$iEstadoDestino = 3;
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
		list($sError, $sDebugE, $sMensaje) = f1205_CambiaEstado($_REQUEST['masi05id'], $_REQUEST['bloque'], $_REQUEST['masi05estado'], 0, '', $_SESSION['unad_id_tercero'], $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugE;
		if ($sError == '') {
			$_REQUEST['masi05estado'] = 0;
			$sError = '<b>' . $ETI['msg_itemabierto'] . '</b>';
			$iTipoError = 1;
		}
	}
}
//Insertar o modificar un elemento
if (($_REQUEST['paso'] == 10) || ($_REQUEST['paso'] == 12)) {
	$bMueveScroll = true;
	list($_REQUEST, $sError, $iTipoError, $sDebugGuardar) = f1205_db_GuardarV2b($_REQUEST, $objDB, $bDebug, $idTercero);
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
		list($sError, $sDebugE, $sMensaje) = f1205_CambiaEstado($_REQUEST['masi05id'], $_REQUEST['bloque'], $_REQUEST['masi05estado'], $iEstadoDestino, '', $_SESSION['unad_id_tercero'], $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugE;
		if ($sError == '') {
			$_REQUEST['masi05estado'] = $iEstadoDestino;
			$sError = '<b>' . $ETI['msg_itemcerrado'] . '</b>';
			$iTipoError = 1;
		}
	}
}
// Cambio de consecutivo.
if ($_REQUEST['paso'] == 93) {
	$_REQUEST['paso'] = 2;
	$_REQUEST['masi05consec_nuevo'] = numeros_validar($_REQUEST['masi05consec_nuevo']);
	if ($_REQUEST['masi05consec_nuevo'] == '') {
		$sError = $ERR['masi05consec'];
	}
	if ($sError == '') {
		list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 8, $idTercero, $objDB);
		if (!$bDevuelve) {
			$sError = $ERR['8'] . ' [Mod ' . $iCodModulo . ']';
		}
	}
	list($sTabla1205, $sErrorT) = f1205_NombreTabla($_REQUEST['bmes'], $objDB);
	$sError = $sError . $sErrorT;
	if ($sError == '') {
		//Ver que el consecutivo no exista.
		$sSQL = 'SELECT masi05id FROM ' . $sTabla1205 . ' WHERE masi05consec=' . $_REQUEST['masi05consec_nuevo'] . ' AND masi05idproceso=' . $_REQUEST['masi05idproceso'] . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$sError = 'El consecutivo ' . $_REQUEST['masi05consec_nuevo'] . ' ya existe';
		}
	}
	if ($sError == '') {
		//Aplicar el cambio.
		$sSQL = 'UPDATE ' . $sTabla1205 . ' SET masi05consec=' . $_REQUEST['masi05consec_nuevo'] . ' WHERE masi05id=' . $_REQUEST['masi05id'] . '';
		$tabla = $objDB->ejecutasql($sSQL);
		$sDetalle = 'Cambia el consecutivo de ' . $_REQUEST['masi05consec'] . ' a ' . $_REQUEST['masi05consec_nuevo'] . '';
		$_REQUEST['masi05consec'] = $_REQUEST['masi05consec_nuevo'];
		$_REQUEST['masi05consec_nuevo'] = '';
		seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 8, $_REQUEST['masi05id'], $sDetalle, $objDB);
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
		list($sError, $iTipoError, $sDebugElimina) = f1205_db_Eliminar($_REQUEST['masi05id'], $_REQUEST['bloque'], $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugElimina;
	}
	if ($sError == '') {
		$_REQUEST['paso'] = -1;
		$sError = $ETI['msg_itemeliminado'];
		$iTipoError = 1;
	}
}
// Procesar un grupo de poblacion.
if ($_REQUEST['paso'] == 21) {
	$_REQUEST['paso'] = 2;
	if ($sError == '') {
		$id = numeros_validar($_REQUEST['idprocesa']);
		if ($id != $_REQUEST['idprocesa']) {
			$sError = 'No fue posible determinar los datos de origen.';
		} else {
			if ((int)$id == 0) {
				$sError = 'No fue posible determinar los datos de origen.';
			}
		}
	}
	if ($sError == '') {
		list($iCantidad, $sError, $iTipoError, $sDebugP) = f1206_Procesar($_REQUEST['masi05id'], $_REQUEST['bloque'], $_REQUEST['idprocesa'], $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugP;
		$_REQUEST['masi05total_usuarios'] = $_REQUEST['masi05total_usuarios'] + $iCantidad;
	}
	if ($sError == '') {
		$sError = $ETI['msg_procesoterminado'];
		$iTipoError = 1;
	}
}
//limpiar la pantalla
if ($_REQUEST['paso'] == -1) {
	$_REQUEST['masi05idproceso'] = $_REQUEST['bproceso'];
	$_REQUEST['masi05consec'] = '';
	$_REQUEST['masi05consec_nuevo'] = '';
	$_REQUEST['masi05id'] = '';
	$_REQUEST['masi05estado'] = 0;
	$_REQUEST['masi05asunto'] = '';
	$_REQUEST['masi05cuerpo'] = '';
	$_REQUEST['masi05admiterpta'] = 0;
	$_REQUEST['masi05correorpta'] = '';
	$_REQUEST['masi05firma'] = '';
	$_REQUEST['masi05idusuario'] = $idTercero;
	$_REQUEST['masi05idusuario_td'] = $APP->tipo_doc;
	$_REQUEST['masi05idusuario_doc'] = '';
	$_REQUEST['masi05fecha'] = $iHoy;
	$_REQUEST['masi05hora'] = fecha_hora();
	$_REQUEST['masi05min'] = fecha_minuto();
	$_REQUEST['masi05unidadfunc'] = 0;
	$_REQUEST['masi05zona'] = 0;
	$_REQUEST['masi05centro'] = 0;
	$_REQUEST['masi05escuela'] = 0;
	$_REQUEST['masi05programa'] = 0;
	$_REQUEST['masi05idperiodo'] = 0;
	$_REQUEST['masi05curso'] = 0;
	$_REQUEST['masi05docente'] = 0;
	$_REQUEST['masi05total_usuarios'] = 0;
	$_REQUEST['masi05total_envios'] = 0;
	$_REQUEST['masi05tiponotifica'] = 11;
	$_REQUEST['masi05periodicidad'] = 0;
	$_REQUEST['masi05idrelacion'] = 0;
	$_REQUEST['masi05idrelacion2'] = 0;
	$_REQUEST['masi05idrelacion3'] = 0;
	$_REQUEST['bmes'] = date("Ym");
	$_REQUEST['paso'] = 0;
	switch ($_REQUEST['masi05idproceso']) {
		case 2741: // Postulados a grados
			$_REQUEST['masi05idrelacion2'] = -1;
			break;
	}
}
if ($bLimpiaHijos) {
	$_REQUEST['masi06idmensaje'] = '';
	$_REQUEST['masi06consec'] = '';
	$_REQUEST['masi06id'] = '';
	$_REQUEST['masi06zona'] = 0;
	$_REQUEST['masi06centro'] = 0;
	$_REQUEST['masi06escuela'] = 0;
	$_REQUEST['masi06nivelforma'] = 0;
	$_REQUEST['masi06programa'] = 0;
	$_REQUEST['masi06est_condicion'] = 0;
	$_REQUEST['masi06sexo'] = 0;
	$_REQUEST['masi06idperiodo'] = 0;
	$_REQUEST['masi06curso'] = 0;
	$_REQUEST['masi06docente'] = 0;
	$_REQUEST['masi06unidadfunc'] = 0;
	$_REQUEST['masi06agnogrado'] = 0;
	$_REQUEST['masi07idmensaje'] = '';
	$_REQUEST['masi07consec'] = '';
	$_REQUEST['masi07id'] = '';
	$_REQUEST['masi07titulo'] = '';
	$_REQUEST['masi07idorigen'] = 0;
	$_REQUEST['masi07idarchivo'] = 0;
	$_REQUEST['masi08idmensaje'] = '';
	$_REQUEST['masi08idtercero'] = 0; //$idTercero;
	$_REQUEST['masi08idtercero_td'] = $APP->tipo_doc;
	$_REQUEST['masi08idtercero_doc'] = '';
	$_REQUEST['masi08idfecha'] = $iHoy;
	$_REQUEST['masi08id'] = '';
	$_REQUEST['masi08idpoblacion'] = '';
	$_REQUEST['masi08fechaenvio'] = 0;
	$_REQUEST['masi08horaenvio'] = 0;
	$_REQUEST['masi08minenvio'] = 0;
	$_REQUEST['masi08idsmtp'] = 0;
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
$bEdita1206 = false;
$bEdita1207 = false;
$bEdita1208 = false;
$bAplicaUnidadFuncional = false;
$bAplicaEscuela = false;
$bAplicaPrograma = false;
$bAplicaPeriodo = false;
$bAplicaCurso = false;
$bAplicaGrado = false;
$bBloqueado = false;
$bGestionaPoblacion = false;
$bPermiteRepetir = false;
$bRelacion1 = false;
$bRelacion2 = false;
$bRelacion3 = false;
switch ($_REQUEST['masi05idproceso']) {
	case 0: // - Ninguno
		break;
	case 2: // - Funcionarios
	case 3: // - Contratistas
		$bAplicaUnidadFuncional = true;
		break;
	case 11: // - Aspirantes
	case 2306: // - Acompañamiento académico
	case 2307: // - Seguimiento académico
	case 12229: // - Convocados
		$bAplicaEscuela = true;
		$bAplicaPrograma = true;
		$bAplicaPeriodo = true;
		break;
	case 12: // - Estudiantes
	case 13: // - Estudiantes ausentes
	case 2209: // - Estudiantes del programa
		$bAplicaEscuela = true;
		$bAplicaPrograma = true;
		$bAplicaPeriodo = true;
		$bAplicaCurso = true;
		break;
	case 17: // - Egresados
		$bAplicaEscuela = true;
		$bAplicaPrograma = true;
		$bAplicaGrado = true;
		break;
	case 2741: // - Postulados a grados
		$bAplicaEscuela = true;
		$bAplicaPrograma = true;
		$bRelacion1 = true;
		$bRelacion2 = true;
		break;
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
	$bPuedeGuardar = false;
	$bBloqueado = true;
	$bGestionaPoblacion = f1205_GestionaPoblacion($_REQUEST['masi05idproceso'], $_REQUEST['masi05estado']);
	switch ($_REQUEST['masi05estado']) {
		case 0: // Abierto
			$bPuedeGuardar = true;
			$bConEliminar = true;
			$bConBotonCerrar = true;
			$bDevuelve = false;
			$bEdita1206 = true;
			$bEdita1207 = true;
			$bEdita1208 = true;
			list($sNomTabla1208, $sErrorH) = f1208_NombreTabla($_REQUEST['bloque'], $objDB);
			if ($sErrorH == '') {
				$sSQL = 'SELECT 1 FROM ' . $sNomTabla1208 . ' WHERE masi08idmensaje=' . $_REQUEST['masi05id'] . ' LIMIT 0, 1';
				$tabla08 = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($tabla08) == 0) {
					$bBloqueado = false;
				}
			}
			$sError = $sError . $sErrorH;
			break;
		case 3: // Completo
			list($bPuedeAbrir, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 17, $idTercero, $objDB);
			break;
		case 5: // En proceso
		case 7: // Enviado
			break;
		case 9: // Descartado
			break;
	}
}
//DATOS PARA COMPLETAR EL FORMULARIO
if ($_REQUEST['bloque'] != $_REQUEST['bmes']) {
	$_REQUEST['bloque'] = $_REQUEST['bmes'];
	if ($_REQUEST['bloque'] != '') {
		list($sErrorM, $sDebugA) = f1200_ArmarEstructura($_REQUEST['bmes'], $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugA;
		if ($bDebug && ($sErrorM != '')) {
			$sDebug = $sDebug . log_debug('Error desde la estructura: <span class="rojo">' . $sErrorM . '</span>');
		}
		list($sErrorM, $sDebugA) = f1200_RevisarTablaPoblacion($_REQUEST['bmes'], $objDB, $bDebug);
		$sError = $sError . $sErrorM;
		$sDebug = $sDebug . $sDebugA;
	}
}
// lOS AÑOS LOS TOMAMOS DE LA TABLA QUE TIENE LAP PARTICION.
$iAgno = (int) substr($_REQUEST['bmes'], 0, 4);
$iAgnoIni = $iAgno;
$iAgnoFin = $iAgno + 1;
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
if ($_REQUEST['masi05id'] == '') {
	$objCombos->nuevo('masi05idproceso', $_REQUEST['masi05idproceso'], false);
	$objCombos->sAccion = 'cargaproceso()';
	$sSQL = 'SELECT masi72id AS id, masi72nombre AS nombre FROM masi72proceso ORDER BY masi72id';
	$sHTML = $objCombos->html($sSQL, $objDB);
} else {
	$masi05idproceso_nombre = '{' . $ETI['msg_ninguno'] . '}';
	$sSQL = 'SELECT masi72nombre FROM masi72proceso WHERE masi72id=' . $_REQUEST['masi05idproceso'];
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$masi05idproceso_nombre = cadena_notildes($fila['masi72nombre']);
	}
	$sHTML = html_oculto('masi05idproceso', $_REQUEST['masi05idproceso'], $masi05idproceso_nombre);
}
$html_masi05idproceso = $sHTML;
$masi05estado_nombre = '{' . $_REQUEST['masi05estado'] . '}';
$sSQL = 'SELECT unad96nombre, unad96etiqueta FROM unad96estado WHERE unad96idmodulo=1205 AND unad96id=' . $_REQUEST['masi05estado'];
$tabla = $objDB->ejecutasql($sSQL);
if ($objDB->nf($tabla) > 0) {
	$fila = $objDB->sf($tabla);
	$masi05estado_nombre = cadena_notildes($fila['unad96nombre']);
	if ($sIdioma != 'es') {
		$masi05estado_nombre = Etiqueta_Valor(1205, $fila['unad96etiqueta'], $sIdioma, $objDB);
	}
}
$html_masi05estado = html_oculto('masi05estado', $_REQUEST['masi05estado'], $masi05estado_nombre);
$objCombos->nuevo('masi05admiterpta', $_REQUEST['masi05admiterpta'], true, $ETI['no'], 0);
$objCombos->sAccion = 'admiterpta()';
$objCombos->addItem(1, $ETI['si']);
//$objCombos->addArreglo($amasi05admiterpta, $imasi05admiterpta);
$sSQL = '';
$html_masi05admiterpta = $objCombos->html($sSQL, $objDB);
$objCombos->nuevo('masi05firma', $_REQUEST['masi05firma'], true, '{' . $ETI['msg_ninguna'] . '}', 0);
$objCombos->bEsCombobox = true;
$sSQL = 'SELECT masi09id AS id, masi09nombre AS nombre FROM masi09firma ORDER BY masi09nombre';
$html_masi05firma = $objCombos->html($sSQL, $objDB);
list($masi05idusuario_rs, $_REQUEST['masi05idusuario'], $_REQUEST['masi05idusuario_td'], $_REQUEST['masi05idusuario_doc']) = html_tercero($_REQUEST['masi05idusuario_td'], $_REQUEST['masi05idusuario_doc'], $_REQUEST['masi05idusuario'], 0, $objDB);
$bOculto = true;
$html_masi05idusuario = html_DivTerceroV8('masi05idusuario', $_REQUEST['masi05idusuario_td'], $_REQUEST['masi05idusuario_doc'], $bOculto, $objDB, $objCombos, 0, $ETI['ing_doc']);
list($masi05tiponotifica_nombre, $sErrorDet) = tabla_campoxid('masi73tiponoti', 'masi73nombre', 'masi73id', $_REQUEST['masi05tiponotifica'], '{' . $ETI['msg_sindato'] . '}', $objDB);
$html_masi05tiponotifica = html_oculto('masi05tiponotifica', $_REQUEST['masi05tiponotifica'], $masi05tiponotifica_nombre);
if ($bPermiteRepetir) {
	$objCombos->nuevo('masi05periodicidad', $_REQUEST['masi05periodicidad'], true, $amasi05periodicidad[0], 0);
	$objCombos->addArreglo($amasi05periodicidad, $imasi05periodicidad);
	$sSQL = '';
	$html_masi05periodicidad = $objCombos->html($sSQL, $objDB);
}
if ($bRelacion1) {
	$et_masi05idrelacion = '- -';
	$objCombos->nuevo('masi05idrelacion', $_REQUEST['masi05idrelacion'], true, '{' . $ETI['msg_ninguna'] . '}', 0);
	$sSQL = '';
	switch ($_REQUEST['masi05idproceso']) {
		case 2741: // Postulados a grados
			$et_masi05idrelacion = $ETI['masi05idrelacion_2711'];
			$sSQL = 'SELECT grad01id AS id, CONCAT(grad01agno, ".", grad01consec, " - ", grad01nombre) AS nombre FROM grad01cohortes WHERE grad01id>0 ORDER BY grad01fechagrado DESC';
			break;
	}
	$html_masi05idrelacion = $objCombos->html($sSQL, $objDB);
}
if ($bRelacion2) {
	$et_masi05idrelacion2 = '- -';
	$et_vacio = '{' . $ETI['msg_ninguna'] . '}';
	$vr_vacio = 0;
	$sSQL = '';
	switch ($_REQUEST['masi05idproceso']) {
		case 2741: // Postulados a grados
			$et_masi05idrelacion2 = $ETI['masi05idrelacion2_2711'];
			$et_vacio = '{' . $ETI['msg_todos'] . '}';
			$vr_vacio = -1;
			$sSQL = 'SELECT grad45id AS id, grad45nombre AS nombre FROM grad45estadosolgrad grad45id';
			break;
	}
	$objCombos->nuevo('masi05idrelacion2', $_REQUEST['masi05idrelacion2'], true, $et_vacio, $vr_vacio);
	$html_masi05idrelacion2 = $objCombos->html($sSQL, $objDB);
}
if ($bRelacion3) {
	$et_masi05idrelacion3 = '- -';
	$objCombos->nuevo('masi05idrelacion3', $_REQUEST['masi05idrelacion3'], true, '{' . $ETI['msg_ninguna'] . '}', 0);
	$sSQL = '';
	$html_masi05idrelacion3 = $objCombos->html($sSQL, $objDB);
}
if ((int)$_REQUEST['paso'] == 0) {
} else {
}
if ($bEdita1206) {
	if ($bAplicaUnidadFuncional) {
		$html_masi06unidadfunc = f1206_HTMLComboV2_masi06unidadfunc($objDB, $objCombos, $_REQUEST['masi06unidadfunc']);
	}
	$objCombos->nuevo('masi06zona', $_REQUEST['masi06zona'], true, '{' . $ETI['msg_todas'] . '}', 0);
	$objCombos->sAccion = 'carga_combo_masi06centro();';
	$sSQL = 'SELECT unad23id AS id, unad23nombre AS nombre FROM unad23zona WHERE unad23id>0 ORDER BY unad23conestudiantes DESC, unad23nombre';
	$html_masi06zona = $objCombos->html($sSQL, $objDB);
	$html_masi06centro = f1206_HTMLComboV2_masi06centro($objDB, $objCombos, $_REQUEST['masi06centro'], $_REQUEST['masi06zona']);
	if ($bAplicaEscuela) {
		$objCombos->nuevo('masi06escuela', $_REQUEST['masi06escuela'], true, '{' . $ETI['msg_todas'] . '}', 0);
		$objCombos->sAccion = 'carga_combo_masi06programa();';
		$sSQL = 'SELECT core12id AS id, core12nombre AS nombre FROM core12escuela WHERE core12id>0 AND core12tieneestudiantes="S" ORDER BY core12nombre';
		$html_masi06escuela = $objCombos->html($sSQL, $objDB);
	}
	if ($bAplicaPrograma) {
		$objCombos->nuevo('masi06nivelforma', $_REQUEST['masi06nivelforma'], true, '{' . $ETI['msg_todos'] . '}', 0);
		$objCombos->sAccion = 'carga_combo_masi06programa();';
		$sSQL = 'SELECT core22id AS id, core22nombre AS nombre FROM core22nivelprograma WHERE core22id>0 ORDER BY core22orden, core22nombre';
		$html_masi06nivelforma = $objCombos->html($sSQL, $objDB);
		$html_masi06programa = f1206_HTMLComboV2_masi06programa($objDB, $objCombos, $_REQUEST['masi06programa'], $_REQUEST['masi06escuela'], $_REQUEST['masi06nivelforma']);
	}
	$objCombos->nuevo('masi06est_condicion', $_REQUEST['masi06est_condicion'], true, $amasi06est_condicion[0], 0);
	switch($idProceso) {
		case 0: // - Ninguno
			//$objCombos->addArreglo($amasi06est_condicion, $imasi06est_condicion);
			break;
		case 2: // - Funcionarios
			break;
		case 3: // - Contratistas
			break;
		case 11: // - Aspirantes
			break;
		case 12: // - Estudiantes
			break;
		case 13: // - Estudiantes ausentes
			break;
		case 17: // - Egresados
			break;
		case 2209: // - Estudiantes del programa
			break;
		case 2306: // - Acompañamiento académico
			break;
		case 2307: // - Seguimiento académico
			break;
		case 2741: // - Postulados a grados
			break;
		case 12229: // - Convocados
			break;
	}
	$sSQL = '';
	$html_masi06est_condicion = $objCombos->html($sSQL, $objDB);
	$objCombos->nuevo('masi06sexo', $_REQUEST['masi06sexo'], true, '{' . $ETI['msg_todos'] . '}', 0);
	$objCombos->addArreglo($amasi06sexo, $imasi06sexo);
	$sSQL = '';
	$html_masi06sexo = $objCombos->html($sSQL, $objDB);
	if ($bAplicaPeriodo) {
		$html_masi06idperiodo = f1206_HTMLComboV2_masi06idperiodo($objDB, $objCombos, $_REQUEST['masi06idperiodo']);
	}
	if ($bAplicaCurso) {
		$html_masi06curso = f1206_HTMLComboV2_masi06curso($objDB, $objCombos, $_REQUEST['masi06curso'], $_REQUEST['masi06idperiodo'], $_REQUEST['masi06programa'], $_REQUEST['masi06escuela']);
		$masi06docente_nombre = $_REQUEST['masi06docente'];
		$html_masi06docente = html_oculto('masi06docente', $_REQUEST['masi06docente'], $masi06docente_nombre);
	}
	if ($bAplicaGrado) {
		$iAgnoIniGrado = 1989;
		$iAgnoFinGrado = fecha_agno();
		$objCombos->nuevo('masi06agnogrado', $_REQUEST['masi06agnogrado'], true, '{' . $ETI['msg_todos'] . '}', 0);
		$objCombos->numeros($iAgnoIniGrado, $iAgnoFinGrado, 1);
		$sSQL = '';
		$html_masi06agnogrado = $objCombos->html($sSQL, $objDB);
	}
}
if ($bEdita1207) {
}
if ($bEdita1208 && $bGestionaPoblacion) {
	list($masi08idtercero_rs, $_REQUEST['masi08idtercero'], $_REQUEST['masi08idtercero_td'], $_REQUEST['masi08idtercero_doc']) = html_tercero($_REQUEST['masi08idtercero_td'], $_REQUEST['masi08idtercero_doc'], $_REQUEST['masi08idtercero'], 0, $objDB);
	$bOculto = true;
	if ((int)$_REQUEST['masi08id'] == 0) {
		$bOculto = false;
	}
	$html_masi08idtercero = html_DivTerceroV8('masi08idtercero', $_REQUEST['masi08idtercero_td'], $_REQUEST['masi08idtercero_doc'], $bOculto, $objDB, $objCombos, 1, $ETI['ing_doc']);
	list($sTabla1206, $sErrorH) = f1206_NombreTabla($_REQUEST['bloque'], $objDB);
	$sError = $sError . $sErrorH;
	$html_masi08idpoblacion = '';
	if ($sError == '') {
		$objCombos->nuevo('masi08idpoblacion', $_REQUEST['masi08idpoblacion'], true, '{' . $ETI['msg_ninguno'] . '}', 0);
		$sSQL = 'SELECT masi06id AS id, masi06consec AS nombre FROM ' . $sTabla1206 . ' WHERE masi06idmensaje=' . $_REQUEST['masi05id'] .  ' ORDER BY masi06consec DESC';
		$html_masi08idpoblacion = $objCombos->html($sSQL, $objDB);
	}
	if (false) {
		$objCombos->nuevo('masi08idsmtp', $_REQUEST['masi08idsmtp'], true, '{' . $ETI['msg_ninguno'] . '}', 0);
		$sSQL = 'SELECT unad69id AS id, unad69titulo AS nombre FROM unad69smtp ORDER BY unad69titulo';
		$html_masi08idsmtp = $objCombos->html($sSQL, $objDB);
	} else {
		$masi08idsmtp_nombre = '&nbsp;';
		if ((int)$_REQUEST['masi08idsmtp'] != 0) {
			list($masi08idsmtp_nombre, $sErrorDet) = tabla_campoxid('unad69smtp', 'unad69titulo', 'unad69id', $_REQUEST['masi08idsmtp'], '{' . $ETI['msg_sindato'] . '}', $objDB);
		}
		$html_masi08idsmtp = html_oculto('masi08idsmtp', $_REQUEST['masi08idsmtp'], $masi08idsmtp_nombre);
	}
}
//Alistar datos adicionales
$id_rpt = 0;
//$id_rpt=reportes_id(_Identificador_Tipo_Reporte_, $objDB);
$objCombos->nuevo('bmes', $_REQUEST['bmes'], true, '{' . $ETI['msg_seleccione'] . '}');
$objCombos->sAccion = 'paginarf1205()';
$sSQL = 'SHOW TABLES LIKE "masi05mensajes%"';
$tbase = $objDB->ejecutasql($sSQL);
while ($fbase = $objDB->sf($tbase)) {
	$sNomTabla05 = substr($fbase[0], 15);
	$sEtiquetaTabla = substr($sNomTabla05, 0, 4) . ' - '. substr($sNomTabla05, 4);
	$objCombos->addItem($sNomTabla05, $sEtiquetaTabla);
}
$sSQL = '';
$html_bmes = $objCombos->html($sSQL, $objDB);
$objCombos->nuevo('bunidadfunc', $_REQUEST['bunidadfunc'], true, '{' . $ETI['msg_todos'] . '}');
$objCombos->bEsCombobox = true;
$objCombos->sAccion = 'paginarf1205()';
$objCombos->iAncho = 400;
$sSQL = f226_ConsultaCombo();
$html_bunidadfunc = $objCombos->html($sSQL, $objDB);
$objCombos->nuevo('bzona', $_REQUEST['bzona'], true, '{' . $ETI['msg_todos'] . '}');
$objCombos->sAccion = 'carga_combo_bcentro();';
$sSQL = 'SELECT unad23id AS id, unad23nombre AS nombre FROM unad23zona ORDER BY unad23nombre';
$html_bzona = $objCombos->html($sSQL, $objDB);
$html_bcentro = f1205_HTMLComboV2_bcentro($objDB, $objCombos, $_REQUEST['bcentro'], $_REQUEST['bzona']);
if ($bAplicaEscuela) {
	$objCombos->nuevo('bescuela', $_REQUEST['bescuela'], true, '{' . $ETI['msg_todos'] . '}');
	$objCombos->sAccion = 'carga_combo_bprograma();';
	$sSQL = 'SELECT core12id AS id, core12nombre AS nombre FROM core12escuela WHERE core12id>0 AND core12tieneestudiantes="S" ORDER BY core12nombre';
	$html_bescuela = $objCombos->html($sSQL, $objDB);
}
if ($bAplicaPrograma) {
	$html_bprograma = f1205_HTMLComboV2_bprograma($objDB, $objCombos, $_REQUEST['bprograma'], $_REQUEST['bescuela']);
}
if ($idProceso == 0) {
	$objCombos->nuevo('bproceso', $_REQUEST['bproceso'], false);
	$objCombos->sAccion = 'limpiapagina()';
	$sSQL = 'SELECT masi72id AS id, masi72nombre AS nombre FROM masi72proceso ORDER BY masi72id';
	$html_bproceso = $objCombos->html($sSQL, $objDB);
}
if ((int)$_REQUEST['paso'] > 0) {
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
//Cargar las tablas de datos
$aParametros[0] = ''; //$_REQUEST['p1_1205'];
$aParametros[97] = $_REQUEST['bmes'];
$aParametros[100] = $idTercero;
$aParametros[101] = $_REQUEST['paginaf1205'];
$aParametros[102] = $_REQUEST['lppf1205'];
$aParametros[103] = $_REQUEST['basunto'];
$aParametros[104] = $_REQUEST['bcuerpo'];
$aParametros[105] = $_REQUEST['bfechainicia'];
$aParametros[106] = $_REQUEST['bfechafinal'];
$aParametros[107] = $_REQUEST['bunidadfunc'];
$aParametros[108] = $_REQUEST['bzona'];
$aParametros[109] = $_REQUEST['bcentro'];
$aParametros[110] = $_REQUEST['bescuela'];
$aParametros[111] = $_REQUEST['bprograma'];
$aParametros[112] = $_REQUEST['bcurso'];
$aParametros[113] = $_REQUEST['bproceso'];
list($sTabla1205, $sDebugTabla) = f1205_TablaDetalleV2($aParametros, $objDB, $bDebug);
$sDebug = $sDebug . $sDebugTabla;
$sTabla1206 = '';
$sTabla1207 = '';
$sTabla1208 = '';
if ($_REQUEST['paso'] != 0) {
	//Poblacion
	$aParametros1206[0] = $_REQUEST['masi05id'];
	$aParametros1206[97] = $_REQUEST['bloque'];
	$aParametros1206[100] = $idTercero;
	$aParametros1206[101] = $_REQUEST['paginaf1206'];
	$aParametros1206[102] = $_REQUEST['lppf1206'];
	//$aParametros1206[103] = $_REQUEST['bnombre1206'];
	//$aParametros1206[104] = $_REQUEST['blistar1206'];
	list($sTabla1206, $sDebugTabla) = f1206_TablaDetalleV2($aParametros1206, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugTabla;
	//Anexo
	$aParametros1207[0] = $_REQUEST['masi05id'];
	$aParametros1207[97] = $_REQUEST['bloque'];
	$aParametros1207[100] = $idTercero;
	$aParametros1207[101] = $_REQUEST['paginaf1207'];
	$aParametros1207[102] = $_REQUEST['lppf1207'];
	//$aParametros1207[103] = $_REQUEST['bnombre1207'];
	//$aParametros1207[104] = $_REQUEST['blistar1207'];
	list($sTabla1207, $sDebugTabla) = f1207_TablaDetalleV2($aParametros1207, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugTabla;
	//Destinatario
	$aParametros1208[0] = $_REQUEST['masi05id'];
	$aParametros1208[97] = $_REQUEST['bloque'];
	$aParametros1208[100] = $idTercero;
	$aParametros1208[101] = $_REQUEST['paginaf1208'];
	$aParametros1208[102] = $_REQUEST['lppf1208'];
	//$aParametros1208[103] = $_REQUEST['bnombre1208'];
	//$aParametros1208[104] = $_REQUEST['blistar1208'];
	list($sTabla1208, $sDebugTabla) = f1208_TablaDetalleV2($aParametros1208, $objDB, $bDebug);
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
			window.document.frmlista.consulta.value = window.document.frmedita.consulta_1205.value;
			window.document.frmlista.titulos.value = window.document.frmedita.titulos_1205.value;
			window.document.frmlista.nombrearchivo.value = 'Mensajes masivos';
			window.document.frmlista.submit();
		} else {
			ModalMensaje("<?php echo $ERR['6']; ?>");
		}
	}

	function asignarvariables() {
		window.document.frmimpp.separa.value = window.document.frmedita.csv_separa.value.trim();
		window.document.frmimpp.v3.value = window.document.frmedita.basunto.value;
		window.document.frmimpp.v4.value = window.document.frmedita.bcuerpo.value;
		window.document.frmimpp.v5.value = window.document.frmedita.bfechainicia.value;
		window.document.frmimpp.v6.value = window.document.frmedita.bfechafinal.value;
		window.document.frmimpp.v7.value = window.document.frmedita.bunidadfunc.value;
		window.document.frmimpp.v8.value = window.document.frmedita.bzona.value;
		window.document.frmimpp.v9.value = window.document.frmedita.bcentro.value;
		window.document.frmimpp.v10.value = window.document.frmedita.bescuela.value;
		window.document.frmimpp.v11.value = window.document.frmedita.bprograma.value;
		window.document.frmimpp.v12.value = window.document.frmedita.bcurso.value;
		window.document.frmimpp.v13.value = window.document.frmedita.bproceso.value;
		window.document.frmimpp.v97.value = window.document.frmedita.bmes.value;
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
			window.document.frmimpp.action = '<?php echo $APP->rutacomun; ?>e1205_ss.php';
			window.document.frmimpp.submit();
		} else {
			ModalMensaje(sError);
		}
	}

	function imprimep() {
		if (window.document.frmedita.seg_5.value == 1) {
			asignarvariables();
			window.document.frmimpp.action = '<?php echo $APP->rutacomun; ?>p1205.php';
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
		datos[1] = window.document.frmedita.masi05idproceso.value;
		datos[2] = window.document.frmedita.masi05consec.value;
		datos[97] = window.document.frmedita.bmes.value;
		if ((datos[1] != '') && (datos[2] != '')) {
			xajax_f1205_ExisteDato(datos);
		}
	}

	function cargadato(llave1, llave2) {
		window.document.frmedita.masi05idproceso.value = String(llave1);
		window.document.frmedita.masi05consec.value = String(llave2);
		window.document.frmedita.paso.value = 1;
		window.document.frmedita.submit();
	}

	function cargaridf1205(llave1) {
		window.document.frmedita.masi05id.value = String(llave1);
		window.document.frmedita.paso.value = 3;
		window.document.frmedita.submit();
	}

	function carga_combo_masi05centro() {
		let params = new Array();
		params[0] = window.document.frmedita.masi05zona.value;
		document.getElementById('div_masi05centro').innerHTML = '<b>Procesando datos, por favor espere...</b><input id="masi05centro" name="masi05centro" type="hidden" value="" />';
		xajax_f1205_Combomasi05centro(params);
	}

	function carga_combo_masi05programa() {
		let params = new Array();
		params[0] = window.document.frmedita.masi05escuela.value;
		document.getElementById('div_masi05programa').innerHTML = '<b>Procesando datos, por favor espere...</b><input id="masi05programa" name="masi05programa" type="hidden" value="" />';
		xajax_f1205_Combomasi05programa(params);
	}

	function carga_combo_masi05curso() {
		let params = new Array();
		params[0] = window.document.frmedita.masi05programa.value;
		document.getElementById('div_masi05curso').innerHTML = '<b>Procesando datos, por favor espere...</b><input id="masi05curso" name="masi05curso" type="hidden" value="" />';
		xajax_f1205_Combomasi05curso(params);
	}

	function carga_combo_bcentro() {
		let params = new Array();
		params[0] = window.document.frmedita.bzona.value;
		document.getElementById('div_bcentro').innerHTML = '<b>Procesando datos, por favor espere...</b><input id="bcentro" name="bcentro" type="hidden" value="" />';
		xajax_f1205_Combobcentro(params);
	}

	function carga_combo_bprograma() {
		let params = new Array();
		params[0] = window.document.frmedita.bescuela.value;
		document.getElementById('div_bprograma').innerHTML = '<b>Procesando datos, por favor espere...</b><input id="bprograma" name="bprograma" type="hidden" value="" />';
		xajax_f1205_Combobprograma(params);
	}

	function paginarf1205() {
		let params = new Array();
		params[97] = window.document.frmedita.bmes.value;
		params[99] = window.document.frmedita.debug.value;
		params[100] = <?php echo $idTercero; ?>;
		params[101] = window.document.frmedita.paginaf1205.value;
		params[102] = window.document.frmedita.lppf1205.value;
		params[103] = window.document.frmedita.basunto.value;
		params[104] = window.document.frmedita.bcuerpo.value;
		params[105] = window.document.frmedita.bfechainicia.value;
		params[106] = window.document.frmedita.bfechafinal.value;
		params[107] = window.document.frmedita.bunidadfunc.value;
		params[108] = window.document.frmedita.bzona.value;
		params[109] = window.document.frmedita.bcentro.value;
		params[110] = window.document.frmedita.bescuela.value;
		params[111] = window.document.frmedita.bprograma.value;
		params[112] = window.document.frmedita.bcurso.value;
		params[113] = window.document.frmedita.bproceso.value;
		document.getElementById('div_f1205detalle').innerHTML = '<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf1205" name="paginaf1205" type="hidden" value="' + params[101] + '" /><input id="lppf1205" name="lppf1205" type="hidden" value="' + params[102] + '" />';
		xajax_f1205_HtmlTabla(params);
	}
<?php
if ($bConBotonCerrar) {
?>
	function enviacerrar() {
		ModalConfirmV2('<?php echo $ETI['msg_cierre1205']; ?>', () => {
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
if ($bPuedeAbrir) {
?>
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
		document.getElementById("masi05idproceso").focus();
	}

	function buscarV2016(sCampo) {
		window.document.frmedita.iscroll.value = window.scrollY;
		expandesector(98);
		window.document.frmedita.scampobusca.value = sCampo;
		let params = new Array();
		params[1] = sCampo;
		//params[2] = window.document.frmedita.iagno.value;
		//params[3] = window.document.frmedita.itipo.value;
		xajax_f1205_Busquedas(params);
	}

	function Devuelve(sValor) {
		let sCampo = window.document.frmedita.scampobusca.value;
		if (sCampo == 'masi05idusuario') {
			ter_traerxid('masi05idusuario', sValor);
		}
		if (sCampo == 'masi08idtercero') {
			ter_traerxid('masi08idtercero', sValor);
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
		if (ref == 1207) {
			if (sRetorna != '') {
				window.document.frmedita.masi07idorigen.value = window.document.frmedita.div96v1.value;
				window.document.frmedita.masi07idarchivo.value = sRetorna;
				verboton('beliminamasi07idarchivo', 'block');
			}
			archivo_lnk(window.document.frmedita.masi07idorigen.value, window.document.frmedita.masi07idarchivo.value, 'div_masi07idarchivo');
			paginarf1207();
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
	
	function procesarf1206(id) {
		MensajeAlarmaV2('<?php echo $ETI['msg_ejecutando']; ?>', 2);
		expandesector(98);
		window.document.frmedita.idprocesa.value = id;
		window.document.frmedita.paso.value = 21;
		window.document.frmedita.submit();
	}

	function cargaproceso() {
		window.document.frmedita.bproceso.value = window.document.frmedita.masi05idproceso.value;
		limpiapagina();
	}

	function admiterpta() {
		let sMuestra = 'none';
		let iValor = window.document.frmedita.masi05admiterpta.value;
		if (iValor == 1) {
			sMuestra = 'block';
		}
		document.getElementById('div_masi05correorpta').style.display = sMuestra;
	}
</script>
<?php
if ($_REQUEST['paso'] != 0) {
?>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>jsi/js1206.js?v=4a"></script>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>jsi/js1207.js?v=2"></script>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>jsi/js1208.js?v=2b"></script>
<?php
}
?>
<form id="frmimpp" name="frmimpp" method="post" action="<?php echo $APP->rutacomun; ?>p1205.php" target="_blank" style="display:none">
<input id="r" name="r" type="hidden" value="1205" />
<input id="id1205" name="id1205" type="hidden" value="<?php echo $_REQUEST['masi05id']; ?>" />
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
<input id="bloque" name="bloque" type="hidden" value="<?php echo $_REQUEST['bloque']; ?>" />
<input id="seg_5" name="seg_5" type="hidden" value="<?php echo $seg_5; ?>" />
<input id="seg_6" name="seg_6" type="hidden" value="<?php echo $seg_6; ?>" />
<input id="idprocesa" name="idprocesa" type="hidden" value="" />
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
echo $objForma->htmlExpande(1205, $_REQUEST['boculta1205'], $ETI['bt_mostrar'], $ETI['bt_ocultar']);
$sEstiloDiv = '';
if ($_REQUEST['boculta1205'] != 0) {
	$sEstiloDiv = ' style="display:none;"';
}
?>
</div>
<div id="div_p1205"<?php echo $sEstiloDiv; ?>>
<?php
}
//Mostrar formulario para editar
?>
<label class="Label130"<?php echo $sOcultaConsec; ?>>
<?php
echo $ETI['masi05consec'];
?>
</label>
<label class="Label130"<?php echo $sOcultaConsec; ?>>
<?php
if ($_REQUEST['paso'] != 2) {
?>
<input id="masi05consec" name="masi05consec" type="text" value="<?php echo $_REQUEST['masi05consec']; ?>" onchange="RevisaLlave()" class="cuatro" />
<?php
} else {
	echo html_oculto('masi05consec', $_REQUEST['masi05consec'], formato_numero($_REQUEST['masi05consec']));
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
echo $ETI['masi05id'];
?>
</label>
<label class="Label60"<?php echo $sOcultaId; ?>>
<?php
	echo html_oculto('masi05id', $_REQUEST['masi05id'], formato_numero($_REQUEST['masi05id']));
?>
</label>
<label class="Label90">
<?php
echo $ETI['masi05idproceso'];
?>
</label>
<label class="Label250">
<?php
echo $html_masi05idproceso;
?>
</label>
<label class="Label90">
<?php
echo $ETI['masi05estado'];
?>
</label>
<label class="Label220">
<?php
echo $html_masi05estado;
?>
</label>
<label class="L">
<?php
echo $ETI['masi05asunto'];
?>

<input id="masi05asunto" name="masi05asunto" type="text" value="<?php echo $_REQUEST['masi05asunto']; ?>" maxlength="250" class="L" placeholder="<?php echo $ETI['ing_campo'] . $ETI['masi05asunto']; ?>" />
</label>
<label class="txtAreaEXTRA">
<?php
echo $ETI['masi05cuerpo'];
?>
<textarea id="masi05cuerpo" name="masi05cuerpo" placeholder="<?php echo $ETI['ing_campo'] . $ETI['masi05cuerpo']; ?>"><?php echo $_REQUEST['masi05cuerpo']; ?></textarea>
</label>

<?php
if ($bRelacion1) {
?>
<label class="Label160">
<?php
echo $et_masi05idrelacion;
?>
</label>
<label>
<?php
echo $html_masi05idrelacion;
?>
</label>
<?php
	echo html_salto();
} else {
?>
<input id="masi05idrelacion" name="masi05idrelacion" type="hidden" value="<?php echo $_REQUEST['masi05idrelacion']; ?>" />
<?php
}
if ($bRelacion2) {
?>
<label class="Label160">
<?php
echo $et_masi05idrelacion2;
?>
</label>
<label>
<?php
echo $html_masi05idrelacion2;
?>
</label>
<?php
	echo html_salto();
} else {
?>
<input id="masi05idrelacion2" name="masi05idrelacion2" type="hidden" value="<?php echo $_REQUEST['masi05idrelacion2']; ?>" />
<?php
}
if ($bRelacion3) {
?>
<label class="Label160">
<?php
echo $et_masi05idrelacion3;
?>
</label>
<label>
<?php
echo $html_masi05idrelacion3;
?>
</label>
<?php
	echo html_salto();
} else {
?>
<input id="masi05idrelacion3" name="masi05idrelacion3" type="hidden" value="<?php echo $_REQUEST['masi05idrelacion3']; ?>" />
<?php
}
?>

<label class="Label160">
<?php
echo $ETI['masi05fecha'];
?>
</label>
<div class="Campo250">
<?php
if ($bBloqueado) {
	echo html_oculto('masi05fecha', $_REQUEST['masi05fecha'], fecha_desdenumero($_REQUEST['masi05fecha']));
} else {
	echo html_FechaEnNumero('masi05fecha', $_REQUEST['masi05fecha'], false, '', $iAgnoIni, $iAgnoFin);
}
?>
</div>
<?php
if (false) {
	echo $objForma->htmlBotonSolo('bmasi05fecha_hoy', 'btMiniHoy', "fecha_AsignarNum('masi05fecha', " . $iHoy . ")", $ETI['bt_hoy']);
}
?>
<label class="Label90">
<?php
echo $ETI['masi05hora'];
?>
</label>
<div class="campo_HoraMin" id="div_masi05hora">
<?php
echo html_HoraMin('masi05hora', $_REQUEST['masi05hora'], 'masi05min', $_REQUEST['masi05min'], $bBloqueado);
?>
</div>

<label class="Label30"></label>
<label class="Label160">
<?php
echo $ETI['masi05admiterpta'];
?>
</label>
<label class="Label60">
<?php
echo $html_masi05admiterpta;
?>
</label>
<?php
$sEstiloDiv = '';
if ($_REQUEST['masi05admiterpta'] == 0) {
	$sEstiloDiv = ' style="display:none;"';
}
?>
<div id="div_masi05correorpta"<?php echo $sEstiloDiv; ?>>
<label class="L">
<?php
echo $ETI['masi05correorpta'];
?>
<input id="masi05correorpta" name="masi05correorpta" type="text" value="<?php echo $_REQUEST['masi05correorpta']; ?>" maxlength="200" class="L" placeholder="<?php echo $ETI['ing_campo'] . $ETI['masi05correorpta']; ?>" />
</label>
</div>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['masi05tiponotifica'];
?>
</label>
<label>
<div id="div_masi05tiponotifica">
<?php
echo $html_masi05tiponotifica;
?>
</div>
</label>
<?php
if ($bPermiteRepetir) {
?>
<label class="Label130">
<?php
echo $ETI['masi05periodicidad'];
?>
</label>
<label>
<?php
echo $html_masi05periodicidad;
?>
</label>
<div class="salto1px"></div>
<?php
} else {
?>
<input id="masi05periodicidad" name="masi05periodicidad" type="hidden" value="<?php echo $_REQUEST['masi05periodicidad']; ?>" />
<?php
}
?>
<label class="Label130">
<?php
echo $ETI['masi05firma'];
?>
</label>
<label>
<div class="field">
<?php
echo $html_masi05firma;
?>
</div>
</label>
<div class="salto1px"></div>
<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo $ETI['masi05idusuario'];
?>
</label>
<div class="salto1px"></div>
<input id="masi05idusuario" name="masi05idusuario" type="hidden" value="<?php echo $_REQUEST['masi05idusuario']; ?>" />
<div id="div_masi05idusuario_llaves">
<?php
echo $html_masi05idusuario;
?>
</div>
<div class="salto1px"></div>
<div id="div_masi05idusuario" class="L"><?php echo $masi05idusuario_rs; ?></div>
<div class="salto1px"></div>
</div>
<input id="masi05unidadfunc" name="masi05unidadfunc" type="hidden" value="<?php echo $_REQUEST['masi05unidadfunc']; ?>" />
<input id="masi05zona" name="masi05zona" type="hidden" value="<?php echo $_REQUEST['masi05zona']; ?>" />
<input id="masi05centro" name="masi05centro" type="hidden" value="<?php echo $_REQUEST['masi05centro']; ?>" />
<input id="masi05escuela" name="masi05escuela" type="hidden" value="<?php echo $_REQUEST['masi05escuela']; ?>" />
<input id="masi05programa" name="masi05programa" type="hidden" value="<?php echo $_REQUEST['masi05programa']; ?>" />
<input id="masi05idperiodo" name="masi05idperiodo" type="hidden" value="<?php echo $_REQUEST['masi05idperiodo']; ?>" />
<input id="masi05curso" name="masi05curso" type="hidden" value="<?php echo $_REQUEST['masi05curso']; ?>" />
<input id="masi05docente" name="masi05docente" type="hidden" value="<?php echo $_REQUEST['masi05docente']; ?>" />
<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="Label130">
<?php
echo $ETI['masi05total_usuarios'];
?>
</label>
<label class="Label130">
<div id="div_masi05total_usuarios">
<?php
echo html_oculto('masi05total_usuarios', $_REQUEST['masi05total_usuarios']);
?>
</div>
</label>
<label class="Label130">
<?php
echo $ETI['masi05total_envios'];
?>
</label>
<label class="Label130">
<div id="div_masi05total_envios">
<?php
echo html_oculto('masi05total_envios', $_REQUEST['masi05total_envios']);
?>
</div>
</label>
<div class="salto1px"></div>
</div>
<?php
// -- Inicia Grupo campos 1206 Poblacion
?>
<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo $ETI['titulo_1206'];
?>
</label>
<?php
if ($_REQUEST['paso'] == 2) {
	if ($bEdita1206) {
?>
<input id="boculta1206" name="boculta1206" type="hidden" value="<?php echo $_REQUEST['boculta1206']; ?>" />
<div class="ir_derecha"<?php echo $sAnchoExpandeContrae; ?>>
<?php
if (false) {
?>
<label class="Label30">
<input id="btexcel1206" name="btexcel1206" type="button" value="Exportar" class="btMiniExcel" onclick="imprime1206();" title="Exportar" />
</label>
<?php
}
echo $objForma->htmlExpande(1206, $_REQUEST['boculta1206'], $ETI['bt_mostrar'], $ETI['bt_ocultar']);
$sEstiloDiv = '';
if ($_REQUEST['boculta1206'] != 0) {
	$sEstiloDiv = ' style="display:none;"';
}
?>
</div>
<div class="salto1px"></div>
<div id="div_p1206"<?php echo $sEstiloDiv; ?>>
<label class="Label130"<?php echo $sOcultaConsec; ?>>
<?php
echo $ETI['masi06consec'];
?>
</label>
<label class="Label130"<?php echo $sOcultaConsec; ?>>
<div id="div_masi06consec">
<?php
if ((int)$_REQUEST['masi06id'] == 0) {
?>
<input id="masi06consec" name="masi06consec" type="text" value="<?php echo $_REQUEST['masi06consec']; ?>" onchange="revisaf1206()" class="cuatro" />
<?php
} else {
	echo html_oculto('masi06consec', $_REQUEST['masi06consec'], formato_numero($_REQUEST['masi06consec']));
}
?>
</div>
</label>
<label class="Label60"<?php echo $sOcultaId; ?>>
<?php
echo $ETI['masi06id'];
?>
</label>
<label class="Label60"<?php echo $sOcultaId; ?>>
<div id="div_masi06id">
<?php
	echo html_oculto('masi06id', $_REQUEST['masi06id'], formato_numero($_REQUEST['masi06id']));
?>
</div>
</label>

<div class="salto1px"></div>
<div class="GrupoCampos520">
<?php
if ($bAplicaUnidadFuncional) {
?>
<label class="Label160">
<?php
echo $ETI['masi06unidadfunc'];
?>
</label>
<div id="div_masi06unidadfunc" class="field">
<?php
echo $html_masi06unidadfunc;
?>
</div>
<?php
echo html_salto();
} else {
?>
<input id="masi06unidadfunc" name="masi06unidadfunc" type="hidden" value="<?php echo $_REQUEST['masi06unidadfunc']; ?>" />
<?php
}
?>
<label class="Label160">
<?php
echo $ETI['masi06zona'];
?>
</label>
<label>
<?php
echo $html_masi06zona;
?>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['masi06centro'];
?>
</label>
<label>
<div id="div_masi06centro" class="field">
<?php
echo $html_masi06centro;
?>
</div>
</label>
<div class="salto1px"></div>
</div>

<div class="GrupoCampos450">
<label class="Label90">
<?php
echo $ETI['masi06est_condicion'];
?>
</label>
<label>
<?php
echo $html_masi06est_condicion;
?>
</label>
<div class="salto1px"></div>
<label class="Label90">
<?php
echo $ETI['masi06sexo'];
?>
</label>
<label>
<?php
echo $html_masi06sexo;
?>
</label>
<div class="salto1px"></div>
</div>

<?php
if ($bAplicaEscuela) {
?>
<div class="GrupoCampos520">
<label class="Label160">
<?php
echo $ETI['masi06escuela'];
?>
</label>
<label>
<?php
echo $html_masi06escuela;
?>
</label>
<?php
} else {
?>
<input id="masi06escuela" name="masi06escuela" type="hidden" value="<?php echo $_REQUEST['masi06escuela']; ?>" />
<?php
}
if ($bAplicaPrograma) {
?>
<label class="Label160">
<?php
echo $ETI['masi06nivelforma'];
?>
</label>
<label>
<?php
echo $html_masi06nivelforma;
?>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['masi06programa'];
?>
</label>
<label>
<div id="div_masi06programa" class="field">
<?php
echo $html_masi06programa;
?>
</div>
</label>
<?php
} else {
?>
<input id="masi06nivelforma" name="masi06nivelforma" type="hidden" value="<?php echo $_REQUEST['masi06nivelforma']; ?>" />
<input id="masi06programa" name="masi06programa" type="hidden" value="<?php echo $_REQUEST['masi06programa']; ?>" />
<?php
}
?>
<?php
if ($bAplicaEscuela) {
	echo html_salto();
?>
</div>
<?php
}
?>

<?php
if ($bAplicaPeriodo) {
?>
<div class="GrupoCampos450">
<label class="Label90">
<?php
echo $ETI['masi06idperiodo'];
?>
</label>
<label>
<div id="div_masi06idperiodo" class="field">
<?php
echo $html_masi06idperiodo;
?>
</div>
</label>
<?php
} else {
?>
<input id="masi06idperiodo" name="masi06idperiodo" type="hidden" value="<?php echo $_REQUEST['masi06idperiodo']; ?>" />
<?php
}
if ($bAplicaCurso) {
?>
<label class="Label90">
<?php
echo $ETI['masi06curso'];
?>
</label>
<label>
<div id="div_masi06curso" class="field">
<?php
echo $html_masi06curso;
echo html_salto();
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label90">
<?php
echo $ETI['masi06docente'];
?>
</label>
<label>
<div id="div_masi06docente">
<?php
echo $html_masi06docente;
?>
</div>
</label>
<?php
} else {
?>
<input id="masi06curso" name="masi06curso" type="hidden" value="<?php echo $_REQUEST['masi06curso']; ?>" />
<input id="masi06docente" name="masi06docente" type="hidden" value="<?php echo $_REQUEST['masi06docente']; ?>" />
<?php
}
?>
<?php
if ($bAplicaPeriodo) {
	echo html_salto();
?>
</div>
<?php
}
?>

<?php
if ($bAplicaGrado) {
?>
<div class="GrupoCampos450">
<label class="Label160">
<?php
echo $ETI['masi06agnogrado'];
?>
</label>
<label class="Label90">
<?php
echo $html_masi06agnogrado;
?>
</label>
<?php
} else {
?>
<input id="masi06agnogrado" name="masi06agnogrado" type="hidden" value="<?php echo $_REQUEST['masi06agnogrado']; ?>" />
<?php
}
?>
<?php
if ($bAplicaGrado) {
	echo html_salto();
?>
</div>
<?php
}
?>

<div class="salto5px"></div>
<label class="Label130">&nbsp;</label>
<?php
$sEstiloElimina = 'display:none;';
if ((int)$_REQUEST['masi06id'] != 0) {
	$sEstiloElimina = 'inline-block;';
}
echo $objForma->htmlBotonSolo('bguarda1206', 'btMiniGuardar', 'guardaf1206()', $ETI['bt_mini_guardar_1206'], 30);
echo $objForma->htmlBotonSolo('blimpia1206', 'btMiniLimpiar', 'limpiaf1206()', $ETI['bt_mini_limpiar_1206'], 30);
echo $objForma->htmlBotonSolo('belimina1206', 'btMiniEliminar', 'eliminaf1206()', $ETI['bt_mini_eliminar_1206'], 30, $sEstiloElimina);
//Este es el cierre del div_p1206
?>
<div class="salto1px"></div>
</div>
<?php
		} //Termina el segundo bloque  condicional - bloque editar.
?>
<div class="salto1px"></div>
<?php
if (false) {
?>
<div class="ir_derecha GrupoCamposAyuda">
<label class="Label130">
<?php
echo $ETI['msg_nombre'];
?>
</label>
<label>
<input id="bnombre1206" name="bnombre1206" type="text" value="<?php echo $_REQUEST['bnombre1206']; ?>" onchange="paginarf1206()" />
</label>
<label class="Label130">
<?php
echo $ETI['msg_Listar'];
?>
</label>
<label>
<?php
echo $html_blistar1206;
?>
</label>
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
<?php
}
?>
<div id="div_f1206detalle">
<?php
echo $sTabla1206;
?>
</div>
<?php
}
?>
<div class="salto1px"></div>
</div>
<?php
// -- Termina Grupo campos 1206 Poblacion
?>
<?php
// -- Inicia Grupo campos 1207 Anexo
?>
<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo $ETI['titulo_1207'];
?>
</label>
<?php
if ($_REQUEST['paso'] == 2) {
	if ($bEdita1207) {
?>
<input id="boculta1207" name="boculta1207" type="hidden" value="<?php echo $_REQUEST['boculta1207']; ?>" />
<div class="ir_derecha"<?php echo $sAnchoExpandeContrae; ?>>
<?php
if (false) {
?>
<label class="Label30">
<input id="btexcel1207" name="btexcel1207" type="button" value="Exportar" class="btMiniExcel" onclick="imprime1207();" title="Exportar" />
</label>
<?php
}
echo $objForma->htmlExpande(1207, $_REQUEST['boculta1207'], $ETI['bt_mostrar'], $ETI['bt_ocultar']);
$sEstiloDiv = '';
if ($_REQUEST['boculta1207'] != 0) {
	$sEstiloDiv = ' style="display:none;"';
}
?>
</div>
<div class="salto1px"></div>
<div id="div_p1207"<?php echo $sEstiloDiv; ?>>
<label class="Label130"<?php echo $sOcultaConsec; ?>>
<?php
echo $ETI['masi07consec'];
?>
</label>
<label class="Label130"<?php echo $sOcultaConsec; ?>>
<div id="div_masi07consec">
<?php
if ((int)$_REQUEST['masi07id'] == 0) {
?>
<input id="masi07consec" name="masi07consec" type="text" value="<?php echo $_REQUEST['masi07consec']; ?>" onchange="revisaf1207()" class="cuatro" />
<?php
} else {
	echo html_oculto('masi07consec', $_REQUEST['masi07consec'], formato_numero($_REQUEST['masi07consec']));
}
?>
</div>
</label>
<label class="Label60"<?php echo $sOcultaId; ?>>
<?php
echo $ETI['masi07id'];
?>
</label>
<label class="Label60"<?php echo $sOcultaId; ?>>
<div id="div_masi07id">
<?php
	echo html_oculto('masi07id', $_REQUEST['masi07id'], formato_numero($_REQUEST['masi07id']));
?>
</div>
</label>
<label class="L">
<?php
echo $ETI['masi07titulo'];
?>

<input id="masi07titulo" name="masi07titulo" type="text" value="<?php echo $_REQUEST['masi07titulo']; ?>" maxlength="200" class="L" placeholder="<?php echo $ETI['ing_campo'] . $ETI['masi07titulo']; ?>" />
</label>
<input id="masi07idorigen" name="masi07idorigen" type="hidden" value="<?php echo $_REQUEST['masi07idorigen']; ?>" />
<input id="masi07idarchivo" name="masi07idarchivo" type="hidden" value="<?php echo $_REQUEST['masi07idarchivo']; ?>" />
<input id="masi07idarchivo_up" name="masi07idarchivo_up" type="hidden" value="<?php echo html_lnkupload(1207, $_REQUEST['masi07id'], '_' . $_REQUEST['bloque']); ?>" />
<div class="GrupoCampos300">
<div class="salto1px"></div>
<div id="div_masi07idarchivo" class="Campo300">
<?php
echo html_lnkarchivo((int)$_REQUEST['masi07idorigen'], (int)$_REQUEST['masi07idarchivo']);
?>
</div>
<?php
$sEstiloAnexa = '';
$sEstiloElimina = ' style="display:none;"';
if ((int)$_REQUEST['masi07id'] == 0) {
	$sEstiloAnexa = ' style="display:none;"';
}
if ((int)$_REQUEST['masi07idarchivo'] != 0) {
	$sEstiloElimina = '';
}
echo $objForma->htmlBotonSolo('banexamasi07idarchivo', 'btMiniAnexar', 'carga_masi07idarchivo()', $ETI['bt_mini_cargararchivo'], 30, $sEstiloAnexa);
echo $objForma->htmlBotonSolo('beliminamasi07idarchivo', 'btMiniEliminar', 'eliminamasi07idarchivo()', $ETI['bt_mini_eliminararchivo'], 30, $sEstiloElimina);
?>
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
<label class="Label130">&nbsp;</label>
<?php
$sEstiloElimina = 'display:none;';
if ((int)$_REQUEST['masi07id'] != 0) {
	$sEstiloElimina = 'inline-block;';
}
echo $objForma->htmlBotonSolo('bguarda1207', 'btMiniGuardar', 'guardaf1207()', $ETI['bt_mini_guardar_1207'], 30);
echo $objForma->htmlBotonSolo('blimpia1207', 'btMiniLimpiar', 'limpiaf1207()', $ETI['bt_mini_limpiar_1207'], 30);
echo $objForma->htmlBotonSolo('belimina1207', 'btMiniEliminar', 'eliminaf1207()', $ETI['bt_mini_eliminar_1207'], 30, $sEstiloElimina);
//Este es el cierre del div_p1207
?>
<div class="salto1px"></div>
</div>
<?php
		} //Termina el segundo bloque  condicional - bloque editar.
?>
<div class="salto1px"></div>
<?php
if (false) {
?>
<div class="ir_derecha GrupoCamposAyuda">
<label class="Label130">
<?php
echo $ETI['msg_nombre'];
?>
</label>
<label>
<input id="bnombre1207" name="bnombre1207" type="text" value="<?php echo $_REQUEST['bnombre1207']; ?>" onchange="paginarf1207()" />
</label>
<label class="Label130">
<?php
echo $ETI['msg_Listar'];
?>
</label>
<label>
<?php
echo $html_blistar1207;
?>
</label>
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
<?php
}
?>
<div id="div_f1207detalle">
<?php
echo $sTabla1207;
?>
</div>
<?php
}
?>
<div class="salto1px"></div>
</div>
<?php
// -- Termina Grupo campos 1207 Anexo
?>
<?php
// -- Inicia Grupo campos 1208 Destinatario
?>
<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo $ETI['titulo_1208'];
?>
</label>
<?php
if ($_REQUEST['paso'] == 2) {
	if ($bEdita1208 && $bGestionaPoblacion) {
?>
<input id="boculta1208" name="boculta1208" type="hidden" value="<?php echo $_REQUEST['boculta1208']; ?>" />
<div class="ir_derecha"<?php echo $sAnchoExpandeContrae; ?>>
<?php
if (false) {
?>
<label class="Label30">
<input id="btexcel1208" name="btexcel1208" type="button" value="Exportar" class="btMiniExcel" onclick="imprime1208();" title="Exportar" />
</label>
<?php
}
echo $objForma->htmlExpande(1208, $_REQUEST['boculta1208'], $ETI['bt_mostrar'], $ETI['bt_ocultar']);
$sEstiloDiv = '';
if ($_REQUEST['boculta1208'] != 0) {
	$sEstiloDiv = ' style="display:none;"';
}
?>
</div>
<div class="salto1px"></div>
<div id="div_p1208"<?php echo $sEstiloDiv; ?>>
<div class="salto1px"></div>
<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo $ETI['masi08idtercero'];
?>
</label>
<div class="salto1px"></div>
<input id="masi08idtercero" name="masi08idtercero" type="hidden" value="<?php echo $_REQUEST['masi08idtercero']; ?>" />
<div id="div_masi08idtercero_llaves">
<?php
echo $html_masi08idtercero;
?>
</div>
<div class="salto1px"></div>
<div id="div_masi08idtercero" class="L"><?php echo $masi08idtercero_rs; ?></div>
<div class="salto1px"></div>
</div>
<label class="Label130">
<?php
echo $ETI['masi08idfecha'];
?>
</label>
<div class="Campo220" id="div_masi08idfecha">
<?php
if ((int)$_REQUEST['masi08id'] == 0) {
	echo html_FechaEnNumero('masi08idfecha', $_REQUEST['masi08idfecha'], true, 'revisaf1208()'); //, false, '', $iAgnoIni, $iAgnoFin); //$bvacio, $accion
} else {
	echo html_oculto('masi08idfecha', $_REQUEST['masi08idfecha'], formato_FechaLargaDesdeNumero($_REQUEST['masi08idfecha']));
}
?>
</div>
<?php
if (false) {
	echo $objForma->htmlBotonSolo('bmasi08idfecha_hoy', 'btMiniHoy', "fecha_AsignarNum('masi08idfecha', " . $iHoy . ")", $ETI['bt_hoy']);
}
?>
<label class="Label60"<?php echo $sOcultaId; ?>>
<?php
echo $ETI['masi08id'];
?>
</label>
<label class="Label60"<?php echo $sOcultaId; ?>>
<div id="div_masi08id">
<?php
	echo html_oculto('masi08id', $_REQUEST['masi08id'], formato_numero($_REQUEST['masi08id']));
?>
</div>
</label>
<label class="Label130">
<?php
echo $ETI['masi08idpoblacion'];
?>
</label>
<label>
<?php
echo $html_masi08idpoblacion;
?>
</label>
<label class="Label130">
<?php
echo $ETI['masi08fechaenvio'];
?>
</label>
<label class="Label220">
<div id="div_masi08fechaenvio">
<?php
echo html_oculto('masi08fechaenvio', $_REQUEST['masi08fechaenvio'], fecha_desdenumero($_REQUEST['masi08fechaenvio'])); //formato_FechaLargaDesdeNumero
?>
</div>
</label>
<label class="Label130">
<?php
echo $ETI['masi08horaenvio'];
?>
</label>
<div class="campo_HoraMin" id="div_masi08horaenvio">
<?php
echo html_HoraMin('masi08horaenvio', $_REQUEST['masi08horaenvio'], 'masi08minenvio', $_REQUEST['masi08minenvio'], true);
?>
</div>
<label class="Label130">
<?php
echo $ETI['masi08idsmtp'];
?>
</label>
<label>
<div id="div_masi08idsmtp">
<?php
echo $html_masi08idsmtp;
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label130">&nbsp;</label>
<?php
$sEstiloElimina = 'display:none;';
if ((int)$_REQUEST['masi08id'] != 0) {
	$sEstiloElimina = 'inline-block;';
}
echo $objForma->htmlBotonSolo('bguarda1208', 'btMiniGuardar', 'guardaf1208()', $ETI['bt_mini_guardar_1208'], 30);
echo $objForma->htmlBotonSolo('blimpia1208', 'btMiniLimpiar', 'limpiaf1208()', $ETI['bt_mini_limpiar_1208'], 30);
echo $objForma->htmlBotonSolo('belimina1208', 'btMiniEliminar', 'eliminaf1208()', $ETI['bt_mini_eliminar_1208'], 30, $sEstiloElimina);
//Este es el cierre del div_p1208
?>
<div class="salto1px"></div>
</div>
<?php
		} //Termina el segundo bloque  condicional - bloque editar.
?>
<div class="salto1px"></div>
<?php
if (false) {
?>
<div class="ir_derecha GrupoCamposAyuda">
<label class="Label130">
<?php
echo $ETI['msg_nombre'];
?>
</label>
<label>
<input id="bnombre1208" name="bnombre1208" type="text" value="<?php echo $_REQUEST['bnombre1208']; ?>" onchange="paginarf1208()" />
</label>
<label class="Label130">
<?php
echo $ETI['msg_Listar'];
?>
</label>
<label>
<?php
echo $html_blistar1208;
?>
</label>
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
<?php
}
?>
<div id="div_f1208detalle">
<?php
echo $sTabla1208;
?>
</div>
<?php
}
?>
<div class="salto1px"></div>
</div>
<?php
// -- Termina Grupo campos 1208 Destinatario
?>
<?php
if (false) {
	//Ejemplo de boton de ayuda
	//echo html_BotonAyuda('NombreCampo');
	//echo html_DivAyudaLocal('NombreCampo');
}
if ($bConExpande) {
	//Este es el cierre del div_p1205
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
<?php
if ($idProceso == 0) {
?>
<label class="Label160">
<?php
echo $ETI['msg_bproceso'];
?>
</label>
<label>
<?php
echo $html_bproceso;
?>
</label>
<div class="salto1px"></div>
<?php
} else {
?>
<input id="bproceso" name="bproceso" type="hidden" value="<?php echo $_REQUEST['bproceso']; ?>" />
<?php
}
?>
<label class="Label160">
<?php
echo $ETI['msg_bmes'];
?>
</label>
<label>
<?php
echo $html_bmes;
?>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['msg_basunto'];
?>
</label>
<label>
<input id="basunto" name="basunto" type="text" value="<?php echo $_REQUEST['basunto']; ?>" onchange="paginarf1205()" autocomplete="off" />
</label>
<label class="Label90">
<?php
echo $ETI['msg_bcuerpo'];
?>
</label>
<label>
<input id="bcuerpo" name="bcuerpo" type="text" value="<?php echo $_REQUEST['bcuerpo']; ?>" onchange="paginarf1205()" autocomplete="off" />
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['msg_bfechainicia'];
?>
</label>
<label class="Label250">
<?php
echo html_FechaEnNumero('bfechainicia', $_REQUEST['bfechainicia'], true, 'paginarf1205()', $iAgnoIni);
?>
</label>
<label class="Label130">
<?php
echo $ETI['msg_bfechafinal'];
?>
</label>
<label class="Label250">
<?php
echo html_FechaEnNumero('bfechafinal', $_REQUEST['bfechafinal'], true, 'paginarf1205()', $iAgnoIni);
?>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['msg_bunidadfunc'];
?>
</label>
<div class="field">
<?php
echo $html_bunidadfunc;
echo html_salto();
?>
</div>
<label class="Label160">
<?php
echo $ETI['msg_bzona'];
?>
</label>
<label>
<?php
echo $html_bzona;
?>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['msg_bcentro'];
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
<?php
if ($bAplicaEscuela) {
?>
<label class="Label160">
<?php
echo $ETI['msg_bescuela'];
?>
</label>
<label>
<?php
echo $html_bescuela;
?>
</label>
<div class="salto1px"></div>
<?php
} else {
?>
<input id="bescuela" name="bescuela" type="hidden" value="<?php echo $_REQUEST['bescuela']; ?>" />
<?php
}
if ($bAplicaPrograma) {
?>
<label class="Label160">
<?php
echo $ETI['msg_bprograma'];
?>
</label>
<label>
<div id="div_bprograma" class="field">
<?php
echo $html_bprograma;
?>
</div>
</label>
<div class="salto1px"></div>
<?php
} else {
?>
<input id="bprograma" name="bprograma" type="hidden" value="<?php echo $_REQUEST['bprograma']; ?>" />
<?php
}
if ($bAplicaCurso) {
?>
<label class="Label160">
<?php
echo $ETI['msg_bcurso'];
?>
</label>
<label>
<input id="bcurso" name="bcurso" type="text" value="<?php echo $_REQUEST['bcurso']; ?>" onchange="paginarf1205()" autocomplete="off" />
</label>
<div class="salto1px"></div>
<?php
} else {
?>
<input id="bcurso" name="bcurso" type="hidden" value="<?php echo $_REQUEST['bcurso']; ?>" />
<?php
}
?>
</div>

<div class="salto1px"></div>
<?php
echo ' ' . $csv_separa;
?>
<div class="salto1px"></div>
<div id="div_f1205detalle">
<?php
echo $sTabla1205;
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
echo $ETI['msg_masi05consec'];
?>
</label>
<label class="Label90">
<?php
echo '<b>' . $_REQUEST['masi05consec'] . '</b>';
?>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['msg_masi05consec_nuevo'];
// onchange="RevisaConsec()"
?>
</label>
<label class="Label90">
<input id="masi05consec_nuevo" name="masi05consec_nuevo" type="text" value="<?php echo $_REQUEST['masi05consec_nuevo']; ?>" class="cuatro" />
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
<input id="titulo_1205" name="titulo_1205" type="hidden" value="<?php echo $sTituloModulo; ?>" />
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
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>js/jquery.autocomplete.css" type="text/css" />
<script language="javascript" src="<?php echo $APP->rutacomun; ?>js/jquery.autocomplete.js"></script>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>jodit/jodit.js"></script>
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>jodit/jodit.css" type="text/css" />
<?php
//if (false) {
//}
//if ($_REQUEST['paso'] == 0) {
?>
<script language="javascript">
	let editor = new Jodit('#masi05cuerpo',{
		height: 400,
		language: 'es',
		removeButtons: ['file', 'video', 'print', 'about']
	});
</script>
<?php
//}
?>
<script language="javascript" src="ac_1205.js"></script>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>unad_todas2024v2.js"></script>
<?php
forma_piedepagina();
// !TODO: Terminar de actualizar combos chosen, ajustar funciones en librería para envío de mensajes y función o librería que debe ejecutar el CRON
