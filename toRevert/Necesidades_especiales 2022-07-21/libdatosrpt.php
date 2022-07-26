<?php
function f146_TituloPeriodo($idPeriodo, $objDB, $bDebug=false){
    $sDebug='';
    $sRes='';
    if ((int)$idPeriodo!=0){
		$sRes='{'.$idPeriodo.'}';
		$sSQL='SELECT exte02nombre FROM exte02per_aca WHERE exte02id='.$idPeriodo.'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$fila=$objDB->sf($tabla);
			$sRes=$fila['exte02nombre'];
			}
        }
    return array($sRes, $sDebug);
    }
?>