<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2018 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.22.1 jueves, 5 de julio de 2018
--- 2309 Respuestas
*/
function f2309_db_Guardar($valores, $objdb, $bDebug=false){
	$icodmodulo=2309;
	$bAudita[2]=false;
	$bAudita[3]=false;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2309='lg/lg_2309_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2309)){$mensajes_2309='lg/lg_2309_es.php';}
	require $mensajes_todas;
	require $mensajes_2309;
	$sError='';
	$sDebug='';
	$binserta=false;
	$iAccion=3;
	if(!is_array($valores)){$valores=json_decode(str_replace('\"','"',$valores),true);}
	$cara09idpregunta=numeros_validar($valores[1]);
	$cara09consec=numeros_validar($valores[2]);
	$cara09id=numeros_validar($valores[3], true);
	$cara09valor=numeros_validar($valores[4]);
	$cara09contenido=trim($valores[5]);
	//$cara09contenido=htmlspecialchars(trim($valores[5]));
	if ($cara09valor==''){$cara09valor=0;}
	$sSepara=', ';
	if ($cara09contenido==''){$sError=$ERR['cara09contenido'].$sSepara.$sError;}
	if ($cara09valor==''){
		//$sError=$ERR['cara09valor'].$sSepara.$sError;
		}else{
		}
	if ($cara09valor>10){
		$sError=$ERR['cara09valor_mas'].$sSepara.$sError;
		}else{
		if ($cara09valor<0){
			$sError=$ERR['cara09valor_menos'].$sSepara.$sError;
			}
		}
	//if ($cara09id==''){$sError=$ERR['cara09id'].$sSepara.$sError;}//CONSECUTIVO
	//if ($cara09consec==''){$sError=$ERR['cara09consec'].$sSepara.$sError;}//CONSECUTIVO
	if ($cara09idpregunta==''){$sError=$ERR['cara09idpregunta'].$sSepara.$sError;}
	if ($sError==''){
		if ((int)$cara09id==0){
			if ((int)$cara09consec==0){
				$cara09consec=tabla_consecutivo('cara09pregrpta', 'cara09consec', 'cara09idpregunta='.$cara09idpregunta.'', $objdb);
				if ($cara09consec==-1){$sError=$objdb->serror;}
				}else{
				if (!seg_revisa_permiso($icodmodulo, 8, $objdb)){$sError=$ERR['8'];}
				}
			if ($sError==''){
				$sql='SELECT cara09idpregunta FROM cara09pregrpta WHERE cara09idpregunta='.$cara09idpregunta.' AND cara09consec='.$cara09consec.'';
				$result=$objdb->ejecutasql($sql);
				if ($objdb->nf($result)!=0){
					$sError=$ERR['existe'];
					}else{
					if (!seg_revisa_permiso($icodmodulo, 2, $objdb)){$sError=$ERR['2'];}
					}
				}
			if ($sError==''){
				$cara09id=tabla_consecutivo('cara09pregrpta', 'cara09id', '', $objdb);
				if ($cara09id==-1){$sError=$objdb->serror;}
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
		//Si el campo cara09contenido permite html quite la linea htmlspecialchars para el campo y habilite la siguiente linea:
		//$cara09contenido=str_replace('"', '\"', $cara09contenido);
		$cara09contenido=str_replace('"', '\"', $cara09contenido);
		if ($binserta){
			$scampos='cara09idpregunta, cara09consec, cara09id, cara09valor, cara09contenido';
			$svalores=''.$cara09idpregunta.', '.$cara09consec.', '.$cara09id.', '.$cara09valor.', "'.$cara09contenido.'"';
			if ($APP->utf8==1){
				$sql='INSERT INTO cara09pregrpta ('.$scampos.') VALUES ('.utf8_encode($svalores).');';
				}else{
				$sql='INSERT INTO cara09pregrpta ('.$scampos.') VALUES ('.$svalores.');';
				}
			$result=$objdb->ejecutasql($sql);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' {Respuestas}.<!-- '.$sql.' -->';
				}else{
				if ($bAudita[2]){
					seg_auditar($icodmodulo, $_SESSION['unad_id_tercero'], 2, $cara09id, $sql, $objdb);
					}
				}
			}else{
			$scampo2309[1]='cara09valor';
			$scampo2309[2]='cara09contenido';
			$svr2309[1]=$cara09valor;
			$svr2309[2]=$cara09contenido;
			$inumcampos=2;
			$sWhere='cara09id='.$cara09id.'';
			//$sWhere='cara09idpregunta='.$cara09idpregunta.' AND cara09consec='.$cara09consec.'';
			$sql='SELECT * FROM cara09pregrpta WHERE '.$sWhere;
			$sdatos='';
			$bpasa=false;
			$result=$objdb->ejecutasql($sql);
			if ($objdb->nf($result)>0){
				$filaorigen=$objdb->sf($result);
				for ($k=1;$k<=$inumcampos;$k++){
					if ($filaorigen[$scampo2309[$k]]!=$svr2309[$k]){
						if ($sdatos!=''){$sdatos=$sdatos.', ';}
						$sdatos=$sdatos.$scampo2309[$k].'="'.$svr2309[$k].'"';
						$bpasa=true;
						}
					}
				}
			if ($bpasa){
				if ($APP->utf8==1){
					$sql='UPDATE cara09pregrpta SET '.utf8_encode($sdatos).' WHERE '.$sWhere.';';
					}else{
					$sql='UPDATE cara09pregrpta SET '.$sdatos.' WHERE '.$sWhere.';';
					}
				$result=$objdb->ejecutasql($sql);
				if ($result==false){
					$sError=$ERR['falla_guardar'].' {Respuestas}. <!-- '.$sql.' -->';
					}else{
					if ($bAudita[3]){
						seg_auditar($icodmodulo, $_SESSION['unad_id_tercero'], 3, $cara09id, $sql, $objdb);
						}
					}
				}
			}
		}
	return array($sError, $iAccion, $cara09id, $sDebug);
	}
function f2309_db_Eliminar($params, $objdb, $bDebug=false){
	$icodmodulo=2309;
	$bAudita[4]=false;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2309='lg/lg_2309_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2309)){$mensajes_2309='lg/lg_2309_es.php';}
	require $mensajes_todas;
	require $mensajes_2309;
	$sError='';
	$sDebug='';
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	$cara09idpregunta=numeros_validar($params[1]);
	$cara09consec=numeros_validar($params[2]);
	$cara09id=numeros_validar($params[3]);
	if ($sError==''){
		if (!seg_revisa_permiso($icodmodulo, 4, $objdb)){
			$sError=$ERR['4'];
			}
		}
	if ($sError==''){
		$sql='SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=2309';
		$tablaor=$objdb->ejecutasql($sql);
		while ($filaor=$objdb->sf($tablaor)){
			$sql='SELECT '.$filaor['campoid'].' FROM '.$filaor['tabla'].' WHERE '.$filaor['camporev'].'='.$cara09id.' LIMIT 0, 1';
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
		$sWhere='cara09id='.$cara09id.'';
		//$sWhere='cara09idpregunta='.$cara09idpregunta.' AND cara09consec='.$cara09consec.'';
		$sql='DELETE FROM cara09pregrpta WHERE '.$sWhere.';';
		$result=$objdb->ejecutasql($sql);
		if ($result==false){
			$sError=$ERR['falla_eliminar'].' {2309 Respuestas}.<!-- '.$sql.' -->';
			}else{
			if ($bAudita[4]){
				seg_auditar($icodmodulo, $_SESSION['unad_id_tercero'], 4, $cara09id, $sql, $objdb);
				}
			}
		}
	return array($sError, $sDebug);
	}
function f2309_TablaDetalleV2($params, $objdb, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2309='lg/lg_2309_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2309)){$mensajes_2309='lg/lg_2309_es.php';}
	require $mensajes_todas;
	require $mensajes_2309;
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	if (isset($params[0])==0){$params[0]=-1;}
	if (isset($params[101])==0){$params[101]=1;}
	if (isset($params[102])==0){$params[102]=20;}
	//if (isset($params[103])==0){$params[103]='';}
	//$params[103]=numeros_validar($params[103]);
	$params[0]=numeros_validar($params[0]);
	if ($params[0]==''){$params[0]=-1;}
	$sDebug='';
	$cara08id=$params[0];
	$pagina=$params[101];
	$lineastabla=$params[102];
	$babierta=true;
	//$sql='SELECT Campo FROM cara08pregunta WHERE cara08id='.$cara08id;
	//$tabla=$objdb->ejecutasql($sql);
	//if ($objdb->nf($tabla)>0){
		//$fila=$objdb->sf($tabla);
		//if ($fila['Campo']!='S'){$babierta=true;}
		//}
	$sqladd='';
	$sqladd1='';
	$sLeyenda='';
	if (false){
		$sLeyenda='<div class="salto1px"></div>
<div class="GrupoCamposAyuda">
<b>Importante:</b> Mensaje al usuario
<div class="salto1px"></div>
</div>';
		}
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
	$sTitulos='Pregunta, Consec, Id, Valor, Contenido';
	$sql='SELECT TB.cara09idpregunta, TB.cara09consec, TB.cara09id, TB.cara09valor, TB.cara09contenido 
FROM cara09pregrpta AS TB 
WHERE '.$sqladd1.' TB.cara09idpregunta='.$cara08id.' '.$sqladd.'
ORDER BY TB.cara09consec';
	$sqllista=str_replace("'","|",$sql);
	$sqllista=str_replace('"',"|",$sqllista);
	$sErrConsulta='<input id="consulta_2309" name="consulta_2309" type="hidden" value="'.$sqllista.'"/>
<input id="titulos_2309" name="titulos_2309" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objdb->ejecutasql($sql);
	if ($tabladetalle==false){
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 2309: '.$sql.'<br>';}
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sql.' '.$objdb->serror.'"/>';
		//$sLeyenda=$sql;
		}else{
		$registros=$objdb->nf($tabladetalle);
		if ($registros==0){
			return array(utf8_encode($sErrConsulta.'<input id="paginaf2309" name="paginaf2309" type="hidden" value="'.$pagina.'"/><input id="lppf2309" name="lppf2309" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
			}
		if ((($registros-1)/$lineastabla)<($pagina-1)){$pagina=(int)(($registros-1)/$lineastabla)+1;}
		if ($registros>$lineastabla){
			$rbase=($pagina-1)*$lineastabla;
			$limite=' LIMIT '.$rbase.', '.$lineastabla;
			$tabladetalle=$objdb->ejecutasql($sql.$limite);
			}
		}
	$res=$sErrConsulta.$sLeyenda.'<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">
<tr class="fondoazul">
<td><b>'.$ETI['cara09consec'].'</b></td>
<td><b>'.$ETI['cara09contenido'].'</b></td>
<td><b>'.$ETI['cara09valor'].'</b></td>
<td align="right">
'.html_paginador('paginaf2309', $registros, $lineastabla, $pagina, 'paginarf2309()').'
'.html_lpp('lppf2309', $lineastabla, 'paginarf2309()').'
</td>
</tr>';
	$tlinea=1;
	while($filadet=$objdb->sf($tabladetalle)){
		$sPrefijo='';
		$sSufijo='';
		$sClass='';
		$sLink='';
		if ($filadet['cara09valor']!=0){
			$sPrefijo='<b>';
			$sSufijo='</b>';
			}
		if(($tlinea%2)==0){$sClass=' class="resaltetabla"';}
		$tlinea++;
		$et_cara09consec=$sPrefijo.$filadet['cara09consec'].$sSufijo;
		$et_cara09valor=$sPrefijo.$filadet['cara09valor'].$sSufijo;
		$et_cara09contenido=$sPrefijo.cadena_notildes($filadet['cara09contenido']).$sSufijo;
		if ($babierta){
			$sLink='<a href="javascript:cargaridf2309('.$filadet['cara09id'].')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$res=$res.'<tr'.$sClass.'>
<td>'.$et_cara09consec.'</td>
<td>'.$et_cara09contenido.'</td>
<td>'.$et_cara09valor.'</td>
<td>'.$sLink.'</td>
</tr>';
		}
	$res=$res.'</table>';
	$objdb->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
	}
function f2309_Clonar($cara09idpregunta, $cara09idpreguntaPadre, $objdb){
	$sError='';
	$cara09consec=tabla_consecutivo('cara09pregrpta', 'cara09consec', 'cara09idpregunta='.$cara09idpregunta.'', $objdb);
	if ($cara09consec==-1){$sError=$objdb->serror;}
	$cara09id=tabla_consecutivo('cara09pregrpta', 'cara09id', '', $objdb);
	if ($cara09id==-1){$sError=$objdb->serror;}
	if ($sError==''){
		$sCampos2309='cara09idpregunta, cara09consec, cara09id, cara09valor, cara09contenido';
		$sValores2309='';
		$sql='SELECT * FROM cara09pregrpta WHERE cara09idpregunta='.$cara09idpreguntaPadre.'';
		$tabla=$objdb->ejecutasql($sql);
		while($fila=$objdb->sf($tabla)){
			if ($sValores2309!=''){$sValores2309=$sValores2309.', ';}
			$sValores2309=$sValores2309.'('.$cara09idpregunta.', '.$cara09consec.', '.$cara09id.', '.$fila['cara09valor'].', "'.$fila['cara09contenido'].'")';
			$cara09consec++;
			$cara09id++;
			}
		if ($sValores2309!=''){
			$sql='INSERT INTO cara09pregrpta('.$sCampos2309.') VALUES '.$sValores2309.'';
			$result=$objdb->ejecutasql($sql);
			}
		}
	return $sError;
	}
// -- 2309 Respuestas XAJAX 
function f2309_Guardar($valores, $params){
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
		$objdb=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objdb->dbPuerto=$APP->dbpuerto;}
		$objdb->xajax();
		$bHayDb=true;
		list($sError, $iAccion, $cara09id, $sDebugGuardar)=f2309_db_Guardar($valores, $objdb, $bDebug);
		$sDebug=$sDebug.$sDebugGuardar;
		}
	$objResponse=new xajaxResponse();
	if ($sError==''){
		list($sdetalle, $sDebugTabla)=f2309_TablaDetalleV2($params, $objdb, $bDebug);
		$sDebug=$sDebug.$sDebugTabla;
		$objResponse->assign('div_f2309detalle', 'innerHTML', $sdetalle);
		//if ($iAccion==2){
			//$objResponse->call('cargaridf2309('.$cara09id.')');
			//}else{
			$objResponse->call('limpiaf2309');
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
function f2309_Traer($params){
	$_SESSION['u_ultimominuto']=iminutoavance();
	$sError='';
	$bHayDb=false;
	$besta=false;
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	$paso=$params[0];
	if ($paso==1){
		$cara09idpregunta=numeros_validar($params[1]);
		$cara09consec=numeros_validar($params[2]);
		if (($cara09idpregunta!='')&&($cara09consec!='')){$besta=true;}
		}else{
		$cara09id=$params[103];
		if ((int)$cara09id!=0){$besta=true;}
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
			$sqlcondi=$sqlcondi.'cara09idpregunta='.$cara09idpregunta.' AND cara09consec='.$cara09consec.'';
			}else{
			$sqlcondi=$sqlcondi.'cara09id='.$cara09id.'';
			}
		$sql='SELECT * FROM cara09pregrpta WHERE '.$sqlcondi;
		$tabla=$objdb->ejecutasql($sql);
		if ($objdb->nf($tabla)>0){
			$fila=$objdb->sf($tabla);
			$besta=true;
			}
		}
	$objResponse=new xajaxResponse();
	if ($besta){
		if (isset($APP->piel)==0){$APP->piel=1;}
		$iPiel=$APP->piel;
		$cara09consec_nombre='';
		$html_cara09consec=html_oculto('cara09consec', $fila['cara09consec'], $cara09consec_nombre);
		$objResponse->assign('div_cara09consec', 'innerHTML', $html_cara09consec);
		$cara09id_nombre='';
		$html_cara09id=html_oculto('cara09id', $fila['cara09id'], $cara09id_nombre);
		$objResponse->assign('div_cara09id', 'innerHTML', $html_cara09id);
		$objResponse->assign('cara09valor', 'value', $fila['cara09valor']);
		$objResponse->assign('cara09contenido', 'value', $fila['cara09contenido']);
		$objResponse->call("MensajeAlarmaV2('', 0)");
		$objResponse->call("verboton('belimina2309','block')");
		}else{
		if ($paso==1){
			$objResponse->assign('cara09consec', 'value', $cara09consec);
			}else{
			$objResponse->call('MensajeAlarmaV2("No se encontro el registro de referencia:'.$cara09id.'", 0)');
			}
		}
	if ($bHayDb){
		$objdb->CerrarConexion();
		}
	return $objResponse;
	}
function f2309_Eliminar($params){
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
	$objdb=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objdb->dbPuerto=$APP->dbpuerto;}
	$objdb->xajax();
	list($sError, $sDebugElimina)=f2309_db_Eliminar($params, $objdb, $bDebug);
	$sDebug=$sDebug.$sDebugElimina;
	$objResponse=new xajaxResponse();
	if ($sError==''){
		list($sDetalle, $sDebugTabla)=f2309_TablaDetalleV2($params, $objdb, $bDebug);
		$sDebug=$sDebug.$sDebugTabla;
		$objResponse->assign('div_f2309detalle', 'innerHTML', $sDetalle);
		$objResponse->call('limpiaf2309');
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
function f2309_HtmlTabla($params){
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
	list($sDetalle, $sDebugTabla)=f2309_TablaDetalleV2($params, $objdb, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objdb->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f2309detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f2309_PintarLlaves($params){
	$_SESSION['u_ultimominuto']=iminutoavance();
	//if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	if (isset($APP->piel)==0){$APP->piel=1;}
	$iPiel=$APP->piel;
	$html_cara09consec='<input id="cara09consec" name="cara09consec" type="text" value="" onchange="revisaf2309()" class="cuatro"/>';
	$html_cara09id='<input id="cara09id" name="cara09id" type="hidden" value=""/>';
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_cara09consec','innerHTML', $html_cara09consec);
	$objResponse->assign('div_cara09id','innerHTML', $html_cara09id);
	return $objResponse;
	}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------
?>