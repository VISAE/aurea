<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2019 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.22.7 martes, 19 de marzo de 2019
--- 2222 Cambios de estado
*/
function f2222_db_Guardar($valores, $objDB, $bDebug=false){
	$iCodModulo=2222;
	$bAudita[2]=false;
	$bAudita[3]=false;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2222='lg/lg_2222_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2222)){$mensajes_2222='lg/lg_2222_es.php';}
	require $mensajes_todas;
	require $mensajes_2222;
	$sError='';
	$sDebug='';
	$binserta=false;
	$iAccion=3;
	if(!is_array($valores)){$valores=json_decode(str_replace('\"','"',$valores),true);}
	$core22idestprograma=numeros_validar($valores[1]);
	$core22consec=numeros_validar($valores[2]);
	$core22id=numeros_validar($valores[3], true);
	$core22idestadoorigen=numeros_validar($valores[4]);
	$core22idestadodestino=numeros_validar($valores[5]);
	$core22fecha=$valores[6];
	$core22anotacion=htmlspecialchars(trim($valores[7]));
	//if ($core22idestadoorigen==''){$core22idestadoorigen=0;}
	//if ($core22idestadodestino==''){$core22idestadodestino=0;}
	$sSepara=', ';
	if ($core22anotacion==''){$sError=$ERR['core22anotacion'].$sSepara.$sError;}
	if ($core22fecha==0){
		//$core22fecha=fecha_DiaMod();
		$sError=$ERR['core22fecha'].$sSepara.$sError;
		}
	if ($core22idestadodestino==''){$sError=$ERR['core22idestadodestino'].$sSepara.$sError;}
	if ($core22idestadoorigen==''){$sError=$ERR['core22idestadoorigen'].$sSepara.$sError;}
	//if ($core22id==''){$sError=$ERR['core22id'].$sSepara.$sError;}//CONSECUTIVO
	//if ($core22consec==''){$sError=$ERR['core22consec'].$sSepara.$sError;}//CONSECUTIVO
	if ($core22idestprograma==''){$sError=$ERR['core22idestprograma'].$sSepara.$sError;}
	if ($sError==''){
		if ((int)$core22id==0){
			if ((int)$core22consec==0){
				$core22consec=tabla_consecutivo('core22gradohistorialest', 'core22consec', 'core22idestprograma='.$core22idestprograma.'', $objDB);
				if ($core22consec==-1){$sError=$objDB->serror;}
				}else{
				if (!seg_revisa_permiso($iCodModulo, 8, $objDB)){$sError=$ERR['8'];}
				}
			if ($sError==''){
				$sSQL='SELECT core22idestprograma FROM core22gradohistorialest WHERE core22idestprograma='.$core22idestprograma.' AND core22consec='.$core22consec.'';
				$result=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($result)!=0){
					$sError=$ERR['existe'];
					}else{
					if (!seg_revisa_permiso($iCodModulo, 2, $objDB)){$sError=$ERR['2'];}
					}
				}
			if ($sError==''){
				$core22id=tabla_consecutivo('core22gradohistorialest', 'core22id', '', $objDB);
				if ($core22id==-1){$sError=$objDB->serror;}
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
		//Si el campo core22anotacion permite html quite la linea htmlspecialchars para el campo y habilite la siguiente linea:
		//$core22anotacion=str_replace('"', '\"', $core22anotacion);
		$core22anotacion=str_replace('"', '\"', $core22anotacion);
		if ($binserta){
			$scampos='core22idestprograma, core22consec, core22id, core22idestadoorigen, core22idestadodestino, 
core22fecha, core22anotacion';
			$svalores=''.$core22idestprograma.', '.$core22consec.', '.$core22id.', '.$core22idestadoorigen.', '.$core22idestadodestino.', 
"'.$core22fecha.'", "'.$core22anotacion.'"';
			if ($APP->utf8==1){
				$sSQL='INSERT INTO core22gradohistorialest ('.$scampos.') VALUES ('.utf8_encode($svalores).');';
				}else{
				$sSQL='INSERT INTO core22gradohistorialest ('.$scampos.') VALUES ('.$svalores.');';
				}
			$result=$objDB->ejecutasql($sSQL);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' {Cambios de estado}.<!-- '.$sSQL.' -->';
				}else{
				if ($bAudita[2]){
					seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 2, $core22id, $sSQL, $objDB);
					}
				}
			}else{
			$scampo2222[1]='core22idestadoorigen';
			$scampo2222[2]='core22idestadodestino';
			$scampo2222[3]='core22fecha';
			$scampo2222[4]='core22anotacion';
			$svr2222[1]=$core22idestadoorigen;
			$svr2222[2]=$core22idestadodestino;
			$svr2222[3]=$core22fecha;
			$svr2222[4]=$core22anotacion;
			$inumcampos=4;
			$sWhere='core22id='.$core22id.'';
			//$sWhere='core22idestprograma='.$core22idestprograma.' AND core22consec='.$core22consec.'';
			$sSQL='SELECT * FROM core22gradohistorialest WHERE '.$sWhere;
			$sdatos='';
			$bpasa=false;
			$result=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($result)>0){
				$filaorigen=$objDB->sf($result);
				for ($k=1;$k<=$inumcampos;$k++){
					if ($filaorigen[$scampo2222[$k]]!=$svr2222[$k]){
						if ($sdatos!=''){$sdatos=$sdatos.', ';}
						$sdatos=$sdatos.$scampo2222[$k].'="'.$svr2222[$k].'"';
						$bpasa=true;
						}
					}
				}
			if ($bpasa){
				if ($APP->utf8==1){
					$sSQL='UPDATE core22gradohistorialest SET '.utf8_encode($sdatos).' WHERE '.$sWhere.';';
					}else{
					$sSQL='UPDATE core22gradohistorialest SET '.$sdatos.' WHERE '.$sWhere.';';
					}
				$result=$objDB->ejecutasql($sSQL);
				if ($result==false){
					$sError=$ERR['falla_guardar'].' {Cambios de estado}. <!-- '.$sSQL.' -->';
					}else{
					if ($bAudita[3]){
						seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 3, $core22id, $sSQL, $objDB);
						}
					}
				}
			}
		}
	return array($sError, $iAccion, $core22id, $sDebug);
	}
function f2222_db_Eliminar($aParametros, $objDB, $bDebug=false){
	$iCodModulo=2222;
	$bAudita[4]=false;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2222='lg/lg_2222_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2222)){$mensajes_2222='lg/lg_2222_es.php';}
	require $mensajes_todas;
	require $mensajes_2222;
	$sError='';
	$sDebug='';
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$core22idestprograma=numeros_validar($aParametros[1]);
	$core22consec=numeros_validar($aParametros[2]);
	$core22id=numeros_validar($aParametros[3]);
	if ($sError==''){
		if (!seg_revisa_permiso($iCodModulo, 4, $objDB)){
			$sError=$ERR['4'];
			}
		}
	if ($sError==''){
		$sSQL='SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=2222';
		$tablaor=$objDB->ejecutasql($sSQL);
		while ($filaor=$objDB->sf($tablaor)){
			$sSQL='SELECT '.$filaor['campoid'].' FROM '.$filaor['tabla'].' WHERE '.$filaor['camporev'].'='.$core22id.' LIMIT 0, 1';
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
		$sWhere='core22id='.$core22id.'';
		//$sWhere='core22idestprograma='.$core22idestprograma.' AND core22consec='.$core22consec.'';
		$sSQL='DELETE FROM core22gradohistorialest WHERE '.$sWhere.';';
		$result=$objDB->ejecutasql($sSQL);
		if ($result==false){
			$sError=$ERR['falla_eliminar'].' {2222 Cambios de estado}.<!-- '.$sSQL.' -->';
			}else{
			if ($bAudita[4]){
				seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $core22id, $sSQL, $objDB);
				}
			}
		}
	return array($sError, $sDebug);
	}
function f2222_TablaDetalleV2($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2222='lg/lg_2222_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2222)){$mensajes_2222='lg/lg_2222_es.php';}
	require $mensajes_todas;
	require $mensajes_2222;
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	if (isset($aParametros[0])==0){$aParametros[0]=-1;}
	if (isset($aParametros[97])==0){$aParametros[97]=0;}
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
	$babierta=false;
	if ($aParametros[97]==1){$babierta=true;}
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
	$aEstado=array();
	$sSQL='SELECT core02id, core02nombre FROM core02estadoprograma';
	$tabla=$objDB->ejecutasql($sSQL);
	while($fila=$objDB->sf($tabla)){
		$aEstado[$fila['core02id']]=cadena_notildes($fila['core02nombre']);
		}
	$sTitulos='Estprograma, Consec, Id, Estadoorigen, Estadodestino, Fecha, Anotacion';
	$sSQL='SELECT TB.core22idtercero, TB.core22consec, TB.core22id, TB.core22fecha, 
	TB.core22anotacion, T11.unad11razonsocial, TB.core22idestadoorigen, TB.core22idestadodestino 
	FROM core22gradohistorialest AS TB, unad11terceros AS T11 
	WHERE '.$sSQLadd1.' TB.core22idestprograma='.$core01id.' AND TB.core22idtercero=T11.unad11id '.$sSQLadd.'
	ORDER BY TB.core22fecha DESC';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_2222" name="consulta_2222" type="hidden" value="'.$sSQLlista.'"/>
	<input id="titulos_2222" name="titulos_2222" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objDB->ejecutasql($sSQL);
	if ($tabladetalle==false){
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 2222: '.$sSQL.'<br>';}
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		//$sLeyenda=$sSQL;
		}else{
		$registros=$objDB->nf($tabladetalle);
		if ($registros==0){
			return array(utf8_encode($sErrConsulta.'<input id="paginaf2222" name="paginaf2222" type="hidden" value="'.$pagina.'"/><input id="lppf2222" name="lppf2222" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
	<td><b>'.$ETI['core22fecha'].'</b></td>
	<td align="center"><b>'.$ETI['core22idestadoorigen'].'</b></td>
	<td align="center"><b>'.$ETI['core22idestadodestino'].'</b></td>
	<td align="right" colspan="2">
	'.html_paginador('paginaf2222', $registros, $lineastabla, $pagina, 'paginarf2222()').'
	'.html_lpp('lppf2222', $lineastabla, 'paginarf2222()').'
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
		//$et_core22consec=$sPrefijo.$filadet['core22consec'].$sSufijo;
		$et_core22idestadoorigen=$sPrefijo.$aEstado[$filadet['core22idestadoorigen']].$sSufijo;
		$et_core22idestadodestino=$sPrefijo.$aEstado[$filadet['core22idestadodestino']].$sSufijo;
		$et_core22fecha='';
		if ($filadet['core22fecha']!=0){$et_core22fecha=$sPrefijo.fecha_desdenumero($filadet['core22fecha']).$sSufijo;}
		if ($filadet['core22idtercero']==0){
			$et_core22idtercero='';
			}else{
			$et_core22idtercero=cadena_notildes($filadet['unad11razonsocial']);
			}
		$et_core22anotacion=$sPrefijo.cadena_notildes($filadet['core22anotacion']).$sSufijo;
		if ($babierta){
			$sLink='<a href="javascript:retirar2222('.$filadet['core22id'].')" class="lnkresalte">Quitar</a>';
			}
		$res=$res.'<tr'.$sClass.'>
		<td>'.$et_core22fecha.'</td>
		<td align="center">'.$et_core22idestadoorigen.'</td>
		<td align="center">'.$et_core22idestadodestino.'</td>
		<td>'.$et_core22idtercero.'</td>
		<td>'.$sLink.'</td>
		</tr>';
		//<td><b>'.$ETI['core22anotacion'].'</b></td>
		if ($filadet['core22anotacion']!=''){
			$res=$res.'<tr'.$sClass.'>
			<td></td>
			<td colspan="4">'.$et_core22anotacion.'</td>
			</tr>';
			}
		}
	$res=$res.'</table>';
	$objDB->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
	}
function f2222_Clonar($core22idestprograma, $core22idestprogramaPadre, $objDB){
	$sError='';
	$core22consec=tabla_consecutivo('core22gradohistorialest', 'core22consec', 'core22idestprograma='.$core22idestprograma.'', $objDB);
	if ($core22consec==-1){$sError=$objDB->serror;}
	$core22id=tabla_consecutivo('core22gradohistorialest', 'core22id', '', $objDB);
	if ($core22id==-1){$sError=$objDB->serror;}
	if ($sError==''){
		$sCampos2222='core22idestprograma, core22consec, core22id, core22idestadoorigen, core22idestadodestino, core22fecha, core22anotacion';
		$sValores2222='';
		$sSQL='SELECT * FROM core22gradohistorialest WHERE core22idestprograma='.$core22idestprogramaPadre.'';
		$tabla=$objDB->ejecutasql($sSQL);
		while($fila=$objDB->sf($tabla)){
			if ($sValores2222!=''){$sValores2222=$sValores2222.', ';}
			$sValores2222=$sValores2222.'('.$core22idestprograma.', '.$core22consec.', '.$core22id.', '.$fila['core22idestadoorigen'].', '.$fila['core22idestadodestino'].', "'.$fila['core22fecha'].'", "'.$fila['core22anotacion'].'")';
			$core22consec++;
			$core22id++;
			}
		if ($sValores2222!=''){
			$sSQL='INSERT INTO core22gradohistorialest('.$sCampos2222.') VALUES '.$sValores2222.'';
			$result=$objDB->ejecutasql($sSQL);
			}
		}
	return $sError;
	}
// -- 2222 Cambios de estado XAJAX 
function f2222_Guardar($valores, $aParametros){
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
		list($sError, $iAccion, $core22id, $sDebugGuardar)=f2222_db_Guardar($valores, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugGuardar;
		}
	$objResponse=new xajaxResponse();
	if ($sError==''){
		list($sdetalle, $sDebugTabla)=f2222_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugTabla;
		$objResponse->assign('div_f2222detalle', 'innerHTML', $sdetalle);
		//if ($iAccion==2){
			//$objResponse->call('cargaridf2222('.$core22id.')');
			//}else{
			$objResponse->call('limpiaf2222');
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
function f2222_Traer($aParametros){
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
		$core22idestprograma=numeros_validar($aParametros[1]);
		$core22consec=numeros_validar($aParametros[2]);
		if (($core22idestprograma!='')&&($core22consec!='')){$besta=true;}
		}else{
		$core22id=$aParametros[103];
		if ((int)$core22id!=0){$besta=true;}
		}
	if ($besta){
		$besta=false;
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		$bHayDb=true;
		$sSQLcondi='';
		if ($paso==1){
			$sSQLcondi=$sSQLcondi.'core22idestprograma='.$core22idestprograma.' AND core22consec='.$core22consec.'';
			}else{
			$sSQLcondi=$sSQLcondi.'core22id='.$core22id.'';
			}
		$sSQL='SELECT * FROM core22gradohistorialest WHERE '.$sSQLcondi;
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
		$core22consec_nombre='';
		$html_core22consec=html_oculto('core22consec', $fila['core22consec'], $core22consec_nombre);
		$objResponse->assign('div_core22consec', 'innerHTML', $html_core22consec);
		$core22id_nombre='';
		$html_core22id=html_oculto('core22id', $fila['core22id'], $core22id_nombre);
		$objResponse->assign('div_core22id', 'innerHTML', $html_core22id);
		$objResponse->assign('core22idestadoorigen', 'value', $fila['core22idestadoorigen']);
		$objResponse->assign('core22idestadodestino', 'value', $fila['core22idestadodestino']);
		$objResponse->assign('core22fecha', 'value', $fila['core22fecha']);
		list($iDia, $iMes, $iAgno)=fecha_DividirNumero($fila['core22fecha'], true);
		$objResponse->assign('core22fecha_dia', 'value', $iDia);
		$objResponse->assign('core22fecha_mes', 'value', $iMes);
		$objResponse->assign('core22fecha_agno', 'value', $iAgno);
		$objResponse->assign('core22anotacion', 'value', $fila['core22anotacion']);
		$objResponse->call("MensajeAlarmaV2('', 0)");
		$objResponse->call("verboton('belimina2222','block')");
		}else{
		if ($paso==1){
			$objResponse->assign('core22consec', 'value', $core22consec);
			}else{
			$objResponse->call('MensajeAlarmaV2("No se encontro el registro de referencia:'.$core22id.'", 0)');
			}
		}
	if ($bHayDb){
		$objDB->CerrarConexion();
		}
	return $objResponse;
	}
function f2222_Eliminar($aParametros){
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
	list($sError, $sDebugElimina)=f2222_db_Eliminar($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugElimina;
	$objResponse=new xajaxResponse();
	if ($sError==''){
		list($sDetalle, $sDebugTabla)=f2222_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugTabla;
		$objResponse->assign('div_f2222detalle', 'innerHTML', $sDetalle);
		$objResponse->call('limpiaf2222');
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
function f2222_HtmlTabla($aParametros){
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
	list($sDetalle, $sDebugTabla)=f2222_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f2222detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f2222_PintarLlaves($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	//if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	if (isset($APP->piel)==0){$APP->piel=1;}
	$iPiel=$APP->piel;
	$html_core22consec='<input id="core22consec" name="core22consec" type="text" value="" onchange="revisaf2222()" class="cuatro"/>';
	$html_core22id='<input id="core22id" name="core22id" type="hidden" value=""/>';
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_core22consec','innerHTML', $html_core22consec);
	$objResponse->assign('div_core22id','innerHTML', $html_core22id);
	return $objResponse;
	}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------
?>