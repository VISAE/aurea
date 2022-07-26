<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2019 - 2022 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.22.6d miércoles, 23 de enero de 2019
--- Modelo Versión 2.28.1 jueves, 5 de mayo de 2022
--- 2349 cara49necespeciales
*/
/** Archivo lib2349.php.
* Libreria 2349 cara49necespeciales.
* @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
* @date jueves, 5 de mayo de 2022
*/
function f2349_HTMLComboV2_cara49idperaca($objDB, $objCombos, $valor){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('cara49idperaca', $valor, true, '{'.$ETI['msg_todos'].'}');
	//Solo los peracas donde haya encuestas.
	$sIds='-99';
	$sSQL='SELECT cara01idperaca FROM cara01encuesta GROUP BY cara01idperaca';
	$tabla=$objDB->ejecutasql($sSQL);
	while($fila=$objDB->sf($tabla)){
		$sIds=$sIds.','.$fila['cara01idperaca'];
		}
	$sSQL=f146_ConsultaCombo('exte02id IN ('.$sIds.')');
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f2349_HTMLComboV2_cara49idzona($objDB, $objCombos, $valor){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('cara49idzona', $valor, true, '{'.$ETI['msg_todas'].'}');
	//$objCombos->iAncho=450;
	$objCombos->sAccion='carga_combo_cara49idcentro()';
	$sSQL='SELECT unad23id AS id, unad23nombre AS nombre FROM unad23zona';
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f2349_HTMLComboV2_cara49idcentro($objDB, $objCombos, $valor, $vrcara49idzona){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('cara49idcentro', $valor, true, '{'.$ETI['msg_todos'].'}');
	//$objCombos->iAncho=450;
	$objCombos->sAccion='paginarf2349();';
	$sSQL='';
	if ((int)$vrcara49idzona!=0){
		$sSQL='SELECT unad24id AS id, unad24nombre AS nombre FROM unad24sede WHERE unad24idzona='.$vrcara49idzona.'';
		}
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f2349_HTMLComboV2_cara49idprograma($objDB, $objCombos, $valor, $vrcara49idescuela){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('cara49idprograma', $valor, true, '{'.$ETI['msg_todos'].'}');
	//$objCombos->iAncho=450;
	$objCombos->sAccion='paginarf2349();';
	$sSQL='';
	if ((int)$vrcara49idescuela!=0){
		$sSQL='SELECT core09id AS id, CONCAT(core09nombre, " [", core09codigo, "]") AS nombre FROM core09programa WHERE core09idescuela='.$vrcara49idescuela.'';
		}
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f2349_Combocara49idcentro($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$objCombos=new clsHtmlCombos();
	$html_cara49idcentro=f2349_HTMLComboV2_cara49idcentro($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_cara49idcentro', 'innerHTML', $html_cara49idcentro);
	//$objResponse->call('$("#cara49idcentro").chosen()');
	$objResponse->call('paginarf2349()');
	return $objResponse;
	}
function f2349_Combocara49idprograma($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$objCombos=new clsHtmlCombos();
	$html_cara49idprograma=f2349_HTMLComboV2_cara49idprograma($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_cara49idprograma', 'innerHTML', $html_cara49idprograma);
	$objResponse->call('$("#cara49idprograma").chosen()');
	$objResponse->call('paginarf2349()');
	return $objResponse;
	}
function f2349_Busquedas($aParametros){
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2349='lg/lg_2349_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2349)){$mensajes_2349='lg/lg_2349_es.php';}
	require $mensajes_todas;
	require $mensajes_2349;
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
	$sTitulo='<h2>'.$ETI['titulo_2349'].' - '.$sTitulo.'</h2>';
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97titulo', 'innerHTML', $sTitulo);
	$objResponse->assign('div_97params', 'innerHTML', $sParams);
	$objResponse->assign('div_97tabla', 'innerHTML', $sTabla);
	$objResponse->setFunction('paginarbusqueda','',$sJavaBusqueda);
	$objResponse->call('expandesector(97)');
	return $objResponse;
	}
function f2349_HtmlBusqueda($aParametros){
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
function f2349_TablaDetalleV2($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2349='lg/lg_2349_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2349)){$mensajes_2349='lg/lg_2349_es.php';}
	require $mensajes_todas;
	require $mensajes_2349;
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
	$idTercero=$aParametros[100];
	$sDebug='';
	if (true){
		//Leemos los parametros de entrada.
		$pagina=$aParametros[101];
		$lineastabla=$aParametros[102];
		$cara49idperaca=$aParametros[103];
		$cara49idzona=$aParametros[104];
		$cara49idcentro=$aParametros[105];
		$cara49idescuela=$aParametros[106];
		$cara49idprograma=$aParametros[107];
		$cara49tipopoblacion=$aParametros[108];
		$cara49periodomat=$aParametros[109];
		}
	$bAbierta=true;
	//$sSQL='SELECT Campo FROM Tabla WHERE Id='.$sValorId;
	//$tabla=$objDB->ejecutasql($sSQL);
	//if ($objDB->nf($tabla)>0){
		//$fila=$objDB->sf($tabla);
		//if ($fila['Campo']!='S'){$bAbierta=true;}
		//}
	$sLeyenda='';
	$sBotones='<input id="paginaf2349" name="paginaf2349" type="hidden" value="'.$pagina.'"/>
	<input id="lppf2349" name="lppf2349" type="hidden" value="'.$lineastabla.'"/>';
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
		$sSQLadd='';
		$sSQLadd1='';
		//if ($cara49idperaca!=''){$sSQLadd1=$sSQLadd1.'TB.cara49idperaca='.$cara49idperaca.' AND ';}
		//if ($cara49idzona!=''){$sSQLadd1=$sSQLadd1.'TB.cara49idzona='.$cara49idzona.' AND ';}
		//if ($cara49idcentro!=''){$sSQLadd1=$sSQLadd1.'TB.cara49idcentro='.$cara49idcentro.' AND ';}
		//if ($cara49idescuela!=''){$sSQLadd1=$sSQLadd1.'TB.cara49idescuela='.$cara49idescuela.' AND ';}
		//if ($cara49idprograma!=''){$sSQLadd1=$sSQLadd1.'TB.cara49idprograma='.$cara49idprograma.' AND ';}
		//if ($cara49tipopoblacion!=''){$sSQLadd1=$sSQLadd1.'TB.cara49tipopoblacion='.$cara49tipopoblacion.' AND ';}
		//if ($cara49periodomat!=''){$sSQLadd1=$sSQLadd1.'TB.cara49periodomat='.$cara49periodomat.' AND ';}
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
	$sTitulos='Peraca, Zona, Centro, Tipopoblacion';
	$registros=0;
	$bGigante=false; //En caso de que la tabla sea muy grande pasarlo a true
	$sLimite='';
	if ($bGigante){
		$sSQL='SELECT COUNT(1) AS Total 
		FROM cara49necespeciales AS TB, exte02per_aca AS T1, unad23zona AS T2, unad24sede AS T3, core12escuela AS T4, core09programa AS T5, exte02per_aca AS T7 
		WHERE '.$sSQLadd1.' TB.cara49idperaca=T1.exte02id AND TB.cara49idzona=T2.unad23id AND TB.cara49idcentro=T3.unad24id AND TB.cara49idescuela=T4.core12id AND TB.cara49idprograma=T5.core09id AND TB.cara49periodomat=T7.exte02id '.$sSQLadd.'';
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
	$sSQL='SELECT T1.exte02nombre, T2.unad23nombre, T3.unad24nombre, TB.cara49tipopoblacion, TB.cara49idperaca, TB.cara49idzona, TB.cara49idcentro 
FROM cara49necespeciales AS TB, exte02per_aca AS T1, unad23zona AS T2, unad24sede AS T3 
WHERE '.$sSQLadd1.' TB.cara49idperaca=T1.exte02id AND TB.cara49idzona=T2.unad23id AND TB.cara49idcentro=T3.unad24id '.$sSQLadd.'
ORDER BY TB.cara49idperaca';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_2349" name="consulta_2349" type="hidden" value="'.$sSQLlista.'"/>
	<input id="titulos_2349" name="titulos_2349" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objDB->ejecutasql($sSQL.$sLimite);
	if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 2349: '.$sSQL.$sLimite.'<br>';}
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
	<td><b>'.$ETI['cara49idperaca'].'</b></td>
	<td><b>'.$ETI['cara49idzona'].'</b></td>
	<td><b>'.$ETI['cara49idcentro'].'</b></td>
	<td><b>'.$ETI['cara49idescuela'].'</b></td>
	<td><b>'.$ETI['cara49idprograma'].'</b></td>
	<td><b>'.$ETI['cara49tipopoblacion'].'</b></td>
	<td><b>'.$ETI['cara49periodomat'].'</b></td>
	<td align="right">
	'.html_paginador('paginaf2349', $registros, $lineastabla, $pagina, 'paginarf2349()').'
	'.html_lpp('lppf2349', $lineastabla, 'paginarf2349()').'
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
		if ($bAbierta){
			$sLink='<a href="javascript:cargadato('."'".$filadet['cara49idperaca']."'".')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$res=$res.'<tr'.$sClass.'>
		<td>'.$sPrefijo.cadena_notildes($filadet['exte02nombre']).$sSufijo.'</td>
		<td>'.$sPrefijo.cadena_notildes($filadet['unad23nombre']).$sSufijo.'</td>
		<td>'.$sPrefijo.cadena_notildes($filadet['unad24nombre']).$sSufijo.'</td>
		<td>'.$sPrefijo.cadena_notildes($filadet['core12nombre']).$sSufijo.'</td>
		<td>'.$sPrefijo.cadena_notildes($filadet['core09nombre']).$sSufijo.'</td>
		<td>'.$sPrefijo.$filadet['cara49tipopoblacion'].$sSufijo.'</td>
		<td>'.$sPrefijo.cadena_notildes($filadet['exte02nombre']).$sSufijo.'</td>
		<td>'.$sLink.'</td>
		</tr>';
		}
	$res=$res.'</table>
	<div class="salto5px"></div>
	</div>';
	$objDB->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
	}
function f2349_HtmlTabla($aParametros){
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
	list($sDetalle, $sDebugTabla)=f2349_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f2349detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------
?>