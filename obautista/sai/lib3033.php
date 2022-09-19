<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2020 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.25.4 miércoles, 5 de agosto de 2020
--- 3033 Palabras claves
*/
function f3033_HTMLComboV2_saiu33idpalabra($objDB, $objCombos, $valor){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('saiu33idpalabra', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	//$objCombos->iAncho=450;
	$objCombos->sAccion='revisaf3033()';
	$sSQL='SELECT saiu34id AS id, saiu34palabra AS nombre FROM saiu34palabraclave';
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f3033_db_Guardar($valores, $objDB, $bDebug=false){
	$iCodModulo=3033;
	$bAudita[2]=false;
	$bAudita[3]=false;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3033='lg/lg_3033_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3033)){$mensajes_3033='lg/lg_3033_es.php';}
	require $mensajes_todas;
	require $mensajes_3033;
	$sError='';
	$sDebug='';
	$bInserta=false;
	$iAccion=3;
	if(!is_array($valores)){$valores=json_decode(str_replace('\"','"',$valores),true);}
	$saiu33idbasecon=numeros_validar($valores[1]);
	$saiu33idpalabra=numeros_validar($valores[2]);
	$saiu33id=numeros_validar($valores[3], true);
	$saiu33activo=numeros_validar($valores[4]);
	//if ($saiu33activo==''){$saiu33activo=0;}
	$sSepara=', ';
	if ($saiu33activo==''){$sError=$ERR['saiu33activo'].$sSepara.$sError;}
	//if ($saiu33id==''){$sError=$ERR['saiu33id'].$sSepara.$sError;}//CONSECUTIVO
	if ($saiu33idpalabra==''){$sError=$ERR['saiu33idpalabra'].$sSepara.$sError;}
	if ($saiu33idbasecon==''){$sError=$ERR['saiu33idbasecon'].$sSepara.$sError;}
	if ($sError==''){
		if ((int)$saiu33id==0){
			if ($sError==''){
				$sSQL='SELECT saiu33idbasecon FROM saiu33basepalabraclave WHERE saiu33idbasecon='.$saiu33idbasecon.' AND saiu33idpalabra='.$saiu33idpalabra.'';
				$result=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($result)!=0){
					$sError=$ERR['existe'];
					}else{
					if (!seg_revisa_permiso($iCodModulo, 2, $objDB)){$sError=$ERR['2'];}
					}
				}
			if ($sError==''){
				$saiu33id=tabla_consecutivo('saiu33basepalabraclave', 'saiu33id', '', $objDB);
				if ($saiu33id==-1){$sError=$objDB->serror;}
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
			$sCampos3033='saiu33idbasecon, saiu33idpalabra, saiu33id, saiu33activo';
			$sValores3033=''.$saiu33idbasecon.', '.$saiu33idpalabra.', '.$saiu33id.', '.$saiu33activo.'';
			if ($APP->utf8==1){
				$sSQL='INSERT INTO saiu33basepalabraclave ('.$sCampos3033.') VALUES ('.utf8_encode($sValores3033).');';
				}else{
				$sSQL='INSERT INTO saiu33basepalabraclave ('.$sCampos3033.') VALUES ('.$sValores3033.');';
				}
			if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Guardar 3033 '.$sSQL.'<br>';}
			$result=$objDB->ejecutasql($sSQL);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' [3033].<!-- '.$sSQL.' -->';
				}else{
				if ($bAudita[2]){
					seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 2, $saiu33id, $sSQL, $objDB);
					}
				}
			}else{
			$scampo3033[1]='saiu33activo';
			$svr3033[1]=$saiu33activo;
			$inumcampos=1;
			$sWhere='saiu33id='.$saiu33id.'';
			//$sWhere='saiu33idbasecon='.$saiu33idbasecon.' AND saiu33idpalabra='.$saiu33idpalabra.'';
			$sSQL='SELECT * FROM saiu33basepalabraclave WHERE '.$sWhere;
			$sdatos='';
			$bpasa=false;
			$result=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($result)>0){
				$filaorigen=$objDB->sf($result);
				for ($k=1;$k<=$inumcampos;$k++){
					if ($filaorigen[$scampo3033[$k]]!=$svr3033[$k]){
						if ($sdatos!=''){$sdatos=$sdatos.', ';}
						$sdatos=$sdatos.$scampo3033[$k].'="'.$svr3033[$k].'"';
						$bpasa=true;
						}
					}
				}
			if ($bpasa){
				if ($APP->utf8==1){
					$sSQL='UPDATE saiu33basepalabraclave SET '.utf8_encode($sdatos).' WHERE '.$sWhere.';';
					}else{
					$sSQL='UPDATE saiu33basepalabraclave SET '.$sdatos.' WHERE '.$sWhere.';';
					}
				$result=$objDB->ejecutasql($sSQL);
				if ($result==false){
					$sError=$ERR['falla_guardar'].' {Palabras claves}. <!-- '.$sSQL.' -->';
					}else{
					if ($bAudita[3]){
						seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 3, $saiu33id, $sSQL, $objDB);
						}
					}
				}
			}
		}
	return array($sError, $iAccion, $saiu33id, $sDebug);
	}
function f3033_db_Eliminar($aParametros, $objDB, $bDebug=false){
	$iCodModulo=3033;
	$bAudita[4]=false;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3033='lg/lg_3033_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3033)){$mensajes_3033='lg/lg_3033_es.php';}
	require $mensajes_todas;
	require $mensajes_3033;
	$sError='';
	$sDebug='';
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$saiu33idbasecon=numeros_validar($aParametros[1]);
	$saiu33idpalabra=numeros_validar($aParametros[2]);
	$saiu33id=numeros_validar($aParametros[3]);
	if ($sError==''){
		if (!seg_revisa_permiso($iCodModulo, 4, $objDB)){
			$sError=$ERR['4'];
			}
		}
	if ($sError==''){
		$sSQL='SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=3033';
		$tablaor=$objDB->ejecutasql($sSQL);
		while ($filaor=$objDB->sf($tablaor)){
			$sSQL='SELECT '.$filaor['campoid'].' FROM '.$filaor['tabla'].' WHERE '.$filaor['camporev'].'='.$saiu33id.' LIMIT 0, 1';
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
		$sWhere='saiu33id='.$saiu33id.'';
		//$sWhere='saiu33idbasecon='.$saiu33idbasecon.' AND saiu33idpalabra='.$saiu33idpalabra.'';
		$sSQL='DELETE FROM saiu33basepalabraclave WHERE '.$sWhere.';';
		$result=$objDB->ejecutasql($sSQL);
		if ($result==false){
			$sError=$ERR['falla_eliminar'].' {3033 Palabras claves}.<!-- '.$sSQL.' -->';
			}else{
			if ($bAudita[4]){
				seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $saiu33id, $sSQL, $objDB);
				}
			}
		}
	return array($sError, $sDebug);
	}
function f3033_TablaDetalleV2($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3033='lg/lg_3033_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3033)){$mensajes_3033='lg/lg_3033_es.php';}
	require $mensajes_todas;
	require $mensajes_3033;
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
	$bAbierta=true;
	//$sSQL='SELECT Campo FROM saiu31baseconocimiento WHERE saiu31id='.$saiu31id;
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
		return array($sLeyenda.'<input id="paginaf3033" name="paginaf3033" type="hidden" value="'.$pagina.'"/><input id="lppf3033" name="lppf3033" type="hidden" value="'.$lineastabla.'"/>', $sDebug);
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
	$sTitulos='Basecon, Palabra, Id, Activo';
	$sSQL='SELECT TB.saiu33idbasecon, T2.saiu34palabra, TB.saiu33id, TB.saiu33activo, TB.saiu33idpalabra 
FROM saiu33basepalabraclave AS TB, saiu34palabraclave AS T2 
WHERE '.$sSQLadd1.' TB.saiu33idbasecon='.$saiu31id.' AND TB.saiu33idpalabra=T2.saiu34id '.$sSQLadd.'
ORDER BY TB.saiu33idpalabra';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_3033" name="consulta_3033" type="hidden" value="'.$sSQLlista.'"/>
<input id="titulos_3033" name="titulos_3033" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objDB->ejecutasql($sSQL);
	if ($tabladetalle==false){
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 3033: '.$sSQL.'<br>';}
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		//$sLeyenda=$sSQL;
		}else{
		$registros=$objDB->nf($tabladetalle);
		if ($registros==0){
			//return array(utf8_encode($sErrConsulta.'<input id="paginaf3033" name="paginaf3033" type="hidden" value="'.$pagina.'"/><input id="lppf3033" name="lppf3033" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
<td><b>'.$ETI['saiu33idpalabra'].'</b></td>
<td><b>'.$ETI['saiu33activo'].'</b></td>
<td align="right">
'.html_paginador('paginaf3033', $registros, $lineastabla, $pagina, 'paginarf3033()').'
'.html_lpp('lppf3033', $lineastabla, 'paginarf3033()', 500).'
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
		$et_saiu33idpalabra=$sPrefijo.cadena_notildes($filadet['saiu34palabra']).$sSufijo;
		$et_saiu33activo=$sPrefijo.$filadet['saiu33activo'].$sSufijo;
		if ($bAbierta){
			$et_saiu33idpalabra=html_combo('saiu33idpalabra_'.$filadet['saiu33id'], 'saiu34id', 'saiu34palabra', 'saiu34palabraclave', '', 'saiu34palabra', $filadet['saiu33idpalabra'], $objDB, 'cuadricula3033(2, '.$filadet['saiu33id'].', this.value)', true, '{'.$ETI['msg_ninguna'].'}', '0');
			$et_saiu33activo='<input id="saiu33activo_'.$filadet['saiu33id'].'" name="saiu33activo_'.$filadet['saiu33id'].'" type="text" value="'.$filadet['saiu33activo'].'" onchange="cuadricula3033(4, '.$filadet['saiu33id'].', this.value)"/>';
			$sLink='<input id="btMenos3033_saiu33id" name="btMenos3033_saiu33id" type="button" value="Retirar" class="btMiniMenos" onclick="menosuno3033('.$filadet['saiu33id'].');" title="Retirar fila"/>';
			}
		$res=$res.'<tr'.$sClass.'>
<td>'.$et_saiu33idpalabra.'</td>
<td>'.$et_saiu33activo.'</td>
<td>'.$sLink.'</td>
</tr>';
		}
	$res=$res.'</table>';
	$objDB->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
	}
// -- 3033 Palabras claves XAJAX 
function f3033_Guardar($valores, $aParametros){
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
		list($sError, $iAccion, $saiu33id, $sDebugGuardar)=f3033_db_Guardar($valores, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugGuardar;
		}
	$objResponse=new xajaxResponse();
	if ($sError==''){
		list($sdetalle, $sDebugTabla)=f3033_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugTabla;
		$objResponse->assign('div_f3033detalle', 'innerHTML', $sdetalle);
		//if ($iAccion==2){
			//$objResponse->call('cargaridf3033('.$saiu33id.')');
			//}else{
			$objResponse->call('limpiaf3033');
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
function f3033_Traer($aParametros){
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
		$saiu33idbasecon=numeros_validar($aParametros[1]);
		$saiu33idpalabra=numeros_validar($aParametros[2]);
		if (($saiu33idbasecon!='')&&($saiu33idpalabra!='')){$besta=true;}
		}else{
		$saiu33id=$aParametros[103];
		if ((int)$saiu33id!=0){$besta=true;}
		}
	if ($besta){
		$besta=false;
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		$bHayDb=true;
		$sSQLcondi='';
		if ($paso==1){
			$sSQLcondi=$sSQLcondi.'saiu33idbasecon='.$saiu33idbasecon.' AND saiu33idpalabra='.$saiu33idpalabra.'';
			}else{
			$sSQLcondi=$sSQLcondi.'saiu33id='.$saiu33id.'';
			}
		$sSQL='SELECT * FROM saiu33basepalabraclave WHERE '.$sSQLcondi;
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
		list($saiu33idpalabra_nombre, $serror_det)=tabla_campoxid('saiu34palabraclave','saiu34palabra','saiu34id', $fila['saiu33idpalabra'],'{'.$ETI['msg_sindato'].'}', $objDB);
		$html_saiu33idpalabra=html_oculto('saiu33idpalabra', $fila['saiu33idpalabra'], $saiu33idpalabra_nombre);
		$objResponse->assign('div_saiu33idpalabra', 'innerHTML', $html_saiu33idpalabra);
		$saiu33id_nombre='';
		$html_saiu33id=html_oculto('saiu33id', $fila['saiu33id'], $saiu33id_nombre);
		$objResponse->assign('div_saiu33id', 'innerHTML', $html_saiu33id);
		$objResponse->assign('saiu33activo', 'value', $fila['saiu33activo']);
		$objResponse->call("MensajeAlarmaV2('', 0)");
		$objResponse->call("verboton('belimina3033','block')");
		}else{
		if ($paso==1){
			$objResponse->assign('saiu33idpalabra', 'value', $saiu33idpalabra);
			}else{
			$objResponse->call('MensajeAlarmaV2("No se encontro el registro de referencia:'.$saiu33id.'", 0)');
			}
		}
	if ($bHayDb){
		$objDB->CerrarConexion();
		}
	return $objResponse;
	}
function f3033_Eliminar($aParametros){
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
	list($sError, $sDebugElimina)=f3033_db_Eliminar($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugElimina;
	$objResponse=new xajaxResponse();
	if ($sError==''){
		list($sDetalle, $sDebugTabla)=f3033_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugTabla;
		$objResponse->assign('div_f3033detalle', 'innerHTML', $sDetalle);
		$objResponse->call('limpiaf3033');
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
function f3033_HtmlTabla($aParametros){
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
	list($sDetalle, $sDebugTabla)=f3033_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f3033detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f3033_PintarLlaves($aParametros){
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
	$html_saiu33idpalabra=f3033_HTMLComboV2_saiu33idpalabra($objDB, $objCombos, 0);
	$html_saiu33id='<input id="saiu33id" name="saiu33id" type="hidden" value=""/>';
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_saiu33idpalabra','innerHTML', $html_saiu33idpalabra);
	$objResponse->assign('div_saiu33id','innerHTML', $html_saiu33id);
	return $objResponse;
	}
function f3033_MasUno($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	$sError='';
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$saiu33idbasecon=$aParametros[1];
	$saiu33idpalabra=$aParametros[2];
	$saiu33id=tabla_consecutivo('saiu33basepalabraclave', 'saiu33id', '', $objDB);
	$sCampos='saiu33idbasecon, saiu33idpalabra, saiu33id, saiu33activo';
	$sValores=''.$saiu33idbasecon.', '.$saiu33idpalabra.', '.$saiu33id.', 0';
	$sSQL='INSERT INTO saiu33basepalabraclave ('.$sCampos.') VALUES ('.$sValores.');';
	$result=$objDB->ejecutasql($sSQL);
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->call('paginarf3033');
	$objResponse->call('MensajeAlarmaV2("Se ha agregado una fila al proceso", 1)');
	return $objResponse;
	}
function f3033_MenosUno($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	$sError='';
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$idPadre=$aParametros[1];
	$saiu33id=$aParametros[2];
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$sSQL='DELETE FROM saiu33basepalabraclave WHERE saiu33id='.$saiu33id.';';
	$result=$objDB->ejecutasql($sSQL);
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->call('paginarf3033');
	$objResponse->call("MensajeAlarmaV2('Se ha retirado la fila {Ref: ".$saiu33id."}', 1)");
	return $objResponse;
	}
function f3033_GuardarCuadro($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	$sError='';
	$sDebug='';
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$idcampo=$aParametros[1];
	$saiu33id=$aParametros[2];
	$valor=htmlspecialchars($aParametros[3]);
	$sValor2=htmlspecialchars($aParametros[4]);
	$origen=$aParametros[3];
	//$idPadre=$aParametros[4];
	$sCampo='';
	$iTipoError=0;
	switch($idcampo){
		case 4:
		$sCampo='saiu33activo';
		$valor=numeros_validar($valor);
		if ($valor==''){$valor=0;}
		break;
		}
	$bPaginar=false;
	if ($sError==''){
		switch($sCampo){
			case 'saiu33activo':
			$sSQL='UPDATE saiu33basepalabraclave SET '.$sCampo.'="'.$valor.'" WHERE saiu33id='.$saiu33id.';';
			$result=$objDB->ejecutasql($sSQL);
			break;
			case '':
			$sError='No se ha encontrado el campo '.$idcampo;
			break;
			}
		}
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	//$objResponse->call("MensajeAlarmaV2('".$sError."', ".$iTipoError.")");
	if (($sError=='')){
		if ($bPaginar){
			$objResponse->call('paginarf3033');
			}else{
			//$objResponse->call('MensajeAlarmaV2("Se ha acualizado el valor en la lista {'.$saiu33idbasecon.'}", 1)');
			$objResponse->assign('div_fila'.$saiu33id, 'innerHTML', '<span class="verde">Guardado</span>');
			if ($origen===$valor){
				}else{
				$objResponse->assign($sCampo.'_'.$saiu33id, 'value', $valor);
				}
			}
		}else{
		$objResponse->assign('div_fila'.$saiu33id, 'innerHTML', '<span class="rojo">'.$sError.'</span>');
		}
	return $objResponse;
	}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------
?>