<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2018 - 2020 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.21.0 viernes, 22 de junio de 2018
--- Modelo Versión 2.22.0 martes, 26 de junio de 2018
--- Modelo Versión 2.22.1 martes, 3 de julio de 2018
--- Modelo Versión 2.22.2 martes, 17 de julio de 2018
--- Modelo Versión 2.25.0 viernes, 3 de abril de 2020
--- Modelo Versión 2.25.5 domingo, 16 de agosto de 2020
*/

/** Archivo caracterizacion.php.
 * Modulo 2301 cara01encuesta.
 * @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
 * @param debug=1 (Opcional), bandera para indicar si se generan datos de depuración
 * @date viernes, 3 de abril de 2020
 * Cambios 12 de junio de 2020
 * 1. interpretación a valores cualitativos de los niveles y riesgo de cada una de las fichas evaluadas.
 * 2. Visualización de los resultados de la encuesta en el momento en que el estudiante finaliza y da cierre.
 * Omar Augusto Bautista Mora - UNAD - 2020
 * omar.bautista@unad.edu.co
 */
if (file_exists('./err_control.php')) {
	require './err_control.php';
}
$bDebug = false;
$bVerIntro = false;
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
if (isset($_REQUEST['intro']) != 0) {
	$bVerIntro = true;
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
$bEstudiante = true;
$bEnSesion = false;
if ((int)$_SESSION['unad_id_tercero'] > 0) {
	$bEnSesion = true;
}
if (!$bEnSesion) {
	if ($bDebug) {
		echo 'No se encuentra una sesi&oacute;n. [' . $APP->rutacomun . ']-[' . $_SESSION['unad_id_tercero'] . ']';
		die();
	}
	$_SESSION['unad_redir'] = 'encuestares.php';
	header('Location:index.php');
	die();
}
$grupo_id = 1; //Necesita ajustarlo...
$iCodModulo = 2301;
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
$mensajes_2301 = $APP->rutacomun . 'lg/lg_2301_' . $_SESSION['unad_idioma'] . '.php';
if (!file_exists($mensajes_2301)) {
	$mensajes_2301 = $APP->rutacomun . 'lg/lg_2301_es.php';
}
$mensajes_2344 = $APP->rutacomun . 'lg/lg_2344_' . $_SESSION['unad_idioma'] . '.php';
if (!file_exists($mensajes_2344)) {
	$mensajes_2344 = $APP->rutacomun . 'lg/lg_2344_es.php';
}
$mensajes_2310 = 'lg/lg_2310_' . $_SESSION['unad_idioma'] . '.php';
if (!file_exists($mensajes_2310)) {
	$mensajes_2310 = 'lg/lg_2310_es.php';
}
require $mensajes_todas;
require $mensajes_2301;
require $mensajes_2344;
require $mensajes_2310;
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
if ($bCerrado) {
	$objDB->CerrarConexion();
	require $APP->rutacomun . 'unad_forma_v2.php';
	forma_cabeceraV3($xajax, $ETI['titulo_2344']);
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
		header('Location:noticia.php?ret=encuestares.php');
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
$idEntidad = Traer_Entidad();
$mensajes_2310 = 'lg/lg_2310_' . $_SESSION['unad_idioma'] . '.php';
if (!file_exists($mensajes_2310)) {
	$mensajes_2310 = 'lg/lg_2310_es.php';
}
require $mensajes_2310;
// -- Si esta cargando la pagina por primer vez se revisa si requiere auditar y se manda a hacer un limpiar (paso -1)
if (isset($_REQUEST['paso']) == 0) {
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' No se recibe la variable marcador<br>';
		if (isset($_POST['paso']) != 0) {
			$sDebug = $sDebug . fecha_microtiempo() . ' La variable marcador viene por post ' . $_POST['paso'] . '<br>';
		}
		if (isset($_GET['r']) != 0) {
			$sDebug = $sDebug . fecha_microtiempo() . ' La variable marcador de registro esta vigente ' . $_GET['r'] . '<br>';
		}
	}
	$_REQUEST['paso'] = -1;
	if ($bEstudiante) {
		$_REQUEST['paso'] = 1;
	}
	if ($audita[1]) {
		seg_auditaingreso($iCodModulo, $_SESSION['unad_id_tercero'], $objDB);
	}
}
// -- 2301 cara01encuesta
require $APP->rutacomun . 'lib2301.php';
// -- 2310 Preguntas de la prueba
require 'lib2310.php';
$xajax = new xajax();
$xajax->configure('javascript URI', $APP->rutacomun . 'xajax/');
$xajax->register(XAJAX_FUNCTION, 'unad11_Mostrar_v2');
$xajax->register(XAJAX_FUNCTION, 'unad11_TraerXid');
$xajax->register(XAJAX_FUNCTION, 'f2301_Combocara01depto');
$xajax->register(XAJAX_FUNCTION, 'f2301_Combocara01ciudad');
$xajax->register(XAJAX_FUNCTION, 'f2301_Combocara01idcead');
$xajax->register(XAJAX_FUNCTION, 'f2301_Busqueda_cara01idconsejero');
$xajax->register(XAJAX_FUNCTION, 'sesion_abandona_V2');
$xajax->register(XAJAX_FUNCTION, 'sesion_mantenerV4');
$xajax->register(XAJAX_FUNCTION, 'f2301_HtmlTabla');
$xajax->register(XAJAX_FUNCTION, 'f2301_ExisteDato');
$xajax->register(XAJAX_FUNCTION, 'f2301_Busquedas');
$xajax->register(XAJAX_FUNCTION, 'f2301_HtmlBusqueda');
if ($bEstudiante) {
	$xajax->register(XAJAX_FUNCTION, 'f2310_GuardarRespuesta');
} else {
	$xajax->register(XAJAX_FUNCTION, 'f2301_MarcarConsejero');
	$xajax->register(XAJAX_FUNCTION, 'f2301_Combobprograma');
	$xajax->register(XAJAX_FUNCTION, 'f2301_Combobcead');
}
$xajax->processRequest();
if ($bPeticionXAJAX) {
	die(); // Esto hace que las llamadas por xajax terminen aquí.
}
$sUrlTablero = 'miscursos.php';
$sMensaje = '';
$sHTMLHistorial = '';
if (isset($APP->urltablero) != 0) {
	if (file_exists($APP->urltablero)) {
		$sUrlTablero = $APP->urltablero;
	}
}
if ($bEstudiante) {
	if ($_REQUEST['paso'] == 21) {
		$_REQUEST['paso'] = 1;
		list($sError, $sDebugE) = f2301_IniciarEncuesta($idTercero, 0, $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugE;
	}
	//Verificar que tenga una caracterizacion
	if (isset($_REQUEST['cara01idperaca']) == 0) {
		$_REQUEST['cara01idperaca'] = '';
		$sSQL = 'SELECT core01peracainicial FROM core01estprograma WHERE core01idtercero=' . $idTercero . ' ORDER BY core01peracainicial DESC';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' Consulta de verificacion ' . $sSQL . '<br>';
		}
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			$_REQUEST['cara01idperaca'] = $fila['core01peracainicial'];
			//Ver si ya tiene una caracterizacion.
			$sSQL = 'SELECT TB.cara01id, TB.cara01idperaca, TB.cara01completa, T2.exte02titulo 
			FROM cara01encuesta AS TB, exte02per_aca AS T2 
			WHERE TB.cara01idtercero=' . $idTercero . ' AND TB.cara01idperaca=T2.exte02id
			ORDER BY TB.cara01completa, TB.cara01idperaca DESC';
			$tabla = $objDB->ejecutasql($sSQL);
			$iNumFilas = $objDB->nf($tabla);
			if ($iNumFilas == 0) {
				$bVerIntro = true;
			} else {
				$fila = $objDB->sf($tabla);
				$_REQUEST['cara01idperaca'] = $fila['cara01idperaca'];
			}
		} else {
			// No tiene peraca... asi que bueno... todo para aca.
			$bDebugMenu = false;
			list($et_menu, $sDebugM) = html_menuCampus($APP->idsistema, $objDB, $iPiel, $bDebugMenu, $idTercero);
			$objDB->CerrarConexion();
			require $APP->rutacomun . 'unad_forma_v2.php';
			forma_cabeceraV3($xajax, $ETI['titulo_2301']);
			echo $et_menu;
			forma_mitad();
?>
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>js/bootstrap.min.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>css/criticalPath.css">
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>css/principal.css">
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>unad_estilos2018.css" type="text/css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.0.1/css/font-awesome.min.css">
<script language="javascript">
function irtablero() {
window.document.frmtablero.submit();
}
</script>
<?php
?>
<form id="frmtablero" name="frmtablero" method="post" action="<?php echo $sUrlTablero; ?>">
</form>
<div id="interna">
<form id="frmedita" name="frmedita" method="post" action="" autocomplete="off">
<input id="bNoAutocompletar" name="bNoAutocompletar" type="password" value="" style="display:none;" />
<div class="GrupoCampos">
<div class="MarquesinaGrande">
Usted no esta considerado para adelantar el proceso de caracterizaci&oacute;n, gracias por su tiempo.
</div>
<div class="salto1px"></div>
<label class="Label300">&nbsp;</label>
<label class="Label130">
<input id="cmdTablero" name="cmdTablero" type="button" value="Mis Cursos" class="BotonAzul" onclick="javascript:irtablero()" />
</label>
<div class="salto1px"></div>
</div>
</form>
<?php
				if ($sDebug != '') {
					$iSegFin = microtime(true);
					$iSegundos = $iSegFin - $iSegIni;
					echo '<div class="salto1px"></div><div class="GrupoCampos" id="div_debug">' . $sDebug . fecha_microtiempo() . ' Tiempo total del proceso: <b>' . $iSegundos . '</b> Segundos' . '<div class="salto1px"></div></div>';
				}
?>
</div>
<?php
			forma_piedepagina();
			die();
		}
	}
}
if ($bVerIntro) {
	$bDebugMenu = false;
	list($et_menu, $sDebugM) = html_menuCampus($APP->idsistema, $objDB, $iPiel, $bDebugMenu, $idTercero);
	if ($sMensaje == '') {
		$sMensaje = $ETI['msg_noresultados'];
	}
	require $APP->rutacomun . 'unad_forma_v2.php';
	forma_cabeceraV3($xajax, $ETI['titulo_2301']);
	echo $et_menu;
	forma_mitad();
?>
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>js/bootstrap.min.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>css/criticalPath.css">
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>css/principal.css">
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>unad_estilos2018.css" type="text/css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.0.1/css/font-awesome.min.css">
<div id="interna">
<form id="frmedita" name="frmedita" method="post" action="" autocomplete="off">
<input id="bNoAutocompletar" name="bNoAutocompletar" type="password" value="" style="display:none;" />
<input id="paso" name="paso" type="hidden" value="21" />
<div class="GrupoCampos">
<?php echo $ETI['msg_procesocarac']; ?>
<div class="GrupoCamposAyuda">
<div class="MarquesinaMedia">
<?php echo $sMensaje; ?>
</div>
</div>	
<h1>
<input id="cmdVolver" name="cmdVolver" type="submit" value="<?php echo $ETI['bt_volver']; ?>" class="BotonAzul" onclick="javascript:history.back();return false;" />
</h1>
<div class="salto1px"></div>
</div>
</form>
<?php
		if ($sDebug != '') {
			$iSegFin = microtime(true);
			$iSegundos = $iSegFin - $iSegIni;
			echo '<div class="salto1px"></div><div class="GrupoCampos" id="div_debug">' . $sDebug . fecha_microtiempo() . ' Tiempo total del proceso: <b>' . $iSegundos . '</b> Segundos' . '<div class="salto1px"></div></div>';
		}
?>
</div>
<?php
	forma_piedepagina();
	die();
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
if (isset($_REQUEST['paginaf2301']) == 0) {
	$_REQUEST['paginaf2301'] = 1;
}
if (isset($_REQUEST['lppf2301']) == 0) {
	$_REQUEST['lppf2301'] = 20;
}
if (isset($_REQUEST['boculta2301']) == 0) {
	if ($bEstudiante) {
		$_REQUEST['boculta2301'] = 0;
	} else {
		$_REQUEST['boculta2301'] = 1;
	}
}
if (isset($_REQUEST['paginaf2310']) == 0) {
	$_REQUEST['paginaf2310'] = 1;
}
if (isset($_REQUEST['lppf2310']) == 0) {
	$_REQUEST['lppf2310'] = 20;
}
if (isset($_REQUEST['bocultaResultados']) == 0) {
	$_REQUEST['bocultaResultados'] = 0;
}
// -- Inicializar variables de datos.
if (isset($_REQUEST['cara01idperaca']) == 0) {
	$_REQUEST['cara01idperaca'] = '';
}
if (isset($_REQUEST['cara01idtercero']) == 0) {
	if ($bEstudiante) {
		$_REQUEST['cara01idtercero'] = $idTercero;
	} else {
		$_REQUEST['cara01idtercero'] = 0;
	}
}
if (isset($_REQUEST['cara01idtercero_td']) == 0) {
	$_REQUEST['cara01idtercero_td'] = $APP->tipo_doc;
}
if (isset($_REQUEST['cara01idtercero_doc']) == 0) {
	$_REQUEST['cara01idtercero_doc'] = '';
}
if (isset($_REQUEST['cara01id']) == 0) {
	$_REQUEST['cara01id'] = '';
}
if (isset($_REQUEST['cara01completa']) == 0) {
	$_REQUEST['cara01completa'] = 'N';
}
if (isset($_REQUEST['cara01fichaper']) == 0) {
	$_REQUEST['cara01fichaper'] = 0;
}
if (isset($_REQUEST['cara01fichafam']) == 0) {
	$_REQUEST['cara01fichafam'] = 0;
}
if (isset($_REQUEST['cara01fichaaca']) == 0) {
	$_REQUEST['cara01fichaaca'] = 0;
}
if (isset($_REQUEST['cara01fichalab']) == 0) {
	$_REQUEST['cara01fichalab'] = 0;
}
if (isset($_REQUEST['cara01fichabien']) == 0) {
	$_REQUEST['cara01fichabien'] = 0;
}
if (isset($_REQUEST['cara01fichapsico']) == 0) {
	$_REQUEST['cara01fichapsico'] = 0;
}
if (isset($_REQUEST['cara01fichadigital']) == 0) {
	$_REQUEST['cara01fichadigital'] = 0;
}
if (isset($_REQUEST['cara01fichalectura']) == 0) {
	$_REQUEST['cara01fichalectura'] = 0;
}
if (isset($_REQUEST['cara01ficharazona']) == 0) {
	$_REQUEST['cara01ficharazona'] = '';
}
if (isset($_REQUEST['cara01fichaingles']) == 0) {
	$_REQUEST['cara01fichaingles'] = '';
}
if (isset($_REQUEST['cara01fechaencuesta']) == 0) {
	$_REQUEST['cara01fechaencuesta'] = '';
} //{fecha_hoy();}
if (isset($_REQUEST['cara01idzona']) == 0) {
	$_REQUEST['cara01idzona'] = '';
}
if (isset($_REQUEST['cara01idcead']) == 0) {
	$_REQUEST['cara01idcead'] = '';
}
if (isset($_REQUEST['cara01matconvenio']) == 0) {
	$_REQUEST['cara01matconvenio'] = 'N';
}
if (isset($_REQUEST['cara01niveldigital']) == 0) {
	$_REQUEST['cara01niveldigital'] = 0;
}
if (isset($_REQUEST['cara01nivellectura']) == 0) {
	$_REQUEST['cara01nivellectura'] = 0;
}
if (isset($_REQUEST['cara01nivelrazona']) == 0) {
	$_REQUEST['cara01nivelrazona'] = 0;
}
if (isset($_REQUEST['cara01nivelingles']) == 0) {
	$_REQUEST['cara01nivelingles'] = 0;
}
if (isset($_REQUEST['cara01fechainicio']) == 0) {
	$_REQUEST['cara01fechainicio'] = '';
} //{fecha_hoy();}
if (isset($_REQUEST['cara01idprograma']) == 0) {
	$_REQUEST['cara01idprograma'] = 0;
}
if (isset($_REQUEST['cara01idescuela']) == 0) {
	$_REQUEST['cara01idescuela'] = 0;
}
if (isset($_REQUEST['cara01fichabiolog']) == 0) {
	$_REQUEST['cara01fichabiolog'] = '';
}
if (isset($_REQUEST['cara01nivelbiolog']) == 0) {
	$_REQUEST['cara01nivelbiolog'] = 0;
}
if (isset($_REQUEST['cara01fichafisica']) == 0) {
	$_REQUEST['cara01fichafisica'] = '';
}
if (isset($_REQUEST['cara01nivelfisica']) == 0) {
	$_REQUEST['cara01nivelfisica'] = 0;
}
if (isset($_REQUEST['cara01fichaquimica']) == 0) {
	$_REQUEST['cara01fichaquimica'] = 0;
}
if (isset($_REQUEST['cara01nivelquimica']) == 0) {
	$_REQUEST['cara01nivelquimica'] = 0;
}
if (isset($_REQUEST['cara01tipocaracterizacion']) == 0) {
	$_REQUEST['cara01tipocaracterizacion'] = 0;
}
if (isset($_REQUEST['cara01psico_puntaje']) == 0) {
	$_REQUEST['cara01psico_puntaje'] = 0;
}
if ((int)$_REQUEST['paso'] > 0) {
	//Preguntas de la prueba
}
// Espacio para inicializar otras variables
$bTraerEntorno = false;
$bActualizarEdad = false;
if (isset($_REQUEST['csv_separa']) == 0) {
	$_REQUEST['csv_separa'] = ',';
}
if (isset($_REQUEST['ficha']) == 0) {
	$_REQUEST['ficha'] = 1;
}
if (!$bEstudiante) {
	if (isset($_REQUEST['bdoc']) == 0) {
		$_REQUEST['bdoc'] = '';
	}
	if (isset($_REQUEST['bnombre']) == 0) {
		$_REQUEST['bnombre'] = '';
	}
	if (isset($_REQUEST['bperiodo']) == 0) {
		$_REQUEST['bperiodo'] = '';
		$bTraerEntorno = true;
	}
	if (isset($_REQUEST['blistar']) == 0) {
		$_REQUEST['blistar'] = 1;
	}
	if (isset($_REQUEST['bescuela']) == 0) {
		$_REQUEST['bescuela'] = '';
	}
	if (isset($_REQUEST['bprograma']) == 0) {
		$_REQUEST['bprograma'] = '';
	}
	if (isset($_REQUEST['bzona']) == 0) {
		$_REQUEST['bzona'] = '';
	}
	if (isset($_REQUEST['bcead']) == 0) {
		$_REQUEST['bcead'] = '';
	}
	if (isset($_REQUEST['btipocara']) == 0) {
		$_REQUEST['btipocara'] = '';
	}
	if (isset($_REQUEST['bpoblacion']) == 0) {
		$_REQUEST['bpoblacion'] = '';
	}
	if (isset($_REQUEST['bconvenio']) == 0) {
		$_REQUEST['bconvenio'] = '';
	}
}
if ($bTraerEntorno) {
	$sSQL = 'SELECT * FROM unad95entorno WHERE unad95id=' . $idTercero . '';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		if ($fila['unad95periodo'] != 0) {
			$_REQUEST['bperiodo'] = $fila['unad95periodo'];
		}
		if ($fila['unad95escuela'] != 0) {
			$_REQUEST['bescuela'] = $fila['unad95escuela'];
		}
		if ($fila['unad95programa'] != 0) {
			$_REQUEST['bprograma'] = $fila['unad95programa'];
		}
		if ($fila['unad95zona'] != 0) {
			$_REQUEST['bzona'] = $fila['unad95zona'];
		}
		if ($fila['unad95centro'] != 0) {
			$_REQUEST['bcead'] = $fila['unad95centro'];
		}
	}
}
if ($bDebug) {
	$sDebug = $sDebug . fecha_microtiempo() . ' Corriendo el paso ' . $_REQUEST['paso'] . ' antes de entrar a leer instrucciones.<br>';
}
if ($bEstudiante) {
	if ($_REQUEST['paso'] == -1) {
		$_REQUEST['paso'] = 3;
	}
	//2022 - 08 - 19 - Le armamos el historial
	$sSQL = 'SELECT TB.cara01id, TB.cara01idperaca, TB.cara01completa, T2.exte02titulo 
	FROM cara01encuesta AS TB, exte02per_aca AS T2 
	WHERE TB.cara01idtercero=' . $idTercero . ' AND TB.cara01idperaca=T2.exte02id AND TB.cara01completa="S"
	ORDER BY TB.cara01idperaca DESC';
	$tabla = $objDB->ejecutasql($sSQL);
	$iNumFilas = $objDB->nf($tabla);
	if ($iNumFilas > 1) {
		$sHTMLHistorial = '<div class="GrupoCamposAyuda">
		<b>Periodos diligenciados:</b>
		' . html_salto() . '
		<div class="tabuladores">';
		$bMarcaInicial = false;
		while ($fila = $objDB->sf($tabla)) {
			$sNomPeriodo = cadena_notildes($fila['exte02titulo']);
			$k = $fila['cara01idperaca'];
			if ($k != $_REQUEST['cara01idperaca']) {}
			if (true){
				if ($fila['cara01completa'] == 'S') {
					$sHTMLHistorial = $sHTMLHistorial . html_BotonVerde('p_' . $k, $sNomPeriodo, 'javascript:cargaridf2301(' . $fila['cara01id'] . ');', $sNomPeriodo);
				} else {
					$sHTMLHistorial = $sHTMLHistorial . html_BotonRojo('p_' . $k, $sNomPeriodo, 'javascript:cargaridf2301(' . $fila['cara01id'] . ');', $sNomPeriodo);
				}
			}
		}
		$sHTMLHistorial = $sHTMLHistorial . html_salto().'</div></div>';
	}
}
//Si Modifica o Elimina Cargar los campos
if (($_REQUEST['paso'] == 1) || ($_REQUEST['paso'] == 3)) {
	$_REQUEST['cara01idtercero_td'] = $APP->tipo_doc;
	$_REQUEST['cara01idtercero_doc'] = '';
	if ($_REQUEST['paso'] == 1) {
		$sSQLcondi = 'cara01idperaca=' . $_REQUEST['cara01idperaca'] . ' AND cara01idtercero="' . $_REQUEST['cara01idtercero'] . '"';
	} else {
		$sSQLcondi = 'cara01id=' . $_REQUEST['cara01id'] . '';
	}
	$sSQL = 'SELECT * FROM cara01encuesta WHERE ' . $sSQLcondi;
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$_REQUEST['cara01idperaca'] = $fila['cara01idperaca'];
		$_REQUEST['cara01idtercero'] = $fila['cara01idtercero'];
		$_REQUEST['cara01id'] = $fila['cara01id'];
		$_REQUEST['cara01completa'] = $fila['cara01completa'];
		$_REQUEST['cara01fichaper'] = $fila['cara01fichaper'];
		$_REQUEST['cara01fichafam'] = $fila['cara01fichafam'];
		$_REQUEST['cara01fichaaca'] = $fila['cara01fichaaca'];
		$_REQUEST['cara01fichalab'] = $fila['cara01fichalab'];
		$_REQUEST['cara01fichabien'] = $fila['cara01fichabien'];
		$_REQUEST['cara01fichapsico'] = $fila['cara01fichapsico'];
		$_REQUEST['cara01fichadigital'] = $fila['cara01fichadigital'];
		$_REQUEST['cara01fichalectura'] = $fila['cara01fichalectura'];
		$_REQUEST['cara01ficharazona'] = $fila['cara01ficharazona'];
		$_REQUEST['cara01fichaingles'] = $fila['cara01fichaingles'];
		$_REQUEST['cara01fechaencuesta'] = $fila['cara01fechaencuesta'];
		$_REQUEST['cara01idzona'] = $fila['cara01idzona'];
		$_REQUEST['cara01idcead'] = $fila['cara01idcead'];
		$_REQUEST['cara01matconvenio'] = $fila['cara01matconvenio'];
		$_REQUEST['cara01niveldigital'] = $fila['cara01niveldigital'];
		$_REQUEST['cara01nivellectura'] = $fila['cara01nivellectura'];
		$_REQUEST['cara01nivelrazona'] = $fila['cara01nivelrazona'];
		$_REQUEST['cara01nivelingles'] = $fila['cara01nivelingles'];
		$_REQUEST['cara01fechainicio'] = $fila['cara01fechainicio'];
		$_REQUEST['cara01idprograma'] = $fila['cara01idprograma'];
		$_REQUEST['cara01idescuela'] = $fila['cara01idescuela'];
		$_REQUEST['cara01fichabiolog'] = $fila['cara01fichabiolog'];
		$_REQUEST['cara01nivelbiolog'] = $fila['cara01nivelbiolog'];
		$_REQUEST['cara01fichafisica'] = $fila['cara01fichafisica'];
		$_REQUEST['cara01nivelfisica'] = $fila['cara01nivelfisica'];
		$_REQUEST['cara01fichaquimica'] = $fila['cara01fichaquimica'];
		$_REQUEST['cara01nivelquimica'] = $fila['cara01nivelquimica'];
		$_REQUEST['cara01tipocaracterizacion'] = $fila['cara01tipocaracterizacion'];
		$_REQUEST['cara01psico_puntaje'] = $fila['cara01psico_puntaje'];
		$bcargo = true;
		$_REQUEST['paso'] = 2;
		$_REQUEST['boculta2301'] = 0;
		if (!$bEstudiante) {
			$_REQUEST['boculta101'] = $fila['cara01fichaper'];
			$_REQUEST['boculta102'] = $fila['cara01fichafam'];
			$_REQUEST['boculta103'] = $fila['cara01fichaaca'];
			$_REQUEST['boculta104'] = $fila['cara01fichalab'];
			$_REQUEST['boculta105'] = $fila['cara01fichabien'];
			$_REQUEST['boculta106'] = $fila['cara01fichapsico'];
			$_REQUEST['boculta107'] = $fila['cara01fichadigital'];
			$_REQUEST['boculta108'] = $fila['cara01fichalectura'];
			$_REQUEST['boculta109'] = $fila['cara01ficharazona'];
			$_REQUEST['boculta110'] = $fila['cara01fichaingles'];
			$_REQUEST['boculta111'] = $fila['cara01fichabiolog'];
			$_REQUEST['boculta112'] = $fila['cara01fichafisica'];
			$_REQUEST['boculta113'] = $fila['cara01fichaquimica'];
		} else {
		}
		$bLimpiaHijos = true;
	} else {
		$_REQUEST['paso'] = 0;
	}
}
function html_2201Tablero($id)
{
	return '<div class="salto1px"></div>
	<label class="Label300">&nbsp;</label>
	<label class="Label130">
	<input id="cmdTablero' . $id . '" name="cmdTablero' . $id . '" type="button" value="Mis Cursos" class="BotonAzul" onclick="javascript:irtablero()" />
	</label>';
}
function f2301_NombrePuntaje($sCompetencia, $iValor, $ETI)
{
	$sValor = '';
	$dPorcentaje = 0;
	$sEstilo = 'secondary';
	$sNota = '';
	$iPuntajeMax = 0;
	switch ($sCompetencia) {
		case 'puntaje':
			if ($iValor >= 24 && $iValor <= 30) {
				$sValor = 'Bajo';
			} else {
				if ($iValor >= 17 && $iValor <= 23) {
					$sValor = 'Medio';
				} else {
					if ($iValor >= 10 && $iValor <= 16) {
						$sValor = 'Alto';
					} else {
						$sValor = 'Sin definir';
					}
				}
			}			
			break;
		case 'lectura':
			if ($iValor >= 0 && $iValor <= 40) {
				$sValor = 'Bajo';
				$sEstilo = 'danger';
				$sNota = $ETI[$sCompetencia.'_bajo'];
			} else {
				if ($iValor >= 50 && $iValor <= 90) {
					$sValor = 'Medio';
					$sEstilo = 'info';
					$sNota = $ETI[$sCompetencia.'_medio'];
				} else {
					if ($iValor >= 100 && $iValor <= 150) {
						$sValor = 'Alto';
						$sEstilo = 'success';
						$sNota = $ETI[$sCompetencia.'_alto'];
					} else {
						$sValor = 'Sin definir';
					}
				}
			}
			if ($iValor>=0) {
				$iPuntajeMax = 150;
				$dPorcentaje = round(($iValor / $iPuntajeMax)*100,2);
			}
			break;
		case 'digital':
		case 'ingles':
			if ($iValor >= 0 && $iValor <= 40) {
				$sValor = 'Bajo';
				$sEstilo = 'danger';
				$sNota = $ETI[$sCompetencia.'_bajo'];
			} else {
				if ($iValor >= 50 && $iValor <= 80) {
					$sValor = 'Medio';
					$sEstilo = 'info';
					$sNota = $ETI[$sCompetencia.'_medio'];
				} else {
					if ($iValor >= 90 && $iValor <= 120) {
						$sValor = 'Alto';
						$sEstilo = 'success';
						$sNota = $ETI[$sCompetencia.'_alto'];
					} else {
						$sValor = 'Sin definir';
					}
				}
			}
			if ($iValor>=0) {
				$iPuntajeMax = 120;
				$dPorcentaje = round(($iValor / $iPuntajeMax)*100,2);
			}
			break;
		case 'razona':
		case 'biolog':
		case 'fisica':
		case 'quimica':
			if ($iValor >= 0 && $iValor <= 30) {
				$sValor = 'Bajo';
				$sEstilo = 'danger';
				$sNota = $ETI[$sCompetencia.'_bajo'];
			} else {
				if ($iValor >= 40 && $iValor <= 60) {
					$sValor = 'Medio';
					$sEstilo = 'info';
					$sNota = $ETI[$sCompetencia.'_medio'];
				} else {
					if ($iValor >= 70 && $iValor <= 100) {
						$sValor = 'Alto';
						$sEstilo = 'success';
						$sNota = $ETI[$sCompetencia.'_alto'];
					} else {
						$sValor = 'Sin definir';
					}
				}
			}
			if ($iValor>0) {
				$iPuntajeMax = 100;
				$dPorcentaje = $iValor;
			}
			break;
	}
	return array($sValor, $sEstilo, $dPorcentaje, $sNota, $iPuntajeMax);
}
if ($bDebug) {
	$sDebug = $sDebug . fecha_microtiempo() . ' Corriendo el paso ' . $_REQUEST['paso'] . ' antes de guardar<br>';
}
//Verificar los datos de la matricula .. Febrero 24 de 2022
if ($_REQUEST['paso'] == 31) {
	$_REQUEST['paso'] = -1;
	$bMueveScroll = true;
	if ($_REQUEST['bperiodo'] == '') {
		$sError = 'No se ha definido el periodo a verificar';
	}
	if ($sError == '') {
		require 'lib2301consejero.php';
		list($iProcesados, $sError, $sDebugV) = f2301_VerificarMatricula($_REQUEST['bperiodo'], $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugV;
	}
	if ($sError == '') {
		$sError = 'Se ha Verificado los datos de matricula';
		if ($iProcesados > 0) {
			$sError = $sError . ', Encuestas ajustadas: ' . formato_numero($iProcesados) . '';
		}
		$iTipoError = 1;
	}
}
//limpiar la pantalla
if ($_REQUEST['paso'] == -1) {
	$_REQUEST['cara01idperaca'] = '';
	$_REQUEST['cara01idtercero'] = 0;
	$_REQUEST['cara01id'] = '';
	$_REQUEST['cara01idtercero_td'] = $APP->tipo_doc;
	$_REQUEST['cara01idtercero_doc'] = '';
	$_REQUEST['cara01completa'] = 'N';
	$_REQUEST['cara01fichaper'] = 0;
	$_REQUEST['cara01fichafam'] = -1;
	$_REQUEST['cara01fichaaca'] = -1;
	$_REQUEST['cara01fichalab'] = -1;
	$_REQUEST['cara01fichabien'] = -1;
	$_REQUEST['cara01fichapsico'] = -1;
	$_REQUEST['cara01fichadigital'] = -1;
	$_REQUEST['cara01fichalectura'] = -1;
	$_REQUEST['cara01ficharazona'] = -1;
	$_REQUEST['cara01fichaingles'] = -1;
	$_REQUEST['cara01fechaencuesta'] = ''; //fecha_hoy();
	$_REQUEST['cara01idzona'] = '';
	$_REQUEST['cara01idcead'] = '';
	$_REQUEST['cara01matconvenio'] = 'N';
	$_REQUEST['cara01niveldigital'] = '';
	$_REQUEST['cara01nivellectura'] = '';
	$_REQUEST['cara01nivelrazona'] = '';
	$_REQUEST['cara01nivelingles'] = '';
	$_REQUEST['cara01fechainicio'] = ''; //fecha_hoy();
	$_REQUEST['cara01idprograma'] = 0;
	$_REQUEST['cara01idescuela'] = 0;
	$_REQUEST['cara01fichabiolog'] = -1;
	$_REQUEST['cara01nivelbiolog'] = 0;
	$_REQUEST['cara01fichafisica'] = -1;
	$_REQUEST['cara01nivelfisica'] = 0;
	$_REQUEST['cara01fichaquimica'] = -1;
	$_REQUEST['cara01nivelquimica'] = 0;
	$_REQUEST['cara01tipocaracterizacion'] = 0;
	$_REQUEST['cara01psico_puntaje'] = 0;
	$_REQUEST['paso'] = 0;
}
if ($bLimpiaHijos) {
}
//AQUI SE DEBEN CARGAR TODOS LOS DATOS QUE LA FORMA NECESITE.
$bPuedeGuardar = false;
$bConEliminar = false;
$bHayImprimir = false;
$sScriptImprime = 'imprimelista()';
$sClaseBoton = 'btEnviarExcel';
$bAntiguo = false;
if ($_REQUEST['cara01tipocaracterizacion'] == 3) {
	$bAntiguo = true;
}
//Permisos adicionales
$seg_5 = 0;
$seg_6 = 0;
if (!$bEstudiante) {
	list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 6, $idTercero, $objDB);
	if ($bDevuelve) {
		$seg_6 = 1;
	}
}
if ($seg_6 == 1) {
}
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
//Crear los controles que requieran llamado a base de datos
$objCombos = new clsHtmlCombos();
$objTercero = new clsHtmlTercero();
list($cara01idtercero_rs, $_REQUEST['cara01idtercero'], $_REQUEST['cara01idtercero_td'], $_REQUEST['cara01idtercero_doc']) = html_tercero($_REQUEST['cara01idtercero_td'], $_REQUEST['cara01idtercero_doc'], $_REQUEST['cara01idtercero'], 0, $objDB);
if ((int)$_REQUEST['paso'] == 0) {
	$idTerceroFuncion = 0;
	if ($bEstudiante) {
		$idTerceroFuncion = $idTercero;
	}
	$html_cara01idperaca = f2301_HTMLComboV2_cara01idperaca($objDB, $objCombos, $_REQUEST['cara01idperaca'], $idTerceroFuncion);
} else {
	list($cara01idperaca_nombre, $sErrorDet) = tabla_campoxid('exte02per_aca', 'exte02nombre', 'exte02id', $_REQUEST['cara01idperaca'], '{' . $ETI['msg_sindato'] . '}', $objDB);
	$html_cara01idperaca = html_oculto('cara01idperaca', $_REQUEST['cara01idperaca'], $cara01idperaca_nombre);
}
//Alistar datos adicionales
$bPuedeAbrir = false;
if ($_REQUEST['paso'] != 0) {
	if ($_REQUEST['cara01completa'] == 'S') {
		//Definir las condiciones que permitirán abrir el registro.
		list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 17, $idTercero, $objDB);
		if ($bDevuelve) {
			$bPuedeAbrir = true;
		}
	}
}
$id_rpt = 0;
//$id_rpt=reportes_id(_Identificador_Tipo_Reporte_, $objDB);
if (false) {
	$objCombos->nuevo('csv_separa', $_REQUEST['csv_separa'], false);
	$objCombos->addItem(',', $ETI['msg_coma']);
	$objCombos->addItem(';', $ETI['msg_puntoycoma']);
	$csv_separa = '<label class="Label90">' . $ETI['msg_separador'] . '</label><label class="Label130">' . $objCombos->html('', $objDB) . '</label>';
} else {
	$csv_separa = '<input id="csv_separa" name="csv_separa" type="hidden" value="," />';
}
$iNumFormatosImprime = 0;
$iModeloReporte = 2301;
$html_iFormatoImprime = '<input id="iformatoimprime" name="iformatoimprime" type="hidden" value="0" />
';
if ($_REQUEST['paso'] > 0) {
	$bDevuelve = false;
	//list($bDevuelve, $sDebugP)=seg_revisa_permisoV3($iCodModulo, 5, $idTercero, $objDB);
	if ($bDevuelve) {
		$seg_5 = 1;
	}
}
if ($bEstudiante) {
	//2022 - 08 - 19 : Ver si tiene mas de una encuesta cargada y que pueda cambiar entre ellas

} else {
	//Cargar las tablas de datos
	$aParametros[0] = ''; //$_REQUEST['p1_2301'];
	$aParametros[100] = $idTercero;
	$aParametros[101] = $_REQUEST['paginaf2301'];
	$aParametros[102] = $_REQUEST['lppf2301'];
	$aParametros[103] = $_REQUEST['bdoc'];
	$aParametros[104] = $_REQUEST['bnombre'];
	$aParametros[105] = $_REQUEST['bperiodo'];
	$aParametros[106] = $_REQUEST['blistar'];
	$aParametros[107] = $_REQUEST['bescuela'];
	$aParametros[108] = $_REQUEST['bprograma'];
	$aParametros[109] = $_REQUEST['bzona'];
	$aParametros[110] = $_REQUEST['bcead'];
	$aParametros[111] = $_REQUEST['btipocara'];
	$aParametros[112] = $_REQUEST['bpoblacion'];
	$aParametros[113] = $_REQUEST['bconvenio'];
	list($sTabla2301, $sDebugTabla) = f2301_TablaDetalleV2($aParametros, $objDB, $bDebug);
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
$sDebug = $sDebug . $sDebugM;
$objDB->CerrarConexion();
//FORMA
$bBloqueTitulo = true;
switch ($iPiel) {
	case 2:
		require $APP->rutacomun . 'unad_forma2023.php';
		forma_InicioV4($xajax, $ETI['titulo_2301']);
		$bBloqueTitulo = false;
		break;
	default:
		require $APP->rutacomun . 'unad_forma_v2.php';
		forma_cabeceraV3($xajax, $ETI['titulo_2301']);
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
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.0.1/css/font-awesome.min.css">
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>css/botones_resultado.css?v=2">
<?php
switch ($iPiel) {
	case 2:
		$aRutas = array(
			array('./', 'CARACTERIZACION'),
			array('', $ETI['titulo_2301res'])
		);
		$iNumBoton = 0;
		$aBotones[$iNumBoton] = array('muestraayuda(' . $APP->idsistema . ', ' . $iCodModulo . ')', $ETI['bt_ayuda'], 'iHelp');
		$iNumBoton++;
		if ($bConEliminar) {
			$aBotones[$iNumBoton] = array('eliminadato()', $ETI['bt_eliminar'], 'iDelete');
			$iNumBoton++;
		}
		if ($bHayImprimir) {
			$aBotones[$iNumBoton] = array($sScriptImprime, $ETI['bt_imprimir'], 'iExcel');
			$iNumBoton++;
		}
		$aBotones[$iNumBoton] = array('limpiapagina()', $ETI['bt_limpiar'], 'iDocument');
		$iNumBoton++;
		if ($bPuedeGuardar) {
			$aBotones[$iNumBoton] = array('enviaguardar()', $ETI['bt_guardar'], 'iSaveFill');
			$iNumBoton++;
		}
		forma_cabeceraV4($aRutas, $aBotones, true, $bDebug);
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
			//if (illave==1){params[5]='FuncionCuandoNoEsta';}
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
			if (idcampo == 'cara01idtercero') {
				params[4] = 'RevisaLlave';
			}
			xajax_unad11_TraerXid(params);
		}
	}

	function imprimelista() {
		if (window.document.frmedita.seg_6.value == 1) {
			window.document.frmlista.consulta.value = window.document.frmedita.consulta_2301.value;
			window.document.frmlista.titulos.value = window.document.frmedita.titulos_2301.value;
			window.document.frmlista.nombrearchivo.value = 'Encuesta';
			window.document.frmlista.submit();
		} else {
			ModalMensaje("<?php echo $ERR['6']; ?>");
		}
	}

	function asignarvariables() {
		window.document.frmimpp.iformato94.value = window.document.frmedita.iformatoimprime.value;
		//window.document.frmimpp.v3.value=window.document.frmedita.bnombre.value;
		//window.document.frmimpp.v4.value=window.document.frmedita.bcodigo.value;
		//window.document.frmimpp.separa.value=window.document.frmedita.csv_separa.value.trim();
	}

	function imprimeexcel() {
		let sError = '';
		if (window.document.frmedita.seg_6.value != 1) {
			sError = "<?php echo $ERR['6']; ?>";
		}
		//if (sError==''){/*Agregar validaciones*/}
		if (sError == '') {
			asignarvariables();
			window.document.frmimpp.action = 'e2301.php';
			window.document.frmimpp.submit();
		} else {
			ModalMensaje(sError);
		}
	}

	function imprimep() {
		if (window.document.frmedita.seg_5.value == 1) {
			asignarvariables();
			window.document.frmimpp.action = 'p2301.php';
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

	function paginarf2301() {
		var params = new Array();
		params[99] = window.document.frmedita.debug.value;
		params[100] = <?php echo $idTercero; ?>;
		params[101] = window.document.frmedita.paginaf2301.value;
		params[102] = window.document.frmedita.lppf2301.value;
		params[103] = window.document.frmedita.bdoc.value;
		params[104] = window.document.frmedita.bnombre.value;
		params[105] = window.document.frmedita.bperiodo.value;
		params[106] = window.document.frmedita.blistar.value;
		params[107] = window.document.frmedita.bescuela.value;
		params[108] = window.document.frmedita.bprograma.value;
		params[109] = window.document.frmedita.bzona.value;
		params[110] = window.document.frmedita.bcead.value;
		params[111] = window.document.frmedita.btipocara.value;
		params[112] = window.document.frmedita.bpoblacion.value;
		params[113] = window.document.frmedita.bconvenio.value;
		//document.getElementById('div_f2301detalle').innerHTML='<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf2301" name="paginaf2301" type="hidden" value="'+params[101]+'" /><input id="lppf2301" name="lppf2301" type="hidden" value="'+params[102]+'" />';
		xajax_f2301_HtmlTabla(params);
	}

	function revfoco(objeto) {
		setTimeout(function() {
			objeto.focus();
		}, 10);
	}

	function cargaridf2301(llave1) {
		window.document.frmedita.cara01id.value = String(llave1);
		window.document.frmedita.paso.value = 3;
		window.document.frmedita.submit();
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
		document.getElementById("cara01idperaca").focus();
	}

	function buscarV2016(sCampo) {
		window.document.frmedita.iscroll.value = window.pageYOffset;
		expandesector(98);
		window.document.frmedita.scampobusca.value = sCampo;
		var params = new Array();
		params[1] = sCampo;
		//params[2]=window.document.frmedita.iagno.value;
		//params[3]=window.document.frmedita.itipo.value;
		xajax_f2301_Busquedas(params);
	}

	function retornacontrol() {
		expandesector(1);
		window.scrollTo(0, window.document.frmedita.iscroll.value);
	}

	function Devuelve(sValor) {
		var sCampo = window.document.frmedita.scampobusca.value;
		if (sCampo == 'cara01idtercero') {
			ter_traerxid('cara01idtercero', sValor);
		}
		if (sCampo == 'cara01idconfirmadesp') {
			ter_traerxid('cara01idconfirmadesp', sValor);
		}
		if (sCampo == 'cara01idconfirmacr') {
			ter_traerxid('cara01idconfirmacr', sValor);
		}
		if (sCampo == 'cara01idconfirmadisc') {
			ter_traerxid('cara01idconfirmadisc', sValor);
		}
		if (sCampo == 'cara01idconsejero') {
			ter_traerxid('cara01idconsejero', sValor);
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
			// var sMensaje='Lo que quiera decir.';
			//if (sCampo=='sNombreCampo'){sMensaje='Mensaje para otro campo.';}
			// divAyuda.innerHTML=sMensaje;
			divAyuda.style.display = 'block';
		}
	}

	function cierraDiv96(ref) {
		var sRetorna = window.document.frmedita.div96v2.value;
		if (ref == 0) {
			if (sRetorna != '') {
				window.document.frmedita.cara01discv2soporteorigen.value = window.document.frmedita.div96v1.value;
				window.document.frmedita.cara01discv2archivoorigen.value = sRetorna;
				verboton('beliminacara01discv2archivoorigen', 'block');
			}
			archivo_lnk(window.document.frmedita.cara01discv2soporteorigen.value, window.document.frmedita.cara01discv2archivoorigen.value, 'div_cara01discv2archivoorigen');
			paginarf0();
		}
		MensajeAlarmaV2('', 0);
		retornacontrol();
	}
</script>
<script type="text/x-mathjax-config">
	MathJax.Hub.Config({
    tex2jax: {inlineMath: [["$","$"],["\\(","\\)"]]}
  });
</script>
<script type="text/javascript" src="<?php echo $APP->rutacomun; ?>latex/MathJax.js?config=TeX-AMS_HTML-full"></script>
<?php
if ($bEstudiante) {
?>
<form id="frmtablero" name="frmtablero" method="post" action="<?php echo $sUrlTablero; ?>">
</form>
<?php
}
if ($_REQUEST['paso'] != 0) {
?>
<form id="frmimpp" name="frmimpp" method="post" action="p2301.php" target="_blank">
<input id="r" name="r" type="hidden" value="2301" />
<input id="id2301" name="id2301" type="hidden" value="<?php echo $_REQUEST['cara01id']; ?>" />
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
<?php
$sComplemento = '';
//if ($bDebug){$sComplemento='caracterizacion.php?debug=1&r=1';}
?>
<form id="frmedita" name="frmedita" method="post" action="<?php echo $sComplemento; ?>" autocomplete="off">
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
?>
<div class="titulos">
<div class="titulosD">
<input id="cmdAyuda" name="cmdAyuda" type="button" class="btUpAyuda" onclick="muestraayuda(<?php echo $APP->idsistema . ', ' . $iCodModulo; ?>);" title="<?php echo $ETI['bt_ayuda']; ?>" value="<?php echo $ETI['bt_ayuda']; ?>" />
</div>
<div class="titulosI">
<?php
echo '<h2>' . $ETI['titulo_2301'] . '</h2>';
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
?>
<input id="boculta2301" name="boculta2301" type="hidden" value="<?php echo $_REQUEST['boculta2301']; ?>" />
<?php
echo $sHTMLHistorial;
//Div para ocultar
$bconexpande = true;
if ($bEstudiante) {
$bconexpande = false;
}
if ($bconexpande) {
?>
<div class="ir_derecha" <?php echo $sAnchoExpandeContrae; ?>>
<label class="Label30">
<input id="btexpande2301" name="btexpande2301" type="button" value="Mostrar" class="btMiniExpandir" onclick="expandepanel(2301,'block',0);" title="<?php echo $ETI['bt_mostrar']; ?>" style="display:<?php if ($_REQUEST['boculta2301'] == 0) {
echo 'none';
} else {
echo 'block';
} ?>;" />
</label>
<label class="Label30">
<input id="btrecoge2301" name="btrecoge2301" type="button" value="Ocultar" class="btMiniRecoger" onclick="expandepanel(2301,'none',1);" title="<?php echo $ETI['bt_ocultar']; ?>" style="display:<?php if ($_REQUEST['boculta2301'] == 0) {
echo 'block';
} else {
echo 'none';
} ?>;" />
</label>
</div>
<div id="div_p2301" style="display:<?php if ($_REQUEST['boculta2301'] == 0) {
echo 'block';
} else {
echo 'none';
} ?>;">
<?php
}
//Mostrar formulario para editar
?>
<label class="Label130">
<?php
echo $ETI['cara01idperaca'];
?>
</label>
<label class="Label600">
<?php
echo $html_cara01idperaca;
?>
</label>
<div class="salto1px"></div>
<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo $ETI['cara01idtercero'];
?>
</label>
<div class="salto1px"></div>
<input id="cara01idtercero" name="cara01idtercero" type="hidden" value="<?php echo $_REQUEST['cara01idtercero']; ?>" />
<div id="div_cara01idtercero_llaves">
<?php
$bOculto = true;
if ($bEstudiante) {
	$bOculto = true;
} else {
	if ($_REQUEST['paso'] != 2) {
		$bOculto = false;
	}
}
echo html_DivTerceroV2('cara01idtercero', $_REQUEST['cara01idtercero_td'], $_REQUEST['cara01idtercero_doc'], $bOculto, 1, $ETI['ing_doc']);
?>
</div>
<div class="salto1px"></div>
<div id="div_cara01idtercero" class="L"><?php echo $cara01idtercero_rs; ?></div>
<div class="salto1px"></div>
</div>

<div class="GrupoCampos450">
<label class="Label60">
<?php
echo $ETI['cara01id'];
?>
</label>
<label class="Label60">
<?php
echo html_oculto('cara01id', $_REQUEST['cara01id']);
?>
</label>
<label class="Label90">
<?php
$et_cara01completa = $ETI['msg_abierto'];
$et_cara01fechaencuesta = '&nbsp;';
if ($_REQUEST['cara01completa'] == 'S') {
$et_cara01completa = $ETI['msg_cerrado'];
$et_cara01fechaencuesta = fecha_desdenumero($_REQUEST['cara01fechaencuesta']);
}
echo html_oculto('cara01completa', $_REQUEST['cara01completa'], $et_cara01completa);
?>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['cara01fechaencuesta'];
?>
</label>
<div class="Campo220">
<?php
echo html_oculto('cara01fechaencuesta', $_REQUEST['cara01fechaencuesta'], $et_cara01fechaencuesta);
?>
</div>
<div class="salto1px"></div>
</div>

<div class="salto1px"></div>
<?php
if ($bEstudiante) {
	if ($_REQUEST['paso'] == 2) {
		if ($_REQUEST['cara01completa'] == 'S') {
			$_REQUEST['bocultaResultados'] = 0;
?>
<?php
// Inicia Indicadores resultados
?>
<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo $ETI['titulo_2301'];
?>
</label>
<input id="bocultaResultados" name="bocultaResultados" type="hidden" value="<?php echo $_REQUEST['bocultaResultados']; ?>" />
<div class="ir_derecha" style="width:62px;">
<label class="Label30">
<input id="btexpandeResultados" name="btexpandeResultados" type="button" value="Expandir" class="btMiniExpandir" onclick="expandepanel('Resultados','block',0);" title="<?php echo $ETI['bt_mostrar']; ?>" style="display:<?php if ($_REQUEST['bocultaResultados'] == 0) {
echo 'none';
} else {
echo 'block';
} ?>;" />
</label>
<label class="Label30">
<input id="btrecogeResultados" name="btrecogeResultados" type="button" value="Recoger" class="btMiniRecoger" onclick="expandepanel('Resultados','none',1);" title="<?php echo $ETI['bt_ocultar']; ?>" style="display:<?php if ($_REQUEST['bocultaResultados'] == 0) {
echo 'block';
} else {
echo 'none';
} ?>;" />
</label>
</div>
<div class="salto1px"></div>


<!-- //! TODO: Aqui comienza -->


<div id="div_pResultados" style="display:<?php if ($_REQUEST['bocultaResultados'] == 0) {echo 'block';} else {echo 'none';} ?>;">
<?php
$bInvitaPAPC = false;
if (false) {
?>
<label class="Label220">
<?php
echo $ETI['cara01psico_puntaje'];
?>
</label>
<label class="Label60">
<div id="div_cara01psico_puntaje">
<?php
list($sValor, $sEstilo, $dPorcentaje, $sNota, $iPuntajeMax)=f2301_NombrePuntaje('puntaje', $_REQUEST['cara01psico_puntaje'], $ETI);
echo html_oculto('cara01psico_puntaje', $_REQUEST['cara01psico_puntaje'], $sValor);
?>
</div>
</label>
<?php
}else{
?>
<input id="cara01psico_puntaje" name="cara01psico_puntaje" type="hidden" value="<?php echo $_REQUEST['cara01psico_puntaje']; ?>" />
<?php
}
?>
<div class="salto1px"></div>
<div class="GrupoCamposAyuda">
<b>
<?php
echo $ETI['cara01fichapsico'];
?>
</b>
<?php
if ($_REQUEST['cara01psico_puntaje'] < 17) {
	echo $ETI['msg_psico_alto'];
} else {
	if ($_REQUEST['cara01psico_puntaje'] < 24) {
		echo $ETI['msg_psico_medio'];
	} else {
		echo $ETI['msg_psico_bajo'];
	}
}
?>
<div class="salto1px"></div>
</div>
<?php
if ($_REQUEST['cara01fichadigital'] != -1){
$bInvitaPAPC = true;
?>
<div class="salto1px"></div>



<div class="GrupoCampos450">
<div class="btn-group dropright">
<div style="min-width:400px">
<label class="Label220">
<?php
echo $ETI['cara01fichadigital'];
?>
</label>
<label class="Label60">
<?php
echo $ETI['msg_nivel'];
?>
</label>
<label class="Label60">
<?php
list($sValor, $sEstilo, $dPorcentaje, $sNota, $iPuntajeMax)=f2301_NombrePuntaje('digital', $_REQUEST['cara01niveldigital'], $ETI);
echo html_oculto('cara01niveldigital', $_REQUEST['cara01niveldigital'], $sValor);
?>
</label>


<div class="salto1px"></div>
<div class="progress">
<div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="<?php echo $dPorcentaje; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $dPorcentaje; ?>%"><?php echo $dPorcentaje; ?>%</div>
</div>
</div>
<button type="button" class="btn btn-danger dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
<div class="dropdown-menu bg-<?php echo $sEstilo; ?>">
<div class="card text-white bg-<?php echo $sEstilo; ?> mb-3" style="max-width: 25rem;">
<div class="card-header"><?php echo $ETI['msg_puntaje'].': '.$_REQUEST['cara01niveldigital'].'/'.$iPuntajeMax; ?></div>
<div class="card-body">
<h5 class="card-title"><?php echo $ETI['msg_nivel'].': <b>'.$sValor.'</b>'; ?></h5>
<p class="card-text"><?php echo $sNota; ?></p>
</div>
</div>
</div>
</div>
<div class="salto5px"></div>
</div>



<?php
} else {
?>
<input id="cara01niveldigital" name="cara01niveldigital" type="hidden" value="<?php echo $_REQUEST['cara01niveldigital']; ?>" />
<?php
}
if ($_REQUEST['cara01fichalectura'] != -1){
$bInvitaPAPC = true;
?>

<div class="GrupoCampos450">
<div class="btn-group dropright">
<div style="min-width:400px">
<label class="Label220">
<?php
echo $ETI['cara01fichalectura'];
?>
</label>
<label class="Label60">
<?php
echo $ETI['msg_nivel'];
?>
</label>
<label class="Label60">
<?php
list($sValor, $sEstilo, $dPorcentaje, $sNota, $iPuntajeMax)=f2301_NombrePuntaje('lectura', $_REQUEST['cara01nivellectura'], $ETI);
echo html_oculto('cara01nivellectura', $_REQUEST['cara01nivellectura'], $sValor);
?>
</label>

<div class="salto1px"></div>
<div class="progress">
<div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="<?php echo $dPorcentaje; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $dPorcentaje; ?>%"><?php echo $dPorcentaje; ?>%</div>
</div>
</div>
<button type="button" class="btn btn-danger dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
<div class="dropdown-menu bg-<?php echo $sEstilo; ?>">
<div class="card text-white bg-<?php echo $sEstilo; ?> mb-3" style="max-width: 25rem;">
<div class="card-header"><?php echo $ETI['msg_puntaje'].': '.$_REQUEST['cara01nivellectura'].'/'.$iPuntajeMax; ?></div>
<div class="card-body">
<h5 class="card-title"><?php echo $ETI['msg_nivel'].': <b>'.$sValor.'</b>'; ?></h5>
<p class="card-text"><?php echo $sNota; ?></p>
</div>
</div>
</div>
</div>
<div class="salto5px"></div>
</div>

<?php
} else {
?>
<input id="cara01nivellectura" name="cara01nivellectura" type="hidden" value="<?php echo $_REQUEST['cara01nivellectura']; ?>" />
<?php
}
if ($_REQUEST['cara01ficharazona'] != -1){
$bInvitaPAPC = true;
?>
<div class="salto1px"></div>

<div class="GrupoCampos450">
<div class="btn-group dropright">
<div style="min-width:400px">
<label class="Label220">
<?php
echo $ETI['cara01ficharazona'];
?>
</label>
<label class="Label60">
<?php
echo $ETI['msg_nivel'];
?>
</label>
<label class="Label60">
<?php
list($sValor, $sEstilo, $dPorcentaje, $sNota, $iPuntajeMax)=f2301_NombrePuntaje('razona', $_REQUEST['cara01nivelrazona'], $ETI);
echo html_oculto('cara01nivelrazona', $_REQUEST['cara01nivelrazona'], $sValor);
?>
</label>

<div class="salto1px"></div>
<div class="progress">
<div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="<?php echo $dPorcentaje; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $dPorcentaje; ?>%"><?php echo $dPorcentaje; ?>%</div>
</div>
</div>
<button type="button" class="btn btn-danger dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
<div class="dropdown-menu bg-<?php echo $sEstilo; ?>">
<div class="card text-white bg-<?php echo $sEstilo; ?> mb-3" style="max-width: 25rem;">
<div class="card-header"><?php echo $ETI['msg_puntaje'].': '.$_REQUEST['cara01nivelrazona'].'/'.$iPuntajeMax; ?></div>
<div class="card-body">
<h5 class="card-title"><?php echo $ETI['msg_nivel'].': <b>'.$sValor.'</b>'; ?></h5>
<p class="card-text"><?php echo $sNota; ?></p>
</div>
</div>
</div>
</div>
<div class="salto5px"></div>
</div>

<?php
} else {
?>
<input id="cara01nivelrazona" name="cara01nivelrazona" type="hidden" value="<?php echo $_REQUEST['cara01nivelrazona']; ?>" />
<?php
}
if ($_REQUEST['cara01fichaingles'] != -1){
$bInvitaPAPC = true;
?>

<div class="GrupoCampos450">
<div class="btn-group dropright">
<div style="min-width:400px">
<label class="Label220">
<?php
echo $ETI['cara01fichaingles'];
?>
</label>
<label class="Label60">
<?php
echo $ETI['msg_nivel'];
?>
</label>
<label class="Label60">
<?php
list($sValor, $sEstilo, $dPorcentaje, $sNota, $iPuntajeMax)=f2301_NombrePuntaje('ingles', $_REQUEST['cara01nivelingles'], $ETI);
echo html_oculto('cara01nivelingles', $_REQUEST['cara01nivelingles'], $sValor);
?>
</label>

<div class="salto1px"></div>
<div class="progress">
<div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="<?php echo $dPorcentaje; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $dPorcentaje; ?>%"><?php echo $dPorcentaje; ?>%</div>
</div>
</div>
<button type="button" class="btn btn-danger dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
<div class="dropdown-menu bg-<?php echo $sEstilo; ?>">
<div class="card text-white bg-<?php echo $sEstilo; ?> mb-3" style="max-width: 25rem;">
<div class="card-header"><?php echo $ETI['msg_puntaje'].': '.$_REQUEST['cara01nivelingles'].'/'.$iPuntajeMax; ?></div>
<div class="card-body">
<h5 class="card-title"><?php echo $ETI['msg_nivel'].': <b>'.$sValor.'</b>'; ?></h5>
<p class="card-text"><?php echo $sNota; ?></p>
</div>
</div>
</div>
</div>
<div class="salto5px"></div>
</div>

<?php
} else {
?>
<input id="cara01nivelingles" name="cara01nivelingles" type="hidden" value="<?php echo $_REQUEST['cara01nivelingles']; ?>" />
<?php
}
if ($_REQUEST['cara01fichabiolog'] != -1){
$bInvitaPAPC = true;
?>
<div class="salto1px"></div>

<div class="GrupoCampos450">
<div class="btn-group dropright">
<div style="min-width:400px">
<label class="Label220">
<?php
echo $ETI['cara01fichabiolog'];
?>
</label>
<label class="Label60">
<?php
echo $ETI['msg_nivel'];
?>
</label>
<label class="Label60">
<?php
list($sValor, $sEstilo, $dPorcentaje, $sNota, $iPuntajeMax)=f2301_NombrePuntaje('biolog', $_REQUEST['cara01nivelbiolog'], $ETI);
echo html_oculto('cara01nivelbiolog', $_REQUEST['cara01nivelbiolog'], $sValor);
?>
</label>

<div class="salto1px"></div>
<div class="progress">
<div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="<?php echo $dPorcentaje; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $dPorcentaje; ?>%"><?php echo $dPorcentaje; ?>%</div>
</div>
</div>
<button type="button" class="btn btn-danger dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
<div class="dropdown-menu bg-<?php echo $sEstilo; ?>">
<div class="card text-white bg-<?php echo $sEstilo; ?> mb-3" style="max-width: 25rem;">
<div class="card-header"><?php echo $ETI['msg_puntaje'].': '.$_REQUEST['cara01nivelbiolog'].'/'.$iPuntajeMax; ?></div>
<div class="card-body">
<h5 class="card-title"><?php echo $ETI['msg_nivel'].': <b>'.$sValor.'</b>'; ?></h5>
<p class="card-text"><?php echo $sNota; ?></p>
</div>
</div>
</div>
</div>
<div class="salto5px"></div>
</div>

<?php
} else {
?>
<input id="cara01nivelbiolog" name="cara01nivelbiolog" type="hidden" value="<?php echo $_REQUEST['cara01nivelbiolog']; ?>" />
<?php
}
if ($_REQUEST['cara01fichafisica'] != -1){
$bInvitaPAPC = true;
?>

<div class="GrupoCampos450">
<div class="btn-group dropright">
<div style="min-width:400px">
<label class="Label220">
<?php
echo $ETI['cara01fichafisica'];
?>
</label>
<label class="Label60">
<?php
echo $ETI['msg_nivel'];
?>
</label>
<label class="Label60">
<?php
list($sValor, $sEstilo, $dPorcentaje, $sNota, $iPuntajeMax)=f2301_NombrePuntaje('fisica', $_REQUEST['cara01nivelfisica'], $ETI);
echo html_oculto('cara01nivelfisica', $_REQUEST['cara01nivelfisica'], $sValor);
?>
</label>

<div class="salto1px"></div>
<div class="progress">
<div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="<?php echo $dPorcentaje; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $dPorcentaje; ?>%"><?php echo $dPorcentaje; ?>%</div>
</div>
</div>
<button type="button" class="btn btn-danger dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
<div class="dropdown-menu bg-<?php echo $sEstilo; ?>">
<div class="card text-white bg-<?php echo $sEstilo; ?> mb-3" style="max-width: 25rem;">
<div class="card-header"><?php echo $ETI['msg_puntaje'].': '.$_REQUEST['cara01nivelfisica'].'/'.$iPuntajeMax; ?></div>
<div class="card-body">
<h5 class="card-title"><?php echo $ETI['msg_nivel'].': <b>'.$sValor.'</b>'; ?></h5>
<p class="card-text"><?php echo $sNota; ?></p>
</div>
</div>
</div>
</div>
<div class="salto5px"></div>
</div>

<?php
} else {
?>
<input id="cara01nivelfisica" name="cara01nivelfisica" type="hidden" value="<?php echo $_REQUEST['cara01nivelfisica']; ?>" />
<?php
}
if ($_REQUEST['cara01fichaquimica'] != -1){
$bInvitaPAPC = true;
?>
<div class="salto1px"></div>

<div class="GrupoCampos450">
<div class="btn-group dropright">
<div style="min-width:400px">
<label class="Label220">
<?php
echo $ETI['cara01fichaquimica'];
?>
</label>
<label class="Label60">
<?php
echo $ETI['msg_nivel'];
?>
</label>
<label class="Label60">
<?php
list($sValor, $sEstilo, $dPorcentaje, $sNota, $iPuntajeMax)=f2301_NombrePuntaje('quimica', $_REQUEST['cara01nivelquimica'], $ETI);
echo html_oculto('cara01nivelquimica', $_REQUEST['cara01nivelquimica'], $sValor);
?>
</label>

<div class="salto1px"></div>
<div class="progress">
<div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="<?php echo $dPorcentaje; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $dPorcentaje; ?>%"><?php echo $dPorcentaje; ?>%</div>
</div>
</div>
<button type="button" class="btn btn-danger dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
<div class="dropdown-menu bg-<?php echo $sEstilo; ?>">
<div class="card text-white bg-<?php echo $sEstilo; ?> mb-3" style="max-width: 25rem;">
<div class="card-header"><?php echo $ETI['msg_puntaje'].': '.$_REQUEST['cara01nivelquimica'].'/'.$iPuntajeMax; ?></div>
<div class="card-body">
<h5 class="card-title"><?php echo $ETI['msg_nivel'].': <b>'.$sValor.'</b>'; ?></h5>
<p class="card-text"><?php echo $sNota; ?></p>
</div>
</div>
</div>
</div>
<div class="salto5px"></div>
</div>

<?php
} else {
?>
<input id="cara01nivelquimica" name="cara01nivelquimica" type="hidden" value="<?php echo $_REQUEST['cara01nivelquimica']; ?>" />
<?php
}
?>
<?php
if ($bInvitaPAPC) {
?>
<div class="salto1px"></div>
<div class="GrupoCampos700">
<?php
if (false) {
?>
<a href="https://sivisae.unad.edu.co/sivisae/pages/sivisae_acceso_PAPC_cronograma.php" target="_blank">
<?php
}
?>
<img src="https://i.ibb.co/z5x9X40/invita-talleres-papc.png" alt="<?php echo $ETI['bt_invita_papc']; ?>" title="<?php echo $ETI['bt_invita_papc']; ?>" style="width:100%;" />
<?php
if (false) {
?>
</a>
<?php
}
?>
</div>
<?php
}
?>
<div class="salto5px"></div>
</div>
<?php
// Fin indicadores resultados
?>
</div>
<?php
// -- Termina Grupo campos Resultados
?>
<?php 
} else {
?>
<div class="salto5px"></div>
<div class="GrupoCamposAyuda">
<div class="MarquesinaMedia">
<?php echo $ETI['msg_encuestaabierta']; ?>
</div>
</div>
<?php
}
}
}
?>

<?php
// Termina el div_areatrabajo y DIV_areaform
?>

</div><!-- /div_areatrabajo -->
</div><!-- /DIV_areaform -->
</div><!-- /DIV_Sector1 -->


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
<input id="titulo_2301" name="titulo_2301" type="hidden" value="<?php echo $ETI['titulo_2301']; ?>" />
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
echo '<h2>' . $ETI['titulo_2301'] . '</h2>';
?>
</div>
</div>
<div id="cargaForm">
<div id="area">
<div id="div_97params"></div>
<div class="salto1px"></div>
<div id="div_97tabla"></div>
</div><!-- /div_area -->
</div><!-- /DIV_cargaForm -->
</div><!-- /DIV_Sector97 -->


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
echo '<h2>' . $ETI['titulo_2301'] . '</h2>';
?>
</div>
</div>
<?php
}
?>
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
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>js/jquery.autocomplete.css" type="text/css" />
<script language="javascript" src="<?php echo $APP->rutacomun; ?>js/jquery.autocomplete.js"></script>
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>js/chosen.css" type="text/css" />
<script language="javascript" src="<?php echo $APP->rutacomun; ?>js/chosen.jquery.js"></script>
<script language="javascript">
$().ready(function() {
<?php
if ($_REQUEST['paso'] == 0) {
?>
$("#cara01idperaca").chosen();
<?php
}
?>
$("#bperiodo").chosen();
$("#bconvenio").chosen();
$(".progress").css("height","30px");
<?php
switch ($iPiel) {
	case 2:
		break;
	default:
?>
	$("html").css("font-size","8px");
<?php
		break;
}
?>
});
</script>
<script language="javascript" src="ac_2301.js"></script>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>unad_todas.js?ver=8"></script>
<?php
forma_piedepagina();

