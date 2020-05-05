<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2020 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.24.1 lunes, 24 de febrero de 2020
--- 1770 Recursos usados
*/
function f1770_db_Guardar($valores, $objDB, $bDebug=false){
	$iCodModulo=1770;
	$bAudita[2]=false;
	$bAudita[3]=false;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_1770=$APP->rutacomun.'lg/lg_1770_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1770)){$mensajes_1770=$APP->rutacomun.'lg/lg_1770_es.php';}
	require $mensajes_todas;
	require $mensajes_1770;
	$sError='';
	$sDebug='';
	$bInserta=false;
	$iAccion=3;
	if(!is_array($valores)){$valores=json_decode(str_replace('\"','"',$valores),true);}
	$ofer70idcurso=numeros_validar($valores[1]);
	$ofer70consec=numeros_validar($valores[2]);
	$ofer70id=numeros_validar($valores[3], true);
	$ofer70idtiporecurso=numeros_validar($valores[4]);
	$ofer70detalle=htmlspecialchars(trim($valores[5]));
	//if ($ofer70idtiporecurso==''){$ofer70idtiporecurso=0;}
	$sSepara=', ';
	if ($ofer70detalle==''){$sError=$ERR['ofer70detalle'].$sSepara.$sError;}
	if ($ofer70idtiporecurso==''){$sError=$ERR['ofer70idtiporecurso'].$sSepara.$sError;}
	//if ($ofer70id==''){$sError=$ERR['ofer70id'].$sSepara.$sError;}//CONSECUTIVO
	//if ($ofer70consec==''){$sError=$ERR['ofer70consec'].$sSepara.$sError;}//CONSECUTIVO
	if ($ofer70idcurso==''){$sError=$ERR['ofer70idcurso'].$sSepara.$sError;}
	if ($sError==''){
		if ((int)$ofer70id==0){
			if ((int)$ofer70consec==0){
				$ofer70consec=tabla_consecutivo('ofer70cursorecurso', 'ofer70consec', 'ofer70idcurso='.$ofer70idcurso.'', $objDB);
				if ($ofer70consec==-1){$sError=$objDB->serror;}
				}else{
				if (!seg_revisa_permiso($iCodModulo, 8, $objDB)){$sError=$ERR['8'];}
				}
			if ($sError==''){
				$sSQL='SELECT ofer70idcurso FROM ofer70cursorecurso WHERE ofer70idcurso='.$ofer70idcurso.' AND ofer70consec='.$ofer70consec.'';
				$result=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($result)!=0){
					$sError=$ERR['existe'];
					}else{
					if (!seg_revisa_permiso($iCodModulo, 2, $objDB)){$sError=$ERR['2'];}
					}
				}
			if ($sError==''){
				$ofer70id=tabla_consecutivo('ofer70cursorecurso', 'ofer70id', '', $objDB);
				if ($ofer70id==-1){$sError=$objDB->serror;}
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
		//Si el campo ofer70detalle permite html quite la linea htmlspecialchars para el campo y habilite la siguiente linea:
		//$ofer70detalle=str_replace('"', '\"', $ofer70detalle);
		$ofer70detalle=str_replace('"', '\"', $ofer70detalle);
		if ($bInserta){
			$sCampos140='ofer70idcurso, ofer70consec, ofer70id, ofer70idtiporecurso, ofer70detalle';
			$sValores140=''.$ofer70idcurso.', '.$ofer70consec.', '.$ofer70id.', '.$ofer70idtiporecurso.', "'.$ofer70detalle.'"';
			if ($APP->utf8==1){
				$sSQL='INSERT INTO ofer70cursorecurso ('.$sCampos140.') VALUES ('.utf8_encode($sValores140).');';
				}else{
				$sSQL='INSERT INTO ofer70cursorecurso ('.$sCampos140.') VALUES ('.$sValores140.');';
				}
			$result=$objDB->ejecutasql($sSQL);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' {Recursos usados}.<!-- '.$sSQL.' -->';
				}else{
				if ($bAudita[2]){
					seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 2, $ofer70id, $sSQL, $objDB);
					}
				}
			}else{
			$scampo1770[1]='ofer70idtiporecurso';
			$scampo1770[2]='ofer70detalle';
			$svr1770[1]=$ofer70idtiporecurso;
			$svr1770[2]=$ofer70detalle;
			$inumcampos=2;
			$sWhere='ofer70id='.$ofer70id.'';
			//$sWhere='ofer70idcurso='.$ofer70idcurso.' AND ofer70consec='.$ofer70consec.'';
			$sSQL='SELECT * FROM ofer70cursorecurso WHERE '.$sWhere;
			$sdatos='';
			$bpasa=false;
			$result=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($result)>0){
				$filaorigen=$objDB->sf($result);
				for ($k=1;$k<=$inumcampos;$k++){
					if ($filaorigen[$scampo1770[$k]]!=$svr1770[$k]){
						if ($sdatos!=''){$sdatos=$sdatos.', ';}
						$sdatos=$sdatos.$scampo1770[$k].'="'.$svr1770[$k].'"';
						$bpasa=true;
						}
					}
				}
			if ($bpasa){
				if ($APP->utf8==1){
					$sSQL='UPDATE ofer70cursorecurso SET '.utf8_encode($sdatos).' WHERE '.$sWhere.';';
					}else{
					$sSQL='UPDATE ofer70cursorecurso SET '.$sdatos.' WHERE '.$sWhere.';';
					}
				$result=$objDB->ejecutasql($sSQL);
				if ($result==false){
					$sError=$ERR['falla_guardar'].' {Recursos usados}. <!-- '.$sSQL.' -->';
					}else{
					if ($bAudita[3]){
						seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 3, $ofer70id, $sSQL, $objDB);
						}
					}
				}
			}
		}
	return array($sError, $iAccion, $ofer70id, $sDebug);
	}
function f1770_db_Eliminar($aParametros, $objDB, $bDebug=false){
	$iCodModulo=1770;
	$bAudita[4]=false;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_1770=$APP->rutacomun.'lg/lg_1770_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1770)){$mensajes_1770=$APP->rutacomun.'lg/lg_1770_es.php';}
	require $mensajes_todas;
	require $mensajes_1770;
	$sError='';
	$sDebug='';
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$ofer70idcurso=numeros_validar($aParametros[1]);
	$ofer70consec=numeros_validar($aParametros[2]);
	$ofer70id=numeros_validar($aParametros[3]);
	if ($sError==''){
		if (!seg_revisa_permiso($iCodModulo, 4, $objDB)){
			$sError=$ERR['4'];
			}
		}
	if ($sError==''){
		$sSQL='SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=1770';
		$tablaor=$objDB->ejecutasql($sSQL);
		while ($filaor=$objDB->sf($tablaor)){
			$sSQL='SELECT '.$filaor['campoid'].' FROM '.$filaor['tabla'].' WHERE '.$filaor['camporev'].'='.$ofer70id.' LIMIT 0, 1';
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
		$sWhere='ofer70id='.$ofer70id.'';
		//$sWhere='ofer70idcurso='.$ofer70idcurso.' AND ofer70consec='.$ofer70consec.'';
		$sSQL='DELETE FROM ofer70cursorecurso WHERE '.$sWhere.';';
		$result=$objDB->ejecutasql($sSQL);
		if ($result==false){
			$sError=$ERR['falla_eliminar'].' {1770 Recursos usados}.<!-- '.$sSQL.' -->';
			}else{
			if ($bAudita[4]){
				seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $ofer70id, $sSQL, $objDB);
				}
			}
		}
	return array($sError, $sDebug);
	}
function f1770_TablaDetalleV2($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_1770=$APP->rutacomun.'lg/lg_1770_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1770)){$mensajes_1770=$APP->rutacomun.'lg/lg_1770_es.php';}
	require $mensajes_todas;
	require $mensajes_1770;
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
	$sDebug='';
	$unad40id=$aParametros[0];
	$idTercero=$aParametros[100];
	$pagina=$aParametros[101];
	$lineastabla=$aParametros[102];
	$bAbierta=true;
	//$sSQL='SELECT Campo FROM unad40curso WHERE unad40id='.$unad40id;
	//$tabla=$objDB->ejecutasql($sSQL);
	//if ($objDB->nf($tabla)>0){
		//$fila=$objDB->sf($tabla);
		//if ($fila['Campo']!='S'){$bAbierta=true;}
		//}
	$sSQLadd='';
	$sSQLadd1='';
	$sLeyenda='';
	if ($sLeyenda!=''){
		$sLeyenda='<div class="salto1px"></div>
<div class="GrupoCamposAyuda">
'.$sLeyenda.'
<div class="salto1px"></div>
</div>';
		}
/*
	$aEstado=array();
	$sSQL='SELECT id, nombre FROM tabla';
	$tabla=$objDB->ejecutasql($sSQL);
	while($fila=$objDB->sf($tabla)){
		$aEstado[$fila['id']]=cadena_notildes($fila['nombre']);
		}
*/
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
	$sTitulos='Curso, Consec, Id, Tiporecurso, Detalle';
	$sSQL='SELECT TB.ofer70idcurso, TB.ofer70consec, TB.ofer70id, T4.ofer71nombre, TB.ofer70detalle, TB.ofer70idtiporecurso 
FROM ofer70cursorecurso AS TB, ofer71tiporecurso AS T4 
WHERE '.$sSQLadd1.' TB.ofer70idcurso='.$unad40id.' AND TB.ofer70idtiporecurso=T4.ofer71id '.$sSQLadd.'
ORDER BY TB.ofer70consec';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_1770" name="consulta_1770" type="hidden" value="'.$sSQLlista.'"/>
<input id="titulos_1770" name="titulos_1770" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objDB->ejecutasql($sSQL);
	if ($tabladetalle==false){
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 1770: '.$sSQL.'<br>';}
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		//$sLeyenda=$sSQL;
		}else{
		$registros=$objDB->nf($tabladetalle);
		if ($registros==0){
			return array(utf8_encode($sErrConsulta.'<input id="paginaf1770" name="paginaf1770" type="hidden" value="'.$pagina.'"/><input id="lppf1770" name="lppf1770" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
<td><b>'.$ETI['ofer70consec'].'</b></td>
<td><b>'.$ETI['ofer70idtiporecurso'].'</b></td>
<td><b>'.$ETI['ofer70detalle'].'</b></td>
<td align="right">
'.html_paginador('paginaf1770', $registros, $lineastabla, $pagina, 'paginarf1770()').'
'.html_lpp('lppf1770', $lineastabla, 'paginarf1770()').'
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
		$et_ofer70consec=$sPrefijo.$filadet['ofer70consec'].$sSufijo;
		$et_ofer70idtiporecurso=$sPrefijo.cadena_notildes($filadet['ofer71nombre']).$sSufijo;
		$et_ofer70detalle=$sPrefijo.cadena_notildes($filadet['ofer70detalle']).$sSufijo;
		if ($bAbierta){
			$sLink='<a href="javascript:cargaridf1770('.$filadet['ofer70id'].')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$res=$res.'<tr'.$sClass.'>
<td>'.$et_ofer70consec.'</td>
<td>'.$et_ofer70idtiporecurso.'</td>
<td>'.$et_ofer70detalle.'</td>
<td>'.$sLink.'</td>
</tr>';
		}
	$res=$res.'</table>';
	$objDB->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
	}
function f1770_Clonar($ofer70idcurso, $ofer70idcursoPadre, $objDB){
	$sError='';
	$ofer70consec=tabla_consecutivo('ofer70cursorecurso', 'ofer70consec', 'ofer70idcurso='.$ofer70idcurso.'', $objDB);
	if ($ofer70consec==-1){$sError=$objDB->serror;}
	$ofer70id=tabla_consecutivo('ofer70cursorecurso', 'ofer70id', '', $objDB);
	if ($ofer70id==-1){$sError=$objDB->serror;}
	if ($sError==''){
		$sCampos1770='ofer70idcurso, ofer70consec, ofer70id, ofer70idtiporecurso, ofer70detalle';
		$sValores1770='';
		$sSQL='SELECT * FROM ofer70cursorecurso WHERE ofer70idcurso='.$ofer70idcursoPadre.'';
		$tabla=$objDB->ejecutasql($sSQL);
		while($fila=$objDB->sf($tabla)){
			if ($sValores1770!=''){$sValores1770=$sValores1770.', ';}
			$sValores1770=$sValores1770.'('.$ofer70idcurso.', '.$ofer70consec.', '.$ofer70id.', '.$fila['ofer70idtiporecurso'].', "'.$fila['ofer70detalle'].'")';
			$ofer70consec++;
			$ofer70id++;
			}
		if ($sValores1770!=''){
			$sSQL='INSERT INTO ofer70cursorecurso('.$sCampos1770.') VALUES '.$sValores1770.'';
			$result=$objDB->ejecutasql($sSQL);
			}
		}
	return $sError;
	}
// -- 1770 Recursos usados XAJAX 
function f1770_Guardar($valores, $aParametros){
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
		list($sError, $iAccion, $ofer70id, $sDebugGuardar)=f1770_db_Guardar($valores, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugGuardar;
		}
	$objResponse=new xajaxResponse();
	if ($sError==''){
		list($sdetalle, $sDebugTabla)=f1770_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugTabla;
		$objResponse->assign('div_f1770detalle', 'innerHTML', $sdetalle);
		//if ($iAccion==2){
			//$objResponse->call('cargaridf1770('.$ofer70id.')');
			//}else{
			$objResponse->call('limpiaf1770');
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
function f1770_Traer($aParametros){
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
		$ofer70idcurso=numeros_validar($aParametros[1]);
		$ofer70consec=numeros_validar($aParametros[2]);
		if (($ofer70idcurso!='')&&($ofer70consec!='')){$besta=true;}
		}else{
		$ofer70id=$aParametros[103];
		if ((int)$ofer70id!=0){$besta=true;}
		}
	if ($besta){
		$besta=false;
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		$bHayDb=true;
		$sSQLcondi='';
		if ($paso==1){
			$sSQLcondi=$sSQLcondi.'ofer70idcurso='.$ofer70idcurso.' AND ofer70consec='.$ofer70consec.'';
			}else{
			$sSQLcondi=$sSQLcondi.'ofer70id='.$ofer70id.'';
			}
		$sSQL='SELECT * FROM ofer70cursorecurso WHERE '.$sSQLcondi;
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
		$ofer70consec_nombre='';
		$html_ofer70consec=html_oculto('ofer70consec', $fila['ofer70consec'], $ofer70consec_nombre);
		$objResponse->assign('div_ofer70consec', 'innerHTML', $html_ofer70consec);
		$ofer70id_nombre='';
		$html_ofer70id=html_oculto('ofer70id', $fila['ofer70id'], $ofer70id_nombre);
		$objResponse->assign('div_ofer70id', 'innerHTML', $html_ofer70id);
		$objResponse->assign('ofer70idtiporecurso', 'value', $fila['ofer70idtiporecurso']);
		$objResponse->assign('ofer70detalle', 'value', $fila['ofer70detalle']);
		$objResponse->call("MensajeAlarmaV2('', 0)");
		$objResponse->call("verboton('belimina1770','block')");
		}else{
		if ($paso==1){
			$objResponse->assign('ofer70consec', 'value', $ofer70consec);
			}else{
			$objResponse->call('MensajeAlarmaV2("No se encontro el registro de referencia:'.$ofer70id.'", 0)');
			}
		}
	if ($bHayDb){
		$objDB->CerrarConexion();
		}
	return $objResponse;
	}
function f1770_Eliminar($aParametros){
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
	list($sError, $sDebugElimina)=f1770_db_Eliminar($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugElimina;
	$objResponse=new xajaxResponse();
	if ($sError==''){
		list($sDetalle, $sDebugTabla)=f1770_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugTabla;
		$objResponse->assign('div_f1770detalle', 'innerHTML', $sDetalle);
		$objResponse->call('limpiaf1770');
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
function f1770_HtmlTabla($aParametros){
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
	list($sDetalle, $sDebugTabla)=f1770_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f1770detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f1770_PintarLlaves($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	//if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	if (isset($APP->piel)==0){$APP->piel=1;}
	$iPiel=$APP->piel;
	$html_ofer70consec='<input id="ofer70consec" name="ofer70consec" type="text" value="" onchange="revisaf1770()" class="cuatro"/>';
	$html_ofer70id='<input id="ofer70id" name="ofer70id" type="hidden" value=""/>';
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_ofer70consec','innerHTML', $html_ofer70consec);
	$objResponse->assign('div_ofer70id','innerHTML', $html_ofer70id);
	return $objResponse;
	}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------
?>