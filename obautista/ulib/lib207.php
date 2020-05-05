<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2018 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.22.4 miércoles, 29 de agosto de 2018
--- 207 Anotaciones
*/
function f207_db_Guardar($valores, $objDB, $bDebug=false){
	$iCodModulo=207;
	$bAudita[2]=false;
	$bAudita[3]=false;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_207=$APP->rutacomun.'lg/lg_207_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_207)){$mensajes_207=$APP->rutacomun.'lg/lg_207_es.php';}
	require $mensajes_todas;
	require $mensajes_207;
	$sError='';
	$sDebug='';
	$binserta=false;
	$iAccion=3;
	if(!is_array($valores)){$valores=json_decode(str_replace('\"','"',$valores),true);}
	$unae07idnoconformidad=numeros_validar($valores[1]);
	$unae07consec=numeros_validar($valores[2]);
	$unae07id=numeros_validar($valores[3], true);
	$unae07idtercero=numeros_validar($valores[4]);
	$unae07fecha=$valores[5];
	$unae07minuto=numeros_validar($valores[6]);
	$unae07nota=htmlspecialchars(trim($valores[7]));
	//if ($unae07minuto==''){$unae07minuto=0;}
	$sSepara=', ';
	if ($unae07nota==''){$sError=$ERR['unae07nota'].$sSepara.$sError;}
	if ($unae07minuto==''){$sError=$ERR['unae07minuto'].$sSepara.$sError;}
	if ($unae07fecha==0){
		//$unae07fecha=fecha_DiaMod();
		$sError=$ERR['unae07fecha'].$sSepara.$sError;
		}
	if ($unae07idtercero==0){$sError=$ERR['unae07idtercero'].$sSepara.$sError;}
	//if ($unae07id==''){$sError=$ERR['unae07id'].$sSepara.$sError;}//CONSECUTIVO
	//if ($unae07consec==''){$sError=$ERR['unae07consec'].$sSepara.$sError;}//CONSECUTIVO
	if ($unae07idnoconformidad==''){$sError=$ERR['unae07idnoconformidad'].$sSepara.$sError;}
	if ($sError==''){
		list($sError, $sInfo)=tercero_Bloqueado($unae07idtercero, $objDB);
		if ($sInfo!=''){$sError=$sError.'<br>'.sInfo;}
		}
	if ($sError==''){
		if ((int)$unae07id==0){
			if ((int)$unae07consec==0){
				$unae07consec=tabla_consecutivo('unae07noconfornota', 'unae07consec', 'unae07idnoconformidad='.$unae07idnoconformidad.'', $objDB);
				if ($unae07consec==-1){$sError=$objDB->serror;}
				}else{
				if (!seg_revisa_permiso($iCodModulo, 8, $objDB)){$sError=$ERR['8'];}
				}
			if ($sError==''){
				$sSQL='SELECT unae07idnoconformidad FROM unae07noconfornota WHERE unae07idnoconformidad='.$unae07idnoconformidad.' AND unae07consec='.$unae07consec.'';
				$result=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($result)!=0){
					$sError=$ERR['existe'];
					}else{
					if (!seg_revisa_permiso($iCodModulo, 2, $objDB)){$sError=$ERR['2'];}
					}
				}
			if ($sError==''){
				$unae07id=tabla_consecutivo('unae07noconfornota', 'unae07id', '', $objDB);
				if ($unae07id==-1){$sError=$objDB->serror;}
				$binserta=true;
				$iAccion=2;
				}
			}else{
			if (!seg_revisa_permiso($iCodModulo, 3, $objDB)){$sError=$ERR['3'];}
			}
		}
	if ($sError==''){
		if ($binserta){
			$unae07idtercero=$_SESSION['unad_id_tercero'];
			$unae07fecha=fecha_DiaMod();
			$unae07minuto=fecha_MinutoMod();
			$unae07idcont=0;
			$unae07idarchivo=0;
			}
		}
	if ($sError==''){
		//Si el campo unae07nota permite html quite la linea htmlspecialchars para el campo y habilite la siguiente linea:
		//$unae07nota=str_replace('"', '\"', $unae07nota);
		$unae07nota=str_replace('"', '\"', $unae07nota);
		if ($binserta){
			$scampos='unae07idnoconformidad, unae07consec, unae07id, unae07idtercero, unae07fecha, 
unae07minuto, unae07nota, unae07idcont, unae07idarchivo';
			$svalores=''.$unae07idnoconformidad.', '.$unae07consec.', '.$unae07id.', "'.$unae07idtercero.'", "'.$unae07fecha.'", 
'.$unae07minuto.', "'.$unae07nota.'", 0, 0';
			if ($APP->utf8==1){
				$sSQL='INSERT INTO unae07noconfornota ('.$scampos.') VALUES ('.utf8_encode($svalores).');';
				}else{
				$sSQL='INSERT INTO unae07noconfornota ('.$scampos.') VALUES ('.$svalores.');';
				}
			$result=$objDB->ejecutasql($sSQL);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' {Anotaciones}.<!-- '.$sSQL.' -->';
				}else{
				if ($bAudita[2]){
					seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 2, $unae07id, $sSQL, $objDB);
					}
				}
			}else{
			$scampo207[1]='unae07nota';
			$svr207[1]=$unae07nota;
			$inumcampos=1;
			$sWhere='unae07id='.$unae07id.'';
			//$sWhere='unae07idnoconformidad='.$unae07idnoconformidad.' AND unae07consec='.$unae07consec.'';
			$sSQL='SELECT * FROM unae07noconfornota WHERE '.$sWhere;
			$sdatos='';
			$bpasa=false;
			$result=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($result)>0){
				$filaorigen=$objDB->sf($result);
				for ($k=1;$k<=$inumcampos;$k++){
					if ($filaorigen[$scampo207[$k]]!=$svr207[$k]){
						if ($sdatos!=''){$sdatos=$sdatos.', ';}
						$sdatos=$sdatos.$scampo207[$k].'="'.$svr207[$k].'"';
						$bpasa=true;
						}
					}
				}
			if ($bpasa){
				if ($APP->utf8==1){
					$sSQL='UPDATE unae07noconfornota SET '.utf8_encode($sdatos).' WHERE '.$sWhere.';';
					}else{
					$sSQL='UPDATE unae07noconfornota SET '.$sdatos.' WHERE '.$sWhere.';';
					}
				$result=$objDB->ejecutasql($sSQL);
				if ($result==false){
					$sError=$ERR['falla_guardar'].' {Anotaciones}. <!-- '.$sSQL.' -->';
					}else{
					if ($bAudita[3]){
						seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 3, $unae07id, $sSQL, $objDB);
						}
					}
				}
			}
		}
	return array($sError, $iAccion, $unae07id, $sDebug);
	}
function f207_db_Eliminar($aParametros, $objDB, $bDebug=false){
	$iCodModulo=207;
	$bAudita[4]=false;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_207=$APP->rutacomun.'lg/lg_207_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_207)){$mensajes_207=$APP->rutacomun.'lg/lg_207_es.php';}
	require $mensajes_todas;
	require $mensajes_207;
	$sError='';
	$sDebug='';
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$unae07idnoconformidad=numeros_validar($aParametros[1]);
	$unae07consec=numeros_validar($aParametros[2]);
	$unae07id=numeros_validar($aParametros[3]);
	if ($sError==''){
		if (!seg_revisa_permiso($iCodModulo, 4, $objDB)){
			$sError=$ERR['4'];
			}
		}
	if ($sError==''){
		$sSQL='SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=207';
		$tablaor=$objDB->ejecutasql($sSQL);
		while ($filaor=$objDB->sf($tablaor)){
			$sSQL='SELECT '.$filaor['campoid'].' FROM '.$filaor['tabla'].' WHERE '.$filaor['camporev'].'='.$unae07id.' LIMIT 0, 1';
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
		$sWhere='unae07id='.$unae07id.'';
		//$sWhere='unae07idnoconformidad='.$unae07idnoconformidad.' AND unae07consec='.$unae07consec.'';
		$sSQL='DELETE FROM unae07noconfornota WHERE '.$sWhere.';';
		$result=$objDB->ejecutasql($sSQL);
		if ($result==false){
			$sError=$ERR['falla_eliminar'].' {207 Anotaciones}.<!-- '.$sSQL.' -->';
			}else{
			if ($bAudita[4]){
				seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $unae07id, $sSQL, $objDB);
				}
			}
		}
	return array($sError, $sDebug);
	}
function f207_TablaDetalleV2($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_207=$APP->rutacomun.'lg/lg_207_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_207)){$mensajes_207=$APP->rutacomun.'lg/lg_207_es.php';}
	require $mensajes_todas;
	require $mensajes_207;
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	if (isset($aParametros[0])==0){$aParametros[0]=-1;}
	if (isset($aParametros[101])==0){$aParametros[101]=1;}
	if (isset($aParametros[102])==0){$aParametros[102]=20;}
	//if (isset($aParametros[103])==0){$aParametros[103]='';}
	//$aParametros[103]=numeros_validar($aParametros[103]);
	$aParametros[0]=numeros_validar($aParametros[0]);
	if ($aParametros[0]==''){$aParametros[0]=-1;}
	$sDebug='';
	$unae03id=$aParametros[0];
	$pagina=$aParametros[101];
	$lineastabla=$aParametros[102];
	$babierta=true;
	//$sSQL='SELECT Campo FROM unae03noconforme WHERE unae03id='.$unae03id;
	//$tabla=$objDB->ejecutasql($sSQL);
	//if ($objDB->nf($tabla)>0){
		//$fila=$objDB->sf($tabla);
		//if ($fila['Campo']!='S'){$babierta=true;}
		//}
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
				$sSQLadd=$sSQLadd.' AND T6.sys11razonsocial LIKE "%'.$sCadena.'%"';
				//$sSQLadd1=$sSQLadd1.'T1.sys11razonsocial LIKE "%'.$sCadena.'%" AND ';
				}
			}
		}
	*/
	$sTitulos='Noconformidad, Consec, Id, Tercero, Fecha, Minuto, Nota, Cont, Archivo';
	$sSQL='SELECT TB.unae07idnoconformidad, TB.unae07consec, TB.unae07id, T4.unad11razonsocial AS C4_nombre, TB.unae07fecha, TB.unae07minuto, TB.unae07nota, TB.unae07idcont, TB.unae07idarchivo, TB.unae07idtercero, T4.unad11tipodoc AS C4_td, T4.unad11doc AS C4_doc 
FROM unae07noconfornota AS TB, unad11terceros AS T4 
WHERE '.$sSQLadd1.' TB.unae07idnoconformidad='.$unae03id.' AND TB.unae07idtercero=T4.unad11id '.$sSQLadd.'
ORDER BY TB.unae07consec';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_207" name="consulta_207" type="hidden" value="'.$sSQLlista.'"/>
<input id="titulos_207" name="titulos_207" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objDB->ejecutasql($sSQL);
	if ($tabladetalle==false){
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 207: '.$sSQL.'<br>';}
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		//$sLeyenda=$sSQL;
		}else{
		$registros=$objDB->nf($tabladetalle);
		if ($registros==0){
			return array(utf8_encode($sErrConsulta.'<input id="paginaf207" name="paginaf207" type="hidden" value="'.$pagina.'"/><input id="lppf207" name="lppf207" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
<td><b>'.$ETI['unae07consec'].'</b></td>
<td><b>'.$ETI['unae07nota'].'</b></td>
<td><b>'.$ETI['unae07idarchivo'].'</b></td>
<td colspan="2"><b>'.$ETI['unae07idtercero'].'</b></td>
<td colspan="2"><b>'.$ETI['unae07fecha'].'</b></td>
<td align="right">
'.html_paginador('paginaf207', $registros, $lineastabla, $pagina, 'paginarf207()').'
'.html_lpp('lppf207', $lineastabla, 'paginarf207()').'
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
		$et_unae07consec=$sPrefijo.$filadet['unae07consec'].$sSufijo;
		$et_unae07idtercero=$sPrefijo.$filadet['unae07idtercero'].$sSufijo;
		$et_unae07fecha='';
		if ($filadet['unae07fecha']!=0){$et_unae07fecha=$sPrefijo.fecha_desdenumero($filadet['unae07fecha']).$sSufijo;}
		$et_unae07minuto=$sPrefijo.html_TablaHoraMinDesdeNumero($filadet['unae07minuto']).$sSufijo;
		$et_unae07nota=$sPrefijo.cadena_notildes($filadet['unae07nota']).$sSufijo;
		$et_unae07idarchivo='';
		if ($filadet['unae07idarchivo']!=0){
			//$et_unae07idarchivo='<img src="verarchivo.php?cont='.$filadet['unae07idcont'].'&id='.$filadet['unae07idarchivo'].'&maxx=150"/>';
			$et_unae07idarchivo=html_lnkarchivo((int)$filadet['unae07idcont'], (int)$filadet['unae07idarchivo']);
			}
		if ($babierta){
			$sLink='<a href="javascript:cargaridf207('.$filadet['unae07id'].')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$res=$res.'<tr'.$sClass.'>
<td>'.$et_unae07consec.'</td>
<td>'.$et_unae07nota.'</td>
<td>'.$et_unae07idarchivo.'</td>
<td>'.$sPrefijo.$filadet['C4_td'].' '.$filadet['C4_doc'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['C4_nombre']).$sSufijo.'</td>
<td>'.$et_unae07fecha.'</td>
<td>'.$et_unae07minuto.'</td>
<td>'.$sLink.'</td>
</tr>';
		}
	$res=$res.'</table>';
	$objDB->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
	}
function f207_Clonar($unae07idnoconformidad, $unae07idnoconformidadPadre, $objDB){
	$sError='';
	$unae07consec=tabla_consecutivo('unae07noconfornota', 'unae07consec', 'unae07idnoconformidad='.$unae07idnoconformidad.'', $objDB);
	if ($unae07consec==-1){$sError=$objDB->serror;}
	$unae07id=tabla_consecutivo('unae07noconfornota', 'unae07id', '', $objDB);
	if ($unae07id==-1){$sError=$objDB->serror;}
	if ($sError==''){
		$sCampos207='unae07idnoconformidad, unae07consec, unae07id, unae07idtercero, unae07fecha, unae07minuto, unae07nota, unae07idcont, unae07idarchivo';
		$sValores207='';
		$sSQL='SELECT * FROM unae07noconfornota WHERE unae07idnoconformidad='.$unae07idnoconformidadPadre.'';
		$tabla=$objDB->ejecutasql($sSQL);
		while($fila=$objDB->sf($tabla)){
			if ($sValores207!=''){$sValores207=$sValores207.', ';}
			$sValores207=$sValores207.'('.$unae07idnoconformidad.', '.$unae07consec.', '.$unae07id.', '.$fila['unae07idtercero'].', "'.$fila['unae07fecha'].'", '.$fila['unae07minuto'].', "'.$fila['unae07nota'].'", "'.$fila['unae07idcont'].'", "'.$fila['unae07idarchivo'].'")';
			$unae07consec++;
			$unae07id++;
			}
		if ($sValores207!=''){
			$sSQL='INSERT INTO unae07noconfornota('.$sCampos207.') VALUES '.$sValores207.'';
			$result=$objDB->ejecutasql($sSQL);
			}
		}
	return $sError;
	}
// -- 207 Anotaciones XAJAX 
function elimina_archivo_unae07idarchivo($idpadre){
	$_SESSION['u_ultimominuto']=iminutoavance();
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	archivo_eliminar('unae07noconfornota', 'unae07id', 'unae07idcont', 'unae07idarchivo', $idpadre, $objDB);
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->call("limpia_unae07idarchivo");
	return $objResponse;
	}
function f207_Guardar($valores, $aParametros){
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
		list($sError, $iAccion, $unae07id, $sDebugGuardar)=f207_db_Guardar($valores, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugGuardar;
		}
	$objResponse=new xajaxResponse();
	if ($sError==''){
		list($sdetalle, $sDebugTabla)=f207_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugTabla;
		$objResponse->assign('div_f207detalle', 'innerHTML', $sdetalle);
		//if ($iAccion==2){
			//$objResponse->call('cargaridf207('.$unae07id.')');
			//}else{
			$objResponse->call('limpiaf207');
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
function f207_Traer($aParametros){
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
		$unae07idnoconformidad=numeros_validar($aParametros[1]);
		$unae07consec=numeros_validar($aParametros[2]);
		if (($unae07idnoconformidad!='')&&($unae07consec!='')){$besta=true;}
		}else{
		$unae07id=$aParametros[103];
		if ((int)$unae07id!=0){$besta=true;}
		}
	if ($besta){
		$besta=false;
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		$bHayDb=true;
		$sSQLcondi='';
		if ($paso==1){
			$sSQLcondi=$sSQLcondi.'unae07idnoconformidad='.$unae07idnoconformidad.' AND unae07consec='.$unae07consec.'';
			}else{
			$sSQLcondi=$sSQLcondi.'unae07id='.$unae07id.'';
			}
		$sSQL='SELECT * FROM unae07noconfornota WHERE '.$sSQLcondi;
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
		$unae07idtercero_id=(int)$fila['unae07idtercero'];
		$unae07idtercero_td=$APP->tipo_doc;
		$unae07idtercero_doc='';
		$unae07idtercero_nombre='';
		if ($unae07idtercero_id!=0){
			list($unae07idtercero_nombre, $unae07idtercero_id, $unae07idtercero_td, $unae07idtercero_doc)=html_tercero($unae07idtercero_td, $unae07idtercero_doc, $unae07idtercero_id, 0, $objDB);
			}
		$unae07consec_nombre='';
		$html_unae07consec=html_oculto('unae07consec', $fila['unae07consec'], $unae07consec_nombre);
		$objResponse->assign('div_unae07consec', 'innerHTML', $html_unae07consec);
		$unae07id_nombre='';
		$html_unae07id=html_oculto('unae07id', $fila['unae07id'], $unae07id_nombre);
		$objResponse->assign('div_unae07id', 'innerHTML', $html_unae07id);
		$bOculto=true;
		$html_unae07idtercero_llaves=html_DivTerceroV2('unae07idtercero', $unae07idtercero_td, $unae07idtercero_doc, $bOculto, $unae07idtercero_id, $ETI['ing_doc']);
		$objResponse->assign('unae07idtercero', 'value', $unae07idtercero_id);
		$objResponse->assign('div_unae07idtercero_llaves', 'innerHTML', $html_unae07idtercero_llaves);
		$objResponse->assign('div_unae07idtercero', 'innerHTML', $unae07idtercero_nombre);
		$unae07fecha_eti=$fila['unae07fecha'];
		$html_unae07fecha=html_oculto('unae07fecha', $fila['unae07fecha'], $unae07fecha_eti);
		$objResponse->assign('div_unae07fecha', 'innerHTML', $html_unae07fecha);
		$unae07minuto_eti=$fila['unae07minuto'];
		$html_unae07minuto=html_oculto('unae07minuto', $fila['unae07minuto'], $unae07minuto_eti);
		$objResponse->assign('div_unae07minuto', 'innerHTML', $html_unae07minuto);
		$objResponse->assign('unae07nota', 'value', $fila['unae07nota']);
		$objResponse->assign('unae07idcont', 'value', $fila['unae07idcont']);
		$idorigen=(int)$fila['unae07idcont'];
		$objResponse->assign('unae07idarchivo', 'value', $fila['unae07idarchivo']);
		$objResponse->call("verboton('banexaunae07idarchivo', 'block')");
		$stemp='none';
		$stemp2=html_lnkarchivo($idorigen, (int)$fila['unae07idarchivo']);
		if ((int)$fila['unae07idarchivo']!=0){$stemp='block';}
		$objResponse->assign('div_unae07idarchivo', 'innerHTML', $stemp2);
		$objResponse->call("verboton('beliminaunae07idarchivo','".$stemp."')");
		$objResponse->call("MensajeAlarmaV2('', 0)");
		$objResponse->call("verboton('belimina207','block')");
		}else{
		if ($paso==1){
			$objResponse->assign('unae07consec', 'value', $unae07consec);
			}else{
			$objResponse->call('MensajeAlarmaV2("No se encontro el registro de referencia:'.$unae07id.'", 0)');
			}
		}
	if ($bHayDb){
		$objDB->CerrarConexion();
		}
	return $objResponse;
	}
function f207_Eliminar($aParametros){
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
	list($sError, $sDebugElimina)=f207_db_Eliminar($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugElimina;
	$objResponse=new xajaxResponse();
	if ($sError==''){
		list($sDetalle, $sDebugTabla)=f207_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugTabla;
		$objResponse->assign('div_f207detalle', 'innerHTML', $sDetalle);
		$objResponse->call('limpiaf207');
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
function f207_HtmlTabla($aParametros){
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
	list($sDetalle, $sDebugTabla)=f207_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f207detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f207_PintarLlaves($aParametros){
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
	$html_unae07consec='<input id="unae07consec" name="unae07consec" type="text" value="" onchange="revisaf207()" class="cuatro"/>';
	$html_unae07id='<input id="unae07id" name="unae07id" type="hidden" value=""/>';
	list($unae07idtercero_rs, $unae07idtercero, $unae07idtercero_td, $unae07idtercero_doc)=html_tercero('CC', '', $_SESSION['unad_id_tercero'], 0, $objDB);
	$html_unae07idtercero_llaves=html_DivTerceroV2('unae07idtercero', $unae07idtercero_td, $unae07idtercero_doc, true, 0, $ETI['ing_doc']);
	$iMinuto=fecha_MinutoMod();
	$iDia=fecha_DiaMod();
	$html_unae07fecha=html_oculto('unae07fecha', $iDia, fecha_desdenumero($iDia));
	$html_unae07minuto=html_oculto('unae07minuto', $iMinuto, html_TablaHoraMinDesdeNumero($iMinuto));
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_unae07consec','innerHTML', $html_unae07consec);
	$objResponse->assign('div_unae07id','innerHTML', $html_unae07id);
	$objResponse->assign('unae07idtercero','value', $unae07idtercero);
	$objResponse->assign('div_unae07idtercero_llaves','innerHTML', $html_unae07idtercero_llaves);
	$objResponse->assign('div_unae07idtercero','innerHTML', $unae07idtercero_rs);
	$objResponse->assign('div_unae07fecha','innerHTML', $html_unae07fecha);
	$objResponse->assign('div_unae07minuto','innerHTML', $html_unae07minuto);
	return $objResponse;
	}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------
?>