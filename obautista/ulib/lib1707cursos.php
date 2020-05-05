<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2018 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.22.5 martes, 13 de noviembre de 2018
--- 1707 Oferta
*/
function f1707_HTMLComboV2_ofer08idper_aca($objDB, $objCombos, $valor){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('ofer08idper_aca', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	$objCombos->sAccion='revisaf1707()';
	$sSQL='SELECT exte02id AS id, exte02nombre AS nombre FROM exte02per_aca';
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f1707_HTMLComboV2_ofer08cead($objDB, $objCombos, $valor){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('ofer08cead', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	$objCombos->sAccion='revisaf1707()';
	$sSQL='SELECT unad24id AS id, unad24nombre AS nombre FROM unad24sede';
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f1707_db_Guardar($valores, $objDB, $bDebug=false){
	$iCodModulo=1707;
	$bAudita[2]=false;
	$bAudita[3]=false;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_1707=$APP->rutacomun.'lg/lg_1707_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1707)){$mensajes_1707=$APP->rutacomun.'lg/lg_1707_es.php';}
	require $mensajes_todas;
	require $mensajes_1707;
	$sError='';
	$sDebug='';
	$binserta=false;
	$iAccion=3;
	if(!is_array($valores)){$valores=json_decode(str_replace('\"','"',$valores),true);}
	$ofer08idcurso=numeros_validar($valores[1]);
	$ofer08idper_aca=numeros_validar($valores[2]);
	$ofer08cead=numeros_validar($valores[3]);
	$ofer08id=numeros_validar($valores[4], true);
	$ofer08estadooferta=numeros_validar($valores[5]);
	$ofer08numestudiantes=numeros_validar($valores[6]);
	$ofer08fechaoferta=$valores[7];
	$ofer08estadocampus=numeros_validar($valores[8]);
	//if ($ofer08estadooferta==''){$ofer08estadooferta=0;}
	//if ($ofer08numestudiantes==''){$ofer08numestudiantes=0;}
	//if ($ofer08estadocampus==''){$ofer08estadocampus=0;}
	$sSepara=', ';
	if ($ofer08estadocampus==''){$sError=$ERR['ofer08estadocampus'].$sSepara.$sError;}
	if (!fecha_esvalida($ofer08fechaoferta)){
		//$ofer08fechaoferta='00/00/0000';
		$sError=$ERR['ofer08fechaoferta'].$sSepara.$sError;
		}
	if ($ofer08numestudiantes==''){$sError=$ERR['ofer08numestudiantes'].$sSepara.$sError;}
	if ($ofer08estadooferta==''){$sError=$ERR['ofer08estadooferta'].$sSepara.$sError;}
	//if ($ofer08id==''){$sError=$ERR['ofer08id'].$sSepara.$sError;}//CONSECUTIVO
	if ($ofer08cead==''){$sError=$ERR['ofer08cead'].$sSepara.$sError;}
	if ($ofer08idper_aca==''){$sError=$ERR['ofer08idper_aca'].$sSepara.$sError;}
	if ($ofer08idcurso==''){$sError=$ERR['ofer08idcurso'].$sSepara.$sError;}
	if ($sError==''){
		if ((int)$ofer08id==0){
			if ($sError==''){
				$sSQL='SELECT ofer08idcurso FROM ofer08oferta WHERE ofer08idcurso='.$ofer08idcurso.' AND ofer08idper_aca='.$ofer08idper_aca.' AND ofer08cead='.$ofer08cead.'';
				$result=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($result)!=0){
					$sError=$ERR['existe'];
					}else{
					if (!seg_revisa_permiso($iCodModulo, 2, $objDB)){$sError=$ERR['2'];}
					}
				}
			if ($sError==''){
				$ofer08id=tabla_consecutivo('ofer08oferta', 'ofer08id', '', $objDB);
				if ($ofer08id==-1){$sError=$objDB->serror;}
				$binserta=true;
				$iAccion=2;
				}
			}else{
			if (!seg_revisa_permiso($iCodModulo, 3, $objDB)){$sError=$ERR['3'];}
			}
		}
	if ($sError==''){
		if ($binserta){
			$ofer08estadooferta=0;
			$ofer08estadocampus=0;
			//$sSQL='SELECT Campo FROM Tabla WHERE Id='.$sValorId;
			//$tabla=$objDB->ejecutasql($sSQL);
			//if ($objDB->nf($tabla)>0){
				//$fila=$objDB->sf($tabla);
				//$sCampo=$fila['sCampo'];
				//}
			$sError='INFORMACION PARA EL ADMINISTRADOR DEL SISTEMA: No ha definido valores para los campos ocultos.';
			}
		}
	if ($sError==''){
		if ($binserta){
			$scampos='ofer08idcurso, ofer08idper_aca, ofer08cead, ofer08id, ofer08estadooferta, 
ofer08numestudiantes, ofer08fechaoferta, ofer08estadocampus';
			$svalores=''.$ofer08idcurso.', '.$ofer08idper_aca.', '.$ofer08cead.', '.$ofer08id.', '.$ofer08estadooferta.', 
'.$ofer08numestudiantes.', "'.$ofer08fechaoferta.'", '.$ofer08estadocampus.'';
			if ($APP->utf8==1){
				$sSQL='INSERT INTO ofer08oferta ('.$scampos.') VALUES ('.utf8_encode($svalores).');';
				}else{
				$sSQL='INSERT INTO ofer08oferta ('.$scampos.') VALUES ('.$svalores.');';
				}
			$result=$objDB->ejecutasql($sSQL);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' {Oferta}.<!-- '.$sSQL.' -->';
				}else{
				if ($bAudita[2]){
					seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 2, $ofer08id, $sSQL, $objDB);
					}
				}
			}else{
			$scampo1707[1]='ofer08numestudiantes';
			$svr1707[1]=$ofer08numestudiantes;
			$inumcampos=1;
			$sWhere='ofer08id='.$ofer08id.'';
			//$sWhere='ofer08idcurso='.$ofer08idcurso.' AND ofer08idper_aca='.$ofer08idper_aca.' AND ofer08cead='.$ofer08cead.'';
			$sSQL='SELECT * FROM ofer08oferta WHERE '.$sWhere;
			$sdatos='';
			$bpasa=false;
			$result=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($result)>0){
				$filaorigen=$objDB->sf($result);
				for ($k=1;$k<=$inumcampos;$k++){
					if ($filaorigen[$scampo1707[$k]]!=$svr1707[$k]){
						if ($sdatos!=''){$sdatos=$sdatos.', ';}
						$sdatos=$sdatos.$scampo1707[$k].'="'.$svr1707[$k].'"';
						$bpasa=true;
						}
					}
				}
			if ($bpasa){
				if ($APP->utf8==1){
					$sSQL='UPDATE ofer08oferta SET '.utf8_encode($sdatos).' WHERE '.$sWhere.';';
					}else{
					$sSQL='UPDATE ofer08oferta SET '.$sdatos.' WHERE '.$sWhere.';';
					}
				$result=$objDB->ejecutasql($sSQL);
				if ($result==false){
					$sError=$ERR['falla_guardar'].' {Oferta}. <!-- '.$sSQL.' -->';
					}else{
					if ($bAudita[3]){
						seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 3, $ofer08id, $sSQL, $objDB);
						}
					}
				}
			}
		}
	return array($sError, $iAccion, $ofer08id, $sDebug);
	}
function f1707_db_Eliminar($aParametros, $objDB, $bDebug=false){
	$iCodModulo=1707;
	$bAudita[4]=false;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_1707=$APP->rutacomun.'lg/lg_1707_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1707)){$mensajes_1707=$APP->rutacomun.'lg/lg_1707_es.php';}
	require $mensajes_todas;
	require $mensajes_1707;
	$sError='';
	$sDebug='';
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$ofer08idcurso=numeros_validar($aParametros[1]);
	$ofer08idper_aca=numeros_validar($aParametros[2]);
	$ofer08cead=numeros_validar($aParametros[3]);
	$ofer08id=numeros_validar($aParametros[4]);
	if ($sError==''){
		if (!seg_revisa_permiso($iCodModulo, 4, $objDB)){
			$sError=$ERR['4'];
			}
		}
	if ($sError==''){
		$sSQL='SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=1707';
		$tablaor=$objDB->ejecutasql($sSQL);
		while ($filaor=$objDB->sf($tablaor)){
			$sSQL='SELECT '.$filaor['campoid'].' FROM '.$filaor['tabla'].' WHERE '.$filaor['camporev'].'='.$ofer08id.' LIMIT 0, 1';
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
		$sWhere='ofer08id='.$ofer08id.'';
		//$sWhere='ofer08idcurso='.$ofer08idcurso.' AND ofer08idper_aca='.$ofer08idper_aca.' AND ofer08cead='.$ofer08cead.'';
		$sSQL='DELETE FROM ofer08oferta WHERE '.$sWhere.';';
		$result=$objDB->ejecutasql($sSQL);
		if ($result==false){
			$sError=$ERR['falla_eliminar'].' {1707 Oferta}.<!-- '.$sSQL.' -->';
			}else{
			if ($bAudita[4]){
				seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $ofer08id, $sSQL, $objDB);
				}
			}
		}
	return array($sError, $sDebug);
	}
function f1707_TablaDetalleV2($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_1707=$APP->rutacomun.'lg/lg_1707_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1707)){$mensajes_1707=$APP->rutacomun.'lg/lg_1707_es.php';}
	require $mensajes_todas;
	require $mensajes_1707;
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	if (isset($aParametros[0])==0){$aParametros[0]=-1;}
	if (isset($aParametros[101])==0){$aParametros[101]=1;}
	if (isset($aParametros[102])==0){$aParametros[102]=20;}
	//if (isset($aParametros[103])==0){$aParametros[103]='';}
	//$aParametros[103]=numeros_validar($aParametros[103]);
	$aParametros[0]=numeros_validar($aParametros[0]);
	if ($aParametros[0]==''){$aParametros[0]=-1;}
	$sDebug='';
	$unad40id=$aParametros[0];
	$pagina=$aParametros[101];
	$lineastabla=$aParametros[102];
	$babierta=false;
	//$sSQL='SELECT Campo FROM unad40curso WHERE unad40id='.$unad40id;
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
				$sSQLadd=$sSQLadd.' AND T6.sys11razonsocial LIKE "%'.$sCadena.'%"';
				//$sSQLadd1=$sSQLadd1.'T1.sys11razonsocial LIKE "%'.$sCadena.'%" AND ';
				}
			}
		}
	*/
	$sTitulos='Curso, Per_aca, Cead, Id, Estadooferta, Numestudiantes, Fechaoferta, Estadocampus';
	$sSQL='SELECT TB.ofer08idcurso, T2.exte02nombre, TB.ofer08id, T5.ofer07nombre, TB.ofer08numestudiantes, TB.ofer08fechaoferta, T8.ofer15nombre, TB.ofer08idper_aca, TB.ofer08cead, TB.ofer08estadooferta, TB.ofer08estadocampus, TB.ofer08obligaacreditar, TB.ofer08av_fechatermina 
FROM ofer08oferta AS TB, exte02per_aca AS T2, ofer07estadooferta AS T5, ofer15estadocampus AS T8 
WHERE '.$sSQLadd1.' TB.ofer08idcurso='.$unad40id.' AND TB.ofer08idper_aca=T2.exte02id AND TB.ofer08estadooferta=T5.ofer07id AND TB.ofer08estadocampus=T8.ofer15id '.$sSQLadd.'
ORDER BY TB.ofer08idper_aca DESC';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_1707" name="consulta_1707" type="hidden" value="'.$sSQLlista.'"/>
<input id="titulos_1707" name="titulos_1707" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objDB->ejecutasql($sSQL);
	if ($tabladetalle==false){
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 1707: '.$sSQL.'<br>';}
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		//$sLeyenda=$sSQL;
		}else{
		$registros=$objDB->nf($tabladetalle);
		if ($registros==0){
			//return array(utf8_encode($sErrConsulta.'<input id="paginaf1707" name="paginaf1707" type="hidden" value="'.$pagina.'"/><input id="lppf1707" name="lppf1707" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
<td colspan="2"><b>'.$ETI['ofer08idper_aca'].'</b></td>
<td><b>'.$ETI['ofer08estadooferta'].'</b></td>
<td><b>'.$ETI['ofer08numestudiantes'].'</b></td>
<td><b>'.$ETI['ofer08obligaacreditar'].'</b></td>
<td><b>'.$ETI['ofer08estadocampus'].'</b></td>
<td align="right">
'.html_paginador('paginaf1707', $registros, $lineastabla, $pagina, 'paginarf1707()').'
'.html_lpp('lppf1707', $lineastabla, 'paginarf1707()').'
</td>
</tr>';
	$tlinea=1;
	while($filadet=$objDB->sf($tabladetalle)){
		$sPrefijo='';
		$sSufijo='';
		$sClass='';
		$sLink='';
		if(($tlinea%2)==0){$sClass=' class="resaltetabla"';}
		$tlinea++;
		$et_ofer08obligaacreditar=$ETI['msg_especiales'];
		if ($filadet['ofer08obligaacreditar']=='S'){
			$sPrefijo='<b>';
			$sSufijo='</b>';
			$et_ofer08obligaacreditar=$sPrefijo.$ETI['msg_acredita'].$sSufijo;
			if ($filadet['ofer08estadocampus']==10){
				$sLink=$sPrefijo.fecha_desdenumero($filadet['ofer08av_fechatermina']).$sSufijo;
				}
			}
		if ($filadet['ofer08obligaacreditar']=='N'){
			$et_ofer08obligaacreditar=$sPrefijo.$ETI['msg_certifica'].$sSufijo;
			}
		$et_ofer08idper_aca=$sPrefijo.cadena_notildes($filadet['exte02nombre']).$sSufijo;
		$et_ofer08estadooferta=$sPrefijo.cadena_notildes($filadet['ofer07nombre']).$sSufijo;
		$et_ofer08numestudiantes=$sPrefijo.formato_numero($filadet['ofer08numestudiantes'], 0).'&nbsp;&nbsp;'.$sSufijo;
		//$et_ofer08fechaoferta='';
		//if ($filadet['ofer08fechaoferta']!='00/00/0000'){$et_ofer08fechaoferta=$sPrefijo.$filadet['ofer08fechaoferta'].$sSufijo;}
		$et_ofer08estadocampus=$sPrefijo.cadena_notildes($filadet['ofer15nombre']).$sSufijo;
		if ($babierta){
			//$sLink='<a href="javascript:cargaridf1707('.$filadet['ofer08id'].')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$res=$res.'<tr'.$sClass.'>
<td>'.$sPrefijo.$filadet['ofer08idper_aca'].$sSufijo.'</td>
<td>'.$et_ofer08idper_aca.'</td>
<td>'.$et_ofer08estadooferta.'</td>
<td align="right">'.$et_ofer08numestudiantes.'</td>
<td>'.$et_ofer08obligaacreditar.'</td>
<td>'.$et_ofer08estadocampus.'</td>
<td>'.$sLink.'</td>
</tr>';
		}
	$res=$res.'</table>';
	$objDB->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
	}
function f1707_Clonar($ofer08idcurso, $ofer08idcursoPadre, $objDB){
	$sError='';
	$ofer08id=tabla_consecutivo('ofer08oferta', 'ofer08id', '', $objDB);
	if ($ofer08id==-1){$sError=$objDB->serror;}
	if ($sError==''){
		$sCampos1707='ofer08idcurso, ofer08idper_aca, ofer08cead, ofer08id, ofer08estadooferta, ofer08numestudiantes, ofer08fechaoferta, ofer08estadocampus';
		$sValores1707='';
		$sSQL='SELECT * FROM ofer08oferta WHERE ofer08idcurso='.$ofer08idcursoPadre.'';
		$tabla=$objDB->ejecutasql($sSQL);
		while($fila=$objDB->sf($tabla)){
			if ($sValores1707!=''){$sValores1707=$sValores1707.', ';}
			$sValores1707=$sValores1707.'('.$ofer08idcurso.', '.$fila['ofer08idper_aca'].', '.$fila['ofer08cead'].', '.$ofer08id.', '.$fila['ofer08estadooferta'].', '.$fila['ofer08numestudiantes'].', "'.$fila['ofer08fechaoferta'].'", '.$fila['ofer08estadocampus'].')';
			$ofer08id++;
			}
		if ($sValores1707!=''){
			$sSQL='INSERT INTO ofer08oferta('.$sCampos1707.') VALUES '.$sValores1707.'';
			$result=$objDB->ejecutasql($sSQL);
			}
		}
	return $sError;
	}
// -- 1707 Oferta XAJAX 
function f1707_Guardar($valores, $aParametros){
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
		list($sError, $iAccion, $ofer08id, $sDebugGuardar)=f1707_db_Guardar($valores, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugGuardar;
		}
	$objResponse=new xajaxResponse();
	if ($sError==''){
		list($sdetalle, $sDebugTabla)=f1707_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugTabla;
		$objResponse->assign('div_f1707detalle', 'innerHTML', $sdetalle);
		//if ($iAccion==2){
			//$objResponse->call('cargaridf1707('.$ofer08id.')');
			//}else{
			$objResponse->call('limpiaf1707');
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
function f1707_Traer($aParametros){
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
		$ofer08idcurso=numeros_validar($aParametros[1]);
		$ofer08idper_aca=numeros_validar($aParametros[2]);
		$ofer08cead=numeros_validar($aParametros[3]);
		if (($ofer08idcurso!='')&&($ofer08idper_aca!='')&&($ofer08cead!='')){$besta=true;}
		}else{
		$ofer08id=$aParametros[103];
		if ((int)$ofer08id!=0){$besta=true;}
		}
	if ($besta){
		$besta=false;
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		$bHayDb=true;
		$sSQLcondi='';
		if ($paso==1){
			$sSQLcondi=$sSQLcondi.'ofer08idcurso='.$ofer08idcurso.' AND ofer08idper_aca='.$ofer08idper_aca.' AND ofer08cead='.$ofer08cead.'';
			}else{
			$sSQLcondi=$sSQLcondi.'ofer08id='.$ofer08id.'';
			}
		$sSQL='SELECT * FROM ofer08oferta WHERE '.$sSQLcondi;
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
		list($ofer08idper_aca_nombre, $serror_det)=tabla_campoxid('exte02per_aca','exte02nombre','exte02id', $fila['ofer08idper_aca'],'{'.$ETI['msg_sindato'].'}', $objDB);
		$html_ofer08idper_aca=html_oculto('ofer08idper_aca', $fila['ofer08idper_aca'], $ofer08idper_aca_nombre);
		$objResponse->assign('div_ofer08idper_aca', 'innerHTML', $html_ofer08idper_aca);
		list($ofer08cead_nombre, $serror_det)=tabla_campoxid('unad24sede','unad24nombre','unad24id', $fila['ofer08cead'],'{'.$ETI['msg_sindato'].'}', $objDB);
		$html_ofer08cead=html_oculto('ofer08cead', $fila['ofer08cead'], $ofer08cead_nombre);
		$objResponse->assign('div_ofer08cead', 'innerHTML', $html_ofer08cead);
		$ofer08id_nombre='';
		$html_ofer08id=html_oculto('ofer08id', $fila['ofer08id'], $ofer08id_nombre);
		$objResponse->assign('div_ofer08id', 'innerHTML', $html_ofer08id);
		list($ofer08estadooferta_nombre, $serror_det)=tabla_campoxid('ofer07estadooferta','ofer07nombre','ofer07id', $fila['ofer08estadooferta'],'{'.$ETI['msg_sindato'].'}', $objDB);
		$html_ofer08estadooferta=html_oculto('ofer08estadooferta', $fila['ofer08estadooferta'], $ofer08estadooferta_nombre);
		$objResponse->assign('div_ofer08estadooferta', 'innerHTML', $html_ofer08estadooferta);
		$objResponse->assign('ofer08numestudiantes', 'value', $fila['ofer08numestudiantes']);
		$html_ofer08fechaoferta=html_oculto('ofer08fechaoferta', $fila['ofer08fechaoferta']);
		$objResponse->assign('div_ofer08fechaoferta', 'innerHTML', $html_ofer08fechaoferta);
		list($ofer08estadocampus_nombre, $serror_det)=tabla_campoxid('ofer15estadocampus','ofer15nombre','ofer15id', $fila['ofer08estadocampus'],'{'.$ETI['msg_sindato'].'}', $objDB);
		$html_ofer08estadocampus=html_oculto('ofer08estadocampus', $fila['ofer08estadocampus'], $ofer08estadocampus_nombre);
		$objResponse->assign('div_ofer08estadocampus', 'innerHTML', $html_ofer08estadocampus);
		$objResponse->call("MensajeAlarmaV2('', 0)");
		$objResponse->call("verboton('belimina1707','block')");
		}else{
		if ($paso==1){
			$objResponse->assign('ofer08idper_aca', 'value', $ofer08idper_aca);
			$objResponse->assign('ofer08cead', 'value', $ofer08cead);
			}else{
			$objResponse->call('MensajeAlarmaV2("No se encontro el registro de referencia:'.$ofer08id.'", 0)');
			}
		}
	if ($bHayDb){
		$objDB->CerrarConexion();
		}
	return $objResponse;
	}
function f1707_Eliminar($aParametros){
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
	list($sError, $sDebugElimina)=f1707_db_Eliminar($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugElimina;
	$objResponse=new xajaxResponse();
	if ($sError==''){
		list($sDetalle, $sDebugTabla)=f1707_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugTabla;
		$objResponse->assign('div_f1707detalle', 'innerHTML', $sDetalle);
		$objResponse->call('limpiaf1707');
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
function f1707_HtmlTabla($aParametros){
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
	list($sDetalle, $sDebugTabla)=f1707_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f1707detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f1707_PintarLlaves($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	//if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	if (isset($APP->piel)==0){$APP->piel=1;}
	$iPiel=$APP->piel;
	$objCombos=new clsHtmlCombos('n');
	$html_ofer08idper_aca=f1707_HTMLComboV2_ofer08idper_aca($objDB, $objCombos, 0);
	$html_ofer08cead=f1707_HTMLComboV2_ofer08cead($objDB, $objCombos, 0);
	$html_ofer08id='<input id="ofer08id" name="ofer08id" type="hidden" value=""/>';
	$html_ofer08estadooferta=f1707_HTMLComboV2_ofer08estadooferta($objDB, $objCombos, 0);
	$sofer08fechaoferta=fecha_hoy();
	$html_ofer08fechaoferta=html_oculto('ofer08fechaoferta', $sofer08fechaoferta, formato_fechalarga($sofer08fechaoferta));
	$html_ofer08estadocampus=f1707_HTMLComboV2_ofer08estadocampus($objDB, $objCombos, 0);
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_ofer08idper_aca','innerHTML', $html_ofer08idper_aca);
	$objResponse->assign('div_ofer08cead','innerHTML', $html_ofer08cead);
	$objResponse->assign('div_ofer08id','innerHTML', $html_ofer08id);
	$objResponse->assign('div_ofer08estadooferta','innerHTML', $html_ofer08estadooferta);
	$objResponse->assign('div_ofer08fechaoferta','innerHTML', $html_ofer08fechaoferta);
	$objResponse->assign('div_ofer08estadocampus','innerHTML', $html_ofer08estadocampus);
	return $objResponse;
	}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------
?>