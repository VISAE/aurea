<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2020 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.25.5 jueves, 13 de agosto de 2020
--- 3029 Anexos Mesa de ayuda
*/
function f3029_HTMLComboV2_saiu29idanexo($objDB, $objCombos, $valor){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('saiu29idanexo', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	//$objCombos->iAncho=450;
	$objCombos->sAccion='revisaf3029()';
	$sSQL='SELECT saiu04id AS id, saiu04titulo AS nombre FROM saiu04temaanexo';
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f3029_db_Guardar($valores, $objDB, $bDebug=false){
	$iCodModulo=3029;
	$bAudita[2]=false;
	$bAudita[3]=false;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3029='lg/lg_3029_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3029)){$mensajes_3029='lg/lg_3029_es.php';}
	require $mensajes_todas;
	require $mensajes_3029;
	$sError='';
	$sDebug='';
	$bInserta=false;
	$iAccion=3;
	if(!is_array($valores)){$valores=json_decode(str_replace('\"','"',$valores),true);}
	if (isset($valores[98])==0){$valores[98]='';}
	$saiu29idsolicitud=numeros_validar($valores[1]);
	$saiu29idanexo=numeros_validar($valores[2]);
	$saiu29consec=numeros_validar($valores[3]);
	$saiu29id=numeros_validar($valores[4]);
	$saiu29detalle=htmlspecialchars(trim($valores[7]));
	$iAgno=numeros_validar($valores[98]);
	$sSepara=', ';
	if ($iAgno==''){
		$sError='<b>No se ha definido un a&ntilde;o</b>';
	}else{
		$sTabla29='saiu29anexos_'.$iAgno;
		if (!tabla_existe($sTabla29, $objDB)){
			$sError='No ha sido posible encontrar el origen de datos '.$sTabla29;
		}
	}
	//if ($saiu29detalle==''){$sError=$ERR['saiu29detalle'].$sSepara.$sError;}
	//if ($saiu29id==''){$sError=$ERR['saiu29id'].$sSepara.$sError;}//CONSECUTIVO
	//if ($saiu29consec==''){$sError=$ERR['saiu29consec'].$sSepara.$sError;}//CONSECUTIVO
	if ($saiu29idanexo==''){$sError=$ERR['saiu29idanexo'].$sSepara.$sError;}
	if ($saiu29idsolicitud==''){$sError=$ERR['saiu29idsolicitud'].$sSepara.$sError;}
	if ($sError==''){
		if ((int)$saiu29id==0){
			if ((int)$saiu29consec==0){
				$saiu29consec=tabla_consecutivo($sTabla29, 'saiu29consec', 'saiu29idsolicitud='.$saiu29idsolicitud.' AND saiu29idanexo='.$saiu29idanexo.'', $objDB);
				if ($saiu29consec==-1){$sError=$objDB->serror;}
				}else{
				if (!seg_revisa_permiso($iCodModulo, 8, $objDB)){$sError=$ERR['8'];}
				}
			if ($sError==''){
				$sSQL='SELECT saiu29idsolicitud FROM '.$sTabla29.' WHERE saiu29idsolicitud='.$saiu29idsolicitud.' AND saiu29idanexo='.$saiu29idanexo.' AND saiu29consec='.$saiu29consec.'';
				$result=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($result)!=0){
					$sError=$ERR['existe'];
					}else{
					if (!seg_revisa_permiso($iCodModulo, 2, $objDB)){$sError=$ERR['2'];}
					}
				}
			if ($sError==''){
				$saiu29id=tabla_consecutivo($sTabla29, 'saiu29id', '', $objDB);
				if ($saiu29id==-1){$sError=$objDB->serror;}
				$bInserta=true;
				$iAccion=2;
				}
			}else{
			if (!seg_revisa_permiso($iCodModulo, 3, $objDB)){$sError=$ERR['3'];}
			}
		}
	if ($sError==''){
		if ($bInserta){
			$saiu29idorigen=0;
			$saiu29idarchivo=0;
			}
		}
	if ($sError==''){
		//Si el campo saiu29detalle permite html quite la linea htmlspecialchars para el campo y habilite la siguiente linea:
		//$saiu29detalle=str_replace('"', '\"', $saiu29detalle);
		$saiu29detalle=str_replace('"', '\"', $saiu29detalle);
		if ($bInserta){
			$sCampos3029='saiu29idsolicitud, saiu29idanexo, saiu29consec, saiu29id, saiu29idorigen, 
			saiu29idarchivo, saiu29detalle';
			$sValores3029=''.$saiu29idsolicitud.', '.$saiu29idanexo.', '.$saiu29consec.', '.$saiu29id.', 0, 
			0, "'.$saiu29detalle.'"';
			if ($APP->utf8==1){
				$sSQL='INSERT INTO '.$sTabla29.' ('.$sCampos3029.') VALUES ('.utf8_encode($sValores3029).');';
				}else{
				$sSQL='INSERT INTO '.$sTabla29.' ('.$sCampos3029.') VALUES ('.$sValores3029.');';
				}
			if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Guardar 3029 '.$sSQL.'<br>';}
			$result=$objDB->ejecutasql($sSQL);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' [3029].<!-- '.$sSQL.' -->';
				}else{
				if ($bAudita[2]){
					seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 2, $saiu29id, $sSQL, $objDB);
					}
				}
			}else{
			$scampo3029[1]='saiu29detalle';
			$svr3029[1]=$saiu29detalle;
			$inumcampos=1;
			$sWhere='saiu29id='.$saiu29id.'';
			//$sWhere='saiu29idsolicitud='.$saiu29idsolicitud.' AND saiu29idanexo='.$saiu29idanexo.' AND saiu29consec='.$saiu29consec.'';
			$sSQL='SELECT * FROM '.$sTabla29.' WHERE '.$sWhere;
			$sdatos='';
			$bpasa=false;
			$result=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($result)>0){
				$filaorigen=$objDB->sf($result);
				for ($k=1;$k<=$inumcampos;$k++){
					if ($filaorigen[$scampo3029[$k]]!=$svr3029[$k]){
						if ($sdatos!=''){$sdatos=$sdatos.', ';}
						$sdatos=$sdatos.$scampo3029[$k].'="'.$svr3029[$k].'"';
						$bpasa=true;
						}
					}
				}
			if ($bpasa){
				if ($APP->utf8==1){
					$sSQL='UPDATE '.$sTabla29.' SET '.utf8_encode($sdatos).' WHERE '.$sWhere.';';
					}else{
					$sSQL='UPDATE '.$sTabla29.' SET '.$sdatos.' WHERE '.$sWhere.';';
					}
				$result=$objDB->ejecutasql($sSQL);
				if ($result==false){
					$sError=$ERR['falla_guardar'].' {Anexos Mesa de ayuda}. <!-- '.$sSQL.' -->';
					}else{
					if ($bAudita[3]){
						seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 3, $saiu29id, $sSQL, $objDB);
						}
					}
				}
			}
		}
	return array($sError, $iAccion, $saiu29id, $sDebug);
	}
function f3029_db_Eliminar($aParametros, $objDB, $bDebug=false){
	$iCodModulo=3029;
	$bAudita[4]=false;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3029='lg/lg_3029_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3029)){$mensajes_3029='lg/lg_3029_es.php';}
	require $mensajes_todas;
	require $mensajes_3029;
	$sError='';
	$sDebug='';
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$saiu29idsolicitud=numeros_validar($aParametros[1]);
	$saiu29idanexo=numeros_validar($aParametros[2]);
	$saiu29consec=numeros_validar($aParametros[3]);
	$saiu29id=numeros_validar($aParametros[4]);
	$iAgno=numeros_validar($aParametros[98]);
	if ($iAgno==''){
		$sError='<b>No se ha definido un a&ntilde;o</b>';
	}else{
		$sTabla29='saiu29anexos_'.$iAgno;
		if (!tabla_existe($sTabla29, $objDB)){
			$sError='No ha sido posible encontrar el origen de datos '.$sTabla29;
		}
	}
	if ($sError==''){
		if (!seg_revisa_permiso($iCodModulo, 4, $objDB)){
			$sError=$ERR['4'];
			}
		}
	if ($sError==''){
		$sSQL='SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=3029';
		$tablaor=$objDB->ejecutasql($sSQL);
		while ($filaor=$objDB->sf($tablaor)){
			$sSQL='SELECT '.$filaor['campoid'].' FROM '.$filaor['tabla'].' WHERE '.$filaor['camporev'].'='.$saiu29id.' LIMIT 0, 1';
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
		$sWhere='saiu29id='.$saiu29id.'';
		//$sWhere='saiu29idsolicitud='.$saiu29idsolicitud.' AND saiu29idanexo='.$saiu29idanexo.' AND saiu29consec='.$saiu29consec.'';
		$sSQL='DELETE FROM '.$sTabla29.' WHERE '.$sWhere.';';
		$result=$objDB->ejecutasql($sSQL);
		if ($result==false){
			$sError=$ERR['falla_eliminar'].' {3029 Anexos Mesa de ayuda}.<!-- '.$sSQL.' -->';
			}else{
			if ($bAudita[4]){
				seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $saiu29id, $sSQL, $objDB);
				}
			}
		}
	return array($sError, $sDebug);
	}
function f3029_TablaDetalleV2($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3029='lg/lg_3029_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3029)){$mensajes_3029='lg/lg_3029_es.php';}
	require $mensajes_todas;
	require $mensajes_3029;
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
		$sTabla29='saiu29anexos_'.$iAgno;
		if (!tabla_existe($sTabla29, $objDB)){
			$sLeyenda='No ha sido posible encontrar el origen de datos '.$sTabla29;
		}
	}
	if ($sLeyenda!=''){
		$sLeyenda='<div class="salto1px"></div>
		<div class="GrupoCamposAyuda">
		'.$sLeyenda.'
		<div class="salto1px"></div>
		</div>';
		return array($sLeyenda.'<input id="paginaf3029" name="paginaf3029" type="hidden" value="'.$pagina.'"/><input id="lppf3029" name="lppf3029" type="hidden" value="'.$lineastabla.'"/>', $sDebug);
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
	$sTitulos='Solicitud, Anexo, Consec, Id, Origen, Archivo, Detalle';
	$sSQL='SELECT TB.saiu29idsolicitud, T2.saiu04titulo, TB.saiu29consec, TB.saiu29id, TB.saiu29idorigen, TB.saiu29idarchivo, TB.saiu29detalle, TB.saiu29idanexo 
	FROM '.$sTabla29.' AS TB, saiu04temaanexo AS T2 
	WHERE '.$sSQLadd1.' TB.saiu29idsolicitud='.$saiu28id.' AND TB.saiu29idanexo=T2.saiu04id '.$sSQLadd.'
	ORDER BY TB.saiu29idanexo, TB.saiu29consec';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_3029" name="consulta_3029" type="hidden" value="'.$sSQLlista.'"/>
	<input id="titulos_3029" name="titulos_3029" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objDB->ejecutasql($sSQL);
	if ($tabladetalle==false){
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 3029: '.$sSQL.'<br>';}
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		//$sLeyenda=$sSQL;
		}else{
		$registros=$objDB->nf($tabladetalle);
		if ($registros==0){
			return array(utf8_encode($sErrConsulta.'<input id="paginaf3029" name="paginaf3029" type="hidden" value="'.$pagina.'"/><input id="lppf3029" name="lppf3029" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
	<td><b>'.$ETI['saiu29idanexo'].'</b></td>
	<td><b>'.$ETI['saiu29consec'].'</b></td>
	<td><b>'.$ETI['saiu29idarchivo'].'</b></td>
	<td><b>'.$ETI['saiu29detalle'].'</b></td>
	<td align="right">
	'.html_paginador('paginaf3029', $registros, $lineastabla, $pagina, 'paginarf3029()').'
	'.html_lpp('lppf3029', $lineastabla, 'paginarf3029()').'
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
		$et_saiu29idanexo=$sPrefijo.cadena_notildes($filadet['saiu04titulo']).$sSufijo;
		$et_saiu29consec=$sPrefijo.$filadet['saiu29consec'].$sSufijo;
		$et_saiu29idarchivo='';
		if ($filadet['saiu29idarchivo']!=0){
			//$et_saiu29idarchivo='<img src="verarchivo.php?cont='.$filadet['saiu29idorigen'].'&id='.$filadet['saiu29idarchivo'].'&maxx=150"/>';
			$et_saiu29idarchivo=html_lnkarchivo((int)$filadet['saiu29idorigen'], (int)$filadet['saiu29idarchivo']);
			}
		$et_saiu29detalle=$sPrefijo.cadena_notildes($filadet['saiu29detalle']).$sSufijo;
		if ($bAbierta){
			$sLink='<a href="javascript:cargaridf3029('.$filadet['saiu29id'].')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$res=$res.'<tr'.$sClass.'>
		<td>'.$et_saiu29idanexo.'</td>
		<td>'.$et_saiu29consec.'</td>
		<td>'.$et_saiu29idarchivo.'</td>
		<td>'.$et_saiu29detalle.'</td>
		<td>'.$sLink.'</td>
		</tr>';
		}
	$res=$res.'</table>';
	$objDB->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
	}
// -- 3029 Anexos Mesa de ayuda XAJAX 
function elimina_archivo_saiu29idarchivo($idPadre, $iAgno, $bDebug=false){
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
		$sTabla29='saiu29anexos_'.$iAgno;
		archivo_eliminar($sTabla29, 'saiu29id', 'saiu29idorigen', 'saiu29idarchivo', $idPadre, $objDB);
		}
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	if ($bPuedeEliminar){
		$objResponse->call("limpia_saiu29idarchivo");
		$objResponse->call("paginarf3209()");
		}else{
		$objResponse->call("MensajeAlarmaV2('".$sError."', 0)");
		}
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f3029_Guardar($valores, $aParametros){
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
		list($sError, $iAccion, $saiu29id, $sDebugGuardar)=f3029_db_Guardar($valores, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugGuardar;
		}
	$objResponse=new xajaxResponse();
	if ($sError==''){
		list($sdetalle, $sDebugTabla)=f3029_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugTabla;
		$objResponse->assign('div_f3029detalle', 'innerHTML', $sdetalle);
		//if ($iAccion==2){
			//$objResponse->call('cargaridf3029('.$saiu29id.')');
			//}else{
			$objResponse->call('limpiaf3029');
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
function f3029_Traer($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$sError='';
	$bHayDb=false;
	$besta=false;
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	if(isset($aParametros[98])==0){$aParametros[98]='';}
	$paso=$aParametros[0];
	$iAgno=numeros_validar($aParametros[98]);
	if ($iAgno==''){
		$sError='<b>No se ha definido un a&ntilde;o</b>';
	}else{
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		$bHayDb=true;
		$sTabla29='saiu29anexos_'.$iAgno;
		if (!tabla_existe($sTabla29, $objDB)){
			$sError='No ha sido posible encontrar el origen de datos '.$sTabla29;
		}
	}
	if ($paso==1){
		$saiu29idsolicitud=numeros_validar($aParametros[1]);
		$saiu29idanexo=numeros_validar($aParametros[2]);
		$saiu29consec=numeros_validar($aParametros[3]);
		if (($saiu29idsolicitud!='')&&($saiu29idanexo!='')&&($saiu29consec!='')){$besta=true;}
		}else{
		$saiu29id=$aParametros[103];
		if ((int)$saiu29id!=0){$besta=true;}
		}
	if ($besta){
		$besta=false;
		$sSQLcondi='';
		if ($paso==1){
			$sSQLcondi=$sSQLcondi.'saiu29idsolicitud='.$saiu29idsolicitud.' AND saiu29idanexo='.$saiu29idanexo.' AND saiu29consec='.$saiu29consec.'';
			}else{
			$sSQLcondi=$sSQLcondi.'saiu29id='.$saiu29id.'';
			}
		$sSQL='SELECT * FROM '.$sTabla29.' WHERE '.$sSQLcondi;
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
		list($saiu29idanexo_nombre, $serror_det)=tabla_campoxid('saiu04temaanexo','saiu04titulo','saiu04id', $fila['saiu29idanexo'],'{'.$ETI['msg_sindato'].'}', $objDB);
		$html_saiu29idanexo=html_oculto('saiu29idanexo', $fila['saiu29idanexo'], $saiu29idanexo_nombre);
		$objResponse->assign('div_saiu29idanexo', 'innerHTML', $html_saiu29idanexo);
		$saiu29consec_nombre='';
		$html_saiu29consec=html_oculto('saiu29consec', $fila['saiu29consec'], $saiu29consec_nombre);
		$objResponse->assign('div_saiu29consec', 'innerHTML', $html_saiu29consec);
		$saiu29id_nombre='';
		$html_saiu29id=html_oculto('saiu29id', $fila['saiu29id'], $saiu29id_nombre);
		$objResponse->assign('div_saiu29id', 'innerHTML', $html_saiu29id);
		$objResponse->assign('saiu29idorigen', 'value', $fila['saiu29idorigen']);
		$idorigen=(int)$fila['saiu29idorigen'];
		$objResponse->assign('saiu29idarchivo', 'value', $fila['saiu29idarchivo']);
		$sMuestraAnexar='block';
		$sMuestraEliminar='none';
		$sHTMLArchivo=html_lnkarchivo($idorigen, (int)$fila['saiu29idarchivo']);
		if ((int)$fila['saiu29idarchivo']!=0){
			$sMuestraEliminar='block';
			//Aqui puede poner validaciones al eliminar - Si no se puede eliminar reversar el mostrado.
			}
		$objResponse->assign('div_saiu29idarchivo', 'innerHTML', $sHTMLArchivo);
		$objResponse->call("verboton('banexasaiu29idarchivo', '".$sMuestraAnexar."')");
		$objResponse->call("verboton('beliminasaiu29idarchivo','".$sMuestraEliminar."')");
		$objResponse->assign('saiu29detalle', 'value', $fila['saiu29detalle']);
		$objResponse->call("MensajeAlarmaV2('', 0)");
		$objResponse->call("verboton('belimina3029','block')");
		}else{
		if ($paso==1){
			$objResponse->assign('saiu29idanexo', 'value', $saiu29idanexo);
			$objResponse->assign('saiu29consec', 'value', $saiu29consec);
			}else{
			$objResponse->call('MensajeAlarmaV2("No se encontro el registro de referencia:'.$saiu29id.'", 0)');
			}
		}
	if ($bHayDb){
		$objDB->CerrarConexion();
		}
	return $objResponse;
	}
function f3029_Eliminar($aParametros){
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
	list($sError, $sDebugElimina)=f3029_db_Eliminar($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugElimina;
	$objResponse=new xajaxResponse();
	if ($sError==''){
		list($sDetalle, $sDebugTabla)=f3029_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugTabla;
		$objResponse->assign('div_f3029detalle', 'innerHTML', $sDetalle);
		$objResponse->call('limpiaf3029');
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
function f3029_HtmlTabla($aParametros){
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
	list($sDetalle, $sDebugTabla)=f3029_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f3029detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f3029_PintarLlaves($aParametros){
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
	$html_saiu29idanexo=f3029_HTMLComboV2_saiu29idanexo($objDB, $objCombos, 0);
	$html_saiu29consec='<input id="saiu29consec" name="saiu29consec" type="text" value="" onchange="revisaf3029()" class="cuatro"/>';
	$html_saiu29id='<input id="saiu29id" name="saiu29id" type="hidden" value=""/>';
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_saiu29idanexo','innerHTML', $html_saiu29idanexo);
	$objResponse->assign('div_saiu29consec','innerHTML', $html_saiu29consec);
	$objResponse->assign('div_saiu29id','innerHTML', $html_saiu29id);
	return $objResponse;
	}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------
?>