<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2018 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.22.6c jueves, 6 de diciembre de 2018
*/
function f1400_EnviarMensaje($idTercero, $idPeraca, $idHilo, $sCuerpo, $objDB, $bDebug=false){
	$sError='';
	$sDebug='';
	if ($sCuerpo==''){
		$sError='No se ha enviado una mensaje a la conversaci&oacute;n';
		}
	if ($sError==''){
		if ((int)$idHilo==0){
			$sError='No se ha podido establecer la informaci&oacute;n de la conversaci&oacute;n';
			}
		}
	if ($sError==''){
		$maid01fecha=fecha_DiaMod();
		$maid01minuto=fecha_MinutoMod();
		$maid01cuerpo=addslashes($sCuerpo);
		$maid01cuerpo=str_replace('"', '\"', $maid01cuerpo);
		$maid01consec=tabla_consecutivo('maid01mensaje_'.$idPeraca.'','maid01consec', '', $objDB);
		$maid01id=tabla_consecutivo('maid01mensaje_'.$idPeraca.'','maid01id', '', $objDB);
		$sCampos1401='maid01idhilo, maid01consec, maid01id, maid01idenvia, maid01idperaca, maid01estado, maid01cuerpo, maid01fecha, maid01minuto';
		$sValores1401=''.$idHilo.', '.$maid01consec.', '.$maid01id.', '.$idTercero.', '.$idPeraca.', 0, "'.$maid01cuerpo.'", '.$maid01fecha.', '.$maid01minuto.'';
		$sSQL='INSERT INTO maid01mensaje_'.$idPeraca.' ('.$sCampos1401.') VALUES ('.$sValores1401.');';
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Insertando mensaje: '.$sSQL.'<br>';}
		$result=$objDB->ejecutasql($sSQL);
		if ($result==false){
			$sError='Error inesperado al intentar enviar el mensaje, si el problema persiste comuniquese con el administrador del sistema.';
			}else{
			//Avisar que el mensaje se inserto.
			$iDiaHilo=0;
			$iMinHilo=0;
			$sData5='';
			$iDestinos=0;
			$aDestinos=array();
			$sSQL='SELECT maid03idproceso, maid03idadmin, maid03iddestino, maid03fechaultmsg, maid03minultmsg FROM maid03hilo_'.$idPeraca.' WHERE maid03id='.$idHilo.'';
			if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consultando el hilo: '.$sSQL.'<br>';}
			$tabla3=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla3)>0){
				$fila3=$objDB->sf($tabla3);
				$iDiaHilo=$fila3['maid03fechaultmsg'];
				$iMinHilo=$fila3['maid03minultmsg'];
				if ($fila3['maid03idproceso']==0){
					$iDestinos=2;
					$aDestinos[1]['id']=$fila3['maid03idadmin'];
					$aDestinos[1]['rol']=0;
					$aDestinos[2]['id']=$fila3['maid03iddestino'];
					$aDestinos[2]['rol']=0;
					}else{
					$sSQL='SELECT maid04idtercero, maid04idrol FROM maid04participa_'.$idPeraca.' WHERE maid04idhilo='.$idHilo.' AND maid04idestado=1';
					$tabla=$objDB->ejecutasql($sSQL);
					while($fila=$objDB->sf($tabla)){
						$iDestinos++;
						$aDestinos[$iDestinos]['id']=$fila['maid04idtercero'];
						$aDestinos[$iDestinos]['rol']=$fila['maid04idrol'];
						}
					}
				}
			$sCampos05='INSERT INTO maid05estadomsg_'.$idPeraca.' (maid05idhilo, maid05idtercero, maid05idmensaje, maid05id, maid05leido, maid05fechaleido, maid05minleido) VALUES ';
			$maid05id=tabla_consecutivo('maid05estadomsg_'.$idPeraca.'', 'maid05id', '', $objDB);
			for($k=1;$k<=$iDestinos;$k++){
				if ($sData5!=''){$sData5=$sData5.', ';}
				$iLeido=0;
				$maid05fechaleido=0;
				$maid05minleido=0;
				if ($aDestinos[$k]['id']==$idTercero){
					$iLeido=1;
					$maid05fechaleido=$maid01fecha;
					$maid05minleido=$maid01minuto;
					}
				$sData5=$sData5.'('.$idHilo.', '.$aDestinos[$k]['id'].', '.$maid01id.', '.$maid05id.', '.$iLeido.', '.$maid05fechaleido.', '.$maid05minleido.')';
				$maid05id++;
				}
			if ($sData5!=''){
				$sSQL=$sCampos05.$sData5;
				if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Insertando participantes.: '.$sSQL.'<br>';}
				$result=$objDB->ejecutasql($sSQL);
				}
			//Termina de avisar a quienes les debe llegar el mensaje, ahora actualizar el hilo para decirle que tiene un numevo mensaje.
			$bActualiza=true;
			if ($iDiaHilo==$maid01fecha){
				if ($iMinHilo==$maid01minuto){$bActualiza=false;}
				}
			if ($bActualiza){
				$sSQL='UPDATE maid03hilo_'.$idPeraca.' SET maid03fechaultmsg='.$maid01fecha.', maid03minultmsg='.$maid01minuto.' WHERE maid03id='.$idHilo.'';
				$result=$objDB->ejecutasql($sSQL);
				}
			//Termina de actualizar el hilo.
			}
		}
	return array($sError, $sDebug);
	}
function f1400_HtmlHilos($idTercero, $idPeraca, $idHilo, $objDB, $bDebug=false){
	$sRes='';
	$sDebug='';
	if ((int)$idPeraca!=0){
		$sSQL='SELECT maid03id, maid03asunto, maid03asunto2, maid03idadmin, maid03iddestino 
FROM maid03hilo_'.$idPeraca.' AS TB 
WHERE ((maid03idadmin='.$idTercero.') OR (maid03iddestino='.$idTercero.'))
ORDER BY maid03fechaultmsg DESC, maid03minultmsg DESC';
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta Hilos: '.$sSQL.'<br>';}
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$sRes='<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">';
			}
		while($fila=$objDB->sf($tabla)){
			$bAsunto=true;
			if ($fila['maid03idadmin']==$idTercero){
				if ($fila['maid03iddestino']!=0){$bAsunto=false;}
				}
			if ($bAsunto){
				$sEtiqueta=cadena_notildes($fila['maid03asunto']);
				}else{
				$sEtiqueta=cadena_notildes($fila['maid03asunto2']);
				}
			list($iNuevos, $iLeidos, $sDebugM)=f1400_MensajesHilo($idPeraca, $fila['maid03id'], $idTercero, $objDB, $bDebug);
			$sMensajes=$iLeidos;
			if ($iNuevos>0){$sMensajes=$iLeidos.' ['.$iNuevos.']';}
			$sRes=$sRes.'<tr><td>'.$sEtiqueta.'</td><td align="right"><a href="javascript:selhilo('.$fila['maid03id'].');">'.$sMensajes.'</a></td></tr>';
			}
		if ($sRes==''){
			$sRes='No registra conversaciones';
			}else{
			$sRes=$sRes.'</table>';
			}
		}else{
		$sRes='[No se ha seleccionado un periodo]';
		}
	return array($sRes, $sDebug);
	}
function f1400_HtmlPeraca($idTercero, $objDB, $bDebug=false){
	$sRes='';
	$sDebug='';
	//Saber en que peracas esta la persona.
	$sIniciaConversa='<a href="javascript:iniciarhilo();"><b>Iniciar conversaci&oacute;n</b></a>';
	$sSQL='SELECT TB.maid06idperaca, T1.exte02nombre FROM maid06terceroperaca AS TB, exte02per_aca AS T1 WHERE TB.maid06idtercero='.$idTercero.' AND TB.maid06idperaca=T1.exte02id ORDER BY TB.maid06idperaca DESC';
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$sRes='<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">
<tr><td>'.$sIniciaConversa.'</td><td>Nuevos</td></tr>';
		}
	while($fila=$objDB->sf($tabla)){
		list($iNuevos, $iLeidos, $sDebugM)=f1400_MensajesPeraca($fila['maid06idperaca'], $idTercero, $objDB, $bDebug);
		$sRes=$sRes.'<tr><td>'.cadena_notildes($fila['exte02nombre']).'</td><td align="right"><a href="javascript:selperaca('.$fila['maid06idperaca'].');">'.$iNuevos.'</a></td></tr>';
		$sDebug=$sDebug.$sDebugM;
		}
	if ($sRes==''){
		$sRes='No registra conversaciones ('.$sIniciaConversa.')';
		}else{
		$sRes=$sRes.'</table>';
		}
	return array($sRes, $sDebug);
	}
function f1400_HtmlVerHilo($idTercero, $idPeraca, $idHilo, $idPagina, $objDB, $bDebug=false, $sContenido=''){
	$sRes='';
	$sDebug='';
	if ((int)$idHilo!=0){
		require './app.php';
		$mensajes_1401='lg/lg_1401_'.$_SESSION['unad_idioma'].'.php';
		if (!file_exists($mensajes_1401)){$mensajes_1401='lg/lg_1401_es.php';}
		require $mensajes_1401;
		$bHiloPropio=false;
		if ($idTercero==$_SESSION['unad_id_tercero']){$bHiloPropio=true;}
		//El historico...
		$iHoy=fecha_DiaMod();
		$iMinuto=fecha_MinutoMod();
		$sSQL='SELECT TB.maid01idenvia, TB.maid01cuerpo, TB.maid01fecha, TB.maid01minuto, T11.unad11razonsocial, T5.maid05leido, T5.maid05id 
FROM maid05estadomsg_'.$idPeraca.' AS T5, maid01mensaje_'.$idPeraca.' AS TB, unad11terceros AS T11 
WHERE T5.maid05idhilo='.$idHilo.' AND maid05idtercero='.$idTercero.' AND T5.maid05leido IN (0,1) AND T5.maid05idmensaje=TB.maid01id AND TB.maid01estado=0 AND TB.maid01idenvia=T11.unad11id
ORDER BY T5.maid05idmensaje DESC
LIMIT '.$idPagina.',20';
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta Hilos: '.$sSQL.'<br>';}
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$sRes='<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">';
			}
		while($fila=$objDB->sf($tabla)){
			if ($bHiloPropio){
				if ($fila['maid05leido']==0){
					//Marcarlo como leido.
					$sSQL='UPDATE maid05estadomsg_'.$idPeraca.' SET maid05leido=1, maid05fechaleido='.$iHoy.', maid05minleido='.$iMinuto.' WHERE maid05id='.$fila['maid05id'].'';
					$result=$objDB->ejecutasql($sSQL);
					}
				}
			$sEnvia='Yo';
			if ($fila['maid01idenvia']!=$idTercero){
				$sEnvia=cadena_notildes($fila['unad11razonsocial']);
				}
			$sFecha=html_TablaHoraMinDesdeNumero($fila['maid01minuto']);
			if ($fila['maid01fecha']!=$iHoy){
				$sFecha=fecha_desdenumero($fila['maid01fecha']).' '.$sFecha;
				}
			$sRes=$sRes.'<tr><td>'.$sEnvia.'</td><td align="right">'.$sFecha.'</td></tr>
<tr><td colspan="2">'.cadena_notildes($fila['maid01cuerpo']).'</td></tr>';
			}
		if ($sRes==''){
			}else{
			$sRes=$sRes.'</table>';
			}
		//La opcion de iniciar un nuevo mensaje.
		$bEntra=false;
		if ($idPagina==0){
			$bEntra=$bHiloPropio;
			}
		if ($bEntra){
			$sRes='<label class="txtAreaS">'.$ETI['maid01cuerpo'].'
<textarea id="maid01cuerpo" name="maid01cuerpo" placeholder="">'.$sContenido.'</textarea>
</label>
<label class="Label130">&nbsp;</label>
<label class="Label130">
<input id="btNuevoMensaje" name="btNuevoMensaje" type="button" value="Enviar" class="BotonAzul" onclick="enviarmensaje();" title="Enviar Mensaje"/>
</label>
'.$sRes;
			}
		}
	return array($sRes, $sDebug);
	}
function f1400_IniciarHilo($idProceso, $idPeraca, $idCurso, $idGrupo, $idTerceroOrigen, $idTerceroDestino, $objDB, $bDebug){
	$id03=0;
	$sError='';
	$sDebug='';
	if ($idPeraca==''){
		$sError='No da definido el periodo para la conversaci&oacute;n';
		}
	if ($sError==''){
		if ($idCurso==''){$idCurso=0;}
		if ($idGrupo==''){$idGrupo=0;}
		//Puede que el hilo ya exista.
		$sSQL='SELECT maid03id FROM maid03hilo_'.$idPeraca.' WHERE ((maid03idadmin='.$idTerceroOrigen.' AND maid03iddestino='.$idTerceroDestino.') OR (maid03idadmin='.$idTerceroDestino.' AND maid03iddestino='.$idTerceroOrigen.')) AND maid03idproceso='.$idProceso.'';
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Buscando hilos previos '.$sSQL.'<br>';}
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$fila=$objDB->sf($tabla);
			$id03=$fila['maid03id'];
			}
		}
	if ($sError==''){
		if ($id03==0){
			//Crear el hilo
			$sCampos1403='maid03consec, maid03id, maid03idperaca, maid03idproceso, maid03fecha, 
maid03minuto, maid03idcurso, maid03idgrupo, maid03idadmin, maid03permiteretiro, 
maid03asunto, maid03fechaultmsg, maid03minultmsg, maid03asunto2, maid03iddestino';
			$maid03fecha=fecha_DiaMod();
			$maid03minuto=fecha_MinutoMod();
			$maid03asunto='['.$idTerceroOrigen.']';
			$maid03asunto2='['.$idTerceroDestino.']';
			$sSQL='SELECT unad11id, unad11razonsocial FROM unad11terceros WHERE unad11id IN ('.$idTerceroOrigen.', '.$idTerceroDestino.')';
			$tabla=$objDB->ejecutasql($sSQL);
			while ($fila=$objDB->sf($tabla)){
				if ($fila['unad11id']==$idTerceroOrigen){$maid03asunto=$fila['unad11razonsocial'];}
				if ($fila['unad11id']==$idTerceroDestino){$maid03asunto2=$fila['unad11razonsocial'];}
				}
			$maid03consec=tabla_consecutivo('maid03hilo_'.$idPeraca.'', 'maid03consec', '', $objDB);
			$id03=tabla_consecutivo('maid03hilo_'.$idPeraca.'','maid03id', '', $objDB);
			$sValores1403=''.$maid03consec.', '.$id03.', '.$idPeraca.', '.$idProceso.', '.$maid03fecha.', 
'.$maid03minuto.', '.$idCurso.', '.$idGrupo.', '.$idTerceroOrigen.', "N", 
"'.$maid03asunto.'", '.$maid03fecha.', '.$maid03minuto.', "'.$maid03asunto2.'", '.$idTerceroDestino.'';
			$sSQL='INSERT INTO maid03hilo_'.$idPeraca.' ('.$sCampos1403.') VALUES ('.$sValores1403.');';
			$result=$objDB->ejecutasql($sSQL);
			if ($result==false){
				$sError='Error inesperado al intentar iniciar la conversaci&oacute;n, si el problema persiste por favor comuniquese con el administrador del sistema.';
				if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta fallida '.$sSQL.'<br>';}
				$id03=0;
				}else{
				//Asegurarse que los dos esten registrados en la tabla 6.
				f1400_Registrar($idPeraca, $idTerceroOrigen, $objDB, $bDebug);
				f1400_Registrar($idPeraca, $idTerceroDestino, $objDB, $bDebug);
				}
			}
		}
	return array($id03, $sError, $sDebug);
	}
function f1400_MensajesHilo($idPeraca, $idHilo, $idTercero, $objDB, $bDebug=false){
	$iNuevos=0;
	$iLeidos=0;
	$sDebug='';
	$sSQL='SELECT maid05leido, COUNT(maid05id) AS Total FROM maid05estadomsg_'.$idPeraca.' WHERE maid05idhilo='.$idHilo.' AND maid05idtercero='.$idTercero.' GROUP BY maid05leido';
	$tabla=$objDB->ejecutasql($sSQL);
	while($fila=$objDB->sf($tabla)){
		if ($fila['maid05leido']==0){$iNuevos=$fila['Total'];}
		if ($fila['maid05leido']==1){$iLeidos=$fila['Total'];}
		}
	return array($iNuevos, $iLeidos, $sDebug);
	}
function f1400_MensajesPeraca($idPeraca, $idTercero, $objDB, $bDebug=false){
	$iNuevos=0;
	$iLeidos=0;
	$sDebug='';
	$sSQL='SELECT maid05leido, COUNT(maid05id) AS Total FROM maid05estadomsg_'.$idPeraca.' WHERE maid05idtercero='.$idTercero.' GROUP BY maid05leido';
	$tabla=$objDB->ejecutasql($sSQL);
	while($fila=$objDB->sf($tabla)){
		if ($fila['maid05leido']==0){$iNuevos=$fila['Total'];}
		if ($fila['maid05leido']==1){$iLeidos=$fila['Total'];}
		}
	return array($iNuevos, $iLeidos, $sDebug);
	}
function f1400_MensajesPendientes($idTercero, $objDB, $bDebug=false){
	$iRes=0;
	$sDebug='';
	//Saber en que peracas esta la persona.
	$sSQL='SELECT maid06idperaca FROM maid06terceroperaca WHERE maid06idtercero='.$idTercero.'';
	$tabla=$objDB->ejecutasql($sSQL);
	while($fila=$objDB->sf($tabla)){
		list($iNuevos, $iLeidos, $sDebugM)=f1400_MensajesPeraca($fila['maid06idperaca'], $idTercero, $objDB, $bDebug);
		$iRes=$iRes+$iNuevos;
		$sDebug=$sDebug.$sDebugM;
		}
	return array($iRes, $sDebug);
	}
function f1400_Registrar($idPeraca, $idTercero, $objDB, $bDebug=false){
	$sError='';
	$sDebug='';
	$sSQL='SELECT maid06id FROM maid06terceroperaca WHERE maid06idtercero='.$idTercero.' AND maid06idperaca='.$idPeraca.'';
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)==0){
		$id06=tabla_consecutivo('maid06terceroperaca','maid06id', '', $objDB);
		$sSQL='INSERT INTO maid06terceroperaca(maid06idtercero, maid06idperaca, maid06id, maid06numhilos) VALUES ('.$idTercero.', '.$idPeraca.', '.$id06.', 1)';
		$result=$objDB->ejecutasql($sSQL);
		}
	return array($sError, $sDebug);
	}
function f1400_VerificarEstructura($idPeraca, $objDB, $bDebug=false){
	$sError='';
	$sDebug='';
	$sTabla='maid03hilo_'.$idPeraca;
	$bIniciarContenedor=!$objDB->bexistetabla($sTabla);
	if ($bIniciarContenedor){
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Creando la tabla '.$sTabla.'<br>';}
		$sSQL="CREATE TABLE ".$sTabla."(maid03consec int NOT NULL, maid03id int NULL DEFAULT 0, maid03idperaca int NULL DEFAULT 0, maid03idproceso int NULL DEFAULT 0, maid03fecha int NULL DEFAULT 0, maid03minuto int NULL DEFAULT 0, maid03idcurso int NULL DEFAULT 0, maid03idgrupo int NULL DEFAULT 0, maid03idadmin int NULL DEFAULT 0, maid03permiteretiro varchar(1) NULL, maid03asunto varchar(250) NULL, maid03fechaultmsg int NULL DEFAULT 0, maid03minultmsg int NULL DEFAULT 0, maid03asunto2 varchar(250) NULL, maid03iddestino int NULL DEFAULT 0)";
		$result=$objDB->ejecutasql($sSQL);
		if ($result==false){
			$sError='No fue posible iniciar los contenedores de mensajes, por favor comuniquese con el administrador del sistema.';
			if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Error intentando crear la tabla de hilos '.$objDB->serror.'<br>';}
			$bIniciarContenedor=false;
			}
		}
	if ($bIniciarContenedor){
		$sSQL="ALTER TABLE ".$sTabla." ADD PRIMARY KEY(maid03id)";
		$result=$objDB->ejecutasql($sSQL);
		$sSQL="ALTER TABLE ".$sTabla." ADD UNIQUE INDEX maid03hilo_id(maid03consec)";
		$result=$objDB->ejecutasql($sSQL);
		$sSQL="ALTER TABLE ".$sTabla." ADD INDEX maid03hilo_admin(maid03idadmin)";
		$result=$objDB->ejecutasql($sSQL);
		$sSQL="ALTER TABLE ".$sTabla." ADD INDEX maid03hilo_destino(maid03iddestino)";
		$result=$objDB->ejecutasql($sSQL);

		$sTabla='maid04participa_'.$idPeraca;
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Creando la tabla '.$sTabla.'<br>';}
		$sSQL="CREATE TABLE ".$sTabla." (maid04idhilo int NOT NULL, maid04idtercero int NOT NULL, maid04id int NULL DEFAULT 0, maid04idrol int NULL DEFAULT 0, maid04fechaing int NULL DEFAULT 0, maid04mining int NULL DEFAULT 0, maid04idestado int NULL DEFAULT 0)";
		$result=$objDB->ejecutasql($sSQL);
		$sSQL="ALTER TABLE ".$sTabla." ADD PRIMARY KEY(maid04id)";
		$result=$objDB->ejecutasql($sSQL);
		$sSQL="ALTER TABLE ".$sTabla." ADD UNIQUE INDEX maid04participa_id(maid04idhilo, maid04idtercero)";
		$result=$objDB->ejecutasql($sSQL);
		$sSQL="ALTER TABLE ".$sTabla." ADD INDEX maid04participa_padre(maid04idhilo)";
		$result=$objDB->ejecutasql($sSQL);

		$sTabla='maid01mensaje_'.$idPeraca;
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Creando la tabla '.$sTabla.'<br>';}
		$sSQL="CREATE TABLE ".$sTabla." (maid01idhilo int NOT NULL, maid01consec int NULL DEFAULT 0, maid01id int NULL DEFAULT 0, maid01idenvia int NULL DEFAULT 0, maid01idperaca int NULL DEFAULT 0, maid01estado int NULL DEFAULT 0, maid01cuerpo Text NULL, maid01fecha int NULL DEFAULT 0, maid01minuto int NULL DEFAULT 0)";
		$result=$objDB->ejecutasql($sSQL);
		$sSQL="ALTER TABLE ".$sTabla." ADD PRIMARY KEY(maid01id)";
		$result=$objDB->ejecutasql($sSQL);
		$sSQL="ALTER TABLE ".$sTabla." ADD UNIQUE INDEX maid01mensaje_id(maid01idhilo, maid01consec)";
		$result=$objDB->ejecutasql($sSQL);
		$sSQL="ALTER TABLE ".$sTabla." ADD INDEX maid01mensaje_padre(maid01idhilo)";
		$result=$objDB->ejecutasql($sSQL);

		$sTabla='maid02anexo_'.$idPeraca;
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Creando la tabla '.$sTabla.'<br>';}
		$sSQL="CREATE TABLE ".$sTabla." (maid02idmensaje int NOT NULL, maid02consec int NOT NULL, maid02id int NULL DEFAULT 0, maid02origen int NULL DEFAULT 0, maid02idanexo int NULL DEFAULT 0, maid02detalle varchar(250) NULL)";
		$result=$objDB->ejecutasql($sSQL);
		$sSQL="ALTER TABLE ".$sTabla." ADD PRIMARY KEY(maid02id)";
		$result=$objDB->ejecutasql($sSQL);
		$sSQL="ALTER TABLE ".$sTabla." ADD UNIQUE INDEX maid02anexo_id(maid02idmensaje, maid02consec)";
		$result=$objDB->ejecutasql($sSQL);
		$sSQL="ALTER TABLE ".$sTabla." ADD INDEX maid02anexo_padre(maid02idmensaje)";
		$result=$objDB->ejecutasql($sSQL);

		$sTabla='maid05estadomsg_'.$idPeraca;
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Creando la tabla '.$sTabla.'<br>';}
		$sSQL="CREATE TABLE ".$sTabla." (maid05idhilo int NOT NULL, maid05idtercero int NOT NULL, maid05idmensaje int NOT NULL, maid05id int NULL DEFAULT 0, maid05leido int NULL DEFAULT 0, maid05fechaleido int NULL DEFAULT 0, maid05minleido int NULL DEFAULT 0, maid05notifica1 int NULL DEFAULT 0, maid05notifica7 int NULL DEFAULT 0)";
		$result=$objDB->ejecutasql($sSQL);
		$sSQL="ALTER TABLE ".$sTabla." ADD PRIMARY KEY(maid05id)";
		$result=$objDB->ejecutasql($sSQL);
		$sSQL="ALTER TABLE ".$sTabla." ADD UNIQUE INDEX maid05estadomsg_id(maid05idhilo, maid05idtercero, maid05idmensaje)";
		$result=$objDB->ejecutasql($sSQL);
		$sSQL="ALTER TABLE ".$sTabla." ADD INDEX maid05estadomsg_padre(maid05idhilo)";
		$result=$objDB->ejecutasql($sSQL);
		}
	return array($sError, $sDebug);
	}
?>