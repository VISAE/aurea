<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2020 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.25.4 martes, 4 de agosto de 2020
--- 3032 Temas asociados
*/
function f3032_HTMLComboV2_saiu32idtema($objDB, $objCombos, $valor){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('saiu32idtema', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	//$objCombos->iAncho=450;
	$objCombos->sAccion='revisaf3032()';
	$sSQL='SELECT saiu03id AS id, saiu03titulo AS nombre FROM saiu03temasol';
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f3032_db_Guardar($valores, $objDB, $bDebug=false){
	$iCodModulo=3032;
	$bAudita[2]=false;
	$bAudita[3]=false;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3032='lg/lg_3032_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3032)){$mensajes_3032='lg/lg_3032_es.php';}
	require $mensajes_todas;
	require $mensajes_3032;
	$sError='';
	$sDebug='';
	$bInserta=false;
	$iAccion=3;
	if(!is_array($valores)){$valores=json_decode(str_replace('\"','"',$valores),true);}
	$saiu32idbasecon=numeros_validar($valores[1]);
	$saiu32idtema=numeros_validar($valores[2]);
	$saiu32id=numeros_validar($valores[3], true);
	$saiu32activo=numeros_validar($valores[4]);
	//if ($saiu32activo==''){$saiu32activo=0;}
	$sSepara=', ';
	if ($saiu32activo==''){$sError=$ERR['saiu32activo'].$sSepara.$sError;}
	//if ($saiu32id==''){$sError=$ERR['saiu32id'].$sSepara.$sError;}//CONSECUTIVO
	if ($saiu32idtema==''){$sError=$ERR['saiu32idtema'].$sSepara.$sError;}
	if ($saiu32idbasecon==''){$sError=$ERR['saiu32idbasecon'].$sSepara.$sError;}
	if ($sError==''){
		if ((int)$saiu32id==0){
			if ($sError==''){
				$sSQL='SELECT saiu32idbasecon FROM saiu32basecontema WHERE saiu32idbasecon='.$saiu32idbasecon.' AND saiu32idtema='.$saiu32idtema.'';
				$result=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($result)!=0){
					$sError=$ERR['existe'];
					}else{
					if (!seg_revisa_permiso($iCodModulo, 2, $objDB)){$sError=$ERR['2'];}
					}
				}
			if ($sError==''){
				$saiu32id=tabla_consecutivo('saiu32basecontema', 'saiu32id', '', $objDB);
				if ($saiu32id==-1){$sError=$objDB->serror;}
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
			$sCampos3032='saiu32idbasecon, saiu32idtema, saiu32id, saiu32activo';
			$sValores3032=''.$saiu32idbasecon.', '.$saiu32idtema.', '.$saiu32id.', '.$saiu32activo.'';
			if ($APP->utf8==1){
				$sSQL='INSERT INTO saiu32basecontema ('.$sCampos3032.') VALUES ('.utf8_encode($sValores3032).');';
				}else{
				$sSQL='INSERT INTO saiu32basecontema ('.$sCampos3032.') VALUES ('.$sValores3032.');';
				}
			if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Guardar 3032 '.$sSQL.'<br>';}
			$result=$objDB->ejecutasql($sSQL);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' [3032].<!-- '.$sSQL.' -->';
				}else{
				if ($bAudita[2]){
					seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 2, $saiu32id, $sSQL, $objDB);
					}
				}
			}else{
			$scampo3032[1]='saiu32activo';
			$svr3032[1]=$saiu32activo;
			$inumcampos=1;
			$sWhere='saiu32id='.$saiu32id.'';
			//$sWhere='saiu32idbasecon='.$saiu32idbasecon.' AND saiu32idtema='.$saiu32idtema.'';
			$sSQL='SELECT * FROM saiu32basecontema WHERE '.$sWhere;
			$sdatos='';
			$bpasa=false;
			$result=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($result)>0){
				$filaorigen=$objDB->sf($result);
				for ($k=1;$k<=$inumcampos;$k++){
					if ($filaorigen[$scampo3032[$k]]!=$svr3032[$k]){
						if ($sdatos!=''){$sdatos=$sdatos.', ';}
						$sdatos=$sdatos.$scampo3032[$k].'="'.$svr3032[$k].'"';
						$bpasa=true;
						}
					}
				}
			if ($bpasa){
				if ($APP->utf8==1){
					$sSQL='UPDATE saiu32basecontema SET '.utf8_encode($sdatos).' WHERE '.$sWhere.';';
					}else{
					$sSQL='UPDATE saiu32basecontema SET '.$sdatos.' WHERE '.$sWhere.';';
					}
				$result=$objDB->ejecutasql($sSQL);
				if ($result==false){
					$sError=$ERR['falla_guardar'].' {Temas asociados}. <!-- '.$sSQL.' -->';
					}else{
					if ($bAudita[3]){
						seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 3, $saiu32id, $sSQL, $objDB);
						}
					}
				}
			}
		}
	return array($sError, $iAccion, $saiu32id, $sDebug);
	}
function f3032_db_Eliminar($aParametros, $objDB, $bDebug=false){
	$iCodModulo=3032;
	$bAudita[4]=false;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3032='lg/lg_3032_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3032)){$mensajes_3032='lg/lg_3032_es.php';}
	require $mensajes_todas;
	require $mensajes_3032;
	$sError='';
	$sDebug='';
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$saiu32idbasecon=numeros_validar($aParametros[1]);
	$saiu32idtema=numeros_validar($aParametros[2]);
	$saiu32id=numeros_validar($aParametros[3]);
	if ($sError==''){
		if (!seg_revisa_permiso($iCodModulo, 4, $objDB)){
			$sError=$ERR['4'];
			}
		}
	if ($sError==''){
		$sSQL='SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=3032';
		$tablaor=$objDB->ejecutasql($sSQL);
		while ($filaor=$objDB->sf($tablaor)){
			$sSQL='SELECT '.$filaor['campoid'].' FROM '.$filaor['tabla'].' WHERE '.$filaor['camporev'].'='.$saiu32id.' LIMIT 0, 1';
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
		$sWhere='saiu32id='.$saiu32id.'';
		//$sWhere='saiu32idbasecon='.$saiu32idbasecon.' AND saiu32idtema='.$saiu32idtema.'';
		$sSQL='DELETE FROM saiu32basecontema WHERE '.$sWhere.';';
		$result=$objDB->ejecutasql($sSQL);
		if ($result==false){
			$sError=$ERR['falla_eliminar'].' {3032 Temas asociados}.<!-- '.$sSQL.' -->';
			}else{
			if ($bAudita[4]){
				seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $saiu32id, $sSQL, $objDB);
				}
			}
		}
	return array($sError, $sDebug);
	}
function f3032_TablaDetalleV2($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3032='lg/lg_3032_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3032)){$mensajes_3032='lg/lg_3032_es.php';}
	require $mensajes_todas;
	require $mensajes_3032;
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
	$saiu31id=$aParametros[0];
	$pagina=$aParametros[101];
	$lineastabla=$aParametros[102];
	$bAbierta=false;
	$sSQL='SELECT saiu31estado FROM saiu31baseconocimiento WHERE saiu31id='.$saiu31id;
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		if ($fila['saiu31estado']!='S'){$bAbierta=true;}
		}
	$sLeyenda='';
	if ($sLeyenda!=''){
		$sLeyenda='<div class="salto1px"></div>
<div class="GrupoCamposAyuda">
'.$sLeyenda.'
<div class="salto1px"></div>
</div>';
		return array($sLeyenda.'<input id="paginaf3032" name="paginaf3032" type="hidden" value="'.$pagina.'"/><input id="lppf3032" name="lppf3032" type="hidden" value="'.$lineastabla.'"/>', $sDebug);
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
	$sTitulos='Basecon, Tema, Id, Activo';
	$sSQL='SELECT TB.saiu32idbasecon, T2.saiu03titulo, TB.saiu32id, TB.saiu32activo, TB.saiu32idtema 
FROM saiu32basecontema AS TB, saiu03temasol AS T2 
WHERE '.$sSQLadd1.' TB.saiu32idbasecon='.$saiu31id.' AND TB.saiu32idtema=T2.saiu03id '.$sSQLadd.'
ORDER BY TB.saiu32idtema';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_3032" name="consulta_3032" type="hidden" value="'.$sSQLlista.'"/>
<input id="titulos_3032" name="titulos_3032" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objDB->ejecutasql($sSQL);
	if ($tabladetalle==false){
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 3032: '.$sSQL.'<br>';}
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		//$sLeyenda=$sSQL;
		}else{
		$registros=$objDB->nf($tabladetalle);
		if ($registros==0){
			return array(utf8_encode($sErrConsulta.'<input id="paginaf3032" name="paginaf3032" type="hidden" value="'.$pagina.'"/><input id="lppf3032" name="lppf3032" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
<td><b>'.$ETI['saiu32idtema'].'</b></td>
<td><b>'.$ETI['saiu32activo'].'</b></td>
<td align="right">
'.html_paginador('paginaf3032', $registros, $lineastabla, $pagina, 'paginarf3032()').'
'.html_lpp('lppf3032', $lineastabla, 'paginarf3032()').'
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
		$et_saiu32idtema=$sPrefijo.cadena_notildes($filadet['saiu03titulo']).$sSufijo;
		$et_saiu32activo=$sPrefijo.$filadet['saiu32activo'].$sSufijo;
		if ($bAbierta){
			$sLink='<a href="javascript:cargaridf3032('.$filadet['saiu32id'].')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$res=$res.'<tr'.$sClass.'>
<td>'.$et_saiu32idtema.'</td>
<td>'.$et_saiu32activo.'</td>
<td>'.$sLink.'</td>
</tr>';
		}
	$res=$res.'</table>';
	$objDB->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
	}
// -- 3032 Temas asociados XAJAX 
function f3032_Guardar($valores, $aParametros){
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
		list($sError, $iAccion, $saiu32id, $sDebugGuardar)=f3032_db_Guardar($valores, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugGuardar;
		}
	$objResponse=new xajaxResponse();
	if ($sError==''){
		list($sdetalle, $sDebugTabla)=f3032_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugTabla;
		$objResponse->assign('div_f3032detalle', 'innerHTML', $sdetalle);
		//if ($iAccion==2){
			//$objResponse->call('cargaridf3032('.$saiu32id.')');
			//}else{
			$objResponse->call('limpiaf3032');
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
function f3032_Traer($aParametros){
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
		$saiu32idbasecon=numeros_validar($aParametros[1]);
		$saiu32idtema=numeros_validar($aParametros[2]);
		if (($saiu32idbasecon!='')&&($saiu32idtema!='')){$besta=true;}
		}else{
		$saiu32id=$aParametros[103];
		if ((int)$saiu32id!=0){$besta=true;}
		}
	if ($besta){
		$besta=false;
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		$bHayDb=true;
		$sSQLcondi='';
		if ($paso==1){
			$sSQLcondi=$sSQLcondi.'saiu32idbasecon='.$saiu32idbasecon.' AND saiu32idtema='.$saiu32idtema.'';
			}else{
			$sSQLcondi=$sSQLcondi.'saiu32id='.$saiu32id.'';
			}
		$sSQL='SELECT * FROM saiu32basecontema WHERE '.$sSQLcondi;
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
		list($saiu32idtema_nombre, $serror_det)=tabla_campoxid('saiu03temasol','saiu03titulo','saiu03id', $fila['saiu32idtema'],'{'.$ETI['msg_sindato'].'}', $objDB);
		$html_saiu32idtema=html_oculto('saiu32idtema', $fila['saiu32idtema'], $saiu32idtema_nombre);
		$objResponse->assign('div_saiu32idtema', 'innerHTML', $html_saiu32idtema);
		$saiu32id_nombre='';
		$html_saiu32id=html_oculto('saiu32id', $fila['saiu32id'], $saiu32id_nombre);
		$objResponse->assign('div_saiu32id', 'innerHTML', $html_saiu32id);
		$objResponse->assign('saiu32activo', 'value', $fila['saiu32activo']);
		$objResponse->call("MensajeAlarmaV2('', 0)");
		$objResponse->call("verboton('belimina3032','block')");
		}else{
		if ($paso==1){
			$objResponse->assign('saiu32idtema', 'value', $saiu32idtema);
			}else{
			$objResponse->call('MensajeAlarmaV2("No se encontro el registro de referencia:'.$saiu32id.'", 0)');
			}
		}
	if ($bHayDb){
		$objDB->CerrarConexion();
		}
	return $objResponse;
	}
function f3032_Eliminar($aParametros){
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
	list($sError, $sDebugElimina)=f3032_db_Eliminar($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugElimina;
	$objResponse=new xajaxResponse();
	if ($sError==''){
		list($sDetalle, $sDebugTabla)=f3032_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugTabla;
		$objResponse->assign('div_f3032detalle', 'innerHTML', $sDetalle);
		$objResponse->call('limpiaf3032');
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
function f3032_HtmlTabla($aParametros){
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
	list($sDetalle, $sDebugTabla)=f3032_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f3032detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f3032_PintarLlaves($aParametros){
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
	$html_saiu32idtema=f3032_HTMLComboV2_saiu32idtema($objDB, $objCombos, 0);
	$html_saiu32id='<input id="saiu32id" name="saiu32id" type="hidden" value=""/>';
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_saiu32idtema','innerHTML', $html_saiu32idtema);
	$objResponse->assign('div_saiu32id','innerHTML', $html_saiu32id);
	return $objResponse;
	}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------
?>