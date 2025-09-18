<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2024 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Version 3.0.11 viernes, 2 de agosto de 2024
*/
/*
error_reporting(E_ALL);
ini_set("display_errors", 1);
*/
if (file_exists('./err_control.php')) {
	require './err_control.php';
}
if (!file_exists('./app.php')) {
	echo '<b>Error N 1 de instalaci&oacute;n</b><br>No se ha establecido un archivo de configuraci&oacute;n, por favor comuniquese con el administrador del sistema.';
	die();
}
mb_internal_encoding('UTF-8');
require './app.php';
require $APP->rutacomun . 'unad_todas.php';
require $APP->rutacomun . 'libs/clsdbadmin.php';
require $APP->rutacomun . 'unad_librerias.php';
require $APP->rutacomun . 'libaurea.php';
require $APP->rutacomun . 'libdatos.php';
require $APP->rutacomun . 'libexcel_ss.php';
require $APP->rutacomun . 'vendor/autoload.php';
require $APP->rutacomun . 'lib111.php';
if ((int)$_SESSION['unad_id_tercero'] == 0) {
	die();
} else {
	$idTercero = numeros_validar($_SESSION['unad_id_tercero']);
	if ($idTercero != $_SESSION['unad_id_tercero']) {
		die();
	}
}

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$_SESSION['u_ultimominuto'] = iminutoavance();
$sError = '';
$iReporte = 0;
$bDebug = false;
if (isset($_REQUEST['clave']) == 0) {
	$_REQUEST['clave'] = '';
}
if (isset($_REQUEST['rdebug']) == 0) {
	$_REQUEST['rdebug'] = 0;
}
$aNombres = array(
	'', '', '', 'Nombre', 'Listar',
	'Estado', 'Documento', 'Tipo', 'Motivo', 'Unidad',
	'Responsable', 'Zona'
);
$aTipos = array(
	0, 0, 0, 1, 0,
	0, 1, 0, 0, 0,
	0, 0
);
$iNumVariables = 11;
for ($k = 3; $k <= $iNumVariables; $k++) {
	if (isset($_REQUEST['v' . $k]) == 0) {
		$_REQUEST['v' . $k] = '';
	} else {
		//Validar las variables.
		if ($aTipos[$k] == 1) {
			$vVr = cadena_Validar($_REQUEST['v' . $k]);
		} else {
			$vVr = numeros_validar($_REQUEST['v' . $k]);
		}
		if ($vVr != $_REQUEST['v' . $k]) {
			$sError = 'No fue posible validar el contenido de la variable ' . $aNombres[$k];
		}
	}
}
$sDebug = '';
if ($sError == '') {
	for ($k = 3; $k <= $iNumVariables; $k++) {
		switch ($k) {
			case 31: //Variable tipo texto
				$iVr = cadena_Validar($_REQUEST['v' . $k]);
				break;
			default:
				$iVr = numeros_validar($_REQUEST['v' . $k]);
				break;
		}
		if ($iVr != $_REQUEST['v' . $k]) {
			$sError = 'No fue posible validar el contenido de la variable ' . $k . '';
			$k = $iNumVariables + 1;
		}
	}
}
if ($sError == '') {
	//Validar permisos.
	$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto != '') {
		$objDB->dbPuerto = $APP->dbpuerto;
	}
	list($bEntra, $sDebugP) = seg_revisa_permisoV3(3047, 6, $idTercero, $objDB);
	if (!$bEntra) {
		$sError = 'No tiene permiso para consultar este reporte [Mod 3047 : 6]';
	}
}
if ($sError == '') {
	if ($_REQUEST['rdebug'] == 1) {
		$bDebug = true;
	}
	$sTituloRpt = 'Tramites';
	$sFormato = 'formato.xlsx';
	if (!file_exists($sFormato)) {
		$sError = 'Formato no encontrado {' . $sFormato . '}';
	}
}
if ($sError == '') {
	// ------------------------------------------------
	// Leemos los parametros de entrada.
	// ------------------------------------------------
	$bNombre = cadena_Validar(trim($_REQUEST['v3']));
	$bEstado = numeros_validar($_REQUEST['v5']);
	$bDoc = cadena_Validar($_REQUEST['v6']);
	$bTipo = numeros_validar($_REQUEST['v7']);
	$bMotivo = numeros_validar($_REQUEST['v8']);
	$bUnidad = numeros_validar($_REQUEST['v9']);
	$bResponsable = numeros_validar($_REQUEST['v10']);
	$bZona = numeros_validar($_REQUEST['v11']);
	$bAgno = numeros_validar($_REQUEST['v12']);
	switch ($bTipo) {
		case 1: // Saldos a favor
			$sTituloRpt = 'Saldos_A_Favor';
			break;
		case 707: // Confirmación de recaudos
			$sTituloRpt = 'Confirmacion_de_Recaudos';
			break;
		default:
			$sError = 'No se ha definido el tipo de reporte.';
			break;
	}
}
if ($sError == '') {
	$sSubtitulo = '';
	$sDetalle = '';
	$sSQLadd = '';
	$sSQLadd1 = '';
	if ($bDoc != '') {
		$sSQLadd = $sSQLadd . ' AND T11.unad11doc LIKE "%' . $bDoc . '%"';
	}
	if ($bEstado != '') {
		$sSQLadd1 = $sSQLadd1 . 'TB.saiu47estado=' . $bEstado . ' AND ';
	}
	if ($bMotivo != '') {
		$sSQLadd1 = $sSQLadd1 . 'TB.saiu47t1idmotivo=' . $bMotivo . ' AND ';
	}
	if ($bUnidad != '') {
		$sSQLadd1 = $sSQLadd1 . 'TB.saiu47idunidad=' . $bUnidad . ' AND ';
	}
	if ($bResponsable != '') {
		if ($bResponsable == 1) {
			$sSQL = 'SELECT TB.bita28id AS id
			FROM bita28eqipoparte AS TB, unad11terceros AS T2 
			WHERE TB.bita28idtercero=T2.unad11id AND TB.bita28idtercero=' . $idTercero . ' AND TB.bita28activo="S" ' . '';
			$tabla = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla) > 0) {
				$fila = $objDB->sf($tabla);
				$sSQLadd1 = $sSQLadd1 . 'TB.saiu47idresponsable=' . $fila['id'] . ' AND ';
			}
		}
	}
	if ($bZona != '') {
		$sSQLadd1 = $sSQLadd1 . 'TB.saiu47idzona=' . $bZona . ' AND ';
	}
	if ($bNombre != '') {
		$sBase = mb_strtoupper($bNombre);
		$aNoms = explode(' ', $sBase);
		for ($k = 1; $k <= count($aNoms); $k++) {
			$sCadena = $aNoms[$k - 1];
			if ($sCadena != '') {
				$sSQLadd = $sSQLadd . ' AND T11.unad11razonsocial LIKE "%' . $sCadena . '%"';
			}
		}
	}
	$sTabla = 'saiu47tramites_';
	$sTabla = $sTabla . $bAgno;
	// ------------------------------------------------
	// Fin de las condiciones de la consulta
	// ------------------------------------------------
	$sLimite = '';
	$sBase = '';
	$sSQL = 'SHOW TABLES LIKE "saiu47tramites%"';
	$tabla = $objDB->ejecutasql($sSQL);
	while ($fila = $objDB->sf($tabla)) {
		if ($sBase != '') {
			$sBase = $sBase . ' UNION ';
		}
		$sBase = $sBase . 'SELECT TB.saiu47agno, TB.saiu47mes, TB.saiu47dia, TB.saiu47estado, TB.saiu47consec, 
		TB.saiu47id, TB.saiu47origenagno, TB.saiu47origenmes, TB.saiu47origenid, TB.saiu47dia, 
		TB.saiu47hora, TB.saiu47minuto, TB.saiu47tiporadicado, TB.saiu47tipotramite, TB.saiu47t1idmotivo, 
		TB.saiu47idsolicitante, TB.saiu47idresponsable, TB.saiu47t1vrsolicitado, TB.saiu47idperiodo, TB.saiu47idescuela, 
		TB.saiu47idprograma, TB.saiu47idzona, TB.saiu47idcentro, TB.saiu47idunidad, TB.saiu47tem_radicado
		FROM ' . $sTabla . ' AS TB
		WHERE TB.saiu47tipotramite=' . $bTipo . ' AND ' . $sSQLadd1 . ' TB.saiu47id>0 ' . $sSQLadd . '';
	}
	$sSQLReporte = '' . $sBase . '
	ORDER BY saiu47tipotramite, saiu47agno DESC, saiu47mes DESC, saiu47consec DESC';
	// ------------------------------------------------
	// Fin de la consulta
	// ------------------------------------------------
}
if ($sError == '') {
	$sProtocolo = 'http';
	if (isset($_SERVER['HTTPS']) != 0) {
		if ($_SERVER['HTTPS'] == 'on') {
			$sProtocolo = 'https';
		}
	}
	$sServerRpt = $sProtocolo . '://' . $_SERVER['SERVER_NAME'];
	// - Quien esta descargando el reporte.
	$sNombreUsuario = '[' . $idTercero . ']';
	$sSQL = 'SELECT unad11razonsocial FROM unad11terceros WHERE unad11id=' . $idTercero . '';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$sNombreUsuario = cadena_LimpiarTildes($fila['unad11razonsocial']) . ' [' . $idTercero . ']';
	}
	$objReader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
	$objExcel = $objReader->load($sFormato);
	$objExcel->getProperties()->setCreator($sNombreUsuario . ' - http://www.unad.edu.co');
	$objExcel->getProperties()->setLastModifiedBy($sNombreUsuario . ' - http://www.unad.edu.co');
	$objExcel->getProperties()->setTitle($sTituloRpt);
	$objExcel->getProperties()->setSubject($sTituloRpt);
	$objExcel->getProperties()->setDescription('Reporte 3047 del SII 4.0 en ' . $sServerRpt . ' creado en ' . fecha_hoy() . ' ' . formato_horaminuto(fecha_hora(), fecha_minuto()));
	$objHoja = $objExcel->getActiveSheet();
	$objHoja->setTitle(substr($sTituloRpt, 0, 30));
	$objContenedor = $objHoja;
	$sColTope = 'P';
	//Imagen del encabezado
	$sImagenSuperior = $APP->rutacomun . 'imagenes/rpt_cabeza.jpg';
	PHPExcel_Justificar_Celda_HorizontalCentro($objContenedor, 'A1');
	if (file_exists($sImagenSuperior)) {
		PHPExcel_Agrega_Dibujo($objContenedor, 'Logo', 'Logo', $sImagenSuperior, '161', 'A1', '0', false, '0');
	}
	$sFechaImpreso = formato_fechalarga(fecha_hoy(), true) . ' ' . html_TablaHoraMin(fecha_hora(), fecha_minuto());
	PHPExcel_Texto_Tres_Partes($objContenedor, $sColTope . '9', ' ', 'Fecha impresión: ', $sFechaImpreso, 'AmOsUn', true, false, 9, 'Calibri', 'AzOsUn');
	PHPExcel_Alinear_Celda_Derecha($objContenedor, $sColTope . '9');
	$iFila = 12;
	PHPEXCEL_Escribir($objHoja, 0, $iFila, 'Tramites');
	PHPExcel_Mexclar_Celdas($objContenedor, 'A' . $iFila . ':' . $sColTope . $iFila);
	PHPExcel_Justificar_Celda_HorizontalCentro($objContenedor, 'A' . $iFila);
	PHPExcel_Formato_Fuente_Celda($objContenedor, 'A' . $iFila, '14', 'Yu Gothic', 'AzOsUn', true, false, false);
	//Espacio para el encabezado
	if ($sSubtitulo != '') {
		$iFila++;
		PHPEXCEL_Escribir($objHoja, 0, $iFila, $sSubtitulo);
		PHPExcel_Justificar_Celda_HorizontalCentro($objContenedor, 'A' . $iFila);
		PHPExcel_Formato_Fuente_Celda($objContenedor, 'A' . $iFila, '12', 'Yu Gothic', 'AmOsUn', true, false, false);
		PHPExcel_Mexclar_Celdas($objContenedor, 'A' . $iFila . ':' . $sColTope . $iFila);
	}
	if ($sDetalle != '') {
		$iFila++;
		PHPEXCEL_Escribir($objHoja, 0, $iFila, $sDetalle);
		PHPExcel_Justificar_Celda_HorizontalCentro($objContenedor, 'A' . $iFila);
		PHPExcel_Formato_Fuente_Celda($objContenedor, 'A' . $iFila, '10', 'Yu Gothic', 'Ne', true, false, false);
		PHPExcel_Mexclar_Celdas($objContenedor, 'A' . $iFila . ':' . $sColTope . $iFila);
	}
	PHPExcel_RellenarCeldas($objContenedor, 'A1:' . $sColTope . $iFila, 'Bl', false);
	$iFila++;
	$iFilaBase = $iFila;
	$aTitulos = array(
		'Fecha', 'Consecutivo', 'Solicitante', '', '',
		'Estado', 'Fecha', 'Motivo', 'Valor solicitado', 'Periodo', 
		'Escuela', 'Programa', 'Zona', 'Centro', 'Unidad', 
		'Responsable'

	);
	$aAnchos = array(
		13, 13, 13, 13, 35,
		20, 13, 30, 15, 30, 
		13, 30, 13, 20, 30, 
		30
	);
	for ($k = 0; $k <= 15; $k++) {
		PHPEXCEL_Escribir($objHoja, $k, $iFila, $aTitulos[$k]);
		$sColumna = columna_Letra($k);
		$objHoja->getColumnDimension($sColumna)->setWidth($aAnchos[$k]);
		PHPExcel_Justificar_Celda_HorizontalCentro($objContenedor, $sColumna . $iFila);
	}
	//PHPExcel_Mexclar_Celdas($objContenedor, 'A' . $iFila . ':B' . $iFila . '');
	PHPExcel_Formato_Fuente_Celda($objContenedor, 'A' . $iFila . ':' . $sColTope . $iFila, '10', 'Yu Gothic', 'Ne', true, false, false);
	$iFila++;
	$asaiu47estado = array();
	$asaiu47tiporadicado = array();
	$asaiu47tipotramite = array();
	$aTercero = array();
	$asaiu47motivotramite = array();
	$asaiu47periodo = array();
	$asaiu47escuela = array();
	$asaiu47programa = array();
	$asaiu47zona = array();
	$asaiu47centro = array();
	$asaiu47unidad = array();
	$tabla = $objDB->ejecutasql($sSQLReporte);
	if ($bDebug) {
		PHPEXCEL_Escribir($objHoja, 1, $iFila, $sSQLReporte);
		$iFila++;
	}
	// Cargar tablas de configuración
	$sSQL = 'SELECT saiu60id, saiu60nombre FROM saiu60estadotramite';
	$tabla60 = $objDB->ejecutasql($sSQL);
	while ($fila60 = $objDB->sf($tabla60)) {
		$asaiu47estado[$fila60['saiu60id']] = $fila60['saiu60nombre'];
	}
	//
	while ($fila = $objDB->sf($tabla)) {
		$iAgno = $fila['saiu47agno'];
		$sTabla2 = 'saiu48anotaciones_' . $iAgno;
		$et_fecha = fecha_Armar($fila['saiu47dia'], $fila['saiu47mes'], $fila['saiu47agno']);
		$et_saiu47tem_radicado = fecha_desdenumero($fila['saiu47tem_radicado']);
		if (isset($aTercero[$fila['saiu47idsolicitante']]) == 0) {
			$sSQL = 'SELECT unad11tipodoc, unad11doc, unad11razonsocial
			FROM unad11terceros WHERE unad11id=' . $fila['saiu47idsolicitante'] . '';
			$tabla11 = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla11) > 0) {
				$fila11 = $objDB->sf($tabla11);
				$aTercero[$fila['saiu47idsolicitante']]['td'] = $fila11['unad11tipodoc'];
				$aTercero[$fila['saiu47idsolicitante']]['doc'] = $fila11['unad11doc'];
				$aTercero[$fila['saiu47idsolicitante']]['razonsocial'] = $fila11['unad11razonsocial'];
			}
		}
		$et_saiu47estado = '';
		if (isset($asaiu47estado[$fila['saiu47estado']]) == 0) {
			$sDato = '{' . $fila['saiu47estado'] . '}';
			$sSQL = 'SELECT saiu60nombre FROM saiu60estadotramite WHERE saiu60id=' . $fila['saiu47estado'] . '';
			$tabla60 = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla60) > 0) {
				$fila60 = $objDB->sf($tabla60);
				$sDato = $fila60['saiu60nombre'];
			}
			$asaiu47estado[$fila['saiu47estado']] = $sDato;
		}
		
		$et_saiu48idtramite = '';
		if (isset($asaiu48fecha[$fila['saiu47fecha']]) == 0) {
			$sDato = '{' . $fila['saiu47fecha'] . '}';
			$sSQL = 'SELECT saiu48fecha FROM ' . $sTabla2 . '  WHERE saiu48idtramite=' . $fila['saiu47id'] . '';
			$tabla48 = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla48) > 0) {
				$fila48 = $objDB->sf($tabla48);
				$sDato = $fila48['saiu48fecha'];
			}
			$asaiu48idtramite[$fila['saiu48fecha']] = $sDato;
		}
		$et_saiu47motivotramite = '';
		if (isset($asaiu47motivotramite[$fila['saiu47t1idmotivo']]) == 0) {
			$sDato = '{' . $fila['saiu47t1idmotivo'] . '}';
			$sSQL = 'SELECT saiu50nombre FROM saiu50motivotramite WHERE saiu50id=' . $fila['saiu47t1idmotivo'] . '';
			$tabla50 = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla50) > 0) {
				$fila50 = $objDB->sf($tabla50);
				$sDato = $fila50['saiu50nombre'];
			}
			$asaiu47motivotramite[$fila['saiu47t1idmotivo']] = $sDato;
		}
		$et_saiu47periodo = '';
		if (isset($asaiu47periodo[$fila['saiu47idperiodo']]) == 0) {
			$sDato = '{' . $fila['saiu47idperiodo'] . '}';
			$sSQL = 'SELECT exte02nombre FROM exte02per_aca WHERE exte02id=' . $fila['saiu47idperiodo'] . '';
			$tabla02 = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla02) > 0) {
				$fila02 = $objDB->sf($tabla02);
				$sDato = $fila02['exte02nombre'];
			}
			$asaiu47periodo[$fila['saiu47idperiodo']] = $sDato;
		}
		$et_saiu47escuela = '';
		if (isset($asaiu47escuela[$fila['saiu47idescuela']]) == 0) {
			$sDato = '{' . $fila['saiu47idescuela'] . '}';
			$sSQL = 'SELECT core12sigla FROM core12escuela WHERE core12id=' . $fila['saiu47idescuela'] . '';
			$tabla12 = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla12) > 0) {
				$fila12 = $objDB->sf($tabla12);
				$sDato = $fila12['core12sigla'];
			}
			$asaiu47escuela[$fila['saiu47idescuela']] = $sDato;
		}
		$et_saiu47programa = '';
		if (isset($asaiu47programa[$fila['saiu47idprograma']]) == 0) {
			$sDato = '{' . $fila['saiu47idprograma'] . '}';
			$sSQL = 'SELECT core09nombre FROM core09programa WHERE core09id=' . $fila['saiu47idprograma'] . '';
			$tabla09 = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla09) > 0) {
				$fila09 = $objDB->sf($tabla09);
				$sDato = $fila09['core09nombre'];
			}
			$asaiu47programa[$fila['saiu47idprograma']] = $sDato;
		}
		$et_saiu47zona = '';
		if (isset($asaiu47zona[$fila['saiu47idzona']]) == 0) {
			$sDato = '{' . $fila['saiu47idzona'] . '}';
			$sSQL = 'SELECT unad23sigla FROM unad23zona WHERE unad23id=' . $fila['saiu47idzona'] . '';
			$tabla23 = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla23) > 0) {
				$fila23 = $objDB->sf($tabla23);
				$sDato = $fila23['unad23sigla'];
			}
			$asaiu47zona[$fila['saiu47idzona']] = $sDato;
		}
		$et_saiu47centro = '';
		if (isset($asaiu47centro[$fila['saiu47idcentro']]) == 0) {
			$sDato = '{' . $fila['saiu47idcentro'] . '}';
			$sSQL = 'SELECT unad24nombre FROM unad24sede WHERE unad24id=' . $fila['saiu47idcentro'] . '';
			$tabla24 = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla24) > 0) {
				$fila24 = $objDB->sf($tabla24);
				$sDato = $fila24['unad24nombre'];
			}
			$asaiu47centro[$fila['saiu47idcentro']] = $sDato;
		}
		$et_saiu47unidad = '';
		if (isset($asaiu47unidad[$fila['saiu47idunidad']]) == 0) {
			$sDato = '{' . $fila['saiu47idunidad'] . '}';
			$sSQL = 'SELECT unae26nombre FROM unae26unidadesfun WHERE unae26id=' . $fila['saiu47idunidad'] . '';
			$tabla26 = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla26) > 0) {
				$fila26 = $objDB->sf($tabla26);
				$sDato = $fila26['unae26nombre'];
			}
			$asaiu47unidad[$fila['saiu47idunidad']] = $sDato;
		}
		if (isset($aTercero[$fila['saiu47idresponsable']]) == 0) {
			$sSQL = 'SELECT unad11tipodoc, unad11doc, unad11razonsocial
			FROM unad11terceros WHERE unad11id=' . $fila['saiu47idresponsable'] . '';
			$tabla11 = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla11) > 0) {
				$fila11 = $objDB->sf($tabla11);
				$aTercero[$fila['saiu47idresponsable']] = $fila11['unad11razonsocial'];
			}
		}
		PHPEXCEL_Escribir($objHoja, 0, $iFila, $et_fecha);
		PHPEXCEL_Escribir($objHoja, 1, $iFila, $fila['saiu47consec']);
		PHPEXCEL_Escribir($objHoja, 2, $iFila, $aTercero[$fila['saiu47idsolicitante']]['td']);
		PHPEXCEL_Escribir($objHoja, 3, $iFila, $aTercero[$fila['saiu47idsolicitante']]['doc']);
		PHPEXCEL_Escribir($objHoja, 4, $iFila, $aTercero[$fila['saiu47idsolicitante']]['razonsocial']);
		PHPEXCEL_Escribir($objHoja, 5, $iFila, $asaiu47estado[$fila['saiu47estado']]);
		PHPEXCEL_Escribir($objHoja, 6, $iFila, $et_saiu47tem_radicado);
		PHPEXCEL_Escribir($objHoja, 7, $iFila, $asaiu47motivotramite[$fila['saiu47t1idmotivo']]);
		PHPEXCEL_Escribir($objHoja, 8, $iFila, formato_moneda($fila['saiu47t1vrsolicitado']));
		$et_saiu47periodo = $asaiu47periodo[$fila['saiu47idperiodo']];
		PHPEXCEL_Escribir($objHoja, 9, $iFila, $et_saiu47periodo);
		$et_saiu47escuela = $asaiu47escuela[$fila['saiu47idescuela']];
		PHPEXCEL_Escribir($objHoja, 10, $iFila, $et_saiu47escuela);
		$et_saiu47programa = $asaiu47programa[$fila['saiu47idprograma']];
		PHPEXCEL_Escribir($objHoja, 11, $iFila, $et_saiu47programa);
		$et_saiu47zona = $asaiu47zona[$fila['saiu47idzona']];
		PHPEXCEL_Escribir($objHoja, 12, $iFila, $et_saiu47zona);
		$et_saiu47centro = $asaiu47centro[$fila['saiu47idcentro']];
		PHPEXCEL_Escribir($objHoja, 13, $iFila, $et_saiu47centro);
		$et_saiu47unidad = $asaiu47unidad[$fila['saiu47idunidad']];
		PHPEXCEL_Escribir($objHoja, 14, $iFila, $et_saiu47unidad);
		PHPEXCEL_Escribir($objHoja, 15, $iFila, $aTercero[$fila['saiu47idresponsable']]);
		$iFila++;
	}
	$objDB->CerrarConexion();
	PHPExcel_RellenarCeldas($objContenedor, 'A' . $iFilaBase . ':' . $sColTope . $iFila, 'Bl', true);
	PHPEXCEL_Escribir($objHoja, 0, 1, '');
	PHPExcel_RellenarCeldas($objContenedor, 'A1', 'Bl', true);
	if ($_REQUEST['clave'] != '') {
		/* Bloquear la hoja. */
		$objHoja->getProtection()->setPassword($_REQUEST['clave']);
		$objHoja->getProtection()->setSheet(true);
		$objHoja->getProtection()->setSort(true);
	}
	/* descargar el resultado */
	header('Expires: Thu, 27 Mar 1980 23:59:00 GMT'); /* la pagina expira en una fecha pasada */
	header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT'); /* ultima actualizacion ahora cuando la cargamos */
	header('Cache-Control: no-cache, must-revalidate'); /* no guardar en CACHE */
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename="' . $sTituloRpt . '.xlsx"');
	header('Cache-Control: max-age=0');
	$objWriter = new Xlsx($objExcel);
	$objWriter->save('php://output');
	die();
} else {
	echo $sError;
}
