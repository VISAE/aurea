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
	'', '', '', 'Nombre', 'Estado'
);
$aTipos = array(
	0, 0, 0, 1, 0
);
$iNumVariables = 4;
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
	list($bEntra, $sDebugP) = seg_revisa_permisoV3(2935, 6, $idTercero, $objDB);
	if (!$bEntra) {
		$sError = 'No tiene permiso para consultar este reporte [Mod 2935 : 6]';
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
	$bestado = numeros_validar($_REQUEST['v4']);
	$sSubtitulo = '';
	$sDetalle = '';
	$sSQLadd = '';
	$sSQLadd1 = '';
	// ------------------------------------------------
	// Fin de las condiciones de la consulta
	// ------------------------------------------------
	$sCampos = 'SELECT TB.visa35consec, TB.visa35id, T3.visa34nombre, TB.visa35nombre, TB.visa35estado, TB.visa35idtipo';
	$sConsulta = 'FROM visa35convocatoria AS TB, visa34convtipo AS T3 
	WHERE ' . $sSQLadd1 . ' TB.visa35id>0 AND TB.visa35idtipo=T3.visa34id ' . $sSQLadd . '';
	$sOrden = 'ORDER BY TB.visa35consec';
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
	$objExcel->getProperties()->setDescription('Reporte 2935 del SII 5.0 en ' . $sServerRpt . ' creado en ' . fecha_hoy() . ' ' . formato_horaminuto(fecha_hora(), fecha_minuto()));
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
	PHPEXCEL_Escribir($objHoja, 0, $iFila, 'Convocatorias');
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
	$avisa35idtipo = array();
	$avisa35idzona = array();
	$avisa35idcentro = array();
	$avisa35idescuela = array();
	$avisa35idprograma = array();
	$avisa35nivelforma = array();
	$avisa35idproducto = array();
	$tabla = $objDB->ejecutasql($sSQLReporte);
	if ($bDebug) {
		PHPEXCEL_Escribir($objHoja, 1, $iFila, $sSQL);
		$iFila++;
	}
	while ($fila = $objDB->sf($tabla)) {
		PHPEXCEL_Escribir($objHoja, 0, $iFila, $fila['visa35consec']);
		$et_visa35idtipo = '';
		if ($fila['visa35idtipo'] != 0) {
			if (isset($avisa35idtipo[$fila['visa35idtipo']]) == 0) {
				$sDato = '{' . $fila['visa35idtipo'] . '}';
				$sSQL = 'SELECT visa34nombre FROM visa34convtipo WHERE visa34id=' . $fila['visa35idtipo'] . '';
				$tablad = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($tablad) > 0) {
					$filad = $objDB->sf($tablad);
					$sDato = $filad['visa34nombre'];
				}
				$avisa35idtipo[$fila['visa35idtipo']] = $sDato;
			}
			$et_visa35idtipo = $avisa35idtipo[$fila['visa35idtipo']];
		}
		PHPEXCEL_Escribir($objHoja, 1, $iFila, $et_visa35idtipo);
		PHPEXCEL_Escribir($objHoja, 2, $iFila, $fila['visa35nombre']);
		$et_visa35idzona = '';
		if ($fila['visa35idzona'] != 0) {
			if (isset($avisa35idzona[$fila['visa35idzona']]) == 0) {
				$sDato = '{' . $fila['visa35idzona'] . '}';
				$sSQL = 'SELECT unad23nombre FROM unad23zona WHERE unad23id=' . $fila['visa35idzona'] . '';
				$tablad = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($tablad) > 0) {
					$filad = $objDB->sf($tablad);
					$sDato = $filad['unad23nombre'];
				}
				$avisa35idzona[$fila['visa35idzona']] = $sDato;
			}
			$et_visa35idzona = $avisa35idzona[$fila['visa35idzona']];
		}
		PHPEXCEL_Escribir($objHoja, 3, $iFila, $et_visa35idzona);
		$et_visa35idcentro = '';
		if ($fila['visa35idcentro'] != 0) {
			if (isset($avisa35idcentro[$fila['visa35idcentro']]) == 0) {
				$sDato = '{' . $fila['visa35idcentro'] . '}';
				$sSQL = 'SELECT unad24nombre FROM unad24sede WHERE unad24id=' . $fila['visa35idcentro'] . '';
				$tablad = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($tablad) > 0) {
					$filad = $objDB->sf($tablad);
					$sDato = $filad['unad24nombre'];
				}
				$avisa35idcentro[$fila['visa35idcentro']] = $sDato;
			}
			$et_visa35idcentro = $avisa35idcentro[$fila['visa35idcentro']];
		}
		PHPEXCEL_Escribir($objHoja, 4, $iFila, $et_visa35idcentro);
		$et_visa35idescuela = '';
		if ($fila['visa35idescuela'] != 0) {
			if (isset($avisa35idescuela[$fila['visa35idescuela']]) == 0) {
				$sDato = '{' . $fila['visa35idescuela'] . '}';
				$sSQL = 'SELECT core12nombre FROM core12escuela WHERE core12id=' . $fila['visa35idescuela'] . '';
				$tablad = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($tablad) > 0) {
					$filad = $objDB->sf($tablad);
					$sDato = $filad['core12nombre'];
				}
				$avisa35idescuela[$fila['visa35idescuela']] = $sDato;
			}
			$et_visa35idescuela = $avisa35idescuela[$fila['visa35idescuela']];
		}
		PHPEXCEL_Escribir($objHoja, 5, $iFila, $et_visa35idescuela);
		$et_visa35idprograma = '';
		if ($fila['visa35idprograma'] != 0) {
			if (isset($avisa35idprograma[$fila['visa35idprograma']]) == 0) {
				$sDato = '{' . $fila['visa35idprograma'] . '}';
				$sSQL = 'SELECT core09nombre FROM core09programa WHERE core09id=' . $fila['visa35idprograma'] . '';
				$tablad = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($tablad) > 0) {
					$filad = $objDB->sf($tablad);
					$sDato = $filad['core09nombre'];
				}
				$avisa35idprograma[$fila['visa35idprograma']] = $sDato;
			}
			$et_visa35idprograma = $avisa35idprograma[$fila['visa35idprograma']];
		}
		PHPEXCEL_Escribir($objHoja, 6, $iFila, $et_visa35idprograma);
		$et_visa35gruponivel = 'Si';
		if ($fila['visa35gruponivel'] == 0) {
			$et_visa35gruponivel = 'No';
		}
		PHPEXCEL_Escribir($objHoja, 7, $iFila, $et_visa35gruponivel);
		$et_visa35nivelforma = '';
		if ($fila['visa35nivelforma'] != 0) {
			if (isset($avisa35nivelforma[$fila['visa35nivelforma']]) == 0) {
				$sDato = '{' . $fila['visa35nivelforma'] . '}';
				$sSQL = 'SELECT core22nombre FROM core22nivelprograma WHERE core22id=' . $fila['visa35nivelforma'] . '';
				$tablad = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($tablad) > 0) {
					$filad = $objDB->sf($tablad);
					$sDato = $filad['core22nombre'];
				}
				$avisa35nivelforma[$fila['visa35nivelforma']] = $sDato;
			}
			$et_visa35nivelforma = $avisa35nivelforma[$fila['visa35nivelforma']];
		}
		PHPEXCEL_Escribir($objHoja, 8, $iFila, $et_visa35nivelforma);
		PHPEXCEL_Escribir($objHoja, 9, $iFila, $fila['visa35estado']);
		PHPEXCEL_Escribir($objHoja, 10, $iFila, $fila['visa35numcupos']);
		PHPEXCEL_Escribir($objHoja, 11, $iFila, $fila['visa35fecha_apertura']);
		PHPEXCEL_Escribir($objHoja, 12, $iFila, $fila['visa35fecha_liminscrip']);
		PHPEXCEL_Escribir($objHoja, 13, $iFila, $fila['visa35fecha_limrevdoc']);
		PHPEXCEL_Escribir($objHoja, 14, $iFila, $fila['visa35fecha_examenes']);
		PHPEXCEL_Escribir($objHoja, 15, $iFila, $fila['visa35fecha_seleccion']);
		PHPEXCEL_Escribir($objHoja, 16, $iFila, $fila['visa35fecha_ratificacion']);
		PHPEXCEL_Escribir($objHoja, 17, $iFila, $fila['visa35fecha_cierra']);
		PHPEXCEL_Escribir($objHoja, 18, $iFila, $fila['visa35presentacion']);
		PHPEXCEL_Escribir($objHoja, 19, $iFila, $fila['visa35total_inscritos']);
		PHPEXCEL_Escribir($objHoja, 20, $iFila, $fila['visa35total_autorizados']);
		PHPEXCEL_Escribir($objHoja, 21, $iFila, $fila['visa35total_presentaex']);
		PHPEXCEL_Escribir($objHoja, 22, $iFila, $fila['visa35total_aprobados']);
		PHPEXCEL_Escribir($objHoja, 23, $iFila, $fila['visa35total_admitidos']);
		PHPEXCEL_Escribir($objHoja, 24, $iFila, $fila['visa35idconvenio']);
		PHPEXCEL_Escribir($objHoja, 25, $iFila, $fila['visa35idresolucion']);
		$et_visa35idproducto = '';
		if ($fila['visa35idproducto'] != 0) {
			if (isset($avisa35idproducto[$fila['visa35idproducto']]) == 0) {
				$sDato = '{' . $fila['visa35idproducto'] . '}';
				$sSQL = 'SELECT cart01nombre FROM cart01productos WHERE cart01id=' . $fila['visa35idproducto'] . '';
				$tablad = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($tablad) > 0) {
					$filad = $objDB->sf($tablad);
					$sDato = $filad['cart01nombre'];
				}
				$avisa35idproducto[$fila['visa35idproducto']] = $sDato;
			}
			$et_visa35idproducto = $avisa35idproducto[$fila['visa35idproducto']];
		}
		PHPEXCEL_Escribir($objHoja, 26, $iFila, $et_visa35idproducto);
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

