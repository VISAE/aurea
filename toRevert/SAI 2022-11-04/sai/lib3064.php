<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2022 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.27.5 viernes, 7 de enero de 2022
--- 3064 Notificados
*/
function f3064_db_Guardar($valores, $objDB, $bDebug=false, $idTercero=0){
	$iCodModulo=3064;
	$bAudita[2]=false;
	$bAudita[3]=false;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3064='lg/lg_3064_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3064)){$mensajes_3064='lg/lg_3064_es.php';}
	require $mensajes_todas;
	require $mensajes_3064;
	$sError='';
	$sDebug='';
	if ($idTercero==0){$idTercero=$_SESSION['unad_id_tercero'];}
	$bInserta=false;
	$iAccion=3;
	if(!is_array($valores)){$valores=json_decode(str_replace('\"','"',$valores),true);}
	$saiu64idmensaje=numeros_validar($valores[1]);
	$saiu64idtercero=numeros_validar($valores[2]);
	$saiu64id=numeros_validar($valores[3], true);
	$saiu64fechaaplicado=$valores[4];
	$sSepara=', ';
	//if ($saiu64id==''){$sError=$ERR['saiu64id'].$sSepara.$sError;}//CONSECUTIVO
	if ($saiu64idtercero==0){$sError=$ERR['saiu64idtercero'].$sSepara.$sError;}
	if ($saiu64idmensaje==''){$sError=$ERR['saiu64idmensaje'].$sSepara.$sError;}
	if ($sError==''){
		list($sError, $sInfo)=tercero_Bloqueado($saiu64idtercero, $objDB);
		if ($sInfo!=''){$sError=$sError.'<br>'.$sInfo;}
		}
	if ($sError==''){
		if ((int)$saiu64id==0){
			if ($sError==''){
				$sSQL='SELECT saiu64idmensaje FROM saiu64mensajetercero WHERE saiu64idmensaje='.$saiu64idmensaje.' AND saiu64idtercero="'.$saiu64idtercero.'"';
				$result=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($result)!=0){
					$sError=$ERR['existe'];
					}else{
					list($bDevuelve, $sDebugP)=seg_revisa_permisoV3($iCodModulo, 2, $idTercero, $objDB);
					if (!$bDevuelve){$sError=$ERR['2'];}
					}
				}
			if ($sError==''){
				$saiu64id=tabla_consecutivo('saiu64mensajetercero', 'saiu64id', '', $objDB);
				if ($saiu64id==-1){$sError=$objDB->serror;}
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
			$sCampos3064='saiu64idmensaje, saiu64idtercero, saiu64id, saiu64fechaaplicado';
			$sValores3064=''.$saiu64idmensaje.', "'.$saiu64idtercero.'", '.$saiu64id.', "'.$saiu64fechaaplicado.'"';
			if ($APP->utf8==1){
				$sSQL='INSERT INTO saiu64mensajetercero ('.$sCampos3064.') VALUES ('.utf8_encode($sValores3064).');';
				}else{
				$sSQL='INSERT INTO saiu64mensajetercero ('.$sCampos3064.') VALUES ('.$sValores3064.');';
				}
			if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Guardar 3064 '.$sSQL.'<br>';}
			$result=$objDB->ejecutasql($sSQL);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' [3064].<!-- '.$sSQL.' -->';
				}else{
				if ($bAudita[2]){
					seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 2, $saiu64id, $sSQL, $objDB);
					}
				}
			}else{
			$scampo3064[1]='saiu64fechaaplicado';
			$svr3064[1]=$saiu64fechaaplicado;
			$inumcampos=1;
			$sWhere='saiu64id='.$saiu64id.'';
			//$sWhere='saiu64idmensaje='.$saiu64idmensaje.' AND saiu64idtercero="'.$saiu64idtercero.'"';
			$sSQL='SELECT * FROM saiu64mensajetercero WHERE '.$sWhere;
			$sdatos='';
			$bPasa=false;
			$result=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($result)>0){
				$filaorigen=$objDB->sf($result);
				for ($k=1;$k<=$inumcampos;$k++){
					if ($filaorigen[$scampo3064[$k]]!=$svr3064[$k]){
						if ($sdatos!=''){$sdatos=$sdatos.', ';}
						$sdatos=$sdatos.$scampo3064[$k].'="'.$svr3064[$k].'"';
						$bPasa=true;
						}
					}
				}
			if ($bPasa){
				if ($APP->utf8==1){
					$sSQL='UPDATE saiu64mensajetercero SET '.utf8_encode($sdatos).' WHERE '.$sWhere.';';
					}else{
					$sSQL='UPDATE saiu64mensajetercero SET '.$sdatos.' WHERE '.$sWhere.';';
					}
				if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Guardar 3064 '.$sSQL.'<br>';}
				$result=$objDB->ejecutasql($sSQL);
				if ($result==false){
					$sError=$ERR['falla_guardar'].' {Notificados}. <!-- '.$sSQL.' -->';
					}else{
					if ($bAudita[3]){
						seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 3, $saiu64id, $sSQL, $objDB);
						}
					}
				}
			}
		}
	return array($sError, $iAccion, $saiu64id, $sDebug);
	}
function f3064_db_Eliminar($aParametros, $objDB, $bDebug=false, $idTercero=0){
	$iCodModulo=3064;
	$bAudita[4]=false;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3064='lg/lg_3064_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3064)){$mensajes_3064='lg/lg_3064_es.php';}
	require $mensajes_todas;
	require $mensajes_3064;
	$sError='';
	$sDebug='';
	if ($idTercero==0){$idTercero=$_SESSION['unad_id_tercero'];}
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$saiu64idmensaje=numeros_validar($aParametros[1]);
	$saiu64idtercero=numeros_validar($aParametros[2]);
	$saiu64id=numeros_validar($aParametros[3]);
	if ($sError==''){
		list($bDevuelve, $sDebugP)=seg_revisa_permisoV3($iCodModulo, 4, $idTercero, $objDB);
		if (!$bDevuelve){
			$sError=$ERR['4'];
			}
		}
	if ($sError==''){
		$sSQL='SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=3064';
		$tablaor=$objDB->ejecutasql($sSQL);
		while ($filaor=$objDB->sf($tablaor)){
			$sSQL='SELECT '.$filaor['campoid'].' FROM '.$filaor['tabla'].' WHERE '.$filaor['camporev'].'='.$saiu64id.' LIMIT 0, 1';
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
		$sWhere='saiu64id='.$saiu64id.'';
		//$sWhere='saiu64idmensaje='.$saiu64idmensaje.' AND saiu64idtercero="'.$saiu64idtercero.'"';
		$sSQL='DELETE FROM saiu64mensajetercero WHERE '.$sWhere.';';
		$result=$objDB->ejecutasql($sSQL);
		if ($result==false){
			$sError=$ERR['falla_eliminar'].' {3064 Notificados}.<!-- '.$sSQL.' -->';
			}else{
			if ($bAudita[4]){
				seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $saiu64id, $sSQL, $objDB);
				}
			}
		}
	return array($sError, $sDebug);
	}
function f3064_TablaDetalleV2($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3064='lg/lg_3064_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3064)){$mensajes_3064='lg/lg_3064_es.php';}
	require $mensajes_todas;
	require $mensajes_3064;
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
	$sBotones='<input id="paginaf3064" name="paginaf3064" type="hidden" value="'.$pagina.'"/>
	<input id="lppf3064" name="lppf3064" type="hidden" value="'.$lineastabla.'"/>';
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
	$sTitulos='Mensaje, Tercero, Id, Fechaaplicado';
	$sSQL='SELECT TB.saiu64idmensaje, T2.unad11razonsocial AS C2_nombre, TB.saiu64id, TB.saiu64fechaaplicado, TB.saiu64idtercero, T2.unad11tipodoc AS C2_td, T2.unad11doc AS C2_doc 
	FROM saiu64mensajetercero AS TB, unad11terceros AS T2 
	WHERE '.$sSQLadd1.' TB.saiu64idmensaje='.$saiu63id.' AND TB.saiu64idtercero=T2.unad11id '.$sSQLadd.'
	ORDER BY TB.saiu64idtercero';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_3064" name="consulta_3064" type="hidden" value="'.$sSQLlista.'"/>
	<input id="titulos_3064" name="titulos_3064" type="hidden" value="'.$sTitulos.'"/>';
	if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 3064: '.$sSQL.'<br>';}
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
	<td colspan="2"><b>'.$ETI['saiu64idtercero'].'</b></td>
	<td><b>'.$ETI['saiu64fechaaplicado'].'</b></td>
	<td align="right">
	'.html_paginador('paginaf3064', $registros, $lineastabla, $pagina, 'paginarf3064()').'
	'.html_lpp('lppf3064', $lineastabla, 'paginarf3064()').'
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
		$et_saiu64idtercero_doc='';
		$et_saiu64idtercero_nombre='';
		if ($filadet['saiu64idtercero']!=0){
			$et_saiu64idtercero_doc=$sPrefijo.$filadet['C2_td'].' '.$filadet['C2_doc'].$sSufijo;
			$et_saiu64idtercero_nombre=$sPrefijo.cadena_notildes($filadet['C2_nombre']).$sSufijo;
			}
		$et_saiu64fechaaplicado='';
		if ($filadet['saiu64fechaaplicado']!='00/00/0000'){$et_saiu64fechaaplicado=$sPrefijo.$filadet['saiu64fechaaplicado'].$sSufijo;}
		if ($bAbierta){
			$sLink='<a href="javascript:cargaridf3064('.$filadet['saiu64id'].')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$res=$res.'<tr'.$sClass.'>
		<td>'.$et_saiu64idtercero_doc.'</td>
		<td>'.$et_saiu64idtercero_nombre.'</td>
		<td>'.$et_saiu64fechaaplicado.'</td>
		<td>'.$sLink.'</td>
		</tr>';
		}
	$res=$res.'</table>
	<div class="salto5px"></div>
	</div>';
	$objDB->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
	}
// -- 3064 Notificados XAJAX 
function f3064_Guardar($valores, $aParametros){
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
		list($sError, $iAccion, $saiu64id, $sDebugGuardar)=f3064_db_Guardar($valores, $objDB, $bDebug, $idTercero);
		$sDebug=$sDebug.$sDebugGuardar;
		}
	$objResponse=new xajaxResponse();
	if ($sError==''){
		list($sdetalle, $sDebugTabla)=f3064_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugTabla;
		$objResponse->assign('div_f3064detalle', 'innerHTML', $sdetalle);
		//if ($iAccion==2){
			//$objResponse->call('cargaridf3064('.$saiu64id.')');
			//}else{
			$objResponse->call('limpiaf3064');
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
function f3064_Traer($aParametros){
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
		$saiu64idmensaje=numeros_validar($aParametros[1]);
		$saiu64idtercero=numeros_validar($aParametros[2]);
		if (($saiu64idmensaje!='')&&($saiu64idtercero!='')){$besta=true;}
		}else{
		$saiu64id=$aParametros[103];
		if ((int)$saiu64id!=0){$besta=true;}
		}
	if ($besta){
		$besta=false;
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		$bHayDb=true;
		$sSQLcondi='';
		if ($paso==1){
			$sSQLcondi=$sSQLcondi.'saiu64idmensaje='.$saiu64idmensaje.' AND saiu64idtercero='.$saiu64idtercero.'';
			}else{
			$sSQLcondi=$sSQLcondi.'saiu64id='.$saiu64id.'';
			}
		$sSQL='SELECT * FROM saiu64mensajetercero WHERE '.$sSQLcondi;
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$fila=$objDB->sf($tabla);
			$besta=true;
			}
		}
	$objResponse=new xajaxResponse();
	if ($besta){
		$iPiel=iDefinirPiel($APP, 1);
		$saiu64idtercero_id=(int)$fila['saiu64idtercero'];
		$saiu64idtercero_td=$APP->tipo_doc;
		$saiu64idtercero_doc='';
		$saiu64idtercero_nombre='';
		if ($saiu64idtercero_id!=0){
			list($saiu64idtercero_nombre, $saiu64idtercero_id, $saiu64idtercero_td, $saiu64idtercero_doc)=html_tercero($saiu64idtercero_td, $saiu64idtercero_doc, $saiu64idtercero_id, 0, $objDB);
			}
		$html_saiu64idtercero_llaves=html_DivTerceroV2('saiu64idtercero', $saiu64idtercero_td, $saiu64idtercero_doc, true, 2, 'Ingrese el documento');
		$objResponse->assign('saiu64idtercero', 'value', $saiu64idtercero_id);
		$objResponse->assign('div_saiu64idtercero_llaves', 'innerHTML', $html_saiu64idtercero_llaves);
		$objResponse->assign('div_saiu64idtercero', 'innerHTML', $saiu64idtercero_nombre);
		$saiu64id_nombre='';
		$html_saiu64id=html_oculto('saiu64id', $fila['saiu64id'], $saiu64id_nombre);
		$objResponse->assign('div_saiu64id', 'innerHTML', $html_saiu64id);
		$html_saiu64fechaaplicado=html_oculto('saiu64fechaaplicado', $fila['saiu64fechaaplicado'], fecha_desdenumero($fila['saiu64fechaaplicado']));
		$objResponse->assign('div_saiu64fechaaplicado', 'innerHTML', $html_saiu64fechaaplicado);
		$objResponse->call("MensajeAlarmaV2('', 0)");
		$objResponse->call("verboton('belimina3064','block')");
		}else{
		if ($paso==1){
			}else{
			$objResponse->call('MensajeAlarmaV2("No se encontro el registro de referencia:'.$saiu64id.'", 0)');
			}
		}
	if ($bHayDb){
		$objDB->CerrarConexion();
		}
	return $objResponse;
	}
function f3064_Eliminar($aParametros){
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
	list($sError, $sDebugElimina)=f3064_db_Eliminar($aParametros, $objDB, $bDebug, $idTercero);
	$sDebug=$sDebug.$sDebugElimina;
	$objResponse=new xajaxResponse();
	if ($sError==''){
		list($sDetalle, $sDebugTabla)=f3064_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugTabla;
		$objResponse->assign('div_f3064detalle', 'innerHTML', $sDetalle);
		$objResponse->call('limpiaf3064');
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
function f3064_HtmlTabla($aParametros){
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
	list($sDetalle, $sDebugTabla)=f3064_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f3064detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f3064_PintarLlaves($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	//if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$iPiel=iDefinirPiel($APP, 1);
	$saiu64idtercero=0;
	$saiu64idtercero_rs='';
	$html_saiu64idtercero_llaves=html_DivTerceroV2('saiu64idtercero', $APP->tipo_doc, '', false, 2, $ETI['ing_doc']);
	$html_saiu64id='<input id="saiu64id" name="saiu64id" type="hidden" value=""/>';
	$et_saiu64fechaaplicado='00/00/0000';
	$html_saiu64fechaaplicado=html_oculto('saiu64fechaaplicado', 0, $et_saiu64fechaaplicado);
	$objResponse=new xajaxResponse();
	$objResponse->assign('saiu64idtercero','value', $saiu64idtercero);
	$objResponse->assign('div_saiu64idtercero_llaves','innerHTML', $html_saiu64idtercero_llaves);
	$objResponse->assign('div_saiu64idtercero','innerHTML', $saiu64idtercero_rs);
	$objResponse->assign('div_saiu64id','innerHTML', $html_saiu64id);
	$objResponse->assign('div_saiu64fechaaplicado','innerHTML', $html_saiu64fechaaplicado);
	return $objResponse;
	}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------
?>