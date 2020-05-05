<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2015 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.9.7 domingo, 29 de noviembre de 2015
--- 1922 Respuestas
*/
function html_combo_even22idpregunta($objdb, $valor){
	require 'app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$res=html_combo('even22idpregunta', '', '', '', '', '', $valor, $objdb, 'revisaf1922()', true, '{'.$ETI['msg_seleccione'].'}', '');
	return utf8_encode($res);
	}
function f1922_db_Guardar($valores, $objdb){
	$icodmodulo=1922;
	$bAudita[2]=false;
	$bAudita[3]=false;
	require 'app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_1922=$APP->rutacomun.'lg/lg_1922_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1922)){$mensajes_1922=$APP->rutacomun.'lg/lg_1922_es.php';}
	require $mensajes_todas;
	require $mensajes_1922;
	$sError='';
	$binserta=false;
	if(!is_array($valores)){$valores=json_decode(str_replace('\"','"',$valores),true);}
	$even22idaplica=numeros_validar($valores[1]);
	$even22idpregunta=numeros_validar($valores[2]);
	$even22id=numeros_validar($valores[3], true);
	$even22tiporespuesta=numeros_validar($valores[4]);
	$even22opcional=htmlspecialchars($valores[5]);
	$even22irespuesta=numeros_validar($valores[6]);
	$even22nota=htmlspecialchars($valores[7]);
	$even22relevante=htmlspecialchars($valores[8]);
	//if ($even22tiporespuesta==''){$even22tiporespuesta=0;}
	//if ($even22irespuesta==''){$even22irespuesta=0;}
	if ($even22relevante==''){$sError=$ERR['even22relevante'];}
	if ($even22nota==''){$sError=$ERR['even22nota'];}
	if ($even22irespuesta==''){$sError=$ERR['even22irespuesta'];}
	if ($even22opcional==''){$sError=$ERR['even22opcional'];}
	if ($even22tiporespuesta==''){$sError=$ERR['even22tiporespuesta'];}
	//if ($even22id==''){$sError=$ERR['even22id'];}//CONSECUTIVO
	if ($even22idpregunta==''){$sError=$ERR['even22idpregunta'];}
	if ($even22idaplica==''){$sError=$ERR['even22idaplica'];}
	if ($sError==''){
		if ((int)$even22id==0){
			if ($sError==''){
				$sql='SELECT even22idaplica FROM even22encuestarpta WHERE even22idaplica='.$even22idaplica.' AND even22idpregunta='.$even22idpregunta.'';
				$result=$objdb->ejecutasql($sql);
				if ($objdb->nf($result)!=0){
					$sError=$ERR['existe'];
					}else{
					if (!seg_revisa_permiso($icodmodulo, 2, $objdb)){$sError=$ERR['2'];}
					}
				}
			if ($sError==''){
				$even22id=tabla_consecutivo('even22encuestarpta', 'even22id', '', $objdb);
				if ($even22id==-1){$sError=$objdb->serror;}
				$binserta=true;
				}
			}else{
			if (!seg_revisa_permiso($icodmodulo, 3, $objdb)){$sError=$ERR['3'];}
			}
		}
	if ($sError==''){
		//Si el campo even22nota permite html quite la linea htmlspecialchars para el campo y habilite la siguiente linea:
		//$even22nota=str_replace('"', '\"', $even22nota);
		$even22nota=str_replace('&quot;', '\"', $even22nota);
		if ($binserta){
			$scampos='even22idaplica, even22idpregunta, even22id, even22tiporespuesta, even22opcional, even22irespuesta, even22nota, even22relevante';
			$svalores=''.$even22idaplica.', '.$even22idpregunta.', '.$even22id.', '.$even22tiporespuesta.', "'.$even22opcional.'", '.$even22irespuesta.', "'.$even22nota.'", "'.$even22relevante.'"';
			$sql='INSERT INTO even22encuestarpta ('.$scampos.') VALUES ('.$svalores.');';
			$result=$objdb->ejecutasql($sql);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' {Respuestas}.<!-- '.$sql.' -->';
				}else{
				if ($bAudita[2]){
					seg_auditar($icodmodulo, $_SESSION['unad_id_tercero'], 2, $even22id, $sql, $objdb);
					}
				}
			}else{
			$scampo1922[1]='even22tiporespuesta';
			$scampo1922[2]='even22opcional';
			$scampo1922[3]='even22irespuesta';
			$scampo1922[4]='even22nota';
			$scampo1922[5]='even22relevante';
			$svr1922[1]=$even22tiporespuesta;
			$svr1922[2]=$even22opcional;
			$svr1922[3]=$even22irespuesta;
			$svr1922[4]=$even22nota;
			$svr1922[5]=$even22relevante;
			$inumcampos=5;
			$sWhere='even22idaplica='.$even22idaplica.' AND even22idpregunta='.$even22idpregunta.'';
			$sql='SELECT * FROM even22encuestarpta WHERE '.$sWhere;
			$sdatos='';
			$bpasa=false;
			$result=$objdb->ejecutasql($sql);
			if ($objdb->nf($result)>0){
				$filaorigen=$objdb->sf($result);
				for ($k=1;$k<=$inumcampos;$k++){
					if ($filaorigen[$scampo1922[$k]]!=$svr1922[$k]){
						if ($sdatos!=''){$sdatos=$sdatos.', ';}
						$sdatos=$sdatos.$scampo1922[$k].'="'.$svr1922[$k].'"';
						$bpasa=true;
						}
					}
				}
			if ($bpasa){
				$sql='UPDATE even22encuestarpta SET '.$sdatos.' WHERE '.$sWhere.';';
				$result=$objdb->ejecutasql($sql);
				if ($result==false){
					$sError=$ERR['falla_guardar'].' {Respuestas}. <!-- '.$sql.' -->';
					}else{
					if ($bAudita[3]){
						seg_auditar($icodmodulo, $_SESSION['unad_id_tercero'], 3, $even22id, $sql, $objdb);
						}
					}
				}
			}
		}
	return array($sError);
	}
function f1922_db_Eliminar($params, $objdb){
	$icodmodulo=1922;
	$bAudita[4]=false;
	require 'app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_1922=$APP->rutacomun.'lg/lg_1922_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1922)){$mensajes_1922=$APP->rutacomun.'lg/lg_1922_es.php';}
	require $mensajes_todas;
	require $mensajes_1922;
	$sError='';
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	$even22idaplica=numeros_validar($params[1]);
	$even22idpregunta=numeros_validar($params[2]);
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
		$sWhere='even22idaplica='.$even22idaplica.' AND even22idpregunta='.$even22idpregunta.'';
		$sql='DELETE FROM even22encuestarpta WHERE '.$sWhere.';';
		$result=$objdb->ejecutasql($sql);
		if ($result==false){
			$sError=$ERR['falla_eliminar'].' {1922 Respuestas}.<!-- '.$sql.' -->';
			}else{
			if ($bAudita[4]){
				seg_auditar($icodmodulo, $_SESSION['unad_id_tercero'], 4, $even22id, $sql, $objdb);
				}
			}
		}
	return $sError;
	}
function f1922_TablaDetalle($params, $objdb){
	require 'app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_1922=$APP->rutacomun.'lg/lg_1922_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1922)){$mensajes_1922=$APP->rutacomun.'lg/lg_1922_es.php';}
	require $mensajes_todas;
	require $mensajes_1922;
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	if (isset($params[0])==0){$params[0]=-1;}
	if (isset($params[101])==0){$params[101]=1;}
	if (isset($params[102])==0){$params[102]=20;}
	//if (isset($params[103])==0){$params[103]='';}
	//$params[103]=numeros_validar($params[103]);
	$params[0]=numeros_validar($params[0]);
	if ($params[0]==''){$params[0]=-1;}
	$even21id=$params[0];
	$pagina=$params[101];
	$lineastabla=$params[102];
	$babierta=true;
	$sqladd='';
	//if ((int)$params[103]!=-1){$sqladd=$sqladd.' AND TB.campo='.$params[103];}
	//if ($params[103]!=''){$sqladd=$sqladd.' AND TB.campo2 LIKE "%'.$params[103].'%"';}
	$sTitulos='Aplica, Pregunta, Id, Tiporespuesta, Opcional, Irespuesta, Nota, Relevante';
	$sql='SELECT TB.even22idaplica, TB.even22idpregunta, TB.even22id, TB.even22tiporespuesta, TB.even22opcional, TB.even22irespuesta, TB.even22nota, TB.even22relevante 
FROM even22encuestarpta AS TB 
WHERE TB.even22idaplica='.$even21id.' '.$sqladd.'
';// ORDER BY TB.nombre';
	$sqllista=str_replace("'","|",$sql);
	$sqllista=str_replace('"',"|",$sqllista);
	$sErrConsulta='<input id="consulta_1922" name="consulta_1922" type="hidden" value="'.$sqllista.'"/>
<input id="titulos_1922" name="titulos_1922" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objdb->ejecutasql($sql);
	if ($tabladetalle==false){
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sql.' '.$objdb->serror.'"/>';
		}else{
		$registros=$objdb->nf($tabladetalle);
		if ($registros==0){
			return utf8_encode($sErrConsulta.'<input id="paginaf1922" name="paginaf1922" type="hidden" value="'.$pagina.'"/><input id="lppf1922" name="lppf1922" type="hidden" value="'.$lineastabla.'"/>');
			break;
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
<td><b>'.$ETI['even22idpregunta'].'</b></td>
<td><b>'.$ETI['even22tiporespuesta'].'</b></td>
<td><b>'.$ETI['even22opcional'].'</b></td>
<td><b>'.$ETI['even22irespuesta'].'</b></td>
<td><b>'.$ETI['even22nota'].'</b></td>
<td><b>'.$ETI['even22relevante'].'</b></td>
<td align="right">
'.html_paginador('paginaf1922', $registros, $lineastabla, $pagina, 'paginarf1922()').'
'.html_lpp('lppf1922', $lineastabla, 'paginarf1922()').'
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
		$et_even22opcional=$ETI['no'];
		if ($filadet['even22opcional']=='S'){$et_even22opcional=$ETI['si'];}
		$et_even22relevante=$ETI['no'];
		if ($filadet['even22relevante']=='S'){$et_even22relevante=$ETI['si'];}
		if ($babierta){
			$sLink='<a href="javascript:cargaridf1922('."'".$filadet['even22id']."'".')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$res=$res.'<tr'.$sClass.'>
<td>'.$sPrefijo.$filadet['even22idpregunta'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['even22tiporespuesta'].$sSufijo.'</td>
<td>'.$sPrefijo.$et_even22opcional.$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['even22irespuesta'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['even22nota']).$sSufijo.'</td>
<td>'.$sPrefijo.$et_even22relevante.$sSufijo.'</td>
<td>'.$sLink.'</td>
</tr>';
		}
	$res=$res.'</table>';
	return utf8_encode($res);
	}
function f1922_Clonar($even22idaplica, $even22idaplicaPadre, $objdb){
	$sError='';
	$even22id=tabla_consecutivo('even22encuestarpta', 'even22id', '', $objdb);
	if ($even22id==-1){$sError=$objdb->serror;}
	if ($sError==''){
		$sCampos1922='even22idaplica, even22idpregunta, even22id, even22tiporespuesta, even22opcional, even22irespuesta, even22nota, even22relevante';
		$sValores1922='';
		$sql='SELECT * FROM even22encuestarpta WHERE even22idaplica='.$even22idaplicaPadre.'';
		$tabla=$objdb->ejecutasql($sql);
		while($fila=$objdb->sf($tabla)){
			if ($sValores1922!=''){$sValores1922=$sValores1922.', ';}
			$sValores1922=$sValores1922.'('.$even22idaplica.', '.$fila['even22idpregunta'].', '.$even22id.', '.$fila['even22tiporespuesta'].', "'.$fila['even22opcional'].'", '.$fila['even22irespuesta'].', "'.$fila['even22nota'].'", "'.$fila['even22relevante'].'")';
			$even22id++;
			}
		if ($sValores1922!=''){
			$sql='INSERT INTO even22encuestarpta('.$sCampos1922.') VALUES '.$sValores1922.'';
			$result=$objdb->ejecutasql($sql);
			}
		}
	return $sError;
	}
// -- 1922 Respuestas XAJAX 
function f1922_Guardar($valores, $params){
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
		list($sError)=f1922_db_Guardar($valores, $objdb);
		}
	$objResponse=new xajaxResponse();
	if ($sError==''){
		$sdetalle=f1922_TablaDetalle($params, $objdb);
		$objResponse->assign('div_f1922detalle', 'innerHTML', $sdetalle);
		$objResponse->call('limpiaf1922');
		$objResponse->assign('alarma', 'innerHTML', 'item guardado');
		}else{
		$objResponse->assign('alarma', 'innerHTML', $sError);
		}
	return $objResponse;
	}
function f1922_Traer($params){
	$_SESSION['u_ultimominuto']=iminutoavance();
	require 'app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$sError='';
	$besta=false;
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	$paso=$params[0];
	if ($paso==1){
		$even22idaplica=numeros_validar($params[1]);
		$even22idpregunta=numeros_validar($params[2]);
		if (($even22idaplica!='')&&($even22idpregunta!='')){$besta=true;}
		}else{
		$even22id=$params[103];
		if ((int)$even22id!=0){$besta=true;}
		}
	if ($besta){
		$besta=false;
		require 'app.php';
		$objdb=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objdb->dbPuerto=$APP->dbpuerto;}
		$objdb->xajax();
		$sqlcondi='';
		if ($paso==1){
			$sqlcondi=$sqlcondi.'even22idaplica='.$even22idaplica.' AND even22idpregunta='.$even22idpregunta.'';
			}else{
			$sqlcondi=$sqlcondi.'even22id='.$even22id.'';
			}
		$sql='SELECT * FROM even22encuestarpta WHERE '.$sqlcondi;
		$tabla=$objdb->ejecutasql($sql);
		if ($objdb->nf($tabla)>0){
			$fila=$objdb->sf($tabla);
			$besta=true;
			}
		}
	$objResponse=new xajaxResponse();
	if ($besta){
		list($even22idpregunta_nombre, $serror_det)=tabla_campoxid('','','', $fila['even22idpregunta'],'{'.$ETI['msg_sindato'].'}', $objdb);
		$html_even22idpregunta=html_oculto('even22idpregunta', $fila['even22idpregunta'], $even22idpregunta_nombre);
		$objResponse->assign('div_even22idpregunta', 'innerHTML', $html_even22idpregunta);
		$even22id_nombre='';
		$html_even22id=html_oculto('even22id', $fila['even22id'], $even22id_nombre);
		$objResponse->assign('div_even22id', 'innerHTML', $html_even22id);
		$objResponse->assign('even22tiporespuesta', 'value', $fila['even22tiporespuesta']);
		$objResponse->assign('even22opcional', 'value', $fila['even22opcional']);
		$objResponse->assign('even22irespuesta', 'value', $fila['even22irespuesta']);
		$objResponse->assign('even22nota', 'value', $fila['even22nota']);
		$objResponse->assign('even22relevante', 'value', $fila['even22relevante']);
		$objResponse->assign('alarma', 'innerHTML', '');
		$objResponse->call("verboton('belimina1922','block')");
		}else{
		if ($paso==1){
			$objResponse->assign('even22idpregunta', 'value', $even22idpregunta);
			}else{
			$objResponse->assign('alarma', 'innerHTML', 'No se encontro el registro de referencia:'.$even22id);
			}
		}
	return $objResponse;
	}
function f1922_Eliminar($params){
	$_SESSION['u_ultimominuto']=iminutoavance();
	$sError='';
	//if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	require 'app.php';
	$objdb=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objdb->dbPuerto=$APP->dbpuerto;}
	$objdb->xajax();
	$sError=f1922_db_Eliminar($params, $objdb);
	$objResponse=new xajaxResponse();
	if ($sError==''){
		$sDetalle=f1922_TablaDetalle($params, $objdb);
		$objResponse->assign('div_f1922detalle', 'innerHTML', $sDetalle);
		$objResponse->call('limpiaf1922');
		$sError='Item eliminado';
		}
	$objResponse->assign('alarma', 'innerHTML', $sError);
	return $objResponse;
	}
function f1922_HtmlTabla($params){
	$_SESSION['u_ultimominuto']=iminutoavance();
	$sError='';
	require 'app.php';
	$objdb=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objdb->dbPuerto=$APP->dbpuerto;}
	$objdb->xajax();
	$sDetalle=f1922_TablaDetalle($params, $objdb);
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f1922detalle', 'innerHTML', $sDetalle);
	return $objResponse;
	}
function f1922_PintarLlaves(){
	$_SESSION['u_ultimominuto']=iminutoavance();
	require 'app.php';
	$objdb=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objdb->dbPuerto=$APP->dbpuerto;}
	$objdb->xajax();
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$html_even22idpregunta=html_combo_even22idpregunta($objdb, 0);
	$html_even22id='<input id="even22id" name="even22id" type="hidden" value=""/>';
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_even22idpregunta','innerHTML', $html_even22idpregunta);
	$objResponse->assign('div_even22id','innerHTML', $html_even22id);
	return $objResponse;
	}
?>