<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2016 - 2024 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 3.0.15 miércoles, 14 de mayo de 2025
*/
/** Archivo saiuencuesta.php.
 * Modulo 3074 saiu74encuesta.
 * @author Omar Augusto Bautista Mora - omar.bautista@unad.edu.co
 * @param debug = 1  (Opcional), bandera para indicar si se generan datos de depuración
 * @date miércoles, 14 de mayo de 2025
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
} else {
	$_REQUEST['debug'] = 0;
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
require $APP->rutacomun . 'unad_forma_campus.php';
require $APP->rutacomun . 'libcomp.php';
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
if (!$bEnSesion) {
	header('Location:index.php');
	die();
}
$iConsecutivoMenu = 1;
$iMinVerDB = 8560;
$iCodModulo = 3074;
$iCodModuloConsulta = $iCodModulo;
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
$mensajes_3074 = 'lg/lg_3074_' . $_SESSION['unad_idioma'] . '.php';
if (!file_exists($mensajes_3074)) {
	$mensajes_3074 = 'lg/lg_3074_es.php';
}
require $mensajes_todas;
require $mensajes_3074;
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
$sTituloModulo = $ETI['titulo_3074'];
$sUrlTablero = 'tablero.php';
if (isset($APP->urltablero) != 0) {
	if (file_exists($APP->urltablero)) {
		$sUrlTablero = $APP->urltablero;
	}
}
if ($APP->idsistema == 7) {
	$sUrlTablero = 'index.php';
}
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
		list($et_menu, $sDebugM) = html_menuCampusV3($objDB, $bDebugMenu, $idTercero);
		$sDebug = $sDebug . $sDebugM;
	}
	$objDB->CerrarConexion();
	forma_cabecera($xajax, $sTituloModulo);
	echo $et_menu;
	forma_mitad();
	$objForma = new clsHtmlForma($iPiel);
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
		header('Location:noticia.php?ret=saiuencuesta.php');
		die();
	}
}
$seg_1707 = 0;
$bDevuelve = false;
list($bDevuelve, $sDebugP) = seg_revisa_permisoV3(17, 1707, $idTercero, $objDB, $bDebug);
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
			//Si el otro usuario tambien tiene el permiso... no se debe permitir.
			list($bDevuelve, $sDebugP) = seg_revisa_permisoV3(17, 1707, $idTercero, $objDB, $bDebug);
			if ($bDevuelve) {
				//Reversamos el permiso
				$bOtroUsuario = false;
				$idTercero = $_SESSION['unad_id_tercero'];
				$sError = 'No es permitido consultar al usuario ' . $_REQUEST['deb_doc'] . '';
			} else {
				$sDebug = $sDebug . fecha_microtiempo() . ' Se verifica la ventana de trabajo para el usuario ' . $fila['unad11razonsocial'] . '.<br>';
			}
			//Termina de revisar si se tiene que revocar el permiso.
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
	$_REQUEST['paso'] = -1;
	if ($audita[1]) {
		seg_auditaingreso($iCodModulo, $_SESSION['unad_id_tercero'], $objDB);
	}
}
// -- 3074 saiu74encuesta
require 'lib3074.php';
$xajax = new xajax();
$xajax->configure('javascript URI', $APP->rutacomun . 'xajax/');
$xajax->register(XAJAX_FUNCTION, 'sesion_abandona_V2');
$xajax->register(XAJAX_FUNCTION, 'sesion_mantenerV4');
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
if (isset($_REQUEST['paginaf3074']) == 0) {
	$_REQUEST['paginaf3074'] = 1;
}
if (isset($_REQUEST['lppf3074']) == 0) {
	$_REQUEST['lppf3074'] = 20;
}
if (isset($_REQUEST['boculta3074']) == 0) {
	$_REQUEST['boculta3074'] = 0;
}
// -- Inicializar variables de datos.
if (isset($_REQUEST['saiu74codmodulo']) == 0) {
	$_REQUEST['saiu74codmodulo'] = '';
}
if (isset($_REQUEST['saiu74idreg']) == 0) {
	$_REQUEST['saiu74idreg'] = '';
}
if (isset($_REQUEST['saiu74id']) == 0) {
	$_REQUEST['saiu74id'] = '';
}
if (isset($_REQUEST['saiu74agno']) == 0) {
	$_REQUEST['saiu74agno'] = '';
}
if (isset($_REQUEST['saiu74acepta']) == 0) {
	$_REQUEST['saiu74acepta'] = 0;
}
if (isset($_REQUEST['saiu74fecharespuesta']) == 0) {
	$_REQUEST['saiu74fecharespuesta'] = '';
	//$_REQUEST['saiu74fecharespuesta'] = $iHoy;
}
if (isset($_REQUEST['saiu74preg1']) == 0) {
	$_REQUEST['saiu74preg1'] = '';
}
if (isset($_REQUEST['saiu74preg2']) == 0) {
	$_REQUEST['saiu74preg2'] = '';
}
if (isset($_REQUEST['saiu74preg3']) == 0) {
	$_REQUEST['saiu74preg3'] = '';
}
if (isset($_REQUEST['saiu74preg4']) == 0) {
	$_REQUEST['saiu74preg4'] = '';
}
if (isset($_REQUEST['saiu74preg5']) == 0) {
	$_REQUEST['saiu74preg5'] = '';
}
if (isset($_REQUEST['saiu74preg6']) == 0) {
	$_REQUEST['saiu74preg6'] = '';
}
if (isset($_REQUEST['saiu74comentario']) == 0) {
	$_REQUEST['saiu74comentario'] = '';
}
$_REQUEST['saiu74codmodulo'] = numeros_validar($_REQUEST['saiu74codmodulo']);
$_REQUEST['saiu74idreg'] = numeros_validar($_REQUEST['saiu74idreg']);
$_REQUEST['saiu74id'] = numeros_validar($_REQUEST['saiu74id']);
$_REQUEST['saiu74agno'] = numeros_validar($_REQUEST['saiu74agno']);
$_REQUEST['saiu74acepta'] = numeros_validar($_REQUEST['saiu74acepta']);
$_REQUEST['saiu74fecharespuesta'] = numeros_validar($_REQUEST['saiu74fecharespuesta']);
$_REQUEST['saiu74preg1'] = numeros_validar($_REQUEST['saiu74preg1']);
$_REQUEST['saiu74preg2'] = numeros_validar($_REQUEST['saiu74preg2']);
$_REQUEST['saiu74preg3'] = numeros_validar($_REQUEST['saiu74preg3']);
$_REQUEST['saiu74preg4'] = numeros_validar($_REQUEST['saiu74preg4']);
$_REQUEST['saiu74preg5'] = numeros_validar($_REQUEST['saiu74preg5']);
$_REQUEST['saiu74preg6'] = numeros_validar($_REQUEST['saiu74preg6']);
$_REQUEST['saiu74comentario'] = cadena_Validar($_REQUEST['saiu74comentario']);
// Espacio para inicializar otras variables
if (isset($_REQUEST['bnombre']) == 0) {
	$_REQUEST['bnombre'] = '';
}
//if (isset($_REQUEST['blistar'])==0){$_REQUEST['blistar']='';}
//Cerrar la encuesta.
$aPendientes = array();
if ($_REQUEST['paso'] == 2) {
	list($_REQUEST, $aPendientes, $sErrorG, $iTipoError, $sDebugG) = f3074_GuardarEncuesta($_REQUEST, $objDB, $bDebug);
	$sError = $sError . $sErrorG;
	$sDebug = $sDebug . $sDebugG;
}
//limpiar la pantalla
if ($_REQUEST['paso'] == -1) {
	$_REQUEST['paso'] = 0;
	$_REQUEST['saiu74codmodulo'] = '';
	$_REQUEST['saiu74idreg'] = '';
	$_REQUEST['saiu74id'] = '';
	$_REQUEST['saiu74agno'] = '';
	$_REQUEST['saiu74acepta'] = 0;
	$_REQUEST['saiu74fecharespuesta'] = '';
	$_REQUEST['saiu74preg1'] = '';
	$_REQUEST['saiu74preg2'] = '';
	$_REQUEST['saiu74preg3'] = '';
	$_REQUEST['saiu74preg4'] = '';
	$_REQUEST['saiu74preg5'] = '';
	$_REQUEST['saiu74preg6'] = '';
	$_REQUEST['saiu74comentario'] = '';
	//Revisar que encuestas debe hacer.... crearlas si es necesario
	// f3074_CargarEncuestas($idTercero, $objDB);
}
if ($bLimpiaHijos) {
}
//AQUI SE DEBEN CARGAR TODOS LOS DATOS QUE LA FORMA NECESITE.
$bPuedeGuardar = true;
$bConEliminar = false;
$bAbierta = false;
$html_preguntas = '';
$saiu74codmodulo = '';
$saiu74idreg = '';
$saiu74agno = '';
$saiu74acepta = 0;
if ($_REQUEST['paso'] == 0) {
	$sURL = '';
	if (isset($_REQUEST['u']) != 0) {
		$sURL = url_decode_simple($_REQUEST['u']);
		$saiu74acepta = 1;
	} else if (isset($_REQUEST['n']) != 0) {
		$sURL = url_decode_simple($_REQUEST['n']);
	}
	if ($sURL != '') {
		$aURL = explode('|', $sURL);
		if (count($aURL) == 3) {
			$saiu74codmodulo = $aURL[0];
			$saiu74idreg = $aURL[1];
			$saiu74agno = $aURL[2];
			list($bAbierta, $sErrorC, $sDebugC) = f3074_BuscarEncuesta($saiu74codmodulo, $saiu74idreg, $saiu74agno, $objDB, $bDebug);
			$sError = $sError . $sErrorC;
			$sDebug = $sDebug . $sDebugC;
		}
	}
	if ($bAbierta) {
		$_REQUEST['saiu74codmodulo'] = $saiu74codmodulo;
		$_REQUEST['saiu74idreg'] = $saiu74idreg;
		$_REQUEST['saiu74agno'] = $saiu74agno;
		$_REQUEST['saiu74acepta'] = $saiu74acepta;
		if ($saiu74acepta == 0) {
			list($_REQUEST, $aPendientes, $sErrorG, $iTipoError, $sDebugG) = f3074_GuardarEncuesta($_REQUEST, $objDB, $bDebug);
			$sError = $sError . $sErrorG;
			$sDebug = $sDebug . $sDebugG;
		} else {
			$bResaltarPendientes = false;
			$iPreguntas = 6;
			$iComentario = $iPreguntas + 1;
			if (count($aPendientes) > 0) {
				$bResaltarPendientes = true;
			}
			$html_preguntas = $html_preguntas . '<div class="container-encuesta">';
			$html_preguntas = $html_preguntas . '<div class="encuesta-form">';

			for ($iPregunta = 1; $iPregunta <= $iPreguntas; $iPregunta++) {
				$sNombrePreg = 'saiu74preg' . $iPregunta;
				$sPregunta = '';
				$sValor = $_REQUEST[$sNombrePreg];
				$sValores = implode('|', $aValores);
				$sEtiquetas = implode('|', $aEtiquetas);
				$sPregunta = $sPregunta . '<div class="group-radio">';
				$sPregunta = $sPregunta . html_RadioV2($sNombrePreg, $sValor, $sValores, $sEtiquetas, 'marcaropcion(' . $iPregunta . ')');
				$sPregunta = $sPregunta . '</div>';
				$sClassRespondida = '';
				if ($bResaltarPendientes) {
					if (in_array($iPregunta, $aPendientes)) {
						$sClassRespondida = ' error';
					} else {
						$sClassRespondida = ' fill';
					}
				}
				$html_preguntas = $html_preguntas . '<div id="val_' . $iPregunta . '" class="container-pregunta' . $sClassRespondida . '">';
				$html_preguntas = $html_preguntas . '<div class="pregunta">';
				$html_preguntas = $html_preguntas . '<p>' . $ETI[$sNombrePreg] . '</p>';
				$html_preguntas = $html_preguntas . $sPregunta;
				$html_preguntas = $html_preguntas . '</div></div>';
			}
			$sPregunta = '<input id="saiu74comentario" name="saiu74comentario" type="text" value="' . $_REQUEST['saiu74comentario'] . '" style="width:98%;" placeholder="' . $ETI['msg_comentario'] . '"/>';
			$html_preguntas = $html_preguntas . '<div id="val_' . $iComentario . '" class="container-pregunta">';
			$html_preguntas = $html_preguntas . '<div class="pregunta">';
			$html_preguntas = $html_preguntas . '<p>' . $ETI['saiu74comentario'] . '</p>';
			$html_preguntas = $html_preguntas . $sPregunta;
			$html_preguntas = $html_preguntas . '</div></div>';
			$objForma = new clsHtmlForma($iPiel);
			$html_preguntas = $html_preguntas . $objForma->htmlBotonSolo('cmdTermina', 'botonAprobado', 'terminar()', 'Terminar encuesta');
			$html_preguntas = $html_preguntas . '</div></div>';
		}
	}
}
//Permisos adicionales
$seg_5 = 0;
$seg_6 = 0;
$seg_8 = 0;
//DATOS PARA COMPLETAR EL FORMULARIO
$iAgnoIni = 2000;
$iAgno = fecha_agno();
$iAgnoFin = $iAgno + 5;
$sNombreUsuario = '';
//Crear los controles que requieran llamado a base de datos
$objCombos = new clsHtmlCombos();
$objForma = new clsHtmlForma($iPiel);
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
// Fin de cargar las tablas de datos
list($et_menu, $sDebugM) = html_menuCampusV3($objDB, $bDebugMenu, $idTercero);
$sDebug = $sDebug . $sDebugM;
$objDB->CerrarConexion();
//FORMA
forma_cabecera($xajax, $sTituloModulo);
echo $et_menu;
// Pasar botones
$iNumBoton = 0;
$aBotones[$iNumBoton] = array('limpiapagina()', $ETI['bt_limpiar'], 'btMiniLimpiar');
$iNumBoton++;
$aBotones[$iNumBoton] = array('muestraayuda(' . $APP->idsistema . ', ' . $iCodModulo . ')', $ETI['bt_ayuda'], 'btMiniAyuda');
$iNumBoton++;
forma_mitad($objForma, true, $sTituloModulo, $aBotones);
// Ahora pintar el titulo del modulo
?>
<script language="javascript">
	function mueveencuesta() {
		window.document.frmedita.paso.value = 21;
		window.document.frmedita.submit();
	}

	function expandesector(codigo) {
		document.getElementById('div_sector1').style.display = 'none';
		document.getElementById('div_sector2').style.display = 'none';
		document.getElementById('div_sector95').style.display = 'none';
		document.getElementById('div_sector96').style.display = 'none';
		document.getElementById('div_sector98').style.display = 'none';
		document.getElementById('div_sector' + codigo).style.display = 'block';
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

	function mantener_sesion() {
		xajax_sesion_mantenerV4();
	}
	setInterval('xajax_sesion_abandona_V2();', 60000);

	function marcaropcion(idRpta) {
		if (idRpta == '') {
			idRpta = 0;
		}
		if (idRpta > 0) {
			document.getElementById('val_' + idRpta).className = 'container-pregunta fill';
		}
	}

	function terminar() {
		ModalMensajeV2('<?php echo $ETI['msg_termina_encuesta']; ?>', () => {
			expandesector(98);
			window.document.frmedita.paso.value=2;
			window.document.frmedita.submit();
		});
	}
</script>
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
<input id="icodmodulo" name="icodmodulo" type="hidden" value="<?php echo $iCodModulo; ?>" />
<input id="seg_5" name="seg_5" type="hidden" value="<?php echo $seg_5; ?>" />
<input id="seg_6" name="seg_6" type="hidden" value="<?php echo $seg_6; ?>" />
<input id="saiu74codmodulo" name="saiu74codmodulo" type="hidden" value="<?php echo $_REQUEST['saiu74codmodulo']; ?>" />
<input id="saiu74idreg" name="saiu74idreg" type="hidden" value="<?php echo $_REQUEST['saiu74idreg']; ?>" />
<input id="saiu74agno" name="saiu74agno" type="hidden" value="<?php echo $_REQUEST['saiu74agno']; ?>" />
<input id="saiu74acepta" name="saiu74acepta" type="hidden" value="<?php echo $_REQUEST['saiu74acepta']; ?>" />
<div id="div_sector1">
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
<label class="Label30">&nbsp;</label>
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
$bConExpande = false;
//Mostrar formulario para editar
if ($html_preguntas == '') {
	$sMsg = $ETI['msg_noencuesta'] . ' <a class="lnkresalte" href="' . $sUrlTablero . '">' . $ETI['msg_noencuesta_link'] . '</a>';
	echo html_Alerta($sMsg);
} else {
	echo html_Alerta($ETI['msg_justifica']);
?>
<div class="salto1px"></div>
<div id="div_respuestas">
<?php
echo $html_preguntas;
?>
</div>
<div class="salto1px"></div>
<?php
}
//Mostrar el contenido de la tabla
?>
<?php
// Termina el div_areatrabajo y DIV_areaform
?>
</div>
</div>
</div>


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
<div class="areaform">
<div class="areatrabajo">
</div>
</div>
<?php
// Termina el div_sector2
?>
</div>


<div id="div_sector95" style="display:none">
<div class="areaform">
<div id="div_95cuerpo"></div>
</div>
</div>


<div id="div_sector96" style="display:none">
<input id="div96v1" name="div96v1" type="hidden" value="" />
<input id="div96v2" name="div96v2" type="hidden" value="" />
<input id="div96v3" name="div96v3" type="hidden" value="" />
<input id="div96campo" name="div96campo" type="hidden" value="" />
<input id="div96llave" name="div96llave" type="hidden" value="" />
<input id="titulo_1926" name="titulo_1926" type="hidden" value="<?php echo $sTituloModulo; ?>" />
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
<div class="areaform">
<div id="div_96cuerpo"></div>
</div>
</div>


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
echo '<h2>' . $sTituloModulo . '</h2>';
?>
</div>
</div>
<?php
}
?>
<div class="areaform">
<div class="areatrabajo">
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
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>css/encuesta.css" type="text/css" />
<script language="javascript" src="<?php echo $APP->rutacomun; ?>unad_todas2024v2.js"></script>
<?php
forma_piedepagina();
