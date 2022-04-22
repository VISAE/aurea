<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2016 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.12.5 miércoles, 16 de marzo de 2016
--- 1929 Opciones de respuesta
*/
function f1929_db_Guardar($valores, $objdb){
	$icodmodulo=1929;
	$bAudita[2]=false;
	$bAudita[3]=false;
	require 'app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_1929='lg/lg_1929_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1929)){$mensajes_1929='lg/lg_1929_es.php';}
	require $mensajes_todas;
	require $mensajes_1929;
	$sError='';
	$binserta=false;
	if(!is_array($valores)){$valores=json_decode(str_replace('\"','"',$valores),true);}
	$even29idpregunta=numeros_validar($valores[1]);
	$even29consec=numeros_validar($valores[2]);
	$even29id=numeros_validar($valores[3], true);
	$even29etiqueta=htmlspecialchars(trim($valores[4]));
	$even29detalle=htmlspecialchars(trim($valores[5]));
	if ($even29detalle==''){$sError=$ERR['even29detalle'];}
	if ($even29etiqueta==''){$sError=$ERR['even29etiqueta'];}
	//if ($even29id==''){$sError=$ERR['even29id'];}//CONSECUTIVO
	//if ($even29consec==''){$sError=$ERR['even29consec'];}//CONSECUTIVO
	if ($even29idpregunta==''){$sError=$ERR['even29idpregunta'];}
	if ($sError==''){
		if ((int)$even29id==0){
			if ((int)$even29consec==0){
				$even29consec=tabla_consecutivo('even29encpregresp', 'even29consec', 'even29idpregunta='.$even29idpregunta.'', $objdb);
				if ($even29consec==-1){$sError=$objdb->serror;}
				}else{
				if (!seg_revisa_permiso($icodmodulo, 8, $objdb)){$sError=$ERR['8'];}
				}
			if ($sError==''){
				$sql='SELECT even29idpregunta FROM even29encpregresp WHERE even29idpregunta='.$even29idpregunta.' AND even29consec='.$even29consec.'';
				$result=$objdb->ejecutasql($sql);
				if ($objdb->nf($result)!=0){
					$sError=$ERR['existe'];
					}else{
					if (!seg_revisa_permiso($icodmodulo, 2, $objdb)){$sError=$ERR['2'];}
					}
				}
			if ($sError==''){
				$even29id=tabla_consecutivo('even29encpregresp', 'even29id', '', $objdb);
				if ($even29id==-1){$sError=$objdb->serror;}
				$binserta=true;
				}
			}else{
			if (!seg_revisa_permiso($icodmodulo, 3, $objdb)){$sError=$ERR['3'];}
			}
		}
	if ($sError==''){
		//Si el campo even29etiqueta permite html quite la linea htmlspecialchars para el campo y habilite la siguiente linea:
		//$even29etiqueta=str_replace('"', '\"', $even29etiqueta);
		$even29etiqueta=str_replace('&quot;', '\"', $even29etiqueta);
		//Si el campo even29detalle permite html quite la linea htmlspecialchars para el campo y habilite la siguiente linea:
		//$even29detalle=str_replace('"', '\"', $even29detalle);
		$even29detalle=str_replace('&quot;', '\"', $even29detalle);
		if ($binserta){
			$scampos='even29idpregunta, even29consec, even29id, even29etiqueta, even29detalle';
			$svalores=''.$even29idpregunta.', '.$even29consec.', '.$even29id.', "'.$even29etiqueta.'", "'.$even29detalle.'"';
			if ($APP->utf8==1){
				$sql='INSERT INTO even29encpregresp ('.$scampos.') VALUES ('.utf8_encode($svalores).');';
				}else{
				$sql='INSERT INTO even29encpregresp ('.$scampos.') VALUES ('.$svalores.');';
				}
			$result=$objdb->ejecutasql($sql);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' {Opciones de respuesta}.<!-- '.$sql.' -->';
				}else{
				if ($bAudita[2]){
					seg_auditar($icodmodulo, $_SESSION['unad_id_tercero'], 2, $even29id, $sql, $objdb);
					}
				}
			}else{
			$scampo1929[1]='even29etiqueta';
			$scampo1929[2]='even29detalle';
			$svr1929[1]=$even29etiqueta;
			$svr1929[2]=$even29detalle;
			$inumcampos=2;
			$sWhere='even29id='.$even29id.'';
			//$sWhere='even29idpregunta='.$even29idpregunta.' AND even29consec='.$even29consec.'';
			$sql='SELECT * FROM even29encpregresp WHERE '.$sWhere;
			$sdatos='';
			$bpasa=false;
			$result=$objdb->ejecutasql($sql);
			if ($objdb->nf($result)>0){
				$filaorigen=$objdb->sf($result);
				for ($k=1;$k<=$inumcampos;$k++){
					if ($filaorigen[$scampo1929[$k]]!=$svr1929[$k]){
						if ($sdatos!=''){$sdatos=$sdatos.', ';}
						$sdatos=$sdatos.$scampo1929[$k].'="'.$svr1929[$k].'"';
						$bpasa=true;
						}
					}
				}
			if ($bpasa){
				if ($APP->utf8==1){
					$sql='UPDATE even29encpregresp SET '.utf8_encode($sdatos).' WHERE '.$sWhere.';';
					}else{
					$sql='UPDATE even29encpregresp SET '.$sdatos.' WHERE '.$sWhere.';';
					}
				$result=$objdb->ejecutasql($sql);
				if ($result==false){
					$sError=$ERR['falla_guardar'].' {Opciones de respuesta}. <!-- '.$sql.' -->';
					}else{
					if ($bAudita[3]){
						seg_auditar($icodmodulo, $_SESSION['unad_id_tercero'], 3, $even29id, $sql, $objdb);
						}
					}
				}
			}
		}
	return array($sError);
	}
function f1929_db_Eliminar($params, $objdb){
	$icodmodulo=1929;
	$bAudita[4]=false;
	require 'app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_1929='lg/lg_1929_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1929)){$mensajes_1929='lg/lg_1929_es.php';}
	require $mensajes_todas;
	require $mensajes_1929;
	$sError='';
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	$even29idpregunta=numeros_validar($params[1]);
	$even29consec=numeros_validar($params[2]);
	$even29id=numeros_validar($params[3]);
/*	if (!comprobacion){
		$sError='No se puede eliminar';//EXPLICAR LA RAZON
		}*/
	if ($sError==''){
		if (!seg_revisa_permiso($icodmodulo, 4, $objdb)){
			$sError=$ERR['4'];
			}
		}
	if ($sError==''){
		//acciones previas
		$sWhere='even29id='.$even29id.'';
		//$sWhere='even29idpregunta='.$even29idpregunta.' AND even29consec='.$even29consec.'';
		$sql='DELETE FROM even29encpregresp WHERE '.$sWhere.';';
		$result=$objdb->ejecutasql($sql);
		if ($result==false){
			$sError=$ERR['falla_eliminar'].' {1929 Opciones de respuesta}.<!-- '.$sql.' -->';
			}else{
			if ($bAudita[4]){
				seg_auditar($icodmodulo, $_SESSION['unad_id_tercero'], 4, $even29id, $sql, $objdb);
				}
			}
		}
	return $sError;
	}
function f1929_TablaDetalle($params, $objdb){
	require 'app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_1929='lg/lg_1929_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1929)){$mensajes_1929='lg/lg_1929_es.php';}
	require $mensajes_todas;
	require $mensajes_1929;
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	if (isset($params[0])==0){$params[0]=-1;}
	if (isset($params[101])==0){$params[101]=1;}
	if (isset($params[102])==0){$params[102]=20;}
	//if (isset($params[103])==0){$params[103]='';}
	//$params[103]=numeros_validar($params[103]);
	$params[0]=numeros_validar($params[0]);
	if ($params[0]==''){$params[0]=-1;}
	$even18id=$params[0];
	$pagina=$params[101];
	$lineastabla=$params[102];
	$babierta=true;
	//$sql='SELECT Campo FROM even18encuestapregunta WHERE even18id='.$even18id;
	//$tabla=$objdb->ejecutasql($sql);
	//if ($objdb->nf($tabla)>0){
		//$fila=$objdb->sf($tabla);
		//if ($fila['Campo']!='S'){$babierta=true;}
		//}
	$sqladd='';
	//if (isset($params[103])==0){$params[103]='';}
	//if ((int)$params[103]!=-1){$sqladd=$sqladd.' AND TB.campo='.$params[103];}
	//if ($params[103]!=''){$sqladd=$sqladd.' AND TB.campo2 LIKE "%'.$params[103].'%"';}
	$sTitulos='Pregunta, Consec, Id, Etiqueta, Detalle';
	$sql='SELECT TB.even29idpregunta, TB.even29consec, TB.even29id, TB.even29etiqueta, TB.even29detalle 
FROM even29encpregresp AS TB 
WHERE TB.even29idpregunta='.$even18id.' '.$sqladd.'
ORDER BY TB.even29consec';
	$sqllista=str_replace("'","|",$sql);
	$sqllista=str_replace('"',"|",$sqllista);
	$sErrConsulta='<input id="consulta_1929" name="consulta_1929" type="hidden" value="'.$sqllista.'"/>
<input id="titulos_1929" name="titulos_1929" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objdb->ejecutasql($sql);
	if ($tabladetalle==false){
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sql.' '.$objdb->serror.'"/>';
		}else{
		$registros=$objdb->nf($tabladetalle);
		if ($registros==0){
			return utf8_encode($sErrConsulta.'<input id="paginaf1929" name="paginaf1929" type="hidden" value="'.$pagina.'"/><input id="lppf1929" name="lppf1929" type="hidden" value="'.$lineastabla.'"/>');
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
<td><b>'.$ETI['even29consec'].'</b></td>
<td><b>'.$ETI['even29etiqueta'].'</b></td>
<td><b>'.$ETI['even29detalle'].'</b></td>
<td align="right">
'.html_paginador('paginaf1929', $registros, $lineastabla, $pagina, 'paginarf1929()').'
'.html_lpp('lppf1929', $lineastabla, 'paginarf1929()').'
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
		$et_even29consec=$sPrefijo.$filadet['even29consec'].$sSufijo;
		$et_even29etiqueta=$sPrefijo.cadena_notildes($filadet['even29etiqueta']).$sSufijo;
		$et_even29detalle=$sPrefijo.cadena_notildes($filadet['even29detalle']).$sSufijo;
		if ($babierta){
			$sLink='<a href="javascript:cargaridf1929('."'".$filadet['even29id']."'".')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$res=$res.'<tr'.$sClass.'>
<td>'.$et_even29consec.'</td>
<td>'.$et_even29etiqueta.'</td>
<td>'.$et_even29detalle.'</td>
<td>'.$sLink.'</td>
</tr>';
		}
	$res=$res.'</table>';
	return utf8_encode($res);
	}
function f1929_Clonar($even29idpregunta, $even29idpreguntaPadre, $objdb){
	$sError='';
	$even29consec=tabla_consecutivo('even29encpregresp', 'even29consec', 'even29idpregunta='.$even29idpregunta.'', $objdb);
	if ($even29consec==-1){$sError=$objdb->serror;}
	$even29id=tabla_consecutivo('even29encpregresp', 'even29id', '', $objdb);
	if ($even29id==-1){$sError=$objdb->serror;}
	if ($sError==''){
		$sCampos1929='even29idpregunta, even29consec, even29id, even29etiqueta, even29detalle';
		$sValores1929='';
		$sql='SELECT * FROM even29encpregresp WHERE even29idpregunta='.$even29idpreguntaPadre.'';
		$tabla=$objdb->ejecutasql($sql);
		while($fila=$objdb->sf($tabla)){
			if ($sValores1929!=''){$sValores1929=$sValores1929.', ';}
			$sValores1929=$sValores1929.'('.$even29idpregunta.', '.$even29consec.', '.$even29id.', "'.$fila['even29etiqueta'].'", "'.$fila['even29detalle'].'")';
			$even29consec++;
			$even29id++;
			}
		if ($sValores1929!=''){
			$sql='INSERT INTO even29encpregresp('.$sCampos1929.') VALUES '.$sValores1929.'';
			$result=$objdb->ejecutasql($sql);
			}
		}
	return $sError;
	}
// -- 1929 Opciones de respuesta XAJAX 
function f1929_Guardar($valores, $params){
	$_SESSION['u_ultimominuto']=iminutoavance();
	$sError='';
	//if(!is_array($valores)){$datos=json_decode(str_replace('\"','"',$valores),true);}
	//if (isset($datos[0])==0){$datos[0]='';}
	//if ($datos[0]==''){$sError=$ERR[''];}
	if ($sError==''){
		require 'app.php';
		$objdb=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objdb->dbPuerto=$APP->dbpuerto;}
		$objdb->xajax();
		list($sError)=f1929_db_Guardar($valores, $objdb);
		}
	$objResponse=new xajaxResponse();
	if ($sError==''){
		$sdetalle=f1929_TablaDetalle($params, $objdb);
		$objResponse->assign('div_f1929detalle', 'innerHTML', $sdetalle);
		$objResponse->call('limpiaf1929');
		$objResponse->assign('alarma', 'innerHTML', 'item guardado');
		}else{
		$objResponse->assign('alarma', 'innerHTML', $sError);
		}
	return $objResponse;
	}
function f1929_Traer($params){
	$_SESSION['u_ultimominuto']=iminutoavance();
	$sError='';
	$besta=false;
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	$paso=$params[0];
	if ($paso==1){
		$even29idpregunta=numeros_validar($params[1]);
		$even29consec=numeros_validar($params[2]);
		if (($even29idpregunta!='')&&($even29consec!='')){$besta=true;}
		}else{
		$even29id=$params[103];
		if ((int)$even29id!=0){$besta=true;}
		}
	if ($besta){
		$besta=false;
		require 'app.php';
		$objdb=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objdb->dbPuerto=$APP->dbpuerto;}
		$objdb->xajax();
		$sqlcondi='';
		if ($paso==1){
			$sqlcondi=$sqlcondi.'even29idpregunta='.$even29idpregunta.' AND even29consec='.$even29consec.'';
			}else{
			$sqlcondi=$sqlcondi.'even29id='.$even29id.'';
			}
		$sql='SELECT * FROM even29encpregresp WHERE '.$sqlcondi;
		$tabla=$objdb->ejecutasql($sql);
		if ($objdb->nf($tabla)>0){
			$fila=$objdb->sf($tabla);
			$besta=true;
			}
		}
	$objResponse=new xajaxResponse();
	if ($besta){
		$even29consec_nombre='';
		$html_even29consec=html_oculto('even29consec', $fila['even29consec'], $even29consec_nombre);
		$objResponse->assign('div_even29consec', 'innerHTML', $html_even29consec);
		$even29id_nombre='';
		$html_even29id=html_oculto('even29id', $fila['even29id'], $even29id_nombre);
		$objResponse->assign('div_even29id', 'innerHTML', $html_even29id);
		$objResponse->assign('even29etiqueta', 'value', $fila['even29etiqueta']);
		$objResponse->assign('even29detalle', 'value', $fila['even29detalle']);
		$objResponse->assign('alarma', 'innerHTML', '');
		$objResponse->call("verboton('belimina1929','block')");
		}else{
		if ($paso==1){
			$objResponse->assign('even29consec', 'value', $even29consec);
			}else{
			$objResponse->assign('alarma', 'innerHTML', 'No se encontro el registro de referencia:'.$even29id);
			}
		}
	return $objResponse;
	}
function f1929_Eliminar($params){
	$_SESSION['u_ultimominuto']=iminutoavance();
	$sError='';
	//if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	require 'app.php';
	$objdb=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objdb->dbPuerto=$APP->dbpuerto;}
	$objdb->xajax();
	$sError=f1929_db_Eliminar($params, $objdb);
	$objResponse=new xajaxResponse();
	if ($sError==''){
		$sDetalle=f1929_TablaDetalle($params, $objdb);
		$objResponse->assign('div_f1929detalle', 'innerHTML', $sDetalle);
		$objResponse->call('limpiaf1929');
		$sError='Item eliminado';
		}
	$objResponse->assign('alarma', 'innerHTML', $sError);
	return $objResponse;
	}
function f1929_HtmlTabla($params){
	$_SESSION['u_ultimominuto']=iminutoavance();
	$sError='';
	require 'app.php';
	$objdb=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objdb->dbPuerto=$APP->dbpuerto;}
	$objdb->xajax();
	$sDetalle=f1929_TablaDetalle($params, $objdb);
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f1929detalle', 'innerHTML', $sDetalle);
	return $objResponse;
	}
function f1929_PintarLlaves(){
	$_SESSION['u_ultimominuto']=iminutoavance();
	require 'app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$html_even29consec='<input id="even29consec" name="even29consec" type="text" value="" onchange="revisaf1929()" class="cuatro"/>';
	$html_even29id='<input id="even29id" name="even29id" type="hidden" value=""/>';
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_even29consec','innerHTML', $html_even29consec);
	$objResponse->assign('div_even29id','innerHTML', $html_even29id);
	return $objResponse;
	}
function f1929_GuardaEtiqueta($params){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	$sError='';
	require 'app.php';
	$objdb=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objdb->dbPuerto=$APP->dbpuerto;}
	$objdb->xajax();
	$id29=numeros_validar($params[1]);
	$etiqueta=htmlspecialchars(trim($params[2]));
	if ($id29==''){$id29=0;}
	if ($id29>0){
		$sql='UPDATE even29encpregresp SET even29etiqueta="'.$etiqueta.'" WHERE even29id='.$id29;
		$tabla=$objdb->ejecutasql($sql);
		}
	}
function f1929_GuardaDetalle($params){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	$sError='';
	require 'app.php';
	$objdb=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objdb->dbPuerto=$APP->dbpuerto;}
	$objdb->xajax();
	$id29=numeros_validar($params[1]);
	$etiqueta=htmlspecialchars(trim($params[2]));
	if ($id29==''){$id29=0;}
	if ($id29>0){
		$sql='UPDATE even29encpregresp SET even29detalle="'.$etiqueta.'" WHERE even29id='.$id29;
		$tabla=$objdb->ejecutasql($sql);
		}
	}
?>