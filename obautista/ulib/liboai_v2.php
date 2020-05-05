<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2020 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.24.0 viernes, 24 de enero de 2020
*/
function f1718_ValidarAgenda($idPeraca, $idCurso, $objDB, $bDebug=false){
	$sError='';
	$sDebug='';
	$sSQL='SELECT TB.ofer08idagenda 
FROM ofer08oferta AS TB 
WHERE TB.ofer08idper_aca='.$idPeraca.' AND TB.ofer08idcurso='.$idCurso.'';
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		if ($fila['ofer08idagenda']==0){
			$sError='No se ha seleccionado una agenda para el curso.';
			}
		}
	if ($sError==''){
		$sSQL='SELECT TB.ofer18idact_moodle, TB.ofer18origennota 
FROM ofer18cargaxnavxdia AS TB 
WHERE TB.ofer18curso='.$idCurso.' AND TB.ofer18per_aca='.$idPeraca.' AND TB.ofer18numaula=1';
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Buscando actividades en la agenda: '.$sSQL.'<br>';}
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			while($fila=$objDB->sf($tabla)){
				if ($fila['ofer18idact_moodle']==0){
					$sError='No se ha hecho la integraci&oacute;n de la agenda para el centralizador de calificaciones.';
					break;
					}
				if ($fila['ofer18origennota']==0){
					$sError='No se ha hecho la integraci&oacute;n de la agenda para el centralizador de calificaciones.';
					break;
					}
				}
			}else{
			$sError='La agenda del curso no ha sido generada.';
			}
		}
	return array($sError, $sDebug);
	}
function f1718_GuardarCuadro($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	$sError='';
	$sDebug='';
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$idcampo=$aParametros[1];
	$idActividad=$aParametros[2];
	$valor=htmlspecialchars($aParametros[3]);
	$sValor2=htmlspecialchars($aParametros[4]);
	$origen=$aParametros[3];
	//$idPadre=$aParametros[4];
	$idPeraca=numeros_validar($aParametros[5]);
	$idCurso=numeros_validar($aParametros[6]);
	$idAula=numeros_validar($aParametros[7]);
	$sCampo='';
	$iTipoError=0;
	$bVolverOrigen=false;
	switch($idcampo){
		case 13:
		$sCampo='ofer18origennota';
		$valor=numeros_validar($valor);
		if ($valor==''){$valor=0;}
		break;
		case 14:
		$sCampo='ofer18idact_moodle';
		break;
		}
	$bPaginar=false;
	if ($sError==''){
		switch($sCampo){
			case 'ofer18origennota':
			if ($valor>1){
				//Es un laboratorio o una salida
				$sSQL='SELECT ofer18idactividad FROM ofer18cargaxnavxdia WHERE ofer18per_aca='.$idPeraca.' AND ofer18curso='.$idCurso.'  AND ofer18numaula='.$idAula.' AND ofer18idactividad<>'.$idActividad.' AND ofer18origennota='.$valor.'';
				$tabla=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($tabla)>0){
					$sError='Solo se permite una actividad asociada a laboratorio';
					if ($valor==3){$sError='Solo se permite una actividad asociada a salidas de campo';}
					$valor=1;
					$bVolverOrigen=true;
					}
				}
			$sSQL='UPDATE ofer18cargaxnavxdia SET '.$sCampo.'='.$valor.' WHERE ofer18idactividad='.$idActividad.' AND ofer18per_aca='.$idPeraca.' AND ofer18curso='.$idCurso.'  AND ofer18numaula='.$idAula.';';
			$result=$objDB->ejecutasql($sSQL);
			break;
			case 'ofer18idact_moodle':
			$sSQL='UPDATE ofer18cargaxnavxdia SET '.$sCampo.'="'.$valor.'" WHERE ofer18idactividad='.$idActividad.' AND ofer18per_aca='.$idPeraca.' AND ofer18curso='.$idCurso.'  AND ofer18numaula='.$idAula.';';
			$result=$objDB->ejecutasql($sSQL);
			break;
			case '':
			$sError='No se ha encontrado el campo '.$idcampo;
			break;
			}
		}
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	//$objResponse->call("MensajeAlarmaV2('".$sError."', ".$iTipoError.")");
	if (($sError=='')){
		if ($bPaginar){
			$objResponse->call('paginarf1718');
			}else{
			//$objResponse->call('MensajeAlarmaV2("Se ha acualizado el valor en la lista {'.$ofer18fase.'}", 1)');
			$objResponse->assign('div_fila'.$idActividad, 'innerHTML', '<span class="verde">Guardado</span>');
			if ($origen===$valor){
				}else{
				$objResponse->assign($sCampo.'_'.$idActividad, 'value', $valor);
				}
			}
		}else{
		$objResponse->assign('div_fila'.$idActividad, 'innerHTML', '<span class="rojo">'.$sError.'</span>');
		if ($bVolverOrigen){
			$objResponse->assign('ofer18origennota_'.$idActividad, 'value', 1);
			}
		}
	return $objResponse;
	}
?>