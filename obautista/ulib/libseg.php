<?php
/*
--- © Angel Mauro Avellaneda Barreto - Ideas - 2019 ---
--- mauro@avellaneda.co - http://www.ideasw.com
--- Modelo Versión 2.22.6c jueves, 11 de julio de 2019
--- Esta libreria contiene funciones que son muy privadas, no se les deben entregar a los desarrolladores.. Se debe crear una version para desarrolladores que no lleve el codigo de las funciones, solamente encabezados y valores de retorno con valores predeterminados.
*/
function f213_ProcesarDia($unae13idia, $objDB, $bDebug=false, $bCargarCursos=false){
	$sError='';
	$sDebug='';
	if (true){
	$iLinea1=0;
	$iLinea2=0;
	$iLinea3=0;
	$sProcesados='-99';
	list($iDia, $iMes, $iAgno)=fecha_DividirNumero($unae13idia);
	$sOrigen=($iAgno*100)+$iMes;
	$sTabla='unad71sesion'.$sOrigen.'';
	if (!tabla_existe($sTabla, $objDB)){
		$sError='No se ha encontrado la tabla <b>'.$sTabla.'</b>';
		}
	if ($sError==''){
		//, unae13origen, unae13comunes
		$sCampos213='unae13idia, unae13idtercero, unae13id, unae13estado, unae13idrevisa, 
unae13fecharevisa, unae13dictamen, unae13origen, unae13comunes, unad13iporigen, 
unae13latgrados, unae13latdecimas, unae13longrados, unae13longdecimas, unae13proximidad, 
unae13tiposospecha';
		$unae13id=tabla_consecutivo('unae13enrevision','unae13id', '', $objDB);
		$unae13estado=0;
		$unae13idrevisa=0;
		$unae13fecharevisa=0;
		$unae13dictamen='';
		//@@ Falta sacar las semillas seguras
		$sIpsSeguras='"xxxxxxx"';
		$sSQL='SELECT unad90ip FROM unad90controlip WHERE unad90accion=90';
		$tabla=$objDB->ejecutasql($sSQL);
		while($fila=$objDB->sf($tabla)){
			$sIpsSeguras=$sIpsSeguras.',"'.$fila['unad90ip'].'"';
			}
		$sHosts='"XXX"';
		$sSQL='SELECT unad71iporigen, unad71hostname, COUNT(DISTINCT(unad71idtercero)) AS Total
FROM '.$sTabla.'
WHERE unad71fechaini='.$unae13idia.' AND unad71iporigen NOT IN ('.$sIpsSeguras.')
GROUP BY unad71iporigen, unad71hostname
HAVING COUNT(DISTINCT(unad71idtercero))>5';
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Lista de host con la misma IP '.$sSQL.'<br>';}
		$tabla=$objDB->ejecutasql($sSQL);
		while($fila=$objDB->sf($tabla)){
			$sHosts=$sHosts.',"'.$fila['unad71hostname'].'"';
			$unae13origen=$fila['unad71hostname'];
			$unae13comunes=$fila['Total'];
			$unad13iporigen=$fila['unad71iporigen'];
			//Ahora si buscar e insertar el registro.
			$sSQL='SELECT unad71idtercero 
FROM '.$sTabla.' AS TB
WHERE unad71fechaini='.$unae13idia.' AND unad71iporigen="'.$fila['unad71iporigen'].'" AND unad71hostname="'.$fila['unad71hostname'].'"
GROUP BY unad71idtercero';
			$tabla2=$objDB->ejecutasql($sSQL);
			while($fila2=$objDB->sf($tabla2)){
				if ($bDebug){
					if (!cadena_contiene($sProcesados, ', '.$fila2['unad71idtercero'].' ')){
						$iLinea1++;
						}
					}
				$sProcesados=$sProcesados.', '.$fila2['unad71idtercero'].' ';
				$bPasa=true;
				$sSQL='SELECT unae13id, unae13comunes FROM unae13enrevision WHERE unae13idia='.$unae13idia.' AND unae13idtercero='.$fila2['unad71idtercero'].'';
				$tabla3=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($tabla3)>0){
					$fila3=$objDB->sf($tabla3);
					if ($fila3['unae13comunes']<$unae13comunes){
						$sSQL='UPDATE unae13enrevision SET unae13origen="'.$unae13origen.'", unae13comunes='.$unae13comunes.', unad13iporigen="'.$unad13iporigen.'", unae13tiposospecha=1 WHERE unae13id='.$fila3['unae13id'].'';
						$result=$objDB->ejecutasql($sSQL);
						}
					}else{
					$sValores213=''.$unae13idia.', '.$fila2['unad71idtercero'].', '.$unae13id.', '.$unae13estado.', '.$unae13idrevisa.', 
'.$unae13fecharevisa.', "'.$unae13dictamen.'", "'.$unae13origen.'", '.$unae13comunes.', "'.$unad13iporigen.'", 
0, "", 0, "", 0, 
1';
					$sSQL='INSERT INTO unae13enrevision ('.$sCampos213.') VALUES ('.$sValores213.');';
					$result=$objDB->ejecutasql($sSQL);
					$unae13id++;
					}
				}
			}
		//Ahora ya tenemos la primer linea de analisis... vamos a procesar los navegadores que salten la ip....
		$sSQL='SELECT unad71hostname, COUNT(DISTINCT(unad71idtercero)) AS Total
FROM '.$sTabla.'
WHERE unad71fechaini='.$unae13idia.' AND unad71iporigen NOT IN ('.$sIpsSeguras.')
GROUP BY unad71hostname
HAVING COUNT(DISTINCT(unad71idtercero))>5';
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Lista de host '.$sSQL.'<br>';}
		$tabla=$objDB->ejecutasql($sSQL);
		while($fila=$objDB->sf($tabla)){
			$sHosts=$sHosts.',"'.$fila['unad71hostname'].'"';
			$unae13origen=$fila['unad71hostname'];
			$unae13comunes=$fila['Total'];
			//Ahora si buscar e insertar el registro.
			$sSQL='SELECT unad71iporigen, unad71idtercero 
FROM '.$sTabla.' AS TB
WHERE unad71fechaini='.$unae13idia.' AND unad71idtercero NOT IN ('.$sProcesados.') AND unad71hostname="'.$fila['unad71hostname'].'"
GROUP BY unad71idtercero';
			if ($bDebug){$sDebugHost=fecha_microtiempo().' Usuarios por host '.$sSQL.'<br>';}
			$bHostSucio=false;
			$tabla2=$objDB->ejecutasql($sSQL);
			while($fila2=$objDB->sf($tabla2)){
				if ($bDebug){
					if (!cadena_contiene($sProcesados, ', '.$fila2['unad71idtercero'].' ')){
						$iLinea2++;
						$bHostSucio=true;
						}
					}
				$bPasa=true;
				$sSQL='SELECT unae13id, unae13comunes, unae13tiposospecha FROM unae13enrevision WHERE unae13idia='.$unae13idia.' AND unae13idtercero='.$fila2['unad71idtercero'].'';
				$tabla3=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($tabla3)>0){
					$fila3=$objDB->sf($tabla3);
					if ($fila3['unae13comunes']<$unae13comunes){
						//Solo se actualiza si tiene mas entradas que en el proceso anterior, el principal es el proceso anterior.
						$sAddOrigen='';
						//, unae13tiposospecha=1
						if ($fila3['unae13tiposospecha']==3){
							$sAddOrigen=', unae13tiposospecha=2';
							}
						$sSQL='UPDATE unae13enrevision SET unae13comunes='.$unae13comunes.$sAddOrigen.' WHERE unae13id='.$fila3['unae13id'].'';
						$result=$objDB->ejecutasql($sSQL);
						}
					}else{
					$sProcesados=$sProcesados.', '.$fila2['unad71idtercero'].' ';
					$sValores213=''.$unae13idia.', '.$fila2['unad71idtercero'].', '.$unae13id.', '.$unae13estado.', '.$unae13idrevisa.', 
'.$unae13fecharevisa.', "'.$unae13dictamen.'", "'.$unae13origen.'", '.$unae13comunes.', "'.$fila2['unad71iporigen'].'", 
0, "", 0, "", 0, 
2';
					$sSQL='INSERT INTO unae13enrevision ('.$sCampos213.') VALUES ('.$sValores213.');';
					$result=$objDB->ejecutasql($sSQL);
					$unae13id++;
					}
				}
			if ($bHostSucio){
				$sDebug=$sDebug.$sDebugHost;
				}
			}
		//Ahora marcamos esas conexiones como multiconexiones
		$sSQL='UPDATE '.$sTabla.' SET unad71estado=5
WHERE unad71fechaini='.$unae13idia.' AND unad71hostname IN ('.$sHosts.') AND unad71estado<>5 AND unad71iporigen NOT IN ('.$sIpsSeguras.')';
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Marcamos las conexiones '.$sSQL.'<br>';}
		$result=$objDB->ejecutasql($sSQL);
		//Ahora vamos a procesar aquellas lecturas de geolocalizacion que sean iguales....
		$sSQL='SELECT unad71latgrados, unad71latdecimas, unad71longrados, unad71longdecimas, unad71proximidad, unad71navegador, COUNT(DISTINCT(unad71idtercero)) AS Total
FROM '.$sTabla.'
WHERE unad71fechaini='.$unae13idia.' AND unad71idtercero NOT IN ('.$sProcesados.') AND unad71iporigen NOT IN ('.$sIpsSeguras.') AND unad71proximidad>0 AND unad71proximidad<2500
GROUP BY unad71latgrados, unad71latdecimas, unad71longrados, unad71longdecimas, unad71proximidad, unad71navegador
HAVING COUNT(DISTINCT(unad71idtercero))>19';
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Lista de lecturas similares '.$sSQL.'<br>';}
		if (false){
		$tabla=$objDB->ejecutasql($sSQL);
		while($fila=$objDB->sf($tabla)){
			//$unae13origen=$fila['unad71hostname'];
			$unae13comunes=$fila['Total'];
			//Ahora si buscar e insertar el registro.
			$sSQL='SELECT unad71idtercero, (unad71iporigen) AS Ip, unad71id, unad71estado 
FROM '.$sTabla.' AS TB
WHERE unad71fechaini='.$unae13idia.' AND unad71idtercero NOT IN ('.$sProcesados.') 
AND unad71latgrados="'.$fila['unad71latgrados'].'" AND unad71latdecimas="'.$fila['unad71latdecimas'].'" 
AND unad71longrados="'.$fila['unad71longrados'].'" AND unad71longdecimas="'.$fila['unad71longdecimas'].'" 
AND unad71proximidad="'.$fila['unad71proximidad'].'" AND unad71navegador="'.$fila['unad71navegador'].'"';
			if ($bDebug){$sDebugHost=fecha_microtiempo().' Usuarios por lectura '.$sSQL.'<br>';}
			$bHostSucio=false;
			$tabla2=$objDB->ejecutasql($sSQL);
			while($fila2=$objDB->sf($tabla2)){
				if ($bDebug){
					if (!cadena_contiene($sProcesados, ', '.$fila2['unad71idtercero'].' ')){
						$iLinea3++;
						$bHostSucio=true;
						}
					}
				//Marcamos la conexion como sospechosa.
				if ($fila2['unad71estado']<5){
					$sSQL='UPDATE '.$sTabla.' SET unad71estado=6 WHERE unad71id='.$fila2['unad71id'].'';
					$result=$objDB->ejecutasql($sSQL);
					}
				$bPasa=true;
				$sSQL='SELECT unae13id, unae13comunes FROM unae13enrevision WHERE unae13idia='.$unae13idia.' AND unae13idtercero='.$fila2['unad71idtercero'].'';
				$tabla3=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($tabla3)>0){
					$fila3=$objDB->sf($tabla3);
					if ($fila3['unae13comunes']<$unae13comunes){
						//Solo se actualiza si tiene mas entradas que en el proceso anterior.
						$sSQL='UPDATE unae13enrevision SET unae13comunes='.$unae13comunes.' WHERE unae13id='.$fila3['unae13id'].'';
						$result=$objDB->ejecutasql($sSQL);
						}
					}else{
					$unae13origen='';
					//$unae13dictamen='Multiconexion en . . Prox: '.$fila['unad71proximidad'].' mts';
					$unae13dictamen='';
					$sProcesados=$sProcesados.', '.$fila2['unad71idtercero'].' ';
					$sValores213=''.$unae13idia.', '.$fila2['unad71idtercero'].', '.$unae13id.', '.$unae13estado.', '.$unae13idrevisa.', 
'.$unae13fecharevisa.', "'.$unae13dictamen.'", "'.$unae13origen.'", '.$unae13comunes.', "'.$fila2['Ip'].'",
'.$fila['unad71latgrados'].', "'.$fila['unad71latdecimas'].'", '.$fila['unad71longrados'].', "'.$fila['unad71longdecimas'].'", '.$fila['unad71proximidad'].', 
3';
					$sSQL='INSERT INTO unae13enrevision ('.$sCampos213.') VALUES ('.$sValores213.');';
					$result=$objDB->ejecutasql($sSQL);
					$unae13id++;
					}
				}
			if ($bHostSucio){
				$sDebug=$sDebug.$sDebugHost;
				}
			}
			//Termina si no se procesa.
			}
		}
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' <b>Procesados linea 1: '.$iLinea1.', Procesados linea 2: '.$iLinea2.', Procesados linea 3: </b>'.$iLinea3.'<br>';}
		//Fin del codigo de la funcion.
		}
	return array($sError, $sDebug, $iLinea1, $iLinea2, $iLinea3);
	}
?>