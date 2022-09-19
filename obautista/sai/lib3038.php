<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2020 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.25.4 martes, 4 de agosto de 2020
--- 3038 Cambios de estado
*/
function f3038_db_Guardar($valores, $objDB, $bDebug=false){
	$iCodModulo=3038;
	$bAudita[2]=false;
	$bAudita[3]=false;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3038='lg/lg_3038_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3038)){$mensajes_3038='lg/lg_3038_es.php';}
	require $mensajes_todas;
	require $mensajes_3038;
	$sError='';
	$sDebug='';
	$bInserta=false;
	$iAccion=3;
	if(!is_array($valores)){$valores=json_decode(str_replace('\"','"',$valores),true);}
	$saiu38idbasecon=numeros_validar($valores[1]);
	$saiu38consec=numeros_validar($valores[2]);
	$saiu38id=numeros_validar($valores[3], true);
	$saiu38idestadorigen=numeros_validar($valores[4]);
	$saiu38idestadofin=numeros_validar($valores[5]);
	$saiu38detalle=htmlspecialchars(trim($valores[6]));
	$saiu38usuario=numeros_validar($valores[7]);
	$saiu38fecha=$valores[8];
	//if ($saiu38idestadorigen==''){$saiu38idestadorigen=0;}
	//if ($saiu38idestadofin==''){$saiu38idestadofin=0;}
	$sSepara=', ';
	if ($saiu38fecha==0){
		//$saiu38fecha=fecha_DiaMod();
		$sError=$ERR['saiu38fecha'].$sSepara.$sError;
		}
	if ($saiu38usuario==0){$sError=$ERR['saiu38usuario'].$sSepara.$sError;}
	if ($saiu38detalle==''){$sError=$ERR['saiu38detalle'].$sSepara.$sError;}
	if ($saiu38idestadofin==''){$sError=$ERR['saiu38idestadofin'].$sSepara.$sError;}
	if ($saiu38idestadorigen==''){$sError=$ERR['saiu38idestadorigen'].$sSepara.$sError;}
	//if ($saiu38id==''){$sError=$ERR['saiu38id'].$sSepara.$sError;}//CONSECUTIVO
	//if ($saiu38consec==''){$sError=$ERR['saiu38consec'].$sSepara.$sError;}//CONSECUTIVO
	if ($saiu38idbasecon==''){$sError=$ERR['saiu38idbasecon'].$sSepara.$sError;}
	if ($sError==''){
		list($sError, $sInfo)=tercero_Bloqueado($saiu38usuario, $objDB);
		if ($sInfo!=''){$sError=$sError.'<br>'.sInfo;}
		}
	if ($sError==''){
		if ((int)$saiu38id==0){
			if ((int)$saiu38consec==0){
				$saiu38consec=tabla_consecutivo('saiu38basecambioest', 'saiu38consec', 'saiu38idbasecon='.$saiu38idbasecon.'', $objDB);
				if ($saiu38consec==-1){$sError=$objDB->serror;}
				}else{
				if (!seg_revisa_permiso($iCodModulo, 8, $objDB)){$sError=$ERR['8'];}
				}
			if ($sError==''){
				$sSQL='SELECT saiu38idbasecon FROM saiu38basecambioest WHERE saiu38idbasecon='.$saiu38idbasecon.' AND saiu38consec='.$saiu38consec.'';
				$result=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($result)!=0){
					$sError=$ERR['existe'];
					}else{
					if (!seg_revisa_permiso($iCodModulo, 2, $objDB)){$sError=$ERR['2'];}
					}
				}
			if ($sError==''){
				$saiu38id=tabla_consecutivo('saiu38basecambioest', 'saiu38id', '', $objDB);
				if ($saiu38id==-1){$sError=$objDB->serror;}
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
		//Si el campo saiu38detalle permite html quite la linea htmlspecialchars para el campo y habilite la siguiente linea:
		//$saiu38detalle=str_replace('"', '\"', $saiu38detalle);
		$saiu38detalle=str_replace('"', '\"', $saiu38detalle);
		if ($bInserta){
			$sCampos3038='saiu38idbasecon, saiu38consec, saiu38id, saiu38idestadorigen, saiu38idestadofin, 
saiu38detalle, saiu38usuario, saiu38fecha';
			$sValores3038=''.$saiu38idbasecon.', '.$saiu38consec.', '.$saiu38id.', '.$saiu38idestadorigen.', '.$saiu38idestadofin.', 
"'.$saiu38detalle.'", "'.$saiu38usuario.'", "'.$saiu38fecha.'"';
			if ($APP->utf8==1){
				$sSQL='INSERT INTO saiu38basecambioest ('.$sCampos3038.') VALUES ('.utf8_encode($sValores3038).');';
				}else{
				$sSQL='INSERT INTO saiu38basecambioest ('.$sCampos3038.') VALUES ('.$sValores3038.');';
				}
			if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Guardar 3038 '.$sSQL.'<br>';}
			$result=$objDB->ejecutasql($sSQL);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' [3038].<!-- '.$sSQL.' -->';
				}else{
				if ($bAudita[2]){
					seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 2, $saiu38id, $sSQL, $objDB);
					}
				}
			}else{
			$scampo3038[1]='saiu38idestadorigen';
			$scampo3038[2]='saiu38idestadofin';
			$scampo3038[3]='saiu38detalle';
			$scampo3038[4]='saiu38usuario';
			$scampo3038[5]='saiu38fecha';
			$svr3038[1]=$saiu38idestadorigen;
			$svr3038[2]=$saiu38idestadofin;
			$svr3038[3]=$saiu38detalle;
			$svr3038[4]=$saiu38usuario;
			$svr3038[5]=$saiu38fecha;
			$inumcampos=5;
			$sWhere='saiu38id='.$saiu38id.'';
			//$sWhere='saiu38idbasecon='.$saiu38idbasecon.' AND saiu38consec='.$saiu38consec.'';
			$sSQL='SELECT * FROM saiu38basecambioest WHERE '.$sWhere;
			$sdatos='';
			$bpasa=false;
			$result=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($result)>0){
				$filaorigen=$objDB->sf($result);
				for ($k=1;$k<=$inumcampos;$k++){
					if ($filaorigen[$scampo3038[$k]]!=$svr3038[$k]){
						if ($sdatos!=''){$sdatos=$sdatos.', ';}
						$sdatos=$sdatos.$scampo3038[$k].'="'.$svr3038[$k].'"';
						$bpasa=true;
						}
					}
				}
			if ($bpasa){
				if ($APP->utf8==1){
					$sSQL='UPDATE saiu38basecambioest SET '.utf8_encode($sdatos).' WHERE '.$sWhere.';';
					}else{
					$sSQL='UPDATE saiu38basecambioest SET '.$sdatos.' WHERE '.$sWhere.';';
					}
				$result=$objDB->ejecutasql($sSQL);
				if ($result==false){
					$sError=$ERR['falla_guardar'].' {Cambios de estado}. <!-- '.$sSQL.' -->';
					}else{
					if ($bAudita[3]){
						seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 3, $saiu38id, $sSQL, $objDB);
						}
					}
				}
			}
		}
	return array($sError, $iAccion, $saiu38id, $sDebug);
	}
function f3038_db_Eliminar($aParametros, $objDB, $bDebug=false){
	$iCodModulo=3038;
	$bAudita[4]=false;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3038='lg/lg_3038_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3038)){$mensajes_3038='lg/lg_3038_es.php';}
	require $mensajes_todas;
	require $mensajes_3038;
	$sError='';
	$sDebug='';
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$saiu38idbasecon=numeros_validar($aParametros[1]);
	$saiu38consec=numeros_validar($aParametros[2]);
	$saiu38id=numeros_validar($aParametros[3]);
	if ($sError==''){
		if (!seg_revisa_permiso($iCodModulo, 4, $objDB)){
			$sError=$ERR['4'];
			}
		}
	if ($sError==''){
		$sSQL='SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=3038';
		$tablaor=$objDB->ejecutasql($sSQL);
		while ($filaor=$objDB->sf($tablaor)){
			$sSQL='SELECT '.$filaor['campoid'].' FROM '.$filaor['tabla'].' WHERE '.$filaor['camporev'].'='.$saiu38id.' LIMIT 0, 1';
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
		$sWhere='saiu38id='.$saiu38id.'';
		//$sWhere='saiu38idbasecon='.$saiu38idbasecon.' AND saiu38consec='.$saiu38consec.'';
		$sSQL='DELETE FROM saiu38basecambioest WHERE '.$sWhere.';';
		$result=$objDB->ejecutasql($sSQL);
		if ($result==false){
			$sError=$ERR['falla_eliminar'].' {3038 Cambios de estado}.<!-- '.$sSQL.' -->';
			}else{
			if ($bAudita[4]){
				seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $saiu38id, $sSQL, $objDB);
				}
			}
		}
	return array($sError, $sDebug);
	}
function f3038_TablaDetalleV2($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3038='lg/lg_3038_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3038)){$mensajes_3038='lg/lg_3038_es.php';}
	require $mensajes_todas;
	require $mensajes_3038;
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
	$saiu31id=$aParametros[0];
	$pagina=$aParametros[101];
	$lineastabla=$aParametros[102];
	$bAbierta=false;
	$sSQL='SELECT saiu31estado FROM saiu31baseconocimiento WHERE saiu31id='.$saiu31id;
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		if ($fila['saiu31estado']!='S'){$bAbierta=true;}
		}
	$sLeyenda='';
	if ($sLeyenda!=''){
		$sLeyenda='<div class="salto1px"></div>
<div class="GrupoCamposAyuda">
'.$sLeyenda.'
<div class="salto1px"></div>
</div>';
		return array($sLeyenda.'<input id="paginaf3038" name="paginaf3038" type="hidden" value="'.$pagina.'"/><input id="lppf3038" name="lppf3038" type="hidden" value="'.$lineastabla.'"/>', $sDebug);
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
	$sTitulos='Basecon, Consec, Id, Estadorigen, Estadofin, Detalle, Usuario, Fecha';
	$sSQL='SELECT TB.saiu38idbasecon, TB.saiu38consec, TB.saiu38id, T4.saiu36nombre, T5.saiu36nombre, TB.saiu38detalle, T7.unad11razonsocial AS C7_nombre, TB.saiu38fecha, TB.saiu38idestadorigen, TB.saiu38idestadofin, TB.saiu38usuario, T7.unad11tipodoc AS C7_td, T7.unad11doc AS C7_doc 
FROM saiu38basecambioest AS TB, saiu36estadobase AS T4, saiu36estadobase AS T5, unad11terceros AS T7 
WHERE '.$sSQLadd1.' TB.saiu38idbasecon='.$saiu31id.' AND TB.saiu38idestadorigen=T4.saiu36id AND TB.saiu38idestadofin=T5.saiu36id AND TB.saiu38usuario=T7.unad11id '.$sSQLadd.'
ORDER BY TB.saiu38consec';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_3038" name="consulta_3038" type="hidden" value="'.$sSQLlista.'"/>
<input id="titulos_3038" name="titulos_3038" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objDB->ejecutasql($sSQL);
	if ($tabladetalle==false){
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 3038: '.$sSQL.'<br>';}
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		//$sLeyenda=$sSQL;
		}else{
		$registros=$objDB->nf($tabladetalle);
		if ($registros==0){
			return array(utf8_encode($sErrConsulta.'<input id="paginaf3038" name="paginaf3038" type="hidden" value="'.$pagina.'"/><input id="lppf3038" name="lppf3038" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
<td><b>'.$ETI['saiu38consec'].'</b></td>
<td><b>'.$ETI['saiu38idestadorigen'].'</b></td>
<td><b>'.$ETI['saiu38idestadofin'].'</b></td>
<td><b>'.$ETI['saiu38detalle'].'</b></td>
<td colspan="2"><b>'.$ETI['saiu38usuario'].'</b></td>
<td><b>'.$ETI['saiu38fecha'].'</b></td>
<td align="right">
'.html_paginador('paginaf3038', $registros, $lineastabla, $pagina, 'paginarf3038()').'
'.html_lpp('lppf3038', $lineastabla, 'paginarf3038()').'
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
		$et_saiu38consec=$sPrefijo.$filadet['saiu38consec'].$sSufijo;
		$et_saiu38idestadorigen=$sPrefijo.cadena_notildes($filadet['saiu36nombre']).$sSufijo;
		$et_saiu38idestadofin=$sPrefijo.cadena_notildes($filadet['saiu36nombre']).$sSufijo;
		$et_saiu38detalle=$sPrefijo.cadena_notildes($filadet['saiu38detalle']).$sSufijo;
		$et_saiu38usuario_doc='';
		$et_saiu38usuario_nombre='';
		if ($filadet['saiu38usuario']!=0){
			$et_saiu38usuario_doc=$sPrefijo.$filadet['C7_td'].' '.$filadet['C7_doc'].$sSufijo;
			$et_saiu38usuario_nombre=$sPrefijo.cadena_notildes($filadet['C7_nombre']).$sSufijo;
			}
		$et_saiu38fecha='';
		if ($filadet['saiu38fecha']!=0){$et_saiu38fecha=$sPrefijo.fecha_desdenumero($filadet['saiu38fecha']).$sSufijo;}
		if ($bAbierta){
			$sLink='<a href="javascript:cargaridf3038('.$filadet['saiu38id'].')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$res=$res.'<tr'.$sClass.'>
<td>'.$et_saiu38consec.'</td>
<td>'.$et_saiu38idestadorigen.'</td>
<td>'.$et_saiu38idestadofin.'</td>
<td>'.$et_saiu38detalle.'</td>
<td>'.$et_saiu38usuario_doc.'</td>
<td>'.$et_saiu38usuario_nombre.'</td>
<td>'.$et_saiu38fecha.'</td>
<td>'.$sLink.'</td>
</tr>';
		}
	$res=$res.'</table>';
	$objDB->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
	}
// -- 3038 Cambios de estado XAJAX 
function f3038_Guardar($valores, $aParametros){
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
		list($sError, $iAccion, $saiu38id, $sDebugGuardar)=f3038_db_Guardar($valores, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugGuardar;
		}
	$objResponse=new xajaxResponse();
	if ($sError==''){
		list($sdetalle, $sDebugTabla)=f3038_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugTabla;
		$objResponse->assign('div_f3038detalle', 'innerHTML', $sdetalle);
		//if ($iAccion==2){
			//$objResponse->call('cargaridf3038('.$saiu38id.')');
			//}else{
			$objResponse->call('limpiaf3038');
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
function f3038_Traer($aParametros){
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
		$saiu38idbasecon=numeros_validar($aParametros[1]);
		$saiu38consec=numeros_validar($aParametros[2]);
		if (($saiu38idbasecon!='')&&($saiu38consec!='')){$besta=true;}
		}else{
		$saiu38id=$aParametros[103];
		if ((int)$saiu38id!=0){$besta=true;}
		}
	if ($besta){
		$besta=false;
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		$bHayDb=true;
		$sSQLcondi='';
		if ($paso==1){
			$sSQLcondi=$sSQLcondi.'saiu38idbasecon='.$saiu38idbasecon.' AND saiu38consec='.$saiu38consec.'';
			}else{
			$sSQLcondi=$sSQLcondi.'saiu38id='.$saiu38id.'';
			}
		$sSQL='SELECT * FROM saiu38basecambioest WHERE '.$sSQLcondi;
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
		$saiu38usuario_id=(int)$fila['saiu38usuario'];
		$saiu38usuario_td=$APP->tipo_doc;
		$saiu38usuario_doc='';
		$saiu38usuario_nombre='';
		if ($saiu38usuario_id!=0){
			list($saiu38usuario_nombre, $saiu38usuario_id, $saiu38usuario_td, $saiu38usuario_doc)=html_tercero($saiu38usuario_td, $saiu38usuario_doc, $saiu38usuario_id, 0, $objDB);
			}
		$saiu38consec_nombre='';
		$html_saiu38consec=html_oculto('saiu38consec', $fila['saiu38consec'], $saiu38consec_nombre);
		$objResponse->assign('div_saiu38consec', 'innerHTML', $html_saiu38consec);
		$saiu38id_nombre='';
		$html_saiu38id=html_oculto('saiu38id', $fila['saiu38id'], $saiu38id_nombre);
		$objResponse->assign('div_saiu38id', 'innerHTML', $html_saiu38id);
		$objResponse->assign('saiu38idestadorigen', 'value', $fila['saiu38idestadorigen']);
		$objResponse->assign('saiu38idestadofin', 'value', $fila['saiu38idestadofin']);
		$objResponse->assign('saiu38detalle', 'value', $fila['saiu38detalle']);
		$objResponse->assign('saiu38usuario', 'value', $fila['saiu38usuario']);
		$objResponse->assign('saiu38usuario_td', 'value', $saiu38usuario_td);
		$objResponse->assign('saiu38usuario_doc', 'value', $saiu38usuario_doc);
		$objResponse->assign('div_saiu38usuario', 'innerHTML', $saiu38usuario_nombre);
		$objResponse->assign('saiu38fecha', 'value', $fila['saiu38fecha']);
		list($iDia, $iMes, $iAgno)=fecha_DividirNumero($fila['saiu38fecha'], true);
		$objResponse->assign('saiu38fecha_dia', 'value', $iDia);
		$objResponse->assign('saiu38fecha_mes', 'value', $iMes);
		$objResponse->assign('saiu38fecha_agno', 'value', $iAgno);
		$objResponse->call("MensajeAlarmaV2('', 0)");
		$objResponse->call("verboton('belimina3038','block')");
		}else{
		if ($paso==1){
			$objResponse->assign('saiu38consec', 'value', $saiu38consec);
			}else{
			$objResponse->call('MensajeAlarmaV2("No se encontro el registro de referencia:'.$saiu38id.'", 0)');
			}
		}
	if ($bHayDb){
		$objDB->CerrarConexion();
		}
	return $objResponse;
	}
function f3038_Eliminar($aParametros){
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
	list($sError, $sDebugElimina)=f3038_db_Eliminar($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugElimina;
	$objResponse=new xajaxResponse();
	if ($sError==''){
		list($sDetalle, $sDebugTabla)=f3038_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugTabla;
		$objResponse->assign('div_f3038detalle', 'innerHTML', $sDetalle);
		$objResponse->call('limpiaf3038');
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
function f3038_HtmlTabla($aParametros){
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
	list($sDetalle, $sDebugTabla)=f3038_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f3038detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f3038_PintarLlaves($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	//if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	if (isset($APP->piel)==0){$APP->piel=1;}
	$iPiel=$APP->piel;
	$html_saiu38consec='<input id="saiu38consec" name="saiu38consec" type="text" value="" onchange="revisaf3038()" class="cuatro"/>';
	$html_saiu38id='<input id="saiu38id" name="saiu38id" type="hidden" value=""/>';
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_saiu38consec','innerHTML', $html_saiu38consec);
	$objResponse->assign('div_saiu38id','innerHTML', $html_saiu38id);
	return $objResponse;
	}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------
?>