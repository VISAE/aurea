<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2019 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Version 2.23.2 viernes, 14 de junio de 2019
*/
error_reporting(E_ALL);
ini_set("display_errors", 1);
if (!file_exists('./app.php')){
	echo '<b>Error N 1 de instalaci&oacute;n</b><br>No se ha establecido un archivo de configuraci&oacute;n, por favor comuniquese con el administrador del sistema.';
	die();
	}
mb_internal_encoding('UTF-8');
require './app.php';
require $APP->rutacomun.'unad_todas.php';
require $APP->rutacomun.'libs/clsdbadmin.php';
require $APP->rutacomun.'unad_librerias.php';
//require $APP->rutacomun.'tcpdf/config/lang/spa.php';
require $APP->rutacomun.'tcpdf/tcpdf.php';
function pdfReporteV2($iReporte, $PARAMS, $iFormato, $sNumCopiaReporte, $bCodificarUTF8, $objDB, $bDebug=false){
	$sError='';
	$idEgresado=$PARAMS['id2706'];
	$nombre='Pepito Perez';
	$id_estudiante='54321';
	$programa='Libertad';
	$centro='Pantano de vargaz';
	$fecha='20 de julio de 1810';
	$solicitud='Solicitud';
	$sSQL='SELECT TB.core01idtercero, T11.unad11doc, T11.unad11razonsocial, T24.unad24nombre, TB.core01gradofecha, T9.core09nombre 
FROM core01estprograma AS TB, unad11terceros AS T11, unad24sede AS T24, core09programa AS T9 
WHERE TB.core01id='.$idEgresado.' AND TB.core01idtercero=T11.unad11id AND TB.core011idcead=T24.unad24id AND TB.core01idprograma=T9.core09id ';
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$id_estudiante=$fila['unad11doc'];
		$nombre=$fila['unad11razonsocial'];
		$centro=$fila['unad24nombre'];
		$fecha=formato_FechaLargaDesdeNumero($fila['core01gradofecha']);
		$programa=$fila['core09nombre'];
		}
	$px = 8;
	$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
	$pdf->SetCreator('Plataforma AUREA - GIDT - PTI');
	$pdf->SetAuthor('Universidad Nacional Abierta y Distancia - UNAD');
	$pdf->SetTitle('Carné de Egresado');
	$pdf->SetSubject('Carné De Egresado');
	$pdf->SetKeywords('Carné Egresado, PDF');

	// set header and footer fonts
	$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
	$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

	// set default monospaced font
	$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

	// set margins
	$pdf->SetMargins(10, PDF_MARGIN_TOP, 10);
	$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
	$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

	// set auto page breaks
	$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

	// set image scale factor
	$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

	//$pdf->SetProtection(array('modify', 'copy', 'annot-forms', 'fill-forms', 'extract', 'assemble '), "$id", null, 0, null);
	$pdf->SetProtection(array('modify', 'copy', 'annot-forms', 'fill-forms', 'extract', 'assemble '), "$id_estudiante", null, 0, null);
	$pdf->SetFont('times', 'B', 20);

	$pdf->AddPage('P', 'A4');
	
	$pdf->setJPEGQuality(75);
	// The '@' character is used to indicate that follows an image data stream and not an image file name
	//$pdf->Image('@'.$imagen, 83, 32, 18, 23);
	$imagen=base64_decode('iVBORw0KGgoAAAANSUhEUgAAABwAAAASCAMAAAB/2U7WAAAABlBMVEUAAAD///+l2Z/dAAAASUlEQVR4XqWQUQoAIAxC2/0vXZDrEX4IJTRkb7lobNUStXsB0jIXIAMSsQnWlsV+wULF4Avk9fLq2r8a5HSE35Q3eO2XP1A1wQkZSgETvDtKdQAAAABJRU5ErkJggg==');
	$pdf->Image('@'.$imagen, 83, 32, 18, 23);

	$html = "";
	$html = '<style type="text/css">
table {     
font-family: Verdana, Geneva, Arial, Helvetica, Sans-Serif;
font-style: normal;
font-weight: normal;

position: relative;
width: 100%;
table-layout: fixed;
border-collapse: collapse;
margin: 2px;
font-size: 12px;
}

.text2{
font-size: 8px;
font-weight: normal;
}
.text2{
font-size: 14px;
font-weight: normal;
}
.text3{
font-size: 12px;
font-weight: normal;
}
.text4{
font-size: 14px;
font-weight: bold;
}
</style>

<table>
<tr>
<td align="center" style="width: 5%;

padding: 0px;

vertical-align: middle;
text-align: justify;
background-color: #f2b80d;
color: #ffffff">



</td>
<td style="width: 45%;                                    
padding: 10px;

vertical-align: middle;
text-align: justify;">
<table>
<tr>
<td>
<div align="left">
<br><img src="http://congresos.unad.edu.co/apps/ulib/imagenes/logoUNAD2600.jpg" width="92" height="70" alt=""/>
</div>
<br>
</td>
<td>
<div align="right">
<br>

</div>
</td>
</tr>
<tr>
<td colspan="2" style="font-weight: bold; font-size: 11px;">'.$nombre.'</td>
</tr>
<tr>
<td colspan="2" style="font-weight: normal; font-size: 10px;">'.$id_estudiante.'</td>
</tr>
<tr>
<td colspan="2" style="font-weight: normal; font-size: '.$px.'px;">'.$programa.'</td>
</tr>
<tr>
<td colspan="2" style="font-weight: normal; font-size: '.$px.'px;">'.$centro.'</td>
</tr>
<tr>
<td colspan="2" style="font-weight: normal; font-size: '.$px.'px;">'.$fecha.'<br></td>
</tr>
</table>                    
</td>
<td style="width: 50%;                                    
padding: 10px;

vertical-align: middle;
text-align: justify;
font-weight: normal; font-size: 9px;">
<br>
<ol>
<li>Este carné es personal e intransferible.</li>
<li>Es el único documento válido como Egresado ante las autoridades de la Universidad.</li>
<li>No es valido para transacciones comerciales.</li>
<li>Si encuentra este documento, favor devolverlo o comunicarse al 344 37 00 - Calle 14 Sur No. 14-23.</li>
</ol>
<br>
<div align="center" >
<table>
<tr>
<td style="width: 25%">
</td>
<td style="width: 75%">
<span class="text2">
www.unad.edu.co
</span>
<br><br>
<span class="text3">
Colombia
</span>                                    
<span class="text1">                                       

</span>
<br>
<span class="text3">
Bogotá D.C.
</span>
<br>
</td>
</tr>
</table>                        
</div>
<br>                  
</td>
</tr>
</table>
';
	// ---------------------------------------------------------
	$pdf->writeHTML($html, true, false, true, false, '');

	// define barcode style
	$style = array(
		'position' => '',
		'align' => 'C',
		'stretch' => false,
		'fitwidth' => true,
		'cellfitalign' => '',
		'border' => false,
		'hpadding' => 'auto',
		'vpadding' => 'auto',
		'fgcolor' => array(0,0,0),
		'bgcolor' => false, //array(255,255,255),
		'text' => false,
		'font' => 'helvetica',
		'fontsize' => 8,
		'stretchtext' => 4
	);

	//$pdf->write1DBarcode($code, $type, $x, $y, $w, $h, $xres, $style, $align);
	$pdf->write1DBarcode($id_estudiante.' '.$solicitud, 'C128', '21', '72', '54', 10, 0.4, $style, 'N');

	$pdf->Image($_SERVER['DOCUMENT_ROOT'].'/imagenes/logoscalidad.png', 73, 73, 32, 9, 'PNG', 'http://www.unad.edu.co', '', true, 150, '', false, false, 0, false, false, false);
	// set style for barcode
	$style = array(
		'border' => 2,
		'vpadding' => 'auto',
		'hpadding' => 'auto',
		'fgcolor' => array(0,0,0),
		'bgcolor' => false, //array(255,255,255)
		'module_width' => 1, // width of a single module in points
		'module_height' => 1 // height of a single module in points
	);

	$mensaje = "$nombre\n$id_estudiante\n$programa\n$centro\n$fecha";
	$pdf->write2DBarcode($mensaje, 'QRCODE,L', 112, 57, 23, 23, $style, 'N');


	//Verdana, Geneva, Arial, Helvetica, Sans-Serif
	$pdf->SetFont('Helvetica', 'B', 15);

	$pdf->SetTextColor(255, 255, 255);


	$pdf->StartTransform();
	$pdf->Rotate(90);
	$pdf->MultiCell(2, 1, "", 0, 'C', false, 1, "30", "", true, 0, false, true, 0, "T", false, true);
	$pdf->MultiCell(50, 8, "E G R E S A D O", 0, 'C', false, 1, "", "", true, 0, false, true, 0, "T", false, true);

	$pdf->StopTransform();



	// reset pointer to the last page
	$pdf->lastPage();

	$style = array('dash' => '5,5');
	$pdf->SetDrawColor(200, 220, 255);
	$pdf->Line(105, 27, 105, 82.8);//línea central

	$pdf->Line(5, 27, 205, 27);//línea izquierda
	$pdf->Line(5, 82.8, 205, 82.8);//línea inferior
	$pdf->Line(10, 22, 10, 88);//línea izquierda

	$pdf->Line(200, 22, 200, 88);//línea derecha


	// reset pointer to the last page
	$pdf->lastPage();
	//$archivo1 = "carne_egresado_$id_estudiante $solicitud.pdf";
	//Close and output PDF document
	//$pdf->Output($archivo1, 'F');
	return array($pdf, $sError);
	}
//Empezar revisando que haya una sesion.
if ($_SESSION['unad_id_tercero']==0){
	die();
	}
$sError='';
$iReporte=0;
$iFormato94=0;
$bEntra=false;
$bDebug=false;
if (isset($_REQUEST['r'])!=0){$iReporte=numeros_validar($_REQUEST['r']);}
if (isset($_REQUEST['rdebug'])!=0){if ($_REQUEST['rdebug']==1){$bDebug=true;}}
if (isset($_REQUEST['iformato94'])!=0){$iFormato94=$_REQUEST['iformato94'];}
if ((int)$iReporte!=0){$bEntra=true;}
if ($bEntra){
	$_SESSION['u_ultimominuto']=iminutoavance();
	$iFormato=0;
	if (isset($_REQUEST['f'])!=0){if ($_REQUEST['f']==1){$iFormato=1;}}
	//if (isset($_REQUEST['variable'])==0){$_REQUEST['variable']=0;}
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$bCodificarUTF8=false;
	if ($APP->utf8==1){$bCodificarUTF8=true;}
	$bEntra=false;
	$sTituloRpt='Carnet_Egresado';
	$bReporteControlado=false;
	$sNumCopiaReporte='';
	list($pdf, $sError)=pdfReporteV2($iReporte, $_REQUEST, $iFormato, $sNumCopiaReporte, $bCodificarUTF8, $objDB, $bDebug);
	if ($sError==''){
		//$sTituloRpt=$sTituloRpt.'_'.$pdf->sRefRpt;
		$pdf->Output($sTituloRpt.'.pdf','D');
		}else{
		echo $sError;
		}
	}
?>