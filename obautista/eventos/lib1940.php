<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2017 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.16.3 miércoles, 04 de enero de 2017
--- 1940 Propietarios
*/
function f1940_db_Guardar($valores, $objdb, $bDebug=false){
	$icodmodulo=1940;
	$bAudita[2]=false;
	$bAudita[3]=false;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_1940='lg/lg_1940_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1940)){$mensajes_1940='lg/lg_1940_es.php';}
	require $mensajes_todas;
	require $mensajes_1940;
	$sError='';
	$sDebug='';
	$binserta=false;
	$iAccion=3;
	if(!is_array($valores)){$valores=json_decode(str_replace('\"','"',$valores),true);}
	$even40idencuesta=numeros_validar($valores[1]);
	$even40idpropietario=numeros_validar($valores[2]);
	$even40id=numeros_validar($valores[3], true);
	$even40activo=htmlspecialchars(trim($valores[4]));
	if ($even40activo==''){$sError=$ERR['even40activo'];}
	//if ($even40id==''){$sError=$ERR['even40id'];}//CONSECUTIVO
	if ($even40idpropietario==0){$sError=$ERR['even40idpropietario'];}
	if ($even40idencuesta==''){$sError=$ERR['even40idencuesta'];}
	if ($sError==''){
		list($sError, $sInfo)=tercero_Bloqueado($even40idpropietario, $objdb);
		if ($sInfo!=''){$sError=$sError.'<br>'.sInfo;}
		}
	if ($sError==''){
		if ((int)$even40id==0){
			if ($sError==''){
				$sql='SELECT even40idencuesta FROM even40encuestaprop WHERE even40idencuesta='.$even40idencuesta.' AND even40idpropietario="'.$even40idpropietario.'"';
				$result=$objdb->ejecutasql($sql);
				if ($objdb->nf($result)!=0){
					$sError=$ERR['existe'];
					}else{
					if (!seg_revisa_permiso($icodmodulo, 2, $objdb)){$sError=$ERR['2'];}
					}
				}
			if ($sError==''){
				$even40id=tabla_consecutivo('even40encuestaprop', 'even40id', '', $objdb);
				if ($even40id==-1){$sError=$objdb->serror;}
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
			$scampos='even40idencuesta, even40idpropietario, even40id, even40activo';
			$svalores=''.$even40idencuesta.', "'.$even40idpropietario.'", '.$even40id.', "'.$even40activo.'"';
			if ($APP->utf8==1){
				$sql='INSERT INTO even40encuestaprop ('.$scampos.') VALUES ('.cadena_codificar($svalores).');';
				}else{
				$sql='INSERT INTO even40encuestaprop ('.$scampos.') VALUES ('.$svalores.');';
				}
			$result=$objdb->ejecutasql($sql);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' {Propietarios}.<!-- '.$sql.' -->';
				}else{
				if ($bAudita[2]){
					seg_auditar($icodmodulo, $_SESSION['unad_id_tercero'], 2, $even40id, $sql, $objdb);
					}
				}
			}else{
			$scampo1940[1]='even40activo';
			$svr1940[1]=$even40activo;
			$inumcampos=1;
			$sWhere='even40id='.$even40id.'';
			//$sWhere='even40idencuesta='.$even40idencuesta.' AND even40idpropietario="'.$even40idpropietario.'"';
			$sql='SELECT * FROM even40encuestaprop WHERE '.$sWhere;
			$sdatos='';
			$bpasa=false;
			$result=$objdb->ejecutasql($sql);
			if ($objdb->nf($result)>0){
				$filaorigen=$objdb->sf($result);
				for ($k=1;$k<=$inumcampos;$k++){
					if ($filaorigen[$scampo1940[$k]]!=$svr1940[$k]){
						if ($sdatos!=''){$sdatos=$sdatos.', ';}
						$sdatos=$sdatos.$scampo1940[$k].'="'.$svr1940[$k].'"';
						$bpasa=true;
						}
					}
				}
			if ($bpasa){
				if ($APP->utf8==1){
					$sql='UPDATE even40encuestaprop SET '.cadena_codificar($sdatos).' WHERE '.$sWhere.';';
					}else{
					$sql='UPDATE even40encuestaprop SET '.$sdatos.' WHERE '.$sWhere.';';
					}
				$result=$objdb->ejecutasql($sql);
				if ($result==false){
					$sError=$ERR['falla_guardar'].' {Propietarios}. <!-- '.$sql.' -->';
					}else{
					if ($bAudita[3]){
						seg_auditar($icodmodulo, $_SESSION['unad_id_tercero'], 3, $even40id, $sql, $objdb);
						}
					}
				}
			}
		}
	return array($sError, $iAccion, $even40id, $sDebug);
	}
function f1940_db_Eliminar($params, $objdb, $bDebug=false){
	$icodmodulo=1940;
	$bAudita[4]=false;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_1940='lg/lg_1940_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1940)){$mensajes_1940='lg/lg_1940_es.php';}
	require $mensajes_todas;
	require $mensajes_1940;
	$sError='';
	$sDebug='';
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	$even40idencuesta=numeros_validar($params[1]);
	$even40idpropietario=numeros_validar($params[2]);
	$even40id=numeros_validar($params[3]);
	if ($sError==''){
		if (!seg_revisa_permiso($icodmodulo, 4, $objdb)){
			$sError=$ERR['4'];
			}
		}
	if ($sError==''){
		$sql='SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=1940';
		$tablaor=$objdb->ejecutasql($sql);
		while ($filaor=$objdb->sf($tablaor)){
			$sql='SELECT '.$filaor['campoid'].' FROM '.$filaor['tabla'].' WHERE '.$filaor['camporev'].'='.$even40id.' LIMIT 0, 1';
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
		$sWhere='even40id='.$even40id.'';
		//$sWhere='even40idencuesta='.$even40idencuesta.' AND even40idpropietario="'.$even40idpropietario.'"';
		$sql='DELETE FROM even40encuestaprop WHERE '.$sWhere.';';
		$result=$objdb->ejecutasql($sql);
		if ($result==false){
			$sError=$ERR['falla_eliminar'].' {1940 Propietarios}.<!-- '.$sql.' -->';
			}else{
			if ($bAudita[4]){
				seg_auditar($icodmodulo, $_SESSION['unad_id_tercero'], 4, $even40id, $sql, $objdb);
				}
			}
		}
	return array($sError, $sDebug);
	}
// Si necesita compatilidad con versiones anteriores se habilita esta parte.
//function f1940_TablaDetalle($params, $objdb){
	//list($sRes, $sDebug)=f1940_TablaDetalleV2($params, $objdb);
	//return $sRes;
	//}
function f1940_TablaDetalleV2($params, $objdb, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_1940='lg/lg_1940_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1940)){$mensajes_1940='lg/lg_1940_es.php';}
	require $mensajes_todas;
	require $mensajes_1940;
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
	$sqladd1='';
	//if (isset($params[103])==0){$params[103]='';}
	//if ((int)$params[103]!=-1){$sqladd=$sqladd.' AND TB.campo='.$params[103];}
	//if ($params[103]!=''){$sqladd=$sqladd.' AND TB.campo2 LIKE "%'.$params[103].'%"';}
	/*
	if ($params[104]!=''){
		$sBase=trim(strtoupper($params[104]));
		$aNoms=explode(' ', $sBase);
		for ($k=1;$k<=count($aNoms);$k++){
			$sCadena=$aNoms[$k-1];
			if ($sCadena!=''){
				$sqladd=$sqladd.' AND T6.sys11razonsocial LIKE "%'.$sCadena.'%"';
				//$sqladd1=$sqladd1.'T1.sys11razonsocial LIKE "%'.$sCadena.'%" AND ';
				}
			}
		}
	*/
	$sTitulos='Encuesta, Propietario, Id, Activo';
	$sql='SELECT TB.even40idencuesta, T2.unad11razonsocial AS C2_nombre, TB.even40id, TB.even40activo, TB.even40idpropietario, T2.unad11tipodoc AS C2_td, T2.unad11doc AS C2_doc 
FROM even40encuestaprop AS TB, unad11terceros AS T2 
WHERE '.$sqladd1.' TB.even40idencuesta='.$even16id.' AND TB.even40idpropietario=T2.unad11id '.$sqladd.'
ORDER BY TB.even40idpropietario';
	$sqllista=str_replace("'","|",$sql);
	$sqllista=str_replace('"',"|",$sqllista);
	$sErrConsulta='<input id="consulta_1940" name="consulta_1940" type="hidden" value="'.$sqllista.'"/>
<input id="titulos_1940" name="titulos_1940" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objdb->ejecutasql($sql);
	if ($tabladetalle==false){
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 1940: '.$sql.'<br>';}
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sql.' '.$objdb->serror.'"/>';
		}else{
		$registros=$objdb->nf($tabladetalle);
		if ($registros==0){
			return array(cadena_codificar($sErrConsulta.'<input id="paginaf1940" name="paginaf1940" type="hidden" value="'.$pagina.'"/><input id="lppf1940" name="lppf1940" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
<td colspan="2"><b>'.$ETI['even40idpropietario'].'</b></td>
<td><b>'.$ETI['even40activo'].'</b></td>
<td align="right">
'.html_paginador('paginaf1940', $registros, $lineastabla, $pagina, 'paginarf1940()').'
'.html_lpp('lppf1940', $lineastabla, 'paginarf1940()').'
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
		$et_even40idpropietario=$sPrefijo.$filadet['even40idpropietario'].$sSufijo;
		$et_even40activo=$ETI['no'];
		if ($filadet['even40activo']=='S'){$et_even40activo=$ETI['si'];}
		if ($babierta){
			$sLink='<a href="javascript:cargaridf1940('.$filadet['even40id'].')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$res=$res.'<tr'.$sClass.'>
<td>'.$sPrefijo.$filadet['C2_td'].' '.$filadet['C2_doc'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['C2_nombre']).$sSufijo.'</td>
<td>'.$et_even40activo.'</td>
<td>'.$sLink.'</td>
</tr>';
		}
	$res=$res.'</table>';
	return array(cadena_codificar($res), $sDebug);
	}
function f1940_Clonar($even40idencuesta, $even40idencuestaPadre, $objdb){
	$sError='';
	$even40id=tabla_consecutivo('even40encuestaprop', 'even40id', '', $objdb);
	if ($even40id==-1){$sError=$objdb->serror;}
	if ($sError==''){
		$sCampos1940='even40idencuesta, even40idpropietario, even40id, even40activo';
		$sValores1940='';
		$sql='SELECT * FROM even40encuestaprop WHERE even40idencuesta='.$even40idencuestaPadre.'';
		$tabla=$objdb->ejecutasql($sql);
		while($fila=$objdb->sf($tabla)){
			if ($sValores1940!=''){$sValores1940=$sValores1940.', ';}
			$sValores1940=$sValores1940.'('.$even40idencuesta.', '.$fila['even40idpropietario'].', '.$even40id.', "'.$fila['even40activo'].'")';
			$even40id++;
			}
		if ($sValores1940!=''){
			$sql='INSERT INTO even40encuestaprop('.$sCampos1940.') VALUES '.$sValores1940.'';
			$result=$objdb->ejecutasql($sql);
			}
		}
	return $sError;
	}
// -- 1940 Propietarios XAJAX 
function f1940_Guardar($valores, $params){
	$_SESSION['u_ultimominuto']=iminutoavance();
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$sError='';
	$bDebug=false;
	$sDebug='';
	$bHayDb=false;
	$opts=$params;
	if(!is_array($opts)){$opts=json_decode(str_replace('\"','"',$opts),true);}
	if (isset($opts[99])!=0){if ($opts[99]==1){$bDebug=true;}}
	//if(!is_array($valores)){$datos=json_decode(str_replace('\"','"',$valores),true);}
	//if (isset($datos[0])==0){$datos[0]='';}
	//if ($datos[0]==''){$sError=$ERR[''];}
	if ($sError==''){
		require './app.php';
		$objdb=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objdb->dbPuerto=$APP->dbpuerto;}
		$objdb->xajax();
		$bHayDb=true;
		list($sError, $iAccion, $even40id, $sDebugGuardar)=f1940_db_Guardar($valores, $objdb, $bDebug);
		$sDebug=$sDebug.$sDebugGuardar;
		}
	$objResponse=new xajaxResponse();
	if ($sError==''){
		list($sdetalle, $sDebugTabla)=f1940_TablaDetalleV2($params, $objdb, $bDebug);
		$sDebug=$sDebug.$sDebugTabla;
		$objResponse->assign('div_f1940detalle', 'innerHTML', $sdetalle);
		//if ($iAccion==2){
			//$objResponse->call('cargaridf1940('.$even40id.')');
			//}else{
			$objResponse->call('limpiaf1940');
			//}
		$objResponse->call("MensajeAlarmaV2('".$ETI['msg_itemguardado']."', 1)");
		}else{
		$objResponse->call("MensajeAlarmaV2('".$sError."', 0)");
		}
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	if ($bHayDb){
		$objdb->CerrarConexion();
		}
	return $objResponse;
	}
function f1940_Traer($params){
	$_SESSION['u_ultimominuto']=iminutoavance();
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$sError='';
	$bHayDb=false;
	$besta=false;
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	$paso=$params[0];
	if ($paso==1){
		$even40idencuesta=numeros_validar($params[1]);
		$even40idpropietario=numeros_validar($params[2]);
		if (($even40idencuesta!='')&&($even40idpropietario!='')){$besta=true;}
		}else{
		$even40id=$params[103];
		if ((int)$even40id!=0){$besta=true;}
		}
	if ($besta){
		$besta=false;
		require './app.php';
		$objdb=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objdb->dbPuerto=$APP->dbpuerto;}
		$objdb->xajax();
		$bHayDb=true;
		$sqlcondi='';
		if ($paso==1){
			$sqlcondi=$sqlcondi.'even40idencuesta='.$even40idencuesta.' AND even40idpropietario='.$even40idpropietario.'';
			}else{
			$sqlcondi=$sqlcondi.'even40id='.$even40id.'';
			}
		$sql='SELECT * FROM even40encuestaprop WHERE '.$sqlcondi;
		$tabla=$objdb->ejecutasql($sql);
		if ($objdb->nf($tabla)>0){
			$fila=$objdb->sf($tabla);
			$besta=true;
			}
		}
	$objResponse=new xajaxResponse();
	if ($besta){
		$even40idpropietario_id=(int)$fila['even40idpropietario'];
		$even40idpropietario_td=$APP->tipo_doc;
		$even40idpropietario_doc='';
		$even40idpropietario_nombre='';
		if ($even40idpropietario_id!=0){
			list($even40idpropietario_nombre, $even40idpropietario_id, $even40idpropietario_td, $even40idpropietario_doc)=html_tercero($even40idpropietario_td, $even40idpropietario_doc, $even40idpropietario_id, 0, $objdb);
			}
		$html_even40idpropietario_llaves=html_DivTerceroV2('even40idpropietario', $even40idpropietario_td, $even40idpropietario_doc, true, 2, 'Ingrese el documento');
		$objResponse->assign('even40idpropietario', 'value', $even40idpropietario_id);
		$objResponse->assign('div_even40idpropietario_llaves', 'innerHTML', $html_even40idpropietario_llaves);
		$objResponse->assign('div_even40idpropietario', 'innerHTML', $even40idpropietario_nombre);
		$even40id_nombre='';
		$html_even40id=html_oculto('even40id', $fila['even40id'], $even40id_nombre);
		$objResponse->assign('div_even40id', 'innerHTML', $html_even40id);
		$objResponse->assign('even40activo', 'value', $fila['even40activo']);
		$objResponse->call("MensajeAlarmaV2('', 0)");
		$objResponse->call("verboton('belimina1940','block')");
		}else{
		if ($paso==1){
			}else{
			$objResponse->call('MensajeAlarmaV2("No se encontro el registro de referencia:'.$even40id.'", 0)');
			}
		}
	if ($bHayDb){
		$objdb->CerrarConexion();
		}
	return $objResponse;
	}
function f1940_Eliminar($params){
	$_SESSION['u_ultimominuto']=iminutoavance();
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$sError='';
	$iTipoError=0;
	$bDebug=false;
	$sDebug='';
	$opts=$params;
	if(!is_array($opts)){$opts=json_decode(str_replace('\"','"',$opts),true);}
	if (isset($opts[99])!=0){if ($opts[99]==1){$bDebug=true;}}
	//if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	require './app.php';
	$objdb=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objdb->dbPuerto=$APP->dbpuerto;}
	$objdb->xajax();
	list($sError, $sDebugElimina)=f1940_db_Eliminar($params, $objdb, $bDebug);
	$sDebug=$sDebug.$sDebugElimina;
	$objResponse=new xajaxResponse();
	if ($sError==''){
		list($sDetalle, $sDebugTabla)=f1940_TablaDetalleV2($params, $objdb, $bDebug);
		$sDebug=$sDebug.$sDebugTabla;
		$objResponse->assign('div_f1940detalle', 'innerHTML', $sDetalle);
		$objResponse->call('limpiaf1940');
		$sError=$ETI['msg_itemeliminado'];
		$iTipoError=1;
		}
	$objResponse->call("MensajeAlarmaV2('".$sError."', ".$iTipoError.")");
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	$objdb->CerrarConexion();
	return $objResponse;
	}
function f1940_HtmlTabla($params){
	$_SESSION['u_ultimominuto']=iminutoavance();
	$sError='';
	$bDebug=false;
	$sDebug='';
	$opts=$params;
	if(!is_array($opts)){$opts=json_decode(str_replace('\"','"',$opts),true);}
	if (isset($opts[99])!=0){if ($opts[99]==1){$bDebug=true;}}
	require './app.php';
	$objdb=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objdb->dbPuerto=$APP->dbpuerto;}
	$objdb->xajax();
	list($sDetalle, $sDebugTabla)=f1940_TablaDetalleV2($params, $objdb, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objdb->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f1940detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f1940_PintarLlaves($params){
	$_SESSION['u_ultimominuto']=iminutoavance();
	//if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$even40idpropietario=0;
	$even40idpropietario_rs='';
	$html_even40idpropietario_llaves=html_DivTerceroV2('even40idpropietario', $APP->tipo_doc, '', false, 2, $ETI['ing_doc']);
	$html_even40id='<input id="even40id" name="even40id" type="hidden" value=""/>';
	$objResponse=new xajaxResponse();
	$objResponse->assign('even40idpropietario','value', $even40idpropietario);
	$objResponse->assign('div_even40idpropietario_llaves','innerHTML', $html_even40idpropietario_llaves);
	$objResponse->assign('div_even40idpropietario','innerHTML', $even40idpropietario_rs);
	$objResponse->assign('div_even40id','innerHTML', $html_even40id);
	return $objResponse;
	}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------
?>