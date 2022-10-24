<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2019 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.22.7 martes, 19 de marzo de 2019
--- 2224 Cambios de actores
*/
function f2224_db_Guardar($valores, $objDB, $bDebug=false){
	$iCodModulo=2224;
	$bAudita[2]=false;
	$bAudita[3]=false;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2224='lg/lg_2224_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2224)){$mensajes_2224='lg/lg_2224_es.php';}
	require $mensajes_todas;
	require $mensajes_2224;
	$sError='';
	$sDebug='';
	$binserta=false;
	$iAccion=3;
	if(!is_array($valores)){$valores=json_decode(str_replace('\"','"',$valores),true);}
	$core23idestprograma=numeros_validar($valores[1]);
	$core23consec=numeros_validar($valores[2]);
	$core23id=numeros_validar($valores[3], true);
	$core23idactor=numeros_validar($valores[4]);
	$core23idtercerosale=numeros_validar($valores[5]);
	$core23idterceroentra=numeros_validar($valores[6]);
	$core23fecha=$valores[7];
	//if ($core23idactor==''){$core23idactor=0;}
	$sSepara=', ';
	if ($core23fecha==0){
		//$core23fecha=fecha_DiaMod();
		$sError=$ERR['core23fecha'].$sSepara.$sError;
		}
	if ($core23idterceroentra==0){$sError=$ERR['core23idterceroentra'].$sSepara.$sError;}
	if ($core23idtercerosale==0){$sError=$ERR['core23idtercerosale'].$sSepara.$sError;}
	if ($core23idactor==''){$sError=$ERR['core23idactor'].$sSepara.$sError;}
	//if ($core23id==''){$sError=$ERR['core23id'].$sSepara.$sError;}//CONSECUTIVO
	//if ($core23consec==''){$sError=$ERR['core23consec'].$sSepara.$sError;}//CONSECUTIVO
	if ($core23idestprograma==''){$sError=$ERR['core23idestprograma'].$sSepara.$sError;}
	if ($sError==''){
		list($sError, $sInfo)=tercero_Bloqueado($core23idterceroentra, $objDB);
		if ($sInfo!=''){$sError=$sError.'<br>'.sInfo;}
		}
	if ($sError==''){
		list($sError, $sInfo)=tercero_Bloqueado($core23idtercerosale, $objDB);
		if ($sInfo!=''){$sError=$sError.'<br>'.sInfo;}
		}
	if ($sError==''){
		if ((int)$core23id==0){
			if ((int)$core23consec==0){
				$core23consec=tabla_consecutivo('core23gradohistorialactor', 'core23consec', 'core23idestprograma='.$core23idestprograma.'', $objDB);
				if ($core23consec==-1){$sError=$objDB->serror;}
				}else{
				if (!seg_revisa_permiso($iCodModulo, 8, $objDB)){$sError=$ERR['8'];}
				}
			if ($sError==''){
				$sSQL='SELECT core23idestprograma FROM core23gradohistorialactor WHERE core23idestprograma='.$core23idestprograma.' AND core23consec='.$core23consec.'';
				$result=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($result)!=0){
					$sError=$ERR['existe'];
					}else{
					if (!seg_revisa_permiso($iCodModulo, 2, $objDB)){$sError=$ERR['2'];}
					}
				}
			if ($sError==''){
				$core23id=tabla_consecutivo('core23gradohistorialactor', 'core23id', '', $objDB);
				if ($core23id==-1){$sError=$objDB->serror;}
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
			$scampos='core23idestprograma, core23consec, core23id, core23idactor, core23idtercerosale, 
core23idterceroentra, core23fecha';
			$svalores=''.$core23idestprograma.', '.$core23consec.', '.$core23id.', '.$core23idactor.', "'.$core23idtercerosale.'", 
"'.$core23idterceroentra.'", "'.$core23fecha.'"';
			if ($APP->utf8==1){
				$sSQL='INSERT INTO core23gradohistorialactor ('.$scampos.') VALUES ('.utf8_encode($svalores).');';
				}else{
				$sSQL='INSERT INTO core23gradohistorialactor ('.$scampos.') VALUES ('.$svalores.');';
				}
			$result=$objDB->ejecutasql($sSQL);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' {Cambios de actores}.<!-- '.$sSQL.' -->';
				}else{
				if ($bAudita[2]){
					seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 2, $core23id, $sSQL, $objDB);
					}
				}
			}else{
			$scampo2224[1]='core23idactor';
			$scampo2224[2]='core23idtercerosale';
			$scampo2224[3]='core23idterceroentra';
			$scampo2224[4]='core23fecha';
			$svr2224[1]=$core23idactor;
			$svr2224[2]=$core23idtercerosale;
			$svr2224[3]=$core23idterceroentra;
			$svr2224[4]=$core23fecha;
			$inumcampos=4;
			$sWhere='core23id='.$core23id.'';
			//$sWhere='core23idestprograma='.$core23idestprograma.' AND core23consec='.$core23consec.'';
			$sSQL='SELECT * FROM core23gradohistorialactor WHERE '.$sWhere;
			$sdatos='';
			$bpasa=false;
			$result=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($result)>0){
				$filaorigen=$objDB->sf($result);
				for ($k=1;$k<=$inumcampos;$k++){
					if ($filaorigen[$scampo2224[$k]]!=$svr2224[$k]){
						if ($sdatos!=''){$sdatos=$sdatos.', ';}
						$sdatos=$sdatos.$scampo2224[$k].'="'.$svr2224[$k].'"';
						$bpasa=true;
						}
					}
				}
			if ($bpasa){
				if ($APP->utf8==1){
					$sSQL='UPDATE core23gradohistorialactor SET '.utf8_encode($sdatos).' WHERE '.$sWhere.';';
					}else{
					$sSQL='UPDATE core23gradohistorialactor SET '.$sdatos.' WHERE '.$sWhere.';';
					}
				$result=$objDB->ejecutasql($sSQL);
				if ($result==false){
					$sError=$ERR['falla_guardar'].' {Cambios de actores}. <!-- '.$sSQL.' -->';
					}else{
					if ($bAudita[3]){
						seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 3, $core23id, $sSQL, $objDB);
						}
					}
				}
			}
		}
	return array($sError, $iAccion, $core23id, $sDebug);
	}
function f2224_db_Eliminar($aParametros, $objDB, $bDebug=false){
	$iCodModulo=2224;
	$bAudita[4]=false;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2224='lg/lg_2224_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2224)){$mensajes_2224='lg/lg_2224_es.php';}
	require $mensajes_todas;
	require $mensajes_2224;
	$sError='';
	$sDebug='';
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$core23idestprograma=numeros_validar($aParametros[1]);
	$core23consec=numeros_validar($aParametros[2]);
	$core23id=numeros_validar($aParametros[3]);
	if ($sError==''){
		if (!seg_revisa_permiso($iCodModulo, 4, $objDB)){
			$sError=$ERR['4'];
			}
		}
	if ($sError==''){
		$sSQL='SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=2224';
		$tablaor=$objDB->ejecutasql($sSQL);
		while ($filaor=$objDB->sf($tablaor)){
			$sSQL='SELECT '.$filaor['campoid'].' FROM '.$filaor['tabla'].' WHERE '.$filaor['camporev'].'='.$core23id.' LIMIT 0, 1';
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
		$sWhere='core23id='.$core23id.'';
		//$sWhere='core23idestprograma='.$core23idestprograma.' AND core23consec='.$core23consec.'';
		$sSQL='DELETE FROM core23gradohistorialactor WHERE '.$sWhere.';';
		$result=$objDB->ejecutasql($sSQL);
		if ($result==false){
			$sError=$ERR['falla_eliminar'].' {2224 Cambios de actores}.<!-- '.$sSQL.' -->';
			}else{
			if ($bAudita[4]){
				seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $core23id, $sSQL, $objDB);
				}
			}
		}
	return array($sError, $sDebug);
	}
function f2224_TablaDetalleV2($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2224='lg/lg_2224_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2224)){$mensajes_2224='lg/lg_2224_es.php';}
	require $mensajes_todas;
	require $mensajes_2224;
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	if (isset($aParametros[0])==0){$aParametros[0]=-1;}
	if (isset($aParametros[101])==0){$aParametros[101]=1;}
	if (isset($aParametros[102])==0){$aParametros[102]=20;}
	//if (isset($aParametros[103])==0){$aParametros[103]='';}
	//$aParametros[103]=numeros_validar($aParametros[103]);
	$aParametros[0]=numeros_validar($aParametros[0]);
	if ($aParametros[0]==''){$aParametros[0]=-1;}
	$sDebug='';
	$core01id=$aParametros[0];
	$pagina=$aParametros[101];
	$lineastabla=$aParametros[102];
	$babierta=true;
	//$sSQL='SELECT Campo FROM core01estprograma WHERE core01id='.$core01id;
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
	$sTitulos='Estprograma, Consec, Id, Actor, Tercerosale, Terceroentra, Fecha';
	$sSQL='SELECT TB.core23idestprograma, TB.core23consec, TB.core23id, T4.core31nombre, T5.unad11razonsocial AS C5_nombre, T6.unad11razonsocial AS C6_nombre, TB.core23fecha, TB.core23idactor, TB.core23idtercerosale, T5.unad11tipodoc AS C5_td, T5.unad11doc AS C5_doc, TB.core23idterceroentra, T6.unad11tipodoc AS C6_td, T6.unad11doc AS C6_doc 
FROM core23gradohistorialactor AS TB, core31gradoactor AS T4, unad11terceros AS T5, unad11terceros AS T6 
WHERE '.$sSQLadd1.' TB.core23idestprograma='.$core01id.' AND TB.core23idactor=T4.core31id AND TB.core23idtercerosale=T5.unad11id AND TB.core23idterceroentra=T6.unad11id '.$sSQLadd.'
ORDER BY TB.core23consec';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_2224" name="consulta_2224" type="hidden" value="'.$sSQLlista.'"/>
<input id="titulos_2224" name="titulos_2224" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objDB->ejecutasql($sSQL);
	if ($tabladetalle==false){
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 2224: '.$sSQL.'<br>';}
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		//$sLeyenda=$sSQL;
		}else{
		$registros=$objDB->nf($tabladetalle);
		if ($registros==0){
			return array(utf8_encode($sErrConsulta.'<input id="paginaf2224" name="paginaf2224" type="hidden" value="'.$pagina.'"/><input id="lppf2224" name="lppf2224" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
<td><b>'.$ETI['core23consec'].'</b></td>
<td><b>'.$ETI['core23idactor'].'</b></td>
<td colspan="2"><b>'.$ETI['core23idtercerosale'].'</b></td>
<td colspan="2"><b>'.$ETI['core23idterceroentra'].'</b></td>
<td><b>'.$ETI['core23fecha'].'</b></td>
<td align="right">
'.html_paginador('paginaf2224', $registros, $lineastabla, $pagina, 'paginarf2224()').'
'.html_lpp('lppf2224', $lineastabla, 'paginarf2224()').'
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
		$et_core23consec=$sPrefijo.$filadet['core23consec'].$sSufijo;
		$et_core23idactor=$sPrefijo.cadena_notildes($filadet['core31nombre']).$sSufijo;
		$et_core23idtercerosale=$sPrefijo.$filadet['core23idtercerosale'].$sSufijo;
		$et_core23idterceroentra=$sPrefijo.$filadet['core23idterceroentra'].$sSufijo;
		$et_core23fecha='';
		if ($filadet['core23fecha']!=0){$et_core23fecha=$sPrefijo.fecha_desdenumero($filadet['core23fecha']).$sSufijo;}
		if ($babierta){
			$sLink='<a href="javascript:cargaridf2224('.$filadet['core23id'].')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$res=$res.'<tr'.$sClass.'>
<td>'.$et_core23consec.'</td>
<td>'.$et_core23idactor.'</td>
<td>'.$sPrefijo.$filadet['C5_td'].' '.$filadet['C5_doc'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['C5_nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['C6_td'].' '.$filadet['C6_doc'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['C6_nombre']).$sSufijo.'</td>
<td>'.$et_core23fecha.'</td>
<td>'.$sLink.'</td>
</tr>';
		}
	$res=$res.'</table>';
	$objDB->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
	}
function f2224_Clonar($core23idestprograma, $core23idestprogramaPadre, $objDB){
	$sError='';
	$core23consec=tabla_consecutivo('core23gradohistorialactor', 'core23consec', 'core23idestprograma='.$core23idestprograma.'', $objDB);
	if ($core23consec==-1){$sError=$objDB->serror;}
	$core23id=tabla_consecutivo('core23gradohistorialactor', 'core23id', '', $objDB);
	if ($core23id==-1){$sError=$objDB->serror;}
	if ($sError==''){
		$sCampos2224='core23idestprograma, core23consec, core23id, core23idactor, core23idtercerosale, core23idterceroentra, core23fecha';
		$sValores2224='';
		$sSQL='SELECT * FROM core23gradohistorialactor WHERE core23idestprograma='.$core23idestprogramaPadre.'';
		$tabla=$objDB->ejecutasql($sSQL);
		while($fila=$objDB->sf($tabla)){
			if ($sValores2224!=''){$sValores2224=$sValores2224.', ';}
			$sValores2224=$sValores2224.'('.$core23idestprograma.', '.$core23consec.', '.$core23id.', '.$fila['core23idactor'].', '.$fila['core23idtercerosale'].', '.$fila['core23idterceroentra'].', "'.$fila['core23fecha'].'")';
			$core23consec++;
			$core23id++;
			}
		if ($sValores2224!=''){
			$sSQL='INSERT INTO core23gradohistorialactor('.$sCampos2224.') VALUES '.$sValores2224.'';
			$result=$objDB->ejecutasql($sSQL);
			}
		}
	return $sError;
	}
// -- 2224 Cambios de actores XAJAX 
function f2224_Guardar($valores, $aParametros){
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
		list($sError, $iAccion, $core23id, $sDebugGuardar)=f2224_db_Guardar($valores, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugGuardar;
		}
	$objResponse=new xajaxResponse();
	if ($sError==''){
		list($sdetalle, $sDebugTabla)=f2224_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugTabla;
		$objResponse->assign('div_f2224detalle', 'innerHTML', $sdetalle);
		//if ($iAccion==2){
			//$objResponse->call('cargaridf2224('.$core23id.')');
			//}else{
			$objResponse->call('limpiaf2224');
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
function f2224_Traer($aParametros){
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
		$core23idestprograma=numeros_validar($aParametros[1]);
		$core23consec=numeros_validar($aParametros[2]);
		if (($core23idestprograma!='')&&($core23consec!='')){$besta=true;}
		}else{
		$core23id=$aParametros[103];
		if ((int)$core23id!=0){$besta=true;}
		}
	if ($besta){
		$besta=false;
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		$bHayDb=true;
		$sSQLcondi='';
		if ($paso==1){
			$sSQLcondi=$sSQLcondi.'core23idestprograma='.$core23idestprograma.' AND core23consec='.$core23consec.'';
			}else{
			$sSQLcondi=$sSQLcondi.'core23id='.$core23id.'';
			}
		$sSQL='SELECT * FROM core23gradohistorialactor WHERE '.$sSQLcondi;
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
		$core23idtercerosale_id=(int)$fila['core23idtercerosale'];
		$core23idtercerosale_td=$APP->tipo_doc;
		$core23idtercerosale_doc='';
		$core23idtercerosale_nombre='';
		if ($core23idtercerosale_id!=0){
			list($core23idtercerosale_nombre, $core23idtercerosale_id, $core23idtercerosale_td, $core23idtercerosale_doc)=html_tercero($core23idtercerosale_td, $core23idtercerosale_doc, $core23idtercerosale_id, 0, $objDB);
			}
		$core23idterceroentra_id=(int)$fila['core23idterceroentra'];
		$core23idterceroentra_td=$APP->tipo_doc;
		$core23idterceroentra_doc='';
		$core23idterceroentra_nombre='';
		if ($core23idterceroentra_id!=0){
			list($core23idterceroentra_nombre, $core23idterceroentra_id, $core23idterceroentra_td, $core23idterceroentra_doc)=html_tercero($core23idterceroentra_td, $core23idterceroentra_doc, $core23idterceroentra_id, 0, $objDB);
			}
		$core23consec_nombre='';
		$html_core23consec=html_oculto('core23consec', $fila['core23consec'], $core23consec_nombre);
		$objResponse->assign('div_core23consec', 'innerHTML', $html_core23consec);
		$core23id_nombre='';
		$html_core23id=html_oculto('core23id', $fila['core23id'], $core23id_nombre);
		$objResponse->assign('div_core23id', 'innerHTML', $html_core23id);
		$objResponse->assign('core23idactor', 'value', $fila['core23idactor']);
		$objResponse->assign('core23idtercerosale', 'value', $fila['core23idtercerosale']);
		$objResponse->assign('core23idtercerosale_td', 'value', $core23idtercerosale_td);
		$objResponse->assign('core23idtercerosale_doc', 'value', $core23idtercerosale_doc);
		$objResponse->assign('div_core23idtercerosale', 'innerHTML', $core23idtercerosale_nombre);
		$objResponse->assign('core23idterceroentra', 'value', $fila['core23idterceroentra']);
		$objResponse->assign('core23idterceroentra_td', 'value', $core23idterceroentra_td);
		$objResponse->assign('core23idterceroentra_doc', 'value', $core23idterceroentra_doc);
		$objResponse->assign('div_core23idterceroentra', 'innerHTML', $core23idterceroentra_nombre);
		$objResponse->assign('core23fecha', 'value', $fila['core23fecha']);
		list($iDia, $iMes, $iAgno)=fecha_DividirNumero($fila['core23fecha'], true);
		$objResponse->assign('core23fecha_dia', 'value', $iDia);
		$objResponse->assign('core23fecha_mes', 'value', $iMes);
		$objResponse->assign('core23fecha_agno', 'value', $iAgno);
		$objResponse->call("MensajeAlarmaV2('', 0)");
		$objResponse->call("verboton('belimina2224','block')");
		}else{
		if ($paso==1){
			$objResponse->assign('core23consec', 'value', $core23consec);
			}else{
			$objResponse->call('MensajeAlarmaV2("No se encontro el registro de referencia:'.$core23id.'", 0)');
			}
		}
	if ($bHayDb){
		$objDB->CerrarConexion();
		}
	return $objResponse;
	}
function f2224_Eliminar($aParametros){
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
	list($sError, $sDebugElimina)=f2224_db_Eliminar($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugElimina;
	$objResponse=new xajaxResponse();
	if ($sError==''){
		list($sDetalle, $sDebugTabla)=f2224_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugTabla;
		$objResponse->assign('div_f2224detalle', 'innerHTML', $sDetalle);
		$objResponse->call('limpiaf2224');
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
function f2224_HtmlTabla($aParametros){
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
	list($sDetalle, $sDebugTabla)=f2224_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f2224detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f2224_PintarLlaves($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	//if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	if (isset($APP->piel)==0){$APP->piel=1;}
	$iPiel=$APP->piel;
	$html_core23consec='<input id="core23consec" name="core23consec" type="text" value="" onchange="revisaf2224()" class="cuatro"/>';
	$html_core23id='<input id="core23id" name="core23id" type="hidden" value=""/>';
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_core23consec','innerHTML', $html_core23consec);
	$objResponse->assign('div_core23id','innerHTML', $html_core23id);
	return $objResponse;
	}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------
?>