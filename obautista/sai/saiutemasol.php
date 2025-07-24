<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2020 - 2025 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.24.1 martes, 11 de febrero de 2020
--- Modelo Versión 2.25.3 martes, 14 de julio de 2020
--- Modelo Versión 3.0.16 martes, 8 de julio de 2025
*/

/** Archivo saiutemasol.php.
 * Modulo 3003 saiu03temasol.
 * @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
 * @param debug=1 (Opcional), bandera para indicar si se generan datos de depuración
 * @date martes, 11 de febrero de 2020
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
$iMinVerDB = 8727;
$iCodModulo = 3003;
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
$mensajes_3003 = 'lg/lg_3003_' . $sIdioma . '.php';
if (!file_exists($mensajes_3003)) {
	$mensajes_3003 = 'lg/lg_3003_es.php';
}
require $mensajes_todas;
require $mensajes_3003;
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
$sTituloModulo = $ETI['titulo_3003'];
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
		header('Location:noticia.php?ret=saiutemasol.php');
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
$mensajes_3004 = 'lg/lg_3004_' . $sIdioma . '.php';
if (!file_exists($mensajes_3004)) {
	$mensajes_3004 = 'lg/lg_3004_es.php';
}
require $mensajes_3004;
// -- Si esta cargando la pagina por primer vez se revisa si requiere auditar y se manda a hacer un limpiar (paso -1)
if (isset($_REQUEST['paso']) == 0) {
	$_REQUEST['paso'] = -1;
	if ($audita[1]) {
		seg_auditaingreso($iCodModulo, $_SESSION['unad_id_tercero'], $objDB);
	}
}
// -- 3003 saiu03temasol
require 'lib3003.php';
// -- 3004 Anexos
require 'lib3004.php';
$xajax = new xajax();
$xajax->configure('javascript URI', $APP->rutacomun . 'xajax/');
$xajax->register(XAJAX_FUNCTION, 'unad11_Mostrar_v2');
$xajax->register(XAJAX_FUNCTION, 'unad11_TraerXid');
$xajax->register(XAJAX_FUNCTION, 'f3003_Combosaiu03idequiporesp1');
$xajax->register(XAJAX_FUNCTION, 'f3003_Combosaiu03idequiporesp2');
$xajax->register(XAJAX_FUNCTION, 'f3003_Combosaiu03idequiporesp3');
$xajax->register(XAJAX_FUNCTION, 'sesion_abandona_V2');
$xajax->register(XAJAX_FUNCTION, 'sesion_mantenerV4');
$xajax->register(XAJAX_FUNCTION, 'f3003_HtmlTabla');
$xajax->register(XAJAX_FUNCTION, 'f3003_ExisteDato');
$xajax->register(XAJAX_FUNCTION, 'f3003_Busquedas');
$xajax->register(XAJAX_FUNCTION, 'f3003_HtmlBusqueda');
$xajax->register(XAJAX_FUNCTION, 'elimina_archivo_saiu04idarchforma');
$xajax->register(XAJAX_FUNCTION, 'f3004_Guardar');
$xajax->register(XAJAX_FUNCTION, 'f3004_Traer');
$xajax->register(XAJAX_FUNCTION, 'f3004_Eliminar');
$xajax->register(XAJAX_FUNCTION, 'f3004_HtmlTabla');
$xajax->register(XAJAX_FUNCTION, 'f3004_PintarLlaves');
$xajax->register(XAJAX_FUNCTION, 'f3003_saiu03idliderrespon');
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
if (isset($_REQUEST['paginaf3003']) == 0) {
	$_REQUEST['paginaf3003'] = 1;
}
if (isset($_REQUEST['lppf3003']) == 0) {
	$_REQUEST['lppf3003'] = 20;
}
if (isset($_REQUEST['boculta3003']) == 0) {
	$_REQUEST['boculta3003'] = 0;
}
if (isset($_REQUEST['paginaf3004']) == 0) {
	$_REQUEST['paginaf3004'] = 1;
}
if (isset($_REQUEST['lppf3004']) == 0) {
	$_REQUEST['lppf3004'] = 20;
}
if (isset($_REQUEST['boculta3004']) == 0) {
	$_REQUEST['boculta3004'] = 0;
}
// -- Inicializar variables de datos.
if (isset($_REQUEST['saiu03tiposol']) == 0) {
	$_REQUEST['saiu03tiposol'] = 0;
}
if (isset($_REQUEST['saiu03consec']) == 0) {
	$_REQUEST['saiu03consec'] = '';
}
if (isset($_REQUEST['saiu03consec_nuevo']) == 0) {
	$_REQUEST['saiu03consec_nuevo'] = '';
}
if (isset($_REQUEST['saiu03id']) == 0) {
	$_REQUEST['saiu03id'] = '';
}
if (isset($_REQUEST['saiu03activo']) == 0) {
	$_REQUEST['saiu03activo'] = 'S';
}
if (isset($_REQUEST['saiu03titulo']) == 0) {
	$_REQUEST['saiu03titulo'] = '';
}
if (isset($_REQUEST['saiu03descripcion']) == 0) {
	$_REQUEST['saiu03descripcion'] = '';
}
if (isset($_REQUEST['saiu03ayuda']) == 0) {
	$_REQUEST['saiu03ayuda'] = '';
}
if (isset($_REQUEST['saiu03obligaconf']) == 0) {
	$_REQUEST['saiu03obligaconf'] = '';
}
if (isset($_REQUEST['saiu03numetapas']) == 0) {
	$_REQUEST['saiu03numetapas'] = 1;
}
if (isset($_REQUEST['saiu03nometapa1']) == 0) {
	$_REQUEST['saiu03nometapa1'] = '';
}
if (isset($_REQUEST['saiu03idunidadresp1']) == 0) {
	$_REQUEST['saiu03idunidadresp1'] = '';
}
if (isset($_REQUEST['saiu03idequiporesp1']) == 0) {
	$_REQUEST['saiu03idequiporesp1'] = '';
}
if (isset($_REQUEST['saiu03idliderrespon1']) == 0) {
	$_REQUEST['saiu03idliderrespon1'] = 0;
} // {$_SESSION['unad_id_tercero'];}
if (isset($_REQUEST['saiu03idliderrespon1_td']) == 0) {
	$_REQUEST['saiu03idliderrespon1_td'] = $APP->tipo_doc;
}
if (isset($_REQUEST['saiu03idliderrespon1_doc']) == 0) {
	$_REQUEST['saiu03idliderrespon1_doc'] = '';
}
if (isset($_REQUEST['saiu03tiemprespdias1']) == 0) {
	$_REQUEST['saiu03tiemprespdias1'] = '';
}
if (isset($_REQUEST['saiu03tiempresphoras1']) == 0) {
	$_REQUEST['saiu03tiempresphoras1'] = '';
}
if (isset($_REQUEST['saiu03nometapa2']) == 0) {
	$_REQUEST['saiu03nometapa2'] = '';
}
if (isset($_REQUEST['saiu03idunidadresp2']) == 0) {
	$_REQUEST['saiu03idunidadresp2'] = '';
}
if (isset($_REQUEST['saiu03idequiporesp2']) == 0) {
	$_REQUEST['saiu03idequiporesp2'] = '';
}
if (isset($_REQUEST['saiu03idliderrespon2']) == 0) {
	$_REQUEST['saiu03idliderrespon2'] = 0;
} // {$_SESSION['unad_id_tercero'];}
if (isset($_REQUEST['saiu03idliderrespon2_td']) == 0) {
	$_REQUEST['saiu03idliderrespon2_td'] = $APP->tipo_doc;
}
if (isset($_REQUEST['saiu03idliderrespon2_doc']) == 0) {
	$_REQUEST['saiu03idliderrespon2_doc'] = '';
}
if (isset($_REQUEST['saiu03tiemprespdias2']) == 0) {
	$_REQUEST['saiu03tiemprespdias2'] = '';
}
if (isset($_REQUEST['saiu03tiempresphoras2']) == 0) {
	$_REQUEST['saiu03tiempresphoras2'] = '';
}
if (isset($_REQUEST['saiu03nometapa3']) == 0) {
	$_REQUEST['saiu03nometapa3'] = '';
}
if (isset($_REQUEST['saiu03idunidadresp3']) == 0) {
	$_REQUEST['saiu03idunidadresp3'] = '';
}
if (isset($_REQUEST['saiu03idequiporesp3']) == 0) {
	$_REQUEST['saiu03idequiporesp3'] = '';
}
if (isset($_REQUEST['saiu03idliderrespon3']) == 0) {
	$_REQUEST['saiu03idliderrespon3'] = 0;
} // {$_SESSION['unad_id_tercero'];}
if (isset($_REQUEST['saiu03idliderrespon3_td']) == 0) {
	$_REQUEST['saiu03idliderrespon3_td'] = $APP->tipo_doc;
}
if (isset($_REQUEST['saiu03idliderrespon3_doc']) == 0) {
	$_REQUEST['saiu03idliderrespon3_doc'] = '';
}
if (isset($_REQUEST['saiu03tiemprespdias3']) == 0) {
	$_REQUEST['saiu03tiemprespdias3'] = '';
}
if (isset($_REQUEST['saiu03tiempresphoras3']) == 0) {
	$_REQUEST['saiu03tiempresphoras3'] = '';
}
if (isset($_REQUEST['saiu03otrosusaurios']) == 0) {
	$_REQUEST['saiu03otrosusaurios'] = '';
}
if (isset($_REQUEST['saiu03consupervisor']) == 0) {
	$_REQUEST['saiu03consupervisor'] = '';
}
if (isset($_REQUEST['saiu03anonimos']) == 0) {
	$_REQUEST['saiu03anonimos'] = '';
}
if (isset($_REQUEST['saiu03anexoslibres']) == 0) {
	$_REQUEST['saiu03anexoslibres'] = '';
}
if (isset($_REQUEST['saiu03moduloasociado']) == 0) {
	$_REQUEST['saiu03moduloasociado'] = 0;
}
if (isset($_REQUEST['saiu03nivelrespuesta']) == 0) {
	$_REQUEST['saiu03nivelrespuesta'] = 3;
}
if (isset($_REQUEST['saiu03consupervisor2']) == 0) {
	$_REQUEST['saiu03consupervisor2'] = 'N';
}
if (isset($_REQUEST['saiu03consupervisor3']) == 0) {
	$_REQUEST['saiu03consupervisor3'] = 'N';
}
if (isset($_REQUEST['saiu03infoprograma']) == 0) {
	$_REQUEST['saiu03infoprograma'] = '';
}
if (isset($_REQUEST['saiu03infoperiodos']) == 0) {
	$_REQUEST['saiu03infoperiodos'] = '';
}
if (isset($_REQUEST['saiu03requierepago']) == 0) {
	$_REQUEST['saiu03requierepago'] = '';
}
if (isset($_REQUEST['saiu03incluyemodelo']) == 0) {
	$_REQUEST['saiu03incluyemodelo'] = '';
}
if (isset($_REQUEST['saiu03modelo']) == 0) {
	$_REQUEST['saiu03modelo'] = '';
}
if (isset($_REQUEST['saiu03firmacertificada']) == 0) {
	$_REQUEST['saiu03firmacertificada'] = '';
}
if (isset($_REQUEST['saiu03ordenllamada']) == 0) {
	$_REQUEST['saiu03ordenllamada'] = '';
}
if (isset($_REQUEST['saiu03ordenchat']) == 0) {
	$_REQUEST['saiu03ordenchat'] = '';
}
if (isset($_REQUEST['saiu03ordencorreo']) == 0) {
	$_REQUEST['saiu03ordencorreo'] = '';
}
if (isset($_REQUEST['saiu03ordenpresencial']) == 0) {
	$_REQUEST['saiu03ordenpresencial'] = '';
}
if (isset($_REQUEST['saiu03ordensoporte']) == 0) {
	$_REQUEST['saiu03ordensoporte'] = '';
}
if (isset($_REQUEST['saiu03ordenpqrs']) == 0) {
	$_REQUEST['saiu03ordenpqrs'] = '';
}
if (isset($_REQUEST['saiu03ordentramites']) == 0) {
	$_REQUEST['saiu03ordentramites'] = '';
}
if (isset($_REQUEST['saiu03ordencorresp']) == 0) {
	$_REQUEST['saiu03ordencorresp'] = '';
}
if (isset($_REQUEST['saiu03ordenfav']) == 0) {
	$_REQUEST['saiu03ordenfav'] = 0;
}
if (isset($_REQUEST['saiu03reddeservicio']) == 0) {
	$_REQUEST['saiu03reddeservicio'] = 0;
}
$_REQUEST['saiu03tiposol'] = numeros_validar($_REQUEST['saiu03tiposol']);
$_REQUEST['saiu03consec'] = numeros_validar($_REQUEST['saiu03consec']);
$_REQUEST['saiu03id'] = numeros_validar($_REQUEST['saiu03id']);
$_REQUEST['saiu03activo'] = cadena_Validar($_REQUEST['saiu03activo']);
$_REQUEST['saiu03titulo'] = cadena_Validar($_REQUEST['saiu03titulo']);
$_REQUEST['saiu03descripcion'] = cadena_Validar($_REQUEST['saiu03descripcion']);
$_REQUEST['saiu03ayuda'] = cadena_Validar($_REQUEST['saiu03ayuda']);
$_REQUEST['saiu03obligaconf'] = cadena_Validar($_REQUEST['saiu03obligaconf']);
$_REQUEST['saiu03numetapas'] = numeros_validar($_REQUEST['saiu03numetapas']);
$_REQUEST['saiu03nometapa1'] = cadena_Validar($_REQUEST['saiu03nometapa1']);
$_REQUEST['saiu03idunidadresp1'] = numeros_validar($_REQUEST['saiu03idunidadresp1']);
$_REQUEST['saiu03idequiporesp1'] = numeros_validar($_REQUEST['saiu03idequiporesp1']);
$_REQUEST['saiu03idliderrespon1'] = numeros_validar($_REQUEST['saiu03idliderrespon1']);
$_REQUEST['saiu03idliderrespon1_td'] = cadena_Validar($_REQUEST['saiu03idliderrespon1_td']);
$_REQUEST['saiu03idliderrespon1_doc'] = cadena_Validar($_REQUEST['saiu03idliderrespon1_doc']);
$_REQUEST['saiu03tiemprespdias1'] = numeros_validar($_REQUEST['saiu03tiemprespdias1']);
$_REQUEST['saiu03tiempresphoras1'] = numeros_validar($_REQUEST['saiu03tiempresphoras1']);
$_REQUEST['saiu03nometapa2'] = cadena_Validar($_REQUEST['saiu03nometapa2']);
$_REQUEST['saiu03idunidadresp2'] = numeros_validar($_REQUEST['saiu03idunidadresp2']);
$_REQUEST['saiu03idequiporesp2'] = numeros_validar($_REQUEST['saiu03idequiporesp2']);
$_REQUEST['saiu03idliderrespon2'] = numeros_validar($_REQUEST['saiu03idliderrespon2']);
$_REQUEST['saiu03idliderrespon2_td'] = cadena_Validar($_REQUEST['saiu03idliderrespon2_td']);
$_REQUEST['saiu03idliderrespon2_doc'] = cadena_Validar($_REQUEST['saiu03idliderrespon2_doc']);
$_REQUEST['saiu03tiemprespdias2'] = numeros_validar($_REQUEST['saiu03tiemprespdias2']);
$_REQUEST['saiu03tiempresphoras2'] = numeros_validar($_REQUEST['saiu03tiempresphoras2']);
$_REQUEST['saiu03nometapa3'] = cadena_Validar($_REQUEST['saiu03nometapa3']);
$_REQUEST['saiu03idunidadresp3'] = numeros_validar($_REQUEST['saiu03idunidadresp3']);
$_REQUEST['saiu03idequiporesp3'] = numeros_validar($_REQUEST['saiu03idequiporesp3']);
$_REQUEST['saiu03idliderrespon3'] = numeros_validar($_REQUEST['saiu03idliderrespon3']);
$_REQUEST['saiu03idliderrespon3_td'] = cadena_Validar($_REQUEST['saiu03idliderrespon3_td']);
$_REQUEST['saiu03idliderrespon3_doc'] = cadena_Validar($_REQUEST['saiu03idliderrespon3_doc']);
$_REQUEST['saiu03tiemprespdias3'] = numeros_validar($_REQUEST['saiu03tiemprespdias3']);
$_REQUEST['saiu03tiempresphoras3'] = numeros_validar($_REQUEST['saiu03tiempresphoras3']);
$_REQUEST['saiu03otrosusaurios'] = cadena_Validar($_REQUEST['saiu03otrosusaurios']);
$_REQUEST['saiu03consupervisor'] = cadena_Validar($_REQUEST['saiu03consupervisor']);
$_REQUEST['saiu03anonimos'] = cadena_Validar($_REQUEST['saiu03anonimos']);
$_REQUEST['saiu03anexoslibres'] = cadena_Validar($_REQUEST['saiu03anexoslibres']);
$_REQUEST['saiu03moduloasociado'] = numeros_validar($_REQUEST['saiu03moduloasociado']);
$_REQUEST['saiu03nivelrespuesta'] = numeros_validar($_REQUEST['saiu03nivelrespuesta']);
$_REQUEST['saiu03consupervisor2'] = cadena_Validar($_REQUEST['saiu03consupervisor2']);
$_REQUEST['saiu03consupervisor3'] = cadena_Validar($_REQUEST['saiu03consupervisor3']);
$_REQUEST['saiu03infoprograma'] = numeros_validar($_REQUEST['saiu03infoprograma']);
$_REQUEST['saiu03infoperiodos'] = numeros_validar($_REQUEST['saiu03infoperiodos']);
$_REQUEST['saiu03requierepago'] = numeros_validar($_REQUEST['saiu03requierepago']);
$_REQUEST['saiu03incluyemodelo'] = numeros_validar($_REQUEST['saiu03incluyemodelo']);
$_REQUEST['saiu03modelo'] = cadena_Validar($_REQUEST['saiu03modelo']);
$_REQUEST['saiu03firmacertificada'] = numeros_validar($_REQUEST['saiu03firmacertificada']);
$_REQUEST['saiu03ordenllamada'] = numeros_validar($_REQUEST['saiu03ordenllamada']);
$_REQUEST['saiu03ordenchat'] = numeros_validar($_REQUEST['saiu03ordenchat']);
$_REQUEST['saiu03ordencorreo'] = numeros_validar($_REQUEST['saiu03ordencorreo']);
$_REQUEST['saiu03ordenpresencial'] = numeros_validar($_REQUEST['saiu03ordenpresencial']);
$_REQUEST['saiu03ordensoporte'] = numeros_validar($_REQUEST['saiu03ordensoporte']);
$_REQUEST['saiu03ordenpqrs'] = numeros_validar($_REQUEST['saiu03ordenpqrs']);
$_REQUEST['saiu03ordentramites'] = numeros_validar($_REQUEST['saiu03ordentramites']);
$_REQUEST['saiu03ordencorresp'] = numeros_validar($_REQUEST['saiu03ordencorresp']);
$_REQUEST['saiu03ordenfav'] = numeros_validar($_REQUEST['saiu03ordenfav']);
$_REQUEST['saiu03reddeservicio'] = numeros_validar($_REQUEST['saiu03reddeservicio']);
if ((int)$_REQUEST['paso'] > 0) {
	//Anexos
	if (isset($_REQUEST['saiu04idtema']) == 0) {
		$_REQUEST['saiu04idtema'] = '';
	}
	if (isset($_REQUEST['saiu04consec']) == 0) {
		$_REQUEST['saiu04consec'] = '';
	}
	if (isset($_REQUEST['saiu04id']) == 0) {
		$_REQUEST['saiu04id'] = '';
	}
	if (isset($_REQUEST['saiu04activo']) == 0) {
		$_REQUEST['saiu04activo'] = 'S';
	}
	if (isset($_REQUEST['saiu04orden']) == 0) {
		$_REQUEST['saiu04orden'] = '';
	}
	if (isset($_REQUEST['saiu04obligatorio']) == 0) {
		$_REQUEST['saiu04obligatorio'] = '';
	}
	if (isset($_REQUEST['saiu04titulo']) == 0) {
		$_REQUEST['saiu04titulo'] = '';
	}
	if (isset($_REQUEST['saiu04descripcion']) == 0) {
		$_REQUEST['saiu04descripcion'] = '';
	}
	if (isset($_REQUEST['saiu04idtipogd']) == 0) {
		$_REQUEST['saiu04idtipogd'] = '';
	}
	if (isset($_REQUEST['saiu04idorigenforma']) == 0) {
		$_REQUEST['saiu04idorigenforma'] = 0;
	}
	if (isset($_REQUEST['saiu04idarchforma']) == 0) {
		$_REQUEST['saiu04idarchforma'] = 0;
	}
	if (isset($_REQUEST['saiu04idetapa']) == 0) {
		$_REQUEST['saiu04idetapa'] = 1;
	}
	$_REQUEST['saiu04idtema'] = numeros_validar($_REQUEST['saiu04idtema']);
	$_REQUEST['saiu04consec'] = numeros_validar($_REQUEST['saiu04consec']);
	$_REQUEST['saiu04id'] = numeros_validar($_REQUEST['saiu04id']);
	$_REQUEST['saiu04activo'] = cadena_Validar($_REQUEST['saiu04activo']);
	$_REQUEST['saiu04orden'] = cadena_Validar($_REQUEST['saiu04orden']);
	$_REQUEST['saiu04obligatorio'] = cadena_Validar($_REQUEST['saiu04obligatorio']);
	$_REQUEST['saiu04titulo'] = cadena_Validar($_REQUEST['saiu04titulo']);
	$_REQUEST['saiu04descripcion'] = cadena_Validar($_REQUEST['saiu04descripcion']);
	$_REQUEST['saiu04idtipogd'] = numeros_validar($_REQUEST['saiu04idtipogd']);
	$_REQUEST['saiu04idorigenforma'] = numeros_validar($_REQUEST['saiu04idorigenforma']);
	$_REQUEST['saiu04idarchforma'] = numeros_validar($_REQUEST['saiu04idarchforma']);
	$_REQUEST['saiu04idetapa'] = numeros_validar($_REQUEST['saiu04idetapa']);
}
// Espacio para inicializar otras variables
if (isset($_REQUEST['csv_separa']) == 0) {
	$_REQUEST['csv_separa'] = ';';
}
if (isset($_REQUEST['bnombre']) == 0) {
	$_REQUEST['bnombre'] = '';
}
if (isset($_REQUEST['blistar']) == 0) {
	$_REQUEST['blistar'] = '';
}
if (isset($_REQUEST['bunidad']) == 0) {
	$_REQUEST['bunidad'] = '';
}
if (isset($_REQUEST['borden']) == 0) {
	$_REQUEST['borden'] = '';
}
if (isset($_REQUEST['btiposol']) == 0) {
	$_REQUEST['btiposol'] = '';
}
$_REQUEST['bnombre'] = cadena_Validar($_REQUEST['bnombre']);
$_REQUEST['bunidad'] = numeros_validar($_REQUEST['bunidad']);
$_REQUEST['borden'] = numeros_validar($_REQUEST['borden']);
$_REQUEST['btiposol'] = numeros_validar($_REQUEST['btiposol']);
if ((int)$_REQUEST['paso'] > 0) {
	//Anexos
	if (isset($_REQUEST['bnombre3004']) == 0) {
		$_REQUEST['bnombre3004'] = '';
	}
	//if (isset($_REQUEST['blistar3004'])==0){$_REQUEST['blistar3004']='';}
}
//Si Modifica o Elimina Cargar los campos
if (($_REQUEST['paso'] == 1) || ($_REQUEST['paso'] == 3)) {
	$_REQUEST['saiu03idliderrespon1_td'] = $APP->tipo_doc;
	$_REQUEST['saiu03idliderrespon1_doc'] = '';
	$_REQUEST['saiu03idliderrespon2_td'] = $APP->tipo_doc;
	$_REQUEST['saiu03idliderrespon2_doc'] = '';
	$_REQUEST['saiu03idliderrespon3_td'] = $APP->tipo_doc;
	$_REQUEST['saiu03idliderrespon3_doc'] = '';
	if ($_REQUEST['paso'] == 1) {
		$sSQLcondi = 'saiu03tiposol=' . $_REQUEST['saiu03tiposol'] . ' AND saiu03consec=' . $_REQUEST['saiu03consec'] . '';
	} else {
		$sSQLcondi = 'saiu03id=' . $_REQUEST['saiu03id'] . '';
	}
	$sSQL = 'SELECT * FROM saiu03temasol WHERE ' . $sSQLcondi;
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$_REQUEST['saiu03tiposol'] = $fila['saiu03tiposol'];
		$_REQUEST['saiu03consec'] = $fila['saiu03consec'];
		$_REQUEST['saiu03id'] = $fila['saiu03id'];
		$_REQUEST['saiu03activo'] = $fila['saiu03activo'];
		$_REQUEST['saiu03titulo'] = $fila['saiu03titulo'];
		$_REQUEST['saiu03descripcion'] = $fila['saiu03descripcion'];
		$_REQUEST['saiu03ayuda'] = $fila['saiu03ayuda'];
		$_REQUEST['saiu03obligaconf'] = $fila['saiu03obligaconf'];
		$_REQUEST['saiu03numetapas'] = $fila['saiu03numetapas'];
		$_REQUEST['saiu03nometapa1'] = $fila['saiu03nometapa1'];
		$_REQUEST['saiu03idunidadresp1'] = $fila['saiu03idunidadresp1'];
		$_REQUEST['saiu03idequiporesp1'] = $fila['saiu03idequiporesp1'];
		$_REQUEST['saiu03idliderrespon1'] = $fila['saiu03idliderrespon1'];
		$_REQUEST['saiu03tiemprespdias1'] = $fila['saiu03tiemprespdias1'];
		$_REQUEST['saiu03tiempresphoras1'] = $fila['saiu03tiempresphoras1'];
		$_REQUEST['saiu03nometapa2'] = $fila['saiu03nometapa2'];
		$_REQUEST['saiu03idunidadresp2'] = $fila['saiu03idunidadresp2'];
		$_REQUEST['saiu03idequiporesp2'] = $fila['saiu03idequiporesp2'];
		$_REQUEST['saiu03idliderrespon2'] = $fila['saiu03idliderrespon2'];
		$_REQUEST['saiu03tiemprespdias2'] = $fila['saiu03tiemprespdias2'];
		$_REQUEST['saiu03tiempresphoras2'] = $fila['saiu03tiempresphoras2'];
		$_REQUEST['saiu03nometapa3'] = $fila['saiu03nometapa3'];
		$_REQUEST['saiu03idunidadresp3'] = $fila['saiu03idunidadresp3'];
		$_REQUEST['saiu03idequiporesp3'] = $fila['saiu03idequiporesp3'];
		$_REQUEST['saiu03idliderrespon3'] = $fila['saiu03idliderrespon3'];
		$_REQUEST['saiu03tiemprespdias3'] = $fila['saiu03tiemprespdias3'];
		$_REQUEST['saiu03tiempresphoras3'] = $fila['saiu03tiempresphoras3'];
		$_REQUEST['saiu03otrosusaurios'] = $fila['saiu03otrosusaurios'];
		$_REQUEST['saiu03consupervisor'] = $fila['saiu03consupervisor'];
		$_REQUEST['saiu03anonimos'] = $fila['saiu03anonimos'];
		$_REQUEST['saiu03anexoslibres'] = $fila['saiu03anexoslibres'];
		$_REQUEST['saiu03moduloasociado'] = $fila['saiu03moduloasociado'];
		$_REQUEST['saiu03nivelrespuesta'] = $fila['saiu03nivelrespuesta'];
		$_REQUEST['saiu03consupervisor2'] = $fila['saiu03consupervisor2'];
		$_REQUEST['saiu03consupervisor3'] = $fila['saiu03consupervisor3'];
		$_REQUEST['saiu03infoprograma'] = $fila['saiu03infoprograma'];
		$_REQUEST['saiu03infoperiodos'] = $fila['saiu03infoperiodos'];
		$_REQUEST['saiu03requierepago'] = $fila['saiu03requierepago'];
		$_REQUEST['saiu03incluyemodelo'] = $fila['saiu03incluyemodelo'];
		$_REQUEST['saiu03modelo'] = $fila['saiu03modelo'];
		$_REQUEST['saiu03firmacertificada'] = $fila['saiu03firmacertificada'];
		$_REQUEST['saiu03ordenllamada'] = $fila['saiu03ordenllamada'];
		$_REQUEST['saiu03ordenchat'] = $fila['saiu03ordenchat'];
		$_REQUEST['saiu03ordencorreo'] = $fila['saiu03ordencorreo'];
		$_REQUEST['saiu03ordenpresencial'] = $fila['saiu03ordenpresencial'];
		$_REQUEST['saiu03ordensoporte'] = $fila['saiu03ordensoporte'];
		$_REQUEST['saiu03ordenpqrs'] = $fila['saiu03ordenpqrs'];
		$_REQUEST['saiu03ordentramites'] = $fila['saiu03ordentramites'];
		$_REQUEST['saiu03ordencorresp'] = $fila['saiu03ordencorresp'];
		$_REQUEST['saiu03ordenfav'] = $fila['saiu03ordenfav'];
		$_REQUEST['saiu03reddeservicio'] = $fila['saiu03reddeservicio'];
		$bcargo = true;
		$_REQUEST['paso'] = 2;
		$_REQUEST['boculta3003'] = 0;
		$bLimpiaHijos = true;
	} else {
		$_REQUEST['paso'] = 0;
	}
}
//Insertar o modificar un elemento
if (($_REQUEST['paso'] == 10) || ($_REQUEST['paso'] == 12)) {
	$bMueveScroll = true;
	list($_REQUEST, $sError, $iTipoError, $sDebugGuardar) = f3003_db_GuardarV2($_REQUEST, $objDB, $bDebug, $idTercero);
	$sDebug = $sDebug . $sDebugGuardar;
	if ($sError == '') {
		$sError = '<b>' . $ETI['msg_itemguardado'] . '</b>';
		$iTipoError = 1;
	}
}
// Cambio de consecutivo.
if ($_REQUEST['paso'] == 93) {
	$_REQUEST['paso'] = 2;
	$_REQUEST['saiu03consec_nuevo'] = numeros_validar($_REQUEST['saiu03consec_nuevo']);
	if ($_REQUEST['saiu03consec_nuevo'] == '') {
		$sError = $ERR['saiu03consec'];
	}
	if ($sError == '') {
		list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 8, $idTercero, $objDB);
		if (!$bDevuelve) {
			$sError = $ERR['8'] . ' [Mod ' . $iCodModulo . ']';
		}
	}
	if ($sError == '') {
		//Ver que el consecutivo no exista.
		$sSQL = 'SELECT saiu03id FROM saiu03temasol WHERE saiu03consec=' . $_REQUEST['saiu03consec_nuevo'] . ' AND saiu03tiposol=' . $_REQUEST['saiu03tiposol'] . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$sError = 'El consecutivo ' . $_REQUEST['saiu03consec_nuevo'] . ' ya existe';
		}
	}
	if ($sError == '') {
		//Aplicar el cambio.
		$sSQL = 'UPDATE saiu03temasol SET saiu03consec=' . $_REQUEST['saiu03consec_nuevo'] . ' WHERE saiu03id=' . $_REQUEST['saiu03id'] . '';
		$tabla = $objDB->ejecutasql($sSQL);
		$sDetalle = 'Cambia el consecutivo de ' . $_REQUEST['saiu03consec'] . ' a ' . $_REQUEST['saiu03consec_nuevo'] . '';
		$_REQUEST['saiu03consec'] = $_REQUEST['saiu03consec_nuevo'];
		$_REQUEST['saiu03consec_nuevo'] = '';
		seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 8, $_REQUEST['saiu03id'], $sDetalle, $objDB);
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
		list($sError, $iTipoError, $sDebugElimina) = f3003_db_Eliminar($_REQUEST['saiu03id'], $objDB, $bDebug);
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
	$_REQUEST['saiu03tiposol'] = 0;
	$_REQUEST['saiu03consec'] = '';
	$_REQUEST['saiu03consec_nuevo'] = '';
	$_REQUEST['saiu03id'] = '';
	$_REQUEST['saiu03activo'] = 'S';
	$_REQUEST['saiu03titulo'] = '';
	$_REQUEST['saiu03descripcion'] = '';
	$_REQUEST['saiu03ayuda'] = '';
	$_REQUEST['saiu03obligaconf'] = '';
	$_REQUEST['saiu03numetapas'] = 1;
	$_REQUEST['saiu03nometapa1'] = '';
	$_REQUEST['saiu03idunidadresp1'] = '';
	$_REQUEST['saiu03idequiporesp1'] = '';
	$_REQUEST['saiu03idliderrespon1'] = 0; //$idTercero;
	$_REQUEST['saiu03idliderrespon1_td'] = $APP->tipo_doc;
	$_REQUEST['saiu03idliderrespon1_doc'] = '';
	$_REQUEST['saiu03tiemprespdias1'] = '';
	$_REQUEST['saiu03tiempresphoras1'] = '';
	$_REQUEST['saiu03nometapa2'] = '';
	$_REQUEST['saiu03idunidadresp2'] = '';
	$_REQUEST['saiu03idequiporesp2'] = '';
	$_REQUEST['saiu03idliderrespon2'] = 0; //$idTercero;
	$_REQUEST['saiu03idliderrespon2_td'] = $APP->tipo_doc;
	$_REQUEST['saiu03idliderrespon2_doc'] = '';
	$_REQUEST['saiu03tiemprespdias2'] = '';
	$_REQUEST['saiu03tiempresphoras2'] = '';
	$_REQUEST['saiu03nometapa3'] = '';
	$_REQUEST['saiu03idunidadresp3'] = '';
	$_REQUEST['saiu03idequiporesp3'] = '';
	$_REQUEST['saiu03idliderrespon3'] = 0; //$idTercero;
	$_REQUEST['saiu03idliderrespon3_td'] = $APP->tipo_doc;
	$_REQUEST['saiu03idliderrespon3_doc'] = '';
	$_REQUEST['saiu03tiemprespdias3'] = '';
	$_REQUEST['saiu03tiempresphoras3'] = '';
	$_REQUEST['saiu03otrosusaurios'] = '';
	$_REQUEST['saiu03consupervisor'] = 'N';
	$_REQUEST['saiu03anonimos'] = '';
	$_REQUEST['saiu03anexoslibres'] = '';
	$_REQUEST['saiu03moduloasociado'] = 0;
	$_REQUEST['saiu03nivelrespuesta'] = 3;
	$_REQUEST['saiu03consupervisor2'] = 'N';
	$_REQUEST['saiu03consupervisor3'] = 'N';
	$_REQUEST['saiu03infoprograma'] = 0;
	$_REQUEST['saiu03infoperiodos'] = 0;
	$_REQUEST['saiu03requierepago'] = 0;
	$_REQUEST['saiu03incluyemodelo'] = 0;
	$_REQUEST['saiu03modelo'] = '';
	$_REQUEST['saiu03firmacertificada'] = 0;
	$_REQUEST['saiu03ordenllamada'] = 0;
	$_REQUEST['saiu03ordenchat'] = 0;
	$_REQUEST['saiu03ordencorreo'] = 0;
	$_REQUEST['saiu03ordenpresencial'] = 0;
	$_REQUEST['saiu03ordensoporte'] = 0;
	$_REQUEST['saiu03ordenpqrs'] = 0;
	$_REQUEST['saiu03ordentramites'] = 0;
	$_REQUEST['saiu03ordencorresp'] = 0;
	$_REQUEST['saiu03ordenfav'] = 0;
	$_REQUEST['saiu03reddeservicio'] = 0;
	$_REQUEST['paso'] = 0;
}
if ($bLimpiaHijos) {
	$_REQUEST['saiu04idtema'] = '';
	$_REQUEST['saiu04consec'] = '';
	$_REQUEST['saiu04id'] = '';
	$_REQUEST['saiu04activo'] = 'S';
	$_REQUEST['saiu04orden'] = '';
	$_REQUEST['saiu04obligatorio'] = '';
	$_REQUEST['saiu04titulo'] = '';
	$_REQUEST['saiu04descripcion'] = '';
	$_REQUEST['saiu04idtipogd'] = 0;
	$_REQUEST['saiu04idorigenforma'] = 0;
	$_REQUEST['saiu04idarchforma'] = 0;
	$_REQUEST['saiu04idetapa'] = 1;
}
//AQUI SE DEBEN CARGAR TODOS LOS DATOS QUE LA FORMA NECESITE.
$bPuedeGuardar = true;
$bConEliminar = false;
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
$bEdita3004 = false;
//Permisos adicionales
$seg_5 = 0;
$seg_6 = 0;
$seg_8 = 0;
//list($bDevuelve, $sDebugP)=seg_revisa_permisoV3($iCodModulo, 6, $idTercero, $objDB);
if ($_REQUEST['paso'] > 0) {
	$bDevuelve = false;
	//list($bDevuelve, $sDebugP)=seg_revisa_permisoV3($iCodModulo, 5, $idTercero, $objDB);
	if ($bDevuelve) {
		$seg_5 = 1;
	}
	$bConEliminar = true;
	list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 8, $idTercero, $objDB);
	if ($bDevuelve) {
		$seg_8 = 1;
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
$objCombos->nuevo('saiu03activo', $_REQUEST['saiu03activo'], false);
$objCombos->sino();
$html_saiu03activo = $objCombos->html('', $objDB);
$html_saiu03tiposol = f3003_HTMLComboV2_saiu03tiposol($objDB, $objCombos, $_REQUEST['saiu03tiposol']);
$objCombos->nuevo('saiu03obligaconf', $_REQUEST['saiu03obligaconf'], false);
$objCombos->sino();
$html_saiu03obligaconf = $objCombos->html('', $objDB);
$objCombos->nuevo('saiu03numetapas', $_REQUEST['saiu03numetapas'], false, '{' . $ETI['msg_seleccione'] . '}');
//$objCombos->addArreglo($asaiu03numetapas, $isaiu03numetapas);
$objCombos->sAccion = 'cambiapagina()';
$objCombos->addItem(1, 1);
$objCombos->addItem(2, 2);
$objCombos->addItem(3, 3);
$html_saiu03numetapas = $objCombos->html('', $objDB);
$objCombos->nuevo('saiu03idunidadresp1', $_REQUEST['saiu03idunidadresp1'], true, '{' . $ETI['msg_seleccione'] . '}');
$objCombos->iAncho = 340;
$objCombos->sAccion = 'carga_combo_saiu03idequiporesp1();';
$sSQL26 = f226_ConsultaCombo();
$html_saiu03idunidadresp1 = $objCombos->html($sSQL26, $objDB);
$html_saiu03idequiporesp1 = f3003_HTMLComboV2_saiu03idequiporesp1($objDB, $objCombos, $_REQUEST['saiu03idequiporesp1'], $_REQUEST['saiu03idunidadresp1']);
list($saiu03idliderrespon1_rs, $_REQUEST['saiu03idliderrespon1'], $_REQUEST['saiu03idliderrespon1_td'], $_REQUEST['saiu03idliderrespon1_doc']) = html_tercero($_REQUEST['saiu03idliderrespon1_td'], $_REQUEST['saiu03idliderrespon1_doc'], $_REQUEST['saiu03idliderrespon1'], 0, $objDB);
$bOculto = false;
$html_saiu03idliderrespon1 = html_DivTerceroV8('saiu03idliderrespon1', $_REQUEST['saiu03idliderrespon1_td'], $_REQUEST['saiu03idliderrespon1_doc'], $bOculto, $objDB, $objCombos, 0, $ETI['ing_doc']);
if ($_REQUEST['saiu03numetapas'] > 1) {
	$objCombos->nuevo('saiu03idunidadresp2', $_REQUEST['saiu03idunidadresp2'], true, '{' . $ETI['msg_seleccione'] . '}');
	$objCombos->iAncho = 300;
	$objCombos->sAccion = 'carga_combo_saiu03idequiporesp2();';
	$html_saiu03idunidadresp2 = $objCombos->html($sSQL26, $objDB);
	$html_saiu03idequiporesp2 = f3003_HTMLComboV2_saiu03idequiporesp2($objDB, $objCombos, $_REQUEST['saiu03idequiporesp2'], $_REQUEST['saiu03idunidadresp2']);
	list($saiu03idliderrespon2_rs, $_REQUEST['saiu03idliderrespon2'], $_REQUEST['saiu03idliderrespon2_td'], $_REQUEST['saiu03idliderrespon2_doc']) = html_tercero($_REQUEST['saiu03idliderrespon2_td'], $_REQUEST['saiu03idliderrespon2_doc'], $_REQUEST['saiu03idliderrespon2'], 0, $objDB);
	$objCombos->nuevo('saiu03consupervisor2', $_REQUEST['saiu03consupervisor2'], false);
	$objCombos->sino();
	$html_saiu03consupervisor2 = $objCombos->html('', $objDB);
}
if ($_REQUEST['saiu03numetapas'] > 2) {
	$objCombos->nuevo('saiu03idunidadresp3', $_REQUEST['saiu03idunidadresp3'], true, '{' . $ETI['msg_seleccione'] . '}');
	$objCombos->iAncho = 300;
	$objCombos->sAccion = 'carga_combo_saiu03idequiporesp3();';
	$html_saiu03idunidadresp3 = $objCombos->html($sSQL26, $objDB);
	$html_saiu03idequiporesp3 = f3003_HTMLComboV2_saiu03idequiporesp3($objDB, $objCombos, $_REQUEST['saiu03idequiporesp3'], $_REQUEST['saiu03idunidadresp3']);
	list($saiu03idliderrespon3_rs, $_REQUEST['saiu03idliderrespon3'], $_REQUEST['saiu03idliderrespon3_td'], $_REQUEST['saiu03idliderrespon3_doc']) = html_tercero($_REQUEST['saiu03idliderrespon3_td'], $_REQUEST['saiu03idliderrespon3_doc'], $_REQUEST['saiu03idliderrespon3'], 0, $objDB);
	$objCombos->nuevo('saiu03consupervisor3', $_REQUEST['saiu03consupervisor3'], false);
	$objCombos->sino();
	$html_saiu03consupervisor3 = $objCombos->html('', $objDB);
}
$objCombos->nuevo('saiu03otrosusaurios', $_REQUEST['saiu03otrosusaurios'], false);
$objCombos->sino();
$html_saiu03otrosusaurios = $objCombos->html('', $objDB);
$objCombos->nuevo('saiu03consupervisor', $_REQUEST['saiu03consupervisor'], false);
$objCombos->sino();
$html_saiu03consupervisor = $objCombos->html('', $objDB);
$objCombos->nuevo('saiu03anonimos', $_REQUEST['saiu03anonimos'], false);
$objCombos->sino();
$html_saiu03anonimos = $objCombos->html('', $objDB);
$objCombos->nuevo('saiu03anexoslibres', $_REQUEST['saiu03anexoslibres'], false);
$objCombos->sino();
$html_saiu03anexoslibres = $objCombos->html('', $objDB);
list($saiu03moduloasociado_nombre, $sErrorDet) = tabla_campoxid('', '', '', $_REQUEST['saiu03moduloasociado'], '{' . $ETI['msg_sindato'] . '}', $objDB);
$html_saiu03moduloasociado = html_oculto('saiu03moduloasociado', $_REQUEST['saiu03moduloasociado'], $saiu03moduloasociado_nombre);
$objCombos->nuevo('saiu03nivelrespuesta', $_REQUEST['saiu03nivelrespuesta'], false, '{' . $ETI['msg_seleccione'] . '}');
$sSQL = 'SELECT saiu17id AS id, saiu17nombre AS nombre FROM saiu17nivelatencion ORDER BY saiu17id';
$html_saiu03nivelrespuesta = $objCombos->html($sSQL, $objDB);
$objCombos->nuevo('saiu03infoprograma', $_REQUEST['saiu03infoprograma'], true, '' . $ETI['no'] . '', 0);
$objCombos->addItem(1, $ETI['si']);
//$objCombos->addArreglo($asaiu03infoprograma, $isaiu03infoprograma);
$html_saiu03infoprograma = $objCombos->html('', $objDB);
$objCombos->nuevo('saiu03infoperiodos', $_REQUEST['saiu03infoperiodos'], true, '' . $ETI['no'] . '', 0);
$objCombos->addItem(1, $ETI['si']);
//$objCombos->addArreglo($asaiu03infoperiodos, $isaiu03infoperiodos);
$html_saiu03infoperiodos = $objCombos->html('', $objDB);
$objCombos->nuevo('saiu03requierepago', $_REQUEST['saiu03requierepago'], true, '' . $ETI['no'] . '', 0);
$objCombos->addItem(1, $ETI['si']);
//$objCombos->addArreglo($asaiu03requierepago, $isaiu03requierepago);
$html_saiu03requierepago = $objCombos->html('', $objDB);
$objCombos->nuevo('saiu03incluyemodelo', $_REQUEST['saiu03incluyemodelo'], true, '' . $ETI['no'] . '', 0);
$objCombos->addItem(1, $ETI['si']);
$objCombos->sAccion = 'ajustarmodelo()';
//$objCombos->addArreglo($asaiu03incluyemodelo, $isaiu03incluyemodelo);
$html_saiu03incluyemodelo = $objCombos->html('', $objDB);
$objCombos->nuevo('saiu03ordenllamada', $_REQUEST['saiu03ordenllamada'], true, $asaiu03orden[0], 0);
$objCombos->addArreglo($asaiu03orden, $isaiu03orden);
$html_saiu03ordenllamada = $objCombos->html('', $objDB);
$objCombos->nuevo('saiu03ordenchat', $_REQUEST['saiu03ordenchat'], true, $asaiu03orden[0], 0);
$objCombos->addArreglo($asaiu03orden, $isaiu03orden);
$html_saiu03ordenchat = $objCombos->html('', $objDB);
$objCombos->nuevo('saiu03ordencorreo', $_REQUEST['saiu03ordencorreo'], true, $asaiu03orden[0], 0);
$objCombos->addArreglo($asaiu03orden, $isaiu03orden);
$html_saiu03ordencorreo = $objCombos->html('', $objDB);
$objCombos->nuevo('saiu03ordenpresencial', $_REQUEST['saiu03ordenpresencial'], true, $asaiu03orden[0], 0);
$objCombos->addArreglo($asaiu03orden, $isaiu03orden);
$html_saiu03ordenpresencial = $objCombos->html('', $objDB);
$objCombos->nuevo('saiu03ordensoporte', $_REQUEST['saiu03ordensoporte'], true, $asaiu03orden[0], 0);
$objCombos->addArreglo($asaiu03orden, $isaiu03orden);
$html_saiu03ordensoporte = $objCombos->html('', $objDB);
$objCombos->nuevo('saiu03ordenpqrs', $_REQUEST['saiu03ordenpqrs'], true, $asaiu03orden[0], 0);
$objCombos->addArreglo($asaiu03orden, $isaiu03orden);
$html_saiu03ordenpqrs = $objCombos->html('', $objDB);
$objCombos->nuevo('saiu03ordentramites', $_REQUEST['saiu03ordentramites'], true, $asaiu03orden[0], 0);
$objCombos->addArreglo($asaiu03orden, $isaiu03orden);
$html_saiu03ordentramites = $objCombos->html('', $objDB);
$objCombos->nuevo('saiu03ordencorresp', $_REQUEST['saiu03ordencorresp'], true, $asaiu03orden[0], 0);
$objCombos->addArreglo($asaiu03orden, $isaiu03orden);
$html_saiu03ordencorresp = $objCombos->html('', $objDB);
$objCombos->nuevo('saiu03ordenfav', $_REQUEST['saiu03ordenfav'], true, $asaiu03orden[0], 0);
$objCombos->addArreglo($asaiu03orden, $isaiu03orden);
$html_saiu03ordenfav = $objCombos->html('', $objDB);
$objCombos->nuevo('saiu03reddeservicio', $_REQUEST['saiu03reddeservicio'], true, '{' . $ETI['msg_na'] . '}', 0);
$objCombos->iAncho = 340;
$sSQL = 'SELECT saiu74id AS id, saiu74nombre AS nombre FROM saiu74reddeservicio ORDER BY saiu74nombre';
$html_saiu03reddeservicio = $objCombos->html($sSQL, $objDB);
if ((int)$_REQUEST['paso'] == 0) {
} else {
	//list($saiu03tiposol_nombre, $sErrorDet)=tabla_campoxid('saiu02tiposol','saiu02titulo','saiu02id',$_REQUEST['saiu03tiposol'],'{'.$ETI['msg_sindato'].'}', $objDB);
	//$html_saiu03tiposol=html_oculto('saiu03tiposol', $_REQUEST['saiu03tiposol'], $saiu03tiposol_nombre);
	$objCombos->nuevo('saiu04activo', $_REQUEST['saiu04activo'], false);
	$objCombos->sino();
	$html_saiu04activo = $objCombos->html('', $objDB);
	$objCombos->nuevo('saiu04obligatorio', $_REQUEST['saiu04obligatorio'], false);
	$objCombos->sino();
	$html_saiu04obligatorio = $objCombos->html('', $objDB);
	$objCombos->nuevo('saiu04idetapa', $_REQUEST['saiu04idetapa'], false, '{' . $ETI['msg_seleccione'] . '}');
	$objCombos->addItem(1, 1);
	if ($_REQUEST['saiu03numetapas'] > 1) {
		$objCombos->addItem(2, 2);
		if ($_REQUEST['saiu03numetapas'] > 2) {
			$objCombos->addItem(3, 3);
		}
	}
	$html_saiu04idetapa = $objCombos->html('', $objDB);
}
//Alistar datos adicionales
$id_rpt = 0;
//$id_rpt=reportes_id(_Identificador_Tipo_Reporte_, $objDB);
if (false) {
	$objCombos->nuevo('blistar', $_REQUEST['blistar'], true, '{' . $ETI['msg_todos'] . '}');
	$objCombos->iAncho = 300;
	$objCombos->sAccion = 'paginarf3003()';
	$sSQL = 'SELECT saiu02id AS id, saiu02titulo AS nombre FROM saiu02tiposol WHERE saiu02id>0 ORDER BY saiu02titulo';
	$html_blistar = $objCombos->html($sSQL, $objDB);
}
$objCombos->nuevo('bunidad', $_REQUEST['bunidad'], true, '{' . $ETI['msg_todas'] . '}');
$objCombos->addItem('0', '[Sin asignar a una unidad]');
$objCombos->iAncho = 400;
$objCombos->sAccion = 'paginarf3003()';
$html_bunidad = $objCombos->html($sSQL26, $objDB);
$objCombos->nuevo('borden', $_REQUEST['borden'], true, '{' . $ETI['msg_ninguno'] . '}');
$objCombos->sAccion = 'paginarf3003()';
$sSQL = 'SELECT saiu24id AS id, saiu24nombre AS nombre FROM saiu24modulossai ORDER BY saiu24orden, saiu24nombre';
$html_borden = $objCombos->html($sSQL, $objDB);
$objCombos->nuevo('btiposol', $_REQUEST['btiposol'], true, '{' . $ETI['msg_todos'] . '}');
$objCombos->iAncho = 400;
$objCombos->sAccion = 'paginarf3003()';
$sSQL = 'SELECT TB.saiu02id AS id, CONCAT(TB.saiu02titulo, " [", T1.saiu01titulo, "]") AS nombre 
FROM saiu02tiposol AS TB, saiu01claseser AS T1 
WHERE TB.saiu02id>0 AND TB.saiu02clasesol=T1.saiu01id 
ORDER BY TB.saiu02titulo';
$html_btiposol = $objCombos->html($sSQL, $objDB);

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
$aParametros[0] = ''; //$_REQUEST['p1_3003'];
$aParametros[100] = $idTercero;
$aParametros[101] = $_REQUEST['paginaf3003'];
$aParametros[102] = $_REQUEST['lppf3003'];
$aParametros[103] = $_REQUEST['bnombre'];
$aParametros[105] = $_REQUEST['bunidad'];
$aParametros[106] = $_REQUEST['borden'];
$aParametros[107] = $_REQUEST['btiposol'];
list($sTabla3003, $sDebugTabla) = f3003_TablaDetalleV2($aParametros, $objDB, $bDebug);
$sDebug = $sDebug . $sDebugTabla;
$sTabla3004 = '';
if ($_REQUEST['paso'] != 0) {
	//Anexos
	$aParametros3004[0] = $_REQUEST['saiu03id'];
	$aParametros3004[100] = $idTercero;
	$aParametros3004[101] = $_REQUEST['paginaf3004'];
	$aParametros3004[102] = $_REQUEST['lppf3004'];
	//$aParametros3004[103]=$_REQUEST['bnombre3004'];
	//$aParametros3004[104]=$_REQUEST['blistar3004'];
	list($sTabla3004, $sDebugTabla) = f3004_TablaDetalleV2($aParametros3004, $objDB, $bDebug);
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
			window.document.frmlista.consulta.value = window.document.frmedita.consulta_3003.value;
			window.document.frmlista.titulos.value = window.document.frmedita.titulos_3003.value;
			window.document.frmlista.nombrearchivo.value = 'Temas de solicitud';
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
			window.document.frmimpp.action = 'e3003.php';
			window.document.frmimpp.submit();
		} else {
			ModalMensaje(sError);
		}
	}

	function imprimep() {
		if (window.document.frmedita.seg_5.value == 1) {
			asignarvariables();
			window.document.frmimpp.action = 'p3003.php';
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
		datos[1] = window.document.frmedita.saiu03tiposol.value;
		datos[2] = window.document.frmedita.saiu03consec.value;
		if ((datos[1] != '') && (datos[2] != '')) {
			xajax_f3003_ExisteDato(datos);
		}
	}

	function cargadato(llave1, llave2) {
		window.document.frmedita.saiu03tiposol.value = String(llave1);
		window.document.frmedita.saiu03consec.value = String(llave2);
		window.document.frmedita.paso.value = 1;
		window.document.frmedita.submit();
	}

	function cargaridf3003(llave1) {
		window.document.frmedita.saiu03id.value = String(llave1);
		window.document.frmedita.paso.value = 3;
		window.document.frmedita.submit();
	}

	function carga_combo_saiu03idequiporesp1() {
		let params = new Array();
		params[0] = window.document.frmedita.saiu03idunidadresp1.value;
		document.getElementById('div_saiu03idequiporesp1').innerHTML = '<b>Procesando datos, por favor espere...</b><input id="saiu03idequiporesp1" name="saiu03idequiporesp1" type="hidden" value="" />';
		xajax_f3003_Combosaiu03idequiporesp1(params);
	}

	function carga_combo_saiu03idequiporesp2() {
		let params = new Array();
		params[0] = window.document.frmedita.saiu03idunidadresp2.value;
		document.getElementById('div_saiu03idequiporesp2').innerHTML = '<b>Procesando datos, por favor espere...</b><input id="saiu03idequiporesp2" name="saiu03idequiporesp2" type="hidden" value="" />';
		xajax_f3003_Combosaiu03idequiporesp2(params);
	}

	function carga_combo_saiu03idequiporesp3() {
		let params = new Array();
		params[0] = window.document.frmedita.saiu03idunidadresp3.value;
		document.getElementById('div_saiu03idequiporesp3').innerHTML = '<b>Procesando datos, por favor espere...</b><input id="saiu03idequiporesp3" name="saiu03idequiporesp3" type="hidden" value="" />';
		xajax_f3003_Combosaiu03idequiporesp3(params);
	}

	function paginarf3003() {
		let params = new Array();
		params[99] = window.document.frmedita.debug.value;
		params[100] = <?php echo $idTercero; ?>;
		params[101] = window.document.frmedita.paginaf3003.value;
		params[102] = window.document.frmedita.lppf3003.value;
		params[103] = window.document.frmedita.bnombre.value;
		//params[104]=window.document.frmedita.blistar.value;
		params[105] = window.document.frmedita.bunidad.value;
		params[106] = window.document.frmedita.borden.value;
		params[107] = window.document.frmedita.btiposol.value;
		document.getElementById('div_f3003detalle').innerHTML = '<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf3003" name="paginaf3003" type="hidden" value="' + params[101] + '" /><input id="lppf3003" name="lppf3003" type="hidden" value="' + params[102] + '" />';
		xajax_f3003_HtmlTabla(params);
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
		document.getElementById("saiu03tiposol").focus();
	}

	function buscarV2016(sCampo) {
		window.document.frmedita.iscroll.value = window.scrollY;
		expandesector(98);
		window.document.frmedita.scampobusca.value = sCampo;
		let params = new Array();
		params[1] = sCampo;
		//params[2]=window.document.frmedita.iagno.value;
		//params[3]=window.document.frmedita.itipo.value;
		xajax_f3003_Busquedas(params);
	}

	function Devuelve(sValor) {
		let sCampo = window.document.frmedita.scampobusca.value;
		if (sCampo == 'saiu03idliderrespon1') {
			ter_traerxid('saiu03idliderrespon1', sValor);
		}
		if (sCampo == 'saiu03idliderrespon2') {
			ter_traerxid('saiu03idliderrespon2', sValor);
		}
		if (sCampo == 'saiu03idliderrespon3') {
			ter_traerxid('saiu03idliderrespon3', sValor);
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
		if (ref == 3004) {
			if (sRetorna != '') {
				window.document.frmedita.saiu04idorigenforma.value = window.document.frmedita.div96v1.value;
				window.document.frmedita.saiu04idarchforma.value = sRetorna;
				verboton('beliminasaiu04idarchforma', 'block');
			}
			archivo_lnk(window.document.frmedita.saiu04idorigenforma.value, window.document.frmedita.saiu04idarchforma.value, 'div_saiu04idarchforma');
			paginarf3004();
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

	function ajustarmodelo() {
		var iForma = window.document.frmedita.saiu03incluyemodelo.value;
		var sMuestra = 'none';
		if (iForma == 1) {
			sMuestra = 'block';
		}
		document.getElementById('div_saiu03modelo').style.display = sMuestra;
	}

	function consultalider(iEtapa) {
		var params = new Array();
		params[99] = window.document.frmedita.debug.value;
		params[100] = <?php echo $idTercero; ?>;
		params[101] = iEtapa;
		params[102] = document.getElementById('saiu03idequiporesp' + iEtapa).value;
		//document.getElementById('div_f3003detalle').innerHTML='<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf3003" name="paginaf3003" type="hidden" value="'+params[101]+'" /><input id="lppf3003" name="lppf3003" type="hidden" value="'+params[102]+'" />';
		xajax_f3003_saiu03idliderrespon(params);
	}
</script>
<?php
if ($_REQUEST['paso'] != 0) {
?>
<script language="javascript" src="jsi/js3004.js"></script>
<?php
}
?>
<form id="frmimpp" name="frmimpp" method="post" action="p3003.php" target="_blank" style="display:none">
<input id="r" name="r" type="hidden" value="3003" />
<input id="id3003" name="id3003" type="hidden" value="<?php echo $_REQUEST['saiu03id']; ?>" />
<input id="v3" name="v3" type="hidden" value="" />
<input id="v4" name="v4" type="hidden" value="" />
<input id="v5" name="v5" type="hidden" value="" />
<input id="v6" name="v6" type="hidden" value="" />
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
echo $objForma->htmlExpande(3003, $_REQUEST['boculta3003'], $ETI['bt_mostrar'], $ETI['bt_ocultar']);
$sEstiloDiv = '';
if ($_REQUEST['boculta3003'] != 0) {
	$sEstiloDiv = ' style="display:none;"';
}
?>
</div>
<div id="div_p3003"<?php echo $sEstiloDiv; ?>>
<?php
}
//Mostrar formulario para editar
?>
<label class="Label130">
<?php
echo $ETI['saiu03consec'];
?>
</label>
<label class="Label130">
<?php
if ($_REQUEST['paso'] != 2) {
?>
<input id="saiu03consec" name="saiu03consec" type="text" value="<?php echo $_REQUEST['saiu03consec']; ?>" onchange="RevisaLlave()" class="cuatro" />
<?php
} else {
echo html_oculto('saiu03consec', $_REQUEST['saiu03consec'], formato_numero($_REQUEST['saiu03consec']));
}
?>
</label>
<?php
/*
if ($seg_8==1){
echo $objForma->htmlBotonSolo('cmdCambiaConsec', 'btMiniActualizar', 'expandesector(93);', $ETI['bt_cambiar'], 30);
echo '<label class="Label30">&nbsp;</label>';
}
*/
?>
<label class="Label60"<?php echo $sOcultaId; ?>>
<?php
echo $ETI['saiu03id'];
?>
</label>
<label class="Label60"<?php echo $sOcultaId; ?>>
<?php
echo html_oculto('saiu03id', $_REQUEST['saiu03id'], formato_numero($_REQUEST['saiu03id']));
?>
</label>
<label class="Label60">
<?php
echo $ETI['saiu03activo'];
?>
</label>
<label class="Label60">
<?php
echo $html_saiu03activo;
?>
</label>
<label class="Label200">
<?php
echo $ETI['saiu03nivelrespuesta'];
?>
</label>
<label class="Label160">
<?php
echo $html_saiu03nivelrespuesta;
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu03tiposol'];
?>
</label>
<label>
<?php
echo $html_saiu03tiposol;
?>
</label>
<label class="L">
<?php
echo $ETI['saiu03titulo'];
?>

<input id="saiu03titulo" name="saiu03titulo" type="text" value="<?php echo $_REQUEST['saiu03titulo']; ?>" maxlength="250" class="L" placeholder="<?php echo $ETI['ing_campo'] . $ETI['saiu03titulo']; ?>" />
</label>
<label class="txtAreaS">
<?php
echo $ETI['saiu03descripcion'];
?>
<textarea id="saiu03descripcion" name="saiu03descripcion" placeholder="<?php echo $ETI['ing_campo'] . $ETI['saiu03descripcion']; ?>"><?php echo $_REQUEST['saiu03descripcion']; ?></textarea>
</label>
<label class="txtAreaS">
<?php
echo $ETI['saiu03ayuda'];
?>
<textarea id="saiu03ayuda" name="saiu03ayuda" placeholder="<?php echo $ETI['ing_campo'] . $ETI['saiu03ayuda']; ?>"><?php echo $_REQUEST['saiu03ayuda']; ?></textarea>
</label>
<label class="Label250">
<?php
echo $ETI['saiu03obligaconf'];
?>
</label>
<label class="Label60">
<?php
echo $html_saiu03obligaconf;
?>
</label>
<label class="Label130">
<?php
echo $ETI['saiu03otrosusaurios'];
?>
</label>
<label class="Label60">
<?php
echo $html_saiu03otrosusaurios;
?>
</label>
<label class="Label160">
<?php
echo $ETI['saiu03anonimos'];
?>
</label>
<label class="Label60">
<?php
echo $html_saiu03anonimos;
?>
</label>
<label class="Label130">
<?php
echo $ETI['saiu03anexoslibres'];
?>
</label>
<label class="Label60">
<?php
echo $html_saiu03anexoslibres;
?>
</label>
<label class="Label130">
<?php
echo $ETI['saiu03numetapas'];
?>
</label>
<label class="Label60">
<?php
echo $html_saiu03numetapas;
?>
</label>
<div class="salto1px"></div>
<hr />
<label class="TituloGrupo">
<?php
echo $ETI['msg_etapa1'];
?>
</label>
<div class="salto1px"></div>
<label class="L">
<?php
echo $ETI['saiu03nometapa1'];
?>

<input id="saiu03nometapa1" name="saiu03nometapa1" type="text" value="<?php echo $_REQUEST['saiu03nometapa1']; ?>" maxlength="100" class="L" placeholder="<?php echo $ETI['ing_campo'] . $ETI['saiu03nometapa1']; ?>" />
</label>
<div class="GrupoCampos520">
<div class="salto5px"></div>
<label class="Label160">
<?php
echo $ETI['saiu03idunidadresp1'];
?>
</label>
<label>
<?php
echo $html_saiu03idunidadresp1;
?>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['saiu03reddeservicio'];
?>
</label>
<label>
<?php
echo $html_saiu03reddeservicio;
?>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['saiu03idequiporesp1'];
?>
</label>
<label>
<div id="div_saiu03idequiporesp1">
<?php
echo $html_saiu03idequiporesp1;
?>
</div>
</label>
<div class="salto1px"></div>
</div>

<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo $ETI['saiu03idliderrespon1'];
?>
</label>
<div class="salto1px"></div>
<input id="saiu03idliderrespon1" name="saiu03idliderrespon1" type="hidden" value="<?php echo $_REQUEST['saiu03idliderrespon1']; ?>" />
<div id="div_saiu03idliderrespon1_llaves">
<?php
echo $html_saiu03idliderrespon1;
?>
</div>
<div class="salto1px"></div>
<div id="div_saiu03idliderrespon1" class="L"><?php echo $saiu03idliderrespon1_rs; ?></div>
<div class="salto1px"></div>
</div>

<div class="salto1px"></div>
<label class="Label250">
<?php
echo $ETI['saiu03tiemprespdias1'];
?>
</label>
<label class="Label130">
<input id="saiu03tiemprespdias1" name="saiu03tiemprespdias1" type="text" value="<?php echo $_REQUEST['saiu03tiemprespdias1']; ?>" class="diez" maxlength="10" placeholder="<?php echo $ETI['ing_vr']; ?>" />
</label>
<label class="Label250">
<?php
echo $ETI['saiu03tiempresphoras1'];
?>
</label>
<label class="Label130">
<input id="saiu03tiempresphoras1" name="saiu03tiempresphoras1" type="text" value="<?php echo $_REQUEST['saiu03tiempresphoras1']; ?>" class="diez" maxlength="10" placeholder="<?php echo $ETI['ing_vr']; ?>" />
</label>
<label class="Label160">
<?php
echo $ETI['saiu03consupervisor'];
?>
</label>
<label class="Label60">
<?php
echo $html_saiu03consupervisor;
?>
</label>
<?php
if ($_REQUEST['saiu03numetapas'] > 1) {
?>
<div class="salto1px"></div>
<hr />
<label class="TituloGrupo">
<?php
echo $ETI['msg_etapa2'];
?>
</label>
<div class="salto1px"></div>
<label class="L">
<?php
echo $ETI['saiu03nometapa2'];
?>

<input id="saiu03nometapa2" name="saiu03nometapa2" type="text" value="<?php echo $_REQUEST['saiu03nometapa2']; ?>" maxlength="100" class="L" placeholder="<?php echo $ETI['ing_campo'] . $ETI['saiu03nometapa2']; ?>" />
</label>

<div class="GrupoCampos520">
<div class="salto5px"></div>
<label class="Label160">
<?php
echo $ETI['saiu03idunidadresp2'];
?>
</label>
<label>
<?php
echo $html_saiu03idunidadresp2;
?>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['saiu03idequiporesp2'];
?>
</label>
<label>
<div id="div_saiu03idequiporesp2">
<?php
echo $html_saiu03idequiporesp2;
?>
</div>
</label>
<div class="salto1px"></div>
</div>

<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo $ETI['saiu03idliderrespon2'];
?>
</label>
<div class="salto1px"></div>
<input id="saiu03idliderrespon2" name="saiu03idliderrespon2" type="hidden" value="<?php echo $_REQUEST['saiu03idliderrespon2']; ?>" />
<div id="div_saiu03idliderrespon2_llaves">
<?php
$bOculto = false;
echo html_DivTerceroV2('saiu03idliderrespon2', $_REQUEST['saiu03idliderrespon2_td'], $_REQUEST['saiu03idliderrespon2_doc'], $bOculto, 0, $ETI['ing_doc']);
?>
</div>
<div class="salto1px"></div>
<div id="div_saiu03idliderrespon2" class="L"><?php echo $saiu03idliderrespon2_rs; ?></div>
<div class="salto1px"></div>
</div>

<div class="salto1px"></div>
<label class="Label250">
<?php
echo $ETI['saiu03tiemprespdias2'];
?>
</label>
<label class="Label130">
<input id="saiu03tiemprespdias2" name="saiu03tiemprespdias2" type="text" value="<?php echo $_REQUEST['saiu03tiemprespdias2']; ?>" class="diez" maxlength="10" placeholder="<?php echo $ETI['ing_vr']; ?>" />
</label>
<label class="Label250">
<?php
echo $ETI['saiu03tiempresphoras2'];
?>
</label>
<label class="Label130">
<input id="saiu03tiempresphoras2" name="saiu03tiempresphoras2" type="text" value="<?php echo $_REQUEST['saiu03tiempresphoras2']; ?>" class="diez" maxlength="10" placeholder="<?php echo $ETI['ing_vr']; ?>" />
</label>
<label class="Label160">
<?php
echo $ETI['saiu03consupervisor2'];
?>
</label>
<label class="Label60">
<?php
echo $html_saiu03consupervisor2;
?>
</label>
<?php
} else {
?>
<input id="saiu03nometapa2" name="saiu03nometapa2" type="hidden" value="<?php echo $_REQUEST['saiu03nometapa2']; ?>" />
<input id="saiu03idunidadresp2" name="saiu03idunidadresp2" type="hidden" value="<?php echo $_REQUEST['saiu03idunidadresp2']; ?>" />
<input id="saiu03idequiporesp2" name="saiu03idequiporesp2" type="hidden" value="<?php echo $_REQUEST['saiu03idequiporesp2']; ?>" />
<input id="saiu03idliderrespon2" name="saiu03idliderrespon2" type="hidden" value="<?php echo $_REQUEST['saiu03idliderrespon2']; ?>" />
<input id="saiu03idliderrespon2_td" name="saiu03idliderrespon2_td" type="hidden" value="<?php echo $_REQUEST['saiu03idliderrespon2_td']; ?>" />
<input id="saiu03idliderrespon2_doc" name="saiu03idliderrespon2_doc" type="hidden" value="<?php echo $_REQUEST['saiu03idliderrespon2_doc']; ?>" />
<input id="saiu03tiemprespdias2" name="saiu03tiemprespdias2" type="hidden" value="<?php echo $_REQUEST['saiu03tiemprespdias2']; ?>" />
<input id="saiu03tiempresphoras2" name="saiu03tiempresphoras2" type="hidden" value="<?php echo $_REQUEST['saiu03tiempresphoras2']; ?>" />
<input id="saiu03consupervisor2" name="saiu03consupervisor2" type="hidden" value="<?php echo $_REQUEST['saiu03consupervisor2']; ?>" />
<?php
}
if ($_REQUEST['saiu03numetapas'] > 2) {
?>
<div class="salto1px"></div>
<hr />
<label class="TituloGrupo">
<?php
echo $ETI['msg_etapa3'];
?>
</label>
<div class="salto1px"></div>
<label class="L">
<?php
echo $ETI['saiu03nometapa3'];
?>

<input id="saiu03nometapa3" name="saiu03nometapa3" type="text" value="<?php echo $_REQUEST['saiu03nometapa3']; ?>" maxlength="100" class="L" placeholder="<?php echo $ETI['ing_campo'] . $ETI['saiu03nometapa3']; ?>" />
</label>

<div class="GrupoCampos520">
<div class="salto5px"></div>
<label class="Label160">
<?php
echo $ETI['saiu03idunidadresp3'];
?>
</label>
<label>
<?php
echo $html_saiu03idunidadresp3;
?>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['saiu03idequiporesp3'];
?>
</label>
<label>
<div id="div_saiu03idequiporesp3">
<?php
echo $html_saiu03idequiporesp3;
?>
</div>
</label>
<div class="salto1px"></div>
</div>

<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo $ETI['saiu03idliderrespon3'];
?>
</label>
<div class="salto1px"></div>
<input id="saiu03idliderrespon3" name="saiu03idliderrespon3" type="hidden" value="<?php echo $_REQUEST['saiu03idliderrespon3']; ?>" />
<div id="div_saiu03idliderrespon3_llaves">
<?php
$bOculto = false;
echo html_DivTerceroV2('saiu03idliderrespon3', $_REQUEST['saiu03idliderrespon3_td'], $_REQUEST['saiu03idliderrespon3_doc'], $bOculto, 0, $ETI['ing_doc']);
?>
</div>
<div class="salto1px"></div>
<div id="div_saiu03idliderrespon3" class="L"><?php echo $saiu03idliderrespon3_rs; ?></div>
<div class="salto1px"></div>
</div>

<div class="salto1px"></div>
<label class="Label250">
<?php
echo $ETI['saiu03tiemprespdias3'];
?>
</label>
<label class="Label130">
<input id="saiu03tiemprespdias3" name="saiu03tiemprespdias3" type="text" value="<?php echo $_REQUEST['saiu03tiemprespdias3']; ?>" class="diez" maxlength="10" placeholder="<?php echo $ETI['ing_vr']; ?>" />
</label>
<label class="Label250">
<?php
echo $ETI['saiu03tiempresphoras3'];
?>
</label>
<label class="Label130">
<input id="saiu03tiempresphoras3" name="saiu03tiempresphoras3" type="text" value="<?php echo $_REQUEST['saiu03tiempresphoras3']; ?>" class="diez" maxlength="10" placeholder="<?php echo $ETI['ing_vr']; ?>" />
</label>
<label class="Label160">
<?php
echo $ETI['saiu03consupervisor3'];
?>
</label>
<label class="Label60">
<?php
echo $html_saiu03consupervisor3;
?>
</label>
<?php
} else {
?>
<input id="saiu03nometapa3" name="saiu03nometapa3" type="hidden" value="<?php echo $_REQUEST['saiu03nometapa3']; ?>" />
<input id="saiu03idunidadresp3" name="saiu03idunidadresp3" type="hidden" value="<?php echo $_REQUEST['saiu03idunidadresp3']; ?>" />
<input id="saiu03idequiporesp3" name="saiu03idequiporesp3" type="hidden" value="<?php echo $_REQUEST['saiu03idequiporesp3']; ?>" />
<input id="saiu03idliderrespon3" name="saiu03idliderrespon3" type="hidden" value="<?php echo $_REQUEST['saiu03idliderrespon3']; ?>" />
<input id="saiu03idliderrespon3_td" name="saiu03idliderrespon3_td" type="hidden" value="<?php echo $_REQUEST['saiu03idliderrespon3_td']; ?>" />
<input id="saiu03idliderrespon3_doc" name="saiu03idliderrespon3_doc" type="hidden" value="<?php echo $_REQUEST['saiu03idliderrespon3_doc']; ?>" />
<input id="saiu03tiemprespdias3" name="saiu03tiemprespdias3" type="hidden" value="<?php echo $_REQUEST['saiu03tiemprespdias3']; ?>" />
<input id="saiu03tiempresphoras3" name="saiu03tiempresphoras3" type="hidden" value="<?php echo $_REQUEST['saiu03tiempresphoras3']; ?>" />
<input id="saiu03consupervisor3" name="saiu03consupervisor3" type="hidden" value="<?php echo $_REQUEST['saiu03consupervisor3']; ?>" />
<?php
}
?>
<div class="salto1px"></div>
<div class="GrupoCampos">
<div class="salto5px"></div>
<label class="Label300">
<?php
echo $ETI['saiu03infoprograma'];
?>
</label>
<label class="Label60">
<?php
echo $html_saiu03infoprograma;
?>
</label>
<label class="Label300">
<?php
echo $ETI['saiu03infoperiodos'];
?>
</label>
<label class="Label60">
<?php
echo $html_saiu03infoperiodos;
?>
</label>
<label class="Label200">
<?php
echo $ETI['saiu03requierepago'];
?>
</label>
<label class="Label60">
<?php
echo $html_saiu03requierepago;
?>
</label>
<label class="Label130">
<?php
echo $ETI['saiu03incluyemodelo'];
?>
</label>
<label class="Label60">
<?php
echo $html_saiu03incluyemodelo;
?>
</label>
<div class="salto1px"></div>
<?php
$sEstilo = ' style="display:none"';
if ($_REQUEST['saiu03incluyemodelo'] == 1) {
$sEstilo = '';
}
?>
<div id="div_saiu03modelo" <?php echo $sEstilo; ?>>
<label class="txtAreaS">
<?php
echo $ETI['saiu03modelo'];
?>
<textarea id="saiu03modelo" name="saiu03modelo" placeholder="<?php echo $ETI['ing_campo'] . $ETI['saiu03modelo']; ?>"><?php echo $_REQUEST['saiu03modelo']; ?></textarea>
</label>
</div>
<?php
if ($_REQUEST['saiu03moduloasociado'] != 0) {
?>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu03moduloasociado'];
?>
</label>
<label>
<div id="div_saiu03moduloasociado">
<?php
echo $html_saiu03moduloasociado;
?>
</div>
</label>
<?php
} else {
?>
<input id="saiu03moduloasociado" name="saiu03moduloasociado" type="hidden" value="<?php echo $_REQUEST['saiu03moduloasociado']; ?>" />
<?php
}
?>
<div class="salto1px"></div>
</div>

<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo $ETI['msg_usomodulos'];
?>
</label>
<div class="salto1px"></div>
<label class="Label200">
<?php
echo $ETI['saiu03ordenllamada'];
?>
</label>
<label class="Label160">
<?php
echo $html_saiu03ordenllamada;
?>
</label>
<label class="Label200">
<?php
echo $ETI['saiu03ordenchat'];
?>
</label>
<label class="Label160">
<?php
echo $html_saiu03ordenchat;
?>
</label>
<label class="Label200">
<?php
echo $ETI['saiu03ordencorreo'];
?>
</label>
<label class="Label160">
<?php
echo $html_saiu03ordencorreo;
?>
</label>
<div class="salto1px"></div>
<label class="Label200">
<?php
echo $ETI['saiu03ordenpresencial'];
?>
</label>
<label class="Label160">
<?php
echo $html_saiu03ordenpresencial;
?>
</label>
<label class="Label200">
<?php
echo $ETI['saiu03ordensoporte'];
?>
</label>
<label class="Label160">
<?php
echo $html_saiu03ordensoporte;
?>
</label>
<label class="Label200">
<?php
echo $ETI['saiu03ordenpqrs'];
?>
</label>
<label class="Label160">
<?php
echo $html_saiu03ordenpqrs;
?>
</label>
<div class="salto1px"></div>
<label class="Label200">
<?php
echo $ETI['saiu03ordentramites'];
?>
</label>
<label class="Label160">
<?php
echo $html_saiu03ordentramites;
?>
</label>
<label class="Label200">
<?php
echo $ETI['saiu03ordencorresp'];
?>
</label>
<label class="Label160">
<?php
echo $html_saiu03ordencorresp;
?>
</label>
<label class="Label200">
<?php
echo $ETI['saiu03ordenfav'];
?>
</label>
<label class="Label160">
<?php
echo $html_saiu03ordenfav;
?>
</label>
<div class="salto1px"></div>
</div>
<?php
// -- Inicia Grupo campos 3004 Anexos
?>
<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo $ETI['titulo_3004'];
?>
</label>
<input id="boculta3004" name="boculta3004" type="hidden" value="<?php echo $_REQUEST['boculta3004']; ?>" />
<?php
if ($_REQUEST['paso'] == 2) {
//if ($bCondicion){
?>
<div class="ir_derecha" <?php echo $sAnchoExpandeContrae; ?>>
<!--
<label class="Label30">
<input id="btexcel3004" name="btexcel3004" type="button" value="Exportar" class="btMiniExcel" onclick="imprime3004();" title="Exportar"/>
</label>
-->
<label class="Label30">
<input id="btexpande3004" name="btexpande3004" type="button" value="Expandir" class="btMiniExpandir" onclick="expandepanel(3004,'block',0);" title="<?php echo $ETI['bt_mostrar']; ?>" style="display:<?php if ($_REQUEST['boculta3004'] == 0) {
echo 'none';
} else {
echo 'block';
} ?>;" />
</label>
<label class="Label30">
<input id="btrecoge3004" name="btrecoge3004" type="button" value="Recoger" class="btMiniRecoger" onclick="expandepanel(3004,'none',1);" title="<?php echo $ETI['bt_ocultar']; ?>" style="display:<?php if ($_REQUEST['boculta3004'] == 0) {
echo 'block';
} else {
echo 'none';
} ?>;" />
</label>
</div>
<div class="salto1px"></div>
<div id="div_p3004" style="display:<?php if ($_REQUEST['boculta3004'] == 0) {
echo 'block';
} else {
echo 'none';
} ?>;">
<label class="Label130">
<?php
echo $ETI['saiu04consec'];
?>
</label>
<label class="Label130">
<div id="div_saiu04consec">
<?php
if ((int)$_REQUEST['saiu04id'] == 0) {
?>
<input id="saiu04consec" name="saiu04consec" type="text" value="<?php echo $_REQUEST['saiu04consec']; ?>" onchange="revisaf3004()" class="cuatro" />
<?php
} else {
echo html_oculto('saiu04consec', $_REQUEST['saiu04consec'], formato_numero($_REQUEST['saiu04consec']));
}
?>
</div>
</label>
<label class="Label60"<?php echo $sOcultaId; ?>>
<?php
echo $ETI['saiu04id'];
?>
</label>
<label class="Label60"<?php echo $sOcultaId; ?>>
<div id="div_saiu04id">
<?php
echo html_oculto('saiu04id', $_REQUEST['saiu04id'], formato_numero($_REQUEST['saiu04id']));
?>
</div>
</label>
<label class="Label60">
<?php
echo $ETI['saiu04idetapa'];
?>
</label>
<label class="Label60">
<?php
echo $html_saiu04idetapa;
?>
</label>
<label class="Label60">
<?php
echo $ETI['saiu04orden'];
?>
</label>
<label class="Label130">
<input id="saiu04orden" name="saiu04orden" type="text" value="<?php echo $_REQUEST['saiu04orden']; ?>" class="diez" maxlength="10" placeholder="<?php echo $ETI['ing_vr']; ?>" />
</label>
<label class="Label60">
<?php
echo $ETI['saiu04activo'];
?>
</label>
<label class="Label60">
<?php
echo $html_saiu04activo;
?>
</label>
<label class="Label90">
<?php
echo $ETI['saiu04obligatorio'];
?>
</label>
<label class="Label60">
<?php
echo $html_saiu04obligatorio;
?>
</label>
<label class="L">
<?php
echo $ETI['saiu04titulo'];
?>

<input id="saiu04titulo" name="saiu04titulo" type="text" value="<?php echo $_REQUEST['saiu04titulo']; ?>" maxlength="100" class="L" placeholder="<?php echo $ETI['ing_campo'] . $ETI['saiu04titulo']; ?>" />
</label>

<div class="GrupoCampos520">
<label class="txtAreaS">
<?php
echo $ETI['saiu04descripcion'];
?>
<textarea id="saiu04descripcion" name="saiu04descripcion" placeholder="<?php echo $ETI['ing_campo'] . $ETI['saiu04descripcion']; ?>"><?php echo $_REQUEST['saiu04descripcion']; ?></textarea>
</label>
<div class="salto1px"></div>
</div>

<input id="saiu04idorigenforma" name="saiu04idorigenforma" type="hidden" value="<?php echo $_REQUEST['saiu04idorigenforma']; ?>" />
<input id="saiu04idarchforma" name="saiu04idarchforma" type="hidden" value="<?php echo $_REQUEST['saiu04idarchforma']; ?>" />
<div class="GrupoCampos300">
<div class="salto1px"></div>
<div id="div_saiu04idarchforma" class="Campo220">
<?php
echo html_lnkarchivo((int)$_REQUEST['saiu04idorigenforma'], (int)$_REQUEST['saiu04idarchforma']);
?>
</div>
<label class="Label30">
<input type="button" id="banexasaiu04idarchforma" name="banexasaiu04idarchforma" value="Anexar" class="btAnexarS" onclick="carga_saiu04idarchforma()" title="Cargar archivo" style="display:<?php if ((int)$_REQUEST['saiu04id'] != 0) {
echo 'block';
} else {
echo 'none';
} ?>;" />
</label>
<label class="Label30">
<input type="button" id="beliminasaiu04idarchforma" name="beliminasaiu04idarchforma" value="Eliminar" class="btBorrarS" onclick="eliminasaiu04idarchforma()" title="Eliminar archivo" style="display:<?php if ((int)$_REQUEST['saiu04idarchforma'] != 0) {
echo 'block';
} else {
echo 'none';
} ?>;" />
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['saiu04idtipogd'];
?>
</label>
<label class="Label90">
<div id="div_saiu04idtipogd">
<?php
echo html_oculto('saiu04idtipogd', $_REQUEST['saiu04idtipogd']);
?>
</div>
</label>
<div class="salto1px"></div>
</div>

<div class="salto1px"></div>
<label class="Label130">&nbsp;</label>
<label class="Label30">
<input id="bguarda3004" name="bguarda3004" type="button" value="Guardar" class="btMiniGuardar" onclick="guardaf3004()" title="<?php echo $ETI['bt_mini_guardar_3004']; ?>" />
</label>
<label class="Label30">
<input id="blimpia3004" name="blimpia3004" type="button" value="Limpiar" class="btMiniLimpiar" onclick="limpiaf3004()" title="<?php echo $ETI['bt_mini_limpiar_3004']; ?>" />
</label>
<label class="Label30">
<input id="belimina3004" name="belimina3004" type="button" value="Eliminar" class="btMiniEliminar" onclick="eliminaf3004()" title="<?php echo $ETI['bt_mini_eliminar_3004']; ?>" style="display:<?php if ((int)$_REQUEST['saiu04id'] != 0) {
echo 'block';
} else {
echo 'none';
} ?>;" />
</label>
<?php
//Este es el cierre del div_p3004
?>
<div class="salto1px"></div>
</div>
<?php
//} //Termina el segundo bloque  condicional - bloque editar.
?>
<div class="salto1px"></div>
<?php
if (false) {
?>
<div class="ir_derecha">
<label class="Label130">
<?php
echo $ETI['msg_nombre'];
?>
</label>
<label>
<input id="bnombre3004" name="bnombre3004" type="text" value="<?php echo $_REQUEST['bnombre3004']; ?>" onchange="paginarf3004()" />
</label>
<label class="Label130">
<?php
echo $ETI['msg_Listar'];
?>
</label>
<label>
<?php
echo $html_blistar3004;
?>
</label>
<div class="salto1px"></div>
</div>
<?php
}
?>
<div id="div_f3004detalle">
<?php
echo $sTabla3004;
?>
</div>
<?php
}
?>
<div class="salto1px"></div>
</div>
<?php
// -- Termina Grupo campos 3004 Anexos
?>
<?php
if (false) {
	//Ejemplo de boton de ayuda
	//echo html_BotonAyuda('NombreCampo');
	//echo html_DivAyudaLocal('NombreCampo');
}
if ($bConExpande) {
	//Este es el cierre del div_p3003
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
<label class="Label160">
<?php
echo $ETI['saiu03titulo'];
?>
</label>
<label>
<input id="bnombre" name="bnombre" type="text" value="<?php echo $_REQUEST['bnombre']; ?>" onchange="paginarf3003()" autocomplete="off" />
</label>
<?php
if (false) {
?>
<label class="Label160">
<?php
echo $ETI['saiu03tiposol'];
?>
</label>
<label>
<?php
echo $html_blistar;
?>
</label>
<?php
}
?>
<label class="Label160">
<?php
echo $ETI['saiu03idunidadresp1'];
?>
</label>
<label class="Label450">
<?php
echo $html_bunidad;
?>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['msg_ordenarpor'];
?>
</label>
<label>
<?php
echo $html_borden;
?>
</label>
<label class="Label160">
<?php
echo $ETI['saiu03tiposol'];
?>
</label>
<label class="Label450">
<?php
echo $html_btiposol;
?>
</label>

</div>
<div class="salto1px"></div>
<?php
echo ' ' . $csv_separa;
?>
<div id="div_f3003detalle">
<?php
echo $sTabla3003;
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
echo $ETI['msg_saiu03consec'];
?>
</label>
<label class="Label90">
<?php
echo '<b>' . $_REQUEST['saiu03consec'] . '</b>';
?>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['msg_saiu03consec_nuevo'];
// onchange="RevisaConsec()"
?>
</label>
<label class="Label90">
<input id="saiu03consec_nuevo" name="saiu03consec_nuevo" type="text" value="<?php echo $_REQUEST['saiu03consec_nuevo']; ?>" class="cuatro" />
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
<input id="titulo_3003" name="titulo_3003" type="hidden" value="<?php echo $sTituloModulo; ?>" />
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
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>js/jquery.autocomplete.css" type="text/css" />
<script language="javascript" src="<?php echo $APP->rutacomun; ?>js/jquery.autocomplete.js"></script>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>js/chosen.jquery.js"></script>
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>js/chosen.css" type="text/css" />
<script language="javascript">
	$().ready(function() {
		<?php
		if ($_REQUEST['paso'] == 0) {
		?>
			//$("#saiu03tiposol").chosen();
		<?php
		}
		?>
		$("#saiu03tiposol").chosen();
		$("#btiposol").chosen();
	});
</script>
<script language="javascript" src="ac_3003.js"></script>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>unad_todas2024v2.js"></script>
<?php
forma_piedepagina();

