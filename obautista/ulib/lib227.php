<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2020 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.24.1 viernes, 7 de febrero de 2020
--- 227 Fractales
*/
function f227_HTMLComboV2_unae27idzona($objDB, $objCombos, $valor){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('unae27idzona', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	//$objCombos->iAncho=450;
	$objCombos->sAccion='revisaf227()';
	$sSQL='SELECT unad23id AS id, CONCAT(unad23sigla, " - ", unad23nombre) AS nombre FROM unad23zona ORDER BY unad23conestudiantes DESC, unad23nombre';
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f227_db_Guardar($valores, $objDB, $bDebug=false){
	$iCodModulo=226;
	$bAudita[2]=true;
	$bAudita[3]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_227=$APP->rutacomun.'lg/lg_226_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_227)){$mensajes_227=$APP->rutacomun.'lg/lg_226_es.php';}
	require $mensajes_todas;
	require $mensajes_227;
	$sError='';
	$sDebug='';
	$bInserta=false;
	$iAccion=3;
	if(!is_array($valores)){$valores=json_decode(str_replace('\"','"',$valores),true);}
	$unae27consec=numeros_validar($valores[1]);
	$unae27idzona=numeros_validar($valores[2]);
	$unae27id=numeros_validar($valores[3], true);
	$unae27activa=htmlspecialchars(trim($valores[4]));
	$unae27nombre=htmlspecialchars(trim($valores[5]));
	$unae27fractal=htmlspecialchars(trim($valores[6]));
	$unae27idresponsable=numeros_validar($valores[8]);
	$unae27tituloresponsable=htmlspecialchars(trim($valores[9]));
	$unae27orden=numeros_validar($valores[10]);
	//if ($unae27unidadpadre==''){$unae27unidadpadre=0;}
	$sSepara=', ';
	//if ($unae26tituloresponsable==''){$sError=$ERR['unae26tituloresponsable'].$sSepara.$sError;}
	//if ($unae26idresponsable==0){$sError=$ERR['unae26idresponsable'].$sSepara.$sError;}
	if ($unae27nombre==''){$sError=$ERR['unae27nombre'].$sSepara.$sError;}
	if ($unae27activa==''){$sError=$ERR['unae27activa'].$sSepara.$sError;}
	//if ($unae27id==''){$sError=$ERR['unae27id'].$sSepara.$sError;}//CONSECUTIVO
	if ($unae27idzona==''){$sError=$ERR['unae27idzona'].$sSepara.$sError;}
	if ($unae27consec==''){$sError=$ERR['unae27consec'].$sSepara.$sError;}
	if ($sError==''){
		list($sError, $sInfo)=tercero_Bloqueado($unae27idresponsable, $objDB);
		if ($sInfo!=''){$sError=$sError.'<br>'.sInfo;}
		}
	if ($sError==''){
		if ((int)$unae27id==0){
			if ($sError==''){
				$sSQL='SELECT 1 FROM unae26unidadesfun WHERE unae27consec='.$unae27consec.' AND unae27idzona='.$unae27idzona.'';
				$result=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($result)!=0){
					$sError=$ERR['existe'];
					}else{
					if (!seg_revisa_permiso($iCodModulo, 2, $objDB)){$sError=$ERR['2'];}
					}
				}
			if ($sError==''){
				$unae27id=tabla_consecutivo('unae26unidadesfun', 'unae26id', '', $objDB);
				if ($unae27id==-1){$sError=$objDB->serror;}
				$bInserta=true;
				$iAccion=2;
				}
			}else{
			if (!seg_revisa_permiso($iCodModulo, 3, $objDB)){$sError=$ERR['3'];}
			}
		}
	if ($sError==''){
		//if ($bInserta){
			$unae27fractal='N';
			$unae27unidadpadre=0;
			$sSQL='SELECT unae26unidadpadre, unae26nivel, unae26lugar, unae26prefijo FROM unae26unidadesfun WHERE unae26consec='.$unae27consec.' AND unae26idzona=0';
			$tabla=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla)>0){
				$fila=$objDB->sf($tabla);
				$unae27unidadpadre=$fila['unae26unidadpadre'];
				$unae26nivel=$fila['unae26nivel'];
				$unae26lugar=$fila['unae26lugar'];
				$unae26prefijo=$fila['unae26prefijo'];
				}else{
				$sError='No se tiene informaci&oacute;n del registro origen.';
				}
			//$sError='INFORMACION PARA EL ADMINISTRADOR DEL SISTEMA: No ha definido valores para los campos ocultos.';
			//}
		}
	if ($sError==''){
	if ($unae27orden==''){$unae27orden=$unae27consec;}
		if ($bInserta){
			$sCampos226='unae26consec, unae26idzona, unae26id, unae26activa, unae26nombre,  
unae26fractal, unae26unidadpadre, unae26idresponsable, unae26tituloresponsable, unae26orden, 
unae26nivel, unae26lugar, unae26prefijo';
			$sValores226=''.$unae27consec.', '.$unae27idzona.', '.$unae27id.', "'.$unae27activa.'", "'.$unae27nombre.'", 
"'.$unae27fractal.'", '.$unae27unidadpadre.', "'.$unae27idresponsable.'", "'.$unae27tituloresponsable.'", '.$unae27orden.', 
'.$unae26nivel.', '.$unae26lugar.', "'.$unae26prefijo.'"';
			if ($APP->utf8==1){
				$sSQL='INSERT INTO unae26unidadesfun ('.$sCampos226.') VALUES ('.utf8_encode($sValores226).');';
				}else{
				$sSQL='INSERT INTO unae26unidadesfun ('.$sCampos226.') VALUES ('.$sValores226.');';
				}
			$result=$objDB->ejecutasql($sSQL);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' {Fractales}.<!-- '.$sSQL.' -->';
				}else{
				if ($bAudita[2]){
					seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 2, $unae26id, $sSQL, $objDB);
					}
				}
			}else{
			$scampo227[1]='unae26activa';
			$scampo227[2]='unae26nombre';
			$scampo227[3]='unae26idresponsable';
			$scampo227[4]='unae26tituloresponsable';
			$scampo227[5]='unae26orden';
			$scampo227[6]='unae26nivel';
			$scampo227[7]='unae26lugar';
			$scampo227[8]='unae26prefijo';
			$svr227[1]=$unae27activa;
			$svr227[2]=$unae27nombre;
			$svr227[3]=$unae27idresponsable;
			$svr227[4]=$unae27tituloresponsable;
			$svr227[5]=$unae27orden;
			$svr227[6]=$unae26nivel;
			$svr227[7]=$unae26lugar;
			$svr227[8]=$unae26prefijo;
			$inumcampos=8;
			$sWhere='unae26id='.$unae27id.'';
			//$sWhere='unae27consec='.$unae27consec.' AND unae27idzona='.$unae27idzona.'';
			$sSQL='SELECT * FROM unae26unidadesfun WHERE '.$sWhere;
			$sdatos='';
			$bpasa=false;
			$result=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($result)>0){
				$filaorigen=$objDB->sf($result);
				for ($k=1;$k<=$inumcampos;$k++){
					if ($filaorigen[$scampo227[$k]]!=$svr227[$k]){
						if ($sdatos!=''){$sdatos=$sdatos.', ';}
						$sdatos=$sdatos.$scampo227[$k].'="'.$svr227[$k].'"';
						$bpasa=true;
						}
					}
				}
			if ($bpasa){
				if ($APP->utf8==1){
					$sSQL='UPDATE unae26unidadesfun SET '.utf8_encode($sdatos).' WHERE '.$sWhere.';';
					}else{
					$sSQL='UPDATE unae26unidadesfun SET '.$sdatos.' WHERE '.$sWhere.';';
					}
				$result=$objDB->ejecutasql($sSQL);
				if ($result==false){
					$sError=$ERR['falla_guardar'].' {Fractales}. <!-- '.$sSQL.' -->';
					}else{
					if ($bAudita[3]){
						seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 3, $unae27id, $sSQL, $objDB);
						}
					}
				}
			}
		}
	return array($sError, $iAccion, $unae27id, $sDebug);
	}
function f227_db_Eliminar($aParametros, $objDB, $bDebug=false){
	$iCodModulo=226;
	$bAudita[4]=false;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_227=$APP->rutacomun.'lg/lg_226_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_227)){$mensajes_227=$APP->rutacomun.'lg/lg_226_es.php';}
	require $mensajes_todas;
	require $mensajes_227;
	$sError='';
	$sDebug='';
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$unae27consec=numeros_validar($aParametros[1]);
	$unae27idzona=numeros_validar($aParametros[2]);
	$unae27id=numeros_validar($aParametros[3]);
	if ($sError==''){
		if (!seg_revisa_permiso($iCodModulo, 4, $objDB)){
			$sError=$ERR['4'];
			}
		}
	if ($sError==''){
		$sSQL='SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=227';
		$tablaor=$objDB->ejecutasql($sSQL);
		while ($filaor=$objDB->sf($tablaor)){
			$sSQL='SELECT '.$filaor['campoid'].' FROM '.$filaor['tabla'].' WHERE '.$filaor['camporev'].'='.$unae27id.' LIMIT 0, 1';
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
		$sWhere='unae26id='.$unae27id.'';
		//$sWhere='unae27consec='.$unae27consec.' AND unae27idzona='.$unae27idzona.'';
		$sSQL='DELETE FROM unae26unidadesfun WHERE '.$sWhere.';';
		$result=$objDB->ejecutasql($sSQL);
		if ($result==false){
			$sError=$ERR['falla_eliminar'].' {227 Fractales}.<!-- '.$sSQL.' -->';
			}else{
			if ($bAudita[4]){
				seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $unae27id, $sSQL, $objDB);
				}
			}
		}
	return array($sError, $sDebug);
	}
function f227_TablaDetalleV2($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_227=$APP->rutacomun.'lg/lg_226_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_227)){$mensajes_227=$APP->rutacomun.'lg/lg_226_es.php';}
	require $mensajes_todas;
	require $mensajes_227;
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
	$unae26consec=$aParametros[0];
	$idTercero=$aParametros[100];
	$pagina=$aParametros[101];
	$lineastabla=$aParametros[102];
	$bAbierta=true;
	//$sSQL='SELECT Campo FROM unae26unidadesfun WHERE unae26id='.$unae26consec;
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
	$sTitulos='Consec, Zona, Id, Activa, Nombre, Fractal, Unidadpadre, Responsable, Tituloresponsable';
	$sSQL='SELECT TB.unae26consec, TB.unae26idzona, TB.unae26id, TB.unae26activa, TB.unae26nombre, TB.unae26fractal, T8.unad11razonsocial AS C8_nombre, TB.unae26tituloresponsable, TB.unae26unidadpadre, TB.unae26idresponsable, T8.unad11tipodoc AS C8_td, T8.unad11doc AS C8_doc, T9.unad23nombre 
FROM unae26unidadesfun AS TB, unad11terceros AS T8, unad23zona AS T9 
WHERE TB.unae26consec='.$unae26consec.' AND TB.unae26idzona<>0 AND '.$sSQLadd1.' TB.unae26idresponsable=T8.unad11id AND TB.unae26idzona=T9.unad23id '.$sSQLadd.'
ORDER BY T9.unad23nombre';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_227" name="consulta_227" type="hidden" value="'.$sSQLlista.'"/>
<input id="titulos_227" name="titulos_227" type="hidden" value="'.$sTitulos.'"/>';
	if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 227: '.$sSQL.'<br>';}
	$tabladetalle=$objDB->ejecutasql($sSQL);
	if ($tabladetalle==false){
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		//$sLeyenda=$sSQL;
		}else{
		$registros=$objDB->nf($tabladetalle);
		if ($registros==0){
			return array(utf8_encode($sErrConsulta.'<input id="paginaf227" name="paginaf227" type="hidden" value="'.$pagina.'"/><input id="lppf227" name="lppf227" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
<td><b>'.$ETI['unae26idzona'].'</b></td>
<td><b>'.$ETI['unae26activa'].'</b></td>
<td><b>'.$ETI['unae26nombre'].'</b></td>
<td colspan="2"><b>'.$ETI['unae26idresponsable'].'</b></td>
<td align="right">
'.html_paginador('paginaf227', $registros, $lineastabla, $pagina, 'paginarf227()').'
'.html_lpp('lppf227', $lineastabla, 'paginarf227()').'
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
		$et_unae26idzona='Sin Zonz';
		if ($filadet['unae26idzona']!=0){
			$sSQL1='SELECT unad23nombre FROM unad23zona WHERE unad23id='.$filadet['unae26idzona'];
			$tablanombre=$objDB->ejecutasql($sSQL1);
			if ($objDB->nf($tablanombre)>0){
				$filanom=$objDB->sf($tablanombre);
				$et_unae26idzona=$filanom['unad23nombre'];
				}
			}
		$et_unae26idzona=$sPrefijo.cadena_notildes($filadet['unad23nombre']).$sSufijo;
		$et_unae26activa=$sPrefijo.$ETI['no'].$sSufijo;
		if ($filadet['unae26activa']=='S'){$et_unae26activa=$sPrefijo.$ETI['si'].$sSufijo;}
		$et_unae26nombre=$sPrefijo.cadena_notildes($filadet['unae26nombre']).$sSufijo;
		$et_unae26fractal=$sPrefijo.cadena_notildes($filadet['unae26fractal']).$sSufijo;
		$et_unae26idresponsable=$sPrefijo.$filadet['unae26idresponsable'].$sSufijo;
		$et_unae26tituloresponsable=$sPrefijo.cadena_notildes($filadet['unae26tituloresponsable']).$sSufijo;
		if ($bAbierta){
			$sLink='<a href="javascript:cargaridf227('.$filadet['unae26id'].')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$res=$res.'<tr'.$sClass.'>
<td>'.$et_unae26idzona.'</td>
<td>'.$et_unae26activa.'</td>
<td>'.$et_unae26nombre.'</td>
<td>'.$sPrefijo.$filadet['C8_td'].' '.$filadet['C8_doc'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['C8_nombre']).$sSufijo.'</td>
<td>'.$sLink.'</td>
</tr>';
		}
	$res=$res.'</table>';
	$objDB->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
	}
function f227_Clonar($unae27consec, $unae27consecPadre, $objDB){
	$sError='';
	$unae27id=tabla_consecutivo('unae26unidadesfun', 'unae27id', '', $objDB);
	if ($unae27id==-1){$sError=$objDB->serror;}
	if ($sError==''){
		$sCampos227='unae26consec, unae26idzona, unae26id, unae26activa, unae26nombre, unae26fractal, unae26unidadpadre, unae26idresponsable, unae26tituloresponsable';
		$sValores227='';
		$sSQL='SELECT * FROM unae26unidadesfun WHERE unae27consec='.$unae27consecPadre.'';
		$tabla=$objDB->ejecutasql($sSQL);
		while($fila=$objDB->sf($tabla)){
			if ($sValores227!=''){$sValores227=$sValores227.', ';}
			$sValores227=$sValores227.'('.$unae27consec.', '.$fila['unae26idzona'].', '.$unae27id.', "'.$fila['unae26activa'].'", "'.$fila['unae26nombre'].'", "'.$fila['unae26fractal'].'", '.$fila['unae26unidadpadre'].', '.$fila['unae26idresponsable'].', "'.$fila['unae26tituloresponsable'].'")';
			$unae27id++;
			}
		if ($sValores227!=''){
			$sSQL='INSERT INTO unae26unidadesfun('.$sCampos227.') VALUES '.$sValores227.'';
			$result=$objDB->ejecutasql($sSQL);
			}
		}
	return $sError;
	}
// -- 227 Fractales XAJAX 
function f227_Guardar($valores, $aParametros){
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
		list($sError, $iAccion, $unae27id, $sDebugGuardar)=f227_db_Guardar($valores, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugGuardar;
		}
	$objResponse=new xajaxResponse();
	if ($sError==''){
		list($sdetalle, $sDebugTabla)=f227_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugTabla;
		$objResponse->assign('div_f227detalle', 'innerHTML', $sdetalle);
		//if ($iAccion==2){
			//$objResponse->call('cargaridf227('.$unae27id.')');
			//}else{
			$objResponse->call('limpiaf227');
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
function f227_Traer($aParametros){
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
		$unae27consec=numeros_validar($aParametros[1]);
		$unae27idzona=numeros_validar($aParametros[2]);
		if (($unae27consec!='')&&($unae27idzona!='')){$besta=true;}
		}else{
		$unae27id=$aParametros[103];
		if ((int)$unae27id!=0){$besta=true;}
		}
	if ($besta){
		$besta=false;
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		$bHayDb=true;
		$sSQLcondi='';
		if ($paso==1){
			$sSQLcondi=$sSQLcondi.'unae26consec='.$unae27consec.' AND unae26idzona='.$unae27idzona.'';
			}else{
			$sSQLcondi=$sSQLcondi.'unae26id='.$unae27id.'';
			}
		$sSQL='SELECT * FROM unae26unidadesfun WHERE '.$sSQLcondi;
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
		$unae27idresponsable_id=(int)$fila['unae26idresponsable'];
		$unae27idresponsable_td=$APP->tipo_doc;
		$unae27idresponsable_doc='';
		$unae27idresponsable_nombre='';
		if ($unae27idresponsable_id!=0){
			list($unae27idresponsable_nombre, $unae27idresponsable_id, $unae27idresponsable_td, $unae27idresponsable_doc)=html_tercero($unae27idresponsable_td, $unae27idresponsable_doc, $unae27idresponsable_id, 0, $objDB);
			}
		list($unae27idzona_nombre, $serror_det)=tabla_campoxid('unad23zona','unad23nombre','unad23id', $fila['unae26idzona'],'{'.$ETI['msg_sindato'].'}', $objDB);
		$html_unae27idzona=html_oculto('unae27idzona', $fila['unae26idzona'], $unae27idzona_nombre);
		$objResponse->assign('div_unae27idzona', 'innerHTML', $html_unae27idzona);
		$unae27id_nombre='';
		$html_unae27id=html_oculto('unae27id', $fila['unae26id'], $unae27id_nombre);
		$objResponse->assign('div_unae27id', 'innerHTML', $html_unae27id);
		$objResponse->assign('unae27activa', 'value', $fila['unae26activa']);
		$objResponse->assign('unae27nombre', 'value', $fila['unae26nombre']);
		$objResponse->assign('unae27fractal', 'value', $fila['unae26fractal']);
		$objResponse->assign('unae27unidadpadre', 'value', $fila['unae26unidadpadre']);
		$objResponse->assign('unae27idresponsable', 'value', $fila['unae26idresponsable']);
		$objResponse->assign('unae27idresponsable_td', 'value', $unae27idresponsable_td);
		$objResponse->assign('unae27idresponsable_doc', 'value', $unae27idresponsable_doc);
		$objResponse->assign('div_unae27idresponsable', 'innerHTML', $unae27idresponsable_nombre);
		$objResponse->assign('unae27tituloresponsable', 'value', $fila['unae26tituloresponsable']);
		$objResponse->assign('unae27orden', 'value', $fila['unae26orden']);
		$objResponse->call("MensajeAlarmaV2('', 0)");
		$objResponse->call("verboton('belimina227','block')");
		}else{
		if ($paso==1){
			$objResponse->assign('unae27idzona', 'value', $unae27idzona);
			}else{
			$objResponse->call('MensajeAlarmaV2("No se encontro el registro de referencia:'.$unae27id.'", 0)');
			}
		}
	if ($bHayDb){
		$objDB->CerrarConexion();
		}
	return $objResponse;
	}
function f227_Eliminar($aParametros){
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
	list($sError, $sDebugElimina)=f227_db_Eliminar($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugElimina;
	$objResponse=new xajaxResponse();
	if ($sError==''){
		list($sDetalle, $sDebugTabla)=f227_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugTabla;
		$objResponse->assign('div_f227detalle', 'innerHTML', $sDetalle);
		$objResponse->call('limpiaf227');
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
function f227_HtmlTabla($aParametros){
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
	list($sDetalle, $sDebugTabla)=f227_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f227detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f227_PintarLlaves($aParametros){
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
	$html_unae27idzona=f227_HTMLComboV2_unae27idzona($objDB, $objCombos, 0);
	$html_unae27id='<input id="unae27id" name="unae27id" type="hidden" value=""/>';
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_unae27idzona','innerHTML', $html_unae27idzona);
	$objResponse->assign('div_unae27id','innerHTML', $html_unae27id);
	return $objResponse;
	}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------
?>