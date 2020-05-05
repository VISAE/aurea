<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2019 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.23.2 lunes, 10 de junio de 2019
--- 215 Actividades ejecutadas
*/
function f215_db_Guardar($valores, $objDB, $bDebug=false){
	$iCodModulo=215;
	$bAudita[2]=false;
	$bAudita[3]=false;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_215=$APP->rutacomun.'lg/lg_215_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_215)){$mensajes_215=$APP->rutacomun.'lg/lg_215_es.php';}
	require $mensajes_todas;
	require $mensajes_215;
	$sError='';
	$sDebug='';
	$binserta=false;
	$iAccion=3;
	if(!is_array($valores)){$valores=json_decode(str_replace('\"','"',$valores),true);}
	$unae15idlogcron=numeros_validar($valores[1]);
	$unae15consec=numeros_validar($valores[2]);
	$unae15id=numeros_validar($valores[3], true);
	$unae15idaccion=numeros_validar($valores[4]);
	$unae15detalle=htmlspecialchars(trim($valores[5]));
	$unae15minuto=numeros_validar($valores[6]);
	//if ($unae15idaccion==''){$unae15idaccion=0;}
	//if ($unae15minuto==''){$unae15minuto=0;}
	$sSepara=', ';
	if ($unae15minuto==''){$sError=$ERR['unae15minuto'].$sSepara.$sError;}
	if ($unae15detalle==''){$sError=$ERR['unae15detalle'].$sSepara.$sError;}
	if ($unae15idaccion==''){$sError=$ERR['unae15idaccion'].$sSepara.$sError;}
	//if ($unae15id==''){$sError=$ERR['unae15id'].$sSepara.$sError;}//CONSECUTIVO
	//if ($unae15consec==''){$sError=$ERR['unae15consec'].$sSepara.$sError;}//CONSECUTIVO
	if ($unae15idlogcron==''){$sError=$ERR['unae15idlogcron'].$sSepara.$sError;}
	if ($sError==''){
		if ((int)$unae15id==0){
			if ((int)$unae15consec==0){
				$unae15consec=tabla_consecutivo('unae15cronregistro', 'unae15consec', 'unae15idlogcron='.$unae15idlogcron.'', $objDB);
				if ($unae15consec==-1){$sError=$objDB->serror;}
				}else{
				if (!seg_revisa_permiso($iCodModulo, 8, $objDB)){$sError=$ERR['8'];}
				}
			if ($sError==''){
				$sSQL='SELECT unae15idlogcron FROM unae15cronregistro WHERE unae15idlogcron='.$unae15idlogcron.' AND unae15consec='.$unae15consec.'';
				$result=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($result)!=0){
					$sError=$ERR['existe'];
					}else{
					if (!seg_revisa_permiso($iCodModulo, 2, $objDB)){$sError=$ERR['2'];}
					}
				}
			if ($sError==''){
				$unae15id=tabla_consecutivo('unae15cronregistro', 'unae15id', '', $objDB);
				if ($unae15id==-1){$sError=$objDB->serror;}
				$binserta=true;
				$iAccion=2;
				}
			}else{
			if (!seg_revisa_permiso($iCodModulo, 3, $objDB)){$sError=$ERR['3'];}
			}
		}
	if ($sError==''){
		if ($binserta){
			}
		}
	if ($sError==''){
		//Si el campo unae15detalle permite html quite la linea htmlspecialchars para el campo y habilite la siguiente linea:
		//$unae15detalle=str_replace('"', '\"', $unae15detalle);
		$unae15detalle=str_replace('"', '\"', $unae15detalle);
		if ($binserta){
			$scampos='unae15idlogcron, unae15consec, unae15id, unae15idaccion, unae15detalle, 
unae15minuto';
			$svalores=''.$unae15idlogcron.', '.$unae15consec.', '.$unae15id.', '.$unae15idaccion.', "'.$unae15detalle.'", 
'.$unae15minuto.'';
			if ($APP->utf8==1){
				$sSQL='INSERT INTO unae15cronregistro ('.$scampos.') VALUES ('.utf8_encode($svalores).');';
				}else{
				$sSQL='INSERT INTO unae15cronregistro ('.$scampos.') VALUES ('.$svalores.');';
				}
			$result=$objDB->ejecutasql($sSQL);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' {Actividades ejecutadas}.<!-- '.$sSQL.' -->';
				}else{
				if ($bAudita[2]){
					seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 2, $unae15id, $sSQL, $objDB);
					}
				}
			}else{
			$scampo215[1]='unae15idaccion';
			$scampo215[2]='unae15detalle';
			$scampo215[3]='unae15minuto';
			$svr215[1]=$unae15idaccion;
			$svr215[2]=$unae15detalle;
			$svr215[3]=$unae15minuto;
			$inumcampos=3;
			$sWhere='unae15id='.$unae15id.'';
			//$sWhere='unae15idlogcron='.$unae15idlogcron.' AND unae15consec='.$unae15consec.'';
			$sSQL='SELECT * FROM unae15cronregistro WHERE '.$sWhere;
			$sdatos='';
			$bpasa=false;
			$result=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($result)>0){
				$filaorigen=$objDB->sf($result);
				for ($k=1;$k<=$inumcampos;$k++){
					if ($filaorigen[$scampo215[$k]]!=$svr215[$k]){
						if ($sdatos!=''){$sdatos=$sdatos.', ';}
						$sdatos=$sdatos.$scampo215[$k].'="'.$svr215[$k].'"';
						$bpasa=true;
						}
					}
				}
			if ($bpasa){
				if ($APP->utf8==1){
					$sSQL='UPDATE unae15cronregistro SET '.utf8_encode($sdatos).' WHERE '.$sWhere.';';
					}else{
					$sSQL='UPDATE unae15cronregistro SET '.$sdatos.' WHERE '.$sWhere.';';
					}
				$result=$objDB->ejecutasql($sSQL);
				if ($result==false){
					$sError=$ERR['falla_guardar'].' {Actividades ejecutadas}. <!-- '.$sSQL.' -->';
					}else{
					if ($bAudita[3]){
						seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 3, $unae15id, $sSQL, $objDB);
						}
					}
				}
			}
		}
	return array($sError, $iAccion, $unae15id, $sDebug);
	}
function f215_db_Eliminar($aParametros, $objDB, $bDebug=false){
	$iCodModulo=215;
	$bAudita[4]=false;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_215=$APP->rutacomun.'lg/lg_215_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_215)){$mensajes_215=$APP->rutacomun.'lg/lg_215_es.php';}
	require $mensajes_todas;
	require $mensajes_215;
	$sError='';
	$sDebug='';
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$unae15idlogcron=numeros_validar($aParametros[1]);
	$unae15consec=numeros_validar($aParametros[2]);
	$unae15id=numeros_validar($aParametros[3]);
	if ($sError==''){
		if (!seg_revisa_permiso($iCodModulo, 4, $objDB)){
			$sError=$ERR['4'];
			}
		}
	if ($sError==''){
		$sSQL='SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=215';
		$tablaor=$objDB->ejecutasql($sSQL);
		while ($filaor=$objDB->sf($tablaor)){
			$sSQL='SELECT '.$filaor['campoid'].' FROM '.$filaor['tabla'].' WHERE '.$filaor['camporev'].'='.$unae15id.' LIMIT 0, 1';
			$tabla=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla)>0){
				$sError=$filaor['mensaje'];
				if ($filaor['etiqueta']!=''){
					if (isset($ERR[$filaor['etiqueta']])!=0){$sError=$ERR[$filaor['etiqueta']];}
					}
				break;
				}
			}
		}
	if ($sError==''){
		//acciones previas
		$sWhere='unae15id='.$unae15id.'';
		//$sWhere='unae15idlogcron='.$unae15idlogcron.' AND unae15consec='.$unae15consec.'';
		$sSQL='DELETE FROM unae15cronregistro WHERE '.$sWhere.';';
		$result=$objDB->ejecutasql($sSQL);
		if ($result==false){
			$sError=$ERR['falla_eliminar'].' {215 Actividades ejecutadas}.<!-- '.$sSQL.' -->';
			}else{
			if ($bAudita[4]){
				seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $unae15id, $sSQL, $objDB);
				}
			}
		}
	return array($sError, $sDebug);
	}
function f215_TablaDetalleV2($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_215=$APP->rutacomun.'lg/lg_215_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_215)){$mensajes_215=$APP->rutacomun.'lg/lg_215_es.php';}
	require $mensajes_todas;
	require $mensajes_215;
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	if (isset($aParametros[0])==0){$aParametros[0]=-1;}
	if (isset($aParametros[101])==0){$aParametros[101]=1;}
	if (isset($aParametros[102])==0){$aParametros[102]=20;}
	if (isset($aParametros[103])==0){$aParametros[103]='';}
	if (isset($aParametros[104])==0){$aParametros[104]='';}
	$iFecha=numeros_validar($aParametros[103]);
	$aParametros[0]=numeros_validar($aParametros[0]);
	if ($aParametros[0]==''){$aParametros[0]=-1;}
	$sDebug='';
	$unae14id=$aParametros[0];
	$pagina=$aParametros[101];
	$lineastabla=$aParametros[102];
	$babierta=true;
	//$sSQL='SELECT Campo FROM unae14logcron WHERE unae14id='.$unae14id;
	//$tabla=$objDB->ejecutasql($sSQL);
	//if ($objDB->nf($tabla)>0){
		//$fila=$objDB->sf($tabla);
		//if ($fila['Campo']!='S'){$babierta=true;}
		//}
	$sSQLadd='';
	$sSQLadd1='';
	$sLeyenda='';
	if (false){
		$sLeyenda='<div class="salto1px"></div>
<div class="GrupoCamposAyuda">
<b>Importante:</b> Mensaje al usuario
<div class="salto1px"></div>
</div>';
		}
	//if ((int)$aParametros[103]!=-1){$sSQLadd=$sSQLadd.' AND TB.campo='.$aParametros[103];}
	//if ($aParametros[103]!=''){$sSQLadd=$sSQLadd.' AND TB.campo2 LIKE "%'.$aParametros[103].'%"';}
	/*
	if ($aParametros[104]!=''){
		$sBase=trim(strtoupper($aParametros[104]));
		$aNoms=explode(' ', $sBase);
		for ($k=1;$k<=count($aNoms);$k++){
			$sCadena=$aNoms[$k-1];
			if ($sCadena!=''){
				$sSQLadd=$sSQLadd.' AND T6.unad11razonsocial LIKE "%'.$sCadena.'%"';
				//$sSQLadd1=$sSQLadd1.'T1.unad11razonsocial LIKE "%'.$sCadena.'%" AND ';
				}
			}
		}
	*/
	$sTitulos='Logcron, Consec, Id, Accion, Detalle, Minuto';
	$sSQL='SELECT TB.unae15idlogcron, TB.unae15consec, TB.unae15id, T4.unae16accion, TB.unae15detalle, TB.unae15minuto, TB.unae15idaccion 
FROM unae15cronregistro AS TB, unae16cronaccion AS T4 
WHERE '.$sSQLadd1.' TB.unae15idlogcron='.$unae14id.' AND TB.unae15idaccion=T4.unae16id '.$sSQLadd.'
ORDER BY TB.unae15consec';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_215" name="consulta_215" type="hidden" value="'.$sSQLlista.'"/>
<input id="titulos_215" name="titulos_215" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objDB->ejecutasql($sSQL);
	if ($tabladetalle==false){
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 215: '.$sSQL.'<br>';}
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		//$sLeyenda=$sSQL;
		}else{
		$registros=$objDB->nf($tabladetalle);
		if ($registros==0){
			return array(utf8_encode($sErrConsulta.'<input id="paginaf215" name="paginaf215" type="hidden" value="'.$pagina.'"/><input id="lppf215" name="lppf215" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
			}
		if ((($registros-1)/$lineastabla)<($pagina-1)){$pagina=(int)(($registros-1)/$lineastabla)+1;}
		if ($registros>$lineastabla){
			$rbase=($pagina-1)*$lineastabla;
			$limite=' LIMIT '.$rbase.', '.$lineastabla;
			$tabladetalle=$objDB->ejecutasql($sSQL.$limite);
			}
		}
	$res=$sErrConsulta.$sLeyenda.'<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">
<tr class="fondoazul">
<td><b>'.$ETI['unae15consec'].'</b></td>
<td><b>'.$ETI['unae15idaccion'].'</b></td>
<td><b>'.$ETI['unae15detalle'].'</b></td>
<td><b>'.$ETI['unae15minuto'].'</b></td>
<td align="right">
'.html_paginador('paginaf215', $registros, $lineastabla, $pagina, 'paginarf215()').'
'.html_lpp('lppf215', $lineastabla, 'paginarf215()').'
</td>
</tr>';
	$tlinea=1;
	while($filadet=$objDB->sf($tabladetalle)){
		$sPrefijo='';
		$sSufijo='';
		$sClass='';
		$sLink='';
		if (false){
			$sPrefijo='<b>';
			$sSufijo='</b>';
			}
		if(($tlinea%2)==0){$sClass=' class="resaltetabla"';}
		$tlinea++;
		$et_unae15consec=$sPrefijo.$filadet['unae15consec'].$sSufijo;
		$et_unae15idaccion=$sPrefijo.cadena_notildes($filadet['unae16accion']).$sSufijo;
		$et_unae15detalle=$sPrefijo.cadena_notildes($filadet['unae15detalle']).$sSufijo;
		$et_unae15minuto=$sPrefijo.html_TablaHoraMinDesdeNumero($filadet['unae15minuto']).$sSufijo;
		if ($babierta){
			//$sLink='<a href="javascript:cargaridf215('.$filadet['unae15id'].')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$res=$res.'<tr'.$sClass.'>
<td>'.$et_unae15consec.'</td>
<td>'.$et_unae15idaccion.'</td>
<td>'.$et_unae15detalle.'</td>
<td>'.$et_unae15minuto.'</td>
<td>'.$sLink.'</td>
</tr>';
		}
	$res=$res.'</table>';
	$objDB->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
	}
function f215_Clonar($unae15idlogcron, $unae15idlogcronPadre, $objDB){
	$sError='';
	$unae15consec=tabla_consecutivo('unae15cronregistro', 'unae15consec', 'unae15idlogcron='.$unae15idlogcron.'', $objDB);
	if ($unae15consec==-1){$sError=$objDB->serror;}
	$unae15id=tabla_consecutivo('unae15cronregistro', 'unae15id', '', $objDB);
	if ($unae15id==-1){$sError=$objDB->serror;}
	if ($sError==''){
		$sCampos215='unae15idlogcron, unae15consec, unae15id, unae15idaccion, unae15detalle, unae15minuto';
		$sValores215='';
		$sSQL='SELECT * FROM unae15cronregistro WHERE unae15idlogcron='.$unae15idlogcronPadre.'';
		$tabla=$objDB->ejecutasql($sSQL);
		while($fila=$objDB->sf($tabla)){
			if ($sValores215!=''){$sValores215=$sValores215.', ';}
			$sValores215=$sValores215.'('.$unae15idlogcron.', '.$unae15consec.', '.$unae15id.', '.$fila['unae15idaccion'].', "'.$fila['unae15detalle'].'", '.$fila['unae15minuto'].')';
			$unae15consec++;
			$unae15id++;
			}
		if ($sValores215!=''){
			$sSQL='INSERT INTO unae15cronregistro('.$sCampos215.') VALUES '.$sValores215.'';
			$result=$objDB->ejecutasql($sSQL);
			}
		}
	return $sError;
	}
// -- 215 Actividades ejecutadas XAJAX 
function f215_Guardar($valores, $aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$sError='';
	$bDebug=false;
	$sDebug='';
	$bHayDb=false;
	$opts=$aParametros;
	if(!is_array($opts)){$opts=json_decode(str_replace('\"','"',$opts),true);}
	if (isset($opts[99])!=0){if ($opts[99]==1){$bDebug=true;}}
	//if(!is_array($valores)){$datos=json_decode(str_replace('\"','"',$valores),true);}
	//if (isset($datos[0])==0){$datos[0]='';}
	//if ($datos[0]==''){$sError=$ERR[''];}
	if ($sError==''){
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		$bHayDb=true;
		list($sError, $iAccion, $unae15id, $sDebugGuardar)=f215_db_Guardar($valores, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugGuardar;
		}
	$objResponse=new xajaxResponse();
	if ($sError==''){
		list($sdetalle, $sDebugTabla)=f215_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugTabla;
		$objResponse->assign('div_f215detalle', 'innerHTML', $sdetalle);
		//if ($iAccion==2){
			//$objResponse->call('cargaridf215('.$unae15id.')');
			//}else{
			$objResponse->call('limpiaf215');
			//}
		$objResponse->call("MensajeAlarmaV2('".$ETI['msg_itemguardado']."', 1)");
		}else{
		$objResponse->call("MensajeAlarmaV2('".$sError."', 0)");
		}
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	if ($bHayDb){
		$objDB->CerrarConexion();
		}
	return $objResponse;
	}
function f215_Traer($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$sError='';
	$bHayDb=false;
	$besta=false;
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$paso=$aParametros[0];
	if ($paso==1){
		$unae15idlogcron=numeros_validar($aParametros[1]);
		$unae15consec=numeros_validar($aParametros[2]);
		if (($unae15idlogcron!='')&&($unae15consec!='')){$besta=true;}
		}else{
		$unae15id=$aParametros[103];
		if ((int)$unae15id!=0){$besta=true;}
		}
	if ($besta){
		$besta=false;
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		$bHayDb=true;
		$sSQLcondi='';
		if ($paso==1){
			$sSQLcondi=$sSQLcondi.'unae15idlogcron='.$unae15idlogcron.' AND unae15consec='.$unae15consec.'';
			}else{
			$sSQLcondi=$sSQLcondi.'unae15id='.$unae15id.'';
			}
		$sSQL='SELECT * FROM unae15cronregistro WHERE '.$sSQLcondi;
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$fila=$objDB->sf($tabla);
			$besta=true;
			}
		}
	$objResponse=new xajaxResponse();
	if ($besta){
		if (isset($APP->piel)==0){$APP->piel=1;}
		$iPiel=$APP->piel;
		$unae15consec_nombre='';
		$html_unae15consec=html_oculto('unae15consec', $fila['unae15consec'], $unae15consec_nombre);
		$objResponse->assign('div_unae15consec', 'innerHTML', $html_unae15consec);
		$unae15id_nombre='';
		$html_unae15id=html_oculto('unae15id', $fila['unae15id'], $unae15id_nombre);
		$objResponse->assign('div_unae15id', 'innerHTML', $html_unae15id);
		$objResponse->assign('unae15idaccion', 'value', $fila['unae15idaccion']);
		$objResponse->assign('unae15detalle', 'value', $fila['unae15detalle']);
		$objResponse->assign('unae15minuto', 'value', $fila['unae15minuto']);
		$objResponse->call("MensajeAlarmaV2('', 0)");
		$objResponse->call("verboton('belimina215','block')");
		}else{
		if ($paso==1){
			$objResponse->assign('unae15consec', 'value', $unae15consec);
			}else{
			$objResponse->call('MensajeAlarmaV2("No se encontro el registro de referencia:'.$unae15id.'", 0)');
			}
		}
	if ($bHayDb){
		$objDB->CerrarConexion();
		}
	return $objResponse;
	}
function f215_Eliminar($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$sError='';
	$iTipoError=0;
	$bDebug=false;
	$sDebug='';
	$opts=$aParametros;
	if(!is_array($opts)){$opts=json_decode(str_replace('\"','"',$opts),true);}
	if (isset($opts[99])!=0){if ($opts[99]==1){$bDebug=true;}}
	//if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	list($sError, $sDebugElimina)=f215_db_Eliminar($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugElimina;
	$objResponse=new xajaxResponse();
	if ($sError==''){
		list($sDetalle, $sDebugTabla)=f215_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugTabla;
		$objResponse->assign('div_f215detalle', 'innerHTML', $sDetalle);
		$objResponse->call('limpiaf215');
		$sError=$ETI['msg_itemeliminado'];
		$iTipoError=1;
		}
	$objResponse->call("MensajeAlarmaV2('".$sError."', ".$iTipoError.")");
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	$objDB->CerrarConexion();
	return $objResponse;
	}
function f215_HtmlTabla($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	$sError='';
	$bDebug=false;
	$sDebug='';
	$opts=$aParametros;
	if(!is_array($opts)){$opts=json_decode(str_replace('\"','"',$opts),true);}
	if (isset($opts[99])!=0){if ($opts[99]==1){$bDebug=true;}}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	list($sDetalle, $sDebugTabla)=f215_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f215detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f215_PintarLlaves($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	//if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	if (isset($APP->piel)==0){$APP->piel=1;}
	$iPiel=$APP->piel;
	$html_unae15consec='<input id="unae15consec" name="unae15consec" type="text" value="" onchange="revisaf215()" class="cuatro"/>';
	$html_unae15id='<input id="unae15id" name="unae15id" type="hidden" value=""/>';
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_unae15consec','innerHTML', $html_unae15consec);
	$objResponse->assign('div_unae15id','innerHTML', $html_unae15id);
	return $objResponse;
	}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------
function f215_IniciarCron($objDB){
	$unae14fecha=fecha_DiaMod();
	$unae14consec=tabla_consecutivo('unae14logcron', 'unae14consec', 'unae14fecha='.$unae14fecha.'', $objDB);
	$unae14id=tabla_consecutivo('unae14logcron','unae14id', '', $objDB);
	$unae14minuto=fecha_MinutoMod();
	$sCampos214='unae14fecha, unae14consec, unae14id, unae14minuto, unae14minutofin';
	$sValores214=''.$unae14fecha.', '.$unae14consec.', '.$unae14id.', '.$unae14minuto.', '.$unae14minuto.'';
	$sSQL='INSERT INTO unae14logcron ('.$sCampos214.') VALUES ('.$sValores214.');';
	$result=$objDB->ejecutasql($sSQL);
	return $unae14id;
	}
function f215_Registrar($unae15idlogcron, $unae15idaccion, $sDetalle, $objDB){
	$unae15consec=tabla_consecutivo('unae15cronregistro', 'unae15consec', 'unae15idlogcron='.$unae15idlogcron.'', $objDB);
	$unae15id=tabla_consecutivo('unae15cronregistro', 'unae15id', '', $objDB);
	$unae15detalle=str_replace('"', '\"', $sDetalle);
	$unae15minuto=fecha_MinutoMod();
	$scampos='unae15idlogcron, unae15consec, unae15id, unae15idaccion, unae15detalle, 
unae15minuto';
	$svalores=''.$unae15idlogcron.', '.$unae15consec.', '.$unae15id.', '.$unae15idaccion.', "'.$unae15detalle.'", 
'.$unae15minuto.'';
	$sSQL='INSERT INTO unae15cronregistro ('.$scampos.') VALUES ('.$svalores.');';
	$result=$objDB->ejecutasql($sSQL);
	$sSQL='UPDATE unae14logcron SET unae14minutofin='.$unae15minuto.' WHERE unae14id='.$unae15idlogcron.'';
	$result=$objDB->ejecutasql($sSQL);
	}
?>