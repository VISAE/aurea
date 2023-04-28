<?php
/*
--- Modelo Version 1.0.0 jueves, 28 de mayo de 2020
--- Saúl Alexander Hernández  - UNAD  2020
--- saul.hernandez@unad.edu.co
*/
function columna_Letra($col)
{
	$Letra = '';
	$Letra = PHPExcel_Cell::stringFromColumnIndex($col);
	return $Letra;
}
function columna_Number($col)
{
	$i = 0;
	$i = PHPExcel_Cell::columnIndexFromString($col);
	return $i - 1;
}
function fColor($Color)
{
	$res = 'Ne';
	switch ($Color) {
		case 'Ne':
			$res = 'FF000000';
			break;
		case 'Az':
			$res = 'FF0000FF';
			break;
		case 'Ve':
			$res = 'FF00FF00';
			break;
		case 'Ro':
			$res = 'FFFF0000';
			break;
		case 'Ma':
			$res = 'FF993300';
			break;
		case 'Bl':
			$res = 'FFFFFFFF';
			break;
		case 'Gr':
			$res = 'eaecee';
			break;
		case 'Am':
			$res = 'FFFFFF00';
			break;
		case 'VeOs':
			$res = 'FF008000';
			break;
		case 'RoOs':
			$res = 'FF800000';
			break;
		case 'AmOs':
			$res = 'FFF08000';
			break;
		case 'GrOs':
			$res = 'FF808080';
			break;
		case 'AzOs':
			$res = 'FF000080';
			break;

		case 'AmUn':
			$res = 'FFC000';
			break;
		case 'AmOsUn':
			$res = 'FB9800';
			break;
		case 'AzUn':
			$res = '28AFDF';
			break;
		case 'AzOsUn':
			$res = '005D93';
			break;
		default:
			//Verificar qeu sea un exagesimal
			$sVer = cadena_limpiar($Color, '0123456789ABCDEF');
			if ($sVer == $Color) {
				$res = $Color;
			}
			break;
	}
	return $res;
}
function MaxColumnas($objPHPExcel)
{
	$sMaxColum = $objPHPExcel->setActiveSheetIndex(0)->getHighestColumn();
	return $sMaxColum;
}
function MaxFilas($objPHPExcel)
{
	$sMaxFilas = $objPHPExcel->setActiveSheetIndex(0)->getHighestRow();
	return $sMaxFilas;
}
function MaxRango($objPHPExcel)
{
	$sMax = $objPHPExcel->setActiveSheetIndex(0)->calculateWorksheetDimension();
	return $sMax;
}

function PHPExcel_Agrega_Dibujo($objPHPExcel, $setName, $setDescription, $setPath, $setHeight, $Coordenada = 'A1', $OffSet = '0', $bSombra = false, $SomDireccion = '0')
{
	$objDrawing = new PHPExcel_Worksheet_Drawing();
	$objDrawing->setName($setName);
	$objDrawing->setDescription($setDescription);
	$objDrawing->setPath($setPath);
	$objDrawing->setHeight($setHeight);
	$objDrawing->setCoordinates($Coordenada);
	$objDrawing->setOffsetX($OffSet);
	$objDrawing->getShadow()->setVisible($bSombra);
	$objDrawing->getShadow()->setDirection($SomDireccion);
	$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
}
function PHPExcel_Ajustar_Texto_Columna($objPHPExcel, $columna)
{
	$objPHPExcel->getActiveSheet()->getColumnDimension($columna)->setAutoSize(true);
}
function PHPExcel_Ajustar_Texto_Toda_Columna($objPHPExcel)
{
	foreach (range('A', $objPHPExcel->getActiveSheet()->getHighestDataColumn()) as $col) {
		$objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
	}
}
function PHPExcel_Alinear_Celda_Derecha($objPHPExcel, $Celda)
{
	$objPHPExcel->getActiveSheet()->getStyle($Celda)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
}
function PHPExcel_Alinear_Celda_Izquierda($objPHPExcel, $Celda)
{
	$objPHPExcel->getActiveSheet()->getStyle($Celda)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
}
function PHPExcel_Contorno_Rango($objPHPExcel, $Rango, $Color = 'Ro', $Grosor = '1')
{
	$Color = fColor($Color);
	if ($Grosor == '1') {
		$stylekBorderOutline = array(
			'borders' => array(
				'outline' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN,
					'color' => array('argb' => $Color),
				),
			),
		);
	}
	if ($Grosor == '2') {
		$stylekBorderOutline = array(
			'borders' => array(
				'outline' => array(
					'style' => PHPExcel_Style_Border::BORDER_THICK,
					'color' => array('argb' => $Color),
				),
			),
		);
	}
	$objPHPExcel->getActiveSheet()->getStyle($Rango)->applyFromArray($stylekBorderOutline);
}
function PHPExcel_Contorno_Tabla($objPHPExcel, $ColumnaIni, $filaIni, $Color = 'Ro', $Grosor = '1', $bEncabezado = false, $bRellenar = false, $ColorRelleno = 'Gr', $blineas)
{
	$sMaxColum = $objPHPExcel->setActiveSheetIndex(0)->getHighestColumn();
	$sMaxFilas = $objPHPExcel->setActiveSheetIndex(0)->getHighestRow();
	$iColIni = columna_Number($ColumnaIni);
	$highestColumn = $objPHPExcel->setActiveSheetIndex(0)->getHighestColumn();
	$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
	if ($bEncabezado) {
		$Contorno = $ColumnaIni . $filaIni . ':' . $highestColumn . $filaIni;
		PHPExcel_Contorno_Rango($objPHPExcel, $Contorno, $Color, $Grosor);
	}
	if ($bRellenar) {
		$Contorno = $ColumnaIni . $filaIni . ':' . $highestColumn . $sMaxFilas;
		PHPExcel_RellenarCeldas($objPHPExcel, $Contorno, $ColorRelleno, $blineas);
	}
	for ($col = $iColIni; $col < $highestColumnIndex; ++$col) {
		$Columna = columna_Letra($col);
		$Contorno = $Columna . $filaIni . ':' . $Columna . $sMaxFilas;
		PHPExcel_Contorno_Rango($objPHPExcel, $Contorno, $Color, $Grosor);
	}
}

function PHPEXCEL_Escribir($objHoja, $iColumna, $iFila, $sValor)
{
	$objHoja->setCellValueByColumnAndRow($iColumna, $iFila, $sValor);
}

function PHPExcel_Formatear_Celdas_Numero($objPHPExcel, $Rango)
{
	$objPHPExcel->getActiveSheet()->getStyle($Rango)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_EUR_SIMPLE);
}
function PHPExcel_Formato_Fuente_Celda($objPHPExcel, $Celda, $Size, $NombreFuente = 'Candara', $ColorFuente = 'FF000000', $bBold = false, $bItalic = false, $bUnder = false, $blineas = false)
{
	if ($blineas) {
		$objPHPExcel->getDefaultStyle()->getBorders($Celda)->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	}
	$objPHPExcel->getActiveSheet()->getStyle($Celda)->getFont()->setName($NombreFuente);
	$objPHPExcel->getActiveSheet()->getStyle($Celda)->getFont()->setSize($Size);
	$objPHPExcel->getActiveSheet()->getStyle($Celda)->getFont()->setBold($bBold);
	$objPHPExcel->getActiveSheet()->getStyle($Celda)->getFont()->setItalic($bItalic);
	if ($bUnder) {
		$objPHPExcel->getActiveSheet()->getStyle($Celda)->getFont()->setUnderline(PHPExcel_Style_Font::UNDERLINE_SINGLE);
	}
	$ColorFuente = fColor($ColorFuente);
	$objPHPExcel->getActiveSheet()->getStyle($Celda)->getFont()->getColor()->setARGB($ColorFuente);
}
function PHPExcel_Justificar_Celda_HorizontalCentro($objPHPExcel, $Celda)
{
	$objPHPExcel->getActiveSheet()->getStyle($Celda)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
}
function PHPExcel_Justificar_Celda_VerticalCentro($objPHPExcel, $Celda)
{
	$objPHPExcel->getActiveSheet()->getStyle($Celda)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
}
function PHPExcel_Mexclar_Celdas($objPHPExcel, $Rango)
{
	$objPHPExcel->getActiveSheet()->mergeCells($Rango);
}
function PHPExcel_Reducir_para_encajar($objPHPExcel, $Celda)
{
	$objPHPExcel->getActiveSheet()->getStyle($Celda)->getAlignment()->setShrinkToFit(true);
}
function PHPExcel_RellenarCeldas($objPHPExcel, $Rango, $Color = 'Gr', $blineas = true)
{
	$Color = fColor($Color);
	if ($blineas) {
		$objPHPExcel->getDefaultStyle()->getBorders($Rango)->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	}
	$objPHPExcel->getActiveSheet()->getStyle($Rango)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
	$objPHPExcel->getActiveSheet()->getStyle($Rango)->getFill()->getStartColor()->setARGB($Color);
}
function PHPExcel_Texto_Tres_Partes($objPHPExcel, $Celda, $TextIni = '', $TextMed = '', $TextFin = '', $TextMedColor = 'Ve', $bBold = false, $bItalic = false, $Size = '12', $Name = 'Calibri', $TextFinColor = 'Ne')
{
	$objPHPExcel->getActiveSheet()->getStyle($Celda)->getFont()->setSize($Size);
	$TextMedColor = fColor($TextMedColor);
	$TextFinColor = fColor($TextFinColor);
	$objRichText = new PHPExcel_RichText();
	$objRichText->createText($TextIni);
	$objPayable = $objRichText->createTextRun($TextMed);
	$objPayable->getFont()->setBold($bBold);
	$objPayable->getFont()->setItalic($bItalic);
	$objPayable->getFont()->setColor(new PHPExcel_Style_Color($TextMedColor));
	$objPayable->getFont()->setName($Name);
	$objPayable->getFont()->setSize($Size);
	$objPayable2 = $objRichText->createTextRun($TextFin);
	$objPayable2->getFont()->setBold($bBold);
	$objPayable2->getFont()->setItalic($bItalic);
	$objPayable2->getFont()->setName($Name);
	$objPayable2->getFont()->setSize($Size);
	$objPayable2->getFont()->setColor(new PHPExcel_Style_Color($TextFinColor));
	$objPHPExcel->getActiveSheet()->getCell($Celda)->setValue($objRichText);
}

function RetCabezaTablaUnad($objPHPExcel, $iColDato, $iFilDato, $sTitulos)
{
	$objHoja = $objPHPExcel->getActiveSheet();
	$aTitulos = explode(',', $sTitulos);
	for ($k = 1; $k <= count($aTitulos); $k++) {
		$sTitulo = $aTitulos[$k - 1];
		if ($sTitulo != '') {
			$Celda1 = RetCelda($iColDato, $iFilDato - 1);
			$Celda = RetCelda($iColDato, $iFilDato);
			$Celda2 = RetCelda($iColDato, $iFilDato + 1);
			$objHoja->setCellValue($Celda, $sTitulo);
			if (($iColDato % 2) == 0) {
				PHPExcel_RellenarCeldas($objPHPExcel, $Celda1 . ':' . $Celda2, 'AzUn', false);
			} else {
				PHPExcel_RellenarCeldas($objPHPExcel, $Celda1 . ':' . $Celda2, 'AmUn', false);
			}
			PHPExcel_Formato_Fuente_Celda($objPHPExcel, $Celda, '14', 'Yu Gothic', 'Bl', true, false, false);
			PHPExcel_Contorno_Rango($objPHPExcel, $Celda1 . ':' . $Celda2, 'Ne', '1');
			$columna = columna_Letra($iColDato);
			PHPExcel_Ajustar_Texto_Columna($objPHPExcel, $columna);
			$iColDato = $iColDato + 1;
		}
	}
}
function RetCelda($iCol, $ifila)
{
	$Cell = '';
	$sCol = columna_Letra($iCol);
	$Cell = $sCol . $ifila;
	return $Cell;
}
function RetLineaTablaUnad($objPHPExcel, $iColDato, $iFilDato, $sDatos, $ColorFuente = 'AzOsUn', $NombreFuente = 'Yu Gothic', $Size = '12', $blineas = true)
{
	$objHoja = $objPHPExcel->getActiveSheet();
	$CeldaIni = RetCelda($iColDato, $iFilDato);
	$ColorFuente = fColor($ColorFuente);
	$aTitulos = explode(',', $sDatos);

	for ($k = 1; $k <= count($aTitulos); $k++) {
		$sTitulo = $aTitulos[$k - 1];
		if ($sTitulo != '') {
			$Celda = RetCelda($iColDato, $iFilDato);
			$objPHPExcel->getActiveSheet()->getCell($Celda)->setValue($sTitulo);
			$iColDato = $iColDato + 1;
		}
	}
	$Celda2 = RetCelda($iColDato - 1, $iFilDato);
	$objPHPExcel->getActiveSheet()->getStyle($CeldaIni . ':' . $Celda2)->getFont()->getColor()->setARGB($ColorFuente);
	$objPHPExcel->getActiveSheet()->getStyle($CeldaIni . ':' . $Celda2)->getFont()->setName($NombreFuente);
	$objPHPExcel->getActiveSheet()->getStyle($CeldaIni . ':' . $Celda2)->getFont()->setSize($Size);
	if ($blineas) {
		$objPHPExcel->getDefaultStyle()->getBorders($Celda)->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	}
}
