<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2018 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.22.3 jueves, 16 de agosto de 2018
--- 2317 Anexos
*/
function f2317_db_Guardar($valores, $objDB, $bDebug=false){
	$iCodModulo=2317;
	$bAudita[2]=false;
	$bAudita[3]=false;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2317='lg/lg_2317_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2317)){$mensajes_2317='lg/lg_2317_es.php';}
	require $mensajes_todas;
	require $mensajes_2317;
	$sError='';
	$sDebug='';
	$binserta=false;
	$iAccion=3;
	if(!is_array($valores)){$valores=json_decode(str_replace('\"','"',$valores),true);}
	$cara17idpregunta=numeros_validar($valores[1]);
	$cara17consec=numeros_validar($valores[2]);
	$cara17id=numeros_validar($valores[3], true);
	$cara17nombre=htmlspecialchars(trim($valores[6]));
	$sSepara=', ';
	if ($cara17nombre==''){$sError=$ERR['cara17nombre'].$sSepara.$sError;}
	//if ($cara17id==''){$sError=$ERR['cara17id'].$sSepara.$sError;}//CONSECUTIVO
	//if ($cara17consec==''){$sError=$ERR['cara17consec'].$sSepara.$sError;}//CONSECUTIVO
	if ($cara17idpregunta==''){$sError=$ERR['cara17idpregunta'].$sSepara.$sError;}
	if ($sError==''){
		if ((int)$cara17id==0){
			if ((int)$cara17consec==0){
				$cara17consec=tabla_consecutivo('cara17preganexo', 'cara17consec', 'cara17idpregunta='.$cara17idpregunta.'', $objDB);
				if ($cara17consec==-1){$sError=$objDB->serror;}
				}else{
				if (!seg_revisa_permiso($iCodModulo, 8, $objDB)){$sError=$ERR['8'];}
				}
			if ($sError==''){
				$sSQL='SELECT cara17idpregunta FROM cara17preganexo WHERE cara17idpregunta='.$cara17idpregunta.' AND cara17consec='.$cara17consec.'';
				$result=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($result)!=0){
					$sError=$ERR['existe'];
					}else{
					if (!seg_revisa_permiso($iCodModulo, 2, $objDB)){$sError=$ERR['2'];}
					}
				}
			if ($sError==''){
				$cara17id=tabla_consecutivo('cara17preganexo', 'cara17id', '', $objDB);
				if ($cara17id==-1){$sError=$objDB->serror;}
				$binserta=true;
				$iAccion=2;
				}
			}else{
			if (!seg_revisa_permiso($iCodModulo, 3, $objDB)){$sError=$ERR['3'];}
			}
		}
	if ($sError==''){
		if ($binserta){
			$cara17idorigen=0;
			$cara17idanexo=0;
			}
		}
	if ($sError==''){
		if ($binserta){
			$scampos='cara17idpregunta, cara17consec, cara17id, cara17idorigen, cara17idanexo, 
cara17nombre';
			$svalores=''.$cara17idpregunta.', '.$cara17consec.', '.$cara17id.', 0, 0, 
"'.$cara17nombre.'"';
			if ($APP->utf8==1){
				$sSQL='INSERT INTO cara17preganexo ('.$scampos.') VALUES ('.utf8_encode($svalores).');';
				}else{
				$sSQL='INSERT INTO cara17preganexo ('.$scampos.') VALUES ('.$svalores.');';
				}
			$result=$objDB->ejecutasql($sSQL);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' {Anexos}.<!-- '.$sSQL.' -->';
				}else{
				if ($bAudita[2]){
					seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 2, $cara17id, $sSQL, $objDB);
					}
				}
			}else{
			$scampo2317[1]='cara17nombre';
			$svr2317[1]=$cara17nombre;
			$inumcampos=1;
			$sWhere='cara17id='.$cara17id.'';
			//$sWhere='cara17idpregunta='.$cara17idpregunta.' AND cara17consec='.$cara17consec.'';
			$sSQL='SELECT * FROM cara17preganexo WHERE '.$sWhere;
			$sdatos='';
			$bpasa=false;
			$result=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($result)>0){
				$filaorigen=$objDB->sf($result);
				for ($k=1;$k<=$inumcampos;$k++){
					if ($filaorigen[$scampo2317[$k]]!=$svr2317[$k]){
						if ($sdatos!=''){$sdatos=$sdatos.', ';}
						$sdatos=$sdatos.$scampo2317[$k].'="'.$svr2317[$k].'"';
						$bpasa=true;
						}
					}
				}
			if ($bpasa){
				if ($APP->utf8==1){
					$sSQL='UPDATE cara17preganexo SET '.utf8_encode($sdatos).' WHERE '.$sWhere.';';
					}else{
					$sSQL='UPDATE cara17preganexo SET '.$sdatos.' WHERE '.$sWhere.';';
					}
				$result=$objDB->ejecutasql($sSQL);
				if ($result==false){
					$sError=$ERR['falla_guardar'].' {Anexos}. <!-- '.$sSQL.' -->';
					}else{
					if ($bAudita[3]){
						seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 3, $cara17id, $sSQL, $objDB);
						}
					}
				}
			}
		}
	return array($sError, $iAccion, $cara17id, $sDebug);
	}
function f2317_db_Eliminar($aParametros, $objDB, $bDebug=false){
	$iCodModulo=2317;
	$bAudita[4]=false;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2317='lg/lg_2317_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2317)){$mensajes_2317='lg/lg_2317_es.php';}
	require $mensajes_todas;
	require $mensajes_2317;
	$sError='';
	$sDebug='';
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$cara17idpregunta=numeros_validar($aParametros[1]);
	$cara17consec=numeros_validar($aParametros[2]);
	$cara17id=numeros_validar($aParametros[3]);
	if ($sError==''){
		if (!seg_revisa_permiso($iCodModulo, 4, $objDB)){
			$sError=$ERR['4'];
			}
		}
	if ($sError==''){
		$sSQL='SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=2317';
		$tablaor=$objDB->ejecutasql($sSQL);
		while ($filaor=$objDB->sf($tablaor)){
			$sSQL='SELECT '.$filaor['campoid'].' FROM '.$filaor['tabla'].' WHERE '.$filaor['camporev'].'='.$cara17id.' LIMIT 0, 1';
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
		$sWhere='cara17id='.$cara17id.'';
		//$sWhere='cara17idpregunta='.$cara17idpregunta.' AND cara17consec='.$cara17consec.'';
		$sSQL='DELETE FROM cara17preganexo WHERE '.$sWhere.';';
		$result=$objDB->ejecutasql($sSQL);
		if ($result==false){
			$sError=$ERR['falla_eliminar'].' {2317 Anexos}.<!-- '.$sSQL.' -->';
			}else{
			if ($bAudita[4]){
				seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $cara17id, $sSQL, $objDB);
				}
			}
		}
	return array($sError, $sDebug);
	}
function f2317_TablaDetalleV2($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2317='lg/lg_2317_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2317)){$mensajes_2317='lg/lg_2317_es.php';}
	require $mensajes_todas;
	require $mensajes_2317;
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	if (isset($aParametros[0])==0){$aParametros[0]=-1;}
	if (isset($aParametros[101])==0){$aParametros[101]=1;}
	if (isset($aParametros[102])==0){$aParametros[102]=20;}
	//if (isset($aParametros[103])==0){$aParametros[103]='';}
	//$aParametros[103]=numeros_validar($aParametros[103]);
	$aParametros[0]=numeros_validar($aParametros[0]);
	if ($aParametros[0]==''){$aParametros[0]=-1;}
	$sDebug='';
	$cara08id=$aParametros[0];
	$pagina=$aParametros[101];
	$lineastabla=$aParametros[102];
	$babierta=true;
	//$sSQL='SELECT Campo FROM cara08pregunta WHERE cara08id='.$cara08id;
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
	$sTitulos='Pregunta, Consec, Id, Origen, Anexo, Nombre';
	$sSQL='SELECT TB.cara17idpregunta, TB.cara17consec, TB.cara17id, TB.cara17idorigen, TB.cara17idanexo, TB.cara17nombre 
FROM cara17preganexo AS TB 
WHERE '.$sSQLadd1.' TB.cara17idpregunta='.$cara08id.' '.$sSQLadd.'
ORDER BY TB.cara17consec';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_2317" name="consulta_2317" type="hidden" value="'.$sSQLlista.'"/>
<input id="titulos_2317" name="titulos_2317" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objDB->ejecutasql($sSQL);
	if ($tabladetalle==false){
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 2317: '.$sSQL.'<br>';}
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		//$sLeyenda=$sSQL;
		}else{
		$registros=$objDB->nf($tabladetalle);
		if ($registros==0){
			return array(utf8_encode($sErrConsulta.'<input id="paginaf2317" name="paginaf2317" type="hidden" value="'.$pagina.'"/><input id="lppf2317" name="lppf2317" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
<td><b>'.$ETI['cara17consec'].'</b></td>
<td><b>'.$ETI['cara17idanexo'].'</b></td>
<td><b>'.$ETI['cara17nombre'].'</b></td>
<td align="right">
'.html_paginador('paginaf2317', $registros, $lineastabla, $pagina, 'paginarf2317()').'
'.html_lpp('lppf2317', $lineastabla, 'paginarf2317()').'
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
		$et_cara17consec=$sPrefijo.$filadet['cara17consec'].$sSufijo;
		$et_cara17idanexo='';
		if ($filadet['cara17idanexo']!=0){
			//$et_cara17idanexo='<img src="verarchivo.php?cont='.$filadet['cara17idorigen'].'&id='.$filadet['cara17idanexo'].'&maxx=150"/>';
			$et_cara17idanexo=html_lnkarchivo((int)$filadet['cara17idorigen'], (int)$filadet['cara17idanexo']);
			}
		$et_cara17nombre=$sPrefijo.cadena_notildes($filadet['cara17nombre']).$sSufijo;
		if ($babierta){
			$sLink='<a href="javascript:cargaridf2317('.$filadet['cara17id'].')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$res=$res.'<tr'.$sClass.'>
<td>'.$et_cara17consec.'</td>
<td>'.$et_cara17idanexo.'</td>
<td>'.$et_cara17nombre.'</td>
<td>'.$sLink.'</td>
</tr>';
		}
	$res=$res.'</table>';
	$objDB->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
	}
function f2317_Clonar($cara17idpregunta, $cara17idpreguntaPadre, $objDB){
	$sError='';
	$cara17consec=tabla_consecutivo('cara17preganexo', 'cara17consec', 'cara17idpregunta='.$cara17idpregunta.'', $objDB);
	if ($cara17consec==-1){$sError=$objDB->serror;}
	$cara17id=tabla_consecutivo('cara17preganexo', 'cara17id', '', $objDB);
	if ($cara17id==-1){$sError=$objDB->serror;}
	if ($sError==''){
		$sCampos2317='cara17idpregunta, cara17consec, cara17id, cara17idorigen, cara17idanexo, cara17nombre';
		$sValores2317='';
		$sSQL='SELECT * FROM cara17preganexo WHERE cara17idpregunta='.$cara17idpreguntaPadre.'';
		$tabla=$objDB->ejecutasql($sSQL);
		while($fila=$objDB->sf($tabla)){
			if ($sValores2317!=''){$sValores2317=$sValores2317.', ';}
			$sValores2317=$sValores2317.'('.$cara17idpregunta.', '.$cara17consec.', '.$cara17id.', "'.$fila['cara17idorigen'].'", "'.$fila['cara17idanexo'].'", "'.$fila['cara17nombre'].'")';
			$cara17consec++;
			$cara17id++;
			}
		if ($sValores2317!=''){
			$sSQL='INSERT INTO cara17preganexo('.$sCampos2317.') VALUES '.$sValores2317.'';
			$result=$objDB->ejecutasql($sSQL);
			}
		}
	return $sError;
	}
// -- 2317 Anexos XAJAX 
function elimina_archivo_cara17idanexo($idpadre){
	$_SESSION['u_ultimominuto']=iminutoavance();
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	archivo_eliminar('cara17preganexo', 'cara17id', 'cara17idorigen', 'cara17idanexo', $idpadre, $objDB);
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->call("limpia_cara17idanexo");
	return $objResponse;
	}
function f2317_Guardar($valores, $aParametros){
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
		list($sError, $iAccion, $cara17id, $sDebugGuardar)=f2317_db_Guardar($valores, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugGuardar;
		}
	$objResponse=new xajaxResponse();
	if ($sError==''){
		list($sdetalle, $sDebugTabla)=f2317_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugTabla;
		$objResponse->assign('div_f2317detalle', 'innerHTML', $sdetalle);
		//if ($iAccion==2){
			//$objResponse->call('cargaridf2317('.$cara17id.')');
			//}else{
			$objResponse->call('limpiaf2317');
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
function f2317_Traer($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	$sError='';
	$bHayDb=false;
	$besta=false;
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$paso=$aParametros[0];
	if ($paso==1){
		$cara17idpregunta=numeros_validar($aParametros[1]);
		$cara17consec=numeros_validar($aParametros[2]);
		if (($cara17idpregunta!='')&&($cara17consec!='')){$besta=true;}
		}else{
		$cara17id=$aParametros[103];
		if ((int)$cara17id!=0){$besta=true;}
		}
	if ($besta){
		$besta=false;
		require './app.php';
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		$bHayDb=true;
		$sSQLcondi='';
		if ($paso==1){
			$sSQLcondi=$sSQLcondi.'cara17idpregunta='.$cara17idpregunta.' AND cara17consec='.$cara17consec.'';
			}else{
			$sSQLcondi=$sSQLcondi.'cara17id='.$cara17id.'';
			}
		$sSQL='SELECT * FROM cara17preganexo WHERE '.$sSQLcondi;
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
		$cara17consec_nombre='';
		$html_cara17consec=html_oculto('cara17consec', $fila['cara17consec'], $cara17consec_nombre);
		$objResponse->assign('div_cara17consec', 'innerHTML', $html_cara17consec);
		$cara17id_nombre='';
		$html_cara17id=html_oculto('cara17id', $fila['cara17id'], $cara17id_nombre);
		$objResponse->assign('div_cara17id', 'innerHTML', $html_cara17id);
		$objResponse->assign('cara17idorigen', 'value', $fila['cara17idorigen']);
		$idorigen=(int)$fila['cara17idorigen'];
		$objResponse->assign('cara17idanexo', 'value', $fila['cara17idanexo']);
		$objResponse->call("verboton('banexacara17idanexo', 'block')");
		$stemp='none';
		$stemp2=html_lnkarchivo($idorigen, (int)$fila['cara17idanexo']);
		if ((int)$fila['cara17idanexo']!=0){$stemp='block';}
		$objResponse->assign('div_cara17idanexo', 'innerHTML', $stemp2);
		$objResponse->call("verboton('beliminacara17idanexo','".$stemp."')");
		$objResponse->assign('cara17nombre', 'value', $fila['cara17nombre']);
		$objResponse->call("MensajeAlarmaV2('', 0)");
		$objResponse->call("verboton('belimina2317','block')");
		}else{
		if ($paso==1){
			$objResponse->assign('cara17consec', 'value', $cara17consec);
			}else{
			$objResponse->call('MensajeAlarmaV2("No se encontro el registro de referencia:'.$cara17id.'", 0)');
			}
		}
	if ($bHayDb){
		$objDB->CerrarConexion();
		}
	return $objResponse;
	}
function f2317_Eliminar($aParametros){
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
	list($sError, $sDebugElimina)=f2317_db_Eliminar($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugElimina;
	$objResponse=new xajaxResponse();
	if ($sError==''){
		list($sDetalle, $sDebugTabla)=f2317_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugTabla;
		$objResponse->assign('div_f2317detalle', 'innerHTML', $sDetalle);
		$objResponse->call('limpiaf2317');
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
function f2317_HtmlTabla($aParametros){
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
	list($sDetalle, $sDebugTabla)=f2317_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f2317detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f2317_PintarLlaves($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	//if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	if (isset($APP->piel)==0){$APP->piel=1;}
	$iPiel=$APP->piel;
	$html_cara17consec='<input id="cara17consec" name="cara17consec" type="text" value="" onchange="revisaf2317()" class="cuatro"/>';
	$html_cara17id='<input id="cara17id" name="cara17id" type="hidden" value=""/>';
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_cara17consec','innerHTML', $html_cara17consec);
	$objResponse->assign('div_cara17id','innerHTML', $html_cara17id);
	return $objResponse;
	}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------
?>