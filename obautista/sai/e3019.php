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
$mensajes_3019 = 'lg/lg_3019_' . $_SESSION['unad_idioma'] . '.php';
if (!file_exists($mensajes_3019)) {
	$mensajes_3019 = 'lg/lg_3019_es.php';
}
require $mensajes_3019;
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
	$sTituloRpt = $sTituloRpt . ' Atencion Chat ' . $iAgno;
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
		$objPHPExcel->getProperties()->setDescription('Reporte de Atencion Chat ' . $sServerRpt . ' creado en ' . fecha_hoy() . ' ' . formato_horaminuto(fecha_hora(), fecha_minuto()));
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
			cadena_tildes($ETI['saiu19consec']), cadena_tildes($ETI['msg_fecha']), cadena_tildes($ETI['saiu19hora']), cadena_tildes($ETI['saiu19estado']),cadena_tildes($ETI['saiu19fecharespuesta']), cadena_tildes($ETI['saiu19horarespuesta']), cadena_tildes($ETI['saiu19solucion']),
			cadena_tildes($ETI['saiu19tiposolicitud']), cadena_tildes($ETI['saiu19temasolicitud']), cadena_tildes($ETI['saiu19idunidadcaso']), cadena_tildes($ETI['saiu19idequipocaso']), cadena_tildes($ETI['saiu19idsupervisorcaso']),
			cadena_tildes($ETI['saiu19idresponsablecaso']), cadena_tildes($ETI['saiu19idsolicitante']), cadena_tildes($ETI['saiu19razonsocial']), cadena_tildes($ETI['saiu19idzona']),
			cadena_tildes($ETI['saiu19idcentro']), cadena_tildes($ETI['saiu19idescuela']), cadena_tildes($ETI['saiu19idprograma'])
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
			cadena_tildes($ETI['saiu19consec']), cadena_tildes($ETI['saiu19idsolicitante']), cadena_tildes($ETI['saiu19razonsocial']), cadena_tildes($ETI['saiu19temasolicitud']), cadena_tildes($ETI['saiu19evalfecha']),
			cadena_tildes($ETI['saiu19evalamabilidad']), cadena_tildes($ETI['saiu19evalamabmotivo']), cadena_tildes($ETI['saiu19evalrapidez']), cadena_tildes($ETI['saiu19evalrapidmotivo']), cadena_tildes($ETI['saiu19evalclaridad']),
			cadena_tildes($ETI['saiu19evalcalridmotivo']), cadena_tildes($ETI['saiu19evalresolvio']), cadena_tildes($ETI['saiu19evalsugerencias']), cadena_tildes($ETI['saiu19evalconocimiento']),
			cadena_tildes($ETI['saiu19evalconocmotivo']), cadena_tildes($ETI['saiu19evalutilidad']), cadena_tildes($ETI['saiu19evalutilmotivo'])
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
			$sCondi = $sCondi . ' AND TB.saiu19estado=' . $iEstado . '';
		}
		switch ($iListar) {
			case 1:
				$sCondi = $sCondi . ' AND TB.saiu19idresponsable=' . $idTercero . '';
				break;
			case 2:
				$sCondi = $sCondi . ' AND TB.saiu19idresponsablecaso=' . $idTercero . '';
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
					$sCondi = $sCondi . ' AND TB.saiu19idequipocaso IN (' . $sEquipos . ')';
				} else {
					$sCondi = $sCondi . ' AND TB.saiu19idresponsablecaso=' . $idTercero . '';
				}
				break;
		}
		$sSQL = '';
		$sSQL = $sSQL . 'SELECT TB.saiu19agno, TB.saiu19mes, TB.saiu19dia, TB.saiu19id, TB.saiu19estado, 
		TB.saiu19hora, TB.saiu19minuto, T11.unad11tipodoc, T11.unad11doc, T11.unad11razonsocial AS nominteresado, 
		TB.saiu19idzona, TB.saiu19idcentro, TB.saiu19idescuela, TB.saiu19idprograma, TB.saiu19solucion, 
		TB.saiu19tiposolicitud, TB.saiu19temasolicitud, TB.saiu19idunidadcaso, TB.saiu19idequipocaso, TB.saiu19idsupervisorcaso, 
		TB.saiu19idresponsablecaso, TB.saiu19evalfecha, TB.saiu19evalamabilidad, TB.saiu19evalamabmotivo, TB.saiu19evalrapidez,
		TB.saiu19evalrapidmotivo, TB.saiu19evalclaridad, TB.saiu19evalcalridmotivo, TB.saiu19evalresolvio, TB.saiu19evalsugerencias,
		TB.saiu19evalconocimiento, TB.saiu19evalconocmotivo, TB.saiu19evalutilidad, TB.saiu19evalutilmotivo,
		TB.saiu19fechafin, TB.saiu19horafin, TB.saiu19minutofin
		FROM saiu19chat_' . $iAgno . ' AS TB, unad11terceros AS T11
		WHERE TB.saiu19idsolicitante=T11.unad11id ' . $sCondi . '';
		if ($sSQL != '') {
			$sSQL = $sSQL . ' ORDER BY saiu19mes DESC, saiu19dia DESC, saiu19consec DESC';
			$tabla = $objDB->ejecutasql($sSQL);
			if ($tabla == false) {
				if ($bDebug) {
					$objHoja->setCellValueByColumnAndRow(1, $iFila, $sSQL);
					$iFila++;
				}
			} else {
				$asaiu19estado = array();
				$asaiu19tiposolicitud = array();
				$asaiu19temasolicitud = array();
				$asaiu19idunidadcaso = array();
				$asaiu19idequipocaso = array();
				$asaiu19idsupervisorcaso = array();
				$asaiu19idresponsablecaso = array();
				$asaiu19idzona = array();
				$asaiu19idcentro = array();
				$asaiu19idescuela = array();
				$asaiu19idprograma = array();
				$acalificacion = array('','Deficiente','Malo','Aceptable','Bueno','Excelente');
				while ($fila = $objDB->sf($tabla)) {
					$saiu19dia = fecha_armar($fila['saiu19dia'], $fila['saiu19mes'], $fila['saiu19agno']);
					$saiu19hora = html_TablaHoraMin($fila['saiu19hora'], $fila['saiu19minuto']);
					$i_saiu19estado = $fila['saiu19estado'];
					$saiu19fechafin = fecha_desdenumero($fila['saiu19fechafin']);
					$saiu19horafin = html_TablaHoraMin($fila['saiu19horafin'], $fila['saiu19minutofin']);
					$saiu19evalfecha = fecha_desdenumero($fila['saiu19evalfecha']);
					if (isset($asaiu19estado[$i_saiu19estado]) == 0) {
						$sSQL = 'SELECT saiu11nombre FROM saiu11estadosol WHERE saiu11id=' . $i_saiu19estado . '';
						$tablae = $objDB->ejecutasql($sSQL);
						if ($objDB->nf($tablae) > 0) {
							$filae = $objDB->sf($tablae);
							$asaiu19estado[$i_saiu19estado] = cadena_LimpiarTildes($filae['saiu11nombre']);
						} else {
							$asaiu19estado[$i_saiu19estado] = '';
						}
					}
					$saiu19estado = cadena_tildes($asaiu19estado[$i_saiu19estado]);
					$i_saiu19solucion = $fila['saiu19solucion'];
					$saiu19solucion = cadena_tildes($asaiu19solucion[$i_saiu19solucion]);
					$i_saiu19tiposolicitud = $fila['saiu19tiposolicitud'];
					if (isset($asaiu19tiposolicitud[$i_saiu19tiposolicitud]) == 0) {
						$sSQL = 'SELECT saiu02titulo FROM saiu02tiposol WHERE saiu02id=' . $i_saiu19tiposolicitud . '';
						$tablae = $objDB->ejecutasql($sSQL);
						if ($objDB->nf($tablae) > 0) {
							$filae = $objDB->sf($tablae);
							$asaiu19tiposolicitud[$i_saiu19tiposolicitud] = cadena_LimpiarTildes($filae['saiu02titulo']);
						} else {
							$asaiu19tiposolicitud[$i_saiu19tiposolicitud] = '';
						}
					}
					$saiu19tiposolicitud = cadena_tildes($asaiu19tiposolicitud[$i_saiu19tiposolicitud]);
					$i_saiu19temasolicitud = $fila['saiu19temasolicitud'];
					if (isset($asaiu19temasolicitud[$i_saiu19temasolicitud]) == 0) {
						$sSQL = 'SELECT saiu03titulo FROM saiu03temasol WHERE saiu03id=' . $i_saiu19temasolicitud . '';
						$tablae = $objDB->ejecutasql($sSQL);
						if ($objDB->nf($tablae) > 0) {
							$filae = $objDB->sf($tablae);
							$asaiu19temasolicitud[$i_saiu19temasolicitud] = cadena_LimpiarTildes($filae['saiu03titulo']);
						} else {
							$asaiu19temasolicitud[$i_saiu19temasolicitud] = '';
						}
					}
					$saiu19temasolicitud = ($asaiu19temasolicitud[$i_saiu19temasolicitud]);
					$i_saiu19idunidadcaso = $fila['saiu19idunidadcaso'];
					if (isset($asaiu19idunidadcaso[$i_saiu19idunidadcaso]) == 0) {
						$sSQL = 'SELECT unae26nombre FROM unae26unidadesfun WHERE unae26id=' . $i_saiu19idunidadcaso . '';
						$tablae = $objDB->ejecutasql($sSQL);
						if ($objDB->nf($tablae) > 0) {
							$filae = $objDB->sf($tablae);
							$asaiu19idunidadcaso[$i_saiu19idunidadcaso] = cadena_LimpiarTildes($filae['unae26nombre']);
						} else {
							$asaiu19idunidadcaso[$i_saiu19idunidadcaso] = '{Ninguna}';
						}
					}
					$saiu19idunidadcaso = ($asaiu19idunidadcaso[$i_saiu19idunidadcaso]);
					$i_saiu19idequipocaso = $fila['saiu19idequipocaso'];
					if (isset($asaiu19idequipocaso[$i_saiu19idequipocaso]) == 0) {
						$sSQL = 'SELECT bita27nombre FROM bita27equipotrabajo WHERE bita27id=' . $i_saiu19idequipocaso . '';
						$tablae = $objDB->ejecutasql($sSQL);
						if ($objDB->nf($tablae) > 0) {
							$filae = $objDB->sf($tablae);
							$asaiu19idequipocaso[$i_saiu19idequipocaso] = cadena_LimpiarTildes($filae['bita27nombre']);
						} else {
							$asaiu19idequipocaso[$i_saiu19idequipocaso] = '{Ninguno}';
						}
					}
					$saiu19idequipocaso = ($asaiu19idequipocaso[$i_saiu19idequipocaso]);
					$i_saiu19idsupervisorcaso = $fila['saiu19idsupervisorcaso'];
					if (isset($asaiu19idsupervisorcaso[$i_saiu19idsupervisorcaso]) == 0) {
						$sSQL = 'SELECT unad11razonsocial FROM unad11terceros WHERE unad11id=' . $i_saiu19idsupervisorcaso . '';
						$tablae = $objDB->ejecutasql($sSQL);
						if ($objDB->nf($tablae) > 0) {
							$filae = $objDB->sf($tablae);
							$asaiu19idsupervisorcaso[$i_saiu19idsupervisorcaso] = cadena_LimpiarTildes($filae['unad11razonsocial']);
						} else {
							$asaiu19idsupervisorcaso[$i_saiu19idsupervisorcaso] = '{Ninguno}';
						}
					}
					$saiu19idsupervisorcaso = ($asaiu19idsupervisorcaso[$i_saiu19idsupervisorcaso]);
					$i_saiu19idresponsablecaso = $fila['saiu19idresponsablecaso'];
					if (isset($asaiu19idresponsablecaso[$i_saiu19idresponsablecaso]) == 0) {
						$sSQL = 'SELECT unad11razonsocial FROM unad11terceros WHERE unad11id=' . $i_saiu19idresponsablecaso . '';
						$tablae = $objDB->ejecutasql($sSQL);
						if ($objDB->nf($tablae) > 0) {
							$filae = $objDB->sf($tablae);
							$asaiu19idresponsablecaso[$i_saiu19idresponsablecaso] = cadena_LimpiarTildes($filae['unad11razonsocial']);
							//$asaiu19idresponsablecaso[$i_saiu19idresponsablecaso] = cadena_decodificar($filae['unad11razonsocial']);
						} else {
							$asaiu19idresponsablecaso[$i_saiu19idresponsablecaso] = '{Ninguno}';
						}
					}
					$saiu19idresponsablecaso = ($asaiu19idresponsablecaso[$i_saiu19idresponsablecaso]);
					$sDoc = mb_convert_encoding($fila['unad11tipodoc'] . $fila['unad11doc'], 'ISO-8859-1', 'UTF-8');
					$sRazonSocial = mb_convert_encoding($fila['nominteresado'], 'ISO-8859-1', 'UTF-8');
					$i_saiu19idzona = $fila['saiu19idzona'];
					if (isset($asaiu19idzona[$i_saiu19idzona]) == 0) {
						$sSQL = 'SELECT unad23nombre FROM unad23zona WHERE unad23id=' . $i_saiu19idzona . '';
						$tablae = $objDB->ejecutasql($sSQL);
						if ($objDB->nf($tablae) > 0) {
							$filae = $objDB->sf($tablae);
							$asaiu19idzona[$i_saiu19idzona] = $filae['unad23nombre'];
						} else {
							$asaiu19idzona[$i_saiu19idzona] = '{Ninguna}';
						}
					}
					$saiu19idzona = ($asaiu19idzona[$i_saiu19idzona]);
					$i_saiu19idcentro = $fila['saiu19idcentro'];
					if (isset($asaiu19idcentro[$i_saiu19idcentro]) == 0) {
						$sSQL = 'SELECT unad24nombre FROM unad24sede WHERE unad24id=' . $i_saiu19idcentro . '';
						$tablae = $objDB->ejecutasql($sSQL);
						if ($objDB->nf($tablae) > 0) {
							$filae = $objDB->sf($tablae);
							$asaiu19idcentro[$i_saiu19idcentro] = $filae['unad24nombre'];
						} else {
							$asaiu19idcentro[$i_saiu19idcentro] = '{Ninguna}';
						}
					}
					$saiu19idcentro = ($asaiu19idcentro[$i_saiu19idcentro]);
					$i_saiu19idescuela = $fila['saiu19idescuela'];
					if (isset($asaiu19idescuela[$i_saiu19idescuela]) == 0) {
						$sSQL = 'SELECT core12nombre FROM core12escuela WHERE core12id=' . $i_saiu19idescuela . '';
						$tablae = $objDB->ejecutasql($sSQL);
						if ($objDB->nf($tablae) > 0) {
							$filae = $objDB->sf($tablae);
							$asaiu19idescuela[$i_saiu19idescuela] = $filae['core12nombre'];
						} else {
							$asaiu19idescuela[$i_saiu19idescuela] = '{Ninguna}';
						}
					}
					$saiu19idescuela = ($asaiu19idescuela[$i_saiu19idescuela]);
					$i_saiu19idprograma = $fila['saiu19idprograma'];
					if (isset($asaiu19idprograma[$i_saiu19idprograma]) == 0) {
						$sSQL = 'SELECT core09nombre FROM core09programa WHERE core09id=' . $i_saiu19idprograma . '';
						$tablae = $objDB->ejecutasql($sSQL);
						if ($objDB->nf($tablae) > 0) {
							$filae = $objDB->sf($tablae);
							$asaiu19idprograma[$i_saiu19idprograma] = $filae['core09nombre'];
						} else {
							$asaiu19idprograma[$i_saiu19idprograma] = '{Ninguno}';
						}
					}
					$saiu19idprograma = ($asaiu19idprograma[$i_saiu19idprograma]);
					
					$objHoja->setCellValueByColumnAndRow(0, $iFila, $fila['saiu19id']);
					$objHoja->setCellValueByColumnAndRow(1, $iFila, $saiu19dia);
					$objHoja->setCellValueByColumnAndRow(2, $iFila, $saiu19hora);
					$objHoja->setCellValueByColumnAndRow(3, $iFila, cadena_decodificar($saiu19estado));
					$objHoja->setCellValueByColumnAndRow(4, $iFila, $saiu19fechafin);
					$objHoja->setCellValueByColumnAndRow(5, $iFila, $saiu19horafin);
					$objHoja->setCellValueByColumnAndRow(6, $iFila, cadena_decodificar($saiu19solucion));
					$objHoja->setCellValueByColumnAndRow(7, $iFila, cadena_decodificar($saiu19tiposolicitud));
					$objHoja->setCellValueByColumnAndRow(8, $iFila, cadena_decodificar($saiu19temasolicitud));
					$objHoja->setCellValueByColumnAndRow(9, $iFila, cadena_decodificar($saiu19idunidadcaso));
					$objHoja->setCellValueByColumnAndRow(10, $iFila, cadena_decodificar($saiu19idequipocaso));
					$objHoja->setCellValueByColumnAndRow(11, $iFila, $saiu19idsupervisorcaso);
					$objHoja->setCellValueByColumnAndRow(12, $iFila, $saiu19idresponsablecaso);
					$objHoja->setCellValueByColumnAndRow(13, $iFila, $sDoc);
					$objHoja->setCellValueByColumnAndRow(14, $iFila, $sRazonSocial);
					$objHoja->setCellValueByColumnAndRow(15, $iFila, $saiu19idzona);
					$objHoja->setCellValueByColumnAndRow(16, $iFila, $saiu19idcentro);
					$objHoja->setCellValueByColumnAndRow(17, $iFila, $saiu19idescuela);
					$objHoja->setCellValueByColumnAndRow(18, $iFila, $saiu19idprograma);
					$iFila++;
					if ($i_saiu19estado == 7) {
						$objPHPExcel->setActiveSheetIndex(1);
						$objHoja = $objPHPExcel->getActiveSheet();
						$objHoja->setCellValueByColumnAndRow(0, $iFilaHoja2, $fila['saiu19id']);
						$objHoja->setCellValueByColumnAndRow(1, $iFilaHoja2, $sDoc);
						$objHoja->setCellValueByColumnAndRow(2, $iFilaHoja2, $sRazonSocial);
						$objHoja->setCellValueByColumnAndRow(3, $iFilaHoja2, cadena_decodificar($saiu19temasolicitud));
						$objHoja->setCellValueByColumnAndRow(4, $iFilaHoja2, $saiu19evalfecha);
						$objHoja->setCellValueByColumnAndRow(5, $iFilaHoja2, $acalificacion[$fila['saiu19evalamabilidad']]);
						$objHoja->setCellValueByColumnAndRow(6, $iFilaHoja2, cadena_decodificar($fila['saiu19evalamabmotivo']));
						$objHoja->setCellValueByColumnAndRow(7, $iFilaHoja2, $acalificacion[$fila['saiu19evalrapidez']]);
						$objHoja->setCellValueByColumnAndRow(8, $iFilaHoja2, cadena_decodificar($fila['saiu19evalrapidmotivo']));
						$objHoja->setCellValueByColumnAndRow(9, $iFilaHoja2, $acalificacion[$fila['saiu19evalclaridad']]);
						$objHoja->setCellValueByColumnAndRow(10, $iFilaHoja2, cadena_decodificar($fila['saiu19evalcalridmotivo']));
						$objHoja->setCellValueByColumnAndRow(11, $iFilaHoja2, $acalificacion[$fila['saiu19evalresolvio']]);
						$objHoja->setCellValueByColumnAndRow(12, $iFilaHoja2, cadena_decodificar($fila['saiu19evalsugerencias']));
						$objHoja->setCellValueByColumnAndRow(13, $iFilaHoja2, $acalificacion[$fila['saiu19evalconocimiento']]);
						$objHoja->setCellValueByColumnAndRow(14, $iFilaHoja2, cadena_decodificar($fila['saiu19evalconocmotivo']));
						$objHoja->setCellValueByColumnAndRow(15, $iFilaHoja2, $acalificacion[$fila['saiu19evalutilidad']]);
						$objHoja->setCellValueByColumnAndRow(16, $iFilaHoja2, cadena_decodificar($fila['saiu19evalutilmotivo']));
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
