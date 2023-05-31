<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2020 - 2022 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Version 2.27.5b viernes, 11 de febrero de 2022
--- Modelo Versión 2.29.4 jueves, 11 de mayo de 2023
*/

/** Archivo index.php.
 * Modulo 2200 .
 * @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
 * @param debug = 1  (Opcional), bandera para indicar si se generan datos de depuración
 * @date jueves, 11 de mayo de 2023
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
require $APP->rutacomun . 'libsai.php';
if (($bPeticionXAJAX) && ($_SESSION['unad_id_tercero'] == 0)) {
	// viene por xajax.
	$xajax = new xajax();
	$xajax->configure('javascript URI', $APP->rutacomun . 'xajax/');
	$xajax->register(XAJAX_FUNCTION, 'sesion_abandona_V2');
	$xajax->processRequest();
	die();
}
$bEnSesion = false;
if ((int)$_SESSION['unad_id_tercero'] != 0) {
	$bEnSesion = true;
}
$iPiel = 1;
$grupo_id = 0;
$iCodModulo = 2200;

// -- Se cargan los archivos de idioma
$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
if (!file_exists($mensajes_todas)) {
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
}
$mensajes_269 = $APP->rutacomun . 'lg/lg_269_' . $_SESSION['unad_idioma'] . '.php';
if (!file_exists($mensajes_269)) {
	$mensajes_269 = $APP->rutacomun . 'lg/lg_269_es.php';
}
$mensajes_3000 = 'lg/lg_3000_' . $_SESSION['unad_idioma'] . '.php';
if (!file_exists($mensajes_3000)) {
	$mensajes_200 = 'lg/lg_3000_es.php';
}
require $mensajes_todas;
require $mensajes_269;
require $mensajes_3000;
$xajax = NULL;
$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
if ($APP->dbpuerto != '') {
	$objDB->dbPuerto = $APP->dbpuerto;
}
// -- Si esta cargando la pagina por primer vez se revisa si requiere auditar y se manda a hacer un limpiar (paso -1)
if (isset($_REQUEST['paso']) == 0) {
	list($sError, $sDebugC) = f3000_TablasMes(fecha_agno(), fecha_mes(), $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugC;
	$_REQUEST['paso'] = 0;
}
$iPiel = iDefinirPiel($APP, 1);
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
if ($bCerrado) {
	$objDB->CerrarConexion();
	require $APP->rutacomun . 'unad_forma_v2.php';
	forma_cabeceraV3($xajax, $ETI['titulo_2200']);
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
		header('Location:noticia.php?ret=index.php');
		die();
	}
}
//Esto activa los login, solo en los index abiertos
if ($_SESSION['unad_id_tercero'] == 0) {
	require $APP->rutacomun . 'unad_login.php';
	//Las variables de sesion se activan dentro de la función por lo que no es necesario volverlas a asignar.
	list($idTercero, $sErrorL, $sDebugL) = login_ActivarSesion($objDB, $bDebug);
}
//Termina de revisar el login
$idTercero = $_SESSION['unad_id_tercero'];
$bOtroUsuario = false;
$seg_1707 = 0;
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
		$sOcultaId = '';
	}
} else {
	$_REQUEST['debug'] = 0;
}
//PROCESOS DE LA PAGINA
//$idEntidad = Traer_Entidad();
// -- Si esta cargando la pagina por primer vez se revisa si requiere auditar y se manda a hacer un limpiar (paso -1)
if (isset($_REQUEST['paso']) == 0) {
	$_REQUEST['paso'] = 0;
}
// -- 269 aure69versionado
require $APP->rutacomun . 'lib269.php';
//require 'lib3000.php';
$xajax = new xajax();
$xajax->configure('javascript URI', $APP->rutacomun . 'xajax/');
$xajax->register(XAJAX_FUNCTION, 'sesion_abandona_V2');
$xajax->register(XAJAX_FUNCTION, 'sesion_mantenerV4');
$xajax->register(XAJAX_FUNCTION, 'f269_HtmlTablaSistema');
$xajax->register(XAJAX_FUNCTION, 'f3000_Combobita27equipotrabajo');
$xajax->register(XAJAX_FUNCTION, 'f3000_Combobita28eqipoparte');
$xajax->register(XAJAX_FUNCTION, 'f3000_HtmlTablaPQRS');
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
$iAgnoFin = fecha_agno();
// -- Se inicializan las variables, primero las que controlan la visualización de la página.
if (isset($_REQUEST['iscroll']) == 0) {
	$_REQUEST['iscroll'] = 0;
}
if (isset($_REQUEST['paginaf269']) == 0) {
	$_REQUEST['paginaf269'] = 1;
}
if (isset($_REQUEST['lppf269']) == 0) {
	$_REQUEST['lppf269'] = 20;
}
// Espacio para inicializar otras variables
if (isset($_REQUEST['blistar']) == 0) {
	$_REQUEST['blistar'] = 1;
}
//AQUI SE DEBEN CARGAR TODOS LOS DATOS QUE LA FORMA NECESITE.
//DATOS PARA COMPLETAR EL FORMULARIO
$sNombreUsuario = '';
if ($seg_1707 == 1) {
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
$seg_12 = 0;
list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 12, $idTercero, $objDB);
if ($bDevuelve) {
	$seg_12 = 1;
}
//Crear los controles que requieran llamado a base de datos
$objCombos = new clsHtmlCombos();
if ($seg_12 == 1) {
	$objCombos->nuevo('unae26unidadesfun', '', true, '{' . $ETI['msg_seleccione'] . '}');
	$objCombos->sAccion = 'carga_combo_bita27equipotrabajo();';
	$objCombos->iAncho = 370;
	$sSQL = 'SELECT unae26id AS id, CONCAT(unae26prefijo, "", unae26nombre) AS nombre FROM unae26unidadesfun WHERE unae26idzona=0 AND unae26id>0 ORDER BY unae26lugar, unae26nivel, unae26nombre';
	$html_unae26unidadesfun = $objCombos->html($sSQL, $objDB);
	$html_bita27equipotrabajo = f3000_HTMLComboV2_bita27equipotrabajo($objDB, $objCombos, '', '');
	$html_bita28eqipoparte = f3000_HTMLComboV2_bita28eqipoparte($objDB, $objCombos, '', '');
	$_REQUEST['blistar'] = 0;
} else {
	$objCombos->nuevo('blistar', $_REQUEST['blistar'], false);
	$objCombos->addItem('1', 'Mis asignaciones');
	$objCombos->addItem('2', 'Asignado a mi equipo');
	$objCombos->sAccion = 'paginarf3000()';
	$html_blistar = $objCombos->html('', $objDB);
}
$objCombos->nuevo('bagno', '', false, '{' . $ETI['msg_todos'] . '}');
$objCombos->sAccion = 'paginarf3000()';
$objCombos->numeros(2020, $iAgnoFin, 1);
$html_bagno = $objCombos->html('', $objDB);
$aParametros[100] = $idTercero;
$aParametros[106] = $iAgnoFin;
$aParametros[107] = $_REQUEST['blistar'];
list($sTabla3000, $iVenceRojo, $iVenceNaranja, $iVenceVerde, $iTiempoRojo, $iTiempoNaranja, $iTiempoVerde, $sDebugTabla) = f3000_TablaDetallePQRS($aParametros, $objDB, $bDebug);
$sDebug = $sDebug . $sDebugTabla;
//DATOS PARA COMPLETAR EL FORMULARIO
list($sHTMLPendientes, $iEnProceso, $iACargo, $iVencidas, $iEnSupervision, $sDebugD) = f3000_Estado($idTercero, $objDB, true, $bDebug, true);
$sDebug = $sDebug . $sDebugD;
//Cargar las tablas de datos
$aParametros[98] = $APP->idsistema;
$aParametros[100] = $idTercero;
$aParametros[101] = $_REQUEST['paginaf269'];
$aParametros[102] = $_REQUEST['lppf269'];
list($sTabla269, $sDebugTabla) = f269_TablaDetalleV2Sistema($aParametros, $objDB, $bDebug);
$sDebug = $sDebug . $sDebugTabla;
$bDebugMenu = false;
list($et_menu, $sDebugM) = html_menuV2($APP->idsistema, $objDB, $iPiel, $bDebugMenu, $idTercero);
$sDebug = $sDebug . $sDebugM;
$objDB->CerrarConexion();
//FORMA
require $APP->rutacomun . 'unad_forma_v2.php';
forma_cabeceraV3($xajax, $ETI['titulo_3000']);
echo $et_menu;
forma_mitad();
?>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>js/jquery-3.3.1.min.js"></script>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>js/popper.min.js"></script>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>js/bootstrap.min.js"></script>
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>js/bootstrap.min.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>css/criticalPath.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>css/principal.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>unad_estilos2018.css?v=2" type="text/css" />
<?php
if ($iPiel == 2) {
?>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>js/access.js"></script>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>js/mogu-menu-access.js"></script>
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>style-2023.css?v=2" type="text/css" />
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>css/mogu-menu-access.css" type="text/css" />
<?php
}
?>
<script language="javascript">
	function limpiapagina() {
		expandesector(98);
		window.document.frmedita.paso.value = -1;
		window.document.frmedita.submit();
	}

	function cambiapagina() {
		expandesector(98);
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
	}

	function paginarf269(id68 = 0) {
		let params = new Array();
		params[1] = id68;
		params[98] = <?php echo $APP->idsistema; ?>;
		params[99] = window.document.frmedita.debug.value;
		params[100] = <?php echo $idTercero; ?>;
		params[101] = window.document.frmedita.paginaf269.value;
		params[102] = window.document.frmedita.lppf269.value;
		//document.getElementById('div_f269detalle').innerHTML='<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf269" name="paginaf269" type="hidden" value="'+params[101]+'" /><input id="lppf269" name="lppf269" type="hidden" value="'+params[102]+'" />';
		xajax_f269_HtmlTablaSistema(params);
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
	<?php
	if ($seg_12 == 1) {
	?>

		function carga_combo_bita27equipotrabajo() {
			var params = new Array();
			params[0] = window.document.frmedita.unae26unidadesfun.value;
			document.getElementById('div_bita27equipotrabajo').innerHTML = '<b>Procesando datos, por favor espere...</b><input id="bita27equipotrabajo" name="bita27equipotrabajo" type="hidden" value="" />';
			xajax_f3000_Combobita27equipotrabajo(params);
		}

		function carga_combo_bita28eqipoparte() {
			var params = new Array();
			params[0] = window.document.frmedita.bita27equipotrabajo.value;
			document.getElementById('div_bita28eqipoparte').innerHTML = '<b>Procesando datos, por favor espere...</b><input id="bita28eqipoparte" name="bita28eqipoparte" type="hidden" value="" />';
			xajax_f3000_Combobita28eqipoparte(params);
		}
	<?php
	}
	?>

	function paginarf3000() {
		var params = new Array();
		params[99] = window.document.frmedita.debug.value;
		params[100] = <?php echo $idTercero; ?>;
		<?php
		if ($seg_12 == 1) {
		?>
			params[103] = window.document.frmedita.unae26unidadesfun.value;
			params[104] = window.document.frmedita.bita27equipotrabajo.value;
			params[105] = window.document.frmedita.bita28eqipoparte.value;
		<?php
		} else {
		?>
			params[107] = window.document.frmedita.blistar.value;
		<?php
		}
		?>
		params[106] = window.document.frmedita.bagno.value;
		// params[106] = window.document.frmedita.blistar.value;
		document.getElementById('div_f3000detalle').innerHTML = '<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf3000" name="paginaf3000" type="hidden" value="' + params[101] + '" /><input id="lppf3005" name="lppf3005" type="hidden" value="' + params[102] + '" />';
		xajax_f3000_HtmlTablaPQRS(params);
	}
</script>
<div id="interna">
<form id="frmedita" name="frmedita" method="post" action="" autocomplete="off">
<input id="bNoAutocompletar" name="bNoAutocompletar" type="password" value="" style="display:none;" />
<input id="paso" name="paso" type="hidden" value="0" />
<input id="shoy" name="shoy" type="hidden" value="<?php echo fecha_hoy(); ?>" />
<input id="shora" name="shora" type="hidden" value="<?php echo fecha_hora(); ?>" />
<input id="stipodoc" name="stipodoc" type="hidden" value="<?php echo $APP->tipo_doc; ?>" />
<input id="idusuario" name="idusuario" type="hidden" value="<?php echo $_SESSION['unad_id_tercero']; ?>" />
<input id="ipiel" name="ipiel" type="hidden" value="<?php echo $iPiel; ?>" />
<div id="div_sector1">
<div class="titulos">
<div class="titulosD">
<input id="cmdAyuda" name="cmdAyuda" type="button" class="btUpAyuda" onclick="muestraayuda('','');" title="<?php echo $ETI['bt_ayuda']; ?>" value="<?php echo $ETI['bt_ayuda']; ?>" />
<?php
?>
</div>
<div class="titulosI">
<?php
echo '<h2>' . $ETI['titulo'] . '</h2>';
?>
</div>
</div>
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
echo $sHTMLPendientes;
?>
<div class="salto1px"></div>
<div id="div_f269detalle">
<?php
echo $sTabla269;
?>
</div>
</div><!-- CIERRA EL DIV areatrabajo -->
</div><!-- CIERRA EL DIV areaform -->
<div class="areaform">
<div class="areatrabajo">
<label class="TituloGrupo">
<?php
echo $ETI['tableropqrs'];
?>
</label>
<div class="salto1px"></div>
<?php
if ($seg_12 == 1) {
?>
<label class="Label90">
<?php
echo $ETI['unidadresp'];
?>
</label>
<label class="Label450">
<?php
echo $html_unae26unidadesfun;
?>
</label>
<div class="salto1px"></div>
<label class="Label90">
<?php
echo $ETI['equiporesp'];
?>
</label>
<label class="Label380">
<div id="div_bita27equipotrabajo">
<?php
echo $html_bita27equipotrabajo;
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label90">
<?php
echo $ETI['responsable'];
?>
</label>
<label class="Label380">
<div id="div_bita28eqipoparte">
<?php
echo $html_bita28eqipoparte;
?>
</div>
</label>
<?php
} else {
?>
<label class="Label90">
<?php
echo $ETI['msg_blistar'];
?>
</label>
<label class="Label450">
<?php
echo $html_blistar;
?>
</label>
<?php
}
?>
<label class="Label60">
<?php
echo $ETI['agno'];
?>
</label>
<label class="Label90">
<?php
echo $html_bagno;
?>
</label>
<div class="salto1px"></div>
<div id="div_f3000detalle">
<?php
echo $sTabla3000;
?>
</div>
<div class="GrupoCampos300">
<label class="TituloGrupo">
<?php
echo $ETI['vence_solicitud'];
?>
</label>
<label class="Label200 fondoRojo">
<?php
echo $ETI['vence_rojo'];
?>
</label>
<label class="Label60 fondoRojo">
<div id="div_f3000vencerojo">
<?php
echo $iVenceRojo;
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label200 fondoNaranja">
<?php
echo $ETI['vence_naranja'];
?>
</label>
<label class="Label60 fondoNaranja">
<div id="div_f3000vencenaranja">
<?php
echo $iVenceNaranja;
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label200 fondoVerde">
<?php
echo $ETI['vence_verde'];
?>
</label>
<label class="Label60 fondoVerde">
<div id="div_f3000venceverde">
<?php
echo $iVenceVerde;
?>
</div>
</label>
<div class="salto1px"></div>
</div>
<div class="GrupoCampos300">
<label class="TituloGrupo">
<?php
echo $ETI['tiempos_resp'];
?>
</label>
<label class="Label200 fondoRojo">
<?php
echo $ETI['tiempos_rojo'];
?>
</label>
<label class="Label60 fondoRojo">
<div id="div_f3000tiemporojo">
<?php
echo $iTiempoRojo;
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label200 fondoNaranja">
<?php
echo $ETI['tiempos_naranja'];
?>
</label>
<label class="Label60 fondoNaranja">
<div id="div_f3000tiemponaranja">
<?php
echo $iTiempoNaranja;
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label200 fondoVerde">
<?php
echo $ETI['tiempos_verde'];
?>
</label>
<label class="Label60 fondoVerde">
<div id="div_f3000tiempoverde">
<?php
echo $iTiempoVerde;
?>
</div>
</label>
<div class="salto1px"></div>
</div>
</div><!-- CIERRA EL DIV areatrabajo -->
</div><!-- CIERRA EL DIV areaform -->
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
<input id="titulo_3000" name="titulo_3000" type="hidden" value="<?php echo $ETI['titulo_3000']; ?>" />
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
echo '<h2>' . $ETI['titulo_3000'] . '</h2>';
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
<script language="javascript" src="<?php echo $APP->rutacomun; ?>unad_todas.js?ver=8"></script>
<?php
forma_piedepagina();

