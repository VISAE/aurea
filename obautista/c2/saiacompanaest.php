<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2021 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.25.10b lunes, 25 de enero de 2021
--- Modelo Versión 2.25.10c martes, 9 de febrero de 2021
--- Modelo Versión 2.25.10c lunes, 15 de febrero de 2021
--- Modelo Versión 2.25.10c miércoles, 24 de febrero de 2021
*/

/** Archivo saiacompanaest.php.
 * Modulo 3041 saiu41docente.
 * @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
 * @param debug=1 (Opcional), bandera para indicar si se generan datos de depuración
 * @date lunes, 25 de enero de 2021
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
	$sDebug = $sDebug . '' . date('H:i:s') . $sMili . ' Inicia pagina <br>';
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
//if (!file_exists('./opts.php')){require './opts.php';if ($OPT->opcion==1){$bOpcion=true;}}
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
require $APP->rutacomun . 'libcore.php';
require $APP->rutacomun . 'libsai.php';
if (($bPeticionXAJAX) && ($_SESSION['unad_id_tercero'] == 0)) {
	// viene por xajax.
	$xajax = new xajax();
	$xajax->configure('javascript URI', $APP->rutacomun . 'xajax/');
	$xajax->register(XAJAX_FUNCTION, 'sesion_abandona_V2');
	$xajax->processRequest();
	die();
}
$grupo_id = 1; //Necesita ajustarlo...
$iCodModulo = 3041;
$iCodModuloConsulta = $iCodModulo;
switch ($APP->idsistema) {
	case 29: // SAE
		$iCodModuloConsulta = 2921;
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
$mensajes_3041 = $APP->rutacomun . 'lg/lg_3041_' . $_SESSION['unad_idioma'] . '.php';
if (!file_exists($mensajes_3041)) {
	$mensajes_3041 = $APP->rutacomun . 'lg/lg_3041_es.php';
}
require $mensajes_todas;
require $mensajes_3041;
$xajax = NULL;
$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
if ($APP->dbpuerto != '') {
	$objDB->dbPuerto = $APP->dbpuerto;
}
if (isset($APP->piel) == 0) {
	$APP->piel = 1;
}
$sAnchoExpandeContrae = ' style="width:62px;"';
$iPiel = $APP->piel;
$iPiel = 1; //Piel 2018.
if ($bDebug) {
	$sDebug = $sDebug . '' . fecha_microtiempo() . ' Probando conexi&oacute;n con la base de datos <b>' . $APP->dbname . '</b> en <b>' . $APP->dbhost . '</b><br>';
}
if (!$objDB->Conectar()) {
	$bCerrado = true;
	if ($bDebug) {
		$sDebug = $sDebug . '' . fecha_microtiempo() . ' Error al intentar conectar con la base de datos <b>' . $objDB->serror . '</b><br>';
	}
}
list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModuloConsulta, 1, $_SESSION['unad_id_tercero'], $objDB);
if (!$bDevuelve) {
	header('Location:nopermiso.php');
	die();
}
if (!$bPeticionXAJAX) {
	if (noticias_pendientes($objDB)) {
		$objDB->CerrarConexion();
		header('Location:noticia.php?ret=saiacompanaest.php');
		die();
	}
}
$idTercero = $_SESSION['unad_id_tercero'];
$bOtroUsuario = false;
if (isset($_REQUEST['deb_doc']) != 0) {
	list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 1707, $_SESSION['unad_id_tercero'], $objDB, $bDebug);
	//$sDebug=$sDebug.$sDebugP;
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
			$sDebug = $sDebug . fecha_microtiempo() . ' No cuenta con permiso de ingreso como otro usuario [Modulo ' . $iCodModulo . ' Permiso 1707].<br>';
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
$idEntidad = 0;
if (isset($APP->entidad) != 0) {
	if ($APP->entidad == 1) {
		$idEntidad = 1;
	}
}
$mensajes_2323 = $APP->rutacomun . 'lg/lg_2323_' . $_SESSION['unad_idioma'] . '.php';
if (!file_exists($mensajes_2323)) {
	$mensajes_2323 = $APP->rutacomun . 'lg/lg_2323_es.php';
}
$mensajes_2343 = $APP->rutacomun . 'lg/lg_2343_' . $_SESSION['unad_idioma'] . '.php';
if (!file_exists($mensajes_2343)) {
	$mensajes_2343 = $APP->rutacomun . 'lg/lg_2343_es.php';
}
require $mensajes_2323;
require $mensajes_2343;
// -- Si esta cargando la pagina por primer vez se revisa si requiere auditar y se manda a hacer un limpiar (paso -1)
if (isset($_REQUEST['paso']) == 0) {
	if (isset($_REQUEST['saiu41paso']) == 0) {
		$_REQUEST['paso'] = -1;
	} else {
		//echo $_REQUEST['saiu41paso'];
		$_REQUEST['paso'] = $_REQUEST['saiu41paso'];
	}
	if ($audita[1]) {
		seg_auditaingreso($iCodModulo, $_SESSION['unad_id_tercero'], $objDB);
	}
}
// -- 3041 saiu41docente
require $APP->rutacomun . 'lib3041.php';
// -- 2323 Acompanamiento
require $APP->rutacomun . 'lib2323res.php';
// -- 3042 Historico
require $APP->rutacomun . 'lib3042.php';
// -- 2343 Alertas
require $APP->rutacomun . 'lib2343.php';

$xajax = new xajax();
$xajax->configure('javascript URI', $APP->rutacomun . 'xajax/');
$xajax->register(XAJAX_FUNCTION, 'unad11_Mostrar_v2');
$xajax->register(XAJAX_FUNCTION, 'unad11_TraerXid');
$xajax->register(XAJAX_FUNCTION, 'f3041_Combosaiu41idperiodo');
$xajax->register(XAJAX_FUNCTION, 'f3041_Combosaiu41idcurso');
$xajax->register(XAJAX_FUNCTION, 'f3041_Combosaiu41idactividad');
$xajax->register(XAJAX_FUNCTION, 'sesion_abandona_V2');
$xajax->register(XAJAX_FUNCTION, 'sesion_mantenerV4');
$xajax->register(XAJAX_FUNCTION, 'f3041_HtmlTabla');
$xajax->register(XAJAX_FUNCTION, 'f3041_ExisteDato');
$xajax->register(XAJAX_FUNCTION, 'f3041_Busquedas');
$xajax->register(XAJAX_FUNCTION, 'f3041_HtmlBusqueda');
$xajax->register(XAJAX_FUNCTION, 'f3041_HtmlTablaInfoAcademico');
$xajax->register(XAJAX_FUNCTION, 'f2323_HtmlTablaRes');
$xajax->register(XAJAX_FUNCTION, 'f3042_HtmlTabla');
$xajax->register(XAJAX_FUNCTION, 'f2343_Guardar');
$xajax->register(XAJAX_FUNCTION, 'f2343_Traer');
$xajax->register(XAJAX_FUNCTION, 'f2343_Eliminar');
$xajax->register(XAJAX_FUNCTION, 'f2343_HtmlTabla');
$xajax->register(XAJAX_FUNCTION, 'f2343_PintarLlaves');
$xajax->register(XAJAX_FUNCTION, 'f236_TraerInfoPersonal');
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
// -- Se inicializan las variables, primero las que controlan la visualización de la página.
if (isset($_REQUEST['iscroll']) == 0) {
	$_REQUEST['iscroll'] = 0;
}
if (isset($_REQUEST['paginaf3041']) == 0) {
	$_REQUEST['paginaf3041'] = 1;
}
if (isset($_REQUEST['lppf3041']) == 0) {
	$_REQUEST['lppf3041'] = 20;
}
if (isset($_REQUEST['boculta3041']) == 0) {
	$_REQUEST['boculta3041'] = 0;
}
if (isset($_REQUEST['paginaf304111']) == 0) {
	$_REQUEST['paginaf304111'] = 1;
}
if (isset($_REQUEST['lppf304111']) == 0) {
	$_REQUEST['lppf304111'] = 20;
}
if (isset($_REQUEST['paginaf2323res']) == 0) {
	$_REQUEST['paginaf2323res'] = 1;
}
if (isset($_REQUEST['lppf2323res']) == 0) {
	$_REQUEST['lppf2323res'] = 20;
}
if (isset($_REQUEST['boculta2323']) == 0) {
	$_REQUEST['boculta2323'] = 0;
}
if (isset($_REQUEST['paginaf3042']) == 0) {
	$_REQUEST['paginaf3042'] = 1;
}
if (isset($_REQUEST['lppf3042']) == 0) {
	$_REQUEST['lppf3042'] = 20;
}
if (isset($_REQUEST['boculta3042']) == 0) {
	$_REQUEST['boculta3042'] = 0;
}
if (isset($_REQUEST['paginaf2343']) == 0) {
	$_REQUEST['paginaf2343'] = 1;
}
if (isset($_REQUEST['lppf2343']) == 0) {
	$_REQUEST['lppf2343'] = 20;
}
if (isset($_REQUEST['boculta2343']) == 0) {
	$_REQUEST['boculta2343'] = 0;
}
// -- Inicializar variables de datos.
if (isset($_REQUEST['saiu41idestudiante']) == 0) {
	$_REQUEST['saiu41idestudiante'] = 0;
} // {$_SESSION['unad_id_tercero'];}
if (isset($_REQUEST['saiu41idestudiante_td']) == 0) {
	$_REQUEST['saiu41idestudiante_td'] = $APP->tipo_doc;
}
if (isset($_REQUEST['saiu41idestudiante_doc']) == 0) {
	$_REQUEST['saiu41idestudiante_doc'] = '';
}
if (isset($_REQUEST['saiu41consec']) == 0) {
	$_REQUEST['saiu41consec'] = '';
}
if (isset($_REQUEST['saiu41consec_nuevo']) == 0) {
	$_REQUEST['saiu41consec_nuevo'] = '';
}
if (isset($_REQUEST['saiu41id']) == 0) {
	$_REQUEST['saiu41id'] = '';
}
if (isset($_REQUEST['saiu41tipocontacto']) == 0) {
	$_REQUEST['saiu41tipocontacto'] = '';
}
if (isset($_REQUEST['saiu41fecha']) == 0) {
	$_REQUEST['saiu41fecha'] = '';
} //{fecha_hoy();}
if (isset($_REQUEST['saiu41cerrada']) == 0) {
	$_REQUEST['saiu41cerrada'] = 0;
}
if (isset($_REQUEST['saiu41idperiodo']) == 0) {
	$_REQUEST['saiu41idperiodo'] = '';
}
if (isset($_REQUEST['saiu41idcurso']) == 0) {
	$_REQUEST['saiu41idcurso'] = '';
}
if (isset($_REQUEST['saiu41idactividad']) == 0) {
	$_REQUEST['saiu41idactividad'] = '';
}
if (isset($_REQUEST['saiu41idtutor']) == 0) {
	$_REQUEST['saiu41idtutor'] = $_SESSION['unad_id_tercero'];
}
if (isset($_REQUEST['saiu41idtutor_td']) == 0) {
	$_REQUEST['saiu41idtutor_td'] = $APP->tipo_doc;
}
if (isset($_REQUEST['saiu41idtutor_doc']) == 0) {
	$_REQUEST['saiu41idtutor_doc'] = '';
}
if (isset($_REQUEST['saiu41visiblealest']) == 0) {
	$_REQUEST['saiu41visiblealest'] = '';
}
if (isset($_REQUEST['saiu41contacto_efectivo']) == 0) {
	$_REQUEST['saiu41contacto_efectivo'] = '';
}
if (isset($_REQUEST['saiu41contacto_forma']) == 0) {
	$_REQUEST['saiu41contacto_forma'] = '';
}
if (isset($_REQUEST['saiu41contacto_observa']) == 0) {
	$_REQUEST['saiu41contacto_observa'] = '';
}
if (isset($_REQUEST['saiu41seretira']) == 0) {
	$_REQUEST['saiu41seretira'] = '';
}
if (isset($_REQUEST['saiu41factorprincipaldesc']) == 0) {
	$_REQUEST['saiu41factorprincipaldesc'] = '';
}
if (isset($_REQUEST['saiu41motivocontacto']) == 0) {
	$_REQUEST['saiu41motivocontacto'] = '';
}
if (isset($_REQUEST['saiu41acciones']) == 0) {
	$_REQUEST['saiu41acciones'] = '';
}
if (isset($_REQUEST['saiu41resultados']) == 0) {
	$_REQUEST['saiu41resultados'] = '';
}
if (isset($_REQUEST['cara43consec']) == 0) {
	$_REQUEST['cara43consec'] = '';
}
if (isset($_REQUEST['cara43id']) == 0) {
	$_REQUEST['cara43id'] = '';
}
if (isset($_REQUEST['cara43idalerta']) == 0) {
	$_REQUEST['cara43idalerta'] = '';
}
if (isset($_REQUEST['cara43fecharegistro']) == 0) {
	$_REQUEST['cara43fecharegistro'] = '';
} //{fecha_hoy();}
if (isset($_REQUEST['cara43idregistro']) == 0) {
	$_REQUEST['cara43idregistro'] = 0;
} //{$_SESSION['unad_id_tercero'];}
if (isset($_REQUEST['cara43idregistro_td']) == 0) {
	$_REQUEST['cara43idregistro_td'] = $APP->tipo_doc;
}
if (isset($_REQUEST['cara43idregistro_doc']) == 0) {
	$_REQUEST['cara43idregistro_doc'] = '';
}
if (isset($_REQUEST['cara43fechacierre']) == 0) {
	$_REQUEST['cara43fechacierre'] = '';
}
if (isset($_REQUEST['cara43idcierre']) == 0) {
	$_REQUEST['cara43idcierre'] = '';
}
if (isset($_REQUEST['cara23idtercero']) == 0) {
	$_REQUEST['cara23idtercero'] = 0;
}
// Espacio para inicializar otras variables
if (isset($_REQUEST['csv_separa']) == 0) {
	$_REQUEST['csv_separa'] = ',';
}
if (isset($_REQUEST['bnombre']) == 0) {
	$_REQUEST['bnombre'] = '';
}
if (isset($_REQUEST['bdoc']) == 0) {
	$_REQUEST['bdoc'] = '';
}
if (isset($_REQUEST['blistar']) == 0) {
	$_REQUEST['blistar'] = 1;
}
if (isset($_REQUEST['bperiodo']) == 0) {
	$_REQUEST['bperiodo'] = '';
}
if (isset($_REQUEST['bcodcurso']) == 0) {
	$_REQUEST['bcodcurso'] = '';
}
//Si Modifica o Elimina Cargar los campos
if ($bDebug) {
	$sDebug = $sDebug . fecha_microtiempo() . ' Paso ' . $_REQUEST['paso'] . '<br>';
}
if (($_REQUEST['paso'] == 1) || ($_REQUEST['paso'] == 3)) {
	$_REQUEST['saiu41idestudiante_td'] = $APP->tipo_doc;
	$_REQUEST['saiu41idestudiante_doc'] = '';
	$_REQUEST['saiu41idtutor_td'] = $APP->tipo_doc;
	$_REQUEST['saiu41idtutor_doc'] = '';
	if ($_REQUEST['paso'] == 1) {
		list($idContTercero, $sError) = f1011_BloqueTercero($_REQUEST['saiu41idestudiante'], $objDB);
		$sTabla41 = 'saiu41docente_' . $idContTercero;
		$sSQLcondi = 'saiu41idestudiante="' . $_REQUEST['saiu41idestudiante'] . '" AND saiu41consec=' . $_REQUEST['saiu41consec'] . '';
	} else {
		if (isset($_REQUEST['contenedor']) == 0) {
			$_REQUEST['contenedor'] = '';
		}
		$sTabla41 = 'saiu41docente_' . $_REQUEST['contenedor'];
		$sSQLcondi = 'saiu41id=' . $_REQUEST['saiu41id'] . '';
	}
	$sSQL = 'SELECT * FROM ' . $sTabla41 . ' WHERE ' . $sSQLcondi;
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' Traida de datos ' . $sSQL . '<br>';
	}
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$_REQUEST['saiu41idestudiante'] = $fila['saiu41idestudiante'];
		$_REQUEST['saiu41consec'] = $fila['saiu41consec'];
		$_REQUEST['saiu41id'] = $fila['saiu41id'];
		$_REQUEST['saiu41tipocontacto'] = $fila['saiu41tipocontacto'];
		$_REQUEST['saiu41fecha'] = $fila['saiu41fecha'];
		$_REQUEST['saiu41cerrada'] = $fila['saiu41cerrada'];
		$_REQUEST['saiu41idperiodo'] = $fila['saiu41idperiodo'];
		$_REQUEST['saiu41idcurso'] = $fila['saiu41idcurso'];
		$_REQUEST['saiu41idactividad'] = $fila['saiu41idactividad'];
		$_REQUEST['saiu41idtutor'] = $fila['saiu41idtutor'];
		$_REQUEST['saiu41visiblealest'] = $fila['saiu41visiblealest'];
		$_REQUEST['saiu41contacto_efectivo'] = $fila['saiu41contacto_efectivo'];
		$_REQUEST['saiu41contacto_forma'] = $fila['saiu41contacto_forma'];
		$_REQUEST['saiu41contacto_observa'] = $fila['saiu41contacto_observa'];
		$_REQUEST['saiu41seretira'] = $fila['saiu41seretira'];
		$_REQUEST['saiu41factorprincipaldesc'] = $fila['saiu41factorprincipaldesc'];
		$_REQUEST['saiu41motivocontacto'] = $fila['saiu41motivocontacto'];
		$_REQUEST['saiu41acciones'] = $fila['saiu41acciones'];
		$_REQUEST['saiu41resultados'] = $fila['saiu41resultados'];
		$_REQUEST['cara23idtercero'] = $fila['saiu41idestudiante'];
		$bcargo = true;
		$_REQUEST['paso'] = 2;
		$_REQUEST['boculta3041'] = 0;
		$bLimpiaHijos = true;
	} else {
		$_REQUEST['paso'] = 0;
	}
}
//Cerrar
$bCerrando = false;
if ($_REQUEST['paso'] == 16) {
	$_REQUEST['paso'] = 12;
	$_REQUEST['saiu41cerrada'] = 1;
	$bCerrando = true;
}
//Abrir
if ($_REQUEST['paso'] == 17) {
	$_REQUEST['paso'] = 2;
	//Es posible que deba definir el codigo de permiso para abrir.
	if (!seg_revisa_permiso($iCodModulo, 17, $objDB)) {
		$sError = $ERR['3'];
	}
	//Otras restricciones para abrir.
	if ($sError == '') {
		//$sError='Motivo por el que no se pueda abrir, no se permite modificar.';
	}
	if ($sError != '') {
		$_REQUEST['saiu41cerrada'] = 'S';
	} else {
		list($idContTercero, $sError) = f1011_BloqueTercero($_REQUEST['saiu41idestudiante'], $objDB);
		$sTabla41 = 'saiu41docente_' . $idContTercero;
		$sSQL = 'UPDATE ' . $sTabla41 . ' SET saiu41cerrada=0 WHERE saiu41id=' . $_REQUEST['saiu41id'] . '';
		$tabla = $objDB->ejecutasql($sSQL);
		seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 3, $_REQUEST['saiu41id'], 'Abre Acompañamiento a estudiantes', $objDB);
		$_REQUEST['saiu41cerrada'] = 'N';
		$sError = '<b>El documento ha sido abierto</b>';
		$iTipoError = 1;
	}
}
//Insertar o modificar un elemento
if (($_REQUEST['paso'] == 10) || ($_REQUEST['paso'] == 12)) {
	$bMueveScroll = true;
	list($_REQUEST, $sError, $iTipoError, $bCerrando, $sErrorCerrando, $sDebugGuardar) = f3041_db_GuardarV2($_REQUEST, $objDB, $bDebug);
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
	$_REQUEST['saiu41consec_nuevo'] = numeros_validar($_REQUEST['saiu41consec_nuevo']);
	if ($_REQUEST['saiu41consec_nuevo'] == '') {
		$sError = $ERR['saiu41consec'];
	}
	if ($sError == '') {
		if (!seg_revisa_permiso($iCodModulo, 8, $objDB)) {
			$sError = $ERR['8'];
		}
	}
	if ($sError == '') {
		//Ver que el consecutivo no exista.
		$sSQL = 'SELECT saiu41id FROM ' . $sTabla41 . ' WHERE saiu41consec=' . $_REQUEST['saiu41consec_nuevo'] . ' AND saiu41idestudiante=' . $_REQUEST['saiu41idestudiante'] . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$sError = 'El consecutivo ' . $_REQUEST['saiu41consec_nuevo'] . ' ya existe';
		}
	}
	if ($sError == '') {
		//Aplicar el cambio.
		list($idContTercero, $sError) = f1011_BloqueTercero($_REQUEST['saiu41idestudiante'], $objDB);
		$sTabla41 = 'saiu41docente_' . $idContTercero;
		$sSQL = 'UPDATE ' . $sTabla41 . ' SET saiu41consec=' . $_REQUEST['saiu41consec_nuevo'] . ' WHERE saiu41id=' . $_REQUEST['saiu41id'] . '';
		$tabla = $objDB->ejecutasql($sSQL);
		$sDetalle = 'Cambia el consecutivo de ' . $_REQUEST['saiu41consec'] . ' a ' . $_REQUEST['saiu41consec_nuevo'] . '';
		$_REQUEST['saiu41consec'] = $_REQUEST['saiu41consec_nuevo'];
		$_REQUEST['saiu41consec_nuevo'] = '';
		seg_auditar($iCodModulo, $_SESSION['u_idtercero'], 8, $_REQUEST['saiu41id'], $sDetalle, $objDB);
		$sError = '<b>Se ha aplicado el cambio de consecutivo.</b>';
		$iTipoError = 1;
	} else {
		$iSector = 93;
	}
}
//Eliminar un elemento
if ($_REQUEST['paso'] == 13) {
	$_REQUEST['paso'] = 2;
	list($sError, $iTipoError, $sDebugElimina) = f3041_db_Eliminar($_REQUEST['saiu41idestudiante'], $_REQUEST['saiu41id'], $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugElimina;
	if ($sError == '') {
		$_REQUEST['paso'] = -1;
		$sError = $ETI['msg_itemeliminado'];
		$iTipoError = 1;
	}
}
//limpiar la pantalla
if ($_REQUEST['paso'] == -1) {
	$_REQUEST['saiu41idestudiante'] = 0; //$idTercero;
	$_REQUEST['saiu41idestudiante_td'] = $APP->tipo_doc;
	$_REQUEST['saiu41idestudiante_doc'] = '';
	$_REQUEST['saiu41consec'] = '';
	$_REQUEST['saiu41consec_nuevo'] = '';
	$_REQUEST['saiu41id'] = '';
	$_REQUEST['saiu41tipocontacto'] = 0;
	$_REQUEST['saiu41fecha'] = ''; //fecha_hoy();
	$_REQUEST['saiu41cerrada'] = 0;
	$_REQUEST['saiu41idperiodo'] = 0;
	$_REQUEST['saiu41idcurso'] = 0;
	$_REQUEST['saiu41idactividad'] = '';
	$_REQUEST['saiu41idtutor'] = $idTercero;
	$_REQUEST['saiu41idtutor_td'] = $APP->tipo_doc;
	$_REQUEST['saiu41idtutor_doc'] = '';
	$_REQUEST['saiu41visiblealest'] = 0;
	$_REQUEST['saiu41contacto_efectivo'] = 0;
	$_REQUEST['saiu41contacto_forma'] = '';
	$_REQUEST['saiu41contacto_observa'] = '';
	$_REQUEST['saiu41seretira'] = 0;
	$_REQUEST['saiu41factorprincipaldesc'] = '';
	$_REQUEST['saiu41motivocontacto'] = '';
	$_REQUEST['saiu41acciones'] = '';
	$_REQUEST['saiu41resultados'] = '';
	$_REQUEST['paso'] = 0;
	$_REQUEST['cara23idtercero'] = 0;
	$_REQUEST['cara43idestudiante'] = '';
	$_REQUEST['cara43consec'] = '';
	$_REQUEST['cara43id'] = '';
	$_REQUEST['cara43idalerta'] = '';
	$_REQUEST['cara43fecharegistro'] = 0;
	$_REQUEST['cara43idregistro'] = $idTercero;
	$_REQUEST['cara43idregistro_td'] = $APP->tipo_doc;
	$_REQUEST['cara43idregistro_doc'] = '';
	$_REQUEST['cara43fechacierre'] = 0;
	$_REQUEST['cara43idcierre'] = 0;
}
if ($bLimpiaHijos) {
}
//AQUI SE DEBEN CARGAR TODOS LOS DATOS QUE LA FORMA NECESITE.
//DATOS PARA COMPLETAR EL FORMULARIO
//Permisos adicionales
$seg_5 = 0;
$seg_6 = 0;
$seg_8 = 0;
$seg_12 = 0;
list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 12, $idTercero, $objDB);
if ($bDevuelve) {
	$seg_12 = 1;
}
//if ($seg_6==1){}
$html_personal = '';
$html_InfoContacto = '';
//Crear los controles que requieran llamado a base de datos
$objCombos = new clsHtmlCombos();
$objTercero = new clsHtmlTercero();
list($saiu41idestudiante_rs, $_REQUEST['saiu41idestudiante'], $_REQUEST['saiu41idestudiante_td'], $_REQUEST['saiu41idestudiante_doc']) = html_tercero($_REQUEST['saiu41idestudiante_td'], $_REQUEST['saiu41idestudiante_doc'], $_REQUEST['saiu41idestudiante'], 0, $objDB);
$html_saiu41idperiodo = f3041_HTMLComboV2_saiu41idperiodo($objDB, $objCombos, $_REQUEST['saiu41idperiodo'], $_REQUEST['saiu41idestudiante']);
$html_saiu41idcurso = f3041_HTMLComboV2_saiu41idcurso($objDB, $objCombos, $_REQUEST['saiu41idcurso'], $_REQUEST['saiu41idperiodo'], $_REQUEST['saiu41idestudiante']);
$html_saiu41idactividad = f3041_HTMLComboV2_saiu41idactividad($objDB, $objCombos, $_REQUEST['saiu41idactividad'], $_REQUEST['saiu41idcurso'], $_REQUEST['saiu41idperiodo'], $_REQUEST['saiu41idestudiante']);
list($saiu41idtutor_rs, $_REQUEST['saiu41idtutor'], $_REQUEST['saiu41idtutor_td'], $_REQUEST['saiu41idtutor_doc']) = html_tercero($_REQUEST['saiu41idtutor_td'], $_REQUEST['saiu41idtutor_doc'], $_REQUEST['saiu41idtutor'], 0, $objDB);
$objCombos->nuevo('saiu41visiblealest', $_REQUEST['saiu41visiblealest'], true, $ETI['no'], 0);
$objCombos->addItem(1, $ETI['si']);
//$objCombos->addArreglo($asaiu41visiblealest, $isaiu41visiblealest);
$html_saiu41visiblealest = $objCombos->html('', $objDB);
$objCombos->nuevo('saiu41contacto_efectivo', $_REQUEST['saiu41contacto_efectivo'], true, $ETI['no'], 0);
$objCombos->addItem(1, $ETI['si']);
//$objCombos->addArreglo($asaiu41contacto_efectivo, $isaiu41contacto_efectivo);
$html_saiu41contacto_efectivo = $objCombos->html('', $objDB);
$objCombos->nuevo('saiu41contacto_forma', $_REQUEST['saiu41contacto_forma'], true, '{' . $ETI['msg_seleccione'] . '}');
$sSQL = 'SELECT cara27id AS id, cara27titulo AS nombre FROM cara27mediocont WHERE cara27id>0 ORDER BY cara27titulo';
$html_saiu41contacto_forma = $objCombos->html($sSQL, $objDB);
$objCombos->nuevo('saiu41seretira', $_REQUEST['saiu41seretira'], true, $ETI['no'], 0);
$objCombos->sAccion = 'advertencia_retiro()';
$objCombos->addItem(1, $ETI['si']);
//$objCombos->addArreglo($asaiu41seretira, $isaiu41seretira);
$html_saiu41seretira = $objCombos->html('', $objDB);
$objCombos->nuevo('saiu41factorprincipaldesc', $_REQUEST['saiu41factorprincipaldesc'], true, '{' . $ETI['msg_na'] . '}', 0);
$sSQL = 'SELECT cara15id AS id, cara15nombre AS nombre FROM cara15factordeserta WHERE cara15id>0 ORDER BY cara15nombre';
$html_saiu41factorprincipaldesc = $objCombos->html($sSQL, $objDB);
$objCombos->nuevo('saiu41motivocontacto', $_REQUEST['saiu41motivocontacto'], true, '{' . $ETI['msg_seleccione'] . '}');
$sSQL = 'SELECT saiu43id AS id, saiu43nombre AS nombre FROM saiu43situacioncontacto ORDER BY saiu43orden, saiu43nombre';
$html_saiu41motivocontacto = $objCombos->html($sSQL, $objDB);
$objCombos->nuevo('saiu41acciones', $_REQUEST['saiu41acciones'], true, '{' . $ETI['msg_seleccione'] . '}');
$sSQL = 'SELECT cara25id AS id, cara25titulo AS nombre FROM cara25accionescat ORDER BY cara25orden, cara25titulo';
$html_saiu41acciones = $objCombos->html($sSQL, $objDB);
$objCombos->nuevo('saiu41resultados', $_REQUEST['saiu41resultados'], true, '{' . $ETI['msg_seleccione'] . '}');
$sSQL = 'SELECT cara26id AS id, cara26titulo AS nombre FROM cara26resultcat ORDER BY cara26orden, cara26titulo';
$html_saiu41resultados = $objCombos->html($sSQL, $objDB);
if ((int)$_REQUEST['paso'] == 0) {
	$objCombos->nuevo('saiu41tipocontacto', $_REQUEST['saiu41tipocontacto'], true, '{' . $ETI['msg_seleccione'] . '}');
	$sSQL = 'SELECT ceca26id AS id, ceca26nombre AS nombre FROM ceca26tipoacompana ORDER BY ceca26nombre';
	$html_saiu41tipocontacto = $objCombos->html($sSQL, $objDB);
} else {
	list($saiu41tipocontacto_nombre, $sErrorDet) = tabla_campoxid('ceca26tipoacompana', 'ceca26nombre', 'ceca26id', $_REQUEST['saiu41tipocontacto'], '{' . $ETI['msg_sindato'] . '}', $objDB);
	$html_saiu41tipocontacto = html_oculto('saiu41tipocontacto', $_REQUEST['saiu41tipocontacto'], $saiu41tipocontacto_nombre);
}
$objCombos->nuevo('cara43idalerta', $_REQUEST['cara43idalerta'], true, '{' . $ETI['msg_seleccione'] . '}');
$sSQL = 'SELECT cara42id AS id, cara42nombre AS nombre FROM cara42alertas WHERE cara42activa=1 ORDER BY cara42orden, cara42nombre';
$html_cara43idalerta = $objCombos->html($sSQL, $objDB);
if ($_REQUEST['saiu41idestudiante'] != 0) {
	list($html_InfoContacto, $html_personal, $sDebugC) = f3000_InfoContacto($_REQUEST['saiu41idestudiante'], $idTercero, $objDB, $bDebug);
}
//Alistar datos adicionales
$bPuedeAbrir = false;
if ($_REQUEST['paso'] != 0) {
	if ($_REQUEST['saiu41cerrada'] == 1) {
		//Definir las condiciones que permitirán abrir el registro.
		list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 17, $idTercero, $objDB);
		if ($bDevuelve) {
			$bPuedeAbrir = true;
		}
	}
}
$id_rpt = 0;
$iAgnoFin = fecha_agno();
//$id_rpt=reportes_id(_Identificador_Tipo_Reporte_, $objDB);
$bVacio = false;
if ($seg_12 == 1) {
	$bVacio = true;
}
$objCombos->nuevo('blistar', $_REQUEST['blistar'], $bVacio, '{' . $ETI['msg_todos'] . '}');
$objCombos->sAccion = 'paginarf3041()';
$objCombos->addItem(1, 'Los que soy tutor');
$objCombos->addItem(2, 'Mis acomapa&ntilde;amientos pendientes');
$objCombos->addItem(3, 'Todos pendientes');
if ($bVacio) {
	$objCombos->addItem(4, 'Con Voluntad de Retiro');
}
$html_blistar = $objCombos->html('', $objDB);
$objCombos->nuevo('bperiodo', $_REQUEST['bperiodo'], true, '{' . $ETI['msg_todos'] . '}');
$objCombos->iAncho = 600;
$objCombos->sAccion = 'paginarf3041()';
$sSQL = f146_ConsultaCombo('exte02id>760', $objDB);
$html_bperiodo = $objCombos->html($sSQL, $objDB);

if (false) {
	$objCombos->nuevo('csv_separa', $_REQUEST['csv_separa'], false);
	$objCombos->addItem(',', $ETI['msg_coma']);
	$objCombos->addItem(';', $ETI['msg_puntoycoma']);
	$csv_separa = '<label class="Label90">' . $ETI['msg_separador'] . '</label><label class="Label130">' . $objCombos->html('', $objDB) . '</label>';
} else {
	$csv_separa = '<input id="csv_separa" name="csv_separa" type="hidden" value="," />';
}
$iNumFormatosImprime = 0;
$iModeloReporte = 3041;
$html_iFormatoImprime = '<input id="iformatoimprime" name="iformatoimprime" type="hidden" value="0" />
';
if ($_REQUEST['paso'] > 0) {
	$bDevuelve = false;
	//list($bDevuelve, $sDebugP)=seg_revisa_permisoV3($iCodModulo, 5, $idTercero, $objDB);
	if ($bDevuelve) {
		$seg_5 = 1;
	}
	if ($_REQUEST['saiu41cerrada'] != 'S') {
		list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 8, $idTercero, $objDB);
		if ($bDevuelve) {
			$seg_8 = 1;
		}
	}
}
//Cargar las tablas de datos
$aParametros[0] = ''; //$_REQUEST['p1_3041'];
$aParametros[100] = $idTercero;
$aParametros[101] = $_REQUEST['paginaf3041'];
$aParametros[102] = $_REQUEST['lppf3041'];
$aParametros[103] = $_REQUEST['bnombre'];
$aParametros[104] = $_REQUEST['blistar'];
$aParametros[105] = $_REQUEST['bdoc'];
$aParametros[106] = $_REQUEST['bperiodo'];
$aParametros[107] = $_REQUEST['bcodcurso'];
list($sTabla3041, $sDebugTabla) = f3041_TablaDetalleV2($aParametros, $objDB, $bDebug);
$sDebug = $sDebug . $sDebugTabla;
$sTabla304Acad = '<input id="paginaf304111" name="paginaf304111" type="hidden" value="' . $_REQUEST['paginaf304111'] . '"/>
<input id="lppf304111" name="lppf304111" type="hidden" value="' . $_REQUEST['lppf304111'] . '"/>';
$sTabla2323 = '<input id="paginaf2323res" name="paginaf2323res" type="hidden" value="' . $_REQUEST['paginaf2323res'] . '" />
<input id="lppf2323res" name="lppf2323res" type="hidden" value="' . $_REQUEST['lppf2323res'] . '" />';
$sTabla3042 = '<input id="paginaf3042" name="paginaf3042" type="hidden" value="' . $_REQUEST['paginaf3042'] . '"/>
<input id="lppf3042" name="lppf3042" type="hidden" value="' . $_REQUEST['lppf3042'] . '"/>';
if ($_REQUEST['saiu41idestudiante'] != 0) {
	$aParametros[0] = ''; //$_REQUEST['p1_3041'];
	$aParametros[100] = $idTercero;
	$aParametros[101] = $_REQUEST['paginaf304111'];
	$aParametros[102] = $_REQUEST['lppf304111'];
	$aParametros[103] = $_REQUEST['saiu41idestudiante'];
	list($sTabla304Acad, $sDebugTabla) = f3041_TablaInfoAcademico($aParametros, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugTabla;
	//Acompanamiento
	$aParametros2323[100] = $idTercero;
	$aParametros2323[101] = $_REQUEST['paginaf2323res'];
	$aParametros2323[102] = $_REQUEST['lppf2323res'];
	$aParametros2323[103] = $_REQUEST['saiu41idestudiante'];
	list($sTabla2323, $sDebugTabla) = f2323_TablaDetalleV2Res($aParametros2323, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugTabla;
	//Historico
	$aParametros3042[91] = $_REQUEST['paginaf3042'];
	$aParametros3042[92] = $_REQUEST['lppf3042'];
	$aParametros3042[93] = $_REQUEST['saiu41id'];
	$aParametros3042[100] = $idTercero;
	$aParametros3042[103] = $_REQUEST['saiu41idestudiante'];
	list($sTabla3042, $sDebugTabla) = f3042_TablaDetalleV2($aParametros3042, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugTabla;
}
//Alertas
$aParametros2343[0] = $_REQUEST['saiu41idestudiante'];
$aParametros2343[100] = $idTercero;
$aParametros2343[101] = $_REQUEST['paginaf2343'];
$aParametros2343[102] = $_REQUEST['lppf2343'];
//$aParametros2343[103]=$_REQUEST['bnombre2343'];
//$aParametros2343[104]=$_REQUEST['blistar2343'];
list($sTabla2343, $sDebugTabla) = f2343_TablaDetalleV2($aParametros2343, $objDB, $bDebug);
$sDebug = $sDebug . $sDebugTabla;

$bDebugMenu = false;
list($et_menu, $sDebugM) = html_menuV2($APP->idsistema, $objDB, $iPiel, $bDebugMenu, $idTercero);
$sDebug = $sDebug . $sDebugM;
$objDB->CerrarConexion();
//FORMA
require $APP->rutacomun . 'unad_forma_v2.php';
forma_cabeceraV3($xajax, $ETI['titulo_3041']);
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
		if (window.document.frmedita.saiu41cerrada.value == 0) {
			var sEst = 'none';
			if (codigo == 1) {
				sEst = 'block';
			}
			document.getElementById('cmdGuardarf').style.display = sEst;
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
			params[4] = 'RevisaLlave()';
			params[5] = 'f3041_InfoInteresado()';
			xajax_unad11_Mostrar_v2(params);
		} else {
			document.getElementById(idcampo).value = 0;
			document.getElementById('div_' + idcampo).innerHTML = '&nbsp;';
			f3041_InfoInteresado();
		}
	}

	function ter_traerxid(idcampo, vrcampo) {
		var params = new Array();
		params[0] = vrcampo;
		params[1] = idcampo;
		if (params[0] != 0) {
			if (idcampo == 'saiu41idestudiante') {
				params[4] = 'RevisaLlave';
				params[5] = 'f3041_InfoInteresado';
			}
			xajax_unad11_TraerXid(params);
		}
	}

	function imprimelista() {
		if (window.document.frmedita.seg_6.value == 1) {
			window.document.frmlista.consulta.value = window.document.frmedita.consulta_3041.value;
			window.document.frmlista.titulos.value = window.document.frmedita.titulos_3041.value;
			window.document.frmlista.nombrearchivo.value = 'Acompañamiento a estudiantes';
			window.document.frmlista.submit();
		} else {
			window.alert("<?php echo $ERR['6']; ?>");
		}
	}

	function asignarvariables() {
		//window.document.frmimpp.v3.value=window.document.frmedita.bnombre.value;
		//window.document.frmimpp.v4.value=window.document.frmedita.bcodigo.value;
		//window.document.frmimpp.separa.value=window.document.frmedita.csv_separa.value.trim();
	}

	function imprimeexcel() {
		var sError = '';
		if (window.document.frmedita.seg_6.value != 1) {
			sError = "<?php echo $ERR['6']; ?>";
		}
		//if (sError==''){/*Agregar validaciones*/}
		if (sError == '') {
			asignarvariables();
			window.document.frmimpp.action = '<?php echo $APP->rutacomun; ?>e3041.php';
			window.document.frmimpp.submit();
		} else {
			window.alert(sError);
		}
	}

	function imprimep() {
		if (window.document.frmedita.seg_5.value == 1) {
			asignarvariables();
			window.document.frmimpp.action = '<?php echo $APP->rutacomun; ?>p3041.php';
			window.document.frmimpp.submit();
			<?php
			if ($iNumFormatosImprime > 0) {
			?>
				expandesector(1);
			<?php
			}
			?>
		} else {
			window.alert("<?php echo $ERR['5']; ?>");
		}
	}

	function verrpt() {
		window.document.frmimprime.submit();
	}

	function eliminadato() {
		if (confirm("<?php echo $ETI['confirma_eliminar']; ?>?")) {
			expandesector(98);
			window.document.frmedita.paso.value = 13;
			window.document.frmedita.submit();
		}
	}

	function RevisaLlave() {
		var datos = new Array();
		datos[1] = window.document.frmedita.saiu41idestudiante.value;
		datos[2] = window.document.frmedita.saiu41consec.value;
		if ((datos[1] != '') && (datos[2] != '')) {
			xajax_f3041_ExisteDato(datos);
		} else {
			if ((datos[1] != '')) {
				f3041_InfoInteresado();
			}
		}
	}

	function cargadato(llave1, llave2) {
		window.document.frmedita.saiu41idestudiante.value = String(llave1);
		window.document.frmedita.saiu41consec.value = String(llave2);
		window.document.frmedita.paso.value = 1;
		window.document.frmedita.submit();
	}

	function cargaridf3041(cont, llave1) {
		window.document.frmedita.contenedor.value = String(cont);
		window.document.frmedita.saiu41id.value = String(llave1);
		window.document.frmedita.paso.value = 3;
		window.document.frmedita.submit();
	}

	function carga_combo_saiu41idperiodo() {
		var params = new Array();
		params[0] = window.document.frmedita.saiu41idestudiante.value;
		document.getElementById('div_saiu41idperiodo').innerHTML = '<b>Procesando datos, por favor espere...</b><input id="saiu41idperiodo" name="saiu41idperiodo" type="hidden" value="" />';
		xajax_f3041_Combosaiu41idperiodo(params);
	}

	function carga_combo_saiu41idcurso() {
		var params = new Array();
		params[0] = window.document.frmedita.saiu41idperiodo.value;
		params[1] = window.document.frmedita.saiu41idestudiante.value;
		document.getElementById('div_saiu41idcurso').innerHTML = '<b>Procesando datos, por favor espere...</b><input id="saiu41idcurso" name="saiu41idcurso" type="hidden" value="" />';
		xajax_f3041_Combosaiu41idcurso(params);
	}

	function carga_combo_saiu41idactividad() {
		var params = new Array();
		params[0] = window.document.frmedita.saiu41idcurso.value;
		params[1] = window.document.frmedita.saiu41idperiodo.value;
		params[2] = window.document.frmedita.saiu41idestudiante.value;
		document.getElementById('div_saiu41idactividad').innerHTML = '<b>Procesando datos, por favor espere...</b><input id="saiu41idactividad" name="saiu41idactividad" type="hidden" value="" />';
		xajax_f3041_Combosaiu41idactividad(params);
	}

	function paginarf2323() {
		var params = new Array();
		params[99] = window.document.frmedita.debug.value;
		params[100] = window.document.frmedita.id11.value;
		params[101] = window.document.frmedita.paginaf2323res.value;
		params[102] = window.document.frmedita.lppf2323res.value;
		params[103] = window.document.frmedita.saiu41idestudiante.value;
		document.getElementById('div_f2323detalleRes').innerHTML = '<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf2323res" name="paginaf2323res" type="hidden" value="' + params[101] + '" /><input id="lppf2323res" name="lppf2323res" type="hidden" value="' + params[102] + '" />';
		xajax_f2323_HtmlTablaRes(params);
	}

	function paginarf3041() {
		var params = new Array();
		params[99] = window.document.frmedita.debug.value;
		params[100] = <?php echo $idTercero; ?>;
		params[101] = window.document.frmedita.paginaf3041.value;
		params[102] = window.document.frmedita.lppf3041.value;
		params[103] = window.document.frmedita.bnombre.value;
		params[104] = window.document.frmedita.blistar.value;
		params[105] = window.document.frmedita.bdoc.value;
		params[106] = window.document.frmedita.bperiodo.value;
		params[107] = window.document.frmedita.bcodcurso.value;
		//document.getElementById('div_f3041detalle').innerHTML='<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf3041" name="paginaf3041" type="hidden" value="'+params[101]+'" /><input id="lppf3041" name="lppf3041" type="hidden" value="'+params[102]+'" />';
		xajax_f3041_HtmlTabla(params);
	}

	function enviacerrar() {
		if (confirm('Esta seguro de cerrar el registro?\nluego de cerrado no se permite modificar')) {
			expandesector(98);
			window.document.frmedita.paso.value = 16;
			window.document.frmedita.submit();
		}
	}

	function enviaabrir() {
		if (confirm('Esta seguro de abrir el registro?\nesto le permite volver a modificar')) {
			expandesector(98);
			window.document.frmedita.paso.value = 17;
			window.document.frmedita.submit();
		}
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
		document.getElementById("saiu41idestudiante").focus();
	}

	function buscarV2016(sCampo) {
		window.document.frmedita.iscroll.value = window.pageYOffset;
		expandesector(98);
		window.document.frmedita.scampobusca.value = sCampo;
		var params = new Array();
		params[1] = sCampo;
		//params[2]=window.document.frmedita.iagno.value;
		//params[3]=window.document.frmedita.itipo.value;
		xajax_f3041_Busquedas(params);
	}

	function retornacontrol() {
		expandesector(1);
		window.scrollTo(0, window.document.frmedita.iscroll.value);
	}

	function Devuelve(sValor) {
		var sCampo = window.document.frmedita.scampobusca.value;
		if (sCampo == 'saiu41idestudiante') {
			ter_traerxid('saiu41idestudiante', sValor);
		}
		if (sCampo == 'saiu41idtutor') {
			ter_traerxid('saiu41idtutor', sValor);
		}
		retornacontrol();
	}

	function mantener_sesion() {
		xajax_sesion_mantenerV4();
	}
	setInterval('xajax_sesion_abandona_V2();', 60000);

	function AyudaLocal(sCampo) {
		var divAyuda = document.getElementById('div_ayuda_' + sCampo);
		if (typeof divAyuda === 'undefined') {} else {
			verboton('cmdAyuda_' + sCampo, 'none');
			var sMensaje = 'Lo que quiera decir.';
			//if (sCampo=='sNombreCampo'){sMensaje='Mensaje para otro campo.';}
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
		if (confirm("Esta seguro de cambiar el consecutivo?")) {
			expandesector(98);
			window.document.frmedita.paso.value = 93;
			window.document.frmedita.submit();
		}
	}

	function f3041_InfoInteresado() {
		window.document.frmedita.cara23idtercero.value = window.document.frmedita.saiu41idestudiante.value;
		var params = new Array();
		params[91] = window.document.frmedita.paginaf3042.value;
		params[92] = window.document.frmedita.lppf3042.value;
		params[93] = window.document.frmedita.saiu41id.value;
		params[99] = window.document.frmedita.debug.value;
		params[100] = <?php echo $idTercero; ?>;
		params[101] = window.document.frmedita.paginaf304111.value;
		params[102] = window.document.frmedita.lppf304111.value;
		params[103] = window.document.frmedita.saiu41idestudiante.value;
		document.getElementById('div_f3041academico').innerHTML = '<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf304111" name="paginaf304111" type="hidden" value="' + params[101] + '" /><input id="lppf304111" name="lppf304111" type="hidden" value="' + params[102] + '" />';
		xajax_f3041_HtmlTablaInfoAcademico(params);
		document.getElementById('div_f3042detalle').innerHTML = '<input id="paginaf3042" name="paginaf3042" type="hidden" value="' + params[101] + '" /><input id="lppf3042" name="lppf3042" type="hidden" value="' + params[102] + '" />';
		var params = new Array();
		params[0] = window.document.frmedita.cara23idtercero.value;
		params[99] = window.document.frmedita.debug.value;
		params[100] = window.document.frmedita.id11.value;
		params[101] = window.document.frmedita.paginaf2343.value;
		params[102] = window.document.frmedita.lppf2343.value;
		document.getElementById('div_f2343detalle').innerHTML = '<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf2343" name="paginaf2343" type="hidden" value="' + params[101] + '" /><input id="lppf2343" name="lppf2343" type="hidden" value="' + params[102] + '" />';
		xajax_f2343_HtmlTabla(params);
	}

	function paginarf3042() {
		var params = new Array();
		params[91] = window.document.frmedita.paginaf3042.value;
		params[92] = window.document.frmedita.lppf3042.value;
		params[93] = window.document.frmedita.saiu41id.value;
		params[99] = window.document.frmedita.debug.value;
		params[100] = window.document.frmedita.id11.value;
		params[103] = window.document.frmedita.saiu41idestudiante.value;
		document.getElementById('div_f3042detalle').innerHTML = '<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf3042" name="paginaf3042" type="hidden" value="' + params[101] + '" /><input id="lppf3042" name="lppf3042" type="hidden" value="' + params[102] + '" />';
		xajax_f3042_HtmlTabla(params);
	}

	function llevafoco(sCampo) {
		document.getElementById(sCampo).focus();
	}

	function nuevo3041(idtercero) {
		if (window.document.frmedita.paso.value == 0) {
			ter_traerxid('saiu41idestudiante', idtercero);
			expandepanel(3041, 'block', 0);
			window.scrollTo(0, 200);
			setTimeout(function() {
				llevafoco('saiu41tipocontacto');
			}, 200);
		} else {
			MensajeAlarmaV2('Debe limpiar la pantalla.', 1);
		}
	}

	function verinfopersonal(id) {
		var params = new Array();
		params[1] = id;
		document.getElementById('div_infopersonal').innerHTML = '<b>Procesando datos, por favor espere...</b>';
		xajax_f236_TraerInfoPersonal(params);
	}

	function advertencia_retiro() {
		if (window.document.frmedita.saiu41seretira.value == 1) {
			ModalConfirm('<?php echo $ETI['msg_confirmaretirar']; ?>');
			ModalDialogConfirm(function(confirm) {
				if (confirm) {} else {
					window.document.frmedita.saiu41seretira.value = 0;
				}
			});
		}
	}
</script>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>jsi/js2343.js?v=2"></script>
<?php
if ($_REQUEST['paso'] != 0) {
?>
<form id="frmimpp" name="frmimpp" method="post" action="<?php echo $APP->rutacomun; ?>p3041.php" target="_blank">
<input id="r" name="r" type="hidden" value="3041" />
<input id="id3041" name="id3041" type="hidden" value="<?php echo $_REQUEST['saiu41id']; ?>" />
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
<input id="ihoy" name="ihoy" type="hidden" value="<?php echo fecha_DiaMod(); ?>" />
<input id="shora" name="shora" type="hidden" value="<?php echo fecha_hora(); ?>" />
<input id="stipodoc" name="stipodoc" type="hidden" value="<?php echo $APP->tipo_doc; ?>" />
<input id="idusuario" name="idusuario" type="hidden" value="<?php echo $_SESSION['unad_id_tercero']; ?>" />
<input id="id11" name="id11" type="hidden" value="<?php echo $idTercero; ?>" />
<input id="ipiel" name="ipiel" type="hidden" value="<?php echo $iPiel; ?>" />
<input id="seg_5" name="seg_5" type="hidden" value="<?php echo $seg_5; ?>" />
<input id="seg_6" name="seg_6" type="hidden" value="<?php echo $seg_6; ?>" />
<input id="cara23idtercero" name="cara23idtercero" type="hidden" value="<?php echo $_REQUEST['cara23idtercero']; ?>" />
<input id="contenedor" name="contenedor" type="hidden" value="" />
<div id="div_sector1">
<div class="titulos">
<div class="titulosD">
<input id="cmdAyuda" name="cmdAyuda" type="button" class="btUpAyuda" onclick="muestraayuda(<?php echo $APP->idsistema . ', ' . $iCodModulo; ?>);" title="<?php echo $ETI['bt_ayuda']; ?>" value="<?php echo $ETI['bt_ayuda']; ?>" />
<?php
if ($_REQUEST['paso'] == 2) {
if ($_REQUEST['saiu41cerrada'] == 0) {
?>
<input id="cmdEliminar" name="cmdEliminar" type="button" class="btUpEliminar" onclick="eliminadato();" title="<?php echo $ETI['bt_eliminar']; ?>" value="<?php echo $ETI['bt_eliminar']; ?>" />
<?php
}
}
$bHayImprimir = false;
$sScript = 'imprimelista()';
$sClaseBoton = 'btEnviarExcel';
if ($seg_6 == 1) {
$bHayImprimir = true;
}
if ($_REQUEST['paso'] != 0) {
if ($seg_5 == 1) {
if ($_REQUEST['saiu41cerrada'] == 1) {
//$bHayImprimir=true;
//$sScript='imprimep()';
//if ($iNumFormatosImprime>0){
//$sScript='expandesector(94)';
//}
//$sClaseBoton='btEnviarPDF'; //btUpPrint
//if ($id_rpt!=0){$sScript='verrpt()';}
}
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
if ($_REQUEST['saiu41cerrada'] == 0) {
?>
<input id="cmdGuardar" name="cmdGuardar" type="button" class="btUpGuardar" onclick="enviaguardar();" title="<?php echo $ETI['bt_guardar']; ?>" value="<?php echo $ETI['bt_guardar']; ?>" />
<?php
if ($_REQUEST['paso'] > 0) {
?>
<input id="cmdCerrar" name="cmdCerrar" type="button" class="btSupCerrar" onClick="enviacerrar();" title="Cerrar" value="Cerrar" />
<?php
}
} else {
if ($_REQUEST['paso'] > 0) {
if ($bPuedeAbrir) {
?>
<input id="cmdAbrir" name="cmdAbrir" type="button" class="btSupAbrir" onclick="enviaabrir();" title="Abrir" value="Abrir" />
<?php
}
}
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
echo '<h2>' . $ETI['titulo_3041'] . '</h2>';
?>
</div>
</div>
<div class="areaform">
<div class="areatrabajo">
<?php
//Div para ocultar
$bConExpande = true;
if ($bConExpande) {
?>
<div class="ir_derecha" <?php echo $sAnchoExpandeContrae; ?>>
<input id="boculta3041" name="boculta3041" type="hidden" value="<?php echo $_REQUEST['boculta3041']; ?>" />
<label class="Label30">
<input id="btexpande3041" name="btexpande3041" type="button" value="Mostrar" class="btMiniExpandir" onclick="expandepanel(3041,'block',0);" title="<?php echo $ETI['bt_mostrar']; ?>" style="display:<?php if ($_REQUEST['boculta3041'] == 0) {
echo 'none';
} else {
echo 'block';
} ?>;" />
</label>
<label class="Label30">
<input id="btrecoge3041" name="btrecoge3041" type="button" value="Ocultar" class="btMiniRecoger" onclick="expandepanel(3041,'none',1);" title="<?php echo $ETI['bt_ocultar']; ?>" style="display:<?php if ($_REQUEST['boculta3041'] == 0) {
echo 'block';
} else {
echo 'none';
} ?>;" />
</label>
</div>
<div id="div_p3041" style="display:<?php if ($_REQUEST['boculta3041'] == 0) {
echo 'block';
} else {
echo 'none';
} ?>;">
<?php
}
//Mostrar formulario para editar
?>
<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo $ETI['saiu41idestudiante'];
?>
</label>
<div class="salto1px"></div>
<input id="saiu41idestudiante" name="saiu41idestudiante" type="hidden" value="<?php echo $_REQUEST['saiu41idestudiante']; ?>" />
<div id="div_saiu41idestudiante_llaves">
<?php
$bOculto = true;
if ($_REQUEST['paso'] != 2) {
$bOculto = false;
}
echo html_DivTerceroV2('saiu41idestudiante', $_REQUEST['saiu41idestudiante_td'], $_REQUEST['saiu41idestudiante_doc'], $bOculto, 1, $ETI['ing_doc']);
?>
</div>
<div class="salto1px"></div>
<div id="div_saiu41idestudiante" class="L"><?php echo $saiu41idestudiante_rs; ?></div>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu41consec'];
?>
</label>
<label class="Label130">
<?php
if ($_REQUEST['paso'] != 2) {
?>
<input id="saiu41consec" name="saiu41consec" type="text" value="<?php echo $_REQUEST['saiu41consec']; ?>" onchange="RevisaLlave()" class="cuatro" />
<?php
} else {
echo html_oculto('saiu41consec', $_REQUEST['saiu41consec'], formato_numero($_REQUEST['saiu41consec']));
}
?>
</label>
<?php
/*
if ($seg_8==1){
$objForma=new clsHtmlForma($iPiel);
echo $objForma->htmlBotonSolo('cmdCambiaConsec', 'btMiniActualizar', 'expandesector(93);', $ETI['bt_cambiar'], 30);
echo '<label class="Label30">&nbsp;</label>';
}
*/
?>
<label class="Label60">
<?php
echo $ETI['saiu41id'];
?>
</label>
<label class="Label60">
<?php
echo html_oculto('saiu41id', $_REQUEST['saiu41id'], formato_numero($_REQUEST['saiu41id']));
?>
</label>
<div class="salto1px"></div>
<div id="div_infopersonal">
<?php
echo $html_personal;
?>
</div>
<div class="salto1px"></div>
<div id="div_contacto">
<?php
echo $html_InfoContacto;
?>
</div>
<div class="salto1px"></div>
</div>

<div class="GrupoCampos520">
<label class="Label130">
<?php
echo $ETI['saiu41tipocontacto'];
?>
</label>
<label>
<?php
echo $html_saiu41tipocontacto;
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu41fecha'];
?>
</label>
<div class="Campo220">
<?php
echo html_FechaEnNumero('saiu41fecha', $_REQUEST['saiu41fecha'], false, '', 2020, $iAgnoFin); //$bvacio=false,$accion=",$iagnoini=0,$iagnofin=0
?>
</div>
<!--
<label class="Label30">
<input id="bsaiu41fecha_hoy" name="bsaiu41fecha_hoy" type="button" value="Hoy" class="btMiniHoy" onclick="fecha_AsignarNum('saiu41fecha','<?php echo fecha_DiaMod(); ?>')" title="<?php echo $ETI['bt_hoy']; ?>"/>
</label>
-->
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu41cerrada'];
?>
</label>
<label class="Label90">
<?php
$et_saiu41cerrada = $ETI['msg_abierto'];
if ($_REQUEST['saiu41cerrada'] == 1) {
$et_saiu41cerrada = $ETI['msg_cerrado'];
}
echo html_oculto('saiu41cerrada', $_REQUEST['saiu41cerrada'], $et_saiu41cerrada);
?>
</label>
<div class="salto1px"></div>
<label class="Label200">
<?php
echo $ETI['saiu41visiblealest'];
?>
</label>
<label class="Label60">
<?php
echo $html_saiu41visiblealest;
?>
</label>
<div class="salto1px"></div>
</div>

<div class="salto1px"></div>
<div id="div_f3041academico">
<?php
echo $sTabla304Acad;
?>
</div>

<div class="salto1px"></div>
<div class="GrupoCampos520">
<label class="Label90">
<?php
echo $ETI['saiu41idperiodo'];
?>
</label>
<label>
<div id="div_saiu41idperiodo">
<?php
echo $html_saiu41idperiodo;
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label90">
<?php
echo $ETI['saiu41idcurso'];
?>
</label>
<label>
<div id="div_saiu41idcurso">
<?php
echo $html_saiu41idcurso;
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label90">
<?php
echo $ETI['saiu41idactividad'];
?>
</label>
<label>
<div id="div_saiu41idactividad">
<?php
echo $html_saiu41idactividad;
?>
</div>
</label>
<div class="salto1px"></div>
</div>

<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo $ETI['saiu41idtutor'];
?>
</label>
<div class="salto1px"></div>
<input id="saiu41idtutor" name="saiu41idtutor" type="hidden" value="<?php echo $_REQUEST['saiu41idtutor']; ?>" />
<div id="div_saiu41idtutor_llaves">
<?php
$bOculto = true;
echo html_DivTerceroV2('saiu41idtutor', $_REQUEST['saiu41idtutor_td'], $_REQUEST['saiu41idtutor_doc'], $bOculto, 0, $ETI['ing_doc']);
?>
</div>
<div class="salto1px"></div>
<div id="div_saiu41idtutor" class="L"><?php echo $saiu41idtutor_rs; ?></div>
<div class="salto1px"></div>
</div>
<?php
// -- Inicia Grupo campos 2343 Alertas
?>
<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo $ETI['titulo_2343'];
?>
</label>
<input id="boculta2343" name="boculta2343" type="hidden" value="<?php echo $_REQUEST['boculta2343']; ?>" />
<?php
if (true) {
?>
<div class="ir_derecha" <?php echo $sAnchoExpandeContrae; ?>>
<!--
<label class="Label30">
<input id="btexcel2343" name="btexcel2343" type="button" value="Exportar" class="btMiniExcel" onclick="imprime2343();" title="Exportar"/>
</label>
-->
<label class="Label30">
<input id="btexpande2343" name="btexpande2343" type="button" value="Expandir" class="btMiniExpandir" onclick="expandepanel(2343,'block',0);" title="<?php echo $ETI['bt_mostrar']; ?>" style="display:<?php if ($_REQUEST['boculta2343'] == 0) {
echo 'none';
} else {
echo 'block';
} ?>;" />
</label>
<label class="Label30">
<input id="btrecoge2343" name="btrecoge2343" type="button" value="Recoger" class="btMiniRecoger" onclick="expandepanel(2343,'none',1);" title="<?php echo $ETI['bt_ocultar']; ?>" style="display:<?php if ($_REQUEST['boculta2343'] == 0) {
echo 'block';
} else {
echo 'none';
} ?>;" />
</label>
</div>
<div class="salto1px"></div>
<div id="div_p2343" style="display:<?php if ($_REQUEST['boculta2343'] == 0) {
echo 'block';
} else {
echo 'none';
} ?>;">
<label class="Label130">
<?php
echo $ETI['cara43consec'];
?>
</label>
<label class="Label130">
<div id="div_cara43consec">
<?php
if ((int)$_REQUEST['cara43id'] == 0) {
?>
<input id="cara43consec" name="cara43consec" type="text" value="<?php echo $_REQUEST['cara43consec']; ?>" onchange="revisaf2343()" class="cuatro" />
<?php
} else {
echo html_oculto('cara43consec', $_REQUEST['cara43consec'], formato_numero($_REQUEST['cara43consec']));
}
?>
</div>
</label>
<label class="Label60">
<?php
echo $ETI['cara43id'];
?>
</label>
<label class="Label60">
<div id="div_cara43id">
<?php
echo html_oculto('cara43id', $_REQUEST['cara43id'], formato_numero($_REQUEST['cara43id']));
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['cara43idalerta'];
?>
</label>
<label>
<?php
echo $html_cara43idalerta;
?>
</label>
<input id="cara43fecharegistro" name="cara43fecharegistro" type="hidden" value="<?php echo $_REQUEST['cara43fecharegistro']; ?>" />
<input id="cara43idregistro" name="cara43idregistro" type="hidden" value="<?php echo $_REQUEST['cara43idregistro']; ?>" />
<input id="cara43fechacierre" name="cara43fechacierre" type="hidden" value="<?php echo $_REQUEST['cara43fechacierre']; ?>" />
<input id="cara43idcierre" name="cara43idcierre" type="hidden" value="<?php echo $_REQUEST['cara43idcierre']; ?>" />
<div class="salto1px"></div>
<label class="Label130">&nbsp;</label>
<label class="Label30">
<input id="bguarda2343" name="bguarda2343" type="button" value="Guardar" class="btMiniGuardar" onclick="guardaf2343()" title="<?php echo $ETI['bt_mini_guardar_2343']; ?>" />
</label>
<label class="Label30">
<input id="blimpia2343" name="blimpia2343" type="button" value="Limpiar" class="btMiniLimpiar" onclick="limpiaf2343()" title="<?php echo $ETI['bt_mini_limpiar_2343']; ?>" />
</label>
<label class="Label30">
<input id="belimina2343" name="belimina2343" type="button" value="Eliminar" class="btMiniEliminar" onclick="eliminaf2343()" title="<?php echo $ETI['bt_mini_eliminar_2343']; ?>" style="display:<?php if ((int)$_REQUEST['cara43id'] != 0) {
echo 'block';
} else {
echo 'none';
} ?>;" />
</label>
<?php
//Este es el cierre del div_p2343
?>
<div class="salto1px"></div>
</div>
<?php
?>
<div class="salto1px"></div>
<div id="div_f2343detalle">
<?php
echo $sTabla2343;
?>
</div>
<?php
}
?>
<div class="salto1px"></div>
</div>
<?php
// -- Termina Grupo campos 2343 Alertas
?>

<div class="salto5px"></div>
<label class="Label220">
<?php
echo $ETI['saiu41motivocontacto'];
?>
</label>
<label>
<?php
echo $html_saiu41motivocontacto;
?>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['saiu41contacto_efectivo'];
?>
</label>
<label class="Label60">
<?php
echo $html_saiu41contacto_efectivo;
?>
</label>
<label class="Label160">
<?php
echo $ETI['saiu41contacto_forma'];
?>
</label>
<label>
<?php
echo $html_saiu41contacto_forma;
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu41acciones'];
?>
</label>
<label>
<?php
echo $html_saiu41acciones;
?>
</label>
<label class="txtAreaS">
<?php
echo $ETI['saiu41contacto_observa'];
?>
<textarea id="saiu41contacto_observa" name="saiu41contacto_observa" placeholder="<?php echo $ETI['ing_campo'] . $ETI['saiu41contacto_observa']; ?>"><?php echo $_REQUEST['saiu41contacto_observa']; ?></textarea>
</label>

<label class="Label130">
<?php
echo $ETI['saiu41resultados'];
?>
</label>
<label>
<?php
echo $html_saiu41resultados;
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu41seretira'];
?>
</label>
<label class="Label60">
<?php
echo $html_saiu41seretira;
?>
</label>
<label class="Label220">
<?php
echo $ETI['saiu41factorprincipaldesc'];
?>
</label>
<label>
<?php
echo $html_saiu41factorprincipaldesc;
?>
</label>
<?php
// -- Inicia Grupo campos 2323 Acompanamiento
?>
<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo" style="width:450px">
<?php
echo $ETI['titulo_2323res'];
?>
</label>
<input id="boculta2323" name="boculta2323" type="hidden" value="<?php echo $_REQUEST['boculta2323']; ?>" />
<div class="salto1px"></div>
<?php
?>
<div id="div_f2323detalleRes">
<?php
echo $sTabla2323;
?>
</div>
<div class="salto1px"></div>
</div>
<?php
// -- Termina Grupo campos 2323 Acompanamiento
?>
<?php
// -- Inicia Grupo campos 3042 Historico
?>
<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo $ETI['titulo_3042'];
?>
</label>
<input id="boculta3042" name="boculta3042" type="hidden" value="<?php echo $_REQUEST['boculta3042']; ?>" />
<div class="salto1px"></div>
<div id="div_f3042detalle">
<?php
echo $sTabla3042;
?>
</div>
<div class="salto1px"></div>
</div>
<?php
// -- Termina Grupo campos 3042 Historico
?>
<?php
if (false) {
//Ejemplo de boton de ayuda
//echo html_BotonAyuda('NombreCampo');
//echo html_DivAyudaLocal('NombreCampo');
}
if ($bConExpande) {
//Este es el cierre del div_p3041
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
echo '<h3>' . $ETI['bloque1'] . '</h3>';
?>
</div>
<div class="areatrabajo">
<div class="ir_derecha">
<label class="Label90">
<?php
echo $ETI['msg_bdoc'];
?>
</label>
<label>
<input id="bdoc" name="bdoc" type="text" value="<?php echo $_REQUEST['bdoc']; ?>" onchange="paginarf3041()" autocomplete="off" />
</label>
<label class="Label90">
<?php
echo $ETI['msg_bnombre'];
?>
</label>
<label>
<input id="bnombre" name="bnombre" type="text" value="<?php echo $_REQUEST['bnombre']; ?>" onchange="paginarf3041()" autocomplete="off" />
</label>
<div class="salto1px"></div>
<label class="Label90">
<?php
echo $ETI['saiu41idperiodo'];
?>
</label>
<label class="Label130">
<?php
echo $html_bperiodo;
?>
</label>
<div class="salto1px"></div>
<label class="Label90">
<?php
echo $ETI['saiu41idcurso'];
?>
</label>
<label>
<input id="bcodcurso" name="bcodcurso" type="text" value="<?php echo $_REQUEST['bcodcurso']; ?>" onchange="paginarf3041()" autocomplete="off" />
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
echo ' ' . $csv_separa;
?>
<div id="div_f3041detalle">
<?php
echo $sTabla3041;
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
echo $ETI['msg_saiu41consec'];
?>
</label>
<label class="Label90">
<?php
echo '<b>' . $_REQUEST['saiu41consec'] . '</b>';
?>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['msg_saiu41consec_nuevo'];
// onchange="RevisaConsec()"
?>
</label>
<label class="Label90">
<input id="saiu41consec_nuevo" name="saiu41consec_nuevo" type="text" value="<?php echo $_REQUEST['saiu41consec_nuevo']; ?>" class="cuatro" />
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
<input id="titulo_3041" name="titulo_3041" type="hidden" value="<?php echo $ETI['titulo_3041']; ?>" />
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
echo '<h2>' . $ETI['titulo_3041'] . '</h2>';
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
echo '<h2>' . $ETI['titulo_3041'] . '</h2>';
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
$iSegundos = $iSegFin - $iSegIni;
echo '<div class="salto1px"></div><div class="GrupoCampos" id="div_debug">' . $sDebug . fecha_microtiempo() . ' Tiempo total del proceso: <b>' . $iSegundos . '</b> Segundos' . '<div class="salto1px"></div></div>';
}
?>
<input id="scampobusca" name="scampobusca" type="hidden" value="" />
<input id="iscroll" name="iscroll" type="hidden" value="<?php echo $_REQUEST['iscroll']; ?>" />
<input id="itipoerror" name="itipoerror" type="hidden" value="<?php echo $iTipoError; ?>" />
<input id="debug" name="debug" type="hidden" value="<?php echo $_REQUEST['debug']; ?>" />
</form>
</div><!-- /DIV_interna -->
<div class="flotante">
<?php
if ($_REQUEST['saiu41cerrada'] == 0) {
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
		echo 'setTimeout(function(){expandesector(' . $iSector . ');}, 10);
';
	}
	if ($bMueveScroll) {
		echo 'setTimeout(function(){retornacontrol();}, 2);
';
	}
	?>
</script>
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>js/jquery.autocomplete.css" type="text/css" />
<script language="javascript" src="<?php echo $APP->rutacomun; ?>js/jquery.autocomplete.js"></script>
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>js/chosen.css" type="text/css" />
<script language="javascript" src="<?php echo $APP->rutacomun; ?>js/chosen.jquery.js"></script>
<script language="javascript">
	$().ready(function() {
		$("#saiu41factorprincipaldesc").chosen();
		$("#saiu41contacto_forma").chosen();
		$("#saiu41motivocontacto").chosen();
		$("#saiu41acciones").chosen();
		$("#saiu41resultados").chosen();
		$("#bperiodo").chosen();
	});
</script>
<script language="javascript" src="ac_3041.js"></script>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>unad_todas.js?ver=8"></script>
<?php
forma_piedepagina();
?>