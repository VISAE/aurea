<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2020 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.25.0 martes, 17 de marzo de 2020
--- 2211 Plan de estudios
*/
function f2211_db_Guardar($valores, $objDB, $bDebug=false){
	$iCodModulo=2211;
	$bAudita[2]=false;
	$bAudita[3]=false;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2211=$APP->rutacomun.'lg/lg_2211_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2211)){$mensajes_2211=$APP->rutacomun.'lg/lg_2211_es.php';}
	require $mensajes_todas;
	require $mensajes_2211;
	$sError='';
	$sDebug='';
	$bInserta=false;
	$iAccion=3;
	if(!is_array($valores)){$valores=json_decode(str_replace('\"','"',$valores),true);}
	$core11idcurso=numeros_validar($valores[1]);
	$core11idversionprograma=numeros_validar($valores[2]);
	$core11id=numeros_validar($valores[3], true);
	$core11idlineaprof=numeros_validar($valores[4]);
	//if ($core11idlineaprof==''){$core11idlineaprof=0;}
	//if ($core11idprograma==''){$core11idprograma=0;}
	$sSepara=', ';
	if ($core11idlineaprof==''){$sError=$ERR['core11idlineaprof'].$sSepara.$sError;}
	//if ($core11id==''){$sError=$ERR['core11id'].$sSepara.$sError;}//CONSECUTIVO
	if ($core11idversionprograma==0){$sError=$ERR['core11idversionprograma'].$sSepara.$sError;}
	if ($core11idcurso==''){$sError=$ERR['core11idcurso'].$sSepara.$sError;}
	if ($sError==''){
		$sSQL='SELECT  FROM core10programaversion WHERE ="'.$core11idversionprograma.'"';
		$result=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($result)==0){$sError='No se encuentra el Versionprograma {ref '.$core11idversionprograma.'}';}
		}
	if ($sError==''){
		if ((int)$core11id==0){
			if ($sError==''){
				$sSQL='SELECT core11idcurso FROM core11plandeestudio WHERE core11idcurso='.$core11idcurso.' AND core11idversionprograma='.$core11idversionprograma.'';
				$result=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($result)!=0){
					$sError=$ERR['existe'];
					}else{
					if (!seg_revisa_permiso($iCodModulo, 2, $objDB)){$sError=$ERR['2'];}
					}
				}
			if ($sError==''){
				$core11id=tabla_consecutivo('core11plandeestudio', 'core11id', '', $objDB);
				if ($core11id==-1){$sError=$objDB->serror;}
				$bInserta=true;
				$iAccion=2;
				}
			}else{
			if (!seg_revisa_permiso($iCodModulo, 3, $objDB)){$sError=$ERR['3'];}
			}
		}
	if ($sError==''){
		if ($bInserta){
			$core11idprograma=0;
			//$sSQL='SELECT Campo FROM Tabla WHERE Id='.$sValorId;
			//$tabla=$objDB->ejecutasql($sSQL);
			//if ($objDB->nf($tabla)>0){
				//$fila=$objDB->sf($tabla);
				//$sCampo=$fila['sCampo'];
				//}
			$sError='INFORMACION PARA EL ADMINISTRADOR DEL SISTEMA: No ha definido valores para los campos ocultos.';
			}
		}
	if ($sError==''){
		if ($bInserta){
			$sCampos140='core11idcurso, core11idversionprograma, core11id, core11idlineaprof, core11idprograma';
			$sValores140=''.$core11idcurso.', '.$core11idversionprograma.', '.$core11id.', '.$core11idlineaprof.', '.$core11idprograma.'';
			if ($APP->utf8==1){
				$sSQL='INSERT INTO core11plandeestudio ('.$sCampos140.') VALUES ('.utf8_encode($sValores140).');';
				}else{
				$sSQL='INSERT INTO core11plandeestudio ('.$sCampos140.') VALUES ('.$sValores140.');';
				}
			$result=$objDB->ejecutasql($sSQL);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' {Plan de estudios}.<!-- '.$sSQL.' -->';
				}else{
				if ($bAudita[2]){
					seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 2, $core11id, $sSQL, $objDB);
					}
				}
			}else{
			$scampo2211[1]='core11idlineaprof';
			$svr2211[1]=$core11idlineaprof;
			$inumcampos=1;
			$sWhere='core11id='.$core11id.'';
			//$sWhere='core11idcurso='.$core11idcurso.' AND core11idversionprograma='.$core11idversionprograma.'';
			$sSQL='SELECT * FROM core11plandeestudio WHERE '.$sWhere;
			$sdatos='';
			$bpasa=false;
			$result=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($result)>0){
				$filaorigen=$objDB->sf($result);
				for ($k=1;$k<=$inumcampos;$k++){
					if ($filaorigen[$scampo2211[$k]]!=$svr2211[$k]){
						if ($sdatos!=''){$sdatos=$sdatos.', ';}
						$sdatos=$sdatos.$scampo2211[$k].'="'.$svr2211[$k].'"';
						$bpasa=true;
						}
					}
				}
			if ($bpasa){
				if ($APP->utf8==1){
					$sSQL='UPDATE core11plandeestudio SET '.utf8_encode($sdatos).' WHERE '.$sWhere.';';
					}else{
					$sSQL='UPDATE core11plandeestudio SET '.$sdatos.' WHERE '.$sWhere.';';
					}
				$result=$objDB->ejecutasql($sSQL);
				if ($result==false){
					$sError=$ERR['falla_guardar'].' {Plan de estudios}. <!-- '.$sSQL.' -->';
					}else{
					if ($bAudita[3]){
						seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 3, $core11id, $sSQL, $objDB);
						}
					}
				}
			}
		}
	return array($sError, $iAccion, $core11id, $sDebug);
	}
function f2211_db_Eliminar($aParametros, $objDB, $bDebug=false){
	$iCodModulo=2211;
	$bAudita[4]=false;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2211=$APP->rutacomun.'lg/lg_2211_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2211)){$mensajes_2211=$APP->rutacomun.'lg/lg_2211_es.php';}
	require $mensajes_todas;
	require $mensajes_2211;
	$sError='';
	$sDebug='';
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$core11idcurso=numeros_validar($aParametros[1]);
	$core11idversionprograma=numeros_validar($aParametros[2]);
	$core11id=numeros_validar($aParametros[3]);
	if ($sError==''){
		if (!seg_revisa_permiso($iCodModulo, 4, $objDB)){
			$sError=$ERR['4'];
			}
		}
	if ($sError==''){
		$sSQL='SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=2211';
		$tablaor=$objDB->ejecutasql($sSQL);
		while ($filaor=$objDB->sf($tablaor)){
			$sSQL='SELECT '.$filaor['campoid'].' FROM '.$filaor['tabla'].' WHERE '.$filaor['camporev'].'='.$core11id.' LIMIT 0, 1';
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
		$sWhere='core11id='.$core11id.'';
		//$sWhere='core11idcurso='.$core11idcurso.' AND core11idversionprograma='.$core11idversionprograma.'';
		$sSQL='DELETE FROM core11plandeestudio WHERE '.$sWhere.';';
		$result=$objDB->ejecutasql($sSQL);
		if ($result==false){
			$sError=$ERR['falla_eliminar'].' {2211 Plan de estudios}.<!-- '.$sSQL.' -->';
			}else{
			if ($bAudita[4]){
				seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $core11id, $sSQL, $objDB);
				}
			}
		}
	return array($sError, $sDebug);
	}
function f2211_TablaDetalleV2($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2211=$APP->rutacomun.'lg/lg_2211_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2211)){$mensajes_2211=$APP->rutacomun.'lg/lg_2211_es.php';}
	require $mensajes_todas;
	require $mensajes_2211;
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
	$unad40id=$aParametros[0];
	$pagina=$aParametros[101];
	$lineastabla=$aParametros[102];
	$bAbierta=true;
	//$sSQL='SELECT Campo FROM unad40curso WHERE unad40id='.$unad40id;
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
		return array($sLeyenda.'<input id="paginaf2211" name="paginaf2211" type="hidden" value="'.$pagina.'"/><input id="lppf2211" name="lppf2211" type="hidden" value="'.$lineastabla.'"/>', $sDebug);
		die();
		}
	$aEstado=array();
	$aEstado['S']='Vigente';
	$aEstado['N']='En alistamiento';
	$aEstado['R']='En revisi&oacute;n';
	$aEstado['X']='Vencido';
/*
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
	$sTitulos='Curso, Versionprograma, Id, Lineaprof, Programa';
	$sSQL='SELECT TB.core11idcurso, T2.core10consec, T2.core10fechaversion, T2.core10numregcalificado, T2.core10estado, TB.core11id, TB.core11idprograma, TB.core11idversionprograma, TB.core11idlineaprof, T9.core09codigo, T9.core09nombre 
FROM core11plandeestudio AS TB, core10programaversion AS T2, core09programa AS T9 
WHERE '.$sSQLadd1.' TB.core11idcurso='.$unad40id.' AND TB.core11idversionprograma=T2.core10id AND T2.core10idprograma=T9.core09id '.$sSQLadd.'
ORDER BY TB.core11idversionprograma';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_2211" name="consulta_2211" type="hidden" value="'.$sSQLlista.'"/>
<input id="titulos_2211" name="titulos_2211" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objDB->ejecutasql($sSQL);
	if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 2211: '.$sSQL.'<br>';}
	if ($tabladetalle==false){
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		//$sLeyenda=$sSQL;
		}else{
		$registros=$objDB->nf($tabladetalle);
		if ($registros==0){
			$sLeyenda='<div class="salto1px"></div>
<div class="GrupoCamposAyuda">
<b>El curso no se encuentra asociado a planes de estudio.</b>
<div class="salto1px"></div>
</div>';
			return array(utf8_encode($sLeyenda.$sErrConsulta.'<input id="paginaf2211" name="paginaf2211" type="hidden" value="'.$pagina.'"/><input id="lppf2211" name="lppf2211" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
<td colspan="2"><b>'.$ETI['core11idprograma'].'</b></td>
<td><b>'.$ETI['core11idversionprograma'].'</b></td>
<td><b>'.$ETI['core10estado'].'</b></td>
<td align="right">
'.html_paginador('paginaf2211', $registros, $lineastabla, $pagina, 'paginarf2211()').'
'.html_lpp('lppf2211', $lineastabla, 'paginarf2211()').'
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
		//T2.core10consec, T2.core10fechaversion, T2.core10numregcalificado
		$et_core11idversionprograma=$sPrefijo.$filadet['core10consec'].' - '.fecha_desdenumero($filadet['core10fechaversion']).' [Res MEN '.$filadet['core10numregcalificado'].']'.$sSufijo;
		//T9.core09codigo, T9.core09nombre
		$et_core11idprograma_consec=$sPrefijo.$filadet['core09codigo'].$sSufijo;
		$et_core11idprograma=$sPrefijo.cadena_notildes($filadet['core09nombre']).$sSufijo;
		$et_core10estado=$aEstado[$filadet['core10estado']];
		if ($bAbierta){
			//$sLink='<a href="javascript:cargaridf2211('.$filadet['core11id'].')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$res=$res.'<tr'.$sClass.'>
<td>'.$et_core11idprograma_consec.'</td>
<td>'.$et_core11idprograma.'</td>
<td>'.$et_core11idversionprograma.'</td>
<td colspan="2">'.$et_core10estado.'</td>
</tr>';
		}
	$res=$res.'</table>';
	$objDB->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
	}
// -- 2211 Plan de estudios XAJAX 
function f2211_Busqueda_db_core11idversionprograma($sCodigo, $objDB, $bDebug=false){
	$sRespuesta='';
	$sDebug='';
	$id=0;
	$sCodigo=htmlspecialchars(trim($sCodigo));
	if ($sCodigo!=''){
		$sSQL='SELECT , core10estado, core10id FROM core10programaversion WHERE core10id="'.$sCodigo.'"';
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta Busqueda: '.$sSQL.'<br>';}
		$res=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($res)!=0){
			$fila=$objDB->sf($res);
			$sRespuesta='<b>'.$fila['core10id'].' '.cadena_notildes($fila['core10estado']).'</b>';
			$id=$fila[''];
			}
		if ($sRespuesta==''){
			$sRespuesta='<span class="rojo">{'.$sCodigo.' No encontrado}</span>';
			}
		}
	return array($id, utf8_encode($sRespuesta), $sDebug);
	}
function f2211_Busqueda_core11idversionprograma($aParametros){
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$_SESSION['u_ultimominuto']=iminutoavance();
	$sRespuesta='';
	$sDebug='';
	$scodigo=$aParametros[0];
	$bxajax=true;
	$bDebug=false;
	if (isset($aParametros[3])!=0){if ($aParametros[3]==1){$bxajax=false;}}
	if (isset($aParametros[9])!=0){if ($aParametros[9]==1){$bDebug=true;}}
	$id=0;
	if ($scodigo!=''){
		require './app.php';
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		list($id, $sRespuesta, $sDebugCon)=f2211_Busqueda_db_core11idversionprograma($scodigo, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugCon;
		$objDB->CerrarConexion();
		}
	$objid=$aParametros[1];
	$sdiv=$aParametros[2];
	$objResponse=new xajaxResponse();
	$objResponse->assign($sdiv, 'innerHTML', $sRespuesta);
	$objResponse->assign($objid, 'value', $id);
	if ((int)$id>0){
		$objResponse->call('revisaf2211');
		}
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f2211_Guardar($valores, $aParametros){
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
		list($sError, $iAccion, $core11id, $sDebugGuardar)=f2211_db_Guardar($valores, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugGuardar;
		}
	$objResponse=new xajaxResponse();
	if ($sError==''){
		list($sdetalle, $sDebugTabla)=f2211_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugTabla;
		$objResponse->assign('div_f2211detalle', 'innerHTML', $sdetalle);
		//if ($iAccion==2){
			//$objResponse->call('cargaridf2211('.$core11id.')');
			//}else{
			$objResponse->call('limpiaf2211');
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
function f2211_Traer($aParametros){
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
		$core11idcurso=numeros_validar($aParametros[1]);
		$core11idversionprograma=numeros_validar($aParametros[2]);
		if (($core11idcurso!='')&&($core11idversionprograma!='')){$besta=true;}
		}else{
		$core11id=$aParametros[103];
		if ((int)$core11id!=0){$besta=true;}
		}
	if ($besta){
		$besta=false;
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		$bHayDb=true;
		$sSQLcondi='';
		if ($paso==1){
			$sSQLcondi=$sSQLcondi.'core11idcurso='.$core11idcurso.' AND core11idversionprograma='.$core11idversionprograma.'';
			}else{
			$sSQLcondi=$sSQLcondi.'core11id='.$core11id.'';
			}
		$sSQL='SELECT * FROM core11plandeestudio WHERE '.$sSQLcondi;
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
		$core11idversionprograma_nombre='';
		$core11idversionprograma_cod='';
		if ((int)$fila['core11idversionprograma']!=0){
			$sSQL='SELECT core10id, core10estado FROM core10programaversion WHERE ='.$fila['core11idversionprograma'].'';
			$res=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($res)!=0){
				$filaDetalle=$objDB->sf($res);
				$core11idversionprograma_nombre='<b>'.cadena_notildes($filaDetalle['core10estado']).'</b>';
				$core11idversionprograma_cod=$filaDetalle['core10id'];
				}
			if ($core11idversionprograma_nombre==''){
				$core11idversionprograma_nombre='<font class="rojo">{Ref : '.$fila['core11idversionprograma'].' No encontrado}</font>';
				}
			}
		$html_core11idversionprograma_cod=html_oculto('core11idversionprograma_cod', $core11idversionprograma_cod);
		$objResponse->assign('core11idversionprograma', 'value', $fila['core11idversionprograma']);
		$objResponse->assign('div_core11idversionprograma_cod', 'innerHTML', $html_core11idversionprograma_cod);
		$objResponse->call("verboton('bcore11idversionprograma','none')");
		$objResponse->assign('div_core11idversionprograma', 'innerHTML', $core11idversionprograma_nombre);
		$core11id_nombre='';
		$html_core11id=html_oculto('core11id', $fila['core11id'], $core11id_nombre);
		$objResponse->assign('div_core11id', 'innerHTML', $html_core11id);
		$objResponse->assign('core11idlineaprof', 'value', $fila['core11idlineaprof']);
		$objResponse->assign('core11idprograma', 'value', $fila['core11idprograma']);
		$objResponse->call("MensajeAlarmaV2('', 0)");
		$objResponse->call("verboton('belimina2211','block')");
		}else{
		if ($paso==1){
			$objResponse->assign('core11idversionprograma', 'value', $core11idversionprograma);
			}else{
			$objResponse->call('MensajeAlarmaV2("No se encontro el registro de referencia:'.$core11id.'", 0)');
			}
		}
	if ($bHayDb){
		$objDB->CerrarConexion();
		}
	return $objResponse;
	}
function f2211_Eliminar($aParametros){
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
	list($sError, $sDebugElimina)=f2211_db_Eliminar($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugElimina;
	$objResponse=new xajaxResponse();
	if ($sError==''){
		list($sDetalle, $sDebugTabla)=f2211_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugTabla;
		$objResponse->assign('div_f2211detalle', 'innerHTML', $sDetalle);
		$objResponse->call('limpiaf2211');
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
function f2211_HtmlTabla($aParametros){
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
	list($sDetalle, $sDebugTabla)=f2211_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f2211detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f2211_PintarLlaves($aParametros){
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
	$objCombos=new clsHtmlCombos('n');
	$html_core11idversionprograma_cod='<input id="core11idversionprograma_cod" name="core11idversionprograma_cod" type="text" value="" onchange="cod_core11idversionprograma()" class="veinte"/>';
	$html_core11id='<input id="core11id" name="core11id" type="hidden" value=""/>';
	$objResponse=new xajaxResponse();
	$objResponse->assign('core11idversionprograma','value', '0');
	$objResponse->assign('div_core11idversionprograma_cod','innerHTML', $html_core11idversionprograma_cod);
	$objResponse->assign('div_core11idversionprograma','innerHTML', '');
	$objResponse->call("verboton('bcore11idversionprograma','block')");
	$objResponse->assign('div_core11id','innerHTML', $html_core11id);
	return $objResponse;
	}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------
?>