<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2016 - 2019 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.12.0 viernes, 15 de enero de 2016
--- Modelo Versión 2.15.8 lunes, 24 de octubre de 2016
--- Modelo Versión 2.22.7 viernes, 22 de marzo de 2019
--- 1505 Anotaciones
*/
function f1505_db_Guardar($valores, $objDB, $bDebug=false){
	$iCodModulo=1505;
	$bAudita[2]=false;
	$bAudita[3]=false;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_1505=$APP->rutacomun.'lg/lg_1505_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1505)){$mensajes_1505=$APP->rutacomun.'lg/lg_1505_es.php';}
	require $mensajes_todas;
	require $mensajes_1505;
	$sError='';
	$sDebug='';
	$binserta=false;
	$iAccion=3;
	if(!is_array($valores)){$valores=json_decode(str_replace('\"','"',$valores),true);}
	$bita05idbitacora=numeros_validar($valores[1]);
	$bita05consec=numeros_validar($valores[2]);
	$bita05id=numeros_validar($valores[3], true);
	$bita05fecha=$valores[4];
	$bita05hora=numeros_validar($valores[5]);
	$bita05minuto=numeros_validar($valores[6]);
	$bita05idtercero=numeros_validar($valores[7]);
	$bita05contenido=htmlspecialchars(trim($valores[8]));
	$bita05descartada=htmlspecialchars(trim($valores[9]));
	$bita05visiblesolicitante=htmlspecialchars(trim($valores[10]));
	//if ($bita05hora==''){$bita05hora=0;}
	//if ($bita05minuto==''){$bita05minuto=0;}
	$sSepara=', ';
	if ($bita05visiblesolicitante==''){$sError=$ERR['bita05visiblesolicitante'].$sSepara.$sError;}
	if ($bita05descartada==''){$sError=$ERR['bita05descartada'].$sSepara.$sError;}
	if ($bita05contenido==''){$sError=$ERR['bita05contenido'].$sSepara.$sError;}
	if ($bita05idtercero==0){$sError=$ERR['bita05idtercero'].$sSepara.$sError;}
	if ($bita05minuto==''){$sError=$ERR['bita05minuto'].$sSepara.$sError;}
	if ($bita05hora==''){$sError=$ERR['bita05hora'].$sSepara.$sError;}
	if (!fecha_esvalida($bita05fecha)){
		//$bita05fecha='00/00/0000';
		$sError=$ERR['bita05fecha'].$sSepara.$sError;
		}
	//if ($bita05id==''){$sError=$ERR['bita05id'].$sSepara.$sError;}//CONSECUTIVO
	//if ($bita05consec==''){$sError=$ERR['bita05consec'].$sSepara.$sError;}//CONSECUTIVO
	if ($bita05idbitacora==''){$sError=$ERR['bita05idbitacora'].$sSepara.$sError;}
	if ($sError==''){
		list($sError, $sInfo)=tercero_Bloqueado($bita05idtercero, $objDB);
		if ($sInfo!=''){$sError=$sError.'<br>'.sInfo;}
		}
	if ($sError==''){
		if ((int)$bita05id==0){
			if ((int)$bita05consec==0){
				$bita05consec=tabla_consecutivo('bita05bitacoranota', 'bita05consec', 'bita05idbitacora='.$bita05idbitacora.'', $objDB);
				if ($bita05consec==-1){$sError=$objDB->serror;}
				}else{
				if (!seg_revisa_permiso($iCodModulo, 8, $objDB)){$sError=$ERR['8'];}
				}
			if ($sError==''){
				$sSQL='SELECT bita05idbitacora FROM bita05bitacoranota WHERE bita05idbitacora='.$bita05idbitacora.' AND bita05consec='.$bita05consec.'';
				$result=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($result)!=0){
					$sError=$ERR['existe'];
					}else{
					if (!seg_revisa_permiso($iCodModulo, 2, $objDB)){$sError=$ERR['2'];}
					}
				}
			if ($sError==''){
				$bita05id=tabla_consecutivo('bita05bitacoranota', 'bita05id', '', $objDB);
				if ($bita05id==-1){$sError=$objDB->serror;}
				$binserta=true;
				$iAccion=2;
				}
			}else{
			if (!seg_revisa_permiso($iCodModulo, 3, $objDB)){$sError=$ERR['3'];}
			}
		}
	if ($sError==''){
		if ($binserta){
			$bita05idorigen=0;
			$bita05idarchivo=0;
			}
		}
	if ($sError==''){
		//Si el campo bita05contenido permite html quite la linea htmlspecialchars para el campo y habilite la siguiente linea:
		//$bita05contenido=str_replace('"', '\"', $bita05contenido);
		$bita05contenido=str_replace('"', '\"', $bita05contenido);
		if ($binserta){
			$scampos='bita05idbitacora, bita05consec, bita05id, bita05fecha, bita05hora, 
bita05minuto, bita05idtercero, bita05contenido, bita05descartada, bita05visiblesolicitante, 
bita05idorigen, bita05idarchivo';
			$svalores=''.$bita05idbitacora.', '.$bita05consec.', '.$bita05id.', "'.$bita05fecha.'", '.$bita05hora.', 
'.$bita05minuto.', "'.$bita05idtercero.'", "'.$bita05contenido.'", "'.$bita05descartada.'", "'.$bita05visiblesolicitante.'", 
0, 0';
			if ($APP->utf8==1){
				$sSQL='INSERT INTO bita05bitacoranota ('.$scampos.') VALUES ('.utf8_encode($svalores).');';
				}else{
				$sSQL='INSERT INTO bita05bitacoranota ('.$scampos.') VALUES ('.$svalores.');';
				}
			$result=$objDB->ejecutasql($sSQL);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' {Anotaciones}.<!-- '.$sSQL.' -->';
				}else{
				if ($bAudita[2]){
					seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 2, $bita05id, $sSQL, $objDB);
					}
				}
			}else{
			$scampo1505[1]='bita05fecha';
			$scampo1505[2]='bita05hora';
			$scampo1505[3]='bita05minuto';
			$scampo1505[4]='bita05idtercero';
			$scampo1505[5]='bita05contenido';
			$scampo1505[6]='bita05descartada';
			$scampo1505[7]='bita05visiblesolicitante';
			$svr1505[1]=$bita05fecha;
			$svr1505[2]=$bita05hora;
			$svr1505[3]=$bita05minuto;
			$svr1505[4]=$bita05idtercero;
			$svr1505[5]=$bita05contenido;
			$svr1505[6]=$bita05descartada;
			$svr1505[7]=$bita05visiblesolicitante;
			$inumcampos=7;
			$sWhere='bita05id='.$bita05id.'';
			//$sWhere='bita05idbitacora='.$bita05idbitacora.' AND bita05consec='.$bita05consec.'';
			$sSQL='SELECT * FROM bita05bitacoranota WHERE '.$sWhere;
			$sdatos='';
			$bpasa=false;
			$result=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($result)>0){
				$filaorigen=$objDB->sf($result);
				for ($k=1;$k<=$inumcampos;$k++){
					if ($filaorigen[$scampo1505[$k]]!=$svr1505[$k]){
						if ($sdatos!=''){$sdatos=$sdatos.', ';}
						$sdatos=$sdatos.$scampo1505[$k].'="'.$svr1505[$k].'"';
						$bpasa=true;
						}
					}
				}
			if ($bpasa){
				if ($APP->utf8==1){
					$sSQL='UPDATE bita05bitacoranota SET '.utf8_encode($sdatos).' WHERE '.$sWhere.';';
					}else{
					$sSQL='UPDATE bita05bitacoranota SET '.$sdatos.' WHERE '.$sWhere.';';
					}
				$result=$objDB->ejecutasql($sSQL);
				if ($result==false){
					$sError=$ERR['falla_guardar'].' {Anotaciones}. <!-- '.$sSQL.' -->';
					}else{
					if ($bAudita[3]){
						seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 3, $bita05id, $sSQL, $objDB);
						}
					}
				}
			}
		}
	return array($sError, $iAccion, $bita05id, $sDebug);
	}
function f1505_db_Eliminar($aParametros, $objDB, $bDebug=false){
	$iCodModulo=1505;
	$bAudita[4]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_1505=$APP->rutacomun.'lg/lg_1505_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1505)){$mensajes_1505=$APP->rutacomun.'lg/lg_1505_es.php';}
	require $mensajes_todas;
	require $mensajes_1505;
	$sError='';
	$sDebug='';
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$bita05idbitacora=numeros_validar($aParametros[1]);
	$bita05consec=numeros_validar($aParametros[2]);
	$bita05id=numeros_validar($aParametros[3]);
	if ($sError==''){
		if (!seg_revisa_permiso($iCodModulo, 4, $objDB)){
			$sError=$ERR['4'];
			}
		}
	if ($sError==''){
		$sSQL='SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=1505';
		$tablaor=$objDB->ejecutasql($sSQL);
		while ($filaor=$objDB->sf($tablaor)){
			$sSQL='SELECT '.$filaor['campoid'].' FROM '.$filaor['tabla'].' WHERE '.$filaor['camporev'].'='.$bita05id.' LIMIT 0, 1';
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
		$sWhere='bita05id='.$bita05id.'';
		//$sWhere='bita05idbitacora='.$bita05idbitacora.' AND bita05consec='.$bita05consec.'';
		$sSQL='DELETE FROM bita05bitacoranota WHERE '.$sWhere.';';
		$result=$objDB->ejecutasql($sSQL);
		if ($result==false){
			$sError=$ERR['falla_eliminar'].' {1505 Anotaciones}.<!-- '.$sSQL.' -->';
			}else{
			if ($bAudita[4]){
				seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $bita05id, $sSQL, $objDB);
				}
			}
		}
	return array($sError, $sDebug);
	}
function f1505_TablaDetalleV2($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_1505=$APP->rutacomun.'lg/lg_1505_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1505)){$mensajes_1505=$APP->rutacomun.'lg/lg_1505_es.php';}
	require $mensajes_todas;
	require $mensajes_1505;
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	if (isset($aParametros[0])==0){$aParametros[0]=-1;}
	if (isset($aParametros[101])==0){$aParametros[101]=1;}
	if (isset($aParametros[102])==0){$aParametros[102]=20;}
	//if (isset($aParametros[103])==0){$aParametros[103]='';}
	//$aParametros[103]=numeros_validar($aParametros[103]);
	$aParametros[0]=numeros_validar($aParametros[0]);
	if ($aParametros[0]==''){$aParametros[0]=-1;}
	$sDebug='';
	$bita04id=$aParametros[0];
	$pagina=$aParametros[101];
	$lineastabla=$aParametros[102];
	$babierta=false;
	$sql='SELECT bita04estado FROM bita04bitacora WHERE bita04id='.$bita04id;
	$tabla=$objDB->ejecutasql($sql);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		if ($fila['bita04estado']==0){$babierta=true;}
		if ($fila['bita04estado']==1){$babierta=true;}
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
	$sTitulos='Bitacora, Consec, Id, Fecha, Hora, Minuto, Tercero, Contenido, Descartada, Visiblesolicitante, Origen, Archivo';
	$sSQL='SELECT TB.bita05idbitacora, TB.bita05consec, TB.bita05id, TB.bita05fecha, TB.bita05hora, TB.bita05minuto, T7.unad11razonsocial AS C7_nombre, TB.bita05contenido, TB.bita05descartada, TB.bita05visiblesolicitante, TB.bita05idorigen, TB.bita05idarchivo, TB.bita05idtercero, T7.unad11tipodoc AS C7_td, T7.unad11doc AS C7_doc 
FROM bita05bitacoranota AS TB, unad11terceros AS T7 
WHERE '.$sSQLadd1.' TB.bita05idbitacora='.$bita04id.' AND TB.bita05idtercero=T7.unad11id '.$sSQLadd.'
ORDER BY TB.bita05consec';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_1505" name="consulta_1505" type="hidden" value="'.$sSQLlista.'"/>
<input id="titulos_1505" name="titulos_1505" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objDB->ejecutasql($sSQL);
	if ($tabladetalle==false){
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 1505: '.$sSQL.'<br>';}
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		//$sLeyenda=$sSQL;
		}else{
		$registros=$objDB->nf($tabladetalle);
		if ($registros==0){
			return array(utf8_encode($sErrConsulta.'<input id="paginaf1505" name="paginaf1505" type="hidden" value="'.$pagina.'"/><input id="lppf1505" name="lppf1505" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
<td><b>'.$ETI['bita05consec'].'</b></td>
<td colspan="2"><b>'.$ETI['bita05fecha'].'</b></td>
<td colspan="2"><b>'.$ETI['bita05idtercero'].'</b></td>
<td><b>'.$ETI['bita05contenido'].'</b></td>
<td><b>'.$ETI['bita05descartada'].'</b></td>
<td><b>'.$ETI['bita05visiblesolicitante'].'</b></td>
<td><b>'.$ETI['bita05idarchivo'].'</b></td>
<td align="right">
'.html_paginador('paginaf1505', $registros, $lineastabla, $pagina, 'paginarf1505()').'
'.html_lpp('lppf1505', $lineastabla, 'paginarf1505()').'
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
		$eti_bita05descartada=$ETI['no'];
		if ($filadet['bita05descartada']=='S'){$eti_bita05descartada=$ETI['si'];}
		$et_bita05visiblesolicitante=$ETI['no'];
		if ($filadet['bita05visiblesolicitante']=='S'){$et_bita05visiblesolicitante=$ETI['si'];}
		$et_bita05idarchivo='';
		if ($filadet['bita05idarchivo']!=0){
			//$et_bita05idarchivo='<img src="verarchivo.php?cont='.$filadet['bita05idorigen'].'&id='.$filadet['bita05idarchivo'].'&maxx=150"/>';
			$et_bita05idarchivo=html_lnkarchivo((int)$filadet['bita05idorigen'], (int)$filadet['bita05idarchivo']);
			}
		if ($babierta){
			$sLink='<a href="javascript:cargaridf1505('.$filadet['bita05id'].')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$res=$res.'<tr'.$sClass.'>
<td>'.$sPrefijo.$filadet['bita05consec'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['bita05fecha'].$sSufijo.'</td>
<td>'.formato_hora($filadet['bita05hora']).':'.formato_hora($filadet['bita05minuto']).'</td>
<td>'.$sPrefijo.$filadet['C7_td'].' '.$filadet['C7_doc'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['C7_nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['bita05contenido'].$sSufijo.'</td>
<td>'.$sPrefijo.$eti_bita05descartada.$sSufijo.'</td>
<td>'.$et_bita05visiblesolicitante.'</td>
<td>'.$et_bita05idarchivo.'</td>
<td>'.$sLink.'</td>
</tr>';
		}
	$res=$res.'</table>';
	$objDB->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
	}
function f1505_Clonar($bita05idbitacora, $bita05idbitacoraPadre, $objDB){
	$sError='';
	$bita05consec=tabla_consecutivo('bita05bitacoranota', 'bita05consec', 'bita05idbitacora='.$bita05idbitacora.'', $objDB);
	if ($bita05consec==-1){$sError=$objDB->serror;}
	$bita05id=tabla_consecutivo('bita05bitacoranota', 'bita05id', '', $objDB);
	if ($bita05id==-1){$sError=$objDB->serror;}
	if ($sError==''){
		$sCampos1505='bita05idbitacora, bita05consec, bita05id, bita05fecha, bita05hora, bita05minuto, bita05idtercero, bita05contenido, bita05descartada, bita05visiblesolicitante, bita05idorigen, bita05idarchivo';
		$sValores1505='';
		$sSQL='SELECT * FROM bita05bitacoranota WHERE bita05idbitacora='.$bita05idbitacoraPadre.'';
		$tabla=$objDB->ejecutasql($sSQL);
		while($fila=$objDB->sf($tabla)){
			if ($sValores1505!=''){$sValores1505=$sValores1505.', ';}
			$sValores1505=$sValores1505.'('.$bita05idbitacora.', '.$bita05consec.', '.$bita05id.', "'.$fila['bita05fecha'].'", '.$fila['bita05hora'].', '.$fila['bita05minuto'].', '.$fila['bita05idtercero'].', "'.$fila['bita05contenido'].'", "'.$fila['bita05descartada'].'", "'.$fila['bita05visiblesolicitante'].'", "'.$fila['bita05idorigen'].'", "'.$fila['bita05idarchivo'].'")';
			$bita05consec++;
			$bita05id++;
			}
		if ($sValores1505!=''){
			$sSQL='INSERT INTO bita05bitacoranota('.$sCampos1505.') VALUES '.$sValores1505.'';
			$result=$objDB->ejecutasql($sSQL);
			}
		}
	return $sError;
	}
// -- 1505 Anotaciones XAJAX 
function elimina_archivo_bita05idarchivo($idpadre){
	$_SESSION['u_ultimominuto']=iminutoavance();
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	archivo_eliminar('bita05bitacoranota', 'bita05id', 'bita05idorigen', 'bita05idarchivo', $idpadre, $objDB);
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->call("limpia_bita05idarchivo");
	return $objResponse;
	}
function f1505_Guardar($valores, $aParametros){
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
		list($sError, $iAccion, $bita05id, $sDebugGuardar)=f1505_db_Guardar($valores, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugGuardar;
		}
	$objResponse=new xajaxResponse();
	if ($sError==''){
		list($sdetalle, $sDebugTabla)=f1505_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugTabla;
		$objResponse->assign('div_f1505detalle', 'innerHTML', $sdetalle);
		//if ($iAccion==2){
			//$objResponse->call('cargaridf1505('.$bita05id.')');
			//}else{
			$objResponse->call('limpiaf1505');
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
function f1505_Traer($aParametros){
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
		$bita05idbitacora=numeros_validar($aParametros[1]);
		$bita05consec=numeros_validar($aParametros[2]);
		if (($bita05idbitacora!='')&&($bita05consec!='')){$besta=true;}
		}else{
		$bita05id=$aParametros[103];
		if ((int)$bita05id!=0){$besta=true;}
		}
	if ($besta){
		$besta=false;
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		$bHayDb=true;
		$sSQLcondi='';
		if ($paso==1){
			$sSQLcondi=$sSQLcondi.'bita05idbitacora='.$bita05idbitacora.' AND bita05consec='.$bita05consec.'';
			}else{
			$sSQLcondi=$sSQLcondi.'bita05id='.$bita05id.'';
			}
		$sSQL='SELECT * FROM bita05bitacoranota WHERE '.$sSQLcondi;
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
		$bita05idtercero_id=(int)$fila['bita05idtercero'];
		$bita05idtercero_td=$APP->tipo_doc;
		$bita05idtercero_doc='';
		$bita05idtercero_nombre='';
		if ($bita05idtercero_id!=0){
			list($bita05idtercero_nombre, $bita05idtercero_id, $bita05idtercero_td, $bita05idtercero_doc)=html_tercero($bita05idtercero_td, $bita05idtercero_doc, $bita05idtercero_id, 0, $objDB);
			}
		$bita05consec_nombre='';
		$html_bita05consec=html_oculto('bita05consec', $fila['bita05consec'], $bita05consec_nombre);
		$objResponse->assign('div_bita05consec', 'innerHTML', $html_bita05consec);
		$bita05id_nombre='';
		$html_bita05id=html_oculto('bita05id', $fila['bita05id'], $bita05id_nombre);
		$objResponse->assign('div_bita05id', 'innerHTML', $html_bita05id);
		$objResponse->assign('bita05fecha', 'value', $fila['bita05fecha']);
		$objResponse->assign('bita05fecha_dia', 'value', substr($fila['bita05fecha'], 0, 2));
		$objResponse->assign('bita05fecha_mes', 'value', substr($fila['bita05fecha'], 3, 2));
		$objResponse->assign('bita05fecha_agno', 'value', substr($fila['bita05fecha'], 6, 4));
		$html_bita05hora=html_HoraMin('bita05hora', $fila['bita05hora'], 'bita05minuto', $fila['bita05minuto'], false);
		$objResponse->assign('div_bita05hora', 'innerHTML', $html_bita05hora);
		$objResponse->assign('bita05idtercero', 'value', $fila['bita05idtercero']);
		$objResponse->assign('bita05idtercero_td', 'value', $bita05idtercero_td);
		$objResponse->assign('bita05idtercero_doc', 'value', $bita05idtercero_doc);
		$objResponse->assign('div_bita05idtercero', 'innerHTML', $bita05idtercero_nombre);
		$objResponse->assign('bita05contenido', 'value', $fila['bita05contenido']);
		$objResponse->assign('bita05descartada', 'value', $fila['bita05descartada']);
		$objResponse->assign('bita05visiblesolicitante', 'value', $fila['bita05visiblesolicitante']);
		$objResponse->assign('bita05idorigen', 'value', $fila['bita05idorigen']);
		$idorigen=(int)$fila['bita05idorigen'];
		$objResponse->assign('bita05idarchivo', 'value', $fila['bita05idarchivo']);
		$objResponse->call("verboton('banexabita05idarchivo', 'block')");
		$stemp='none';
		$stemp2=html_lnkarchivo($idorigen, (int)$fila['bita05idarchivo']);
		if ((int)$fila['bita05idarchivo']!=0){$stemp='block';}
		$objResponse->assign('div_bita05idarchivo', 'innerHTML', $stemp2);
		$objResponse->call("verboton('beliminabita05idarchivo','".$stemp."')");
		$objResponse->call("MensajeAlarmaV2('', 0)");
		$objResponse->call("verboton('belimina1505','block')");
		}else{
		if ($paso==1){
			$objResponse->assign('bita05consec', 'value', $bita05consec);
			}else{
			$objResponse->call('MensajeAlarmaV2("No se encontro el registro de referencia:'.$bita05id.'", 0)');
			}
		}
	if ($bHayDb){
		$objDB->CerrarConexion();
		}
	return $objResponse;
	}
function f1505_Eliminar($aParametros){
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
	list($sError, $sDebugElimina)=f1505_db_Eliminar($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugElimina;
	$objResponse=new xajaxResponse();
	if ($sError==''){
		list($sDetalle, $sDebugTabla)=f1505_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugTabla;
		$objResponse->assign('div_f1505detalle', 'innerHTML', $sDetalle);
		$objResponse->call('limpiaf1505');
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
function f1505_HtmlTabla($aParametros){
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
	list($sDetalle, $sDebugTabla)=f1505_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f1505detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f1505_PintarLlaves($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	//if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	if (isset($APP->piel)==0){$APP->piel=1;}
	$iPiel=$APP->piel;
	$html_bita05consec='<input id="bita05consec" name="bita05consec" type="text" value="" onchange="revisaf1505()" class="cuatro"/>';
	$html_bita05id='<input id="bita05id" name="bita05id" type="hidden" value=""/>';
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_bita05consec','innerHTML', $html_bita05consec);
	$objResponse->assign('div_bita05id','innerHTML', $html_bita05id);
	return $objResponse;
	}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------
?>