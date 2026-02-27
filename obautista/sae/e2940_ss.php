<?php
/*
--- © Omar Augusto Bautista - UNAD - 2026 ---
--- omar.bautista@unad.edu.co - http://www.unad.edu.co
--- Modelo Version 3.1.5 viernes, 27 de febrero de 2026
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
require $APP->rutacomun . 'libexcel_ss.php';
require $APP->rutacomun . 'vendor/autoload.php';
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
	'', '', '', 'Documento', 'Nombre',
	'Convocatoria', 'Estado', 'Tipologia', 'Subtipologia'
);
$aTipos = array(
	0, 0, 0, 1, 1,
	0, 0, 0, 0
);
$iNumVariables = 8;
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
	for ($k = 3; $k <= $iNumVariables; $k++){
		switch($k){
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
	list($bEntra, $sDebugP) = seg_revisa_permisoV3(2940, 6, $idTercero, $objDB);
	if (!$bEntra) {
		$sError = 'No tiene permiso para consultar este reporte [Mod 2940 : 6]';
	}
}
if ($sError == '') {
	if ($_REQUEST['rdebug'] == 1) {
		$bDebug = true;
	}
	$sTituloRpt = 'Reporte';
	$sFormato = 'formato.xlsx';
	if (!file_exists($sFormato)) {
		$sError = 'Formato no encontrado {' . $sFormato . '}';
	}
}
if ($sError == '') {
	// ------------------------------------------------
	// Leemos los parametros de entrada.
	// ------------------------------------------------
	$bdocumento = cadena_Validar(trim($_REQUEST['v3']));
	$bnombre = cadena_Validar(trim($_REQUEST['v4']));
	$bconvocatoria = numeros_validar($_REQUEST['v5']);
	$bestado = numeros_validar($_REQUEST['v6']);
	$btipologia = numeros_validar($_REQUEST['v7']);
	$bsubtipologia = numeros_validar($_REQUEST['v8']);
	$sSubtitulo = '';
	$sDetalle = '';
	$sSQLadd = '';
	$sSQLadd1 = '';
	// ------------------------------------------------
	// Fin de las condiciones de la consulta
	// ------------------------------------------------
	$sCampos = 'SELECT TB.visa40id, T2.visa35nombre, T3.unad11razonsocial AS C3_nombre, TB.visa40estado, TB.visa40fechainsc, TB.visa40idconvocatoria, TB.visa40idtercero, T3.unad11tipodoc AS C3_td, T3.unad11doc AS C3_doc';
	$sConsulta = 'FROM visa40inscripcion AS TB, visa35convocatoria AS T2, unad11terceros AS T3 
	WHERE ' . $sSQLadd1 . ' TB.visa40id>0 AND TB.visa40idconvocatoria=T2.visa35id AND TB.visa40idtercero=T3.unad11id ' . $sSQLadd . '';
	$sOrden = 'ORDER BY TB.visa40idconvocatoria, TB.visa40idtercero';
	$sSQLReporte = $sCampos . ' ' . $sConsulta . ' ' . $sOrden;
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
	$objExcel->getProperties()->setDescription('Reporte 2940 del SII 5.0 en ' . $sServerRpt . ' creado en ' . fecha_hoy() . ' ' . formato_horaminuto(fecha_hora(), fecha_minuto()));
	$objHoja = $objExcel->getActiveSheet();
	$objHoja->setTitle(substr($sTituloRpt, 0, 30));
	$objContenedor = $objHoja;
	$sColTope = 'J';
	//Imagen del encabezado
	$sImagenSuperior = $APP->rutacomun . 'imagenes/rpt_cabeza.jpg';
	PHPExcel_Justificar_Celda_HorizontalCentro($objContenedor, 'A1');
	if (file_exists($sImagenSuperior)) {
		PHPExcel_Agrega_Dibujo($objContenedor, 'Logo', 'Logo', $sImagenSuperior, '161', 'A1', '0',false, '0');
	}
	$sFechaImpreso = formato_fechalarga(fecha_hoy(), true) . ' ' . html_TablaHoraMin(fecha_hora(), fecha_minuto());
	PHPExcel_Texto_Tres_Partes($objContenedor, $sColTope . '9', ' ', 'Fecha impresión: ', $sFechaImpreso, 'AmOsUn', true, false, 9, 'Calibri', 'AzOsUn');
	PHPExcel_Alinear_Celda_Derecha($objContenedor, $sColTope . '9');
	$iFila = 12;
	PHPEXCEL_Escribir($objHoja, 0, $iFila, 'Inscripcion convocatoria');
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
		'Col0', 'Col1', 'Col2', 'Col3', 'Col4',
		'Col5', 'Col6', 'Col7', 'Col8', 'Col9'
	);
	$aAnchos = array(
		13, 13, 13, 13, 13,
		13, 13, 13, 13, 13
	);
	for ($k = 0; $k <= 9; $k++) {
		PHPEXCEL_Escribir($objHoja, $k, $iFila, $aTitulos[$k]);
		$sColumna = columna_Letra($k);
		$objHoja->getColumnDimension($sColumna)->setWidth($aAnchos[$k]);
		PHPExcel_Justificar_Celda_HorizontalCentro($objContenedor, $sColumna . $iFila);
	}
	//PHPExcel_Mexclar_Celdas($objContenedor, 'A' . $iFila . ':B' . $iFila . '');
	PHPExcel_Formato_Fuente_Celda($objContenedor, 'A' . $iFila . ':' . $sColTope . $iFila, '10', 'Yu Gothic', 'Ne', true, false, false);
	$iFila++;
	$avisa40idconvocatoria = array();
	$avisa40idperiodo = array();
	$avisa40idescuela = array();
	$avisa40idprograma = array();
	$avisa40idzona = array();
	$avisa40idcentro = array();
	$avisa40idtipologia = array();
	$avisa40idsubtipo = array();
	$tabla = $objDB->ejecutasql($sSQLReporte);
	if ($bDebug) {
		PHPEXCEL_Escribir($objHoja, 1, $iFila, $sSQL);
		$iFila++;
	}
	while ($fila = $objDB->sf($tabla)) {
		$et_visa40idconvocatoria = '';
		if ($fila['visa40idconvocatoria'] != 0) {
			if (isset($avisa40idconvocatoria[$fila['visa40idconvocatoria']]) == 0) {
				$sDato = '{' . $fila['visa40idconvocatoria'] . '}';
				$sSQL = 'SELECT visa35nombre FROM visa35convocatoria WHERE visa35id=' . $fila['visa40idconvocatoria'] . '';
				$tablad = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($tablad) > 0) {
					$filad = $objDB->sf($tablad);
					$sDato = $filad['visa35nombre'];
				}
				$avisa40idconvocatoria[$fila['visa40idconvocatoria']] = $sDato;
			}
			$et_visa40idconvocatoria = $avisa40idconvocatoria[$fila['visa40idconvocatoria']];
		}
		PHPEXCEL_Escribir($objHoja, 0, $iFila, $et_visa40idconvocatoria);
		PHPEXCEL_Escribir($objHoja, 1, $iFila, $fila['visa40idtercero']);
		PHPEXCEL_Escribir($objHoja, 2, $iFila, $fila['visa40estado']);
		$et_visa40idperiodo = '';
		if ($fila['visa40idperiodo'] != 0) {
			if (isset($avisa40idperiodo[$fila['visa40idperiodo']]) == 0) {
				$sDato = '{' . $fila['visa40idperiodo'] . '}';
				$sSQL = 'SELECT exte02nombre FROM exte02per_aca WHERE exte02id=' . $fila['visa40idperiodo'] . '';
				$tablad = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($tablad) > 0) {
					$filad = $objDB->sf($tablad);
					$sDato = $filad['exte02nombre'];
				}
				$avisa40idperiodo[$fila['visa40idperiodo']] = $sDato;
			}
			$et_visa40idperiodo = $avisa40idperiodo[$fila['visa40idperiodo']];
		}
		PHPEXCEL_Escribir($objHoja, 3, $iFila, $et_visa40idperiodo);
		$et_visa40idescuela = '';
		if ($fila['visa40idescuela'] != 0) {
			if (isset($avisa40idescuela[$fila['visa40idescuela']]) == 0) {
				$sDato = '{' . $fila['visa40idescuela'] . '}';
				$sSQL = 'SELECT core12nombre FROM core12escuela WHERE core12id=' . $fila['visa40idescuela'] . '';
				$tablad = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($tablad) > 0) {
					$filad = $objDB->sf($tablad);
					$sDato = $filad['core12nombre'];
				}
				$avisa40idescuela[$fila['visa40idescuela']] = $sDato;
			}
			$et_visa40idescuela = $avisa40idescuela[$fila['visa40idescuela']];
		}
		PHPEXCEL_Escribir($objHoja, 4, $iFila, $et_visa40idescuela);
		$et_visa40idprograma = '';
		if ($fila['visa40idprograma'] != 0) {
			if (isset($avisa40idprograma[$fila['visa40idprograma']]) == 0) {
				$sDato = '{' . $fila['visa40idprograma'] . '}';
				$sSQL = 'SELECT core09nombre FROM core09programa WHERE core09id=' . $fila['visa40idprograma'] . '';
				$tablad = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($tablad) > 0) {
					$filad = $objDB->sf($tablad);
					$sDato = $filad['core09nombre'];
				}
				$avisa40idprograma[$fila['visa40idprograma']] = $sDato;
			}
			$et_visa40idprograma = $avisa40idprograma[$fila['visa40idprograma']];
		}
		PHPEXCEL_Escribir($objHoja, 5, $iFila, $et_visa40idprograma);
		$et_visa40idzona = '';
		if ($fila['visa40idzona'] != 0) {
			if (isset($avisa40idzona[$fila['visa40idzona']]) == 0) {
				$sDato = '{' . $fila['visa40idzona'] . '}';
				$sSQL = 'SELECT unad23nombre FROM unad23zona WHERE unad23id=' . $fila['visa40idzona'] . '';
				$tablad = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($tablad) > 0) {
					$filad = $objDB->sf($tablad);
					$sDato = $filad['unad23nombre'];
				}
				$avisa40idzona[$fila['visa40idzona']] = $sDato;
			}
			$et_visa40idzona = $avisa40idzona[$fila['visa40idzona']];
		}
		PHPEXCEL_Escribir($objHoja, 6, $iFila, $et_visa40idzona);
		$et_visa40idcentro = '';
		if ($fila['visa40idcentro'] != 0) {
			if (isset($avisa40idcentro[$fila['visa40idcentro']]) == 0) {
				$sDato = '{' . $fila['visa40idcentro'] . '}';
				$sSQL = 'SELECT unad24nombre FROM unad24sede WHERE unad24id=' . $fila['visa40idcentro'] . '';
				$tablad = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($tablad) > 0) {
					$filad = $objDB->sf($tablad);
					$sDato = $filad['unad24nombre'];
				}
				$avisa40idcentro[$fila['visa40idcentro']] = $sDato;
			}
			$et_visa40idcentro = $avisa40idcentro[$fila['visa40idcentro']];
		}
		PHPEXCEL_Escribir($objHoja, 7, $iFila, $et_visa40idcentro);
		PHPEXCEL_Escribir($objHoja, 8, $iFila, $fila['visa40fechainsc']);
		PHPEXCEL_Escribir($objHoja, 9, $iFila, $fila['visa40fechaadmision']);
		PHPEXCEL_Escribir($objHoja, 10, $iFila, $fila['visa40numcupo']);
		$et_visa40idtipologia = '';
		if ($fila['visa40idtipologia'] != 0) {
			if (isset($avisa40idtipologia[$fila['visa40idtipologia']]) == 0) {
				$sDato = '{' . $fila['visa40idtipologia'] . '}';
				$sSQL = 'SELECT visa36nombre FROM visa36convtipologia WHERE visa36id=' . $fila['visa40idtipologia'] . '';
				$tablad = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($tablad) > 0) {
					$filad = $objDB->sf($tablad);
					$sDato = $filad['visa36nombre'];
				}
				$avisa40idtipologia[$fila['visa40idtipologia']] = $sDato;
			}
			$et_visa40idtipologia = $avisa40idtipologia[$fila['visa40idtipologia']];
		}
		PHPEXCEL_Escribir($objHoja, 11, $iFila, $et_visa40idtipologia);
		$et_visa40idsubtipo = '';
		if ($fila['visa40idsubtipo'] != 0) {
			if (isset($avisa40idsubtipo[$fila['visa40idsubtipo']]) == 0) {
				$sDato = '{' . $fila['visa40idsubtipo'] . '}';
				$sSQL = 'SELECT visa37nombre FROM visa37convsubtipo WHERE visa37id=' . $fila['visa40idsubtipo'] . '';
				$tablad = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($tablad) > 0) {
					$filad = $objDB->sf($tablad);
					$sDato = $filad['visa37nombre'];
				}
				$avisa40idsubtipo[$fila['visa40idsubtipo']] = $sDato;
			}
			$et_visa40idsubtipo = $avisa40idsubtipo[$fila['visa40idsubtipo']];
		}
		PHPEXCEL_Escribir($objHoja, 12, $iFila, $et_visa40idsubtipo);
		PHPEXCEL_Escribir($objHoja, 13, $iFila, $fila['visa40idminuta']);
		PHPEXCEL_Escribir($objHoja, 14, $iFila, $fila['visa40idresolucion']);
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

