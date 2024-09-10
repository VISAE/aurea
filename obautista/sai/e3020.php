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
$mensajes_3020 = 'lg/lg_3020_' . $_SESSION['unad_idioma'] . '.php';
if (!file_exists($mensajes_3020)) {
	$mensajes_3020 = 'lg/lg_3020_es.php';
}
require $mensajes_3020;
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
	$sTituloRpt = $sTituloRpt . ' Atencion Correos ' . $iAgno;
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
		$objPHPExcel->getProperties()->setDescription('Reporte de Atencion Correos ' . $sServerRpt . ' creado en ' . fecha_hoy() . ' ' . formato_horaminuto(fecha_hora(), fecha_minuto()));
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
		$sTituloHoja = cadena_LimpiarTildes($ETI['titulo']);
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
			cadena_tildes($ETI['saiu20consec']), cadena_tildes($ETI['msg_fecha']), cadena_tildes($ETI['saiu20hora']), cadena_tildes($ETI['saiu20estado']),cadena_tildes($ETI['saiu20fecharespuesta']), cadena_tildes($ETI['saiu20horarespuesta']), cadena_tildes($ETI['saiu20solucion']),
			cadena_tildes($ETI['saiu20tiposolicitud']), cadena_tildes($ETI['saiu20temasolicitud']), cadena_tildes($ETI['saiu20idunidadcaso']), cadena_tildes($ETI['saiu20idequipocaso']), cadena_tildes($ETI['saiu20idsupervisorcaso']),
			cadena_tildes($ETI['saiu20idresponsablecaso']), cadena_tildes($ETI['saiu20idsolicitante']), cadena_tildes($ETI['saiu20razonsocial']), cadena_tildes($ETI['saiu20idzona']),
			cadena_tildes($ETI['saiu20idcentro']), cadena_tildes($ETI['saiu20idescuela']), cadena_tildes($ETI['saiu20idprograma'])
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
			cadena_tildes($ETI['saiu20consec']), cadena_tildes($ETI['saiu20idsolicitante']), cadena_tildes($ETI['saiu20razonsocial']), cadena_tildes($ETI['saiu20temasolicitud']), cadena_tildes($ETI['saiu20evalfecha']),
			cadena_tildes($ETI['saiu20evalamabilidad']), cadena_tildes($ETI['saiu20evalamabmotivo']), cadena_tildes($ETI['saiu20evalrapidez']), cadena_tildes($ETI['saiu20evalrapidmotivo']), cadena_tildes($ETI['saiu20evalclaridad']),
			cadena_tildes($ETI['saiu20evalcalridmotivo']), cadena_tildes($ETI['saiu20evalresolvio']), cadena_tildes($ETI['saiu20evalsugerencias']), cadena_tildes($ETI['saiu20evalconocimiento']),
			cadena_tildes($ETI['saiu20evalconocmotivo']), cadena_tildes($ETI['saiu20evalutilidad']), cadena_tildes($ETI['saiu20evalutilmotivo'])
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
			$sCondi = $sCondi . ' AND TB.saiu20estado=' . $iEstado . '';
		}
		switch ($iListar) {
			case 1:
				$sCondi = $sCondi . ' AND TB.saiu20idresponsable=' . $idTercero . '';
				break;
			case 2:
				$sCondi = $sCondi . ' AND TB.saiu20idresponsablecaso=' . $idTercero . '';
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
					$sCondi = $sCondi . ' AND TB.saiu20idequipocaso IN (' . $sEquipos . ')';
				} else {
					$sCondi = $sCondi . ' AND TB.saiu20idresponsablecaso=' . $idTercero . '';
				}
				break;
		}
		$sSQL = '';
		$sSQL = $sSQL . 'SELECT TB.saiu20agno, TB.saiu20mes, TB.saiu20dia, TB.saiu20id, TB.saiu20estado, 
		TB.saiu20hora, TB.saiu20minuto, T11.unad11tipodoc, T11.unad11doc, T11.unad11razonsocial AS nominteresado, 
		TB.saiu20idzona, TB.saiu20idcentro, TB.saiu20idescuela, TB.saiu20idprograma, TB.saiu20solucion, 
		TB.saiu20tiposolicitud, TB.saiu20temasolicitud, TB.saiu20idunidadcaso, TB.saiu20idequipocaso, TB.saiu20idsupervisorcaso, 
		TB.saiu20idresponsablecaso, TB.saiu20evalfecha, TB.saiu20evalamabilidad, TB.saiu20evalamabmotivo, TB.saiu20evalrapidez,
		TB.saiu20evalrapidmotivo, TB.saiu20evalclaridad, TB.saiu20evalcalridmotivo, TB.saiu20evalresolvio, TB.saiu20evalsugerencias,
		TB.saiu20evalconocimiento, TB.saiu20evalconocmotivo, TB.saiu20evalutilidad, TB.saiu20evalutilmotivo,
		TB.saiu20fechafin, TB.saiu20horafin, TB.saiu20minutofin
		FROM saiu20correo_' . $iAgno . ' AS TB, unad11terceros AS T11
		WHERE TB.saiu20idsolicitante=T11.unad11id ' . $sCondi . '';
		if ($sSQL != '') {
			$sSQL = $sSQL . ' ORDER BY saiu20mes DESC, saiu20dia DESC, saiu20consec DESC';
			$tabla = $objDB->ejecutasql($sSQL);
			if ($tabla == false) {
				if ($bDebug) {
					$objHoja->setCellValueByColumnAndRow(1, $iFila, $sSQL);
					$iFila++;
				}
			} else {
				$asaiu20estado = array();
				$asaiu20tiposolicitud = array();
				$asaiu20temasolicitud = array();
				$asaiu20idunidadcaso = array();
				$asaiu20idequipocaso = array();
				$asaiu20idsupervisorcaso = array();
				$asaiu20idresponsablecaso = array();
				$asaiu20idzona = array();
				$asaiu20idcentro = array();
				$asaiu20idescuela = array();
				$asaiu20idprograma = array();
				$acalificacion = array('','Deficiente','Malo','Aceptable','Bueno','Excelente');
				while ($fila = $objDB->sf($tabla)) {
					$saiu20dia = fecha_armar($fila['saiu20dia'], $fila['saiu20mes'], $fila['saiu20agno']);
					$saiu20hora = html_TablaHoraMin($fila['saiu20hora'], $fila['saiu20minuto']);
					$i_saiu20estado = $fila['saiu20estado'];
					$saiu20fechafin = fecha_desdenumero($fila['saiu20fechafin']);
					$saiu20horafin = html_TablaHoraMin($fila['saiu20horafin'], $fila['saiu20minutofin']);
					$saiu20evalfecha = fecha_desdenumero($fila['saiu20evalfecha']);
					if (isset($asaiu20estado[$i_saiu20estado]) == 0) {
						$sSQL = 'SELECT saiu11nombre FROM saiu11estadosol WHERE saiu11id=' . $i_saiu20estado . '';
						$tablae = $objDB->ejecutasql($sSQL);
						if ($objDB->nf($tablae) > 0) {
							$filae = $objDB->sf($tablae);
							$asaiu20estado[$i_saiu20estado] = cadena_LimpiarTildes($filae['saiu11nombre']);
						} else {
							$asaiu20estado[$i_saiu20estado] = '';
						}
					}
					$saiu20estado = cadena_tildes($asaiu20estado[$i_saiu20estado]);
					$i_saiu20solucion = $fila['saiu20solucion'];
					$saiu20solucion = cadena_tildes($asaiu20solucion[$i_saiu20solucion]);
					$i_saiu20tiposolicitud = $fila['saiu20tiposolicitud'];
					if (isset($asaiu20tiposolicitud[$i_saiu20tiposolicitud]) == 0) {
						$sSQL = 'SELECT saiu02titulo FROM saiu02tiposol WHERE saiu02id=' . $i_saiu20tiposolicitud . '';
						$tablae = $objDB->ejecutasql($sSQL);
						if ($objDB->nf($tablae) > 0) {
							$filae = $objDB->sf($tablae);
							$asaiu20tiposolicitud[$i_saiu20tiposolicitud] = cadena_LimpiarTildes($filae['saiu02titulo']);
						} else {
							$asaiu20tiposolicitud[$i_saiu20tiposolicitud] = '';
						}
					}
					$saiu20tiposolicitud = cadena_tildes($asaiu20tiposolicitud[$i_saiu20tiposolicitud]);
					$i_saiu20temasolicitud = $fila['saiu20temasolicitud'];
					if (isset($asaiu20temasolicitud[$i_saiu20temasolicitud]) == 0) {
						$sSQL = 'SELECT saiu03titulo FROM saiu03temasol WHERE saiu03id=' . $i_saiu20temasolicitud . '';
						$tablae = $objDB->ejecutasql($sSQL);
						if ($objDB->nf($tablae) > 0) {
							$filae = $objDB->sf($tablae);
							$asaiu20temasolicitud[$i_saiu20temasolicitud] = cadena_LimpiarTildes($filae['saiu03titulo']);
						} else {
							$asaiu20temasolicitud[$i_saiu20temasolicitud] = '';
						}
					}
					$saiu20temasolicitud = ($asaiu20temasolicitud[$i_saiu20temasolicitud]);
					$i_saiu20idunidadcaso = $fila['saiu20idunidadcaso'];
					if (isset($asaiu20idunidadcaso[$i_saiu20idunidadcaso]) == 0) {
						$sSQL = 'SELECT unae26nombre FROM unae26unidadesfun WHERE unae26id=' . $i_saiu20idunidadcaso . '';
						$tablae = $objDB->ejecutasql($sSQL);
						if ($objDB->nf($tablae) > 0) {
							$filae = $objDB->sf($tablae);
							$asaiu20idunidadcaso[$i_saiu20idunidadcaso] = cadena_LimpiarTildes($filae['unae26nombre']);
						} else {
							$asaiu20idunidadcaso[$i_saiu20idunidadcaso] = '{Ninguna}';
						}
					}
					$saiu20idunidadcaso = ($asaiu20idunidadcaso[$i_saiu20idunidadcaso]);
					$i_saiu20idequipocaso = $fila['saiu20idequipocaso'];
					if (isset($asaiu20idequipocaso[$i_saiu20idequipocaso]) == 0) {
						$sSQL = 'SELECT bita27nombre FROM bita27equipotrabajo WHERE bita27id=' . $i_saiu20idequipocaso . '';
						$tablae = $objDB->ejecutasql($sSQL);
						if ($objDB->nf($tablae) > 0) {
							$filae = $objDB->sf($tablae);
							$asaiu20idequipocaso[$i_saiu20idequipocaso] = cadena_LimpiarTildes($filae['bita27nombre']);
						} else {
							$asaiu20idequipocaso[$i_saiu20idequipocaso] = '{Ninguno}';
						}
					}
					$saiu20idequipocaso = ($asaiu20idequipocaso[$i_saiu20idequipocaso]);
					$i_saiu20idsupervisorcaso = $fila['saiu20idsupervisorcaso'];
					if (isset($asaiu20idsupervisorcaso[$i_saiu20idsupervisorcaso]) == 0) {
						$sSQL = 'SELECT unad11razonsocial FROM unad11terceros WHERE unad11id=' . $i_saiu20idsupervisorcaso . '';
						$tablae = $objDB->ejecutasql($sSQL);
						if ($objDB->nf($tablae) > 0) {
							$filae = $objDB->sf($tablae);
							$asaiu20idsupervisorcaso[$i_saiu20idsupervisorcaso] = cadena_LimpiarTildes($filae['unad11razonsocial']);
						} else {
							$asaiu20idsupervisorcaso[$i_saiu20idsupervisorcaso] = '{Ninguno}';
						}
					}
					$saiu20idsupervisorcaso = ($asaiu20idsupervisorcaso[$i_saiu20idsupervisorcaso]);
					$i_saiu20idresponsablecaso = $fila['saiu20idresponsablecaso'];
					if (isset($asaiu20idresponsablecaso[$i_saiu20idresponsablecaso]) == 0) {
						$sSQL = 'SELECT unad11razonsocial FROM unad11terceros WHERE unad11id=' . $i_saiu20idresponsablecaso . '';
						$tablae = $objDB->ejecutasql($sSQL);
						if ($objDB->nf($tablae) > 0) {
							$filae = $objDB->sf($tablae);
							$asaiu20idresponsablecaso[$i_saiu20idresponsablecaso] = cadena_LimpiarTildes($filae['unad11razonsocial']);
							//$asaiu20idresponsablecaso[$i_saiu20idresponsablecaso] = cadena_decodificar($filae['unad11razonsocial']);
						} else {
							$asaiu20idresponsablecaso[$i_saiu20idresponsablecaso] = '{Ninguno}';
						}
					}
					$saiu20idresponsablecaso = ($asaiu20idresponsablecaso[$i_saiu20idresponsablecaso]);
					$sDoc = mb_convert_encoding($fila['unad11tipodoc'] . $fila['unad11doc'], 'ISO-8859-1', 'UTF-8');
					$sRazonSocial = mb_convert_encoding($fila['nominteresado'], 'ISO-8859-1', 'UTF-8');
					$i_saiu20idzona = $fila['saiu20idzona'];
					if (isset($asaiu20idzona[$i_saiu20idzona]) == 0) {
						$sSQL = 'SELECT unad23nombre FROM unad23zona WHERE unad23id=' . $i_saiu20idzona . '';
						$tablae = $objDB->ejecutasql($sSQL);
						if ($objDB->nf($tablae) > 0) {
							$filae = $objDB->sf($tablae);
							$asaiu20idzona[$i_saiu20idzona] = $filae['unad23nombre'];
						} else {
							$asaiu20idzona[$i_saiu20idzona] = '{Ninguna}';
						}
					}
					$saiu20idzona = ($asaiu20idzona[$i_saiu20idzona]);
					$i_saiu20idcentro = $fila['saiu20idcentro'];
					if (isset($asaiu20idcentro[$i_saiu20idcentro]) == 0) {
						$sSQL = 'SELECT unad24nombre FROM unad24sede WHERE unad24id=' . $i_saiu20idcentro . '';
						$tablae = $objDB->ejecutasql($sSQL);
						if ($objDB->nf($tablae) > 0) {
							$filae = $objDB->sf($tablae);
							$asaiu20idcentro[$i_saiu20idcentro] = $filae['unad24nombre'];
						} else {
							$asaiu20idcentro[$i_saiu20idcentro] = '{Ninguna}';
						}
					}
					$saiu20idcentro = ($asaiu20idcentro[$i_saiu20idcentro]);
					$i_saiu20idescuela = $fila['saiu20idescuela'];
					if (isset($asaiu20idescuela[$i_saiu20idescuela]) == 0) {
						$sSQL = 'SELECT core12nombre FROM core12escuela WHERE core12id=' . $i_saiu20idescuela . '';
						$tablae = $objDB->ejecutasql($sSQL);
						if ($objDB->nf($tablae) > 0) {
							$filae = $objDB->sf($tablae);
							$asaiu20idescuela[$i_saiu20idescuela] = $filae['core12nombre'];
						} else {
							$asaiu20idescuela[$i_saiu20idescuela] = '{Ninguna}';
						}
					}
					$saiu20idescuela = ($asaiu20idescuela[$i_saiu20idescuela]);
					$i_saiu20idprograma = $fila['saiu20idprograma'];
					if (isset($asaiu20idprograma[$i_saiu20idprograma]) == 0) {
						$sSQL = 'SELECT core09nombre FROM core09programa WHERE core09id=' . $i_saiu20idprograma . '';
						$tablae = $objDB->ejecutasql($sSQL);
						if ($objDB->nf($tablae) > 0) {
							$filae = $objDB->sf($tablae);
							$asaiu20idprograma[$i_saiu20idprograma] = $filae['core09nombre'];
						} else {
							$asaiu20idprograma[$i_saiu20idprograma] = '{Ninguno}';
						}
					}
					$saiu20idprograma = ($asaiu20idprograma[$i_saiu20idprograma]);
					
					$objHoja->setCellValueByColumnAndRow(0, $iFila, $fila['saiu20id']);
					$objHoja->setCellValueByColumnAndRow(1, $iFila, $saiu20dia);
					$objHoja->setCellValueByColumnAndRow(2, $iFila, $saiu20hora);
					$objHoja->setCellValueByColumnAndRow(3, $iFila, cadena_decodificar($saiu20estado));
					$objHoja->setCellValueByColumnAndRow(4, $iFila, $saiu20fechafin);
					$objHoja->setCellValueByColumnAndRow(5, $iFila, $saiu20horafin);
					$objHoja->setCellValueByColumnAndRow(6, $iFila, cadena_decodificar($saiu20solucion));
					$objHoja->setCellValueByColumnAndRow(7, $iFila, cadena_decodificar($saiu20tiposolicitud));
					$objHoja->setCellValueByColumnAndRow(8, $iFila, cadena_decodificar($saiu20temasolicitud));
					$objHoja->setCellValueByColumnAndRow(9, $iFila, cadena_decodificar($saiu20idunidadcaso));
					$objHoja->setCellValueByColumnAndRow(10, $iFila, cadena_decodificar($saiu20idequipocaso));
					$objHoja->setCellValueByColumnAndRow(11, $iFila, $saiu20idsupervisorcaso);
					$objHoja->setCellValueByColumnAndRow(12, $iFila, $saiu20idresponsablecaso);
					$objHoja->setCellValueByColumnAndRow(13, $iFila, $sDoc);
					$objHoja->setCellValueByColumnAndRow(14, $iFila, $sRazonSocial);
					$objHoja->setCellValueByColumnAndRow(15, $iFila, $saiu20idzona);
					$objHoja->setCellValueByColumnAndRow(16, $iFila, $saiu20idcentro);
					$objHoja->setCellValueByColumnAndRow(17, $iFila, $saiu20idescuela);
					$objHoja->setCellValueByColumnAndRow(18, $iFila, $saiu20idprograma);
					$iFila++;
					if ($i_saiu20estado == 7) {
						$objPHPExcel->setActiveSheetIndex(1);
						$objHoja = $objPHPExcel->getActiveSheet();
						$objHoja->setCellValueByColumnAndRow(0, $iFilaHoja2, $fila['saiu20id']);
						$objHoja->setCellValueByColumnAndRow(1, $iFilaHoja2, $sDoc);
						$objHoja->setCellValueByColumnAndRow(2, $iFilaHoja2, $sRazonSocial);
						$objHoja->setCellValueByColumnAndRow(3, $iFilaHoja2, cadena_decodificar($saiu20temasolicitud));
						$objHoja->setCellValueByColumnAndRow(4, $iFilaHoja2, $saiu20evalfecha);
						$objHoja->setCellValueByColumnAndRow(5, $iFilaHoja2, $acalificacion[$fila['saiu20evalamabilidad']]);
						$objHoja->setCellValueByColumnAndRow(6, $iFilaHoja2, cadena_decodificar($fila['saiu20evalamabmotivo']));
						$objHoja->setCellValueByColumnAndRow(7, $iFilaHoja2, $acalificacion[$fila['saiu20evalrapidez']]);
						$objHoja->setCellValueByColumnAndRow(8, $iFilaHoja2, cadena_decodificar($fila['saiu20evalrapidmotivo']));
						$objHoja->setCellValueByColumnAndRow(9, $iFilaHoja2, $acalificacion[$fila['saiu20evalclaridad']]);
						$objHoja->setCellValueByColumnAndRow(10, $iFilaHoja2, cadena_decodificar($fila['saiu20evalcalridmotivo']));
						$objHoja->setCellValueByColumnAndRow(11, $iFilaHoja2, $acalificacion[$fila['saiu20evalresolvio']]);
						$objHoja->setCellValueByColumnAndRow(12, $iFilaHoja2, cadena_decodificar($fila['saiu20evalsugerencias']));
						$objHoja->setCellValueByColumnAndRow(13, $iFilaHoja2, $acalificacion[$fila['saiu20evalconocimiento']]);
						$objHoja->setCellValueByColumnAndRow(14, $iFilaHoja2, cadena_decodificar($fila['saiu20evalconocmotivo']));
						$objHoja->setCellValueByColumnAndRow(15, $iFilaHoja2, $acalificacion[$fila['saiu20evalutilidad']]);
						$objHoja->setCellValueByColumnAndRow(16, $iFilaHoja2, cadena_decodificar($fila['saiu20evalutilmotivo']));
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
