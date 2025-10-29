<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2025 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.22.1 jueves, 5 de julio de 2018
--- omar.bautista@unad.edu.co
--- Modelo Versión 3.0.17 martes, 16 de septiembre de 2025
*/
/** Archivo carapregfi.php.
 * Modulo 2308 cara08pregunta. Fisica
 * @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
 * @param debug = 1  (Opcional), bandera para indicar si se generan datos de depuración
 * @date martes, 16 de septiembre de 2025
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
$iMinVerDB = 8963;
$iCodModulo = 2355;
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
$mensajes_2300 = 'lg/lg_2300_' . $sIdioma . '.php';
if (!file_exists($mensajes_2300)) {
	$mensajes_2300 = 'lg/lg_2300_es.php';
}
require $mensajes_2300;
*/
$mensajes_2308 = 'lg/lg_2308_' . $sIdioma . '.php';
if (!file_exists($mensajes_2308)) {
	$mensajes_2308 = 'lg/lg_2308_es.php';
}
require $mensajes_todas;
require $mensajes_2308;
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
$sTituloModulo = $ETI['titulo_2355'];
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
		header('Location:noticia.php?ret=carapregcd.php');
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
$mensajes_2309 = 'lg/lg_2309_' . $sIdioma . '.php';
if (!file_exists($mensajes_2309)) {
	$mensajes_2309 = 'lg/lg_2309_es.php';
}
$mensajes_2317 = 'lg/lg_2317_' . $sIdioma . '.php';
if (!file_exists($mensajes_2317)) {
	$mensajes_2317 = 'lg/lg_2317_es.php';
}
require $mensajes_2309;
require $mensajes_2317;
// -- Si esta cargando la pagina por primer vez se revisa si requiere auditar y se manda a hacer un limpiar (paso -1)
if (isset($_REQUEST['paso']) == 0) {
	$_REQUEST['paso'] = -1;
	if ($audita[1]) {
		seg_auditaingreso($iCodModulo, $_SESSION['unad_id_tercero'], $objDB);
	}
}
// -- 2308 cara08pregunta
require 'lib2308.php';
// -- 2309 Respuestas
require 'lib2309.php';
// -- 2317 Anexos
require 'lib2317.php';
$xajax = new xajax();
$xajax->configure('javascript URI', $APP->rutacomun . 'xajax/');
$xajax->register(XAJAX_FUNCTION, 'sesion_abandona_V2');
$xajax->register(XAJAX_FUNCTION, 'sesion_mantenerV4');
$xajax->register(XAJAX_FUNCTION, 'f2308_HtmlTabla');
$xajax->register(XAJAX_FUNCTION, 'f2308_ExisteDato');
$xajax->register(XAJAX_FUNCTION, 'f2309_Guardar');
$xajax->register(XAJAX_FUNCTION, 'f2309_Traer');
$xajax->register(XAJAX_FUNCTION, 'f2309_Eliminar');
$xajax->register(XAJAX_FUNCTION, 'f2309_HtmlTabla');
$xajax->register(XAJAX_FUNCTION, 'f2309_PintarLlaves');
$xajax->register(XAJAX_FUNCTION,'elimina_archivo_cara17idanexo');
$xajax->register(XAJAX_FUNCTION,'f2317_Guardar');
$xajax->register(XAJAX_FUNCTION,'f2317_Traer');
$xajax->register(XAJAX_FUNCTION,'f2317_Eliminar');
$xajax->register(XAJAX_FUNCTION,'f2317_HtmlTabla');
$xajax->register(XAJAX_FUNCTION,'f2317_PintarLlaves');
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
$cara08idbloque=6;
// -- Se inicializan las variables, primero las que controlan la visualización de la página.
if (isset($_REQUEST['iscroll']) == 0) {
	$_REQUEST['iscroll'] = 0;
}
if (isset($_REQUEST['paginaf2308']) == 0) {
	$_REQUEST['paginaf2308'] = 1;
}
if (isset($_REQUEST['lppf2308']) == 0) {
	$_REQUEST['lppf2308'] = 20;
}
if (isset($_REQUEST['boculta2308']) == 0) {
	$_REQUEST['boculta2308'] = 0;
}
if (isset($_REQUEST['paginaf2309']) == 0) {
	$_REQUEST['paginaf2309'] = 1;
}
if (isset($_REQUEST['lppf2309']) == 0) {
	$_REQUEST['lppf2309'] = 20;
}
if (isset($_REQUEST['boculta2309']) == 0) {
	$_REQUEST['boculta2309'] = 0;
}
if (isset($_REQUEST['paginaf2317']) == 0) {
	$_REQUEST['paginaf2317'] = 1;
}
if (isset($_REQUEST['lppf2317']) == 0) {
	$_REQUEST['lppf2317'] = 20;
}
if (isset($_REQUEST['boculta2317']) == 0) {
	$_REQUEST['boculta2317'] = 0;
}
// -- Inicializar variables de datos.
if (isset($_REQUEST['cara08idbloque']) == 0) {
	$_REQUEST['cara08idbloque'] = $cara08idbloque;
}
if (isset($_REQUEST['cara08consec']) == 0) {
	$_REQUEST['cara08consec'] = '';
}
if (isset($_REQUEST['cara08consec_nuevo']) == 0) {
	$_REQUEST['cara08consec_nuevo'] = '';
}
if (isset($_REQUEST['cara08id']) == 0) {
	$_REQUEST['cara08id'] = '';
}
if (isset($_REQUEST['cara08activa']) == 0) {
	$_REQUEST['cara08activa'] = 'S';
}
if (isset($_REQUEST['cara08idgrupo']) == 0) {
	$_REQUEST['cara08idgrupo'] = '';
}
if (isset($_REQUEST['cara08titulo']) == 0) {
	$_REQUEST['cara08titulo'] = '';
}
if (isset($_REQUEST['cara08cuerpo']) == 0) {
	$_REQUEST['cara08cuerpo'] = '';
}
if (isset($_REQUEST['cara08tipopreg']) == 0) {
	$_REQUEST['cara08tipopreg'] = '';
}
if (isset($_REQUEST['cara08usosiniciales']) == 0) {
	$_REQUEST['cara08usosiniciales'] = '';
}
if (isset($_REQUEST['cara08usostotales']) == 0) {
	$_REQUEST['cara08usostotales'] = 0;
}
if (isset($_REQUEST['cara08nivelpregunta']) == 0) {
	$_REQUEST['cara08nivelpregunta'] = '';
}
if (isset($_REQUEST['cara08retroalimenta']) == 0) {
	$_REQUEST['cara08retroalimenta'] = '';
}
$_REQUEST['cara08idbloque'] = numeros_validar($_REQUEST['cara08idbloque']);
$_REQUEST['cara08consec'] = numeros_validar($_REQUEST['cara08consec']);
$_REQUEST['cara08id'] = numeros_validar($_REQUEST['cara08id']);
$_REQUEST['cara08activa'] = cadena_Validar($_REQUEST['cara08activa']);
$_REQUEST['cara08idgrupo'] = numeros_validar($_REQUEST['cara08idgrupo']);
$_REQUEST['cara08titulo'] = cadena_Validar($_REQUEST['cara08titulo']);
$_REQUEST['cara08cuerpo'] = cadena_Validar($_REQUEST['cara08cuerpo']);
$_REQUEST['cara08tipopreg'] = numeros_validar($_REQUEST['cara08tipopreg']);
$_REQUEST['cara08usosiniciales'] = numeros_validar($_REQUEST['cara08usosiniciales']);
$_REQUEST['cara08usostotales'] = numeros_validar($_REQUEST['cara08usostotales']);
$_REQUEST['cara08nivelpregunta'] = numeros_validar($_REQUEST['cara08nivelpregunta']);
$_REQUEST['cara08retroalimenta'] = cadena_Validar($_REQUEST['cara08retroalimenta']);
if ((int)$_REQUEST['paso'] > 0) {
	//Respuestas
	if (isset($_REQUEST['cara09idpregunta']) == 0) {
		$_REQUEST['cara09idpregunta'] = '';
	}
	if (isset($_REQUEST['cara09consec']) == 0) {
		$_REQUEST['cara09consec'] = '';
	}
	if (isset($_REQUEST['cara09id']) == 0) {
		$_REQUEST['cara09id'] = '';
	}
	if (isset($_REQUEST['cara09valor']) == 0) {
		$_REQUEST['cara09valor'] = '';
	}
	if (isset($_REQUEST['cara09contenido']) == 0) {
		$_REQUEST['cara09contenido'] = '';
	}
	$_REQUEST['cara09idpregunta'] = numeros_validar($_REQUEST['cara09idpregunta']);
	$_REQUEST['cara09consec'] = numeros_validar($_REQUEST['cara09consec']);
	$_REQUEST['cara09id'] = numeros_validar($_REQUEST['cara09id']);
	$_REQUEST['cara09valor'] = numeros_validar($_REQUEST['cara09valor']);
	$_REQUEST['cara09contenido'] = cadena_Validar($_REQUEST['cara09contenido']);
	//Anexos
	if (isset($_REQUEST['cara17consec']) == 0) {
	    $_REQUEST['cara17consec'] = '';
	}
	if (isset($_REQUEST['cara17id']) == 0) {
	    $_REQUEST['cara17id'] = '';
	}
	if (isset($_REQUEST['cara17idorigen']) == 0) {
	    $_REQUEST['cara17idorigen'] = 0;
	}
	if (isset($_REQUEST['cara17idanexo']) == 0) {
	    $_REQUEST['cara17idanexo'] = 0;
	}
	if (isset($_REQUEST['cara17nombre']) == 0) {
	    $_REQUEST['cara17nombre'] = '';
	}
	$_REQUEST['cara17consec'] = numeros_validar($_REQUEST['cara17consec']);
	$_REQUEST['cara17id'] = numeros_validar($_REQUEST['cara17id']);
	$_REQUEST['cara17idorigen'] = numeros_validar($_REQUEST['cara17idorigen']);
	$_REQUEST['cara17idanexo'] = numeros_validar($_REQUEST['cara17idanexo']);
	$_REQUEST['cara17nombre'] = cadena_Validar($_REQUEST['cara17nombre']);	
}
// Espacio para inicializar otras variables
if (isset($_REQUEST['csv_separa']) == 0) {
	$_REQUEST['csv_separa'] = ';';
}
if (isset($_REQUEST['bnombre']) == 0) {
	$_REQUEST['bnombre'] = '';
}
if (isset($_REQUEST['bpregunta']) == 0) {
	$_REQUEST['bpregunta'] = '';
}
/*
if (isset($_REQUEST['blistar']) == 0) {
	$_REQUEST['blistar'] = '';
}
*/
if ((int)$_REQUEST['paso'] > 0) {
	//Respuestas
	if (isset($_REQUEST['bnombre2309']) == 0) {
		$_REQUEST['bnombre2309'] = '';
	}
	/*
	if (isset($_REQUEST['blistar2309']) == 0) {
		$_REQUEST['blistar2309'] = '';
	}
	*/
}
//Si Modifica o Elimina Cargar los campos
if (($_REQUEST['paso'] == 1) || ($_REQUEST['paso'] == 3)) {
	if ($_REQUEST['paso'] == 1) {
		$sSQLcondi = 'cara08idbloque=' . $cara08idbloque . ' AND cara08consec=' . $_REQUEST['cara08consec'] . '';
	} else {
		$sSQLcondi = 'cara08id=' . $_REQUEST['cara08id'] . '';
	}
	$sSQL = 'SELECT * FROM cara08pregunta WHERE ' . $sSQLcondi;
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$_REQUEST['cara08idbloque'] = $fila['cara08idbloque'];
		$_REQUEST['cara08consec'] = $fila['cara08consec'];
		$_REQUEST['cara08id'] = $fila['cara08id'];
		$_REQUEST['cara08activa'] = $fila['cara08activa'];
		$_REQUEST['cara08idgrupo'] = $fila['cara08idgrupo'];
		$_REQUEST['cara08titulo'] = $fila['cara08titulo'];
		$_REQUEST['cara08cuerpo'] = $fila['cara08cuerpo'];
		$_REQUEST['cara08tipopreg'] = $fila['cara08tipopreg'];
		$_REQUEST['cara08usosiniciales'] = $fila['cara08usosiniciales'];
		$_REQUEST['cara08usostotales'] = $fila['cara08usostotales'];
		$_REQUEST['cara08nivelpregunta'] = $fila['cara08nivelpregunta'];
		$_REQUEST['cara08retroalimenta'] = $fila['cara08retroalimenta'];
		$bcargo = true;
		$_REQUEST['paso'] = 2;
		$_REQUEST['boculta2308'] = 0;
		$bLimpiaHijos = true;
		if ($_REQUEST['cara08idbloque'] != $cara08idbloque) {
			$_REQUEST['paso'] = -1;
			$sError = 'No es posible cargar preguntas que no pertenezcan al bloque ' . $cara08idbloque . '';
		}
	} else {
		$_REQUEST['paso'] = 0;
	}
}
//Insertar o modificar un elemento
if (($_REQUEST['paso'] == 10) || ($_REQUEST['paso'] == 12)) {
	$bMueveScroll = true;
	list($_REQUEST, $sError, $iTipoError, $sDebugGuardar) = f2308_db_GuardarV2($_REQUEST, $objDB, $bDebug, $idTercero);
	$sDebug = $sDebug . $sDebugGuardar;
	if ($sError == '') {
		$sError = '<b>' . $ETI['msg_itemguardado'] . '</b>';
		$iTipoError = 1;
	}
}
// Cambio de consecutivo.
if ($_REQUEST['paso'] == 93) {
	$_REQUEST['paso'] = 2;
	$_REQUEST['cara08consec_nuevo'] = numeros_validar($_REQUEST['cara08consec_nuevo']);
	if ($_REQUEST['cara08consec_nuevo'] == '') {
		$sError = $ERR['cara08consec'];
	}
	if ($sError == '') {
		list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 8, $idTercero, $objDB);
		if (!$bDevuelve) {
			$sError = $ERR['8'] . ' [Mod ' . $iCodModulo . ']';
		}
	}
	if ($sError == '') {
		//Ver que el consecutivo no exista.
		$sSQL = 'SELECT cara08id FROM cara08pregunta WHERE cara08consec=' . $_REQUEST['cara08consec_nuevo'] . ' AND cara08idbloque=' . $_REQUEST['cara08idbloque'] . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$sError = 'El consecutivo ' . $_REQUEST['cara08consec_nuevo'] . ' ya existe';
		}
	}
	if ($sError == '') {
		//Aplicar el cambio.
		$sSQL = 'UPDATE cara08pregunta SET cara08consec=' . $_REQUEST['cara08consec_nuevo'] . ' WHERE cara08id=' . $_REQUEST['cara08id'] . '';
		$tabla = $objDB->ejecutasql($sSQL);
		$sDetalle = 'Cambia el consecutivo de ' . $_REQUEST['cara08consec'] . ' a ' . $_REQUEST['cara08consec_nuevo'] . '';
		$_REQUEST['cara08consec'] = $_REQUEST['cara08consec_nuevo'];
		$_REQUEST['cara08consec_nuevo'] = '';
		seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 8, $_REQUEST['cara08id'], $sDetalle, $objDB);
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
		list($sError, $iTipoError, $sDebugElimina) = f2308_db_Eliminar($_REQUEST['cara08id'], $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugElimina;
	}
	if ($sError == '') {
		$_REQUEST['paso'] = -1;
		$sError = $ETI['msg_itemeliminado'];
		$iTipoError = 1;
	}
}
//limpiar la pantalla
if ($_REQUEST['paso']==-1){
	$_REQUEST['cara08idbloque'] = $cara08idbloque;
	$_REQUEST['cara08consec'] = '';
	$_REQUEST['cara08consec_nuevo'] = '';
	$_REQUEST['cara08id'] = '';
	$_REQUEST['cara08activa'] = 'S';
	$_REQUEST['cara08idgrupo'] = '';
	$_REQUEST['cara08titulo'] = '';
	$_REQUEST['cara08cuerpo'] = '';
	$_REQUEST['cara08tipopreg'] = '';
	$_REQUEST['cara08usosiniciales'] = '';
	$_REQUEST['cara08usostotales'] = 0;
	$_REQUEST['cara08nivelpregunta'] = 0;
	$_REQUEST['cara08retroalimenta'] = '';
	$_REQUEST['paso'] = 0;
}
if ($bLimpiaHijos) {
	$_REQUEST['cara09idpregunta'] = '';
	$_REQUEST['cara09consec'] = '';
	$_REQUEST['cara09id'] = '';
	$_REQUEST['cara09valor'] = '';
	$_REQUEST['cara09contenido'] = '';
	$_REQUEST['cara17idpregunta'] = '';
	$_REQUEST['cara17consec'] = '';
	$_REQUEST['cara17id'] = '';
	$_REQUEST['cara17idorigen'] = 0;
	$_REQUEST['cara17idanexo'] = 0;
	$_REQUEST['cara17nombre'] = '';
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
$bEdita2309 = false;
//Permisos adicionales
$seg_5 = 0;
$seg_6 = 0;
$seg_8 = 0;
list($bHayImprimir, $sDebugP, $seg_6) = seg_revisa_permisoV3($iCodModulo, 6, $idTercero, $objDB);
if ((int)$_REQUEST['paso'] != 0) {
	list($bHayImprimir2, $sDebugP, $seg_5) = seg_revisa_permisoV3($iCodModulo, 5, $idTercero, $objDB);
	$bEdita2309 = true;
	$bConEliminar = true;
	list($bDevuelve, $sDebugP, $seg_8) = seg_revisa_permisoV3($iCodModulo, 8, $idTercero, $objDB);
}
//DATOS PARA COMPLETAR EL FORMULARIO
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
$objCombos->nuevo('cara08activa', $_REQUEST['cara08activa'], false);
$objCombos->sino($ETI['si'], $ETI['no']); //, $sValorSi='S', $sValorNo='N'
$html_cara08activa = $objCombos->html('', $objDB);
$objCombos->nuevo('cara08idgrupo', $_REQUEST['cara08idgrupo'], true, '{' . $ETI['msg_ninguno'] . '}', 0);
$sSQL = 'SELECT cara06id AS id, cara06nombre AS nombre FROM cara06grupopreg WHERE cara06idgrupo=' . $cara08idbloque . ' ORDER BY cara06nombre';
$html_cara08idgrupo = $objCombos->html($sSQL, $objDB);
$objCombos->nuevo('cara08tipopreg', $_REQUEST['cara08tipopreg'], false, '{' . $ETI['msg_seleccione'] . '}');
$objCombos->addItem(0, 'Seleccion M&uacute;ltiple, &Uacute;nica Respuesta');
//$objCombos->addArreglo($acara08tipopreg, $icara08tipopreg);
$sSQL = '';
$html_cara08tipopreg = $objCombos->html($sSQL, $objDB);
if ($_REQUEST['cara08usostotales'] == 0) {
	$objCombos->nuevo('cara08nivelpregunta', $_REQUEST['cara08nivelpregunta'], false, '{' . $ETI['msg_seleccione'] . '}');
	$objCombos->addItem(0, $ETI['msg_basica']);
	$objCombos->addItem(1, $ETI['msg_profundizacion']);
	//$objCombos->addArreglo($acara08nivelpregunta, $icara08nivelpregunta);
	$html_cara08nivelpregunta = $objCombos->html('', $objDB);
} else {
	$et_cara08nivelpregunta = $ETI['msg_basica'];
	if ($_REQUEST['cara08nivelpregunta'] == 1) {
		$et_cara08nivelpregunta = $ETI['msg_basica'];
	}
	$html_cara08nivelpregunta = html_oculto('cara08nivelpregunta', $_REQUEST['cara08nivelpregunta'], $et_cara08nivelpregunta);
}
if ((int)$_REQUEST['paso'] == 0) {
} else {
}
//Alistar datos adicionales
$id_rpt = 0;
//$id_rpt=reportes_id(_Identificador_Tipo_Reporte_, $objDB);
/*
$objCombos->nuevo('blistar', $_REQUEST['blistar'], true, '{' . $ETI['msg_todos'] . '}');
$objCombos->sAccion = 'paginarf2308()';
$sSQL = '';
$html_blistar = $objCombos->html($sSQL, $objDB);
//$html_blistar = $objCombos->comboSistema(2308, 1, $objDB, 'paginarf2308()');
$objCombos->nuevo('blistar2309', $_REQUEST['blistar2309'], true, '{' . $ETI['msg_todos'] . '}');
$html_blistar2309 = $objCombos->comboSistema(2309, 1, $objDB, 'paginarf2309()');
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
//Cargar las tablas de datos
$aParametros[0] = ''; //$_REQUEST['p1_2308'];
$aParametros[100] = $idTercero;
$aParametros[101] = $_REQUEST['paginaf2308'];
$aParametros[102] = $_REQUEST['lppf2308'];
$aParametros[103] = $cara08idbloque;
$aParametros[104] = $_REQUEST['bnombre'];
$aParametros[105] = $_REQUEST['bpregunta'];
list($sTabla2308, $sDebugTabla) = f2308_TablaDetalleV2($aParametros, $objDB, $bDebug);
$sDebug = $sDebug . $sDebugTabla;
$sTabla2309 = '';
$sTabla2317 = '';
if ($_REQUEST['paso'] != 0) {
	//Respuestas
	$aParametros2309[0] = $_REQUEST['cara08id'];
	$aParametros2309[100] = $idTercero;
	$aParametros2309[101] = $_REQUEST['paginaf2309'];
	$aParametros2309[102] = $_REQUEST['lppf2309'];
	//$aParametros2309[103] = $_REQUEST['bnombre2309'];
	//$aParametros2309[104] = $_REQUEST['blistar2309'];
	list($sTabla2309, $sDebugTabla) = f2309_TablaDetalleV2($aParametros2309, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugTabla;
	//Anexos
	$aParametros2317[0] = $_REQUEST['cara08id'];
	$aParametros2317[101] = $_REQUEST['paginaf2317'];
	$aParametros2317[102] = $_REQUEST['lppf2317'];
	//$aParametros2317[103] = $_REQUEST['bnombre2317'];
	//$aParametros2317[104] = $_REQUEST['blistar2317'];
	list($sTabla2317, $sDebugTabla) = f2317_TablaDetalleV2($aParametros2317, $objDB, $bDebug);
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
			window.document.frmlista.consulta.value = window.document.frmedita.consulta_2308.value;
			window.document.frmlista.titulos.value = window.document.frmedita.titulos_2308.value;
			window.document.frmlista.nombrearchivo.value = 'Preguntas Competencias digitales';
			window.document.frmlista.submit();
		} else {
			ModalMensaje("<?php echo $ERR['6']; ?>");
		}
	}

	function asignarvariables() {
		//window.document.frmimpp.v3.value = window.document.frmedita.bnombre.value;
		//window.document.frmimpp.v4.value = window.document.frmedita.bcodigo.value;
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
			window.document.frmimpp.action = 'e2308_ss.php';
			window.document.frmimpp.submit();
		} else {
			ModalMensaje(sError);
		}
	}

	function imprimep() {
		if (window.document.frmedita.seg_5.value == 1) {
			asignarvariables();
			window.document.frmimpp.action = 'p2308.php';
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
		datos[1] = window.document.frmedita.cara08idbloque.value;
		datos[2] = window.document.frmedita.cara08consec.value;
		if ((datos[1] != '') && (datos[2] != '')) {
			xajax_f2308_ExisteDato(datos);
		}
	}

	function cargadato(llave1, llave2) {
		window.document.frmedita.cara08idbloque.value = String(llave1);
		window.document.frmedita.cara08consec.value = String(llave2);
		window.document.frmedita.paso.value = 1;
		window.document.frmedita.submit();
	}

	function cargaridf2308(llave1) {
		window.document.frmedita.cara08id.value = String(llave1);
		window.document.frmedita.paso.value = 3;
		window.document.frmedita.submit();
	}

	function paginarf2308() {
		let params = new Array();
		params[99] = window.document.frmedita.debug.value;
		params[100] = <?php echo $idTercero; ?>;
		params[101] = window.document.frmedita.paginaf2308.value;
		params[102] = window.document.frmedita.lppf2308.value;
		params[103] = window.document.frmedita.cara08idbloque.value;
		params[104] = window.document.frmedita.bnombre.value;
		params[105] = window.document.frmedita.bpregunta.value;
		document.getElementById('div_f2308detalle').innerHTML = '<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf2308" name="paginaf2308" type="hidden" value="' + params[101] + '" /><input id="lppf2308" name="lppf2308" type="hidden" value="' + params[102] + '" />';
		xajax_f2308_HtmlTabla(params);
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
		document.getElementById("cara08consec").focus();
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
	function cierraDiv96(ref){
		let sRetorna=window.document.frmedita.div96v2.value;
		if (ref==2317){
			if (sRetorna!=''){
				window.document.frmedita.cara17idorigen.value=window.document.frmedita.div96v1.value;
				window.document.frmedita.cara17idanexo.value=sRetorna;
				verboton('beliminacara17idanexo','block');
			}
			archivo_lnk(window.document.frmedita.cara17idorigen.value, window.document.frmedita.cara17idanexo.value, 'div_cara17idanexo');
			paginarf2317();
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

	function verpregunta(id){
		window.document.frmvisor.id.value=id;
		window.document.frmvisor.submit();
	}
</script>
<?php
if ($_REQUEST['paso'] != 0) {
?>
<script language="javascript" src="jsi/js2309.js"></script>
<script language="javascript" src="jsi/js2317.js"></script>
<?php
}
?>
<?php
if ($_REQUEST['paso']!=0){
?>
<form id="frmimpp" name="frmimpp" method="post" action="p2308.php" target="_blank" style="display:none">
<input id="r" name="r" type="hidden" value="2308" />
<input id="id2308" name="id2308" type="hidden" value="<?php echo $_REQUEST['cara08id']; ?>" />
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
<form id="frmvisor" name="frmvisor" method="post" action="verpregunta.php" target="_blank" style="display:none">
<input id="id" name="id" type="hidden" value="" />
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
//Div para ocultar
$bConExpande = true;
if ($bConExpande) {
?>
<div class="ir_derecha"<?php echo $sAnchoExpandeContrae; ?>>
<?php
echo $objForma->htmlExpande(2308, $_REQUEST['boculta2308'], $ETI['bt_mostrar'], $ETI['bt_ocultar']);
$sEstiloDiv = '';
if ($_REQUEST['boculta2308'] != 0) {
	$sEstiloDiv = ' style="display:none;"';
}
?>
</div>
<div id="div_p2308"<?php echo $sEstiloDiv; ?>>
<?php
}
//Mostrar formulario para editar
?>
<input id="cara08idbloque" name="cara08idbloque" type="hidden" value="<?php echo $cara08idbloque; ?>"/>
<label class="Label90">
<?php
echo $ETI['cara08consec'];
?>
</label>
<label class="Label90">
<?php
if ($_REQUEST['paso'] != 2) {
?>
<input id="cara08consec" name="cara08consec" type="text" value="<?php echo $_REQUEST['cara08consec']; ?>" onchange="RevisaLlave()" class="cuatro" />
<?php
} else {
	echo html_oculto('cara08consec', $_REQUEST['cara08consec'], formato_numero($_REQUEST['cara08consec']));
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
<label class="Label60">
<?php
echo $ETI['cara08id'];
?>
</label>
<label class="Label60">
<?php
	echo html_oculto('cara08id', $_REQUEST['cara08id'], formato_numero($_REQUEST['cara08id']));
?>
</label>
<label class="Label60">
<?php
echo $ETI['cara08activa'];
?>
</label>
<label class="Label60">
<?php
echo $html_cara08activa;
?>
</label>
<label class="Label60">
<?php
echo $ETI['cara08nivelpregunta'];
?>
</label>
<label class="Label160">
<?php
echo $html_cara08nivelpregunta;
?>
</label>
<label class="Label90">
<?php
echo $ETI['cara08idgrupo'];
?>
</label>
<label>
<?php
echo $html_cara08idgrupo;
?>
</label>
<label class="L">
<?php
echo $ETI['cara08titulo'];
?>

<input id="cara08titulo" name="cara08titulo" type="text" value="<?php echo $_REQUEST['cara08titulo']; ?>" maxlength="100" class="L" placeholder="<?php echo $ETI['ing_campo'].$ETI['cara08titulo']; ?>"/>
</label>
<label class="txtAreaM">
<?php
echo $ETI['cara08cuerpo'];
?>
<textarea id="cara08cuerpo" name="cara08cuerpo" placeholder="<?php echo $ETI['ing_campo'].$ETI['cara08cuerpo']; ?>"><?php echo $_REQUEST['cara08cuerpo']; ?></textarea>
</label>
<label class="Label160">
<?php
echo $ETI['cara08tipopreg'];
?>
</label>
<label>
<?php
echo $html_cara08tipopreg;
?>
</label>
<label class="Label130">
<?php
echo $ETI['cara08usosiniciales'];
?>
</label>
<label class="Label130">
<input id="cara08usosiniciales" name="cara08usosiniciales" type="text" value="<?php echo $_REQUEST['cara08usosiniciales']; ?>" class="cuatro" maxlength="10" placeholder="0"/>
</label>
<label class="Label130">
<?php
echo $ETI['cara08usostotales'];
?>
</label>
<label class="Label130">
<?php
echo html_oculto('cara08usostotales', $_REQUEST['cara08usostotales']);
?>
</label>
<?php
if ($_REQUEST['paso']==2){
	echo $objForma->htmlBotonSolo('cmdVisualizar', 'lnkresalte', 'verpregunta('.$_REQUEST['cara08id'].')', $ETI['lnk_visor'], 130);
}
?>
<div class="salto5px"></div>
<label class="txtAreaS">
<?php
echo $ETI['cara08retroalimenta'];
?>
<textarea id="cara08retroalimenta" name="cara08retroalimenta" placeholder="<?php echo $ETI['ing_campoa'] . $ETI['cara08retroalimenta']; ?>"><?php echo $_REQUEST['cara08retroalimenta']; ?></textarea>
</label>
<?php
// -- Inicia Grupo campos 2309 Respuestas
?>
<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo $ETI['titulo_2309'];
?>
</label>
<input id="boculta2309" name="boculta2309" type="hidden" value="<?php echo $_REQUEST['boculta2309']; ?>" />
<?php
if ($_REQUEST['paso'] == 2) {
	if ($bEdita2309) {
?>
<div class="ir_derecha"<?php echo $sAnchoExpandeContrae; ?>>
<?php
if (false) {
?>
<label class="Label30">
<input id="btexcel2309" name="btexcel2309" type="button" value="Exportar" class="btMiniExcel" onclick="imprime2309();" title="Exportar" />
</label>
<?php
}
echo $objForma->htmlExpande(2309, $_REQUEST['boculta2309'], $ETI['bt_mostrar'], $ETI['bt_ocultar']);
$sEstiloDiv = '';
if ($_REQUEST['boculta2309'] != 0) {
	$sEstiloDiv = ' style="display:none;"';
}
?>
</div>
<div class="salto1px"></div>
<div id="div_p2309"<?php echo $sEstiloDiv; ?>>
<label class="Label90">
<?php
echo $ETI['cara09consec'];
?>
</label>
<label class="Label90">
<div id="div_cara09consec">
<?php
if ((int)$_REQUEST['cara09id'] == 0) {
?>
<input id="cara09consec" name="cara09consec" type="text" value="<?php echo $_REQUEST['cara09consec']; ?>" onchange="revisaf2309()" class="cuatro" />
<?php
} else {
	echo html_oculto('cara09consec', $_REQUEST['cara09consec'], formato_numero($_REQUEST['cara09consec']));
}
?>
</div>
</label>
<label class="Label60">
<?php
echo $ETI['cara09id'];
?>
</label>
<label class="Label60">
<div id="div_cara09id">
<?php
	echo html_oculto('cara09id', $_REQUEST['cara09id'], formato_numero($_REQUEST['cara09id']));
?>
</div>
</label>
<label class="Label130">
<?php
echo $ETI['cara09valor'];
?>
</label>
<label class="Label130">
<input id="cara09valor" name="cara09valor" type="text" value="<?php echo $_REQUEST['cara09valor']; ?>" class="cuatro" maxlength="2" placeholder="0 - 10"/>
</label>
<label class="txtAreaS">
<?php
echo $ETI['cara09contenido'];
?>
<textarea id="cara09contenido" name="cara09contenido" placeholder="<?php echo $ETI['ing_campo'] . $ETI['cara09contenido']; ?>"><?php echo $_REQUEST['cara09contenido']; ?></textarea>
</label>
<div class="salto1px"></div>
<label class="Label130">&nbsp;</label>
<?php
$sEstiloElimina = 'display:none;';
if ((int)$_REQUEST['cara09id'] != 0) {
	$sEstiloElimina = 'inline-block;';
}
echo $objForma->htmlBotonSolo('bguarda2309', 'btMiniGuardar', 'guardaf2309()', $ETI['bt_mini_guardar_2309'], 30);
echo $objForma->htmlBotonSolo('blimpia2309', 'btMiniLimpiar', 'limpiaf2309()', $ETI['bt_mini_limpiar_2309'], 30);
echo $objForma->htmlBotonSolo('belimina2309', 'btMiniEliminar', 'eliminaf2309()', $ETI['bt_mini_eliminar_2309'], 30, $sEstiloElimina);
//Este es el cierre del div_p2309
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
<div class="salto1px"></div>
<?php
}
?>
<div id="div_f2309detalle">
<?php
echo $sTabla2309;
?>
</div>
<?php
	}
?>
<div class="salto1px"></div>
</div>
<?php
// -- Termina Grupo campos 2309 Respuestas
?>
<?php
// -- Inicia Grupo campos 2317 Anexos
?>
<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo $ETI['titulo_2317'];
?>
</label>
<input id="boculta2317" name="boculta2317" type="hidden" value="<?php echo $_REQUEST['boculta2317']; ?>" />
<?php
if ($_REQUEST['paso']==2){
	//if ($bCondicion){
?>
<div class="ir_derecha" style="width:62px;">
<!--
<label class="Label30">
<input id="btexcel2317" name="btexcel2317" type="button" value="Exportar" class="btMiniExcel" onclick="imprime2317();" title="Exportar"/>
</label>
-->
<label class="Label30">
<input id="btexpande2317" name="btexpande2317" type="button" value="Expandir" class="btMiniExpandir" onclick="expandepanel(2317,'block',0);" title="<?php echo $ETI['bt_mostrar']; ?>" style="display:<?php if ($_REQUEST['boculta2317']==0){echo 'none'; }else{echo 'block';} ?>;"/>
</label>
<label class="Label30">
<input id="btrecoge2317" name="btrecoge2317" type="button" value="Recoger" class="btMiniRecoger" onclick="expandepanel(2317,'none',1);" title="<?php echo $ETI['bt_ocultar']; ?>" style="display:<?php if ($_REQUEST['boculta2317']==0){echo 'block'; }else{echo 'none';} ?>;"/>
</label>
</div>
<div class="salto1px"></div>
<div id="div_p2317" style="display:<?php if ($_REQUEST['boculta2317']==0){echo 'block'; }else{echo 'none';} ?>;">
<label class="Label90">
<?php
echo $ETI['cara17consec'];
?>
</label>
<label class="Label90"><div id="div_cara17consec">
<?php
if ((int)$_REQUEST['cara17id']==0){
?>
<input id="cara17consec" name="cara17consec" type="text" value="<?php echo $_REQUEST['cara17consec']; ?>" onchange="revisaf2317()" class="cuatro"/>
<?php
	}else{
	echo html_oculto('cara17consec', $_REQUEST['cara17consec']);
	}
?>
</div></label>
<label class="Label60">
<?php
echo $ETI['cara17id'];
?>
</label>
<label class="Label60">
<div id="div_cara17id">
<?php
	echo html_oculto('cara17id', $_REQUEST['cara17id']);
?>
</div>
</label>
<input id="cara17idorigen" name="cara17idorigen" type="hidden" value="<?php echo $_REQUEST['cara17idorigen']; ?>"/>
<input id="cara17idanexo" name="cara17idanexo" type="hidden" value="<?php echo $_REQUEST['cara17idanexo']; ?>"/>
<div class="GrupoCampos300">
<div class="salto1px"></div>
<div id="div_cara17idanexo" class="Campo220">
<?php
echo html_lnkarchivo((int)$_REQUEST['cara17idorigen'], (int)$_REQUEST['cara17idanexo']);
?>
</div>
<?php
$sEstiloAnexa = 'style="display:none;"';
$sEstiloElimina = 'style="display:none;"';
if ((int)$_REQUEST['cara17id'] != 0) {
	$sEstiloAnexa = 'style="display:block;"';
}
if ((int)$_REQUEST['cara17idanexo'] != 0) {
	$sEstiloElimina = 'style="display:block;"';
}
echo $objForma->htmlBotonSolo('banexacara17idanexo', 'btAnexarS', 'carga_cara17idanexo()', 'Cargar archivo', 30, $sEstiloAnexa, 'Anexar');
echo $objForma->htmlBotonSolo('beliminacara17idanexo', 'btBorrarS', 'eliminacara17idanexo()', 'Eliminar archivo', 30, $sEstiloElimina, 'Eliminar');
?>
<div class="salto1px"></div>
</div>
<label class="L">
<?php
echo $ETI['cara17nombre'];
?>

<input id="cara17nombre" name="cara17nombre" type="text" value="<?php echo $_REQUEST['cara17nombre']; ?>" maxlength="100" class="L" placeholder="<?php echo $ETI['ing_campo'].$ETI['cara17nombre']; ?>"/>
</label>
<div class="salto1px"></div>
<label class="Label130">&nbsp;</label>
<label class="Label30">
<input id="bguarda2317" name="bguarda2317" type="button" value="Guardar" class="btMiniGuardar" onclick="guardaf2317()" title="<?php echo $ETI['bt_mini_guardar_2317']; ?>"/>
</label>
<label class="Label30">
<input id="blimpia2317" name="blimpia2317" type="button" value="Limpiar" class="btMiniLimpiar" onclick="limpiaf2317()" title="<?php echo $ETI['bt_mini_limpiar_2317']; ?>"/>
</label>
<label class="Label30">
<input id="belimina2317" name="belimina2317" type="button" value="Eliminar" class="btMiniEliminar" onclick="eliminaf2317()" title="<?php echo $ETI['bt_mini_eliminar_2317']; ?>" style="display:<?php if ((int)$_REQUEST['cara17id']!=0){echo 'block';}else{echo 'none';} ?>;"/>
</label>
<?php
//Este es el cierre del div_p2317
?>
<div class="salto1px"></div>
</div>
<?php
		//} //Termina el segundo bloque  condicional - bloque editar.
?>
<div class="salto1px"></div>
<div id="div_f2317detalle">
<?php
echo $sTabla2317;
?>
</div>
<?php
	}
?>
<div class="salto1px"></div>
</div>
<?php
// -- Termina Grupo campos 2317 Anexos
?>
<?php
if (false) {
	//Ejemplo de boton de ayuda
	//echo html_BotonAyuda('NombreCampo');
	//echo html_DivAyudaLocal('NombreCampo');
}
if ($bConExpande) {
	//Este es el cierre del div_p2308
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
<input id="bnombre" name="bnombre" type="text" value="<?php echo $_REQUEST['bnombre']; ?>" onchange="paginarf2308()" autocomplete="off" />
</label>
<label class="Label90">
Pregunta
</label>
<label>
<input id="bpregunta" name="bpregunta" type="text" value="<?php echo $_REQUEST['bpregunta']; ?>" onchange="paginarf2308()" autocomplete="off"/>
</label>
<?php
if (false){
?>
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
<?php
}
?>
</div>
<div class="salto1px"></div>
<?php
echo ' ' . $csv_separa;
?>
<div id="div_f2308detalle">
<?php
echo $sTabla2308;
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
echo $ETI['msg_cara08consec'];
?>
</label>
<label class="Label90">
<?php
echo '<b>' . $_REQUEST['cara08consec'] . '</b>';
?>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['msg_cara08consec_nuevo'];
// onchange="RevisaConsec()"
?>
</label>
<label class="Label90">
<input id="cara08consec_nuevo" name="cara08consec_nuevo" type="text" value="<?php echo $_REQUEST['cara08consec_nuevo']; ?>" class="cuatro" />
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
<input id="titulo_2308" name="titulo_2308" type="hidden" value="<?php echo $sTituloModulo; ?>" />
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
		$("#cara08idgrupo").chosen({width:"100%"});
	});
</script>
<?php
}
?>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>unad_todas2024v2.js"></script>
<?php
forma_piedepagina();

