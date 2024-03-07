<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2023 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.29.6 lunes, 31 de julio de 2023
--- 3021 saiu21directa
*/
/** Archivo lib3021.php.
 * Libreria 3021 saiu21directa.
 * @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
 * @date lunes, 31 de julio de 2023
 */
function f3021_HTMLComboV2_saiu21agno($objDB, $objCombos, $valor){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('saiu21agno', $valor, false, '{'.$ETI['msg_seleccione'].'}');
	//$objCombos->iAncho=450;
	$objCombos->sAccion='RevisaLlave();';
	$sSQL='SHOW TABLES LIKE "saiu21directa%"';
	//if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Total Periodo: Lista de contenedores: '.$sSQL.'<br>';}
	$tablac=$objDB->ejecutasql($sSQL);
	while($filac=$objDB->sf($tablac)){
		$sAgno=substr($filac[0], 14);
		$objCombos->addItem($sAgno, $sAgno);
	}
	$res=$objCombos->html('', $objDB);
	return $res;
	}
function f3021_HTMLComboV2_saiu21mes($objDB, $objCombos, $valor){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	/*
	$objCombos->nuevo('saiu21mes', $valor, false, '{'.$ETI['msg_seleccione'].'}');
	//$objCombos->iAncho=450;
	$objCombos->sAccion='RevisaLlave();';
	$sSQL='SELECT  AS id,  AS nombre FROM ';
	$res=$objCombos->html($sSQL, $objDB);
	*/
	$res=html_ComboMes('saiu21mes', $valor, false, 'RevisaLlave();');
	return $res;
	}
function f3021_HTMLComboV2_saiu21tiporadicado($objDB, $objCombos, $valor){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('saiu21tiporadicado', $valor, false, '{'.$ETI['msg_seleccione'].'}');
	//$objCombos->iAncho=450;
	$objCombos->sAccion='RevisaLlave();';
	$sSQL='SELECT saiu16id AS id, saiu16nombre AS nombre FROM saiu16tiporadicado WHERE saiu16id IN (1, 3) ORDER BY saiu16nombre';
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f3021_HTMLComboV2_saiu21tiposolicitud($objDB, $objCombos, $valor){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('saiu21tiposolicitud', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	$objCombos->sAccion='carga_combo_saiu21temasolicitud();';
	//$objCombos->iAncho=450;
	$sSQL='SELECT TB.saiu02id AS id, CONCAT(TB.saiu02titulo, " [", T1.saiu01titulo, "]") AS nombre 
	FROM saiu02tiposol AS TB, saiu01claseser AS T1 
	WHERE TB.saiu02id>0 AND TB.saiu02ordenllamada<9 AND TB.saiu02clasesol=T1.saiu01id 
	ORDER BY TB.saiu02ordenllamada, TB.saiu02titulo';
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f3021_HTMLComboV2_saiu21temasolicitud($objDB, $objCombos, $valor, $vrsaiu21tiposolicitud){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('saiu21temasolicitud', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	//$objCombos->iAncho=450;
	$sSQL='';
	if ((int)$vrsaiu21tiposolicitud==0){
		$objCombos->sAccion='carga_combo_saiu21tiposolicitud()';
		$sSQL='SELECT TB.saiu03id AS id, CONCAT(TB.saiu03titulo, " [", T2.saiu02titulo, "]") AS nombre 
		FROM saiu03temasol AS TB, saiu02tiposol AS T2 
		WHERE TB.saiu03id>0 AND TB.saiu03ordenllamada<9 AND TB.saiu03tiposol=T2.saiu02id
		ORDER BY TB.saiu03ordensoporte, TB.saiu03titulo';
		}else{
		$sSQL='SELECT saiu03id AS id, saiu03titulo AS nombre 
		FROM saiu03temasol 
		WHERE saiu03id>0 AND saiu03ordenllamada<9 AND saiu03tiposol='.$vrsaiu21tiposolicitud.'
		ORDER BY saiu03ordenllamada, saiu03titulo';
		}
	//if ((int)$vrsaiu21tiposolicitud!=0){}
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f3021_HTMLComboV2_saiu21idcentro($objDB, $objCombos, $valor, $vrsaiu21idzona){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$sCondi='';
	$objCombos->nuevo('saiu21idcentro', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	//$objCombos->iAncho=450;
	$sSQL='';
	if ((int)$vrsaiu21idzona!=0) {
		$sSQL='SELECT unad24id AS id, CONCAT(unad24nombre, CASE unad24activa WHEN "S" THEN "" ELSE " [INACTIVA]" END) AS nombre 
		FROM unad24sede 
		WHERE unad24idzona='.$vrsaiu21idzona.' AND unad24id>0 
		ORDER BY unad24activa DESC, unad24nombre';
	}
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f3021_HTMLComboV2_saiu21coddepto($objDB, $objCombos, $valor, $vrsaiu21codpais){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('saiu21coddepto', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	//$objCombos->iAncho=450;
	$objCombos->sAccion='carga_combo_saiu21codciudad()';
	$sSQL='';
	if ((int)$vrsaiu21codpais!=0){
		$sSQL='SELECT unad19codigo AS id, unad19nombre AS nombre FROM unad19depto WHERE unad19codpais="'.$vrsaiu21codpais.'" ORDER BY unad19nombre';
		}
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f3021_HTMLComboV2_saiu21codciudad($objDB, $objCombos, $valor, $vrsaiu21coddepto){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('saiu21codciudad', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	//$objCombos->iAncho=450;
	$sSQL='';
	if ((int)$vrsaiu21coddepto!=0){
		$sSQL='SELECT unad20codigo AS id, unad20nombre AS nombre FROM unad20ciudad WHERE unad20coddepto="'.$vrsaiu21coddepto.'" ORDER BY unad20nombre';
		}
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f3021_Combosaiu21tiposolicitud($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$objCombos=new clsHtmlCombos();
	$idTema=$aParametros[0];
	$idTipo=0;
	$sSQL='SELECT saiu03tiposol FROM saiu03temasol WHERE saiu03id='.$idTema.'';
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$idTipo=$fila['saiu03tiposol'];
		$html_saiu21tiposolicitud=f3021_HTMLComboV2_saiu21tiposolicitud($objDB, $objCombos, $idTipo);
		$html_saiu21temasolicitud=f3021_HTMLComboV2_saiu21temasolicitud($objDB, $objCombos, $idTema, $idTipo);
		$objDB->CerrarConexion();
		$objResponse=new xajaxResponse();
		$objResponse->assign('div_saiu21tiposolicitud', 'innerHTML', $html_saiu21tiposolicitud);
		$objResponse->assign('div_saiu21temasolicitud', 'innerHTML', $html_saiu21temasolicitud);
		$objResponse->call('$("#saiu21tiposolicitud").chosen()');
		$objResponse->call('$("#saiu21temasolicitud").chosen()');
		return $objResponse;
		}else{
		$objDB->CerrarConexion();
		}
	}
function f3021_Combosaiu21temasolicitud($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$objCombos=new clsHtmlCombos();
	$html_saiu21temasolicitud=f3021_HTMLComboV2_saiu21temasolicitud($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_saiu21temasolicitud', 'innerHTML', $html_saiu21temasolicitud);
	$objResponse->call('$("#saiu21temasolicitud").chosen({width:"100%"})');
	return $objResponse;
	}
function f3021_HTMLComboV2_saiu21idprograma($objDB, $objCombos, $valor, $vrsaiu21idescuela){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('saiu21idprograma', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	$objCombos->addItem('0', $ETI['msg_na']);
	//$objCombos->iAncho=450;
	$sCondiEscuela=' AND TB.core09idescuela="'.$vrsaiu21idescuela.'"';
	$sTabla2='';
	$sCampos2='';
	if ($vrsaiu21idescuela==''){
		$sCondiEscuela=' AND TB.core09idescuela=T12.core12id';
		$sTabla2=', core12escuela AS T12';
		$sCampos2=', " [", T12.core12sigla, "]"';
		}
	$sSQL='SELECT TB.core09id AS id, CONCAT(TB.core09nombre, " [", TB.core09codigo, "]"'.$sCampos2.') AS nombre FROM core09programa AS TB'.$sTabla2.' WHERE TB.core09id>0'.$sCondiEscuela;
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f3021_Combosaiu21idcentro($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$objCombos=new clsHtmlCombos();
	$html_saiu21idcentro=f3021_HTMLComboV2_saiu21idcentro($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_saiu21idcentro', 'innerHTML', $html_saiu21idcentro);
	$objResponse->call('$("#saiu21idcentro").chosen()');
	return $objResponse;
	}
function f3021_Combosaiu21coddepto($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$objCombos=new clsHtmlCombos();
	$html_saiu21coddepto=f3021_HTMLComboV2_saiu21coddepto($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_saiu21coddepto', 'innerHTML', $html_saiu21coddepto);
	$objResponse->call('$("#saiu21coddepto").chosen()');
	return $objResponse;
	}
function f3021_Combosaiu21codciudad($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$objCombos=new clsHtmlCombos();
	$html_saiu21codciudad=f3021_HTMLComboV2_saiu21codciudad($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_saiu21codciudad', 'innerHTML', $html_saiu21codciudad);
	$objResponse->call('$("#saiu21codciudad").chosen()');
	return $objResponse;
	}
function f3021_Combosaiu21idprograma($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$objCombos=new clsHtmlCombos();
	$html_saiu21idprograma=f3021_HTMLComboV2_saiu21idprograma($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_saiu21idprograma', 'innerHTML', $html_saiu21idprograma);
	$objResponse->call('$("#saiu21idprograma").chosen({width:"100%"})');
	return $objResponse;
	}
function f3021_ExisteDato($datos){
	if(!is_array($datos)){$datos=json_decode(str_replace('\"','"',$datos),true);}
	$_SESSION['u_ultimominuto']=iminutoavance();
	$bHayLlave=true;
	$saiu21agno=numeros_validar($datos[1]);
	if ($saiu21agno==''){$bHayLlave=false;}
	$saiu21mes=numeros_validar($datos[2]);
	if ($saiu21mes==''){$bHayLlave=false;}
	$saiu21tiporadicado=numeros_validar($datos[3]);
	if ($saiu21tiporadicado==''){$bHayLlave=false;}
	$saiu21consec=numeros_validar($datos[4]);
	if ($saiu21consec==''){$bHayLlave=false;}
	if ($bHayLlave){
		require './app.php';
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		$sSQL='SELECT 1 FROM saiu21directa_'.$saiu21agno.' WHERE saiu21agno='.$saiu21agno.' AND saiu21mes='.$saiu21mes.' AND saiu21tiporadicado='.$saiu21tiporadicado.' AND saiu21consec='.$saiu21consec.'';
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
function f3021_Busquedas($aParametros){
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3021='lg/lg_3021_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3021)){$mensajes_3021='lg/lg_3021_es.php';}
	require $mensajes_todas;
	require $mensajes_3021;
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
		case 'saiu21idsolicitante':
		require $APP->rutacomun.'lib111.php';
		$sTabla=f111_TablaDetalleBusquedas($aParametrosB, $objDB);
		$sTitulo='Busqueda de terceros';
		$sParams=f111_ParametrosBusqueda();
		$sJavaBusqueda=f111_JavaScriptBusqueda(3021);
		break;
		case 'saiu21idresponsable':
		require $APP->rutacomun.'lib111.php';
		$sTabla=f111_TablaDetalleBusquedas($aParametrosB, $objDB);
		$sTitulo='Busqueda de terceros';
		$sParams=f111_ParametrosBusqueda();
		$sJavaBusqueda=f111_JavaScriptBusqueda(3021);
		break;
		}
	$sTitulo='<h2>'.$ETI['titulo_3021'].' - '.$sTitulo.'</h2>';
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97titulo', 'innerHTML', $sTitulo);
	$objResponse->assign('div_97params', 'innerHTML', $sParams);
	$objResponse->assign('div_97tabla', 'innerHTML', $sTabla);
	$objResponse->setFunction('paginarbusqueda','',$sJavaBusqueda);
	$objResponse->call('expandesector(97)');
	return $objResponse;
	}
function f3021_HtmlBusqueda($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$sError='';
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$sDetalle='';
	switch($aParametros[100]){
		case 'saiu21idsolicitante':
		require $APP->rutacomun.'lib111.php';
		$sDetalle=f111_TablaDetalleBusquedas($aParametros, $objDB);
		break;
		case 'saiu21idresponsable':
		require $APP->rutacomun.'lib111.php';
		$sDetalle=f111_TablaDetalleBusquedas($aParametros, $objDB);
		break;
		}
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97tabla', 'innerHTML', $sDetalle);
	return $objResponse;
	}
function f3021_TablaDetalleV2($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3021='lg/lg_3021_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3021)){$mensajes_3021='lg/lg_3021_es.php';}
	require $mensajes_todas;
	require $mensajes_3021;
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	if (isset($aParametros[100])==0){$aParametros[100]=$_SESSION['unad_id_tercero'];}
	if (isset($aParametros[101])==0){$aParametros[101]=1;}
	if (isset($aParametros[102])==0){$aParametros[102]=20;}
	//if (isset($aParametros[103])==0){$aParametros[103]='';}
	if (isset($aParametros[104])==0){$aParametros[104]='';}
	if (isset($aParametros[105])==0){$aParametros[105]='';}
	//$aParametros[103]=numeros_validar($aParametros[103]);
	$idTercero=$aParametros[100];
	$sDebug='';
	$pagina=$aParametros[101];
	$lineastabla=$aParametros[102];
	$iVigencia=$aParametros[104];
	$iTipo=$aParametros[105];
	$bAbierta=true;
	//$sSQL='SELECT Campo FROM Tabla WHERE Id='.$sValorId;
	//$tabla=$objDB->ejecutasql($sSQL);
	//if ($objDB->nf($tabla)>0){
		//$fila=$objDB->sf($tabla);
		//if ($fila['Campo']!='S'){$bAbierta=true;}
		//}
	$sLeyenda='';
	//Verificamos que exista la tabla.
	if ((int)$iVigencia==0){$iVigencia=fecha_agno();}
	if ($sLeyenda!=''){
		$sLeyenda='<div class="salto1px"></div>
<div class="GrupoCamposAyuda">
'.$sLeyenda.'
<div class="salto1px"></div>
</div>';
		return array($sLeyenda.'<input id="paginaf3021" name="paginaf3021" type="hidden" value="'.$pagina.'"/><input id="lppf3021" name="lppf3021" type="hidden" value="'.$lineastabla.'"/>', $sDebug);
		die();
		}

	$aEstado=array();
	$sSQL='SELECT saiu11id, saiu11nombre FROM saiu11estadosol';
	$tabla=$objDB->ejecutasql($sSQL);
	while($fila=$objDB->sf($tabla)){
		$aEstado[$fila['saiu11id']]=cadena_notildes($fila['saiu11nombre']);
		}
	$aTipoRad=array();
	$sSQL='SELECT saiu16id, saiu16nombre FROM saiu16tiporadicado';
	$tabla=$objDB->ejecutasql($sSQL);
	while($fila=$objDB->sf($tabla)){
		$aTipoRad[$fila['saiu16id']]=cadena_notildes($fila['saiu16nombre']);
		}
	$sSQLadd='';
	$sSQLadd1='';
	switch($aParametros[105]){
		case 1:
		$sSQLadd1=$sSQLadd1.'TB.saiu21idresponsable='.$idTercero.' AND ';
		break;
		}
	//if ((int)$aParametros[103]!=-1){$sSQLadd=$sSQLadd.' AND TB.campo='.$aParametros[103];}
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
	$sTitulos='Agno, Mes, Tiporadicado, Consec, Id, Dia, Hora, Minuto, Estado, Telefono, Numtelefono, Solicitante, Tipointeresado, Clasesolicitud, Tiposolicitud, Temasolicitud, Zona, Centro, Codpais, Coddepto, Codciudad, Escuela, Programa, Periodo, Numorigen, Pqrs, Detalle, Horafin, Minutofin, Paramercadeo, Responsable, Tiemprespdias, Tiempresphoras, Tiemprespminutos';
	$registros=0;
	$bGigante=false; //En caso de que la tabla sea muy grande pasarlo a true
	$sLimite='';
	if ($bGigante){
		$sSQL='SELECT COUNT(1) AS Total 
FROM saiu21directa_'.$iVigencia.' AS TB, unad11terceros AS T12, saiu03temasol AS T16 
WHERE '.$sSQLadd1.' TB.saiu21idsolicitante=T12.unad11id AND TB.saiu21temasolicitud=T16.saiu03id '.$sSQLadd.'';
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
	//, TB.saiu21idpqrs, TB.saiu21detalle, TB.saiu21horafin, TB.saiu21minutofin, TB.saiu21paramercadeo, TB.saiu21tiemprespdias, TB.saiu21tiempresphoras, TB.saiu21tiemprespminutos, TB.saiu21tipointeresado, TB.saiu21clasesolicitud, TB.saiu21tiposolicitud, TB.saiu21temasolicitud, TB.saiu21idzona, TB.saiu21idcentro, TB.saiu21codpais, TB.saiu21coddepto, TB.saiu21codciudad, TB.saiu21idescuela, TB.saiu21idprograma, TB.saiu21idperiodo, TB.saiu21idresponsable
	$sSQL='SELECT TB.saiu21agno, TB.saiu21mes, TB.saiu21consec, TB.saiu21id, TB.saiu21dia, TB.saiu21hora, TB.saiu21minuto, T12.unad11razonsocial AS C12_nombre, T16.saiu03titulo, TB.saiu21estado, T12.unad11tipodoc AS C12_td, T12.unad11doc AS C12_doc, TB.saiu21idsolicitante, TB.saiu21tiporadicado 
FROM saiu21directa_'.$iVigencia.' AS TB, unad11terceros AS T12, saiu03temasol AS T16 
WHERE '.$sSQLadd1.' TB.saiu21idsolicitante=T12.unad11id AND TB.saiu21temasolicitud=T16.saiu03id '.$sSQLadd.'
ORDER BY TB.saiu21agno DESC, TB.saiu21mes DESC, TB.saiu21dia DESC, TB.saiu21tiporadicado, TB.saiu21consec DESC';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_3021" name="consulta_3021" type="hidden" value="'.$sSQLlista.'"/>
<input id="titulos_3021" name="titulos_3021" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objDB->ejecutasql($sSQL.$sLimite);
	if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 3021: '.$sSQL.$sLimite.'<br>';}
	if ($tabladetalle==false){
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		//$sLeyenda=$sSQL;
		}else{
		if (!$bGigante){
			$registros=$objDB->nf($tabladetalle);
			if ($registros==0){
				//return array(utf8_encode($sErrConsulta.'<input id="paginaf3021" name="paginaf3021" type="hidden" value="'.$pagina.'"/><input id="lppf3021" name="lppf3021" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
				}
			if ((($registros-1)/$lineastabla)<($pagina-1)){$pagina=(int)(($registros-1)/$lineastabla)+1;}
			if ($registros>$lineastabla){
				$rbase=($pagina-1)*$lineastabla;
				$sLimite=' LIMIT '.$rbase.', '.$lineastabla;
				$tabladetalle=$objDB->ejecutasql($sSQL.$sLimite);
				}
			}
		}
	$res=$sErrConsulta.$sLeyenda.'<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">
<thead class="fondoazul"><tr>
<td colspan="2"><b>'.$ETI['msg_fecha'].'</b></td>
<td><b>'.$ETI['saiu21tiporadicado'].'</b></td>
<td><b>'.$ETI['saiu21consec'].'</b></td>
<td><b>'.$ETI['saiu21estado'].'</b></td>
<td colspan="2"><b>'.$ETI['saiu21idsolicitante'].'</b></td>
<td><b>'.$ETI['saiu21temasolicitud'].'</b></td>
<td align="right">
'.html_paginador('paginaf3021', $registros, $lineastabla, $pagina, 'paginarf3021()').'
'.html_lpp('lppf3021', $lineastabla, 'paginarf3021()').'
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
		$et_saiu21hora=html_TablaHoraMin($filadet['saiu21hora'], $filadet['saiu21minuto']);
		$et_saiu21idsolicitante_doc='';
		$et_saiu21idsolicitante_nombre='';
		if ($filadet['saiu21idsolicitante']!=0){
			$et_saiu21idsolicitante_doc=$sPrefijo.$filadet['C12_td'].' '.$filadet['C12_doc'].$sSufijo;
			$et_saiu21idsolicitante_nombre=$sPrefijo.cadena_notildes($filadet['C12_nombre']).$sSufijo;
			}
		/*
		$et_saiu21horafin=html_TablaHoraMin($filadet['saiu21horafin'], $filadet['saiu21minutofin']);
		$et_saiu21idresponsable_doc='';
		$et_saiu21idresponsable_nombre='';
		if ($filadet['saiu21idresponsable']!=0){
			$et_saiu21idresponsable_doc=$sPrefijo.$filadet['C31_td'].' '.$filadet['C31_doc'].$sSufijo;
			$et_saiu21idresponsable_nombre=$sPrefijo.cadena_notildes($filadet['C31_nombre']).$sSufijo;
			}
		*/
		if ($bAbierta){
			$sLink='<a href="javascript:cargaridf3021('.$filadet['saiu21agno'].','.$filadet['saiu21id'].')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$et_fecha=fecha_armar($filadet['saiu21dia'], $filadet['saiu21mes'], $filadet['saiu21agno']);
		$res=$res.'<tr'.$sClass.'>
<td>'.$sPrefijo.$et_fecha.$sSufijo.'</td>
<td>'.$sPrefijo.$et_saiu21hora.$sSufijo.'</td>
<td>'.$sPrefijo.$aTipoRad[$filadet['saiu21tiporadicado']].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu21consec'].$sSufijo.'</td>
<td>'.$sPrefijo.$aEstado[$filadet['saiu21estado']].$sSufijo.'</td>
<td>'.$et_saiu21idsolicitante_doc.'</td>
<td>'.$et_saiu21idsolicitante_nombre.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['saiu03titulo']).$sSufijo.'</td>
<td>'.$sLink.'</td>
</tr>';
		}
	$res=$res.'</table>';
	$objDB->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
	}
function f3021_HtmlTabla($aParametros){
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
	list($sDetalle, $sDebugTabla)=f3021_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f3021detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f3021_db_CargarPadre($DATA, $objDB, $bDebug=false){
	$sError='';
	$iTipoError=0;
	$sDebug='';
	require './app.php';
	$DATA['saiu21idsolicitante_td']=$APP->tipo_doc;
	$DATA['saiu21idsolicitante_doc']='';
	$DATA['saiu21idresponsable_td']=$APP->tipo_doc;
	$DATA['saiu21idresponsable_doc']='';
	if ($DATA['paso']==1){
		$sSQLcondi='saiu21agno='.$DATA['saiu21agno'].' AND saiu21mes='.$DATA['saiu21mes'].' AND saiu21tiporadicado='.$DATA['saiu21tiporadicado'].' AND saiu21consec='.$DATA['saiu21consec'].'';
		}else{
		$sSQLcondi='saiu21id='.$DATA['saiu21id'].'';
		}
	$sSQL='SELECT * FROM saiu21directa_'.$DATA['saiu21agno'].' WHERE '.$sSQLcondi;
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$DATA['saiu21agno']=$fila['saiu21agno'];
		$DATA['saiu21mes']=$fila['saiu21mes'];
		$DATA['saiu21tiporadicado']=$fila['saiu21tiporadicado'];
		$DATA['saiu21consec']=$fila['saiu21consec'];
		$DATA['saiu21id']=$fila['saiu21id'];
		$DATA['saiu21dia']=$fila['saiu21dia'];
		$DATA['saiu21hora']=$fila['saiu21hora'];
		$DATA['saiu21minuto']=$fila['saiu21minuto'];
		$DATA['saiu21estado']=$fila['saiu21estado'];
		$DATA['saiu21idsolicitante']=$fila['saiu21idsolicitante'];
		$DATA['saiu21tipointeresado']=$fila['saiu21tipointeresado'];
		$DATA['saiu21clasesolicitud']=$fila['saiu21clasesolicitud'];
		$DATA['saiu21tiposolicitud']=$fila['saiu21tiposolicitud'];
		$DATA['saiu21temasolicitud']=$fila['saiu21temasolicitud'];
		$DATA['saiu21idzona']=$fila['saiu21idzona'];
		$DATA['saiu21idcentro']=$fila['saiu21idcentro'];
		$DATA['saiu21codpais']=$fila['saiu21codpais'];
		$DATA['saiu21coddepto']=$fila['saiu21coddepto'];
		$DATA['saiu21codciudad']=$fila['saiu21codciudad'];
		$DATA['saiu21idescuela']=$fila['saiu21idescuela'];
		$DATA['saiu21idprograma']=$fila['saiu21idprograma'];
		$DATA['saiu21idperiodo']=$fila['saiu21idperiodo'];
		$DATA['saiu21idpqrs']=$fila['saiu21idpqrs'];
		$DATA['saiu21detalle']=$fila['saiu21detalle'];
		$DATA['saiu21horafin']=$fila['saiu21horafin'];
		$DATA['saiu21minutofin']=$fila['saiu21minutofin'];
		$DATA['saiu21paramercadeo']=$fila['saiu21paramercadeo'];
		$DATA['saiu21idresponsable']=$fila['saiu21idresponsable'];
		$DATA['saiu21tiemprespdias']=$fila['saiu21tiemprespdias'];
		$DATA['saiu21tiempresphoras']=$fila['saiu21tiempresphoras'];
		$DATA['saiu21tiemprespminutos']=$fila['saiu21tiemprespminutos'];
		$DATA['saiu21solucion']=$fila['saiu21solucion'];
		$DATA['saiu21idcaso']=$fila['saiu21idcaso'];
		$DATA['saiu21respuesta']=$fila['saiu21respuesta'];
		$bcargo=true;
		$DATA['paso']=2;
		$DATA['boculta3021']=0;
		$bLimpiaHijos=true;
		}else{
		$DATA['paso']=0;
		}
	return array($DATA, $sError, $iTipoError, $sDebug);
	}
function f3021_Cerrar($saiu21id, $objDB, $bDebug=false){
	$sInfo='';
	$sDebug='';
	return array($sInfo, $sDebug);
	}
function f3021_db_GuardarV2($DATA, $objDB, $bDebug=false){
	$iCodModulo=3021;
	$bAudita[2]=true;
	$bAudita[3]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3021='lg/lg_3021_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3021)){$mensajes_3021='lg/lg_3021_es.php';}
	require $mensajes_todas;
	require $mensajes_3021;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	$bCerrando=false;
	$sErrorCerrando='';
	// -- Se inicia validando todas las posibles entradas de usuario.
	/*
	if (isset($DATA['saiu21agno'])==0){$DATA['saiu21agno']='';}
	if (isset($DATA['saiu21mes'])==0){$DATA['saiu21mes']='';}
	if (isset($DATA['saiu21tiporadicado'])==0){$DATA['saiu21tiporadicado']='';}
	if (isset($DATA['saiu21consec'])==0){$DATA['saiu21consec']='';}
	if (isset($DATA['saiu21id'])==0){$DATA['saiu21id']='';}
	if (isset($DATA['saiu21dia'])==0){$DATA['saiu21dia']='';}
	if (isset($DATA['saiu21hora'])==0){$DATA['saiu21hora']='';}
	if (isset($DATA['saiu21minuto'])==0){$DATA['saiu21minuto']='';}
	if (isset($DATA['saiu21idsolicitante'])==0){$DATA['saiu21idsolicitante']='';}
	if (isset($DATA['saiu21tipointeresado'])==0){$DATA['saiu21tipointeresado']='';}
	if (isset($DATA['saiu21clasesolicitud'])==0){$DATA['saiu21clasesolicitud']='';}
	if (isset($DATA['saiu21tiposolicitud'])==0){$DATA['saiu21tiposolicitud']='';}
	if (isset($DATA['saiu21temasolicitud'])==0){$DATA['saiu21temasolicitud']='';}
	if (isset($DATA['saiu21idzona'])==0){$DATA['saiu21idzona']='';}
	if (isset($DATA['saiu21idcentro'])==0){$DATA['saiu21idcentro']='';}
	if (isset($DATA['saiu21codpais'])==0){$DATA['saiu21codpais']='';}
	if (isset($DATA['saiu21coddepto'])==0){$DATA['saiu21coddepto']='';}
	if (isset($DATA['saiu21codciudad'])==0){$DATA['saiu21codciudad']='';}
	if (isset($DATA['saiu21idescuela'])==0){$DATA['saiu21idescuela']='';}
	if (isset($DATA['saiu21idprograma'])==0){$DATA['saiu21idprograma']='';}
	if (isset($DATA['saiu21idperiodo'])==0){$DATA['saiu21idperiodo']='';}
	if (isset($DATA['saiu21detalle'])==0){$DATA['saiu21detalle']='';}
	if (isset($DATA['saiu21horafin'])==0){$DATA['saiu21horafin']='';}
	if (isset($DATA['saiu21minutofin'])==0){$DATA['saiu21minutofin']='';}
	if (isset($DATA['saiu21paramercadeo'])==0){$DATA['saiu21paramercadeo']='';}
	if (isset($DATA['saiu21idresponsable'])==0){$DATA['saiu21idresponsable']='';}
	if (isset($DATA['saiu21solucion'])==0){$DATA['saiu21solucion']='';}
	if (isset($DATA['saiu21respuesta'])==0){$DATA['saiu21respuesta']='';}
	*/
	$DATA['saiu21agno']=numeros_validar($DATA['saiu21agno']);
	$DATA['saiu21mes']=numeros_validar($DATA['saiu21mes']);
	$DATA['saiu21tiporadicado']=numeros_validar($DATA['saiu21tiporadicado']);
	$DATA['saiu21consec']=numeros_validar($DATA['saiu21consec']);
	$DATA['saiu21dia']=numeros_validar($DATA['saiu21dia']);
	$DATA['saiu21hora']=numeros_validar($DATA['saiu21hora']);
	$DATA['saiu21minuto']=numeros_validar($DATA['saiu21minuto']);
	$DATA['saiu21tipointeresado']=numeros_validar($DATA['saiu21tipointeresado']);
	$DATA['saiu21clasesolicitud']=numeros_validar($DATA['saiu21clasesolicitud']);
	$DATA['saiu21tiposolicitud']=numeros_validar($DATA['saiu21tiposolicitud']);
	$DATA['saiu21temasolicitud']=numeros_validar($DATA['saiu21temasolicitud']);
	$DATA['saiu21idzona']=numeros_validar($DATA['saiu21idzona']);
	$DATA['saiu21idcentro']=numeros_validar($DATA['saiu21idcentro']);
	$DATA['saiu21codpais']=htmlspecialchars(trim($DATA['saiu21codpais']));
	$DATA['saiu21coddepto']=htmlspecialchars(trim($DATA['saiu21coddepto']));
	$DATA['saiu21codciudad']=htmlspecialchars(trim($DATA['saiu21codciudad']));
	$DATA['saiu21idescuela']=numeros_validar($DATA['saiu21idescuela']);
	$DATA['saiu21idprograma']=numeros_validar($DATA['saiu21idprograma']);
	$DATA['saiu21idperiodo']=numeros_validar($DATA['saiu21idperiodo']);
	$DATA['saiu21detalle']=htmlspecialchars(trim($DATA['saiu21detalle']));
	$DATA['saiu21horafin']=numeros_validar($DATA['saiu21horafin']);
	$DATA['saiu21minutofin']=numeros_validar($DATA['saiu21minutofin']);
	$DATA['saiu21paramercadeo']=numeros_validar($DATA['saiu21paramercadeo']);
	$DATA['saiu21solucion']=numeros_validar($DATA['saiu21solucion']);
	$DATA['saiu21respuesta']=htmlspecialchars(trim($DATA['saiu21respuesta']));
	// -- Se inicializan las variables que puedan pasar vacias {Especialmente números}.
	//if ($DATA['saiu21dia']==''){$DATA['saiu21dia']=0;}
	//if ($DATA['saiu21hora']==''){$DATA['saiu21hora']=0;}
	//if ($DATA['saiu21minuto']==''){$DATA['saiu21minuto']=0;}
	if ($DATA['saiu21estado']==''){$DATA['saiu21estado']=0;}
	//if ($DATA['saiu21tipointeresado']==''){$DATA['saiu21tipointeresado']=0;}
	if ($DATA['saiu21idpqrs']==''){$DATA['saiu21idpqrs']=0;}
	//if ($DATA['saiu21paramercadeo']==''){$DATA['saiu21paramercadeo']=0;}
	//if ($DATA['saiu21solucion']==''){$DATA['saiu21solucion']=0;}
	// -- Seccion para validar los posibles causales de error.
	$sSepara=', ';
	$bConCierre=false;
	if ($DATA['saiu21estado']==7){
		$bConCierre=true;
		if ($DATA['saiu21solucion']==''){
			$sError=$ERR['saiu21solucion'].$sSepara.$sError;
			}else{
			if ((int)$DATA['saiu21solucion']==0){
				$sError=$ERR['saiu21solucion_proceso'].$sSepara.$sError;
				}
			}
		if ($DATA['saiu21respuesta']==''){$sError=$ERR['saiu21respuesta'].$sSepara.$sError;}
		if ($DATA['saiu21idresponsable']==0){$sError=$ERR['saiu21idresponsable'].$sSepara.$sError;}
		if ($DATA['saiu21paramercadeo']==''){$sError=$ERR['saiu21paramercadeo'].$sSepara.$sError;}
		//if ($DATA['saiu21minutofin']==''){$sError=$ERR['saiu21minutofin'].$sSepara.$sError;}
		//if ($DATA['saiu21horafin']==''){$sError=$ERR['saiu21horafin'].$sSepara.$sError;}
		if ($DATA['saiu21detalle']==''){$sError=$ERR['saiu21detalle'].$sSepara.$sError;}
		if ($DATA['saiu21idperiodo']==''){$sError=$ERR['saiu21idperiodo'].$sSepara.$sError;}
		if ($DATA['saiu21idprograma']==''){$sError=$ERR['saiu21idprograma'].$sSepara.$sError;}
		if ($DATA['saiu21idescuela']==''){$sError=$ERR['saiu21idescuela'].$sSepara.$sError;}
		if ($DATA['saiu21codciudad']==''){$sError=$ERR['saiu21codciudad'].$sSepara.$sError;}
		if ($DATA['saiu21coddepto']==''){$sError=$ERR['saiu21coddepto'].$sSepara.$sError;}
		if ($DATA['saiu21codpais']==''){$sError=$ERR['saiu21codpais'].$sSepara.$sError;}
		if ($DATA['saiu21idcentro']==''){$sError=$ERR['saiu21idcentro'].$sSepara.$sError;}
		if ($DATA['saiu21idzona']==''){$sError=$ERR['saiu21idzona'].$sSepara.$sError;}
		if ($DATA['saiu21temasolicitud']==''){$sError=$ERR['saiu21temasolicitud'].$sSepara.$sError;}
		if ($DATA['saiu21tiposolicitud']==''){$sError=$ERR['saiu21tiposolicitud'].$sSepara.$sError;}
		if ($DATA['saiu21clasesolicitud']==''){$sError=$ERR['saiu21clasesolicitud'].$sSepara.$sError;}
		if ($DATA['saiu21tipointeresado']==''){$sError=$ERR['saiu21tipointeresado'].$sSepara.$sError;}
		if ($DATA['saiu21idsolicitante']==0){$sError=$ERR['saiu21idsolicitante'].$sSepara.$sError;}
		if ($DATA['saiu21minuto']==''){$sError=$ERR['saiu21minuto'].$sSepara.$sError;}
		if ($DATA['saiu21hora']==''){$sError=$ERR['saiu21hora'].$sSepara.$sError;}
		if ($DATA['saiu21dia']==''){$sError=$ERR['saiu21dia'].$sSepara.$sError;}
		//if ($DATA['saiu21hora']==''){$DATA['saiu21hora']=fecha_hora();}
		if ($sError!=''){$DATA['saiu21estado']=2;}
		$sErrorCerrando=$sError;
		$sError='';
		//Fin de las valiaciones NO LLAVE.
		}
	if (true){
		switch($DATA['saiu21estado']){
			case 7: //Logra cerrar
			case 8: //Solicitud abandonada
			case 9: //Cancelada por el usuario
			if (trim($DATA['saiu21minutofin'])==''){$DATA['saiu21minutofin']=fecha_minuto();}
			if (trim($DATA['saiu21horafin'])==''){$DATA['saiu21horafin']=fecha_hora();}
			//$DATA['saiu21minutofin']=fecha_minuto();
			//$DATA['saiu21horafin']=fecha_hora();
			break; 
			default:
			$DATA['saiu21estado']=2;
			if ($DATA['saiu21hora']==''){$DATA['saiu21hora']=fecha_hora();}
			if ($DATA['saiu21minuto']==''){$DATA['saiu21minuto']=fecha_minuto();}
			if ($DATA['saiu21horafin']==''){$DATA['saiu21horafin']=0;}
			if ($DATA['saiu21minutofin']==''){$DATA['saiu21minutofin']=0;}
			break;
			}
		if ($DATA['saiu21clasesolicitud']==''){$DATA['saiu21clasesolicitud']=0;}
		if ($DATA['saiu21tiposolicitud']==''){$DATA['saiu21tiposolicitud']=0;}
		if ($DATA['saiu21temasolicitud']==''){$DATA['saiu21temasolicitud']=0;}
		if ($DATA['saiu21idzona']==''){$DATA['saiu21idzona']=0;}
		if ($DATA['saiu21idcentro']==''){$DATA['saiu21idcentro']=0;}
		if ($DATA['saiu21idescuela']==''){$DATA['saiu21idescuela']=0;}
		if ($DATA['saiu21idprograma']==''){$DATA['saiu21idprograma']=0;}
		if ($DATA['saiu21idperiodo']==''){$DATA['saiu21idperiodo']=0;}
		}
	//Valiaciones de campos obligatorios en todo guardar.
	if ($DATA['saiu21tiporadicado']==''){$sError=$ERR['saiu21tiporadicado'];}
	if ($DATA['saiu21mes']==''){$sError=$ERR['saiu21mes'];}
	if ($DATA['saiu21agno']==''){$sError=$ERR['saiu21agno'];}
	// -- Tiene un cerrado.
	$iDiaIni=($DATA['saiu21agno']*10000)+($DATA['saiu21mes']*100)+$DATA['saiu21dia'];
	if ($bConCierre){
		//Validaciones previas a cerrar
		if ($DATA['saiu21estado']==7){
			list($DATA['saiu21tiemprespdias'], $DATA['saiu21tiempresphoras'], $DATA['saiu21tiemprespminutos'])=Tiempo_MinutosCalendario($iDiaIni, $DATA['saiu21hora'], $DATA['saiu21minuto'], $iDiaIni, $DATA['saiu21horafin'], $DATA['saiu21minutofin']);
			}
		//Aprobó las Validaciones al cerrar
		if ($sError.$sErrorCerrando!=''){
			$DATA['saiu21estado']=2;
			$sErrorCerrando=$sError.' '.$sErrorCerrando;
			$sError='';
			}else{
			$bCerrando=true;
			//Acciones del cierre
			}
		}
	// -- Fin del cerrado.
	// -- Se verifican los valores de campos de otras tablas.
	$sTabla21='saiu21directa_'.$DATA['saiu21agno'];
	if ($DATA['saiu21idresponsable_doc']!=''){
		if ($sError==''){$sError=tabla_terceros_existe($DATA['saiu21idresponsable_td'], $DATA['saiu21idresponsable_doc'], $objDB, 'El tercero Responsable ');}
		if ($sError==''){
			list($sError, $sInfo)=tercero_Bloqueado($DATA['saiu21idresponsable'], $objDB);
			if ($sInfo!=''){$sError=$sError.'<br>'.$sInfo;}
			}
		}
	if ($DATA['saiu21idsolicitante_doc']!=''){
		if ($sError==''){$sError=tabla_terceros_existe($DATA['saiu21idsolicitante_td'], $DATA['saiu21idsolicitante_doc'], $objDB, 'El tercero Solicitante ');}
		if ($sError==''){
			list($sError, $sInfo)=tercero_Bloqueado($DATA['saiu21idsolicitante'], $objDB);
			if ($sInfo!=''){$sError=$sError.'<br>'.$sInfo;}
			}
		}
	$bQuitarCodigo=false;
	$sCampoCodigo='';
	if ($sError==''){
		if ($DATA['paso']==10){
			if ($DATA['saiu21consec']==''){
				$DATA['saiu21consec']=tabla_consecutivo($sTabla21, 'saiu21consec', 'saiu21agno='.$DATA['saiu21agno'].' AND saiu21mes='.$DATA['saiu21mes'].' AND saiu21tiporadicado='.$DATA['saiu21tiporadicado'].'', $objDB);
				if ($DATA['saiu21consec']==-1){$sError=$objDB->serror;}
				$bQuitarCodigo=true;
				$sCampoCodigo='saiu21consec';
				}else{
				if (!seg_revisa_permiso($iCodModulo, 8, $objDB)){
					$sError=$ERR['8'];
					$DATA['saiu21consec']='';
					}
				}
			if ($sError==''){
				$sSQL='SELECT 1 FROM '.$sTabla21.' WHERE saiu21agno='.$DATA['saiu21agno'].' AND saiu21mes='.$DATA['saiu21mes'].' AND saiu21tiporadicado='.$DATA['saiu21tiporadicado'].' AND saiu21consec='.$DATA['saiu21consec'].'';
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
			$DATA['saiu21id']=tabla_consecutivo($sTabla21,'saiu21id', '', $objDB);
			if ($DATA['saiu21id']==-1){$sError=$objDB->serror;}
			}
		}
	if ($sError==''){
		//Encontrar la clase
		$sSQL='SELECT saiu02clasesol FROM saiu02tiposol WHERE saiu02id='.$DATA['saiu21tiposolicitud'].'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$fila=$objDB->sf($tabla);
			$DATA['saiu21clasesolicitud']=$fila['saiu02clasesol'];
			}
		}
	$idSolicitantePrevio=0;
	if ($sError==''){
		if (get_magic_quotes_gpc()==1){$DATA['saiu21detalle']=stripslashes($DATA['saiu21detalle']);}
		//Si el campo saiu21detalle permite html quite la linea htmlspecialchars para el campo y habilite la siguiente linea:
		//$saiu21detalle=addslashes($DATA['saiu21detalle']);
		$saiu21detalle=str_replace('"', '\"', $DATA['saiu21detalle']);
		if (get_magic_quotes_gpc()==1){$DATA['saiu21respuesta']=stripslashes($DATA['saiu21respuesta']);}
		//$saiu21respuesta=addslashes($DATA['saiu21respuesta']);
		$saiu21respuesta=str_replace('"', '\"', $DATA['saiu21respuesta']);
		$bpasa=false;
		if ($DATA['paso']==10){
			$DATA['saiu21idpqrs']=0;
			$DATA['saiu21tiemprespdias']=0;
			$DATA['saiu21tiempresphoras']=0;
			$DATA['saiu21tiemprespminutos']=0;
			$DATA['saiu21idcaso']=0;
			$sCampos3021='saiu21agno, saiu21mes, saiu21tiporadicado, saiu21consec, saiu21id, 
			saiu21dia, saiu21hora, saiu21minuto, saiu21estado, saiu21idsolicitante, 
			saiu21tipointeresado, saiu21clasesolicitud, saiu21tiposolicitud, saiu21temasolicitud, saiu21idzona, 
			saiu21idcentro, saiu21codpais, saiu21coddepto, saiu21codciudad, saiu21idescuela, 
			saiu21idprograma, saiu21idperiodo, saiu21idpqrs, saiu21detalle, 
			saiu21horafin, saiu21minutofin, saiu21paramercadeo, saiu21idresponsable, saiu21tiemprespdias, 
			saiu21tiempresphoras, saiu21tiemprespminutos, saiu21solucion, saiu21idcaso, saiu21respuesta';
			$sValores3021=''.$DATA['saiu21agno'].', '.$DATA['saiu21mes'].', '.$DATA['saiu21tiporadicado'].', '.$DATA['saiu21consec'].', '.$DATA['saiu21id'].', 
			'.$DATA['saiu21dia'].', '.$DATA['saiu21hora'].', '.$DATA['saiu21minuto'].', '.$DATA['saiu21estado'].', '.$DATA['saiu21idsolicitante'].', 
			'.$DATA['saiu21tipointeresado'].', '.$DATA['saiu21clasesolicitud'].', '.$DATA['saiu21tiposolicitud'].', '.$DATA['saiu21temasolicitud'].', '.$DATA['saiu21idzona'].', 
			'.$DATA['saiu21idcentro'].', "'.$DATA['saiu21codpais'].'", "'.$DATA['saiu21coddepto'].'", "'.$DATA['saiu21codciudad'].'", '.$DATA['saiu21idescuela'].', 
			'.$DATA['saiu21idprograma'].', '.$DATA['saiu21idperiodo'].', '.$DATA['saiu21idpqrs'].', "'.$saiu21detalle.'", 
			'.$DATA['saiu21horafin'].', '.$DATA['saiu21minutofin'].', '.$DATA['saiu21paramercadeo'].', '.$DATA['saiu21idresponsable'].', '.$DATA['saiu21tiemprespdias'].', 
			'.$DATA['saiu21tiempresphoras'].', '.$DATA['saiu21tiemprespminutos'].', '.$DATA['saiu21solucion'].', '.$DATA['saiu21idcaso'].', "'.$saiu21respuesta.'"';
			if ($APP->utf8==1){
				$sSQL='INSERT INTO '.$sTabla21.' ('.$sCampos3021.') VALUES ('.utf8_encode($sValores3021).');';
				$sdetalle=$sCampos3021.'['.utf8_encode($sValores3021).']';
				}else{
				$sSQL='INSERT INTO '.$sTabla21.' ('.$sCampos3021.') VALUES ('.$sValores3021.');';
				$sdetalle=$sCampos3021.'['.$sValores3021.']';
				}
			$idAccion=2;
			$bpasa=true;
			}else{
			$scampo[1]='saiu21dia';
			$scampo[2]='saiu21hora';
			$scampo[3]='saiu21minuto';
			$scampo[4]='saiu21idsolicitante';
			$scampo[5]='saiu21tipointeresado';
			$scampo[6]='saiu21clasesolicitud';
			$scampo[7]='saiu21temasolicitud';
			$scampo[8]='saiu21idzona';
			$scampo[9]='saiu21idcentro';
			$scampo[10]='saiu21codpais';
			$scampo[11]='saiu21coddepto';
			$scampo[12]='saiu21codciudad';
			$scampo[13]='saiu21idescuela';
			$scampo[14]='saiu21idprograma';
			$scampo[15]='saiu21idperiodo';
			$scampo[16]='saiu21detalle';
			$scampo[17]='saiu21horafin';
			$scampo[18]='saiu21minutofin';
			$scampo[19]='saiu21paramercadeo';
			$scampo[20]='saiu21idresponsable';
			$scampo[21]='saiu21solucion';
			$scampo[22]='saiu21tiposolicitud';
			$scampo[23]='saiu21estado';
			$scampo[24]='saiu21tiemprespdias';
			$scampo[25]='saiu21tiempresphoras';
			$scampo[26]='saiu21tiemprespminutos';
			$scampo[27]='saiu21respuesta';
			$sdato[1]=$DATA['saiu21dia'];
			$sdato[2]=$DATA['saiu21hora'];
			$sdato[3]=$DATA['saiu21minuto'];
			$sdato[4]=$DATA['saiu21idsolicitante'];
			$sdato[5]=$DATA['saiu21tipointeresado'];
			$sdato[6]=$DATA['saiu21clasesolicitud'];
			$sdato[7]=$DATA['saiu21temasolicitud'];
			$sdato[8]=$DATA['saiu21idzona'];
			$sdato[9]=$DATA['saiu21idcentro'];
			$sdato[10]=$DATA['saiu21codpais'];
			$sdato[11]=$DATA['saiu21coddepto'];
			$sdato[12]=$DATA['saiu21codciudad'];
			$sdato[13]=$DATA['saiu21idescuela'];
			$sdato[14]=$DATA['saiu21idprograma'];
			$sdato[15]=$DATA['saiu21idperiodo'];
			$sdato[16]=$saiu21detalle;
			$sdato[17]=$DATA['saiu21horafin'];
			$sdato[18]=$DATA['saiu21minutofin'];
			$sdato[19]=$DATA['saiu21paramercadeo'];
			$sdato[20]=$DATA['saiu21idresponsable'];
			$sdato[21]=$DATA['saiu21solucion'];
			$sdato[22]=$DATA['saiu21tiposolicitud'];
			$sdato[23]=$DATA['saiu21estado'];
			$sdato[24]=$DATA['saiu21tiemprespdias'];
			$sdato[25]=$DATA['saiu21tiempresphoras'];
			$sdato[26]=$DATA['saiu21tiemprespminutos'];
			$sdato[27]=$saiu21respuesta;
			$numcmod=27;
			$sWhere='saiu21id='.$DATA['saiu21id'].'';
			$sSQL='SELECT * FROM '.$sTabla21.' WHERE '.$sWhere;
			$sdatos='';
			$bPrimera=true;
			$result=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($result)>0){
				$filabase=$objDB->sf($result);
				if ($DATA['saiu21idsolicitante']!=$filabase['saiu21idsolicitante']){
					$idSolicitantePrevio=$filabase['saiu21idsolicitante'];
					}
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
					$sSQL='UPDATE '.$sTabla21.' SET '.utf8_encode($sdatos).' WHERE '.$sWhere.';';
					}else{
					$sdetalle=$sdatos.'['.$sWhere.']';
					$sSQL='UPDATE '.$sTabla21.' SET '.$sdatos.' WHERE '.$sWhere.';';
					}
				$idAccion=3;
				}
			}
		if ($bpasa){
			if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Guardar 3021 '.$sSQL.'<br>';}
			$result=$objDB->ejecutasql($sSQL);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' [3021] ..<!-- '.$sSQL.' -->';
				if ($idAccion==2){
					$DATA['saiu21id']='';
					$bQuitarCodigo=true;
					$DATA['paso']=0;
					}else{
					$DATA['paso']=2;
					}
				$bCerrando=false;
				}else{
				if ($bAudita[$idAccion]){seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], $idAccion, $DATA['saiu21id'], $sdetalle, $objDB);}
				$DATA['paso']=2;
				//Registrar en el inventario.
				$valores3000[2]=$iCodModulo;
				$valores3000[3]=$DATA['saiu21agno'];
				$valores3000[4]=$DATA['saiu21id'];
				if ($idSolicitantePrevio!=0){
					//Retirar al anterior.
					$valores3000[1]=$idSolicitantePrevio;
					f3000_Retirar($valores3000, $objDB, $bDebug);
					}
				if ($DATA['saiu21idsolicitante']!=0){
					$valores3000[1]=$DATA['saiu21idsolicitante'];
					$valores3000[5]=$iDiaIni;
					$valores3000[6]=$DATA['saiu21tiposolicitud'];
					$valores3000[7]=$DATA['saiu21temasolicitud'];
					$valores3000[8]=$DATA['saiu21estado'];
					f3000_Registrar($valores3000, $objDB, $bDebug);
					}
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
		$bCerrando=false;
		if ($bQuitarCodigo){
			if ($sCampoCodigo!=''){$DATA[$sCampoCodigo]='';}
			}
		}
	$sInfoCierre='';
	if ($bCerrando){
		list($sErrorCerrando, $sDebugCerrar)=f3021_Cerrar($DATA['saiu21id'], $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugCerrar;
		}
	//if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' InfoDepura<br>';}
	return array($DATA, $sError, $iTipoError, $bCerrando, $sErrorCerrando, $sDebug);
	}
function f3021_db_Eliminar($saiu21agno, $saiu21id, $objDB, $bDebug=false){
	$iCodModulo=3021;
	$bAudita[4]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3021='lg/lg_3021_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3021)){$mensajes_3021='lg/lg_3021_es.php';}
	require $mensajes_todas;
	require $mensajes_3021;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	$saiu21id=numeros_validar($saiu21id);
	// Traer los datos para hacer las validaciones.
	$sTabla21='saiu21directa_'.$saiu21agno;
	if ($sError==''){
		$sSQL='SELECT * FROM '.$sTabla21.' WHERE saiu21id='.$saiu21id.'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$filabase=$objDB->sf($tabla);
			}else{
			$sError='No se encuentra el registro solicitado {Ref: '.$saiu21id.'}';
			}
		}
	if ($sError==''){
		if (!seg_revisa_permiso($iCodModulo, 4, $objDB)){
			$sError=$ERR['4'];
			}
		}
	if ($sError==''){
		$sSQL='SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=3021';
		$tablaor=$objDB->ejecutasql($sSQL);
		while ($filaor=$objDB->sf($tablaor)){
			$sSQL='SELECT '.$filaor['campoid'].' FROM '.$filaor['tabla'].' WHERE '.$filaor['camporev'].'='.$_REQUEST['saiu21id'].' LIMIT 0, 1';
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
		if ($filabase['saiu21idsolicitante']!=0){
			//Retirar al anterior.
			$valores3000[1]=$filabase['saiu21idsolicitante'];
			$valores3000[2]=$iCodModulo;
			$valores3000[3]=$filabase['saiu21agno'];
			$valores3000[4]=$filabase['saiu21id'];
			f3000_Retirar($valores3000, $objDB, $bDebug);
			}
		$sWhere='saiu21id='.$saiu21id.'';
		//$sWhere='saiu21consec='.$filabase['saiu21consec'].' AND saiu21tiporadicado='.$filabase['saiu21tiporadicado'].' AND saiu21mes='.$filabase['saiu21mes'].' AND saiu21agno='.$filabase['saiu21agno'].'';
		$sSQL='DELETE FROM '.$sTabla21.' WHERE '.$sWhere.';';
		$result=$objDB->ejecutasql($sSQL);
		if ($result==false){
			$sError=$ERR['falla_eliminar'].' .. <!-- '.$sSQL.' -->';
			}else{
			if ($bAudita[4]){seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $saiu21id, $sWhere, $objDB);}
			}
		}
	return array($sError, $iTipoError, $sDebug);
	}
function f3021_TituloBusqueda(){
	return 'B&uacute;squeda de Registro de Atenci&oacute;n Presencial';
	}
function f3021_ParametrosBusqueda(){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3021='lg/lg_3021_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3021)){$mensajes_3021='lg/lg_3021_es.php';}
	require $mensajes_todas;
	require $mensajes_3021;
	$sParams='<label class="Label90">
	'.$ETI['msg_bnombre'].'
	</label>
	<label>
	<input id="b3021nombre" name="b3021nombre" type="text" value="" onchange="paginarbusqueda()" />
	</label>';
	return $sParams;
	}
function f3021_JavaScriptBusqueda($iModuloBusca){
	$sRes='var sCampo=window.document.frmedita.scampobusca.value;
	var params=new Array();
	params[100]=sCampo;
	params[101]=window.document.frmedita.paginabusqueda.value;
	params[102]=window.document.frmedita.lppfbusqueda.value;
	params[103]=window.document.frmedita.b3021nombre.value;
	xajax_f'.$iModuloBusca.'_HtmlBusqueda(params);';
	return $sRes;
	}
function f3021_TablaDetalleBusquedas($aParametros, $objDB){
	$res='';
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3021='lg/lg_3021_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3021)){$mensajes_3021='lg/lg_3021_es.php';}
	require $mensajes_todas;
	require $mensajes_3021;
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
		return array($sLeyenda.'<input id="paginaf3021" name="paginaf3021" type="hidden" value="'.$pagina.'"/><input id="lppf3021" name="lppf3021" type="hidden" value="'.$lineastabla.'"/>', $sDebug);
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
	$sTitulos='Agno, Mes, Tiporadicado, Consec, Id, Dia, Hora, Minuto, Estado, Telefono, Numtelefono, Solicitante, Tipointeresado, Clasesolicitud, Tiposolicitud, Temasolicitud, Zona, Centro, Codpais, Coddepto, Codciudad, Escuela, Programa, Periodo, Numorigen, Pqrs, Detalle, Horafin, Minutofin, Paramercadeo, Responsable, Tiemprespdias, Tiempresphoras, Tiemprespminutos';
	$sSQL='SELECT TB.saiu21agno, TB.saiu21mes, T3.saiu16nombre, TB.saiu21consec, TB.saiu21id, TB.saiu21dia, TB.saiu21hora, TB.saiu21minuto, T9.saiu11nombre, T10.saiu22nombre, T12.unad11razonsocial AS C12_nombre, T13.bita07nombre, T14.saiu01titulo, T15.saiu02titulo, T16.saiu03titulo, T17.unad23nombre, T18.unad24nombre, T19.unad18nombre, T20.unad19nombre, T21.unad20nombre, T22.core12nombre, T23.core09nombre, T24.exte02nombre, TB.saiu21idpqrs, TB.saiu21detalle, TB.saiu21horafin, TB.saiu21minutofin, TB.saiu21paramercadeo, T31.unad11razonsocial AS C31_nombre, TB.saiu21tiemprespdias, TB.saiu21tiempresphoras, TB.saiu21tiemprespminutos, TB.saiu21tiporadicado, TB.saiu21estado, TB.saiu21idsolicitante, T12.unad11tipodoc AS C12_td, T12.unad11doc AS C12_doc, TB.saiu21tipointeresado, TB.saiu21clasesolicitud, TB.saiu21tiposolicitud, TB.saiu21temasolicitud, TB.saiu21idzona, TB.saiu21idcentro, TB.saiu21codpais, TB.saiu21coddepto, TB.saiu21codciudad, TB.saiu21idescuela, TB.saiu21idprograma, TB.saiu21idperiodo, TB.saiu21idresponsable, T31.unad11tipodoc AS C31_td, T31.unad11doc AS C31_doc 
FROM saiu21directa AS TB, saiu16tiporadicado AS T3, saiu11estadosol AS T9, saiu22telefonos AS T10, unad11terceros AS T12, bita07tiposolicitante AS T13, saiu01claseser AS T14, saiu02tiposol AS T15, saiu03temasol AS T16, unad23zona AS T17, unad24sede AS T18, unad18pais AS T19, unad19depto AS T20, unad20ciudad AS T21, core12escuela AS T22, core09programa AS T23, exte02per_aca AS T24, unad11terceros AS T31 
WHERE '.$sSQLadd1.' TB.saiu21tiporadicado=T3.saiu16id AND TB.saiu21estado=T9.saiu11id AND TB.saiu21idsolicitante=T12.unad11id AND TB.saiu21tipointeresado=T13.bita07id AND TB.saiu21clasesolicitud=T14.saiu01id AND TB.saiu21tiposolicitud=T15.saiu02id AND TB.saiu21temasolicitud=T16.saiu03id AND TB.saiu21idzona=T17.unad23id AND TB.saiu21idcentro=T18.unad24id AND TB.saiu21codpais=T19.unad18codigo AND TB.saiu21coddepto=T20.unad19codigo AND TB.saiu21codciudad=T21.unad20codigo AND TB.saiu21idescuela=T22.core12id AND TB.saiu21idprograma=T23.core09id AND TB.saiu21idperiodo=T24.exte02id AND TB.saiu21idresponsable=T31.unad11id '.$sSQLadd.'
ORDER BY TB.saiu21agno, TB.saiu21mes, TB.saiu21tiporadicado, TB.saiu21consec';
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
			//return array(utf8_encode($sErrConsulta.'<input id="paginaf3021" name="paginaf3021" type="hidden" value="'.$pagina.'"/><input id="lppf3021" name="lppf3021" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
			}
		if ((($registros-1)/$lineastabla)<($pagina-1)){$pagina=(int)(($registros-1)/$lineastabla)+1;}
		if ($registros>$lineastabla){
			$rbase=($pagina-1)*$lineastabla;
			$limite=' LIMIT '.$rbase.', '.$lineastabla;
			$tabladetalle=$objDB->ejecutasql($sSQL.$limite);
			}
		}
	$res=$sErrConsulta.$sLeyenda.'<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">
<thead class="fondoazul"><tr>
<td><b>'.$ETI['saiu21agno'].'</b></td>
<td><b>'.$ETI['saiu21mes'].'</b></td>
<td><b>'.$ETI['saiu21tiporadicado'].'</b></td>
<td><b>'.$ETI['saiu21consec'].'</b></td>
<td><b>'.$ETI['saiu21dia'].'</b></td>
<td><b>'.$ETI['saiu21hora'].'</b></td>
<td><b>'.$ETI['saiu21estado'].'</b></td>
<td colspan="2"><b>'.$ETI['saiu21idsolicitante'].'</b></td>
<td><b>'.$ETI['saiu21tipointeresado'].'</b></td>
<td><b>'.$ETI['saiu21clasesolicitud'].'</b></td>
<td><b>'.$ETI['saiu21tiposolicitud'].'</b></td>
<td><b>'.$ETI['saiu21temasolicitud'].'</b></td>
<td><b>'.$ETI['saiu21idzona'].'</b></td>
<td><b>'.$ETI['saiu21idcentro'].'</b></td>
<td><b>'.$ETI['saiu21codpais'].'</b></td>
<td><b>'.$ETI['saiu21coddepto'].'</b></td>
<td><b>'.$ETI['saiu21codciudad'].'</b></td>
<td><b>'.$ETI['saiu21idescuela'].'</b></td>
<td><b>'.$ETI['saiu21idprograma'].'</b></td>
<td><b>'.$ETI['saiu21idperiodo'].'</b></td>
<td><b>'.$ETI['saiu21idpqrs'].'</b></td>
<td><b>'.$ETI['saiu21detalle'].'</b></td>
<td><b>'.$ETI['saiu21horafin'].'</b></td>
<td><b>'.$ETI['saiu21paramercadeo'].'</b></td>
<td colspan="2"><b>'.$ETI['saiu21idresponsable'].'</b></td>
<td><b>'.$ETI['saiu21tiemprespdias'].'</b></td>
<td><b>'.$ETI['saiu21tiempresphoras'].'</b></td>
<td><b>'.$ETI['saiu21tiemprespminutos'].'</b></td>
<td align="right">
'.html_paginador('paginabusqueda', $registros, $lineastabla, $pagina, 'paginarbusqueda()').'
'.html_lpp('lppfbusqueda', $lineastabla, 'paginarbusqueda()').'
</td>
</tr></thead>';
	$tlinea=1;
	while($filadet=$objDB->sf($tabladetalle)){
		$sPrefijo='<a href="javascript:Devuelve(\''.$filadet['saiu21id'].'\');">';
		$sSufijo='</a>';
		$tlinea++;
		$et_saiu21hora=html_TablaHoraMin($filadet['saiu21hora'], $filadet['saiu21minuto']);
		$et_saiu21horafin=html_TablaHoraMin($filadet['saiu21horafin'], $filadet['saiu21minutofin']);
		$res=$res.'<tr onmouseover="cambia_color_over(this);" onmouseout="cambia_color_out(this);">
<td>'.$sPrefijo.$filadet['saiu21agno'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu21mes'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['saiu16nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu21consec'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu21dia'].$sSufijo.'</td>
<td>'.$sPrefijo.$et_saiu21hora.$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['saiu11nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['saiu22nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['C12_td'].' '.$filadet['C12_doc'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['C12_nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['bita07nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['saiu01titulo']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['saiu02titulo']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['saiu03titulo']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['unad23nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['unad24nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu21codpais'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu21coddepto'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu21codciudad'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['core12nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['core09nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['exte02nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu21idpqrs'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu21detalle'].$sSufijo.'</td>
<td>'.$sPrefijo.$et_saiu21horafin.$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu21paramercadeo'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['C31_td'].' '.$filadet['C31_doc'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['C31_nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu21tiemprespdias'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu21tiempresphoras'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu21tiemprespminutos'].$sSufijo.'</td>
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