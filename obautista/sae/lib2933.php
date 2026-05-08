<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2022 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.28.1 jueves, 7 de abril de 2022
--- 2933 Acceso a cursos
*/
function f2933_HTMLComboV2_plab33idperiodo($objDB, $objCombos, $valor){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('plab33idperiodo', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	$objCombos->iAncho=420;
	$objCombos->sAccion='carga_combo_plab33idcurso()';
	$sSQL=f146_ConsultaCombo('exte02id>0');
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f2933_HTMLComboV2_plab33idcurso($objDB, $objCombos, $valor, $vrplab33idperiodo){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('plab33idcurso', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	$objCombos->iAncho=420;
	$objCombos->sAccion='revisaf2933()';
	$sSQL='';
	if ((int)$vrplab33idperiodo!=0){
		$sIds='-99';
		$sSQL='SELECT ofer08idcurso FROM ofer08oferta WHERE ofer08idper_aca='.$vrplab33idperiodo.' AND ofer08cead=0 AND ofer08estadooferta=1';
		$tabla=$objDB->ejecutasql($sSQL);
		while($fila=$objDB->sf($tabla)){
			$sIds=$sIds.','.$fila['ofer08idcurso'];
			}
		$sSQL='SELECT unad40id AS id, CONCAT(unad40titulo, " - ", unad40nombre) AS nombre 
		FROM unad40curso 
		WHERE unad40id IN ('.$sIds.')
		ORDER BY unad40nombre';
		}
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f2933_Comboplab33idcurso($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$objCombos=new clsHtmlCombos();
	$html_plab33idcurso=f2933_HTMLComboV2_plab33idcurso($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_plab33idcurso', 'innerHTML', $html_plab33idcurso);
	$objResponse->call('$("#plab33idcurso").chosen()');
	return $objResponse;
	}
function f2933_db_Guardar($valores, $objDB, $bDebug=false, $idTercero=0){
	$iCodModulo=2933;
	$bAudita[2]=false;
	$bAudita[3]=false;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2933='lg/lg_2933_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2933)){$mensajes_2933='lg/lg_2933_es.php';}
	require $mensajes_todas;
	require $mensajes_2933;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	if ($idTercero==0){$idTercero=$_SESSION['unad_id_tercero'];}
	$bInserta=false;
	$iAccion=3;
	if(!is_array($valores)){$valores=json_decode(str_replace('\"','"',$valores),true);}
	$plab33idciclo=numeros_validar($valores[1]);
	$plab33idmonitor=numeros_validar($valores[2]);
	$plab33idperiodo=numeros_validar($valores[3]);
	$plab33idcurso=numeros_validar($valores[4]);
	$plab33id=numeros_validar($valores[5], true);
	$plab33activo=numeros_validar($valores[6]);
	//if ($plab33activo==''){$plab33activo=0;}
	// -- Seccion para validar los posibles causales de error.
	$sSepara=', ';
	if ($plab33activo==''){$sError=$ERR['plab33activo'].$sSepara.$sError;}
	if ((int)$plab33idmonitor==0){$sError=$ERR['plab33idmonitor'].$sSepara.$sError;}
	if ((int)$plab33idperiodo==0){$sError=$ERR['plab33idperiodo'].$sSepara.$sError;}
	if ((int)$plab33idcurso==0){$sError=$ERR['plab33idcurso'].$sSepara.$sError;}
	//if ($plab33id==''){$sError=$ERR['plab33id'].$sSepara.$sError;}//CONSECUTIVO
	// -- Se verifican los valores de campos de otras tablas.
	if ($sError==''){
		list($sError, $sInfo)=tercero_Bloqueado($plab33idmonitor, $objDB);
		if ($sInfo!=''){$sError=$sError.'<br>'.$sInfo;}
		}
	if ($sError==''){
		//Verificar que haga parte de la convocatoria.
		$sSQL='SELECT 1 FROM plab32emonitor WHERE plab32idciclo='.$plab33idciclo.' AND plab32idtercero='.$plab33idmonitor.'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)==0){
			$sError='El tercero no se encuentra registrado como E-monitor';
			}
		}
	$bQuitarCodigo=false;
	$sCampoCodigo='';
	if ($sError==''){
		if ((int)$plab33id==0){
			if ($sError==''){
				$sSQL='SELECT 1 FROM plab33emoncurso WHERE plab33idciclo='.$plab33idciclo.' AND plab33idmonitor="'.$plab33idmonitor.'" AND plab33idperiodo='.$plab33idperiodo.' AND plab33idcurso='.$plab33idcurso.'';
				$result=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($result)!=0){
					$sError=$ERR['existe'];
					}else{
					list($bDevuelve, $sDebugP)=seg_revisa_permisoV3($iCodModulo, 2, $idTercero, $objDB);
					if (!$bDevuelve){$sError=$ERR['2'];}
					}
				}
			if ($sError==''){
				$plab33id=tabla_consecutivo('plab33emoncurso', 'plab33id', '', $objDB);
				if ($plab33id==-1){$sError=$objDB->serror;}
				$bInserta=true;
				$iAccion=2;
				}
			}else{
			list($bDevuelve, $sDebugP)=seg_revisa_permisoV3($iCodModulo, 3, $idTercero, $objDB);
			if (!$bDevuelve){$sError=$ERR['3'];}
			}
		}
	if ($sError==''){
		if ($bInserta){
			//Preparar el Id, Si no lo hay se quita la comprobación.
			}
		}
	if ($sError==''){
		if ($bInserta){
			$sCampos2933='plab33idciclo, plab33idmonitor, plab33idperiodo, plab33idcurso, plab33id, 
			plab33activo';
			$sValores2933=''.$plab33idciclo.', '.$plab33idmonitor.', '.$plab33idperiodo.', '.$plab33idcurso.', '.$plab33id.', 
			'.$plab33activo.'';
			if ($APP->utf8==1){
				$sSQL='INSERT INTO plab33emoncurso ('.$sCampos2933.') VALUES ('.cadena_codificar($sValores2933).');';
				}else{
				$sSQL='INSERT INTO plab33emoncurso ('.$sCampos2933.') VALUES ('.$sValores2933.');';
				}
			if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Guardar 2933 '.$sSQL.'<br>';}
			$result=$objDB->ejecutasql($sSQL);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' [2933].<!-- '.$sSQL.' -->';
				}else{
				if ($bAudita[2]){
					seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 2, $plab33id, $sSQL, $objDB);
					}
				}
			}else{
			$scampo2933[1]='plab33activo';
			$svr2933[1]=$plab33activo;
			$inumcampos=1;
			$sWhere='plab33id='.$plab33id.'';
			//$sWhere='plab33idciclo='.$plab33idciclo.' AND plab33idmonitor="'.$plab33idmonitor.'" AND plab33idperiodo='.$plab33idperiodo.' AND plab33idcurso='.$plab33idcurso.'';
			$sSQL='SELECT * FROM plab33emoncurso WHERE '.$sWhere;
			$sdatos='';
			$bPasa=false;
			$result=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($result)>0){
				$filaorigen=$objDB->sf($result);
				for ($k=1;$k<=$inumcampos;$k++){
					if ($filaorigen[$scampo2933[$k]]!=$svr2933[$k]){
						if ($sdatos!=''){$sdatos=$sdatos.', ';}
						$sdatos=$sdatos.$scampo2933[$k].'="'.$svr2933[$k].'"';
						$bPasa=true;
						}
					}
				}
			if ($bPasa){
				if ($APP->utf8==1){
					$sSQL='UPDATE plab33emoncurso SET '.cadena_codificar($sdatos).' WHERE '.$sWhere.';';
					}else{
					$sSQL='UPDATE plab33emoncurso SET '.$sdatos.' WHERE '.$sWhere.';';
					}
				if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Guardar 2933 '.$sSQL.'<br>';}
				$result=$objDB->ejecutasql($sSQL);
				if ($result==false){
					$sError=$ERR['falla_guardar'].' {Acceso a cursos}. <!-- '.$sSQL.' -->';
					}else{
					if ($bAudita[3]){
						seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 3, $plab33id, $sSQL, $objDB);
						}
					}
				}
			}
		}
	return array($sError, $iAccion, $plab33id, $sDebug);
	}
function f2933_db_Eliminar($aParametros, $objDB, $bDebug=false, $idTercero=0){
	$iCodModulo=2933;
	$bAudita[4]=false;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2933='lg/lg_2933_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2933)){$mensajes_2933='lg/lg_2933_es.php';}
	require $mensajes_todas;
	require $mensajes_2933;
	$sError='';
	$sDebug='';
	if ($idTercero==0){$idTercero=$_SESSION['unad_id_tercero'];}
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$plab33idciclo=numeros_validar($aParametros[1]);
	$plab33idmonitor=numeros_validar($aParametros[2]);
	$plab33idperiodo=numeros_validar($aParametros[3]);
	$plab33idcurso=numeros_validar($aParametros[4]);
	$plab33id=numeros_validar($aParametros[5]);
	if ($sError==''){
		list($bDevuelve, $sDebugP)=seg_revisa_permisoV3($iCodModulo, 4, $idTercero, $objDB);
		if (!$bDevuelve){
			$sError=$ERR['4'];
			}
		}
	if ($sError==''){
		$sSQL='SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=2933';
		$tablaor=$objDB->ejecutasql($sSQL);
		while ($filaor=$objDB->sf($tablaor)){
			$sSQL='SELECT '.$filaor['campoid'].' FROM '.$filaor['tabla'].' WHERE '.$filaor['camporev'].'='.$plab33id.' LIMIT 0, 1';
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
		$sWhere='plab33id='.$plab33id.'';
		//$sWhere='plab33idciclo='.$plab33idciclo.' AND plab33idmonitor="'.$plab33idmonitor.'" AND plab33idperiodo='.$plab33idperiodo.' AND plab33idcurso='.$plab33idcurso.'';
		$sSQL='DELETE FROM plab33emoncurso WHERE '.$sWhere.';';
		$result=$objDB->ejecutasql($sSQL);
		if ($result==false){
			$sError=$ERR['falla_eliminar'].' {2933 Acceso a cursos}.<!-- '.$sSQL.' -->';
			}else{
			if ($bAudita[4]){
				seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $plab33id, $sSQL, $objDB);
				}
			}
		}
	return array($sError, $sDebug);
	}
function f2933_TablaDetalleV2($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2933='lg/lg_2933_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2933)){$mensajes_2933='lg/lg_2933_es.php';}
	require $mensajes_todas;
	require $mensajes_2933;
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	if (isset($aParametros[0])==0){$aParametros[0]=-1;}
	if (isset($aParametros[100])==0){$aParametros[100]=$_SESSION['unad_id_tercero'];}
	if (isset($aParametros[101])==0){$aParametros[101]=1;}
	if (isset($aParametros[102])==0){$aParametros[102]=20;}
	if (isset($aParametros[103])==0){$aParametros[103]='';}
	//if (isset($aParametros[104])==0){$aParametros[104]='';}
	$idTercero=$aParametros[100];
	$aParametros[0]=numeros_validar($aParametros[0]);
	if ($aParametros[0]==''){$aParametros[0]=-1;}
	$plab31id=$aParametros[0];
	if (true){
		//Leemos los parametros de entrada.
		$pagina=$aParametros[101];
		$lineastabla=$aParametros[102];
		$bDoc=trim($aParametros[103]);
		//$aParametros[104]=numeros_validar($aParametros[104]);
		}
	$sDebug='';
	$bAbierta=true;
	//$sSQL='SELECT Campo FROM plab31emonitoresciclo WHERE plab31id='.$plab31id;
	//$tabla=$objDB->ejecutasql($sSQL);
	//if ($objDB->nf($tabla)>0){
		//$fila=$objDB->sf($tabla);
		//if ($fila['Campo']!='S'){$bAbierta=true;}
		//}
	$sLeyenda='';
	$sBotones='<input id="paginaf2933" name="paginaf2933" type="hidden" value="'.$pagina.'"/>
	<input id="lppf2933" name="lppf2933" type="hidden" value="'.$lineastabla.'"/>';
	if ($sLeyenda!=''){
		$sLeyenda='<div class="salto1px"></div>
		<div class="GrupoCamposAyuda">
		'.$sLeyenda.'
		<div class="salto1px"></div>
		</div>';
		return array($sLeyenda.$sBotones, $sDebug);
		die();
		}
	$iPiel=iDefinirPiel($APP, 1);
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
	if ($bDoc!=''){$sSQLadd=$sSQLadd.' AND T2.unad11doc LIKE "%'.$bDoc.'%"';}
	/*
	if ($bNombre!=''){
		$sBase=strtoupper($bNombre);
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
	$sTitulos='Ciclo, Monitor, Periodo, Curso, Id, Activo';
	$sSQL='SELECT TB.plab33idciclo, T2.unad11razonsocial AS C2_nombre, TB.plab33idperiodo, TB.plab33idcurso, TB.plab33id, TB.plab33activo, 
	TB.plab33idmonitor, T2.unad11tipodoc AS C2_td, T2.unad11doc AS C2_doc, T40.unad40titulo, T40.unad40nombre 
	FROM plab33emoncurso AS TB, unad11terceros AS T2, unad40curso AS T40 
	WHERE '.$sSQLadd1.' TB.plab33idciclo='.$plab31id.' AND TB.plab33idmonitor=T2.unad11id AND TB.plab33idcurso=T40.unad40id '.$sSQLadd.'
	ORDER BY TB.plab33idmonitor, TB.plab33idperiodo DESC, TB.plab33idcurso DESC';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_2933" name="consulta_2933" type="hidden" value="'.$sSQLlista.'"/>
	<input id="titulos_2933" name="titulos_2933" type="hidden" value="'.$sTitulos.'"/>';
	if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 2933: '.$sSQL.'<br>';}
	$tabladetalle=$objDB->ejecutasql($sSQL);
	if ($tabladetalle==false){
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		//$sLeyenda=$sSQL;
		}else{
		$registros=$objDB->nf($tabladetalle);
		if ($registros==0){
			return array($sErrConsulta.$sBotones, $sDebug);
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
	<td colspan="2"><b>'.$ETI['plab33idmonitor'].'</b></td>
	<td><b>'.$ETI['plab33idperiodo'].'</b></td>
	<td colspan="2"><b>'.$ETI['plab33idcurso'].'</b></td>
	<td><b>'.$ETI['plab33activo'].'</b></td>
	<td align="right">
	'.html_paginador('paginaf2933', $registros, $lineastabla, $pagina, 'paginarf2933()').'
	'.html_lpp('lppf2933', $lineastabla, 'paginarf2933()').'
	</td>
	</tr></thead>';
	$tlinea=1;
	while($filadet=$objDB->sf($tabladetalle)){
		$sPrefijo='';
		$sSufijo='';
		$sClass=' class="resaltetabla"';
		$sLink='';
		if ($filadet['plab33activo']!=1){
			$sPrefijo='<span class="rojo">';
			$sSufijo='</span>';
			}
		if(($tlinea%2)!=0){$sClass='';}
		$tlinea++;
		$et_plab33idmonitor_doc='';
		$et_plab33idmonitor_nombre='';
		if ($filadet['plab33idmonitor']!=0){
			$et_plab33idmonitor_doc=$sPrefijo.$filadet['C2_td'].' '.$filadet['C2_doc'].$sSufijo;
			$et_plab33idmonitor_nombre=$sPrefijo.cadena_notildes($filadet['C2_nombre']).$sSufijo;
			}
		$et_plab33idperiodo=$sPrefijo.$filadet['plab33idperiodo'].$sSufijo;
		$et_plab33idcurso=$sPrefijo.$filadet['unad40titulo'].$sSufijo;
		$et_plab33idcurso_nombre=$sPrefijo.cadena_notildes($filadet['unad40nombre']).$sSufijo;
		$et_plab33activo=$sPrefijo.$ETI['si'].$sSufijo;
		if ($filadet['plab33activo']!=1){
			$et_plab33activo=$sPrefijo.$ETI['no'].$sSufijo;
			}
		if ($bAbierta){
			$sLink='<a href="javascript:cargaridf2933('.$filadet['plab33id'].')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$res=$res.'<tr'.$sClass.'>
		<td>'.$et_plab33idmonitor_doc.'</td>
		<td>'.$et_plab33idmonitor_nombre.'</td>
		<td>'.$et_plab33idperiodo.'</td>
		<td>'.$et_plab33idcurso.'</td>
		<td>'.$et_plab33idcurso_nombre.'</td>
		<td>'.$et_plab33activo.'</td>
		<td>'.$sLink.'</td>
		</tr>';
		}
	$res=$res.'</table>
	<div class="salto5px"></div>
	</div>';
	$objDB->liberar($tabladetalle);
	return array(cadena_codificar($res), $sDebug);
	}
// -- 2933 Acceso a cursos XAJAX 
function f2933_Guardar($valores, $aParametros){
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
	if (isset($opts[100])==0){$opts[100]=0;}
	//if(!is_array($valores)){$datos=json_decode(str_replace('\"','"',$valores),true);}
	//if (isset($datos[0])==0){$datos[0]='';}
	//if ($datos[0]==''){$sError=$ERR[''];}
	$idTercero=$opts[100];
	if ($sError==''){
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		$bHayDb=true;
		list($sError, $iAccion, $plab33id, $sDebugGuardar)=f2933_db_Guardar($valores, $objDB, $bDebug, $idTercero);
		$sDebug=$sDebug.$sDebugGuardar;
		}
	$objResponse=new xajaxResponse();
	if ($sError==''){
		list($sdetalle, $sDebugTabla)=f2933_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugTabla;
		$objResponse->assign('div_f2933detalle', 'innerHTML', $sdetalle);
		//if ($iAccion==2){
			//$objResponse->call('cargaridf2933('.$plab33id.')');
			//}else{
			$objResponse->call('limpiaf2933');
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
function f2933_Traer($aParametros){
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
		$plab33idciclo=numeros_validar($aParametros[1]);
		$plab33idmonitor=numeros_validar($aParametros[2]);
		$plab33idperiodo=numeros_validar($aParametros[3]);
		$plab33idcurso=numeros_validar($aParametros[4]);
		if (($plab33idciclo!='')&&($plab33idmonitor!='')&&($plab33idperiodo!='')&&($plab33idcurso!='')){$besta=true;}
		}else{
		$plab33id=$aParametros[103];
		if ((int)$plab33id!=0){$besta=true;}
		}
	if ($besta){
		$besta=false;
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		$bHayDb=true;
		$sSQLcondi='';
		if ($paso==1){
			$sSQLcondi=$sSQLcondi.'plab33idciclo='.$plab33idciclo.' AND plab33idmonitor='.$plab33idmonitor.' AND plab33idperiodo='.$plab33idperiodo.' AND plab33idcurso='.$plab33idcurso.'';
			}else{
			$sSQLcondi=$sSQLcondi.'plab33id='.$plab33id.'';
			}
		$sSQL='SELECT * FROM plab33emoncurso WHERE '.$sSQLcondi;
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$fila=$objDB->sf($tabla);
			$besta=true;
			}
		}
	$objResponse=new xajaxResponse();
	if ($besta){
		$iPiel=iDefinirPiel($APP, 1);
		$plab33idmonitor_id=(int)$fila['plab33idmonitor'];
		$plab33idmonitor_td=$APP->tipo_doc;
		$plab33idmonitor_doc='';
		$plab33idmonitor_nombre='';
		if ($plab33idmonitor_id!=0){
			list($plab33idmonitor_nombre, $plab33idmonitor_id, $plab33idmonitor_td, $plab33idmonitor_doc)=html_tercero($plab33idmonitor_td, $plab33idmonitor_doc, $plab33idmonitor_id, 0, $objDB);
			}
		$html_plab33idmonitor_llaves=html_DivTerceroV2('plab33idmonitor', $plab33idmonitor_td, $plab33idmonitor_doc, true, 2, 'Ingrese el documento');
		$objResponse->assign('plab33idmonitor', 'value', $plab33idmonitor_id);
		$objResponse->assign('div_plab33idmonitor_llaves', 'innerHTML', $html_plab33idmonitor_llaves);
		$objResponse->assign('div_plab33idmonitor', 'innerHTML', $plab33idmonitor_nombre);
		list($plab33idperiodo_nombre, $serror_det)=tabla_campoxid('exte02per_aca','exte02nombre','exte02id', $fila['plab33idperiodo'],'{'.$ETI['msg_sindato'].'}', $objDB);
		$html_plab33idperiodo=html_oculto('plab33idperiodo', $fila['plab33idperiodo'], $plab33idperiodo_nombre);
		$objResponse->assign('div_plab33idperiodo', 'innerHTML', $html_plab33idperiodo);
		list($plab33idcurso_nombre, $serror_det)=tabla_campoxid('unad40curso','CONCAT(unad40titulo, " - ", unad40nombre)','unad40id', $fila['plab33idcurso'],'{'.$ETI['msg_sindato'].'}', $objDB);
		$html_plab33idcurso=html_oculto('plab33idcurso', $fila['plab33idcurso'], $plab33idcurso_nombre);
		$objResponse->assign('div_plab33idcurso', 'innerHTML', $html_plab33idcurso);
		$plab33id_nombre='';
		$html_plab33id=html_oculto('plab33id', $fila['plab33id'], $plab33id_nombre);
		$objResponse->assign('div_plab33id', 'innerHTML', $html_plab33id);
		$objResponse->assign('plab33activo', 'value', $fila['plab33activo']);
		$objResponse->call("MensajeAlarmaV2('', 0)");
		$objResponse->call("verboton('belimina2933','block')");
		}else{
		if ($paso==1){
			$objResponse->assign('plab33idperiodo', 'value', $plab33idperiodo);
			$objResponse->assign('plab33idcurso', 'value', $plab33idcurso);
			}else{
			$objResponse->call('MensajeAlarmaV2("No se encontro el registro de referencia:'.$plab33id.'", 0)');
			}
		}
	if ($bHayDb){
		$objDB->CerrarConexion();
		}
	return $objResponse;
	}
function f2933_Eliminar($aParametros){
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
	if (isset($opts[100])==0){$opts[100]=0;}
	$idTercero=$opts[100];
	//if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	list($sError, $sDebugElimina)=f2933_db_Eliminar($aParametros, $objDB, $bDebug, $idTercero);
	$sDebug=$sDebug.$sDebugElimina;
	$objResponse=new xajaxResponse();
	if ($sError==''){
		list($sDetalle, $sDebugTabla)=f2933_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugTabla;
		$objResponse->assign('div_f2933detalle', 'innerHTML', $sDetalle);
		$objResponse->call('limpiaf2933');
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
function f2933_HtmlTabla($aParametros){
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
	list($sDetalle, $sDebugTabla)=f2933_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f2933detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f2933_PintarLlaves($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	//if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$iPiel=iDefinirPiel($APP, 1);
	$objCombos=new clsHtmlCombos();
	$plab33idmonitor=0;
	$plab33idmonitor_rs='';
	$html_plab33idmonitor_llaves=html_DivTerceroV2('plab33idmonitor', $APP->tipo_doc, '', false, 2, $ETI['ing_doc']);
	$html_plab33idperiodo=f2933_HTMLComboV2_plab33idperiodo($objDB, $objCombos, '');
	$html_plab33idcurso=f2933_HTMLComboV2_plab33idcurso($objDB, $objCombos, '', '');
	$html_plab33id='<input id="plab33id" name="plab33id" type="hidden" value=""/>';
	$objResponse=new xajaxResponse();
	$objResponse->assign('plab33idmonitor','value', $plab33idmonitor);
	$objResponse->assign('div_plab33idmonitor_llaves','innerHTML', $html_plab33idmonitor_llaves);
	$objResponse->assign('div_plab33idmonitor','innerHTML', $plab33idmonitor_rs);
	$objResponse->assign('div_plab33idperiodo','innerHTML', $html_plab33idperiodo);
	$objResponse->assign('div_plab33idcurso','innerHTML', $html_plab33idcurso);
	$objResponse->assign('div_plab33id','innerHTML', $html_plab33id);
	$objResponse->call('$("#plab33idperiodo").chosen()');
	$objResponse->call('$("#plab33idcurso").chosen()');
	return $objResponse;
	}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------
?>