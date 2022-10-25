<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2018 - 2022 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- © Cristhiam Dario Silva Chavez - UNAD - 2019 ---
--- cristhiam.silva@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.22.0 miércoles, 27 de junio de 2018
--- Modelo Versión 2.22.7 martes, 5 de marzo de 2019
--- Modelo Versión 2.22.8 jueves, 28 de marzo de 2019
--- Modelo Versión 2.25.0 sábado, 20 de junio de 2020
--- Modelo Versión 2.25.7 jueves, 1 de octubre de 2020
--- Modelo Versión 2.25.10c miércoles, 10 de marzo de 2021
--- Modelo Versión 2.27.0 viernes, 8 de octubre de 2021
--- Modelo Versión 2.28.2 miércoles, 1 de junio de 2022

*/

/** Archivo coreestudiante.php.
 * Modulo 2202 core01estprograma.
 * @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
 * @param debug=1 (Opcional), bandera para indicar si se generan datos de depuración
 * @date sábado, 20 de junio de 2020
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
require $APP->rutacomun . 'libcore.php';
require $APP->rutacomun . 'libc2.php';
require $APP->rutacomun . 'libpei.php';
if (($bPeticionXAJAX) && ($_SESSION['unad_id_tercero'] == 0)) {
	// viene por xajax.
	$xajax = new xajax();
	$xajax->configure('javascript URI', $APP->rutacomun . 'xajax/');
	$xajax->register(XAJAX_FUNCTION, 'sesion_abandona_V2');
	$xajax->processRequest();
	die();
}
$grupo_id = 1; //Necesita ajustarlo...
$iCodModulo = 2202;
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
$mensajes_2202 = $APP->rutacomun . 'lg/lg_2202_' . $_SESSION['unad_idioma'] . '.php';
if (!file_exists($mensajes_2202)) {
	$mensajes_2202 = $APP->rutacomun . 'lg/lg_2202_es.php';
}
require $mensajes_todas;
require $mensajes_2202;
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
	forma_cabeceraV3($xajax, $ETI['titulo_2202']);
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
		header('Location:noticia.php?ret=coreestudiante.php');
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
$idEntidad = Traer_Entidad();
$mensajes_2203 = $APP->rutacomun . 'lg/lg_2203_' . $_SESSION['unad_idioma'] . '.php';
if (!file_exists($mensajes_2203)) {
	$mensajes_2203 = $APP->rutacomun . 'lg/lg_2203_es.php';
}
$mensajes_2216 = $APP->rutacomun . 'lg/lg_2216_' . $_SESSION['unad_idioma'] . '.php';
if (!file_exists($mensajes_2216)) {
	$mensajes_2216 = $APP->rutacomun . 'lg/lg_2216_es.php';
}
$mensajes_2222 = 'lg/lg_2222_' . $_SESSION['unad_idioma'] . '.php';
if (!file_exists($mensajes_2222)) {
	$mensajes_2222 = 'lg/lg_2222_es.php';
}
$mensajes_2224 = 'lg/lg_2224_' . $_SESSION['unad_idioma'] . '.php';
if (!file_exists($mensajes_2224)) {
	$mensajes_2224 = 'lg/lg_2224_es.php';
}
$mensajes_2271 = 'lg/lg_2271_' . $_SESSION['unad_idioma'] . '.php';
if (!file_exists($mensajes_2271)) {
	$mensajes_2271 = 'lg/lg_2271_es.php';
}
$mensajes_12201 = 'lg/lg_12201_' . $_SESSION['unad_idioma'] . '.php';
if (!file_exists($mensajes_12201)) {
	$mensajes_12201 = 'lg/lg_12201_es.php';
}
$mensajes_3041 = $APP->rutacomun . 'lg/lg_3041_' . $_SESSION['unad_idioma'] . '.php';
if (!file_exists($mensajes_3041)) {
	$mensajes_3041 = $APP->rutacomun . 'lg/lg_3041_es.php';
}
require $mensajes_2203;
require $mensajes_2216;
require $mensajes_2222;
require $mensajes_2224;
require $mensajes_2271;
require $mensajes_12201;
// -- Si esta cargando la pagina por primer vez se revisa si requiere auditar y se manda a hacer un limpiar (paso -1)
if (isset($_REQUEST['paso']) == 0) {
	$_REQUEST['paso'] = -1;
	if ($audita[1]) {
		seg_auditaingreso($iCodModulo, $_SESSION['unad_id_tercero'], $objDB);
	}
}
// -- 2202 core01estprograma
require 'lib2202.php';
require $APP->rutacomun . 'lib2202comun.php';
// -- 2203 Plan de estudios
require $APP->rutacomun . 'lib2203.php';
// -- 2216 Actas de matricula
require $APP->rutacomun . 'lib2216consulta.php';
// -- 2222 Cambios de estado
require 'lib2222.php';
// -- 2224 Cambios de actores
require 'lib2224.php';
// -- 2271 Homologaciones externas
require 'lib2271est.php';
// -- 12201 Anotaciones
require 'lib12201.php';

$xajax = new xajax();
$xajax->configure('javascript URI', $APP->rutacomun . 'xajax/');
$xajax->register(XAJAX_FUNCTION, 'unad11_Mostrar_v2');
$xajax->register(XAJAX_FUNCTION, 'unad11_TraerXid');
$xajax->register(XAJAX_FUNCTION, 'formatear_moneda');
$xajax->register(XAJAX_FUNCTION, 'f2202_Combocore01idprograma');
$xajax->register(XAJAX_FUNCTION, 'f2202_Combocore01idplandeestudios');
$xajax->register(XAJAX_FUNCTION, 'f2202_Combocore011idcead');
$xajax->register(XAJAX_FUNCTION, 'sesion_abandona_V2');
$xajax->register(XAJAX_FUNCTION, 'sesion_mantenerV4');
$xajax->register(XAJAX_FUNCTION, 'f2202_HtmlTabla');
$xajax->register(XAJAX_FUNCTION, 'f2202_ExisteDato');
$xajax->register(XAJAX_FUNCTION, 'f2202_Busquedas');
$xajax->register(XAJAX_FUNCTION, 'f2202_HtmlBusqueda');
$xajax->register(XAJAX_FUNCTION, 'f2202_Combobprograma');
$xajax->register(XAJAX_FUNCTION, 'f2202_Combobversion');
$xajax->register(XAJAX_FUNCTION, 'f2202_Combobcead');
$xajax->register(XAJAX_FUNCTION, 'f2202_Combobtipohomol');
$xajax->register(XAJAX_FUNCTION, 'f2203_HtmlTabla');
$xajax->register(XAJAX_FUNCTION, 'f2216_HtmlTablaConsulta');
$xajax->register(XAJAX_FUNCTION, 'f2222_HtmlTabla');
$xajax->register(XAJAX_FUNCTION, 'f2224_HtmlTabla');
$xajax->register(XAJAX_FUNCTION, 'f2271_HtmlTabla');
$xajax->register(XAJAX_FUNCTION, 'elimina_archivo_corf01idarchivoanexo');
$xajax->register(XAJAX_FUNCTION, 'f12201_Guardar');
$xajax->register(XAJAX_FUNCTION, 'f12201_Traer');
$xajax->register(XAJAX_FUNCTION, 'f12201_Eliminar');
$xajax->register(XAJAX_FUNCTION, 'f12201_HtmlTabla');
$xajax->register(XAJAX_FUNCTION, 'f12201_PintarLlaves');
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
if (isset($_REQUEST['paginaf2202']) == 0) {
	$_REQUEST['paginaf2202'] = 1;
}
if (isset($_REQUEST['lppf2202']) == 0) {
	$_REQUEST['lppf2202'] = 20;
}
if (isset($_REQUEST['boculta2202']) == 0) {
	$_REQUEST['boculta2202'] = 0;
}
if (isset($_REQUEST['paginaf2203']) == 0) {
	$_REQUEST['paginaf2203'] = 1;
}
if (isset($_REQUEST['lppf2203']) == 0) {
	$_REQUEST['lppf2203'] = 200;
}
if (isset($_REQUEST['boculta2203']) == 0) {
	$_REQUEST['boculta2203'] = 0;
}
if (isset($_REQUEST['paginaf2216']) == 0) {
	$_REQUEST['paginaf2216'] = 1;
}
if (isset($_REQUEST['lppf2216']) == 0) {
	$_REQUEST['lppf2216'] = 20;
}
if (isset($_REQUEST['paginaf2222']) == 0) {
	$_REQUEST['paginaf2222'] = 1;
}
if (isset($_REQUEST['lppf2222']) == 0) {
	$_REQUEST['lppf2222'] = 20;
}
if (isset($_REQUEST['boculta2222']) == 0) {
	$_REQUEST['boculta2222'] = 0;
}
if (isset($_REQUEST['paginaf2224']) == 0) {
	$_REQUEST['paginaf2224'] = 1;
}
if (isset($_REQUEST['lppf2224']) == 0) {
	$_REQUEST['lppf2224'] = 20;
}
if (isset($_REQUEST['boculta2224']) == 0) {
	$_REQUEST['boculta2224'] = 0;
}
if (isset($_REQUEST['paginaf2271']) == 0) {
	$_REQUEST['paginaf2271'] = 1;
}
if (isset($_REQUEST['lppf2271']) == 0) {
	$_REQUEST['lppf2271'] = 20;
}
if (isset($_REQUEST['boculta2271']) == 0) {
	$_REQUEST['boculta2271'] = 0;
}
if (isset($_REQUEST['paginaf12201']) == 0) {
	$_REQUEST['paginaf12201'] = 1;
}
if (isset($_REQUEST['lppf12201']) == 0) {
	$_REQUEST['lppf12201'] = 20;
}
if (isset($_REQUEST['boculta12201']) == 0) {
	$_REQUEST['boculta12201'] = 1;
}
// -- Inicializar variables de datos.
if (isset($_REQUEST['core01idtercero']) == 0) {
	$_REQUEST['core01idtercero'] = 0;
	//$_REQUEST['core01idtercero'] = $_SESSION['unad_id_tercero'];
}
if (isset($_REQUEST['core01idtercero_td']) == 0) {
	$_REQUEST['core01idtercero_td'] = $APP->tipo_doc;
}
if (isset($_REQUEST['core01idtercero_doc']) == 0) {
	$_REQUEST['core01idtercero_doc'] = '';
}
if (isset($_REQUEST['core01idprograma']) == 0) {
	$_REQUEST['core01idprograma'] = '';
}
if (isset($_REQUEST['core01idplandeestudios']) == 0) {
	$_REQUEST['core01idplandeestudios'] = '';
}
if (isset($_REQUEST['core01id']) == 0) {
	$_REQUEST['core01id'] = '';
}
if (isset($_REQUEST['core01idescuela']) == 0) {
	$_REQUEST['core01idescuela'] = '';
}
if (isset($_REQUEST['core01idzona']) == 0) {
	$_REQUEST['core01idzona'] = '';
}
if (isset($_REQUEST['core011idcead']) == 0) {
	$_REQUEST['core011idcead'] = '';
}
if (isset($_REQUEST['core01fechainicio']) == 0) {
	$_REQUEST['core01fechainicio'] = '';
	//$_REQUEST['core01fechainicio'] = $iHoy;
}
if (isset($_REQUEST['core01peracainicial']) == 0) {
	$_REQUEST['core01peracainicial'] = '';
}
if (isset($_REQUEST['core01fechaultmatricula']) == 0) {
	$_REQUEST['core01fechaultmatricula'] = '';
}
if (isset($_REQUEST['core01numcredbasicos']) == 0) {
	$_REQUEST['core01numcredbasicos'] = '';
}
if (isset($_REQUEST['core01numcredespecificos']) == 0) {
	$_REQUEST['core01numcredespecificos'] = '';
}
if (isset($_REQUEST['core01numcredelecgenerales']) == 0) {
	$_REQUEST['core01numcredelecgenerales'] = '';
}
if (isset($_REQUEST['core01numcredelecescuela']) == 0) {
	$_REQUEST['core01numcredelecescuela'] = '';
}
if (isset($_REQUEST['core01numcredelecprograma']) == 0) {
	$_REQUEST['core01numcredelecprograma'] = '';
}
if (isset($_REQUEST['core01numcredeleccomplem']) == 0) {
	$_REQUEST['core01numcredeleccomplem'] = '';
}
if (isset($_REQUEST['core01numcredelectivos']) == 0) {
	$_REQUEST['core01numcredelectivos'] = '';
}
if (isset($_REQUEST['core01idestado']) == 0) {
	$_REQUEST['core01idestado'] = -2;
}
if (isset($_REQUEST['core01numcredbasicosaprob']) == 0) {
	$_REQUEST['core01numcredbasicosaprob'] = 0;
}
if (isset($_REQUEST['core01numcredespecificosaprob']) == 0) {
	$_REQUEST['core01numcredespecificosaprob'] = 0;
}
if (isset($_REQUEST['core01numcredelecgeneralesaprob']) == 0) {
	$_REQUEST['core01numcredelecgeneralesaprob'] = 0;
}
if (isset($_REQUEST['core01numcredelecescuelaaprob']) == 0) {
	$_REQUEST['core01numcredelecescuelaaprob'] = 0;
}
if (isset($_REQUEST['core01numcredelecprogramaaaprob']) == 0) {
	$_REQUEST['core01numcredelecprogramaaaprob'] = 0;
}
if (isset($_REQUEST['core01numcredeleccomplemaprob']) == 0) {
	$_REQUEST['core01numcredeleccomplemaprob'] = 0;
}
if (isset($_REQUEST['core01numcredelectivosaprob']) == 0) {
	$_REQUEST['core01numcredelectivosaprob'] = 0;
}
if (isset($_REQUEST['core01notaminima']) == 0) {
	$_REQUEST['core01notaminima'] = 3;
}
if (isset($_REQUEST['core01notamaxima']) == 0) {
	$_REQUEST['core01notamaxima'] = 5;
}
if (isset($_REQUEST['core01fechafinaliza']) == 0) {
	$_REQUEST['core01fechafinaliza'] = '';
	//$_REQUEST['core01fechafinaliza'] = $iHoy;
}
if (isset($_REQUEST['core01peracafinal']) == 0) {
	$_REQUEST['core01peracafinal'] = 0;
}
if (isset($_REQUEST['core01avanceplanest']) == 0) {
	$_REQUEST['core01avanceplanest'] = 0;
}
if (isset($_REQUEST['core01idrevision']) == 0) {
	$_REQUEST['core01idrevision'] = 0;
	//$_REQUEST['core01idrevision'] = $_SESSION['unad_id_tercero'];
}
if (isset($_REQUEST['core01idrevision_td']) == 0) {
	$_REQUEST['core01idrevision_td'] = $APP->tipo_doc;
}
if (isset($_REQUEST['core01idrevision_doc']) == 0) {
	$_REQUEST['core01idrevision_doc'] = '';
}
if (isset($_REQUEST['core01fecharevision']) == 0) {
	$_REQUEST['core01fecharevision'] = '';
	//$_REQUEST['core01fecharevision'] = $iHoy;
}
if (isset($_REQUEST['core01gradoestado']) == 0) {
	$_REQUEST['core01gradoestado'] = 0;
}
if (isset($_REQUEST['core01gradoidopcion']) == 0) {
	$_REQUEST['core01gradoidopcion'] = 0;
}
if (isset($_REQUEST['core01gradofecha']) == 0) {
	$_REQUEST['core01gradofecha'] = '';
	//$_REQUEST['core01gradofecha'] = $iHoy;
}
if (isset($_REQUEST['core01contmatriculas']) == 0) {
	$_REQUEST['core01contmatriculas'] = 0;
}
if (isset($_REQUEST['core01contciclo1']) == 0) {
	$_REQUEST['core01contciclo1'] = '';
}
if (isset($_REQUEST['core01contciclo2']) == 0) {
	$_REQUEST['core01contciclo2'] = '';
}
if (isset($_REQUEST['core01contciclo3']) == 0) {
	$_REQUEST['core01contciclo3'] = '';
}
if (isset($_REQUEST['core01contciclo4']) == 0) {
	$_REQUEST['core01contciclo4'] = '';
}
if (isset($_REQUEST['core01contciclo5']) == 0) {
	$_REQUEST['core01contciclo5'] = '';
}
if (isset($_REQUEST['core01contciclo6']) == 0) {
	$_REQUEST['core01contciclo6'] = '';
}
if (isset($_REQUEST['core01contciclo7']) == 0) {
	$_REQUEST['core01contciclo7'] = '';
}
if (isset($_REQUEST['core01contciclo8']) == 0) {
	$_REQUEST['core01contciclo8'] = '';
}
if (isset($_REQUEST['core01contciclo9']) == 0) {
	$_REQUEST['core01contciclo9'] = '';
}
if (isset($_REQUEST['core01contciclo10']) == 0) {
	$_REQUEST['core01contciclo10'] = '';
}
if (isset($_REQUEST['core01contciclo11']) == 0) {
	$_REQUEST['core01contciclo11'] = '';
}
if (isset($_REQUEST['core01contciclo12']) == 0) {
	$_REQUEST['core01contciclo12'] = '';
}
if (isset($_REQUEST['core01contciclo13']) == 0) {
	$_REQUEST['core01contciclo13'] = '';
}
if (isset($_REQUEST['core01contciclo14']) == 0) {
	$_REQUEST['core01contciclo14'] = '';
}
if (isset($_REQUEST['core01contciclo15']) == 0) {
	$_REQUEST['core01contciclo15'] = '';
}
if (isset($_REQUEST['core01contciclo16']) == 0) {
	$_REQUEST['core01contciclo16'] = '';
}
if (isset($_REQUEST['core01contciclo17']) == 0) {
	$_REQUEST['core01contciclo17'] = '';
}
if (isset($_REQUEST['core01contciclo18']) == 0) {
	$_REQUEST['core01contciclo18'] = '';
}
if (isset($_REQUEST['core01contciclo19']) == 0) {
	$_REQUEST['core01contciclo19'] = '';
}
if (isset($_REQUEST['core01contciclo20']) == 0) {
	$_REQUEST['core01contciclo20'] = '';
}
if (isset($_REQUEST['core01contestado']) == 0) {
	$_REQUEST['core01contestado'] = 0;
}
if (isset($_REQUEST['core01estadopractica']) == 0) {
	$_REQUEST['core01estadopractica'] = 0;
}
if (isset($_REQUEST['core01idtipopractica']) == 0) {
	$_REQUEST['core01idtipopractica'] = 0;
}
if (isset($_REQUEST['core01idlineaprof']) == 0) {
	$_REQUEST['core01idlineaprof'] = 0;
}
if (isset($_REQUEST['core01semestrerelativo']) == 0) {
	$_REQUEST['core01semestrerelativo'] = '';
}
if (isset($_REQUEST['core01ciclobase']) == 0) {
	$_REQUEST['core01ciclobase'] = '';
}
if (isset($_REQUEST['core01ciclobase_prev']) == 0) {
	$_REQUEST['core01ciclobase_prev'] = '';
}
if (isset($_REQUEST['core01idlineaprof2']) == 0) {
	$_REQUEST['core01idlineaprof2'] = 0;
}
if (isset($_REQUEST['core01idpruebaestado']) == 0) {
	$_REQUEST['core01idpruebaestado'] = 0;
}
if (isset($_REQUEST['corf14idcurso']) == 0) {
	$_REQUEST['corf14idcurso'] = '';
}
if (isset($_REQUEST['core01sem_proyectados']) == 0) {
	$_REQUEST['core01sem_proyectados'] = 0;
}
if (isset($_REQUEST['core01sem_total']) == 0) {
	$_REQUEST['core01sem_total'] = 0;
}
if (isset($_REQUEST['core01factordeserta']) == 0) {
	$_REQUEST['core01factordeserta'] = 0;
}
$_REQUEST['core01idtercero'] = numeros_validar($_REQUEST['core01idtercero']);
$_REQUEST['core01idtercero_td'] = cadena_Validar($_REQUEST['core01idtercero_td']);
$_REQUEST['core01idtercero_doc'] = cadena_Validar($_REQUEST['core01idtercero_doc']);
$_REQUEST['core01idprograma'] = numeros_validar($_REQUEST['core01idprograma']);
$_REQUEST['core01idplandeestudios'] = numeros_validar($_REQUEST['core01idplandeestudios']);
$_REQUEST['core01id'] = numeros_validar($_REQUEST['core01id']);
if ((int)$_REQUEST['paso'] > 0) {
	//Cambios de estado 
	if (isset($_REQUEST['core22anotacion']) == 0) {
		$_REQUEST['core22anotacion'] = '';
	}
	//Cambios de actores
	//Anotaciones
	if (isset($_REQUEST['corf01consec']) == 0) {
		$_REQUEST['corf01consec'] = '';
	}
	if (isset($_REQUEST['corf01id']) == 0) {
		$_REQUEST['corf01id'] = '';
	}
	if (isset($_REQUEST['corf01visible']) == 0) {
		$_REQUEST['corf01visible'] = '';
	}
	if (isset($_REQUEST['corf01anotacion']) == 0) {
		$_REQUEST['corf01anotacion'] = '';
	}
	if (isset($_REQUEST['corf01idorigenanexo']) == 0) {
		$_REQUEST['corf01idorigenanexo'] = 0;
	}
	if (isset($_REQUEST['corf01idarchivoanexo']) == 0) {
		$_REQUEST['corf01idarchivoanexo'] = 0;
	}
	if (isset($_REQUEST['corf01usuario']) == 0) {
		$_REQUEST['corf01usuario'] = 0;
	}
	if (isset($_REQUEST['corf01usuario_td']) == 0) {
		$_REQUEST['corf01usuario_td'] = $APP->tipo_doc;
	}
	if (isset($_REQUEST['corf01usuario_doc']) == 0) {
		$_REQUEST['corf01usuario_doc'] = '';
	}
	if (isset($_REQUEST['corf01fecha']) == 0) {
		$_REQUEST['corf01fecha'] = '';
		//$_REQUEST['corf01fecha'] = $iHoy;
	}
	if (isset($_REQUEST['corf01hora']) == 0) {
		$_REQUEST['corf01hora'] = '';
	}
	if (isset($_REQUEST['corf01minuto']) == 0) {
		$_REQUEST['corf01minuto'] = '';
	}
}
// Espacio para inicializar otras variables
$bTraerEntorno = false;
if (isset($_REQUEST['csv_separa']) == 0) {
	$_REQUEST['csv_separa'] = ';';
}
if (isset($_REQUEST['bdoc']) == 0) {
	$_REQUEST['bdoc'] = '';
}
if (isset($_REQUEST['bnombre']) == 0) {
	$_REQUEST['bnombre'] = '';
}
if (isset($_REQUEST['blistar']) == 0) {
	$_REQUEST['blistar'] = 2;
}
if (isset($_REQUEST['bzona']) == 0) {
	$_REQUEST['bzona'] = '';
}
if (isset($_REQUEST['bcead']) == 0) {
	$_REQUEST['bcead'] = '';
}
if (isset($_REQUEST['bescuela']) == 0) {
	$_REQUEST['bescuela'] = '';
	$bTraerEntorno = true;
}
if (isset($_REQUEST['bprograma']) == 0) {
	$_REQUEST['bprograma'] = '';
}
if (isset($_REQUEST['bconvenio']) == 0) {
	$_REQUEST['bconvenio'] = '';
}
if (isset($_REQUEST['bperiodo']) == 0) {
	$_REQUEST['bperiodo'] = '';
}
if (isset($_REQUEST['bversion']) == 0) {
	$_REQUEST['bversion'] = '';
}
if (isset($_REQUEST['bestado']) == 0) {
	$_REQUEST['bestado'] = '';
}
if (isset($_REQUEST['bavmin']) == 0) {
	$_REQUEST['bavmin'] = '';
}
if (isset($_REQUEST['bavmax']) == 0) {
	$_REQUEST['bavmax'] = '';
}
if (isset($_REQUEST['bcontinuidad']) == 0) {
	$_REQUEST['bcontinuidad'] = '';
}
if (isset($_REQUEST['bcohorte']) == 0) {
	$_REQUEST['bcohorte'] = '';
}
if (isset($_REQUEST['bmatricula']) == 0) {
	$_REQUEST['bmatricula'] = '';
}
if (isset($_REQUEST['bsituacion']) == 0) {
	$_REQUEST['bsituacion'] = '';
}
if (isset($_REQUEST['btipohomol']) == 0) {
	$_REQUEST['btipohomol'] = '';
}
if (isset($_REQUEST['bnivelforma']) == 0) {
	$_REQUEST['bnivelforma'] = '';
}
if (isset($_REQUEST['bopcgrado']) == 0) {
	$_REQUEST['bopcgrado'] = '';
}
if (isset($_REQUEST['bpostgrado']) == 0) {
	$_REQUEST['bpostgrado'] = '';
}
if (isset($_REQUEST['bfactordeserta']) == 0) {
	$_REQUEST['bfactordeserta'] = '';
}
if ((int)$_REQUEST['paso'] > 0) {
	if (isset($_REQUEST['bcod2203']) == 0) {
		$_REQUEST['bcod2203'] = '';
	}
	if (isset($_REQUEST['bnombre2203']) == 0) {
		$_REQUEST['bnombre2203'] = '';
	}
	if (isset($_REQUEST['blistar2203']) == 0) {
		$_REQUEST['blistar2203'] = '';
	}
	if (isset($_REQUEST['btipo2203']) == 0) {
		$_REQUEST['btipo2203'] = '';
	}
}
if ($bTraerEntorno) {
	$sSQL = 'SELECT * FROM unad95entorno WHERE unad95id=' . $idTercero . '';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		if ($fila['unad95escuela'] != 0) {
			$_REQUEST['bescuela'] = $fila['unad95escuela'];
			$_REQUEST['blistar'] = '';
		}
		if ($fila['unad95programa'] != 0) {
			$_REQUEST['bprograma'] = $fila['unad95programa'];
			$_REQUEST['blistar'] = '';
		}
		if ($fila['unad95zona'] != 0) {
			$_REQUEST['bzona'] = $fila['unad95zona'];
			$_REQUEST['blistar'] = '';
		}
		if ($fila['unad95centro'] != 0) {
			$_REQUEST['bcead'] = $fila['unad95centro'];
			$_REQUEST['blistar'] = '';
		}
	}
}
//GENERAR EL PEI
if ($_REQUEST['paso'] == 21) {
	$_REQUEST['paso'] = 2;
	list($sError, $sDebugP) = f2202_GenerarPEI($_REQUEST['core01idtercero'], $_REQUEST['core01idprograma'], $_REQUEST['core01idplandeestudios'], $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugP;
	if ($sError == '') {
		// En ruta comun libcore.php
		list($sError, $sDebugI) = f2201_ActualizarNotas($_REQUEST['core01id'], $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugI;
		$bActualizarInfoBase = true;
		$sError = $ETI['msg_peiconstruido'];
		$iTipoError = 1;
		$_REQUEST['paso'] = 3;
	}
}
//Agregar curso excepcional al PEI
if ($_REQUEST['paso'] == 29) {
	$_REQUEST['paso'] = 2;
	$bMueveScroll = true;
	if ((int)$_REQUEST['corf14idcurso'] == 0) {
		$sError = 'No se ha definido el curso a agregar.';
	}
	if ($sError == '') {
		list($sError, $sDebugA) = f2202_AddExcepcion($_REQUEST['core01id'], $_REQUEST['corf14idcurso'], $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugA;
	}
	if ($sError == '') {
		$sError = 'Se ha agregado la excepci&oacute;n';
		$iTipoError = 1;
		$_REQUEST['paso'] = 3;
	}
}
//Importar la data historica.
$bActualizarInfoBase = false;
if ($_REQUEST['paso'] == 22) {
	$_REQUEST['paso'] = 2;
	$bTraeRyC = false;
	if ($_REQUEST['core01idtercero_td'] != 'CC') {
		$sError = 'Solo es posible importar usuarios que el tipo de documento este marcado como cedula.';
	}
	if ($sError == '') {
		list($objDBRyC, $sDebugC) = TraerDBRyCV2($bDebug);
		$sDebug = $sDebug . $sDebugC;
		if ($objDBRyC == NULL) {
			$sError = 'No ha sido posible establecer conexi&oacute;n con EDUNAT.';
		} else {
			$bTraeRyC = true;
		}
	}
	if ($sError == '') {
		set_time_limit(0);
		// -- 2216 core16actamatricula
		require $APP->rutacomun . 'lib2216.php';
		// -- 2301 cara01encuesta
		require $APP->rutacomun . 'lib2301.php';
		//No conformidades.
		require $APP->rutacomun . 'libs/cls203.php';

		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' <b>IMPORTANDO HISTORICOS</b><br>';
		}
		list($sError, $sDebugI) = f2201_ImportarHistorico($_REQUEST['core01id'], $objDB, $objDBRyC, $bDebug);
		$sDebug = $sDebug . $sDebugI;
		if ($sError == '') {
			// En ruta comun libcore.php
			list($sError, $sDebugI) = f2201_ActualizarNotas($_REQUEST['core01id'], $objDB, $bDebug);
			$sDebug = $sDebug . $sDebugI;
			$bActualizarInfoBase = true;
		}
	}
	if ($sError == '') {
		$sError = 'Importaci&oacute;n completa.';
		$iTipoError = 1;
		$_REQUEST['paso'] = 3;
	}
	if ($bTraeRyC) {
		if ($objDBRyC != NULL) {
			$objDBRyC->CerrarConexion();
		}
	}
}
//Actualizar notas desde el historico.
if ($_REQUEST['paso'] == 25) {
	$_REQUEST['paso'] = 2;
	if ($sError == '') {
		set_time_limit(0);
		//Julio 25 de 2022 primero traemos las homologaciones.
		list($sErrorH, $sDebugH) = f2201_ImportarHomologaciones($_REQUEST['core01id'], $objDB, NULL, $bDebug);
		$sDebug = $sDebug . $sDebugH;
		// -- 2216 core16actamatricula
		require $APP->rutacomun . 'lib2216.php';
		list($sError, $sDebugI, $_REQUEST['core01idestado']) = f2201_ActualizarNotas($_REQUEST['core01id'], $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugI;
		$bActualizarInfoBase = true;
	}
	if ($sError == '') {
		$sError = 'Importaci&oacute;n completa.';
		$iTipoError = 1;
	}
}
//Verificar si tiene graddo
if ($_REQUEST['paso'] == 28) {
	$_REQUEST['paso'] = 2;
	//Verificamos que el periodo y la fecha inicial esten bien...
	$sSQL='SELECT core16peraca, core16fechamatricula, core16fecharecibido
	FROM core16actamatricula 
	WHERE core16tercero=' . $_REQUEST['core01idtercero'] . ' AND core16idprograma=' . $_REQUEST['core01idprograma'] . '
	ORDER BY core16peraca';
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' Consultando Matricula: ' . $sSQL . ' <br>';
	}
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0) {
		$fila=$objDB->sf($tabla);
		$iPeriodoBase = $fila['core16peraca'];
		if ($iPeriodoBase != $_REQUEST['core01peracainicial']){
			$sAdd = '';
			$_REQUEST['core01peracainicial'] = $iPeriodoBase;
			if ($fila['core16fechamatricula'] != 0) {
				$_REQUEST['core01fechainicio'] = $fila['core16fechamatricula'];
				$sAdd = ', core01fechainicio=' . $fila['core16fechamatricula'] . '';
			}
			$iPeriodoFinal = $fila['core16peraca'];
			while($fila=$objDB->sf($tabla)){
				$iPeriodoFinal = $fila['core16peraca'];
			}
			if ($iPeriodoFinal != $_REQUEST['core01peracafinal']) {
				$_REQUEST['core01peracafinal'] = $iPeriodoFinal;
				$sAdd = $sAdd . ', core01peracafinal=' . $iPeriodoFinal . '';
			}
			$sSQL = 'UPDATE core01estprograma SET core01peracainicial=' . $iPeriodoBase . $sAdd . ' WHERE core01id=' . $_REQUEST['core01id'] . '';
			$result=$objDB->ejecutasql($sSQL);
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . ' Actualizando periodo inicial: ' . $sSQL . ' <br>';
			}
		}
	}
	if ($sError == '') {
		list($sError, $sDebugI) = f2203_VerificarGrado($_REQUEST['core01id'], $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugI;
	}
	if ($sError == '') {
		list($sError, $sDebugC, $iResultado, $iNumMatriculas) = f2201_AnalizarContinuidad($_REQUEST['core01id'], $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugC;
		$sError = 'Se ha verificado la existencia de grado.';
		$iTipoError = 1;
		$_REQUEST['paso'] = 3;
	}
}
//Ajustar un curso comun
if ($_REQUEST['paso'] == 31) {
	$_REQUEST['paso'] = 2;
	$bMueveScroll = true;
	list($idContenedor, $sErrorCont) = f1011_BloqueTercero($_REQUEST['core01idtercero'], $objDB);
	if ($sError == '') {
		$sCambiaEstado = '';
		$sSQL = 'SELECT core03estado 
		FROM core03plandeestudios_' . $idContenedor . ' 
		WHERE core03idestprograma=' . $_REQUEST['core01id'] . ' AND core03idcurso=' . $_REQUEST['id03'] . '';
		$tabla01 = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla01) > 0) {
			$fila01 = $objDB->sf($tabla01);
			if ($fila01['core03estado'] == 9) {
				$sCambiaEstado = ', core03estado=0';
			}
		}
		$sSQL = 'UPDATE core03plandeestudios_' . $idContenedor . ' SET core03itipocurso=' . $_REQUEST['id03tipo'] . $sCambiaEstado . ' 
		WHERE core03idestprograma=' . $_REQUEST['core01id'] . ' AND core03idcurso=' . $_REQUEST['id03'] . '';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' Ajustando el curso: ' . $sSQL . '<br>';
		}
		$tabla = $objDB->ejecutasql($sSQL);
		list($core01avanceplanest, $sErrorP, $sDebugP) = f2203_TotalizarPlanV2($_REQUEST['core01id'], $objDB, $bDebug, $idContenedor);
		$sDebug = $sDebug . $sDebugP;
	}
	if ($sError == '') {
		$sError = 'Se ha ajustado el curso en el plan de estudios.';
		$iTipoError = 2;
		$_REQUEST['paso'] = 3;
	}
}
//Encuesta de deserción (enviar el archivo.)
if ($_REQUEST['paso'] == 32) {
	$_REQUEST['paso'] = 2;
	$bMueveScroll = true;
	if ($sError == '') {
		require $APP->rutacomun . 'libaurea.php';
		list($aure73codigo, $sError, $sDebugE, $aure73idtabla, $aure73id) = AUREA_IniciarEncuestaPublica($_REQUEST['core01idtercero'], 2, 2202, 22, $_REQUEST['core01id'], $objDB, $bDebug);
	}
	if ($sError == ''){
		$sSQL = 'SELECT core01desc_cont_encuesta, core01desc_id_encuesta FROM core01estprograma WHERE core01id=' . $_REQUEST['core01id'] . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			if ($fila['core01desc_id_encuesta'] == 0){
				$sSQL = 'UPDATE core01estprograma SET core01desc_cont_encuesta=' . $aure73idtabla . ', core01desc_id_encuesta=' . $aure73id . ' WHERE core01id=' . $_REQUEST['core01id'] . '';
				$result = $objDB->ejecutasql($sSQL);
			}
		}
	}
	if ($sError == '') {
		$sError = 'Se ha enviado la encuesta de deserci&oacute;n al estudiante.';
		$iTipoError = 2;
	}
}
//Si Modifica o Elimina Cargar los campos
if (($_REQUEST['paso'] == 1) || ($_REQUEST['paso'] == 3)) {
	$_REQUEST['core01idtercero_td'] = $APP->tipo_doc;
	$_REQUEST['core01idtercero_doc'] = '';
	$_REQUEST['core01idrevision_td'] = $APP->tipo_doc;
	$_REQUEST['core01idrevision_doc'] = '';
	if ($_REQUEST['paso'] == 1) {
		$sSQLcondi = 'core01idtercero="' . $_REQUEST['core01idtercero'] . '" AND core01idprograma=' . $_REQUEST['core01idprograma'] . '';
	} else {
		$sSQLcondi = 'core01id=' . $_REQUEST['core01id'] . '';
	}
	$sSQL = 'SELECT * FROM core01estprograma WHERE ' . $sSQLcondi;
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$_REQUEST['core01idtercero'] = $fila['core01idtercero'];
		$_REQUEST['core01idprograma'] = $fila['core01idprograma'];
		$_REQUEST['core01idplandeestudios'] = $fila['core01idplandeestudios'];
		$_REQUEST['core01id'] = $fila['core01id'];
		$_REQUEST['core01idescuela'] = $fila['core01idescuela'];
		$_REQUEST['core01idzona'] = $fila['core01idzona'];
		$_REQUEST['core011idcead'] = $fila['core011idcead'];
		$_REQUEST['core01fechainicio'] = $fila['core01fechainicio'];
		$_REQUEST['core01peracainicial'] = $fila['core01peracainicial'];
		$_REQUEST['core01fechaultmatricula'] = $fila['core01fechaultmatricula'];
		$_REQUEST['core01numcredbasicos'] = $fila['core01numcredbasicos'];
		$_REQUEST['core01numcredespecificos'] = $fila['core01numcredespecificos'];
		$_REQUEST['core01numcredelecgenerales'] = $fila['core01numcredelecgenerales'];
		$_REQUEST['core01numcredelecescuela'] = $fila['core01numcredelecescuela'];
		$_REQUEST['core01numcredelecprograma'] = $fila['core01numcredelecprograma'];
		$_REQUEST['core01numcredeleccomplem'] = $fila['core01numcredeleccomplem'];
		$_REQUEST['core01numcredelectivos'] = $fila['core01numcredelectivos'];
		$_REQUEST['core01idestado'] = $fila['core01idestado'];
		$_REQUEST['core01numcredbasicosaprob'] = $fila['core01numcredbasicosaprob'];
		$_REQUEST['core01numcredespecificosaprob'] = $fila['core01numcredespecificosaprob'];
		$_REQUEST['core01numcredelecgeneralesaprob'] = $fila['core01numcredelecgeneralesaprob'];
		$_REQUEST['core01numcredelecescuelaaprob'] = $fila['core01numcredelecescuelaaprob'];
		$_REQUEST['core01numcredelecprogramaaaprob'] = $fila['core01numcredelecprogramaaaprob'];
		$_REQUEST['core01numcredeleccomplemaprob'] = $fila['core01numcredeleccomplemaprob'];
		$_REQUEST['core01numcredelectivosaprob'] = $fila['core01numcredelectivosaprob'];
		$_REQUEST['core01fechafinaliza'] = $fila['core01fechafinaliza'];
		$_REQUEST['core01peracafinal'] = $fila['core01peracafinal'];
		$_REQUEST['core01avanceplanest'] = $fila['core01avanceplanest'];
		$_REQUEST['core01idrevision'] = $fila['core01idrevision'];
		$_REQUEST['core01fecharevision'] = $fila['core01fecharevision'];
		$_REQUEST['core01gradoestado'] = $fila['core01gradoestado'];
		$_REQUEST['core01gradoidopcion'] = $fila['core01gradoidopcion'];
		$_REQUEST['core01gradofecha'] = $fila['core01gradofecha'];
		$_REQUEST['core01contmatriculas'] = $fila['core01contmatriculas'];
		$_REQUEST['core01contciclo1'] = $fila['core01contciclo1'];
		$_REQUEST['core01contciclo2'] = $fila['core01contciclo2'];
		$_REQUEST['core01contciclo3'] = $fila['core01contciclo3'];
		$_REQUEST['core01contciclo4'] = $fila['core01contciclo4'];
		$_REQUEST['core01contciclo5'] = $fila['core01contciclo5'];
		$_REQUEST['core01contciclo6'] = $fila['core01contciclo6'];
		$_REQUEST['core01contciclo7'] = $fila['core01contciclo7'];
		$_REQUEST['core01contciclo8'] = $fila['core01contciclo8'];
		$_REQUEST['core01contciclo9'] = $fila['core01contciclo9'];
		$_REQUEST['core01contciclo10'] = $fila['core01contciclo10'];
		$_REQUEST['core01contciclo11'] = $fila['core01contciclo11'];
		$_REQUEST['core01contciclo12'] = $fila['core01contciclo12'];
		$_REQUEST['core01contciclo13'] = $fila['core01contciclo13'];
		$_REQUEST['core01contciclo14'] = $fila['core01contciclo14'];
		$_REQUEST['core01contciclo15'] = $fila['core01contciclo15'];
		$_REQUEST['core01contciclo16'] = $fila['core01contciclo16'];
		$_REQUEST['core01contciclo17'] = $fila['core01contciclo17'];
		$_REQUEST['core01contciclo18'] = $fila['core01contciclo18'];
		$_REQUEST['core01contciclo19'] = $fila['core01contciclo19'];
		$_REQUEST['core01contciclo20'] = $fila['core01contciclo20'];
		$_REQUEST['core01contestado'] = $fila['core01contestado'];
		$_REQUEST['core01estadopractica'] = $fila['core01estadopractica'];
		$_REQUEST['core01idtipopractica'] = $fila['core01idtipopractica'];
		$_REQUEST['core01idlineaprof'] = $fila['core01idlineaprof'];
		$_REQUEST['core01condicion'] = $fila['core01condicion'];
		$_REQUEST['core01semestrerelativo'] = $fila['core01semestrerelativo'];
		$_REQUEST['core01sem_proyectados'] = $fila['core01sem_proyectados'];
		$_REQUEST['core01sem_total'] = $fila['core01sem_total'];
		$_REQUEST['core01ciclobase'] = $fila['core01ciclobase'];
		$_REQUEST['core01idlineaprof2'] = $fila['core01idlineaprof2'];
		$_REQUEST['core01idpruebaestado'] = $fila['core01idpruebaestado'];
		$_REQUEST['core01factordeserta'] = $fila['core01factordeserta'];
		$bcargo = true;
		$_REQUEST['paso'] = 2;
		$_REQUEST['boculta2202'] = 0;
		$bLimpiaHijos = true;
	} else {
		$_REQUEST['paso'] = 0;
	}
}
$bEjecutaModificaCiclo = false;
//Insertar o modificar un elemento
if (($_REQUEST['paso'] == 10) || ($_REQUEST['paso'] == 12)) {
	if ($_REQUEST['core01ciclobase_prev'] != $_REQUEST['core01ciclobase']) {
		$bEjecutaModificaCiclo = true;
	}
	$bMueveScroll = true;
	list($_REQUEST, $sError, $iTipoError, $sDebugGuardar) = f2202_db_GuardarV2($_REQUEST, $objDB, $bDebug, $idTercero);
	$sDebug = $sDebug . $sDebugGuardar;
	if ($sError == '') {
		$sError = '<b>' . $ETI['msg_itemguardado'] . '</b>';
		$iTipoError = 1;
	} else {
		$bEjecutaModificaCiclo = false;
	}
}
if ($bEjecutaModificaCiclo) {
	//Tenemos que retirar los cursos del ciclo previo.
	list($sErrorA, $sDebugA) = f2201_AjustarCicloPrevio($_REQUEST['core01id'], $objDB, $bDebug);
	$bActualizarInfoBase = true;
}
//Admitir el estudiante
if ($_REQUEST['paso'] == 23) {
	$_REQUEST['paso'] = 2;
	$bMueveScroll = true;
	if ($sError == '') {
		list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 3, $_SESSION['unad_id_tercero'], $objDB);
		if (!$bDevuelve) {
			$sError = $ERR['3'];
		}
	}
	if ($sError == '') {
		list($sError, $sDebugE) = f2222_CambiaEstado($_REQUEST['core01id'], $_REQUEST['core01idestado'], -1, $_SESSION['unad_id_tercero'], '', $objDB, 0, $bDebug);
		$sDebug = $sDebug . $sDebugE;
	}
	if ($sError == '') {
		$_REQUEST['core01idestado'] = -1;
		$sError = '<b>' . $ETI['msg_admitido'] . '</b>';
		$iTipoError = 1;
	}
}
//Rechazar el estudiante
if ($_REQUEST['paso'] == 24) {
	$_REQUEST['paso'] = 2;
	$bMueveScroll = true;
	if ($sError == '') {
		list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 3, $_SESSION['unad_id_tercero'], $objDB);
		if (!$bDevuelve) {
			$sError = $ERR['3'];
		}
	}
	if ($sError == '') {
		list($sError, $sDebugE) = f2222_CambiaEstado($_REQUEST['core01id'], $_REQUEST['core01idestado'], 99, $_SESSION['unad_id_tercero'], '', $objDB, 0, $bDebug);
		$sDebug = $sDebug . $sDebugE;
	}
	if ($sError == '') {
		$_REQUEST['core01idestado'] = 99;
		$sError = '<b>' . $ETI['msg_noadmitido'] . '</b>';
		$iTipoError = 1;
	}
}
if ($_REQUEST['paso'] == 26) {
	$_REQUEST['paso'] = 2;
	$bMueveScroll = true;
	//Antes que nada nos aseguramos de que este bien totalizado
	$sSQL = 'SELECT 1 FROM core01estprograma WHERE core01id=' . $_REQUEST['core01id'] . ' AND core01sem_proyectados=0';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0){
		list($core01avanceplanest, $sErrorP, $sDebugP) = f2203_TotalizarPlanV2($_REQUEST['core01id'], $objDB, $bDebug, 0, true);
		$sDebug = $sDebug . $sDebugP;
	}
	list($sError, $sDebugC, $iResultado, $iNumMatriculas) = f2201_AnalizarContinuidad($_REQUEST['core01id'], $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugC;
	if ($sError == '') {
		$_REQUEST['core01contestado'] = $iResultado;
		$_REQUEST['core01contmatriculas'] = $iNumMatriculas;
		$sSQL = 'SELECT core01sem_proyectados, core01semestrerelativo, core01sem_total 
		FROM core01estprograma 
		WHERE core01id=' . $_REQUEST['core01id'] . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0){
			$fila = $objDB->sf($tabla);
			$_REQUEST['core01sem_proyectados'] = $fila['core01sem_proyectados'];
			$_REQUEST['core01semestrerelativo'] = $fila['core01semestrerelativo'];
			$_REQUEST['core01sem_total'] = $fila['core01sem_total'];
		}
		$sError = '<b>Revisi&oacute;n completa</b>';
		$iTipoError = 1;
	}
}
//Activar el estudiante...
if ($_REQUEST['paso'] == 27) {
	$_REQUEST['paso'] = 2;
	$iResultado = 0;
	$sVer = htmlspecialchars($_REQUEST['core22anotacion']);
	if ($sVer != $_REQUEST['core22anotacion']) {
		$sError = 'Falla al intentar aplicar el cambio de estado';
		$_REQUEST['paso'] = -1;
	}
	if ($sError == '') {
		if ($_REQUEST['core22anotacion'] == '') {
			$sError = 'Se requiere una anotaci&oacute;n para registrar el cambio de estado.';
		}
	}
	if ($sError == '') {
		list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 3, $_SESSION['unad_id_tercero'], $objDB);
		if (!$bDevuelve) {
			$sError = $ERR['3'];
		}
	}
	if ($sError == '') {
		list($sError, $sDebugE) = f2222_CambiaEstado($_REQUEST['core01id'], $_REQUEST['core01idestado'], $iResultado, $_SESSION['unad_id_tercero'], $_REQUEST['core22anotacion'], $objDB, 0, $bDebug);
		$sDebug = $sDebug . $sDebugE;
	}
	if ($sError == '') {
		$_REQUEST['core01idestado'] = $iResultado;
		$sError = '<b>El estudiante ha sido marcado como activo.</b>';
		$iTipoError = 1;
	}
}
//Plan de estudios revisado...
if ($_REQUEST['paso'] == 30) {
	$_REQUEST['paso'] = 2;
	$bMueveScroll = true;
	if ($sError == '') {
		$sSQL = 'UPDATE core01estprograma SET core01idrevision=' . $idTercero . ', core01fecharevision=' . $iHoy . ' WHERE core01id=' . $_REQUEST['core01id'] . '';
		$result = $objDB->ejecutasql($sSQL);
	}
	if ($sError == '') {
		$_REQUEST['core01idrevision'] = $idTercero;
		$_REQUEST['core01fecharevision'] = $iHoy;
		$sError = '<b>El plan de estudios ha sido marcado como revisado</b>';
		$iTipoError = 1;
	}
}
//Eliminar un elemento
if ($_REQUEST['paso'] == 13) {
	$_REQUEST['paso'] = 2;
	if ($sError == '') {
		switch ($_REQUEST['core01idestado']) {
			case 7: //Graduando
			case 10: //Graduado
				$sError = 'El estado el alumno no permite eliminaci&oacute;n.';
				break;
		}
	}
	if ($sError == '') {
		list($sError, $iTipoError, $sDebugElimina) = f2202_db_Eliminar($_REQUEST['core01id'], $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugElimina;
		if ($sError == '') {
			$_REQUEST['paso'] = -1;
			$sError = $ETI['msg_itemeliminado'];
			$iTipoError = 1;
		}
	}
}
if ($bActualizarInfoBase) {
	$sSQL = 'SELECT core01estadopractica FROM core01estprograma WHERE core01id=' . $_REQUEST['core01id'] . '';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$_REQUEST['core01estadopractica'] = $fila['core01estadopractica'];
	}
}
//Retirar un historial de cambio de estado.
//Actualizar los datos de caracterizacion
if ($_REQUEST['paso'] == 52) {
	$_REQUEST['paso'] = -1;
	$iActualizados = 0;
	$sSQL = 'SELECT core01id, core01idtercero, core01idprograma 
	FROM core01estprograma 
	WHERE core01peracainicial>473 AND core01idcaraing=0';
	$tabla = $objDB->ejecutasql($sSQL);
	$iBase = $objDB->nf($tabla);
	while ($fila = $objDB->sf($tabla)) {
		//Buscar cual fue la caracterizacion de entrada y actualizar el registro.
		$cara01id = 0;
		$sSQL = 'SELECT cara01id
		FROM cara01encuesta 
		WHERE cara01idtercero=' . $fila['core01idtercero'] . ' AND cara01idprograma=' . $fila['core01idprograma'] . '
		ORDER BY cara01idperaca';
		$tabla1 = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla1) > 0) {
			$fila1 = $objDB->sf($tabla1);
			$cara01id = $fila1['cara01id'];
		}
		if ($cara01id != 0) {
			$sSQL = 'UPDATE core01estprograma SET core01idcaraing=' . $cara01id . ' WHERE core01id=' . $fila['core01id'] . '';
			$result = $objDB->ejecutasql($sSQL);
			$iActualizados++;
		}
	}
	$sError = 'Se han actualizado ' . formato_numero($iActualizados) . ' de ' . formato_numero($iBase) . '';
	$iTipoError = 1;
}
//limpiar la pantalla
if ($_REQUEST['paso'] == -1) {
	$_REQUEST['core01idtercero'] = 0; //$idTercero;
	$_REQUEST['core01idtercero_td'] = $APP->tipo_doc;
	$_REQUEST['core01idtercero_doc'] = '';
	$_REQUEST['core01idprograma'] = '';
	$_REQUEST['core01idplandeestudios'] = 0;
	$_REQUEST['core01id'] = '';
	$_REQUEST['core01idescuela'] = '';
	$_REQUEST['core01idzona'] = '';
	$_REQUEST['core011idcead'] = '';
	$_REQUEST['core01fechainicio'] = ''; //fecha_hoy();
	$_REQUEST['core01peracainicial'] = '';
	$_REQUEST['core01fechaultmatricula'] = '';
	$_REQUEST['core01numcredbasicos'] = '';
	$_REQUEST['core01numcredespecificos'] = '';
	$_REQUEST['core01numcredelecgenerales'] = '';
	$_REQUEST['core01numcredelecescuela'] = '';
	$_REQUEST['core01numcredelecprograma'] = '';
	$_REQUEST['core01numcredeleccomplem'] = '';
	$_REQUEST['core01numcredelectivos'] = '';
	$_REQUEST['core01idestado'] = -2;
	$_REQUEST['core01numcredbasicosaprob'] = 0;
	$_REQUEST['core01numcredespecificosaprob'] = 0;
	$_REQUEST['core01numcredelecgeneralesaprob'] = 0;
	$_REQUEST['core01numcredelecescuelaaprob'] = 0;
	$_REQUEST['core01numcredelecprogramaaaprob'] = 0;
	$_REQUEST['core01numcredeleccomplemaprob'] = 0;
	$_REQUEST['core01numcredelectivosaprob'] = 0;
	$_REQUEST['core01fechafinaliza'] = ''; //fecha_hoy();
	$_REQUEST['core01peracafinal'] = 0;
	$_REQUEST['core01avanceplanest'] = 0;
	$_REQUEST['core01idrevision'] = 0; //$idTercero;
	$_REQUEST['core01idrevision_td'] = $APP->tipo_doc;
	$_REQUEST['core01idrevision_doc'] = '';
	$_REQUEST['core01fecharevision'] = ''; //fecha_hoy();
	$_REQUEST['core01gradoestado'] = 0;
	$_REQUEST['core01gradoidopcion'] = 0;
	$_REQUEST['core01gradofecha'] = 0;
	$_REQUEST['core01contmatriculas'] = 0;
	$_REQUEST['core01contciclo1'] = '';
	$_REQUEST['core01contciclo2'] = '';
	$_REQUEST['core01contciclo3'] = '';
	$_REQUEST['core01contciclo4'] = '';
	$_REQUEST['core01contciclo5'] = '';
	$_REQUEST['core01contciclo6'] = '';
	$_REQUEST['core01contciclo7'] = '';
	$_REQUEST['core01contciclo8'] = '';
	$_REQUEST['core01contciclo9'] = '';
	$_REQUEST['core01contciclo10'] = '';
	$_REQUEST['core01contciclo11'] = '';
	$_REQUEST['core01contciclo12'] = '';
	$_REQUEST['core01contciclo13'] = '';
	$_REQUEST['core01contciclo14'] = '';
	$_REQUEST['core01contciclo15'] = '';
	$_REQUEST['core01contciclo16'] = '';
	$_REQUEST['core01contciclo17'] = '';
	$_REQUEST['core01contciclo18'] = '';
	$_REQUEST['core01contciclo19'] = '';
	$_REQUEST['core01contciclo20'] = '';
	$_REQUEST['core01contestado'] = 0;
	$_REQUEST['core01estadopractica'] = 0;
	$_REQUEST['core01idtipopractica'] = 0;
	$_REQUEST['core01idlineaprof'] = 0;
	$_REQUEST['core01idlineaprof2'] = 0;
	$_REQUEST['core01condicion'] = 0;
	$_REQUEST['core01semestrerelativo'] = 1;
	$_REQUEST['core01ciclobase'] = 0;
	$_REQUEST['core01idpruebaestado'] = 0;
	$_REQUEST['core01sem_proyectados'] = 0;
	$_REQUEST['core01sem_total'] = 0;
	$_REQUEST['core01factordeserta'] = 0;
	$_REQUEST['paso'] = 0;
}
if ($bLimpiaHijos) {
	$_REQUEST['core22anotacion'] = '';
	$_REQUEST['corf01idestprograma'] = '';
	$_REQUEST['corf01consec'] = '';
	$_REQUEST['corf01id'] = '';
	$_REQUEST['corf01visible'] = 0;
	$_REQUEST['corf01anotacion'] = '';
	$_REQUEST['corf01idorigenanexo'] = 0;
	$_REQUEST['corf01idarchivoanexo'] = 0;
	$_REQUEST['corf01usuario'] = 0; //$idTercero;
	$_REQUEST['corf01usuario_td'] = $APP->tipo_doc;
	$_REQUEST['corf01usuario_doc'] = '';
	$_REQUEST['corf01fecha'] = ''; //fecha_hoy();
	$_REQUEST['corf01hora'] = fecha_hora();
	$_REQUEST['corf01minuto'] = fecha_minuto();
}
//AQUI SE DEBEN CARGAR TODOS LOS DATOS QUE LA FORMA NECESITE.
//DATOS PARA COMPLETAR EL FORMULARIO
$bMuestraPei = false;
$bConPlan = false;
$bBotonAdmitir = false;
$bTieneExcepciones = false;
$bPuedeRevisar = false;
$iNumLineas = 0;
$sIds14 = '';
if ($_REQUEST['paso'] > 0) {
	switch ($_REQUEST['core01idestado']) {
		case '-2':
			$bBotonAdmitir = true;
			break;
		case '-1':
			$bConPlan = true;
			if ($_REQUEST['core01idrevision'] == 0) {
				list($bPuedeRevisar, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 3, $idTercero, $objDB);
			}
			break;
		case 0: // Activo
		case 2: // Inactivo
		case 3: // Sin matricula por 2 años
		case 6: // Desertor.
		case 9: // Retirado
		case 12: // Cambio de programa .
			list($bPuedeRevisar, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 3, $idTercero, $objDB);
			$bConPlan = true;
			//if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' <b>Revisando Exepciones</b><br>';}
			list($bTieneExcepciones, $sIds14, $sDebugE) = f2201_Excepciones($_REQUEST['core01id'], $objDB, $bDebug);
			$sDebug = $sDebug . $sDebugE;
			break;
		default:
			$bConPlan = true;
			break;
	}
}
$html_continuidad = '';
//Permisos adicionales
list($idCohorteOrigen, $sDebugC) = f217_CohorteAFecha($iHoy, $objDB);
$seg_5 = 0;
$seg_6 = 0;
$seg_12 = 0;
list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 6, $idTercero, $objDB);
if ($bDevuelve) {
	$seg_6 = 1;
}
//Crear los controles que requieran llamado a base de datos
$objCombos = new clsHtmlCombos();
$objTercero = new clsHtmlTercero();
list($core01idtercero_rs, $_REQUEST['core01idtercero'], $_REQUEST['core01idtercero_td'], $_REQUEST['core01idtercero_doc']) = html_tercero($_REQUEST['core01idtercero_td'], $_REQUEST['core01idtercero_doc'], $_REQUEST['core01idtercero'], 0, $objDB);
$objCombos->nuevo('core01idzona', $_REQUEST['core01idzona'], true, '{' . $ETI['msg_seleccione'] . '}');
$objCombos->sAccion = 'carga_combo_core011idcead();';
$sSQL = 'SELECT unad23id AS id, unad23nombre AS nombre FROM unad23zona WHERE unad23conestudiantes="S" ORDER BY unad23nombre';
$html_core01idzona = $objCombos->html($sSQL, $objDB);
$html_core011idcead = f2202_HTMLComboV2_core011idcead($objDB, $objCombos, $_REQUEST['core011idcead'], $_REQUEST['core01idzona']);
$objCombos->nuevo('core01peracainicial', $_REQUEST['core01peracainicial'], true, '{' . $ETI['msg_seleccione'] . '}');
$objCombos->iAncho = 370;
$sSQL = f146_ConsultaCombo('', $objDB);
$html_core01peracainicial = $objCombos->html($sSQL, $objDB);

list($core01idestado_nombre, $sErrorDet) = tabla_campoxid('core02estadoprograma', 'core02nombre', 'core02id', $_REQUEST['core01idestado'], '{' . $ETI['msg_sindato'] . '}', $objDB);
$html_core01idestado = html_oculto('core01idestado', $_REQUEST['core01idestado'], $core01idestado_nombre);
list($core01idrevision_rs, $_REQUEST['core01idrevision'], $_REQUEST['core01idrevision_td'], $_REQUEST['core01idrevision_doc']) = html_tercero($_REQUEST['core01idrevision_td'], $_REQUEST['core01idrevision_doc'], $_REQUEST['core01idrevision'], 0, $objDB);
list($core01gradoestado_nombre, $sErrorDet) = tabla_campoxid('core32gradoestado', 'core32nombre', 'core32id', $_REQUEST['core01gradoestado'], '{' . $ETI['msg_sindato'] . '}', $objDB);
$html_core01gradoestado = html_oculto('core01gradoestado', $_REQUEST['core01gradoestado'], $core01gradoestado_nombre);
list($core01gradoidopcion_nombre, $sErrorDet) = tabla_campoxid('core38opciongrado', 'core38nombre', 'core38id', $_REQUEST['core01gradoidopcion'], '{' . $ETI['msg_ninguna'] . '}', $objDB);
$html_core01gradoidopcion = html_oculto('core01gradoidopcion', $_REQUEST['core01gradoidopcion'], $core01gradoidopcion_nombre);
$et_core01peracafinal = '&nbsp;';

$bPuedeCambiarVersion = false;
$bPuedeActualizar = false;
$bNecesitaImportar = false;
$bActualizNotas = false;
$bCambioPrograma = false;
$bConCicloBase = false;
$bConPractica = false;
$bConLineaProfundiza = false;
$bConContinuidad = false;
$bConFactorDeserta = false;
$bOfreceTitulo = false;
$bPuedeCambiarCondicion = true;
$bPuedeActivar = false;
$bVerificaGrado = false;
$idContenedor = 0;
$iMaxCicloBase = 0;
if ((int)$_REQUEST['paso'] != 0) {
	$bPuedeCambiarCondicion = false;
	switch ($_REQUEST['core01idestado']) {
		case -2: //Aspirante
			$bPuedeCambiarCondicion = true;
			break;
		case -1: //Admitido
		case 0: //Activo
			$bPuedeActualizar = true;
			$bActualizNotas = true;
			break;
		case 2: //Inactivo
		case 3: //Sin matricula por 2 años o mas.
			$bPuedeActualizar = true;
			$bActualizNotas = true;
			$bConFactorDeserta = true;
			break;
		case 7: //Graduando
			$bVerificaGrado = true;
			break;
		case 9: // Retirado
			$bConFactorDeserta = true;
			$bPuedeActivar = true;
			break;
		case 12: // Cambio de programa.
			$bPuedeActivar = true;
		default:
			break;
	}
	if ($_REQUEST['core01estadopractica'] != -1) {
		$bConPractica = true;
		list($core01estadopractica_nombre, $sErrorDet) = tabla_campoxid('olab43estadopractica', 'olab43nombre', 'olab43id', $_REQUEST['core01estadopractica'], '{' . $ETI['msg_sindato'] . '}', $objDB);
		$html_core01estadopractica = html_oculto('core01estadopractica', $_REQUEST['core01estadopractica'], $core01estadopractica_nombre);
		list($core01idtipopractica_nombre, $sErrorDet) = tabla_campoxid('olab41tipopractica', 'olab41titulo', 'olab41id', $_REQUEST['core01idtipopractica'], '{' . $ETI['msg_sindato'] . '}', $objDB);
		$html_core01idtipopractica = html_oculto('core01idtipopractica', $_REQUEST['core01idtipopractica'], $core01idtipopractica_nombre);
	}
	if ($_REQUEST['core01idplandeestudios'] != 0) {
		$sSQL = 'SELECT 1 FROM core21lineaprof WHERE core21idprograma=' . $_REQUEST['core01idprograma'] . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$sSQL = 'SELECT core10numlineasprof FROM core10programaversion WHERE core10id=' . $_REQUEST['core01idplandeestudios'] . '';
			$tabla = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla) > 0) {
				$fila = $objDB->sf($tabla);
				$iNumLineas = $fila['core10numlineasprof'];
			}
		}
	}
	if ($iNumLineas > 0) {
		switch ($_REQUEST['core01idlineaprof']) {
			case -1:
				break;
			case 0:
				if ((int)$_REQUEST['core01idplandeestudios'] != 0) {
					$bConLineaProfundiza = true;
					$objCombos->nuevo('core01idlineaprof', $_REQUEST['core01idlineaprof'], true, $ETI['msg_ninguna'], 0);
					$objCombos->iAncho = 300;
					$sSQL = 'SELECT core21id AS id, core21nombre AS nombre FROM core21lineaprof WHERE core21idprograma=' . $_REQUEST['core01idprograma'] . ' ORDER BY core21nombre';
					$html_core01idlineaprof = $objCombos->html($sSQL, $objDB);
				}
				break;
			default:
				if ((int)$_REQUEST['core01idplandeestudios'] != 0) {
					$bConLineaProfundiza = true;
					list($core01idlineaprof_nombre, $sErrorDet) = tabla_campoxid('core21lineaprof', 'core21nombre', 'core21id', $_REQUEST['core01idlineaprof'], '{' . $ETI['msg_sindato'] . '}', $objDB);
					$html_core01idlineaprof = html_oculto('core01idlineaprof', $_REQUEST['core01idlineaprof'], $core01idlineaprof_nombre);
				}
				break;
		}
	}
	if ($iNumLineas > 1) {
		switch ($_REQUEST['core01idlineaprof2']) {
			case -1:
				break;
			case 0:
				$objCombos->nuevo('core01idlineaprof2', $_REQUEST['core01idlineaprof2'], true, $ETI['msg_ninguna'], 0);
				$objCombos->iAncho = 300;
				$sSQL = 'SELECT core21id AS id, core21nombre AS nombre FROM core21lineaprof WHERE core21idprograma=' . $_REQUEST['core01idprograma'] . ' ORDER BY core21nombre';
				$html_core01idlineaprof2 = $objCombos->html($sSQL, $objDB);
				break;
			default:
				list($core01idlineaprof2_nombre, $sErrorDet) = tabla_campoxid('core21lineaprof', 'core21nombre', 'core21id', $_REQUEST['core01idlineaprof2'], '{' . $ETI['msg_sindato'] . '}', $objDB);
				$html_core01idlineaprof2 = html_oculto('core01idlineaprof2', $_REQUEST['core01idlineaprof2'], $core01idlineaprof2_nombre);
				break;
		}
	}
	$bConContinuidad = true;
	/*
	//La continuidad aplica del periodo 761 para adelante.
	if ($_REQUEST['core01peracainicial'] > 760) {
		$bConContinuidad = true;
	} else {
		//La otra opcion es que la continuidad nos marque mas de 2 matriculas.
		if ($_REQUEST['core01contmatriculas'] > 1) {
			$bConContinuidad = true;
		}
	}
	*/
	if ($bConContinuidad) {
		//$core01contestado_nombre=$acore01contestado[$_REQUEST['core01contestado']];
		list($core01contestado_nombre, $sErrorDet) = tabla_campoxid('core36estadocont', 'core36nombre', 'core36id', $_REQUEST['core01contestado'], '{' . $ETI['msg_sindato'] . '}', $objDB);
		$html_core01contestado = html_oculto('core01contestado', $_REQUEST['core01contestado'], $core01contestado_nombre);
		$html_continuidad = f2201_TablaContinuidad($_REQUEST['core01id'], $objDB, $bDebug);
	}
	if ($bConFactorDeserta) {
		$et_core01factordeserta = ' {' . $_REQUEST['core01factordeserta'] . '}';
		if ($_REQUEST['core01factordeserta'] == 0){
			$et_core01factordeserta = ' {' . $ETI['msg_pendiente'] . '}';
		} else {
			$sSQL = 'SELECT cara15nombre FROM cara15factordeserta WHERE cara15id=' . $_REQUEST['core01factordeserta'] . '';
			$tabla = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla) > 0){
				$fila = $objDB->sf($tabla);
				$et_core01factordeserta = cadena_notildes($fila['cara15nombre']);
			}
		}
	}
	$objCombos->nuevo('corf01visible', $_REQUEST['corf01visible'], true, $ETI['no'], 0);
	$objCombos->addItem(1, $ETI['si']);
	//$objCombos->addArreglo($acorf01visible, $icorf01visible);
	$html_corf01visible = $objCombos->html('', $objDB);
	list($corf01usuario_rs, $_REQUEST['corf01usuario'], $_REQUEST['corf01usuario_td'], $_REQUEST['corf01usuario_doc']) = html_tercero($_REQUEST['corf01usuario_td'], $_REQUEST['corf01usuario_doc'], $_REQUEST['corf01usuario'], 0, $objDB);
	//Ahora traemos datos del programa
	$sSQL = 'SELECT core09ofrecetitulo, core09nivelestnuevos, core09formapromocion 
	FROM core09programa 
	WHERE core09id=' . $_REQUEST['core01idprograma'] . '';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		if ($fila['core09ofrecetitulo'] != 0){
			$bOfreceTitulo = true;
		}
		if ($fila['core09formapromocion'] == 1){
			if ($fila['core09nivelestnuevos'] > 1) {
				$bConCicloBase = true;
				$iMaxCicloBase = $fila['core09nivelestnuevos'] - 1;
			}
		}
	}
}
if ($bConCicloBase) {
	if ($_REQUEST['core01ciclobase'] == 0) {
		$objCombos->nuevo('core01ciclobase', $_REQUEST['core01ciclobase'], true, $acore01ciclobase[0], 0);
		for ($k = 1; $k <= $iMaxCicloBase; $k++) {
			$objCombos->addItem($k, $acore01ciclobase[$k]);
		}
		$html_core01ciclobase = $objCombos->html('', $objDB);
	} else {
		$html_core01ciclobase = html_oculto('core01ciclobase', $_REQUEST['core01ciclobase'], $acore01ciclobase[$_REQUEST['core01ciclobase']]);
	}
}
$bPuedeCambiarEscuela = true;
if ((int)$_REQUEST['paso'] != 0) {
	$bPuedeCambiarEscuela = false;
	if ((int)$_REQUEST['core01idestado'] == -2) {
		$bPuedeCambiarEscuela = true;
	}
}
if ($bPuedeCambiarEscuela) {
	$html_core01idprograma = f2202_HTMLComboV2_core01idprograma($objDB, $objCombos, $_REQUEST['core01idprograma'], $_REQUEST['core01idescuela']);
	$objCombos->nuevo('core01idescuela', $_REQUEST['core01idescuela'], true, '{' . $ETI['msg_seleccione'] . '}');
	$objCombos->iAncho = 400;
	$objCombos->sAccion = 'carga_combo_core01idprograma();';
	$sSQL = 'SELECT core12id AS id, core12nombre AS nombre 
	FROM core12escuela 
	WHERE core12tieneestudiantes="S" 
	ORDER BY core12nombre';
	$html_core01idescuela = $objCombos->html($sSQL, $objDB);
} else {
	list($core01idprograma_nombre, $sErrorDet) = tabla_campoxid('core09programa', 'CONCAT(core09codigo, " - ", core09nombre)', 'core09id', $_REQUEST['core01idprograma'], '{' . $ETI['msg_sindato'] . '}', $objDB);
	$html_core01idprograma = html_oculto('core01idprograma', $_REQUEST['core01idprograma'], $core01idprograma_nombre);
	if ((int)$_REQUEST['core01idplandeestudios'] != 0) {
		list($bPuedeCambiarVersion, $bNecesitaImportar, $iPlanActual, $sDebugR) = f2202_PuedeEditarPlanEstudios($_REQUEST['core01id'], $objDB, $bDebug);
		if ($bNecesitaImportar) {
			$bActualizNotas = false;
		}
	} else {
		$bPuedeCambiarVersion = true;
		if ($bDebug) {
			if ($_REQUEST['core01peracainicial'] < 761) {
				$bNecesitaImportar = true;
			}
		}
	}
	list($core01idescuela_nombre, $sErrorDet) = tabla_campoxid('core12escuela', 'core12nombre', 'core12id', $_REQUEST['core01idescuela'], '{' . $ETI['msg_sindato'] . '}', $objDB);
	$html_core01idescuela = html_oculto('core01idescuela', $_REQUEST['core01idescuela'], $core01idescuela_nombre);
	if ($_REQUEST['core01peracafinal'] != 0) {
		list($et_core01peracafinal, $sErrorDet) = tabla_campoxid('exte02per_aca', 'exte02nombre', 'exte02id', $_REQUEST['core01peracafinal'], '{' . $_REQUEST['core01peracafinal'] . '}', $objDB);
	}
}
if ($bPuedeCambiarEscuela) {
} else {
}
if ($bPuedeCambiarVersion) {
	$html_core01idplandeestudios = f2202_HTMLComboV2_core01idplandeestudios($objDB, $objCombos, $_REQUEST['core01idplandeestudios'], $_REQUEST['core01idprograma']);
} else {
	$core01idplandeestudios_nombre = f2210_TituloPlan($_REQUEST['core01idplandeestudios'], $objDB, false, false);
	$html_core01idplandeestudios = html_oculto('core01idplandeestudios', $_REQUEST['core01idplandeestudios'], $core01idplandeestudios_nombre);
}
if ($bPuedeCambiarCondicion) {
	$objCombos->nuevo('core01condicion', $_REQUEST['core01condicion'], true, '{' . $ETI['msg_seleccione'] . '}');
	$sSQL = 'SELECT corf02id AS id, corf02nombre AS nombre FROM corf02estcondicion ORDER BY corf02id';
	$html_core01condicion = $objCombos->html($sSQL, $objDB);
} else {
	list($core01condicion_nombre, $sErrorDet) = tabla_campoxid('corf02estcondicion', 'corf02nombre', 'corf02id', $_REQUEST['core01condicion'], '{' . $ETI['msg_sindato'] . '}', $objDB);
	$html_core01condicion = html_oculto('core01condicion', $_REQUEST['core01condicion'], $core01condicion_nombre);
}
if ($bTieneExcepciones) {
	$objCombos->nuevo('corf14idcurso', $_REQUEST['corf14idcurso'], true, '{' . $ETI['msg_seleccione'] . '}');
	$objCombos->iAncho = 300;
	$sOtrasLineas = '';
	$sSQL = 'SELECT TB.corf14idcurso AS id, CONCAT(T40.unad40titulo, " - ", T40.unad40nombre, " [", T13.core13nombre, "]") AS nombre 
	FROM corf14cursoexcepcion AS TB, unad40curso AS T40, core13tiporegistroprog AS T13 
	WHERE TB.corf14idplanest=' . $_REQUEST['core01idplandeestudios'] . ' AND corf14idcurso IN (' . $sIds14 . ') 
	AND TB.corf14idcurso=T40.unad40id AND TB.corf14tipocredito=T13.core13id 
	ORDER BY T40.unad40titulo';
	$html_corf14idcurso = $objCombos->html($sSQL, $objDB);
}
$html_pei = '';
$html_PruebaEstado = '';
if ((int)$_REQUEST['paso'] != 0) {
	if ($_REQUEST['core01idplandeestudios'] != -1) {
		$bMuestraPei = true;
	}
	if ($bOfreceTitulo){
		if ((int)$_REQUEST['core01idpruebaestado'] != 0) {
			list($html_PruebaEstado, $sDebugH) = f2201_PruebaEstado($_REQUEST['core01id'], $objDB, $bDebug);
			$sDebug = $sDebug . $sDebugH;
		}else {
			$html_PruebaEstado='<div class="rojo">'.$ETI['msg_nopruebaest'].'</div>';
		}
	}
}
if ($bMuestraPei) {
	$aParametros2203[101] = $_REQUEST['paginaf2203'];
	$aParametros2203[102] = $_REQUEST['lppf2203'];
	$aParametros2203[103] = $_REQUEST['bnombre2203'];
	$aParametros2203[104] = $_REQUEST['blistar2203'];
	$aParametros2203[105] = $_REQUEST['bcod2203'];
	$aParametros2203[106] = $_REQUEST['btipo2203'];
	$objPEI = new unad_pei($_REQUEST['core01idtercero'], $_REQUEST['core01idprograma']);
	$objPEI->iJuegoCampos = 2;
	list($sErrorP, $sDebugP) = $objPEI->cargarV2($aParametros2203, $objDB, false, $bDebug);
	$sDebug = $sDebug . $sDebugP;
	list($html_pei, $sDebugR) = $objPEI->html_encabezado();
	$sDebug = $sDebug . $sDebugR;
}
//Alistar datos adicionales
$id_rpt = 0;
//$id_rpt=reportes_id(_Identificador_Tipo_Reporte_, $objDB);
$objCombos->nuevo('blistar', $_REQUEST['blistar'], true, '{' . $ETI['msg_todos'] . '}');
$objCombos->sAccion = 'paginarf2202()';
$objCombos->addArreglo($ablistar2202, $iblistar2202);
$html_blistar = $objCombos->html('', $objDB);
$objCombos->nuevo('bzona', $_REQUEST['bzona'], true, '{' . $ETI['msg_todas'] . '}');
$objCombos->sAccion = 'carga_combo_bcead()';
$sSQL = 'SELECT unad23id AS id, unad23nombre AS nombre FROM unad23zona WHERE unad23conestudiantes="S" ORDER BY unad23nombre';
$html_bzona = $objCombos->html($sSQL, $objDB);
$html_bcead = f2202_HTMLComboV2_bcead($objDB, $objCombos, $_REQUEST['bcead'], $_REQUEST['bzona']);
$objCombos->nuevo('bescuela', $_REQUEST['bescuela'], true, '{' . $ETI['msg_todas'] . '}');
$objCombos->sAccion = 'carga_combo_bprograma()';
$sSQL = 'SELECT core12id AS id, core12nombre AS nombre FROM core12escuela WHERE core12tieneestudiantes="S" AND core12id>0 ORDER BY core12nombre';
$html_bescuela = $objCombos->html($sSQL, $objDB);
$html_bprograma = f2202_HTMLComboV2_bprograma($objDB, $objCombos, $_REQUEST['bprograma'], $_REQUEST['bescuela'], 0, $_REQUEST['bnivelforma']);
$html_bversion = f2202_HTMLComboV2_bversion($objDB, $objCombos, $_REQUEST['bversion'], $_REQUEST['bprograma']);
$objCombos->nuevo('bconvenio', $_REQUEST['bconvenio'], true, '{' . $ETI['msg_todos'] . '}');
$objCombos->iAncho = 500;
$objCombos->sAccion = 'paginarf2202()';
$sSQL = 'SELECT core50id AS id, core50nombre AS nombre FROM core50convenios ORDER BY core50estado DESC, core50nombre';
$html_bconvenio = $objCombos->html($sSQL, $objDB);
$objCombos->nuevo('bperiodo', $_REQUEST['bperiodo'], true, '{' . $ETI['msg_todos'] . '}');
$objCombos->iAncho = 600;
$objCombos->sAccion = 'paginarf2202()';
$sSQL = f146_ConsultaCombo();
$html_bperiodo = $objCombos->html($sSQL, $objDB);
$objCombos->nuevo('bestado', $_REQUEST['bestado'], true, '{' . $ETI['msg_todos'] . '}');
$objCombos->sAccion = 'paginarf2202()';
$sSQL = 'SELECT core02id AS id, core02nombre AS nombre FROM core02estadoprograma ORDER BY core02id';
$html_bestado = $objCombos->html($sSQL, $objDB);
$objCombos->nuevo('bcontinuidad', $_REQUEST['bcontinuidad'], true, '{' . $ETI['msg_todos'] . '}');
$objCombos->sAccion = 'paginarf2202()';
$sSQL = 'SELECT core36id AS id, core36nombre AS nombre FROM core36estadocont ORDER BY core36id';
$html_bcontinuidad = $objCombos->html($sSQL, $objDB);
$objCombos->nuevo('bcohorte', $_REQUEST['bcohorte'], true, '{' . $ETI['msg_todas'] . '}');
//$objCombos->sAccion='carga_combo_core01periodo();';
$objCombos->sAccion = 'paginarf2202();';
$sSQL = 'SELECT unae17id AS id, unae17nombre AS nombre FROM unae17cicloacadem WHERE unae17id<=' . $idCohorteOrigen . ' ORDER BY unae17orden DESC';
$html_bcohorte = $objCombos->html($sSQL, $objDB);

$objCombos->nuevo('bmatricula', $_REQUEST['bmatricula'], true, '{' . $ETI['msg_todas'] . '}');
$objCombos->sAccion = 'paginarf2202();';
$iTope = 38;
for ($k = 1; $k <= $iTope; $k++) {
	$objCombos->addItem($k, $k);
	switch ($k) {
		case 10:
		case 20:
		case 30:
			$objCombos->addItem('m'.$k, 'Mas de '.$k.'');
		break;
	}
}
$sSQL = '';
$html_bmatricula = $objCombos->html($sSQL, $objDB);
$objCombos->nuevo('bsituacion', $_REQUEST['bsituacion'], true, '{' . $ETI['msg_todas'] . '}');
$objCombos->sAccion = 'carga_combo_btipohomol()';
$sSQL = 'SELECT core65id AS id, core65nombre AS nombre FROM core65clasehomologa ORDER BY core65id';
$html_bsituacion = $objCombos->html($sSQL, $objDB);
$html_btipohomol = f2202_HTMLComboV2_btipohomol($objDB, $objCombos, $_REQUEST['btipohomol'], $_REQUEST['bsituacion']);

$objCombos->nuevo('bnivelforma', $_REQUEST['bnivelforma'], true, '{' . $ETI['msg_todas'] . '}');
//$objCombos->sAccion = 'paginarf2202();';
$objCombos->sAccion = 'carga_combo_bprograma()';
$objCombos->addItem(-1, '[Basica]');
$objCombos->addItem(-2, '[No formal]');
$objCombos->addItem(-3, '[Superior]');
$objCombos->addItem(-4, '[PostGrado]');
$sSQL = 'SELECT core22id AS id, core22nombre AS nombre FROM core22nivelprograma ORDER BY core22orden';
$html_bnivelforma = $objCombos->html($sSQL, $objDB);

$objCombos->nuevo('bopcgrado', $_REQUEST['bopcgrado'], true, '{' . $ETI['msg_todas'] . '}');
$objCombos->sAccion = 'mueve_opciongrado();';
$objCombos->addItem('PENDIENTE', '{' . $ETI['msg_pendiente'] . '}');
$sSQL='SELECT core38id AS id, core38nombre  AS nombre FROM core38opciongrado ORDER BY core38nivelacademico, core38nombre';
$html_bopcgrado = $objCombos->html($sSQL, $objDB);

$sIds09Opc = '-99';
$sSQL = 'SELECT corf54idasociado
FROM corf54programaopcion
GROUP BY corf54idasociado';
$tabla = $objDB->ejecutasql($sSQL);
while ($fila=$objDB->sf($tabla)){
	$sIds09Opc = $sIds09Opc . ',' . $fila['corf54idasociado'];
}
$objCombos->nuevo('bpostgrado', $_REQUEST['bpostgrado'], true, '{' . $ETI['msg_todos'] . '}');
$objCombos->sAccion = 'paginarf2202();';
$objCombos->iAncho = 600;
$sSQL='SELECT core09id AS id, CONCAT(core09nombre, " - ", core09codigo, CASE core09activo WHEN "S" THEN "" ELSE " [INACTIVO]" END) AS nombre 
FROM core09programa 
WHERE core09id IN (' . $sIds09Opc . ')
ORDER BY core09activo DESC, core09nombre';
$html_bpostgrado = $objCombos->html($sSQL, $objDB);

$objCombos->nuevo('bfactordeserta', $_REQUEST['bfactordeserta'], true, '{' . $ETI['msg_todos'] . '}');
$objCombos->sAccion = 'paginarf2202();';
$objCombos->addItem('PENDIENTE', '{' . $ETI['msg_pendiente'] . '}');
$sSQL = 'SELECT cara15id AS id, cara15nombre AS nombre FROM cara15factordeserta WHERE cara15id>0 ORDER BY cara15nombre';
$html_bfactordeserta = $objCombos->html($sSQL, $objDB);

//$html_blistar=$objCombos->comboSistema(2202, 1, $objDB, 'paginarf2202()');
if ($_REQUEST['paso'] != 0) {
	/*
	$objCombos->nuevo('blistar2203', $_REQUEST['blistar2203'], true, '{'.$ETI['msg_todos'].'}');
	$objCombos->sAccion='paginarf2203()';
	$sSQL='SELECT core15id AS id, core15nombre AS nombre FROM core15componentes ORDER BY core15idareaconoce, core15consec';
	$html_blistar2203=$objCombos->html($sSQL, $objDB);
	*/
	$objCombos->nuevo('btipo2203', $_REQUEST['btipo2203'], true, '{' . $ETI['msg_todos'] . '}');
	$objCombos->sAccion = 'paginarf2203()';
	//WHERE core13fijo=1 
	$sSQL = 'SELECT core13id AS id, core13nombre AS nombre FROM core13tiporegistroprog WHERE core13id NOT IN (9, 10, 11, 12) ORDER BY core13orden';
	$html_btipo2203 = $objCombos->html($sSQL, $objDB);
}
if (false) {
	$objCombos->nuevo('csv_separa', $_REQUEST['csv_separa'], false);
	$objCombos->addItem(',', $ETI['msg_coma']);
	$objCombos->addItem(';', $ETI['msg_puntoycoma']);
	$csv_separa = '<label class="Label90">' . $ETI['msg_separador'] . '</label><label class="Label130">' . $objCombos->html('', $objDB) . '</label>';
} else {
	$csv_separa = '<input id="csv_separa" name="csv_separa" type="hidden" value="," />';
}
$iNumFormatosImprime = 0;
$iModeloReporte = 2202;
$html_iFormatoImprime = '<input id="iformatoimprime" name="iformatoimprime" type="hidden" value="0" />
';
if ($_REQUEST['paso'] > 0) {
	$bDevuelve = false;
	list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 5, $idTercero, $objDB);
	if ($bDevuelve) {
		$seg_5 = 1;
	}
}
//Cargar las tablas de datos
$aParametros[0] = ''; //$_REQUEST['p1_2202'];
$aParametros[100] = $idTercero;
$aParametros[101] = $_REQUEST['paginaf2202'];
$aParametros[102] = $_REQUEST['lppf2202'];
$aParametros[103] = $_REQUEST['bdoc'];
$aParametros[104] = $_REQUEST['bnombre'];
$aParametros[105] = $_REQUEST['blistar'];
$aParametros[106] = $_REQUEST['bzona'];
$aParametros[107] = $_REQUEST['bcead'];
$aParametros[108] = $_REQUEST['bescuela'];
$aParametros[109] = $_REQUEST['bprograma'];
$aParametros[110] = $_REQUEST['bconvenio'];
$aParametros[111] = $_REQUEST['bperiodo'];
$aParametros[112] = $_REQUEST['bversion'];
$aParametros[113] = $_REQUEST['bestado'];
$aParametros[114] = $_REQUEST['bavmin'];
$aParametros[115] = $_REQUEST['bavmax'];
$aParametros[116] = $_REQUEST['bcontinuidad'];
$aParametros[117] = $_REQUEST['bcohorte'];
$aParametros[118] = $_REQUEST['bmatricula'];
$aParametros[119] = $_REQUEST['bsituacion'];
$aParametros[120] = $_REQUEST['btipohomol'];
$aParametros[121] = $_REQUEST['bnivelforma'];
$aParametros[122] = $_REQUEST['bopcgrado'];
$aParametros[123] = $_REQUEST['bpostgrado'];
$aParametros[124] = $_REQUEST['bfactordeserta'];
list($sTabla2202, $sDebugTabla) = f2202_TablaDetalleV2($aParametros, $objDB, $bDebug);
$sDebug = $sDebug . $sDebugTabla;
$sTabla2203 = '';
$sTabla2216 = '';
$sTabla2222 = '';
$sTabla2224 = '';
$sTabla2271 = '';
$sTabla12201 = '';
if ($_REQUEST['paso'] != 0) {
	if ($_REQUEST['core01idplandeestudios'] > 0) {
		//Plan de estudios
		$aParametros2203[0] = $_REQUEST['core01id'];
		$aParametros2203[100] = $idContenedor;
		$aParametros2203[101] = $_REQUEST['paginaf2203'];
		$aParametros2203[102] = $_REQUEST['lppf2203'];
		$aParametros2203[103] = $_REQUEST['bnombre2203'];
		$aParametros2203[104] = $_REQUEST['blistar2203'];
		$aParametros2203[105] = $_REQUEST['bcod2203'];
		$aParametros2203[106] = $_REQUEST['btipo2203'];
		//$aParametros2203[103]=$_REQUEST['bnombre2203'];
		//$aParametros2203[104]=$_REQUEST['blistar2203'];
		list($sTabla2203, $sDebugTabla) = f2203_TablaDetalleV2($aParametros2203, $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugTabla;
	}
	//Actas de matricula
	$aParametros2216[0] = $_REQUEST['core01id'];
	$aParametros2216[100] = $idTercero;
	$aParametros2216[101] = $_REQUEST['paginaf2216'];
	$aParametros2216[102] = $_REQUEST['lppf2216'];
	$aParametros2216[103] = $_REQUEST['core01idtercero'];
	$aParametros2216[104] = $_REQUEST['core01idprograma'];
	$aParametros2216[105] = $_REQUEST['core01idplandeestudios'];
	list($sTabla2216, $sDebugTabla) = f2216_TablaDetalleV2Consulta($aParametros2216, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugTabla;
	//Cambios de estado
	$aParametros2222[0] = $_REQUEST['core01id'];
	$aParametros2222[100] = $idTercero;
	$aParametros2222[101] = $_REQUEST['paginaf2222'];
	$aParametros2222[102] = $_REQUEST['lppf2222'];
	//$aParametros2222[103]=$_REQUEST['bnombre2222'];
	//$aParametros2222[104]=$_REQUEST['blistar2222'];
	list($sTabla2222, $sDebugTabla) = f2222_TablaDetalleV2($aParametros2222, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugTabla;
	//Cambios de actores
	$aParametros2224[0] = $_REQUEST['core01id'];
	$aParametros2224[100] = $idTercero;
	$aParametros2224[101] = $_REQUEST['paginaf2224'];
	$aParametros2224[102] = $_REQUEST['lppf2224'];
	//$aParametros2224[103]=$_REQUEST['bnombre2224'];
	//$aParametros2224[104]=$_REQUEST['blistar2224'];
	list($sTabla2224, $sDebugTabla) = f2224_TablaDetalleV2($aParametros2224, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugTabla;
	//Homologaciones externas
	$aParametros2271[0] = $_REQUEST['core01id'];
	$aParametros2271[100] = $idTercero;
	$aParametros2271[101] = $_REQUEST['paginaf2271'];
	$aParametros2271[102] = $_REQUEST['lppf2271'];
	//$aParametros2271[103] = $_REQUEST['bnombre2271'];
	//$aParametros2271[104] = $_REQUEST['blistar2271'];
	list($sTabla2271, $sDebugTabla) = f2271_TablaDetalleV2($aParametros2271, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugTabla;
	//Anotaciones
	$aParametros12201[0] = $_REQUEST['core01id'];
	$aParametros12201[100] = $idTercero;
	$aParametros12201[101] = $_REQUEST['paginaf12201'];
	$aParametros12201[102] = $_REQUEST['lppf12201'];
	//$aParametros12201[103]=$_REQUEST['bnombre12201'];
	//$aParametros12201[104]=$_REQUEST['blistar12201'];
	list($sTabla12201, $sDebugTabla) = f12201_TablaDetalleV2($aParametros12201, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugTabla;
}
$bDebugMenu = false;
list($et_menu, $sDebugM) = html_menuV2($APP->idsistema, $objDB, $iPiel, $bDebugMenu, $idTercero);
$sDebug = $sDebug . $sDebugM;
$objDB->CerrarConexion();
//FORMA
require $APP->rutacomun . 'unad_forma_v2.php';
forma_cabeceraV3($xajax, $ETI['titulo_2202']);
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
		if (window.document.frmedita.paso.value == 0) {
			expandesector(98);
			window.document.frmedita.paso.value = 10;
			window.document.frmedita.submit();
		} else {
			if (window.document.frmedita.core01ciclobase.value == window.document.frmedita.core01ciclobase_prev.value) {
				ejecuta_guardar();
			} else {
				ModalConfirm('<?php echo $ETI['msg_infocambiaciclobase']; ?>');
				ModalDialogConfirm(function(confirm) {
					if (confirm) {
						ejecuta_guardar();
					}
				});
			}
		}
	}

	function ejecuta_guardar() {
		expandesector(98);
		window.document.frmedita.paso.value = 12;
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
		document.getElementById('div_sector95').style.display = 'none';
		document.getElementById('div_sector96').style.display = 'none';
		document.getElementById('div_sector97').style.display = 'none';
		document.getElementById('div_sector98').style.display = 'none';
		document.getElementById('div_sector' + codigo).style.display = 'block';
		var sEst = 'none';
		if (codigo == 1) {
			sEst = 'block';
		}
		document.getElementById('cmdGuardarf').style.display = sEst;
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
			if (idcampo == 'core01idtercero') {
				params[4] = 'RevisaLlave';
			}
			xajax_unad11_TraerXid(params);
		}
	}

	function imprimelista() {
		if (window.document.frmedita.seg_6.value == 1) {
			window.document.frmlista.consulta.value = window.document.frmedita.consulta_2202.value;
			window.document.frmlista.titulos.value = window.document.frmedita.titulos_2202.value;
			window.document.frmlista.nombrearchivo.value = 'Estudiantes';
			window.document.frmlista.submit();
		} else {
			ModalMensaje("<?php echo $ERR['6']; ?>");
		}
	}

	function asignarvariablesv2() {
		window.document.frmimpp.v0.value = <?php echo $idTercero; ?>;
		window.document.frmimpp.v3.value = window.document.frmedita.bdoc.value;
		window.document.frmimpp.v4.value = window.document.frmedita.bnombre.value;
		window.document.frmimpp.v5.value = window.document.frmedita.blistar.value;
		window.document.frmimpp.v6.value = window.document.frmedita.bzona.value;
		window.document.frmimpp.v7.value = window.document.frmedita.bcead.value;
		window.document.frmimpp.v8.value = window.document.frmedita.bescuela.value;
		window.document.frmimpp.v9.value = window.document.frmedita.bprograma.value;
		window.document.frmimpp.v10.value = window.document.frmedita.bconvenio.value;
		window.document.frmimpp.v11.value = window.document.frmedita.bperiodo.value;
		window.document.frmimpp.v12.value = window.document.frmedita.bversion.value;
		window.document.frmimpp.v13.value = window.document.frmedita.bestado.value;
		window.document.frmimpp.v14.value = window.document.frmedita.bavmin.value;
		window.document.frmimpp.v15.value = window.document.frmedita.bavmax.value;
		window.document.frmimpp.v16.value = window.document.frmedita.bcontinuidad.value;
		window.document.frmimpp.v17.value = '';
		window.document.frmimpp.v18.value = window.document.frmedita.bcohorte.value;
		window.document.frmimpp.v19.value = window.document.frmedita.bmatricula.value;
		window.document.frmimpp.v20.value = window.document.frmedita.bsituacion.value;
		window.document.frmimpp.v21.value = window.document.frmedita.btipohomol.value;
		window.document.frmimpp.v22.value = window.document.frmedita.bnivelforma.value;
		window.document.frmimpp.v23.value = window.document.frmedita.bopcgrado.value;
		window.document.frmimpp.v24.value = window.document.frmedita.bpostgrado.value;
		window.document.frmimpp.v25.value = window.document.frmedita.bfactordeserta.value;
	}

	function asignarvariables() {
		//window.document.frmimpp.iformato94.value=window.document.frmedita.iformatoimprime.value;
		window.document.frmimpp.v3.value = window.document.frmedita.bescuela.value;
		window.document.frmimpp.v4.value = window.document.frmedita.bprograma.value;
		window.document.frmimpp.v5.value = window.document.frmedita.bzona.value;
		window.document.frmimpp.v6.value = window.document.frmedita.bcead.value;
		window.document.frmimpp.v7.value = window.document.frmedita.bperiodo.value;
		window.document.frmimpp.v8.value = window.document.frmedita.bestado.value;
		window.document.frmimpp.v13.value = window.document.frmedita.bavmin.value;
		window.document.frmimpp.v14.value = window.document.frmedita.bavmax.value;
		window.document.frmimpp.v15.value = '';
		/*
		window.document.frmimpp.v16.value = window.document.frmedita.bcontinuidad.value;
		window.document.frmimpp.v17.value = '';
		window.document.frmimpp.v18.value = window.document.frmedita.bcohorte.value;
		window.document.frmimpp.v19.value = window.document.frmedita.bmatricula.value;
		window.document.frmimpp.v20.value = window.document.frmedita.bsituacion.value;
		window.document.frmimpp.v21.value = window.document.frmedita.btipohomol.value;
		window.document.frmimpp.v22.value = window.document.frmedita.bnivelforma.value;
		window.document.frmimpp.v23.value = window.document.frmedita.bopcgrado.value;
		window.document.frmimpp.v24.value = window.document.frmedita.bpostgrado.value;
		window.document.frmimpp.v25.value = window.document.frmedita.bfactordeserta.value;
		*/
		//window.document.frmimpp.separa.value=window.document.frmedita.csv_separa.value.trim();
	}

	function imprimeexcel() {
		var sError = '';
		if (window.document.frmedita.seg_6.value != 1) {
			sError = "<?php echo $ERR['6']; ?>";
		}
		//if (sError==''){/*Agregar validaciones*/}
		if (sError == '') {
			asignarvariablesv2();
			window.document.frmimpp.action = '<?php echo $APP->rutacomun; ?>e2202v2.php';
			window.document.frmimpp.submit();
		} else {
			ModalMensaje(sError);
		}
	}

	function imprimep() {
		if (window.document.frmedita.seg_5.value == 1) {
			asignarvariables();
			window.document.frmimpp.action = 'p2202.php';
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
		datos[1] = window.document.frmedita.core01idtercero.value;
		datos[2] = window.document.frmedita.core01idprograma.value;
		datos[3] = window.document.frmedita.core01idplandeestudios.value;
		if ((datos[1] != '') && (datos[2] != '') && (datos[3] != '')) {
			xajax_f2202_ExisteDato(datos);
		}
	}

	function cargadato(llave1, llave2, llave3) {
		window.document.frmedita.core01idtercero.value = String(llave1);
		window.document.frmedita.core01idprograma.value = String(llave2);
		window.document.frmedita.core01idplandeestudios.value = String(llave3);
		window.document.frmedita.paso.value = 1;
		window.document.frmedita.submit();
	}

	function cargaridf2202(llave1) {
		window.document.frmedita.core01id.value = String(llave1);
		window.document.frmedita.paso.value = 3;
		window.document.frmedita.submit();
	}

	function carga_combo_core01idprograma() {
		var params = new Array();
		params[0] = window.document.frmedita.core01idescuela.value;
		document.getElementById('div_core01idprograma').innerHTML = '<b>Procesando datos, por favor espere...</b><input id="core01idprograma" name="core01idprograma" type="hidden" value="" />';
		xajax_f2202_Combocore01idprograma(params);
	}

	function carga_combo_core01idplandeestudios() {
		var params = new Array();
		params[0] = window.document.frmedita.core01idprograma.value;
		document.getElementById('div_core01idplandeestudios').innerHTML = '<b>Procesando datos, por favor espere...</b><input id="core01idplandeestudios" name="core01idplandeestudios" type="hidden" value="" />';
		xajax_f2202_Combocore01idplandeestudios(params);
	}

	function carga_combo_core011idcead() {
		var params = new Array();
		params[0] = window.document.frmedita.core01idzona.value;
		document.getElementById('div_core011idcead').innerHTML = '<b>Procesando datos, por favor espere...</b><input id="core011idcead" name="core011idcead" type="hidden" value="" />';
		xajax_f2202_Combocore011idcead(params);
	}

	function paginarf2202() {
		var params = new Array();
		params[99] = window.document.frmedita.debug.value;
		params[100] = <?php echo $idTercero; ?>;
		params[101] = window.document.frmedita.paginaf2202.value;
		params[102] = window.document.frmedita.lppf2202.value;
		params[103] = window.document.frmedita.bdoc.value;
		params[104] = window.document.frmedita.bnombre.value;
		params[105] = window.document.frmedita.blistar.value;
		params[106] = window.document.frmedita.bzona.value;
		params[107] = window.document.frmedita.bcead.value;
		params[108] = window.document.frmedita.bescuela.value;
		params[109] = window.document.frmedita.bprograma.value;
		params[110] = window.document.frmedita.bconvenio.value;
		params[111] = window.document.frmedita.bperiodo.value;
		params[112] = window.document.frmedita.bversion.value;
		params[113] = window.document.frmedita.bestado.value;
		params[114] = window.document.frmedita.bavmin.value;
		params[115] = window.document.frmedita.bavmax.value;
		params[116] = window.document.frmedita.bcontinuidad.value;
		params[117] = window.document.frmedita.bcohorte.value;
		params[118] = window.document.frmedita.bmatricula.value;
		params[119] = window.document.frmedita.bsituacion.value;
		params[120] = window.document.frmedita.btipohomol.value;
		params[121] = window.document.frmedita.bnivelforma.value;
		params[122] = window.document.frmedita.bopcgrado.value;
		params[123] = window.document.frmedita.bpostgrado.value;
		params[124] = window.document.frmedita.bfactordeserta.value;
		document.getElementById('div_f2202detalle').innerHTML = '<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf2202" name="paginaf2202" type="hidden" value="' + params[101] + '" /><input id="lppf2202" name="lppf2202" type="hidden" value="' + params[102] + '" />';
		xajax_f2202_HtmlTabla(params);
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
		document.getElementById("core01idtercero").focus();
	}

	function buscarV2016(sCampo) {
		window.document.frmedita.iscroll.value = window.pageYOffset;
		expandesector(98);
		window.document.frmedita.scampobusca.value = sCampo;
		var params = new Array();
		params[1] = sCampo;
		//params[2]=window.document.frmedita.iagno.value;
		//params[3]=window.document.frmedita.itipo.value;
		xajax_f2202_Busquedas(params);
	}

	function retornacontrol() {
		expandesector(1);
		window.scrollTo(0, window.document.frmedita.iscroll.value);
	}

	function Devuelve(sValor) {
		var sCampo = window.document.frmedita.scampobusca.value;
		if (sCampo == 'core01idtercero') {
			ter_traerxid('core01idtercero', sValor);
		}
		if (sCampo == 'core01idrevision') {
			ter_traerxid('core01idrevision', sValor);
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
		if (ref == 12201) {
			if (sRetorna != '') {
				window.document.frmedita.corf01idorigenanexo.value = window.document.frmedita.div96v1.value;
				window.document.frmedita.corf01idarchivoanexo.value = sRetorna;
				verboton('beliminacorf01idarchivoanexo', 'block');
			}
			archivo_lnk(window.document.frmedita.corf01idorigenanexo.value, window.document.frmedita.corf01idarchivoanexo.value, 'div_corf01idarchivoanexo');
			paginarf12201();
		}
		MensajeAlarmaV2('', 0);
		retornacontrol();
	}

	function carga_combo_bprograma() {
		var params = new Array();
		params[0] = window.document.frmedita.bescuela.value;
		params[1] = 0;
		params[2] = window.document.frmedita.bnivelforma.value;
		xajax_f2202_Combobprograma(params);
	}

	function carga_combo_bversion() {
		var params = new Array();
		params[0] = window.document.frmedita.bprograma.value;
		xajax_f2202_Combobversion(params);
	}

	function carga_combo_bcead() {
		var params = new Array();
		params[0] = window.document.frmedita.bzona.value;
		xajax_f2202_Combobcead(params);
	}
	function carga_combo_btipohomol() {
		var params = new Array();
		params[0] = window.document.frmedita.bsituacion.value;
		params[1] = 0;
		xajax_f2202_Combobtipohomol(params);
	}

	function mueve_opciongrado() {
		let sEstilo = 'none';
		if (window.document.frmedita.bopcgrado.value == 5) {sEstilo = 'block';}
		if (window.document.frmedita.bopcgrado.value == 16) {sEstilo = 'block';}
		document.getElementById('lbl_bpostgrado_1').style.display = sEstilo;
		document.getElementById('lbl_bpostgrado_2').style.display = sEstilo;
		paginarf2202();
	}
<?php
if ($_REQUEST['paso'] != 0) {
	//Esta funcion se trae de la js2203
?>

		function paginarf2203() {
			var params = new Array();
			params[0] = window.document.frmedita.core01id.value;
			params[99] = window.document.frmedita.debug.value;
			params[100] = <?php echo $idContenedor; ?>;
			params[101] = window.document.frmedita.paginaf2203.value;
			params[102] = window.document.frmedita.lppf2203.value;
			params[103] = window.document.frmedita.bnombre2203.value;
			params[104] = window.document.frmedita.blistar2203.value;
			params[105] = window.document.frmedita.bcod2203.value;
			params[106] = window.document.frmedita.btipo2203.value;
			//params[103]=window.document.frmedita.bnombre2203.value;
			//params[104]=window.document.frmedita.blistar2203.value;
			document.getElementById('div_f2203detalle').innerHTML = '<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf2203" name="paginaf2203" type="hidden" value="' + params[101] + '" /><input id="lppf2203" name="lppf2203" type="hidden" value="' + params[102] + '" />';
			xajax_f2203_HtmlTabla(params);
		}
	<?php
	}
	?>
	<?php
	if ($bPuedeActualizar) {
	?>

		function actualizarpei() {
			window.document.frmedita.iscroll.value = window.pageYOffset;
			expandesector(98);
			window.document.frmedita.paso.value = 21;
			window.document.frmedita.submit();
		}
	<?php
	}
	if ($bBotonAdmitir) {
	?>

		function admitir() {
			window.document.frmedita.iscroll.value = window.pageYOffset;
			expandesector(98);
			window.document.frmedita.paso.value = 23;
			window.document.frmedita.submit();
		}

		function rechazar() {
			window.document.frmedita.iscroll.value = window.pageYOffset;
			expandesector(98);
			window.document.frmedita.paso.value = 24;
			window.document.frmedita.submit();
		}
	<?php
	}
	$bTieneImportar = false;
	if ($bNecesitaImportar) {
		$bTieneImportar = true;
	} else {
		if ($_REQUEST['paso'] > 0) {
			if (!$bMuestraPei) {
				$bTieneImportar = true;
			}
		}
	}
	if ($bTieneImportar) {
	?>

		function importar() {
			window.document.frmedita.iscroll.value = window.pageYOffset;
			expandesector(98);
			window.document.frmedita.paso.value = 22;
			window.document.frmedita.submit();
		}
		<?php
	}
	if ($bNecesitaImportar) {
	} else {
		if ($_REQUEST['paso'] > 0) {
		?>

			function actualizanotas() {
				window.document.frmedita.iscroll.value = window.pageYOffset;
				expandesector(98);
				window.document.frmedita.paso.value = 25;
				window.document.frmedita.submit();
			}
	<?php
		}
	}
	?>

	function verhistorial() {
		var sError = '';
		if (window.document.frmedita.seg_5.value != 1) {
			sError = "<?php echo $ERR['5']; ?>";
		}
		if (window.document.frmedita.seg_5.value == 1) {
			window.document.frmhistorial.v3.value = window.document.frmedita.core01idtercero.value;
			window.document.frmhistorial.v4.value = '';
			window.document.frmhistorial.v5.value = '';
			window.document.frmhistorial.v6.value = '';
			window.document.frmhistorial.action = '../c2/p2425.php';
			window.document.frmhistorial.submit();
		} else {
			window.alert(sError);
		}
	}

	function descargarpde() {
		var sError = '';
		if (window.document.frmedita.seg_5.value != 1) {
			sError = "<?php echo $ERR['5']; ?>";
		}
		//if (sError==''){/*Agregar validaciones*/}
		if (sError == '') {
			window.document.frmimpp.v3.value = window.document.frmedita.core01id.value;
			window.document.frmimpp.action = '<?php echo $APP->rutacomun; ?>e2203.php';
			window.document.frmimpp.submit();
		} else {
			window.alert(sError);
		}
	}

	function peipdf() {
		var sError = '';
		if (window.document.frmedita.seg_5.value != 1) {
			sError = "<?php echo $ERR['5']; ?>";
		}
		//if (sError==''){/*Agregar validaciones*/}
		if (sError == '') {
			window.document.frmimpp.v3.value = window.document.frmedita.core01id.value;
			window.document.frmimpp.action = '<?php echo $APP->rutacomun; ?>p2203.php';
			window.document.frmimpp.submit();
		} else {
			window.alert(sError);
		}
	}
	<?php
	if ($bConContinuidad) {
	?>

		function revcontinuidad() {
			window.document.frmedita.iscroll.value = window.pageYOffset;
			expandesector(98);
			window.document.frmedita.paso.value = 26;
			window.document.frmedita.submit();
		}
	<?php
	}
	if ($bPuedeActivar) {
	?>

		function activarest() {
			var sError = '';
			if (window.document.frmedita.core22anotacion.value == '') {
				sError = "Necesita un comentario";
			}
			//if (sError==''){/*Agregar validaciones*/}
			if (sError == '') {
				expandesector(98);
				window.document.frmedita.paso.value = 27;
				window.document.frmedita.submit();
			} else {
				window.alert(sError);
			}
		}
	<?php
	}
	if ($bVerificaGrado) {
	?>

		function vergrado() {
			expandesector(98);
			window.document.frmedita.paso.value = 28;
			window.document.frmedita.submit();
		}
	<?php
	}
	if ($bTieneExcepciones) {
	?>

		function addcurso() {
			if (window.document.frmedita.corf14idcurso.value == '') {
				window.alert('No ha seleccionado el curso a ser agregado');
			} else {
				window.document.frmedita.iscroll.value = window.pageYOffset;
				ModalConfirm('Se va a agregar el curso ' + window.document.frmedita.corf14idcurso.value + ' al plan de estudios <br> Desea continuar?');
				ModalDialogConfirm(function(confirm) {
					if (confirm) {
						ejecuta_addcurso();
					}
				});
			}
		}

		function ejecuta_addcurso() {
			MensajeAlarmaV2('Agregando curso al PEI...', 2);
			expandesector(98);
			window.document.frmedita.paso.value = 29;
			window.document.frmedita.submit();
		}
	<?php
	}
	if ($bPuedeRevisar) {
	?>

		function revisado() {
			window.document.frmedita.iscroll.value = window.pageYOffset;
			ModalConfirm('Confirma que se puede marcar el plan de estudio como revisado?<br>Esto le permitir&aacute; al estudiante ver su plan de estudios individual en el campus virtual, y hacer su inscripci&oacute;n a trabajo de grado.');
			ModalDialogConfirm(function(confirm) {
				if (confirm) {
					ejecuta_revisado();
				}
			});
		}

		function ejecuta_revisado() {
			MensajeAlarmaV2('<?php echo $ETI['msg_ejecutando']; ?>', 2);
			expandesector(98);
			window.document.frmedita.paso.value = 30;
			window.document.frmedita.submit();
		}
	<?php
	}
	?>

	function pasa_ec(id03, idTipo) {
		window.document.frmedita.iscroll.value = window.pageYOffset;
		MensajeAlarmaV2('<?php echo $ETI['msg_ejecutando']; ?>', 2);
		expandesector(98);
		window.document.frmedita.id03.value = id03;
		window.document.frmedita.id03tipo.value = idTipo;
		window.document.frmedita.paso.value = 31;
		window.document.frmedita.submit();
	}
	function encuesta_desc() {
		window.document.frmedita.iscroll.value = window.pageYOffset;
		MensajeAlarmaV2('<?php echo $ETI['msg_ejecutando']; ?>', 2);
		expandesector(98);
		window.document.frmedita.paso.value = 32;
		window.document.frmedita.submit();
	}
</script>
<?php
if ($_REQUEST['paso'] != 0) {
?>
	<script language="javascript" src="<?php echo $APP->rutacomun; ?>jsi/js2216consulta.js?v=2"></script>
<script language="javascript" src="jsi/js2222.js"></script>
<script language="javascript" src="jsi/js2224.js"></script>
<script language="javascript" src="jsi/js2271.js"></script>
<script language="javascript" src="jsi/js12201.js"></script>
<?php
}
?>

<form id="frmhistorial" name="frmhistorial" method="post" action="../c2/p2425.php" target="_blank">
	<input id="r" name="r" type="hidden" value="2425" />
	<input id="v3" name="v3" type="hidden" value="" />
	<input id="v4" name="v4" type="hidden" value="" />
	<input id="v5" name="v5" type="hidden" value="" />
	<input id="v6" name="v6" type="hidden" value="" />
	<input id="iformato94" name="iformato94" type="hidden" value="0" />
	<input id="separa" name="separa" type="hidden" value="," />
	<input id="rdebug" name="rdebug" type="hidden" value="<?php echo $_REQUEST['debug']; ?>" />
	<input id="clave" name="clave" type="hidden" value="" />
</form>

<form id="frmimpp" name="frmimpp" method="post" action="p2202.php" target="_blank">
<input id="r" name="r" type="hidden" value="2202" />
<input id="v0" name="v0" type="hidden" value="" />
<input id="v3" name="v3" type="hidden" value="" />
<input id="v4" name="v4" type="hidden" value="" />
<input id="v5" name="v5" type="hidden" value="" />
<input id="v6" name="v6" type="hidden" value="" />
<input id="v7" name="v7" type="hidden" value="" />
<input id="v8" name="v8" type="hidden" value="" />
<input id="v9" name="v9" type="hidden" value="" />
<input id="v10" name="v10" type="hidden" value="" />
<input id="v11" name="v11" type="hidden" value="" />
<input id="v12" name="v12" type="hidden" value="" />
<input id="v13" name="v13" type="hidden" value="" />
<input id="v14" name="v14" type="hidden" value="" />
<input id="v15" name="v15" type="hidden" value="" />
<input id="v16" name="v16" type="hidden" value="" />
<input id="v17" name="v17" type="hidden" value="" />
<input id="v18" name="v18" type="hidden" value="" />
<input id="v19" name="v19" type="hidden" value="" />
<input id="v20" name="v20" type="hidden" value="" />
<input id="v21" name="v21" type="hidden" value="" />
<input id="v22" name="v22" type="hidden" value="" />
<input id="v23" name="v23" type="hidden" value="" />
<input id="v24" name="v24" type="hidden" value="" />
<input id="v25" name="v25" type="hidden" value="" />
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
<input id="ihoy" name="ihoy" type="hidden" value="<?php echo $iHoy; ?>" />
<input id="shora" name="shora" type="hidden" value="<?php echo fecha_hora(); ?>" />
<input id="stipodoc" name="stipodoc" type="hidden" value="<?php echo $APP->tipo_doc; ?>" />
<input id="idusuario" name="idusuario" type="hidden" value="<?php echo $_SESSION['unad_id_tercero']; ?>" />
<input id="id11" name="id11" type="hidden" value="<?php echo $idTercero; ?>" />
<input id="ipiel" name="ipiel" type="hidden" value="<?php echo $iPiel; ?>" />
<input id="seg_5" name="seg_5" type="hidden" value="<?php echo $seg_5; ?>" />
<input id="seg_6" name="seg_6" type="hidden" value="<?php echo $seg_6; ?>" />
<input id="id03" name="id03" type="hidden" value="" />
<input id="id03tipo" name="id03tipo" type="hidden" value="" />
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
$sScript = 'imprimeexcel()';
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
<input id="cmdGuardar" name="cmdGuardar" type="button" class="btUpGuardar" onclick="enviaguardar();" title="<?php echo $ETI['bt_guardar']; ?>" value="<?php echo $ETI['bt_guardar']; ?>" />
</div>
<div class="titulosI">
<?php
echo '<h2>' . $ETI['titulo_2202'] . '</h2>';
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
<div class="ir_derecha"<?php echo $sAnchoExpandeContrae; ?>>
<?php
$sEstiloExpande = ' style="display:none;"';
$sEstiloRecoje = '';
$sEstiloDiv = '';
if ($_REQUEST['boculta2202'] != 0) {
	$sEstiloExpande = '';
	$sEstiloRecoje = ' style="display:none;"';
	$sEstiloDiv = ' style="display:none;"';
}
?>
<input id="boculta2202" name="boculta2202" type="hidden" value="<?php echo $_REQUEST['boculta2202']; ?>" />
<label class="Label30">
<input id="btexpande2202" name="btexpande2202" type="button" value="Mostrar" class="btMiniExpandir" onclick="expandepanel(2202, 'block', 0);" title="<?php echo $ETI['bt_mostrar']; ?>"<?php echo $sEstiloExpande; ?>/>
</label>
<label class="Label30">
<input id="btrecoge2202" name="btrecoge2202" type="button" value="Ocultar" class="btMiniRecoger" onclick="expandepanel(2202, 'none', 1);" title="<?php echo $ETI['bt_ocultar']; ?>" <?php echo $sEstiloRecoje; ?>/>
</label>
</div>
<div id="div_p2202"<?php echo $sEstiloDiv; ?>>
<?php
}
//Mostrar formulario para editar
?>
<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo $ETI['core01idtercero'];
?>
</label>
<label class="Label30">
<?php
echo $ETI['core01id'];
?>
</label>
<label class="Label60">
<?php
echo html_oculto('core01id', $_REQUEST['core01id']);
?>
</label>
<div class="salto1px"></div>
<input id="core01idtercero" name="core01idtercero" type="hidden" value="<?php echo $_REQUEST['core01idtercero']; ?>" />
<div id="div_core01idtercero_llaves">
<?php
$bOculto = true;
if ($_REQUEST['paso'] != 2) {
	$bOculto = false;
}
echo html_DivTerceroV2('core01idtercero', $_REQUEST['core01idtercero_td'], $_REQUEST['core01idtercero_doc'], $bOculto, 1, $ETI['ing_doc']);
?>
</div>
<div class="salto1px"></div>
<div id="div_core01idtercero" class="L"><?php echo $core01idtercero_rs; ?></div>
<div class="salto1px"></div>
<label class="Label60">
<?php
echo $ETI['core01idzona'];
?>
</label>
<label>
<?php
echo $html_core01idzona;
?>
</label>
<div class="salto1px"></div>
<label class="Label60">
<?php
echo $ETI['core011idcead'];
?>
</label>
<label>
<div id="div_core011idcead">
<?php
echo $html_core011idcead;
?>
</div>
</label>
<div class="salto1px"></div>
</div>

<div class="GrupoCampos520">
<div class="salto1px"></div>
<label class="Label90">
<?php
echo $ETI['core01idestado'];
?>
</label>
<label class="Label380">
<?php
echo $html_core01idestado;
?>
</label>
<div class="salto1px"></div>
<label class="Label90">
<?php
echo $ETI['core01idescuela'];
?>
</label>
<label class="Label380">
<?php
echo $html_core01idescuela;
?>
</label>
<div class="salto1px"></div>
<label class="Label90">
<?php
echo $ETI['core01idprograma'];
?>
</label>
<label class="Label380">
<div id="div_core01idprograma">
<?php
echo $html_core01idprograma;
?>
</div>
</label>
<div class="salto1px"></div>
<?php
if ($bConPlan) {
?>
<label class="Label160">
<?php
echo $ETI['core01idplandeestudios'];
?>
</label>
<label class="Label300">
<div id="div_core01idplandeestudios">
<?php
echo $html_core01idplandeestudios;
?>
</div>
</label>
<?php
} else {
?>
<input id="core01idplandeestudios" name="core01idplandeestudios" type="hidden" value="<?php echo $_REQUEST['core01idplandeestudios']; ?>" />
<?php
}
if ($bConLineaProfundiza) {
?>
<div class="salto1px"></div>
<label class="Label200">
<?php
echo $ETI['core01idlineaprof'];
?>
</label>
<label>
<div id="div_core01idlineaprof">
<?php
echo $html_core01idlineaprof;
?>
</div>
</label>
<?php
if ($iNumLineas > 1) {
?>
<div class="salto1px"></div>
<label class="Label200">
<?php
echo $ETI['core01idlineaprof2'];
?>
</label>
<label>
<div id="div_core01idlineaprof2">
<?php
echo $html_core01idlineaprof2;
?>
</div>
</label>
<?php
} else {
?>
<input id="core01idlineaprof2" name="core01idlineaprof2" type="hidden" value="<?php echo $_REQUEST['core01idlineaprof2']; ?>" />
<?php
}
} else {
?>
<input id="core01idlineaprof" name="core01idlineaprof" type="hidden" value="<?php echo $_REQUEST['core01idlineaprof']; ?>" />
<input id="core01idlineaprof2" name="core01idlineaprof2" type="hidden" value="<?php echo $_REQUEST['core01idlineaprof2']; ?>" />
<?php
}
?>
<?php
if ($bPuedeActualizar) {
?>
<div class="salto1px"></div>
<label class="Label160"></label>
<label class="Label130">
<input id="cmdActualizar" name="cmdActualizar" type="button" class="BotonAzul" value="<?php echo $ETI['msg_actualizar']; ?>" onclick="javascript:actualizarpei()" title="<?php echo $ETI['msg_actualizar']; ?>" />
</label>
<?php
}
if ($bNecesitaImportar) {
if (!$bPuedeActualizar) {
?>
<div class="salto1px"></div>
<label class="Label160"></label>
<?php
} else {
?>
<label class="Label30"></label>
<?php
}
?>
<label class="Label130">
<input id="cmdImportar" name="cmdImportar" type="button" class="BotonAzul" value="<?php echo $ETI['msg_importar']; ?>" onclick="javascript:importar()" title="<?php echo $ETI['msg_importar']; ?>" />
</label>
<?php
}
if ($bActualizNotas) {
?>
<label class="Label130">
<input id="cmdActualizaNota" name="cmdActualizaNota" type="button" class="BotonAzul" value="<?php echo $ETI['msg_importar']; ?>" onclick="javascript:actualizanotas()" title="<?php echo $ETI['msg_importar']; ?>" />
</label>
<?php
}
if ($bVerificaGrado) {
?>
<label class="Label130">
<input id="cmdVerGrado" name="cmdVerGrado" type="button" value="Verificar Grado" class="BotonAzul160" onclick="vergrado();" title="Verificar Grado" />
</label>
<?php
}
?>
<div class="salto1px"></div>
<label class="Label90">
<?php
echo $ETI['core01condicion'];
?>
</label>
<label>
<?php
echo $html_core01condicion;
?>
</label>
<?php
if ($bBotonAdmitir) {
?>
<div class="salto1px"></div>
<label class="Label160">&nbsp;</label>
<label class="Label130">
<input id="cmdAdmitir" name="cmdAdmitir" type="button" class="BotonAzul" value="<?php echo $ETI['msg_admitir']; ?>" onclick="javascript:admitir()" title="<?php echo $ETI['msg_admitir']; ?>" />
</label>
<label class="Label30">&nbsp;</label>
<label class="Label130">
<input id="cmdRechazar" name="cmdRechazar" type="button" class="BotonAzul" value="<?php echo $ETI['msg_rechazar']; ?>" onclick="javascript:rechazar()" title="<?php echo $ETI['msg_rechazar']; ?>" />
</label>
<?php
}
?>
<div class="salto1px"></div>
</div>

<div class="salto1px"></div>
<div class="GrupoCampos450">
<label class="Label130">
<?php
echo $ETI['core01fechainicio'];
?>
</label>
<div class="Campo220">
<?php
echo html_FechaEnNumero('core01fechainicio', $_REQUEST['core01fechainicio']); //$bvacio=false,$accion=",$iagnoini=0,$iagnofin=0
?>
</div>
<div class="salto1px"></div>
<label class="Label200">
<?php
echo $ETI['core01fechaultmatricula'];
?>
</label>
<label class="Label130">
<?php
$et_core01fechaultmatricula = '&nbsp;';
if ($_REQUEST['core01fechaultmatricula'] != 0) {
$et_core01fechaultmatricula = fecha_desdenumero($_REQUEST['core01fechaultmatricula']);
}
echo html_oculto('core01fechaultmatricula', $_REQUEST['core01fechaultmatricula'], $et_core01fechaultmatricula);
?>
</label>
<div class="salto1px"></div>
<label class="Label200">
<?php
echo $ETI['core01fechafinaliza'];
?>
</label>
<label class="Label220">
<?php
$et_core01fechafinaliza = '&nbsp;';
if ($_REQUEST['core01fechafinaliza'] != 0) {
$et_core01fechafinaliza = fecha_desdenumero($_REQUEST['core01fechafinaliza']);
}
echo html_oculto('core01fechafinaliza', $_REQUEST['core01fechafinaliza'], $et_core01fechafinaliza);
?>
</label>
<div class="salto1px"></div>
<?php
if ($_REQUEST['core01idestado'] == 10) {
?>
<label class="Label200">
<?php
echo $ETI['core01gradofecha'];
?>
</label>
<label class="Label220">
<?php
$et_core01gradofecha = '&nbsp;';
if ($_REQUEST['core01gradofecha'] != 0) {
$et_core01gradofecha = fecha_desdenumero($_REQUEST['core01gradofecha']);
}
echo html_oculto('core01gradofecha', $_REQUEST['core01gradofecha'], $et_core01gradofecha);
?>
</label>
<div class="salto1px"></div>
<?php
} else {
?>
<input id="core01gradofecha" name="core01gradofecha" type="hidden" value="<?php echo $_REQUEST['core01gradofecha']; ?>" />
<?php
}
?>
</div>

<div class="GrupoCampos520">
<div class="salto1px"></div>
<?php
if ($bConCicloBase) {
?>
<label class="Label130">
<?php
echo $ETI['core01ciclobase'];
?>
</label>
<label>
<?php
echo $html_core01ciclobase;
?>
</label>
<?php
} else {
?>
<input id="core01ciclobase" name="core01ciclobase" type="hidden" value="<?php echo $_REQUEST['core01ciclobase']; ?>" />
<?php
}
?>
<input id="core01ciclobase_prev" name="core01ciclobase_prev" type="hidden" value="<?php echo $_REQUEST['core01ciclobase']; ?>" />
<label class="Label130">
<?php
echo $ETI['core01peracainicial'];
?>
</label>
<label class="Label350">
<?php
echo $html_core01peracainicial;
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['core01peracafinal'];
?>
</label>
<label class="Label350">
<div id="div_core01peracafinal">
<?php
echo html_oculto('core01peracafinal', $_REQUEST['core01peracafinal'], $et_core01peracafinal);
?>
</div>
</label>
<?php
if ($bConPractica) {
?>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['core01idtipopractica'];
?>
</label>
<label class="Label350">
<div id="div_core01idtipopractica">
<?php
echo $html_core01idtipopractica;
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label220">
<?php
echo $ETI['core01estadopractica'];
?>
</label>
<label class="Label220">
<div id="div_core01estadopractica">
<?php
echo $html_core01estadopractica;
?>
</div>
</label>
<?php
} else {
?>
<input id="core01idtipopractica" name="core01idtipopractica" type="hidden" value="<?php echo $_REQUEST['core01idtipopractica']; ?>" />
<input id="core01estadopractica" name="core01estadopractica" type="hidden" value="<?php echo $_REQUEST['core01estadopractica']; ?>" />
<?php
}
if ($_REQUEST['paso'] > 0) {
if (!$bMuestraPei) {
?>
<div class="salto1px"></div>
<label class="Label130"></label>
<label class="Label130">
<input id="cmdImportar" name="cmdImportar" type="button" class="BotonAzul" value="<?php echo $ETI['msg_importar']; ?>" onclick="javascript:importar()" title="<?php echo $ETI['msg_importar']; ?>" />
</label>
<label class="Label30"></label>
<label class="Label30">
<input id="btpdfh" name="btpdfh" type="button" value="Exportar" class="btMiniTxt" onclick="verhistorial();" title="Historial Acad&eacute;mico" />
</label>
<?php
}
}
?>
<div class="salto1px"></div>
</div>
<?php
if ($bMuestraPei) {
?>
<div class="salto1px"></div>
<div class="GrupoCampos">
<div class="salto1px"></div>
<?php
echo $html_pei;
if ($_REQUEST['paso'] > 0) {
?>
<div class="salto1px"></div>
<div class="GrupoCamposAyuda">
<?php
echo $ETI['msg_distcreditos'];
?>
<div class="salto1px"></div>
</div>

<div class="salto1px"></div>
<div class="GrupoCamposAyuda">
<div class="ir_derecha">
<label class="Label30">
<input id="btpdfh" name="btpdfh" type="button" value="Exportar" class="btMiniTxt" onclick="verhistorial();" title="Historial Acad&eacute;mico" />
</label>
<label class="Label30">
</label>

<label class="Label30">
<input id="btexcel2203" name="btexcel2203" type="button" value="Exportar" class="btMiniExcel" onclick="descargarpde();" title="Exportar Plan de Estudios del estudiante" />
</label>
<label class="Label30">
<input id="btpdf2203" name="btpdf2203" type="button" value="Exportar" class="btMiniTxt" onclick="peipdf();" title="Plan de Estudios del estudiante en PDF" />
</label>
</div>
<label class="Label600"><b>Parametros para consultar cursos incluidos en el Plan de estudios</b></label>
<div class="salto1px"></div>
<label class="Label130">
Codigo curso
</label>
<label class="Label250">
<input id="bcod2203" name="bcod2203" type="text" value="<?php echo $_REQUEST['bcod2203']; ?>" onchange="paginarf2203()" />
</label>
<label class="Label130">
Nombre curso
</label>
<label class="Label250">
<input id="bnombre2203" name="bnombre2203" type="text" value="<?php echo $_REQUEST['bnombre2203']; ?>" onchange="paginarf2203()" />
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['msg_tiporegistro'];
?>
</label>
<label class="Label320">
<?php
echo $html_btipo2203;
?>
</label>
<?php
if (false) {
?>
<div class="salto1px"></div>
<label class="Label300">
<?php
echo $ETI['msg_componeteconoce'];
?>
</label>
<label class="Label600">
<?php
echo $html_blistar2203;
?>
</label>
<?php
} else {
?>
<input id="blistar2203" name="blistar2203" type="hidden" value="<?php echo $_REQUEST['blistar2203']; ?>" />
<?php
}
if ($bTieneExcepciones) {
?>
<label class="Label130">
Agregar al PEI
</label>
<label>
<?php
echo $html_corf14idcurso;
?>
</label>
<label class="Label130">
<input id="btAddCurso" name="btAddCurso" type="button" value="Agregar Curso" class="BotonAzul" onclick="addcurso();" title="Agregar curso al Plan de Estudios Individual" />
</label>
<?php
} else {
?>
<input id="corf14idcurso" name="corf14idcurso" type="hidden" value="<?php echo $_REQUEST['corf14idcurso']; ?>" />
<?php
}
?>
<div class="salto1px"></div>
</div>
<?php
}
?>

<div class="salto1px"></div>
<div id="div_f2203detalle">
<?php
echo $sTabla2203;
?>
</div>

<div class="salto1px"></div>
</div>
<?php
//Termina si existe un PEI
}
?>
<input id="core01numcredbasicosaprob" name="core01numcredbasicosaprob" type="hidden" value="<?php echo $_REQUEST['core01numcredbasicosaprob']; ?>" />
<input id="core01numcredespecificosaprob" name="core01numcredespecificosaprob" type="hidden" value="<?php echo $_REQUEST['core01numcredespecificosaprob']; ?>" />
<input id="core01numcredelectivosaprob" name="core01numcredelectivosaprob" type="hidden" value="<?php echo $_REQUEST['core01numcredelectivosaprob']; ?>" />
<input id="core01avanceplanest" name="core01avanceplanest" type="hidden" value="<?php echo $_REQUEST['core01avanceplanest']; ?>" />

<div class="salto1px"></div>
<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo $ETI['core01idrevision'];
?>
</label>
<?php
if ($bPuedeRevisar) {
?>
<label class="Label130">
<input id="cmdRevisado" name="cmdRevisado" type="button" value="Marcar como revisado" class="btMiniAprobar" onclick="revisado();" title="Marcar como revisado" />
</label>
<?php
}
?>
<div class="salto1px"></div>
<input id="core01idrevision" name="core01idrevision" type="hidden" value="<?php echo $_REQUEST['core01idrevision']; ?>" />
<div id="div_core01idrevision_llaves">
<?php
$bOculto = true;
echo html_DivTerceroV2('core01idrevision', $_REQUEST['core01idrevision_td'], $_REQUEST['core01idrevision_doc'], $bOculto, 0, $ETI['ing_doc']);
?>
</div>
<div class="salto1px"></div>
<div id="div_core01idrevision" class="L"><?php echo $core01idrevision_rs; ?></div>
<div class="salto1px"></div>
</div>

<div class="GrupoCampos450">
<label class="Label160">
<?php
echo $ETI['core01fecharevision'];
?>
</label>
<label class="Label220">
<?php
$et_core01fecharevision = '&nbsp;';
if ($_REQUEST['core01fecharevision'] != 0) {
$et_core01fecharevision = fecha_desdenumero($_REQUEST['core01fecharevision']);
}
echo html_oculto('core01fecharevision', $_REQUEST['core01fecharevision'], $et_core01fecharevision);
//echo html_FechaEnNumero('core01fecharevision', $_REQUEST['core01fecharevision']);
?>
</label>
<?php
if ($bOfreceTitulo) {
?>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['core01gradoestado'];
?>
</label>
<label class="Label220">
<div id="div_core01gradoestado">
<?php
echo $html_core01gradoestado;
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['core01gradoidopcion'];
?>
</label>
<label class="Label220">
<div id="div_core01gradoidopcion">
<?php
echo $html_core01gradoidopcion;
?>
</div>
</label>
<div class="salto1px"></div>
<?php
echo $html_PruebaEstado;
?>
<?php
} else {
?>
<input id="core01gradoestado" name="core01gradoestado" type="hidden" value="<?php echo $_REQUEST['core01gradoestado']; ?>" />
<input id="core01gradoidopcion" name="core01gradoidopcion" type="hidden" value="<?php echo $_REQUEST['core01gradoidopcion']; ?>" />
<?php
}
?>
<input id="core01idpruebaestado" name="core01idpruebaestado" type="hidden" value="<?php echo $_REQUEST['core01idpruebaestado']; ?>" />
<div class="salto1px"></div>
</div>

<input id="boculta2203" name="boculta2203" type="hidden" value="<?php echo $_REQUEST['boculta2203']; ?>" />
<input id="core01contmatriculas" name="core01contmatriculas" type="hidden" value="<?php echo $_REQUEST['core01contmatriculas']; ?>" />
<input id="core01contciclo1" name="core01contciclo1" type="hidden" value="<?php echo $_REQUEST['core01contciclo1']; ?>" />
<input id="core01contciclo2" name="core01contciclo2" type="hidden" value="<?php echo $_REQUEST['core01contciclo2']; ?>" />
<input id="core01contciclo3" name="core01contciclo3" type="hidden" value="<?php echo $_REQUEST['core01contciclo3']; ?>" />
<input id="core01contciclo4" name="core01contciclo4" type="hidden" value="<?php echo $_REQUEST['core01contciclo4']; ?>" />
<input id="core01contciclo5" name="core01contciclo5" type="hidden" value="<?php echo $_REQUEST['core01contciclo5']; ?>" />
<input id="core01contciclo6" name="core01contciclo6" type="hidden" value="<?php echo $_REQUEST['core01contciclo6']; ?>" />
<input id="core01contciclo7" name="core01contciclo7" type="hidden" value="<?php echo $_REQUEST['core01contciclo7']; ?>" />
<input id="core01contciclo8" name="core01contciclo8" type="hidden" value="<?php echo $_REQUEST['core01contciclo8']; ?>" />
<input id="core01contciclo9" name="core01contciclo9" type="hidden" value="<?php echo $_REQUEST['core01contciclo9']; ?>" />
<input id="core01contciclo10" name="core01contciclo10" type="hidden" value="<?php echo $_REQUEST['core01contciclo10']; ?>" />
<input id="core01contciclo11" name="core01contciclo11" type="hidden" value="<?php echo $_REQUEST['core01contciclo11']; ?>" />
<input id="core01contciclo12" name="core01contciclo12" type="hidden" value="<?php echo $_REQUEST['core01contciclo12']; ?>" />
<input id="core01contciclo13" name="core01contciclo13" type="hidden" value="<?php echo $_REQUEST['core01contciclo13']; ?>" />
<input id="core01contciclo14" name="core01contciclo14" type="hidden" value="<?php echo $_REQUEST['core01contciclo14']; ?>" />
<input id="core01contciclo15" name="core01contciclo15" type="hidden" value="<?php echo $_REQUEST['core01contciclo15']; ?>" />
<input id="core01contciclo16" name="core01contciclo16" type="hidden" value="<?php echo $_REQUEST['core01contciclo16']; ?>" />
<input id="core01contciclo17" name="core01contciclo17" type="hidden" value="<?php echo $_REQUEST['core01contciclo17']; ?>" />
<input id="core01contciclo18" name="core01contciclo18" type="hidden" value="<?php echo $_REQUEST['core01contciclo18']; ?>" />
<input id="core01contciclo19" name="core01contciclo19" type="hidden" value="<?php echo $_REQUEST['core01contciclo19']; ?>" />
<input id="core01contciclo20" name="core01contciclo20" type="hidden" value="<?php echo $_REQUEST['core01contciclo20']; ?>" />
<div class="salto1px"></div>
<?php
// -- Inicia Grupo campos 2216 Actas de matricula
?>
<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo $ETI['titulo_2216'];
?>
</label>
<label class="Label200">
<?php
echo $ETI['core01sem_proyectados'];
?>
</label>
<label class="Label30">
<?php
echo html_oculto('core01sem_proyectados', $_REQUEST['core01sem_proyectados']);
?>
</label>
<label class="Label160">
<?php
echo $ETI['core01semestrerelativo'];
?>
</label>
<label class="Label30">
<?php
echo html_oculto('core01semestrerelativo', $_REQUEST['core01semestrerelativo']);
?>
</label>
<label class="Label160">
<?php
echo $ETI['core01sem_total'];
?>
</label>
<label class="Label30">
<?php
echo html_oculto('core01sem_total', $_REQUEST['core01sem_total']);
?>
</label>
<input id="boculta2216" name="boculta2216" type="hidden" value="<?php echo $_REQUEST['boculta2216']; ?>" />
<?php
if ($_REQUEST['paso'] == 2) {
?>
<div class="salto1px"></div>
<div id="div_f2216detalle">
<?php
echo $sTabla2216;
?>
</div>
<?php
}
?>
<div class="salto1px"></div>
</div>
<?php
// -- Termina Grupo campos 2216 Actas de matricula
// -- CONTINUIDAD
if ($bConContinuidad) {
?>
<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo $ETI['msg_continuidad'];
?>
</label>
<label class="Label200">
<?php
echo $ETI['core01contestado'];
?>
</label>
<label>
<div id="div_core01contestado">
<?php
echo $html_core01contestado;
?>
</div>
</label>
<label class="Label130">
<input id="cmdUpdCont" name="cmdUpdCont" type="button" value="Actualizar" class="btMiniActualizar" onclick="revcontinuidad();" title="Actualizar continuidad" />
</label>
<div class="salto1px"></div>
<?php
echo $html_continuidad;
if ($bConFactorDeserta) {
?>
<div class="salto1px"></div>
<label class="Label220">
<?php
echo $ETI['core01factordeserta'];
?>
</label>
<label class="Label600">
<?php
echo html_oculto('core01factordeserta', $_REQUEST['core01factordeserta'], $et_core01factordeserta);
?>
</label>
<?php
	if ($_REQUEST['core01factordeserta'] ==0) {
		if ($bDebug) {
?>
<label class="Label130">
<input id="cmdEncuestaDeserta" name="cmdEncuestaDeserta" type="button" value="Encuestar" class="BotonAzul" onclick="encuesta_desc();" title="Enviar encuesta de deserci&oacute;n" />
</label>
<?php
		}
	}
} else {
?>
<input id="core01factordeserta" name="core01factordeserta" type="hidden" value="<?php echo $_REQUEST['core01factordeserta']; ?>" />
<?php
}
?>
<div class="salto1px"></div>
</div>
<?php
} else {
?>
<input id="core01contestado" name="core01contestado" type="hidden" value="<?php echo $_REQUEST['core01contestado']; ?>" />
<input id="core01factordeserta" name="core01factordeserta" type="hidden" value="<?php echo $_REQUEST['core01factordeserta']; ?>" />
<?php
}
// -- Inicia Grupo campos 2222 Cambios de estado
?>
<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo $ETI['titulo_2222'];
?>
</label>
<input id="boculta2216" name="boculta2216" type="hidden" value="<?php echo $_REQUEST['boculta2216']; ?>" />
<?php
if ($_REQUEST['paso'] == 2) {
if ($bPuedeActivar) {
?>
<label class="Label130">
<input id="cmdActivarEst" name="cmdActivarEst" type="button" value="Activar" class="BotonAzul" onclick="expandesector(2);" title="Activar estudiante" />
</label>
<?php
}
?>
<div class="salto1px"></div>
<div id="div_f2222detalle">
<?php
echo $sTabla2222;
?>
</div>
<?php
}
?>
<div class="salto1px"></div>
</div>
<?php
// -- Termina Grupo campos 2222 Cambios de estado
?>
<?php
// -- Inicia Grupo campos 2224 Cambios de actores
?>
<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo $ETI['titulo_2224'];
?>
</label>
<input id="boculta2224" name="boculta2224" type="hidden" value="<?php echo $_REQUEST['boculta2224']; ?>" />
<?php
if ($_REQUEST['paso'] == 2) {
?>
<div class="salto1px"></div>
<div id="div_f2224detalle">
<?php
echo $sTabla2224;
?>
</div>
<?php
}
?>
<div class="salto1px"></div>
</div>
<?php
// -- Termina Grupo campos 2224 Cambios de actores
?>
<?php
// -- Inicia Grupo campos 2271 Homologaciones externas
?>
<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo $ETI['titulo_2271est'];
?>
</label>
<input id="boculta2271" name="boculta2271" type="hidden" value="<?php echo $_REQUEST['boculta2271']; ?>" />
<?php
if ($_REQUEST['paso'] == 2) {
?>
<div class="salto1px"></div>
<div id="div_f2271detalle">
<?php
echo $sTabla2271;
?>
</div>
<?php
}
?>
<div class="salto1px"></div>
</div>
<?php
// -- Termina Grupo campos 2271 Homologaciones externas
?>
<?php
// -- Inicia Grupo campos 12201 Anotaciones
?>
<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo $ETI['titulo_12201'];
?>
</label>
<input id="boculta12201" name="boculta12201" type="hidden" value="<?php echo $_REQUEST['boculta12201']; ?>" />
<?php
if ($_REQUEST['paso'] == 2) {
	//if ($bCondicion) {
?>
<div class="ir_derecha"<?php echo $sAnchoExpandeContrae; ?>>
<?php
$sEstiloExpande = ' style="display:none;"';
$sEstiloRecoje = '';
$sEstiloDiv = '';
if ($_REQUEST['boculta12201'] != 0) {
	$sEstiloExpande = '';
	$sEstiloRecoje = ' style="display:none;"';
	$sEstiloDiv = ' style="display:none;"';
}
?>
<label class="Label30">
<input id="btexpande12201" name="btexpande12201" type="button" value="Expandir" class="btMiniExpandir" onclick="expandepanel(12201, 'block', 0);" title="<?php echo $ETI['bt_mostrar']; ?>"<?php echo $sEstiloExpande; ?>/>
</label>
<label class="Label30">
<input id="btrecoge12201" name="btrecoge12201" type="button" value="Recoger" class="btMiniRecoger" onclick="expandepanel(12201, 'none', 1);" title="<?php echo $ETI['bt_ocultar']; ?>"<?php echo $sEstiloRecoje; ?>/>
</label>
</div>
<div class="salto1px"></div>
<div id="div_p12201"<?php echo $sEstiloDiv; ?>>
<label class="Label130">
<?php
echo $ETI['corf01consec'];
?>
</label>
<label class="Label130">
<div id="div_corf01consec">
<?php
if ((int)$_REQUEST['corf01id'] == 0) {
?>
<input id="corf01consec" name="corf01consec" type="text" value="<?php echo $_REQUEST['corf01consec']; ?>" onchange="revisaf12201()" class="cuatro" />
<?php
} else {
echo html_oculto('corf01consec', $_REQUEST['corf01consec'], formato_numero($_REQUEST['corf01consec']));
}
?>
</div>
</label>
<label class="Label60">
<?php
echo $ETI['corf01id'];
?>
</label>
<label class="Label60">
<div id="div_corf01id">
<?php
echo html_oculto('corf01id', $_REQUEST['corf01id'], formato_numero($_REQUEST['corf01id']));
?>
</div>
</label>
<label class="Label130">
<?php
echo $ETI['corf01visible'];
?>
</label>
<label>
<?php
echo $html_corf01visible;
?>
</label>
<label class="txtAreaS">
<?php
echo $ETI['corf01anotacion'];
?>
<textarea id="corf01anotacion" name="corf01anotacion" placeholder="<?php echo $ETI['ing_campo'] . $ETI['corf01anotacion']; ?>"><?php echo $_REQUEST['corf01anotacion']; ?></textarea>
</label>
<input id="corf01idorigenanexo" name="corf01idorigenanexo" type="hidden" value="<?php echo $_REQUEST['corf01idorigenanexo']; ?>" />
<input id="corf01idarchivoanexo" name="corf01idarchivoanexo" type="hidden" value="<?php echo $_REQUEST['corf01idarchivoanexo']; ?>" />
<div class="GrupoCampos300">
<div class="salto1px"></div>
<div id="div_corf01idarchivoanexo" class="Campo300">
<?php
echo html_lnkarchivo((int)$_REQUEST['corf01idorigenanexo'], (int)$_REQUEST['corf01idarchivoanexo']);
?>
</div>
<?php
$sEstiloAnexa = '';
$sEstiloElimina = ' style="display:none;"';
if ((int)$_REQUEST['corf01id'] == 0) {
	$sEstiloAnexa = ' style="display:none;"';
}
if ((int)$_REQUEST['corf01idarchivoanexo'] != 0) {
	$sEstiloElimina = ' style="inline-block;"';
}
?>
<label class="Label30">
<input id="banexacorf01idarchivoanexo" name="banexacorf01idarchivoanexo" type="button" value="Anexar" class="btAnexarS" onclick="carga_corf01idarchivoanexo()" title="Cargar archivo"<?php echo $sEstiloAnexa; ?>/>
</label>
<label class="Label30">
<input id="beliminacorf01idarchivoanexo" name="beliminacorf01idarchivoanexo" type="button" value="Eliminar" class="btBorrarS" onclick="eliminacorf01idarchivoanexo()" title="Eliminar archivo"<?php echo $sEstiloElimina; ?>/>
</label>
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo $ETI['corf01usuario'];
?>
</label>
<div class="salto1px"></div>
<input id="corf01usuario" name="corf01usuario" type="hidden" value="<?php echo $_REQUEST['corf01usuario']; ?>" />
<div id="div_corf01usuario_llaves">
<?php
$bOculto = false;
echo html_DivTerceroV2('corf01usuario', $_REQUEST['corf01usuario_td'], $_REQUEST['corf01usuario_doc'], $bOculto, 0, $ETI['ing_doc']);
?>
</div>
<div class="salto1px"></div>
<div id="div_corf01usuario" class="L"><?php echo $corf01usuario_rs; ?></div>
<div class="salto1px"></div>
</div>
<label class="Label130">
<?php
echo $ETI['corf01fecha'];
?>
</label>
<div class="Campo220">
<?php
echo html_FechaEnNumero('corf01fecha', $_REQUEST['corf01fecha']); //$bvacio=false,$accion=",$iagnoini=0,$iagnofin=0
?>
</div>
<label class="Label130">
<?php
echo $ETI['corf01hora'];
?>
</label>
<div class="campo_HoraMin" id="div_corf01hora">
<?php
echo html_HoraMin('corf01hora', $_REQUEST['corf01hora'], 'corf01minuto', $_REQUEST['corf01minuto']);
?>
</div>
<div class="salto1px"></div>
<label class="Label130">&nbsp;</label>
<?php
$sEstiloElimina = ' style="display:none;"';
if ((int)$_REQUEST['corf01id'] != 0) {
	$sEstiloElimina = ' style="inline-block;"';
}
?>
<label class="Label30">
<input id="bguarda12201" name="bguarda12201" type="button" value="Guardar" class="btMiniGuardar" onclick="guardaf12201()" title="<?php echo $ETI['bt_mini_guardar_12201']; ?>" />
</label>
<label class="Label30">
<input id="blimpia12201" name="blimpia12201" type="button" value="Limpiar" class="btMiniLimpiar" onclick="limpiaf12201()" title="<?php echo $ETI['bt_mini_limpiar_12201']; ?>" />
</label>
<label class="Label30">
<input id="belimina12201" name="belimina12201" type="button" value="Eliminar" class="btMiniEliminar" onclick="eliminaf12201()" title="<?php echo $ETI['bt_mini_eliminar_12201']; ?>"<?php echo $sEstiloElimina; ?>/>
</label>
<?php
//Este es el cierre del div_p12201
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
<input id="bnombre12201" name="bnombre12201" type="text" value="<?php echo $_REQUEST['bnombre12201']; ?>" onchange="paginarf12201()" />
</label>
<label class="Label130">
<?php
echo $ETI['msg_Listar'];
?>
</label>
<label>
<?php
echo $html_blistar12201;
?>
</label>
<div class="salto1px"></div>
</div>
<?php
}
?>
<div id="div_f12201detalle">
<?php
echo $sTabla12201;
?>
</div>
<?php
}
?>
<div class="salto1px"></div>
</div>
<?php
// -- Termina Grupo campos 12201 Anotaciones
?>
<?php
if (false) {
	//Ejemplo de boton de ayuda
	//echo html_BotonAyuda('NombreCampo');
	//echo html_DivAyudaLocal('NombreCampo');
}
if ($bConExpande) {
	//Este es el cierre del div_p2202
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
<label class="Label160">
Documento
</label>
<label>
<input id="bdoc" name="bdoc" type="text" value="<?php echo $_REQUEST['bdoc']; ?>" onchange="paginarf2202()" autocomplete="off" />
</label>
<label class="Label90">
Nombre
</label>
<label>
<input id="bnombre" name="bnombre" type="text" value="<?php echo $_REQUEST['bnombre']; ?>" onchange="paginarf2202()" autocomplete="off" />
</label>
<div class="salto1px"></div>
<label class="Label160">
Nivel de formaci&oacute;n
</label>
<label>
<?php
echo $html_bnivelforma;
?>
</label>
<label class="Label160">
Avance m&iacute;nimo
</label>
<label class="Label60">
<input id="bavmin" name="bavmin" type="text" value="<?php echo $_REQUEST['bavmin']; ?>" class="cuatro" onchange="paginarf2202()" autocomplete="off" />
</label>
<label class="Label90">
m&aacute;ximo
</label>
<label class="Label60">
<input id="bavmax" name="bavmax" type="text" value="<?php echo $_REQUEST['bavmax']; ?>" class="cuatro" onchange="paginarf2202()" autocomplete="off" />
</label>
<div class="salto1px"></div>
<label class="Label160">
Escuela
</label>
<label class="Label500">
<?php
echo $html_bescuela;
?>
</label>
<div class="salto1px"></div>
<label class="Label160">
Programa
</label>
<label class="Label130">
<div id="div_bprograma">
<?php
echo $html_bprograma;
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label160">
Plan de estudios
</label>
<label class="Label350">
<div id="div_bversion">
<?php
echo $html_bversion;
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label160">
Estado
</label>
<label class="Label160">
<?php
echo $html_bestado;
?>
</label>
<div class="salto1px"></div>
<label class="Label160">
Zona
</label>
<label class="Label350">
<?php
echo $html_bzona;
?>
</label>
<label class="Label90">
Listar
</label>
<label class="Label130">
<?php
echo $html_blistar;
?>
</label>
<div class="salto1px"></div>
<label class="Label160">
Centro
</label>
<label>
<div id="div_bcead">
<?php
echo $html_bcead;
?>
</div>
</label>
<label class="Label130">
Continuidad
</label>
<label class="Label220">
<?php
echo $html_bcontinuidad;
?>
</label>
<div class="salto1px"></div>
<label class="Label160">
Cohorte
</label>
<label class="Label220">
<?php
echo $html_bcohorte;
?>
</label>
<label class="Label220">
Cantidad de matriculas
</label>
<label class="Label130">
<?php
echo $html_bmatricula;
?>
</label>
<div class="salto1px"></div>
<label class="Label160">
Periodo inicial
</label>
<label class="Label500">
<?php
echo $html_bperiodo;
?>
</label>
<div class="salto1px"></div>
<label class="Label160">
Convenio
</label>
<label class="Label500">
<?php
echo $html_bconvenio;
?>
</label>
<div class="salto1px"></div>
<label class="Label160">
Opci&oacute;n de grado
</label>
<label>
<?php
echo $html_bopcgrado;
?>
</label>
<?php
$sEstilo = ' style="display:none;"';
switch ($_REQUEST['bopcgrado']) {
	case 5:
	case 16:
		$sEstilo = '';
		break;
}
?>
<div class="salto1px"></div>
<label class="Label160" id="lbl_bpostgrado_1"<?php echo $sEstilo; ?>>
Postgrado asociado
</label>
<label id="lbl_bpostgrado_2"<?php echo $sEstilo; ?>>
<?php
echo $html_bpostgrado;
?>
</label>
<div class="salto1px"></div>
<label class="Label220">
Situaci&oacute;n Acad&eacute;mica
</label>
<label>
<?php
echo $html_bsituacion;
?>
</label>
<div class="salto1px"></div>
<label class="Label220">
Tipo de homologaci&oacute;n
</label>
<label>
<div id="div_btipohomol">
<?php
echo $html_btipohomol;
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label220">
Factor por el cual deserta
</label>
<label class="Label160">
<?php
echo $html_bfactordeserta;
?>
</label>

<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
<?php
echo ' ' . $csv_separa;
?>
<div id="div_f2202detalle">
<?php
echo $sTabla2202;
?>
</div>
<?php
// Termina el div_areatrabajo y DIV_areaform
?>
</div>
</div>
</div><!-- /DIV_Sector1 -->


<div id="div_sector2" style="display:none">
<div class="titulos">
<div class="titulosD">
<input id="cmdAyuda2" name="cmdAyuda2" type="button" class="btSupAyuda" onclick="muestraayuda(<?php echo $iCodModulo; ?>);" title="<?php echo $ETI['bt_ayuda']; ?>" value="<?php echo $ETI['bt_ayuda']; ?>" />
<input id="cmdVolverSec2" name="cmdVolverSec2" type="button" class="btSupVolver" onclick="expandesector(1);" title="<?php echo $ETI['bt_volver']; ?>" value="<?php echo $ETI['bt_volver']; ?>" />
</div>
<div class="titulosI">
<?php
echo '<h2>' . $ETI['titulo_activar'] . '</h2>';
?>
</div>
</div>
<div id="cargaForm">
<div id="area">
<label class="txtAreaM">
<?php
echo $ETI['core22anotacion'];
?>
<textarea id="core22anotacion" name="core22anotacion" placeholder="<?php echo $ETI['ing_campo'] . $ETI['core22anotacion']; ?>">
<?php
echo $_REQUEST['core22anotacion'];
?>
</textarea>
</label>
<div class="salto1px"></div>
<label class="Label130"></label>
<label class="Label130">
<input id="cmdVolverSec2b" name="cmdVolverSec2b" type="button" value="Volver" class="BotonAzul" onclick="expandesector(1);" title="Volver" />
</label>
<label class="Label60"></label>
<label class="Label130">
<input id="cmdActivarEst2" name="cmdActivarEst2" type="button" value="Activar" class="BotonAzul" onclick="activarest();" title="Activar estudiante" />
</label>

</div>
</div>
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
<input id="titulo_2202" name="titulo_2202" type="hidden" value="<?php echo $ETI['titulo_2202']; ?>" />
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
echo '<h2>' . $ETI['titulo_2202'] . '</h2>';
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
echo '<h2>' . $ETI['titulo_2202'] . '</h2>';
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
<script language="javascript">
$().ready(function() {
<?php
if ($_REQUEST['paso'] == 0) {
?>
<?php
}
?>
$("#core01peracainicial").chosen();
$("#bprograma").chosen();
$("#bperiodo").chosen();
$("#bconvenio").chosen();
$("#btipohomol").chosen();
$("#bpostgrado").chosen();
});
</script>
<script language="javascript" src="ac_2202.js"></script>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>unad_todas.js?ver=8"></script>
<?php
forma_piedepagina();
?>