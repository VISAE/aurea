<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2019 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.23.6 Friday, September 20, 2019
--- 2329 Asistentes
*/
function f2329_db_Guardar($valores, $objDB, $bDebug=false){
	$iCodModulo=2329;
	$bAudita[2]=false;
	$bAudita[3]=false;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2329='lg/lg_2329_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2329)){$mensajes_2329='lg/lg_2329_es.php';}
	require $mensajes_todas;
	require $mensajes_2329;
	$sError='';
	$sDebug='';
	$binserta=false;
	$iAccion=3;
	if(!is_array($valores)){$valores=json_decode(str_replace('\"','"',$valores),true);}
	$cara29idactividad=numeros_validar($valores[1]);
	$cara29idtercero=numeros_validar($valores[2]);
	$cara29id=numeros_validar($valores[3], true);
	$cara29estado=numeros_validar($valores[4]);
	//if ($cara29estado==''){$cara29estado=0;}
	$sSepara=', ';
	if ($cara29estado==''){$sError=$ERR['cara29estado'].$sSepara.$sError;}
	//if ($cara29id==''){$sError=$ERR['cara29id'].$sSepara.$sError;}//CONSECUTIVO
	if ($cara29idtercero==0){$sError=$ERR['cara29idtercero'].$sSepara.$sError;}
	if ($cara29idactividad==''){$sError=$ERR['cara29idactividad'].$sSepara.$sError;}
	if ($sError==''){
		list($sError, $sInfo)=tercero_Bloqueado($cara29idtercero, $objDB);
		if ($sInfo!=''){$sError=$sError.'<br>'.sInfo;}
		}
	if ($sError==''){
		if ((int)$cara29id==0){
			if ($sError==''){
				$sSQL='SELECT cara29idactividad FROM cara29actividadasiste WHERE cara29idactividad='.$cara29idactividad.' AND cara29idtercero="'.$cara29idtercero.'"';
				$result=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($result)!=0){
					$sError=$ERR['existe'];
					}else{
					if (!seg_revisa_permiso($iCodModulo, 2, $objDB)){$sError=$ERR['2'];}
					}
				}
			if ($sError==''){
				$cara29id=tabla_consecutivo('cara29actividadasiste', 'cara29id', '', $objDB);
				if ($cara29id==-1){$sError=$objDB->serror;}
				$binserta=true;
				$iAccion=2;
				}
			}else{
			if (!seg_revisa_permiso($iCodModulo, 3, $objDB)){$sError=$ERR['3'];}
			}
		}
	if ($sError==''){
		if ($binserta){
			}
		}
	if ($sError==''){
		if ($binserta){
			$scampos='cara29idactividad, cara29idtercero, cara29id, cara29estado';
			$svalores=''.$cara29idactividad.', "'.$cara29idtercero.'", '.$cara29id.', '.$cara29estado.'';
			if ($APP->utf8==1){
				$sSQL='INSERT INTO cara29actividadasiste ('.$scampos.') VALUES ('.utf8_encode($svalores).');';
				}else{
				$sSQL='INSERT INTO cara29actividadasiste ('.$scampos.') VALUES ('.$svalores.');';
				}
			$result=$objDB->ejecutasql($sSQL);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' {Asistentes}.<!-- '.$sSQL.' -->';
				}else{
				if ($bAudita[2]){
					seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 2, $cara29id, $sSQL, $objDB);
					}
				}
			}else{
			$scampo2329[1]='cara29estado';
			$svr2329[1]=$cara29estado;
			$inumcampos=1;
			$sWhere='cara29id='.$cara29id.'';
			//$sWhere='cara29idactividad='.$cara29idactividad.' AND cara29idtercero="'.$cara29idtercero.'"';
			$sSQL='SELECT * FROM cara29actividadasiste WHERE '.$sWhere;
			$sdatos='';
			$bpasa=false;
			$result=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($result)>0){
				$filaorigen=$objDB->sf($result);
				for ($k=1;$k<=$inumcampos;$k++){
					if ($filaorigen[$scampo2329[$k]]!=$svr2329[$k]){
						if ($sdatos!=''){$sdatos=$sdatos.', ';}
						$sdatos=$sdatos.$scampo2329[$k].'="'.$svr2329[$k].'"';
						$bpasa=true;
						}
					}
				}
			if ($bpasa){
				if ($APP->utf8==1){
					$sSQL='UPDATE cara29actividadasiste SET '.utf8_encode($sdatos).' WHERE '.$sWhere.';';
					}else{
					$sSQL='UPDATE cara29actividadasiste SET '.$sdatos.' WHERE '.$sWhere.';';
					}
				$result=$objDB->ejecutasql($sSQL);
				if ($result==false){
					$sError=$ERR['falla_guardar'].' {Asistentes}. <!-- '.$sSQL.' -->';
					}else{
					if ($bAudita[3]){
						seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 3, $cara29id, $sSQL, $objDB);
						}
					}
				}
			}
		}
	return array($sError, $iAccion, $cara29id, $sDebug);
	}
function f2329_db_Eliminar($aParametros, $objDB, $bDebug=false){
	$iCodModulo=2329;
	$bAudita[4]=false;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2329='lg/lg_2329_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2329)){$mensajes_2329='lg/lg_2329_es.php';}
	require $mensajes_todas;
	require $mensajes_2329;
	$sError='';
	$sDebug='';
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$cara29idactividad=numeros_validar($aParametros[1]);
	$cara29idtercero=numeros_validar($aParametros[2]);
	$cara29id=numeros_validar($aParametros[3]);
	if ($sError==''){
		if (!seg_revisa_permiso($iCodModulo, 4, $objDB)){
			$sError=$ERR['4'];
			}
		}
	if ($sError==''){
		$sSQL='SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=2329';
		$tablaor=$objDB->ejecutasql($sSQL);
		while ($filaor=$objDB->sf($tablaor)){
			$sSQL='SELECT '.$filaor['campoid'].' FROM '.$filaor['tabla'].' WHERE '.$filaor['camporev'].'='.$cara29id.' LIMIT 0, 1';
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
		$sWhere='cara29id='.$cara29id.'';
		//$sWhere='cara29idactividad='.$cara29idactividad.' AND cara29idtercero="'.$cara29idtercero.'"';
		$sSQL='DELETE FROM cara29actividadasiste WHERE '.$sWhere.';';
		$result=$objDB->ejecutasql($sSQL);
		if ($result==false){
			$sError=$ERR['falla_eliminar'].' {2329 Asistentes}.<!-- '.$sSQL.' -->';
			}else{
			if ($bAudita[4]){
				seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $cara29id, $sSQL, $objDB);
				}
			}
		}
	return array($sError, $sDebug);
	}
function f2329_TablaDetalleV2($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2329='lg/lg_2329_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2329)){$mensajes_2329='lg/lg_2329_es.php';}
	require $mensajes_todas;
	require $mensajes_2329;
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	if (isset($aParametros[0])==0){$aParametros[0]=-1;}
	if (isset($aParametros[101])==0){$aParametros[101]=1;}
	if (isset($aParametros[102])==0){$aParametros[102]=20;}
	//if (isset($aParametros[103])==0){$aParametros[103]='';}
	//$aParametros[103]=numeros_validar($aParametros[103]);
	$aParametros[0]=numeros_validar($aParametros[0]);
	if ($aParametros[0]==''){$aParametros[0]=-1;}
	$sDebug='';
	$cara28id=$aParametros[0];
	$pagina=$aParametros[101];
	$lineastabla=$aParametros[102];
	$babierta=false;
	$sSQL='SELECT cara28estado FROM cara28actividades WHERE cara28id='.$cara28id;
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		if ($fila['cara28estado']!=7){$babierta=true;}
		}
	$sSQLadd='';
	$sSQLadd1='';
	$sLeyenda='';
	if (false){
		$sLeyenda='<div class="salto1px"></div>
<div class="GrupoCamposAyuda">
<b>Importante:</b> Mensaje al usuario
<div class="salto1px"></div>
</div>';
		}
	//if ((int)$aParametros[103]!=-1){$sSQLadd=$sSQLadd.' AND TB.campo='.$aParametros[103];}
	//if ($aParametros[103]!=''){$sSQLadd=$sSQLadd.' AND TB.campo2 LIKE "%'.$aParametros[103].'%"';}
	/*
	if ($aParametros[104]!=''){
		$sBase=trim(strtoupper($aParametros[104]));
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
	$sTitulos='Actividad, Tercero, Id, Estado';
	$sSQL='SELECT TB.cara29idactividad, T2.unad11razonsocial AS C2_nombre, TB.cara29id, T4.cara31nombre, TB.cara29idtercero, T2.unad11tipodoc AS C2_td, T2.unad11doc AS C2_doc, TB.cara29estado 
FROM cara29actividadasiste AS TB, unad11terceros AS T2, cara31estadoasiste AS T4 
WHERE '.$sSQLadd1.' TB.cara29idactividad='.$cara28id.' AND TB.cara29idtercero=T2.unad11id AND TB.cara29estado=T4.cara31id '.$sSQLadd.'
ORDER BY TB.cara29idtercero';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_2329" name="consulta_2329" type="hidden" value="'.$sSQLlista.'"/>
<input id="titulos_2329" name="titulos_2329" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objDB->ejecutasql($sSQL);
	if ($tabladetalle==false){
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 2329: '.$sSQL.'<br>';}
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		//$sLeyenda=$sSQL;
		}else{
		$registros=$objDB->nf($tabladetalle);
		if ($registros==0){
			return array(utf8_encode($sErrConsulta.'<input id="paginaf2329" name="paginaf2329" type="hidden" value="'.$pagina.'"/><input id="lppf2329" name="lppf2329" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
<td colspan="2"><b>'.$ETI['cara29idtercero'].'</b></td>
<td><b>'.$ETI['cara29estado'].'</b></td>
<td align="right">
'.html_paginador('paginaf2329', $registros, $lineastabla, $pagina, 'paginarf2329()').'
'.html_lpp('lppf2329', $lineastabla, 'paginarf2329()').'
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
		$et_cara29idtercero=$sPrefijo.$filadet['cara29idtercero'].$sSufijo;
		$et_cara29estado=$sPrefijo.cadena_notildes($filadet['cara31nombre']).$sSufijo;
		if ($babierta){
			$sLink='<a href="javascript:cargaridf2329('.$filadet['cara29id'].')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$res=$res.'<tr'.$sClass.'>
<td>'.$sPrefijo.$filadet['C2_td'].' '.$filadet['C2_doc'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['C2_nombre']).$sSufijo.'</td>
<td>'.$et_cara29estado.'</td>
<td>'.$sLink.'</td>
</tr>';
		}
	$res=$res.'</table>';
	$objDB->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
	}
function f2329_Clonar($cara29idactividad, $cara29idactividadPadre, $objDB){
	$sError='';
	$cara29id=tabla_consecutivo('cara29actividadasiste', 'cara29id', '', $objDB);
	if ($cara29id==-1){$sError=$objDB->serror;}
	if ($sError==''){
		$sCampos2329='cara29idactividad, cara29idtercero, cara29id, cara29estado';
		$sValores2329='';
		$sSQL='SELECT * FROM cara29actividadasiste WHERE cara29idactividad='.$cara29idactividadPadre.'';
		$tabla=$objDB->ejecutasql($sSQL);
		while($fila=$objDB->sf($tabla)){
			if ($sValores2329!=''){$sValores2329=$sValores2329.', ';}
			$sValores2329=$sValores2329.'('.$cara29idactividad.', '.$fila['cara29idtercero'].', '.$cara29id.', '.$fila['cara29estado'].')';
			$cara29id++;
			}
		if ($sValores2329!=''){
			$sSQL='INSERT INTO cara29actividadasiste('.$sCampos2329.') VALUES '.$sValores2329.'';
			$result=$objDB->ejecutasql($sSQL);
			}
		}
	return $sError;
	}
// -- 2329 Asistentes XAJAX 
function f2329_Guardar($valores, $aParametros){
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
		list($sError, $iAccion, $cara29id, $sDebugGuardar)=f2329_db_Guardar($valores, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugGuardar;
		}
	$objResponse=new xajaxResponse();
	if ($sError==''){
		list($sdetalle, $sDebugTabla)=f2329_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugTabla;
		$objResponse->assign('div_f2329detalle', 'innerHTML', $sdetalle);
		//if ($iAccion==2){
			//$objResponse->call('cargaridf2329('.$cara29id.')');
			//}else{
			$objResponse->call('limpiaf2329');
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
function f2329_Traer($aParametros){
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
		$cara29idactividad=numeros_validar($aParametros[1]);
		$cara29idtercero=numeros_validar($aParametros[2]);
		if (($cara29idactividad!='')&&($cara29idtercero!='')){$besta=true;}
		}else{
		$cara29id=$aParametros[103];
		if ((int)$cara29id!=0){$besta=true;}
		}
	if ($besta){
		$besta=false;
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		$bHayDb=true;
		$sSQLcondi='';
		if ($paso==1){
			$sSQLcondi=$sSQLcondi.'cara29idactividad='.$cara29idactividad.' AND cara29idtercero='.$cara29idtercero.'';
			}else{
			$sSQLcondi=$sSQLcondi.'cara29id='.$cara29id.'';
			}
		$sSQL='SELECT * FROM cara29actividadasiste WHERE '.$sSQLcondi;
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
		$cara29idtercero_id=(int)$fila['cara29idtercero'];
		$cara29idtercero_td=$APP->tipo_doc;
		$cara29idtercero_doc='';
		$cara29idtercero_nombre='';
		if ($cara29idtercero_id!=0){
			list($cara29idtercero_nombre, $cara29idtercero_id, $cara29idtercero_td, $cara29idtercero_doc)=html_tercero($cara29idtercero_td, $cara29idtercero_doc, $cara29idtercero_id, 0, $objDB);
			}
		$html_cara29idtercero_llaves=html_DivTerceroV2('cara29idtercero', $cara29idtercero_td, $cara29idtercero_doc, true, 2, 'Ingrese el documento');
		$objResponse->assign('cara29idtercero', 'value', $cara29idtercero_id);
		$objResponse->assign('div_cara29idtercero_llaves', 'innerHTML', $html_cara29idtercero_llaves);
		$objResponse->assign('div_cara29idtercero', 'innerHTML', $cara29idtercero_nombre);
		$cara29id_nombre='';
		$html_cara29id=html_oculto('cara29id', $fila['cara29id'], $cara29id_nombre);
		$objResponse->assign('div_cara29id', 'innerHTML', $html_cara29id);
		$objResponse->assign('cara29estado', 'value', $fila['cara29estado']);
		$objResponse->call("MensajeAlarmaV2('', 0)");
		$objResponse->call("verboton('belimina2329','block')");
		}else{
		if ($paso==1){
			}else{
			$objResponse->call('MensajeAlarmaV2("No se encontro el registro de referencia:'.$cara29id.'", 0)');
			}
		}
	if ($bHayDb){
		$objDB->CerrarConexion();
		}
	return $objResponse;
	}
function f2329_Eliminar($aParametros){
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
	list($sError, $sDebugElimina)=f2329_db_Eliminar($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugElimina;
	$objResponse=new xajaxResponse();
	if ($sError==''){
		list($sDetalle, $sDebugTabla)=f2329_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugTabla;
		$objResponse->assign('div_f2329detalle', 'innerHTML', $sDetalle);
		$objResponse->call('limpiaf2329');
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
function f2329_HtmlTabla($aParametros){
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
	list($sDetalle, $sDebugTabla)=f2329_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f2329detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f2329_PintarLlaves($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	//if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	if (isset($APP->piel)==0){$APP->piel=1;}
	$iPiel=$APP->piel;
	$cara29idtercero=0;
	$cara29idtercero_rs='';
	$html_cara29idtercero_llaves=html_DivTerceroV2('cara29idtercero', $APP->tipo_doc, '', false, 2, $ETI['ing_doc']);
	$html_cara29id='<input id="cara29id" name="cara29id" type="hidden" value=""/>';
	$objResponse=new xajaxResponse();
	$objResponse->assign('cara29idtercero','value', $cara29idtercero);
	$objResponse->assign('div_cara29idtercero_llaves','innerHTML', $html_cara29idtercero_llaves);
	$objResponse->assign('div_cara29idtercero','innerHTML', $cara29idtercero_rs);
	$objResponse->assign('div_cara29id','innerHTML', $html_cara29id);
	return $objResponse;
	}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------
?>