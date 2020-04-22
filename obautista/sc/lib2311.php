<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2018 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.22.2 lunes, 16 de julio de 2018
--- 2311 cara11tipocaract
*/
function f2311_ExisteDato($datos){
	if(!is_array($datos)){$datos=json_decode(str_replace('\"','"',$datos),true);}
	$_SESSION['u_ultimominuto']=iminutoavance();
	$bHayLlave=true;
	$cara11consec=numeros_validar($datos[1]);
	if ($cara11consec==''){$bHayLlave=false;}
	if ($bHayLlave){
		require './app.php';
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		$sSQL='SELECT cara11consec FROM cara11tipocaract WHERE cara11consec='.$cara11consec.'';
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
function f2311_Busquedas($aParametros){
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2311='lg/lg_2311_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2311)){$mensajes_2311='lg/lg_2311_es.php';}
	require $mensajes_todas;
	require $mensajes_2311;
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
	$sTitulo='<h2>'.$ETI['titulo_2311'].' - '.$sTitulo.'</h2>';
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97titulo', 'innerHTML', $sTitulo);
	$objResponse->assign('div_97params', 'innerHTML', $sParams);
	$objResponse->assign('div_97tabla', 'innerHTML', $sTabla);
	$objResponse->setFunction('paginarbusqueda','',$sJavaBusqueda);
	$objResponse->call('expandesector(97)');
	return $objResponse;
	}
function f2311_HtmlBusqueda($aParametros){
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
function f2311_TablaDetalleV2($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2311='lg/lg_2311_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2311)){$mensajes_2311='lg/lg_2311_es.php';}
	require $mensajes_todas;
	require $mensajes_2311;
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	if (isset($aParametros[101])==0){$aParametros[101]=1;}
	if (isset($aParametros[102])==0){$aParametros[102]=20;}
	//if (isset($aParametros[103])==0){$aParametros[103]='';}
	//$aParametros[103]=numeros_validar($aParametros[103]);
	$sDebug='';
	$pagina=$aParametros[101];
	$lineastabla=$aParametros[102];
	$babierta=true;
	//$sSQL='SELECT Campo FROM Tabla WHERE Id='.$sValorId;
	//$tabla=$objDB->ejecutasql($sSQL);
	//if ($objDB->nf($tabla)>0){
		//$fila=$objDB->sf($tabla);
		//if ($fila['Campo']!='S'){$babierta=true;}
		//}
	$sLeyenda='';
	if (false){
		$sLeyenda='<div class="salto1px"></div>
<div class="GrupoCamposAyuda">
<b>Importante:</b> Mensaje al usuario
<div class="salto1px"></div>
</div>';
		}
	$sSQLadd='1';
	$sSQLadd1='';
	//if ((int)$aParametros[103]!=-1){$sSQLadd=$sSQLadd.' AND TB.campo='.$aParametros[103];}
	//if ($aParametros[103]!=''){$sSQLadd=$sSQLadd.' AND TB.campo2 LIKE "%'.$aParametros[103].'%"';}
	/*
	if ($aParametros[104]!=''){
		$sBase=trim(strtoupper($aParametros[104]));
		$aNoms=explode(' ', $sBase);
		for ($k=1;$k<=count($aNoms);$k++){
			$sCadena=$aNoms[$k-1];
			if ($sCadena!=''){
				$sSQLadd=$sSQLadd.' AND T6.sys11razonsocial LIKE "%'.$sCadena.'%"';
				//$sSQLadd1=$sSQLadd1.'T1.sys11razonsocial LIKE "%'.$sCadena.'%" AND ';
				}
			}
		}
	*/
	$sTitulos='Consec, Id, Nombre, Ficha1, Ficha1pregbas, Ficha1pregprof, Ficha2, Ficha2pregbas, Ficha2pregprof, Ficha3, Ficha3pregbas, Ficha3pregprof, Ficha4, Ficha4pregbas, Ficha4pregprof, Ficha5, Ficha5pregbas, Ficha5pregprof, Ficha6, Ficha6pregbas, Ficha6pregprof, Ficha7, Ficha7pregbas, Ficha7pregprof';
	$sSQL='SELECT TB.cara11consec, TB.cara11id, TB.cara11nombre, TB.cara11ficha1, TB.cara11ficha1pregbas, TB.cara11ficha1pregprof, TB.cara11ficha2, TB.cara11ficha2pregbas, TB.cara11ficha2pregprof, TB.cara11ficha3, TB.cara11ficha3pregbas, TB.cara11ficha3pregprof, TB.cara11ficha4, TB.cara11ficha4pregbas, TB.cara11ficha4pregprof, TB.cara11ficha5, TB.cara11ficha5pregbas, TB.cara11ficha5pregprof, TB.cara11ficha6, TB.cara11ficha6pregbas, TB.cara11ficha6pregprof, TB.cara11ficha7, TB.cara11ficha7pregbas, TB.cara11ficha7pregprof, TB.cara11fichafamilia 
FROM cara11tipocaract AS TB 
WHERE '.$sSQLadd1.'  '.$sSQLadd.'
ORDER BY TB.cara11nombre';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_2311" name="consulta_2311" type="hidden" value="'.$sSQLlista.'"/>
<input id="titulos_2311" name="titulos_2311" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objDB->ejecutasql($sSQL);
	if ($tabladetalle==false){
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 2311: '.$sSQL.'<br>';}
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		//$sLeyenda=$sSQL;
		}else{
		$registros=$objDB->nf($tabladetalle);
		if ($registros==0){
			//return array(utf8_encode($sErrConsulta.'<input id="paginaf2311" name="paginaf2311" type="hidden" value="'.$pagina.'"/><input id="lppf2311" name="lppf2311" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
<td><b>'.$ETI['cara11consec'].'</b></td>
<td><b>'.$ETI['cara11nombre'].'</b></td>
<td><b>'.$ETI['msg_fichas'].'</b></td>
<td align="right">
'.html_paginador('paginaf2311', $registros, $lineastabla, $pagina, 'paginarf2311()').'
'.html_lpp('lppf2311', $lineastabla, 'paginarf2311()').'
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
		$et_cara11fichafamilia='';
		if ($filadet['cara11fichafamilia']=='S'){$et_cara11fichafamilia=$ETI['cara11fichafamilia'];}
		if ($filadet['cara11ficha1']=='S'){$et_cara11fichafamilia=$et_cara11fichafamilia.' - '.$ETI['cara11ficha1'];}
		if ($filadet['cara11ficha2']=='S'){$et_cara11fichafamilia=$et_cara11fichafamilia.' - '.$ETI['cara11ficha2'];}
		if ($filadet['cara11ficha3']=='S'){$et_cara11fichafamilia=$et_cara11fichafamilia.' - '.$ETI['cara11ficha3'];}
		if ($filadet['cara11ficha4']=='S'){$et_cara11fichafamilia=$et_cara11fichafamilia.' - '.$ETI['cara11ficha4'];}
		if ($filadet['cara11ficha5']=='S'){$et_cara11fichafamilia=$et_cara11fichafamilia.' - '.$ETI['cara11ficha5'];}
		if ($filadet['cara11ficha6']=='S'){$et_cara11fichafamilia=$et_cara11fichafamilia.' - '.$ETI['cara11ficha6'];}
		if ($filadet['cara11ficha7']=='S'){$et_cara11fichafamilia=$et_cara11fichafamilia.' - '.$ETI['cara11ficha7'];}
		/*
		$et_cara11ficha1=$ETI['no'];
		$et_cara11ficha2=$ETI['no'];
		if ($filadet['cara11ficha2']=='S'){$et_cara11ficha2=$ETI['si'];}
		$et_cara11ficha3=$ETI['no'];
		if ($filadet['cara11ficha3']=='S'){$et_cara11ficha3=$ETI['si'];}
		$et_cara11ficha4=$ETI['no'];
		if ($filadet['cara11ficha4']=='S'){$et_cara11ficha4=$ETI['si'];}
		$et_cara11ficha5=$ETI['no'];
		if ($filadet['cara11ficha5']=='S'){$et_cara11ficha5=$ETI['si'];}
		$et_cara11ficha6=$ETI['no'];
		if ($filadet['cara11ficha6']=='S'){$et_cara11ficha6=$ETI['si'];}
		$et_cara11ficha7=$ETI['no'];
		if ($filadet['cara11ficha7']=='S'){$et_cara11ficha7=$ETI['si'];}
		*/
		if ($et_cara11fichafamilia==''){$et_cara11fichafamilia='<b>'.$ETI['msg_ninguna'].'</b>';}
		if ($babierta){
			$sLink='<a href="javascript:cargaridf2311('.$filadet['cara11id'].')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$res=$res.'<tr'.$sClass.'>
<td>'.$sPrefijo.$filadet['cara11consec'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['cara11nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.$et_cara11fichafamilia.$sSufijo.'</td>
<td>'.$sLink.'</td>
</tr>';
		}
	$res=$res.'</table>';
	$objDB->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
	}
function f2311_HtmlTabla($aParametros){
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
	list($sDetalle, $sDebugTabla)=f2311_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f2311detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f2311_db_CargarPadre($DATA, $objDB, $bDebug=false){
	$sError='';
	$iTipoError=0;
	$sDebug='';
	require './app.php';
	if ($DATA['paso']==1){
		$sSQLcondi='cara11consec='.$DATA['cara11consec'].'';
		}else{
		$sSQLcondi='cara11id='.$DATA['cara11id'].'';
		}
	$sSQL='SELECT * FROM cara11tipocaract WHERE '.$sSQLcondi;
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$DATA['cara11consec']=$fila['cara11consec'];
		$DATA['cara11id']=$fila['cara11id'];
		$DATA['cara11nombre']=$fila['cara11nombre'];
		$DATA['cara11ficha1']=$fila['cara11ficha1'];
		$DATA['cara11ficha1pregbas']=$fila['cara11ficha1pregbas'];
		$DATA['cara11ficha1pregprof']=$fila['cara11ficha1pregprof'];
		$DATA['cara11ficha2']=$fila['cara11ficha2'];
		$DATA['cara11ficha2pregbas']=$fila['cara11ficha2pregbas'];
		$DATA['cara11ficha2pregprof']=$fila['cara11ficha2pregprof'];
		$DATA['cara11ficha3']=$fila['cara11ficha3'];
		$DATA['cara11ficha3pregbas']=$fila['cara11ficha3pregbas'];
		$DATA['cara11ficha3pregprof']=$fila['cara11ficha3pregprof'];
		$DATA['cara11ficha4']=$fila['cara11ficha4'];
		$DATA['cara11ficha4pregbas']=$fila['cara11ficha4pregbas'];
		$DATA['cara11ficha4pregprof']=$fila['cara11ficha4pregprof'];
		$DATA['cara11ficha5']=$fila['cara11ficha5'];
		$DATA['cara11ficha5pregbas']=$fila['cara11ficha5pregbas'];
		$DATA['cara11ficha5pregprof']=$fila['cara11ficha5pregprof'];
		$DATA['cara11ficha6']=$fila['cara11ficha6'];
		$DATA['cara11ficha6pregbas']=$fila['cara11ficha6pregbas'];
		$DATA['cara11ficha6pregprof']=$fila['cara11ficha6pregprof'];
		$DATA['cara11ficha7']=$fila['cara11ficha7'];
		$DATA['cara11ficha7pregbas']=$fila['cara11ficha7pregbas'];
		$DATA['cara11ficha7pregprof']=$fila['cara11ficha7pregprof'];
		$DATA['cara11fichafamilia']=$fila['cara11fichafamilia'];
		$bcargo=true;
		$DATA['paso']=2;
		$DATA['boculta2311']=0;
		$bLimpiaHijos=true;
		}else{
		$DATA['paso']=0;
		}
	return array($DATA, $sError, $iTipoError, $sDebug);
	}
function f2311_db_GuardarV2($DATA, $objDB, $bDebug=false){
	$iCodModulo=2311;
	$bAudita[2]=true;
	$bAudita[3]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2311='lg/lg_2311_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2311)){$mensajes_2311='lg/lg_2311_es.php';}
	require $mensajes_todas;
	require $mensajes_2311;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	// -- Se inicia validando todas las posibles entradas de usuario.
	/*
	if (isset($DATA['cara11consec'])==0){$DATA['cara11consec']='';}
	if (isset($DATA['cara11id'])==0){$DATA['cara11id']='';}
	if (isset($DATA['cara11nombre'])==0){$DATA['cara11nombre']='';}
	if (isset($DATA['cara11ficha1'])==0){$DATA['cara11ficha1']='';}
	if (isset($DATA['cara11ficha1pregbas'])==0){$DATA['cara11ficha1pregbas']='';}
	if (isset($DATA['cara11ficha1pregprof'])==0){$DATA['cara11ficha1pregprof']='';}
	if (isset($DATA['cara11ficha2'])==0){$DATA['cara11ficha2']='';}
	if (isset($DATA['cara11ficha2pregbas'])==0){$DATA['cara11ficha2pregbas']='';}
	if (isset($DATA['cara11ficha2pregprof'])==0){$DATA['cara11ficha2pregprof']='';}
	if (isset($DATA['cara11ficha3'])==0){$DATA['cara11ficha3']='';}
	if (isset($DATA['cara11ficha3pregbas'])==0){$DATA['cara11ficha3pregbas']='';}
	if (isset($DATA['cara11ficha3pregprof'])==0){$DATA['cara11ficha3pregprof']='';}
	if (isset($DATA['cara11ficha4'])==0){$DATA['cara11ficha4']='';}
	if (isset($DATA['cara11ficha4pregbas'])==0){$DATA['cara11ficha4pregbas']='';}
	if (isset($DATA['cara11ficha4pregprof'])==0){$DATA['cara11ficha4pregprof']='';}
	if (isset($DATA['cara11ficha5'])==0){$DATA['cara11ficha5']='';}
	if (isset($DATA['cara11ficha5pregbas'])==0){$DATA['cara11ficha5pregbas']='';}
	if (isset($DATA['cara11ficha5pregprof'])==0){$DATA['cara11ficha5pregprof']='';}
	if (isset($DATA['cara11ficha6'])==0){$DATA['cara11ficha6']='';}
	if (isset($DATA['cara11ficha6pregbas'])==0){$DATA['cara11ficha6pregbas']='';}
	if (isset($DATA['cara11ficha6pregprof'])==0){$DATA['cara11ficha6pregprof']='';}
	if (isset($DATA['cara11ficha7'])==0){$DATA['cara11ficha7']='';}
	if (isset($DATA['cara11ficha7pregbas'])==0){$DATA['cara11ficha7pregbas']='';}
	if (isset($DATA['cara11ficha7pregprof'])==0){$DATA['cara11ficha7pregprof']='';}
	if (isset($DATA['cara11fichafamilia'])==0){$DATA['cara11fichafamilia']='';}
	*/
	$DATA['cara11consec']=numeros_validar($DATA['cara11consec']);
	$DATA['cara11nombre']=htmlspecialchars(trim($DATA['cara11nombre']));
	$DATA['cara11ficha1']=htmlspecialchars(trim($DATA['cara11ficha1']));
	$DATA['cara11ficha1pregbas']=numeros_validar($DATA['cara11ficha1pregbas']);
	$DATA['cara11ficha1pregprof']=numeros_validar($DATA['cara11ficha1pregprof']);
	$DATA['cara11ficha2']=htmlspecialchars(trim($DATA['cara11ficha2']));
	$DATA['cara11ficha2pregbas']=numeros_validar($DATA['cara11ficha2pregbas']);
	$DATA['cara11ficha2pregprof']=numeros_validar($DATA['cara11ficha2pregprof']);
	$DATA['cara11ficha3']=htmlspecialchars(trim($DATA['cara11ficha3']));
	$DATA['cara11ficha3pregbas']=numeros_validar($DATA['cara11ficha3pregbas']);
	$DATA['cara11ficha3pregprof']=numeros_validar($DATA['cara11ficha3pregprof']);
	$DATA['cara11ficha4']=htmlspecialchars(trim($DATA['cara11ficha4']));
	$DATA['cara11ficha4pregbas']=numeros_validar($DATA['cara11ficha4pregbas']);
	$DATA['cara11ficha4pregprof']=numeros_validar($DATA['cara11ficha4pregprof']);
	$DATA['cara11ficha5']=htmlspecialchars(trim($DATA['cara11ficha5']));
	$DATA['cara11ficha5pregbas']=numeros_validar($DATA['cara11ficha5pregbas']);
	$DATA['cara11ficha5pregprof']=numeros_validar($DATA['cara11ficha5pregprof']);
	$DATA['cara11ficha6']=htmlspecialchars(trim($DATA['cara11ficha6']));
	$DATA['cara11ficha6pregbas']=numeros_validar($DATA['cara11ficha6pregbas']);
	$DATA['cara11ficha6pregprof']=numeros_validar($DATA['cara11ficha6pregprof']);
	$DATA['cara11ficha7']=htmlspecialchars(trim($DATA['cara11ficha7']));
	$DATA['cara11ficha7pregbas']=numeros_validar($DATA['cara11ficha7pregbas']);
	$DATA['cara11ficha7pregprof']=numeros_validar($DATA['cara11ficha7pregprof']);
	$DATA['cara11fichafamilia']=htmlspecialchars(trim($DATA['cara11fichafamilia']));
	// -- Se inicializan las variables que puedan pasar vacias {Especialmente números}.
	if ($DATA['cara11ficha1pregbas']==''){$DATA['cara11ficha1pregbas']=0;}
	if ($DATA['cara11ficha1pregprof']==''){$DATA['cara11ficha1pregprof']=0;}
	if ($DATA['cara11ficha2pregbas']==''){$DATA['cara11ficha2pregbas']=0;}
	if ($DATA['cara11ficha2pregprof']==''){$DATA['cara11ficha2pregprof']=0;}
	if ($DATA['cara11ficha3pregbas']==''){$DATA['cara11ficha3pregbas']=0;}
	if ($DATA['cara11ficha3pregprof']==''){$DATA['cara11ficha3pregprof']=0;}
	if ($DATA['cara11ficha4pregbas']==''){$DATA['cara11ficha4pregbas']=0;}
	if ($DATA['cara11ficha4pregprof']==''){$DATA['cara11ficha4pregprof']=0;}
	if ($DATA['cara11ficha5pregbas']==''){$DATA['cara11ficha5pregbas']=0;}
	if ($DATA['cara11ficha5pregprof']==''){$DATA['cara11ficha5pregprof']=0;}
	if ($DATA['cara11ficha6pregbas']==''){$DATA['cara11ficha6pregbas']=0;}
	if ($DATA['cara11ficha6pregprof']==''){$DATA['cara11ficha6pregprof']=0;}
	if ($DATA['cara11ficha7pregbas']==''){$DATA['cara11ficha7pregbas']=0;}
	if ($DATA['cara11ficha7pregprof']==''){$DATA['cara11ficha7pregprof']=0;}
	// -- Seccion para validar los posibles causales de error.
	$sSepara=', ';
	if (true){
		if ($DATA['cara11fichafamilia']==''){$sError=$ERR['cara11fichafamilia'].$sSepara.$sError;}
		if ($DATA['cara11ficha7pregprof']==''){$sError=$ERR['cara11ficha7pregprof'].$sSepara.$sError;}
		if ($DATA['cara11ficha7pregbas']==''){$sError=$ERR['cara11ficha7pregbas'].$sSepara.$sError;}
		if ($DATA['cara11ficha7']==''){$sError=$ERR['cara11ficha7'].$sSepara.$sError;}
		if ($DATA['cara11ficha6pregprof']==''){$sError=$ERR['cara11ficha6pregprof'].$sSepara.$sError;}
		if ($DATA['cara11ficha6pregbas']==''){$sError=$ERR['cara11ficha6pregbas'].$sSepara.$sError;}
		if ($DATA['cara11ficha6']==''){$sError=$ERR['cara11ficha6'].$sSepara.$sError;}
		if ($DATA['cara11ficha5pregprof']==''){$sError=$ERR['cara11ficha5pregprof'].$sSepara.$sError;}
		if ($DATA['cara11ficha5pregbas']==''){$sError=$ERR['cara11ficha5pregbas'].$sSepara.$sError;}
		if ($DATA['cara11ficha5']==''){$sError=$ERR['cara11ficha5'].$sSepara.$sError;}
		if ($DATA['cara11ficha4pregprof']==''){$sError=$ERR['cara11ficha4pregprof'].$sSepara.$sError;}
		if ($DATA['cara11ficha4pregbas']==''){$sError=$ERR['cara11ficha4pregbas'].$sSepara.$sError;}
		if ($DATA['cara11ficha4']==''){$sError=$ERR['cara11ficha4'].$sSepara.$sError;}
		if ($DATA['cara11ficha3pregprof']==''){$sError=$ERR['cara11ficha3pregprof'].$sSepara.$sError;}
		if ($DATA['cara11ficha3pregbas']==''){$sError=$ERR['cara11ficha3pregbas'].$sSepara.$sError;}
		if ($DATA['cara11ficha3']==''){$sError=$ERR['cara11ficha3'].$sSepara.$sError;}
		if ($DATA['cara11ficha2pregprof']==''){$sError=$ERR['cara11ficha2pregprof'].$sSepara.$sError;}
		if ($DATA['cara11ficha2pregbas']==''){$sError=$ERR['cara11ficha2pregbas'].$sSepara.$sError;}
		if ($DATA['cara11ficha2']==''){$sError=$ERR['cara11ficha2'].$sSepara.$sError;}
		if ($DATA['cara11ficha1pregprof']==''){$sError=$ERR['cara11ficha1pregprof'].$sSepara.$sError;}
		if ($DATA['cara11ficha1pregbas']==''){$sError=$ERR['cara11ficha1pregbas'].$sSepara.$sError;}
		if ($DATA['cara11ficha1']==''){$sError=$ERR['cara11ficha1'].$sSepara.$sError;}
		if ($DATA['cara11nombre']==''){$sError=$ERR['cara11nombre'].$sSepara.$sError;}
		//Fin de las valiaciones NO LLAVE.
		}
	//Valiaciones de campos obligatorios en todo guardar.
	// -- Se verifican los valores de campos de otras tablas.
	if ($sError==''){
		if ($DATA['paso']==10){
			if ($DATA['cara11consec']==''){
				$DATA['cara11consec']=tabla_consecutivo('cara11tipocaract', 'cara11consec', '', $objDB);
				if ($DATA['cara11consec']==-1){$sError=$objDB->serror;}
				}else{
				if (!seg_revisa_permiso($iCodModulo, 8, $objDB)){
					$sError=$ERR['8'];
					$DATA['cara11consec']='';
					}
				}
			if ($sError==''){
				$sSQL='SELECT cara11consec FROM cara11tipocaract WHERE cara11consec='.$DATA['cara11consec'].'';
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
			$DATA['cara11id']=tabla_consecutivo('cara11tipocaract','cara11id', '', $objDB);
			if ($DATA['cara11id']==-1){$sError=$objDB->serror;}
			}
		}
	if ($sError==''){
		$bpasa=false;
		if ($DATA['paso']==10){
			$sCampos2311='cara11consec, cara11id, cara11nombre, cara11ficha1, cara11ficha1pregbas, cara11ficha1pregprof, cara11ficha2, cara11ficha2pregbas, cara11ficha2pregprof, cara11ficha3, 
cara11ficha3pregbas, cara11ficha3pregprof, cara11ficha4, cara11ficha4pregbas, cara11ficha4pregprof, cara11ficha5, cara11ficha5pregbas, cara11ficha5pregprof, cara11ficha6, cara11ficha6pregbas, 
cara11ficha6pregprof, cara11ficha7, cara11ficha7pregbas, cara11ficha7pregprof, cara11fichafamilia';
			$sValores2311=''.$DATA['cara11consec'].', '.$DATA['cara11id'].', "'.$DATA['cara11nombre'].'", "'.$DATA['cara11ficha1'].'", '.$DATA['cara11ficha1pregbas'].', '.$DATA['cara11ficha1pregprof'].', "'.$DATA['cara11ficha2'].'", '.$DATA['cara11ficha2pregbas'].', '.$DATA['cara11ficha2pregprof'].', "'.$DATA['cara11ficha3'].'", 
'.$DATA['cara11ficha3pregbas'].', '.$DATA['cara11ficha3pregprof'].', "'.$DATA['cara11ficha4'].'", '.$DATA['cara11ficha4pregbas'].', '.$DATA['cara11ficha4pregprof'].', "'.$DATA['cara11ficha5'].'", '.$DATA['cara11ficha5pregbas'].', '.$DATA['cara11ficha5pregprof'].', "'.$DATA['cara11ficha6'].'", '.$DATA['cara11ficha6pregbas'].', 
'.$DATA['cara11ficha6pregprof'].', "'.$DATA['cara11ficha7'].'", '.$DATA['cara11ficha7pregbas'].', '.$DATA['cara11ficha7pregprof'].', "'.$DATA['cara11fichafamilia'].'"';
			if ($APP->utf8==1){
				$sSQL='INSERT INTO cara11tipocaract ('.$sCampos2311.') VALUES ('.utf8_encode($sValores2311).');';
				$sdetalle=$sCampos2311.'['.utf8_encode($sValores2311).']';
				}else{
				$sSQL='INSERT INTO cara11tipocaract ('.$sCampos2311.') VALUES ('.$sValores2311.');';
				$sdetalle=$sCampos2311.'['.$sValores2311.']';
				}
			$idaccion=2;
			$bpasa=true;
			}else{
			$scampo[1]='cara11nombre';
			$scampo[2]='cara11ficha1';
			$scampo[3]='cara11ficha1pregbas';
			$scampo[4]='cara11ficha1pregprof';
			$scampo[5]='cara11ficha2';
			$scampo[6]='cara11ficha2pregbas';
			$scampo[7]='cara11ficha2pregprof';
			$scampo[8]='cara11ficha3';
			$scampo[9]='cara11ficha3pregbas';
			$scampo[10]='cara11ficha3pregprof';
			$scampo[11]='cara11ficha4';
			$scampo[12]='cara11ficha4pregbas';
			$scampo[13]='cara11ficha4pregprof';
			$scampo[14]='cara11ficha5';
			$scampo[15]='cara11ficha5pregbas';
			$scampo[16]='cara11ficha5pregprof';
			$scampo[17]='cara11ficha6';
			$scampo[18]='cara11ficha6pregbas';
			$scampo[19]='cara11ficha6pregprof';
			$scampo[20]='cara11ficha7';
			$scampo[21]='cara11ficha7pregbas';
			$scampo[22]='cara11ficha7pregprof';
			$scampo[23]='cara11fichafamilia';
			$sdato[1]=$DATA['cara11nombre'];
			$sdato[2]=$DATA['cara11ficha1'];
			$sdato[3]=$DATA['cara11ficha1pregbas'];
			$sdato[4]=$DATA['cara11ficha1pregprof'];
			$sdato[5]=$DATA['cara11ficha2'];
			$sdato[6]=$DATA['cara11ficha2pregbas'];
			$sdato[7]=$DATA['cara11ficha2pregprof'];
			$sdato[8]=$DATA['cara11ficha3'];
			$sdato[9]=$DATA['cara11ficha3pregbas'];
			$sdato[10]=$DATA['cara11ficha3pregprof'];
			$sdato[11]=$DATA['cara11ficha4'];
			$sdato[12]=$DATA['cara11ficha4pregbas'];
			$sdato[13]=$DATA['cara11ficha4pregprof'];
			$sdato[14]=$DATA['cara11ficha5'];
			$sdato[15]=$DATA['cara11ficha5pregbas'];
			$sdato[16]=$DATA['cara11ficha5pregprof'];
			$sdato[17]=$DATA['cara11ficha6'];
			$sdato[18]=$DATA['cara11ficha6pregbas'];
			$sdato[19]=$DATA['cara11ficha6pregprof'];
			$sdato[20]=$DATA['cara11ficha7'];
			$sdato[21]=$DATA['cara11ficha7pregbas'];
			$sdato[22]=$DATA['cara11ficha7pregprof'];
			$sdato[23]=$DATA['cara11fichafamilia'];
			$numcmod=23;
			$sWhere='cara11id='.$DATA['cara11id'].'';
			$sSQL='SELECT * FROM cara11tipocaract WHERE '.$sWhere;
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
					$sSQL='UPDATE cara11tipocaract SET '.utf8_encode($sdatos).' WHERE '.$sWhere.';';
					}else{
					$sdetalle=$sdatos.'['.$sWhere.']';
					$sSQL='UPDATE cara11tipocaract SET '.$sdatos.' WHERE '.$sWhere.';';
					}
				$idaccion=3;
				}
			}
		if ($bpasa){
			$result=$objDB->ejecutasql($sSQL);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' [2311] ..<!-- '.$sSQL.' -->';
				if ($idaccion==2){$DATA['cara11id']='';}
				$DATA['paso']=$DATA['paso']-10;
				}else{
				if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Guardar 2311 '.$sSQL.'<br>';}
				if ($bAudita[$idaccion]){seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], $idaccion, $DATA['cara11id'], $sdetalle, $objDB);}
				$DATA['paso']=2;
				}
			}else{
			$DATA['paso']=2;
			}
		}else{
		$DATA['paso']=$DATA['paso']-10;
		}
	//if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' InfoDepura<br>';}
	return array($DATA, $sError, $iTipoError, $sDebug);
	}
function f2311_db_Eliminar($cara11id, $objDB, $bDebug=false){
	$iCodModulo=2311;
	$bAudita[2]=true;
	$bAudita[3]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2311='lg/lg_2311_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2311)){$mensajes_2311='lg/lg_2311_es.php';}
	require $mensajes_todas;
	require $mensajes_2311;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	$cara11id=numeros_validar($cara11id);
	// Traer los datos para hacer las validaciones.
	if ($sError==''){
		$sSQL='SELECT * FROM cara11tipocaract WHERE cara11id='.$cara11id.'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$filabase=$objDB->sf($tabla);
			}else{
			$sError='No se encuentra el registro solicitado {Ref: '.$cara11id.'}';
			}
		}
	if ($sError==''){
		if (!seg_revisa_permiso($iCodModulo, 4, $objDB)){
			$sError=$ERR['4'];
			}
		}
	if ($sError==''){
		$sSQL='SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=2311';
		$tablaor=$objDB->ejecutasql($sSQL);
		while ($filaor=$objDB->sf($tablaor)){
			$sSQL='SELECT '.$filaor['campoid'].' FROM '.$filaor['tabla'].' WHERE '.$filaor['camporev'].'='.$_REQUEST['cara11id'].' LIMIT 0, 1';
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
		$sWhere='cara11id='.$cara11id.'';
		//$sWhere='cara11consec='.$filabase['cara11consec'].'';
		$sSQL='DELETE FROM cara11tipocaract WHERE '.$sWhere.';';
		$result=$objDB->ejecutasql($sSQL);
		if ($result==false){
			$sError=$ERR['falla_eliminar'].' .. <!-- '.$sSQL.' -->';
			}else{
			if ($bAudita[4]){seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $cara11id, $sWhere, $objDB);}
			}
		}
	return array($sError, $iTipoError, $sDebug);
	}
function f2311_TituloBusqueda(){
	return 'Busqueda de Tipos de ficha';
	}
function f2311_ParametrosBusqueda(){
	$sParams='<label class="Label90">Nombre</label><label><input id="b2311nombre" name="b2311nombre" type="text" value="" onchange="paginarbusqueda()" /></label>';
	return $sParams;
	}
function f2311_JavaScriptBusqueda($iModuloBusca){
	$sRes='var sCampo=window.document.frmedita.scampobusca.value;
var params=new Array();
params[100]=sCampo;
params[101]=window.document.frmedita.paginabusqueda.value;
params[102]=window.document.frmedita.lppfbusqueda.value;
params[103]=window.document.frmedita.b2311nombre.value;
xajax_f'.$iModuloBusca.'_HtmlBusqueda(params);';
	return $sRes;
	}
function f2311_TablaDetalleBusquedas($aParametros, $objDB){
	$res='';
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2311='lg/lg_2311_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2311)){$mensajes_2311='lg/lg_2311_es.php';}
	require $mensajes_todas;
	require $mensajes_2311;
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	if (isset($aParametros[101])==0){$aParametros[101]=1;}
	if (isset($aParametros[102])==0){$aParametros[102]=20;}
	if (isset($aParametros[103])==0){$aParametros[103]='';}
	//$aParametros[103]=numeros_validar($aParametros[103]);
	$pagina=$aParametros[101];
	$lineastabla=$aParametros[102];
	$babierta=true;
	//$sSQL='SELECT Campo FROM Tabla WHERE Id='.$sValorId;
	//$tabla=$objDB->ejecutasql($sSQL);
	//if ($objDB->nf($tabla)>0){
		//$fila=$objDB->sf($tabla);
		//if ($fila['Campo']!='S'){$babierta=true;}
		//}
	$sLeyenda='';
	if (false){
		$sLeyenda='<div class="salto1px"></div>
<div class="GrupoCamposAyuda">
<b>Importante:</b> Mensaje al usuario
<div class="salto1px"></div>
</div>';
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
				$sSQLadd=$sSQLadd.' AND T6.sys11razonsocial LIKE "%'.$sCadena.'%"';
				//$sSQLadd1=$sSQLadd1.'T1.sys11razonsocial LIKE "%'.$sCadena.'%" AND ';
				}
			}
		}
	*/
	$sTitulos='Consec, Id, Nombre, Ficha1, Ficha1pregbas, Ficha1pregprof, Ficha2, Ficha2pregbas, Ficha2pregprof, Ficha3, Ficha3pregbas, Ficha3pregprof, Ficha4, Ficha4pregbas, Ficha4pregprof, Ficha5, Ficha5pregbas, Ficha5pregprof, Ficha6, Ficha6pregbas, Ficha6pregprof, Ficha7, Ficha7pregbas, Ficha7pregprof, Fichafamilia';
	$sSQL='SELECT TB.cara11consec, TB.cara11id, TB.cara11nombre, TB.cara11ficha1, TB.cara11ficha1pregbas, TB.cara11ficha1pregprof, TB.cara11ficha2, TB.cara11ficha2pregbas, TB.cara11ficha2pregprof, TB.cara11ficha3, TB.cara11ficha3pregbas, TB.cara11ficha3pregprof, TB.cara11ficha4, TB.cara11ficha4pregbas, TB.cara11ficha4pregprof, TB.cara11ficha5, TB.cara11ficha5pregbas, TB.cara11ficha5pregprof, TB.cara11ficha6, TB.cara11ficha6pregbas, TB.cara11ficha6pregprof, TB.cara11ficha7, TB.cara11ficha7pregbas, TB.cara11ficha7pregprof, TB.cara11fichafamilia 
FROM cara11tipocaract AS TB 
WHERE '.$sSQLadd1.'  '.$sSQLadd.'
ORDER BY TB.cara11consec';
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
			//return array(utf8_encode($sErrConsulta.'<input id="paginaf2311" name="paginaf2311" type="hidden" value="'.$pagina.'"/><input id="lppf2311" name="lppf2311" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
<td><b>'.$ETI['cara11consec'].'</b></td>
<td><b>'.$ETI['cara11nombre'].'</b></td>
<td><b>'.$ETI['cara11ficha1'].'</b></td>
<td><b>'.$ETI['cara11ficha1pregbas'].'</b></td>
<td><b>'.$ETI['cara11ficha1pregprof'].'</b></td>
<td><b>'.$ETI['cara11ficha2'].'</b></td>
<td><b>'.$ETI['cara11ficha2pregbas'].'</b></td>
<td><b>'.$ETI['cara11ficha2pregprof'].'</b></td>
<td><b>'.$ETI['cara11ficha3'].'</b></td>
<td><b>'.$ETI['cara11ficha3pregbas'].'</b></td>
<td><b>'.$ETI['cara11ficha3pregprof'].'</b></td>
<td><b>'.$ETI['cara11ficha4'].'</b></td>
<td><b>'.$ETI['cara11ficha4pregbas'].'</b></td>
<td><b>'.$ETI['cara11ficha4pregprof'].'</b></td>
<td><b>'.$ETI['cara11ficha5'].'</b></td>
<td><b>'.$ETI['cara11ficha5pregbas'].'</b></td>
<td><b>'.$ETI['cara11ficha5pregprof'].'</b></td>
<td><b>'.$ETI['cara11ficha6'].'</b></td>
<td><b>'.$ETI['cara11ficha6pregbas'].'</b></td>
<td><b>'.$ETI['cara11ficha6pregprof'].'</b></td>
<td><b>'.$ETI['cara11ficha7'].'</b></td>
<td><b>'.$ETI['cara11ficha7pregbas'].'</b></td>
<td><b>'.$ETI['cara11ficha7pregprof'].'</b></td>
<td align="right">
'.html_paginador('paginabusqueda', $registros, $lineastabla, $pagina, 'paginarbusqueda()').'
'.html_lpp('lppfbusqueda', $lineastabla, 'paginarbusqueda()').'
</td>
</tr>';
	$tlinea=1;
	while($filadet=$objDB->sf($tabladetalle)){
		$sPrefijo='<a href="javascript:Devuelve(\''.$filadet['cara11id'].'\');">';
		$sSufijo='</a>';
		$tlinea++;
		$res=$res.'<tr onmouseover="cambia_color_over(this);" onmouseout="cambia_color_out(this);">
<td>'.$sPrefijo.$filadet['cara11consec'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['cara11nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['cara11ficha1'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['cara11ficha1pregbas'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['cara11ficha1pregprof'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['cara11ficha2'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['cara11ficha2pregbas'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['cara11ficha2pregprof'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['cara11ficha3'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['cara11ficha3pregbas'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['cara11ficha3pregprof'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['cara11ficha4'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['cara11ficha4pregbas'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['cara11ficha4pregprof'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['cara11ficha5'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['cara11ficha5pregbas'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['cara11ficha5pregprof'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['cara11ficha6'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['cara11ficha6pregbas'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['cara11ficha6pregprof'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['cara11ficha7'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['cara11ficha7pregbas'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['cara11ficha7pregprof'].$sSufijo.'</td>
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