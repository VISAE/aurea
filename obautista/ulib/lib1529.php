<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2019 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.22.7 viernes, 22 de marzo de 2019
--- 1529 Cambios de estado
*/
function f1529_db_Guardar($valores, $objDB, $bDebug=false){
	$iCodModulo=1529;
	$bAudita[2]=false;
	$bAudita[3]=false;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_1529=$APP->rutacomun.'lg/lg_1529_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1529)){$mensajes_1529=$APP->rutacomun.'lg/lg_1529_es.php';}
	require $mensajes_todas;
	require $mensajes_1529;
	$sError='';
	$sDebug='';
	$binserta=false;
	$iAccion=3;
	if(!is_array($valores)){$valores=json_decode(str_replace('\"','"',$valores),true);}
	$bita29idbitacora=numeros_validar($valores[1]);
	$bita29consec=numeros_validar($valores[2]);
	$bita29id=numeros_validar($valores[3], true);
	$bita29idestadoorigen=numeros_validar($valores[4]);
	$bita29idestadofin=numeros_validar($valores[5]);
	$bita29idusuario=numeros_validar($valores[6]);
	$bita29fecha=$valores[7];
	$bita29hora=$valores[8];
	//if ($bita29idestadoorigen==''){$bita29idestadoorigen=0;}
	//if ($bita29idestadofin==''){$bita29idestadofin=0;}
	$sSepara=', ';
	if ($bita29hora==0){
		//$bita29hora=fecha_DiaMod();
		$sError=$ERR['bita29hora'].$sSepara.$sError;
		}
	if ($bita29fecha==0){
		//$bita29fecha=fecha_DiaMod();
		$sError=$ERR['bita29fecha'].$sSepara.$sError;
		}
	if ($bita29idusuario==0){$sError=$ERR['bita29idusuario'].$sSepara.$sError;}
	if ($bita29idestadofin==''){$sError=$ERR['bita29idestadofin'].$sSepara.$sError;}
	if ($bita29idestadoorigen==''){$sError=$ERR['bita29idestadoorigen'].$sSepara.$sError;}
	//if ($bita29id==''){$sError=$ERR['bita29id'].$sSepara.$sError;}//CONSECUTIVO
	//if ($bita29consec==''){$sError=$ERR['bita29consec'].$sSepara.$sError;}//CONSECUTIVO
	if ($bita29idbitacora==''){$sError=$ERR['bita29idbitacora'].$sSepara.$sError;}
	if ($sError==''){
		list($sError, $sInfo)=tercero_Bloqueado($bita29idusuario, $objDB);
		if ($sInfo!=''){$sError=$sError.'<br>'.sInfo;}
		}
	if ($sError==''){
		if ((int)$bita29id==0){
			if ((int)$bita29consec==0){
				$bita29consec=tabla_consecutivo('bita29bitahistestado', 'bita29consec', 'bita29idbitacora='.$bita29idbitacora.'', $objDB);
				if ($bita29consec==-1){$sError=$objDB->serror;}
				}else{
				if (!seg_revisa_permiso($iCodModulo, 8, $objDB)){$sError=$ERR['8'];}
				}
			if ($sError==''){
				$sSQL='SELECT bita29idbitacora FROM bita29bitahistestado WHERE bita29idbitacora='.$bita29idbitacora.' AND bita29consec='.$bita29consec.'';
				$result=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($result)!=0){
					$sError=$ERR['existe'];
					}else{
					if (!seg_revisa_permiso($iCodModulo, 2, $objDB)){$sError=$ERR['2'];}
					}
				}
			if ($sError==''){
				$bita29id=tabla_consecutivo('bita29bitahistestado', 'bita29id', '', $objDB);
				if ($bita29id==-1){$sError=$objDB->serror;}
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
			$scampos='bita29idbitacora, bita29consec, bita29id, bita29idestadoorigen, bita29idestadofin, 
bita29idusuario, bita29fecha, bita29hora';
			$svalores=''.$bita29idbitacora.', '.$bita29consec.', '.$bita29id.', '.$bita29idestadoorigen.', '.$bita29idestadofin.', 
"'.$bita29idusuario.'", "'.$bita29fecha.'", "'.$bita29hora.'"';
			if ($APP->utf8==1){
				$sSQL='INSERT INTO bita29bitahistestado ('.$scampos.') VALUES ('.utf8_encode($svalores).');';
				}else{
				$sSQL='INSERT INTO bita29bitahistestado ('.$scampos.') VALUES ('.$svalores.');';
				}
			$result=$objDB->ejecutasql($sSQL);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' {Cambios de estado}.<!-- '.$sSQL.' -->';
				}else{
				if ($bAudita[2]){
					seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 2, $bita29id, $sSQL, $objDB);
					}
				}
			}else{
			$scampo1529[1]='bita29idestadoorigen';
			$scampo1529[2]='bita29idestadofin';
			$scampo1529[3]='bita29idusuario';
			$scampo1529[4]='bita29fecha';
			$scampo1529[5]='bita29hora';
			$svr1529[1]=$bita29idestadoorigen;
			$svr1529[2]=$bita29idestadofin;
			$svr1529[3]=$bita29idusuario;
			$svr1529[4]=$bita29fecha;
			$svr1529[5]=$bita29hora;
			$inumcampos=5;
			$sWhere='bita29id='.$bita29id.'';
			//$sWhere='bita29idbitacora='.$bita29idbitacora.' AND bita29consec='.$bita29consec.'';
			$sSQL='SELECT * FROM bita29bitahistestado WHERE '.$sWhere;
			$sdatos='';
			$bpasa=false;
			$result=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($result)>0){
				$filaorigen=$objDB->sf($result);
				for ($k=1;$k<=$inumcampos;$k++){
					if ($filaorigen[$scampo1529[$k]]!=$svr1529[$k]){
						if ($sdatos!=''){$sdatos=$sdatos.', ';}
						$sdatos=$sdatos.$scampo1529[$k].'="'.$svr1529[$k].'"';
						$bpasa=true;
						}
					}
				}
			if ($bpasa){
				if ($APP->utf8==1){
					$sSQL='UPDATE bita29bitahistestado SET '.utf8_encode($sdatos).' WHERE '.$sWhere.';';
					}else{
					$sSQL='UPDATE bita29bitahistestado SET '.$sdatos.' WHERE '.$sWhere.';';
					}
				$result=$objDB->ejecutasql($sSQL);
				if ($result==false){
					$sError=$ERR['falla_guardar'].' {Cambios de estado}. <!-- '.$sSQL.' -->';
					}else{
					if ($bAudita[3]){
						seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 3, $bita29id, $sSQL, $objDB);
						}
					}
				}
			}
		}
	return array($sError, $iAccion, $bita29id, $sDebug);
	}
function f1529_db_Eliminar($aParametros, $objDB, $bDebug=false){
	$iCodModulo=1529;
	$bAudita[4]=false;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_1529=$APP->rutacomun.'lg/lg_1529_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1529)){$mensajes_1529=$APP->rutacomun.'lg/lg_1529_es.php';}
	require $mensajes_todas;
	require $mensajes_1529;
	$sError='';
	$sDebug='';
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$bita29idbitacora=numeros_validar($aParametros[1]);
	$bita29consec=numeros_validar($aParametros[2]);
	$bita29id=numeros_validar($aParametros[3]);
	if ($sError==''){
		if (!seg_revisa_permiso($iCodModulo, 4, $objDB)){
			$sError=$ERR['4'];
			}
		}
	if ($sError==''){
		$sSQL='SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=1529';
		$tablaor=$objDB->ejecutasql($sSQL);
		while ($filaor=$objDB->sf($tablaor)){
			$sSQL='SELECT '.$filaor['campoid'].' FROM '.$filaor['tabla'].' WHERE '.$filaor['camporev'].'='.$bita29id.' LIMIT 0, 1';
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
		$sWhere='bita29id='.$bita29id.'';
		//$sWhere='bita29idbitacora='.$bita29idbitacora.' AND bita29consec='.$bita29consec.'';
		$sSQL='DELETE FROM bita29bitahistestado WHERE '.$sWhere.';';
		$result=$objDB->ejecutasql($sSQL);
		if ($result==false){
			$sError=$ERR['falla_eliminar'].' {1529 Cambios de estado}.<!-- '.$sSQL.' -->';
			}else{
			if ($bAudita[4]){
				seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $bita29id, $sSQL, $objDB);
				}
			}
		}
	return array($sError, $sDebug);
	}
function f1529_TablaDetalleV2($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_1529=$APP->rutacomun.'lg/lg_1529_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1529)){$mensajes_1529=$APP->rutacomun.'lg/lg_1529_es.php';}
	require $mensajes_todas;
	require $mensajes_1529;
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
	$sTitulos='Bitacora, Consec, Id, Estadoorigen, Estadofin, Usuario, Fecha, Hora';
	$sSQL='SELECT TB.bita29idbitacora, TB.bita29consec, TB.bita29id, TB.bita29idestadoorigen, TB.bita29idestadofin, T6.unad11razonsocial AS C6_nombre, TB.bita29fecha, TB.bita29hora, TB.bita29idusuario, T6.unad11tipodoc AS C6_td, T6.unad11doc AS C6_doc, T1.bita02nombre AS EstIni, T2.bita02nombre AS EstFin 
FROM bita29bitahistestado AS TB, unad11terceros AS T6, bita02estado AS T1, bita02estado AS T2 
WHERE '.$sSQLadd1.' TB.bita29idbitacora='.$bita04id.' AND TB.bita29idusuario=T6.unad11id AND TB.bita29idestadoorigen=T1.bita02id AND TB.bita29idestadofin=T2.bita02id '.$sSQLadd.'
ORDER BY TB.bita29consec DESC';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_1529" name="consulta_1529" type="hidden" value="'.$sSQLlista.'"/>
<input id="titulos_1529" name="titulos_1529" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objDB->ejecutasql($sSQL);
	if ($tabladetalle==false){
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 1529: '.$sSQL.'<br>';}
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		//$sLeyenda=$sSQL;
		}else{
		$registros=$objDB->nf($tabladetalle);
		if ($registros==0){
			return array(utf8_encode($sErrConsulta.'<input id="paginaf1529" name="paginaf1529" type="hidden" value="'.$pagina.'"/><input id="lppf1529" name="lppf1529" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
<td><b>'.$ETI['bita29consec'].'</b></td>
<td><b>'.$ETI['bita29idestadoorigen'].'</b></td>
<td><b>'.$ETI['bita29idestadofin'].'</b></td>
<td colspan="2"><b>'.$ETI['bita29idusuario'].'</b></td>
<td colspan="2"><b>'.$ETI['bita29fecha'].'</b></td>
<td align="right">
'.html_paginador('paginaf1529', $registros, $lineastabla, $pagina, 'paginarf1529()').'
'.html_lpp('lppf1529', $lineastabla, 'paginarf1529()').'
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
		$et_bita29consec=$sPrefijo.$filadet['bita29consec'].$sSufijo;
		$et_bita29idestadoorigen=$sPrefijo.cadena_notildes($filadet['EstIni']).$sSufijo;
		$et_bita29idestadofin=$sPrefijo.cadena_notildes($filadet['EstFin']).$sSufijo;
		$et_bita29idusuario=$sPrefijo.$filadet['bita29idusuario'].$sSufijo;
		$et_bita29fecha='';
		if ($filadet['bita29fecha']!=0){$et_bita29fecha=$sPrefijo.fecha_desdenumero($filadet['bita29fecha']).$sSufijo;}
		$et_bita29hora=$sPrefijo.html_TablaHoraMinDesdeNumero($filadet['bita29hora']).$sSufijo;
		if ($babierta){
			$sLink='<a href="javascript:cargaridf1529('.$filadet['bita29id'].')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$res=$res.'<tr'.$sClass.'>
<td>'.$et_bita29consec.'</td>
<td>'.$et_bita29idestadoorigen.'</td>
<td>'.$et_bita29idestadofin.'</td>
<td>'.$sPrefijo.$filadet['C6_td'].' '.$filadet['C6_doc'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['C6_nombre']).$sSufijo.'</td>
<td>'.$et_bita29fecha.'</td>
<td>'.$et_bita29hora.'</td>
<td>'.$sLink.'</td>
</tr>';
		}
	$res=$res.'</table>';
	$objDB->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
	}
function f1529_Clonar($bita29idbitacora, $bita29idbitacoraPadre, $objDB){
	$sError='';
	$bita29consec=tabla_consecutivo('bita29bitahistestado', 'bita29consec', 'bita29idbitacora='.$bita29idbitacora.'', $objDB);
	if ($bita29consec==-1){$sError=$objDB->serror;}
	$bita29id=tabla_consecutivo('bita29bitahistestado', 'bita29id', '', $objDB);
	if ($bita29id==-1){$sError=$objDB->serror;}
	if ($sError==''){
		$sCampos1529='bita29idbitacora, bita29consec, bita29id, bita29idestadoorigen, bita29idestadofin, bita29idusuario, bita29fecha, bita29hora';
		$sValores1529='';
		$sSQL='SELECT * FROM bita29bitahistestado WHERE bita29idbitacora='.$bita29idbitacoraPadre.'';
		$tabla=$objDB->ejecutasql($sSQL);
		while($fila=$objDB->sf($tabla)){
			if ($sValores1529!=''){$sValores1529=$sValores1529.', ';}
			$sValores1529=$sValores1529.'('.$bita29idbitacora.', '.$bita29consec.', '.$bita29id.', '.$fila['bita29idestadoorigen'].', '.$fila['bita29idestadofin'].', '.$fila['bita29idusuario'].', "'.$fila['bita29fecha'].'", "'.$fila['bita29hora'].'")';
			$bita29consec++;
			$bita29id++;
			}
		if ($sValores1529!=''){
			$sSQL='INSERT INTO bita29bitahistestado('.$sCampos1529.') VALUES '.$sValores1529.'';
			$result=$objDB->ejecutasql($sSQL);
			}
		}
	return $sError;
	}
// -- 1529 Cambios de estado XAJAX 
function f1529_Guardar($valores, $aParametros){
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
		list($sError, $iAccion, $bita29id, $sDebugGuardar)=f1529_db_Guardar($valores, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugGuardar;
		}
	$objResponse=new xajaxResponse();
	if ($sError==''){
		list($sdetalle, $sDebugTabla)=f1529_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugTabla;
		$objResponse->assign('div_f1529detalle', 'innerHTML', $sdetalle);
		//if ($iAccion==2){
			//$objResponse->call('cargaridf1529('.$bita29id.')');
			//}else{
			$objResponse->call('limpiaf1529');
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
function f1529_Traer($aParametros){
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
		$bita29idbitacora=numeros_validar($aParametros[1]);
		$bita29consec=numeros_validar($aParametros[2]);
		if (($bita29idbitacora!='')&&($bita29consec!='')){$besta=true;}
		}else{
		$bita29id=$aParametros[103];
		if ((int)$bita29id!=0){$besta=true;}
		}
	if ($besta){
		$besta=false;
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		$bHayDb=true;
		$sSQLcondi='';
		if ($paso==1){
			$sSQLcondi=$sSQLcondi.'bita29idbitacora='.$bita29idbitacora.' AND bita29consec='.$bita29consec.'';
			}else{
			$sSQLcondi=$sSQLcondi.'bita29id='.$bita29id.'';
			}
		$sSQL='SELECT * FROM bita29bitahistestado WHERE '.$sSQLcondi;
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
		$bita29idusuario_id=(int)$fila['bita29idusuario'];
		$bita29idusuario_td=$APP->tipo_doc;
		$bita29idusuario_doc='';
		$bita29idusuario_nombre='';
		if ($bita29idusuario_id!=0){
			list($bita29idusuario_nombre, $bita29idusuario_id, $bita29idusuario_td, $bita29idusuario_doc)=html_tercero($bita29idusuario_td, $bita29idusuario_doc, $bita29idusuario_id, 0, $objDB);
			}
		$bita29consec_nombre='';
		$html_bita29consec=html_oculto('bita29consec', $fila['bita29consec'], $bita29consec_nombre);
		$objResponse->assign('div_bita29consec', 'innerHTML', $html_bita29consec);
		$bita29id_nombre='';
		$html_bita29id=html_oculto('bita29id', $fila['bita29id'], $bita29id_nombre);
		$objResponse->assign('div_bita29id', 'innerHTML', $html_bita29id);
		$objResponse->assign('bita29idestadoorigen', 'value', $fila['bita29idestadoorigen']);
		$objResponse->assign('bita29idestadofin', 'value', $fila['bita29idestadofin']);
		$objResponse->assign('bita29idusuario', 'value', $fila['bita29idusuario']);
		$objResponse->assign('bita29idusuario_td', 'value', $bita29idusuario_td);
		$objResponse->assign('bita29idusuario_doc', 'value', $bita29idusuario_doc);
		$objResponse->assign('div_bita29idusuario', 'innerHTML', $bita29idusuario_nombre);
		$objResponse->assign('bita29fecha', 'value', $fila['bita29fecha']);
		list($iDia, $iMes, $iAgno)=fecha_DividirNumero($fila['bita29fecha'], true);
		$objResponse->assign('bita29fecha_dia', 'value', $iDia);
		$objResponse->assign('bita29fecha_mes', 'value', $iMes);
		$objResponse->assign('bita29fecha_agno', 'value', $iAgno);
		$objResponse->assign('bita29hora', 'value', $fila['bita29hora']);
		list($iDia, $iMes, $iAgno)=fecha_DividirNumero($fila['bita29hora'], true);
		$objResponse->assign('bita29hora_dia', 'value', $iDia);
		$objResponse->assign('bita29hora_mes', 'value', $iMes);
		$objResponse->assign('bita29hora_agno', 'value', $iAgno);
		$objResponse->call("MensajeAlarmaV2('', 0)");
		$objResponse->call("verboton('belimina1529','block')");
		}else{
		if ($paso==1){
			$objResponse->assign('bita29consec', 'value', $bita29consec);
			}else{
			$objResponse->call('MensajeAlarmaV2("No se encontro el registro de referencia:'.$bita29id.'", 0)');
			}
		}
	if ($bHayDb){
		$objDB->CerrarConexion();
		}
	return $objResponse;
	}
function f1529_Eliminar($aParametros){
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
	list($sError, $sDebugElimina)=f1529_db_Eliminar($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugElimina;
	$objResponse=new xajaxResponse();
	if ($sError==''){
		list($sDetalle, $sDebugTabla)=f1529_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugTabla;
		$objResponse->assign('div_f1529detalle', 'innerHTML', $sDetalle);
		$objResponse->call('limpiaf1529');
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
function f1529_HtmlTabla($aParametros){
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
	list($sDetalle, $sDebugTabla)=f1529_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f1529detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f1529_PintarLlaves($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	//if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	if (isset($APP->piel)==0){$APP->piel=1;}
	$iPiel=$APP->piel;
	$html_bita29consec='<input id="bita29consec" name="bita29consec" type="text" value="" onchange="revisaf1529()" class="cuatro"/>';
	$html_bita29id='<input id="bita29id" name="bita29id" type="hidden" value=""/>';
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_bita29consec','innerHTML', $html_bita29consec);
	$objResponse->assign('div_bita29id','innerHTML', $html_bita29id);
	return $objResponse;
	}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------
?>