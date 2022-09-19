<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2020 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.25.5 jueves, 13 de agosto de 2020
--- 3039 Cambios de estado
*/
function f3039_db_Guardar($valores, $objDB, $bDebug=false){
	$iCodModulo=3039;
	$bAudita[2]=false;
	$bAudita[3]=false;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3039='lg/lg_3039_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3039)){$mensajes_3039='lg/lg_3039_es.php';}
	require $mensajes_todas;
	require $mensajes_3039;
	$sError='';
	$sDebug='';
	$bInserta=false;
	$iAccion=3;
	if(!is_array($valores)){$valores=json_decode(str_replace('\"','"',$valores),true);}
	$saiu39idsolicitud=numeros_validar($valores[1]);
	$saiu39consec=numeros_validar($valores[2]);
	$saiu39id=numeros_validar($valores[3], true);
	$saiu39idetapa=numeros_validar($valores[4]);
	$saiu39idresponsable=numeros_validar($valores[5]);
	$saiu39idestadorigen=numeros_validar($valores[6]);
	$saiu39idestadofin=numeros_validar($valores[7]);
	$saiu39detalle=htmlspecialchars(trim($valores[8]));
	$saiu39usuario=numeros_validar($valores[9]);
	$saiu39fecha=$valores[10];
	$saiu39hora=numeros_validar($valores[11]);
	$saiu39minuto=numeros_validar($valores[12]);
	$saiu39correterminos=numeros_validar($valores[13]);
	$saiu39tiempousado=numeros_validar($valores[14]);
	$saiu39tiempocalusado=numeros_validar($valores[15]);
	//if ($saiu39idetapa==''){$saiu39idetapa=0;}
	//if ($saiu39idestadorigen==''){$saiu39idestadorigen=0;}
	//if ($saiu39idestadofin==''){$saiu39idestadofin=0;}
	//if ($saiu39hora==''){$saiu39hora=0;}
	//if ($saiu39minuto==''){$saiu39minuto=0;}
	//if ($saiu39correterminos==''){$saiu39correterminos=0;}
	//if ($saiu39tiempousado==''){$saiu39tiempousado=0;}
	//if ($saiu39tiempocalusado==''){$saiu39tiempocalusado=0;}
	$sSepara=', ';
	if ($saiu39tiempocalusado==''){$sError=$ERR['saiu39tiempocalusado'].$sSepara.$sError;}
	if ($saiu39tiempousado==''){$sError=$ERR['saiu39tiempousado'].$sSepara.$sError;}
	if ($saiu39correterminos==''){$sError=$ERR['saiu39correterminos'].$sSepara.$sError;}
	if ($saiu39minuto==''){$sError=$ERR['saiu39minuto'].$sSepara.$sError;}
	if ($saiu39hora==''){$sError=$ERR['saiu39hora'].$sSepara.$sError;}
	if ($saiu39fecha==0){
		//$saiu39fecha=fecha_DiaMod();
		$sError=$ERR['saiu39fecha'].$sSepara.$sError;
		}
	if ($saiu39usuario==0){$sError=$ERR['saiu39usuario'].$sSepara.$sError;}
	if ($saiu39detalle==''){$sError=$ERR['saiu39detalle'].$sSepara.$sError;}
	if ($saiu39idestadofin==''){$sError=$ERR['saiu39idestadofin'].$sSepara.$sError;}
	if ($saiu39idestadorigen==''){$sError=$ERR['saiu39idestadorigen'].$sSepara.$sError;}
	if ($saiu39idresponsable==0){$sError=$ERR['saiu39idresponsable'].$sSepara.$sError;}
	if ($saiu39idetapa==''){$sError=$ERR['saiu39idetapa'].$sSepara.$sError;}
	//if ($saiu39id==''){$sError=$ERR['saiu39id'].$sSepara.$sError;}//CONSECUTIVO
	//if ($saiu39consec==''){$sError=$ERR['saiu39consec'].$sSepara.$sError;}//CONSECUTIVO
	if ($saiu39idsolicitud==''){$sError=$ERR['saiu39idsolicitud'].$sSepara.$sError;}
	if ($sError==''){
		list($sError, $sInfo)=tercero_Bloqueado($saiu39usuario, $objDB);
		if ($sInfo!=''){$sError=$sError.'<br>'.sInfo;}
		}
	if ($sError==''){
		list($sError, $sInfo)=tercero_Bloqueado($saiu39idresponsable, $objDB);
		if ($sInfo!=''){$sError=$sError.'<br>'.sInfo;}
		}
	if ($sError==''){
		if ((int)$saiu39id==0){
			if ((int)$saiu39consec==0){
				$saiu39consec=tabla_consecutivo('saiu39cambioestmesa', 'saiu39consec', 'saiu39idsolicitud='.$saiu39idsolicitud.'', $objDB);
				if ($saiu39consec==-1){$sError=$objDB->serror;}
				}else{
				if (!seg_revisa_permiso($iCodModulo, 8, $objDB)){$sError=$ERR['8'];}
				}
			if ($sError==''){
				$sSQL='SELECT saiu39idsolicitud FROM saiu39cambioestmesa WHERE saiu39idsolicitud='.$saiu39idsolicitud.' AND saiu39consec='.$saiu39consec.'';
				$result=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($result)!=0){
					$sError=$ERR['existe'];
					}else{
					if (!seg_revisa_permiso($iCodModulo, 2, $objDB)){$sError=$ERR['2'];}
					}
				}
			if ($sError==''){
				$saiu39id=tabla_consecutivo('saiu39cambioestmesa', 'saiu39id', '', $objDB);
				if ($saiu39id==-1){$sError=$objDB->serror;}
				$bInserta=true;
				$iAccion=2;
				}
			}else{
			if (!seg_revisa_permiso($iCodModulo, 3, $objDB)){$sError=$ERR['3'];}
			}
		}
	if ($sError==''){
		if ($bInserta){
			$saiu39correterminos=0;
			$saiu39tiempousado=0;
			$saiu39tiempocalusado=0;
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
		//Si el campo saiu39detalle permite html quite la linea htmlspecialchars para el campo y habilite la siguiente linea:
		//$saiu39detalle=str_replace('"', '\"', $saiu39detalle);
		$saiu39detalle=str_replace('"', '\"', $saiu39detalle);
		if ($bInserta){
			$sCampos3039='saiu39idsolicitud, saiu39consec, saiu39id, saiu39idetapa, saiu39idresponsable, 
saiu39idestadorigen, saiu39idestadofin, saiu39detalle, saiu39usuario, saiu39fecha, 
saiu39hora, saiu39minuto, saiu39correterminos, saiu39tiempousado, saiu39tiempocalusado';
			$sValores3039=''.$saiu39idsolicitud.', '.$saiu39consec.', '.$saiu39id.', '.$saiu39idetapa.', "'.$saiu39idresponsable.'", 
'.$saiu39idestadorigen.', '.$saiu39idestadofin.', "'.$saiu39detalle.'", "'.$saiu39usuario.'", "'.$saiu39fecha.'", 
'.$saiu39hora.', '.$saiu39minuto.', '.$saiu39correterminos.', '.$saiu39tiempousado.', '.$saiu39tiempocalusado.'';
			if ($APP->utf8==1){
				$sSQL='INSERT INTO saiu39cambioestmesa ('.$sCampos3039.') VALUES ('.utf8_encode($sValores3039).');';
				}else{
				$sSQL='INSERT INTO saiu39cambioestmesa ('.$sCampos3039.') VALUES ('.$sValores3039.');';
				}
			if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Guardar 3039 '.$sSQL.'<br>';}
			$result=$objDB->ejecutasql($sSQL);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' [3039].<!-- '.$sSQL.' -->';
				}else{
				if ($bAudita[2]){
					seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 2, $saiu39id, $sSQL, $objDB);
					}
				}
			}else{
			$scampo3039[1]='saiu39idetapa';
			$scampo3039[2]='saiu39idresponsable';
			$scampo3039[3]='saiu39idestadorigen';
			$scampo3039[4]='saiu39idestadofin';
			$scampo3039[5]='saiu39detalle';
			$scampo3039[6]='saiu39fecha';
			$svr3039[1]=$saiu39idetapa;
			$svr3039[2]=$saiu39idresponsable;
			$svr3039[3]=$saiu39idestadorigen;
			$svr3039[4]=$saiu39idestadofin;
			$svr3039[5]=$saiu39detalle;
			$svr3039[6]=$saiu39fecha;
			$inumcampos=6;
			$sWhere='saiu39id='.$saiu39id.'';
			//$sWhere='saiu39idsolicitud='.$saiu39idsolicitud.' AND saiu39consec='.$saiu39consec.'';
			$sSQL='SELECT * FROM saiu39cambioestmesa WHERE '.$sWhere;
			$sdatos='';
			$bpasa=false;
			$result=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($result)>0){
				$filaorigen=$objDB->sf($result);
				for ($k=1;$k<=$inumcampos;$k++){
					if ($filaorigen[$scampo3039[$k]]!=$svr3039[$k]){
						if ($sdatos!=''){$sdatos=$sdatos.', ';}
						$sdatos=$sdatos.$scampo3039[$k].'="'.$svr3039[$k].'"';
						$bpasa=true;
						}
					}
				}
			if ($bpasa){
				if ($APP->utf8==1){
					$sSQL='UPDATE saiu39cambioestmesa SET '.utf8_encode($sdatos).' WHERE '.$sWhere.';';
					}else{
					$sSQL='UPDATE saiu39cambioestmesa SET '.$sdatos.' WHERE '.$sWhere.';';
					}
				$result=$objDB->ejecutasql($sSQL);
				if ($result==false){
					$sError=$ERR['falla_guardar'].' {Cambios de estado}. <!-- '.$sSQL.' -->';
					}else{
					if ($bAudita[3]){
						seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 3, $saiu39id, $sSQL, $objDB);
						}
					}
				}
			}
		}
	return array($sError, $iAccion, $saiu39id, $sDebug);
	}
function f3039_db_Eliminar($aParametros, $objDB, $bDebug=false){
	$iCodModulo=3039;
	$bAudita[4]=false;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3039='lg/lg_3039_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3039)){$mensajes_3039='lg/lg_3039_es.php';}
	require $mensajes_todas;
	require $mensajes_3039;
	$sError='';
	$sDebug='';
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$saiu39idsolicitud=numeros_validar($aParametros[1]);
	$saiu39consec=numeros_validar($aParametros[2]);
	$saiu39id=numeros_validar($aParametros[3]);
	if ($sError==''){
		if (!seg_revisa_permiso($iCodModulo, 4, $objDB)){
			$sError=$ERR['4'];
			}
		}
	if ($sError==''){
		$sSQL='SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=3039';
		$tablaor=$objDB->ejecutasql($sSQL);
		while ($filaor=$objDB->sf($tablaor)){
			$sSQL='SELECT '.$filaor['campoid'].' FROM '.$filaor['tabla'].' WHERE '.$filaor['camporev'].'='.$saiu39id.' LIMIT 0, 1';
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
		$sWhere='saiu39id='.$saiu39id.'';
		//$sWhere='saiu39idsolicitud='.$saiu39idsolicitud.' AND saiu39consec='.$saiu39consec.'';
		$sSQL='DELETE FROM saiu39cambioestmesa WHERE '.$sWhere.';';
		$result=$objDB->ejecutasql($sSQL);
		if ($result==false){
			$sError=$ERR['falla_eliminar'].' {3039 Cambios de estado}.<!-- '.$sSQL.' -->';
			}else{
			if ($bAudita[4]){
				seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $saiu39id, $sSQL, $objDB);
				}
			}
		}
	return array($sError, $sDebug);
	}
function f3039_TablaDetalleV2($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3039='lg/lg_3039_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3039)){$mensajes_3039='lg/lg_3039_es.php';}
	require $mensajes_todas;
	require $mensajes_3039;
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
		$sTabla39='saiu39cambioestmesa_'.$iAgno;
		if (!tabla_existe($sTabla39, $objDB)){
			$sLeyenda='No ha sido posible encontrar el origen de datos '.$sTabla39.'';
		}
	}
	if ($sLeyenda!=''){
		$sLeyenda='<div class="salto1px"></div>
		<div class="GrupoCamposAyuda">
		'.$sLeyenda.'
		<div class="salto1px"></div>
		</div>';
		return array($sLeyenda.'<input id="paginaf3039" name="paginaf3039" type="hidden" value="'.$pagina.'"/><input id="lppf3039" name="lppf3039" type="hidden" value="'.$lineastabla.'"/>', $sDebug);
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
	$sTitulos='Solicitud, Consec, Id, Etapa, Responsable, Estadorigen, Estadofin, Detalle, Usuario, Fecha, Hora, Minuto, Correterminos, Tiempousado, Tiempocalusado';
	$sSQL='SELECT TB.saiu39idsolicitud, TB.saiu39consec, TB.saiu39id, TB.saiu39idetapa, T5.unad11razonsocial AS C5_nombre, T6.saiu11nombre, T7.saiu11nombre, TB.saiu39detalle, T9.unad11razonsocial AS C9_nombre, TB.saiu39fecha, TB.saiu39hora, TB.saiu39minuto, TB.saiu39correterminos, TB.saiu39tiempousado, TB.saiu39tiempocalusado, TB.saiu39idresponsable, T5.unad11tipodoc AS C5_td, T5.unad11doc AS C5_doc, TB.saiu39idestadorigen, TB.saiu39idestadofin, TB.saiu39usuario, T9.unad11tipodoc AS C9_td, T9.unad11doc AS C9_doc 
	FROM '.$sTabla39.' AS TB, unad11terceros AS T5, saiu11estadosol AS T6, saiu11estadosol AS T7, unad11terceros AS T9 
	WHERE '.$sSQLadd1.' TB.saiu39idsolicitud='.$saiu28id.' AND TB.saiu39idresponsable=T5.unad11id AND TB.saiu39idestadorigen=T6.saiu11id AND TB.saiu39idestadofin=T7.saiu11id AND TB.saiu39usuario=T9.unad11id '.$sSQLadd.'
	ORDER BY TB.saiu39consec';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_3039" name="consulta_3039" type="hidden" value="'.$sSQLlista.'"/>
	<input id="titulos_3039" name="titulos_3039" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objDB->ejecutasql($sSQL);
	if ($tabladetalle==false){
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 3039: '.$sSQL.'<br>';}
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		//$sLeyenda=$sSQL;
		}else{
		$registros=$objDB->nf($tabladetalle);
		if ($registros==0){
			return array(utf8_encode($sErrConsulta.'<input id="paginaf3039" name="paginaf3039" type="hidden" value="'.$pagina.'"/><input id="lppf3039" name="lppf3039" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
	<td><b>'.$ETI['saiu39consec'].'</b></td>
	<td><b>'.$ETI['saiu39idetapa'].'</b></td>
	<td colspan="2"><b>'.$ETI['saiu39idresponsable'].'</b></td>
	<td><b>'.$ETI['saiu39idestadorigen'].'</b></td>
	<td><b>'.$ETI['saiu39idestadofin'].'</b></td>
	<td><b>'.$ETI['saiu39detalle'].'</b></td>
	<td colspan="2"><b>'.$ETI['saiu39usuario'].'</b></td>
	<td><b>'.$ETI['saiu39fecha'].'</b></td>
	<td><b>'.$ETI['saiu39hora'].'</b></td>
	<td><b>'.$ETI['saiu39correterminos'].'</b></td>
	<td><b>'.$ETI['saiu39tiempousado'].'</b></td>
	<td><b>'.$ETI['saiu39tiempocalusado'].'</b></td>
	<td align="right">
	'.html_paginador('paginaf3039', $registros, $lineastabla, $pagina, 'paginarf3039()').'
	'.html_lpp('lppf3039', $lineastabla, 'paginarf3039()').'
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
		$et_saiu39consec=$sPrefijo.$filadet['saiu39consec'].$sSufijo;
		$et_saiu39idetapa=$sPrefijo.$filadet['saiu39idetapa'].$sSufijo;
		$et_saiu39idresponsable_doc='';
		$et_saiu39idresponsable_nombre='';
		if ($filadet['saiu39idresponsable']!=0){
			$et_saiu39idresponsable_doc=$sPrefijo.$filadet['C5_td'].' '.$filadet['C5_doc'].$sSufijo;
			$et_saiu39idresponsable_nombre=$sPrefijo.cadena_notildes($filadet['C5_nombre']).$sSufijo;
			}
		$et_saiu39idestadorigen=$sPrefijo.cadena_notildes($filadet['saiu11nombre']).$sSufijo;
		$et_saiu39idestadofin=$sPrefijo.cadena_notildes($filadet['saiu11nombre']).$sSufijo;
		$et_saiu39detalle=$sPrefijo.cadena_notildes($filadet['saiu39detalle']).$sSufijo;
		$et_saiu39usuario_doc='';
		$et_saiu39usuario_nombre='';
		if ($filadet['saiu39usuario']!=0){
			$et_saiu39usuario_doc=$sPrefijo.$filadet['C9_td'].' '.$filadet['C9_doc'].$sSufijo;
			$et_saiu39usuario_nombre=$sPrefijo.cadena_notildes($filadet['C9_nombre']).$sSufijo;
			}
		$et_saiu39fecha='';
		if ($filadet['saiu39fecha']!=0){$et_saiu39fecha=$sPrefijo.fecha_desdenumero($filadet['saiu39fecha']).$sSufijo;}
		$et_saiu39hora=html_TablaHoraMin($filadet['saiu39hora'], $filadet['saiu39minuto']);
		$et_saiu39minuto=$sPrefijo.$filadet['saiu39minuto'].$sSufijo;
		$et_saiu39correterminos=$sPrefijo.$filadet['saiu39correterminos'].$sSufijo;
		$et_saiu39tiempousado=$sPrefijo.$filadet['saiu39tiempousado'].$sSufijo;
		$et_saiu39tiempocalusado=$sPrefijo.$filadet['saiu39tiempocalusado'].$sSufijo;
		if ($bAbierta){
			$sLink='<a href="javascript:cargaridf3039('.$filadet['saiu39id'].')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$res=$res.'<tr'.$sClass.'>
		<td>'.$et_saiu39consec.'</td>
		<td>'.$et_saiu39idetapa.'</td>
		<td>'.$et_saiu39idresponsable_doc.'</td>
		<td>'.$et_saiu39idresponsable_nombre.'</td>
		<td>'.$et_saiu39idestadorigen.'</td>
		<td>'.$et_saiu39idestadofin.'</td>
		<td>'.$et_saiu39detalle.'</td>
		<td>'.$et_saiu39usuario_doc.'</td>
		<td>'.$et_saiu39usuario_nombre.'</td>
		<td>'.$et_saiu39fecha.'</td>
		<td>'.$et_saiu39hora.'</td>
		<td>'.$et_saiu39minuto.'</td>
		<td>'.$et_saiu39correterminos.'</td>
		<td>'.$et_saiu39tiempousado.'</td>
		<td>'.$et_saiu39tiempocalusado.'</td>
		<td>'.$sLink.'</td>
		</tr>';
		}
	$res=$res.'</table>';
	$objDB->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
	}
// -- 3039 Cambios de estado XAJAX 
function f3039_Guardar($valores, $aParametros){
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
		list($sError, $iAccion, $saiu39id, $sDebugGuardar)=f3039_db_Guardar($valores, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugGuardar;
		}
	$objResponse=new xajaxResponse();
	if ($sError==''){
		list($sdetalle, $sDebugTabla)=f3039_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugTabla;
		$objResponse->assign('div_f3039detalle', 'innerHTML', $sdetalle);
		//if ($iAccion==2){
			//$objResponse->call('cargaridf3039('.$saiu39id.')');
			//}else{
			$objResponse->call('limpiaf3039');
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
function f3039_Traer($aParametros){
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
	$iAgno=numeros_validar($aParametros[98]);
	if ($iAgno==''){
		$sError='<b>No se ha definido un a&ntilde;o</b>';
	}else{
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		$bHayDb=true;
		$sTabla39='saiu39cambioestmesa_'.$iAgno;
		if (!tabla_existe($sTabla29, $objDB)){
			$sError='No ha sido posible encontrar el origen de datos '.$sTabla39;
		}
	}
	if ($paso==1){
		$saiu39idsolicitud=numeros_validar($aParametros[1]);
		$saiu39consec=numeros_validar($aParametros[2]);
		if (($saiu39idsolicitud!='')&&($saiu39consec!='')){$besta=true;}
		}else{
		$saiu39id=$aParametros[103];
		if ((int)$saiu39id!=0){$besta=true;}
		}
	if ($besta){
		$besta=false;
		$sSQLcondi='';
		if ($paso==1){
			$sSQLcondi=$sSQLcondi.'saiu39idsolicitud='.$saiu39idsolicitud.' AND saiu39consec='.$saiu39consec.'';
			}else{
			$sSQLcondi=$sSQLcondi.'saiu39id='.$saiu39id.'';
			}
		$sSQL='SELECT * FROM '.$sTabla39.' WHERE '.$sSQLcondi;
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
		$saiu39idresponsable_id=(int)$fila['saiu39idresponsable'];
		$saiu39idresponsable_td=$APP->tipo_doc;
		$saiu39idresponsable_doc='';
		$saiu39idresponsable_nombre='';
		if ($saiu39idresponsable_id!=0){
			list($saiu39idresponsable_nombre, $saiu39idresponsable_id, $saiu39idresponsable_td, $saiu39idresponsable_doc)=html_tercero($saiu39idresponsable_td, $saiu39idresponsable_doc, $saiu39idresponsable_id, 0, $objDB);
			}
		$saiu39usuario_id=(int)$fila['saiu39usuario'];
		$saiu39usuario_td=$APP->tipo_doc;
		$saiu39usuario_doc='';
		$saiu39usuario_nombre='';
		if ($saiu39usuario_id!=0){
			list($saiu39usuario_nombre, $saiu39usuario_id, $saiu39usuario_td, $saiu39usuario_doc)=html_tercero($saiu39usuario_td, $saiu39usuario_doc, $saiu39usuario_id, 0, $objDB);
			}
		$saiu39consec_nombre='';
		$html_saiu39consec=html_oculto('saiu39consec', $fila['saiu39consec'], $saiu39consec_nombre);
		$objResponse->assign('div_saiu39consec', 'innerHTML', $html_saiu39consec);
		$saiu39id_nombre='';
		$html_saiu39id=html_oculto('saiu39id', $fila['saiu39id'], $saiu39id_nombre);
		$objResponse->assign('div_saiu39id', 'innerHTML', $html_saiu39id);
		$objResponse->assign('saiu39idetapa', 'value', $fila['saiu39idetapa']);
		$objResponse->assign('saiu39idresponsable', 'value', $fila['saiu39idresponsable']);
		$objResponse->assign('saiu39idresponsable_td', 'value', $saiu39idresponsable_td);
		$objResponse->assign('saiu39idresponsable_doc', 'value', $saiu39idresponsable_doc);
		$objResponse->assign('div_saiu39idresponsable', 'innerHTML', $saiu39idresponsable_nombre);
		$objResponse->assign('saiu39idestadorigen', 'value', $fila['saiu39idestadorigen']);
		$objResponse->assign('saiu39idestadofin', 'value', $fila['saiu39idestadofin']);
		$objResponse->assign('saiu39detalle', 'value', $fila['saiu39detalle']);
		$bOculto=true;
		$html_saiu39usuario_llaves=html_DivTerceroV2('saiu39usuario', $saiu39usuario_td, $saiu39usuario_doc, $bOculto, $saiu39usuario_id, $ETI['ing_doc']);
		$objResponse->assign('saiu39usuario', 'value', $saiu39usuario_id);
		$objResponse->assign('div_saiu39usuario_llaves', 'innerHTML', $html_saiu39usuario_llaves);
		$objResponse->assign('div_saiu39usuario', 'innerHTML', $saiu39usuario_nombre);
		$objResponse->assign('saiu39fecha', 'value', $fila['saiu39fecha']);
		list($iDia, $iMes, $iAgno)=fecha_DividirNumero($fila['saiu39fecha'], true);
		$objResponse->assign('saiu39fecha_dia', 'value', $iDia);
		$objResponse->assign('saiu39fecha_mes', 'value', $iMes);
		$objResponse->assign('saiu39fecha_agno', 'value', $iAgno);
		$html_saiu39hora=html_HoraMin('saiu39hora', $fila['saiu39hora'], 'saiu39minuto', $fila['saiu39minuto'], true);
		$objResponse->assign('div_saiu39hora', 'innerHTML', $html_saiu39hora);
		list($saiu39correterminos_nombre, $serror_det)=tabla_campoxid('','','', $fila['saiu39correterminos'],'{'.$ETI['msg_sindato'].'}', $objDB);
		$html_saiu39correterminos=html_oculto('saiu39correterminos', $fila['saiu39correterminos'], $saiu39correterminos_nombre);
		$objResponse->assign('div_saiu39correterminos', 'innerHTML', $html_saiu39correterminos);
		$saiu39tiempousado_eti=$fila['saiu39tiempousado'];
		$html_saiu39tiempousado=html_oculto('saiu39tiempousado', $fila['saiu39tiempousado'], $saiu39tiempousado_eti);
		$objResponse->assign('div_saiu39tiempousado', 'innerHTML', $html_saiu39tiempousado);
		$saiu39tiempocalusado_eti=$fila['saiu39tiempocalusado'];
		$html_saiu39tiempocalusado=html_oculto('saiu39tiempocalusado', $fila['saiu39tiempocalusado'], $saiu39tiempocalusado_eti);
		$objResponse->assign('div_saiu39tiempocalusado', 'innerHTML', $html_saiu39tiempocalusado);
		$objResponse->call("MensajeAlarmaV2('', 0)");
		$objResponse->call("verboton('belimina3039','block')");
		}else{
		if ($paso==1){
			$objResponse->assign('saiu39consec', 'value', $saiu39consec);
			}else{
			$objResponse->call('MensajeAlarmaV2("No se encontro el registro de referencia:'.$saiu39id.'", 0)');
			}
		}
	if ($bHayDb){
		$objDB->CerrarConexion();
		}
	return $objResponse;
	}
function f3039_Eliminar($aParametros){
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
	list($sError, $sDebugElimina)=f3039_db_Eliminar($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugElimina;
	$objResponse=new xajaxResponse();
	if ($sError==''){
		list($sDetalle, $sDebugTabla)=f3039_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugTabla;
		$objResponse->assign('div_f3039detalle', 'innerHTML', $sDetalle);
		$objResponse->call('limpiaf3039');
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
function f3039_HtmlTabla($aParametros){
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
	list($sDetalle, $sDebugTabla)=f3039_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f3039detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f3039_PintarLlaves($aParametros){
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
	$html_saiu39consec='<input id="saiu39consec" name="saiu39consec" type="text" value="" onchange="revisaf3039()" class="cuatro"/>';
	$html_saiu39id='<input id="saiu39id" name="saiu39id" type="hidden" value=""/>';
	list($saiu39usuario_rs, $saiu39usuario, $saiu39usuario_td, $saiu39usuario_doc)=html_tercero('CC', '', $_SESSION['unad_id_tercero'], 0, $objDB);
	$html_saiu39usuario_llaves=html_DivTerceroV2('saiu39usuario', $saiu39usuario_td, $saiu39usuario_doc, true, 0, $ETI['ing_doc']);
	$html_saiu39hora=html_HoraMin('saiu39hora', fecha_hora(), 'saiu39minuto', fecha_minuto(), true);
	$html_saiu39correterminos=f3039_HTMLComboV2_saiu39correterminos($objDB, $objCombos, 0);
	$html_saiu39tiempousado=html_oculto('saiu39tiempousado', '');
	$html_saiu39tiempocalusado=html_oculto('saiu39tiempocalusado', '');
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_saiu39consec','innerHTML', $html_saiu39consec);
	$objResponse->assign('div_saiu39id','innerHTML', $html_saiu39id);
	$objResponse->assign('saiu39usuario','value', $saiu39usuario);
	$objResponse->assign('div_saiu39usuario_llaves','innerHTML', $html_saiu39usuario_llaves);
	$objResponse->assign('div_saiu39usuario','innerHTML', $saiu39usuario_rs);
	$objResponse->assign('div_saiu39hora','innerHTML', $html_saiu39hora);
	$objResponse->assign('div_saiu39correterminos','innerHTML', $html_saiu39correterminos);
	$objResponse->assign('div_saiu39tiempousado','innerHTML', $html_saiu39tiempousado);
	$objResponse->assign('div_saiu39tiempocalusado','innerHTML', $html_saiu39tiempocalusado);
	return $objResponse;
	}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------
?>