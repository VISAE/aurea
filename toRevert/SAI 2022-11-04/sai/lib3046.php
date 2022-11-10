<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2021 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.25.10c lunes, 12 de abril de 2021
--- 3046 corf06novedad
*/
/** Archivo lib3046.php.
* Libreria 3046 corf06novedad.
* @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
* @date lunes, 12 de abril de 2021
*/
function f3046_HTMLComboV2_corf06idprograma($objDB, $objCombos, $valor, $vrcorf06idescuela){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('corf06idprograma', $valor, true, '{'.$ETI['msg_todos'].'}');
	//$objCombos->iAncho=450;
	$objCombos->sAccion='paginarf3046();';
	$sSQL='';
	if ((int)$vrcorf06idescuela!=0){
		$sSQL='SELECT core09id AS id, core09nombre AS nombre FROM core09programa WHERE core09idescuela='.$vrcorf06idescuela.'';
		}
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f3046_HTMLComboV2_corf06idzona($objDB, $objCombos, $valor){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('corf06idzona', $valor, true, '{'.$ETI['msg_todas'].'}');
	//$objCombos->iAncho=450;
	$objCombos->sAccion='carga_combo_corf06idcentro()';
	$sSQL='SELECT unad23id AS id, unad23nombre AS nombre FROM unad23zona';
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f3046_HTMLComboV2_corf06idcentro($objDB, $objCombos, $valor, $vrcorf06idzona){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('corf06idcentro', $valor, true, '{'.$ETI['msg_todos'].'}');
	//$objCombos->iAncho=450;
	$objCombos->sAccion='paginarf3046();';
	$sSQL='';
	if ((int)$vrcorf06idzona!=0){
		$sSQL='SELECT unad24id AS id, unad24nombre AS nombre FROM unad24sede WHERE unad24idzona='.$vrcorf06idzona.'';
		}
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f3046_Combocorf06idprograma($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$objCombos=new clsHtmlCombos();
	$html_corf06idprograma=f3046_HTMLComboV2_corf06idprograma($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_corf06idprograma', 'innerHTML', $html_corf06idprograma);
	$objResponse->call('$("#corf06idprograma").chosen()');
	$objResponse->call('paginarf3046()');
	return $objResponse;
	}
function f3046_Combocorf06idcentro($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$objCombos=new clsHtmlCombos();
	$html_corf06idcentro=f3046_HTMLComboV2_corf06idcentro($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_corf06idcentro', 'innerHTML', $html_corf06idcentro);
	//$objResponse->call('$("#corf06idcentro").chosen()');
	$objResponse->call('paginarf3046()');
	return $objResponse;
	}
function f3046_Busquedas($aParametros){
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3046='lg/lg_3046_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3046)){$mensajes_3046='lg/lg_3046_es.php';}
	require $mensajes_todas;
	require $mensajes_3046;
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
	$sTitulo='<h2>'.$ETI['titulo_3046'].' - '.$sTitulo.'</h2>';
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97titulo', 'innerHTML', $sTitulo);
	$objResponse->assign('div_97params', 'innerHTML', $sParams);
	$objResponse->assign('div_97tabla', 'innerHTML', $sTabla);
	$objResponse->setFunction('paginarbusqueda','',$sJavaBusqueda);
	$objResponse->call('expandesector(97)');
	return $objResponse;
	}
function f3046_HtmlBusqueda($aParametros){
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
function f3046_TablaDetalleV2($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3046='lg/lg_3046_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3046)){$mensajes_3046='lg/lg_3046_es.php';}
	$mensajes_12206=$APP->rutacomun.'lg/lg_12206_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_12206)){$mensajes_12206=$APP->rutacomun.'lg/lg_12206_es.php';}
	require $mensajes_todas;
	require $mensajes_12206;
	require $mensajes_3046;
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	if (isset($aParametros[100])==0){$aParametros[100]=$_SESSION['unad_id_tercero'];}
	if (isset($aParametros[101])==0){$aParametros[101]=1;}
	if (isset($aParametros[102])==0){$aParametros[102]=20;}
	if (isset($aParametros[103])==0){$aParametros[103]='';}
	if (isset($aParametros[104])==0){$aParametros[104]='';}
	if (isset($aParametros[105])==0){$aParametros[105]='';}
	if (isset($aParametros[106])==0){$aParametros[106]='';}
	if (isset($aParametros[107])==0){$aParametros[107]='';}
	if (isset($aParametros[108])==0){$aParametros[108]='';}
	if (isset($aParametros[109])==0){$aParametros[109]='';}
	if (isset($aParametros[110])==0){$aParametros[110]='';}
	$idTercero=$aParametros[100];
	$sDebug='';
	$pagina=$aParametros[101];
	$lineastabla=$aParametros[102];
	$corf06tiponov=$aParametros[103];
	$corf06estado=$aParametros[104];
	$corf06idescuela=$aParametros[105];
	$corf06idprograma=$aParametros[106];
	$corf06idzona=$aParametros[107];
	$corf06idcentro=$aParametros[108];
	$corf06fecha=$aParametros[109];
	$corf06fechafin=$aParametros[110];
	$bAbierta=true;
	//$sSQL='SELECT Campo FROM Tabla WHERE Id='.$sValorId;
	//$tabla=$objDB->ejecutasql($sSQL);
	//if ($objDB->nf($tabla)>0){
		//$fila=$objDB->sf($tabla);
		//if ($fila['Campo']!='S'){$bAbierta=true;}
		//}
	$sLeyenda='';
	$sBotones='<input id="paginaf3046" name="paginaf3046" type="hidden" value="'.$pagina.'"/>
	<input id="lppf3046" name="lppf3046" type="hidden" value="'.$lineastabla.'"/>';
	if ($sLeyenda!=''){
		$sLeyenda='<div class="salto1px"></div>
		<div class="GrupoCamposAyuda">
		'.$sLeyenda.'
		<div class="salto1px"></div>
		</div>';
		return array($sLeyenda.$sBotones, $sDebug);
		die();
		}
	$aEstado=array();
	$sSQL='SELECT id, nombre FROM tabla';
	$tabla=$objDB->ejecutasql($sSQL);
	while($fila=$objDB->sf($tabla)){
		$aEstado[$fila['id']]=cadena_notildes($fila['nombre']);
		}
	$sSQLadd='';
	$sSQLadd1='';
	if ($corf06tiponov!=''){$sSQLadd1=$sSQLadd1.'TB.corf06tiponov='.$corf06tiponov.' AND ';}
	if ($corf06estado!=''){$sSQLadd1=$sSQLadd1.'TB.corf06estado='.$corf06estado.' AND ';}
	if ($corf06idprograma!=''){
		$sSQLadd1=$sSQLadd1.'TB.corf06idprograma='.$corf06idprograma.' AND ';
		}else{
		if ($corf06idescuela!=''){$sSQLadd1=$sSQLadd1.'TB.corf06idescuela='.$corf06idescuela.' AND ';}
		}
	if ($corf06idcentro!=''){
		$sSQLadd1=$sSQLadd1.'TB.corf06idcentro='.$corf06idcentro.' AND ';
		}else{
		if ($corf06idzona!=''){$sSQLadd1=$sSQLadd1.'TB.corf06idzona='.$corf06idzona.' AND ';}
		}
	if ((int)$corf06fecha!=0){$sSQLadd1=$sSQLadd1.'TB.corf06fecha>='.$corf06fecha.' AND ';}
	if ((int)$corf06fechafin!=0){$sSQLadd1=$sSQLadd1.'TB.corf06fecha<='.$corf06fechafin.' AND ';}
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
	$sTitulos='Tiponov, Estado, Escuela, Programa, Zona, Centro, Fecha, Fechafin';
	$registros=0;
	$bGigante=false; //En caso de que la tabla sea muy grande pasarlo a true
	$sLimite='';
	if ($bGigante){
		$sSQL='SELECT COUNT(1) AS Total 
		FROM corf06novedad AS TB, corf09novedadtipo AS T1, core12escuela AS T3, core09programa AS T4, unad23zona AS T5, unad24sede AS T6 
		WHERE '.$sSQLadd1.' TB.corf06tiponov=T1.corf09id AND TB.corf06idescuela=T3.core12id AND TB.corf06idprograma=T4.core09id AND TB.corf06idzona=T5.unad23id AND TB.corf06idcentro=T6.unad24id '.$sSQLadd.'';
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
	$sSQL='SELECT TB.corf06idescuela, T3.core12sigla, TB.corf06tiponov, T1.corf09nombre, TB.corf06estado, COUNT(1) AS Total 
	FROM corf06novedad AS TB, corf09novedadtipo AS T1, core12escuela AS T3 
	WHERE '.$sSQLadd1.' TB.corf06tiponov=T1.corf09id AND TB.corf06idescuela=T3.core12id '.$sSQLadd.'
	GROUP BY TB.corf06idescuela, T3.core12sigla, TB.corf06tiponov, T1.corf09nombre, TB.corf06estado
	ORDER BY T3.core12sigla, TB.corf06tiponov, TB.corf06estado';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_3046" name="consulta_3046" type="hidden" value="'.$sSQLlista.'"/>
	<input id="titulos_3046" name="titulos_3046" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objDB->ejecutasql($sSQL.$sLimite);
	if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 3046: '.$sSQL.$sLimite.'<br>';}
	if ($tabladetalle==false){
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		//$sLeyenda=$sSQL;
		}else{
		if (!$bGigante){
			$registros=$objDB->nf($tabladetalle);
			if ($registros==0){
				//return array(utf8_encode($sErrConsulta.'<input id="paginaf3046" name="paginaf3046" type="hidden" value="'.$pagina.'"/><input id="lppf3046" name="lppf3046" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
	<td><b>'.$ETI['corf06idescuela'].'</b></td>
	<td><b>'.$ETI['corf06tiponov'].'</b></td>
	<td><b>'.$ETI['corf06estado'].'</b></td>
	<td><b>'.$ETI['msg_total'].'</b></td>
	<td align="right">
	'.html_paginador('paginaf3046', $registros, $lineastabla, $pagina, 'paginarf3046()').'
	'.html_lpp('lppf3046', $lineastabla, 'paginarf3046()').'
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
		//$et_corf06fecha='';
		//if ($filadet['corf06fecha']!=0){$et_corf06fecha=fecha_desdenumero($filadet['corf06fecha']);}
		if ($bAbierta){
			//$sLink='<a href="javascript:cargadato('."'".''."'".')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$sTotal=formato_numero($filadet['Total']);
		$res=$res.'<tr'.$sClass.'>
		<td>'.$sPrefijo.cadena_notildes($filadet['core12sigla']).$sSufijo.'</td>
		<td>'.$sPrefijo.cadena_notildes($filadet['corf09nombre']).$sSufijo.'</td>
		<td>'.$sPrefijo.$acorf06estado[$filadet['corf06estado']].$sSufijo.'</td>
		<td aling="center">'.$sTotal.'</td>
		<td></td>
		</tr>';
		}
	$res=$res.'</table>
	<div class="salto5px"></div>
	</div>';
	$objDB->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
	}
function f3046_HtmlTabla($aParametros){
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
	list($sDetalle, $sDebugTabla)=f3046_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f3046detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
?>