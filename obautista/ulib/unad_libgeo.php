<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2017 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
*/
function geo_Distancia($punto1_lat, $punto1_lon, $punto2_lat, $punto2_lon){
	$degrees = rad2deg(acos((sin(deg2rad($punto1_lat))*sin(deg2rad($punto2_lat))) + (cos(deg2rad($punto1_lat))*cos(deg2rad($punto2_lat))*cos(deg2rad($punto1_lon-$punto2_lon)))));
	$km=round($degrees * 111.13384, 2);
	return $km;
	}
function geo_Analizar($idTercero, $objdb, $bDebug=false){
	$sError='';
	$sDebug='';
	//Primero Alistamos las conexiones frecuentes.
	$aFrec=array();
	$iFrec=0;
	$sql='SELECT unad82id, unad82latgrados, unad82latdecimas, unad82longrados, unad82longdecimas FROM unad82puntosfrec WHERE unad82idtercero='.$idTercero.'';
	$tabla=$objdb->ejecutasql($sql);
	while ($fila=$objdb->sf($tabla)){
		$iFrec++;
		$sDecLat=formato_anchofijo($fila['unad82latdecimas'], 7, '0', false);
		$sDecLon=formato_anchofijo($fila['unad82longdecimas'], 7, '0', false);
		$aFrec[$iFrec]['id']=$fila['unad82id'];
		if ($fila['unad82latgrados']<0){
			$aFrec[$iFrec]['lat']=$fila['unad82latgrados']-($sDecLat/10000000);
			}else{
			$aFrec[$iFrec]['lat']=$fila['unad82latgrados']+($sDecLat/10000000);
			}
		if ($fila['unad82longrados']<0){
			$aFrec[$iFrec]['lon']=$fila['unad82longrados']-($sDecLon/10000000);
			}else{
			$aFrec[$iFrec]['lon']=$fila['unad82longrados']+($sDecLon/10000000);
			}
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Frecuente '.$iFrec.' '.$aFrec[$iFrec]['lat'].' '.$aFrec[$iFrec]['lon'].'<br>';}
		}
	//Julio 24 de 2017 - la tabla unad71sesion fue dividida en varias, asi que toca analizar las tablas que existen y hacer el recorrido.
	$sql='SHOW TABLES LIKE "unad71sesion%"';
	$tbase=$objdb->ejecutasql($sql);
	while ($fbase=$objdb->sf($tbase)){
		$sNomTabla71=$fbase[0];
		//Marcar las que no tienen lectura como fallidas.
		$sql='UPDATE '.$sNomTabla71.' SET unad71estado=2 WHERE unad71idtercero='.$idTercero.' AND unad71longrados=""';
		$tabla=$objdb->ejecutasql($sql);
		//Primero se calculan las frecuentes....
		$sCampos1582='unad82idtercero, unad82consec, unad82id, unad82ip, unad82latgrados, unad82latdecimas, unad82longrados, unad82longdecimas, unad82fechareg';
		$unad82consec=tabla_consecutivo('unad82puntosfrec', 'unad82consec', 'unad82idtercero='.$idTercero.'', $objdb);
		$unad82id=tabla_consecutivo('unad82puntosfrec','unad82id', '', $objdb);
		$unad82fechareg=fecha_DiaMod();
		$sql='SELECT unad71iporigen, unad71latgrados, SUBSTRING(unad71latdecimas,1,3) AS LatDec, unad71longrados, SUBSTRING(unad71longdecimas,1,3) AS LonDec, COUNT(unad71id)
FROM '.$sNomTabla71.'
WHERE unad71idtercero='.$idTercero.' AND unad71longrados<>"" AND unad71proximidad<1000 AND unad71estado IN (0,1)
GROUP BY unad71iporigen, unad71latgrados, SUBSTRING(unad71latdecimas,1,3), unad71longrados, SUBSTRING(unad71longdecimas,1,3)
HAVING COUNT(unad71id)>2';
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta de Frecuentes '.$sql.'<br>';}
		$tablafrec=$objdb->ejecutasql($sql);
		while ($filafrec=$objdb->sf($tablafrec)){
			//Ver si el punto ya esta.
			$bExiste=false;
			$sql='SELECT unad82id FROM unad82puntosfrec WHERE unad82idtercero='.$idTercero.' AND unad82ip="'.$filafrec['unad71iporigen'].'" AND unad82latgrados='.$filafrec['unad71latgrados'].' AND SUBSTRING(unad82latdecimas,1,3)="'.$filafrec['LatDec'].'" AND unad82longrados='.$filafrec['unad71longrados'].' AND SUBSTR(unad82longdecimas,1,3)="'.$filafrec['LonDec'].'"';
			$tabla81=$objdb->ejecutasql($sql);
			if ($objdb->nf($tabla81)>0){
				$bExiste=true;
				if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Existe '.$filafrec['unad71iporigen'].' '.$filafrec['unad71latgrados'].' '.$filafrec['LatDec'].' '.$filafrec['unad71longrados'].' '.$filafrec['LonDec'].'<br>';}
				}
			if (!$bExiste){
				$sLat=$filafrec['LatDec'];
				$sLong=$filafrec['LonDec'];
				//Capturar uno de los puntos frecuentes para guardarlo
				$sql='SELECT unad71latdecimas, unad71longdecimas, COUNT(unad71id)
FROM '.$sNomTabla71.'
WHERE unad71idtercero='.$idTercero.' AND unad71iporigen="'.$filafrec['unad71iporigen'].'" AND unad71latgrados='.$filafrec['unad71latgrados'].' AND SUBSTRING(unad71latdecimas,1,3)="'.$filafrec['LatDec'].'" AND unad71longrados='.$filafrec['unad71longrados'].' AND SUBSTR(unad71longdecimas,1,3)="'.$filafrec['LonDec'].'" AND unad71proximidad<1000 AND unad71estado IN (0,1)
GROUP BY unad71latdecimas, unad71longdecimas
ORDER BY COUNT(unad71id), unad71latgrados LIMIT 0,1';
				$tabladet=$objdb->ejecutasql($sql);
				if ($objdb->nf($tabladet)>0){
					$filadet=$objdb->sf($tabladet);
					$sLat=$filadet['unad71latdecimas'];
					$sLong=$filadet['unad71longdecimas'];
					}
				//Insertarlo.
				$sValores1582=''.$idTercero.', '.$unad82consec.', '.$unad82id.', "'.$filafrec['unad71iporigen'].'", '.$filafrec['unad71latgrados'].', "'.$sLat.'", '.$filafrec['unad71longrados'].', "'.$sLong.'", '.$unad82fechareg.'';
				$sql='INSERT INTO unad82puntosfrec ('.$sCampos1582.') VALUES ('.$sValores1582.');';
				$result=$objdb->ejecutasql($sql);
				$unad82consec++;
				$unad82id++;
				}
			}
		$sql='SELECT unad71id, unad71fechaini, unad71horaini, unad71minutoini, unad71iporigen, unad71latgrados, unad71latdecimas, unad71longrados, unad71longdecimas, unad71proximidad
FROM '.$sNomTabla71.'
WHERE unad71idtercero='.$idTercero.' AND unad71longrados<>"" AND unad71estado=0
ORDER BY unad71id';
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Sesiones por revisar '.$sql.'<br>';}
		$tabla71=$objdb->ejecutasql($sql);
		while ($fila71=$objdb->sf($tabla71)){
			$bProcesada=false;
			if ($fila71['unad71proximidad']>999){
				$sql='UPDATE '.$sNomTabla71.' SET unad71estado=2 WHERE unad71id='.$fila71['unad71id'].'';
				$result=$objdb->ejecutasql($sql);
				$bProcesada=true;
				if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Sesion '.$fila71['unad71id'].' es fallida<br>';}
				}
			if (!$bProcesada){
				$sql='SELECT unad82id FROM unad82puntosfrec WHERE unad82idtercero='.$idTercero.' AND unad82ip="'.$fila71['unad71iporigen'].'" AND unad82latgrados='.$fila71['unad71latgrados'].' AND SUBSTRING(unad82latdecimas,1,3)="'.substr($fila71['unad71latdecimas'], 0, 3).'" AND unad82longrados='.$fila71['unad71longrados'].' AND SUBSTR(unad82longdecimas,1,3)="'.substr($fila71['unad71longdecimas'], 0, 3).'"';
				//if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consultando si es frecuente '.$sql.'<br>';}
				$tabla81=$objdb->ejecutasql($sql);
				if ($objdb->nf($tabla81)>0){
					$sql='UPDATE '.$sNomTabla71.' SET unad71estado=1 WHERE unad71id='.$fila71['unad71id'].'';
					$result=$objdb->ejecutasql($sql);
					$bProcesada=true;
					if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Sesion '.$fila71['unad71id'].' es frecuente<br>';}
					}
				}
			if (!$bProcesada){
				//Tiene una IP QUE ES FRECUENTE PERO LA COORDENADA ESTA SALIDA DE RANGO, SE MARCA COMO FALLIDA.
				$sql='SELECT unad82id FROM unad82puntosfrec WHERE unad82idtercero='.$idTercero.' AND unad82ip="'.$fila71['unad71iporigen'].'"';
				//if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consultando si es frecuente '.$sql.'<br>';}
				$tabla81=$objdb->ejecutasql($sql);
				if ($objdb->nf($tabla81)>0){
					$sql='UPDATE '.$sNomTabla71.' SET unad71estado=2, unad71proximidad=20000 WHERE unad71id='.$fila71['unad71id'].'';
					$result=$objdb->ejecutasql($sql);
					$bProcesada=true;
					if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Sesion '.$fila71['unad71id'].' es fallida con IP Frecuente<br>';}
					}
				}
			if (!$bProcesada){
				//Calcular la distancia del punto...
				$iMin=999999999;
				$sDecLat=formato_anchofijo($fila71['unad71latdecimas'], 7, '0', false);
				$sDecLon=formato_anchofijo($fila71['unad71longdecimas'], 7, '0', false);
				if ($fila['unad71latgrados']<0){
					$pLat=$fila71['unad71latgrados']-($sDecLat/10000000);
					}else{
					$pLat=$fila71['unad71latgrados']+($sDecLat/10000000);
					}
				if ($fila['unad71longrados']<0){
					$pLon=$fila71['unad71longrados']-($sDecLon/10000000);
					}else{
					$pLon=$fila71['unad71longrados']+($sDecLon/10000000);
					}
				if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Punto '.$pLat.' '.$pLon.'<br>';}
				for($k=1;$k<=$iFrec;$k++){
					$iDis=geo_Distancia($aFrec[$k]['lat'], $aFrec[$k]['lon'], $pLat, $pLon);
					if ($iDis<$iMin){$iMin=$iDis;}
					if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Distancia de '.$pLat.' '.$pLon.' a '.$aFrec[$k]['lat'].' '.$aFrec[$k]['lon'].' = '.$iDis.'<br>';}
					}
				if ($iMin<20){
					//Distancia menor a 20 km por lo tanto frecueente...
					$sql='UPDATE '.$sNomTabla71.' SET unad71estado=1 WHERE unad71id='.$fila71['unad71id'].'';
					$result=$objdb->ejecutasql($sql);
					$bProcesada=true;
					if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Sesion '.$fila71['unad71id'].' es frecuente por cercania<br>';}
					}else{
					$bHayVarias=false;
					$sql='SELECT unad71id
FROM '.$sNomTabla71.'
WHERE unad71idtercero='.$idTercero.' AND unad71fechaini='.$fila71['unad71fechaini'].'';
					$tablaver=$objdb->ejecutasql($sql);
					if ($objdb->nf($tablaver)>1){$bHayVarias=true;}
					if ($bHayVarias){
						$sql='UPDATE '.$sNomTabla71.' SET unad71estado=4 WHERE unad71id='.$fila71['unad71id'].'';
						$result=$objdb->ejecutasql($sql);
						$bProcesada=true;
						if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Sesion '.$fila71['unad71id'].' Varias Sesiones en un dia...<br>';}
						}else{
						$sql='UPDATE '.$sNomTabla71.' SET unad71estado=3 WHERE unad71id='.$fila71['unad71id'].'';
						$result=$objdb->ejecutasql($sql);
						$bProcesada=true;
						if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Sesion '.$fila71['unad71id'].' es distancia lejana...<br>';}
						}
					}
				}
			}
		//fin de analizar la tabla....
		}
	return array($sError, $sDebug);
	}
?>