<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2022 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.28.2 jueves, 26 de mayo de 2022
*/
/** Archivo saicambiodoc.php.
 * Modulo 240 unae24historialcambdoc.
 * @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
 * @param debug=1 (Opcional), bandera para indicar si se generan datos de depuración
 * @date jueves, 26 de mayo de 2022
 */
if (file_exists('./err_control.php')) {
	require './err_control.php';
}
$bDebug = false;
$sDebug = '';
if (isset($_REQUEST['deb_doc']) != 0) {
	$bDebug = true;
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
$iCodModulo = 240;
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
//$mensajes_200 = 'lg/lg_200_' . $_SESSION['unad_idioma'] . '.php';
//if (!file_exists($mensajes_200)) {
	//$mensajes_200 = 'lg/lg_200_es.php';
//}
$mensajes_240 = 'lg/lg_240_' . $_SESSION['unad_idioma'] . '.php';
if (!file_exists($mensajes_240)) {
	$mensajes_240 = 'lg/lg_240_es.php';
}
require $mensajes_todas;
//require $mensajes_200;
require $mensajes_240;
$xajax = NULL;
$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
if ($APP->dbpuerto != '') {
	$objDB->dbPuerto = $APP->dbpuerto;
}
$iPiel = iDefinirPiel($APP, 1);
$sAnchoExpandeContrae = ' style="width:62px;"';
if ($bDebug) {
	$sDebug = $sDebug . fecha_microtiempo() . ' Probando conexi&oacute;n con la base de datos <b>' . $APP->dbname . '</b> en <b>' . $APP->dbhost . '</b><br>';
}
if (!$objDB->Conectar()) {
	$bCerrado = true;
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' Error al intentar conectar con la base de datos <b>' . $objDB->serror . '</b><br>';
	}
}
$bDevuelve = true;
// list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 1, $_SESSION['unad_id_tercero'], $objDB);
if (!$bDevuelve) {
	header('Location:nopermiso.php');
	die();
}
if (!$bPeticionXAJAX) {
	if (noticias_pendientes($objDB)) {
		$objDB->CerrarConexion();
		header('Location:noticia.php?ret=saicambiodoc.php');
		die();
	}
}
$idTercero = $_SESSION['unad_id_tercero'];
$bOtroUsuario = false;
if (isset($_REQUEST['deb_doc']) != 0) {
	$bDevuelve = true;
	// list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 1707, $_SESSION['unad_id_tercero'], $objDB, $bDebug);
	//$sDebug = $sDebug . $sDebugP;
	if ($bDevuelve) {
		$sSQL = 'SELECT unad11id, unad11razonsocial FROM unad11terceros WHERE unad11doc="' . $_REQUEST['deb_doc'] . '"';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			$idTercero = $fila['unad11id'];
			$bOtroUsuario = true;
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . ' Se verifica la ventana de trabajo para el usuario ' . $fila['unad11razonsocial'] . '.<br>';
			}
		} else {
			$sError = 'No se ha encontrado el documento &quot;' . $_REQUEST['deb_doc'] . '&quot;';
			$_REQUEST['deb_doc'] = '';
		}
	} else {
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' No cuenta con permiso de ingreso como otro usuario [Modulo ' . $iCodModulo . ' Permiso 1707]<br>';
		}
		$_REQUEST['deb_doc'] = '';
	}
	$bDebug = false;
} else {
	$_REQUEST['deb_doc'] = '';
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
// -- 240 unae24historialcambdoc
require 'lib240.php';
$xajax = new xajax();
$xajax->configure('javascript URI', $APP->rutacomun . 'xajax/');
$xajax->register(XAJAX_FUNCTION, 'unad11_Mostrar_v2');
$xajax->register(XAJAX_FUNCTION, 'unad11_TraerXid');
$xajax->register(XAJAX_FUNCTION, 'elimina_archivo_unae24idarchivo');
$xajax->register(XAJAX_FUNCTION, 'sesion_abandona_V2');
$xajax->register(XAJAX_FUNCTION, 'sesion_mantenerV4');
$xajax->register(XAJAX_FUNCTION, 'f240_HtmlTabla');
$xajax->register(XAJAX_FUNCTION, 'f240_ExisteDato');
$xajax->register(XAJAX_FUNCTION, 'f240_Busquedas');
$xajax->register(XAJAX_FUNCTION, 'f240_HtmlBusqueda');
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
if (isset($_REQUEST['paginaf240']) == 0) {
	$_REQUEST['paginaf240'] = 1;
}
if (isset($_REQUEST['lppf240']) == 0) {
	$_REQUEST['lppf240'] = 20;
}
if (isset($_REQUEST['boculta240']) == 0) {
	$_REQUEST['boculta240'] = 0;
}
// -- Inicializar variables de datos.
if (isset($_REQUEST['unae24idtercero']) == 0) {
	$_REQUEST['unae24idtercero'] = $_SESSION['unad_id_tercero'];
}
if (isset($_REQUEST['unae24idtercero_td']) == 0) {
	$_REQUEST['unae24idtercero_td'] = $APP->tipo_doc;
}
if (isset($_REQUEST['unae24idtercero_doc']) == 0) {
	$_REQUEST['unae24idtercero_doc'] = '';
}
if (isset($_REQUEST['unae24consec']) == 0) {
	$_REQUEST['unae24consec'] = '';
}
if (isset($_REQUEST['unae24consec_nuevo']) == 0) {
	$_REQUEST['unae24consec_nuevo'] = '';
}
if (isset($_REQUEST['unae24id']) == 0) {
	$_REQUEST['unae24id'] = '';
}
if (isset($_REQUEST['unae24tipodocorigen']) == 0) {
	$_REQUEST['unae24tipodocorigen'] = '';
}
if (isset($_REQUEST['unae24docorigen']) == 0) {
	$_REQUEST['unae24docorigen'] = '';
}
if (isset($_REQUEST['unae24tipodocdestino']) == 0) {
	$_REQUEST['unae24tipodocdestino'] = '';
}
if (isset($_REQUEST['unae24docdestino']) == 0) {
	$_REQUEST['unae24docdestino'] = '';
}
if (isset($_REQUEST['unae24idsolicita']) == 0) {
	$_REQUEST['unae24idsolicita'] = $_SESSION['unad_id_tercero'];
}
if (isset($_REQUEST['unae24idsolicita_td']) == 0) {
	$_REQUEST['unae24idsolicita_td'] = $APP->tipo_doc;
}
if (isset($_REQUEST['unae24idsolicita_doc']) == 0) {
	$_REQUEST['unae24idsolicita_doc'] = '';
}
if (isset($_REQUEST['unae24fechasol']) == 0) {
	$_REQUEST['unae24fechasol'] = 0;//{fecha_hoy();
}
if (isset($_REQUEST['unae24horasol']) == 0) {
	$_REQUEST['unae24horasol'] = fecha_hora();
}
if (isset($_REQUEST['unae24minsol']) == 0) {
	$_REQUEST['unae24minsol'] = fecha_minuto();
}
if (isset($_REQUEST['unae24idorigen']) == 0) {
	$_REQUEST['unae24idorigen'] = 0;
}
if (isset($_REQUEST['unae24idarchivo']) == 0) {
	$_REQUEST['unae24idarchivo'] = 0;
}
if (isset($_REQUEST['unae24estado']) == 0) {
	$_REQUEST['unae24estado'] = 0;
}
if (isset($_REQUEST['unae24detalle']) == 0) {
	$_REQUEST['unae24detalle'] = '';
}
if (isset($_REQUEST['unae24idaprueba']) == 0) {
	$_REQUEST['unae24idaprueba'] = 0;
}
if (isset($_REQUEST['unae24idaprueba_td']) == 0) {
	$_REQUEST['unae24idaprueba_td'] = $APP->tipo_doc;
}
if (isset($_REQUEST['unae24idaprueba_doc']) == 0) {
	$_REQUEST['unae24idaprueba_doc'] = '';
}
if (isset($_REQUEST['unae24fechaapr']) == 0) {
	$_REQUEST['unae24fechaapr'] = 0;//{fecha_hoy();
}
if (isset($_REQUEST['unae24horaaprueba']) == 0) {
	$_REQUEST['unae24horaaprueba'] = fecha_hora();
}
if (isset($_REQUEST['unae24minaprueba']) == 0) {
	$_REQUEST['unae24minaprueba'] = fecha_minuto();
}
if (isset($_REQUEST['unae24tiempod']) == 0) {
	$_REQUEST['unae24tiempod'] = '';
}
if (isset($_REQUEST['unae24tiempoh']) == 0) {
	$_REQUEST['unae24tiempoh'] = '';
}
// Espacio para inicializar otras variables
if (isset($_REQUEST['csv_separa']) == 0) {
	$_REQUEST['csv_separa'] = ';';
}
if (isset($_REQUEST['bnombre']) == 0) {
	$_REQUEST['bnombre'] = '';
}
/*
if (isset($_REQUEST['blistar']) == 0) {
	$_REQUEST['blistar'] = '';
}
*/
//Si Modifica o Elimina Cargar los campos
if (($_REQUEST['paso'] == 1) || ($_REQUEST['paso'] == 3)) {
	$_REQUEST['unae24idtercero_td'] = $APP->tipo_doc;
	$_REQUEST['unae24idtercero_doc'] = '';
	$_REQUEST['unae24idsolicita_td'] = $APP->tipo_doc;
	$_REQUEST['unae24idsolicita_doc'] = '';
	$_REQUEST['unae24idaprueba_td'] = $APP->tipo_doc;
	$_REQUEST['unae24idaprueba_doc'] = '';
	if ($_REQUEST['paso'] == 1) {
		$sSQLcondi = 'unae24idtercero="' . $_REQUEST['unae24idtercero'] . '" AND unae24consec=' . $_REQUEST['unae24consec'] . '';
	} else {
		$sSQLcondi = 'unae24id=' . $_REQUEST['unae24id'] . '';
	}
	$sSQL = 'SELECT * FROM unae24historialcambdoc WHERE ' . $sSQLcondi;
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$_REQUEST['unae24idtercero'] = $fila['unae24idtercero'];
		$_REQUEST['unae24consec'] = $fila['unae24consec'];
		$_REQUEST['unae24id'] = $fila['unae24id'];
		$_REQUEST['unae24tipodocorigen'] = $fila['unae24tipodocorigen'];
		$_REQUEST['unae24docorigen'] = $fila['unae24docorigen'];
		$_REQUEST['unae24tipodocdestino'] = $fila['unae24tipodocdestino'];
		$_REQUEST['unae24docdestino'] = $fila['unae24docdestino'];
		$_REQUEST['unae24idsolicita'] = $fila['unae24idsolicita'];
		$_REQUEST['unae24fechasol'] = $fila['unae24fechasol'];
		$_REQUEST['unae24horasol'] = $fila['unae24horasol'];
		$_REQUEST['unae24minsol'] = $fila['unae24minsol'];
		$_REQUEST['unae24idorigen'] = $fila['unae24idorigen'];
		$_REQUEST['unae24idarchivo'] = $fila['unae24idarchivo'];
		$_REQUEST['unae24estado'] = $fila['unae24estado'];
		$_REQUEST['unae24detalle'] = $fila['unae24detalle'];
		$_REQUEST['unae24idaprueba'] = $fila['unae24idaprueba'];
		$_REQUEST['unae24fechaapr'] = $fila['unae24fechaapr'];
		$_REQUEST['unae24horaaprueba'] = $fila['unae24horaaprueba'];
		$_REQUEST['unae24minaprueba'] = $fila['unae24minaprueba'];
		$_REQUEST['unae24tiempod'] = $fila['unae24tiempod'];
		$_REQUEST['unae24tiempoh'] = $fila['unae24tiempoh'];
		$bcargo = true;
		$_REQUEST['paso'] = 2;
		$_REQUEST['boculta240'] = 0;
		$bLimpiaHijos = true;
	} else {
		$_REQUEST['paso'] = 0;
	}
}
//Cerrar
$bCerrando = false;
if ($_REQUEST['paso'] == 16) {
	$_REQUEST['paso'] = 12;
	$_REQUEST['unae24estado'] = 7;
	$bCerrando = true;
}
//Abrir
if ($_REQUEST['paso'] == 17) {
	$_REQUEST['paso'] = 2;
	//Es posible que deba definir el codigo de permiso para abrir.
	$bDevuelve = true;
	// list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 17, $idTercero, $objDB);
	if (!$bDevuelve) {
		$sError = $ERR['3'];
	}
	//Otras restricciones para abrir.
	if ($sError == '') {
		//$sError = 'Motivo por el que no se pueda abrir, no se permite modificar.';
	}
	if ($sError != '') {
		//$_REQUEST['unae24estado'] = 7;
	} else {
		$sSQL = 'UPDATE unae24historialcambdoc SET unae24estado = 0 WHERE unae24id=' . $_REQUEST['unae24id'] . '';
		$tabla = $objDB->ejecutasql($sSQL);
		seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 3, $_REQUEST['unae24id'], 'Abre Solicitud de cambio de documento', $objDB);
		$_REQUEST['unae24estado'] = 0;
		$sError = '<b>' . $ETI['msg_itemabierto'] . '</b>';
		$iTipoError = 1;
	}
}
//Insertar o modificar un elemento
if (($_REQUEST['paso'] == 10) || ($_REQUEST['paso'] == 12)) {
	$bMueveScroll = true;
	list($_REQUEST, $sError, $iTipoError, $bCerrando, $sErrorCerrando, $sDebugGuardar) = f240_db_GuardarV2($_REQUEST, $objDB, $bDebug, $idTercero);
	$sDebug = $sDebug . $sDebugGuardar;
	if ($sError == '') {
		$sError = '<b>' . $ETI['msg_itemguardado'] . '</b>';
		$iTipoError = 1;
		if ($sErrorCerrando != '') {
			$iTipoError = 0;
			$sError = '<b>' . $ETI['msg_itemguardado'] . '</b><br>' . $sErrorCerrando;
		}
		if ($bCerrando) {
			$sError = '<b>' . $ETI['msg_itemcerrado'] . '</b>';
		}
	}
}
if ($bCerrando) {
	//acciones del cerrado
}
// Cambio de consecutivo.
if ($_REQUEST['paso'] == 93) {
	$_REQUEST['paso'] = 2;
	$_REQUEST['unae24consec_nuevo'] = numeros_validar($_REQUEST['unae24consec_nuevo']);
	if ($_REQUEST['unae24consec_nuevo'] == '') {
		$sError = $ERR['unae24consec'];
	}
	if ($sError == '') {
		$bDevuelve = true;
		// list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 8, $idTercero, $objDB);
		if (!$bDevuelve) {
			$sError = $ERR['8'];
		}
	}
	if ($sError == '') {
		//Ver que el consecutivo no exista.
		$sSQL = 'SELECT unae24id FROM unae24historialcambdoc WHERE unae24consec=' . $_REQUEST['unae24consec_nuevo'] . ' AND unae24idtercero=' . $_REQUEST['unae24idtercero'] . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$sError = 'El consecutivo ' . $_REQUEST['unae24consec_nuevo'] . ' ya existe';
		}
	}
	if ($sError == '') {
		//Aplicar el cambio.
		$sSQL = 'UPDATE unae24historialcambdoc SET unae24consec=' . $_REQUEST['unae24consec_nuevo'] . ' WHERE unae24id=' . $_REQUEST['unae24id'] . '';
		$tabla = $objDB->ejecutasql($sSQL);
		$sDetalle = 'Cambia el consecutivo de ' . $_REQUEST['unae24consec'] . ' a ' . $_REQUEST['unae24consec_nuevo'] . '';
		$_REQUEST['unae24consec'] = $_REQUEST['unae24consec_nuevo'];
		$_REQUEST['unae24consec_nuevo'] = '';
		seg_auditar($iCodModulo, $_SESSION['u_idtercero'], 8, $_REQUEST['unae24id'], $sDetalle, $objDB);
		$sError = '<b>Se ha aplicado el cambio de consecutivo.</b>';
		$iTipoError = 1;
	} else {
		$iSector = 93;
	}
}
//Eliminar un elemento
if ($_REQUEST['paso'] == 13) {
	$_REQUEST['paso'] = 2;
	list($sError, $iTipoError, $sDebugElimina) = f240_db_Eliminar($_REQUEST['unae24id'], $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugElimina;
	if ($sError == '') {
		$_REQUEST['paso'] = -1;
		$sError = $ETI['msg_itemeliminado'];
		$iTipoError = 1;
	}
}
//limpiar la pantalla
if ($_REQUEST['paso'] == -1) {
	$_REQUEST['unae24idtercero'] = $idTercero;
	$_REQUEST['unae24idtercero_td'] = $APP->tipo_doc;
	$_REQUEST['unae24idtercero_doc'] = '';
	$_REQUEST['unae24consec'] = '';
	$_REQUEST['unae24consec_nuevo'] = '';
	$_REQUEST['unae24id'] = '';
	$_REQUEST['unae24tipodocorigen'] = '';
	$_REQUEST['unae24docorigen'] = '';
	$_REQUEST['unae24tipodocdestino'] = '';
	$_REQUEST['unae24docdestino'] = '';
	$_REQUEST['unae24idsolicita'] = $idTercero;
	$_REQUEST['unae24idsolicita_td'] = $APP->tipo_doc;
	$_REQUEST['unae24idsolicita_doc'] = '';
	$_REQUEST['unae24fechasol'] = 0; //fecha_hoy();
	$_REQUEST['unae24horasol'] = fecha_hora();
	$_REQUEST['unae24minsol'] = fecha_minuto();
	$_REQUEST['unae24idorigen'] = 0;
	$_REQUEST['unae24idarchivo'] = 0;
	$_REQUEST['unae24estado'] = 0;
	$_REQUEST['unae24detalle'] = '';
	$_REQUEST['unae24idaprueba'] = 0;
	$_REQUEST['unae24idaprueba_td'] = $APP->tipo_doc;
	$_REQUEST['unae24idaprueba_doc'] = '';
	$_REQUEST['unae24fechaapr'] = 0; //fecha_hoy();
	$_REQUEST['unae24horaaprueba'] = fecha_hora();
	$_REQUEST['unae24minaprueba'] = fecha_minuto();
	$_REQUEST['unae24tiempod'] = '';
	$_REQUEST['unae24tiempoh'] = '';
	$_REQUEST['paso'] = 0;
}
if ($bLimpiaHijos) {
}
//AQUI SE DEBEN CARGAR TODOS LOS DATOS QUE LA FORMA NECESITE.
//DATOS PARA COMPLETAR EL FORMULARIO
$iAgnoIni = 2000;
$iAgnoFin = fecha_agno() + 5;
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
list($unae24idtercero_rs, $_REQUEST['unae24idtercero'], $_REQUEST['unae24idtercero_td'], $_REQUEST['unae24idtercero_doc']) = html_tercero($_REQUEST['unae24idtercero_td'], $_REQUEST['unae24idtercero_doc'], $_REQUEST['unae24idtercero'], 0, $objDB);
$objCombos->nuevo('unae24tipodocdestino', $_REQUEST['unae24tipodocdestino'], true, '{' . $ETI['msg_seleccione'] . '}');
//$objCombos->addArreglo($aunae24tipodocdestino, $iunae24tipodocdestino);
$html_unae24tipodocdestino = $objCombos->html('', $objDB);
list($unae24idsolicita_rs, $_REQUEST['unae24idsolicita'], $_REQUEST['unae24idsolicita_td'], $_REQUEST['unae24idsolicita_doc']) = html_tercero($_REQUEST['unae24idsolicita_td'], $_REQUEST['unae24idsolicita_doc'], $_REQUEST['unae24idsolicita'], 0, $objDB);
list($unae24idaprueba_rs, $_REQUEST['unae24idaprueba'], $_REQUEST['unae24idaprueba_td'], $_REQUEST['unae24idaprueba_doc']) = html_tercero($_REQUEST['unae24idaprueba_td'], $_REQUEST['unae24idaprueba_doc'], $_REQUEST['unae24idaprueba'], 0, $objDB);
if ((int)$_REQUEST['paso'] == 0) {
} else {
}
//Alistar datos adicionales
$bPuedeAbrir = false;
if ($_REQUEST['paso'] != 0) {
	if ($_REQUEST['unae24estado'] == 7) {
		//Definir las condiciones que permitirán abrir el registro.
		$bDevuelve = true;
		// list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 17, $idTercero, $objDB);
		if ($bDevuelve) {
			$bPuedeAbrir = true;
		}
	}
}
$id_rpt = 0;
//$id_rpt=reportes_id(_Identificador_Tipo_Reporte_, $objDB);
/*
$objCombos->nuevo('blistar', $_REQUEST['blistar'], true, '{' . $ETI['msg_todos'] . '}');
$objCombos->sAccion = 'paginarf240()';
$html_blistar = $objCombos->html('', $objDB);
//$html_blistar = $objCombos->comboSistema(240, 1, $objDB, 'paginarf240()');
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
$iModeloReporte = 240;
$html_iFormatoImprime = '<input id="iformatoimprime" name="iformatoimprime" type="hidden" value="0" />
';
if ($_REQUEST['paso'] > 0) {
	$bDevuelve = false;
	//list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 5, $idTercero, $objDB);
	if ($bDevuelve) {
		$seg_5 = 1;
	}
	if ($_REQUEST['unae24estado'] == 0) {
		$bDevuelve = false;
		//list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 8, $idTercero, $objDB);
		if ($bDevuelve) {
			$seg_8 = 1;
		}
	}
}
//Cargar las tablas de datos
$aParametros[0] = ''; //$_REQUEST['p1_240'];
$aParametros[100] = $idTercero;
$aParametros[101] = $_REQUEST['paginaf240'];
$aParametros[102] = $_REQUEST['lppf240'];
//$aParametros[103] = $_REQUEST['bnombre'];
//$aParametros[104] = $_REQUEST['blistar'];
list($sTabla240, $sDebugTabla) = f240_TablaDetalleV2($aParametros, $objDB, $bDebug);
$sDebug = $sDebug . $sDebugTabla;
$bDebugMenu = false;
list($et_menu, $sDebugM) = html_menuV2($APP->idsistema, $objDB, $iPiel, $bDebugMenu, $idTercero);
$sDebug = $sDebug . $sDebugM;
$objDB->CerrarConexion();
//FORMA
require $APP->rutacomun . 'unad_forma_v2.php';
forma_cabeceraV3($xajax, $ETI['titulo_240']);
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
		if (window.document.frmedita.unae24estado.value == 0) {
			var sEst = 'none';
			if (codigo == 1) {
				sEst = 'block';
			}
			// document.getElementById('cmdGuardarf').style.display = sEst;
			}
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
		var params = new Array();
		params[0] = vrcampo;
		params[1] = idcampo;
		if (params[0] != 0) {
			if (idcampo == 'unae24idtercero') {
				params[4] = 'RevisaLlave';
			}
			xajax_unad11_TraerXid(params);
		}
	}

	function imprimelista() {
		if (window.document.frmedita.seg_6.value == 1) {
			window.document.frmlista.consulta.value = window.document.frmedita.consulta_240.value;
			window.document.frmlista.titulos.value = window.document.frmedita.titulos_240.value;
			window.document.frmlista.nombrearchivo.value = 'Solicitud de cambio de documento';
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
			window.document.frmimpp.action = 'e240.php';
			window.document.frmimpp.submit();
		} else {
			ModalMensaje(sError);
		}
	}

	function imprimep() {
		if (window.document.frmedita.seg_5.value == 1) {
			asignarvariables();
			window.document.frmimpp.action = 'p240.php';
			window.document.frmimpp.submit();
<?php
if ($iNumFormatosImprime>0) {
?>
			expandesector(1);
<?php
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
		datos[1] = window.document.frmedita.unae24idtercero.value;
		datos[2] = window.document.frmedita.unae24consec.value;
		if ((datos[1] != '') && (datos[2] != '')) {
			xajax_f240_ExisteDato(datos);
		}
	}

	function cargadato(llave1, llave2) {
		window.document.frmedita.unae24idtercero.value = String(llave1);
		window.document.frmedita.unae24consec.value = String(llave2);
		window.document.frmedita.paso.value = 1;
		window.document.frmedita.submit();
	}

	function cargaridf240(llave1) {
		window.document.frmedita.unae24id.value = String(llave1);
		window.document.frmedita.paso.value = 3;
		window.document.frmedita.submit();
	}

	function limpia_unae24idarchivo() {
		window.document.frmedita.unae24idorigen.value = 0;
		window.document.frmedita.unae24idarchivo.value = 0;
		var da_Archivo = document.getElementById('div_unae24idarchivo');
		da_Archivo.innerHTML = '&nbsp;';
		verboton('beliminaunae24idarchivo', 'none');
		//paginarf240();
	}

	function carga_unae24idarchivo() {
		window.document.frmedita.iscroll.value = window.pageYOffset;
		window.document.frmedita.div96v1.value = '';
		window.document.frmedita.div96v2.value = '';
		window.document.frmedita.div96v3.value = '';
		document.getElementById('div_96titulo').innerHTML = '<h2>' + window.document.frmedita.titulo_240.value + ' - Cargar archivo</h2>';
		document.getElementById('div_96cuerpo').innerHTML = '<iframe id="iframe96" src="framearchivo.php?ref=240&id=' + window.document.frmedita.unae24id.value+'" height="400px" width="100%" frameborder="0"></iframe>';
		expandesector(96);
		window.scrollTo(0, 150);
	}

	function eliminaunae24idarchivo() {
		var did = window.document.frmedita.unae24id;
		ModalConfirm('&iquest;<?php echo $ETI['eliminar_unae24idarchivo']; ?>?');
		ModalDialogConfirm(function(confirm) {if (confirm) {
			xajax_elimina_archivo_unae24idarchivo(did.value);
			//paginarf240();
			}});
	}

	function paginarf240() {
		var params = new Array();
		params[99] = window.document.frmedita.debug.value;
		params[100] = <?php echo $idTercero; ?>;
		params[101] = window.document.frmedita.paginaf240.value;
		params[102] = window.document.frmedita.lppf240.value;
		//params[103] = window.document.frmedita.bnombre.value;
		//params[104] = window.document.frmedita.blistar.value;
		//document.getElementById('div_f240detalle').innerHTML = '<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf240" name="paginaf240" type="hidden" value="' + params[101] + '" /><input id="lppf240" name="lppf240" type="hidden" value="' + params[102] + '" />';
		xajax_f240_HtmlTabla(params);
	}

	function enviacerrar() {
		ModalConfirm('<?php echo $ETI['msg_cierre240']; ?>');
		ModalDialogConfirm(function(confirm) {if (confirm) {ejecuta_enviacerrar();}});
	}

	function ejecuta_enviacerrar() {
		MensajeAlarmaV2('<?php echo $ETI['msg_ejecutando']; ?>', 2);
		expandesector(98);
		window.document.frmedita.paso.value = 16;
		window.document.frmedita.submit();
	}

	function enviaabrir() {
		ModalConfirm('<?php echo $ETI['msg_confirmaabrir']; ?>');
		ModalDialogConfirm(function(confirm) {if (confirm) {ejecuta_enviaabrir();}});
	}

	function ejecuta_enviaabrir() {
		MensajeAlarmaV2('<?php echo $ETI['msg_ejecutando']; ?>', 2);
		expandesector(98);
		window.document.frmedita.paso.value = 17;
		window.document.frmedita.submit();
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
		document.getElementById("unae24idtercero").focus();
	}

	function buscarV2016(sCampo) {
		window.document.frmedita.iscroll.value = window.pageYOffset;
		expandesector(98);
		window.document.frmedita.scampobusca.value = sCampo;
		var params = new Array();
		params[1] = sCampo;
		//params[2] = window.document.frmedita.iagno.value;
		//params[3] = window.document.frmedita.itipo.value;
		xajax_f240_Busquedas(params);
	}

	function retornacontrol() {
		expandesector(1);
		window.scrollTo(0, window.document.frmedita.iscroll.value);
	}

	function Devuelve(sValor) {
		var sCampo = window.document.frmedita.scampobusca.value;
		if (sCampo == 'unae24idtercero') {
			ter_traerxid('unae24idtercero', sValor);
		}
		if (sCampo == 'unae24idsolicita') {
			ter_traerxid('unae24idsolicita', sValor);
		}
		if (sCampo == 'unae24idaprueba') {
			ter_traerxid('unae24idaprueba', sValor);
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
	if (ref==0) {
		if (sRetorna != '') {
			window.document.frmedita.unae24idorigen.value = window.document.frmedita.div96v1.value;
			window.document.frmedita.unae24idarchivo.value = sRetorna;
			verboton('beliminaunae24idarchivo', 'block');
		}
		archivo_lnk(window.document.frmedita.unae24idorigen.value, window.document.frmedita.unae24idarchivo.value, 'div_unae24idarchivo');
		paginarf0();
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
</script>
<?php
if ($_REQUEST['paso'] != 0) {
?>
<form id="frmimpp" name="frmimpp" method="post" action="p240.php" target="_blank">
<input id="r" name="r" type="hidden" value="240" />
<input id="id240" name="id240" type="hidden" value="<?php echo $_REQUEST['unae24id']; ?>" />
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
<input id="cmdLimpiar" name="cmdLimpiar" type="button" class="btUpLimpiar" onclick="limpiapagina();" title="<?php echo $ETI['bt_limpiar']; ?>" value="<?php echo $ETI['bt_limpiar']; ?>" />
</div>
<div class="titulosI">
<?php
echo '<h2>' . $ETI['titulo_240'] . '</h2>';
?>
</div>
</div>
<div class="areaform">
<div class="areatrabajo">
<?php
//Div para ocultar
$bConExpande = false;
if ($bConExpande) {
?>
<div class="ir_derecha"<?php echo $sAnchoExpandeContrae; ?>>
<?php
$sEstiloExpande = ' style="display:none;"';
$sEstiloRecoje = '';
$sEstiloDiv = '';
if ($_REQUEST['boculta240'] != 0) {
	$sEstiloExpande = '';
	$sEstiloRecoje = ' style="display:none;"';
	$sEstiloDiv = ' style="display:none;"';
}
?>
<input id="boculta240" name="boculta240" type="hidden" value="<?php echo $_REQUEST['boculta240']; ?>" />
<label class="Label30">
<input id="btexpande240" name="btexpande240" type="button" value="Mostrar" class="btMiniExpandir" onclick="expandepanel(240, 'block',0);" title="<?php echo $ETI['bt_mostrar']; ?>"<?php echo $sEstiloExpande; ?>/>
</label>
<label class="Label30">
<input id="btrecoge240" name="btrecoge240" type="button" value="Ocultar" class="btMiniRecoger" onclick="expandepanel(240, 'none',1);" title="<?php echo $ETI['bt_ocultar']; ?>" <?php echo $sEstiloRecoje; ?>/>
</label>
</div>
<div id="div_p240"<?php echo $sEstiloDiv; ?>>
<?php
}
//Mostrar formulario para editar
?>
<?php
if (false) {
?>
<div class="salto1px"></div>
<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo $ETI['unae24idtercero'];
?>
</label>
<div class="salto1px"></div>
<input id="unae24idtercero" name="unae24idtercero" type="hidden" value="<?php echo $_REQUEST['unae24idtercero']; ?>" />
<div id="div_unae24idtercero_llaves">
<?php
$bOculto = true;
echo html_DivTerceroV2('unae24idtercero', $_REQUEST['unae24idtercero_td'], $_REQUEST['unae24idtercero_doc'], $bOculto, 1, $ETI['ing_doc']);
?>
</div>
<div class="salto1px"></div>
<div id="div_unae24idtercero" class="L"><?php echo $unae24idtercero_rs; ?></div>
<div class="salto1px"></div>
</div>
<?php
} else {
?>
<input id="unae24idtercero" name="unae24idtercero" type="hidden" value="<?php echo $_REQUEST['unae24idtercero']; ?>" />
<?php
}
?>
<?php
if (false) {
?>
<label class="Label130">
<?php
echo $ETI['unae24fechasol'];
?>
</label>
<label class="Label220">
<div id="div_unae24fechasol">
<?php
echo html_oculto('unae24fechasol', $_REQUEST['unae24fechasol'], formato_FechaLargaDesdeNumero($_REQUEST['unae24fechasol'])); //formato_FechaLargaDesdeNumero
?>
</div>
</label>
<label class="Label130">
<?php
echo $ETI['unae24horasol'];
?>
</label>
<div class="campo_HoraMin" id="div_unae24horasol">
<?php
echo html_HoraMin('unae24horasol', $_REQUEST['unae24horasol'], 'unae24minsol', $_REQUEST['unae24minsol'], true);
?>
</div>
<div class="salto1px"></div>
<?php
} else {
?>
<input id="unae24fechasol" name="unae24fechasol" type="hidden" value="<?php echo $_REQUEST['unae24fechasol']; ?>" />
<input id="unae24horasol" name="unae24horasol" type="hidden" value="<?php echo $_REQUEST['unae24horasol']; ?>" />
<input id="unae24minsol" name="unae24minsol" type="hidden" value="<?php echo $_REQUEST['unae24minsol']; ?>" />
<?php
}
?>
<label class="Label130">
<?php
echo $ETI['unae24consec'];
?>
</label>
<label class="Label130">
<?php
if (false) {
?>
<input id="unae24consec" name="unae24consec" type="text" value="<?php echo $_REQUEST['unae24consec']; ?>" onchange="RevisaLlave()" class="cuatro" />
<?php
} else {
	echo html_oculto('unae24consec', $_REQUEST['unae24consec'], formato_numero($_REQUEST['unae24consec']));
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
echo $ETI['unae24id'];
?>
</label>
<label class="Label60">
<?php
	echo html_oculto('unae24id', $_REQUEST['unae24id'], formato_numero($_REQUEST['unae24id']));
?>
</label>
<label class="Label60">
<?php
echo $ETI['unae24estado'];
?>
</label>
<label class="Label90">
<?php
$et_unae24estado = $aunae24estado[$_REQUEST['unae24estado']];
if ($_REQUEST['unae24estado'] == 7) {
	// $et_unae24estado = $ETI['msg_cerrado'];
}
echo html_oculto('unae24estado', $_REQUEST['unae24estado'], $et_unae24estado);
?>
</label>
<?php
if (false) {
?>
<label class="Label130">
<?php
echo $ETI['unae24tipodocorigen'];
?>
</label>
<label><div id="div_unae24tipodocorigen">
<?php
echo html_oculto('unae24tipodocorigen', $_REQUEST['unae24tipodocorigen']);
?>
</div></label>
<label class="Label130">
<?php
echo $ETI['unae24docorigen'];
?>
</label>
<label><div id="div_unae24docorigen">
<?php
echo html_oculto('unae24docorigen', $_REQUEST['unae24docorigen']);
?>
</div></label>
<?php
} else {
?>
<input id="unae24tipodocorigen" name="unae24tipodocorigen" type="hidden" value="<?php echo $_REQUEST['unae24tipodocorigen']; ?>" />
<input id="unae24docorigen" name="unae24docorigen" type="hidden" value="<?php echo $_REQUEST['unae24docorigen']; ?>" />
<?php
}
?>


<div class="salto1px"></div>
<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo $ETI['unae24idsolicita'];
?>
</label>
<div class="salto1px"></div>
<input id="unae24idsolicita" name="unae24idsolicita" type="hidden" value="<?php echo $_REQUEST['unae24idsolicita']; ?>" />
<div id="div_unae24idsolicita_llaves">
<?php
$bOculto = true;
echo html_DivTerceroV2('unae24idsolicita', $_REQUEST['unae24idsolicita_td'], $_REQUEST['unae24idsolicita_doc'], $bOculto, 0, $ETI['ing_doc']);
?>
</div>
<div class="salto1px"></div>
<div id="div_unae24idsolicita" class="L"><?php echo $unae24idsolicita_rs; ?></div>
<div class="salto1px"></div>
</div>
<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo $ETI['unae24destino'];
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['unae24tipodocdestino'];
?>
</label>
<label>
<?php
echo html_tipodocV2('unae24tipodocdestino',$_REQUEST['unae24tipodocdestino']);
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['unae24docdestino'];
?>
</label>
<label>
<input id="unae24docdestino" name="unae24docdestino" type="text" value="<?php echo $_REQUEST['unae24docdestino']; ?>" maxlength="20" placeholder="<?php echo $ETI['ing_campo'] . $ETI['unae24docdestino']; ?>" />
</label>
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
<input id="unae24idorigen" name="unae24idorigen" type="hidden" value="<?php echo $_REQUEST['unae24idorigen']; ?>" />
<input id="unae24idarchivo" name="unae24idarchivo" type="hidden" value="<?php echo $_REQUEST['unae24idarchivo']; ?>" />
<div class="GrupoCampos300">
<div class="salto1px"></div>
<label class="TituloGrupo">
<?php
echo $ETI['unae24archivo']
?>
</label>
<div class="salto1px"></div>
<div id="div_unae24idarchivo" class="Campo300">
<?php
echo html_lnkarchivo((int)$_REQUEST['unae24idorigen'], (int)$_REQUEST['unae24idarchivo']);
?>
</div>
<?php
$sEstiloAnexa = ' style="display:block;"';
$sEstiloElimina = ' style="display:none;"';
$sEstiloAyudaArchivo = ' style="display:none;"';
if ((int)$_REQUEST['unae24id'] == 0) {
	$sEstiloAnexa = ' style="display:none;"';
} else if ($_REQUEST['unae24estado'] == 0 && (int)$_REQUEST['unae24idarchivo'] == 0) {
	$sEstiloAyudaArchivo = ' style="display:block;"';
}
if ((int)$_REQUEST['unae24idarchivo'] != 0) {
	$sEstiloElimina = ' style="inline-block;"';
}
?>
<label class="Label30">
<input id="banexaunae24idarchivo" name="banexaunae24idarchivo" type="button" value="Anexar" class="btAnexarS" onclick="carga_unae24idarchivo()" title="Cargar archivo"<?php echo $sEstiloAnexa; ?>/>
</label>
<label class="Label30">
<input id="beliminaunae24idarchivo" name="beliminaunae24idarchivo" type="button" value="Eliminar" class="btBorrarS" onclick="eliminaunae24idarchivo()" title="Eliminar archivo"<?php echo $sEstiloElimina; ?>/>
</label>
<div class="salto1px"></div>
<div id='div_ayuda_unae24idarchivo' class='GrupoCamposAyuda'<?php echo $sEstiloAyudaArchivo; ?>>
<?php
echo $ETI['ayuda_unae24idarchivo'];
?>
</div>
<div class="salto1px"></div>
</div>
<label class="txtAreaS">
<?php
echo $ETI['unae24detalle'];
?>
<textarea id="unae24detalle" name="unae24detalle" placeholder="<?php echo $ETI['ing_campo'] . $ETI['unae24detalle']; ?>"><?php echo $_REQUEST['unae24detalle']; ?></textarea>
</label>
<?php
if (false) {
?>
<div class="salto1px"></div>
<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo $ETI['unae24idaprueba'];
?>
</label>
<div class="salto1px"></div>
<input id="unae24idaprueba" name="unae24idaprueba" type="hidden" value="<?php echo $_REQUEST['unae24idaprueba']; ?>" />
<div id="div_unae24idaprueba_llaves">
<?php
$bOculto = true;
echo html_DivTerceroV2('unae24idaprueba', $_REQUEST['unae24idaprueba_td'], $_REQUEST['unae24idaprueba_doc'], $bOculto, 0, $ETI['ing_doc']);
?>
</div>
<div class="salto1px"></div>
<div id="div_unae24idaprueba" class="L"><?php echo $unae24idaprueba_rs; ?></div>
<div class="salto1px"></div>
</div>
<label class="Label130">
<?php
echo $ETI['unae24fechaapr'];
?>
</label>
<label class="Label220">
<div id="div_unae24fechaapr">
<?php
echo html_oculto('unae24fechaapr', $_REQUEST['unae24fechaapr'], fecha_desdenumero($_REQUEST['unae24fechaapr'])); //formato_FechaLargaDesdeNumero
?>
</div>
</label>
<label class="Label130">
<?php
echo $ETI['unae24horaaprueba'];
?>
</label>
<div class="campo_HoraMin" id="div_unae24horaaprueba">
<?php
echo html_HoraMin('unae24horaaprueba', $_REQUEST['unae24horaaprueba'], 'unae24minaprueba', $_REQUEST['unae24minaprueba'], true);
?>
</div>
<?php
} else {
?>
<input id="unae24idaprueba" name="unae24idaprueba" type="hidden" value="<?php echo $_REQUEST['unae24idaprueba']; ?>" />
<input id="unae24fechaapr" name="unae24fechaapr" type="hidden" value="<?php echo $_REQUEST['unae24fechaapr']; ?>" />
<input id="unae24horaaprueba" name="unae24horaaprueba" type="hidden" value="<?php echo $_REQUEST['unae24horaaprueba']; ?>" />
<?php
}
?>
<input id="unae24tiempod" name="unae24tiempod" type="hidden" value="<?php echo $_REQUEST['unae24tiempod']; ?>" />
<input id="unae24tiempoh" name="unae24tiempoh" type="hidden" value="<?php echo $_REQUEST['unae24tiempoh']; ?>" />
<div class="salto5px"></div>
<label class="Label300">&nbsp;</label>
<label class="Label130">
<input id="cmdGuardar" name="cmdGuardar" type="button" value="Guardar" class="BotonAzul" onclick="javascript:enviaguardar()" />
</label>
<label class="Label60">&nbsp;</label>
<label class="Label160">
<input id="cmdEnviar" name="cmdEnviar" type="button" value="Enviar Solicitud" class="BotonAzul160" onclick="javascript:enviacerrar()" />
</label>
<?php
if (false) {
	//Ejemplo de boton de ayuda
	//echo html_BotonAyuda('NombreCampo');
	//echo html_DivAyudaLocal('NombreCampo');
}
if ($bConExpande) {
	//Este es el cierre del div_p240
?>
<div class="salto1px"></div>
</div>
<?php
}
//Mostrar el contenido de la tabla
?>
</div><!-- CIERRA EL DIV areatrabajo -->
</div><!-- CIERRA EL DIV areaform -->
<div class="areaform">
<div class="areatitulo">
<?php
echo '<h3>' . $ETI['bloquehistorial'] . '</h3>';
?>
</div>
<div class="areatrabajo">
<?php
if (false) {
?>
<div class="ir_derecha">
<label class="Label90">
<?php
echo $ETI['msg_bnombre'];
?>
</label>
<label>
<input id="bnombre" name="bnombre" type="text" value="<?php echo $_REQUEST['bnombre']; ?>" onchange="paginarf240()" autocomplete="off" />
</label>
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
</div>
<div class="salto1px"></div>
<?php
}
?>
<?php
echo ' ' . $csv_separa;
?>
<div id="div_f240detalle">
<?php
echo $sTabla240;
?>
</div>
</div><!-- /div_areatrabajo -->
</div><!-- /DIV_areaform -->
</div><!-- /DIV_Sector1 -->


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
</div><!-- /div_area -->
</div><!-- /DIV_cargaForm -->
</div><!-- /DIV_Sector2 -->


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
echo $ETI['msg_unae24consec'];
?>
</label>
<label class="Label90">
<?php
echo '<b>' . $_REQUEST['unae24consec'] . '</b>';
?>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['msg_unae24consec_nuevo'];
// onchange="RevisaConsec()"
?>
</label>
<label class="Label90">
<input id="unae24consec_nuevo" name="unae24consec_nuevo" type="text" value="<?php echo $_REQUEST['unae24consec_nuevo']; ?>" class="cuatro" />
</label>
<div class="salto1px"></div>
<label class="Label160">&nbsp;</label>
<?php
echo $objForma->htmlBotonSolo('cmdCambiaConsecFinal', 'botonProceso', 'mod_consec();', $ETI['bt_cambiar'], 130);
echo $objForma->htmlFinMarco();
?>
</div><!-- /DIV_Sector93 -->


<div id="div_sector95" style="display:none">
<div id="cargaForm">
<div id="div_95cuerpo"></div>
</div><!-- /DIV_cargaForm -->
</div><!-- /DIV_Sector95 -->


<div id="div_sector96" style="display:none">
<input id="div96v1" name="div96v1" type="hidden" value="" />
<input id="div96v2" name="div96v2" type="hidden" value="" />
<input id="div96v3" name="div96v3" type="hidden" value="" />
<input id="div96campo" name="div96campo" type="hidden" value="" />
<input id="div96llave" name="div96llave" type="hidden" value="" />
<input id="titulo_240" name="titulo_240" type="hidden" value="<?php echo $ETI['titulo_240']; ?>" />
<div class="titulos">
<div class="titulosD">
<input id="cmdAyuda96" name="cmdAyuda96" type="button" class="btSupAyuda" onclick="muestraayuda(<?php echo $iCodModulo; ?>);" title="<?php echo $ETI['bt_ayuda']; ?>" value="<?php echo $ETI['bt_ayuda']; ?>" />
</div>
<div class="titulosI" id="div_96titulo"></div>
</div>
<div id="cargaForm">
<div id="div_96cuerpo"></div>
</div><!-- /DIV_cargaForm -->
</div><!-- /DIV_Sector96 -->


<div id="div_sector97" style="display:none">
<div class="titulos">
<div class="titulosD">
<input id="cmdAyuda97" name="cmdAyuda97" type="button" class="btSupAyuda" onclick="muestraayuda(<?php echo $iCodModulo; ?>);" title="<?php echo $ETI['bt_ayuda']; ?>" value="<?php echo $ETI['bt_ayuda']; ?>" />
<input id="cmdVolverSec97" name="cmdVolverSec97" type="button" class="btSupVolver" onclick="retornacontrol();" title="<?php echo $ETI['bt_volver']; ?>" value="<?php echo $ETI['bt_volver']; ?>" />
</div>
<div class="titulosI" id="div_97titulo">
<?php
echo '<h2>' . $ETI['titulo_240'] . '</h2>';
?>
</div>
</div>
<div id="areaform">
<div id="areatrabajo">
<div id="div_97params"></div>
<div class="salto1px"></div>
<div id="div_97tabla"></div>
</div><!-- /DIV_areatrabajo -->
</div><!-- /DIV_areaform -->
</div><!-- /DIV_Sector97 -->


<div id="div_sector98" style="display:none">
<div class="titulos">
<div class="titulosD">
<input id="cmdAyuda98" name="cmdAyuda98" type="button" class="btSupAyuda" onclick="muestraayuda(<?php echo $iCodModulo; ?>);" title="<?php echo $ETI['bt_ayuda']; ?>" value="<?php echo $ETI['bt_ayuda']; ?>" />
</div>
<div class="titulosI">
<?php
echo '<h2>' . $ETI['titulo_240'] . '</h2>';
?>
</div>
</div>
<div id="cargaForm">
<div id="area">
<div class="MarquesinaMedia">
<?php
echo $ETI['msg_espere'];
?>
</div><!-- /Termina la marquesina -->
</div><!-- /div_area -->
</div><!-- /DIV_cargaForm -->
</div><!-- /DIV_Sector98 -->


<?php
if ($sDebug != '') {
	$iSegFin = microtime(true);
	$iSegundos = $iSegFin-$iSegIni;
	echo '<div class="salto1px"></div><div class="GrupoCampos" id="div_debug">' . $sDebug . fecha_microtiempo() . ' Tiempo total del proceso: <b>' . $iSegundos . '</b> Segundos' . '<div class="salto1px"></div></div>';
}
?>
<input id="scampobusca" name="scampobusca" type="hidden" value="" />
<input id="iscroll" name="iscroll" type="hidden" value="<?php echo $_REQUEST['iscroll']; ?>" />
<input id="itipoerror" name="itipoerror" type="hidden" value="<?php echo $iTipoError; ?>" />
<input id="debug" name="debug" type="hidden" value="<?php echo $_REQUEST['debug']; ?>" />
</form>
</div><!-- /DIV_interna -->
<?php
if (false) {
?>
<div class="flotante">
<?php
if ($_REQUEST['unae24estado'] == 0) {
?>
<input id="cmdGuardarf" name="cmdGuardarf" type="button" class="btSoloGuardar" onClick="enviaguardar();" value="<?php echo $ETI['bt_guardar']; ?>" />
<?php
}
?>
</div>
<?php
}
?>
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
if ($_REQUEST['paso'] == 0) {
?>
<script language="javascript">
$().ready(function() {
//$("#bperiodo").chosen();
});
</script>
<?php
}
?>
<script language="javascript" src="ac_240.js"></script>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>unad_todas.js?ver=8"></script>
<?php
forma_piedepagina();
?>