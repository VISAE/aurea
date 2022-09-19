<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2021 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Version 2.25.10c domingo, 7 de marzo de 2021
*/
/*
error_reporting(E_ALL);
ini_set("display_errors", 1);
*/
if (file_exists('./err_control.php')){require './err_control.php';}
if (!file_exists('./app.php')){
	echo '<b>Error N 1 de instalaci&oacute;n</b><br>No se ha establecido un archivo de configuraci&oacute;n, por favor comuniquese con el administrador del sistema.';
	die();
	}
mb_internal_encoding('UTF-8');
require './app.php';
require $APP->rutacomun.'unad_todas.php';
require $APP->rutacomun.'libs/clsdbadmin.php';
require $APP->rutacomun.'unad_librerias.php';
require $APP->rutacomun.'excel/PHPExcel.php';
require $APP->rutacomun.'excel/PHPExcel/Writer/Excel2007.php';
require $APP->rutacomun.'libexcel.php';
if ($_SESSION['unad_id_tercero']==0){
	die();
	}
$_SESSION['u_ultimominuto']=iminutoavance();
$sError='';
$iReporte=0;
$bEntra=true;
$bDebug=false;
if (isset($_REQUEST['r'])!=0){$iReporte=numeros_validar($_REQUEST['r']);}
if (isset($_REQUEST['clave'])==0){$_REQUEST['clave']='';}
if (isset($_REQUEST['rdebug'])==0){$_REQUEST['rdebug']=0;}
if (isset($_REQUEST['v3'])==0){$_REQUEST['v3']='';}
if (isset($_REQUEST['v4'])==0){$_REQUEST['v4']='';}
if ($sError!=''){$bEntra=false;}
if ($bEntra){
	if ($_REQUEST['rdebug']==1){$bDebug=true;}
	$bEntra=false;
	$sTituloRpt='Reporte';
	$sFormato='formato.xlsx';
	if ($sError==''){
		if (!file_exists($sFormato)){
			$sError='Formato no encontrado {'.$sFormato.'}';
			}
		}
	/* if (isset($_REQUEST['v3'])==0){($_REQUEST['v3']='');} */
	$saiu44idsolicitante=$_REQUEST['v3'];
	$saiu44idtiposol=$_REQUEST['v4'];
	if ($sError==''){
		$objReader=PHPExcel_IOFactory::createReader('Excel2007');
		$objPHPExcel=$objReader->load($sFormato);
		$objPHPExcel->getProperties()->setCreator('Mauro Avellaneda - http://www.unad.edu.co');
		$objPHPExcel->getProperties()->setLastModifiedBy('Mauro Avellaneda - http://www.unad.edu.co');
		$objPHPExcel->getProperties()->setTitle($sTituloRpt);
		$objPHPExcel->getProperties()->setSubject($sTituloRpt);
		$objPHPExcel->getProperties()->setDescription('Reporte de http://www.unad.edu.co');
		$objHoja=$objPHPExcel->getActiveSheet();
		$objHoja->setTitle(substr($sTituloRpt, 0, 30));
		$sColTope='J';
		//Imagen del encabezado
		PHPExcel_Justificar_Celda_HorizontalCentro($objPHPExcel, 'A1');
		PHPExcel_Agrega_Dibujo($objPHPExcel, 'Logo', 'Logo', $APP->rutacomun.'imagenes/rpt_cabeza.jpg','161','A1','0',false,'0');
		$sFechaImpreso=formato_fechalarga(fecha_hoy(), true);
		PHPExcel_Texto_Tres_Partes($objPHPExcel, $sColTope.'9',' ','Fecha impresión: ',$sFechaImpreso,'AmOsUn',true,false, 9,'Calibri','AzOsUn');
		PHPExcel_Alinear_Celda_Derecha($objPHPExcel, $sColTope.'9');
		//Titulo 
		$objHoja->setCellValueByColumnAndRow(0, 10, 'Verificar Asignacion');
		PHPExcel_Mexclar_Celdas($objPHPExcel,'A10:'.$sColTope.'10');
		PHPExcel_Justificar_Celda_HorizontalCentro($objPHPExcel,'A10');
		PHPExcel_Formato_Fuente_Celda($objPHPExcel,'A10','14','Yu Gothic','AzOsUn',true,false,false);
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		//Espacio para el encabezado
		$sSubtitulo='Subtitulo';
		$sDetalle='Detalle del reporte';
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
		$iFila=12;
		PHPExcel_RellenarCeldas($objPHPExcel,'A1:'.$sColTope.$iFila,'Bl',false);
		$iFila++;
		$iFilaBase=$iFila;
		$aTitulos=array('Col0','Col1','Col2','Col3','Col4','Col5','Col6','Col7','Col8','Col9');
		$aAnchos=array(13,13,13,13,13,13,13,13,13,13);
		for ($k=0;$k<=9;$k++){
			$objHoja->setCellValueByColumnAndRow($k, $iFila, $aTitulos[$k]);
			$sColumna=columna_Letra($k);
			$objPHPExcel->getActiveSheet()->getColumnDimension($sColumna)->setWidth($aAnchos[$k]);
			PHPExcel_Justificar_Celda_HorizontalCentro($objPHPExcel, $sColumna.$iFila);
			}
		//PHPExcel_Mexclar_Celdas($objPHPExcel,'A'.$iFila.':B'.$iFila.'');
		PHPExcel_Formato_Fuente_Celda($objPHPExcel,'A'.$iFila.':'.$sColTope.$iFila,'10','Yu Gothic','Ne',true,false,false);
		$iFila++;
		$sCondi='';
		if ($saiu44idsolicitante!=''){$sCondi=$sCondi.' AND saiu44idsolicitante='.$saiu44idsolicitante.'';};
		if ($saiu44idtiposol!=''){$sCondi=$sCondi.' AND saiu44idtiposol='.$saiu44idtiposol.'';};
		$sSQL='SELECT * 
FROM saiu44revasigna 
WHERE ';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($bDebug){
			$objHoja->setCellValueByColumnAndRow(1, $iFila, $sSQL);
			$iFila++;
			}
		while ($fila=$objDB->sf($tabla)){
			$objHoja->setCellValueByColumnAndRow(0, $iFila, $fila['saiu44idsolicitante']);
			$objHoja->setCellValueByColumnAndRow(1, $iFila, $fila['saiu44idtiposol']);
			$iFila++;
			}
		$objDB->CerrarConexion();
		PHPExcel_RellenarCeldas($objPHPExcel,'A'.$iFilaBase.':'.$sColTope.$iFila, 'Bl', true);
		if ($_REQUEST['clave']!=''){
			/* Bloquear la hoja. */
			$objHoja->getProtection()->setPassword($_REQUEST['clave']);
			$objHoja->getProtection()->setSheet(true);
			$objHoja->getProtection()->setSort(true);
			}
		/* descargar el resultado */
		header('Expires: Thu, 27 Mar 1980 23:59:00 GMT'); /* la pagina expira en una fecha pasada */
		header('Last-Modified: '.gmdate("D, d M Y H:i:s").' GMT'); /* ultima actualizacion ahora cuando la cargamos */
		header('Cache-Control: no-cache, must-revalidate'); /* no guardar en CACHE */
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="'.$sTituloRpt.'.xlsx"');
		header('Cache-Control: max-age=0');
		$objWriter=PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->setPreCalculateFormulas(false);
		$objWriter->save('php://output');
		die();
		}else{
		echo $sError;
		}
	}else{
	echo $sError;
	}
?>