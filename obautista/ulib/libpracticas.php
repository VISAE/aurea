<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2019 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
*/
function f2143_CargarDocumentos($id01, $objDB, $idTipoPractica=0, $bDebug=false){
	$sError='';
	$sDebug='';
	if ($idTipoPractica==0){
		$sSQL='SELECT core01idtipopractica FROM core01estprograma WHERE core01id='.$id01.'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$fila=$objDB->sf($tabla);
			$idTipoPractica=$fila['core01idtipopractica'];
			}
		}
	if ($idTipoPractica!=0){
		//Cargar los documentos solicitados para el tipo de practica.
		$aDocs=array();
		$iNumDocs=0;
		$sSQL='SELECT olab42id, olab42unicavez, olab42controlvence, olab42idtipodocgd FROM olab42tipopracdoc WHERE olab42idtipopractica='.$idTipoPractica.' ORDER BY olab42orden, olab42consec';
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Listado de documentos a incorporar: '.$sSQL.'<br>';}
		$tabla=$objDB->ejecutasql($sSQL);
		while($fila=$objDB->sf($tabla)){
			$iNumDocs++;
			$aDocs[$iNumDocs]['id']=$fila['olab42id'];
			$aDocs[$iNumDocs]['unico']=$fila['olab42unicavez'];
			$aDocs[$iNumDocs]['control']=$fila['olab42controlvence'];
			$aDocs[$iNumDocs]['tipogd']=$fila['olab42idtipodocgd'];
			}
		$scampos46='olab46idestprograma, olab46consec, olab46id, olab46iddoc, olab46idpractica, 
olab46iddocgd, olab46origen, olab46archivo, olab46fechadoc, olab46fechavence, 
olab46idaprueba, olab46fechaaprueba';
		$olab46consec=tabla_consecutivo('olab46practicadoc', 'olab46consec', 'olab46idestprograma='.$id01.'', $objDB);
		$olab46id=tabla_consecutivo('olab46practicadoc', 'olab46id', '', $objDB);
		for ($k=1;$k<=$iNumDocs;$k++){
			//Cada uno de los archivos deberia existir...
			$id=$aDocs[$k]['id'];
			$sSQL='SELECT 1 FROM olab46practicadoc WHERE olab46idestprograma='.$id01.' AND olab46iddoc='.$id.' AND olab46idpractica=0';
			$tabla=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla)==0){
				$svalores46=''.$id01.', '.$olab46consec.', '.$olab46id.', '.$id.', 0, 
'.$aDocs[$k]['tipogd'].', 0, 0, 0, 0, 
0, 0';
				$sSQL='INSERT INTO olab46practicadoc ('.$scampos46.') VALUES ('.$svalores46.');';
				if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Incorporando documento: '.$sSQL.'<br>';}
				$result=$objDB->ejecutasql($sSQL);
				$olab46consec++;
				$olab46id++;
				}
			}
		}
	return array($sError, $sDebug);
	}
function f2143_CargarPracticas($id01, $objDB, $bDebug=false){
	$sError='';
	$sDebug='';
	$core01idtercero=0;
	$core01idprograma=0;
	$sSQL='SELECT core01idtercero, core01idprograma, core01idtipopractica FROM core01estprograma WHERE core01id='.$id01.'';
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$core01idtercero=$fila['core01idtercero'];
		$idTipoPractica=$fila['core01idtipopractica'];
		}
	if ($idTipoPractica!=0){
		$iNumPracticas=0;
		$sSQL='SELECT olab41numpracticas FROM olab41tipopractica WHERE olab41id='.$idTipoPractica.'';
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Revisando info del tipo de practica: '.$sSQL.'<br>';}
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$fila=$objDB->sf($tabla);
			$iNumPracticas=$fila['olab41numpracticas'];
			}
		$scampos45='olab45idestprograma, olab45numpractica, olab45id, olab45idtipopractica, olab45fechaaprueba, 
olab45idaprueba, olab45fechainicio, olab45fechafin, olab45idperiodo, olab45idcurso, 
olab45idtutor, olab45idmonitor, olab45idcupopractica, olab45nota, olab45fechanota, 
olab45idcalifica, olab45aprobada, olab45idestudiante, olab45idprograma, olab45idescenario';
		$olab45id=tabla_consecutivo('olab45practica', 'olab45id', '', $objDB);
		for ($k=1;$k<=$iNumPracticas;$k++){
			//x practicas deberian existir...
			$sSQL='SELECT 1 FROM olab45practica WHERE olab45idestprograma='.$id01.' AND olab45numpractica='.$k.'';
			$tabla=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla)==0){
				$svalores45=''.$id01.', '.$k.', '.$olab45id.', '.$idTipoPractica.', 0, 
0, 0, 0, 0, 0, 
0, 0, 0, 0, 0, 0, 
0, "N", '.$core01idtercero.', '.$core01idprograma.', 0';
				$sSQL='INSERT INTO olab45practica ('.$scampos45.') VALUES ('.$svalores45.');';
				if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Incorporando practica: '.$sSQL.'<br>';}
				$result=$objDB->ejecutasql($sSQL);
				$olab45id++;
				}
			}
		}
	return array($sError, $sDebug);
	}
?>