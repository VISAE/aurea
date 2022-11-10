<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2019 - 2022 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.22.7 miércoles, 20 de marzo de 2019
--- Modelo Versión 2.24.1 martes, 11 de febrero de 2020
--- Modelo Versión 2.25.0 martes, 17 de marzo de 2020
--- Modelo Versión 2.25.6 jueves, 10 de septiembre de 2020
--- Modelo Versión 2.28.2 lunes, 6 de junio de 2022
--- Modelo Versión 2.28.2 viernes, 17 de junio de 2022
*/

/** Archivo bitaequipotrabajo.php.
 * Modulo 1527 bita27equipotrabajo.
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
//require $APP->rutacomun.'libdatos.php';
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
$iCodModulo = 3011;
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
$mensajes_1527 = 'lg/lg_1527_' . $_SESSION['unad_idioma'] . '.php';
if (!file_exists($mensajes_1527)) {
	$mensajes_1527 = 'lg/lg_1527_es.php';
}
require $mensajes_todas;
require $mensajes_1527;
$xajax = NULL;
$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
if ($APP->dbpuerto != '') {
	$objDB->dbPuerto = $APP->dbpuerto;
}
$iPiel = iDefinirPiel($APP, 1);
$sAnchoExpandeContrae = ' style="width:62px;"';
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
	forma_cabeceraV3($xajax, $ETI['titulo_3011']);
	echo $et_menu;
	forma_mitad();
	?>
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
		header('Location:noticia.php?ret=misequipos.php');
		die();
	}
}
$idTercero = $_SESSION['unad_id_tercero'];
$bOtroUsuario = false;
$seg_1707 = 0;
$bDevuelve = false;
list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 1707, $_SESSION['unad_id_tercero'], $objDB, $bDebug);
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
$mensajes_1528 = 'lg/lg_1528_' . $_SESSION['unad_idioma'] . '.php';
if (!file_exists($mensajes_1528)) {
	$mensajes_1528 = 'lg/lg_1528_es.php';
}
require $mensajes_1528;
// -- Si esta cargando la pagina por primer vez se revisa si requiere auditar y se manda a hacer un limpiar (paso -1)
if (isset($_REQUEST['paso']) == 0) {
	$_REQUEST['paso'] = -1;
	if ($audita[1]) {
		seg_auditaingreso($iCodModulo, $_SESSION['unad_id_tercero'], $objDB);
	}
}
// -- 1527 bita27equipotrabajo
require 'lib1527.php';
// -- 1528 Integrantes
require 'lib1528.php';
$xajax = new xajax();
$xajax->configure('javascript URI', $APP->rutacomun . 'xajax/');
$xajax->register(XAJAX_FUNCTION, 'unad11_Mostrar_v2');
$xajax->register(XAJAX_FUNCTION, 'unad11_TraerXid');
$xajax->register(XAJAX_FUNCTION, 'f1527_Combobita27cead');
$xajax->register(XAJAX_FUNCTION, 'f1527_Combobita27idprograma');
$xajax->register(XAJAX_FUNCTION, 'sesion_abandona_V2');
$xajax->register(XAJAX_FUNCTION, 'sesion_mantenerV4');
$xajax->register(XAJAX_FUNCTION, 'f1527_HtmlTabla');
$xajax->register(XAJAX_FUNCTION, 'f1527_ExisteDato');
$xajax->register(XAJAX_FUNCTION, 'f1527_Busquedas');
$xajax->register(XAJAX_FUNCTION, 'f1527_HtmlBusqueda');
$xajax->register(XAJAX_FUNCTION, 'f1528_Guardar');
$xajax->register(XAJAX_FUNCTION, 'f1528_Traer');
$xajax->register(XAJAX_FUNCTION, 'f1528_Eliminar');
$xajax->register(XAJAX_FUNCTION, 'f1528_HtmlTabla');
$xajax->register(XAJAX_FUNCTION, 'f1528_PintarLlaves');
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
if (isset($_REQUEST['paginaf1527']) == 0) {
	$_REQUEST['paginaf1527'] = 1;
}
if (isset($_REQUEST['lppf1527']) == 0) {
	$_REQUEST['lppf1527'] = 20;
}
if (isset($_REQUEST['boculta1527']) == 0) {
	$_REQUEST['boculta1527'] = 1;
}
if (isset($_REQUEST['paginaf1528']) == 0) {
	$_REQUEST['paginaf1528'] = 1;
}
if (isset($_REQUEST['lppf1528']) == 0) {
	$_REQUEST['lppf1528'] = 20;
}
if (isset($_REQUEST['boculta1528']) == 0) {
	$_REQUEST['boculta1528'] = 0;
}
// -- Inicializar variables de datos.
if (isset($_REQUEST['bita27consec']) == 0) {
	$_REQUEST['bita27consec'] = '';
}
if (isset($_REQUEST['bita27consec_nuevo']) == 0) {
	$_REQUEST['bita27consec_nuevo'] = '';
}
if (isset($_REQUEST['bita27id']) == 0) {
	$_REQUEST['bita27id'] = '';
}
if (isset($_REQUEST['bita27nombre']) == 0) {
	$_REQUEST['bita27nombre'] = '';
}
if (isset($_REQUEST['bita27idlider']) == 0) {
	$_REQUEST['bita27idlider'] = 0;
}
if (isset($_REQUEST['bita27idlider_td']) == 0) {
	$_REQUEST['bita27idlider_td'] = $APP->tipo_doc;
}
if (isset($_REQUEST['bita27idlider_doc']) == 0) {
	$_REQUEST['bita27idlider_doc'] = '';
}
if (isset($_REQUEST['bita27idperfil']) == 0) {
	$_REQUEST['bita27idperfil'] = 0;
}
if (isset($_REQUEST['bita27correogrupo']) == 0) {
	$_REQUEST['bita27correogrupo'] = '';
}
if (isset($_REQUEST['bita27idunidadfunc']) == 0) {
	$_REQUEST['bita27idunidadfunc'] = 0;
}
if (isset($_REQUEST['bita27nivelrespuesta']) == 0) {
	$_REQUEST['bita27nivelrespuesta'] = 3;
}
if (isset($_REQUEST['bita27idzona']) == 0) {
	$_REQUEST['bita27idzona'] = '';
}
if (isset($_REQUEST['bita27cead']) == 0) {
	$_REQUEST['bita27cead'] = '';
}
if (isset($_REQUEST['bita27idescuela']) == 0) {
	$_REQUEST['bita27idescuela'] = '';
}
if (isset($_REQUEST['bita27idprograma']) == 0) {
	$_REQUEST['bita27idprograma'] = '';
}
if (isset($_REQUEST['bita27propietario']) == 0) {
	$_REQUEST['bita27propietario'] = 0;
	//$_REQUEST['bita27propietario'] = $idTercero;
}
if (isset($_REQUEST['bita27propietario_td']) == 0) {
	$_REQUEST['bita27propietario_td'] = $APP->tipo_doc;
}
if (isset($_REQUEST['bita27propietario_doc']) == 0) {
	$_REQUEST['bita27propietario_doc'] = '';
}
if (isset($_REQUEST['bita27activo']) == 0) {
	$_REQUEST['bita27activo'] = 1;
}
if ((int)$_REQUEST['paso'] > 0) {
	//Integrantes
	if (isset($_REQUEST['bita28idtercero']) == 0) {
		$_REQUEST['bita28idtercero'] = 0;
	}
	if (isset($_REQUEST['bita28idtercero_td']) == 0) {
		$_REQUEST['bita28idtercero_td'] = $APP->tipo_doc;
	}
	if (isset($_REQUEST['bita28idtercero_doc']) == 0) {
		$_REQUEST['bita28idtercero_doc'] = '';
	}
	if (isset($_REQUEST['bita28id']) == 0) {
		$_REQUEST['bita28id'] = '';
	}
	if (isset($_REQUEST['bita28activo']) == 0) {
		$_REQUEST['bita28activo'] = 'S';
	}
	if (isset($_REQUEST['bita28fechaingreso']) == 0) {
		$_REQUEST['bita28fechaingreso'] = '';
	}
	if (isset($_REQUEST['bita28fechasalida']) == 0) {
		$_REQUEST['bita28fechasalida'] = '';
	}
}
// Espacio para inicializar otras variables
if (isset($_REQUEST['csv_separa']) == 0) {
	$_REQUEST['csv_separa'] = ';';
}
if (isset($_REQUEST['bdoc']) == 0) {
	$_REQUEST['bdoc'] = '';
}
if (isset($_REQUEST['bestudiante']) == 0) {
	$_REQUEST['bestudiante'] = '';
}
if (isset($_REQUEST['bnombre']) == 0) {
	$_REQUEST['bnombre'] = '';
}
if (isset($_REQUEST['blistar']) == 0) {
	$_REQUEST['blistar'] = '';
}
if (isset($_REQUEST['blistar2']) == 0) {
	$_REQUEST['blistar2'] = '';
}
if (isset($_REQUEST['blistar3']) == 0) {
	$_REQUEST['blistar3'] = '';
}
if ((int)$_REQUEST['paso'] > 0) {
	//Integrantes
	if (isset($_REQUEST['bnombre1528']) == 0) {
		$_REQUEST['bnombre1528'] = '';
	}
}
//Si Modifica o Elimina Cargar los campos
if (($_REQUEST['paso'] == 1) || ($_REQUEST['paso'] == 3)) {
	$_REQUEST['bita27idlider_td'] = $APP->tipo_doc;
	$_REQUEST['bita27idlider_doc'] = '';
	$_REQUEST['bita27propietario_td'] = $APP->tipo_doc;
	$_REQUEST['bita27propietario_doc'] = '';
	if ($_REQUEST['paso'] == 1) {
		$sSQLcondi = 'bita27consec=' . $_REQUEST['bita27consec'] . '';
	} else {
		$sSQLcondi = 'bita27id=' . $_REQUEST['bita27id'] . '';
	}
	$sSQL = 'SELECT * FROM bita27equipotrabajo WHERE ' . $sSQLcondi;
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$_REQUEST['bita27consec'] = $fila['bita27consec'];
		$_REQUEST['bita27id'] = $fila['bita27id'];
		$_REQUEST['bita27nombre'] = $fila['bita27nombre'];
		$_REQUEST['bita27idlider'] = $fila['bita27idlider'];
		$_REQUEST['bita27idperfil'] = $fila['bita27idperfil'];
		$_REQUEST['bita27correogrupo'] = $fila['bita27correogrupo'];
		$_REQUEST['bita27idunidadfunc'] = $fila['bita27idunidadfunc'];
		$_REQUEST['bita27nivelrespuesta'] = $fila['bita27nivelrespuesta'];
		$_REQUEST['bita27idzona'] = $fila['bita27idzona'];
		$_REQUEST['bita27cead'] = $fila['bita27cead'];
		$_REQUEST['bita27idescuela'] = $fila['bita27idescuela'];
		$_REQUEST['bita27idprograma'] = $fila['bita27idprograma'];
		$_REQUEST['bita27propietario'] = $fila['bita27propietario'];
		$_REQUEST['bita27activo'] = $fila['bita27activo'];
		$bcargo = true;
		$_REQUEST['paso'] = 2;
		$_REQUEST['boculta1527'] = 0;
		$bLimpiaHijos = true;
	} else {
		$_REQUEST['paso'] = 0;
	}
}
//Insertar o modificar un elemento
if (($_REQUEST['paso'] == 10) || ($_REQUEST['paso'] == 12)) {
	$bMueveScroll = true;
	list($_REQUEST, $sError, $iTipoError, $sDebugGuardar) = f1527_db_GuardarV2($_REQUEST, $objDB, $bDebug, $idTercero, $iCodModulo);
	$sDebug = $sDebug . $sDebugGuardar;
	if ($sError == '') {
		$sError = '<b>' . $ETI['msg_itemguardado'] . '</b>';
		$iTipoError = 1;
	}
}
// Cambio de consecutivo.
if ($_REQUEST['paso'] == 93) {
	$_REQUEST['paso'] = 2;
	$_REQUEST['bita27consec_nuevo'] = numeros_validar($_REQUEST['bita27consec_nuevo']);
	if ($_REQUEST['bita27consec_nuevo'] == '') {
		$sError = $ERR['bita27consec'];
	}
	if ($sError == '') {
		list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 8, $idTercero, $objDB);
		if (!$bDevuelve) {
			$sError = $ERR['8'];
		}
	}
	if ($sError == '') {
		//Ver que el consecutivo no exista.
		$sSQL = 'SELECT bita27id FROM bita27equipotrabajo WHERE bita27consec=' . $_REQUEST['bita27consec_nuevo'] . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$sError = 'El consecutivo ' . $_REQUEST['bita27consec_nuevo'] . ' ya existe';
		}
	}
	if ($sError == '') {
		//Aplicar el cambio.
		$sSQL = 'UPDATE bita27equipotrabajo SET bita27consec=' . $_REQUEST['bita27consec_nuevo'] . ' WHERE bita27id=' . $_REQUEST['bita27id'] . '';
		$tabla = $objDB->ejecutasql($sSQL);
		$sDetalle = 'Cambia el consecutivo de ' . $_REQUEST['bita27consec'] . ' a ' . $_REQUEST['bita27consec_nuevo'] . '';
		$_REQUEST['bita27consec'] = $_REQUEST['bita27consec_nuevo'];
		$_REQUEST['bita27consec_nuevo'] = '';
		seg_auditar($iCodModulo, $_SESSION['u_idtercero'], 8, $_REQUEST['bita27id'], $sDetalle, $objDB);
		$sError = '<b>Se ha aplicado el cambio de consecutivo.</b>';
		$iTipoError = 1;
	} else {
		$iSector = 93;
	}
}
//Eliminar un elemento
if ($_REQUEST['paso'] == 13) {
	$_REQUEST['paso'] = 2;
	list($sError, $iTipoError, $sDebugElimina) = f1527_db_Eliminar($_REQUEST['bita27id'], $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugElimina;
	if ($sError == '') {
		$_REQUEST['paso'] = -1;
		$sError = $ETI['msg_itemeliminado'];
		$iTipoError = 1;
	}
}
//limpiar la pantalla
if ($_REQUEST['paso'] == -1) {
	$_REQUEST['bita27consec'] = '';
	$_REQUEST['bita27consec_nuevo'] = '';
	$_REQUEST['bita27id'] = '';
	$_REQUEST['bita27nombre'] = '';
	$_REQUEST['bita27idlider'] = 0; //$idTercero;
	$_REQUEST['bita27idlider_td'] = $APP->tipo_doc;
	$_REQUEST['bita27idlider_doc'] = '';
	$_REQUEST['bita27idperfil'] = 0;
	$_REQUEST['bita27correogrupo'] = '';
	$_REQUEST['bita27idunidadfunc'] = 0;
	$_REQUEST['bita27nivelrespuesta'] = 3;
	$_REQUEST['bita27idzona'] = 0;
	$_REQUEST['bita27cead'] = 0;
	$_REQUEST['bita27idescuela'] = 0;
	$_REQUEST['bita27idprograma'] = 0;
	$_REQUEST['bita27propietario'] = 0; //$idTercero;
	$_REQUEST['bita27propietario_td'] = $APP->tipo_doc;
	$_REQUEST['bita27propietario_doc'] = '';
	$_REQUEST['bita27activo'] = 1;
	$_REQUEST['paso'] = 0;
}
if ($bLimpiaHijos) {
	$_REQUEST['bita28idequipotrab'] = '';
	$_REQUEST['bita28idtercero'] = 0; //$idTercero;
	$_REQUEST['bita28idtercero_td'] = $APP->tipo_doc;
	$_REQUEST['bita28idtercero_doc'] = '';
	$_REQUEST['bita28id'] = '';
	$_REQUEST['bita28activo'] = 'S';
	$_REQUEST['bita28fechaingreso'] = ''; //fecha_hoy();
	$_REQUEST['bita28fechasalida'] = ''; //fecha_hoy();
}
//AQUI SE DEBEN CARGAR TODOS LOS DATOS QUE LA FORMA NECESITE.
$bConGuardar = false;
$bEditaLider = false;
if ($_REQUEST['paso'] != 0) {
	if ($_REQUEST['bita27idlider'] == $idTercero) {
		$bConGuardar = true;
	}
	if ($_REQUEST['bita27propietario'] == $idTercero) {
		$bEditaLider = true;
	}
}
//DATOS PARA COMPLETAR EL FORMULARIO
$iAgnoIni = 2000;
$iAgno = fecha_agno();
$iAgnoFin = $iAgno + 5;
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
$seg_8 = 0;
/*
list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 6, $idTercero, $objDB);
if ($bDevuelve) {
	$seg_6 = 1;
}
*/
//Crear los controles que requieran llamado a base de datos
$objCombos = new clsHtmlCombos();
$objTercero = new clsHtmlTercero();
list($bita27idlider_rs, $_REQUEST['bita27idlider'], $_REQUEST['bita27idlider_td'], $_REQUEST['bita27idlider_doc']) = html_tercero($_REQUEST['bita27idlider_td'], $_REQUEST['bita27idlider_doc'], $_REQUEST['bita27idlider'], 0, $objDB);
if (false) {
	$objCombos->nuevo('bita27idperfil', $_REQUEST['bita27idperfil'], true, '{' . $ETI['msg_ninguno'] . '}', 0);
	$objCombos->iAncho = 300;
	$sSQL = 'SELECT unad05id AS id, unad05nombre AS nombre FROM unad05perfiles WHERE unad05reservado="S" ORDER BY unad05nombre';
	$html_bita27idperfil = $objCombos->html($sSQL, $objDB);
} else {
	list($bita27idperfil_nombre, $sErrorDet) = tabla_campoxid('unad05perfiles', 'unad05nombre', 'unad05id', $_REQUEST['bita27idperfil'], '{' . $ETI['msg_ninguno'] . '}', $objDB);
	$html_bita27idperfil = html_oculto('bita27idperfil', $_REQUEST['bita27idperfil'], $bita27idperfil_nombre);
}
$objCombos->nuevo('bita27idunidadfunc', $_REQUEST['bita27idunidadfunc'], true, '{' . $ETI['msg_ninguna'] . '}', 0);
$sSQL26 = 'SELECT unae26id AS id, CONCAT(unae26prefijo, "", unae26nombre) AS nombre FROM unae26unidadesfun WHERE unae26idzona=0 AND unae26id>0 ORDER BY unae26lugar, unae26nombre';
$html_bita27idunidadfunc = $objCombos->html($sSQL26, $objDB);
$objCombos->nuevo('bita27nivelrespuesta', $_REQUEST['bita27nivelrespuesta'], false, '{' . $ETI['msg_seleccione'] . '}');
$sSQL17 = 'SELECT saiu17id AS id, saiu17nombre AS nombre FROM saiu17nivelatencion ORDER BY saiu17id';
$html_bita27nivelrespuesta = $objCombos->html($sSQL17, $objDB);
$objCombos->nuevo('bita27idzona', $_REQUEST['bita27idzona'], true, '{' . $ETI['msg_todas'] . '}', 0);
$objCombos->sAccion = 'carga_combo_bita27cead();';
$sSQL = 'SELECT unad23id AS id, unad23nombre AS nombre FROM unad23zona WHERE unad23id>0 ORDER BY unad23conestudiantes DESC, unad23nombre';
$html_bita27idzona = $objCombos->html($sSQL, $objDB);
$html_bita27cead = f1527_HTMLComboV2_bita27cead($objDB, $objCombos, $_REQUEST['bita27cead'], $_REQUEST['bita27idzona']);
$objCombos->nuevo('bita27idescuela', $_REQUEST['bita27idescuela'], true, '{' . $ETI['msg_todas'] . '}', 0);
$objCombos->sAccion = 'carga_combo_bita27idprograma();';
$sSQL = 'SELECT core12id AS id, core12nombre AS nombre FROM core12escuela WHERE core12id>0 ORDER BY core12nombre';
$html_bita27idescuela = $objCombos->html($sSQL, $objDB);
$html_bita27idprograma = f1527_HTMLComboV2_bita27idprograma($objDB, $objCombos, $_REQUEST['bita27idprograma'], $_REQUEST['bita27idescuela']);
list($bita27propietario_rs, $_REQUEST['bita27propietario'], $_REQUEST['bita27propietario_td'], $_REQUEST['bita27propietario_doc']) = html_tercero($_REQUEST['bita27propietario_td'], $_REQUEST['bita27propietario_doc'], $_REQUEST['bita27propietario'], 0, $objDB);
$et_bita27activo = $ETI['si'];
if ($_REQUEST['bita27activo'] == 0) {
	$et_bita27activo = $ETI['no'];
}
$html_bita27activo = html_oculto('bita27activo', $_REQUEST['bita27activo'], $et_bita27activo);
if ((int)$_REQUEST['paso'] == 0) {
} else {
	list($bita28idtercero_rs, $_REQUEST['bita28idtercero'], $_REQUEST['bita28idtercero_td'], $_REQUEST['bita28idtercero_doc']) = html_tercero($_REQUEST['bita28idtercero_td'], $_REQUEST['bita28idtercero_doc'], $_REQUEST['bita28idtercero'], 0, $objDB);
	$objCombos->nuevo('bita28activo', $_REQUEST['bita28activo'], false);
	$objCombos->sino($ETI['si'], $ETI['no']); //, $sValorSi='S', $sValorNo='N'
	$html_bita28activo = $objCombos->html('', $objDB);
}
//Alistar datos adicionales
$id_rpt = 0;
//$id_rpt=reportes_id(_Identificador_Tipo_Reporte_, $objDB);
$objCombos->nuevo('blistar', $_REQUEST['blistar'], true, '{' . $ETI['msg_todos'] . '}');
$objCombos->addItem('0', '{Sin Unidad}');
$objCombos->iAncho = 450;
$objCombos->sAccion = 'paginarf1527()';
$html_blistar = $objCombos->html($sSQL26, $objDB);

$objCombos->nuevo('blistar2', $_REQUEST['blistar2'], true, '{' . $ETI['msg_todos'] . '}');
$objCombos->sAccion = 'paginarf1527()';
$html_blistar2 = $objCombos->html($sSQL17, $objDB);

$objCombos->nuevo('blistar3', $_REQUEST['blistar3'], true, '{' . $ETI['msg_todos'] . '}');
$objCombos->sAccion = 'paginarf1527()';
$objCombos->addItem(1, 'Donde soy integrante');
$objCombos->addItem(2, 'Donde soy lider');
$html_blistar3 = $objCombos->html('', $objDB);
/*
//$html_blistar=$objCombos->comboSistema(1527, 1, $objDB, 'paginarf1527()');
$objCombos->nuevo('blistar1528', $_REQUEST['blistar1528'], true, '{'.$ETI['msg_todos'].'}');
$html_blistar1528=$objCombos->comboSistema(1528, 1, $objDB, 'paginarf1528()');
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
$iModeloReporte = 1527;
$html_iFormatoImprime = '<input id="iformatoimprime" name="iformatoimprime" type="hidden" value="0" />
';
if ($_REQUEST['paso'] > 0) {
	$bDevuelve = false;
	//list($bDevuelve, $sDebugP)=seg_revisa_permisoV3($iCodModulo, 5, $idTercero, $objDB);
	if ($bDevuelve) {
		$seg_5 = 1;
	}
	list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 8, $idTercero, $objDB);
	if ($bDevuelve) {
		$seg_8 = 1;
	}
}
//Cargar las tablas de datos
$aParametros[0] = ''; //$_REQUEST['p1_1527'];
$aParametros[98] = $idTercero;
$aParametros[100] = $idTercero;
$aParametros[101] = $_REQUEST['paginaf1527'];
$aParametros[102] = $_REQUEST['lppf1527'];
$aParametros[103] = $_REQUEST['bnombre'];
$aParametros[104] = $_REQUEST['blistar'];
$aParametros[105] = $_REQUEST['bdoc'];
$aParametros[106] = $_REQUEST['bestudiante'];
$aParametros[107] = $_REQUEST['blistar2'];
list($sTabla1527, $sDebugTabla) = f1527_TablaDetalleV2($aParametros, $objDB, $bDebug);
$sDebug = $sDebug . $sDebugTabla;
$sTabla1528 = '';
if ($_REQUEST['paso'] != 0) {
	//Integrantes
	$aParametros1528[0] = $_REQUEST['bita27id'];
	$aParametros1528[100] = $idTercero;
	$aParametros1528[101] = $_REQUEST['paginaf1528'];
	$aParametros1528[102] = $_REQUEST['lppf1528'];
	//$aParametros1528[103]=$_REQUEST['bnombre1528'];
	//$aParametros1528[104]=$_REQUEST['blistar1528'];
	list($sTabla1528, $sDebugTabla) = f1528_TablaDetalleV2($aParametros1528, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugTabla;
}
$bDebugMenu = false;
list($et_menu, $sDebugM) = html_menuV2($APP->idsistema, $objDB, $iPiel, $bDebugMenu, $idTercero);
$sDebug = $sDebug . $sDebugM;
$objDB->CerrarConexion();
//FORMA
require $APP->rutacomun . 'unad_forma_v2.php';
forma_cabeceraV3($xajax, $ETI['titulo_3011']);
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
		var dpaso = window.document.frmedita.paso;
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
		var objdiv = document.getElementById('div_p' + codigo);
		var objban = document.getElementById('boculta' + codigo);
		var otroestado = 'none';
		if (estado == 'none') {
			otroestado = 'block';
		}
		objdiv.style.display = estado;
		objban.value = valor;
		verboton('btrecoge' + codigo, estado);
		verboton('btexpande' + codigo, otroestado);
	}

	function verboton(idboton, estado) {
		var objbt = document.getElementById(idboton);
		objbt.style.display = estado;
	}

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
if ($bConGuardar) {
?>
		var sEst = 'none';
		if (codigo == 1) {
			sEst = 'block';
		}
		document.getElementById('cmdGuardarf').style.display = sEst;
<?php
}
?>
	}

	function ter_retorna() {
		var sRetorna = window.document.frmedita.div96v2.value;
		if (sRetorna != '') {
			var idcampo = window.document.frmedita.div96campo.value;
			var illave = window.document.frmedita.div96llave.value;
			var did = document.getElementById(idcampo);
			var dtd = document.getElementById(idcampo + '_td');
			var ddoc = document.getElementById(idcampo + '_doc');
			dtd.value = window.document.frmedita.div96v1.value;
			ddoc.value = sRetorna;
			did.value = window.document.frmedita.div96v3.value;
			ter_muestra(idcampo, illave);
		}
		MensajeAlarmaV2('', 0);
		retornacontrol();
	}

	function ter_muestra(idcampo, illave) {
		var params = new Array();
		params[1] = document.getElementById(idcampo + '_doc').value;
		if (params[1] != '') {
			params[0] = document.getElementById(idcampo + '_td').value;
			params[2] = idcampo;
			params[3] = 'div_' + idcampo;
			if (illave == 2) {
				params[4] = 'revisaf1528';
			}
			xajax_unad11_Mostrar_v2(params);
		} else {
			document.getElementById(idcampo).value = 0;
			document.getElementById('div_' + idcampo).innerHTML = '&nbsp;';
			//FuncionCuandoNoHayNada
		}
	}

	function ter_traerxid(idcampo, vrcampo) {
		var params = new Array();
		params[0] = vrcampo;
		params[1] = idcampo;
		if (params[0] != 0) {
			xajax_unad11_TraerXid(params);
		}
	}

	function imprimelista() {
		if (window.document.frmedita.seg_6.value == 1) {
			window.document.frmlista.consulta.value = window.document.frmedita.consulta_1527.value;
			window.document.frmlista.titulos.value = window.document.frmedita.titulos_1527.value;
			window.document.frmlista.nombrearchivo.value = 'Equipos de trabajo';
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
		var sError = '';
		if (window.document.frmedita.seg_6.value != 1) {
			sError = "<?php echo $ERR['6']; ?>";
		}
		if (sError == '') {
			/*Agregar validaciones*/
		}
		if (sError == '') {
			asignarvariables();
			window.document.frmimpp.action = 'e1527.php';
			window.document.frmimpp.submit();
		} else {
			ModalMensaje(sError);
		}
	}

	function imprimep() {
		if (window.document.frmedita.seg_5.value == 1) {
			asignarvariables();
			window.document.frmimpp.action = 'p1527.php';
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
		var datos = new Array();
		datos[1] = window.document.frmedita.bita27consec.value;
		if ((datos[1] != '')) {
			xajax_f1527_ExisteDato(datos);
		}
	}

	function cargadato(llave1) {
		window.document.frmedita.bita27consec.value = String(llave1);
		window.document.frmedita.paso.value = 1;
		window.document.frmedita.submit();
	}

	function cargaridf1527(llave1) {
		window.document.frmedita.bita27id.value = String(llave1);
		window.document.frmedita.paso.value = 3;
		window.document.frmedita.submit();
	}

	function carga_combo_bita27cead() {
		var params = new Array();
		params[0] = window.document.frmedita.bita27idzona.value;
		document.getElementById('div_bita27cead').innerHTML = '<b>Procesando datos, por favor espere...</b><input id="bita27cead" name="bita27cead" type="hidden" value="" />';
		xajax_f1527_Combobita27cead(params);
	}

	function carga_combo_bita27idprograma() {
		var params = new Array();
		params[0] = window.document.frmedita.bita27idescuela.value;
		document.getElementById('div_bita27idprograma').innerHTML = '<b>Procesando datos, por favor espere...</b><input id="bita27idprograma" name="bita27idprograma" type="hidden" value="" />';
		xajax_f1527_Combobita27idprograma(params);
	}

	function paginarf1527() {
		var params = new Array();
		params[98] = <?php echo $idTercero; ?>;
		params[99] = window.document.frmedita.debug.value;
		params[100] = <?php echo $idTercero; ?>;
		params[101] = window.document.frmedita.paginaf1527.value;
		params[102] = window.document.frmedita.lppf1527.value;
		params[103] = window.document.frmedita.bnombre.value;
		params[104] = window.document.frmedita.blistar.value;
		params[105] = window.document.frmedita.bdoc.value;
		params[106] = window.document.frmedita.bestudiante.value;
		params[107] = window.document.frmedita.blistar2.value;
		//document.getElementById('div_f1527detalle').innerHTML='<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf1527" name="paginaf1527" type="hidden" value="'+params[101]+'" /><input id="lppf1527" name="lppf1527" type="hidden" value="'+params[102]+'" />';
		xajax_f1527_HtmlTabla(params);
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
		document.getElementById("bita27consec").focus();
	}

	function buscarV2016(sCampo) {
		window.document.frmedita.iscroll.value = window.pageYOffset;
		expandesector(98);
		window.document.frmedita.scampobusca.value = sCampo;
		var params = new Array();
		params[1] = sCampo;
		//params[2] = window.document.frmedita.iagno.value;
		//params[3] = window.document.frmedita.itipo.value;
		xajax_f1527_Busquedas(params);
	}

	function retornacontrol() {
		expandesector(1);
		window.scrollTo(0, window.document.frmedita.iscroll.value);
	}

	function Devuelve(sValor) {
		var sCampo = window.document.frmedita.scampobusca.value;
		if (sCampo == 'bita27idlider') {
			ter_traerxid('bita27idlider', sValor);
		}
		if (sCampo == 'bita27propietario') {
			ter_traerxid('bita27propietario', sValor);
		}
		if (sCampo == 'bita28idtercero') {
			ter_traerxid('bita28idtercero', sValor);
		}
		retornacontrol();
	}

	function mantener_sesion() {
		xajax_sesion_mantenerV4();
	}
	setInterval('xajax_sesion_abandona_V2();', 60000);

	function AyudaLocal(sCampo) {
		var divAyuda = document.getElementById('div_ayuda_' + sCampo);
		if (typeof divAyuda === 'undefined') {
		} else {
			verboton('cmdAyuda_' + sCampo, 'none');
			var sMensaje = 'Lo que quiera decir.';
			//if (sCampo == 'sNombreCampo') {
				//sMensaje = 'Mensaje para otro campo.';
			//}
			divAyuda.innerHTML = sMensaje;
			divAyuda.style.display = 'block';
		}
	}

	function cierraDiv96(ref) {
		var sRetorna = window.document.frmedita.div96v2.value;
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
</script>
<?php
if ($_REQUEST['paso'] != 0) {
?>
<script language="javascript" src="jsi/js1528.js?v=4"></script>
<form id="frmimpp" name="frmimpp" method="post" action="p1527.php" target="_blank">
<input id="r" name="r" type="hidden" value="1527" />
<input id="id1527" name="id1527" type="hidden" value="<?php echo $_REQUEST['bita27id']; ?>" />
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
if ($bConGuardar) {
?>
<input id="cmdGuardar" name="cmdGuardar" type="button" class="btUpGuardar" onclick="enviaguardar();" title="<?php echo $ETI['bt_guardar']; ?>" value="<?php echo $ETI['bt_guardar']; ?>" />
<?php
}
?>
</div>
<div class="titulosI">
<?php
echo '<h2>' . $ETI['titulo_3011'] . '</h2>';
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
if ($_REQUEST['boculta1527'] != 0) {
	$sEstiloExpande = '';
	$sEstiloRecoje = ' style="display:none;"';
	$sEstiloDiv = ' style="display:none;"';
}
?>
<input id="boculta1527" name="boculta1527" type="hidden" value="<?php echo $_REQUEST['boculta1527']; ?>" />
<label class="Label30">
<input id="btexpande1527" name="btexpande1527" type="button" value="Mostrar" class="btMiniExpandir" onclick="expandepanel(1527, 'block', 0);" title="<?php echo $ETI['bt_mostrar']; ?>"<?php echo $sEstiloExpande; ?>/>
</label>
<label class="Label30">
<input id="btrecoge1527" name="btrecoge1527" type="button" value="Ocultar" class="btMiniRecoger" onclick="expandepanel(1527, 'none', 1);" title="<?php echo $ETI['bt_ocultar']; ?>" <?php echo $sEstiloRecoje; ?>/>
</label>
</div>
<div id="div_p1527"<?php echo $sEstiloDiv; ?>>
<?php
}
//Mostrar formulario para editar
?>
<label class="Label200">
<?php
echo $ETI['bita27consec'];
?>
</label>
<label class="Label130">
<?php
if (false) {
?>
<input id="bita27consec" name="bita27consec" type="text" value="<?php echo $_REQUEST['bita27consec']; ?>" onchange="RevisaLlave()" class="cuatro" />
<?php
} else {
	echo html_oculto('bita27consec', $_REQUEST['bita27consec'], formato_numero($_REQUEST['bita27consec']));
}
?>
</label>
<?php
/*
if ($seg_8 == 1) {
	$objForma = new clsHtmlForma($iPiel);
	echo $objForma->htmlBotonSolo('cmdCambiaConsec', 'btMiniActualizar', 'expandesector(93);', $ETI['bt_cambiar'], 30);
	echo '<label class="Label30">&nbsp;</label>';
}
*/
?>
<label class="Label60">
<?php
echo $ETI['bita27id'];
?>
</label>
<label class="Label60">
<?php
	echo html_oculto('bita27id', $_REQUEST['bita27id'], formato_numero($_REQUEST['bita27id']));
?>
</label>
<label class="Label90">
<?php
echo $ETI['bita27activo'];
?>
</label>
<label class="Label60">
<?php
echo $html_bita27activo;
?>
</label>
<div class="salto1px"></div>
<label class="Label200">
<?php
echo $ETI['bita27nivelrespuesta'];
?>
</label>
<label class="Label200">
<?php
echo $html_bita27nivelrespuesta;
?>
</label>
<label class="Label160">
<?php
echo $ETI['bita27idunidadfunc'];
?>
</label>
<label>
<?php
echo $html_bita27idunidadfunc;
?>
</label>
<label class="L">
<?php
echo $ETI['bita27nombre'];
?>

<input id="bita27nombre" name="bita27nombre" type="text" value="<?php echo $_REQUEST['bita27nombre']; ?>" maxlength="100" class="L" placeholder="<?php echo $ETI['ing_campo'] . $ETI['bita27nombre']; ?>" />
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['bita27idzona'];
?>
</label>
<label>
<?php
echo $html_bita27idzona;
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['bita27cead'];
?>
</label>
<label>
<div id="div_bita27cead">
<?php
echo $html_bita27cead;
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['bita27idescuela'];
?>
</label>
<label>
<?php
echo $html_bita27idescuela;
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['bita27idprograma'];
?>
</label>
<label>
<div id="div_bita27idprograma">
<?php
echo $html_bita27idprograma;
?>
</div>
</label>
<div class="salto1px"></div>
<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo $ETI['bita27idlider'];
?>
</label>
<div class="salto1px"></div>
<input id="bita27idlider" name="bita27idlider" type="hidden" value="<?php echo $_REQUEST['bita27idlider']; ?>" />
<div id="div_bita27idlider_llaves">
<?php
$bOculto = !$bEditaLider;
echo html_DivTerceroV2('bita27idlider', $_REQUEST['bita27idlider_td'], $_REQUEST['bita27idlider_doc'], $bOculto, 0, $ETI['ing_doc']);
?>
</div>
<div class="salto1px"></div>
<div id="div_bita27idlider" class="L"><?php echo $bita27idlider_rs; ?></div>
<div class="salto1px"></div>
</div>

<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo $ETI['bita27propietario'];
?>
</label>
<div class="salto1px"></div>
<input id="bita27propietario" name="bita27propietario" type="hidden" value="<?php echo $_REQUEST['bita27propietario']; ?>" />
<div id="div_bita27propietario_llaves">
<?php
$bOculto = true;
echo html_DivTerceroV2('bita27propietario', $_REQUEST['bita27propietario_td'], $_REQUEST['bita27propietario_doc'], $bOculto, 0, $ETI['ing_doc']);
?>
</div>
<div class="salto1px"></div>
<div id="div_bita27propietario" class="L"><?php echo $bita27propietario_rs; ?></div>
<div class="salto1px"></div>
</div>

<div class="GrupoCampos450">
<label class="Label130">
<?php
echo $ETI['bita27idperfil'];
?>
</label>
<label>
<?php
echo $html_bita27idperfil;
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['bita27correogrupo'];
?>
</label>
<label>
<input id="bita27correogrupo" name="bita27correogrupo" type="text" value="<?php echo $_REQUEST['bita27correogrupo']; ?>" maxlength="50" placeholder="<?php echo $ETI['ing_campo'] . $ETI['bita27correogrupo']; ?>" />
</label>
<div class="salto1px"></div>
</div>
<?php
// -- Inicia Grupo campos 1528 Integrantes
?>
<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo $ETI['titulo_1528'];
?>
</label>
<input id="boculta1528" name="boculta1528" type="hidden" value="<?php echo $_REQUEST['boculta1528']; ?>" />
<?php
if ($_REQUEST['paso'] == 2) {
	//if ($bCondicion) {
?>
<div class="ir_derecha"<?php echo $sAnchoExpandeContrae; ?>>
<?php
if (false) {
?>
<label class="Label30">
<input id="btexcel1528" name="btexcel1528" type="button" value="Exportar" class="btMiniExcel" onclick="imprime1528();" title="Exportar" />
</label>
<?php
}
$sEstiloExpande = ' style="display:none;"';
$sEstiloRecoje = '';
$sEstiloDiv = '';
if ($_REQUEST['boculta1528'] != 0) {
	$sEstiloExpande = '';
	$sEstiloRecoje = ' style="display:none;"';
	$sEstiloDiv = ' style="display:none;"';
}
?>
<label class="Label30">
<input id="btexpande1528" name="btexpande1528" type="button" value="Expandir" class="btMiniExpandir" onclick="expandepanel(1528, 'block', 0);" title="<?php echo $ETI['bt_mostrar']; ?>"<?php echo $sEstiloExpande; ?>/>
</label>
<label class="Label30">
<input id="btrecoge1528" name="btrecoge1528" type="button" value="Recoger" class="btMiniRecoger" onclick="expandepanel(1528, 'none', 1);" title="<?php echo $ETI['bt_ocultar']; ?>"<?php echo $sEstiloRecoje; ?>/>
</label>
</div>
<div class="salto1px"></div>
<div id="div_p1528"<?php echo $sEstiloDiv; ?>>
<div class="salto1px"></div>
<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo $ETI['bita28idtercero'];
?>
</label>
<div class="salto1px"></div>
<input id="bita28idtercero" name="bita28idtercero" type="hidden" value="<?php echo $_REQUEST['bita28idtercero']; ?>" />
<div id="div_bita28idtercero_llaves">
<?php
$bOculto = true;
if ((int)$_REQUEST['bita28id'] == 0) {
	$bOculto = false;
}
echo html_DivTerceroV2('bita28idtercero', $_REQUEST['bita28idtercero_td'], $_REQUEST['bita28idtercero_doc'], $bOculto, 2, $ETI['ing_doc']);
?>
</div>
<div class="salto1px"></div>
<div id="div_bita28idtercero" class="L"><?php echo $bita28idtercero_rs; ?></div>
<div class="salto1px"></div>
</div>

<div class="GrupoCampos450">
<label class="Label160">
<?php
echo $ETI['bita28activo'];
?>
</label>
<label class="Label60">
<?php
echo $html_bita28activo;
?>
</label>
<label class="Label60">
<?php
echo $ETI['bita28id'];
?>
</label>
<label class="Label60">
<div id="div_bita28id">
<?php
echo html_oculto('bita28id', $_REQUEST['bita28id']);
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['bita28fechaingreso'];
?>
</label>
<div class="Campo220">
<?php
echo html_FechaEnNumero('bita28fechaingreso', $_REQUEST['bita28fechaingreso']); //$bvacio=false,$accion=",$iagnoini=0,$iagnofin=0
?>
</div>
<?php
if (false) {
?>
<label class="Label30">
<input id="bbita28fechaingreso_hoy" name="bbita28fechaingreso_hoy" type="button" value="Hoy" class="btMiniHoy" onclick="fecha_AsignarNum('bita28fechaingreso', '<?php echo $iHoy; ?>')" title="<?php echo $ETI['bt_hoy']; ?>" />
</label>
<?php
}
?>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['bita28fechasalida'];
?>
</label>
<div class="Campo220">
<?php
echo html_FechaEnNumero('bita28fechasalida', $_REQUEST['bita28fechasalida'], true); //$bvacio=false,$accion=",$iagnoini=0,$iagnofin=0
?>
</div>
<?php
if (false) {
?>
<label class="Label30">
<input id="bbita28fechasalida_hoy" name="bbita28fechasalida_hoy" type="button" value="Hoy" class="btMiniHoy" onclick="fecha_AsignarNum('bita28fechasalida', '<?php echo $iHoy; ?>')" title="<?php echo $ETI['bt_hoy']; ?>" />
</label>
<?php
}
?>
<div class="salto1px"></div>
</div>

<div class="salto1px"></div>
<label class="Label130">&nbsp;</label>
<?php
$sEstiloElimina = ' style="display:none;"';
if ((int)$_REQUEST['bita28id'] != 0) {
	$sEstiloElimina = ' style="inline-block;"';
}
?>
<label class="Label30">
<input id="bguarda1528" name="bguarda1528" type="button" value="Guardar" class="btMiniGuardar" onclick="guardaf1528()" title="<?php echo $ETI['bt_mini_guardar_1528']; ?>" />
</label>
<label class="Label30">
<input id="blimpia1528" name="blimpia1528" type="button" value="Limpiar" class="btMiniLimpiar" onclick="limpiaf1528()" title="<?php echo $ETI['bt_mini_limpiar_1528']; ?>" />
</label>
<label class="Label30">
<input id="belimina1528" name="belimina1528" type="button" value="Eliminar" class="btMiniEliminar" onclick="eliminaf1528()" title="<?php echo $ETI['bt_mini_eliminar_1528']; ?>"<?php echo $sEstiloElimina; ?>/>
</label>
<?php
//Este es el cierre del div_p1528
?>
<div class="salto1px"></div>
</div>
<?php
		//} //Termina el segundo bloque  condicional - bloque editar.
?>
<div class="salto1px"></div>
<?php
?>
<div id="div_f1528detalle">
<?php
echo $sTabla1528;
?>
</div>
<?php
}
?>
<div class="salto1px"></div>
</div>
<?php
// -- Termina Grupo campos 1528 Integrantes
?>
<?php
if (false) {
	//Ejemplo de boton de ayuda
	//echo html_BotonAyuda('NombreCampo');
	//echo html_DivAyudaLocal('NombreCampo');
}
if ($bConExpande) {
	//Este es el cierre del div_p1527
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
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['bita27nombre_grupo'];
?>
</label>
<label class="Label250">
<input id="bnombre" name="bnombre" type="text" value="<?php echo $_REQUEST['bnombre']; ?>" onchange="paginarf1527()" autocomplete="off" />
</label>
<label class="Label160">
<?php
echo $ETI['bita27idunidadfunc'];
?>
</label>
<label class="Label500">
<?php
echo $html_blistar;
?>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['bita27nivelrespuesta'];
?>
</label>
<label class="Label200">
<?php
echo $html_blistar2;
?>
</label>
<label class="Label160">
<?php
echo $ETI['msg_blistar'];
?>
</label>
<label class="Label200">
<?php
echo $html_blistar3;
?>
</label>
<div class="salto1px"></div>
<label class="Label200">
<?php
echo $ETI['bita28idtercero_doc'];
?>
</label>
<label class="Label250">
<input id="bdoc" name="bdoc" type="text" value="<?php echo $_REQUEST['bdoc']; ?>" onchange="paginarf1527()" autocomplete="off" />
</label>
<label class="Label90">
<?php
echo $ETI['bita28idtercero'];
?>
</label>
<label class="Label250">
<input id="bestudiante" name="bestudiante" type="text" value="<?php echo $_REQUEST['bestudiante']; ?>" onchange="paginarf1527()" autocomplete="off" />
</label>
</div>
<div class="salto1px"></div>
<?php
echo ' ' . $csv_separa;
?>
<div id="div_f1527detalle">
<?php
echo $sTabla1527;
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
echo $ETI['msg_bita27consec'];
?>
</label>
<label class="Label90">
<?php
echo '<b>' . $_REQUEST['bita27consec'] . '</b>';
?>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['msg_bita27consec_nuevo'];
// onchange="RevisaConsec()"
?>
</label>
<label class="Label90">
<input id="bita27consec_nuevo" name="bita27consec_nuevo" type="text" value="<?php echo $_REQUEST['bita27consec_nuevo']; ?>" class="cuatro" />
</label>
<div class="salto1px"></div>
<label class="Label160">&nbsp;</label>
<?php
echo $objForma->htmlBotonSolo('cmdCambiaConsecFinal', 'botonProceso', 'mod_consec();', $ETI['bt_cambiar'], 130);
echo $objForma->htmlFinMarco();
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
<input id="titulo_1527" name="titulo_1527" type="hidden" value="<?php echo $ETI['titulo_3011']; ?>" />
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


<div id="div_sector97" style="display:none">
<div class="titulos">
<div class="titulosD">
<input id="cmdAyuda97" name="cmdAyuda97" type="button" class="btSupAyuda" onclick="muestraayuda(<?php echo $iCodModulo; ?>);" title="<?php echo $ETI['bt_ayuda']; ?>" value="<?php echo $ETI['bt_ayuda']; ?>" />
<input id="cmdVolverSec97" name="cmdVolverSec97" type="button" class="btSupVolver" onclick="retornacontrol();" title="<?php echo $ETI['bt_volver']; ?>" value="<?php echo $ETI['bt_volver']; ?>" />
</div>
<div class="titulosI" id="div_97titulo">
<?php
echo '<h2>' . $ETI['titulo_3011'] . '</h2>';
?>
</div>
</div>
<div id="areaform">
<div id="areatrabajo">
<div id="div_97params"></div>
<div class="salto1px"></div>
<div id="div_97tabla"></div>
</div>
</div>
</div>


<div id="div_sector98" style="display:none">
<div class="titulos">
<div class="titulosD">
<input id="cmdAyuda98" name="cmdAyuda98" type="button" class="btSupAyuda" onclick="muestraayuda(<?php echo $iCodModulo; ?>);" title="<?php echo $ETI['bt_ayuda']; ?>" value="<?php echo $ETI['bt_ayuda']; ?>" />
</div>
<div class="titulosI">
<?php
echo '<h2>' . $ETI['titulo_3011'] . '</h2>';
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
if ($bConGuardar) {
?>
<input id="cmdGuardarf" name="cmdGuardarf" type="button" class="btSoloGuardar" onClick="enviaguardar();" value="<?php echo $ETI['bt_guardar']; ?>" />
<?php
}
?>
</div>
<?php
echo html_DivAlarmaV2($sError, $iTipoError);
//El script que cambia el sector que se muestra
?>

<script language="javascript">
<?php
if ($iSector != 1) {
	echo 'setTimeout(function() {expandesector(' . $iSector . ');}, 10);
';
}
if ($bMueveScroll) {
	echo 'setTimeout(function() {retornacontrol();}, 2);
';
}
?>
</script>
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>js/jquery.autocomplete.css" type="text/css" />
<script language="javascript" src="<?php echo $APP->rutacomun; ?>js/jquery.autocomplete.js"></script>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>js/chosen.jquery.js"></script>
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>js/chosen.css" type="text/css" />
<?php
//if ($_REQUEST['paso'] == 0) {
?>
<script language="javascript">
$().ready(function() {
$("#bita27idunidadfunc").chosen();
$("#bita27idprograma").chosen();
});
</script>
<?php
//}
?>
<script language="javascript" src="ac_1527.js?v=3"></script>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>unad_todas.js?ver=8"></script>
<?php
forma_piedepagina();
?>