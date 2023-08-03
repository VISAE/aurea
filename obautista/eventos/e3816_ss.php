<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2023 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Version 2.29.3 martes, 4 de abril de 2023
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
$iNumVariables = 5;
for ($k = 3; $k <= $iNumVariables; $k++){
	if (isset($_REQUEST['v' . $k]) == 0) {
		$_REQUEST['v' . $k] = '';
	}
}
//Validar las variables.
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
	$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto != '') {
		$objDB->dbPuerto = $APP->dbpuerto;
	}
	//Validar permisos.
	list($bEntra, $sDebugP) = seg_revisa_permisoV3(3816, 6, $idTercero, $objDB);
	if (!$bEntra) {
		$sError = 'No tiene permiso para consultar este reporte [Mod 3816 : 6]';
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
	$cipa01periodo = $_REQUEST['v3'];
	$cipa01alcance = $_REQUEST['v4'];
	$cipa01clase = $_REQUEST['v5'];
	$cipa01estado = $_REQUEST['v6'];
	$cipa01escuela = $_REQUEST['v7'];
	$cipa01programa = $_REQUEST['v8'];
	$cipa01zona = $_REQUEST['v9'];
	$cipa01centro = $_REQUEST['v10'];
	$cipa01idcurso = $_REQUEST['v11'];
	$cipa02forma = $_REQUEST['v12'];
	//$cipa02fecha = $_REQUEST['v13'];
	$cipa02fechaini = $_REQUEST['v13'];
	$cipa02fechafin = $_REQUEST['v14'];
	//$cipa01est_proyectados = $_REQUEST['v15'];
	//$cipa01est_asistentes = $_REQUEST['v16'];
	$sSQLadd = '';
	$sSQLadd1 = '';
	if ($cipa01periodo != '') {
		$sSQLadd1 = $sSQLadd1 . 'T1.cipa01periodo=' . $cipa01periodo . ' AND ';
	}
		if ($cipa01alcance != '') {
		$sSQLadd1 = $sSQLadd1 . 'T1.cipa01alcance=' . $cipa01alcance . ' AND ';
	}
	if ($cipa01clase != '') {
		$sSQLadd1 = $sSQLadd1 . 'T1.cipa01clase=' . $cipa01clase . ' AND ';
	}
	if ($cipa01estado != '') {
		$sSQLadd1 = $sSQLadd1 . 'T1.cipa01estado=' . $cipa01estado . ' AND ';
	}
	if ($cipa01escuela != '') {
		$sSQLadd1 = $sSQLadd1 . 'T1.cipa01escuela=' . $cipa01escuela . ' AND ';
	}
	if ($cipa01programa != '') {
		$sSQLadd1 = $sSQLadd1 . 'T1.cipa01programa=' . $cipa01programa . ' AND ';
	}
	if ($cipa01zona != '') {
		$sSQLadd1 = $sSQLadd1 . 'T1.cipa01zona=' . $cipa01zona . ' AND ';
	}
	if ($cipa01centro != '') {
		$sSQLadd1 = $sSQLadd1 . 'T1.cipa01centro=' . $cipa01centro . ' AND ';
	}
	if ($cipa01idcurso != '') {
		$sSQLadd1 = $sSQLadd1 . 'T1.cipa01idcurso=' . $cipa01idcurso . ' AND ';
	}
	if ($cipa02forma != '') {
		$sSQLadd1 = $sSQLadd1 . 'TB.cipa02forma=' . $cipa02forma . ' AND ';
	}
	/*
	if ($cipa02fecha != '') {
		$sSQLadd1 = $sSQLadd1 . 'TB.cipa02fecha=' . $cipa02fecha . ' AND ';
	}
	*/
	if ($cipa02fechaini != '') {
		$sSQLadd1 = $sSQLadd1 . 'TB.cipa02fecha>=' . $cipa02fechaini . ' AND ';
	}
	if ($cipa02fechafin != '') {
		$sSQLadd1 = $sSQLadd1 . 'TB.cipa02fecha<=' . $cipa02fechafin . ' AND ';
	}
	
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
	$sColTope = 'M';
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
	PHPEXCEL_Escribir($objHoja, 0, $iFila, 'Reporte de jornadas CIPAS');
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
		'Fecha', 'Periodo', 'Escuela', 'Programa', 'Curso', 
		'Zona', 'Centro', 'Alcance', 'Clase', 'Estado', 
		'Modalidad', 'Est Inscritos', 'Est asistentes'
	);
	$aAnchos = array(
		13, 13, 13, 13, 13,
		13, 13, 13, 13, 13,
		13, 13, 13
	);
	for ($k = 0; $k <= 12; $k++) {
		PHPEXCEL_Escribir($objHoja, $k, $iFila, $aTitulos[$k]);
		$sColumna = columna_Letra($k);
		$objHoja->getColumnDimension($sColumna)->setWidth($aAnchos[$k]);
		PHPExcel_Justificar_Celda_HorizontalCentro($objContenedor, $sColumna . $iFila);
	}
	//PHPExcel_Mexclar_Celdas($objContenedor, 'A' . $iFila . ':B' . $iFila . '');
	PHPExcel_Formato_Fuente_Celda($objContenedor, 'A' . $iFila . ':' . $sColTope . $iFila, '10', 'Yu Gothic', 'Ne', true, false, false);
	$iFila++;
	//-- Fin del area no comparada
	$acipa02forma=array('Virtual', 'In Situ');
	$sSQL = 'SELECT TB.cipa02consec, T2.exte02nombre, T12.core12sigla, TB.cipa02forma, TB.cipa02lugar, TB.cipa02link, T9.core09nombre, T40.unad40nombre, T23.unad23nombre, T24.unad24nombre, T13.cipa13nombre, T14.cipa14nombre, T11.cipa11nombre, TB.cipa02forma, TB.cipa02fecha, TB.cipa02horaini, TB.cipa02minini, TB.cipa02horafin, TB.cipa02minfin, TB.cipa02numinscritos, TB.cipa02numparticipantes, TB.cipa02tematica 
	FROM exte02per_aca AS T2, cipa02jornada AS TB, cipa01oferta AS T1, core12escuela AS T12, core09programa AS T9, unad40curso AS T40, unad23zona AS T23, unad24sede AS T24, cipa13alcance AS T13, cipa14clasecipas AS T14, cipa11estado AS T11 
	WHERE ' . $sSQLadd1 . ' TB.cipa02idoferta=T1.cipa01id AND T1.cipa01periodo=T2.exte02id AND T1.cipa01escuela=T12.core12id AND T1.cipa01programa=T9.core09id AND T1.cipa01idcurso=T40.unad40id AND T1.cipa01zona=T23.unad23id AND T1.cipa01centro=T24.unad24id AND T1.cipa01alcance=T13.cipa13id AND T1.cipa01clase=T14.cipa14id AND T1.cipa01estado=T11.cipa11id ' . $sSQLadd . ' 
	ORDER BY TB.cipa02fecha, TB.cipa02horaini, TB.cipa02minini';
	
	$tabla = $objDB->ejecutasql($sSQL);
	if ($bDebug) {
		PHPEXCEL_Escribir($objHoja, 1, $iFila, $sSQL);
		$iFila++;
	}
	while ($fila = $objDB->sf($tabla)) {
		PHPEXCEL_Escribir($objHoja, 0, $iFila, fecha_desdenumero($fila['cipa02fecha']));
		PHPEXCEL_Escribir($objHoja, 1, $iFila, $fila['exte02nombre']);
		PHPEXCEL_Escribir($objHoja, 2, $iFila, $fila['core12sigla']);
		PHPEXCEL_Escribir($objHoja, 3, $iFila, $fila['core09nombre']);
		PHPEXCEL_Escribir($objHoja, 4, $iFila, $fila['unad40nombre']);
		PHPEXCEL_Escribir($objHoja, 5, $iFila, $fila['unad23nombre']);
		PHPEXCEL_Escribir($objHoja, 6, $iFila, $fila['unad24nombre']);
		PHPEXCEL_Escribir($objHoja, 7, $iFila, $fila['cipa13nombre']);
		PHPEXCEL_Escribir($objHoja, 8, $iFila, $fila['cipa14nombre']);
		PHPEXCEL_Escribir($objHoja, 9, $iFila, $fila['cipa11nombre']);
		PHPEXCEL_Escribir($objHoja, 10, $iFila, $acipa02forma[$fila['cipa02forma']]);
		PHPEXCEL_Escribir($objHoja, 11, $iFila, $fila['cipa02numinscritos']);
		PHPEXCEL_Escribir($objHoja, 12, $iFila, $fila['cipa02numparticipantes']);
		/*
		
		PHPEXCEL_Escribir($objHoja, 10, $iFila, $fila['cipa02fechaini']);
		PHPEXCEL_Escribir($objHoja, 11, $iFila, $fila['cipa02fechafin']);
		*/
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
