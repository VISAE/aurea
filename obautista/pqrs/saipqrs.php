<?php
/*
--- © Omar Augusto Bautista Mora - UNAD - 2022 ---
--- omar.bautista@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 1.0 miércoles, 16 de noviembre de 2022
*/

if (file_exists('./err_control.php')) {
	require './err_control.php';
}
$bDebug = false;
$sDebug = '';
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
if (isset($_SESSION['unad_id_tercero']) == 0) {
	$_SESSION['unad_id_tercero']=0;
}
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
require $APP->rutacomun . 'libaurea.php';
require $APP->rutacomun . 'libmail.php';
if (($bPeticionXAJAX) && ($_SESSION['unad_id_tercero'] == 0)) {
	// viene por xajax.
	$xajax = new xajax();
	$xajax->configure('javascript URI', $APP->rutacomun . 'xajax/');
	$xajax->register(XAJAX_FUNCTION, 'sesion_abandona_V2');
	$xajax->processRequest();
	die();
}
$grupo_id = 1; //Necesita ajustarlo...
$iCodModulo = 3005;
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
$mensajes_3005 = $APP->rutacomun . 'lg/lg_3005_' . $_SESSION['unad_idioma'] . '.php';
if (!file_exists($mensajes_3005)) {
	$mensajes_3005 = $APP->rutacomun . 'lg/lg_3005_es.php';
}
require $mensajes_todas;
require $mensajes_3005;
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
	$bDevuelve = true;
	if ($_SESSION['unad_id_tercero'] == 0) {
		$bDevuelve = false;
	}
	if (!$bDevuelve) {
		$bCerrado = true;
		$sMsgCierre = '<div class="MarquesinaGrande">No cuenta con permiso para acceder a este modulo [' . $iCodModulo . '].</div>';
	}
}
if ($bCerrado) {
	$objDB->CerrarConexion();
	if ($_SESSION['unad_id_tercero'] == 1) {
		$_SESSION['unad_id_tercero'] = 0;
	}
	require $APP->rutacomun . 'unad_forma_v2.php';
	forma_cabeceraV3($xajax, $ETI['titulo_3005']);
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
$idTercero = $_SESSION['unad_id_tercero'];
$bOtroUsuario = false;
if (isset($_REQUEST['deb_tipodoc']) == 0) {
	$_REQUEST['deb_tipodoc'] = $APP->tipo_doc;
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
$mensajes_3000 = $APP->rutacomun . 'lg/lg_3000_' . $_SESSION['unad_idioma'] . '.php';
if (!file_exists($mensajes_3000)) {
	$mensajes_3000 = $APP->rutacomun . 'lg/lg_3000_es.php';
}
$mensajes_3006 = $APP->rutacomun . 'lg/lg_3006_' . $_SESSION['unad_idioma'] . '.php';
if (!file_exists($mensajes_3006)) {
	$mensajes_3006 = $APP->rutacomun . 'lg/lg_3006_es.php';
}
$mensajes_3007 = $APP->rutacomun . 'lg/lg_3007_' . $_SESSION['unad_idioma'] . '.php';
if (!file_exists($mensajes_3007)) {
	$mensajes_3007 = $APP->rutacomun . 'lg/lg_3007_es.php';
}
require $mensajes_3000;
require $mensajes_3006;
require $mensajes_3007;
// -- Si esta cargando la pagina por primer vez se manda a hacer un limpiar (paso -1)
if (isset($_REQUEST['paso']) == 0) {
	$_REQUEST['paso'] = -1;
}
// -- 3005 saiu05solicitud
require $APP->rutacomun . 'lib3005.php';
require 'lib3005_externa.php';
// -- 3000 Historial de solicitudes
require $APP->rutacomun . 'lib3000.php';
// -- 3006 Anotaciones
require $APP->rutacomun . 'lib3006.php';
// -- 3007 Anexos
require $APP->rutacomun . 'lib3007.php';
$xajax = new xajax();
$xajax->configure('javascript URI', $APP->rutacomun . 'xajax/');
$xajax->register(XAJAX_FUNCTION, 'unad11_Mostrar_v2');
$xajax->register(XAJAX_FUNCTION, 'unad11_Mostrar_v2SAI');
$xajax->register(XAJAX_FUNCTION, 'unad11_TraerXid');
$xajax->register(XAJAX_FUNCTION, 'unad11_TraerXidSAI');
$xajax->register(XAJAX_FUNCTION, 'formatear_moneda');
$xajax->register(XAJAX_FUNCTION, 'f3005_Combosaiu05idtemaorigen');
$xajax->register(XAJAX_FUNCTION, 'f3005_Combosaiu05idequiporesp');
$xajax->register(XAJAX_FUNCTION, 'sesion_abandona_V2');
$xajax->register(XAJAX_FUNCTION, 'sesion_mantenerV4');
$xajax->register(XAJAX_FUNCTION, 'f3005_HtmlTabla');
$xajax->register(XAJAX_FUNCTION, 'f3005_ExisteDato');
$xajax->register(XAJAX_FUNCTION, 'f3005_Busquedas');
$xajax->register(XAJAX_FUNCTION, 'f3005_HtmlBusqueda');
$xajax->register(XAJAX_FUNCTION, 'f3000_HtmlTabla');
$xajax->register(XAJAX_FUNCTION, 'elimina_archivo_saiu06idarchivo');
$xajax->register(XAJAX_FUNCTION, 'f3006_Guardar');
$xajax->register(XAJAX_FUNCTION, 'f3006_Traer');
$xajax->register(XAJAX_FUNCTION, 'f3006_Eliminar');
$xajax->register(XAJAX_FUNCTION, 'f3006_HtmlTabla');
$xajax->register(XAJAX_FUNCTION, 'f3006_PintarLlaves');
$xajax->register(XAJAX_FUNCTION, 'elimina_archivo_saiu07idarchivo');
$xajax->register(XAJAX_FUNCTION, 'f3007_Guardar');
$xajax->register(XAJAX_FUNCTION, 'f3007_Traer');
$xajax->register(XAJAX_FUNCTION, 'f3007_Eliminar');
$xajax->register(XAJAX_FUNCTION, 'f3007_HtmlTabla');
$xajax->register(XAJAX_FUNCTION, 'f3007_PintarLlaves');
$xajax->register(XAJAX_FUNCTION, 'f236_TraerInfoPersonal');
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
if (isset($_REQUEST['paginaf3000']) == 0) {
	$_REQUEST['paginaf3000'] = 1;
}
if (isset($_REQUEST['lppf3000']) == 0) {
	$_REQUEST['lppf3000'] = 10;
}
if (isset($_REQUEST['boculta3000']) == 0) {
	$_REQUEST['boculta3000'] = 0;
}
if (isset($_REQUEST['paginaf3005']) == 0) {
	$_REQUEST['paginaf3005'] = 1;
}
if (isset($_REQUEST['lppf3005']) == 0) {
	$_REQUEST['lppf3005'] = 20;
}
if (isset($_REQUEST['boculta3005']) == 0) {
	$_REQUEST['boculta3005'] = 1;
}
if (isset($_REQUEST['boculta3005_0']) == 0) {
	$_REQUEST['boculta3005_0'] = 0;
}
if (isset($_REQUEST['paginaf3006']) == 0) {
	$_REQUEST['paginaf3006'] = 1;
}
if (isset($_REQUEST['lppf3006']) == 0) {
	$_REQUEST['lppf3006'] = 20;
}
if (isset($_REQUEST['boculta3006']) == 0) {
	$_REQUEST['boculta3006'] = 0;
}
if (isset($_REQUEST['boculta3006_Campos']) == 0) {
	$_REQUEST['boculta3006_Campos'] = 1;
}
if (isset($_REQUEST['paginaf3007']) == 0) {
	$_REQUEST['paginaf3007'] = 1;
}
if (isset($_REQUEST['lppf3007']) == 0) {
	$_REQUEST['lppf3007'] = 20;
}
if (isset($_REQUEST['boculta3007']) == 0) {
	$_REQUEST['boculta3007'] = 0;
}
// -- Inicializar variables de datos.
if (isset($_REQUEST['saiu05agno']) == 0) {
	$_REQUEST['saiu05agno'] = 0;
}
if (isset($_REQUEST['saiu05mes']) == 0) {
	$_REQUEST['saiu05mes'] = 0;
}
if (isset($_REQUEST['saiu05tiporadicado']) == 0) {
	$_REQUEST['saiu05tiporadicado'] = 1;
}
if (isset($_REQUEST['saiu05consec']) == 0) {
	$_REQUEST['saiu05consec'] = '';
}
if (isset($_REQUEST['saiu05consec_nuevo']) == 0) {
	$_REQUEST['saiu05consec_nuevo'] = '';
}
if (isset($_REQUEST['saiu05id']) == 0) {
	$_REQUEST['saiu05id'] = '';
}
if (isset($_REQUEST['saiu05origenagno']) == 0) {
	$_REQUEST['saiu05origenagno'] = '';
}
if (isset($_REQUEST['saiu05origenmes']) == 0) {
	$_REQUEST['saiu05origenmes'] = '';
}
if (isset($_REQUEST['saiu05origenid']) == 0) {
	$_REQUEST['saiu05origenid'] = 0;
}
if (isset($_REQUEST['saiu05dia']) == 0) {
	$_REQUEST['saiu05dia'] = '';
	//$_REQUEST['saiu05dia'] = $iHoy;
}
if (isset($_REQUEST['saiu05hora']) == 0) {
	$_REQUEST['saiu05hora'] = fecha_hora();
}
if (isset($_REQUEST['saiu05minuto']) == 0) {
	$_REQUEST['saiu05minuto'] = fecha_minuto();
}
if (isset($_REQUEST['saiu05estado']) == 0) {
	$_REQUEST['saiu05estado'] = -1;
}
if (isset($_REQUEST['saiu05idmedio']) == 0) {
	$_REQUEST['saiu05idmedio'] = 0;
}
if (isset($_REQUEST['saiu05idtiposolorigen']) == 0) {
	$_REQUEST['saiu05idtiposolorigen'] = '';
}
if (isset($_REQUEST['saiu05idtemaorigen']) == 0) {
	$_REQUEST['saiu05idtemaorigen'] = '';
}
if (isset($_REQUEST['saiu05idtemafin']) == 0) {
	$_REQUEST['saiu05idtemafin'] = '';
}
if (isset($_REQUEST['saiu05idtiposolfin']) == 0) {
	$_REQUEST['saiu05idtiposolfin'] = '';
}
if (isset($_REQUEST['saiu05idsolicitante']) == 0) {
	$_REQUEST['saiu05idsolicitante'] = $_SESSION['unad_id_tercero'];
}
if (isset($_REQUEST['saiu05idsolicitante_td']) == 0) {
	$_REQUEST['saiu05idsolicitante_td'] = $APP->tipo_doc;
}
if (isset($_REQUEST['saiu05idsolicitante_doc']) == 0) {
	$_REQUEST['saiu05idsolicitante_doc'] = '';
}
if (isset($_REQUEST['saiu05idinteresado']) == 0) {
	$_REQUEST['saiu05idinteresado'] = 0;
	//$_REQUEST['saiu05idinteresado'] = $_SESSION['unad_id_tercero'];
}
if (isset($_REQUEST['saiu05idinteresado_td']) == 0) {
	$_REQUEST['saiu05idinteresado_td'] = $APP->tipo_doc;
}
if (isset($_REQUEST['saiu05idinteresado_doc']) == 0) {
	$_REQUEST['saiu05idinteresado_doc'] = '';
}
if (isset($_REQUEST['saiu05tipointeresado']) == 0) {
	$_REQUEST['saiu05tipointeresado'] = 6;
}
if (isset($_REQUEST['saiu05rptaforma']) == 0) {
	$_REQUEST['saiu05rptaforma'] = 0;
}
if (isset($_REQUEST['saiu05rptacorreo']) == 0) {
	$_REQUEST['saiu05rptacorreo'] = '';
}
if (isset($_REQUEST['saiu05rptadireccion']) == 0) {
	$_REQUEST['saiu05rptadireccion'] = '';
}
if (isset($_REQUEST['saiu05costogenera']) == 0) {
	$_REQUEST['saiu05costogenera'] = 0;
}
if (isset($_REQUEST['saiu05costovalor']) == 0) {
	$_REQUEST['saiu05costovalor'] = 0;
}
if (isset($_REQUEST['saiu05costorefpago']) == 0) {
	$_REQUEST['saiu05costorefpago'] = '';
}
if (isset($_REQUEST['saiu05prioridad']) == 0) {
	$_REQUEST['saiu05prioridad'] = '';
}
if (isset($_REQUEST['saiu05idzona']) == 0) {
	$_REQUEST['saiu05idzona'] = '';
}
if (isset($_REQUEST['saiu05cead']) == 0) {
	$_REQUEST['saiu05cead'] = '';
}
if (isset($_REQUEST['saiu05refdoc']) == 0) {
	$_REQUEST['saiu05refdoc'] = '';
}
if (isset($_REQUEST['saiu05numref']) == 0) {
	$_REQUEST['saiu05numref'] = '';
}
if (isset($_REQUEST['saiu05detalle']) == 0) {
	$_REQUEST['saiu05detalle'] = '';
}
if (isset($_REQUEST['saiu05infocomplemento']) == 0) {
	$_REQUEST['saiu05infocomplemento'] = '';
}
if (isset($_REQUEST['saiu05idunidadresp']) == 0) {
	$_REQUEST['saiu05idunidadresp'] = 0;
}
if (isset($_REQUEST['saiu05idequiporesp']) == 0) {
	$_REQUEST['saiu05idequiporesp'] = 0;
}
if (isset($_REQUEST['saiu05idsupervisor']) == 0) {
	$_REQUEST['saiu05idsupervisor'] = 0;
	//$_REQUEST['saiu05idsupervisor'] = $_SESSION['unad_id_tercero'];
}
if (isset($_REQUEST['saiu05idsupervisor_td']) == 0) {
	$_REQUEST['saiu05idsupervisor_td'] = $APP->tipo_doc;
}
if (isset($_REQUEST['saiu05idsupervisor_doc']) == 0) {
	$_REQUEST['saiu05idsupervisor_doc'] = '';
}
if (isset($_REQUEST['saiu05idresponsable']) == 0) {
	$_REQUEST['saiu05idresponsable'] = 0;
	//$_REQUEST['saiu05idresponsable'] = $_SESSION['unad_id_tercero'];
}
if (isset($_REQUEST['saiu05idresponsable_td']) == 0) {
	$_REQUEST['saiu05idresponsable_td'] = $APP->tipo_doc;
}
if (isset($_REQUEST['saiu05idresponsable_doc']) == 0) {
	$_REQUEST['saiu05idresponsable_doc'] = '';
}
if (isset($_REQUEST['saiu05idescuela']) == 0) {
	$_REQUEST['saiu05idescuela'] = '';
}
if (isset($_REQUEST['saiu05idprograma']) == 0) {
	$_REQUEST['saiu05idprograma'] = '';
}
if (isset($_REQUEST['saiu05idperiodo']) == 0) {
	$_REQUEST['saiu05idperiodo'] = '';
}
if (isset($_REQUEST['saiu05idcurso']) == 0) {
	$_REQUEST['saiu05idcurso'] = '';
}
if (isset($_REQUEST['saiu05idgrupo']) == 0) {
	$_REQUEST['saiu05idgrupo'] = '';
}
if (isset($_REQUEST['saiu05tiemprespdias']) == 0) {
	$_REQUEST['saiu05tiemprespdias'] = '';
}
if (isset($_REQUEST['saiu05tiempresphoras']) == 0) {
	$_REQUEST['saiu05tiempresphoras'] = '';
}
if (isset($_REQUEST['saiu05fecharespprob']) == 0) {
	$_REQUEST['saiu05fecharespprob'] = '';
	//$_REQUEST['saiu05fecharespprob'] = fecha_hoy();
}
if (isset($_REQUEST['saiu05respuesta']) == 0) {
	$_REQUEST['saiu05respuesta'] = '';
}
if (isset($_REQUEST['saiu05fecharespdef']) == 0) {
	$_REQUEST['saiu05fecharespdef'] = '';
	//$_REQUEST['saiu05fecharespdef'] = fecha_hoy();
}
if (isset($_REQUEST['saiu05horarespdef']) == 0) {
	$_REQUEST['saiu05horarespdef'] = fecha_hora();
}
if (isset($_REQUEST['saiu05minrespdef']) == 0) {
	$_REQUEST['saiu05minrespdef'] = fecha_minuto();
}
if (isset($_REQUEST['saiu05diasproc']) == 0) {
	$_REQUEST['saiu05diasproc'] = 0;
}
if (isset($_REQUEST['saiu05minproc']) == 0) {
	$_REQUEST['saiu05minproc'] = 0;
}
if (isset($_REQUEST['saiu05diashabproc']) == 0) {
	$_REQUEST['saiu05diashabproc'] = 0;
}
if (isset($_REQUEST['saiu05minhabproc']) == 0) {
	$_REQUEST['saiu05minhabproc'] = 0;
}
if (isset($_REQUEST['saiu05idmoduloproc']) == 0) {
	$_REQUEST['saiu05idmoduloproc'] = '';
}
if (isset($_REQUEST['saiu05identificadormod']) == 0) {
	$_REQUEST['saiu05identificadormod'] = '';
}
if (isset($_REQUEST['saiu05numradicado']) == 0) {
	$_REQUEST['saiu05numradicado'] = '';
}
if (isset($_REQUEST['saiu05evalacepta']) == 0) {
	$_REQUEST['saiu05evalacepta'] = 0;
}
if (isset($_REQUEST['saiu05evalfecha']) == 0) {
	$_REQUEST['saiu05evalfecha'] = '';
	//$_REQUEST['saiu05evalfecha'] = fecha_hoy();
}
if (isset($_REQUEST['saiu05evalamabilidad']) == 0) {
	$_REQUEST['saiu05evalamabilidad'] = 0;
}
if (isset($_REQUEST['saiu05evalamabmotivo']) == 0) {
	$_REQUEST['saiu05evalamabmotivo'] = '';
}
if (isset($_REQUEST['saiu05evalrapidez']) == 0) {
	$_REQUEST['saiu05evalrapidez'] = 0;
}
if (isset($_REQUEST['saiu05evalrapidmotivo']) == 0) {
	$_REQUEST['saiu05evalrapidmotivo'] = '';
}
if (isset($_REQUEST['saiu05evalclaridad']) == 0) {
	$_REQUEST['saiu05evalclaridad'] = 0;
}
if (isset($_REQUEST['saiu05evalcalridmotivo']) == 0) {
	$_REQUEST['saiu05evalcalridmotivo'] = '';
}
if (isset($_REQUEST['saiu05evalresolvio']) == 0) {
	$_REQUEST['saiu05evalresolvio'] = 0;
}
if (isset($_REQUEST['saiu05evalsugerencias']) == 0) {
	$_REQUEST['saiu05evalsugerencias'] = '';
}
if (isset($_REQUEST['saiu05idcategoria']) == 0) {
	$_REQUEST['saiu05idcategoria'] = '';
}
if ((int)$_REQUEST['paso'] > 0) {
	//Anotaciones
	if (isset($_REQUEST['saiu06consec']) == 0) {
		$_REQUEST['saiu06consec'] = '';
	}
	if (isset($_REQUEST['saiu06id']) == 0) {
		$_REQUEST['saiu06id'] = '';
	}
	if (isset($_REQUEST['saiu06anotacion']) == 0) {
		$_REQUEST['saiu06anotacion'] = '';
	}
	if (isset($_REQUEST['saiu06visible']) == 0) {
		$_REQUEST['saiu06visible'] = '';
	}
	if (isset($_REQUEST['saiu06descartada']) == 0) {
		$_REQUEST['saiu06descartada'] = '';
	}
	if (isset($_REQUEST['saiu06idorigen']) == 0) {
		$_REQUEST['saiu06idorigen'] = 0;
	}
	if (isset($_REQUEST['saiu06idarchivo']) == 0) {
		$_REQUEST['saiu06idarchivo'] = 0;
	}
	if (isset($_REQUEST['saiu06idusuario']) == 0) {
		$_REQUEST['saiu06idusuario'] = $idTercero;
	}
	if (isset($_REQUEST['saiu06idusuario_td']) == 0) {
		$_REQUEST['saiu06idusuario_td'] = $APP->tipo_doc;
	}
	if (isset($_REQUEST['saiu06idusuario_doc']) == 0) {
		$_REQUEST['saiu06idusuario_doc'] = '';
	}
	if (isset($_REQUEST['saiu06fecha']) == 0) {
		$_REQUEST['saiu06fecha'] = fecha_hoy();
		//$_REQUEST['saiu06fecha'] = $iHoy;
	}
	if (isset($_REQUEST['saiu06hora']) == 0) {
		$_REQUEST['saiu06hora'] = fecha_hora();
	}
	if (isset($_REQUEST['saiu06minuto']) == 0) {
		$_REQUEST['saiu06minuto'] = fecha_minuto();
	}
	//Anexos
	if (isset($_REQUEST['saiu07consec']) == 0) {
		$_REQUEST['saiu07consec'] = '';
	}
	if (isset($_REQUEST['saiu07id']) == 0) {
		$_REQUEST['saiu07id'] = '';
	}
	if (isset($_REQUEST['saiu07idtipoanexo']) == 0) {
		$_REQUEST['saiu07idtipoanexo'] = '';
	}
	if (isset($_REQUEST['saiu07detalle']) == 0) {
		$_REQUEST['saiu07detalle'] = '';
	}
	if (isset($_REQUEST['saiu07idorigen']) == 0) {
		$_REQUEST['saiu07idorigen'] = 0;
	}
	if (isset($_REQUEST['saiu07idarchivo']) == 0) {
		$_REQUEST['saiu07idarchivo'] = 0;
	}
	if (isset($_REQUEST['saiu07idusuario']) == 0) {
		$_REQUEST['saiu07idusuario'] = 0;
	} //{$_SESSION['unad_id_tercero'];}
	if (isset($_REQUEST['saiu07idusuario_td']) == 0) {
		$_REQUEST['saiu07idusuario_td'] = $APP->tipo_doc;
	}
	if (isset($_REQUEST['saiu07idusuario_doc']) == 0) {
		$_REQUEST['saiu07idusuario_doc'] = '';
	}
	if (isset($_REQUEST['saiu07fecha']) == 0) {
		$_REQUEST['saiu07fecha'] = '';
		//$_REQUEST['saiu07fecha'] = $iHoy;
	} //{fecha_hoy();}
	if (isset($_REQUEST['saiu07hora']) == 0) {
		$_REQUEST['saiu07hora'] = '';
	}
	if (isset($_REQUEST['saiu07minuto']) == 0) {
		$_REQUEST['saiu07minuto'] = '';
	}
	if (isset($_REQUEST['saiu07estado']) == 0) {
		$_REQUEST['saiu07estado'] = '';
	}
	if (isset($_REQUEST['saiu07idvalidad']) == 0) {
		$_REQUEST['saiu07idvalidad'] = 0;
	} //{$_SESSION['unad_id_tercero'];}
	if (isset($_REQUEST['saiu07idvalidad_td']) == 0) {
		$_REQUEST['saiu07idvalidad_td'] = $APP->tipo_doc;
	}
	if (isset($_REQUEST['saiu07idvalidad_doc']) == 0) {
		$_REQUEST['saiu07idvalidad_doc'] = '';
	}
	if (isset($_REQUEST['saiu07fechavalida']) == 0) {
		$_REQUEST['saiu07fechavalida'] = '';
		//$_REQUEST['saiu07fechavalida'] = $iHoy;
	} //{fecha_hoy();}
	if (isset($_REQUEST['saiu07horavalida']) == 0) {
		$_REQUEST['saiu07horavalida'] = '';
	}
	if (isset($_REQUEST['saiu07minvalida']) == 0) {
		$_REQUEST['saiu07minvalida'] = '';
	}
}
// Espacio para inicializar otras variables
if (isset($_REQUEST['csv_separa']) == 0) {
	$_REQUEST['csv_separa'] = ';';
}
if (isset($_REQUEST['bnombre']) == 0) {
	$_REQUEST['bnombre'] = '';
}
if (isset($_REQUEST['blistar']) == 0) {
	$_REQUEST['blistar'] = fecha_agno();
}
if (isset($_REQUEST['bMuestraExistentes']) == 0) {
	$_REQUEST['bMuestraExistentes'] = false;
}
if (isset($_REQUEST['sTabla3005']) == 0) {
	$_REQUEST['sTabla3005'] = '';
}
if (isset($_REQUEST['opcion']) == 0) {
	$_REQUEST['opcion'] = 0;
}
if (isset($_REQUEST['aceptaterminos']) == 0) {
	$objDB->CerrarConexion();
	require $APP->rutacomun . 'unad_forma_v2.php';
	forma_cabeceraV3($xajax, $ETI['titulo_3005']);
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
	<form id="frmterminos" name="frmterminos" method="post" action="" autocomplete="off">
	<h1 class="TituloAzul1"><?php echo $ETI['bt_tratadatos']; ?></h1>
	<div class="GrupoCampos700 container float-none mx-auto">
	<p><?php echo $ETI['msg_tratadatos']; ?></p>
	</div>
	<div class="salto1px"></div>
	<input id="aceptaterminos" name="aceptaterminos" type="hidden" value="1" />
	<input id="cmdAceptar" name="cmdAceptar" type="submit" value="Aceptar y continuar" class="BotonAzul200">
	</form>
	<?php
	if ($bDebug) {
		echo $sDebug;
	}
	forma_piedepagina();
	die();
}
if ((int)$_REQUEST['paso'] > 0) {
	//Anotaciones
	if (isset($_REQUEST['bnombre3006']) == 0) {
		$_REQUEST['bnombre3006'] = '';
	}
	//if (isset($_REQUEST['blistar3006'])==0){$_REQUEST['blistar3006']='';}
	//Anexos
	if (isset($_REQUEST['bnombre3007']) == 0) {
		$_REQUEST['bnombre3007'] = '';
	}
	//if (isset($_REQUEST['blistar3007'])==0){$_REQUEST['blistar3007']='';}
}
$sTabla3005 = html_entity_decode($_REQUEST['sTabla3005'], ENT_QUOTES);
if (($_REQUEST['paso'] == 14)) {
	$aRefDoc = explode('-', $_REQUEST['saiu05refdoc']);
	if (count($aRefDoc) < 3) {
		$sSQL = 'SELECT unad11id FROM unad11terceros WHERE unad11doc="' . $_REQUEST['saiu05refdoc'] . '"';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			$idTercero = $fila['unad11id'];						
			$sTabla3005 = '<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">
			<tr class="fondoazul">
			<td><b>'.$ETI['msg_numsolicitud'].'</b></td>
			<td colspan="2"><b>'.$ETI['saiu05dia'].'</b></td>
			<td><b>'.$ETI['saiu05estado'].'</b></td>
			<td></td>
			</tr>';
			$tlinea=1;
			$bAbierta=true;
			$sSQL = 'SHOW TABLES LIKE "saiu05solicitud%"';
			$tablac = $objDB->ejecutasql($sSQL);
			while ($filac = $objDB->sf($tablac)) {
				$sContenedor = substr($filac[0], 15);
				if ($sContenedor != '') {
					$sSQL = 'SELECT TB.saiu05agno, TB.saiu05mes, TB.saiu05dia, TB.saiu05consec, T12.saiu11nombre, TB.saiu05hora, TB.saiu05minuto, TB.saiu05id, TB.saiu05estado, TB.saiu05numref
					FROM ' . $filac[0] . ' AS TB, saiu11estadosol AS T12 
					WHERE TB.saiu05estado=T12.saiu11id AND TB.saiu05idsolicitante="' . $idTercero . '"';
					$tabla = $objDB->ejecutasql($sSQL);
					if ($objDB->nf($tabla) > 0) {
						$_REQUEST['bMuestraExistentes'] = true;
						while ($fila = $objDB->sf($tabla)) {
							$_REQUEST['saiu05id'] = $fila['saiu05id'];
							$sPrefijo='';
							$sSufijo='';
							$sClass='';
							$sLink='';
							if (false){
								$sPrefijo='<b>';
								$sSufijo='</b>';
								}
							if(($tlinea%2)==0){$sClass=' class="resaltetabla"';}
							$tlinea++;
							$et_NumSol=f3000_NumSolicitud($fila['saiu05agno'], $fila['saiu05mes'], $fila['saiu05consec']);
							$et_saiu05dia='';
							$et_saiu05dia=fecha_armar($fila['saiu05dia'], $fila['saiu05mes'], $fila['saiu05agno']);
							$et_saiu05hora=html_TablaHoraMin($fila['saiu05hora'], $fila['saiu05minuto']);
							if ($bAbierta){
								$sLink='<a href="javascript:cargaridf3005('.$fila['saiu05agno'].', '.$fila['saiu05mes'].', '.$fila['saiu05id'].')" class="lnkresalte">'.$ETI['lnk_consultar'].'</a>';
								}
							$sTabla3005=$sTabla3005.'<tr'.$sClass.'>
							<td>'.$sPrefijo.$et_NumSol.$sSufijo.'</td>
							<td>'.$sPrefijo.$et_saiu05dia.$sSufijo.'</td>
							<td>'.$sPrefijo.$et_saiu05hora.$sSufijo.'</td>
							<td>'.$sPrefijo.cadena_notildes($fila['saiu11nombre']).$sSufijo.'</td>
							<td>'.$sLink.'</td>
							</tr>';
						}
					}
				}
			}
			$sTabla3005=$sTabla3005.'</table>';
			$_REQUEST['sTabla3005'] = $sTabla3005;
			$_REQUEST['boculta3005'] = 1;
			if ($_REQUEST['saiu05id'] == 0) {
				$sError = 'C&oacute;digo erroneo';
			}
		} else {
			$sError = 'No se ha encontrado el documento &quot;' . $_REQUEST['saiu05refdoc'] . '&quot;';
		}
	} else {		
		$_REQUEST['saiu05origenagno'] = substr($aRefDoc[0], 0, 4);
		$_REQUEST['saiu05origenmes'] = substr($aRefDoc[0], 4); 
		$_REQUEST['saiu05id'] = $aRefDoc[1];
		$_REQUEST['saiu05numref'] = $_REQUEST['saiu05refdoc'];
		$_REQUEST['bMuestraExistentes'] = false;
		$_REQUEST['paso'] = 3;
	}
}
//Si Modifica o Elimina Cargar los campos
if (($_REQUEST['paso'] == 1) || ($_REQUEST['paso'] == 3)) {
	$_REQUEST['saiu05idsolicitante_td'] = $APP->tipo_doc;
	$_REQUEST['saiu05idsolicitante_doc'] = '';
	$_REQUEST['saiu05idinteresado_td'] = $APP->tipo_doc;
	$_REQUEST['saiu05idinteresado_doc'] = '';
	$_REQUEST['saiu05idsupervisor_td'] = $APP->tipo_doc;
	$_REQUEST['saiu05idsupervisor_doc'] = '';
	$_REQUEST['saiu05idresponsable_td'] = $APP->tipo_doc;
	$_REQUEST['saiu05idresponsable_doc'] = '';
	$sTabla = 'saiu05solicitud' . f3000_Contenedor($_REQUEST['saiu05origenagno'], $_REQUEST['saiu05origenmes']);
	if ($objDB->bexistetabla($sTabla)) {
		if ($_REQUEST['paso'] == 1) {
			$sSQLcondi = 'saiu05agno=' . $_REQUEST['saiu05agno'] . ' AND saiu05mes=' . $_REQUEST['saiu05mes'] . ' AND saiu05tiporadicado=' . $_REQUEST['saiu05tiporadicado'] . ' AND saiu05consec=' . $_REQUEST['saiu05consec'] . '';
		} else {
			$sSQLcondi = 'saiu05id=' . $_REQUEST['saiu05id'] . '';
			if ($_REQUEST['bMuestraExistentes'] == false) {
				$sSQLcondi = $sSQLcondi . ' AND saiu05numref="' . $_REQUEST['saiu05numref'] . '"';
			}
		}
		$sSQL = 'SELECT * FROM ' . $sTabla . ' WHERE ' . $sSQLcondi;
		// $sDebug=$sDebug.fecha_microtiempo().' SQL CARGA LISTA '.$sSQL.'<br>';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			$_REQUEST['saiu05agno'] = $fila['saiu05agno'];
			$_REQUEST['saiu05mes'] = $fila['saiu05mes'];
			$_REQUEST['saiu05tiporadicado'] = $fila['saiu05tiporadicado'];
			$_REQUEST['saiu05consec'] = $fila['saiu05consec'];
			$_REQUEST['saiu05id'] = $fila['saiu05id'];
			$_REQUEST['saiu05origenagno'] = $fila['saiu05origenagno'];
			$_REQUEST['saiu05origenmes'] = $fila['saiu05origenmes'];
			$_REQUEST['saiu05origenid'] = $fila['saiu05origenid'];
			$_REQUEST['saiu05dia'] = $fila['saiu05dia'];
			$_REQUEST['saiu05hora'] = $fila['saiu05hora'];
			$_REQUEST['saiu05minuto'] = $fila['saiu05minuto'];
			$_REQUEST['saiu05raddia'] = $fila['saiu05raddia'];
			$_REQUEST['saiu05radhora'] = $fila['saiu05radhora'];
			$_REQUEST['saiu05radmin'] = $fila['saiu05radmin'];
			$_REQUEST['saiu05raddespcalend'] = $fila['saiu05raddespcalend'];
			$_REQUEST['saiu05raddesphab'] = $fila['saiu05raddesphab'];
			$_REQUEST['saiu05estado'] = $fila['saiu05estado'];
			$_REQUEST['saiu05idmedio'] = $fila['saiu05idmedio'];
			$_REQUEST['saiu05idtiposolorigen'] = $fila['saiu05idtiposolorigen'];
			$_REQUEST['saiu05idtemaorigen'] = $fila['saiu05idtemaorigen'];
			$_REQUEST['saiu05idtiposolfin'] = $fila['saiu05idtiposolfin'];
			$_REQUEST['saiu05idtemafin'] = $fila['saiu05idtemafin'];
			$_REQUEST['saiu05idsolicitante'] = $fila['saiu05idsolicitante'];
			$_REQUEST['saiu05idinteresado'] = $fila['saiu05idinteresado'];
			$_REQUEST['saiu05tipointeresado'] = $fila['saiu05tipointeresado'];
			$_REQUEST['saiu05rptaforma'] = $fila['saiu05rptaforma'];
			$_REQUEST['saiu05rptacorreo'] = $fila['saiu05rptacorreo'];
			$_REQUEST['saiu05rptadireccion'] = $fila['saiu05rptadireccion'];
			$_REQUEST['saiu05costogenera'] = $fila['saiu05costogenera'];
			$_REQUEST['saiu05costovalor'] = $fila['saiu05costovalor'];
			$_REQUEST['saiu05costorefpago'] = $fila['saiu05costorefpago'];
			$_REQUEST['saiu05prioridad'] = $fila['saiu05prioridad'];
			$_REQUEST['saiu05idzona'] = $fila['saiu05idzona'];
			$_REQUEST['saiu05cead'] = $fila['saiu05cead'];
			$_REQUEST['saiu05numref'] = $fila['saiu05numref'];
			$_REQUEST['saiu05detalle'] = $fila['saiu05detalle'];
			$_REQUEST['saiu05infocomplemento'] = $fila['saiu05infocomplemento'];
			$_REQUEST['saiu05idunidadresp'] = $fila['saiu05idunidadresp'];
			$_REQUEST['saiu05idequiporesp'] = $fila['saiu05idequiporesp'];
			$_REQUEST['saiu05idsupervisor'] = $fila['saiu05idsupervisor'];
			$_REQUEST['saiu05idresponsable'] = $fila['saiu05idresponsable'];
			$_REQUEST['saiu05idescuela'] = $fila['saiu05idescuela'];
			$_REQUEST['saiu05idprograma'] = $fila['saiu05idprograma'];
			$_REQUEST['saiu05idperiodo'] = $fila['saiu05idperiodo'];
			$_REQUEST['saiu05idcurso'] = $fila['saiu05idcurso'];
			$_REQUEST['saiu05idgrupo'] = $fila['saiu05idgrupo'];
			$_REQUEST['saiu05tiemprespdias'] = $fila['saiu05tiemprespdias'];
			$_REQUEST['saiu05tiempresphoras'] = $fila['saiu05tiempresphoras'];
			$_REQUEST['saiu05fecharespprob'] = $fila['saiu05fecharespprob'];
			$_REQUEST['saiu05respuesta'] = $fila['saiu05respuesta'];
			$_REQUEST['saiu05fecharespdef'] = $fila['saiu05fecharespdef'];
			$_REQUEST['saiu05horarespdef'] = $fila['saiu05horarespdef'];
			$_REQUEST['saiu05minrespdef'] = $fila['saiu05minrespdef'];
			$_REQUEST['saiu05diasproc'] = $fila['saiu05diasproc'];
			$_REQUEST['saiu05minproc'] = $fila['saiu05minproc'];
			$_REQUEST['saiu05diashabproc'] = $fila['saiu05diashabproc'];
			$_REQUEST['saiu05minhabproc'] = $fila['saiu05minhabproc'];
			$_REQUEST['saiu05idmoduloproc'] = $fila['saiu05idmoduloproc'];
			$_REQUEST['saiu05identificadormod'] = $fila['saiu05identificadormod'];
			$_REQUEST['saiu05numradicado'] = $fila['saiu05numradicado'];
			$_REQUEST['saiu05evalacepta'] = $fila['saiu05evalacepta'];
			$_REQUEST['saiu05evalfecha'] = $fila['saiu05evalfecha'];
			$_REQUEST['saiu05evalamabilidad'] = $fila['saiu05evalamabilidad'];
			$_REQUEST['saiu05evalamabmotivo'] = $fila['saiu05evalamabmotivo'];
			$_REQUEST['saiu05evalrapidez'] = $fila['saiu05evalrapidez'];
			$_REQUEST['saiu05evalrapidmotivo'] = $fila['saiu05evalrapidmotivo'];
			$_REQUEST['saiu05evalclaridad'] = $fila['saiu05evalclaridad'];
			$_REQUEST['saiu05evalcalridmotivo'] = $fila['saiu05evalcalridmotivo'];
			$_REQUEST['saiu05evalresolvio'] = $fila['saiu05evalresolvio'];
			$_REQUEST['saiu05evalsugerencias'] = $fila['saiu05evalsugerencias'];
			$_REQUEST['saiu05idcategoria'] = $fila['saiu05idcategoria'];
			$bcargo = true;
			$_REQUEST['paso'] = 2;
			$_REQUEST['boculta3005'] = 0;
			$_REQUEST['boculta3005_0'] = 0;
			$bLimpiaHijos = true;
			if ($_REQUEST['saiu05tiporadicado'] != 1) {
				$sError = 'La solicitud que intenta consultar no corresponde a este m&oacute;dulo.';
				$_REQUEST['paso'] = -1;
			}
		} else {
			$_REQUEST['paso'] = 0;
			if ($_REQUEST['bMuestraExistentes'] == false) {
				$sError = 'Referencia de consulta incorrecta.';
				$_REQUEST['paso'] = -1;
			}
		}
	} else {
		$sError = 'No ha sido posible encontrar el contenedor para ' . $_REQUEST['saiu05agno'] . ' - ' . $_REQUEST['saiu05mes'] . '';
		$_REQUEST['paso'] = -1;
	}
}
if ($_REQUEST['opcion'] == 2) {
	$_REQUEST['paso'] = -1;
}
//Cerrar
$sMensajeMail = '';
$bCerrando = false;
if ($_REQUEST['paso'] == 16) {
	$_REQUEST['paso'] = 12;
	$iAgno=fecha_agno();
	$iContenedor = $iAgno . fecha_mes();
	$sTabla='saiu05solicitud_' . $iContenedor;
	if (!$objDB->bexistetabla($sTabla)) {
		$sError = 'No ha sido posible acceder al contenedor de datos';
	}
	if ($sError == '') {
		$_REQUEST['saiu05estado'] = 0;
		$bCerrando = true;
	} else {
		$_REQUEST['paso'] = 2;
	}
}
//Insertar o modificar un elemento
if (($_REQUEST['paso'] == 10) || ($_REQUEST['paso'] == 12)) {
	$bMueveScroll = true;
	list($_REQUEST, $sError, $iTipoError, $sDebugGuardar) = f3005_db_Guardar($_REQUEST, $objDB, $bDebug, $idTercero);
	$sDebug = $sDebug . $sDebugGuardar;
	if ($sError == '') {
		$sError = '<b>' . $ETI['msg_itemguardado'] . '</b>';
		$iTipoError = 1;
	} else {
		$_REQUEST['saiu05estado'] = -1;
	}
}
// Cambio de consecutivo.
if ($_REQUEST['paso'] == 93) {
	$_REQUEST['paso'] = 2;
	$_REQUEST['saiu05consec_nuevo'] = numeros_validar($_REQUEST['saiu05consec_nuevo']);
	if ($_REQUEST['saiu05consec_nuevo'] == '') {
		$sError = $ERR['saiu05consec'];
	}
	if ($sError == '') {
		list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 8, $idTercero, $objDB);
		if (!$bDevuelve) {
			$sError = $ERR['8'];
		}
	}
	if ($sError == '') {
		$sTabla5 = 'saiu05solicitud' . f3000_Contenedor($_REQUEST['saiu05agno'], $_REQUEST['saiu05mes']);
		//Ver que el consecutivo no exista.
		$sSQL = 'SELECT saiu05id FROM ' . $sTabla5 . ' WHERE saiu05consec=' . $_REQUEST['saiu05consec_nuevo'] . ' AND saiu05tiporadicado=' . $_REQUEST['saiu05tiporadicado'] . ' AND saiu05mes=' . $_REQUEST['saiu05mes'] . ' AND saiu05agno=' . $_REQUEST['saiu05agno'] . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$sError = 'El consecutivo ' . $_REQUEST['saiu05consec_nuevo'] . ' ya existe';
		}
	}
	if ($sError == '') {
		//Aplicar el cambio.
		$sSQL = 'UPDATE ' . $sTabla5 . ' SET saiu05consec=' . $_REQUEST['saiu05consec_nuevo'] . ' WHERE saiu05id=' . $_REQUEST['saiu05id'] . '';
		$tabla = $objDB->ejecutasql($sSQL);
		$sDetalle = 'Cambia el consecutivo de ' . $_REQUEST['saiu05consec'] . ' a ' . $_REQUEST['saiu05consec_nuevo'] . '';
		$_REQUEST['saiu05consec'] = $_REQUEST['saiu05consec_nuevo'];
		$_REQUEST['saiu05consec_nuevo'] = '';
		seg_auditar($iCodModulo, $_SESSION['u_idtercero'], 8, $_REQUEST['saiu05id'], $sDetalle, $objDB);
		$sError = '<b>Se ha aplicado el cambio de consecutivo.</b>';
		$iTipoError = 1;
	} else {
		$iSector = 93;
	}
}
//Eliminar un elemento
if ($_REQUEST['paso'] == 13) {
	$_REQUEST['paso'] = 2;
	list($sError, $iTipoError, $sDebugElimina) = f3005_db_Eliminar($_REQUEST['saiu05agno'], $_REQUEST['saiu05mes'], $_REQUEST['saiu05id'], $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugElimina;
	if ($sError == '') {
		$_REQUEST['paso'] = -1;
		$sError = $ETI['msg_itemeliminado'];
		$iTipoError = 1;
	}
}
//limpiar la pantalla
$iViaWeb = 3;
if ($_REQUEST['paso'] == -1) {
	$_REQUEST['saiu05agno'] = fecha_agno();
	$_REQUEST['saiu05mes'] = fecha_mes();
	$_REQUEST['saiu05tiporadicado'] = 1;
	$_REQUEST['saiu05consec'] = '';
	$_REQUEST['saiu05consec_nuevo'] = '';
	$_REQUEST['saiu05id'] = '';
	$_REQUEST['saiu05origenagno'] = '';
	$_REQUEST['saiu05origenmes'] = '';
	$_REQUEST['saiu05origenid'] = 0;
	$_REQUEST['saiu05dia'] = ''; //fecha_hoy();
	$_REQUEST['saiu05hora'] = fecha_hora();
	$_REQUEST['saiu05minuto'] = fecha_minuto();
	$_REQUEST['saiu05raddia'] = '';
	$_REQUEST['saiu05radhora'] = '';
	$_REQUEST['saiu05radmin'] = '';
	$_REQUEST['saiu05raddespcalend'] = '';
	$_REQUEST['saiu05raddesphab'] = '';
	$_REQUEST['saiu05estado'] = -1;
	$_REQUEST['saiu05idmedio'] = 0;
	$_REQUEST['saiu05idtiposolorigen'] = '';
	$_REQUEST['saiu05idtemaorigen'] = '';
	$_REQUEST['saiu05idtiposolfin'] = '';
	$_REQUEST['saiu05idtemafin'] = '';
	$_REQUEST['saiu05idsolicitante'] = 0; //$idTercero;
	$_REQUEST['saiu05idsolicitante_td'] = $APP->tipo_doc;
	$_REQUEST['saiu05idsolicitante_doc'] = '';
	$_REQUEST['saiu05idinteresado'] = 0; //$idTercero;
	$_REQUEST['saiu05idinteresado_td'] = $APP->tipo_doc;
	$_REQUEST['saiu05idinteresado_doc'] = '';
	$_REQUEST['saiu05tipointeresado'] = 6;
	$_REQUEST['saiu05rptaforma'] = 0;
	$_REQUEST['saiu05rptacorreo'] = '';
	$_REQUEST['saiu05rptadireccion'] = '';
	$_REQUEST['saiu05costogenera'] = 0;
	$_REQUEST['saiu05costovalor'] = 0;
	$_REQUEST['saiu05costorefpago'] = '';
	$_REQUEST['saiu05prioridad'] = '';
	$_REQUEST['saiu05idzona'] = '';
	$_REQUEST['saiu05cead'] = '';
	$_REQUEST['saiu05refdoc'] = '';
	$_REQUEST['saiu05numref'] = '';
	$_REQUEST['saiu05detalle'] = '';
	$_REQUEST['saiu05infocomplemento'] = '';
	$_REQUEST['saiu05idunidadresp'] = 0;
	$_REQUEST['saiu05idequiporesp'] = 0;
	$_REQUEST['saiu05idsupervisor'] = 0; //$idTercero;
	$_REQUEST['saiu05idsupervisor_td'] = $APP->tipo_doc;
	$_REQUEST['saiu05idsupervisor_doc'] = '';
	$_REQUEST['saiu05idresponsable'] = 0; //$idTercero;
	$_REQUEST['saiu05idresponsable_td'] = $APP->tipo_doc;
	$_REQUEST['saiu05idresponsable_doc'] = '';
	$_REQUEST['saiu05idescuela'] = '';
	$_REQUEST['saiu05idprograma'] = '';
	$_REQUEST['saiu05idperiodo'] = '';
	$_REQUEST['saiu05idcurso'] = '';
	$_REQUEST['saiu05idgrupo'] = '';
	$_REQUEST['saiu05tiemprespdias'] = '';
	$_REQUEST['saiu05tiempresphoras'] = '';
	$_REQUEST['saiu05fecharespprob'] = fecha_hoy();
	$_REQUEST['saiu05respuesta'] = '';
	$_REQUEST['saiu05fecharespdef'] = 0;
	$_REQUEST['saiu05horarespdef'] = 0;
	$_REQUEST['saiu05minrespdef'] = 0;
	$_REQUEST['saiu05diasproc'] = 0;
	$_REQUEST['saiu05minproc'] = 0;
	$_REQUEST['saiu05diashabproc'] = 0;
	$_REQUEST['saiu05minhabproc'] = 0;
	$_REQUEST['saiu05idmoduloproc'] = '';
	$_REQUEST['saiu05identificadormod'] = '';
	$_REQUEST['saiu05numradicado'] = '';
	$_REQUEST['saiu05evalacepta'] = -1;
	$_REQUEST['saiu05evalfecha'] = fecha_hoy();
	$_REQUEST['saiu05evalamabilidad'] = 0;
	$_REQUEST['saiu05evalamabmotivo'] = '';
	$_REQUEST['saiu05evalrapidez'] = 0;
	$_REQUEST['saiu05evalrapidmotivo'] = '';
	$_REQUEST['saiu05evalclaridad'] = 0;
	$_REQUEST['saiu05evalcalridmotivo'] = '';
	$_REQUEST['saiu05evalresolvio'] = 0;
	$_REQUEST['saiu05evalsugerencias'] = '';
	$_REQUEST['saiu05idcategoria'] = '';
	$_REQUEST['bMuestraExistentes'] = false;
	$_REQUEST['boculta3005']=0;
	$_REQUEST['boculta3006_Campos']=1;
	$_REQUEST['sTabla3005']='';
	$_REQUEST['aceptaterminos']=1;
	$_REQUEST['paso'] = 0;
}
if ($bLimpiaHijos) {
	$_REQUEST['saiu06idsolicitud'] = '';
	$_REQUEST['saiu06consec'] = '';
	$_REQUEST['saiu06id'] = '';
	$_REQUEST['saiu06anotacion'] = '';
	$_REQUEST['saiu06visible'] = '';
	$_REQUEST['saiu06descartada'] = '';
	$_REQUEST['saiu06idorigen'] = 0;
	$_REQUEST['saiu06idarchivo'] = 0;
	$_REQUEST['saiu06idusuario'] = $idTercero;
	$_REQUEST['saiu06idusuario_td'] = $APP->tipo_doc;
	$_REQUEST['saiu06idusuario_doc'] = '';
	$_REQUEST['saiu06fecha'] = fecha_hoy();
	$_REQUEST['saiu06hora'] = fecha_hora();
	$_REQUEST['saiu06minuto'] = fecha_minuto();
	$_REQUEST['saiu07idsolicitud'] = '';
	$_REQUEST['saiu07consec'] = '';
	$_REQUEST['saiu07id'] = '';
	$_REQUEST['saiu07idtipoanexo'] = '';
	$_REQUEST['saiu07detalle'] = '';
	$_REQUEST['saiu07idorigen'] = 0;
	$_REQUEST['saiu07idarchivo'] = 0;
	$_REQUEST['saiu07idusuario'] = 0; //$idTercero;
	$_REQUEST['saiu07idusuario_td'] = $APP->tipo_doc;
	$_REQUEST['saiu07idusuario_doc'] = '';
	$_REQUEST['saiu07fecha'] = ''; //fecha_hoy();
	$_REQUEST['saiu07hora'] = fecha_hora();
	$_REQUEST['saiu07minuto'] = fecha_minuto();
	$_REQUEST['saiu07estado'] = 0;
	$_REQUEST['saiu07idvalidad'] = 0; //$idTercero;
	$_REQUEST['saiu07idvalidad_td'] = $APP->tipo_doc;
	$_REQUEST['saiu07idvalidad_doc'] = '';
	$_REQUEST['saiu07fechavalida'] = ''; //fecha_hoy();
	$_REQUEST['saiu07horavalida'] = fecha_hora();
	$_REQUEST['saiu07minvalida'] = fecha_minuto();
}
//AQUI SE DEBEN CARGAR TODOS LOS DATOS QUE LA FORMA NECESITE.
$bConMedio = false;
if ((int)$_REQUEST['paso'] > 0) {
	if ($_REQUEST['saiu05idmedio'] != $iViaWeb) {
		$bConMedio = false;
	}
}
//DATOS PARA COMPLETAR EL FORMULARIO
$iAgno=fecha_agno();
$iAgnoFin = fecha_agno();
$iContenedor = $iAgno . fecha_mes();
$sTabla='saiu05solicitud_' . $iContenedor;
if (!$objDB->bexistetabla($sTabla)){
	list($sErrorT, $sDebugT)=f3000_TablasMes($iAgno, fecha_mes(), $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugT;
	}
// - AJUSTAR LAS TABLAS
//f3000_AjustarTablas($objDB, $bDebug);
//Permisos adicionales
$seg_4 = 0;
$seg_5 = 0;
$seg_6 = 0;
$seg_8 = 0;
/*
list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 6, $idTercero, $objDB);
if ($bDevuelve) {
	$seg_6 = 1;
}
*/
//Crear los controles que requieran llamado a base de datos
$objCombos = new clsHtmlCombos();
$objTercero = new clsHtmlTercero();
$saiu05estado_nombre = '';
if ($_REQUEST['saiu05estado'] == -1) {
	$saiu05estado_nombre = 'Borrador';
} else {
	list($saiu05estado_nombre, $sErrorDet) = tabla_campoxid('saiu11estadosol', 'saiu11nombre', 'saiu11id', $_REQUEST['saiu05estado'], '{' . $ETI['msg_sindato'] . '}', $objDB);
}
$html_saiu05estado = html_oculto('saiu05estado', $_REQUEST['saiu05estado'], $saiu05estado_nombre);
if ($bConMedio) {
	//list($saiu05idmedio_nombre, $sErrorDet) = tabla_campoxid('bita01tiposolicitud', 'bita01nombre', 'bita01id', $_REQUEST['saiu05idmedio'], '{' . $ETI['msg_sindato'] . '}', $objDB);
	//$html_saiu05idmedio = html_oculto('saiu05idmedio', $_REQUEST['saiu05idmedio'], $saiu05idmedio_nombre);
	$objCombos->nuevo('saiu05idmedio', $_REQUEST['saiu05idmedio'], true, '{' . $ETI['msg_seleccione'] . '}');
	$sSQL = 'SELECT bita01id AS id, bita01nombre AS nombre FROM bita01tiposolicitud ORDER BY bita01nombre';
	$html_saiu05idmedio = $objCombos->html($sSQL, $objDB);
}
if ((int)$_REQUEST['paso'] == 0) {
	$objCombos->nuevo('saiu05idtiposolorigen', $_REQUEST['saiu05idtiposolorigen'], true, '{' . $ETI['msg_seleccione'] . '}');
	$objCombos->sAccion = 'carga_combo_saiu05idtemaorigen();';
	$objCombos->iAncho = 370;
	$sSQL = 'SELECT saiu02id AS id, saiu02titulo AS nombre FROM saiu02tiposol WHERE saiu02id>0 ORDER BY saiu02titulo';
	$html_saiu05idtiposolorigen = $objCombos->html($sSQL, $objDB);
	$html_saiu05idtemaorigen = f3005_HTMLComboV2_saiu05idtemaorigen($objDB, $objCombos, $_REQUEST['saiu05idtemaorigen'], $_REQUEST['saiu05idtiposolorigen']);
} else {
	list($saiu05idtiposolorigen_nombre, $sErrorDet) = tabla_campoxid('saiu02tiposol', 'saiu02titulo', 'saiu02id', $_REQUEST['saiu05idtiposolorigen'], '{' . $ETI['msg_sindato'] . '}', $objDB);
	$html_saiu05idtiposolorigen = html_oculto('saiu05idtiposolorigen', $_REQUEST['saiu05idtiposolorigen'], $saiu05idtiposolorigen_nombre);
	list($saiu05idtemaorigen_nombre, $sErrorDet) = tabla_campoxid('saiu03temasol', 'saiu03titulo', 'saiu03id', $_REQUEST['saiu05idtemaorigen'], '{' . $ETI['msg_sindato'] . '}', $objDB);
	$html_saiu05idtemaorigen = html_oculto('saiu05idtemaorigen', $_REQUEST['saiu05idtemaorigen'], $saiu05idtemaorigen_nombre);
}
list($saiu05idsolicitante_rs, $_REQUEST['saiu05idsolicitante'], $_REQUEST['saiu05idsolicitante_td'], $_REQUEST['saiu05idsolicitante_doc']) = html_tercero($_REQUEST['saiu05idsolicitante_td'], $_REQUEST['saiu05idsolicitante_doc'], $idTercero, 0, $objDB);
list($saiu05idinteresado_rs, $_REQUEST['saiu05idinteresado'], $_REQUEST['saiu05idinteresado_td'], $_REQUEST['saiu05idinteresado_doc']) = html_tercero($_REQUEST['saiu05idinteresado_td'], $_REQUEST['saiu05idinteresado_doc'], $_REQUEST['saiu05idinteresado'], 0, $objDB);
$objCombos->nuevo('saiu05tipointeresado', $_REQUEST['saiu05tipointeresado'], false, '{' . $ETI['msg_seleccione'] . '}');
$sSQL = 'SELECT bita07id AS id, bita07nombre AS nombre FROM bita07tiposolicitante ORDER BY bita07nombre';
$html_saiu05tipointeresado = $objCombos->html($sSQL, $objDB);

$objCombos->nuevo('saiu05costogenera', $_REQUEST['saiu05costogenera'], false, '{' . $ETI['msg_seleccione'] . '}');
$objCombos->sAccion = 'ajustarcosto()';
$objCombos->SiNo($ETI['si'], $ETI['no'], 1, 0);
$html_saiu05costogenera = $objCombos->html('', $objDB);

$objCombos->nuevo('saiu05rptaforma', $_REQUEST['saiu05rptaforma'], false, '{' . $ETI['msg_seleccione'] . '}');
$objCombos->sAccion = 'ajustarformarpta()';
$sSQL = 'SELECT saiu12id AS id, saiu12nombre AS nombre FROM saiu12formarespuesta ORDER BY saiu12nombre';
$html_saiu05rptaforma = $objCombos->html($sSQL, $objDB);
list($saiu05idresponsable_rs, $_REQUEST['saiu05idresponsable'], $_REQUEST['saiu05idresponsable_td'], $_REQUEST['saiu05idresponsable_doc']) = html_tercero($_REQUEST['saiu05idresponsable_td'], $_REQUEST['saiu05idresponsable_doc'], $_REQUEST['saiu05idresponsable'], 0, $objDB);
$objCombos->nuevo('saiu05idcategoria', $_REQUEST['saiu05idcategoria'], true, '{' . $ETI['msg_seleccione'] . '}');
$sSQL = 'SELECT saiu68id AS id, saiu68nombre AS nombre FROM saiu68categoria WHERE saiu68publica = 1 ORDER BY saiu68nombre';
$html_saiu05idcategoria = $objCombos->html($sSQL, $objDB);
if ((int)$_REQUEST['paso'] == 0) {
} else {
	$objCombos->nuevo('saiu06visible', $_REQUEST['saiu06visible'], false);
	$objCombos->sino();
	$html_saiu06visible = $objCombos->html('', $objDB);
	$objCombos->nuevo('saiu06descartada', $_REQUEST['saiu06descartada'], false);
	$objCombos->sino();
	$html_saiu06descartada = $objCombos->html('', $objDB);
	list($saiu06idusuario_rs, $_REQUEST['saiu06idusuario'], $_REQUEST['saiu06idusuario_td'], $_REQUEST['saiu06idusuario_doc']) = html_tercero($_REQUEST['saiu06idusuario_td'], $_REQUEST['saiu06idusuario_doc'], $_REQUEST['saiu06idusuario'], 0, $objDB);
	$objCombos->nuevo('saiu07idtipoanexo', $_REQUEST['saiu07idtipoanexo'], true, '{' . $ETI['msg_seleccione'] . '}');
	$sSQL = 'SELECT saiu04id AS id, saiu04titulo AS nombre FROM saiu04temaanexo ORDER BY saiu04titulo';
	$html_saiu07idtipoanexo = $objCombos->html($sSQL, $objDB);
	list($saiu07idusuario_rs, $_REQUEST['saiu07idusuario'], $_REQUEST['saiu07idusuario_td'], $_REQUEST['saiu07idusuario_doc']) = html_tercero($_REQUEST['saiu07idusuario_td'], $_REQUEST['saiu07idusuario_doc'], $_REQUEST['saiu07idusuario'], 0, $objDB);
	list($saiu07estado_nombre, $sErrorDet) = tabla_campoxid('saiu14estadoanexo', 'saiu14nombre', 'saiu14id', $_REQUEST['saiu07estado'], '{' . $ETI['msg_sindato'] . '}', $objDB);
	$html_saiu07estado = html_oculto('saiu07estado', $_REQUEST['saiu07estado'], $saiu07estado_nombre);
	list($saiu07idvalidad_rs, $_REQUEST['saiu07idvalidad'], $_REQUEST['saiu07idvalidad_td'], $_REQUEST['saiu07idvalidad_doc']) = html_tercero($_REQUEST['saiu07idvalidad_td'], $_REQUEST['saiu07idvalidad_doc'], $_REQUEST['saiu07idvalidad'], 0, $objDB);
}
//Alistar datos adicionales
$id_rpt = 0;
//$id_rpt=reportes_id(_Identificador_Tipo_Reporte_, $objDB);
$objCombos->nuevo('blistar', $_REQUEST['blistar'], false, '{' . $ETI['msg_todos'] . '}');
$objCombos->sAccion = 'paginarf3005()';
$objCombos->numeros(2020, $iAgnoFin, 1);
/*
$sSQL='SHOW TABLES LIKE "saiu05solicitud%"';
$tablac=$objDB->ejecutasql($sSQL);
while($filac=$objDB->sf($tablac)){
	$sAgno=substr($filac[0], 16);
	$objCombos->addItem($sAgno, $sAgno);
	}
*/
$html_blistar = $objCombos->html('', $objDB);
/*
$objCombos->nuevo('blistar3006', $_REQUEST['blistar3006'], true, '{'.$ETI['msg_todos'].'}');
$html_blistar3006=$objCombos->comboSistema(3006, 1, $objDB, 'paginarf3006()');
$objCombos->nuevo('blistar3007', $_REQUEST['blistar3007'], true, '{'.$ETI['msg_todos'].'}');
$html_blistar3007=$objCombos->comboSistema(3007, 1, $objDB, 'paginarf3007()');
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
$iModeloReporte = 3005;
$html_iFormatoImprime = '<input id="iformatoimprime" name="iformatoimprime" type="hidden" value="0" />
';
if ($_REQUEST['paso'] > 0) {
	$bDevuelve = false;
	//list($bDevuelve, $sDebugP)=seg_revisa_permisoV3($iCodModulo, 5, $idTercero, $objDB);
	if ($bDevuelve) {
		$seg_5 = 1;
	}
	list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 4, $idTercero, $objDB);
	if ($bDevuelve) {
		$seg_4 = 1;
	}
}
//Cargar las tablas de datos
$aParametros[0] = ''; //$_REQUEST['p1_3005'];
$aParametros[100] = $idTercero;
$aParametros[101] = $_REQUEST['paginaf3005'];
$aParametros[102] = $_REQUEST['lppf3005'];
$aParametros[103] = $_REQUEST['bnombre'];
$aParametros[104] = $_REQUEST['blistar'];
$sTabla3000 = '';
$sTabla3006 = '';
$sTabla3007 = '';
$sNumSol = '';
$aParametros3000[0] = $idTercero;
$aParametros3000[1] = $iCodModulo;
$aParametros3000[2] = $_REQUEST['saiu05agno'];
$aParametros3000[3] = $_REQUEST['saiu05id'];
$aParametros3000[100] = $_REQUEST['saiu05idsolicitante'];
$aParametros3000[101] = $_REQUEST['paginaf3000'];
$aParametros3000[102] = $_REQUEST['lppf3000'];
//$aParametros3000[103]=$_REQUEST['bnombre3000'];
//$aParametros3000[104]=$_REQUEST['blistar3000'];
list($sTabla3000, $sDebugTabla) = f3000_TablaDetalleV2($aParametros3000, $objDB, $bDebug);
$sDebug = $sDebug . $sDebugTabla;
if ($_REQUEST['paso'] != 0) {
	//Anotaciones
	$sNumSol = f3000_NumSolicitud($_REQUEST['saiu05agno'], $_REQUEST['saiu05mes'], $_REQUEST['saiu05consec']);
	$aParametros3006[0] = $_REQUEST['saiu05id'];
	$aParametros3006[97] = $_REQUEST['saiu05agno'];
	$aParametros3006[98] = $_REQUEST['saiu05mes'];
	$aParametros3006[99] = true;
	$aParametros3006[100] = $idTercero;
	$aParametros3006[101] = $_REQUEST['paginaf3006'];
	$aParametros3006[102] = $_REQUEST['lppf3006'];
	//$aParametros3006[103]=$_REQUEST['bnombre3006'];
	//$aParametros3006[104]=$_REQUEST['blistar3006'];
	list($sTabla3006, $sDebugTabla) = f3006_TablaDetalleV2($aParametros3006, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugTabla;
	//Anexos
	$aParametros3007[0] = $_REQUEST['saiu05id'];
	$aParametros3007[97] = $_REQUEST['saiu05agno'];
	$aParametros3007[98] = $_REQUEST['saiu05mes'];
	$aParametros3007[100] = $idTercero;
	$aParametros3007[101] = $_REQUEST['paginaf3007'];
	$aParametros3007[102] = $_REQUEST['lppf3007'];
	//$aParametros3007[103]=$_REQUEST['bnombre3007'];
	//$aParametros3007[104]=$_REQUEST['blistar3007'];
	list($sTabla3007, $sDebugTabla) = f3007_TablaDetalleV2($aParametros3007, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugTabla;
}
$bDebugMenu = false;
// list($et_menu, $sDebugM) = html_menuV2($APP->idsistema, $objDB, $iPiel, $bDebugMenu, $idTercero);
// $sDebug = $sDebug . $sDebugM;
$objDB->CerrarConexion();
//FORMA
require $APP->rutacomun . 'unad_forma_v2.php';
forma_cabeceraV3($xajax, $ETI['titulo_3005']);
// echo $et_menu;
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
		window.document.frmedita.opcion.value = 0;
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

	function enviacerrar() {
		ModalConfirm('<?php echo $ETI['msg_cerrar']; ?>');
		ModalDialogConfirm(function(confirm) {
			if (confirm) {
				ejecuta_enviacerrar();
			}
		});
	}

	function ejecuta_enviacerrar() {
		MensajeAlarmaV2('<?php echo $ETI['msg_ejecutando']; ?>', 2);
		expandesector(98);
		window.document.frmedita.paso.value = 16;
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
		document.getElementById('div_sector93').style.display = 'none';
		document.getElementById('div_sector95').style.display = 'none';
		document.getElementById('div_sector96').style.display = 'none';
		document.getElementById('div_sector97').style.display = 'none';
		document.getElementById('div_sector98').style.display = 'none';
		document.getElementById('div_sector' + codigo).style.display = 'block';
		var sEst = 'none';
		if (codigo == 1) {
			sEst = 'block';
		}
		<?php
		if ($_REQUEST['opcion'] == 2) {
		?>
		document.getElementById('cmdGuardarf').style.display = sEst;
		<?php
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
			if (idcampo == 'saiu05idsolicitante') {
				params[6] = 3005;
				xajax_unad11_Mostrar_v2SAI(params);
			} else {
				xajax_unad11_Mostrar_v2(params);
			}
		} else {
			document.getElementById(idcampo).value = 0;
			document.getElementById('div_' + idcampo).innerHTML = '&nbsp;';
			paginarf3000();
		}
	}

	function ter_traerxid(idcampo, vrcampo) {
		var params = new Array();
		params[0] = vrcampo;
		params[1] = idcampo;
		if (params[0] != 0) {
			params[6] = 3005;
			xajax_unad11_TraerXidSAI(params);
		}
	}

	function imprimelista() {
		if (window.document.frmedita.seg_6.value == 1) {
			window.document.frmlista.consulta.value = window.document.frmedita.consulta_3005.value;
			window.document.frmlista.titulos.value = window.document.frmedita.titulos_3005.value;
			window.document.frmlista.nombrearchivo.value = 'Solicitudes';
			window.document.frmlista.submit();
		} else {
			ModalMensaje("<?php echo $ERR['6']; ?>");
		}
	}

	function asignarvariables() {
		//window.document.frmimpp.v3.value = window.document.frmedita.bnombre.value;
		//window.document.frmimpp.v4.value = window.document.frmedita.bcodigo.value;
		//window.document.frmimpp.separa.value = window.document.frmedita.csv_separa.value.trim();
	}

	function imprimeexcel() {
		var sError = '';
		if (window.document.frmedita.seg_6.value != 1) {
			sError = "<?php echo $ERR['6']; ?>";
		}
		if (sError == '') {
			/*Agregar validaciones*/
		}
		if (sError == '') {
			asignarvariables();
			window.document.frmimpp.action = '<?php echo $APP->rutacomun; ?>e3005.php';
			window.document.frmimpp.submit();
		} else {
			ModalMensaje(sError);
		}
	}

	function imprimep() {
		if (window.document.frmedita.seg_5.value == 1) {
			asignarvariables();
			window.document.frmimpp.action = '<?php echo $APP->rutacomun; ?>p3005.php';
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
	
	function RevisaLlave() {
		var datos = new Array();
		datos[1] = window.document.frmedita.saiu05agno.value;
		datos[2] = window.document.frmedita.saiu05mes.value;
		datos[3] = window.document.frmedita.saiu05tiporadicado.value;
		datos[4] = window.document.frmedita.saiu05consec.value;
		if ((datos[1] != '') && (datos[2] != '') && (datos[3] != '') && (datos[4] != '')) {
			xajax_f3005_ExisteDato(datos);
		}
	}

	function cargadato(llave1, llave2, llave3, llave4) {
		window.document.frmedita.saiu05agno.value = String(llave1);
		window.document.frmedita.saiu05mes.value = String(llave2);
		window.document.frmedita.saiu05tiporadicado.value = String(llave3);
		window.document.frmedita.saiu05consec.value = String(llave4);
		window.document.frmedita.paso.value = 1;
		window.document.frmedita.submit();
	}

	function cargarnumref() {
		window.document.frmedita.paso.value = 14;
		window.document.frmedita.submit();
	}
	
	function cargaridf3005(llave1, llave2, llave3) {
		window.document.frmedita.saiu05origenagno.value = String(llave1);
		window.document.frmedita.saiu05origenmes.value = String(llave2);
		window.document.frmedita.saiu05id.value = String(llave3);
		// window.document.frmedita.bMuestraExistentes.value = true;
		window.document.frmedita.paso.value = 3;
		window.document.frmedita.submit();
	}

	function carga_combo_saiu05idtemaorigen() {
		var params = new Array();
		params[0] = window.document.frmedita.saiu05idtiposolorigen.value;
		document.getElementById('div_saiu05idtemaorigen').innerHTML = '<b>Procesando datos, por favor espere...</b><input id="saiu05idtemaorigen" name="saiu05idtemaorigen" type="hidden" value="" />';
		xajax_f3005_Combosaiu05idtemaorigen(params);
	}

	function carga_combo_saiu05idtemafin() {
		var params = new Array();
		params[0] = window.document.frmedita.saiu05idtiposolfin.value;
		document.getElementById('div_saiu05idtemafin').innerHTML = '<b>Procesando datos, por favor espere...</b><input id="saiu05idtemafin" name="saiu05idtemafin" type="hidden" value="" />';
		xajax_f3005_Combosaiu05idtemafin(params);
	}

	function carga_combo_saiu05idequiporesp() {
		var params = new Array();
		params[0] = window.document.frmedita.saiu05idzona.value;
		document.getElementById('div_saiu05idequiporesp').innerHTML = '<b>Procesando datos, por favor espere...</b><input id="saiu05idequiporesp" name="saiu05idequiporesp" type="hidden" value="" />';
		xajax_f3005_Combosaiu05idequiporesp(params);
	}

	function paginarf3005() {
		var params = new Array();
		params[99] = window.document.frmedita.debug.value;
		params[100] = <?php echo $idTercero; ?>;
		params[101] = window.document.frmedita.paginaf3005.value;
		params[102] = window.document.frmedita.lppf3005.value;
		params[103] = window.document.frmedita.bnombre.value;
		params[104] = window.document.frmedita.blistar.value;
		document.getElementById('div_f3005detalle').innerHTML = '<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf3005" name="paginaf3005" type="hidden" value="' + params[101] + '" /><input id="lppf3005" name="lppf3005" type="hidden" value="' + params[102] + '" />';
		xajax_f3005_HtmlTabla(params);
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
		document.getElementById("saiu05agno").focus();
	}

	function buscarV2016(sCampo) {
		window.document.frmedita.iscroll.value = window.pageYOffset;
		expandesector(98);
		window.document.frmedita.scampobusca.value = sCampo;
		var params = new Array();
		params[1] = sCampo;
		//params[2]=window.document.frmedita.iagno.value;
		//params[3]=window.document.frmedita.itipo.value;
		xajax_f3005_Busquedas(params);
	}

	function retornacontrol() {
		expandesector(1);
		window.scrollTo(0, window.document.frmedita.iscroll.value);
	}

	function Devuelve(sValor) {
		var sCampo = window.document.frmedita.scampobusca.value;
		if (sCampo == 'saiu05idsolicitante') {
			ter_traerxid('saiu05idsolicitante', sValor);
		}
		if (sCampo == 'saiu05idinteresado') {
			ter_traerxid('saiu05idinteresado', sValor);
		}
		if (sCampo == 'saiu05idresponsable') {
			ter_traerxid('saiu05idresponsable', sValor);
		}
		if (sCampo == 'saiu06idusuario') {
			ter_traerxid('saiu06idusuario', sValor);
		}
		if (sCampo == 'saiu07idusuario') {
			ter_traerxid('saiu07idusuario', sValor);
		}
		if (sCampo == 'saiu07idvalidad') {
			ter_traerxid('saiu07idvalidad', sValor);
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
		if (ref == 3006) {
			if (sRetorna != '') {
				window.document.frmedita.saiu06idorigen.value = window.document.frmedita.div96v1.value;
				window.document.frmedita.saiu06idarchivo.value = sRetorna;
				verboton('beliminasaiu06idarchivo', 'block');
			}
			archivo_lnk(window.document.frmedita.saiu06idorigen.value, window.document.frmedita.saiu06idarchivo.value, 'div_saiu06idarchivo');
			paginarf3006();
		}
		if (ref == 3007) {
			/*
			if (sRetorna != '') {
				window.document.frmedita.saiu07idorigen.value = window.document.frmedita.div96v1.value;
				window.document.frmedita.saiu07idarchivo.value = sRetorna;
				verboton('beliminasaiu07idarchivo', 'block');
			}
			archivo_lnk(window.document.frmedita.saiu07idorigen.value, window.document.frmedita.saiu07idarchivo.value, 'div_saiu07idarchivo');
			*/
			paginarf3007();
		}
		MensajeAlarmaV2('', 0);
		retornacontrol();
	}

	function mod_consec() {
		ModalConfirmV2('<?php echo $ETI['msg_confirmamodconsec']; ?>', () => {
			ejecuta_modconsec();
		});
	}

	function ejecuta_modconsec() {
		MensajeAlarmaV2('<?php echo $ETI['msg_ejecutando']; ?>', 2);
		expandesector(98);
		window.document.frmedita.paso.value = 93;
		window.document.frmedita.submit();
	}

	function ajustarformarpta() {
		var iForma = window.document.frmedita.saiu05rptaforma.value;
		var sMuestra = 'none';
		if (iForma == 1) {
			sMuestra = 'block';
		}
		document.getElementById('div_saiu05rptacorreo').style.display = sMuestra;
		sMuestra = 'none';
		if (iForma == 2) {
			sMuestra = 'block';
		}
		document.getElementById('div_saiu05rptadireccion').style.display = sMuestra;
	}

	function ajustarcosto() {
		var iForma = window.document.frmedita.saiu05costogenera.value;
		var sMuestra = 'none';
		if (iForma == 1) {
			sMuestra = 'block';
		}
		document.getElementById('div_saiu05costovalor').style.display = sMuestra;
	}

	function paginarf3000() {
		var params = new Array();
		params[0] = window.document.frmedita.id11.value;
		params[1] = 3005;
		params[2] = window.document.frmedita.saiu05agno.value;
		params[3] = window.document.frmedita.saiu05id.value;
		params[99] = window.document.frmedita.debug.value;
		params[100] = window.document.frmedita.saiu05idsolicitante.value;
		params[101] = window.document.frmedita.paginaf3000.value;
		params[102] = window.document.frmedita.lppf3000.value;
		//params[103]=window.document.frmedita.bnombre3000.value;
		//params[104]=window.document.frmedita.blistar3000.value;
		document.getElementById('div_f3000detalle').innerHTML = '<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf3000" name="paginaf3000" type="hidden" value="' + params[101] + '" /><input id="lppf3000" name="lppf3000" type="hidden" value="' + params[102] + '" />';
		xajax_f3000_HtmlTabla(params);
	}

	function verinfopersonal(id) {
		var params = new Array();
		params[1] = id;
		document.getElementById('div_infopersonal').innerHTML = '<b>Procesando datos, por favor espere...</b>';
		xajax_f236_TraerInfoPersonal(params);
	}

	function realizaaccion(opcion) {
		window.document.frmopcion.opcion.value = opcion;
		window.document.frmopcion.submit();
	}

	function mod_tratadatos() {
		ModalConfirmV2('<?php echo $ETI['msg_tratadatos']; ?>');
		ModalDialogConfirm(function(confirm) {
			if (confirm) {
				document.getElementById("modal-btn-si").click();
			}
		});
	}
</script>
<?php
if ($_REQUEST['saiu05estado'] == 0) {
?>
<form class="login">
<h1 class="TituloAzul1">C&oacute;digo de referencia de consulta<br><span class="rojo"><?php echo $_REQUEST['saiu05numref']; ?></span></h1>
<div class="GrupoCampos400">
<label>Conserve este código para realizar la consulta del avance de su solicitud.</label>
</div>
<div class="salto1px"></div>
<input id="cmdFinaliza" name="cmdFinaliza" type="button" value="Finalizar" onclick="window.location.href='index.php';" class="BotonAzul">
</form>
<?php
} else {
?>
<?php
if ($_REQUEST['paso'] != 0) {
?>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>jsi/js3006.js?v=1"></script>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>jsi/js3007.js?v=1"></script>
<form id="frmimpp" name="frmimpp" method="post" action="<?php echo $APP->rutacomun; ?>p3005.php" target="_blank">
<input id="r" name="r" type="hidden" value="3005" />
<input id="id3005" name="id3005" type="hidden" value="<?php echo $_REQUEST['saiu05id']; ?>" />
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
<input id="opcion" name="opcion" type="hidden" value="<?php echo $_REQUEST['opcion'] ?>" />
<input id="aceptaterminos" name="aceptaterminos" type="hidden" value="<?php echo $_REQUEST['aceptaterminos'] ?>" />
<div id="div_sector1">
<div class="titulos">
<div class="titulosD">
<input id="cmdAyuda" name="cmdAyuda" type="button" class="btUpAyuda" onclick="muestraayuda(<?php echo $APP->idsistema . ', ' . $iCodModulo; ?>);" title="<?php echo $ETI['bt_ayuda']; ?>" value="<?php echo $ETI['bt_ayuda']; ?>" />
<?php
$bHayImprimir = false;
$sScript = 'imprimelista()';
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
<?php
if ($_REQUEST['opcion'] == 2 && $_REQUEST['saiu05estado'] == -1) {
?>
<input id="cmdGuardar" name="cmdGuardar" type="button" class="btUpGuardar" onclick="enviaguardar();" title="<?php echo $ETI['bt_guardar']; ?>" value="<?php echo $ETI['bt_guardar']; ?>" />
<?php
}
?>
<input id="cmdVolver" name="cmdVolver" type="button" class="btSupVolver" onclick="window.location.href='index.php';" title="Volver" value="Volver">
</div>
<div class="titulosI">
<?php
echo '<h2>' . $ETI['titulo_3005'] . '</h2>';
?>
</div>
</div>


<div class="areaform">
<div class="areatitulo">
<?php
$sTitulo = 'Crear PQRS';
if ($_REQUEST['saiu05id'] != '') {
	$sTitulo = 'Informaci&oacute;n PQRS';
}
echo '<h3>' . $sTitulo . '</h3>';
?>
</div>
<div class="areatrabajo">
<?php
//Div para ocultar
$bConExpande = false;
if ($bConExpande) {
?>
<div class="ir_derecha"<?php echo $sAnchoExpandeContrae; ?>>
<?php
$sEstiloExpande = ' style="display:none;"';
$sEstiloRecoje = '';
$sEstiloDiv = '';
if ($_REQUEST['boculta3005'] != 0) {
	$sEstiloExpande = '';
	$sEstiloRecoje = ' style="display:none;"';
	$sEstiloDiv = ' style="display:none;"';
}
?>
<input id="boculta3005" name="boculta3005" type="hidden" value="<?php echo $_REQUEST['boculta3005']; ?>" />
<label class="Label30">
<input id="btexpande3005" name="btexpande3005" type="button" value="Mostrar" class="btMiniExpandir" onclick="expandepanel(3005, 'block', 0);" title="<?php echo $ETI['bt_mostrar']; ?>"<?php echo $sEstiloExpande; ?>/>
</label>
<label class="Label30">
<input id="btrecoge3005" name="btrecoge3005" type="button" value="Ocultar" class="btMiniRecoger" onclick="expandepanel(3005, 'none', 1);" title="<?php echo $ETI['bt_ocultar']; ?>" <?php echo $sEstiloRecoje; ?>/>
</label>
</div>
<div id="div_p3005"<?php echo $sEstiloDiv; ?>>
<?php
}
//Mostrar formulario para editar
?>
<?php
if ($_SESSION['unad_id_tercero'] == 1) {
?>
<div class="GrupoCamposAyuda">
<?php
echo $ETI['advertencia'] . $ETI['msg_anonimo'];
?>
<div class="salto1px"></div>
</div>
<div class="salto5px"></div>
<?php
}
?>
<input id="saiu05origenagno" name="saiu05origenagno" type="hidden" value="<?php echo $_REQUEST['saiu05origenagno']; ?>" />
<input id="saiu05origenmes" name="saiu05origenmes" type="hidden" value="<?php echo $_REQUEST['saiu05origenmes']; ?>" />
<?php
if ($_REQUEST['saiu05origenid'] != 0) {
?>
<label class="Label130">
<?php
echo $ETI['saiu05origenid'];
?>
</label>
<label class="Label130">
<div id="div_saiu05origenid">
<?php
echo html_oculto('saiu05origenid', $_REQUEST['saiu05origenid']);
?>
</div>
</label>
<?php
} else {
?>
<input id="saiu05origenid" name="saiu05origenid" type="hidden" value="<?php echo $_REQUEST['saiu05origenid']; ?>" />
<?php
}
?>
<label class="Label300">
<label class="Label130">
<?php
echo $ETI['saiu05dia'];
?>
</label>
<label class="Label130">
<?php
$et_saiu05dia = fecha_armar($_REQUEST['saiu05dia'], $_REQUEST['saiu05mes'], $_REQUEST['saiu05agno']);
echo html_oculto('saiu05dia', $_REQUEST['saiu05dia'], $et_saiu05dia);
?>
</label>
</label>
<label class="Label220">
<label class="Label60">
<?php
echo $ETI['saiu05hora'];
?>
</label>
<div class="campo_HoraMin Label130" id="div_saiu05hora">
<?php
echo html_HoraMin('saiu05hora', $_REQUEST['saiu05hora'], 'saiu05minuto', $_REQUEST['saiu05minuto'], true);
?>
</div>
</label>
<label class="Label250">
<label class="Label60">
<?php
echo $ETI['saiu05estado'];
?>
</label>
<label class="Label160">
<div id="div_saiu05estado">
<?php
echo $html_saiu05estado;
?>
</div>
</label>
</label>
<div class="salto1px"></div>
<label class="Label300">
<label class="Label130">
<?php
echo $ETI['msg_numsolicitud'];
?>
</label>
<label class="Label130">
<?php
echo '<b>' . $sNumSol . '</b>';
?>
</label>
</label>
<input id="saiu05agno" name="saiu05agno" type="hidden" value="<?php echo $_REQUEST['saiu05agno']; ?>" />
<input id="saiu05mes" name="saiu05mes" type="hidden" value="<?php echo $_REQUEST['saiu05mes']; ?>" />
<input id="saiu05tiporadicado" name="saiu05tiporadicado" type="hidden" value="<?php echo $_REQUEST['saiu05tiporadicado']; ?>" />
<input id="saiu05consec" name="saiu05consec" type="hidden" value="<?php echo $_REQUEST['saiu05consec']; ?>" />
<label class="Label220">
<label class="Label60">
<?php
echo $ETI['saiu05id'];
?>
</label>
<label class="Label130">
<?php
echo html_oculto('saiu05id', $_REQUEST['saiu05id'], formato_numero($_REQUEST['saiu05id']));
?>
</label>
</label>
<label class="Label250">
<label class="Label60">
<?php
echo $ETI['saiu05idcategoria'];
?>
</label>
<label class="Label160">
<?php
echo $html_saiu05idcategoria;
?>
</label>
</label>
<div class="salto1px"></div>

<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo $ETI['saiu05idsolicitante'];
?>
</label>
<div class="salto1px"></div>
<input id="saiu05idsolicitante" name="saiu05idsolicitante" type="hidden" value="<?php echo $_REQUEST['saiu05idsolicitante']; ?>" />
<?php
if ($_REQUEST['saiu05idsolicitante'] > 1) {
?>
<div id="div_saiu05idsolicitante_llaves">
<?php
$bOculto = true;
echo html_DivTerceroV2('saiu05idsolicitante', $_REQUEST['saiu05idsolicitante_td'], $_REQUEST['saiu05idsolicitante_doc'], $bOculto, 0, $ETI['ing_doc']);
?>
</div>
<div class="salto1px"></div>
<?php
} else {
?>
<input name="saiu05idsolicitante_td" type="hidden" id="saiu05idsolicitante_td" value="<?php echo $_REQUEST['saiu05idsolicitante_td']; ?>">
<input name="saiu05idsolicitante_doc" type="hidden" id="saiu05idsolicitante_doc" value="<?php echo $_REQUEST['saiu05idsolicitante_doc']; ?>">
<?php
}
?>
<div id="div_saiu05idsolicitante" class="L"><?php echo $saiu05idsolicitante_rs; ?></div>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['saiu05tipointeresado'];
?>
</label>
<label class="Label160">
<?php
echo $html_saiu05tipointeresado;
?>
</label>
<div class="salto1px"></div>
<?php
if ($bConMedio) {
?>
<label class="L">
<?php
echo $ETI['saiu05idmedio'] . ' ' . $html_saiu05idmedio;
?>
</label>
<?php
} else {
?>
<input id="saiu05idmedio" name="saiu05idmedio" type="hidden" value="<?php echo $_REQUEST['saiu05idmedio']; ?>" />
<?php
}
?>
<div class="salto1px"></div>
<?php
if ($_SESSION['unad_id_tercero'] > 0) {
	$sHref = 'javascript:;';
	$sOnclick = 'mod_tratadatos();';
	$sTarget =  '';
?>
<a id="cmdTrataDatos" name="cmdTrataDatos" class="BotonAzul200" title="<?php echo $ETI['bt_tratadatos']; ?>" href="<?php echo $sHref; ?>" target="<?php echo $sTarget; ?>" onclick="<?php echo $sOnclick; ?>" style="width:420px; padding:7px 10px;"><?php echo $ETI['bt_tratadatos']; ?></a>
<div class="salto1px"></div>
<?php
}
?>
</div>



<div class="GrupoCampos520">
<label class="Label200">
<?php
echo $ETI['saiu05numref'];
?>
</label>
<label class="Label200">
<div id="div_saiu05numref">
<?php
echo html_oculto('saiu05numref', $_REQUEST['saiu05numref']);
?>
</div>
</label>
<div class="salto1px"></div>
<?php
if ($_REQUEST['saiu05idmoduloproc'] != 0) {
?>
<label class="Label130">
<?php
echo $ETI['saiu05idmoduloproc'];
?>
</label>
<label class="Label130">
<?php
echo html_oculto('saiu05idmoduloproc', $_REQUEST['saiu05idmoduloproc']);
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu05identificadormod'];
?>
</label>
<label class="Label130">
<?php
echo html_oculto('saiu05identificadormod', $_REQUEST['saiu05identificadormod']);
?>
</label>
<?php
} else {
?>
<input id="saiu05idmoduloproc" name="saiu05idmoduloproc" type="hidden" value="<?php echo $_REQUEST['saiu05idmoduloproc']; ?>" />
<input id="saiu05identificadormod" name="saiu05identificadormod" type="hidden" value="<?php echo $_REQUEST['saiu05identificadormod']; ?>" />
<?php
}
?>
<div class="salto1px"></div>
<?php
if ($_REQUEST['saiu05numradicado'] != 0) {
?>
<label class="Label200">
<?php
echo $ETI['saiu05numradicado'];
?>
</label>
<label class="Label130">
<?php
echo html_oculto('saiu05numradicado', $_REQUEST['saiu05numradicado']);
?>
</label>
<?php
} else {
?>
<input id="saiu05numradicado" name="saiu05numradicado" type="hidden" value="<?php echo $_REQUEST['saiu05numradicado']; ?>" />
<?php
}
?>
<div class="salto1px"></div>
</div>




<div class="GrupoCampos520">
<label class="Label130">
<?php
echo $ETI['saiu05idtiposolorigen'];
?>
</label>
<label class="Label350">
<?php
echo $html_saiu05idtiposolorigen;
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['saiu05idtemaorigen'];
?>
</label>
<label class="Label350">
<div id="div_saiu05idtemaorigen">
<?php
echo $html_saiu05idtemaorigen;
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label250">
<?php
echo $ETI['saiu05rptaforma'];
?>
</label>
<label class="Label160">
<?php
echo $html_saiu05rptaforma;
?>
</label>
<div class="salto1px"></div>
<?php
$sEstilo = ' style="display:none"';
if ($_REQUEST['saiu05rptaforma'] == 1) {
	$sEstilo = ' style="display:block"';
}
?>
<div id="div_saiu05rptacorreo" <?php echo $sEstilo; ?>>
<label class="Label250">
<?php
echo $ETI['saiu05rptacorreo'];
?>
</label>
<label class="Label250">
<input id="saiu05rptacorreo" name="saiu05rptacorreo" type="text" value="<?php echo $_REQUEST['saiu05rptacorreo']; ?>" maxlength="50" placeholder="<?php echo $ETI['ing_campo'] . $ETI['saiu05rptacorreo']; ?>" />
</label>
</div>
<?php
$sEstilo = ' style="display:none"';
if ($_REQUEST['saiu05rptaforma'] == 2) {
	$sEstilo = ' style="display:block"';
}
?>
<div id="div_saiu05rptadireccion" <?php echo $sEstilo; ?>>
<label class="L">
<?php
echo $ETI['saiu05rptadireccion'];
?>

<input id="saiu05rptadireccion" name="saiu05rptadireccion" type="text" value="<?php echo $_REQUEST['saiu05rptadireccion']; ?>" maxlength="100" class="L" placeholder="<?php echo $ETI['ing_campo'] . $ETI['saiu05rptadireccion']; ?>" />
</label>
</div>
<?php
if (false) {
?>
<div class="salto1px"></div>
<label class="Label250">
<?php
echo $ETI['saiu05costogenera'];
?>
</label>
<label class="Label160">
<?php
echo $html_saiu05costogenera;
?>
</label>
<div class="salto1px"></div>
<?php
$sEstilo = ' style="display:none"';
if ($_REQUEST['saiu05costogenera'] == 1) {
	$sEstilo = '';
}
?>
<div id="div_saiu05costovalor" <?php echo $sEstilo; ?>>
<label class="Label250">
<?php
echo $ETI['saiu05costovalor'];
?>
</label>
<label class="Label160">
<input id="saiu05costovalor" name="saiu05costovalor" type="text" value="<?php echo formato_numero($_REQUEST['saiu05costovalor'], 2); ?>" class="veinte" maxlength="15" style="text-align:right" onchange="formatea_moneda(this);" placeholder="<?php echo $ETI['ing_vr']; ?>" />
</label>
<input id="saiu05costorefpago" name="saiu05costorefpago" type="hidden" value="<?php echo $_REQUEST['saiu05costorefpago']; ?>" />
</div>
<?php
} else {
?>
<input id="saiu05costogenera" name="saiu05costogenera" type="hidden" value="<?php echo $_REQUEST['saiu05costogenera']; ?>" />
<input id="saiu05costovalor" name="saiu05costovalor" type="hidden" value="<?php echo $_REQUEST['saiu05costovalor']; ?>" />
<input id="saiu05costorefpago" name="saiu05costorefpago" type="hidden" value="<?php echo $_REQUEST['saiu05costorefpago']; ?>" />
<?php
}
?>
<div class="salto1px"></div>
</div>
<input id="saiu05idtemafin" name="saiu05idtemafin" type="hidden" value="<?php echo $_REQUEST['saiu05idtemafin']; ?>" />
<input id="saiu05idtiposolfin" name="saiu05idtiposolfin" type="hidden" value="<?php echo $_REQUEST['saiu05idtiposolfin']; ?>" />



<div class="salto1px"></div>
<?php
if (false) {
?>
<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo $ETI['titulo_3000'];
?>
</label>
<input id="boculta3000" name="boculta3000" type="hidden" value="<?php echo $_REQUEST['boculta3000']; ?>" />
<div class="salto1px"></div>
<div id="div_f3000detalle">
<?php
echo $sTabla3000;
?>
</div>
<div class="salto1px"></div>
</div>
<?php
}
?>



<?php
if (false) {
?>
<div class="salto1px"></div>
<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo $ETI['saiu05idinteresado'];
?>
</label>
<div class="salto1px"></div>
<input id="saiu05idinteresado" name="saiu05idinteresado" type="hidden" value="<?php echo $_REQUEST['saiu05idinteresado']; ?>" />
<div id="div_saiu05idinteresado_llaves">
<?php
$bOculto = true;
if ($_REQUEST['paso'] != 2) {
$bOculto = false;
}
echo html_DivTerceroV2('saiu05idinteresado', $_REQUEST['saiu05idinteresado_td'], $_REQUEST['saiu05idinteresado_doc'], $bOculto, 0, $ETI['ing_doc']);
?>
</div>
<div class="salto1px"></div>
<div id="div_saiu05idinteresado" class="L"><?php echo $saiu05idinteresado_rs; ?></div>
<div class="salto1px"></div>
</div>
<?php
} else {
?>
<input id="saiu05idinteresado" name="saiu05idinteresado" type="hidden" value="<?php echo $_REQUEST['saiu05idinteresado']; ?>" />
<input id="saiu05idinteresado_td" name="saiu05idinteresado_td" type="hidden" value="<?php echo $_REQUEST['saiu05idinteresado_td']; ?>" />
<input id="saiu05idinteresado_doc" name="saiu05idinteresado_doc" type="hidden" value="<?php echo $_REQUEST['saiu05idinteresado_doc']; ?>" />
<?php
}
?>
<input id="saiu05prioridad" name="saiu05prioridad" type="hidden" value="<?php echo $_REQUEST['saiu05prioridad']; ?>" />
<input id="saiu05idzona" name="saiu05idzona" type="hidden" value="<?php echo $_REQUEST['saiu05idzona']; ?>" />
<input id="saiu05cead" name="saiu05cead" type="hidden" value="<?php echo $_REQUEST['saiu05cead']; ?>" />
<div>







</div>
<div class="salto1px"></div>
<label class="txtAreaS">
<?php
echo $ETI['saiu05detalle'];
?>
<textarea id="saiu05detalle" name="saiu05detalle" placeholder="<?php echo $ETI['ing_campo'] . $ETI['saiu05detalle']; ?>"><?php echo $_REQUEST['saiu05detalle']; ?></textarea>
</label>
<input id="saiu05infocomplemento" name="saiu05infocomplemento" type="hidden" value="<?php echo $_REQUEST['saiu05infocomplemento']; ?>" />
<div class="salto1px"></div>
<?php
if (false) {
?>
<input id="saiu05idescuela" name="saiu05idescuela" type="hidden" value="<?php echo $_REQUEST['saiu05idescuela']; ?>" />
<input id="saiu05idprograma" name="saiu05idprograma" type="hidden" value="<?php echo $_REQUEST['saiu05idprograma']; ?>" />
<input id="saiu05idperiodo" name="saiu05idperiodo" type="hidden" value="<?php echo $_REQUEST['saiu05idperiodo']; ?>" />
<input id="saiu05idcurso" name="saiu05idcurso" type="hidden" value="<?php echo $_REQUEST['saiu05idcurso']; ?>" />
<input id="saiu05idgrupo" name="saiu05idgrupo" type="hidden" value="<?php echo $_REQUEST['saiu05idgrupo']; ?>" />
<?php
}
?>
<input id="saiu05tiemprespdias" name="saiu05tiemprespdias" type="hidden" value="<?php echo $_REQUEST['saiu05tiemprespdias']; ?>" />
<input id="saiu05tiempresphoras" name="saiu05tiempresphoras" type="hidden" value="<?php echo $_REQUEST['saiu05tiempresphoras']; ?>" />
<input id="saiu05fecharespprob" name="saiu05fecharespprob" type="hidden" value="<?php echo $_REQUEST['saiu05fecharespprob']; ?>" />
<input id="saiu05respuesta" name="saiu05respuesta" type="hidden" value="<?php echo $_REQUEST['saiu05respuesta']; ?>" />
<?php
// -- Inicia Grupo campos 3007 Anexos
?>
<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo $ETI['titulo_3007'];
?>
</label>
<?php
if ($_REQUEST['paso'] == 0) {
?>
<div class="salto1px"></div>
<div class="GrupoCamposAyuda">
<?php
echo $ETI['saiu04guardarprimero'];
?>
<div class="salto1px"></div>
</div>
<?php
}
?>
<input id="boculta3007" name="boculta3007" type="hidden" value="<?php echo $_REQUEST['boculta3007']; ?>" />
<?php
if ($_REQUEST['paso'] == 2) {
$bCondicion = false;
if ($bCondicion){
?>
<div class="ir_derecha" style="width:62px;">
<!--
<label class="Label30">
<input id="btexcel3007" name="btexcel3007" type="button" value="Exportar" class="btMiniExcel" onclick="imprime3007();" title="Exportar"/>
</label>
-->
<label class="Label30">
<input id="btexpande3007" name="btexpande3007" type="button" value="Expandir" class="btMiniExpandir" onclick="expandepanel(3007,'block',0);" title="<?php echo $ETI['bt_mostrar']; ?>" style="display:<?php if ($_REQUEST['boculta3007'] == 0) {
echo 'none';
} else {
echo 'block';
} ?>;" />
</label>
<label class="Label30">
<input id="btrecoge3007" name="btrecoge3007" type="button" value="Recoger" class="btMiniRecoger" onclick="expandepanel(3007,'none',1);" title="<?php echo $ETI['bt_ocultar']; ?>" style="display:<?php if ($_REQUEST['boculta3007'] == 0) {
echo 'block';
} else {
echo 'none';
} ?>;" />
</label>
</div>
<div class="salto1px"></div>
<div id="div_p3007" style="display:<?php if ($_REQUEST['boculta3007'] == 0) {
echo 'block';
} else {
echo 'none';
} ?>;">
<label class="Label130">
<?php
echo $ETI['saiu07consec'];
?>
</label>
<label class="Label130">
<div id="div_saiu07consec">
<?php
if ((int)$_REQUEST['saiu07id'] == 0) {
?>
<input id="saiu07consec" name="saiu07consec" type="text" value="<?php echo $_REQUEST['saiu07consec']; ?>" onchange="revisaf3007()" class="cuatro" />
<?php
} else {
echo html_oculto('saiu07consec', $_REQUEST['saiu07consec'], formato_numero($_REQUEST['saiu07consec']));
}
?>
</div>
</label>
<label class="Label60">
<?php
echo $ETI['saiu07id'];
?>
</label>
<label class="Label60">
<div id="div_saiu07id">
<?php
echo html_oculto('saiu07id', $_REQUEST['saiu07id'], formato_numero($_REQUEST['saiu07id']));
?>
</div>
</label>
<label class="Label130">
<?php
echo $ETI['saiu07idtipoanexo'];
?>
</label>
<label>
<?php
echo $html_saiu07idtipoanexo;
?>
</label>
<label class="L">
<?php
echo $ETI['saiu07detalle'];
?>

<input id="saiu07detalle" name="saiu07detalle" type="text" value="<?php echo $_REQUEST['saiu07detalle']; ?>" maxlength="100" class="L" placeholder="<?php echo $ETI['ing_campo'] . $ETI['saiu07detalle']; ?>" />
</label>
<input id="saiu07idorigen" name="saiu07idorigen" type="hidden" value="<?php echo $_REQUEST['saiu07idorigen']; ?>" />
<input id="saiu07idarchivo" name="saiu07idarchivo" type="hidden" value="<?php echo $_REQUEST['saiu07idarchivo']; ?>" />
<div class="GrupoCampos300">
<div class="salto1px"></div>
<div id="div_saiu07idarchivo" class="Campo220">
<?php
echo html_lnkarchivo((int)$_REQUEST['saiu07idorigen'], (int)$_REQUEST['saiu07idarchivo']);
?>
</div>
<label class="Label30">
<input type="button" id="banexasaiu07idarchivo" name="banexasaiu07idarchivo" value="Anexar" class="btAnexarS" onclick="carga_saiu07idarchivo()" title="Cargar archivo" style="display:<?php if ((int)$_REQUEST['saiu07id'] != 0) {
echo 'block';
} else {
echo 'none';
} ?>;" />
</label>
<label class="Label30">
<input type="button" id="beliminasaiu07idarchivo" name="beliminasaiu07idarchivo" value="Eliminar" class="btBorrarS" onclick="eliminasaiu07idarchivo()" title="Eliminar archivo" style="display:<?php if ((int)$_REQUEST['saiu07idarchivo'] != 0) {
echo 'block';
} else {
echo 'none';
} ?>;" />
</label>
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo $ETI['saiu07idusuario'];
?>
</label>
<div class="salto1px"></div>
<input id="saiu07idusuario" name="saiu07idusuario" type="hidden" value="<?php echo $_REQUEST['saiu07idusuario']; ?>" />
<div id="div_saiu07idusuario_llaves">
<?php
$bOculto = false;
echo html_DivTerceroV2('saiu07idusuario', $_REQUEST['saiu07idusuario_td'], $_REQUEST['saiu07idusuario_doc'], $bOculto, 0, $ETI['ing_doc']);
?>
</div>
<div class="salto1px"></div>
<div id="div_saiu07idusuario" class="L"><?php echo $saiu07idusuario_rs; ?></div>
<div class="salto1px"></div>
</div>
<label class="Label130">
<?php
echo $ETI['saiu07fecha'];
?>
</label>
<div class="Campo220">
<?php
echo html_FechaEnNumero('saiu07fecha', $_REQUEST['saiu07fecha']); //$bvacio=false,$accion=",$iagnoini=0,$iagnofin=0
?>
</div>
<!--
<label class="Label30">
<input id="bsaiu07fecha_hoy" name="bsaiu07fecha_hoy" type="button" value="Hoy" class="btMiniHoy" onclick="fecha_AsignarNum('saiu07fecha','<?php echo fecha_DiaMod(); ?>')" title="<?php echo $ETI['bt_hoy']; ?>"/>
</label>
-->
<label class="Label130">
<?php
echo $ETI['saiu07hora'];
?>
</label>
<div class="campo_HoraMin" id="div_saiu07hora">
<?php
echo html_HoraMin('saiu07hora', $_REQUEST['saiu07hora'], 'saiu07minuto', $_REQUEST['saiu07minuto']);
?>
</div>
<label class="Label130">
<?php
echo $ETI['saiu07estado'];
?>
</label>
<label>
<div id="div_saiu07estado">
<?php
echo $html_saiu07estado;
?>
</div>
</label>
<div class="salto1px"></div>
<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo $ETI['saiu07idvalidad'];
?>
</label>
<div class="salto1px"></div>
<input id="saiu07idvalidad" name="saiu07idvalidad" type="hidden" value="<?php echo $_REQUEST['saiu07idvalidad']; ?>" />
<div id="div_saiu07idvalidad_llaves">
<?php
$bOculto = false;
echo html_DivTerceroV2('saiu07idvalidad', $_REQUEST['saiu07idvalidad_td'], $_REQUEST['saiu07idvalidad_doc'], $bOculto, 0, $ETI['ing_doc']);
?>
</div>
<div class="salto1px"></div>
<div id="div_saiu07idvalidad" class="L"><?php echo $saiu07idvalidad_rs; ?></div>
<div class="salto1px"></div>
</div>
<label class="Label130">
<?php
echo $ETI['saiu07fechavalida'];
?>
</label>
<div class="Campo220">
<?php
echo html_FechaEnNumero('saiu07fechavalida', $_REQUEST['saiu07fechavalida']); //$bvacio=false,$accion=",$iagnoini=0,$iagnofin=0
?>
</div>
<!--
<label class="Label30">
<input id="bsaiu07fechavalida_hoy" name="bsaiu07fechavalida_hoy" type="button" value="Hoy" class="btMiniHoy" onclick="fecha_AsignarNum('saiu07fechavalida','<?php echo fecha_DiaMod(); ?>')" title="<?php echo $ETI['bt_hoy']; ?>"/>
</label>
-->
<label class="Label130">
<?php
echo $ETI['saiu07horavalida'];
?>
</label>
<div class="campo_HoraMin" id="div_saiu07horavalida">
<?php
echo html_HoraMin('saiu07horavalida', $_REQUEST['saiu07horavalida'], 'saiu07minvalida', $_REQUEST['saiu07minvalida']);
?>
</div>
<div class="salto1px"></div>
<label class="Label130">&nbsp;</label>
<label class="Label30">
<input id="bguarda3007" name="bguarda3007" type="button" value="Guardar" class="btMiniGuardar" onclick="guardaf3007()" title="<?php echo $ETI['bt_mini_guardar_3007']; ?>" />
</label>
<label class="Label30">
<input id="blimpia3007" name="blimpia3007" type="button" value="Limpiar" class="btMiniLimpiar" onclick="limpiaf3007()" title="<?php echo $ETI['bt_mini_limpiar_3007']; ?>" />
</label>
<label class="Label30">
<input id="belimina3007" name="belimina3007" type="button" value="Eliminar" class="btMiniEliminar" onclick="eliminaf3007()" title="<?php echo $ETI['bt_mini_eliminar_3007']; ?>" style="display:<?php if ((int)$_REQUEST['saiu07id'] != 0) {
echo 'block';
} else {
echo 'none';
} ?>;" />
</label>
<?php
//Este es el cierre del div_p3007
?>
<div class="salto1px"></div>
</div>
<?php
} else { //Termina el segundo bloque  condicional - bloque editar.
?>
<input id="saiu07idorigen" name="saiu07idorigen" type="hidden" value="<?php echo $_REQUEST['saiu07idorigen']; ?>" />
<input id="saiu07idarchivo" name="saiu07idarchivo" type="hidden" value="<?php echo $_REQUEST['saiu07idarchivo']; ?>" />
<input id="saiu07idusuario" name="saiu07idusuario" type="hidden" value="<?php echo $_REQUEST['saiu07idusuario']; ?>" />
<input id="saiu07idvalidad" name="saiu07idvalidad" type="hidden" value="<?php echo $_REQUEST['saiu07idvalidad']; ?>" />
<?php
}
?>
<div class="salto1px"></div>
<div id="div_f3007detalle">
<?php
echo $sTabla3007;
?>
</div>
<?php
}
?>
<div class="salto1px"></div>
</div>
<?php
// -- Termina Grupo campos 3007 Anexos
?>
<div class="salto5px"></div>
<?php
if ($_REQUEST['paso'] == 0) {
?>
<label class="Label320"></label>
<label class="Label60"></label>
<label class="Label160">
<input id="cmdGuardar" name="cmdGuardar" type="button" class="BotonAzul160" value="<?php echo $ETI['bt_guardar']; ?>" onclick="enviaguardar();" title="<?php echo $ETI['bt_guardar']; ?>" />
</label>
<div class="salto1px"></div>
<?php
} else {
	switch ($_REQUEST['saiu05estado']) {
		case -1: // Borrador
?>
<label class="Label320"></label>
<label class="Label60"></label>
<label class="Label160">
<input id="cmdSolicitar" name="cmdSolicitar" type="button" class="BotonAzul160" value="<?php echo $ETI['bt_solicitar']; ?>" onclick="enviacerrar();" title="<?php echo $ETI['bt_solicitar']; ?>" />
</label>
<div class="salto1px"></div>
<?php
		break;
	}
}
?>
<?php
if (false) {
?>
<?php
// -- Inicia Grupo campos 3006 Anotaciones
?>
<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo $ETI['titulo_3006'];
?>
</label>
<input id="boculta3006" name="boculta3006" type="hidden" value="<?php echo $_REQUEST['boculta3006']; ?>" />
<input id="boculta3006_Campos" name="boculta3006_Campos" type="hidden" value="<?php echo $_REQUEST['boculta3006_Campos']; ?>" />
<?php
if ($_REQUEST['paso'] == 2) {
//if ($bCondicion){
?>
<div class="ir_derecha" style="width:62px;">
<!--
<label class="Label30">
<input id="btexcel3006" name="btexcel3006" type="button" value="Exportar" class="btMiniExcel" onclick="imprime3006();" title="Exportar"/>
</label>
-->
<label class="Label30">
<input id="btexpande3006" name="btexpande3006" type="button" value="Expandir" class="btMiniExpandir" onclick="expandepanel(3006,'block',0);" title="<?php echo $ETI['bt_mostrar']; ?>" style="display:<?php if ($_REQUEST['boculta3006'] == 0) {
echo 'none';
} else {
echo 'block';
} ?>;" />
</label>
<label class="Label30">
<input id="btrecoge3006" name="btrecoge3006" type="button" value="Recoger" class="btMiniRecoger" onclick="expandepanel(3006,'none',1);" title="<?php echo $ETI['bt_ocultar']; ?>" style="display:<?php if ($_REQUEST['boculta3006'] == 0) {
echo 'block';
} else {
echo 'none';
} ?>;" />
</label>
</div>
<div class="salto1px"></div>
<div id="div_p3006" style="display:<?php if ($_REQUEST['boculta3006'] == 0) {
echo 'block';
} else {
echo 'none';
} ?>;">
<div id="div_p3006_Campos" style="display:<?php if ($_REQUEST['boculta3006_Campos'] == 0) {
echo 'block';
} else {
echo 'none';
} ?>;">
<label class="Label130">
<?php
echo $ETI['saiu06consec'];
?>
</label>
<label class="Label130">
<div id="div_saiu06consec">
<?php
if ((int)$_REQUEST['saiu06id'] == 0) {
?>
<input id="saiu06consec" name="saiu06consec" type="text" value="<?php echo $_REQUEST['saiu06consec']; ?>" onchange="revisaf3006()" class="cuatro" />
<?php
} else {
echo html_oculto('saiu06consec', $_REQUEST['saiu06consec'], formato_numero($_REQUEST['saiu06consec']));
}
?>
</div>
</label>
<label class="Label60">
<?php
echo $ETI['saiu06id'];
?>
</label>
<label class="Label60">
<div id="div_saiu06id">
<?php
echo html_oculto('saiu06id', $_REQUEST['saiu06id'], formato_numero($_REQUEST['saiu06id']));
?>
</div>
</label>
<label class="Label90">
<?php
echo $ETI['saiu06fecha'];
?>
</label>
<div class="Campo220" id="div_saiu06fecha">
<?php
echo html_oculto('saiu06fecha', $_REQUEST['saiu06fecha'], $_REQUEST['saiu06fecha']);
?>
</div>
<div class="campo_HoraMin" id="div_saiu06hora">
<?php
echo html_HoraMin('saiu06hora', $_REQUEST['saiu06hora'], 'saiu06minuto', $_REQUEST['saiu06minuto'], true);
?>
</div>
<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="txtAreaS">
<?php
echo $ETI['saiu06anotacion'];
?>
<textarea id="saiu06anotacion" name="saiu06anotacion" placeholder="<?php echo $ETI['ing_campo'] . $ETI['saiu06anotacion']; ?>"><?php echo $_REQUEST['saiu06anotacion']; ?></textarea>
</label>
<input id="saiu06idorigen" name="saiu06idorigen" type="hidden" value="<?php echo $_REQUEST['saiu06idorigen']; ?>" />
<input id="saiu06idarchivo" name="saiu06idarchivo" type="hidden" value="<?php echo $_REQUEST['saiu06idarchivo']; ?>" />
<div class="salto5px"></div>
</div>
<div class="salto5px"></div>
</div>
<div id="div_f3006detalle">
<?php
echo $sTabla3006;
?>
</div>
<div class="salto5px"></div>
<?php
//Este es el cierre del div_p3006
?>
<div class="salto1px"></div>
</div>
<?php
//} //Termina el segundo bloque  condicional - bloque editar.
?>
<?php
}
?>
<div class="salto1px"></div>
</div>
<?php
// -- Termina Grupo campos 3006 Anotaciones
?>
<?php
}
?>
<input id="saiu05idunidadresp" name="saiu05idunidadresp" type="hidden" value="<?php echo $_REQUEST['saiu05idunidadresp']; ?>" />
<input id="saiu05idequiporesp" name="saiu05idequiporesp" type="hidden" value="<?php echo $_REQUEST['saiu05idequiporesp']; ?>" />
<input id="saiu05idsupervisor" name="saiu05idsupervisor" type="hidden" value="<?php echo $_REQUEST['saiu05idsupervisor']; ?>" />
<input id="saiu05idresponsable" name="saiu05idresponsable" type="hidden" value="<?php echo $_REQUEST['saiu05idresponsable']; ?>" />
<?php
if (false) {
	//Ejemplo de boton de ayuda
	//echo html_BotonAyuda('NombreCampo');
	//echo html_DivAyudaLocal('NombreCampo');
}
if ($bConExpande) {
	//Este es el cierre del div_p3005
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
<?php
if ($_REQUEST['bMuestraExistentes']) {
// Inicio Area de Datos Existentes
?>
<input id="bMuestraExistentes" name="bMuestraExistentes" type="hidden" value="<?php echo $_REQUEST['bMuestraExistentes']; ?>" />
<input id="sTabla3005" name="sTabla3005" type="hidden" value="<?php echo htmlentities($_REQUEST['sTabla3005'], ENT_QUOTES); ?>" />
<div class="areaform">
<div class="areatitulo">
<?php
echo '<h3>' . $ETI['bloque1'] . '</h3>';
?>
</div>
<div class="areatrabajo">
<?php
echo ' ' . $csv_separa;
?>
<div id="div_f3005detalle">
<?php
echo $sTabla3005;
?>
</div>
<?php
// Termina el div_areatrabajo y DIV_areaform
?>
</div>
</div>
<?php
}
// Fin Area de Datos Existentes
?>



</div>


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


<div id="div_sector93" style="display:none">
<?php
$objForma = new clsHtmlForma($iPiel);
$objForma->addBoton('cmdAyuda93', 'btSupAyuda', 'muestraayuda(' . $iCodModulo . ');', $ETI['bt_ayuda']);
$objForma->addBoton('cmdVolverSec93', 'btSupVolver', 'expandesector(1);', $ETI['bt_volver']);
echo $objForma->htmlTitulo('' . $ETI['titulo_sector93'] . '', $iCodModulo);
echo $objForma->htmlInicioMarco();
?>
<label class="Label160">
<?php
echo $ETI['msg_saiu05consec'];
?>
</label>
<label class="Label90">
<?php
echo '<b>' . $_REQUEST['saiu05consec'] . '</b>';
?>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['msg_saiu05consec_nuevo'];
// onchange="RevisaConsec()"
?>
</label>
<label class="Label90">
<input id="saiu05consec_nuevo" name="saiu05consec_nuevo" type="text" value="<?php echo $_REQUEST['saiu05consec_nuevo']; ?>" class="cuatro" />
</label>
<div class="salto1px"></div>
<label class="Label160">&nbsp;</label>
<?php
echo $objForma->htmlBotonSolo('cmdCambiaConsecFinal', 'botonProceso', 'mod_consec();', $ETI['bt_cambiar'], 130);
echo $objForma->htmlFinMarco();
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
<input id="titulo_3005" name="titulo_3005" type="hidden" value="<?php echo $ETI['titulo_3005']; ?>" />
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
echo '<h2>' . $ETI['titulo_3005'] . '</h2>';
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
echo '<h2>' . $ETI['titulo_3005'] . '</h2>';
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
} // Cierre validación saiu05idestado
?>

<?php
// Termina el bloque div_interna
?>
</div>
<div class="flotante">
<?php
if ($_REQUEST['opcion'] == 2 && $_REQUEST['saiu05estado'] == -1) {
?>
<input id="cmdGuardarf" name="cmdGuardarf" type="button" class="btSoloGuardar" onClick="enviaguardar();" value="<?php echo $ETI['bt_guardar']; ?>" />
<?php
}
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
<?php
if ($_REQUEST['paso'] == 0) {
?>
<script language="javascript">
$().ready(function() {
jQuery("#saiu05idtiposolorigen, #saiu05idtemaorigen").chosen({
	no_results_text: "No existen coincidencias: ",
	width: "100%"});
});
<?php
if ($_SESSION['unad_id_tercero'] > 1) {
?>
ter_muestra('saiu05idsolicitante', 0);
<?php
}
?>
</script>
<?php
}
?>
<script language="javascript" src="ac_3005.js?v=1"></script>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>unad_todas.js?ver=8"></script>
<script>
$().ready(function() {
<?php
if ($_SESSION['unad_id_tercero'] == 1 && $_REQUEST['saiu05estado'] == -1) {
?>
ModalMensaje("<?php echo $ETI['msg_anonimo']; ?>");
<?php
}
?>
});
</script>
<?php
forma_piedepagina();
?>