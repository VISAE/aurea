<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2016 - 2024 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.12.5 martes, 15 de marzo de 2016
--- Modelo Versión 2.17.0 domingo, 26 de marzo de 2017
--- Modelo Versión 2.23.7 domingo, 8 de octubre de 2019 - Se agrega valiacion a respuestas opcionales.
--- Modelo Versión 2.25.8 martes, 10 de noviembre de 2020
--- Modelo Versión 2.28.1 viernes, 13 de mayo de 2022 - Se ajusta la forma 
--- Modelo Versión 2.28.2 viernes, 3 de junio de 2022
--- Modelo Versión 3.0.12c jueves, 12 de diciembre de 2024
*/
/** Archivo encuesta.php.
 * Modulo 1921 even21encuestaaplica.
 * @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
 * @param debug=1 (Opcional), bandera para indicar si se generan datos de depuración
 * @date viernes, 3 de junio de 2022
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
$iMinVerDB = 7117;
$iCodModulo = 1926;
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
$mensajes_1926 = 'lg/lg_1926_' . $_SESSION['unad_idioma'] . '.php';
if (!file_exists($mensajes_1926)) {
	$mensajes_1926 = 'lg/lg_1926_es.php';
}
require $mensajes_todas;
require $mensajes_1926;
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
$sTituloModulo = $ETI['titulo_1926'];
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
		header('Location:noticia.php?ret=encuesta_2024.php');
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
// -- 1926 
require 'lib1926.php';
$xajax = new xajax();
$xajax->configure('javascript URI', $APP->rutacomun . 'xajax/');
$xajax->register(XAJAX_FUNCTION, 'Cargar_even21depto');
$xajax->register(XAJAX_FUNCTION, 'Cargar_even21ciudad');
$xajax->register(XAJAX_FUNCTION, 'f1621_Guardar_even21ciudad');
$xajax->register(XAJAX_FUNCTION, 'f1621_Guardar_even21fechanace');
$xajax->register(XAJAX_FUNCTION, 'Cargar_even21idcead');
$xajax->register(XAJAX_FUNCTION, 'f1621_Guardar_even21idcead');
$xajax->register(XAJAX_FUNCTION, 'f1621_Guardar_even21perfil');
$xajax->register(XAJAX_FUNCTION, 'f1621_Guardar_even21idprograma');
$xajax->register(XAJAX_FUNCTION, 'sesion_abandona_V2');
$xajax->register(XAJAX_FUNCTION, 'sesion_mantenerV4');
$xajax->register(XAJAX_FUNCTION, 'f1926_HtmlTabla');
$xajax->register(XAJAX_FUNCTION, 'f1926_CargarCuerpo');
$xajax->register(XAJAX_FUNCTION, 'f1926_GuardaRptaV2');
$xajax->register(XAJAX_FUNCTION, 'f1926_MarcarOpcion');
$xajax->register(XAJAX_FUNCTION, 'f1926_GuadaAbierta');
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
if (isset($_REQUEST['paginaf1926']) == 0) {
	$_REQUEST['paginaf1926'] = 1;
}
if (isset($_REQUEST['lppf1926']) == 0) {
	$_REQUEST['lppf1926'] = 20;
}
if (isset($_REQUEST['boculta1926']) == 0) {
	$_REQUEST['boculta1926'] = 0;
}
// -- Inicializar variables de datos.
if (isset($_REQUEST['even21pais']) == 0) {
	$_REQUEST['even21pais'] = $_SESSION['unad_pais'];
}
if (isset($_REQUEST['even21depto']) == 0) {
	$_REQUEST['even21depto'] = '';
}
if (isset($_REQUEST['even21ciudad']) == 0) {
	$_REQUEST['even21ciudad'] = '';
}
if (isset($_REQUEST['even21fechanace']) == 0) {
	$_REQUEST['even21fechanace'] = '';
} //{fecha_hoy();}
if (isset($_REQUEST['even21perfil']) == 0) {
	$_REQUEST['even21perfil'] = 0;
}
if (isset($_REQUEST['even21idzona']) == 0) {
	$_REQUEST['even21idzona'] = '';
}
if (isset($_REQUEST['even21idcead']) == 0) {
	$_REQUEST['even21idcead'] = '';
}
if (isset($_REQUEST['even21idprograma']) == 0) {
	$_REQUEST['even21idprograma'] = '';
}
if (isset($_REQUEST['unad11genero']) == 0) {
	$_REQUEST['unad11genero'] = '';
}
// Espacio para inicializar otras variables
if (isset($_REQUEST['bnombre']) == 0) {
	$_REQUEST['bnombre'] = '';
}
if (isset($_REQUEST['idencuesta']) == 0) {
	$_REQUEST['idencuesta'] = 0;
}
if (isset($_REQUEST['id21']) == 0) {
	$_REQUEST['id21'] = 0;
}
//if (isset($_REQUEST['blistar'])==0){$_REQUEST['blistar']='';}
//Cerrar la encuesta.
$bResaltarPendientes = false;
if ($_REQUEST['paso'] == 2) {
	$id21 = $_REQUEST['id21'];
	$bTermino = true;
	$sSQL = 'SELECT 1 FROM even22encuestarpta WHERE even22idaplica=' . $id21 . ' AND even22irespuesta=-1 AND even22opcional<>"S" AND even22idpregcondiciona=0 AND even22tiporespuesta IN (0,1)';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$bTermino = false;
	}
	if ($bTermino) {
		//Agosto 9 de 2019 - Se ajusta para revisar que las preguntas del tipo abierta y que no sean opcionales tengan una respuesta.
		$sSQL = 'SELECT 1 FROM even22encuestarpta WHERE even22idaplica=' . $id21 . ' AND even22nota="" AND even22opcional<>"S" AND even22idpregcondiciona=0 AND even22tiporespuesta=3';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$bTermino = false;
		}
	}
	if ($bTermino) {
		//Vamos a revisar las condiconales..
		$sSQL = 'SELECT even22idpregcondiciona, even22valorcondiciona FROM even22encuestarpta WHERE even22idaplica=' . $id21 . ' AND even22idpregcondiciona<>0 GROUP BY even22idpregcondiciona, even22valorcondiciona';
		$tabla = $objDB->ejecutasql($sSQL);
		while ($fila = $objDB->sf($tabla)) {
			$sSQL = 'SELECT even22irespuesta FROM even22encuestarpta WHERE even22idaplica=' . $id21 . ' AND even22idpregunta=' . $fila['even22idpregcondiciona'] . '';
			$tabla22 = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla22) > 0) {
				$fila22 = $objDB->sf($tabla22);
				if ($fila22['even22irespuesta'] == $fila['even22valorcondiciona']) {
					$sSQL = 'SELECT 1 FROM even22encuestarpta WHERE even22idaplica=' . $id21 . ' AND even22irespuesta=-1 AND even22opcional<>"S" AND even22idpregcondiciona=' . $fila['even22idpregcondiciona'] . ' AND AND even22valorcondiciona=' . $fila['even22valorcondiciona'] . ' even22tiporespuesta IN (0,1)';
					$tablar = $objDB->ejecutasql($sSQL);
					if ($objDB->nf($tablar) > 0) {
						$bTermino = false;
					}
					if ($bTermino) {
						$sSQL = 'SELECT 1 FROM even22encuestarpta WHERE even22idaplica=' . $id21 . ' AND even22nota="" AND even22opcional<>"S" AND even22idpregcondiciona=' . $fila['even22idpregcondiciona'] . ' AND AND even22valorcondiciona=' . $fila['even22valorcondiciona'] . ' AND even22tiporespuesta=3';
						$tablar = $objDB->ejecutasql($sSQL);
						if ($objDB->nf($tablar) > 0) {
							$bTermino = false;
						}
					}
				}
			}
		}
	}
	$sFechaNace = '';
	if ($bTermino) {
		if ($_REQUEST['even21ciudad'] == '') {
			$bTermino = false;
			$sError = 'No ha diligenciado la ciudad de residencia';
		}
		if ($_REQUEST['even21depto'] == '') {
			$bTermino = false;
			$sError = 'No ha diligenciado el departamento de residencia';
		}
		if ($_REQUEST['unad11genero'] == '') {
			$bTermino = false;
			$sError = 'No ha diligenciado el sexo al que pertenece';
		}
		if (!fecha_esvalida($_REQUEST['even21fechanace'])) {
			$bTermino = false;
			$sError = 'La fecha de nacimiento no es v&aacute;lida.';
		} else {
			$sFechaNace = $_REQUEST['even21fechanace'];
			$aFecha = explode('/', $sFechaNace);
			$iMax = fecha_agno() - 10;
			if ($aFecha[2] > $iMax) {
				$bTermino = false;
				$sError = 'Su a&ntilde;o de nacimiento no puede ser superior a ' . $iMax;
			}
		}
		if ((int)$_REQUEST['even21idcead'] == 0) {
			$bTermino = false;
			$sError = 'No ha diligenciado el CEAD al que esta inscrito.';
		}
		if ((int)$_REQUEST['even21idzona'] == 0) {
			$bTermino = false;
			$sError = 'No ha diligenciado la zona a la que pertenece.';
		}
		if ($_REQUEST['even21perfil'] == 0) {
			if ((int)$_REQUEST['even21idprograma'] == 0) {
				$bTermino = false;
				$sError = 'Por favor indique el programa en que esta matriculado.';
			}
		} else {
			$_REQUEST['even21idprograma'] = (int)$_REQUEST['even21idprograma'];
		}
	}
	if ($bTermino) {
		//Ver que las repuestas de la 21 esten completas.
		$sSQL = 'UPDATE even21encuestaaplica SET even21pais="' . $_REQUEST['even21pais'] . '", even21depto="' . $_REQUEST['even21depto'] . '", even21ciudad="' . $_REQUEST['even21ciudad'] . '", even21fechanace="' . $_REQUEST['even21fechanace'] . '", even21perfil=' . $_REQUEST['even21perfil'] . ', even21idzona=' . $_REQUEST['even21idzona'] . ', even21idcead=' . $_REQUEST['even21idcead'] . ', even21idprograma=' . $_REQUEST['even21idprograma'] . ' WHERE even21id=' . $id21 . '';
		$tabla = $objDB->ejecutasql($sSQL);
	}
	if ($bTermino) {
		if ($bOtroUsuario) {
			$bTermino = false;
			$sError = 'Todas las condiciones para el cierre de la encuesta estan completas.. [Usted no puede cerrar la encuesta.]';
		}
	}
	if (!$bTermino) {
		$_REQUEST['paso'] = 0;
		if ($sError == '') {
			$sError = 'A&uacute;n no ha diligenciado todas las respuestas, por favor termine sus respuestas y vuelva a intentar.';
			$bResaltarPendientes = true;
		}
	} else {
		$_REQUEST['paso'] = -1;
		//Actaulizamos la fecha de nacimiento del tercero.
		$sSQL = 'SELECT unad11fechanace, unad11genero FROM unad11terceros WHERE unad11id=' . $idTercero . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			$sUpdate = '';
			if ($fila['unad11fechanace'] != $sFechaNace) {
				$sUpdate = 'unad11fechanace="' . $sFechaNace . '"';
			}
			if ($fila['unad11genero'] != $_REQUEST['unad11genero']) {
				if ($sUpdate != '') {
					$sUpdate = $sUpdate . ', ';
				}
				$sUpdate = 'unad11genero="' . $_REQUEST['unad11genero'] . '"';
			}
			if ($sUpdate != '') {
				$sUpdate = 'UPDATE unad11terceros SET ' . $sUpdate . ' WHERE unad11id=' . $idTercero . '';
				$tabla = $objDB->ejecutasql($sUpdate);
			}
		}
		//Cerrar la encuesta.
		$sSQL = 'UPDATE even21encuestaaplica SET even21fechapresenta="' . fecha_hoy() . '", even21terminada="S" WHERE even21id=' . $id21 . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if (false) {
			//Totalizar las preguntas.
			$sSQL = 'SELECT even21idencuesta FROM even21encuestaaplica WHERE even21id=' . $id21 . '';
			$tabla = $objDB->ejecutasql($sSQL);
			$fila = $objDB->sf($tabla);
			$id16 = $fila['even21idencuesta'];
			//Termina de totalizar las preguntas.
		}
	}
}
//Recarga la encuesta por pregunta divertente
if ($_REQUEST['paso'] == 21) {
	$_REQUEST['paso'] = 0;
	$bMueveScroll = true;
}
//limpiar la pantalla
$bCompletarVacios = false;
if ($_REQUEST['paso'] == -1) {
	$_REQUEST['even21pais'] = $_SESSION['unad_pais'];
	$_REQUEST['even21depto'] = '';
	$_REQUEST['even21ciudad'] = '';
	$_REQUEST['even21fechanace'] = ''; //fecha_hoy();
	$_REQUEST['even21perfil'] = 0;
	$_REQUEST['even21idzona'] = '';
	$_REQUEST['even21idcead'] = '';
	$_REQUEST['even21idprograma'] = '';
	$_REQUEST['paso'] = 0;
	$_REQUEST['idencuesta'] = 0;
	$_REQUEST['id21'] = 0;
	$_REQUEST['unad11genero'] = '';
	$bCompletarVacios = true;
	//Revisar que encuestas debe hacer.... crearlas si es necesario
	f1926_CargarEncuestas($idTercero, $objDB);
}
if ($bLimpiaHijos) {
}
//AQUI SE DEBEN CARGAR TODOS LOS DATOS QUE LA FORMA NECESITE.
$bPuedeGuardar = true;
$bConEliminar = false;
$bHayImprimir = false;
$bHayImprimir2 = false;
$sScriptImprime = 'imprimelista()';
// $sScriptImprime2 = 'imprimeexcel()';
$sClaseImprime = 'btEnviarExcel';
// $sClaseImprime2 = 'btEnviarPdf';
switch ($iPiel) {
	case 2:
		$sClaseImprime = 'iExcel';
		// $sClaseImprime2 = 'iPdf';
		break;
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
$iNumEncuestas = 0;
$iCaracter = -1;
if ($_REQUEST['idencuesta'] != 0) {
	$iNumEncuestas = 1;
} else {
	list($_REQUEST['idencuesta'], $_REQUEST['id21']) = f1926_Siguiente($idTercero, $objDB);
	if ($_REQUEST['idencuesta'] != 0) {
		$iNumEncuestas = 1;
	}
}
if ($_REQUEST['idencuesta'] != 0) {
	$sTituloEncuesta = 'No se encuentra la encuesta {Ref: ' . $_REQUEST['idencuesta'] . '}';
	$sHtmlEncuesta = '';
	$html_preguntas = '';
	$html_cierre = '';
	$bVuelve = true;
	$bAjustarTitulo = false;
	$sSQL = 'SELECT * FROM even16encuesta WHERE even16id=' . $_REQUEST['idencuesta'] . '';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$sTituloEncuesta = cadena_notildes($fila['even16encabezado']);
		$iCaracter = $fila['even16caracter'];
		$html_cierre = '';
		$sSQL = 'SELECT T1.even21idperaca, T1.even21idcurso, T1.even21idbloquedo, T1.even21terminada, T1.even21pais, 
		T1.even21depto, T1.even21ciudad, T1.even21fechanace, T1.even21idzona, T1.even21idcead, T1.even21perfil, 
		T1.even21idprograma, T1.even21obligatoria 
		FROM even21encuestaaplica AS T1 
		WHERE T1.even21id=' . $_REQUEST['id21'] . '';
		$tabla21 = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla21) > 0) {
			$bVuelve = false;
			$fila21 = $objDB->sf($tabla21);
			$_REQUEST['even21pais'] = $fila21['even21pais'];
			$_REQUEST['even21depto'] = $fila21['even21depto'];
			$_REQUEST['even21ciudad'] = $fila21['even21ciudad'];
			$_REQUEST['even21fechanace'] = $fila21['even21fechanace'];
			$_REQUEST['even21idzona'] = $fila21['even21idzona'];
			$_REQUEST['even21idcead'] = $fila21['even21idcead'];
			$_REQUEST['even21perfil'] = $fila21['even21perfil'];
			$_REQUEST['even21idprograma'] = $fila21['even21idprograma'];
			if ($fila21['even21terminada'] == 'S') {
				$bVuelve = true;
			} else {
				$bCompletarVacios = true;
				if ($fila21['even21obligatoria'] != 'S') {
					$bVuelve = true;
				}
			}
			$sTituloPeriodo = '';
			if ($fila21['even21idperaca'] != 0) {
				$sSQL = 'SELECT exte02titulo FROM exte02per_aca WHERE exte02id=' . $fila21['even21idperaca'] . '';
				$tabla2 = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($tabla2) > 0) {
					$fila2 = $objDB->sf($tabla2);
					$sTituloPeriodo = '<h1><b>Periodo: ' . cadena_notildes($fila2['exte02titulo']) . '</b></h1>';
				}
			}
			$sTituloCurso = '';
			if ($fila21['even21idcurso'] != 0) {
				$idCurso = $fila21['even21idcurso'];
				$sSQL = 'SELECT unad40titulo, unad40nombre FROM unad40curso WHERE unad40id=' . $idCurso . '';
				$tabla40 = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($tabla40) > 0) {
					$fila40 = $objDB->sf($tabla40);
					$sTituloCurso = '<h2><b>Curso: ' . $fila40['unad40titulo'] . ' - ' . cadena_notildes($fila40['unad40nombre']) . '</b></h2>';
				}
			}
			$sHtmlEncuesta = '<div class="my-2">';
			$sHtmlEncuesta = $sHtmlEncuesta . $sTituloPeriodo;
			$sHtmlEncuesta = $sHtmlEncuesta . $sTituloCurso;
			$sHtmlEncuesta = $sHtmlEncuesta . '</div>';
			$sHtmlEncuesta = $sHtmlEncuesta . '<div class="my-2">';
			$sHtmlEncuesta = $sHtmlEncuesta . $sTituloEncuesta;
			$sHtmlEncuesta = $sHtmlEncuesta . '</div>';

		}
		list($html_preguntas, $sDebugR) = f1926_Html_RespuestasV2($_REQUEST['id21'], $objDB, 0, 0, $bResaltarPendientes, $bDebug);
		$sDebug = $sDebug . $sDebugR;
	} else {
		$sHtmlEncuesta = html_Alerta($sTituloEncuesta, 'rojo');
	}
	if ($bVuelve) {
		$html_cierre = $html_cierre . $ETI['msg_vuelveindex'];
	}
}
if ($bCompletarVacios) {
	$bTraerTercero = false;
	$bTraerInfoMatricula = false;
	$sInfoUpd = '';
	if ($_REQUEST['unad11genero'] == '') {
		$bTraerTercero = true;
	}
	if (!$bTraerTercero) {
		if (!fecha_esvalida($_REQUEST['even21fechanace'])) {
			$bTraerTercero = true;
		}
	}
	if (!$bTraerTercero) {
		if ($_REQUEST['even21depto'] == '') {
			$bTraerTercero = true;
		}
	}
	if (!$bTraerTercero) {
		if ($_REQUEST['even21ciudad'] == '') {
			$bTraerTercero = true;
		}
	}
	if ($bTraerTercero) {
		$sSQL = 'SELECT unad11fechanace, unad11genero, unad11pais, unad11deptodoc, unad11ciudaddoc FROM unad11terceros WHERE unad11id=' . $idTercero . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			if ($_REQUEST['unad11genero'] == '') {
				$_REQUEST['unad11genero'] = $fila['unad11genero'];
			}
			if (!fecha_esvalida($_REQUEST['even21fechanace'])) {
				$_REQUEST['even21fechanace'] = $fila['unad11fechanace'];
			}
			if ($_REQUEST['even21pais'] == '') {
				$_REQUEST['even21pais'] = $fila['unad11pais'];
			}
			if ($_REQUEST['even21depto'] == '') {
				$_REQUEST['even21depto'] = $fila['unad11deptodoc'];
			}
			if ($_REQUEST['even21ciudad'] == '') {
				$_REQUEST['even21ciudad'] = $fila['unad11ciudaddoc'];
			}
		}
	}
	if ((int)$_REQUEST['even21idzona'] == 0) {
		$bTraerInfoMatricula = true;
	}
	if ((int)$_REQUEST['even21idcead'] == 0) {
		$bTraerInfoMatricula = true;
	}
	if ((int)$_REQUEST['even21idprograma'] == 0) {
		$bTraerInfoMatricula = true;
	}
	if ($bTraerInfoMatricula) {
		$sSQL = 'SELECT core01idzona, core011idcead, core01idprograma FROM core01estprograma WHERE core01idtercero=' . $idTercero . ' AND core01idestado NOT IN (-2, 11, 12, 98, 99) ORDER BY core01id DESC';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			if ((int)$_REQUEST['even21idzona'] == 0) {
				$_REQUEST['even21idzona'] = $fila['core01idzona'];
			}
			if ((int)$_REQUEST['even21idcead'] == 0) {
				$_REQUEST['even21idcead'] = $fila['core011idcead'];
			}
			if ((int)$_REQUEST['even21idprograma'] == 0) {
				$_REQUEST['even21idprograma'] = $fila['core01idprograma'];
			}
		}
	}
	if ($sInfoUpd != '') {
		//T1.even21idperaca, T1.even21idcurso, T1.even21idbloquedo, T1.even21terminada, T1.even21pais, T1.even21depto, T1.even21ciudad, T1.even21fechanace, T1.even21idzona, T1.even21idcead, T1.even21perfil, T1.even21idprograma, T1.even21obligatoria
		$sSQL = 'UPDATE even21encuestaaplica SET ' . $sInfoUpd . ' WHERE even21id=' . $_REQUEST['id21'] . '';
		$tabla = $objDB->ejecutasql($sSQL);
	}
}
$bEditaPerfil = false;
if ($_REQUEST['idencuesta'] != 0) {
	list($unad11genero_nombre, $sErrorDet) = tabla_campoxid('unad22combos', 'unad22nombre', 'unad22codopcion', '"' . $_REQUEST['unad11genero'] . '"', '{' . $ETI['msg_sindato'] . '}', $objDB);
	$html_unad11genero = html_oculto('unad11genero', $_REQUEST['unad11genero'], $unad11genero_nombre);
	// list($even21pais_nombre, $sErrorDet) = tabla_campoxid('unad18pais', 'unad18nombre', 'unad18codigo', '"' . $_REQUEST['even21pais'] . '"', '{' . $ETI['msg_sindato'] . '}', $objDB);
	// $html_even21pais = html_oculto('even21pais', $_REQUEST['even21pais'], $even21pais_nombre);
	$html_even21pais=html_combo('even21pais', 'unad18codigo', 'unad18nombre', 'unad18pais', '', 'unad18nombre', $_REQUEST['even21pais'], $objDB, 'carga_combo_even21depto();', false, '{'.$ETI['msg_seleccione'].'}', '');
	// list($even21depto_nombre, $sErrorDet) = tabla_campoxid('unad19depto', 'unad19nombre', 'unad19codigo', '"' . $_REQUEST['even21depto'] . '"', '{' . $ETI['msg_sindato'] . '}', $objDB);
	// $html_even21depto = html_oculto('even21depto', $_REQUEST['even21depto'], $even21depto_nombre);
	$html_even21depto = f1926_HTMLComboV2_even21depto($objDB, $objCombos, $_REQUEST['even21depto'], $_REQUEST['even21pais']);
	// list($even21ciudad_nombre, $sErrorDet) = tabla_campoxid('unad20ciudad', 'unad20nombre', 'unad20codigo', '"' . $_REQUEST['even21ciudad'] . '"', '{' . $ETI['msg_sindato'] . '}', $objDB);
	// $html_even21ciudad = html_oculto('even21ciudad', $_REQUEST['even21ciudad'], $even21ciudad_nombre);
	$html_even21ciudad=html_combo_even21ciudad($objDB, $_REQUEST['even21ciudad'], $_REQUEST['even21depto']);
	list($even21perfil_nombre, $sErrorDet) = tabla_campoxid('even30perfilencuesta', 'even30nombre', 'even30id', $_REQUEST['even21perfil'], '{' . $ETI['msg_sindato'] . '}', $objDB);
	$html_even21perfil = html_oculto('even21perfil', $_REQUEST['even21perfil'], $even21perfil_nombre);
	// Datos unad
	list($even21idzona_nombre, $sErrorDet) = tabla_campoxid('unad23zona', 'unad23nombre', 'unad23id', $_REQUEST['even21idzona'], '{' . $ETI['msg_sindato'] . '}', $objDB);
	$html_even21idzona = html_oculto('even21idzona', $_REQUEST['even21idzona'], $even21idzona_nombre);
	list($even21idcead_nombre, $sErrorDet) = tabla_campoxid('unad24sede', 'unad24nombre', 'unad24id', $_REQUEST['even21idcead'], '{' . $ETI['msg_sindato'] . '}', $objDB);
	$html_even21idcead = html_oculto('even21idcead', $_REQUEST['even21idcead'], $even21idcead_nombre);
	list($even21idprograma_nombre, $sErrorDet) = tabla_campoxid('core09programa', 'CONCAT(core09nombre, " [", core09codigo, "]", CASE core09activo WHEN "S" THEN "" ELSE " [INACTIVO]" END)', 'core09id', $_REQUEST['even21idprograma'], '{' . $ETI['msg_sindato'] . '}', $objDB);
	$html_even21idprograma = html_oculto('even21idprograma', $_REQUEST['even21idprograma'], $even21idprograma_nombre);
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
if ($_REQUEST['idencuesta'] == 0) {
	$aBotones[$iNumBoton] = array('limpiapagina()', $ETI['bt_limpiar'], 'btMiniLimpiar');
	$iNumBoton++;
}
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

	function carga_combo_even21depto() {
		var params = new Array();
		params[0] = window.document.frmedita.even21pais.value;
		params[1] = window.document.frmedita.id21.value;
		params[9] = window.document.frmedita.debug.value;
		xajax_Cargar_even21depto(params);
	}

	function carga_combo_even21ciudad() {
		var params = new Array();
		params[0] = window.document.frmedita.even21depto.value;
		params[1] = window.document.frmedita.id21.value;
		params[9] = window.document.frmedita.debug.value;
		xajax_Cargar_even21ciudad(params);
	}

	function guardar_even21ciudad() {
		var params = new Array();
		params[0] = window.document.frmedita.even21ciudad.value;
		params[1] = window.document.frmedita.id21.value;
		params[9] = window.document.frmedita.debug.value;
		xajax_f1621_Guardar_even21ciudad(params);
	}

	function guardar_even21fechanace() {
		var params = new Array();
		params[0] = window.document.frmedita.even21fechanace.value;
		params[1] = window.document.frmedita.id21.value;
		params[9] = window.document.frmedita.debug.value;
		xajax_f1621_Guardar_even21fechanace(params);
	}

	function carga_combo_even21idcead() {
		var params = new Array();
		params[0] = window.document.frmedita.even21idzona.value;
		params[1] = window.document.frmedita.id21.value;
		params[9] = window.document.frmedita.debug.value;
		xajax_Cargar_even21idcead(params);
	}

	function guardar_even21idcead() {
		var params = new Array();
		params[0] = window.document.frmedita.even21idcead.value;
		params[1] = window.document.frmedita.id21.value;
		params[9] = window.document.frmedita.debug.value;
		xajax_f1621_Guardar_even21idcead(params);
	}

	function guardar_even21perfil() {
		var params = new Array();
		params[0] = window.document.frmedita.even21perfil.value;
		params[1] = window.document.frmedita.id21.value;
		params[9] = window.document.frmedita.debug.value;
		xajax_f1621_Guardar_even21perfil(params);
	}

	function guardar_even21idprograma() {
		var params = new Array();
		params[0] = window.document.frmedita.even21idprograma.value;
		params[1] = window.document.frmedita.id21.value;
		params[9] = window.document.frmedita.debug.value;
		xajax_f1621_Guardar_even21idprograma(params);
	}

	function paginarf1921() {
		var params = new Array();
		params[101] = window.document.frmedita.paginaf1921.value;
		params[102] = window.document.frmedita.lppf1921.value;
		//params[103]=window.document.frmedita.bnombre.value;
		//params[104]=window.document.frmedita.blistar.value;
		xajax_f1921_HtmlTabla(params);
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

	function CargarCuerpo() {
		var params = new Array();
		params[0] = window.document.frmedita.id21.value;
		xajax_f1926_CargarCuerpo(params);
	}

	function selrpta(idRpta, valor, itipo, iDiverge, idPreg) {
		var params = new Array();
		params[1] = idRpta;
		params[2] = valor;
		params[3] = itipo;
		params[4] = iDiverge;
		params[5] = window.document.frmedita.id21.value;
		params[6] = idPreg;
		params[9] = window.document.frmedita.debug.value;
		if (iDiverge == 1) {
			window.document.frmedita.iscroll.value = window.pageYOffset;
			// expandesector(98);
		}
		xajax_f1926_GuardaRptaV2(params);
	}

	function marcaropcion(idRpta, iConsec, bChequeada) {
		var iValor = 0;
		if (bChequeada) {
			iValor = 1;
		}
		var params = new Array();
		params[1] = idRpta;
		params[2] = iConsec;
		params[3] = iValor;
		xajax_f1926_MarcarOpcion(params);
	}

	function rptabierta(idRpta, sValor) {
		var params = new Array();
		params[1] = idRpta;
		params[2] = sValor;
		xajax_f1926_GuadaAbierta(params);
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
<input id="idencuesta" name="idencuesta" type="hidden" value="<?php echo $_REQUEST['idencuesta']; ?>" />
<input id="id21" name="id21" type="hidden" value="<?php echo $_REQUEST['id21']; ?>" />
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
if ($iNumEncuestas == 0) {
	$sMsg = $ETI['msg_noencuesta'] . ' <a class="lnkresalte" href="' . $sUrlTablero . '">' . $ETI['msg_noencuesta_link'] . '</a>';
	echo html_Alerta($sMsg);
} else {
	if ($iCaracter > 0) {
		echo html_Alerta($ETI['msg_obligatoria']);
	}
?>
<?php
// Mostrar datos de la encuesta
echo $sHtmlEncuesta;
?>
<div class="salto1px"></div>
<div class="container-datos">
<div class="datos-residencia">
<?php
echo '<b>' . $ETI['msg_lugar'] . '</b>';
?>
<div class="datos__content">
<div>
<label>
<?php
echo $ETI['even21pais'];
?>
</label>
<?php
echo $html_even21pais;
?>
</div>
<div>
<label>
<?php
echo $ETI['even21depto'];
?>
</label>
<div id="div_even21depto">
<?php
echo $html_even21depto;
?>
</div>
</div>
<div>
<label>
<?php
echo $ETI['even21ciudad'];
?>
</label>
<div id="div_even21ciudad">
<?php
echo $html_even21ciudad;
?>
</div>
</div>
<div>
<label>
<?php
echo $ETI['even21fechanace'];
?>
</label>
<?php
echo html_oculto('even21fechanace', $_REQUEST['even21fechanace']);
?>
</div>
<div>
<label>
<?php
echo $ETI['msg_genero'];
?>
</label>
<?php
echo $html_unad11genero;
?>
</div>
</div>
</div>
<div class="datos-unad">
<?php
echo '<b>' . $ETI['msg_vinculo'] . '</b>';
?>
<div class="datos__content">
<div>
<label>
<?php
echo $ETI['even21idzona'];
?>
</label>
<?php
echo $html_even21idzona;
?>
</div>
<div>
<label>
<?php
echo $ETI['even21idcead'];
?>
</label>
<?php
echo $html_even21idcead;
?>
</div>
<div>
<label>
<?php
echo $ETI['even21perfil'];
?>
</label>
<?php
echo $html_even21perfil;
?>
</div>
<div>
<label>
<?php
echo $ETI['even21idprograma'];
?>
</label>
<?php
echo $html_even21idprograma;
?>
</div>
</div>
</div>
</div>
<div id="div_respuestas">
<?php
echo $html_preguntas;
?>
</div>
<div class="salto1px"></div>
<?php
echo $html_cierre;
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
<?php
if ($bEditaPerfil) {
?>
<script language="javascript">
	$().ready(function() {
		$("#even21idprograma").chosen();
	});
</script>
<?php
}
?>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>unad_todas2024v2.js"></script>
<?php
forma_piedepagina();
