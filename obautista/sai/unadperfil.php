<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2014 - 2018 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Version 0.4.0 viernes, 14 de febrero de 2014
--- Modelo Version 0.7.0 viernes, 14 de marzo de 2014
--- Modelo Versión 2.7.10 miércoles, 10 de junio de 2015
--- Modelo Versión 2.9.7 lunes, 23 de noviembre de 2015
--- Modelo Versión 2.22.3 miércoles, 15 de agosto de 2018
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
$iConsecutivoMenu = 1;
$iMinVerDB = 7117;
$iCodModulo = 105;
$iCodModuloConsulta = $iCodModulo;
switch ($APP->idsistema) {
	case 17:
		$iCodModuloConsulta = 1791;
		break;
	case 23:
		$iCodModuloConsulta = 2391;
		break;
}
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
$mensajes_105 = $APP->rutacomun . 'lg/lg_105_' . $_SESSION['unad_idioma'] . '.php';
if (!file_exists($mensajes_105)) {
	$mensajes_105 = $APP->rutacomun . 'lg/lg_105_es.php';
}
require $mensajes_todas;
require $mensajes_105;
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
$sTituloModulo = $ETI['titulo_105'];
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
	// list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModuloConsulta, 1, $_SESSION['unad_id_tercero'], $objDB);
	$bDevuelve = true;
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
			require $APP->rutacomun . 'unad_forma2023.php';
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
		header('Location:noticia.php?ret=unadperfil.php');
		die();
	}
}
$seg_1707 = 0;
$bDevuelve = false;
list($bDevuelve, $sDebugP) = seg_revisa_permisoV3(1700, 1707, $_SESSION['unad_id_tercero'], $objDB, $bDebug);
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
$mensajes_106 = $APP->rutacomun . 'lg/lg_106_' . $_SESSION['unad_idioma'] . '.php';
if (!file_exists($mensajes_106)) {
	$mensajes_106 = $APP->rutacomun . 'lg/lg_106_es.php';
}
require $mensajes_106;
// -- Si esta cargando la pagina por primer vez se revisa si requiere auditar y se manda a hacer un limpiar (paso -1)
if (isset($_REQUEST['paso']) == 0) {
	$_REQUEST['paso'] = -1;
	if ($audita[1]) {
		seg_auditaingreso($iCodModulo, $_SESSION['unad_id_tercero'], $objDB);
	}
}
// -- 105 unad05perfiles
require $APP->rutacomun . 'lib105.php';
// -- 106 Permisos por perfil
require $APP->rutacomun . 'lib106.php';
$xajax = new xajax();
$xajax->configure('javascript URI', $APP->rutacomun . 'xajax/');
$xajax->register(XAJAX_FUNCTION, 'sesion_abandona_V2');
$xajax->register(XAJAX_FUNCTION, 'sesion_mantenerV4');
$xajax->register(XAJAX_FUNCTION, 'f105_HtmlTabla');
$xajax->register(XAJAX_FUNCTION, 'f105_ExisteDato');
$xajax->register(XAJAX_FUNCTION, 'f106_Guardar');
$xajax->register(XAJAX_FUNCTION, 'f106_Traer');
$xajax->register(XAJAX_FUNCTION, 'f106_Eliminar');
$xajax->register(XAJAX_FUNCTION, 'f106_HtmlTabla');
$xajax->register(XAJAX_FUNCTION, 'f106_PintarLlaves');
$xajax->register(XAJAX_FUNCTION, 'pintar_combo_unad06idmodulo');
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
if (isset($_REQUEST['paginaf105']) == 0) {
	$_REQUEST['paginaf105'] = 1;
}
if (isset($_REQUEST['lppf105']) == 0) {
	$_REQUEST['lppf105'] = 20;
}
if (isset($_REQUEST['boculta105']) == 0) {
	$_REQUEST['boculta105'] = 0;
}
if (isset($_REQUEST['paginaf106']) == 0) {
	$_REQUEST['paginaf106'] = 1;
}
if (isset($_REQUEST['lppf106']) == 0) {
	$_REQUEST['lppf106'] = 20;
}
if (isset($_REQUEST['boculta106']) == 0) {
	$_REQUEST['boculta106'] = 0;
}
// -- Inicializar variables de datos.
if (isset($_REQUEST['unad05id']) == 0) {
	$_REQUEST['unad05id'] = '';
}
if (isset($_REQUEST['unad05nombre']) == 0) {
	$_REQUEST['unad05nombre'] = '';
}
if (isset($_REQUEST['unad05aplicativo']) == 0) {
	$_REQUEST['unad05aplicativo'] = 0;
}
if (isset($_REQUEST['unad05reservado']) == 0) {
	$_REQUEST['unad05reservado'] = 'N';
}
if (isset($_REQUEST['unad05delegable']) == 0) {
	$_REQUEST['unad05delegable'] = 'N';
}
if ((int)$_REQUEST['paso'] > 0) {
	//Permisos por perfil
	if (isset($_REQUEST['bsistema']) == 0) {
		$_REQUEST['bsistema'] = '';
	}
	if ($APP->idsistema != 1) {
		$_REQUEST['bsistema'] = $APP->idsistema;
	}
	if (isset($_REQUEST['bmodulo']) == 0) {
		$_REQUEST['bmodulo'] = '';
	}
	if (isset($_REQUEST['bpermiso']) == 0) {
		$_REQUEST['bpermiso'] = '';
	}
	if (isset($_REQUEST['bdato_106']) == 0) {
		$_REQUEST['bdato_106'] = 0;
	}
	if (isset($_REQUEST['unad06idmodulo']) == 0) {
		$_REQUEST['unad06idmodulo'] = '';
	}
	if (isset($_REQUEST['unad06idpermiso']) == 0) {
		$_REQUEST['unad06idpermiso'] = '';
	}
	if (isset($_REQUEST['unad06vigente']) == 0) {
		$_REQUEST['unad06vigente'] = 'S';
	}
}
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
//Si Modifica o Elimina Cargar los campos
if (($_REQUEST['paso'] == 1) || ($_REQUEST['paso'] == 3)) {
	$sSQL = 'SELECT * FROM unad05perfiles WHERE unad05id=' . $_REQUEST['unad05id'] . '';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$_REQUEST['unad05id'] = $fila['unad05id'];
		$_REQUEST['unad05nombre'] = $fila['unad05nombre'];
		$_REQUEST['unad05aplicativo'] = $fila['unad05aplicativo'];
		$_REQUEST['unad05reservado'] = $fila['unad05reservado'];
		$_REQUEST['unad05delegable'] = $fila['unad05delegable'];
		$bcargo = true;
		$_REQUEST['paso'] = 2;
		$_REQUEST['boculta105'] = 0;
		$bLimpiaHijos = true;
		if ($APP->idsistema != 1) {
			if ($fila['unad05id'] == 1) {
				$sError = 'El perfil 1 ya existe y no esta disponible en este modulo.';
				$_REQUEST['paso'] = -1;
				$bcargo = false;
			}
		}
	} else {
		$_REQUEST['paso'] = 0;
	}
}
//Insertar o modificar un elemento
if (($_REQUEST['paso'] == 10) || ($_REQUEST['paso'] == 12)) {
	$bMueveScroll = true;
	list($_REQUEST, $sError, $iTipoError, $sDebugGuardar) = f105_db_GuardarV2($_REQUEST, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugGuardar;
	if ($sError == '') {
		$sError = '<b>' . $ETI['msg_itemguardado'] . '</b>';
		$iTipoError = 1;
	}
}
//Eliminar un elemento
if ($_REQUEST['paso'] == 13) {
	$_REQUEST['paso'] = 2;
	list($sError, $iTipoError, $sDebugElimina) = f105_db_Eliminar($_REQUEST, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugElimina;
	if ($sError == '') {
		$_REQUEST['paso'] = -1;
		$sError = $ETI['msg_itemeliminado'];
		$iTipoError = 1;
	}
}
//limpiar la pantalla
if ($_REQUEST['paso'] == -1) {
	$_REQUEST['unad05id'] = '';
	$_REQUEST['unad05nombre'] = '';
	$_REQUEST['unad05aplicativo'] = 0;
	$_REQUEST['unad05reservado'] = 'N';
	$_REQUEST['unad05delegable'] = 'N';
	$_REQUEST['paso'] = 0;
}
//AQUI SE DEBEN CARGAR TODOS LOS DATOS QUE LA FORMA NECESITE.
$bPuedeGuardar = true;
$bConEliminar = false;
$bHayImprimir = false;
$bHayImprimir2 = false;
$sScriptImprime = 'imprimelista()';
// $sScriptImprime2 = 'imprimeexcel()';
$sClaseImprime = 'btEnviarExcel';
// $sClaseImprime2 = 'btEnviarPdf';
switch ($iPiel) {
	case 2:
		$sClaseImprime = 'iExcel';
		// $sClaseImprime2 = 'iPdf';
		break;
}
//Permisos adicionales
$seg_5 = 0;
$seg_6 = 0;
$seg_8 = 0;
list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 5, $idTercero, $objDB);
if ($bDevuelve) {
	$seg_5 = 1;
}
/*
list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 6, $idTercero, $objDB);
if ($bDevuelve) {
	$seg_6 = 1;
}
*/
if ($seg_6 == 1) {
	$bHayImprimir = true;
}
if ((int)$_REQUEST['paso'] != 0) {
	$bDevuelve = false;
	list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 5, $idTercero, $objDB);
	if ($bDevuelve) {
		$seg_5 = 1;
	}
	$bConEliminar = true;
	/*
	if ($seg_5 == 1) {
		$bHayImprimir = true;
	}
	*/
}
//DATOS PARA COMPLETAR EL FORMULARIO
$iAgnoIni = 2000;
$iAgno = fecha_agno();
$iAgnoFin = $iAgno + 5;
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
if ($APP->idsistema != 1) {
	$html_unad05aplicativo = '<input id="unad05aplicativo" name="unad05aplicativo" type="hidden" value="' . $_REQUEST['unad05aplicativo'] . '" />';
	$et_unad05reservado = $ETI['no'];
	if ($_REQUEST['unad05reservado'] == 'S') {
		$et_unad05reservado = $ETI['si'];
	}
	$html_unad05reservado = html_oculto('unad05reservado', $_REQUEST['unad05reservado'], $et_unad05reservado);
} else {
	$html_unad05aplicativo = html_combo('unad05aplicativo', 'unad01id', 'unad01nombre', 'unad01sistema', '', 'unad01nombre', $_REQUEST['unad05aplicativo'], $objDB, '', true, '{' . $ETI['msg_ninguno'] . '}|{' . $ETI['msg_todos'] . '}', '0|-1');
	$objCombos->nuevo('unad05reservado', $_REQUEST['unad05reservado'], false);
	$objCombos->sino($ETI['si'], $ETI['no']); //, $sValorSi='S', $sValorNo='N'
	$html_unad05reservado = $objCombos->html('', $objDB);
}
$objCombos->nuevo('unad05delegable', $_REQUEST['unad05delegable'], false);
$objCombos->sino($ETI['si'], $ETI['no']); //, $sValorSi='S', $sValorNo='N'
$html_unad05delegable = $objCombos->html('', $objDB);
if ((int)$_REQUEST['paso'] > 0) {
	if ($APP->idsistema == 1) {
		$html_bsistema = html_combo('bsistema', 'unad01id', 'unad01nombre', 'unad01sistema', 'unad01instalado="S"', 'unad01nombre', $_REQUEST['bsistema'], $objDB, 'CambiaSistema()', true, '{' . $ETI['msg_todos'] . '}', '');
	} else {
		$html_bsistema = '<input id="bsistema" name="bsistema" type="hidden" value="' . $_REQUEST['bsistema'] . '" />';
	}
	$html_bmodulo = html_combo_unad06idmodulo($objDB, $_REQUEST['bsistema'], $_REQUEST['bmodulo']);
	$html_bpermiso = html_combo_unad06idpermiso($objDB, $_REQUEST['bpermiso']);
}
//Alistar datos adicionales
$id_rpt = 0;
//$id_rpt=reportes_id(_Identificador_Tipo_Reporte_, $objDB);
/*
$objCombos->nuevo('blistar', $_REQUEST['blistar'], true, '{' . $ETI['msg_todos'] . '}');
$objCombos->sAccion = 'paginarf195()';
$sSQL = '';
$html_blistar = $objCombos->html($sSQL, $objDB);
//$html_blistar = $objCombos->comboSistema(195, 1, $objDB, 'paginarf195()');
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
$iModeloReporte = 105;
$html_iFormatoImprime = '<input id="iformatoimprime" name="iformatoimprime" type="hidden" value="0" />
';
//Cargar las tablas de datos
$aParametros[101] = $_REQUEST['paginaf105'];
$aParametros[102] = $_REQUEST['lppf105'];
$aParametros[103] = $_REQUEST['bnombre'];
list($sTabla105, $sDebugTabla) = f105_TablaDetalleV2($aParametros, $objDB, $bDebug);
$sDebug = $sDebug . $sDebugTabla;
$sTabla106 = '';
if ($_REQUEST['paso'] != 0) {
	//Permisos por perfil
	$aParametros106[0] = $_REQUEST['unad05id'];
	$aParametros106[91] = $_REQUEST['bsistema'];
	$aParametros106[92] = $_REQUEST['bmodulo'];
	$aParametros106[93] = $_REQUEST['bpermiso'];
	$aParametros106[101] = $_REQUEST['paginaf106'];
	$aParametros106[102] = $_REQUEST['lppf106'];
	list($sTabla106, $sDebugTabla) = f106_TablaDetalleV2($aParametros106, $objDB, $bDebug);
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
		require $APP->rutacomun . 'unad_forma2023.php';
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
		document.getElementById('botones_sector1').style.display = 'flex';
		document.getElementById('botones_sector97').style.display = 'none';
		switch (codigo) {
			case 1:
				break;
			case 97:
				document.getElementById('botones_sector1').style.display = 'none';
				document.getElementById('botones_sector' + codigo).style.display = 'flex';
				break;
			default:
				document.getElementById('botones_sector1').style.display = 'none';
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
			window.document.frmlista.consulta.value = window.document.frmedita.consulta_105.value;
			window.document.frmlista.titulos.value = window.document.frmedita.titulos_105.value;
			window.document.frmlista.submit();
		} else {
			ModalMensaje("<?php echo $ERR['6']; ?>");
		}
	}

	function imprimep() {
		window.document.frmimpp.submit();
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
		datos[1] = window.document.frmedita.unad05id.value;
		if ((datos[1] != '')) {
			xajax_f105_ExisteDato(datos);
		}
	}

	function cargadato(llave1) {
		window.document.frmedita.unad05id.value = String(llave1);
		window.document.frmedita.paso.value = 1;
		window.document.frmedita.submit();
	}

	function paginarf105() {
		let params = new Array();
		params[99] = window.document.frmedita.debug.value;
		params[101] = window.document.frmedita.paginaf105.value;
		params[102] = window.document.frmedita.lppf105.value;
		params[103] = window.document.frmedita.bnombre.value;
		//document.getElementById('div_f105detalle').innerHTML = '<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf105" name="paginaf105" type="hidden" value="' + params[101] + '" /><input id="lppf105" name="lppf105" type="hidden" value="' + params[102] + '" />';
		xajax_f105_HtmlTabla(params);
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
		document.getElementById("unad05id").focus();
	}

	function paginarf106() {
		let params = new Array();
		params[0] = window.document.frmedita.unad05id.value;
		params[91] = window.document.frmedita.bsistema.value;
		params[92] = window.document.frmedita.bmodulo.value;
		params[93] = window.document.frmedita.bpermiso.value;
		params[101] = window.document.frmedita.paginaf106.value;
		params[102] = window.document.frmedita.lppf106.value;
		xajax_f106_HtmlTabla(params);
	}

	function mantener_sesion() {
		xajax_sesion_mantenerV4();
	}
	setInterval('xajax_sesion_abandona_V2();', 60000);

	function AyudaLocal(sCampo) {
		let divAyuda = document.getElementById('div_ayuda_' + sCampo);
		if (typeof divAyuda === 'undefined') {} else {
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

	function CambiaSistema() {
		let params = new Array();
		params[91] = window.document.frmedita.bsistema.value;
		xajax_pintar_combo_unad06idmodulo(params);
		paginarf106();
	}

	function quitapermiso(idmodulo, idpermiso) {
		let params = new Array();
		params[0] = window.document.frmedita.unad05id.value;
		params[1] = window.document.frmedita.unad05id.value;
		params[2] = idmodulo;
		params[3] = idpermiso;
		params[91] = window.document.frmedita.bsistema.value;
		params[92] = window.document.frmedita.bmodulo.value;
		params[93] = window.document.frmedita.bpermiso.value;
		params[101] = window.document.frmedita.paginaf106.value;
		params[102] = window.document.frmedita.lppf106.value;
		xajax_f106_Eliminar(params);
	}

	function anexapermiso(idmodulo, idpermiso) {
		var valores = new Array();
		let params = new Array();
		valores[1] = window.document.frmedita.unad05id.value;
		valores[2] = idmodulo;
		valores[3] = idpermiso;
		valores[4] = 'S';
		valores[100] = 0;
		params[0] = window.document.frmedita.unad05id.value;
		params[91] = window.document.frmedita.bsistema.value;
		params[92] = window.document.frmedita.bmodulo.value;
		params[93] = window.document.frmedita.bpermiso.value;
		params[101] = window.document.frmedita.paginaf106.value;
		params[102] = window.document.frmedita.lppf106.value;
		xajax_f106_Guardar(valores, params);
	}

	function revisaf106() {
		paginarf106();
	}
</script>
<form id="frmimpp" name="frmimpp" method="post" action="<?php echo $APP->rutacomun; ?>p105.php" target="_blank" style="display:none">
<input id="r" name="r" type="hidden" value="105" />
<input id="id105" name="id105" type="hidden" value="<?php echo $_REQUEST['unad05id']; ?>" />
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
<input id="seg_5" name="seg_5" type="hidden" value="<?php echo $seg_5; ?>" />
<input id="seg_6" name="seg_6" type="hidden" value="<?php echo $seg_6; ?>" />
<div id="div_sector1">
<?php
if ($bBloqueTitulo) {
	$objForma->addBoton('cmdAyuda', 'btSupAyuda', 'muestraayuda(' . $APP->idsistema . ', ' . $iCodModulo . ');', $ETI['bt_ayuda']);
	if ($bConEliminar) {
		$objForma->addBoton('cmdEliminar', 'btUpEliminar', 'eliminadato();', $ETI['bt_eliminar']);
	}
	if ($bHayImprimir) {
		$objForma->addBoton('cmdImprimir', $sClaseImprime, $sScriptImprime, $ETI['bt_imprimir']);
	}
	$objForma->addBoton('cmdLimpiar', 'btUpLimpiar', 'limpiapagina();', $ETI['bt_limpiar']);
	if ($bPuedeGuardar) {
		$objForma->addBoton('cmdGuardar', 'btUpGuardar', 'enviaguardar();', $ETI['bt_guardar']);
	}
	if (false) {
		$objForma->addBoton('cmdAnular', 'btSupAnular', 'expandesector(2);', $ETI['bt_anular']);
	}
	echo $objForma->htmlTitulo($sTituloModulo, $iCodModulo);
}
echo $objForma->htmlInicioMarco();
//Termina el bloque titulo
?>
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
echo $objForma->htmlExpande(105, $_REQUEST['boculta105'], $ETI['bt_mostrar'], $ETI['bt_ocultar']);
$sEstiloDiv = '';
if ($_REQUEST['boculta105'] != 0) {
	$sEstiloDiv = ' style="display:none;"';
}
?>
</div>
<div id="div_p105"<?php echo $sEstiloDiv; ?>>
<?php
}
//Mostrar formulario para editar
 ?>
<label class="Label90">
<?php
echo $ETI['unad05id'];
?>
</label>
<label class="Label130">
<?php
if ($_REQUEST['paso']!=2){
?>
<input id="unad05id" name="unad05id" type="text" value="<?php echo $_REQUEST['unad05id']; ?>" onchange="RevisaLlave()" class="diez" maxlength="10" placeholder="<?php echo $ETI['ing_vr']; ?>"/>
<?php
	} else {
	echo html_oculto('unad05id', $_REQUEST['unad05id']);
	}
?>
</label>
<label class="Label90">
<?php
echo $ETI['unad05reservado'];
?>
</label>
<label class="Label60">
<?php
echo $html_unad05reservado;
?>
</label>
<label class="Label90">
<?php
echo $ETI['unad05delegable'];
?>
</label>
<label class="Label60">
<?php
echo $html_unad05delegable;
?>
</label>
<?php
if ($APP->idsistema!=1){
	echo $html_unad05aplicativo;
	} else {
?>
<label class="Label90">
<?php
echo $ETI['unad05aplicativo'];
?>
</label>
<label>
<?php
echo $html_unad05aplicativo;
?>
</label>
<?php
	}
?>
<label class="L">
<?php
echo $ETI['unad05nombre'];
?>
<div class="salto1px"></div>
<input id="unad05nombre" name="unad05nombre" type="text" value="<?php echo $_REQUEST['unad05nombre']; ?>" maxlength="100" class="L" placeholder="<?php echo $ETI['ing_campo'].$ETI['unad05nombre']; ?>"/>
</label>
<?php
// -- Inicia Grupo campos 106 Permisos por perfil
?>
<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo $ETI['titulo_106'];
?>
</label>
<input id="boculta106" name="boculta106" type="hidden" value="<?php echo $_REQUEST['boculta106']; ?>" />
<?php
if ($_REQUEST['paso']==2){
	//if ($bCondicion){
?>
<div class="ir_derecha"<?php echo $sAnchoExpandeContrae; ?>>
<?php
echo $objForma->htmlExpande(106, $_REQUEST['boculta106'], $ETI['bt_mostrar'], $ETI['bt_ocultar']);
$sEstiloDiv = '';
if ($_REQUEST['boculta106'] != 0) {
	$sEstiloDiv = ' style="display:none;"';
}
?>
</div>
<div id="div_p106"<?php echo $sEstiloDiv; ?>>
<div class="salto1px"></div>
<?php
if ($APP->idsistema==1){
?>
<label class="Label60">
<?php
echo 'Sistema';
?>
</label>
<label class="Label220">
<?php 
echo $html_bsistema;
?>
</label>
<?php
	} else {
	echo $html_bsistema;
	}
?>
<label class="Label60">
<?php
echo $ETI['unad06idmodulo'];
?>
</label>
<label class="Label350"><div id="div_bmodulo">
<?php
echo $html_bmodulo;
?>
</div></label>
<label class="Label90">
<?php
echo $ETI['unad06idpermiso'];
?>
</label>
<label class="Label200">
<?php
echo $html_bpermiso;
?>
</label>
<div class="salto1px"></div>
</div>
<?php
		//} //Termina el segundo bloque  condicional - bloque editar.
?>
<div class="salto1px"></div>
<div id="div_f106detalle">
<?php
echo $sTabla106;
?>
</div>
<?php
	}
//fin de si el paso es 2
?>
<div class="salto1px"></div>
</div>
<?php
// -- Termina Grupo campos 106 Permisos por perfil
?>
<?php
if (false) {
	//Ejemplo de boton de ayuda
	//echo html_BotonAyuda('NombreCampo');
	//echo html_DivAyudaLocal('NombreCampo');
}
if ($bConExpande) {
	//Este es el cierre del div_p105
?>
<div class="salto1px"></div>
</div>
<?php
}
//Mostrar el contenido de la tabla
// CIERRA EL DIV areatrabajo
echo $objForma->htmlFinMarco();
?>
<?php
echo $objForma->htmlInicioMarco($ETI['bloque1']);
?>
<div class="ir_derecha">
<label class="Label90">
<?php
echo $ETI['msg_bnombre'];
?>
</label>
<label>
<input id="bnombre" name="bnombre" type="text" value="<?php echo $_REQUEST['bnombre']; ?>" onchange="paginarf105()" autocomplete="off"/>
</label>
</div>
<div class="salto1px"></div>
<?php
echo ' ' . $csv_separa;
?>
<div class="salto1px"></div>
<div id="div_f105detalle">
<?php
echo $sTabla105;
?>
</div>
<?php
// CIERRA EL DIV areatrabajo
?>
<?php
echo $objForma->htmlFinMarco();
?>
</div><!-- /DIV_Sector1 -->


<div id="div_sector2" style="display:none">
<?php
$objForma = new clsHtmlForma($iPiel);
if ($bBloqueTitulo) {
	$objForma->addBoton('cmdAyuda2', 'btSupAyuda', 'muestraayuda(' . $iCodModulo . ');', $ETI['bt_ayuda']);
	$objForma->addBoton('cmdVolverSec2', 'btSupVolver', 'retornacontrol();', $ETI['bt_volver']);
	echo $objForma->htmlTitulo($sTituloModulo, $iCodModulo);
}
echo $objForma->htmlInicioMarco();
echo $objForma->htmlFinMarco();
?>
</div><!-- /DIV_Sector2 -->


<div id="div_sector95" style="display:none">
<?php
echo $objForma->htmlInicioMarco();
echo '<div id="div_95cuerpo"></div>';
echo $objForma->htmlFinMarco();
?>
</div><!-- /DIV_Sector95 -->


<div id="div_sector96" style="display:none">
<input id="div96v1" name="div96v1" type="hidden" value="" />
<input id="div96v2" name="div96v2" type="hidden" value="" />
<input id="div96v3" name="div96v3" type="hidden" value="" />
<input id="div96campo" name="div96campo" type="hidden" value="" />
<input id="div96llave" name="div96llave" type="hidden" value="" />
<input id="titulo_105" name="titulo_105" type="hidden" value="<?php echo $sTituloModulo; ?>" />
<?php
$objForma=new clsHtmlForma($iPiel);
if ($bBloqueTitulo) {
	$objForma->addBoton('cmdAyuda96', 'btSupAyuda', 'muestraayuda(' . $iCodModulo . ');', $ETI['bt_ayuda']);
	$objForma->addBoton('cmdVolverSec96', 'btSupVolver', 'retornacontrol();', $ETI['bt_volver']);
	echo $objForma->htmlTitulo($sTituloModulo, $iCodModulo, 'div_96titulo');
}
echo $objForma->htmlInicioMarco();
echo '<div id="div_96cuerpo"></div>';
echo $objForma->htmlFinMarco();
?>
</div><!-- /DIV_Sector96 -->


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
$objForma = new clsHtmlForma($iPiel);
if ($bBloqueTitulo) {
	$objForma->addBoton('cmdAyuda98', 'btSupAyuda', 'muestraayuda(' . $iCodModulo . ');', $ETI['bt_ayuda']);
	echo $objForma->htmlTitulo($sTituloModulo, $iCodModulo);
}
echo $objForma->htmlInicioMarco();
echo $objForma->htmlEspere($ETI['msg_espere']);
echo $objForma->htmlFinMarco();
?>
</div><!-- /DIV_Sector98 -->


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
<?php
echo $objForma->htmlBotonSolo('cmdGuardarf', 'btSoloGuardar', 'enviaguardar()', $ETI['bt_guardar']);
?>
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
<script language="javascript" src="<?php echo $APP->rutacomun; ?>unad_todas2024.js?ver=2"></script>
<?php
forma_piedepagina();