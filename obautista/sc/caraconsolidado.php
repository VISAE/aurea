<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2018 - 2020 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- © Omar Augusto Bautista - UNAD - 2026 ---
--- omar.bautista@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.21.0 jueves, 21 de junio de 2018
--- Modelo Versión 2.25.6 miércoles, 9 de septiembre de 2020
--- Modelo Versión 2.28.1 jueves, 28 de abril de 2022
--- Modelo Versión 3.2.5c miércoles, 29 de julio de 2026
*/

/** Archivo caraconsolidado.php.
 * Modulo 2350
 * @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
 * @param debug=1 (Opcional), bandera para indicar si se generan datos de depuración
 * @date Wednesday, June 21, 2018*
 *
 * Cambios 21 de mayo de 2020
 * 1. Adición de combos de Escuela y Programa
 * Omar Augusto Bautista Mora - UNAD - 2020
 * omar.bautista@unad.edu.co
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
	$sDebug = '<div class="log"><span class="log__time">' . date('H:i:s') . $sMili . '</span><code class="log__description"> Inicia pagina</code></div>';
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
require $APP->rutacomun . 'libaurea.php';
require $APP->rutacomun . 'libcomp.php';
require $APP->rutacomun . 'libdatos.php';
require $APP->rutacomun . 'libhtml.php';
require $APP->rutacomun . 'libcombos.php';
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
$iConsecutivoMenu = 1;
$iMinVerDB = 7774;
$iCodModulo = 2350;
$iCodModuloConsulta = $iCodModulo;
$sIdioma = AUREA_Idioma();
$audita[1] = false;
// -- Se cargan los archivos de idioma
$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
if (!file_exists($mensajes_todas)) {
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
}
/*
$mensajes_2300 = 'lg/lg_2300_' . $sIdioma . '.php';
if (!file_exists($mensajes_2300)) {
	$mensajes_2300 = 'lg/lg_2300_es.php';
}
require $mensajes_2300;
*/
$mensajes_2350 = 'lg/lg_2350_' . $sIdioma . '.php';
if (!file_exists($mensajes_2350)) {
	$mensajes_2350 = 'lg/lg_2350_es.php';
}
require $mensajes_todas;
require $mensajes_2350;
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
$sTituloModulo = $ETI['titulo_2350'];
switch ($iPiel) {
	case 2:
		$sAnchoExpandeContrae = '';
		$bBloqueTitulo = false;
		break;
}
// --- Final de las variables para la forma
if ($bDebug) {
	$sDebug = $sDebug . log_debug('Probando conexi&oacute;n con la base de datos <b>' . $APP->dbname . '</b> en <b>' . $APP->dbhost . '</b>');
}
$bCargaMenu = true;
if (!$objDB->Conectar()) {
	$bCargaMenu = false;
	$bCerrado = true;
	$sMsgCierre = '<div class="MarquesinaGrande">Disculpe las molestias estamos en este momento nuestros servicios no estas disponibles.<br>Por favor intente acceder mas tarde.<br>Si el problema persiste por favor informe al administrador del sistema.</div>';
	if ($bDebug) {
		$sDebug = $sDebug . log_debug('Error al intentar conectar con la base de datos <b>' . $objDB->serror . '</b>');
	}
}
if (!$bCerrado) {
	$iVerDB = version_upd($objDB);
	if ($iMinVerDB > $iVerDB) {
		$bCerrado = true;
		$sMsgCierre = '<div class="MarquesinaGrande">La base de datos se encuentra desactualizada para este modulo.<br>Por favor informe al administrador del sistema.</div>';
		if ($bDebug) {
			$sDebug = $sDebug . log_debug('<b>DB DESACTUALIZADA [Requerida:' . $iMinVerDB . ' - Encontrada:' . $iVerDB . ']</b>');
		}
	} else {
		if ($bDebug) {
			$sDebug = $sDebug . log_debug('Versi&oacute;n DB <b>' . $iVerDB . '</b> [Requerida:' . $iMinVerDB . ']');
		}
	}
}
if (!$bCerrado) {
	list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModuloConsulta, 1, $idTercero, $objDB);
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
			require $APP->rutacomun . 'unad_forma2024.php';
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
		echo console_debug($sDebug);
	}
	echo $objForma->htmlFinMarco();
	forma_piedepagina();
	die();
}
if (!$bPeticionXAJAX) {
	if (noticias_pendientes($objDB)) {
		$objDB->CerrarConexion();
		header('Location:noticia.php?ret=caraconsolidado.php');
		die();
	}
}
$seg_1707 = 0;
$bDevuelve = false;
list($bDevuelve, $sDebugP, $seg_1707) = seg_revisa_permisoV3($iCodModulo, 1707, $_SESSION['unad_id_tercero'], $objDB, $bDebug);
$sDebug = $sDebug . $sDebugP;
if (isset($_REQUEST['deb_tipodoc']) == 0) {
	$_REQUEST['deb_tipodoc'] = $APP->tipo_doc;
}
$_REQUEST['deb_tipodoc'] = cadena_Validar($_REQUEST['deb_tipodoc']);
$_REQUEST['deb_doc'] = cadena_Validar($_REQUEST['deb_doc']);
if ($_REQUEST['deb_doc'] != '') {
	if ($seg_1707 == 1) {
		$sSQL = 'SELECT unad11id, unad11razonsocial FROM unad11terceros WHERE unad11doc="' . $_REQUEST['deb_doc'] . '" AND unad11tipodoc="' . $_REQUEST['deb_tipodoc'] . '"';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			$idTercero = $fila['unad11id'];
			$bOtroUsuario = true;
			if ($bDebug) {
				$sDebug = $sDebug . log_debug('Se verifica la ventana de trabajo para el usuario ' . $fila['unad11razonsocial'] . '.');
			}
		} else {
			$sError = $ETI['msg_doc_no_encontrado'] . ' &quot;' . $_REQUEST['deb_tipodoc'] . ' ' . $_REQUEST['deb_doc'] . '&quot;';
			$_REQUEST['deb_doc'] = '';
		}
	} else {
		if ($bDebug) {
			$sDebug = $sDebug . log_debug('No cuenta con permiso de ingreso como otro usuario [Modulo ' . $iCodModulo . ' Permiso 1707]');
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
// -- 2350 cara50consolidado
require 'lib2350.php';
$xajax = new xajax();
$xajax->configure('javascript URI', $APP->rutacomun . 'xajax/');
$xajax->register(XAJAX_FUNCTION, 'f2350_Combocara50idcentro');
$xajax->register(XAJAX_FUNCTION, 'f2350_Combocore50idprograma');
$xajax->register(XAJAX_FUNCTION, 'sesion_abandona_V2');
$xajax->register(XAJAX_FUNCTION, 'sesion_mantenerV4');
$xajax->register(XAJAX_FUNCTION, 'f2350_HtmlTabla');
$xajax->register(XAJAX_FUNCTION, 'f2350_ExisteDato');
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
if (isset($_REQUEST['paginaf2350']) == 0) {
	$_REQUEST['paginaf2350'] = 1;
}
if (isset($_REQUEST['lppf2350']) == 0) {
	$_REQUEST['lppf2350'] = 20;
}
if (isset($_REQUEST['boculta2350']) == 0) {
	$_REQUEST['boculta2350'] = 0;
}
// -- Inicializar variables de datos.
if (isset($_REQUEST['cara50idperaca']) == 0) {
	$_REQUEST['cara50idperaca'] = '';
}
if (isset($_REQUEST['cara50idzona']) == 0) {
	$_REQUEST['cara50idzona'] = '';
}
if (isset($_REQUEST['cara50idcentro']) == 0) {
	$_REQUEST['cara50idcentro'] = '';
}
if (isset($_REQUEST['core50idescuela']) == 0) {
	$_REQUEST['core50idescuela'] = '';
}
if (isset($_REQUEST['core50idprograma']) == 0) {
	$_REQUEST['core50idprograma'] = '';
}
if (isset($_REQUEST['core50idtipo']) == 0) {
	$_REQUEST['core50idtipo'] = 0;
}
if (isset($_REQUEST['cara50poblacion']) == 0) {
	$_REQUEST['cara50poblacion'] = 0;
}
if (isset($_REQUEST['cara50periodoacomp']) == 0) {
	$_REQUEST['cara50periodoacomp'] = '';
}
if (isset($_REQUEST['cara50convenio']) == 0) {
	$_REQUEST['cara50convenio'] = '';
}
if (isset($_REQUEST['cara50periodomatricula']) == 0) {
	$_REQUEST['cara50periodomatricula'] = '';
}
if (isset($_REQUEST['cara50tipomatricula']) == 0) {
	$_REQUEST['cara50tipomatricula'] = 0;
}
if (isset($_REQUEST['cara50listadoc']) == 0) {
	$_REQUEST['cara50listadoc'] = '';
}
$_REQUEST['cara50idperaca'] = numeros_validar($_REQUEST['cara50idperaca']);
$_REQUEST['cara50idzona'] = numeros_validar($_REQUEST['cara50idzona']);
$_REQUEST['cara50idcentro'] = numeros_validar($_REQUEST['cara50idcentro']);
$_REQUEST['core50idescuela'] = numeros_validar($_REQUEST['core50idescuela']);
$_REQUEST['core50idprograma'] = numeros_validar($_REQUEST['core50idprograma']);
$_REQUEST['core50idtipo'] = numeros_validar($_REQUEST['core50idtipo']);
$_REQUEST['cara50poblacion'] = numeros_validar($_REQUEST['cara50poblacion']);
$_REQUEST['cara50periodoacomp'] = numeros_validar($_REQUEST['cara50periodoacomp']);
$_REQUEST['cara50convenio'] = numeros_validar($_REQUEST['cara50convenio']);
$_REQUEST['cara50periodomatricula'] = numeros_validar($_REQUEST['cara50periodomatricula']);
$_REQUEST['cara50tipomatricula'] = numeros_validar($_REQUEST['cara50tipomatricula']);
$_REQUEST['cara50listadoc'] = cadena_Validar($_REQUEST['cara50listadoc']);
// Espacio para inicializar otras variables
if (isset($_REQUEST['csv_separa']) == 0) {
	$_REQUEST['csv_separa'] = ';';
}
if (isset($_REQUEST['bnombre']) == 0) {
	$_REQUEST['bnombre'] = '';
}
/*
if (isset($_REQUEST['blistar']) == 0) {
	$_REQUEST['blistar'] = '';
}
*/
//Si Modifica o Elimina Cargar los campos
if (($_REQUEST['paso'] == 1) || ($_REQUEST['paso'] == 3)) {
	$sNomTabla2350 = f2350_NombreTabla();
	$sSQL = 'SELECT * FROM ' . $sNomTabla2350 . ' WHERE cara50idperaca=' . $_REQUEST['cara50idperaca'] . '';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$_REQUEST['cara50idperaca'] = $fila['cara50idperaca'];
		$_REQUEST['cara50idzona'] = $fila['cara50idzona'];
		$_REQUEST['cara50idcentro'] = $fila['cara50idcentro'];
		$_REQUEST['core50idescuela'] = $fila['core50idescuela'];
		$_REQUEST['core50idprograma'] = $fila['core50idprograma'];
		$_REQUEST['core50idtipo'] = $fila['core50idtipo'];
		$_REQUEST['cara50poblacion'] = $fila['cara50poblacion'];
		$_REQUEST['cara50periodoacomp'] = $fila['cara50periodoacomp'];
		$_REQUEST['cara50convenio'] = $fila['cara50convenio'];
		$_REQUEST['cara50periodomatricula'] = $fila['cara50periodomatricula'];
		$_REQUEST['cara50tipomatricula'] = $fila['cara50tipomatricula'];
		$_REQUEST['cara50listadoc'] = $fila['cara50listadoc'];
		$bcargo = true;
		$_REQUEST['paso'] = 2;
		$_REQUEST['boculta2350'] = 0;
		$bLimpiaHijos = true;
	} else {
		$_REQUEST['paso'] = 0;
	}
}
//Insertar o modificar un elemento
if (($_REQUEST['paso'] == 10) || ($_REQUEST['paso'] == 12)) {
	$bMueveScroll = true;
	list($_REQUEST, $sError, $iTipoError, $sDebugGuardar) = f2350_db_GuardarV2b($_REQUEST, $objDB, $bDebug, $idTercero);
	$sDebug = $sDebug . $sDebugGuardar;
	if ($sError == '') {
		$sError = '<b>' . $ETI['msg_itemguardado'] . '</b>';
		$iTipoError = 1;
	}
}
//Eliminar un elemento
if ($_REQUEST['paso'] == 13) {
	$_REQUEST['paso'] = 2;
	if ($sError == '') {
		list($sError, $iTipoError, $sDebugElimina) = f2350_db_Eliminar($_REQUEST[''], $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugElimina;
	}
	if ($sError == '') {
		$_REQUEST['paso'] = -1;
		$sError = $ETI['msg_itemeliminado'];
		$iTipoError = 1;
	}
}
// Proceso de pagina - ejemplo
if ($_REQUEST['paso'] == 21) {
	$_REQUEST['paso'] = 2;
	if ($sError == '') {
		// acciones a ejecutar
	}
	if ($sError == '') {
		$sError = $ETI['msg_procesoterminado'];
		$iTipoError = 1;
	}
}
//limpiar la pantalla
if ($_REQUEST['paso'] == -1) {
	$_REQUEST['cara50idperaca'] = '';
	$_REQUEST['cara50idzona'] = '';
	$_REQUEST['cara50idcentro'] = '';
	$_REQUEST['core50idescuela'] = '';
	$_REQUEST['core50idprograma'] = '';
	$_REQUEST['core50idtipo'] = '';
	$_REQUEST['cara50poblacion'] = 1;
	$_REQUEST['cara50periodoacomp'] = '';
	$_REQUEST['cara50convenio'] = '';
	$_REQUEST['cara50periodomatricula'] = '';
	$_REQUEST['cara50tipomatricula'] = '';
	$_REQUEST['cara50listadoc'] = '';
	$_REQUEST['paso'] = 0;
}
if ($bLimpiaHijos) {
}
//AQUI SE DEBEN CARGAR TODOS LOS DATOS QUE LA FORMA NECESITE.
$bPuedeGuardar = false;
$bConEliminar = false;
$bEditable = true; // Esta bandera se deja a modo de ejemplo, puede requerir ser dividida muchas veces.
$bHayImprimir = false;
$bHayImprimir2 = false;
$bAdministra = false;
$bEsZonal = false;
$sScriptImprime = 'imprimeexcel()';
$sScriptImprime2 = 'imprimeexcel(2)';
$sClaseImprime = 'iExcel';
$sClaseImprime2 = 'iExcel';
if ($iPiel == 0) {
	$sClaseImprime = 'btEnviarExcel';
	$sClaseImprime2 = 'btEnviarExcel';
}
$bConSector2 = false;
//Permisos adicionales
$seg_5 = 0;
$seg_6 = 0;
$seg_12 = 0;
$seg_1710 = 0;
list($bHayImprimir, $sDebugP, $seg_6) = seg_revisa_permisoV3($iCodModulo, 6, $idTercero, $objDB);
if ($bHayImprimir) {
	$bHayImprimir2 = true;
}
$sDebug = $sDebug . $sDebugP;
list($bAdministra, $sDebugP, $seg_12) = seg_revisa_permisoV3($iCodModulo, 12, $idTercero, $objDB);
$sDebug = $sDebug . $sDebugP;
list($bEsZonal, $sDebugP, $seg_1710) = seg_revisa_permisoV3($iCodModulo, 1710, $idTercero, $objDB);
$sDebug = $sDebug . $sDebugP;
if ((int)$_REQUEST['paso'] != 0) {
	//list($bHayImprimir2, $sDebugP, $seg_5) = seg_revisa_permisoV3($iCodModulo, 5, $idTercero, $objDB);
	$bConEliminar = true;
	$bEditable = false;
}
//DATOS PARA COMPLETAR EL FORMULARIO
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
$objCombos->nuevo('cara50idzona', $_REQUEST['cara50idzona'], true, '{' . $ETI['msg_seleccione'] . '}');
$objCombos->bEsCombobox = true;
$objCombos->sAccion = 'carga_combo_cara50idcentro();';
$bParteZona=true;
//Administrar zonas.
if ($seg_1710 == 1) {
	$bParteZona = false;
}
//Consultar datos de otros usuarios.
if ($seg_12 == 1) {
	$bParteZona = false;
}
//Ser consejero...
list($bEsConsejero, $sIdCentro, $sDebug) = f2300_EsConsejero($idTercero, $objDB, $bDebug);
if ($bEsConsejero) {
	$bParteZona = false;
}
if (!$bParteZona) {
	$sCondiZona = '';
} else {
	list($sIdZona, $idPrimera, $sDebugZ) = f2300_ZonasTercero($idTercero, $objDB, $bDebug);
	if ($_REQUEST['cara50idzona'] == '') {
		$_REQUEST['cara50idzona'] = $idPrimera;
	}
	$sCondiZona = ' unad23id IN (' . $sIdZona . ') AND ';
}
$sSQL = 'SELECT unad23id AS id, unad23nombre AS nombre FROM unad23zona WHERE ' . $sCondiZona . ' unad23conestudiantes="S" ORDER BY unad23nombre';
$html_cara50idzona = $objCombos->html($sSQL, $objDB);
$html_cara50idcentro = f2350_HTMLComboV2_cara50idcentro($objDB, $objCombos, $_REQUEST['cara50idcentro'], $_REQUEST['cara50idzona']);
$objCombos->nuevo('core50idescuela', $_REQUEST['core50idescuela'], true, '{' . $ETI['msg_seleccione'] . '}');
$objCombos->bEsCombobox = true;
$objCombos->sAccion = 'carga_combo_core50idprograma();';
$sSQL = f2212_ConsultaComboEscuela();
$html_core50idescuela = $objCombos->html($sSQL, $objDB);
$html_core50idprograma = f2350_HTMLComboV2_core50idprograma($objDB, $objCombos, $_REQUEST['core50idprograma'], $_REQUEST['core50idescuela']);
$html_core50idtipo = f2350_HTMLComboV2_core50idtipo($objDB, $objCombos, $_REQUEST['core50idtipo']);
$objCombos->nuevo('cara50poblacion', $_REQUEST['cara50poblacion'], false, '{' . $ETI['msg_seleccione'] . '}');
$objCombos->bEsCombobox = true;
$objCombos->addItem(1, 'Donde soy consejero');
$bGeneral = false;
if ($bAdministra) {
	$bGeneral = true;
}
if ($bEsZonal) {
	$bGeneral = true;
}
if ($bGeneral) {
	$objCombos->addItem(9, 'Todos');
}
$html_cara50poblacion = $objCombos->html('', $objDB);
$html_cara50periodoacomp = f2350_HTMLComboV2_cara50periodoacomp($objDB, $objCombos, $_REQUEST['cara50periodoacomp']);
$html_cara50convenio = f2350_HTMLComboV2_cara50convenio($objDB, $objCombos, $_REQUEST['cara50convenio']);;
$objCombos->nuevo('cara50periodomatricula', $_REQUEST['cara50periodomatricula'], true, '{' . $ETI['msg_seleccione'] . '}');
$objCombos->bEsCombobox = true;
$sSQL = f146_ConsultaCombo('exte02id>0');
$html_cara50periodomatricula = $objCombos->html($sSQL, $objDB);
$objCombos->nuevo('cara50tipomatricula', $_REQUEST['cara50tipomatricula'], true, '{' . $ETI['msg_todos'] . '}', 0);
$objCombos->bEsCombobox = true;
$objCombos->addItem('0', $acara50tipomatricula[0]);
$objCombos->addArreglo($acara50tipomatricula, $icara50tipomatricula);
$sSQL = '';
$html_cara50tipomatricula = $objCombos->html($sSQL, $objDB);
$html_cara50idperaca = f2350_HTMLComboV2_cara50idperaca($objDB, $objCombos, $_REQUEST['cara50idperaca']);
//Alistar datos adicionales
$id_rpt = 0;
//$id_rpt=reportes_id(_Identificador_Tipo_Reporte_, $objDB);
/*
$objCombos->nuevo('blistar', $_REQUEST['blistar'], true, '{' . $ETI['msg_todos'] . '}');
$objCombos->sAccion = 'paginarf2350()';
$sSQL = '';
$html_blistar = $objCombos->html($sSQL, $objDB);
//$html_blistar = $objCombos->comboSistema(2350, 1, $objDB, 'paginarf2350()');
*/
if (true) {
	$objCombos->nuevo('csv_separa', $_REQUEST['csv_separa'], false);
	$objCombos->addItem(',', $ETI['msg_coma']);
	$objCombos->addItem(';', $ETI['msg_puntoycoma']);
	$sClaseLabel = 'Label200';
	if ($iPiel == 2) {
		$sClaseLabel = 'w-25';
	}
	$csv_separa = '<label class="' . $sClaseLabel . '">' . $ETI['msg_separador'] . '</label><label class="' . $sClaseLabel . '">' . $objCombos->html('', $objDB) . '</label>';
} else {
	$csv_separa = '<input id="csv_separa" name="csv_separa" type="hidden" value="," />';
}
$sTabla2350 = '';
if (false) {
	//Cargar las tablas de datos
	$aParametros[0] = ''; //$_REQUEST['p1_2350'];
	$aParametros[100] = $idTercero;
	$aParametros[101] = $_REQUEST['paginaf2350'];
	$aParametros[102] = $_REQUEST['lppf2350'];
	//$aParametros[103] = $_REQUEST['bnombre'];
	//$aParametros[104] = $_REQUEST['blistar'];
	// list($sTabla2350, $sDebugTabla) = f2350_TablaDetalleV2($aParametros, $objDB, $bDebug);
	// $sDebug = $sDebug . $sDebugTabla;
}
switch ($iPiel) {
	case 2:
		list($et_menu, $sDebugM) = html_Menu2023($APP->idsistema, $objDB, $iPiel, $bDebugMenu, $idTercero);
		break;
	default:
		list($et_menu, $sDebugM) = html_menuV2($APP->idsistema, $objDB, $iPiel, $bDebugMenu, $idTercero);
		break;
}
$sDebug = $sDebug . $sDebugM;
$objDB->CerrarConexion();
//FORMA
switch ($iPiel) {
	case 2:
		require $APP->rutacomun . 'unad_forma2024.php';
		forma_InicioV4($xajax, $sTituloModulo);
		$aRutas = array(
			array('./', $sTituloApp),
			array('./' . $sPaginaModulo, $sGrupoModulo),
			array('', $sTituloModulo)
		);
		$iNumBoton = 0;
		$aBotones[$iNumBoton] = array('muestraayuda(' . $APP->idsistema . ', ' . $iCodModulo . ')', $ETI['bt_ayuda'], 'iHelp');
		$iNumBoton++;
		if ($bConEliminar) {
			$aBotones[$iNumBoton] = array('eliminadato()', $ETI['bt_eliminar'], 'iDelete');
			$iNumBoton++;
		}
		if ($bHayImprimir) {
			$aBotones[$iNumBoton] = array($sScriptImprime, $ETI['bt_consolidado'], $sClaseImprime);
			$iNumBoton++;
		}
		if ($bHayImprimir2) {
			$aBotones[$iNumBoton] = array($sScriptImprime2, $ETI['bt_discrimina'], $sClaseImprime2);
			$iNumBoton++;
		}
		$aBotones[$iNumBoton] = array('limpiapagina()', $ETI['bt_limpiar'], 'iDocument');
		$iNumBoton++;
		if ($bPuedeGuardar) {
			$aBotones[$iNumBoton] = array('enviaguardar()', $ETI['bt_guardar'], 'iSaveFill');
			$iNumBoton++;
		}
		if ($bConSector2) {
			$aBotones[$iNumBoton] = array('expandesector(1)', $ETI['bt_volver'], 'iArrowBack', 2);
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
	function expandesector(codigo) {
		document.getElementById('div_sector1').style.display = 'none';
		document.getElementById('div_sector2').style.display = 'none';
		document.getElementById('div_sector95').style.display = 'none';
		document.getElementById('div_sector96').style.display = 'none';
		document.getElementById('div_sector98').style.display = 'none';
		document.getElementById('div_sector' + codigo).style.display = 'block';
		<?php
		switch ($iPiel) {
			case 2:
				echo "document.getElementById('botones_sector1').style.display = 'none';";
				if ($bConSector2) {
					echo "document.getElementById('botones_sector2').style.display = 'none';";
				}
		?>
				switch (codigo) {
					case 1:
					case 2:
						document.getElementById('botones_sector' + codigo).style.display = 'flex';
						break;
					default:
						//document.getElementById('botones_sector1').style.display = 'none';
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

	function imprimelista() {
		if (window.document.frmedita.seg_6.value == 1) {
			window.document.frmlista.consulta.value = window.document.frmedita.consulta_2350.value;
			window.document.frmlista.titulos.value = window.document.frmedita.titulos_2350.value;
			window.document.frmlista.nombrearchivo.value = 'Consolidado';
			window.document.frmlista.submit();
		} else {
			ModalMensaje("<?php echo $ERR['6']; ?>");
		}
	}

	function asignarvariables() {
		window.document.frmimpp.v3.value = window.document.frmedita.cara50idperaca.value;
		window.document.frmimpp.v4.value = window.document.frmedita.cara50idzona.value;
		window.document.frmimpp.v5.value = window.document.frmedita.cara50idcentro.value;
		window.document.frmimpp.v9.value = window.document.frmedita.core50idescuela.value;
		window.document.frmimpp.v10.value = window.document.frmedita.core50idprograma.value;
		window.document.frmimpp.v6.value = window.document.frmedita.core50idtipo.value;
		window.document.frmimpp.v7.value = window.document.frmedita.cara50poblacion.value;
		window.document.frmimpp.v11.value = window.document.frmedita.cara50periodoacomp.value;
		window.document.frmimpp.v8.value = window.document.frmedita.cara50convenio.value;
		window.document.frmimpp.v12.value = window.document.frmedita.cara50periodomatricula.value;
		window.document.frmimpp.v13.value = window.document.frmedita.cara50tipomatricula.value;
		window.document.frmimpp.v14.value = window.document.frmedita.cara50listadoc.value;
		window.document.frmimpp.separa.value = window.document.frmedita.csv_separa.value.trim();
		//window.document.frmimpp.v3.value = window.document.frmedita.bnombre.value;
		//window.document.frmimpp.v4.value = window.document.frmedita.bcodigo.value;
	}

	function imprimeexcel(iTipo = 1) {
		let sError = '';
		if (window.document.frmedita.seg_6.value != 1) {
			sError = "<?php echo $ERR['6']; ?>";
		}
		if (sError == '') {
			if (window.document.frmedita.cara50idperaca.value == '') {
				if (window.document.frmedita.cara50periodoacomp.value == '') {
					if (window.document.frmedita.cara50periodomatricula.value == '') {
						sError = "<?php echo $ERR['msg_periodo']; ?>";
					}
				}
			}
		}
		if (sError == '') {
			let sTipo = '';
			let sMensaje = '<?php echo $ETI['msg_imprimeexcel']; ?>';
			if (iTipo == 2) {
				sTipo = '_vbg';
				sMensaje = '<?php echo $ETI['msg_imprimeexcel_2']; ?>';
			}
			ModalConfirmV2(sMensaje, () => {
				asignarvariables();
				window.document.frmimpp.action = 't2301' + sTipo + '.php';
				window.document.frmimpp.submit();
			});
		} else {
			ModalMensaje(sError);
		}
	}
	
	function imprimep() {
		if (window.document.frmedita.seg_5.value == 1) {
			asignarvariables();
			window.document.frmimpp.action = 'p2350.php';
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
		datos[1] = window.document.frmedita.cara50idperaca.value;
		if ((datos[1] != '')) {
			xajax_f2350_ExisteDato(datos);
		}
	}

	function cargadato(llave1) {
		window.document.frmedita.cara50idperaca.value = String(llave1);
		window.document.frmedita.paso.value = 1;
		window.document.frmedita.submit();
	}

	function carga_combo_cara50idcentro() {
		let params = new Array();
		params[0] = window.document.frmedita.cara50idzona.value;
		document.getElementById('div_cara50idcentro').innerHTML = '<b>Procesando datos, por favor espere...</b><input id="cara50idcentro" name="cara50idcentro" type="hidden" value="" />';
		xajax_f2350_Combocara50idcentro(params);
	}

	function carga_combo_core50idprograma() {
		let params = new Array();
		params[0] = window.document.frmedita.core50idescuela.value;
		document.getElementById('div_core50idprograma').innerHTML = '<b>Procesando datos, por favor espere...</b><input id="core50idprograma" name="core50idprograma" type="hidden" value="" />';
		xajax_f2350_Combocore50idprograma(params);
	}

	function paginarf2350() {
		let params = new Array();
		params[99] = window.document.frmedita.debug.value;
		params[100] = <?php echo $idTercero; ?>;
		params[101] = window.document.frmedita.paginaf2350.value;
		params[102] = window.document.frmedita.lppf2350.value;
		//params[103] = window.document.frmedita.bnombre.value;
		//params[104] = window.document.frmedita.blistar.value;
		document.getElementById('div_f2350detalle').innerHTML = '<div class="GrupoCamposAyuda"><div class="MarquesinaMedia"><?php echo $ETI['msg_procesando_datos']; ?></div></div><input id="paginaf2350" name="paginaf2350" type="hidden" value="' + params[101] + '" /><input id="lppf2350" name="lppf2350" type="hidden" value="' + params[102] + '" />';
		xajax_f2350_HtmlTabla(params);
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
		document.getElementById("cara50idperaca").focus();
	}

	function mantener_sesion() {
		xajax_sesion_mantenerV4();
	}
	setInterval('xajax_sesion_abandona_V2();', 60000);

	function AyudaLocal(sCampo) {
		let divAyuda = document.getElementById('div_ayuda_' + sCampo);
		if (typeof divAyuda === 'undefined') {} else {
			verboton('cmdAyuda_' + sCampo, 'none');
			let sMensaje = 'Lo que quiera decir.';
			//if (sCampo == 'sNombreCampo') {
			//sMensaje = 'Mensaje para otro campo.';
			//}
			divAyuda.innerHTML = sMensaje;
			divAyuda.style.display = 'block';
		}
	}
</script>
<?php
if ($_REQUEST['paso'] != 0) {
?>
<?php
}
?>
<form id="frmimpp" name="frmimpp" method="post" action="p2350.php" target="_blank" style="display:none">
<input id="r" name="r" type="hidden" value="2350" />
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
<input id="iformato94" name="iformato94" type="hidden" value="0" />
<input id="separa" name="separa" type="hidden" value="," />
<input id="rdebug" name="rdebug" type="hidden" value="<?php echo $_REQUEST['debug']; ?>" />
<input id="clave" name="clave" type="hidden" value="" />
</form>
<form id="frmlista" name="frmlista" method="post" action="listados.php" target="_blank" style="display:none">
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
<input id="icodmodulo" name="icodmodulo" type="hidden" value="<?php echo $iCodModulo; ?>" />
<input id="seg_5" name="seg_5" type="hidden" value="<?php echo $seg_5; ?>" />
<input id="seg_6" name="seg_6" type="hidden" value="<?php echo $seg_6; ?>" />
<input id="seg_1707" name="seg_1707" type="hidden" value="<?php echo $seg_1707; ?>" />
<div id="div_sector1">
<?php
if ($bBloqueTitulo) {
?>
<div class="titulos">
<div class="titulosD">
<input id="cmdAyuda" name="cmdAyuda" type="button" class="btUpAyuda" onclick="muestraayuda(<?php echo $APP->idsistema . ', ' . $iCodModulo; ?>);" title="<?php echo $ETI['bt_ayuda']; ?>" value="<?php echo $ETI['bt_ayuda']; ?>" />
<?php
if ($bConEliminar) {
?>
<input id="cmdEliminar" name="cmdEliminar" type="button" class="btUpEliminar" onclick="eliminadato();" title="<?php echo $ETI['bt_eliminar']; ?>" value="<?php echo $ETI['bt_eliminar']; ?>" />
<?php
}
if ($bHayImprimir) {
?>
<input id="cmdImprimir" name="cmdImprimir" type="button" class="<?php echo $sClaseImprime; ?>" onclick="<?php echo $sScriptImprime; ?>" title="<?php echo $ETI['bt_imprimir']; ?>" value="<?php echo $ETI['bt_imprimir']; ?>" />
<?php
}
if ($bHayImprimir2) {
?>
<input id="cmdImprimir2" name="cmdImprimir2" type="button" class="<?php echo $sClaseImprime2; ?>" onclick="<?php echo $sScriptImprime2; ?>" title="<?php echo $ETI['bt_imprimir']; ?>" value="<?php echo $ETI['bt_imprimir']; ?>" />
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
if (false) {
?>
<input id="cmdAnular" name="cmdAnular" type="button" class="btSupAnular" onclick="expandesector(2);" title="<?php echo $ETI['bt_anular']; ?>" value="<?php echo $ETI['bt_anular']; ?>" />
<?php
}
?>
</div>
<div class="titulosI">
<?php
echo '<h2>' . $sTituloModulo . '</h2>';
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
<?php
echo $ETI['msg_documento'];
?>
</label>
<label class="Label60">
<?php
echo $html_deb_tipodoc;
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
$bConExpande = true;
if ($bConExpande) {
?>
<div class="ir_derecha" <?php echo $sAnchoExpandeContrae; ?>>
<?php
echo $objForma->htmlExpande(2350, $_REQUEST['boculta2350'], $ETI['bt_mostrar'], $ETI['bt_ocultar']);
$sEstiloDiv = '';
if ($_REQUEST['boculta2350'] != 0) {
$sEstiloDiv = ' style="display:none;"';
}
?>
</div>
<div id="div_p2350" <?php echo $sEstiloDiv; ?>>
<?php
}
//Mostrar formulario para editar
?>
<label class="Label200">
<?php
echo $ETI['core50idperaca'];
?>
</label>
<label>
<div id="div_cara50idperaca" class="field">
<?php
echo $html_cara50idperaca;
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label200">
<?php
echo $ETI['msg_periodoacomp'];
?>
</label>
<label>
<div id="div_cara50periodoacomp" class="field">
<?php
echo $html_cara50periodoacomp;
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label200">
<?php
echo $ETI['core50idzona'];
?>
</label>
<label>
<div id="div_cara50idzona" class="field">
<?php
echo $html_cara50idzona;
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label200">
<?php
echo $ETI['core50idcentro'];
?>
</label>
<label>
<div id="div_cara50idcentro" class="field">
<?php
echo $html_cara50idcentro;
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label200">
<?php
echo $ETI['core50idescuela'];
?>
</label>
<label>
<div id="div_core50idescuela" class="field">
<?php
echo $html_core50idescuela;
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label200">
<?php
echo $ETI['core50idprograma'];
?>
</label>
<label>
<div id="div_core50idprograma" class="field">
<?php
echo $html_core50idprograma;
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label200">
<?php
echo $ETI['core50idtipo'];
?>
</label>
<label>
<div id="div_core50idtipo" class="field">
<?php
echo $html_core50idtipo;
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label200">
<?php
echo $ETI['cara50poblacion'];
?>
</label>
<label>
<div id="div_cara50poblacion" class="field">
<?php
echo $html_cara50poblacion;
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label200">
<?php
echo $ETI['cara50convenio'];
?>
</label>
<label>
<div id="div_cara50convenio" class="field">
<?php
echo $html_cara50convenio;
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label200">
<?php
echo $ETI['cara50periodomatricula'];
?>
</label>
<label>
<div id="div_cara50periodomatricula" class="field">
<?php
echo $html_cara50periodomatricula;
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label200">
<?php
echo $ETI['cara50tipomatricula'];
?>
</label>
<label>
<div id="div_cara50tipomatricula" class="field">
<?php
echo $html_cara50tipomatricula;
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label200">
<?php
echo $ETI['cara50listadoc'];
?>
</label>
<label>
<textarea id="cara50listadoc" name="cara50listadoc" placeholder="<?php echo $ETI['ing_campo'] . $ETI['cara50listadoc']; ?>"><?php echo $_REQUEST['cara50listadoc']; ?></textarea>
</label>
<div class="salto1px"></div>
<?php
echo $csv_separa;
if (false) {
//Ejemplo de boton de ayuda
//echo html_BotonAyuda('NombreCampo');
//echo html_DivAyudaLocal('NombreCampo');
}
if ($bConExpande) {
//Este es el cierre del div_p2350
?>
<div class="salto1px"></div>
</div>
<?php
}
// CIERRA EL DIV areatrabajo
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
<input id="titulo_2350" name="titulo_2350" type="hidden" value="<?php echo $sTituloModulo; ?>" />
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


<div id="div_sector97" style="display:none">
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
	$iSegundos = $iSegFin - $iSegIni;
	$sDebug = $sDebug . log_debug('Tiempo total del proceso: <b>' . $iSegundos . '</b> Segundos');
	echo console_debug($sDebug);
}
?>
<input id="scampobusca" name="scampobusca" type="hidden" value="" />
<input id="iscroll" name="iscroll" type="hidden" value="<?php echo $_REQUEST['iscroll']; ?>" />
<input id="itipoerror" name="itipoerror" type="hidden" value="<?php echo $iTipoError; ?>" />
<input id="debug" name="debug" type="hidden" value="<?php echo $_REQUEST['debug']; ?>" />
<input id="msg_requerido" name="msg_requerido" type="hidden" value="<?php echo $ERR['requeridos']; ?>" />
<input id="msg_pendiente" name="msg_pendiente" type="hidden" value="<?php echo $ETI['msg_pendiente']; ?>" />
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
<script language="javascript" src="<?php echo $APP->rutacomun; ?>unad_todas2024v2.js"></script>
<?php
forma_piedepagina();
