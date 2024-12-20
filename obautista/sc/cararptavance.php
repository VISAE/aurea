<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2018 - 2020 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.22.3 jueves, 23 de agosto de 2018
--- Modelo Versión 2.24.1 viernes, 31 de enero de 2020
*/

/** Archivo cararptavance.php.
 * Modulo 2357 cara57rptavance.
 * @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
 * @param debug=1 (Opcional), bandera para indicar si se generan datos de depuración
 * @date viernes, 31 de enero de 2020
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
if (($bPeticionXAJAX) && ($_SESSION['unad_id_tercero'] == 0)) {
	// viene por xajax.
	$xajax = new xajax();
	$xajax->configure('javascript URI', $APP->rutacomun . 'xajax/');
	$xajax->register(XAJAX_FUNCTION, 'sesion_abandona_V2');
	$xajax->processRequest();
	die();
}
$grupo_id = 1; //Necesita ajustarlo...
$iCodModulo = 2357;
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
$mensajes_2357 = 'lg/lg_2357_' . $_SESSION['unad_idioma'] . '.php';
if (!file_exists($mensajes_2357)) {
	$mensajes_2357 = 'lg/lg_2357_es.php';
}
require $mensajes_todas;
require $mensajes_2357;
$xajax = NULL;
$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
if ($APP->dbpuerto != '') {
	$objDB->dbPuerto = $APP->dbpuerto;
}
if (isset($APP->piel) == 0) {
	$APP->piel = 1;
}
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
list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 1, $_SESSION['unad_id_tercero'], $objDB);
if (!$bDevuelve) {
	header('Location:nopermiso.php');
	die();
}
if (!$bPeticionXAJAX) {
	if (noticias_pendientes($objDB)) {
		$objDB->CerrarConexion();
		header('Location:noticia.php?ret=cararptavance.php');
		die();
	}
}
$idTercero = $_SESSION['unad_id_tercero'];
$bOtroUsuario = false;
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
// -- Si esta cargando la pagina por primer vez se revisa si requiere auditar y se manda a hacer un limpiar (paso -1)
if (isset($_REQUEST['paso']) == 0) {
	$_REQUEST['paso'] = -1;
	if ($audita[1]) {
		seg_auditaingreso($iCodModulo, $_SESSION['unad_id_tercero'], $objDB);
	}
}
// -- 2357 cara57rptavance
require 'lib2357.php';
$xajax = new xajax();
$xajax->configure('javascript URI', $APP->rutacomun . 'xajax/');
$xajax->register(XAJAX_FUNCTION, 'sesion_abandona_V2');
$xajax->register(XAJAX_FUNCTION, 'sesion_mantenerV4');
$xajax->register(XAJAX_FUNCTION, 'f2357_HtmlTabla');
$xajax->register(XAJAX_FUNCTION, 'f2357_HtmlDashboard');
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
if (isset($_REQUEST['paginaf2357']) == 0) {
	$_REQUEST['paginaf2357'] = 1;
}
if (isset($_REQUEST['lppf2357']) == 0) {
	$_REQUEST['lppf2357'] = 20;
}
if (isset($_REQUEST['boculta2357']) == 0) {
	$_REQUEST['boculta2357'] = 0;
}
if (isset($_REQUEST['boculta101']) == 0) {
	$_REQUEST['boculta101'] = 0;
}
if (isset($_REQUEST['boculta102']) == 0) {
	$_REQUEST['boculta102'] = 0;
}
if (isset($_REQUEST['boculta103']) == 0) {
	$_REQUEST['boculta103'] = 0;
}
if (isset($_REQUEST['boculta104']) == 0) {
	$_REQUEST['boculta104'] = 0;
}
if (isset($_REQUEST['boculta105']) == 0) {
	$_REQUEST['boculta105'] = 0;
}
if (isset($_REQUEST['boculta106']) == 0) {
	$_REQUEST['boculta106'] = 0;
}
if (isset($_REQUEST['boculta107']) == 0) {
	$_REQUEST['boculta107'] = 0;
}
if (isset($_REQUEST['boculta108']) == 0) {
	$_REQUEST['boculta108'] = 0;
}
if (isset($_REQUEST['boculta109']) == 0) {
	$_REQUEST['boculta109'] = 0;
}
if (isset($_REQUEST['boculta110']) == 0) {
	$_REQUEST['boculta110'] = 0;
}
if (isset($_REQUEST['boculta111']) == 0) {
	$_REQUEST['boculta111'] = 0;
}
// -- Inicializar variables de datos.
if (isset($_REQUEST['cara57peraca']) == 0) {
	$_REQUEST['cara57peraca'] = '';
}
// Espacio para inicializar otras variables
if (isset($_REQUEST['csv_separa']) == 0) {
	$_REQUEST['csv_separa'] = ',';
}
if (isset($_REQUEST['bnombre']) == 0) {
	$_REQUEST['bnombre'] = '';
}
//if (isset($_REQUEST['blistar'])==0){$_REQUEST['blistar']='';}
if (isset($_REQUEST['ficha']) == 0) {
	$_REQUEST['ficha'] = 1;
}
//Procesar la matricula del periodo
if ($_REQUEST['paso'] == 23) {
	$_REQUEST['paso'] = 2;
	if ($sError == '') {
		//la funcion esta en la libcore y la lib2216
		require $APP->rutacomun . 'lib2216.php';
		set_time_limit(0);
		$idPeraca = $_REQUEST['cara57peraca'];
		$iTotal = 0;
		if (isset($_REQUEST['total']) == 0) {
			$_REQUEST['total'] = 0;
		}
		if ($_REQUEST['total'] == 1) {
			$iTotal = 1;
		}
		list($sError, $iProcesados, $sDebugM) = f2216_MatricularPeriodo($idPeraca, $objDB, $bDebug, $iTotal);
		$sDebug = $sDebug . $sDebugM;
		if ($sError == '') {
			$sError = 'Se ha revisado la matricula, ' . $iProcesados . ' registros procesados.';
			$iTipoError = 1;
		}
	}
}
//AQUI SE DEBEN CARGAR TODOS LOS DATOS QUE LA FORMA NECESITE.
//DATOS PARA COMPLETAR EL FORMULARIO
//Permisos adicionales
$seg_5 = 0;
$seg_6 = 0;
list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 6, $idTercero, $objDB);
if ($bDevuelve) {
	$seg_6 = 1;
}
//if ($seg_6==1){}
//Crear los controles que requieran llamado a base de datos
$objCombos = new clsHtmlCombos();
$objCombos->nuevo('cara57peraca', $_REQUEST['cara57peraca'], true, '{' . $ETI['msg_seleccione'] . '}');
$objCombos->sAccion = 'paginarf2357()';
$sIds = '-99';
$sSQL = 'SELECT core16peraca FROM core16actamatricula GROUP BY core16peraca';
$tabla = $objDB->ejecutasql($sSQL);
while ($fila = $objDB->sf($tabla)) {
	$sIds = $sIds . ',' . $fila['core16peraca'];
}
$sSQL = 'SELECT exte02id FROM exte02per_aca WHERE exte02vigente="S"';
$tabla = $objDB->ejecutasql($sSQL);
while ($fila = $objDB->sf($tabla)) {
	$sIds = $sIds . ',' . $fila['exte02id'];
}
$sWhere = 'exte02id IN (' . $sIds . ')';

$sSQL = f146_ConsultaCombo($sWhere, $objDB);
$html_cara57peraca = $objCombos->html($sSQL, $objDB);
if ((int)$_REQUEST['paso'] == 0) {
} else {
}
//Alistar datos adicionales
$id_rpt = 0;
//$id_rpt=reportes_id(_Identificador_Tipo_Reporte_, $objDB);
/*
$objCombos->nuevo('blistar', $_REQUEST['blistar'], true, '{'.$ETI['msg_todos'].'}');
$objCombos->sAccion='paginarf2357()';
$html_blistar=$objCombos->html('', $objDB);
//$html_blistar=$objCombos->comboSistema(2357, 1, $objDB, 'paginarf2357()');
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
$iModeloReporte = 2357;
$html_iFormatoImprime = '<input id="iformatoimprime" name="iformatoimprime" type="hidden" value="0" />
';
//list($bDevuelve, $sDebugP)=seg_revisa_permisoV3($iCodModulo, 5, $idTercero, $objDB);
if ($bDevuelve) {
	$seg_5 = 1;
}
//Cargar las tablas de datos
$sHTMLMapa = '';
$sScriptMapa = '';
$aDetalle = array();
$aParametros[0] = ''; //$_REQUEST['p1_2357'];
$aParametros[100] = $idTercero;
$aParametros[101] = $_REQUEST['paginaf2357'];
$aParametros[102] = $_REQUEST['lppf2357'];
$aParametros[103] = $_REQUEST['cara57peraca'];
//$aParametros[104]=$_REQUEST['blistar'];
list($sTabla2357, $sDebugTabla) = f2357_TablaDetalleV2($aParametros, $objDB, $bDebug);
$sDebug = $sDebug . $sDebugTabla;
if ((int)$_REQUEST['cara57peraca'] != 0){
	list($aDetalle, $sHTMLMapa, $sScriptMapa, $sDebugTabla)=f2357_DashboardDetalle($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
}
$bDebugMenu = false;
list($et_menu, $sDebugM) = html_menuV2($APP->idsistema, $objDB, $iPiel, $bDebugMenu, $idTercero);
$sDebug = $sDebug . $sDebugM;
$objDB->CerrarConexion();
//FORMA
require $APP->rutacomun . 'unad_forma_v2.php';
forma_cabeceraV3($xajax, $ETI['titulo_2357']);
echo $et_menu;
forma_mitad();
?>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>js/jquery-3.3.1.min.js"></script>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>js/popper.min.js"></script>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>js/bootstrap.min.js"></script>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>js/Chart.min.js"></script>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>js/Chartjs-plugin-datalabels.js"></script>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>js/jquery.qtip.min.js"></script>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>js/jquery.maphilight.min.js"></script>
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>js/bootstrap.min.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>css/criticalPath.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>css/principal.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>unad_estilos2018.css?v=2" type="text/css" />
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>js/Chart.min.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>js/jquery.qtip.min.css" type="text/css" />
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
		document.getElementById('div_sector95').style.display = 'none';
		document.getElementById('div_sector96').style.display = 'none';
		document.getElementById('div_sector98').style.display = 'none';
		document.getElementById('div_sector' + codigo).style.display = 'block';
	}

	function imprimelista() {
		if (window.document.frmedita.seg_6.value == 1) {
			window.document.frmlista.consulta.value = window.document.frmedita.consulta_2357.value;
			window.document.frmlista.titulos.value = window.document.frmedita.titulos_2357.value;
			window.document.frmlista.nombrearchivo.value = 'Avance encuestas';
			window.document.frmlista.submit();
		} else {
			window.alert("<?php echo $ERR['6']; ?>");
		}
	}

	function asignarvariables() {
		window.document.frmimpp.v3.value = window.document.frmedita.cara57peraca.value;
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
			window.document.frmimpp.action = 'e2357.php';
			window.document.frmimpp.submit();
		} else {
			window.alert(sError);
		}
	}

	function imprimep() {
		if (window.document.frmedita.seg_5.value == 1) {
			asignarvariables();
			window.document.frmimpp.action = 'p2357.php';
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

	function paginarf2357() {
		var params = new Array();
		params[99] = window.document.frmedita.debug.value;
		params[100] = <?php echo $idTercero; ?>;
		params[101] = window.document.frmedita.paginaf2357.value;
		params[102] = window.document.frmedita.lppf2357.value;
		params[103] = window.document.frmedita.cara57peraca.value;
		//params[104]=window.document.frmedita.blistar.value;
		document.getElementById('div_f2357detalle').innerHTML = '<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf2357" name="paginaf2357" type="hidden" value="' + params[101] + '" /><input id="lppf2357" name="lppf2357" type="hidden" value="' + params[102] + '" />';
		document.getElementById('div_f2357dashboard').style.display = 'none';
		xajax_f2357_HtmlTabla(params);
		//xajax_f2357_HtmlDashboard(params);
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
		document.getElementById("cara57peraca").focus();
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

	function procesarmatricula(iObliga = 0) {
		if (confirm('Este proceso podra tomar bastante tiempo, desea continuar?')) {
			MensajeAlarmaV2('Procesando matricula...', 2);
			expandesector(98);
			window.document.frmedita.total.value = iObliga;
			window.document.frmedita.paso.value = 23;
			window.document.frmedita.submit();
		}
	}
	// 
</script>
<script language="javascript" src="jsi/js2357.js?ver=2"></script>
<form id="frmimpp" name="frmimpp" method="post" action="p2357.php" target="_blank">
<input id="r" name="r" type="hidden" value="2357" />
<input id="v3" name="v3" type="hidden" value="" />
<input id="v4" name="v4" type="hidden" value="" />
<input id="v5" name="v5" type="hidden" value="" />
<input id="iformato94" name="iformato94" type="hidden" value="0" />
<input id="separa" name="separa" type="hidden" value="," />
<input id="rdebug" name="rdebug" type="hidden" value="<?php echo $_REQUEST['debug']; ?>" />
<input id="clave" name="clave" type="hidden" value="" />
</form>
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
<input id="seg_5" name="seg_5" type="hidden" value="<?php echo $seg_5; ?>" />
<input id="seg_6" name="seg_6" type="hidden" value="<?php echo $seg_6; ?>" />
<input id="total" name="total" type="hidden" value="" />
<div id="div_sector1">
<div class="titulos">
<div class="titulosD">
<input id="cmdAyuda" name="cmdAyuda" type="button" class="btUpAyuda" onclick="muestraayuda(<?php echo $APP->idsistema . ', ' . $iCodModulo; ?>);" title="<?php echo $ETI['bt_ayuda']; ?>" value="<?php echo $ETI['bt_ayuda']; ?>" />
<?php
$bHayImprimir = false;
$sScript = 'imprimeexcel()';
$sClaseBoton = 'btEnviarExcel';
if ($seg_6 == 1) {
$bHayImprimir = true;
}
if ($bHayImprimir) {
?>
<input id="cmdImprimir" name="cmdImprimir" type="button" class="<?php echo $sClaseBoton; ?>" onclick="<?php echo $sScript; ?>" title="<?php echo $ETI['bt_imprimir']; ?>" value="<?php echo $ETI['bt_imprimir']; ?>" />
<?php
}
$bHayImprimir2 = false;
$sScript = 'imprimep()';
$sClaseBoton = 'btEnviarPDF'; //btUpPrint
if ($seg_5 == 1) {
$bHayImprimir2 = true;
}
if ($bHayImprimir2) {
?>
<input id="cmdImprimir2" name="cmdImprimir2" type="button" class="<?php echo $sClaseBoton; ?>" onclick="<?php echo $sScript; ?>" title="<?php echo $ETI['bt_imprimir']; ?>" value="<?php echo $ETI['bt_imprimir']; ?>" />
<?php
}
?>
<input id="cmdLimpiar" name="cmdLimpiar" type="button" class="btUpLimpiar" onclick="limpiapagina();" title="<?php echo $ETI['bt_limpiar']; ?>" value="<?php echo $ETI['bt_limpiar']; ?>" />
<?php
?>
</div>
<div class="titulosI">
<?php
echo '<h2>' . $ETI['titulo_2357'] . '</h2>';
?>
</div>
</div>
<div class="areaform">
<div class="areatrabajo">
<input id="ficha" name="ficha" type="hidden" value="<?php echo $_REQUEST['ficha']; ?>" />
<input id="boculta101" name="boculta101" type="hidden" value="<?php echo $_REQUEST['boculta101']; ?>" />
<input id="boculta102" name="boculta102" type="hidden" value="<?php echo $_REQUEST['boculta102']; ?>" />
<input id="boculta103" name="boculta103" type="hidden" value="<?php echo $_REQUEST['boculta103']; ?>" />
<input id="boculta104" name="boculta104" type="hidden" value="<?php echo $_REQUEST['boculta104']; ?>" />
<input id="boculta105" name="boculta105" type="hidden" value="<?php echo $_REQUEST['boculta105']; ?>" />
<input id="boculta106" name="boculta106" type="hidden" value="<?php echo $_REQUEST['boculta106']; ?>" />
<input id="boculta107" name="boculta107" type="hidden" value="<?php echo $_REQUEST['boculta107']; ?>" />
<input id="boculta108" name="boculta108" type="hidden" value="<?php echo $_REQUEST['boculta108']; ?>" />
<input id="boculta109" name="boculta109" type="hidden" value="<?php echo $_REQUEST['boculta109']; ?>" />
<input id="boculta110" name="boculta110" type="hidden" value="<?php echo $_REQUEST['boculta110']; ?>" />
<input id="boculta111" name="boculta111" type="hidden" value="<?php echo $_REQUEST['boculta111']; ?>" />
<?php
$bGrupo1 = true;
$bGrupo2 = false;
$bGrupo3 = false;
$bGrupo4 = false;
$bGrupo5 = false;
$bGrupo6 = false;
$bGrupo7 = false;
$bGrupo8 = false;
$bGrupo9 = false;
$bGrupo10 = false;
$bGrupo11 = false;
//Div para ocultar
$bconexpande = false;
if ($bconexpande) {
?>
<div class="ir_derecha" style="width:62px;">
<input id="boculta2357" name="boculta2357" type="hidden" value="<?php echo $_REQUEST['boculta2357']; ?>" />
<label class="Label30">
<input id="btexpande2357" name="btexpande2357" type="button" value="Mostrar" class="btMiniExpandir" onclick="expandepanel(2357,'block',0);" title="<?php echo $ETI['bt_mostrar']; ?>" style="display:<?php if ($_REQUEST['boculta2357'] == 0) {
echo 'none';
} else {
echo 'block';
} ?>;" />
</label>
<label class="Label30">
<input id="btrecoge2357" name="btrecoge2357" type="button" value="Ocultar" class="btMiniRecoger" onclick="expandepanel(2357,'none',1);" title="<?php echo $ETI['bt_ocultar']; ?>" style="display:<?php if ($_REQUEST['boculta2357'] == 0) {
echo 'block';
} else {
echo 'none';
} ?>;" />
</label>
</div>
<div id="div_p2357" style="display:<?php if ($_REQUEST['boculta2357'] == 0) {
echo 'block';
} else {
echo 'none';
} ?>;">
<?php
}
//Mostrar formulario para editar
?>
<label class="Label90">
<?php
echo $ETI['cara57peraca'];
?>
</label>
<label>
<?php
echo $html_cara57peraca;
?>
</label>
<?php
if (false) {
//Ejemplo de boton de ayuda
//echo html_BotonAyuda('NombreCampo');
//echo html_DivAyudaLocal('NombreCampo');
}
if ($bconexpande) {
//Este es el cierre del div_p2357
?>
<div class="salto1px"></div>
</div>
<?php
}
//Mostrar el contenido de la tabla
?>
<?php
?>
<?php
echo ' ' . $csv_separa;
?>
<div class="salto1px"></div>
<div id="div_f2357detalle">
<?php
echo $sTabla2357;
?>
</div>

<?php
$sEstilo = ' style="display:none"';
//$_REQUEST['bocultaDashboard'] = 1;
$_REQUEST['bocultaDashboard'] = 0;
if ((int)$_REQUEST['cara57peraca'] != 0){
	$sEstilo = '';
	$_REQUEST['bocultaDashboard'] = 0;
}
?>
<div id="div_f2357dashboard"<?php echo $sEstilo; ?>>
<?php
$sPrevTitulo = '<hr />
<b>';
$sPrevTitulo2 = '<b>';
$sSufTitulo = '</b>';
?>
<div class="salto1px"></div>
<?php
echo $sPrevTitulo . $ETI['cara57dashboard'] . $sSufTitulo;
?>
<input id="bocultaDashboard" name="bocultaDashboard" type="hidden" value="<?php echo $_REQUEST['bocultaDashboard']; ?>" />
<div class="ir_derecha" style="width:62px;">
<label class="Label30">
<input id="btexpandeDashboard" name="btexpandeDashboard" type="button" value="Expandir" class="btMiniExpandir" onclick="expandepanel('Dashboard','block',0);" title="<?php echo $ETI['bt_mostrar']; ?>" style="display:<?php if ($_REQUEST['bocultaDashboard'] == 0) {
echo 'none';
} else {
echo 'block';
} ?>;" />
</label>
<label class="Label30">
<input id="btrecogeDashboard" name="btrecogeDashboard" type="button" value="Recoger" class="btMiniRecoger" onclick="expandepanel('Dashboard','none',1);" title="<?php echo $ETI['bt_ocultar']; ?>" style="display:<?php if ($_REQUEST['bocultaDashboard'] == 0) {
echo 'block';
} else {
echo 'none';
} ?>;" />
</label>
</div>
<div class="salto1px"></div>
<div id="div_pDashboard" style="display:<?php if ($_REQUEST['bocultaDashboard'] == 0) {
echo 'block';
} else {
echo 'none';
} ?>;">
<?php
$bconexpande = true;
if ($bGrupo1) {
$sEstilo = '';
if ($sTabla2357) {
$sEstilo = ' style="display:none"';
if ($_REQUEST['ficha'] == 1) {
$sEstilo = '';
}
}
?>
<div id="div_f2357grafico" class="GrupoCampos450"><?php echo $sHTMLMapa; ?></div>
<div class="salto1px"></div>
<div class="GrupoCampos" id="div_ficha1" <?php echo $sEstilo; ?>>
<?php
if ($bconexpande) {
?>
<div class="ir_derecha" style="width:62px;">
<label class="Label30">
<input id="btexpande101" name="btexpande101" type="button" value="Mostrar" class="btMiniExpandir" onclick="expandepanel(101,'block',0);" title="<?php echo $ETI['bt_mostrar']; ?>" style="display:<?php if ($_REQUEST['boculta101'] == 0) {
echo 'block';
} else {
echo 'none';
} ?>;" />
</label>
<label class="Label30">
<input id="btrecoge101" name="btrecoge101" type="button" value="Ocultar" class="btMiniRecoger" onclick="expandepanel(101,'none',1);" title="<?php echo $ETI['bt_ocultar']; ?>" style="display:<?php if ($_REQUEST['boculta101'] == 0) {
echo 'none';
} else {
echo 'block';
} ?>;" />
</label>
</div>
<?php
}
?>
<label class="TituloGrupo">
<?php
echo $ETI['cara57infgral'];
?>
</label>
<div class="salto1px"></div>
<div id="div_p101" style="display:<?php if ($_REQUEST['boculta101'] == 0) {
echo 'none';
} else {
echo 'block';
} ?>;">
<div class="GrupoCampos520">
<label class="TituloGrupo">
<?php
echo 'Matriculados por Zonas';
?>
</label>
<div class="salto1px"></div>
<div id="div_f2357detalle_aZonas">
</div>
</div>
<div class="GrupoCampos520">
<label class="TituloGrupo">
<?php
echo 'Matriculados por Sedes';
?>
</label>
<div class="salto1px"></div>
<div id="div_f2357detalle_aSedes">
</div>
</div>
<div class="salto1px"></div>
<div class="GrupoCampos520">
<label class="TituloGrupo">
<?php
echo 'Matriculados por Escuelas';
?>
</label>
<div class="salto1px"></div>
<div id="div_f2357detalle_aEscuelas">
</div>
</div>
<div class="GrupoCampos520">
<label class="TituloGrupo">
<?php
echo 'Matriculados por Programas';
?>
</label>
<div class="salto1px"></div>
<div id="div_f2357detalle_aProgramas">
</div>
</div>
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
</div>
<?php
}
if ($bGrupo2) {
$sEstilo = '';
if ($sTabla2357) {
$sEstilo = '';
if ($_REQUEST['ficha'] == 2) {
$sEstilo = '';
}
}
?>
<div class="salto1px"></div>
<div class="GrupoCampos" id="div_ficha2" <?php echo $sEstilo; ?>>
<?php
if ($bconexpande) {
?>
<div class="ir_derecha" style="width:62px;">
<label class="Label30">
<input id="btexpande102" name="btexpande102" type="button" value="Mostrar" class="btMiniExpandir" onclick="expandepanel(102,'block',0);" title="<?php echo $ETI['bt_mostrar']; ?>" style="display:<?php if ($_REQUEST['boculta102'] == 0) {
echo 'block';
} else {
echo 'none';
} ?>;" />
</label>
<label class="Label30">
<input id="btrecoge102" name="btrecoge102" type="button" value="Ocultar" class="btMiniRecoger" onclick="expandepanel(102,'none',1);" title="<?php echo $ETI['bt_ocultar']; ?>" style="display:<?php if ($_REQUEST['boculta102'] == 0) {
echo 'none';
} else {
echo 'block';
} ?>;" />
</label>
</div>
<?php
}
?>
<label class="TituloGrupo">
<?php
echo $ETI['cara57aspsocdem'];
?>
</label>
<div class="salto1px"></div>
<div id="div_p102" style="display:<?php if ($_REQUEST['boculta102'] == 0) {
echo 'none';
} else {
echo 'block';
} ?>;">
<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo 'Grafico n';
?>
</label>
<div class="salto1px"></div>
</div>
<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo 'Grafico n';
?>
</label>
<div class="salto1px"></div>
<div id="div_cara01idconfirmadisc_llaves">
</div>
</div>
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
</div>
<?php
}
if ($bGrupo3) {
$sEstilo = '';
if ($sTabla2357) {
$sEstilo = '';
if ($_REQUEST['ficha'] == 3) {
$sEstilo = '';
}
}
?>
<div class="salto1px"></div>
<div class="GrupoCampos" id="div_ficha3" <?php echo $sEstilo; ?>>
<?php
if ($bconexpande) {
?>
<div class="ir_derecha" style="width:63px;">
<label class="Label30">
<input id="btexpande103" name="btexpande103" type="button" value="Mostrar" class="btMiniExpandir" onclick="expandepanel(103,'block',0);" title="<?php echo $ETI['bt_mostrar']; ?>" style="display:<?php if ($_REQUEST['boculta103'] == 0) {
echo 'block';
} else {
echo 'none';
} ?>;" />
</label>
<label class="Label30">
<input id="btrecoge103" name="btrecoge103" type="button" value="Ocultar" class="btMiniRecoger" onclick="expandepanel(103,'none',1);" title="<?php echo $ETI['bt_ocultar']; ?>" style="display:<?php if ($_REQUEST['boculta103'] == 0) {
echo 'none';
} else {
echo 'block';
} ?>;" />
</label>
</div>
<?php
}
?>
<label class="TituloGrupo">
<?php
echo $ETI['cara57etnias'];
?>
</label>
<div class="salto1px"></div>
<div id="div_p103" style="display:<?php if ($_REQUEST['boculta103'] == 0) {
echo 'none';
} else {
echo 'block';
} ?>;">
<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo 'Grafico n';
?>
</label>
<div class="salto1px"></div>
</div>
<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo 'Grafico n';
?>
</label>
<div class="salto1px"></div>
<div id="div_cara01idconfirmadisc_llaves">
</div>
</div>
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
</div>
<?php
}
if ($bGrupo4) {
$sEstilo = '';
if ($sTabla2357) {
$sEstilo = '';
if ($_REQUEST['ficha'] == 4) {
$sEstilo = '';
}
}
?>
<div class="salto1px"></div>
<div class="GrupoCampos" id="div_ficha4" <?php echo $sEstilo; ?>>
<?php
if ($bconexpande) {
?>
<div class="ir_derecha" style="width:63px;">
<label class="Label30">
<input id="btexpande104" name="btexpande104" type="button" value="Mostrar" class="btMiniExpandir" onclick="expandepanel(104,'block',0);" title="<?php echo $ETI['bt_mostrar']; ?>" style="display:<?php if ($_REQUEST['boculta104'] == 0) {
echo 'block';
} else {
echo 'none';
} ?>;" />
</label>
<label class="Label30">
<input id="btrecoge104" name="btrecoge104" type="button" value="Ocultar" class="btMiniRecoger" onclick="expandepanel(104,'none',1);" title="<?php echo $ETI['bt_ocultar']; ?>" style="display:<?php if ($_REQUEST['boculta104'] == 0) {
echo 'none';
} else {
echo 'block';
} ?>;" />
</label>
</div>
<?php
}
?>
<label class="TituloGrupo">
<?php
echo $ETI['cara57disc'];
?>
</label>
<div class="salto1px"></div>
<div id="div_p104" style="display:<?php if ($_REQUEST['boculta104'] == 0) {
echo 'none';
} else {
echo 'block';
} ?>;">
<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo 'Grafico n';
?>
</label>
<div class="salto1px"></div>
</div>
<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo 'Grafico n';
?>
</label>
<div class="salto1px"></div>
<div id="div_cara01idconfirmadisc_llaves">
</div>
</div>
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
</div>
<?php
}
if ($bGrupo5) {
$sEstilo = '';
if ($sTabla2357) {
$sEstilo = '';
if ($_REQUEST['ficha'] == 5) {
$sEstilo = '';
}
}
?>
<div class="salto1px"></div>
<div class="GrupoCampos" id="div_ficha5" <?php echo $sEstilo; ?>>
<?php
if ($bconexpande) {
?>
<div class="ir_derecha" style="width:63px;">
<label class="Label30">
<input id="btexpande105" name="btexpande105" type="button" value="Mostrar" class="btMiniExpandir" onclick="expandepanel(105,'block',0);" title="<?php echo $ETI['bt_mostrar']; ?>" style="display:<?php if ($_REQUEST['boculta105'] == 0) {
echo 'block';
} else {
echo 'none';
} ?>;" />
</label>
<label class="Label30">
<input id="btrecoge105" name="btrecoge105" type="button" value="Ocultar" class="btMiniRecoger" onclick="expandepanel(105,'none',1);" title="<?php echo $ETI['bt_ocultar']; ?>" style="display:<?php if ($_REQUEST['boculta105'] == 0) {
echo 'none';
} else {
echo 'block';
} ?>;" />
</label>
</div>
<?php
}
?>
<label class="TituloGrupo">
<?php
echo $ETI['cara57aspacad'];
?>
</label>
<div class="salto1px"></div>
<div id="div_p105" style="display:<?php if ($_REQUEST['boculta105'] == 0) {
echo 'none';
} else {
echo 'block';
} ?>;">
<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo 'Grafico n';
?>
</label>
<div class="salto1px"></div>
</div>
<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo 'Grafico n';
?>
</label>
<div class="salto1px"></div>
<div id="div_cara01idconfirmadisc_llaves">
</div>
</div>
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
</div>
<?php
}
if ($bGrupo6) {
$sEstilo = '';
if ($sTabla2357) {
$sEstilo = '';
if ($_REQUEST['ficha'] == 6) {
$sEstilo = '';
}
}
?>
<div class="salto1px"></div>
<div class="GrupoCampos" id="div_ficha6" <?php echo $sEstilo; ?>>
<?php
if ($bconexpande) {
?>
<div class="ir_derecha" style="width:63px;">
<label class="Label30">
<input id="btexpande106" name="btexpande106" type="button" value="Mostrar" class="btMiniExpandir" onclick="expandepanel(106,'block',0);" title="<?php echo $ETI['bt_mostrar']; ?>" style="display:<?php if ($_REQUEST['boculta106'] == 0) {
echo 'block';
} else {
echo 'none';
} ?>;" />
</label>
<label class="Label30">
<input id="btrecoge106" name="btrecoge106" type="button" value="Ocultar" class="btMiniRecoger" onclick="expandepanel(106,'none',1);" title="<?php echo $ETI['bt_ocultar']; ?>" style="display:<?php if ($_REQUEST['boculta106'] == 0) {
echo 'none';
} else {
echo 'block';
} ?>;" />
</label>
</div>
<?php
}
?>
<label class="TituloGrupo">
<?php
echo $ETI['cara57aspext'];
?>
</label>
<div class="salto1px"></div>
<div id="div_p106" style="display:<?php if ($_REQUEST['boculta106'] == 0) {
echo 'none';
} else {
echo 'block';
} ?>;">
<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo 'Grafico n';
?>
</label>
<div class="salto1px"></div>
</div>
<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo 'Grafico n';
?>
</label>
<div class="salto1px"></div>
<div id="div_cara01idconfirmadisc_llaves">
</div>
</div>
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
</div>
<?php
}
if ($bGrupo7) {
$sEstilo = '';
if ($sTabla2357) {
$sEstilo = '';
if ($_REQUEST['ficha'] == 7) {
$sEstilo = '';
}
}
?>
<div class="salto1px"></div>
<div class="GrupoCampos" id="div_ficha7" <?php echo $sEstilo; ?>>
<?php
if ($bconexpande) {
?>
<div class="ir_derecha" style="width:63px;">
<label class="Label30">
<input id="btexpande107" name="btexpande107" type="button" value="Mostrar" class="btMiniExpandir" onclick="expandepanel(107,'block',0);" title="<?php echo $ETI['bt_mostrar']; ?>" style="display:<?php if ($_REQUEST['boculta107'] == 0) {
echo 'block';
} else {
echo 'none';
} ?>;" />
</label>
<label class="Label30">
<input id="btrecoge107" name="btrecoge107" type="button" value="Ocultar" class="btMiniRecoger" onclick="expandepanel(107,'none',1);" title="<?php echo $ETI['bt_ocultar']; ?>" style="display:<?php if ($_REQUEST['boculta107'] == 0) {
echo 'none';
} else {
echo 'block';
} ?>;" />
</label>
</div>
<?php
}
?>
<label class="TituloGrupo">
<?php
echo $ETI['cara57asplab'];
?>
</label>
<div class="salto1px"></div>
<div id="div_p107" style="display:<?php if ($_REQUEST['boculta107'] == 0) {
echo 'none';
} else {
echo 'block';
} ?>;">
<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo 'Grafico n';
?>
</label>
<div class="salto1px"></div>
</div>
<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo 'Grafico n';
?>
</label>
<div class="salto1px"></div>
<div id="div_cara01idconfirmadisc_llaves">
</div>
</div>
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
</div>
<?php
}
if ($bGrupo8) {
$sEstilo = '';
if ($sTabla2357) {
$sEstilo = '';
if ($_REQUEST['ficha'] == 8) {
$sEstilo = '';
}
}
?>
<div class="salto1px"></div>
<div class="GrupoCampos" id="div_ficha8" <?php echo $sEstilo; ?>>
<?php
if ($bconexpande) {
?>
<div class="ir_derecha" style="width:63px;">
<label class="Label30">
<input id="btexpande108" name="btexpande108" type="button" value="Mostrar" class="btMiniExpandir" onclick="expandepanel(108,'block',0);" title="<?php echo $ETI['bt_mostrar']; ?>" style="display:<?php if ($_REQUEST['boculta108'] == 0) {
echo 'block';
} else {
echo 'none';
} ?>;" />
</label>
<label class="Label30">
<input id="btrecoge108" name="btrecoge108" type="button" value="Ocultar" class="btMiniRecoger" onclick="expandepanel(108,'none',1);" title="<?php echo $ETI['bt_ocultar']; ?>" style="display:<?php if ($_REQUEST['boculta108'] == 0) {
echo 'none';
} else {
echo 'block';
} ?>;" />
</label>
</div>
<?php
}
?>
<label class="TituloGrupo">
<?php
echo $ETI['cara57aspfam'];
?>
</label>
<div class="salto1px"></div>
<div id="div_p108" style="display:<?php if ($_REQUEST['boculta108'] == 0) {
echo 'none';
} else {
echo 'block';
} ?>;">
<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo 'Grafico n';
?>
</label>
<div class="salto1px"></div>
</div>
<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo 'Grafico n';
?>
</label>
<div class="salto1px"></div>
<div id="div_cara01idconfirmadisc_llaves">
</div>
</div>
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
</div>
<?php
}
if ($bGrupo9) {
$sEstilo = '';
if ($sTabla2357) {
$sEstilo = '';
if ($_REQUEST['ficha'] == 9) {
$sEstilo = '';
}
}
?>
<div class="salto1px"></div>
<div class="GrupoCampos" id="div_ficha9" <?php echo $sEstilo; ?>>
<?php
if ($bconexpande) {
?>
<div class="ir_derecha" style="width:63px;">
<label class="Label30">
<input id="btexpande109" name="btexpande109" type="button" value="Mostrar" class="btMiniExpandir" onclick="expandepanel(109,'block',0);" title="<?php echo $ETI['bt_mostrar']; ?>" style="display:<?php if ($_REQUEST['boculta109'] == 0) {
echo 'block';
} else {
echo 'none';
} ?>;" />
</label>
<label class="Label30">
<input id="btrecoge109" name="btrecoge109" type="button" value="Ocultar" class="btMiniRecoger" onclick="expandepanel(109,'none',1);" title="<?php echo $ETI['bt_ocultar']; ?>" style="display:<?php if ($_REQUEST['boculta109'] == 0) {
echo 'none';
} else {
echo 'block';
} ?>;" />
</label>
</div>
<?php
}
?>
<label class="TituloGrupo">
<?php
echo $ETI['cara57aspbiu'];
?>
</label>
<div class="salto1px"></div>
<div id="div_p109" style="display:<?php if ($_REQUEST['boculta109'] == 0) {
echo 'none';
} else {
echo 'block';
} ?>;">
<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo 'Grafico n';
?>
</label>
<div class="salto1px"></div>
</div>
<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo 'Grafico n';
?>
</label>
<div class="salto1px"></div>
<div id="div_cara01idconfirmadisc_llaves">
</div>
</div>
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
</div>
<?php
}
if ($bGrupo10) {
$sEstilo = '';
if ($sTabla2357) {
$sEstilo = '';
if ($_REQUEST['ficha'] == 10) {
$sEstilo = '';
}
}
?>
<div class="salto1px"></div>
<div class="GrupoCampos" id="div_ficha10" <?php echo $sEstilo; ?>>
<?php
if ($bconexpande) {
?>
<div class="ir_derecha" style="width:63px;">
<label class="Label30">
<input id="btexpande110" name="btexpande110" type="button" value="Mostrar" class="btMiniExpandir" onclick="expandepanel(110,'block',0);" title="<?php echo $ETI['bt_mostrar']; ?>" style="display:<?php if ($_REQUEST['boculta110'] == 0) {
echo 'block';
} else {
echo 'none';
} ?>;" />
</label>
<label class="Label30">
<input id="btrecoge110" name="btrecoge110" type="button" value="Ocultar" class="btMiniRecoger" onclick="expandepanel(110,'none',1);" title="<?php echo $ETI['bt_ocultar']; ?>" style="display:<?php if ($_REQUEST['boculta110'] == 0) {
echo 'none';
} else {
echo 'block';
} ?>;" />
</label>
</div>
<?php
}
?>
<label class="TituloGrupo">
<?php
echo $ETI['cara57respsico'];
?>
</label>
<div class="salto1px"></div>
<div id="div_p110" style="display:<?php if ($_REQUEST['boculta110'] == 0) {
echo 'none';
} else {
echo 'block';
} ?>;">
<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo 'Grafico n';
?>
</label>
<div class="salto1px"></div>
</div>
<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo 'Grafico n';
?>
</label>
<div class="salto1px"></div>
<div id="div_cara01idconfirmadisc_llaves">
</div>
</div>
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
</div>
<?php
}
if ($bGrupo11) {
$sEstilo = '';
if ($sTabla2357) {
$sEstilo = '';
if ($_REQUEST['ficha'] == 11) {
$sEstilo = '';
}
}
?>
<div class="salto1px"></div>
<div class="GrupoCampos" id="div_ficha11" <?php echo $sEstilo; ?>>
<?php
if ($bconexpande) {
?>
<div class="ir_derecha" style="width:63px;">
<label class="Label30">
<input id="btexpande111" name="btexpande111" type="button" value="Mostrar" class="btMiniExpandir" onclick="expandepanel(111,'block',0);" title="<?php echo $ETI['bt_mostrar']; ?>" style="display:<?php if ($_REQUEST['boculta111'] == 0) {
echo 'block';
} else {
echo 'none';
} ?>;" />
</label>
<label class="Label30">
<input id="btrecoge111" name="btrecoge111" type="button" value="Ocultar" class="btMiniRecoger" onclick="expandepanel(111,'none',1);" title="<?php echo $ETI['bt_ocultar']; ?>" style="display:<?php if ($_REQUEST['boculta111'] == 0) {
echo 'none';
} else {
echo 'block';
} ?>;" />
</label>
</div>
<?php
}
?>
<label class="TituloGrupo">
<?php
echo $ETI['cara57rescomp'];
?>
</label>
<div class="salto1px"></div>
<div id="div_p111" style="display:<?php if ($_REQUEST['boculta111'] == 0) {
echo 'none';
} else {
echo 'block';
} ?>;">
<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo 'Grafico n';
?>
</label>
<div class="salto1px"></div>
</div>
<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo 'Grafico n';
?>
</label>
<div class="salto1px"></div>
<div id="div_cara01idconfirmadisc_llaves">
</div>
</div>
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
</div>
<?php
}
?>
</div>
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
<input id="titulo_2357" name="titulo_2357" type="hidden" value="<?php echo $ETI['titulo_2357']; ?>" />
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


<div id="div_sector98" style="display:none">
<div class="titulos">
<div class="titulosD">
<input id="cmdAyuda98" name="cmdAyuda98" type="button" class="btSupAyuda" onclick="muestraayuda(<?php echo $iCodModulo; ?>);" title="<?php echo $ETI['bt_ayuda']; ?>" value="<?php echo $ETI['bt_ayuda']; ?>" />
</div>
<div class="titulosI">
<?php
echo '<h2>' . $ETI['titulo_2357'] . '</h2>';
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
if ((int)$_REQUEST['cara57peraca'] != 0){
	echo 'setTimeout(pintagrafica(), 2);
	';	
?>
function pintagrafica(){
	pintarGraficosf2357(<?php echo json_encode($aDetalle); ?>);
	<?php echo $sScriptMapa; ?>
}
<?php
}
?>
</script>
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>js/chosen.css" type="text/css" />
<script language="javascript" src="<?php echo $APP->rutacomun; ?>js/chosen.jquery.js"></script>
<script language="javascript">
$().ready(function() {
$("#cara57peraca").chosen();
});
</script>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>unad_todas.js?ver=8"></script>
<?php
forma_piedepagina();
?>