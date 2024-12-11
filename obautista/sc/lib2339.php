<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2020 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.25.0 viernes, 22 de mayo de 2020
--- 2339 Autorizaciones
*/
function f2339_db_Guardar($valores, $objDB, $bDebug=false){
	$iCodModulo=2339;
	$bAudita[2]=false;
	$bAudita[3]=false;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2339='lg/lg_2339_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2339)){$mensajes_2339='lg/lg_2339_es.php';}
	require $mensajes_todas;
	require $mensajes_2339;
	$sError='';
	$sDebug='';
	$bInserta=false;
	$iAccion=3;
	if(!is_array($valores)){$valores=json_decode(str_replace('\"','"',$valores),true);}
	$cara39idtercero=numeros_validar($valores[1]);
	$cara39consec=numeros_validar($valores[2]);
	$cara39id=numeros_validar($valores[3], true);
	$cara39idautoriza=numeros_validar($valores[4]);
	$cara39fechaini=$valores[5];
	$cara39fechafin=$valores[6];
	$cara39estado=numeros_validar($valores[7]);
	$cara39detalle=htmlspecialchars(trim($valores[8]));
	$cara39notasistema=htmlspecialchars(trim($valores[9]));
	if ($cara39estado==''){$cara39estado=0;}
	$sSepara=', ';
	//if ($cara39notasistema==''){$sError=$ERR['cara39notasistema'].$sSepara.$sError;}
	if ($cara39detalle==''){$sError=$ERR['cara39detalle'].$sSepara.$sError;}
	//if ($cara39estado==''){$sError=$ERR['cara39estado'].$sSepara.$sError;}
	if ($cara39fechafin==0){
		//$cara39fechafin=fecha_DiaMod();
		//$sError=$ERR['cara39fechafin'].$sSepara.$sError;
		}
	if ($cara39fechaini==0){
		//$cara39fechaini=fecha_DiaMod();
		$sError=$ERR['cara39fechaini'].$sSepara.$sError;
		}
	if ($cara39idautoriza==0){$sError=$ERR['cara39idautoriza'].$sSepara.$sError;}
	//if ($cara39id==''){$sError=$ERR['cara39id'].$sSepara.$sError;}//CONSECUTIVO
	//if ($cara39consec==''){$sError=$ERR['cara39consec'].$sSepara.$sError;}//CONSECUTIVO
	if ($cara39idtercero==''){$sError=$ERR['cara39idtercero'].$sSepara.$sError;}
	if ($sError==''){
		list($sError, $sInfo)=tercero_Bloqueado($cara39idautoriza, $objDB);
		if ($sInfo!=''){$sError=$sError.'<br>'.$sInfo;}
		}
	if ($sError==''){
		if ((int)$cara39id==0){
			if ((int)$cara39consec==0){
				$cara39consec=tabla_consecutivo('cara39autorizacionmovil', 'cara39consec', 'cara39idtercero='.$cara39idtercero.'', $objDB);
				if ($cara39consec==-1){$sError=$objDB->serror;}
				}else{
				if (!seg_revisa_permiso($iCodModulo, 8, $objDB)){$sError=$ERR['8'];}
				}
			if ($sError==''){
				$sSQL='SELECT cara39idtercero FROM cara39autorizacionmovil WHERE cara39idtercero='.$cara39idtercero.' AND cara39consec='.$cara39consec.'';
				$result=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($result)!=0){
					$sError=$ERR['existe'];
					}else{
					if (!seg_revisa_permiso($iCodModulo, 2, $objDB)){$sError=$ERR['2'];}
					}
				}
			if ($sError==''){
				$cara39id=tabla_consecutivo('cara39autorizacionmovil', 'cara39id', '', $objDB);
				if ($cara39id==-1){$sError=$objDB->serror;}
				$bInserta=true;
				$iAccion=2;
				}
			}else{
			if (!seg_revisa_permiso($iCodModulo, 3, $objDB)){$sError=$ERR['3'];}
			}
		}
	if ($sError==''){
		if ($bInserta){
			$cara39estado=0;
			//$sSQL='SELECT Campo FROM Tabla WHERE Id='.$sValorId;
			//$tabla=$objDB->ejecutasql($sSQL);
			//if ($objDB->nf($tabla)>0){
				//$fila=$objDB->sf($tabla);
				//$sCampo=$fila['sCampo'];
				//}
			//$sError='INFORMACION PARA EL ADMINISTRADOR DEL SISTEMA: No ha definido valores para los campos ocultos.';
			}
		}
	if ($sError==''){
		//Si el campo cara39detalle permite html quite la linea htmlspecialchars para el campo y habilite la siguiente linea:
		//$cara39detalle=str_replace('"', '\"', $cara39detalle);
		$cara39detalle=str_replace('"', '\"', $cara39detalle);
		//Si el campo cara39notasistema permite html quite la linea htmlspecialchars para el campo y habilite la siguiente linea:
		//$cara39notasistema=str_replace('"', '\"', $cara39notasistema);
		$cara39notasistema=str_replace('"', '\"', $cara39notasistema);
		if ($bInserta){
			$sCampos2330='cara39idtercero, cara39consec, cara39id, cara39idautoriza, cara39fechaini, 
cara39fechafin, cara39estado, cara39detalle, cara39notasistema';
			$sValores2330=''.$cara39idtercero.', '.$cara39consec.', '.$cara39id.', "'.$cara39idautoriza.'", "'.$cara39fechaini.'", 
"'.$cara39fechafin.'", '.$cara39estado.', "'.$cara39detalle.'", "'.$cara39notasistema.'"';
			if ($APP->utf8==1){
				$sSQL='INSERT INTO cara39autorizacionmovil ('.$sCampos2330.') VALUES ('.cadena_codificar($sValores2330).');';
				}else{
				$sSQL='INSERT INTO cara39autorizacionmovil ('.$sCampos2330.') VALUES ('.$sValores2330.');';
				}
			$result=$objDB->ejecutasql($sSQL);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' {Autorizaciones}.<!-- '.$sSQL.' -->';
				}else{
				if ($bAudita[2]){
					seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 2, $cara39id, $sSQL, $objDB);
					}
				}
			}else{
			$scampo2339[1]='cara39fechaini';
			$scampo2339[2]='cara39fechafin';
			$scampo2339[3]='cara39detalle';
			$svr2339[1]=$cara39fechaini;
			$svr2339[2]=$cara39fechafin;
			$svr2339[3]=$cara39detalle;
			$inumcampos=3;
			$sWhere='cara39id='.$cara39id.'';
			//$sWhere='cara39idtercero='.$cara39idtercero.' AND cara39consec='.$cara39consec.'';
			$sSQL='SELECT * FROM cara39autorizacionmovil WHERE '.$sWhere;
			$sdatos='';
			$bpasa=false;
			$result=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($result)>0){
				$filaorigen=$objDB->sf($result);
				for ($k=1;$k<=$inumcampos;$k++){
					if ($filaorigen[$scampo2339[$k]]!=$svr2339[$k]){
						if ($sdatos!=''){$sdatos=$sdatos.', ';}
						$sdatos=$sdatos.$scampo2339[$k].'="'.$svr2339[$k].'"';
						$bpasa=true;
						}
					}
				}
			if ($bpasa){
				if ($APP->utf8==1){
					$sSQL='UPDATE cara39autorizacionmovil SET '.cadena_codificar($sdatos).' WHERE '.$sWhere.';';
					}else{
					$sSQL='UPDATE cara39autorizacionmovil SET '.$sdatos.' WHERE '.$sWhere.';';
					}
				$result=$objDB->ejecutasql($sSQL);
				if ($result==false){
					$sError=$ERR['falla_guardar'].' {Autorizaciones}. <!-- '.$sSQL.' -->';
					}else{
					//list($iRes, $sDebug)=f111_VerificarAccesoMoviles($cara39idtercero, $objDB, $bDebug);
					if ($bAudita[3]){
						seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 3, $cara39id, $sSQL, $objDB);
						}
					}
				}
			}
		}
	return array($sError, $iAccion, $cara39id, $sDebug);
	}
function f2339_db_Eliminar($aParametros, $objDB, $bDebug=false){
	$iCodModulo=2339;
	$bAudita[4]=false;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2339='lg/lg_2339_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2339)){$mensajes_2339='lg/lg_2339_es.php';}
	require $mensajes_todas;
	require $mensajes_2339;
	$sError='';
	$sDebug='';
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$cara39idtercero=numeros_validar($aParametros[1]);
	$cara39consec=numeros_validar($aParametros[2]);
	$cara39id=numeros_validar($aParametros[3]);
	if ($sError==''){
		if (!seg_revisa_permiso($iCodModulo, 4, $objDB)){
			$sError=$ERR['4'];
			}
		}
	if ($sError==''){
		$sSQL='SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=2339';
		$tablaor=$objDB->ejecutasql($sSQL);
		while ($filaor=$objDB->sf($tablaor)){
			$sSQL='SELECT '.$filaor['campoid'].' FROM '.$filaor['tabla'].' WHERE '.$filaor['camporev'].'='.$cara39id.' LIMIT 0, 1';
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
		$sWhere='cara39id='.$cara39id.'';
		//$sWhere='cara39idtercero='.$cara39idtercero.' AND cara39consec='.$cara39consec.'';
		$sSQL='DELETE FROM cara39autorizacionmovil WHERE '.$sWhere.';';
		$result=$objDB->ejecutasql($sSQL);
		if ($result==false){
			$sError=$ERR['falla_eliminar'].' {2339 Autorizaciones}.<!-- '.$sSQL.' -->';
			}else{
			if ($bAudita[4]){
				seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $cara39id, $sSQL, $objDB);
				}
			}
		}
	return array($sError, $sDebug);
	}
function f2339_TablaDetalleV2($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2339='lg/lg_2339_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2339)){$mensajes_2339='lg/lg_2339_es.php';}
	require $mensajes_todas;
	require $mensajes_2339;
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
	$unad11id=$aParametros[0];
	$pagina=$aParametros[101];
	$lineastabla=$aParametros[102];
	$bAbierta=true;
	//$sSQL='SELECT Campo FROM unad11terceros WHERE unad11id='.$unad11id;
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
		return array($sLeyenda.'<input id="paginaf2339" name="paginaf2339" type="hidden" value="'.$pagina.'"/><input id="lppf2339" name="lppf2339" type="hidden" value="'.$lineastabla.'"/>', $sDebug);
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
	$sTitulos='Tercero, Consec, Id, Autoriza, Fechaini, Fechafin, Estado, Detalle, Notasistema';
	$sSQL='SELECT TB.cara39idtercero, TB.cara39consec, TB.cara39id, T4.unad11razonsocial AS C4_nombre, TB.cara39fechaini, TB.cara39fechafin, TB.cara39estado, TB.cara39detalle, TB.cara39notasistema, TB.cara39idautoriza, T4.unad11tipodoc AS C4_td, T4.unad11doc AS C4_doc 
FROM cara39autorizacionmovil AS TB, unad11terceros AS T4 
WHERE '.$sSQLadd1.' TB.cara39idtercero='.$unad11id.' AND TB.cara39idautoriza=T4.unad11id '.$sSQLadd.'
ORDER BY TB.cara39consec';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_2339" name="consulta_2339" type="hidden" value="'.$sSQLlista.'"/>
<input id="titulos_2339" name="titulos_2339" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objDB->ejecutasql($sSQL);
	if ($tabladetalle==false){
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 2339: '.$sSQL.'<br>';}
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		//$sLeyenda=$sSQL;
		}else{
		$registros=$objDB->nf($tabladetalle);
		if ($registros==0){
			return array(cadena_codificar($sErrConsulta.'<input id="paginaf2339" name="paginaf2339" type="hidden" value="'.$pagina.'"/><input id="lppf2339" name="lppf2339" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
<td><b>'.$ETI['cara39consec'].'</b></td>
<td colspan="2"><b>'.$ETI['cara39idautoriza'].'</b></td>
<td><b>'.$ETI['cara39fechaini'].'</b></td>
<td><b>'.$ETI['cara39fechafin'].'</b></td>
<td><b>'.$ETI['cara39estado'].'</b></td>
<td align="right">
'.html_paginador('paginaf2339', $registros, $lineastabla, $pagina, 'paginarf2339()').'
'.html_lpp('lppf2339', $lineastabla, 'paginarf2339()').'
</td>
</tr>';
	$tlinea=1;
	while($filadet=$objDB->sf($tabladetalle)){
		$sPrefijo='';
		$sSufijo='';
		$sClass='';
		$sLink='';
		$et_cara39estado=$ETI['msg_autorizada'];
		if ($filadet['cara39estado']!=0){
			$sPrefijo='<span class="rojo">';
			$sSufijo='</span>';
			$et_cara39estado=$sPrefijo.$ETI['msg_revocada'].$sSufijo;
			}
		if(($tlinea%2)==0){$sClass=' class="resaltetabla"';}
		$tlinea++;
		$et_cara39consec=$sPrefijo.$filadet['cara39consec'].$sSufijo;
		$et_cara39idautoriza_doc='';
		$et_cara39idautoriza_nombre='';
		if ($filadet['cara39idautoriza']!=0){
			$et_cara39idautoriza_doc=$sPrefijo.$filadet['C4_td'].' '.$filadet['C4_doc'].$sSufijo;
			$et_cara39idautoriza_nombre=$sPrefijo.cadena_notildes($filadet['C4_nombre']).$sSufijo;
			}
		$et_cara39fechaini='';
		if ($filadet['cara39fechaini']!=0){$et_cara39fechaini=$sPrefijo.fecha_desdenumero($filadet['cara39fechaini']).$sSufijo;}
		$et_cara39fechafin='';
		if ($filadet['cara39fechafin']!=0){$et_cara39fechafin=$sPrefijo.fecha_desdenumero($filadet['cara39fechafin']).$sSufijo;}
		$et_cara39detalle=$sPrefijo.cadena_notildes($filadet['cara39detalle']).$sSufijo;
		$et_cara39notasistema=$sPrefijo.cadena_notildes($filadet['cara39notasistema']).$sSufijo;
		if ($bAbierta){
			$sLink='<a href="javascript:cargaridf2339('.$filadet['cara39id'].')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$sRevoca='';
		//<td><b>'.$ETI['cara39notasistema'].'</b></td>
		//<td>'.$et_cara39notasistema.'</td>
		$res=$res.'<tr'.$sClass.'>
<td>'.$et_cara39consec.'</td>
<td>'.$et_cara39idautoriza_doc.'</td>
<td>'.$et_cara39idautoriza_nombre.'</td>
<td>'.$et_cara39fechaini.'</td>
<td>'.$et_cara39fechafin.'</td>
<td>'.$et_cara39estado.'</td>
<td>'.$sLink.'</td>
</tr>'.$sRevoca;
		}
	$res=$res.'</table>';
	$objDB->liberar($tabladetalle);
	return array(cadena_codificar($res), $sDebug);
	}
// -- 2339 Autorizaciones XAJAX 
function f2339_Guardar($valores, $aParametros){
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
		list($sError, $iAccion, $cara39id, $sDebugGuardar)=f2339_db_Guardar($valores, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugGuardar;
		}
	$objResponse=new xajaxResponse();
	if ($sError==''){
		list($sdetalle, $sDebugTabla)=f2339_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugTabla;
		list($iRes, $sDebugM)=f111_VerificarAccesoMoviles($valores[1], $objDB, $bDebug);
		$unad11accesomovil_nombre=$ETI['no'];
		if ($iRes!=0){$unad11accesomovil_nombre=$ETI['si'];}
		$html_unad11accesomovil=html_oculto('unad11accesomovil', $iRes, $unad11accesomovil_nombre);
		$objResponse->assign('div_unad11accesomovil', 'innerHTML', $html_unad11accesomovil);
		$objResponse->assign('div_f2339detalle', 'innerHTML', $sdetalle);
		//if ($iAccion==2){
			//$objResponse->call('cargaridf2339('.$cara39id.')');
			//}else{
			$objResponse->call('limpiaf2339');
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
function f2339_Traer($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2339='lg/lg_2339_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2339)){$mensajes_2339='lg/lg_2339_es.php';}
	require $mensajes_todas;
	require $mensajes_2339;
	$sError='';
	$bHayDb=false;
	$besta=false;
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$paso=$aParametros[0];
	if ($paso==1){
		$cara39idtercero=numeros_validar($aParametros[1]);
		$cara39consec=numeros_validar($aParametros[2]);
		if (($cara39idtercero!='')&&($cara39consec!='')){$besta=true;}
		}else{
		$cara39id=$aParametros[103];
		if ((int)$cara39id!=0){$besta=true;}
		}
	if ($besta){
		$besta=false;
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		$bHayDb=true;
		$sSQLcondi='';
		if ($paso==1){
			$sSQLcondi=$sSQLcondi.'cara39idtercero='.$cara39idtercero.' AND cara39consec='.$cara39consec.'';
			}else{
			$sSQLcondi=$sSQLcondi.'cara39id='.$cara39id.'';
			}
		$sSQL='SELECT * FROM cara39autorizacionmovil WHERE '.$sSQLcondi;
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
		$cara39idautoriza_id=(int)$fila['cara39idautoriza'];
		$cara39idautoriza_td=$APP->tipo_doc;
		$cara39idautoriza_doc='';
		$cara39idautoriza_nombre='';
		if ($cara39idautoriza_id!=0){
			list($cara39idautoriza_nombre, $cara39idautoriza_id, $cara39idautoriza_td, $cara39idautoriza_doc)=html_tercero($cara39idautoriza_td, $cara39idautoriza_doc, $cara39idautoriza_id, 0, $objDB);
			}
		$cara39consec_nombre='';
		$html_cara39consec=html_oculto('cara39consec', $fila['cara39consec'], $cara39consec_nombre);
		$objResponse->assign('div_cara39consec', 'innerHTML', $html_cara39consec);
		$cara39id_nombre='';
		$html_cara39id=html_oculto('cara39id', $fila['cara39id'], $cara39id_nombre);
		$objResponse->assign('div_cara39id', 'innerHTML', $html_cara39id);
		$bOculto=true;
		$html_cara39idautoriza_llaves=html_DivTerceroV2('cara39idautoriza', $cara39idautoriza_td, $cara39idautoriza_doc, $bOculto, $cara39idautoriza_id, $ETI['ing_doc']);
		$objResponse->assign('cara39idautoriza', 'value', $cara39idautoriza_id);
		$objResponse->assign('div_cara39idautoriza_llaves', 'innerHTML', $html_cara39idautoriza_llaves);
		$objResponse->assign('div_cara39idautoriza', 'innerHTML', $cara39idautoriza_nombre);
		$objResponse->assign('cara39fechaini', 'value', $fila['cara39fechaini']);
		list($iDia, $iMes, $iAgno)=fecha_DividirNumero($fila['cara39fechaini'], true);
		$objResponse->assign('cara39fechaini_dia', 'value', $iDia);
		$objResponse->assign('cara39fechaini_mes', 'value', $iMes);
		$objResponse->assign('cara39fechaini_agno', 'value', $iAgno);
		$objResponse->assign('cara39fechafin', 'value', $fila['cara39fechafin']);
		list($iDia, $iMes, $iAgno)=fecha_DividirNumero($fila['cara39fechafin'], true);
		$objResponse->assign('cara39fechafin_dia', 'value', $iDia);
		$objResponse->assign('cara39fechafin_mes', 'value', $iMes);
		$objResponse->assign('cara39fechafin_agno', 'value', $iAgno);
		$cara39estado_nombre=$ETI['msg_autorizada'];
		if ($fila['cara39estado']!=0){
			$cara39estado_nombre=$ETI['msg_revocada'];
			}
		$html_cara39estado=html_oculto('cara39estado', $fila['cara39estado'], $cara39estado_nombre);
		$objResponse->assign('div_cara39estado', 'innerHTML', $html_cara39estado);
		$objResponse->assign('cara39detalle', 'value', $fila['cara39detalle']);
		$cara39notasistema_eti=$fila['cara39notasistema'];
		$html_cara39notasistema=html_oculto('cara39notasistema', $fila['cara39notasistema'], $cara39notasistema_eti);
		$objResponse->assign('div_cara39notasistema', 'innerHTML', $html_cara39notasistema);
		$objResponse->call("MensajeAlarmaV2('', 0)");
		$objResponse->call("verboton('belimina2339','block')");
		}else{
		if ($paso==1){
			$objResponse->assign('cara39consec', 'value', $cara39consec);
			}else{
			$objResponse->call('MensajeAlarmaV2("No se encontro el registro de referencia:'.$cara39id.'", 0)');
			}
		}
	if ($bHayDb){
		$objDB->CerrarConexion();
		}
	return $objResponse;
	}
function f2339_Eliminar($aParametros){
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
	list($sError, $sDebugElimina)=f2339_db_Eliminar($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugElimina;
	$objResponse=new xajaxResponse();
	if ($sError==''){
		list($sDetalle, $sDebugTabla)=f2339_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugTabla;
		list($iRes, $sDebugM)=f111_VerificarAccesoMoviles($opts[1], $objDB, $bDebug);
		$unad11accesomovil_nombre=$ETI['no'];
		if ($iRes!=0){$unad11accesomovil_nombre=$ETI['si'];}
		$html_unad11accesomovil=html_oculto('unad11accesomovil', $iRes, $unad11accesomovil_nombre);
		$objResponse->assign('div_unad11accesomovil', 'innerHTML', $html_unad11accesomovil);
		$objResponse->assign('div_f2339detalle', 'innerHTML', $sDetalle);
		$objResponse->call('limpiaf2339');
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
function f2339_HtmlTabla($aParametros){
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
	list($sDetalle, $sDebugTabla)=f2339_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f2339detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f2339_PintarLlaves($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	//if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2339='lg/lg_2339_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2339)){$mensajes_2339='lg/lg_2339_es.php';}
	require $mensajes_todas;
	require $mensajes_2339;
	if (isset($APP->piel)==0){$APP->piel=1;}
	$iPiel=$APP->piel;
	$objCombos=new clsHtmlCombos();
	$html_cara39consec='<input id="cara39consec" name="cara39consec" type="text" value="" onchange="revisaf2339()" class="cuatro"/>';
	$html_cara39id='<input id="cara39id" name="cara39id" type="hidden" value=""/>';
	list($cara39idautoriza_rs, $cara39idautoriza, $cara39idautoriza_td, $cara39idautoriza_doc)=html_tercero('CC', '', $_SESSION['unad_id_tercero'], 0, $objDB);
	$html_cara39idautoriza_llaves=html_DivTerceroV2('cara39idautoriza', $cara39idautoriza_td, $cara39idautoriza_doc, true, 0, $ETI['ing_doc']);
	$cara39estado_nombre=$ETI['msg_autorizada'];
	$html_cara39estado=html_oculto('cara39estado', 0, $cara39estado_nombre);
	$html_cara39notasistema=html_oculto('cara39notasistema', '');
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_cara39consec','innerHTML', $html_cara39consec);
	$objResponse->assign('div_cara39id','innerHTML', $html_cara39id);
	$objResponse->assign('cara39idautoriza','value', $cara39idautoriza);
	$objResponse->assign('div_cara39idautoriza_llaves','innerHTML', $html_cara39idautoriza_llaves);
	$objResponse->assign('div_cara39idautoriza','innerHTML', $cara39idautoriza_rs);
	$objResponse->assign('div_cara39estado','innerHTML', $html_cara39estado);
	$objResponse->assign('div_cara39notasistema','innerHTML', $html_cara39notasistema);
	return $objResponse;
	}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------
?>