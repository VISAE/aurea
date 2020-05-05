<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2019 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Version 2.23.4 Monday, July 29, 2019
*/
/*
/** Archivo para reportes tipo csv 2416.
* Aquí se genera un archivo tipo csv con la siguiente estructura (indicar estructura).
* @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
* @date Monday, July 29, 2019
*/
error_reporting(E_ALL);
ini_set("display_errors", 1);
if (file_exists('./err_control.php')){require './err_control.php';}
if (!file_exists('./app.php')){
	echo '<b>Error N 1 de instalaci&oacute;n</b><br>No se ha establecido un archivo de configuraci&oacute;n, por favor comuniquese con el administrador del sistema.';
	die();
	}
mb_internal_encoding('UTF-8');
require './app.php';
require $APP->rutacomun.'unad_sesion.php';
require $APP->rutacomun.'unad_todas.php';
require $APP->rutacomun.'libs/clsdbadmin.php';
require $APP->rutacomun.'unad_librerias.php';
require $APP->rutacomun.'libs/clsplanos.php';
require $APP->rutacomun.'libdatos.php';
if ($_SESSION['unad_id_tercero']==0){
	die();
	}
$_SESSION['u_ultimominuto']=iminutoavance();
$sError='';
$iReporte=0;
$bEntra=true;
$bDebug=false;
if (isset($_REQUEST['clave'])==0){$_REQUEST['clave']='';}
if (isset($_REQUEST['periodo'])==0){$_REQUEST['periodo']='';}
if (isset($_REQUEST['curso'])==0){$_REQUEST['curso']='';}
if (isset($_REQUEST['tutor'])==0){$_REQUEST['tutor']='';}
if (isset($_REQUEST['rdebug'])==0){$_REQUEST['rdebug']=0;}
if ($_REQUEST['curso']==''){$sError='No se ha especificado un curso';}
if ($_REQUEST['periodo']==''){$sError='No se ha especificado un periodo';}
if ($sError!=''){$bEntra=false;}
if ($bEntra){
	if ($_REQUEST['rdebug']==1){$bDebug=true;}
	$cSepara=',';
	$cEvita=';';
	$cComplementa='.';
	if (isset($_REQUEST['separa'])!=0){
		if ($_REQUEST['separa']==';'){
			$cSepara=';';
			$cEvita=',';
			}
		}
	$idPeraca=$_REQUEST['periodo'];
	$idCurso=$_REQUEST['curso'];
	$idTutor=$_REQUEST['tutor'];
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$sPath=dirname(__FILE__);
	$sSeparador=archivos_separador($sPath);
	$sPath=archivos_rutaservidor($sPath,$sSeparador);
	$sNombrePlano='sabana.csv';
	$sTituloRpt='Sabana_de_notas_'.$_REQUEST['curso'].'_'.$_REQUEST['periodo'].'';
	$sNombrePlanoFinal=$sTituloRpt.'.csv';
	$objplano=new clsPlanos($sPath.$sNombrePlano);
	$sDato='UNIVERSIDAD NACIONAL ABIERTA Y A DISTANCIA - UNAD';
	$objplano->AdicionarLinea($sDato);
	$sDato=utf8_decode('Sabana de notas -  Periodo: '.$_REQUEST['periodo'].' - Curso:'.$_REQUEST['curso'].'');
	$objplano->AdicionarLinea($sDato);
	$sDato='';
	$objplano->AdicionarLinea($sDato);
	/* Alistar los arreglos para las tablas hijas */
	$aceca16idperaca=array();
	$aSys11=array();
	/*
	$sTitulos1='Titulo 1';
	for ($l=1;$l<=5;$l++){
		$sTitulo1=$sTitulo1.$cSepara;
		}
	*/
	$idContPeraca=f146_Contenedor($idPeraca, $objDB);
	//Los encabezados...
	$sNomCurso='{'.$_REQUEST['curso'].'}';
	$sNomDocente='{'.$_REQUEST['tutor'].'}';
	$sSQL='SELECT unad40nombre FROM unad40curso WHERE unad40id='.$_REQUEST['curso'];
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$sNomCurso=$fila['unad40nombre'];
		}
	$aEstado=array('Pendiente', 'No Iniciada', '', 'Presentada', '', 'No Presentada', '', 'Calificada');
	$sTabla06='core06grupos_'.$idContPeraca;
	$sCondiGrupo='';
	$sSQLBase='';
	$aActividades=array();
	$aTutor=array();
	$iNumActividades=0;
	$sListaActividades='';
	//Estado de la matricula
	$aEstadoMat=array();
	$sSQL='SELECT core33id, core33nombre FROM core33estadomatricula ';
	$tabla=$objDB->ejecutasql($sSQL);
	while($fila=$objDB->sf($tabla)){
		$aEstadoMat[$fila['core33id']]=cadena_notildes($fila['core33nombre']);
		}
	//Traer las actividades del curso.
	$sSQL='SELECT T18.ofer18idactividad, T4.ofer04nombre, T3.ofer03idmomento 
FROM ofer18cargaxnavxdia AS T18, ofer04cursoactividad AS T4, ofer03cursounidad AS T3 
WHERE T18.ofer18curso='.$idCurso.' AND T18.ofer18per_aca='.$idPeraca.' AND T18.ofer18numaula=1 AND T18.ofer18fase<>0 AND T18.ofer18unidad<>0
AND T18.ofer18idactividad=T4.ofer04id AND T18.ofer18unidad=T3.ofer03id
ORDER BY T3.ofer03idmomento, STR_TO_DATE(T18.ofer18fecharetro, "%d/%m/%Y")';
	$tabla=$objDB->ejecutasql($sSQL);
	while($fila=$objDB->sf($tabla)){
		$iNumActividades++;
		$aActividades[$iNumActividades]['id']=$fila['ofer18idactividad'];
		$aActividades[$iNumActividades]['nombre']=utf8_decode($fila['ofer04nombre']);
		$sListaActividades=$sListaActividades.$cSepara.str_replace($cSepara, $cComplementa, utf8_decode($fila['ofer04nombre']));
		}
	
	//Lo vamos a hacer en dos etapas, una para determinar los grupos
	$sCondiTutor='';
	$sCondiTutor2='';
	$sTituloTutor=$cSepara.'Tutor';
	if ($idTutor!=''){
		$sCondiTutor='TB.core04idtutor='.$idTutor.' AND ';
		$sCondiTutor2='TB.core05idtutor='.$idTutor.' AND ';
		$sTituloTutor='';
		}
	$sBloque1='Grupo'.$cSepara.'TD'.$cSepara.'Documento'.$cSepara.'Estudiante'.$cSepara.'Estado'.$sListaActividades.$cSepara.'75 %'.$cSepara.'25 %'.$cSepara.'Acumulado'.$cSepara.'Num Actividades No Presentadas'.$sTituloTutor;
	//$objplano->AdicionarLinea($sTitulo1);
	$objplano->AdicionarLinea($sBloque1);
	$sSQL='SHOW TABLES LIKE "core04%"';
	if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Total Periodo: Lista de contenedores: '.$sSQL.'<br>';}
	$tablac=$objDB->ejecutasql($sSQL);
	while($filac=$objDB->sf($tablac)){
		$iContenedor=substr($filac[0], 16);
		if ($sSQLBase!=''){$sSQLBase=$sSQLBase.' UNION ';}
		$sSQLBase=$sSQLBase.'SELECT T6.core06consec, TB.core04estado, T11.unad11tipodoc, T11.unad11doc, T11.unad11razonsocial, TB.core04nota75, TB.core04nota25, TB.core04estado, TB.core04tercero, TB.core04idtutor 
FROM core04matricula_'.$iContenedor.' AS TB, '.$sTabla06.' AS T6, unad11terceros AS T11 
WHERE '.$sCondiTutor.'TB.core04idcurso='.$idCurso.' AND TB.core04peraca='.$idPeraca.' AND TB.core04idgrupo=T6.core06id AND TB.core04tercero=T11.unad11id';
		}
	$sSQL=$sSQLBase.' ORDER BY core06consec, unad11doc';
	$aEstudiante=array();
	$aLista=array();
	$iEstudiantes=0;
	//if ($bDebug){
		//$objplano->AdicionarLinea($sSQL);
		//}
	$tabla=$objDB->ejecutasql($sSQL);
	while ($fila=$objDB->sf($tabla)){
		$iEstudiantes++;
		$aLista[$fila['core04tercero']]=$iEstudiantes;
		$aEstudiante[$iEstudiantes]['grupo']=$fila['core06consec'];
		$aEstudiante[$iEstudiantes]['id']=$fila['core04tercero'];
		$aEstudiante[$iEstudiantes]['td']=$fila['unad11tipodoc'];
		$aEstudiante[$iEstudiantes]['doc']=$fila['unad11doc'];
		$aEstudiante[$iEstudiantes]['razonsocial']=utf8_decode($fila['unad11razonsocial']);
		$aEstudiante[$iEstudiantes]['estado']=$aEstadoMat[$fila['core04estado']];
		for($j=1;$j<=$iNumActividades;$j++){
			$aEstudiante[$iEstudiantes]['act_'.$j]='[Sin Dato]';
			}
		$aEstudiante[$iEstudiantes]['nota75']=$fila['core04nota75'];
		$aEstudiante[$iEstudiantes]['nota25']=$fila['core04nota25'];
		$aEstudiante[$iEstudiantes]['nopresenta']=0;
		$aEstudiante[$iEstudiantes]['tutor']=$fila['core04idtutor'];
		}
	//Cargar las actividades
	$sSQL='SHOW TABLES LIKE "core04%"';
	$tablac=$objDB->ejecutasql($sSQL);
	while($filac=$objDB->sf($tablac)){
		$iContenedor=substr($filac[0], 16);
		//AND TB.core05idactividad='.$idActividad.'
		$sSQL='SELECT TB.core05tercero, TB.core05idactividad, TB.core05estado, TB.core05nota
FROM core05actividades_'.$iContenedor.' AS TB
WHERE '.$sCondiTutor2.'TB.core05idcurso='.$idCurso.' AND TB.core05peraca='.$idPeraca.'';
		$tabla=$objDB->ejecutasql($sSQL);
		while($fila=$objDB->sf($tabla)){
			for($j=1;$j<=$iNumActividades;$j++){
				if ($aActividades[$j]['id']==$fila['core05idactividad']){
					$iLinea=$aLista[$fila['core05tercero']];
					$sVrNota='[Pendiente]';
					switch($fila['core05estado']){
						case 1:
						case 3:
						if ($fila['core05nota']<>0){
							$sVrNota='['.(int)$fila['core05nota'].']';
							}
						break;
						case 5:
						$sVrNota='NO PRESENTADA';
						$aEstudiante[$iLinea]['nopresenta']++;
						break;
						case 7:
						$sVrNota=(int)$fila['core05nota'];
						break;
						}
					$aEstudiante[$iLinea]['act_'.$j]=$sVrNota;
					$j=$iNumActividades+1;
					}
				}
			}
		}
	//Mostrar las tareas.
	for ($k=1;$k<=$iEstudiantes;$k++){
		$lin_core04idgrupo=$aEstudiante[$k]['grupo'];
		$lin_unad11tipodoc=$cSepara.$aEstudiante[$k]['td'];
		$lin_unad11doc=$cSepara.$aEstudiante[$k]['doc'];
		$lin_unad11razonsocial=$cSepara.$aEstudiante[$k]['razonsocial'];
		$lin_core04estado=$cSepara.$aEstudiante[$k]['estado'];
		$lin_act='';
		for($j=1;$j<=$iNumActividades;$j++){
			$lin_act=$lin_act.$cSepara.$aEstudiante[$k]['act_'.$j];
			}
		$lin_nota75=$cSepara.$aEstudiante[$k]['nota75'];
		$lin_nota25=$cSepara.$aEstudiante[$k]['nota25'];
		$lin_notaacum=$cSepara.((int)$aEstudiante[$k]['nota75']+(int)$aEstudiante[$k]['nota25']);
		$lin_nopresenta=$cSepara.$aEstudiante[$k]['nopresenta'];
		$lin_tutor='';
		if ($idTutor==''){
			$idTutorEst=$aEstudiante[$k]['tutor'];
			if ($idTutorEst==0){
				$lin_tutor=$cSepara.'[Sin Tutor]';
				}else{
				if (isset($aTutor[$idTutorEst])==0){
					$sSQL='SELECT unad11razonsocial FROM unad11terceros WHERE unad11id='.$idTutorEst.'';
					$tablae=$objDB->ejecutasql($sSQL);
					if ($objDB->nf($tablae)>0){
						$filae=$objDB->sf($tablae);
						$aTutor[$idTutorEst]=str_replace($cSepara, $cComplementa, utf8_decode($filae['unad11razonsocial']));
						}else{
						$aTutor[$idTutorEst]='{'.$idTutorEst.'}';
						}
					}
				$lin_tutor=$cSepara.$aTutor[$idTutorEst];
				}
			}
		$sBloque1=$lin_core04idgrupo.$lin_unad11tipodoc.$lin_unad11doc.$lin_unad11razonsocial.$lin_core04estado.$lin_act.$lin_nota75.$lin_nota25.$lin_notaacum.$lin_nopresenta.$lin_tutor;
		$objplano->AdicionarLinea($sBloque1);
		}
	$objDB->CerrarConexion();
	$objplano->Generar();
	header('Content-Description: File Transfer');
	header('Content-Type: text/csv');
	header('Content-Length: '.filesize($sPath.$sNombrePlano));
	header('Content-Disposition: attachment; filename='.basename($sNombrePlanoFinal));
	readfile($sPath.$sNombrePlano);
	}
?>