<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2019 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.22.7 viernes, 22 de marzo de 2019
--- 1530 Cambios de responsable
*/
function f1530_db_Guardar($valores, $objDB, $bDebug=false){
	$iCodModulo=1530;
	$bAudita[2]=false;
	$bAudita[3]=false;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_1530=$APP->rutacomun.'lg/lg_1530_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1530)){$mensajes_1530=$APP->rutacomun.'lg/lg_1530_es.php';}
	require $mensajes_todas;
	require $mensajes_1530;
	$sError='';
	$sDebug='';
	$binserta=false;
	$iAccion=3;
	if(!is_array($valores)){$valores=json_decode(str_replace('\"','"',$valores),true);}
	$bita30idbitacora=numeros_validar($valores[1]);
	$bita30consec=numeros_validar($valores[2]);
	$bita30id=numeros_validar($valores[3], true);
	$bita30idresponsable=numeros_validar($valores[4]);
	$bita30fechafin=$valores[5];
	$bita30horafin=numeros_validar($valores[6]);
	$bita30nota=htmlspecialchars(trim($valores[7]));
	//if ($bita30horafin==''){$bita30horafin=0;}
	$sSepara=', ';
	if ($bita30nota==''){$sError=$ERR['bita30nota'].$sSepara.$sError;}
	if ($bita30horafin==''){$sError=$ERR['bita30horafin'].$sSepara.$sError;}
	if ($bita30fechafin==0){
		//$bita30fechafin=fecha_DiaMod();
		$sError=$ERR['bita30fechafin'].$sSepara.$sError;
		}
	if ($bita30idresponsable==0){$sError=$ERR['bita30idresponsable'].$sSepara.$sError;}
	//if ($bita30id==''){$sError=$ERR['bita30id'].$sSepara.$sError;}//CONSECUTIVO
	//if ($bita30consec==''){$sError=$ERR['bita30consec'].$sSepara.$sError;}//CONSECUTIVO
	if ($bita30idbitacora==''){$sError=$ERR['bita30idbitacora'].$sSepara.$sError;}
	if ($sError==''){
		list($sError, $sInfo)=tercero_Bloqueado($bita30idresponsable, $objDB);
		if ($sInfo!=''){$sError=$sError.'<br>'.sInfo;}
		}
	if ($sError==''){
		if ((int)$bita30id==0){
			if ((int)$bita30consec==0){
				$bita30consec=tabla_consecutivo('bita30bitahistrespon', 'bita30consec', 'bita30idbitacora='.$bita30idbitacora.'', $objDB);
				if ($bita30consec==-1){$sError=$objDB->serror;}
				}else{
				if (!seg_revisa_permiso($iCodModulo, 8, $objDB)){$sError=$ERR['8'];}
				}
			if ($sError==''){
				$sSQL='SELECT bita30idbitacora FROM bita30bitahistrespon WHERE bita30idbitacora='.$bita30idbitacora.' AND bita30consec='.$bita30consec.'';
				$result=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($result)!=0){
					$sError=$ERR['existe'];
					}else{
					if (!seg_revisa_permiso($iCodModulo, 2, $objDB)){$sError=$ERR['2'];}
					}
				}
			if ($sError==''){
				$bita30id=tabla_consecutivo('bita30bitahistrespon', 'bita30id', '', $objDB);
				if ($bita30id==-1){$sError=$objDB->serror;}
				$binserta=true;
				$iAccion=2;
				}
			}else{
			if (!seg_revisa_permiso($iCodModulo, 3, $objDB)){$sError=$ERR['3'];}
			}
		}
	if ($sError==''){
		if ($binserta){
			$bita30horafin=0;
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
		//Si el campo bita30nota permite html quite la linea htmlspecialchars para el campo y habilite la siguiente linea:
		//$bita30nota=str_replace('"', '\"', $bita30nota);
		$bita30nota=str_replace('"', '\"', $bita30nota);
		if ($binserta){
			$scampos='bita30idbitacora, bita30consec, bita30id, bita30idresponsable, bita30fechafin, 
bita30horafin, bita30nota';
			$svalores=''.$bita30idbitacora.', '.$bita30consec.', '.$bita30id.', "'.$bita30idresponsable.'", "'.$bita30fechafin.'", 
'.$bita30horafin.', "'.$bita30nota.'"';
			if ($APP->utf8==1){
				$sSQL='INSERT INTO bita30bitahistrespon ('.$scampos.') VALUES ('.utf8_encode($svalores).');';
				}else{
				$sSQL='INSERT INTO bita30bitahistrespon ('.$scampos.') VALUES ('.$svalores.');';
				}
			$result=$objDB->ejecutasql($sSQL);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' {Cambios de responsable}.<!-- '.$sSQL.' -->';
				}else{
				if ($bAudita[2]){
					seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 2, $bita30id, $sSQL, $objDB);
					}
				}
			}else{
			$scampo1530[1]='bita30idresponsable';
			$scampo1530[2]='bita30fechafin';
			$scampo1530[3]='bita30nota';
			$svr1530[1]=$bita30idresponsable;
			$svr1530[2]=$bita30fechafin;
			$svr1530[3]=$bita30nota;
			$inumcampos=3;
			$sWhere='bita30id='.$bita30id.'';
			//$sWhere='bita30idbitacora='.$bita30idbitacora.' AND bita30consec='.$bita30consec.'';
			$sSQL='SELECT * FROM bita30bitahistrespon WHERE '.$sWhere;
			$sdatos='';
			$bpasa=false;
			$result=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($result)>0){
				$filaorigen=$objDB->sf($result);
				for ($k=1;$k<=$inumcampos;$k++){
					if ($filaorigen[$scampo1530[$k]]!=$svr1530[$k]){
						if ($sdatos!=''){$sdatos=$sdatos.', ';}
						$sdatos=$sdatos.$scampo1530[$k].'="'.$svr1530[$k].'"';
						$bpasa=true;
						}
					}
				}
			if ($bpasa){
				if ($APP->utf8==1){
					$sSQL='UPDATE bita30bitahistrespon SET '.utf8_encode($sdatos).' WHERE '.$sWhere.';';
					}else{
					$sSQL='UPDATE bita30bitahistrespon SET '.$sdatos.' WHERE '.$sWhere.';';
					}
				$result=$objDB->ejecutasql($sSQL);
				if ($result==false){
					$sError=$ERR['falla_guardar'].' {Cambios de responsable}. <!-- '.$sSQL.' -->';
					}else{
					if ($bAudita[3]){
						seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 3, $bita30id, $sSQL, $objDB);
						}
					}
				}
			}
		}
	return array($sError, $iAccion, $bita30id, $sDebug);
	}
function f1530_db_Eliminar($aParametros, $objDB, $bDebug=false){
	$iCodModulo=1530;
	$bAudita[4]=false;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_1530=$APP->rutacomun.'lg/lg_1530_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1530)){$mensajes_1530=$APP->rutacomun.'lg/lg_1530_es.php';}
	require $mensajes_todas;
	require $mensajes_1530;
	$sError='';
	$sDebug='';
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$bita30idbitacora=numeros_validar($aParametros[1]);
	$bita30consec=numeros_validar($aParametros[2]);
	$bita30id=numeros_validar($aParametros[3]);
	if ($sError==''){
		if (!seg_revisa_permiso($iCodModulo, 4, $objDB)){
			$sError=$ERR['4'];
			}
		}
	if ($sError==''){
		$sSQL='SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=1530';
		$tablaor=$objDB->ejecutasql($sSQL);
		while ($filaor=$objDB->sf($tablaor)){
			$sSQL='SELECT '.$filaor['campoid'].' FROM '.$filaor['tabla'].' WHERE '.$filaor['camporev'].'='.$bita30id.' LIMIT 0, 1';
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
		$sWhere='bita30id='.$bita30id.'';
		//$sWhere='bita30idbitacora='.$bita30idbitacora.' AND bita30consec='.$bita30consec.'';
		$sSQL='DELETE FROM bita30bitahistrespon WHERE '.$sWhere.';';
		$result=$objDB->ejecutasql($sSQL);
		if ($result==false){
			$sError=$ERR['falla_eliminar'].' {1530 Cambios de responsable}.<!-- '.$sSQL.' -->';
			}else{
			if ($bAudita[4]){
				seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $bita30id, $sSQL, $objDB);
				}
			}
		}
	return array($sError, $sDebug);
	}
function f1530_TablaDetalleV2($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_1530=$APP->rutacomun.'lg/lg_1530_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1530)){$mensajes_1530=$APP->rutacomun.'lg/lg_1530_es.php';}
	require $mensajes_todas;
	require $mensajes_1530;
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	if (isset($aParametros[0])==0){$aParametros[0]=-1;}
	if (isset($aParametros[101])==0){$aParametros[101]=1;}
	if (isset($aParametros[102])==0){$aParametros[102]=20;}
	//if (isset($aParametros[103])==0){$aParametros[103]='';}
	//$aParametros[103]=numeros_validar($aParametros[103]);
	$aParametros[0]=numeros_validar($aParametros[0]);
	if ($aParametros[0]==''){$aParametros[0]=-1;}
	$sDebug='';
	$bita04id=$aParametros[0];
	$pagina=$aParametros[101];
	$lineastabla=$aParametros[102];
	$babierta=false;
	//$sSQL='SELECT Campo FROM bita04bitacora WHERE bita04id='.$bita04id;
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
	$sTitulos='Bitacora, Consec, Id, Responsable, Fechafin, Horafin, Nota';
	$sSQL='SELECT TB.bita30idbitacora, TB.bita30consec, TB.bita30id, T4.unad11razonsocial AS C4_nombre, TB.bita30fechafin, TB.bita30horafin, TB.bita30nota, TB.bita30idresponsable, T4.unad11tipodoc AS C4_td, T4.unad11doc AS C4_doc 
FROM bita30bitahistrespon AS TB, unad11terceros AS T4 
WHERE '.$sSQLadd1.' TB.bita30idbitacora='.$bita04id.' AND TB.bita30idresponsable=T4.unad11id '.$sSQLadd.'
ORDER BY TB.bita30consec DESC';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_1530" name="consulta_1530" type="hidden" value="'.$sSQLlista.'"/>
<input id="titulos_1530" name="titulos_1530" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objDB->ejecutasql($sSQL);
	if ($tabladetalle==false){
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 1530: '.$sSQL.'<br>';}
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		//$sLeyenda=$sSQL;
		}else{
		$registros=$objDB->nf($tabladetalle);
		if ($registros==0){
			return array(utf8_encode($sErrConsulta.'<input id="paginaf1530" name="paginaf1530" type="hidden" value="'.$pagina.'"/><input id="lppf1530" name="lppf1530" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
<td><b>'.$ETI['bita30consec'].'</b></td>
<td colspan="2"><b>'.$ETI['bita30idresponsable'].'</b></td>
<td colspan="2"><b>'.$ETI['bita30fechafin'].'</b></td>
<td align="right">
'.html_paginador('paginaf1530', $registros, $lineastabla, $pagina, 'paginarf1530()').'
'.html_lpp('lppf1530', $lineastabla, 'paginarf1530()').'
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
		$et_bita30consec=$sPrefijo.$filadet['bita30consec'].$sSufijo;
		$et_bita30idresponsable=$sPrefijo.$filadet['bita30idresponsable'].$sSufijo;
		$et_bita30fechafin='';
		if ($filadet['bita30fechafin']!=0){$et_bita30fechafin=$sPrefijo.fecha_desdenumero($filadet['bita30fechafin']).$sSufijo;}
		$et_bita30horafin=$sPrefijo.html_TablaHoraMinDesdeNumero($filadet['bita30horafin']).$sSufijo;
		if ($babierta){
			$sLink='<a href="javascript:cargaridf1530('.$filadet['bita30id'].')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$res=$res.'<tr'.$sClass.'>
<td>'.$et_bita30consec.'</td>
<td>'.$sPrefijo.$filadet['C4_td'].' '.$filadet['C4_doc'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['C4_nombre']).$sSufijo.'</td>
<td>'.$et_bita30fechafin.'</td>
<td>'.$et_bita30horafin.'</td>
<td>'.$sLink.'</td>
</tr>';
		if ($filadet['bita30nota']!=''){
			$et_bita30nota=$sPrefijo.cadena_notildes($filadet['bita30nota']).$sSufijo;
			$res=$res.'<tr'.$sClass.'>
<td></td>
<td colspan="5">'.$ETI['bita30nota'].': '.$et_bita30nota.'</td>
</tr>';
			}
		}
	$res=$res.'</table>';
	$objDB->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
	}
function f1530_Clonar($bita30idbitacora, $bita30idbitacoraPadre, $objDB){
	$sError='';
	$bita30consec=tabla_consecutivo('bita30bitahistrespon', 'bita30consec', 'bita30idbitacora='.$bita30idbitacora.'', $objDB);
	if ($bita30consec==-1){$sError=$objDB->serror;}
	$bita30id=tabla_consecutivo('bita30bitahistrespon', 'bita30id', '', $objDB);
	if ($bita30id==-1){$sError=$objDB->serror;}
	if ($sError==''){
		$sCampos1530='bita30idbitacora, bita30consec, bita30id, bita30idresponsable, bita30fechafin, bita30horafin, bita30nota';
		$sValores1530='';
		$sSQL='SELECT * FROM bita30bitahistrespon WHERE bita30idbitacora='.$bita30idbitacoraPadre.'';
		$tabla=$objDB->ejecutasql($sSQL);
		while($fila=$objDB->sf($tabla)){
			if ($sValores1530!=''){$sValores1530=$sValores1530.', ';}
			$sValores1530=$sValores1530.'('.$bita30idbitacora.', '.$bita30consec.', '.$bita30id.', '.$fila['bita30idresponsable'].', "'.$fila['bita30fechafin'].'", '.$fila['bita30horafin'].', "'.$fila['bita30nota'].'")';
			$bita30consec++;
			$bita30id++;
			}
		if ($sValores1530!=''){
			$sSQL='INSERT INTO bita30bitahistrespon('.$sCampos1530.') VALUES '.$sValores1530.'';
			$result=$objDB->ejecutasql($sSQL);
			}
		}
	return $sError;
	}
// -- 1530 Cambios de responsable XAJAX 
function f1530_Guardar($valores, $aParametros){
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
		list($sError, $iAccion, $bita30id, $sDebugGuardar)=f1530_db_Guardar($valores, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugGuardar;
		}
	$objResponse=new xajaxResponse();
	if ($sError==''){
		list($sdetalle, $sDebugTabla)=f1530_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugTabla;
		$objResponse->assign('div_f1530detalle', 'innerHTML', $sdetalle);
		//if ($iAccion==2){
			//$objResponse->call('cargaridf1530('.$bita30id.')');
			//}else{
			$objResponse->call('limpiaf1530');
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
function f1530_Traer($aParametros){
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
		$bita30idbitacora=numeros_validar($aParametros[1]);
		$bita30consec=numeros_validar($aParametros[2]);
		if (($bita30idbitacora!='')&&($bita30consec!='')){$besta=true;}
		}else{
		$bita30id=$aParametros[103];
		if ((int)$bita30id!=0){$besta=true;}
		}
	if ($besta){
		$besta=false;
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		$bHayDb=true;
		$sSQLcondi='';
		if ($paso==1){
			$sSQLcondi=$sSQLcondi.'bita30idbitacora='.$bita30idbitacora.' AND bita30consec='.$bita30consec.'';
			}else{
			$sSQLcondi=$sSQLcondi.'bita30id='.$bita30id.'';
			}
		$sSQL='SELECT * FROM bita30bitahistrespon WHERE '.$sSQLcondi;
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
		$bita30idresponsable_id=(int)$fila['bita30idresponsable'];
		$bita30idresponsable_td=$APP->tipo_doc;
		$bita30idresponsable_doc='';
		$bita30idresponsable_nombre='';
		if ($bita30idresponsable_id!=0){
			list($bita30idresponsable_nombre, $bita30idresponsable_id, $bita30idresponsable_td, $bita30idresponsable_doc)=html_tercero($bita30idresponsable_td, $bita30idresponsable_doc, $bita30idresponsable_id, 0, $objDB);
			}
		$bita30consec_nombre='';
		$html_bita30consec=html_oculto('bita30consec', $fila['bita30consec'], $bita30consec_nombre);
		$objResponse->assign('div_bita30consec', 'innerHTML', $html_bita30consec);
		$bita30id_nombre='';
		$html_bita30id=html_oculto('bita30id', $fila['bita30id'], $bita30id_nombre);
		$objResponse->assign('div_bita30id', 'innerHTML', $html_bita30id);
		$objResponse->assign('bita30idresponsable', 'value', $fila['bita30idresponsable']);
		$objResponse->assign('bita30idresponsable_td', 'value', $bita30idresponsable_td);
		$objResponse->assign('bita30idresponsable_doc', 'value', $bita30idresponsable_doc);
		$objResponse->assign('div_bita30idresponsable', 'innerHTML', $bita30idresponsable_nombre);
		$objResponse->assign('bita30fechafin', 'value', $fila['bita30fechafin']);
		list($iDia, $iMes, $iAgno)=fecha_DividirNumero($fila['bita30fechafin'], true);
		$objResponse->assign('bita30fechafin_dia', 'value', $iDia);
		$objResponse->assign('bita30fechafin_mes', 'value', $iMes);
		$objResponse->assign('bita30fechafin_agno', 'value', $iAgno);
		$bita30horafin_eti=$fila['bita30horafin'];
		$html_bita30horafin=html_oculto('bita30horafin', $fila['bita30horafin'], $bita30horafin_eti);
		$objResponse->assign('div_bita30horafin', 'innerHTML', $html_bita30horafin);
		$objResponse->assign('bita30nota', 'value', $fila['bita30nota']);
		$objResponse->call("MensajeAlarmaV2('', 0)");
		$objResponse->call("verboton('belimina1530','block')");
		}else{
		if ($paso==1){
			$objResponse->assign('bita30consec', 'value', $bita30consec);
			}else{
			$objResponse->call('MensajeAlarmaV2("No se encontro el registro de referencia:'.$bita30id.'", 0)');
			}
		}
	if ($bHayDb){
		$objDB->CerrarConexion();
		}
	return $objResponse;
	}
function f1530_Eliminar($aParametros){
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
	list($sError, $sDebugElimina)=f1530_db_Eliminar($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugElimina;
	$objResponse=new xajaxResponse();
	if ($sError==''){
		list($sDetalle, $sDebugTabla)=f1530_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugTabla;
		$objResponse->assign('div_f1530detalle', 'innerHTML', $sDetalle);
		$objResponse->call('limpiaf1530');
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
function f1530_HtmlTabla($aParametros){
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
	list($sDetalle, $sDebugTabla)=f1530_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f1530detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f1530_PintarLlaves($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	//if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	if (isset($APP->piel)==0){$APP->piel=1;}
	$iPiel=$APP->piel;
	$html_bita30consec='<input id="bita30consec" name="bita30consec" type="text" value="" onchange="revisaf1530()" class="cuatro"/>';
	$html_bita30id='<input id="bita30id" name="bita30id" type="hidden" value=""/>';
	$html_bita30horafin=html_oculto('bita30horafin', '');
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_bita30consec','innerHTML', $html_bita30consec);
	$objResponse->assign('div_bita30id','innerHTML', $html_bita30id);
	$objResponse->assign('div_bita30horafin','innerHTML', $html_bita30horafin);
	return $objResponse;
	}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------
?>