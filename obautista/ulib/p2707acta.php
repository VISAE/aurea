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
	$id='321';
	$programa='Libertad';
	$centro='Pantano de vargaz';
	$fecha='20 de julio de 1810';
	$solicitud='Solicitud';
	$escuela='';
	$dd='';
	$mm='';
	$year='';
	$ciudad='';
	$cadena1='';
	$director='';
	$centro='';
	$cadena3='';
	$titulo='';
	$snies='';
	$nombres='';
	$apellidos='';
	$cadena2='';
	$tipo_documento='';
	$documento='';
	$lugar_expedicion='';
	$trabajo_grado='';
	$libro_actas='';
	$reg_diploma='';
	$libro='';
	$folio='';
	$fecha_dd='';
	$fecha_mm='';
	$fecha_yy='';
	$sSQL='SELECT TB.core01idtercero, T11.unad11doc, T11.unad11razonsocial, T24.unad24nombre, TB.core01gradofecha, T9.core09nombre, TB.core01gradonumacta 
FROM core01estprograma AS TB, unad11terceros AS T11, unad24sede AS T24, core09programa AS T9 
WHERE TB.core01id='.$idEgresado.' AND TB.core01idtercero=T11.unad11id AND TB.core011idcead=T24.unad24id AND TB.core01idprograma=T9.core09id ';
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$documento=$fila['unad11doc'];
		$nombre=$fila['unad11razonsocial'];
		$centro=$fila['unad24nombre'];
		$fecha=formato_FechaLargaDesdeNumero($fila['core01gradofecha']);
		$programa=$fila['core09nombre'];
		$id=$fila['core01gradonumacta'];
		}
	$px = 8;
	//$pdf = new html2pdf('P','Legal','es',true,'UTF-8', array(25,10,25,10));
	$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
// set default header data
//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 006', PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set font
$pdf->SetFont('dejavusans', '', 10);

// add a page
$pdf->AddPage();

$text='<page backtop="30mm" backbottom="20mm">';
    $text.='<page_header><div align="center">';
        $text.=''
                . '<img src="http://congresos.unad.edu.co/apps/ulib/imagenes/logoUNAD2600.jpg" width="132" height="104" alt=""/>';
        $text.='<p style="text-align: center; font-weight: arial; font-size: 17px;">Ministerio de Educación Nacional</p>';
        $text.='</div>';
    $text.='</page_header>';
    
    $text.="<div>";
        $text.='<p style="text-align: center; font-weight: arial; font-size: 17px;"><b></b></p>';
        $text.='<p style="text-align: center; font-weight: arial; font-size: 19px;"><b>ACTA DE GRADO No. '.$id.'</b></p>';
        $text.='<p style="text-align: center; font-weight: arial; font-size: 17px;">'.$escuela.'</p>';
        $text.='<p style="text-align: justify; font-weight: arial; font-size: 17px;">En ceremonia realizada el día '.$dd.' de '.$mm.' de '.$year
                .' en la ciudad de '.$ciudad.' y presidida por '.$cadena1.' '.$director.', Director del Centro '.$centro.', previa delegación'
                . ' de la Rectoría, '.$cadena3.'</p><br>';        
    $text.='</div>';
    
    $text.='<div align="center">';
        $text.='<b style="font-size: 19px">'.$titulo.'</b><br>';                
    $text.='</div>';
    $text.='<div align="center">';
        $text.='<font style="font-size: 12px"><b>REGISTRO ICFES - M.E.N. '.$snies.'</b></font><br><br>';                
    $text.='</div>';
    $text.='<div align="center">';
        $text.='<b style="font-size: 17px">A:</b><br><br>';                
    $text.='</div>';
    $text.='<div align="center">';
        $text.='<b style="font-size: 17px">'.$nombres.' '.$apellidos.'</b><br><br>';                
    $text.='</div>';
    $text.='<div align="center">';
        $text.='<p style="text-align: justify; font-weight: arial; font-size: 17px;">'.$cadena2.' con '.$tipo_documento.' No. '.$documento.' de '
                .$lugar_expedicion.', quien cumplió satisfactoriamente con los requisitos exigidos en los Reglamentos y Normas Legales,'
                . ' habiendo aprobado el trabajo de grado titulado:</p><br>';         
    $text.='</div>';
    $text.='<div align="center">';
        $text.='<p style="text-align: center; font-weight: arial; font-size: 17px;"><b>'.$trabajo_grado.'</b></p>';         
    $text.='</div>';
    $text.='<div align="center">';
        $text.='<p style="text-align: justify; font-weight: arial; font-size: 17px;">Y le otorgó el diploma que lo acredita como tal. <br><br>'
                . 'En fe de lo anterior se firma la presente Acta de Grado,'
                . ' en Bogotá, D.C., a los '.$dd.' días del mes de '.$mm.' de '.$year.'.</p><br>';         
    $text.='</div>';
    $text.='<div align="center">';
        $text.='<p style="text-align: center; font-weight: arial; font-size: 17px;"><b>Anotado en el libro de actas No. '.$libro_actas.''
                . '<br>Registro de Diploma '.$reg_diploma.' Libro '.$libro.' Folio '.$folio.'</b></p>';         
    $text.='</div>';
    $text.='<div align="center">';
        $text.='<p style="text-align: center; font-weight: arial; font-size: 17px;"><i>En fe de lo anterior, se firma la presente Acta de Grado'
                . ' en Bogotá D.C., a los '.$fecha_dd.' días del mes de '.$fecha_mm.' de '.$fecha_yy.'</i></p>';         
    $text.='</div>';
    $text.='<div align="center">';
        $text.='<p style="text-align: center; font-weight: arial; font-size: 17px;"><b>ES FIEL DUPLICADO TOMADO DEL DOCUMENTO ORIGINAL</b></p><br>';         
    $text.='</div>';
$text.='</page>';

	$pdf->WriteHTML($text, true, false, true, false, '');
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
	$sTituloRpt='Acta';
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