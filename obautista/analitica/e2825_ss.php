<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2023 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Version 2.29.1 miércoles, 18 de enero de 2023
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
if (isset($_REQUEST['v3']) == 0) {
	$_REQUEST['v3'] = '';
}
if (isset($_REQUEST['v4']) == 0) {
	$_REQUEST['v4'] = '';
}
if (isset($_REQUEST['v5']) == 0) {
	$_REQUEST['v5'] = '';
}
if (isset($_REQUEST['v6']) == 0) {
	$_REQUEST['v6'] = '';
}
if (isset($_REQUEST['v7']) == 0) {
	$_REQUEST['v7'] = '';
}
if (isset($_REQUEST['v8']) == 0) {
	$_REQUEST['v8'] = '';
}
if (isset($_REQUEST['v9']) == 0) {
	$_REQUEST['v9'] = '';
}
if (isset($_REQUEST['v10']) == 0) {
	$_REQUEST['v10'] = '';
}
if (isset($_REQUEST['v11']) == 0) {
	$_REQUEST['v11'] = '';
}
//Validar las variables.
if ($sError == '') {
	if (isset($_REQUEST['gthu54fecha']) != 0) {
		$iVr = numeros_validar($_REQUEST['gthu54fecha']);
		if ($iVr != $_REQUEST['gthu54fecha']) {
			$sError = 'No fue posible validar el contenido de la variable Fecha';
		}
	} else {
		$sError = 'No se ha recibido la variable Fecha';
	}
}
if ($sError == '') {
	if (isset($_REQUEST['gthu54condicion']) != 0) {
		$iVr = numeros_validar($_REQUEST['gthu54condicion']);
		if ($iVr != $_REQUEST['gthu54condicion']) {
			$sError = 'No fue posible validar el contenido de la variable Condicion';
		}
	} else {
		$sError = 'No se ha recibido la variable Condicion';
	}
}
if ($sError == '') {
	if (isset($_REQUEST['gthu54idcargo']) != 0) {
		$iVr = numeros_validar($_REQUEST['gthu54idcargo']);
		if ($iVr != $_REQUEST['gthu54idcargo']) {
			$sError = 'No fue posible validar el contenido de la variable Cargo';
		}
	} else {
		$sError = 'No se ha recibido la variable Cargo';
	}
}
if ($sError == '') {
	if (isset($_REQUEST['gthu54idescuela']) != 0) {
		$iVr = numeros_validar($_REQUEST['gthu54idescuela']);
		if ($iVr != $_REQUEST['gthu54idescuela']) {
			$sError = 'No fue posible validar el contenido de la variable Escuela';
		}
	} else {
		$sError = 'No se ha recibido la variable Escuela';
	}
}
if ($sError == '') {
	if (isset($_REQUEST['gthu54iddepedencia']) != 0) {
		$iVr = numeros_validar($_REQUEST['gthu54iddepedencia']);
		if ($iVr != $_REQUEST['gthu54iddepedencia']) {
			$sError = 'No fue posible validar el contenido de la variable Depedencia';
		}
	} else {
		$sError = 'No se ha recibido la variable Depedencia';
	}
}
if ($sError == '') {
	if (isset($_REQUEST['gthu54vercondicion']) != 0) {
		$iVr = numeros_validar($_REQUEST['gthu54vercondicion']);
		if ($iVr != $_REQUEST['gthu54vercondicion']) {
			$sError = 'No fue posible validar el contenido de la variable Vercondicion';
		}
	} else {
		$sError = 'No se ha recibido la variable Vercondicion';
	}
}
if ($sError == '') {
	if (isset($_REQUEST['gthu54vercargo']) != 0) {
		$iVr = numeros_validar($_REQUEST['gthu54vercargo']);
		if ($iVr != $_REQUEST['gthu54vercargo']) {
			$sError = 'No fue posible validar el contenido de la variable Vercargo';
		}
	} else {
		$sError = 'No se ha recibido la variable Vercargo';
	}
}
if ($sError == '') {
	if (isset($_REQUEST['gthu54verescuela']) != 0) {
		$iVr = numeros_validar($_REQUEST['gthu54verescuela']);
		if ($iVr != $_REQUEST['gthu54verescuela']) {
			$sError = 'No fue posible validar el contenido de la variable Verescuela';
		}
	} else {
		$sError = 'No se ha recibido la variable Verescuela';
	}
}
if ($sError == '') {
	if (isset($_REQUEST['gthu54verdepedencia']) != 0) {
		$iVr = numeros_validar($_REQUEST['gthu54verdepedencia']);
		if ($iVr != $_REQUEST['gthu54verdepedencia']) {
			$sError = 'No fue posible validar el contenido de la variable Verdepedencia';
		}
	} else {
		$sError = 'No se ha recibido la variable Verdepedencia';
	}
}
if ($sError == '') {
	$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto != '') {
		$objDB->dbPuerto = $APP->dbpuerto;
	}
	//Validar permisos.
	list($bEntra, $sDebugP) = seg_revisa_permisoV3(2825, 6, $idTercero, $objDB);
	if (!$bEntra) {
		$sError = 'No tiene permiso para consultar este reporte [Mod 2825 : 6]';
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
	//Leemos los parametros de entrada.
	$sSubtitulo = '';
	$sDetalle = '';
	$gthu54fecha = $_REQUEST['v3'];
	$gthu54condicion = $_REQUEST['v4'];
	$gthu54idcargo = $_REQUEST['v5'];
	$gthu54idescuela = $_REQUEST['v6'];
	$gthu54iddepedencia = $_REQUEST['v7'];
	$gthu54vercondicion = $_REQUEST['v8'];
	$gthu54vercargo = $_REQUEST['v9'];
	$gthu54verescuela = $_REQUEST['v10'];
	$gthu54verdepedencia = $_REQUEST['v11'];
	$sSQLadd = '';
	$sSQLadd1 = '';
	if ($gthu54fecha != '') {
		$sSQLadd1 = $sSQLadd1 . ' AND gthu54fecha=' . $gthu54fecha . '';
	};
	if ($gthu54condicion != '') {
		$sSQLadd1 = $sSQLadd1 . ' AND gthu54condicion=' . $gthu54condicion . '';
	};
	if ($gthu54idcargo != '') {
		$sSQLadd1 = $sSQLadd1 . ' AND gthu54idcargo=' . $gthu54idcargo . '';
	};
	if ($gthu54idescuela != '') {
		$sSQLadd1 = $sSQLadd1 . ' AND gthu54idescuela=' . $gthu54idescuela . '';
	};
	if ($gthu54iddepedencia != '') {
		$sSQLadd1 = $sSQLadd1 . ' AND gthu54iddepedencia=' . $gthu54iddepedencia . '';
	};
	if ($gthu54vercondicion != '') {
		$sSQLadd1 = $sSQLadd1 . ' AND gthu54vercondicion=' . $gthu54vercondicion . '';
	};
	if ($gthu54vercargo != '') {
		$sSQLadd1 = $sSQLadd1 . ' AND gthu54vercargo=' . $gthu54vercargo . '';
	};
	if ($gthu54verescuela != '') {
		$sSQLadd1 = $sSQLadd1 . ' AND gthu54verescuela=' . $gthu54verescuela . '';
	};
	if ($gthu54verdepedencia != '') {
		$sSQLadd1 = $sSQLadd1 . ' AND gthu54verdepedencia=' . $gthu54verdepedencia . '';
	};
	//Fin de las condiciones de la consulta
	//-- Area para saltar comparaciones con los archivos tipo e
}
if ($sError == '') {
	$objReader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
	$objExcel = $objReader->load($sFormato);
	$objExcel->getProperties()->setCreator('Mauro Avellaneda - http://www.unad.edu.co');
	$objExcel->getProperties()->setLastModifiedBy('Mauro Avellaneda - http://www.unad.edu.co');
	$objExcel->getProperties()->setTitle($sTituloRpt);
	$objExcel->getProperties()->setSubject($sTituloRpt);
	$objExcel->getProperties()->setDescription('Reporte de http://www.unad.edu.co');
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
	PHPEXCEL_Escribir($objHoja, 0, $iFila, 'Personal Activo');
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
	//-- Fin del area no comparada
	$sSQL = 'SELECT * 
	FROM gthu54hvlaboral 
	WHERE ';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($bDebug) {
		PHPEXCEL_Escribir($objHoja, 1, $iFila, $sSQL);
		$iFila++;
	}
	while ($fila = $objDB->sf($tabla)) {
		PHPEXCEL_Escribir($objHoja, 0, $iFila, $fila['gthu54fecha']);
		PHPEXCEL_Escribir($objHoja, 1, $iFila, $fila['gthu54condicion']);
		PHPEXCEL_Escribir($objHoja, 2, $iFila, $fila['gthu54idcargo']);
		PHPEXCEL_Escribir($objHoja, 3, $iFila, $fila['gthu54idescuela']);
		PHPEXCEL_Escribir($objHoja, 4, $iFila, $fila['gthu54iddepedencia']);
		PHPEXCEL_Escribir($objHoja, 5, $iFila, $fila['gthu54vercondicion']);
		PHPEXCEL_Escribir($objHoja, 6, $iFila, $fila['gthu54vercargo']);
		PHPEXCEL_Escribir($objHoja, 7, $iFila, $fila['gthu54verescuela']);
		PHPEXCEL_Escribir($objHoja, 8, $iFila, $fila['gthu54verdepedencia']);
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
