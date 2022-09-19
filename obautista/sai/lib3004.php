<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2020 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.24.1 martes, 11 de febrero de 2020
--- 3004 Anexos
*/
function f3004_db_Guardar($valores, $objDB, $bDebug=false){
	$iCodModulo=3004;
	$bAudita[2]=false;
	$bAudita[3]=false;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3004='lg/lg_3004_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3004)){$mensajes_3004='lg/lg_3004_es.php';}
	require $mensajes_todas;
	require $mensajes_3004;
	$sError='';
	$sDebug='';
	$bInserta=false;
	$iAccion=3;
	if(!is_array($valores)){$valores=json_decode(str_replace('\"','"',$valores),true);}
	$saiu04idtema=numeros_validar($valores[1]);
	$saiu04consec=numeros_validar($valores[2]);
	$saiu04id=numeros_validar($valores[3], true);
	$saiu04activo=htmlspecialchars(trim($valores[4]));
	$saiu04orden=numeros_validar($valores[5]);
	$saiu04obligatorio=htmlspecialchars(trim($valores[6]));
	$saiu04titulo=htmlspecialchars(trim($valores[7]));
	$saiu04descripcion=htmlspecialchars(trim($valores[8]));
	$saiu04idtipogd=numeros_validar($valores[9]);
	$saiu04idetapa=numeros_validar($valores[12]);
	//if ($saiu04orden==''){$saiu04orden=0;}
	//if ($saiu04idtipogd==''){$saiu04idtipogd=0;}
	//if ($saiu04idetapa==''){$saiu04idetapa=0;}
	$sSepara=', ';
	if ($saiu04idetapa==''){$sError=$ERR['saiu04idetapa'].$sSepara.$sError;}
	if ($saiu04idtipogd==''){$sError=$ERR['saiu04idtipogd'].$sSepara.$sError;}
	//if ($saiu04descripcion==''){$sError=$ERR['saiu04descripcion'].$sSepara.$sError;}
	if ($saiu04titulo==''){$sError=$ERR['saiu04titulo'].$sSepara.$sError;}
	if ($saiu04obligatorio==''){$sError=$ERR['saiu04obligatorio'].$sSepara.$sError;}
	//if ($saiu04orden==''){$sError=$ERR['saiu04orden'].$sSepara.$sError;}
	if ($saiu04activo==''){$sError=$ERR['saiu04activo'].$sSepara.$sError;}
	//if ($saiu04id==''){$sError=$ERR['saiu04id'].$sSepara.$sError;}//CONSECUTIVO
	//if ($saiu04consec==''){$sError=$ERR['saiu04consec'].$sSepara.$sError;}//CONSECUTIVO
	if ($saiu04idtema==''){$sError=$ERR['saiu04idtema'].$sSepara.$sError;}
	if ($sError==''){
		if ((int)$saiu04id==0){
			if ((int)$saiu04consec==0){
				$saiu04consec=tabla_consecutivo('saiu04temaanexo', 'saiu04consec', 'saiu04idtema='.$saiu04idtema.'', $objDB);
				if ($saiu04consec==-1){$sError=$objDB->serror;}
				}else{
				if (!seg_revisa_permiso($iCodModulo, 8, $objDB)){$sError=$ERR['8'];}
				}
			if ($sError==''){
				$sSQL='SELECT saiu04idtema FROM saiu04temaanexo WHERE saiu04idtema='.$saiu04idtema.' AND saiu04consec='.$saiu04consec.'';
				$result=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($result)!=0){
					$sError=$ERR['existe'];
					}else{
					if (!seg_revisa_permiso($iCodModulo, 2, $objDB)){$sError=$ERR['2'];}
					}
				}
			if ($sError==''){
				$saiu04id=tabla_consecutivo('saiu04temaanexo', 'saiu04id', '', $objDB);
				if ($saiu04id==-1){$sError=$objDB->serror;}
				$bInserta=true;
				$iAccion=2;
				}
			}else{
			if (!seg_revisa_permiso($iCodModulo, 3, $objDB)){$sError=$ERR['3'];}
			}
		}
	if ($sError==''){
		if ($bInserta){
			$saiu04idtipogd=0;
			$saiu04idorigenforma=0;
			$saiu04idarchforma=0;
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
		if ($saiu04orden==''){$saiu04orden=$saiu04consec;}
		//Si el campo saiu04descripcion permite html quite la linea htmlspecialchars para el campo y habilite la siguiente linea:
		//$saiu04descripcion=str_replace('"', '\"', $saiu04descripcion);
		$saiu04descripcion=str_replace('"', '\"', $saiu04descripcion);
		if ($bInserta){
			$sCampos3003='saiu04idtema, saiu04consec, saiu04id, saiu04activo, saiu04orden, 
saiu04obligatorio, saiu04titulo, saiu04descripcion, saiu04idtipogd, saiu04idorigenforma, 
saiu04idarchforma, saiu04idetapa';
			$sValores3003=''.$saiu04idtema.', '.$saiu04consec.', '.$saiu04id.', "'.$saiu04activo.'", '.$saiu04orden.', 
"'.$saiu04obligatorio.'", "'.$saiu04titulo.'", "'.$saiu04descripcion.'", '.$saiu04idtipogd.', 0, 
0, '.$saiu04idetapa.'';
			if ($APP->utf8==1){
				$sSQL='INSERT INTO saiu04temaanexo ('.$sCampos3003.') VALUES ('.utf8_encode($sValores3003).');';
				}else{
				$sSQL='INSERT INTO saiu04temaanexo ('.$sCampos3003.') VALUES ('.$sValores3003.');';
				}
			$result=$objDB->ejecutasql($sSQL);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' {Anexos}.<!-- '.$sSQL.' -->';
				}else{
				if ($bAudita[2]){
					seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 2, $saiu04id, $sSQL, $objDB);
					}
				}
			}else{
			$scampo3004[1]='saiu04activo';
			$scampo3004[2]='saiu04orden';
			$scampo3004[3]='saiu04obligatorio';
			$scampo3004[4]='saiu04titulo';
			$scampo3004[5]='saiu04descripcion';
			$scampo3004[6]='saiu04idetapa';
			$svr3004[1]=$saiu04activo;
			$svr3004[2]=$saiu04orden;
			$svr3004[3]=$saiu04obligatorio;
			$svr3004[4]=$saiu04titulo;
			$svr3004[5]=$saiu04descripcion;
			$svr3004[6]=$saiu04idetapa;
			$inumcampos=6;
			$sWhere='saiu04id='.$saiu04id.'';
			//$sWhere='saiu04idtema='.$saiu04idtema.' AND saiu04consec='.$saiu04consec.'';
			$sSQL='SELECT * FROM saiu04temaanexo WHERE '.$sWhere;
			$sdatos='';
			$bpasa=false;
			$result=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($result)>0){
				$filaorigen=$objDB->sf($result);
				for ($k=1;$k<=$inumcampos;$k++){
					if ($filaorigen[$scampo3004[$k]]!=$svr3004[$k]){
						if ($sdatos!=''){$sdatos=$sdatos.', ';}
						$sdatos=$sdatos.$scampo3004[$k].'="'.$svr3004[$k].'"';
						$bpasa=true;
						}
					}
				}
			if ($bpasa){
				if ($APP->utf8==1){
					$sSQL='UPDATE saiu04temaanexo SET '.utf8_encode($sdatos).' WHERE '.$sWhere.';';
					}else{
					$sSQL='UPDATE saiu04temaanexo SET '.$sdatos.' WHERE '.$sWhere.';';
					}
				$result=$objDB->ejecutasql($sSQL);
				if ($result==false){
					$sError=$ERR['falla_guardar'].' {Anexos}. <!-- '.$sSQL.' -->';
					}else{
					if ($bAudita[3]){
						seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 3, $saiu04id, $sSQL, $objDB);
						}
					}
				}
			}
		}
	return array($sError, $iAccion, $saiu04id, $sDebug);
	}
function f3004_db_Eliminar($aParametros, $objDB, $bDebug=false){
	$iCodModulo=3004;
	$bAudita[4]=false;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3004='lg/lg_3004_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3004)){$mensajes_3004='lg/lg_3004_es.php';}
	require $mensajes_todas;
	require $mensajes_3004;
	$sError='';
	$sDebug='';
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$saiu04idtema=numeros_validar($aParametros[1]);
	$saiu04consec=numeros_validar($aParametros[2]);
	$saiu04id=numeros_validar($aParametros[3]);
	if ($sError==''){
		if (!seg_revisa_permiso($iCodModulo, 4, $objDB)){
			$sError=$ERR['4'];
			}
		}
	if ($sError==''){
		$sSQL='SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=3004';
		$tablaor=$objDB->ejecutasql($sSQL);
		while ($filaor=$objDB->sf($tablaor)){
			$sSQL='SELECT '.$filaor['campoid'].' FROM '.$filaor['tabla'].' WHERE '.$filaor['camporev'].'='.$saiu04id.' LIMIT 0, 1';
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
		$sWhere='saiu04id='.$saiu04id.'';
		//$sWhere='saiu04idtema='.$saiu04idtema.' AND saiu04consec='.$saiu04consec.'';
		$sSQL='DELETE FROM saiu04temaanexo WHERE '.$sWhere.';';
		$result=$objDB->ejecutasql($sSQL);
		if ($result==false){
			$sError=$ERR['falla_eliminar'].' {3004 Anexos}.<!-- '.$sSQL.' -->';
			}else{
			if ($bAudita[4]){
				seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $saiu04id, $sSQL, $objDB);
				}
			}
		}
	return array($sError, $sDebug);
	}
function f3004_TablaDetalleV2($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3004='lg/lg_3004_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3004)){$mensajes_3004='lg/lg_3004_es.php';}
	require $mensajes_todas;
	require $mensajes_3004;
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
	$saiu03id=$aParametros[0];
	$idTercero=$aParametros[100];
	$pagina=$aParametros[101];
	$lineastabla=$aParametros[102];
	$bAbierta=true;
	//$sSQL='SELECT Campo FROM saiu03temasol WHERE saiu03id='.$saiu03id;
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
	$sTitulos='Tema, Consec, Id, Activo, Orden, Obligatorio, Titulo, Descripcion, Tipogd, Origenforma, Archforma';
	$sSQL='SELECT TB.saiu04idtema, TB.saiu04consec, TB.saiu04id, TB.saiu04activo, TB.saiu04orden, TB.saiu04obligatorio, TB.saiu04titulo, TB.saiu04descripcion, TB.saiu04idtipogd, TB.saiu04idorigenforma, TB.saiu04idarchforma, TB.saiu04idetapa 
FROM saiu04temaanexo AS TB 
WHERE '.$sSQLadd1.' TB.saiu04idtema='.$saiu03id.' '.$sSQLadd.'
ORDER BY TB.saiu04idetapa, TB.saiu04orden, TB.saiu04consec';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_3004" name="consulta_3004" type="hidden" value="'.$sSQLlista.'"/>
<input id="titulos_3004" name="titulos_3004" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objDB->ejecutasql($sSQL);
	if ($tabladetalle==false){
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 3004: '.$sSQL.'<br>';}
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		//$sLeyenda=$sSQL;
		}else{
		$registros=$objDB->nf($tabladetalle);
		if ($registros==0){
			return array(utf8_encode($sErrConsulta.'<input id="paginaf3004" name="paginaf3004" type="hidden" value="'.$pagina.'"/><input id="lppf3004" name="lppf3004" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
<td><b>'.$ETI['saiu04consec'].'</b></td>
<td><b>'.$ETI['saiu04idetapa'].'</b></td>
<td><b>'.$ETI['saiu04orden'].'</b></td>
<td><b>'.$ETI['saiu04activo'].'</b></td>
<td><b>'.$ETI['saiu04obligatorio'].'</b></td>
<td><b>'.$ETI['saiu04titulo'].'</b></td>
<td><b>'.$ETI['saiu04idarchforma'].'</b></td>
<td align="right">
'.html_paginador('paginaf3004', $registros, $lineastabla, $pagina, 'paginarf3004()').'
'.html_lpp('lppf3004', $lineastabla, 'paginarf3004()').'
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
		$et_saiu04consec=$sPrefijo.$filadet['saiu04consec'].$sSufijo;
		$et_saiu04activo=$sPrefijo.$ETI['no'].$sSufijo;
		if ($filadet['saiu04activo']=='S'){$et_saiu04activo=$sPrefijo.$ETI['si'].$sSufijo;}
		$et_saiu04orden=$sPrefijo.$filadet['saiu04orden'].$sSufijo;
		$et_saiu04obligatorio=$sPrefijo.$ETI['no'].$sSufijo;
		if ($filadet['saiu04obligatorio']=='S'){$et_saiu04obligatorio=$sPrefijo.$ETI['si'].$sSufijo;}
		$et_saiu04titulo=$sPrefijo.cadena_notildes($filadet['saiu04titulo']).$sSufijo;
		//$et_saiu04descripcion=$sPrefijo.cadena_notildes($filadet['saiu04descripcion']).$sSufijo;
		//$et_saiu04idtipogd=$sPrefijo.$filadet['saiu04idtipogd'].$sSufijo;
		$et_saiu04idarchforma='';
		if ($filadet['saiu04idarchforma']!=0){
			//$et_saiu04idarchforma='<img src="verarchivo.php?cont='.$filadet['saiu04idorigenforma'].'&id='.$filadet['saiu04idarchforma'].'&maxx=150"/>';
			$et_saiu04idarchforma=html_lnkarchivo((int)$filadet['saiu04idorigenforma'], (int)$filadet['saiu04idarchforma']);
			}
		$et_saiu04idetapa=$sPrefijo.$filadet['saiu04idetapa'].$sSufijo;
		if ($bAbierta){
			$sLink='<a href="javascript:cargaridf3004('.$filadet['saiu04id'].')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$res=$res.'<tr'.$sClass.'>
<td>'.$et_saiu04consec.'</td>
<td>'.$et_saiu04idetapa.'</td>
<td>'.$et_saiu04orden.'</td>
<td>'.$et_saiu04activo.'</td>
<td>'.$et_saiu04obligatorio.'</td>
<td>'.$et_saiu04titulo.'</td>
<td>'.$et_saiu04idarchforma.'</td>
<td>'.$sLink.'</td>
</tr>';
		}
	$res=$res.'</table>';
	$objDB->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
	}
function f3004_Clonar($saiu04idtema, $saiu04idtemaPadre, $objDB){
	$sError='';
	$saiu04consec=tabla_consecutivo('saiu04temaanexo', 'saiu04consec', 'saiu04idtema='.$saiu04idtema.'', $objDB);
	if ($saiu04consec==-1){$sError=$objDB->serror;}
	$saiu04id=tabla_consecutivo('saiu04temaanexo', 'saiu04id', '', $objDB);
	if ($saiu04id==-1){$sError=$objDB->serror;}
	if ($sError==''){
		$sCampos3004='saiu04idtema, saiu04consec, saiu04id, saiu04activo, saiu04orden, saiu04obligatorio, saiu04titulo, saiu04descripcion, saiu04idtipogd, saiu04idorigenforma, saiu04idarchforma, saiu04idetapa';
		$sValores3004='';
		$sSQL='SELECT * FROM saiu04temaanexo WHERE saiu04idtema='.$saiu04idtemaPadre.'';
		$tabla=$objDB->ejecutasql($sSQL);
		while($fila=$objDB->sf($tabla)){
			if ($sValores3004!=''){$sValores3004=$sValores3004.', ';}
			$sValores3004=$sValores3004.'('.$saiu04idtema.', '.$saiu04consec.', '.$saiu04id.', "'.$fila['saiu04activo'].'", '.$fila['saiu04orden'].', "'.$fila['saiu04obligatorio'].'", "'.$fila['saiu04titulo'].'", "'.$fila['saiu04descripcion'].'", '.$fila['saiu04idtipogd'].', "'.$fila['saiu04idorigenforma'].'", "'.$fila['saiu04idarchforma'].'", '.$fila['saiu04idetapa'].')';
			$saiu04consec++;
			$saiu04id++;
			}
		if ($sValores3004!=''){
			$sSQL='INSERT INTO saiu04temaanexo('.$sCampos3004.') VALUES '.$sValores3004.'';
			$result=$objDB->ejecutasql($sSQL);
			}
		}
	return $sError;
	}
// -- 3004 Anexos XAJAX 
function elimina_archivo_saiu04idarchforma($idpadre){
	$_SESSION['u_ultimominuto']=iminutoavance();
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	archivo_eliminar('saiu04temaanexo', 'saiu04id', 'saiu04idorigenforma', 'saiu04idarchforma', $idpadre, $objDB);
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->call("limpia_saiu04idarchforma");
	return $objResponse;
	}
function f3004_Guardar($valores, $aParametros){
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
		list($sError, $iAccion, $saiu04id, $sDebugGuardar)=f3004_db_Guardar($valores, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugGuardar;
		}
	$objResponse=new xajaxResponse();
	if ($sError==''){
		list($sdetalle, $sDebugTabla)=f3004_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugTabla;
		$objResponse->assign('div_f3004detalle', 'innerHTML', $sdetalle);
		//if ($iAccion==2){
			//$objResponse->call('cargaridf3004('.$saiu04id.')');
			//}else{
			$objResponse->call('limpiaf3004');
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
function f3004_Traer($aParametros){
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
		$saiu04idtema=numeros_validar($aParametros[1]);
		$saiu04consec=numeros_validar($aParametros[2]);
		if (($saiu04idtema!='')&&($saiu04consec!='')){$besta=true;}
		}else{
		$saiu04id=$aParametros[103];
		if ((int)$saiu04id!=0){$besta=true;}
		}
	if ($besta){
		$besta=false;
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		$bHayDb=true;
		$sSQLcondi='';
		if ($paso==1){
			$sSQLcondi=$sSQLcondi.'saiu04idtema='.$saiu04idtema.' AND saiu04consec='.$saiu04consec.'';
			}else{
			$sSQLcondi=$sSQLcondi.'saiu04id='.$saiu04id.'';
			}
		$sSQL='SELECT * FROM saiu04temaanexo WHERE '.$sSQLcondi;
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
		$saiu04consec_nombre='';
		$html_saiu04consec=html_oculto('saiu04consec', $fila['saiu04consec'], $saiu04consec_nombre);
		$objResponse->assign('div_saiu04consec', 'innerHTML', $html_saiu04consec);
		$saiu04id_nombre='';
		$html_saiu04id=html_oculto('saiu04id', $fila['saiu04id'], $saiu04id_nombre);
		$objResponse->assign('div_saiu04id', 'innerHTML', $html_saiu04id);
		$objResponse->assign('saiu04activo', 'value', $fila['saiu04activo']);
		$objResponse->assign('saiu04orden', 'value', $fila['saiu04orden']);
		$objResponse->assign('saiu04obligatorio', 'value', $fila['saiu04obligatorio']);
		$objResponse->assign('saiu04titulo', 'value', $fila['saiu04titulo']);
		$objResponse->assign('saiu04descripcion', 'value', $fila['saiu04descripcion']);
		$saiu04idtipogd_eti=$fila['saiu04idtipogd'];
		$html_saiu04idtipogd=html_oculto('saiu04idtipogd', $fila['saiu04idtipogd'], $saiu04idtipogd_eti);
		$objResponse->assign('div_saiu04idtipogd', 'innerHTML', $html_saiu04idtipogd);
		$objResponse->assign('saiu04idorigenforma', 'value', $fila['saiu04idorigenforma']);
		$idorigen=(int)$fila['saiu04idorigenforma'];
		$objResponse->assign('saiu04idarchforma', 'value', $fila['saiu04idarchforma']);
		$objResponse->call("verboton('banexasaiu04idarchforma', 'block')");
		$stemp='none';
		$stemp2=html_lnkarchivo($idorigen, (int)$fila['saiu04idarchforma']);
		if ((int)$fila['saiu04idarchforma']!=0){$stemp='block';}
		$objResponse->assign('div_saiu04idarchforma', 'innerHTML', $stemp2);
		$objResponse->call("verboton('beliminasaiu04idarchforma','".$stemp."')");
		$objResponse->assign('saiu04idetapa', 'value', $fila['saiu04idetapa']);
		$objResponse->call("MensajeAlarmaV2('', 0)");
		$objResponse->call("verboton('belimina3004','block')");
		}else{
		if ($paso==1){
			$objResponse->assign('saiu04consec', 'value', $saiu04consec);
			}else{
			$objResponse->call('MensajeAlarmaV2("No se encontro el registro de referencia:'.$saiu04id.'", 0)');
			}
		}
	if ($bHayDb){
		$objDB->CerrarConexion();
		}
	return $objResponse;
	}
function f3004_Eliminar($aParametros){
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
	list($sError, $sDebugElimina)=f3004_db_Eliminar($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugElimina;
	$objResponse=new xajaxResponse();
	if ($sError==''){
		list($sDetalle, $sDebugTabla)=f3004_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugTabla;
		$objResponse->assign('div_f3004detalle', 'innerHTML', $sDetalle);
		$objResponse->call('limpiaf3004');
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
function f3004_HtmlTabla($aParametros){
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
	list($sDetalle, $sDebugTabla)=f3004_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f3004detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f3004_PintarLlaves($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	//if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	if (isset($APP->piel)==0){$APP->piel=1;}
	$iPiel=$APP->piel;
	$html_saiu04consec='<input id="saiu04consec" name="saiu04consec" type="text" value="" onchange="revisaf3004()" class="cuatro"/>';
	$html_saiu04id='<input id="saiu04id" name="saiu04id" type="hidden" value=""/>';
	$html_saiu04idtipogd=html_oculto('saiu04idtipogd', '');
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_saiu04consec','innerHTML', $html_saiu04consec);
	$objResponse->assign('div_saiu04id','innerHTML', $html_saiu04id);
	$objResponse->assign('div_saiu04idtipogd','innerHTML', $html_saiu04idtipogd);
	return $objResponse;
	}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------
?>