<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2021 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Version 2.25.10c miércoles, 7 de abril de 2021
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
require $APP->rutacomun . 'libexcel_ss.php';
require $APP->rutacomun . 'vendor/autoload.php';
$sIdioma = AUREA_Idioma();
$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
if (!file_exists($mensajes_todas)) {
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
}
$mensajes_2932 = 'lg/lg_2932_' . $sIdioma . '.php';
if (!file_exists($mensajes_2932)) {
	$mensajes_2932 = 'lg/lg_2932_es.php';
}
require $mensajes_todas;
require $mensajes_2932;
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
$iCodModulo = 2931;
if (isset($_REQUEST['clave']) == 0) {
	$_REQUEST['clave'] = '';
}
if (isset($_REQUEST['rdebug']) == 0) {
	$_REQUEST['rdebug'] = 0;
}
$aNombres = array(
	'', '', '', 'Ciclo', 'Titulo',
	'Estado'
);
$aTipos = array(
	0, 0, 0, 0, 1,
	0
);
$iNumVariables = 5;
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
	//Validar permisos.
	$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto != '') {
		$objDB->dbPuerto = $APP->dbpuerto;
	}
	list($bEntra, $sDebugP) = seg_revisa_permisoV3(2931, 6, $idTercero, $objDB);
	if (!$bEntra) {
		$sError = 'No tiene permiso para consultar este reporte [Mod 2931 : 6]';
	}
}
if ($sError == '') {
	if ($_REQUEST['rdebug'] == 1) {
		$bDebug = true;
	}
	$sTituloRpt1 = 'Reporte E-monitores';
	$sTituloRpt2 = 'Acceso a cursos';
	$sFormato = 'formato.xlsx';
	if (!file_exists($sFormato)) {
		$sError = 'Formato no encontrado {' . $sFormato . '}';
	}
}
if ($sError == '') {
	// ------------------------------------------------
	// Leemos los parametros de entrada.
	// ------------------------------------------------
	$idCiclo = numeros_validar($_REQUEST['v3']);
	$sTituloCiclo = cadena_Validar($_REQUEST['v4']);
	$bEstado = numeros_validar($_REQUEST['v5']);
	$sSubtitulo = '';
	$sDetalle = '';
	$sSQLadd = '';
	$sSQLadd1 = '';
	$sSQLadd2 = '';
	$sSQLadd3 = '';
	if ($idCiclo == '') {
		$sError = 'No se ha establecido el ciclo de monitoria';
	}
	if ($sTituloCiclo == '') {
		$sError = 'No se ha establecido el titulo del ciclo de monitoria';
	}
	if ($bEstado != '') {
		$sSQLadd = $sSQLadd . ' AND TB.plab32estado=' . $bEstado . '';
	}
	/*
	if ($bdocumento != '') {
		$sSQLadd = $sSQLadd . ' AND T2.unad11doc LIKE "%' . $bdocumento . '%"';
	}
	if ($bnombre != '') {
		$sBase = mb_strtoupper($bnombre);
		$aNoms = explode(' ', $sBase);
		for ($k = 1; $k <= count($aNoms); $k++) {
			$sCadena = $aNoms[$k - 1];
			if ($sCadena != '') {
				$sSQLadd = $sSQLadd . ' AND T2.unad11razonsocial LIKE "%' . $sCadena . '%"';
				//$sSQLadd1 = $sSQLadd1 . 'TB.unad11razonsocial LIKE "%' . $sCadena . '%" AND ';
			}
		}
	}
	*/
	// ------------------------------------------------
	// Fin de las condiciones de la consulta
	// ------------------------------------------------
	$sCampos = 'SELECT TB.plab32idciclo, T2.unad11razonsocial AS C2_nombre, TB.plab32estado, TB.plab32fechaingreso, TB.plab32fechafin, 
	TB.plab32idtercero, T2.unad11tipodoc AS C2_td, T2.unad11doc AS C2_doc, T2.unad11correoinstitucional AS C2_correoins';
	$sConsulta = 'FROM plab32emonitor AS TB, unad11terceros AS T2 
	WHERE ' . $sSQLadd1 . ' TB.plab32idciclo=' . $idCiclo . ' AND TB.plab32idtercero=T2.unad11id ' . $sSQLadd . '';
	$sOrden = 'ORDER BY TB.plab32idtercero';
	$sSQLReporte1 = $sCampos . ' ' . $sConsulta . ' ' . $sOrden;
	$sCampos = 'SELECT TB.plab33idciclo, T2.unad11razonsocial AS C2_nombre, TB.plab33idperiodo, TB.plab33idcurso, TB.plab33id, TB.plab33activo, 
	TB.plab33idmonitor, T2.unad11tipodoc AS C2_td, T2.unad11doc AS C2_doc, T40.unad40titulo, T40.unad40nombre';
	$sConsulta = 'FROM plab33emoncurso AS TB, unad11terceros AS T2, unad40curso AS T40 
	WHERE ' . $sSQLadd3 . ' TB.plab33idciclo=' . $idCiclo . ' AND TB.plab33idmonitor=T2.unad11id AND TB.plab33idcurso=T40.unad40id ' . $sSQLadd2 . '';
	$sOrden = 'ORDER BY TB.plab33idmonitor, TB.plab33idperiodo DESC, TB.plab33idcurso DESC';
	$sSQLReporte2 = $sCampos . ' ' . $sConsulta . ' ' . $sOrden;
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
	$objExcel->getProperties()->setTitle($sTituloRpt1);
	$objExcel->getProperties()->setSubject($sTituloRpt1);
	$objExcel->getProperties()->setDescription('Reporte 2931 del SII 5.0 en ' . $sServerRpt . ' creado en ' . fecha_hoy() . ' ' . formato_horaminuto(fecha_hora(), fecha_minuto()));
	// Inicio Reporte 1
	$objHoja = $objExcel->getActiveSheet();
	$objHoja->setTitle(substr($sTituloRpt1, 0, 30));
	$objContenedor = $objHoja;
	$sColTope = 'G';
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
	PHPEXCEL_Escribir($objHoja, 0, $iFila, $sTituloRpt1 . ' ' . $sTituloCiclo);
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
		'Tipo Doc', 'Documento', 'Nombre', 'Correo institucional', 'Fecha ingreso',
		'Fecha retiro', 'Estado'
	);
	$aAnchos = array(
		8, 13, 40, 30, 13,
		13, 10
	);
	for ($k = 0; $k <= 6; $k++) {
		PHPEXCEL_Escribir($objHoja, $k, $iFila, $aTitulos[$k]);
		$sColumna = columna_Letra($k);
		$objHoja->getColumnDimension($sColumna)->setWidth($aAnchos[$k]);
		PHPExcel_Justificar_Celda_HorizontalCentro($objContenedor, $sColumna . $iFila);
	}
	//PHPExcel_Mexclar_Celdas($objContenedor, 'A' . $iFila . ':B' . $iFila . '');
	PHPExcel_Formato_Fuente_Celda($objContenedor, 'A' . $iFila . ':' . $sColTope . $iFila, '10', 'Yu Gothic', 'Ne', true, false, false);
	$iFila++;
	// $avisa40idconvocatoria = array();
	$tabla = $objDB->ejecutasql($sSQLReporte1);
	if ($bDebug) {
		PHPEXCEL_Escribir($objHoja, 1, $iFila, $sSQL);
		$iFila++;
	}
	while ($fila = $objDB->sf($tabla)) {
		PHPEXCEL_Escribir($objHoja, 0, $iFila, $fila['C2_td']);
		PHPEXCEL_Escribir($objHoja, 1, $iFila, $fila['C2_doc']);
		PHPEXCEL_Escribir($objHoja, 2, $iFila, $fila['C2_nombre']);
		PHPEXCEL_Escribir($objHoja, 3, $iFila, $fila['C2_correoins']);
		$et_plab32fechaingreso = fecha_desdenumero($fila['plab32fechaingreso']);
		PHPEXCEL_Escribir($objHoja, 4, $iFila, $et_plab32fechaingreso);
		$et_plab32fechafin = fecha_desdenumero($fila['plab32fechafin']);
		PHPEXCEL_Escribir($objHoja, 5, $iFila, $et_plab32fechafin);
		$et_plab32estado = '';
		if ($fila['plab32estado'] != '') {
			if (isset($aplab32estado[$fila['plab32estado']]) != 0) {
				$et_plab32estado = $aplab32estado[$fila['plab32estado']];
			}
		}
		PHPEXCEL_Escribir($objHoja, 6, $iFila, $et_plab32estado);
		$iFila++;
	}
	PHPExcel_RellenarCeldas($objContenedor, 'A' . $iFilaBase . ':' . $sColTope . $iFila, 'Bl', true);
	PHPEXCEL_Escribir($objHoja, 0, 1, '');
	PHPExcel_RellenarCeldas($objContenedor, 'A1', 'Bl', true);
	// Fin Reporte 1
	//
	// Inicio Reporte 2
	$objExcel->createSheet();
	$objExcel->setActiveSheetIndex(1);
	$objHoja = $objExcel->getActiveSheet();
	$objHoja->setTitle(substr($sTituloRpt2, 0, 30));
	$objContenedor = $objHoja;
	$sColTope = 'G';
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
	PHPEXCEL_Escribir($objHoja, 0, $iFila, $sTituloRpt2 . ' ' . $sTituloCiclo);
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
		'Tipo Doc', 'Documento', 'Nombre', 'Periodo', 'Cod. Curso',
		'Curso', 'Activo'
	);
	$aAnchos = array(
		8, 13, 40, 40, 10,
		40, 8
	);
	for ($k = 0; $k <= 6; $k++) {
		PHPEXCEL_Escribir($objHoja, $k, $iFila, $aTitulos[$k]);
		$sColumna = columna_Letra($k);
		$objHoja->getColumnDimension($sColumna)->setWidth($aAnchos[$k]);
		PHPExcel_Justificar_Celda_HorizontalCentro($objContenedor, $sColumna . $iFila);
	}
	//PHPExcel_Mexclar_Celdas($objContenedor, 'A' . $iFila . ':B' . $iFila . '');
	PHPExcel_Formato_Fuente_Celda($objContenedor, 'A' . $iFila . ':' . $sColTope . $iFila, '10', 'Yu Gothic', 'Ne', true, false, false);
	$iFila++;
	// $avisa40idconvocatoria = array();
	$tabla = $objDB->ejecutasql($sSQLReporte2);
	if ($bDebug) {
		PHPEXCEL_Escribir($objHoja, 1, $iFila, $sSQL);
		$iFila++;
	}
	$aplab33idperiodo = array();
	while ($fila = $objDB->sf($tabla)) {
		PHPEXCEL_Escribir($objHoja, 0, $iFila, $fila['C2_td']);
		PHPEXCEL_Escribir($objHoja, 1, $iFila, $fila['C2_doc']);
		PHPEXCEL_Escribir($objHoja, 2, $iFila, $fila['C2_nombre']);
		$et_plab33idperiodo = '';
		if ($fila['plab33idperiodo'] != 0) {
			if (isset($aplab33idperiodo[$fila['plab33idperiodo']]) == 0) {
				$sDato = '{' . $fila['plab33idperiodo'] . '}';
				$sSQL = 'SELECT exte02titulo FROM exte02per_aca WHERE exte02id=' . $fila['plab33idperiodo'] . '';
				$tablad = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($tablad) > 0) {
					$filad = $objDB->sf($tablad);
					$sDato = $filad['exte02titulo'];
				}
				$aplab33idperiodo[$fila['plab33idperiodo']] = $sDato;
			}
			$et_plab33idperiodo = $aplab33idperiodo[$fila['plab33idperiodo']];
		}
		PHPEXCEL_Escribir($objHoja, 3, $iFila, $et_plab33idperiodo);
		PHPEXCEL_Escribir($objHoja, 4, $iFila, $fila['unad40titulo']);
		PHPEXCEL_Escribir($objHoja, 5, $iFila, $fila['unad40nombre']);
		$et_plab33activo = $ETI['si'];
		if ($fila['plab33activo'] != 1) {
			$et_plab33activo = $ETI['no'];
		}
		PHPEXCEL_Escribir($objHoja, 6, $iFila, $et_plab33activo);
		$iFila++;
	}
	PHPExcel_RellenarCeldas($objContenedor, 'A' . $iFilaBase . ':' . $sColTope . $iFila, 'Bl', true);
	PHPEXCEL_Escribir($objHoja, 0, 1, '');
	PHPExcel_RellenarCeldas($objContenedor, 'A1', 'Bl', true);
	// Fin Reporte 2
	$objExcel->setActiveSheetIndex(0);
	$objDB->CerrarConexion();
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
	header('Content-Disposition: attachment;filename="' . $sTituloRpt1 . '.xlsx"');
	header('Cache-Control: max-age=0');
	$objWriter = new Xlsx($objExcel);
	$objWriter->save('php://output');
	die();
} else {
	echo $sError;
}

