<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2021 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.25.10c martes, 30 de marzo de 2021
--- Modelo Versión 2.26.2 martes, 22 de junio de 2021
--- 3050 saiu50motivotramite
*/
/** Archivo lib3050.php.
* Libreria 3050 saiu50motivotramite.
* @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
* @date martes, 30 de marzo de 2021
*/
function f3050_HTMLComboV2_saiu50idtipotram($objDB, $objCombos, $valor){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('saiu50idtipotram', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	//$objCombos->iAncho=450;
	$objCombos->sAccion='RevisaLlave();';
	$sSQL='SELECT saiu46id AS id, saiu46nombre AS nombre FROM saiu46tipotramite';
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f3050_HTMLComboV2_saiu50idgrupotrabajo($objDB, $objCombos, $valor, $vrsaiu50idunidadresp){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('saiu50idgrupotrabajo', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	//$objCombos->iAncho=450;
	$sSQL='';
	if ((int)$vrsaiu50idunidadresp!=0){
		$sSQL='SELECT bita27id AS id, bita27nombre AS nombre FROM bita27equipotrabajo WHERE bita27idunidadfunc='.$vrsaiu50idunidadresp.'';
		}
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f3050_Combosaiu50idgrupotrabajo($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$objCombos=new clsHtmlCombos();
	$html_saiu50idgrupotrabajo=f3050_HTMLComboV2_saiu50idgrupotrabajo($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_saiu50idgrupotrabajo', 'innerHTML', $html_saiu50idgrupotrabajo);
	//$objResponse->call('$("#saiu50idgrupotrabajo").chosen()');
	return $objResponse;
	}
function f3050_ExisteDato($datos){
	if(!is_array($datos)){$datos=json_decode(str_replace('\"','"',$datos),true);}
	$_SESSION['u_ultimominuto']=iminutoavance();
	$bHayLlave=true;
	$saiu50idtipotram=numeros_validar($datos[1]);
	if ($saiu50idtipotram==''){$bHayLlave=false;}
	$saiu50consec=numeros_validar($datos[2]);
	if ($saiu50consec==''){$bHayLlave=false;}
	if ($bHayLlave){
		require './app.php';
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		$sSQL='SELECT 1 FROM saiu50motivotramite WHERE saiu50idtipotram='.$saiu50idtipotram.' AND saiu50consec='.$saiu50consec.'';
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
function f3050_Busquedas($aParametros){
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3050='lg/lg_3050_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3050)){$mensajes_3050='lg/lg_3050_es.php';}
	require $mensajes_todas;
	require $mensajes_3050;
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
	$sTitulo='<h2>'.$ETI['titulo_3050'].' - '.$sTitulo.'</h2>';
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97titulo', 'innerHTML', $sTitulo);
	$objResponse->assign('div_97params', 'innerHTML', $sParams);
	$objResponse->assign('div_97tabla', 'innerHTML', $sTabla);
	$objResponse->setFunction('paginarbusqueda','',$sJavaBusqueda);
	$objResponse->call('expandesector(97)');
	return $objResponse;
	}
function f3050_HtmlBusqueda($aParametros){
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
function f3050_TablaDetalleV2($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3050='lg/lg_3050_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3050)){$mensajes_3050='lg/lg_3050_es.php';}
	require $mensajes_todas;
	require $mensajes_3050;
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
	$sBotones='<input id="paginaf3050" name="paginaf3050" type="hidden" value="'.$pagina.'"/>
	<input id="lppf3050" name="lppf3050" type="hidden" value="'.$lineastabla.'"/>';
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
	$sTitulos='Tipotram, Consec, Id, Vigente, Orden, Nombre';
	$registros=0;
	$bGigante=false; //En caso de que la tabla sea muy grande pasarlo a true
	$sLimite='';
	if ($bGigante){
		$sSQL='SELECT COUNT(1) AS Total 
		FROM saiu50motivotramite AS TB, saiu46tipotramite AS T1 
		WHERE '.$sSQLadd1.' TB.saiu50idtipotram=T1.saiu46id '.$sSQLadd.'';
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
	$sSQL='SELECT T1.saiu46nombre, TB.saiu50consec, TB.saiu50id, TB.saiu50vigente, TB.saiu50orden, TB.saiu50nombre, TB.saiu50idtipotram 
	FROM saiu50motivotramite AS TB, saiu46tipotramite AS T1 
	WHERE '.$sSQLadd1.' TB.saiu50idtipotram=T1.saiu46id '.$sSQLadd.'
	ORDER BY TB.saiu50idtipotram, TB.saiu50consec';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_3050" name="consulta_3050" type="hidden" value="'.$sSQLlista.'"/>
	<input id="titulos_3050" name="titulos_3050" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objDB->ejecutasql($sSQL.$sLimite);
	if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 3050: '.$sSQL.$sLimite.'<br>';}
	if ($tabladetalle==false){
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		//$sLeyenda=$sSQL;
		}else{
		if (!$bGigante){
			$registros=$objDB->nf($tabladetalle);
			if ($registros==0){
				//return array(utf8_encode($sErrConsulta.'<input id="paginaf3050" name="paginaf3050" type="hidden" value="'.$pagina.'"/><input id="lppf3050" name="lppf3050" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
	<td><b>'.$ETI['saiu50idtipotram'].'</b></td>
	<td><b>'.$ETI['saiu50consec'].'</b></td>
	<td><b>'.$ETI['saiu50vigente'].'</b></td>
	<td><b>'.$ETI['saiu50orden'].'</b></td>
	<td><b>'.$ETI['saiu50nombre'].'</b></td>
	<td align="right">
	'.html_paginador('paginaf3050', $registros, $lineastabla, $pagina, 'paginarf3050()').'
	'.html_lpp('lppf3050', $lineastabla, 'paginarf3050()').'
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
		$et_saiu50vigente=$sPrefijo.$ETI['no'].$sSufijo;
		if ($filadet['saiu50vigente']=='S'){$et_saiu50vigente=$sPrefijo.$ETI['si'].$sSufijo;}
		if ($bAbierta){
			$sLink='<a href="javascript:cargaridf3050('.$filadet['saiu50id'].')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$res=$res.'<tr'.$sClass.'>
		<td>'.$sPrefijo.cadena_notildes($filadet['saiu46nombre']).$sSufijo.'</td>
		<td>'.$sPrefijo.$filadet['saiu50consec'].$sSufijo.'</td>
		<td>'.$sPrefijo.$filadet['saiu50vigente'].$sSufijo.'</td>
		<td>'.$sPrefijo.$filadet['saiu50orden'].$sSufijo.'</td>
		<td>'.$sPrefijo.cadena_notildes($filadet['saiu50nombre']).$sSufijo.'</td>
		<td>'.$sLink.'</td>
		</tr>';
		}
	$res=$res.'</table>
	<div class="salto5px"></div>
	</div>';
	$objDB->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
	}
function f3050_HtmlTabla($aParametros){
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
	list($sDetalle, $sDebugTabla)=f3050_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f3050detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f3050_db_CargarPadre($DATA, $objDB, $bDebug=false){
	$sError='';
	$iTipoError=0;
	$sDebug='';
	require './app.php';
	if ($DATA['paso']==1){
		$sSQLcondi='saiu50idtipotram='.$DATA['saiu50idtipotram'].' AND saiu50consec='.$DATA['saiu50consec'].'';
		}else{
		$sSQLcondi='saiu50id='.$DATA['saiu50id'].'';
		}
	$sSQL='SELECT * FROM saiu50motivotramite WHERE '.$sSQLcondi;
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$DATA['saiu50idtipotram']=$fila['saiu50idtipotram'];
		$DATA['saiu50consec']=$fila['saiu50consec'];
		$DATA['saiu50id']=$fila['saiu50id'];
		$DATA['saiu50vigente']=$fila['saiu50vigente'];
		$DATA['saiu50orden']=$fila['saiu50orden'];
		$DATA['saiu50nombre']=$fila['saiu50nombre'];
		$DATA['saiu50detalleest']=$fila['saiu50detalleest'];
		$DATA['saiu50idunidadresp']=$fila['saiu50idunidadresp'];
		$DATA['saiu50idgrupotrabajo']=$fila['saiu50idgrupotrabajo'];
		$DATA['saiu50tiemporptadias']=$fila['saiu50tiemporptadias'];
		$bcargo=true;
		$DATA['paso']=2;
		$DATA['boculta3050']=0;
		$bLimpiaHijos=true;
		}else{
		$DATA['paso']=0;
		}
	return array($DATA, $sError, $iTipoError, $sDebug);
	}
function f3050_db_GuardarV2($DATA, $objDB, $bDebug=false){
	$iCodModulo=3050;
	$bAudita[2]=true;
	$bAudita[3]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3050='lg/lg_3050_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3050)){$mensajes_3050='lg/lg_3050_es.php';}
	require $mensajes_todas;
	require $mensajes_3050;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	// -- Se inicia validando todas las posibles entradas de usuario.
	/*
	if (isset($DATA['saiu50idtipotram'])==0){$DATA['saiu50idtipotram']='';}
	if (isset($DATA['saiu50consec'])==0){$DATA['saiu50consec']='';}
	if (isset($DATA['saiu50id'])==0){$DATA['saiu50id']='';}
	if (isset($DATA['saiu50vigente'])==0){$DATA['saiu50vigente']='';}
	if (isset($DATA['saiu50orden'])==0){$DATA['saiu50orden']='';}
	if (isset($DATA['saiu50nombre'])==0){$DATA['saiu50nombre']='';}
	if (isset($DATA['saiu50detalleest'])==0){$DATA['saiu50detalleest']='';}
	if (isset($DATA['saiu50idunidadresp'])==0){$DATA['saiu50idunidadresp']='';}
	if (isset($DATA['saiu50idgrupotrabajo'])==0){$DATA['saiu50idgrupotrabajo']='';}
	if (isset($DATA['saiu50tiemporptadias'])==0){$DATA['saiu50tiemporptadias']='';}
	*/
	$DATA['saiu50idtipotram']=numeros_validar($DATA['saiu50idtipotram']);
	$DATA['saiu50consec']=numeros_validar($DATA['saiu50consec']);
	$DATA['saiu50vigente']=numeros_validar($DATA['saiu50vigente']);
	$DATA['saiu50orden']=numeros_validar($DATA['saiu50orden']);
	$DATA['saiu50nombre']=htmlspecialchars(trim($DATA['saiu50nombre']));
	$DATA['saiu50detalleest']=htmlspecialchars(trim($DATA['saiu50detalleest']));
	$DATA['saiu50idunidadresp']=numeros_validar($DATA['saiu50idunidadresp']);
	$DATA['saiu50idgrupotrabajo']=numeros_validar($DATA['saiu50idgrupotrabajo']);
	$DATA['saiu50tiemporptadias']=numeros_validar($DATA['saiu50tiemporptadias']);
	// -- Se inicializan las variables que puedan pasar vacias {Especialmente números}.
	//if ($DATA['saiu50vigente']==''){$DATA['saiu50vigente']=0;}
	//if ($DATA['saiu50orden']==''){$DATA['saiu50orden']=0;}
	//if ($DATA['saiu50idunidadresp']==''){$DATA['saiu50idunidadresp']=0;}
	//if ($DATA['saiu50idgrupotrabajo']==''){$DATA['saiu50idgrupotrabajo']=0;}
	//if ($DATA['saiu50tiemporptadias']==''){$DATA['saiu50tiemporptadias']=0;}
	// -- Seccion para validar los posibles causales de error.
	$sSepara=', ';
	if (true){
		if ($DATA['saiu50tiemporptadias']==''){$sError=$ERR['saiu50tiemporptadias'].$sSepara.$sError;}
		if ($DATA['saiu50idgrupotrabajo']==''){$sError=$ERR['saiu50idgrupotrabajo'].$sSepara.$sError;}
		if ($DATA['saiu50idunidadresp']==''){$sError=$ERR['saiu50idunidadresp'].$sSepara.$sError;}
		//if ($DATA['saiu50detalleest']==''){$sError=$ERR['saiu50detalleest'].$sSepara.$sError;}
		if ($DATA['saiu50nombre']==''){$sError=$ERR['saiu50nombre'].$sSepara.$sError;}
		//if ($DATA['saiu50orden']==''){$sError=$ERR['saiu50orden'].$sSepara.$sError;}
		if ($DATA['saiu50vigente']==''){$sError=$ERR['saiu50vigente'].$sSepara.$sError;}
		//Fin de las valiaciones NO LLAVE.
		}
	//Valiaciones de campos obligatorios en todo guardar.
	if ($DATA['saiu50idtipotram']==''){$sError=$ERR['saiu50idtipotram'];}
	// -- Se verifican los valores de campos de otras tablas.
	$bQuitarCodigo=false;
	$sCampoCodigo='';
	if ($sError==''){
		if ($DATA['paso']==10){
			if ($DATA['saiu50consec']==''){
				$DATA['saiu50consec']=tabla_consecutivo('saiu50motivotramite', 'saiu50consec', 'saiu50idtipotram='.$DATA['saiu50idtipotram'].'', $objDB);
				if ($DATA['saiu50consec']==-1){$sError=$objDB->serror;}
				$bQuitarCodigo=true;
				$sCampoCodigo='saiu50consec';
				}else{
				if (!seg_revisa_permiso($iCodModulo, 8, $objDB)){
					$sError=$ERR['8'];
					$DATA['saiu50consec']='';
					}
				}
			if ($sError==''){
				$sSQL='SELECT 1 FROM saiu50motivotramite WHERE saiu50idtipotram='.$DATA['saiu50idtipotram'].' AND saiu50consec='.$DATA['saiu50consec'].'';
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
			$DATA['saiu50id']=tabla_consecutivo('saiu50motivotramite','saiu50id', '', $objDB);
			if ($DATA['saiu50id']==-1){$sError=$objDB->serror;}
			}
		}
	if ($sError==''){
		if ($DATA['saiu50orden']==''){$DATA['saiu50orden']=$DATA['saiu50consec'];}
		$saiu50detalleest=str_replace('"', '\"', $DATA['saiu50detalleest']);
		$bpasa=false;
		if ($DATA['paso']==10){
			$sCampos3050='saiu50idtipotram, saiu50consec, saiu50id, saiu50vigente, saiu50orden, 
			saiu50nombre, saiu50detalleest, saiu50idunidadresp, saiu50idgrupotrabajo, saiu50tiemporptadias';
			$sValores3050=''.$DATA['saiu50idtipotram'].', '.$DATA['saiu50consec'].', '.$DATA['saiu50id'].', '.$DATA['saiu50vigente'].', '.$DATA['saiu50orden'].', 
			"'.$DATA['saiu50nombre'].'", "'.$saiu50detalleest.'", '.$DATA['saiu50idunidadresp'].', '.$DATA['saiu50idgrupotrabajo'].', '.$DATA['saiu50tiemporptadias'].'';
			if ($APP->utf8==1){
				$sSQL='INSERT INTO saiu50motivotramite ('.$sCampos3050.') VALUES ('.utf8_encode($sValores3050).');';
				$sdetalle=$sCampos3050.'['.utf8_encode($sValores3050).']';
				}else{
				$sSQL='INSERT INTO saiu50motivotramite ('.$sCampos3050.') VALUES ('.$sValores3050.');';
				$sdetalle=$sCampos3050.'['.$sValores3050.']';
				}
			$idAccion=2;
			$bpasa=true;
			}else{
			$scampo[1]='saiu50vigente';
			$scampo[2]='saiu50orden';
			$scampo[3]='saiu50nombre';
			$scampo[4]='saiu50detalleest';
			$scampo[5]='saiu50idunidadresp';
			$scampo[6]='saiu50idgrupotrabajo';
			$scampo[7]='saiu50tiemporptadias';
			$sdato[1]=$DATA['saiu50vigente'];
			$sdato[2]=$DATA['saiu50orden'];
			$sdato[3]=$DATA['saiu50nombre'];
			$sdato[4]=$saiu50detalleest;
			$sdato[5]=$DATA['saiu50idunidadresp'];
			$sdato[6]=$DATA['saiu50idgrupotrabajo'];
			$sdato[7]=$DATA['saiu50tiemporptadias'];
			$numcmod=7;
			$sWhere='saiu50id='.$DATA['saiu50id'].'';
			$sSQL='SELECT * FROM saiu50motivotramite WHERE '.$sWhere;
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
					$sSQL='UPDATE saiu50motivotramite SET '.utf8_encode($sdatos).' WHERE '.$sWhere.';';
					}else{
					$sdetalle=$sdatos.'['.$sWhere.']';
					$sSQL='UPDATE saiu50motivotramite SET '.$sdatos.' WHERE '.$sWhere.';';
					}
				$idAccion=3;
				}
			}
		if ($bpasa){
			if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Guardar 3050 '.$sSQL.'<br>';}
			$result=$objDB->ejecutasql($sSQL);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' [3050] ..<!-- '.$sSQL.' -->';
				if ($idAccion==2){
					$DATA['saiu50id']='';
					$bQuitarCodigo=true;
					$DATA['paso']=0;
					}else{
					$DATA['paso']=2;
					}
				}else{
				if ($bAudita[$idAccion]){seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], $idAccion, $DATA['saiu50id'], $sdetalle, $objDB);}
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
function f3050_db_Eliminar($saiu50id, $objDB, $bDebug=false){
	$iCodModulo=3050;
	$bAudita[4]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3050='lg/lg_3050_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3050)){$mensajes_3050='lg/lg_3050_es.php';}
	require $mensajes_todas;
	require $mensajes_3050;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	$saiu50id=numeros_validar($saiu50id);
	// Traer los datos para hacer las validaciones.
	if ($sError==''){
		$sSQL='SELECT * FROM saiu50motivotramite WHERE saiu50id='.$saiu50id.'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$filabase=$objDB->sf($tabla);
			}else{
			$sError='No se encuentra el registro solicitado {Ref: '.$saiu50id.'}';
			}
		}
	if ($sError==''){
		if (!seg_revisa_permiso($iCodModulo, 4, $objDB)){
			$sError=$ERR['4'];
			}
		}
	if ($sError==''){
		$sSQL='SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=3050';
		$tablaor=$objDB->ejecutasql($sSQL);
		while ($filaor=$objDB->sf($tablaor)){
			$sSQL='SELECT '.$filaor['campoid'].' FROM '.$filaor['tabla'].' WHERE '.$filaor['camporev'].'='.$_REQUEST['saiu50id'].' LIMIT 0, 1';
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
		$sWhere='saiu50id='.$saiu50id.'';
		//$sWhere='saiu50consec='.$filabase['saiu50consec'].' AND saiu50idtipotram='.$filabase['saiu50idtipotram'].'';
		$sSQL='DELETE FROM saiu50motivotramite WHERE '.$sWhere.';';
		$result=$objDB->ejecutasql($sSQL);
		if ($result==false){
			$sError=$ERR['falla_eliminar'].' .. <!-- '.$sSQL.' -->';
			}else{
			if ($bAudita[4]){seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $saiu50id, $sWhere, $objDB);}
			}
		}
	return array($sError, $iTipoError, $sDebug);
	}
function f3050_TituloBusqueda(){
	return 'Busqueda de Motivos para tramites';
	}
function f3050_ParametrosBusqueda(){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3050='lg/lg_3050_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3050)){$mensajes_3050='lg/lg_3050_es.php';}
	require $mensajes_todas;
	require $mensajes_3050;
	$sParams='<label class="Label90">
	'.$ETI['msg_bnombre'].'
	</label>
	<label>
	<input id="b3050nombre" name="b3050nombre" type="text" value="" onchange="paginarbusqueda()" />
	</label>';
	return $sParams;
	}
function f3050_JavaScriptBusqueda($iModuloBusca){
	$sRes='var sCampo=window.document.frmedita.scampobusca.value;
	var params=new Array();
	params[100]=sCampo;
	params[101]=window.document.frmedita.paginabusqueda.value;
	params[102]=window.document.frmedita.lppfbusqueda.value;
	params[103]=window.document.frmedita.b3050nombre.value;
	xajax_f'.$iModuloBusca.'_HtmlBusqueda(params);';
	return $sRes;
	}
function f3050_TablaDetalleBusquedas($aParametros, $objDB){
	$res='';
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3050='lg/lg_3050_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3050)){$mensajes_3050='lg/lg_3050_es.php';}
	require $mensajes_todas;
	require $mensajes_3050;
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
		return array($sLeyenda.'<input id="paginaf3050" name="paginaf3050" type="hidden" value="'.$pagina.'"/><input id="lppf3050" name="lppf3050" type="hidden" value="'.$lineastabla.'"/>', $sDebug);
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
	$sTitulos='Tipotram, Consec, Id, Vigente, Orden, Nombre';
	$sSQL='SELECT T1.saiu46nombre, TB.saiu50consec, TB.saiu50id, TB.saiu50vigente, TB.saiu50orden, TB.saiu50nombre, TB.saiu50idtipotram 
	FROM saiu50motivotramite AS TB, saiu46tipotramite AS T1 
	WHERE '.$sSQLadd1.' TB.saiu50idtipotram=T1.saiu46id '.$sSQLadd.'
	ORDER BY TB.saiu50idtipotram, TB.saiu50consec';
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
			//return array(utf8_encode($sErrConsulta.'<input id="paginaf3050" name="paginaf3050" type="hidden" value="'.$pagina.'"/><input id="lppf3050" name="lppf3050" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
	<td><b>'.$ETI['saiu50idtipotram'].'</b></td>
	<td><b>'.$ETI['saiu50consec'].'</b></td>
	<td><b>'.$ETI['saiu50vigente'].'</b></td>
	<td><b>'.$ETI['saiu50orden'].'</b></td>
	<td><b>'.$ETI['saiu50nombre'].'</b></td>
	<td align="right">
	'.html_paginador('paginabusqueda', $registros, $lineastabla, $pagina, 'paginarbusqueda()').'
	'.html_lpp('lppfbusqueda', $lineastabla, 'paginarbusqueda()').'
	</td>
	</tr></thead>';
	$tlinea=1;
	while($filadet=$objDB->sf($tabladetalle)){
		$sPrefijo='<a href="javascript:Devuelve(\''.$filadet['saiu50id'].'\');">';
		$sSufijo='</a>';
		$tlinea++;
		$et_saiu50vigente=$sPrefijo.$ETI['no'].$sSufijo;
		if ($filadet['saiu50vigente']=='S'){$et_saiu50vigente=$sPrefijo.$ETI['si'].$sSufijo;}
		$res=$res.'<tr onmouseover="cambia_color_over(this);" onmouseout="cambia_color_out(this);">
		<td>'.$sPrefijo.cadena_notildes($filadet['saiu46nombre']).$sSufijo.'</td>
		<td>'.$sPrefijo.$filadet['saiu50consec'].$sSufijo.'</td>
		<td>'.$sPrefijo.$filadet['saiu50vigente'].$sSufijo.'</td>
		<td>'.$sPrefijo.$filadet['saiu50orden'].$sSufijo.'</td>
		<td>'.$sPrefijo.cadena_notildes($filadet['saiu50nombre']).$sSufijo.'</td>
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