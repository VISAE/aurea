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
	$objResponse->call('$("#saiu21idcentro").chosen({width:"100%"})');
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
	$html_saiu21codciudad=f3021_HTMLComboV2_saiu21codciudad($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_saiu21coddepto', 'innerHTML', $html_saiu21coddepto);
	$objResponse->call('$("#saiu21coddepto").chosen({width:"100%"})');
	$objResponse->assign('div_saiu21codciudad', 'innerHTML', $html_saiu21codciudad);
	$objResponse->call('$("#saiu21codciudad").chosen({width:"100%"})');
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
	$objResponse->call('$("#saiu21codciudad").chosen({width:"100%"})');
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
	if (isset($aParametros[103])==0){$aParametros[103]='';}
	if (isset($aParametros[104])==0){$aParametros[104]='';}
	if (isset($aParametros[105])==0){$aParametros[105]='';}
	if (isset($aParametros[106])==0){$aParametros[106]='';}
	if (isset($aParametros[107])==0){$aParametros[107]='';}
	if (isset($aParametros[108])==0){$aParametros[108]='';}
	if (isset($aParametros[109])==0){$aParametros[109]='';}
	//$aParametros[103]=numeros_validar($aParametros[103]);
	$idTercero=$aParametros[100];
	$sDebug='';
	$pagina=$aParametros[101];
	$lineastabla=$aParametros[102];
	$sNombre = $aParametros[103];
	$iAgno=$aParametros[104];
	$iEstado = $aParametros[105];
	$bListar = $aParametros[106];
	$bdoc = $aParametros[107];
	$bcategoria = $aParametros[108];
	$btema = $aParametros[109];
	$bAbierta=true;
	//$sSQL='SELECT Campo FROM Tabla WHERE Id='.$sValorId;
	//$tabla=$objDB->ejecutasql($sSQL);
	//if ($objDB->nf($tabla)>0){
		//$fila=$objDB->sf($tabla);
		//if ($fila['Campo']!='S'){$bAbierta=true;}
		//}
	$sLeyenda='';
	//Verificamos que exista la tabla.
	if ($iAgno == '') {
		$sLeyenda = 'No ha seleccionado un a&ntilde;o a consultar';
	}
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
	$aCategoria=array();
	$sSQL = 'SELECT saiu02id AS id, saiu02titulo AS nombre FROM saiu02tiposol';
	$tabla=$objDB->ejecutasql($sSQL);
	while($fila=$objDB->sf($tabla)){
		$aCategoria[$fila['id']]=cadena_notildes($fila['nombre']);
		}
	$aTema=array();
	$sSQL = 'SELECT saiu03id AS id, saiu03titulo AS nombre FROM saiu03temasol';
	$tabla=$objDB->ejecutasql($sSQL);
	while($fila=$objDB->sf($tabla)){
		$aTema[$fila['id']]=cadena_notildes($fila['nombre']);
		}
	$sSQLadd='';
	$sSQLadd1='';
	if ($iEstado !== '') {
		$sSQLadd = $sSQLadd . ' AND TB.saiu21estado=' . $iEstado . '';
	}
	switch($bListar){
		case 1:
			$sSQLadd=$sSQLadd.' AND TB.saiu21idresponsable=' . $idTercero. '';		
			break;
		case 2:
			$sSQLadd=$sSQLadd.' AND TB.saiu21idresponsablecaso=' . $idTercero. '';		
			break;
		case 3:
			$aEquipos = array();
			$sEquipos = '';
			$sSQL = 'SELECT bita27id FROM bita27equipotrabajo WHERE bita27activo=1 AND bita27idlider=' . $idTercero . '';
			$tabla = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla) > 0) {
				while ($fila = $objDB->sf($tabla)) {
					$aEquipos[] = $fila['bita27id'];
				}
			} else {
				$sSQL = 'SELECT bita28idequipotrab FROM bita28eqipoparte WHERE bita28activo="S" AND bita28idtercero=' . $idTercero . '';
				$tabla = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($tabla) > 0) {
					while ($fila = $objDB->sf($tabla)) {
						$aEquipos[] = $fila['bita28idequipotrab'];
					}
				}
			}
			$sEquipos = implode(',', $aEquipos);
			if ($sEquipos != '') {
				$sSQLadd = $sSQLadd . ' AND TB.saiu21idequipocaso IN (' . $sEquipos . ')';
			} else {
				$sSQLadd = $sSQLadd . ' AND TB.saiu21idresponsablecaso=' . $idTercero . '';
			}
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . ' Lider o Colaborador: ' . $sSQL . '<br>';
			}
			break;
	}
	if ($sNombre !== '') {
		$sBase = mb_strtoupper($sNombre);
		$aNoms = explode(' ', $sBase);
		for ($k = 1; $k <= count($aNoms); $k++) {
			$sCadena = $aNoms[$k - 1];
			if ($sCadena != '') {
				$sSQLadd = $sSQLadd . ' AND T11.unad11razonsocial LIKE "%' . $sCadena . '%"';
			}
		}
	}
	if ($bdoc !== '') {
		$sSQLadd = $sSQLadd . ' AND T11.unad11doc LIKE "%' . $bdoc . '%"';
	}	
	if ($bcategoria !== '') {
		$sSQLadd = $sSQLadd . ' AND TB.saiu21tiposolicitud=' . $bcategoria . '';
	}
	if ($btema !== '') {
		$sSQLadd = $sSQLadd . ' AND TB.saiu21temasolicitud=' . $btema . '';
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
FROM saiu21directa_'.$iAgno.' AS TB, unad11terceros AS T12
WHERE '.$sSQLadd1.' TB.saiu21idsolicitante=T12.unad11id '.$sSQLadd.'';
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
	$sSQL='SELECT TB.saiu21agno, TB.saiu21mes, TB.saiu21consec, TB.saiu21id, TB.saiu21dia, TB.saiu21hora, TB.saiu21minuto, 
T12.unad11razonsocial AS C12_nombre, TB.saiu21tiposolicitud, saiu21temasolicitud, TB.saiu21estado, T12.unad11tipodoc AS C12_td, 
T12.unad11doc AS C12_doc, TB.saiu21idsolicitante, TB.saiu21tiporadicado, TB.saiu21solucion 
FROM saiu21directa_'.$iAgno.' AS TB, unad11terceros AS T12
WHERE '.$sSQLadd1.' TB.saiu21idsolicitante=T12.unad11id '.$sSQLadd.'
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
				//return array(cadena_codificar($sErrConsulta.'<input id="paginaf3021" name="paginaf3021" type="hidden" value="'.$pagina.'"/><input id="lppf3021" name="lppf3021" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
<td><b>'.$ETI['saiu21consec'].'</b></td>
<td><b>'.$ETI['saiu21estado'].'</b></td>
<td colspan="2"><b>'.$ETI['saiu21idsolicitante'].'</b></td>
<td><b>'.$ETI['saiu21tiposolicitud'].'</b></td>
<td><b>'.$ETI['saiu21temasolicitud'].'</b></td>
<td><b>'.$ETI['saiu21solucion'].'</b></td>
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
		$sEstado = '';
		if (isset($aEstado[$filadet['saiu21estado']])==0) {
			$sEstado = $ETI['definir'];
		} else {
			$sEstado = $aEstado[$filadet['saiu21estado']];
		}
		$sCategoria = '';
		if (isset($aCategoria[$filadet['saiu21tiposolicitud']])==0) {
			$sCategoria = $ETI['definir'];
		} else {
			$sCategoria = $aCategoria[$filadet['saiu21tiposolicitud']];
		}
		$sTema = '';
		if (isset($aTema[$filadet['saiu21temasolicitud']])==0) {
			$sTema = $ETI['definir'];
		} else {
			$sTema = $aTema[$filadet['saiu21temasolicitud']];
		}
		$sSolucion = '';
		if (isset($asaiu21solucion[$filadet['saiu21solucion']])==0) {
			$sSolucion = $ETI['definir'];
		} else {
			$sSolucion = $asaiu21solucion[$filadet['saiu21solucion']];
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
<td>'.$sPrefijo.$filadet['saiu21consec'].$sSufijo.'</td>
<td>'.$sPrefijo.$sEstado.$sSufijo.'</td>
<td>'.$et_saiu21idsolicitante_doc.'</td>
<td>'.$et_saiu21idsolicitante_nombre.'</td>
<td>'.$sPrefijo.$sCategoria.$sSufijo.'</td>
<td>'.$sPrefijo.$sTema.$sSufijo.'</td>
<td>'.$sPrefijo.$sSolucion.$sSufijo.'</td>
<td>'.$sLink.'</td>
</tr>';
		}
	$res=$res.'</table>';
	$objDB->liberar($tabladetalle);
	return array(cadena_codificar($res), $sDebug);
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
	$DATA['saiuid']=21;
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
	$DATA['saiu21fechafin']=numeros_validar($DATA['saiu21fechafin']);
	$DATA['saiu21horafin']=numeros_validar($DATA['saiu21horafin']);
	$DATA['saiu21minutofin']=numeros_validar($DATA['saiu21minutofin']);
	$DATA['saiu21paramercadeo']=numeros_validar($DATA['saiu21paramercadeo']);
	$DATA['saiu21solucion']=numeros_validar($DATA['saiu21solucion']);
	$DATA['saiu21respuesta']=htmlspecialchars(trim($DATA['saiu21respuesta']));
	$DATA['saiu21idcaso']=numeros_validar($DATA['saiu21idcaso']);
	$DATA['saiu21fecharespcaso']=numeros_validar($DATA['saiu21fecharespcaso']);
	$DATA['saiu21horarespcaso']=numeros_validar($DATA['saiu21horarespcaso']);
	$DATA['saiu21minrespcaso']=numeros_validar($DATA['saiu21minrespcaso']);
	$DATA['saiu21idunidadcaso']=numeros_validar($DATA['saiu21idunidadcaso']);
	$DATA['saiu21idequipocaso']=numeros_validar($DATA['saiu21idequipocaso']);
	$DATA['saiu21idsupervisorcaso']=numeros_validar($DATA['saiu21idsupervisorcaso']);
	$DATA['saiu21idresponsablecaso']=numeros_validar($DATA['saiu21idresponsablecaso']);
	$DATA['saiu21numref']=htmlspecialchars(trim($DATA['saiu21numref']));
	// -- Se inicializan las variables que puedan pasar vacias {Especialmente números}.
	//if ($DATA['saiu21dia']==''){$DATA['saiu21dia']=0;}
	//if ($DATA['saiu21hora']==''){$DATA['saiu21hora']=0;}
	//if ($DATA['saiu21minuto']==''){$DATA['saiu21minuto']=0;}
	if ($DATA['saiu21estado']==''){$DATA['saiu21estado']=0;}
	if ($DATA['saiu21estadoorigen']==''){$DATA['saiu21estadoorigen']=0;}
	//if ($DATA['saiu21tipointeresado']==''){$DATA['saiu21tipointeresado']=0;}
	if ($DATA['saiu21idpqrs']==''){$DATA['saiu21idpqrs']=0;}
	//if ($DATA['saiu21paramercadeo']==''){$DATA['saiu21paramercadeo']=0;}
	//if ($DATA['saiu21solucion']==''){$DATA['saiu21solucion']=0;}
	// -- Seccion para validar los posibles causales de error.
	$sSepara=', ';
	$bConCierre=false;
	$bEnviaEncuesta=false;
	$bEnviaCaso=false;
	if ($DATA['saiu21temasolicitud']==''){$sError=$ERR['saiu21temasolicitud'].$sSepara.$sError;}
	if ($DATA['saiu21tiposolicitud']==''){$sError=$ERR['saiu21tiposolicitud'].$sSepara.$sError;}
	// if ($DATA['saiu21clasesolicitud']==''){$sError=$ERR['saiu21clasesolicitud'].$sSepara.$sError;}
	if ($DATA['saiu21tipointeresado']==''){$sError=$ERR['saiu21tipointeresado'].$sSepara.$sError;}
	if ($DATA['saiu21idsolicitante']==0){$sError=$ERR['saiu21idsolicitante'].$sSepara.$sError;}
	if ($DATA['saiu21minuto']==''){$sError=$ERR['saiu21minuto'].$sSepara.$sError;}
	if ($DATA['saiu21hora']==''){$sError=$ERR['saiu21hora'].$sSepara.$sError;}
	if ($DATA['saiu21dia']==''){$sError=$ERR['saiu21dia'].$sSepara.$sError;}
	if ($DATA['saiu21mes']==''){$sError=$ERR['saiu21mes'].$sSepara.$sError;}
	if ($DATA['saiu21agno']==''){$sError=$ERR['saiu21agno'].$sSepara.$sError;}
	if ($DATA['saiu21idcentro']==''){$sError=$ERR['saiu21idcentro'].$sSepara.$sError;}
	if ($DATA['saiu21idzona']==''){$sError=$ERR['saiu21idzona'].$sSepara.$sError;}
	if ($DATA['saiu21detalle']==''){$sError=$ERR['saiu21detalle'].$sSepara.$sError;}
	if ($DATA['saiu21fecharespcaso']==''){$DATA['saiu21fecharespcaso']=0;}
	if ($DATA['saiu21estado']==1 || $DATA['saiu21estado']==7){
		if ($DATA['saiu21estado']==7) {
			$bConCierre=true;
		}
		if ($DATA['saiu21solucion']=='') {
			$sError=$ERR['saiu21solucion'].$sSepara.$sError;
			}else{
			if ((int)$DATA['saiu21solucion']==0){
				$sError=$ERR['saiu21solucion_proceso'].$sSepara.$sError;
				}
			}
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Estado: '.$DATA['saiu21estado']. ' - Solución: '. $DATA['saiu21solucion'] .'<br>';}
		if ($DATA['saiu21idresponsable']==0){$sError=$ERR['saiu21idresponsable'].$sSepara.$sError;}
		if ($DATA['saiu21paramercadeo']==''){$sError=$ERR['saiu21paramercadeo'].$sSepara.$sError;}
		//if ($DATA['saiu21minutofin']==''){$sError=$ERR['saiu21minutofin'].$sSepara.$sError;}
		//if ($DATA['saiu21horafin']==''){$sError=$ERR['saiu21horafin'].$sSepara.$sError;}
		if ($DATA['saiu21idperiodo']==''){$sError=$ERR['saiu21idperiodo'].$sSepara.$sError;}
		if ($DATA['saiu21idprograma']==''){$sError=$ERR['saiu21idprograma'].$sSepara.$sError;}
		if ($DATA['saiu21idescuela']==''){$sError=$ERR['saiu21idescuela'].$sSepara.$sError;}
		if ($DATA['saiu21codciudad']==''){$sError=$ERR['saiu21codciudad'].$sSepara.$sError;}
		if ($DATA['saiu21coddepto']==''){$sError=$ERR['saiu21coddepto'].$sSepara.$sError;}
		if ($DATA['saiu21codpais']==''){$sError=$ERR['saiu21codpais'].$sSepara.$sError;}
		//if ($DATA['saiu21hora']==''){$DATA['saiu21hora']=fecha_hora();}
		if ($DATA['saiu21solucion']==1){ // Resuelto en la atención
			if ($DATA['saiu21respuesta']==''){$sError=$ERR['saiu21respuesta'].$sSepara.$sError;}
		}
		if ($DATA['saiu21solucion']==3) { // Se inicia caso
			if ($DATA['saiu21temasolicitud'] != $DATA['saiu21temasolicitudorigen']) {
				$DATA['saiu21idresponsablecaso']=0;
			}
			if ($DATA['saiu21idresponsablecaso']==0) {
				list($DATA['saiu21idunidadcaso'], $DATA['saiu21idequipocaso'], $DATA['saiu21idsupervisorcaso'], $DATA['saiu21idresponsablecaso'], $saiu21tiemprespdias, $saiu21tiempresphoras, $sErrorF, $iTipoError, $sDebugF) = f3021_ConsultaResponsable($DATA, $objDB, $bDebug);
				if ($sErrorF!=''){$sError=$sError.'<br>'.$sErrorF;}
				if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta Responsable: '.$sDebugF.'<br>';}
				if ($DATA['saiu21idunidadcaso']==0){$sError=$ERR['saiu21idunidadcaso'].$sSepara.$sError;}
				if ($DATA['saiu21idequipocaso']==0){$sError=$ERR['saiu21idequipocaso'].$sSepara.$sError;}
				if ($DATA['saiu21idsupervisorcaso']==0){$sError=$ERR['saiu21idsupervisorcaso'].$sSepara.$sError;}
				if ($DATA['saiu21idresponsablecaso']==0){$sError=$ERR['saiu21idresponsablecaso'].$sSepara.$sError;}
				if ($sError!='') {
					$DATA['saiu21idunidadcaso']=0;
					$DATA['saiu21idequipocaso']=0;
					$DATA['saiu21idsupervisorcaso']=0;
					$DATA['saiu21idresponsablecaso']=0;
				}
			}
		}
		if ($sError=='') {
			if ($DATA['saiu21solucion']==5) { // Se inicia PQRS
				require $APP->rutacomun . 'lib3005.php';
				require $APP->rutacomun . 'lib3007.php';
				$aParams=array();
				$aParams['iCodModulo']=$iCodModulo;
				$aParams['paso']=10;
				$aParams['saiu05estado']=-1;
				$aParams['saiu05agno']=fecha_agno();
				$aParams['saiu05mes']=fecha_mes();
				$aParams['saiu05dia']=fecha_dia();
				$aParams['saiu05hora']=fecha_hora();
				$aParams['saiu05minuto']=fecha_minuto();
				$aParams['saiu05origenid']='';
				$aParams['saiu05idmedio']='0';
				$aParams['saiu05rptaforma']='';
				$aParams['saiu05idmoduloproc']='';
				$aParams['saiu05identificadormod']='';
				$aParams['bCambiaEst']=0;
				$aParams['saiu05idsolicitante']=$DATA['saiu21idsolicitante'];
				$aParams['saiu05tipointeresado']=$DATA['saiu21tipointeresado'];
				$aParams['saiu05detalle']=$DATA['saiu21detalle'];
				$aParams['saiu05idtiposolorigen']=$DATA['saiu21tiposolicitud'];
				$aParams['saiu05idtemaorigen']=$DATA['saiu21temasolicitud'];
				$aParams['saiu05idcategoria']=1;
				$aParams['saiu05idunidadresp']=0;
				$aParams['saiu05idequiporesp']=0;
				$aParams['saiu05idsupervisor']=0;
				$aParams['saiu05idresponsable']=0;
				$aParams['saiu05idresponsable_doc']='';
				$aParams['saiu05idinteresado_doc']='';
				$aParams['saiu05idsolicitante_doc']='';
				$aParams['saiu05tiporadicado']=1;
				$aParams['saiu05infocomplemento']='';
				$aParams['saiu05respuesta']='';
				$aParams['saiu05consec']='';
				$aParams['saiu05rptacorreo']='';
				$aParams['saiu05rptadireccion']='';
				$aParams['saiu05costogenera']='';
				$aParams['saiu05costovalor']=0;
				$aParams['saiu05costorefpago']='';
				$aParams['saiu05numradicado']='';
				$aParams['saiu05numref']='';
				$aParams['saiu05idinteresado']=0;
				list($aParams, $sErrorF, $iTipoError, $sDebugF)=f3005_db_GuardarV2($aParams, $objDB, $bDebug);
				if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Crea PQRS: '.$sDebugF.'<br>';}
				if ($sErrorF!=''){$sError=$sError.'<br>Errores creando PQRS: '.$sErrorF;}
				if ($sError == '') {
					$DATA['saiu21idpqrs']=$aParams['saiu05id'];
					$DATA['saiu21numref']=$aParams['saiu05numref'];
					if ($DATA['saiu21idpqrs']==-1){$sError=$ERR['saiu21idpqrs'].$sSepara.$sError;}
					if ($DATA['saiu21numref']==''){$sError=$ERR['saiu21numref'].$sSepara.$sError;}
				}
			}
		}
		if ($sError!=''){
			if ($DATA['saiu21estado']!=1) {	// Asignado
				$DATA['saiu21estado']=2;	// En tramite
			}
		}
		$sErrorCerrando=$sError;
		//Fin de las valiaciones NO LLAVE.
		}
	if ($sError==''){
		switch($DATA['saiu21estado']){
			case 1: //Caso Asignado
			break;
			case 7: //Logra cerrar			
			switch($DATA['saiu21solucion']){
				case 1: // Resuelto en la atención
				case 5: // Se inicia PQRS
				$DATA['saiu21fecharespcaso']=0;
				$DATA['saiu21fechafin']=fecha_DiaMod();
				$DATA['saiu21horafin']=fecha_hora();
				$DATA['saiu21minutofin']=fecha_minuto();
				$bEnviaEncuesta=true;
				break;
				case 3: // Se inicia caso
				if ($DATA['saiu21estadoorigen']==1) {
					if ($DATA['saiu21respuesta']==''){
						$sError=$ERR['saiu21respuesta'].$sSepara.$sError;
					} else {
						$DATA['saiu21fecharespcaso']=fecha_DiaMod();
						$DATA['saiu21horarespcaso']=fecha_hora();
						$DATA['saiu21minrespcaso']=fecha_minuto();
						$bEnviaEncuesta=true;
					}
				} else {
					$DATA['saiu21fecharespcaso']=0;
					$DATA['saiu21fechafin']=fecha_DiaMod();
					$DATA['saiu21horafin']=fecha_hora();
					$DATA['saiu21minutofin']=fecha_minuto();
					$iDiaIni=($DATA['saiu21agno']*10000)+($DATA['saiu21mes']*100)+$DATA['saiu21dia'];
					list($DATA['saiu21tiemprespdias'], $DATA['saiu21tiempresphoras'], $DATA['saiu21tiemprespminutos'])=Tiempo_MinutosCalendario($iDiaIni, $DATA['saiu21hora'], $DATA['saiu21minuto'], $DATA['saiu21fechafin'], $DATA['saiu21horafin'], $DATA['saiu21minutofin']);
					$DATA['saiu21idcaso']=(int)(fecha_DiaMod().$DATA['saiu21id'].'');
					$DATA['saiu21respuesta']='';
					$DATA['saiu21estado']=1;
					$bEnviaCaso=true;
				}
				break;
				case 8: // Sin resolver
				$DATA['saiu21respuesta']='';
				break;
				default:
				$sError=$ERR['saiu21solucion'].$sSepara.$sError;
				break;
			}
			break;
			case 8: //Solicitud abandonada
			case 9: //Cancelada por el usuario
			if (trim($DATA['saiu21fechafin'])==''){$DATA['saiu21fechafin']=fecha_DiaMod();}
			if (trim($DATA['saiu21minutofin'])==''){$DATA['saiu21minutofin']=fecha_minuto();}
			if (trim($DATA['saiu21horafin'])==''){$DATA['saiu21horafin']=fecha_hora();}
			//$DATA['saiu21minutofin']=fecha_minuto();
			//$DATA['saiu21horafin']=fecha_hora();
			break; 
			default:
			$DATA['saiu21estado']=2;
			if ($DATA['saiu21hora']==''){$DATA['saiu21hora']=fecha_hora();}
			if ($DATA['saiu21minuto']==''){$DATA['saiu21minuto']=fecha_minuto();}
			if ($DATA['saiu21fechafin']==''){$DATA['saiu21fechafin']=0;}
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
	$iDiaIni=fecha_ArmarNumero($DATA['saiu21dia'],$DATA['saiu21mes'],$DATA['saiu21agno']);
	if ($bConCierre && $sError==''){
		//Validaciones previas a cerrar
		if ($DATA['saiu21estado']==7){
			switch($DATA['saiu21solucion']){
				case 1: // Resuelto en la atención
				case 5: // Se inicia PQRS
				list($DATA['saiu21tiemprespdias'], $DATA['saiu21tiempresphoras'], $DATA['saiu21tiemprespminutos'])=Tiempo_MinutosCalendario($iDiaIni, $DATA['saiu21hora'], $DATA['saiu21minuto'], $DATA['saiu21fechafin'], $DATA['saiu21horafin'], $DATA['saiu21minutofin']);
				break;
			}
		}
		//Aprobó las Validaciones al cerrar
		if ($sError.$sErrorCerrando!=''){
			if ($DATA['saiu21estado']==7) {
				$DATA['saiu21estado'] = $DATA['saiu21estadoorigen'];
			} else if ($DATA['saiu21estado']!=1) {
				$DATA['saiu21estado']=2;
			}
			$sErrorCerrando=$sError.' '.$sErrorCerrando;
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
	if ($sError == '') {
		list($sErrorR, $sDebugR) = f3021_RevTabla_saiu21directa($DATA['saiu21agno'], $objDB);
		$sError = $sError . $sErrorR;
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
		$DATA['saiu21detalle']=stripslashes($DATA['saiu21detalle']);
		//Si el campo saiu21detalle permite html quite la linea htmlspecialchars para el campo y habilite la siguiente linea:
		//$saiu21detalle=addslashes($DATA['saiu21detalle']);
		$saiu21detalle=str_replace('"', '\"', $DATA['saiu21detalle']);
		$DATA['saiu21respuesta']=stripslashes($DATA['saiu21respuesta']);
		//$saiu21respuesta=addslashes($DATA['saiu21respuesta']);
		$saiu21respuesta=str_replace('"', '\"', $DATA['saiu21respuesta']);
		$bpasa=false;
		if ($DATA['paso']==10){
			$DATA['saiu21idpqrs']=0;
			$DATA['saiu21tiemprespdias']=0;
			$DATA['saiu21tiempresphoras']=0;
			$DATA['saiu21tiemprespminutos']=0;
			$DATA['saiu21agno']=fecha_agno();
			$DATA['saiu21mes']=fecha_mes();
			$DATA['saiu21dia']=fecha_dia();
			$DATA['saiu21hora']=fecha_hora();
			$DATA['saiu21minuto']=fecha_minuto();
			$DATA['saiu21idcaso']=0;
			$sCampos3021='saiu21agno, saiu21mes, saiu21tiporadicado, saiu21consec, saiu21id, 
			saiu21dia, saiu21hora, saiu21minuto, saiu21estado, saiu21idsolicitante, 
			saiu21tipointeresado, saiu21clasesolicitud, saiu21tiposolicitud, saiu21temasolicitud, saiu21idzona, 
			saiu21idcentro, saiu21codpais, saiu21coddepto, saiu21codciudad, saiu21idescuela, 
			saiu21idprograma, saiu21idperiodo, saiu21idpqrs, saiu21detalle, saiu21fechafin,
			saiu21horafin, saiu21minutofin, saiu21paramercadeo, saiu21idresponsable, saiu21tiemprespdias, 
			saiu21tiempresphoras, saiu21tiemprespminutos, saiu21solucion, saiu21idcaso, saiu21respuesta';
			$sValores3021=''.$DATA['saiu21agno'].', '.$DATA['saiu21mes'].', '.$DATA['saiu21tiporadicado'].', '.$DATA['saiu21consec'].', '.$DATA['saiu21id'].', 
			'.$DATA['saiu21dia'].', '.$DATA['saiu21hora'].', '.$DATA['saiu21minuto'].', '.$DATA['saiu21estado'].', '.$DATA['saiu21idsolicitante'].', 
			'.$DATA['saiu21tipointeresado'].', '.$DATA['saiu21clasesolicitud'].', '.$DATA['saiu21tiposolicitud'].', '.$DATA['saiu21temasolicitud'].', '.$DATA['saiu21idzona'].', 
			'.$DATA['saiu21idcentro'].', "'.$DATA['saiu21codpais'].'", "'.$DATA['saiu21coddepto'].'", "'.$DATA['saiu21codciudad'].'", '.$DATA['saiu21idescuela'].', 
			'.$DATA['saiu21idprograma'].', '.$DATA['saiu21idperiodo'].', '.$DATA['saiu21idpqrs'].', "'.$saiu21detalle.'", '.$DATA['saiu21fechafin'].', 
			'.$DATA['saiu21horafin'].', '.$DATA['saiu21minutofin'].', '.$DATA['saiu21paramercadeo'].', '.$DATA['saiu21idresponsable'].', '.$DATA['saiu21tiemprespdias'].', 
			'.$DATA['saiu21tiempresphoras'].', '.$DATA['saiu21tiemprespminutos'].', '.$DATA['saiu21solucion'].', '.$DATA['saiu21idcaso'].', "'.$saiu21respuesta.'"';
			if ($APP->utf8==1){
				$sSQL='INSERT INTO '.$sTabla21.' ('.$sCampos3021.') VALUES ('.cadena_codificar($sValores3021).');';
				$sdetalle=$sCampos3021.'['.cadena_codificar($sValores3021).']';
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
			$scampo[28]='saiu21idcaso';
			$scampo[29]='saiu21fecharespcaso';
			$scampo[30]='saiu21horarespcaso';
			$scampo[31]='saiu21minrespcaso';
			$scampo[32]='saiu21idunidadcaso';
			$scampo[33]='saiu21idequipocaso';
			$scampo[34]='saiu21idsupervisorcaso';
			$scampo[35]='saiu21idresponsablecaso';
			$scampo[36]='saiu21idpqrs';
			$scampo[37]='saiu21numref';
			$scampo[38]='saiu21fechafin';
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
			$sdato[28]=$DATA['saiu21idcaso'];
			$sdato[29]=$DATA['saiu21fecharespcaso'];
			$sdato[30]=$DATA['saiu21horarespcaso'];
			$sdato[31]=$DATA['saiu21minrespcaso'];
			$sdato[32]=$DATA['saiu21idunidadcaso'];
			$sdato[33]=$DATA['saiu21idequipocaso'];
			$sdato[34]=$DATA['saiu21idsupervisorcaso'];
			$sdato[35]=$DATA['saiu21idresponsablecaso'];
			$sdato[36]=$DATA['saiu21idpqrs'];
			$sdato[37]=$DATA['saiu21numref'];
			$sdato[38]=$DATA['saiu21fechafin'];
			$numcmod=38;
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
					$sdetalle=cadena_codificar($sdatos).'['.$sWhere.']';
					$sSQL='UPDATE '.$sTabla21.' SET '.cadena_codificar($sdatos).' WHERE '.$sWhere.';';
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
				if ($bEnviaCaso) {
					list($sMensaje, $sErrorE, $sDebugE) = f3000_EnviaCorreosAtencion($DATA, $DATA['saiu21agno'], $objDB, $bDebug, true);
					$sError = $sError . $sErrorE;
					$sDebug = $sDebug . $sDebugE;
					}
				if ($bEnviaEncuesta) {
					list($sMensaje, $sErrorE, $sDebugE) = f3000_EnviaCorreosAtencion($DATA, $DATA['saiu21agno'], $objDB, $bDebug);
					$sError = $sError . $sErrorE;
					$sDebug = $sDebug . $sDebugE;
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
			//return array(cadena_codificar($sErrConsulta.'<input id="paginaf3021" name="paginaf3021" type="hidden" value="'.$pagina.'"/><input id="lppf3021" name="lppf3021" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
	return cadena_codificar($res);
	}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------
function f3021_RevTabla_saiu21directa($sContenedor, $objDB, $bDebug = false)
{
	list($sError, $sDebug) = f3021_RevisarTabla($sContenedor, $objDB, $bDebug);
	return array($sError, $sDebug);
}
function f3021_ConsultaResponsable($DATA, $objDB, $bDebug = false)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_3021 = 'lg/lg_3021_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_3021)) {
		$mensajes_3021 = 'lg/lg_3021_es.php';
	}
	require $mensajes_todas;
	require $mensajes_3021;
	$sError = '';
	$iTipoError = 0;
	$sDebug = '';
	$saiu21idunidadcaso = 0;
	$saiu21idequipocaso = 0;
	$saiu21idsupervisorcaso = 0;
	$saiu21idresponsablecaso = 0;
	$saiu21tiemprespdias = 0;
	$saiu21tiempresphoras = 0;
	if (isset($DATA['saiu21temasolicitud']) == 0) {
		$DATA['saiu21temasolicitud'] = '';
	}
	if ($DATA['saiu21temasolicitud'] == '') {
		$sError = $ERR['saiu21temasolicitud'] . $sSepara . $sError;
	}
	if ($sError == '') {
		$sSQL = 'SELECT saiu03idunidadresp1, saiu03idequiporesp1, saiu03idliderrespon1, saiu03tiemprespdias1, saiu03tiempresphoras1
		FROM saiu03temasol
		WHERE saiu03id = ' . $DATA['saiu21temasolicitud'] . '';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' Consulta responsable solicitud ' . $sSQL . '<br>';
		}
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			$saiu21idunidadcaso = $fila['saiu03idunidadresp1'];
			$saiu21idequipocaso = $fila['saiu03idequiporesp1'];
			$saiu21idsupervisorcaso = $fila['saiu03idliderrespon1'];
			$saiu21idresponsablecaso = $saiu21idsupervisorcaso;
			$saiu21tiemprespdias = $fila['saiu03tiemprespdias1'];
			$saiu21tiempresphoras = $fila['saiu03tiempresphoras1'];
		} else {
			$sError = $sError . 'No se ha configurado el tema de solicitud.';
		}
	}
	return array($saiu21idunidadcaso, $saiu21idequipocaso, $saiu21idsupervisorcaso, $saiu21idresponsablecaso, $saiu21tiemprespdias, $saiu21tiempresphoras, $sError, $iTipoError, $sDebug);
}
function f3021_ActualizarAtiende($DATA, $objDB, $bDebug = false, $idTercero)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_3021 = 'lg/lg_3021_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_3021)) {
		$mensajes_3021 = 'lg/lg_3021_es.php';
	}
	require $mensajes_todas;
	require $mensajes_3021;
	$sTabla21 = 'saiu21directa_' . $DATA['saiu21agno'];
	$sResultado = '';
	$sError = '';
	$sDebug = '';
	$iTipoError = 0;
	if (!$objDB->bexistetabla($sTabla21)) {
		$sError = $sError . 'No ha sido posible acceder al contenedor de datos';
	}
	if ($sError == '') {
		$sSQL = 'SELECT saiu21agno, saiu21mes, saiu21estado, saiu21temasolicitud FROM ' . $sTabla21 . ' WHERE saiu21id=' . $DATA['saiu21id'] . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			list($DATA['saiu21idunidadcaso'], $DATA['saiu21idequipocaso'], $DATA['saiu21idsupervisorcaso'], $DATA['saiu21idresponsablecaso'], $saiu21tiemprespdias, $saiu21tiempresphoras, $sErrorF, $iTipoError, $sDebugF) = f3021_ConsultaResponsable($fila, $objDB, $bDebug);
			$sError = $sError . $sErrorF;
			if ($bDebug) {
				$sDebug = $sDebug . $sDebugF;
			}
		} else {
			$sError = $sError . $ETI['saiu21noexiste'];
		}
	}
	if ($sError == '') {
		if ($saiu21tiemprespdias > 0) { // Cálculo fecha probable de respuesta
			$iFechaBase = fecha_DiaMod();
			$saiu21fecharespprob = fecha_NumSumarDias($iFechaBase, $saiu21tiemprespdias);
		}
		list($DATA, $sErrorE, $iTipoError, $sDebugGuardar) = f3021_db_GuardarV2($DATA, $objDB, $bDebug, $idTercero);
		$sError = $sError . $sErrorE;
		$sDebug = $sDebug . $sDebugGuardar;
		if ($sError == '') {
			$sError = '<b>' . $ETI['msg_itemguardado'] . '</b>';
			$iTipoError = 1;
		}
	}
	return array($DATA, $sError, $iTipoError, $sDebug);
}
function elimina_archivo_saiu21idarchivo($idpadre, $iAgno)
{
	$_SESSION['u_ultimominuto'] = iminutoavance();
	require './app.php';
	$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto != '') {
		$objDB->dbPuerto = $APP->dbpuerto;
	}
	$objDB->xajax();
	$sTabla21 = 'saiu21directa_' . $iAgno;
	archivo_eliminar($sTabla21, 'saiu21id', 'saiu21idorigen', 'saiu21idarchivo', $idpadre, $objDB);
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->call("limpia_saiu21idarchivo");
	return $objResponse;
}
function elimina_archivo_saiu21idarchivorta($idpadre, $iAgno)
{
	$_SESSION['u_ultimominuto'] = iminutoavance();
	require './app.php';
	$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto != '') {
		$objDB->dbPuerto = $APP->dbpuerto;
	}
	$objDB->xajax();
	$sTabla21 = 'saiu21directa_' . $iAgno;
	archivo_eliminar($sTabla21, 'saiu21id', 'saiu21idorigenrta', 'saiu21idarchivorta', $idpadre, $objDB);
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->call("limpia_saiu21idarchivorta");
	return $objResponse;
}
function f3021_HTMLComboV2_btema($objDB, $objCombos, $valor, $vrbtipo)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$objCombos->nuevo('btema', $valor, true, '{' . $ETI['msg_todos'] . '}');
	$sSQL = '';
	if ((int)$vrbtipo != 0) {
		$objCombos->sAccion = 'paginarf3021()';
		$objCombos->iAncho = 450;
		$sCondi = 'saiu03tiposol="' . $vrbtipo . '"';
		if ($sCondi != '') {
			$sCondi = ' WHERE ' . $sCondi;
		}
		$sSQL = 'SELECT saiu03id AS id, saiu03titulo AS nombre FROM saiu03temasol' . $sCondi;
	}
	$res = $objCombos->html($sSQL, $objDB);
	return $res;
}
function f3021_Combobtema($aParametros)
{
	$_SESSION['u_ultimominuto'] = iminutoavance();
	if (!is_array($aParametros)) {
		$aParametros = json_decode(str_replace('\"', '"', $aParametros), true);
	}
	require './app.php';
	$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto != '') {
		$objDB->dbPuerto = $APP->dbpuerto;
	}
	$objDB->xajax();
	$objCombos = new clsHtmlCombos('n');
	$html_btema = f3021_HTMLComboV2_btema($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_btema', 'innerHTML', $html_btema);
	$objResponse->call('jQuery("#btema").chosen({no_results_text: "No existen coincidencias: ",width: "100%"})');
	$objResponse->call('paginarf3021');
	return $objResponse;
}