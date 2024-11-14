<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2024 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 3.0.11b miércoles, 14 de agosto de 2024
*/
/** Archivo saiuchat.php.
 * Modulo 3019 saiu19chat.
 * @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
 * @param debug = 1  (Opcional), bandera para indicar si se generan datos de depuración
 * @date miércoles, 14 de agosto de 2024
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
require $APP->rutacomun . 'libtiempo.php';
require $APP->rutacomun . 'libmail.php';
require $APP->rutacomun . 'libaurea.php';
if (($bPeticionXAJAX) && ($_SESSION['unad_id_tercero'] == 0)) {
	// viene por xajax.
	$xajax = new xajax();
	$xajax->configure('javascript URI', $APP->rutacomun . 'xajax/');
	$xajax->register(XAJAX_FUNCTION, 'sesion_abandona_V2');
	$xajax->processRequest();
	die();
}
$iConsecutivoMenu = 2;
$iMinVerDB = 7774;
$iCodModulo = 3019;
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
/*
$mensajes_000 = 'lg/lg_000_' . $_SESSION['unad_idioma'] . '.php';
if (!file_exists($mensajes_000)) {
	$mensajes_000 = 'lg/lg_000_es.php';
}
require $mensajes_000;
*/
$mensajes_3000=$APP->rutacomun.'lg/lg_3000_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_3000)) {
	$mensajes_3000=$APP->rutacomun.'lg/lg_3000_es.php';
}
$mensajes_3019 = 'lg/lg_3019_' . $_SESSION['unad_idioma'] . '.php';
if (!file_exists($mensajes_3019)) {
	$mensajes_3019 = 'lg/lg_3019_es.php';
}
require $mensajes_todas;
require $mensajes_3000;
require $mensajes_3019;
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
$sTituloModulo = $ETI['titulo_3019'];
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
		$objForma->addBoton('cmdAyuda98', 'btSupAyuda', 'muestraayuda('.$iCodModulo.');', $ETI['bt_ayuda']);
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
		header('Location:noticia.php?ret=saiuchat.php');
		die();
	}
}
$bOtroUsuario=false;
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
$idEntidad = Traer_Entidad();
// -- Si esta cargando la pagina por primer vez se revisa si requiere auditar y se manda a hacer un limpiar (paso -1)
if (isset($_REQUEST['paso']) == 0) {
	$_REQUEST['paso'] = -1;
	if ($audita[1]) {
		seg_auditaingreso($iCodModulo, $_SESSION['unad_id_tercero'], $objDB);
	}
}
// -- 3019 saiu19chat
require 'lib3019.php';
// -- 3000 Historial de solicitudes
require $APP->rutacomun.'lib3000.php';
$xajax = new xajax();
$xajax->configure('javascript URI', $APP->rutacomun . 'xajax/');
$xajax->register(XAJAX_FUNCTION,'unad11_Mostrar_v2');
$xajax->register(XAJAX_FUNCTION,'unad11_Mostrar_v2SAI');
$xajax->register(XAJAX_FUNCTION,'unad11_TraerXid');
$xajax->register(XAJAX_FUNCTION,'unad11_TraerXidSAI');
$xajax->register(XAJAX_FUNCTION,'f3019_Combosaiu19tiposolicitud');
$xajax->register(XAJAX_FUNCTION,'f3019_Combosaiu19temasolicitud');
$xajax->register(XAJAX_FUNCTION,'f3019_Combosaiu19idcentro');
$xajax->register(XAJAX_FUNCTION,'f3019_Combosaiu19coddepto');
$xajax->register(XAJAX_FUNCTION,'f3019_Combosaiu19codciudad');
$xajax->register(XAJAX_FUNCTION,'f3019_Combosaiu19idprograma');
$xajax->register(XAJAX_FUNCTION, 'sesion_abandona_V2');
$xajax->register(XAJAX_FUNCTION, 'sesion_mantenerV4');
$xajax->register(XAJAX_FUNCTION, 'f3019_HtmlTabla');
$xajax->register(XAJAX_FUNCTION, 'f3019_ExisteDato');
$xajax->register(XAJAX_FUNCTION,'f3019_Busquedas');
$xajax->register(XAJAX_FUNCTION,'f3019_HtmlBusqueda');
$xajax->register(XAJAX_FUNCTION,'f3000_HtmlTabla');
$xajax->register(XAJAX_FUNCTION,'f3000pqrs_HtmlTabla');
$xajax->register(XAJAX_FUNCTION,'elimina_archivo_saiu19idarchivo');
$xajax->register(XAJAX_FUNCTION,'elimina_archivo_saiu19idarchivorta');
$xajax->register(XAJAX_FUNCTION,'f3019_Combobtema');
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
if (isset($_REQUEST['iscroll'])==0){$_REQUEST['iscroll']=0;}
if (isset($_REQUEST['paginaf3019'])==0){$_REQUEST['paginaf3019']=1;}
if (isset($_REQUEST['lppf3019'])==0){$_REQUEST['lppf3019']=20;}
if (isset($_REQUEST['boculta3019'])==0){$_REQUEST['boculta3019']=0;}
if (isset($_REQUEST['paginaf3000'])==0){$_REQUEST['paginaf3000']=1;}
if (isset($_REQUEST['lppf3000'])==0){$_REQUEST['lppf3000']=10;}
if (isset($_REQUEST['paginaf3000pqrs'])==0){$_REQUEST['paginaf3000pqrs']=1;}
if (isset($_REQUEST['lppf3000pqrs'])==0){$_REQUEST['lppf3000pqrs']=10;}
if (isset($_REQUEST['boculta3000'])==0){$_REQUEST['boculta3000']=0;}
// -- Inicializar variables de datos.
if (isset($_REQUEST['saiu19agno'])==0){$_REQUEST['saiu19agno']='';}
if (isset($_REQUEST['saiu19mes'])==0){$_REQUEST['saiu19mes']='';}
if (isset($_REQUEST['saiu19tiporadicado'])==0){$_REQUEST['saiu19tiporadicado']=1;}
if (isset($_REQUEST['saiu19consec'])==0){$_REQUEST['saiu19consec']='';}
if (isset($_REQUEST['saiu19consec_nuevo'])==0){$_REQUEST['saiu19consec_nuevo']='';}
if (isset($_REQUEST['saiu19id'])==0){$_REQUEST['saiu19id']='';}
if (isset($_REQUEST['saiu19dia'])==0){$_REQUEST['saiu19dia']=fecha_dia();}
if (isset($_REQUEST['saiu19hora'])==0){$_REQUEST['saiu19hora']=fecha_hora();}
if (isset($_REQUEST['saiu19minuto'])==0){$_REQUEST['saiu19minuto']=fecha_minuto();}
if (isset($_REQUEST['saiu19estado'])==0){$_REQUEST['saiu19estado']=-1;}
if (isset($_REQUEST['saiu19estadoorigen'])==0){$_REQUEST['saiu19estadoorigen']=-1;}
if (isset($_REQUEST['saiu19idchat'])==0){$_REQUEST['saiu19idchat']='';}
if (isset($_REQUEST['saiu19idsolicitante'])==0){$_REQUEST['saiu19idsolicitante']=0;}// {$_SESSION['unad_id_tercero'];}
if (isset($_REQUEST['saiu19idsolicitante_td'])==0){$_REQUEST['saiu19idsolicitante_td']=$APP->tipo_doc;}
if (isset($_REQUEST['saiu19idsolicitante_doc'])==0){$_REQUEST['saiu19idsolicitante_doc']='';}
if (isset($_REQUEST['saiu19tipointeresado'])==0){$_REQUEST['saiu19tipointeresado']='';}
if (isset($_REQUEST['saiu19clasesolicitud'])==0){$_REQUEST['saiu19clasesolicitud']=0;}
if (isset($_REQUEST['saiu19tiposolicitud'])==0){$_REQUEST['saiu19tiposolicitud']=0;}
if (isset($_REQUEST['saiu19temasolicitud'])==0){$_REQUEST['saiu19temasolicitud']=0;}
if (isset($_REQUEST['saiu19temasolicitudorigen'])==0){$_REQUEST['saiu19temasolicitudorigen']='';}
if (isset($_REQUEST['saiu19idzona'])==0){$_REQUEST['saiu19idzona']='';}
if (isset($_REQUEST['saiu19idcentro'])==0){$_REQUEST['saiu19idcentro']='';}
if (isset($_REQUEST['saiu19codpais'])==0){$_REQUEST['saiu19codpais']=$_SESSION['unad_pais'];}
if (isset($_REQUEST['saiu19coddepto'])==0){$_REQUEST['saiu19coddepto']='';}
if (isset($_REQUEST['saiu19codciudad'])==0){$_REQUEST['saiu19codciudad']='';}
if (isset($_REQUEST['saiu19idescuela'])==0){$_REQUEST['saiu19idescuela']='';}
if (isset($_REQUEST['saiu19idprograma'])==0){$_REQUEST['saiu19idprograma']='';}
if (isset($_REQUEST['saiu19idperiodo'])==0){$_REQUEST['saiu19idperiodo']='';}
if (isset($_REQUEST['saiu19numorigen'])==0){$_REQUEST['saiu19numorigen']='';}
if (isset($_REQUEST['saiu19idpqrs'])==0){$_REQUEST['saiu19idpqrs']=0;}
if (isset($_REQUEST['saiu19detalle'])==0){$_REQUEST['saiu19detalle']='';}
if (isset($_REQUEST['saiu19fechafin'])==0){$_REQUEST['saiu19fechafin']='';}
if (isset($_REQUEST['saiu19horafin'])==0){$_REQUEST['saiu19horafin']='';}
if (isset($_REQUEST['saiu19minutofin'])==0){$_REQUEST['saiu19minutofin']='';}
if (isset($_REQUEST['saiu19paramercadeo'])==0){$_REQUEST['saiu19paramercadeo']=0;}
if (isset($_REQUEST['saiu19idresponsable'])==0){$_REQUEST['saiu19idresponsable']=0;}// {$_SESSION['unad_id_tercero'];}
if (isset($_REQUEST['saiu19idresponsable_td'])==0){$_REQUEST['saiu19idresponsable_td']=$APP->tipo_doc;}
if (isset($_REQUEST['saiu19idresponsable_doc'])==0){$_REQUEST['saiu19idresponsable_doc']='';}
if (isset($_REQUEST['saiu19tiemprespdias'])==0){$_REQUEST['saiu19tiemprespdias']='';}
if (isset($_REQUEST['saiu19tiempresphoras'])==0){$_REQUEST['saiu19tiempresphoras']='';}
if (isset($_REQUEST['saiu19tiemprespminutos'])==0){$_REQUEST['saiu19tiemprespminutos']='';}
if (isset($_REQUEST['saiu19solucion'])==0){$_REQUEST['saiu19solucion']=0;}
if (isset($_REQUEST['saiu19idcaso'])==0){$_REQUEST['saiu19idcaso']=0;}
if (isset($_REQUEST['saiu19numsesionchat'])==0){$_REQUEST['saiu19numsesionchat']='';}
if (isset($_REQUEST['saiu19respuesta'])==0){$_REQUEST['saiu19respuesta']='';}
if (isset($_REQUEST['saiu19idorigen'])==0){$_REQUEST['saiu19idorigen']=0;}
if (isset($_REQUEST['saiu19idarchivo'])==0){$_REQUEST['saiu19idarchivo']=0;}
if (isset($_REQUEST['saiu19idorigenrta'])==0){$_REQUEST['saiu19idorigenrta']=0;}
if (isset($_REQUEST['saiu19idarchivorta'])==0){$_REQUEST['saiu19idarchivorta']=0;}
if (isset($_REQUEST['saiu19fecharespcaso'])==0){$_REQUEST['saiu19fecharespcaso']='';}
if (isset($_REQUEST['saiu19horarespcaso'])==0){$_REQUEST['saiu19horarespcaso']=0;}
if (isset($_REQUEST['saiu19minrespcaso'])==0){$_REQUEST['saiu19minrespcaso']=0;}
if (isset($_REQUEST['saiu19idunidadcaso'])==0){$_REQUEST['saiu19idunidadcaso']=0;}
if (isset($_REQUEST['saiu19idequipocaso'])==0){$_REQUEST['saiu19idequipocaso']=0;}
if (isset($_REQUEST['saiu19idsupervisorcaso'])==0){$_REQUEST['saiu19idsupervisorcaso']=0;}
if (isset($_REQUEST['saiu19idresponsablecaso'])==0){$_REQUEST['saiu19idresponsablecaso']=0;}
if (isset($_REQUEST['saiu19numref'])==0){$_REQUEST['saiu19numref']='';}
if (isset($_REQUEST['saiu19evalacepta'])==0){$_REQUEST['saiu19evalacepta']=0;}
if (isset($_REQUEST['saiu19evalfecha'])==0){$_REQUEST['saiu19evalfecha']='';}
if (isset($_REQUEST['saiu19evalamabilidad'])==0){$_REQUEST['saiu19evalamabilidad']=0;}
if (isset($_REQUEST['saiu19evalamabmotivo'])==0){$_REQUEST['saiu19evalamabmotivo']='';}
if (isset($_REQUEST['saiu19evalrapidez'])==0){$_REQUEST['saiu19evalrapidez']=0;}
if (isset($_REQUEST['saiu19evalrapidmotivo'])==0){$_REQUEST['saiu19evalrapidmotivo']='';}
if (isset($_REQUEST['saiu19evalclaridad'])==0){$_REQUEST['saiu19evalclaridad']=0;}
if (isset($_REQUEST['saiu19evalcalridmotivo'])==0){$_REQUEST['saiu19evalcalridmotivo']='';}
if (isset($_REQUEST['saiu19evalresolvio'])==0){$_REQUEST['saiu19evalresolvio']=0;}
if (isset($_REQUEST['saiu19evalsugerencias'])==0){$_REQUEST['saiu19evalsugerencias']='';}
if (isset($_REQUEST['saiu19evalconocimiento'])==0){$_REQUEST['saiu19evalconocimiento']=0;}
if (isset($_REQUEST['saiu19evalconocmotivo'])==0){$_REQUEST['saiu19evalconocmotivo']='';}
if (isset($_REQUEST['saiu19evalutilidad'])==0){$_REQUEST['saiu19evalutilidad']=0;}
if (isset($_REQUEST['saiu19evalutilmotivo'])==0){$_REQUEST['saiu19evalutilmotivo']='';}
if (isset($_REQUEST['saiu19idresponsablecaso_td'])==0){$_REQUEST['saiu19idresponsablecaso_td']=$APP->tipo_doc;}
if (isset($_REQUEST['saiu19idresponsablecaso_doc'])==0){$_REQUEST['saiu19idresponsablecaso_doc']='';}
// Espacio para inicializar otras variables
if (isset($_REQUEST['csv_separa'])==0){$_REQUEST['csv_separa']=',';}
if (isset($_REQUEST['bnombre'])==0){$_REQUEST['bnombre']='';}
if (isset($_REQUEST['bagno'])==0){$_REQUEST['bagno']=fecha_agno();}
if (isset($_REQUEST['bestado'])==0){$_REQUEST['bestado']=1;}
if (isset($_REQUEST['blistar'])==0){$_REQUEST['blistar']=2;}
if (isset($_REQUEST['bdoc'])==0){$_REQUEST['bdoc']='';}
if (isset($_REQUEST['bcategoria'])==0){$_REQUEST['bcategoria']='';}
if (isset($_REQUEST['btema'])==0){$_REQUEST['btema']='';}
if (isset($_REQUEST['bagnopqrs'])==0){$_REQUEST['bagnopqrs']=fecha_agno();}
if (isset($_REQUEST['vdtipointeresado'])==0){
	$sVr='';
	$sSQL='SELECT bita07id FROM bita07tiposolicitante WHERE bita07predet="S" ORDER BY bita07orden, bita07nombre';
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$sVr=$fila['bita07id'];
		}
	$_REQUEST['vdtipointeresado']=$sVr;
	}
if (isset($_REQUEST['vdidchat'])==0){
	$sVr='';
	$sSQL='SELECT saiu27id FROM saiu27chats WHERE saiu27predet=1 ORDER BY saiu27orden, saiu27consec';
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$sVr=$fila['saiu27id'];
		}
	$_REQUEST['vdidchat']=$sVr;
	}
if (isset($_REQUEST['saiucanal'])==0){$_REQUEST['saiucanal']=3;}
//Si Modifica o Elimina Cargar los campos
if (($_REQUEST['paso']==1)||($_REQUEST['paso']==3)){
	$_REQUEST['saiu19idsolicitante_td']=$APP->tipo_doc;
	$_REQUEST['saiu19idsolicitante_doc']='';
	$_REQUEST['saiu19idresponsable_td']=$APP->tipo_doc;
	$_REQUEST['saiu19idresponsable_doc']='';
	$_REQUEST['saiu19idresponsablecaso_td']=$APP->tipo_doc;
	$_REQUEST['saiu19idresponsablecaso_doc']='';
	$sTabla = 'saiu19chat_'.$_REQUEST['saiu19agno'];
	if ($objDB->bexistetabla($sTabla)) {
		list($sErrorR, $sDebugR) = f3019_RevTabla_saiu19chat($_REQUEST['saiu19agno'], $objDB, $bDebug);
		$sError = $sError . $sErrorR;
		$sDebug = $sDebug . $sDebugR;
		if ($_REQUEST['paso']==1) {
			$sSQLcondi='saiu19agno='.$_REQUEST['saiu19agno'].' AND saiu19mes='.$_REQUEST['saiu19mes'].' AND saiu19tiporadicado='.$_REQUEST['saiu19tiporadicado'].' AND saiu19consec='.$_REQUEST['saiu19consec'].'';
		} else {
			$sSQLcondi='saiu19id='.$_REQUEST['saiu19id'].'';
		}
		$sSQL='SELECT * FROM ' . $sTabla . ' WHERE '.$sSQLcondi;
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta registro: '.$sSQL.'<br>';}
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0) {
			$fila=$objDB->sf($tabla);
			$_REQUEST['saiu19agno']=$fila['saiu19agno'];
			$_REQUEST['saiu19mes']=$fila['saiu19mes'];
			$_REQUEST['saiu19tiporadicado']=$fila['saiu19tiporadicado'];
			$_REQUEST['saiu19consec']=$fila['saiu19consec'];
			$_REQUEST['saiu19id']=$fila['saiu19id'];
			$_REQUEST['saiu19dia']=$fila['saiu19dia'];
			$_REQUEST['saiu19hora']=$fila['saiu19hora'];
			$_REQUEST['saiu19minuto']=$fila['saiu19minuto'];
			$_REQUEST['saiu19estado']=$fila['saiu19estado'];
			$_REQUEST['saiu19estadoorigen']=$fila['saiu19estado'];
			$_REQUEST['saiu19idchat']=$fila['saiu19idchat'];
			$_REQUEST['saiu19idsolicitante']=$fila['saiu19idsolicitante'];
			$_REQUEST['saiu19tipointeresado']=$fila['saiu19tipointeresado'];
			$_REQUEST['saiu19clasesolicitud']=$fila['saiu19clasesolicitud'];
			$_REQUEST['saiu19tiposolicitud']=$fila['saiu19tiposolicitud'];
			$_REQUEST['saiu19temasolicitud']=$fila['saiu19temasolicitud'];
			$_REQUEST['saiu19temasolicitudorigen']=$fila['saiu19temasolicitud'];
			$_REQUEST['saiu19idzona']=$fila['saiu19idzona'];
			$_REQUEST['saiu19idcentro']=$fila['saiu19idcentro'];
			$_REQUEST['saiu19codpais']=$fila['saiu19codpais'];
			$_REQUEST['saiu19coddepto']=$fila['saiu19coddepto'];
			$_REQUEST['saiu19codciudad']=$fila['saiu19codciudad'];
			$_REQUEST['saiu19idescuela']=$fila['saiu19idescuela'];
			$_REQUEST['saiu19idprograma']=$fila['saiu19idprograma'];
			$_REQUEST['saiu19idperiodo']=$fila['saiu19idperiodo'];
			$_REQUEST['saiu19numorigen']=$fila['saiu19numorigen'];
			$_REQUEST['saiu19idpqrs']=$fila['saiu19idpqrs'];
			$_REQUEST['saiu19detalle']=$fila['saiu19detalle'];
			$_REQUEST['saiu19fechafin']=$fila['saiu19fechafin'];
			$_REQUEST['saiu19horafin']=$fila['saiu19horafin'];
			$_REQUEST['saiu19minutofin']=$fila['saiu19minutofin'];
			$_REQUEST['saiu19paramercadeo']=$fila['saiu19paramercadeo'];
			$_REQUEST['saiu19idresponsable']=$fila['saiu19idresponsable'];
			$_REQUEST['saiu19tiemprespdias']=$fila['saiu19tiemprespdias'];
			$_REQUEST['saiu19tiempresphoras']=$fila['saiu19tiempresphoras'];
			$_REQUEST['saiu19tiemprespminutos']=$fila['saiu19tiemprespminutos'];
			$_REQUEST['saiu19solucion']=$fila['saiu19solucion'];
			$_REQUEST['saiu19idcaso']=$fila['saiu19idcaso'];
			$_REQUEST['saiu19numsesionchat']=$fila['saiu19numsesionchat'];
			$_REQUEST['saiu19respuesta']=$fila['saiu19respuesta'];
			if ($sError=='') {
				$_REQUEST['saiu19idorigen'] = $fila['saiu19idorigen'];
				$_REQUEST['saiu19idarchivo'] = $fila['saiu19idarchivo'];
				$_REQUEST['saiu19idorigenrta'] = $fila['saiu19idorigenrta'];
				$_REQUEST['saiu19idarchivorta'] = $fila['saiu19idarchivorta'];
				$_REQUEST['saiu19fecharespcaso'] = $fila['saiu19fecharespcaso'];
				$_REQUEST['saiu19horarespcaso'] = $fila['saiu19horarespcaso'];
				$_REQUEST['saiu19minrespcaso'] = $fila['saiu19minrespcaso'];
				$_REQUEST['saiu19idunidadcaso'] = $fila['saiu19idunidadcaso'];
				$_REQUEST['saiu19idequipocaso'] = $fila['saiu19idequipocaso'];
				$_REQUEST['saiu19idsupervisorcaso'] = $fila['saiu19idsupervisorcaso'];
				$_REQUEST['saiu19idresponsablecaso'] = $fila['saiu19idresponsablecaso'];
				$_REQUEST['saiu19numref'] = $fila['saiu19numref'];
				$_REQUEST['saiu19evalacepta'] = $fila['saiu19evalacepta'];
				$_REQUEST['saiu19evalfecha'] = $fila['saiu19evalfecha'];
				$_REQUEST['saiu19evalamabilidad'] = $fila['saiu19evalamabilidad'];
				$_REQUEST['saiu19evalamabmotivo'] = $fila['saiu19evalamabmotivo'];
				$_REQUEST['saiu19evalrapidez'] = $fila['saiu19evalrapidez'];
				$_REQUEST['saiu19evalrapidmotivo'] = $fila['saiu19evalrapidmotivo'];
				$_REQUEST['saiu19evalclaridad'] = $fila['saiu19evalclaridad'];
				$_REQUEST['saiu19evalcalridmotivo'] = $fila['saiu19evalcalridmotivo'];
				$_REQUEST['saiu19evalresolvio'] = $fila['saiu19evalresolvio'];
				$_REQUEST['saiu19evalsugerencias'] = $fila['saiu19evalsugerencias'];
				$_REQUEST['saiu19evalconocimiento'] = $fila['saiu19evalconocimiento'];
				$_REQUEST['saiu19evalconocmotivo'] = $fila['saiu19evalconocmotivo'];
				$_REQUEST['saiu19evalutilidad'] = $fila['saiu19evalutilidad'];
				$_REQUEST['saiu19evalutilmotivo'] = $fila['saiu19evalutilmotivo'];
			}
			$bcargo=true;
			$_REQUEST['paso']=2;
			$_REQUEST['boculta3019']=0;
			$bLimpiaHijos=true;
		} else {
			$_REQUEST['paso']=0;
		}
	} else {
		$sError = 'No ha sido posible encontrar el contenedor para ' . $sTabla . '';
		$_REQUEST['paso'] = -1;
	}
}
//Cerrar
$bCerrando=false;
if ($_REQUEST['paso']==16){
	$_REQUEST['paso']=12;
	$_REQUEST['saiu19estado']=7;
	$bCerrando=true;
	}
if ($_REQUEST['paso']==21){
	$_REQUEST['paso']=12;
	$_REQUEST['saiu19estado']=8;
	$bCerrando=true;
	}
if ($_REQUEST['paso']==22){
	$_REQUEST['paso']=12;
	$_REQUEST['saiu19estado']=9;
	$bCerrando=true;
	}
//Abrir
if ($_REQUEST['paso']==17){
	$_REQUEST['paso']=2;
	//Es posible que deba definir el codigo de permiso para abrir.
	if (!seg_revisa_permiso($iCodModulo, 17, $objDB)){
		$sError=$ERR['3'];
		}
	//Otras restricciones para abrir.
	if ($sError==''){
		//$sError='Motivo por el que no se pueda abrir, no se permite modificar.';
		}
	if ($sError!=''){
		$_REQUEST['saiu19estado']=7;
		}else{
		$saiu19estado=2;
		if ($_REQUEST['saiu19idcaso']!=0){$saiu19estado=1;}
		$sSQL='UPDATE saiu19chat_'.$_REQUEST['saiu19agno'].' SET saiu19estado='.$saiu19estado.' WHERE saiu19id='.$_REQUEST['saiu19id'].'';
		$tabla=$objDB->ejecutasql($sSQL);
		seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 3, $_REQUEST['saiu19id'], 'Abre Registro de conversacion chat', $objDB);
		$_REQUEST['saiu19estado']=$saiu19estado;
		$sError='<b>El registro ha sido abierto</b>';
		$iTipoError=1;
		}
	}
//Insertar o modificar un elemento
if (($_REQUEST['paso']==10)||($_REQUEST['paso']==12)){
	$bMueveScroll=true;
	list($_REQUEST, $sError, $iTipoError, $bCerrando, $sErrorCerrando, $sDebugGuardar)=f3019_db_GuardarV2($_REQUEST, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugGuardar;
	if ($sError==''){
		$sError='<b>'.$ETI['msg_itemguardado'].'</b>';
		$iTipoError=1;
		if ($sErrorCerrando!=''){
			$iTipoError=0;
			$sError='<b>'.$ETI['msg_itemguardado'].'</b><br>'.$sErrorCerrando;
			}
		if ($bCerrando){
			$sError='<b>'.$ETI['msg_itemcerrado'].'</b>';
			}
		} else {
			if ($_REQUEST['paso']==0) {
				if ($_REQUEST['saiu19estado']!=1) {
					$_REQUEST['saiu19estado']=-1;
				}
			}
		}
	}
if ($bCerrando){
	//acciones del cerrado
	}
// Cambio de consecutivo.
if ($_REQUEST['paso']==93){
	$_REQUEST['paso']=2;
	$_REQUEST['saiu19consec_nuevo']=numeros_validar($_REQUEST['saiu19consec_nuevo']);
	if ($_REQUEST['saiu19consec_nuevo']==''){$sError=$ERR['saiu19consec'];}
	if ($sError==''){
		if (!seg_revisa_permiso($iCodModulo, 8, $objDB)){
			$sError=$ERR['8'];
			}
		}
	if ($sError==''){
		//Ver que el consecutivo no exista.
		$sSQL='SELECT saiu19id FROM saiu19chat_'.$_REQUEST['saiu19agno'].' WHERE saiu19consec='.$_REQUEST['saiu19consec_nuevo'].' AND saiu19tiporadicado='.$_REQUEST['saiu19tiporadicado'].' AND saiu19mes='.$_REQUEST['saiu19mes'].' AND saiu19agno='.$_REQUEST['saiu19agno'].'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$sError='El consecutivo '.$_REQUEST['saiu19consec_nuevo'].' ya existe';
			}
		}
	if ($sError==''){
		//Aplicar el cambio.
		$sSQL='UPDATE saiu19chat_'.$_REQUEST['saiu19agno'].' SET saiu19consec='.$_REQUEST['saiu19consec_nuevo'].' WHERE saiu19id='.$_REQUEST['saiu19id'].'';
		$tabla=$objDB->ejecutasql($sSQL);
		$sDetalle='Cambia el consecutivo de '.$_REQUEST['saiu19consec'].' a '.$_REQUEST['saiu19consec_nuevo'].'';
		$_REQUEST['saiu19consec']=$_REQUEST['saiu19consec_nuevo'];
		$_REQUEST['saiu19consec_nuevo']='';
		seg_auditar($iCodModulo, $_SESSION['u_idtercero'], 8, $_REQUEST['saiu19id'], $sDetalle, $objDB);
		$sError='<b>Se ha aplicado el cambio de consecutivo.</b>';
		$iTipoError=1;
		}else{
		$iSector=93;
		}
	}
//Eliminar un elemento
if ($_REQUEST['paso'] == 13) {
	$_REQUEST['paso'] = 2;
	if ($sError == '') {
		list($sError, $iTipoError, $sDebugElimina)=f3019_db_Eliminar($_REQUEST['saiu19agno'], $_REQUEST['saiu19id'], $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugElimina;
	}
	if ($sError == '') {
		$_REQUEST['paso'] = -1;
		$sError = $ETI['msg_itemeliminado'];
		$iTipoError = 1;
	}
}
// Reasignar responsable.
if ($_REQUEST['paso'] == 26) {
	$_REQUEST['paso'] = 2;
	$bMueveScroll = true;
	$bPermisoSupv = $idTercero == $_REQUEST['saiu19idsupervisorcaso'];
	if ($bPermisoSupv || $seg_1707) {
		$sTabla19 = 'saiu19chat_' . $_REQUEST['saiu19agno'];
		if (!$objDB->bexistetabla($sTabla19)) {
			$sError = 'No ha sido posible acceder al contenedor de datos ' . $sTabla19 . '';
		}
		if ($sError == '') {
			$bCambiaLider = false;
			$saiu19idunidadcaso = $_REQUEST['saiu19idunidadcaso'];
			$saiu19idequipocaso = $_REQUEST['saiu19idequipocaso'];
			$saiu19idsupervisorcaso = $_REQUEST['saiu19idsupervisorcaso'];
			$sSQL = 'SELECT bita27id, bita27idlider, bita27idunidadfunc FROM bita27equipotrabajo WHERE bita27idlider=' . $_REQUEST['saiu19idresponsablecasofin'] . ' AND bita27activo=1 ';
			$tabla = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla) > 0) {
				$fila = $objDB->sf($tabla);
				$sSQL = 'UPDATE ' . $sTabla19 . ' SET saiu19idunidadcaso=' . $fila['bita27idunidadfunc'] . ', saiu19idequipocaso=' . $fila['bita27id'] . ', 
saiu19idsupervisorcaso=' . $fila['bita27idlider'] . ', saiu19idresponsablecaso=' . $_REQUEST['saiu19idresponsablecasofin'] . ' WHERE saiu19id=' . $_REQUEST['saiu19id'] . '';
				$bCambiaLider = true;
				$saiu05idunidadresp = $fila['bita27idunidadfunc'];
				$saiu19idequipocaso = $fila['bita27id'];
				$saiu19idsupervisorcaso = $fila['bita27idlider'];
			} else {
				$sSQL = 'UPDATE ' . $sTabla19 . ' SET saiu19idresponsablecaso=' . $_REQUEST['saiu19idresponsablecasofin'] . ' WHERE saiu19id=' . $_REQUEST['saiu19id'] . '';
			}
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . ' Consulta reasignación: '.$sSQL.'<br>';
			}
			$result = $objDB->ejecutasql($sSQL);
			if ($result==false){
				$sError=$sError.$ERR['saiu19idresponsablecasofin'].'';
			} else {
				seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 3, $_REQUEST['saiu19id'], 'Reasigna el responsable ', $objDB);
				if ($bCambiaLider) {
					$_REQUEST['saiu19idunidadcaso']=$saiu19idunidadcaso;
					$_REQUEST['saiu19idequipocaso']=$saiu19idequipocaso;
					$_REQUEST['saiu19idsupervisorcaso']=$saiu19idsupervisorcaso;
				}
				$_REQUEST['saiu19idresponsablecaso']=$_REQUEST['saiu19idresponsablecasofin'];
				$sError = '<b>Se ha realizado la reasignaci&oacute;n.</b>';
				$iTipoError = 1;
				$_REQUEST['saiuid']=19;
				list($sMensaje, $sErrorE, $sDebugE) = f3000_EnviaCorreosAtencion($_REQUEST, $_REQUEST['saiu19agno'], $objDB, $bDebug, true);
				$sError = $sError . $sErrorE;
				$sDebug = $sDebug . $sDebugE;
			}
		}
	} else {
		$sError=$sError.$ERR['3'].'';
	}
}
// Actualiza atiende
if ($_REQUEST['paso'] == 27) {
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' Consultando informaci&oacute;n de responsables.<br>';
	}
	if ($_REQUEST['saiu19estado'] < 7){
		list($_REQUEST, $sErrorE, $iTipoError, $sDebugGuardar) = f3019_ActualizarAtiende($_REQUEST, $objDB, $bDebug, $idTercero);
		$sError = $sError . $sErrorE;
		$sDebug = $sDebug . $sDebugGuardar;
	} else {
		$sError = $sError . $ETI['saiu19cerrada'];
	}
}
//limpiar la pantalla
if ($_REQUEST['paso'] == -1) {
	$_REQUEST['saiu19agno']=fecha_agno();
	$_REQUEST['saiu19mes']=fecha_mes();
	//$_REQUEST['saiu19tiporadicado']='';
	$_REQUEST['saiu19consec']='';
	$_REQUEST['saiu19consec_nuevo']='';
	$_REQUEST['saiu19id']='';
	$_REQUEST['saiu19dia']=fecha_dia();
	$_REQUEST['saiu19hora']=fecha_hora();
	$_REQUEST['saiu19minuto']=fecha_minuto();
	$_REQUEST['saiu19estado']=-1;
	$_REQUEST['saiu19estadoorigen']=-1;
	if ($_REQUEST['saiu19idchat']==''){
		$_REQUEST['saiu19idchat']=$_REQUEST['vdidchat'];
	}
	$_REQUEST['saiu19idsolicitante']=0;//$idTercero;
	$_REQUEST['saiu19idsolicitante_td']=$APP->tipo_doc;
	$_REQUEST['saiu19idsolicitante_doc']='';
	$_REQUEST['saiu19tipointeresado']=$_REQUEST['vdtipointeresado'];
	$_REQUEST['saiu19clasesolicitud']=0;
	$_REQUEST['saiu19tiposolicitud']=0;
	$_REQUEST['saiu19temasolicitud']=0;
	$_REQUEST['saiu19temasolicitudorigen']='';
	$_REQUEST['saiu19idzona']='';
	$_REQUEST['saiu19idcentro']='';
	$_REQUEST['saiu19codpais']=$_SESSION['unad_pais'];
	$_REQUEST['saiu19coddepto']='';
	$_REQUEST['saiu19codciudad']='';
	$_REQUEST['saiu19idescuela']=0;
	$_REQUEST['saiu19idprograma']=0;
	$_REQUEST['saiu19idperiodo']=0;
	$_REQUEST['saiu19numorigen']='';
	$_REQUEST['saiu19idpqrs']=0;
	$_REQUEST['saiu19detalle']='';
	$_REQUEST['saiu19fechafin']='';
	$_REQUEST['saiu19horafin']='';
	$_REQUEST['saiu19minutofin']='';
	$_REQUEST['saiu19paramercadeo']=0;
	$_REQUEST['saiu19idresponsable']=$idTercero;
	$_REQUEST['saiu19idresponsable_td']=$APP->tipo_doc;
	$_REQUEST['saiu19idresponsable_doc']='';
	$_REQUEST['saiu19tiemprespdias']='';
	$_REQUEST['saiu19tiempresphoras']='';
	$_REQUEST['saiu19tiemprespminutos']='';
	$_REQUEST['saiu19solucion']=0;
	$_REQUEST['saiu19idcaso']=0;
	$_REQUEST['saiu19numsesionchat']='';
	$_REQUEST['saiu19respuesta']='';
	$_REQUEST['saiu19idorigen']=0;
	$_REQUEST['saiu19idarchivo']=0;
	$_REQUEST['saiu19idorigenrta']=0;
	$_REQUEST['saiu19idarchivorta']=0;
	$_REQUEST['saiu19fecharespcaso']='';
	$_REQUEST['saiu19horarespcaso']=0;
	$_REQUEST['saiu19minrespcaso']=0;
	$_REQUEST['saiu19idunidadcaso']=0;
	$_REQUEST['saiu19idequipocaso']=0;
	$_REQUEST['saiu19idsupervisorcaso']=0;
	$_REQUEST['saiu19idresponsablecaso']=0;
	$_REQUEST['saiu19numref']='';
	$_REQUEST['saiu19evalacepta']=0;
	$_REQUEST['saiu19evalfecha']='';
	$_REQUEST['saiu19evalamabilidad']=0;
	$_REQUEST['saiu19evalamabmotivo']='';
	$_REQUEST['saiu19evalrapidez']=0;
	$_REQUEST['saiu19evalrapidmotivo']='';
	$_REQUEST['saiu19evalclaridad']=0;
	$_REQUEST['saiu19evalcalridmotivo']='';
	$_REQUEST['saiu19evalresolvio']=0;
	$_REQUEST['saiu19evalsugerencias']='';
	$_REQUEST['saiu19evalconocimiento']=0;
	$_REQUEST['saiu19evalconocmotivo']='';
	$_REQUEST['saiu19evalutilidad']=0;
	$_REQUEST['saiu19evalutilmotivo']='';
	$_REQUEST['saiu19idresponsablecaso_td']=$APP->tipo_doc;
	$_REQUEST['saiu19idresponsablecaso_doc']='';
	$_REQUEST['paso'] = 0;
}
if ($bLimpiaHijos) {
}
//AQUI SE DEBEN CARGAR TODOS LOS DATOS QUE LA FORMA NECESITE.
$bPuedeAbrir = false;
$bPuedeEliminar = false;
$bPuedeGuardar = false;
$bPuedeCerrar = false;
$bHayImprimir = false;
$sScriptImprime = 'imprimeexcel()';
$sClaseImprime = 'iExcel';
$bEditable = $_REQUEST['saiu19estado'] == -1 || $_REQUEST['saiu19estado'] == 2;
$bPermisoSupv = $idTercero == $_REQUEST['saiu19idsupervisorcaso'];
$bPermisoResp = $idTercero == $_REQUEST['saiu19idresponsablecaso'];
$bPermisoAsignado = $bPermisoSupv || $bPermisoResp;
//DATOS PARA COMPLETAR EL FORMULARIO
$iAgno=fecha_agno();
$sNombreUsuario = '';
if ($seg_1707 == 1) {
	$sSQL = 'SELECT unad11razonsocial FROM unad11terceros WHERE unad11id=' . $idTercero . '';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$sNombreUsuario = cadena_notildes($fila['unad11razonsocial']);
	}
}
$sTabla='saiu19chat_'.$iAgno;
if (!$objDB->bexistetabla($sTabla)){
	list($sErrorT, $sDebugT)=f3000_TablasMes($iAgno, fecha_mes(), $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugT;
}
//Permisos adicionales
$seg_5=0;
$seg_6=0;
$seg_8=0;
$seg_12 = 0;
list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 12, $idTercero, $objDB);
if ($bDevuelve) {
	$seg_12 = 1;
}
list($bDevuelve, $sDebugP)=seg_revisa_permisoV3($iCodModulo, 6, $idTercero, $objDB);
if ($bDevuelve){$seg_6=1;}
if ($seg_6==1){$bHayImprimir=true;}
if ((int)$_REQUEST['paso'] == 0) {
	$bPuedeGuardar = true;
} else {
	switch ($_REQUEST['saiu19estado']) {
		case 1: // Caso asignado
			if ($bPermisoAsignado) {
				$bPuedeGuardar = true;
				$bPuedeCerrar = true;
			}
			break;
		case 2: // En tramite
			$bPuedeEliminar = true;
			$bPuedeGuardar = true;
			$bPuedeCerrar = true;
			break;
		case 7: // Radicada
			break;
		default:
			break;

	}
}
//DATOS PARA COMPLETAR EL FORMULARIO
$sNombreUsuario = '';
//Crear los controles que requieran llamado a base de datos
$objCombos = new clsHtmlCombos();
$objForma = new clsHtmlForma($iPiel);
$objTercero=new clsHtmlTercero();
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
$iAgnoFin = fecha_agno();
list($saiu19estado_nombre, $sErrorDet)=tabla_campoxid('saiu11estadosol','saiu11nombre','saiu11id',$_REQUEST['saiu19estado'],'{'.$ETI['msg_sindato'].'}', $objDB);
$html_saiu19estado=html_oculto('saiu19estado', $_REQUEST['saiu19estado'], $saiu19estado_nombre);
$objCombos->nuevo('saiu19idchat', $_REQUEST['saiu19idchat'], true, '{'.$ETI['msg_seleccione'].'}');
$sSQL='SELECT saiu27id AS id, saiu27nombre AS nombre FROM saiu27chats ORDER BY saiu27nombre';
$html_saiu19idchat=$objCombos->html($sSQL, $objDB);
list($saiu19idsolicitante_rs, $_REQUEST['saiu19idsolicitante'], $_REQUEST['saiu19idsolicitante_td'], $_REQUEST['saiu19idsolicitante_doc'])=html_tercero($_REQUEST['saiu19idsolicitante_td'], $_REQUEST['saiu19idsolicitante_doc'], $_REQUEST['saiu19idsolicitante'], 0, $objDB);
list($saiu19idresponsable_rs, $_REQUEST['saiu19idresponsable'], $_REQUEST['saiu19idresponsable_td'], $_REQUEST['saiu19idresponsable_doc'])=html_tercero($_REQUEST['saiu19idresponsable_td'], $_REQUEST['saiu19idresponsable_doc'], $_REQUEST['saiu19idresponsable'], 0, $objDB);
$saiu19idunidadcaso_nombre = '&nbsp;';
if ($_REQUEST['saiu19idunidadcaso'] != '') {
	if ((int)$_REQUEST['saiu19idunidadcaso'] == 0) {
		$saiu19idunidadcaso_nombre = '{' . $ETI['msg_sindato'] . '}';
	} else {
		list($saiu19idunidadcaso_nombre, $sErrorDet) = tabla_campoxid('unae26unidadesfun', 'unae26nombre', 'unae26id', $_REQUEST['saiu19idunidadcaso'], '{' . $ETI['msg_sindato'] . '}', $objDB);
	}
}
$html_saiu19idunidadcaso = html_oculto('saiu19idunidadcaso', $_REQUEST['saiu19idunidadcaso'], $saiu19idunidadcaso_nombre);
$saiu19idequipocaso_nombre = '&nbsp;';
if ($_REQUEST['saiu19idequipocaso'] != '') {
	if ((int)$_REQUEST['saiu19idequipocaso'] == 0) {
		$saiu19idequipocaso_nombre = '{' . $ETI['msg_sindato'] . '}';
	} else {
		list($saiu19idequipocaso_nombre, $sErrorDet) = tabla_campoxid('bita27equipotrabajo', 'bita27nombre', 'bita27id', $_REQUEST['saiu19idequipocaso'], '{' . $ETI['msg_sindato'] . '}', $objDB);
	}
}
$html_saiu19idequipocaso = html_oculto('saiu19idequipocaso', $_REQUEST['saiu19idequipocaso'], $saiu19idequipocaso_nombre);
$saiu19idsupervisorcaso_rs='&nbsp;';
$sSQL = 'SELECT T11.unad11razonsocial FROM saiu03temasol AS TB, unad11terceros AS T11 WHERE TB.saiu03idliderrespon1=T11.unad11id AND TB.saiu03id = ' . $_REQUEST['saiu19temasolicitud'] . ' AND TB.saiu03idliderrespon1 = ' . $_REQUEST['saiu19idsupervisorcaso'] . '';
$tabla = $objDB->ejecutasql($sSQL);
if ($fila = $objDB->sf($tabla)) {
	$saiu19idsupervisorcaso_rs=$fila['unad11razonsocial'];
} else {
	$saiu19idsupervisorcaso_rs = '{' . $ETI['msg_sindato'] . '}';
}
list($saiu19idresponsablecaso_rs, $_REQUEST['saiu19idresponsablecaso'], $_REQUEST['saiu19idresponsablecaso_td'], $_REQUEST['saiu19idresponsablecaso_doc']) = html_tercero($_REQUEST['saiu19idresponsablecaso_td'], $_REQUEST['saiu19idresponsablecaso_doc'], $_REQUEST['saiu19idresponsablecaso'], 0, $objDB);
if ($saiu19idresponsablecaso_rs == '') {
	$saiu19idresponsablecaso_rs = '{' . $ETI['msg_sindato'] . '}';
}
$html_saiu19idresponsablecasocombo = '<b>' . $saiu19idresponsablecaso_rs . '</b>';
if ($_REQUEST['saiu19estado'] < 7) {
	if ($idTercero == $_REQUEST['saiu19idsupervisorcaso'] || $seg_1707) {
		$objCombos->nuevo('saiu19idresponsablecasofin', $_REQUEST['saiu19idresponsablecaso'], true, '{' . $ETI['msg_seleccione'] . '}');
		$sSQL = 'SELECT TB.bita28idtercero AS id, T2.unad11razonsocial AS nombre
			FROM bita28eqipoparte AS TB, unad11terceros AS T2 
			WHERE  TB.bita28idequipotrab=' . $_REQUEST['saiu19idequipocaso'] . ' AND TB.bita28idtercero=T2.unad11id AND TB.bita28activo="S"
			ORDER BY T2.unad11razonsocial';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' Lista de responsables: '. $sSQL.'<br>ID RESPONSABLE: ' . $_REQUEST['saiu19idresponsablecaso'] .'<br>';
		}
		$html_saiu19idresponsablecasocombo = $objCombos->html($sSQL, $objDB);
	}
}
if ($bEditable || $bPermisoSupv) {
	$objCombos->nuevo('saiu19tipointeresado', $_REQUEST['saiu19tipointeresado'], true, '{'.$ETI['msg_seleccione'].'}');
	$sSQL='SELECT bita07id AS id, bita07nombre AS nombre FROM bita07tiposolicitante ORDER BY bita07orden, bita07nombre';
	$html_saiu19tipointeresado=$objCombos->html($sSQL, $objDB);
	$html_saiu19tiposolicitud=f3019_HTMLComboV2_saiu19tiposolicitud($objDB, $objCombos, $_REQUEST['saiu19tiposolicitud']);
	$html_saiu19temasolicitud=f3019_HTMLComboV2_saiu19temasolicitud($objDB, $objCombos, $_REQUEST['saiu19temasolicitud'], $_REQUEST['saiu19tiposolicitud']);
	$objCombos->nuevo('saiu19idzona', $_REQUEST['saiu19idzona'], true, '{'.$ETI['msg_seleccione'].'}');
	$objCombos->sAccion='carga_combo_saiu19idcentro();';
	$sSQL='SELECT unad23id AS id, unad23nombre AS nombre FROM unad23zona WHERE unad23conestudiantes="S" ORDER BY unad23nombre';
	$html_saiu19idzona=$objCombos->html($sSQL, $objDB);
	$html_saiu19idcentro=f3019_HTMLComboV2_saiu19idcentro($objDB, $objCombos, $_REQUEST['saiu19idcentro'], $_REQUEST['saiu19idzona']);
	$objCombos->nuevo('saiu19codpais', $_REQUEST['saiu19codpais'], true, '{'.$ETI['msg_seleccione'].'}');
	$objCombos->sAccion='carga_combo_saiu19coddepto();';
	$sSQL='SELECT unad18codigo AS id, unad18nombre AS nombre FROM unad18pais ORDER BY unad18nombre';
	$html_saiu19codpais=$objCombos->html($sSQL, $objDB);
	$html_saiu19coddepto=f3019_HTMLComboV2_saiu19coddepto($objDB, $objCombos, $_REQUEST['saiu19coddepto'], $_REQUEST['saiu19codpais']);
	$html_saiu19codciudad=f3019_HTMLComboV2_saiu19codciudad($objDB, $objCombos, $_REQUEST['saiu19codciudad'], $_REQUEST['saiu19coddepto']);
	$objCombos->nuevo('saiu19idescuela', $_REQUEST['saiu19idescuela'], true, '{'.$ETI['msg_seleccione'].'}');
	$objCombos->sAccion='carga_combo_saiu19idprograma();';
	$objCombos->addItem('0', $ETI['msg_na']);
	$sSQL='SELECT core12id AS id, core12nombre AS nombre FROM core12escuela WHERE core12id>0 AND core12tieneestudiantes="S" ORDER BY core12tieneestudiantes DESC, core12nombre';
	$html_saiu19idescuela=$objCombos->html($sSQL, $objDB);
	$html_saiu19idprograma=f3019_HTMLComboV2_saiu19idprograma($objDB, $objCombos, $_REQUEST['saiu19idprograma'], $_REQUEST['saiu19idescuela']);
	$objCombos->nuevo('saiu19idperiodo', $_REQUEST['saiu19idperiodo'], true, '{'.$ETI['msg_seleccione'].'}');
	$objCombos->addItem('0', $ETI['msg_na']);
	$sSQL=f146_ConsultaCombo('exte02id>0');
	$html_saiu19idperiodo=$objCombos->html($sSQL, $objDB);
	$objCombos->nuevo('saiu19paramercadeo', $_REQUEST['saiu19paramercadeo'], true, $ETI['no'], 0);
	$objCombos->addItem(1, $ETI['si']);
	//$objCombos->addArreglo($asaiu19paramercadeo, $isaiu19paramercadeo);
	$html_saiu19paramercadeo=$objCombos->html('', $objDB);
	$objCombos->nuevo('saiu19solucion', $_REQUEST['saiu19solucion'], true, $asaiu19solucion[0], 0);
	//$objCombos->addItem(1, $ETI['si']);
	$objCombos->sAccion='valida_combo_saiu19solucion();';
	$objCombos->addArreglo($asaiu19solucion, $isaiu19solucion);
	$html_saiu19solucion=$objCombos->html('', $objDB);
} else {
	list($saiu19tipointeresado_nombre, $sErrorDet) = tabla_campoxid('bita07tiposolicitante', 'bita07nombre', 'bita07id', $_REQUEST['saiu19tipointeresado'], '{' . $ETI['msg_sindato'] . '}', $objDB);
	$html_saiu19tipointeresado = html_oculto('saiu19tipointeresado', $_REQUEST['saiu19tipointeresado'], $saiu19tipointeresado_nombre);
	list($saiu19tiposolicitud_nombre, $sErrorDet) = tabla_campoxid('saiu02tiposol', 'saiu02titulo', 'saiu02id', $_REQUEST['saiu19tiposolicitud'], '{' . $ETI['msg_sindato'] . '}', $objDB);
	$html_saiu19tiposolicitud = html_oculto('saiu19tiposolicitud', $_REQUEST['saiu19tiposolicitud'], $saiu19tiposolicitud_nombre);
	list($saiu19temasolicitud_nombre, $sErrorDet) = tabla_campoxid('saiu03temasol', 'saiu03titulo', 'saiu03id', $_REQUEST['saiu19temasolicitud'], '{' . $ETI['msg_sindato'] . '}', $objDB);
	$html_saiu19temasolicitud = html_oculto('saiu19temasolicitud', $_REQUEST['saiu19temasolicitud'], $saiu19temasolicitud_nombre);
	list($saiu19idzona_nombre, $sErrorDet) = tabla_campoxid('unad23zona', 'unad23nombre', 'unad23id', $_REQUEST['saiu19idzona'], '{' . $ETI['msg_sindato'] . '}', $objDB);
	$html_saiu19idzona = html_oculto('saiu19idzona', $_REQUEST['saiu19idzona'], $saiu19idzona_nombre);
	list($saiu19idcentro_nombre, $sErrorDet) = tabla_campoxid('unad24sede', 'unad24nombre', 'unad24id', $_REQUEST['saiu19idcentro'], '{' . $ETI['msg_sindato'] . '}', $objDB);
	$html_saiu19idcentro = html_oculto('saiu19idcentro', $_REQUEST['saiu19idcentro'], $saiu19idcentro_nombre);
	list($saiu19codpais_nombre, $sErrorDet) = tabla_campoxid('unad18pais', 'unad18nombre', 'unad18codigo', $_REQUEST['saiu19codpais'], '{' . $ETI['msg_sindato'] . '}', $objDB);
	$html_saiu19codpais = html_oculto('saiu19codpais', $_REQUEST['saiu19codpais'], $saiu19codpais_nombre);
	list($saiu19coddepto_nombre, $sErrorDet) = tabla_campoxid('unad19depto', 'unad19nombre', 'unad19codigo', $_REQUEST['saiu19coddepto'], '{' . $ETI['msg_sindato'] . '}', $objDB);
	$html_saiu19coddepto = html_oculto('saiu19coddepto', $_REQUEST['saiu19coddepto'], $saiu19coddepto_nombre);
	list($saiu19codciudad_nombre, $sErrorDet) = tabla_campoxid('unad20ciudad', 'unad20nombre', 'unad20codigo', $_REQUEST['saiu19codciudad'], '{' . $ETI['msg_sindato'] . '}', $objDB);
	$html_saiu19codciudad = html_oculto('saiu19codciudad', $_REQUEST['saiu19codciudad'], $saiu19codciudad_nombre);
	list($saiu19idescuela_nombre, $sErrorDet) = tabla_campoxid('core12escuela', 'core12nombre', 'core12id', $_REQUEST['saiu19idescuela'], '{' . $ETI['msg_sindato'] . '}', $objDB);
	$html_saiu19idescuela = html_oculto('saiu19idescuela', $_REQUEST['saiu19idescuela'], $saiu19idescuela_nombre);
	list($saiu19idprograma_nombre, $sErrorDet) = tabla_campoxid('core09programa', 'core09nombre', 'core09id', $_REQUEST['saiu19idprograma'], '{' . $ETI['msg_sindato'] . '}', $objDB);
	$html_saiu19idprograma = html_oculto('saiu19idprograma', $_REQUEST['saiu19idprograma'], $saiu19idprograma_nombre);
	list($saiu19idperiodo_nombre, $sErrorDet) = tabla_campoxid('exte02per_aca', 'exte02nombre', 'exte02id', $_REQUEST['saiu19idperiodo'], '{' . $ETI['msg_sindato'] . '}', $objDB);
	$html_saiu19idperiodo = html_oculto('saiu19idperiodo', $_REQUEST['saiu19idperiodo'], $saiu19idperiodo_nombre);
	$html_saiu19solucion = html_oculto('saiu19solucion', $_REQUEST['saiu19solucion'], $asaiu19solucion[$_REQUEST['saiu19solucion']]);
}
if ((int)$_REQUEST['paso'] == 0) {
	$html_saiu19agno=f3019_HTMLComboV2_saiu19agno($objDB, $objCombos, $_REQUEST['saiu19agno']);
	$html_saiu19mes=f3019_HTMLComboV2_saiu19mes($objDB, $objCombos, $_REQUEST['saiu19mes']);
	$html_saiu19dia=html_ComboDia('saiu19dia', $_REQUEST['saiu19dia'], false);
	$html_saiu19tiporadicado=f3019_HTMLComboV2_saiu19tiporadicado($objDB, $objCombos, $_REQUEST['saiu19tiporadicado']);
} else {
	$saiu19agno_nombre=$_REQUEST['saiu19agno'];
	//$saiu19agno_nombre=$asaiu19agno[$_REQUEST['saiu19agno']];
	//list($saiu19agno_nombre, $sErrorDet)=tabla_campoxid('','','',$_REQUEST['saiu19agno'],'{'.$ETI['msg_sindato'].'}', $objDB);
	$html_saiu19agno=html_oculto('saiu19agno', $_REQUEST['saiu19agno'], $saiu19agno_nombre);
	$saiu19mes_nombre=strtoupper(fecha_mes_nombre((int)$_REQUEST['saiu19mes']));
	//$saiu19mes_nombre=$asaiu19mes[$_REQUEST['saiu19mes']];
	//list($saiu19mes_nombre, $sErrorDet)=tabla_campoxid('','','',$_REQUEST['saiu19mes'],'{'.$ETI['msg_sindato'].'}', $objDB);
	$html_saiu19mes=html_oculto('saiu19mes', $_REQUEST['saiu19mes'], $saiu19mes_nombre);
	$saiu19dia_nombre=$_REQUEST['saiu19dia'];
	$html_saiu19dia=html_oculto('saiu19dia', $_REQUEST['saiu19dia'], $saiu19dia_nombre);
	list($saiu19tiporadicado_nombre, $sErrorDet)=tabla_campoxid('saiu16tiporadicado','saiu16nombre','saiu16id',$_REQUEST['saiu19tiporadicado'],'{'.$ETI['msg_sindato'].'}', $objDB);
	$html_saiu19tiporadicado=html_oculto('saiu19tiporadicado', $_REQUEST['saiu19tiporadicado'], $saiu19tiporadicado_nombre);
}
$bEnProceso=true;
if ($_REQUEST['paso']!=0){
	if ($_REQUEST['saiu19estado']>6){$bEnProceso=false;}
	}
if (true){
	}else{
	list($saiu19tiposolicitud_nombre, $sErrorDet)=tabla_campoxid('saiu02tiposol','saiu02titulo','saiu02id',$_REQUEST['saiu19tiposolicitud'],'{'.$ETI['msg_sindato'].'}', $objDB);
	$html_saiu19tiposolicitud=html_oculto('saiu19tiposolicitud', $_REQUEST['saiu19tiposolicitud'], $saiu19tiposolicitud_nombre);
	}
//Alistar datos adicionales
$bPuedeAbrir=false;
$bConBotonAbandona=false;
$bConBotonCancela=false;
if ($_REQUEST['paso']!=0){
	if ($_REQUEST['saiu19estado']>6){
		//Definir las condiciones que permitirán abrir el registro.
		list($bDevuelve, $sDebugP)=seg_revisa_permisoV3($iCodModulo, 17, $idTercero, $objDB);
		if ($bDevuelve){$bPuedeAbrir=true;}
		}else{
		$bConBotonAbandona=true;
		$bConBotonCancela=true;
		}
	}
$id_rpt = 0;
//$id_rpt=reportes_id(_Identificador_Tipo_Reporte_, $objDB);
$objCombos->nuevo('bagno', $_REQUEST['bagno'], false, '{'.$ETI['msg_todos'].'}');
$objCombos->sAccion='paginarf3019()';
$sSQL='SHOW TABLES LIKE "saiu19chat%"';
$tablac=$objDB->ejecutasql($sSQL);
while($filac=$objDB->sf($tablac)){
	$sAgno=substr($filac[0], 11);
	$objCombos->addItem($sAgno, $sAgno);
}
$html_bagno=$objCombos->html('', $objDB);
$objCombos->nuevo('bestado', $_REQUEST['bestado'], true, '{' . $ETI['msg_todos'] . '}');
$objCombos->addItem('-1', 'Borrador');
$objCombos->addItem('0', 'Solicitado');
$objCombos->addItem('1', 'Asignado');
$objCombos->addItem('2', 'En tr&aacute;mite');
$objCombos->addItem('7', 'Resuelto');
$objCombos->sAccion = 'paginarf3019()';
$html_bestado = $objCombos->html('', $objDB);
$bTodos = false;
if ($seg_12 == 1) {
	$bTodos = true;
}
$objCombos->nuevo('blistar', $_REQUEST['blistar'], $bTodos, '{'.$ETI['msg_todos'].'}');
$objCombos->addItem('1', 'Mis registros');
$objCombos->addItem('2', 'Mis asignaciones');
$objCombos->addItem('3', 'Asignado a mi equipo');
$objCombos->sAccion='paginarf3019()';
$html_blistar=$objCombos->html('', $objDB);
$objCombos->nuevo('bcategoria', $_REQUEST['bcategoria'], true, '{' . $ETI['msg_todos'] . '}');
$objCombos->sAccion = 'carga_combo_btema()';
$sSQL = 'SELECT saiu02id AS id, saiu02titulo AS nombre FROM saiu02tiposol WHERE saiu02id>0 ORDER BY saiu02titulo';
$html_bcategoria = $objCombos->html($sSQL, $objDB);
$objCombos->nuevo('btema', $_REQUEST['btema'], true, '{' . $ETI['msg_todos'] . '}');
$html_btema = $objCombos->html('', $objDB);
$objCombos->nuevo('bagnopqrs', $_REQUEST['bagnopqrs'], false, '{' . $ETI['msg_todos'] . '}');
$objCombos->sAccion = 'paginarf3000pqrs()';
$objCombos->numeros(2020, $iAgnoFin, 1);
$html_bagnopqrs = $objCombos->html('', $objDB);
//$html_blistar = $objCombos->comboSistema(3019, 1, $objDB, 'paginarf3019()');
if (false) {
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
$iModeloReporte = 3019;
$html_iFormatoImprime = '<input id="iformatoimprime" name="iformatoimprime" type="hidden" value="0" />';
// TODO
$objCombos->nuevo('saiucanal', $_REQUEST['saiucanal'], false, '{'.$ETI['msg_todas'].'}');
$objCombos->sAccion='cambiacanal()';
$objCombos->addArreglo($aCanal, $iCanal);
$html_saiucanal=$objCombos->html('', $objDB);
// TODO
if ($_REQUEST['paso']>0){
	$bDevuelve=false;
	//list($bDevuelve, $sDebugP)=seg_revisa_permisoV3($iCodModulo, 5, $idTercero, $objDB);
	if ($bDevuelve){
		$seg_5=1;
		}
	if ($_REQUEST['saiu19estado']>6){
		list($bDevuelve, $sDebugP)=seg_revisa_permisoV3($iCodModulo, 8, $idTercero, $objDB);
		if ($bDevuelve){$seg_8=1;}
		}
	}
//Cargar las tablas de datos
list($sErrorR, $sDebugR) = f3019_RevTabla_saiu19chat($_REQUEST['saiu19agno'], $objDB, $bDebug);
$sError = $sError . $sErrorR;
$sDebug = $sDebug . $sDebugR;
$aParametros[0] = ''; //$_REQUEST['p1_3019'];
$aParametros[100] = $idTercero;
$aParametros[101] = $_REQUEST['paginaf3019'];
$aParametros[102] = $_REQUEST['lppf3019'];
$aParametros[103]=$_REQUEST['bnombre'];
$aParametros[104]=$_REQUEST['bagno'];
$aParametros[105] = $_REQUEST['bestado'];
$aParametros[106] = $_REQUEST['blistar'];
$aParametros[107] = $_REQUEST['bdoc'];
$aParametros[108] = $_REQUEST['bcategoria'];
$aParametros[109] = $_REQUEST['btema'];
list($sTabla3019, $sDebugTabla) = f3019_TablaDetalleV2($aParametros, $objDB, $bDebug);
$sDebug = $sDebug . $sDebugTabla;
$sTabla3000='';
$aParametros3000[0]=$idTercero;
$aParametros3000[1]=$iCodModulo;
$aParametros3000[2]=$_REQUEST['saiu19agno'];
$aParametros3000[3]=$_REQUEST['saiu19id'];
$aParametros3000[100]=$_REQUEST['saiu19idsolicitante'];
$aParametros3000[101]=$_REQUEST['paginaf3000'];
$aParametros3000[102]=$_REQUEST['lppf3000'];
//$aParametros3000[103]=$_REQUEST['bnombre3000'];
//$aParametros3000[104]=$_REQUEST['blistar3000'];
list($sTabla3000, $sDebugTabla)=f3000_TablaDetalleV2($aParametros3000, $objDB, $bDebug);
$sDebug=$sDebug.$sDebugTabla;
$sTabla3000pqrs='';
$aParametros3000[101]=$_REQUEST['paginaf3000pqrs'];
$aParametros3000[102]=$_REQUEST['lppf3000pqrs'];
$aParametros3000[103]=$_REQUEST['bagnopqrs'];
//$aParametros3000[104]=$_REQUEST['blistar3000'];
list($sTabla3000pqrs, $sDebugTabla)=f3000pqrs_TablaDetalleV2($aParametros3000, $objDB, $bDebug);
$sDebug=$sDebug.$sDebugTabla;
$bDebugMenu=false;
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
		if ($bPuedeEliminar) {
			$aBotones[$iNumBoton] = array('eliminadato()', $ETI['bt_eliminar'], 'iDelete');
			$iNumBoton++;
		}
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
		if ($bPuedeCerrar) {
			$aBotones[$iNumBoton] = array('enviacerrar()', $ETI['bt_cerrar'], 'iTask');
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
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>css/css_tabs.css" type="text/css" />
<script language="javascript">
function enviaguardar(){
	window.document.frmedita.iscroll.value=window.pageYOffset;
	expandesector(98);
	var dpaso=window.document.frmedita.paso;
	if (dpaso.value==0){
		dpaso.value=10;
		}else{
		dpaso.value=12;
		}
	window.document.frmedita.submit();
	}
function cambiapagina(){
	expandesector(98);
	window.document.frmedita.submit();
	}
function cambiapaginaV2(){
	expandesector(98);
	window.document.frmedita.paso.value=1;
	window.document.frmedita.submit();
	}
function expandepanel(codigo,estado,valor){
	let objdiv= document.getElementById('div_p'+codigo);
	let objban= document.getElementById('boculta'+codigo);
	let otroestado='none';
	if (estado=='none'){
		otroestado='block';
	}
	objdiv.style.display=estado;
	objban.value=valor;
	verboton('btrecoge'+codigo,estado);
	verboton('btexpande'+codigo,otroestado);
	}
function verboton(idboton,estado){
	let objbt=document.getElementById(idboton);
	objbt.style.display=estado;
	}
function expandesector(codigo) {
	document.getElementById('div_sector1').style.display = 'none';
	document.getElementById('div_sector2').style.display = 'none';
	document.getElementById('div_sector93').style.display='none';
	document.getElementById('div_sector95').style.display = 'none';
	document.getElementById('div_sector96').style.display = 'none';
	document.getElementById('div_sector97').style.display='none';
	document.getElementById('div_sector98').style.display = 'none';
	document.getElementById('div_sector' + codigo).style.display = 'block';
<?php
switch ($iPiel) {
	case 2:
?>
		document.getElementById('botones_sector1').style.display = 'none';
		document.getElementById('botones_sector97').style.display = 'none';
		switch (codigo) {
			case 1:
				document.getElementById('botones_sector1').style.display = 'flex';
				break;
			case 2:
				document.getElementById('botones_sector2').style.display = 'flex';
				break;
			case 97:
				document.getElementById('botones_sector1').style.display = 'none';
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
		if (window.document.frmedita.saiu19estado.value<7){
		document.getElementById('cmdGuardarf').style.display = sEst;
		}
<?php
		}
		break;
}
?>
	}
function ter_retorna(){
	var sRetorna=window.document.frmedita.div96v2.value;
	if (sRetorna!=''){
		let idcampo=window.document.frmedita.div96campo.value;
		let illave=window.document.frmedita.div96llave.value;
		let did=document.getElementById(idcampo);
		let dtd=document.getElementById(idcampo+'_td');
		let ddoc=document.getElementById(idcampo+'_doc');
		dtd.value=window.document.frmedita.div96v1.value;
		ddoc.value=sRetorna;
		did.value=window.document.frmedita.div96v3.value;
		ter_muestra(idcampo, illave);
		}
	MensajeAlarmaV2('', 0);
	retornacontrol();
	}
function ter_muestra(idcampo, illave){
	let saiu19idsolicitante=0;
	let params=new Array();
	params[1]=document.getElementById(idcampo+'_doc').value;
	if (params[1]!=''){
		params[0]=document.getElementById(idcampo+'_td').value;
		params[2]=idcampo;
		params[3]='div_'+idcampo;
		if (illave==1){params[4]='RevisaLlave';}
		//if (illave==1){params[5]='FuncionCuandoNoEsta';}
		if (idcampo=='saiu19idsolicitante'){
			params[6]=3019;
			xajax_unad11_Mostrar_v2SAI(params);
		}else{
			xajax_unad11_Mostrar_v2(params);
		}
	}else{
		document.getElementById(idcampo).value=0;
		document.getElementById('div_'+idcampo).innerHTML='&nbsp;';
		paginarf3000();
		paginarf3000pqrs();
	}
	saiu19idsolicitante = document.getElementById('saiu19idsolicitante').value;
	if (saiu19idsolicitante == 0) {
		document.getElementById('div_saiu19regsolicitante').style.display='none';
	} else {
		document.getElementById('div_saiu19regsolicitante').style.display='block';
	}
}
function ter_traerxid(idcampo, vrcampo){
	let params=new Array();
	params[0]=vrcampo;
	params[1]=idcampo;
	if (params[0]!=0){
		params[6]=3019;
		xajax_unad11_TraerXidSAI(params);
		}
	}
function imprimelista(){
	if (window.document.frmedita.seg_6.value==1){
		window.document.frmlista.consulta.value=window.document.frmedita.consulta_3019.value;
		window.document.frmlista.titulos.value=window.document.frmedita.titulos_3019.value;
		window.document.frmlista.nombrearchivo.value='Sesiones de chat';
		window.document.frmlista.submit();
		}else{
		window.alert("<?php echo $ERR['6']; ?>");
		}
	}
function asignarvariables(){
	window.document.frmimpp.v0.value = <?php echo $idTercero; ?>;
	window.document.frmimpp.v3.value = window.document.frmedita.bagno.value;
	window.document.frmimpp.v4.value = window.document.frmedita.bestado.value;
	window.document.frmimpp.v5.value = window.document.frmedita.blistar.value;
	window.document.frmimpp.separa.value = window.document.frmedita.csv_separa.value.trim();
}
function imprimeexcel(){
	let sError='';
	if (window.document.frmedita.seg_6.value!=1){sError="<?php echo $ERR['6']; ?>";}
	//if (sError==''){/*Agregar validaciones*/}
	if (sError==''){
		asignarvariables();
		window.document.frmimpp.action='e3019.php';
		window.document.frmimpp.submit();
		}else{
		ModalMensaje(sError);
		}
	}
function imprimep(){
	if (window.document.frmedita.seg_5.value==1){
		asignarvariables();
		window.document.frmimpp.action='p3019.php';
		window.document.frmimpp.submit();
<?php
if ($iNumFormatosImprime>0){
?>
		expandesector(1);
<?php
	}
?>
		}else{
		ModalMensaje("<?php echo $ERR['5']; ?>");
		}
	}
function verrpt(){
	window.document.frmimprime.submit();
	}
function eliminadato(){
	ModalConfirm('&iquest;<?php echo $ETI['confirma_eliminar']; ?>?');
	ModalDialogConfirm(function(confirm){if(confirm){ejecuta_eliminadato();}});
	}
function ejecuta_eliminadato(){
	MensajeAlarmaV2('<?php echo $ETI['msg_ejecutando']; ?>', 2);
	expandesector(98);
	window.document.frmedita.paso.value=13;
	window.document.frmedita.submit();
	}
function RevisaLlave(){
	let datos= new Array();
	datos[1]=window.document.frmedita.saiu19agno.value;
	datos[2]=window.document.frmedita.saiu19mes.value;
	datos[3]=window.document.frmedita.saiu19tiporadicado.value;
	datos[4]=window.document.frmedita.saiu19consec.value;
	if ((datos[1]!='')&&(datos[2]!='')&&(datos[3]!='')&&(datos[4]!='')){
		xajax_f3019_ExisteDato(datos);
		}
	}
function cargadato(llave1, llave2, llave3, llave4){
	window.document.frmedita.saiu19agno.value=String(llave1);
	window.document.frmedita.saiu19mes.value=String(llave2);
	window.document.frmedita.saiu19tiporadicado.value=String(llave3);
	window.document.frmedita.saiu19consec.value=String(llave4);
	window.document.frmedita.paso.value=1;
	window.document.frmedita.submit();
	}
function cargaridf3019(llave1, llave2){
	window.document.frmedita.saiu19agno.value=String(llave1);
	window.document.frmedita.saiu19id.value=String(llave2);
	window.document.frmedita.paso.value=3;
	window.document.frmedita.submit();
	}
function carga_combo_saiu19tiposolicitud(){
	let params=new Array();
	params[0]=window.document.frmedita.saiu19temasolicitud.value;
	document.getElementById('div_saiu19tiposolicitud').innerHTML='<b>Procesando datos, por favor espere...</b><input id="saiu19tiposolicitud" name="saiu19tiposolicitud" type="hidden" value="" />';
	document.getElementById('div_saiu19temasolicitud').innerHTML='<b>Procesando datos, por favor espere...</b><input id="saiu19temasolicitud" name="saiu19temasolicitud" type="hidden" value="" />';
	xajax_f3019_Combosaiu19tiposolicitud(params);
	}
function carga_combo_saiu19temasolicitud(){
	let params=new Array();
	params[0]=window.document.frmedita.saiu19tiposolicitud.value;
	document.getElementById('div_saiu19temasolicitud').innerHTML='<b>Procesando datos, por favor espere...</b><input id="saiu19temasolicitud" name="saiu19temasolicitud" type="hidden" value="" />';
	xajax_f3019_Combosaiu19temasolicitud(params);
	}
function carga_combo_saiu19idcentro(){
	let params=new Array();
	params[0]=window.document.frmedita.saiu19idzona.value;
	document.getElementById('div_saiu19idcentro').innerHTML='<b>Procesando datos, por favor espere...</b><input id="saiu19idcentro" name="saiu19idcentro" type="hidden" value="" />';
	xajax_f3019_Combosaiu19idcentro(params);
	}
function carga_combo_saiu19coddepto(){
	let params=new Array();
	params[0]=window.document.frmedita.saiu19codpais.value;
	document.getElementById('div_saiu19coddepto').innerHTML='<b>Procesando datos, por favor espere...</b><input id="saiu19coddepto" name="saiu19coddepto" type="hidden" value="" />';
	xajax_f3019_Combosaiu19coddepto(params);
	}
function carga_combo_saiu19codciudad(){
	let params=new Array();
	params[0]=window.document.frmedita.saiu19coddepto.value;
	document.getElementById('div_saiu19codciudad').innerHTML='<b>Procesando datos, por favor espere...</b><input id="saiu19codciudad" name="saiu19codciudad" type="hidden" value="" />';
	xajax_f3019_Combosaiu19codciudad(params);
	}
function carga_combo_saiu19idprograma(){
	let params=new Array();
	params[0]=window.document.frmedita.saiu19idescuela.value;
	document.getElementById('div_saiu19idprograma').innerHTML='<b>Procesando datos, por favor espere...</b><input id="saiu19idprograma" name="saiu19idprograma" type="hidden" value="" />';
	xajax_f3019_Combosaiu19idprograma(params);
	}
function paginarf3019() {
	let params = new Array();
	params[99]=window.document.frmedita.debug.value;
	params[100]=<?php echo $idTercero; ?>;
	params[101]=window.document.frmedita.paginaf3019.value;
	params[102]=window.document.frmedita.lppf3019.value;
	params[103]=window.document.frmedita.bnombre.value;
	params[104] = window.document.frmedita.bagno.value;
	params[105] = window.document.frmedita.bestado.value;
	params[106] = window.document.frmedita.blistar.value;
	params[107] = window.document.frmedita.bdoc.value;
	params[108] = window.document.frmedita.bcategoria.value;
	params[109] = window.document.frmedita.btema.value;
	document.getElementById('div_f3019detalle').innerHTML='<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf3019" name="paginaf3019" type="hidden" value="'+params[101]+'" /><input id="lppf3019" name="lppf3019" type="hidden" value="'+params[102]+'" />';
	xajax_f3019_HtmlTabla(params);
	}
function enviacerrar(){
	ModalMensajeV2('<?php echo $ETI['msg_cerrar']; ?>', () => {
		expandesector(98);
		window.document.frmedita.paso.value=16;
		window.document.frmedita.submit();
	}, {
		botonAceptar: 'Cerrar'
	});
}
function enviaabrir(){
	if (confirm('Esta seguro de abrir el registro?\nesto le permite volver a modificar')){
		expandesector(98);
		window.document.frmedita.paso.value=17;
		window.document.frmedita.submit();
		}
	}
function revfoco(objeto){
	setTimeout(function(){objeto.focus();},10);
	}
function siguienteobjeto(){}
document.onkeydown=function(e){
	if (document.all){
		if (event.keyCode==13){event.keyCode=9;}
		}else{
		if (e.which==13){siguienteobjeto();}
		}
	}
function objinicial(){
	document.getElementById("saiu19agno").focus();
	}
function buscarV2016(sCampo){
	window.document.frmedita.iscroll.value=window.scrollY;
	expandesector(98);
	window.document.frmedita.scampobusca.value=sCampo;
	var params=new Array();
	params[1]=sCampo;
	//params[2]=window.document.frmedita.iagno.value;
	//params[3]=window.document.frmedita.itipo.value;
	xajax_f3019_Busquedas(params);
	}
function retornacontrol(){
	expandesector(1);
	window.scrollTo(0, window.document.frmedita.iscroll.value);
	}
function Devuelve(sValor){
	let sCampo=window.document.frmedita.scampobusca.value;
	if (sCampo=='saiu19idsolicitante'){
		ter_traerxid('saiu19idsolicitante', sValor);
		}
	if (sCampo=='saiu19idresponsable'){
		ter_traerxid('saiu19idresponsable', sValor);
		}
	retornacontrol();
	}
function mantener_sesion(){xajax_sesion_mantenerV4();}
setInterval ('xajax_sesion_abandona_V2();', 60000);
function AyudaLocal(sCampo){
	let divAyuda=document.getElementById('div_ayuda_'+sCampo);
	if (typeof divAyuda==='undefined'){
		}else{
		verboton('cmdAyuda_'+sCampo, 'none');
		var sMensaje='Lo que quiera decir.';
		//if (sCampo=='sNombreCampo'){sMensaje='Mensaje para otro campo.';}
		divAyuda.innerHTML=sMensaje;
		divAyuda.style.display='block';
		}
	}
function cierraDiv96(ref){
	let sRetorna=window.document.frmedita.div96v2.value;
	if (ref == '3019') {
		if (sRetorna != '') {
			window.document.frmedita.saiu19idorigen.value = window.document.frmedita.div96v1.value;
			window.document.frmedita.saiu19idarchivo.value = sRetorna;
			verboton('beliminasaiu19idarchivo', 'block');
		}
		archivo_lnk(window.document.frmedita.saiu19idorigen.value, window.document.frmedita.saiu19idarchivo.value, 'div_saiu19idarchivo');
	}
	if (ref == '3019rta') {
		if (sRetorna != '') {
			window.document.frmedita.saiu19idorigenrta.value = window.document.frmedita.div96v1.value;
			window.document.frmedita.saiu19idarchivorta.value = sRetorna;
			verboton('beliminasaiu19idarchivorta', 'block');
		}
		archivo_lnk(window.document.frmedita.saiu19idorigenrta.value, window.document.frmedita.saiu19idarchivorta.value, 'div_saiu19idarchivorta');
	}
	retornacontrol();
	}
function mod_consec(){
	ModalConfirm('<?php echo $ETI['msg_confirmamodconsec']; ?>');
	ModalDialogConfirm(function(confirm){if(confirm){ejecuta_modconsec();}});
	}
function ejecuta_modconsec(){
	MensajeAlarmaV2('<?php echo $ETI['msg_ejecutando']; ?>', 2);
	expandesector(98);
	window.document.frmedita.paso.value=93;
	window.document.frmedita.submit();
	}
function abandonar(){
	if (confirm("Confirma que el solicitante abandono la conversaci&oacute;n?")){
		expandesector(98);
		window.document.frmedita.paso.value=21;
		window.document.frmedita.submit();
		}
	}
function cancelar(){
	if (confirm("Confirma que la llamada fue cancelada?")){
		expandesector(98);
		window.document.frmedita.paso.value=22;
		window.document.frmedita.submit();
		}
	}
function paginarf3000(){
	let params=new Array();
	params[0]=window.document.frmedita.id11.value;
	params[1]=3019;
	params[2]=window.document.frmedita.saiu19agno.value;
	params[3]=window.document.frmedita.saiu19id.value;
	params[99]=window.document.frmedita.debug.value;
	params[100]=window.document.frmedita.saiu19idsolicitante.value;
	params[101]=window.document.frmedita.paginaf3000.value;
	params[102]=window.document.frmedita.lppf3000.value;
	//params[103]=window.document.frmedita.bnombre3000.value;
	//params[104]=window.document.frmedita.blistar3000.value;
	document.getElementById('div_f3000detalle').innerHTML='<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf3000" name="paginaf3000" type="hidden" value="'+params[101]+'" /><input id="lppf3000" name="lppf3000" type="hidden" value="'+params[102]+'" />';
	xajax_f3000_HtmlTabla(params);
	}
function paginarf3000pqrs(){
	let params=new Array();
	params[0]=window.document.frmedita.id11.value;
	params[1]=3019;
	params[2]=window.document.frmedita.saiu19agno.value;
	params[3]=window.document.frmedita.saiu19id.value;
	params[99]=window.document.frmedita.debug.value;
	params[100]=window.document.frmedita.saiu19idsolicitante.value;
	params[101]=window.document.frmedita.paginaf3000pqrs.value;
	params[102]=window.document.frmedita.lppf3000pqrs.value;
	params[103]=window.document.frmedita.bagnopqrs.value;
	//params[104]=window.document.frmedita.blistar3000.value;
	document.getElementById('div_f3000pqrsdetalle').innerHTML='<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf3000pqrs" name="paginaf3000pqrs" type="hidden" value="'+params[101]+'" /><input id="lppf3000pqrs" name="lppf3000pqrs" type="hidden" value="'+params[102]+'" />';
	xajax_f3000pqrs_HtmlTabla(params);
	}
function valida_combo_saiu19solucion() {
	let iSolucion = parseInt(document.getElementById('saiu19solucion').value);
	let iEstado = parseInt(document.getElementById('saiu19estado').value);
	switch(iSolucion) {
		case 1:
		document.getElementById('div_saiu19respuesta').style.display='block';
		break;
		case 0:
		case 5:
		case 3:
		document.getElementById('div_saiu19respuesta').style.display='none';
		if (iEstado==1) {
			document.getElementById('div_saiu19respuesta').style.display='block';
		}
		break;		
		default:
		document.getElementById('div_saiu19respuesta').style.display='none';
		break;
	}
}
function actualizaratiende() {
	let sError = '';
	if (window.document.frmedita.saiu19id.value == '') {
		sError = 'Por favor seleccione una solicitud.';
	}
	if (sError == '') {
		window.document.frmedita.iscroll.value = window.pageYOffset;
		MensajeAlarmaV2('<?php echo $ETI['msg_ejecutando']; ?>...', 2);
		expandesector(98);
		window.document.frmedita.paso.value = 27;
		window.document.frmedita.submit();
	}
}
<?php
if ($_REQUEST['saiu19estado'] < 7) {
	if ($bPermisoSupv || $seg_1707) {
?>
	function enviareasignar() {
		window.document.frmedita.iscroll.value = window.pageYOffset;
		ModalConfirmV2('<?php echo $ETI['pregunta_reasigna']; ?>', () => {
			ejecuta_enviareasignar();
		});
	}

	function ejecuta_enviareasignar() {
		MensajeAlarmaV2('<?php echo $ETI['msg_ejecutando']; ?>', 2);
		expandesector(98);
		window.document.frmedita.paso.value = 26;
		window.document.frmedita.submit();
	}
<?php
	}
}
?>
function abrir_tab(evt, sId) {
	evt.preventDefault();
	let i, tabcontent, tablinks;
	tabcontent = document.getElementsByClassName("tabcontent");
	for (i = 0; i < tabcontent.length; i++) {
		tabcontent[i].style.display = "none";
	}
	tablinks = document.getElementsByClassName("tablinks");
	for (i = 0; i < tablinks.length; i++) {
		tablinks[i].className = tablinks[i].className.replace(" active", "");
	}
	document.getElementById(sId).style.display = "flex";
	document.getElementById(sId).style.flexWrap = "wrap";
	evt.currentTarget.className += " active";
}
<?php
// if ($_REQUEST['saiu19estado']==-1) {
if (true) {
?>
	function limpia_saiu19idarchivo(){
		window.document.frmedita.saiu19idorigen.value=0;
		window.document.frmedita.saiu19idarchivo.value=0;
		let da_Archivo=document.getElementById('div_saiu19idarchivo');
		da_Archivo.innerHTML='&nbsp;';
		verboton('beliminasaiu19idarchivo','none');
		//paginarf0000();
	}
	function carga_saiu19idarchivo(){
		window.document.frmedita.iscroll.value=window.pageYOffset;
		window.document.frmedita.div96v1.value='';
		window.document.frmedita.div96v2.value='';
		window.document.frmedita.div96v3.value='';
		let saiu19id=window.document.frmedita.saiu19id.value;
		let agno=window.document.frmedita.saiu19agno.value;
		document.getElementById('div_96titulo').innerHTML='<h2>'+window.document.frmedita.titulo_3019.value+' - Cargar archivo detalle</h2>';
		document.getElementById('div_96cuerpo').innerHTML='<iframe id="iframe96" src="framearchivodis.php?ref=3019&id='+saiu19id+'&tabla=_'+agno+'&tipo=0" height="400px" width="100%" frameborder="0"></iframe>';
		expandesector(96);
		window.scrollTo(0, 150);
	}
	function eliminasaiu19idarchivo(){
		let did=window.document.frmedita.saiu19id;
		let agno=window.document.frmedita.saiu19agno.value;
		if (confirm("Esta seguro de eliminar el archivo?")){
			xajax_elimina_archivo_saiu19idarchivo(did.value, agno);
			//paginarf0000();
		}
	}
<?php
}
?>
<?php
// if ($_REQUEST['saiu19estado']==-1) {
if (true) {
?>
	function limpia_saiu19idarchivorta(){
		window.document.frmedita.saiu19idorigenrta.value=0;
		window.document.frmedita.saiu19idarchivorta.value=0;
		let da_Archivo=document.getElementById('div_saiu19idarchivorta');
		da_Archivo.innerHTML='&nbsp;';
		verboton('beliminasaiu19idarchivorta','none');
		//paginarf0000();
	}
	function carga_saiu19idarchivorta(){
		window.document.frmedita.iscroll.value=window.pageYOffset;
		window.document.frmedita.div96v1.value='';
		window.document.frmedita.div96v2.value='';
		window.document.frmedita.div96v3.value='';
		let saiu19id=window.document.frmedita.saiu19id.value;
		let agno=window.document.frmedita.saiu19agno.value;
		document.getElementById('div_96titulo').innerHTML='<h2>'+window.document.frmedita.titulo_3019.value+' - Cargar archivo respuesta</h2>';
		document.getElementById('div_96cuerpo').innerHTML='<iframe id="iframe96" src="framearchivodis.php?ref=3019rta&id='+saiu19id+'&tabla=_'+agno+'&tipo=0" height="400px" width="100%" frameborder="0"></iframe>';
		expandesector(96);
		window.scrollTo(0, 150);
	}
	function eliminasaiu19idarchivorta(){
		let did=window.document.frmedita.saiu19id;
		let agno=window.document.frmedita.saiu19agno.value;
		if (confirm("Esta seguro de eliminar el archivo?")){
			xajax_elimina_archivo_saiu19idarchivorta(did.value, agno);
			//paginarf0000();
		}
	}
<?php
}
?>
function carga_combo_btema() {
	let params = new Array();
	params[0] = window.document.frmedita.bcategoria.value;
	document.getElementById('div_btema').innerHTML = '<b>Procesando datos, por favor espere...</b><input id="btema" name="btema" type="hidden" value="" />';
	xajax_f3019_Combobtema(params);
}
</script>
<?php
// if ($_REQUEST['paso']!=0){
?>
<form id="frmimpp" name="frmimpp" method="post" action="p3019.php" target="_blank" style="display:none;">
<input id="r" name="r" type="hidden" value="3019" />
<input id="id3019" name="id3019" type="hidden" value="<?php echo $_REQUEST['saiu19id']; ?>" />
<input id="v0" name="v0" type="hidden" value="" />
<input id="v3" name="v3" type="hidden" value="" />
<input id="v4" name="v4" type="hidden" value="" />
<input id="v5" name="v5" type="hidden" value="" />
<input id="iformato94" name="iformato94" type="hidden" value="0" />
<input id="separa" name="separa" type="hidden" value="," />
<input id="rdebug" name="rdebug" type="hidden" value="<?php echo $_REQUEST['debug']; ?>"/>
<input id="clave" name="clave" type="hidden" value="" />
</form>
<?php
// }
?>
<form id="frmlista" name="frmlista" method="post" action="listados.php" target="_blank" style="display:none;">
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
<input id="vdtipointeresado" name="vdtipointeresado" type="hidden" value="<?php echo $_REQUEST['vdtipointeresado']; ?>" />
<input id="vdidchat" name="vdidchat" type="hidden" value="<?php echo $_REQUEST['vdidchat']; ?>" />
<div id="div_sector1">
<!-- TODO -->
<div class="areaform"> 
<div class="areatrabajo">
<div class="GrupoCamposAyuda">
<div class="salto5px"></div>
<label class="Label160">
<?php
echo $ETI['saiucanal'];
?>
</label>
<label class="Label160">
<?php
echo $html_saiucanal;
?>
</label>
<div class="salto5px"></div>
</div>
</div>
</div>
<!-- TODO -->
<?php
if ($bBloqueTitulo) {
?>
<div class="titulos">
<div class="titulosD">
<input id="cmdAyuda" name="cmdAyuda" type="button" class="btUpAyuda" onclick="muestraayuda(<?php echo $APP->idsistema . ', ' . $iCodModulo; ?>);" title="<?php echo $ETI['bt_ayuda']; ?>" value="<?php echo $ETI['bt_ayuda']; ?>" />
<?php
if ($_REQUEST['paso']==2){
	if ($_REQUEST['saiu19estado']<7 && $bPuedeEliminar){
?>
<input id="cmdEliminar" name="cmdEliminar" type="button" class="btUpEliminar" onclick="eliminadato();" title="<?php echo $ETI['bt_eliminar']; ?>" value="<?php echo $ETI['bt_eliminar']; ?>"/>
<?php
		}
	}
?>
<input id="cmdLimpiar" name="cmdLimpiar" type="button" class="btUpLimpiar" onclick="limpiapagina();" title="<?php echo $ETI['bt_limpiar']; ?>" value="<?php echo $ETI['bt_limpiar']; ?>"/>
<?php
if ($_REQUEST['saiu19estado']<7 && $bPuedeGuardar){
?>
<input id="cmdGuardar" name="cmdGuardar" type="button" class="btUpGuardar" onclick="enviaguardar();" title="<?php echo $ETI['bt_guardar']; ?>" value="<?php echo $ETI['bt_guardar']; ?>"/>
<?php
	if ($_REQUEST['paso']>0 && $bPuedeCerrar){
?>
<input id="cmdCerrar" name="cmdCerrar" type="button" class="btSupCerrar" onClick="enviacerrar();" title="Cerrar" value="Cerrar"/>
<?php
		}
	}else{
	if ($_REQUEST['paso']>0){
		if ($bPuedeAbrir){
?>
<input id="cmdAbrir" name="cmdAbrir" type="button" class="btSupAbrir" onclick="enviaabrir();" title="Abrir" value="Abrir"/>
<?php
			}
		}
	}
if (false){
?>
<input id="cmdAnular" name="cmdAnular" type="button" class="btSupAnular" onclick="expandesector(2);" title="<?php echo $ETI['bt_anular']; ?>" value="<?php echo $ETI['bt_anular']; ?>"/>
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
Documento
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
<label class="Label30">
<input id="btRevisaDoc" name="btRevisaDoc" type="button" value="Actualizar" class="btMiniActualizar" onclick="limpiapagina()" title="Consultar documento" />
</label>
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
<div class="ir_derecha"<?php echo $sAnchoExpandeContrae; ?>>
<?php
echo $objForma->htmlExpande(3019, $_REQUEST['boculta3019'], $ETI['bt_mostrar'], $ETI['bt_ocultar']);
$sEstiloDiv = '';
if ($_REQUEST['boculta3019'] != 0) {
	$sEstiloDiv = ' style="display:none;"';
}
?>
</div>
<div id="div_p3019"<?php echo $sEstiloDiv; ?>>
<?php
}
//Mostrar formulario para editar
?>
<label class="Label130">
<?php
echo $ETI['msg_fecha'];
?>
</label>
<?php
if ($_REQUEST['paso']==0){
?>
<label class="Label60">
<?php
echo $html_saiu19dia;
?>
</label>
<label class="Label130">
<?php
echo $html_saiu19mes;
?>
</label>
<label class="Label90">
<?php
echo $html_saiu19agno;
?>
</label>
<?php
	}else{
?>
<label class="Label220">
<?php
echo $html_saiu19dia.'/'.$html_saiu19mes.'/'.$html_saiu19agno;
?>
</label>
<?php
	}
?>
<label class="Label60">
<?php
echo $ETI['saiu19hora'];
?>
</label>
<div class="campo_HoraMin" id="div_saiu19hora">
<?php
$bOculto=False;
if ($_REQUEST['paso']!=0){$bOculto=True;}
echo html_HoraMin('saiu19hora', $_REQUEST['saiu19hora'], 'saiu19minuto', $_REQUEST['saiu19minuto'], $bOculto);
?>
</div>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu19consec'];
?>
</label>
<label class="Label130">
<?php
if ($_REQUEST['paso']!=2){
?>
<input id="saiu19consec" name="saiu19consec" type="text" value="<?php echo $_REQUEST['saiu19consec']; ?>" onchange="RevisaLlave()" class="cuatro"/>
<?php
	}else{
	echo html_oculto('saiu19consec', $_REQUEST['saiu19consec'], formato_numero($_REQUEST['saiu19consec']));
	}
?>
</label>
<?php
/*
if ($seg_8==1){
	$objForma=new clsHtmlForma($iPiel);
	echo $objForma->htmlBotonSolo('cmdCambiaConsec', 'btMiniActualizar', 'expandesector(93);', $ETI['bt_cambiar'], 30);
	echo '<label class="Label30">&nbsp;</label>';
	}
*/
?>
<label class="Label60">
<?php
echo $ETI['saiu19id'];
?>
</label>
<label class="Label60">
<?php
	echo html_oculto('saiu19id', $_REQUEST['saiu19id'], formato_numero($_REQUEST['saiu19id']));
?>
</label>
<label class="Label130">
<?php
echo $ETI['saiu19estado'];
?>
</label>
<label>
<div id="div_saiu19estado">
<?php
echo $html_saiu19estado;
?>
</div>
<input id="saiu19estadoorigen" name="saiu19estadoorigen" type="hidden" value="<?php echo $_REQUEST['saiu19estadoorigen']; ?>" />
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu19idchat'];
?>
</label>
<label>
<?php
echo $html_saiu19idchat;
?>
</label>
<label class="Label160">
<?php
echo $ETI['saiu19numsesionchat'];
?>
</label>
<label>
<input id="saiu19numsesionchat" name="saiu19numsesionchat" type="text" value="<?php echo $_REQUEST['saiu19numsesionchat']; ?>" maxlength="20" placeholder="<?php echo $ETI['ing_campo'].$ETI['saiu19numsesionchat']; ?>"/>
</label>
<div class="salto1px"></div>
<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo $ETI['saiu19idsolicitante'];
?>
</label>
<div class="salto1px"></div>
<input id="saiu19idsolicitante" name="saiu19idsolicitante" type="hidden" value="<?php echo $_REQUEST['saiu19idsolicitante']; ?>"/>
<div id="div_saiu19idsolicitante_llaves">
<?php
$bOculto=!$bEnProceso;
echo html_DivTerceroV2('saiu19idsolicitante', $_REQUEST['saiu19idsolicitante_td'], $_REQUEST['saiu19idsolicitante_doc'], $bOculto, 0, $ETI['ing_doc']);
?>
</div>
<div class="salto1px"></div>
<div id="div_saiu19idsolicitante" class="L"><?php echo $saiu19idsolicitante_rs; ?></div>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['saiu19tipointeresado'];
?>
</label>
<label class="Label200">
<?php
echo $html_saiu19tipointeresado;
?>
</label>
<div class="salto1px"></div>
<label class="L">
<?php
echo $ETI['saiu19numorigen'];
?>
<input id="saiu19numorigen" name="saiu19numorigen" type="text" value="<?php echo $_REQUEST['saiu19numorigen']; ?>" maxlength="20" placeholder="<?php echo $ETI['ing_campo'].$ETI['saiu19numorigen']; ?>" class="L"/>
</label>
<div class="salto1px"></div>
<?php
if ($_REQUEST['saiu19idsolicitante'] == 0) {
	$sEstiloDiv = ' style="display:block;"';
} else {
	$sEstiloDiv = ' style="display:none;"';
}
?>
<div id="div_saiu19regsolicitante" class="L" <?php echo $sEstiloDiv; ?>>
<input id="cmdRegSolicitante" name="cmdRegSolicitante" type="button" value="<?php echo $ETI['saiu19regsolicitante']; ?>" class="BotonAzul200" onclick="window.open('unadpersonas.php', '_blank');" title="<?php echo $ETI['saiu19regsolicitante']; ?>"/>
</div>
<div class="salto1px"></div>
</div>

<div class="GrupoCampos520">
<label class="Label130">
<?php
echo $ETI['saiu19idzona'];
?>
</label>
<label>
<?php
echo $html_saiu19idzona;
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu19idcentro'];
?>
</label>
<label>
<div id="div_saiu19idcentro">
<?php
echo $html_saiu19idcentro;
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu19codpais'];
?>
</label>
<label>
<?php
echo $html_saiu19codpais;
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu19coddepto'];
?>
</label>
<label>
<div id="div_saiu19coddepto">
<?php
echo $html_saiu19coddepto;
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu19codciudad'];
?>
</label>
<label>
<div id="div_saiu19codciudad">
<?php
echo $html_saiu19codciudad;
?>
</div>
</label>
<div class="salto1px"></div>
</div>

<div class="salto1px"></div>
<div class="GrupoCampos">
<div class="tab">
<button class="tablinks" onclick="abrir_tab(event, 'solicitudes')" id="tab_inicial"><?php echo $ETI['titulo_3000']; ?></button>
<button class="tablinks" onclick="abrir_tab(event, 'pqrs')"><?php echo $ETI['titulo_3005_pqrs']; ?></button>
</div>
<input id="boculta3000" name="boculta3000" type="hidden" value="<?php echo $_REQUEST['boculta3000']; ?>" />
<div id="solicitudes" class="tabcontent">
<div id="div_f3000detalle">
<?php
echo $sTabla3000;
?>
</div>
</div>
<div id="pqrs" class="tabcontent">
<div>
<label class="Label60">
<?php
echo $ETI['saiu19agno'];
?>
</label>
<label class="Label90">
<?php
echo $html_bagnopqrs;
?>
</label>
</div>
<div class="salto1px"></div>
<div id="div_f3000pqrsdetalle">
<?php
echo $sTabla3000pqrs;
?>
</div>
</div>
<div class="salto1px"></div>
</div>

<div class="salto1px"></div>
<input id="saiu19clasesolicitud" name="saiu19clasesolicitud" type="hidden" value="<?php echo $_REQUEST['saiu19clasesolicitud']; ?>"/>
<label class="Label130">
<?php
echo $ETI['saiu19tiposolicitud'];
?>
</label>
<label>
<div id="div_saiu19tiposolicitud">
<?php
echo $html_saiu19tiposolicitud;
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu19temasolicitud'];
?>
</label>
<label>
<div id="div_saiu19temasolicitud">
<?php
echo $html_saiu19temasolicitud;
?>
</div>
<input id="saiu19temasolicitudorigen" name="saiu19temasolicitudorigen" type="hidden" value="<?php echo $_REQUEST['saiu19temasolicitudorigen']; ?>"/>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu19idescuela'];
?>
</label>
<label>
<?php
echo $html_saiu19idescuela;
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu19idprograma'];
?>
</label>
<label>
<div id="div_saiu19idprograma">
<?php
echo $html_saiu19idprograma;
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu19idperiodo'];
?>
</label>
<label>
<?php
echo $html_saiu19idperiodo;
?>
</label>
<div class="salto1px"></div>
<div class="GrupoCampos520">
<label class="txtAreaS">
<?php
echo $ETI['saiu19detalle'];
?>
<?php
$sInactivo='readonly disabled';
if ($bEditable){
	$sInactivo='';
} else {
?>
<input name="saiu19detalle" type="hidden" value="<?php echo $_REQUEST['saiu19detalle']; ?>" />
<?php
}
?>
<textarea id="saiu19detalle" name="saiu19detalle" placeholder="<?php echo $ETI['ing_campo'].$ETI['saiu19detalle']; ?>" <?php echo $sInactivo; ?>><?php echo $_REQUEST['saiu19detalle']; ?></textarea>
</label>
</div>
<div class="GrupoCampos300">
<div class="salto1px"></div>
<input id="saiu19idorigen" name="saiu19idorigen" type="hidden" value="<?php echo $_REQUEST['saiu19idorigen']; ?>" />
<input id="saiu19idarchivo" name="saiu19idarchivo" type="hidden" value="<?php echo $_REQUEST['saiu19idarchivo']; ?>" />
<div id="div_saiu19idarchivo" class="Campo220">
<?php
echo html_lnkarchivo((int)$_REQUEST['saiu19idorigen'], (int)$_REQUEST['saiu19idarchivo']);
?>
</div>
<?php
if ($_REQUEST['saiu19estado']==2) {
?>
<label class="Label30">
<input type="button" id="banexasaiu19idarchivo" name="banexasaiu19idarchivo" value="Anexar" class="btAnexarS" onclick="carga_saiu19idarchivo()" title="Cargar archivo" style="display:<?php if ((int)$_REQUEST['saiu19id'] != 0) {
echo 'block';
} else {
echo 'none';
} ?>;" />
</label>
<label class="Label30">
<input type="button" id="beliminasaiu19idarchivo" name="beliminasaiu19idarchivo" value="Eliminar" class="btBorrarS" onclick="eliminasaiu19idarchivo()" title="Eliminar archivo" style="display:<?php if ((int)$_REQUEST['saiu19idarchivo'] != 0) {
echo 'block';
} else {
echo 'none';
} ?>;" />
</label>
<?php
}
?>
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
<?php
if ($_REQUEST['saiu19estado']>0) {
	$sEstilo='display:none;';
	if ($_REQUEST['saiu19solucion']==1 || $_REQUEST['saiu19solucion']==3) {
		$sEstilo='display:block;';
	}
?>
<div id="div_saiu19respuesta" style="<?php echo $sEstilo; ?>">
<div class="GrupoCampos520">
<label class="txtAreaS">
<?php
echo $ETI['saiu19respuesta'];
?>
<?php
if ($_REQUEST['saiu19estado']==7) {
?>
<label class="Label220 ir_derecha">
<?php
if ($_REQUEST['saiu19solucion']==1) {
	$saiu19fecharesp = fecha_desdenumero($_REQUEST['saiu19fechafin']);
	$saiu19horaresp = html_HoraMin('saiu19horafin', $_REQUEST['saiu19horafin'], 'saiu19minutofin', $_REQUEST['saiu19minutofin'], true);
	echo $saiu19fecharesp . ' ' . $saiu19horaresp;
} else {
	$saiu19fecharespcaso = fecha_desdenumero($_REQUEST['saiu19fecharespcaso']);
	$saiu19horarespcaso = html_HoraMin('saiu19horarespcaso', $_REQUEST['saiu19horarespcaso'], 'saiu19minrespcaso', $_REQUEST['saiu19minrespcaso'], true);
	echo $saiu19fecharespcaso . ' ' . $saiu19horarespcaso;
}
?>
</label>
<?php
}
?>
<?php
$sInactivo='readonly disabled';
if ($bPuedeCerrar){$sInactivo='';}
?>
<textarea id="saiu19respuesta" name="saiu19respuesta" placeholder="<?php echo $ETI['ing_campo'].$ETI['saiu19respuesta']; ?>" <?php echo $sInactivo; ?>><?php echo $_REQUEST['saiu19respuesta']; ?></textarea>
</label>
</div>
<div class="GrupoCampos300">
<div class="salto1px"></div>
<input id="saiu19idorigenrta" name="saiu19idorigenrta" type="hidden" value="<?php echo $_REQUEST['saiu19idorigenrta']; ?>" />
<input id="saiu19idarchivorta" name="saiu19idarchivorta" type="hidden" value="<?php echo $_REQUEST['saiu19idarchivorta']; ?>" />
<div id="div_saiu19idarchivorta" class="Campo220">
<?php
echo html_lnkarchivo((int)$_REQUEST['saiu19idorigenrta'], (int)$_REQUEST['saiu19idarchivorta']);
?>
</div>
<?php
if ($_REQUEST['saiu19estado']>0 && $bPuedeCerrar) {
?>
<label class="Label30">
<input type="button" id="banexasaiu19idarchivorta" name="banexasaiu19idarchivorta" value="Anexar" class="btAnexarS" onclick="carga_saiu19idarchivorta()" title="Cargar archivo" style="display:<?php if ((int)$_REQUEST['saiu19id'] != 0) {
echo 'block';
} else {
echo 'none';
} ?>;" />
</label>
<label class="Label30">
<input type="button" id="beliminasaiu19idarchivorta" name="beliminasaiu19idarchivorta" value="Eliminar" class="btBorrarS" onclick="eliminasaiu19idarchivorta()" title="Eliminar archivo" style="display:<?php if ((int)$_REQUEST['saiu19idarchivorta'] != 0) {
echo 'block';
} else {
echo 'none';
} ?>;" />
</label>
<?php
}
?>
<div class="salto1px"></div>
</div>
</div>
<div class="salto1px"></div>
<div class="GrupoCampos450">
<label class="Label160">
<?php
echo $ETI['saiu19solucion'];
?>
</label>
<label class="Label130">
<?php
echo $html_saiu19solucion;
?>
</label>
<div class="salto1px"></div>
<?php
if (false) {
?>
<label class="Label200">
<?php
echo $ETI['saiu19paramercadeo'];
?>
</label>
<label class="Label130">
<?php
echo $html_saiu19paramercadeo;
?>
</label>
<div class="salto1px"></div>
<?php
} else {
?>
<input id="saiu19paramercadeo" name="saiu19paramercadeo" type="hidden" value="<?php echo $_REQUEST['saiu19paramercadeo']; ?>"/>
<?php
}
?>
<label class="Label160">
<?php
echo $ETI['saiu19idcaso'];
?>
</label>
<label class="Label130">
<?php
echo html_oculto('saiu19idcaso', $_REQUEST['saiu19idcaso']);
?>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['saiu19idpqrs'];
?>
</label>
<label class="Label160">
<?php
$html_saiu19idpqrs_cod='0';
if ($_REQUEST['saiu19idpqrs']!=0) {
	$html_saiu19idpqrs_cod=f3000_NumSolicitud($_REQUEST['saiu19agno'], $_REQUEST['saiu19mes'], $_REQUEST['saiu19idpqrs']);
}
echo html_oculto('saiu19idpqrs', $_REQUEST['saiu19idpqrs'],$html_saiu19idpqrs_cod);
if ($_REQUEST['saiu19numref']!='') {
	echo '<br><b>Ref. de consulta: <a href="saiusolcitudes.php?saiu05origenagno='.$_REQUEST['saiu19agno'].'&saiu05origenmes='.$_REQUEST['saiu19mes'].'&saiu05id='.$_REQUEST['saiu19idpqrs'].'&paso=3" target="_blank">' . $_REQUEST['saiu19numref'] . '</a></b>';
}
?>
</label>
<div class="salto1px"></div>
<?php
if ($_REQUEST['paso']==2){
	if ($_REQUEST['saiu19estado']<7 && $bPuedeCerrar){
?>
<label class="Label130">
<input id="cmdTermina" name="cmdTermina" type="button" value="<?php echo $ETI['saiu19cerrar']; ?>" class="BotonAzul160" onclick="enviacerrar()" title="<?php echo $ETI['saiu19cerrar']; ?>"/>
</label>
<?php
		}
	}
?>
<div class="salto1px"></div>
</div>
<?php
}
?>
<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo $ETI['saiu19idresponsable'];
?>
</label>
<div class="salto1px"></div>
<input id="saiu19idresponsable" name="saiu19idresponsable" type="hidden" value="<?php echo $_REQUEST['saiu19idresponsable']; ?>"/>
<div id="div_saiu19idresponsable_llaves">
<?php
$bOculto=true;
echo html_DivTerceroV2('saiu19idresponsable', $_REQUEST['saiu19idresponsable_td'], $_REQUEST['saiu19idresponsable_doc'], $bOculto, 0, $ETI['ing_doc']);
?>
</div>
<div class="salto1px"></div>
<div id="div_saiu19idresponsable" class="L"><?php echo $saiu19idresponsable_rs; ?></div>
<div class="salto1px"></div>
<label class="Label90">
<?php
echo $ETI['saiu19horafin'];
?>
</label>
<div class="campo_HoraMin" id="div_saiu19horafin">
<?php
$bOculto=False;
if ($_REQUEST['paso']!=0){$bOculto=True;}
echo html_HoraMin('saiu19horafin', $_REQUEST['saiu19horafin'], 'saiu19minutofin', $_REQUEST['saiu19minutofin'], $bOculto);
?>
<input id="saiu19fechafin" name="saiu19fechafin" type="hidden" value="<?php echo $_REQUEST['saiu19fechafin']; ?>"/>
</div>
<?php
if ($_REQUEST['saiu19estado']==1 || $_REQUEST['saiu19estado']==7){
?>
<div class="salto1px"></div>
<label class="L">
<?php
echo $ETI['saiu19tiemprespdias'].' <b>'.Tiempo_HTML($_REQUEST['saiu19tiemprespdias'], $_REQUEST['saiu19tiempresphoras'], $_REQUEST['saiu19tiemprespminutos']).'</b>';
?>
</label>
<?php
	}
?>
<div class="salto1px"></div>
</div>
<input id="saiu19tiemprespdias" name="saiu19tiemprespdias" type="hidden" value="<?php echo $_REQUEST['saiu19tiemprespdias']; ?>"/>
<input id="saiu19tiempresphoras" name="saiu19tiempresphoras" type="hidden" value="<?php echo $_REQUEST['saiu19tiempresphoras']; ?>"/>
<input id="saiu19tiemprespminutos" name="saiu19tiemprespminutos" type="hidden" value="<?php echo $_REQUEST['saiu19tiemprespminutos']; ?>"/>
<div class="salto1px"></div>
<?php
if (false){
if ($bConBotonAbandona){
?>
<label class="Label130">
<input id="cmdAbandona" name="cmdAbandona" type="button" value="Abandonada" class="BotonAzul" onclick="abandonar()" title="Llamada abandonada"/>
</label>
<label class="Label30"></label>
<?php
	}
if ($bConBotonCancela){
?>
<label class="Label130">
<input id="cmdCancela" name="cmdCancela" type="button" value="Cancelada" class="BotonAzul" onclick="cancelar()" title="Llamada cancelada"/>
</label>
<label class="Label30"></label>
<?php
	}
if ($_REQUEST['paso']==2){
	if ($_REQUEST['saiu19estado']<7){
?>
<label class="Label130">
<input id="cmdTermina" name="cmdTermina" type="button" value="Terminar" class="BotonAzul" onclick="enviacerrar()" title="Terminar Llamada"/>
</label>
<?php
		}
	}
}
?>
<?php
// Inicio caja - responsable caso
$sEstilo = ' style="display:none"';
if ((int)$_REQUEST['paso'] != 0) {
	if ($_REQUEST['saiu19solucion'] == 3) {
		$sEstilo = ' style="display:block"';
	}
}
?>
<div class="GrupoCampos520" <?php echo $sEstilo; ?>>
<label class="TituloGrupo">
<?php
echo $ETI['saiu19atiendecaso'];
?>
<?php
if ($_REQUEST['saiu19estado'] < 7) {
	if ($bPermisoSupv) {
?>
<div class="ir_derecha" style="width:62px;">
<input id="bRevAtiende" name="bRevAtiende" type="button" value="<?php echo $ETI['saiu19actatiendecaso']; ?>" class="btMiniActualizar" onclick="actualizaratiende()" title="<?php echo $ETI['saiu19actatiendecaso']; ?>" style="display:<?php if ((int)$_REQUEST['saiu19id'] != 0) {
echo 'block';
} else {
echo 'none';
} ?>;" />
</div>
<?php
	}
}
?>
</label>
<div class="salto1px"></div>
<input id="saiu19idsupervisorcaso" name="saiu19idsupervisorcaso" type="hidden" value="<?php echo $_REQUEST['saiu19idsupervisorcaso']; ?>" />
<input id="saiu19idresponsablecaso" name="saiu19idresponsablecaso" type="hidden" value="<?php echo $_REQUEST['saiu19idresponsablecaso']; ?>" />
<label class="Label160">
<?php echo $ETI['saiu19idunidadcaso']; ?>
</label>
<label class="Label200">
<b><?php echo $html_saiu19idunidadcaso; ?></b>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php echo $ETI['saiu19idequipocaso']; ?>
</label>
<label class="Label200">
<b><?php echo $html_saiu19idequipocaso; ?></b>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php echo $ETI['saiu19idsupervisorcaso']; ?>
</label>
<label class="Label200">
<b><?php echo $saiu19idsupervisorcaso_rs; ?></b>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php echo $ETI['saiu19idresponsablecaso']; ?>
</label>
<label id="lbl_f3019CambioResponsable" class="Label200">
<b><?php echo $saiu19idresponsablecaso_rs; ?></b>
</label>
<div class="salto1px"></div>
<?php
if ($_REQUEST['saiu19estado'] < 7) {
	if ($bPermisoSupv) {
?>
<label class="Label160">
<input id="cmdReasignar" name="cmdReasignar" type="button" class="BotonAzul200" value="<?php echo $ETI['saiu19reasignacaso']; ?>" onclick="expandesector(2);" title="<?php echo $ETI['saiu19reasignacaso']; ?>" />
</label>
<div class="salto1px"></div>
<?php
	}
}
?>
</div>
<?php
// Fin caja - responsable caso
?>
<?php
if (false) {
	//Ejemplo de boton de ayuda
	//echo html_BotonAyuda('NombreCampo');
	//echo html_DivAyudaLocal('NombreCampo');
}
if ($bConExpande) {
	//Este es el cierre del div_p3019
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
echo '<h3>'.$ETI['bloque1'].'</h3>';
?>
</div>
<div class="areatrabajo">
<div class="ir_derecha">
<label class="Label90">
<?php
echo $ETI['msg_bdoc'];
?>
</label>
<label class="Label200">
<input id="bdoc" name="bdoc" type="text" value="<?php echo $_REQUEST['bdoc']; ?>" onchange="paginarf3019()" autocomplete="off" />
</label>
<label class="Label90">
<?php
echo $ETI['msg_bnombre'];
?>
</label>
<label class="Label200">
<input id="bnombre" name="bnombre" type="text" value="<?php echo $_REQUEST['bnombre']; ?>" onchange="paginarf3019()" autocomplete="off" />
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu19tiposolicitud'];
?>
</label>
<label class="Label350">
<?php
echo $html_bcategoria;
?>
</label>
<div class="salto1px"></div>
<label class="Label90">
<?php
echo $ETI['saiu19temasolicitud'];
?>
</label>
<label class="Label350">
<div id="div_btema">
<?php
echo $html_btema;
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label60">
<?php
echo $ETI['msg_blistar'];
?>
</label>
<label class="Label200">
<?php
echo $html_blistar;
?>
</label>
<label class="Label60">
<?php
echo $ETI['saiu19estado'];
?>
</label>
<label class="Label130">
<?php
echo $html_bestado;
?>
</label>
<label class="Label60">
<?php
echo $ETI['saiu19agno'];
?>
</label>
<label class="Label130">
<?php
echo $html_bagno;
?>
</label>
</div>
<div class="salto1px"></div>
<?php
echo ' '.$csv_separa;
?>
<div id="div_f3019detalle">
<?php
echo $sTabla3019;
?>
</div>
</div><!-- /div_areatrabajo -->
</div><!-- /DIV_areaform -->
</div>


<div id="div_sector2" style="display:none">
<?php
// if ($bBloqueTitulo) {
?>
<div class="titulos">
<div class="titulosD">
<input id="cmdAyuda2" name="cmdAyuda2" type="button" class="btSupAyuda" onclick="muestraayuda(<?php echo $iCodModulo; ?>);" title="<?php echo $ETI['bt_ayuda']; ?>" value="<?php echo $ETI['bt_ayuda']; ?>" />
<input id="cmdVolverSec2" name="cmdVolverSec2" type="button" class="btSupVolver" onclick="expandesector(1);" title="<?php echo $ETI['bt_volver']; ?>" value="<?php echo $ETI['bt_volver']; ?>" />
</div>
<div class="titulosI">
<?php
echo '<h2>' . $ETI['titulo_sector2_reasigna'] . '</h2>';
?>
</div>
</div>
<?php
// }
?>
<div class="areaform">
<div class="areatrabajo">
<?php
if ($_REQUEST['saiu19estado'] < 7) {
if ($idTercero == $_REQUEST['saiu19idsupervisorcaso'] || $seg_1707) {
	$sEstiloDiv = ' style="display:block;"';
} else {
	$sEstiloDiv = ' style="display:none;"';
}
}
?>
<div class="GrupoCampos520" <?php echo $sEstiloDiv; ?>>
<label class="TituloGrupo">
<?php
echo $ETI['saiu19atiendecaso'];
?>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php echo $ETI['saiu19idunidadcaso']; ?>
</label>
<label class="Label200">
<b><?php echo $html_saiu19idunidadcaso; ?></b>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php echo $ETI['saiu19idequipocaso']; ?>
</label>
<label class="Label200">
<b><?php echo $html_saiu19idequipocaso; ?></b>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php echo $ETI['saiu19idsupervisorcaso']; ?>
</label>
<label class="Label200">
<b><?php echo $saiu19idsupervisorcaso_rs; ?></b>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php echo $ETI['saiu19idresponsablecaso']; ?>
</label>
<label class="Label200">
<div id="div_saiu19idresponsablecaso">
<?php 
echo $html_saiu19idresponsablecasocombo; 
?>
</div>
</label>
<?php
if ($_REQUEST['saiu19estado'] < 7) {
if ($idTercero == $_REQUEST['saiu19idsupervisorcaso'] || $seg_1707) {
?>
<div class="salto1px"></div>
<label class="Label160">
<input id="cmdGuardarR" name="cmdGuardarR" type="button" class="BotonAzul200" value="<?php echo $ETI['guarda_reasigna']; ?>" onclick="enviareasignar();" title="<?php echo $ETI['guarda_reasigna']; ?>" />
</label>
<?php
}
}
?>
<div class="salto1px"></div>
</div>
</div>
</div>
<?php
// Termina el div_sector2
?>
</div>


<div id="div_sector93" style="display:none">
<?php
$objForma=new clsHtmlForma($iPiel);
$objForma->addBoton('cmdAyuda93', 'btSupAyuda', 'muestraayuda('.$iCodModulo.');', $ETI['bt_ayuda']);
$objForma->addBoton('cmdVolverSec93', 'btSupVolver', 'expandesector(1);', $ETI['bt_volver']);
echo $objForma->htmlTitulo(''.$ETI['titulo_sector93'].'', $iCodModulo);
echo $objForma->htmlInicioMarco();
?>
<label class="Label160">
<?php
echo $ETI['msg_saiu19consec'];
?>
</label>
<label class="Label90">
<?php
echo '<b>'.$_REQUEST['saiu19consec'].'</b>';
?>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['msg_saiu19consec_nuevo'];
// onchange="RevisaConsec()"
?>
</label>
<label class="Label90">
<input id="saiu19consec_nuevo" name="saiu19consec_nuevo" type="text" value="<?php echo $_REQUEST['saiu19consec_nuevo']; ?>" class="cuatro"/>
</label>
<div class="salto1px"></div>
<label class="Label160">&nbsp;</label>
<?php
echo $objForma->htmlBotonSolo('cmdCambiaConsecFinal', 'botonProceso', 'mod_consec();', $ETI['bt_cambiar'], 130);
echo $objForma->htmlFinMarco();
?>
</div><!-- /DIV_Sector93 -->
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
<input id="titulo_3019" name="titulo_3019" type="hidden" value="<?php echo $sTituloModulo; ?>" />
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
if ($bBloqueTitulo) {
	if ($bPuedeGuardar) {
		if ($_REQUEST['saiu19estado']<7) {
?>
<div class="flotante">
<input id="cmdGuardarf" name="cmdGuardarf" type="button" class="btSoloGuardar" onClick="enviaguardar();" value="<?php echo $ETI['bt_guardar']; ?>" />
</div>
<?php
		}
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
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>js/jquery.autocomplete.css" type="text/css"/>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>js/jquery.autocomplete.js"></script>
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>js/chosen.css" type="text/css"/>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>js/chosen.jquery.js"></script>
<script language="javascript">
document.getElementById("tab_inicial").click();
$().ready(function(){
<?php
if ($bEditable || $bPermisoSupv) {
?>
$("#saiu19idcentro").chosen({width:"100%"});
$("#saiu19coddepto").chosen({width:"100%"});
$("#saiu19codciudad").chosen({width:"100%"});
<?php
if ($bEnProceso){
?>
$("#saiu19tiposolicitud").chosen({width:"100%"});
<?php
	}
?>
$("#saiu19temasolicitud").chosen({width:"100%"});
$("#saiu19idprograma").chosen({width:"100%"});
$("#saiu19idperiodo").chosen({width:"100%"});
<?php
}
?>
$("#bcategoria").chosen({width:"100%"});
$("#btema").chosen({width:"100%"});
});
</script>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>jsi/js3000.js"></script>
<script language="javascript" src="ac_3019.js"></script>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>unad_todas2024v2.js"></script>
<?php
forma_piedepagina();

