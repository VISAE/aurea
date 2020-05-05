<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2020 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Version 2.24.1 martes, 11 de febrero de 2020
*/
function exte03_actualizar($objDB){
	$sError='';
	/*
	//Se debe buscar actualizar la tabla core09
	//exte03programa
	$scampos='exte03consec, exte03id, exte03idescuela, exte03nombre, exte03idcoordinador';
	$sSQL='SELECT c_programas, pro_descripcion, codigo_escuela FROM programas WHERE c_programas NOT IN (SELECT exte03id FROM exte03programa)';
	$tabla=$objDB->ejecutasql($sSQL);
	while ($fdato=$objDB->sf($tabla)){
		$sSQL='INSERT INTO exte03programa (exte03consec, exte03id, exte03idescuela, exte03nombre, exte03idcoordinador) VALUES ('.$fdato['c_programas'].', '.$fdato['c_programas'].', '.$fdato['codigo_escuela'].', "'.$fdato['pro_descripcion'].'", 0)';
		$resultado=$objDB->ejecutasql($sSQL);
		if ($resultado==false){
			if ($sError!=''){$sError=$sError.'<br>';}
			$sError=$sError.$sSQL;
			}
		}
	*/
	//Ahora lo mismo pero con la core09
	$scampos='core09codigo, core09id, core09nombre, core09idescuela, core09iddirector, core09idversionactual, core09activo, core09idtipocaracterizacion';
	$sSQL='SELECT c_programas, pro_descripcion, codigo_escuela FROM programas WHERE c_programas NOT IN (SELECT core09id FROM core09programa)';
	$tabla=$objDB->ejecutasql($sSQL);
	while ($fdato=$objDB->sf($tabla)){
		$sSQL='INSERT INTO core09programa (core09codigo, core09id, core09idescuela, core09nombre, core09iddirector, core09idversionactual, core09activo, core09idtipocaracterizacion) VALUES ('.$fdato['c_programas'].', '.$fdato['c_programas'].', '.$fdato['codigo_escuela'].', "'.$fdato['pro_descripcion'].'", 0, 0, "S", 0)';
		$resultado=$objDB->ejecutasql($sSQL);
		if ($resultado==false){
			if ($sError!=''){$sError=$sError.'<br>';}
			$sError=$sError.$sSQL;
			}
		}
	return $sError;
	}
function unad40_actualizar($objDB){
	list($sError, $sDebug)=unad40_actualizarV2($objDB);
	}
function unad40_actualizarV2($objDB, $bDebug=false){
	//Enero 26 de 2015 - se agrega num creditos.
	$sError='';
	$sDebug='';
	$bHayDBRyC=false;
	list($objRyC, $sDebug)=TraerDBRyCV2();
	if ($objRyC==NULL){
		$sError='No ha sido posible conectarse con el origen de datos';
		}else{
		$bHayDBRyC=true;
		}
	if ($sError==''){
		$sCampos40='unad40consec, unad40id, unad40nombre, unad40titulo, unad40idagenda, 
unad40diainical, unad40numestudiantes, unad40idnav, unad40tipocurso, unad40tipostandard, 
unad40nivelformacion, unad40numcreditos, unad40idescuela, unad40idprograma';
		$sId='-99';
		$sSQL='SELECT unad40id FROM unad40curso';
		$tabla=$objDB->ejecutasql($sSQL);
		while($fila=$objDB->sf($tabla)){
			$sId=$sId.','.$fila['unad40id'];
			}
		$sSQL='SELECT consecutivo, mat_descripcion, codigo_escuela, mat_pro, intensidad FROM materias WHERE c_pensum=3 AND consecutivo NOT IN ('.$sId.') AND consecutivo<9999999999';
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Cursos pendiente de ser incluidos [RyC]:'.$sSQL.'<br>';}
		$tabla=$objRyC->ejecutasql($sSQL);
		while ($fdato=$objRyC->sf($tabla)){
			$sValores40=''.$fdato['consecutivo'].', '.$fdato['consecutivo'].', "'.$fdato['mat_descripcion'].'", '.$fdato['consecutivo'].', 0, 
0, 0, 0, 1, 1, 
1, '.$fdato['intensidad'].', '.$fdato['codigo_escuela'].', '.$fdato['mat_pro'].'';
			$sSQL='INSERT INTO unad40curso ('.$sCampos40.') VALUES ('.$sValores40.')';
			$resultado=$objDB->ejecutasql($sSQL);
			if ($resultado==false){
				if ($sError!=''){$sError=$sError.'<br>';}
				$sError=$sError.$sSQL;
				}
			}
		//Actualizar escuelas.
		/*
		$sSQL='SELECT consecutivo, codigo_escuela, mat_pro FROM materias WHERE c_pensum=3 AND consecutivo IN (SELECT unad40id FROM unad40curso WHERE unad40idescuela=0) AND consecutivo<9999999999 AND codigo_escuela<>0';
		$tabla=$objDB->ejecutasql($sSQL);
		while ($fdato=$objDB->sf($tabla)){
			$sSQL='UPDATE unad40curso SET unad40idescuela='.$fdato['codigo_escuela'].', unad40idprograma='.$fdato['mat_pro'].' WHERE unad40id='.$fdato['consecutivo'];
			$resultado=$objDB->ejecutasql($sSQL);
			if ($resultado==false){
				//if ($sError!=''){$sError=$sError.'<br>';}
				//$sError=$sError.$sSQL;
				}
			}
		*/
		//Actualizar los cursos sin creditos.
		/*
		$sSQL='SELECT consecutivo, intensidad FROM materias WHERE c_pensum=3 AND consecutivo IN (SELECT unad40id FROM unad40curso WHERE unad40numcreditos=0) AND consecutivo<9999999999 AND intensidad>0';
		$tabla=$objDB->ejecutasql($sSQL);
		while ($fdato=$objDB->sf($tabla)){
			$sSQL='UPDATE unad40curso SET unad40numcreditos='.$fdato['intensidad'].' WHERE unad40id='.$fdato['consecutivo'];
			$resultado=$objDB->ejecutasql($sSQL);
			if ($resultado==false){
				//if ($sError!=''){$sError=$sError.'<br>';}
				//$sError=$sError.$sSQL;
				}
			}
		*/
		}
	if ($bHayDBRyC){
		$objRyC->CerrarConexion();
		}
	return array($sError, $sDebug);
	}
?>