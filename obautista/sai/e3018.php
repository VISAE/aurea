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
$mensajes_3018 = 'lg/lg_3018_' . $_SESSION['unad_idioma'] . '.php';
if (!file_exists($mensajes_3018)) {
	$mensajes_3018 = 'lg/lg_3018_es.php';
}
require $mensajes_3018;
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
	$sTituloRpt = $sTituloRpt . ' Atencion Telefon ' . $iAgno;
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
		$objPHPExcel->getProperties()->setDescription('Reporte de Atencion Telefonica ' . $sServerRpt . ' creado en ' . fecha_hoy() . ' ' . formato_horaminuto(fecha_hora(), fecha_minuto()));
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
			cadena_tildes($ETI['saiu18consec']), cadena_tildes($ETI['msg_fecha']), cadena_tildes($ETI['saiu18hora']), cadena_tildes($ETI['saiu18estado']),cadena_tildes($ETI['saiu18fecharespuesta']), cadena_tildes($ETI['saiu18horarespuesta']), cadena_tildes($ETI['saiu18solucion']),
			cadena_tildes($ETI['saiu18tiposolicitud']), cadena_tildes($ETI['saiu18temasolicitud']), cadena_tildes($ETI['saiu18idunidadcaso']), cadena_tildes($ETI['saiu18idequipocaso']), cadena_tildes($ETI['saiu18idsupervisorcaso']),
			cadena_tildes($ETI['saiu18idresponsablecaso']), cadena_tildes($ETI['saiu18idsolicitante']), cadena_tildes($ETI['saiu18razonsocial']), cadena_tildes($ETI['saiu18idzona']),
			cadena_tildes($ETI['saiu18idcentro']), cadena_tildes($ETI['saiu18idescuela']), cadena_tildes($ETI['saiu18idprograma'])
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
			cadena_tildes($ETI['saiu18consec']), cadena_tildes($ETI['saiu18idsolicitante']), cadena_tildes($ETI['saiu18razonsocial']), cadena_tildes($ETI['saiu18temasolicitud']), cadena_tildes($ETI['saiu18evalfecha']),
			cadena_tildes($ETI['saiu18evalamabilidad']), cadena_tildes($ETI['saiu18evalamabmotivo']), cadena_tildes($ETI['saiu18evalrapidez']), cadena_tildes($ETI['saiu18evalrapidmotivo']), cadena_tildes($ETI['saiu18evalclaridad']),
			cadena_tildes($ETI['saiu18evalcalridmotivo']), cadena_tildes($ETI['saiu18evalresolvio']), cadena_tildes($ETI['saiu18evalsugerencias']), cadena_tildes($ETI['saiu18evalconocimiento']),
			cadena_tildes($ETI['saiu18evalconocmotivo']), cadena_tildes($ETI['saiu18evalutilidad']), cadena_tildes($ETI['saiu18evalutilmotivo'])
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
			$sCondi = $sCondi . ' AND TB.saiu18estado=' . $iEstado . '';
		}
		switch ($iListar) {
			case 1:
				$sCondi = $sCondi . ' AND TB.saiu18idresponsable=' . $idTercero . '';
				break;
			case 2:
				$sCondi = $sCondi . ' AND TB.saiu18idresponsablecaso=' . $idTercero . '';
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
					$sCondi = $sCondi . ' AND TB.saiu18idequipocaso IN (' . $sEquipos . ')';
				} else {
					$sCondi = $sCondi . ' AND TB.saiu18idresponsablecaso=' . $idTercero . '';
				}
				break;
		}
		$sSQL = '';
		$sSQL = $sSQL . 'SELECT TB.saiu18agno, TB.saiu18mes, TB.saiu18dia, TB.saiu18id, TB.saiu18estado, 
		TB.saiu18hora, TB.saiu18minuto, T11.unad11tipodoc, T11.unad11doc, T11.unad11razonsocial AS nominteresado, 
		TB.saiu18idzona, TB.saiu18idcentro, TB.saiu18idescuela, TB.saiu18idprograma, TB.saiu18solucion, 
		TB.saiu18tiposolicitud, TB.saiu18temasolicitud, TB.saiu18idunidadcaso, TB.saiu18idequipocaso, TB.saiu18idsupervisorcaso, 
		TB.saiu18idresponsablecaso, TB.saiu18evalfecha, TB.saiu18evalamabilidad, TB.saiu18evalamabmotivo, TB.saiu18evalrapidez,
		TB.saiu18evalrapidmotivo, TB.saiu18evalclaridad, TB.saiu18evalcalridmotivo, TB.saiu18evalresolvio, TB.saiu18evalsugerencias,
		TB.saiu18evalconocimiento, TB.saiu18evalconocmotivo, TB.saiu18evalutilidad, TB.saiu18evalutilmotivo,
		TB.saiu18fechafin, TB.saiu18horafin, TB.saiu18minutofin
		FROM saiu18telefonico_' . $iAgno . ' AS TB, unad11terceros AS T11
		WHERE TB.saiu18idsolicitante=T11.unad11id ' . $sCondi . '';
		if ($sSQL != '') {
			$sSQL = $sSQL . ' ORDER BY saiu18mes DESC, saiu18dia DESC, saiu18consec DESC';
			$tabla = $objDB->ejecutasql($sSQL);
			if ($tabla == false) {
				if ($bDebug) {
					$objHoja->setCellValueByColumnAndRow(1, $iFila, $sSQL);
					$iFila++;
				}
			} else {
				$asaiu18estado = array();
				$asaiu18tiposolicitud = array();
				$asaiu18temasolicitud = array();
				$asaiu18idunidadcaso = array();
				$asaiu18idequipocaso = array();
				$asaiu18idsupervisorcaso = array();
				$asaiu18idresponsablecaso = array();
				$asaiu18idzona = array();
				$asaiu18idcentro = array();
				$asaiu18idescuela = array();
				$asaiu18idprograma = array();
				$acalificacion = array('','Deficiente','Malo','Aceptable','Bueno','Excelente');
				while ($fila = $objDB->sf($tabla)) {
					$saiu18dia = fecha_armar($fila['saiu18dia'], $fila['saiu18mes'], $fila['saiu18agno']);
					$saiu18hora = html_TablaHoraMin($fila['saiu18hora'], $fila['saiu18minuto']);
					$i_saiu18estado = $fila['saiu18estado'];
					$saiu18fechafin = fecha_desdenumero($fila['saiu18fechafin']);
					$saiu18horafin = html_TablaHoraMin($fila['saiu18horafin'], $fila['saiu18minutofin']);
					$saiu18evalfecha = fecha_desdenumero($fila['saiu18evalfecha']);
					if (isset($asaiu18estado[$i_saiu18estado]) == 0) {
						$sSQL = 'SELECT saiu11nombre FROM saiu11estadosol WHERE saiu11id=' . $i_saiu18estado . '';
						$tablae = $objDB->ejecutasql($sSQL);
						if ($objDB->nf($tablae) > 0) {
							$filae = $objDB->sf($tablae);
							$asaiu18estado[$i_saiu18estado] = cadena_LimpiarTildes($filae['saiu11nombre']);
						} else {
							$asaiu18estado[$i_saiu18estado] = '';
						}
					}
					$saiu18estado = cadena_tildes($asaiu18estado[$i_saiu18estado]);
					$i_saiu18solucion = $fila['saiu18solucion'];
					$saiu18solucion = cadena_tildes($asaiu18solucion[$i_saiu18solucion]);
					$i_saiu18tiposolicitud = $fila['saiu18tiposolicitud'];
					if (isset($asaiu18tiposolicitud[$i_saiu18tiposolicitud]) == 0) {
						$sSQL = 'SELECT saiu02titulo FROM saiu02tiposol WHERE saiu02id=' . $i_saiu18tiposolicitud . '';
						$tablae = $objDB->ejecutasql($sSQL);
						if ($objDB->nf($tablae) > 0) {
							$filae = $objDB->sf($tablae);
							$asaiu18tiposolicitud[$i_saiu18tiposolicitud] = cadena_LimpiarTildes($filae['saiu02titulo']);
						} else {
							$asaiu18tiposolicitud[$i_saiu18tiposolicitud] = '';
						}
					}
					$saiu18tiposolicitud = cadena_tildes($asaiu18tiposolicitud[$i_saiu18tiposolicitud]);
					$i_saiu18temasolicitud = $fila['saiu18temasolicitud'];
					if (isset($asaiu18temasolicitud[$i_saiu18temasolicitud]) == 0) {
						$sSQL = 'SELECT saiu03titulo FROM saiu03temasol WHERE saiu03id=' . $i_saiu18temasolicitud . '';
						$tablae = $objDB->ejecutasql($sSQL);
						if ($objDB->nf($tablae) > 0) {
							$filae = $objDB->sf($tablae);
							$asaiu18temasolicitud[$i_saiu18temasolicitud] = cadena_LimpiarTildes($filae['saiu03titulo']);
						} else {
							$asaiu18temasolicitud[$i_saiu18temasolicitud] = '';
						}
					}
					$saiu18temasolicitud = ($asaiu18temasolicitud[$i_saiu18temasolicitud]);
					$i_saiu18idunidadcaso = $fila['saiu18idunidadcaso'];
					if (isset($asaiu18idunidadcaso[$i_saiu18idunidadcaso]) == 0) {
						$sSQL = 'SELECT unae26nombre FROM unae26unidadesfun WHERE unae26id=' . $i_saiu18idunidadcaso . '';
						$tablae = $objDB->ejecutasql($sSQL);
						if ($objDB->nf($tablae) > 0) {
							$filae = $objDB->sf($tablae);
							$asaiu18idunidadcaso[$i_saiu18idunidadcaso] = cadena_LimpiarTildes($filae['unae26nombre']);
						} else {
							$asaiu18idunidadcaso[$i_saiu18idunidadcaso] = '{Ninguna}';
						}
					}
					$saiu18idunidadcaso = ($asaiu18idunidadcaso[$i_saiu18idunidadcaso]);
					$i_saiu18idequipocaso = $fila['saiu18idequipocaso'];
					if (isset($asaiu18idequipocaso[$i_saiu18idequipocaso]) == 0) {
						$sSQL = 'SELECT bita27nombre FROM bita27equipotrabajo WHERE bita27id=' . $i_saiu18idequipocaso . '';
						$tablae = $objDB->ejecutasql($sSQL);
						if ($objDB->nf($tablae) > 0) {
							$filae = $objDB->sf($tablae);
							$asaiu18idequipocaso[$i_saiu18idequipocaso] = cadena_LimpiarTildes($filae['bita27nombre']);
						} else {
							$asaiu18idequipocaso[$i_saiu18idequipocaso] = '{Ninguno}';
						}
					}
					$saiu18idequipocaso = ($asaiu18idequipocaso[$i_saiu18idequipocaso]);
					$i_saiu18idsupervisorcaso = $fila['saiu18idsupervisorcaso'];
					if (isset($asaiu18idsupervisorcaso[$i_saiu18idsupervisorcaso]) == 0) {
						$sSQL = 'SELECT unad11razonsocial FROM unad11terceros WHERE unad11id=' . $i_saiu18idsupervisorcaso . '';
						$tablae = $objDB->ejecutasql($sSQL);
						if ($objDB->nf($tablae) > 0) {
							$filae = $objDB->sf($tablae);
							$asaiu18idsupervisorcaso[$i_saiu18idsupervisorcaso] = cadena_LimpiarTildes($filae['unad11razonsocial']);
						} else {
							$asaiu18idsupervisorcaso[$i_saiu18idsupervisorcaso] = '{Ninguno}';
						}
					}
					$saiu18idsupervisorcaso = ($asaiu18idsupervisorcaso[$i_saiu18idsupervisorcaso]);
					$i_saiu18idresponsablecaso = $fila['saiu18idresponsablecaso'];
					if (isset($asaiu18idresponsablecaso[$i_saiu18idresponsablecaso]) == 0) {
						$sSQL = 'SELECT unad11razonsocial FROM unad11terceros WHERE unad11id=' . $i_saiu18idresponsablecaso . '';
						$tablae = $objDB->ejecutasql($sSQL);
						if ($objDB->nf($tablae) > 0) {
							$filae = $objDB->sf($tablae);
							$asaiu18idresponsablecaso[$i_saiu18idresponsablecaso] = cadena_LimpiarTildes($filae['unad11razonsocial']);
							//$asaiu18idresponsablecaso[$i_saiu18idresponsablecaso] = cadena_decodificar($filae['unad11razonsocial']);
						} else {
							$asaiu18idresponsablecaso[$i_saiu18idresponsablecaso] = '{Ninguno}';
						}
					}
					$saiu18idresponsablecaso = ($asaiu18idresponsablecaso[$i_saiu18idresponsablecaso]);
					$sDoc = mb_convert_encoding($fila['unad11tipodoc'] . $fila['unad11doc'], 'ISO-8859-1', 'UTF-8');
					$sRazonSocial = mb_convert_encoding($fila['nominteresado'], 'ISO-8859-1', 'UTF-8');
					$i_saiu18idzona = $fila['saiu18idzona'];
					if (isset($asaiu18idzona[$i_saiu18idzona]) == 0) {
						$sSQL = 'SELECT unad23nombre FROM unad23zona WHERE unad23id=' . $i_saiu18idzona . '';
						$tablae = $objDB->ejecutasql($sSQL);
						if ($objDB->nf($tablae) > 0) {
							$filae = $objDB->sf($tablae);
							$asaiu18idzona[$i_saiu18idzona] = $filae['unad23nombre'];
						} else {
							$asaiu18idzona[$i_saiu18idzona] = '{Ninguna}';
						}
					}
					$saiu18idzona = ($asaiu18idzona[$i_saiu18idzona]);
					$i_saiu18idcentro = $fila['saiu18idcentro'];
					if (isset($asaiu18idcentro[$i_saiu18idcentro]) == 0) {
						$sSQL = 'SELECT unad24nombre FROM unad24sede WHERE unad24id=' . $i_saiu18idcentro . '';
						$tablae = $objDB->ejecutasql($sSQL);
						if ($objDB->nf($tablae) > 0) {
							$filae = $objDB->sf($tablae);
							$asaiu18idcentro[$i_saiu18idcentro] = $filae['unad24nombre'];
						} else {
							$asaiu18idcentro[$i_saiu18idcentro] = '{Ninguna}';
						}
					}
					$saiu18idcentro = ($asaiu18idcentro[$i_saiu18idcentro]);
					$i_saiu18idescuela = $fila['saiu18idescuela'];
					if (isset($asaiu18idescuela[$i_saiu18idescuela]) == 0) {
						$sSQL = 'SELECT core12nombre FROM core12escuela WHERE core12id=' . $i_saiu18idescuela . '';
						$tablae = $objDB->ejecutasql($sSQL);
						if ($objDB->nf($tablae) > 0) {
							$filae = $objDB->sf($tablae);
							$asaiu18idescuela[$i_saiu18idescuela] = $filae['core12nombre'];
						} else {
							$asaiu18idescuela[$i_saiu18idescuela] = '{Ninguna}';
						}
					}
					$saiu18idescuela = ($asaiu18idescuela[$i_saiu18idescuela]);
					$i_saiu18idprograma = $fila['saiu18idprograma'];
					if (isset($asaiu18idprograma[$i_saiu18idprograma]) == 0) {
						$sSQL = 'SELECT core09nombre FROM core09programa WHERE core09id=' . $i_saiu18idprograma . '';
						$tablae = $objDB->ejecutasql($sSQL);
						if ($objDB->nf($tablae) > 0) {
							$filae = $objDB->sf($tablae);
							$asaiu18idprograma[$i_saiu18idprograma] = $filae['core09nombre'];
						} else {
							$asaiu18idprograma[$i_saiu18idprograma] = '{Ninguno}';
						}
					}
					$saiu18idprograma = ($asaiu18idprograma[$i_saiu18idprograma]);
					
					$objHoja->setCellValueByColumnAndRow(0, $iFila, $fila['saiu18id']);
					$objHoja->setCellValueByColumnAndRow(1, $iFila, $saiu18dia);
					$objHoja->setCellValueByColumnAndRow(2, $iFila, $saiu18hora);
					$objHoja->setCellValueByColumnAndRow(3, $iFila, cadena_decodificar($saiu18estado));
					$objHoja->setCellValueByColumnAndRow(4, $iFila, $saiu18fechafin);
					$objHoja->setCellValueByColumnAndRow(5, $iFila, $saiu18horafin);
					$objHoja->setCellValueByColumnAndRow(6, $iFila, cadena_decodificar($saiu18solucion));
					$objHoja->setCellValueByColumnAndRow(7, $iFila, cadena_decodificar($saiu18tiposolicitud));
					$objHoja->setCellValueByColumnAndRow(8, $iFila, cadena_decodificar($saiu18temasolicitud));
					$objHoja->setCellValueByColumnAndRow(9, $iFila, cadena_decodificar($saiu18idunidadcaso));
					$objHoja->setCellValueByColumnAndRow(10, $iFila, cadena_decodificar($saiu18idequipocaso));
					$objHoja->setCellValueByColumnAndRow(11, $iFila, $saiu18idsupervisorcaso);
					$objHoja->setCellValueByColumnAndRow(12, $iFila, $saiu18idresponsablecaso);
					$objHoja->setCellValueByColumnAndRow(13, $iFila, $sDoc);
					$objHoja->setCellValueByColumnAndRow(14, $iFila, $sRazonSocial);
					$objHoja->setCellValueByColumnAndRow(15, $iFila, $saiu18idzona);
					$objHoja->setCellValueByColumnAndRow(16, $iFila, $saiu18idcentro);
					$objHoja->setCellValueByColumnAndRow(17, $iFila, $saiu18idescuela);
					$objHoja->setCellValueByColumnAndRow(18, $iFila, $saiu18idprograma);
					$iFila++;
					if ($i_saiu18estado == 7) {
						$objPHPExcel->setActiveSheetIndex(1);
						$objHoja = $objPHPExcel->getActiveSheet();
						$objHoja->setCellValueByColumnAndRow(0, $iFilaHoja2, $fila['saiu18id']);
						$objHoja->setCellValueByColumnAndRow(1, $iFilaHoja2, $sDoc);
						$objHoja->setCellValueByColumnAndRow(2, $iFilaHoja2, $sRazonSocial);
						$objHoja->setCellValueByColumnAndRow(3, $iFilaHoja2, cadena_decodificar($saiu18temasolicitud));
						$objHoja->setCellValueByColumnAndRow(4, $iFilaHoja2, $saiu18evalfecha);
						$objHoja->setCellValueByColumnAndRow(5, $iFilaHoja2, $acalificacion[$fila['saiu18evalamabilidad']]);
						$objHoja->setCellValueByColumnAndRow(6, $iFilaHoja2, cadena_decodificar($fila['saiu18evalamabmotivo']));
						$objHoja->setCellValueByColumnAndRow(7, $iFilaHoja2, $acalificacion[$fila['saiu18evalrapidez']]);
						$objHoja->setCellValueByColumnAndRow(8, $iFilaHoja2, cadena_decodificar($fila['saiu18evalrapidmotivo']));
						$objHoja->setCellValueByColumnAndRow(9, $iFilaHoja2, $acalificacion[$fila['saiu18evalclaridad']]);
						$objHoja->setCellValueByColumnAndRow(10, $iFilaHoja2, cadena_decodificar($fila['saiu18evalcalridmotivo']));
						$objHoja->setCellValueByColumnAndRow(11, $iFilaHoja2, $acalificacion[$fila['saiu18evalresolvio']]);
						$objHoja->setCellValueByColumnAndRow(12, $iFilaHoja2, cadena_decodificar($fila['saiu18evalsugerencias']));
						$objHoja->setCellValueByColumnAndRow(13, $iFilaHoja2, $acalificacion[$fila['saiu18evalconocimiento']]);
						$objHoja->setCellValueByColumnAndRow(14, $iFilaHoja2, cadena_decodificar($fila['saiu18evalconocmotivo']));
						$objHoja->setCellValueByColumnAndRow(15, $iFilaHoja2, $acalificacion[$fila['saiu18evalutilidad']]);
						$objHoja->setCellValueByColumnAndRow(16, $iFilaHoja2, cadena_decodificar($fila['saiu18evalutilmotivo']));
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
