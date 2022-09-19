<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2021 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.25.10c lunes, 5 de abril de 2021
--- 3055 Manuales
*/
function f3055_db_Guardar($valores, $objDB, $bDebug=false){
	$iCodModulo=3055;
	$bAudita[2]=false;
	$bAudita[3]=false;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3055='lg/lg_3055_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3055)){$mensajes_3055='lg/lg_3055_es.php';}
	require $mensajes_todas;
	require $mensajes_3055;
	$sError='';
	$sDebug='';
	$bInserta=false;
	$iAccion=3;
	if(!is_array($valores)){$valores=json_decode(str_replace('\"','"',$valores),true);}
	$saiu55idmanual=numeros_validar($valores[1]);
	$saiu55consec=numeros_validar($valores[2]);
	$saiu55id=numeros_validar($valores[3], true);
	$saiu55fecha=$valores[4];
	$saiu55infoversion=htmlspecialchars(trim($valores[5]));
	$saiu55formaenlace=numeros_validar($valores[6]);
	$saiu55ruta=htmlspecialchars(trim($valores[7]));
	//if ($saiu55formaenlace==''){$saiu55formaenlace=0;}
	$sSepara=', ';
	if ($saiu55formaenlace==1){
		if ($saiu55ruta==''){$sError=$ERR['saiu55ruta'].$sSepara.$sError;}
		}
	if ($saiu55formaenlace==''){$sError=$ERR['saiu55formaenlace'].$sSepara.$sError;}
	//if ($saiu55infoversion==''){$sError=$ERR['saiu55infoversion'].$sSepara.$sError;}
	if ($saiu55fecha==0){
		//$saiu55fecha=fecha_DiaMod();
		$sError=$ERR['saiu55fecha'].$sSepara.$sError;
		}
	//if ($saiu55id==''){$sError=$ERR['saiu55id'].$sSepara.$sError;}//CONSECUTIVO
	//if ($saiu55consec==''){$sError=$ERR['saiu55consec'].$sSepara.$sError;}//CONSECUTIVO
	if ($saiu55idmanual==''){$sError=$ERR['saiu55idmanual'].$sSepara.$sError;}
	if ($sError==''){
		if ((int)$saiu55id==0){
			if ((int)$saiu55consec==0){
				$saiu55consec=tabla_consecutivo('saiu55manualversion', 'saiu55consec', 'saiu55idmanual='.$saiu55idmanual.'', $objDB);
				if ($saiu55consec==-1){$sError=$objDB->serror;}
				}else{
				if (!seg_revisa_permiso($iCodModulo, 8, $objDB)){$sError=$ERR['8'];}
				}
			if ($sError==''){
				$sSQL='SELECT saiu55idmanual FROM saiu55manualversion WHERE saiu55idmanual='.$saiu55idmanual.' AND saiu55consec='.$saiu55consec.'';
				$result=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($result)!=0){
					$sError=$ERR['existe'];
					}else{
					if (!seg_revisa_permiso($iCodModulo, 2, $objDB)){$sError=$ERR['2'];}
					}
				}
			if ($sError==''){
				$saiu55id=tabla_consecutivo('saiu55manualversion', 'saiu55id', '', $objDB);
				if ($saiu55id==-1){$sError=$objDB->serror;}
				$bInserta=true;
				$iAccion=2;
				}
			}else{
			if (!seg_revisa_permiso($iCodModulo, 3, $objDB)){$sError=$ERR['3'];}
			}
		}
	if ($sError==''){
		if ($bInserta){
			$saiu55idorigen=0;
			$saiu55idarchivo=0;
			}
		}
	if ($sError==''){
		if ($bInserta){
			$sCampos3055='saiu55idmanual, saiu55consec, saiu55id, saiu55fecha, saiu55infoversion, 
			saiu55formaenlace, saiu55ruta, saiu55idorigen, saiu55idarchivo';
			$sValores3055=''.$saiu55idmanual.', '.$saiu55consec.', '.$saiu55id.', "'.$saiu55fecha.'", "'.$saiu55infoversion.'", 
			'.$saiu55formaenlace.', "'.$saiu55ruta.'", 0, 0';
			if ($APP->utf8==1){
				$sSQL='INSERT INTO saiu55manualversion ('.$sCampos3055.') VALUES ('.utf8_encode($sValores3055).');';
				}else{
				$sSQL='INSERT INTO saiu55manualversion ('.$sCampos3055.') VALUES ('.$sValores3055.');';
				}
			if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Guardar 3055 '.$sSQL.'<br>';}
			$result=$objDB->ejecutasql($sSQL);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' [3055].<!-- '.$sSQL.' -->';
				}else{
				if ($bAudita[2]){
					seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 2, $saiu55id, $sSQL, $objDB);
					}
				}
			}else{
			$scampo3055[1]='saiu55fecha';
			$scampo3055[2]='saiu55infoversion';
			$scampo3055[3]='saiu55formaenlace';
			$scampo3055[4]='saiu55ruta';
			$svr3055[1]=$saiu55fecha;
			$svr3055[2]=$saiu55infoversion;
			$svr3055[3]=$saiu55formaenlace;
			$svr3055[4]=$saiu55ruta;
			$inumcampos=4;
			$sWhere='saiu55id='.$saiu55id.'';
			//$sWhere='saiu55idmanual='.$saiu55idmanual.' AND saiu55consec='.$saiu55consec.'';
			$sSQL='SELECT * FROM saiu55manualversion WHERE '.$sWhere;
			$sdatos='';
			$bpasa=false;
			$result=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($result)>0){
				$filaorigen=$objDB->sf($result);
				for ($k=1;$k<=$inumcampos;$k++){
					if ($filaorigen[$scampo3055[$k]]!=$svr3055[$k]){
						if ($sdatos!=''){$sdatos=$sdatos.', ';}
						$sdatos=$sdatos.$scampo3055[$k].'="'.$svr3055[$k].'"';
						$bpasa=true;
						}
					}
				}
			if ($bpasa){
				if ($APP->utf8==1){
					$sSQL='UPDATE saiu55manualversion SET '.utf8_encode($sdatos).' WHERE '.$sWhere.';';
					}else{
					$sSQL='UPDATE saiu55manualversion SET '.$sdatos.' WHERE '.$sWhere.';';
					}
				$result=$objDB->ejecutasql($sSQL);
				if ($result==false){
					$sError=$ERR['falla_guardar'].' {Manuales}. <!-- '.$sSQL.' -->';
					}else{
					if ($bAudita[3]){
						seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 3, $saiu55id, $sSQL, $objDB);
						}
					}
				}
			}
		}
	return array($sError, $iAccion, $saiu55id, $sDebug);
	}
function f3055_db_Eliminar($aParametros, $objDB, $bDebug=false){
	$iCodModulo=3055;
	$bAudita[4]=false;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3055='lg/lg_3055_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3055)){$mensajes_3055='lg/lg_3055_es.php';}
	require $mensajes_todas;
	require $mensajes_3055;
	$sError='';
	$sDebug='';
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$saiu55idmanual=numeros_validar($aParametros[1]);
	$saiu55consec=numeros_validar($aParametros[2]);
	$saiu55id=numeros_validar($aParametros[3]);
	if ($sError==''){
		if (!seg_revisa_permiso($iCodModulo, 4, $objDB)){
			$sError=$ERR['4'];
			}
		}
	if ($sError==''){
		$sSQL='SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=3055';
		$tablaor=$objDB->ejecutasql($sSQL);
		while ($filaor=$objDB->sf($tablaor)){
			$sSQL='SELECT '.$filaor['campoid'].' FROM '.$filaor['tabla'].' WHERE '.$filaor['camporev'].'='.$saiu55id.' LIMIT 0, 1';
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
		$sWhere='saiu55id='.$saiu55id.'';
		//$sWhere='saiu55idmanual='.$saiu55idmanual.' AND saiu55consec='.$saiu55consec.'';
		$sSQL='DELETE FROM saiu55manualversion WHERE '.$sWhere.';';
		$result=$objDB->ejecutasql($sSQL);
		if ($result==false){
			$sError=$ERR['falla_eliminar'].' {3055 Manuales}.<!-- '.$sSQL.' -->';
			}else{
			if ($bAudita[4]){
				seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $saiu55id, $sSQL, $objDB);
				}
			}
		}
	return array($sError, $sDebug);
	}
function f3055_TablaDetalleV2($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3055='lg/lg_3055_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3055)){$mensajes_3055='lg/lg_3055_es.php';}
	require $mensajes_todas;
	require $mensajes_3055;
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
	$saiu53id=$aParametros[0];
	$pagina=$aParametros[101];
	$lineastabla=$aParametros[102];
	$bAbierta=true;
	//$sSQL='SELECT Campo FROM saiu53manuales WHERE saiu53id='.$saiu53id;
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
		return array($sLeyenda.'<input id="paginaf3055" name="paginaf3055" type="hidden" value="'.$pagina.'"/><input id="lppf3055" name="lppf3055" type="hidden" value="'.$lineastabla.'"/>', $sDebug);
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
	$sTitulos='Manual, Consec, Id, Fecha, Infoversion, Formaenlace, Ruta, Origen, Archivo';
	$sSQL='SELECT TB.saiu55idmanual, TB.saiu55consec, TB.saiu55id, TB.saiu55fecha, TB.saiu55infoversion, TB.saiu55formaenlace, TB.saiu55ruta, TB.saiu55idorigen, TB.saiu55idarchivo 
FROM saiu55manualversion AS TB 
WHERE '.$sSQLadd1.' TB.saiu55idmanual='.$saiu53id.' '.$sSQLadd.'
ORDER BY TB.saiu55consec';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_3055" name="consulta_3055" type="hidden" value="'.$sSQLlista.'"/>
<input id="titulos_3055" name="titulos_3055" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objDB->ejecutasql($sSQL);
	if ($tabladetalle==false){
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 3055: '.$sSQL.'<br>';}
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		//$sLeyenda=$sSQL;
		}else{
		$registros=$objDB->nf($tabladetalle);
		if ($registros==0){
			return array(utf8_encode($sErrConsulta.'<input id="paginaf3055" name="paginaf3055" type="hidden" value="'.$pagina.'"/><input id="lppf3055" name="lppf3055" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
<td><b>'.$ETI['saiu55consec'].'</b></td>
<td><b>'.$ETI['saiu55fecha'].'</b></td>
<td><b>'.$ETI['saiu55formaenlace'].'</b></td>
<td><b>'.$ETI['saiu55idarchivo'].'</b></td>
<td align="right">
'.html_paginador('paginaf3055', $registros, $lineastabla, $pagina, 'paginarf3055()').'
'.html_lpp('lppf3055', $lineastabla, 'paginarf3055()').'
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
		$et_saiu55consec=$sPrefijo.$filadet['saiu55consec'].$sSufijo;
		$et_saiu55fecha='';
		if ($filadet['saiu55fecha']!=0){$et_saiu55fecha=$sPrefijo.fecha_desdenumero($filadet['saiu55fecha']).$sSufijo;}
		$et_saiu55formaenlace=$sPrefijo.$asaiu55formaenlace[$filadet['saiu55formaenlace']].$sSufijo;
		$et_saiu55idarchivo='';
		if ($filadet['saiu55idarchivo']!=0){
			//$et_saiu55idarchivo='<img src="verarchivo.php?cont='.$filadet['saiu55idorigen'].'&id='.$filadet['saiu55idarchivo'].'&maxx=150"/>';
			$et_saiu55idarchivo=html_lnkarchivo((int)$filadet['saiu55idorigen'], (int)$filadet['saiu55idarchivo']);
			}
		if ($bAbierta){
			$sLink='<a href="javascript:cargaridf3055('.$filadet['saiu55id'].')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$res=$res.'<tr'.$sClass.'>
		<td>'.$et_saiu55consec.'</td>
		<td>'.$et_saiu55fecha.'</td>
		<td>'.$et_saiu55formaenlace.'</td>
		<td>'.$et_saiu55idarchivo.'</td>
		<td>'.$sLink.'</td>
		</tr>';
		}
	$res=$res.'</table>
<div class="salto5px"></div>
</div>';
	$objDB->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
	}
// -- 3055 Manuales XAJAX 
function elimina_archivo_saiu55idarchivo($idPadre, $bDebug=false){
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
		archivo_eliminar('saiu55manualversion', 'saiu55id', 'saiu55idorigen', 'saiu55idarchivo', $idPadre, $objDB);
		}
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	if ($bPuedeEliminar){
		$objResponse->call("limpia_saiu55idarchivo");
		}else{
		MensajeAlarmaV2('".$sError."', 0);
		}
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f3055_Guardar($valores, $aParametros){
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
		list($sError, $iAccion, $saiu55id, $sDebugGuardar)=f3055_db_Guardar($valores, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugGuardar;
		}
	$objResponse=new xajaxResponse();
	if ($sError==''){
		list($sdetalle, $sDebugTabla)=f3055_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugTabla;
		$objResponse->assign('div_f3055detalle', 'innerHTML', $sdetalle);
		//if ($iAccion==2){
			//$objResponse->call('cargaridf3055('.$saiu55id.')');
			//}else{
			$objResponse->call('limpiaf3055');
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
function f3055_Traer($aParametros){
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
		$saiu55idmanual=numeros_validar($aParametros[1]);
		$saiu55consec=numeros_validar($aParametros[2]);
		if (($saiu55idmanual!='')&&($saiu55consec!='')){$besta=true;}
		}else{
		$saiu55id=$aParametros[103];
		if ((int)$saiu55id!=0){$besta=true;}
		}
	if ($besta){
		$besta=false;
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		$bHayDb=true;
		$sSQLcondi='';
		if ($paso==1){
			$sSQLcondi=$sSQLcondi.'saiu55idmanual='.$saiu55idmanual.' AND saiu55consec='.$saiu55consec.'';
			}else{
			$sSQLcondi=$sSQLcondi.'saiu55id='.$saiu55id.'';
			}
		$sSQL='SELECT * FROM saiu55manualversion WHERE '.$sSQLcondi;
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
		$saiu55consec_nombre='';
		$html_saiu55consec=html_oculto('saiu55consec', $fila['saiu55consec'], $saiu55consec_nombre);
		$objResponse->assign('div_saiu55consec', 'innerHTML', $html_saiu55consec);
		$saiu55id_nombre='';
		$html_saiu55id=html_oculto('saiu55id', $fila['saiu55id'], $saiu55id_nombre);
		$objResponse->assign('div_saiu55id', 'innerHTML', $html_saiu55id);
		$objResponse->assign('saiu55fecha', 'value', $fila['saiu55fecha']);
		list($iDia, $iMes, $iAgno)=fecha_DividirNumero($fila['saiu55fecha'], true);
		$objResponse->assign('saiu55fecha_dia', 'value', $iDia);
		$objResponse->assign('saiu55fecha_mes', 'value', $iMes);
		$objResponse->assign('saiu55fecha_agno', 'value', $iAgno);
		$objResponse->assign('saiu55infoversion', 'value', $fila['saiu55infoversion']);
		$objResponse->assign('saiu55formaenlace', 'value', $fila['saiu55formaenlace']);
		$objResponse->assign('saiu55ruta', 'value', $fila['saiu55ruta']);
		$objResponse->assign('saiu55idorigen', 'value', $fila['saiu55idorigen']);
		$idorigen=(int)$fila['saiu55idorigen'];
		$objResponse->assign('saiu55idarchivo', 'value', $fila['saiu55idarchivo']);
		$sMuestraAnexar='block';
		$sMuestraEliminar='none';
		$sHTMLArchivo=html_lnkarchivo($idorigen, (int)$fila['saiu55idarchivo']);
		if ((int)$fila['saiu55idarchivo']!=0){
			$sMuestraEliminar='block';
			//Aqui puede poner validaciones al eliminar - Si no se puede eliminar reversar el mostrado.
			}
		$objResponse->assign('div_saiu55idarchivo', 'innerHTML', $sHTMLArchivo);
		$objResponse->call("verboton('banexasaiu55idarchivo', '".$sMuestraAnexar."')");
		$objResponse->call("verboton('beliminasaiu55idarchivo','".$sMuestraEliminar."')");
		$objResponse->call("MensajeAlarmaV2('', 0)");
		$objResponse->call("verboton('belimina3055','block')");
		}else{
		if ($paso==1){
			$objResponse->assign('saiu55consec', 'value', $saiu55consec);
			}else{
			$objResponse->call('MensajeAlarmaV2("No se encontro el registro de referencia:'.$saiu55id.'", 0)');
			}
		}
	if ($bHayDb){
		$objDB->CerrarConexion();
		}
	return $objResponse;
	}
function f3055_Eliminar($aParametros){
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
	list($sError, $sDebugElimina)=f3055_db_Eliminar($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugElimina;
	$objResponse=new xajaxResponse();
	if ($sError==''){
		list($sDetalle, $sDebugTabla)=f3055_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugTabla;
		$objResponse->assign('div_f3055detalle', 'innerHTML', $sDetalle);
		$objResponse->call('limpiaf3055');
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
function f3055_HtmlTabla($aParametros){
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
	list($sDetalle, $sDebugTabla)=f3055_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f3055detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f3055_PintarLlaves($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	//if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	if (isset($APP->piel)==0){$APP->piel=1;}
	$iPiel=$APP->piel;
	$html_saiu55consec='<input id="saiu55consec" name="saiu55consec" type="text" value="" onchange="revisaf3055()" class="cuatro"/>';
	$html_saiu55id='<input id="saiu55id" name="saiu55id" type="hidden" value=""/>';
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_saiu55consec','innerHTML', $html_saiu55consec);
	$objResponse->assign('div_saiu55id','innerHTML', $html_saiu55id);
	return $objResponse;
	}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------
?>