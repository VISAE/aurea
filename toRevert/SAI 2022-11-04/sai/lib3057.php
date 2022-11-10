<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2021 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.25.10c lunes, 12 de abril de 2021
--- 3057 saiu57correos
*/
/** Archivo lib3057.php.
* Libreria 3057 saiu57correos.
* @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
* @date lunes, 12 de abril de 2021
*/
function f3057_ExisteDato($datos){
	if(!is_array($datos)){$datos=json_decode(str_replace('\"','"',$datos),true);}
	$_SESSION['u_ultimominuto']=iminutoavance();
	$bHayLlave=true;
	$saiu57consec=numeros_validar($datos[1]);
	if ($saiu57consec==''){$bHayLlave=false;}
	if ($bHayLlave){
		require './app.php';
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		$sSQL='SELECT 1 FROM saiu57correos WHERE saiu57consec='.$saiu57consec.'';
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
function f3057_Busquedas($aParametros){
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3057='lg/lg_3057_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3057)){$mensajes_3057='lg/lg_3057_es.php';}
	require $mensajes_todas;
	require $mensajes_3057;
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
	$sTitulo='<h2>'.$ETI['titulo_3057'].' - '.$sTitulo.'</h2>';
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97titulo', 'innerHTML', $sTitulo);
	$objResponse->assign('div_97params', 'innerHTML', $sParams);
	$objResponse->assign('div_97tabla', 'innerHTML', $sTabla);
	$objResponse->setFunction('paginarbusqueda','',$sJavaBusqueda);
	$objResponse->call('expandesector(97)');
	return $objResponse;
	}
function f3057_HtmlBusqueda($aParametros){
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
function f3057_TablaDetalleV2($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3057='lg/lg_3057_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3057)){$mensajes_3057='lg/lg_3057_es.php';}
	require $mensajes_todas;
	require $mensajes_3057;
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
	$sBotones='<input id="paginaf3057" name="paginaf3057" type="hidden" value="'.$pagina.'"/>
	<input id="lppf3057" name="lppf3057" type="hidden" value="'.$lineastabla.'"/>';
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
	$sSQLadd='1';
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
	$sTitulos='Consec, Id, Vigente, Orden, Titulo, Servidorsmtp, Puertomail, Autenticacion, Usuariomail, Pwdmail, Confirmado';
	$registros=0;
	$bGigante=false; //En caso de que la tabla sea muy grande pasarlo a true
	$sLimite='';
	if ($bGigante){
		$sSQL='SELECT COUNT(1) AS Total 
		FROM saiu57correos AS TB 
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
	$sSQL='SELECT TB.saiu57consec, TB.saiu57id, TB.saiu57vigente, TB.saiu57orden, TB.saiu57titulo, TB.saiu57servidorsmtp, TB.saiu57puertomail, TB.saiu57autenticacion, TB.saiu57usuariomail, TB.saiu57pwdmail, TB.saiu57confirmado 
	FROM saiu57correos AS TB 
	WHERE '.$sSQLadd1.'  '.$sSQLadd.'
	ORDER BY TB.saiu57consec';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_3057" name="consulta_3057" type="hidden" value="'.$sSQLlista.'"/>
	<input id="titulos_3057" name="titulos_3057" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objDB->ejecutasql($sSQL.$sLimite);
	if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 3057: '.$sSQL.$sLimite.'<br>';}
	if ($tabladetalle==false){
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		//$sLeyenda=$sSQL;
		}else{
		if (!$bGigante){
			$registros=$objDB->nf($tabladetalle);
			if ($registros==0){
				//return array(utf8_encode($sErrConsulta.'<input id="paginaf3057" name="paginaf3057" type="hidden" value="'.$pagina.'"/><input id="lppf3057" name="lppf3057" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
	<td><b>'.$ETI['saiu57consec'].'</b></td>
	<td><b>'.$ETI['saiu57vigente'].'</b></td>
	<td><b>'.$ETI['saiu57orden'].'</b></td>
	<td><b>'.$ETI['saiu57titulo'].'</b></td>
	<td><b>'.$ETI['saiu57confirmado'].'</b></td>
	<td align="right">
	'.html_paginador('paginaf3057', $registros, $lineastabla, $pagina, 'paginarf3057()').'
	'.html_lpp('lppf3057', $lineastabla, 'paginarf3057()').'
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
		$et_saiu57vigente=$sPrefijo.$ETI['no'].$sSufijo;
		if ($filadet['saiu57vigente']==1){$et_saiu57vigente=$sPrefijo.$ETI['si'].$sSufijo;}
		$et_saiu57confirmado=$sPrefijo.$ETI['si'].$sSufijo;
		if ($filadet['saiu57confirmado']==0){$et_saiu57confirmado=$sPrefijo.$ETI['no'].$sSufijo;}
		if ($bAbierta){
			$sLink='<a href="javascript:cargaridf3057('.$filadet['saiu57id'].')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$res=$res.'<tr'.$sClass.'>
		<td>'.$sPrefijo.$filadet['saiu57consec'].$sSufijo.'</td>
		<td>'.$sPrefijo.$et_saiu57vigente.$sSufijo.'</td>
		<td>'.$sPrefijo.$filadet['saiu57orden'].$sSufijo.'</td>
		<td>'.$sPrefijo.cadena_notildes($filadet['saiu57titulo']).$sSufijo.'</td>
		<td>'.$sPrefijo.$et_saiu57confirmado.$sSufijo.'</td>
		<td>'.$sLink.'</td>
		</tr>';
		}
	$res=$res.'</table>
	<div class="salto5px"></div>
	</div>';
	$objDB->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
	}
function f3057_HtmlTabla($aParametros){
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
	list($sDetalle, $sDebugTabla)=f3057_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f3057detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f3057_db_CargarPadre($DATA, $objDB, $bDebug=false){
	$sError='';
	$iTipoError=0;
	$sDebug='';
	require './app.php';
	if ($DATA['paso']==1){
		$sSQLcondi='saiu57consec='.$DATA['saiu57consec'].'';
		}else{
		$sSQLcondi='saiu57id='.$DATA['saiu57id'].'';
		}
	$sSQL='SELECT * FROM saiu57correos WHERE '.$sSQLcondi;
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$DATA['saiu57consec']=$fila['saiu57consec'];
		$DATA['saiu57id']=$fila['saiu57id'];
		$DATA['saiu57vigente']=$fila['saiu57vigente'];
		$DATA['saiu57orden']=$fila['saiu57orden'];
		$DATA['saiu57titulo']=$fila['saiu57titulo'];
		$DATA['saiu57servidorsmtp']=$fila['saiu57servidorsmtp'];
		$DATA['saiu57puertomail']=$fila['saiu57puertomail'];
		$DATA['saiu57autenticacion']=$fila['saiu57autenticacion'];
		$DATA['saiu57usuariomail']=$fila['saiu57usuariomail'];
		$DATA['saiu57pwdmail']=$fila['saiu57pwdmail'];
		$DATA['saiu57confirmado']=$fila['saiu57confirmado'];
		$DATA['saiu57servidorimpa']=$fila['saiu57servidorimpa'];
		$DATA['saiu57puertoimpap']=$fila['saiu57puertoimpap'];
		$DATA['saiu57autentimap']=$fila['saiu57autentimap'];
		$bcargo=true;
		$DATA['paso']=2;
		$DATA['boculta3057']=0;
		$bLimpiaHijos=true;
		}else{
		$DATA['paso']=0;
		}
	return array($DATA, $sError, $iTipoError, $sDebug);
	}
function f3057_db_GuardarV2($DATA, $objDB, $bDebug=false){
	$iCodModulo=3057;
	$bAudita[2]=true;
	$bAudita[3]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3057='lg/lg_3057_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3057)){$mensajes_3057='lg/lg_3057_es.php';}
	require $mensajes_todas;
	require $mensajes_3057;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	// -- Se inicia validando todas las posibles entradas de usuario.
	/*
	if (isset($DATA['saiu57consec'])==0){$DATA['saiu57consec']='';}
	if (isset($DATA['saiu57id'])==0){$DATA['saiu57id']='';}
	if (isset($DATA['saiu57vigente'])==0){$DATA['saiu57vigente']='';}
	if (isset($DATA['saiu57orden'])==0){$DATA['saiu57orden']='';}
	if (isset($DATA['saiu57titulo'])==0){$DATA['saiu57titulo']='';}
	if (isset($DATA['saiu57servidorsmtp'])==0){$DATA['saiu57servidorsmtp']='';}
	if (isset($DATA['saiu57puertomail'])==0){$DATA['saiu57puertomail']='';}
	if (isset($DATA['saiu57autenticacion'])==0){$DATA['saiu57autenticacion']='';}
	if (isset($DATA['saiu57usuariomail'])==0){$DATA['saiu57usuariomail']='';}
	if (isset($DATA['saiu57pwdmail'])==0){$DATA['saiu57pwdmail']='';}
	if (isset($DATA['saiu57servidorimpa'])==0){$DATA['saiu57servidorimpa']='';}
	if (isset($DATA['saiu57puertoimpap'])==0){$DATA['saiu57puertoimpap']='';}
	if (isset($DATA['saiu57autentimap'])==0){$DATA['saiu57autentimap']='';}
	*/
	$DATA['saiu57consec']=numeros_validar($DATA['saiu57consec']);
	$DATA['saiu57vigente']=numeros_validar($DATA['saiu57vigente']);
	$DATA['saiu57orden']=numeros_validar($DATA['saiu57orden']);
	$DATA['saiu57titulo']=htmlspecialchars(trim($DATA['saiu57titulo']));
	$DATA['saiu57servidorsmtp']=htmlspecialchars(trim($DATA['saiu57servidorsmtp']));
	$DATA['saiu57puertomail']=htmlspecialchars(trim($DATA['saiu57puertomail']));
	$DATA['saiu57autenticacion']=htmlspecialchars(trim($DATA['saiu57autenticacion']));
	$DATA['saiu57usuariomail']=htmlspecialchars(trim($DATA['saiu57usuariomail']));
	$DATA['saiu57pwdmail']=htmlspecialchars(trim($DATA['saiu57pwdmail']));
	$DATA['saiu57servidorimpa']=htmlspecialchars(trim($DATA['saiu57servidorimpa']));
	$DATA['saiu57puertoimpap']=htmlspecialchars(trim($DATA['saiu57puertoimpap']));
	$DATA['saiu57autentimap']=htmlspecialchars(trim($DATA['saiu57autentimap']));
	// -- Se inicializan las variables que puedan pasar vacias {Especialmente números}.
	//if ($DATA['saiu57vigente']==''){$DATA['saiu57vigente']=0;}
	//if ($DATA['saiu57orden']==''){$DATA['saiu57orden']=0;}
	if ($DATA['saiu57confirmado']==''){$DATA['saiu57confirmado']=0;}
	// -- Seccion para validar los posibles causales de error.
	$sSepara=', ';
	if (true){
		if ($DATA['saiu57pwdmail']==''){$sError=$ERR['saiu57pwdmail'].$sSepara.$sError;}
		if ($DATA['saiu57usuariomail']==''){$sError=$ERR['saiu57usuariomail'].$sSepara.$sError;}
		//if ($DATA['saiu57autenticacion']==''){$sError=$ERR['saiu57autenticacion'].$sSepara.$sError;}
		//if ($DATA['saiu57puertomail']==''){$sError=$ERR['saiu57puertomail'].$sSepara.$sError;}
		if ($DATA['saiu57servidorsmtp']==''){$sError=$ERR['saiu57servidorsmtp'].$sSepara.$sError;}
		if ($DATA['saiu57titulo']==''){$sError=$ERR['saiu57titulo'].$sSepara.$sError;}
		//if ($DATA['saiu57orden']==''){$sError=$ERR['saiu57orden'].$sSepara.$sError;}
		if ($DATA['saiu57vigente']==''){$sError=$ERR['saiu57vigente'].$sSepara.$sError;}
		//Fin de las valiaciones NO LLAVE.
		}
	//Valiaciones de campos obligatorios en todo guardar.
	// -- Se verifican los valores de campos de otras tablas.
	$bQuitarCodigo=false;
	$sCampoCodigo='';
	if ($sError==''){
		if ($DATA['paso']==10){
			if ($DATA['saiu57consec']==''){
				$DATA['saiu57consec']=tabla_consecutivo('saiu57correos', 'saiu57consec', '', $objDB);
				if ($DATA['saiu57consec']==-1){$sError=$objDB->serror;}
				$bQuitarCodigo=true;
				$sCampoCodigo='saiu57consec';
				}else{
				if (!seg_revisa_permiso($iCodModulo, 8, $objDB)){
					$sError=$ERR['8'];
					$DATA['saiu57consec']='';
					}
				}
			if ($sError==''){
				$sSQL='SELECT 1 FROM saiu57correos WHERE saiu57consec='.$DATA['saiu57consec'].'';
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
			$DATA['saiu57id']=tabla_consecutivo('saiu57correos','saiu57id', '', $objDB);
			if ($DATA['saiu57id']==-1){$sError=$objDB->serror;}
			}
		}
	if ($sError==''){
		$bpasa=false;
		if ($DATA['saiu57orden']==''){$DATA['saiu57orden']=$DATA['saiu57consec'];}
		if ($DATA['paso']==10){
			$DATA['saiu57confirmado']=0;
			$sCampos3057='saiu57consec, saiu57id, saiu57vigente, saiu57orden, saiu57titulo, 
			saiu57servidorsmtp, saiu57puertomail, saiu57autenticacion, saiu57usuariomail, saiu57pwdmail, 
			saiu57confirmado, saiu57servidorimpa, saiu57puertoimpap, saiu57autentimap';
			$sValores3057=''.$DATA['saiu57consec'].', '.$DATA['saiu57id'].', '.$DATA['saiu57vigente'].', '.$DATA['saiu57orden'].', "'.$DATA['saiu57titulo'].'", 
			"'.$DATA['saiu57servidorsmtp'].'", "'.$DATA['saiu57puertomail'].'", "'.$DATA['saiu57autenticacion'].'", "'.$DATA['saiu57usuariomail'].'", "'.$DATA['saiu57pwdmail'].'", 
			'.$DATA['saiu57confirmado'].', "'.$DATA['saiu57servidorimpa'].'", "'.$DATA['saiu57puertoimpap'].'", "'.$DATA['saiu57autentimap'].'"';
			if ($APP->utf8==1){
				$sSQL='INSERT INTO saiu57correos ('.$sCampos3057.') VALUES ('.utf8_encode($sValores3057).');';
				$sdetalle=$sCampos3057.'['.utf8_encode($sValores3057).']';
				}else{
				$sSQL='INSERT INTO saiu57correos ('.$sCampos3057.') VALUES ('.$sValores3057.');';
				$sdetalle=$sCampos3057.'['.$sValores3057.']';
				}
			$idAccion=2;
			$bpasa=true;
			}else{
			$scampo[1]='saiu57vigente';
			$scampo[2]='saiu57orden';
			$scampo[3]='saiu57titulo';
			$scampo[4]='saiu57servidorsmtp';
			$scampo[5]='saiu57puertomail';
			$scampo[6]='saiu57autenticacion';
			$scampo[7]='saiu57usuariomail';
			$scampo[8]='saiu57pwdmail';
			$scampo[9]='saiu57servidorimpa';
			$scampo[10]='saiu57puertoimpap';
			$scampo[11]='saiu57autentimap';
			$sdato[1]=$DATA['saiu57vigente'];
			$sdato[2]=$DATA['saiu57orden'];
			$sdato[3]=$DATA['saiu57titulo'];
			$sdato[4]=$DATA['saiu57servidorsmtp'];
			$sdato[5]=$DATA['saiu57puertomail'];
			$sdato[6]=$DATA['saiu57autenticacion'];
			$sdato[7]=$DATA['saiu57usuariomail'];
			$sdato[8]=$DATA['saiu57pwdmail'];
			$sdato[9]=$DATA['saiu57servidorimpa'];
			$sdato[10]=$DATA['saiu57puertoimpap'];
			$sdato[11]=$DATA['saiu57autentimap'];
			$numcmod=11;
			$sWhere='saiu57id='.$DATA['saiu57id'].'';
			$sSQL='SELECT * FROM saiu57correos WHERE '.$sWhere;
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
					$sSQL='UPDATE saiu57correos SET '.utf8_encode($sdatos).' WHERE '.$sWhere.';';
					}else{
					$sdetalle=$sdatos.'['.$sWhere.']';
					$sSQL='UPDATE saiu57correos SET '.$sdatos.' WHERE '.$sWhere.';';
					}
				$idAccion=3;
				}
			}
		if ($bpasa){
			if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Guardar 3057 '.$sSQL.'<br>';}
			$result=$objDB->ejecutasql($sSQL);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' [3057] ..<!-- '.$sSQL.' -->';
				if ($idAccion==2){
					$DATA['saiu57id']='';
					$bQuitarCodigo=true;
					$DATA['paso']=0;
					}else{
					$DATA['paso']=2;
					}
				}else{
				if ($bAudita[$idAccion]){seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], $idAccion, $DATA['saiu57id'], $sdetalle, $objDB);}
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
function f3057_db_Eliminar($saiu57id, $objDB, $bDebug=false){
	$iCodModulo=3057;
	$bAudita[4]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3057='lg/lg_3057_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3057)){$mensajes_3057='lg/lg_3057_es.php';}
	require $mensajes_todas;
	require $mensajes_3057;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	$saiu57id=numeros_validar($saiu57id);
	// Traer los datos para hacer las validaciones.
	if ($sError==''){
		$sSQL='SELECT * FROM saiu57correos WHERE saiu57id='.$saiu57id.'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$filabase=$objDB->sf($tabla);
			}else{
			$sError='No se encuentra el registro solicitado {Ref: '.$saiu57id.'}';
			}
		}
	if ($sError==''){
		if (!seg_revisa_permiso($iCodModulo, 4, $objDB)){
			$sError=$ERR['4'];
			}
		}
	if ($sError==''){
		$sSQL='SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=3057';
		$tablaor=$objDB->ejecutasql($sSQL);
		while ($filaor=$objDB->sf($tablaor)){
			$sSQL='SELECT '.$filaor['campoid'].' FROM '.$filaor['tabla'].' WHERE '.$filaor['camporev'].'='.$_REQUEST['saiu57id'].' LIMIT 0, 1';
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
		$sWhere='saiu57id='.$saiu57id.'';
		//$sWhere='saiu57consec='.$filabase['saiu57consec'].'';
		$sSQL='DELETE FROM saiu57correos WHERE '.$sWhere.';';
		$result=$objDB->ejecutasql($sSQL);
		if ($result==false){
			$sError=$ERR['falla_eliminar'].' .. <!-- '.$sSQL.' -->';
			}else{
			if ($bAudita[4]){seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $saiu57id, $sWhere, $objDB);}
			}
		}
	return array($sError, $iTipoError, $sDebug);
	}
function f3057_TituloBusqueda(){
	return 'Busqueda de Correos de soporte';
	}
function f3057_ParametrosBusqueda(){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3057='lg/lg_3057_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3057)){$mensajes_3057='lg/lg_3057_es.php';}
	require $mensajes_todas;
	require $mensajes_3057;
	$sParams='<label class="Label90">
	'.$ETI['msg_bnombre'].'
	</label>
	<label>
	<input id="b3057nombre" name="b3057nombre" type="text" value="" onchange="paginarbusqueda()" />
	</label>';
	return $sParams;
	}
function f3057_JavaScriptBusqueda($iModuloBusca){
	$sRes='var sCampo=window.document.frmedita.scampobusca.value;
	var params=new Array();
	params[100]=sCampo;
	params[101]=window.document.frmedita.paginabusqueda.value;
	params[102]=window.document.frmedita.lppfbusqueda.value;
	params[103]=window.document.frmedita.b3057nombre.value;
	xajax_f'.$iModuloBusca.'_HtmlBusqueda(params);';
	return $sRes;
	}
function f3057_TablaDetalleBusquedas($aParametros, $objDB){
	$res='';
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3057='lg/lg_3057_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3057)){$mensajes_3057='lg/lg_3057_es.php';}
	require $mensajes_todas;
	require $mensajes_3057;
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
		return array($sLeyenda.'<input id="paginaf3057" name="paginaf3057" type="hidden" value="'.$pagina.'"/><input id="lppf3057" name="lppf3057" type="hidden" value="'.$lineastabla.'"/>', $sDebug);
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
	$sTitulos='Consec, Id, Vigente, Orden, Titulo, Servidorsmtp, Puertomail, Autenticacion, Usuariomail, Pwdmail, Confirmado';
	$sSQL='SELECT TB.saiu57consec, TB.saiu57id, TB.saiu57vigente, TB.saiu57orden, TB.saiu57titulo, TB.saiu57servidorsmtp, TB.saiu57puertomail, TB.saiu57autenticacion, TB.saiu57usuariomail, TB.saiu57pwdmail, TB.saiu57confirmado 
	FROM saiu57correos AS TB 
	WHERE '.$sSQLadd1.'  '.$sSQLadd.'
	ORDER BY TB.saiu57consec';
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
			//return array(utf8_encode($sErrConsulta.'<input id="paginaf3057" name="paginaf3057" type="hidden" value="'.$pagina.'"/><input id="lppf3057" name="lppf3057" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
	<td><b>'.$ETI['saiu57consec'].'</b></td>
	<td><b>'.$ETI['saiu57vigente'].'</b></td>
	<td><b>'.$ETI['saiu57orden'].'</b></td>
	<td><b>'.$ETI['saiu57titulo'].'</b></td>
	<td><b>'.$ETI['saiu57servidorsmtp'].'</b></td>
	<td><b>'.$ETI['saiu57puertomail'].'</b></td>
	<td><b>'.$ETI['saiu57autenticacion'].'</b></td>
	<td><b>'.$ETI['saiu57usuariomail'].'</b></td>
	<td><b>'.$ETI['saiu57pwdmail'].'</b></td>
	<td><b>'.$ETI['saiu57confirmado'].'</b></td>
	<td align="right">
	'.html_paginador('paginabusqueda', $registros, $lineastabla, $pagina, 'paginarbusqueda()').'
	'.html_lpp('lppfbusqueda', $lineastabla, 'paginarbusqueda()').'
	</td>
	</tr></thead>';
	$tlinea=1;
	while($filadet=$objDB->sf($tabladetalle)){
		$sPrefijo='<a href="javascript:Devuelve(\''.$filadet['saiu57id'].'\');">';
		$sSufijo='</a>';
		$tlinea++;
		$et_saiu57vigente=$sPrefijo.$ETI['no'].$sSufijo;
		if ($filadet['saiu57vigente']=='S'){$et_saiu57vigente=$sPrefijo.$ETI['si'].$sSufijo;}
		$res=$res.'<tr onmouseover="cambia_color_over(this);" onmouseout="cambia_color_out(this);">
		<td>'.$sPrefijo.$filadet['saiu57consec'].$sSufijo.'</td>
		<td>'.$sPrefijo.$filadet['saiu57vigente'].$sSufijo.'</td>
		<td>'.$sPrefijo.$filadet['saiu57orden'].$sSufijo.'</td>
		<td>'.$sPrefijo.cadena_notildes($filadet['saiu57titulo']).$sSufijo.'</td>
		<td>'.$sPrefijo.cadena_notildes($filadet['saiu57servidorsmtp']).$sSufijo.'</td>
		<td>'.$sPrefijo.cadena_notildes($filadet['saiu57puertomail']).$sSufijo.'</td>
		<td>'.$sPrefijo.$filadet['saiu57autenticacion'].$sSufijo.'</td>
		<td>'.$sPrefijo.cadena_notildes($filadet['saiu57usuariomail']).$sSufijo.'</td>
		<td>'.$sPrefijo.cadena_notildes($filadet['saiu57pwdmail']).$sSufijo.'</td>
		<td>'.$sPrefijo.$filadet['saiu57confirmado'].$sSufijo.'</td>
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