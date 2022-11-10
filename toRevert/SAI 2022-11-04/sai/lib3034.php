<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2020 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.25.4 martes, 4 de agosto de 2020
--- 3034 saiu34palabraclave
*/
/** Archivo lib3034.php.
* Libreria 3034 saiu34palabraclave.
* @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
* @date martes, 4 de agosto de 2020
*/
function f3034_ExisteDato($datos){
	if(!is_array($datos)){$datos=json_decode(str_replace('\"','"',$datos),true);}
	$_SESSION['u_ultimominuto']=iminutoavance();
	$bHayLlave=true;
	$saiu34consec=numeros_validar($datos[1]);
	if ($saiu34consec==''){$bHayLlave=false;}
	if ($bHayLlave){
		require './app.php';
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		$sSQL='SELECT 1 FROM saiu34palabraclave WHERE saiu34consec='.$saiu34consec.'';
		$res=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($res)==0){$bHayLlave=false;}
		$objDB->CerrarConexion();
		if ($bHayLlave){
			$objResponse=new xajaxResponse();
			$objResponse->call('cambiapaginaV2');
			return $objResponse;
			}
		}
	}
function f3034_Busquedas($aParametros){
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3034='lg/lg_3034_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3034)){$mensajes_3034='lg/lg_3034_es.php';}
	require $mensajes_todas;
	require $mensajes_3034;
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$sCampo=$aParametros[1];
	$sTitulo=' {'.$sCampo.'}';
	if (isset($aParametros[2])==0){$aParametros[2]=0;}
	if (isset($aParametros[3])==0){$aParametros[3]=0;}
	$sParams='';
	$sTabla='';
	$sJavaBusqueda='';
	$aParametrosB=array();
	$aParametrosB[101]=1;
	$aParametrosB[102]=20;
	switch($sCampo){
		}
	$sTitulo='<h2>'.$ETI['titulo_3034'].' - '.$sTitulo.'</h2>';
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97titulo', 'innerHTML', $sTitulo);
	$objResponse->assign('div_97params', 'innerHTML', $sParams);
	$objResponse->assign('div_97tabla', 'innerHTML', $sTabla);
	$objResponse->setFunction('paginarbusqueda','',$sJavaBusqueda);
	$objResponse->call('expandesector(97)');
	return $objResponse;
	}
function f3034_HtmlBusqueda($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$sError='';
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$sDetalle='';
	switch($aParametros[100]){
		}
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97tabla', 'innerHTML', $sDetalle);
	return $objResponse;
	}
function f3034_TablaDetalleV2($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3034='lg/lg_3034_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3034)){$mensajes_3034='lg/lg_3034_es.php';}
	require $mensajes_todas;
	require $mensajes_3034;
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	if (isset($aParametros[100])==0){$aParametros[100]=$_SESSION['unad_id_tercero'];}
	if (isset($aParametros[101])==0){$aParametros[101]=1;}
	if (isset($aParametros[102])==0){$aParametros[102]=20;}
	if (isset($aParametros[103])==0){$aParametros[103]='';}
	//if (isset($aParametros[104])==0){$aParametros[104]='';}
	//$aParametros[103]=numeros_validar($aParametros[103]);
	$idTercero=$aParametros[100];
	$sDebug='';
	$pagina=$aParametros[101];
	$lineastabla=$aParametros[102];
	$bAbierta=true;
	//$sSQL='SELECT Campo FROM Tabla WHERE Id='.$sValorId;
	//$tabla=$objDB->ejecutasql($sSQL);
	//if ($objDB->nf($tabla)>0){
		//$fila=$objDB->sf($tabla);
		//if ($fila['Campo']!='S'){$bAbierta=true;}
		//}
	$sLeyenda='';
	if ($sLeyenda!=''){
		$sLeyenda='<div class="salto1px"></div>
<div class="GrupoCamposAyuda">
'.$sLeyenda.'
<div class="salto1px"></div>
</div>';
		return array($sLeyenda.'<input id="paginaf3034" name="paginaf3034" type="hidden" value="'.$pagina.'"/><input id="lppf3034" name="lppf3034" type="hidden" value="'.$lineastabla.'"/>', $sDebug);
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
	$sSQLadd='1';
	$sSQLadd1='';
	//if ((int)$aParametros[103]!=-1){$sSQLadd=$sSQLadd.' AND TB.campo='.$aParametros[103];}
	//if ($aParametros[103]!=''){$sSQLadd=$sSQLadd.' AND TB.campo2 LIKE "%'.$aParametros[103].'%"';}
	if ($aParametros[103]!=''){
		$sBase=trim(strtoupper($aParametros[103]));
		$aNoms=explode(' ', $sBase);
		for ($k=1;$k<=count($aNoms);$k++){
			$sCadena=$aNoms[$k-1];
			if ($sCadena!=''){
				$sSQLadd='T6.unad11razonsocial LIKE "%'.$sCadena.'%" AND '.$sSQLadd;
				//$sSQLadd1=$sSQLadd1.'T1.unad11razonsocial LIKE "%'.$sCadena.'%" AND ';
				}
			}
		}
	$sTitulos='Consec, Id, Palabra, Activo';
	$registros=0;
	$bGigante=false; //En caso de que la tabla sea muy grande pasarlo a true
	$sLimite='';
	if ($bGigante){
		$sSQL='SELECT COUNT(1) AS Total 
FROM saiu34palabraclave AS TB 
WHERE '.$sSQLadd1.'  '.$sSQLadd.'';
		$tabladetalle=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabladetalle)>0){
			$fila=$objDB->sf($tabladetalle);
			$registros=$fila['Total'];
			}
		if ((($registros-1)/$lineastabla)<($pagina-1)){$pagina=(int)(($registros-1)/$lineastabla)+1;}
		if ($registros>$lineastabla){
			$rbase=($pagina-1)*$lineastabla;
			$sLimite=' LIMIT '.$rbase.', '.$lineastabla;
			}
		}
	$sSQL='SELECT TB.saiu34consec, TB.saiu34id, TB.saiu34palabra, TB.saiu34activo 
FROM saiu34palabraclave AS TB 
WHERE '.$sSQLadd1.'  '.$sSQLadd.'
ORDER BY TB.saiu34activo, TB.saiu34palabra';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_3034" name="consulta_3034" type="hidden" value="'.$sSQLlista.'"/>
<input id="titulos_3034" name="titulos_3034" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objDB->ejecutasql($sSQL.$sLimite);
	if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 3034: '.$sSQL.$sLimite.'<br>';}
	if ($tabladetalle==false){
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		//$sLeyenda=$sSQL;
		}else{
		if (!$bGigante){
			$registros=$objDB->nf($tabladetalle);
			if ($registros==0){
				//return array(utf8_encode($sErrConsulta.'<input id="paginaf3034" name="paginaf3034" type="hidden" value="'.$pagina.'"/><input id="lppf3034" name="lppf3034" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
				}
			if ((($registros-1)/$lineastabla)<($pagina-1)){$pagina=(int)(($registros-1)/$lineastabla)+1;}
			if ($registros>$lineastabla){
				$rbase=($pagina-1)*$lineastabla;
				$sLimite=' LIMIT '.$rbase.', '.$lineastabla;
				$tabladetalle=$objDB->ejecutasql($sSQL.$sLimite);
				}
			}
		}
	$res=$sErrConsulta.$sLeyenda.'<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">
<thead class="fondoazul"><tr>
<td><b>'.$ETI['saiu34consec'].'</b></td>
<td><b>'.$ETI['saiu34activo'].'</b></td>
<td><b>'.$ETI['saiu34palabra'].'</b></td>
<td align="right">
'.html_paginador('paginaf3034', $registros, $lineastabla, $pagina, 'paginarf3034()').'
'.html_lpp('lppf3034', $lineastabla, 'paginarf3034()').'
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
		$et_saiu34activo=$sPrefijo.$ETI['si'].$sSufijo;
		if ($filadet['saiu34activo']==0){$et_saiu34activo=$sPrefijo.$ETI['no'].$sSufijo;}
		if ($bAbierta){
			$sLink='<a href="javascript:cargaridf3034('.$filadet['saiu34id'].')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$res=$res.'<tr'.$sClass.'>
<td>'.$sPrefijo.$filadet['saiu34consec'].$sSufijo.'</td>
<td>'.$sPrefijo.$et_saiu34activo.$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['saiu34palabra']).$sSufijo.'</td>
<td>'.$sLink.'</td>
</tr>';
		}
	$res=$res.'</table>';
	$objDB->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
	}
function f3034_HtmlTabla($aParametros){
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
	list($sDetalle, $sDebugTabla)=f3034_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f3034detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f3034_db_CargarPadre($DATA, $objDB, $bDebug=false){
	$sError='';
	$iTipoError=0;
	$sDebug='';
	require './app.php';
	if ($DATA['paso']==1){
		$sSQLcondi='saiu34consec='.$DATA['saiu34consec'].'';
		}else{
		$sSQLcondi='saiu34id='.$DATA['saiu34id'].'';
		}
	$sSQL='SELECT * FROM saiu34palabraclave WHERE '.$sSQLcondi;
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$DATA['saiu34consec']=$fila['saiu34consec'];
		$DATA['saiu34id']=$fila['saiu34id'];
		$DATA['saiu34palabra']=$fila['saiu34palabra'];
		$DATA['saiu34activo']=$fila['saiu34activo'];
		$bcargo=true;
		$DATA['paso']=2;
		$DATA['boculta3034']=0;
		$bLimpiaHijos=true;
		}else{
		$DATA['paso']=0;
		}
	return array($DATA, $sError, $iTipoError, $sDebug);
	}
function f3034_db_GuardarV2($DATA, $objDB, $bDebug=false){
	$iCodModulo=3034;
	$bAudita[2]=true;
	$bAudita[3]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3034='lg/lg_3034_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3034)){$mensajes_3034='lg/lg_3034_es.php';}
	require $mensajes_todas;
	require $mensajes_3034;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	// -- Se inicia validando todas las posibles entradas de usuario.
	/*
	if (isset($DATA['saiu34consec'])==0){$DATA['saiu34consec']='';}
	if (isset($DATA['saiu34id'])==0){$DATA['saiu34id']='';}
	if (isset($DATA['saiu34palabra'])==0){$DATA['saiu34palabra']='';}
	if (isset($DATA['saiu34activo'])==0){$DATA['saiu34activo']='';}
	*/
	$DATA['saiu34consec']=numeros_validar($DATA['saiu34consec']);
	$DATA['saiu34palabra']=htmlspecialchars(trim($DATA['saiu34palabra']));
	$DATA['saiu34activo']=numeros_validar($DATA['saiu34activo']);
	// -- Se inicializan las variables que puedan pasar vacias {Especialmente números}.
	//if ($DATA['saiu34activo']==''){$DATA['saiu34activo']=0;}
	// -- Seccion para validar los posibles causales de error.
	$sSepara=', ';
	if (true){
		if ($DATA['saiu34activo']==''){$sError=$ERR['saiu34activo'].$sSepara.$sError;}
		if ($DATA['saiu34palabra']==''){$sError=$ERR['saiu34palabra'].$sSepara.$sError;}
		//Fin de las valiaciones NO LLAVE.
		}
	//Valiaciones de campos obligatorios en todo guardar.
	// -- Se verifican los valores de campos de otras tablas.
	$bQuitarCodigo=false;
	$sCampoCodigo='';
	if ($sError==''){
		if ($DATA['paso']==10){
			if ($DATA['saiu34consec']==''){
				$DATA['saiu34consec']=tabla_consecutivo('saiu34palabraclave', 'saiu34consec', '', $objDB);
				if ($DATA['saiu34consec']==-1){$sError=$objDB->serror;}
				$bQuitarCodigo=true;
				$sCampoCodigo='saiu34consec';
				}else{
				if (!seg_revisa_permiso($iCodModulo, 8, $objDB)){
					$sError=$ERR['8'];
					$DATA['saiu34consec']='';
					}
				}
			if ($sError==''){
				$sSQL='SELECT 1 FROM saiu34palabraclave WHERE saiu34consec='.$DATA['saiu34consec'].'';
				$result=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($result)!=0){
					$sError=$ERR['existe'];
					}else{
					if (!seg_revisa_permiso($iCodModulo, 2, $objDB)){$sError=$ERR['2'];}
					}
				}
			}else{
			if (!seg_revisa_permiso($iCodModulo, 3, $objDB)){$sError=$ERR['3'];}
			}
		}
	if ($sError==''){
		if ($DATA['paso']==10){
			//Preparar el Id, Si no lo hay se quita la comprobación.
			$DATA['saiu34id']=tabla_consecutivo('saiu34palabraclave','saiu34id', '', $objDB);
			if ($DATA['saiu34id']==-1){$sError=$objDB->serror;}
			}
		}
	if ($sError==''){
		$bpasa=false;
		if ($DATA['paso']==10){
			$sCampos3034='saiu34consec, saiu34id, saiu34palabra, saiu34activo';
			$sValores3034=''.$DATA['saiu34consec'].', '.$DATA['saiu34id'].', "'.$DATA['saiu34palabra'].'", '.$DATA['saiu34activo'].'';
			if ($APP->utf8==1){
				$sSQL='INSERT INTO saiu34palabraclave ('.$sCampos3034.') VALUES ('.utf8_encode($sValores3034).');';
				$sdetalle=$sCampos3034.'['.utf8_encode($sValores3034).']';
				}else{
				$sSQL='INSERT INTO saiu34palabraclave ('.$sCampos3034.') VALUES ('.$sValores3034.');';
				$sdetalle=$sCampos3034.'['.$sValores3034.']';
				}
			$idAccion=2;
			$bpasa=true;
			}else{
			$scampo[1]='saiu34palabra';
			$scampo[2]='saiu34activo';
			$sdato[1]=$DATA['saiu34palabra'];
			$sdato[2]=$DATA['saiu34activo'];
			$numcmod=2;
			$sWhere='saiu34id='.$DATA['saiu34id'].'';
			$sSQL='SELECT * FROM saiu34palabraclave WHERE '.$sWhere;
			$sdatos='';
			$bPrimera=true;
			$result=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($result)>0){
				$filabase=$objDB->sf($result);
				if ($bDebug&&$bPrimera){
					for ($k=1;$k<=$numcmod;$k++){
						if (isset($filabase[$scampo[$k]])==0){
							$sDebug=$sDebug.fecha_microtiempo().' FALLA CODIGO: Falta el campo '.$k.' '.$scampo[$k].'<br>';
							}
						}
					$bPrimera=false;
					}
				$bsepara=false;
				for ($k=1;$k<=$numcmod;$k++){
					if ($filabase[$scampo[$k]]!=$sdato[$k]){
						if ($sdatos!=''){$sdatos=$sdatos.', ';}
						$sdatos=$sdatos.$scampo[$k].'="'.$sdato[$k].'"';
						$bpasa=true;
						}
					}
				}
			if ($bpasa){
				if ($APP->utf8==1){
					$sdetalle=utf8_encode($sdatos).'['.$sWhere.']';
					$sSQL='UPDATE saiu34palabraclave SET '.utf8_encode($sdatos).' WHERE '.$sWhere.';';
					}else{
					$sdetalle=$sdatos.'['.$sWhere.']';
					$sSQL='UPDATE saiu34palabraclave SET '.$sdatos.' WHERE '.$sWhere.';';
					}
				$idAccion=3;
				}
			}
		if ($bpasa){
			if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Guardar 3034 '.$sSQL.'<br>';}
			$result=$objDB->ejecutasql($sSQL);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' [3034] ..<!-- '.$sSQL.' -->';
				if ($idAccion==2){
					$DATA['saiu34id']='';
					$bQuitarCodigo=true;
					$DATA['paso']=0;
					}else{
					$DATA['paso']=2;
					}
				}else{
				if ($bAudita[$idAccion]){seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], $idAccion, $DATA['saiu34id'], $sdetalle, $objDB);}
				$DATA['paso']=2;
				}
			}else{
			$DATA['paso']=2;
			}
		}else{
		if ($DATA['paso']==10){
			$DATA['paso']=0;
			}else{
			$DATA['paso']=2;
			}
		if ($bQuitarCodigo){
			if ($sCampoCodigo!=''){$DATA[$sCampoCodigo]='';}
			}
		}
	//if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' InfoDepura<br>';}
	return array($DATA, $sError, $iTipoError, $sDebug);
	}
function f3034_db_Eliminar($saiu34id, $objDB, $bDebug=false){
	$iCodModulo=3034;
	$bAudita[4]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3034='lg/lg_3034_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3034)){$mensajes_3034='lg/lg_3034_es.php';}
	require $mensajes_todas;
	require $mensajes_3034;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	$saiu34id=numeros_validar($saiu34id);
	// Traer los datos para hacer las validaciones.
	if ($sError==''){
		$sSQL='SELECT * FROM saiu34palabraclave WHERE saiu34id='.$saiu34id.'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$filabase=$objDB->sf($tabla);
			}else{
			$sError='No se encuentra el registro solicitado {Ref: '.$saiu34id.'}';
			}
		}
	if ($sError==''){
		if (!seg_revisa_permiso($iCodModulo, 4, $objDB)){
			$sError=$ERR['4'];
			}
		}
	if ($sError==''){
		$sSQL='SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=3034';
		$tablaor=$objDB->ejecutasql($sSQL);
		while ($filaor=$objDB->sf($tablaor)){
			$sSQL='SELECT '.$filaor['campoid'].' FROM '.$filaor['tabla'].' WHERE '.$filaor['camporev'].'='.$_REQUEST['saiu34id'].' LIMIT 0, 1';
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
		$sWhere='saiu34id='.$saiu34id.'';
		//$sWhere='saiu34consec='.$filabase['saiu34consec'].'';
		$sSQL='DELETE FROM saiu34palabraclave WHERE '.$sWhere.';';
		$result=$objDB->ejecutasql($sSQL);
		if ($result==false){
			$sError=$ERR['falla_eliminar'].' .. <!-- '.$sSQL.' -->';
			}else{
			if ($bAudita[4]){seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $saiu34id, $sWhere, $objDB);}
			}
		}
	return array($sError, $iTipoError, $sDebug);
	}
function f3034_TituloBusqueda(){
	return 'Busqueda de Palabras clave';
	}
function f3034_ParametrosBusqueda(){
	$sParams='<label class="Label90">Nombre</label><label><input id="b3034nombre" name="b3034nombre" type="text" value="" onchange="paginarbusqueda()" /></label>';
	return $sParams;
	}
function f3034_JavaScriptBusqueda($iModuloBusca){
	$sRes='var sCampo=window.document.frmedita.scampobusca.value;
var params=new Array();
params[100]=sCampo;
params[101]=window.document.frmedita.paginabusqueda.value;
params[102]=window.document.frmedita.lppfbusqueda.value;
params[103]=window.document.frmedita.b3034nombre.value;
xajax_f'.$iModuloBusca.'_HtmlBusqueda(params);';
	return $sRes;
	}
function f3034_TablaDetalleBusquedas($aParametros, $objDB){
	$res='';
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3034='lg/lg_3034_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3034)){$mensajes_3034='lg/lg_3034_es.php';}
	require $mensajes_todas;
	require $mensajes_3034;
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	if (isset($aParametros[100])==0){$aParametros[100]=$_SESSION['unad_id_tercero'];}
	if (isset($aParametros[101])==0){$aParametros[101]=1;}
	if (isset($aParametros[102])==0){$aParametros[102]=20;}
	if (isset($aParametros[103])==0){$aParametros[103]='';}
	if (isset($aParametros[104])==0){$aParametros[104]='';}
	//$aParametros[103]=numeros_validar($aParametros[103]);
	$idTercero=$aParametros[100];
	$pagina=$aParametros[101];
	$lineastabla=$aParametros[102];
	$bAbierta=true;
	//$sSQL='SELECT Campo FROM Tabla WHERE Id='.$sValorId;
	//$tabla=$objDB->ejecutasql($sSQL);
	//if ($objDB->nf($tabla)>0){
		//$fila=$objDB->sf($tabla);
		//if ($fila['Campo']!='S'){$bAbierta=true;}
		//}
	$sLeyenda='';
	if ($sLeyenda!=''){
		$sLeyenda='<div class="salto1px"></div>
<div class="GrupoCamposAyuda">
'.$sLeyenda.'
<div class="salto1px"></div>
</div>';
		return array($sLeyenda.'<input id="paginaf3034" name="paginaf3034" type="hidden" value="'.$pagina.'"/><input id="lppf3034" name="lppf3034" type="hidden" value="'.$lineastabla.'"/>', $sDebug);
		die();
		}
	$sSQLadd='1';
	$sSQLadd1='';
	//if ($aParametros[103]!=''){$sSQLadd1=$sSQLadd1.'TB.campo2 LIKE "%'.$aParametros[103].'%" AND ';}
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
	$sTitulos='Consec, Id, Palabra, Activo';
	$sSQL='SELECT TB.saiu34consec, TB.saiu34id, TB.saiu34palabra, TB.saiu34activo 
FROM saiu34palabraclave AS TB 
WHERE '.$sSQLadd1.'  '.$sSQLadd.'
ORDER BY TB.saiu34consec';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_busqueda" name="consulta_busqueda" type="hidden" value="'.$sSQLlista.'"/>
<input id="titulos_busqueda" name="titulos_busqueda" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objDB->ejecutasql($sSQL);
	if ($tabladetalle==false){
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		//$sLeyenda=$sSQL;
		}else{
		$registros=$objDB->nf($tabladetalle);
		if ($registros==0){
			//return array(utf8_encode($sErrConsulta.'<input id="paginaf3034" name="paginaf3034" type="hidden" value="'.$pagina.'"/><input id="lppf3034" name="lppf3034" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
<td><b>'.$ETI['saiu34consec'].'</b></td>
<td><b>'.$ETI['saiu34palabra'].'</b></td>
<td><b>'.$ETI['saiu34activo'].'</b></td>
<td align="right">
'.html_paginador('paginabusqueda', $registros, $lineastabla, $pagina, 'paginarbusqueda()').'
'.html_lpp('lppfbusqueda', $lineastabla, 'paginarbusqueda()').'
</td>
</tr></thead>';
	$tlinea=1;
	while($filadet=$objDB->sf($tabladetalle)){
		$sPrefijo='<a href="javascript:Devuelve(\''.$filadet['saiu34id'].'\');">';
		$sSufijo='</a>';
		$tlinea++;
		$et_saiu34activo=$sPrefijo.$ETI['no'].$sSufijo;
		if ($filadet['saiu34activo']=='S'){$et_saiu34activo=$sPrefijo.$ETI['si'].$sSufijo;}
		$res=$res.'<tr onmouseover="cambia_color_over(this);" onmouseout="cambia_color_out(this);">
<td>'.$sPrefijo.$filadet['saiu34consec'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['saiu34palabra']).$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu34activo'].$sSufijo.'</td>
<td></td>
</tr>';
		}
	$res=$res.'</table>';
	$objDB->liberar($tabladetalle);
	return utf8_encode($res);
	}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------
?>