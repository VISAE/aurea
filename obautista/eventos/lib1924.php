<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2016 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.12.5 lunes, 14 de marzo de 2016
--- Modelo Versión 2.15.8 miércoles, 02 de noviembre de 2016
--- 1924 Periodos que aplican
*/
function html_combo_even24idperaca($objdb, $valor){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$res=html_combo('even24idperaca', 'exte02id', 'CONCAT(exte02nombre, " {", exte02id, "}")', 'exte02per_aca', '', 'exte02id DESC', $valor, $objdb, 'revisaf1924()', true, '{'.$ETI['msg_seleccione'].'}', '');
	return utf8_encode($res);
	}
function f1924_db_Guardar($valores, $objdb, $bDebug=false){
	$icodmodulo=1924;
	$bAudita[2]=false;
	$bAudita[3]=false;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_1924='lg/lg_1924_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1924)){$mensajes_1924='lg/lg_1924_es.php';}
	require $mensajes_todas;
	require $mensajes_1924;
	$sError='';
	$sDebug='';
	$binserta=false;
	$iAccion=3;
	if(!is_array($valores)){$valores=json_decode(str_replace('\"','"',$valores),true);}
	$even24idencuesta=numeros_validar($valores[1]);
	$even24idperaca=numeros_validar($valores[2]);
	$even24id=numeros_validar($valores[3], true);
	$even24fechainicial=$valores[4];
	$even24fechafinal=$valores[5];
	if (!fecha_esvalida($even24fechafinal)){
		//$even24fechafinal='00/00/0000';
		$sError=$ERR['even24fechafinal'];
		}
	if (!fecha_esvalida($even24fechainicial)){
		//$even24fechainicial='00/00/0000';
		$sError=$ERR['even24fechainicial'];
		}
	//if ($even24id==''){$sError=$ERR['even24id'];}//CONSECUTIVO
	if ($even24idperaca==''){$sError=$ERR['even24idperaca'];}
	if ($even24idencuesta==''){$sError=$ERR['even24idencuesta'];}
	if ($sError==''){
		if ((int)$even24id==0){
			if ($sError==''){
				$sql='SELECT even24idencuesta FROM even24encuestaperaca WHERE even24idencuesta='.$even24idencuesta.' AND even24idperaca='.$even24idperaca.'';
				$result=$objdb->ejecutasql($sql);
				if ($objdb->nf($result)!=0){
					$sError=$ERR['existe'];
					}else{
					if (!seg_revisa_permiso($icodmodulo, 2, $objdb)){$sError=$ERR['2'];}
					}
				}
			if ($sError==''){
				$even24id=tabla_consecutivo('even24encuestaperaca', 'even24id', '', $objdb);
				if ($even24id==-1){$sError=$objdb->serror;}
				$binserta=true;
				$iAccion=2;
				}
			}else{
			if (!seg_revisa_permiso($icodmodulo, 3, $objdb)){$sError=$ERR['3'];}
			}
		}
	if ($sError==''){
		if ($binserta){
			$scampos='even24idencuesta, even24idperaca, even24id, even24fechainicial, even24fechafinal';
			$svalores=''.$even24idencuesta.', '.$even24idperaca.', '.$even24id.', "'.$even24fechainicial.'", "'.$even24fechafinal.'"';
			if ($APP->utf8==1){
				$sql='INSERT INTO even24encuestaperaca ('.$scampos.') VALUES ('.utf8_encode($svalores).');';
				}else{
				$sql='INSERT INTO even24encuestaperaca ('.$scampos.') VALUES ('.$svalores.');';
				}
			$result=$objdb->ejecutasql($sql);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' {Periodos que aplican}.<!-- '.$sql.' -->';
				}else{
				if ($bAudita[2]){
					seg_auditar($icodmodulo, $_SESSION['unad_id_tercero'], 2, $even24id, $sql, $objdb);
					}
				}
			}else{
			$scampo1924[1]='even24fechainicial';
			$scampo1924[2]='even24fechafinal';
			$svr1924[1]=$even24fechainicial;
			$svr1924[2]=$even24fechafinal;
			$inumcampos=2;
			$sWhere='even24id='.$even24id.'';
			//$sWhere='even24idencuesta='.$even24idencuesta.' AND even24idperaca='.$even24idperaca.'';
			$sql='SELECT * FROM even24encuestaperaca WHERE '.$sWhere;
			$sdatos='';
			$bpasa=false;
			$result=$objdb->ejecutasql($sql);
			if ($objdb->nf($result)>0){
				$filaorigen=$objdb->sf($result);
				for ($k=1;$k<=$inumcampos;$k++){
					if ($filaorigen[$scampo1924[$k]]!=$svr1924[$k]){
						if ($sdatos!=''){$sdatos=$sdatos.', ';}
						$sdatos=$sdatos.$scampo1924[$k].'="'.$svr1924[$k].'"';
						$bpasa=true;
						}
					}
				}
			if ($bpasa){
				if ($APP->utf8==1){
					$sql='UPDATE even24encuestaperaca SET '.utf8_encode($sdatos).' WHERE '.$sWhere.';';
					}else{
					$sql='UPDATE even24encuestaperaca SET '.$sdatos.' WHERE '.$sWhere.';';
					}
				$result=$objdb->ejecutasql($sql);
				if ($result==false){
					$sError=$ERR['falla_guardar'].' {Periodos que aplican}. <!-- '.$sql.' -->';
					}else{
					if ($bAudita[3]){
						seg_auditar($icodmodulo, $_SESSION['unad_id_tercero'], 3, $even24id, $sql, $objdb);
						}
					}
				}
			}
		}
	return array($sError, $iAccion, $even24id, $sDebug);
	}
function f1924_db_Eliminar($params, $objdb, $bDebug=false){
	$icodmodulo=1924;
	$bAudita[4]=false;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_1924='lg/lg_1924_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1924)){$mensajes_1924='lg/lg_1924_es.php';}
	require $mensajes_todas;
	require $mensajes_1924;
	$sError='';
	$sDebug='';
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	$even24idencuesta=numeros_validar($params[1]);
	$even24idperaca=numeros_validar($params[2]);
	$even24id=numeros_validar($params[3]);
	if ($sError==''){
		if (!seg_revisa_permiso($icodmodulo, 4, $objdb)){
			$sError=$ERR['4'];
			}
		}
	if ($sError==''){
		//acciones previas
		$sWhere='even24id='.$even24id.'';
		//$sWhere='even24idencuesta='.$even24idencuesta.' AND even24idperaca='.$even24idperaca.'';
		$sql='DELETE FROM even24encuestaperaca WHERE '.$sWhere.';';
		$result=$objdb->ejecutasql($sql);
		if ($result==false){
			$sError=$ERR['falla_eliminar'].' {1924 Periodos que aplican}.<!-- '.$sql.' -->';
			}else{
			if ($bAudita[4]){
				seg_auditar($icodmodulo, $_SESSION['unad_id_tercero'], 4, $even24id, $sql, $objdb);
				}
			}
		}
	return array($sError, $sDebug);
	}
function f1924_TablaDetalleV2($params, $objdb, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_1924='lg/lg_1924_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1924)){$mensajes_1924='lg/lg_1924_es.php';}
	require $mensajes_todas;
	require $mensajes_1924;
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
	$babierta=true;
	/*
	$sql='SELECT even16publicada FROM even16encuesta WHERE even16id='.$even16id;
	$tabla=$objdb->ejecutasql($sql);
	if ($objdb->nf($tabla)>0){
		$fila=$objdb->sf($tabla);
		if ($fila['even16publicada']!='S'){$babierta=true;}
		}
	*/
	$sqladd='';
	//if (isset($params[103])==0){$params[103]='';}
	//if ((int)$params[103]!=-1){$sqladd=$sqladd.' AND TB.campo='.$params[103];}
	//if ($params[103]!=''){$sqladd=$sqladd.' AND TB.campo2 LIKE "%'.$params[103].'%"';}
	$sTitulos='Encuesta, Peraca, Id, Fechainicial, Fechafinal';
	$sql='SELECT TB.even24idencuesta, T2.exte02nombre, TB.even24id, TB.even24fechainicial, TB.even24fechafinal, TB.even24idperaca 
FROM even24encuestaperaca AS TB, exte02per_aca AS T2 
WHERE TB.even24idencuesta='.$even16id.' AND TB.even24idperaca=T2.exte02id '.$sqladd.'
ORDER BY TB.even24idperaca DESC';
	$sqllista=str_replace("'","|",$sql);
	$sqllista=str_replace('"',"|",$sqllista);
	$sErrConsulta='<input id="consulta_1924" name="consulta_1924" type="hidden" value="'.$sqllista.'"/>
<input id="titulos_1924" name="titulos_1924" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objdb->ejecutasql($sql);
	if ($tabladetalle==false){
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 1924: '.$sql.'<br>';}
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sql.' '.$objdb->serror.'"/>';
		}else{
		$registros=$objdb->nf($tabladetalle);
		if ($registros==0){
			return array(utf8_encode($sErrConsulta.'<input id="paginaf1924" name="paginaf1924" type="hidden" value="'.$pagina.'"/><input id="lppf1924" name="lppf1924" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
<td><b>'.$ETI['even24idperaca'].'</b></td>
<td><b>'.$ETI['even24fechainicial'].'</b></td>
<td><b>'.$ETI['even24fechafinal'].'</b></td>
<td align="right">
'.html_paginador('paginaf1924', $registros, $lineastabla, $pagina, 'paginarf1924()').'
'.html_lpp('lppf1924', $lineastabla, 'paginarf1924()').'
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
		$et_even24idperaca=$sPrefijo.cadena_notildes($filadet['exte02nombre']).$sSufijo;
		$et_even24fechainicial='';
		if ($filadet['even24fechainicial']!='00/00/0000'){$et_even24fechainicial=$sPrefijo.$filadet['even24fechainicial'].$sSufijo;}
		$et_even24fechafinal='';
		if ($filadet['even24fechafinal']!='00/00/0000'){$et_even24fechafinal=$sPrefijo.$filadet['even24fechafinal'].$sSufijo;}
		if ($babierta){
			$sLink='<a href="javascript:cargaridf1924('."'".$filadet['even24id']."'".')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$res=$res.'<tr'.$sClass.'>
<td>'.$et_even24idperaca.'</td>
<td>'.$et_even24fechainicial.'</td>
<td>'.$et_even24fechafinal.'</td>
<td>'.$sLink.'</td>
</tr>';
		}
	$res=$res.'</table>';
	return array(utf8_encode($res), $sDebug);
	}
function f1924_Clonar($even24idencuesta, $even24idencuestaPadre, $objdb){
	$sError='';
	$even24id=tabla_consecutivo('even24encuestaperaca', 'even24id', '', $objdb);
	if ($even24id==-1){$sError=$objdb->serror;}
	if ($sError==''){
		$sCampos1924='even24idencuesta, even24idperaca, even24id, even24fechainicial, even24fechafinal';
		$sValores1924='';
		$sql='SELECT * FROM even24encuestaperaca WHERE even24idencuesta='.$even24idencuestaPadre.'';
		$tabla=$objdb->ejecutasql($sql);
		while($fila=$objdb->sf($tabla)){
			if ($sValores1924!=''){$sValores1924=$sValores1924.', ';}
			$sValores1924=$sValores1924.'('.$even24idencuesta.', '.$fila['even24idperaca'].', '.$even24id.', "'.$fila['even24fechainicial'].'", "'.$fila['even24fechafinal'].'")';
			$even24id++;
			}
		if ($sValores1924!=''){
			$sql='INSERT INTO even24encuestaperaca('.$sCampos1924.') VALUES '.$sValores1924.'';
			$result=$objdb->ejecutasql($sql);
			}
		}
	return $sError;
	}
// -- 1924 Periodos que aplican XAJAX 
function f1924_Guardar($valores, $params){
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
		$objdb=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objdb->dbPuerto=$APP->dbpuerto;}
		$objdb->xajax();
		list($sError, $iAccion, $even24id, $sDebugGuardar)=f1924_db_Guardar($valores, $objdb, $bDebug);
		$sDebug=$sDebug.$sDebugGuardar;
		}
	$objResponse=new xajaxResponse();
	if ($sError==''){
		list($sdetalle, $sDebugTabla)=f1924_TablaDetalleV2($params, $objdb, $bDebug);
		$sDebug=$sDebug.$sDebugTabla;
		$objResponse->assign('div_f1924detalle', 'innerHTML', $sdetalle);
		//if ($iAccion==2){
			//$objResponse->call('cargaridf1924('.$even24id.')');
			//}else{
			$objResponse->call('limpiaf1924');
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
function f1924_Traer($params){
	$_SESSION['u_ultimominuto']=iminutoavance();
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$sError='';
	$besta=false;
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	$paso=$params[0];
	if ($paso==1){
		$even24idencuesta=numeros_validar($params[1]);
		$even24idperaca=numeros_validar($params[2]);
		if (($even24idencuesta!='')&&($even24idperaca!='')){$besta=true;}
		}else{
		$even24id=$params[103];
		if ((int)$even24id!=0){$besta=true;}
		}
	if ($besta){
		$besta=false;
		$objdb=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objdb->dbPuerto=$APP->dbpuerto;}
		$objdb->xajax();
		$sqlcondi='';
		if ($paso==1){
			$sqlcondi=$sqlcondi.'even24idencuesta='.$even24idencuesta.' AND even24idperaca='.$even24idperaca.'';
			}else{
			$sqlcondi=$sqlcondi.'even24id='.$even24id.'';
			}
		$sql='SELECT * FROM even24encuestaperaca WHERE '.$sqlcondi;
		$tabla=$objdb->ejecutasql($sql);
		if ($objdb->nf($tabla)>0){
			$fila=$objdb->sf($tabla);
			$besta=true;
			}
		}
	$objResponse=new xajaxResponse();
	if ($besta){
		list($even24idperaca_nombre, $serror_det)=tabla_campoxid('exte02per_aca','exte02nombre','exte02id', $fila['even24idperaca'],'{'.$ETI['msg_sindato'].'}', $objdb);
		$html_even24idperaca=html_oculto('even24idperaca', $fila['even24idperaca'], $even24idperaca_nombre);
		$objResponse->assign('div_even24idperaca', 'innerHTML', $html_even24idperaca);
		$even24id_nombre='';
		$html_even24id=html_oculto('even24id', $fila['even24id'], $even24id_nombre);
		$objResponse->assign('div_even24id', 'innerHTML', $html_even24id);
		$objResponse->assign('even24fechainicial', 'value', $fila['even24fechainicial']);
		$objResponse->assign('even24fechainicial_dia', 'value', substr($fila['even24fechainicial'], 0, 2));
		$objResponse->assign('even24fechainicial_mes', 'value', substr($fila['even24fechainicial'], 3, 2));
		$objResponse->assign('even24fechainicial_agno', 'value', substr($fila['even24fechainicial'], 6, 4));
		$objResponse->assign('even24fechafinal', 'value', $fila['even24fechafinal']);
		$objResponse->assign('even24fechafinal_dia', 'value', substr($fila['even24fechafinal'], 0, 2));
		$objResponse->assign('even24fechafinal_mes', 'value', substr($fila['even24fechafinal'], 3, 2));
		$objResponse->assign('even24fechafinal_agno', 'value', substr($fila['even24fechafinal'], 6, 4));
		$objResponse->call("MensajeAlarmaV2('', 0)");
		$objResponse->call("verboton('belimina1924','block')");
		}else{
		if ($paso==1){
			$objResponse->assign('even24idperaca', 'value', $even24idperaca);
			}else{
			$objResponse->call('MensajeAlarmaV2("No se encontro el registro de referencia:'.$even24id.'", 0)');
			}
		}
	return $objResponse;
	}
function f1924_Eliminar($params){
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
	list($sError, $sDebugElimina)=f1924_db_Eliminar($params, $objdb, $bDebug);
	$sDebug=$sDebug.$sDebugElimina;
	$objResponse=new xajaxResponse();
	if ($sError==''){
		list($sDetalle, $sDebugTabla)=f1924_TablaDetalleV2($params, $objdb, $bDebug);
		$sDebug=$sDebug.$sDebugTabla;
		$objResponse->assign('div_f1924detalle', 'innerHTML', $sDetalle);
		$objResponse->call('limpiaf1924');
		$sError=$ETI['msg_itemeliminado'];
		$iTipoError=1;
		}
	$objResponse->call("MensajeAlarmaV2('".$sError."', ".$iTipoError.")");
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f1924_HtmlTabla($params){
	$_SESSION['u_ultimominuto']=iminutoavance();
	$sError='';
	$bDebug=false;
	$sDebug='';
	require './app.php';
	$objdb=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objdb->dbPuerto=$APP->dbpuerto;}
	$objdb->xajax();
	list($sDetalle, $sDebugTabla)=f1924_TablaDetalleV2($params, $objdb, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f1924detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f1924_PintarLlaves($params){
	$_SESSION['u_ultimominuto']=iminutoavance();
	//if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	require './app.php';
	$objdb=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objdb->dbPuerto=$APP->dbpuerto;}
	$objdb->xajax();
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$html_even24idperaca=html_combo_even24idperaca($objdb, 0);
	$html_even24id='<input id="even24id" name="even24id" type="hidden" value=""/>';
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_even24idperaca','innerHTML', $html_even24idperaca);
	$objResponse->assign('div_even24id','innerHTML', $html_even24id);
	return $objResponse;
	}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------
?>