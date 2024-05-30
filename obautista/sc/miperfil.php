<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2016 - 2023 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.19.7c viernes, 09 de febrero de 2018
--- Modelo Versión 2.21.0 martes, 17 de abril de 2018
--- Modelo Versión 2.22.3 miércoles, 1 de agosto de 2018
--- Modelo Versión 2.24.1 jueves, 30 de enero de 2020
--- Modelo Versión 2.25.4 lunes, 10 de agosto de 2020 - Se agregan banderas para cambio de idioma.
--- Modelo Versión 2.25.10c martes, 3 de febrero de 2021
--- Modelo Versión 2.28.2 domingo, 29 de mayo de 2022
--- Modelo Versión 2.28.2 martes, 23 de agosto de 2022 - Autorizacion de contacto
--- Modelo Versión 2.29.2 lunes, 13 de febrero de 2023 - Se hace obligatori completar la autorización de contacto.
--- Modelo Versión 3.0.4b miércoles, 14 de febrero de 2024
*/
/** Archivo miperfil.php.
 * Modulo 17 unad11terceros.
 * @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
 * @param debug=1 (Opcional), bandera para indicar si se generan datos de depuración
 * @date jueves, 4 de febrero de 2021
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
$bBotonDerecho = true;
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
require $APP->rutacomun . 'libaurea.php';
require $APP->rutacomun . 'unad_login.php';
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
$iMinVerDB = 7117;
$iCodModulo = 17;
$iCodModuloConsulta = $iCodModulo;
$audita[1] = false;
$bAcceder = true;
$iDiasVentanaClave = 40;
$bCerrado = false;
$bTipoGeneral = true;
if ($APP->idsistema == 0) {
	$bTipoGeneral = false;
}
// -- Se cargan los archivos de idioma
$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
if (!file_exists($mensajes_todas)) {
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
}
$mensajes_17 = $APP->rutacomun . 'lg/lg_17_' . $_SESSION['unad_idioma'] . '.php';
if (!file_exists($mensajes_17)) {
	$mensajes_17 = $APP->rutacomun . 'lg/lg_17_es.php';
}
require $mensajes_todas;
require $mensajes_17;
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
if ($bTipoGeneral) {
	$sTituloModulo = $ETI['titulo_17perfil'];
} else {
	$sTituloModulo = $ETI['titulo_perfil'];
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
	list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModuloConsulta, 1, $_SESSION['unad_id_tercero'], $objDB);
	if (!$bDevuelve) {
		$bCerrado = true;
		$sMsgCierre = '<div class="MarquesinaGrande">No cuenta con permiso para acceder a este modulo [' . $iCodModuloConsulta . '].</div>';
	}
}
if ($bCerrado) {
	if ($bCargaMenu) {
		switch ($iPiel) {
			case 2:
				list($et_menu, $sDebugM) = html_Menu2023($APP->idsistema, $objDB, $iPiel, $bDebugMenu, $idTercero);
				break;
			default:
				list($et_menu, $sDebugM) = html_menuV2($APP->idsistema, $objDB, $iPiel, $bDebugMenu, $idTercero);
				break;
		}
	}
	$objDB->CerrarConexion();
	switch ($iPiel) {
		case 2:
			require $APP->rutacomun . 'unad_forma2023.php';
			forma_InicioV4($xajax, $sTituloModulo);
			$aRutas = array(
				array('./', $sTituloApp),
				array('./' . $sPaginaModulo, $sGrupoModulo),
				array('', $sTituloModulo)
			);
			$iNumBoton = 0;
			$aBotones[$iNumBoton] = array('muestraayuda(' . $APP->idsistema . ', ' . $iCodModulo . ')', $ETI['bt_ayuda'], 'iHelp');
			$iNumBoton++;
			forma_cabeceraV4b($aRutas, $aBotones, true, 1);
			echo $et_menu;
			forma_mitad($idTercero);
			break;
		default:
			require $APP->rutacomun . 'unad_forma_v2_2024.php';
			forma_cabeceraV3($xajax, $sTituloModulo);
			echo $et_menu;
			forma_mitad();
			break;
	}
	$objForma = new clsHtmlForma($iPiel);
	if ($bBloqueTitulo) {
		$objForma->addBoton('cmdAyuda98', 'btSupAyuda', 'muestraayuda(' . $iCodModulo . ');', $ETI['bt_ayuda']);
		echo $objForma->htmlTitulo($sTituloModulo, $iCodModulo);
	}
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
		header('Location:noticia.php?ret=miperfil.php');
		die();
	}
}
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
		if ($objDB->nf($tabla) == 0) {
			//Intentamos importar el usuario
			unad11_importar_V2($_REQUEST['deb_doc'], '', $objDB);
			$tabla = $objDB->ejecutasql($sSQL);
		}
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			$idTercero = $fila['unad11id'];
			$bOtroUsuario = true;
			//Si el otro usuario tambien tiene el permiso... no se debe permitir.
			$bDevuelve = false;
			//list($bDevuelve, $sDebugP)=seg_revisa_permisoV3($iCodModulo, 1707, $idTercero, $objDB, $bDebug);
			if ($bDevuelve) {
				//Reversamos el permiso
				$bOtroUsuario = false;
				$idTercero = $_SESSION['unad_id_tercero'];
				$sError = 'No es permitido consultar al usuario ' . $_REQUEST['deb_doc'] . '';
			} else {
				if ($bDebug) {
					$sDebug = $sDebug . fecha_microtiempo() . ' Se verifica la ventana de trabajo para el usuario ' . $fila['unad11razonsocial'] . '.<br>';
				}
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
}
if (isset($_REQUEST['modo']) == 0) {
	$_REQUEST['modo'] = 0;
}
if ($_REQUEST['paso'] == 26) {
	$_REQUEST['paso'] = 0;
	if (isset($_REQUEST['idioma']) == 0) {
		$_REQUEST['idioma'] = '';
	}
	switch ($_REQUEST['idioma']) {
		case 'es':
		case 'en':
		case 'pt':
			$_SESSION['unad_idioma'] = $_REQUEST['idioma'];
			$sSQL = 'UPDATE unad11terceros SET unad11idioma="' . $_REQUEST['idioma'] . '" WHERE unad11id=' . $_SESSION['unad_id_tercero'] . '';
			$result = $objDB->ejecutasql($sSQL);
			// -- Se recargan los archivos de idioma
			$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
			if (!file_exists($mensajes_todas)) {
				$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
			}
			$mensajes_17 = $APP->rutacomun . 'lg/lg_17_' . $_SESSION['unad_idioma'] . '.php';
			if (!file_exists($mensajes_17)) {
				$mensajes_17 = $APP->rutacomun . 'lg/lg_17_es.php';
			}
			require $mensajes_todas;
			require $mensajes_17;
			break;
	}
}
$sRutaLibs = './';
if ($bTipoGeneral){
	$sRutaLibs = $APP->rutacomun;
}
$mensajes_1011 = $APP->rutacomun . 'lg/lg_1011_' . $_SESSION['unad_idioma'] . '.php';
if (!file_exists($mensajes_1011)) {
	$mensajes_1011 = $APP->rutacomun . 'lg/lg_1011_es.php';
}
require $mensajes_1011;
if ($bTipoGeneral){
	$mensajes_107 = $APP->rutacomun . 'lg/lg_107_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_107)) {
		$mensajes_107 = $APP->rutacomun . 'lg/lg_107_es.php';
	}
	require $mensajes_107;
}
require $APP->rutacomun . 'lib17.php';
require $APP->rutacomun . 'lib17contacto.php';
if ($bTipoGeneral){
	// -- 107 Perfiles
	require $APP->rutacomun . 'lib107.php';
}
$xajax = new xajax();
$xajax->configure('javascript URI', $APP->rutacomun . 'xajax/');
$xajax->register(XAJAX_FUNCTION, 'f17_Combounad11idcead');
$xajax->register(XAJAX_FUNCTION, 'f17_Combounad11idprograma');
$xajax->register(XAJAX_FUNCTION, 'f17_Combounad11deptodoc');
$xajax->register(XAJAX_FUNCTION, 'f17_Combounad11ciudaddoc');
$xajax->register(XAJAX_FUNCTION, 'sesion_abandona_V2');
$xajax->register(XAJAX_FUNCTION, 'sesion_mantenerV4');
$xajax->register(XAJAX_FUNCTION, 'usuario_OpcionGuardar');
if ($bTipoGeneral){
	$xajax->register(XAJAX_FUNCTION, 'f107_HtmlTablaPerfil');
}
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
if (isset($_REQUEST['boculta111']) == 0) {
	$_REQUEST['boculta111'] = 0;
}
if ($bTipoGeneral){
	if (isset($_REQUEST['paginaf107']) == 0) {
		$_REQUEST['paginaf107'] = 1;
	}
	if (isset($_REQUEST['lppf107']) == 0) {
		$_REQUEST['lppf107'] = 20;
	}
}
// -- Inicializar variables de datos.
if (isset($_REQUEST['unad11tipodoc']) == 0) {
	$_REQUEST['unad11tipodoc'] = $APP->tipo_doc;
}
if (isset($_REQUEST['unad11doc']) == 0) {
	$_REQUEST['unad11doc'] = '';
}
if (isset($_REQUEST['unad11pais']) == 0) {
	$_REQUEST['unad11pais'] = $_SESSION['unad_pais'];
}
if (isset($_REQUEST['unad11usuario']) == 0) {
	$_REQUEST['unad11usuario'] = '';
}
if (isset($_REQUEST['unad11nombre1']) == 0) {
	$_REQUEST['unad11nombre1'] = '';
}
if (isset($_REQUEST['unad11nombre2']) == 0) {
	$_REQUEST['unad11nombre2'] = '';
}
if (isset($_REQUEST['unad11apellido1']) == 0) {
	$_REQUEST['unad11apellido1'] = '';
}
if (isset($_REQUEST['unad11apellido2']) == 0) {
	$_REQUEST['unad11apellido2'] = '';
}
if (isset($_REQUEST['unad11genero']) == 0) {
	$_REQUEST['unad11genero'] = '';
}
if (isset($_REQUEST['unad11fechanace']) == 0) {
	$_REQUEST['unad11fechanace'] = '';
}
if (isset($_REQUEST['unad11rh']) == 0) {
	$_REQUEST['unad11rh'] = '';
}
if (isset($_REQUEST['unad11ecivil']) == 0) {
	$_REQUEST['unad11ecivil'] = '';
}
if (isset($_REQUEST['unad11direccion']) == 0) {
	$_REQUEST['unad11direccion'] = '';
}
if (isset($_REQUEST['unad11telefono']) == 0) {
	$_REQUEST['unad11telefono'] = '';
}
if (isset($_REQUEST['unad11correo']) == 0) {
	$_REQUEST['unad11correo'] = '';
}
if (isset($_REQUEST['unad11deptodoc']) == 0) {
	$_REQUEST['unad11deptodoc'] = '';
}
if (isset($_REQUEST['unad11ciudaddoc']) == 0) {
	$_REQUEST['unad11ciudaddoc'] = '';
}
if (isset($_REQUEST['unad11aceptanotificacion']) == 0) {
	$_REQUEST['unad11aceptanotificacion'] = 'P';
}
if (isset($_REQUEST['unad11correonotifica']) == 0) {
	$_REQUEST['unad11correonotifica'] = '';
}
if (isset($_REQUEST['unad11correoinstitucional']) == 0) {
	$_REQUEST['unad11correoinstitucional'] = '';
}
if (isset($_REQUEST['unad11skype']) == 0) {
	$_REQUEST['unad11skype'] = '';
}
if (isset($_REQUEST['unad11mostrarcelular']) == 0) {
	$_REQUEST['unad11mostrarcelular'] = '';
}
if (isset($_REQUEST['unad11mostrarcorreo']) == 0) {
	$_REQUEST['unad11mostrarcorreo'] = '';
}
if (isset($_REQUEST['unad11mostrarskype']) == 0) {
	$_REQUEST['unad11mostrarskype'] = '';
}
if (isset($_REQUEST['unad11fechaconfmail']) == 0) {
	$_REQUEST['unad11fechaconfmail'] = 0;
}
if (isset($_REQUEST['unad11rolunad']) == 0) {
	$_REQUEST['unad11rolunad'] = '';
}
if (isset($_REQUEST['unad11exluirdobleaut']) == 0) {
	$_REQUEST['unad11exluirdobleaut'] = 'N';
}
if (isset($_REQUEST['unad11idzona']) == 0) {
	$_REQUEST['unad11idzona'] = '';
}
if (isset($_REQUEST['unad11idcead']) == 0) {
	$_REQUEST['unad11idcead'] = '';
}
if (isset($_REQUEST['unad11idescuela']) == 0) {
	$_REQUEST['unad11idescuela'] = '';
}
if (isset($_REQUEST['unad11idprograma']) == 0) {
	$_REQUEST['unad11idprograma'] = '';
}
if (isset($_REQUEST['unad11presentacion']) == 0) {
	$_REQUEST['unad11presentacion'] = '';
}
if (isset($_REQUEST['unad11fechaactualiza']) == 0) {
	$_REQUEST['unad11fechaactualiza'] = 0;
}
if (isset($_REQUEST['unad11correonotificanuevo']) == 0) {
	$_REQUEST['unad11correonotificanuevo'] = '';
}
if (isset($_REQUEST['unad11debeactualizarclave']) == 0) {
	$_REQUEST['unad11debeactualizarclave'] = '';
}
if (isset($_REQUEST['unad11fechadoc']) == 0) {
	$_REQUEST['unad11fechadoc'] = '';
}
if (isset($_REQUEST['unad01estrato']) == 0) {
	$_REQUEST['unad01estrato'] = '';
}
if (isset($_REQUEST['unad01zonares']) == 0) {
	$_REQUEST['unad01zonares'] = '';
}
if (isset($_REQUEST['unad01autoriza_tel']) == 0) {
	$_REQUEST['unad01autoriza_tel'] = '-1';
}
if (isset($_REQUEST['unad01autoriza_bol']) == 0) {
	$_REQUEST['unad01autoriza_bol'] = '-1';
}
if (isset($_REQUEST['unad01oir_ruv']) == 0) {
	$_REQUEST['unad01oir_ruv'] = '-1';
}
if (isset($_REQUEST['unad01autoriza_mat']) == 0) {
	$_REQUEST['unad01autoriza_mat'] = '-1';
}
if (isset($_REQUEST['unad01autoriza_bien']) == 0) {
	$_REQUEST['unad01autoriza_bien'] = '-1';
}
if (isset($_REQUEST['salto']) == 0) {
	$_REQUEST['salto'] = '';
}
$_REQUEST['unad11direccion'] = cadena_Validar($_REQUEST['unad11direccion'], true);
$sPermitidos = $ETI['permitidos'];
// Espacio para inicializar otras variables
$bMensajeEntrada = false;
if (isset($_REQUEST['m']) != 0) {
	if ($_REQUEST['m'] == 'b') {
		$bMensajeEntrada = true;
	}
}
$bDatosPersonales = true;
if ($_REQUEST['paso'] == -1) {
	$_REQUEST['paso'] = 0;
	$sSQL = 'SELECT * 
	FROM unad11terceros 
	WHERE unad11id=' . $idTercero . '';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$_REQUEST['unad11tipodoc'] = $fila['unad11tipodoc'];
		$_REQUEST['unad11doc'] = $fila['unad11doc'];
		$_REQUEST['unad11pais'] = $fila['unad11pais'];
		$_REQUEST['unad11usuario'] = $fila['unad11usuario'];
		$_REQUEST['unad11nombre1'] = $fila['unad11nombre1'];
		$_REQUEST['unad11nombre2'] = $fila['unad11nombre2'];
		$_REQUEST['unad11apellido1'] = $fila['unad11apellido1'];
		$_REQUEST['unad11apellido2'] = $fila['unad11apellido2'];
		$_REQUEST['unad11genero'] = $fila['unad11genero'];
		$_REQUEST['unad11fechanace'] = $fila['unad11fechanace'];
		$_REQUEST['unad11rh'] = $fila['unad11rh'];
		$_REQUEST['unad11ecivil'] = $fila['unad11ecivil'];
		$_REQUEST['unad11direccion'] = $fila['unad11direccion'];
		$_REQUEST['unad11telefono'] = $fila['unad11telefono'];
		$_REQUEST['unad11correo'] = $fila['unad11correo'];
		$_REQUEST['unad11deptodoc'] = $fila['unad11deptodoc'];
		$_REQUEST['unad11ciudaddoc'] = $fila['unad11ciudaddoc'];
		$_REQUEST['unad11aceptanotificacion'] = $fila['unad11aceptanotificacion'];
		$_REQUEST['unad11correonotifica'] = $fila['unad11correonotifica'];
		$_REQUEST['unad11correoinstitucional'] = $fila['unad11correoinstitucional'];
		$_REQUEST['unad11skype'] = $fila['unad11skype'];
		$_REQUEST['unad11mostrarcelular'] = $fila['unad11mostrarcelular'];
		$_REQUEST['unad11mostrarcorreo'] = $fila['unad11mostrarcorreo'];
		$_REQUEST['unad11mostrarskype'] = $fila['unad11mostrarskype'];
		$_REQUEST['unad11fechaconfmail'] = $fila['unad11fechaconfmail'];
		$_REQUEST['unad11rolunad'] = $fila['unad11rolunad'];
		$_REQUEST['unad11exluirdobleaut'] = $fila['unad11exluirdobleaut'];
		$_REQUEST['unad11idzona'] = $fila['unad11idzona'];
		$_REQUEST['unad11idcead'] = $fila['unad11idcead'];
		$_REQUEST['unad11idescuela'] = $fila['unad11idescuela'];
		$_REQUEST['unad11idprograma'] = $fila['unad11idprograma'];
		$_REQUEST['unad11presentacion'] = str_replace('<br />', '', $fila['unad11presentacion']);
		$_REQUEST['unad11presentacion'] = str_replace('<br/>', '', $_REQUEST['unad11presentacion']);
		$_REQUEST['unad11presentacion'] = str_replace('<br>', '', $_REQUEST['unad11presentacion']);
		$_REQUEST['unad11fechaactualiza'] = $fila['unad11fechaactualiza'];
		$_REQUEST['unad11correonotificanuevo'] = $fila['unad11correonotificanuevo'];
		$_REQUEST['unad11debeactualizarclave'] = $fila['unad11debeactualizarclave'];
		$_REQUEST['unad11fechadoc'] = $fila['unad11fechadoc'];
		$_REQUEST['unad01estrato'] = $fila['unad01estrato'];
		$_REQUEST['unad01zonares'] = $fila['unad01zonares'];
		$_REQUEST['unad01autoriza_tel'] = $fila['unad01autoriza_tel'];
		$_REQUEST['unad01autoriza_bol'] = $fila['unad01autoriza_bol'];
		$_REQUEST['unad01oir_ruv'] = $fila['unad01oir_ruv'];
		$_REQUEST['unad01autoriza_mat'] = $fila['unad01autoriza_mat'];
		$_REQUEST['unad01autoriza_bien'] = $fila['unad01autoriza_bien'];
		$bcargo = true;
		$_REQUEST['paso'] = 2;
		if ($bDebug) {
			$sDebug = $sDebug . '' . fecha_microtiempo() . ' Termina de traer los datos del estudiante<br>';
		}
	}
}
/*
$sSQL = 'SELECT 1 FROM unad07usuarios WHERE unad07idtercero=' . $idTercero . ' AND unad07vigente="S" LIMIT 0,1';
$tabla = $objDB->ejecutasql($sSQL);
if ($objDB->nf($tabla) > 0){
	$bDatosPersonales = true;
} else {
	if (trim($_REQUEST['unad11correo']) != '') {
		//Si se inscribió directamente no puede modificar el correo hasta que no confirme el documento.
		$sSQL = 'SELECT 1 FROM aure72preinscripcion WHERE aure72correo="' . $_REQUEST['unad11correo'] . '"';
		$tabla72 = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla72) > 0) {
			$bDatosPersonales = true;
		}
	}
}
*/
$sInfo = '';
$bEnviarMailConfirma = false;
$bRevisaModo = false;
if ($_REQUEST['paso'] == 22) {
	$_REQUEST['paso'] = 21;
	$bEnviarMailConfirma = true;
}
if ($_REQUEST['paso'] == 21) {
	$_REQUEST['paso'] = 0;
	$_REQUEST['unad11nombre1'] = strtoupper(htmlspecialchars(trim($_REQUEST['unad11nombre1'])));
	$_REQUEST['unad11nombre2'] = strtoupper(htmlspecialchars(trim($_REQUEST['unad11nombre2'])));
	$_REQUEST['unad11apellido1'] = strtoupper(htmlspecialchars(trim($_REQUEST['unad11apellido1'])));
	$_REQUEST['unad11apellido2'] = strtoupper(htmlspecialchars(trim($_REQUEST['unad11apellido2'])));
	$_REQUEST['unad11correo'] = htmlspecialchars(trim($_REQUEST['unad11correo']));
	$_REQUEST['unad11telefono'] = numeros_validar($_REQUEST['unad11telefono']);
	$_REQUEST['unad11skype'] = htmlspecialchars($_REQUEST['unad11skype']);
	$sSepara = ', ';
	if ($_REQUEST['unad11correo'] != '') {
		list($sError, $sDebugC) = AUREA_ValidaCuentaCorreo($_REQUEST['unad11correo'], $objDB, $bDebug);
		if ($sError != '') {
			$sError = $ERR['unad11correo'] . '<br>' . $sError . '';
		}
	}
	if ($sError == '') {
		if ($_REQUEST['unad11mostrarskype'] == 'S') {
			if ($_REQUEST['unad11skype'] == '') {
				$sError = $ERR['unad11skype'];
			}
		}
	}
	if ($sError == '') {
		if ($_REQUEST['unad11mostrarcelular'] == 'S') {
			if ($_REQUEST['unad11telefono'] == '') {
				$sError = $ERR['unad11telefono'];
			}
		}
	}
	if (!fecha_NumValido($_REQUEST['unad11fechadoc'])) {
		//$sError = $ERR['unad11fechadoc'] . $sSepara . $sError;
	}
	if (!fecha_esvalida($_REQUEST['unad11fechanace'])) {
		$sError = $ERR['unad11fechanace'] . $sSepara . $sError;
	}
	if ($_REQUEST['unad11apellido1'] == '') {
		$sError = $ERR['unad11apellido1'] . $sSepara . $sError;
	}
	if ($_REQUEST['unad11nombre1'] == '') {
		$sError = $ERR['unad11nombre1'] . $sSepara . $sError;
	}
	$bErrorAutorizaciones = false;
	if ($bDatosPersonales){
		// Esto es temporal.
		if ($_REQUEST['unad01autoriza_tel'] == '-1') {
			$bErrorAutorizaciones = true;
		}
		if ($_REQUEST['unad01autoriza_mat'] == '-1') {
			$bErrorAutorizaciones = true;
		}
		if ($_REQUEST['unad01autoriza_bien'] == '-1') {
			$bErrorAutorizaciones = true;
		}
		if ($_REQUEST['unad01autoriza_bol'] == '-1') {
			$bErrorAutorizaciones = true;
		}
	// Termina lo temporal.
	}
	if ($bErrorAutorizaciones){
		$sError = $ERR['unad01autoriza'] . $sSepara . $sError;
	}
	if ($sError == '') {
		if ($idTercero != $_SESSION['unad_id_tercero']) {
			$sError = 'No fue posible continuar debido a un error de validaci&oacute;n de datos, se ha informado al administrador del sistema.';
		}
	}
	if ($sError == '') {
		//Si el campo unad11presentacion permite html quite la linea htmlspecialchars para el campo y habilite la siguiente linea:
		$_REQUEST['unad11nombre1'] = cadena_letrasynumeros($_REQUEST['unad11nombre1'], ' &');
		$_REQUEST['unad11nombre2'] = cadena_letrasynumeros($_REQUEST['unad11nombre2'], ' &');
		$_REQUEST['unad11apellido1'] = cadena_letrasynumeros($_REQUEST['unad11apellido1'], ' &');
		$_REQUEST['unad11apellido2'] = cadena_letrasynumeros($_REQUEST['unad11apellido2'], ' &');
		$_REQUEST['unad11direccion'] = cadena_letrasynumeros($_REQUEST['unad11direccion'], ' ._-#°&()');
		$unad11presentacion = cadena_Reemplazar($_REQUEST['unad11presentacion'], "'", '');
		$unad11presentacion = nl2br(addslashes($unad11presentacion));
		//$unad11presentacion=str_replace('"', '\"', $DATA['unad11presentacion']);
		$unad11razonsocial = trim(trim($_REQUEST['unad11nombre1']) . ' ' . trim($_REQUEST['unad11nombre2']) . ' ' . trim($_REQUEST['unad11apellido1']) . ' ' . trim($_REQUEST['unad11apellido2']));
		$scampo[1] = 'unad11genero';
		$scampo[2] = 'unad11fechanace';
		$scampo[3] = 'unad11direccion';
		$scampo[4] = 'unad11correo';
		$scampo[5] = 'unad11mostrarcorreo';
		$scampo[6] = 'unad11skype';
		$scampo[7] = 'unad11mostrarskype';
		$scampo[8] = 'unad11telefono';
		$scampo[9] = 'unad11mostrarcelular';
		$scampo[10] = 'unad11presentacion';
		$scampo[11] = 'unad11pais';
		$scampo[12] = 'unad11deptodoc';
		$scampo[13] = 'unad11ciudaddoc';
		$scampo[14] = 'unad11nombre1';
		$scampo[15] = 'unad11nombre2';
		$scampo[16] = 'unad11apellido1';
		$scampo[17] = 'unad11apellido2';
		$scampo[18] = 'unad11razonsocial';
		$scampo[19] = 'unad11rh';
		$scampo[20] = 'unad11ecivil';
		$scampo[21] = 'unad11fechadoc';
		$scampo[22] = 'unad01estrato';
		$scampo[23] = 'unad01zonares';
		$scampo[24] = 'unad01autoriza_tel';
		$scampo[25] = 'unad01autoriza_bol';
		$scampo[26] = 'unad01oir_ruv';
		$scampo[27] = 'unad01autoriza_mat';
		$scampo[28] = 'unad01autoriza_bien';
		$sdato[1] = $_REQUEST['unad11genero'];
		$sdato[2] = $_REQUEST['unad11fechanace'];
		$sdato[3] = $_REQUEST['unad11direccion'];
		$sdato[4] = $_REQUEST['unad11correo'];
		$sdato[5] = $_REQUEST['unad11mostrarcorreo'];
		$sdato[6] = $_REQUEST['unad11skype'];
		$sdato[7] = $_REQUEST['unad11mostrarskype'];
		$sdato[8] = $_REQUEST['unad11telefono'];
		$sdato[9] = $_REQUEST['unad11mostrarcelular'];
		$sdato[10] = $unad11presentacion;
		$sdato[11] = $_REQUEST['unad11pais'];
		$sdato[12] = $_REQUEST['unad11deptodoc'];
		$sdato[13] = $_REQUEST['unad11ciudaddoc'];
		$sdato[14] = $_REQUEST['unad11nombre1'];
		$sdato[15] = $_REQUEST['unad11nombre2'];
		$sdato[16] = $_REQUEST['unad11apellido1'];
		$sdato[17] = $_REQUEST['unad11apellido2'];
		$sdato[18] = $unad11razonsocial;
		$sdato[19] = $_REQUEST['unad11rh'];
		$sdato[20] = $_REQUEST['unad11ecivil'];
		$sdato[21] = $_REQUEST['unad11fechadoc'];
		$sdato[22] = $_REQUEST['unad01estrato'];
		$sdato[23] = $_REQUEST['unad01zonares'];
		$sdato[24] = $_REQUEST['unad01autoriza_tel'];
		$sdato[25] = $_REQUEST['unad01autoriza_bol'];
		$sdato[26] = $_REQUEST['unad01oir_ruv'];
		$sdato[27] = $_REQUEST['unad01autoriza_mat'];
		$sdato[28] = $_REQUEST['unad01autoriza_bien'];
		$iNumCamposMod = 28;
		$sWhere = 'unad11id=' . $idTercero . '';
		$sSQL = 'SELECT * FROM unad11terceros WHERE ' . $sWhere;
		$sdatos = '';
		$bPasa = false;
		$result = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($result) > 0) {
			$filabase = $objDB->sf($result);
			$bsepara = false;
			for ($k = 1; $k <= $iNumCamposMod; $k++) {
				if ($filabase[$scampo[$k]] != $sdato[$k]) {
					if ($sdatos != '') {
						$sdatos = $sdatos . ', ';
					}
					$sdatos = $sdatos . $scampo[$k] . '="' . $sdato[$k] . '"';
					$bPasa = true;
				}
			}
		}
		$sHoy = fecha_hoy();
		if ($bPasa) {
			$sdetalle = $sdatos;
			$unad11modf = fecha_DiaMod();
			$unad11modm = fecha_MinutoMod();
			$sdatos = $sdatos . ', unad11modf=' . $unad11modf . ', unad11modm=' . $unad11modm . ', unad11fechaactualiza=' . $unad11modf . ', unad11fechatablero="' . $sHoy . '"';
			$sSQL = 'UPDATE unad11terceros SET ' . $sdatos . ' WHERE unad11id=' . $idTercero . ';';
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . ' Actualizando: ' . $sSQL . ' <br>';
			}
			$result = $objDB->ejecutasql($sSQL);
			if ($result == false) {
				$sError = 'Se ha presentado un error al intentar actualizar, si el problema persiste por favor informe al administrador del sistema.';
			}
			if ($sError == '') {
				list($sErrorM, $sDebugM) = AUREA_ActualizarPerfilMoodle($idTercero, $objDB, $bDebug);
				$sDebug = $sDebug . $sDebugM;
				seg_auditar(111, $_SESSION['unad_id_tercero'], 3, $idTercero, $sdetalle, $objDB);
				$sInfo = '<b>Se han guardado las modificaciones</b>';
				$sError = 'Se han guardado las modificaciones';
				$iTipoError = 1;
				if ($_REQUEST['modo'] == 1) {
					header('Location:servicios.php?w=1');
					die();
				}
			}
		} else {
			//El usuario simplmente dio un guardar, solo guardamos la confirmacion...
			$sSQL = 'UPDATE unad11terceros SET unad11fechatablero="' . $sHoy . '" WHERE unad11id=' . $idTercero . ';';
			$result = $objDB->ejecutasql($sSQL);
		}
	} else {
		//Algo fallo, nos aseguramos de que no se envie nada.
		$bEnviarMailConfirma = false;
	}
	$bMueveScroll = true;
}
//Enviar un mail de confirmacion.
if ($bEnviarMailConfirma) {
	$_REQUEST['paso'] = 0;
	list($sCodigo, $sError) = AUREA_ConfirmarCorreoNotifica($idTercero, $objDB);
	if ($sError == '') {
		$sError = 'Se ha enviado un codigo de confirmaci&oacute;n a su correo de notificaciones.';
		$iTipoError = 2;
		$iSector = 2;
	}
}
$bHayCodigo = false;
$sCodigo = '';
$sTabla = 'aure01login' . date('Ym');
$sSQL = 'SELECT aure01id, aure01codigo FROM ' . $sTabla . ' WHERE aure01idtercero=' . $idTercero . ' AND aure01consec=0 AND aure01fecha=' . fecha_DiaMod() . '';
$tabla = $objDB->ejecutasql($sSQL);
if ($objDB->nf($tabla) > 0) {
	$fila = $objDB->sf($tabla);
	$bHayCodigo = true;
	$sCodigo = $fila['aure01codigo'];
}
//Confirmar el codigo
if ($_REQUEST['paso'] == 23) {
	$_REQUEST['paso'] = 0;
	if (isset($_REQUEST['cod_confirma']) == 0) {
		$_REQUEST['cod_confirma'] = '';
	}
	$_REQUEST['cod_confirma'] = numeros_validar($_REQUEST['cod_confirma']);
	if ($_REQUEST['cod_confirma'] == '') {
		$sError = 'No ha ingresado un c&oacute;digo de verificaci&oacute;n';
		$iSector = 2;
	}
	if ($sError == '') {
		if (!$bHayCodigo) {
			$sError = 'No se encuentra un c&oacute;digo de verficaci&oacute;n vigente, es posible que necesite generar un c&oacute;digo nuevo.';
		}
	}
	if ($sError == '') {
		if ($sCodigo != $_REQUEST['cod_confirma']) {
			$sError = 'El c&oacute;digo de confirmaci&oacute;n no corresponde.';
			$iSector = 2;
		}
	}
	if ($sError == '') {
		$sDetalleAudita = 'Confirma el correo de notificaciones';
		$unad11modf = fecha_DiaMod();
		$unad11modm = fecha_MinutoMod();
		$sdatos = 'unad11fechaconfmail=' . $unad11modf . '';
		$sdatos = $sdatos . ', unad11modf=' . $unad11modf . ', unad11modm=' . $unad11modm . '';
		if ($_REQUEST['unad11fechaconfmail'] != 0) {
			$sdatos = $sdatos . ', unad11correonotifica=unad11correonotificanuevo';
		}
		$sSQL = 'UPDATE unad11terceros SET ' . $sdatos . ' WHERE unad11id=' . $idTercero . ';';
		$result = $objDB->ejecutasql($sSQL);
		if ($_REQUEST['unad11fechaconfmail'] != 0) {
			$sSQL = 'UPDATE unad11terceros SET unad11correonotificanuevo="" WHERE unad11id=' . $idTercero . ';';
			$result = $objDB->ejecutasql($sSQL);
			$sDetalleAudita = 'Modifica el correo de notificaciones Anterior: ' . $_REQUEST['unad11correonotifica'] . ' Nuevo: ' . $_REQUEST['unad11correonotificanuevo'] . '';
			$_REQUEST['unad11correonotifica'] = $_REQUEST['unad11correonotificanuevo'];
			$_REQUEST['unad11correonotificanuevo'] = '';
		}
		seg_auditar(111, $_SESSION['unad_id_tercero'], 3, $idTercero, $sDetalleAudita, $objDB);
		//ahora intentar actualizar la mdl_user del moodle.
		list($sError, $sDebugM) = AUREA_ActualizarPerfilMoodle($_SESSION['unad_id_tercero'], $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugM;
		//fin de intentar actualizar la mdl_user.
		$sError = '<b>Se han confirmado su correo de notificaciones</b>';
		$iTipoError = 1;
		$_REQUEST['unad11fechaconfmail'] = $unad11modf;
	}
}
//La funcion de cambio de contraseña es equivalente a la que hay en recuperar.php
//Aplicar el cambio real de la clave...
$bPasaATablero = false;
if ($_REQUEST['paso'] == 24) {
	$_REQUEST['paso'] = 2;
	$bMueveScroll = true;
	$sBase = $_REQUEST['txtnuevaclave'];
	$_REQUEST['txtnuevaclave'] = htmlspecialchars($_REQUEST['txtnuevaclave']);
	if ($sBase != $_REQUEST['txtnuevaclave']) {
		$sError = 'El valor ingresado contiene caracteres no permitidos, por favor intente con otra clave.';
	}
	if ($sError == '') {
		if ($_REQUEST['txtnuevaclave'] != $_REQUEST['txtnuevaclave2']) {
			$sError = 'La confirmaci&oacute;n no corresponde con la contrase&ntilde;a';
		}
	}
	if ($sError == '') {
		list($sError, $sDebugV) = AUREA_ClaveValidaV3($_REQUEST['txtnuevaclave'], $idTercero, $objDB, $sPermitidos, $bDebug);
		$sDebug = $sDebug . $sDebugV;
	}
	if ($sError == '') {
		//Debe haber confirmado el correo antes de poder hacer este proceso...
		if ($_REQUEST['unad11fechaconfmail'] == 0) {
			$sError = 'Antes de cambiar la contrase&ntilde;a debe haber confirmado su correo de notificaciones.';
		}
	}
	if ($sError == '') {
		if ($idTercero == $_SESSION['unad_id_tercero']) {
			$sError = login_ActualizarClave($_REQUEST['unad11usuario'], $_REQUEST['txtnuevaclave'], $idTercero, $objDB);
		} else {
			$sError = 'No fue posible actualizar la contrase&ntilde;a debido a un error de validaci&oacute;n de datos, se ha informado al administrador del sistema.';
		}
		if ($sError == '') {
			$_REQUEST['unad11debeactualizarclave'] = 0;
			seg_rastro(17, 0, 0, $idTercero, 'Cambia su cotraseña desde el tablero', $objDB);
			$sError = 'Se ha actualizado exitosamente la contrase&ntilde;a de acceso a Campus Virtual.';
			$iTipoError = 1;
			//$_REQUEST['boculta111']=1;
			if ($_REQUEST['salto'] == 1) {
				$bPasaATablero = true;
			}
		}
	}
}
if ($_REQUEST['paso'] == 25) {
	$_REQUEST['paso'] = 2;
	$bMueveScroll = true;
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' Verificando perfiles [Ref ' . $idTercero . ']<br>';
	}
	list($sError, $sDebugG) = f107_VerificarPerfiles($idTercero, '', $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugG;
}
$bForzarClave = false;
$bForzarCorreo = false;
$bBloqueClave = true;
$bBloqueFoto = true;
if ($_REQUEST['unad11debeactualizarclave'] > 0) {
	$iHoy = fecha_DiaMod();
	$iFechaTope = fecha_NumSumarDias($_REQUEST['unad11debeactualizarclave'], $iDiasVentanaClave);
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' Fecha tope=' . $iFechaTope . ' <br>';
	}
	if ($iFechaTope < $iHoy) {
		if ($_REQUEST['unad11fechaconfmail'] == 0) {
			$bForzarCorreo = true;
			$bBloqueClave = false;
			$bBloqueFoto = false;
		} else {
			$bForzarClave = true;
			$_REQUEST['salto'] = 1;
		}
	}
}
if (!$bForzarCorreo) {
	if ($_REQUEST['unad11fechaconfmail'] == 0) {
		if ($_REQUEST['unad11exluirdobleaut'] != 'S') {
			if ($_REQUEST['unad11rolunad'] > 0) {
				$bForzarCorreo = true;
				$bBloqueClave = false;
				$bBloqueFoto = false;
			}
		}
	}
}
//AQUI SE DEBEN CARGAR TODOS LOS DATOS QUE LA FORMA NECESITE.
$bPuedeGuardar = false;
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
if ($seg_6 == 1) {
	$bHayImprimir = true;
}
$bPuedeEditar = false;
$bPuedeModificarCorreo = true;
$bModoReducido = false;
if (isset($_REQUEST['mred']) != 0){
	if ($_REQUEST['mred'] == 1){
		$_REQUEST['modo'] = 1;
		$bModoReducido = true;
		$bPuedeEditar = true;
		//Anulo las demas opciones.
		$seg_1707 = 0;
		$bPuedeModificarCorreo = false;
	}
}
//DATOS PARA COMPLETAR EL FORMULARIO
$iAgnoIni = 2000;
$iAgno = fecha_agno();
$iAgnoFin = $iAgno + 5;
$sNombreUsuario = '';
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
$bIgualarRCONT = false;
$unad11fechaactualiza = 0;
if ($idTercero == $_SESSION['unad_id_tercero']){
	//Verificamos que no tenga limitaciones para editar.
	$sSQL = 'SELECT unad11correo, unad11idtablero, unad11identidad_verifica, unad11nombre1, unad11fechaactualiza 
	FROM unad11terceros 
	WHERE unad11id=' . $idTercero . '';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$unad11fechaactualiza = $fila['unad11fechaactualiza'];
		if ($fila['unad11idtablero'] == 0) {
			$bPuedeEditar = true;
		} else {
		}
		if ($fila['unad11identidad_verifica'] != 0){
			$bPuedeEditar = false;
		}
		if (trim($fila['unad11nombre1']) == '') {
			$bModoReducido = true;
			$_REQUEST['modo'] = 1;
		}
		//Si se inscribió directamente no puede modificar el correo hasta que no confirme el documento.
		$sSQL = 'SELECT 1 FROM aure72preinscripcion WHERE aure72correo="' . $fila['unad11correo'] . '"';
		$tabla72 = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla72) > 0) {
			$bPuedeModificarCorreo = false;
			$bDatosPersonales = true;
		}
	}
} else {
	$sSQL = 'SELECT unad11nombre1 
	FROM unad11terceros 
	WHERE unad11id=' . $idTercero . '';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		if (trim($fila['unad11nombre1']) == '') {
			$bModoReducido = true;
		}
	}
}
if ($bModoReducido){
	$bBloqueFoto = false;
	$bBloqueClave = false;
}
if (!$bPuedeEditar) {
	if ($unad11fechaactualiza < 20221223) {
		if ($_REQUEST['unad11tipodoc'] == 'CC') {
			//Traemos los datos de RCONT y ACTUALIZAMOS
			list($objRCONT, $sDebugC) = TraerDBRyCV2($bDebug);
			$sDebug = $sDebug . $sDebugC;
			if ($objRCONT == NULL) {
				//$sError = 'No ha sido posible establecer conexi&oacute;n con EDUNAT.';
			} else {
				$sSQL = 'SELECT primer_nombre, segundo_nombre,primer_apellido,segundo_apellido 
				FROM preinscripcion 
				WHERE documento=' . $_REQUEST['unad11doc'] . '
				ORDER BY fecha_grab DESC';
				$tabla = $objRCONT->ejecutasql($sSQL);
				if ($objRCONT->nf($tabla) > 0) {
					$fila = $objRCONT->sf($tabla);
					$_REQUEST['unad11nombre1'] = cadena_letrasynumeros($fila['primer_nombre'], ' &');
					$_REQUEST['unad11nombre2'] = cadena_letrasynumeros($fila['segundo_nombre'], ' &');
					$_REQUEST['unad11apellido1'] = cadena_letrasynumeros($fila['primer_apellido'], ' &');
					$_REQUEST['unad11apellido2'] = cadena_letrasynumeros($fila['segundo_apellido'], ' &');
					$unad11razonsocial = trim(trim($_REQUEST['unad11nombre1']) . ' ' . trim($_REQUEST['unad11nombre2']) . ' ' . trim($_REQUEST['unad11apellido1']) . ' ' . trim($_REQUEST['unad11apellido2']));
					$sSQL = 'UPDATE unad11terceros SET unad11nombre1="' . $_REQUEST['unad11nombre1'] . '", 
					unad11nombre2="' . $_REQUEST['unad11nombre2'] . '", unad11apellido1="' . $_REQUEST['unad11apellido1'] . '", 
					unad11apellido2="' . $_REQUEST['unad11apellido2'] . '", unad11razonsocial="' . $unad11razonsocial . '", 
					unad11fechaactualiza=' . $iHoy . ' WHERE unad11id=' . $idTercero . '';
				} else {
					$sSQL = 'UPDATE unad11terceros SET unad11fechaactualiza=' . $iHoy . ' WHERE unad11id=' . $idTercero . '';
				}
				$result = $objDB->ejecutasql($sSQL);
				$objRCONT->CerrarConexion();
			}
		}
	}
}
//Crear los controles que requieran llamado a base de datos
$objCombos = new clsHtmlCombos();
$objForma = new clsHtmlForma($iPiel);
$bMuestra = $bPuedeEditar;
if ($_REQUEST['unad11genero'] == '') {
	$bMuestra = true;
}
if ($bMuestra) {
	$objCombos->nuevo('unad11genero', $_REQUEST['unad11genero'], true, '{' . $ETI['msg_seleccione'] . '}');
	$sSQL = 'SELECT unad22codopcion AS id, unad22nombre AS nombre FROM unad22combos WHERE unad22idmodulo=111 AND unad22consec=1 AND unad22activa="S" ORDER BY unad22orden';
	$html_unad11genero = $objCombos->html($sSQL, $objDB);
} else {
	$et_unad11genero = '{' . $_REQUEST['unad11genero'] . '}';
	$sSQL = 'SELECT unad22nombre FROM unad22combos WHERE unad22idmodulo=111 AND unad22consec=1 AND unad22codopcion="' . $_REQUEST['unad11genero'] . '"';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$et_unad11genero = cadena_notildes($fila['unad22nombre']);
	}
	$html_unad11genero = html_oculto('unad11genero', $_REQUEST['unad11genero'], $et_unad11genero);
}
$html_unad11rh = html_combo('unad11rh', 'unad22codopcion', 'unad22nombre', 'unad22combos', 'unad22idmodulo=111 AND unad22consec=2 AND unad22activa="S"', 'unad22orden', $_REQUEST['unad11rh'], $objDB, '', true, '{' . $ETI['msg_na'] . '}', '');
$html_unad11ecivil = html_combo('unad11ecivil', 'unad21codigo', 'unad21nombre', 'unad21estadocivil', '', 'unad21orden', $_REQUEST['unad11ecivil'], $objDB, '', true, '{' . $ETI['msg_na'] . '}', '');
if ($bPuedeEditar) {
	$objCombos->nuevo('unad01estrato', $_REQUEST['unad01estrato'], true, $aunad01estrato[0], 0);
	$objCombos->addArreglo($aunad01estrato, $iunad01estrato);
	$html_unad01estrato = $objCombos->html('', $objDB);
} else {
	$et_unad01estrato = $aunad01estrato[0];
	switch ($_REQUEST['unad01estrato']) {
		case 1:
		case 2:
		case 3:
		case 4:
		case 5:
		case 6:
			$et_unad01estrato = $aunad01estrato[$_REQUEST['unad01estrato']];
			break;
	}
	$html_unad01estrato = html_oculto('unad01estrato', $_REQUEST['unad01estrato'], $et_unad01estrato);
}
$objCombos->nuevo('unad11idzona', $_REQUEST['unad11idzona'], true, '{' . $ETI['msg_ninguna'] . '}', 0);
$objCombos->sAccion = 'carga_combo_unad11idcead();';
$html_unad11idzona = $objCombos->html('SELECT unad23id AS id, unad23nombre AS nombre FROM unad23zona ORDER BY unad23nombre', $objDB);
$et_unad11aceptanotificacion = $ETI['msg_decidirdespues'];
if ($_REQUEST['unad11aceptanotificacion'] == 'N') {
	$et_unad11aceptanotificacion = $ETI['no'];
}
if ($_REQUEST['unad11aceptanotificacion'] == 'S') {
	$et_unad11aceptanotificacion = $ETI['si'];
}
$html_unad11aceptanotificacion = html_oculto('unad11aceptanotificacion', $_REQUEST['unad11aceptanotificacion'], $et_unad11aceptanotificacion);
$html_unad11mostrarcorreo = html_sino('unad11mostrarcorreo', $_REQUEST['unad11mostrarcorreo']);
$html_unad11mostrarskype = html_sino('unad11mostrarskype', $_REQUEST['unad11mostrarskype']);
$html_unad11mostrarcelular = html_sino('unad11mostrarcelular', $_REQUEST['unad11mostrarcelular']);
$html_unad11idcead = f17_HTMLComboV2_unad11idcead($objDB, $objCombos, $_REQUEST['unad11idcead'], $_REQUEST['unad11idzona']);
$objCombos->nuevo('unad11idescuela', $_REQUEST['unad11idescuela'], true, '{' . $ETI['msg_ninguna'] . '}', 0);
$objCombos->sAccion = 'carga_combo_unad11idprograma();';
$sSQL = 'SELECT exte01id AS id, exte01nombre AS nombre FROM exte01escuela ORDER BY exte01nombre';
$html_unad11idescuela = $objCombos->html($sSQL, $objDB);
$html_unad11idprograma = f17_HTMLComboV2_unad11idprograma($objDB, $objCombos, $_REQUEST['unad11idprograma'], $_REQUEST['unad11idescuela']);
$objCombos->nuevo('unad11pais', $_REQUEST['unad11pais'], true, '{' . $ETI['msg_otro'] . '}');
$objCombos->sAccion = 'carga_combo_unad11deptodoc();';
$sSQL = 'SELECT unad18codigo AS id, unad18nombre AS nombre FROM unad18pais ORDER BY unad18nombre';
$html_unad11pais = $objCombos->html($sSQL, $objDB);
$html_unad11deptodoc = f17_HTMLComboV2_unad11deptodoc($objDB, $objCombos, $_REQUEST['unad11deptodoc'], $_REQUEST['unad11pais']);
$html_unad11ciudaddoc = f17_HTMLComboV2_unad11ciudaddoc($objDB, $objCombos, $_REQUEST['unad11ciudaddoc'], $_REQUEST['unad11deptodoc']);
$objCombos->nuevo('unad01zonares', $_REQUEST['unad01zonares'], true, '{' . $ETI['msg_seleccione'] . '}', '');
$objCombos->AddItem('U', $aunad01zonares[1]);
$objCombos->AddItem('R', $aunad01zonares[2]);
$html_unad01zonares = $objCombos->html('', $objDB);
$sInfoNoti = '';
switch ($_REQUEST['unad11rolunad']) {
	case 1: //Contratistas.
	case 2: //Personal administrativo
		if ($_REQUEST['unad11fechaconfmail'] == 0) {
			$sInfoNoti = $ETI['msg_noticontratista'];
		}
		break;
	case 0: // Estudiantes.
		if ($_REQUEST['unad11fechaconfmail'] == 0) {
			$sInfoNoti = $ETI['msg_notiestudiante'];
		}
		break;
}
$objCombos->nuevo('unad01autoriza_tel', $_REQUEST['unad01autoriza_tel'], true, '-', -1);
$objCombos->addItem(1, $ETI['si']);
$objCombos->addItem(0, $ETI['no']);
$html_unad01autoriza_tel = $objCombos->html('', $objDB);
$objCombos->nuevo('unad01autoriza_bol', $_REQUEST['unad01autoriza_bol'], true, '-', -1);
$objCombos->addItem(1, $ETI['si']);
$objCombos->addItem(0, $ETI['no']);
//$objCombos->addArreglo($aunad01autoriza_bol, $iunad01autoriza_bol);
$html_unad01autoriza_bol = $objCombos->html('', $objDB);
$objCombos->nuevo('unad01oir_ruv', $_REQUEST['unad01oir_ruv'], true, '-', -1);
$objCombos->addItem(1, $ETI['si']);
$objCombos->addItem(0, $ETI['no']);
$html_unad01oir_ruv = $objCombos->html('', $objDB);

$objCombos->nuevo('unad01autoriza_mat', $_REQUEST['unad01autoriza_mat'], true, '-', -1);
$objCombos->addItem(1, $ETI['si']);
$objCombos->addItem(0, $ETI['no']);
$html_unad01autoriza_mat = $objCombos->html('', $objDB);
$objCombos->nuevo('unad01autoriza_bien', $_REQUEST['unad01autoriza_bien'], true, '-', -1);
$objCombos->addItem(1, $ETI['si']);
$objCombos->addItem(0, $ETI['no']);
$html_unad01autoriza_bien = $objCombos->html('', $objDB);

if ($sInfoNoti != '') {
	$sInfoNoti = '<div class="GrupoCamposAyuda">' . $sInfoNoti . '
	<div class="salto1px"></div>
	' . $ETI['msg_recomendaciones'] . '
	<div class="salto1px"></div>
	</div>';
}
if ($bTipoGeneral){
	//Perfiles
	$aParametros107[100] = $idTercero;
	$aParametros107[101] = $_REQUEST['paginaf107'];
	$aParametros107[102] = $_REQUEST['lppf107'];
	//$aParametros107[103]=$_REQUEST['bnombre107'];
	//$aParametros107[104]=$_REQUEST['blistar107'];
	list($sTabla107, $sDebugTabla) = f107_TablaDetalleV2Perfil($aParametros107, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugTabla;
}
switch ($iPiel) {
	case 2:
		list($et_menu, $sDebugM) = html_Menu2023($APP->idsistema, $objDB, $iPiel, $bDebugMenu, $idTercero);
		break;
	default:
		if ($bTipoGeneral) {
			list($et_menu, $sDebugM) = html_menuV2($APP->idsistema, $objDB, $iPiel, $bDebugMenu, $idTercero);
		} else {
			list($et_menu, $sDebugM) = html_menuCampus($APP->idsistema, $objDB, $iPiel, $bDebugMenu, $idTercero);
		}
		break;
}
$sDebug = $sDebug . $sDebugM;
$objDB->CerrarConexion();
//FORMA
switch ($iPiel) {
	case 2:
		require $APP->rutacomun . 'unad_forma2023.php';
		forma_InicioV4($xajax, $sTituloModulo);
		$aRutas = array(
			array('./', $sTituloApp),
			array('./' . $sPaginaModulo, $sGrupoModulo),
			array('', $sTituloModulo)
		);
		$iNumBoton = 0;
		$aBotones[$iNumBoton] = array('muestraayuda(' . $APP->idsistema . ', ' . $iCodModulo . ')', $ETI['bt_ayuda'], 'iHelp');
		$iNumBoton++;
		$aBotones[$iNumBoton] = array('limpiapagina()', $ETI['bt_limpiar'], 'iDocument');
		$iNumBoton++;
		$aBotones[$iNumBoton] = array('enviaguardar()', $ETI['bt_guardar'], 'iSaveFill');
		$iNumBoton++;
		$aBotones[$iNumBoton] = array('expandesector(1)', $ETI['bt_volver'], 'iArrowBack', 97);
		$iNumBoton++;
		forma_cabeceraV4b($aRutas, $aBotones, true, $iSector);
		echo $et_menu;
		forma_mitad($idTercero);
		break;
	default:
		require $APP->rutacomun . 'unad_forma_v2_2024.php';
		forma_cabeceraV3($xajax, $sTituloModulo);
		echo $et_menu;
		forma_mitad();
		break;
}
?>
<script language="javascript">
	function guardanoti() {
		window.document.frmedita.iscroll.value = window.scrollY;
		expandesector(98);
		window.document.frmedita.paso.value = 21;
		window.document.frmedita.submit();
	}

	function expandesector(codigo) {
		document.getElementById('div_sector1').style.display = 'none';
		document.getElementById('div_sector2').style.display = 'none';
		document.getElementById('div_sector93').style.display = 'none';
		document.getElementById('div_sector95').style.display = 'none';
		document.getElementById('div_sector96').style.display = 'none';
		document.getElementById('div_sector98').style.display = 'none';
		document.getElementById('div_sector' + codigo).style.display = 'block';
<?php
switch ($iPiel) {
	case 2:
?>
		document.getElementById('botones_sector1').style.display = 'flex';
		document.getElementById('botones_sector97').style.display = 'none';
		switch (codigo) {
			case 1:
				break;
			case 97:
				document.getElementById('botones_sector1').style.display = 'none';
				document.getElementById('botones_sector' + codigo).style.display = 'flex';
				break;
			default:
				document.getElementById('botones_sector1').style.display = 'none';
				break;
		}
		if (codigo == 1) {
			document.getElementById('nav').removeAttribute('disabled');
		} else {
			document.getElementById('nav').setAttribute('disabled', '');
		}
<?php
		break;
	default:
		if ($bPuedeGuardar && $bBloqueTitulo) {
?>
		let sEst = 'none';
		if (codigo == 1) {
			sEst = 'block';
		}
		document.getElementById('cmdGuardarf').style.display = sEst;
<?php
		}
		break;
}
?>
	}

	function carga_combo_unad11idcead() {
		let params = new Array();
		params[0] = window.document.frmedita.unad11idzona.value;
		xajax_f17_Combounad11idcead(params);
	}

	function carga_combo_unad11idprograma() {
		let params = new Array();
		params[0] = window.document.frmedita.unad11idescuela.value;
		xajax_f17_Combounad11idprograma(params);
	}

	function carga_combo_unad11deptodoc() {
		let params = new Array();
		params[0] = window.document.frmedita.unad11pais.value;
		xajax_f17_Combounad11deptodoc(params);
	}

	function carga_combo_unad11ciudaddoc() {
		let params = new Array();
		params[0] = window.document.frmedita.unad11deptodoc.value;
		xajax_f17_Combounad11ciudaddoc(params);
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

	function cierraDiv96(ref) {
		let sRetorna = window.document.frmedita.div96v2.value;
		MensajeAlarmaV2('', 0);
		retornacontrol();
	}
<?php
if (!$bBotonDerecho) {
?>
		var mensajeerror = "Boton derecho deshabilitado";
		if (document.layers) {
			window.captureEvents(Event.MOUSEDOWN);
		}

		function bloquear(e) {
			if (navigator.appName == 'Netscape' && (e.which == 2 || e.which == 3)) {
				alert(mensajeerror);
				return false;
			}
			if (navigator.appName == 'Microsoft Internet Explorer' && (event.button == 2 || event.button == 3)) {
				alert(mensajeerror);
				return false;
			}
		}
		window.onmousedown = bloquear;
		//document.onmousedown=bloquear;
<?php
}
?>

	function cerrarventana() {
		window.close();
	}

	function volver() {
		window.document.frmvolver.submit();
	}

	function confirmar() {
		window.document.frmedita.iscroll.value = window.scrollY;
		expandesector(98);
		window.document.frmedita.paso.value = 22;
		window.document.frmedita.submit();
	}

	function verificar() {
		window.document.frmedita.iscroll.value = window.scrollY;
		expandesector(98);
		window.document.frmedita.paso.value = 23;
		window.document.frmedita.submit();
	}

	function cambiaclave() {
		window.document.frmedita.iscroll.value = window.scrollY;
		expandesector(98);
		window.document.frmedita.paso.value = 24;
		window.document.frmedita.submit();
	}

	function irclave() {
		window.document.frmclave.submit();
	}

	function ircontacto() {
		window.document.frmcontacto.submit();
	}

	function cambiaidioma(sIdioma) {
		expandesector(98);
		window.document.frmedita.idioma.value = sIdioma;
		window.document.frmedita.paso.value = 26;
		window.document.frmedita.submit()
	}
<?php
$sRutaAurea = './';
if ($bTipoGeneral) {
	$sRutaAurea = 'https://aurea2.unad.edu.co/aurea/';
	if ($idEntidad == 1) {
		$sRutaAurea = '../aurea/';
	}
?>
	function verperfiles() {
		window.document.frmedita.iscroll.value = window.scrollY;
		expandesector(98);
		window.document.frmedita.paso.value = 25;
		window.document.frmedita.submit();
	}
<?php
}
?>
</script>
<?php
if ($bTipoGeneral) {
?>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>jsi/js107perfil.js"></script>
<?php
}
?>
<div id="interna">
<form id="frmclave" name="frmclave" method="post" action="<?php echo $sRutaAurea; ?>contrasegna.php">
</form>
<form id="frmcontacto" name="frmcontacto" method="post" action="<?php echo $sRutaAurea; ?>contacto.php">
</form>
<form id="frmvolver" name="frmvolver" method="post" action="./">
</form>
<form id="frmedita" name="frmedita" method="post" action="" autocomplete="off">
<input id="bNoAutocompletar" name="bNoAutocompletar" type="password" value="" style="display:none;" />
<input id="paso" name="paso" type="hidden" value="<?php echo $_REQUEST['paso']; ?>" />
<input id="unad11rolunad" name="unad11rolunad" type="hidden" value="<?php echo $_REQUEST['unad11rolunad']; ?>" />
<input id="unad11debeactualizarclave" name="unad11debeactualizarclave" type="hidden" value="<?php echo $_REQUEST['unad11debeactualizarclave']; ?>" />
<input id="salto" name="salto" type="hidden" value="<?php echo $_REQUEST['salto']; ?>" />
<input id="idioma" name="idioma" type="hidden" value="" />
<input id="modo" name="modo" type="hidden" value="<?php echo $_REQUEST['modo']; ?>" />
<div id="div_sector1">
<?php
if ($bBloqueTitulo) {
	if ($bTipoGeneral) {
		$objForma->addBoton('cmdAyuda', 'btSupAyuda', 'muestraayuda(' . $APP->idsistema . ', ' . $iCodModulo . ');', $ETI['bt_ayuda']);
	}
	$objForma->addBoton('cmdLimpiar', 'btUpLimpiar', 'limpiapagina();', $ETI['bt_limpiar']);
	if (!$bTipoGeneral) {
		$objForma->addBoton('cmdVolverSec2', 'btSupVolver', 'volver();', $ETI['bt_volver']);
	}
	echo $objForma->htmlTitulo($sTituloModulo, $iCodModulo);
}
echo $objForma->htmlInicioMarco();
//Termina el bloque titulo
?>
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
<?php
echo $objForma->htmlBotonSolo('btRevisaDoc', 'btMiniActualizar', 'limpiapagina()', 'Consultar documento', 30);
?>
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
if ($bMensajeEntrada) {
?>
<div class="salto1px"></div>
<div class="GrupoCamposAyuda">
<div class="MarquesinaMedia">
Por favor complete los campos de AUTORIZACI&Oacute;N PARA EL TRATAMIENTO DE DATOS PERSONALES
</div>
<div class="salto1px"></div>
</div>
<?php
}
?>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['unad11doc'];
?>
</label>
<label class="Label90">
<?php
echo html_oculto('unad11tipodoc', $_REQUEST['unad11tipodoc']);
?>
</label>
<label class="Label220">
<?php
echo html_oculto('unad11doc', $_REQUEST['unad11doc']);
?>
</label>
<?php
$bOculto = true;
if ($_REQUEST['unad11usuario'] != '') {
	if ($_REQUEST['unad11usuario'] != $_REQUEST['unad11tipodoc'].$_REQUEST['unad11doc']) {
		$bOculto = false;
	}
}
if ($bOculto){
?>
<input id="unad11usuario" name="unad11usuario" type="hidden" value="<?php echo $_REQUEST['unad11usuario']; ?>" />
<?php
} else {
?>
<label class="Label90">
<?php
echo $ETI['unad11usuario'];
?>
</label>
<label class="Label130">
<div id="div_unad11usuario">
<?php
echo html_oculto('unad11usuario', $_REQUEST['unad11usuario']);
?>
</div>
</label>
<?php
}
?>
<div class="salto1px"></div>
<div id="divnombres">
<label class="Label160">
<?php
echo $ETI['unad11nombre1'];
?>
</label>
<label>
<?php
if ($bPuedeEditar) {
?>
<input id="unad11nombre1" name="unad11nombre1" type="text" value="<?php echo $_REQUEST['unad11nombre1']; ?>" maxlength="30" placeholder="<?php echo $ETI['ing_campo'] . $ETI['unad11nombre1']; ?>" />
<?php
} else {
	echo html_oculto('unad11nombre1', $_REQUEST['unad11nombre1']);
}
?>
</label>
<label class="Label160">
<?php
echo $ETI['unad11nombre2'];
?>
</label>
<label>
<?php
if ($bPuedeEditar) {
?>
<input id="unad11nombre2" name="unad11nombre2" type="text" value="<?php echo $_REQUEST['unad11nombre2']; ?>" maxlength="30" placeholder="<?php echo $ETI['ing_campo'] . $ETI['unad11nombre2']; ?>" />
<?php
} else {
	echo html_oculto('unad11nombre2', $_REQUEST['unad11nombre2']);
}
?>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['unad11apellido1'];
?>
</label>
<label>
<?php
if ($bPuedeEditar) {
?>
<input id="unad11apellido1" name="unad11apellido1" type="text" value="<?php echo $_REQUEST['unad11apellido1']; ?>" maxlength="30" placeholder="<?php echo $ETI['ing_campo'] . $ETI['unad11apellido1']; ?>" />
<?php
} else {
	echo html_oculto('unad11apellido1', $_REQUEST['unad11apellido1']);
}
?>
</label>
<label class="Label160">
<?php
echo $ETI['unad11apellido2'];
?>
</label>
<label>
<?php
if ($bPuedeEditar) {
?>
<input id="unad11apellido2" name="unad11apellido2" type="text" value="<?php echo $_REQUEST['unad11apellido2']; ?>" maxlength="30" placeholder="<?php echo $ETI['ing_campo'] . $ETI['unad11apellido2']; ?>" />
<?php
} else {
	echo html_oculto('unad11apellido2', $_REQUEST['unad11apellido2']);
}
?>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['unad11telefono'];
?>
</label>
<label class="Label250">
<input id="unad11telefono" name="unad11telefono" type="text" value="<?php echo $_REQUEST['unad11telefono']; ?>" />
</label>
<?php
if ($bModoReducido) {
?>
<input id="unad11mostrarcelular" name="unad11mostrarcelular" type="hidden" value="<?php echo $_REQUEST['unad11mostrarcelular']; ?>" />
<?php
} else {
?>
<label class="Label450">
<?php
echo $ETI['unad11mostrarcelular'];
?>
</label>
<label class="Label60">
<?php
echo $html_unad11mostrarcelular;
?>
</label>
<?php
}
?>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['unad11fechanace'];
?>
</label>
<label class="Label300">
<div>
<?php
$bBloqueado = false;
if (fecha_esvalida($_REQUEST['unad11fechanace'])) {
	$bBloqueado = !$bPuedeEditar;
}
if ($bBloqueado){
	echo html_oculto('unad11fechanace', $_REQUEST['unad11fechanace']);
} else {
	echo html_fecha('unad11fechanace', $_REQUEST['unad11fechanace'], true, '', 1900, fecha_agno()-5);
}
?>
</div>
</label>
<label class="Label60">
<?php
echo $ETI['unad11genero'];
?>
</label>
<label class="Label160">
<?php
echo $html_unad11genero;
?>
</label>
<?php
if ($bModoReducido) {
?>
<input id="unad11rh" name="unad11rh" type="hidden" value="<?php echo $_REQUEST['unad11rh']; ?>" />
<input id="unad11ecivil" name="unad11ecivil" type="hidden" value="<?php echo $_REQUEST['unad11ecivil']; ?>" />
<?php
} else {
?>
<label class="Label60">
<?php
echo $ETI['unad11rh'];
?>
</label>
<label class="Label130">
<?php
echo $html_unad11rh;
?>
</label>
<label class="Label130">
<?php
echo $ETI['unad11ecivil'];
?>
</label>
<label class="Label130">
<?php
echo $html_unad11ecivil;
?>
</label>
<?php
}
?>
</div>
<div class="salto1px"></div>
<label class="Label450">
<?php
echo $ETI['unad11fechadoc'];
?>
</label>
<div class="Campo300">
<?php
$bBloqueado = false;
if (fecha_NumValido($_REQUEST['unad11fechadoc'])) {
	$bBloqueado = !$bPuedeEditar;
}
if ($bBloqueado){
	echo html_oculto('unad11fechadoc', $_REQUEST['unad11fechadoc'], fecha_desdenumero($_REQUEST['unad11fechadoc']));
} else {
	echo html_FechaEnNumero('unad11fechadoc', $_REQUEST['unad11fechadoc'], true, '', 1900, fecha_agno());
}
?>
</div>
<div class="salto1px"></div>

<?php
if ($_REQUEST['unad11debeactualizarclave'] > 0) {
$_REQUEST['boculta111'] = 0;
?>
<div class="GrupoCamposAyuda">
<div class="rojo">
<?php
if (!$bForzarCorreo) {
echo $ETI['msg_clavevencida'];
} else {
echo $ETI['msg_debeconfirmarcorreo'];
}
?>
</div>
<div class="salto1px"></div>
</div>
<?php
}
if ($bBloqueFoto) {
?>
<div class="GrupoCampos450">
<div class="salto1px"></div>
<?php
$sRutaFoto = $sRutaLibs . 'img/usuarioFoto.jpg';
if ($_REQUEST['unad11genero'] == 'M') {
	$sRutaFoto = $sRutaLibs . 'img/avatar_m.png';
}
if ($_REQUEST['unad11genero'] == 'F') {
	$sRutaFoto = $sRutaLibs . 'img/avatar_f.png';
}
?>
<img src="<?php echo $sRutaFoto; ?>" />
<div class="salto1px"></div>
</div>
<?php
}
if ($bBloqueClave) {
?>
<div id="div_clave" class="GrupoCampos450" style="width:550px !important;">
<label class="TituloGrupo">
<?php
echo $ETI['msg_clave'];
?>
</label>
<div class="salto1px"></div>
<label class="Label350">
Para cambiar su contrase&ntilde;a
</label>
<?php
echo $objForma->htmlBotonSolo('cmdClave', 'botonProceso azul', 'irclave()', 'Haga clic aqu&iacute; para cambiar su contrase&ntilde;a', 160, '', 'Haga clic aqu&iacute;');
?>
<div class="salto1px"></div>
<label class="Label350">
Para actualizar su correo de notificaciones
</label>
<?php
echo $objForma->htmlBotonSolo('cmdNotifica', 'botonProceso azul', 'ircontacto()', 'Haga clic aqu&iacute; para actualizar su correo de notificaciones', 160, '', 'Haga clic aqu&iacute;');
?>
<?php
//las banderas para cambio de idioma.
$bConBotones = $bDebug;
if ($idEntidad != 0) {
	$bConBotones = true;
}
$bConEspanol = $bConBotones;
$bConIngles = $bConBotones;
$bConPortugues = $bConBotones;
switch ($_SESSION['unad_idioma']) {
	case 'es':
		$bConEspanol = false;
		break;
	case 'en':
		$bConIngles = false;
		break;
	case 'pt':
		$bConPortugues = false;
		break;
}
?>
<div class="salto1px"></div>
<?php
if ($bConEspanol) {
?>
<label class="Label30"></label>
<label class="Label60">
<a href="javascript:cambiaidioma('es');"><img src="<?php echo $APP->rutacomun; ?>img/es_co.jpg" title="Espa&ntilde;ol" width="40px" height="25px" /></a>
</label>
<?php
}
if ($bConIngles) {
?>
<label class="Label30"></label>
<label class="Label60">
<a href="javascript:cambiaidioma('en');"><img src="<?php echo $APP->rutacomun; ?>img/en_en.jpg" title="English" width="40px" height="25px" /></a>
</label>
<?php
}
if ($bConPortugues) {
?>
<label class="Label30"></label>
<label class="Label60">
<a href="javascript:cambiaidioma('pt');"><img src="<?php echo $APP->rutacomun; ?>img/pt_br.jpg" title="Portugu&ecirc;s" width="40px" height="25px" /></a>
</label>
<?php
}
?>
<div class="salto1px"></div>
</div>
<?php
}
//$bForzarCorreo
//$bForzarClave
$sMuestra = '';
if ($bForzarClave) {
$sMuestra = ' style="display:none;"';
}
?>
<div class="salto1px"></div>
<?php
if (true) {
?>
<div id="div_bloquecorreo" <?php echo $sMuestra; ?>>
<?php
echo $sInfoNoti;
?>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['unad11correo'];
?>
</label>
<label class="Label500">
<?php
if ($bPuedeModificarCorreo){
?>
<input id="unad11correo" name="unad11correo" type="text" value="<?php echo $_REQUEST['unad11correo']; ?>" class="L" />
<?php
} else {
	echo html_oculto('unad11correo', $_REQUEST['unad11correo']);
}
?>
</label>
<div class="salto1px"></div>
<label class="Label200">
<?php
echo $ETI['unad11aceptanotificacion'];
?>
</label>
<label class="Label200">
<?php
echo $html_unad11aceptanotificacion;
?>
</label>
<label class="Label450">
<?php
echo $ETI['unad11mostrarcorreo'];
?>
</label>
<label class="Label60">
<?php
echo $html_unad11mostrarcorreo;
?>
</label>
<div class="salto1px"></div>
<label class="Label200">
<?php
echo $ETI['unad11correonotifica'];
?>
</label>
<label class="Label500">
<?php
echo html_oculto('unad11correonotifica', $_REQUEST['unad11correonotifica']);
?>
</label>
<input id="unad11correonotificanuevo" name="unad11correonotificanuevo" type="hidden" value="<?php echo $_REQUEST['unad11correonotificanuevo']; ?>" />
<div class="salto1px"></div>
<label class="Label200">
<?php
echo $ETI['unad11fechaconfmail'];
?>
</label>
<label class="Label130">
<div id="div_unad11fechaconfmail">
<?php
echo html_oculto('unad11fechaconfmail', $_REQUEST['unad11fechaconfmail'], fecha_desdenumero($_REQUEST['unad11fechaconfmail'], '[Sin confirmar]'));
?>
</div>
</label>
<?php
$bEntra = false;
if ($_REQUEST['unad11fechaconfmail'] == 0) {
	$bEntra = true;
} else {
	if ($_REQUEST['unad11correonotificanuevo'] != '') {
		$bEntra = true;
	}
}
?>
<?php
if ($bModoReducido) {
?>
<input id="unad11correoinstitucional" name="unad11correoinstitucional" type="hidden" value="<?php echo $_REQUEST['unad11correoinstitucional']; ?>" />
<?php
} else {
?>
<div class="salto1px"></div>
<input id="unad11correoinstitucional" name="unad11correoinstitucional" type="hidden" value="<?php echo $_REQUEST['unad11correoinstitucional']; ?>" />
<label class="L">
<?php
echo $ETI['unad11correoinstitucional'] . ' <b>' . $_REQUEST['unad11correoinstitucional'] . '</b>';
?>
</label>
<?php
}
?>
</div>
<?php
//Este div termina el bloque de correo.
}
?>
<div class="salto1px"></div>
<?php
$sMuestra = '';
?>
<?php
if ($bModoReducido) {
?>
<input id="unad11skype" name="unad11skype" type="hidden" value="<?php echo $_REQUEST['unad11skype']; ?>" />
<input id="unad11mostrarskype" name="unad11mostrarskype" type="hidden" value="<?php echo $_REQUEST['unad11mostrarskype']; ?>" />
<?php
} else {
?>
<div id="div_bloqueadicionales" <?php echo $sMuestra; ?>>
<label class="Label160">
<?php
echo $ETI['unad11skype'];
?>
</label>
<label class="Label250">
<input id="unad11skype" name="unad11skype" type="text" value="<?php echo $_REQUEST['unad11skype']; ?>" />
</label>
<label class="Label450">
<?php
echo $ETI['unad11mostrarskype'];
?>
</label>
<label class="Label60">
<?php
echo $html_unad11mostrarskype;
?>
</label>
<?php
//Termina el bloque de datos adicionales.
}
?>
<?php
if (false) {
?>
<div class="salto1px"></div>
<table style="border:none;width:100%;">
<td>
<label class="L">
<?php
echo $ETI['unad01oir_ruv'];
?>
</label>
</td>
<td>
<?php
echo $html_unad01oir_ruv;
?>
</td>
</tr>
</table>
<?php
} else {
?>
<input id="unad01oir_ruv" name="unad01oir_ruv" type="hidden" value="<?php echo $_REQUEST['unad01oir_ruv']; ?>" />
<?php
}
if ($bDatosPersonales) {
?>
<?php
$bMensaje = false;
if ($_REQUEST['unad01autoriza_tel'] == '-1'){
	$bMensaje = true;
}
if ($_REQUEST['unad01autoriza_bol'] == '-1'){
	$bMensaje = true;
}
if ($_REQUEST['unad01autoriza_mat'] == '-1'){
	$bMensaje = true;
}
if ($_REQUEST['unad01autoriza_bien'] == '-1'){
	$bMensaje = true;
}
if ($bMensaje) {
?>
<div class="salto1px"></div>
<span class="rojo">Por favor responda a las preguntas para tratamiento de datos personales</span>
<?php
}
?>
<div class="salto1px"></div>
<div class="GrupoCamposAyuda">
<table style="border:none;width:100%;" class="no-hover">
<tr>
<td colspan="2" align="center">
<div class="TituloGrupo">
<?php
echo $ETI['msg_autorizaciones'];
?>
</div>
</td>
</tr>
<tr>
<td colspan="2">
<label class="L">
<?php
echo f17_InfoDatosPersonales(1);
?>
</label>
</td>
</tr>
<tr>
<td>
<label class="L">
<?php
echo f17_InfoDatosPersonales(21);
?>
</label>
</td>
<td style="max-width:150px;">
<?php
echo $html_unad01autoriza_tel;
?>
</td>
</tr>
<tr>
<td>
<label class="L">
<?php
echo f17_InfoDatosPersonales(23);
?>
</label>
</td>
<td style="max-width:150px;">
<?php
echo $html_unad01autoriza_mat;
?>
</td>
</tr>
<tr>
<td>
<label class="L">
<?php
echo f17_InfoDatosPersonales(24);
?>
</label>
</td>
<td style="max-width:150px;">
<?php
echo $html_unad01autoriza_bien;
?>
</td>
</tr>

<tr>
<td>
<label class="L">
<?php
echo f17_InfoDatosPersonales(22);
?>
</label>
</td>
<td>
<?php
echo $html_unad01autoriza_bol;
?>
</td>
</tr>
<tr>
<td colspan="2">
<label class="L">
<?php
echo f17_InfoDatosPersonales(2);
?>
</label>
</td>
</tr>
<tr>
</table>
<div class="salto1px"></div>
</div>
<?php
} else {
?>
<input id="unad01autoriza_tel" name="unad01autoriza_tel" type="hidden" value="<?php echo $_REQUEST['unad01autoriza_tel']; ?>" />
<input id="unad01autoriza_mat" name="unad01autoriza_mat" type="hidden" value="<?php echo $_REQUEST['unad01autoriza_mat']; ?>" />
<input id="unad01autoriza_bol" name="unad01autoriza_bol" type="hidden" value="<?php echo $_REQUEST['unad01autoriza_bol']; ?>" />
<input id="unad01autoriza_bien" name="unad01autoriza_bien" type="hidden" value="<?php echo $_REQUEST['unad01autoriza_bien']; ?>" />
<?php
}
?>
<div class="salto1px"></div>
<div class="GrupoCamposAyuda">
<label class="TituloGrupo">
<?php
echo $ETI['msg_lugarreside'];
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['unad11pais'];
?>
</label>
<label>
<?php
echo $html_unad11pais;
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['unad11deptodoc'];
?>
</label>
<label>
<div id="div_unad11deptodoc">
<?php
echo $html_unad11deptodoc;
?>
</div>
</label>
<label class="Label200">
<?php
echo $ETI['unad01zonares'];
?>
</label>
<label class="Label130">
<?php
echo $html_unad01zonares;
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['unad11ciudaddoc'];
?>
</label>
<label>
<div id="div_unad11ciudaddoc">
<?php
echo $html_unad11ciudaddoc;
?>
</div>
</label>
<label class="Label200">
<?php
echo $ETI['unad01estrato'];
?>
</label>
<label class="Label200">
<?php
echo $html_unad01estrato;
?>
</label>
<div class="salto1px"></div>
<label class="L">
<?php
echo $ETI['unad11direccion'];
?>
<input id="unad11direccion" name="unad11direccion" type="text" value="<?php echo $_REQUEST['unad11direccion']; ?>" maxlength="100" class="L" placeholder="<?php echo $ETI['ing_campo'] . $ETI['unad11direccion']; ?>" />
</label>
<div class="salto1px"></div>
</div>
<?php
if (false) {
?>
<div class="GrupoCamposAyuda">
<label class="TituloGrupo">
<?php
echo $ETI['msg_lugarestudia'];
?>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['unad11idzona'];
?>
</label>
<label>
<?php
echo $html_unad11idzona;
?>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['unad11idcead'];
?>
</label>
<label>
<div id="div_unad11idcead">
<?php
echo $html_unad11idcead;
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['unad11idescuela'];
?>
</label>
<label>
<?php
echo $html_unad11idescuela;
?>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['unad11idprograma'];
?>
</label>
<label>
<div id="div_unad11idprograma">
<?php
echo $html_unad11idprograma;
?>
</div>
</label>
<div class="salto1px"></div>
</div>
<?php
}
?>
<div class="salto1px"></div>
<label class="txtAreaM">
<?php
echo $ETI['unad11presentacion'];
?>
<textarea id="unad11presentacion" name="unad11presentacion" placeholder="<?php echo $ETI['ing_campo'] . $ETI['unad11presentacion']; ?>"><?php echo $_REQUEST['unad11presentacion']; ?></textarea>
</label>
</div>
<input id="unad11fechaactualiza" name="unad11fechaactualiza" type="hidden" value="<?php echo $_REQUEST['unad11fechaactualiza']; ?>" />
<div class="salto1px"></div>
<label class="Label160">&nbsp;</label>
<label class="Label130">
<?php
if ($bOtroUsuario) {
	$bPuedeGuardar = false;
}
if ($bForzarCorreo) {
	$bPuedeGuardar = false;
}
if ($bForzarClave) {
	$bPuedeGuardar = false;
}
if ($bPuedeGuardar) {
	echo $objForma->htmlBotonSolo('cmdGuardaNoti', 'btGuardarS azul', 'guardanoti()', 'Guardar informaci&oacute;n de perfil', 130, '', 'Guardar');
}
?>
</label>
<?php
if (!$bTipoGeneral) {
	$bPuedeVolver = true;
	if ($bForzarClave) {
		$bPuedeVolver = false;
	}
	if ($bForzarCorreo) {
		$bPuedeVolver = false;
	}
	if ($bPuedeVolver) {
?>
<label class="Label160">&nbsp;</label>
<?php
echo $objForma->htmlBotonSolo('cmdVolver2', 'BotonAzul', 'volver()', 'Volver al panel', 130, '', 'Volver');
?>
<?php
	}
} else {
// -- Inicia Grupo campos 107 Perfiles
?>
<div class="salto1px"></div>
<div class="GrupoCampos">
<div class="ir_derecha">
<?php
echo $objForma->htmlBotonSolo('cmdUpdPerfiles', 'botonActualizar', 'verperfiles()', 'Haga clic aqu&iacute; para verificar sus perfiles asignados.', 130, '', 'Verificar');
?>
<label class="Label60"></label>
</div>
<label class="TituloGrupo">
<?php
echo $ETI['titulo_107perfil'];
?>
</label>

<div class="salto1px"></div>
<div id="div_f107detalle">
<?php
echo $sTabla107;
?>
</div>
<div class="salto1px"></div>
</div>
<?php
// -- Termina Grupo campos 107 Perfiles
}
?>
<?php
if ($sInfo != '') {
?>
<div class="salto1px"></div>
<div class="GrupoCamposAyuda">
<div class="MarquesinaMedia">
<div align="center" class="verde">
<?php
echo $sInfo;
?>
</div>
</div>
<div class="salto1px"></div>
</div>
<?php
}
?>
<?php
// CIERRA EL DIV areatrabajo
?>
</div>
</div>
<div class="salto1px"></div>
<div class="ir_derecha">
</div>
<div id="div_tiempo" style="width:150px;"></div>
</div><!-- /DIV_Sector1 -->


<div id="div_sector2" style="display:none">
<?php
$objForma = new clsHtmlForma($iPiel);
if ($bBloqueTitulo) {
	$objForma->addBoton('cmdAyuda2', 'btSupAyuda', 'muestraayuda(' . $iCodModulo . ');', $ETI['bt_ayuda']);
	$objForma->addBoton('cmdVolverSec2', 'btSupVolver', 'retornacontrol();', $ETI['bt_volver']);
	echo $objForma->htmlTitulo($sTituloModulo, $iCodModulo);
}
echo $objForma->htmlInicioMarco();
?>
<div class="salto1px"></div>
<div class="GrupoCamposAyuda">
<?php
echo $ETI['msg_ayudacodigo'];
?>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['msg_codigo'];
?>
</label>
<label>
<input id="cod_confirma" name="cod_confirma" type="text" value="" placeholder="1234567890" />
</label>
<div class="salto1px"></div>
<label class="Label160">&nbsp;</label>
<?php
echo $objForma->htmlBotonSolo('cmdRevCodigo', 'btSoloProceso', 'verificar()', 'Verificar c&oacute;digo de acceso', 130, '', 'Verificar');
?>
<div class="salto1px"></div>
</div>
<?php
echo $objForma->htmlFinMarco();
?>
</div><!-- /DIV_Sector2 -->


<div id="div_sector93" style="display:none">
</div><!-- /DIV_Sector93 -->


<div id="div_sector95" style="display:none">
<?php
echo $objForma->htmlInicioMarco();
echo '<div id="div_95cuerpo"></div>';
echo $objForma->htmlFinMarco();
?>
</div><!-- /DIV_Sector95 -->


<div id="div_sector96" style="display:none">
<input id="div96v1" name="div96v1" type="hidden" value="" />
<input id="div96v2" name="div96v2" type="hidden" value="" />
<input id="div96v3" name="div96v3" type="hidden" value="" />
<input id="div96campo" name="div96campo" type="hidden" value="" />
<input id="div96llave" name="div96llave" type="hidden" value="" />
<input id="titulo_17" name="titulo_17" type="hidden" value="<?php echo $ETI['titulo_17']; ?>" />
<?php
$objForma=new clsHtmlForma($iPiel);
if ($bBloqueTitulo) {
	$objForma->addBoton('cmdAyuda96', 'btSupAyuda', 'muestraayuda(' . $iCodModulo . ');', $ETI['bt_ayuda']);
	$objForma->addBoton('cmdVolverSec96', 'btSupVolver', 'retornacontrol();', $ETI['bt_volver']);
	echo $objForma->htmlTitulo($sTituloModulo, $iCodModulo, 'div_96titulo');
}
echo $objForma->htmlInicioMarco();
echo '<div id="div_96cuerpo"></div>';
echo $objForma->htmlFinMarco();
?>
</div><!-- /DIV_Sector96 -->


<div id="div_sector97" style="display:none">
<?php
$objForma = new clsHtmlForma($iPiel);
if ($bBloqueTitulo) {
	$objForma->addBoton('cmdAyuda97', 'btSupAyuda', 'muestraayuda(' . $iCodModulo . ');', $ETI['bt_ayuda']);
	$objForma->addBoton('cmdVolverSec97', 'btSupVolver', 'retornacontrol();', $ETI['bt_volver']);
	echo $objForma->htmlTitulo($sTituloModulo, $iCodModulo, 'div_97titulo');
}
echo $objForma->htmlInicioMarco();
?>
<div id="div_97params"></div>
<div class="salto1px"></div>
<div id="div_97tabla"></div>
<?php
echo $objForma->htmlFinMarco();
?>
</div><!-- /DIV_Sector97 -->


<div id="div_sector98" style="display:none">
<?php
$objForma = new clsHtmlForma($iPiel);
if ($bBloqueTitulo) {
	$objForma->addBoton('cmdAyuda98', 'btSupAyuda', 'muestraayuda(' . $iCodModulo . ');', $ETI['bt_ayuda']);
	echo $objForma->htmlTitulo($sTituloModulo, $iCodModulo);
}
echo $objForma->htmlInicioMarco();
echo $objForma->htmlEspere($ETI['msg_espere']);
echo $objForma->htmlFinMarco();
?>
</div><!-- /DIV_Sector98 -->


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
<?php
echo $objForma->htmlBotonSolo('cmdGuardarf', 'btSoloGuardar', 'enviaguardar()', $ETI['bt_guardar']);
?>
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
if ($bPasaATablero) {
echo 'setTimeout(function(){volver();}, 3000);
';
}
?>
</script>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>unad_todas2024.js?ver=2"></script>
<?php
forma_piedepagina();