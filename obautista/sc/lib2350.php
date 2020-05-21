<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2018 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.21.0 jueves, 21 de junio de 2018
--- 2350 core50consolidado
*/
/** Archivo lib2350.php.
* Libreria 2350 core50consolidado.
* @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
* @date Wednesday, June 21, 2018
 *
 * Cambios 21 de mayo de 2020
 * 1. Adición de función f2350_HTMLComboV2_core50idprograma
 * 2. Adición de función f2350_Combocore50idprograma
 * Omar Augusto Bautista Mora - UNAD - 2020
 * omar.bautista@unad.edu.co
*/
function f2350_HTMLComboV2_core50idperaca($objDB, $objCombos, $valor){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('core50idperaca', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	$objCombos->sAccion='RevisaLlave();';
	//Solo los peracas donde haya encuestas.
	$sIds='-99';
	$sSQL='SELECT cara01idperaca FROM cara01encuesta GROUP BY cara01idperaca';
	$tabla=$objDB->ejecutasql($sSQL);
	while($fila=$objDB->sf($tabla)){
		$sIds=$sIds.','.$fila['cara01idperaca'];
		}
	$sSQL='SELECT exte02id AS id, CONCAT(CASE exte02vigente WHEN "S" THEN "" ELSE "[" END, exte02nombre," {",exte02id,"} ",CASE exte02vigente WHEN "S" THEN "" ELSE " - INACTIVO]" END) AS nombre FROM exte02per_aca WHERE exte02id IN ('.$sIds.') ORDER BY exte02vigente DESC, exte02id DESC';
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f2350_HTMLComboV2_core50idzona($objDB, $objCombos, $valor){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('core50idzona', $valor, true, '{'.$ETI['msg_todas'].'}');
	$objCombos->sAccion='carga_combo_core50idcentro()';
	$res=$objCombos->html('SELECT unad23id AS id, unad23nombre AS nombre FROM unad23zona', $objDB);
	return $res;
	}
function f2350_HTMLComboV2_core50idcentro($objDB, $objCombos, $valor, $vrcore50idzona){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	//@@ Se debe arreglar la condicion..
	$sCondi='unad24idzona="'.$vrcore50idzona.'"';
	if ($sCondi!=''){$sCondi=' WHERE '.$sCondi.' AND unad24activa="S"';}
	$objCombos->nuevo('core50idcentro', $valor, true, '{'.$ETI['msg_todos'].'}');
	$res=$objCombos->html('SELECT unad24id AS id, unad24nombre AS nombre FROM unad24sede'.$sCondi, $objDB);
	return $res;
	}
function f2350_Combocore50idcentro($params){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$objCombos=new clsHtmlCombos('n');
	$html_core50idcentro=f2350_HTMLComboV2_core50idcentro($objDB, $objCombos, '', $params[0]);
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_core50idcentro', 'innerHTML', $html_core50idcentro);
	return $objResponse;
	}
function f2350_Busquedas($params){
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2350='lg/lg_2350_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2350)){$mensajes_2350='lg/lg_2350_es.php';}
	require $mensajes_todas;
	require $mensajes_2350;
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
	$sTitulo='<h2>'.$ETI['titulo_2350'].' - '.$sTitulo.'</h2>';
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97titulo', 'innerHTML', $sTitulo);
	$objResponse->assign('div_97params', 'innerHTML', $sParams);
	$objResponse->assign('div_97tabla', 'innerHTML', $sTabla);
	$objResponse->setFunction('paginarbusqueda','',$sJavaBusqueda);
	$objResponse->call('expandesector(97)');
	return $objResponse;
	}
function f2350_HtmlBusqueda($params){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	$sError='';
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$sDetalle='';
	switch($params[100]){
		}
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97tabla', 'innerHTML', $sDetalle);
	return $objResponse;
	}
function f2350_TablaDetalleV2($params, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2350='lg/lg_2350_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2350)){$mensajes_2350='lg/lg_2350_es.php';}
	require $mensajes_todas;
	require $mensajes_2350;
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	if (isset($params[101])==0){$params[101]=1;}
	if (isset($params[102])==0){$params[102]=20;}
	//if (isset($params[103])==0){$params[103]='';}
	//$params[103]=numeros_validar($params[103]);
	$sDebug='';
	$pagina=$params[101];
	$lineastabla=$params[102];
	$babierta=true;
	//$sql='SELECT Campo FROM Tabla WHERE Id='.$sValorId;
	//$tabla=$objDB->ejecutasql($sql);
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
	$sqladd='';
	$sqladd1='';
	//if ((int)$params[103]!=-1){$sqladd=$sqladd.' AND TB.campo='.$params[103];}
	//if ($params[103]!=''){$sqladd=$sqladd.' AND TB.campo2 LIKE "%'.$params[103].'%"';}
	/*
	if ($params[104]!=''){
		$sBase=trim(strtoupper($params[104]));
		$aNoms=explode(' ', $sBase);
		for ($k=1;$k<=count($aNoms);$k++){
			$sCadena=$aNoms[$k-1];
			if ($sCadena!=''){
				$sqladd=$sqladd.' AND T6.sys11razonsocial LIKE "%'.$sCadena.'%"';
				//$sqladd1=$sqladd1.'T1.sys11razonsocial LIKE "%'.$sCadena.'%" AND ';
				}
			}
		}
	*/
	$sTitulos='Peraca, Zona, Centro';
	$sql='SELECT T1.exte02nombre, T2.unad23nombre, T3.unad24nombre, TB.core50idperaca, TB.core50idzona, TB.core50idcentro 
FROM core50consolidado AS TB, exte02per_aca AS T1, unad23zona AS T2, unad24sede AS T3 
WHERE '.$sqladd1.' TB.core50idperaca=T1.exte02id AND TB.core50idzona=T2.unad23id AND TB.core50idcentro=T3.unad24id '.$sqladd.'
ORDER BY TB.core50idperaca';
	$sqllista=str_replace("'","|",$sql);
	$sqllista=str_replace('"',"|",$sqllista);
	$sErrConsulta='<input id="consulta_2350" name="consulta_2350" type="hidden" value="'.$sqllista.'"/>
<input id="titulos_2350" name="titulos_2350" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objDB->ejecutasql($sql);
	if ($tabladetalle==false){
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 2350: '.$sql.'<br>';}
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sql.' '.$objDB->serror.'"/>';
		//$sLeyenda=$sql;
		}else{
		$registros=$objDB->nf($tabladetalle);
		if ($registros==0){
			//return array(utf8_encode($sErrConsulta.'<input id="paginaf2350" name="paginaf2350" type="hidden" value="'.$pagina.'"/><input id="lppf2350" name="lppf2350" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
			}
		if ((($registros-1)/$lineastabla)<($pagina-1)){$pagina=(int)(($registros-1)/$lineastabla)+1;}
		if ($registros>$lineastabla){
			$rbase=($pagina-1)*$lineastabla;
			$limite=' LIMIT '.$rbase.', '.$lineastabla;
			$tabladetalle=$objDB->ejecutasql($sql.$limite);
			}
		}
	$res=$sErrConsulta.$sLeyenda.'<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">
<tr class="fondoazul">
<td><b>'.$ETI['core50idperaca'].'</b></td>
<td><b>'.$ETI['core50idzona'].'</b></td>
<td><b>'.$ETI['core50idcentro'].'</b></td>
<td align="right">
'.html_paginador('paginaf2350', $registros, $lineastabla, $pagina, 'paginarf2350()').'
'.html_lpp('lppf2350', $lineastabla, 'paginarf2350()').'
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
		if ($babierta){
			$sLink='<a href="javascript:cargadato('."'".$filadet['core50idperaca']."'".')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$res=$res.'<tr'.$sClass.'>
<td>'.$sPrefijo.cadena_notildes($filadet['exte02nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['unad23nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['unad24nombre']).$sSufijo.'</td>
<td>'.$sLink.'</td>
</tr>';
		}
	$res=$res.'</table>';
	$objDB->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
	}
function f2350_HtmlTabla($params){
	$_SESSION['u_ultimominuto']=iminutoavance();
	$sError='';
	$bDebug=false;
	$sDebug='';
	$opts=$params;
	if(!is_array($opts)){$opts=json_decode(str_replace('\"','"',$opts),true);}
	if (isset($opts[99])!=0){if ($opts[99]==1){$bDebug=true;}}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	list($sDetalle, $sDebugTabla)=f2350_TablaDetalleV2($params, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f2350detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f2350_HTMLComboV2_core50idprograma($objDB, $objCombos, $valor, $vrcore50idescuela){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	//@@ Se debe arreglar la condicion..
	$sCondi='core09idescuela="'.$vrcore50idescuela.'"';
	if ($sCondi!=''){$sCondi=' WHERE '.$sCondi;}
	$objCombos->nuevo('core50idprograma', $valor, true, '{'.$ETI['msg_todos'].'}');
	$sSQL='SELECT core09id AS id, core09nombre AS nombre FROM core09programa'.$sCondi;
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f2350_Combocore50idprograma($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$objCombos=new clsHtmlCombos('n');
	$html_core50idprograma=f2350_HTMLComboV2_core50idprograma($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_core50idprograma', 'innerHTML', $html_core50idprograma);
	return $objResponse;
	}
?>
