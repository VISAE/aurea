<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2018 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
*/
function f2111_CerrarNota($idPeraca, $idCurso, $idEstudiante, $iTipoLab, $objDB, $idActividad=0, $iPuntajeMax=0, $bDebug=false, $iFechaCierre=0){
	$sError='';
	$sDebug='';
	if ($idActividad==0){
		list($idActividad, $iPuntajeMax, $sDebug)=f2111_TraerPuntajeAgenda($idPeraca, $idCurso, $iTipoLab, $objDB, $bDebug);
		if ($idActividad==0){
			$sError='No se ha hecho la integraci&oacute;n de la agenda en el centralizador de calificaciones.';
			}
		}
	if ($sError==''){
		if ($iFechaCierre==0){
			$iFechaCierre=f2111_FechaCierre($idPeraca, $idCurso, $objDB, $bDebug);
			}
		}
	if ($sError==''){
		$sSQL='SELECT TB.olab08id, TB.olab08realizado, T11.olab11id, T11.olab11nota, T11.olab11inforetro, T11.olab11puntaje, T11.olab11asiste1, T11.olab11asiste2, T11.olab11asiste3, T11.olab11asiste4, T11.olab11asiste5, TC.unad11idtablero
FROM olab08oferta AS TB, olab11cupos AS T11, unad11terceros AS TC  
WHERE TB.olab08idcurso='.$idCurso.' AND TB.olab08idperaca='.$idPeraca.' AND TB.olab08idtipooferta='.$iTipoLab.'
AND TB.olab08id=T11.olab11idoferta AND T11.olab11idestudiante='.$idEstudiante.' AND T11.olab11idestudiante=TC.unad11id';
		$tablac=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tablac)>0){
			$filac=$objDB->sf($tablac);
			$iFechaNota=0;
			if ($filac['olab08realizado']=='S'){$iFechaNota=$iFechaCierre;}
			//Ahora verificamos que tenga la actividad para el curso.
			$iContenedor=$filac['unad11idtablero'];
			$sSQL='SELECT TB.core05id, TB.core05estado, TB.core05idcupolab 
FROM core05actividades_'.$iContenedor.' AS TB 
WHERE TB.core05tercero='.$idEstudiante.' AND TB.core05idcurso='.$idCurso.' AND TB.core05peraca='.$idPeraca.' AND TB.core05idactividad='.$idActividad.'';
			$tabla5=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla5)>0){
				$fila5=$objDB->sf($tabla5);
				$bPasa=true;
				if ($fila5['core05estado']>6){
					//Ya fue calificado, asi que no se debe moficar facilmente.
					$bPasa=false;
					}
				if ($bPasa){
					$iNotaAlumno=$filac['olab11puntaje'];
					$iEstado=5;
					if ($filac['olab11asiste1']=='S'){
						$iEstado=3;
						}else{
						if ($filac['olab11asiste2']=='S'){
							$iEstado=3;
							}else{
							if ($filac['olab11asiste3']=='S'){
								$iEstado=3;
								}else{
								if ($filac['olab11asiste4']=='S'){
									$iEstado=3;
									}else{
									if ($filac['olab11asiste5']=='S'){
										$iEstado=3;
										}
									}
								}
							}
						}
					$sAdd='';
					if ($fila5['core05idcupolab']!=$filac['olab11id']){
						$sAdd=', core05idcupolab='.$filac['olab11id'].'';
						}
					$sSQL='UPDATE core05actividades_'.$iContenedor.' SET core05nota='.$iNotaAlumno.', core05fechanota='.$iFechaNota.', core05estado='.$iEstado.', core05retroalimentacion="Nota '.$filac['olab11nota'].' - '.$filac['olab11inforetro'].'" '.$sAdd.' WHERE core05id='.$fila5['core05id'].'';
					$result=$objDB->ejecutasql($sSQL);
					if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Actualizando nota: '.$sSQL.'<br>';}
					}
				}else{
				$sError='No se registra matricula activa del estudiante.';
				}
			}else{
			$sError='El estudiante esta inscrito en laboratorios';
			}
		}
	return array($sError, $sDebug);
	}
function f2111_FechaCierre($idPeraca, $idCurso, $objDB, $bDebug=false){
	$iFecha=0;
	$sSQL='SELECT olab25cerrado, olab25fechaaplicado FROM olab25centrarnotas WHERE olab25idperaca='.$idPeraca.' AND olab25idcurso='.$idCurso.'';
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		if ($fila['olab25cerrado']=='S'){
			$iFecha=fecha_EnNumero($fila['olab25fechaaplicado']);
			}
		}
	return $iFecha;
	}
function f2111_TraerPuntajeAgenda($idPeraca, $idCurso, $iTipoLab, $objDB, $bDebug=false){
	$iOrigen=$iTipoLab+2;
	$idActividad=0;
	$iPesoActividad=0;
	$sDebug='';
	$sSQL='SELECT ofer18idactividad, ofer18peso FROM ofer18cargaxnavxdia WHERE ofer18per_aca='.$idPeraca.' AND ofer18curso='.$idCurso.' AND ofer18numaula=1 AND ofer18origennota='.$iOrigen.'';
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$idActividad=$fila['ofer18idactividad'];
		$iPesoActividad=$fila['ofer18peso'];
		}
	return array($idActividad, $iPesoActividad, $sDebug);
	}
?>
