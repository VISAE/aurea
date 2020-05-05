<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2020 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.24.1 martes, 11 de febrero de 2020
--- libreria para integración en RyC
*/
function f1757_OfertarRyC($idPeraca, $idCurso, $idUsuario, $objDB, $bDebug=false){
	$sError='';
	$sDebug='';
	$iNumProgramas=0;
	if ($idPeraca>761){
		list($objDBRyC, $sDebugR)=TraerDBRyCV2($bDebug);
		}else{
		list($objDBRyC, $sDebugR)=TraerDBRyCPruebas($bDebug);
		}
	$sDebug=$sDebug.$sDebugR;
	if ($objDBRyC==NULL){
		$sError='No fue posible conectar con Registro y Control.';
		}
	if ($sError==''){
		if ($idUsuario==0){
			//Estamos repasando el tema..
			//Traemos el tercero que lo oferto. 
			//ofer64idaprobacion='.$idTercero.', ofer64estadoaprobacion=1
			$sSQL='SELECT ofer64id, ofer64idaprobacion, ofer64estadoaprobacion, ofer64estado, ofer64idescuela, ofer64fechaoferta, ofer64preaprobado FROM ofer64preofanalisis WHERE ofer64idperiodo='.$idPeraca.' AND ofer64idcurso='.$idCurso.'';
			if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Trayendo informaci&oacute;n del curso: '.$sSQL.'<br>';}
			$tabla=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla)>0){
				$fila=$objDB->sf($tabla);
				if ($fila['ofer64fechaoferta']==0){
					$iHoy=$fila['ofer64preaprobado'];
					}else{
					$iHoy=$fila['ofer64fechaoferta'];
					}
				if ($fila['ofer64idaprobacion']==0){
					//No hay un id que aprobo.... debe ser el secretario academico...
					$sSQL='SELECT core12idadministrador FROM core12escuela WHERE core12id='.$fila['ofer64idescuela'].'';
					if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Buscando al secretario academico: '.$sSQL.'<br>';}
					$tabla12=$objDB->ejecutasql($sSQL);
					if ($objDB->nf($tabla12)>0){
						$fila12=$objDB->sf($tabla12);
						if ($fila12['core12idadministrador']!=0){
							$idUsuario=$fila12['core12idadministrador'];
							$sSQL='UPDATE ofer64preofanalisis SET ofer64idaprobacion='.$idUsuario.', ofer64estadoaprobacion=1 WHERE ofer64id='.$fila['ofer64id'].'';
							if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Actualizando datos del ofertante: '.$sSQL.'<br>';}
							$result=$objDB->ejecutasql($sSQL);
							}else{
							$sError='No se ha definido el administrador de la escuela.';
							}
						}else{
						$sError='No se ha encontrado informaci&oacute;n de la escuela.';
						}
					}else{
					$idUsuario=$fila['ofer64idaprobacion'];
					}
				}else{
				$sError='No se ha encontrado analisis de oferta para el curso '.$idCurso.' periodo '.$idPeraca.'';
				}
			}else{
			$iHoy=fecha_DiaMod();
			}
		}
	if ($sError==''){
		//Alisto el documento del usuario.
		$sDoc='';
		$sHoy=date('Y-m-d H:i:s');
		$sSQL='SELECT unad11doc FROM unad11terceros WHERE unad11id='.$idUsuario.'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$fila=$objDB->sf($tabla);
			$sDoc=$fila['unad11doc'];
			}else{
			$sError='No ha sido posible encontrar el usuario que oferta [Ref '.$idUsuario.'].';
			}
		}
	if ($sError==''){
		//Inactivar toda la oferta de ese curso para ese peraca.
		$sSQL='UPDATE oferta_cursos SET estado=0 WHERE ms_codigo='.$idCurso.' AND per_aca='.$idPeraca.'';
		$result=$objDBRyC->ejecutasql($sSQL);
		$sSQL='UPDATE oferta_cursos_sena SET estado=0 WHERE ms_codigo='.$idCurso.' AND per_aca='.$idPeraca.'';
		$result=$objDBRyC->ejecutasql($sSQL);
		$sSQL='UPDATE oferta_cursos_florida SET estado=0 WHERE ms_codigo='.$idCurso.' AND per_aca='.$idPeraca.'';
		$result=$objDBRyC->ejecutasql($sSQL);
		//Ubicar planes de estudio donde el curso fue solicitado.
		$sSQL='SELECT TB.ofer55id, TB.ofer55idprograma, TB.ofer55proyestnuevos, TB.ofer55proyestantiguos, TB.ofer55nivel, T11.core11obligarorio, TB.ofer55fecharyc, TB.ofer55idescuela 
FROM ofer55preofertacurso AS TB, core09programa AS T6, core10programaversion AS T7, core11plandeestudio AS T11 
WHERE TB.ofer55idcurso='.$idCurso.' AND TB.ofer55periodo='.$idPeraca.' AND TB.ofer55estado=1 AND (TB.ofer55proyestnuevos+TB.ofer55proyestantiguos)>0 
 AND TB.ofer55idprograma=T6.core09id AND TB.ofer55idversionprog=T7.core10id
AND T7.core10id=T11.core11idversionprograma AND T11.core11idcurso='.$idCurso.'';
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' ENVIO DE OFERTA: Listado de programas para el curso: '.$sSQL.'<br>';}
		$tabla=$objDB->ejecutasql($sSQL);
		$iNumProgramas=$objDB->nf($tabla);
		}
	if ($sError==''){
		$sCamposOC='ms_codigo, ms_semestre, ms_programa, ms_pensum, per_aca, 
basico, estado, basico_programa, tipo_estudiante, usuario, 
fecha';
		//Ahora si activo los programas.
		while($fila=$objDB->sf($tabla)){
			$iBasicoPrograma=0;
			//$iObligatorio=$fila['core11obligarorio'];
			//$iBasicoPrograma=$iObligatorio;
			$iBasicoPrograma=$fila['core11obligarorio'];
			$iObligatorio=0;
			$sTipoEst='H';
			if ($fila['ofer55proyestnuevos']>0){
				$sTipoEst='G';
				}
			if ($fila['ofer55idescuela']==45000){
				$sSQL='SELECT 1 FROM oferta_cursos_florida WHERE ms_codigo='.$idCurso.' AND per_aca='.$idPeraca.' AND ms_programa='.$fila['ofer55idprograma'].'';
				$tablaOC=$objDBRyC->ejecutasql($sSQL);
				if ($objDBRyC->nf($tablaOC)==0){
					$sValoresOC=''.$idCurso.', 2, '.$fila['ofer55idprograma'].', 3, '.$idPeraca.', 
'.$iObligatorio.', 1, '.$iBasicoPrograma.', "'.$sTipoEst.'", "'.$sDoc.'", 
"'.$sHoy.'"';
					$sSQL='INSERT INTO oferta_cursos_florida('.$sCamposOC.') VALUES ('.$sValoresOC.')';
					if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' ENVIO DE OFERTA: (RyC - FL) '.$sSQL.'<br>';}
					$result=$objDBRyC->ejecutasql($sSQL);
					}else{
					$sSQL='UPDATE oferta_cursos_florida SET estado=1, tipo_estudiante="'.$sTipoEst.'" WHERE ms_codigo='.$idCurso.' AND per_aca='.$idPeraca.' AND ms_programa='.$fila['ofer55idprograma'].'';
					if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' ENVIO DE OFERTA: (RyC - FL) '.$sSQL.'<br>';}
					$result=$objDBRyC->ejecutasql($sSQL);
					}
				}else{
			//Oferta General.
			$sSQL='SELECT 1 FROM oferta_cursos WHERE ms_codigo='.$idCurso.' AND per_aca='.$idPeraca.' AND ms_programa='.$fila['ofer55idprograma'].'';
			$tablaOC=$objDBRyC->ejecutasql($sSQL);
			if ($objDBRyC->nf($tablaOC)==0){
				$sValoresOC=''.$idCurso.', 2, '.$fila['ofer55idprograma'].', 3, '.$idPeraca.', 
'.$iObligatorio.', 1, '.$iBasicoPrograma.', "'.$sTipoEst.'", "'.$sDoc.'", 
"'.$sHoy.'"';
				$sSQL='INSERT INTO oferta_cursos('.$sCamposOC.') VALUES ('.$sValoresOC.')';
				if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' ENVIO DE OFERTA: (RyC) '.$sSQL.'<br>';}
				$result=$objDBRyC->ejecutasql($sSQL);
				}else{
				$sSQL='UPDATE oferta_cursos SET estado=1, tipo_estudiante="'.$sTipoEst.'" WHERE ms_codigo='.$idCurso.' AND per_aca='.$idPeraca.' AND ms_programa='.$fila['ofer55idprograma'].'';
				if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' ENVIO DE OFERTA: (RyC) '.$sSQL.'<br>';}
				$result=$objDBRyC->ejecutasql($sSQL);
				}
			//Antes de inertarlo ver si aplica el programa.
			$bAplicaAcuerdos=false;
			$sSQL='SELECT 1 FROM acuerdos_sena WHERE programa_unad='.$fila['ofer55idprograma'].'';
			$tablaOC=$objDBRyC->ejecutasql($sSQL);
			if ($objDBRyC->nf($tablaOC)>0){
				$bAplicaAcuerdos=true;
				}
			if ($bAplicaAcuerdos){
				$sSQL='SELECT 1 FROM oferta_cursos_sena WHERE ms_codigo='.$idCurso.' AND per_aca='.$idPeraca.' AND ms_programa='.$fila['ofer55idprograma'].'';
				$tablaOC=$objDBRyC->ejecutasql($sSQL);
				if ($objDBRyC->nf($tablaOC)==0){
					$sValoresOC=''.$idCurso.', 2, '.$fila['ofer55idprograma'].', 3, '.$idPeraca.', 
'.$iObligatorio.', 1, '.$iBasicoPrograma.', "'.$sTipoEst.'", "'.$sDoc.'", 
"'.$sHoy.'"';
					$sSQL='INSERT INTO oferta_cursos_sena('.$sCamposOC.') VALUES ('.$sValoresOC.')';
					if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' ENVIO DE OFERTA: (RyC - SENA) '.$sSQL.'<br>';}
					$result=$objDBRyC->ejecutasql($sSQL);
					}else{
					$sSQL='UPDATE oferta_cursos_sena SET estado=1, tipo_estudiante="'.$sTipoEst.'" WHERE ms_codigo='.$idCurso.' AND per_aca='.$idPeraca.' AND ms_programa='.$fila['ofer55idprograma'].'';
					if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' ENVIO DE OFERTA: (RyC - SENA) '.$sSQL.'<br>';}
					$result=$objDBRyC->ejecutasql($sSQL);
					}
				}
				}
			//Reportarlo como ofertado...
			if ($fila['ofer55fecharyc']==0){
				$sSQL='UPDATE ofer55preofertacurso SET ofer55fecharyc='.$iHoy.' WHERE ofer55id='.$fila['ofer55id'].'';
				if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' ENVIO DE OFERTA: '.$sSQL.'<br>';}
				$result=$objDB->ejecutasql($sSQL);
				}
			}
		}
	if ($sError==''){
		}
	return array($iNumProgramas, $sError, $sDebug);
	}
?>