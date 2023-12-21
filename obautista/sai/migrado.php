<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2023 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- © William Jammirlhey Rico Ruiz - UNAD - 2023 ---
--- william.rico@unad.edu.co - http://www.unad.edu.co 
--- Modelo Versión 2.30.1 lunes, 14 de agosto de 2023
*/

/** Archivo migrado.php.
 * Modulo 2741 grad41postulaciones.
 * @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
 * @modified William Jamirlhey Rico Ruiz - william.rico@unad.edu.co
 * @param debug=1 (Opcional), bandera para indicar si se generan datos de depuración
 * @date lunes, 14 de agosto de 2023
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
$bEnSesion = false;
if ((int)$_SESSION['unad_id_tercero'] > 0) {
	$bEnSesion = true;
}
if (!$bEnSesion) {
	if ($bDebug) {
		echo 'No se encuentra una sesi&oacute;n. [' . $APP->rutacomun . ']-[' . $_SESSION['unad_id_tercero'] . ']';
		die();
	}
	header('Location:index.php');
	die();
}
$grupo_id = 1; //Necesita ajustarlo...
$iCodModulo = 2741;
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
$mensajes_2741 = $APP->rutacomun . 'lg/lg_2741_' . $_SESSION['unad_idioma'] . '.php';
if (!file_exists($mensajes_2741)) {
	$mensajes_2741 = $APP->rutacomun . 'lg/lg_2741_es.php';
}
require $mensajes_todas;
require $mensajes_2741;
$xajax = NULL;
$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
if ($APP->dbpuerto != '') {
	$objDB->dbPuerto = $APP->dbpuerto;
}
$iPiel = iDefinirPiel($APP, 2);
$sAnchoExpandeContrae = ' style="width:62px;"';
$sOcultaId = ' style="display:none;"';
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
	$bDevuelve = true;
	//list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 1, $_SESSION['unad_id_tercero'], $objDB);
	if (!$bDevuelve) {
		$bCerrado = true;
		$sMsgCierre = '<div class="MarquesinaGrande">No cuenta con permiso para acceder a este modulo [' . $iCodModulo . '].</div>';
		list($et_menu, $sDebugM) = html_menuV2($APP->idsistema, $objDB, $iPiel, false, $_SESSION['unad_id_tercero']);
	}
}
if ($bCerrado) {
	$objDB->CerrarConexion();
	require $APP->rutacomun . 'unad_forma_v2.php';
	forma_cabeceraV3($xajax, $ETI['titulo_migrado']);
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
		header('Location:noticia.php?ret=migrado.php');
		die();
	}
}
$idTercero = $_SESSION['unad_id_tercero'];
$bOtroUsuario = false;
$seg_1707 = 0;
$bDevuelve = false;
list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 1707, $_SESSION['unad_id_tercero'], $objDB, $bDebug);
$sDebug = $sDebug . $sDebugP;
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
$mensajes_2742 = $APP->rutacomun . 'lg/lg_2742_' . $_SESSION['unad_idioma'] . '.php';
if (!file_exists($mensajes_2742)) {
	$mensajes_2742 = $APP->rutacomun . 'lg/lg_2742_es.php';
}
$mensajes_2743 = $APP->rutacomun . 'lg/lg_2743_' . $_SESSION['unad_idioma'] . '.php';
if (!file_exists($mensajes_2743)) {
	$mensajes_2743 = $APP->rutacomun . 'lg/lg_2743_es.php';
}
$mensajes_2744 = $APP->rutacomun . 'lg/lg_2744_' . $_SESSION['unad_idioma'] . '.php';
if (!file_exists($mensajes_2744)) {
	$mensajes_2744 = $APP->rutacomun . 'lg/lg_2744_es.php';
}
require $mensajes_2742;
require $mensajes_2743;
require $mensajes_2744;
// -- Si esta cargando la pagina por primer vez se revisa si requiere auditar y se manda a hacer un limpiar (paso -1)
if (isset($_REQUEST['paso']) == 0) {
	$_REQUEST['paso'] = -1;
	if ($audita[1]) {
		seg_auditaingreso($iCodModulo, $_SESSION['unad_id_tercero'], $objDB);
	}
}
// -- 2741 grad41postulaciones
require $APP->rutacomun . 'lib2741.php';
// -- 2742 Anexos
require $APP->rutacomun . 'lib2742.php';
// -- 2743 Anotaciones
require $APP->rutacomun . 'lib2743.php';
// -- 2744 Cambio de estado
require $APP->rutacomun . 'lib2744.php';
$xajax = new xajax();
$xajax->configure('javascript URI', $APP->rutacomun . 'xajax/');
$xajax->register(XAJAX_FUNCTION, 'unad11_Mostrar_v2');
$xajax->register(XAJAX_FUNCTION, 'unad11_TraerXid');
$xajax->register(XAJAX_FUNCTION, 'f2741_Combograd41idescuela');
$xajax->register(XAJAX_FUNCTION, 'f2741_Combograd41idprograma');
$xajax->register(XAJAX_FUNCTION, 'f2741_Combograd41idpei');
$xajax->register(XAJAX_FUNCTION, 'f2741_Combograd41cerem_modalidad');
$xajax->register(XAJAX_FUNCTION, 'f2741_Combograd41idzona');
$xajax->register(XAJAX_FUNCTION, 'f2741_Combograd41cerem_centro');
$xajax->register(XAJAX_FUNCTION, 'f2741_Combograd41lugardiploma');
$xajax->register(XAJAX_FUNCTION, 'f2741_Combobcentro');
$xajax->register(XAJAX_FUNCTION, 'sesion_abandona_V2');
$xajax->register(XAJAX_FUNCTION, 'sesion_mantenerV4');
$xajax->register(XAJAX_FUNCTION, 'f2741_HtmlTabla');
$xajax->register(XAJAX_FUNCTION, 'f2741_ExisteDato');
$xajax->register(XAJAX_FUNCTION, 'f2741_Busquedas');
$xajax->register(XAJAX_FUNCTION, 'f2741_HtmlBusqueda');
$xajax->register(XAJAX_FUNCTION, 'elimina_archivo_grad42idarchivo');
$xajax->register(XAJAX_FUNCTION, 'f2742_Guardar');
$xajax->register(XAJAX_FUNCTION, 'f2742_Traer');
$xajax->register(XAJAX_FUNCTION, 'f2742_Eliminar');
$xajax->register(XAJAX_FUNCTION, 'f2742_HtmlTabla');
$xajax->register(XAJAX_FUNCTION, 'f2742_PintarLlaves');
$xajax->register(XAJAX_FUNCTION, 'f2743_Guardar');
$xajax->register(XAJAX_FUNCTION, 'f2743_Traer');
$xajax->register(XAJAX_FUNCTION, 'f2743_Eliminar');
$xajax->register(XAJAX_FUNCTION, 'f2743_HtmlTabla');
$xajax->register(XAJAX_FUNCTION, 'f2743_PintarLlaves');
$xajax->register(XAJAX_FUNCTION, 'f2744_Guardar');
$xajax->register(XAJAX_FUNCTION, 'f2744_Traer');
$xajax->register(XAJAX_FUNCTION, 'f2744_Eliminar');
$xajax->register(XAJAX_FUNCTION, 'f2744_HtmlTabla');
$xajax->register(XAJAX_FUNCTION, 'f2744_PintarLlaves');
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
if (isset($_REQUEST['paginaf2741']) == 0) {
	$_REQUEST['paginaf2741'] = 1;
}
if (isset($_REQUEST['lppf2741']) == 0) {
	$_REQUEST['lppf2741'] = 20;
}
if (isset($_REQUEST['boculta2741']) == 0) {
	$_REQUEST['boculta2741'] = 0;
}
if (isset($_REQUEST['paginaf2742']) == 0) {
	$_REQUEST['paginaf2742'] = 1;
}
if (isset($_REQUEST['lppf2742']) == 0) {
	$_REQUEST['lppf2742'] = 20;
}
if (isset($_REQUEST['boculta2742']) == 0) {
	$_REQUEST['boculta2742'] = 0;
}
if (isset($_REQUEST['paginaf2743']) == 0) {
	$_REQUEST['paginaf2743'] = 1;
}
if (isset($_REQUEST['lppf2743']) == 0) {
	$_REQUEST['lppf2743'] = 20;
}
if (isset($_REQUEST['boculta2743']) == 0) {
	$_REQUEST['boculta2743'] = 0;
}
if (isset($_REQUEST['paginaf2744']) == 0) {
	$_REQUEST['paginaf2744'] = 1;
}
if (isset($_REQUEST['lppf2744']) == 0) {
	$_REQUEST['lppf2744'] = 20;
}
if (isset($_REQUEST['boculta2744']) == 0) {
	$_REQUEST['boculta2744'] = 0;
}
// -- Inicializar variables de datos.
if (isset($_REQUEST['grad41consec']) == 0) {
	$_REQUEST['grad41consec'] = '';
}
if (isset($_REQUEST['grad41id']) == 0) {
	$_REQUEST['grad41id'] = '';
}
if (isset($_REQUEST['grad41idtercero']) == 0) {
	// $_REQUEST['grad41idtercero'] = 0;
	$_REQUEST['grad41idtercero'] = $idTercero;
}
if (isset($_REQUEST['grad41idtercero_td']) == 0) {
	$_REQUEST['grad41idtercero_td'] = $APP->tipo_doc;
}
if (isset($_REQUEST['grad41idtercero_doc']) == 0) {
	$_REQUEST['grad41idtercero_doc'] = '';
}
if (isset($_REQUEST['grad41idescuela']) == 0) {
	$_REQUEST['grad41idescuela'] = '';
}
if (isset($_REQUEST['grad41idprograma']) == 0) {
	$_REQUEST['grad41idprograma'] = '';
}
if (isset($_REQUEST['grad41idpei']) == 0) {
	$_REQUEST['grad41idpei'] = '';
}
if (isset($_REQUEST['grad41idfecha']) == 0) {
	$_REQUEST['grad41idfecha'] = $iHoy;
	//$_REQUEST['grad41idfecha'] = '';
}
if (isset($_REQUEST['grad41tipogrado']) == 0) {
	$_REQUEST['grad41tipogrado'] = 1;
}
if (isset($_REQUEST['grad41idcohorte']) == 0) {
	$_REQUEST['grad41idcohorte'] = '';
}
if (isset($_REQUEST['grad41idestado']) == 0) {
	$_REQUEST['grad41idestado'] = 0;
}
if (isset($_REQUEST['grad41fechaaprueba']) == 0) {
	$_REQUEST['grad41fechaaprueba'] = 0;
	//$_REQUEST['grad41fechaaprueba'] = $iHoy;
}
if (isset($_REQUEST['grad41idrecibopago']) == 0) {
	$_REQUEST['grad41idrecibopago'] = 0;
}
if (isset($_REQUEST['grad41agnopresenta']) == 0) {
	$_REQUEST['grad41agnopresenta'] = '';
}
if (isset($_REQUEST['grad41codigoicfes']) == 0) {
	$_REQUEST['grad41codigoicfes'] = '';
}
if (isset($_REQUEST['grad41cerem_modalidad']) == 0) {
	$_REQUEST['grad41cerem_modalidad'] = 0;
}
if (isset($_REQUEST['grad41cerem_centro']) == 0) {
	$_REQUEST['grad41cerem_centro'] = '';
}
if (isset($_REQUEST['grad41libro_num']) == 0) {
	$_REQUEST['grad41libro_num'] = 0;
}
if (isset($_REQUEST['grad41libro_folio']) == 0) {
	$_REQUEST['grad41libro_folio'] = 0;
}
if (isset($_REQUEST['grad41libro_acta']) == 0) {
	$_REQUEST['grad41libro_acta'] = 0;
}
if (isset($_REQUEST['grad41idzona']) == 0) {
	$_REQUEST['grad41idzona'] = '';
}
if (isset($_REQUEST['grad41lugardiploma']) == 0) {
	$_REQUEST['grad41lugardiploma'] = '';
}
if (isset($_REQUEST['grad41enc_tipo']) == 0) {
	$_REQUEST['grad41enc_tipo'] = '';
}
if (isset($_REQUEST['grad41enc_idregistro']) == 0) {
	$_REQUEST['grad41enc_idregistro'] = '';
}
$_REQUEST['grad41consec'] = numeros_validar($_REQUEST['grad41consec']);
$_REQUEST['grad41id'] = numeros_validar($_REQUEST['grad41id']);
$_REQUEST['grad41idtercero'] = numeros_validar($_REQUEST['grad41idtercero']);
$_REQUEST['grad41idtercero_td'] = cadena_Validar($_REQUEST['grad41idtercero_td']);
$_REQUEST['grad41idtercero_doc'] = cadena_Validar($_REQUEST['grad41idtercero_doc']);
$_REQUEST['grad41idescuela'] = numeros_validar($_REQUEST['grad41idescuela']);
$_REQUEST['grad41idprograma'] = numeros_validar($_REQUEST['grad41idprograma']);
$_REQUEST['grad41idpei'] = numeros_validar($_REQUEST['grad41idpei']);
$_REQUEST['grad41idfecha'] = numeros_validar($_REQUEST['grad41idfecha']);
$_REQUEST['grad41tipogrado'] = numeros_validar($_REQUEST['grad41tipogrado']);
$_REQUEST['grad41idcohorte'] = numeros_validar($_REQUEST['grad41idcohorte']);
$_REQUEST['grad41idestado'] = numeros_validar($_REQUEST['grad41idestado']);
$_REQUEST['grad41fechaaprueba'] = numeros_validar($_REQUEST['grad41fechaaprueba']);
$_REQUEST['grad41idrecibopago'] = numeros_validar($_REQUEST['grad41idrecibopago']);
$_REQUEST['grad41agnopresenta'] = numeros_validar($_REQUEST['grad41agnopresenta']);
$_REQUEST['grad41codigoicfes'] = cadena_Validar($_REQUEST['grad41codigoicfes']);
$_REQUEST['grad41cerem_modalidad'] = numeros_validar($_REQUEST['grad41cerem_modalidad']);
$_REQUEST['grad41cerem_centro'] = numeros_validar($_REQUEST['grad41cerem_centro']);
$_REQUEST['grad41libro_num'] = numeros_validar($_REQUEST['grad41libro_num']);
$_REQUEST['grad41libro_folio'] = numeros_validar($_REQUEST['grad41libro_folio']);
$_REQUEST['grad41libro_acta'] = numeros_validar($_REQUEST['grad41libro_acta']);
$_REQUEST['grad41idzona'] = numeros_validar($_REQUEST['grad41idzona']);
$_REQUEST['grad41lugardiploma'] = numeros_validar($_REQUEST['grad41lugardiploma']);
$_REQUEST['grad41enc_tipo'] = numeros_validar($_REQUEST['grad41enc_tipo']);
$_REQUEST['grad41enc_idregistro'] = numeros_validar($_REQUEST['grad41enc_idregistro']);
if ((int)$_REQUEST['paso'] > 0) {
	//Anexos
	if (isset($_REQUEST['grad42idpostulacion']) == 0) {
		$_REQUEST['grad42idpostulacion'] = '';
	}
	if (isset($_REQUEST['grad42iddocumento']) == 0) {
		$_REQUEST['grad42iddocumento'] = '';
	}
	if (isset($_REQUEST['grad42id']) == 0) {
		$_REQUEST['grad42id'] = '';
	}
	if (isset($_REQUEST['grad42idorigen']) == 0) {
		$_REQUEST['grad42idorigen'] = 0;
	}
	if (isset($_REQUEST['grad42idarchivo']) == 0) {
		$_REQUEST['grad42idarchivo'] = 0;
	}
	if (isset($_REQUEST['grad42fechadoc']) == 0) {
		$_REQUEST['grad42fechadoc'] = $iHoy;
		//$_REQUEST['grad42fechadoc'] = '';
	}
	if (isset($_REQUEST['grad42idaprueba']) == 0) {
		$_REQUEST['grad42idaprueba'] = 0;
		//$_REQUEST['grad42idaprueba'] =  $idTercero;
	}
	if (isset($_REQUEST['grad42idaprueba_td']) == 0) {
		$_REQUEST['grad42idaprueba_td'] = $APP->tipo_doc;
	}
	if (isset($_REQUEST['grad42idaprueba_doc']) == 0) {
		$_REQUEST['grad42idaprueba_doc'] = '';
	}
	if (isset($_REQUEST['grad42fechaaprueba']) == 0) {
		$_REQUEST['grad42fechaaprueba'] = 0;
		// $_REQUEST['grad42fechaaprueba'] = $iHoy;
	}
	$_REQUEST['grad42idpostulacion'] = numeros_validar($_REQUEST['grad42idpostulacion']);
	$_REQUEST['grad42iddocumento'] = numeros_validar($_REQUEST['grad42iddocumento']);
	$_REQUEST['grad42id'] = numeros_validar($_REQUEST['grad42id']);
	$_REQUEST['grad42idorigen'] = numeros_validar($_REQUEST['grad42idorigen']);
	$_REQUEST['grad42idarchivo'] = numeros_validar($_REQUEST['grad42idarchivo']);
	$_REQUEST['grad42fechadoc'] = numeros_validar($_REQUEST['grad42fechadoc']);
	$_REQUEST['grad42idaprueba'] = numeros_validar($_REQUEST['grad42idaprueba']);
	$_REQUEST['grad42idaprueba_td'] = cadena_Validar($_REQUEST['grad42idaprueba_td']);
	$_REQUEST['grad42idaprueba_doc'] = cadena_Validar($_REQUEST['grad42idaprueba_doc']);
	$_REQUEST['grad42fechaaprueba'] = numeros_validar($_REQUEST['grad42fechaaprueba']);
	//Anotaciones
	if (isset($_REQUEST['grad43idpostulacion']) == 0) {
		$_REQUEST['grad43idpostulacion'] = '';
	}
	if (isset($_REQUEST['grad43consec']) == 0) {
		$_REQUEST['grad43consec'] = '';
	}
	if (isset($_REQUEST['grad43id']) == 0) {
		$_REQUEST['grad43id'] = '';
	}
	if (isset($_REQUEST['grad43anotacion']) == 0) {
		$_REQUEST['grad43anotacion'] = '';
	}
	if (isset($_REQUEST['grad43idusuario']) == 0) {
		$_REQUEST['grad43idusuario'] = 0;
		//$_REQUEST['grad43idusuario'] =  $idTercero;
	}
	if (isset($_REQUEST['grad43idusuario_td']) == 0) {
		$_REQUEST['grad43idusuario_td'] = $APP->tipo_doc;
	}
	if (isset($_REQUEST['grad43idusuario_doc']) == 0) {
		$_REQUEST['grad43idusuario_doc'] = '';
	}
	if (isset($_REQUEST['grad43fecha']) == 0) {
		$_REQUEST['grad43fecha'] = $iHoy;
		//$_REQUEST['grad43fecha'] = '';
	}
	if (isset($_REQUEST['grad43hora']) == 0) {
		$_REQUEST['grad43hora'] = '';
	}
	if (isset($_REQUEST['grad43minuto']) == 0) {
		$_REQUEST['grad43minuto'] = '';
	}
	$_REQUEST['grad43idpostulacion'] = numeros_validar($_REQUEST['grad43idpostulacion']);
	$_REQUEST['grad43consec'] = numeros_validar($_REQUEST['grad43consec']);
	$_REQUEST['grad43id'] = numeros_validar($_REQUEST['grad43id']);
	$_REQUEST['grad43anotacion'] = cadena_Validar($_REQUEST['grad43anotacion']);
	$_REQUEST['grad43idusuario'] = numeros_validar($_REQUEST['grad43idusuario']);
	$_REQUEST['grad43idusuario_td'] = cadena_Validar($_REQUEST['grad43idusuario_td']);
	$_REQUEST['grad43idusuario_doc'] = cadena_Validar($_REQUEST['grad43idusuario_doc']);
	$_REQUEST['grad43fecha'] = numeros_validar($_REQUEST['grad43fecha']);
	$_REQUEST['grad43hora'] = numeros_validar($_REQUEST['grad43hora']);
	$_REQUEST['grad43minuto'] = numeros_validar($_REQUEST['grad43minuto']);
	//Cambio de estado
	if (isset($_REQUEST['grad44idpostulacion']) == 0) {
		$_REQUEST['grad44idpostulacion'] = '';
	}
	if (isset($_REQUEST['grad44consec']) == 0) {
		$_REQUEST['grad44consec'] = '';
	}
	if (isset($_REQUEST['grad44id']) == 0) {
		$_REQUEST['grad44id'] = '';
	}
	if (isset($_REQUEST['grad44idestadoorigen']) == 0) {
		$_REQUEST['grad44idestadoorigen'] = '';
	}
	if (isset($_REQUEST['grad44idestadofin']) == 0) {
		$_REQUEST['grad44idestadofin'] = '';
	}
	if (isset($_REQUEST['grad44nota']) == 0) {
		$_REQUEST['grad44nota'] = '';
	}
	if (isset($_REQUEST['grad44idusuario']) == 0) {
		$_REQUEST['grad44idusuario'] = 0;
		//$_REQUEST['grad44idusuario'] =  $idTercero;
	}
	if (isset($_REQUEST['grad44idusuario_td']) == 0) {
		$_REQUEST['grad44idusuario_td'] = $APP->tipo_doc;
	}
	if (isset($_REQUEST['grad44idusuario_doc']) == 0) {
		$_REQUEST['grad44idusuario_doc'] = '';
	}
	if (isset($_REQUEST['grad44fecha']) == 0) {
		$_REQUEST['grad44fecha'] = '';
		//$_REQUEST['grad44fecha'] = $iHoy;
	}
	$_REQUEST['grad44idpostulacion'] = numeros_validar($_REQUEST['grad44idpostulacion']);
	$_REQUEST['grad44consec'] = numeros_validar($_REQUEST['grad44consec']);
	$_REQUEST['grad44id'] = numeros_validar($_REQUEST['grad44id']);
	$_REQUEST['grad44idestadoorigen'] = numeros_validar($_REQUEST['grad44idestadoorigen']);
	$_REQUEST['grad44idestadofin'] = numeros_validar($_REQUEST['grad44idestadofin']);
	$_REQUEST['grad44nota'] = cadena_Validar($_REQUEST['grad44nota']);
	$_REQUEST['grad44idusuario'] = numeros_validar($_REQUEST['grad44idusuario']);
	$_REQUEST['grad44idusuario_td'] = cadena_Validar($_REQUEST['grad44idusuario_td']);
	$_REQUEST['grad44idusuario_doc'] = cadena_Validar($_REQUEST['grad44idusuario_doc']);
	$_REQUEST['grad44fecha'] = numeros_validar($_REQUEST['grad44fecha']);
}
// Espacio para inicializar otras variables
if (isset($_REQUEST['csv_separa']) == 0) {
	$_REQUEST['csv_separa'] = ';';
}
if (isset($_REQUEST['postulado']) == 0) {
	$_REQUEST['postulado'] = 1;
}
$_REQUEST['postulado'] = numeros_validar($_REQUEST['postulado']);
//Si Modifica o Elimina Cargar los campos
if (($_REQUEST['paso'] == 1) || ($_REQUEST['paso'] == 3)) {
	$_REQUEST['grad41idtercero_td'] = $APP->tipo_doc;
	$_REQUEST['grad41idtercero_doc'] = '';
	if ($_REQUEST['paso'] == 1) {
		$sSQLcondi = 'grad41consec=' . $_REQUEST['grad41consec'] . '';
	} else {
		$sSQLcondi = 'grad41id=' . $_REQUEST['grad41id'] . '';
	}
	$sSQL = 'SELECT * FROM grad41postulaciones WHERE ' . $sSQLcondi;
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$_REQUEST['grad41consec'] = $fila['grad41consec'];
		$_REQUEST['grad41id'] = $fila['grad41id'];
		$_REQUEST['grad41idtercero'] = $fila['grad41idtercero'];
		$_REQUEST['grad41idescuela'] = $fila['grad41idescuela'];
		$_REQUEST['grad41idprograma'] = $fila['grad41idprograma'];
		$_REQUEST['grad41idpei'] = $fila['grad41idpei'];
		$_REQUEST['grad41idfecha'] = $fila['grad41idfecha'];
		$_REQUEST['grad41tipogrado'] = $fila['grad41tipogrado'];
		$_REQUEST['grad41idcohorte'] = $fila['grad41idcohorte'];
		$_REQUEST['grad41idestado'] = $fila['grad41idestado'];
		$_REQUEST['grad41fechaaprueba'] = $fila['grad41fechaaprueba'];
		$_REQUEST['grad41idrecibopago'] = $fila['grad41idrecibopago'];
		$_REQUEST['grad41agnopresenta'] = $fila['grad41agnopresenta'];
		$_REQUEST['grad41codigoicfes'] = $fila['grad41codigoicfes'];
		$_REQUEST['grad41cerem_modalidad'] = $fila['grad41cerem_modalidad'];
		$_REQUEST['grad41cerem_centro'] = $fila['grad41cerem_centro'];
		$_REQUEST['grad41libro_num'] = $fila['grad41libro_num'];
		$_REQUEST['grad41libro_folio'] = $fila['grad41libro_folio'];
		$_REQUEST['grad41libro_acta'] = $fila['grad41libro_acta'];
		$_REQUEST['grad41idzona'] = $fila['grad41idzona'];
		$_REQUEST['grad41lugardiploma'] = $fila['grad41lugardiploma'];
		$_REQUEST['grad41enc_tipo'] = $fila['grad41enc_tipo'];
		$_REQUEST['grad41enc_idregistro'] = $fila['grad41enc_idregistro'];
		$bcargo = true;
		$_REQUEST['paso'] = 2;
		$_REQUEST['boculta2741'] = 0;
		$bLimpiaHijos = true;
	} else {
		$_REQUEST['paso'] = 0;
	}
}
//Cerrar
$bCerrando = false;
if ($_REQUEST['paso'] == 16) {
	$_REQUEST['paso'] = 12;
	$_REQUEST['grad11estado'] = 5;
	$bCerrando = true;
}
//Insertar o modificar un elemento
if (($_REQUEST['paso'] == 10) || ($_REQUEST['paso'] == 12)) {
	$bMueveScroll = true;
	list($_REQUEST, $sError, $iTipoError, $sDebugGuardar) = f2741_db_GuardarV2($_REQUEST, $objDB, $bDebug, $idTercero);
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
//Eliminar un elemento
if ($_REQUEST['paso'] == 13) {
	$_REQUEST['paso'] = 2;
	if ($sError == '') {
		list($sError, $iTipoError, $sDebugElimina) = f2741_db_Eliminar($_REQUEST['grad41id'], $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugElimina;
	}
	if ($sError == '') {
		$_REQUEST['paso'] = -1;
		$sError = $ETI['msg_itemeliminado'];
		$iTipoError = 1;
	}
}
//Cambiar el estado
if ($_REQUEST['paso'] == 21) {
	list($_REQUEST, $sError, $sDebugE) = f2741_CambiaEstado($_REQUEST, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugE;
	if ($sError == '') {
		switch ($_REQUEST['grad41idestado']) {
			case 5: // 
				$sError = '<b>' . $ETI['msg_radicada'] . '</b>';
				break;
		}
		$iTipoError = 1;
	}
	$_REQUEST['paso'] = 2;
}
//limpiar la pantalla
if ($_REQUEST['paso'] == -1) {
	$_REQUEST['grad41consec'] = '';
	$_REQUEST['grad41id'] = '';
	$_REQUEST['grad41idtercero'] = $idTercero; //0;
	$_REQUEST['grad41idtercero_td'] = $APP->tipo_doc;
	$_REQUEST['grad41idtercero_doc'] = '';
	$_REQUEST['grad41idescuela'] = 0;
	$_REQUEST['grad41idprograma'] = '';
	$_REQUEST['grad41idpei'] = '';
	$_REQUEST['grad41idfecha'] = $iHoy;
	//$_REQUEST['grad41idfecha'] = '';
	$_REQUEST['grad41tipogrado'] = 1;
	$_REQUEST['grad41idcohorte'] = 0;
	$_REQUEST['grad41idestado'] = 0;
	$_REQUEST['grad41fechaaprueba'] = 0;
	$_REQUEST['grad41idrecibopago'] = 0;
	$_REQUEST['grad41agnopresenta'] = 0;
	$_REQUEST['grad41codigoicfes'] = '';
	$_REQUEST['grad41cerem_modalidad'] = 0;
	$_REQUEST['grad41cerem_centro'] = '';
	$_REQUEST['grad41libro_num'] = 0;
	$_REQUEST['grad41libro_folio'] = 0;
	$_REQUEST['grad41libro_acta'] = 0;
	$_REQUEST['grad41idzona'] = '';
	$_REQUEST['grad41lugardiploma'] = '';
	$_REQUEST['grad41enc_tipo'] = '';
	$_REQUEST['grad41enc_idregistro'] = '';
	$_REQUEST['postulado'] = 1;
	$_REQUEST['paso'] = 0;
}
if ($bLimpiaHijos) {
	$_REQUEST['grad42idpostulacion'] = '';
	$_REQUEST['grad42iddocumento'] = '';
	$_REQUEST['grad42id'] = '';
	$_REQUEST['grad42idorigen'] = 0;
	$_REQUEST['grad42idarchivo'] = 0;
	$_REQUEST['grad42fechadoc'] = $iHoy;
	$_REQUEST['grad42idaprueba'] = $idTercero;
	$_REQUEST['grad42idaprueba_td'] = $APP->tipo_doc;
	$_REQUEST['grad42idaprueba_doc'] = '';
	// $_REQUEST['grad42fechaaprueba'] = $iHoy;
	$_REQUEST['grad42fechaaprueba'] = 0;
	$_REQUEST['grad43idpostulacion'] = '';
	$_REQUEST['grad43consec'] = '';
	$_REQUEST['grad43id'] = '';
	$_REQUEST['grad43anotacion'] = '';
	$_REQUEST['grad43idusuario'] = $idTercero;
	$_REQUEST['grad43idusuario_td'] = $APP->tipo_doc;
	$_REQUEST['grad43idusuario_doc'] = '';
	$_REQUEST['grad43fecha'] = $iHoy;
	//$_REQUEST['grad43fecha'] = '';
	$_REQUEST['grad43hora'] = fecha_hora();
	$_REQUEST['grad43minuto'] = fecha_minuto();
	$_REQUEST['grad44idpostulacion'] = '';
	$_REQUEST['grad44consec'] = '';
	$_REQUEST['grad44id'] = '';
	$_REQUEST['grad44idestadoorigen'] = 0;
	$_REQUEST['grad44idestadofin'] = 0;
	$_REQUEST['grad44nota'] = '';
	$_REQUEST['grad44idusuario'] = $idTercero;
	$_REQUEST['grad44idusuario_td'] = $APP->tipo_doc;
	$_REQUEST['grad44idusuario_doc'] = '';
	$_REQUEST['grad44fecha'] = '';
	//$_REQUEST['grad44fecha'] = $iHoy;
}
//AQUI SE DEBEN CARGAR TODOS LOS DATOS QUE LA FORMA NECESITE.
$bPuedeAbrir = false;
$bPuedeEliminar = false;
$bPuedeGuardar = false;
$bPuedePostular = false;
$bHayImprimir = false;
//DATOS PARA COMPLETAR EL FORMULARIO
$iAgnoIni = 2000;
$iAgno = fecha_agno();
$iAgnoFin = $iAgno + 5;
$bCeremoniaVirtual = false;
$bCeremoniaPresencial = false;
$sNombreUsuario = '';
if ($seg_1707 == 1) {
	$sSQL = 'SELECT unad11razonsocial FROM unad11terceros WHERE unad11id=' . $idTercero . '';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$sNombreUsuario = cadena_notildes($fila['unad11razonsocial']);
	}
}
if ($_REQUEST['grad41cerem_modalidad'] == 1) {
	$bCeremoniaPresencial = true;
}
if ($_REQUEST['grad41cerem_modalidad'] == 2) {
	$bCeremoniaVirtual = true;
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
$iEstado = 0;
if ((int)$_REQUEST['paso'] 	== 0) {
	$bPuedeGuardar = true;
} else {
	switch ($_REQUEST['grad41idestado']) {
		case 0: // Borrador
			$bPuedeEliminar = true;
			$bPuedeGuardar = true;
			$bPuedePostular = true;
			$iEstado = 5;
			break;
		case 5: // Radicada
			break;
		default:
			break;

	}
}
//Crear los controles que requieran llamado a base de datos
$objCombos = new clsHtmlCombos();
$objTercero = new clsHtmlTercero();
list($grad41idtercero_rs, $_REQUEST['grad41idtercero'], $_REQUEST['grad41idtercero_td'], $_REQUEST['grad41idtercero_doc']) = html_tercero($_REQUEST['grad41idtercero_td'], $_REQUEST['grad41idtercero_doc'], $_REQUEST['grad41idtercero'], 0, $objDB);
// $html_grad41idescuela = f2741_HTMLComboV2_grad41idescuela($objDB, $objCombos, $_REQUEST['grad41idescuela'], $_REQUEST['grad41idtercero']);
// $html_grad41idprograma = f2741_HTMLComboV2_grad41idprograma($objDB, $objCombos, $_REQUEST['grad41idprograma'], $_REQUEST['grad41idescuela']);
//$html_grad41idpei = f2741_HTMLComboV2_grad41idpei($objDB, $objCombos, $_REQUEST['grad41idpei'], $_REQUEST['grad41idprograma']);
$grad41idestado_nombre = '&nbsp;';
list($grad41idestado_nombre, $sErrorDet) = tabla_campoxid('grad45estadosolgrad', 'grad45nombre', 'grad45id', $_REQUEST['grad41idestado'], '{' . $ETI['msg_sindato'] . '}', $objDB);
$html_grad41idestado = html_oculto('grad41idestado', $_REQUEST['grad41idestado'], $grad41idestado_nombre);
$objCombos->nuevo('grad41agnopresenta', $_REQUEST['grad41agnopresenta'], false, '', 0);
$objCombos->numeros(1980, fecha_agno(), 1);
//$objCombos->addArreglo($agrad41agnopresenta, $igrad41agnopresenta);
$html_grad41agnopresenta = $objCombos->html('', $objDB);
if ($_REQUEST['grad41lugardiploma'] != 0) {
	$html_grad41lugardiploma = f2741_HTMLComboV2_grad41lugardiploma($objDB, $objCombos, $_REQUEST['grad41lugardiploma'], $_REQUEST['grad41cerem_modalidad']);
}
if ((int)$_REQUEST['paso'] == 0) {
	$html_grad41idescuela = f2741_HTMLComboV2_grad41idescuela($objDB, $objCombos, $_REQUEST['grad41idescuela'], $_REQUEST['grad41idtercero']);
	$html_grad41idprograma = f2741_HTMLComboV2_grad41idprograma($objDB, $objCombos, $_REQUEST['grad41idprograma'], $_REQUEST['grad41idescuela'], $_REQUEST['grad41idtercero']);
	$objCombos->nuevo('grad41idcohorte', $_REQUEST['grad41idcohorte'], true, $ETI['msg_seleccione'], 0);
	// $objCombos->addItem(1, $ETI['si']);
	//$objCombos->addArreglo($agrad41idcohorte, $igrad41idcohorte);
	$objCombos->sAccion = 'carga_combo_grad41cerem_modalidad()';
	$sSQL = 'SELECT grad01id AS id, grad01nombre AS nombre 
	FROM grad01cohortes 
	WHERE grad01fechaapruebaescuela<=' . fecha_DiaMod() . ' AND grad01finapruebaescuela>=' . fecha_DiaMod() . ' 
	ORDER BY grad01fechagrado DESC';
	$html_grad41idcohorte = $objCombos->html($sSQL, $objDB);
	$objCombos->nuevo('grad41cerem_modalidad', $_REQUEST['grad41cerem_modalidad'], true, $ETI['msg_seleccione'], 0);
	// $objCombos->sAccion = 'carga_combo_grad41idzona();';
	// // $objCombos->addItem(1, $ETI['si']);
	// $objCombos->addArreglo($agrad41cerem_modalidad, $igrad41cerem_modalidad);
	$html_grad41cerem_modalidad = $objCombos->html('', $objDB);	
	$objCombos->nuevo('grad41idzona', $_REQUEST['grad41idzona'], true, '{' . $ETI['msg_seleccione'] . '}');
	// $objCombos->sAccion = 'carga_combo_grad41lugardiploma();';
	// $sSQL = 'SELECT unad23id AS id, unad23nombre AS nombre FROM unad23zona WHERE unad23conestudiantes="S" ORDER BY unad23nombre';
	$sSQL = '';
	$html_grad41idzona = $objCombos->html($sSQL, $objDB);
} else {
	$grad41idescuela_nombre = '&nbsp;';
	if ((int)$_REQUEST['grad41idescuela'] != 0) {
		list($grad41idescuela_nombre, $sErrorDet) = tabla_campoxid('core12escuela', 'core12nombre', 'core12id', $_REQUEST['grad41idescuela'], '{' . $ETI['msg_sindato'] . '}', $objDB);
	}
	$html_grad41idescuela = html_oculto('grad41idescuela', $_REQUEST['grad41idescuela'], $grad41idescuela_nombre);
	$grad41idprograma_nombre = '&nbsp;';
	if ((int)$_REQUEST['grad41idprograma'] != 0) {
		list($grad41idprograma_nombre, $sErrorDet) = tabla_campoxid('core09programa', 'core09nombre', 'core09id', $_REQUEST['grad41idprograma'], '{' . $ETI['msg_sindato'] . '}', $objDB);
	}
	$html_grad41idprograma = html_oculto('grad41idprograma', $_REQUEST['grad41idprograma'], $grad41idprograma_nombre);
	$grad41idcohorte_nombre = $_REQUEST['grad41idcohorte'];
	//$grad41idcohorte_nombre = $agrad41idcohorte[$_REQUEST['grad41idcohorte']];
	$grad41idcohorte_nombre = '&nbsp;';
	if ((int)$_REQUEST['grad41idcohorte'] != 0) {
		list($grad41idcohorte_nombre, $sErrorDet) = tabla_campoxid('grad01cohortes', 'grad01nombre', 'grad01id', $_REQUEST['grad41idcohorte'], '{' . $ETI['msg_sindato'] . '}', $objDB);
	}
	$html_grad41idcohorte = html_oculto('grad41idcohorte', $_REQUEST['grad41idcohorte'], $grad41idcohorte_nombre);
	$html_grad41idzona = f2741_HTMLComboV2_grad41idzona($objDB, $objCombos, $_REQUEST['grad41idzona'], $_REQUEST['grad41cerem_modalidad'], $_REQUEST['grad41idcohorte']);
	$html_grad41cerem_modalidad = f2741_HTMLComboV2_grad41cerem_modalidad($objDB, $objCombos, $_REQUEST['grad41cerem_modalidad'], $_REQUEST['grad41idcohorte']);
	$html_grad42iddocumento = f2742_HTMLComboV2_grad42iddocumento($objDB, $objCombos, $_REQUEST['grad42iddocumento']);
	list($grad42idaprueba_rs, $_REQUEST['grad42idaprueba'], $_REQUEST['grad42idaprueba_td'], $_REQUEST['grad42idaprueba_doc']) = html_tercero($_REQUEST['grad42idaprueba_td'], $_REQUEST['grad42idaprueba_doc'], $_REQUEST['grad42idaprueba'], 0, $objDB);
	list($grad43idusuario_rs, $_REQUEST['grad43idusuario'], $_REQUEST['grad43idusuario_td'], $_REQUEST['grad43idusuario_doc']) = html_tercero($_REQUEST['grad43idusuario_td'], $_REQUEST['grad43idusuario_doc'], $_REQUEST['grad43idusuario'], 0, $objDB);
	$grad44idestadoorigen_nombre = '&nbsp;';
	if ((int)$_REQUEST['grad44idestadoorigen'] != 0) {
		list($grad44idestadoorigen_nombre, $sErrorDet) = tabla_campoxid('grad45estadosolgrad', 'grad45nombre', 'grad45id', $_REQUEST['grad44idestadoorigen'], '{' . $ETI['msg_sindato'] . '}', $objDB);
	}
	$html_grad44idestadoorigen = html_oculto('grad44idestadoorigen', $_REQUEST['grad44idestadoorigen'], $grad44idestadoorigen_nombre);
	$grad44idestadofin_nombre = '&nbsp;';
	if ((int)$_REQUEST['grad44idestadofin'] != 0) {
		list($grad44idestadofin_nombre, $sErrorDet) = tabla_campoxid('grad45estadosolgrad', 'grad45nombre', 'grad45id', $_REQUEST['grad44idestadofin'], '{' . $ETI['msg_sindato'] . '}', $objDB);
	}
	$html_grad44idestadofin = html_oculto('grad44idestadofin', $_REQUEST['grad44idestadofin'], $grad44idestadofin_nombre);
	list($grad44idusuario_rs, $_REQUEST['grad44idusuario'], $_REQUEST['grad44idusuario_td'], $_REQUEST['grad44idusuario_doc']) = html_tercero($_REQUEST['grad44idusuario_td'], $_REQUEST['grad44idusuario_doc'], $_REQUEST['grad44idusuario'], 0, $objDB);
}
if ($_REQUEST['grad41idcohorte'] != 0) {
	$html_grad41cerem_modalidad = f2741_HTMLComboV2_grad41cerem_modalidad($objDB, $objCombos, $_REQUEST['grad41cerem_modalidad'], $_REQUEST['grad41idcohorte']);
	if ((int)$_REQUEST['grad41cerem_modalidad'] != 0) {
		$html_grad41idzona = f2741_HTMLComboV2_grad41idzona($objDB, $objCombos, $_REQUEST['grad41idzona'], $_REQUEST['grad41cerem_modalidad'], $_REQUEST['grad41idcohorte']);
		if ($_REQUEST['grad41cerem_modalidad'] == 1) {
			$html_grad41cerem_centro = '<label class="Label200">' . $ETI['grad41cerem_centro'] . '</label><label>' . f2741_HTMLComboV2_grad41cerem_centro($objDB, $objCombos, $_REQUEST['grad41cerem_centro'], $_REQUEST['grad41idcohorte'], $_REQUEST['grad41idzona']);
		}
		if ($_REQUEST['grad41cerem_modalidad'] == 2) {
			$html_grad41lugardiploma = '<label class="Label250">' . $ETI['grad41lugardiploma'] . '</label><label>' . f2741_HTMLComboV2_grad41lugardiploma($objDB, $objCombos, $_REQUEST['grad41lugardiploma'], $_REQUEST['grad41idzona']);
		}
	}
}
//Alistar datos adicionales
$id_rpt = 0;
//$id_rpt=reportes_id(_Identificador_Tipo_Reporte_, $objDB);
/*
$objCombos->nuevo('bcohorte', $_REQUEST['bcohorte'], true, '{' . $ETI['msg_todos'] . '}');
$objCombos->sAccion = 'paginarf2741()';
$sSQL = 'SELECT grad01id AS id, grad01nombre AS nombre FROM grad01cohortes ORDER BY grad01nombre';
$html_bcohorte = $objCombos->html($sSQL, $objDB);
$objCombos->nuevo('bzona', $_REQUEST['bzona'], true, '{' . $ETI['msg_todos'] . '}');
$objCombos->sAccion = 'carga_combo_bcentro()';
// $objCombos->sAccion = 'paginarf2741()';
$sSQL = 'SELECT unad23id AS id, unad23nombre AS nombre 
FROM unad23zona 
WHERE unad23id>0 AND unad23conestudiantes="S"
ORDER BY unad23nombre';
$html_bzona = $objCombos->html($sSQL, $objDB);
$html_bcentro = f2741_HTMLComboV2_bcentro($objDB, $objCombos, $_REQUEST['bcentro'], $_REQUEST['bzona']);
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
$iModeloReporte = 2741;
$html_iFormatoImprime = '<input id="iformatoimprime" name="iformatoimprime" type="hidden" value="0" />
';
//Cargar las tablas de datos
$aParametros[0] = ''; //$_REQUEST['p1_2741'];
$aParametros[100] = $idTercero;
$aParametros[101] = $_REQUEST['paginaf2741'];
$aParametros[102] = $_REQUEST['lppf2741'];
$aParametros[103] = $_REQUEST['postulado'];
list($sTabla2741, $sDebugTabla) = f2741_TablaDetalleV2($aParametros, $objDB, $bDebug);
$sDebug = $sDebug . $sDebugTabla;
$sTabla2742 = '';
$sTabla2743 = '';
$sTabla2744 = '';
if ($_REQUEST['paso'] != 0) {
	//Anexos
	$aParametros2742[0] = $_REQUEST['grad41id'];
	$aParametros2742[100] = $idTercero;
	$aParametros2742[101] = $_REQUEST['paginaf2742'];
	$aParametros2742[102] = $_REQUEST['lppf2742'];
	//$aParametros2742[103] = $_REQUEST['bnombre2742'];
	//$aParametros2742[104] = $_REQUEST['blistar2742'];
	list($sTabla2742, $sDebugTabla) = f2742_TablaDetalleV2($aParametros2742, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugTabla;
	//Anotaciones
	$aParametros2743[0] = $_REQUEST['grad41id'];
	$aParametros2743[100] = $idTercero;
	$aParametros2743[101] = $_REQUEST['paginaf2743'];
	$aParametros2743[102] = $_REQUEST['lppf2743'];
	$aParametros2743[103] = $_REQUEST['postulado'];
	//$aParametros2743[103] = $_REQUEST['bnombre2743'];
	//$aParametros2743[104] = $_REQUEST['blistar2743'];
	list($sTabla2743, $sDebugTabla) = f2743_TablaDetalleV2($aParametros2743, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugTabla;
	//Cambio de estado
	$aParametros2744[0] = $_REQUEST['grad41id'];
	$aParametros2744[100] = $idTercero;
	$aParametros2744[101] = $_REQUEST['paginaf2744'];
	$aParametros2744[102] = $_REQUEST['lppf2744'];
	//$aParametros2744[103] = $_REQUEST['bnombre2744'];
	//$aParametros2744[104] = $_REQUEST['blistar2744'];
	list($sTabla2744, $sDebugTabla) = f2744_TablaDetalleV2($aParametros2744, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugTabla;
}
$bDebugMenu = false;
switch ($iPiel) {
	case 2:
		list($et_menu, $sDebugM) = html_menuCampusV2($objDB, $iPiel, $bDebugMenu, $idTercero);
		break;
	default:
		list($et_menu, $sDebugM) = html_menuCampus($APP->idsistema, $objDB, $iPiel, $bDebugMenu, $idTercero);
		break;
}
// $sDebug = $sDebug . $sDebugM;
$objDB->CerrarConexion();
//FORMA
switch ($iPiel) {
	case 2:
		require $APP->rutacomun . 'unad_forma2023.php';
		forma_InicioV4($xajax, $ETI['titulo_migrado']);
		break;
	default:
		require $APP->rutacomun . 'unad_forma_v2.php';
		forma_cabeceraV3($xajax, $ETI['titulo_migrado']);
		echo $et_menu;
		forma_mitad();
		break;
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
switch ($iPiel) {
	case 2:
		$aRutas = array(
			array('', 'CAMPUS'), 
			array('./migrado.php', 'Mi Grado'), 
			array('', $ETI['titulo_migrado'])
		);
		$iNumBoton = 0;
		$aBotones[$iNumBoton] = array('muestraayuda(' . $APP->idsistema . ', ' . $iCodModulo . ')', $ETI['bt_ayuda'], 'iHelp');
		$iNumBoton++;
		if ($bPuedeEliminar) {
			$aBotones[$iNumBoton] = array('eliminadato()', $ETI['bt_eliminar'], 'iDelete');
			$iNumBoton++;
		}
		if ($bHayImprimir) {
			$aBotones[$iNumBoton] = array($sScriptImprime, $ETI['bt_imprimir'], 'iPrint');
			$iNumBoton++;
		}
		$aBotones[$iNumBoton] = array('limpiapagina()', $ETI['bt_limpiar'], 'iDocument');
		$iNumBoton++;
		if ($bPuedeGuardar) {
			$aBotones[$iNumBoton] = array('enviaguardar()', $ETI['bt_guardar'], 'iSaveFill');
			$iNumBoton++;
		}
		if ($bPuedePostular) {
			$aBotones[$iNumBoton] = array('cambiarestado(' . $iEstado . ')', $ETI['bt_postularme'], 'iTask');
			$iNumBoton++;
		}
		forma_cabeceraV4($aRutas, $aBotones, true, $bDebug, true);
		echo $et_menu;
		forma_mitad($idTercero);
		break;
	default:
		break;
}
?>
<script language="javascript">
	function limpiapagina() {
		expandesector(98);
		window.document.frmedita.paso.value = -1;
		window.document.frmedita.submit();
	}

	function enviaguardar() {
		window.document.frmedita.iscroll.value = window.scrollY;
		expandesector(98);
		let dpaso = window.document.frmedita.paso;
		if (dpaso.value == 0) {
			if (confirm("¿Está seguro de seleccionar esta cohorte para grado?")) {
				dpaso.value = 10;
			}
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
		document.getElementById('div_sector97').style.display = 'none';
		document.getElementById('div_sector98').style.display = 'none';
		document.getElementById('div_sector' + codigo).style.display = 'block';
		let sEst = 'none';
		if (codigo == 1) {
			sEst = 'block';
		}
<?php
switch($iPiel) {
	case 2:
?>
	document.getElementById('nav').style.display = sEst;
	document.getElementById('botones_sup').style.display = sEst;
<?php
		break;
	default:
		if ($bPuedeGuardar) {
?>
		document.getElementById('cmdGuardarf').style.display = sEst;
<?php
		}
		break;
}
?>
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
			//if (illave==1){params[5]='FuncionCuandoNoEsta';}
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
			window.document.frmlista.consulta.value = window.document.frmedita.consulta_2741.value;
			window.document.frmlista.titulos.value = window.document.frmedita.titulos_2741.value;
			window.document.frmlista.nombrearchivo.value = 'Mi grado';
			window.document.frmlista.submit();
		} else {
			ModalMensaje("<?php echo $ERR['6']; ?>");
		}
	}

	function asignarvariables() {
		window.document.frmimpp.v3.value = window.document.frmedita.idusuario.value;
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
			window.document.frmimpp.action = 'e2741_ss.php';
			window.document.frmimpp.submit();
		} else {
			ModalMensaje(sError);
		}
	}

	function imprimep() {
		if (window.document.frmedita.seg_5.value == 1) {
			asignarvariables();
			window.document.frmimpp.action = 'p2741.php';
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
		datos[1] = window.document.frmedita.grad41consec.value;
		if ((datos[1] != '')) {
			xajax_f2741_ExisteDato(datos);
		}
	}

	function cargadato(llave1) {
		window.document.frmedita.grad41consec.value = String(llave1);
		window.document.frmedita.paso.value = 1;
		window.document.frmedita.submit();
	}

	function cargaridf2741(llave1) {
		window.document.frmedita.grad41id.value = String(llave1);
		window.document.frmedita.paso.value = 3;
		window.document.frmedita.submit();
	}

	function carga_combo_grad41idprograma() {
		let params = new Array();
		params[0] = window.document.frmedita.grad41idtercero.value;
		params[1] = window.document.frmedita.grad41idescuela.value;
		params[2] = window.document.frmedita.postulado.value;
		document.getElementById('div_grad41idprograma').innerHTML = '<b>Procesando datos, por favor espere...</b><input id="grad41idprograma" name="grad41idprograma" type="hidden" value="" />';
		xajax_f2741_Combograd41idprograma(params);
	}

	function carga_combo_grad41idpei() {
		let params = new Array();
		params[0] = window.document.frmedita.grad41idprograma.value;
		params[1] = window.document.frmedita.grad41idtercero.value;
		params[2] = window.document.frmedita.postulado.value;
		// document.getElementById('div_grad41idpei').innerHTML = '<b>Procesando datos, por favor espere...</b><input id="grad41idpei" name="grad41idpei" type="hidden" value="" />';
		xajax_f2741_Combograd41idpei(params);
	}

	function carga_combo_grad41cerem_modalidad() {
		let params = new Array();
		params[0] = window.document.frmedita.grad41idcohorte.value;
		params[1] = window.document.frmedita.grad41cerem_modalidad.value;
		document.getElementById('div_grad41cerem_modalidad').innerHTML = '<b>Procesando datos, por favor espere...</b><input id="grad41cerem_modalidad" name="grad41cerem_modalidad" type="hidden" value="" />';
		document.getElementById('div_grad41idzona').innerHTML = '<b>Procesando datos, por favor espere...</b><input id="grad41idzona" name="grad41zona" type="hidden" value="" />';
		xajax_f2741_Combograd41cerem_modalidad(params);
	}

	function carga_combo_grad41idzona() {
		let params = new Array();
		params[0] = window.document.frmedita.grad41cerem_modalidad.value;
		params[1] = window.document.frmedita.grad41idcohorte.value;
		document.getElementById('div_grad41idzona').innerHTML = '<b>Procesando datos, por favor espere...</b><input id="grad41idzona" name="grad41zona" type="hidden" value="" />';
		xajax_f2741_Combograd41idzona(params);
	}

	function carga_combo_grad41cerem_centro() {
		let params = new Array();
		params[0] = window.document.frmedita.grad41idcohorte.value;
		params[1] = window.document.frmedita.grad41idzona.value;
		document.getElementById('div_grad41cerem_centro').innerHTML = '<b>Procesando datos, por favor espere...</b><input id="grad41cerem_centro" name="grad41cerem_centro" type="hidden" value="" />';
		xajax_f2741_Combograd41cerem_centro(params);
	}

	function carga_combo_grad41lugardiploma() {
		let params = new Array();
		params[0] = window.document.frmedita.grad41idzona.value;
		document.getElementById('div_grad41lugardiploma').innerHTML = '<b>Procesando datos, por favor espere...</b><input id="grad41lugardiploma" name="grad41lugardiploma" type="hidden" value="" />';
		xajax_f2741_Combograd41lugardiploma(params);
	}

	function paginarf2741() {
		let params = new Array();
		params[99] = window.document.frmedita.debug.value;
		params[100] = <?php echo $idTercero; ?>;
		params[101] = window.document.frmedita.paginaf2741.value;
		params[102] = window.document.frmedita.lppf2741.value;
		//params[103]=window.document.frmedita.bnombre.value;
		//params[104]=window.document.frmedita.blistar.value;
		//document.getElementById('div_f2741detalle').innerHTML = '<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf2741" name="paginaf2741" type="hidden" value="' + params[101] + '" /><input id="lppf2741" name="lppf2741" type="hidden" value="' + params[102] + '" />';
		xajax_f2741_HtmlTabla(params);
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
		document.getElementById("grad41consec").focus();
	}

	function buscarV2016(sCampo) {
		window.document.frmedita.iscroll.value = window.scrollY;
		expandesector(98);
		window.document.frmedita.scampobusca.value = sCampo;
		let params = new Array();
		params[1] = sCampo;
		//params[2] = window.document.frmedita.iagno.value;
		//params[3] = window.document.frmedita.itipo.value;
		xajax_f2741_Busquedas(params);
	}

	function retornacontrol() {
		expandesector(1);
		window.scrollTo(0, window.document.frmedita.iscroll.value);
	}

	function Devuelve(sValor) {
		let sCampo = window.document.frmedita.scampobusca.value;
		if (sCampo == 'grad41idtercero') {
			ter_traerxid('grad41idtercero', sValor);
		}
		if (sCampo == 'grad42idaprueba') {
			ter_traerxid('grad42idaprueba', sValor);
		}
		if (sCampo == 'grad43idusuario') {
			ter_traerxid('grad43idusuario', sValor);
		}
		if (sCampo == 'grad44idusuario') {
			ter_traerxid('grad44idusuario', sValor);
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
		if (ref == 2742) {
			if (sRetorna != '') {
				window.document.frmedita.grad42idorigen.value = window.document.frmedita.div96v1.value;
				window.document.frmedita.grad42idarchivo.value = sRetorna;
				verboton('beliminagrad42idarchivo', 'block');
			}
			archivo_lnk(window.document.frmedita.grad42idorigen.value, window.document.frmedita.grad42idarchivo.value, 'div_grad42idarchivo');
			paginarf2742();
		}
		MensajeAlarmaV2('', 0);
		retornacontrol();
	}

	function cambiarestado(idDestino) {
		let sMensaje = '';
		window.document.frmedita.iscroll.value = window.pageYOffset;
		window.document.frmedita.grad41idestado.value = idDestino;
		if (sMensaje == '') {
			if (idDestino == 5) { // Radicada
				// if (confirm("Al enviar a revisión debe haber agregado todos los integrantes a su trabajo de grado. ¿Desea continuar?")) {
				if (confirm("Al radicar envía los documentos a revisión. ¿Confirma que desea continuar?")) {
					expandesector(98);
					MensajeAlarmaV2('Verificando los campos.', 2);
					window.document.frmedita.paso.value = 21;
					window.document.frmedita.submit();
				}
			} else {
				expandesector(98);
				window.document.frmedita.paso.value = 21;
				window.document.frmedita.submit();
			}
		} else {
			window.alert(sMensaje);
		}
	}
</script>
<?php
if ($_REQUEST['paso'] != 0) {
?>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>jsi/js2742.js?v=1"></script>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>jsi/js2743.js"></script>
<form id="frmimpp" name="frmimpp" method="post" action="p2741.php" target="_blank">
<input id="r" name="r" type="hidden" value="2741" />
<input id="id2741" name="id2741" type="hidden" value="<?php echo $_REQUEST['grad41id']; ?>" />
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
<input id="postulado" name="postulado" type="hidden" value="<?php echo $_REQUEST['postulado']; ?>" />
<div id="div_sector1">
<?php 
switch ($iPiel) {
	case 2:
		break;
	default:
?>
<div class="titulos">
<div class="titulosD">
<input id="cmdAyuda" name="cmdAyuda" type="button" class="btUpAyuda" onclick="muestraayuda(<?php echo $APP->idsistema . ', ' . $iCodModulo; ?>);" title="<?php echo $ETI['bt_ayuda']; ?>" value="<?php echo $ETI['bt_ayuda']; ?>" />
<?php
if ($bPuedeEliminar) {
?>
<input id="cmdEliminar" name="cmdEliminar" type="button" class="btUpEliminar" onclick="eliminadato();" title="<?php echo $ETI['bt_eliminar']; ?>" value="<?php echo $ETI['bt_eliminar']; ?>" />
<?php
}
if ($bHayImprimir) {
?>
<input id="cmdImprimir" name="cmdImprimir" type="button" class="<?php echo $sClaseBoton; ?>" onclick="<?php echo $sScript; ?>" title="<?php echo $ETI['bt_imprimir']; ?>" value="<?php echo $ETI['bt_imprimir']; ?>" />
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
if ($bPuedePostular) {
?>
<input id="cmdCerrar" name="cmdCerrar" type="button" class="btSupCerrar" onclick="cambiarestado(<?php echo $iEstado; ?>);" title="<?php echo $ETI['bt_postularme']; ?>" value="<?php echo $ETI['bt_postularme']; ?>" />
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
echo '<h2>' . $ETI['titulo_migrado'] . '</h2>';
?>
</div>
</div>
<?php
break;
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
echo html_tipodocV2('deb_tipodoc', $_REQUEST['deb_tipodoc']);
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
<label class="Label30"></label>
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
//Div para ocultar
$bConExpande = false;
if ($bConExpande) {
?>
<div class="ir_derecha"<?php echo $sAnchoExpandeContrae; ?>>
<?php
$sEstiloExpande = ' style="display:none;"';
$sEstiloRecoje = '';
$sEstiloDiv = '';
if ($_REQUEST['boculta2741'] != 0) {
	$sEstiloExpande = '';
	$sEstiloRecoje = ' style="display:none;"';
	$sEstiloDiv = ' style="display:none;"';
}
?>
<input id="boculta2741" name="boculta2741" type="hidden" value="<?php echo $_REQUEST['boculta2741']; ?>" />
<label class="Label30">
<input id="btexpande2741" name="btexpande2741" type="button" value="Mostrar" class="btMiniExpandir" onclick="expandepanel(2741, 'block', 0);" title="<?php echo $ETI['bt_mostrar']; ?>"<?php echo $sEstiloExpande; ?>/>
</label>
<label class="Label30">
<input id="btrecoge2741" name="btrecoge2741" type="button" value="Ocultar" class="btMiniRecoger" onclick="expandepanel(2741, 'none', 1);" title="<?php echo $ETI['bt_ocultar']; ?>" <?php echo $sEstiloRecoje; ?>/>
</label>
</div>
<div id="div_p2741"<?php echo $sEstiloDiv; ?>>
<?php
}
//Mostrar formulario para editar
?>
<label class="Label130"<?php echo $sOcultaConsec; ?>>
<?php
echo $ETI['grad41consec'];
?>
</label>
<label class="Label90"<?php echo $sOcultaConsec; ?>>
<?php
if ($_REQUEST['paso'] != 2) {
?>
<input id="grad41consec" name="grad41consec" type="text" value="<?php echo $_REQUEST['grad41consec']; ?>" onchange="RevisaLlave()" class="cuatro" />
<?php
} else {
	echo html_oculto('grad41consec', $_REQUEST['grad41consec'], formato_numero($_REQUEST['grad41consec']));
}
?>
</label>
<?php
if (true) {
?>
<label class="Label60"<?php echo $sOcultaId; ?>>
<?php
echo $ETI['grad41id'];
?>
</label>
<label class="Label60"<?php echo $sOcultaId; ?>>
<?php
	echo html_oculto('grad41id', $_REQUEST['grad41id'], formato_numero($_REQUEST['grad41id']));
?>
</label>
<?php
} else {
?>
<input id="grad41id" name="grad41id" type="hidden" value="<?php echo $_REQUEST['grad41id']; ?>" />
<?php
}
?>
<label class="Label90">
<?php
echo $ETI['grad41idfecha'];
?>
</label>
<label class="Label200">
<div id="div_grad41idfecha">
<?php
echo html_oculto('grad41idfecha', $_REQUEST['grad41idfecha'], fecha_desdenumero($_REQUEST['grad41idfecha'])); //formato_FechaLargaDesdeNumero
?>
</div>
</label>
<label class="Label60">
<?php
echo $ETI['grad41idestado'];
?>
</label>
<label>
<div id="div_grad41idestado">
<?php
echo $html_grad41idestado;
?>
</div>
</label>
<div class="salto1px"></div>
<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo $ETI['grad41idtercero'];
?>
</label>
<div class="salto1px"></div>
<input id="grad41idtercero" name="grad41idtercero" type="hidden" value="<?php echo $_REQUEST['grad41idtercero']; ?>" />
<div id="div_grad41idtercero_llaves">
<?php
$bOculto = true;
// if ($_REQUEST['paso'] != 2) {
// 	$bOculto = false;
// }
echo html_DivTerceroV2('grad41idtercero', $_REQUEST['grad41idtercero_td'], $_REQUEST['grad41idtercero_doc'], $bOculto, 2, $ETI['ing_doc']);
?>
</div>
<div class="salto1px"></div>
<div id="div_grad41idtercero" class="L"><?php echo $grad41idtercero_rs; ?></div>
<div class="salto1px"></div>
</div>
<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo $ETI['grad41idprograma'];
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['grad41idescuela'];
?>
</label>
<label id="div_grad41idescuela">
<?php
echo $html_grad41idescuela;
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['grad41idprograma'];
?>
</label>
<label>
<div id="div_grad41idprograma">
<?php
echo $html_grad41idprograma;
?>
</div>
</label>
<label>
<input id="grad41idpei" name="grad41idpei" type="hidden" value="<?php echo $_REQUEST['grad41idpei']; ?>">
</label>
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
<label class="Label200">
<?php
echo $ETI['grad41idcohorte'];
?>
</label>
<label>
<?php
echo $html_grad41idcohorte;
?>
</label>
<div class="salto1px"></div>
<label class="Label200">
<?php
echo $ETI['grad41fechaaprueba'];
?>
</label>
<div class="Campo200">
<label>
<?php
echo html_oculto('grad41fechaaprueba', $_REQUEST['grad41fechaaprueba'], fecha_desdenumero($_REQUEST['grad41fechaaprueba']));
?>
</label>
</div>
<?php
if (false) {
?>
<label class="Label30">
<input id="bgrad41fechaaprueba_hoy" name="bgrad41fechaaprueba_hoy" type="button" value="Hoy" class="btMiniHoy" onclick="fecha_AsignarNum('grad41fechaaprueba', '<?php echo $iHoy; ?>')" title="<?php echo $ETI['bt_hoy']; ?>" />
</label>
<?php
}
?>
<div class="salto1px"></div>
<label class="Label200">
<?php
echo $ETI['grad41idrecibopago'];
?>
</label>
<label class="Label130">
<div id="div_grad41idrecibopago">
<?php
echo html_oculto('grad41idrecibopago', $_REQUEST['grad41idrecibopago']);
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label200">
<?php
echo $ETI['grad41codigoicfes'];
?>
</label>
<label>

<input id="grad41codigoicfes" name="grad41codigoicfes" type="text" value="<?php echo $_REQUEST['grad41codigoicfes']; ?>" maxlength="50" placeholder="<?php echo $ETI['ing_campo'] . $ETI['grad41codigoicfes']; ?>" />
</label>
<label class="Label200">
<?php
echo $ETI['grad41agnopresenta'];
?>
</label>
<label>
<?php
echo $html_grad41agnopresenta;
?>
</label>
<div class="salto1px"></div>
<label class="Label200">
<?php
echo $ETI['grad41cerem_modalidad'];
?>
</label>
<label id="div_grad41cerem_modalidad" class="Label160">
<?php
echo $html_grad41cerem_modalidad;
?>
</label>
<div class="salto1px"></div>
<label class="Label200">
<?php
echo $ETI['grad41idzona'];
?>
</label>
<label id="div_grad41idzona" class="Label350">
<?php
echo $html_grad41idzona;
?>
</label>
<div id="div_grad41cerem_centro">
<?php
if ($bCeremoniaPresencial) {
echo $html_grad41cerem_centro;
}
?>
</div>
<div id="div_grad41lugardiploma">
<?php
if ($bCeremoniaVirtual) {
echo $html_grad41lugardiploma;
}
?>
</div>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['grad41libro_num'];
?>
</label>
<label class="Label130">
<div id="div_grad41libro_num">
<?php
echo html_oculto('grad41libro_num', $_REQUEST['grad41libro_num']);
?>
</div>
</label>
<label class="Label130">
<?php
echo $ETI['grad41libro_folio'];
?>
</label>
<label class="Label130">
<div id="div_grad41libro_folio">
<?php
echo html_oculto('grad41libro_folio', $_REQUEST['grad41libro_folio']);
?>
</div>
</label>
<label class="Label130">
<?php
echo $ETI['grad41libro_acta'];
?>
</label>
<label class="Label130">
<div id="div_grad41libro_acta">
<?php
echo html_oculto('grad41libro_acta', $_REQUEST['grad41libro_acta']);
?>
</div>
</label>
<input id="grad41enc_tipo" name="grad41enc_tipo" type="hidden" value="<?php echo $_REQUEST['grad41enc_tipo']; ?>" />
<input id="grad41enc_idregistro" name="grad41enc_idregistro" type="hidden" value="<?php echo $_REQUEST['grad41enc_idregistro']; ?>" />
<?php
// -- Inicia Grupo campos 2742 Anexos
?>
<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo $ETI['titulo_2742'];
?>
</label>
<input id="boculta2742" name="boculta2742" type="hidden" value="<?php echo $_REQUEST['boculta2742']; ?>" />
<?php
if ($_REQUEST['paso'] == 2) {
	//if ($bCondicion) {
?>
<div class="ir_derecha"<?php echo $sAnchoExpandeContrae; ?>>
<?php
if (false) {
?>
<label class="Label30">
<input id="btexcel2742" name="btexcel2742" type="button" value="Exportar" class="btMiniExcel" onclick="imprime2742();" title="Exportar" />
</label>
<?php
}
$sEstiloExpande = ' style="display:none;"';
$sEstiloRecoje = '';
$sEstiloDiv = '';
if ($_REQUEST['boculta2742'] != 0) {
	$sEstiloExpande = '';
	$sEstiloRecoje = ' style="display:none;"';
	$sEstiloDiv = ' style="display:none;"';
}
?>
<label class="Label30">
<input id="btexpande2742" name="btexpande2742" type="button" value="Expandir" class="btMiniExpandir" onclick="expandepanel(2742, 'block', 0);" title="<?php echo $ETI['bt_mostrar']; ?>"<?php echo $sEstiloExpande; ?>/>
</label>
<label class="Label30">
<input id="btrecoge2742" name="btrecoge2742" type="button" value="Recoger" class="btMiniRecoger" onclick="expandepanel(2742, 'none', 1);" title="<?php echo $ETI['bt_ocultar']; ?>"<?php echo $sEstiloRecoje; ?>/>
</label>
</div>
<div class="salto1px"></div>
<div id="div_p2742"<?php echo $sEstiloDiv; ?>>
<?php
if (true) {
?>
<label class="Label60"<?php echo $sOcultaId; ?>>
<?php
echo $ETI['grad42id'];
?>
</label>
<label class="Label60"<?php echo $sOcultaId; ?>>
<div id="div_grad42id">
<?php
	echo html_oculto('grad42id', $_REQUEST['grad42id'], formato_numero($_REQUEST['grad42id']));
?>
</div>
</label>
<?php
} else {
?>
<input id="grad42id" name="grad42id" type="hidden" value="<?php echo $_REQUEST['grad42id']; ?>" />
<?php
}
?>
<input id="grad42idorigen" name="grad42idorigen" type="hidden" value="<?php echo $_REQUEST['grad42idorigen']; ?>" />
<input id="grad42idarchivo" name="grad42idarchivo" type="hidden" value="<?php echo $_REQUEST['grad42idarchivo']; ?>" />
<label class="Label130">
<?php
echo $ETI['grad42iddocumento'];
?>
</label>
<label>
<div id="div_grad42iddocumento">
<?php
echo $html_grad42iddocumento;
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label200">
<?php
echo $ETI['grad42fechadoc'];
?>
</label>
<label class="Label130">
<div id="div_grad42fechadoc">
<?php
echo html_oculto('grad42fechadoc', $_REQUEST['grad42fechadoc'], fecha_desdenumero($_REQUEST['grad42fechadoc'])); //formato_FechaLargaDesdeNumero
?>
</div>
</label>
<?php
if (false) {
?>
<?php
echo html_FechaEnNumero('grad42fechadoc', $_REQUEST['grad42fechadoc']); //, false, '', $iAgnoIni, $iAgnoFin); //$bvacio, $accion
?>
<label class="Label30">
<input id="bgrad42fechadoc_hoy" name="bgrad42fechadoc_hoy" type="button" value="Hoy" class="btMiniHoy" onclick="fecha_AsignarNum('grad42fechadoc', '<?php echo $iHoy; ?>')" title="<?php echo $ETI['bt_hoy']; ?>" />
</label>
<?php
}
?>
<div class="salto1px"></div>
<label class="Label200">
<?php
echo $ETI['grad42fechaaprueba'];
?>
</label>
<label class="Label220">
<div id="div_grad42fechaaprueba">
<?php
echo html_oculto('grad42fechaaprueba', $_REQUEST['grad42fechaaprueba'], fecha_desdenumero($_REQUEST['grad42fechaaprueba'])); //formato_FechaLargaDesdeNumero
?>
</div>
</label>
<div class="salto1px"></div>
<div class="GrupoCampos300">
<div class="salto1px"></div>
<div id="div_grad42idarchivo" class="Campo300">
<?php
echo html_lnkarchivo((int)$_REQUEST['grad42idorigen'], (int)$_REQUEST['grad42idarchivo']);
?>
</div>
<?php
$sEstiloAnexa = '';
$sEstiloElimina = ' style="display:none;"';
if ((int)$_REQUEST['grad42id'] == 0) {
	$sEstiloAnexa = ' style="display:none;"';
}
if ((int)$_REQUEST['grad42idarchivo'] != 0) {
	$sEstiloElimina = ' style="inline-block;"';
}
?>
<label class="Label130"></label>
<label class="Label30">
<input id="banexagrad42idarchivo" name="banexagrad42idarchivo" type="button" value="Anexar" class="btMiniAnexar" onclick="carga_grad42idarchivo()" title="Cargar archivo"<?php echo $sEstiloAnexa; ?>/>
</label>
<label class="Label30">
<input id="beliminagrad42idarchivo" name="beliminagrad42idarchivo" type="button" value="Eliminar" class="btMiniEliminar" onclick="eliminagrad42idarchivo()" title="Eliminar archivo"<?php echo $sEstiloElimina; ?>/>
</label>
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
<?php
if (false) {
?>
<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo $ETI['grad42idaprueba'];
?>
</label>
<div class="salto1px"></div>
<input id="grad42idaprueba" name="grad42idaprueba" type="hidden" value="<?php echo $_REQUEST['grad42idaprueba']; ?>" />
<div id="div_grad42idaprueba_llaves">
<?php
$bOculto = true;
echo html_DivTerceroV2('grad42idaprueba', $_REQUEST['grad42idaprueba_td'], $_REQUEST['grad42idaprueba_doc'], $bOculto, 0, $ETI['ing_doc']);
?>
</div>
<div class="salto1px"></div>
<div id="div_grad42idaprueba" class="L"><?php echo $grad42idaprueba_rs; ?></div>
<div class="salto1px"></div>
</div>
<?php
} else {
?>
<input id="grad42idaprueba" name="grad42idaprueba" type="hidden" value="<?php echo $_REQUEST['grad42idaprueba']; ?>" />
<?php
}
?>
<div class="salto1px"></div>
<label class="Label130">&nbsp;</label>
<?php
$sEstiloElimina = ' style="display:none;"';
if ((int)$_REQUEST['grad42id'] != 0) {
	$sEstiloElimina = ' style="inline-block;"';
}
?>
<label class="Label30">
<input id="bguarda2742" name="bguarda2742" type="button" value="Guardar" class="btMiniGuardar" onclick="guardaf2742()" title="<?php echo $ETI['bt_mini_guardar_2742']; ?>" />
</label>
<label class="Label30">
<input id="blimpia2742" name="blimpia2742" type="button" value="Limpiar" class="btMiniLimpiar" onclick="limpiaf2742()" title="<?php echo $ETI['bt_mini_limpiar_2742']; ?>" />
</label>
<label class="Label30">
<input id="belimina2742" name="belimina2742" type="button" value="Eliminar" class="btMiniEliminar" onclick="eliminaf2742()" title="<?php echo $ETI['bt_mini_eliminar_2742']; ?>"<?php echo $sEstiloElimina; ?>/>
</label>
<?php
//Este es el cierre del div_p2742
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
<input id="bnombre2742" name="bnombre2742" type="text" value="<?php echo $_REQUEST['bnombre2742']; ?>" onchange="paginarf2742()" />
</label>
<label class="Label130">
<?php
echo $ETI['msg_Listar'];
?>
</label>
<label>
<?php
echo $html_blistar2742;
?>
</label>
<div class="salto1px"></div>
</div>
<?php
}
?>
<div id="div_f2742detalle">
<?php
echo $sTabla2742;
?>
</div>
<?php
}
?>
<div class="salto1px"></div>
</div>
<?php
// -- Termina Grupo campos 2742 Anexos
?>
<?php
// -- Inicia Grupo campos 2743 Anotaciones
?>
<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo $ETI['titulo_2743'];
?>
</label>
<input id="boculta2743" name="boculta2743" type="hidden" value="<?php echo $_REQUEST['boculta2743']; ?>" />
<?php
if ($_REQUEST['paso'] == 2) {
	//if ($bCondicion) {
?>
<div class="ir_derecha"<?php echo $sAnchoExpandeContrae; ?>>
<?php
if (false) {
?>
<label class="Label30">
<input id="btexcel2743" name="btexcel2743" type="button" value="Exportar" class="btMiniExcel" onclick="imprime2743();" title="Exportar" />
</label>
<?php
}
$sEstiloExpande = ' style="display:none;"';
$sEstiloRecoje = '';
$sEstiloDiv = ' style="display:none;"';
if ($_REQUEST['boculta2743'] != 0) {
	$sEstiloExpande = '';
	$sEstiloRecoje = ' style="display:none;"';
	$sEstiloDiv = ' style="display:none;"';
}
?>
<?php
if (false) {
?>
<label class="Label30">
<input id="btexpande2743" name="btexpande2743" type="button" value="Expandir" class="btMiniExpandir" onclick="expandepanel(2743, 'block', 0);" title="<?php echo $ETI['bt_mostrar']; ?>"<?php echo $sEstiloExpande; ?>/>
</label>
<label class="Label30">
<input id="btrecoge2743" name="btrecoge2743" type="button" value="Recoger" class="btMiniRecoger" onclick="expandepanel(2743, 'none', 1);" title="<?php echo $ETI['bt_ocultar']; ?>"<?php echo $sEstiloRecoje; ?>/>
</label>
<?php
}
?>
</div>
<div class="salto1px"></div>
<div id="div_p2743"<?php echo $sEstiloDiv; ?>>
<label class="Label130"<?php echo $sOcultaConsec; ?>>
<?php
echo $ETI['grad43consec'];
?>
</label>
<label class="Label130"<?php echo $sOcultaConsec; ?>>
<div id="div_grad43consec">
<?php
if ((int)$_REQUEST['grad43id'] == 0) {
?>
<input id="grad43consec" name="grad43consec" type="text" value="<?php echo $_REQUEST['grad43consec']; ?>" onchange="revisaf2743()" class="cuatro" />
<?php
} else {
	echo html_oculto('grad43consec', $_REQUEST['grad43consec'], formato_numero($_REQUEST['grad43consec']));
}
?>
</div>
</label>
<?php
if ($bDebug) {
?>
<label class="Label60"<?php echo $sOcultaId; ?>>
<?php
echo $ETI['grad43id'];
?>
</label>
<label class="Label60"<?php echo $sOcultaId; ?>>
<div id="div_grad43id">
<?php
	echo html_oculto('grad43id', $_REQUEST['grad43id'], formato_numero($_REQUEST['grad43id']));
?>
</div>
</label>
<?php
} else {
?>
<input id="grad43id" name="grad43id" type="hidden" value="<?php echo $_REQUEST['grad43id']; ?>" />
<?php
}
?>
<label class="txtAreaS">
<?php
echo $ETI['grad43anotacion'];
?>
<textarea id="grad43anotacion" name="grad43anotacion" placeholder="<?php echo $ETI['ing_campo'] . $ETI['grad43anotacion']; ?>"><?php echo $_REQUEST['grad43anotacion']; ?></textarea>
</label>
<?php
if (false) {
?>
<div class="salto1px"></div>
<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo $ETI['grad43idusuario'];
?>
</label>
<div class="salto1px"></div>
<input id="grad43idusuario" name="grad43idusuario" type="hidden" value="<?php echo $_REQUEST['grad43idusuario']; ?>" />
<div id="div_grad43idusuario_llaves">
<?php
$bOculto = true;
echo html_DivTerceroV2('grad43idusuario', $_REQUEST['grad43idusuario_td'], $_REQUEST['grad43idusuario_doc'], $bOculto, 0, $ETI['ing_doc']);
?>
</div>
<div class="salto1px"></div>
<div id="div_grad43idusuario" class="L"><?php echo $grad43idusuario_rs; ?></div>
<div class="salto1px"></div>
</div>
<?php
} else {
?>
<input id="grad43idusuario" name="grad43idusuario" type="hidden" value="<?php echo $_REQUEST['grad43idusuario']; ?>" />
<?php
}
?>
<label class="Label130">
<?php
echo $ETI['grad43fecha'];
?>
</label>
<label class="Label220">
<div id="div_grad43fecha">
<?php
echo html_oculto('grad43fecha', $_REQUEST['grad43fecha'], fecha_desdenumero($_REQUEST['grad43fecha'])); //formato_FechaLargaDesdeNumero
?>
</div>
</label>
<label class="Label130">
<?php
echo $ETI['grad43hora'];
?>
</label>
<label>
<div class="campo_HoraMin" id="div_grad43hora">
<?php
echo html_HoraMin('grad43hora', $_REQUEST['grad43hora'], 'grad43minuto', $_REQUEST['grad43minuto'], true);
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label130">&nbsp;</label>
<?php
$sEstiloElimina = ' style="display:none;"';
if ((int)$_REQUEST['grad43id'] != 0) {
	$sEstiloElimina = ' style="inline-block;"';
}
?>
<label class="Label30">
<input id="bguarda2743" name="bguarda2743" type="button" value="Guardar" class="btMiniGuardar" onclick="guardaf2743()" title="<?php echo $ETI['bt_mini_guardar_2743']; ?>" />
</label>
<label class="Label30">
<input id="blimpia2743" name="blimpia2743" type="button" value="Limpiar" class="btMiniLimpiar" onclick="limpiaf2743()" title="<?php echo $ETI['bt_mini_limpiar_2743']; ?>" />
</label>
<label class="Label30">
<input id="belimina2743" name="belimina2743" type="button" value="Eliminar" class="btMiniEliminar" onclick="eliminaf2743()" title="<?php echo $ETI['bt_mini_eliminar_2743']; ?>"<?php echo $sEstiloElimina; ?>/>
</label>
<?php
//Este es el cierre del div_p2743
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
<input id="bnombre2743" name="bnombre2743" type="text" value="<?php echo $_REQUEST['bnombre2743']; ?>" onchange="paginarf2743()" />
</label>
<label class="Label130">
<?php
echo $ETI['msg_Listar'];
?>
</label>
<label>
<?php
echo $html_blistar2743;
?>
</label>
<div class="salto1px"></div>
</div>
<?php
}
?>
<div id="div_f2743detalle">
<?php
echo $sTabla2743;
?>
</div>
<?php
}
?>
<div class="salto1px"></div>
</div>
<?php
// -- Termina Grupo campos 2743 Anotaciones
?>
<?php
// -- Inicia Grupo campos 2744 Cambio de estado
?>
<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo $ETI['titulo_2744'];
?>
</label>
<input id="boculta2744" name="boculta2744" type="hidden" value="<?php echo $_REQUEST['boculta2744']; ?>" />
<?php
if ($_REQUEST['paso'] == 2) {
	//if ($bCondicion) {
?>
<?php
if (false) {
?>
<div class="ir_derecha"<?php echo $sAnchoExpandeContrae; ?>>
<?php
if (false) {
?>
<label class="Label30">
<input id="btexcel2744" name="btexcel2744" type="button" value="Exportar" class="btMiniExcel" onclick="imprime2744();" title="Exportar" />
</label>
<?php
}
$sEstiloExpande = ' style="display:none;"';
$sEstiloRecoje = '';
$sEstiloDiv = '';
if ($_REQUEST['boculta2744'] != 0) {
	$sEstiloExpande = '';
	$sEstiloRecoje = ' style="display:none;"';
	$sEstiloDiv = ' style="display:none;"';
}
?>
<label class="Label30">
<input id="btexpande2744" name="btexpande2744" type="button" value="Expandir" class="btMiniExpandir" onclick="expandepanel(2744, 'block', 0);" title="<?php echo $ETI['bt_mostrar']; ?>"<?php echo $sEstiloExpande; ?>/>
</label>
<label class="Label30">
<input id="btrecoge2744" name="btrecoge2744" type="button" value="Recoger" class="btMiniRecoger" onclick="expandepanel(2744, 'none', 1);" title="<?php echo $ETI['bt_ocultar']; ?>"<?php echo $sEstiloRecoje; ?>/>
</label>
</div>
<div class="salto1px"></div>
<div id="div_p2744"<?php echo $sEstiloDiv; ?>>
<label class="Label130"<?php echo $sOcultaConsec; ?>>
<?php
echo $ETI['grad44consec'];
?>
</label>
<label class="Label130"<?php echo $sOcultaConsec; ?>>
<div id="div_grad44consec">
<?php
if ((int)$_REQUEST['grad44id'] == 0) {
?>
<input id="grad44consec" name="grad44consec" type="text" value="<?php echo $_REQUEST['grad44consec']; ?>" onchange="revisaf2744()" class="cuatro" />
<?php
} else {
	echo html_oculto('grad44consec', $_REQUEST['grad44consec'], formato_numero($_REQUEST['grad44consec']));
}
?>
</div>
</label>
<?php
if ($bDebug) {
?>
<label class="Label60"<?php echo $sOcultaId; ?>>
<?php
echo $ETI['grad44id'];
?>
</label>
<label class="Label60"<?php echo $sOcultaId; ?>>
<div id="div_grad44id">
<?php
	echo html_oculto('grad44id', $_REQUEST['grad44id'], formato_numero($_REQUEST['grad44id']));
?>
</div>
</label>
<?php
} else {
?>
<input id="grad44id" name="grad44id" type="hidden" value="<?php echo $_REQUEST['grad44id']; ?>" />
<?php
}
?>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['grad44idestadoorigen'];
?>
</label>
<label>
<div id="div_grad44idestadoorigen">
<?php
echo $html_grad44idestadoorigen;
?>
</div>
</label>
<label class="Label130">
<?php
echo $ETI['grad44idestadofin'];
?>
</label>
<label>
<div id="div_grad44idestadofin">
<?php
echo $html_grad44idestadofin;
?>
</div>
</label>
<label class="txtAreaS">
<?php
echo $ETI['grad44nota'];
?>
<?php
echo html_oculto('grad44nota', $_REQUEST['grad44nota']);
?>
</label>
<div class="salto1px"></div>
<?php
if (false) {
?>
<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo $ETI['grad44idusuario'];
?>
</label>
<div class="salto1px"></div>
<input id="grad44idusuario" name="grad44idusuario" type="hidden" value="<?php echo $_REQUEST['grad44idusuario']; ?>" />
<div id="div_grad44idusuario_llaves">
<?php
$bOculto = true;
echo html_DivTerceroV2('grad44idusuario', $_REQUEST['grad44idusuario_td'], $_REQUEST['grad44idusuario_doc'], $bOculto, 0, $ETI['ing_doc']);
?>
</div>
<div class="salto1px"></div>
<div id="div_grad44idusuario" class="L"><?php echo $grad44idusuario_rs; ?></div>
<div class="salto1px"></div>
</div>
<?php
}
?>
<label class="Label130">
<?php
echo $ETI['grad44fecha'];
?>
</label>
<label class="Label220">
<div id="div_grad44fecha">
<?php
echo html_oculto('grad44fecha', $_REQUEST['grad44fecha'], fecha_desdenumero($_REQUEST['grad44fecha'])); //formato_FechaLargaDesdeNumero
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label130">&nbsp;</label>
<?php
$sEstiloElimina = ' style="display:none;"';
if ((int)$_REQUEST['grad44id'] != 0) {
	$sEstiloElimina = ' style="inline-block;"';
}
?>
<label class="Label30">
<input id="bguarda2744" name="bguarda2744" type="button" value="Guardar" class="btMiniGuardar" onclick="guardaf2744()" title="<?php echo $ETI['bt_mini_guardar_2744']; ?>" />
</label>
<label class="Label30">
<input id="blimpia2744" name="blimpia2744" type="button" value="Limpiar" class="btMiniLimpiar" onclick="limpiaf2744()" title="<?php echo $ETI['bt_mini_limpiar_2744']; ?>" />
</label>
<label class="Label30">
<input id="belimina2744" name="belimina2744" type="button" value="Eliminar" class="btMiniEliminar" onclick="eliminaf2744()" title="<?php echo $ETI['bt_mini_eliminar_2744']; ?>"<?php echo $sEstiloElimina; ?>/>
</label>
<?php
//Este es el cierre del div_p2744
?>
<div class="salto1px"></div>
</div>
<?php
}
?>

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
<input id="bnombre2744" name="bnombre2744" type="text" value="<?php echo $_REQUEST['bnombre2744']; ?>" onchange="paginarf2744()" />
</label>
<label class="Label130">
<?php
echo $ETI['msg_Listar'];
?>
</label>
<label>
<?php
echo $html_blistar2744;
?>
</label>
<div class="salto1px"></div>
</div>
<?php
}
?>
<div id="div_f2744detalle">
<?php
echo $sTabla2744;
?>
</div>
<?php
}
?>
<div class="salto1px"></div>
</div>
<?php
// -- Termina Grupo campos 2744 Cambio de estado
?>
<?php
if (false) {
	//Ejemplo de boton de ayuda
	//echo html_BotonAyuda('NombreCampo');
	//echo html_DivAyudaLocal('NombreCampo');
}
if ($bConExpande) {
	//Este es el cierre del div_p2741
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
<div class="salto1px"></div>
<?php
echo ' ' . $csv_separa;
?>
<div class="salto1px"></div>
<div id="div_f2741detalle">
<?php
echo $sTabla2741;
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
<input id="titulo_2741" name="titulo_2741" type="hidden" value="<?php echo $ETI['titulo_migrado']; ?>" />
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
echo '<h2>' . $ETI['titulo_migrado'] . '</h2>';
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
echo '<h2>' . $ETI['titulo_migrado'] . '</h2>';
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
<?php
switch ($iPiel) {
	case 2:
		break;
	default:
		if ($bPuedeGuardar) {
?>
<div class="flotante">
<input id="cmdGuardarf" name="cmdGuardarf" type="button" class="btSoloGuardar" onClick="enviaguardar();" value="<?php echo $ETI['bt_guardar']; ?>" />
</div>
<?php
		}
		break;
}
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
<?php
if ($_REQUEST['paso'] == 0) {
?>
<script language="javascript">
	$().ready(function() {
		$("#grad41idescuela").chosen();
		$("#grad41idprograma").chosen();
		// $("#grad41idpei").chosen();
		$("#grad41idcohorte").chosen();
		// $("#grad41cerem_modalidad").chosen();
		// $("#grad41cerem_centro").chosen();
		$("#grad41idzona").chosen();
		// $("#grad41lugardiploma").chosen();
		$("#bcohorte").chosen();
		$("#bzona").chosen();
		$("#bcentro").chosen();
	});
</script>
<?php
}
?>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>unad_todas.js?ver=8"></script>
<?php
forma_piedepagina();

