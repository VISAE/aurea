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
	'', '', '', 'Nombre'
);
$aTipos = array(
	0, 0, 0, 1
);
$iNumVariables = 3;
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
	list($bEntra, $sDebugP) = seg_revisa_permisoV3(2934, 6, $idTercero, $objDB);
	if (!$bEntra) {
		$sError = 'No tiene permiso para consultar este reporte [Mod 2934 : 6]';
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
	$bnombre = cadena_Validar(trim($_REQUEST['v3']));
	$sSubtitulo = '';
	$sDetalle = '';
	$sSQLadd = '';
	$sSQLadd1 = '';
	// ------------------------------------------------
	// Fin de las condiciones de la consulta
	// ------------------------------------------------
	$sCampos = 'SELECT TB.visa34consec, TB.visa34id, TB.visa34nombre, T4.visa46nombre, TB.visa34activo, TB.visa34grupotipologia';
	$sConsulta = 'FROM visa34convtipo AS TB, visa46grupotipologia AS T4 
	WHERE ' . $sSQLadd1 . ' TB.visa34id>0 AND TB.visa34grupotipologia=T4.visa46id ' . $sSQLadd . '';
	$sOrden = 'ORDER BY TB.visa34consec';
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
	$objExcel->getProperties()->setDescription('Reporte 2934 del SII 5.0 en ' . $sServerRpt . ' creado en ' . fecha_hoy() . ' ' . formato_horaminuto(fecha_hora(), fecha_minuto()));
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
	PHPEXCEL_Escribir($objHoja, 0, $iFila, 'Tipo de convocatoria');
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
	$avisa34grupotipologia = array();
	$tabla = $objDB->ejecutasql($sSQLReporte);
	if ($bDebug) {
		PHPEXCEL_Escribir($objHoja, 1, $iFila, $sSQL);
		$iFila++;
	}
	while ($fila = $objDB->sf($tabla)) {
		PHPEXCEL_Escribir($objHoja, 0, $iFila, $fila['visa34consec']);
		PHPEXCEL_Escribir($objHoja, 1, $iFila, $fila['visa34nombre']);
		$et_visa34rolestudiante = 'Si';
		if ($fila['visa34rolestudiante'] == 0) {
			$et_visa34rolestudiante = 'No';
		}
		PHPEXCEL_Escribir($objHoja, 2, $iFila, $et_visa34rolestudiante);
		$et_visa34roladministrativo = 'Si';
		if ($fila['visa34roladministrativo'] == 0) {
			$et_visa34roladministrativo = 'No';
		}
		PHPEXCEL_Escribir($objHoja, 3, $iFila, $et_visa34roladministrativo);
		$et_visa34rolacademico = 'Si';
		if ($fila['visa34rolacademico'] == 0) {
			$et_visa34rolacademico = 'No';
		}
		PHPEXCEL_Escribir($objHoja, 4, $iFila, $et_visa34rolacademico);
		$et_visa34rolaspirante = 'Si';
		if ($fila['visa34rolaspirante'] == 0) {
			$et_visa34rolaspirante = 'No';
		}
		PHPEXCEL_Escribir($objHoja, 5, $iFila, $et_visa34rolaspirante);
		$et_visa34rolegresado = 'Si';
		if ($fila['visa34rolegresado'] == 0) {
			$et_visa34rolegresado = 'No';
		}
		PHPEXCEL_Escribir($objHoja, 6, $iFila, $et_visa34rolegresado);
		$et_visa34rolexterno = 'Si';
		if ($fila['visa34rolexterno'] == 0) {
			$et_visa34rolexterno = 'No';
		}
		PHPEXCEL_Escribir($objHoja, 7, $iFila, $et_visa34rolexterno);
		$et_visa34grupotipologia = '';
		if ($fila['visa34grupotipologia'] != 0) {
			if (isset($avisa34grupotipologia[$fila['visa34grupotipologia']]) == 0) {
				$sDato = '{' . $fila['visa34grupotipologia'] . '}';
				$sSQL = 'SELECT visa46nombre FROM visa46grupotipologia WHERE visa46id=' . $fila['visa34grupotipologia'] . '';
				$tablad = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($tablad) > 0) {
					$filad = $objDB->sf($tablad);
					$sDato = $filad['visa46nombre'];
				}
				$avisa34grupotipologia[$fila['visa34grupotipologia']] = $sDato;
			}
			$et_visa34grupotipologia = $avisa34grupotipologia[$fila['visa34grupotipologia']];
		}
		PHPEXCEL_Escribir($objHoja, 8, $iFila, $et_visa34grupotipologia);
		$et_visa34activo = 'Si';
		if ($fila['visa34activo'] == 0) {
			$et_visa34activo = 'No';
		}
		PHPEXCEL_Escribir($objHoja, 9, $iFila, $et_visa34activo);
		$et_visa34aplicazona = 'Si';
		if ($fila['visa34aplicazona'] == 0) {
			$et_visa34aplicazona = 'No';
		}
		PHPEXCEL_Escribir($objHoja, 10, $iFila, $et_visa34aplicazona);
		$et_visa34aplicacentro = 'Si';
		if ($fila['visa34aplicacentro'] == 0) {
			$et_visa34aplicacentro = 'No';
		}
		PHPEXCEL_Escribir($objHoja, 11, $iFila, $et_visa34aplicacentro);
		$et_visa34aplicaescuela = 'Si';
		if ($fila['visa34aplicaescuela'] == 0) {
			$et_visa34aplicaescuela = 'No';
		}
		PHPEXCEL_Escribir($objHoja, 12, $iFila, $et_visa34aplicaescuela);
		$et_visa34aplicaprograma = 'Si';
		if ($fila['visa34aplicaprograma'] == 0) {
			$et_visa34aplicaprograma = 'No';
		}
		PHPEXCEL_Escribir($objHoja, 13, $iFila, $et_visa34aplicaprograma);
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

