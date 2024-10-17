<?php
/*
--- © Omar Augusto Bautista Mora - UNAD - 2023 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Version 1.0 Miércoles, 12 de abril de 2023
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
$mensajes_3005 = $APP->rutacomun . 'lg/lg_3005_' . $_SESSION['unad_idioma'] . '.php';
if (!file_exists($mensajes_3005)) {
	$mensajes_3005 = $APP->rutacomun . 'lg/lg_3005_es.php';
}
require $mensajes_3005;
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
	$sTituloRpt = $sTituloRpt . ' PQRS ' . $iAgno;
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
		$objPHPExcel->getProperties()->setDescription('Reporte de PQRS ' . $sServerRpt . ' creado en ' . fecha_hoy() . ' ' . formato_horaminuto(fecha_hora(), fecha_minuto()));
		$objHoja = $objPHPExcel->getActiveSheet();
		$objHoja->setTitle($sTituloRpt);
		$sColTope = 'U';
		//Imagen del encabezado
		PHPExcel_Justificar_Celda_HorizontalCentro($objPHPExcel, 'A1');
		PHPExcel_Agrega_Dibujo($objPHPExcel, 'Logo', 'Logo', $APP->rutacomun . 'imagenes/rpt_cabeza.jpg', '161', 'A1', '0', false, '0');
		$sFechaImpreso = formato_fechalarga(fecha_hoy(), true);
		PHPExcel_Texto_Tres_Partes($objPHPExcel, $sColTope . '9', ' ', 'Fecha impresión: ', $sFechaImpreso, 'AmOsUn', true, false, 9, 'Calibri', 'AzOsUn');
		PHPExcel_Alinear_Celda_Derecha($objPHPExcel, $sColTope . '9');
		//Titulo 
		$objHoja->setCellValueByColumnAndRow(0, 10, $ETI['titulo'] . ' ' . $iAgno);
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
			cadena_tildes($ETI['msg_numsolicitud']), cadena_tildes($ETI['saiu05numref']), cadena_tildes($ETI['saiu05dia']), cadena_tildes($ETI['saiu05hora']), cadena_tildes($ETI['saiu05estado']),cadena_tildes($ETI['saiu05fecharespdef']), cadena_tildes($ETI['saiu05horarespdef']), cadena_tildes($ETI['saiu05idcategoria']),
			cadena_tildes($ETI['saiu05idtiposolorigen']), cadena_tildes($ETI['saiu05idclaseser']), cadena_tildes($ETI['saiu05idtemaorigen']), cadena_tildes($ETI['saiu05idunidadresp']), cadena_tildes($ETI['saiu05idequiporesp']), cadena_tildes($ETI['saiu05idsupervisor']),
			cadena_tildes($ETI['saiu05idresponsable']), cadena_tildes($ETI['saiu05idsolicitante']), cadena_tildes($ETI['saiu05razonsocial']), cadena_tildes($ETI['saiu05idzona']),
			cadena_tildes($ETI['saiu05cead']), cadena_tildes($ETI['saiu05idescuela']), cadena_tildes($ETI['saiu05idprograma'])
		);
		$iTitulos = count($aTitulos);
		for ($k = 0; $k <= 20; $k++) {
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
		$sColTope2='Q';
		PHPExcel_Mexclar_Celdas($objPHPExcel, 'A1:' . $sColTope2 . '2');
		PHPExcel_Justificar_Celda_HorizontalCentro($objPHPExcel, 'A1');
		PHPExcel_Formato_Fuente_Celda($objPHPExcel, 'A1', '14', 'Yu Gothic', 'AzOsUn', true, false, false);
		$iFilaHoja2 = 3;
		PHPExcel_RellenarCeldas($objPHPExcel, 'A1:' . $sColTope2 . $iFilaHoja2, 'Bl', false);
		$iFilaHoja2++;
		$iFilaBase2 = $iFilaHoja2;
		$aTitulos = array(
			cadena_tildes($ETI['msg_numsolicitud']), cadena_tildes($ETI['saiu05idsolicitante']), cadena_tildes($ETI['saiu05razonsocial']), cadena_tildes($ETI['saiu05idcategoria']), cadena_tildes($ETI['saiu05evalfecha']),
			cadena_tildes($ETI['saiu05evalamabilidad']), cadena_tildes($ETI['saiu05evalamabmotivo']), cadena_tildes($ETI['saiu05evalrapidez']), cadena_tildes($ETI['saiu05evalrapidmotivo']), cadena_tildes($ETI['saiu05evalclaridad']),
			cadena_tildes($ETI['saiu05evalcalridmotivo']), cadena_tildes($ETI['saiu05evalresolvio']), cadena_tildes($ETI['saiu05evalsugerencias']), cadena_tildes($ETI['saiu05evalconocimiento']),
			cadena_tildes($ETI['saiu05evalconocmotivo']), cadena_tildes($ETI['saiu05evalutilidad']), cadena_tildes($ETI['saiu05evalutilmotivo'])
		);
		$iTitulos = count($aTitulos);
		for ($k = 0; $k <= 16; $k++) {
			$objHoja->setCellValueByColumnAndRow($k, $iFilaHoja2, $aTitulos[$k]);
			$sColumna = columna_Letra($k);
			$objPHPExcel->getActiveSheet()->getColumnDimension($sColumna)->setWidth(13);
			PHPExcel_Justificar_Celda_HorizontalCentro($objPHPExcel, $sColumna . $iFilaHoja2);
		}
		PHPExcel_Formato_Fuente_Celda($objPHPExcel, 'A' . $iFilaHoja2 . ':' . $sColTope2 . $iFilaHoja2, '10', 'Yu Gothic', 'Ne', true, false, false);
		$iFilaHoja2++;
		
		$objPHPExcel->createSheet();
		$objPHPExcel->setActiveSheetIndex(2);
		$objPHPExcel->getActiveSheet()->setTitle('Detalle-Respuesta');
		$objHoja = $objPHPExcel->getActiveSheet();
		$objHoja->setCellValueByColumnAndRow(0, 1, cadena_tildes($ETI['saiu05detalle']) . ' ' . $iAgno);
		$objHoja->getStyleByColumnAndRow(0, 1)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$sColTope3='C';
		PHPExcel_Mexclar_Celdas($objPHPExcel, 'A1:' . $sColTope3 . '3');
		PHPExcel_Justificar_Celda_HorizontalCentro($objPHPExcel, 'A1');
		PHPExcel_Formato_Fuente_Celda($objPHPExcel, 'A1', '14', 'Yu Gothic', 'AzOsUn', true, false, false);
		$iFilaHoja3 = 3;
		$iFilaHoja3++;
		PHPExcel_RellenarCeldas($objPHPExcel, 'A1:' . $sColTope3 . $iFilaHoja3, 'Bl', true);
		$iFilaBase3 = $iFilaHoja3;
		$aTitulos = array(
			cadena_tildes($ETI['saiu05numref']), cadena_tildes($ETI['saiu05detalle']), cadena_tildes($ETI['saiu05respuesta'])
		);
		$iTitulos = count($aTitulos);
		for ($k = 0; $k <= 2; $k++) {
			$objHoja->setCellValueByColumnAndRow($k, $iFilaHoja3, $aTitulos[$k]);
			$sColumna = columna_Letra($k);
			$objPHPExcel->getActiveSheet()->getColumnDimension($sColumna)->setWidth(13);
			PHPExcel_Justificar_Celda_HorizontalCentro($objPHPExcel, $sColumna . $iFilaHoja3);
		}
		PHPExcel_Formato_Fuente_Celda($objPHPExcel, 'A' . $iFilaHoja3 . ':' . $sColTope3 . $iFilaHoja3, '10', 'Yu Gothic', 'Ne', true, false, false);
		$iFilaHoja3++;


		$objPHPExcel->setActiveSheetIndex(0);
		$objHoja = $objPHPExcel->getActiveSheet();
		PHPExcel_Formato_Fuente_Celda($objPHPExcel, 'A' . $iFila . ':' . $sColTope . $iFila, '10', 'Yu Gothic', 'Ne', true, false, false);
		$iFila++;
		$sCondi = '';
		$aTablas = array();
		$iTablas = 0;
		$iNumSolicitudes = 0;
		if ($sError == '') {
			$sSQL = 'SELECT saiu15agno, saiu15mes, SUM(saiu15numsolicitudes) AS Solicitudes 
			FROM saiu15historico 
			WHERE saiu15agno=' . $iAgno . ' AND saiu15tiporadicado=1
			GROUP BY saiu15agno, saiu15mes';
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . ' Historico: ' . $sSQL . '<br>';
			}
			$tabla15 = $objDB->ejecutasql($sSQL);
			while ($fila15 = $objDB->sf($tabla15)) {
				$iNumSolicitudes = $iNumSolicitudes + $fila15['Solicitudes'];
				if ($fila15['saiu15mes'] < 10) {
					$sContenedor = $fila15['saiu15agno'] . '0' . $fila15['saiu15mes'];
				} else {
					$sContenedor = $fila15['saiu15agno'] . $fila15['saiu15mes'];
				}
				$iTablas++;
				$aTablas[$iTablas] = $sContenedor;
				list ($sErrorR, $sDebugR) = f3005_RevisarTabla($sContenedor, $objDB, $bDebug);
			}
		}
		if ($iEstado !== '') {
			$sCondi = $sCondi . ' AND TB.saiu05estado=' . $iEstado . '';
		}
		switch ($iListar) {
			case 1:
				$sCondi = $sCondi . ' AND TB.saiu05idresponsable=' . $idTercero . '';
				break;
			case 2:
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
					$sCondi = $sCondi . ' AND TB.saiu05idequiporesp IN (' . $sEquipos . ')';
				} else {
					$sCondi = $sCondi . ' AND TB.saiu05idresponsable=' . $idTercero . '';
				}
				break;
		}
		$sSQL = '';
		for ($k = 1; $k <= $iTablas; $k++) {
			if ($k != 1) {
				$sSQL = $sSQL . ' UNION ';
			}
			$sContenedor = $aTablas[$k];
			$sSQL = $sSQL . 'SELECT TB.saiu05agno, TB.saiu05mes, TB.saiu05dia, TB.saiu05consec, TB.saiu05estado, 
			TB.saiu05hora, TB.saiu05minuto, T11.unad11tipodoc, T11.unad11doc, T11.unad11razonsocial AS nominteresado, 
			TB.saiu05idzona, TB.saiu05cead, TB.saiu05idescuela, TB.saiu05idprograma, TB.saiu05idcategoria, 
			TB.saiu05idtiposolorigen, TB.saiu05idtemaorigen, TB.saiu05idunidadresp, TB.saiu05idequiporesp, TB.saiu05idsupervisor, 
			TB.saiu05idresponsable, TB.saiu05evalfecha, TB.saiu05evalamabilidad, TB.saiu05evalamabmotivo, TB.saiu05evalrapidez,
			TB.saiu05evalrapidmotivo, TB.saiu05evalclaridad, TB.saiu05evalcalridmotivo, TB.saiu05evalresolvio, TB.saiu05evalsugerencias,
			TB.saiu05evalconocimiento,TB.saiu05evalconocmotivo,TB.saiu05evalutilidad,TB.saiu05evalutilmotivo,
			TB.saiu05fecharespdef,TB.saiu05horarespdef,TB.saiu05minrespdef,TB.saiu05numref,TB.saiu05detalle,
			TB.saiu05respuesta			
			FROM saiu05solicitud_' . $sContenedor . ' AS TB, unad11terceros AS T11
			WHERE TB.saiu05tiporadicado=1 AND TB.saiu05idsolicitante=T11.unad11id ' . $sCondi . '';
		}
		if ($sSQL != '') {
			$sSQL = $sSQL . ' ORDER BY saiu05agno DESC, saiu05mes DESC, saiu05consec DESC';
			$tabla = $objDB->ejecutasql($sSQL);
			if ($tabla == false) {
				if ($bDebug) {
					$objHoja->setCellValueByColumnAndRow(1, $iFila, $sSQL);
					$iFila++;
				}
			} else {
				$asaiu05estado = array();
				$asaiu05idtiposolorigen = array();
				$asaiu05idclaseser = array();
				$asaiu05idcategoria = array();
				$asaiu05idtemaorigen = array();
				$asaiu05idunidadresp = array();
				$asaiu05idequiporesp = array();
				$asaiu05idsupervisor = array();
				$asaiu05idresponsable = array();
				$asaiu05idzona = array();
				$asaiu05cead = array();
				$asaiu05idescuela = array();
				$asaiu05idprograma = array();
				$acalificacion = array('','Deficiente','Malo','Aceptable','Bueno','Excelente');
				while ($fila = $objDB->sf($tabla)) {
					$sNumSol = f3000_NumSolicitud($fila['saiu05agno'], $fila['saiu05mes'], $fila['saiu05consec']);
					$sNumRef = $fila['saiu05numref'];
					$sDetalle = $fila['saiu05detalle'];
					$sRespuesta = $fila['saiu05respuesta'];
					$saiu05dia = fecha_armar($fila['saiu05dia'], $fila['saiu05mes'], $fila['saiu05agno']);
					$saiu05hora = html_TablaHoraMin($fila['saiu05hora'], $fila['saiu05minuto']);
					$i_saiu05estado = $fila['saiu05estado'];
					$saiu05fecharespdef = fecha_desdenumero($fila['saiu05fecharespdef']);
					$saiu05horarespdef = html_TablaHoraMin($fila['saiu05horarespdef'], $fila['saiu05minrespdef']);
					$saiu05evalfecha = fecha_desdenumero($fila['saiu05evalfecha']);
					if (isset($asaiu05estado[$i_saiu05estado]) == 0) {
						$sSQL = 'SELECT saiu11nombre FROM saiu11estadosol WHERE saiu11id=' . $i_saiu05estado . '';
						$tablae = $objDB->ejecutasql($sSQL);
						if ($objDB->nf($tablae) > 0) {
							$filae = $objDB->sf($tablae);
							$asaiu05estado[$i_saiu05estado] = cadena_LimpiarTildes($filae['saiu11nombre']);
						} else {
							$asaiu05estado[$i_saiu05estado] = '';
						}
					}
					$saiu05estado = ($asaiu05estado[$i_saiu05estado]);
					$i_saiu05idtiposolorigen = $fila['saiu05idtiposolorigen'];
					if (isset($asaiu05idtiposolorigen[$i_saiu05idtiposolorigen]) == 0) {
						$sSQL = 'SELECT TB.saiu02titulo, T1.saiu01titulo
						FROM saiu02tiposol AS TB, saiu01claseser AS T1 
						WHERE TB.saiu02clasesol=T1.saiu01id AND saiu02id=' . $i_saiu05idtiposolorigen . '';
						$tablae = $objDB->ejecutasql($sSQL);
						if ($objDB->nf($tablae) > 0) {
							$filae = $objDB->sf($tablae);
							$asaiu05idtiposolorigen[$i_saiu05idtiposolorigen] = cadena_LimpiarTildes($filae['saiu02titulo']);
							$asaiu05idclaseser[$i_saiu05idtiposolorigen] = cadena_LimpiarTildes($filae['saiu01titulo']);
						} else {
							$asaiu05idtiposolorigen[$i_saiu05idtiposolorigen] = '';
							$asaiu05idclaseser[$i_saiu05idtiposolorigen] = '';
						}
					}
					$saiu05idtiposolorigen = ($asaiu05idtiposolorigen[$i_saiu05idtiposolorigen]);
					$saiu05idclaseser = ($asaiu05idclaseser[$i_saiu05idtiposolorigen]);
					$i_saiu05idcategoria = $fila['saiu05idcategoria'];
					if (isset($asaiu05idcategoria[$i_saiu05idcategoria]) == 0) {
						$sSQL = 'SELECT saiu68nombre FROM saiu68categoria WHERE saiu68id=' . $i_saiu05idcategoria . '';
						$tablae = $objDB->ejecutasql($sSQL);
						if ($objDB->nf($tablae) > 0) {
							$filae = $objDB->sf($tablae);
							$asaiu05idcategoria[$i_saiu05idcategoria] = cadena_LimpiarTildes($filae['saiu68nombre']);
						} else {
							$asaiu05idcategoria[$i_saiu05idcategoria] = '';
						}
					}
					$saiu05idcategoria = ($asaiu05idcategoria[$i_saiu05idcategoria]);
					$i_saiu05idtemaorigen = $fila['saiu05idtemaorigen'];
					if (isset($asaiu05idtemaorigen[$i_saiu05idtemaorigen]) == 0) {
						$sSQL = 'SELECT saiu03titulo FROM saiu03temasol WHERE saiu03id=' . $i_saiu05idtemaorigen . '';
						$tablae = $objDB->ejecutasql($sSQL);
						if ($objDB->nf($tablae) > 0) {
							$filae = $objDB->sf($tablae);
							$asaiu05idtemaorigen[$i_saiu05idtemaorigen] = cadena_LimpiarTildes($filae['saiu03titulo']);
						} else {
							$asaiu05idtemaorigen[$i_saiu05idtemaorigen] = '';
						}
					}
					$saiu05idtemaorigen = ($asaiu05idtemaorigen[$i_saiu05idtemaorigen]);
					$i_saiu05idunidadresp = $fila['saiu05idunidadresp'];
					if (isset($asaiu05idunidadresp[$i_saiu05idunidadresp]) == 0) {
						$sSQL = 'SELECT unae26nombre FROM unae26unidadesfun WHERE unae26id=' . $i_saiu05idunidadresp . '';
						$tablae = $objDB->ejecutasql($sSQL);
						if ($objDB->nf($tablae) > 0) {
							$filae = $objDB->sf($tablae);
							$asaiu05idunidadresp[$i_saiu05idunidadresp] = cadena_LimpiarTildes($filae['unae26nombre']);
						} else {
							$asaiu05idunidadresp[$i_saiu05idunidadresp] = '{Ninguna}';
						}
					}
					$saiu05idunidadresp = ($asaiu05idunidadresp[$i_saiu05idunidadresp]);
					$i_saiu05idequiporesp = $fila['saiu05idequiporesp'];
					if (isset($asaiu05idequiporesp[$i_saiu05idequiporesp]) == 0) {
						$sSQL = 'SELECT bita27nombre FROM bita27equipotrabajo WHERE bita27id=' . $i_saiu05idequiporesp . '';
						$tablae = $objDB->ejecutasql($sSQL);
						if ($objDB->nf($tablae) > 0) {
							$filae = $objDB->sf($tablae);
							$asaiu05idequiporesp[$i_saiu05idequiporesp] = cadena_LimpiarTildes($filae['bita27nombre']);
						} else {
							$asaiu05idequiporesp[$i_saiu05idequiporesp] = '{Ninguno}';
						}
					}
					$saiu05idequiporesp = ($asaiu05idequiporesp[$i_saiu05idequiporesp]);
					$i_saiu05idsupervisor = $fila['saiu05idsupervisor'];
					if (isset($asaiu05idsupervisor[$i_saiu05idsupervisor]) == 0) {
						$sSQL = 'SELECT unad11razonsocial FROM unad11terceros WHERE unad11id=' . $i_saiu05idsupervisor . '';
						$tablae = $objDB->ejecutasql($sSQL);
						if ($objDB->nf($tablae) > 0) {
							$filae = $objDB->sf($tablae);
							$asaiu05idsupervisor[$i_saiu05idsupervisor] = cadena_LimpiarTildes($filae['unad11razonsocial']);
							//$asaiu05idsupervisor[$i_saiu05idsupervisor] = cadena_decodificar($filae['unad11razonsocial']);
						} else {
							$asaiu05idsupervisor[$i_saiu05idsupervisor] = '{Ninguno}';
						}
					}
					$saiu05idsupervisor = ($asaiu05idsupervisor[$i_saiu05idsupervisor]);
					$i_saiu05idresponsable = $fila['saiu05idresponsable'];
					if (isset($asaiu05idresponsable[$i_saiu05idresponsable]) == 0) {
						$sSQL = 'SELECT unad11razonsocial FROM unad11terceros WHERE unad11id=' . $i_saiu05idresponsable . '';
						$tablae = $objDB->ejecutasql($sSQL);
						if ($objDB->nf($tablae) > 0) {
							$filae = $objDB->sf($tablae);
							$asaiu05idresponsable[$i_saiu05idresponsable] = cadena_LimpiarTildes($filae['unad11razonsocial']);
							//$asaiu05idresponsable[$i_saiu05idresponsable] = cadena_decodificar($filae['unad11razonsocial']);
						} else {
							$asaiu05idresponsable[$i_saiu05idresponsable] = '{Ninguno}';
						}
					}
					$saiu05idresponsable = ($asaiu05idresponsable[$i_saiu05idresponsable]);
					$sDoc = mb_convert_encoding($fila['unad11tipodoc'] . $fila['unad11doc'], 'ISO-8859-1', 'UTF-8');
					$sRazonSocial = mb_convert_encoding($fila['nominteresado'], 'ISO-8859-1', 'UTF-8');
					$i_saiu05idzona = $fila['saiu05idzona'];
					if (isset($asaiu05idzona[$i_saiu05idzona]) == 0) {
						$sSQL = 'SELECT unad23nombre FROM unad23zona WHERE unad23id=' . $i_saiu05idzona . '';
						$tablae = $objDB->ejecutasql($sSQL);
						if ($objDB->nf($tablae) > 0) {
							$filae = $objDB->sf($tablae);
							$asaiu05idzona[$i_saiu05idzona] = cadena_LimpiarTildes($filae['unad23nombre']);
						} else {
							$asaiu05idzona[$i_saiu05idzona] = '{Ninguna}';
						}
					}
					$saiu05idzona = ($asaiu05idzona[$i_saiu05idzona]);
					$i_saiu05cead = $fila['saiu05cead'];
					if (isset($asaiu05cead[$i_saiu05cead]) == 0) {
						$sSQL = 'SELECT unad24nombre FROM unad24sede WHERE unad24id=' . $i_saiu05cead . '';
						$tablae = $objDB->ejecutasql($sSQL);
						if ($objDB->nf($tablae) > 0) {
							$filae = $objDB->sf($tablae);
							$asaiu05cead[$i_saiu05cead] = cadena_LimpiarTildes($filae['unad24nombre']);
						} else {
							$asaiu05cead[$i_saiu05cead] = '{Ninguna}';
						}
					}
					$saiu05cead = ($asaiu05cead[$i_saiu05cead]);
					$i_saiu05idescuela = $fila['saiu05idescuela'];
					if (isset($asaiu05idescuela[$i_saiu05idescuela]) == 0) {
						$sSQL = 'SELECT core12nombre FROM core12escuela WHERE core12id=' . $i_saiu05idescuela . '';
						$tablae = $objDB->ejecutasql($sSQL);
						if ($objDB->nf($tablae) > 0) {
							$filae = $objDB->sf($tablae);
							$asaiu05idescuela[$i_saiu05idescuela] = cadena_LimpiarTildes($filae['core12nombre']);
						} else {
							$asaiu05idescuela[$i_saiu05idescuela] = '{Ninguna}';
						}
					}
					$saiu05idescuela = ($asaiu05idescuela[$i_saiu05idescuela]);
					$i_saiu05idprograma = $fila['saiu05idprograma'];
					if (isset($asaiu05idprograma[$i_saiu05idprograma]) == 0) {
						$sSQL = 'SELECT core09nombre FROM core09programa WHERE core09id=' . $i_saiu05idprograma . '';
						$tablae = $objDB->ejecutasql($sSQL);
						if ($objDB->nf($tablae) > 0) {
							$filae = $objDB->sf($tablae);
							$asaiu05idprograma[$i_saiu05idprograma] = cadena_LimpiarTildes($filae['core09nombre']);
						} else {
							$asaiu05idprograma[$i_saiu05idprograma] = '{Ninguno}';
						}
					}
					$saiu05idprograma = ($asaiu05idprograma[$i_saiu05idprograma]);
					// $saiu05idprograma=mb_convert_encoding($fila['programa'], 'ISO-8859-1', 'UTF-8');
					$objHoja->setCellValueByColumnAndRow(0, $iFila, $sNumSol);
					$objHoja->setCellValueByColumnAndRow(1, $iFila, $sNumRef);
					$objHoja->setCellValueByColumnAndRow(2, $iFila, $saiu05dia);
					$objHoja->setCellValueByColumnAndRow(3, $iFila, $saiu05hora);
					$objHoja->setCellValueByColumnAndRow(4, $iFila, cadena_decodificar($saiu05estado));
					$objHoja->setCellValueByColumnAndRow(5, $iFila, $saiu05fecharespdef);
					$objHoja->setCellValueByColumnAndRow(6, $iFila, $saiu05horarespdef);
					$objHoja->setCellValueByColumnAndRow(7, $iFila, cadena_decodificar($saiu05idcategoria));
					$objHoja->setCellValueByColumnAndRow(8, $iFila, cadena_decodificar($saiu05idtiposolorigen));
					$objHoja->setCellValueByColumnAndRow(9, $iFila, cadena_decodificar($saiu05idclaseser));
					$objHoja->setCellValueByColumnAndRow(10, $iFila, cadena_decodificar($saiu05idtemaorigen));
					$objHoja->setCellValueByColumnAndRow(11, $iFila, cadena_decodificar($saiu05idunidadresp));
					$objHoja->setCellValueByColumnAndRow(12, $iFila, cadena_decodificar($saiu05idequiporesp));
					$objHoja->setCellValueByColumnAndRow(13, $iFila, $saiu05idsupervisor);
					$objHoja->setCellValueByColumnAndRow(14, $iFila, $saiu05idresponsable);
					$objHoja->setCellValueByColumnAndRow(15, $iFila, $sDoc);
					$objHoja->setCellValueByColumnAndRow(16, $iFila, $sRazonSocial);
					$objHoja->setCellValueByColumnAndRow(17, $iFila, $saiu05idzona);
					$objHoja->setCellValueByColumnAndRow(18, $iFila, $saiu05cead);
					$objHoja->setCellValueByColumnAndRow(19, $iFila, $saiu05idescuela);
					$objHoja->setCellValueByColumnAndRow(20, $iFila, $saiu05idprograma);
					$iFila++;
					if ($i_saiu05estado == 7) {
						$objPHPExcel->setActiveSheetIndex(1);
						$objHoja = $objPHPExcel->getActiveSheet();
						$objHoja->setCellValueByColumnAndRow(0, $iFilaHoja2, $sNumSol);
						$objHoja->setCellValueByColumnAndRow(1, $iFilaHoja2, $sDoc);
						$objHoja->setCellValueByColumnAndRow(2, $iFilaHoja2, $sRazonSocial);
						$objHoja->setCellValueByColumnAndRow(3, $iFilaHoja2, cadena_decodificar($saiu05idcategoria));
						$objHoja->setCellValueByColumnAndRow(4, $iFilaHoja2, $saiu05evalfecha);
						$objHoja->setCellValueByColumnAndRow(5, $iFilaHoja2, $acalificacion[$fila['saiu05evalamabilidad']]);
						$objHoja->setCellValueByColumnAndRow(6, $iFilaHoja2, cadena_decodificar($fila['saiu05evalamabmotivo']));
						$objHoja->setCellValueByColumnAndRow(7, $iFilaHoja2, $acalificacion[$fila['saiu05evalrapidez']]);
						$objHoja->setCellValueByColumnAndRow(8, $iFilaHoja2, cadena_decodificar($fila['saiu05evalrapidmotivo']));
						$objHoja->setCellValueByColumnAndRow(9, $iFilaHoja2, $acalificacion[$fila['saiu05evalclaridad']]);
						$objHoja->setCellValueByColumnAndRow(10, $iFilaHoja2, cadena_decodificar($fila['saiu05evalcalridmotivo']));
						$objHoja->setCellValueByColumnAndRow(11, $iFilaHoja2, $acalificacion[$fila['saiu05evalresolvio']]);
						$objHoja->setCellValueByColumnAndRow(12, $iFilaHoja2, cadena_decodificar($fila['saiu05evalsugerencias']));
						$objHoja->setCellValueByColumnAndRow(13, $iFilaHoja2, $acalificacion[$fila['saiu05evalconocimiento']]);
						$objHoja->setCellValueByColumnAndRow(14, $iFilaHoja2, cadena_decodificar($fila['saiu05evalconocmotivo']));
						$objHoja->setCellValueByColumnAndRow(15, $iFilaHoja2, $acalificacion[$fila['saiu05evalutilidad']]);
						$objHoja->setCellValueByColumnAndRow(16, $iFilaHoja2, cadena_decodificar($fila['saiu05evalutilmotivo']));
						$iFilaHoja2++;
						$objPHPExcel->setActiveSheetIndex(0);
						$objHoja = $objPHPExcel->getActiveSheet();
					}
					$objPHPExcel->setActiveSheetIndex(2);
					$objHoja = $objPHPExcel->getActiveSheet();
					$objHoja->setCellValueByColumnAndRow(0, $iFilaHoja3, $sNumRef);
					$objHoja->getStyleByColumnAndRow(0, $iFilaHoja3)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$objHoja->getColumnDimensionByColumn(0)->setWidth(20);
					$objHoja->setCellValueByColumnAndRow(1, $iFilaHoja3, $sDetalle);
					$objHoja->getStyleByColumnAndRow(1, $iFilaHoja3)->getAlignment()->setWrapText(true);
					$objHoja->getStyleByColumnAndRow(1, $iFilaHoja3)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$objHoja->getColumnDimensionByColumn(1)->setWidth(90);
					$objHoja->setCellValueByColumnAndRow(2, $iFilaHoja3, $sRespuesta);
					$objHoja->getStyleByColumnAndRow(2, $iFilaHoja3)->getAlignment()->setWrapText(true);
					$objHoja->getStyleByColumnAndRow(2, $iFilaHoja3)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$objHoja->getColumnDimensionByColumn(2)->setWidth(90);
					$iFilaHoja3++;
					$objPHPExcel->setActiveSheetIndex(0);
					$objHoja = $objPHPExcel->getActiveSheet();
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
		PHPExcel_RellenarCeldas($objPHPExcel, 'A' . $iFilaBase2 . ':' . $sColTope2 . $iFilaHoja2, 'Bl', true);
		$objPHPExcel->setActiveSheetIndex(2);
		$objHoja = $objPHPExcel->getActiveSheet();
		PHPExcel_RellenarCeldas($objPHPExcel, 'A' . $iFilaBase3 . ':' . $sColTope3 . $iFilaHoja3, 'Bl', true);
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
