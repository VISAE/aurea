<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2015 - 2016 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.9.7 domingo, 29 de noviembre de 2015
--- Modelo Versión 2.15.8 miércoles, 02 de noviembre de 2016
--- 1919 Grupos de preguntas
*/
function f1919_db_Guardar($valores, $objdb, $bDebug=false){
	$icodmodulo=1919;
	$bAudita[2]=false;
	$bAudita[3]=false;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_1919='lg/lg_1919_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1919)){$mensajes_1919='lg/lg_1919_es.php';}
	require $mensajes_todas;
	require $mensajes_1919;
	$sError='';
	$sDebug='';
	$binserta=false;
	$iAccion=3;
	if(!is_array($valores)){$valores=json_decode(str_replace('\"','"',$valores),true);}
	$even19idencuesta=numeros_validar($valores[1]);
	$even19consec=numeros_validar($valores[2]);
	$even19id=numeros_validar($valores[3], true);
	$even19nombre=htmlspecialchars(trim($valores[4]));
	if ($even19nombre==''){$sError=$ERR['even19nombre'];}
	//if ($even19id==''){$sError=$ERR['even19id'];}//CONSECUTIVO
	//if ($even19consec==''){$sError=$ERR['even19consec'];}//CONSECUTIVO
	if ($even19idencuesta==''){$sError=$ERR['even19idencuesta'];}
	if ($sError==''){
		if ((int)$even19id==0){
			if ((int)$even19consec==0){
				$even19consec=tabla_consecutivo('even19encuestagrupo', 'even19consec', 'even19idencuesta='.$even19idencuesta.'', $objdb);
				if ($even19consec==-1){$sError=$objdb->serror;}
				}else{
				if (!seg_revisa_permiso($icodmodulo, 8, $objdb)){$sError=$ERR['8'];}
				}
			if ($sError==''){
				$sql='SELECT even19idencuesta FROM even19encuestagrupo WHERE even19idencuesta='.$even19idencuesta.' AND even19consec='.$even19consec.'';
				$result=$objdb->ejecutasql($sql);
				if ($objdb->nf($result)!=0){
					$sError=$ERR['existe'];
					}else{
					if (!seg_revisa_permiso($icodmodulo, 2, $objdb)){$sError=$ERR['2'];}
					}
				}
			if ($sError==''){
				$even19id=tabla_consecutivo('even19encuestagrupo', 'even19id', '', $objdb);
				if ($even19id==-1){$sError=$objdb->serror;}
				$binserta=true;
				$iAccion=2;
				}
			}else{
			if (!seg_revisa_permiso($icodmodulo, 3, $objdb)){$sError=$ERR['3'];}
			}
		}
	if ($sError==''){
		if ($binserta){
			$scampos='even19idencuesta, even19consec, even19id, even19nombre';
			$svalores=''.$even19idencuesta.', '.$even19consec.', '.$even19id.', "'.$even19nombre.'"';
			if ($APP->utf8==1){
				$sql='INSERT INTO even19encuestagrupo ('.$scampos.') VALUES ('.cadena_codificar($svalores).');';
				}else{
				$sql='INSERT INTO even19encuestagrupo ('.$scampos.') VALUES ('.$svalores.');';
				}
			$result=$objdb->ejecutasql($sql);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' {Grupos de preguntas}.<!-- '.$sql.' -->';
				}else{
				if ($bAudita[2]){
					seg_auditar($icodmodulo, $_SESSION['unad_id_tercero'], 2, $even19id, $sql, $objdb);
					}
				}
			}else{
			$scampo1919[1]='even19nombre';
			$svr1919[1]=$even19nombre;
			$inumcampos=1;
			$sWhere='even19id='.$even19id.'';
			//$sWhere='even19idencuesta='.$even19idencuesta.' AND even19consec='.$even19consec.'';
			$sql='SELECT * FROM even19encuestagrupo WHERE '.$sWhere;
			$sdatos='';
			$bpasa=false;
			$result=$objdb->ejecutasql($sql);
			if ($objdb->nf($result)>0){
				$filaorigen=$objdb->sf($result);
				for ($k=1;$k<=$inumcampos;$k++){
					if ($filaorigen[$scampo1919[$k]]!=$svr1919[$k]){
						if ($sdatos!=''){$sdatos=$sdatos.', ';}
						$sdatos=$sdatos.$scampo1919[$k].'="'.$svr1919[$k].'"';
						$bpasa=true;
						}
					}
				}
			if ($bpasa){
				if ($APP->utf8==1){
					$sql='UPDATE even19encuestagrupo SET '.cadena_codificar($sdatos).' WHERE '.$sWhere.';';
					}else{
					$sql='UPDATE even19encuestagrupo SET '.$sdatos.' WHERE '.$sWhere.';';
					}
				$result=$objdb->ejecutasql($sql);
				if ($result==false){
					$sError=$ERR['falla_guardar'].' {Grupos de preguntas}. <!-- '.$sql.' -->';
					}else{
					if ($bAudita[3]){
						seg_auditar($icodmodulo, $_SESSION['unad_id_tercero'], 3, $even19id, $sql, $objdb);
						}
					}
				}
			}
		}
	return array($sError, $iAccion, $even19id, $sDebug);
	}
function f1919_db_Eliminar($params, $objdb, $bDebug=false){
	$icodmodulo=1919;
	$bAudita[4]=false;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_1919='lg/lg_1919_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1919)){$mensajes_1919='lg/lg_1919_es.php';}
	require $mensajes_todas;
	require $mensajes_1919;
	$sError='';
	$sDebug='';
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	$even19idencuesta=numeros_validar($params[1]);
	$even19consec=numeros_validar($params[2]);
	$even19id=numeros_validar($params[3]);
	if ($sError==''){
		if (!seg_revisa_permiso($icodmodulo, 4, $objdb)){
			$sError=$ERR['4'];
			}
		}
	if ($sError==''){
		//acciones previas
		$sWhere='even19id='.$even19id.'';
		//$sWhere='even19idencuesta='.$even19idencuesta.' AND even19consec='.$even19consec.'';
		$sql='DELETE FROM even19encuestagrupo WHERE '.$sWhere.';';
		$result=$objdb->ejecutasql($sql);
		if ($result==false){
			$sError=$ERR['falla_eliminar'].' {1919 Grupos de preguntas}.<!-- '.$sql.' -->';
			}else{
			if ($bAudita[4]){
				seg_auditar($icodmodulo, $_SESSION['unad_id_tercero'], 4, $even19id, $sql, $objdb);
				}
			}
		}
	return array($sError, $sDebug);
	}
function f1919_TablaDetalleV2($params, $objdb, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_1919='lg/lg_1919_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1919)){$mensajes_1919='lg/lg_1919_es.php';}
	require $mensajes_todas;
	require $mensajes_1919;
	$sDebug='';
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	if (isset($params[0])==0){$params[0]=-1;}
	if (isset($params[101])==0){$params[101]=1;}
	if (isset($params[102])==0){$params[102]=20;}
	//if (isset($params[103])==0){$params[103]='';}
	//$params[103]=numeros_validar($params[103]);
	$params[0]=numeros_validar($params[0]);
	if ($params[0]==''){$params[0]=-1;}
	$even16id=$params[0];
	$pagina=$params[101];
	$lineastabla=$params[102];
	$babierta=false;
	$sql='SELECT even16publicada FROM even16encuesta WHERE even16id='.$even16id;
	$tabla=$objdb->ejecutasql($sql);
	if ($objdb->nf($tabla)>0){
		$fila=$objdb->sf($tabla);
		if ($fila['even16publicada']!='S'){$babierta=true;}
		}
	$sqladd='';
	//if (isset($params[103])==0){$params[103]='';}
	//if ((int)$params[103]!=-1){$sqladd=$sqladd.' AND TB.campo='.$params[103];}
	//if ($params[103]!=''){$sqladd=$sqladd.' AND TB.campo2 LIKE "%'.$params[103].'%"';}
	$sTitulos='Encuesta, Consec, Id, Nombre';
	$sql='SELECT TB.even19idencuesta, TB.even19consec, TB.even19id, TB.even19nombre 
FROM even19encuestagrupo AS TB 
WHERE TB.even19idencuesta='.$even16id.' '.$sqladd.'
';// ORDER BY TB.nombre';
	$sqllista=str_replace("'","|",$sql);
	$sqllista=str_replace('"',"|",$sqllista);
	$sErrConsulta='<input id="consulta_1919" name="consulta_1919" type="hidden" value="'.$sqllista.'"/>
<input id="titulos_1919" name="titulos_1919" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objdb->ejecutasql($sql);
	if ($tabladetalle==false){
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 1919: '.$sql.'<br>';}
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sql.' '.$objdb->serror.'"/>';
		}else{
		$registros=$objdb->nf($tabladetalle);
		if ($registros==0){
			return array(cadena_codificar($sErrConsulta.'<input id="paginaf1919" name="paginaf1919" type="hidden" value="'.$pagina.'"/><input id="lppf1919" name="lppf1919" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
			}
		if ((($registros-1)/$lineastabla)<($pagina-1)){$pagina=(int)(($registros-1)/$lineastabla)+1;}
		if ($registros>$lineastabla){
			$rbase=($pagina-1)*$lineastabla;
			$limite=' LIMIT '.$rbase.', '.$lineastabla;
			$tabladetalle=$objdb->ejecutasql($sql.$limite);
			}
		}
	$res=$sErrConsulta.'<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">
<tr class="fondoazul">
<td><b>'.$ETI['even19consec'].'</b></td>
<td><b>'.$ETI['even19nombre'].'</b></td>
<td align="right">
'.html_paginador('paginaf1919', $registros, $lineastabla, $pagina, 'paginarf1919()').'
'.html_lpp('lppf1919', $lineastabla, 'paginarf1919()').'
</td>
</tr>';
	$tlinea=1;
	while($filadet=$objdb->sf($tabladetalle)){
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
		if ($babierta){
			$sLink='<a href="javascript:cargaridf1919('."'".$filadet['even19id']."'".')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$res=$res.'<tr'.$sClass.'>
<td>'.$sPrefijo.$filadet['even19consec'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['even19nombre']).$sSufijo.'</td>
<td>'.$sLink.'</td>
</tr>';
		}
	$res=$res.'</table>';
	return array(cadena_codificar($res), $sDebug);
	}
function f1919_Clonar($even19idencuesta, $even19idencuestaPadre, $objdb){
	$sError='';
	$even19consec=tabla_consecutivo('even19encuestagrupo', 'even19consec', 'even19idencuesta='.$even19idencuesta.'', $objdb);
	if ($even19consec==-1){$sError=$objdb->serror;}
	$even19id=tabla_consecutivo('even19encuestagrupo', 'even19id', '', $objdb);
	if ($even19id==-1){$sError=$objdb->serror;}
	if ($sError==''){
		$sCampos1919='even19idencuesta, even19consec, even19id, even19nombre';
		$sValores1919='';
		$sql='SELECT * FROM even19encuestagrupo WHERE even19idencuesta='.$even19idencuestaPadre.'';
		$tabla=$objdb->ejecutasql($sql);
		while($fila=$objdb->sf($tabla)){
			if ($sValores1919!=''){$sValores1919=$sValores1919.', ';}
			$sValores1919=$sValores1919.'('.$even19idencuesta.', '.$even19consec.', '.$even19id.', "'.$fila['even19nombre'].'")';
			$even19consec++;
			$even19id++;
			}
		if ($sValores1919!=''){
			$sql='INSERT INTO even19encuestagrupo('.$sCampos1919.') VALUES '.$sValores1919.'';
			$result=$objdb->ejecutasql($sql);
			}
		}
	return $sError;
	}
// -- 1919 Grupos de preguntas XAJAX 
function f1919_Guardar($valores, $params){
	$_SESSION['u_ultimominuto']=iminutoavance();
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$sError='';
	$bDebug=false;
	$sDebug='';
	//if(!is_array($valores)){$datos=json_decode(str_replace('\"','"',$valores),true);}
	//if (isset($datos[0])==0){$datos[0]='';}
	//if ($datos[0]==''){$sError=$ERR[''];}
	if ($sError==''){
		require './app.php';
		$objdb=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objdb->dbPuerto=$APP->dbpuerto;}
		$objdb->xajax();
		list($sError, $iAccion, $even19id, $sDebugGuardar)=f1919_db_Guardar($valores, $objdb, $bDebug);
		$sDebug=$sDebug.$sDebugGuardar;
		}
	$objResponse=new xajaxResponse();
	if ($sError==''){
		list($sdetalle, $sDebugTabla)=f1919_TablaDetalleV2($params, $objdb, $bDebug);
		$sDebug=$sDebug.$sDebugTabla;
		$objResponse->assign('div_f1919detalle', 'innerHTML', $sdetalle);
		//if ($iAccion==2){
			//$objResponse->call('cargaridf1919('.$even19id.')');
			//}else{
			$objResponse->call('limpiaf1919');
			//}
		$objResponse->call("MensajeAlarmaV2('".$ETI['msg_itemguardado']."', 1)");
		}else{
		$objResponse->call("MensajeAlarmaV2('".$sError."', 0)");
		}
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f1919_Traer($params){
	$_SESSION['u_ultimominuto']=iminutoavance();
	$sError='';
	$besta=false;
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	$paso=$params[0];
	if ($paso==1){
		$even19idencuesta=numeros_validar($params[1]);
		$even19consec=numeros_validar($params[2]);
		if (($even19idencuesta!='')&&($even19consec!='')){$besta=true;}
		}else{
		$even19id=$params[103];
		if ((int)$even19id!=0){$besta=true;}
		}
	if ($besta){
		$besta=false;
		require './app.php';
		$objdb=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objdb->dbPuerto=$APP->dbpuerto;}
		$objdb->xajax();
		$sqlcondi='';
		if ($paso==1){
			$sqlcondi=$sqlcondi.'even19idencuesta='.$even19idencuesta.' AND even19consec='.$even19consec.'';
			}else{
			$sqlcondi=$sqlcondi.'even19id='.$even19id.'';
			}
		$sql='SELECT * FROM even19encuestagrupo WHERE '.$sqlcondi;
		$tabla=$objdb->ejecutasql($sql);
		if ($objdb->nf($tabla)>0){
			$fila=$objdb->sf($tabla);
			$besta=true;
			}
		}
	$objResponse=new xajaxResponse();
	if ($besta){
		$even19consec_nombre='';
		$html_even19consec=html_oculto('even19consec', $fila['even19consec'], $even19consec_nombre);
		$objResponse->assign('div_even19consec', 'innerHTML', $html_even19consec);
		$even19id_nombre='';
		$html_even19id=html_oculto('even19id', $fila['even19id'], $even19id_nombre);
		$objResponse->assign('div_even19id', 'innerHTML', $html_even19id);
		$objResponse->assign('even19nombre', 'value', $fila['even19nombre']);
		$objResponse->call("MensajeAlarmaV2('', 0)");
		$objResponse->call("verboton('belimina1919','block')");
		}else{
		if ($paso==1){
			$objResponse->assign('even19consec', 'value', $even19consec);
			}else{
			$objResponse->call('MensajeAlarmaV2("No se encontro el registro de referencia:'.$even19id.'", 0)');
			}
		}
	return $objResponse;
	}
function f1919_Eliminar($params){
	$_SESSION['u_ultimominuto']=iminutoavance();
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$sError='';
	$iTipoError=0;
	$bDebug=false;
	$sDebug='';
	//if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	$objdb=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objdb->dbPuerto=$APP->dbpuerto;}
	$objdb->xajax();
	list($sError, $sDebugElimina)=f1919_db_Eliminar($params, $objdb, $bDebug);
	$sDebug=$sDebug.$sDebugElimina;
	$objResponse=new xajaxResponse();
	if ($sError==''){
		list($sDetalle, $sDebugTabla)=f1919_TablaDetalleV2($params, $objdb, $bDebug);
		$sDebug=$sDebug.$sDebugTabla;
		$objResponse->assign('div_f1919detalle', 'innerHTML', $sDetalle);
		$objResponse->call('limpiaf1919');
		$objResponse->call('limpiaf1918');
		$sError=$ETI['msg_itemeliminado'];
		$iTipoError=1;
		}
	$objResponse->call("MensajeAlarmaV2('".$sError."', ".$iTipoError.")");
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f1919_HtmlTabla($params){
	$_SESSION['u_ultimominuto']=iminutoavance();
	$sError='';
	$bDebug=false;
	$sDebug='';
	require './app.php';
	$objdb=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objdb->dbPuerto=$APP->dbpuerto;}
	$objdb->xajax();
	list($sDetalle, $sDebugTabla)=f1919_TablaDetalleV2($params, $objdb, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f1919detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f1919_PintarLlaves($params){
	$_SESSION['u_ultimominuto']=iminutoavance();
	//if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$html_even19consec='<input id="even19consec" name="even19consec" type="text" value="" onchange="revisaf1919()" class="cuatro"/>';
	$html_even19id='<input id="even19id" name="even19id" type="hidden" value=""/>';
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_even19consec','innerHTML', $html_even19consec);
	$objResponse->assign('div_even19id','innerHTML', $html_even19id);
	return $objResponse;
	}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------
?>