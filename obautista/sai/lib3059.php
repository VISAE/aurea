<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2021 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.25.10c lunes, 3 de mayo de 2021
--- 3059 Anexos
*/
function f3059_db_Guardar($valores, $objDB, $bDebug=false){
	$iCodModulo=3052;
	$bAudita[2]=false;
	$bAudita[3]=false;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3059='lg/lg_3059_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3059)){$mensajes_3059='lg/lg_3059_es.php';}
	require $mensajes_todas;
	require $mensajes_3059;
	$sError='';
	$sDebug='';
	$bInserta=false;
	$iAccion=3;
	if(!is_array($valores)){$valores=json_decode(str_replace('\"','"',$valores),true);}
	if (isset($valores[0])==0){$valores[0]=0;}
	$iAgno=numeros_validar($valores[0]);
	$saiu59idtramite=numeros_validar($valores[1]);
	$saiu59consec=numeros_validar($valores[2]);
	$saiu59id=numeros_validar($valores[3], true);
	$saiu59idtipodoc=numeros_validar($valores[4]);
	$saiu59opcional=numeros_validar($valores[5]);
	$saiu59idestado=numeros_validar($valores[6]);
	$saiu59idusuario=numeros_validar($valores[9]);
	$saiu59fecha=$valores[10];
	$saiu59idrevisa=numeros_validar($valores[11]);
	$saiu59fecharevisa=$valores[12];
	//if ($saiu59idtipodoc==''){$saiu59idtipodoc=0;}
	//if ($saiu59opcional==''){$saiu59opcional=0;}
	//if ($saiu59idestado==''){$saiu59idestado=0;}
	$sSepara=', ';
	//if ($saiu59idrevisa==0){$sError=$ERR['saiu59idrevisa'].$sSepara.$sError;}
	//if ($saiu59idusuario==0){$sError=$ERR['saiu59idusuario'].$sSepara.$sError;}
	if ($saiu59opcional==''){$sError=$ERR['saiu59opcional'].$sSepara.$sError;}
	if ($saiu59idtipodoc==''){$sError=$ERR['saiu59idtipodoc'].$sSepara.$sError;}
	//if ($saiu59id==''){$sError=$ERR['saiu59id'].$sSepara.$sError;}//CONSECUTIVO
	//if ($saiu59consec==''){$sError=$ERR['saiu59consec'].$sSepara.$sError;}//CONSECUTIVO
	if ($saiu59idtramite==''){$sError=$ERR['saiu59idtramite'].$sSepara.$sError;}
	if ($sError==''){
		$sTabla47='saiu47tramites_'.$iAgno;
		$sTabla59='saiu59tramiteanexo_'.$iAgno;
		if (!$objDB->bexistetabla($sTabla47)){
			$sError='No ha sido posible acceder al contenedor de datos';
			}else{
			$sSQL='SELECT saiu47estado FROM '.$sTabla47.' WHERE saiu47id='.$saiu59idtramite;
			$tabla=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla)>0){
				$fila=$objDB->sf($tabla);
				switch($fila['saiu47estado']){
					case 6: //Puede hacer lo que deba.
					
					break;
					default:
					$sError='No se permite editar archivos.';
					break;
					}
				}
			}
		}
	/*
	if ($sError==''){
		list($sError, $sInfo)=tercero_Bloqueado($saiu59idrevisa, $objDB);
		if ($sInfo!=''){$sError=$sError.'<br>'.$sInfo;}
		}
	if ($sError==''){
		list($sError, $sInfo)=tercero_Bloqueado($saiu59idusuario, $objDB);
		if ($sInfo!=''){$sError=$sError.'<br>'.$sInfo;}
		}
	*/
	if ($sError==''){
		if ((int)$saiu59id==0){
			if ((int)$saiu59consec==0){
				$saiu59consec=tabla_consecutivo($sTabla59, 'saiu59consec', 'saiu59idtramite='.$saiu59idtramite.'', $objDB);
				if ($saiu59consec==-1){$sError=$objDB->serror;}
				}else{
				if (!seg_revisa_permiso($iCodModulo, 8, $objDB)){$sError=$ERR['8'];}
				}
			if ($sError==''){
				$sSQL='SELECT 1 FROM '.$sTabla59.' WHERE saiu59idtramite='.$saiu59idtramite.' AND saiu59consec='.$saiu59consec.'';
				$result=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($result)!=0){
					$sError=$ERR['existe'];
					}else{
					if (!seg_revisa_permiso($iCodModulo, 2, $objDB)){$sError=$ERR['2'];}
					}
				}
			if ($sError==''){
				$saiu59id=tabla_consecutivo($sTabla59, 'saiu59id', '', $objDB);
				if ($saiu59id==-1){$sError=$objDB->serror;}
				$bInserta=true;
				$iAccion=2;
				}
			}else{
			if (!seg_revisa_permiso($iCodModulo, 3, $objDB)){$sError=$ERR['3'];}
			}
		}
	if ($sError==''){
		if ($bInserta){
			$saiu59idestado=6;
			$saiu59idusuario=$_SESSION['unad_id_tercero'];
			$saiu59fecha=fecha_DiaMod();
			$saiu59idrevisa=0;
			$saiu59fecharevisa=0;
			$saiu59idorigen=0;
			$saiu59idarchivo=0;
			//$sSQL='SELECT Campo FROM Tabla WHERE Id='.$sValorId;
			//$tabla=$objDB->ejecutasql($sSQL);
			//if ($objDB->nf($tabla)>0){
				//$fila=$objDB->sf($tabla);
				//$sCampo=$fila['sCampo'];
				//}
			//$sError='INFORMACION PARA EL ADMINISTRADOR DEL SISTEMA: No ha definido valores para los campos ocultos.';
			}
		}
	if ($sError==''){
		if ($bInserta){
			$sCampos3059='saiu59idtramite, saiu59consec, saiu59id, saiu59idtipodoc, saiu59opcional, 
			saiu59idestado, saiu59idorigen, saiu59idarchivo, saiu59idusuario, saiu59fecha, 
			saiu59idrevisa, saiu59fecharevisa';
			$sValores3059=''.$saiu59idtramite.', '.$saiu59consec.', '.$saiu59id.', '.$saiu59idtipodoc.', '.$saiu59opcional.', 
			'.$saiu59idestado.', 0, 0, "'.$saiu59idusuario.'", "'.$saiu59fecha.'", 
			"'.$saiu59idrevisa.'", "'.$saiu59fecharevisa.'"';
			if ($APP->utf8==1){
				$sSQL='INSERT INTO '.$sTabla59.' ('.$sCampos3059.') VALUES ('.utf8_encode($sValores3059).');';
				}else{
				$sSQL='INSERT INTO '.$sTabla59.' ('.$sCampos3059.') VALUES ('.$sValores3059.');';
				}
			if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Guardar 3059 '.$sSQL.'<br>';}
			$result=$objDB->ejecutasql($sSQL);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' [3059].<!-- '.$sSQL.' -->';
				}else{
				if ($bAudita[2]){
					seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 2, $saiu59id, $sSQL, $objDB);
					}
				}
			}else{
			$scampo3059[1]='saiu59idtipodoc';
			$scampo3059[2]='saiu59opcional';
			//$scampo3059[3]='saiu59fecha';
			//$scampo3059[4]='saiu59fecharevisa';
			$svr3059[1]=$saiu59idtipodoc;
			$svr3059[2]=$saiu59opcional;
			//$svr3059[3]=$saiu59fecha;
			//$svr3059[4]=$saiu59fecharevisa;
			$inumcampos=2;
			$sWhere='saiu59id='.$saiu59id.'';
			//$sWhere='saiu59idtramite='.$saiu59idtramite.' AND saiu59consec='.$saiu59consec.'';
			$sSQL='SELECT * FROM '.$sTabla59.' WHERE '.$sWhere;
			$sdatos='';
			$bpasa=false;
			$result=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($result)>0){
				$filaorigen=$objDB->sf($result);
				for ($k=1;$k<=$inumcampos;$k++){
					if ($filaorigen[$scampo3059[$k]]!=$svr3059[$k]){
						if ($sdatos!=''){$sdatos=$sdatos.', ';}
						$sdatos=$sdatos.$scampo3059[$k].'="'.$svr3059[$k].'"';
						$bpasa=true;
						}
					}
				}
			if ($bpasa){
				if ($APP->utf8==1){
					$sSQL='UPDATE '.$sTabla59.' SET '.utf8_encode($sdatos).' WHERE '.$sWhere.';';
					}else{
					$sSQL='UPDATE '.$sTabla59.' SET '.$sdatos.' WHERE '.$sWhere.';';
					}
				$result=$objDB->ejecutasql($sSQL);
				if ($result==false){
					$sError=$ERR['falla_guardar'].' {Anexos}. <!-- '.$sSQL.' -->';
					}else{
					if ($bAudita[3]){
						seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 3, $saiu59id, $sSQL, $objDB);
						}
					}
				}
			}
		}
	return array($sError, $iAccion, $saiu59id, $sDebug);
	}
function f3059_db_Eliminar($aParametros, $objDB, $bDebug=false){
	$iCodModulo=3052;
	$bAudita[4]=false;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3059='lg/lg_3059_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3059)){$mensajes_3059='lg/lg_3059_es.php';}
	require $mensajes_todas;
	require $mensajes_3059;
	$sError='';
	$sDebug='';
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$saiu59idtramite=numeros_validar($aParametros[1]);
	$saiu59consec=numeros_validar($aParametros[2]);
	$saiu59id=numeros_validar($aParametros[3]);
	$iAgno=numeros_validar($aParametros[98]);
	if ($sError==''){
		$sTabla47='saiu47tramites_'.$iAgno;
		$sTabla59='saiu59tramiteanexo_'.$iAgno;
		if (!$objDB->bexistetabla($sTabla47)){
			$sError='No ha sido posible acceder al contenedor de datos';
			}else{
			$sSQL='SELECT saiu47estado FROM '.$sTabla47.' WHERE saiu47id='.$saiu59idtramite;
			$tabla=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla)>0){
				$fila=$objDB->sf($tabla);
				switch($fila['saiu47estado']){
					case 6: //Puede hacer lo que deba.
					
					break;
					default:
					$sError='No se permite editar archivos.';
					break;
					}
				}
			}
		}
	if ($sError==''){
		if (!seg_revisa_permiso($iCodModulo, 4, $objDB)){
			$sError=$ERR['4'];
			}
		}
	if ($sError==''){
		//acciones previas
		$sWhere='saiu59id='.$saiu59id.'';
		//$sWhere='saiu59idtramite='.$saiu59idtramite.' AND saiu59consec='.$saiu59consec.'';
		$sSQL='DELETE FROM '.$sTabla59.' WHERE '.$sWhere.';';
		$result=$objDB->ejecutasql($sSQL);
		if ($result==false){
			$sError=$ERR['falla_eliminar'].' {3059 Anexos}.<!-- '.$sSQL.' -->';
			}else{
			if ($bAudita[4]){
				seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $saiu59id, $sSQL, $objDB);
				}
			}
		}
	return array($sError, $sDebug);
	}
function f3059_TablaDetalleV2($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3059='lg/lg_3059_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3059)){$mensajes_3059='lg/lg_3059_es.php';}
	require $mensajes_todas;
	require $mensajes_3059;
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
	$bAbierta=false;
	$sLeyenda='';
	$sTabla47='saiu47tramites_'.$iAgno;
	$sTabla59='saiu59tramiteanexo_'.$iAgno;
	if (!$objDB->bexistetabla($sTabla47)){
		$sLeyenda='No ha sido posible acceder al contenedor de datos';
		}else{
		$bConBotonesDoc=false;
		$bConRevision=false;
		$bConEditar=false;
		$sSQL='SELECT saiu47estado FROM '.$sTabla47.' WHERE saiu47id='.$saiu47id;
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$fila=$objDB->sf($tabla);
			if ($fila['saiu47estado']==0){
				$bConBotonesDoc=true;
				$bAbierta=true;
				}
			if ($fila['saiu47estado']==3){$bConRevision=true;}
			if ($fila['saiu47estado']==5){$bConBotonesDoc=true;}
			if ($fila['saiu47estado']==6){$bConEditar=true;}
			}
		}
	if ($sLeyenda!=''){
		$sLeyenda='<div class="salto1px"></div>
		<div class="GrupoCamposAyuda">
		'.$sLeyenda.'
		<div class="salto1px"></div>
		</div>';
		return array($sLeyenda.'<input id="paginaf3059" name="paginaf3059" type="hidden" value="'.$pagina.'"/><input id="lppf3059" name="lppf3059" type="hidden" value="'.$lineastabla.'"/>', $sDebug);
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
	$sTitulos='Tramite, Consec, Id, Tipodoc, Opcional, Estado, Origen, Archivo, Usuario, Fecha, Revisa, Fecharevisa';
	$sSQL='SELECT TB.saiu59consec, TB.saiu59id, T4.saiu51nombre, TB.saiu59opcional, TB.saiu59idorigen, 
	TB.saiu59idarchivo, TB.saiu59fecha, TB.saiu59fecharevisa, TB.saiu59idtipodoc, TB.saiu59idestado, TB.saiu59idusuario, 
	TB.saiu59idrevisa 
	FROM '.$sTabla59.' AS TB, saiu51tramitedoc AS T4 
	WHERE '.$sSQLadd1.' TB.saiu59idtramite='.$saiu47id.' AND TB.saiu59idtipodoc=T4.saiu51id '.$sSQLadd.'
	ORDER BY TB.saiu59consec';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_3059" name="consulta_3059" type="hidden" value="'.$sSQLlista.'"/>
	<input id="titulos_3059" name="titulos_3059" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objDB->ejecutasql($sSQL);
	if ($tabladetalle==false){
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 3059: '.$sSQL.'<br>';}
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		//$sLeyenda=$sSQL;
		}else{
		$registros=$objDB->nf($tabladetalle);
		if ($registros==0){
			return array(utf8_encode($sErrConsulta.'<input id="paginaf3059" name="paginaf3059" type="hidden" value="'.$pagina.'"/><input id="lppf3059" name="lppf3059" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
	<td><b>'.$ETI['saiu59consec'].'</b></td>
	<td><b>'.$ETI['saiu59idtipodoc'].'</b></td>
	<td><b>'.$ETI['saiu59opcional'].'</b></td>
	<td colspan="2"><b>'.$ETI['saiu59idarchivo'].'</b></td>
	<td><b>'.$ETI['saiu59fecharevisa'].'</b></td>
	<td align="right" colspan="2">
	'.html_paginador('paginaf3059', $registros, $lineastabla, $pagina, 'paginarf3059()').'
	'.html_lpp('lppf3059', $lineastabla, 'paginarf3059()').'
	</td>
	</tr></thead>';
	$tlinea=1;
	while($filadet=$objDB->sf($tabladetalle)){
		$sPrefijo='';
		$sSufijo='';
		$sClass=' class="resaltetabla"';
		$sLink='';
		$sLink2='';
		if ($filadet['saiu59idrevisa']!=0){
			$sPrefijo='<b>';
			$sSufijo='</b>';
			}
		if(($tlinea%2)!=0){$sClass='';}
		$tlinea++;
		$id59=$filadet['saiu59id'];
		$et_saiu59consec=$sPrefijo.$filadet['saiu59consec'].$sSufijo;
		$et_saiu59idtipodoc=$sPrefijo.cadena_notildes($filadet['saiu51nombre']).$sSufijo;
		$et_saiu59opcional='';
		if ($filadet['saiu59opcional']==1){
			$et_saiu59opcional=$sPrefijo.'Opcional'.$sSufijo;
			}
		$et_saiu59idarchivo='';
		if ($filadet['saiu59idarchivo']!=0){
			//$et_saiu59idarchivo='<img src="verarchivo.php?cont='.$filadet['saiu59idorigen'].'&id='.$filadet['saiu59idarchivo'].'&maxx=150"/>';
			$et_saiu59idarchivo=html_lnkarchivo((int)$filadet['saiu59idorigen'], (int)$filadet['saiu59idarchivo']);
			}
		$sBotonAnexa='';
		if ($filadet['saiu59idrevisa']==0){
			if ($bConBotonesDoc){
				$sBotonAnexa='<input id="banexasaiu59idarchivo_'.$id59.'" name="banexasaiu59idarchivo_'.$id59.'" type="button" value="Anexar" class="btMiniAnexar" onclick="carga_saiu59idarchivo('.$id59.')" title="Cargar archivo"/>';
				$bMostrarDetalle=true;
				}
			}
		$et_saiu59fecharevisa='';
		if ($filadet['saiu59fecharevisa']!=0){$et_saiu59fecharevisa=$sPrefijo.fecha_desdenumero($filadet['saiu59fecharevisa']).$sSufijo;}
		if ($bConRevision){
			if ($filadet['saiu59idrevisa']==0){
				$sLink='<a href="javascript:apruebaidf3059('.$filadet['saiu59id'].')" class="lnkresalte">Aprobar</a>';
				}else{
				$sLink2='<a href="javascript:retiraidf3059('.$filadet['saiu59id'].')" class="lnkresalte">Desaprobar</a>';
				}
			}
		if ($bConEditar){
			if ($filadet['saiu59idrevisa']==0){
				$sLink2='<a href="javascript:cargaridf3059('.$filadet['saiu59id'].')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
				}
			}
		if ($bAbierta){
			if ($filadet['saiu59idrevisa']!=0){
				$sLink2='<a href="javascript:retiraidf3059('.$filadet['saiu59id'].')" class="lnkresalte">Desaprobar</a>';
				}
			}
		$res=$res.'<tr'.$sClass.'>
		<td>'.$et_saiu59consec.'</td>
		<td>'.$et_saiu59idtipodoc.'</td>
		<td>'.$et_saiu59opcional.'</td>
		<td>'.$sBotonAnexa.'</td>
		<td>'.$et_saiu59idarchivo.'</td>
		<td>'.$et_saiu59fecharevisa.'</td>
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
// -- 3059 Anexos XAJAX 
function elimina_archivo_saiu59idarchivo($valores, $bDebug=false){
	$_SESSION['u_ultimominuto']=iminutoavance();
	require './app.php';
	if(!is_array($valores)){$datos=json_decode(str_replace('\"','"',$valores),true);}
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$sError='';
	$sDebug='';
	$bPuedeEliminar=true;
	// Definir las condiciones para que se pueda eliminar y el mensaje de error que se debe presentar
	if ($bPuedeEliminar){
		$iAgno=$valores[0];
		$idPadre=$valores[1];
		archivo_eliminar('saiu59tramiteanexo_'.$iAgno, 'saiu59id', 'saiu59idorigen', 'saiu59idarchivo', $idPadre, $objDB);
		}
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	if ($bPuedeEliminar){
		$objResponse->call("limpia_saiu59idarchivo");
		}else{
		$objResponse->call("MensajeAlarmaV2('".$sError."', 0);");
		}
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f3059_Guardar($valores, $aParametros){
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
		list($sError, $iAccion, $saiu59id, $sDebugGuardar)=f3059_db_Guardar($valores, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugGuardar;
		}
	$objResponse=new xajaxResponse();
	if ($sError==''){
		list($sdetalle, $sDebugTabla)=f3059_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugTabla;
		$objResponse->assign('div_f3059detalle', 'innerHTML', $sdetalle);
		//if ($iAccion==2){
			//$objResponse->call('cargaridf3059('.$saiu59id.')');
			//}else{
			$objResponse->call('limpiaf3059');
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
function f3059_Traer($aParametros){
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
	$paso=$aParametros[0];
	$iAgno=numeros_validar($aParametros[98]);
	if ($paso==1){
		$saiu59idtramite=numeros_validar($aParametros[1]);
		$saiu59consec=numeros_validar($aParametros[2]);
		if (($saiu59idtramite!='')&&($saiu59consec!='')){$besta=true;}
		}else{
		$saiu59id=$aParametros[103];
		if ((int)$saiu59id!=0){$besta=true;}
		}
	if ($besta){
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		$bHayDb=true;
		$sTabla47='saiu47tramites_'.$iAgno;
		$sTabla59='saiu59tramiteanexo_'.$iAgno;
		if (!$objDB->bexistetabla($sTabla47)){
			$besta=false;
			$sError='No ha sido posible acceder al contenedor de datos';
			}else{
			/*
			$sSQL='SELECT saiu47estado FROM '.$sTabla47.' WHERE saiu47id='.$saiu59idtramite;
			$tabla=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla)>0){
				$fila=$objDB->sf($tabla);
				switch($fila['saiu47estado']){
					case 6: //Puede hacer lo que deba.
					
					break;
					default:
					$besta=false;
					$sError='No se permite editar archivos.';
					break;
					}
				}
			*/
			}
		}
	if ($besta){
		$besta=false;
		$sSQLcondi='';
		if ($paso==1){
			$sSQLcondi=$sSQLcondi.'saiu59idtramite='.$saiu59idtramite.' AND saiu59consec='.$saiu59consec.'';
			}else{
			$sSQLcondi=$sSQLcondi.'saiu59id='.$saiu59id.'';
			}
		$sSQL='SELECT * FROM '.$sTabla59.' WHERE '.$sSQLcondi;
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
		/*
		$saiu59idusuario_id=(int)$fila['saiu59idusuario'];
		$saiu59idusuario_td=$APP->tipo_doc;
		$saiu59idusuario_doc='';
		$saiu59idusuario_nombre='';
		if ($saiu59idusuario_id!=0){
			list($saiu59idusuario_nombre, $saiu59idusuario_id, $saiu59idusuario_td, $saiu59idusuario_doc)=html_tercero($saiu59idusuario_td, $saiu59idusuario_doc, $saiu59idusuario_id, 0, $objDB);
			}
		$saiu59idrevisa_id=(int)$fila['saiu59idrevisa'];
		$saiu59idrevisa_td=$APP->tipo_doc;
		$saiu59idrevisa_doc='';
		$saiu59idrevisa_nombre='';
		if ($saiu59idrevisa_id!=0){
			list($saiu59idrevisa_nombre, $saiu59idrevisa_id, $saiu59idrevisa_td, $saiu59idrevisa_doc)=html_tercero($saiu59idrevisa_td, $saiu59idrevisa_doc, $saiu59idrevisa_id, 0, $objDB);
			}
		*/
		$saiu59consec_nombre='';
		$html_saiu59consec=html_oculto('saiu59consec', $fila['saiu59consec'], $saiu59consec_nombre);
		$objResponse->assign('div_saiu59consec', 'innerHTML', $html_saiu59consec);
		$saiu59id_nombre='';
		$html_saiu59id=html_oculto('saiu59id', $fila['saiu59id'], $saiu59id_nombre);
		$objResponse->assign('div_saiu59id', 'innerHTML', $html_saiu59id);
		$objResponse->assign('saiu59idtipodoc', 'value', $fila['saiu59idtipodoc']);
		$objResponse->assign('saiu59opcional', 'value', $fila['saiu59opcional']);
		/*
		list($saiu59idestado_nombre, $serror_det)=tabla_campoxid('saiu60estadotramite','saiu60nombre','saiu60id', $fila['saiu59idestado'],'{'.$ETI['msg_sindato'].'}', $objDB);
		$html_saiu59idestado=html_oculto('saiu59idestado', $fila['saiu59idestado'], $saiu59idestado_nombre);
		$objResponse->assign('div_saiu59idestado', 'innerHTML', $html_saiu59idestado);
		*/
		$objResponse->assign('saiu59idorigen', 'value', $fila['saiu59idorigen']);
		$idorigen=(int)$fila['saiu59idorigen'];
		$objResponse->assign('saiu59idarchivo', 'value', $fila['saiu59idarchivo']);
		$sMuestraAnexar='block';
		$sMuestraEliminar='none';
		$sHTMLArchivo=html_lnkarchivo($idorigen, (int)$fila['saiu59idarchivo']);
		if ((int)$fila['saiu59idarchivo']!=0){
			$sMuestraEliminar='block';
			//Aqui puede poner validaciones al eliminar - Si no se puede eliminar reversar el mostrado.
			}
		$objResponse->assign('div_saiu59idarchivo', 'innerHTML', $sHTMLArchivo);
		$objResponse->call("verboton('banexasaiu59idarchivo', '".$sMuestraAnexar."')");
		$objResponse->call("verboton('beliminasaiu59idarchivo','".$sMuestraEliminar."')");
		/*
		$bOculto=true;
		$html_saiu59idusuario_llaves=html_DivTerceroV2('saiu59idusuario', $saiu59idusuario_td, $saiu59idusuario_doc, $bOculto, $saiu59idusuario_id, $ETI['ing_doc']);
		$objResponse->assign('saiu59idusuario', 'value', $saiu59idusuario_id);
		$objResponse->assign('div_saiu59idusuario_llaves', 'innerHTML', $html_saiu59idusuario_llaves);
		$objResponse->assign('div_saiu59idusuario', 'innerHTML', $saiu59idusuario_nombre);
		$html_saiu59fecha=html_oculto('saiu59fecha', $fila['saiu59fecha'], fecha_desdenumero($fila['saiu59fecha']));
		$objResponse->assign('div_saiu59fecha', 'innerHTML', $html_saiu59fecha);
		$bOculto=true;
		$html_saiu59idrevisa_llaves=html_DivTerceroV2('saiu59idrevisa', $saiu59idrevisa_td, $saiu59idrevisa_doc, $bOculto, $saiu59idrevisa_id, $ETI['ing_doc']);
		$objResponse->assign('saiu59idrevisa', 'value', $saiu59idrevisa_id);
		$objResponse->assign('div_saiu59idrevisa_llaves', 'innerHTML', $html_saiu59idrevisa_llaves);
		$objResponse->assign('div_saiu59idrevisa', 'innerHTML', $saiu59idrevisa_nombre);
		$html_saiu59fecharevisa=html_oculto('saiu59fecharevisa', $fila['saiu59fecharevisa'], fecha_desdenumero($fila['saiu59fecharevisa']));
		$objResponse->assign('div_saiu59fecharevisa', 'innerHTML', $html_saiu59fecharevisa);
		*/
		$objResponse->call("MensajeAlarmaV2('', 0)");
		$objResponse->call("verboton('belimina3059','block')");
		}else{
		if ($paso==1){
			$objResponse->assign('saiu59consec', 'value', $saiu59consec);
			}else{
			$objResponse->call('MensajeAlarmaV2("No se encontro el registro de referencia:'.$saiu59id.'", 0)');
			}
		}
	if ($bHayDb){
		$objDB->CerrarConexion();
		}
	return $objResponse;
	}
function f3059_Eliminar($aParametros){
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
	list($sError, $sDebugElimina)=f3059_db_Eliminar($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugElimina;
	$objResponse=new xajaxResponse();
	if ($sError==''){
		list($sDetalle, $sDebugTabla)=f3059_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugTabla;
		$objResponse->assign('div_f3059detalle', 'innerHTML', $sDetalle);
		$objResponse->call('limpiaf3059');
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
function f3059_HtmlTabla($aParametros){
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
	list($sDetalle, $sDebugTabla)=f3059_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f3059detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f3059_PintarLlaves($aParametros){
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
	$html_saiu59consec='<input id="saiu59consec" name="saiu59consec" type="text" value="" onchange="revisaf3059()" class="cuatro"/>';
	$html_saiu59id='<input id="saiu59id" name="saiu59id" type="hidden" value=""/>';
	/*
	$html_saiu59idestado=f3059_HTMLComboV2_saiu59idestado($objDB, $objCombos, '');
	list($saiu59idusuario_rs, $saiu59idusuario, $saiu59idusuario_td, $saiu59idusuario_doc)=html_tercero('CC', '', $_SESSION['unad_id_tercero'], 0, $objDB);
	$html_saiu59idusuario_llaves=html_DivTerceroV2('saiu59idusuario', $saiu59idusuario_td, $saiu59idusuario_doc, true, 0, $ETI['ing_doc']);
	$et_saiu59fecha='00/00/0000';
	$html_saiu59fecha=html_oculto('saiu59fecha', 0, $et_saiu59fecha);
	list($saiu59idrevisa_rs, $saiu59idrevisa, $saiu59idrevisa_td, $saiu59idrevisa_doc)=html_tercero('CC', '', $_SESSION['unad_id_tercero'], 0, $objDB);
	$html_saiu59idrevisa_llaves=html_DivTerceroV2('saiu59idrevisa', $saiu59idrevisa_td, $saiu59idrevisa_doc, true, 0, $ETI['ing_doc']);
	$et_saiu59fecharevisa='00/00/0000';
	$html_saiu59fecharevisa=html_oculto('saiu59fecharevisa', 0, $et_saiu59fecharevisa);
	*/
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_saiu59consec','innerHTML', $html_saiu59consec);
	$objResponse->assign('div_saiu59id','innerHTML', $html_saiu59id);
	/*
	$objResponse->assign('div_saiu59idestado','innerHTML', $html_saiu59idestado);
	$objResponse->call('$("#saiu59idestado").chosen()');
	$objResponse->assign('saiu59idusuario','value', $saiu59idusuario);
	$objResponse->assign('div_saiu59idusuario_llaves','innerHTML', $html_saiu59idusuario_llaves);
	$objResponse->assign('div_saiu59idusuario','innerHTML', $saiu59idusuario_rs);
	$objResponse->assign('div_saiu59fecha','innerHTML', $html_saiu59fecha);
	$objResponse->assign('saiu59idrevisa','value', $saiu59idrevisa);
	$objResponse->assign('div_saiu59idrevisa_llaves','innerHTML', $html_saiu59idrevisa_llaves);
	$objResponse->assign('div_saiu59idrevisa','innerHTML', $saiu59idrevisa_rs);
	$objResponse->assign('div_saiu59fecharevisa','innerHTML', $html_saiu59fecharevisa);
	*/
	return $objResponse;
	}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------
function f3059_TablaDetalleV2Campus($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3059='lg/lg_3059_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3059)){$mensajes_3059='lg/lg_3059_es.php';}
	require $mensajes_todas;
	require $mensajes_3059;
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
	$bAbierta=false;
	$sLeyenda='';
	$sTabla47='saiu47tramites_'.$iAgno;
	$sTabla59='saiu59tramiteanexo_'.$iAgno;
	if (!$objDB->bexistetabla($sTabla47)){
		$sLeyenda='No ha sido posible acceder al contenedor de datos';
		}else{
		$bConBotonesDoc=false;
		$sSQL='SELECT saiu47estado FROM '.$sTabla47.' WHERE saiu47id='.$saiu47id;
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$fila=$objDB->sf($tabla);
			if ($fila['saiu47estado']==0){$bConBotonesDoc=true;}
			if ($fila['saiu47estado']==5){$bConBotonesDoc=true;}
			}
		}
	if ($sLeyenda!=''){
		$sLeyenda='<div class="salto1px"></div>
		<div class="GrupoCamposAyuda">
		'.$sLeyenda.'
		<div class="salto1px"></div>
		</div>';
		return array($sLeyenda.'<input id="paginaf3059" name="paginaf3059" type="hidden" value="'.$pagina.'"/><input id="lppf3059" name="lppf3059" type="hidden" value="'.$lineastabla.'"/>', $sDebug);
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
	$sTitulos='Tramite, Consec, Id, Tipodoc, Opcional, Estado, Origen, Archivo, Usuario, Fecha, Revisa, Fecharevisa';
	$sSQL='SELECT TB.saiu59consec, TB.saiu59id, T4.saiu51nombre, TB.saiu59opcional, TB.saiu59idorigen, 
	TB.saiu59idarchivo, TB.saiu59fecha, TB.saiu59fecharevisa, TB.saiu59idtipodoc, TB.saiu59idestado, TB.saiu59idusuario, 
	TB.saiu59idrevisa 
	FROM '.$sTabla59.' AS TB, saiu51tramitedoc AS T4 
	WHERE '.$sSQLadd1.' TB.saiu59idtramite='.$saiu47id.' AND TB.saiu59idtipodoc=T4.saiu51id '.$sSQLadd.'
	ORDER BY TB.saiu59consec';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_3059" name="consulta_3059" type="hidden" value="'.$sSQLlista.'"/>
	<input id="titulos_3059" name="titulos_3059" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objDB->ejecutasql($sSQL);
	if ($tabladetalle==false){
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 3059: '.$sSQL.'<br>';}
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		//$sLeyenda=$sSQL;
		}else{
		$registros=$objDB->nf($tabladetalle);
		if ($registros==0){
			return array(utf8_encode($sErrConsulta.'<input id="paginaf3059" name="paginaf3059" type="hidden" value="'.$pagina.'"/><input id="lppf3059" name="lppf3059" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
	<td><b>'.$ETI['saiu59consec'].'</b></td>
	<td><b>'.$ETI['saiu59idtipodoc'].'</b></td>
	<td><b>'.$ETI['saiu59opcional'].'</b></td>
	<td colspan="2"><b>'.$ETI['saiu59idarchivo'].'</b></td>
	<td><b>'.$ETI['saiu59fecharevisa'].'</b></td>
	<td align="right">
	'.html_paginador('paginaf3059', $registros, $lineastabla, $pagina, 'paginarf3059()').'
	'.html_lpp('lppf3059', $lineastabla, 'paginarf3059()').'
	</td>
	</tr></thead>';
	$tlinea=1;
	while($filadet=$objDB->sf($tabladetalle)){
		$sPrefijo='';
		$sSufijo='';
		$sClass=' class="resaltetabla"';
		$sLink='';
		if ($filadet['saiu59idrevisa']!=0){
			$sPrefijo='<b>';
			$sSufijo='</b>';
			}
		if(($tlinea%2)!=0){$sClass='';}
		$tlinea++;
		$id59=$filadet['saiu59id'];
		$et_saiu59consec=$sPrefijo.$filadet['saiu59consec'].$sSufijo;
		$et_saiu59idtipodoc=$sPrefijo.cadena_notildes($filadet['saiu51nombre']).$sSufijo;
		$et_saiu59opcional='';
		if ($filadet['saiu59opcional']==1){
			$et_saiu59opcional=$sPrefijo.'Opcional'.$sSufijo;
			}
		$et_saiu59idarchivo='';
		if ($filadet['saiu59idarchivo']!=0){
			//$et_saiu59idarchivo='<img src="verarchivo.php?cont='.$filadet['saiu59idorigen'].'&id='.$filadet['saiu59idarchivo'].'&maxx=150"/>';
			$et_saiu59idarchivo=html_lnkarchivo((int)$filadet['saiu59idorigen'], (int)$filadet['saiu59idarchivo']);
			}
		$sBotonAnexa='';
		if ($filadet['saiu59idrevisa']==0){
			if ($bConBotonesDoc){
				$sBotonAnexa='<input id="banexasaiu59idarchivo_'.$id59.'" name="banexasaiu59idarchivo_'.$id59.'" type="button" value="Anexar" class="btMiniAnexar" onclick="carga_saiu59idarchivo('.$id59.')" title="Cargar archivo"/>';
				$bMostrarDetalle=true;
				}
			}
		$et_saiu59fecharevisa='';
		if ($filadet['saiu59fecharevisa']!=0){$et_saiu59fecharevisa=$sPrefijo.fecha_desdenumero($filadet['saiu59fecharevisa']).$sSufijo;}
		if ($bAbierta){
			//$sLink='<a href="javascript:cargaridf3059('.$filadet['saiu59id'].')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$res=$res.'<tr'.$sClass.'>
		<td>'.$et_saiu59consec.'</td>
		<td>'.$et_saiu59idtipodoc.'</td>
		<td>'.$et_saiu59opcional.'</td>
		<td>'.$sBotonAnexa.'</td>
		<td>'.$et_saiu59idarchivo.'</td>
		<td>'.$et_saiu59fecharevisa.'</td>
		<td>'.$sLink.'</td>
		</tr>';
		}
	$res=$res.'</table>
	<div class="salto5px"></div>
	</div>';
	$objDB->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
	}
function f3059_HtmlTablaCampus($aParametros){
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
	list($sDetalle, $sDebugTabla)=f3059_TablaDetalleV2Campus($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f3059detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f3059_AprobarDocumento($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$sError='';
	$iTipoError=0;
	$bDebug=true;
	$sDebug='';
	$opts=$aParametros;
	if(!is_array($opts)){$opts=json_decode(str_replace('\"','"',$opts),true);}
	if (isset($opts[99])!=0){if ($opts[99]==1){$bDebug=true;}}
	//if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	//Aprobar el documento
	$idProceso=$aParametros[1];
	$saiu49id=$aParametros[3];
	$iAgno=$aParametros[98];
	$iHoy=fecha_DiaMod();
	$sTabla59='saiu59tramiteanexo_'.$iAgno;
	if ($idProceso==0){
		$sSQL='UPDATE '.$sTabla59.' SET saiu59idrevisa=0 ,saiu59fecharevisa=0 WHERE saiu59id='.$saiu49id.'';
		}else{
		$sSQL='UPDATE '.$sTabla59.' SET saiu59idrevisa='.$_SESSION['unad_id_tercero'].' ,saiu59fecharevisa='.$iHoy.' WHERE saiu59id='.$saiu49id.'';
		}
	$result=$objDB->ejecutasql($sSQL);
	if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Actualizando dato: '.$sSQL.'<br>';}
	$objResponse=new xajaxResponse();
	if ($sError==''){
		list($sDetalle, $sDebugTabla)=f3059_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugTabla;
		$objResponse->assign('div_f3059detalle', 'innerHTML', $sDetalle);
		if ($idProceso==0){
			$sError='Se ha retirado la aprobaci&oacute;n del documento.';
			}else{
			$sError='El documento ha sido aprobado.';
			}
		$iTipoError=1;
		}
	$objResponse->call("MensajeAlarmaV2('".$sError."', ".$iTipoError.")");
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	$objDB->CerrarConexion();
	return $objResponse;
	}
?>