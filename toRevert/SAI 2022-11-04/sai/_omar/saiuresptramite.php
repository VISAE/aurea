<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2022 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.28.4 lunes, 19 de septiembre de 2022
*/
/** Archivo saiuresptramite.php.
 * Modulo 3070 saiu70responsabletrami.
 * @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
 * @param debug = 1  (Opcional), bandera para indicar si se generan datos de depuración
 * @date lunes, 19 de septiembre de 2022
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
//require $APP->rutacomun . 'libdatos.php';
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
$iCodModulo = 3070;
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
$mensajes_3000 = 'lg/lg_3000_' . $_SESSION['unad_idioma'] . '.php';
if (!file_exists($mensajes_3000)) {
	$mensajes_3000 = 'lg/lg_3000_es.php';
}
require $mensajes_3000;
*/
$mensajes_3070 = 'lg/lg_3070_' . $_SESSION['unad_idioma'] . '.php';
if (!file_exists($mensajes_3070)) {
	$mensajes_3070 = 'lg/lg_3070_es.php';
}
require $mensajes_todas;
require $mensajes_3070;
$xajax = NULL;
$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
if ($APP->dbpuerto != '') {
	$objDB->dbPuerto = $APP->dbpuerto;
}
$iPiel = iDefinirPiel($APP, 1);
$sAnchoExpandeContrae = ' style="width:62px;"';
$sOcultaId = ''; //' style="display:none;"';
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
	forma_cabeceraV3($xajax, $ETI['titulo_3070']);
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
		header('Location:noticia.php?ret=saiuresptramite.php');
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
// -- 3070 saiu70responsabletrami
require 'lib3070.php';
$xajax = new xajax();
$xajax->configure('javascript URI', $APP->rutacomun . 'xajax/');
$xajax->register(XAJAX_FUNCTION, 'f3070_Combosaiu70numpaso');
$xajax->register(XAJAX_FUNCTION, 'f3070_Combosaiu70idcentro');
$xajax->register(XAJAX_FUNCTION, 'f3070_Combosaiu70idprograma');
$xajax->register(XAJAX_FUNCTION, 'f3070_Combosaiu70idgrupotrabajo');
$xajax->register(XAJAX_FUNCTION, 'f3070_Combosaiu70idresponsable');
$xajax->register(XAJAX_FUNCTION, 'sesion_abandona_V2');
$xajax->register(XAJAX_FUNCTION, 'sesion_mantenerV4');
$xajax->register(XAJAX_FUNCTION, 'f3070_HtmlTabla');
$xajax->register(XAJAX_FUNCTION, 'f3070_ExisteDato');
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
if (isset($_REQUEST['paginaf3070']) == 0) {
	$_REQUEST['paginaf3070'] = 1;
}
if (isset($_REQUEST['lppf3070']) == 0) {
	$_REQUEST['lppf3070'] = 20;
}
if (isset($_REQUEST['boculta3070']) == 0) {
	$_REQUEST['boculta3070'] = 0;
}
// -- Inicializar variables de datos.
if (isset($_REQUEST['saiu70idtipotramite']) == 0) {
	$_REQUEST['saiu70idtipotramite'] = '';
}
if (isset($_REQUEST['saiu70numpaso']) == 0) {
	$_REQUEST['saiu70numpaso'] = '';
}
if (isset($_REQUEST['saiu70idzona']) == 0) {
	$_REQUEST['saiu70idzona'] = '';
}
if (isset($_REQUEST['saiu70idcentro']) == 0) {
	$_REQUEST['saiu70idcentro'] = '';
}
if (isset($_REQUEST['saiu70idescuela']) == 0) {
	$_REQUEST['saiu70idescuela'] = '';
}
if (isset($_REQUEST['saiu70idprograma']) == 0) {
	$_REQUEST['saiu70idprograma'] = '';
}
if (isset($_REQUEST['saiu70id']) == 0) {
	$_REQUEST['saiu70id'] = '';
}
if (isset($_REQUEST['saiu70activo']) == 0) {
	$_REQUEST['saiu70activo'] = 'S';
}
if (isset($_REQUEST['saiu70idunidad']) == 0) {
	$_REQUEST['saiu70idunidad'] = '';
}
if (isset($_REQUEST['saiu70idgrupotrabajo']) == 0) {
	$_REQUEST['saiu70idgrupotrabajo'] = '';
}
if (isset($_REQUEST['saiu70idresponsable']) == 0) {
	$_REQUEST['saiu70idresponsable'] = '';
}
$_REQUEST['saiu70idtipotramite'] = numeros_validar($_REQUEST['saiu70idtipotramite']);
$_REQUEST['saiu70numpaso'] = numeros_validar($_REQUEST['saiu70numpaso']);
$_REQUEST['saiu70idzona'] = numeros_validar($_REQUEST['saiu70idzona']);
$_REQUEST['saiu70idcentro'] = numeros_validar($_REQUEST['saiu70idcentro']);
$_REQUEST['saiu70idescuela'] = numeros_validar($_REQUEST['saiu70idescuela']);
$_REQUEST['saiu70idprograma'] = numeros_validar($_REQUEST['saiu70idprograma']);
$_REQUEST['saiu70id'] = numeros_validar($_REQUEST['saiu70id']);
$_REQUEST['saiu70activo'] = numeros_validar($_REQUEST['saiu70activo']);
$_REQUEST['saiu70idunidad'] = numeros_validar($_REQUEST['saiu70idunidad']);
$_REQUEST['saiu70idgrupotrabajo'] = numeros_validar($_REQUEST['saiu70idgrupotrabajo']);
$_REQUEST['saiu70idresponsable'] = numeros_validar($_REQUEST['saiu70idresponsable']);
// Espacio para inicializar otras variables
if (isset($_REQUEST['csv_separa']) == 0) {
	$_REQUEST['csv_separa'] = ';';
}
if (isset($_REQUEST['btipotramite']) == 0) {
	$_REQUEST['btipotramite'] = '';
}
if (isset($_REQUEST['bnumpaso']) == 0) {
	$_REQUEST['bnumpaso'] = '';
}
//Si Modifica o Elimina Cargar los campos
if (($_REQUEST['paso'] == 1) || ($_REQUEST['paso'] == 3)) {
	if ($_REQUEST['paso'] == 1) {
		$sSQLcondi = 'saiu70idtipotramite=' . $_REQUEST['saiu70idtipotramite'] . ' AND saiu70numpaso=' . $_REQUEST['saiu70numpaso'] . ' AND saiu70idzona=' . $_REQUEST['saiu70idzona'] . ' AND saiu70idcentro=' . $_REQUEST['saiu70idcentro'] . ' AND saiu70idescuela=' . $_REQUEST['saiu70idescuela'] . ' AND saiu70idprograma=' . $_REQUEST['saiu70idprograma'] . '';
	} else {
		$sSQLcondi = 'saiu70id=' . $_REQUEST['saiu70id'] . '';
	}
	$sSQL = 'SELECT * FROM saiu70responsabletrami WHERE ' . $sSQLcondi;
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$_REQUEST['saiu70idtipotramite'] = $fila['saiu70idtipotramite'];
		$_REQUEST['saiu70numpaso'] = $fila['saiu70numpaso'];
		$_REQUEST['saiu70idzona'] = $fila['saiu70idzona'];
		$_REQUEST['saiu70idcentro'] = $fila['saiu70idcentro'];
		$_REQUEST['saiu70idescuela'] = $fila['saiu70idescuela'];
		$_REQUEST['saiu70idprograma'] = $fila['saiu70idprograma'];
		$_REQUEST['saiu70id'] = $fila['saiu70id'];
		$_REQUEST['saiu70activo'] = $fila['saiu70activo'];
		$_REQUEST['saiu70idunidad'] = $fila['saiu70idunidad'];
		$_REQUEST['saiu70idgrupotrabajo'] = $fila['saiu70idgrupotrabajo'];
		$_REQUEST['saiu70idresponsable'] = $fila['saiu70idresponsable'];
		$bcargo = true;
		$_REQUEST['paso'] = 2;
		$_REQUEST['boculta3070'] = 0;
		$bLimpiaHijos = true;
	} else {
		$_REQUEST['paso'] = 0;
	}
}
//Insertar o modificar un elemento
if (($_REQUEST['paso'] == 10) || ($_REQUEST['paso'] == 12)) {
	$bMueveScroll = true;
	list($_REQUEST, $sError, $iTipoError, $sDebugGuardar) = f3070_db_GuardarV2($_REQUEST, $objDB, $bDebug, $idTercero);
	$sDebug = $sDebug . $sDebugGuardar;
	if ($sError == '') {
		$sError = '<b>' . $ETI['msg_itemguardado'] . '</b>';
		$iTipoError = 1;
	}
}
//Eliminar un elemento
if ($_REQUEST['paso'] == 13) {
	$_REQUEST['paso'] = 2;
	list($sError, $iTipoError, $sDebugElimina) = f3070_db_Eliminar($_REQUEST['saiu70id'], $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugElimina;
	if ($sError == '') {
		$_REQUEST['paso'] = -1;
		$sError = $ETI['msg_itemeliminado'];
		$iTipoError = 1;
	}
}
//limpiar la pantalla
if ($_REQUEST['paso'] == -1) {
	$_REQUEST['saiu70idtipotramite'] = '';
	$_REQUEST['saiu70numpaso'] = 0;
	$_REQUEST['saiu70idzona'] = '';
	$_REQUEST['saiu70idcentro'] = '';
	$_REQUEST['saiu70idescuela'] = '';
	$_REQUEST['saiu70idprograma'] = '';
	$_REQUEST['saiu70id'] = '';
	$_REQUEST['saiu70activo'] = 1;
	$_REQUEST['saiu70idunidad'] = '';
	$_REQUEST['saiu70idgrupotrabajo'] = '';
	$_REQUEST['saiu70idresponsable'] = '';
	$_REQUEST['paso'] = 0;
}
if ($bLimpiaHijos) {
}
//AQUI SE DEBEN CARGAR TODOS LOS DATOS QUE LA FORMA NECESITE.
//DATOS PARA COMPLETAR EL FORMULARIO
$sNombreUsuario = '';
if ($seg_1707 == 1){
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
/*
list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 6, $idTercero, $objDB);
if ($bDevuelve) {
	$seg_6 = 1;
}
*/
//Crear los controles que requieran llamado a base de datos
$objCombos = new clsHtmlCombos();
$objCombos->nuevo('saiu70activo', $_REQUEST['saiu70activo'], true, $ETI['no'], 0);
$objCombos->addItem(1, $ETI['si']);
//$objCombos->addArreglo($asaiu70activo, $isaiu70activo);
$html_saiu70activo = $objCombos->html('', $objDB);
$objCombos->nuevo('saiu70idunidad', $_REQUEST['saiu70idunidad'], true, '{' . $ETI['msg_seleccione'] . '}');
$objCombos->sAccion = 'carga_combo_saiu70idgrupotrabajo();';
$sSQL = 'SELECT unae26id AS id, CONCAT(unae26prefijo, "", unae26nombre) AS nombre FROM unae26unidadesfun WHERE unae26idzona=0 AND unae26id>0 ORDER BY unae26lugar, unae26nombre';
$html_saiu70idunidad = $objCombos->html($sSQL, $objDB);
$html_saiu70idgrupotrabajo = f3070_HTMLComboV2_saiu70idgrupotrabajo($objDB, $objCombos, $_REQUEST['saiu70idgrupotrabajo'], $_REQUEST['saiu70idunidad']);
$html_saiu70idresponsable = f3070_HTMLComboV2_saiu70idresponsable($objDB, $objCombos, $_REQUEST['saiu70idresponsable'], $_REQUEST['saiu70idgrupotrabajo']);
if (true) {
	$html_saiu70idtipotramite = f3070_HTMLComboV2_saiu70idtipotramite($objDB, $objCombos, $_REQUEST['saiu70idtipotramite']);
	$html_saiu70numpaso = f3070_HTMLComboV2_saiu70numpaso($objDB, $objCombos, $_REQUEST['saiu70numpaso'], $_REQUEST['saiu70idtipotramite']);
	$html_saiu70idzona = f3070_HTMLComboV2_saiu70idzona($objDB, $objCombos, $_REQUEST['saiu70idzona']);
	$html_saiu70idcentro = f3070_HTMLComboV2_saiu70idcentro($objDB, $objCombos, $_REQUEST['saiu70idcentro'], $_REQUEST['saiu70idzona']);
	$html_saiu70idescuela = f3070_HTMLComboV2_saiu70idescuela($objDB, $objCombos, $_REQUEST['saiu70idescuela']);
	$html_saiu70idprograma = f3070_HTMLComboV2_saiu70idprograma($objDB, $objCombos, $_REQUEST['saiu70idprograma'], $_REQUEST['saiu70idescuela']);
} else {
	$saiu70idtipotramite_nombre = '&nbsp;';
	if ((int)$_REQUEST['saiu70idtipotramite'] != 0) {
		list($saiu70idtipotramite_nombre, $sErrorDet) = tabla_campoxid('saiu46tipotramite', 'saiu46nombre', 'saiu46id', $_REQUEST['saiu70idtipotramite'], '{' . $ETI['msg_sindato'] . '}', $objDB);
	}
	$html_saiu70idtipotramite = html_oculto('saiu70idtipotramite', $_REQUEST['saiu70idtipotramite'], $saiu70idtipotramite_nombre);
	$saiu70numpaso_nombre = $_REQUEST['saiu70numpaso'];
	//$saiu70numpaso_nombre = $asaiu70numpaso[$_REQUEST['saiu70numpaso']];
	//$saiu70numpaso_nombre = '&nbsp;';
	//if ((int)$_REQUEST['saiu70numpaso'] != 0) {
		//list($saiu70numpaso_nombre, $sErrorDet) = tabla_campoxid('', '', '', $_REQUEST['saiu70numpaso'], '{' . $ETI['msg_sindato'] . '}', $objDB);
	//}
	$html_saiu70numpaso = html_oculto('saiu70numpaso', $_REQUEST['saiu70numpaso'], $saiu70numpaso_nombre);
	$saiu70idzona_nombre = '&nbsp;';
	if ((int)$_REQUEST['saiu70idzona'] != 0) {
		list($saiu70idzona_nombre, $sErrorDet) = tabla_campoxid('unad23zona', 'unad23nombre', 'unad23id', $_REQUEST['saiu70idzona'], '{' . $ETI['msg_todas'] . '}', $objDB);
	}
	$html_saiu70idzona = html_oculto('saiu70idzona', $_REQUEST['saiu70idzona'], $saiu70idzona_nombre);
	$saiu70idcentro_nombre = '&nbsp;';
	if ((int)$_REQUEST['saiu70idcentro'] != 0) {
		list($saiu70idcentro_nombre, $sErrorDet) = tabla_campoxid('unad24sede', 'unad24nombre', 'unad24id', $_REQUEST['saiu70idcentro'], '{' . $ETI['msg_todos'] . '}', $objDB);
	}
	$html_saiu70idcentro = html_oculto('saiu70idcentro', $_REQUEST['saiu70idcentro'], $saiu70idcentro_nombre);
	$saiu70idescuela_nombre = '&nbsp;';
	if ((int)$_REQUEST['saiu70idescuela'] != 0) {
		list($saiu70idescuela_nombre, $sErrorDet) = tabla_campoxid('core12escuela', 'core12nombre', 'core12id', $_REQUEST['saiu70idescuela'], '{' . $ETI['msg_todas'] . '}', $objDB);
	}
	$html_saiu70idescuela = html_oculto('saiu70idescuela', $_REQUEST['saiu70idescuela'], $saiu70idescuela_nombre);
	$saiu70idprograma_nombre = '&nbsp;';
	if ((int)$_REQUEST['saiu70idprograma'] != 0) {
		list($saiu70idprograma_nombre, $sErrorDet) = tabla_campoxid('core09programa', 'core09nombre', 'core09id', $_REQUEST['saiu70idprograma'], '{' . $ETI['msg_todos'] . '}', $objDB);
	}
	$html_saiu70idprograma = html_oculto('saiu70idprograma', $_REQUEST['saiu70idprograma'], $saiu70idprograma_nombre);
}
//Alistar datos adicionales
$id_rpt = 0;
//$id_rpt=reportes_id(_Identificador_Tipo_Reporte_, $objDB);
$objCombos->nuevo('btipotramite', $_REQUEST['btipotramite'], true, '{' . $ETI['msg_todos'] . '}');
$objCombos->sAccion = 'paginarf3070()';
$sSQL = 'SELECT saiu46id AS id, saiu46nombre AS nombre FROM saiu46tipotramite';
$html_btipotramite = $objCombos->html($sSQL, $objDB);
// $html_btipotramite = $objCombos->comboSistema(3070, 1, $objDB, 'paginarf3070()');
$objCombos->nuevo('bnumpaso', $_REQUEST['bnumpaso'], true, '-');
$objCombos->sAccion = 'paginarf3070()';
$iPasos = 3;
for ($k = 1; $k <= $iPasos; $k++) {
	$objCombos->addItem($k, $k);
}
$sSQL = '';
$html_bnumpaso = $objCombos->html($sSQL, $objDB);
// $html_bnumpaso = $objCombos->comboSistema(3070, 1, $objDB, 'paginarf3070()');
if (false) {
	$objCombos->nuevo('csv_separa', $_REQUEST['csv_separa'], false);
	$objCombos->addItem(',', $ETI['msg_coma']);
	$objCombos->addItem(';', $ETI['msg_puntoycoma']);
	$csv_separa = '<label class="Label90">' . $ETI['msg_separador'] . '</label><label class="Label130">' . $objCombos->html('', $objDB) . '</label>';
} else {
	$csv_separa = '<input id="csv_separa" name="csv_separa" type="hidden" value="," />';
}
$iNumFormatosImprime = 0;
$iModeloReporte = 3070;
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
$aParametros[0] = ''; //$_REQUEST['p1_3070'];
$aParametros[100] = $idTercero;
$aParametros[101] = $_REQUEST['paginaf3070'];
$aParametros[102] = $_REQUEST['lppf3070'];
$aParametros[103] = $_REQUEST['btipotramite'];
$aParametros[104] = $_REQUEST['bnumpaso'];
list($sTabla3070, $sDebugTabla) = f3070_TablaDetalleV2($aParametros, $objDB, $bDebug);
$sDebug = $sDebug . $sDebugTabla;
$bDebugMenu = false;
list($et_menu, $sDebugM) = html_menuV2($APP->idsistema, $objDB, $iPiel, $bDebugMenu, $idTercero);
$sDebug = $sDebug . $sDebugM;
$objDB->CerrarConexion();
//FORMA
require $APP->rutacomun . 'unad_forma_v2.php';
forma_cabeceraV3($xajax, $ETI['titulo_3070']);
echo $et_menu;
forma_mitad();
if (false) {
?>
	<link rel="stylesheet" href="../ulib/css/criticalPath.css" type="text/css" />
	<link rel="stylesheet" href="../ulib/css/principal.css" type="text/css" />
	<link rel="stylesheet" href="../ulib/unad_estilos2018.css" type="text/css" />
<?php
}
?>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>js/jquery-3.3.1.min.js"></script>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>js/popper.min.js"></script>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>js/bootstrap.min.js"></script>
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>js/bootstrap.min.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>css/criticalPath.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>css/principal.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>unad_estilos2018.css" type="text/css" />
<?php
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
		document.getElementById('div_sector95').style.display = 'none';
		document.getElementById('div_sector96').style.display = 'none';
		document.getElementById('div_sector98').style.display = 'none';
		document.getElementById('div_sector' + codigo).style.display = 'block';
		let sEst = 'none';
		if (codigo == 1) {
			sEst = 'block';
		}
		document.getElementById('cmdGuardarf').style.display = sEst;
	}

	function imprimelista() {
		if (window.document.frmedita.seg_6.value == 1) {
			window.document.frmlista.consulta.value = window.document.frmedita.consulta_3070.value;
			window.document.frmlista.titulos.value = window.document.frmedita.titulos_3070.value;
			window.document.frmlista.nombrearchivo.value = 'Responsables de tramites';
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
			window.document.frmimpp.action = 'e3070.php';
			window.document.frmimpp.submit();
		} else {
			ModalMensaje(sError);
		}
	}

	function imprimep() {
		if (window.document.frmedita.seg_5.value == 1) {
			asignarvariables();
			window.document.frmimpp.action = 'p3070.php';
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
		ModalConfirm('<?php echo $ETI['msg_confirmaeliminar']; ?>');
		ModalDialogConfirm(function(confirm) {
			if (confirm) {
				ejecuta_eliminadato();
			}
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
		datos[1] = window.document.frmedita.saiu70idtipotramite.value;
		datos[2] = window.document.frmedita.saiu70numpaso.value;
		datos[3] = window.document.frmedita.saiu70idzona.value;
		datos[4] = window.document.frmedita.saiu70idcentro.value;
		datos[5] = window.document.frmedita.saiu70idescuela.value;
		datos[6] = window.document.frmedita.saiu70idprograma.value;
		if ((datos[1] != '') && (datos[2] != '') && (datos[3] != '') && (datos[4] != '') && (datos[5] != '') && (datos[6] != '')) {
			xajax_f3070_ExisteDato(datos);
		}
	}

	function cargadato(llave1, llave2, llave3, llave4, llave5, llave6) {
		window.document.frmedita.saiu70idtipotramite.value = String(llave1);
		window.document.frmedita.saiu70numpaso.value = String(llave2);
		window.document.frmedita.saiu70idzona.value = String(llave3);
		window.document.frmedita.saiu70idcentro.value = String(llave4);
		window.document.frmedita.saiu70idescuela.value = String(llave5);
		window.document.frmedita.saiu70idprograma.value = String(llave6);
		window.document.frmedita.paso.value = 1;
		window.document.frmedita.submit();
	}

	function cargaridf3070(llave1) {
		window.document.frmedita.saiu70id.value = String(llave1);
		window.document.frmedita.paso.value = 3;
		window.document.frmedita.submit();
	}

	function carga_combo_saiu70numpaso() {
		let params = new Array();
		params[0] = window.document.frmedita.saiu70idtipotramite.value;
		document.getElementById('div_saiu70numpaso').innerHTML = '<b>Procesando datos, por favor espere...</b><input id="saiu70numpaso" name="saiu70numpaso" type="hidden" value="" />';
		xajax_f3070_Combosaiu70numpaso(params);
	}

	function carga_combo_saiu70idcentro() {
		let params = new Array();
		params[0] = window.document.frmedita.saiu70idzona.value;
		document.getElementById('div_saiu70idcentro').innerHTML = '<b>Procesando datos, por favor espere...</b><input id="saiu70idcentro" name="saiu70idcentro" type="hidden" value="" />';
		xajax_f3070_Combosaiu70idcentro(params);
	}

	function carga_combo_saiu70idprograma() {
		let params = new Array();
		params[0] = window.document.frmedita.saiu70idescuela.value;
		document.getElementById('div_saiu70idprograma').innerHTML = '<b>Procesando datos, por favor espere...</b><input id="saiu70idprograma" name="saiu70idprograma" type="hidden" value="" />';
		xajax_f3070_Combosaiu70idprograma(params);
	}

	function carga_combo_saiu70idgrupotrabajo() {
		let params = new Array();
		params[0] = window.document.frmedita.saiu70idunidad.value;
		document.getElementById('div_saiu70idgrupotrabajo').innerHTML = '<b>Procesando datos, por favor espere...</b><input id="saiu70idgrupotrabajo" name="saiu70idgrupotrabajo" type="hidden" value="" />';
		xajax_f3070_Combosaiu70idgrupotrabajo(params);
		carga_combo_saiu70idresponsable();
	}

	function carga_combo_saiu70idresponsable() {
		let params = new Array();
		params[0] = window.document.frmedita.saiu70idgrupotrabajo.value;
		document.getElementById('div_saiu70idresponsable').innerHTML = '<b>Procesando datos, por favor espere...</b><input id="saiu70idresponsable" name="saiu70idresponsable" type="hidden" value="" />';
		xajax_f3070_Combosaiu70idresponsable(params);
	}

	function paginarf3070() {
		let params = new Array();
		params[99] = window.document.frmedita.debug.value;
		params[100] = <?php echo $idTercero; ?>;
		params[101] = window.document.frmedita.paginaf3070.value;
		params[102] = window.document.frmedita.lppf3070.value;
		params[103] = window.document.frmedita.btipotramite.value;
		params[104] = window.document.frmedita.bnumpaso.value;
		document.getElementById('div_f3070detalle').innerHTML = '<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf3070" name="paginaf3070" type="hidden" value="' + params[101] + '" /><input id="lppf3070" name="lppf3070" type="hidden" value="' + params[102] + '" />';
		xajax_f3070_HtmlTabla(params);
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
		document.getElementById("saiu70idtipotramite").focus();
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
<?php
if ($_REQUEST['paso'] != 0) {
?>
<form id="frmimpp" name="frmimpp" method="post" action="p3070.php" target="_blank">
<input id="r" name="r" type="hidden" value="3070" />
<input id="id3070" name="id3070" type="hidden" value="<?php echo $_REQUEST['saiu70id']; ?>" />
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
?>
<input id="cmdEliminar" name="cmdEliminar" type="button" class="btUpEliminar" onclick="eliminadato();" title="<?php echo $ETI['bt_eliminar']; ?>" value="<?php echo $ETI['bt_eliminar']; ?>" />
<?php
}
$bHayImprimir = false;
$sScript = 'imprimelista()';
$sClaseBoton = 'btEnviarExcel';
if ($seg_6 == 1) {
	$bHayImprimir = true;
}
if ($_REQUEST['paso'] != 0) {
	if ($seg_5 == 1) {
		//$bHayImprimir = true;
		//$sScript = 'imprimep()';
		//if ($iNumFormatosImprime>0) {
			//$sScript = 'expandesector(94)';
			//}
		//$sClaseBoton = 'btEnviarPDF'; //btUpPrint
		//if ($id_rpt != 0) { $sScript = 'verrpt()'; }
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
?>
<input id="cmdGuardar" name="cmdGuardar" type="button" class="btUpGuardar" onclick="enviaguardar();" title="<?php echo $ETI['bt_guardar']; ?>" value="<?php echo $ETI['bt_guardar']; ?>" />
<?php
if (false) {
?>
<input id="cmdAnular" name="cmdAnular" type="button" class="btSupAnular" onclick="expandesector(2);" title="<?php echo $ETI['bt_anular']; ?>" value="<?php echo $ETI['bt_anular']; ?>" />
<?php
}
?>
</div>
<div class="titulosI">
<?php
echo '<h2>' . $ETI['titulo_3070'] . '</h2>';
?>
</div>
</div>
<div class="areaform">
<div class="areatrabajo">
<?php
if ($seg_1707 == 1){
?>
<div class="GrupoCamposAyuda">
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
<input id="btRevisaDoc" name="btRevisaDoc" type="button" value="Actualizar" class="btMiniActualizar" onclick="cambiapagina()" title="Consultar documento" />
</label>
<label class="Label30"></label>
<b>
<?php
echo $sNombreUsuario;
?>
</b>
<div class="salto1px"></div>
</div>
<?php
}else{
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
if ($_REQUEST['boculta3070'] != 0) {
	$sEstiloExpande = '';
	$sEstiloRecoje = ' style="display:none;"';
	$sEstiloDiv = ' style="display:none;"';
}
?>
<input id="boculta3070" name="boculta3070" type="hidden" value="<?php echo $_REQUEST['boculta3070']; ?>" />
<label class="Label30">
<input id="btexpande3070" name="btexpande3070" type="button" value="Mostrar" class="btMiniExpandir" onclick="expandepanel(3070, 'block', 0);" title="<?php echo $ETI['bt_mostrar']; ?>"<?php echo $sEstiloExpande; ?>/>
</label>
<label class="Label30">
<input id="btrecoge3070" name="btrecoge3070" type="button" value="Ocultar" class="btMiniRecoger" onclick="expandepanel(3070, 'none', 1);" title="<?php echo $ETI['bt_ocultar']; ?>" <?php echo $sEstiloRecoje; ?>/>
</label>
</div>
<div id="div_p3070"<?php echo $sEstiloDiv; ?>>
<?php
}
//Mostrar formulario para editar
?>
<label class="Label60"<?php echo $sOcultaId; ?>>
<?php
echo $ETI['saiu70id'];
?>
</label>
<label class="Label60"<?php echo $sOcultaId; ?>>
<?php
	echo html_oculto('saiu70id', $_REQUEST['saiu70id'], formato_numero($_REQUEST['saiu70id']));
?>
</label>
<label class="Label130">
<?php
echo $ETI['saiu70activo'];
?>
</label>
<label>
<?php
echo $html_saiu70activo;
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu70idtipotramite'];
?>
</label>
<label>
<?php
echo $html_saiu70idtipotramite;
?>
</label>
<label class="Label130">
<?php
echo $ETI['saiu70numpaso'];
?>
</label>
<label>
<div id="div_saiu70numpaso">
<?php
echo $html_saiu70numpaso;
?>
</div>
</label>
<div class="salto1px"></div>
<div class="GrupoCampos">
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu70idzona'];
?>
</label>
<label>
<?php
echo $html_saiu70idzona;
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu70idcentro'];
?>
</label>
<label>
<div id="div_saiu70idcentro">
<?php
echo $html_saiu70idcentro;
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu70idescuela'];
?>
</label>
<label>
<?php
echo $html_saiu70idescuela;
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu70idprograma'];
?>
</label>
<label>
<div id="div_saiu70idprograma">
<?php
echo $html_saiu70idprograma;
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu70idunidad'];
?>
</label>
<label>
<?php
echo $html_saiu70idunidad;
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu70idgrupotrabajo'];
?>
</label>
<label>
<div id="div_saiu70idgrupotrabajo">
<?php
echo $html_saiu70idgrupotrabajo;
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu70idresponsable'];
?>
</label>
<label>
<div id="div_saiu70idresponsable">
<?php
echo $html_saiu70idresponsable;
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
	//Este es el cierre del div_p3070
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
<?php
if (true) {
?>
<div class="ir_derecha">
<label class="Label130">
<?php
echo $ETI['saiu70idtipotramite'];
?>
</label>
<label>
<?php
echo $html_btipotramite;
?>
</label>
<label class="Label130">
<?php
echo $ETI['saiu70numpaso'];
?>
</label>
<label class="Label130">
<?php
echo $html_bnumpaso;
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
<div id="div_f3070detalle">
<?php
echo $sTabla3070;
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
<input id="titulo_3070" name="titulo_3070" type="hidden" value="<?php echo $ETI['titulo_3070']; ?>" />
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
echo '<h2>' . $ETI['titulo_3070'] . '</h2>';
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
?>
<input id="cmdGuardarf" name="cmdGuardarf" type="button" class="btSoloGuardar" onClick="enviaguardar();" value="<?php echo $ETI['bt_guardar']; ?>" />
<?php
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
<script language="javascript" src="<?php echo $APP->rutacomun; ?>js/chosen.jquery.js"></script>
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>js/chosen.css" type="text/css" />
<?php
if ($_REQUEST['paso'] == 0) {
?>
<script language="javascript">
	$().ready(function() {
		$("#saiu70idprograma").chosen({width:"450px"});
		$("#saiu70idgrupotrabajo").chosen({width:"450px"});
	});
</script>
<?php
}
?>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>unad_todas.js?ver=8"></script>
<?php
forma_piedepagina();
?>