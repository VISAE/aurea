<?php
/*
--- © Omar Augusto Bautista Mora - UNAD - 2024 ---
--- omar.bautista@unad.edu.co - http://www.unad.edu.co
--- Modelo Version 1.0 Miércoles, 2 de agosto de 2024
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
require $APP->rutacomun . 'libsai.php';
require $APP->rutacomun . 'libdatos.php';
$mensajes_3073 = 'lg/lg_3073_' . $_SESSION['unad_idioma'] . '.php';
if (!file_exists($mensajes_3073)) {
	$mensajes_3073 = 'lg/lg_3073_es.php';
}
require $mensajes_3073;
if ($_SESSION['unad_id_tercero'] == 0) {
	die();
}
$_SESSION['u_ultimominuto'] = iminutoavance();
$sError = '';
$sDebug = '';
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
if (isset($_REQUEST['v0']) == 0) {
	$_REQUEST['v0'] = $_SESSION['unad_id_tercero'];
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
	/* if (isset($_REQUEST['v3'])==0){($_REQUEST['v3']='');} */
	$idTercero = $_REQUEST['v0'];
	$iAgno = $_REQUEST['v3'];
	$iEstado = $_REQUEST['v4'];
	$iListar = $_REQUEST['v5'];
	$sTituloRpt = $sTituloRpt . ' Atencion virtual ' . $iAgno;
	if ($sError == '') {
		$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto != '') {
			$objDB->dbPuerto = $APP->dbpuerto;
		}
		$sProtocolo = 'http';
		if (isset($_SETVER['HTTPS']) != 0) {
			if ($_SERVER['HTTPS'] == 'on') {
				$sProtocolo = 'https';
			}
		}
		$sServerRpt = $sProtocolo . '://' . $_SERVER['SERVER_NAME'];
		$sNombreUsuario = '[' . $idTercero . '] ';
		$sSQL = 'SELECT unad11razonsocial FROM unad11terceros WHERE unad11id=' . $idTercero . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			$sNombreUsuario = cadena_LimpiarTildes($fila['unad11razonsocial']) . ' [' . $idTercero . '] ';
		}
		$objReader = PHPExcel_IOFactory::createReader('Excel2007');
		$objPHPExcel = $objReader->load($sFormato);
		$objPHPExcel->getProperties()->setCreator($sNombreUsuario . 'http://www.unad.edu.co');
		$objPHPExcel->getProperties()->setLastModifiedBy($sNombreUsuario . 'http://www.unad.edu.co');
		$objPHPExcel->getProperties()->setTitle($sTituloRpt);
		$objPHPExcel->getProperties()->setSubject($sTituloRpt);
		$objPHPExcel->getProperties()->setDescription('Reporte del Formato de Atencion Virtual ' . $sServerRpt . ' creado en ' . fecha_hoy() . ' ' . formato_horaminuto(fecha_hora(), fecha_minuto()));
		$objHoja = $objPHPExcel->getActiveSheet();
		$objHoja->setTitle($sTituloRpt);
		$sColTope = 'S';
		//Imagen del encabezado
		PHPExcel_Justificar_Celda_HorizontalCentro($objPHPExcel, 'A1');
		PHPExcel_Agrega_Dibujo($objPHPExcel, 'Logo', 'Logo', $APP->rutacomun . 'imagenes/rpt_cabeza.jpg', '161', 'A1', '0', false, '0');
		$sFechaImpreso = formato_fechalarga(fecha_hoy(), true);
		PHPExcel_Texto_Tres_Partes($objPHPExcel, $sColTope . '9', ' ', 'Fecha impresión: ', $sFechaImpreso, 'AmOsUn', true, false, 9, 'Calibri', 'AzOsUn');
		PHPExcel_Alinear_Celda_Derecha($objPHPExcel, $sColTope . '9');
		//Titulo 
		$sTituloHoja = cadena_tildes($ETI['titulo']);
		$objHoja->setCellValueByColumnAndRow(0, 10, $sTituloHoja . ' ' . $iAgno);
		PHPExcel_Mexclar_Celdas($objPHPExcel, 'A10:' . $sColTope . '11');
		PHPExcel_Justificar_Celda_HorizontalCentro($objPHPExcel, 'A10');
		PHPExcel_Formato_Fuente_Celda($objPHPExcel, 'A10', '14', 'Yu Gothic', 'AzOsUn', true, false, false);
		//Espacio para el encabezado
		$sSubtitulo = 'Subtitulo';
		$sDetalle = 'Detalle del reporte';
		/*
		$objHoja->setCellValueByColumnAndRow(0, 11, $sSubtitulo);
		PHPExcel_Justificar_Celda_HorizontalCentro($objPHPExcel,'A11');
		PHPExcel_Formato_Fuente_Celda($objPHPExcel,'A11','12','Yu Gothic','AmOsUn',true,false,false);
		PHPExcel_Mexclar_Celdas($objPHPExcel,'A11:'.$sColTope.'11');
		$objHoja->setCellValueByColumnAndRow(0, 12, $sDetalle);
		PHPExcel_Justificar_Celda_HorizontalCentro($objPHPExcel,'A12');
		PHPExcel_Formato_Fuente_Celda($objPHPExcel,'A12','10','Yu Gothic','Ne',true,false,false);
		PHPExcel_Mexclar_Celdas($objPHPExcel,'A12:'.$sColTope.'12');
		*/
		$iFila = 12;
		PHPExcel_RellenarCeldas($objPHPExcel, 'A1:' . $sColTope . $iFila, 'Bl', false);
		$iFila++;
		$iFilaBase = $iFila;
		$aTitulos = array(
			cadena_tildes($ETI['saiu73consec']), cadena_tildes($ETI['msg_fecha']), cadena_tildes($ETI['saiu73hora']), cadena_tildes($ETI['saiu73estado']),cadena_tildes($ETI['saiu73fecharespuesta']), cadena_tildes($ETI['saiu73horarespuesta']), cadena_tildes($ETI['saiu73solucion']),
			cadena_tildes($ETI['saiu73tiposolicitud']), cadena_tildes($ETI['saiu73temasolicitud']), cadena_tildes($ETI['saiu73idunidadcaso']), cadena_tildes($ETI['saiu73idequipocaso']), cadena_tildes($ETI['saiu73idsupervisorcaso']),
			cadena_tildes($ETI['saiu73idresponsablecaso']), cadena_tildes($ETI['saiu73idsolicitante']), cadena_tildes($ETI['saiu73razonsocial']), cadena_tildes($ETI['saiu73idzona']),
			cadena_tildes($ETI['saiu73idcentro']), cadena_tildes($ETI['saiu73idescuela']), cadena_tildes($ETI['saiu73idprograma'])
		);
		$iTitulos = count($aTitulos);		
		for ($k = 0; $k <= 18; $k++) {
			$objHoja->setCellValueByColumnAndRow($k, $iFila, $aTitulos[$k]);
			$sColumna = columna_Letra($k);
			$objPHPExcel->getActiveSheet()->getColumnDimension($sColumna)->setWidth(13);
			PHPExcel_Justificar_Celda_HorizontalCentro($objPHPExcel, $sColumna . $iFila);
		}
		//PHPExcel_Mexclar_Celdas($objPHPExcel,'A'.$iFila.':B'.$iFila.'');
		$objPHPExcel->createSheet();
		$objPHPExcel->setActiveSheetIndex(1);
		$objPHPExcel->getActiveSheet()->setTitle('Resultados Encuesta');
		$objHoja = $objPHPExcel->getActiveSheet();
		$objHoja->setCellValueByColumnAndRow(0, 1, cadena_tildes($ETI['titulo_encuesta_excel']) . ' ' . $iAgno);
		PHPExcel_Mexclar_Celdas($objPHPExcel, 'A1:' . $sColTope . '2');
		PHPExcel_Justificar_Celda_HorizontalCentro($objPHPExcel, 'A1');
		PHPExcel_Formato_Fuente_Celda($objPHPExcel, 'A1', '14', 'Yu Gothic', 'AzOsUn', true, false, false);
		$iFilaHoja2 = 3;
		PHPExcel_RellenarCeldas($objPHPExcel, 'A1:' . $sColTope . $iFilaHoja2, 'Bl', false);
		$iFilaHoja2++;
		$iFilaBase2 = $iFilaHoja2;
		$aTitulos = array(
			cadena_tildes($ETI['saiu73consec']), cadena_tildes($ETI['saiu73idsolicitante']), cadena_tildes($ETI['saiu73razonsocial']), cadena_tildes($ETI['saiu73temasolicitud']), cadena_tildes($ETI['saiu73evalfecha']),
			cadena_tildes($ETI['saiu73evalamabilidad']), cadena_tildes($ETI['saiu73evalamabmotivo']), cadena_tildes($ETI['saiu73evalrapidez']), cadena_tildes($ETI['saiu73evalrapidmotivo']), cadena_tildes($ETI['saiu73evalclaridad']),
			cadena_tildes($ETI['saiu73evalcalridmotivo']), cadena_tildes($ETI['saiu73evalresolvio']), cadena_tildes($ETI['saiu73evalsugerencias']), cadena_tildes($ETI['saiu73evalconocimiento']),
			cadena_tildes($ETI['saiu73evalconocmotivo']), cadena_tildes($ETI['saiu73evalutilidad']), cadena_tildes($ETI['saiu73evalutilmotivo'])
		);
		$iTitulos = count($aTitulos);		
		for ($k = 0; $k <= 16; $k++) {
			$objHoja->setCellValueByColumnAndRow($k, $iFilaHoja2, $aTitulos[$k]);
			$sColumna = columna_Letra($k);
			$objPHPExcel->getActiveSheet()->getColumnDimension($sColumna)->setWidth(13);
			PHPExcel_Justificar_Celda_HorizontalCentro($objPHPExcel, $sColumna . $iFilaHoja2);
		}
		PHPExcel_Formato_Fuente_Celda($objPHPExcel, 'A' . $iFilaHoja2 . ':' . $sColTope . $iFilaHoja2, '10', 'Yu Gothic', 'Ne', true, false, false);
		$iFilaHoja2++;

		$objPHPExcel->setActiveSheetIndex(0);
		$objHoja = $objPHPExcel->getActiveSheet();
		PHPExcel_Formato_Fuente_Celda($objPHPExcel, 'A' . $iFila . ':' . $sColTope . $iFila, '10', 'Yu Gothic', 'Ne', true, false, false);
		$iFila++;
		$sCondi = '';
		$aTablas = array();
		$iTablas = 0;
		$iNumSolicitudes = 0;
		if ($iEstado !== '') {
			$sCondi = $sCondi . ' AND TB.saiu73estado=' . $iEstado . '';
		}
		switch ($iListar) {
			case 1:
				$sCondi = $sCondi . ' AND TB.saiu73idresponsable=' . $idTercero . '';
				break;
			case 2:
				$sCondi = $sCondi . ' AND TB.saiu73idresponsablecaso=' . $idTercero . '';
				break;
			case 3:
				$aEquipos = array();
				$sEquipos = '';
				$sSQL = 'SELECT bita27id FROM bita27equipotrabajo WHERE bita27activo=1 AND bita27idlider=' . $idTercero . '';
				$tabla = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($tabla) > 0) {
					while ($fila = $objDB->sf($tabla)) {
						$aEquipos[] = $fila['bita27id'];
					}
				} else {
					$sSQL = 'SELECT bita28idequipotrab FROM bita28eqipoparte WHERE bita28activo="S" AND bita28idtercero=' . $idTercero . '';
					$tabla = $objDB->ejecutasql($sSQL);
					if ($objDB->nf($tabla) > 0) {
						while ($fila = $objDB->sf($tabla)) {
							$aEquipos[] = $fila['bita28idequipotrab'];
						}
					}
				}
				$sEquipos = implode(',', $aEquipos);
				if ($sEquipos != '') {
					$sCondi = $sCondi . ' AND TB.saiu73idequipocaso IN (' . $sEquipos . ')';
				} else {
					$sCondi = $sCondi . ' AND TB.saiu73idresponsablecaso=' . $idTercero . '';
				}
				break;
		}
		$sSQL = '';
		$sSQL = $sSQL . 'SELECT TB.saiu73agno, TB.saiu73mes, TB.saiu73dia, TB.saiu73id, TB.saiu73estado, 
		TB.saiu73hora, TB.saiu73minuto, T11.unad11tipodoc, T11.unad11doc, T11.unad11razonsocial AS nominteresado, 
		TB.saiu73idzona, TB.saiu73idcentro, TB.saiu73idescuela, TB.saiu73idprograma, TB.saiu73solucion, 
		TB.saiu73tiposolicitud, TB.saiu73temasolicitud, TB.saiu73idunidadcaso, TB.saiu73idequipocaso, TB.saiu73idsupervisorcaso, 
		TB.saiu73idresponsablecaso, TB.saiu73evalfecha, TB.saiu73evalamabilidad, TB.saiu73evalamabmotivo, TB.saiu73evalrapidez,
		TB.saiu73evalrapidmotivo, TB.saiu73evalclaridad, TB.saiu73evalcalridmotivo, TB.saiu73evalresolvio, TB.saiu73evalsugerencias,
		TB.saiu73evalconocimiento, TB.saiu73evalconocmotivo, TB.saiu73evalutilidad, TB.saiu73evalutilmotivo,
		TB.saiu73fechafin, TB.saiu73horafin, TB.saiu73minutofin
		FROM saiu73solusuario_' . $iAgno . ' AS TB, unad11terceros AS T11
		WHERE TB.saiu73idsolicitante=T11.unad11id ' . $sCondi . '';
		if ($sSQL != '') {
			$sSQL = $sSQL . ' ORDER BY saiu73mes DESC, saiu73dia DESC, saiu73consec DESC';
			$tabla = $objDB->ejecutasql($sSQL);
			if ($tabla == false) {
				if ($bDebug) {
					$objHoja->setCellValueByColumnAndRow(1, $iFila, $sSQL);
					$iFila++;
				}
			} else {
				$asaiu73estado = array();
				$asaiu73tiposolicitud = array();
				$asaiu73temasolicitud = array();
				$asaiu73idunidadcaso = array();
				$asaiu73idequipocaso = array();
				$asaiu73idsupervisorcaso = array();
				$asaiu73idresponsablecaso = array();
				$asaiu73idzona = array();
				$asaiu73idcentro = array();
				$asaiu73idescuela = array();
				$asaiu73idprograma = array();
				$acalificacion = array('','Deficiente','Malo','Aceptable','Bueno','Excelente');
				while ($fila = $objDB->sf($tabla)) {
					$saiu73dia = fecha_armar($fila['saiu73dia'], $fila['saiu73mes'], $fila['saiu73agno']);
					$saiu73hora = html_TablaHoraMin($fila['saiu73hora'], $fila['saiu73minuto']);
					$i_saiu73estado = $fila['saiu73estado'];
					$saiu73fechafin = fecha_desdenumero($fila['saiu73fechafin']);
					$saiu73horafin = html_TablaHoraMin($fila['saiu73horafin'], $fila['saiu73minutofin']);
					$saiu73evalfecha = fecha_desdenumero($fila['saiu73evalfecha']);
					if (isset($asaiu73estado[$i_saiu73estado]) == 0) {
						$sSQL = 'SELECT saiu11nombre FROM saiu11estadosol WHERE saiu11id=' . $i_saiu73estado . '';
						$tablae = $objDB->ejecutasql($sSQL);
						if ($objDB->nf($tablae) > 0) {
							$filae = $objDB->sf($tablae);
							$asaiu73estado[$i_saiu73estado] = cadena_LimpiarTildes($filae['saiu11nombre']);
						} else {
							$asaiu73estado[$i_saiu73estado] = '';
						}
					}
					$saiu73estado = cadena_tildes($asaiu73estado[$i_saiu73estado]);
					$i_saiu73solucion = $fila['saiu73solucion'];
					$saiu73solucion = cadena_tildes($asaiu73solucion[$i_saiu73solucion]);
					$i_saiu73tiposolicitud = $fila['saiu73tiposolicitud'];
					if (isset($asaiu73tiposolicitud[$i_saiu73tiposolicitud]) == 0) {
						$sSQL = 'SELECT saiu02titulo FROM saiu02tiposol WHERE saiu02id=' . $i_saiu73tiposolicitud . '';
						$tablae = $objDB->ejecutasql($sSQL);
						if ($objDB->nf($tablae) > 0) {
							$filae = $objDB->sf($tablae);
							$asaiu73tiposolicitud[$i_saiu73tiposolicitud] = cadena_LimpiarTildes($filae['saiu02titulo']);
						} else {
							$asaiu73tiposolicitud[$i_saiu73tiposolicitud] = '';
						}
					}
					$saiu73tiposolicitud = cadena_tildes($asaiu73tiposolicitud[$i_saiu73tiposolicitud]);
					$i_saiu73temasolicitud = $fila['saiu73temasolicitud'];
					if (isset($asaiu73temasolicitud[$i_saiu73temasolicitud]) == 0) {
						$sSQL = 'SELECT saiu03titulo FROM saiu03temasol WHERE saiu03id=' . $i_saiu73temasolicitud . '';
						$tablae = $objDB->ejecutasql($sSQL);
						if ($objDB->nf($tablae) > 0) {
							$filae = $objDB->sf($tablae);
							$asaiu73temasolicitud[$i_saiu73temasolicitud] = cadena_LimpiarTildes($filae['saiu03titulo']);
						} else {
							$asaiu73temasolicitud[$i_saiu73temasolicitud] = '';
						}
					}
					$saiu73temasolicitud = ($asaiu73temasolicitud[$i_saiu73temasolicitud]);
					$i_saiu73idunidadcaso = $fila['saiu73idunidadcaso'];
					if (isset($asaiu73idunidadcaso[$i_saiu73idunidadcaso]) == 0) {
						$sSQL = 'SELECT unae26nombre FROM unae26unidadesfun WHERE unae26id=' . $i_saiu73idunidadcaso . '';
						$tablae = $objDB->ejecutasql($sSQL);
						if ($objDB->nf($tablae) > 0) {
							$filae = $objDB->sf($tablae);
							$asaiu73idunidadcaso[$i_saiu73idunidadcaso] = cadena_LimpiarTildes($filae['unae26nombre']);
						} else {
							$asaiu73idunidadcaso[$i_saiu73idunidadcaso] = '{Ninguna}';
						}
					}
					$saiu73idunidadcaso = ($asaiu73idunidadcaso[$i_saiu73idunidadcaso]);
					$i_saiu73idequipocaso = $fila['saiu73idequipocaso'];
					if (isset($asaiu73idequipocaso[$i_saiu73idequipocaso]) == 0) {
						$sSQL = 'SELECT bita27nombre FROM bita27equipotrabajo WHERE bita27id=' . $i_saiu73idequipocaso . '';
						$tablae = $objDB->ejecutasql($sSQL);
						if ($objDB->nf($tablae) > 0) {
							$filae = $objDB->sf($tablae);
							$asaiu73idequipocaso[$i_saiu73idequipocaso] = cadena_LimpiarTildes($filae['bita27nombre']);
						} else {
							$asaiu73idequipocaso[$i_saiu73idequipocaso] = '{Ninguno}';
						}
					}
					$saiu73idequipocaso = ($asaiu73idequipocaso[$i_saiu73idequipocaso]);
					$i_saiu73idsupervisorcaso = $fila['saiu73idsupervisorcaso'];
					if (isset($asaiu73idsupervisorcaso[$i_saiu73idsupervisorcaso]) == 0) {
						$sSQL = 'SELECT unad11razonsocial FROM unad11terceros WHERE unad11id=' . $i_saiu73idsupervisorcaso . '';
						$tablae = $objDB->ejecutasql($sSQL);
						if ($objDB->nf($tablae) > 0) {
							$filae = $objDB->sf($tablae);
							$asaiu73idsupervisorcaso[$i_saiu73idsupervisorcaso] = cadena_LimpiarTildes($filae['unad11razonsocial']);
						} else {
							$asaiu73idsupervisorcaso[$i_saiu73idsupervisorcaso] = '{Ninguno}';
						}
					}
					$saiu73idsupervisorcaso = ($asaiu73idsupervisorcaso[$i_saiu73idsupervisorcaso]);
					$i_saiu73idresponsablecaso = $fila['saiu73idresponsablecaso'];
					if (isset($asaiu73idresponsablecaso[$i_saiu73idresponsablecaso]) == 0) {
						$sSQL = 'SELECT unad11razonsocial FROM unad11terceros WHERE unad11id=' . $i_saiu73idresponsablecaso . '';
						$tablae = $objDB->ejecutasql($sSQL);
						if ($objDB->nf($tablae) > 0) {
							$filae = $objDB->sf($tablae);
							$asaiu73idresponsablecaso[$i_saiu73idresponsablecaso] = cadena_LimpiarTildes($filae['unad11razonsocial']);
							//$asaiu73idresponsablecaso[$i_saiu73idresponsablecaso] = cadena_decodificar($filae['unad11razonsocial']);
						} else {
							$asaiu73idresponsablecaso[$i_saiu73idresponsablecaso] = '{Ninguno}';
						}
					}
					$saiu73idresponsablecaso = ($asaiu73idresponsablecaso[$i_saiu73idresponsablecaso]);
					$sDoc = mb_convert_encoding($fila['unad11tipodoc'] . $fila['unad11doc'], 'ISO-8859-1', 'UTF-8');
					$sRazonSocial = mb_convert_encoding($fila['nominteresado'], 'ISO-8859-1', 'UTF-8');
					$i_saiu73idzona = $fila['saiu73idzona'];
					if (isset($asaiu73idzona[$i_saiu73idzona]) == 0) {
						$sSQL = 'SELECT unad23nombre FROM unad23zona WHERE unad23id=' . $i_saiu73idzona . '';
						$tablae = $objDB->ejecutasql($sSQL);
						if ($objDB->nf($tablae) > 0) {
							$filae = $objDB->sf($tablae);
							$asaiu73idzona[$i_saiu73idzona] = $filae['unad23nombre'];
						} else {
							$asaiu73idzona[$i_saiu73idzona] = '{Ninguna}';
						}
					}
					$saiu73idzona = ($asaiu73idzona[$i_saiu73idzona]);
					$i_saiu73idcentro = $fila['saiu73idcentro'];
					if (isset($asaiu73idcentro[$i_saiu73idcentro]) == 0) {
						$sSQL = 'SELECT unad24nombre FROM unad24sede WHERE unad24id=' . $i_saiu73idcentro . '';
						$tablae = $objDB->ejecutasql($sSQL);
						if ($objDB->nf($tablae) > 0) {
							$filae = $objDB->sf($tablae);
							$asaiu73idcentro[$i_saiu73idcentro] = $filae['unad24nombre'];
						} else {
							$asaiu73idcentro[$i_saiu73idcentro] = '{Ninguna}';
						}
					}
					$saiu73idcentro = ($asaiu73idcentro[$i_saiu73idcentro]);
					$i_saiu73idescuela = $fila['saiu73idescuela'];
					if (isset($asaiu73idescuela[$i_saiu73idescuela]) == 0) {
						$sSQL = 'SELECT core12nombre FROM core12escuela WHERE core12id=' . $i_saiu73idescuela . '';
						$tablae = $objDB->ejecutasql($sSQL);
						if ($objDB->nf($tablae) > 0) {
							$filae = $objDB->sf($tablae);
							$asaiu73idescuela[$i_saiu73idescuela] = $filae['core12nombre'];
						} else {
							$asaiu73idescuela[$i_saiu73idescuela] = '{Ninguna}';
						}
					}
					$saiu73idescuela = ($asaiu73idescuela[$i_saiu73idescuela]);
					$i_saiu73idprograma = $fila['saiu73idprograma'];
					if (isset($asaiu73idprograma[$i_saiu73idprograma]) == 0) {
						$sSQL = 'SELECT core09nombre FROM core09programa WHERE core09id=' . $i_saiu73idprograma . '';
						$tablae = $objDB->ejecutasql($sSQL);
						if ($objDB->nf($tablae) > 0) {
							$filae = $objDB->sf($tablae);
							$asaiu73idprograma[$i_saiu73idprograma] = $filae['core09nombre'];
						} else {
							$asaiu73idprograma[$i_saiu73idprograma] = '{Ninguno}';
						}
					}
					$saiu73idprograma = ($asaiu73idprograma[$i_saiu73idprograma]);
					
					$objHoja->setCellValueByColumnAndRow(0, $iFila, $fila['saiu73id']);
					$objHoja->setCellValueByColumnAndRow(1, $iFila, $saiu73dia);
					$objHoja->setCellValueByColumnAndRow(2, $iFila, $saiu73hora);
					$objHoja->setCellValueByColumnAndRow(3, $iFila, cadena_decodificar($saiu73estado));
					$objHoja->setCellValueByColumnAndRow(4, $iFila, $saiu73fechafin);
					$objHoja->setCellValueByColumnAndRow(5, $iFila, $saiu73horafin);
					$objHoja->setCellValueByColumnAndRow(6, $iFila, cadena_decodificar($saiu73solucion));
					$objHoja->setCellValueByColumnAndRow(7, $iFila, cadena_decodificar($saiu73tiposolicitud));
					$objHoja->setCellValueByColumnAndRow(8, $iFila, cadena_decodificar($saiu73temasolicitud));
					$objHoja->setCellValueByColumnAndRow(9, $iFila, cadena_decodificar($saiu73idunidadcaso));
					$objHoja->setCellValueByColumnAndRow(10, $iFila, cadena_decodificar($saiu73idequipocaso));
					$objHoja->setCellValueByColumnAndRow(11, $iFila, $saiu73idsupervisorcaso);
					$objHoja->setCellValueByColumnAndRow(12, $iFila, $saiu73idresponsablecaso);
					$objHoja->setCellValueByColumnAndRow(13, $iFila, $sDoc);
					$objHoja->setCellValueByColumnAndRow(14, $iFila, $sRazonSocial);
					$objHoja->setCellValueByColumnAndRow(15, $iFila, $saiu73idzona);
					$objHoja->setCellValueByColumnAndRow(16, $iFila, $saiu73idcentro);
					$objHoja->setCellValueByColumnAndRow(17, $iFila, $saiu73idescuela);
					$objHoja->setCellValueByColumnAndRow(18, $iFila, $saiu73idprograma);
					$iFila++;
					if ($i_saiu73estado == 7) {
						$objPHPExcel->setActiveSheetIndex(1);
						$objHoja = $objPHPExcel->getActiveSheet();
						$objHoja->setCellValueByColumnAndRow(0, $iFilaHoja2, $fila['saiu73id']);
						$objHoja->setCellValueByColumnAndRow(1, $iFilaHoja2, $sDoc);
						$objHoja->setCellValueByColumnAndRow(2, $iFilaHoja2, $sRazonSocial);
						$objHoja->setCellValueByColumnAndRow(3, $iFilaHoja2, cadena_decodificar($saiu73temasolicitud));
						$objHoja->setCellValueByColumnAndRow(4, $iFilaHoja2, $saiu73evalfecha);
						$objHoja->setCellValueByColumnAndRow(5, $iFilaHoja2, $acalificacion[$fila['saiu73evalamabilidad']]);
						$objHoja->setCellValueByColumnAndRow(6, $iFilaHoja2, cadena_decodificar($fila['saiu73evalamabmotivo']));
						$objHoja->setCellValueByColumnAndRow(7, $iFilaHoja2, $acalificacion[$fila['saiu73evalrapidez']]);
						$objHoja->setCellValueByColumnAndRow(8, $iFilaHoja2, cadena_decodificar($fila['saiu73evalrapidmotivo']));
						$objHoja->setCellValueByColumnAndRow(9, $iFilaHoja2, $acalificacion[$fila['saiu73evalclaridad']]);
						$objHoja->setCellValueByColumnAndRow(10, $iFilaHoja2, cadena_decodificar($fila['saiu73evalcalridmotivo']));
						$objHoja->setCellValueByColumnAndRow(11, $iFilaHoja2, $acalificacion[$fila['saiu73evalresolvio']]);
						$objHoja->setCellValueByColumnAndRow(12, $iFilaHoja2, cadena_decodificar($fila['saiu73evalsugerencias']));
						$objHoja->setCellValueByColumnAndRow(13, $iFilaHoja2, $acalificacion[$fila['saiu73evalconocimiento']]);
						$objHoja->setCellValueByColumnAndRow(14, $iFilaHoja2, cadena_decodificar($fila['saiu73evalconocmotivo']));
						$objHoja->setCellValueByColumnAndRow(15, $iFilaHoja2, $acalificacion[$fila['saiu73evalutilidad']]);
						$objHoja->setCellValueByColumnAndRow(16, $iFilaHoja2, cadena_decodificar($fila['saiu73evalutilmotivo']));
						$iFilaHoja2++;
						$objPHPExcel->setActiveSheetIndex(0);
						$objHoja = $objPHPExcel->getActiveSheet();
					}
				}
			}
		}
		$objDB->CerrarConexion();
		PHPExcel_RellenarCeldas($objPHPExcel, 'A' . $iFilaBase . ':' . $sColTope . $iFila, 'Bl', true);
		if ($_REQUEST['clave'] != '') {
			/* Bloquear la hoja. */
			$objHoja->getProtection()->setPassword($_REQUEST['clave']);
			$objHoja->getProtection()->setSheet(true);
			$objHoja->getProtection()->setSort(true);
		}
		$objPHPExcel->setActiveSheetIndex(1);
		$objHoja = $objPHPExcel->getActiveSheet();
		PHPExcel_RellenarCeldas($objPHPExcel, 'A' . $iFilaBase2 . ':' . $sColTope . $iFilaHoja2, 'Bl', true);
		if ($_REQUEST['clave'] != '') {
			/* Bloquear la hoja. */
			$objHoja->getProtection()->setPassword($_REQUEST['clave']);
			$objHoja->getProtection()->setSheet(true);
			$objHoja->getProtection()->setSort(true);
		}
		$objPHPExcel->setActiveSheetIndex(0);
		/* descargar el resultado */
		header('Expires: Thu, 27 Mar 1980 23:59:00 GMT'); /* la pagina expira en una fecha pasada */
		header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT'); /* ultima actualizacion ahora cuando la cargamos */
		header('Cache-Control: no-cache, must-revalidate'); /* no guardar en CACHE */
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="' . $sTituloRpt . '.xlsx"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->setPreCalculateFormulas(false);
		$objWriter->save('php://output');
		die();
	} else {
		echo $sError;
	}
} else {
	echo $sError;
}
