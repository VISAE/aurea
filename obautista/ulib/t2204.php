<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2016 - 2019 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- saul.hernandez@unad.edu.co - http://www.unad.edu.co
--- Modelo Version 2.12.5b sábado, 25 de septiembre de 2019
*/
error_reporting(E_ALL);
ini_set("display_errors", 1);
//if (file_exists('./err_control.php')){require './err_control.php';}
if (!file_exists('./app.php')){
	echo '<b>Error N 1 de instalaci&oacute;n</b><br>No se ha establecido un archivo de configuraci&oacute;n, por favor comuniquese con el administrador del sistema.';
	die();
	}
mb_internal_encoding('UTF-8');
require './app.php';
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
$bEntra=true;
$bDebug=false;
if (isset($_REQUEST['v3'])==0){$_REQUEST['v3']='';}
if (isset($_REQUEST['v4'])==0){$_REQUEST['v4']='';}
if (isset($_REQUEST['v5'])==0){$_REQUEST['v5']='';}
if (isset($_REQUEST['v6'])==0){$_REQUEST['v6']='';}
if (isset($_REQUEST['v7'])==0){$_REQUEST['v7']='';}
if (isset($_REQUEST['rdebug'])==0){$_REQUEST['rdebug']=0;}
$idPeriodo=numeros_validar($_REQUEST['v3']);
$idCurso=numeros_validar($_REQUEST['v4']);
$sTipo=numeros_validar($_REQUEST['v5']);
$sFiltro=numeros_validar($_REQUEST['v6']);
$sPrograma=numeros_validar($_REQUEST['v7']);
if ($idPeriodo==''){$sError='No ha seleccionado un periodo';}
if ($idCurso==''){$sError='No ha seleccionado un curso';}
if ($sError!=''){$bEntra=false;}
if ($bEntra){
	if ($_REQUEST['rdebug']==1){$bDebug=true;}
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$sNomTipo='Reporte de matricula';
	if ($sTipo!=''){
		$iTipo=numeros_validar($sTipo);
		$bEntra=false;
		$sSQL='SELECT ofer08incluyelaboratorio, ofer08incluyesalida 
FROM ofer08oferta 
WHERE ofer08idper_aca='.$idPeriodo.' AND ofer08idcurso='.$idCurso.' AND ofer08cead=0 ';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$fila=$objDB->sf($tabla);
			if ($iTipo==0){
				if ($fila['ofer08incluyelaboratorio']=='S'){
					$sNomTipo='Reporte de inscripciones a laboratorios';
					$bEntra=true;
					}else{
					$sError='El curso '.$idCurso.' no oferta laboratorios en este periodo.';
					}
				}
			if ($iTipo==1){
				if ($fila['ofer08incluyesalida']=='S'){
					$bEntra=true;
					$sNomTipo='Reporte de inscripciones a salidas de campo';
					}else{
					$sError='El curso '.$idCurso.' no oferta Salidas de campo en este periodo.';
					}
				}
			}else{
			$sError='No se encuentra oferta para el curso '.$idCurso.' en el periodo '.$idPeriodo.'';
			}
		}
	}
if ($bEntra){
	if ($sTipo!=''){
		//Encontrar la actividad que esta asociada.
		$sSQL='SELECT ofer18idactividad, ofer18peso FROM ofer18cargaxnavxdia WHERE ofer18per_aca='.$idPeriodo.' AND ofer18curso='.$idCurso.'  AND ofer18numaula=1 AND ofer18origennota='.($iTipo+2).'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$fila=$objDB->sf($tabla);
			$idActividad=$fila['ofer18idactividad'];
			$iPesoActividad=$fila['ofer18peso'];
			}else{
			$bEntra=false;
			$sTipoActividad='Laboratorio';
			if ($iTipo==1){$sTipoActividad='Salida de campo';}
			$sError='El curso '.$idCurso.' NO tiene asociada una actividad de '.$sTipoActividad.' en el Centralizador de Calificaciones [Modulo Procesos - Revisi&oacute;n de agendas].';
			}
		}
	}
if ($bEntra){
	$sNomPeriodo='';
	$sNomCurso='';
	$sSQL='SELECT exte02nombre FROM exte02per_aca WHERE exte02id='.$idPeriodo;
	$tablat=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tablat)>0){
		$filat=$objDB->sf($tablat);
		$sNomPeriodo='Periodo: '.utf8_decode($filat['exte02nombre']);
		}
	$sSQL='SELECT unad40titulo, unad40nombre FROM unad40curso WHERE unad40id='.$idCurso;
	$tablat=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tablat)>0){
		$filat=$objDB->sf($tablat);
		$sNomCurso='Curso: '.$filat['unad40titulo'].' - '.utf8_decode($filat['unad40nombre']);
		}
	$cSepara=',';
	$cEvita=';';
	$cComplementa='.';
	if (isset($_REQUEST['separa'])!=0){
		if ($_REQUEST['separa']==';'){
			$cSepara=';';
			$cEvita=',';
			}
		}
	$aCead=array();
	$aPrograma=array();
	$aEstado=array('Matricula Normal', '', '', '', '', '', '', '', 'Aplazado', 'Cancelado');
	$sPath=dirname(__FILE__);
	$sSeparador=archivos_separador($sPath);
	$sPath=archivos_rutaservidor($sPath,$sSeparador);
	$sNombrePlano='t2204.csv';
	//$sTituloRpt=$sNomTipo;
	//$sTituloRpt=cadena_Reemplazar($sNomTipo, '', '_');
	$sTituloRpt='Reporte_Matricula_'.$idPeriodo.'_'.$idCurso.'';
	$sNombrePlanoFinal=$sTituloRpt.'.csv';
	$objplano=new clsPlanos($sPath.$sNombrePlano);
	$sDato='UNIVERSIDAD NACIONAL ABIERTA Y A DISTANCIA - UNAD';
	$objplano->AdicionarLinea($sDato);
	if ($sPrograma!=''){
		$sNomTipo=$sNomTipo.' (Parcial)';
		}
	$objplano->AdicionarLinea($sNomTipo);
	$objplano->AdicionarLinea($sNomPeriodo);
	$objplano->AdicionarLinea($sNomCurso);
	$sDato='Documento'.$cSepara.'Estudiante'.$cSepara.'Zona'.$cSepara.'CEAD'.$cSepara.'Escuela'.$cSepara.'Programa'.$cSepara.'Estado Matricula';
	if ($sTipo!=''){
		$sDato=$sDato.$cSepara.'N de cupo'.$cSepara.'Nota'.$cSepara.'Fecha Nota';
		}
	$objplano->AdicionarLinea($sDato);
	
	$idContPeraca=f146_Contenedor($idPeriodo, $objDB);
	$sTabla51='';
	$sCondiGrupo='';
	$sCondiEstudiante='';
	
	$sSQL='SHOW TABLES LIKE "core04%"';
	$sSQLBase='';
	if ($sTipo==''){
		}else{
		if ($sFiltro=='1'){$sCondiGrupo=' AND T5.core05idcupolab=0';}//Poblacion no inscritos
		if ($sFiltro=='2'){$sCondiGrupo=' AND T5.core05idcupolab>0';}//Poblacion  inscritos
		}
	if ($sPrograma!=''){
		$sCondiGrupo=' AND TB.core04idprograma='.$sPrograma.'';
		}
	if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Total Periodo: Lista de contenedores: '.$sSQL.'<br>';}
	
	$tablac=$objDB->ejecutasql($sSQL);
	while($filac=$objDB->sf($tablac)){
		$iContenedor=substr($filac[0], 16);
		$sTabla06='core06grupos_'.$idContPeraca;
		if ($sSQLBase!=''){$sSQLBase=$sSQLBase.' UNION ';}
		//TB.core05idtutor='.$idTutor.' AND 
		if ($sTipo==''){
			//Solo matricula
			$sSQLBase=$sSQLBase.'SELECT T11.unad11doc, T11.unad11razonsocial, TB.core04idcead, TB.core04idprograma, TB.core04estado, ('.$iContenedor.') AS idContenedor 
FROM core04matricula_'.$iContenedor.' AS TB, unad11terceros AS T11'.$sTabla51.' 
WHERE TB.core04idcurso='.$idCurso.' AND TB.core04peraca='.$idPeriodo.' '.$sCondiGrupo.$sCondiEstudiante.' AND TB.core04tercero=T11.unad11id';
			}else{
			//Inscripciones
			$sSQLBase=$sSQLBase.'SELECT T11.unad11doc, T11.unad11razonsocial, TB.core04idcead, TB.core04idprograma, TB.core04estado, ('.$iContenedor.') AS idContenedor, T5.core05idcupolab, T5.core05estado, T5.core05nota, T5.core05fechanota, T5.core05estado, T5.core05id, T5.core05calificado, T5.core05tercero, T5.core05idgrupo 
FROM core04matricula_'.$iContenedor.' AS TB, core05actividades_'.$iContenedor.' AS T5, unad11terceros AS T11'.$sTabla51.' 
WHERE TB.core04idcurso='.$idCurso.' AND TB.core04peraca='.$idPeriodo.' AND TB.core04id=T5.core05idmatricula AND T5.core05idactividad='.$idActividad.' '.$sCondiGrupo.$sCondiEstudiante.' AND TB.core04tercero=T11.unad11id';
			}
		}
	$sSQL=$sSQLBase.' ORDER BY unad11doc';
	//$objplano->AdicionarLinea('Consulta: '.$sSQL);
	$tabladetalle=$objDB->ejecutasql($sSQL);
	if ($tabladetalle==false){
		if ($bDebug){
			$objplano->AdicionarLinea('Error al ejecutar: '.$sSQL);
			}
		}
	while($filadet=$objDB->sf($tabladetalle)){
		$sComplemento='';
		if ($sTipo!=''){
			$et_nota='';
			$et_fecha='';
			$sPorcentaje='';
			if ($filadet['core05fechanota']!=0){
				$et_nota=formato_numero($filadet['core05nota'], 0).' / '.$iPesoActividad;//
				$et_fecha=fecha_desdenumero($filadet['core05fechanota']);
				$sPorcentaje='0';
				if ($filadet['core05nota']!=0){
					$sPorcentaje=formato_numero(($filadet['core05nota']/$iPesoActividad)*100, 0);
					}
				}
			if ($filadet['core05idcupolab']!=0){
				$sLink=($filadet['core05idcupolab']);
				//$sLink='Inscrito';
				}else{
				$sLink='No inscrito';
				}
			$sComplemento=$cSepara.$sLink.$cSepara.$et_nota.$cSepara.$et_fecha;
			}
		$i_core04idcead=$filadet['core04idcead'];
		if (isset($aCead[$i_core04idcead])==0){
			$sSQL='SELECT TB.unad24nombre, TB.unad24idzona, T23.unad23nombre FROM unad24sede AS TB, unad23zona AS T23 WHERE TB.unad24id='.$i_core04idcead.' AND TB.unad24idzona=T23.unad23id';
			$tablae=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae)>0){
				$filae=$objDB->sf($tablae);
				$aCead[$i_core04idcead]=str_replace($cSepara, $cComplementa, $filae['unad23nombre']).$cSepara.str_replace($cSepara, $cComplementa, $filae['unad24nombre']);
				}else{
				$aCead[$i_core04idcead]=''.$cSepara.'{'.$filadet['core04idcead'].'}';
				}
			}
		$sCead=$cSepara.utf8_decode($aCead[$i_core04idcead]);
		$i_core04idprograma=$filadet['core04idprograma'];
		if (isset($aPrograma[$i_core04idprograma])==0){
			$sSQL='SELECT TB.core09nombre, TB.core09idescuela, T23.core12nombre FROM core09programa AS TB, core12escuela AS T23 WHERE TB.core09id='.$i_core04idprograma.' AND TB.core09idescuela=T23.core12id';
			$tablae=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae)>0){
				$filae=$objDB->sf($tablae);
				$aPrograma[$i_core04idprograma]=str_replace($cSepara, $cComplementa, $filae['core12nombre']).$cSepara.str_replace($cSepara, $cComplementa, $filae['core09nombre']);
				}else{
				$aPrograma[$i_core04idprograma]=''.$cSepara.'{'.$filadet['core04idprograma'].'}';
				}
			}
		$sPrograma=$cSepara.utf8_decode($aPrograma[$i_core04idprograma]);
		$sEstado=$cSepara.'{'.$filadet['core04estado'].'}';
		if (isset($aEstado[$filadet['core04estado']])!=0){
			$sEstado=$cSepara.''.$aEstado[$filadet['core04estado']].'';
			}
		$sDato=$filadet['unad11doc'].$cSepara.utf8_decode($filadet['unad11razonsocial']).$sCead.$sPrograma.$sEstado.$sComplemento;
		$objplano->AdicionarLinea($sDato);
		
		}
	$objDB->CerrarConexion();
	$objplano->Generar();
	header('Content-Description: File Transfer');
	header('Content-Type: text/csv');
	header('Content-Length: '.filesize($sPath.$sNombrePlano));
	header('Content-Disposition: attachment; filename='.basename($sNombrePlanoFinal));
	readfile($sPath.$sNombrePlano);
	}else{
	echo $sError;
	}
?>