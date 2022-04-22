<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2019 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.23.6 Wednesday, October 9, 2019
--- 2914 aplicacion a oferta
*/
function f2914_HTMLComboV2_plab14hv($objDB, $objCombos, $valor){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('plab14hv', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	$objCombos->sAccion='revisaf2914()';
	$sSQL='SELECT plab01id AS id, plab01idtercero AS nombre FROM plab01hv';
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f2914_db_Guardar($valores, $objDB, $bDebug=false){
	$iCodModulo=2914;
	$bAudita[2]=false;
	$bAudita[3]=false;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2914='lg/lg_2914_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2914)){$mensajes_2914='lg/lg_2914_es.php';}
	require $mensajes_todas;
	require $mensajes_2914;
	$sError='';
	$sDebug='';
	$binserta=false;
	$iAccion=3;
	if(!is_array($valores)){$valores=json_decode(str_replace('\"','"',$valores),true);}
	$plab14oferta=numeros_validar($valores[1]);
	$plab14hv=numeros_validar($valores[2]);
	$plab14id=numeros_validar($valores[3], true);
	$plab14fechaaplica=$valores[4];
	$sSepara=', ';
	if ($plab14fechaaplica==0){
		//$plab14fechaaplica=fecha_DiaMod();
		$sError=$ERR['plab14fechaaplica'].$sSepara.$sError;
		}
	//if ($plab14id==''){$sError=$ERR['plab14id'].$sSepara.$sError;}//CONSECUTIVO
	if ($plab14hv==''){$sError=$ERR['plab14hv'].$sSepara.$sError;}
	if ($plab14oferta==''){$sError=$ERR['plab14oferta'].$sSepara.$sError;}
	if ($sError==''){
		if ((int)$plab14id==0){
			if ($sError==''){
				$sSQL='SELECT plab14oferta FROM plab14aplicaofer WHERE plab14oferta='.$plab14oferta.' AND plab14hv='.$plab14hv.'';
				$result=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($result)!=0){
					$sError=$ERR['existe'];
					}else{
					if (!seg_revisa_permiso($iCodModulo, 2, $objDB)){$sError=$ERR['2'];}
					}
				}
			if ($sError==''){
				$plab14id=tabla_consecutivo('plab14aplicaofer', 'plab14id', '', $objDB);
				if ($plab14id==-1){$sError=$objDB->serror;}
				$binserta=true;
				$iAccion=2;
				}
			}else{
			if (!seg_revisa_permiso($iCodModulo, 3, $objDB)){$sError=$ERR['3'];}
			}
		}
	if ($sError==''){
		if ($binserta){
			}
		}
	if ($sError==''){
		if ($binserta){
			$scampos='plab14oferta, plab14hv, plab14id, plab14fechaaplica';
			$svalores=''.$plab14oferta.', '.$plab14hv.', '.$plab14id.', "'.$plab14fechaaplica.'"';
			if ($APP->utf8==1){
				$sSQL='INSERT INTO plab14aplicaofer ('.$scampos.') VALUES ('.utf8_encode($svalores).');';
				}else{
				$sSQL='INSERT INTO plab14aplicaofer ('.$scampos.') VALUES ('.$svalores.');';
				}
			$result=$objDB->ejecutasql($sSQL);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' {aplicacion a oferta}.<!-- '.$sSQL.' -->';
				}else{
				if ($bAudita[2]){
					seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 2, $plab14id, $sSQL, $objDB);
					}
				}
			}else{
			$scampo2914[1]='plab14fechaaplica';
			$svr2914[1]=$plab14fechaaplica;
			$inumcampos=1;
			$sWhere='plab14id='.$plab14id.'';
			//$sWhere='plab14oferta='.$plab14oferta.' AND plab14hv='.$plab14hv.'';
			$sSQL='SELECT * FROM plab14aplicaofer WHERE '.$sWhere;
			$sdatos='';
			$bpasa=false;
			$result=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($result)>0){
				$filaorigen=$objDB->sf($result);
				for ($k=1;$k<=$inumcampos;$k++){
					if ($filaorigen[$scampo2914[$k]]!=$svr2914[$k]){
						if ($sdatos!=''){$sdatos=$sdatos.', ';}
						$sdatos=$sdatos.$scampo2914[$k].'="'.$svr2914[$k].'"';
						$bpasa=true;
						}
					}
				}
			if ($bpasa){
				if ($APP->utf8==1){
					$sSQL='UPDATE plab14aplicaofer SET '.utf8_encode($sdatos).' WHERE '.$sWhere.';';
					}else{
					$sSQL='UPDATE plab14aplicaofer SET '.$sdatos.' WHERE '.$sWhere.';';
					}
				$result=$objDB->ejecutasql($sSQL);
				if ($result==false){
					$sError=$ERR['falla_guardar'].' {aplicacion a oferta}. <!-- '.$sSQL.' -->';
					}else{
					if ($bAudita[3]){
						seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 3, $plab14id, $sSQL, $objDB);
						}
					}
				}
			}
		}
	return array($sError, $iAccion, $plab14id, $sDebug);
	}
function f2914_db_Eliminar($aParametros, $objDB, $bDebug=false){
	$iCodModulo=2914;
	$bAudita[4]=false;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2914='lg/lg_2914_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2914)){$mensajes_2914='lg/lg_2914_es.php';}
	require $mensajes_todas;
	require $mensajes_2914;
	$sError='';
	$sDebug='';
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$plab14oferta=numeros_validar($aParametros[1]);
	$plab14hv=numeros_validar($aParametros[2]);
	$plab14id=numeros_validar($aParametros[3]);
	if ($sError==''){
		if (!seg_revisa_permiso($iCodModulo, 4, $objDB)){
			$sError=$ERR['4'];
			}
		}
	if ($sError==''){
		$sSQL='SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=2914';
		$tablaor=$objDB->ejecutasql($sSQL);
		while ($filaor=$objDB->sf($tablaor)){
			$sSQL='SELECT '.$filaor['campoid'].' FROM '.$filaor['tabla'].' WHERE '.$filaor['camporev'].'='.$plab14id.' LIMIT 0, 1';
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
		$sWhere='plab14id='.$plab14id.'';
		//$sWhere='plab14oferta='.$plab14oferta.' AND plab14hv='.$plab14hv.'';
		$sSQL='DELETE FROM plab14aplicaofer WHERE '.$sWhere.';';
		$result=$objDB->ejecutasql($sSQL);
		if ($result==false){
			$sError=$ERR['falla_eliminar'].' {2914 aplicacion a oferta}.<!-- '.$sSQL.' -->';
			}else{
			if ($bAudita[4]){
				seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $plab14id, $sSQL, $objDB);
				}
			}
		}
	return array($sError, $sDebug);
	}
function f2914_TablaDetalleV2($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2914='lg/lg_2914_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2914)){$mensajes_2914='lg/lg_2914_es.php';}
	require $mensajes_todas;
	require $mensajes_2914;
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	if (isset($aParametros[0])==0){$aParametros[0]=-1;}
	if (isset($aParametros[101])==0){$aParametros[101]=1;}
	if (isset($aParametros[102])==0){$aParametros[102]=20;}
	//if (isset($aParametros[103])==0){$aParametros[103]='';}
	//$aParametros[103]=numeros_validar($aParametros[103]);
	$aParametros[0]=numeros_validar($aParametros[0]);
	if ($aParametros[0]==''){$aParametros[0]=-1;}
	$sDebug='';
	$plab10id=$aParametros[0];
	$pagina=$aParametros[101];
	$lineastabla=$aParametros[102];
	$babierta=true;
	//$sSQL='SELECT Campo FROM plab10oferta WHERE plab10id='.$plab10id;
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
				$sSQLadd=$sSQLadd.' AND T6.unad11razonsocial LIKE "%'.$sCadena.'%"';
				//$sSQLadd1=$sSQLadd1.'T1.unad11razonsocial LIKE "%'.$sCadena.'%" AND ';
				}
			}
		}
	*/
	$sTitulos='Oferta, Hv, Id, Fechaaplica';
	$sSQL='SELECT TB.plab14oferta, T2.plab01idtercero, TB.plab14id, TB.plab14fechaaplica, TB.plab14hv 
FROM plab14aplicaofer AS TB, plab01hv AS T2 
WHERE '.$sSQLadd1.' TB.plab14oferta='.$plab10id.' AND TB.plab14hv=T2.plab01id '.$sSQLadd.'
ORDER BY TB.plab14hv';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_2914" name="consulta_2914" type="hidden" value="'.$sSQLlista.'"/>
<input id="titulos_2914" name="titulos_2914" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objDB->ejecutasql($sSQL);
	if ($tabladetalle==false){
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 2914: '.$sSQL.'<br>';}
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		//$sLeyenda=$sSQL;
		}else{
		$registros=$objDB->nf($tabladetalle);
		if ($registros==0){
			return array(utf8_encode($sErrConsulta.'<input id="paginaf2914" name="paginaf2914" type="hidden" value="'.$pagina.'"/><input id="lppf2914" name="lppf2914" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
<td><b>'.$ETI['plab14hv'].'</b></td>
<td><b>'.$ETI['plab14fechaaplica'].'</b></td>
<td align="right">
'.html_paginador('paginaf2914', $registros, $lineastabla, $pagina, 'paginarf2914()').'
'.html_lpp('lppf2914', $lineastabla, 'paginarf2914()').'
</td>
</tr>';
	$tlinea=1;
	while($filadet=$objDB->sf($tabladetalle)){
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
		$et_plab14hv=$sPrefijo.cadena_notildes($filadet['plab01idtercero']).$sSufijo;
		$et_plab14fechaaplica='';
		if ($filadet['plab14fechaaplica']!=0){$et_plab14fechaaplica=$sPrefijo.fecha_desdenumero($filadet['plab14fechaaplica']).$sSufijo;}
		if ($babierta){
			$sLink='<a href="javascript:cargaridf2914('.$filadet['plab14id'].')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$res=$res.'<tr'.$sClass.'>
<td>'.$et_plab14hv.'</td>
<td>'.$et_plab14fechaaplica.'</td>
<td>'.$sLink.'</td>
</tr>';
		}
	$res=$res.'</table>';
	$objDB->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
	}
function f2914_Clonar($plab14oferta, $plab14ofertaPadre, $objDB){
	$sError='';
	$plab14id=tabla_consecutivo('plab14aplicaofer', 'plab14id', '', $objDB);
	if ($plab14id==-1){$sError=$objDB->serror;}
	if ($sError==''){
		$sCampos2914='plab14oferta, plab14hv, plab14id, plab14fechaaplica';
		$sValores2914='';
		$sSQL='SELECT * FROM plab14aplicaofer WHERE plab14oferta='.$plab14ofertaPadre.'';
		$tabla=$objDB->ejecutasql($sSQL);
		while($fila=$objDB->sf($tabla)){
			if ($sValores2914!=''){$sValores2914=$sValores2914.', ';}
			$sValores2914=$sValores2914.'('.$plab14oferta.', '.$fila['plab14hv'].', '.$plab14id.', "'.$fila['plab14fechaaplica'].'")';
			$plab14id++;
			}
		if ($sValores2914!=''){
			$sSQL='INSERT INTO plab14aplicaofer('.$sCampos2914.') VALUES '.$sValores2914.'';
			$result=$objDB->ejecutasql($sSQL);
			}
		}
	return $sError;
	}
// -- 2914 aplicacion a oferta XAJAX 
function f2914_Guardar($valores, $aParametros){
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
		list($sError, $iAccion, $plab14id, $sDebugGuardar)=f2914_db_Guardar($valores, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugGuardar;
		}
	$objResponse=new xajaxResponse();
	if ($sError==''){
		list($sdetalle, $sDebugTabla)=f2914_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugTabla;
		$objResponse->assign('div_f2914detalle', 'innerHTML', $sdetalle);
		//if ($iAccion==2){
			//$objResponse->call('cargaridf2914('.$plab14id.')');
			//}else{
			$objResponse->call('limpiaf2914');
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
function f2914_Traer($aParametros){
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
		$plab14oferta=numeros_validar($aParametros[1]);
		$plab14hv=numeros_validar($aParametros[2]);
		if (($plab14oferta!='')&&($plab14hv!='')){$besta=true;}
		}else{
		$plab14id=$aParametros[103];
		if ((int)$plab14id!=0){$besta=true;}
		}
	if ($besta){
		$besta=false;
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		$bHayDb=true;
		$sSQLcondi='';
		if ($paso==1){
			$sSQLcondi=$sSQLcondi.'plab14oferta='.$plab14oferta.' AND plab14hv='.$plab14hv.'';
			}else{
			$sSQLcondi=$sSQLcondi.'plab14id='.$plab14id.'';
			}
		$sSQL='SELECT * FROM plab14aplicaofer WHERE '.$sSQLcondi;
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
		list($plab14hv_nombre, $serror_det)=tabla_campoxid('plab01hv','plab01idtercero','plab01id', $fila['plab14hv'],'{'.$ETI['msg_sindato'].'}', $objDB);
		$html_plab14hv=html_oculto('plab14hv', $fila['plab14hv'], $plab14hv_nombre);
		$objResponse->assign('div_plab14hv', 'innerHTML', $html_plab14hv);
		$plab14id_nombre='';
		$html_plab14id=html_oculto('plab14id', $fila['plab14id'], $plab14id_nombre);
		$objResponse->assign('div_plab14id', 'innerHTML', $html_plab14id);
		$objResponse->assign('plab14fechaaplica', 'value', $fila['plab14fechaaplica']);
		list($iDia, $iMes, $iAgno)=fecha_DividirNumero($fila['plab14fechaaplica'], true);
		$objResponse->assign('plab14fechaaplica_dia', 'value', $iDia);
		$objResponse->assign('plab14fechaaplica_mes', 'value', $iMes);
		$objResponse->assign('plab14fechaaplica_agno', 'value', $iAgno);
		$objResponse->call("MensajeAlarmaV2('', 0)");
		$objResponse->call("verboton('belimina2914','block')");
		}else{
		if ($paso==1){
			$objResponse->assign('plab14hv', 'value', $plab14hv);
			}else{
			$objResponse->call('MensajeAlarmaV2("No se encontro el registro de referencia:'.$plab14id.'", 0)');
			}
		}
	if ($bHayDb){
		$objDB->CerrarConexion();
		}
	return $objResponse;
	}
function f2914_Eliminar($aParametros){
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
	list($sError, $sDebugElimina)=f2914_db_Eliminar($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugElimina;
	$objResponse=new xajaxResponse();
	if ($sError==''){
		list($sDetalle, $sDebugTabla)=f2914_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugTabla;
		$objResponse->assign('div_f2914detalle', 'innerHTML', $sDetalle);
		$objResponse->call('limpiaf2914');
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
function f2914_HtmlTabla($aParametros){
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
	list($sDetalle, $sDebugTabla)=f2914_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f2914detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f2914_PintarLlaves($aParametros){
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
	$html_plab14hv=f2914_HTMLComboV2_plab14hv($objDB, $objCombos, 0);
	$html_plab14id='<input id="plab14id" name="plab14id" type="hidden" value=""/>';
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_plab14hv','innerHTML', $html_plab14hv);
	$objResponse->assign('div_plab14id','innerHTML', $html_plab14id);
	return $objResponse;
	}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------
?>