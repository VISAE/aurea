<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2020 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.24.1 viernes, 21 de febrero de 2020
--- 3007 Anexos
*/
function f3007_db_Guardar($valores, $objDB, $bDebug=false){
	$iCodModulo=3007;
	$bAudita[2]=false;
	$bAudita[3]=false;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3007=$APP->rutacomun.'lg/lg_3007_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3007)){$mensajes_3007=$APP->rutacomun.'lg/lg_3007_es.php';}
	require $mensajes_todas;
	require $mensajes_3007;
	$sError='';
	$sDebug='';
	$bInserta=false;
	$iAccion=3;
	if(!is_array($valores)){$valores=json_decode(str_replace('\"','"',$valores),true);}
	$saiu07idsolicitud=numeros_validar($valores[1]);
	$saiu07consec=numeros_validar($valores[2]);
	$saiu07id=numeros_validar($valores[3], true);
	$saiu07idtipoanexo=numeros_validar($valores[4]);
	$saiu07detalle=htmlspecialchars(trim($valores[5]));
	$saiu07idusuario=numeros_validar($valores[8]);
	$saiu07fecha=$valores[9];
	$saiu07hora=numeros_validar($valores[10]);
	$saiu07minuto=numeros_validar($valores[11]);
	$saiu07estado=numeros_validar($valores[12]);
	$saiu07idvalidad=numeros_validar($valores[13]);
	$saiu07fechavalida=$valores[14];
	$saiu07horavalida=numeros_validar($valores[15]);
	$saiu07minvalida=numeros_validar($valores[16]);
	//if ($saiu07idtipoanexo==''){$saiu07idtipoanexo=0;}
	//if ($saiu07hora==''){$saiu07hora=0;}
	//if ($saiu07minuto==''){$saiu07minuto=0;}
	//if ($saiu07estado==''){$saiu07estado=0;}
	//if ($saiu07horavalida==''){$saiu07horavalida=0;}
	//if ($saiu07minvalida==''){$saiu07minvalida=0;}
	$sSepara=', ';
	if ($saiu07minvalida==''){$sError=$ERR['saiu07minvalida'].$sSepara.$sError;}
	if ($saiu07horavalida==''){$sError=$ERR['saiu07horavalida'].$sSepara.$sError;}
	if ($saiu07fechavalida==0){
		//$saiu07fechavalida=fecha_DiaMod();
		$sError=$ERR['saiu07fechavalida'].$sSepara.$sError;
		}
	if ($saiu07idvalidad==0){$sError=$ERR['saiu07idvalidad'].$sSepara.$sError;}
	if ($saiu07estado==''){$sError=$ERR['saiu07estado'].$sSepara.$sError;}
	if ($saiu07minuto==''){$sError=$ERR['saiu07minuto'].$sSepara.$sError;}
	if ($saiu07hora==''){$sError=$ERR['saiu07hora'].$sSepara.$sError;}
	if ($saiu07fecha==0){
		//$saiu07fecha=fecha_DiaMod();
		$sError=$ERR['saiu07fecha'].$sSepara.$sError;
		}
	if ($saiu07idusuario==0){$sError=$ERR['saiu07idusuario'].$sSepara.$sError;}
	if ($saiu07detalle==''){$sError=$ERR['saiu07detalle'].$sSepara.$sError;}
	if ($saiu07idtipoanexo==''){$sError=$ERR['saiu07idtipoanexo'].$sSepara.$sError;}
	//if ($saiu07id==''){$sError=$ERR['saiu07id'].$sSepara.$sError;}//CONSECUTIVO
	//if ($saiu07consec==''){$sError=$ERR['saiu07consec'].$sSepara.$sError;}//CONSECUTIVO
	if ($saiu07idsolicitud==''){$sError=$ERR['saiu07idsolicitud'].$sSepara.$sError;}
	if ($sError==''){
		list($sError, $sInfo)=tercero_Bloqueado($saiu07idvalidad, $objDB);
		if ($sInfo!=''){$sError=$sError.'<br>'.sInfo;}
		}
	if ($sError==''){
		list($sError, $sInfo)=tercero_Bloqueado($saiu07idusuario, $objDB);
		if ($sInfo!=''){$sError=$sError.'<br>'.sInfo;}
		}
	if ($sError==''){
		if ((int)$saiu07id==0){
			if ((int)$saiu07consec==0){
				$saiu07consec=tabla_consecutivo('saiu07anexos', 'saiu07consec', 'saiu07idsolicitud='.$saiu07idsolicitud.'', $objDB);
				if ($saiu07consec==-1){$sError=$objDB->serror;}
				}else{
				if (!seg_revisa_permiso($iCodModulo, 8, $objDB)){$sError=$ERR['8'];}
				}
			if ($sError==''){
				$sSQL='SELECT saiu07idsolicitud FROM saiu07anexos WHERE saiu07idsolicitud='.$saiu07idsolicitud.' AND saiu07consec='.$saiu07consec.'';
				$result=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($result)!=0){
					$sError=$ERR['existe'];
					}else{
					if (!seg_revisa_permiso($iCodModulo, 2, $objDB)){$sError=$ERR['2'];}
					}
				}
			if ($sError==''){
				$saiu07id=tabla_consecutivo('saiu07anexos', 'saiu07id', '', $objDB);
				if ($saiu07id==-1){$sError=$objDB->serror;}
				$bInserta=true;
				$iAccion=2;
				}
			}else{
			if (!seg_revisa_permiso($iCodModulo, 3, $objDB)){$sError=$ERR['3'];}
			}
		}
	if ($sError==''){
		if ($bInserta){
			$saiu07idorigen=0;
			$saiu07idarchivo=0;
			$saiu07estado=0;
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
			$sCampos3005='saiu07idsolicitud, saiu07consec, saiu07id, saiu07idtipoanexo, saiu07detalle, 
saiu07idorigen, saiu07idarchivo, saiu07idusuario, saiu07fecha, saiu07hora, 
saiu07minuto, saiu07estado, saiu07idvalidad, saiu07fechavalida, saiu07horavalida, 
saiu07minvalida';
			$sValores3005=''.$saiu07idsolicitud.', '.$saiu07consec.', '.$saiu07id.', '.$saiu07idtipoanexo.', "'.$saiu07detalle.'", 
0, 0, "'.$saiu07idusuario.'", "'.$saiu07fecha.'", '.$saiu07hora.', 
'.$saiu07minuto.', '.$saiu07estado.', "'.$saiu07idvalidad.'", "'.$saiu07fechavalida.'", '.$saiu07horavalida.', 
'.$saiu07minvalida.'';
			if ($APP->utf8==1){
				$sSQL='INSERT INTO saiu07anexos ('.$sCampos3005.') VALUES ('.utf8_encode($sValores3005).');';
				}else{
				$sSQL='INSERT INTO saiu07anexos ('.$sCampos3005.') VALUES ('.$sValores3005.');';
				}
			$result=$objDB->ejecutasql($sSQL);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' {Anexos}.<!-- '.$sSQL.' -->';
				}else{
				if ($bAudita[2]){
					seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 2, $saiu07id, $sSQL, $objDB);
					}
				}
			}else{
			$scampo3007[1]='saiu07idtipoanexo';
			$scampo3007[2]='saiu07detalle';
			$scampo3007[3]='saiu07idusuario';
			$scampo3007[4]='saiu07fecha';
			$scampo3007[5]='saiu07idvalidad';
			$scampo3007[6]='saiu07fechavalida';
			$svr3007[1]=$saiu07idtipoanexo;
			$svr3007[2]=$saiu07detalle;
			$svr3007[3]=$saiu07idusuario;
			$svr3007[4]=$saiu07fecha;
			$svr3007[5]=$saiu07idvalidad;
			$svr3007[6]=$saiu07fechavalida;
			$inumcampos=6;
			$sWhere='saiu07id='.$saiu07id.'';
			//$sWhere='saiu07idsolicitud='.$saiu07idsolicitud.' AND saiu07consec='.$saiu07consec.'';
			$sSQL='SELECT * FROM saiu07anexos WHERE '.$sWhere;
			$sdatos='';
			$bpasa=false;
			$result=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($result)>0){
				$filaorigen=$objDB->sf($result);
				for ($k=1;$k<=$inumcampos;$k++){
					if ($filaorigen[$scampo3007[$k]]!=$svr3007[$k]){
						if ($sdatos!=''){$sdatos=$sdatos.', ';}
						$sdatos=$sdatos.$scampo3007[$k].'="'.$svr3007[$k].'"';
						$bpasa=true;
						}
					}
				}
			if ($bpasa){
				if ($APP->utf8==1){
					$sSQL='UPDATE saiu07anexos SET '.utf8_encode($sdatos).' WHERE '.$sWhere.';';
					}else{
					$sSQL='UPDATE saiu07anexos SET '.$sdatos.' WHERE '.$sWhere.';';
					}
				$result=$objDB->ejecutasql($sSQL);
				if ($result==false){
					$sError=$ERR['falla_guardar'].' {Anexos}. <!-- '.$sSQL.' -->';
					}else{
					if ($bAudita[3]){
						seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 3, $saiu07id, $sSQL, $objDB);
						}
					}
				}
			}
		}
	return array($sError, $iAccion, $saiu07id, $sDebug);
	}
function f3007_db_Eliminar($aParametros, $objDB, $bDebug=false){
	$iCodModulo=3007;
	$bAudita[4]=false;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3007=$APP->rutacomun.'lg/lg_3007_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3007)){$mensajes_3007=$APP->rutacomun.'lg/lg_3007_es.php';}
	require $mensajes_todas;
	require $mensajes_3007;
	$sError='';
	$sDebug='';
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$saiu07idsolicitud=numeros_validar($aParametros[1]);
	$saiu07consec=numeros_validar($aParametros[2]);
	$saiu07id=numeros_validar($aParametros[3]);
	if ($sError==''){
		if (!seg_revisa_permiso($iCodModulo, 4, $objDB)){
			$sError=$ERR['4'];
			}
		}
	if ($sError==''){
		$sSQL='SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=3007';
		$tablaor=$objDB->ejecutasql($sSQL);
		while ($filaor=$objDB->sf($tablaor)){
			$sSQL='SELECT '.$filaor['campoid'].' FROM '.$filaor['tabla'].' WHERE '.$filaor['camporev'].'='.$saiu07id.' LIMIT 0, 1';
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
		$sWhere='saiu07id='.$saiu07id.'';
		//$sWhere='saiu07idsolicitud='.$saiu07idsolicitud.' AND saiu07consec='.$saiu07consec.'';
		$sSQL='DELETE FROM saiu07anexos WHERE '.$sWhere.';';
		$result=$objDB->ejecutasql($sSQL);
		if ($result==false){
			$sError=$ERR['falla_eliminar'].' {3007 Anexos}.<!-- '.$sSQL.' -->';
			}else{
			if ($bAudita[4]){
				seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $saiu07id, $sSQL, $objDB);
				}
			}
		}
	return array($sError, $sDebug);
	}
function f3007_TablaDetalleV2($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3007=$APP->rutacomun.'lg/lg_3007_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3007)){$mensajes_3007=$APP->rutacomun.'lg/lg_3007_es.php';}
	require $mensajes_todas;
	require $mensajes_3007;
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	if (isset($aParametros[0])==0){$aParametros[0]=-1;}
	if (isset($aParametros[97])==0){$aParametros[97]=fecha_agno();}
	if (isset($aParametros[98])==0){$aParametros[98]=fecha_mes();}
	if (isset($aParametros[100])==0){$aParametros[100]=$_SESSION['unad_id_tercero'];}
	if (isset($aParametros[101])==0){$aParametros[101]=1;}
	if (isset($aParametros[102])==0){$aParametros[102]=20;}
	//if (isset($aParametros[103])==0){$aParametros[103]='';}
	//if (isset($aParametros[104])==0){$aParametros[104]='';}
	//$aParametros[103]=numeros_validar($aParametros[103]);
	$aParametros[0]=numeros_validar($aParametros[0]);
	if ($aParametros[0]==''){$aParametros[0]=-1;}
	$sDebug='';
	$saiu05id=$aParametros[0];
	$sTabla5='saiu05solicitud'.f3000_Contenedor($aParametros[97], $aParametros[98]);
	$sTabla7='saiu07anexos'.f3000_Contenedor($aParametros[97], $aParametros[98]);
	$idTercero=$aParametros[100];
	$pagina=$aParametros[101];
	$lineastabla=$aParametros[102];
	$bAbierta=true;
	//$sSQL='SELECT Campo FROM saiu05solicitud WHERE saiu05id='.$saiu05id;
	//$tabla=$objDB->ejecutasql($sSQL);
	//if ($objDB->nf($tabla)>0){
		//$fila=$objDB->sf($tabla);
		//if ($fila['Campo']!='S'){$bAbierta=true;}
		//}
	$sSQLadd='';
	$sSQLadd1='';
	$sLeyenda='';
	if (!$objDB->bexistetabla($sTabla5)){
		$sLeyenda='No ha sido posible acceder al contenedor de datos';
		}else{
		$bConBotonesDoc=false;
		$bConRevision=false;
		$bConEditar=false;
		$sSQL='SELECT saiu05estado FROM '.$sTabla5.' WHERE saiu05id='.$saiu05id;
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$fila=$objDB->sf($tabla);
			if ($fila['saiu05estado']==0){
				$bConBotonesDoc=true;
				$bAbierta=true;
				}
			if ($fila['saiu05estado']==3){$bConRevision=true;}
			if ($fila['saiu05estado']==5){$bConBotonesDoc=true;}
			if ($fila['saiu05estado']==6){$bConEditar=true;}
			}
		}
	if ($sLeyenda!=''){
		$sLeyenda='<div class="salto1px"></div>
		<div class="GrupoCamposAyuda">
		'.$sLeyenda.'
		<div class="salto1px"></div>
		</div>';
		return array($sLeyenda.'<input id="paginaf3007" name="paginaf3007" type="hidden" value="'.$pagina.'"/><input id="lppf3007" name="lppf3007" type="hidden" value="'.$lineastabla.'"/>', $sDebug);
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
	$sTitulos='Solicitud, Consec, Id, Tipoanexo, Detalle, Origen, Archivo, Usuario, Fecha, Hora, Minuto, Estado, Validad, Fechavalida, Horavalida, Minvalida';
	$sSQL='SELECT TB.saiu07idsolicitud, TB.saiu07consec, TB.saiu07id, T4.saiu04titulo, TB.saiu07detalle, 
	TB.saiu07idorigen, TB.saiu07idarchivo, T8.unad11razonsocial AS C8_nombre, TB.saiu07fecha, TB.saiu07hora, 
	TB.saiu07minuto, T12.saiu14nombre, T13.unad11razonsocial AS C13_nombre, TB.saiu07fechavalida, TB.saiu07horavalida, 
	TB.saiu07minvalida, TB.saiu07idtipoanexo, TB.saiu07idusuario, T8.unad11tipodoc AS C8_td, T8.unad11doc AS C8_doc, 
	TB.saiu07estado, TB.saiu07idvalidad, T13.unad11tipodoc AS C13_td, T13.unad11doc AS C13_doc, T4.saiu04obligatorio 
	FROM '.$sTabla7.' AS TB, saiu04temaanexo AS T4, unad11terceros AS T8, saiu14estadoanexo AS T12, unad11terceros AS T13 
	WHERE '.$sSQLadd1.' TB.saiu07idsolicitud='.$saiu05id.' AND TB.saiu07idtipoanexo=T4.saiu04id AND TB.saiu07idusuario=T8.unad11id AND TB.saiu07estado=T12.saiu14id AND TB.saiu07idvalidad=T13.unad11id '.$sSQLadd.'
	ORDER BY TB.saiu07consec';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_3007" name="consulta_3007" type="hidden" value="'.$sSQLlista.'"/>
	<input id="titulos_3007" name="titulos_3007" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objDB->ejecutasql($sSQL);
	if ($tabladetalle==false){
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 3007: '.$sSQL.'<br>';}
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		//$sLeyenda=$sSQL;
		}else{
		$registros=$objDB->nf($tabladetalle);
		if ($registros==0){
			return array(utf8_encode($sErrConsulta.'<input id="paginaf3007" name="paginaf3007" type="hidden" value="'.$pagina.'"/><input id="lppf3007" name="lppf3007" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
	<td><b>'.$ETI['saiu07consec'].'</b></td>
	<td><b>'.$ETI['saiu07idtipoanexo'].'</b></td>
	<td><b>'.$ETI['saiu04obligatorio'].'</b></td>
	<td colspan="2"><b>'.$ETI['saiu07idarchivo'].'</b></td>
	<td><b>'.$ETI['saiu07fecha'].'</b></td>
	<td><b>'.$ETI['saiu07estado'].'</b></td>
	<td><b>'.$ETI['saiu07fechavalida'].'</b></td>
	<td align="right" colspan="2">
	'.html_paginador('paginaf3007', $registros, $lineastabla, $pagina, 'paginarf3007()').'
	'.html_lpp('lppf3007', $lineastabla, 'paginarf3007()').'
	</td>
	</tr></thead>';
	$tlinea=1;
	while($filadet=$objDB->sf($tabladetalle)){
		$sPrefijo='';
		$sSufijo='';
		$sClass=' class="resaltetabla"';
		$sLink='';
		$sLink2='';
		if ($filadet['saiu07idvalidad']!=0){
			$sPrefijo='<b>';
			$sSufijo='</b>';
			}
		if(($tlinea%2)==0){$sClass='';}
		$tlinea++;
		$id07=$filadet['saiu07id'];
		$et_saiu07consec=$sPrefijo.$filadet['saiu07consec'].$sSufijo;
		$et_saiu07idtipoanexo=$sPrefijo.cadena_notildes($filadet['saiu04titulo']).$sSufijo;
		$et_saiu04obligatorio='';
		if ($filadet['saiu04obligatorio']=="N"){
			$et_saiu04obligatorio=$sPrefijo.'Opcional'.$sSufijo;
			}
		$et_saiu07idarchivo='';
		if ($filadet['saiu07idarchivo']!=0){
			//$et_saiu07idarchivo='<img src="verarchivo.php?cont='.$filadet['saiu07idorigen'].'&id='.$filadet['saiu07idarchivo'].'&maxx=150"/>';
			$et_saiu07idarchivo=html_lnkarchivo((int)$filadet['saiu07idorigen'], (int)$filadet['saiu07idarchivo']);
			}
		$et_saiu07fecha='';
		if ($filadet['saiu07fecha']!=0){$et_saiu07fecha=$sPrefijo.fecha_desdenumero($filadet['saiu07fecha']).$sSufijo;}
		$et_saiu07hora=html_TablaHoraMin($filadet['saiu07hora'], $filadet['saiu07minuto']);
		$et_saiu07minuto=$sPrefijo.$filadet['saiu07minuto'].$sSufijo;
		$et_saiu07estado=$sPrefijo.cadena_notildes($filadet['saiu14nombre']).$sSufijo;
		$sBotonAnexa='';
		if ($filadet['saiu07idvalidad']==0){
			if ($bConBotonesDoc){
				$sBotonAnexa='<input id="banexasaiu07idarchivo_'.$id07.'" name="banexasaiu07idarchivo_'.$id07.'" type="button" value="Anexar" class="btMiniAnexar" onclick="carga_saiu07idarchivo('.$id07.')" title="Cargar archivo"/>';
				$bMostrarDetalle=true;
				}
			}
		$et_saiu07fechavalida='';
		if ($filadet['saiu07fechavalida']!=0){$et_saiu07fechavalida=$sPrefijo.fecha_desdenumero($filadet['saiu07fechavalida']).$sSufijo;}
		$et_saiu07horavalida=html_TablaHoraMin($filadet['saiu07horavalida'], $filadet['saiu07minvalida']);
		$et_saiu07minvalida=$sPrefijo.$filadet['saiu07minvalida'].$sSufijo;
		if ($bConRevision){
		if ($filadet['saiu07idvalidad']==0){
			$sLink='<a href="javascript:apruebaidf3007('.$filadet['saiu07id'].')" class="lnkresalte">Aprobar</a>';
			}else{
			$sLink2='<a href="javascript:retiraidf3007('.$filadet['saiu07id'].')" class="lnkresalte">Desaprobar</a>';
			}
		}
		if ($bConEditar){
		if ($filadet['saiu07idvalidad']==0){
			$sLink2='<a href="javascript:cargaridf3007('.$filadet['saiu07id'].')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		}
		if ($bAbierta){
			if ($filadet['saiu07idvalidad']!=0){
				$sLink2='<a href="javascript:retiraidf3007('.$filadet['saiu07id'].')" class="lnkresalte">Desaprobar</a>';
				}
			}
		$res=$res.'<tr'.$sClass.'>
		<td>'.$et_saiu07consec.'</td>
		<td>'.$et_saiu07idtipoanexo.'</td>
		<td>'.$et_saiu04obligatorio.'</td>
		<td>'.$sBotonAnexa.'</td>
		<td>'.$et_saiu07idarchivo.'</td>
		<td>'.$et_saiu07fecha.' '.$et_saiu07hora.' '.$et_saiu07minuto.'</td>
		<td>'.$et_saiu07estado.'</td>
		<td>'.$et_saiu07fechavalida.'</td>
		<td>'.$sLink.'</td>
		<td>'.$sLink2.'</td>
		</tr>';
		}
	$res=$res.'</table>
	<div class="salto5px"></div>
	</div>';
	$objDB->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
	}
function f3007_Clonar($saiu07idsolicitud, $saiu07idsolicitudPadre, $objDB){
	$sError='';
	$saiu07consec=tabla_consecutivo('saiu07anexos', 'saiu07consec', 'saiu07idsolicitud='.$saiu07idsolicitud.'', $objDB);
	if ($saiu07consec==-1){$sError=$objDB->serror;}
	$saiu07id=tabla_consecutivo('saiu07anexos', 'saiu07id', '', $objDB);
	if ($saiu07id==-1){$sError=$objDB->serror;}
	if ($sError==''){
		$sCampos3007='saiu07idsolicitud, saiu07consec, saiu07id, saiu07idtipoanexo, saiu07detalle, saiu07idorigen, saiu07idarchivo, saiu07idusuario, saiu07fecha, saiu07hora, saiu07minuto, saiu07estado, saiu07idvalidad, saiu07fechavalida, saiu07horavalida, saiu07minvalida';
		$sValores3007='';
		$sSQL='SELECT * FROM saiu07anexos WHERE saiu07idsolicitud='.$saiu07idsolicitudPadre.'';
		$tabla=$objDB->ejecutasql($sSQL);
		while($fila=$objDB->sf($tabla)){
			if ($sValores3007!=''){$sValores3007=$sValores3007.', ';}
			$sValores3007=$sValores3007.'('.$saiu07idsolicitud.', '.$saiu07consec.', '.$saiu07id.', '.$fila['saiu07idtipoanexo'].', "'.$fila['saiu07detalle'].'", "'.$fila['saiu07idorigen'].'", "'.$fila['saiu07idarchivo'].'", '.$fila['saiu07idusuario'].', "'.$fila['saiu07fecha'].'", '.$fila['saiu07hora'].', '.$fila['saiu07minuto'].', '.$fila['saiu07estado'].', '.$fila['saiu07idvalidad'].', "'.$fila['saiu07fechavalida'].'", '.$fila['saiu07horavalida'].', '.$fila['saiu07minvalida'].')';
			$saiu07consec++;
			$saiu07id++;
			}
		if ($sValores3007!=''){
			$sSQL='INSERT INTO saiu07anexos('.$sCampos3007.') VALUES '.$sValores3007.'';
			$result=$objDB->ejecutasql($sSQL);
			}
		}
	return $sError;
	}
// -- 3007 Anexos XAJAX 
function elimina_archivo_saiu07idarchivo($idpadre){
	$_SESSION['u_ultimominuto']=iminutoavance();
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	archivo_eliminar('saiu07anexos', 'saiu07id', 'saiu07idorigen', 'saiu07idarchivo', $idpadre, $objDB);
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->call("limpia_saiu07idarchivo");
	return $objResponse;
	}
function f3007_Guardar($valores, $aParametros){
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
		list($sError, $iAccion, $saiu07id, $sDebugGuardar)=f3007_db_Guardar($valores, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugGuardar;
		}
	$objResponse=new xajaxResponse();
	if ($sError==''){
		list($sdetalle, $sDebugTabla)=f3007_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugTabla;
		$objResponse->assign('div_f3007detalle', 'innerHTML', $sdetalle);
		//if ($iAccion==2){
			//$objResponse->call('cargaridf3007('.$saiu07id.')');
			//}else{
			$objResponse->call('limpiaf3007');
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
function f3007_Traer($aParametros){
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
		$saiu07idsolicitud=numeros_validar($aParametros[1]);
		$saiu07consec=numeros_validar($aParametros[2]);
		if (($saiu07idsolicitud!='')&&($saiu07consec!='')){$besta=true;}
		}else{
		$saiu07id=$aParametros[103];
		if ((int)$saiu07id!=0){$besta=true;}
		}
	if ($besta){
		$besta=false;
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		$bHayDb=true;
		$sSQLcondi='';
		if ($paso==1){
			$sSQLcondi=$sSQLcondi.'saiu07idsolicitud='.$saiu07idsolicitud.' AND saiu07consec='.$saiu07consec.'';
			}else{
			$sSQLcondi=$sSQLcondi.'saiu07id='.$saiu07id.'';
			}
		$sSQL='SELECT * FROM saiu07anexos WHERE '.$sSQLcondi;
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
		$saiu07idusuario_id=(int)$fila['saiu07idusuario'];
		$saiu07idusuario_td=$APP->tipo_doc;
		$saiu07idusuario_doc='';
		$saiu07idusuario_nombre='';
		if ($saiu07idusuario_id!=0){
			list($saiu07idusuario_nombre, $saiu07idusuario_id, $saiu07idusuario_td, $saiu07idusuario_doc)=html_tercero($saiu07idusuario_td, $saiu07idusuario_doc, $saiu07idusuario_id, 0, $objDB);
			}
		$saiu07idvalidad_id=(int)$fila['saiu07idvalidad'];
		$saiu07idvalidad_td=$APP->tipo_doc;
		$saiu07idvalidad_doc='';
		$saiu07idvalidad_nombre='';
		if ($saiu07idvalidad_id!=0){
			list($saiu07idvalidad_nombre, $saiu07idvalidad_id, $saiu07idvalidad_td, $saiu07idvalidad_doc)=html_tercero($saiu07idvalidad_td, $saiu07idvalidad_doc, $saiu07idvalidad_id, 0, $objDB);
			}
		$saiu07consec_nombre='';
		$html_saiu07consec=html_oculto('saiu07consec', $fila['saiu07consec'], $saiu07consec_nombre);
		$objResponse->assign('div_saiu07consec', 'innerHTML', $html_saiu07consec);
		$saiu07id_nombre='';
		$html_saiu07id=html_oculto('saiu07id', $fila['saiu07id'], $saiu07id_nombre);
		$objResponse->assign('div_saiu07id', 'innerHTML', $html_saiu07id);
		$objResponse->assign('saiu07idtipoanexo', 'value', $fila['saiu07idtipoanexo']);
		$objResponse->assign('saiu07detalle', 'value', $fila['saiu07detalle']);
		$objResponse->assign('saiu07idorigen', 'value', $fila['saiu07idorigen']);
		$idorigen=(int)$fila['saiu07idorigen'];
		$objResponse->assign('saiu07idarchivo', 'value', $fila['saiu07idarchivo']);
		$objResponse->call("verboton('banexasaiu07idarchivo', 'block')");
		$stemp='none';
		$stemp2=html_lnkarchivo($idorigen, (int)$fila['saiu07idarchivo']);
		if ((int)$fila['saiu07idarchivo']!=0){$stemp='block';}
		$objResponse->assign('div_saiu07idarchivo', 'innerHTML', $stemp2);
		$objResponse->call("verboton('beliminasaiu07idarchivo','".$stemp."')");
		$objResponse->assign('saiu07idusuario', 'value', $fila['saiu07idusuario']);
		$objResponse->assign('saiu07idusuario_td', 'value', $saiu07idusuario_td);
		$objResponse->assign('saiu07idusuario_doc', 'value', $saiu07idusuario_doc);
		$objResponse->assign('div_saiu07idusuario', 'innerHTML', $saiu07idusuario_nombre);
		$objResponse->assign('saiu07fecha', 'value', $fila['saiu07fecha']);
		list($iDia, $iMes, $iAgno)=fecha_DividirNumero($fila['saiu07fecha'], true);
		$objResponse->assign('saiu07fecha_dia', 'value', $iDia);
		$objResponse->assign('saiu07fecha_mes', 'value', $iMes);
		$objResponse->assign('saiu07fecha_agno', 'value', $iAgno);
		$html_saiu07hora=html_HoraMin('saiu07hora', $fila['saiu07hora'], 'saiu07minuto', $fila['saiu07minuto'], true);
		$objResponse->assign('div_saiu07hora', 'innerHTML', $html_saiu07hora);
		list($saiu07estado_nombre, $serror_det)=tabla_campoxid('saiu14estadoanexo','saiu14nombre','saiu14id', $fila['saiu07estado'],'{'.$ETI['msg_sindato'].'}', $objDB);
		$html_saiu07estado=html_oculto('saiu07estado', $fila['saiu07estado'], $saiu07estado_nombre);
		$objResponse->assign('div_saiu07estado', 'innerHTML', $html_saiu07estado);
		$objResponse->assign('saiu07idvalidad', 'value', $fila['saiu07idvalidad']);
		$objResponse->assign('saiu07idvalidad_td', 'value', $saiu07idvalidad_td);
		$objResponse->assign('saiu07idvalidad_doc', 'value', $saiu07idvalidad_doc);
		$objResponse->assign('div_saiu07idvalidad', 'innerHTML', $saiu07idvalidad_nombre);
		$objResponse->assign('saiu07fechavalida', 'value', $fila['saiu07fechavalida']);
		list($iDia, $iMes, $iAgno)=fecha_DividirNumero($fila['saiu07fechavalida'], true);
		$objResponse->assign('saiu07fechavalida_dia', 'value', $iDia);
		$objResponse->assign('saiu07fechavalida_mes', 'value', $iMes);
		$objResponse->assign('saiu07fechavalida_agno', 'value', $iAgno);
		$html_saiu07horavalida=html_HoraMin('saiu07horavalida', $fila['saiu07horavalida'], 'saiu07minvalida', $fila['saiu07minvalida'], true);
		$objResponse->assign('div_saiu07horavalida', 'innerHTML', $html_saiu07horavalida);
		$objResponse->call("MensajeAlarmaV2('', 0)");
		$objResponse->call("verboton('belimina3007','block')");
		}else{
		if ($paso==1){
			$objResponse->assign('saiu07consec', 'value', $saiu07consec);
			}else{
			$objResponse->call('MensajeAlarmaV2("No se encontro el registro de referencia:'.$saiu07id.'", 0)');
			}
		}
	if ($bHayDb){
		$objDB->CerrarConexion();
		}
	return $objResponse;
	}
function f3007_Eliminar($aParametros){
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
	list($sError, $sDebugElimina)=f3007_db_Eliminar($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugElimina;
	$objResponse=new xajaxResponse();
	if ($sError==''){
		list($sDetalle, $sDebugTabla)=f3007_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugTabla;
		$objResponse->assign('div_f3007detalle', 'innerHTML', $sDetalle);
		$objResponse->call('limpiaf3007');
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
function f3007_HtmlTabla($aParametros){
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
	list($sDetalle, $sDebugTabla)=f3007_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f3007detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f3007_PintarLlaves($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	//if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	if (isset($APP->piel)==0){$APP->piel=1;}
	$iPiel=$APP->piel;
	$html_saiu07consec='<input id="saiu07consec" name="saiu07consec" type="text" value="" onchange="revisaf3007()" class="cuatro"/>';
	$html_saiu07id='<input id="saiu07id" name="saiu07id" type="hidden" value=""/>';
	$html_saiu07hora=html_HoraMin('saiu07hora', fecha_hora(), 'saiu07minuto', fecha_minuto(), true);
	$html_saiu07estado=f3007_HTMLComboV2_saiu07estado($objDB, $objCombos, 0);
	$html_saiu07horavalida=html_HoraMin('saiu07horavalida', fecha_hora(), 'saiu07minvalida', fecha_minuto(), true);
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_saiu07consec','innerHTML', $html_saiu07consec);
	$objResponse->assign('div_saiu07id','innerHTML', $html_saiu07id);
	$objResponse->assign('div_saiu07hora','innerHTML', $html_saiu07hora);
	$objResponse->assign('div_saiu07estado','innerHTML', $html_saiu07estado);
	$objResponse->assign('div_saiu07horavalida','innerHTML', $html_saiu07horavalida);
	return $objResponse;
	}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------
?>