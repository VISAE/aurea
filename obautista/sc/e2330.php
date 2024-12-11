<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2020 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Version 2.25.8 jueves, 19 de noviembre de 2020
--- @modificado William Jammirlhey Rico Ruiz - UNAD - 2020 ---
--- william.rico@unad.edu.co - http://www.unad.edu.co
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
$mensajes_2339='lg/lg_2339_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_2339)){$mensajes_2339='lg/lg_2339_es.php';}
require $mensajes_2339;
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
if ($sError!=''){$bEntra=false;}
if ($bEntra){
	if ($_REQUEST['rdebug']==1){$bDebug=true;}
	$bEntra=false;
	$sTituloRpt='Acceso a moviles';
	$sFormato='formato.xlsx';
	if ($sError==''){
		if (!file_exists($sFormato)){
			$sError='Formato no encontrado {'.$sFormato.'}';
			}
		}
	/* if (isset($_REQUEST['v3'])==0){($_REQUEST['v3']='');} */
	if ($sError==''){
		$objReader=PHPExcel_IOFactory::createReader('Excel2007');
		$objPHPExcel=$objReader->load($sFormato);
		$objPHPExcel->getProperties()->setCreator('Mauro Avellaneda - http://www.unad.edu.co');
		$objPHPExcel->getProperties()->setLastModifiedBy('Mauro Avellaneda - http://www.unad.edu.co');
		$objPHPExcel->getProperties()->setTitle($sTituloRpt);
		$objPHPExcel->getProperties()->setSubject($sTituloRpt);
		$objPHPExcel->getProperties()->setDescription('Reporte de http://www.unad.edu.co');
		$objHoja=$objPHPExcel->getActiveSheet();
		$objHoja->setTitle($sTituloRpt);
		$sColTope='N';
		//Imagen del encabezado
		PHPExcel_Justificar_Celda_HorizontalCentro($objPHPExcel, 'A1');
		PHPExcel_Agrega_Dibujo($objPHPExcel, 'Logo', 'Logo', $APP->rutacomun.'imagenes/rpt_cabeza.jpg','161','A1','0',false,'0');
		$sFechaImpreso=formato_fechalarga(fecha_hoy(), true);
		PHPExcel_Texto_Tres_Partes($objPHPExcel, $sColTope.'9',' ','Fecha impresión: ',$sFechaImpreso,'AmOsUn',true,false, 9,'Calibri','AzOsUn');
		PHPExcel_Alinear_Celda_Derecha($objPHPExcel, $sColTope.'9');
		//Titulo 
		$objHoja->setCellValueByColumnAndRow(0, 10, 'Acceso a moviles');
		PHPExcel_Mexclar_Celdas($objPHPExcel,'A10:'.$sColTope.'10');
		PHPExcel_Justificar_Celda_HorizontalCentro($objPHPExcel,'A10');
		PHPExcel_Formato_Fuente_Celda($objPHPExcel,'A10','14','Yu Gothic','AzOsUn',true,false,false);
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		//Espacio para el encabezado
		$sSubtitulo='Subtitulo';
		$sDetalle='Detalle del reporte';
		$iFila=12;
		PHPExcel_RellenarCeldas($objPHPExcel,'A1:'.$sColTope.$iFila,'Bl',false);
		$iFila++;
		$iFilaBase=$iFila;
		$aTitulos=array('Tipo','Documento','Nombres y Apellidos','Género','Escuela','Zona','Centro','Periodo','Acceso a Móviles','Fecha de Inicio','Fecha de Fin','Autoriza','Estado','Detalle');
		for ($k=0;$k<=13;$k++){
			$objHoja->setCellValueByColumnAndRow($k, $iFila, $aTitulos[$k]);
			$sColumna=columna_Letra($k);
			$objPHPExcel->getActiveSheet()->getColumnDimension($sColumna)->setWidth(13);
			PHPExcel_Justificar_Celda_HorizontalCentro($objPHPExcel, $sColumna.$iFila);
			}
		PHPExcel_Formato_Fuente_Celda($objPHPExcel,'A'.$iFila.':'.$sColTope.$iFila,'10','Yu Gothic','Ne',true,false,false);
		$iFila++;
		$sSQLadd=' AND TB.unad11accesomovil<>0';
		$sSQLadd1=' WHERE unad11id>0';
		$sSQL='SELECT TB.unad11tipodoc, TB.unad11doc, TB.unad11razonsocial, TB.unad11fechanace, TB.unad11genero, TB.unad11accesomovil, TB.unad11id
FROM unad11terceros AS TB 
'.$sSQLadd1.$sSQLadd.'
ORDER BY TB.unad11doc';
		$aAutoriza=array();
		$aDetalle=array();
		$aEscuela=array();
		$aZona=array();
		$aCentro=array();
		$aPeriodo=array();
		$iFechaini=0;
		$iFechafin=0;
		$sDetalle='';
		$iEstado=0;
		$sAutoriza='';
		$idAutoriza='';
		$idEscuela=0;
		$idZona=0;
		$idCentro=0;
		$idPeriodo=0;
		$ln_unad11autoriza='[Sin dato]';
		$ln_cara39fechaini='[Sin dato]';
		$ln_cara39fechafin='[Sin dato]';
		$ln_cara39estado='[Sin dato]';
		$ln_cara39detalle='[Sin dato]';
		$ln_core16idescuela='[Sin dato]';
		$ln_core16idzona='[Sin dato]';
		$ln_core16idcentro='[Sin dato]';
		$ln_core16peraca='[Sin dato]';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($bDebug){
			$objHoja->setCellValueByColumnAndRow(1, $iFila, $sSQL);
			$iFila++;
			}
		while ($fila=$objDB->sf($tabla)){
			$idTercero=$fila['unad11id'];
			if ($idTercero!=0){
				if (isset($aFechaini[$idTercero])==0){					
					$sSQL='SELECT cara39fechaini, cara39fechafin, cara39idautoriza, cara39estado, cara39detalle FROM cara39autorizacionmovil WHERE cara39idtercero='.$idTercero.' ORDER BY cara39fechaini DESC';
					$tabla39=$objDB->ejecutasql($sSQL);
					if ($objDB->nf($tabla39)>0){
						$fila39=$objDB->sf($tabla39);
						$iFechaini=$fila39['cara39fechaini'];
						$iFechafin=$fila39['cara39fechafin'];
						$iEstado=$fila39['cara39estado'];
						$sDetalle=$fila39['cara39detalle'];
						$idAutoriza=$fila39['cara39idautoriza'];
						}
					}
			$ln_cara39fechaini=fecha_desdenumero($iFechaini);
			$ln_cara39fechafin=fecha_desdenumero($iFechafin);
			$ln_cara39detalle=$sDetalle;
			$ln_cara39estado=$ETI['msg_autorizada'];
			if ($iEstado!=0){
				$ln_cara39estado=$ETI['msg_revocada'];
				}
			}
			if ($idTercero!=0){
				if (isset($aEscuela[$idTercero])==0){
					$sSQL='SELECT core16fechamatricula, core16peraca, core16idescuela, core16idzona, core16idcead FROM core16actamatricula WHERE core16tercero='.$idTercero.' AND core16fechamatricula <= '.$iFechaini.' ORDER BY core16fechamatricula DESC';
					$tabla16=$objDB->ejecutasql($sSQL);
					if ($objDB->nf($tabla16)>0){
						$fila16=$objDB->sf($tabla16);
						$idEscuela=$fila16['core16idescuela'];
						$idZona=$fila16['core16idzona'];
						$idCentro=$fila16['core16idcead'];
						$idPeriodo=$fila16['core16peraca'];
					}
				}
			}
			if ($idEscuela!=0){
				if (isset($aEscuela[$idEscuela])==0){
					$aEscuela[$idEscuela]='{'.$idEscuela.'}';
					$sSQL='SELECT core12sigla FROM core12escuela WHERE core12id='.$idEscuela;
					$tabla12=$objDB->ejecutasql($sSQL);
					if ($objDB->nf($tabla12)>0){
						$fila12=$objDB->sf($tabla12);
						$aEscuela[$idEscuela]=$fila12['core12sigla'];
						}
					}
				$ln_core16idescuela=$aEscuela[$idEscuela];
				}
			if ($idZona!=0){
				if (isset($aZona[$idZona])==0){
					$aZona[$idZona]='{'.$idZona.'}';
					$sSQL='SELECT unad23nombre FROM unad23zona WHERE unad23id='.$idZona;
					$tabla23=$objDB->ejecutasql($sSQL);
					if ($objDB->nf($tabla23)>0){
						$fila23=$objDB->sf($tabla23);
						$aZona[$idZona]=$fila23['unad23nombre'];
						}
					}
				$ln_core16idzona=$aZona[$idZona];
				}
			if ($idCentro!=0){
				if (isset($aCentro[$idCentro])==0){
					$aCentro[$idCentro]='{'.$idCentro.'} ';
					$sSQL='SELECT unad24nombre, unad24idzona FROM unad24sede WHERE unad24id='.$idCentro;
					$tabla24=$objDB->ejecutasql($sSQL);
					if ($objDB->nf($tabla24)>0){
						$fila24=$objDB->sf($tabla24);
						$aCentro[$idCentro]=$fila24['unad24nombre'];
						}
					}
				$ln_core16idcentro=$aCentro[$idCentro];
				}
			if ($idAutoriza!=0){
				if (isset($aAutoriza[$idAutoriza])==0){
					$aAutoriza[$idAutoriza]='{'.$idAutoriza.'}';
					$sSQL='SELECT unad11razonsocial FROM unad11terceros WHERE unad11id='.$idAutoriza;
					$tablaA=$objDB->ejecutasql($sSQL);
					if ($objDB->nf($tablaA)>0){
						$filaA=$objDB->sf($tablaA);
						$aAutoriza[$idAutoriza]=$filaA['unad11razonsocial'];
						}
					}
			$ln_unad11autoriza=$aAutoriza[$idAutoriza];
			}
			if ($idPeriodo!=0){
				if (isset($aPeriodo[$idPeriodo])==0){
					$aPeriodo[$idPeriodo]='{'.$idPeriodo.'}';
					$sSQL='SELECT exte02nombre FROM exte02per_aca WHERE exte02id='.$idPeriodo;
					$tablaA=$objDB->ejecutasql($sSQL);
					if ($objDB->nf($tablaA)>0){
						$filaA=$objDB->sf($tablaA);
						$aPeriodo[$idPeriodo]=$filaA['exte02nombre'];
						}
					}
			$ln_core16peraca=$aPeriodo[$idPeriodo];
			}
			$ln_unad11accesomovil=$ETI['si'];
			if ($fila['unad11accesomovil']!=1) {
				$ln_unad11accesomovil=$ETI['no'];
				}
		$aTitulos=array('Tipo','Documento','Nombres y Apellidos','Género','Escuela','Zona','Cead','Periodo','Acceso a Móviles','Fecha de Inicio','Fecha de Fin','Autoriza','Estado','Detalle');
			$objHoja->setCellValueByColumnAndRow(0, $iFila, $fila['unad11tipodoc']);
			$objHoja->setCellValueByColumnAndRow(1, $iFila, $fila['unad11doc']);
			$objHoja->setCellValueByColumnAndRow(2, $iFila, cadena_notildes($fila['unad11razonsocial']));
			$objHoja->setCellValueByColumnAndRow(3, $iFila, $fila['unad11genero']);
			$objHoja->setCellValueByColumnAndRow(4, $iFila, cadena_notildes($ln_core16idescuela));
			$objHoja->setCellValueByColumnAndRow(5, $iFila, cadena_notildes($ln_core16idzona));
			$objHoja->setCellValueByColumnAndRow(6, $iFila, cadena_notildes($ln_core16idcentro));
			$objHoja->setCellValueByColumnAndRow(7, $iFila, cadena_notildes($ln_core16peraca));
			$objHoja->setCellValueByColumnAndRow(8, $iFila, cadena_notildes($ln_unad11accesomovil));
			$objHoja->setCellValueByColumnAndRow(9, $iFila, $ln_cara39fechaini);
			$objHoja->setCellValueByColumnAndRow(10, $iFila, $ln_cara39fechafin);
			$objHoja->setCellValueByColumnAndRow(11, $iFila, cadena_notildes($ln_unad11autoriza));
			$objHoja->setCellValueByColumnAndRow(12, $iFila, $ln_cara39estado);
			$objHoja->setCellValueByColumnAndRow(13, $iFila, cadena_notildes($ln_cara39detalle));
			$objHoja->getColumnDimension(columna_letra(0))->setWidth(5);
			$objHoja->getColumnDimension(columna_letra(1))->setWidth(12);
			$objHoja->getColumnDimension(columna_letra(2))->setWidth(40);
			$objHoja->getColumnDimension(columna_letra(3))->setWidth(8);
			$objHoja->getColumnDimension(columna_letra(4))->setWidth(10);
			$objHoja->getColumnDimension(columna_letra(5))->setWidth(30);
			$objHoja->getColumnDimension(columna_letra(6))->setWidth(20);
			$objHoja->getColumnDimension(columna_letra(7))->setWidth(10);
			$objHoja->getColumnDimension(columna_letra(8))->setWidth(17);
			$objHoja->getColumnDimension(columna_letra(9))->setWidth(15);
			$objHoja->getColumnDimension(columna_letra(10))->setWidth(15);
			$objHoja->getColumnDimension(columna_letra(11))->setWidth(50);
			$objHoja->getColumnDimension(columna_letra(12))->setWidth(10);
			$objHoja->getColumnDimension(columna_letra(13))->setWidth(100);
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
