<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2022 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.27.5 viernes, 7 de enero de 2022
--- 3066 saiu66tiponotifica
*/
/** Archivo lib3066.php.
* Libreria 3066 saiu66tiponotifica.
* @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
* @date viernes, 7 de enero de 2022
*/
function f3066_ExisteDato($datos){
	if(!is_array($datos)){$datos=json_decode(str_replace('\"','"',$datos),true);}
	$_SESSION['u_ultimominuto']=iminutoavance();
	$bHayLlave=true;
	$saiu66consec=numeros_validar($datos[1]);
	if ($saiu66consec==''){$bHayLlave=false;}
	if ($bHayLlave){
		require './app.php';
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		$sSQL='SELECT 1 FROM saiu66tiponotifica WHERE saiu66consec='.$saiu66consec.'';
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
function f3066_Busquedas($aParametros){
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3066='lg/lg_3066_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3066)){$mensajes_3066='lg/lg_3066_es.php';}
	require $mensajes_todas;
	require $mensajes_3066;
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$sCampo=$aParametros[1];
	$sTitulo=' {'.$sCampo.'}';
	if (isset($aParametros[2])==0){$aParametros[2]=0;}
	if (isset($aParametros[3])==0){$aParametros[3]=0;}
	$iPiel=iDefinirPiel($APP, 1);
	$sParams='';
	$sTabla='';
	$sJavaBusqueda='';
	$aParametrosB=array();
	$aParametrosB[101]=1;
	$aParametrosB[102]=20;
	switch($sCampo){
		}
	$sTitulo='<h2>'.$ETI['titulo_3066'].' - '.$sTitulo.'</h2>';
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97titulo', 'innerHTML', $sTitulo);
	$objResponse->assign('div_97params', 'innerHTML', $sParams);
	$objResponse->assign('div_97tabla', 'innerHTML', $sTabla);
	$objResponse->setFunction('paginarbusqueda','',$sJavaBusqueda);
	$objResponse->call('expandesector(97)');
	return $objResponse;
	}
function f3066_HtmlBusqueda($aParametros){
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
function f3066_TablaDetalleV2($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3066='lg/lg_3066_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3066)){$mensajes_3066='lg/lg_3066_es.php';}
	require $mensajes_todas;
	require $mensajes_3066;
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	if (isset($aParametros[100])==0){$aParametros[100]=$_SESSION['unad_id_tercero'];}
	if (isset($aParametros[101])==0){$aParametros[101]=1;}
	if (isset($aParametros[102])==0){$aParametros[102]=20;}
	//if (isset($aParametros[103])==0){$aParametros[103]='';}
	//if (isset($aParametros[104])==0){$aParametros[104]='';}
	$idTercero=$aParametros[100];
	$sDebug='';
	$pagina=$aParametros[101];
	$lineastabla=$aParametros[102];
	//$bNombre=trim($aParametros[103]);
	//$bListar=numeros_validar($aParametros[104]);
	$bAbierta=true;
	//$sSQL='SELECT Campo FROM Tabla WHERE Id='.$sValorId;
	//$tabla=$objDB->ejecutasql($sSQL);
	//if ($objDB->nf($tabla)>0){
		//$fila=$objDB->sf($tabla);
		//if ($fila['Campo']!='S'){$bAbierta=true;}
		//}
	$sLeyenda='';
	$sBotones='<input id="paginaf3066" name="paginaf3066" type="hidden" value="'.$pagina.'"/>
	<input id="lppf3066" name="lppf3066" type="hidden" value="'.$lineastabla.'"/>';
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
	if (true){
		//Esta condición la ponemos para mantener la conparación con los arhcivos tipo e
		$sSQLadd='1';
		$sSQLadd1='';
		//if ($aParametros[104]!=''){$sSQLadd=$sSQLadd.' AND TB.campo2 LIKE "%'.$aParametros[104].'%"';}
		//if ($aParametros[104]!=''){$sSQLadd1=$sSQLadd1.'TB.campo2 LIKE "%'.$aParametros[104].'%" AND ';}
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
		}
	//-- Area para saltar comparaciones con los archivos tipo e
	//-- Fin del area no comparada
	$sTitulos='Consec, Id, Orden, Activa, Nombre';
	$registros=0;
	$bGigante=false; //En caso de que la tabla sea muy grande pasarlo a true
	$sLimite='';
	if ($bGigante){
		$sSQL='SELECT COUNT(1) AS Total 
		FROM saiu66tiponotifica AS TB 
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
	$sSQL='SELECT TB.saiu66consec, TB.saiu66id, TB.saiu66orden, TB.saiu66activa, TB.saiu66nombre 
	FROM saiu66tiponotifica AS TB 
	WHERE '.$sSQLadd1.'  '.$sSQLadd.'
	ORDER BY TB.saiu66consec';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_3066" name="consulta_3066" type="hidden" value="'.$sSQLlista.'"/>
	<input id="titulos_3066" name="titulos_3066" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objDB->ejecutasql($sSQL.$sLimite);
	if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 3066: '.$sSQL.$sLimite.'<br>';}
	if ($tabladetalle==false){
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		//$sLeyenda=$sSQL;
		}else{
		if (!$bGigante){
			$registros=$objDB->nf($tabladetalle);
			//if ($registros==0){
				//return array($sErrConsulta.$sBotones, $sDebug);
				//}
			if ((($registros-1)/$lineastabla)<($pagina-1)){$pagina=(int)(($registros-1)/$lineastabla)+1;}
			if ($registros>$lineastabla){
				$rbase=($pagina-1)*$lineastabla;
				$sLimite=' LIMIT '.$rbase.', '.$lineastabla;
				$tabladetalle=$objDB->ejecutasql($sSQL.$sLimite);
				}
			}
		}
	$res=$sErrConsulta.$sLeyenda;
	$res=$res.'<div class="table-responsive">
	<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">
	<thead class="fondoazul"><tr>
	<td><b>'.$ETI['saiu66consec'].'</b></td>
	<td><b>'.$ETI['saiu66orden'].'</b></td>
	<td><b>'.$ETI['saiu66activa'].'</b></td>
	<td><b>'.$ETI['saiu66nombre'].'</b></td>
	<td align="right">
	'.html_paginador('paginaf3066', $registros, $lineastabla, $pagina, 'paginarf3066()').'
	'.html_lpp('lppf3066', $lineastabla, 'paginarf3066()').'
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
		$et_saiu66activa=$sPrefijo.$ETI['no'].$sSufijo;
		if ($filadet['saiu66activa']=='S'){$et_saiu66activa=$sPrefijo.$ETI['si'].$sSufijo;}
		if ($bAbierta){
			$sLink='<a href="javascript:cargaridf3066('.$filadet['saiu66id'].')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$res=$res.'<tr'.$sClass.'>
		<td>'.$sPrefijo.$filadet['saiu66consec'].$sSufijo.'</td>
		<td>'.$sPrefijo.$filadet['saiu66orden'].$sSufijo.'</td>
		<td>'.$sPrefijo.$filadet['saiu66activa'].$sSufijo.'</td>
		<td>'.$sPrefijo.cadena_notildes($filadet['saiu66nombre']).$sSufijo.'</td>
		<td>'.$sLink.'</td>
		</tr>';
		}
	$res=$res.'</table>
	<div class="salto5px"></div>
	</div>';
	$objDB->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
	}
function f3066_HtmlTabla($aParametros){
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
	list($sDetalle, $sDebugTabla)=f3066_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f3066detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f3066_db_CargarPadre($DATA, $objDB, $bDebug=false){
	$sError='';
	$iTipoError=0;
	$sDebug='';
	require './app.php';
	if ($DATA['paso']==1){
		$sSQLcondi='saiu66consec='.$DATA['saiu66consec'].'';
		}else{
		$sSQLcondi='saiu66id='.$DATA['saiu66id'].'';
		}
	$sSQL='SELECT * FROM saiu66tiponotifica WHERE '.$sSQLcondi;
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$DATA['saiu66consec']=$fila['saiu66consec'];
		$DATA['saiu66id']=$fila['saiu66id'];
		$DATA['saiu66orden']=$fila['saiu66orden'];
		$DATA['saiu66activa']=$fila['saiu66activa'];
		$DATA['saiu66nombre']=$fila['saiu66nombre'];
		$bcargo=true;
		$DATA['paso']=2;
		$DATA['boculta3066']=0;
		$bLimpiaHijos=true;
		}else{
		$DATA['paso']=0;
		}
	return array($DATA, $sError, $iTipoError, $sDebug);
	}
function f3066_db_GuardarV2($DATA, $objDB, $bDebug=false, $idTercero=0){
	$iCodModulo=3066;
	$bAudita[2]=true;
	$bAudita[3]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3066='lg/lg_3066_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3066)){$mensajes_3066='lg/lg_3066_es.php';}
	require $mensajes_todas;
	require $mensajes_3066;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	if ($idTercero==0){$idTercero=$_SESSION['unad_id_tercero'];}
	// -- Se inicia validando todas las posibles entradas de usuario.
	/*
	if (isset($DATA['saiu66consec'])==0){$DATA['saiu66consec']='';}
	if (isset($DATA['saiu66id'])==0){$DATA['saiu66id']='';}
	if (isset($DATA['saiu66orden'])==0){$DATA['saiu66orden']='';}
	if (isset($DATA['saiu66activa'])==0){$DATA['saiu66activa']='';}
	if (isset($DATA['saiu66nombre'])==0){$DATA['saiu66nombre']='';}
	*/
	$DATA['saiu66consec']=numeros_validar($DATA['saiu66consec']);
	$DATA['saiu66orden']=numeros_validar($DATA['saiu66orden']);
	$DATA['saiu66activa']=numeros_validar($DATA['saiu66activa']);
	$DATA['saiu66nombre']=htmlspecialchars(trim($DATA['saiu66nombre']));
	// -- Se inicializan las variables que puedan pasar vacias {Especialmente números}.
	//if ($DATA['saiu66activa']==''){$DATA['saiu66activa']=0;}
	// -- Seccion para validar los posibles causales de error.
	$sSepara=', ';
	if (true){
		if ($DATA['saiu66nombre']==''){$sError=$ERR['saiu66nombre'].$sSepara.$sError;}
		if ($DATA['saiu66activa']==''){$sError=$ERR['saiu66activa'].$sSepara.$sError;}
		//if ($DATA['saiu66orden']==''){$sError=$ERR['saiu66orden'].$sSepara.$sError;}//ORDEN
		//Fin de las valiaciones NO LLAVE.
		}
	//Valiaciones de campos obligatorios en todo guardar.
	// -- Se verifican los valores de campos de otras tablas.
	$bQuitarCodigo=false;
	$sCampoCodigo='';
	if ($sError==''){
		if ($DATA['paso']==10){
			if ($DATA['saiu66consec']==''){
				$DATA['saiu66consec']=tabla_consecutivo('saiu66tiponotifica', 'saiu66consec', '', $objDB);
				if ($DATA['saiu66consec']==-1){$sError=$objDB->serror;}
				$bQuitarCodigo=true;
				$sCampoCodigo='saiu66consec';
				}else{
				list($bDevuelve, $sDebugP)=seg_revisa_permisoV3($iCodModulo, 8, $idTercero, $objDB);
				if (!$bDevuelve){
					$sError=$ERR['8'];
					$DATA['saiu66consec']='';
					}
				}
			if ($sError==''){
				$sSQL='SELECT 1 FROM saiu66tiponotifica WHERE saiu66consec='.$DATA['saiu66consec'].'';
				$result=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($result)!=0){
					$sError=$ERR['existe'];
					}else{
					list($bDevuelve, $sDebugP)=seg_revisa_permisoV3($iCodModulo, 2, $idTercero, $objDB);
					if (!$bDevuelve){$sError=$ERR['2'];}
					}
				}
			}else{
			list($bDevuelve, $sDebugP)=seg_revisa_permisoV3($iCodModulo, 3, $idTercero, $objDB);
			if (!$bDevuelve){$sError=$ERR['3'];}
			}
		}
	if ($sError==''){
		if ($DATA['paso']==10){
			//Preparar el Id, Si no lo hay se quita la comprobación.
			$DATA['saiu66id']=tabla_consecutivo('saiu66tiponotifica','saiu66id', '', $objDB);
			if ($DATA['saiu66id']==-1){$sError=$objDB->serror;}
			//Datos adicionales al iniciar un registro.
			}
		}
	if ($sError==''){
		if ((int)$DATA['saiu66orden']==0){$DATA['saiu66orden']=$DATA['saiu66consec'];}
		$bPasa=false;
		if ($DATA['paso']==10){
			$sCampos3066='saiu66consec, saiu66id, saiu66orden, saiu66activa, saiu66nombre';
			$sValores3066=''.$DATA['saiu66consec'].', '.$DATA['saiu66id'].', "'.$DATA['saiu66orden'].'", '.$DATA['saiu66activa'].', "'.$DATA['saiu66nombre'].'"';
			if ($APP->utf8==1){
				$sSQL='INSERT INTO saiu66tiponotifica ('.$sCampos3066.') VALUES ('.utf8_encode($sValores3066).');';
				$sdetalle=$sCampos3066.'['.utf8_encode($sValores3066).']';
				}else{
				$sSQL='INSERT INTO saiu66tiponotifica ('.$sCampos3066.') VALUES ('.$sValores3066.');';
				$sdetalle=$sCampos3066.'['.$sValores3066.']';
				}
			$idAccion=2;
			$bPasa=true;
			}else{
			$scampo[1]='saiu66orden';
			$scampo[2]='saiu66activa';
			$scampo[3]='saiu66nombre';
			$sdato[1]=$DATA['saiu66orden'];
			$sdato[2]=$DATA['saiu66activa'];
			$sdato[3]=$DATA['saiu66nombre'];
			$numcmod=3;
			$sWhere='saiu66id='.$DATA['saiu66id'].'';
			$sSQL='SELECT * FROM saiu66tiponotifica WHERE '.$sWhere;
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
						$bPasa=true;
						}
					}
				}
			if ($bPasa){
				if ($APP->utf8==1){
					$sdetalle=utf8_encode($sdatos).'['.$sWhere.']';
					$sSQL='UPDATE saiu66tiponotifica SET '.utf8_encode($sdatos).' WHERE '.$sWhere.';';
					}else{
					$sdetalle=$sdatos.'['.$sWhere.']';
					$sSQL='UPDATE saiu66tiponotifica SET '.$sdatos.' WHERE '.$sWhere.';';
					}
				$idAccion=3;
				}
			}
		if ($bPasa){
			if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Guardar 3066 '.$sSQL.'<br>';}
			$result=$objDB->ejecutasql($sSQL);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' [3066] ..<!-- '.$sSQL.' -->';
				if ($idAccion==2){
					$DATA['saiu66id']='';
					$bQuitarCodigo=true;
					$DATA['paso']=0;
					}else{
					$DATA['paso']=2;
					}
				}else{
				if ($bAudita[$idAccion]){seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], $idAccion, $DATA['saiu66id'], $sdetalle, $objDB);}
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
function f3066_db_Eliminar($saiu66id, $objDB, $bDebug=false){
	$iCodModulo=3066;
	$bAudita[4]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3066='lg/lg_3066_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3066)){$mensajes_3066='lg/lg_3066_es.php';}
	require $mensajes_todas;
	require $mensajes_3066;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	$saiu66id=numeros_validar($saiu66id);
	// Traer los datos para hacer las validaciones.
	if ($sError==''){
		$sSQL='SELECT * FROM saiu66tiponotifica WHERE saiu66id='.$saiu66id.'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$filabase=$objDB->sf($tabla);
			}else{
			$sError='No se encuentra el registro solicitado {Ref: '.$saiu66id.'}';
			}
		}
	if ($sError==''){
		if (isset($idTercero)==0){$idTercero=$_SESSION['unad_id_tercero'];}
		list($bDevuelve, $sDebugP)=seg_revisa_permisoV3($iCodModulo, 4, $idTercero, $objDB);
		if (!$bDevuelve){
			$sError=$ERR['4'];
			}
		}
	if ($sError==''){
		$sSQL='SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=3066';
		$tablaor=$objDB->ejecutasql($sSQL);
		while ($filaor=$objDB->sf($tablaor)){
			$sSQL='SELECT '.$filaor['campoid'].' FROM '.$filaor['tabla'].' WHERE '.$filaor['camporev'].'='.$_REQUEST['saiu66id'].' LIMIT 0, 1';
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
		$sWhere='saiu66id='.$saiu66id.'';
		//$sWhere='saiu66consec='.$filabase['saiu66consec'].'';
		$sSQL='DELETE FROM saiu66tiponotifica WHERE '.$sWhere.';';
		$result=$objDB->ejecutasql($sSQL);
		if ($result==false){
			$sError=$ERR['falla_eliminar'].' .. <!-- '.$sSQL.' -->';
			}else{
			if ($bAudita[4]){seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $saiu66id, $sWhere, $objDB);}
			}
		}
	return array($sError, $iTipoError, $sDebug);
	}
function f3066_TituloBusqueda(){
	return 'Busqueda de Tipos de notificaciones';
	}
function f3066_ParametrosBusqueda(){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3066='lg/lg_3066_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3066)){$mensajes_3066='lg/lg_3066_es.php';}
	require $mensajes_todas;
	require $mensajes_3066;
	$sParams='<label class="Label90">
	'.$ETI['msg_bnombre'].'
	</label>
	<label>
	<input id="b3066nombre" name="b3066nombre" type="text" value="" onchange="paginarbusqueda()" />
	</label>';
	return $sParams;
	}
function f3066_JavaScriptBusqueda($iModuloBusca){
	$sRes='var sCampo=window.document.frmedita.scampobusca.value;
	var params=new Array();
	params[100]=sCampo;
	params[101]=window.document.frmedita.paginabusqueda.value;
	params[102]=window.document.frmedita.lppfbusqueda.value;
	params[103]=window.document.frmedita.b3066nombre.value;
	xajax_f'.$iModuloBusca.'_HtmlBusqueda(params);';
	return $sRes;
	}
function f3066_TablaDetalleBusquedas($aParametros, $objDB){
	$res='';
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3066='lg/lg_3066_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3066)){$mensajes_3066='lg/lg_3066_es.php';}
	require $mensajes_todas;
	require $mensajes_3066;
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	if (isset($aParametros[100])==0){$aParametros[100]=$_SESSION['unad_id_tercero'];}
	if (isset($aParametros[101])==0){$aParametros[101]=1;}
	if (isset($aParametros[102])==0){$aParametros[102]=20;}
	if (isset($aParametros[103])==0){$aParametros[103]='';}
	if (isset($aParametros[104])==0){$aParametros[104]='';}
	$idTercero=$aParametros[100];
	$pagina=$aParametros[101];
	$lineastabla=$aParametros[102];
	//$bNombre=trim($aParametros[103]);
	//$bListar=numeros_validar($aParametros[104]);
	$bAbierta=true;
	//$sSQL='SELECT Campo FROM Tabla WHERE Id='.$sValorId;
	//$tabla=$objDB->ejecutasql($sSQL);
	//if ($objDB->nf($tabla)>0){
		//$fila=$objDB->sf($tabla);
		//if ($fila['Campo']!='S'){$bAbierta=true;}
		//}
	$sLeyenda='';
	$sBotones='<input id="paginaf3066" name="paginaf3066" type="hidden" value="'.$pagina.'"/>
	<input id="lppf3066" name="lppf3066" type="hidden" value="'.$lineastabla.'"/>';
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
	$sTitulos='Consec, Id, Orden, Activa, Nombre';
	$sSQL='SELECT TB.saiu66consec, TB.saiu66id, TB.saiu66orden, TB.saiu66activa, TB.saiu66nombre 
	FROM saiu66tiponotifica AS TB 
	WHERE '.$sSQLadd1.'  '.$sSQLadd.'
	ORDER BY TB.saiu66consec';
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
		//if ($registros==0){
			//return array($sErrConsulta.$sBotones, $sDebug);
			//}
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
	<td><b>'.$ETI['saiu66consec'].'</b></td>
	<td><b>'.$ETI['saiu66orden'].'</b></td>
	<td><b>'.$ETI['saiu66activa'].'</b></td>
	<td><b>'.$ETI['saiu66nombre'].'</b></td>
	<td align="right">
	'.html_paginador('paginabusqueda', $registros, $lineastabla, $pagina, 'paginarbusqueda()').'
	'.html_lpp('lppfbusqueda', $lineastabla, 'paginarbusqueda()').'
	</td>
	</tr></thead>';
	$tlinea=1;
	while($filadet=$objDB->sf($tabladetalle)){
		$sPrefijo='<a href="javascript:Devuelve(\''.$filadet['saiu66id'].'\');">';
		$sSufijo='</a>';
		$tlinea++;
		$et_saiu66activa=$sPrefijo.$ETI['no'].$sSufijo;
		if ($filadet['saiu66activa']=='S'){$et_saiu66activa=$sPrefijo.$ETI['si'].$sSufijo;}
		$res=$res.'<tr onmouseover="cambia_color_over(this);" onmouseout="cambia_color_out(this);">
		<td>'.$sPrefijo.$filadet['saiu66consec'].$sSufijo.'</td>
		<td>'.$sPrefijo.$filadet['saiu66orden'].$sSufijo.'</td>
		<td>'.$sPrefijo.$filadet['saiu66activa'].$sSufijo.'</td>
		<td>'.$sPrefijo.cadena_notildes($filadet['saiu66nombre']).$sSufijo.'</td>
		<td></td>
		</tr>';
		}
	$res=$res.'</table>
	<div class="salto5px"></div>
	</div>';
	$objDB->liberar($tabladetalle);
	return utf8_encode($res);
	}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------
?>