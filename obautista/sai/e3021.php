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
$mensajes_3021 = 'lg/lg_3021_' . $_SESSION['unad_idioma'] . '.php';
if (!file_exists($mensajes_3021)) {
	$mensajes_3021 = 'lg/lg_3021_es.php';
}
require $mensajes_3021;
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
	$sTituloRpt = $sTituloRpt . ' Atencion Presenc ' . $iAgno;
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
		$objPHPExcel->getProperties()->setDescription('Reporte de Atencion Presencial ' . $sServerRpt . ' creado en ' . fecha_hoy() . ' ' . formato_horaminuto(fecha_hora(), fecha_minuto()));
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
			cadena_tildes($ETI['saiu21consec']), cadena_tildes($ETI['msg_fecha']), cadena_tildes($ETI['saiu21hora']), cadena_tildes($ETI['saiu21estado']),cadena_tildes($ETI['saiu21fecharespuesta']), cadena_tildes($ETI['saiu21horarespuesta']), cadena_tildes($ETI['saiu21solucion']),
			cadena_tildes($ETI['saiu21tiposolicitud']), cadena_tildes($ETI['saiu21temasolicitud']), cadena_tildes($ETI['saiu21idunidadcaso']), cadena_tildes($ETI['saiu21idequipocaso']), cadena_tildes($ETI['saiu21idsupervisorcaso']),
			cadena_tildes($ETI['saiu21idresponsablecaso']), cadena_tildes($ETI['saiu21idsolicitante']), cadena_tildes($ETI['saiu21razonsocial']), cadena_tildes($ETI['saiu21idzona']),
			cadena_tildes($ETI['saiu21idcentro']), cadena_tildes($ETI['saiu21idescuela']), cadena_tildes($ETI['saiu21idprograma'])
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
			cadena_tildes($ETI['saiu21consec']), cadena_tildes($ETI['saiu21idsolicitante']), cadena_tildes($ETI['saiu21razonsocial']), cadena_tildes($ETI['saiu21temasolicitud']), cadena_tildes($ETI['saiu21evalfecha']),
			cadena_tildes($ETI['saiu21evalamabilidad']), cadena_tildes($ETI['saiu21evalamabmotivo']), cadena_tildes($ETI['saiu21evalrapidez']), cadena_tildes($ETI['saiu21evalrapidmotivo']), cadena_tildes($ETI['saiu21evalclaridad']),
			cadena_tildes($ETI['saiu21evalcalridmotivo']), cadena_tildes($ETI['saiu21evalresolvio']), cadena_tildes($ETI['saiu21evalsugerencias']), cadena_tildes($ETI['saiu21evalconocimiento']),
			cadena_tildes($ETI['saiu21evalconocmotivo']), cadena_tildes($ETI['saiu21evalutilidad']), cadena_tildes($ETI['saiu21evalutilmotivo'])
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
			$sCondi = $sCondi . ' AND TB.saiu21estado=' . $iEstado . '';
		}
		switch ($iListar) {
			case 1:
				$sCondi = $sCondi . ' AND TB.saiu21idresponsable=' . $idTercero . '';
				break;
			case 2:
				$sCondi = $sCondi . ' AND TB.saiu21idresponsablecaso=' . $idTercero . '';
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
					$sCondi = $sCondi . ' AND TB.saiu21idequipocaso IN (' . $sEquipos . ')';
				} else {
					$sCondi = $sCondi . ' AND TB.saiu21idresponsablecaso=' . $idTercero . '';
				}
				break;
		}
		$sSQL = '';
		$sSQL = $sSQL . 'SELECT TB.saiu21agno, TB.saiu21mes, TB.saiu21dia, TB.saiu21id, TB.saiu21estado, 
		TB.saiu21hora, TB.saiu21minuto, T11.unad11tipodoc, T11.unad11doc, T11.unad11razonsocial AS nominteresado, 
		TB.saiu21idzona, TB.saiu21idcentro, TB.saiu21idescuela, TB.saiu21idprograma, TB.saiu21solucion, 
		TB.saiu21tiposolicitud, TB.saiu21temasolicitud, TB.saiu21idunidadcaso, TB.saiu21idequipocaso, TB.saiu21idsupervisorcaso, 
		TB.saiu21idresponsablecaso, TB.saiu21evalfecha, TB.saiu21evalamabilidad, TB.saiu21evalamabmotivo, TB.saiu21evalrapidez,
		TB.saiu21evalrapidmotivo, TB.saiu21evalclaridad, TB.saiu21evalcalridmotivo, TB.saiu21evalresolvio, TB.saiu21evalsugerencias,
		TB.saiu21evalconocimiento, TB.saiu21evalconocmotivo, TB.saiu21evalutilidad, TB.saiu21evalutilmotivo,
		TB.saiu21fechafin, TB.saiu21horafin, TB.saiu21minutofin
		FROM saiu21directa_' . $iAgno . ' AS TB, unad11terceros AS T11
		WHERE TB.saiu21idsolicitante=T11.unad11id ' . $sCondi . '';
		if ($sSQL != '') {
			$sSQL = $sSQL . ' ORDER BY saiu21mes DESC, saiu21dia DESC, saiu21consec DESC';
			$tabla = $objDB->ejecutasql($sSQL);
			if ($tabla == false) {
				if ($bDebug) {
					$objHoja->setCellValueByColumnAndRow(1, $iFila, $sSQL);
					$iFila++;
				}
			} else {
				$asaiu21estado = array();
				$asaiu21tiposolicitud = array();
				$asaiu21temasolicitud = array();
				$asaiu21idunidadcaso = array();
				$asaiu21idequipocaso = array();
				$asaiu21idsupervisorcaso = array();
				$asaiu21idresponsablecaso = array();
				$asaiu21idzona = array();
				$asaiu21idcentro = array();
				$asaiu21idescuela = array();
				$asaiu21idprograma = array();
				$acalificacion = array('','Deficiente','Malo','Aceptable','Bueno','Excelente');
				while ($fila = $objDB->sf($tabla)) {
					$saiu21dia = fecha_armar($fila['saiu21dia'], $fila['saiu21mes'], $fila['saiu21agno']);
					$saiu21hora = html_TablaHoraMin($fila['saiu21hora'], $fila['saiu21minuto']);
					$i_saiu21estado = $fila['saiu21estado'];
					$saiu21fechafin = fecha_desdenumero($fila['saiu21fechafin']);
					$saiu21horafin = html_TablaHoraMin($fila['saiu21horafin'], $fila['saiu21minutofin']);
					$saiu21evalfecha = fecha_desdenumero($fila['saiu21evalfecha']);
					if (isset($asaiu21estado[$i_saiu21estado]) == 0) {
						$sSQL = 'SELECT saiu11nombre FROM saiu11estadosol WHERE saiu11id=' . $i_saiu21estado . '';
						$tablae = $objDB->ejecutasql($sSQL);
						if ($objDB->nf($tablae) > 0) {
							$filae = $objDB->sf($tablae);
							$asaiu21estado[$i_saiu21estado] = cadena_LimpiarTildes($filae['saiu11nombre']);
						} else {
							$asaiu21estado[$i_saiu21estado] = '';
						}
					}
					$saiu21estado = cadena_tildes($asaiu21estado[$i_saiu21estado]);
					$i_saiu21solucion = $fila['saiu21solucion'];
					$saiu21solucion = cadena_tildes($asaiu21solucion[$i_saiu21solucion]);
					$i_saiu21tiposolicitud = $fila['saiu21tiposolicitud'];
					if (isset($asaiu21tiposolicitud[$i_saiu21tiposolicitud]) == 0) {
						$sSQL = 'SELECT saiu02titulo FROM saiu02tiposol WHERE saiu02id=' . $i_saiu21tiposolicitud . '';
						$tablae = $objDB->ejecutasql($sSQL);
						if ($objDB->nf($tablae) > 0) {
							$filae = $objDB->sf($tablae);
							$asaiu21tiposolicitud[$i_saiu21tiposolicitud] = cadena_LimpiarTildes($filae['saiu02titulo']);
						} else {
							$asaiu21tiposolicitud[$i_saiu21tiposolicitud] = '';
						}
					}
					$saiu21tiposolicitud = cadena_tildes($asaiu21tiposolicitud[$i_saiu21tiposolicitud]);
					$i_saiu21temasolicitud = $fila['saiu21temasolicitud'];
					if (isset($asaiu21temasolicitud[$i_saiu21temasolicitud]) == 0) {
						$sSQL = 'SELECT saiu03titulo FROM saiu03temasol WHERE saiu03id=' . $i_saiu21temasolicitud . '';
						$tablae = $objDB->ejecutasql($sSQL);
						if ($objDB->nf($tablae) > 0) {
							$filae = $objDB->sf($tablae);
							$asaiu21temasolicitud[$i_saiu21temasolicitud] = cadena_LimpiarTildes($filae['saiu03titulo']);
						} else {
							$asaiu21temasolicitud[$i_saiu21temasolicitud] = '';
						}
					}
					$saiu21temasolicitud = ($asaiu21temasolicitud[$i_saiu21temasolicitud]);
					$i_saiu21idunidadcaso = $fila['saiu21idunidadcaso'];
					if (isset($asaiu21idunidadcaso[$i_saiu21idunidadcaso]) == 0) {
						$sSQL = 'SELECT unae26nombre FROM unae26unidadesfun WHERE unae26id=' . $i_saiu21idunidadcaso . '';
						$tablae = $objDB->ejecutasql($sSQL);
						if ($objDB->nf($tablae) > 0) {
							$filae = $objDB->sf($tablae);
							$asaiu21idunidadcaso[$i_saiu21idunidadcaso] = cadena_LimpiarTildes($filae['unae26nombre']);
						} else {
							$asaiu21idunidadcaso[$i_saiu21idunidadcaso] = '{Ninguna}';
						}
					}
					$saiu21idunidadcaso = ($asaiu21idunidadcaso[$i_saiu21idunidadcaso]);
					$i_saiu21idequipocaso = $fila['saiu21idequipocaso'];
					if (isset($asaiu21idequipocaso[$i_saiu21idequipocaso]) == 0) {
						$sSQL = 'SELECT bita27nombre FROM bita27equipotrabajo WHERE bita27id=' . $i_saiu21idequipocaso . '';
						$tablae = $objDB->ejecutasql($sSQL);
						if ($objDB->nf($tablae) > 0) {
							$filae = $objDB->sf($tablae);
							$asaiu21idequipocaso[$i_saiu21idequipocaso] = cadena_LimpiarTildes($filae['bita27nombre']);
						} else {
							$asaiu21idequipocaso[$i_saiu21idequipocaso] = '{Ninguno}';
						}
					}
					$saiu21idequipocaso = ($asaiu21idequipocaso[$i_saiu21idequipocaso]);
					$i_saiu21idsupervisorcaso = $fila['saiu21idsupervisorcaso'];
					if (isset($asaiu21idsupervisorcaso[$i_saiu21idsupervisorcaso]) == 0) {
						$sSQL = 'SELECT unad11razonsocial FROM unad11terceros WHERE unad11id=' . $i_saiu21idsupervisorcaso . '';
						$tablae = $objDB->ejecutasql($sSQL);
						if ($objDB->nf($tablae) > 0) {
							$filae = $objDB->sf($tablae);
							$asaiu21idsupervisorcaso[$i_saiu21idsupervisorcaso] = cadena_LimpiarTildes($filae['unad11razonsocial']);
						} else {
							$asaiu21idsupervisorcaso[$i_saiu21idsupervisorcaso] = '{Ninguno}';
						}
					}
					$saiu21idsupervisorcaso = ($asaiu21idsupervisorcaso[$i_saiu21idsupervisorcaso]);
					$i_saiu21idresponsablecaso = $fila['saiu21idresponsablecaso'];
					if (isset($asaiu21idresponsablecaso[$i_saiu21idresponsablecaso]) == 0) {
						$sSQL = 'SELECT unad11razonsocial FROM unad11terceros WHERE unad11id=' . $i_saiu21idresponsablecaso . '';
						$tablae = $objDB->ejecutasql($sSQL);
						if ($objDB->nf($tablae) > 0) {
							$filae = $objDB->sf($tablae);
							$asaiu21idresponsablecaso[$i_saiu21idresponsablecaso] = cadena_LimpiarTildes($filae['unad11razonsocial']);
							//$asaiu21idresponsablecaso[$i_saiu21idresponsablecaso] = cadena_decodificar($filae['unad11razonsocial']);
						} else {
							$asaiu21idresponsablecaso[$i_saiu21idresponsablecaso] = '{Ninguno}';
						}
					}
					$saiu21idresponsablecaso = ($asaiu21idresponsablecaso[$i_saiu21idresponsablecaso]);
					$sDoc = mb_convert_encoding($fila['unad11tipodoc'] . $fila['unad11doc'], 'ISO-8859-1', 'UTF-8');
					$sRazonSocial = mb_convert_encoding($fila['nominteresado'], 'ISO-8859-1', 'UTF-8');
					$i_saiu21idzona = $fila['saiu21idzona'];
					if (isset($asaiu21idzona[$i_saiu21idzona]) == 0) {
						$sSQL = 'SELECT unad23nombre FROM unad23zona WHERE unad23id=' . $i_saiu21idzona . '';
						$tablae = $objDB->ejecutasql($sSQL);
						if ($objDB->nf($tablae) > 0) {
							$filae = $objDB->sf($tablae);
							$asaiu21idzona[$i_saiu21idzona] = $filae['unad23nombre'];
						} else {
							$asaiu21idzona[$i_saiu21idzona] = '{Ninguna}';
						}
					}
					$saiu21idzona = ($asaiu21idzona[$i_saiu21idzona]);
					$i_saiu21idcentro = $fila['saiu21idcentro'];
					if (isset($asaiu21idcentro[$i_saiu21idcentro]) == 0) {
						$sSQL = 'SELECT unad24nombre FROM unad24sede WHERE unad24id=' . $i_saiu21idcentro . '';
						$tablae = $objDB->ejecutasql($sSQL);
						if ($objDB->nf($tablae) > 0) {
							$filae = $objDB->sf($tablae);
							$asaiu21idcentro[$i_saiu21idcentro] = $filae['unad24nombre'];
						} else {
							$asaiu21idcentro[$i_saiu21idcentro] = '{Ninguna}';
						}
					}
					$saiu21idcentro = ($asaiu21idcentro[$i_saiu21idcentro]);
					$i_saiu21idescuela = $fila['saiu21idescuela'];
					if (isset($asaiu21idescuela[$i_saiu21idescuela]) == 0) {
						$sSQL = 'SELECT core12nombre FROM core12escuela WHERE core12id=' . $i_saiu21idescuela . '';
						$tablae = $objDB->ejecutasql($sSQL);
						if ($objDB->nf($tablae) > 0) {
							$filae = $objDB->sf($tablae);
							$asaiu21idescuela[$i_saiu21idescuela] = $filae['core12nombre'];
						} else {
							$asaiu21idescuela[$i_saiu21idescuela] = '{Ninguna}';
						}
					}
					$saiu21idescuela = ($asaiu21idescuela[$i_saiu21idescuela]);
					$i_saiu21idprograma = $fila['saiu21idprograma'];
					if (isset($asaiu21idprograma[$i_saiu21idprograma]) == 0) {
						$sSQL = 'SELECT core09nombre FROM core09programa WHERE core09id=' . $i_saiu21idprograma . '';
						$tablae = $objDB->ejecutasql($sSQL);
						if ($objDB->nf($tablae) > 0) {
							$filae = $objDB->sf($tablae);
							$asaiu21idprograma[$i_saiu21idprograma] = $filae['core09nombre'];
						} else {
							$asaiu21idprograma[$i_saiu21idprograma] = '{Ninguno}';
						}
					}
					$saiu21idprograma = ($asaiu21idprograma[$i_saiu21idprograma]);
					
					$objHoja->setCellValueByColumnAndRow(0, $iFila, $fila['saiu21id']);
					$objHoja->setCellValueByColumnAndRow(1, $iFila, $saiu21dia);
					$objHoja->setCellValueByColumnAndRow(2, $iFila, $saiu21hora);
					$objHoja->setCellValueByColumnAndRow(3, $iFila, cadena_decodificar($saiu21estado));
					$objHoja->setCellValueByColumnAndRow(4, $iFila, $saiu21fechafin);
					$objHoja->setCellValueByColumnAndRow(5, $iFila, $saiu21horafin);
					$objHoja->setCellValueByColumnAndRow(6, $iFila, cadena_decodificar($saiu21solucion));
					$objHoja->setCellValueByColumnAndRow(7, $iFila, cadena_decodificar($saiu21tiposolicitud));
					$objHoja->setCellValueByColumnAndRow(8, $iFila, cadena_decodificar($saiu21temasolicitud));
					$objHoja->setCellValueByColumnAndRow(9, $iFila, cadena_decodificar($saiu21idunidadcaso));
					$objHoja->setCellValueByColumnAndRow(10, $iFila, cadena_decodificar($saiu21idequipocaso));
					$objHoja->setCellValueByColumnAndRow(11, $iFila, $saiu21idsupervisorcaso);
					$objHoja->setCellValueByColumnAndRow(12, $iFila, $saiu21idresponsablecaso);
					$objHoja->setCellValueByColumnAndRow(13, $iFila, $sDoc);
					$objHoja->setCellValueByColumnAndRow(14, $iFila, $sRazonSocial);
					$objHoja->setCellValueByColumnAndRow(15, $iFila, $saiu21idzona);
					$objHoja->setCellValueByColumnAndRow(16, $iFila, $saiu21idcentro);
					$objHoja->setCellValueByColumnAndRow(17, $iFila, $saiu21idescuela);
					$objHoja->setCellValueByColumnAndRow(18, $iFila, $saiu21idprograma);
					$iFila++;
					if ($i_saiu21estado == 7) {
						$objPHPExcel->setActiveSheetIndex(1);
						$objHoja = $objPHPExcel->getActiveSheet();
						$objHoja->setCellValueByColumnAndRow(0, $iFilaHoja2, $fila['saiu21id']);
						$objHoja->setCellValueByColumnAndRow(1, $iFilaHoja2, $sDoc);
						$objHoja->setCellValueByColumnAndRow(2, $iFilaHoja2, $sRazonSocial);
						$objHoja->setCellValueByColumnAndRow(3, $iFilaHoja2, cadena_decodificar($saiu21temasolicitud));
						$objHoja->setCellValueByColumnAndRow(4, $iFilaHoja2, $saiu21evalfecha);
						$objHoja->setCellValueByColumnAndRow(5, $iFilaHoja2, $acalificacion[$fila['saiu21evalamabilidad']]);
						$objHoja->setCellValueByColumnAndRow(6, $iFilaHoja2, cadena_decodificar($fila['saiu21evalamabmotivo']));
						$objHoja->setCellValueByColumnAndRow(7, $iFilaHoja2, $acalificacion[$fila['saiu21evalrapidez']]);
						$objHoja->setCellValueByColumnAndRow(8, $iFilaHoja2, cadena_decodificar($fila['saiu21evalrapidmotivo']));
						$objHoja->setCellValueByColumnAndRow(9, $iFilaHoja2, $acalificacion[$fila['saiu21evalclaridad']]);
						$objHoja->setCellValueByColumnAndRow(10, $iFilaHoja2, cadena_decodificar($fila['saiu21evalcalridmotivo']));
						$objHoja->setCellValueByColumnAndRow(11, $iFilaHoja2, $acalificacion[$fila['saiu21evalresolvio']]);
						$objHoja->setCellValueByColumnAndRow(12, $iFilaHoja2, cadena_decodificar($fila['saiu21evalsugerencias']));
						$objHoja->setCellValueByColumnAndRow(13, $iFilaHoja2, $acalificacion[$fila['saiu21evalconocimiento']]);
						$objHoja->setCellValueByColumnAndRow(14, $iFilaHoja2, cadena_decodificar($fila['saiu21evalconocmotivo']));
						$objHoja->setCellValueByColumnAndRow(15, $iFilaHoja2, $acalificacion[$fila['saiu21evalutilidad']]);
						$objHoja->setCellValueByColumnAndRow(16, $iFilaHoja2, cadena_decodificar($fila['saiu21evalutilmotivo']));
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
