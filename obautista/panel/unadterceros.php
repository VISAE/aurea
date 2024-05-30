<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2014 - 2021 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- © Saul Alexander Hernandez Albarracin - UNAD - 2019 ---
--- saul.hernandez@unad.edu.co - http://www.unad.edu.co
--- Modelo Version 0.4.0 jueves, 06 de febrero de 2014
--- Modelo Version 0.8.0 miércoles, 19 de marzo de 2014
--- Modelo Versión 1.2.2 martes, 22 de julio de 2014
--- Modelo Versión 2.9.1 jueves, 30 de julio de 2015
--- Modelo Versión 2.19.7c viernes, 09 de febrero de 2018
--- Modelo Versión 2.21.0 jueves, 14 de junio de 2018
--- Modelo Versión 2.22.6 jueves, 15 de noviembre de 2018
--- Modelo Versión 2.25.7 martes, 15 de septiembre de 2020
--- Modelo Versión 2.25.10c martes, 3 de febrero de 2021
--- Modelo Versión 2.25.10c jueves, 11 de febrero de 2021
--- 13 de Agosto de 2021 - Se ajusta para no mostrar datos personales.

--- Este modulo debe mantener la compatibilidad con unadpersonas.php
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
$bCerrado = false;
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
$grupo_id = 0;
$iCodModulo = 111;
$bReducido = true;
$iCodModuloConsulta = $iCodModulo;
switch ($APP->idsistema) {
	case 1:
		$bReducido = false;
		break;
	case 30:
		$iCodModuloConsulta = 3093;
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
$mensajes_111 = $APP->rutacomun . 'lg/lg_111_' . $_SESSION['unad_idioma'] . '.php';
if (!file_exists($mensajes_111)) {
	$mensajes_111 = $APP->rutacomun . 'lg/lg_111_es.php';
}
require $mensajes_todas;
require $mensajes_111;
$xajax = NULL;
$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
if ($APP->dbpuerto != '') {
	$objDB->dbPuerto = $APP->dbpuerto;
}
$iPiel = iDefinirPiel($APP, 2);
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
		header('Location:noticia.php?ret=unadterceros.php');
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
// $idEntidad=0;
// -- Si esta cargando la pagina por primer vez se revisa si requiere auditar y se manda a hacer un limpiar (paso -1)
if (isset($_REQUEST['paso']) == 0) {
	$_REQUEST['paso'] = -1;
	if ($audita[1]) {
		seg_auditaingreso($iCodModulo, $_SESSION['unad_id_tercero'], $objDB);
	}
}
// -- 111 unad11terceros
require $APP->rutacomun . 'lib111.php';
$xajax = new xajax();
$xajax->configure('javascript URI', $APP->rutacomun . 'xajax/');
$xajax->register(XAJAX_FUNCTION, 'formatear_moneda');
$xajax->register(XAJAX_FUNCTION, 'Cargar_unad11deptoorigen');
$xajax->register(XAJAX_FUNCTION, 'Cargar_unad11ciudadorigen');
$xajax->register(XAJAX_FUNCTION, 'Cargar_unad11deptodoc');
$xajax->register(XAJAX_FUNCTION, 'Cargar_unad11ciudaddoc');
$xajax->register(XAJAX_FUNCTION, 'f111_Combounad11idcead');
$xajax->register(XAJAX_FUNCTION, 'f111_Combounad11idprograma');
$xajax->register(XAJAX_FUNCTION, 'sesion_abandona_V2');
$xajax->register(XAJAX_FUNCTION, 'sesion_mantenerV4');
$xajax->register(XAJAX_FUNCTION, 'sesion_retomar');
$xajax->register(XAJAX_FUNCTION, 'f111_HtmlTabla');
$xajax->register(XAJAX_FUNCTION, 'f111_ExisteDato');
$xajax->register(XAJAX_FUNCTION, 'f111_TraerInfoPersonal');
$xajax->register(XAJAX_FUNCTION, 'upd_dv');
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
$bEnviarMailConfirma = false;
// -- Se inicializan las variables, primero las que controlan la visualización de la página.
if (isset($_REQUEST['iscroll']) == 0) {
	$_REQUEST['iscroll'] = 0;
}
if (isset($_REQUEST['paginaf111']) == 0) {
	$_REQUEST['paginaf111'] = 1;
}
if (isset($_REQUEST['lppf111']) == 0) {
	$_REQUEST['lppf111'] = 20;
}
if (isset($_REQUEST['boculta111']) == 0) {
	$_REQUEST['boculta111'] = 0;
}
// -- Inicializar variables de datos.
if (isset($_REQUEST['unad11tipodoc']) == 0) {
	$_REQUEST['unad11tipodoc'] = $APP->tipo_doc;
}
if (isset($_REQUEST['unad11doc']) == 0) {
	$_REQUEST['unad11doc'] = '';
}
if (isset($_REQUEST['unad11id']) == 0) {
	$_REQUEST['unad11id'] = '';
}
if (isset($_REQUEST['unad11pais']) == 0) {
	$_REQUEST['unad11pais'] = $_SESSION['unad_pais'];
}
if (isset($_REQUEST['unad11usuario']) == 0) {
	$_REQUEST['unad11usuario'] = '';
}
if (isset($_REQUEST['unad11dv']) == 0) {
	$_REQUEST['unad11dv'] = '';
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
} //{fecha_hoy();}
if (isset($_REQUEST['unad11rh']) == 0) {
	$_REQUEST['unad11rh'] = '';
}
if (isset($_REQUEST['unad11ecivil']) == 0) {
	$_REQUEST['unad11ecivil'] = '';
}
if (isset($_REQUEST['unad11razonsocial']) == 0) {
	$_REQUEST['unad11razonsocial'] = '';
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
if (isset($_REQUEST['unad11sitioweb']) == 0) {
	$_REQUEST['unad11sitioweb'] = '';
}
if (isset($_REQUEST['unad11nacionalidad']) == 0) {
	$_REQUEST['unad11nacionalidad'] = $_SESSION['unad_pais'];
}
if (isset($_REQUEST['unad11deptoorigen']) == 0) {
	$_REQUEST['unad11deptoorigen'] = '';
}
if (isset($_REQUEST['unad11ciudadorigen']) == 0) {
	$_REQUEST['unad11ciudadorigen'] = '';
}
if (isset($_REQUEST['unad11deptodoc']) == 0) {
	$_REQUEST['unad11deptodoc'] = '';
}
if (isset($_REQUEST['unad11ciudaddoc']) == 0) {
	$_REQUEST['unad11ciudaddoc'] = '';
}
if (isset($_REQUEST['unad11clave']) == 0) {
	$_REQUEST['unad11clave'] = '';
}
if (isset($_REQUEST['unad11claveapps']) == 0) {
	$_REQUEST['unad11claveapps'] = '';
}
if (isset($_REQUEST['unad11fechaclaveapps']) == 0) {
	$_REQUEST['unad11fechaclaveapps'] = '';
} //{fecha_hoy();}
if (isset($_REQUEST['unad11bloqueado']) == 0) {
	$_REQUEST['unad11bloqueado'] = '';
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
if (isset($_REQUEST['unad11encuestafecha']) == 0) {
	$_REQUEST['unad11encuestafecha'] = 0;
}
if (isset($_REQUEST['unad11encuestaminuto']) == 0) {
	$_REQUEST['unad11encuestaminuto'] = 0;
}
if (isset($_REQUEST['unad11latgrados']) == 0) {
	$_REQUEST['unad11latgrados'] = '';
}
if (isset($_REQUEST['unad11latdecimas']) == 0) {
	$_REQUEST['unad11latdecimas'] = '';
}
if (isset($_REQUEST['unad11longrados']) == 0) {
	$_REQUEST['unad11longrados'] = '';
}
if (isset($_REQUEST['unad11longdecimas']) == 0) {
	$_REQUEST['unad11longdecimas'] = '';
}
if (isset($_REQUEST['unad11skype']) == 0) {
	$_REQUEST['unad11skype'] = '';
}
if (isset($_REQUEST['unad11mostrarcelular']) == 0) {
	$_REQUEST['unad11mostrarcelular'] = 'N';
}
if (isset($_REQUEST['unad11mostrarcorreo']) == 0) {
	$_REQUEST['unad11mostrarcorreo'] = 'N';
}
if (isset($_REQUEST['unad11mostrarskype']) == 0) {
	$_REQUEST['unad11mostrarskype'] = 'N';
}
if (isset($_REQUEST['unad11fechaterminos']) == 0) {
	$_REQUEST['unad11fechaterminos'] = '';
}
if (isset($_REQUEST['unad11noubicar']) == 0) {
	$_REQUEST['unad11noubicar'] = 0;
}
if (isset($_REQUEST['unad11fechaconfmail']) == 0) {
	$_REQUEST['unad11fechaconfmail'] = 0;
}
if (isset($_REQUEST['unad11exluirdobleaut']) == 0) {
	$_REQUEST['unad11exluirdobleaut'] = 'N';
}
if (isset($_REQUEST['unad11presentacion']) == 0) {
	$_REQUEST['unad11presentacion'] = '';
}
if (isset($_REQUEST['unad11fechaclave']) == 0) {
	$_REQUEST['unad11fechaclave'] = '';
} //{fecha_hoy();}
if (isset($_REQUEST['unad11fechaultingreso']) == 0) {
	$_REQUEST['unad11fechaultingreso'] = '';
} //{fecha_hoy();}
if (isset($_REQUEST['unad11correofuncionario']) == 0) {
	$_REQUEST['unad11correofuncionario'] = '';
}
if (isset($_REQUEST['unad11fechadoc']) == 0) {
	$_REQUEST['unad11fechadoc'] = '';
} //{fecha_hoy();}
if (isset($_REQUEST['unad01estrato']) == 0) {
	$_REQUEST['unad01estrato'] = '';
}
if (isset($_REQUEST['unad01zonares']) == 0) {
	$_REQUEST['unad01zonares'] = '';
}
if (isset($_REQUEST['unae23idexterno']) == 0) {
	$_REQUEST['unae23idexterno'] = 0;
}
// Espacio para inicializar otras variables
if (isset($_REQUEST['csv_separa']) == 0) {
	$_REQUEST['csv_separa'] = ';';
}
if (isset($_REQUEST['bdoc']) == 0) {
	$_REQUEST['bdoc'] = '';
}
if (isset($_REQUEST['bnombre']) == 0) {
	$_REQUEST['bnombre'] = '';
}
if (isset($_REQUEST['busuario']) == 0) {
	$_REQUEST['busuario'] = '';
}
if (isset($_REQUEST['bcorreo']) == 0) {
	$_REQUEST['bcorreo'] = '';
}
if (isset($_REQUEST['bcampo']) == 0) {
	$_REQUEST['bcampo'] = '';
}
if (isset($_REQUEST['badicional']) == 0) {
	$_REQUEST['badicional'] = '';
}
if (isset($_REQUEST['bmatricula']) == 0) {
	$_REQUEST['bmatricula'] = '';
}
if (isset($_REQUEST['bconvenio']) == 0) {
	$_REQUEST['bconvenio'] = '';
}
if (isset($_REQUEST['bdesde']) == 0) {
	$_REQUEST['bdesde'] = '0';
}
if (isset($_REQUEST['bhasta']) == 0) {
	$_REQUEST['bhasta'] = '0';
}
if (isset($_REQUEST['b236']) == 0) {
	$_REQUEST['b236'] = 0;
}
//Si Modifica o Elimina Cargar los campos
if (($_REQUEST['paso'] == 1) || ($_REQUEST['paso'] == 3)) {
	if ($_REQUEST['paso'] == 1) {
		$sSQLcondi = 'unad11doc="' . $_REQUEST['unad11doc'] . '" AND unad11tipodoc="' . $_REQUEST['unad11tipodoc'] . '"';
	} else {
		$sSQLcondi = 'unad11id=' . $_REQUEST['unad11id'] . '';
	}
	$sSQL = 'SELECT * FROM unad11terceros WHERE ' . $sSQLcondi;
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) == 0) {
		//Si no se encuentra y viene por documento habria que importarlo...
		if ($_REQUEST['paso'] == 1) {
			if ($_REQUEST['unad11tipodoc'] == 'CC') {
				unad11_importar_V2($_REQUEST['unad11doc'], '', $objDB);
				$tabla = $objDB->ejecutasql($sSQL);
			}
		}
	}
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$_REQUEST['unad11tipodoc'] = $fila['unad11tipodoc'];
		$_REQUEST['unad11doc'] = $fila['unad11doc'];
		$_REQUEST['unad11id'] = $fila['unad11id'];
		$_REQUEST['unad11pais'] = $fila['unad11pais'];
		$_REQUEST['unad11usuario'] = $fila['unad11usuario'];
		$_REQUEST['unad11dv'] = $fila['unad11dv'];
		$_REQUEST['unad11nombre1'] = $fila['unad11nombre1'];
		$_REQUEST['unad11nombre2'] = $fila['unad11nombre2'];
		$_REQUEST['unad11apellido1'] = $fila['unad11apellido1'];
		$_REQUEST['unad11apellido2'] = $fila['unad11apellido2'];
		$_REQUEST['unad11genero'] = $fila['unad11genero'];
		$_REQUEST['unad11fechanace'] = $fila['unad11fechanace'];
		$_REQUEST['unad11rh'] = $fila['unad11rh'];
		$_REQUEST['unad11ecivil'] = $fila['unad11ecivil'];
		$_REQUEST['unad11razonsocial'] = $fila['unad11razonsocial'];
		$_REQUEST['unad11direccion'] = $fila['unad11direccion'];
		$_REQUEST['unad11telefono'] = $fila['unad11telefono'];
		$_REQUEST['unad11correo'] = $fila['unad11correo'];
		$_REQUEST['unad11sitioweb'] = $fila['unad11sitioweb'];
		$_REQUEST['unad11nacionalidad'] = $fila['unad11nacionalidad'];
		$_REQUEST['unad11deptoorigen'] = $fila['unad11deptoorigen'];
		$_REQUEST['unad11ciudadorigen'] = $fila['unad11ciudadorigen'];
		$_REQUEST['unad11deptodoc'] = $fila['unad11deptodoc'];
		$_REQUEST['unad11ciudaddoc'] = $fila['unad11ciudaddoc'];
		$_REQUEST['unad11clave'] = $fila['unad11clave'];
		$_REQUEST['unad11claveapps'] = $fila['unad11claveapps'];
		$_REQUEST['unad11fechaclaveapps'] = $fila['unad11fechaclaveapps'];
		$_REQUEST['unad11bloqueado'] = $fila['unad11bloqueado'];
		$_REQUEST['unad11aceptanotificacion'] = $fila['unad11aceptanotificacion'];
		$_REQUEST['unad11correonotifica'] = $fila['unad11correonotifica'];
		$_REQUEST['unad11correoinstitucional'] = $fila['unad11correoinstitucional'];
		$_REQUEST['unad11encuestafecha'] = $fila['unad11encuestafecha'];
		$_REQUEST['unad11encuestaminuto'] = $fila['unad11encuestaminuto'];
		$_REQUEST['unad11latgrados'] = $fila['unad11latgrados'];
		$_REQUEST['unad11latdecimas'] = $fila['unad11latdecimas'];
		$_REQUEST['unad11longrados'] = $fila['unad11longrados'];
		$_REQUEST['unad11longdecimas'] = $fila['unad11longdecimas'];
		$_REQUEST['unad11skype'] = $fila['unad11skype'];
		$_REQUEST['unad11mostrarcelular'] = $fila['unad11mostrarcelular'];
		$_REQUEST['unad11mostrarcorreo'] = $fila['unad11mostrarcorreo'];
		$_REQUEST['unad11mostrarskype'] = $fila['unad11mostrarskype'];
		$_REQUEST['unad11fechaterminos'] = $fila['unad11fechaterminos'];
		$_REQUEST['unad11noubicar'] = $fila['unad11noubicar'];
		$_REQUEST['unad11fechaconfmail'] = $fila['unad11fechaconfmail'];
		$_REQUEST['unad11exluirdobleaut'] = $fila['unad11exluirdobleaut'];
		$_REQUEST['unad11presentacion'] = str_replace('<br />', '', $fila['unad11presentacion']);
		$_REQUEST['unad11presentacion'] = str_replace('<br/>', '', $_REQUEST['unad11presentacion']);
		$_REQUEST['unad11presentacion'] = str_replace('<br>', '', $_REQUEST['unad11presentacion']);
		$_REQUEST['unad11fechaclave'] = $fila['unad11fechaclave'];
		$_REQUEST['unad11fechaultingreso'] = $fila['unad11fechaultingreso'];
		$_REQUEST['unad11correofuncionario'] = $fila['unad11correofuncionario'];
		$_REQUEST['unad11fechadoc'] = $fila['unad11fechadoc'];
		$_REQUEST['unad01estrato'] = $fila['unad01estrato'];
		$_REQUEST['unad01zonares'] = $fila['unad01zonares'];
		$sSQL = 'SELECT * FROM unae23infoexterno WHERE unae23idtercero=' . $_REQUEST['unad11id'] . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) == 0) {
			$_REQUEST['unae23idexterno'] = 0;
		} else {
			$fila = $objDB->sf($tabla);
			$_REQUEST['unae23idexterno'] = $fila['unae23idexterno'];
		}
		$bcargo = true;
		$_REQUEST['paso'] = 2;
		$_REQUEST['boculta111'] = 0;
		$bLimpiaHijos = true;
		$_REQUEST['b236'] = 0;
	} else {
		$_REQUEST['paso'] = 0;
	}
}
//Insertar o modificar un elemento
if (($_REQUEST['paso'] == 10) || ($_REQUEST['paso'] == 12)) {
	$bMueveScroll = true;
	list($_REQUEST, $sError, $iTipoError, $sDebugGuardar) = f111_db_GuardarV2($_REQUEST, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugGuardar;
	if ($sError == '') {
		$sError = '<b>' . $ETI['msg_itemguardado'] . '</b>';
		$iTipoError = 1;
	}
}
//Eliminar un elemento
if ($_REQUEST['paso'] == 13) {
	$_REQUEST['paso'] = 2;
	$_REQUEST['unad11tipodoc'] = htmlspecialchars(trim($_REQUEST['unad11tipodoc']));
	$_REQUEST['unad11doc'] = htmlspecialchars(trim($_REQUEST['unad11doc']));
	$_REQUEST['unad11id'] = numeros_validar($_REQUEST['unad11id']);
	$sError = $ERR['4'];
	if ($sError == '') {
		if (!seg_revisa_permiso($iCodModulo, 4, $objDB)) {
			$sError = $ERR['4'];
		}
	}
	if ($sError == '') {
		$sWhere = 'unad11id=' . $_REQUEST['unad11id'] . '';
		//$sWhere='unad11tipodoc="'.$_REQUEST['unad11tipodoc'].'" AND unad11doc="'.$_REQUEST['unad11doc'].'"';
		$sSQL = 'DELETE FROM unad11terceros WHERE ' . $sWhere . ';';
		$result = $objDB->ejecutasql($sSQL);
		if ($result == false) {
			$sError = $ERR['falla_eliminar'] . ' .. <!-- ' . $sSQL . ' -->';
		} else {
			if ($audita[4]) {
				seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $_REQUEST['unad11id'], $sWhere, $objDB);
			}
			$_REQUEST['paso'] = -1;
			$sError = $ETI['msg_itemeliminado'];
			$iTipoError = 1;
		}
	}
}
//Enviar el codigo de acceso.
if ($_REQUEST['paso'] == 22) {
	$_REQUEST['paso'] = 2;
	$bMueveScroll = true;
	list($aure01codigo, $sError, $sDebug) = AUREA_IniciarLogin($_REQUEST['unad11id'], $objDB, '', 2, $bDebug);
	if ($sError == '') {
		$sError = $ETI['msg_codigoenviado'];
		$iTipoError = 1;
		$bConBitacoras = false;
		if ($bConBitacoras) {
			require $APP->rutacomun . 'libs/cls1504.php';
			$objBitacora = new clsT1504();
			$objBitacora->nuevo(4);
			$objBitacora->bita04idsolicita = $_REQUEST['unad11id'];
			list($sErrorB, $iTipoErrorB, $idAccion, $sDebugB) = $objBitacora->guardar($objDB, $bDebug);
			if ($iTipoErrorB == 0) {
				$sError = $sError . '<br>ERROR AL GENERAR LA BITACORA: ' . $sErrorB;
			} else {
				$sError = $sError . '<br>Se ha generado la bitacora ' . $objBitacora->bita04consec;
			}
		}
	}
}
//Actualizar los datos de RCA
if ($_REQUEST['paso'] == 23) {
	$_REQUEST['paso'] = 2;
	$bMueveScroll = true;
	//Es posible que esto se deba llevar a una funcion...
	list($objDBRyC, $sDebug) = TraerDBRyCV2();
	if ($objDBRyC != NULL) {
		$sSQL = 'SELECT usuario FROM usuarios_sii WHERE documento="' . $_REQUEST['unad11doc'] . '"';
		$tablar = $objDBRyC->ejecutasql($sSQL);
		if ($objDBRyC->nf($tablar) > 0) {
			$filar = $objDBRyC->sf($tablar);
			if ($_REQUEST['unad11usuario'] != $filar['usuario']) {
				$sSQL = 'UPDATE unad11terceros SET unad11usuario="' . $filar['usuario'] . '" WHERE unad11id=' . $_REQUEST['unad11id'] . '';
				$tabla = $objDB->ejecutasql($sSQL);
				seg_auditar(111, $_SESSION['unad_id_tercero'], 3, $_REQUEST['unad11id'], 'Actualiza el usuario de ' . $_REQUEST['unad11usuario'] . ' a ' . $filar['usuario'] . '', $objDB);
				$_REQUEST['unad11usuario'] = $filar['usuario'];
				$iTipoError = 2;
				$sError = 'Se ha actualizado el usuario.';
			}
		}
		$objDBRyC->CerrarConexion();
	}
	//
}
if ($_REQUEST['paso'] == 24) {
	$_REQUEST['paso'] = 2;
	$bMueveScroll = true;
	$bDevuelve = false;
	list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 3, $_SESSION['unad_id_tercero'], $objDB);
	if ($bDevuelve) {
		$sSQL = 'UPDATE unad11terceros SET unad11fechaconfmail=0 WHERE unad11id=' . $_REQUEST['unad11id'] . '';
		$tabla = $objDB->ejecutasql($sSQL);
		seg_auditar(111, $_SESSION['unad_id_tercero'], 3, $_REQUEST['unad11id'], 'Retira la marca de confirmación del correo', $objDB);
		$_REQUEST['unad11fechaconfmail'] = 0;
		$sError = 'Se ha retirado la confirmaci&oacute;n del correo de notificaciones.';
		$iTipoError = 1;
	} else {
		$sError = 'No tiene permisos para hacer modificaciones en este modulo.';
	}
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
//limpiar la pantalla
if ($_REQUEST['paso'] == -1) {
	$_REQUEST['unad11tipodoc'] = $APP->tipo_doc;
	$_REQUEST['unad11doc'] = '';
	$_REQUEST['unad11id'] = '';
	$_REQUEST['unad11pais'] = $_SESSION['unad_pais'];
	$_REQUEST['unad11usuario'] = '';
	$_REQUEST['unad11dv'] = '';
	$_REQUEST['unad11nombre1'] = '';
	$_REQUEST['unad11nombre2'] = '';
	$_REQUEST['unad11apellido1'] = '';
	$_REQUEST['unad11apellido2'] = '';
	$_REQUEST['unad11genero'] = '';
	$_REQUEST['unad11fechanace'] = ''; //fecha_hoy();
	$_REQUEST['unad11rh'] = '';
	$_REQUEST['unad11ecivil'] = '';
	$_REQUEST['unad11razonsocial'] = '';
	$_REQUEST['unad11direccion'] = '';
	$_REQUEST['unad11telefono'] = '';
	$_REQUEST['unad11correo'] = '';
	$_REQUEST['unad11sitioweb'] = '';
	$_REQUEST['unad11nacionalidad'] = $_SESSION['unad_pais'];
	$_REQUEST['unad11deptoorigen'] = '';
	$_REQUEST['unad11ciudadorigen'] = '';
	$_REQUEST['unad11deptodoc'] = '';
	$_REQUEST['unad11ciudaddoc'] = '';
	$_REQUEST['unad11clave'] = '';
	$_REQUEST['unad11claveapps'] = '';
	$_REQUEST['unad11fechaclaveapps'] = '';
	$_REQUEST['unad11bloqueado'] = '';
	$_REQUEST['unad11aceptanotificacion'] = 'P';
	$_REQUEST['unad11correonotifica'] = '';
	$_REQUEST['unad11correoinstitucional'] = '';
	$_REQUEST['unad11encuestafecha'] = 0;
	$_REQUEST['unad11encuestaminuto'] = 0;
	$_REQUEST['unad11latgrados'] = '';
	$_REQUEST['unad11latdecimas'] = '';
	$_REQUEST['unad11longrados'] = '';
	$_REQUEST['unad11longdecimas'] = '';
	$_REQUEST['unad11skype'] = '';
	$_REQUEST['unad11mostrarcelular'] = 'N';
	$_REQUEST['unad11mostrarcorreo'] = 'N';
	$_REQUEST['unad11mostrarskype'] = 'N';
	$_REQUEST['unad11fechaterminos'] = '';
	$_REQUEST['unad11noubicar'] = 0;
	$_REQUEST['unad11fechaconfmail'] = 0;
	$_REQUEST['unad11exluirdobleaut'] = 'N';
	$_REQUEST['unad11presentacion'] = '';
	$_REQUEST['unad11fechaclave'] = ''; //fecha_hoy();
	$_REQUEST['unad11fechaultingreso'] = ''; //fecha_hoy();
	$_REQUEST['unad11correofuncionario'] = '';
	$_REQUEST['unad11fechadoc'] = 0;
	$_REQUEST['unad01estrato'] = 0;
	$_REQUEST['unad01zonares'] = '';
	$_REQUEST['unae23idexterno'] = 0;
	$_REQUEST['paso'] = 0;
	$_REQUEST['b236'] = 1;
}
if ($bLimpiaHijos) {
}
//AQUI SE DEBEN CARGAR TODOS LOS DATOS QUE LA FORMA NECESITE.
//DATOS PARA COMPLETAR EL FORMULARIO
//Permisos adicionales
$bHayImprimir = false;
$bPuedeGuardar = true;
$sScript = 'imprimeexcel()';
$sClaseBoton = 'btEnviarExcel';
$seg_5 = 0;
$seg_6 = 0;
$seg_111 = 0;
list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 6, $idTercero, $objDB);
if ($bDevuelve) {
	$seg_6 = 1;
}
$bVentanaTerceros = true;
$bConInfoPersonal = true;
//Crear los controles que requieran llamado a base de datos
$objCombos = new clsHtmlCombos();
$unad11bloqueado_nombre = $ETI['msg_nobloqueado'];
if ($_REQUEST['unad11bloqueado'] == 'S') {
	$unad11bloqueado_nombre = $ETI['msg_bloqueado'];
}
$html_unad11genero = html_combo('unad11genero', 'unad22codopcion', 'unad22nombre', 'unad22combos', 'unad22idmodulo=111 AND unad22consec=1 AND unad22activa="S"', 'unad22orden', $_REQUEST['unad11genero'], $objDB, '', true, '{' . $ETI['msg_na'] . '}', '');
$html_unad11rh = html_combo('unad11rh', 'unad22codopcion', 'unad22nombre', 'unad22combos', 'unad22idmodulo=111 AND unad22consec=2 AND unad22activa="S"', 'unad22orden', $_REQUEST['unad11rh'], $objDB, '', true, '{' . $ETI['msg_na'] . '}', '');
$html_unad11ecivil = html_combo('unad11ecivil', 'unad21codigo', 'unad21nombre', 'unad21estadocivil', '', 'unad21orden', $_REQUEST['unad11ecivil'], $objDB, '', true, '{' . $ETI['msg_na'] . '}', '');
$html_unad11nacionalidad = html_combo('unad11nacionalidad', 'unad18codigo', 'unad18nombre', 'unad18pais', '', 'unad18nombre', $_REQUEST['unad11nacionalidad'], $objDB, 'carga_combo_unad11deptoorigen();', true, '{' . $ETI['msg_seleccione'] . '}', '');
$html_unad11deptoorigen = html_combo_unad11deptoorigen($objDB, $_REQUEST['unad11deptoorigen'], $_REQUEST['unad11nacionalidad']);
$html_unad11deptodoc = html_combo_unad11deptodoc($objDB, $_REQUEST['unad11deptodoc'], $_REQUEST['unad11pais']);
$html_unad11ciudadorigen = html_combo_unad11ciudadorigen($objDB, $_REQUEST['unad11ciudadorigen'], $_REQUEST['unad11deptoorigen']);
$html_unad11ciudaddoc = html_combo_unad11ciudaddoc($objDB, $_REQUEST['unad11ciudaddoc'], $_REQUEST['unad11deptodoc']);
$html_unad11bloqueado = html_oculto('unad11bloqueado', $_REQUEST['unad11bloqueado'], $unad11bloqueado_nombre);
switch ($_REQUEST['unad11aceptanotificacion']) {
	case 'S':
		$unad11aceptanotificacion_nombre = $ETI['si'];
		break;
	case 'N':
		$unad11aceptanotificacion_nombre = $ETI['no'];
		break;
	default:
		$unad11aceptanotificacion_nombre = $ETI['msg_pendiente'];
		break;
}
$html_unad11aceptanotificacion = html_oculto('unad11aceptanotificacion', $_REQUEST['unad11aceptanotificacion'], $unad11aceptanotificacion_nombre);
if ($bVentanaTerceros) {
	$objCombos->nuevo('unad11mostrarcelular', $_REQUEST['unad11mostrarcelular'], false);
	$objCombos->sino($ETI['si'], $ETI['no']);
	$html_unad11mostrarcelular = $objCombos->html('', $objDB);
	$objCombos->nuevo('unad11mostrarcorreo', $_REQUEST['unad11mostrarcorreo'], false);
	$objCombos->sino($ETI['si'], $ETI['no']);
	$html_unad11mostrarcorreo = $objCombos->html('', $objDB);
	$objCombos->nuevo('unad11mostrarskype', $_REQUEST['unad11mostrarskype'], false);
	$objCombos->sino($ETI['si'], $ETI['no']);
	$html_unad11mostrarskype = $objCombos->html('', $objDB);
} else {
	if ($_REQUEST['unad11mostrarcelular'] == 'S') {
		$unad11mostrarcelular_nombre = $ETI['si'];
	} else {
		$unad11mostrarcelular_nombre = $ETI['no'];
	}
	$html_unad11mostrarcelular = html_oculto('unad11mostrarcelular', $_REQUEST['unad11mostrarcelular'], $unad11mostrarcelular_nombre);
	if ($_REQUEST['unad11mostrarcorreo'] == 'S') {
		$unad11mostrarcorreo_nombre = $ETI['si'];
	} else {
		$unad11mostrarcorreo_nombre = $ETI['no'];
	}
	$html_unad11mostrarcorreo = html_oculto('unad11mostrarcorreo', $_REQUEST['unad11mostrarcorreo'], $unad11mostrarcorreo_nombre);
	if ($_REQUEST['unad11mostrarskype'] == 'S') {
		$unad11mostrarskype_nombre = $ETI['si'];
	} else {
		$unad11mostrarskype_nombre = $ETI['no'];
	}
	$html_unad11mostrarskype = html_oculto('unad11mostrarskype', $_REQUEST['unad11mostrarskype'], $unad11mostrarskype_nombre);
}
if ($bReducido) {
} else {
	$eti_unad11noubicar = $ETI['no'];
	if ($_REQUEST['unad11noubicar'] == 1) {
		$eti_unad11noubicar = $ETI['si'];
	}
	$html_unad11noubicar = html_oculto('unad11noubicar', $_REQUEST['unad11noubicar'], $eti_unad11noubicar);
	/*
	$objCombos->nuevo('unad11noubicar', $_REQUEST['unad11noubicar'], false, '{'.$ETI['msg_seleccione'].'}');
	$objCombos->addItem('0', 'No');
	$objCombos->addItem('1', 'Si');
	$html_unad11noubicar=$objCombos->html('', $objDB);
	*/
}
$et_unad11exluirdobleaut = $ETI['no'];
if ($_REQUEST['unad11exluirdobleaut'] == 'S') {
	$et_unad11exluirdobleaut = $ETI['si'];
}
$html_unad11exluirdobleaut = html_oculto('unad11exluirdobleaut', $_REQUEST['unad11exluirdobleaut'], $et_unad11exluirdobleaut);
$html_unad11pais = html_combo('unad11pais', 'unad18codigo', 'unad18nombre', 'unad18pais', '', 'unad18nombre', $_REQUEST['unad11pais'], $objDB, 'carga_combo_unad11deptoorigen()', true, '{' . $ETI['msg_seleccione'] . '}', '');
$objCombos->nuevo('unad01estrato', $_REQUEST['unad01estrato'], true, $aunad01estrato[0], 0);
$objCombos->addArreglo($aunad01estrato, $iunad01estrato);
$html_unad01estrato = $objCombos->html('', $objDB);
$objCombos->nuevo('unad01zonares', $_REQUEST['unad01zonares'], true, '{' . $ETI['msg_seleccione'] . '}', '');
$objCombos->AddItem('U', $aunad01zonares[1]);
$objCombos->AddItem('R', $aunad01zonares[2]);
$html_unad01zonares = $objCombos->html('', $objDB);
//Alistar datos adicionales
$objCombos->nuevo('bcampo', $_REQUEST['bcampo'], true, 'Todos los correos');
$objCombos->addItem('1', 'Correo personal');
$objCombos->addItem('2', 'Correo notificaciones');
$objCombos->addItem('3', 'Correo institucional');
$objCombos->addItem('4', 'Correo funcionario');
$objCombos->sAccion = 'paginarf111()';
$html_bcampo = $objCombos->html('', $objDB);
$objCombos->nuevo('badicional', $_REQUEST['badicional'], true, '{Todos}');
$objCombos->addItem('1', 'Correos confirmados');
$objCombos->addItem('2', 'Correos SIN confirmar');
$objCombos->sAccion = 'paginarf111()';
$html_badicional = $objCombos->html('', $objDB);
$objCombos->nuevo('bmatricula', $_REQUEST['bmatricula'], true, '{Todos}');
$objCombos->sAccion = 'paginarf111()';
$sSQL = f146_ConsultaCombo(2216, $objDB);
$html_bmatricula = $objCombos->html($sSQL, $objDB);
$objCombos->nuevo('bconvenio', $_REQUEST['bconvenio'], true, '{' . $ETI['msg_todos'] . '}');
$objCombos->sAccion = 'paginarf111()';
$sSQL = 'SELECT core50id AS id, core50nombre AS nombre FROM core50convenios ORDER BY core50estado DESC, core50nombre';
$html_bconvenio = $objCombos->html($sSQL, $objDB);
$id_rpt = 0;
if (true) {
	$objCombos->nuevo('csv_separa', $_REQUEST['csv_separa'], false);
	$objCombos->addItem(',', $ETI['msg_coma']);
	$objCombos->addItem(';', $ETI['msg_puntoycoma']);
	$sClaseLabel = 'Label90';
	if ($iPiel == 2) {
		$sClaseLabel = 'w-15';
	}
	$csv_separa = '<label class="' . $sClaseLabel . '">' . $ETI['msg_separador'] . '</label><label class="' . $sClaseLabel . '">' . $objCombos->html('', $objDB) . '</label>';
} else {
	$csv_separa = '<input id="csv_separa" name="csv_separa" type="hidden" value="," />';
}
$iNumFormatosImprime = 0;
$iModeloReporte = 111;
$html_iFormatoImprime = '<input id="iformatoimprime" name="iformatoimprime" type="hidden" value="0" />
';
$sCorreoMensajes = '';
$bCorreoDisponible = true;
$bTieneAdvertenciasSeguridad = false;
if ($_REQUEST['paso'] > 0) {
	$sSQL = 'SELECT 1 FROM unae13enrevision WHERE unae13idtercero=' . $_REQUEST['unad11id'] . ' AND unae13estado IN (0, 1)';
	$tabla = $objDB->ejecutasql($sSQL);
	$iTotalSospechas = $objDB->nf($tabla);
	if ($iTotalSospechas > 0) {
		$bTieneAdvertenciasSeguridad = true;
	}
	if (!$bVentanaTerceros) {
		if ($_REQUEST['unad11fechaconfmail'] == 0) {
		} else {
			$bCorreoDisponible = false;
		}
	}
	if (seg_revisa_permiso($iCodModulo, 5, $objDB)) {
		$seg_5 = 1;
	}
	if (seg_revisa_permiso($iCodModulo, 111, $objDB)) {
		$seg_111 = 1;
		list($sCorreoMensajes, $sErrorN, $sDebugM) = AUREA_CorreoNotifica($_REQUEST['unad11id'], $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugM;
		if ($sError != '') {
			if ($sErrorN != '') {
				$sError = $sError . '<br>';
			}
		}
		$sError = $sError . $sErrorN;
	}
	list($sCorreoUsuario, $sErrorC, $sDebugC, $sCorreoInstitucional) = AUREA_CorreoPrimario($_REQUEST['unad11id'], $objDB, $bDebug);
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' Correo principal: <b>' . $sCorreoUsuario . '</b>, Correo Institucional: <b>' . $sCorreoInstitucional . '</b><br>';
	}
	if ($_REQUEST['unad11id'] != $_SESSION['unad_id_tercero']) {
		$bConInfoPersonal = false;
	} else {
		if ($_REQUEST['b236'] == 0) {
			$bConInfoPersonal = false;
		}
	}
}
$bConInfoPersonal = true;
//Cargar las tablas de datos
$aParametros[0] = ''; //$_REQUEST['p1_111'];
$aParametros[101] = $_REQUEST['paginaf111'];
$aParametros[102] = $_REQUEST['lppf111'];
$aParametros[103] = $_REQUEST['bdoc'];
$aParametros[104] = $_REQUEST['bnombre'];
$aParametros[105] = $_REQUEST['busuario'];
$aParametros[106] = $_REQUEST['bcorreo'];
$aParametros[107] = $_REQUEST['bcampo'];
$aParametros[108] = $_REQUEST['badicional'];
$aParametros[109] = $_REQUEST['bmatricula'];
$aParametros[110] = $_REQUEST['bconvenio'];
$aParametros[111] = $_REQUEST['bdesde'];
$aParametros[112] = $_REQUEST['bhasta'];
list($sTabla111, $sDebugTabla) = f111_TablaDetalleV2($aParametros, $objDB, $bDebug);
$sDebug = $sDebug . $sDebugTabla;
$bDebugMenu = false;
switch ($iPiel) {
	case 2:
		list($et_menu, $sDebugM) = html_Menu2023($APP->idsistema, $objDB, $iPiel, $bDebugMenu, $idTercero);
		break;
	default:
		list($et_menu, $sDebugM) = html_menuV2($APP->idsistema, $objDB, $iPiel, $bDebugMenu, $idTercero);
		break;
}
$sDebug = $sDebug . $sDebugM;
$sTituloApp = f101_SiglaModulo($APP->idsistema, $objDB);
$objDB->CerrarConexion();
//FORMA
switch ($iPiel) {
	case 2:
		require $APP->rutacomun . 'unad_forma2023.php';
		forma_InicioV4($xajax, $sTituloModulo);
		$bBloqueTitulo = false;
		break;
	default:
		require $APP->rutacomun . 'unad_forma_v2.php';
		forma_cabeceraV3($xajax, $ETI['titulo_111']);
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
			array('./', $sTituloApp),
			array('./gm.php?id=' . $grupo_id, $ETI['grupo_nombre_111']),
			array('', $ETI['titulo_111'])
		);
		$iNumBoton = 0;
		$aBotones[$iNumBoton] = array('muestraayuda(' . $APP->idsistema . ', ' . $iCodModulo . ')', $ETI['bt_ayuda'], 'iHelp');
		$iNumBoton++;
		if ($bHayImprimir) {
			$aBotones[$iNumBoton] = array($sScriptImprime, $ETI['bt_imprimir'], $sClaseImprime);
			$iNumBoton++;
		}
		$aBotones[$iNumBoton] = array('limpiapagina()', $ETI['bt_limpiar'], 'iDocument');
		$iNumBoton++;
		if ($bPuedeGuardar) {
			$aBotones[$iNumBoton] = array('enviaguardar()', $ETI['bt_guardar'], 'iSaveFill');
			$iNumBoton++;
		}
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
	function limpiapagina() {
		expandesector(98);
		window.document.frmedita.paso.value = -1;
		window.document.frmedita.submit();
	}

	function enviaguardar() {
		window.document.frmedita.iscroll.value = window.scrollY;
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
		document.getElementById('div_sector95').style.display = 'none';
		document.getElementById('div_sector96').style.display = 'none';
		document.getElementById('div_sector98').style.display = 'none';
		document.getElementById('div_sector' + codigo).style.display = 'block';
		var sEst = 'none';
		if (codigo == 1) {
			sEst = 'block';
		}
		document.getElementById('cmdGuardarf').style.display = sEst;
	}

	function imprimelista() {
		window.document.frmlista.consulta.value = window.document.frmedita.consulta_111.value;
		window.document.frmlista.titulos.value = window.document.frmedita.titulos_111.value;
		window.document.frmlista.csv_separa.value = window.document.frmedita.csv_separa.value;
		window.document.frmlista.nombrearchivo.value = 'Terceros';
		window.document.frmlista.submit();
	}

	function asignarvariables() {
		window.document.frmimpp.v3.value = window.document.frmedita.bdoc.value;
		window.document.frmimpp.v4.value = window.document.frmedita.bnombre.value;
		window.document.frmimpp.v5.value = window.document.frmedita.busuario.value;
		window.document.frmimpp.v6.value = window.document.frmedita.bcorreo.value;
		window.document.frmimpp.v7.value = window.document.frmedita.bcampo.value;
		window.document.frmimpp.v8.value = window.document.frmedita.badicional.value;
		window.document.frmimpp.v9.value = window.document.frmedita.bmatricula.value;
		window.document.frmimpp.v10.value = window.document.frmedita.bconvenio.value;
		window.document.frmimpp.v11.value = window.document.frmedita.bdesde.value;
		window.document.frmimpp.v12.value = window.document.frmedita.bhasta.value;
		window.document.frmimpp.separa.value = window.document.frmedita.csv_separa.value.trim();
	}

	function imprimeexcel() {
		let sError = '';
		if (window.document.frmedita.seg_6.value != 1) {
			sError = "<?php echo $ERR['6']; ?>";
		}
		// if (sError == '') {
		// 	Agregar validaciones
		// }
		if (sError == '') {
			asignarvariables();
			window.document.frmimpp.action = 't111.php';
			window.document.frmimpp.submit();
		} else {
			ModalMensaje(sError);
		}
	}

	function imprimep() {
		if (window.document.frmedita.seg_5.value == 1) {
			asignarvariables();
			window.document.frmimpp.action = '<?php echo $APP->rutacomun; ?>p111.php';
			window.document.frmimpp.submit();
		} else {
			ModalMensaje("<?php echo $ERR['5']; ?>");
		}
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
		datos[1] = window.document.frmedita.unad11tipodoc.value;
		datos[2] = window.document.frmedita.unad11doc.value;
		datos[9] = window.document.frmedita.debug.value;
		if ((datos[1] != '') && (datos[2] != '')) {
			xajax_f111_ExisteDato(datos);
		}
		ajustaforma();
	}

	function cargadato(llave1, llave2) {
		window.document.frmedita.unad11tipodoc.value = String(llave1);
		window.document.frmedita.unad11doc.value = String(llave2);
		window.document.frmedita.paso.value = 1;
		window.document.frmedita.submit();
	}

	function cargaridf111(llave1) {
		window.document.frmedita.unad11id.value = String(llave1);
		window.document.frmedita.paso.value = 3;
		window.document.frmedita.submit();
	}

	function carga_combo_unad11deptoorigen() {
		let params = new Array();
		params[0] = window.document.frmedita.unad11pais.value;
		xajax_Cargar_unad11deptoorigen(params);
		params[0] = '';
		xajax_Cargar_unad11ciudadorigen(params);
	}

	function carga_combo_unad11ciudadorigen() {
		let params = new Array();
		params[0] = window.document.frmedita.unad11deptoorigen.value;
		xajax_Cargar_unad11ciudadorigen(params);
	}

	function carga_combo_unad11deptodoc() {
		let params = new Array();
		params[0] = window.document.frmedita.unad11pais.value;
		xajax_Cargar_unad11deptodoc(params);
		params[0] = '';
		xajax_cargar_unad11ciudaddoc(params);
	}

	function carga_combo_unad11ciudaddoc() {
		let params = new Array();
		params[0] = window.document.frmedita.unad11deptodoc.value;
		xajax_Cargar_unad11ciudaddoc(params);
	}

	function paginarf111() {
		let params = new Array();
		params[99] = window.document.frmedita.debug.value;
		params[101] = window.document.frmedita.paginaf111.value;
		params[102] = window.document.frmedita.lppf111.value;
		params[103] = window.document.frmedita.bdoc.value;
		params[104] = window.document.frmedita.bnombre.value;
		params[105] = window.document.frmedita.busuario.value;
		params[106] = window.document.frmedita.bcorreo.value;
		params[107] = window.document.frmedita.bcampo.value;
		params[108] = window.document.frmedita.badicional.value;
		params[109] = window.document.frmedita.bmatricula.value;
		params[110] = window.document.frmedita.bconvenio.value;
		params[111] = window.document.frmedita.bdesde.value;
		params[112] = window.document.frmedita.bhasta.value;
		//document.getElementById('div_f111detalle').innerHTML = '<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf111" name="paginaf111" type="hidden" value="' + params[101] + '" /><input id="lppf111" name="lppf111" type="hidden" value="' + params[102] + '" />';
		xajax_f111_HtmlTabla(params);
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
		document.getElementById("unad11tipodoc").focus();
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

	function ajustaforma() {
		var dtd = document.getElementById('unad11tipodoc');
		var divnom = document.getElementById('divnombres');
		var divrs = document.getElementById('divrazon');
		var sestado = 'block';
		var sestado2 = 'none';
		if (dtd.value == "NI") {
			sestado = 'none';
			sestado2 = 'block';
		}
		divnom.style.display = sestado;
		divrs.style.display = sestado2;
	}

	function actualizadv() {
		let params = new Array()
		params[0] = window.document.frmedita.unad11id.value;
		xajax_upd_dv(params);
	}

	function enviarmailpwd() {
		window.document.frmedita.iscroll.value = window.scrollY;
		expandesector(98);
		window.document.frmedita.paso.value = 22;
		window.document.frmedita.submit();
	}

	function verificaedunat() {
		window.document.frmedita.iscroll.value = window.scrollY;
		expandesector(98);
		window.document.frmedita.paso.value = 23;
		window.document.frmedita.submit();
	}

	function retirarconfirmacion() {
		window.document.frmedita.iscroll.value = window.scrollY;
		if (confirm('Esta seguro de retirar la confirmaci\u00f3n del correo de notificaciones.')) {
			expandesector(98);
			window.document.frmedita.paso.value = 24;
			window.document.frmedita.submit();
		}
	}

	function verinfopersonal() {
		let params = new Array();
		params[1] = window.document.frmedita.unad11id.value;
		document.getElementById('div_infopersonal').innerHTML = '<b>Procesando datos, por favor espere...</b>';
		xajax_f111_TraerInfoPersonal(params);
	}

	function presentarinfo() {
		document.getElementById('div_infopersonal').innerHTML = '';
		document.getElementById('div_datosper').style.display = 'block';
		window.document.frmedita.b236.value = 1;
	}
</script>
<?php
?>
<form id="frmimpp" name="frmimpp" method="post" action="e111.php" target="_blank" style="display: none">
<input id="r" name="r" type="hidden" value="111" />
<input id="id111" name="id111" type="hidden" value="<?php echo $_REQUEST['unad11id']; ?>" />
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
<input id="iformato94" name="iformato94" type="hidden" value="0" />
<input id="separa" name="separa" type="hidden" value="," />
<input id="rdebug" name="rdebug" type="hidden" value="<?php echo $_REQUEST['debug']; ?>" />
<input id="clave" name="clave" type="hidden" value="" />
</form>
<?php
?>
<form id="frmlista" name="frmlista" method="post" action="listados_csv.php" target="_blank" style="display: none">
<input id="titulos" name="titulos" type="hidden" value="" />
<input id="consulta" name="consulta" type="hidden" value="" />
<input id="csv_separa" name="csv_separa" type="hidden" value="" />
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
<input id="b236" name="b236" type="hidden" value="<?php echo $_REQUEST['b236']; ?>"/>
<div id="div_sector1">
<div class="titulos">
<div class="titulosI">
<?php
echo '<h2>'.$ETI['titulo_111'].'</h2>';
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
<input id="boculta111" name="boculta111" type="hidden" value="<?php echo $_REQUEST['boculta111']; ?>" />
<label class="Label30">
<input id="btexpande111" name="btexpande111" type="button" value="Mostrar" class="btMiniExpandir" onclick="expandepanel(111,'block',0);" title="<?php echo $ETI['bt_mostrar']; ?>" style="display:<?php if ($_REQUEST['boculta111']==0){echo 'none'; } else {echo 'block';} ?>;"/>
</label>
<label class="Label30">
<input id="btrecoge111" name="btrecoge111" type="button" value="Ocultar" class="btMiniRecoger" onclick="expandepanel(111,'none',1);" title="<?php echo $ETI['bt_ocultar']; ?>" style="display:<?php if ($_REQUEST['boculta111']==0){echo 'block'; } else {echo 'none';} ?>;"/>
</label>
</div>
<div id="div_p111" style="display:<?php if ($_REQUEST['boculta111']==0){echo 'block'; } else {echo 'none';} ?>;">
<?php
}
//Mostrar formulario para editar
?>
<label class="Label130">
<?php
echo $ETI['unad11doc'];
?>
</label>
<label class="Label90">
<?php
if ($_REQUEST['paso']!=2){
	echo html_tipodocV2('unad11tipodoc', $_REQUEST['unad11tipodoc'], 'RevisaLlave()', false, false);
	} else {
	echo html_oculto('unad11tipodoc', $_REQUEST['unad11tipodoc']);
	}
?>
</label>
<label class="Label220">
<?php
if ($_REQUEST['paso']!=2){
?>
<input id="unad11doc" name="unad11doc" type="text" value="<?php echo $_REQUEST['unad11doc']; ?>" maxlength="15" onchange="RevisaLlave()" class="veinte"/>
<?php
	} else {
	echo html_oculto('unad11doc', $_REQUEST['unad11doc']);
	}
?>
</label>
<label class="Label30">
<?php
echo $ETI['unad11dv'];
?>
</label>
<label class="Label30"><div id="div_unad11dv">
<?php
echo html_oculto('unad11dv', $_REQUEST['unad11dv']);
?>
</div></label>
<?php
$bcondv=false;
if ($_REQUEST['paso']==2){
	switch($_REQUEST['unad11tipodoc']){
		case 'CC':
		case 'NI':
		case 'TI';
		$bcondv=true;
		}
	}
if ($bcondv){
	echo $objForma->htmlBotonSolo('brevisadv', 'btMiniActualizar', 'actualizadv()', $ETI['upd_dv'], 30);
}
?>
<label class="Label60">
<?php
echo $ETI['unad11id'];
?>
</label>
<label class="Label60">
<?php
	echo html_oculto('unad11id', $_REQUEST['unad11id']);
?>
</label>
<label class="Label90">
<?php
echo $ETI['unad11usuario'];
?>
</label>
<label class="Label130"><div id="div_unad11usuario">
<?php
echo html_oculto('unad11usuario', $_REQUEST['unad11usuario']);
?>
</div></label>
<?php
$bUpdEdunat=false;
if ($_REQUEST['paso']==2){
	switch($_REQUEST['unad11tipodoc']){
		case 'CC':
		$bUpdEdunat=true;
		}
	}
if ($bUpdEdunat){
?>
<label class="Label30">
<input id="bUpdEdunat" name="bUpdEdunat" type="button" value="Actualizar Usuario" class="btMiniActualizar" onclick="verificaedunat()" title="Actualizar Usuario"/>
</label>
<?php
	}
?>
<label class="Label130">
<?php
echo $html_unad11bloqueado;
?>
</label>
<div class="salto1px"></div>
<div id="divnombres">
<label class="Label130">
<?php
echo $ETI['unad11nombre1'];
?>
</label>
<label>
<input id="unad11nombre1" name="unad11nombre1" type="text" value="<?php echo $_REQUEST['unad11nombre1']; ?>" maxlength="30" placeholder="<?php echo $ETI['ing_campo'].$ETI['unad11nombre1']; ?>"/>
</label>
<label class="Label160">
<?php
echo $ETI['unad11nombre2'];
?>
</label>
<label>
<input id="unad11nombre2" name="unad11nombre2" type="text" value="<?php echo $_REQUEST['unad11nombre2']; ?>" maxlength="30" placeholder="<?php echo $ETI['ing_campo'].$ETI['unad11nombre2']; ?>"/>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['unad11apellido1'];
?>
</label>
<label>
<input id="unad11apellido1" name="unad11apellido1" type="text" value="<?php echo $_REQUEST['unad11apellido1']; ?>" maxlength="30" placeholder="<?php echo $ETI['ing_campo'].$ETI['unad11apellido1']; ?>"/>
</label>
<label class="Label160">
<?php
echo $ETI['unad11apellido2'];
?>
</label>
<label>
<input id="unad11apellido2" name="unad11apellido2" type="text" value="<?php echo $_REQUEST['unad11apellido2']; ?>" maxlength="30" placeholder="<?php echo $ETI['ing_campo'].$ETI['unad11apellido2']; ?>"/>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['unad11genero'];
?>
</label>
<label class="Label130">
<?php
echo $html_unad11genero;
?>
</label>
<label class="Label160">
<?php
echo $ETI['unad11fechanace'];
?>
</label>
<label class="Label300">
<div>
<?php
echo html_fecha('unad11fechanace', $_REQUEST['unad11fechanace'], true, '', 1900, date('Y'));
?>
</div>
</label>
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
</div>
<div id="divrazon">
<label class="L">
<?php
echo $ETI['unad11razonsocial'];
?>

<input id="unad11razonsocial" name="unad11razonsocial" type="text" value="<?php echo $_REQUEST['unad11razonsocial']; ?>" maxlength="100" class="L" placeholder="<?php echo $ETI['ing_campo'].$ETI['unad11razonsocial']; ?>"/>
</label>
</div>
<div class="salto1px"></div>
<label class="Label450">
<?php
echo $ETI['unad11fechadoc'];
?>
</label>
<div class="Campo250">
<?php
echo html_FechaEnNumero('unad11fechadoc', $_REQUEST['unad11fechadoc'], true, '', 1900, fecha_agno());
?>
</div>
<?php
if ((int)$_REQUEST['unae23idexterno']!=0){
?>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['unae23idexterno'];
?>
</label>
<label>
<?php
echo html_oculto('unae23idexterno', $_REQUEST['unae23idexterno']);
?>
</label>
<?php
	} else {
?>
<input id="unae23idexterno" name="unae23idexterno" type="hidden" value="<?php echo $_REQUEST['unae23idexterno']; ?>"/>
<?php
	}
?>
<div class="salto1px"></div>
<label class="L">
<?php
echo $ETI['unad11direccion'];
?>

<input id="unad11direccion" name="unad11direccion" type="text" value="<?php echo $_REQUEST['unad11direccion']; ?>" maxlength="100" class="L" placeholder="<?php echo $ETI['ing_campo'].$ETI['unad11direccion']; ?>"/>
</label>
<div class="salto1px"></div>
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
<?php
if (!$bConInfoPersonal){
?>
<div id="div_infopersonal">
<label></label>
<label class="Label160">
<input id="cmdInfoPersonal" name="cmdInfoPersonal" type="button" class="BotonAzul160" value="Datos personales" onclick="verinfopersonal();" title="Ver datos personales"/>
</label>
</div>
<div class="salto1px"></div>
<div id="div_datosper" style="display:none">
<?php
	}
?>
<label class="Label130">
<?php
echo $ETI['unad11telefono'];
?>
</label>
<label class="Label220">
<input id="unad11telefono" name="unad11telefono" type="text" value="<?php echo $_REQUEST['unad11telefono']; ?>" maxlength="30" placeholder="<?php echo $ETI['ing_campo'].$ETI['unad11telefono']; ?>"/>
</label>
<label class="Label60">
<?php
echo $ETI['unad11correo'];
?>
</label>
<label class="Label350">
<?php
if ($bCorreoDisponible){
?>
<input id="unad11correo" name="unad11correo" type="text" value="<?php echo $_REQUEST['unad11correo']; ?>" maxlength="50" class="Label350"/>
<?php
	} else {
	echo html_oculto('unad11correo', $_REQUEST['unad11correo']);
	}
?>
</label>
<?php
if (!$bConInfoPersonal){
?>
<div class="salto1px"></div>
</div>
<?php
	}
?>
<label class="L">
<?php
echo $ETI['unad11sitioweb'];
?>
<input name="unad11sitioweb" type="text" id="unad11sitioweb" value="<?php echo $_REQUEST['unad11sitioweb']; ?>" maxlength="50" class="L"/>
</label>
<div class="salto1px"></div>
<div class="GrupoCampos450">
<label class="Label130">
<?php
echo $ETI['unad11nacionalidad'];
?>
</label>
<label>
<?php
echo $html_unad11nacionalidad;
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['unad11deptoorigen'];
?>
</label>
<label><div id="div_unad11deptoorigen">
<?php
echo $html_unad11deptoorigen;
?>
</div></label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['unad11ciudadorigen'];
?>
</label>
<label><div id="div_unad11ciudadorigen">
<?php
echo $html_unad11ciudadorigen;
?>
</div></label>
<div class="salto1px"></div>
</div>
<div class="GrupoCampos450">
<label class="TituloGrupo">
Lugar de residencia
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
<label><div id="div_unad11deptodoc">
<?php
echo $html_unad11deptodoc;
?>
</div></label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['unad11ciudaddoc'];
?>
</label>
<label><div id="div_unad11ciudaddoc">
<?php
echo $html_unad11ciudaddoc;
?>
</div></label>
<div class="salto1px"></div>
</div>

<div class="salto1px"></div>
<div class="GrupoCampos">
<?php
echo '<b>'.$ETI['msg_notificaciones'].'</b>';
?>
<div class="salto1px"></div>
<label class="Label200">
<?php
echo $ETI['unad11aceptanotificacion'];
?>
</label>
<label class="Label130">
<?php
echo $html_unad11aceptanotificacion;
?>
</label>
<div class="salto1px"></div>
<label class="L">
<?php
echo $ETI['unad11correonotifica'];
?>
<?php
if ($bVentanaTerceros){
	$bBloqueado=false;
	if ($_REQUEST['unad11fechaconfmail']!=0){
		$bBloqueado=true;
		//if ($seg_111==1){$bBloqueado=false;}
		}
	} else {
	$bBloqueado=true;
	}
if ($bBloqueado){
	echo '&nbsp;'.html_oculto('unad11correonotifica', $_REQUEST['unad11correonotifica']);
	} else {
?>
<input id="unad11correonotifica" name="unad11correonotifica" type="text" value="<?php echo $_REQUEST['unad11correonotifica']; ?>" maxlength="50" placeholder="<?php echo $ETI['ing_campo'].$ETI['unad11correonotifica']; ?>" class="L"/>
<?php
	}
?>
</label>
<label class="Label300">
<?php
echo $ETI['unad11fechaconfmail'];
?>
</label>
<label class="Label250"><div id="div_unad11fechaconfmail">
<?php
echo html_oculto('unad11fechaconfmail', $_REQUEST['unad11fechaconfmail'], formato_FechaLargaDesdeNumero($_REQUEST['unad11fechaconfmail']));
?>
</div></label>
<?php
if ($_REQUEST['unad11fechaconfmail']!=0){
	$bPuedeRemover=false;
	$bMostrarAdvertencias=false;
	$bMostrarMensajeSoporte=false;
	if ($bTieneAdvertenciasSeguridad){
		$bMostrarMensajeSoporte=true;
		if ($bVentanaTerceros){
			if ($seg_111==1){
				$bPuedeRemover=true;
				$bMostrarAdvertencias=true;
				$bMostrarMensajeSoporte=false;
				}
			}
		} else {
		$bPuedeRemover=true;
		}
	if ($bPuedeRemover){
?>
<label class="Label30">
<input id="bRemoverConfirmacion" name="bRemoverConfirmacion" type="button" value="Retirar Confirmaci&oacute;n" class="btMiniEliminar" onclick="retirarconfirmacion()" title="Retirar Confirmaci&oacute;n"/>
</label>
<?php
		}
	if ($bMostrarAdvertencias){
?>
<label class="Label30"></label>
<label class="Label320">
<span class="rojo">Cuenta con <?php echo $iTotalSospechas; ?> accesos sospechosos</span>
</label>
<?php
		}
	if ($bMostrarMensajeSoporte){
?>
<label class="L">
<span class="rojo">Para cambiar el correo de notificaciones el estudiante debe solicitarlo por escrito a soporte.campus@unad.edu.co</span>
</label>
<?php
		}
	}
?>
<label class="L">
<?php
echo $ETI['unad11correoinstitucional'];
if ($bVentanaTerceros){
?>
<input id="unad11correoinstitucional" name="unad11correoinstitucional" type="text" value="<?php echo $_REQUEST['unad11correoinstitucional']; ?>" maxlength="50" placeholder="usuario@unadvirtual.edu.co" class="L"/>
<?php
	} else {
	echo '&nbsp;'.html_oculto('unad11correoinstitucional', $_REQUEST['unad11correoinstitucional']);
	}
?>
</label>
<label class="L">
<?php
echo $ETI['unad11correofuncionario'];
if ($bVentanaTerceros){
?>
<input id="unad11correofuncionario" name="unad11correofuncionario" type="text" value="<?php echo $_REQUEST['unad11correofuncionario']; ?>" maxlength="50" placeholder="nombre.apellido@unad.edu.co" class="L"/>
<?php
	} else {
	echo '&nbsp;'.html_oculto('unad11correoinstitucional', $_REQUEST['unad11correoinstitucional']);
	}
?>
</label>
<?php
if ($seg_111==1){
?>
<div class="salto1px"></div>
<div class="GrupoCamposAyuda">
<?php
if ($sCorreoMensajes!=''){
	echo $ETI['msg_correoparaclave'].' <b>'.$sCorreoMensajes.'</b>'; 
?>
<div class="salto1px"></div>
<label class="Label160">&nbsp;</label>
<label class="Labe220">
<input id="cmdContrasegna" name="cmdContrasegna" type="button" value="<?php echo $ETI['msg_contrasegna']; ?>"  onClick="enviarmailpwd();" class="BotonAzul" title="<?php echo $AYU['msg_contrasegna']; ?>"/>
</label>
<?php
	} else {
	echo $ETI['msg_correonodisponible'];
	}
?>
<div class="salto1px"></div>
</div>
<?php
	}
?>
<div class="salto1px"></div>
<label class="Label200">
<?php
echo $ETI['unad11fechaclave'];
?>
</label>
<label class="Label130">
<?php
echo html_oculto('unad11fechaclave', $_REQUEST['unad11fechaclave'], fecha_desdenumero($_REQUEST['unad11fechaclave'], '&nbsp;'));
?>
</label>
<label class="Label200">
<?php
echo $ETI['unad11fechaultingreso'];
?>
</label>
<label class="Label130">
<?php
echo html_oculto('unad11fechaultingreso', $_REQUEST['unad11fechaultingreso'], fecha_desdenumero($_REQUEST['unad11fechaultingreso'], '&nbsp;'));
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['unad11mostrarcelular'];
?>
</label>
<label class="Label60">
<?php
echo $html_unad11mostrarcelular;
?>
</label>
<label class="Label130">
<?php
echo $ETI['unad11mostrarcorreo'];
?>
</label>
<label class="Label60">
<?php
echo $html_unad11mostrarcorreo;
?>
</label>
<label class="Label130">
<?php
echo $ETI['unad11mostrarskype'];
?>
</label>
<label class="Label60">
<?php
echo $html_unad11mostrarskype;
?>
</label>
<label class="Label90">
<?php
echo $ETI['unad11skype'];
?>
</label>
<label>
<?php
if ($bVentanaTerceros){
?>
<input id="unad11skype" name="unad11skype" type="text" value="<?php echo $_REQUEST['unad11skype']; ?>" maxlength="50" placeholder="<?php echo $ETI['ing_campo'].$ETI['unad11skype']; ?>"/>
<?php
	} else {
	echo html_oculto('unad11skype', $_REQUEST['unad11skype']);
	}
?>
</label>
<div class="salto1px"></div>
</div>

<?php
if ($bReducido){
?>
<input id="unad11latgrados" name="unad11latgrados" type="hidden" value="<?php echo $_REQUEST['unad11latgrados']; ?>"/>
<input id="unad11latdecimas" name="unad11latdecimas" type="hidden" value="<?php echo $_REQUEST['unad11latdecimas']; ?>"/>
<input id="unad11longrados" name="unad11longrados" type="hidden" value="<?php echo $_REQUEST['unad11longrados']; ?>"/>
<input id="unad11longdecimas" name="unad11longdecimas" type="hidden" value="<?php echo $_REQUEST['unad11longdecimas']; ?>"/>
<input id="unad11noubicar" name="unad11noubicar" type="hidden" value="<?php echo $_REQUEST['unad11noubicar']; ?>"/>
<?php
	} else {
?>
<div class="salto1px"></div>
<div class="GrupoCampos">
<?php
echo '<b>'.$ETI['msg_geolocaliza'].'</b>';
?>
<div class="salto1px"></div>
<label class="Label90">
<?php
echo $ETI['unad11latgrados'];
?>
</label>
<label class="Label130">
<input id="unad11latgrados" name="unad11latgrados" type="text" value="<?php echo $_REQUEST['unad11latgrados']; ?>" class="diez" maxlength="10" placeholder="<?php echo $ETI['ing_vr']; ?>"/>
</label>
<label>
<input id="unad11latdecimas" name="unad11latdecimas" type="text" value="<?php echo $_REQUEST['unad11latdecimas']; ?>" maxlength="10" placeholder="<?php echo $ETI['ing_campo'].$ETI['unad11latdecimas']; ?>"/>
</label>
<label class="Label90">
<?php
echo $ETI['unad11longrados'];
?>
</label>
<label class="Label130">
<input id="unad11longrados" name="unad11longrados" type="text" value="<?php echo $_REQUEST['unad11longrados']; ?>" class="diez" maxlength="10" placeholder="<?php echo $ETI['ing_vr']; ?>"/>
</label>
<label>
<input id="unad11longdecimas" name="unad11longdecimas" type="text" value="<?php echo $_REQUEST['unad11longdecimas']; ?>" maxlength="10" placeholder="<?php echo $ETI['ing_campo'].$ETI['unad11longdecimas']; ?>"/>
</label>
<div class="salto1px"></div>
<label class="Label200">
<?php
echo $ETI['unad11noubicar'];
?>
</label>
<label class="Label60">
<?php
echo $html_unad11noubicar;
?>
</label>
<label class="Label250">
<?php
echo $ETI['unad11exluirdobleaut'];
?>
</label>
<label class="Label130">
<?php
echo $html_unad11exluirdobleaut;
?>
</label>
<div class="salto1px"></div>
</div>
<?php
	}
?>
<div class="salto1px"></div>
<div class="GrupoCampos">
<?php
echo '<b>'.$ETI['msg_informativo'].'</b>';
?>
<div class="salto1px"></div>
<label class="txtAreaM">
<?php
echo $ETI['unad11presentacion'];
?>
<textarea id="unad11presentacion" name="unad11presentacion" placeholder="<?php echo $ETI['ing_campo'].$ETI['unad11presentacion']; ?>" disabled="disabled"><?php echo $_REQUEST['unad11presentacion']; ?></textarea>
</label>

<div class="salto1px"></div>
<label class="Label200">
<?php
echo $ETI['unad11fechaclaveapps'];
?>
</label>
<label class="Label130">
<?php
echo html_oculto("unad11fechaclaveapps", $_REQUEST['unad11fechaclaveapps']);//, formato_fechalarga($_REQUEST['unad11fechaclaveapps']));
?>
</label>
<div class="salto1px"></div>
<label class="Label300">
<?php
echo $ETI['unad11encuestafecha'];
?>
</label>
<label class="Label130">
<?php
echo html_oculto('unad11encuestafecha', $_REQUEST['unad11encuestafecha'], fecha_desdenumero($_REQUEST['unad11encuestafecha'], '&nbsp;'));
?>
</label>
<label class="Label130"><div id="div_unad11encuestaminuto">
<?php
$et_unad11encuestaminuto='&nbsp;';
if ($_REQUEST['unad11encuestaminuto']!=0){$et_unad11encuestaminuto=html_TablaHoraMinDesdeNumero($_REQUEST['unad11encuestaminuto']);}
echo html_oculto('unad11encuestaminuto', $_REQUEST['unad11encuestaminuto'], $et_unad11encuestaminuto);
?>
</div></label>
<label class="Label250">
<?php
echo $ETI['unad11fechaterminos'];
?>
</label>
<label class="Label130">
<?php
echo html_oculto('unad11fechaterminos', $_REQUEST['unad11fechaterminos'], fecha_desdenumero($_REQUEST['unad11fechaterminos'], '&nbsp;'));
?>
</label>
<div class="salto1px"></div>
</div>

<?php
if (false) {
	//Ejemplo de boton de ayuda
	//echo html_BotonAyuda('NombreCampo');
	//echo html_DivAyudaLocal('NombreCampo');
}
if ($bConExpande) {
	//Este es el cierre del div_p111
?>
<div class="salto1px"></div>
</div>
<?php
}
//Mostrar el contenido de la tabla
// CIERRA EL DIV areatrabajo
echo $objForma->htmlFinMarco();
?>
<?php
echo $objForma->htmlInicioMarco($ETI['bloque1']);
?>
<div class="ir_derecha">
<label class="Label130">
Documento
</label>
<label class="Label220">
<input name="bdoc" type="text" id="bdoc" value="<?php echo $_REQUEST['bdoc']; ?>" onchange="paginarf111()" autocomplete="off"/>
</label>
<label class="Label90">
<?php
echo $ETI['msg_bnombre'];
?>
</label>
<label class="Label220">
<input id="bnombre" name="bnombre" type="text" value="<?php echo $_REQUEST['bnombre']; ?>" onchange="paginarf111()" autocomplete="off"/>
</label>
<label class="Label90">
Usuario
</label>
<label class="Label200">
<input name="busuario" type="text" id="busuario" value="<?php echo $_REQUEST['busuario']; ?>" onchange="paginarf111()" autocomplete="off"/>
</label>
<div class="salto1px"></div>
<label class="Label130">
Correo
</label>
<label class="Label220">
<input id="bcorreo" name="bcorreo" type="text" value="<?php echo $_REQUEST['bcorreo']; ?>" onchange="paginarf111()" autocomplete="off"/>
</label>
<label class="Label200">
<?php
echo $html_bcampo;
?>
</label>
<label class="Label130">
Verificaciones
</label>
<?php
echo $html_badicional;
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
Matriculado en
</label>
</label>
<?php
echo $html_bmatricula;
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
Convenio
</label>
<label class="Label130">
<?php
echo $html_bconvenio;
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
Ingreso desde
</label>
<label class="Label300">
<?php
echo html_FechaEnNumero('bdesde', $_REQUEST['bdesde'], true, 'paginarf111();', 1900, date('Y'));
?>
</label>
<label class="Label130">
Hasta
</label>
<label class="Label300">
<?php
echo html_FechaEnNumero('bhasta', $_REQUEST['bhasta'], true, 'paginarf111();', 1900, date('Y'));
?>
</label>
<?php
echo ' ' . $csv_separa;
?>
<div class="salto1px"></div>
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
<div id="div_f111detalle">
<?php
echo $sTabla111;
?>
</div>
<?php
// CIERRA EL DIV areatrabajo
?>
<?php
echo $objForma->htmlFinMarco();
?>
</div><!-- /DIV_Sector1 -->


<div id="div_sector2" style="display:none">
<?php
if ($bBloqueTitulo) {
?>
<div class="titulos">
<div class="titulosD">
<input id="cmdAyuda2" name="cmdAyuda2" type="button" class="btSupAyuda" onclick="muestraayuda(<?php echo $iCodModulo; ?>);" title="<?php echo $ETI['bt_ayuda']; ?>" value="<?php echo $ETI['bt_ayuda']; ?>"/>
<input id="cmdVolverSec2" name="cmdVolverSec2" type="button" class="btSupVolver" onclick="expandesector(1);" title="<?php echo $ETI['bt_volver']; ?>" value="<?php echo $ETI['bt_volver']; ?>"/>
</div>
<div class="titulosI">
<?php
echo '<h2>'.$ETI['titulo_sectorcambiaclave'].'</h2>';
?>
</div>
</div>
<?php
}
?>
<div id="cargaForm">
<div id="area">
Para que el usuario pueda restaurar su clave se enviar&aacute; un mensaje de restablecimiento al correo <b><?php echo $sCorreoMensajes; ?></b>
<div class="salto1px"></div>
<label class="Label160">&nbsp;</label>
<?php
echo $objForma->htmlBotonSolo('cmdEnviaClave', 'BotonAzul', 'enviarmailpwd();', 'Enviar mensaje de restauraci&oacute;n de contrase&ntilde;a', 220, '', 'Contrase&ntilde;a');
?>
<?php
echo $objForma->htmlFinMarco();
?>
</div><!-- /DIV_Sector2 -->


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
<input id="div96v4" name="div96v4" type="hidden" value="" />
<input id="div96campo" name="div96campo" type="hidden" value="" />
<input id="div96llave" name="div96llave" type="hidden" value="" />
<input id="titulo_111" name="titulo_111" type="hidden" value="<?php echo $ETI['titulo_111']; ?>" />
<?php
$objForma=new clsHtmlForma($iPiel);
$objForma->addBoton('cmdAyuda96', 'btSupAyuda', 'muestraayuda(' . $iCodModulo . ');', $ETI['bt_ayuda']);
echo $objForma->htmlTitulo($sTituloModulo, $iCodModulo);
echo $objForma->htmlInicioMarcoSimple();
echo '<div id="div_96cuerpo"></div>';
echo $objForma->htmlFinMarcoSimple();
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
$objForma=new clsHtmlForma($iPiel);
$objForma->addBoton('cmdAyuda98', 'btSupAyuda', 'muestraayuda(' . $iCodModulo . ');', $ETI['bt_ayuda']);
echo $objForma->htmlTitulo($ETI['titulo_111'], $iCodModulo);
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
if ($iSector!=1){
	echo 'setTimeout(function(){expandesector('.$iSector.');}, 10);
';
	}
if ($bMueveScroll){
	echo 'setTimeout(function(){retornacontrol();}, 2);
';
	}
?>
ajustaforma();
</script>
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>js/chosen.css" type="text/css"/>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>js/chosen.jquery.js"></script>
<script language="javascript">
$().ready(function(){
$("#bmatricula").chosen();
});
</script>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>unad_todas2024.js?ver=2"></script>
<?php
forma_piedepagina();