<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2021 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.25.10c sábado, 27 de febrero de 2021
--- 3083 saiu23inventario
*/
/** Archivo lib3083.php.
* Libreria 3083 saiu23inventario.
* @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
* @date sábado, 27 de febrero de 2021
*/
function f3083_HTMLComboV2_saiu23agno($objDB, $objCombos, $valor){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('saiu23agno', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	//$objCombos->iAncho=450;
	$objCombos->sAccion='RevisaLlave();';
	$sSQL='SELECT  AS id,  AS nombre FROM ';
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f3083_HTMLComboV2_saiu23tema($objDB, $objCombos, $valor, $vrsaiu23unidad){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('saiu23tema', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	//$objCombos->iAncho=450;
	$objCombos->sAccion='paginarf3083();';
	$sSQL='';
	if ((int)$vrsaiu23unidad!=0){
		$sSQL='SELECT saiu03id AS id, saiu03titulo AS nombre FROM saiu03temasol WHERE saiu03tiposol='.$vrsaiu23unidad.'';
		}
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f3083_Combosaiu23tema($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$objCombos=new clsHtmlCombos();
	$html_saiu23tema=f3083_HTMLComboV2_saiu23tema($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_saiu23tema', 'innerHTML', $html_saiu23tema);
	//$objResponse->call('$("#saiu23tema").chosen()');
	$objResponse->call('paginarf3083()');
	return $objResponse;
	}
function f3083_Busquedas($aParametros){
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3083='lg/lg_3083_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3083)){$mensajes_3083='lg/lg_3083_es.php';}
	require $mensajes_todas;
	require $mensajes_3083;
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
	$sTitulo='<h2>'.$ETI['titulo_3083'].' - '.$sTitulo.'</h2>';
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97titulo', 'innerHTML', $sTitulo);
	$objResponse->assign('div_97params', 'innerHTML', $sParams);
	$objResponse->assign('div_97tabla', 'innerHTML', $sTabla);
	$objResponse->setFunction('paginarbusqueda','',$sJavaBusqueda);
	$objResponse->call('expandesector(97)');
	return $objResponse;
	}
function f3083_HtmlBusqueda($aParametros){
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
function f3083_TablaDetalleV2($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3083='lg/lg_3083_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3083)){$mensajes_3083='lg/lg_3083_es.php';}
	require $mensajes_todas;
	require $mensajes_3083;
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	if (isset($aParametros[100])==0){$aParametros[100]=$_SESSION['unad_id_tercero'];}
	if (isset($aParametros[101])==0){$aParametros[101]=1;}
	if (isset($aParametros[102])==0){$aParametros[102]=20;}
	if (isset($aParametros[103])==0){$aParametros[103]='';}
	if (isset($aParametros[104])==0){$aParametros[104]='';}
	if (isset($aParametros[105])==0){$aParametros[105]='';}
	if (isset($aParametros[106])==0){$aParametros[106]='';}
	if (isset($aParametros[107])==0){$aParametros[107]='';}
	$idTercero=$aParametros[100];
	$sDebug='';
	$pagina=$aParametros[101];
	$lineastabla=$aParametros[102];
	$saiu23agno=$aParametros[103];
	$saiu23mes=$aParametros[104];
	$saiu23unidad=$aParametros[105];
	$saiu23tema=$aParametros[106];
	$saiu23documento=$aParametros[107];
	$bAbierta=true;
	//$sSQL='SELECT Campo FROM Tabla WHERE Id='.$sValorId;
	//$tabla=$objDB->ejecutasql($sSQL);
	//if ($objDB->nf($tabla)>0){
		//$fila=$objDB->sf($tabla);
		//if ($fila['Campo']!='S'){$bAbierta=true;}
		//}
	$sLeyenda='';
	$sBotones='<input id="paginaf3083" name="paginaf3083" type="hidden" value="'.$pagina.'"/>
	<input id="lppf3083" name="lppf3083" type="hidden" value="'.$lineastabla.'"/>';
	if ((int)$saiu23agno==0){$sLeyenda='Debe seleccionar un a&ntilde;o.';}
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
	//if ($saiu23agno!=''){$sSQLadd1=$sSQLadd1.'TB.saiu23agno='.$saiu23agno.' AND ';}
	//if ($saiu23mes!=''){$sSQLadd1=$sSQLadd1.'TB.saiu23mes='.$saiu23mes.' AND ';}
	//if ($saiu23unidad!=''){$sSQLadd1=$sSQLadd1.'TB.saiu23unidad='.$saiu23unidad.' AND ';}
	//if ($saiu23tema!=''){$sSQLadd1=$sSQLadd1.'TB.saiu23tema='.$saiu23tema.' AND ';}
	//if ($saiu23documento!=''){$sSQLadd1=$sSQLadd1.'TB.saiu23documento='.$saiu23documento.' AND ';}
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
	$sTitulos='Agno, Mes, Unidad, Tema, Documento';
	$registros=0;
	$bGigante=false; //En caso de que la tabla sea muy grande pasarlo a true
	$sLimite='';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$res=$sErrConsulta.$sLeyenda.$sBotones;
	$res=$res.'<div class="table-responsive">
	<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">';
	/*
	<thead class="fondoazul"><tr>
	<td><b>'.$ETI['saiu23agno'].'</b></td>
	<td><b>'.$ETI['saiu23mes'].'</b></td>
	<td><b>'.$ETI['saiu23unidad'].'</b></td>
	<td><b>'.$ETI['saiu23tema'].'</b></td>
	<td><b>'.$ETI['saiu23documento'].'</b></td>
	<td align="right">
	'.html_paginador('paginaf3083', $registros, $lineastabla, $pagina, 'paginarf3083()').'
	'.html_lpp('lppf3083', $lineastabla, 'paginarf3083()').'
	</td>
	</tr></thead>
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
		if ($bAbierta){
			$sLink='<a href="javascript:cargadato('."'".$filadet['saiu23agno']."'".')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$res=$res.'<tr'.$sClass.'>
		<td>'.$sPrefijo.$filadet['saiu23agno'].$sSufijo.'</td>
		<td>'.$sPrefijo.$filadet['saiu23mes'].$sSufijo.'</td>
		<td>'.$sPrefijo.cadena_notildes($filadet['unae26nombre']).$sSufijo.'</td>
		<td>'.$sPrefijo.cadena_notildes($filadet['saiu03titulo']).$sSufijo.'</td>
		<td>'.$sPrefijo.cadena_notildes($filadet['saiu23documento']).$sSufijo.'</td>
		<td>'.$sLink.'</td>
		</tr>';
		}
	 */
	$res=$res.'</table>
	<div class="salto5px"></div>
	</div>';
	$objDB->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
	}
function f3083_HtmlTabla($aParametros){
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
	list($sDetalle, $sDebugTabla)=f3083_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f3083detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
?>