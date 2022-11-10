<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2021 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.25.10c lunes, 3 de mayo de 2021
--- 3048 Anotaciones
*/
function f3048_db_Guardar($valores, $objDB, $bDebug=false){
	$iCodModulo=3048;
	$bAudita[2]=false;
	$bAudita[3]=false;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3048='lg/lg_3048_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3048)){$mensajes_3048='lg/lg_3048_es.php';}
	require $mensajes_todas;
	require $mensajes_3048;
	$sError='';
	$sDebug='';
	$bInserta=false;
	$iAccion=3;
	if(!is_array($valores)){$valores=json_decode(str_replace('\"','"',$valores),true);}
	if (isset($valores[98])==0){$valores[98]=0;}
	$saiu48idtramite=numeros_validar($valores[1]);
	$saiu48consec=numeros_validar($valores[2]);
	$saiu48id=numeros_validar($valores[3], true);
	$saiu48visiblealinteresado=numeros_validar($valores[4]);
	$saiu48anotacion=htmlspecialchars(trim($valores[5]));
	$saiu48idusuario=numeros_validar($valores[6]);
	$saiu48fecha=$valores[7];
	$saiu48hora=numeros_validar($valores[8]);
	$saiu48minuto=numeros_validar($valores[9]);
	$iAgno=$valores[98];
	$sTabla47='saiu47tramites_'.$iAgno;
	$sTabla48='saiu48anotaciones_'.$iAgno;
	$bEsElPropietario=false;
	if (!$objDB->bexistetabla($sTabla47)){
		$sError='No ha sido posible acceder al contenedor de datos';
		}else{
		//Verificamos que sea el propietario para no pedirle permisos
		$sSQL='SELECT saiu47estado, saiu47idsolicitante FROM '.$sTabla47.' WHERE saiu47id='.$saiu48idtramite;
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$fila=$objDB->sf($tabla);
			if ($fila['saiu47idsolicitante']==$_SESSION['unad_id_tercero']){$bEsElPropietario=true;}
			}
		}
	//if ($saiu48visiblealinteresado==''){$saiu48visiblealinteresado=0;}
	//if ($saiu48hora==''){$saiu48hora=0;}
	//if ($saiu48minuto==''){$saiu48minuto=0;}
	$sSepara=', ';
	/*
	if ($saiu48minuto==''){$sError=$ERR['saiu48minuto'].$sSepara.$sError;}
	if ($saiu48hora==''){$sError=$ERR['saiu48hora'].$sSepara.$sError;}
	if ($saiu48idusuario==0){$sError=$ERR['saiu48idusuario'].$sSepara.$sError;}
	*/
	if ($saiu48anotacion==''){$sError=$ERR['saiu48anotacion'].$sSepara.$sError;}
	if ($saiu48visiblealinteresado==''){$sError=$ERR['saiu48visiblealinteresado'].$sSepara.$sError;}
	//if ($saiu48id==''){$sError=$ERR['saiu48id'].$sSepara.$sError;}//CONSECUTIVO
	//if ($saiu48consec==''){$sError=$ERR['saiu48consec'].$sSepara.$sError;}//CONSECUTIVO
	if ($saiu48idtramite==''){$sError=$ERR['saiu48idtramite'].$sSepara.$sError;}
	/*
	if ($sError==''){
		list($sError, $sInfo)=tercero_Bloqueado($saiu48idusuario, $objDB);
		if ($sInfo!=''){$sError=$sError.'<br>'.$sInfo;}
		}
	*/
	if ($sError==''){
		if ((int)$saiu48id==0){
			if ((int)$saiu48consec==0){
				$saiu48consec=tabla_consecutivo($sTabla48, 'saiu48consec', 'saiu48idtramite='.$saiu48idtramite.'', $objDB);
				if ($saiu48consec==-1){$sError=$objDB->serror;}
				}else{
				if (!seg_revisa_permiso($iCodModulo, 8, $objDB)){$sError=$ERR['8'];}
				}
			if ($sError==''){
				$sSQL='SELECT saiu48idtramite FROM '.$sTabla48.' WHERE saiu48idtramite='.$saiu48idtramite.' AND saiu48consec='.$saiu48consec.'';
				$result=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($result)!=0){
					$sError=$ERR['existe'];
					}else{
					if (!$bEsElPropietario){
						if (!seg_revisa_permiso($iCodModulo, 2, $objDB)){$sError=$ERR['2'];}
						}
					}
				}
			if ($sError==''){
				$saiu48id=tabla_consecutivo($sTabla48, 'saiu48id', '', $objDB);
				if ($saiu48id==-1){$sError=$objDB->serror;}
				$bInserta=true;
				$iAccion=2;
				}
			}else{
			if (!$bEsElPropietario){
				if (!seg_revisa_permiso($iCodModulo, 3, $objDB)){$sError=$ERR['3'];}
				}
			}
		}
	if ($sError==''){
		if ($bInserta){
			$saiu48idusuario=$_SESSION['unad_id_tercero'];
			$saiu48fecha=fecha_DiaMod();
			$saiu48hora=fecha_hora();
			$saiu48minuto=fecha_minuto();
			}
		}
	if ($sError==''){
		//Si el campo saiu48anotacion permite html quite la linea htmlspecialchars para el campo y habilite la siguiente linea:
		//$saiu48anotacion=str_replace('"', '\"', $saiu48anotacion);
		$saiu48anotacion=str_replace('"', '\"', $saiu48anotacion);
		if ($bInserta){
			$sCampos3048='saiu48idtramite, saiu48consec, saiu48id, saiu48visiblealinteresado, saiu48anotacion, 
			saiu48idusuario, saiu48fecha, saiu48hora, saiu48minuto';
			$sValores3048=''.$saiu48idtramite.', '.$saiu48consec.', '.$saiu48id.', '.$saiu48visiblealinteresado.', "'.$saiu48anotacion.'", 
			"'.$saiu48idusuario.'", "'.$saiu48fecha.'", '.$saiu48hora.', '.$saiu48minuto.'';
			if ($APP->utf8==1){
				$sSQL='INSERT INTO '.$sTabla48.' ('.$sCampos3048.') VALUES ('.utf8_encode($sValores3048).');';
				}else{
				$sSQL='INSERT INTO '.$sTabla48.' ('.$sCampos3048.') VALUES ('.$sValores3048.');';
				}
			if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Guardar 3048 '.$sSQL.'<br>';}
			$result=$objDB->ejecutasql($sSQL);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' [3048].<!-- '.$sSQL.' -->';
				}else{
				if ($bAudita[2]){
					seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 2, $saiu48id, $sSQL, $objDB);
					}
				}
			}else{
			$scampo3048[1]='saiu48visiblealinteresado';
			$scampo3048[2]='saiu48anotacion';
			$svr3048[1]=$saiu48visiblealinteresado;
			$svr3048[2]=$saiu48anotacion;
			$inumcampos=2;
			$sWhere='saiu48id='.$saiu48id.'';
			//$sWhere='saiu48idtramite='.$saiu48idtramite.' AND saiu48consec='.$saiu48consec.'';
			$sSQL='SELECT * FROM '.$sTabla48.' WHERE '.$sWhere;
			$sdatos='';
			$bpasa=false;
			$result=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($result)>0){
				$filaorigen=$objDB->sf($result);
				for ($k=1;$k<=$inumcampos;$k++){
					if ($filaorigen[$scampo3048[$k]]!=$svr3048[$k]){
						if ($sdatos!=''){$sdatos=$sdatos.', ';}
						$sdatos=$sdatos.$scampo3048[$k].'="'.$svr3048[$k].'"';
						$bpasa=true;
						}
					}
				}
			if ($bpasa){
				if ($APP->utf8==1){
					$sSQL='UPDATE '.$sTabla48.' SET '.utf8_encode($sdatos).' WHERE '.$sWhere.';';
					}else{
					$sSQL='UPDATE '.$sTabla48.' SET '.$sdatos.' WHERE '.$sWhere.';';
					}
				$result=$objDB->ejecutasql($sSQL);
				if ($result==false){
					$sError=$ERR['falla_guardar'].' {Anotaciones}. <!-- '.$sSQL.' -->';
					}else{
					if ($bAudita[3]){
						seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 3, $saiu48id, $sSQL, $objDB);
						}
					}
				}
			}
		}
	return array($sError, $iAccion, $saiu48id, $sDebug);
	}
function f3048_db_Eliminar($aParametros, $objDB, $bDebug=false){
	$iCodModulo=3048;
	$bAudita[4]=false;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3048='lg/lg_3048_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3048)){$mensajes_3048='lg/lg_3048_es.php';}
	require $mensajes_todas;
	require $mensajes_3048;
	$sError='';
	$sDebug='';
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$saiu48idtramite=numeros_validar($aParametros[1]);
	$saiu48consec=numeros_validar($aParametros[2]);
	$saiu48id=numeros_validar($aParametros[3]);
	$iAgno=$aParametros[98];
	$sTabla47='saiu47tramites_'.$iAgno;
	$sTabla48='saiu48anotaciones_'.$iAgno;
	if (!$objDB->bexistetabla($sTabla47)){
		$sError='No ha sido posible acceder al contenedor de datos';
		}
	if ($sError==''){
		if (!seg_revisa_permiso($iCodModulo, 4, $objDB)){
			$sError=$ERR['4'];
			}
		}
	if ($sError==''){
		$sSQL='SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=3048';
		$tablaor=$objDB->ejecutasql($sSQL);
		while ($filaor=$objDB->sf($tablaor)){
			$sSQL='SELECT '.$filaor['campoid'].' FROM '.$filaor['tabla'].' WHERE '.$filaor['camporev'].'='.$saiu48id.' LIMIT 0, 1';
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
		$sWhere='saiu48id='.$saiu48id.'';
		//$sWhere='saiu48idtramite='.$saiu48idtramite.' AND saiu48consec='.$saiu48consec.'';
		$sSQL='DELETE FROM '.$sTabla48.' WHERE '.$sWhere.';';
		$result=$objDB->ejecutasql($sSQL);
		if ($result==false){
			$sError=$ERR['falla_eliminar'].' {3048 Anotaciones}.<!-- '.$sSQL.' -->';
			}else{
			if ($bAudita[4]){
				seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $saiu48id, $sSQL, $objDB);
				}
			}
		}
	return array($sError, $sDebug);
	}
function f3048_TablaDetalleV2($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3048='lg/lg_3048_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3048)){$mensajes_3048='lg/lg_3048_es.php';}
	require $mensajes_todas;
	require $mensajes_3048;
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	if (isset($aParametros[0])==0){$aParametros[0]=-1;}
	if (isset($aParametros[98])==0){$aParametros[98]=0;}
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
	$saiu47id=$aParametros[0];
	$iAgno=$aParametros[98];
	$pagina=$aParametros[101];
	$lineastabla=$aParametros[102];
	$bAbierta=true;
	$saiu47idsolicitante=0;
	$sLeyenda='';
	$sTabla47='saiu47tramites_'.$iAgno;
	$sTabla48='saiu48anotaciones_'.$iAgno;
	if (!$objDB->bexistetabla($sTabla47)){
		$sLeyenda='No ha sido posible acceder al contenedor de datos';
		}else{
		$sSQL='SELECT saiu47idsolicitante FROM '.$sTabla47.' WHERE saiu47id='.$saiu47id;
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$fila=$objDB->sf($tabla);
			$saiu47idsolicitante=$fila['saiu47idsolicitante'];
			}
		}
	if ($sLeyenda!=''){
		$sLeyenda='<div class="salto1px"></div>
		<div class="GrupoCamposAyuda">
		'.$sLeyenda.'
		<div class="salto1px"></div>
		</div>';
		return array($sLeyenda.'<input id="paginaf3048" name="paginaf3048" type="hidden" value="'.$pagina.'"/><input id="lppf3048" name="lppf3048" type="hidden" value="'.$lineastabla.'"/>', $sDebug);
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
	$sTitulos='Tramite, Consec, Id, Visiblealinteresado, Anotacion, Usuario, Fecha, Hora, Minuto';
	$sSQL='SELECT TB.saiu48idtramite, TB.saiu48consec, TB.saiu48id, TB.saiu48visiblealinteresado, TB.saiu48anotacion, T6.unad11razonsocial AS C6_nombre, TB.saiu48fecha, TB.saiu48hora, TB.saiu48minuto, TB.saiu48idusuario, T6.unad11tipodoc AS C6_td, T6.unad11doc AS C6_doc 
	FROM '.$sTabla48.' AS TB, unad11terceros AS T6 
	WHERE '.$sSQLadd1.' TB.saiu48idtramite='.$saiu47id.' AND TB.saiu48idusuario=T6.unad11id '.$sSQLadd.'
	ORDER BY TB.saiu48consec DESC';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_3048" name="consulta_3048" type="hidden" value="'.$sSQLlista.'"/>
	<input id="titulos_3048" name="titulos_3048" type="hidden" value="'.$sTitulos.'"/>';
	if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 3048: '.$sSQL.'<br>';}
	$tabladetalle=$objDB->ejecutasql($sSQL);
	if ($tabladetalle==false){
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		//$sLeyenda=$sSQL;
		}else{
		$registros=$objDB->nf($tabladetalle);
		if ($registros==0){
			return array(utf8_encode($sErrConsulta.'<input id="paginaf3048" name="paginaf3048" type="hidden" value="'.$pagina.'"/><input id="lppf3048" name="lppf3048" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
	<td><b>'.$ETI['saiu48consec'].'</b></td>
	<td colspan="2"><b>'.$ETI['saiu48idusuario'].'</b></td>
	<td colspan="2"><b>'.$ETI['saiu48fecha'].'</b></td>
	<td><b>'.$ETI['msg_visible'].'</b></td>
	<td colspan="2" align="right">
	'.html_paginador('paginaf3048', $registros, $lineastabla, $pagina, 'paginarf3048()').'
	'.html_lpp('lppf3048', $lineastabla, 'paginarf3048()').'
	</td>
	</tr></thead>';
	$tlinea=1;
	while($filadet=$objDB->sf($tabladetalle)){
		$sPrefijo='';
		$sSufijo='';
		$sClass=' class="resaltetabla"';
		$sLink='';
		$sLinkNotifica='';
		if(($tlinea%2)!=0){$sClass='';}
		$tlinea++;
		$sPrefijo='<b>';
		$sSufijo='</b>';
		$et_saiu48visiblealinteresado=$sPrefijo.$ETI['si'].$sSufijo;
		if ($filadet['saiu48visiblealinteresado']==0){
			$sPrefijo='';
			$sSufijo='';
			$et_saiu48visiblealinteresado=$sPrefijo.$ETI['no'].$sSufijo;
			}
		$et_saiu48consec=$sPrefijo.$filadet['saiu48consec'].$sSufijo;
		$et_saiu48idusuario_doc='';
		$et_saiu48idusuario_nombre='';
		if ($filadet['saiu48idusuario']!=0){
			$et_saiu48idusuario_doc=$sPrefijo.$filadet['C6_td'].' '.$filadet['C6_doc'].$sSufijo;
			$et_saiu48idusuario_nombre=$sPrefijo.cadena_notildes($filadet['C6_nombre']).$sSufijo;
			}
		$et_saiu48fecha='';
		$et_saiu48hora='';
		if ($filadet['saiu48fecha']!=0){
			$et_saiu48fecha=$sPrefijo.fecha_desdenumero($filadet['saiu48fecha']).$sSufijo;
			$et_saiu48hora=$sPrefijo.html_TablaHoraMin($filadet['saiu48hora'], $filadet['saiu48minuto']).$sSufijo;
			}
		if ($bAbierta){
			$sLink='<a href="javascript:cargaridf3048('.$filadet['saiu48id'].')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		if ($saiu47idsolicitante!=$filadet['saiu48idusuario']){
			$sLinkNotifica='<a href="javascript:notificanota('.$filadet['saiu48id'].')" class="lnkresalte">'.'Notificar'.'</a>';
			}
		$res=$res.'<tr'.$sClass.'>
		<td>'.$et_saiu48consec.'</td>
		<td>'.$et_saiu48idusuario_doc.'</td>
		<td>'.$et_saiu48idusuario_nombre.'</td>
		<td>'.$et_saiu48fecha.'</td>
		<td>'.$et_saiu48hora.'</td>
		<td>'.$et_saiu48visiblealinteresado.'</td>
		<td>'.$sLink.'</td>
		<td>'.$sLinkNotifica.'</td>
		</tr>';
		if (trim($filadet['saiu48anotacion'])!=''){
			//<td><b>'.$ETI['saiu48anotacion'].'</b></td>
			$et_saiu48anotacion=$sPrefijo.cadena_notildes($filadet['saiu48anotacion']).$sSufijo;
			$res=$res.'<tr'.$sClass.'>
			<td></td>
			<td colspan="6">'.$et_saiu48anotacion.'</td>
			</tr>';
			}
		}
	$res=$res.'</table>
	<div class="salto5px"></div>
	</div>';
	$objDB->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
	}
// -- 3048 Anotaciones XAJAX 
function f3048_Guardar($valores, $aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$sError='';
	$bDebug=false;
	$bDesdeCampus=false;
	$sDebug='';
	$bHayDb=false;
	$opts=$aParametros;
	if(!is_array($opts)){$opts=json_decode(str_replace('\"','"',$opts),true);}
	if (isset($opts[99])!=0){if ($opts[99]==1){$bDebug=true;}}
	if (isset($opts[97])!=0){if ($opts[97]==1){$bDesdeCampus=true;}}
	//if(!is_array($valores)){$datos=json_decode(str_replace('\"','"',$valores),true);}
	//if (isset($datos[0])==0){$datos[0]='';}
	//if ($datos[0]==''){$sError=$ERR[''];}
	if ($sError==''){
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		$bHayDb=true;
		list($sError, $iAccion, $saiu48id, $sDebugGuardar)=f3048_db_Guardar($valores, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugGuardar;
		}
	$objResponse=new xajaxResponse();
	if ($sError==''){
		if ($bDesdeCampus){
			list($sdetalle, $sDebugTabla)=f3048_TablaDetalleV2Campus($aParametros, $objDB, $bDebug);
			}else{
			list($sdetalle, $sDebugTabla)=f3048_TablaDetalleV2($aParametros, $objDB, $bDebug);
			}
		$sDebug=$sDebug.$sDebugTabla;
		$objResponse->assign('div_f3048detalle', 'innerHTML', $sdetalle);
		//if ($iAccion==2){
			//$objResponse->call('cargaridf3048('.$saiu48id.')');
			//}else{
			$objResponse->call('limpiaf3048');
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
function f3048_Traer($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$sError='';
	$bHayDb=false;
	$besta=false;
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	if (isset($aParametros[98])==0){$aParametros[98]=0;}
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$bHayDb=true;
	$paso=$aParametros[0];
	$iAgno=$aParametros[98];
	$sTabla47='saiu47tramites_'.$iAgno;
	$sTabla48='saiu48anotaciones_'.$iAgno;
	if (!$objDB->bexistetabla($sTabla47)){
		$sError='No ha sido posible acceder al contenedor de datos';
		}
	if ($sError==''){
		if ($paso==1){
			$saiu48idtramite=numeros_validar($aParametros[1]);
			$saiu48consec=numeros_validar($aParametros[2]);
			if (($saiu48idtramite!='')&&($saiu48consec!='')){$besta=true;}
			}else{
			$saiu48id=$aParametros[103];
			if ((int)$saiu48id!=0){$besta=true;}
			}
		}
	if ($besta){
		$besta=false;
		$sSQLcondi='';
		if ($paso==1){
			$sSQLcondi=$sSQLcondi.'saiu48idtramite='.$saiu48idtramite.' AND saiu48consec='.$saiu48consec.'';
			}else{
			$sSQLcondi=$sSQLcondi.'saiu48id='.$saiu48id.'';
			}
		$sSQL='SELECT * FROM '.$sTabla48.' WHERE '.$sSQLcondi;
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$fila=$objDB->sf($tabla);
			$besta=true;
			}
		}
	$objResponse=new xajaxResponse();
	if ($sError==''){
		if ($besta){
			if (isset($APP->piel)==0){$APP->piel=1;}
			$iPiel=$APP->piel;
			$saiu48idusuario_id=(int)$fila['saiu48idusuario'];
			$saiu48idusuario_td=$APP->tipo_doc;
			$saiu48idusuario_doc='';
			$saiu48idusuario_nombre='';
			if ($saiu48idusuario_id!=0){
				list($saiu48idusuario_nombre, $saiu48idusuario_id, $saiu48idusuario_td, $saiu48idusuario_doc)=html_tercero($saiu48idusuario_td, $saiu48idusuario_doc, $saiu48idusuario_id, 0, $objDB);
				}
			$bOculto=true;
			$html_saiu48idusuario=html_DivTerceroV2('saiu48idusuario', $saiu48idusuario_td, $saiu48idusuario_doc, $bOculto, 0, $ETI['ing_doc']);
			$saiu48consec_nombre='';
			$html_saiu48consec=html_oculto('saiu48consec', $fila['saiu48consec'], $saiu48consec_nombre);
			$objResponse->assign('div_saiu48consec', 'innerHTML', $html_saiu48consec);
			$saiu48id_nombre='';
			$html_saiu48id=html_oculto('saiu48id', $fila['saiu48id'], $saiu48id_nombre);
			$objResponse->assign('div_saiu48id', 'innerHTML', $html_saiu48id);
			$objResponse->assign('saiu48visiblealinteresado', 'value', $fila['saiu48visiblealinteresado']);
			$objResponse->assign('saiu48anotacion', 'value', $fila['saiu48anotacion']);
			$objResponse->assign('saiu48idusuario', 'value', $fila['saiu48idusuario']);
			/*
			$objResponse->assign('saiu48idusuario_td', 'value', $saiu48idusuario_td);
			$objResponse->assign('saiu48idusuario_doc', 'value', $saiu48idusuario_doc);
			*/
			$objResponse->assign('div_saiu48idusuario_llaves', 'innerHTML', $html_saiu48idusuario);
			$objResponse->assign('div_saiu48idusuario', 'innerHTML', $saiu48idusuario_nombre);
			$html_saiu48fecha=html_oculto('saiu48fecha', $fila['saiu48fecha'], fecha_desdenumero($fila['saiu48fecha']));
			$objResponse->assign('div_saiu48fecha', 'innerHTML', $html_saiu48fecha);
			$html_saiu48hora=html_HoraMin('saiu48hora', $fila['saiu48hora'], 'saiu48minuto', $fila['saiu48minuto'], true);
			$objResponse->assign('div_saiu48hora', 'innerHTML', $html_saiu48hora);
			$objResponse->call("MensajeAlarmaV2('', 0)");
			$objResponse->call("verboton('belimina3048','block')");
			}else{
			if ($paso==1){
				//$objResponse->call('MensajeAlarmaV2("No se encontro el registro:'.$saiu48consec.': '.$sSQL.'", 0)');
				$objResponse->assign('saiu48consec', 'value', $saiu48consec);
				}else{
				$objResponse->call('MensajeAlarmaV2("No se encontro el registro de referencia:'.$saiu48id.'", 0)');
				}
			}
		}else{
		$objResponse->call('MensajeAlarmaV2("'.$sError.'", 1)');
		}
	if ($bHayDb){
		$objDB->CerrarConexion();
		}
	return $objResponse;
	}
function f3048_Eliminar($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$sError='';
	$iTipoError=0;
	$bDebug=false;
	$bDesdeCampus=false;
	$sDebug='';
	$opts=$aParametros;
	if(!is_array($opts)){$opts=json_decode(str_replace('\"','"',$opts),true);}
	if (isset($opts[99])!=0){if ($opts[99]==1){$bDebug=true;}}
	if (isset($opts[97])!=0){if ($opts[97]==1){$bDesdeCampus=true;}}
	//if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	list($sError, $sDebugElimina)=f3048_db_Eliminar($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugElimina;
	$objResponse=new xajaxResponse();
	if ($sError==''){
		if ($bDesdeCampus){
			list($sDetalle, $sDebugTabla)=f3048_TablaDetalleV2Campus($aParametros, $objDB, $bDebug);
			}else{
			list($sDetalle, $sDebugTabla)=f3048_TablaDetalleV2($aParametros, $objDB, $bDebug);
			}
		$sDebug=$sDebug.$sDebugTabla;
		$objResponse->assign('div_f3048detalle', 'innerHTML', $sDetalle);
		$objResponse->call('limpiaf3048');
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
function f3048_HtmlTabla($aParametros){
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
	list($sDetalle, $sDebugTabla)=f3048_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f3048detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f3048_PintarLlaves($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	//if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	if (isset($APP->piel)==0){$APP->piel=1;}
	$iPiel=$APP->piel;
	$html_saiu48consec='<input id="saiu48consec" name="saiu48consec" type="text" value="" onchange="revisaf3048()" class="cuatro"/>';
	$html_saiu48id='<input id="saiu48id" name="saiu48id" type="hidden" value=""/>';
	$et_saiu48fecha='00/00/0000';
	$html_saiu48fecha=html_oculto('saiu48fecha', 0, $et_saiu48fecha);
	$html_saiu48hora=html_HoraMin('saiu48hora', fecha_hora(), 'saiu48minuto', fecha_minuto(), true);
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_saiu48consec','innerHTML', $html_saiu48consec);
	$objResponse->assign('div_saiu48id','innerHTML', $html_saiu48id);
	$objResponse->assign('div_saiu48fecha','innerHTML', $html_saiu48fecha);
	$objResponse->assign('div_saiu48hora','innerHTML', $html_saiu48hora);
	return $objResponse;
	}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------
function f3048_TablaDetalleV2Campus($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3048='lg/lg_3048_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3048)){$mensajes_3048='lg/lg_3048_es.php';}
	require $mensajes_todas;
	require $mensajes_3048;
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	if (isset($aParametros[0])==0){$aParametros[0]=-1;}
	if (isset($aParametros[98])==0){$aParametros[98]=0;}
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
	$saiu47id=$aParametros[0];
	$iAgno=$aParametros[98];
	$pagina=$aParametros[101];
	$lineastabla=$aParametros[102];
	$bAbierta=true;
	$sLeyenda='';
	$sTabla47='saiu47tramites_'.$iAgno;
	$sTabla48='saiu48anotaciones_'.$iAgno;
	if (!$objDB->bexistetabla($sTabla47)){
		$sLeyenda='No ha sido posible acceder al contenedor de datos';
		}else{
		/*
		$sSQL='SELECT saiu47estado FROM saiu47tramites WHERE saiu47id='.$saiu47id;
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$fila=$objDB->sf($tabla);
			if ($fila['saiu47estado']!='S'){$bAbierta=true;}
			}
		*/
		}
	if ($sLeyenda!=''){
		$sLeyenda='<div class="salto1px"></div>
		<div class="GrupoCamposAyuda">
		'.$sLeyenda.'
		<div class="salto1px"></div>
		</div>';
		return array($sLeyenda.'<input id="paginaf3048" name="paginaf3048" type="hidden" value="'.$pagina.'"/><input id="lppf3048" name="lppf3048" type="hidden" value="'.$lineastabla.'"/>', $sDebug);
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
	$sTitulos='Tramite, Consec, Id, Visiblealinteresado, Anotacion, Usuario, Fecha, Hora, Minuto';
	$sSQL='SELECT TB.saiu48idtramite, TB.saiu48consec, TB.saiu48id, TB.saiu48visiblealinteresado, TB.saiu48anotacion, T6.unad11razonsocial AS C6_nombre, TB.saiu48fecha, TB.saiu48hora, TB.saiu48minuto, TB.saiu48idusuario, T6.unad11tipodoc AS C6_td, T6.unad11doc AS C6_doc 
	FROM '.$sTabla48.' AS TB, unad11terceros AS T6 
	WHERE '.$sSQLadd1.' TB.saiu48idtramite='.$saiu47id.' AND TB.saiu48visiblealinteresado=1 AND TB.saiu48idusuario=T6.unad11id '.$sSQLadd.'
	ORDER BY TB.saiu48consec DESC';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_3048" name="consulta_3048" type="hidden" value="'.$sSQLlista.'"/>
	<input id="titulos_3048" name="titulos_3048" type="hidden" value="'.$sTitulos.'"/>';
	if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 3048: '.$sSQL.'<br>';}
	$tabladetalle=$objDB->ejecutasql($sSQL);
	if ($tabladetalle==false){
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		//$sLeyenda=$sSQL;
		}else{
		$registros=$objDB->nf($tabladetalle);
		if ($registros==0){
			return array(utf8_encode($sErrConsulta.'<input id="paginaf3048" name="paginaf3048" type="hidden" value="'.$pagina.'"/><input id="lppf3048" name="lppf3048" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
	<td><b>'.$ETI['saiu48consec'].'</b></td>
	<td><b>'.$ETI['saiu48idusuario'].'</b></td>
	<td colspan="2"><b>'.$ETI['saiu48fecha'].'</b></td>
	<td align="right">
	'.html_paginador('paginaf3048', $registros, $lineastabla, $pagina, 'paginarf3048()').'
	'.html_lpp('lppf3048', $lineastabla, 'paginarf3048()').'
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
		$et_saiu48consec=$sPrefijo.$filadet['saiu48consec'].$sSufijo;
		$et_saiu48idusuario_nombre='';
		if ($filadet['saiu48idusuario']!=0){
			$et_saiu48idusuario_nombre=$sPrefijo.cadena_notildes($filadet['C6_nombre']).$sSufijo;
			}
		$et_saiu48fecha='';
		$et_saiu48hora='';
		if ($filadet['saiu48fecha']!=0){
			$et_saiu48fecha=$sPrefijo.fecha_desdenumero($filadet['saiu48fecha']).$sSufijo;
			$et_saiu48hora=html_TablaHoraMin($filadet['saiu48hora'], $filadet['saiu48minuto']);
			}
		if ($idTercero==$filadet['saiu48idusuario']){
			$sLink='<a href="javascript:cargaridf3048('.$filadet['saiu48id'].')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$res=$res.'<tr'.$sClass.'>
		<td>'.$et_saiu48consec.'</td>
		<td>'.$et_saiu48idusuario_nombre.'</td>
		<td>'.$et_saiu48fecha.'</td>
		<td>'.$et_saiu48hora.'</td>
		<td>'.$sLink.'</td>
		</tr>';
		if (trim($filadet['saiu48anotacion'])!=''){
			//<td><b>'.$ETI['saiu48anotacion'].'</b></td>
			$et_saiu48anotacion=$sPrefijo.cadena_notildes($filadet['saiu48anotacion']).$sSufijo;
			$res=$res.'<tr'.$sClass.'>
			<td></td>
			<td colspan="4">'.$et_saiu48anotacion.'</td>
			</tr>';
			}
		}
	$res=$res.'</table>
	<div class="salto5px"></div>
	</div>';
	$objDB->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
	}
function f3048_HtmlTablaCampus($aParametros){
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
	list($sDetalle, $sDebugTabla)=f3048_TablaDetalleV2Campus($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f3048detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
?>