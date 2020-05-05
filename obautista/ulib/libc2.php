<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2019 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
*/
function f2216_RetirarAgendas($idPeraca, $idCurso, $objDB, $bDebug=false){
	$sDebug='';
	$sError='';
	
	$aIds=array();
	$sId11='-99';
	$sSQL='SHOW TABLES LIKE "core04%"';
	if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Total Periodo: Lista de contenedores: '.$sSQL.'<br>';}
	$tablac=$objDB->ejecutasql($sSQL);
	while($filac=$objDB->sf($tablac)){
		$iContenedor=substr($filac[0], 16);
		$sId04='-99';
		$sTabla04='core04matricula_'.$iContenedor;
		$sTabla05='core05actividades_'.$iContenedor;
		$sSQL4='SELECT TB.core04id, TB.core04idmatricula, TB.core04tercero 
FROM '.$sTabla04.' AS TB 
WHERE TB.core04peraca='.$idPeraca.' AND TB.core04idcurso='.$idCurso.'';
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Matricula del curso. '.$sSQL4.'<br>';}
		$tabla04=$objDB->ejecutasql($sSQL4);
		while($fila04=$objDB->sf($tabla04)){
			$sId11=$sId11.','.$fila04['core04tercero'];
			$sId04=$sId04.','.$fila04['core04id'];
			}
		//Confirmar que no hayan actividades calificadas o reportadas.
		$sSQL='SELECT 1 FROM '.$sTabla05.' WHERE core05idmatricula IN ('.$sId04.') AND core05estado IN (7, 8)';
		$tabla05=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla05)>0){
			$sError='Ya se han calificado actividades en esta agenda, no se permite eliminar.';
			}else{
			$aIds[$iContenedor]=$sId04;
			}
		}
	if ($sError==''){
		$sSQL='SHOW TABLES LIKE "core04%"';
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Total Periodo: Lista de contenedores: '.$sSQL.'<br>';}
		$tablac=$objDB->ejecutasql($sSQL);
		while($filac=$objDB->sf($tablac)){
			$iContenedor=substr($filac[0], 16);
			$sTabla04='core04matricula_'.$iContenedor;
			$sTabla05='core05actividades_'.$iContenedor;
			$sId04=$aIds[$iContenedor];
			//QUITA LAS ACTIVIDADES.
			$sSQL='DELETE FROM '.$sTabla05.' WHERE core05idmatricula IN ('.$sId04.')';
			$result=$objDB->ejecutasql($sSQL);
			//ACTUALIZA LA TABLA DE MATRICULA
			$sSQL='UPDATE '.$sTabla04.' SET core04aplicoagenda=0 WHERE core04id IN ('.$sId04.')';
			$result=$objDB->ejecutasql($sSQL);
			}
		//Desmarcar para que sean procesadas nuevamente en el siguiente cron...
		$sSQL='UPDATE core16actamatricula SET core16procagenda=0, core16erroragenda=0 WHERE core16peraca='.$idPeraca.' AND core16tercero IN ('.$sId11.')';
		$result=$objDB->ejecutasql($sSQL);
		}
	return array($sError, $sDebug);
	}
function f2400_CalcularEdades($idPeraca, $objDB, $bDebug=false, $bForzar=false){
	$sError='';
	$sDebug='';
	$aCont=array();
	$iCont=0;
	//Primero Ubicamos el - los periodos
	$sIds='-99,'.$idPeraca;
	if ($idPeraca==0){
		$sIds='-99';
		$sSQL='SHOW TABLES LIKE "core04%"';
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Total Periodo: Lista de contenedores: '.$sSQL.'<br>';}
		$tablac=$objDB->ejecutasql($sSQL);
		while($filac=$objDB->sf($tablac)){
			$iContenedor=substr($filac[0], 16);
			$iCont++;
			$aCont[$iCont]=$iContenedor;
			$sSQL='SELECT core04peraca FROM core04matricula_'.$iContenedor.' WHERE core04edad=0 AND core04peraca NOT IN ('.$sIds.') GROUP BY core04peraca';
			$tabla=$objDB->ejecutasql($sSQL);
			while($fila=$objDB->sf($tabla)){
				$sIds=$sIds.','.$fila['core04peraca'];
				}
			}
		//Agregalos los periodos de la matricula.
		$sSQL='SELECT core16peraca FROM core16actamatricula WHERE core16edad=0 AND core16peraca NOT IN ('.$sIds.') GROUP BY core16peraca';
		$tabla=$objDB->ejecutasql($sSQL);
		while($fila=$objDB->sf($tabla)){
			$sIds=$sIds.','.$fila['core16peraca'];
			}
		}
	$sCondiEdad=' AND TB.core04edad=0 ';
	$sCondiEdad2=' AND TB.core16edad=0 ';
	$sWhere4=' AND TB.core04tercero=T11.unad11id AND T11.unad11fechanace<>"00/00/0000" AND T11.unad11fechanace<>"" AND ((SUBSTRING(T11.unad11fechanace, 4,2)*100)+(SUBSTRING(T11.unad11fechanace, 1,2)))';
	$sWhere16=' AND TB.core16tercero=T11.unad11id AND T11.unad11fechanace<>"00/00/0000" AND T11.unad11fechanace<>"" AND ((SUBSTRING(T11.unad11fechanace, 4,2)*100)+(SUBSTRING(T11.unad11fechanace, 1,2)))';
	$sSQL='SELECT ofer14idper_aca, SUBSTRING(ofer14fechafin60, 7,4) AS Agno, ((SUBSTRING(ofer14fechafin60, 4,2)*100)+(SUBSTRING(ofer14fechafin60, 1,2))) AS Dia, ofer14fechafin60 FROM ofer14per_acaparams WHERE ofer14idper_aca IN ('.$sIds.') AND ofer14fechafin60<>"00/00/0000"';
	if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Datos base: '.$sSQL.'<br>';}
	$tabla=$objDB->ejecutasql($sSQL);
	while($fila=$objDB->sf($tabla)){
		for ($j=1;$j<=$iCont;$j++){
			$iContenedor=$aCont[$j];
			$sSQL='UPDATE core04matricula_'.$iContenedor.' AS TB, unad11terceros AS T11
SET TB.core04edad=('.$fila['Agno'].'-SUBSTRING(T11.unad11fechanace, 7,4))
WHERE TB.core04peraca='.$fila['ofer14idper_aca'].' '.$sCondiEdad.''.$sWhere4.'<='.$fila['Dia'].'';
			if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Actualizando edades 1: '.$sSQL.'<br>';}
			$result=$objDB->ejecutasql($sSQL);
			$sSQL='UPDATE core04matricula_'.$iContenedor.' AS TB, unad11terceros AS T11
SET TB.core04edad=('.$fila['Agno'].'-SUBSTRING(T11.unad11fechanace, 7,4)-1)
WHERE TB.core04peraca='.$fila['ofer14idper_aca'].' '.$sCondiEdad.''.$sWhere4.'>'.$fila['Dia'].'';
			if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Actualizando edades 2: '.$sSQL.'<br>';}
			$result=$objDB->ejecutasql($sSQL);
			}
		//ahora en las actas de matricula...
		$sSQL='UPDATE core16actamatricula AS TB, unad11terceros AS T11
SET TB.core16edad=('.$fila['Agno'].'-SUBSTRING(T11.unad11fechanace, 7,4))
WHERE TB.core16peraca='.$fila['ofer14idper_aca'].' '.$sCondiEdad2.''.$sWhere16.'<='.$fila['Dia'].'';
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Actualizando edades Matricula 1: '.$sSQL.'<br>';}
		$result=$objDB->ejecutasql($sSQL);
		$sSQL='UPDATE core16actamatricula AS TB, unad11terceros AS T11
SET TB.core16edad=('.$fila['Agno'].'-SUBSTRING(T11.unad11fechanace, 7,4)-1)
WHERE TB.core16peraca='.$fila['ofer14idper_aca'].' '.$sCondiEdad2.''.$sWhere16.'>'.$fila['Dia'].'';
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Actualizando edades Matricula 2: '.$sSQL.'<br>';}
		$result=$objDB->ejecutasql($sSQL);
		}
	return array($sError, $sDebug);
	}
function f2400_AvanceEstudianteCurso($idTercero, $idPeraca, $idCurso, $objDB, $idContTercero=0, $bDebug=true){
	$sError='';
	$sDebug='';
	$iPuntaje=-1;
	$iAprueba=300;
	$iNumCreditos=0;
	if ($idContTercero==0){
		list($idContTercero, $sError)=f1011_BloqueTercero($idTercero, $objDB);
		}
	if ($sError==''){
		$sSQL='SELECT TB.core04id, TB.core04estado, TB.core04fechaultacceso, TB.core04calificado, TB.core04nota75, TB.core04nota25, TB.core04est_aprob, TB.core04notafinal 
FROM core04matricula_'.$idContTercero.' AS TB 
WHERE TB.core04peraca='.$idPeraca.' AND TB.core04idcurso='.$idCurso.' AND TB.core04tercero='.$idTercero.'';
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consultando avance academico del curso: '.$sSQL.'<br>';}
		$tabla=$objDB->ejecutasql($sSQL);
		while($fila=$objDB->sf($tabla)){
			//0 Disponible en campus, 1 No disponible campus, 5 Reportado 75% 7 Calificado, 9 Retirado
			$iAprueba=$fila['core04est_aprob']*100;
			if ($fila['core04estado']!=9){
				//Se excluyen los cursos retirados (aplazados o cancelados)
				$iPuntaje=0;
				if ($fila['core04calificado']>0){
					//Ver que tanto aprueba...
					$iPuntaje=(int)$fila['core04nota75']+(int)$fila['core04nota25'];
					}
				}
			}
		}
	if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Avance academico del curso: '.$idCurso.' = '.$iPuntaje.' / '.$iAprueba.'<br>';}
	return array($iPuntaje, $iAprueba, $iNumCreditos, $idContTercero, $sDebug);
	}
function f2400_AvanceEstudiantePeriodo($idTercero, $idPeraca, $iFechaConsulta, $objDB, $idContTercero=0, $bDebug=true){
	$sError='';
	$sDebug='';
	$iTotalCursos=0;
	$iCursosSinIngreso=0;
	$iCursosPerdidos=0;
	$iNumCreditos=0;
	$iNumAprobados=0;
	if ($idContTercero==0){
		list($idContTercero, $sError)=f1011_BloqueTercero($idTercero, $objDB);
		}
	if ($sError==''){
		if ($iFechaConsulta==0){$iFechaConsulta=fecha_DiaMod();}
		$iFechaAccesos=fecha_NumSumarDias($iFechaConsulta, 10);
		$sSQL='SELECT TB.core04idcurso, TB.core04id, TB.core04estado, TB.core04fechaultacceso, TB.core04calificado, TB.core04nota75, TB.core04nota25, TB.core04est_aprob, TB.core04notafinal 
FROM core04matricula_'.$idContTercero.' AS TB 
WHERE TB.core04peraca='.$idPeraca.' AND TB.core04tercero='.$idTercero.'';
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consultando avance acedemico: '.$sSQL.'<br>';}
		$tabla=$objDB->ejecutasql($sSQL);
		while($fila=$objDB->sf($tabla)){
			//0 Disponible en campus, 1 No disponible campus, 5 Reportado 75% 7 Calificado, 9 Retirado
			if ($fila['core04estado']!=9){
				//Se excluyen los cursos retirados (aplazados o cancelados)
				$iTotalCursos++;
				if ($fila['core04calificado']>0){
					//Ver que tanto aprueba...
					$iFactor=($fila['core04est_aprob']/5);
					$iAprueba=($fila['core04nota75']+$fila['core04nota25'])/$fila['core04calificado'];
					if ($iAprueba>=$iFactor){
						}else{
						$iCursosPerdidos++;
						if ($fila['core04fechaultacceso']<$iFechaAccesos){$iCursosSinIngreso++;}
						}
					}else{
					$iCursosPerdidos++;
					if ($fila['core04fechaultacceso']<$iFechaAccesos){$iCursosSinIngreso++;}
					}
				}
			}
		}
	return array($iTotalCursos, $iCursosSinIngreso, $iCursosPerdidos, $iNumCreditos, $iNumAprobados, $sDebug);
	}
function f2401_PeriodosTercero($idTercero, $objDB, $bDebug=false){
	$sDebug='';
	$sIds='-99';
	$bTotal=false;
	$sSQL='SELECT 1 FROM core09programa WHERE core09iddirector='.$idTercero.'';
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$bTotal=true;
		}else{
		//Mirar si es un decano.
		$sSQL='SELECT 1 FROM core12escuela WHERE core12tieneestudiantes="S" AND ((core12iddecano='.$idTercero.') OR (core12idadministrador='.$idTercero.'))';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$bTotal=true;
			}
		}
	if ($bTotal){
		//Es lider de programa o decano, pues... todos los peracas que tengan oferta superior al 610
		$sSQL='SELECT ofer08idper_aca FROM ofer08oferta AS TB WHERE ofer08idper_aca>610 AND ofer08estadooferta=1 GROUP BY ofer08idper_aca';
		$tabla=$objDB->ejecutasql($sSQL);
		while($fila=$objDB->sf($tabla)){
			$sIds=$sIds.','.$fila['ofer08idper_aca'];
			}
		}else{
		$sSQL='SELECT TB.core20idperaca 
FROM core20asignacion AS TB
WHERE core20idtutor='.$idTercero.'
GROUP BY TB.core20idperaca';
		$tabla=$objDB->ejecutasql($sSQL);
		while($fila=$objDB->sf($tabla)){
			$sIds=$sIds.','.$fila['core20idperaca'];
			}
		//Ahora donde el se;or es director de curso.
		$sSQL='SELECT ofer08idper_aca FROM ofer08oferta AS TB WHERE ofer08idacomanamento='.$idTercero.' AND ofer08idper_aca>610 GROUP BY ofer08idper_aca';
		$tabla=$objDB->ejecutasql($sSQL);
		while($fila=$objDB->sf($tabla)){
			$sIds=$sIds.','.$fila['ofer08idper_aca'];
			}
		}
	return array($sIds, $sDebug);
	}
function f2401_CursosTercero($idTercero, $objDB, $bDebug=false){
	$sDebug='';
	$sIds='-99';
	$sSQL='SELECT core09id FROM core09programa WHERE core09iddirector='.$idTercero.'';
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$sIdsProgramas='-99';
		while ($fila=$objDB->sf($tabla)){
			$sIdsProgramas=$sIdsProgramas.','.$fila['core09id'];
			}
		$sSQL='SELECT ofer08idcurso FROM ofer08oferta AS TB WHERE ofer08idprograma IN ('.$sIdsProgramas.') AND ofer08idper_aca>610 GROUP BY ofer08idcurso';
		$tabla=$objDB->ejecutasql($sSQL);
		while($fila=$objDB->sf($tabla)){
			$sIds=$sIds.','.$fila['ofer08idcurso'];
			}
		}
	//Mirar si es un decano.
	$sSQL='SELECT core12id FROM core12escuela WHERE core12tieneestudiantes="S" AND ((core12iddecano='.$idTercero.') OR (core12idadministrador='.$idTercero.'))';
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$sIdsEscuela='-99';
		while ($fila=$objDB->sf($tabla)){
			$sIdsEscuela=$sIdsEscuela.','.$fila['core12id'];
			}
		$sSQL='SELECT ofer08idcurso FROM ofer08oferta AS TB WHERE ofer08idescuela IN ('.$sIdsEscuela.') AND ofer08idper_aca>610 GROUP BY ofer08idcurso';
		$tabla=$objDB->ejecutasql($sSQL);
		while($fila=$objDB->sf($tabla)){
			$sIds=$sIds.','.$fila['ofer08idcurso'];
			}
		}
	$sSQL='SELECT TB.core20idcurso FROM core20asignacion AS TB WHERE TB.core20idtutor='.$idTercero.' GROUP BY TB.core20idcurso';
	$tabla=$objDB->ejecutasql($sSQL);
	while($fila=$objDB->sf($tabla)){
		$sIds=$sIds.','.$fila['core20idcurso'];
		}
	$sSQL='SELECT ofer08idcurso FROM ofer08oferta AS TB WHERE ofer08idacomanamento='.$idTercero.' AND ofer08idper_aca>610 GROUP BY ofer08idcurso';
	$tabla=$objDB->ejecutasql($sSQL);
	while($fila=$objDB->sf($tabla)){
		$sIds=$sIds.','.$fila['ofer08idcurso'];
		}
	return array($sIds, $sDebug);
	}
function f2401_ConsolidarNotas($idPeraca, $idCurso, $objDB, $bDebug=false){
	$sError='';
	$sDebug='';
	//$aAulas=array('','A','B','C','D','E');
	$sSQL='SHOW TABLES LIKE "core04%"';
	if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Total Periodo: Lista de contenedores: '.$sSQL.'<br>';}
	$tablac=$objDB->ejecutasql($sSQL);
	while($filac=$objDB->sf($tablac)){
		$iContenedor=substr($filac[0], 16);
		//Llevar la nota de la tabla 5 a la 4
		$sSQL='UPDATE core04matricula_'.$iContenedor.' AS T4 
SET T4.core04nota75=(SELECT SUM(TB.core05acumula75) FROM core05actividades_'.$iContenedor.' AS TB WHERE TB.core05idmatricula=T4.core04id), 
T4.core04nota25=(SELECT SUM(TC.core05acumula25) FROM core05actividades_'.$iContenedor.' AS TC WHERE TC.core05idmatricula=T4.core04id), 
T4.core04calificado=(SELECT SUM(TD.core05calificado) FROM core05actividades_'.$iContenedor.' AS TD WHERE TD.core05idmatricula=T4.core04id)
WHERE T4.core04idcurso='.$idCurso.' AND T4.core04peraca='.$idPeraca.'';
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Totalizar la nota al curso '.$sSQL.' <br>';}
		$result=$objDB->ejecutasql($sSQL);
		}
	return array($sError, $sDebug);
	}
function f2401_CerrarActividad($idPeraca, $idCurso, $idActividad, $idTutor, $objDB, $bDebug=false, $bReconstruye=0){
	$sError='';
	$sDebug='';
	$sCodActividadMoodle='';
	$sRevActividades='"xxx"';
	$bRevOrigenUno=false;
	$aConfig=array();
	if ($sError==''){
		//Espacio para las comprobaciones blockchain
		if ($bReconstruye==0){
			sleep(5);
			}
		//Fin de las comprobaciones...
		}
	if ($sError==''){
		$iValor75=0;
		$iValor25=0;
		$iPuntajeTotal=0;
		$sCampoAcumula='core05acumula75';
		$sInversos='core05acumula25';
		//Mirar en la tabla ofer18 a ver si tiene una actividad.
		$sSQL='SELECT TB.ofer18peso, TB.ofer18origennota, T3.ofer03idmomento 
FROM ofer18cargaxnavxdia AS TB, ofer03cursounidad AS T3 
WHERE TB.ofer18curso='.$idCurso.' AND TB.ofer18per_aca='.$idPeraca.' AND TB.ofer18idactividad='.$idActividad.' AND TB.ofer18numaula=1 AND TB.ofer18unidad=T3.ofer03id
';
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Buscando actividad en la agenda: '.$sSQL.'<br>';}
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$fila=$objDB->sf($tabla);
			if ($fila['ofer18origennota']==1){$bRevOrigenUno=true;}
			$iPuntajeTotal=$fila['ofer18peso'];
			if ($fila['ofer03idmomento']==2){
				$iValor25=$iPuntajeTotal;
				$sCampoAcumula='core05acumula25';
				$sInversos='core05acumula75';
				}else{
				$iValor75=$iPuntajeTotal;
				}
			}
		}
	if ($iPuntajeTotal==0){
		$sError='Esta actividad no tiene marcado un puntaje.';
		}
	$bHayDBCentral=false;
	if ($sError==''){
		//Si el idTutor viene en 0, es posible que se este cerrando desde laboratorios y que los tutores no hayan ingresado al centralizador.
		//Por tanto para evitar molestias, se actualiza el listado de actividades de los tutores.
		$idContPeraca=f146_Contenedor($idPeraca, $objDB);
		$sTabla06='core06grupos_'.$idContPeraca;
		$sSQL='SELECT core06idtutor FROM '.$sTabla06.' WHERE core06peraca='.$idPeraca.' AND core06idcurso='.$idCurso.' AND core06idtutor>0 GROUP BY core06idtutor';
		$tabla06=$objDB->ejecutasql($sSQL);
		while($fila06=$objDB->sf($tabla06)){
			list($sErrorT, $sDebugT)=f2402_CargarActividades($fila06['core06idtutor'], $idPeraca, $idCurso, $objDB, true, $bDebug);
			$sDebug=$sDebug.$sDebugT;
			}
		}
	if ($sError==''){
		$iActualizados=0;
		$iHoy=fecha_DiaMod();
		$sSQL='SHOW TABLES LIKE "core04%"';
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Actulizando actividad: Lista de contenedores: '.$sSQL.'<br>';}
		$tablac=$objDB->ejecutasql($sSQL);
		while($filac=$objDB->sf($tablac)){
			$iContenedor=substr($filac[0], 16);
			//Marcar el valor de las actividades.
			$sSQL='UPDATE core05actividades_'.$iContenedor.' AS TB 
SET TB.core05nota='.$iPuntajeTotal.'
WHERE TB.core05idcurso='.$idCurso.' AND TB.core05peraca='.$idPeraca.' AND TB.core05idactividad='.$idActividad.' AND TB.core05nota>'.$iPuntajeTotal.'';
			if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Verificando que no haya notas desproporcionadas '.$sSQL.' <br>';}
			$result=$objDB->ejecutasql($sSQL);
			//Descabezarlas notas desproporcionadas.
			$sSQL='UPDATE core05actividades_'.$iContenedor.' AS TB 
SET TB.core05puntaje75='.$iValor75.', TB.core05puntaje25='.$iValor25.'
WHERE TB.core05idcurso='.$idCurso.' AND TB.core05peraca='.$idPeraca.' AND TB.core05idactividad='.$idActividad.' AND ((TB.core05puntaje75<>'.$iValor75.') OR (core05puntaje25<>'.$iValor25.'))';
			if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Actualizando puntajes '.$sSQL.' <br>';}
			$result=$objDB->ejecutasql($sSQL);
			//Reportar las notas y los puntajes.
			$sCondiTutor='';
			if ($idTutor!=0){
				$sCondiTutor='TB.core05idtutor='.$idTutor.' AND ';
				}
			$sSQL='UPDATE core05actividades_'.$iContenedor.' AS TB 
SET TB.core05estado=7, TB.'.$sCampoAcumula.'=TB.core05nota, '.$sInversos.'=0 
WHERE '.$sCondiTutor.'TB.core05idcurso='.$idCurso.' AND TB.core05peraca='.$idPeraca.' AND TB.core05idactividad='.$idActividad.' AND TB.core05rezagado=0 AND TB.core05estado IN (3, 7)';
			if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Poner la calificacion '.$sSQL.' <br>';}
			$result=$objDB->ejecutasql($sSQL);
			//Reportar los puntajes de los NO PRESENTADOS
			$sSQL='UPDATE core05actividades_'.$iContenedor.' AS TB 
SET TB.'.$sCampoAcumula.'=TB.core05nota 
WHERE '.$sCondiTutor.'TB.core05idcurso='.$idCurso.' AND TB.core05peraca='.$idPeraca.' AND TB.core05idactividad='.$idActividad.' AND TB.core05rezagado=0 AND TB.core05estado=5';
			if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Calificacion de los no presentados'.$sSQL.' <br>';}
			$result=$objDB->ejecutasql($sSQL);
			//Reportar que esta calificado
			$sSQL='UPDATE core05actividades_'.$iContenedor.' AS TB 
SET TB.core05calificado='.$iPuntajeTotal.' 
WHERE '.$sCondiTutor.'TB.core05idcurso='.$idCurso.' AND TB.core05peraca='.$idPeraca.' AND TB.core05idactividad='.$idActividad.' AND TB.core05rezagado=0 AND TB.core05estado IN (3, 5, 7)';
			if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Reportar la actividad como calificada '.$sSQL.' <br>';}
			$result=$objDB->ejecutasql($sSQL);
			}
		list($sErrorC, $sDebugC)=f2401_ConsolidarNotas($idPeraca, $idCurso, $objDB, $bDebug);
		//Reportar que el tutor califico la actividad.
		$sCondiTutor='';
		if ($idTutor!=0){
			$sCondiTutor='TB.ceca02idtutor='.$idTutor.' AND ';
			}
		//El condicional de estado se aplica por que se puede estar haciendo una reconstruccion de la actividad.
		$sSQL='UPDATE ceca02actividadtutor_'.$idContPeraca.' AS TB 
SET TB.ceca02idestado=7, TB.ceca02idcierra='.$_SESSION['unad_id_tercero'].', TB.ceca02fechacierre='.fecha_DiaMod().', TB.ceca02mincierra='.fecha_MinutoMod().' 
WHERE '.$sCondiTutor.'TB.ceca02idperaca='.$idPeraca.' AND TB.ceca02idcurso='.$idCurso.' AND TB.ceca02idactividad='.$idActividad.' AND TB.ceca02idestado<>7';
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Se marca la actividad como calificada '.$sSQL.' <br>';}
		$result=$objDB->ejecutasql($sSQL);
		}
	return array($sError, $sDebug);
	}
function f2401_EnviarNotas($idPeraca, $idCurso, $idTutor, $iTipo, $objDB, $bDebug=false){
	$sError='';
	$sDebug='';
	$sInfoSincro='';
	$sURLws='http://rca.unad.edu.co/webservicesrca/wsNotasCampus/';
	if ($sError==''){
		switch($iTipo){
			case 1:
			$sCampoPuntaje='core04nota75';
			break;
			case 2:
			$sCampoPuntaje='core04nota25';
			break;
			default:
			$sError='No se reconoce el tipo de nota.';
			break;
			}
		}
	if ($sError==''){
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Iniciando el web service de Calificaciones '.$sURLws.'ws9900.php <br>';}
		$cliente=new nusoap_client($sURLws.'ws9900.php', false);
		$sError=$cliente->getError();
		if ($sError==''){
			}else{
			$sError='Error iniciando el WebService de calificaciones: '.$sError;
			}
		}
	if ($sError==''){
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Iniciando peticiones a Registro y Control<br>';}
		//Listar los alumnos...
		$sSQL='SHOW TABLES LIKE "core04%"';
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Total Periodo: Lista de contenedores: '.$sSQL.'<br>';}
		$tablac=$objDB->ejecutasql($sSQL);
		while($filac=$objDB->sf($tablac)){
			$iContenedor=substr($filac[0], 16);
			//Cargar la notas notas a reportar.
			$sSQL='SELECT TB.core04id, T11.unad11doc, TB.core04nota75, TB.core04fechanota75, TB.core04nota25
FROM core04matricula_'.$iContenedor.' AS TB, unad11terceros AS T11 
WHERE TB.core04idcurso='.$idCurso.' AND TB.core04peraca='.$idPeraca.' AND TB.core04idtutor='.$idTutor.' AND TB.core04estado IN (0, 5, 7) 
AND TB.core04tercero=T11.unad11id';
			if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Alistando los datos a exportar '.$sSQL.' <br>';}
			$tabla=$objDB->ejecutasql($sSQL);
			while($fila=$objDB->sf($tabla)){
				//Enviar la nota del estudiante.
				$sErrAlumno='';
				$sDoc=$fila['unad11doc'];
				$aDatos['ccon']='UNAD2019.';
				$aDatos['documento']=$fila['unad11doc'];
				$aDatos['curso']=$idCurso;
				$aDatos['periodo']=$idPeraca;
				$aDatos['puntaje']=(int)$fila[$sCampoPuntaje];
				$aDatos['tipo']=$iTipo;// 1 para 75%, 2 para 25%, 3 para supletorio, 4 para habilitacion
				$aTotal=array('aDatos'=>$aDatos);
				if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Enviando nota de '.$fila['unad11doc'].' '.(int)$fila[$sCampoPuntaje].'<br>';}
				$sres=$cliente->call('f9900_notas', $aTotal);
				if ($cliente->fault){
					$sErrAlumno='Respuesta inesperada desde el servidor.<br>Parametros '.json_encode($aTotal).'<br>Respuesta '.json_encode($sres).'';
					}else{
					$sErrAlumno=$cliente->getError();
					if ($sErrAlumno!=''){$sErrAlumno='Error al tratar de sincronizar calificaciones: '.$sErrAlumno;}
					}
				if ($sErrAlumno==''){
					$aRpta=explode('@', $sres);
					if ($aRpta[0]==1){
						}else{
						switch($aRpta[1]){
							//Mensajes que seran ignorados.
							case 'Esta calificacion del estudiante ya ha sido registrada previamente':
							//'Esta calificacion del estudiante ya ha sido registrada previamente'
							break;
							default:
							$sErrAlumno='Error al enviar la nota: '.$aRpta[1].'';
							break;
							}
						}
					}
				if ($sErrAlumno!=''){
					if ($sError!=''){$sError=$sError.'<br>';}
					$sError=$sError.'Documento '.$sDoc.': '.$sErrAlumno;
					}
				//Termina de enviar la nota del estudiante.
				}
			}
		//Termina de listar.
		}
	return array($sError, $sInfoSincro, $sDebug);
	}
function f2401_Reportar75($idPeraca, $idCurso, $idTutor, $objDB, $bDebug=false, $idContPeraca=0, $bRecaulcula=false){
	$sError='';
	$sDebug='';
	if ($idContPeraca==0){
		$idContPeraca=f146_Contenedor($idPeraca, $objDB);
		}
	if ($sError==''){
		//Espacio para las comprobaciones blockchain
		//@@@ ojo con la infraestructura...
		//sleep(5);
		//Fin de las comprobaciones...
		}
	if ($sError==''){
		//Verificamos las actividades...
		$sIdActividades='-99';
		$sId02='-99';
		$sIdsM0='-99';
		$sIdsM1='-99';
		$numActMom0=0;
		$numActMom1=0;
		$iPesoTotal=0;
		$sSQL='SELECT TB.ceca02idactividad, TB.ceca02id, TB.ceca02peso, TB.ceca02idestado, TB.ceca02momentoest
FROM ceca02actividadtutor_'.$idContPeraca.' AS TB
WHERE TB.ceca02idtutor='.$idTutor.' AND TB.ceca02idperaca='.$idPeraca.' AND TB.ceca02idcurso='.$idCurso.' AND TB.ceca02momentoest IN (0, 1)';
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' REPORTE 75: Revisando actividades: '.$sSQL.'<br>';}
		$tabla=$objDB->ejecutasql($sSQL);
		while($fila=$objDB->sf($tabla)){
			$sIdActividades=$sIdActividades.','.$fila['ceca02idactividad'];
			$sId02=$sId02.','.$fila['ceca02id'];
			$iPesoTotal=$iPesoTotal+$fila['ceca02peso'];
			if ($fila['ceca02momentoest']==0){
				$sIdsM0=$sIdsM0.','.$fila['ceca02idactividad'];
				$numActMom0++;
				}else{
				$sIdsM1=$sIdsM1.','.$fila['ceca02idactividad'];
				$numActMom1++;
				}
			$bCalificada=false;
			if ($fila['ceca02idestado']==7){$bCalificada=true;}
			if ($fila['ceca02idestado']==8){$bCalificada=true;}
			if (!$bCalificada){
				$sError=' REPORTE 75: Se han encontrado actividades sin calificar, no es posible reportar la nota.';}
			}
		}
	if ($sError==''){
		//Consolidar las notas por si acado.
		list($sErrorC, $sDebugC)=f2401_ConsolidarNotas($idPeraca, $idCurso, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugC;
		}
	if ($sError==''){
		$bEnviaNotas=false;
		if ($idPeraca>615){$bEnviaNotas=true;}
		if ($bRecaulcula){$bEnviaNotas=false;}
		if ($bEnviaNotas){
			//Cuando el webservice este deshabilitado se debe mostrar este mensaje... toca pasarlo a configuracion.
			//$sError='No fue posible iniciar la transmisi&oacute;n de la nota, si el problema persiste por favor informe a la PTI.';
			}
		}
	if ($sError==''){
		if ($bEnviaNotas){
			list($sError, $sInfoSincro, $sDebugWS)=f2401_EnviarNotas($idPeraca, $idCurso, $idTutor, 1, $objDB, $bDebug);
			$sDebug=$sDebug.$sDebugWS;
			}
		}
	if ($sError==''){
		$iHoy=fecha_DiaMod();
		$iHora=fecha_MinutoMod();
		$sTabla06='core06grupos_'.$idContPeraca;
		//Hacer las base estadistica por cada nota.
		$sSQL='SHOW TABLES LIKE "core04%"';
		$tablac=$objDB->ejecutasql($sSQL);
		while($filac=$objDB->sf($tablac)){
			$iContenedor=substr($filac[0], 16);
			//Llevar la nota de la tabla 5 a la 4
			$sSQL='SELECT TB.core04id, TB.core04tercero, TB.core04estado 
FROM core04matricula_'.$iContenedor.' AS TB 
WHERE TB.core04idcurso='.$idCurso.' AND TB.core04peraca='.$idPeraca.' AND TB.core04idtutor='.$idTutor.' AND TB.core04estado IN (0, 5, 7, 8)';
			if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' REPORTE 75: Alistando los id que vamos a totalizar '.$sSQL.' <br>';}
			$tabla=$objDB->ejecutasql($sSQL);
			while($fila=$objDB->sf($tabla)){
				$numActNoPresM0=0;
				$numActNoPresM1=0;
				$iPuntajePresenta=0;
				$iPuntajeNoPresenta=0;
				$iPuntajeAprueba=0;
				$iPuntajeNoAprueba=0;
				//Esto es una sumatoria simple de la tabla 5
				$sSQL='SELECT TB.core05idactividad, TB.core05puntaje75, TB.core05acumula75, TB.core05estado, 0 AS Momento
FROM core05actividades_'.$iContenedor.' AS TB, ofer04cursoactividad AS T1
WHERE TB.core05idmatricula='.$fila['core04id'].' AND TB.core05idactividad IN ('.$sIdsM0.') AND TB.core05idactividad=T1.ofer04id
UNION 
SELECT TB.core05idactividad, TB.core05puntaje75, TB.core05acumula75, TB.core05estado, 1 AS Momento
FROM core05actividades_'.$iContenedor.' AS TB, ofer04cursoactividad AS T1
WHERE TB.core05idmatricula='.$fila['core04id'].' AND TB.core05idactividad IN ('.$sIdsM1.') AND TB.core05idactividad=T1.ofer04id';
				//if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' <b>Revisando actividades del estudiante '.$fila['core04tercero'].'</b> '.$sSQL.' <br>';}
				$tabla5=$objDB->ejecutasql($sSQL);
				while($fila5=$objDB->sf($tabla5)){
					$bEntraCalif=false;
					if ($fila5['core05estado']==7){$bEntraCalif=true;}
					if ($fila5['core05estado']==8){$bEntraCalif=true;}
					if ($bEntraCalif){
						//La actividad fue calificada
						$iPuntajePresenta=$iPuntajePresenta+$fila5['core05puntaje75'];
						$iPuntajeAprueba=$iPuntajeAprueba+$fila5['core05acumula75'];
						$iPuntajeNoAprueba=$iPuntajeNoAprueba+($fila5['core05puntaje75']-$fila5['core05acumula75']);
						}else{
						$iPuntajeNoPresenta=$iPuntajeNoPresenta+$fila5['core05puntaje75'];
						if ($fila5['Momento']==0){
							$numActNoPresM0++;
							}else{
							$numActNoPresM1++;
							}
						}
					}
				$sAdd='';
				if (!$bRecaulcula){
					$sAdd=', core04fechaexporta='.$iHoy.', core04minexporta='.$iHora.', core04estado=8';
					}
				$sSQL='UPDATE core04matricula_'.$iContenedor.' SET core04est_numactivm0='.$numActMom0.', core04est_numactivnoprem0='.$numActNoPresM0.', core04est_numactivm1='.$numActMom1.', core04est_numactivnoprem1='.$numActNoPresM1.', core04est_75presenta='.$iPuntajePresenta.', core04est_75cero='.$iPuntajeNoPresenta.', core04est_75noaprobado='.$iPuntajeNoAprueba.', core04est_75aprobado='.$iPuntajeAprueba.$sAdd.' WHERE core04id='.$fila['core04id'].'';
				$result=$objDB->ejecutasql($sSQL);
				if ($result==false){
					if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' REPORTE 75: Actualizando estadistica error. <span class="rojo">'.$sSQL.'</span><br>';}
					}
				}
			}
		if (!$bRecaulcula){
			//Marcar las actividades del 75 como reportadas.
			$sSQL='UPDATE ceca02actividadtutor_'.$idContPeraca.' SET ceca02idestado=8 WHERE ceca02id IN ('.$sId02.')';
			if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Marcando actividades reportadas '.$sSQL.' <br>';}
			$tabla=$objDB->ejecutasql($sSQL);
			//Armar la estdistica
			list($sErrorT, $sDebugT)=f2401_ArmarEstadisticaCurso($idPeraca, $idCurso, $objDB, $bDebug, $idContPeraca);
			$sDebug=$sDebug.$sDebugT;
			}
		}
	return array($sError, $sDebug);
	}
function f2401_Reportar25($idPeraca, $idCurso, $idTutor, $objDB, $bDebug=false, $idContPeraca=0){
	$sError='';
	$sDebug='';
	if ($idContPeraca==0){
		$idContPeraca=f146_Contenedor($idPeraca, $objDB);
		}
	if ($sError==''){
		//Espacio para las comprobaciones blockchain
		//sleep(5);
		//Fin de las comprobaciones...
		}
	if ($sError==''){
		//Verificamos las actividades...
		$sIdActividades='-99';
		$sId02='-99';
		$sIdsM2='-99';
		$numActMom2=0;
		$iPesoTotal=0;
		$sSQL='SELECT TB.ceca02idactividad, TB.ceca02id, TB.ceca02peso, TB.ceca02idestado, TB.ceca02momentoest
FROM ceca02actividadtutor_'.$idContPeraca.' AS TB
WHERE TB.ceca02idtutor='.$idTutor.' AND TB.ceca02idperaca='.$idPeraca.' AND TB.ceca02idcurso='.$idCurso.' AND TB.ceca02momentoest=2';
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' REPORTE 25: Revisando actividades: '.$sSQL.'<br>';}
		$tabla=$objDB->ejecutasql($sSQL);
		while($fila=$objDB->sf($tabla)){
			$sIdActividades=$sIdActividades.','.$fila['ceca02idactividad'];
			$sId02=$sId02.','.$fila['ceca02id'];
			$iPesoTotal=$iPesoTotal+$fila['ceca02peso'];
			$sIdsM2=$sIdsM2.','.$fila['ceca02idactividad'];
			$numActMom2++;
			if ($fila['ceca02idestado']!=7){
				$sError='Se han encontrado actividades sin calificar, no es posible reportar la nota.';
				}else{
				if (($idPeraca>610)&&($idPeraca<614)){
					//Esto se deja debido a una falla en el calculo de las notas al momento de cerrar la actividad, buscamos evitar que deban volver a cerrar las actividades que ya se hayan totalizado.
					f2401_CerrarActividad($idPeraca, $idCurso, $fila['ceca02idactividad'], $idTutor, $objDB, false, 1);
					}
				}
			}
		}
	if ($sError==''){
		//Consolidar las notas por si acaso.
		list($sErrorC, $sDebugC)=f2401_ConsolidarNotas($idPeraca, $idCurso, $objDB, $bDebug);
		}
	if ($sError==''){
		$bEnviaNotas=false;
		if ($idPeraca>615){$bEnviaNotas=true;}
		if ($bEnviaNotas){
			//Cuando el webservice este deshabilitado se debe mostrar este mensaje... toca pasarlo a configuracion.
			//$sError='No fue posible iniciar la transmisi&oacute;n de la nota, si el problema persiste por favor informe a la PTI.';
			}
		}
	if ($sError==''){
		if ($bEnviaNotas){
			list($sError, $sInfoSincro, $sDebugWS)=f2401_EnviarNotas($idPeraca, $idCurso, $idTutor, 2, $objDB, $bDebug);
			$sDebug=$sDebug.$sDebugWS;
			}
		}
	if ($sError==''){
		//Hacer las base estadistica por cada nota.
		$iHoy=fecha_DiaMod();
		$iHora=fecha_MinutoMod();
		$sTabla06='core06grupos_'.$idContPeraca;
		$sSQL='SHOW TABLES LIKE "core04%"';
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Total Periodo: Lista de contenedores: '.$sSQL.'<br>';}
		$tablac=$objDB->ejecutasql($sSQL);
		while($filac=$objDB->sf($tablac)){
			$sIds04='-99';
			$iContenedor=substr($filac[0], 16);
			//Llevar la nota de la tabla 5 a la 4
			$sSQL='SELECT TB.core04id, TB.core04tercero
FROM core04matricula_'.$iContenedor.' AS TB 
WHERE TB.core04idcurso='.$idCurso.' AND TB.core04peraca='.$idPeraca.' AND TB.core04idtutor='.$idTutor.' AND TB.core04estado IN (0, 5, 7)';
			if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Alistando los id que vamos a totalizar '.$sSQL.' <br>';}
			$tabla=$objDB->ejecutasql($sSQL);
			while($fila=$objDB->sf($tabla)){
				$sIds04=$sIds04.','.$fila['core04id'];
				$numActNoPresM2=0;
				$iPuntajePresenta=0;
				$iPuntajeNoPresenta=0;
				$iPuntajeAprueba=0;
				$iPuntajeNoAprueba=0;
				//Esto es una sumatoria simple de la tabla 5
				$sSQL='SELECT TB.core05idactividad, TB.core05puntaje75, TB.core05acumula75, TB.core05puntaje25, TB.core05acumula25, TB.core05estado
FROM core05actividades_'.$iContenedor.' AS TB, ofer04cursoactividad AS T1
WHERE TB.core05idmatricula='.$fila['core04id'].' AND TB.core05idactividad IN ('.$sIdsM2.') AND TB.core05idactividad=T1.ofer04id';
				if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' <b>Revisando actividades del estudiante '.$fila['core04tercero'].'</b> '.$sSQL.' <br>';}
				$tabla5=$objDB->ejecutasql($sSQL);
				while($fila5=$objDB->sf($tabla5)){
					if ($fila5['core05estado']==7){
						//La actividad fue calificada
						$iPuntajePresenta=$iPuntajePresenta+$fila5['core05puntaje25'];
						$iPuntajeAprueba=$iPuntajeAprueba+$fila5['core05acumula25'];
						$iPuntajeNoAprueba=$iPuntajeNoAprueba+($fila5['core05puntaje25']-$fila5['core05acumula25']);
						}else{
						$iPuntajeNoPresenta=$iPuntajeNoPresenta+$fila5['core05puntaje25'];
						$numActNoPresM2++;
						}
					}
				$sSQL='UPDATE core04matricula_'.$iContenedor.' SET core04est_numactivm2='.$numActMom2.', core04est_numactivnoprem2='.$numActNoPresM2.', core04est_25presenta='.$iPuntajePresenta.', core04est_25cero='.$iPuntajeNoPresenta.', core04est_25noaprobado='.$iPuntajeNoAprueba.', core04est_25aprobado='.$iPuntajeAprueba.', core04fechaexp25='.$iHoy.', core04minexp25='.$iHora.', core04estado=7 WHERE core04id='.$fila['core04id'].'';
				$result=$objDB->ejecutasql($sSQL);
				}
			//Ahora actualizar valores del 100%
			$sSQL='UPDATE core04matricula_'.$iContenedor.' AS TB SET core04est_100presenta=(core04est_75presenta+core04est_25presenta), core04est_100cero=(core04est_75cero+core04est_25cero), core04est_100noaprobado=(core04est_75noaprobado+core04est_25noaprobado), core04est_100aprobado=(core04est_75aprobado+core04est_25aprobado) WHERE core04id IN ('.$sIds04.')';
			$result=$objDB->ejecutasql($sSQL);
			}
		//Marcar las actividades del 75 como reportadas.
		$sSQL='UPDATE ceca02actividadtutor_'.$idContPeraca.' SET ceca02idestado=8 WHERE ceca02id IN ('.$sId02.')';
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Marcando actividades reportadas '.$sSQL.' <br>';}
		$tabla=$objDB->ejecutasql($sSQL);
		//Armar la estdistica
		list($sErrorT, $sDebugT)=f2401_ArmarEstadisticaCurso($idPeraca, $idCurso, $objDB, $bDebug, $idContPeraca);
		$sDebug=$sDebug.$sDebugT;
		}
	return array($sError, $sDebug);
	}
function f2401_Habilitar75($idPeraca, $idCurso, $idTutor, $objDB, $bDebug=false){
	$sError='';
	$sDebug='';
	$idContPeraca=f146_Contenedor($idPeraca, $objDB);
	if ($sError==''){
		//Espacio para las comprobaciones blockchain
		//sleep(5);
		//Fin de las comprobaciones...
		}
	if ($sError==''){
		//Verificamos las actividades...
		$sIdActividades='-99';
		$sId02='-99';
		$sIdsM0='-99';
		$sIdsM1='-99';
		$numActMom0=0;
		$numActMom1=0;
		$iPesoTotal=0;
		$sSQL='SELECT TB.ceca02idactividad, TB.ceca02id, TB.ceca02peso, TB.ceca02idestado, TB.ceca02momentoest
FROM ceca02actividadtutor_'.$idContPeraca.' AS TB
WHERE TB.ceca02idtutor='.$idTutor.' AND TB.ceca02idperaca='.$idPeraca.' AND TB.ceca02idcurso='.$idCurso.' AND TB.ceca02momentoest IN (0, 1)';
		$tabla=$objDB->ejecutasql($sSQL);
		while($fila=$objDB->sf($tabla)){
			$sIdActividades=$sIdActividades.','.$fila['ceca02idactividad'];
			$sId02=$sId02.','.$fila['ceca02id'];
			if ($fila['ceca02idestado']!=8){$sError='Se han encontrado actividades no reportadas, no es posible reversar la nota.';}
			}
		}
	if ($sError==''){
		$iHoy=fecha_DiaMod();
		$iHora=fecha_MinutoMod();
		$sTabla06='core06grupos_'.$idContPeraca;
		//Marcar las actividades del 75 como calificadas.
		$sSQL='UPDATE ceca02actividadtutor_'.$idContPeraca.' SET ceca02idestado=7 WHERE ceca02id IN ('.$sId02.')';
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Marcando actividades como calificadas '.$sSQL.' <br>';}
		$tabla=$objDB->ejecutasql($sSQL);
		//Devolver el estado de las notas.
		$sSQL='SHOW TABLES LIKE "core04%"';
		$tablac=$objDB->ejecutasql($sSQL);
		while($filac=$objDB->sf($tablac)){
			$iContenedor=substr($filac[0], 16);
			//Llevar la nota de la tabla 5 a la 4
			$sSQL='UPDATE core04matricula_'.$iContenedor.' AS TB SET core04estado=0 
WHERE TB.core04idcurso='.$idCurso.' AND TB.core04peraca='.$idPeraca.' AND TB.core04idtutor='.$idTutor.' AND TB.core04estado IN (5, 7)';
			$result=$objDB->ejecutasql($sSQL);
			if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Reversando la matricula: '.$sSQL.' <br>';}
			}
		//Armar la estdistica
		list($sErrorT, $sDebugT)=f2401_ArmarEstadisticaCurso($idPeraca, $idCurso, $objDB, $bDebug, $idContPeraca);
		$sDebug=$sDebug.$sDebugT;
		}
	return array($sError, $sDebug);
	}
function f2401_Habilitar25($idPeraca, $idCurso, $idTutor, $objDB, $bDebug=false){
	$sError='';
	$sDebug='';
	$idContPeraca=f146_Contenedor($idPeraca, $objDB);
	if ($sError==''){
		//Espacio para las comprobaciones blockchain
		//sleep(5);
		//Fin de las comprobaciones...
		}
	if ($sError==''){
		//Verificamos las actividades...
		$sIdActividades='-99';
		$sId02='-99';
		$sIdsM2='-99';
		$numActMom2=0;
		$iPesoTotal=0;
		$sSQL='SELECT TB.ceca02idactividad, TB.ceca02id, TB.ceca02peso, TB.ceca02idestado, TB.ceca02momentoest
FROM ceca02actividadtutor_'.$idContPeraca.' AS TB
WHERE TB.ceca02idtutor='.$idTutor.' AND TB.ceca02idperaca='.$idPeraca.' AND TB.ceca02idcurso='.$idCurso.' AND TB.ceca02momentoest=2';
		$tabla=$objDB->ejecutasql($sSQL);
		while($fila=$objDB->sf($tabla)){
			$sIdActividades=$sIdActividades.','.$fila['ceca02idactividad'];
			$sId02=$sId02.','.$fila['ceca02id'];
			if ($fila['ceca02idestado']!=8){
				$sError='Se han encontrado actividades sin reportar, no es posible habilitar la nota.';
				}
			}
		}
	if ($sError==''){
		//Hacer las base estadistica por cada nota.
		$iHoy=fecha_DiaMod();
		$iHora=fecha_MinutoMod();
		$sTabla06='core06grupos_'.$idContPeraca;
		//Marcar las actividades del 25 como calificadas.
		$sSQL='UPDATE ceca02actividadtutor_'.$idContPeraca.' SET ceca02idestado=7 WHERE ceca02id IN ('.$sId02.')';
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Marcando actividades habilitadas '.$sSQL.' <br>';}
		$tabla=$objDB->ejecutasql($sSQL);
		//Devolver el estado de las notas.
		$sSQL='SHOW TABLES LIKE "core04%"';
		$tablac=$objDB->ejecutasql($sSQL);
		while($filac=$objDB->sf($tablac)){
			$iContenedor=substr($filac[0], 16);
			$sSQL='UPDATE core04matricula_'.$iContenedor.' AS TB SET core04estado=5 
WHERE TB.core04idcurso='.$idCurso.' AND TB.core04peraca='.$idPeraca.' AND TB.core04idtutor='.$idTutor.' AND TB.core04estado=7';
			$result=$objDB->ejecutasql($sSQL);
			if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Reversando el estado de la matricula: '.$sSQL.' <br>';}
			}
		//Armar la estdistica
		list($sErrorT, $sDebugT)=f2401_ArmarEstadisticaCurso($idPeraca, $idCurso, $objDB, $bDebug, $idContPeraca);
		$sDebug=$sDebug.$sDebugT;
		}
	return array($sError, $sDebug);
	}
//Armar los totales implica diferentes tipos de estadisticas, por tanto se arma una clase para poderlos administrar con una consulta sencilla.
class f2408RegTipo1{
var $idTutor=0;
var $idZona=0;
var $idCentro=0;
var $idEscuela=0;
var $idPrograma=0;
var $sSexo='';
var $iEdad=0;
var $iNumEstudiantes=0;
var $iNumResagados=0;
var $iNumNoAsisten=0;
var $iNumReprobados=0;
var $iPuntaje75=0;
var $iPuntaje25=0;
var $iAprobado75=0;
var $iAprobado25=0;
}
class f2408Estadistica{
var $idPeraca=0;
var $idCurso=0;
var $idContPeraca=0;
var $aTotal=array();
var $iTotal=0;
var $aTutores=array();
var $iTutores=0;
function ActualizarAdicionales($objDB){
	$sSQL='UPDATE ceca08estadisticacurso AS TB, unad24sede AS T1
SET TB.ceca08idzona=T1.unad24idzona
WHERE TB.ceca08idzona=0 AND TB.ceca08idcentro=T1.unad24id';
	$result=$objDB->ejecutasql($sSQL);
	$sSQL='UPDATE ceca08estadisticacurso AS TB, core09programa AS T1
SET TB.ceca08idescuela=T1.core09idescuela
WHERE TB.ceca08idescuela=0 AND TB.ceca08idprograma=T1.core09id';
	$result=$objDB->ejecutasql($sSQL);
	}
function sumar($idTutor, $idCentro, $idPrograma, $sSexo, $sEdad, $iResagado, $iReprobado, $iNumNoAsisten, $iPuntaje75, $iPuntaje25, $iAprobado75, $iAprobado25){
	$bExiste=false;
	$iPosExiste=0;
	for ($k=1;$k<=$this->iTotal;$k++){
		$objDato=$this->aTotal[$k];
		if (($objDato->idTutor==$idTutor)&&($objDato->idCentro==$idCentro)&&($objDato->idPrograma==$idPrograma)&&($objDato->sSexo==$sSexo)&&($objDato->sEdad==$sEdad)){
			//Se ha encontrado...
			$iPosExiste=$k;
			$bExiste=true;
			$k=$this->iTotal+1;
			}
		}
	if (!$bExiste){
		$objDato=new f2408RegTipo1();
		$objDato->idTutor=$idTutor;
		$objDato->idCentro=$idCentro;
		$objDato->idPrograma=$idPrograma;
		$objDato->sSexo=$sSexo;
		$objDato->sEdad=$sEdad;
		$objDato->iNumEstudiantes=1;
		$objDato->iResagado=$iResagado;
		$objDato->iReprobado=$iReprobado;
		$objDato->iNumNoAsisten=$iNumNoAsisten;
		$objDato->iPuntaje75=$iPuntaje75;
		$objDato->iPuntaje25=$iPuntaje25;
		$objDato->iAprobado75=$iAprobado75; 
		$objDato->iAprobado25=$iAprobado25;
		$this->iTotal++;
		$this->aTotal[$this->iTotal]=$objDato;
		}else{
		$objDato->iNumEstudiantes++;
		$objDato->iResagado=$objDato->iResagado+$iResagado;
		$objDato->iReprobado=$objDato->iReprobado+$iReprobado;
		$objDato->iNumNoAsisten=$objDato->iNumNoAsisten+$iNumNoAsisten;
		$objDato->iPuntaje75=$objDato->iPuntaje75+$iPuntaje75;
		$objDato->iPuntaje25=$objDato->iPuntaje25+$iPuntaje25;
		$objDato->iAprobado75=$objDato->iAprobado75+$iAprobado75; 
		$objDato->iAprobado25=$objDato->iAprobado25+$iAprobado25;
		$this->aTotal[$iPosExiste]=$objDato;
		}
	}
function TraerDatos($objDB, $bDebug=false){
	$sError='';
	$sDebug='';
	if ($this->idContPeraca==0){
		$this->idContPeraca=f146_Contenedor($this->idPeraca, $objDB);
		}
	if ($sError==''){
		$sSQL='SHOW TABLES LIKE "core04%"';
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Total Periodo: Lista de contenedores: '.$sSQL.'<br>';}
		$tablac=$objDB->ejecutasql($sSQL);
		while($filac=$objDB->sf($tablac)){
			$iContenedor=substr($filac[0], 16);
			$sSQL='SELECT TB.core04idtutor, TB.core04estado, TB.core04idcead, TB.core04idprograma, T11.unad11genero, T11.unad11fechanace, TB.core04fechaexporta, TB.core04est_75aprobado, TB.core04est_25aprobado, TB.core04est_aprob, TB.core04estado, TB.core04est_numactivm0, TB.core04est_numactivnoprem0, TB.core04est_numactivm1, TB.core04est_numactivnoprem1, TB.core04est_numactivm2, TB.core04est_numactivnoprem2 
FROM core04matricula_'.$iContenedor.' AS TB, unad11terceros AS T11 
WHERE TB.core04idcurso='.$this->idCurso.' AND TB.core04peraca='.$this->idPeraca.' AND TB.core04estado IN (5,7, 8) AND TB.core04tercero=T11.unad11id';
			if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' ESTADISTICA: Datos base: '.$sSQL.'<br>';}
			//@@@ Aqui no se recibe bien la estadistica cuando la reportan no viene sumada....
			$tabla=$objDB->ejecutasql($sSQL);
			while($fila=$objDB->sf($tabla)){
				$idTutor=$fila['core04idtutor'];
				$idCentro=$fila['core04idcead'];
				$idPrograma=$fila['core04idprograma'];
				$sSexo='_';
				if ($fila['unad11genero']=='M'){$sSexo='M';}
				if ($fila['unad11genero']=='F'){$sSexo='F';}
				$iEdad=0;
				$sFechaNota=fecha_desdenumero($fila['core04fechaexporta']);
				if (fecha_esvalida($fila['unad11fechanace'])){
					list($iEdad, $iMedida)=fecha_edad($fila['unad11fechanace'], $sFechaNota);
					if ($iMedida!=1){$iEdad=0;}
					}
				$iResagado=0;
				$iReprobado=0;
				$iNumNoAsisten=0;
				$iAprobado75=$fila['core04est_75aprobado'];
				$iAprobado25=$fila['core04est_25aprobado'];
				//core04est_numactivm0 core04est_numactivnoprem0
				$iPuntaje75=375;
				$iPuntaje25=125;
				$iActividadesPresentadas=($fila['core04est_numactivm0']+$fila['core04est_numactivm1'])-($fila['core04est_numactivnoprem0']+$fila['core04est_numactivnoprem1']);
				if ($fila['core04estado']==5){
					$iPuntaje25=0;
					$iAprobado25=0;
					if (($iAprobado75)<($fila['core04est_aprob']*75)){$iReprobado=1;}
					}else{
					$iActividadesPresentadas=$iActividadesPresentadas+($fila['core04est_numactivm2'])-($fila['core04est_numactivnoprem2']);
					if (($iAprobado75+$iAprobado25)<($fila['core04est_aprob']*100)){$iReprobado=1;}
					}
				if ($iActividadesPresentadas==0){
					$iNumNoAsisten=1;
					$iPuntaje75=0;
					$iPuntaje25=0;
					}
				//Por cada alumno mandar la estadistica.
				$this->sumar($idTutor, $idCentro, $idPrograma, $sSexo, $iEdad, $iResagado, $iReprobado, $iNumNoAsisten, $iPuntaje75, $iPuntaje25, $iAprobado75, $iAprobado25);
				}
			}
		}
	return array($sError, $sDebug);
	}
function TraerDatosMatricula($objDB, $bDebug=false){
	$sError='';
	$sDebug='';
	//Esta función hacia colapsar la memoria, asi que no se puede hacer la sumatoria de todo el periodo, esto funciona para curso a curso.
	/*
	if ($this->idContPeraca==0){
		$this->idContPeraca=f146_Contenedor($this->idPeraca, $objDB);
		}
	$sFechaNota=fecha_hoy();
	$sSQL='SELECT ofer14fechaini60 FROM ofer14per_acaparams WHERE ofer14idper_aca='.$this->idPeraca.'';
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$sFechaIniClase=$fila['ofer14fechaini60'];
		}
	if ($sError==''){
		$sSQL='SHOW TABLES LIKE "core04%"';
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Total Periodo: Lista de contenedores: '.$sSQL.'<br>';}
		$tablac=$objDB->ejecutasql($sSQL);
		while($filac=$objDB->sf($tablac)){
			$iContenedor=substr($filac[0], 16);
			$sSQL='SELECT TB.core04idcurso, TB.core04estado, TB.core04idcead, TB.core04idprograma, T11.unad11genero, TB.core04edad 
FROM core04matricula_'.$iContenedor.' AS TB, unad11terceros AS T11 
WHERE TB.core04peraca='.$this->idPeraca.' AND TB.core04tercero=T11.unad11id';
			$tabla=$objDB->ejecutasql($sSQL);
			while($fila=$objDB->sf($tabla)){
				$idCurso=$fila['core04idcurso'];
				$idCentro=$fila['core04idcead'];
				$idPrograma=$fila['core04idprograma'];
				$sSexo='_';
				if ($fila['unad11genero']=='M'){$sSexo='M';}
				if ($fila['unad11genero']=='F'){$sSexo='F';}
				$iEdad=0;
				if (fecha_esvalida($fila['unad11fechanace'])){
					list($iEdad, $iMedida)=fecha_edad($fila['unad11fechanace'], $sFechaNota);
					if ($iMedida!=1){$iEdad=0;}
					}
				$iResagado=0;
				//Por cada alumno mandar la estadistica.
				$this->sumar($idCurso, $idCentro, $idPrograma, $sSexo, $iEdad, $iResagado, 0, 0, 0, 0, 0, 0);
				}
			}
		}
	*/
	return array($sError, $sDebug);
	}
function Guardar($objDB, $bDebug=false){
	$sError='';
	$sDebug='';
	//Primero vemos que las edades esten bien.
	f2400_CalcularEdades($this->idPeraca, $objDB);
	//La idea es que armemos todas las posibles combinaciones en una sola carga sin muchos filtros...
	$ceca08id=tabla_consecutivo('ceca08estadisticacurso','ceca08id', '', $objDB);
	$sCampos08='ceca08idperaca, ceca08idcurso, ceca08tiporegistro, ceca08idtutor, ceca08idzona, 
ceca08idcentro, ceca08idescuela, ceca08idprograma, ceca08sexo, ceca08edad, 
ceca08id, ceca08numestudiantes, ceca08numresagados, ceca08numreprobados, ceca08numinasistentes, 
ceca08promedio75, ceca08promedio25, ceca08promediototal, ceca08puntaje75, ceca08puntaje25, 
ceca08suma75, ceca08suma25';
	$idZona=0;
	$idEscuela=0;
	$sSQL='UPDATE ceca08estadisticacurso SET ceca08numestudiantes=0, ceca08idzona=0, ceca08idescuela=0, ceca08numresagados=0, ceca08numreprobados=0, ceca08numinasistentes=0, 
ceca08promedio75=0, ceca08promedio25=0, ceca08promediototal=0, ceca08puntaje75=0, ceca08puntaje25=0, 
ceca08suma75=0, ceca08suma25=0 WHERE ceca08idperaca='.$this->idPeraca.' AND ceca08idcurso='.$this->idCurso.' AND ceca08tiporegistro=1';
	$result=$objDB->ejecutasql($sSQL);
	for ($k=1;$k<=$this->iTotal;$k++){
		$objDato=$this->aTotal[$k];
		$ceca08promedio75=0;
		$ceca08promedio25=0;
		$ceca08promediototal=0;
		$sSQL='SELECT TB.ceca08id 
FROM ceca08estadisticacurso AS TB
WHERE TB.ceca08idperaca='.$this->idPeraca.' AND TB.ceca08idcurso='.$this->idCurso.' AND TB.ceca08tiporegistro=1 AND TB.ceca08idtutor='.$objDato->idTutor.' AND ceca08idzona='.$idZona.' AND ceca08idcentro='.$objDato->idCentro.' AND ceca08idescuela='.$idEscuela.' AND ceca08idprograma='.$objDato->idPrograma.' AND ceca08sexo="'.$objDato->sSexo.'" AND ceca08edad='.$objDato->sEdad.'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$fila=$objDB->sf($tabla);
			//Actualizar el registro
			$sSQL='UPDATE ceca08estadisticacurso SET ceca08numestudiantes='.$objDato->iNumEstudiantes.', ceca08numresagados='.$objDato->iResagado.', ceca08numreprobados='.$objDato->iReprobado.', ceca08numinasistentes='.$objDato->iNumNoAsisten.', 
ceca08promedio75='.$ceca08promedio75.', ceca08promedio25='.$ceca08promedio25.', ceca08promediototal='.$ceca08promediototal.', ceca08puntaje75='.$objDato->iPuntaje75.', ceca08puntaje25='.$objDato->iPuntaje25.', 
ceca08suma75='.$objDato->iAprobado75.', ceca08suma25='.$objDato->iAprobado25.' WHERE ceca08id='.$fila['ceca08id'].'';
			$result=$objDB->ejecutasql($sSQL);
			}else{
			//Agregar el registro
			$sSQL='INSERT INTO ceca08estadisticacurso ('.$sCampos08.') 
VALUES ('.$this->idPeraca.', '.$this->idCurso.', 1, '.$objDato->idTutor.', '.$idZona.', 
'.$objDato->idCentro.', '.$idEscuela.', '.$objDato->idPrograma.', "'.$objDato->sSexo.'", '.$objDato->sEdad.', 
'.$ceca08id.', '.$objDato->iNumEstudiantes.', '.$objDato->iResagado.', '.$objDato->iReprobado.', '.$objDato->iNumNoAsisten.', 
'.$ceca08promedio75.', '.$ceca08promedio25.', '.$ceca08promediototal.', '.$objDato->iPuntaje75.', '.$objDato->iPuntaje25.', 
'.$objDato->iAprobado75.', '.$objDato->iAprobado25.')';
			$result=$objDB->ejecutasql($sSQL);
			$ceca08id++;
			}
		}
	$this->ActualizarAdicionales($objDB);
	return array($sError, $sDebug);
	}
function GuardarMatricula($objDB, $bDebug=false){
	$sError='';
	$sDebug='';
	$iRegAgrega=0;
	$iRegActualiza=0;
	//Primero vemos que las edades esten bien.
	f2400_CalcularEdades($this->idPeraca, $objDB);
	//La idea es que armemos todas las posibles combinaciones en una sola carga sin muchos filtros...
	$sCampos08='ceca08idperaca, ceca08idcurso, ceca08tiporegistro, ceca08idtutor, ceca08idzona, 
ceca08idcentro, ceca08idescuela, ceca08idprograma, ceca08sexo, ceca08edad, 
ceca08id, ceca08numestudiantes, ceca08numresagados, ceca08numreprobados, ceca08numinasistentes, 
ceca08promedio75, ceca08promedio25, ceca08promediototal, ceca08puntaje75, ceca08puntaje25, 
ceca08suma75, ceca08suma25';
	$idZona=0;
	$idEscuela=0;
	$iResagado=0;
	$iReprobado=0;
	$iNumNoAsisten=0;
	$ceca08promedio75=0;
	$ceca08promedio25=0;
	$ceca08promediototal=0;
	$iPuntaje75=0;
	$iPuntaje25=0;
	$iAprobado75=0;
	$iAprobado25=0;
	$sSQL='UPDATE ceca08estadisticacurso SET ceca08numestudiantes=0, ceca08idzona=0, ceca08idescuela=0, ceca08numresagados=0, ceca08numreprobados=0, ceca08numinasistentes=0, 
ceca08promedio75=0, ceca08promedio25=0, ceca08promediototal=0, ceca08puntaje75=0, ceca08puntaje25=0, 
ceca08suma75=0, ceca08suma25=0 WHERE ceca08idperaca='.$this->idPeraca.' AND ceca08tiporegistro=2';
	$result=$objDB->ejecutasql($sSQL);
	//Traemos la data de matricula directamente.
	$ceca08id=tabla_consecutivo('ceca08estadisticacurso','ceca08id', '', $objDB);
	$sSQLBase='';
	$sSQL='SHOW TABLES LIKE "core04%"';
	//if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Total Periodo: Lista de contenedores: '.$sSQL.'<br>';}
	$tablac=$objDB->ejecutasql($sSQL);
	while($filac=$objDB->sf($tablac)){
		$iContenedor=substr($filac[0], 16);
		if ($sSQLBase!=''){$sSQLBase=$sSQLBase.' UNION ';}
		$sSQLBase=$sSQLBase.'SELECT TB.core04idcurso, TB.core04idcead, TB.core04idprograma, T11.unad11genero, TB.core04edad, '.$iContenedor.' AS Cont, COUNT(TB.core04id) AS Total
FROM core04matricula_'.$iContenedor.' AS TB, unad11terceros AS T11 
WHERE TB.core04peraca='.$this->idPeraca.' AND TB.core04tercero=T11.unad11id
GROUP BY TB.core04idcurso, TB.core04idcead, TB.core04idprograma, T11.unad11genero, TB.core04edad
';
		}
	if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Matricula total: '.$sSQLBase.'<br>';}
	$tabla=$objDB->ejecutasql($sSQLBase);
	while($fila=$objDB->sf($tabla)){
		$sSQL='SELECT TB.ceca08id 
FROM ceca08estadisticacurso AS TB
WHERE TB.ceca08idperaca='.$this->idPeraca.' AND TB.ceca08idcurso='.$fila['core04idcurso'].' AND TB.ceca08tiporegistro=2 AND TB.ceca08idtutor=0 AND ceca08idcentro='.$fila['core04idcead'].' AND ceca08idprograma='.$fila['core04idprograma'].' AND ceca08sexo="'.$fila['unad11genero'].'" AND ceca08edad='.$fila['core04edad'].'';
		$tabla8=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla8)>0){
			$fila8=$objDB->sf($tabla8);
			//Actualizar el registro
			$sSQL='UPDATE ceca08estadisticacurso SET ceca08numestudiantes=(ceca08numestudiantes+'.$fila['Total'].') WHERE ceca08id='.$fila8['ceca08id'].'';
			$result=$objDB->ejecutasql($sSQL);
			}else{
			//Agregar el registro (tener en cuenta que los tutores se colocan en 0 y el dato del curso viene en el tutor, ya que la matricula se hace totalizada en el periodo, no por tutor).
			$sSQL='INSERT INTO ceca08estadisticacurso ('.$sCampos08.') 
VALUES ('.$this->idPeraca.', '.$fila['core04idcurso'].', 2, 0, '.$idZona.', 
'.$fila['core04idcead'].', '.$idEscuela.', '.$fila['core04idprograma'].', "'.$fila['unad11genero'].'", '.$fila['core04edad'].', 
'.$ceca08id.', '.$fila['Total'].', '.$iResagado.', '.$iReprobado.', '.$iNumNoAsisten.', 
'.$ceca08promedio75.', '.$ceca08promedio25.', '.$ceca08promediototal.', '.$iPuntaje75.', '.$iPuntaje25.', 
'.$iAprobado75.', '.$iAprobado25.')';
			$result=$objDB->ejecutasql($sSQL);
			$ceca08id++;
			}
		}
	$this->ActualizarAdicionales($objDB);
	return array($sError, $sDebug);
	}
function __construct($idPeraca, $idCurso, $idContPeraca=0){
	$this->idPeraca=$idPeraca;
	$this->idCurso=$idCurso;
	$this->idContPeraca=$idContPeraca;
	}
}
function f2401_ArmarEstadisticaCurso($idPeraca, $idCurso, $objDB, $bDebug=false, $idContPeraca=0){
	$sError='';
	$sDebug='';
	if ($idContPeraca==0){
		$idContPeraca=f146_Contenedor($idPeraca, $objDB);
		}
	if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Calculando estadistica para el curso '.$idCurso.' en el periodo '.$idPeraca.'<br>';}
	$objEstadistica=new f2408Estadistica($idPeraca, $idCurso, $idContPeraca);
	list($sErrorE, $sDebugE)=$objEstadistica->TraerDatos($objDB, $bDebug);
	$sDebug=$sDebug.$sDebugE;
	list($sErrorE, $sDebugE)=$objEstadistica->Guardar($objDB, $bDebug);
	$sDebug=$sDebug.$sDebugE;
	// AND ((core04fechaexporta<>0) OR (core04fechaexp25<>0))
	return array($sError, $sDebug);
	}
function f2401_ArmarEstadisticaMatricula($idPeraca, $objDB, $bDebug=false, $idContPeraca=0){
	$sError='';
	$sDebug='';
	if ($idContPeraca==0){
		$idContPeraca=f146_Contenedor($idPeraca, $objDB);
		}
	if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Calculando estadistica de matricula para el curso '.$idCurso.' en el periodo '.$idPeraca.'<br>';}
	$objEstadistica=new f2408Estadistica($idPeraca, 0, $idContPeraca);
	list($sErrorE, $sDebugE)=$objEstadistica->TraerDatosMatricula($objDB, $bDebug);
	$sDebug=$sDebug.$sDebugE;
	list($sErrorE, $sDebugE)=$objEstadistica->GuardarMatricula($objDB, $bDebug);
	$sDebug=$sDebug.$sDebugE;
	// AND ((core04fechaexporta<>0) OR (core04fechaexp25<>0))
	return array($sError, $sDebug);
	}
function HTML_AvanceCurso($iCalificado, $iAprobado, $bConTexto=true, $bConValores=false){
	$res='';
	if ($iCalificado>0){
		$sTds='';
		$sVerde=' bgcolor="#009933"';
		$sRojo=' bgcolor="#FF0000"';
		$sAmarillo=' bgcolor="#FFCC33"';
		$iPorcAprobado=round($iAprobado/5,0);
		$iPorcCalificado=round($iCalificado/5,0);
		$iPorcPerdido=($iPorcCalificado-$iPorcAprobado);
		$iPendiente=100-$iPorcCalificado;
		$sTd1='';
		$sTd2='';
		$sTd3='';
		$sTexto='';
		$iCols=0;
		if ($iPorcAprobado>0){
			$sPuntaje='&nbsp;';
			if ($bConValores){$sPuntaje='<div style="color:#FFFFFF"><b>'.$iAprobado.'</b></div>';}
			$sTd1='<td'.$sVerde.' width="'.$iPorcAprobado.'%" title="'.$iPorcAprobado.' %" align="center">'.$sPuntaje.'</td>';
			$iCols++;
			}
		if ($iPorcPerdido>0){
			$sPuntaje='&nbsp;';
			if ($bConValores){$sPuntaje='<div style="color:#FFFFFF"><b>'.($iCalificado-$iAprobado).'</b></div>';}
			$sTd2='<td'.$sRojo.' width="'.$iPorcPerdido.'%" title="'.$iPorcPerdido.' %" align="center">'.$sPuntaje.'</td>';
			$iCols++;
			}
		if ($iPendiente>0){
			$sPuntaje='&nbsp;';
			if ($bConValores){$sPuntaje='<div style="color:#000000"><b>'.(500-$iCalificado).'</b></div>';}
			$sTd3='<td'.$sAmarillo.' width="'.$iPendiente.'%" title="'.$iPendiente.' %" align="center">'.$sPuntaje.'</td>';
			$iCols++;
			}
		$sTds=$sTd1.$sTd2.$sTd3;
		if ($bConTexto){
			$sCols='';
			if ($iCols>1){$sCols=' colspan="'.$iCols.'"';}
			$sFondo='';
			$sDivIni='';
			$sDivFin='';
			if ($iCalificado>0){
				$sFondo=$sRojo;
				$sDivIni='<div style="color:#FFFFFF">';
				$sDivFin='</div>';
				$iPorc=$iAprobado/$iCalificado*10;
				if ($iPorc>=6){$sFondo=$sVerde;}
				}
			$sMensaje='Calificaci&oacute;n acumulada <b>'.$iAprobado.'</b> Puntos';
			if ($bConValores){
				if ($iCalificado>499){
					if ($iAprobado>299){
						$sMensaje='APROBADO';
						}else{
						$sMensaje='REPROBADO';
						}
					}else{
					if ($iPorc>=6){
						if ($iAprobado>299){
							$sMensaje='Parcialmente Aprobado';
							}else{
							$sMensaje='Actividades en proceso';
							}
						}else{
						if (($iCalificado-$iAprobado)>200){
							$sMensaje='REPROBADO';
							}else{
							$sMensaje='Actividades en proceso (Bajo rendimiento)';
							}
						}
					}
				}
			$sTexto='<tr><td align="center"'.$sCols.$sFondo.'>'.$sDivIni.$sMensaje.''.$sDivFin.'</td></tr>';
			}
		$res='<table border="0" align="center" cellpadding="0" cellspacing="0" width="100%" class="BarraAvance">
<tr>'.$sTds.'</tr>'.$sTexto.'
</table>';
		}
	return $res;
	}
?>