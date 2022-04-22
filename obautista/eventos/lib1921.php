<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2015 - 2016 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.9.7 domingo, 29 de noviembre de 2015
--- Modelo Versión 2.15.8 miércoles, 02 de noviembre de 2016
--- 1921 Encuestas aplicadas
*/
function html_combo_even21idperaca($objDB, $valor){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	//$sSQL=f146_ConsultaCombo('', $objDB);
	$res=html_combo('even21idperaca', 'exte02id', 'exte02nombre', 'exte02per_aca', '', 'exte02nombre', $valor, $objDB, 'revisaf1921()', true, '{'.$ETI['msg_seleccione'].'}', '');
	return utf8_encode($res);
	}
function html_combo_even21idbloquedo($objDB, $valor){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$res=html_combo('even21idbloquedo', 'unad63id', 'unad63titulo', 'unad63bloqueo', '', 'unad63titulo', $valor, $objDB, 'revisaf1921()', true, '{'.$ETI['msg_seleccione'].'}', '');
	return utf8_encode($res);
	}
function f1921_db_Guardar($valores, $objDB, $bDebug=false){
	$icodmodulo=1921;
	$bAudita[2]=false;
	$bAudita[3]=false;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_1921='lg/lg_1921_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1921)){$mensajes_1921='lg/lg_1921_es.php';}
	require $mensajes_todas;
	require $mensajes_1921;
	$sError='';
	$sDebug='';
	$binserta=false;
	$iAccion=3;
	if(!is_array($valores)){$valores=json_decode(str_replace('\"','"',$valores),true);}
	$even21idencuesta=numeros_validar($valores[1]);
	$even21idtercero=numeros_validar($valores[2]);
	$even21idperaca=numeros_validar($valores[3]);
	$even21idcurso=numeros_validar($valores[4]);
	$even21idbloquedo=numeros_validar($valores[5]);
	$even21id=numeros_validar($valores[6], true);
	$even21fechapresenta=$valores[7];
	$even21terminada=htmlspecialchars(trim($valores[8]));
	if ($even21terminada==''){$sError=$ERR['even21terminada'];}
	if (!fecha_esvalida($even21fechapresenta)){
		//$even21fechapresenta='00/00/0000';
		$sError=$ERR['even21fechapresenta'];
		}
	//if ($even21id==''){$sError=$ERR['even21id'];}//CONSECUTIVO
	if ($even21idbloquedo==''){$sError=$ERR['even21idbloquedo'];}
	if ($even21idcurso==0){$sError=$ERR['even21idcurso'];}
	if ($even21idperaca==''){$sError=$ERR['even21idperaca'];}
	if ($even21idtercero==0){$sError=$ERR['even21idtercero'];}
	if ($even21idencuesta==''){$sError=$ERR['even21idencuesta'];}
	if ($sError==''){
		$sSQL='SELECT unad40id FROM unad40curso WHERE unad40id="'.$even21idcurso.'"';
		$result=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($result)==0){$sError='No se encuentra el Curso {ref '.$even21idcurso.'}';}
		}
	if ($sError==''){
		list($sError, $sInfo)=tercero_Bloqueado($even21idtercero, $objDB);
		if ($sInfo!=''){$sError=$sError.'<br>'.sInfo;}
		}
	if ($sError==''){
		if ((int)$even21id==0){
			if ($sError==''){
				$sSQL='SELECT even21idencuesta FROM even21encuestaaplica WHERE even21idencuesta='.$even21idencuesta.' AND even21idtercero="'.$even21idtercero.'" AND even21idperaca='.$even21idperaca.' AND even21idcurso='.$even21idcurso.' AND even21idbloquedo='.$even21idbloquedo.'';
				$result=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($result)!=0){
					$sError=$ERR['existe'];
					}else{
					if (!seg_revisa_permiso($icodmodulo, 2, $objDB)){$sError=$ERR['2'];}
					}
				}
			if ($sError==''){
				$even21id=tabla_consecutivo('even21encuestaaplica', 'even21id', '', $objDB);
				if ($even21id==-1){$sError=$objDB->serror;}
				$binserta=true;
				$iAccion=2;
				}
			}else{
			if (!seg_revisa_permiso($icodmodulo, 3, $objDB)){$sError=$ERR['3'];}
			}
		}
	if ($sError==''){
		if ($binserta){
			$scampos='even21idencuesta, even21idtercero, even21idperaca, even21idcurso, even21idbloquedo, even21id, even21fechapresenta, even21terminada';
			$svalores=''.$even21idencuesta.', "'.$even21idtercero.'", '.$even21idperaca.', '.$even21idcurso.', '.$even21idbloquedo.', '.$even21id.', "'.$even21fechapresenta.'", "'.$even21terminada.'"';
			if ($APP->utf8==1){
				$sSQL='INSERT INTO even21encuestaaplica ('.$scampos.') VALUES ('.utf8_encode($svalores).');';
				}else{
				$sSQL='INSERT INTO even21encuestaaplica ('.$scampos.') VALUES ('.$svalores.');';
				}
			$result=$objDB->ejecutasql($sSQL);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' {Encuestas aplicadas}.<!-- '.$sSQL.' -->';
				}else{
				if ($bAudita[2]){
					seg_auditar($icodmodulo, $_SESSION['unad_id_tercero'], 2, $even21id, $sSQL, $objDB);
					}
				}
			}else{
			$scampo1921[1]='even21fechapresenta';
			$scampo1921[2]='even21terminada';
			$svr1921[1]=$even21fechapresenta;
			$svr1921[2]=$even21terminada;
			$inumcampos=2;
			$sWhere='even21id='.$even21id.'';
			//$sWhere='even21idencuesta='.$even21idencuesta.' AND even21idtercero="'.$even21idtercero.'" AND even21idperaca='.$even21idperaca.' AND even21idcurso='.$even21idcurso.' AND even21idbloquedo='.$even21idbloquedo.'';
			$sSQL='SELECT * FROM even21encuestaaplica WHERE '.$sWhere;
			$sdatos='';
			$bpasa=false;
			$result=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($result)>0){
				$filaorigen=$objDB->sf($result);
				for ($k=1;$k<=$inumcampos;$k++){
					if ($filaorigen[$scampo1921[$k]]!=$svr1921[$k]){
						if ($sdatos!=''){$sdatos=$sdatos.', ';}
						$sdatos=$sdatos.$scampo1921[$k].'="'.$svr1921[$k].'"';
						$bpasa=true;
						}
					}
				}
			if ($bpasa){
				if ($APP->utf8==1){
					$sSQL='UPDATE even21encuestaaplica SET '.utf8_encode($sdatos).' WHERE '.$sWhere.';';
					}else{
					$sSQL='UPDATE even21encuestaaplica SET '.$sdatos.' WHERE '.$sWhere.';';
					}
				$result=$objDB->ejecutasql($sSQL);
				if ($result==false){
					$sError=$ERR['falla_guardar'].' {Encuestas aplicadas}. <!-- '.$sSQL.' -->';
					}else{
					if ($bAudita[3]){
						seg_auditar($icodmodulo, $_SESSION['unad_id_tercero'], 3, $even21id, $sSQL, $objDB);
						}
					}
				}
			}
		}
	return array($sError, $iAccion, $even21id, $sDebug);
	}
function f1921_db_Eliminar($params, $objDB, $bDebug=false){
	$icodmodulo=1921;
	$bAudita[4]=false;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_1921='lg/lg_1921_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1921)){$mensajes_1921='lg/lg_1921_es.php';}
	require $mensajes_todas;
	require $mensajes_1921;
	$sError='';
	$sDebug='';
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	$even21idencuesta=numeros_validar($params[1]);
	$even21idtercero=numeros_validar($params[2]);
	$even21idperaca=numeros_validar($params[3]);
	$even21idcurso=numeros_validar($params[4]);
	$even21idbloquedo=numeros_validar($params[5]);
	$even21id=numeros_validar($params[6]);
	if ($sError==''){
		if (!seg_revisa_permiso($icodmodulo, 4, $objDB)){
			$sError=$ERR['4'];
			}
		}
	if ($sError==''){
		//acciones previas
		$sWhere='even21id='.$even21id.'';
		//$sWhere='even21idencuesta='.$even21idencuesta.' AND even21idtercero="'.$even21idtercero.'" AND even21idperaca='.$even21idperaca.' AND even21idcurso='.$even21idcurso.' AND even21idbloquedo='.$even21idbloquedo.'';
		$sSQL='DELETE FROM even21encuestaaplica WHERE '.$sWhere.';';
		$result=$objDB->ejecutasql($sSQL);
		if ($result==false){
			$sError=$ERR['falla_eliminar'].' {1921 Encuestas aplicadas}.<!-- '.$sSQL.' -->';
			}else{
			if ($bAudita[4]){
				seg_auditar($icodmodulo, $_SESSION['unad_id_tercero'], 4, $even21id, $sSQL, $objDB);
				}
			}
		}
	return array($sError, $sDebug);
	}
function f1921_TablaDetalleV2($params, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_1921='lg/lg_1921_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1921)){$mensajes_1921='lg/lg_1921_es.php';}
	require $mensajes_todas;
	require $mensajes_1921;
	$sDebug='';
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	if (isset($params[0])==0){$params[0]=-1;}
	if (isset($params[101])==0){$params[101]=1;}
	if (isset($params[102])==0){$params[102]=20;}
	if (isset($params[103])==0){$params[103]='';}
	if (isset($params[104])==0){$params[104]='';}
	if (isset($params[105])==0){$params[105]='';}
	if (isset($params[106])==0){$params[106]='';}
	//$params[103]=numeros_validar($params[103]);
	$params[0]=numeros_validar($params[0]);
	$params[105]=numeros_validar($params[105]);
	$params[106]=numeros_validar($params[106]);
	if ($params[0]==''){$params[0]=-1;}
	$even16id=$params[0];
	$pagina=$params[101];
	$lineastabla=$params[102];
	$babierta=false;
	$bConPeraca=false;
	$bConCurso=false;
	$sSQL='SELECT even16porperiodo, even16porcurso FROM even16encuesta WHERE even16id='.$even16id;
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		if ($fila['even16porperiodo']=='S'){$bConPeraca=true;}
		if ($fila['even16porcurso']=='S'){$bConCurso=true;}
		}
	$iPendientes=0;
	$iTerminadas=0;
	$sSQL='SELECT COUNT(TB.even21id) AS total, TB.even21terminada FROM even21encuestaaplica AS TB WHERE TB.even21idencuesta='.$even16id.' GROUP BY TB.even21terminada';
	$tabla=$objDB->ejecutasql($sSQL);
	while ($fila=$objDB->sf($tabla)){
		if ($fila['even21terminada']=='S'){
			$iTerminadas=$iTerminadas+$fila['total'];
			}else{
			$iPendientes=$iPendientes+$fila['total'];
			}
		}
	$sSQLadd='';
	$sSQLadd1='';
	//if (isset($params[103])==0){$params[103]='';}
	if ($params[103]!=''){$sSQLadd=$sSQLadd.' AND T2.unad11doc LIKE "%'.$params[103].'%"';}
	if ($params[104]!=''){$sSQLadd=$sSQLadd.' AND T2.unad11razonsocial LIKE "%'.$params[104].'%"';}
	$sCampos='';
	$sTablas='';
	if ($bConPeraca){
		if ($params[105]!=''){$sSQLadd1=$sSQLadd1.' AND TB.even21idperaca='.$params[105];}
		$sCampos=', T3.exte02nombre';
		$sTablas=', exte02per_aca AS T3';
		$sSQLadd=' AND TB.even21idperaca=T3.exte02id'.$sSQLadd;
		}
	if ($bConCurso){
		if ($params[106]!=''){$sSQLadd1=$sSQLadd1.' AND TB.even21idcurso='.$params[106];}
		$sCampos=$sCampos.', T4.unad40nombre';
		$sTablas=$sTablas.', unad40curso AS T4';
		$sSQLadd=' AND TB.even21idcurso=T4.unad40id'.$sSQLadd;
		}
	//, exte02per_aca AS T3, unad40curso AS T4, unad63bloqueo AS T5
	//, T3.exte02nombre, T4.unad40nombre, T5.unad63titulo
	//AND TB.even21idperaca=T3.exte02id AND TB.even21idcurso=T4.unad40id AND TB.even21idbloquedo=T5.unad63id
	$sTitulos='Encuesta, Tercero, Peraca, Curso, Bloquedo, Id, Fechapresenta, Terminada';
	$sSQL='SELECT TB.even21idencuesta'.$sCampos.', T2.unad11razonsocial AS C2_nombre, TB.even21id, TB.even21fechapresenta, TB.even21terminada, TB.even21idtercero, T2.unad11tipodoc AS C2_td, T2.unad11doc AS C2_doc, TB.even21idperaca, TB.even21idcurso, TB.even21idbloquedo 
FROM even21encuestaaplica AS TB, unad11terceros AS T2 '.$sTablas.' 
WHERE TB.even21idencuesta='.$even16id.' '.$sSQLadd1.' AND TB.even21idtercero=T2.unad11id '.$sSQLadd.'
 ORDER BY TB.even21terminada DESC, TB.even21idperaca DESC, TB.even21idcurso, STR_TO_DATE(TB.even21fechapresenta, "%d/%m/%Y") DESC';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_1921" name="consulta_1921" type="hidden" value="'.$sSQLlista.'"/>
<input id="titulos_1921" name="titulos_1921" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objDB->ejecutasql($sSQL);
	if ($tabladetalle==false){
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 1921: '.$sSQL.'<br>';}
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		}else{
		$registros=$objDB->nf($tabladetalle);
		if ($registros==0){
			return array(utf8_encode($sErrConsulta.'<input id="paginaf1921" name="paginaf1921" type="hidden" value="'.$pagina.'"/><input id="lppf1921" name="lppf1921" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
			}
		if ((($registros-1)/$lineastabla)<($pagina-1)){$pagina=(int)(($registros-1)/$lineastabla)+1;}
		if ($registros>$lineastabla){
			$rbase=($pagina-1)*$lineastabla;
			$limite=' LIMIT '.$rbase.', '.$lineastabla;
			$tabladetalle=$objDB->ejecutasql($sSQL.$limite);
			}
		}
	$sTitulos='';
	$iTotal=$iPendientes+$iTerminadas;
	if (($iTotal)>0){
		$sLiberaTodas='';
		if ($iPendientes>0){
			$sLiberaTodas=' <a href="javascript:liberarf1921pendientes()" class="lnkresalte">'.$ETI['lnk_liberarpendientes'].'</a>';
			}
		$sTitulos='<tr class="fondoazul">
<td colspan="8" align="right">Resueltas <b>'.formato_numero($iTerminadas).' ('.formato_numero(($iTerminadas*100)/$iTotal, 2).' %)</b> - Pendientes <b>'.formato_numero($iPendientes).' ('.formato_numero(($iPendientes*100)/$iTotal, 2).' %)</b> Total <b>'.formato_numero($iTotal).'</b>'.$sLiberaTodas.'</td>
</tr>';
		}
	$res=$sErrConsulta.'<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">'.$sTitulos.'
<tr class="fondoazul">
<td colspan="2"><b>'.$ETI['even21idtercero'].'</b></td>
<td><b>'.$ETI['even21idperaca'].'</b></td>
<td><b>'.$ETI['even21idcurso'].'</b></td>
<td><b>'.$ETI['even21idbloquedo'].'</b></td>
<td><b>'.$ETI['even21fechapresenta'].'</b></td>
<td><b>'.$ETI['even21terminada'].'</b></td>
<td align="right">
'.html_paginador('paginaf1921', $registros, $lineastabla, $pagina, 'paginarf1921()').'
'.html_lpp('lppf1921', $lineastabla, 'paginarf1921()').'
</td>
</tr>';
	$tlinea=1;
	while($filadet=$objDB->sf($tabladetalle)){
		$sPrefijo='';
		$sSufijo='';
		$sClass='';
		$sLink='';
		$et_even21terminada=$ETI['no'];
		if ($filadet['even21terminada']=='S'){
			$et_even21terminada=$ETI['si'];
			$sPrefijo='<b>';
			$sSufijo='</b>';
			}
		if(($tlinea%2)==0){$sClass=' class="resaltetabla"';}
		$tlinea++;
		//TB.even21idperaca, TB.even21idcurso, TB.even21idbloquedo 
		$et_even21idperaca='';
		$et_even21idcurso='';
		if ($filadet['even21idcurso']!=0){
			$et_even21idcurso=$filadet['even21idcurso'].' '.cadena_notildes($filadet['unad40nombre']);
			}
		$et_even21idbloquedo='';
		if ($filadet['even21idperaca']!=0){
			$et_even21idperaca=cadena_notildes($filadet['exte02nombre']);
			}
		$et_even21fechapresenta='';
		if ($filadet['even21fechapresenta']!='00/00/0000'){$et_even21fechapresenta=$filadet['even21fechapresenta'];}
		if ($babierta){
			//$sLink='<a href="javascript:cargaridf1921('."'".$filadet['even21id']."'".')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}else{
			if ($filadet['even21terminada']=='S'){
				$sLink='<a href="javascript:abrirf1921('.$filadet['even21id'].')" class="lnkresalte">'.$ETI['lnk_abrir'].'</a>';
				}else{
				$sLink='<a href="javascript:liberarf1921('.$filadet['even21id'].')" class="lnkresalte">'.$ETI['lnk_liberar'].'</a>';
				}
			}
		$res=$res.'<tr'.$sClass.'>
<td>'.$sPrefijo.$filadet['C2_td'].' '.$filadet['C2_doc'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['C2_nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.$et_even21idperaca.$sSufijo.'</td>
<td>'.$sPrefijo.$et_even21idcurso.$sSufijo.'</td>
<td>'.$sPrefijo.$et_even21idbloquedo.$sSufijo.'</td>
<td>'.$sPrefijo.$et_even21fechapresenta.$sSufijo.'</td>
<td>'.$sPrefijo.$et_even21terminada.$sSufijo.'</td>
<td>'.$sLink.'</td>
</tr>';
		}
	$res=$res.'</table>';
	return array(utf8_encode($res), $sDebug);
	}
function f1921_Clonar($even21idencuesta, $even21idencuestaPadre, $objDB){
	$sError='';
	$even21id=tabla_consecutivo('even21encuestaaplica', 'even21id', '', $objDB);
	if ($even21id==-1){$sError=$objDB->serror;}
	if ($sError==''){
		$sCampos1921='even21idencuesta, even21idtercero, even21idperaca, even21idcurso, even21idbloquedo, even21id, even21fechapresenta, even21terminada';
		$sValores1921='';
		$sSQL='SELECT * FROM even21encuestaaplica WHERE even21idencuesta='.$even21idencuestaPadre.'';
		$tabla=$objDB->ejecutasql($sSQL);
		while($fila=$objDB->sf($tabla)){
			if ($sValores1921!=''){$sValores1921=$sValores1921.', ';}
			$sValores1921=$sValores1921.'('.$even21idencuesta.', '.$fila['even21idtercero'].', '.$fila['even21idperaca'].', '.$fila['even21idcurso'].', '.$fila['even21idbloquedo'].', '.$even21id.', "'.$fila['even21fechapresenta'].'", "'.$fila['even21terminada'].'")';
			$even21id++;
			}
		if ($sValores1921!=''){
			$sSQL='INSERT INTO even21encuestaaplica('.$sCampos1921.') VALUES '.$sValores1921.'';
			$result=$objDB->ejecutasql($sSQL);
			}
		}
	return $sError;
	}
// -- 1921 Encuestas aplicadas XAJAX 
function TraerBusqueda_db_even21idcurso($sCodigo, $objDB){
	$sRespuesta='';
	$id=0;
	$sCodigo=htmlspecialchars(trim($sCodigo));
	if ($sCodigo!=''){
		$sSQL='SELECT unad40id, unad40nombre, unad40id FROM unad40curso WHERE unad40id="'.$sCodigo.'"';
		$res=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($res)!=0){
			$fila=$objDB->sf($res);
			$sRespuesta='<b>'.$fila['unad40id'].' '.cadena_notildes($fila['unad40nombre']).'</b>';
			$id=$fila['unad40id'];
			}
		if ($sRespuesta==''){
			$sRespuesta='<span class="rojo">{'.$sCodigo.' No encontrado}</span>';
			}
		}
	return array($id, utf8_encode($sRespuesta));
	}
function TraerBusqueda_even21idcurso($params){
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	$_SESSION['u_ultimominuto']=iminutoavance();
	$sRespuesta='';
	$scodigo=$params[0];
	$bxajax=true;
	if (isset($params[3])!=0){if ($params[3]==1){$bxajax=false;}}
	$id=0;
	if ($scodigo!=''){
		require './app.php';
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		list($id, $sRespuesta)=TraerBusqueda_db_even21idcurso($scodigo, $objDB);
		}
	$objid=$params[1];
	$sdiv=$params[2];
	$objResponse=new xajaxResponse();
	$objResponse->assign($sdiv, 'innerHTML', $sRespuesta);
	$objResponse->assign($objid, 'value', $id);
	if ((int)$id>0){
		$objResponse->call('revisaf1921');
		}
	return $objResponse;
	}
function f1921_Guardar($valores, $params){
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
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		list($sError, $iAccion, $even21id, $sDebugGuardar)=f1921_db_Guardar($valores, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugGuardar;
		}
	$objResponse=new xajaxResponse();
	if ($sError==''){
		list($sdetalle, $sDebugTabla)=f1921_TablaDetalleV2($params, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugTabla;
		$objResponse->assign('div_f1921detalle', 'innerHTML', $sdetalle);
		//if ($iAccion==2){
			//$objResponse->call('cargaridf1921('.$even21id.')');
			//}else{
			$objResponse->call('limpiaf1921');
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
function f1921_Traer($params){
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
		$even21idencuesta=numeros_validar($params[1]);
		$even21idtercero=numeros_validar($params[2]);
		$even21idperaca=numeros_validar($params[3]);
		$even21idcurso=numeros_validar($params[4]);
		$even21idbloquedo=numeros_validar($params[5]);
		if (($even21idencuesta!='')&&($even21idtercero!='')&&($even21idperaca!='')&&($even21idcurso!='')&&($even21idbloquedo!='')){$besta=true;}
		}else{
		$even21id=$params[103];
		if ((int)$even21id!=0){$besta=true;}
		}
	if ($besta){
		$besta=false;
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		$sSQLcondi='';
		if ($paso==1){
			$sSQLcondi=$sSQLcondi.'even21idencuesta='.$even21idencuesta.' AND even21idtercero='.$even21idtercero.' AND even21idperaca='.$even21idperaca.' AND even21idcurso='.$even21idcurso.' AND even21idbloquedo='.$even21idbloquedo.'';
			}else{
			$sSQLcondi=$sSQLcondi.'even21id='.$even21id.'';
			}
		$sSQL='SELECT * FROM even21encuestaaplica WHERE '.$sSQLcondi;
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$fila=$objDB->sf($tabla);
			$besta=true;
			}
		}
	$objResponse=new xajaxResponse();
	if ($besta){
		$even21idtercero_id=(int)$fila['even21idtercero'];
		$even21idtercero_td=$APP->tipo_doc;
		$even21idtercero_doc='';
		$even21idtercero_nombre='';
		if ($even21idtercero_id!=0){
			list($even21idtercero_nombre, $even21idtercero_id, $even21idtercero_td, $even21idtercero_doc)=html_tercero($even21idtercero_td, $even21idtercero_doc, $even21idtercero_id, 0, $objDB);
			}
		$even21idcurso_nombre='';
		$even21idcurso_cod='';
		if ((int)$fila['even21idcurso']!=0){
			$sSQL='SELECT unad40id, unad40nombre FROM unad40curso WHERE unad40id='.$fila['even21idcurso'].'';
			$res=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($res)!=0){
				$filaDetalle=$objDB->sf($res);
				$even21idcurso_nombre='<b>'.cadena_notildes($filaDetalle['unad40nombre']).'</b>';
				$even21idcurso_cod=$filaDetalle['unad40id'];
				}
			if ($even21idcurso_nombre==''){
				$even21idcurso_nombre='<font class="rojo">{Ref : '.$fila['even21idcurso'].' No encontrado}</font>';
				}
			}
		$html_even21idtercero_llaves=html_DivTercero('even21idtercero', $even21idtercero_td, $even21idtercero_doc, true, 2, 'Ingrese el documento');
		$objResponse->assign('even21idtercero', 'value', $even21idtercero_id);
		$objResponse->assign('div_even21idtercero_llaves', 'innerHTML', $html_even21idtercero_llaves);
		$objResponse->assign('div_even21idtercero', 'innerHTML', $even21idtercero_nombre);
		list($even21idperaca_nombre, $serror_det)=tabla_campoxid('exte02per_aca','exte02nombre','exte02id', $fila['even21idperaca'],'{'.$ETI['msg_sindato'].'}', $objDB);
		$html_even21idperaca=html_oculto('even21idperaca', $fila['even21idperaca'], $even21idperaca_nombre);
		$objResponse->assign('div_even21idperaca', 'innerHTML', $html_even21idperaca);
		$html_even21idcurso_cod=html_oculto('even21idcurso_cod', $even21idcurso_cod);
		$objResponse->assign('even21idcurso', 'value', $fila['even21idcurso']);
		$objResponse->assign('div_even21idcurso_cod', 'innerHTML', $html_even21idcurso_cod);
		$objResponse->call("verboton('beven21idcurso','none')");
		$objResponse->assign('div_even21idcurso', 'innerHTML', $even21idcurso_nombre);
		list($even21idbloquedo_nombre, $serror_det)=tabla_campoxid('unad63bloqueo','unad63titulo','unad63id', $fila['even21idbloquedo'],'{'.$ETI['msg_sindato'].'}', $objDB);
		$html_even21idbloquedo=html_oculto('even21idbloquedo', $fila['even21idbloquedo'], $even21idbloquedo_nombre);
		$objResponse->assign('div_even21idbloquedo', 'innerHTML', $html_even21idbloquedo);
		$even21id_nombre='';
		$html_even21id=html_oculto('even21id', $fila['even21id'], $even21id_nombre);
		$objResponse->assign('div_even21id', 'innerHTML', $html_even21id);
		$objResponse->assign('even21fechapresenta', 'value', $fila['even21fechapresenta']);
		$objResponse->assign('even21fechapresenta_dia', 'value', substr($fila['even21fechapresenta'], 0, 2));
		$objResponse->assign('even21fechapresenta_mes', 'value', substr($fila['even21fechapresenta'], 3, 2));
		$objResponse->assign('even21fechapresenta_agno', 'value', substr($fila['even21fechapresenta'], 6, 4));
		$objResponse->assign('even21terminada', 'value', $fila['even21terminada']);
		$objResponse->call("MensajeAlarmaV2('', 0)");
		$objResponse->call("verboton('belimina1921','block')");
		}else{
		if ($paso==1){
			$objResponse->assign('even21idperaca', 'value', $even21idperaca);
			$objResponse->assign('even21idcurso', 'value', $even21idcurso);
			$objResponse->assign('even21idbloquedo', 'value', $even21idbloquedo);
			}else{
			$objResponse->call('MensajeAlarmaV2("No se encontro el registro de referencia:'.$even21id.'", 0)');
			}
		}
	return $objResponse;
	}
function f1921_Eliminar($params){
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
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	list($sError, $sDebugElimina)=f1921_db_Eliminar($params, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugElimina;
	$objResponse=new xajaxResponse();
	if ($sError==''){
		list($sDetalle, $sDebugTabla)=f1921_TablaDetalleV2($params, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugTabla;
		$objResponse->assign('div_f1921detalle', 'innerHTML', $sDetalle);
		$objResponse->call('limpiaf1921');
		$sError=$ETI['msg_itemeliminado'];
		$iTipoError=1;
		}
	$objResponse->call("MensajeAlarmaV2('".$sError."', ".$iTipoError.")");
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f1921_HtmlTabla($params){
	$_SESSION['u_ultimominuto']=iminutoavance();
	$sError='';
	$bDebug=false;
	$sDebug='';
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	list($sDetalle, $sDebugTabla)=f1921_TablaDetalleV2($params, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f1921detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f1921_PintarLlaves($params){
	$_SESSION['u_ultimominuto']=iminutoavance();
	//if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$even21idtercero=0;
	$even21idtercero_rs='';
	$html_even21idtercero_llaves=html_DivTercero('even21idtercero', $APP->tipo_doc, '', false, 2, $ETI['ing_doc']);
	$html_even21idperaca=html_combo_even21idperaca($objDB, 0);
	$html_even21idcurso_cod='<input id="even21idcurso_cod" name="even21idcurso_cod" type="text" value="" onchange="cod_even21idcurso()" class="veinte"/>';
	$html_even21idbloquedo=html_combo_even21idbloquedo($objDB, 0);
	$html_even21id='<input id="even21id" name="even21id" type="hidden" value=""/>';
	$objResponse=new xajaxResponse();
	$objResponse->assign('even21idtercero','value', $even21idtercero);
	$objResponse->assign('div_even21idtercero_llaves','innerHTML', $html_even21idtercero_llaves);
	$objResponse->assign('div_even21idtercero','innerHTML', $even21idtercero_rs);
	$objResponse->assign('div_even21idperaca','innerHTML', $html_even21idperaca);
	$objResponse->assign('even21idcurso','value', '0');
	$objResponse->assign('div_even21idcurso_cod','innerHTML', $html_even21idcurso_cod);
	$objResponse->assign('div_even21idcurso','innerHTML', '');
	$objResponse->call("verboton('beven21idcurso','block')");
	$objResponse->assign('div_even21idbloquedo','innerHTML', $html_even21idbloquedo);
	$objResponse->assign('div_even21id','innerHTML', $html_even21id);
	return $objResponse;
	}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------
function f1921_AbrirEncuesta($params){
	$_SESSION['u_ultimominuto']=iminutoavance();
	$sError='';
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	require 'app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	if (isset($params[1])==0){$params[1]='';}
	$id21=numeros_validar($params[1]);
	if ($id21==''){$id21=0;}
	if ($id21>0){
		$sSQL='UPDATE even21encuestaaplica SET even21terminada="N" WHERE even21id='.$id21;
		$result=$objDB->ejecutasql($sSQL);
		$objResponse=new xajaxResponse();
		$objResponse->call('paginarf1921');
		return $objResponse;
		}
	}
function f1921_ArmarTemporal($id16, $objDB){
	$sError='';
	$sTablaTotal='even31total_'.$id16;
	if (!$objDB->bexistetabla($sTablaTotal)){
		$sCampos='';
		$sSQL='SELECT even18id, even18tiporespuesta, even18concomentario FROM even18encuestapregunta WHERE even18idencuesta='.$id16.'';
		$tabla=$objDB->ejecutasql($sSQL);
		while ($fila=$objDB->sf($tabla)){
			switch($fila['even18tiporespuesta']){
				case 0: // Si - no
				case 1: // Multiple Opcion.
				$sCampos=$sCampos.', even31r'.$fila['even18id'].' int NULL DEFAULT -1';
				break;
				case 2: // Respuesta Multiple.
				case 3: // Abierta.
				$sCampos=$sCampos.', even31r'.$fila['even18id'].' Text NULL';
				break;
				}
			}
		$sSQL='CREATE TABLE '.$sTablaTotal.' (even31id int NULL DEFAULT 0'.$sCampos.')';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($tabla==false){
			$sError='No fue posible crear la tabla de totales: '.$sSQL;
			}else{
			$sSQL='ALTER TABLE '.$sTablaTotal.' ADD PRIMARY KEY(even31id)';
			$tabla=$objDB->ejecutasql($sSQL);
			}
		}
	return $sError;
	}
function f1921_CamposTemporal($id16, $objDB, $sAliasTabla=''){
	$sCampos='';
	$sIds='';
	$sTipos='';
	$sVacios='';
	$sSQL='SELECT even18id, even18tiporespuesta, even18concomentario FROM even18encuestapregunta WHERE even18idencuesta='.$id16.'';
	$tabla=$objDB->ejecutasql($sSQL);
	while ($fila=$objDB->sf($tabla)){
		$sCampos=$sCampos.', '.$sAliasTabla.'even31r'.$fila['even18id'].'';
		if ($sIds!=''){
			$sIds=$sIds.'|';
			$sTipos=$sTipos.'|';
			}
		$sIds=$sIds.$fila['even18id'];
		$sTipos=$sTipos.$fila['even18tiporespuesta'];
		switch($fila['even18tiporespuesta']){
			case 0: // Si - no
			case 1: // Multiple Opcion.
			$sVacios=$sVacios.', -1';
			break;
			case 2: // Respuesta Multiple.
			case 3: // Abierta.
			$sVacios=$sVacios.', ""';
			break;
			}
		}
	return array($sCampos, $sIds, $sTipos, $sVacios);
	}
function f1921_QuitarEncuesta($params){
	$_SESSION['u_ultimominuto']=iminutoavance();
	$sError='';
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	require 'app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	if (isset($params[1])==0){$params[1]='';}
	$id21=numeros_validar($params[1]);
	if ($id21==''){$id21=0;}
	if ($id21>0){
		$sSQL='DELETE FROM even22encuestarpta WHERE even22idaplica='.$id21;
		$result=$objDB->ejecutasql($sSQL);
		$sSQL='DELETE FROM even21encuestaaplica WHERE even21id='.$id21;
		$result=$objDB->ejecutasql($sSQL);
		$objResponse=new xajaxResponse();
		$objResponse->call('paginarf1921');
		return $objResponse;
		}
	}
function f1921_QuitarEncuestasPendientes($params){
	$_SESSION['u_ultimominuto']=iminutoavance();
	$sError='';
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	require 'app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	if (isset($params[1])==0){$params[1]='';}
	$id16=numeros_validar($params[1]);
	if ($id16==''){$id16=0;}
	if ($id16>0){
		$sIds21='-99';
		$iCantidad=1;
		while ($iCantidad>0){
			$sSQL='DELETE FROM even22encuestarpta WHERE even22idaplica IN ('.$sIds21.')';
			$result=$objDB->ejecutasql($sSQL);
			$sSQL='DELETE FROM even21encuestaaplica WHERE even21id IN ('.$sIds21.')';
			$result=$objDB->ejecutasql($sSQL);
			$sSQL='SELECT even21id FROM even21encuestaaplica WHERE even21idencuesta='.$id16.' AND  even21terminada<>"S" LIMIT 0, 100';
			$tabla=$objDB->ejecutasql($sSQL);
			$iCantidad=$objDB->nf($tabla);
			while ($fila=$objDB->sf($tabla)){
				$sIds21=$sIds21.','.$fila['even21id'];
				}
			}
		$objResponse=new xajaxResponse();
		$objResponse->call('paginarf1921');
		return $objResponse;
		}
	}
function f1921_ReconstruirTotales($id16, $objDB){
	//Totalizar las preguntas.
		//Reviso cuales son las preguntas y el tipo de respuesta que deben tener.
		$sSQL='SELECT even18id, even18tiporespuesta FROM even18encuestapregunta WHERE even18idencuesta='.$id16.' AND even18tiporespuesta IN (0,1)';
		$tabla18=$objDB->ejecutasql($sSQL);
		while($fila18=$objDB->sf($tabla18)){
			$iTotal=0;
			for ($p=0;$p<=9;$p++){
				$rpta[$p]=0;
				}
			//Busco las respuestas para esa pregunta... 
			//Esto causo demoras... el problema es que no estoy buscando las respuesta sde la persona, sino todas las respuestas
			$sSQL='SELECT T2.even22irespuesta, COUNT(T2.even22id) AS total
FROM even21encuestaaplica AS TB, even22encuestarpta AS T2 
WHERE TB.even21idencuesta='.$id16.' AND TB.even21terminada="S" AND TB.even21id=T2.even22idaplica AND T2.even22idpregunta='.$fila18['even18id'].'
GROUP BY T2.even22irespuesta';
			$tabla21=$objDB->ejecutasql($sSQL);
			while($fila21=$objDB->sf($tabla21)){
				$iTotal=$iTotal+$fila21['total'];
				if ($fila21['even22irespuesta']<>-1){
					switch($fila18['even18tiporespuesta']){
						case 0:
						$rpta[$fila21['even22irespuesta']]=$fila21['total'];
						break;
						case 1:
						$rpta[$fila21['even22irespuesta']]=$fila21['total'];
						break;
						}
					}
				}
			$sSQL='UPDATE even18encuestapregunta SET even18rptatotal='.$iTotal.', even18rpta0='.$rpta[0].' ,even18rpta1='.$rpta[1].' ,even18rpta2='.$rpta[2].' ,even18rpta3='.$rpta[3].' ,even18rpta4='.$rpta[4].' ,even18rpta5='.$rpta[5].' ,even18rpta6='.$rpta[6].' ,even18rpta7='.$rpta[7].' ,even18rpta8='.$rpta[8].' ,even18rpta9='.$rpta[9].' WHERE even18id='.$fila18['even18id'].'';
			$result=$objDB->ejecutasql($sSQL);
			//echo $sSQL.'<br>';
			}
		$sSQL='SELECT even18id FROM even18encuestapregunta WHERE even18idencuesta='.$id16.' AND even18tiporespuesta=2';
		$tabla18=$objDB->ejecutasql($sSQL);
		while($fila18=$objDB->sf($tabla18)){
			$sSQL='SELECT COUNT(T2.even22id) AS total, SUM(T2.even22rpta1) AS r1, SUM(T2.even22rpta2) AS r2, SUM(T2.even22rpta3) AS r3, SUM(T2.even22rpta4) AS r4, SUM(T2.even22rpta5) AS r5, SUM(T2.even22rpta6) AS r6, SUM(T2.even22rpta7) AS r7, SUM(T2.even22rpta8) AS r8, SUM(T2.even22rpta9) AS r9
FROM even21encuestaaplica AS TB, even22encuestarpta AS T2 
WHERE TB.even21idencuesta='.$id16.' AND TB.even21terminada="S" AND TB.even21id=T2.even22idaplica AND T2.even22idpregunta='.$fila18['even18id'].'
GROUP BY T2.even22irespuesta';
			$tabla21=$objDB->ejecutasql($sSQL);
			$fila21=$objDB->sf($tabla21);
			$iTotal=$fila21['total'];
			$rpta[1]=$fila21['r1'];
			$rpta[2]=$fila21['r2'];
			$rpta[3]=$fila21['r3'];
			$rpta[4]=$fila21['r4'];
			$rpta[5]=$fila21['r5'];
			$rpta[6]=$fila21['r6'];
			$rpta[7]=$fila21['r7'];
			$rpta[8]=$fila21['r8'];
			$rpta[9]=$fila21['r9'];
			$sSQL='UPDATE even18encuestapregunta SET even18rptatotal='.$iTotal.', even18rpta0=0 ,even18rpta1='.$rpta[1].' ,even18rpta2='.$rpta[2].' ,even18rpta3='.$rpta[3].' ,even18rpta4='.$rpta[4].' ,even18rpta5='.$rpta[5].' ,even18rpta6='.$rpta[6].' ,even18rpta7='.$rpta[7].' ,even18rpta8='.$rpta[8].' ,even18rpta9='.$rpta[9].' WHERE even18id='.$fila18['even18id'].'';
			$result=$objDB->ejecutasql($sSQL);
			}
	//tERMINA DE TOTALIZAR LAS PREGUNTAS
	$sTablaTotal='even31total_'.$id16;
	$sSQL='DELETE FROM '.$sTablaTotal;
	$tabla=$objDB->ejecutasql($sSQL);
	list($sCampos, $sIds, $sTipos, $sVacios)=f1921_CamposTemporal($id16, $objDB);
	$sSQL='INSERT INTO '.$sTablaTotal.' (even31id'.$sCampos.') SELECT even21id'.$sVacios.' FROM even21encuestaaplica WHERE even21idencuesta='.$id16.' AND even21terminada="S"';
	$tabla=$objDB->ejecutasql($sSQL);
	$aTipos=explode('|', $sTipos);
	$aIds=explode('|', $sIds);
	$iCampos=count($aTipos);
	for ($k=0;$k<$iCampos;$k++){
		//echo $aIds[$k].' ';
		switch ($aTipos[$k]){
			case 0:
			case 1:
			$sSQL='UPDATE even22encuestarpta AS T2, '.$sTablaTotal.' AS T1 SET T1.even31r'.$aIds[$k].'=T2.even22irespuesta WHERE T2.even22idaplica=T1.even31id AND T2.even22idpregunta='.$aIds[$k].'';
			$tabla=$objDB->ejecutasql($sSQL);
			break;
			case 2:
			for ($j=1;$j<10;$j++){
				$sSQL='UPDATE even22encuestarpta AS T2, '.$sTablaTotal.' AS T1 SET T1.even31r'.$aIds[$k].'=CONCAT(T1.even31r'.$aIds[$k].','.$j.',".") WHERE T2.even22idaplica=T1.even31id AND T2.even22idpregunta='.$aIds[$k].' AND T2.even22rpta'.$j.'=1';
				$tabla=$objDB->ejecutasql($sSQL);
				}
			break;
			case 3:
			$sSQL='UPDATE even22encuestarpta AS T2, '.$sTablaTotal.' AS T1 SET T1.even31r'.$aIds[$k].'=T2.even22nota WHERE T2.even22idaplica=T1.even31id AND T2.even22idpregunta='.$aIds[$k].'';
			$tabla=$objDB->ejecutasql($sSQL);
			break;
			}
		}
	}
function f1921_VerificarTemporal($id16, $objDB, $bDebug=false){
	//Noviembre 12 de 2019, a veces publican una encuesta, luego la despublican y luego vuelven y la publican agregandole nuevas respuestas, entonces la tabla ya no sirve toca arregarla.
	$sError='';
	$sDebug='';
	$sTablaTotal='even31total_'.$id16;
	if ($objDB->bexistetabla($sTablaTotal)){
		$sSQL='SELECT even18id, even18tiporespuesta, even18concomentario FROM even18encuestapregunta WHERE even18idencuesta='.$id16.'';
		$tabla=$objDB->ejecutasql($sSQL);
		while ($fila=$objDB->sf($tabla)){
			$sCampo='even31r'.$fila['even18id'];
			$sSQL='SELECT '.$sCampo.' FROM '.$sTablaTotal.' LIMIT 0, 1';
			$result=$objDB->ejecutasql($sSQL);
			if ($result==false){
				switch($fila['even18tiporespuesta']){
					case 0: // Si - no
					case 1: // Multiple Opcion.
					$sSQL='ALTER TABLE '.$sTablaTotal.' ADD '.$sCampo.' int NULL DEFAULT -1';
					$result=$objDB->ejecutasql($sSQL);
					break;
					case 2: // Respuesta Multiple.
					case 3: // Abierta.
					$sSQL='ALTER TABLE '.$sTablaTotal.' ADD '.$sCampo.' Text NULL';
					$result=$objDB->ejecutasql($sSQL);
					break;
					}
				}
			}
		}
	return array($sError, $sDebug);
	}
?>