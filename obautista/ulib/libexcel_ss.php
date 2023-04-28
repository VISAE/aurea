<?php
function columna_Letra($col)
{
	$Letra = '';
	$Letra = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col + 1);
	return $Letra;
}
function columna_Number($col)
{
	$i = 0;
	$i = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($col);
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
function MaxColumnas($objHoja)
{
	//Para revisar
	$sMaxColum = $objHoja->setActiveSheetIndex(0)->getHighestColumn();
	return $sMaxColum;
}
function MaxFilas($objHoja)
{
	//Para revisar
	$sMaxFilas = $objHoja->setActiveSheetIndex(0)->getHighestRow();
	return $sMaxFilas;
}
function MaxRango($objHoja)
{
	//Para revisar
	$sMax = $objHoja->setActiveSheetIndex(0)->calculateWorksheetDimension();
	return $sMax;
}

function PHPExcel_Agrega_Dibujo($objHoja, $setName, $setDescription, $setPath, $setHeight, $Coordenada = 'A1', $OffSet = '0', $bSombra = false, $SomDireccion = '0')
{
	//Para revisar
	$objDrawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
	$objDrawing->setName($setName);
	$objDrawing->setDescription($setDescription);
	$objDrawing->setPath($setPath);
	$objDrawing->setHeight($setHeight);
	$objDrawing->setCoordinates($Coordenada);
	$objDrawing->setOffsetX($OffSet);
	$objDrawing->getShadow()->setVisible($bSombra);
	$objDrawing->getShadow()->setDirection($SomDireccion);
	$objDrawing->setWorksheet($objHoja);
}
function PHPExcel_Ajustar_Texto_Columna($objHoja, $columna)
{
	//Para revisar
	$objHoja->getColumnDimension($columna)->setAutoSize(true);
}
function PHPExcel_Ajustar_Texto_Toda_Columna($objHoja)
{
	//Para revisar
	foreach (range('A', $objHoja->getHighestDataColumn()) as $col) {
		$objHoja->getColumnDimension($col)->setAutoSize(true);
	}
}
function PHPExcel_Alinear_Celda_Derecha($objHoja, $Celda)
{
	$objHoja->getStyle($Celda)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
}
function PHPExcel_Alinear_Celda_Izquierda($objHoja, $Celda)
{
	$objHoja->getStyle($Celda)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
}
function PHPExcel_Contorno_Rango($objHoja, $Rango, $Color = 'Ro', $Grosor = '1')
{
	//Para revisar
	$Color = fColor($Color);
	if ($Grosor == '1') {
		$stylekBorderOutline = array(
			'borders' => array(
				'outline' => array(
					'style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
					'color' => array('argb' => $Color),
				),
			),
		);
	}
	if ($Grosor == '2') {
		$stylekBorderOutline = array(
			'borders' => array(
				'outline' => array(
					'style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
					'color' => array('argb' => $Color),
				),
			),
		);
	}
	$objHoja->getStyle($Rango)->applyFromArray($stylekBorderOutline);
}
function PHPExcel_Contorno_Tabla($objHoja, $ColumnaIni, $filaIni, $Color = 'Ro', $Grosor = '1', $bEncabezado = false, $bRellenar = false, $ColorRelleno = 'Gr', $blineas)
{
	//Para revisar
	$sMaxColum = $objHoja->getHighestColumn();
	$sMaxFilas = $objHoja->getHighestRow();
	$iColIni = columna_Number($ColumnaIni);
	$highestColumn = $objHoja->getHighestColumn();
	$highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);
	if ($bEncabezado) {
		$Contorno = $ColumnaIni . $filaIni . ':' . $highestColumn . $filaIni;
		PHPExcel_Contorno_Rango($objHoja, $Contorno, $Color, $Grosor);
	}
	if ($bRellenar) {
		$Contorno = $ColumnaIni . $filaIni . ':' . $highestColumn . $sMaxFilas;
		PHPExcel_RellenarCeldas($objHoja, $Contorno, $ColorRelleno, $blineas);
	}
	for ($col = $iColIni; $col < $highestColumnIndex; ++$col) {
		$Columna = columna_Letra($col);
		$Contorno = $Columna . $filaIni . ':' . $Columna . $sMaxFilas;
		PHPExcel_Contorno_Rango($objHoja, $Contorno, $Color, $Grosor);
	}
}
//Escribir en una celda
function PHPEXCEL_Escribir($objHoja, $iColumna, $iFila, $sValor) {
	$objHoja->setCellValue([$iColumna + 1, $iFila], $sValor);
}

function PHPExcel_Formatear_Celdas_Numero($objHoja, $Rango)
{
	//Para revisar
	$objHoja->getStyle($Rango)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_EUR_SIMPLE);
}
function PHPExcel_Formato_Fuente_Celda($objHoja, $Celda, $Size, $NombreFuente = 'Candara', $ColorFuente = 'FF000000', $bBold = false, $bItalic = false, $bUnder = false, $blineas = false)
{
	//Para revisar
	if ($blineas) {
		$objHoja->getDefaultStyle()->getBorders($Celda)->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
	}
	$objHoja->getStyle($Celda)->getFont()->setName($NombreFuente);
	$objHoja->getStyle($Celda)->getFont()->setSize($Size);
	$objHoja->getStyle($Celda)->getFont()->setBold($bBold);
	$objHoja->getStyle($Celda)->getFont()->setItalic($bItalic);
	if ($bUnder) {
		$objHoja->getStyle($Celda)->getFont()->setUnderline(PHPExcel_Style_Font::UNDERLINE_SINGLE);
	}
	$ColorFuente = fColor($ColorFuente);
	$objHoja->getStyle($Celda)->getFont()->getColor()->setARGB($ColorFuente);
}
function PHPExcel_Justificar_Celda_HorizontalCentro($objHoja, $Celda)
{
	$objHoja->getStyle($Celda)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
}
function PHPExcel_Justificar_Celda_VerticalCentro($objHoja, $Celda)
{
	$objHoja->getStyle($Celda)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
}
function PHPExcel_Mexclar_Celdas($objHoja, $Rango)
{
	$objHoja->mergeCells($Rango);
}
function PHPExcel_Reducir_para_encajar($objHoja, $Celda)
{
	//Para revisar
	$objHoja->getStyle($Celda)->getAlignment()->setShrinkToFit(true);
}
function PHPExcel_RellenarCeldas($objHoja, $Rango, $Color = 'Gr', $blineas = false)
{
	$Color = fColor($Color);
	if ($blineas) {
		$objHoja->getStyle($Rango)->getBorders($Rango)->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
		$objHoja->getStyle($Rango)->getBorders($Rango)->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
	}
	$objHoja->getStyle($Rango)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
	$objHoja->getStyle($Rango)->getFill()->getStartColor()->setARGB($Color);
}
function PHPExcel_Texto_Tres_Partes($objHoja, $Celda, $TextIni = '', $TextMed = '', $TextFin = '', $TextMedColor = 'Ve', $bBold = false, $bItalic = false, $Size = '12', $Name = 'Calibri', $TextFinColor = 'Ne')
{
	$objHoja->getStyle($Celda)->getFont()->setSize($Size);
	$TextMedColor = fColor($TextMedColor);
	$TextFinColor = fColor($TextFinColor);
	$objRichText = new \PhpOffice\PhpSpreadsheet\RichText\RichText();
	$objRichText->createText($TextIni);
	$objPayable = $objRichText->createTextRun($TextMed);
	$objPayable->getFont()->setBold($bBold);
	$objPayable->getFont()->setItalic($bItalic);
	$objPayable->getFont()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color($TextMedColor));
	$objPayable->getFont()->setName($Name);
	$objPayable->getFont()->setSize($Size);
	$objPayable2 = $objRichText->createTextRun($TextFin);
	$objPayable2->getFont()->setBold($bBold);
	$objPayable2->getFont()->setItalic($bItalic);
	$objPayable2->getFont()->setName($Name);
	$objPayable2->getFont()->setSize($Size);
	$objPayable2->getFont()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color($TextFinColor));
	$objHoja->getCell($Celda)->setValue($objRichText);
}

function RetCabezaTablaUnad($objHoja, $iColDato, $iFilDato, $sTitulos)
{
	//Para revisar
	$aTitulos = explode(',', $sTitulos);
	for ($k = 1; $k <= count($aTitulos); $k++) {
		$sTitulo = $aTitulos[$k - 1];
		if ($sTitulo != '') {
			$Celda1 = RetCelda($iColDato, $iFilDato - 1);
			$Celda = RetCelda($iColDato, $iFilDato);
			$Celda2 = RetCelda($iColDato, $iFilDato + 1);
			$objHoja->setCellValue($Celda, $sTitulo);
			if (($iColDato % 2) == 0) {
				PHPExcel_RellenarCeldas($objHoja, $Celda1 . ':' . $Celda2, 'AzUn', false);
			} else {
				PHPExcel_RellenarCeldas($objHoja, $Celda1 . ':' . $Celda2, 'AmUn', false);
			}
			PHPExcel_Formato_Fuente_Celda($objHoja, $Celda, '14', 'Yu Gothic', 'Bl', true, false, false);
			PHPExcel_Contorno_Rango($objHoja, $Celda1 . ':' . $Celda2, 'Ne', '1');
			$columna = columna_Letra($iColDato);
			PHPExcel_Ajustar_Texto_Columna($objHoja, $columna);
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
function RetLineaTablaUnad($objHoja, $iColDato, $iFilDato, $sDatos, $ColorFuente = 'AzOsUn', $NombreFuente = 'Yu Gothic', $Size = '12', $blineas = true)
{
	//Para revisar
	$CeldaIni = RetCelda($iColDato, $iFilDato);
	$ColorFuente = fColor($ColorFuente);
	$aTitulos = explode(',', $sDatos);

	for ($k = 1; $k <= count($aTitulos); $k++) {
		$sTitulo = $aTitulos[$k - 1];
		if ($sTitulo != '') {
			$Celda = RetCelda($iColDato, $iFilDato);
			$objHoja->getCell($Celda)->setValue($sTitulo);
			$iColDato = $iColDato + 1;
		}
	}
	$Celda2 = RetCelda($iColDato - 1, $iFilDato);
	$objHoja->getStyle($CeldaIni . ':' . $Celda2)->getFont()->getColor()->setARGB($ColorFuente);
	$objHoja->getStyle($CeldaIni . ':' . $Celda2)->getFont()->setName($NombreFuente);
	$objHoja->getStyle($CeldaIni . ':' . $Celda2)->getFont()->setSize($Size);
	if ($blineas) {
		$objHoja->getDefaultStyle()->getBorders($Celda)->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
	}
}
