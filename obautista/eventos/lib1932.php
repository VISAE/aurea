<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2016 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.15.8 miércoles, 02 de noviembre de 2016
--- 1932 Roles que aplican
*/
function f1932_HTMLComboV2_even32idrol($objdb, $objCombos, $valor){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('even32idrol', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	$objCombos->sAccion='revisaf1932()';
	$res=$objCombos->html('SELECT unad58id AS id, unad58nombre AS nombre FROM unad58rolmoodle', $objdb);
	return $res;
	}
function f1932_db_Guardar($valores, $objdb, $bDebug=false){
	$icodmodulo=1932;
	$bAudita[2]=false;
	$bAudita[3]=false;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_1932='lg/lg_1932_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1932)){$mensajes_1932='lg/lg_1932_es.php';}
	require $mensajes_todas;
	require $mensajes_1932;
	$sError='';
	$sDebug='';
	$binserta=false;
	$iAccion=3;
	if(!is_array($valores)){$valores=json_decode(str_replace('\"','"',$valores),true);}
	$even32idencuesta=numeros_validar($valores[1]);
	$even32idrol=numeros_validar($valores[2]);
	$even32id=numeros_validar($valores[3], true);
	$even32activo=htmlspecialchars(trim($valores[4]));
	if ($even32activo==''){$sError=$ERR['even32activo'];}
	//if ($even32id==''){$sError=$ERR['even32id'];}//CONSECUTIVO
	if ($even32idrol==''){$sError=$ERR['even32idrol'];}
	if ($even32idencuesta==''){$sError=$ERR['even32idencuesta'];}
	if ($sError==''){
		if ((int)$even32id==0){
			if ($sError==''){
				$sql='SELECT even32idencuesta FROM even32encuestarol WHERE even32idencuesta='.$even32idencuesta.' AND even32idrol='.$even32idrol.'';
				$result=$objdb->ejecutasql($sql);
				if ($objdb->nf($result)!=0){
					$sError=$ERR['existe'];
					}else{
					if (!seg_revisa_permiso($icodmodulo, 2, $objdb)){$sError=$ERR['2'];}
					}
				}
			if ($sError==''){
				$even32id=tabla_consecutivo('even32encuestarol', 'even32id', '', $objdb);
				if ($even32id==-1){$sError=$objdb->serror;}
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
			$scampos='even32idencuesta, even32idrol, even32id, even32activo';
			$svalores=''.$even32idencuesta.', '.$even32idrol.', '.$even32id.', "'.$even32activo.'"';
			if ($APP->utf8==1){
				$sql='INSERT INTO even32encuestarol ('.$scampos.') VALUES ('.cadena_codificar($svalores).');';
				}else{
				$sql='INSERT INTO even32encuestarol ('.$scampos.') VALUES ('.$svalores.');';
				}
			$result=$objdb->ejecutasql($sql);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' {Roles que aplican}.<!-- '.$sql.' -->';
				}else{
				if ($bAudita[2]){
					seg_auditar($icodmodulo, $_SESSION['unad_id_tercero'], 2, $even32id, $sql, $objdb);
					}
				}
			}else{
			$scampo1932[1]='even32activo';
			$svr1932[1]=$even32activo;
			$inumcampos=1;
			$sWhere='even32id='.$even32id.'';
			//$sWhere='even32idencuesta='.$even32idencuesta.' AND even32idrol='.$even32idrol.'';
			$sql='SELECT * FROM even32encuestarol WHERE '.$sWhere;
			$sdatos='';
			$bpasa=false;
			$result=$objdb->ejecutasql($sql);
			if ($objdb->nf($result)>0){
				$filaorigen=$objdb->sf($result);
				for ($k=1;$k<=$inumcampos;$k++){
					if ($filaorigen[$scampo1932[$k]]!=$svr1932[$k]){
						if ($sdatos!=''){$sdatos=$sdatos.', ';}
						$sdatos=$sdatos.$scampo1932[$k].'="'.$svr1932[$k].'"';
						$bpasa=true;
						}
					}
				}
			if ($bpasa){
				if ($APP->utf8==1){
					$sql='UPDATE even32encuestarol SET '.cadena_codificar($sdatos).' WHERE '.$sWhere.';';
					}else{
					$sql='UPDATE even32encuestarol SET '.$sdatos.' WHERE '.$sWhere.';';
					}
				$result=$objdb->ejecutasql($sql);
				if ($result==false){
					$sError=$ERR['falla_guardar'].' {Roles que aplican}. <!-- '.$sql.' -->';
					}else{
					if ($bAudita[3]){
						seg_auditar($icodmodulo, $_SESSION['unad_id_tercero'], 3, $even32id, $sql, $objdb);
						}
					}
				}
			}
		}
	return array($sError, $iAccion, $even32id, $sDebug);
	}
function f1932_db_Eliminar($params, $objdb, $bDebug=false){
	$icodmodulo=1932;
	$bAudita[4]=false;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_1932='lg/lg_1932_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1932)){$mensajes_1932='lg/lg_1932_es.php';}
	require $mensajes_todas;
	require $mensajes_1932;
	$sError='';
	$sDebug='';
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	$even32idencuesta=numeros_validar($params[1]);
	$even32idrol=numeros_validar($params[2]);
	$even32id=numeros_validar($params[3]);
	if ($sError==''){
		if (!seg_revisa_permiso($icodmodulo, 4, $objdb)){
			$sError=$ERR['4'];
			}
		}
	if ($sError==''){
		$sql='SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=1932';
		$tablaor=$objdb->ejecutasql($sql);
		while ($filaor=$objdb->sf($tablaor)){
			$sql='SELECT '.$filaor['campoid'].' FROM '.$filaor['tabla'].' WHERE '.$filaor['camporev'].'='.$even32id.' LIMIT 0, 1';
			$tabla=$objdb->ejecutasql($sql);
			if ($objdb->nf($tabla)>0){
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
		$sWhere='even32id='.$even32id.'';
		//$sWhere='even32idencuesta='.$even32idencuesta.' AND even32idrol='.$even32idrol.'';
		$sql='DELETE FROM even32encuestarol WHERE '.$sWhere.';';
		$result=$objdb->ejecutasql($sql);
		if ($result==false){
			$sError=$ERR['falla_eliminar'].' {1932 Roles que aplican}.<!-- '.$sql.' -->';
			}else{
			if ($bAudita[4]){
				seg_auditar($icodmodulo, $_SESSION['unad_id_tercero'], 4, $even32id, $sql, $objdb);
				}
			}
		}
	return array($sError, $sDebug);
	}
// Si necesita compatilidad con versiones anteriores se habilita esta parte.
//function f1932_TablaDetalle($params, $objdb){
	//list($sRes, $sDebug)=f1932_TablaDetalleV2($params, $objdb);
	//return $sRes;
	//}
function f1932_TablaDetalleV2($params, $objdb, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_1932='lg/lg_1932_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1932)){$mensajes_1932='lg/lg_1932_es.php';}
	require $mensajes_todas;
	require $mensajes_1932;
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
	$sTitulos='Encuesta, Rol, Id, Activo';
	$sql='SELECT TB.even32idencuesta, T2.unad58nombre, TB.even32id, TB.even32activo, TB.even32idrol 
FROM even32encuestarol AS TB, unad58rolmoodle AS T2 
WHERE TB.even32idencuesta='.$even16id.' AND TB.even32idrol=T2.unad58id '.$sqladd.'
ORDER BY TB.even32idrol';
	$sqllista=str_replace("'","|",$sql);
	$sqllista=str_replace('"',"|",$sqllista);
	$sErrConsulta='<input id="consulta_1932" name="consulta_1932" type="hidden" value="'.$sqllista.'"/>
<input id="titulos_1932" name="titulos_1932" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objdb->ejecutasql($sql);
	if ($tabladetalle==false){
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 1932: '.$sql.'<br>';}
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sql.' '.$objdb->serror.'"/>';
		}else{
		$registros=$objdb->nf($tabladetalle);
		if ($registros==0){
			return array(cadena_codificar($sErrConsulta.'<input id="paginaf1932" name="paginaf1932" type="hidden" value="'.$pagina.'"/><input id="lppf1932" name="lppf1932" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
<td><b>'.$ETI['even32idrol'].'</b></td>
<td><b>'.$ETI['even32activo'].'</b></td>
<td align="right">
'.html_paginador('paginaf1932', $registros, $lineastabla, $pagina, 'paginarf1932()').'
'.html_lpp('lppf1932', $lineastabla, 'paginarf1932()').'
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
		$et_even32idrol=$sPrefijo.cadena_notildes($filadet['unad58nombre']).$sSufijo;
		$et_even32activo=$ETI['no'];
		if ($filadet['even32activo']=='S'){$et_even32activo=$ETI['si'];}
		if ($babierta){
			$sLink='<a href="javascript:cargaridf1932('."'".$filadet['even32id']."'".')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$res=$res.'<tr'.$sClass.'>
<td>'.$et_even32idrol.'</td>
<td>'.$et_even32activo.'</td>
<td>'.$sLink.'</td>
</tr>';
		}
	$res=$res.'</table>';
	return array(cadena_codificar($res), $sDebug);
	}
function f1932_Clonar($even32idencuesta, $even32idencuestaPadre, $objdb){
	$sError='';
	$even32id=tabla_consecutivo('even32encuestarol', 'even32id', '', $objdb);
	if ($even32id==-1){$sError=$objdb->serror;}
	if ($sError==''){
		$sCampos1932='even32idencuesta, even32idrol, even32id, even32activo';
		$sValores1932='';
		$sql='SELECT * FROM even32encuestarol WHERE even32idencuesta='.$even32idencuestaPadre.'';
		$tabla=$objdb->ejecutasql($sql);
		while($fila=$objdb->sf($tabla)){
			if ($sValores1932!=''){$sValores1932=$sValores1932.', ';}
			$sValores1932=$sValores1932.'('.$even32idencuesta.', '.$fila['even32idrol'].', '.$even32id.', "'.$fila['even32activo'].'")';
			$even32id++;
			}
		if ($sValores1932!=''){
			$sql='INSERT INTO even32encuestarol('.$sCampos1932.') VALUES '.$sValores1932.'';
			$result=$objdb->ejecutasql($sql);
			}
		}
	return $sError;
	}
// -- 1932 Roles que aplican XAJAX 
function f1932_Guardar($valores, $params){
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
		list($sError, $iAccion, $even32id, $sDebugGuardar)=f1932_db_Guardar($valores, $objdb, $bDebug);
		$sDebug=$sDebug.$sDebugGuardar;
		}
	$objResponse=new xajaxResponse();
	if ($sError==''){
		list($sdetalle, $sDebugTabla)=f1932_TablaDetalleV2($params, $objdb, $bDebug);
		$sDebug=$sDebug.$sDebugTabla;
		$objResponse->assign('div_f1932detalle', 'innerHTML', $sdetalle);
		//if ($iAccion==2){
			//$objResponse->call('cargaridf1932('.$even32id.')');
			//}else{
			$objResponse->call('limpiaf1932');
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
function f1932_Traer($params){
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
		$even32idencuesta=numeros_validar($params[1]);
		$even32idrol=numeros_validar($params[2]);
		if (($even32idencuesta!='')&&($even32idrol!='')){$besta=true;}
		}else{
		$even32id=$params[103];
		if ((int)$even32id!=0){$besta=true;}
		}
	if ($besta){
		$besta=false;
		require './app.php';
		$objdb=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objdb->dbPuerto=$APP->dbpuerto;}
		$objdb->xajax();
		$sqlcondi='';
		if ($paso==1){
			$sqlcondi=$sqlcondi.'even32idencuesta='.$even32idencuesta.' AND even32idrol='.$even32idrol.'';
			}else{
			$sqlcondi=$sqlcondi.'even32id='.$even32id.'';
			}
		$sql='SELECT * FROM even32encuestarol WHERE '.$sqlcondi;
		$tabla=$objdb->ejecutasql($sql);
		if ($objdb->nf($tabla)>0){
			$fila=$objdb->sf($tabla);
			$besta=true;
			}
		}
	$objResponse=new xajaxResponse();
	if ($besta){
		list($even32idrol_nombre, $serror_det)=tabla_campoxid('unad58rolmoodle','unad58nombre','unad58id', $fila['even32idrol'],'{'.$ETI['msg_sindato'].'}', $objdb);
		$html_even32idrol=html_oculto('even32idrol', $fila['even32idrol'], $even32idrol_nombre);
		$objResponse->assign('div_even32idrol', 'innerHTML', $html_even32idrol);
		$even32id_nombre='';
		$html_even32id=html_oculto('even32id', $fila['even32id'], $even32id_nombre);
		$objResponse->assign('div_even32id', 'innerHTML', $html_even32id);
		$objResponse->assign('even32activo', 'value', $fila['even32activo']);
		$objResponse->call("MensajeAlarmaV2('', 0)");
		$objResponse->call("verboton('belimina1932','block')");
		}else{
		if ($paso==1){
			$objResponse->assign('even32idrol', 'value', $even32idrol);
			}else{
			$objResponse->call('MensajeAlarmaV2("No se encontro el registro de referencia:'.$even32id.'", 0)');
			}
		}
	return $objResponse;
	}
function f1932_Eliminar($params){
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
	require './app.php';
	$objdb=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objdb->dbPuerto=$APP->dbpuerto;}
	$objdb->xajax();
	list($sError, $sDebugElimina)=f1932_db_Eliminar($params, $objdb, $bDebug);
	$sDebug=$sDebug.$sDebugElimina;
	$objResponse=new xajaxResponse();
	if ($sError==''){
		list($sDetalle, $sDebugTabla)=f1932_TablaDetalleV2($params, $objdb, $bDebug);
		$sDebug=$sDebug.$sDebugTabla;
		$objResponse->assign('div_f1932detalle', 'innerHTML', $sDetalle);
		$objResponse->call('limpiaf1932');
		$sError=$ETI['msg_itemeliminado'];
		$iTipoError=1;
		}
	$objResponse->call("MensajeAlarmaV2('".$sError."', ".$iTipoError.")");
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f1932_HtmlTabla($params){
	$_SESSION['u_ultimominuto']=iminutoavance();
	$sError='';
	$bDebug=false;
	$sDebug='';
	require './app.php';
	$objdb=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objdb->dbPuerto=$APP->dbpuerto;}
	$objdb->xajax();
	list($sDetalle, $sDebugTabla)=f1932_TablaDetalleV2($params, $objdb, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f1932detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f1932_PintarLlaves($params){
	$_SESSION['u_ultimominuto']=iminutoavance();
	//if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	require './app.php';
	$objdb=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objdb->dbPuerto=$APP->dbpuerto;}
	$objdb->xajax();
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos=new clsHtmlCombos('n');
	$html_even32idrol=f1932_HTMLComboV2_even32idrol($objdb, $objCombos, 0);
	$html_even32id='<input id="even32id" name="even32id" type="hidden" value=""/>';
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_even32idrol','innerHTML', $html_even32idrol);
	$objResponse->assign('div_even32id','innerHTML', $html_even32id);
	return $objResponse;
	}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------
?>