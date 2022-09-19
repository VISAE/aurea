<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2021 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.25.10c domingo, 7 de marzo de 2021
--- 3045 saiu41docente
*/
/** Archivo lib3045.php.
* Libreria 3045 saiu41docente.
* @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
* @date domingo, 7 de marzo de 2021
*/
function f3045_HTMLComboV2_saiu41idprograma($objDB, $objCombos, $valor, $vrsaiu41idescuela){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('saiu41idprograma', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	//$objCombos->iAncho=450;
	$objCombos->sAccion='paginarf3045();';
	$sSQL='';
	if ((int)$vrsaiu41idescuela!=0){
		$sSQL='SELECT core09id AS id, core09nombre AS nombre FROM core09programa WHERE core09idescuela='.$vrsaiu41idescuela.'';
		}
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f3045_HTMLComboV2_saiu41idcurso($objDB, $objCombos, $valor, $vrsaiu41idperiodo){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('saiu41idcurso', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	//$objCombos->iAncho=450;
	$objCombos->sAccion='paginarf3045();';
	$sSQL='';
	if ((int)$vrsaiu41idperiodo!=0){
		$sSQL='SELECT T2.unad40id AS id, CONCAT(T2.unad40titulo, " - ", T2.unad40nombre) AS nombre 
		FROM ofer08oferta AS TB, unad40curso AS T2 
		WHERE TB.ofer08idper_aca='.$vrsaiu41idperiodo.' AND TB.ofer08cead=0 AND TB.ofer08idcurso=T2.unad40id
		ORDER BY T2.unad40titulo';
		}
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f3045_HTMLComboV2_saiu41idcead($objDB, $objCombos, $valor, $vrsaiu41idzona){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('saiu41idcead', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	//$objCombos->iAncho=450;
	$objCombos->sAccion='paginarf3045();';
	$sSQL='';
	if ((int)$vrsaiu41idzona!=0){
		$sSQL='SELECT unad24id AS id, unad24nombre AS nombre FROM unad24sede WHERE unad24idzona='.$vrsaiu41idzona.'';
		}
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f3045_Combosaiu41idprograma($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$objCombos=new clsHtmlCombos();
	$html_saiu41idprograma=f3045_HTMLComboV2_saiu41idprograma($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_saiu41idprograma', 'innerHTML', $html_saiu41idprograma);
	$objResponse->call('$("#saiu41idprograma").chosen()');
	$objResponse->call('paginarf3045()');
	return $objResponse;
	}
function f3045_Combosaiu41idcurso($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$objCombos=new clsHtmlCombos();
	$html_saiu41idcurso=f3045_HTMLComboV2_saiu41idcurso($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_saiu41idcurso', 'innerHTML', $html_saiu41idcurso);
	$objResponse->call('$("#saiu41idcurso").chosen()');
	$objResponse->call('paginarf3045()');
	return $objResponse;
	}
function f3045_Combosaiu41idcead($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$objCombos=new clsHtmlCombos();
	$html_saiu41idcead=f3045_HTMLComboV2_saiu41idcead($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_saiu41idcead', 'innerHTML', $html_saiu41idcead);
	//$objResponse->call('$("#saiu41idcead").chosen()');
	$objResponse->call('paginarf3045()');
	return $objResponse;
	}
function f3045_Busquedas($aParametros){
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3045='lg/lg_3045_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3045)){$mensajes_3045='lg/lg_3045_es.php';}
	require $mensajes_todas;
	require $mensajes_3045;
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
	$sTitulo='<h2>'.$ETI['titulo_3045'].' - '.$sTitulo.'</h2>';
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97titulo', 'innerHTML', $sTitulo);
	$objResponse->assign('div_97params', 'innerHTML', $sParams);
	$objResponse->assign('div_97tabla', 'innerHTML', $sTabla);
	$objResponse->setFunction('paginarbusqueda','',$sJavaBusqueda);
	$objResponse->call('expandesector(97)');
	return $objResponse;
	}
function f3045_HtmlBusqueda($aParametros){
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
function f3045_TablaDetalleV2($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3045='lg/lg_3045_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3045)){$mensajes_3045='lg/lg_3045_es.php';}
	require $mensajes_todas;
	require $mensajes_3045;
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
	if (isset($aParametros[111])==0){$aParametros[111]='';}
	$idTercero=$aParametros[100];
	$sDebug='';
	$pagina=$aParametros[101];
	$lineastabla=$aParametros[102];
	$saiu41idperiodo=$aParametros[103];
	$saiu41idescuela=$aParametros[104];
	$saiu41idprograma=$aParametros[105];
	$saiu41idcurso=$aParametros[106];
	$saiu41idzona=$aParametros[107];
	$saiu41idcead=$aParametros[108];
	$saiu41tipocontacto=$aParametros[109];
	$saiu41fecha=$aParametros[110];
	$saiu41fechafin=$aParametros[111];
	$bAbierta=true;
	//$sSQL='SELECT Campo FROM Tabla WHERE Id='.$sValorId;
	//$tabla=$objDB->ejecutasql($sSQL);
	//if ($objDB->nf($tabla)>0){
		//$fila=$objDB->sf($tabla);
		//if ($fila['Campo']!='S'){$bAbierta=true;}
		//}
	$sLeyenda='';
	if ((int)$saiu41idperiodo==0){
		if ((int)$saiu41fecha==0){
			if ((int)$saiu41fechafin==0){
				$sLeyenda='Debe seleccionar o un periodo acad&eacute;mico o una fecha';
				}
			}
		}
	$sBotones='<input id="paginaf3045" name="paginaf3045" type="hidden" value="'.$pagina.'"/>
	<input id="lppf3045" name="lppf3045" type="hidden" value="'.$lineastabla.'"/>';
	if ($sLeyenda!=''){
		$sLeyenda='<div class="salto1px"></div>
		<div class="GrupoCamposAyuda">
		'.$sLeyenda.'
		<div class="salto1px"></div>
		</div>';
		return array($sLeyenda.$sBotones, $sDebug);
		die();
		}
	$aTipo=array();
	$sSQL='SELECT ceca26id, ceca26nombre FROM ceca26tipoacompana';
	$tabla=$objDB->ejecutasql($sSQL);
	while($fila=$objDB->sf($tabla)){
		$aTipo[$fila['ceca26id']]['ab']=0;
		$aTipo[$fila['ceca26id']]['ce']=0;
		}
	$bConTotal=true;
	$sSQLadd='';
	$sSQLadd1='';
	if ($saiu41idperiodo!=''){
		if ($saiu41idperiodo==-99){$saiu41idperiodo=0;}
		$sSQLadd1=$sSQLadd1.'TB.saiu41idperiodo='.$saiu41idperiodo.'';
		}
	if ($saiu41idprograma!=''){
		if ($sSQLadd1!=''){$sSQLadd1=$sSQLadd1.' AND ';}
		$sSQLadd1=$sSQLadd1.'TB.saiu41idprograma='.$saiu41idprograma.'';
		}else{
		if ($saiu41idescuela!=''){
			if ($sSQLadd1!=''){$sSQLadd1=$sSQLadd1.' AND ';}
			$sSQLadd1=$sSQLadd1.'TB.saiu41idescuela='.$saiu41idescuela.'';
			}
		}
	if ($saiu41idcurso!=''){
		if ($sSQLadd1!=''){$sSQLadd1=$sSQLadd1.' AND ';}
		$sSQLadd1=$sSQLadd1.'TB.saiu41idcurso='.$saiu41idcurso.'';
		}
	if ($saiu41idcead!=''){
		if ($sSQLadd1!=''){$sSQLadd1=$sSQLadd1.' AND ';}
		$sSQLadd1=$sSQLadd1.'TB.saiu41idcentro='.$saiu41idcead.'';
		}else{
		if ($saiu41idzona!=''){
			if ($sSQLadd1!=''){$sSQLadd1=$sSQLadd1.' AND ';}
			$sSQLadd1=$sSQLadd1.'TB.saiu41idzona='.$saiu41idzona.'';
			}
		}
	if ($saiu41tipocontacto!=''){
		if ($sSQLadd1!=''){$sSQLadd1=$sSQLadd1.' AND ';}
		$sSQLadd1=$sSQLadd1.'TB.saiu41tipocontacto='.$saiu41tipocontacto.'';
		$sSQLadd=' WHERE ceca26id='.$saiu41tipocontacto.'';
		$bConTotal=false;
		}
	if ((int)$saiu41fecha!=0){
		if ($sSQLadd1!=''){$sSQLadd1=$sSQLadd1.' AND ';}
		$sSQLadd1=$sSQLadd1.'TB.saiu41fecha>='.$saiu41fecha.'';
		}
	if ((int)$saiu41fechafin!=0){
		if ($sSQLadd1!=''){$sSQLadd1=$sSQLadd1.' AND ';}
		$sSQLadd1=$sSQLadd1.'TB.saiu41fecha<='.$saiu41fechafin.'';
		}
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
	$sTitulos='Periodo, Escuela, Programa, Curso, Zona, Cead, Tipocontacto, Fecha, Fechafin';
	$registros=0;
	$bGigante=false; //En caso de que la tabla sea muy grande pasarlo a true
	$sLimite='';
	if ($sSQLadd1!=''){$sSQLadd1=' WHERE '.$sSQLadd1;}
	$sSQL='SHOW TABLES LIKE "core04%"';
	if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' <b>Recaulculando Totales de Matricula Por Curso</b>: Lista de contenedores: '.$sSQL.'<br>';}
	$tabla=$objDB->ejecutasql($sSQL);
	while($fila=$objDB->sf($tabla)){
		//Recorrer los contenedores cargando la data.
		$iContenedor=substr($fila[0], 16);
		if ($iContenedor!=0){
			$sSQL='SELECT TB.saiu41tipocontacto, TB.saiu41cerrada, COUNT(1) AS Total 
			FROM saiu41docente_'.$iContenedor.' AS TB 
			'.$sSQLadd1.'
			GROUP BY TB.saiu41tipocontacto, TB.saiu41cerrada';
			if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Parcial: '.$sSQL.'<br>';}
			$tabla40=$objDB->ejecutasql($sSQL);
			while($fila40=$objDB->sf($tabla40)){
				$idTipoC=$fila40['saiu41tipocontacto'];
				if ($fila40['saiu41cerrada']==0){
					$aTipo[$idTipoC]['ab']=$aTipo[$idTipoC]['ab']+$fila40['Total'];
					}else{
					$aTipo[$idTipoC]['ce']=$aTipo[$idTipoC]['ce']+$fila40['Total'];
					}
				}
			}
		}
	
	$sSQL='SELECT ceca26id, ceca26nombre
	FROM ceca26tipoacompana AS TB '.$sSQLadd.'
	ORDER BY ceca26nombre';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_3045" name="consulta_3045" type="hidden" value="'.$sSQLlista.'"/>
	<input id="titulos_3045" name="titulos_3045" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objDB->ejecutasql($sSQL.$sLimite);
	if ($tabladetalle==false){
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		//$sLeyenda=$sSQL;
		}else{
		if (!$bGigante){
			$registros=$objDB->nf($tabladetalle);
			}
		}
	$res=$sErrConsulta.$sLeyenda.$sBotones;
	$res=$res.'<div class="table-responsive">
	<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">
	<thead class="fondoazul"><tr>
	<td><b>'.$ETI['saiu41tipocontacto'].'</b></td>
	<td align="center"><b>'.$ETI['msg_iniciados'].'</b></td>
	<td align="center"><b>'.$ETI['msg_completos'].'</b></td>
	</tr></thead>';
	$tlinea=1;
	$iTotalAbiertas=0;
	$iTotalCerradas=0;
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
		$idTipoC=$filadet['ceca26id'];
		$et_abiertas='';
		if ($aTipo[$idTipoC]['ab']!=0){
			$et_abiertas=formato_numero($aTipo[$idTipoC]['ab']);
			$iTotalAbiertas=$iTotalAbiertas+$aTipo[$idTipoC]['ab'];
			}
		$et_cerradas='';
		if ($aTipo[$idTipoC]['ce']!=0){
			$et_cerradas=formato_numero($aTipo[$idTipoC]['ce']);
			$iTotalCerradas=$iTotalCerradas+$aTipo[$idTipoC]['ce'];
			}
		$res=$res.'<tr'.$sClass.'>
		<td>'.$sPrefijo.cadena_notildes($filadet['ceca26nombre']).$sSufijo.'</td>
		<td align="right">'.$sPrefijo.$et_abiertas.$sSufijo.'</td>
		<td align="right">'.$sPrefijo.$et_cerradas.$sSufijo.'</td>
		</tr>';
		}
	if ($bConTotal){
		$sPrefijo='<b>';
		$sSufijo='</b>';
		$et_abiertas=formato_numero($iTotalAbiertas);
		$et_cerradas=formato_numero($iTotalCerradas);
		$res=$res.'<tr>
		<td>'.$sPrefijo.'Total'.$sSufijo.'</td>
		<td align="right">'.$sPrefijo.$et_abiertas.$sSufijo.'</td>
		<td align="right">'.$sPrefijo.$et_cerradas.$sSufijo.'</td>
		</tr>
		<tr>
		<td></td>
		<td colspan="2" align="center">'.$sPrefijo.formato_numero($iTotalAbiertas+$iTotalCerradas).$sSufijo.'</td>
		</tr>';
		}
	$res=$res.'</table>
	<div class="salto5px"></div>
	</div>';
	$objDB->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
	}
function f3045_HtmlTabla($aParametros){
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
	list($sDetalle, $sDebugTabla)=f3045_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f3045detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
?>