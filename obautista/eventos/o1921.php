<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2016 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Version 2.12.5b miércoles, 30 de marzo de 2016
*/
error_reporting(E_ALL);
ini_set('display_errors', 1);
set_time_limit(0);
if (!file_exists('./app.php')){
	echo '<b>Error N 1 de instalaci&oacute;n</b><br>No se ha establecido un archivo de configuraci&oacute;n, por favor comuniquese con el administrador del sistema.';
	die();
	}
require '../config.php';
mb_internal_encoding('UTF-8');
require './app.php';
require $APP->rutacomun.'unad_todas.php';
require $APP->rutacomun.'libs/clsdbadmin.php';
require $APP->rutacomun.'unad_librerias.php';
require $APP->rutacomun.'excel/PHPExcel.php';
require $APP->rutacomun.'excel/PHPExcel/Writer/Excel2007.php';
require 'lib1921.php';
if ($_SESSION['unad_id_tercero']==0){
	die();
	}
$_SESSION['u_ultimominuto']=iminutoavance();
$sError='';
$iReporte=1916;
$bEntra=true;
$id16=0;
$pagina=1;
$bDebug=false;
if (isset($_REQUEST['clave'])==0){$_REQUEST['clave']='';}
if (isset($_REQUEST['rdebug'])==0){$_REQUEST['rdebug']=0;}
if (isset($_REQUEST['id1916'])==0){$_REQUEST['id1916']='';}
if (isset($_REQUEST['pagina'])==0){$_REQUEST['pagina']=1;}
if (isset($_REQUEST['v3'])==0){$_REQUEST['v3']='';}
$id16=numeros_validar($_REQUEST['id1916']);
$pagina=numeros_validar($_REQUEST['pagina']);
if ($id16==''){$id16=0;}
if ($pagina==''){$pagina=1;}
if ($id16<1){$sError='No se ha definido la referencia para el reporte';}
if ($sError!=''){$bEntra=false;}
if ($bEntra){
	if ($_REQUEST['rdebug']==1){$bDebug=true;}
	$objdb=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objdb->dbPuerto=$APP->dbpuerto;}
	// -- Hacemos una comprobacion de la tabla de totales.
	$sTablaTotal='even31total_'.$id16;
	if (!$objdb->bexistetabla($sTablaTotal)){
		$sError=f1921_ArmarTemporal($id16, $objdb);
		if ($sError!=''){
			echo $sError;
			die();
			}
		f1921_ReconstruirTotales($id16, $objdb);
		}
	// -- Fin del armado la tabla de totales.
	$bPorPeraca=false;
	$bPorCurso=false;
	$sTituloEncuesta=$id16;
	$sTituloRpt='DatosEncuesta_'.$id16;
	$sql='SELECT even16consec, even16encabezado, even16porperiodo, even16porcurso FROM even16encuesta WHERE even16id='.$id16.'';
	$tabla=$objdb->ejecutasql($sql);
	if ($objdb->nf($tabla)>0){
		$fila=$objdb->sf($tabla);
		$sTituloEncuesta=utf8_decode($fila['even16encabezado']);
		$sTituloRpt='DatosEncuesta_'.$fila['even16consec'];
		if ($fila['even16porperiodo']=='S'){$bPorPeraca=true;}
		if ($fila['even16porcurso']=='S'){$bPorCurso=true;}
		}
	/*
	$sFormato='encuestas.xlsx';
	if ($sError==''){
		if (!file_exists($sFormato)){
			$sError='Formato no encontrado {'.$sFormato.'}';
			}
		}
	*/
	if ($sError==''){
		$cSepara=',';
		$cEvita=';';
		$cComplementa='.';
		if (isset($_REQUEST['separa'])!=0){
			if ($_REQUEST['separa']==';'){
				$cSepara=';';
				$cEvita=',';
				}
			}
		$iBase=0;
		$iTope=10000;
		if ($pagina>1){
			}
		$aPais=array();
		$sql='SELECT unad18codigo, unad18nombre FROM unad18pais';
		$tabla=$objdb->ejecutasql($sql);
		while ($fila=$objdb->sf($tabla)){
			$aPais[$fila['unad18codigo']]=utf8_decode($fila['unad18nombre']);
			}
		$aDepto=array();
		$sql='SELECT unad19codigo, unad19nombre FROM unad19depto';
		$tabla=$objdb->ejecutasql($sql);
		while ($fila=$objdb->sf($tabla)){
			$aDepto[$fila['unad19codigo']]=utf8_decode($fila['unad19nombre']);
			}
		$aCiudad=array();
		$sql='SELECT unad20codigo, unad20nombre FROM unad20ciudad';
		$tabla=$objdb->ejecutasql($sql);
		while ($fila=$objdb->sf($tabla)){
			$aCiudad[$fila['unad20codigo']]=utf8_decode($fila['unad20nombre']);
			}
		$aZona=array();
		$sql='SELECT unad23id, unad23nombre FROM unad23zona';
		$tabla=$objdb->ejecutasql($sql);
		while ($fila=$objdb->sf($tabla)){
			$aZona[$fila['unad23id']]=utf8_decode($fila['unad23nombre']);
			}
		$aCEAD=array();
		$sql='SELECT unad24id, unad24nombre FROM unad24sede';
		$tabla=$objdb->ejecutasql($sql);
		while ($fila=$objdb->sf($tabla)){
			$aCEAD[$fila['unad24id']]=utf8_decode($fila['unad24nombre']);
			}
		$aPerfil=array();
		$sql='SELECT even30id, even30nombre FROM even30perfilencuesta';
		$tabla=$objdb->ejecutasql($sql);
		while ($fila=$objdb->sf($tabla)){
			$aPerfil[$fila['even30id']]=utf8_decode($fila['even30nombre']);
			}
		$aPrograma=array();
		$sql='SELECT core09id, core09nombre FROM core09programa';
		$tabla=$objdb->ejecutasql($sql);
		while ($fila=$objdb->sf($tabla)){
			$sTitulo=str_replace($cSepara, $cComplementa, utf8_decode($fila['core09nombre']));
			$aPrograma[$fila['core09id']]=$sTitulo;
			}
		$iNumPreguntas=0;
		$aPreg=array();
		$aIdPreg=array();
		$sql='SELECT even18id, even18pregunta, even18tiporespuesta, even18concomentario, even18divergente, even18idpregcondiciona, even18valorcondiciona 
FROM even18encuestapregunta 
WHERE even18idencuesta='.$id16.'';
		$tabla=$objdb->ejecutasql($sql);
		while ($fila=$objdb->sf($tabla)){
			$sTitulo=str_replace($cSepara, $cComplementa, utf8_decode($fila['even18pregunta']));
			$aPreg[$fila['even18id']]['titulo']=$sTitulo;
			$aPreg[$fila['even18id']]['tipo']=$fila['even18tiporespuesta'];
			$aPreg[$fila['even18id']]['comenta']=$fila['even18concomentario'];
			$aPreg[$fila['even18id']]['diverge']=$fila['even18divergente'];
			$aPreg[$fila['even18id']]['preg']=$fila['even18idpregcondiciona'];
			$aPreg[$fila['even18id']]['resp']=$fila['even18valorcondiciona'];
			$aPreg[$fila['even18id']]['opciones']=0;
			//Para las preguntas de opcion multiple cargar las respuestas...
			switch($fila['even18tiporespuesta']){
				case 1:
				case 2:
				$iOpciones=0;
				$aPreg[$fila['even18id']]['opcion']['-1']='';
				$aPreg[$fila['even18id']]['opcion']['0']='';
				$sql='SELECT even29etiqueta FROM even29encpregresp WHERE even29idpregunta='.$fila['even18id'].' ORDER BY even29consec';
				$tabla29=$objdb->ejecutasql($sql);
				while ($fila29=$objdb->sf($tabla29)){
					$iOpciones++;
					$sTitulo=str_replace($cSepara, $cComplementa, utf8_decode($fila29['even29etiqueta']));
					$aPreg[$fila['even18id']]['opcion'][$iOpciones]=$sTitulo;
					}
				$aPreg[$fila['even18id']]['opciones']=$iOpciones;
				break;
				}
			$iNumPreguntas++;
			$aIdPreg[$iNumPreguntas]=$fila['even18id'];
			}
		list($sCampos2, $sIds, $sTipos, $sVacios)=f1921_CamposTemporal($id16, $objdb, 'T2.');
		$aTipos=explode('|', $sTipos);
		$aIds=explode('|', $sIds);
		$iCampos=count($aTipos);
		$sCondi='';
		if ($_REQUEST['v3']!=''){
			$sCondi='TB.even21idperaca='.$_REQUEST['v3'].' AND ';
			}
		$sql='SELECT T11.unad11doc, TB.even21idperaca, TB.even21idcurso, TB.even21pais, TB.even21depto, TB.even21ciudad, TB.even21agnos, T11.unad11genero, TB.even21perfil, TB.even21idzona, TB.even21idcead, TB.even21idprograma, TB.even21id'.$sCampos2.' 
FROM even21encuestaaplica AS TB, unad11terceros AS T11, '.$sTablaTotal.' AS T2  
WHERE TB.even21idencuesta='.$id16.' AND '.$sCondi.' TB.even21terminada="S" AND TB.even21idtercero=T11.unad11id AND TB.even21id=T2.even31id
ORDER BY T11.unad11doc';
		if ($bDebug){
			echo $sql;
			die();
			}
		$tabla=$objdb->ejecutasql($sql);
		$iCant=$objdb->nf($tabla);
		if ($iCant>10000){}
		$bCSV=true;
		if ($bCSV){
			require $APP->rutacomun.'libs/clsplanos.php';
			$sPath=dirname(__FILE__);
			$sSeparador=archivos_separador($sPath);
			$sPath=archivos_rutaservidor($sPath,$sSeparador);
			$sNombrePlano='tmp.csv';
			$sNombrePlanoFinal=$sTituloRpt.'.csv';
			$objplano=new clsPlanos($sPath.$sNombrePlano);
			$sDato='UNIVERSIDAD NACIONAL ABIERTA Y A DISTANCIA - UNAD';
			$objplano->AdicionarLinea($sDato);
			$sDato=cadena_codificar('Sistema de Gestión de Eventos - Proceso Encuestas');
			$objplano->AdicionarLinea($sDato);
			$objplano->AdicionarLinea($sTituloEncuesta);
			$sDatoPeriodo='';
			$sDatoPeriodo2='';
			$sDatoCurso='';
			$sDatoCurso2='';
			if ($bPorPeraca){
				$sDatoPeriodo=$cSepara;
				$sDatoPeriodo2=$cSepara.'Periodo';
				}
			if ($bPorCurso){
				$sDatoCurso=$cSepara;
				$sDatoCurso2=$cSepara.'Curso';
				}
			$sCol2='';
			$sDato=''.$cSepara.''.$cSepara.''.$cSepara.''.$cSepara.''.$cSepara.''.$cSepara.''.$cSepara.''.$cSepara.''.$cSepara.''.$cSepara.''.$cSepara.''.$cSepara.''.$sDatoPeriodo.$sDatoCurso;
			for ($k=1;$k<=$iNumPreguntas;$k++){
				$idPregunta=$aIdPreg[$k];
				$sDato=$sDato.$cSepara.$aPreg[$idPregunta]['titulo'];
				//Las tipo Pregunts 2 son diferentes.
				switch($aPreg[$idPregunta]['tipo']){
					case 2:
					for($l=1;$l<=$aPreg[$idPregunta]['opciones'];$l++){
						if ($l!=1){
							$sDato=$sDato.$cSepara.'';
							}
						$sCol2=$sCol2.$cSepara.$aPreg[$idPregunta]['opcion'][$l];
						}
					break;
					default:
					$sCol2=$sCol2.$cSepara.'';
					break;
					}
				}
			$objplano->AdicionarLinea($sDato);
			$sDato='No.'.$cSepara.'Documento'.$cSepara.'Pais'.$cSepara.''.$cSepara.'Departamento'.$cSepara.''.$cSepara.'Ciudad'.$cSepara.'Edad'.$cSepara.'Genero'.$cSepara.'Zona'.$cSepara.'CEAD'.$cSepara.'Perfil'.$cSepara.'Programa'.$sDatoPeriodo2.$sDatoCurso2.$sCol2;
			$objplano->AdicionarLinea($sDato);
			$iFila=1;
			$sIdPeraca=-1;
			while ($fila=$objdb->sf($tabla)){
				if (isset($aPais[$fila['even21pais']])==0){
					$sPais='{'.$fila['even21pais'].'}';
					}else{
					$sPais=$aPais[$fila['even21pais']];
					}
				$sDepto='{'.$fila['even21depto'].'}';
				if (isset($aDepto[$fila['even21depto']])!=0){
					$sDepto=$aDepto[$fila['even21depto']];
					}
				$sCiudad='{'.$fila['even21ciudad'].'}';
				if (isset($aCiudad[$fila['even21ciudad']])!=0){
					$sCiudad=$aCiudad[$fila['even21ciudad']];
					}
				$sGenero='';
				if ($fila['unad11genero']=='M'){$sGenero='Masculino';}
				if ($fila['unad11genero']=='F'){$sGenero='Femenino';}
				$sZona='{'.$aZona[$fila['even21idzona']].'}';
				if (isset($aZona[$fila['even21idzona']])!=0){
					//Junio 7 de 2017 - Por alguna razon no encuentra todas las zonas... (????)
					$sZona=$aZona[$fila['even21idzona']];
					}
				$sCEAD=$aCEAD[$fila['even21idcead']];
				$sPerfil=$aPerfil[$fila['even21perfil']];
				$sPrograma='';
				if ($fila['even21perfil']==0){
					$sPrograma='['.$fila['even21idprograma'].']';
					if (isset($aPrograma[$fila['even21idprograma']])!=0){
						$sPrograma=$aPrograma[$fila['even21idprograma']];
						}
					}
				$sDatoPeriodo='';
				$sDatoCurso='';
				//$sCuerpo=str_replace($cSepara, $cComplementa, $sCuerpo);
				if ($bPorPeraca){
					if ($sIdPeraca!=$fila['even21idperaca']){
						$sIdPeraca=$fila['even21idperaca'];
						$sPeriodo=$sIdPeraca;
						$sql='SELECT exte02nombre FROM exte02per_aca WHERE exte02id='.$sIdPeraca;
						$tabla02=$objdb->ejecutasql($sql);
						if ($objdb->nf($tabla02)>0){
							$fila02=$objdb->sf($tabla02);
							$sPeriodo=$fila02['exte02nombre'];
							}
						}
					$sDatoPeriodo=$cSepara.$sPeriodo;
					}
				if ($bPorCurso){
					$sCurso=$fila['even21idcurso'];
					$sDatoCurso=$cSepara.$sCurso;
					}
				$sDato=$iFila.$cSepara.$fila['unad11doc'].$cSepara.$fila['even21pais'].$cSepara.$sPais.$cSepara.$fila['even21depto'].$cSepara.$sDepto.$cSepara.$sCiudad.$cSepara.$fila['even21agnos'].$cSepara.$sGenero.$cSepara.$sZona.$cSepara.$sCEAD.$cSepara.$sPerfil.$cSepara.$sPrograma.$sDatoPeriodo.$sDatoCurso;
				for ($k=1;$k<=$iNumPreguntas;$k++){
					$idPregunta=$aIdPreg[$k];
					$sRespuesta='';
					switch($aPreg[$idPregunta]['tipo']){
						case 0:
						if ($fila['even31r'.$idPregunta]==0){$sRespuesta='No';}
						if ($fila['even31r'.$idPregunta]==1){$sRespuesta='Si';}
						break;
						case 1:
						$iSeleccion=$fila['even31r'.$idPregunta];
						$sRespuesta='['.$iSeleccion.']';
						if (isset($aPreg[$idPregunta]['opcion'][$iSeleccion])!=0){
							$sRespuesta=$aPreg[$idPregunta]['opcion'][$iSeleccion];
							}
						break;
						case 2:
						for($l=1;$l<=$aPreg[$idPregunta]['opciones'];$l++){
							$aRpta[$l]=0;
							}
						$sSeleccion=$fila['even31r'.$idPregunta];
						$aSeleccion=explode('.',$sSeleccion);
						$iSeleccion=count($aSeleccion);
						for ($l=0;$l<$iSeleccion;$l++){
							$iPuesto=(int)$aSeleccion[$l];
							if ($iPuesto>0){
								$aRpta[$iPuesto]=1;
								}
							}
						for($l=1;$l<=$aPreg[$idPregunta]['opciones'];$l++){
							if ($l!=1){
								$sRespuesta=$sRespuesta.$cSepara.'';
								}
							//Falta dibujar en que respuestas estuvo.
							if ($aRpta[$l]>0){
								$sRespuesta=$sRespuesta.'Si';
								}
							}
						break;
						case 3:
						$sTitulo=str_replace($cSepara, $cComplementa, utf8_decode($fila['even31r'.$idPregunta]));
						$sTitulo=str_replace($cEvita, $cComplementa, $sTitulo);
						$sRespuesta=$sTitulo;
						break;
						}
					$sDato=$sDato.$cSepara.$sRespuesta;
					}
				$objplano->AdicionarLinea($sDato);
				$iFila++;
				}
			$objplano->Generar();
			header('Content-Description: File Transfer');
			header('Content-Type: text/csv');
			header('Content-Length: '.filesize($sPath.$sNombrePlano));
			header('Content-Disposition: attachment; filename='.basename($sNombrePlanoFinal));
			readfile($sPath.$sNombrePlano);
			//Tenemos que enviarlo a css
			}else{
			$objReader=PHPExcel_IOFactory::createReader('Excel2007');
			$objPHPExcel=$objReader->load($sFormato);
			$objPHPExcel->getProperties()->setCreator('Mauro Avellaneda - http://www.unad.edu.co');
			$objPHPExcel->getProperties()->setLastModifiedBy('Mauro Avellaneda - http://www.unad.edu.co');
			$objPHPExcel->getProperties()->setTitle($sTituloRpt);
			$objPHPExcel->getProperties()->setSubject($sTituloRpt);
			$objPHPExcel->getProperties()->setDescription('Reporte de http://www.unad.edu.co');
			$objHoja=$objPHPExcel->getActiveSheet();
			$objHoja->setTitle($sTituloRpt);
			$iFila=9;
			while ($fila=$objdb->sf($tabla)){
				$sPais=$aPais[$fila['even21pais']];
				$objHoja->setCellValueByColumnAndRow(0, $iFila, $iFila-8);
				$objHoja->setCellValueByColumnAndRow(1, $iFila, $fila['unad11doc']);
				$objHoja->setCellValueByColumnAndRow(2, $iFila, $fila['even21pais']);
				$objHoja->setCellValueByColumnAndRow(3, $iFila, cadena_codificar($sPais));
				$iFila++;
				}
			if ($_REQUEST['clave']!=''){
				//Bloquear la hoja.
				$objHoja->getProtection()->setPassword($_REQUEST['clave']);
				$objHoja->getProtection()->setSheet(true);
				$objHoja->getProtection()->setSort(true);
				}
			//descargar el resultado
			header('Expires: Thu, 27 Mar 1980 23:59:00 GMT'); //la pagina expira en una fecha pasada
			header('Last-Modified: '.gmdate("D, d M Y H:i:s").' GMT'); //ultima actualizacion ahora cuando la cargamos
			header('Cache-Control: no-cache, must-revalidate'); //no guardar en CACHE
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename="'.$sTituloRpt.'.xlsx"');
			header('Cache-Control: max-age=0');
			$objWriter=PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
			$objWriter->setPreCalculateFormulas(false);
			$objWriter->save('php://output');
			}
		die();
		}else{
		echo $sError;
		}
	}
?>