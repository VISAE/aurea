<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2016 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.12.5 lunes, 14 de marzo de 2016
--- Modelo Versión 2.15.8 miércoles, 02 de noviembre de 2016
--- 1925 Cursos que aplican
*/
function f1925_db_Guardar($valores, $objdb, $bDebug=false){
	$icodmodulo=1925;
	$bAudita[2]=false;
	$bAudita[3]=false;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_1925='lg/lg_1925_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1925)){$mensajes_1925='lg/lg_1925_es.php';}
	require $mensajes_todas;
	require $mensajes_1925;
	$sError='';
	$sDebug='';
	$binserta=false;
	$iAccion=3;
	if(!is_array($valores)){$valores=json_decode(str_replace('\"','"',$valores),true);}
	$even25idencuesta=numeros_validar($valores[1]);
	$even25idcurso=numeros_validar($valores[2]);
	$even25id=numeros_validar($valores[3], true);
	$even25activo=htmlspecialchars(trim($valores[4]));
	if ($even25activo==''){$sError=$ERR['even25activo'];}
	//if ($even25id==''){$sError=$ERR['even25id'];}//CONSECUTIVO
	if ($even25idcurso==0){$sError=$ERR['even25idcurso'];}
	if ($even25idencuesta==''){$sError=$ERR['even25idencuesta'];}
	if ($sError==''){
		$sql='SELECT unad40id FROM unad40curso WHERE unad40id="'.$even25idcurso.'"';
		$result=$objdb->ejecutasql($sql);
		if ($objdb->nf($result)==0){$sError='No se encuentra el Curso {ref '.$even25idcurso.'}';}
		}
	if ($sError==''){
		if ((int)$even25id==0){
			if ($sError==''){
				$sql='SELECT even25idencuesta FROM even25encuestacurso WHERE even25idencuesta='.$even25idencuesta.' AND even25idcurso='.$even25idcurso.'';
				$result=$objdb->ejecutasql($sql);
				if ($objdb->nf($result)!=0){
					$sError=$ERR['existe'];
					}else{
					if (!seg_revisa_permiso($icodmodulo, 2, $objdb)){$sError=$ERR['2'];}
					}
				}
			if ($sError==''){
				$even25id=tabla_consecutivo('even25encuestacurso', 'even25id', '', $objdb);
				if ($even25id==-1){$sError=$objdb->serror;}
				$binserta=true;
				$iAccion=2;
				}
			}else{
			if (!seg_revisa_permiso($icodmodulo, 3, $objdb)){$sError=$ERR['3'];}
			}
		}
	if ($sError==''){
		if ($binserta){
			}
		}
	if ($sError==''){
		if ($binserta){
			$scampos='even25idencuesta, even25idcurso, even25id, even25activo';
			$svalores=''.$even25idencuesta.', '.$even25idcurso.', '.$even25id.', "'.$even25activo.'"';
			if ($APP->utf8==1){
				$sql='INSERT INTO even25encuestacurso ('.$scampos.') VALUES ('.utf8_encode($svalores).');';
				}else{
				$sql='INSERT INTO even25encuestacurso ('.$scampos.') VALUES ('.$svalores.');';
				}
			$result=$objdb->ejecutasql($sql);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' {Cursos que aplican}.<!-- '.$sql.' -->';
				}else{
				if ($bAudita[2]){
					seg_auditar($icodmodulo, $_SESSION['unad_id_tercero'], 2, $even25id, $sql, $objdb);
					}
				}
			}else{
			$scampo1925[1]='even25activo';
			$svr1925[1]=$even25activo;
			$inumcampos=1;
			$sWhere='even25id='.$even25id.'';
			//$sWhere='even25idencuesta='.$even25idencuesta.' AND even25idcurso='.$even25idcurso.'';
			$sql='SELECT * FROM even25encuestacurso WHERE '.$sWhere;
			$sdatos='';
			$bpasa=false;
			$result=$objdb->ejecutasql($sql);
			if ($objdb->nf($result)>0){
				$filaorigen=$objdb->sf($result);
				for ($k=1;$k<=$inumcampos;$k++){
					if ($filaorigen[$scampo1925[$k]]!=$svr1925[$k]){
						if ($sdatos!=''){$sdatos=$sdatos.', ';}
						$sdatos=$sdatos.$scampo1925[$k].'="'.$svr1925[$k].'"';
						$bpasa=true;
						}
					}
				}
			if ($bpasa){
				if ($APP->utf8==1){
					$sql='UPDATE even25encuestacurso SET '.utf8_encode($sdatos).' WHERE '.$sWhere.';';
					}else{
					$sql='UPDATE even25encuestacurso SET '.$sdatos.' WHERE '.$sWhere.';';
					}
				$result=$objdb->ejecutasql($sql);
				if ($result==false){
					$sError=$ERR['falla_guardar'].' {Cursos que aplican}. <!-- '.$sql.' -->';
					}else{
					if ($bAudita[3]){
						seg_auditar($icodmodulo, $_SESSION['unad_id_tercero'], 3, $even25id, $sql, $objdb);
						}
					}
				}
			}
		}
	return array($sError, $iAccion, $even25id, $sDebug);
	}
function f1925_db_Eliminar($params, $objdb, $bDebug=false){
	$icodmodulo=1925;
	$bAudita[4]=false;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_1925='lg/lg_1925_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1925)){$mensajes_1925='lg/lg_1925_es.php';}
	require $mensajes_todas;
	require $mensajes_1925;
	$sError='';
	$sDebug='';
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	$even25idencuesta=numeros_validar($params[1]);
	$even25idcurso=numeros_validar($params[2]);
	$even25id=numeros_validar($params[3]);
	if ($sError==''){
		if (!seg_revisa_permiso($icodmodulo, 4, $objdb)){
			$sError=$ERR['4'];
			}
		}
	if ($sError==''){
		//acciones previas
		$sWhere='even25id='.$even25id.'';
		//$sWhere='even25idencuesta='.$even25idencuesta.' AND even25idcurso='.$even25idcurso.'';
		$sql='DELETE FROM even25encuestacurso WHERE '.$sWhere.';';
		$result=$objdb->ejecutasql($sql);
		if ($result==false){
			$sError=$ERR['falla_eliminar'].' {1925 Cursos que aplican}.<!-- '.$sql.' -->';
			}else{
			if ($bAudita[4]){
				seg_auditar($icodmodulo, $_SESSION['unad_id_tercero'], 4, $even25id, $sql, $objdb);
				}
			}
		}
	return array($sError, $sDebug);
	}
function f1925_TablaDetalleV2($params, $objdb, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_1925='lg/lg_1925_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1925)){$mensajes_1925='lg/lg_1925_es.php';}
	require $mensajes_todas;
	require $mensajes_1925;
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
	$sTitulos='Encuesta, Curso, Id, Activo';
	$sql='SELECT TB.even25idencuesta, T2.unad40nombre, TB.even25id, TB.even25activo, TB.even25idcurso 
FROM even25encuestacurso AS TB, unad40curso AS T2 
WHERE TB.even25idencuesta='.$even16id.' AND TB.even25idcurso=T2.unad40id '.$sqladd.'
ORDER BY TB.even25idcurso';
	$sqllista=str_replace("'","|",$sql);
	$sqllista=str_replace('"',"|",$sqllista);
	$sErrConsulta='<input id="consulta_1925" name="consulta_1925" type="hidden" value="'.$sqllista.'"/>
<input id="titulos_1925" name="titulos_1925" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objdb->ejecutasql($sql);
	if ($tabladetalle==false){
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 1925: '.$sql.'<br>';}
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sql.' '.$objdb->serror.'"/>';
		}else{
		$registros=$objdb->nf($tabladetalle);
		if ($registros==0){
			return array(utf8_encode($sErrConsulta.'<input id="paginaf1925" name="paginaf1925" type="hidden" value="'.$pagina.'"/><input id="lppf1925" name="lppf1925" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
<td colspan="2"><b>'.$ETI['even25idcurso'].'</b></td>
<td><b>'.$ETI['even25activo'].'</b></td>
<td align="right">
'.html_paginador('paginaf1925', $registros, $lineastabla, $pagina, 'paginarf1925()').'
'.html_lpp('lppf1925', $lineastabla, 'paginarf1925()').'
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
		$et_even25idcurso_cod=$filadet['even25idcurso'];
		$et_even25idcurso=$sPrefijo.cadena_notildes($filadet['unad40nombre']).$sSufijo;
		$et_even25activo=$ETI['no'];
		if ($filadet['even25activo']=='S'){$et_even25activo=$ETI['si'];}
		if ($babierta){
			$sLink='<a href="javascript:cargaridf1925('."'".$filadet['even25id']."'".')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$res=$res.'<tr'.$sClass.'>
<td>'.$et_even25idcurso_cod.'</td>
<td>'.$et_even25idcurso.'</td>
<td>'.$et_even25activo.'</td>
<td>'.$sLink.'</td>
</tr>';
		}
	$res=$res.'</table>';
	return array(utf8_encode($res), $sDebug);
	}
function f1925_Clonar($even25idencuesta, $even25idencuestaPadre, $objdb){
	$sError='';
	$even25id=tabla_consecutivo('even25encuestacurso', 'even25id', '', $objdb);
	if ($even25id==-1){$sError=$objdb->serror;}
	if ($sError==''){
		$sCampos1925='even25idencuesta, even25idcurso, even25id, even25activo';
		$sValores1925='';
		$sql='SELECT * FROM even25encuestacurso WHERE even25idencuesta='.$even25idencuestaPadre.'';
		$tabla=$objdb->ejecutasql($sql);
		while($fila=$objdb->sf($tabla)){
			if ($sValores1925!=''){$sValores1925=$sValores1925.', ';}
			$sValores1925=$sValores1925.'('.$even25idencuesta.', '.$fila['even25idcurso'].', '.$even25id.', "'.$fila['even25activo'].'")';
			$even25id++;
			}
		if ($sValores1925!=''){
			$sql='INSERT INTO even25encuestacurso('.$sCampos1925.') VALUES '.$sValores1925.'';
			$result=$objdb->ejecutasql($sql);
			}
		}
	return $sError;
	}
// -- 1925 Cursos que aplican XAJAX 
function TraerBusqueda_db_even25idcurso($sCodigo, $objdb){
	$sRespuesta='';
	$id=0;
	$sCodigo=htmlspecialchars(trim($sCodigo));
	if ($sCodigo!=''){
		$sql='SELECT unad40id, unad40nombre, unad40id FROM unad40curso WHERE unad40id="'.$sCodigo.'"';
		$res=$objdb->ejecutasql($sql);
		if ($objdb->nf($res)!=0){
			$fila=$objdb->sf($res);
			$sRespuesta='<b>'.$fila['unad40id'].' '.cadena_notildes($fila['unad40nombre']).'</b>';
			$id=$fila['unad40id'];
			}
		if ($sRespuesta==''){
			$sRespuesta='<span class="rojo">{'.$sCodigo.' No encontrado}</span>';
			}
		}
	return array($id, utf8_encode($sRespuesta));
	}
function TraerBusqueda_even25idcurso($params){
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	$_SESSION['u_ultimominuto']=iminutoavance();
	$sRespuesta='';
	$scodigo=$params[0];
	$bxajax=true;
	if (isset($params[3])!=0){if ($params[3]==1){$bxajax=false;}}
	$id=0;
	if ($scodigo!=''){
		require './app.php';
		$objdb=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objdb->dbPuerto=$APP->dbpuerto;}
		$objdb->xajax();
		list($id, $sRespuesta)=TraerBusqueda_db_even25idcurso($scodigo, $objdb);
		}
	$objid=$params[1];
	$sdiv=$params[2];
	$objResponse=new xajaxResponse();
	$objResponse->assign($sdiv, 'innerHTML', $sRespuesta);
	$objResponse->assign($objid, 'value', $id);
	if ((int)$id>0){
		$objResponse->call('revisaf1925');
		}
	return $objResponse;
	}
function f1925_Guardar($valores, $params){
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
		list($sError, $iAccion, $even25id, $sDebugGuardar)=f1925_db_Guardar($valores, $objdb, $bDebug);
		$sDebug=$sDebug.$sDebugGuardar;
		}
	$objResponse=new xajaxResponse();
	if ($sError==''){
		list($sdetalle, $sDebugTabla)=f1925_TablaDetalleV2($params, $objdb, $bDebug);
		$sDebug=$sDebug.$sDebugTabla;
		$objResponse->assign('div_f1925detalle', 'innerHTML', $sdetalle);
		//if ($iAccion==2){
			//$objResponse->call('cargaridf1925('.$even25id.')');
			//}else{
			$objResponse->call('limpiaf1925');
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
function f1925_Traer($params){
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
		$even25idencuesta=numeros_validar($params[1]);
		$even25idcurso=numeros_validar($params[2]);
		if (($even25idencuesta!='')&&($even25idcurso!='')){$besta=true;}
		}else{
		$even25id=$params[103];
		if ((int)$even25id!=0){$besta=true;}
		}
	if ($besta){
		$besta=false;
		$objdb=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objdb->dbPuerto=$APP->dbpuerto;}
		$objdb->xajax();
		$sqlcondi='';
		if ($paso==1){
			$sqlcondi=$sqlcondi.'even25idencuesta='.$even25idencuesta.' AND even25idcurso='.$even25idcurso.'';
			}else{
			$sqlcondi=$sqlcondi.'even25id='.$even25id.'';
			}
		$sql='SELECT * FROM even25encuestacurso WHERE '.$sqlcondi;
		$tabla=$objdb->ejecutasql($sql);
		if ($objdb->nf($tabla)>0){
			$fila=$objdb->sf($tabla);
			$besta=true;
			}
		}
	$objResponse=new xajaxResponse();
	if ($besta){
		$even25idcurso_nombre='';
		$even25idcurso_cod='';
		if ((int)$fila['even25idcurso']!=0){
			$sql='SELECT unad40id, unad40nombre FROM unad40curso WHERE unad40id='.$fila['even25idcurso'].'';
			$res=$objdb->ejecutasql($sql);
			if ($objdb->nf($res)!=0){
				$filaDetalle=$objdb->sf($res);
				$even25idcurso_nombre='<b>'.cadena_notildes($filaDetalle['unad40nombre']).'</b>';
				$even25idcurso_cod=$filaDetalle['unad40id'];
				}
			if ($even25idcurso_nombre==''){
				$even25idcurso_nombre='<font class="rojo">{Ref : '.$fila['even25idcurso'].' No encontrado}</font>';
				}
			}
		$html_even25idcurso_cod=html_oculto('even25idcurso_cod', $even25idcurso_cod);
		$objResponse->assign('even25idcurso', 'value', $fila['even25idcurso']);
		$objResponse->assign('div_even25idcurso_cod', 'innerHTML', $html_even25idcurso_cod);
		$objResponse->call("verboton('beven25idcurso','none')");
		$objResponse->assign('div_even25idcurso', 'innerHTML', $even25idcurso_nombre);
		$even25id_nombre='';
		$html_even25id=html_oculto('even25id', $fila['even25id'], $even25id_nombre);
		$objResponse->assign('div_even25id', 'innerHTML', $html_even25id);
		$objResponse->assign('even25activo', 'value', $fila['even25activo']);
		$objResponse->call("MensajeAlarmaV2('', 0)");
		$objResponse->call("verboton('belimina1925','block')");
		}else{
		if ($paso==1){
			$objResponse->assign('even25idcurso', 'value', $even25idcurso);
			}else{
			$objResponse->call('MensajeAlarmaV2("No se encontro el registro de referencia:'.$even25id.'", 0)');
			}
		}
	return $objResponse;
	}
function f1925_Eliminar($params){
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
	list($sError, $sDebugElimina)=f1925_db_Eliminar($params, $objdb, $bDebug);
	$sDebug=$sDebug.$sDebugElimina;
	$objResponse=new xajaxResponse();
	if ($sError==''){
		list($sDetalle, $sDebugTabla)=f1925_TablaDetalleV2($params, $objdb, $bDebug);
		$sDebug=$sDebug.$sDebugTabla;
		$objResponse->assign('div_f1925detalle', 'innerHTML', $sDetalle);
		$objResponse->call('limpiaf1925');
		$sError=$ETI['msg_itemeliminado'];
		$iTipoError=1;
		}
	$objResponse->call("MensajeAlarmaV2('".$sError."', ".$iTipoError.")");
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f1925_HtmlTabla($params){
	$_SESSION['u_ultimominuto']=iminutoavance();
	$sError='';
	$bDebug=false;
	$sDebug='';
	require './app.php';
	$objdb=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objdb->dbPuerto=$APP->dbpuerto;}
	$objdb->xajax();
	list($sDetalle, $sDebugTabla)=f1925_TablaDetalleV2($params, $objdb, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f1925detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f1925_PintarLlaves($params){
	$_SESSION['u_ultimominuto']=iminutoavance();
	//if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	require './app.php';
	$objdb=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objdb->dbPuerto=$APP->dbpuerto;}
	$objdb->xajax();
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$html_even25idcurso_cod='<input id="even25idcurso_cod" name="even25idcurso_cod" type="text" value="" onchange="cod_even25idcurso()" class="veinte"/>';
	$html_even25id='<input id="even25id" name="even25id" type="hidden" value=""/>';
	$objResponse=new xajaxResponse();
	$objResponse->assign('even25idcurso','value', '0');
	$objResponse->assign('div_even25idcurso_cod','innerHTML', $html_even25idcurso_cod);
	$objResponse->assign('div_even25idcurso','innerHTML', '');
	$objResponse->call("verboton('beven25idcurso','block')");
	$objResponse->assign('div_even25id','innerHTML', $html_even25id);
	return $objResponse;
	}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------
?>