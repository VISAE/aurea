<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2022 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.27.5 viernes, 7 de enero de 2022
--- 3065 Grupos a notificar
*/
function f3065_HTMLComboV2_saiu65idgrupo($objDB, $objCombos, $valor){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('saiu65idgrupo', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	//$objCombos->iAncho=450;
	$objCombos->sAccion='revisaf3065()';
	$sSQL='SELECT bita27id AS id, bita27nombre AS nombre FROM bita27equipotrabajo WHERE bita27id>0 ORDER BY bita27nombre';
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f3065_db_Guardar($valores, $objDB, $bDebug=false, $idTercero=0){
	$iCodModulo=3065;
	$bAudita[2]=false;
	$bAudita[3]=false;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3065='lg/lg_3065_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3065)){$mensajes_3065='lg/lg_3065_es.php';}
	require $mensajes_todas;
	require $mensajes_3065;
	$sError='';
	$sDebug='';
	if ($idTercero==0){$idTercero=$_SESSION['unad_id_tercero'];}
	$bInserta=false;
	$iAccion=3;
	if(!is_array($valores)){$valores=json_decode(str_replace('\"','"',$valores),true);}
	$saiu65idmensaje=numeros_validar($valores[1]);
	$saiu65idgrupo=numeros_validar($valores[2]);
	$saiu65id=numeros_validar($valores[3], true);
	$saiu65fechaaplicado=$valores[4];
	$sSepara=', ';
	//if ($saiu65id==''){$sError=$ERR['saiu65id'].$sSepara.$sError;}//CONSECUTIVO
	if ($saiu65idgrupo==''){$sError=$ERR['saiu65idgrupo'].$sSepara.$sError;}
	if ($saiu65idmensaje==''){$sError=$ERR['saiu65idmensaje'].$sSepara.$sError;}
	if ($sError==''){
		if ((int)$saiu65id==0){
			if ($sError==''){
				$sSQL='SELECT saiu65idmensaje FROM saiu65mensajegrupo WHERE saiu65idmensaje='.$saiu65idmensaje.' AND saiu65idgrupo='.$saiu65idgrupo.'';
				$result=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($result)!=0){
					$sError=$ERR['existe'];
					}else{
					list($bDevuelve, $sDebugP)=seg_revisa_permisoV3($iCodModulo, 2, $idTercero, $objDB);
					if (!$bDevuelve){$sError=$ERR['2'];}
					}
				}
			if ($sError==''){
				$saiu65id=tabla_consecutivo('saiu65mensajegrupo', 'saiu65id', '', $objDB);
				if ($saiu65id==-1){$sError=$objDB->serror;}
				$bInserta=true;
				$iAccion=2;
				}
			}else{
			list($bDevuelve, $sDebugP)=seg_revisa_permisoV3($iCodModulo, 3, $idTercero, $objDB);
			if (!$bDevuelve){$sError=$ERR['3'];}
			}
		}
	if ($sError==''){
		if ($bInserta){
			}
		}
	if ($sError==''){
		if ($bInserta){
			$sCampos3065='saiu65idmensaje, saiu65idgrupo, saiu65id, saiu65fechaaplicado';
			$sValores3065=''.$saiu65idmensaje.', '.$saiu65idgrupo.', '.$saiu65id.', "'.$saiu65fechaaplicado.'"';
			if ($APP->utf8==1){
				$sSQL='INSERT INTO saiu65mensajegrupo ('.$sCampos3065.') VALUES ('.utf8_encode($sValores3065).');';
				}else{
				$sSQL='INSERT INTO saiu65mensajegrupo ('.$sCampos3065.') VALUES ('.$sValores3065.');';
				}
			if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Guardar 3065 '.$sSQL.'<br>';}
			$result=$objDB->ejecutasql($sSQL);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' [3065].<!-- '.$sSQL.' -->';
				}else{
				if ($bAudita[2]){
					seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 2, $saiu65id, $sSQL, $objDB);
					}
				}
			}else{
			$scampo3065[1]='saiu65fechaaplicado';
			$svr3065[1]=$saiu65fechaaplicado;
			$inumcampos=1;
			$sWhere='saiu65id='.$saiu65id.'';
			//$sWhere='saiu65idmensaje='.$saiu65idmensaje.' AND saiu65idgrupo='.$saiu65idgrupo.'';
			$sSQL='SELECT * FROM saiu65mensajegrupo WHERE '.$sWhere;
			$sdatos='';
			$bPasa=false;
			$result=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($result)>0){
				$filaorigen=$objDB->sf($result);
				for ($k=1;$k<=$inumcampos;$k++){
					if ($filaorigen[$scampo3065[$k]]!=$svr3065[$k]){
						if ($sdatos!=''){$sdatos=$sdatos.', ';}
						$sdatos=$sdatos.$scampo3065[$k].'="'.$svr3065[$k].'"';
						$bPasa=true;
						}
					}
				}
			if ($bPasa){
				if ($APP->utf8==1){
					$sSQL='UPDATE saiu65mensajegrupo SET '.utf8_encode($sdatos).' WHERE '.$sWhere.';';
					}else{
					$sSQL='UPDATE saiu65mensajegrupo SET '.$sdatos.' WHERE '.$sWhere.';';
					}
				if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Guardar 3065 '.$sSQL.'<br>';}
				$result=$objDB->ejecutasql($sSQL);
				if ($result==false){
					$sError=$ERR['falla_guardar'].' {Grupos a notificar}. <!-- '.$sSQL.' -->';
					}else{
					if ($bAudita[3]){
						seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 3, $saiu65id, $sSQL, $objDB);
						}
					}
				}
			}
		}
	return array($sError, $iAccion, $saiu65id, $sDebug);
	}
function f3065_db_Eliminar($aParametros, $objDB, $bDebug=false, $idTercero=0){
	$iCodModulo=3065;
	$bAudita[4]=false;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3065='lg/lg_3065_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3065)){$mensajes_3065='lg/lg_3065_es.php';}
	require $mensajes_todas;
	require $mensajes_3065;
	$sError='';
	$sDebug='';
	if ($idTercero==0){$idTercero=$_SESSION['unad_id_tercero'];}
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$saiu65idmensaje=numeros_validar($aParametros[1]);
	$saiu65idgrupo=numeros_validar($aParametros[2]);
	$saiu65id=numeros_validar($aParametros[3]);
	if ($sError==''){
		list($bDevuelve, $sDebugP)=seg_revisa_permisoV3($iCodModulo, 4, $idTercero, $objDB);
		if (!$bDevuelve){
			$sError=$ERR['4'];
			}
		}
	if ($sError==''){
		$sSQL='SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=3065';
		$tablaor=$objDB->ejecutasql($sSQL);
		while ($filaor=$objDB->sf($tablaor)){
			$sSQL='SELECT '.$filaor['campoid'].' FROM '.$filaor['tabla'].' WHERE '.$filaor['camporev'].'='.$saiu65id.' LIMIT 0, 1';
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
		$sWhere='saiu65id='.$saiu65id.'';
		//$sWhere='saiu65idmensaje='.$saiu65idmensaje.' AND saiu65idgrupo='.$saiu65idgrupo.'';
		$sSQL='DELETE FROM saiu65mensajegrupo WHERE '.$sWhere.';';
		$result=$objDB->ejecutasql($sSQL);
		if ($result==false){
			$sError=$ERR['falla_eliminar'].' {3065 Grupos a notificar}.<!-- '.$sSQL.' -->';
			}else{
			if ($bAudita[4]){
				seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $saiu65id, $sSQL, $objDB);
				}
			}
		}
	return array($sError, $sDebug);
	}
function f3065_TablaDetalleV2($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3065='lg/lg_3065_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3065)){$mensajes_3065='lg/lg_3065_es.php';}
	require $mensajes_todas;
	require $mensajes_3065;
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	if (isset($aParametros[0])==0){$aParametros[0]=-1;}
	if (isset($aParametros[100])==0){$aParametros[100]=$_SESSION['unad_id_tercero'];}
	if (isset($aParametros[101])==0){$aParametros[101]=1;}
	if (isset($aParametros[102])==0){$aParametros[102]=20;}
	//if (isset($aParametros[103])==0){$aParametros[103]='';}
	//if (isset($aParametros[104])==0){$aParametros[104]='';}
	$idTercero=$aParametros[100];
	$aParametros[0]=numeros_validar($aParametros[0]);
	if ($aParametros[0]==''){$aParametros[0]=-1;}
	$saiu63id=$aParametros[0];
	$pagina=$aParametros[101];
	$lineastabla=$aParametros[102];
	//$aParametros[103]=trim($aParametros[103]);
	//$aParametros[104]=numeros_validar($aParametros[104]);
	$sDebug='';
	$bAbierta=false;
	$sSQL='SELECT saiu63estado FROM saiu63mensajenotifica WHERE saiu63id='.$saiu63id;
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		if ($fila['saiu63estado']!='S'){$bAbierta=true;}
		}
	$sLeyenda='';
	$sBotones='<input id="paginaf3065" name="paginaf3065" type="hidden" value="'.$pagina.'"/>
	<input id="lppf3065" name="lppf3065" type="hidden" value="'.$lineastabla.'"/>';
	if ($sLeyenda!=''){
		$sLeyenda='<div class="salto1px"></div>
		<div class="GrupoCamposAyuda">
		'.$sLeyenda.'
		<div class="salto1px"></div>
		</div>';
		return array($sLeyenda.$sBotones, $sDebug);
		die();
		}
	$iPiel=iDefinirPiel($APP, 1);
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
	if ($bNombre!=''){
		$sBase=strtoupper($bNombre);
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
	$sTitulos='Mensaje, Grupo, Id, Fechaaplicado';
	$sSQL='SELECT TB.saiu65idmensaje, T2.bita27nombre, TB.saiu65id, TB.saiu65fechaaplicado, TB.saiu65idgrupo 
	FROM saiu65mensajegrupo AS TB, bita27equipotrabajo AS T2 
	WHERE '.$sSQLadd1.' TB.saiu65idmensaje='.$saiu63id.' AND TB.saiu65idgrupo=T2.bita27id '.$sSQLadd.'
	ORDER BY TB.saiu65idgrupo';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_3065" name="consulta_3065" type="hidden" value="'.$sSQLlista.'"/>
	<input id="titulos_3065" name="titulos_3065" type="hidden" value="'.$sTitulos.'"/>';
	if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 3065: '.$sSQL.'<br>';}
	$tabladetalle=$objDB->ejecutasql($sSQL);
	if ($tabladetalle==false){
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		//$sLeyenda=$sSQL;
		}else{
		$registros=$objDB->nf($tabladetalle);
		if ($registros==0){
			return array($sErrConsulta.$sBotones, $sDebug);
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
	<td><b>'.$ETI['saiu65idgrupo'].'</b></td>
	<td><b>'.$ETI['saiu65fechaaplicado'].'</b></td>
	<td align="right">
	'.html_paginador('paginaf3065', $registros, $lineastabla, $pagina, 'paginarf3065()').'
	'.html_lpp('lppf3065', $lineastabla, 'paginarf3065()').'
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
		$et_saiu65idgrupo=$sPrefijo.cadena_notildes($filadet['bita27nombre']).$sSufijo;
		$et_saiu65fechaaplicado='';
		if ($filadet['saiu65fechaaplicado']!='00/00/0000'){$et_saiu65fechaaplicado=$sPrefijo.$filadet['saiu65fechaaplicado'].$sSufijo;}
		if ($bAbierta){
			$sLink='<a href="javascript:cargaridf3065('.$filadet['saiu65id'].')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$res=$res.'<tr'.$sClass.'>
		<td>'.$et_saiu65idgrupo.'</td>
		<td>'.$et_saiu65fechaaplicado.'</td>
		<td>'.$sLink.'</td>
		</tr>';
		}
	$res=$res.'</table>
	<div class="salto5px"></div>
	</div>';
	$objDB->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
	}
// -- 3065 Grupos a notificar XAJAX 
function f3065_Guardar($valores, $aParametros){
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
	if (isset($opts[100])==0){$opts[100]=0;}
	//if(!is_array($valores)){$datos=json_decode(str_replace('\"','"',$valores),true);}
	//if (isset($datos[0])==0){$datos[0]='';}
	//if ($datos[0]==''){$sError=$ERR[''];}
	$idTercero=$opts[100];
	if ($sError==''){
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		$bHayDb=true;
		list($sError, $iAccion, $saiu65id, $sDebugGuardar)=f3065_db_Guardar($valores, $objDB, $bDebug, $idTercero);
		$sDebug=$sDebug.$sDebugGuardar;
		}
	$objResponse=new xajaxResponse();
	if ($sError==''){
		list($sdetalle, $sDebugTabla)=f3065_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugTabla;
		$objResponse->assign('div_f3065detalle', 'innerHTML', $sdetalle);
		//if ($iAccion==2){
			//$objResponse->call('cargaridf3065('.$saiu65id.')');
			//}else{
			$objResponse->call('limpiaf3065');
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
function f3065_Traer($aParametros){
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
		$saiu65idmensaje=numeros_validar($aParametros[1]);
		$saiu65idgrupo=numeros_validar($aParametros[2]);
		if (($saiu65idmensaje!='')&&($saiu65idgrupo!='')){$besta=true;}
		}else{
		$saiu65id=$aParametros[103];
		if ((int)$saiu65id!=0){$besta=true;}
		}
	if ($besta){
		$besta=false;
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		$bHayDb=true;
		$sSQLcondi='';
		if ($paso==1){
			$sSQLcondi=$sSQLcondi.'saiu65idmensaje='.$saiu65idmensaje.' AND saiu65idgrupo='.$saiu65idgrupo.'';
			}else{
			$sSQLcondi=$sSQLcondi.'saiu65id='.$saiu65id.'';
			}
		$sSQL='SELECT * FROM saiu65mensajegrupo WHERE '.$sSQLcondi;
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$fila=$objDB->sf($tabla);
			$besta=true;
			}
		}
	$objResponse=new xajaxResponse();
	if ($besta){
		$iPiel=iDefinirPiel($APP, 1);
		list($saiu65idgrupo_nombre, $serror_det)=tabla_campoxid('bita27equipotrabajo','bita27nombre','bita27id', $fila['saiu65idgrupo'],'{'.$ETI['msg_sindato'].'}', $objDB);
		$html_saiu65idgrupo=html_oculto('saiu65idgrupo', $fila['saiu65idgrupo'], $saiu65idgrupo_nombre);
		$objResponse->assign('div_saiu65idgrupo', 'innerHTML', $html_saiu65idgrupo);
		$saiu65id_nombre='';
		$html_saiu65id=html_oculto('saiu65id', $fila['saiu65id'], $saiu65id_nombre);
		$objResponse->assign('div_saiu65id', 'innerHTML', $html_saiu65id);
		$html_saiu65fechaaplicado=html_oculto('saiu65fechaaplicado', $fila['saiu65fechaaplicado'], fecha_desdenumero($fila['saiu65fechaaplicado']));
		$objResponse->assign('div_saiu65fechaaplicado', 'innerHTML', $html_saiu65fechaaplicado);
		$objResponse->call("MensajeAlarmaV2('', 0)");
		$objResponse->call("verboton('belimina3065','block')");
		}else{
		if ($paso==1){
			$objResponse->assign('saiu65idgrupo', 'value', $saiu65idgrupo);
			}else{
			$objResponse->call('MensajeAlarmaV2("No se encontro el registro de referencia:'.$saiu65id.'", 0)');
			}
		}
	if ($bHayDb){
		$objDB->CerrarConexion();
		}
	return $objResponse;
	}
function f3065_Eliminar($aParametros){
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
	if (isset($opts[100])==0){$opts[100]=0;}
	$idTercero=$opts[100];
	//if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	list($sError, $sDebugElimina)=f3065_db_Eliminar($aParametros, $objDB, $bDebug, $idTercero);
	$sDebug=$sDebug.$sDebugElimina;
	$objResponse=new xajaxResponse();
	if ($sError==''){
		list($sDetalle, $sDebugTabla)=f3065_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugTabla;
		$objResponse->assign('div_f3065detalle', 'innerHTML', $sDetalle);
		$objResponse->call('limpiaf3065');
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
function f3065_HtmlTabla($aParametros){
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
	list($sDetalle, $sDebugTabla)=f3065_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f3065detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f3065_PintarLlaves($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	//if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$iPiel=iDefinirPiel($APP, 1);
	$objCombos=new clsHtmlCombos();
	$html_saiu65idgrupo=f3065_HTMLComboV2_saiu65idgrupo($objDB, $objCombos, '');
	$html_saiu65id='<input id="saiu65id" name="saiu65id" type="hidden" value=""/>';
	$et_saiu65fechaaplicado='00/00/0000';
	$html_saiu65fechaaplicado=html_oculto('saiu65fechaaplicado', 0, $et_saiu65fechaaplicado);
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_saiu65idgrupo','innerHTML', $html_saiu65idgrupo);
	$objResponse->call('$("#saiu65idgrupo").chosen()');
	$objResponse->assign('div_saiu65id','innerHTML', $html_saiu65id);
	$objResponse->assign('div_saiu65fechaaplicado','innerHTML', $html_saiu65fechaaplicado);
	return $objResponse;
	}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------
?>