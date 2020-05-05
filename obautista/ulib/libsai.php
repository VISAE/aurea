<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2020 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.24.1 viernes, 21 de febrero de 2020
*/
function f3000_CalcularTotales($idTercero, $iAgno, $iMes, $objDB, $bDebug=false){
	$sError='';
	$sDebug='';
	$iMes=(int)$iMes;
	$sSQL='UPDATE saiu15historico SET saiu15numsolicitudes=0, saiu15numsupervisiones=0 WHERE saiu15idinteresado='.$idTercero.' AND saiu15agno='.$iAgno.' AND saiu15mes='.$iMes.'';
	$result=$objDB->ejecutasql($sSQL);
	$sSQL='SHOW TABLES LIKE "saiu05solicitud'.f3000_Contenedor($iAgno, $iMes).'"';
	if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Lista de bases: '.$sSQL.'<br>';}
	$tabla=$objDB->ejecutasql($sSQL);
	while($fila=$objDB->sf($tabla)){
		$sNomTabla=$fila[0];
		$iSolicitados=0;
		$iResponsable=0;
		$sSQL='SELECT saiu05tiporadicado, COUNT(saiu05id) AS Total FROM '.$sNomTabla.' WHERE saiu05idsolicitante='.$idTercero.' GROUP BY saiu05tiporadicado';
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consultando el mes: '.$sSQL.'<br>';}
		$tabla5=$objDB->ejecutasql($sSQL);
		while($fila5=$objDB->sf($tabla5)){
			$iSolicitados=$fila5['Total'];
			$sSQL='SELECT saiu15id FROM saiu15historico WHERE saiu15idinteresado='.$idTercero.' AND saiu15agno='.$iAgno.' AND saiu15mes='.$iMes.' AND saiu15tiporadicado='.$fila5['saiu05tiporadicado'].'';
			$tabla15=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla15)==0){
				$saiu15id=tabla_consecutivo('saiu15historico', 'saiu15id', '', $objDB);
				$sSQL='INSERT INTO saiu15historico (saiu15idinteresado, saiu15agno, saiu15mes, saiu15tiporadicado, saiu15id, saiu15numsolicitudes, saiu15numsupervisiones) VALUES ('.$idTercero.', '.$iAgno.', '.$iMes.', '.$fila5['saiu05tiporadicado'].', '.$saiu15id.', '.$iSolicitados.', '.$iResponsable.')';
				}else{
				$fila15=$objDB->sf($tabla15);
				$sSQL='UPDATE saiu15historico SET saiu15numsolicitudes='.$iSolicitados.', saiu15numsupervisiones='.$iResponsable.' WHERE saiu15id='.$fila15['saiu15id'].'';
				}
			if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Actualiza el historico: '.$sSQL.'<br>';}
			$result=$objDB->ejecutasql($sSQL);
			}
		}	
	return array($sError, $sDebug);
	}
function f3000_Contenedor($iAgno, $iMes){
	$iMes=(int)$iMes;
	if ($iMes<10){
		$sContenedor=$iAgno.'0'.$iMes;
		}else{
		$sContenedor=$iAgno.$iMes;
		}
	return '_'.$sContenedor.'';
	}
function f3000_Estado($idTercero, $objDB, $bDebug=false){
	$iEnProceso=0;
	$iACargo=0;
	$iVencidas=0;
	$iEnSupervision=0;
	$sDebug='';
	return array($iEnProceso, $iACargo, $iVencidas, $iEnSupervision, $sDebug);
	}
function f3000_NumSolicitud($iAgno, $iMes, $iConsec){
	$sRes=$iAgno.'-';
	$sRes=$sRes.formato_anchofijo($iMes, 2, '0');
	$sRes=$sRes.'-'.formato_anchofijo($iConsec, 8, '0');
	return $sRes;
	}
?>