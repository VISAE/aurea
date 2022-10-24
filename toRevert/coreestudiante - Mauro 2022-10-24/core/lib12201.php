<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2020 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.25.7 jueves, 1 de octubre de 2020
--- 12201 Anotaciones
*/
function f12201_db_Guardar($valores, $objDB, $bDebug=false){
	$iCodModulo=12201;
	$bAudita[2]=false;
	$bAudita[3]=false;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_12201='lg/lg_12201_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_12201)){$mensajes_12201='lg/lg_12201_es.php';}
	require $mensajes_todas;
	require $mensajes_12201;
	$sError='';
	$sDebug='';
	$bInserta=false;
	$iAccion=3;
	if(!is_array($valores)){$valores=json_decode(str_replace('\"','"',$valores),true);}
	$corf01idestprograma=numeros_validar($valores[1]);
	$corf01consec=numeros_validar($valores[2]);
	$corf01id=numeros_validar($valores[3], true);
	$corf01visible=numeros_validar($valores[4]);
	$corf01anotacion=htmlspecialchars(trim($valores[5]));
	$corf01usuario=numeros_validar($valores[8]);
	$corf01fecha=$valores[9];
	$corf01hora=numeros_validar($valores[10]);
	$corf01minuto=numeros_validar($valores[11]);
	//if ($corf01visible==''){$corf01visible=0;}
	//if ($corf01hora==''){$corf01hora=0;}
	//if ($corf01minuto==''){$corf01minuto=0;}
	$sSepara=', ';
	if ($corf01minuto==''){$sError=$ERR['corf01minuto'].$sSepara.$sError;}
	if ($corf01hora==''){$sError=$ERR['corf01hora'].$sSepara.$sError;}
	if ($corf01fecha==0){
		//$corf01fecha=fecha_DiaMod();
		$sError=$ERR['corf01fecha'].$sSepara.$sError;
		}
	if ($corf01usuario==0){$sError=$ERR['corf01usuario'].$sSepara.$sError;}
	if ($corf01anotacion==''){$sError=$ERR['corf01anotacion'].$sSepara.$sError;}
	if ($corf01visible==''){$sError=$ERR['corf01visible'].$sSepara.$sError;}
	//if ($corf01id==''){$sError=$ERR['corf01id'].$sSepara.$sError;}//CONSECUTIVO
	//if ($corf01consec==''){$sError=$ERR['corf01consec'].$sSepara.$sError;}//CONSECUTIVO
	if ($corf01idestprograma==''){$sError=$ERR['corf01idestprograma'].$sSepara.$sError;}
	if ($sError==''){
		list($sError, $sInfo)=tercero_Bloqueado($corf01usuario, $objDB);
		if ($sInfo!=''){$sError=$sError.'<br>'.sInfo;}
		}
	if ($sError==''){
		if ((int)$corf01id==0){
			if ((int)$corf01consec==0){
				$corf01consec=tabla_consecutivo('corf01estnota', 'corf01consec', 'corf01idestprograma='.$corf01idestprograma.'', $objDB);
				if ($corf01consec==-1){$sError=$objDB->serror;}
				}else{
				if (!seg_revisa_permiso($iCodModulo, 8, $objDB)){$sError=$ERR['8'];}
				}
			if ($sError==''){
				$sSQL='SELECT corf01idestprograma FROM corf01estnota WHERE corf01idestprograma='.$corf01idestprograma.' AND corf01consec='.$corf01consec.'';
				$result=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($result)!=0){
					$sError=$ERR['existe'];
					}else{
					if (!seg_revisa_permiso($iCodModulo, 2, $objDB)){$sError=$ERR['2'];}
					}
				}
			if ($sError==''){
				$corf01id=tabla_consecutivo('corf01estnota', 'corf01id', '', $objDB);
				if ($corf01id==-1){$sError=$objDB->serror;}
				$bInserta=true;
				$iAccion=2;
				}
			}else{
			if (!seg_revisa_permiso($iCodModulo, 3, $objDB)){$sError=$ERR['3'];}
			}
		}
	if ($sError==''){
		if ($bInserta){
			$corf01idorigenanexo=0;
			$corf01idarchivoanexo=0;
			}
		}
	if ($sError==''){
		//Si el campo corf01anotacion permite html quite la linea htmlspecialchars para el campo y habilite la siguiente linea:
		//$corf01anotacion=str_replace('"', '\"', $corf01anotacion);
		$corf01anotacion=str_replace('"', '\"', $corf01anotacion);
		if ($bInserta){
			$sCampos12201='corf01idestprograma, corf01consec, corf01id, corf01visible, corf01anotacion, 
corf01idorigenanexo, corf01idarchivoanexo, corf01usuario, corf01fecha, corf01hora, 
corf01minuto';
			$sValores12201=''.$corf01idestprograma.', '.$corf01consec.', '.$corf01id.', '.$corf01visible.', "'.$corf01anotacion.'", 
0, 0, "'.$corf01usuario.'", "'.$corf01fecha.'", '.$corf01hora.', 
'.$corf01minuto.'';
			if ($APP->utf8==1){
				$sSQL='INSERT INTO corf01estnota ('.$sCampos12201.') VALUES ('.utf8_encode($sValores12201).');';
				}else{
				$sSQL='INSERT INTO corf01estnota ('.$sCampos12201.') VALUES ('.$sValores12201.');';
				}
			if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Guardar 12201 '.$sSQL.'<br>';}
			$result=$objDB->ejecutasql($sSQL);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' [12201].<!-- '.$sSQL.' -->';
				}else{
				if ($bAudita[2]){
					seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 2, $corf01id, $sSQL, $objDB);
					}
				}
			}else{
			$scampo12201[1]='corf01visible';
			$scampo12201[2]='corf01anotacion';
			$scampo12201[3]='corf01usuario';
			$scampo12201[4]='corf01fecha';
			$scampo12201[5]='corf01hora';
			$scampo12201[6]='corf01minuto';
			$svr12201[1]=$corf01visible;
			$svr12201[2]=$corf01anotacion;
			$svr12201[3]=$corf01usuario;
			$svr12201[4]=$corf01fecha;
			$svr12201[5]=$corf01hora;
			$svr12201[6]=$corf01minuto;
			$inumcampos=6;
			$sWhere='corf01id='.$corf01id.'';
			//$sWhere='corf01idestprograma='.$corf01idestprograma.' AND corf01consec='.$corf01consec.'';
			$sSQL='SELECT * FROM corf01estnota WHERE '.$sWhere;
			$sdatos='';
			$bpasa=false;
			$result=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($result)>0){
				$filaorigen=$objDB->sf($result);
				for ($k=1;$k<=$inumcampos;$k++){
					if ($filaorigen[$scampo12201[$k]]!=$svr12201[$k]){
						if ($sdatos!=''){$sdatos=$sdatos.', ';}
						$sdatos=$sdatos.$scampo12201[$k].'="'.$svr12201[$k].'"';
						$bpasa=true;
						}
					}
				}
			if ($bpasa){
				if ($APP->utf8==1){
					$sSQL='UPDATE corf01estnota SET '.utf8_encode($sdatos).' WHERE '.$sWhere.';';
					}else{
					$sSQL='UPDATE corf01estnota SET '.$sdatos.' WHERE '.$sWhere.';';
					}
				$result=$objDB->ejecutasql($sSQL);
				if ($result==false){
					$sError=$ERR['falla_guardar'].' {Anotaciones}. <!-- '.$sSQL.' -->';
					}else{
					if ($bAudita[3]){
						seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 3, $corf01id, $sSQL, $objDB);
						}
					}
				}
			}
		}
	return array($sError, $iAccion, $corf01id, $sDebug);
	}
function f12201_db_Eliminar($aParametros, $objDB, $bDebug=false){
	$iCodModulo=12201;
	$bAudita[4]=false;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_12201='lg/lg_12201_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_12201)){$mensajes_12201='lg/lg_12201_es.php';}
	require $mensajes_todas;
	require $mensajes_12201;
	$sError='';
	$sDebug='';
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$corf01idestprograma=numeros_validar($aParametros[1]);
	$corf01consec=numeros_validar($aParametros[2]);
	$corf01id=numeros_validar($aParametros[3]);
	if ($sError==''){
		if (!seg_revisa_permiso($iCodModulo, 4, $objDB)){
			$sError=$ERR['4'];
			}
		}
	if ($sError==''){
		$sSQL='SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=12201';
		$tablaor=$objDB->ejecutasql($sSQL);
		while ($filaor=$objDB->sf($tablaor)){
			$sSQL='SELECT '.$filaor['campoid'].' FROM '.$filaor['tabla'].' WHERE '.$filaor['camporev'].'='.$corf01id.' LIMIT 0, 1';
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
		$sWhere='corf01id='.$corf01id.'';
		//$sWhere='corf01idestprograma='.$corf01idestprograma.' AND corf01consec='.$corf01consec.'';
		$sSQL='DELETE FROM corf01estnota WHERE '.$sWhere.';';
		$result=$objDB->ejecutasql($sSQL);
		if ($result==false){
			$sError=$ERR['falla_eliminar'].' {12201 Anotaciones}.<!-- '.$sSQL.' -->';
			}else{
			if ($bAudita[4]){
				seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $corf01id, $sSQL, $objDB);
				}
			}
		}
	return array($sError, $sDebug);
	}
function f12201_TablaDetalleV2($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_12201='lg/lg_12201_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_12201)){$mensajes_12201='lg/lg_12201_es.php';}
	require $mensajes_todas;
	require $mensajes_12201;
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
	$core01id=$aParametros[0];
	$pagina=$aParametros[101];
	$lineastabla=$aParametros[102];
	$bAbierta=true;
	//$sSQL='SELECT Campo FROM core01estprograma WHERE core01id='.$core01id;
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
		return array($sLeyenda.'<input id="paginaf12201" name="paginaf12201" type="hidden" value="'.$pagina.'"/><input id="lppf12201" name="lppf12201" type="hidden" value="'.$lineastabla.'"/>', $sDebug);
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
	$sTitulos='Estprograma, Consec, Id, Visible, Anotacion, Origenanexo, Archivoanexo, Usuario, Fecha, Hora, Minuto';
	$sSQL='SELECT TB.corf01idestprograma, TB.corf01consec, TB.corf01id, TB.corf01visible, TB.corf01anotacion, TB.corf01idorigenanexo, TB.corf01idarchivoanexo, T8.unad11razonsocial AS C8_nombre, TB.corf01fecha, TB.corf01hora, TB.corf01minuto, TB.corf01usuario, T8.unad11tipodoc AS C8_td, T8.unad11doc AS C8_doc 
FROM corf01estnota AS TB, unad11terceros AS T8 
WHERE '.$sSQLadd1.' TB.corf01idestprograma='.$core01id.' AND TB.corf01usuario=T8.unad11id '.$sSQLadd.'
ORDER BY TB.corf01consec';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_12201" name="consulta_12201" type="hidden" value="'.$sSQLlista.'"/>
<input id="titulos_12201" name="titulos_12201" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objDB->ejecutasql($sSQL);
	if ($tabladetalle==false){
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 12201: '.$sSQL.'<br>';}
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		//$sLeyenda=$sSQL;
		}else{
		$registros=$objDB->nf($tabladetalle);
		if ($registros==0){
			return array(utf8_encode($sErrConsulta.'<input id="paginaf12201" name="paginaf12201" type="hidden" value="'.$pagina.'"/><input id="lppf12201" name="lppf12201" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
			}
		if ((($registros-1)/$lineastabla)<($pagina-1)){$pagina=(int)(($registros-1)/$lineastabla)+1;}
		if ($registros>$lineastabla){
			$rbase=($pagina-1)*$lineastabla;
			$limite=' LIMIT '.$rbase.', '.$lineastabla;
			$tabladetalle=$objDB->ejecutasql($sSQL.$limite);
			}
		}
	$res=$sErrConsulta.$sLeyenda;
	$res=$res.'<div class="table-responsive">
<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">
<thead class="fondoazul"><tr>
<td><b>'.$ETI['corf01consec'].'</b></td>
<td><b>'.$ETI['corf01visible'].'</b></td>
<td><b>'.$ETI['corf01anotacion'].'</b></td>
<td><b>'.$ETI['corf01idarchivoanexo'].'</b></td>
<td colspan="2"><b>'.$ETI['corf01usuario'].'</b></td>
<td><b>'.$ETI['corf01fecha'].'</b></td>
<td><b>'.$ETI['corf01hora'].'</b></td>
<td align="right">
'.html_paginador('paginaf12201', $registros, $lineastabla, $pagina, 'paginarf12201()').'
'.html_lpp('lppf12201', $lineastabla, 'paginarf12201()').'
</td>
</tr></thead>';
	$tlinea=1;
	while($filadet=$objDB->sf($tabladetalle)){
		$sPrefijo='';
		$sSufijo='';
		$sClass=' class="resaltetabla"';
		$sLink='';
		if (false){
			$sPrefijo='<b>';
			$sSufijo='</b>';
			}
		if(($tlinea%2)!=0){$sClass='';}
		$tlinea++;
		$et_corf01consec=$sPrefijo.$filadet['corf01consec'].$sSufijo;
		$et_corf01visible=$sPrefijo.$filadet['corf01visible'].$sSufijo;
		$et_corf01anotacion=$sPrefijo.cadena_notildes($filadet['corf01anotacion']).$sSufijo;
		$et_corf01idarchivoanexo='';
		if ($filadet['corf01idarchivoanexo']!=0){
			//$et_corf01idarchivoanexo='<img src="verarchivo.php?cont='.$filadet['corf01idorigenanexo'].'&id='.$filadet['corf01idarchivoanexo'].'&maxx=150"/>';
			$et_corf01idarchivoanexo=html_lnkarchivo((int)$filadet['corf01idorigenanexo'], (int)$filadet['corf01idarchivoanexo']);
			}
		$et_corf01usuario_doc='';
		$et_corf01usuario_nombre='';
		if ($filadet['corf01usuario']!=0){
			$et_corf01usuario_doc=$sPrefijo.$filadet['C8_td'].' '.$filadet['C8_doc'].$sSufijo;
			$et_corf01usuario_nombre=$sPrefijo.cadena_notildes($filadet['C8_nombre']).$sSufijo;
			}
		$et_corf01fecha='';
		if ($filadet['corf01fecha']!=0){$et_corf01fecha=$sPrefijo.fecha_desdenumero($filadet['corf01fecha']).$sSufijo;}
		$et_corf01hora=html_TablaHoraMin($filadet['corf01hora'], $filadet['corf01minuto']);
		$et_corf01minuto=$sPrefijo.$filadet['corf01minuto'].$sSufijo;
		if ($bAbierta){
			$sLink='<a href="javascript:cargaridf12201('.$filadet['corf01id'].')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$res=$res.'<tr'.$sClass.'>
<td>'.$et_corf01consec.'</td>
<td>'.$et_corf01visible.'</td>
<td>'.$et_corf01anotacion.'</td>
<td>'.$et_corf01idarchivoanexo.'</td>
<td>'.$et_corf01usuario_doc.'</td>
<td>'.$et_corf01usuario_nombre.'</td>
<td>'.$et_corf01fecha.'</td>
<td>'.$et_corf01hora.'</td>
<td>'.$et_corf01minuto.'</td>
<td>'.$sLink.'</td>
</tr>';
		}
	$res=$res.'</table>
<div class="salto5px"></div>
</div>';
	$objDB->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
	}
// -- 12201 Anotaciones XAJAX 
function elimina_archivo_corf01idarchivoanexo($idPadre, $bDebug=false){
	$_SESSION['u_ultimominuto']=iminutoavance();
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$sError='';
	$sDebug='';
	$bPuedeEliminar=true;
	// Definir las condiciones para que se pueda eliminar y el mensaje de error que se debe presentar
	if ($bPuedeEliminar){
		archivo_eliminar('corf01estnota', 'corf01id', 'corf01idorigenanexo', 'corf01idarchivoanexo', $idPadre, $objDB);
		}
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	if ($bPuedeEliminar){
		$objResponse->call("limpia_corf01idarchivoanexo");
		}else{
		MensajeAlarmaV2('".$sError."', 0);
		}
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f12201_Guardar($valores, $aParametros){
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
		list($sError, $iAccion, $corf01id, $sDebugGuardar)=f12201_db_Guardar($valores, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugGuardar;
		}
	$objResponse=new xajaxResponse();
	if ($sError==''){
		list($sdetalle, $sDebugTabla)=f12201_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugTabla;
		$objResponse->assign('div_f12201detalle', 'innerHTML', $sdetalle);
		//if ($iAccion==2){
			//$objResponse->call('cargaridf12201('.$corf01id.')');
			//}else{
			$objResponse->call('limpiaf12201');
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
function f12201_Traer($aParametros){
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
		$corf01idestprograma=numeros_validar($aParametros[1]);
		$corf01consec=numeros_validar($aParametros[2]);
		if (($corf01idestprograma!='')&&($corf01consec!='')){$besta=true;}
		}else{
		$corf01id=$aParametros[103];
		if ((int)$corf01id!=0){$besta=true;}
		}
	if ($besta){
		$besta=false;
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		$bHayDb=true;
		$sSQLcondi='';
		if ($paso==1){
			$sSQLcondi=$sSQLcondi.'corf01idestprograma='.$corf01idestprograma.' AND corf01consec='.$corf01consec.'';
			}else{
			$sSQLcondi=$sSQLcondi.'corf01id='.$corf01id.'';
			}
		$sSQL='SELECT * FROM corf01estnota WHERE '.$sSQLcondi;
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
		$corf01usuario_id=(int)$fila['corf01usuario'];
		$corf01usuario_td=$APP->tipo_doc;
		$corf01usuario_doc='';
		$corf01usuario_nombre='';
		if ($corf01usuario_id!=0){
			list($corf01usuario_nombre, $corf01usuario_id, $corf01usuario_td, $corf01usuario_doc)=html_tercero($corf01usuario_td, $corf01usuario_doc, $corf01usuario_id, 0, $objDB);
			}
		$corf01consec_nombre='';
		$html_corf01consec=html_oculto('corf01consec', $fila['corf01consec'], $corf01consec_nombre);
		$objResponse->assign('div_corf01consec', 'innerHTML', $html_corf01consec);
		$corf01id_nombre='';
		$html_corf01id=html_oculto('corf01id', $fila['corf01id'], $corf01id_nombre);
		$objResponse->assign('div_corf01id', 'innerHTML', $html_corf01id);
		$objResponse->assign('corf01visible', 'value', $fila['corf01visible']);
		$objResponse->assign('corf01anotacion', 'value', $fila['corf01anotacion']);
		$objResponse->assign('corf01idorigenanexo', 'value', $fila['corf01idorigenanexo']);
		$idorigen=(int)$fila['corf01idorigenanexo'];
		$objResponse->assign('corf01idarchivoanexo', 'value', $fila['corf01idarchivoanexo']);
		$sMuestraAnexar='block';
		$sMuestraEliminar='none';
		$sHTMLArchivo=html_lnkarchivo($idorigen, (int)$fila['corf01idarchivoanexo']);
		if ((int)$fila['corf01idarchivoanexo']!=0){
			$sMuestraEliminar='block';
			//Aqui puede poner validaciones al eliminar - Si no se puede eliminar reversar el mostrado.
			}
		$objResponse->assign('div_corf01idarchivoanexo', 'innerHTML', $sHTMLArchivo);
		$objResponse->call("verboton('banexacorf01idarchivoanexo', '".$sMuestraAnexar."')");
		$objResponse->call("verboton('beliminacorf01idarchivoanexo','".$sMuestraEliminar."')");
		$objResponse->assign('corf01usuario', 'value', $fila['corf01usuario']);
		$objResponse->assign('corf01usuario_td', 'value', $corf01usuario_td);
		$objResponse->assign('corf01usuario_doc', 'value', $corf01usuario_doc);
		$objResponse->assign('div_corf01usuario', 'innerHTML', $corf01usuario_nombre);
		$objResponse->assign('corf01fecha', 'value', $fila['corf01fecha']);
		list($iDia, $iMes, $iAgno)=fecha_DividirNumero($fila['corf01fecha'], true);
		$objResponse->assign('corf01fecha_dia', 'value', $iDia);
		$objResponse->assign('corf01fecha_mes', 'value', $iMes);
		$objResponse->assign('corf01fecha_agno', 'value', $iAgno);
		$html_corf01hora=html_HoraMin('corf01hora', $fila['corf01hora'], 'corf01minuto', $fila['corf01minuto'], false);
		$objResponse->assign('div_corf01hora', 'innerHTML', $html_corf01hora);
		$objResponse->call("MensajeAlarmaV2('', 0)");
		$objResponse->call("verboton('belimina12201','block')");
		}else{
		if ($paso==1){
			$objResponse->assign('corf01consec', 'value', $corf01consec);
			}else{
			$objResponse->call('MensajeAlarmaV2("No se encontro el registro de referencia:'.$corf01id.'", 0)');
			}
		}
	if ($bHayDb){
		$objDB->CerrarConexion();
		}
	return $objResponse;
	}
function f12201_Eliminar($aParametros){
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
	list($sError, $sDebugElimina)=f12201_db_Eliminar($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugElimina;
	$objResponse=new xajaxResponse();
	if ($sError==''){
		list($sDetalle, $sDebugTabla)=f12201_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugTabla;
		$objResponse->assign('div_f12201detalle', 'innerHTML', $sDetalle);
		$objResponse->call('limpiaf12201');
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
function f12201_HtmlTabla($aParametros){
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
	list($sDetalle, $sDebugTabla)=f12201_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f12201detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f12201_PintarLlaves($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	//if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	if (isset($APP->piel)==0){$APP->piel=1;}
	$iPiel=$APP->piel;
	$html_corf01consec='<input id="corf01consec" name="corf01consec" type="text" value="" onchange="revisaf12201()" class="cuatro"/>';
	$html_corf01id='<input id="corf01id" name="corf01id" type="hidden" value=""/>';
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_corf01consec','innerHTML', $html_corf01consec);
	$objResponse->assign('div_corf01id','innerHTML', $html_corf01id);
	return $objResponse;
	}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------
?>