<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2020 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.25.4 domingo, 19 de julio de 2020
--- 3027 saiu27chats
*/
/** Archivo lib3027.php.
* Libreria 3027 saiu27chats.
* @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
* @date domingo, 19 de julio de 2020
*/
function f3027_ExisteDato($datos){
	if(!is_array($datos)){$datos=json_decode(str_replace('\"','"',$datos),true);}
	$_SESSION['u_ultimominuto']=iminutoavance();
	$bHayLlave=true;
	$saiu27consec=numeros_validar($datos[1]);
	if ($saiu27consec==''){$bHayLlave=false;}
	if ($bHayLlave){
		require './app.php';
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		$sSQL='SELECT 1 FROM saiu27chats WHERE saiu27consec='.$saiu27consec.'';
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
function f3027_Busquedas($aParametros){
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3027='lg/lg_3027_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3027)){$mensajes_3027='lg/lg_3027_es.php';}
	require $mensajes_todas;
	require $mensajes_3027;
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
	$sTitulo='<h2>'.$ETI['titulo_3027'].' - '.$sTitulo.'</h2>';
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97titulo', 'innerHTML', $sTitulo);
	$objResponse->assign('div_97params', 'innerHTML', $sParams);
	$objResponse->assign('div_97tabla', 'innerHTML', $sTabla);
	$objResponse->setFunction('paginarbusqueda','',$sJavaBusqueda);
	$objResponse->call('expandesector(97)');
	return $objResponse;
	}
function f3027_HtmlBusqueda($aParametros){
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
function f3027_TablaDetalleV2($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3027='lg/lg_3027_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3027)){$mensajes_3027='lg/lg_3027_es.php';}
	require $mensajes_todas;
	require $mensajes_3027;
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	if (isset($aParametros[100])==0){$aParametros[100]=$_SESSION['unad_id_tercero'];}
	if (isset($aParametros[101])==0){$aParametros[101]=1;}
	if (isset($aParametros[102])==0){$aParametros[102]=20;}
	//if (isset($aParametros[103])==0){$aParametros[103]='';}
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
		return array($sLeyenda.'<input id="paginaf3027" name="paginaf3027" type="hidden" value="'.$pagina.'"/><input id="lppf3027" name="lppf3027" type="hidden" value="'.$lineastabla.'"/>', $sDebug);
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
	$sTitulos='Consec, Id, Activo, Predet, Orden, Nombre';
	$registros=0;
	$bGigante=false; //En caso de que la tabla sea muy grande pasarlo a true
	$sLimite='';
	if ($bGigante){
		$sSQL='SELECT COUNT(1) AS Total 
FROM saiu27chats AS TB 
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
	$sSQL='SELECT TB.saiu27consec, TB.saiu27id, TB.saiu27activo, TB.saiu27predet, TB.saiu27orden, TB.saiu27nombre 
FROM saiu27chats AS TB 
WHERE '.$sSQLadd1.'  '.$sSQLadd.'
ORDER BY TB.saiu27consec';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_3027" name="consulta_3027" type="hidden" value="'.$sSQLlista.'"/>
<input id="titulos_3027" name="titulos_3027" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objDB->ejecutasql($sSQL.$sLimite);
	if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 3027: '.$sSQL.$sLimite.'<br>';}
	if ($tabladetalle==false){
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		//$sLeyenda=$sSQL;
		}else{
		if (!$bGigante){
			$registros=$objDB->nf($tabladetalle);
			if ($registros==0){
				//return array(utf8_encode($sErrConsulta.'<input id="paginaf3027" name="paginaf3027" type="hidden" value="'.$pagina.'"/><input id="lppf3027" name="lppf3027" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
<td><b>'.$ETI['saiu27consec'].'</b></td>
<td><b>'.$ETI['saiu27activo'].'</b></td>
<td><b>'.$ETI['saiu27predet'].'</b></td>
<td><b>'.$ETI['saiu27orden'].'</b></td>
<td><b>'.$ETI['saiu27nombre'].'</b></td>
<td align="right">
'.html_paginador('paginaf3027', $registros, $lineastabla, $pagina, 'paginarf3027()').'
'.html_lpp('lppf3027', $lineastabla, 'paginarf3027()').'
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
		$et_saiu27activo=$sPrefijo.$ETI['no'].$sSufijo;
		if ($filadet['saiu27activo']=='S'){$et_saiu27activo=$sPrefijo.$ETI['si'].$sSufijo;}
		if ($bAbierta){
			$sLink='<a href="javascript:cargaridf3027('.$filadet['saiu27id'].')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$res=$res.'<tr'.$sClass.'>
<td>'.$sPrefijo.$filadet['saiu27consec'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu27activo'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu27predet'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu27orden'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['saiu27nombre']).$sSufijo.'</td>
<td>'.$sLink.'</td>
</tr>';
		}
	$res=$res.'</table>';
	$objDB->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
	}
function f3027_HtmlTabla($aParametros){
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
	list($sDetalle, $sDebugTabla)=f3027_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f3027detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f3027_db_CargarPadre($DATA, $objDB, $bDebug=false){
	$sError='';
	$iTipoError=0;
	$sDebug='';
	require './app.php';
	if ($DATA['paso']==1){
		$sSQLcondi='saiu27consec='.$DATA['saiu27consec'].'';
		}else{
		$sSQLcondi='saiu27id='.$DATA['saiu27id'].'';
		}
	$sSQL='SELECT * FROM saiu27chats WHERE '.$sSQLcondi;
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$DATA['saiu27consec']=$fila['saiu27consec'];
		$DATA['saiu27id']=$fila['saiu27id'];
		$DATA['saiu27activo']=$fila['saiu27activo'];
		$DATA['saiu27predet']=$fila['saiu27predet'];
		$DATA['saiu27orden']=$fila['saiu27orden'];
		$DATA['saiu27nombre']=$fila['saiu27nombre'];
		$bcargo=true;
		$DATA['paso']=2;
		$DATA['boculta3027']=0;
		$bLimpiaHijos=true;
		}else{
		$DATA['paso']=0;
		}
	return array($DATA, $sError, $iTipoError, $sDebug);
	}
function f3027_db_GuardarV2($DATA, $objDB, $bDebug=false){
	$iCodModulo=3027;
	$bAudita[2]=true;
	$bAudita[3]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3027='lg/lg_3027_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3027)){$mensajes_3027='lg/lg_3027_es.php';}
	require $mensajes_todas;
	require $mensajes_3027;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	// -- Se inicia validando todas las posibles entradas de usuario.
	/*
	if (isset($DATA['saiu27consec'])==0){$DATA['saiu27consec']='';}
	if (isset($DATA['saiu27id'])==0){$DATA['saiu27id']='';}
	if (isset($DATA['saiu27activo'])==0){$DATA['saiu27activo']='';}
	if (isset($DATA['saiu27predet'])==0){$DATA['saiu27predet']='';}
	if (isset($DATA['saiu27orden'])==0){$DATA['saiu27orden']='';}
	if (isset($DATA['saiu27nombre'])==0){$DATA['saiu27nombre']='';}
	*/
	$DATA['saiu27consec']=numeros_validar($DATA['saiu27consec']);
	$DATA['saiu27activo']=numeros_validar($DATA['saiu27activo']);
	$DATA['saiu27predet']=numeros_validar($DATA['saiu27predet']);
	$DATA['saiu27orden']=numeros_validar($DATA['saiu27orden']);
	$DATA['saiu27nombre']=htmlspecialchars(trim($DATA['saiu27nombre']));
	// -- Se inicializan las variables que puedan pasar vacias {Especialmente números}.
	//if ($DATA['saiu27activo']==''){$DATA['saiu27activo']=0;}
	//if ($DATA['saiu27predet']==''){$DATA['saiu27predet']=0;}
	//if ($DATA['saiu27orden']==''){$DATA['saiu27orden']=0;}
	// -- Seccion para validar los posibles causales de error.
	$sSepara=', ';
	if (true){
		if ($DATA['saiu27nombre']==''){$sError=$ERR['saiu27nombre'].$sSepara.$sError;}
		//if ($DATA['saiu27orden']==''){$sError=$ERR['saiu27orden'].$sSepara.$sError;}
		if ($DATA['saiu27predet']==''){$sError=$ERR['saiu27predet'].$sSepara.$sError;}
		if ($DATA['saiu27activo']==''){$sError=$ERR['saiu27activo'].$sSepara.$sError;}
		//Fin de las valiaciones NO LLAVE.
		}
	//Valiaciones de campos obligatorios en todo guardar.
	// -- Se verifican los valores de campos de otras tablas.
	$bQuitarCodigo=false;
	$sCampoCodigo='';
	if ($sError==''){
		if ($DATA['paso']==10){
			if ($DATA['saiu27consec']==''){
				$DATA['saiu27consec']=tabla_consecutivo('saiu27chats', 'saiu27consec', '', $objDB);
				if ($DATA['saiu27consec']==-1){$sError=$objDB->serror;}
				$bQuitarCodigo=true;
				$sCampoCodigo='saiu27consec';
				}else{
				if (!seg_revisa_permiso($iCodModulo, 8, $objDB)){
					$sError=$ERR['8'];
					$DATA['saiu27consec']='';
					}
				}
			if ($sError==''){
				$sSQL='SELECT 1 FROM saiu27chats WHERE saiu27consec='.$DATA['saiu27consec'].'';
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
			$DATA['saiu27id']=tabla_consecutivo('saiu27chats','saiu27id', '', $objDB);
			if ($DATA['saiu27id']==-1){$sError=$objDB->serror;}
			}
		}
	if ($sError==''){
		if ($DATA['saiu27orden']==''){$DATA['saiu27orden']=$DATA['saiu27consec'];}
		$bpasa=false;
		if ($DATA['paso']==10){
			$sCampos3027='saiu27consec, saiu27id, saiu27activo, saiu27predet, saiu27orden, 
saiu27nombre';
			$sValores3027=''.$DATA['saiu27consec'].', '.$DATA['saiu27id'].', '.$DATA['saiu27activo'].', '.$DATA['saiu27predet'].', '.$DATA['saiu27orden'].', 
"'.$DATA['saiu27nombre'].'"';
			if ($APP->utf8==1){
				$sSQL='INSERT INTO saiu27chats ('.$sCampos3027.') VALUES ('.utf8_encode($sValores3027).');';
				$sdetalle=$sCampos3027.'['.utf8_encode($sValores3027).']';
				}else{
				$sSQL='INSERT INTO saiu27chats ('.$sCampos3027.') VALUES ('.$sValores3027.');';
				$sdetalle=$sCampos3027.'['.$sValores3027.']';
				}
			$idAccion=2;
			$bpasa=true;
			}else{
			$scampo[1]='saiu27activo';
			$scampo[2]='saiu27predet';
			$scampo[3]='saiu27orden';
			$scampo[4]='saiu27nombre';
			$sdato[1]=$DATA['saiu27activo'];
			$sdato[2]=$DATA['saiu27predet'];
			$sdato[3]=$DATA['saiu27orden'];
			$sdato[4]=$DATA['saiu27nombre'];
			$numcmod=4;
			$sWhere='saiu27id='.$DATA['saiu27id'].'';
			$sSQL='SELECT * FROM saiu27chats WHERE '.$sWhere;
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
					$sSQL='UPDATE saiu27chats SET '.utf8_encode($sdatos).' WHERE '.$sWhere.';';
					}else{
					$sdetalle=$sdatos.'['.$sWhere.']';
					$sSQL='UPDATE saiu27chats SET '.$sdatos.' WHERE '.$sWhere.';';
					}
				$idAccion=3;
				}
			}
		if ($bpasa){
			if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Guardar 3027 '.$sSQL.'<br>';}
			$result=$objDB->ejecutasql($sSQL);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' [3027] ..<!-- '.$sSQL.' -->';
				if ($idAccion==2){
					$DATA['saiu27id']='';
					$bQuitarCodigo=true;
					$DATA['paso']=0;
					}else{
					$DATA['paso']=2;
					}
				}else{
				if ($bAudita[$idAccion]){seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], $idAccion, $DATA['saiu27id'], $sdetalle, $objDB);}
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
function f3027_db_Eliminar($saiu27id, $objDB, $bDebug=false){
	$iCodModulo=3027;
	$bAudita[4]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3027='lg/lg_3027_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3027)){$mensajes_3027='lg/lg_3027_es.php';}
	require $mensajes_todas;
	require $mensajes_3027;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	$saiu27id=numeros_validar($saiu27id);
	// Traer los datos para hacer las validaciones.
	if ($sError==''){
		$sSQL='SELECT * FROM saiu27chats WHERE saiu27id='.$saiu27id.'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$filabase=$objDB->sf($tabla);
			}else{
			$sError='No se encuentra el registro solicitado {Ref: '.$saiu27id.'}';
			}
		}
	if ($sError==''){
		if (!seg_revisa_permiso($iCodModulo, 4, $objDB)){
			$sError=$ERR['4'];
			}
		}
	if ($sError==''){
		$sSQL='SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=3027';
		$tablaor=$objDB->ejecutasql($sSQL);
		while ($filaor=$objDB->sf($tablaor)){
			$sSQL='SELECT '.$filaor['campoid'].' FROM '.$filaor['tabla'].' WHERE '.$filaor['camporev'].'='.$_REQUEST['saiu27id'].' LIMIT 0, 1';
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
		$sWhere='saiu27id='.$saiu27id.'';
		//$sWhere='saiu27consec='.$filabase['saiu27consec'].'';
		$sSQL='DELETE FROM saiu27chats WHERE '.$sWhere.';';
		$result=$objDB->ejecutasql($sSQL);
		if ($result==false){
			$sError=$ERR['falla_eliminar'].' .. <!-- '.$sSQL.' -->';
			}else{
			if ($bAudita[4]){seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $saiu27id, $sWhere, $objDB);}
			}
		}
	return array($sError, $iTipoError, $sDebug);
	}
function f3027_TituloBusqueda(){
	return 'Busqueda de Chats';
	}
function f3027_ParametrosBusqueda(){
	$sParams='<label class="Label90">Nombre</label><label><input id="b3027nombre" name="b3027nombre" type="text" value="" onchange="paginarbusqueda()" /></label>';
	return $sParams;
	}
function f3027_JavaScriptBusqueda($iModuloBusca){
	$sRes='var sCampo=window.document.frmedita.scampobusca.value;
var params=new Array();
params[100]=sCampo;
params[101]=window.document.frmedita.paginabusqueda.value;
params[102]=window.document.frmedita.lppfbusqueda.value;
params[103]=window.document.frmedita.b3027nombre.value;
xajax_f'.$iModuloBusca.'_HtmlBusqueda(params);';
	return $sRes;
	}
function f3027_TablaDetalleBusquedas($aParametros, $objDB){
	$res='';
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3027='lg/lg_3027_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3027)){$mensajes_3027='lg/lg_3027_es.php';}
	require $mensajes_todas;
	require $mensajes_3027;
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
		return array($sLeyenda.'<input id="paginaf3027" name="paginaf3027" type="hidden" value="'.$pagina.'"/><input id="lppf3027" name="lppf3027" type="hidden" value="'.$lineastabla.'"/>', $sDebug);
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
	$sTitulos='Consec, Id, Activo, Predet, Orden, Nombre';
	$sSQL='SELECT TB.saiu27consec, TB.saiu27id, TB.saiu27activo, TB.saiu27predet, TB.saiu27orden, TB.saiu27nombre 
FROM saiu27chats AS TB 
WHERE '.$sSQLadd1.'  '.$sSQLadd.'
ORDER BY TB.saiu27consec';
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
			//return array(utf8_encode($sErrConsulta.'<input id="paginaf3027" name="paginaf3027" type="hidden" value="'.$pagina.'"/><input id="lppf3027" name="lppf3027" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
<td><b>'.$ETI['saiu27consec'].'</b></td>
<td><b>'.$ETI['saiu27activo'].'</b></td>
<td><b>'.$ETI['saiu27predet'].'</b></td>
<td><b>'.$ETI['saiu27orden'].'</b></td>
<td><b>'.$ETI['saiu27nombre'].'</b></td>
<td align="right">
'.html_paginador('paginabusqueda', $registros, $lineastabla, $pagina, 'paginarbusqueda()').'
'.html_lpp('lppfbusqueda', $lineastabla, 'paginarbusqueda()').'
</td>
</tr></thead>';
	$tlinea=1;
	while($filadet=$objDB->sf($tabladetalle)){
		$sPrefijo='<a href="javascript:Devuelve(\''.$filadet['saiu27id'].'\');">';
		$sSufijo='</a>';
		$tlinea++;
		$et_saiu27activo=$sPrefijo.$ETI['no'].$sSufijo;
		if ($filadet['saiu27activo']=='S'){$et_saiu27activo=$sPrefijo.$ETI['si'].$sSufijo;}
		$res=$res.'<tr onmouseover="cambia_color_over(this);" onmouseout="cambia_color_out(this);">
<td>'.$sPrefijo.$filadet['saiu27consec'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu27activo'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu27predet'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu27orden'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['saiu27nombre']).$sSufijo.'</td>
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