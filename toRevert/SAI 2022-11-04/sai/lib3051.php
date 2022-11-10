<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2021 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.25.10c martes, 30 de marzo de 2021
--- Modelo Versión 2.26.3b viernes, 6 de agosto de 2021
--- 3051 saiu51tramitedoc
*/
/** Archivo lib3051.php.
* Libreria 3051 saiu51tramitedoc.
* @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
* @date martes, 30 de marzo de 2021
*/
function f3051_HTMLComboV2_saiu51idtipotram($objDB, $objCombos, $valor){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('saiu51idtipotram', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	//$objCombos->iAncho=450;
	$objCombos->sAccion='RevisaLlave();';
	$sSQL='SELECT saiu46id AS id, saiu46nombre AS nombre FROM saiu46tipotramite';
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f3051_ExisteDato($datos){
	if(!is_array($datos)){$datos=json_decode(str_replace('\"','"',$datos),true);}
	$_SESSION['u_ultimominuto']=iminutoavance();
	$bHayLlave=true;
	$saiu51idtipotram=numeros_validar($datos[1]);
	if ($saiu51idtipotram==''){$bHayLlave=false;}
	$saiu51consec=numeros_validar($datos[2]);
	if ($saiu51consec==''){$bHayLlave=false;}
	if ($bHayLlave){
		require './app.php';
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		$sSQL='SELECT 1 FROM saiu51tramitedoc WHERE saiu51idtipotram='.$saiu51idtipotram.' AND saiu51consec='.$saiu51consec.'';
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
function f3051_Busquedas($aParametros){
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3051='lg/lg_3051_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3051)){$mensajes_3051='lg/lg_3051_es.php';}
	require $mensajes_todas;
	require $mensajes_3051;
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
	$sTitulo='<h2>'.$ETI['titulo_3051'].' - '.$sTitulo.'</h2>';
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97titulo', 'innerHTML', $sTitulo);
	$objResponse->assign('div_97params', 'innerHTML', $sParams);
	$objResponse->assign('div_97tabla', 'innerHTML', $sTabla);
	$objResponse->setFunction('paginarbusqueda','',$sJavaBusqueda);
	$objResponse->call('expandesector(97)');
	return $objResponse;
	}
function f3051_HtmlBusqueda($aParametros){
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
function f3051_TablaDetalleV2($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3051='lg/lg_3051_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3051)){$mensajes_3051='lg/lg_3051_es.php';}
	require $mensajes_todas;
	require $mensajes_3051;
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	if (isset($aParametros[100])==0){$aParametros[100]=$_SESSION['unad_id_tercero'];}
	if (isset($aParametros[101])==0){$aParametros[101]=1;}
	if (isset($aParametros[102])==0){$aParametros[102]=20;}
	//if (isset($aParametros[103])==0){$aParametros[103]='';}
	//if (isset($aParametros[104])==0){$aParametros[104]='';}
	//$aParametros[103]=numeros_validar($aParametros[103]);
	//$aParametros[104]=numeros_validar($aParametros[104]);
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
	$sBotones='<input id="paginaf3051" name="paginaf3051" type="hidden" value="'.$pagina.'"/>
	<input id="lppf3051" name="lppf3051" type="hidden" value="'.$lineastabla.'"/>';
	if ($sLeyenda!=''){
		$sLeyenda='<div class="salto1px"></div>
		<div class="GrupoCamposAyuda">
		'.$sLeyenda.'
		<div class="salto1px"></div>
		</div>';
		return array($sLeyenda.$sBotones, $sDebug);
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
	//if ($aParametros[104]!=''){$sSQLadd=$sSQLadd.' AND TB.campo2 LIKE "%'.$aParametros[104].'%"';}
	//if ($aParametros[104]!=''){$sSQLadd1=$sSQLadd1.'TB.campo2 LIKE "%'.$aParametros[104].'%" AND ';}
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
	$sTitulos='Tipotram, Consec, Id, Vigente, Opcional, Orden, Nombre';
	$registros=0;
	$bGigante=false; //En caso de que la tabla sea muy grande pasarlo a true
	$sLimite='';
	if ($bGigante){
		$sSQL='SELECT COUNT(1) AS Total 
		FROM saiu51tramitedoc AS TB, saiu46tipotramite AS T1 
		WHERE '.$sSQLadd1.' TB.saiu51idtipotram=T1.saiu46id '.$sSQLadd.'';
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
	$sSQL='SELECT T1.saiu46nombre, TB.saiu51consec, TB.saiu51id, TB.saiu51vigente, TB.saiu51opcional, TB.saiu51orden, TB.saiu51nombre, TB.saiu51proveedor, TB.saiu51idtipotram 
	FROM saiu51tramitedoc AS TB, saiu46tipotramite AS T1 
	WHERE '.$sSQLadd1.' TB.saiu51idtipotram=T1.saiu46id '.$sSQLadd.'
	ORDER BY TB.saiu51idtipotram, TB.saiu51consec';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_3051" name="consulta_3051" type="hidden" value="'.$sSQLlista.'"/>
	<input id="titulos_3051" name="titulos_3051" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objDB->ejecutasql($sSQL.$sLimite);
	if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 3051: '.$sSQL.$sLimite.'<br>';}
	if ($tabladetalle==false){
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		//$sLeyenda=$sSQL;
		}else{
		if (!$bGigante){
			$registros=$objDB->nf($tabladetalle);
			if ($registros==0){
				//return array(utf8_encode($sErrConsulta.'<input id="paginaf3051" name="paginaf3051" type="hidden" value="'.$pagina.'"/><input id="lppf3051" name="lppf3051" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
				}
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
	<td><b>'.$ETI['saiu51idtipotram'].'</b></td>
	<td><b>'.$ETI['saiu51consec'].'</b></td>
	<td><b>'.$ETI['saiu51vigente'].'</b></td>
	<td><b>'.$ETI['saiu51opcional'].'</b></td>
	<td><b>'.$ETI['saiu51orden'].'</b></td>
	<td><b>'.$ETI['saiu51nombre'].'</b></td>
	<td><b>'.$ETI['saiu51proveedor'].'</b></td>
	<td align="right">
	'.html_paginador('paginaf3051', $registros, $lineastabla, $pagina, 'paginarf3051()').'
	'.html_lpp('lppf3051', $lineastabla, 'paginarf3051()').'
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
		$et_saiu51vigente=$sPrefijo.$ETI['no'].$sSufijo;
		if ($filadet['saiu51vigente']=='S'){$et_saiu51vigente=$sPrefijo.$ETI['si'].$sSufijo;}
		if ($bAbierta){
			$sLink='<a href="javascript:cargaridf3051('.$filadet['saiu51id'].')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$res=$res.'<tr'.$sClass.'>
		<td>'.$sPrefijo.cadena_notildes($filadet['saiu46nombre']).$sSufijo.'</td>
		<td>'.$sPrefijo.$filadet['saiu51consec'].$sSufijo.'</td>
		<td>'.$sPrefijo.$filadet['saiu51vigente'].$sSufijo.'</td>
		<td>'.$sPrefijo.$filadet['saiu51opcional'].$sSufijo.'</td>
		<td>'.$sPrefijo.$filadet['saiu51orden'].$sSufijo.'</td>
		<td>'.$sPrefijo.cadena_notildes($filadet['saiu51nombre']).$sSufijo.'</td>
		<td>'.$sPrefijo.$asaiu51proveedor[$filadet['saiu51proveedor']].$sSufijo.'</td>
		<td>'.$sLink.'</td>
		</tr>';
		}
	$res=$res.'</table>
	<div class="salto5px"></div>
	</div>';
	$objDB->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
	}
function f3051_HtmlTabla($aParametros){
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
	list($sDetalle, $sDebugTabla)=f3051_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f3051detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f3051_db_CargarPadre($DATA, $objDB, $bDebug=false){
	$sError='';
	$iTipoError=0;
	$sDebug='';
	require './app.php';
	if ($DATA['paso']==1){
		$sSQLcondi='saiu51idtipotram='.$DATA['saiu51idtipotram'].' AND saiu51consec='.$DATA['saiu51consec'].'';
		}else{
		$sSQLcondi='saiu51id='.$DATA['saiu51id'].'';
		}
	$sSQL='SELECT * FROM saiu51tramitedoc WHERE '.$sSQLcondi;
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$DATA['saiu51idtipotram']=$fila['saiu51idtipotram'];
		$DATA['saiu51consec']=$fila['saiu51consec'];
		$DATA['saiu51id']=$fila['saiu51id'];
		$DATA['saiu51vigente']=$fila['saiu51vigente'];
		$DATA['saiu51opcional']=$fila['saiu51opcional'];
		$DATA['saiu51orden']=$fila['saiu51orden'];
		$DATA['saiu51nombre']=$fila['saiu51nombre'];
		$DATA['saiu51proveedor']=$fila['saiu51proveedor'];
		$DATA['sau51idtipodocumento']=$fila['sau51idtipodocumento'];
		$DATA['saiu51visible']=$fila['saiu51visible'];
		$bcargo=true;
		$DATA['paso']=2;
		$DATA['boculta3051']=0;
		$bLimpiaHijos=true;
		}else{
		$DATA['paso']=0;
		}
	return array($DATA, $sError, $iTipoError, $sDebug);
	}
function f3051_db_GuardarV2($DATA, $objDB, $bDebug=false){
	$iCodModulo=3051;
	$bAudita[2]=true;
	$bAudita[3]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3051='lg/lg_3051_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3051)){$mensajes_3051='lg/lg_3051_es.php';}
	require $mensajes_todas;
	require $mensajes_3051;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	// -- Se inicia validando todas las posibles entradas de usuario.
	/*
	if (isset($DATA['saiu51idtipotram'])==0){$DATA['saiu51idtipotram']='';}
	if (isset($DATA['saiu51consec'])==0){$DATA['saiu51consec']='';}
	if (isset($DATA['saiu51id'])==0){$DATA['saiu51id']='';}
	if (isset($DATA['saiu51vigente'])==0){$DATA['saiu51vigente']='';}
	if (isset($DATA['saiu51opcional'])==0){$DATA['saiu51opcional']='';}
	if (isset($DATA['saiu51orden'])==0){$DATA['saiu51orden']='';}
	if (isset($DATA['saiu51nombre'])==0){$DATA['saiu51nombre']='';}
	if (isset($DATA['saiu51proveedor'])==0){$DATA['saiu51proveedor']='';}
	if (isset($DATA['sau51idtipodocumento'])==0){$DATA['sau51idtipodocumento']='';}
	if (isset($DATA['saiu51visible'])==0){$DATA['saiu51visible']='';}
	*/
	$DATA['saiu51idtipotram']=numeros_validar($DATA['saiu51idtipotram']);
	$DATA['saiu51consec']=numeros_validar($DATA['saiu51consec']);
	$DATA['saiu51vigente']=numeros_validar($DATA['saiu51vigente']);
	$DATA['saiu51opcional']=numeros_validar($DATA['saiu51opcional']);
	$DATA['saiu51orden']=numeros_validar($DATA['saiu51orden']);
	$DATA['saiu51nombre']=htmlspecialchars(trim($DATA['saiu51nombre']));
	$DATA['saiu51proveedor']=numeros_validar($DATA['saiu51proveedor']);
	$DATA['sau51idtipodocumento']=numeros_validar($DATA['sau51idtipodocumento']);
	$DATA['saiu51visible']=numeros_validar($DATA['saiu51visible']);
	// -- Se inicializan las variables que puedan pasar vacias {Especialmente números}.
	//if ($DATA['saiu51vigente']==''){$DATA['saiu51vigente']=0;}
	//if ($DATA['saiu51opcional']==''){$DATA['saiu51opcional']=0;}
	//if ($DATA['saiu51orden']==''){$DATA['saiu51orden']=0;}
	if ($DATA['saiu51proveedor']==''){$DATA['saiu51proveedor']=0;}
	if ($DATA['sau51idtipodocumento']==''){$DATA['sau51idtipodocumento']=0;}
	if ($DATA['saiu51visible']==''){$DATA['saiu51visible']=0;}
	// -- Seccion para validar los posibles causales de error.
	$sSepara=', ';
	if (true){
		if ($DATA['saiu51nombre']==''){$sError=$ERR['saiu51nombre'].$sSepara.$sError;}
		//if ($DATA['saiu51orden']==''){$sError=$ERR['saiu51orden'].$sSepara.$sError;}
		if ($DATA['saiu51opcional']==''){$sError=$ERR['saiu51opcional'].$sSepara.$sError;}
		if ($DATA['saiu51vigente']==''){$sError=$ERR['saiu51vigente'].$sSepara.$sError;}
		//Fin de las valiaciones NO LLAVE.
		}
	//Valiaciones de campos obligatorios en todo guardar.
	if ($DATA['saiu51idtipotram']==''){$sError=$ERR['saiu51idtipotram'];}
	// -- Se verifican los valores de campos de otras tablas.
	$bQuitarCodigo=false;
	$sCampoCodigo='';
	if ($sError==''){
		if ($DATA['paso']==10){
			if ($DATA['saiu51consec']==''){
				$DATA['saiu51consec']=tabla_consecutivo('saiu51tramitedoc', 'saiu51consec', 'saiu51idtipotram='.$DATA['saiu51idtipotram'].'', $objDB);
				if ($DATA['saiu51consec']==-1){$sError=$objDB->serror;}
				$bQuitarCodigo=true;
				$sCampoCodigo='saiu51consec';
				}else{
				if (!seg_revisa_permiso($iCodModulo, 8, $objDB)){
					$sError=$ERR['8'];
					$DATA['saiu51consec']='';
					}
				}
			if ($sError==''){
				$sSQL='SELECT 1 FROM saiu51tramitedoc WHERE saiu51idtipotram='.$DATA['saiu51idtipotram'].' AND saiu51consec='.$DATA['saiu51consec'].'';
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
			$DATA['saiu51id']=tabla_consecutivo('saiu51tramitedoc','saiu51id', '', $objDB);
			if ($DATA['saiu51id']==-1){$sError=$objDB->serror;}
			}
		}
	if ($sError==''){
		if ($DATA['saiu51orden']==''){$DATA['saiu51orden']=$DATA['saiu51consec'];}
		$bPasa=false;
		if ($DATA['paso']==10){
			$sCampos3051='saiu51idtipotram, saiu51consec, saiu51id, saiu51vigente, saiu51opcional, 
			saiu51orden, saiu51nombre, saiu51proveedor, sau51idtipodocumento, saiu51visible';
			$sValores3051=''.$DATA['saiu51idtipotram'].', '.$DATA['saiu51consec'].', '.$DATA['saiu51id'].', '.$DATA['saiu51vigente'].', '.$DATA['saiu51opcional'].', 
			'.$DATA['saiu51orden'].', "'.$DATA['saiu51nombre'].'", '.$DATA['saiu51proveedor'].', '.$DATA['sau51idtipodocumento'].', '.$DATA['saiu51visible'].'';
			if ($APP->utf8==1){
				$sSQL='INSERT INTO saiu51tramitedoc ('.$sCampos3051.') VALUES ('.utf8_encode($sValores3051).');';
				$sdetalle=$sCampos3051.'['.utf8_encode($sValores3051).']';
				}else{
				$sSQL='INSERT INTO saiu51tramitedoc ('.$sCampos3051.') VALUES ('.$sValores3051.');';
				$sdetalle=$sCampos3051.'['.$sValores3051.']';
				}
			$idAccion=2;
			$bPasa=true;
			}else{
			$scampo[1]='saiu51vigente';
			$scampo[2]='saiu51opcional';
			$scampo[3]='saiu51orden';
			$scampo[4]='saiu51nombre';
			$scampo[5]='saiu51proveedor';
			$scampo[6]='sau51idtipodocumento';
			$scampo[7]='saiu51visible';
			$sdato[1]=$DATA['saiu51vigente'];
			$sdato[2]=$DATA['saiu51opcional'];
			$sdato[3]=$DATA['saiu51orden'];
			$sdato[4]=$DATA['saiu51nombre'];
			$sdato[5]=$DATA['saiu51proveedor'];
			$sdato[6]=$DATA['sau51idtipodocumento'];
			$sdato[7]=$DATA['saiu51visible'];
			$numcmod=7;
			$sWhere='saiu51id='.$DATA['saiu51id'].'';
			$sSQL='SELECT * FROM saiu51tramitedoc WHERE '.$sWhere;
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
					$sSQL='UPDATE saiu51tramitedoc SET '.utf8_encode($sdatos).' WHERE '.$sWhere.';';
					}else{
					$sdetalle=$sdatos.'['.$sWhere.']';
					$sSQL='UPDATE saiu51tramitedoc SET '.$sdatos.' WHERE '.$sWhere.';';
					}
				$idAccion=3;
				}
			}
		if ($bPasa){
			if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Guardar 3051 '.$sSQL.'<br>';}
			$result=$objDB->ejecutasql($sSQL);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' [3051] ..<!-- '.$sSQL.' -->';
				if ($idAccion==2){
					$DATA['saiu51id']='';
					$bQuitarCodigo=true;
					$DATA['paso']=0;
					}else{
					$DATA['paso']=2;
					}
				}else{
				if ($bAudita[$idAccion]){seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], $idAccion, $DATA['saiu51id'], $sdetalle, $objDB);}
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
function f3051_db_Eliminar($saiu51id, $objDB, $bDebug=false){
	$iCodModulo=3051;
	$bAudita[4]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3051='lg/lg_3051_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3051)){$mensajes_3051='lg/lg_3051_es.php';}
	require $mensajes_todas;
	require $mensajes_3051;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	$saiu51id=numeros_validar($saiu51id);
	// Traer los datos para hacer las validaciones.
	if ($sError==''){
		$sSQL='SELECT * FROM saiu51tramitedoc WHERE saiu51id='.$saiu51id.'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$filabase=$objDB->sf($tabla);
			}else{
			$sError='No se encuentra el registro solicitado {Ref: '.$saiu51id.'}';
			}
		}
	if ($sError==''){
		if (!seg_revisa_permiso($iCodModulo, 4, $objDB)){
			$sError=$ERR['4'];
			}
		}
	if ($sError==''){
		$sSQL='SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=3051';
		$tablaor=$objDB->ejecutasql($sSQL);
		while ($filaor=$objDB->sf($tablaor)){
			$sSQL='SELECT '.$filaor['campoid'].' FROM '.$filaor['tabla'].' WHERE '.$filaor['camporev'].'='.$_REQUEST['saiu51id'].' LIMIT 0, 1';
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
		$sWhere='saiu51id='.$saiu51id.'';
		//$sWhere='saiu51consec='.$filabase['saiu51consec'].' AND saiu51idtipotram='.$filabase['saiu51idtipotram'].'';
		$sSQL='DELETE FROM saiu51tramitedoc WHERE '.$sWhere.';';
		$result=$objDB->ejecutasql($sSQL);
		if ($result==false){
			$sError=$ERR['falla_eliminar'].' .. <!-- '.$sSQL.' -->';
			}else{
			if ($bAudita[4]){seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $saiu51id, $sWhere, $objDB);}
			}
		}
	return array($sError, $iTipoError, $sDebug);
	}
function f3051_TituloBusqueda(){
	return 'Busqueda de Documento para tramites';
	}
function f3051_ParametrosBusqueda(){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3051='lg/lg_3051_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3051)){$mensajes_3051='lg/lg_3051_es.php';}
	require $mensajes_todas;
	require $mensajes_3051;
	$sParams='<label class="Label90">
	'.$ETI['msg_bnombre'].'
	</label>
	<label>
	<input id="b3051nombre" name="b3051nombre" type="text" value="" onchange="paginarbusqueda()" />
	</label>';
	return $sParams;
	}
function f3051_JavaScriptBusqueda($iModuloBusca){
	$sRes='var sCampo=window.document.frmedita.scampobusca.value;
	var params=new Array();
	params[100]=sCampo;
	params[101]=window.document.frmedita.paginabusqueda.value;
	params[102]=window.document.frmedita.lppfbusqueda.value;
	params[103]=window.document.frmedita.b3051nombre.value;
	xajax_f'.$iModuloBusca.'_HtmlBusqueda(params);';
	return $sRes;
	}
function f3051_TablaDetalleBusquedas($aParametros, $objDB){
	$res='';
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3051='lg/lg_3051_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3051)){$mensajes_3051='lg/lg_3051_es.php';}
	require $mensajes_todas;
	require $mensajes_3051;
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
		return array($sLeyenda.'<input id="paginaf3051" name="paginaf3051" type="hidden" value="'.$pagina.'"/><input id="lppf3051" name="lppf3051" type="hidden" value="'.$lineastabla.'"/>', $sDebug);
		die();
		}
	$sSQLadd='';
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
	$sTitulos='Tipotram, Consec, Id, Vigente, Opcional, Orden, Nombre';
	$sSQL='SELECT T1.saiu46nombre, TB.saiu51consec, TB.saiu51id, TB.saiu51vigente, TB.saiu51opcional, TB.saiu51orden, TB.saiu51nombre, TB.saiu51idtipotram 
	FROM saiu51tramitedoc AS TB, saiu46tipotramite AS T1 
	WHERE '.$sSQLadd1.' TB.saiu51idtipotram=T1.saiu46id '.$sSQLadd.'
	ORDER BY TB.saiu51idtipotram, TB.saiu51consec';
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
			//return array(utf8_encode($sErrConsulta.'<input id="paginaf3051" name="paginaf3051" type="hidden" value="'.$pagina.'"/><input id="lppf3051" name="lppf3051" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
	<td><b>'.$ETI['saiu51idtipotram'].'</b></td>
	<td><b>'.$ETI['saiu51consec'].'</b></td>
	<td><b>'.$ETI['saiu51vigente'].'</b></td>
	<td><b>'.$ETI['saiu51opcional'].'</b></td>
	<td><b>'.$ETI['saiu51orden'].'</b></td>
	<td><b>'.$ETI['saiu51nombre'].'</b></td>
	<td align="right">
	'.html_paginador('paginabusqueda', $registros, $lineastabla, $pagina, 'paginarbusqueda()').'
	'.html_lpp('lppfbusqueda', $lineastabla, 'paginarbusqueda()').'
	</td>
	</tr></thead>';
	$tlinea=1;
	while($filadet=$objDB->sf($tabladetalle)){
		$sPrefijo='<a href="javascript:Devuelve(\''.$filadet['saiu51id'].'\');">';
		$sSufijo='</a>';
		$tlinea++;
		$et_saiu51vigente=$sPrefijo.$ETI['no'].$sSufijo;
		if ($filadet['saiu51vigente']=='S'){$et_saiu51vigente=$sPrefijo.$ETI['si'].$sSufijo;}
		$res=$res.'<tr onmouseover="cambia_color_over(this);" onmouseout="cambia_color_out(this);">
		<td>'.$sPrefijo.cadena_notildes($filadet['saiu46nombre']).$sSufijo.'</td>
		<td>'.$sPrefijo.$filadet['saiu51consec'].$sSufijo.'</td>
		<td>'.$sPrefijo.$filadet['saiu51vigente'].$sSufijo.'</td>
		<td>'.$sPrefijo.$filadet['saiu51opcional'].$sSufijo.'</td>
		<td>'.$sPrefijo.$filadet['saiu51orden'].$sSufijo.'</td>
		<td>'.$sPrefijo.cadena_notildes($filadet['saiu51nombre']).$sSufijo.'</td>
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