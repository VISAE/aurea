<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2016 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.15.8 lunes, 12 de septiembre de 2016
--- 140 unad40curso
*/
function f140_Busquedas($params){
	require './app.php';
	$objdb=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objdb->dbPuerto=$APP->dbpuerto;}
	$objdb->xajax();
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_140=$APP->rutacomun.'lg/lg_140_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_140)){$mensajes_140=$APP->rutacomun.'lg/lg_140_es.php';}
	require $mensajes_todas;
	require $mensajes_140;
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	$sCampo=$params[1];
	$sTitulo=' {'.$sCampo.'}';
	if (isset($params[2])==0){$params[2]=0;}
	if (isset($params[3])==0){$params[3]=0;}
	$sParams='';
	$sTabla='';
	$sJavaBusqueda='';
	$paramsb=array();
	$paramsb[101]=1;
	$paramsb[102]=20;
	switch($sCampo){
		}
	$sTitulo='<h2>'.$ETI['titulo_140'].' - '.$sTitulo.'</h2>';
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97titulo', 'innerHTML', $sTitulo);
	$objResponse->assign('div_97params', 'innerHTML', $sParams);
	$objResponse->assign('div_97tabla', 'innerHTML', $sTabla);
	$objResponse->setFunction('paginarbusqueda','',$sJavaBusqueda);
	$objResponse->call('expandesector(97)');
	return $objResponse;
	}
function f140_HtmlBusqueda($params){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	$sError='';
	require './app.php';
	$objdb=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objdb->dbPuerto=$APP->dbpuerto;}
	$objdb->xajax();
	$sDetalle='';
	switch($params[100]){
		}
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97tabla', 'innerHTML', $sDetalle);
	return $objResponse;
	}
function f140_TituloBusqueda(){
	return 'Busqueda de Cursos';
	}
function f140_ParametrosBusqueda(){
	$sParams='<label class="Label90">Codigo</label><label><input id="b140codigo" name="b140codigo" type="text" value="" onchange="paginarbusqueda()" /></label>
<label class="Label90">Nombre</label><label><input id="b140nombre" name="b140nombre" type="text" value="" onchange="paginarbusqueda()" /></label>';
	return $sParams;
	}
function f140_JavaScriptBusqueda($iModuloBusca){
	$sRes='var sCampo=window.document.frmedita.scampobusca.value;
var params=new Array();
params[100]=sCampo;
params[101]=window.document.frmedita.paginabusqueda.value;
params[102]=window.document.frmedita.lppfbusqueda.value;
params[103]=window.document.frmedita.b140nombre.value;
params[104]=window.document.frmedita.b140codigo.value;
xajax_f'.$iModuloBusca.'_HtmlBusqueda(params);';
	return $sRes;
	}
function f140_TablaDetalleBusquedas($params, $objdb){
	$res='';
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_140=$APP->rutacomun.'lg/lg_140_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_140)){$mensajes_140=$APP->rutacomun.'lg/lg_140_es.php';}
	require $mensajes_todas;
	require $mensajes_140;
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	if (isset($params[101])==0){$params[101]=1;}
	if (isset($params[102])==0){$params[102]=20;}
	if (isset($params[103])==0){$params[103]='';}
	if (isset($params[104])==0){$params[104]='';}
	$params[104]=numeros_validar($params[104]);
	$pagina=$params[101];
	$lineastabla=$params[102];
	$babierta=true;
	//$sql='SELECT Campo FROM Tabla WHERE Id='.$sValorId;
	//$tabla=$objdb->ejecutasql($sql);
	//if ($objdb->nf($tabla)>0){
		//$fila=$objdb->sf($tabla);
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
	$sqladd='';
	//if ((int)$params[103]!=-1){$sqladd=$sqladd.' AND TB.campo='.$params[103];}
	if ($params[103]!=''){$sqladd=$sqladd.' AND TB.unad40nombre LIKE "%'.$params[103].'%"';}
	if ($params[104]!=''){$sqladd=$sqladd.' AND TB.unad40consec LIKE "%'.$params[104].'%"';}
	$sTitulos='Consec, Nombre';
	$sql='SELECT TB.unad40consec, TB.unad40nombre, TB.unad40id, TB.unad40incluyelaboratorio, TB.unad40incluyesalida
FROM unad40curso AS TB 
WHERE TB.unad40id>0 '.$sqladd.'
ORDER BY TB.unad40nombre';
	$sqllista=str_replace("'","|",$sql);
	$sqllista=str_replace('"',"|",$sqllista);
	$sErrConsulta='<input id="consulta_busqueda" name="consulta_busqueda" type="hidden" value="'.$sqllista.'"/>
<input id="titulos_busqueda" name="titulos_busqueda" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objdb->ejecutasql($sql);
	if ($tabladetalle==false){
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sql.' '.$objdb->serror.'"/>';
		//$sLeyenda=$sql;
		}else{
		$registros=$objdb->nf($tabladetalle);
		if ($registros==0){
			//return array(utf8_encode($sErrConsulta.'<input id="paginaf140" name="paginaf140" type="hidden" value="'.$pagina.'"/><input id="lppf140" name="lppf140" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
			}
		if ((($registros-1)/$lineastabla)<($pagina-1)){$pagina=(int)(($registros-1)/$lineastabla)+1;}
		if ($registros>$lineastabla){
			$rbase=($pagina-1)*$lineastabla;
			$limite=' LIMIT '.$rbase.', '.$lineastabla;
			$tabladetalle=$objdb->ejecutasql($sql.$limite);
			}
		}
	$res=$sErrConsulta.$sLeyenda.'<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">
<tr class="fondoazul">
<td><b>'.$ETI['unad40consec'].'</b></td>
<td><b>'.$ETI['unad40nombre'].'</b></td>
<td><b>'.$ETI['unad40incluyelaboratorio'].'</b></td>
<td><b>'.$ETI['unad40incluyesalida'].'</b></td>
<td align="right">
'.html_paginador('paginabusqueda', $registros, $lineastabla, $pagina, 'paginarbusqueda()').'
'.html_lpp('lppfbusqueda', $lineastabla, 'paginarbusqueda()').'
</td>
</tr>';
	$tlinea=1;
	while($filadet=$objdb->sf($tabladetalle)){
		$sPrefijo='<a href="javascript:Devuelve(\''.$filadet['unad40id'].'\');">';
		$sSufijo='</a>';
		$tlinea++;
		$et_unad40incluyelaboratorio=$ETI['no'];
		if ($filadet['unad40incluyelaboratorio']=='S'){$et_unad40incluyelaboratorio=$ETI['si'];}
		$et_unad40incluyesalida=$ETI['no'];
		if ($filadet['unad40incluyesalida']=='S'){$et_unad40incluyesalida=$ETI['si'];}
		$res=$res.'<tr onmouseover="cambia_color_over(this);" onmouseout="cambia_color_out(this);">
<td>'.$sPrefijo.$filadet['unad40consec'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['unad40nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.$et_unad40incluyelaboratorio.$sSufijo.'</td>
<td>'.$sPrefijo.$et_unad40incluyesalida.$sSufijo.'</td>
<td></td>
</tr>';
		}
	$res=$res.'</table>';
	return utf8_encode($res);
	}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------
?>