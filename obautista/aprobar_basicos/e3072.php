<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2022 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Version 2.28.2 viernes, 2 de septiembre de 2022
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
require $APP->rutacomun . 'excel/PHPExcel.php';
require $APP->rutacomun . 'excel/PHPExcel/Writer/Excel2007.php';
require $APP->rutacomun . 'libexcel.php';
if ($_SESSION['unad_id_tercero'] == 0) {
	die();
}
$_SESSION['u_ultimominuto'] = iminutoavance();
$sError = '';
$iReporte = 0;
$bEntra = true;
$bDebug = false;
if (isset($_REQUEST['r']) != 0) {
	$iReporte = numeros_validar($_REQUEST['r']);
}
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
if ($sError != '') {
	$bEntra = false;
}
if ($bEntra) {
	if ($_REQUEST['rdebug'] == 1) {
		$bDebug = true;
	}
	$bEntra = false;
	$sTituloRpt = 'Reporte';
	$sFormato = 'formato.xlsx';
	if ($sError == '') {
		if (!file_exists($sFormato)) {
			$sError = 'Formato no encontrado {' . $sFormato . '}';
		}
	}
	if ($sError == '') {
		$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto != '') {
			$objDB->dbPuerto = $APP->dbpuerto;
		}
		//Leemos los parametros de entrada.
		$sSubtitulo = '';
		$sDetalle = '';
		$bVar3 = $_REQUEST['v3'];
		$bVar4 = $_REQUEST['v4'];
		$bVar5 = $_REQUEST['v5'];
		$sSQLadd = '';
		$sSQLadd1 = '';
		//Fin de las condiciones de la consulta
	//-- Area para saltar comparaciones con los archivos tipo e
		$objReader=PHPExcel_IOFactory::createReader('Excel2007');
		$objPHPExcel = $objReader->load($sFormato);
		$objPHPExcel->getProperties()->setCreator('Mauro Avellaneda - http://www.unad.edu.co');
		$objPHPExcel->getProperties()->setLastModifiedBy('Mauro Avellaneda - http://www.unad.edu.co');
		$objPHPExcel->getProperties()->setTitle($sTituloRpt);
		$objPHPExcel->getProperties()->setSubject($sTituloRpt);
		$objPHPExcel->getProperties()->setDescription('Reporte de http://www.unad.edu.co');
		$objHoja = $objPHPExcel->getActiveSheet();
		$objHoja->setTitle(substr($sTituloRpt, 0, 30));
		$sColTope = 'J';
		//Imagen del encabezado
		$sImagenSuperior = $APP->rutacomun . 'imagenes/rpt_cabeza.jpg';
		PHPExcel_Justificar_Celda_HorizontalCentro($objPHPExcel, 'A1');
		if (file_exists($sImagenSuperior)) {
			PHPExcel_Agrega_Dibujo($objPHPExcel, 'Logo', 'Logo', $APP->rutacomun . 'imagenes/rpt_cabeza.jpg', '161', 'A1', '0',false, '0');
		}
		$sFechaImpreso = formato_fechalarga(fecha_hoy(), true) . ' ' . html_TablaHoraMin(fecha_hora(), fecha_minuto());
		PHPExcel_Texto_Tres_Partes($objPHPExcel, $sColTope . '9', ' ', 'Fecha impresión: ',$sFechaImpreso, 'AmOsUn',true,false, 9, 'Calibri', 'AzOsUn');
		PHPExcel_Alinear_Celda_Derecha($objPHPExcel, $sColTope . '9');
		$iFila = 12;
		$objHoja->setCellValueByColumnAndRow(0, $iFila, 'Aprobar cambio de datos basicos');
		PHPExcel_Mexclar_Celdas($objPHPExcel, 'A' . $iFila . ':' . $sColTope.$iFila);
		PHPExcel_Justificar_Celda_HorizontalCentro($objPHPExcel, 'A' . $iFila);
		PHPExcel_Formato_Fuente_Celda($objPHPExcel, 'A' . $iFila, '14', 'Yu Gothic', 'AzOsUn',true,false,false);
		//Espacio para el encabezado
		if ($sSubtitulo != '') {
			$iFila++;
			$objHoja->setCellValueByColumnAndRow(0, $iFila, $sSubtitulo);
			PHPExcel_Justificar_Celda_HorizontalCentro($objPHPExcel, 'A' . $iFila);
			PHPExcel_Formato_Fuente_Celda($objPHPExcel, 'A' . $iFila, '12', 'Yu Gothic', 'AmOsUn',true,false,false);
			PHPExcel_Mexclar_Celdas($objPHPExcel, 'A' . $iFila . ':' . $sColTope.$iFila);
		}
		if ($sDetalle != '') {
			$iFila++;
			$objHoja->setCellValueByColumnAndRow(0, $iFila, $sDetalle);
			PHPExcel_Justificar_Celda_HorizontalCentro($objPHPExcel, 'A' . $iFila);
			PHPExcel_Formato_Fuente_Celda($objPHPExcel, 'A' . $iFila, '10', 'Yu Gothic', 'Ne',true,false,false);
			PHPExcel_Mexclar_Celdas($objPHPExcel, 'A' . $iFila . ':' . $sColTope.$iFila);
		}
		PHPExcel_RellenarCeldas($objPHPExcel, 'A1:' . $sColTope.$iFila, 'Bl',false);
		$iFila++;
		$iFilaBase = $iFila;
		$aTitulos = array('Col0', 'Col1', 'Col2', 'Col3', 'Col4',
		'Col5', 'Col6', 'Col7', 'Col8', 'Col9');
		$aAnchos = array(13,13,13,13,13,
		13,13,13,13,13);
		for ($k = 0; $k <= 9; $k++) {
			$objHoja->setCellValueByColumnAndRow($k, $iFila, $aTitulos[$k]);
			$sColumna=columna_Letra($k);
			$objPHPExcel->getActiveSheet()->getColumnDimension($sColumna)->setWidth($aAnchos[$k]);
			PHPExcel_Justificar_Celda_HorizontalCentro($objPHPExcel, $sColumna.$iFila);
		}
		//PHPExcel_Mexclar_Celdas($objPHPExcel, 'A' . $iFila . ':B' . $iFila . '');
		PHPExcel_Formato_Fuente_Celda($objPHPExcel, 'A' . $iFila . ':' . $sColTope.$iFila, '10', 'Yu Gothic', 'Ne',true,false,false);
		$iFila++;
	//-- Fin del area no comparada
		$sSQL = 'SELECT * 
		FROM unae40historialcambdoc 
		WHERE unae40idtercero="' . $DATA['unae40idtercero'] . '" AND unae40consec=' . $DATA['unae40consec'] . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($bDebug) {
			$objHoja->setCellValueByColumnAndRow(1, $iFila, $sSQL);
			$iFila++;
		}
		while ($fila = $objDB->sf($tabla)) {
			$objHoja->setCellValueByColumnAndRow(0, $iFila, $fila['unae40idtercero']);
			$objHoja->setCellValueByColumnAndRow(1, $iFila, $fila['unae40consec']);
			$objHoja->setCellValueByColumnAndRow(2, $iFila, $fila['unae40id']);
			$objHoja->setCellValueByColumnAndRow(3, $iFila, $fila['unae40tipodocorigen']);
			$objHoja->setCellValueByColumnAndRow(4, $iFila, $fila['unae40docorigen']);
			$objHoja->setCellValueByColumnAndRow(5, $iFila, $fila['unae40or_nombre1']);
			$objHoja->setCellValueByColumnAndRow(6, $iFila, $fila['unae40or_nombre2']);
			$objHoja->setCellValueByColumnAndRow(7, $iFila, $fila['unae40or_apellido1']);
			$objHoja->setCellValueByColumnAndRow(8, $iFila, $fila['unae40or_apellido2']);
			$objHoja->setCellValueByColumnAndRow(9, $iFila, $fila['unae40or_sexo']);
			$objHoja->setCellValueByColumnAndRow(10, $iFila, $fila['unae40or_fechanac']);
			$objHoja->setCellValueByColumnAndRow(11, $iFila, $fila['unae40or_fechadoc']);
			$objHoja->setCellValueByColumnAndRow(12, $iFila, $fila['unae40tipodocdestino']);
			$objHoja->setCellValueByColumnAndRow(13, $iFila, $fila['unae40docdestino']);
			$objHoja->setCellValueByColumnAndRow(14, $iFila, $fila['unae40des_nombre1']);
			$objHoja->setCellValueByColumnAndRow(15, $iFila, $fila['unae40des_nombre2']);
			$objHoja->setCellValueByColumnAndRow(16, $iFila, $fila['unae40des_apellido1']);
			$objHoja->setCellValueByColumnAndRow(17, $iFila, $fila['unae40des_apellido2']);
			$objHoja->setCellValueByColumnAndRow(18, $iFila, $fila['unae40des_sexo']);
			$objHoja->setCellValueByColumnAndRow(19, $iFila, $fila['unae40des_fechanac']);
			$objHoja->setCellValueByColumnAndRow(20, $iFila, $fila['unae40des_fechadoc']);
			$objHoja->setCellValueByColumnAndRow(21, $iFila, $fila['unae40idsolicita']);
			$objHoja->setCellValueByColumnAndRow(22, $iFila, $fila['unae40fechasol']);
			$objHoja->setCellValueByColumnAndRow(23, $iFila, $fila['unae40horasol']);
			$objHoja->setCellValueByColumnAndRow(24, $iFila, $fila['unae40minsol']);
			$objHoja->setCellValueByColumnAndRow(25, $iFila, $fila['unae40idorigen']);
			$objHoja->setCellValueByColumnAndRow(26, $iFila, $fila['unae40idarchivo']);
			$objHoja->setCellValueByColumnAndRow(27, $iFila, $fila['unae40estado']);
			$objHoja->setCellValueByColumnAndRow(28, $iFila, $fila['unae40detalle']);
			$objHoja->setCellValueByColumnAndRow(29, $iFila, $fila['unae40idaprueba']);
			$objHoja->setCellValueByColumnAndRow(30, $iFila, $fila['unae40fechaapr']);
			$objHoja->setCellValueByColumnAndRow(31, $iFila, $fila['unae40horaaprueba']);
			$objHoja->setCellValueByColumnAndRow(32, $iFila, $fila['unae40minaprueba']);
			$objHoja->setCellValueByColumnAndRow(33, $iFila, $fila['unae40tiempod']);
			$objHoja->setCellValueByColumnAndRow(34, $iFila, $fila['unae40tiempoh']);
			$iFila++;
		}
		$objDB->CerrarConexion();
		PHPExcel_RellenarCeldas($objPHPExcel, 'A' . $iFilaBase . ':' . $sColTope.$iFila, 'Bl', true);
		if ($_REQUEST['clave'] != '') {
			/* Bloquear la hoja. */
			$objHoja->getProtection()->setPassword($_REQUEST['clave']);
			$objHoja->getProtection()->setSheet(true);
			$objHoja->getProtection()->setSort(true);
		}
		/* descargar el resultado */
		header('Expires: Thu, 27 Mar 1980 23:59:00 GMT'); /* la pagina expira en una fecha pasada */
		header('Last-Modified: '.gmdate("D, d M Y H:i:s") . ' GMT'); /* ultima actualizacion ahora cuando la cargamos */
		header('Cache-Control: no-cache, must-revalidate'); /* no guardar en CACHE */
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="' . $sTituloRpt . '.xlsx"');
		header('Cache-Control: max-age = 0');
		$objWriter=PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->setPreCalculateFormulas(false);
		$objWriter->save('php://output');
		die();
	} else {
		echo $sError;
	}
} else {
	echo $sError;
}
?>