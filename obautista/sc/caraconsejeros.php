<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2018 - 2025 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.22.3 lunes, 6 de agosto de 2018
--- Modelo Versión 2.25.5 lunes, 24 de agosto de 2020
--- Modelo Versión 2.28.0 jueves, 24 de febrero de 2022
--- Modelo Versión 2.28.1 jueves, 5 de mayo de 2022
*/

/** Archivo caraconsejeros.php.
 * Modulo 2313 cara13consejeros.
 * @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
 * @param debug=1 (Opcional), bandera para indicar si se generan datos de depuración
 * @date Tuesday, October 1, 2019
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
require $APP->rutacomun . 'libdatos.php';
require $APP->rutacomun . 'libhtml.php';
require $APP->rutacomun . 'xajax/xajax_core/xajax.inc.php';
require $APP->rutacomun . 'unad_xajax.php';
require $APP->rutacomun . 'libsc.php';
if (($bPeticionXAJAX) && ($_SESSION['unad_id_tercero'] == 0)) {
	// viene por xajax.
	$xajax = new xajax();
	$xajax->configure('javascript URI', $APP->rutacomun . 'xajax/');
	$xajax->register(XAJAX_FUNCTION, 'sesion_abandona_V2');
	$xajax->processRequest();
	die();
}
$iConsecutivoMenu = 1;
$iMinVerDB = 8042;
$iCodModulo = 2313;
$iCodModuloConsulta = $iCodModulo;
$audita[1] = false;
$audita[2] = true;
$audita[3] = true;
$audita[4] = true;
$audita[5] = false;
// -- Se cargan los archivos de idioma
$sIdioma = AUREA_Idioma();
$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
if (!file_exists($mensajes_todas)) {
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
}
$mensajes_2301 = $APP->rutacomun . 'lg/lg_2301_' . $sIdioma . '.php';
if (!file_exists($mensajes_2301)) {
	$mensajes_2301 = $APP->rutacomun . 'lg/lg_2301_es.php';
}
$mensajes_2313 = 'lg/lg_2313_' . $sIdioma . '.php';
if (!file_exists($mensajes_2313)) {
	$mensajes_2313 = 'lg/lg_2313_es.php';
}
require $mensajes_todas;
require $mensajes_2301;
require $mensajes_2313;
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
$sTituloModulo = $ETI['titulo_2313'];
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
		header('Location:noticia.php?ret=caraconsejeros.php');
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
$idEntidad = Traer_Entidad();
// -- Si esta cargando la pagina por primer vez se revisa si requiere auditar y se manda a hacer un limpiar (paso -1)
if (isset($_REQUEST['paso']) == 0) {
	$_REQUEST['paso'] = -1;
	if ($audita[1]) {
		seg_auditaingreso($iCodModulo, $_SESSION['unad_id_tercero'], $objDB);
	}
}
// -- 2313 cara13consejeros
require 'lib2313.php';
// -- 2301 Encuesta
require $APP->rutacomun . 'lib2301.php';
$xajax = new xajax();
$xajax->configure('javascript URI', $APP->rutacomun . 'xajax/');
$xajax->register(XAJAX_FUNCTION, 'unad11_Mostrar_v2');
$xajax->register(XAJAX_FUNCTION, 'unad11_TraerXid');
$xajax->register(XAJAX_FUNCTION, 'formatear_moneda');
$xajax->register(XAJAX_FUNCTION, 'f2313_Combocara01idcead');
$xajax->register(XAJAX_FUNCTION, 'sesion_abandona_V2');
$xajax->register(XAJAX_FUNCTION, 'sesion_mantenerV4');
$xajax->register(XAJAX_FUNCTION, 'f2313_HtmlTabla');
$xajax->register(XAJAX_FUNCTION, 'f2313_ExisteDato');
$xajax->register(XAJAX_FUNCTION, 'f2313_Busquedas');
$xajax->register(XAJAX_FUNCTION, 'f2313_HtmlBusqueda');
$xajax->register(XAJAX_FUNCTION, 'f2301_HtmlTablaConsejero');
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
if (isset($_REQUEST['paginaf2313']) == 0) {
	$_REQUEST['paginaf2313'] = 1;
}
if (isset($_REQUEST['lppf2313']) == 0) {
	$_REQUEST['lppf2313'] = 20;
}
if (isset($_REQUEST['boculta2313']) == 0) {
	$_REQUEST['boculta2313'] = 0;
}
if (isset($_REQUEST['paginaf2301']) == 0) {
	$_REQUEST['paginaf2301'] = 1;
}
if (isset($_REQUEST['lppf2301']) == 0) {
	$_REQUEST['lppf2301'] = 20;
}
if (isset($_REQUEST['boculta2301']) == 0) {
	$_REQUEST['boculta2301'] = 0;
}
// -- Inicializar variables de datos.
if (isset($_REQUEST['cara13peraca']) == 0) {
	$_REQUEST['cara13peraca'] = '';
}
if (isset($_REQUEST['cara13idconsejero']) == 0) {
	$_REQUEST['cara13idconsejero'] = 0;
} // {$_SESSION['unad_id_tercero'];}
if (isset($_REQUEST['cara13idconsejero_td']) == 0) {
	$_REQUEST['cara13idconsejero_td'] = $APP->tipo_doc;
}
if (isset($_REQUEST['cara13idconsejero_doc']) == 0) {
	$_REQUEST['cara13idconsejero_doc'] = '';
}
if (isset($_REQUEST['cara13id']) == 0) {
	$_REQUEST['cara13id'] = '';
}
if (isset($_REQUEST['cara13activo']) == 0) {
	$_REQUEST['cara13activo'] = 'S';
}
if (isset($_REQUEST['cara01idzona']) == 0) {
	$_REQUEST['cara01idzona'] = '';
}
if (isset($_REQUEST['cara01idcead']) == 0) {
	$_REQUEST['cara01idcead'] = '';
}
if (isset($_REQUEST['cara01cargaasignada']) == 0) {
	$_REQUEST['cara01cargaasignada'] = '';
}
if (isset($_REQUEST['cara01cargafinal']) == 0) {
	$_REQUEST['cara01cargafinal'] = '';
}
if (isset($_REQUEST['cara01fechafin']) == 0) {
	$_REQUEST['cara01fechafin'] = '';
} //{fecha_hoy();}
if (isset($_REQUEST['cara13cargacentro']) == 0) {
	$_REQUEST['cara13cargacentro'] = 0;
}
if (isset($_REQUEST['cara13permitircargacentro']) == 0) {
	$_REQUEST['cara13permitircargacentro'] = '';
}
$_REQUEST['cara13peraca'] = numeros_validar($_REQUEST['cara13peraca']);
$_REQUEST['cara13idconsejero'] = numeros_validar($_REQUEST['cara13idconsejero']);
$_REQUEST['cara13idconsejero_td'] = cadena_Validar($_REQUEST['cara13idconsejero_td']);
$_REQUEST['cara13idconsejero_doc'] = cadena_Validar($_REQUEST['cara13idconsejero_doc']);
$_REQUEST['cara13id'] = numeros_validar($_REQUEST['cara13id']);
$_REQUEST['cara13activo'] = cadena_Validar($_REQUEST['cara13activo']);
$_REQUEST['cara01idzona'] = numeros_validar($_REQUEST['cara01idzona']);
$_REQUEST['cara01idcead'] = numeros_validar($_REQUEST['cara01idcead']);
$_REQUEST['cara01cargaasignada'] = numeros_validar($_REQUEST['cara01cargaasignada']);
$_REQUEST['cara01cargafinal'] = numeros_validar($_REQUEST['cara01cargafinal']);
$_REQUEST['cara01fechafin'] = numeros_validar($_REQUEST['cara01fechafin']);
$_REQUEST['cara13cargacentro'] = numeros_validar($_REQUEST['cara13cargacentro']);
$_REQUEST['cara13permitircargacentro'] = numeros_validar($_REQUEST['cara13permitircargacentro']);
// Espacio para inicializar otras variables
if (isset($_REQUEST['csv_separa']) == 0) {
	$_REQUEST['csv_separa'] = ',';
}
if (isset($_REQUEST['bnombre']) == 0) {
	$_REQUEST['bnombre'] = '';
}
if (isset($_REQUEST['blistar']) == 0) {
	$_REQUEST['blistar'] = '';
}
if (isset($_REQUEST['bzona']) == 0) {
	$_REQUEST['bzona'] = '';
}
if (isset($_REQUEST['bdoc']) == 0) {
	$_REQUEST['bdoc'] = '';
}
if (isset($_REQUEST['bpoblacion']) == 0) {
	$_REQUEST['bpoblacion'] = '';
}
//Si Modifica o Elimina Cargar los campos
if (($_REQUEST['paso'] == 1) || ($_REQUEST['paso'] == 3)) {
	$_REQUEST['cara13idconsejero_td'] = $APP->tipo_doc;
	$_REQUEST['cara13idconsejero_doc'] = '';
	if ($_REQUEST['paso'] == 1) {
		$sSQLcondi = 'cara13peraca=' . $_REQUEST['cara13peraca'] . ' AND cara13idconsejero="' . $_REQUEST['cara13idconsejero'] . '"';
	} else {
		$sSQLcondi = 'cara13id=' . $_REQUEST['cara13id'] . '';
	}
	$sSQL = 'SELECT * FROM cara13consejeros WHERE ' . $sSQLcondi;
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$_REQUEST['cara13peraca'] = $fila['cara13peraca'];
		$_REQUEST['cara13idconsejero'] = $fila['cara13idconsejero'];
		$_REQUEST['cara13id'] = $fila['cara13id'];
		$_REQUEST['cara13activo'] = $fila['cara13activo'];
		$_REQUEST['cara01idzona'] = $fila['cara01idzona'];
		$_REQUEST['cara01idcead'] = $fila['cara01idcead'];
		$_REQUEST['cara01cargaasignada'] = $fila['cara01cargaasignada'];
		$_REQUEST['cara01cargafinal'] = $fila['cara01cargafinal'];
		$_REQUEST['cara01fechafin'] = $fila['cara01fechafin'];
		$_REQUEST['cara13cargacentro'] = $fila['cara13cargacentro'];
		$_REQUEST['cara13permitircargacentro'] = $fila['cara13permitircargacentro'];
		$bcargo = true;
		$_REQUEST['paso'] = 2;
		$_REQUEST['boculta2313'] = 0;
		$bLimpiaHijos = true;
	} else {
		$_REQUEST['paso'] = 0;
	}
}
//Insertar o modificar un elemento
if (($_REQUEST['paso'] == 10) || ($_REQUEST['paso'] == 12)) {
	$bMueveScroll = true;
	list($_REQUEST, $sError, $iTipoError, $sDebugGuardar) = f2313_db_GuardarV2($_REQUEST, $objDB, $bDebug, $idTercero);
	$sDebug = $sDebug . $sDebugGuardar;
	if ($sError == '') {
		$sError = '<b>' . $ETI['msg_itemguardado'] . '</b>';
		$iTipoError = 1;
	}
}
//Eliminar un elemento
if ($_REQUEST['paso'] == 13) {
	$_REQUEST['paso'] = 2;
	if ($sError == '') {
		list($sError, $iTipoError, $sDebugElimina) = f2313_db_Eliminar($_REQUEST['cara13id'], $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugElimina;
	}
	if ($sError == '') {
		$_REQUEST['paso'] = -1;
		$sError = $ETI['msg_itemeliminado'];
		$iTipoError = 1;
	}
}
$bActualizarCarga = false;
$bCompleta = false;
if ($_REQUEST['paso'] == 21) {
	$_REQUEST['paso'] = -1;
	$bActualizarCarga = true;
	$bCompleta = true;
}
if ($_REQUEST['paso'] == 22) {
	$_REQUEST['paso'] = -1;
	$bActualizarCarga = true;
}
if ($bActualizarCarga) {
	require 'lib2334.php';
	list($sError, $sDebugU) = f2313_ActualizarCarga($_REQUEST['blistar'], $objDB, $bDebug, 0, $bCompleta);
	$sDebug = $sDebug . $sDebugU;
	if ($sError == '') {
		$sError = 'Se ha actualizado la carga asignada a consejeros en el periodo ' . $_REQUEST['blistar'];
		$iTipoError = 1;
	}
}
/* 
// Para reiniciar un periodo --- No se deja en el sistema para que no la embarren...
UPDATE core16actamatricula
SET core16idconsejero=0
WHERE core16peraca=1391 AND core16nuevo=1 AND core16estado NOT IN (9, 10) AND core16idconsejero<>0
*/
if (($_REQUEST['paso'] == 50)) {
	$_REQUEST['paso'] = -1;
	require 'lib2334.php';
	if (!seg_revisa_permiso($iCodModulo, 2, $objDB)) {
		$sError = $ERR['2'];
	}
	if ($sError == '') {
		list($sError, $iTipoError, $sInfoProceso, $sDebugP) = f2313_ProcesarArchivo($_REQUEST, $_FILES, $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugP;
		if ($sInfoProceso != '') {
			$sError = $sError . '<br>' . $sInfoProceso;
		}
	}
}
//limpiar la pantalla
if ($_REQUEST['paso'] == -1) {
	$_REQUEST['cara13peraca'] = '';
	$_REQUEST['cara13idconsejero'] = 0; //$_SESSION['unad_id_tercero'];
	$_REQUEST['cara13idconsejero_td'] = $APP->tipo_doc;
	$_REQUEST['cara13idconsejero_doc'] = '';
	$_REQUEST['cara13id'] = '';
	$_REQUEST['cara13activo'] = 'S';
	$_REQUEST['cara01idzona'] = '';
	$_REQUEST['cara01idcead'] = '';
	$_REQUEST['cara01cargaasignada'] = '';
	$_REQUEST['cara01cargafinal'] = '';
	$_REQUEST['cara01fechafin'] = 0;
	$_REQUEST['cara13cargacentro'] = 0;
	$_REQUEST['cara13permitircargacentro'] = 1;
	$_REQUEST['paso'] = 0;
}
if ($bLimpiaHijos) {
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
//Permisos adicionales
$seg_5 = 0;
$seg_6 = 0;
$seg_8 = 0;
/*
list($bHayImprimir, $sDebugP, $seg_6) = seg_revisa_permisoV3($iCodModulo, 6, $idTercero, $objDB);
*/
if ((int)$_REQUEST['paso'] != 0) {
	//list($bHayImprimir2, $sDebugP, $seg_5) = seg_revisa_permisoV3($iCodModulo, 5, $idTercero, $objDB);
	$bConEliminar = true;
	//list($bDevuelve, $sDebugP, $seg_8) = seg_revisa_permisoV3($iCodModulo, 8, $idTercero, $objDB);
}
//DATOS PARA COMPLETAR EL FORMULARIO
$iAgnoIni=2000;
$iAgnoFin=fecha_agno()+1;
//Crear los controles que requieran llamado a base de datos
$objCombos = new clsHtmlCombos();
$objForma = new clsHtmlForma($iPiel);
$objTercero = new clsHtmlTercero();
$seg_1710 = 0;
list($bPermiso, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 1710, $idTercero, $objDB, $bDebug);
if ($bPermiso) {
	$seg_1710 = 1;
}
$sCondiZona = ' WHERE unad23conestudiantes="S"';
$bVacioZona = true;
if ($seg_1710 == 0) {
	$bVacioZona = false;
	list($sIdZona, $idPrimera, $sDebugZ) = f2300_ZonasTercero($idTercero, $objDB, $bDebug);
	if ($_REQUEST['bzona'] == '') {
		$_REQUEST['bzona'] = $idPrimera;
	}
	if ($_REQUEST['cara01idzona'] == '') {
		$_REQUEST['cara01idzona'] = $idPrimera;
	}
	$sCondiZona = ' WHERE unad23id IN (' . $sIdZona . ') AND unad23conestudiantes="S"';
}
list($cara13idconsejero_rs, $_REQUEST['cara13idconsejero'], $_REQUEST['cara13idconsejero_td'], $_REQUEST['cara13idconsejero_doc']) = html_tercero($_REQUEST['cara13idconsejero_td'], $_REQUEST['cara13idconsejero_doc'], $_REQUEST['cara13idconsejero'], 0, $objDB);
$bOculto = true;
if ($_REQUEST['paso'] != 2) {
	$bOculto = false;
}
$html_cara13idconsejero = html_DivTerceroV8('cara13idconsejero', $_REQUEST['cara13idconsejero_td'], $_REQUEST['cara13idconsejero_doc'], $bOculto, $objDB, $objCombos, 1, $ETI['ing_doc']);
$objCombos->nuevo('cara01idzona', $_REQUEST['cara01idzona'], true, '{' . $ETI['msg_seleccione'] . '}');
$objCombos->sAccion = 'carga_combo_cara01idcead();';
$sSQL = 'SELECT unad23id AS id, unad23nombre AS nombre FROM unad23zona ' . $sCondiZona . ' ORDER BY unad23nombre';
$html_cara01idzona = $objCombos->html($sSQL, $objDB);

$objCombos->nuevo('cara13activo', $_REQUEST['cara13activo'], false);
$objCombos->sino($ETI['si'], $ETI['no']); //, $sValorSi='S', $sValorNo='N'
$html_cara13activo = $objCombos->html('', $objDB);
$html_cara01idcead = f2313_HTMLComboV2_cara01idcead($objDB, $objCombos, $_REQUEST['cara01idcead'], $_REQUEST['cara01idzona']);
$objCombos->nuevo('cara13permitircargacentro', $_REQUEST['cara13permitircargacentro'], true, $ETI['no'], 0);
$objCombos->addItem(1, $ETI['si']);
//$objCombos->addArreglo($acara13permitircargacentro, $icara13permitircargacentro);
$html_cara13permitircargacentro = $objCombos->html('', $objDB);
if ((int)$_REQUEST['paso'] == 0) {
	$html_cara13peraca = f2313_HTMLComboV2_cara13peraca($objDB, $objCombos, $_REQUEST['cara13peraca']);
} else {
	$cara13peraca_nombre = '&nbsp;';
	if ((int)$_REQUEST['cara13peraca'] != 0) {
		list($cara13peraca_nombre, $sErrorDet) = tabla_campoxid('exte02per_aca', 'exte02nombre', 'exte02id', $_REQUEST['cara13peraca'], '{' . $ETI['msg_sindato'] . '}', $objDB);
	}
	$html_cara13peraca = html_oculto('cara13peraca', $_REQUEST['cara13peraca'], $cara13peraca_nombre);
	//list($cara01idzona_nombre, $sErrorDet)=tabla_campoxid('unad23zona','unad23nombre','unad23id',$_REQUEST['cara01idzona'],'{'.$ETI['msg_sindato'].'}', $objDB);
	//$html_cara01idzona=html_oculto('cara01idzona', $_REQUEST['cara01idzona'], $cara01idzona_nombre);
}
//Alistar datos adicionales
$id_rpt = 0;
//$id_rpt=reportes_id(_Identificador_Tipo_Reporte_, $objDB);
$objCombos->nuevo('blistar', $_REQUEST['blistar'], true, '{' . $ETI['msg_todos'] . '}');
$objCombos->iAncho = 600;
$objCombos->sAccion = 'paginarf2313()';
$sSQL = f146_ConsultaCombo(2216, $objDB);
$html_blistar = $objCombos->html($sSQL, $objDB);
$objCombos->nuevo('bzona', $_REQUEST['bzona'], $bVacioZona, '{' . $ETI['msg_todas'] . '}');
if ($bVacioZona) {
	$objCombos->addItem('-99', '{Sin zona}');
}
$objCombos->sAccion = 'paginarf2313()';
$sSQL = 'SELECT unad23id AS id, unad23nombre AS nombre FROM unad23zona ' . $sCondiZona . ' ORDER BY unad23nombre';
$html_bzona = $objCombos->html($sSQL, $objDB);
$objCombos->nuevo('bpoblacion', $_REQUEST['bpoblacion'], true, '{' . $ETI['msg_todos'] . '}');
$objCombos->sAccion = 'paginarf2313()';
$objCombos->addItem(1, 'Con asignaci&oacute;n de centro');
$sSQL = '';
$html_bpoblacion = $objCombos->html($sSQL, $objDB);
//$html_blistar=$objCombos->comboSistema(2313, 1, $objDB, 'paginarf2313()');

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
$iModeloReporte = 2313;
//Cargar las tablas de datos
$aParametros[0] = ''; //$_REQUEST['p1_2313'];
$aParametros[100] = $idTercero;
$aParametros[101] = $_REQUEST['paginaf2313'];
$aParametros[102] = $_REQUEST['lppf2313'];
$aParametros[103] = $_REQUEST['bnombre'];
$aParametros[104] = $_REQUEST['blistar'];
$aParametros[105] = $_REQUEST['bzona'];
$aParametros[106] = $_REQUEST['bdoc'];
$aParametros[107] = $_REQUEST['bpoblacion'];
list($sTabla2313, $sDebugTabla) = f2313_TablaDetalleV2($aParametros, $objDB, $bDebug);
$sDebug = $sDebug . $sDebugTabla;
$sTabla2301 = '';
if ($_REQUEST['paso'] != 0) {
	//Encuesta
	$aParametros2301[100] = $_REQUEST['cara13idconsejero'];
	$aParametros2301[101] = $_REQUEST['paginaf2301'];
	$aParametros2301[102] = $_REQUEST['lppf2301'];
	$aParametros2301[103] = $_REQUEST['cara13peraca'];
	list($sTabla2301, $sDebugTabla) = f2301_TablaDetalleV2Consejero($aParametros2301, $objDB, $bDebug);
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
			if (idcampo == 'cara13idconsejero') {
				params[4] = 'RevisaLlave';
			}
			xajax_unad11_TraerXid(params);
		}
	}

	function imprimelista() {
		if (window.document.frmedita.seg_6.value == 1) {
			window.document.frmlista.consulta.value = window.document.frmedita.consulta_2313.value;
			window.document.frmlista.titulos.value = window.document.frmedita.titulos_2313.value;
			window.document.frmlista.nombrearchivo.value = 'Consejeros';
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
			window.document.frmimpp.action = 'e2313.php';
			window.document.frmimpp.submit();
		} else {
			ModalMensaje(sError);
		}
	}

	function imprimep() {
		if (window.document.frmedita.seg_5.value == 1) {
			asignarvariables();
			window.document.frmimpp.action = 'p2313.php';
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
		datos[1] = window.document.frmedita.cara13peraca.value;
		datos[2] = window.document.frmedita.cara13idconsejero.value;
		if ((datos[1] != '') && (datos[2] != '')) {
			xajax_f2313_ExisteDato(datos);
		}
	}

	function cargadato(llave1, llave2) {
		window.document.frmedita.cara13peraca.value = String(llave1);
		window.document.frmedita.cara13idconsejero.value = String(llave2);
		window.document.frmedita.paso.value = 1;
		window.document.frmedita.submit();
	}

	function cargaridf2313(llave1) {
		window.document.frmedita.cara13id.value = String(llave1);
		window.document.frmedita.paso.value = 3;
		window.document.frmedita.submit();
	}

	function carga_combo_cara01idcead() {
		let params=new Array();
		params[0] = window.document.frmedita.cara01idzona.value;
		document.getElementById('div_cara01idcead').innerHTML = '<b>Procesando datos, por favor espere...</b><input id="cara01idcead" name="cara01idcead" type="hidden" value="" />';
		xajax_f2313_Combocara01idcead(params);
	}

	function paginarf2313() {
		let params = new Array();
		params[99] = window.document.frmedita.debug.value;
		params[100] = <?php echo $idTercero; ?>;
		params[101] = window.document.frmedita.paginaf2313.value;
		params[102] = window.document.frmedita.lppf2313.value;
		params[103] = window.document.frmedita.bnombre.value;
		params[104] = window.document.frmedita.blistar.value;
		params[105] = window.document.frmedita.bzona.value;
		params[106] = window.document.frmedita.bdoc.value;
		params[107] = window.document.frmedita.bpoblacion.value;
		document.getElementById('div_f2313detalle').innerHTML = '<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf2313" name="paginaf2313" type="hidden" value="' + params[101] + '" /><input id="lppf2313" name="lppf2313" type="hidden" value="' + params[102] + '" />';
		xajax_f2313_HtmlTabla(params);
	}

	function f2313_cargamasiva() {
		extensiones_permitidas = new Array(".xls", ".xlsx");
		let sError = '';
		let archivo = window.document.frmedita.archivodatos.value;
		if (sError == '') {
			if (!archivo) {
				sError = "No has seleccionado ning\u00fan archivo";
			}
		}
		if (sError == '') {
			//recupero la extensión de este nombre de archivo
			extension = (archivo.substring(archivo.lastIndexOf("."))).toLowerCase();
			//compruebo si la extensión está entre las permitidas
			permitida = false;
			for (var i = 0; i < extensiones_permitidas.length; i++) {
				if (extensiones_permitidas[i] == extension) {
					permitida = true;
					break;
				}
			}
			if (!permitida) {
				sError = "Comprueba la extensi\u00f3n de los archivos a subir. \nS\u00f3lo se pueden subir archivos con extensiones: " + extensiones_permitidas.join();
			} else {
				expandesector(98);
				window.document.frmedita.paso.value = 50;
				window.document.frmedita.submit();
				return 1;
			}
		}
		//si estoy aqui es que no se ha podido submitir
		MensajeAlarmaV2(sError, 0);
		return 0;
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
		document.getElementById("cara13peraca").focus();
	}

	function buscarV2016(sCampo) {
		window.document.frmedita.iscroll.value = window.scrollY;
		expandesector(98);
		window.document.frmedita.scampobusca.value = sCampo;
		let params = new Array();
		params[1] = sCampo;
		//params[2]=window.document.frmedita.iagno.value;
		//params[3]=window.document.frmedita.itipo.value;
		xajax_f2313_Busquedas(params);
	}

	function Devuelve(sValor) {
		let sCampo = window.document.frmedita.scampobusca.value;
		if (sCampo == 'cara13idconsejero') {
			ter_traerxid('cara13idconsejero', sValor);
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
	
	function actualizarcarga() {
		window.document.frmedita.iscroll.value = window.pageYOffset;
		if (window.document.frmedita.blistar.value == '') {
			ModalMensaje("Por favor seleccione un periodo para ejecutar el proceso");
			window.document.frmedita.blistar.focus();
			MensajeAlarmaV2('Por favor seleccione un periodo para ejecutar el proceso', 0);
		} else {
			ModalConfirmV2('Esto asignar&aacute; a los estudiantes nuevos que no hayan matriculado catedra a los tutores que tengan menor carga<br>Por favor asegurese de que se haya hecho totalmente la asignaci&oacute;n docente<br>Esta seguro de continuar?', () => {
				ejecuta_actualizarcarga();
			});
		}
	}

	function ejecuta_actualizarcarga() {
		MensajeAlarmaV2('<?php echo $ETI['msg_ejecutando']; ?>', 2);
		expandesector(98);
		window.document.frmedita.paso.value = 21;
		window.document.frmedita.submit();
	}

	function totalizarcarga() {
		if (window.document.frmedita.blistar.value == '') {
			ModalMensaje("Por favor seleccione un periodo para ejecutar el proceso");
			window.document.frmedita.blistar.focus();
			MensajeAlarmaV2('Por favor seleccione un periodo para ejecutar el proceso', 0);
		} else {
			window.document.frmedita.iscroll.value = window.pageYOffset;
			expandesector(98);
			MensajeAlarmaV2('Totalizando carga asignada a consejeros en el periodo ' + window.document.frmedita.blistar.value, 2);
			window.document.frmedita.paso.value = 22;
			window.document.frmedita.submit();
		}
	}

	function paginarf2301() {
		let params = new Array();
		params[99] = window.document.frmedita.debug.value;
		params[100] = window.document.frmedita.cara13idconsejero.value;
		params[101] = window.document.frmedita.paginaf2301.value;
		params[102] = window.document.frmedita.lppf2301.value;
		params[103] = window.document.frmedita.cara13peraca.value;
		document.getElementById('div_f2301detalle').innerHTML = '<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf2301" name="paginaf2301" type="hidden" value="' + params[101] + '" /><input id="lppf2301" name="lppf2301" type="hidden" value="' + params[102] + '" />';
		xajax_f2301_HtmlTablaConsejero(params);
	}
</script>
<?php
if ($_REQUEST['paso']!=0){
?>
<form id="frmimpp" name="frmimpp" method="post" action="p2313.php" target="_blank" style="display:none">
<input id="r" name="r" type="hidden" value="2313" />
<input id="id2313" name="id2313" type="hidden" value="<?php echo $_REQUEST['cara13id']; ?>" />
<input id="v3" name="v3" type="hidden" value="" />
<input id="v4" name="v4" type="hidden" value="" />
<input id="v5" name="v5" type="hidden" value="" />
<input id="iformato94" name="iformato94" type="hidden" value="0" />
<input id="separa" name="separa" type="hidden" value="," />
<input id="rdebug" name="rdebug" type="hidden" value="<?php echo $_REQUEST['debug']; ?>"/>
<input id="clave" name="clave" type="hidden" value="" />
</form>
<?php
	}
?>
<form id="frmlista" name="frmlista" method="post" action="listados.php" target="_blank" style="display:none">
<input id="titulos" name="titulos" type="hidden" value="" />
<input id="consulta" name="consulta" type="hidden" value="" />
<input id="nombrearchivo" name="nombrearchivo" type="hidden" value="" />
</form>
<div id="interna">
<form id="frmedita" name="frmedita" method="post" action="" enctype="multipart/form-data" autocomplete="off">
<input id="bNoAutocompletar" name="bNoAutocompletar" type="password" value="" style="display:none;"/>
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
echo $objForma->htmlExpande(2313, $_REQUEST['boculta2313'], $ETI['bt_mostrar'], $ETI['bt_ocultar']);
$sEstiloDiv = '';
if ($_REQUEST['boculta2313'] != 0) {
	$sEstiloDiv = ' style="display:none;"';
}
?>
</div>
<div id="div_p2313" <?php echo $sEstiloDiv; ?>>
<?php
}
//Mostrar formulario para editar
?>
<label class="Label130">
<?php
echo $ETI['cara13peraca'];
?>
</label>
<label class="Label600">
<?php
echo $html_cara13peraca;
?>
</label>
<div class="salto1px"></div>
<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo $ETI['cara13idconsejero'];
?>
</label>
<div class="salto1px"></div>
<input id="cara13idconsejero" name="cara13idconsejero" type="hidden" value="<?php echo $_REQUEST['cara13idconsejero']; ?>"/>
<div id="div_cara13idconsejero_llaves">
<?php
echo $html_cara13idconsejero;
?>
</div>
<div class="salto1px"></div>
<div id="div_cara13idconsejero" class="L"><?php echo $cara13idconsejero_rs; ?></div>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['cara01cargaasignada'];
?>
</label>
<label class="Label60">
<div align="right">
<?php
echo  $_REQUEST['cara01cargafinal'].' /';
?>
</div>
</label>
<label class="Label130">
<input id="cara01cargaasignada" name="cara01cargaasignada" type="text" value="<?php echo $_REQUEST['cara01cargaasignada']; ?>" class="diez" maxlength="10" placeholder="<?php echo $ETI['ing_vr']; ?>"/>
</label>
<input id="cara01cargafinal" name="cara01cargafinal" type="hidden" value="<?php echo $_REQUEST['cara01cargafinal']; ?>"/>
<div class="salto1px"></div>
<label class="Label200">
<?php
echo $ETI['cara13permitircargacentro'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara13permitircargacentro;
?>
</label>
<div class="salto1px"></div>
<label class="Label200">
<?php
echo $ETI['cara13cargacentro'];
?>
</label>
<label class="Label90"><div id="div_cara13cargacentro">
<?php
echo html_oculto('cara13cargacentro', $_REQUEST['cara13cargacentro']);
?>
</div></label>
<div class="salto1px"></div>
</div>

<div class="GrupoCampos450">
<label class="Label60">
<?php
echo $ETI['cara13activo'];
?>
</label>
<label class="Label90">
<?php
echo $html_cara13activo;
?>
</label>
<label class="Label60">
<?php
echo $ETI['cara13id'];
?>
</label>
<label class="Label60">
<?php
echo html_oculto('cara13id', $_REQUEST['cara13id']);
?>
</label>
<div class="salto1px"></div>
<label class="Label60">
<?php
echo $ETI['cara01idzona'];
?>
</label>
<label>
<?php
echo $html_cara01idzona;
?>
</label>
<div class="salto1px"></div>
<label class="Label60">
<?php
echo $ETI['cara01idcead'];
?>
</label>
<label>
<div id="div_cara01idcead">
<?php
echo $html_cara01idcead;
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['cara01fechafin'];
?>
</label>
<div class="Campo220">
<?php
echo html_FechaEnNumero('cara01fechafin', $_REQUEST['cara01fechafin'], true, '', $iAgnoIni, $iAgnoFin);
?>
</div>
<?php
if (false){
?>
<label class="Label30">
<input id="bcara01fechafin_hoy" name="bcara01fechafin_hoy" type="button" value="Hoy" class="btMiniHoy" onclick="fecha_AsignarNum('cara01fechafin','<?php echo $iHoy; ?>')" title="<?php echo $ETI['bt_hoy']; ?>"/>
</label>
<?php
	}
?>
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo $ETI['titulo_2301'];
?>
</label>
<input id="boculta2301" name="boculta2301" type="hidden" value="<?php echo $_REQUEST['boculta2301']; ?>" />
<?php
if ($_REQUEST['paso']==2){
?>
<div id="div_f2301detalle">
<?php
echo $sTabla2301;
?>
</div>
<?php
	}
?>
<div class="salto1px"></div>
</div>

<div class="salto1px"></div>
<?php
// -- Inicia la carga masiva
if ($_REQUEST['paso']==0){
?>
<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo $ETI['msg_plano'];
?>
</label>
<div class="salto1px"></div>
<input id="MAX_FILE_SIZE" name="MAX_FILE_SIZE" type="hidden" value="1000000" />
<label class="Label500">
<input id="archivodatos" name="archivodatos" type="file" />
</label>
<label class="Label130">
<?php
echo $objForma->htmlBotonSolo('cmdanexar', 'botonAnexar', 'f2313_cargamasiva()', $ETI['msg_subir']);
?>
</label>
<div class="salto1px"></div>
<div class="GrupoCamposAyuda">
<?php
echo $ETI['msg_infoplano'];
?>
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
</div>
<?php
	}
//Termina la carga masiva.
if (false){
	//Ejemplo de boton de ayuda
	//echo html_BotonAyuda('NombreCampo');
	//echo html_DivAyudaLocal('NombreCampo');
	}
if ($bConExpande){
	//Este es el cierre del div_p2313
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
<label class="Label90">
<?php
echo $ETI['msg_bdoc'];
?>
</label>
<label>
<input id="bdoc" name="bdoc" type="text" value="<?php echo $_REQUEST['bdoc']; ?>" onchange="paginarf2313()" autocomplete="off"/>
</label>
<label class="Label90">
<?php
echo $ETI['msg_bnombre'];
?>
</label>
<label>
<input id="bnombre" name="bnombre" type="text" value="<?php echo $_REQUEST['bnombre']; ?>" onchange="paginarf2313()" autocomplete="off"/>
</label>
<?php
echo $objForma->htmlBotonSolo('cmdTotalizaCarga', 'btMiniActualizar', 'totalizarcarga()', 'Totalizar carga asignada', 30);
echo $objForma->htmlBotonSolo('cmdActualizaCarga', 'btMiniMas', 'actualizarcarga()', 'Actualizar carga asignada [Agrega carga por centro]', 30);
?>
<div class="salto1px"></div>
<label class="Label90">
Periodo
</label>
<label class="Label600">
<?php
echo $html_blistar;
?>
</label>
<div class="salto1px"></div>
<label class="Label90">
Zona
</label>
<label>
<?php
echo $html_bzona;
?>
</label>
<label class="Label90">
Listar
</label>
<label class="Label160">
<?php
echo $html_bpoblacion;
?>
</label>
<div class="salto1px"></div>
<?php
echo ' '.$csv_separa;
?>
<div class="salto1px"></div>
<div id="div_f2313detalle">
<?php
echo $sTabla2313;
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
<input id="cmdAyuda2" name="cmdAyuda2" type="button" class="btSupAyuda" onclick="muestraayuda(<?php echo $iCodModulo; ?>);" title="<?php echo $ETI['bt_ayuda']; ?>" value="<?php echo $ETI['bt_ayuda']; ?>"/>
<input id="cmdVolverSec2" name="cmdVolverSec2" type="button" class="btSupVolver" onclick="expandesector(1);" title="<?php echo $ETI['bt_volver']; ?>" value="<?php echo $ETI['bt_volver']; ?>"/>
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
<input id="titulo_2313" name="titulo_2313" type="hidden" value="<?php echo $sTituloModulo; ?>" />
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
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>js/chosen.css" type="text/css"/>
<script language="javascript">
$().ready(function(){
<?php
if ($_REQUEST['paso']==0){
?>
$("#cara13peraca").chosen();
<?php
}
?>
$("#blistar").chosen();
});
</script>
<script language="javascript" src="ac_2313.js"></script>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>unad_todas2024v2.js"></script>
<?php
forma_piedepagina();

