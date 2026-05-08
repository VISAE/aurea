<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2021 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.25.10c miércoles, 7 de abril de 2021
--- 2932 Participantes
*/
function f2932_db_Guardar($valores, $objDB, $bDebug=false){
	$iCodModulo=2932;
	$bAudita[2]=true;
	$bAudita[3]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2932='lg/lg_2932_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2932)){$mensajes_2932='lg/lg_2932_es.php';}
	require $mensajes_todas;
	require $mensajes_2932;
	$sError='';
	$sDebug='';
	$bInserta=false;
	$iAccion=3;
	if(!is_array($valores)){$valores=json_decode(str_replace('\"','"',$valores),true);}
	$plab32idciclo=numeros_validar($valores[1]);
	$plab32idtercero=numeros_validar($valores[2]);
	$plab32id=numeros_validar($valores[3], true);
	$plab32estado=numeros_validar($valores[4]);
	$plab32fechaingreso=$valores[5];
	$plab32fechafin=$valores[6];
	//if ($plab32estado==''){$plab32estado=0;}
	$sSepara=', ';
	$bUbicarFechas=false;
	if ($plab32fechafin==0){
		//$plab32fechafin=fecha_DiaMod();
		//$sError=$ERR['plab32fechafin'].$sSepara.$sError;
		$bUbicarFechas=true;
		}
	if ($plab32fechaingreso==0){
		//$plab32fechaingreso=fecha_DiaMod();
		//$sError=$ERR['plab32fechaingreso'].$sSepara.$sError;
		$bUbicarFechas=true;
		}
	if ($plab32estado==''){$sError=$ERR['plab32estado'].$sSepara.$sError;}
	//if ($plab32id==''){$sError=$ERR['plab32id'].$sSepara.$sError;}//CONSECUTIVO
	if ($plab32idtercero==0){$sError=$ERR['plab32idtercero'].$sSepara.$sError;}
	if ($plab32idciclo==''){$sError=$ERR['plab32idciclo'].$sSepara.$sError;}
	if ($sError==''){
		list($sError, $sInfo)=tercero_Bloqueado($plab32idtercero, $objDB);
		if ($sInfo!=''){$sError=$sError.'<br>'.$sInfo;}
		}
	if ($sError==''){
		if ($bUbicarFechas){
			$sSQL='SELECT plab31fechainicio, plab31fechafinal FROM plab31emonitoresciclo WHERE plab31id='.$plab32idciclo.'';
			$tabla=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla)>0){
				$fila=$objDB->sf($tabla);
				if ($plab32fechaingreso==0){$plab32fechafin=$fila['plab31fechainicio'];}
				if ($plab32fechafin==0){$plab32fechafin=$fila['plab31fechafinal'];}
				}else{
				$sError=$ERR['plab32fechafin'].$sSepara.$sError;
				}
			}
		}
	if ($sError==''){
		if ((int)$plab32id==0){
			if ($sError==''){
				$sSQL='SELECT plab32idciclo FROM plab32emonitor WHERE plab32idciclo='.$plab32idciclo.' AND plab32idtercero="'.$plab32idtercero.'"';
				$result=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($result)!=0){
					$sError=$ERR['existe'];
					}else{
					if (!seg_revisa_permiso($iCodModulo, 2, $objDB)){$sError=$ERR['2'];}
					}
				}
			if ($sError==''){
				$plab32id=tabla_consecutivo('plab32emonitor', 'plab32id', '', $objDB);
				if ($plab32id==-1){$sError=$objDB->serror;}
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
			$sCampos2932='plab32idciclo, plab32idtercero, plab32id, plab32estado, plab32fechaingreso, 
			plab32fechafin';
			$sValores2932=''.$plab32idciclo.', "'.$plab32idtercero.'", '.$plab32id.', '.$plab32estado.', "'.$plab32fechaingreso.'", 
			"'.$plab32fechafin.'"';
			if ($APP->utf8==1){
				$sSQL='INSERT INTO plab32emonitor ('.$sCampos2932.') VALUES ('.cadena_codificar($sValores2932).');';
				}else{
				$sSQL='INSERT INTO plab32emonitor ('.$sCampos2932.') VALUES ('.$sValores2932.');';
				}
			if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Guardar 2932 '.$sSQL.'<br>';}
			$result=$objDB->ejecutasql($sSQL);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' [2932].<!-- '.$sSQL.' -->';
				}else{
				if ($bAudita[2]){
					seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 2, $plab32id, $sSQL, $objDB);
					}
				}
			}else{
			$scampo2932[1]='plab32estado';
			$scampo2932[2]='plab32fechaingreso';
			$scampo2932[3]='plab32fechafin';
			$svr2932[1]=$plab32estado;
			$svr2932[2]=$plab32fechaingreso;
			$svr2932[3]=$plab32fechafin;
			$inumcampos=3;
			$sWhere='plab32id='.$plab32id.'';
			//$sWhere='plab32idciclo='.$plab32idciclo.' AND plab32idtercero="'.$plab32idtercero.'"';
			$sSQL='SELECT * FROM plab32emonitor WHERE '.$sWhere;
			$sdatos='';
			$bpasa=false;
			$result=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($result)>0){
				$filaorigen=$objDB->sf($result);
				for ($k=1;$k<=$inumcampos;$k++){
					if ($filaorigen[$scampo2932[$k]]!=$svr2932[$k]){
						if ($sdatos!=''){$sdatos=$sdatos.', ';}
						$sdatos=$sdatos.$scampo2932[$k].'="'.$svr2932[$k].'"';
						$bpasa=true;
						}
					}
				}
			if ($bpasa){
				if ($APP->utf8==1){
					$sSQL='UPDATE plab32emonitor SET '.cadena_codificar($sdatos).' WHERE '.$sWhere.';';
					}else{
					$sSQL='UPDATE plab32emonitor SET '.$sdatos.' WHERE '.$sWhere.';';
					}
				$result=$objDB->ejecutasql($sSQL);
				if ($result==false){
					$sError=$ERR['falla_guardar'].' {Participantes}. <!-- '.$sSQL.' -->';
					}else{
					if ($bAudita[3]){
						seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 3, $plab32id, $sSQL, $objDB);
						}
					}
				}
			}
		}
	return array($sError, $iAccion, $plab32id, $sDebug);
	}
function f2932_db_Eliminar($aParametros, $objDB, $bDebug=false){
	$iCodModulo=2932;
	$bAudita[4]=false;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2932='lg/lg_2932_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2932)){$mensajes_2932='lg/lg_2932_es.php';}
	require $mensajes_todas;
	require $mensajes_2932;
	$sError='';
	$sDebug='';
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$plab32idciclo=numeros_validar($aParametros[1]);
	$plab32idtercero=numeros_validar($aParametros[2]);
	$plab32id=numeros_validar($aParametros[3]);
	if ($sError==''){
		if (!seg_revisa_permiso($iCodModulo, 4, $objDB)){
			$sError=$ERR['4'];
			}
		}
	if ($sError==''){
		$sSQL='SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=2932';
		$tablaor=$objDB->ejecutasql($sSQL);
		while ($filaor=$objDB->sf($tablaor)){
			$sSQL='SELECT '.$filaor['campoid'].' FROM '.$filaor['tabla'].' WHERE '.$filaor['camporev'].'='.$plab32id.' LIMIT 0, 1';
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
		$sWhere='plab32id='.$plab32id.'';
		//$sWhere='plab32idciclo='.$plab32idciclo.' AND plab32idtercero="'.$plab32idtercero.'"';
		$sSQL='DELETE FROM plab32emonitor WHERE '.$sWhere.';';
		$result=$objDB->ejecutasql($sSQL);
		if ($result==false){
			$sError=$ERR['falla_eliminar'].' {2932 Participantes}.<!-- '.$sSQL.' -->';
			}else{
			if ($bAudita[4]){
				seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $plab32id, $sSQL, $objDB);
				}
			}
		}
	return array($sError, $sDebug);
	}
function f2932_TablaDetalleV2($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2932='lg/lg_2932_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2932)){$mensajes_2932='lg/lg_2932_es.php';}
	require $mensajes_todas;
	require $mensajes_2932;
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
	$plab31id=$aParametros[0];
	$pagina=$aParametros[101];
	$lineastabla=$aParametros[102];
	$bAbierta=true;
	//$sSQL='SELECT Campo FROM plab31emonitoresciclo WHERE plab31id='.$plab31id;
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
		return array($sLeyenda.'<input id="paginaf2932" name="paginaf2932" type="hidden" value="'.$pagina.'"/><input id="lppf2932" name="lppf2932" type="hidden" value="'.$lineastabla.'"/>', $sDebug);
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
	$sTitulos='Ciclo, Tercero, Id, Estado, Fechaingreso, Fechafin';
	$sSQL='SELECT TB.plab32idciclo, T2.unad11razonsocial AS C2_nombre, TB.plab32id, TB.plab32estado, TB.plab32fechaingreso, TB.plab32fechafin, TB.plab32idtercero, T2.unad11tipodoc AS C2_td, T2.unad11doc AS C2_doc 
FROM plab32emonitor AS TB, unad11terceros AS T2 
WHERE '.$sSQLadd1.' TB.plab32idciclo='.$plab31id.' AND TB.plab32idtercero=T2.unad11id '.$sSQLadd.'
ORDER BY TB.plab32idtercero';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_2932" name="consulta_2932" type="hidden" value="'.$sSQLlista.'"/>
<input id="titulos_2932" name="titulos_2932" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objDB->ejecutasql($sSQL);
	if ($tabladetalle==false){
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 2932: '.$sSQL.'<br>';}
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		//$sLeyenda=$sSQL;
		}else{
		$registros=$objDB->nf($tabladetalle);
		if ($registros==0){
			return array(cadena_codificar($sErrConsulta.'<input id="paginaf2932" name="paginaf2932" type="hidden" value="'.$pagina.'"/><input id="lppf2932" name="lppf2932" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
<td colspan="2"><b>'.$ETI['plab32idtercero'].'</b></td>
<td><b>'.$ETI['plab32estado'].'</b></td>
<td><b>'.$ETI['plab32fechaingreso'].'</b></td>
<td><b>'.$ETI['plab32fechafin'].'</b></td>
<td align="right">
'.html_paginador('paginaf2932', $registros, $lineastabla, $pagina, 'paginarf2932()').'
'.html_lpp('lppf2932', $lineastabla, 'paginarf2932()').'
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
		$et_plab32idtercero_doc='';
		$et_plab32idtercero_nombre='';
		if ($filadet['plab32idtercero']!=0){
			$et_plab32idtercero_doc=$sPrefijo.$filadet['C2_td'].' '.$filadet['C2_doc'].$sSufijo;
			$et_plab32idtercero_nombre=$sPrefijo.cadena_notildes($filadet['C2_nombre']).$sSufijo;
			}
		$et_plab32estado=$sPrefijo.$filadet['plab32estado'].$sSufijo;
		$et_plab32fechaingreso='';
		if ($filadet['plab32fechaingreso']!=0){$et_plab32fechaingreso=$sPrefijo.fecha_desdenumero($filadet['plab32fechaingreso']).$sSufijo;}
		$et_plab32fechafin='';
		if ($filadet['plab32fechafin']!=0){$et_plab32fechafin=$sPrefijo.fecha_desdenumero($filadet['plab32fechafin']).$sSufijo;}
		if ($bAbierta){
			$sLink='<a href="javascript:cargaridf2932('.$filadet['plab32id'].')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$res=$res.'<tr'.$sClass.'>
		<td>'.$et_plab32idtercero_doc.'</td>
		<td>'.$et_plab32idtercero_nombre.'</td>
		<td>'.$et_plab32estado.'</td>
		<td>'.$et_plab32fechaingreso.'</td>
		<td>'.$et_plab32fechafin.'</td>
		<td>'.$sLink.'</td>
		</tr>';
		}
	$res=$res.'</table>
<div class="salto5px"></div>
</div>';
	$objDB->liberar($tabladetalle);
	return array(cadena_codificar($res), $sDebug);
	}
// -- 2932 Participantes XAJAX 
function f2932_Guardar($valores, $aParametros){
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
		list($sError, $iAccion, $plab32id, $sDebugGuardar)=f2932_db_Guardar($valores, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugGuardar;
		}
	$objResponse=new xajaxResponse();
	if ($sError==''){
		list($sdetalle, $sDebugTabla)=f2932_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugTabla;
		$objResponse->assign('div_f2932detalle', 'innerHTML', $sdetalle);
		//if ($iAccion==2){
			//$objResponse->call('cargaridf2932('.$plab32id.')');
			//}else{
			$objResponse->call('limpiaf2932');
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
function f2932_Traer($aParametros){
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
		$plab32idciclo=numeros_validar($aParametros[1]);
		$plab32idtercero=numeros_validar($aParametros[2]);
		if (($plab32idciclo!='')&&($plab32idtercero!='')){$besta=true;}
		}else{
		$plab32id=$aParametros[103];
		if ((int)$plab32id!=0){$besta=true;}
		}
	if ($besta){
		$besta=false;
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		$bHayDb=true;
		$sSQLcondi='';
		if ($paso==1){
			$sSQLcondi=$sSQLcondi.'plab32idciclo='.$plab32idciclo.' AND plab32idtercero='.$plab32idtercero.'';
			}else{
			$sSQLcondi=$sSQLcondi.'plab32id='.$plab32id.'';
			}
		$sSQL='SELECT * FROM plab32emonitor WHERE '.$sSQLcondi;
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
		$plab32idtercero_id=(int)$fila['plab32idtercero'];
		$plab32idtercero_td=$APP->tipo_doc;
		$plab32idtercero_doc='';
		$plab32idtercero_nombre='';
		if ($plab32idtercero_id!=0){
			list($plab32idtercero_nombre, $plab32idtercero_id, $plab32idtercero_td, $plab32idtercero_doc)=html_tercero($plab32idtercero_td, $plab32idtercero_doc, $plab32idtercero_id, 0, $objDB);
			}
		$html_plab32idtercero_llaves=html_DivTerceroV2('plab32idtercero', $plab32idtercero_td, $plab32idtercero_doc, true, 2, 'Ingrese el documento');
		$objResponse->assign('plab32idtercero', 'value', $plab32idtercero_id);
		$objResponse->assign('div_plab32idtercero_llaves', 'innerHTML', $html_plab32idtercero_llaves);
		$objResponse->assign('div_plab32idtercero', 'innerHTML', $plab32idtercero_nombre);
		$plab32id_nombre='';
		$html_plab32id=html_oculto('plab32id', $fila['plab32id'], $plab32id_nombre);
		$objResponse->assign('div_plab32id', 'innerHTML', $html_plab32id);
		$objResponse->assign('plab32estado', 'value', $fila['plab32estado']);
		$objResponse->assign('plab32fechaingreso', 'value', $fila['plab32fechaingreso']);
		list($iDia, $iMes, $iAgno)=fecha_DividirNumero($fila['plab32fechaingreso'], true);
		$objResponse->assign('plab32fechaingreso_dia', 'value', $iDia);
		$objResponse->assign('plab32fechaingreso_mes', 'value', $iMes);
		$objResponse->assign('plab32fechaingreso_agno', 'value', $iAgno);
		$objResponse->assign('plab32fechafin', 'value', $fila['plab32fechafin']);
		list($iDia, $iMes, $iAgno)=fecha_DividirNumero($fila['plab32fechafin'], true);
		$objResponse->assign('plab32fechafin_dia', 'value', $iDia);
		$objResponse->assign('plab32fechafin_mes', 'value', $iMes);
		$objResponse->assign('plab32fechafin_agno', 'value', $iAgno);
		$objResponse->call("MensajeAlarmaV2('', 0)");
		$objResponse->call("verboton('belimina2932','block')");
		}else{
		if ($paso==1){
			}else{
			$objResponse->call('MensajeAlarmaV2("No se encontro el registro de referencia:'.$plab32id.'", 0)');
			}
		}
	if ($bHayDb){
		$objDB->CerrarConexion();
		}
	return $objResponse;
	}
function f2932_Eliminar($aParametros){
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
	list($sError, $sDebugElimina)=f2932_db_Eliminar($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugElimina;
	$objResponse=new xajaxResponse();
	if ($sError==''){
		list($sDetalle, $sDebugTabla)=f2932_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugTabla;
		$objResponse->assign('div_f2932detalle', 'innerHTML', $sDetalle);
		$objResponse->call('limpiaf2932');
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
function f2932_HtmlTabla($aParametros){
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
	list($sDetalle, $sDebugTabla)=f2932_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f2932detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f2932_PintarLlaves($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	//if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	if (isset($APP->piel)==0){$APP->piel=1;}
	$iPiel=$APP->piel;
	$plab32idtercero=0;
	$plab32idtercero_rs='';
	$html_plab32idtercero_llaves=html_DivTerceroV2('plab32idtercero', $APP->tipo_doc, '', false, 2, $ETI['ing_doc']);
	$html_plab32id='<input id="plab32id" name="plab32id" type="hidden" value=""/>';
	$objResponse=new xajaxResponse();
	$objResponse->assign('plab32idtercero','value', $plab32idtercero);
	$objResponse->assign('div_plab32idtercero_llaves','innerHTML', $html_plab32idtercero_llaves);
	$objResponse->assign('div_plab32idtercero','innerHTML', $plab32idtercero_rs);
	$objResponse->assign('div_plab32id','innerHTML', $html_plab32id);
	return $objResponse;
	}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------
?>