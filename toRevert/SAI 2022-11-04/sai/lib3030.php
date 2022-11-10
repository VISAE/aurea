<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2020 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.25.5 jueves, 13 de agosto de 2020
--- 3030 Anotaciones
*/
function f3030_db_Guardar($valores, $objDB, $bDebug=false){
	$iCodModulo=3030;
	$bAudita[2]=false;
	$bAudita[3]=false;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3030='lg/lg_3030_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3030)){$mensajes_3030='lg/lg_3030_es.php';}
	require $mensajes_todas;
	require $mensajes_3030;
	$sError='';
	$sDebug='';
	$bInserta=false;
	$iAccion=3;
	if(!is_array($valores)){$valores=json_decode(str_replace('\"','"',$valores),true);}
	if (isset($valores[98])==0){$valores[98]='';}
	$saiu30idsolicitud=numeros_validar($valores[1]);
	$saiu30consec=numeros_validar($valores[2]);
	$saiu30id=numeros_validar($valores[3], true);
	$saiu30visiblealinteresado=numeros_validar($valores[4]);
	$saiu30anotacion=htmlspecialchars(trim($valores[5]));
	$saiu30idusuario=numeros_validar($valores[6]);
	$saiu30fecha=$valores[7];
	$saiu30hora=numeros_validar($valores[8]);
	$saiu30minuto=numeros_validar($valores[9]);
	$iAgno=numeros_validar($valores[98]);
	if ($iAgno==''){
		$sError='<b>No se ha definido un a&ntilde;o</b>';
	}else{
		$sTabla30='saiu30anotaciones_'.$iAgno;
		if (!tabla_existe($sTabla30, $objDB)){
			$sError='No ha sido posible encontrar el origen de datos '.$sTabla30;
		}
	}
	//if ($saiu30visiblealinteresado==''){$saiu30visiblealinteresado=0;}
	//if ($saiu30hora==''){$saiu30hora=0;}
	//if ($saiu30minuto==''){$saiu30minuto=0;}
	$sSepara=', ';
	//if ($saiu30minuto==''){$sError=$ERR['saiu30minuto'].$sSepara.$sError;}
	//if ($saiu30hora==''){$sError=$ERR['saiu30hora'].$sSepara.$sError;}
	if ($saiu30fecha==0){
		//$saiu30fecha=fecha_DiaMod();
		//$sError=$ERR['saiu30fecha'].$sSepara.$sError;
		}
	//if ($saiu30idusuario==0){$sError=$ERR['saiu30idusuario'].$sSepara.$sError;}
	if ($saiu30anotacion==''){$sError=$ERR['saiu30anotacion'].$sSepara.$sError;}
	if ($saiu30visiblealinteresado==''){$sError=$ERR['saiu30visiblealinteresado'].$sSepara.$sError;}
	//if ($saiu30id==''){$sError=$ERR['saiu30id'].$sSepara.$sError;}//CONSECUTIVO
	//if ($saiu30consec==''){$sError=$ERR['saiu30consec'].$sSepara.$sError;}//CONSECUTIVO
	if ($saiu30idsolicitud==''){$sError=$ERR['saiu30idsolicitud'].$sSepara.$sError;}
	//if ($sError==''){
		//list($sError, $sInfo)=tercero_Bloqueado($saiu30idusuario, $objDB);
		//if ($sInfo!=''){$sError=$sError.'<br>'.$sInfo;}
		//}
	if ($sError==''){
		if ((int)$saiu30id==0){
			if ((int)$saiu30consec==0){
				$saiu30consec=tabla_consecutivo($sTabla30, 'saiu30consec', 'saiu30idsolicitud='.$saiu30idsolicitud.'', $objDB);
				if ($saiu30consec==-1){$sError=$objDB->serror;}
				}else{
				if (!seg_revisa_permiso($iCodModulo, 8, $objDB)){$sError=$ERR['8'];}
				}
			if ($sError==''){
				$sSQL='SELECT saiu30idsolicitud FROM '.$sTabla30.' WHERE saiu30idsolicitud='.$saiu30idsolicitud.' AND saiu30consec='.$saiu30consec.'';
				$result=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($result)!=0){
					$sError=$ERR['existe'];
					}else{
					if (!seg_revisa_permiso($iCodModulo, 2, $objDB)){$sError=$ERR['2'];}
					}
				}
			if ($sError==''){
				$saiu30id=tabla_consecutivo($sTabla30, 'saiu30id', '', $objDB);
				if ($saiu30id==-1){$sError=$objDB->serror;}
				$bInserta=true;
				$iAccion=2;
				}
			}else{
			if (!seg_revisa_permiso($iCodModulo, 3, $objDB)){$sError=$ERR['3'];}
			}
		}
	if ($sError==''){
		if ($bInserta){
			$saiu30idusuario=$_SESSION['unad_id_tercero'];
			$saiu30fecha=fecha_DiaMod();
			$saiu30hora=fecha_hora();
			$saiu30minuto=fecha_minuto();
			}
		}
	if ($sError==''){
		//Si el campo saiu30anotacion permite html quite la linea htmlspecialchars para el campo y habilite la siguiente linea:
		//$saiu30anotacion=str_replace('"', '\"', $saiu30anotacion);
		$saiu30anotacion=str_replace('"', '\"', $saiu30anotacion);
		if ($bInserta){
			$sCampos3030='saiu30idsolicitud, saiu30consec, saiu30id, saiu30visiblealinteresado, saiu30anotacion, 
			saiu30idusuario, saiu30fecha, saiu30hora, saiu30minuto';
			$sValores3030=''.$saiu30idsolicitud.', '.$saiu30consec.', '.$saiu30id.', '.$saiu30visiblealinteresado.', "'.$saiu30anotacion.'", 
			'.$saiu30idusuario.', '.$saiu30fecha.', '.$saiu30hora.', '.$saiu30minuto.'';
			if ($APP->utf8==1){
				$sSQL='INSERT INTO '.$sTabla30.' ('.$sCampos3030.') VALUES ('.utf8_encode($sValores3030).');';
				}else{
				$sSQL='INSERT INTO '.$sTabla30.' ('.$sCampos3030.') VALUES ('.$sValores3030.');';
				}
			if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Guardar 3030 '.$sSQL.'<br>';}
			$result=$objDB->ejecutasql($sSQL);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' [3030].<!-- '.$sSQL.' -->';
				}else{
				if ($bAudita[2]){
					seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 2, $saiu30id, $sSQL, $objDB);
					}
				}
			}else{
			$scampo3030[1]='saiu30visiblealinteresado';
			$scampo3030[2]='saiu30anotacion';
			$scampo3030[3]='saiu30idusuario';
			$scampo3030[4]='saiu30fecha';
			$svr3030[1]=$saiu30visiblealinteresado;
			$svr3030[2]=$saiu30anotacion;
			$svr3030[3]=$saiu30idusuario;
			$svr3030[4]=$saiu30fecha;
			$inumcampos=4;
			$sWhere='saiu30id='.$saiu30id.'';
			//$sWhere='saiu30idsolicitud='.$saiu30idsolicitud.' AND saiu30consec='.$saiu30consec.'';
			$sSQL='SELECT * FROM '.$sTabla30.' WHERE '.$sWhere;
			$sdatos='';
			$bpasa=false;
			$result=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($result)>0){
				$filaorigen=$objDB->sf($result);
				for ($k=1;$k<=$inumcampos;$k++){
					if ($filaorigen[$scampo3030[$k]]!=$svr3030[$k]){
						if ($sdatos!=''){$sdatos=$sdatos.', ';}
						$sdatos=$sdatos.$scampo3030[$k].'="'.$svr3030[$k].'"';
						$bpasa=true;
						}
					}
				}
			if ($bpasa){
				if ($APP->utf8==1){
					$sSQL='UPDATE '.$sTabla30.' SET '.utf8_encode($sdatos).' WHERE '.$sWhere.';';
					}else{
					$sSQL='UPDATE '.$sTabla30.' SET '.$sdatos.' WHERE '.$sWhere.';';
					}
				$result=$objDB->ejecutasql($sSQL);
				if ($result==false){
					$sError=$ERR['falla_guardar'].' {Anotaciones}. <!-- '.$sSQL.' -->';
					}else{
					if ($bAudita[3]){
						seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 3, $saiu30id, $sSQL, $objDB);
						}
					}
				}
			}
		}
	return array($sError, $iAccion, $saiu30id, $sDebug);
	}
function f3030_db_Eliminar($aParametros, $objDB, $bDebug=false){
	$iCodModulo=3030;
	$bAudita[4]=false;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3030='lg/lg_3030_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3030)){$mensajes_3030='lg/lg_3030_es.php';}
	require $mensajes_todas;
	require $mensajes_3030;
	$sError='';
	$sDebug='';
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$saiu30idsolicitud=numeros_validar($aParametros[1]);
	$saiu30consec=numeros_validar($aParametros[2]);
	$saiu30id=numeros_validar($aParametros[3]);
	$iAgno=numeros_validar($aParametros[98]);
	if ($iAgno==''){
		$sError='<b>No se ha definido un a&ntilde;o</b>';
	}else{
		$sTabla30='saiu30anotaciones_'.$iAgno;
		if (!tabla_existe($sTabla30, $objDB)){
			$sError='No ha sido posible encontrar el origen de datos '.$sTabla30;
		}
	}
	if ($sError==''){
		if (!seg_revisa_permiso($iCodModulo, 4, $objDB)){
			$sError=$ERR['4'];
			}
		}
	if ($sError==''){
		$sSQL='SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=3030';
		$tablaor=$objDB->ejecutasql($sSQL);
		while ($filaor=$objDB->sf($tablaor)){
			$sSQL='SELECT '.$filaor['campoid'].' FROM '.$filaor['tabla'].' WHERE '.$filaor['camporev'].'='.$saiu30id.' LIMIT 0, 1';
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
		$sWhere='saiu30id='.$saiu30id.'';
		//$sWhere='saiu30idsolicitud='.$saiu30idsolicitud.' AND saiu30consec='.$saiu30consec.'';
		$sSQL='DELETE FROM '.$sTabla30.' WHERE '.$sWhere.';';
		$result=$objDB->ejecutasql($sSQL);
		if ($result==false){
			$sError=$ERR['falla_eliminar'].' {3030 Anotaciones}.<!-- '.$sSQL.' -->';
			}else{
			if ($bAudita[4]){
				seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $saiu30id, $sSQL, $objDB);
				}
			}
		}
	return array($sError, $sDebug);
	}
function f3030_TablaDetalleV2($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3030='lg/lg_3030_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3030)){$mensajes_3030='lg/lg_3030_es.php';}
	require $mensajes_todas;
	require $mensajes_3030;
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	if (isset($aParametros[0])==0){$aParametros[0]=-1;}
	if (isset($aParametros[98])==0){$aParametros[98]='';}
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
	$saiu28id=$aParametros[0];
	$iAgno=$aParametros[98];
	$pagina=$aParametros[101];
	$lineastabla=$aParametros[102];
	$bAbierta=true;
	//$sSQL='SELECT Campo FROM saiu28mesaayuda WHERE saiu28id='.$saiu28id;
	//$tabla=$objDB->ejecutasql($sSQL);
	//if ($objDB->nf($tabla)>0){
		//$fila=$objDB->sf($tabla);
		//if ($fila['Campo']!='S'){$bAbierta=true;}
		//}
	$sLeyenda='';
	if ($iAgno==''){
		$sLeyenda='<b>No se ha definido un a&ntilde;o</b>';
	}else{
		$sTabla30='saiu30anotaciones_'.$iAgno;
		if (!tabla_existe($sTabla30, $objDB)){
			$sLeyenda='No ha sido posible encontrar el origen de datos '.$sTabla30;
		}
	}
	if ($sLeyenda!=''){
		$sLeyenda='<div class="salto1px"></div>
		<div class="GrupoCamposAyuda">
		'.$sLeyenda.'
		<div class="salto1px"></div>
		</div>';
		return array($sLeyenda.'<input id="paginaf3030" name="paginaf3030" type="hidden" value="'.$pagina.'"/><input id="lppf3030" name="lppf3030" type="hidden" value="'.$lineastabla.'"/>', $sDebug);
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
	$sTitulos='Solicitud, Consec, Id, Visiblealinteresado, Anotacion, Usuario, Fecha, Hora, Minuto';
	$sSQL='SELECT TB.saiu30idsolicitud, TB.saiu30consec, TB.saiu30id, TB.saiu30visiblealinteresado, TB.saiu30anotacion, T6.unad11razonsocial AS C6_nombre, TB.saiu30fecha, TB.saiu30hora, TB.saiu30minuto, TB.saiu30idusuario, T6.unad11tipodoc AS C6_td, T6.unad11doc AS C6_doc 
	FROM '.$sTabla30.' AS TB, unad11terceros AS T6 
	WHERE '.$sSQLadd1.' TB.saiu30idsolicitud='.$saiu28id.' AND TB.saiu30idusuario=T6.unad11id '.$sSQLadd.'
	ORDER BY TB.saiu30consec';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_3030" name="consulta_3030" type="hidden" value="'.$sSQLlista.'"/>
	<input id="titulos_3030" name="titulos_3030" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objDB->ejecutasql($sSQL);
	if ($tabladetalle==false){
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 3030: '.$sSQL.'<br>';}
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		//$sLeyenda=$sSQL;
		}else{
		$registros=$objDB->nf($tabladetalle);
		if ($registros==0){
			return array(utf8_encode($sErrConsulta.'<input id="paginaf3030" name="paginaf3030" type="hidden" value="'.$pagina.'"/><input id="lppf3030" name="lppf3030" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
			}
		if ((($registros-1)/$lineastabla)<($pagina-1)){$pagina=(int)(($registros-1)/$lineastabla)+1;}
		if ($registros>$lineastabla){
			$rbase=($pagina-1)*$lineastabla;
			$limite=' LIMIT '.$rbase.', '.$lineastabla;
			$tabladetalle=$objDB->ejecutasql($sSQL.$limite);
			}
		}
	$res=$sErrConsulta.$sLeyenda.'<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">
	<thead class="fondoazul"><tr>
	<td><b>'.$ETI['saiu30consec'].'</b></td>
	<td><b>'.$ETI['saiu30visiblealinteresado'].'</b></td>
	<td><b>'.$ETI['saiu30anotacion'].'</b></td>
	<td colspan="2"><b>'.$ETI['saiu30idusuario'].'</b></td>
	<td><b>'.$ETI['saiu30fecha'].'</b></td>
	<td><b>'.$ETI['saiu30hora'].'</b></td>
	<td align="right">
	'.html_paginador('paginaf3030', $registros, $lineastabla, $pagina, 'paginarf3030()').'
	'.html_lpp('lppf3030', $lineastabla, 'paginarf3030()').'
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
		$et_saiu30consec=$sPrefijo.$filadet['saiu30consec'].$sSufijo;
		$et_saiu30visiblealinteresado=$sPrefijo.$filadet['saiu30visiblealinteresado'].$sSufijo;
		$et_saiu30anotacion=$sPrefijo.cadena_notildes($filadet['saiu30anotacion']).$sSufijo;
		$et_saiu30idusuario_doc='';
		$et_saiu30idusuario_nombre='';
		if ($filadet['saiu30idusuario']!=0){
			$et_saiu30idusuario_doc=$sPrefijo.$filadet['C6_td'].' '.$filadet['C6_doc'].$sSufijo;
			$et_saiu30idusuario_nombre=$sPrefijo.cadena_notildes($filadet['C6_nombre']).$sSufijo;
			}
		$et_saiu30fecha='';
		if ($filadet['saiu30fecha']!=0){$et_saiu30fecha=$sPrefijo.fecha_desdenumero($filadet['saiu30fecha']).$sSufijo;}
		$et_saiu30hora=html_TablaHoraMin($filadet['saiu30hora'], $filadet['saiu30minuto']);
		$et_saiu30minuto=$sPrefijo.$filadet['saiu30minuto'].$sSufijo;
		if ($bAbierta){
			$sLink='<a href="javascript:cargaridf3030('.$filadet['saiu30id'].')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$res=$res.'<tr'.$sClass.'>
		<td>'.$et_saiu30consec.'</td>
		<td>'.$et_saiu30visiblealinteresado.'</td>
		<td>'.$et_saiu30anotacion.'</td>
		<td>'.$et_saiu30idusuario_doc.'</td>
		<td>'.$et_saiu30idusuario_nombre.'</td>
		<td>'.$et_saiu30fecha.'</td>
		<td>'.$et_saiu30hora.'</td>
		<td>'.$et_saiu30minuto.'</td>
		<td>'.$sLink.'</td>
		</tr>';
		}
	$res=$res.'</table>';
	$objDB->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
	}
// -- 3030 Anotaciones XAJAX 
function f3030_Guardar($valores, $aParametros){
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
		list($sError, $iAccion, $saiu30id, $sDebugGuardar)=f3030_db_Guardar($valores, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugGuardar;
		}
	$objResponse=new xajaxResponse();
	if ($sError==''){
		list($sdetalle, $sDebugTabla)=f3030_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugTabla;
		$objResponse->assign('div_f3030detalle', 'innerHTML', $sdetalle);
		//if ($iAccion==2){
			//$objResponse->call('cargaridf3030('.$saiu30id.')');
			//}else{
			$objResponse->call('limpiaf3030');
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
function f3030_Traer($aParametros){
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
	$iAgno=numeros_validar($aParametros[98]);
	if ($iAgno==''){
		$sError='<b>No se ha definido un a&ntilde;o</b>';
	}else{
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		$bHayDb=true;
		$sTabla30='saiu30anotaciones_'.$iAgno;
		if (!tabla_existe($sTabla30, $objDB)){
			$sError='No ha sido posible encontrar el origen de datos '.$sTabla30;
		}
	}
	if ($paso==1){
		$saiu30idsolicitud=numeros_validar($aParametros[1]);
		$saiu30consec=numeros_validar($aParametros[2]);
		if (($saiu30idsolicitud!='')&&($saiu30consec!='')){$besta=true;}
		}else{
		$saiu30id=$aParametros[103];
		if ((int)$saiu30id!=0){$besta=true;}
		}
	if ($besta){
		$besta=false;
		$sSQLcondi='';
		if ($paso==1){
			$sSQLcondi=$sSQLcondi.'saiu30idsolicitud='.$saiu30idsolicitud.' AND saiu30consec='.$saiu30consec.'';
			}else{
			$sSQLcondi=$sSQLcondi.'saiu30id='.$saiu30id.'';
			}
		$sSQL='SELECT * FROM '.$sTabla30.' WHERE '.$sSQLcondi;
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
		$saiu30idusuario_id=(int)$fila['saiu30idusuario'];
		$saiu30idusuario_td=$APP->tipo_doc;
		$saiu30idusuario_doc='';
		$saiu30idusuario_nombre='';
		if ($saiu30idusuario_id!=0){
			list($saiu30idusuario_nombre, $saiu30idusuario_id, $saiu30idusuario_td, $saiu30idusuario_doc)=html_tercero($saiu30idusuario_td, $saiu30idusuario_doc, $saiu30idusuario_id, 0, $objDB);
			}
		$saiu30consec_nombre='';
		$html_saiu30consec=html_oculto('saiu30consec', $fila['saiu30consec'], $saiu30consec_nombre);
		$objResponse->assign('div_saiu30consec', 'innerHTML', $html_saiu30consec);
		$saiu30id_nombre='';
		$html_saiu30id=html_oculto('saiu30id', $fila['saiu30id'], $saiu30id_nombre);
		$objResponse->assign('div_saiu30id', 'innerHTML', $html_saiu30id);
		$objResponse->assign('saiu30visiblealinteresado', 'value', $fila['saiu30visiblealinteresado']);
		$objResponse->assign('saiu30anotacion', 'value', $fila['saiu30anotacion']);
		$bOculto=true;
		$html_saiu30idusuario_llaves=html_DivTerceroV2('saiu30idusuario', $saiu30idusuario_td, $saiu30idusuario_doc, $bOculto, $saiu30idusuario_id, $ETI['ing_doc']);
		$objResponse->assign('saiu30idusuario', 'value', $saiu30idusuario_id);
		$objResponse->assign('div_saiu30idusuario_llaves', 'innerHTML', $html_saiu30idusuario_llaves);
		$objResponse->assign('div_saiu30idusuario', 'innerHTML', $saiu30idusuario_nombre);
		$html_saiu30fecha=html_oculto('saiu30fecha', $fila['saiu30fecha'], fecha_desdenumero($fila['saiu30fecha']));
		$objResponse->assign('div_saiu30fecha', 'innerHTML', $html_saiu30fecha);
		$html_saiu30hora=html_HoraMin('saiu30hora', $fila['saiu30hora'], 'saiu30minuto', $fila['saiu30minuto'], true);
		$objResponse->assign('div_saiu30hora', 'innerHTML', $html_saiu30hora);
		$objResponse->call("MensajeAlarmaV2('', 0)");
		$objResponse->call("verboton('belimina3030','block')");
		}else{
		if ($paso==1){
			$objResponse->assign('saiu30consec', 'value', $saiu30consec);
			}else{
			$objResponse->call('MensajeAlarmaV2("No se encontro el registro de referencia:'.$saiu30id.'", 0)');
			}
		}
	if ($bHayDb){
		$objDB->CerrarConexion();
		}
	return $objResponse;
	}
function f3030_Eliminar($aParametros){
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
	list($sError, $sDebugElimina)=f3030_db_Eliminar($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugElimina;
	$objResponse=new xajaxResponse();
	if ($sError==''){
		list($sDetalle, $sDebugTabla)=f3030_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugTabla;
		$objResponse->assign('div_f3030detalle', 'innerHTML', $sDetalle);
		$objResponse->call('limpiaf3030');
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
function f3030_HtmlTabla($aParametros){
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
	list($sDetalle, $sDebugTabla)=f3030_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f3030detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f3030_PintarLlaves($aParametros){
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
	$objCombos=new clsHtmlCombos();
	$html_saiu30consec='<input id="saiu30consec" name="saiu30consec" type="text" value="" onchange="revisaf3030()" class="cuatro"/>';
	$html_saiu30id='<input id="saiu30id" name="saiu30id" type="hidden" value=""/>';
	list($saiu30idusuario_rs, $saiu30idusuario, $saiu30idusuario_td, $saiu30idusuario_doc)=html_tercero('CC', '', $_SESSION['unad_id_tercero'], 0, $objDB);
	$html_saiu30idusuario_llaves=html_DivTerceroV2('saiu30idusuario', $saiu30idusuario_td, $saiu30idusuario_doc, true, 0, $ETI['ing_doc']);
	//$et_saiu30fecha='00/00/0000';
	$et_saiu30fecha=fecha_hoy();
	$html_saiu30fecha=html_oculto('saiu30fecha', fecha_DiaMod(), $et_saiu30fecha);
	$html_saiu30hora=html_HoraMin('saiu30hora', fecha_hora(), 'saiu30minuto', fecha_minuto(), true);
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_saiu30consec','innerHTML', $html_saiu30consec);
	$objResponse->assign('div_saiu30id','innerHTML', $html_saiu30id);
	$objResponse->assign('saiu30idusuario','value', $saiu30idusuario);
	$objResponse->assign('div_saiu30idusuario_llaves','innerHTML', $html_saiu30idusuario_llaves);
	$objResponse->assign('div_saiu30idusuario','innerHTML', $saiu30idusuario_rs);
	$objResponse->assign('div_saiu30fecha','innerHTML', $html_saiu30fecha);
	$objResponse->assign('div_saiu30hora','innerHTML', $html_saiu30hora);
	return $objResponse;
	}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------
?>