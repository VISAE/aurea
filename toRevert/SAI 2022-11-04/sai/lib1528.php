<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2019 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.22.7 miércoles, 20 de marzo de 2019
--- 1528 Integrantes
*/
function f1528_db_Guardar($valores, $objDB, $bDebug=false){
	$iCodModulo=1528;
	$bAudita[2]=false;
	$bAudita[3]=false;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_1528='lg/lg_1528_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1528)){$mensajes_1528='lg/lg_1528_es.php';}
	require $mensajes_todas;
	require $mensajes_1528;
	$sError='';
	$sDebug='';
	$bInserta=false;
	$iAccion=3;
	if(!is_array($valores)){$valores=json_decode(str_replace('\"','"',$valores),true);}
	$bita28idequipotrab=numeros_validar($valores[1]);
	$bita28idtercero=numeros_validar($valores[2]);
	$bita28id=numeros_validar($valores[3], true);
	$bita28activo=htmlspecialchars(trim($valores[4]));
	$bita28fechaingreso=$valores[5];
	$bita28fechasalida=$valores[6];
	$sSepara=', ';
	if ($bita28fechasalida==0){
		//$bita28fechasalida=fecha_DiaMod();
		//$sError=$ERR['bita28fechasalida'].$sSepara.$sError;
		}
	if ($bita28fechaingreso==0){
		//$bita28fechaingreso=fecha_DiaMod();
		$sError=$ERR['bita28fechaingreso'].$sSepara.$sError;
		}
	if ($bita28activo==''){$sError=$ERR['bita28activo'].$sSepara.$sError;}
	//if ($bita28id==''){$sError=$ERR['bita28id'].$sSepara.$sError;}//CONSECUTIVO
	if ($bita28idtercero==0){$sError=$ERR['bita28idtercero'].$sSepara.$sError;}
	if ($bita28idequipotrab==''){$sError=$ERR['bita28idequipotrab'].$sSepara.$sError;}
	if ($sError==''){
		list($sError, $sInfo)=tercero_Bloqueado($bita28idtercero, $objDB);
		if ($sInfo!=''){$sError=$sError.'<br>'.$sInfo;}
		}
	if ($sError==''){
		if ((int)$bita28id==0){
			if ($sError==''){
				$sSQL='SELECT bita28idequipotrab FROM bita28eqipoparte WHERE bita28idequipotrab='.$bita28idequipotrab.' AND bita28idtercero="'.$bita28idtercero.'"';
				$result=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($result)!=0){
					$sError=$ERR['existe'];
					}else{
					if (!seg_revisa_permiso($iCodModulo, 2, $objDB)){$sError=$ERR['2'];}
					}
				}
			if ($sError==''){
				$bita28id=tabla_consecutivo('bita28eqipoparte', 'bita28id', '', $objDB);
				if ($bita28id==-1){$sError=$objDB->serror;}
				$bInserta=true;
				$iAccion=2;
				}
			}else{
			if (!seg_revisa_permiso($iCodModulo, 3, $objDB)){$sError=$ERR['3'];}
			}
		}
	if ($sError==''){
		if ($bInserta){
			}
		}
	if ($sError==''){
		if ($bInserta){
			$sCampos1527='bita28idequipotrab, bita28idtercero, bita28id, bita28activo, bita28fechaingreso, 
			bita28fechasalida';
			$sValores1527=''.$bita28idequipotrab.', "'.$bita28idtercero.'", '.$bita28id.', "'.$bita28activo.'", "'.$bita28fechaingreso.'", 
			"'.$bita28fechasalida.'"';
			if ($APP->utf8==1){
				$sSQL='INSERT INTO bita28eqipoparte ('.$sCampos1527.') VALUES ('.utf8_encode($sValores1527).');';
				}else{
				$sSQL='INSERT INTO bita28eqipoparte ('.$sCampos1527.') VALUES ('.$sValores1527.');';
				}
			$result=$objDB->ejecutasql($sSQL);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' {Integrantes}.<!-- '.$sSQL.' -->';
				}else{
				if ($bAudita[2]){
					seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 2, $bita28id, $sSQL, $objDB);
					}
				}
			}else{
			$scampo1528[1]='bita28activo';
			$scampo1528[2]='bita28fechaingreso';
			$scampo1528[3]='bita28fechasalida';
			$svr1528[1]=$bita28activo;
			$svr1528[2]=$bita28fechaingreso;
			$svr1528[3]=$bita28fechasalida;
			$inumcampos=3;
			$sWhere='bita28id='.$bita28id.'';
			//$sWhere='bita28idequipotrab='.$bita28idequipotrab.' AND bita28idtercero="'.$bita28idtercero.'"';
			$sSQL='SELECT * FROM bita28eqipoparte WHERE '.$sWhere;
			$sdatos='';
			$bpasa=false;
			$result=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($result)>0){
				$filaorigen=$objDB->sf($result);
				for ($k=1;$k<=$inumcampos;$k++){
					if ($filaorigen[$scampo1528[$k]]!=$svr1528[$k]){
						if ($sdatos!=''){$sdatos=$sdatos.', ';}
						$sdatos=$sdatos.$scampo1528[$k].'="'.$svr1528[$k].'"';
						$bpasa=true;
						}
					}
				}
			if ($bpasa){
				if ($APP->utf8==1){
					$sSQL='UPDATE bita28eqipoparte SET '.utf8_encode($sdatos).' WHERE '.$sWhere.';';
					}else{
					$sSQL='UPDATE bita28eqipoparte SET '.$sdatos.' WHERE '.$sWhere.';';
					}
				$result=$objDB->ejecutasql($sSQL);
				if ($result==false){
					$sError=$ERR['falla_guardar'].' {Integrantes}. <!-- '.$sSQL.' -->';
					}else{
					if ($bAudita[3]){
						seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 3, $bita28id, $sSQL, $objDB);
						}
					}
				}
			}
		}
	return array($sError, $iAccion, $bita28id, $sDebug);
	}
function f1528_db_Eliminar($aParametros, $objDB, $bDebug=false){
	$iCodModulo=1528;
	$bAudita[4]=false;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_1528='lg/lg_1528_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1528)){$mensajes_1528='lg/lg_1528_es.php';}
	require $mensajes_todas;
	require $mensajes_1528;
	$sError='';
	$sDebug='';
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$bita28idequipotrab=numeros_validar($aParametros[1]);
	$bita28idtercero=numeros_validar($aParametros[2]);
	$bita28id=numeros_validar($aParametros[3]);
	if ($sError==''){
		if (!seg_revisa_permiso($iCodModulo, 4, $objDB)){
			$sError=$ERR['4'];
			}
		}
	if ($sError==''){
		$sSQL='SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=1528';
		$tablaor=$objDB->ejecutasql($sSQL);
		while ($filaor=$objDB->sf($tablaor)){
			$sSQL='SELECT '.$filaor['campoid'].' FROM '.$filaor['tabla'].' WHERE '.$filaor['camporev'].'='.$bita28id.' LIMIT 0, 1';
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
		$sWhere='bita28id='.$bita28id.'';
		//$sWhere='bita28idequipotrab='.$bita28idequipotrab.' AND bita28idtercero="'.$bita28idtercero.'"';
		$sSQL='DELETE FROM bita28eqipoparte WHERE '.$sWhere.';';
		$result=$objDB->ejecutasql($sSQL);
		if ($result==false){
			$sError=$ERR['falla_eliminar'].' {1528 Integrantes}.<!-- '.$sSQL.' -->';
			}else{
			if ($bAudita[4]){
				seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $bita28id, $sSQL, $objDB);
				}
			}
		}
	return array($sError, $sDebug);
	}
function f1528_TablaDetalleV2($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_1528='lg/lg_1528_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1528)){$mensajes_1528='lg/lg_1528_es.php';}
	require $mensajes_todas;
	require $mensajes_1528;
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	if (isset($aParametros[0])==0){$aParametros[0]=-1;}
	if (isset($aParametros[100])==0){$aParametros[100]=$_SESSION['unad_id_tercero'];}
	if (isset($aParametros[101])==0){$aParametros[101]=1;}
	if (isset($aParametros[102])==0){$aParametros[102]=20;}
	//if (isset($aParametros[103])==0){$aParametros[103]='';}
	//if (isset($aParametros[104])==0){$aParametros[104]='';}
	//$aParametros[103]=numeros_validar($aParametros[103]);
	$aParametros[0]=numeros_validar($aParametros[0]);
	if ($aParametros[0]==''){$aParametros[0]=-1;}
	$idTercero=$aParametros[100];
	$sDebug='';
	$bita27id=$aParametros[0];
	$pagina=$aParametros[101];
	$lineastabla=$aParametros[102];
	$bAbierta=true;
	//$sSQL='SELECT Campo FROM bita27equipotrabajo WHERE bita27id='.$bita27id;
	//$tabla=$objDB->ejecutasql($sSQL);
	//if ($objDB->nf($tabla)>0){
		//$fila=$objDB->sf($tabla);
		//if ($fila['Campo']!='S'){$bAbierta=true;}
		//}
	$sLeyenda='';
	if ($sLeyenda!=''){
		$sLeyenda='<div class="salto1px"></div>
		<div class="GrupoCamposAyuda">
		'.$sLeyenda.'
		<div class="salto1px"></div>
		</div>';
		return array($sLeyenda.'<input id="paginaf1528" name="paginaf1528" type="hidden" value="'.$pagina.'"/><input id="lppf1528" name="lppf1528" type="hidden" value="'.$lineastabla.'"/>', $sDebug);
		die();
		}
	/*
	$aEstado=array();
	$sSQL='SELECT id, nombre FROM tabla';
	$tabla=$objDB->ejecutasql($sSQL);
	while($fila=$objDB->sf($tabla)){
		$aEstado[$fila['id']]=cadena_notildes($fila['nombre']);
		}
	*/
	$sSQLadd='';
	$sSQLadd1='';
	//if ((int)$aParametros[103]!=-1){$sSQLadd=$sSQLadd.' AND TB.campo='.$aParametros[103];}
	//if ($aParametros[103]!=''){$sSQLadd=$sSQLadd.' AND TB.campo2 LIKE "%'.$aParametros[103].'%"';}
	/*
	if ($aParametros[103]!=''){
		$sBase=trim(strtoupper($aParametros[103]));
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
	$sTitulos='Equipotrab, Tercero, Id, Activo, Fechaingreso, Fechasalida';
	$sSQL='SELECT T2.unad11razonsocial AS C2_nombre, TB.bita28id, TB.bita28activo, TB.bita28fechaingreso, TB.bita28fechasalida, 
	TB.bita28idtercero, T2.unad11tipodoc AS C2_td, T2.unad11doc AS C2_doc 
	FROM bita28eqipoparte AS TB, unad11terceros AS T2 
	WHERE '.$sSQLadd1.' TB.bita28idequipotrab='.$bita27id.' AND TB.bita28idtercero=T2.unad11id '.$sSQLadd.'
	ORDER BY TB.bita28activo DESC, T2.unad11doc';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_1528" name="consulta_1528" type="hidden" value="'.$sSQLlista.'"/>
	<input id="titulos_1528" name="titulos_1528" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objDB->ejecutasql($sSQL);
	if ($tabladetalle==false){
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 1528: '.$sSQL.'<br>';}
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		//$sLeyenda=$sSQL;
		}else{
		$registros=$objDB->nf($tabladetalle);
		if ($registros==0){
			return array(utf8_encode($sErrConsulta.'<input id="paginaf1528" name="paginaf1528" type="hidden" value="'.$pagina.'"/><input id="lppf1528" name="lppf1528" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
<td colspan="2"><b>'.$ETI['bita28idtercero'].'</b></td>
<td><b>'.$ETI['bita28activo'].'</b></td>
<td><b>'.$ETI['bita28fechaingreso'].'</b></td>
<td><b>'.$ETI['bita28fechasalida'].'</b></td>
<td align="right">
'.html_paginador('paginaf1528', $registros, $lineastabla, $pagina, 'paginarf1528()').'
'.html_lpp('lppf1528', $lineastabla, 'paginarf1528()').'
</td>
</tr>';
	$tlinea=1;
	while($filadet=$objDB->sf($tabladetalle)){
		$sPrefijo='';
		$sSufijo='';
		$sClass='';
		$sLink='';
		$et_bita28activo=$sPrefijo.$ETI['no'].$sSufijo;
		if ($filadet['bita28activo']=='S'){
			$sPrefijo='<b>';
			$sSufijo='</b>';
			$et_bita28activo=$sPrefijo.$ETI['si'].$sSufijo;
			}
		if(($tlinea%2)==0){$sClass=' class="resaltetabla"';}
		$tlinea++;
		$et_bita28idtercero_doc='';
		$et_bita28idtercero_nombre='';
		if ($filadet['bita28idtercero']!=0){
			$et_bita28idtercero_doc=$sPrefijo.$filadet['C2_td'].' '.$filadet['C2_doc'].$sSufijo;
			$et_bita28idtercero_nombre=$sPrefijo.cadena_notildes($filadet['C2_nombre']).$sSufijo;
			}
		$et_bita28fechaingreso='';
		if ($filadet['bita28fechaingreso']!=0){$et_bita28fechaingreso=$sPrefijo.fecha_desdenumero($filadet['bita28fechaingreso']).$sSufijo;}
		$et_bita28fechasalida='';
		if ($filadet['bita28fechasalida']!=0){$et_bita28fechasalida=$sPrefijo.fecha_desdenumero($filadet['bita28fechasalida']).$sSufijo;}
		if ($bAbierta){
			$sLink='<a href="javascript:cargaridf1528('.$filadet['bita28id'].')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$res=$res.'<tr'.$sClass.'>
<td>'.$et_bita28idtercero_doc.'</td>
<td>'.$et_bita28idtercero_nombre.'</td>
<td>'.$et_bita28activo.'</td>
<td>'.$et_bita28fechaingreso.'</td>
<td>'.$et_bita28fechasalida.'</td>
<td>'.$sLink.'</td>
</tr>';
		}
	$res=$res.'</table>';
	$objDB->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
	}
function f1528_Clonar($bita28idequipotrab, $bita28idequipotrabPadre, $objDB){
	$sError='';
	$bita28id=tabla_consecutivo('bita28eqipoparte', 'bita28id', '', $objDB);
	if ($bita28id==-1){$sError=$objDB->serror;}
	if ($sError==''){
		$sCampos1528='bita28idequipotrab, bita28idtercero, bita28id, bita28activo, bita28fechaingreso, bita28fechasalida';
		$sValores1528='';
		$sSQL='SELECT * FROM bita28eqipoparte WHERE bita28idequipotrab='.$bita28idequipotrabPadre.'';
		$tabla=$objDB->ejecutasql($sSQL);
		while($fila=$objDB->sf($tabla)){
			if ($sValores1528!=''){$sValores1528=$sValores1528.', ';}
			$sValores1528=$sValores1528.'('.$bita28idequipotrab.', '.$fila['bita28idtercero'].', '.$bita28id.', "'.$fila['bita28activo'].'", "'.$fila['bita28fechaingreso'].'", "'.$fila['bita28fechasalida'].'")';
			$bita28id++;
			}
		if ($sValores1528!=''){
			$sSQL='INSERT INTO bita28eqipoparte('.$sCampos1528.') VALUES '.$sValores1528.'';
			$result=$objDB->ejecutasql($sSQL);
			}
		}
	return $sError;
	}
// -- 1528 Integrantes XAJAX 
function f1528_Guardar($valores, $aParametros){
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
		list($sError, $iAccion, $bita28id, $sDebugGuardar)=f1528_db_Guardar($valores, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugGuardar;
		}
	$objResponse=new xajaxResponse();
	if ($sError==''){
		list($sdetalle, $sDebugTabla)=f1528_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugTabla;
		$objResponse->assign('div_f1528detalle', 'innerHTML', $sdetalle);
		//if ($iAccion==2){
			//$objResponse->call('cargaridf1528('.$bita28id.')');
			//}else{
			$objResponse->call('limpiaf1528');
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
function f1528_Traer($aParametros){
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
		$bita28idequipotrab=numeros_validar($aParametros[1]);
		$bita28idtercero=numeros_validar($aParametros[2]);
		if (($bita28idequipotrab!='')&&($bita28idtercero!='')){$besta=true;}
		}else{
		$bita28id=$aParametros[103];
		if ((int)$bita28id!=0){$besta=true;}
		}
	if ($besta){
		$besta=false;
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		$bHayDb=true;
		$sSQLcondi='';
		if ($paso==1){
			$sSQLcondi=$sSQLcondi.'bita28idequipotrab='.$bita28idequipotrab.' AND bita28idtercero='.$bita28idtercero.'';
			}else{
			$sSQLcondi=$sSQLcondi.'bita28id='.$bita28id.'';
			}
		$sSQL='SELECT * FROM bita28eqipoparte WHERE '.$sSQLcondi;
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
		$bita28idtercero_id=(int)$fila['bita28idtercero'];
		$bita28idtercero_td=$APP->tipo_doc;
		$bita28idtercero_doc='';
		$bita28idtercero_nombre='';
		if ($bita28idtercero_id!=0){
			list($bita28idtercero_nombre, $bita28idtercero_id, $bita28idtercero_td, $bita28idtercero_doc)=html_tercero($bita28idtercero_td, $bita28idtercero_doc, $bita28idtercero_id, 0, $objDB);
			}
		$html_bita28idtercero_llaves=html_DivTerceroV2('bita28idtercero', $bita28idtercero_td, $bita28idtercero_doc, true, 2, 'Ingrese el documento');
		$objResponse->assign('bita28idtercero', 'value', $bita28idtercero_id);
		$objResponse->assign('div_bita28idtercero_llaves', 'innerHTML', $html_bita28idtercero_llaves);
		$objResponse->assign('div_bita28idtercero', 'innerHTML', $bita28idtercero_nombre);
		$bita28id_nombre='';
		$html_bita28id=html_oculto('bita28id', $fila['bita28id'], $bita28id_nombre);
		$objResponse->assign('div_bita28id', 'innerHTML', $html_bita28id);
		$objResponse->assign('bita28activo', 'value', $fila['bita28activo']);
		$objResponse->assign('bita28fechaingreso', 'value', $fila['bita28fechaingreso']);
		list($iDia, $iMes, $iAgno)=fecha_DividirNumero($fila['bita28fechaingreso'], true);
		$objResponse->assign('bita28fechaingreso_dia', 'value', $iDia);
		$objResponse->assign('bita28fechaingreso_mes', 'value', $iMes);
		$objResponse->assign('bita28fechaingreso_agno', 'value', $iAgno);
		$objResponse->assign('bita28fechasalida', 'value', $fila['bita28fechasalida']);
		list($iDia, $iMes, $iAgno)=fecha_DividirNumero($fila['bita28fechasalida'], true);
		$objResponse->assign('bita28fechasalida_dia', 'value', $iDia);
		$objResponse->assign('bita28fechasalida_mes', 'value', $iMes);
		$objResponse->assign('bita28fechasalida_agno', 'value', $iAgno);
		$objResponse->call("MensajeAlarmaV2('', 0)");
		$objResponse->call("verboton('belimina1528','block')");
		}else{
		if ($paso==1){
			}else{
			$objResponse->call('MensajeAlarmaV2("No se encontro el registro de referencia:'.$bita28id.'", 0)');
			}
		}
	if ($bHayDb){
		$objDB->CerrarConexion();
		}
	return $objResponse;
	}
function f1528_Eliminar($aParametros){
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
	list($sError, $sDebugElimina)=f1528_db_Eliminar($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugElimina;
	$objResponse=new xajaxResponse();
	if ($sError==''){
		list($sDetalle, $sDebugTabla)=f1528_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugTabla;
		$objResponse->assign('div_f1528detalle', 'innerHTML', $sDetalle);
		$objResponse->call('limpiaf1528');
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
function f1528_HtmlTabla($aParametros){
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
	list($sDetalle, $sDebugTabla)=f1528_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f1528detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f1528_PintarLlaves($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	//if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	if (isset($APP->piel)==0){$APP->piel=1;}
	$iPiel=$APP->piel;
	$bita28idtercero=0;
	$bita28idtercero_rs='';
	$html_bita28idtercero_llaves=html_DivTerceroV2('bita28idtercero', $APP->tipo_doc, '', false, 2, $ETI['ing_doc']);
	$html_bita28id='<input id="bita28id" name="bita28id" type="hidden" value=""/>';
	$objResponse=new xajaxResponse();
	$objResponse->assign('bita28idtercero','value', $bita28idtercero);
	$objResponse->assign('div_bita28idtercero_llaves','innerHTML', $html_bita28idtercero_llaves);
	$objResponse->assign('div_bita28idtercero','innerHTML', $bita28idtercero_rs);
	$objResponse->assign('div_bita28id','innerHTML', $html_bita28id);
	return $objResponse;
	}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------
?>