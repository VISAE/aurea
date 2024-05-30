<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2020 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.25.4 domingo, 19 de julio de 2020
--- 3019 saiu19chat
*/
/** Archivo lib3019.php.
* Libreria 3019 saiu19chat.
* @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
* @date domingo, 19 de julio de 2020
*/
function f3019_HTMLComboV2_saiu19agno($objDB, $objCombos, $valor){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('saiu19agno', $valor, false, '{'.$ETI['msg_seleccione'].'}');
	//$objCombos->iAncho=450;
	$objCombos->sAccion='RevisaLlave();';
	$sSQL='SHOW TABLES LIKE "saiu19chat%"';
	//if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Total Periodo: Lista de contenedores: '.$sSQL.'<br>';}
	$tablac=$objDB->ejecutasql($sSQL);
	while($filac=$objDB->sf($tablac)){
		$sAgno=substr($filac[0], 11);
		$objCombos->addItem($sAgno, $sAgno);
	}
	$res=$objCombos->html('', $objDB);
	return $res;
	}
function f3019_HTMLComboV2_saiu19mes($objDB, $objCombos, $valor){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	/*
	$objCombos->nuevo('saiu19mes', $valor, false, '{'.$ETI['msg_seleccione'].'}');
	//$objCombos->iAncho=450;
	$objCombos->sAccion='RevisaLlave();';
	$sSQL='SELECT  AS id,  AS nombre FROM ';
	$res=$objCombos->html($sSQL, $objDB);
	*/
	$res=html_ComboMes('saiu19mes', $valor, false, 'RevisaLlave();');
	return $res;
	}
function f3019_HTMLComboV2_saiu19tiporadicado($objDB, $objCombos, $valor){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('saiu19tiporadicado', $valor, false, '{'.$ETI['msg_seleccione'].'}');
	//$objCombos->iAncho=450;
	$objCombos->sAccion='RevisaLlave();';
	$sSQL='SELECT saiu16id AS id, saiu16nombre AS nombre FROM saiu16tiporadicado WHERE saiu16id IN (1, 3) ORDER BY saiu16nombre';
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f3019_HTMLComboV2_saiu19tiposolicitud($objDB, $objCombos, $valor){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('saiu19tiposolicitud', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	$objCombos->sAccion='carga_combo_saiu19temasolicitud();';
	//$objCombos->iAncho=450;
	$sSQL='SELECT TB.saiu02id AS id, CONCAT(TB.saiu02titulo, " [", T1.saiu01titulo, "]") AS nombre 
	FROM saiu02tiposol AS TB, saiu01claseser AS T1 
	WHERE TB.saiu02id>0 AND TB.saiu02ordenllamada<9 AND TB.saiu02clasesol=T1.saiu01id 
	ORDER BY TB.saiu02ordenllamada, TB.saiu02titulo';
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f3019_HTMLComboV2_saiu19temasolicitud($objDB, $objCombos, $valor, $vrsaiu19tiposolicitud){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('saiu19temasolicitud', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	//$objCombos->iAncho=450;
	$sSQL='';
	if ((int)$vrsaiu19tiposolicitud==0){
		$objCombos->sAccion='carga_combo_saiu19tiposolicitud()';
		$sSQL='SELECT TB.saiu03id AS id, CONCAT(TB.saiu03titulo, " [", T2.saiu02titulo, "]") AS nombre 
		FROM saiu03temasol AS TB, saiu02tiposol AS T2 
		WHERE TB.saiu03id>0 AND TB.saiu03ordenllamada<9 AND TB.saiu03tiposol=T2.saiu02id
		ORDER BY TB.saiu03ordensoporte, TB.saiu03titulo';
		}else{
		$sSQL='SELECT saiu03id AS id, saiu03titulo AS nombre 
		FROM saiu03temasol 
		WHERE saiu03id>0 AND saiu03ordenllamada<9 AND saiu03tiposol='.$vrsaiu19tiposolicitud.'
		ORDER BY saiu03ordenllamada, saiu03titulo';
		}
	//if ((int)$vrsaiu19tiposolicitud!=0){}
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f3019_HTMLComboV2_saiu19idcentro($objDB, $objCombos, $valor, $vrsaiu19idzona){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$sCondi='';
	$objCombos->nuevo('saiu19idcentro', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	//$objCombos->iAncho=450;
	$sSQL='';
	if ((int)$vrsaiu19idzona!=0) {
		$sSQL='SELECT unad24id AS id, CONCAT(unad24nombre, CASE unad24activa WHEN "S" THEN "" ELSE " [INACTIVA]" END) AS nombre 
		FROM unad24sede 
		WHERE unad24idzona='.$vrsaiu19idzona.' AND unad24id>0 
		ORDER BY unad24activa DESC, unad24nombre';
	}
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f3019_HTMLComboV2_saiu19coddepto($objDB, $objCombos, $valor, $vrsaiu19codpais){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('saiu19coddepto', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	//$objCombos->iAncho=450;
	$objCombos->sAccion='carga_combo_saiu19codciudad()';
	$sSQL='';
	if ((int)$vrsaiu19codpais!=0){
		$sSQL='SELECT unad19codigo AS id, unad19nombre AS nombre FROM unad19depto WHERE unad19codpais="'.$vrsaiu19codpais.'" ORDER BY unad19nombre';
		}
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f3019_HTMLComboV2_saiu19codciudad($objDB, $objCombos, $valor, $vrsaiu19coddepto){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('saiu19codciudad', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	//$objCombos->iAncho=450;
	$sSQL='';
	if ((int)$vrsaiu19coddepto!=0){
		$sSQL='SELECT unad20codigo AS id, unad20nombre AS nombre FROM unad20ciudad WHERE unad20coddepto="'.$vrsaiu19coddepto.'" ORDER BY unad20nombre';
		}
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f3019_Combosaiu19tiposolicitud($aParametros){
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
		$html_saiu19tiposolicitud=f3019_HTMLComboV2_saiu19tiposolicitud($objDB, $objCombos, $idTipo);
		$html_saiu19temasolicitud=f3019_HTMLComboV2_saiu19temasolicitud($objDB, $objCombos, $idTema, $idTipo);
		$objDB->CerrarConexion();
		$objResponse=new xajaxResponse();
		$objResponse->assign('div_saiu19tiposolicitud', 'innerHTML', $html_saiu19tiposolicitud);
		$objResponse->assign('div_saiu19temasolicitud', 'innerHTML', $html_saiu19temasolicitud);
		$objResponse->call('$("#saiu19tiposolicitud").chosen()');
		$objResponse->call('$("#saiu19temasolicitud").chosen()');
		return $objResponse;
		}else{
		$objDB->CerrarConexion();
		}
	}
function f3019_Combosaiu19temasolicitud($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$objCombos=new clsHtmlCombos();
	$html_saiu19temasolicitud=f3019_HTMLComboV2_saiu19temasolicitud($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_saiu19temasolicitud', 'innerHTML', $html_saiu19temasolicitud);
	$objResponse->call('$("#saiu19temasolicitud").chosen({width:"100%"})');
	return $objResponse;
	}
function f3019_HTMLComboV2_saiu19idprograma($objDB, $objCombos, $valor, $vrsaiu19idescuela){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('saiu19idprograma', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	$objCombos->addItem('0', $ETI['msg_na']);
	//$objCombos->iAncho=450;
	$sCondiEscuela=' AND TB.core09idescuela="'.$vrsaiu19idescuela.'"';
	$sTabla2='';
	$sCampos2='';
	if ($vrsaiu19idescuela==''){
		$sCondiEscuela=' AND TB.core09idescuela=T12.core12id';
		$sTabla2=', core12escuela AS T12';
		$sCampos2=', " [", T12.core12sigla, "]"';
		}
	$sSQL='SELECT TB.core09id AS id, CONCAT(TB.core09nombre, " [", TB.core09codigo, "]"'.$sCampos2.') AS nombre FROM core09programa AS TB'.$sTabla2.' WHERE TB.core09id>0'.$sCondiEscuela;
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f3019_Combosaiu19idcentro($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$objCombos=new clsHtmlCombos();
	$html_saiu19idcentro=f3019_HTMLComboV2_saiu19idcentro($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_saiu19idcentro', 'innerHTML', $html_saiu19idcentro);
	$objResponse->call('$("#saiu19idcentro").chosen({width:"100%"})');
	return $objResponse;
	}
function f3019_Combosaiu19coddepto($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$objCombos=new clsHtmlCombos();
	$html_saiu19coddepto=f3019_HTMLComboV2_saiu19coddepto($objDB, $objCombos, '', $aParametros[0]);
	$html_saiu19codciudad=f3019_HTMLComboV2_saiu19codciudad($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_saiu19coddepto', 'innerHTML', $html_saiu19coddepto);
	$objResponse->call('$("#saiu19coddepto").chosen({width:"100%"})');
	$objResponse->assign('div_saiu19codciudad', 'innerHTML', $html_saiu19codciudad);
	$objResponse->call('$("#saiu19codciudad").chosen({width:"100%"})');
	return $objResponse;
	}
function f3019_Combosaiu19codciudad($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$objCombos=new clsHtmlCombos();
	$html_saiu19codciudad=f3019_HTMLComboV2_saiu19codciudad($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_saiu19codciudad', 'innerHTML', $html_saiu19codciudad);
	$objResponse->call('$("#saiu19codciudad").chosen({width:"100%"})');
	return $objResponse;
	}
function f3019_Combosaiu19idprograma($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$objCombos=new clsHtmlCombos();
	$html_saiu19idprograma=f3019_HTMLComboV2_saiu19idprograma($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_saiu19idprograma', 'innerHTML', $html_saiu19idprograma);
	$objResponse->call('$("#saiu19idprograma").chosen({width:"100%"})');
	return $objResponse;
	}
function f3019_ExisteDato($datos){
	if(!is_array($datos)){$datos=json_decode(str_replace('\"','"',$datos),true);}
	$_SESSION['u_ultimominuto']=iminutoavance();
	$bHayLlave=true;
	$saiu19agno=numeros_validar($datos[1]);
	if ($saiu19agno==''){$bHayLlave=false;}
	$saiu19mes=numeros_validar($datos[2]);
	if ($saiu19mes==''){$bHayLlave=false;}
	$saiu19tiporadicado=numeros_validar($datos[3]);
	if ($saiu19tiporadicado==''){$bHayLlave=false;}
	$saiu19consec=numeros_validar($datos[4]);
	if ($saiu19consec==''){$bHayLlave=false;}
	if ($bHayLlave){
		require './app.php';
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		$sSQL='SELECT 1 FROM saiu19chat_'.$saiu19agno.' WHERE saiu19agno='.$saiu19agno.' AND saiu19mes='.$saiu19mes.' AND saiu19tiporadicado='.$saiu19tiporadicado.' AND saiu19consec='.$saiu19consec.'';
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
function f3019_Busquedas($aParametros){
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3019='lg/lg_3019_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3019)){$mensajes_3019='lg/lg_3019_es.php';}
	require $mensajes_todas;
	require $mensajes_3019;
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
		case 'saiu19idsolicitante':
		require $APP->rutacomun.'lib111.php';
		$sTabla=f111_TablaDetalleBusquedas($aParametrosB, $objDB);
		$sTitulo='Busqueda de terceros';
		$sParams=f111_ParametrosBusqueda();
		$sJavaBusqueda=f111_JavaScriptBusqueda(3019);
		break;
		case 'saiu19idresponsable':
		require $APP->rutacomun.'lib111.php';
		$sTabla=f111_TablaDetalleBusquedas($aParametrosB, $objDB);
		$sTitulo='Busqueda de terceros';
		$sParams=f111_ParametrosBusqueda();
		$sJavaBusqueda=f111_JavaScriptBusqueda(3019);
		break;
		}
	$sTitulo='<h2>'.$ETI['titulo_3019'].' - '.$sTitulo.'</h2>';
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97titulo', 'innerHTML', $sTitulo);
	$objResponse->assign('div_97params', 'innerHTML', $sParams);
	$objResponse->assign('div_97tabla', 'innerHTML', $sTabla);
	$objResponse->setFunction('paginarbusqueda','',$sJavaBusqueda);
	$objResponse->call('expandesector(97)');
	return $objResponse;
	}
function f3019_HtmlBusqueda($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$sError='';
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$sDetalle='';
	switch($aParametros[100]){
		case 'saiu19idsolicitante':
		require $APP->rutacomun.'lib111.php';
		$sDetalle=f111_TablaDetalleBusquedas($aParametros, $objDB);
		break;
		case 'saiu19idresponsable':
		require $APP->rutacomun.'lib111.php';
		$sDetalle=f111_TablaDetalleBusquedas($aParametros, $objDB);
		break;
		}
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97tabla', 'innerHTML', $sDetalle);
	return $objResponse;
	}
function f3019_TablaDetalleV2($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3019='lg/lg_3019_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3019)){$mensajes_3019='lg/lg_3019_es.php';}
	require $mensajes_todas;
	require $mensajes_3019;
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
		return array($sLeyenda.'<input id="paginaf3019" name="paginaf3019" type="hidden" value="'.$pagina.'"/><input id="lppf3019" name="lppf3019" type="hidden" value="'.$lineastabla.'"/>', $sDebug);
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
		$sSQLadd = $sSQLadd . ' AND TB.saiu19estado=' . $iEstado . '';
	}
	switch($bListar){
		case 1:
			$sSQLadd=$sSQLadd.' AND TB.saiu19idresponsable=' . $idTercero. '';		
			break;
		case 2:
			$sSQLadd=$sSQLadd.' AND TB.saiu19idresponsablecaso=' . $idTercero. '';		
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
				$sSQLadd = $sSQLadd . ' AND TB.saiu19idequipocaso IN (' . $sEquipos . ')';
			} else {
				$sSQLadd = $sSQLadd . ' AND TB.saiu19idresponsablecaso=' . $idTercero . '';
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
		$sSQLadd = $sSQLadd . ' AND TB.saiu19tiposolicitud=' . $bcategoria . '';
	}
	if ($btema !== '') {
		$sSQLadd = $sSQLadd . ' AND TB.saiu19temasolicitud=' . $btema . '';
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
	$sTitulos='Agno, Mes, Tiporadicado, Consec, Id, Dia, Hora, Minuto, Estado, Chat, Solicitante, Tipointeresado, Clasesolicitud, Tiposolicitud, Temasolicitud, Zona, Centro, Codpais, Coddepto, Codciudad, Escuela, Programa, Periodo, Numorigen, Pqrs, Detalle, Horafin, Minutofin, Paramercadeo, Responsable, Tiemprespdias, Tiempresphoras, Tiemprespminutos';
	$registros=0;
	$bGigante=false; //En caso de que la tabla sea muy grande pasarlo a true
	$sLimite='';
	if ($bGigante){
		$sSQL='SELECT COUNT(1) AS Total 
FROM saiu19chat_'.$iAgno.' AS TB, unad11terceros AS T12
WHERE '.$sSQLadd1.' TB.saiu19idsolicitante=T12.unad11id '.$sSQLadd.'';
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
	//, TB.saiu19idpqrs, TB.saiu19detalle, TB.saiu19horafin, TB.saiu19minutofin, TB.saiu19paramercadeo, TB.saiu19tiemprespdias, TB.saiu19tiempresphoras, TB.saiu19tiemprespminutos, TB.saiu19tipointeresado, TB.saiu19clasesolicitud, TB.saiu19tiposolicitud, TB.saiu19temasolicitud, TB.saiu19idzona, TB.saiu19idcentro, TB.saiu19codpais, TB.saiu19coddepto, TB.saiu19codciudad, TB.saiu19idescuela, TB.saiu19idprograma, TB.saiu19idperiodo, TB.saiu19idresponsable
	$sSQL='SELECT TB.saiu19agno, TB.saiu19mes, TB.saiu19consec, TB.saiu19id, TB.saiu19dia, TB.saiu19hora, TB.saiu19minuto, 
T12.unad11razonsocial AS C12_nombre, TB.saiu19tiposolicitud, saiu19temasolicitud, TB.saiu19estado, T12.unad11tipodoc AS C12_td, 
T12.unad11doc AS C12_doc, TB.saiu19idsolicitante, TB.saiu19tiporadicado, TB.saiu19solucion 
FROM saiu19chat_'.$iAgno.' AS TB, unad11terceros AS T12
WHERE '.$sSQLadd1.' TB.saiu19idsolicitante=T12.unad11id '.$sSQLadd.'
ORDER BY TB.saiu19agno DESC, TB.saiu19mes DESC, TB.saiu19dia DESC, TB.saiu19tiporadicado, TB.saiu19consec DESC';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_3019" name="consulta_3019" type="hidden" value="'.$sSQLlista.'"/>
<input id="titulos_3019" name="titulos_3019" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objDB->ejecutasql($sSQL.$sLimite);
	if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 3019: '.$sSQL.$sLimite.'<br>';}
	if ($tabladetalle==false){
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		//$sLeyenda=$sSQL;
		}else{
		if (!$bGigante){
			$registros=$objDB->nf($tabladetalle);
			if ($registros==0){
				//return array(cadena_codificar($sErrConsulta.'<input id="paginaf3019" name="paginaf3019" type="hidden" value="'.$pagina.'"/><input id="lppf3019" name="lppf3019" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
<td><b>'.$ETI['saiu19consec'].'</b></td>
<td><b>'.$ETI['saiu19estado'].'</b></td>
<td colspan="2"><b>'.$ETI['saiu19idsolicitante'].'</b></td>
<td><b>'.$ETI['saiu19tiposolicitud'].'</b></td>
<td><b>'.$ETI['saiu19temasolicitud'].'</b></td>
<td><b>'.$ETI['saiu19solucion'].'</b></td>
<td align="right">
'.html_paginador('paginaf3019', $registros, $lineastabla, $pagina, 'paginarf3019()').'
'.html_lpp('lppf3019', $lineastabla, 'paginarf3019()').'
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
		$et_saiu19hora=html_TablaHoraMin($filadet['saiu19hora'], $filadet['saiu19minuto']);
		$et_saiu19idsolicitante_doc='';
		$et_saiu19idsolicitante_nombre='';
		if ($filadet['saiu19idsolicitante']!=0){
			$et_saiu19idsolicitante_doc=$sPrefijo.$filadet['C12_td'].' '.$filadet['C12_doc'].$sSufijo;
			$et_saiu19idsolicitante_nombre=$sPrefijo.cadena_notildes($filadet['C12_nombre']).$sSufijo;
			}
		$sEstado = '';
		if (isset($aEstado[$filadet['saiu19estado']])==0) {
			$sEstado = $ETI['definir'];
		} else {
			$sEstado = $aEstado[$filadet['saiu19estado']];
		}
		$sCategoria = '';
		if (isset($aCategoria[$filadet['saiu19tiposolicitud']])==0) {
			$sCategoria = $ETI['definir'];
		} else {
			$sCategoria = $aCategoria[$filadet['saiu19tiposolicitud']];
		}
		$sTema = '';
		if (isset($aTema[$filadet['saiu19temasolicitud']])==0) {
			$sTema = $ETI['definir'];
		} else {
			$sTema = $aTema[$filadet['saiu19temasolicitud']];
		}
		$sSolucion = '';
		if (isset($asaiu19solucion[$filadet['saiu19solucion']])==0) {
			$sSolucion = $ETI['definir'];
		} else {
			$sSolucion = $asaiu19solucion[$filadet['saiu19solucion']];
		}
		/*
		$et_saiu19horafin=html_TablaHoraMin($filadet['saiu19horafin'], $filadet['saiu19minutofin']);
		$et_saiu19idresponsable_doc='';
		$et_saiu19idresponsable_nombre='';
		if ($filadet['saiu19idresponsable']!=0){
			$et_saiu19idresponsable_doc=$sPrefijo.$filadet['C31_td'].' '.$filadet['C31_doc'].$sSufijo;
			$et_saiu19idresponsable_nombre=$sPrefijo.cadena_notildes($filadet['C31_nombre']).$sSufijo;
			}
		*/
		if ($bAbierta){
			$sLink='<a href="javascript:cargaridf3019('.$filadet['saiu19agno'].','.$filadet['saiu19id'].')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$et_fecha=fecha_armar($filadet['saiu19dia'], $filadet['saiu19mes'], $filadet['saiu19agno']);
		$res=$res.'<tr'.$sClass.'>
<td>'.$sPrefijo.$et_fecha.$sSufijo.'</td>
<td>'.$sPrefijo.$et_saiu19hora.$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu19consec'].$sSufijo.'</td>
<td>'.$sPrefijo.$sEstado.$sSufijo.'</td>
<td>'.$et_saiu19idsolicitante_doc.'</td>
<td>'.$et_saiu19idsolicitante_nombre.'</td>
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
function f3019_HtmlTabla($aParametros){
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
	list($sDetalle, $sDebugTabla)=f3019_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f3019detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f3019_db_CargarPadre($DATA, $objDB, $bDebug=false){
	$sError='';
	$iTipoError=0;
	$sDebug='';
	require './app.php';
	$DATA['saiu19idsolicitante_td']=$APP->tipo_doc;
	$DATA['saiu19idsolicitante_doc']='';
	$DATA['saiu19idresponsable_td']=$APP->tipo_doc;
	$DATA['saiu19idresponsable_doc']='';
	if ($DATA['paso']==1){
		$sSQLcondi='saiu19agno='.$DATA['saiu19agno'].' AND saiu19mes='.$DATA['saiu19mes'].' AND saiu19tiporadicado='.$DATA['saiu19tiporadicado'].' AND saiu19consec='.$DATA['saiu19consec'].'';
		}else{
		$sSQLcondi='saiu19id='.$DATA['saiu19id'].'';
		}
	$sSQL='SELECT * FROM saiu19chat_'.$DATA['saiu19agno'].' WHERE '.$sSQLcondi;
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$DATA['saiu19agno']=$fila['saiu19agno'];
		$DATA['saiu19mes']=$fila['saiu19mes'];
		$DATA['saiu19tiporadicado']=$fila['saiu19tiporadicado'];
		$DATA['saiu19consec']=$fila['saiu19consec'];
		$DATA['saiu19id']=$fila['saiu19id'];
		$DATA['saiu19dia']=$fila['saiu19dia'];
		$DATA['saiu19hora']=$fila['saiu19hora'];
		$DATA['saiu19minuto']=$fila['saiu19minuto'];
		$DATA['saiu19estado']=$fila['saiu19estado'];
		$DATA['saiu19idchat']=$fila['saiu19idchat'];
		$DATA['saiu19idsolicitante']=$fila['saiu19idsolicitante'];
		$DATA['saiu19tipointeresado']=$fila['saiu19tipointeresado'];
		$DATA['saiu19clasesolicitud']=$fila['saiu19clasesolicitud'];
		$DATA['saiu19tiposolicitud']=$fila['saiu19tiposolicitud'];
		$DATA['saiu19temasolicitud']=$fila['saiu19temasolicitud'];
		$DATA['saiu19idzona']=$fila['saiu19idzona'];
		$DATA['saiu19idcentro']=$fila['saiu19idcentro'];
		$DATA['saiu19codpais']=$fila['saiu19codpais'];
		$DATA['saiu19coddepto']=$fila['saiu19coddepto'];
		$DATA['saiu19codciudad']=$fila['saiu19codciudad'];
		$DATA['saiu19idescuela']=$fila['saiu19idescuela'];
		$DATA['saiu19idprograma']=$fila['saiu19idprograma'];
		$DATA['saiu19idperiodo']=$fila['saiu19idperiodo'];
		$DATA['saiu19numorigen']=$fila['saiu19numorigen'];
		$DATA['saiu19idpqrs']=$fila['saiu19idpqrs'];
		$DATA['saiu19detalle']=$fila['saiu19detalle'];
		$DATA['saiu19horafin']=$fila['saiu19horafin'];
		$DATA['saiu19minutofin']=$fila['saiu19minutofin'];
		$DATA['saiu19paramercadeo']=$fila['saiu19paramercadeo'];
		$DATA['saiu19idresponsable']=$fila['saiu19idresponsable'];
		$DATA['saiu19tiemprespdias']=$fila['saiu19tiemprespdias'];
		$DATA['saiu19tiempresphoras']=$fila['saiu19tiempresphoras'];
		$DATA['saiu19tiemprespminutos']=$fila['saiu19tiemprespminutos'];
		$DATA['saiu19solucion']=$fila['saiu19solucion'];
		$DATA['saiu19idcaso']=$fila['saiu19idcaso'];
		$DATA['saiu19numsesionchat']=$fila['saiu19numsesionchat'];
		$DATA['saiu19respuesta']=$fila['saiu19respuesta'];
		$bcargo=true;
		$DATA['paso']=2;
		$DATA['boculta3019']=0;
		$bLimpiaHijos=true;
		}else{
		$DATA['paso']=0;
		}
	return array($DATA, $sError, $iTipoError, $sDebug);
	}
function f3019_Cerrar($saiu19id, $objDB, $bDebug=false){
	$sInfo='';
	$sDebug='';
	return array($sInfo, $sDebug);
	}
function f3019_db_GuardarV2($DATA, $objDB, $bDebug=false){
	$iCodModulo=3019;
	$bAudita[2]=true;
	$bAudita[3]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3019='lg/lg_3019_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3019)){$mensajes_3019='lg/lg_3019_es.php';}
	require $mensajes_todas;
	require $mensajes_3019;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	$bCerrando=false;
	$sErrorCerrando='';
	// -- Se inicia validando todas las posibles entradas de usuario.
	/*
	if (isset($DATA['saiu19agno'])==0){$DATA['saiu19agno']='';}
	if (isset($DATA['saiu19mes'])==0){$DATA['saiu19mes']='';}
	if (isset($DATA['saiu19tiporadicado'])==0){$DATA['saiu19tiporadicado']='';}
	if (isset($DATA['saiu19consec'])==0){$DATA['saiu19consec']='';}
	if (isset($DATA['saiu19id'])==0){$DATA['saiu19id']='';}
	if (isset($DATA['saiu19dia'])==0){$DATA['saiu19dia']='';}
	if (isset($DATA['saiu19hora'])==0){$DATA['saiu19hora']='';}
	if (isset($DATA['saiu19minuto'])==0){$DATA['saiu19minuto']='';}
	if (isset($DATA['saiu19idchat'])==0){$DATA['saiu19idchat']='';}
	if (isset($DATA['saiu19idsolicitante'])==0){$DATA['saiu19idsolicitante']='';}
	if (isset($DATA['saiu19tipointeresado'])==0){$DATA['saiu19tipointeresado']='';}
	if (isset($DATA['saiu19clasesolicitud'])==0){$DATA['saiu19clasesolicitud']='';}
	if (isset($DATA['saiu19tiposolicitud'])==0){$DATA['saiu19tiposolicitud']='';}
	if (isset($DATA['saiu19temasolicitud'])==0){$DATA['saiu19temasolicitud']='';}
	if (isset($DATA['saiu19idzona'])==0){$DATA['saiu19idzona']='';}
	if (isset($DATA['saiu19idcentro'])==0){$DATA['saiu19idcentro']='';}
	if (isset($DATA['saiu19codpais'])==0){$DATA['saiu19codpais']='';}
	if (isset($DATA['saiu19coddepto'])==0){$DATA['saiu19coddepto']='';}
	if (isset($DATA['saiu19codciudad'])==0){$DATA['saiu19codciudad']='';}
	if (isset($DATA['saiu19idescuela'])==0){$DATA['saiu19idescuela']='';}
	if (isset($DATA['saiu19idprograma'])==0){$DATA['saiu19idprograma']='';}
	if (isset($DATA['saiu19idperiodo'])==0){$DATA['saiu19idperiodo']='';}
	if (isset($DATA['saiu19numorigen'])==0){$DATA['saiu19numorigen']='';}
	if (isset($DATA['saiu19detalle'])==0){$DATA['saiu19detalle']='';}
	if (isset($DATA['saiu19horafin'])==0){$DATA['saiu19horafin']='';}
	if (isset($DATA['saiu19minutofin'])==0){$DATA['saiu19minutofin']='';}
	if (isset($DATA['saiu19paramercadeo'])==0){$DATA['saiu19paramercadeo']='';}
	if (isset($DATA['saiu19idresponsable'])==0){$DATA['saiu19idresponsable']='';}
	if (isset($DATA['saiu19solucion'])==0){$DATA['saiu19solucion']='';}
	if (isset($DATA['saiu19numsesionchat'])==0){$DATA['saiu19numsesionchat']='';}
	if (isset($DATA['saiu19respuesta'])==0){$DATA['saiu19respuesta']='';}
	*/
	$DATA['saiuid']=19;
	$DATA['saiu19agno']=numeros_validar($DATA['saiu19agno']);
	$DATA['saiu19mes']=numeros_validar($DATA['saiu19mes']);
	$DATA['saiu19tiporadicado']=numeros_validar($DATA['saiu19tiporadicado']);
	$DATA['saiu19consec']=numeros_validar($DATA['saiu19consec']);
	$DATA['saiu19dia']=numeros_validar($DATA['saiu19dia']);
	$DATA['saiu19hora']=numeros_validar($DATA['saiu19hora']);
	$DATA['saiu19minuto']=numeros_validar($DATA['saiu19minuto']);
	$DATA['saiu19idchat']=numeros_validar($DATA['saiu19idchat']);
	$DATA['saiu19tipointeresado']=numeros_validar($DATA['saiu19tipointeresado']);
	$DATA['saiu19clasesolicitud']=numeros_validar($DATA['saiu19clasesolicitud']);
	$DATA['saiu19tiposolicitud']=numeros_validar($DATA['saiu19tiposolicitud']);
	$DATA['saiu19temasolicitud']=numeros_validar($DATA['saiu19temasolicitud']);
	$DATA['saiu19idzona']=numeros_validar($DATA['saiu19idzona']);
	$DATA['saiu19idcentro']=numeros_validar($DATA['saiu19idcentro']);
	$DATA['saiu19codpais']=htmlspecialchars(trim($DATA['saiu19codpais']));
	$DATA['saiu19coddepto']=htmlspecialchars(trim($DATA['saiu19coddepto']));
	$DATA['saiu19codciudad']=htmlspecialchars(trim($DATA['saiu19codciudad']));
	$DATA['saiu19idescuela']=numeros_validar($DATA['saiu19idescuela']);
	$DATA['saiu19idprograma']=numeros_validar($DATA['saiu19idprograma']);
	$DATA['saiu19idperiodo']=numeros_validar($DATA['saiu19idperiodo']);
	$DATA['saiu19numorigen']=htmlspecialchars(trim($DATA['saiu19numorigen']));
	$DATA['saiu19detalle']=htmlspecialchars(trim($DATA['saiu19detalle']));
	$DATA['saiu19fechafin']=numeros_validar($DATA['saiu19fechafin']);
	$DATA['saiu19horafin']=numeros_validar($DATA['saiu19horafin']);
	$DATA['saiu19minutofin']=numeros_validar($DATA['saiu19minutofin']);
	$DATA['saiu19paramercadeo']=numeros_validar($DATA['saiu19paramercadeo']);
	$DATA['saiu19solucion']=numeros_validar($DATA['saiu19solucion']);
	$DATA['saiu19numsesionchat']=htmlspecialchars(trim($DATA['saiu19numsesionchat']));
	$DATA['saiu19respuesta']=htmlspecialchars(trim($DATA['saiu19respuesta']));
	$DATA['saiu19idcaso']=numeros_validar($DATA['saiu19idcaso']);
	$DATA['saiu19fecharespcaso']=numeros_validar($DATA['saiu19fecharespcaso']);
	$DATA['saiu19horarespcaso']=numeros_validar($DATA['saiu19horarespcaso']);
	$DATA['saiu19minrespcaso']=numeros_validar($DATA['saiu19minrespcaso']);
	$DATA['saiu19idunidadcaso']=numeros_validar($DATA['saiu19idunidadcaso']);
	$DATA['saiu19idequipocaso']=numeros_validar($DATA['saiu19idequipocaso']);
	$DATA['saiu19idsupervisorcaso']=numeros_validar($DATA['saiu19idsupervisorcaso']);
	$DATA['saiu19idresponsablecaso']=numeros_validar($DATA['saiu19idresponsablecaso']);
	$DATA['saiu19numref']=htmlspecialchars(trim($DATA['saiu19numref']));
	// -- Se inicializan las variables que puedan pasar vacias {Especialmente números}.
	//if ($DATA['saiu19dia']==''){$DATA['saiu19dia']=0;}
	//if ($DATA['saiu19hora']==''){$DATA['saiu19hora']=0;}
	//if ($DATA['saiu19minuto']==''){$DATA['saiu19minuto']=0;}
	if ($DATA['saiu19estado']==''){$DATA['saiu19estado']=0;}
	if ($DATA['saiu19estadoorigen']==''){$DATA['saiu19estadoorigen']=0;}
	//if ($DATA['saiu19idchat']==''){$DATA['saiu19idchat']=0;}
	//if ($DATA['saiu19tipointeresado']==''){$DATA['saiu19tipointeresado']=0;}
	if ($DATA['saiu19idpqrs']==''){$DATA['saiu19idpqrs']=0;}
	//if ($DATA['saiu19paramercadeo']==''){$DATA['saiu19paramercadeo']=0;}
	//if ($DATA['saiu19solucion']==''){$DATA['saiu19solucion']=0;}
	// -- Seccion para validar los posibles causales de error.
	$sSepara=', ';
	$bConCierre=false;
	$bEnviaEncuesta=false;
	$bEnviaCaso=false;
	if ($DATA['saiu19temasolicitud']==''){$sError=$ERR['saiu19temasolicitud'].$sSepara.$sError;}
	if ($DATA['saiu19tiposolicitud']==''){$sError=$ERR['saiu19tiposolicitud'].$sSepara.$sError;}
	// if ($DATA['saiu19clasesolicitud']==''){$sError=$ERR['saiu19clasesolicitud'].$sSepara.$sError;}
	if ($DATA['saiu19tipointeresado']==''){$sError=$ERR['saiu19tipointeresado'].$sSepara.$sError;}
	if ($DATA['saiu19idsolicitante']==0){$sError=$ERR['saiu19idsolicitante'].$sSepara.$sError;}
	if ($DATA['saiu19minuto']==''){$sError=$ERR['saiu19minuto'].$sSepara.$sError;}
	if ($DATA['saiu19hora']==''){$sError=$ERR['saiu19hora'].$sSepara.$sError;}
	if ($DATA['saiu19dia']==''){$sError=$ERR['saiu19dia'].$sSepara.$sError;}
	if ($DATA['saiu19mes']==''){$sError=$ERR['saiu19mes'].$sSepara.$sError;}
	if ($DATA['saiu19agno']==''){$sError=$ERR['saiu19agno'].$sSepara.$sError;}
	if ($DATA['saiu19idcentro']==''){$sError=$ERR['saiu19idcentro'].$sSepara.$sError;}
	if ($DATA['saiu19idzona']==''){$sError=$ERR['saiu19idzona'].$sSepara.$sError;}
	if ($DATA['saiu19detalle']==''){$sError=$ERR['saiu19detalle'].$sSepara.$sError;}
	if ($DATA['saiu19fecharespcaso']==''){$DATA['saiu19fecharespcaso']=0;}
	if ($DATA['saiu19estado']==1 || $DATA['saiu19estado']==7){
		if ($DATA['saiu19estado']==7) {
			$bConCierre=true;
		}
		if ($DATA['saiu19solucion']=='') {
			$sError=$ERR['saiu19solucion'].$sSepara.$sError;
			}else{
			if ((int)$DATA['saiu19solucion']==0){
				$sError=$ERR['saiu19solucion_proceso'].$sSepara.$sError;
				}
			}
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Estado: '.$DATA['saiu19estado']. ' - Solución: '. $DATA['saiu19solucion'] .'<br>';}
		if ($DATA['saiu19idresponsable']==0){$sError=$ERR['saiu19idresponsable'].$sSepara.$sError;}
		if ($DATA['saiu19paramercadeo']==''){$sError=$ERR['saiu19paramercadeo'].$sSepara.$sError;}
		//if ($DATA['saiu19minutofin']==''){$sError=$ERR['saiu19minutofin'].$sSepara.$sError;}
		//if ($DATA['saiu19horafin']==''){$sError=$ERR['saiu19horafin'].$sSepara.$sError;}
		if ($DATA['saiu19idperiodo']==''){$sError=$ERR['saiu19idperiodo'].$sSepara.$sError;}
		if ($DATA['saiu19idprograma']==''){$sError=$ERR['saiu19idprograma'].$sSepara.$sError;}
		if ($DATA['saiu19idescuela']==''){$sError=$ERR['saiu19idescuela'].$sSepara.$sError;}
		if ($DATA['saiu19codciudad']==''){$sError=$ERR['saiu19codciudad'].$sSepara.$sError;}
		if ($DATA['saiu19coddepto']==''){$sError=$ERR['saiu19coddepto'].$sSepara.$sError;}
		if ($DATA['saiu19codpais']==''){$sError=$ERR['saiu19codpais'].$sSepara.$sError;}
		//if ($DATA['saiu19hora']==''){$DATA['saiu19hora']=fecha_hora();}
		if ($DATA['saiu19solucion']==1){ // Resuelto en la atención
			if ($DATA['saiu19respuesta']==''){$sError=$ERR['saiu19respuesta'].$sSepara.$sError;}
		}
		if ($DATA['saiu19solucion']==3) { // Se inicia caso
			if ($DATA['saiu19temasolicitud'] != $DATA['saiu19temasolicitudorigen']) {
				$DATA['saiu19idresponsablecaso']=0;
			}
			if ($DATA['saiu19idresponsablecaso']==0) {
				list($DATA['saiu19idunidadcaso'], $DATA['saiu19idequipocaso'], $DATA['saiu19idsupervisorcaso'], $DATA['saiu19idresponsablecaso'], $saiu19tiemprespdias, $saiu19tiempresphoras, $sErrorF, $iTipoError, $sDebugF) = f3019_ConsultaResponsable($DATA, $objDB, $bDebug);
				if ($sErrorF!=''){$sError=$sError.'<br>'.$sErrorF;}
				if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta Responsable: '.$sDebugF.'<br>';}
				if ($DATA['saiu19idunidadcaso']==0){$sError=$ERR['saiu19idunidadcaso'].$sSepara.$sError;}
				if ($DATA['saiu19idequipocaso']==0){$sError=$ERR['saiu19idequipocaso'].$sSepara.$sError;}
				if ($DATA['saiu19idsupervisorcaso']==0){$sError=$ERR['saiu19idsupervisorcaso'].$sSepara.$sError;}
				if ($DATA['saiu19idresponsablecaso']==0){$sError=$ERR['saiu19idresponsablecaso'].$sSepara.$sError;}
				if ($sError!='') {
					$DATA['saiu19idunidadcaso']=0;
					$DATA['saiu19idequipocaso']=0;
					$DATA['saiu19idsupervisorcaso']=0;
					$DATA['saiu19idresponsablecaso']=0;
				}
			}
		}
		if ($DATA['saiu19idchat']==''){$sError=$ERR['saiu19idchat'].$sSepara.$sError;}
		if ($sError=='') {
			if ($DATA['saiu19solucion']==5) { // Se inicia PQRS
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
				$aParams['saiu05idsolicitante']=$DATA['saiu19idsolicitante'];
				$aParams['saiu05tipointeresado']=$DATA['saiu19tipointeresado'];
				$aParams['saiu05detalle']=$DATA['saiu19detalle'];
				$aParams['saiu05idtiposolorigen']=$DATA['saiu19tiposolicitud'];
				$aParams['saiu05idtemaorigen']=$DATA['saiu19temasolicitud'];
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
					$DATA['saiu19idpqrs']=$aParams['saiu05id'];
					$DATA['saiu19numref']=$aParams['saiu05numref'];
					if ($DATA['saiu19idpqrs']==-1){$sError=$ERR['saiu19idpqrs'].$sSepara.$sError;}
					if ($DATA['saiu19numref']==''){$sError=$ERR['saiu19numref'].$sSepara.$sError;}
				}
			}
		}
		if ($sError!=''){
			if ($DATA['saiu19estado']!=1) {	// Asignado
				$DATA['saiu19estado']=2;	// En tramite
			}
		}
		$sErrorCerrando=$sError;
		//Fin de las valiaciones NO LLAVE.
		}
	if ($sError==''){
		switch($DATA['saiu19estado']){
			case 1: //Caso Asignado
			break;
			case 7: //Logra cerrar			
			switch($DATA['saiu19solucion']){
				case 1: // Resuelto en la atención
				case 5: // Se inicia PQRS
				$DATA['saiu19fecharespcaso']=0;
				$DATA['saiu19fechafin']=fecha_DiaMod();
				$DATA['saiu19horafin']=fecha_hora();
				$DATA['saiu19minutofin']=fecha_minuto();
				$bEnviaEncuesta=true;
				break;
				case 3: // Se inicia caso
				if ($DATA['saiu19estadoorigen']==1) {
					if ($DATA['saiu19respuesta']==''){
						$sError=$ERR['saiu19respuesta'].$sSepara.$sError;
					} else {
						$DATA['saiu19fecharespcaso']=fecha_DiaMod();
						$DATA['saiu19horarespcaso']=fecha_hora();
						$DATA['saiu19minrespcaso']=fecha_minuto();
						$bEnviaEncuesta=true;
					}
				} else {
					$DATA['saiu19fecharespcaso']=0;
					$DATA['saiu19fechafin']=fecha_DiaMod();
					$DATA['saiu19horafin']=fecha_hora();
					$DATA['saiu19minutofin']=fecha_minuto();
					$iDiaIni=($DATA['saiu19agno']*10000)+($DATA['saiu19mes']*100)+$DATA['saiu19dia'];
					list($DATA['saiu19tiemprespdias'], $DATA['saiu19tiempresphoras'], $DATA['saiu19tiemprespminutos'])=Tiempo_MinutosCalendario($iDiaIni, $DATA['saiu19hora'], $DATA['saiu19minuto'], $DATA['saiu19fechafin'], $DATA['saiu19horafin'], $DATA['saiu19minutofin']);
					$DATA['saiu19idcaso']=(int)(fecha_DiaMod().$DATA['saiu19id'].'');
					$DATA['saiu19respuesta']='';
					$DATA['saiu19estado']=1;
					$bEnviaCaso=true;
				}
				break;
				case 8: //Solicitud abandonada
				case 9: //Cancelada por el usuario
				case 10: //Solicitud abandonada
				case 11: //Cancelada por el usuario
				$DATA['saiu19respuesta']='';
				break;
				default:
				$sError=$ERR['saiu19solucion'].$sSepara.$sError;
				break;
			}
			break;
			case 8: //Solicitud abandonada
			case 9: //Cancelada por el usuario
			case 10: //Solicitud abandonada
			case 11: //Cancelada por el usuario
			if (trim($DATA['saiu19fechafin'])==''){$DATA['saiu19fechafin']=fecha_DiaMod();}
			if (trim($DATA['saiu19minutofin'])==''){$DATA['saiu19minutofin']=fecha_minuto();}
			if (trim($DATA['saiu19horafin'])==''){$DATA['saiu19horafin']=fecha_hora();}
			//$DATA['saiu19minutofin']=fecha_minuto();
			//$DATA['saiu19horafin']=fecha_hora();
			break; 
			default:
			$DATA['saiu19estado']=2;
			if ($DATA['saiu19hora']==''){$DATA['saiu19hora']=fecha_hora();}
			if ($DATA['saiu19minuto']==''){$DATA['saiu19minuto']=fecha_minuto();}
			if ($DATA['saiu19fechafin']==''){$DATA['saiu19fechafin']=0;}
			if ($DATA['saiu19horafin']==''){$DATA['saiu19horafin']=0;}
			if ($DATA['saiu19minutofin']==''){$DATA['saiu19minutofin']=0;}
			break;
		}
		if ($DATA['saiu19clasesolicitud']==''){$DATA['saiu19clasesolicitud']=0;}
		if ($DATA['saiu19tiposolicitud']==''){$DATA['saiu19tiposolicitud']=0;}
		if ($DATA['saiu19temasolicitud']==''){$DATA['saiu19temasolicitud']=0;}
		if ($DATA['saiu19idzona']==''){$DATA['saiu19idzona']=0;}
		if ($DATA['saiu19idcentro']==''){$DATA['saiu19idcentro']=0;}
		if ($DATA['saiu19idescuela']==''){$DATA['saiu19idescuela']=0;}
		if ($DATA['saiu19idprograma']==''){$DATA['saiu19idprograma']=0;}
		if ($DATA['saiu19idperiodo']==''){$DATA['saiu19idperiodo']=0;}
	}
	//Valiaciones de campos obligatorios en todo guardar.
	if ($DATA['saiu19tiporadicado']==''){$sError=$ERR['saiu19tiporadicado'];}
	if ($DATA['saiu19mes']==''){$sError=$ERR['saiu19mes'];}
	if ($DATA['saiu19agno']==''){$sError=$ERR['saiu19agno'];}
	// -- Tiene un cerrado.
	$iDiaIni=fecha_ArmarNumero($DATA['saiu19dia'],$DATA['saiu19mes'],$DATA['saiu19agno']);
	if ($bConCierre && $sError==''){
		//Validaciones previas a cerrar
		if ($DATA['saiu19estado']==7){
			switch($DATA['saiu19solucion']){
				case 1: // Resuelto en la atención
				case 5: // Se inicia PQRS
				list($DATA['saiu19tiemprespdias'], $DATA['saiu19tiempresphoras'], $DATA['saiu19tiemprespminutos'])=Tiempo_MinutosCalendario($iDiaIni, $DATA['saiu19hora'], $DATA['saiu19minuto'], $DATA['saiu19fechafin'], $DATA['saiu19horafin'], $DATA['saiu19minutofin']);
				break;
			}
		}
		//Aprobó las Validaciones al cerrar
		if ($sError.$sErrorCerrando!=''){
			if ($DATA['saiu19estado']==7) {
				$DATA['saiu19estado'] = $DATA['saiu19estadoorigen'];
			} else if ($DATA['saiu19estado']!=1) {
				$DATA['saiu19estado']=2;
			}
			$sErrorCerrando=$sError.' '.$sErrorCerrando;
		}else{
			$bCerrando=true;
			//Acciones del cierre
		}
	}
	// -- Fin del cerrado.
	// -- Se verifican los valores de campos de otras tablas.
	$sTabla19='saiu19chat_'.$DATA['saiu19agno'];
	if ($DATA['saiu19idresponsable_doc']!=''){
		if ($sError==''){$sError=tabla_terceros_existe($DATA['saiu19idresponsable_td'], $DATA['saiu19idresponsable_doc'], $objDB, 'El tercero Responsable ');}
		if ($sError==''){
			list($sError, $sInfo)=tercero_Bloqueado($DATA['saiu19idresponsable'], $objDB);
			if ($sInfo!=''){$sError=$sError.'<br>'.$sInfo;}
			}
		}
	if ($DATA['saiu19idsolicitante_doc']!=''){
		if ($sError==''){$sError=tabla_terceros_existe($DATA['saiu19idsolicitante_td'], $DATA['saiu19idsolicitante_doc'], $objDB, 'El tercero Solicitante ');}
		if ($sError==''){
			list($sError, $sInfo)=tercero_Bloqueado($DATA['saiu19idsolicitante'], $objDB);
			if ($sInfo!=''){$sError=$sError.'<br>'.$sInfo;}
			}
		}
	if ($sError == '') {
		list($sErrorR, $sDebugR) = f3019_RevTabla_saiu19chat($DATA['saiu19agno'], $objDB);
		$sError = $sError . $sErrorR;
	}
	$bQuitarCodigo=false;
	$sCampoCodigo='';
	if ($sError==''){
		if ($DATA['paso']==10){
			if ($DATA['saiu19consec']==''){
				$DATA['saiu19consec']=tabla_consecutivo($sTabla19, 'saiu19consec', 'saiu19agno='.$DATA['saiu19agno'].' AND saiu19mes='.$DATA['saiu19mes'].' AND saiu19tiporadicado='.$DATA['saiu19tiporadicado'].'', $objDB);
				if ($DATA['saiu19consec']==-1){$sError=$objDB->serror;}
				$bQuitarCodigo=true;
				$sCampoCodigo='saiu19consec';
				}else{
				if (!seg_revisa_permiso($iCodModulo, 8, $objDB)){
					$sError=$ERR['8'];
					$DATA['saiu19consec']='';
					}
				}
			if ($sError==''){
				$sSQL='SELECT 1 FROM '.$sTabla19.' WHERE saiu19agno='.$DATA['saiu19agno'].' AND saiu19mes='.$DATA['saiu19mes'].' AND saiu19tiporadicado='.$DATA['saiu19tiporadicado'].' AND saiu19consec='.$DATA['saiu19consec'].'';
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
			$DATA['saiu19id']=tabla_consecutivo($sTabla19,'saiu19id', '', $objDB);
			if ($DATA['saiu19id']==-1){$sError=$objDB->serror;}
			}
		}
	if ($sError==''){
		//Encontrar la clase
		$sSQL='SELECT saiu02clasesol FROM saiu02tiposol WHERE saiu02id='.$DATA['saiu19tiposolicitud'].'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$fila=$objDB->sf($tabla);
			$DATA['saiu19clasesolicitud']=$fila['saiu02clasesol'];
			}
		}
	$idSolicitantePrevio=0;
	if ($sError==''){
		$DATA['saiu19detalle']=stripslashes($DATA['saiu19detalle']);
		//Si el campo saiu19detalle permite html quite la linea htmlspecialchars para el campo y habilite la siguiente linea:
		//$saiu19detalle=addslashes($DATA['saiu19detalle']);
		$saiu19detalle=str_replace('"', '\"', $DATA['saiu19detalle']);
		$DATA['saiu19respuesta']=stripslashes($DATA['saiu19respuesta']);
		//$saiu19respuesta=addslashes($DATA['saiu19respuesta']);
		$saiu19respuesta=str_replace('"', '\"', $DATA['saiu19respuesta']);
		$bpasa=false;
		if ($DATA['paso']==10){
			$DATA['saiu19idpqrs']=0;
			$DATA['saiu19tiemprespdias']=0;
			$DATA['saiu19tiempresphoras']=0;
			$DATA['saiu19tiemprespminutos']=0;
			$DATA['saiu19agno']=fecha_agno();
			$DATA['saiu19mes']=fecha_mes();
			$DATA['saiu19dia']=fecha_dia();
			$DATA['saiu19hora']=fecha_hora();
			$DATA['saiu19minuto']=fecha_minuto();
			$DATA['saiu19idcaso']=0;
			$sCampos3019='saiu19agno, saiu19mes, saiu19tiporadicado, saiu19consec, saiu19id, 
			saiu19dia, saiu19hora, saiu19minuto, saiu19estado, saiu19idchat, saiu19idsolicitante, 
			saiu19tipointeresado, saiu19clasesolicitud, saiu19tiposolicitud, saiu19temasolicitud, saiu19idzona, 
			saiu19idcentro, saiu19codpais, saiu19coddepto, saiu19codciudad, saiu19idescuela, 
			saiu19idprograma, saiu19idperiodo, saiu19numorigen, saiu19idpqrs, saiu19detalle, saiu19fechafin,
			saiu19horafin, saiu19minutofin, saiu19paramercadeo, saiu19idresponsable, saiu19tiemprespdias, 
			saiu19tiempresphoras, saiu19tiemprespminutos, saiu19solucion, saiu19idcaso, saiu19numsesionchat, saiu19respuesta';
			$sValores3019=''.$DATA['saiu19agno'].', '.$DATA['saiu19mes'].', '.$DATA['saiu19tiporadicado'].', '.$DATA['saiu19consec'].', '.$DATA['saiu19id'].', 
			'.$DATA['saiu19dia'].', '.$DATA['saiu19hora'].', '.$DATA['saiu19minuto'].', '.$DATA['saiu19estado'].', '.$DATA['saiu19idchat'].', '.$DATA['saiu19idsolicitante'].', 
			'.$DATA['saiu19tipointeresado'].', '.$DATA['saiu19clasesolicitud'].', '.$DATA['saiu19tiposolicitud'].', '.$DATA['saiu19temasolicitud'].', '.$DATA['saiu19idzona'].', 
			'.$DATA['saiu19idcentro'].', "'.$DATA['saiu19codpais'].'", "'.$DATA['saiu19coddepto'].'", "'.$DATA['saiu19codciudad'].'", '.$DATA['saiu19idescuela'].', 
			'.$DATA['saiu19idprograma'].', '.$DATA['saiu19idperiodo'].', "'.$DATA['saiu19numorigen'].'", '.$DATA['saiu19idpqrs'].', "'.$saiu19detalle.'", '.$DATA['saiu19fechafin'].', 
			'.$DATA['saiu19horafin'].', '.$DATA['saiu19minutofin'].', '.$DATA['saiu19paramercadeo'].', '.$DATA['saiu19idresponsable'].', '.$DATA['saiu19tiemprespdias'].', 
			'.$DATA['saiu19tiempresphoras'].', '.$DATA['saiu19tiemprespminutos'].', '.$DATA['saiu19solucion'].', '.$DATA['saiu19idcaso'].', "'.$DATA['saiu19numsesionchat'].'", "'.$saiu19respuesta.'"';
			if ($APP->utf8==1){
				$sSQL='INSERT INTO '.$sTabla19.' ('.$sCampos3019.') VALUES ('.cadena_codificar($sValores3019).');';
				$sdetalle=$sCampos3019.'['.cadena_codificar($sValores3019).']';
				}else{
				$sSQL='INSERT INTO '.$sTabla19.' ('.$sCampos3019.') VALUES ('.$sValores3019.');';
				$sdetalle=$sCampos3019.'['.$sValores3019.']';
				}
			$idAccion=2;
			$bpasa=true;
			}else{
			$scampo[1]='saiu19dia';
			$scampo[2]='saiu19hora';
			$scampo[3]='saiu19minuto';
			$scampo[4]='saiu19idchat';
			$scampo[5]='saiu19idsolicitante';
			$scampo[6]='saiu19tipointeresado';
			$scampo[7]='saiu19clasesolicitud';
			$scampo[8]='saiu19temasolicitud';
			$scampo[9]='saiu19idzona';
			$scampo[10]='saiu19idcentro';
			$scampo[11]='saiu19codpais';
			$scampo[12]='saiu19coddepto';
			$scampo[13]='saiu19codciudad';
			$scampo[14]='saiu19idescuela';
			$scampo[15]='saiu19idprograma';
			$scampo[16]='saiu19idperiodo';
			$scampo[17]='saiu19numorigen';
			$scampo[18]='saiu19detalle';
			$scampo[19]='saiu19horafin';
			$scampo[20]='saiu19minutofin';
			$scampo[21]='saiu19paramercadeo';
			$scampo[22]='saiu19idresponsable';
			$scampo[23]='saiu19solucion';
			$scampo[24]='saiu19tiposolicitud';
			$scampo[25]='saiu19estado';
			$scampo[26]='saiu19tiemprespdias';
			$scampo[27]='saiu19tiempresphoras';
			$scampo[28]='saiu19tiemprespminutos';
			$scampo[29]='saiu19respuesta';
			$scampo[30]='saiu19numsesionchat';
			$scampo[31]='saiu19idcaso';
			$scampo[32]='saiu19fecharespcaso';
			$scampo[33]='saiu19horarespcaso';
			$scampo[34]='saiu19minrespcaso';
			$scampo[35]='saiu19idunidadcaso';
			$scampo[36]='saiu19idequipocaso';
			$scampo[37]='saiu19idsupervisorcaso';
			$scampo[38]='saiu19idresponsablecaso';
			$scampo[39]='saiu19idpqrs';
			$scampo[40]='saiu19numref';
			$scampo[41]='saiu19fechafin';
			$sdato[1]=$DATA['saiu19dia'];
			$sdato[2]=$DATA['saiu19hora'];
			$sdato[3]=$DATA['saiu19minuto'];
			$sdato[4]=$DATA['saiu19idchat'];
			$sdato[5]=$DATA['saiu19idsolicitante'];
			$sdato[6]=$DATA['saiu19tipointeresado'];
			$sdato[7]=$DATA['saiu19clasesolicitud'];
			$sdato[8]=$DATA['saiu19temasolicitud'];
			$sdato[9]=$DATA['saiu19idzona'];
			$sdato[10]=$DATA['saiu19idcentro'];
			$sdato[11]=$DATA['saiu19codpais'];
			$sdato[12]=$DATA['saiu19coddepto'];
			$sdato[13]=$DATA['saiu19codciudad'];
			$sdato[14]=$DATA['saiu19idescuela'];
			$sdato[15]=$DATA['saiu19idprograma'];
			$sdato[16]=$DATA['saiu19idperiodo'];
			$sdato[17]=$DATA['saiu19numorigen'];
			$sdato[18]=$saiu19detalle;
			$sdato[19]=$DATA['saiu19horafin'];
			$sdato[20]=$DATA['saiu19minutofin'];
			$sdato[21]=$DATA['saiu19paramercadeo'];
			$sdato[22]=$DATA['saiu19idresponsable'];
			$sdato[23]=$DATA['saiu19solucion'];
			$sdato[24]=$DATA['saiu19tiposolicitud'];
			$sdato[25]=$DATA['saiu19estado'];
			$sdato[26]=$DATA['saiu19tiemprespdias'];
			$sdato[27]=$DATA['saiu19tiempresphoras'];
			$sdato[28]=$DATA['saiu19tiemprespminutos'];
			$sdato[29]=$saiu19respuesta;
			$sdato[30]=$DATA['saiu19numsesionchat'];
			$sdato[31]=$DATA['saiu19idcaso'];
			$sdato[32]=$DATA['saiu19fecharespcaso'];
			$sdato[33]=$DATA['saiu19horarespcaso'];
			$sdato[34]=$DATA['saiu19minrespcaso'];
			$sdato[35]=$DATA['saiu19idunidadcaso'];
			$sdato[36]=$DATA['saiu19idequipocaso'];
			$sdato[37]=$DATA['saiu19idsupervisorcaso'];
			$sdato[38]=$DATA['saiu19idresponsablecaso'];
			$sdato[39]=$DATA['saiu19idpqrs'];
			$sdato[40]=$DATA['saiu19numref'];
			$sdato[41]=$DATA['saiu19fechafin'];
			$numcmod=41;
			$sWhere='saiu19id='.$DATA['saiu19id'].'';
			$sSQL='SELECT * FROM '.$sTabla19.' WHERE '.$sWhere;
			$sdatos='';
			$bPrimera=true;
			$result=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($result)>0){
				$filabase=$objDB->sf($result);
				if ($DATA['saiu19idsolicitante']!=$filabase['saiu19idsolicitante']){
					$idSolicitantePrevio=$filabase['saiu19idsolicitante'];
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
					$sSQL='UPDATE '.$sTabla19.' SET '.cadena_codificar($sdatos).' WHERE '.$sWhere.';';
					}else{
					$sdetalle=$sdatos.'['.$sWhere.']';
					$sSQL='UPDATE '.$sTabla19.' SET '.$sdatos.' WHERE '.$sWhere.';';
					}
				$idAccion=3;
				}
			}
		if ($bpasa){
			if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Guardar 3019 '.$sSQL.'<br>';}
			$result=$objDB->ejecutasql($sSQL);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' [3019] ..<!-- '.$sSQL.' -->';
				if ($idAccion==2){
					$DATA['saiu19id']='';
					$bQuitarCodigo=true;
					$DATA['paso']=0;
					}else{
					$DATA['paso']=2;
					}
				$bCerrando=false;
				}else{
				if ($bAudita[$idAccion]){seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], $idAccion, $DATA['saiu19id'], $sdetalle, $objDB);}
				$DATA['paso']=2;
				//Registrar en el inventario.
				$valores3000[2]=$iCodModulo;
				$valores3000[3]=$DATA['saiu19agno'];
				$valores3000[4]=$DATA['saiu19id'];
				if ($idSolicitantePrevio!=0){
					//Retirar al anterior.
					$valores3000[1]=$idSolicitantePrevio;
					f3000_Retirar($valores3000, $objDB, $bDebug);
					}
				if ($DATA['saiu19idsolicitante']!=0){
					$valores3000[1]=$DATA['saiu19idsolicitante'];
					$valores3000[5]=$iDiaIni;
					$valores3000[6]=$DATA['saiu19tiposolicitud'];
					$valores3000[7]=$DATA['saiu19temasolicitud'];
					$valores3000[8]=$DATA['saiu19estado'];
					f3000_Registrar($valores3000, $objDB, $bDebug);
					}
				if ($bEnviaCaso) {
					list($sMensaje, $sErrorE, $sDebugE) = f3000_EnviaCorreosAtencion($DATA, $DATA['saiu19agno'], $objDB, $bDebug, true);
					$sError = $sError . $sErrorE;
					$sDebug = $sDebug . $sDebugE;
					}
				if ($bEnviaEncuesta) {
					list($sMensaje, $sErrorE, $sDebugE) = f3000_EnviaCorreosAtencion($DATA, $DATA['saiu19agno'], $objDB, $bDebug);
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
		list($sErrorCerrando, $sDebugCerrar)=f3019_Cerrar($DATA['saiu19id'], $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugCerrar;
		}
	//if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' InfoDepura<br>';}
	return array($DATA, $sError, $iTipoError, $bCerrando, $sErrorCerrando, $sDebug);
	}
function f3019_db_Eliminar($saiu19agno, $saiu19id, $objDB, $bDebug=false){
	$iCodModulo=3019;
	$bAudita[4]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3019='lg/lg_3019_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3019)){$mensajes_3019='lg/lg_3019_es.php';}
	require $mensajes_todas;
	require $mensajes_3019;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	$saiu19id=numeros_validar($saiu19id);
	// Traer los datos para hacer las validaciones.
	$sTabla19='saiu19chat_'.$saiu19agno;
	if ($sError==''){
		$sSQL='SELECT * FROM '.$sTabla19.' WHERE saiu19id='.$saiu19id.'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$filabase=$objDB->sf($tabla);
			}else{
			$sError='No se encuentra el registro solicitado {Ref: '.$saiu19id.'}';
			}
		}
	if ($sError==''){
		if (!seg_revisa_permiso($iCodModulo, 4, $objDB)){
			$sError=$ERR['4'];
			}
		}
	if ($sError==''){
		$sSQL='SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=3019';
		$tablaor=$objDB->ejecutasql($sSQL);
		while ($filaor=$objDB->sf($tablaor)){
			$sSQL='SELECT '.$filaor['campoid'].' FROM '.$filaor['tabla'].' WHERE '.$filaor['camporev'].'='.$_REQUEST['saiu19id'].' LIMIT 0, 1';
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
		if ($filabase['saiu19idsolicitante']!=0){
			//Retirar al anterior.
			$valores3000[1]=$filabase['saiu19idsolicitante'];
			$valores3000[2]=$iCodModulo;
			$valores3000[3]=$filabase['saiu19agno'];
			$valores3000[4]=$filabase['saiu19id'];
			f3000_Retirar($valores3000, $objDB, $bDebug);
			}
		$sWhere='saiu19id='.$saiu19id.'';
		//$sWhere='saiu19consec='.$filabase['saiu19consec'].' AND saiu19tiporadicado='.$filabase['saiu19tiporadicado'].' AND saiu19mes='.$filabase['saiu19mes'].' AND saiu19agno='.$filabase['saiu19agno'].'';
		$sSQL='DELETE FROM '.$sTabla19.' WHERE '.$sWhere.';';
		$result=$objDB->ejecutasql($sSQL);
		if ($result==false){
			$sError=$ERR['falla_eliminar'].' .. <!-- '.$sSQL.' -->';
			}else{
			if ($bAudita[4]){seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $saiu19id, $sWhere, $objDB);}
			}
		}
	return array($sError, $iTipoError, $sDebug);
	}
function f3019_TituloBusqueda(){
	return 'B&uacute;squeda de Sesiones de chat';
	}
function f3019_ParametrosBusqueda(){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3019='lg/lg_3019_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3019)){$mensajes_3019='lg/lg_3019_es.php';}
	require $mensajes_todas;
	require $mensajes_3019;
	$sParams='<label class="Label90">
	'.$ETI['msg_bnombre'].'
	</label>
	<label>
	<input id="b3019nombre" name="b3019nombre" type="text" value="" onchange="paginarbusqueda()" />
	</label>';
	return $sParams;
	}
function f3019_JavaScriptBusqueda($iModuloBusca){
	$sRes='var sCampo=window.document.frmedita.scampobusca.value;
	var params=new Array();
	params[100]=sCampo;
	params[101]=window.document.frmedita.paginabusqueda.value;
	params[102]=window.document.frmedita.lppfbusqueda.value;
	params[103]=window.document.frmedita.b3019nombre.value;
	xajax_f'.$iModuloBusca.'_HtmlBusqueda(params);';
	return $sRes;
	}
function f3019_TablaDetalleBusquedas($aParametros, $objDB){
	$res='';
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3019='lg/lg_3019_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3019)){$mensajes_3019='lg/lg_3019_es.php';}
	require $mensajes_todas;
	require $mensajes_3019;
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
		return array($sLeyenda.'<input id="paginaf3019" name="paginaf3019" type="hidden" value="'.$pagina.'"/><input id="lppf3019" name="lppf3019" type="hidden" value="'.$lineastabla.'"/>', $sDebug);
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
	$sTitulos='Agno, Mes, Tiporadicado, Consec, Id, Dia, Hora, Minuto, Estado, Chat, Solicitante, Tipointeresado, Clasesolicitud, Tiposolicitud, Temasolicitud, Zona, Centro, Codpais, Coddepto, Codciudad, Escuela, Programa, Periodo, Numorigen, Pqrs, Detalle, Horafin, Minutofin, Paramercadeo, Responsable, Tiemprespdias, Tiempresphoras, Tiemprespminutos, Solucion, Caso';
	$sSQL='SELECT TB.saiu19agno, TB.saiu19mes, TB.saiu19tiporadicado, TB.saiu19consec, TB.saiu19id, TB.saiu19dia, TB.saiu19hora, TB.saiu19minuto, T9.saiu11nombre, T10.saiu27nombre, T11.unad11razonsocial AS C11_nombre, T12.bita07nombre, T13.saiu01titulo, T14.saiu02titulo, T15.saiu03titulo, T16.unad23nombre, T17.unad24nombre, T18.unad18nombre, T19.unad19nombre, T20.unad20nombre, T21.core12nombre, T22.core09nombre, T23.exte02nombre, TB.saiu19numorigen, TB.saiu19idpqrs, TB.saiu19detalle, TB.saiu19horafin, TB.saiu19minutofin, TB.saiu19paramercadeo, T30.unad11razonsocial AS C30_nombre, TB.saiu19tiemprespdias, TB.saiu19tiempresphoras, TB.saiu19tiemprespminutos, TB.saiu19solucion, TB.saiu19idcaso, TB.saiu19estado, TB.saiu19idchat, TB.saiu19idsolicitante, T11.unad11tipodoc AS C11_td, T11.unad11doc AS C11_doc, TB.saiu19tipointeresado, TB.saiu19clasesolicitud, TB.saiu19tiposolicitud, TB.saiu19temasolicitud, TB.saiu19idzona, TB.saiu19idcentro, TB.saiu19codpais, TB.saiu19coddepto, TB.saiu19codciudad, TB.saiu19idescuela, TB.saiu19idprograma, TB.saiu19idperiodo, TB.saiu19idresponsable, T30.unad11tipodoc AS C30_td, T30.unad11doc AS C30_doc 
FROM saiu19chat AS TB, saiu11estadosol AS T9, saiu27chats AS T10, unad11terceros AS T11, bita07tiposolicitante AS T12, saiu01claseser AS T13, saiu02tiposol AS T14, saiu03temasol AS T15, unad23zona AS T16, unad24sede AS T17, unad18pais AS T18, unad19depto AS T19, unad20ciudad AS T20, core12escuela AS T21, core09programa AS T22, exte02per_aca AS T23, unad11terceros AS T30 
WHERE '.$sSQLadd1.' TB.saiu19estado=T9.saiu11id AND TB.saiu19idchat=T10.saiu27id AND TB.saiu19idsolicitante=T11.unad11id AND TB.saiu19tipointeresado=T12.bita07id AND TB.saiu19clasesolicitud=T13.saiu01id AND TB.saiu19tiposolicitud=T14.saiu02id AND TB.saiu19temasolicitud=T15.saiu03id AND TB.saiu19idzona=T16.unad23id AND TB.saiu19idcentro=T17.unad24id AND TB.saiu19codpais=T18.unad18codigo AND TB.saiu19coddepto=T19.unad19codigo AND TB.saiu19codciudad=T20.unad20codigo AND TB.saiu19idescuela=T21.core12id AND TB.saiu19idprograma=T22.core09id AND TB.saiu19idperiodo=T23.exte02id AND TB.saiu19idresponsable=T30.unad11id '.$sSQLadd.'
ORDER BY TB.saiu19agno, TB.saiu19mes, TB.saiu19tiporadicado, TB.saiu19consec';
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
			//return array(cadena_codificar($sErrConsulta.'<input id="paginaf3019" name="paginaf3019" type="hidden" value="'.$pagina.'"/><input id="lppf3019" name="lppf3019" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
<td><b>'.$ETI['saiu19agno'].'</b></td>
<td><b>'.$ETI['saiu19mes'].'</b></td>
<td><b>'.$ETI['saiu19tiporadicado'].'</b></td>
<td><b>'.$ETI['saiu19consec'].'</b></td>
<td><b>'.$ETI['saiu19dia'].'</b></td>
<td><b>'.$ETI['saiu19hora'].'</b></td>
<td><b>'.$ETI['saiu19estado'].'</b></td>
<td><b>'.$ETI['saiu19idchat'].'</b></td>
<td colspan="2"><b>'.$ETI['saiu19idsolicitante'].'</b></td>
<td><b>'.$ETI['saiu19tipointeresado'].'</b></td>
<td><b>'.$ETI['saiu19clasesolicitud'].'</b></td>
<td><b>'.$ETI['saiu19tiposolicitud'].'</b></td>
<td><b>'.$ETI['saiu19temasolicitud'].'</b></td>
<td><b>'.$ETI['saiu19idzona'].'</b></td>
<td><b>'.$ETI['saiu19idcentro'].'</b></td>
<td><b>'.$ETI['saiu19codpais'].'</b></td>
<td><b>'.$ETI['saiu19coddepto'].'</b></td>
<td><b>'.$ETI['saiu19codciudad'].'</b></td>
<td><b>'.$ETI['saiu19idescuela'].'</b></td>
<td><b>'.$ETI['saiu19idprograma'].'</b></td>
<td><b>'.$ETI['saiu19idperiodo'].'</b></td>
<td><b>'.$ETI['saiu19numorigen'].'</b></td>
<td><b>'.$ETI['saiu19idpqrs'].'</b></td>
<td><b>'.$ETI['saiu19detalle'].'</b></td>
<td><b>'.$ETI['saiu19horafin'].'</b></td>
<td><b>'.$ETI['saiu19paramercadeo'].'</b></td>
<td colspan="2"><b>'.$ETI['saiu19idresponsable'].'</b></td>
<td><b>'.$ETI['saiu19tiemprespdias'].'</b></td>
<td><b>'.$ETI['saiu19tiempresphoras'].'</b></td>
<td><b>'.$ETI['saiu19tiemprespminutos'].'</b></td>
<td><b>'.$ETI['saiu19solucion'].'</b></td>
<td><b>'.$ETI['saiu19idcaso'].'</b></td>
<td align="right">
'.html_paginador('paginabusqueda', $registros, $lineastabla, $pagina, 'paginarbusqueda()').'
'.html_lpp('lppfbusqueda', $lineastabla, 'paginarbusqueda()').'
</td>
</tr></thead>';
	$tlinea=1;
	while($filadet=$objDB->sf($tabladetalle)){
		$sPrefijo='<a href="javascript:Devuelve(\''.$filadet['saiu19id'].'\');">';
		$sSufijo='</a>';
		$tlinea++;
		$et_saiu19hora=html_TablaHoraMin($filadet['saiu19hora'], $filadet['saiu19minuto']);
		$et_saiu19horafin=html_TablaHoraMin($filadet['saiu19horafin'], $filadet['saiu19minutofin']);
		$res=$res.'<tr onmouseover="cambia_color_over(this);" onmouseout="cambia_color_out(this);">
<td>'.$sPrefijo.$filadet['saiu19agno'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu19mes'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['saiu16nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu19consec'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu19dia'].$sSufijo.'</td>
<td>'.$sPrefijo.$et_saiu19hora.$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['saiu11nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['saiu27nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['C11_td'].' '.$filadet['C11_doc'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['C11_nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['bita07nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['saiu01titulo']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['saiu02titulo']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['saiu03titulo']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['unad23nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['unad24nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu19codpais'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu19coddepto'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu19codciudad'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['core12nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['core09nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['exte02nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['saiu19numorigen']).$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu19idpqrs'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu19detalle'].$sSufijo.'</td>
<td>'.$sPrefijo.$et_saiu19horafin.$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu19paramercadeo'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['C30_td'].' '.$filadet['C30_doc'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['C30_nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu19tiemprespdias'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu19tiempresphoras'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu19tiemprespminutos'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu19solucion'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu19idcaso'].$sSufijo.'</td>
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
function f3019_RevTabla_saiu19chat($sContenedor, $objDB, $bDebug = false)
{
	list($sError, $sDebug) = f3019_RevisarTabla($sContenedor, $objDB, $bDebug);
	return array($sError, $sDebug);
}
function f3019_ConsultaResponsable($DATA, $objDB, $bDebug = false)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_3019 = 'lg/lg_3019_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_3019)) {
		$mensajes_3019 = 'lg/lg_3019_es.php';
	}
	require $mensajes_todas;
	require $mensajes_3019;
	$sError = '';
	$iTipoError = 0;
	$sDebug = '';
	$saiu19idunidadcaso = 0;
	$saiu19idequipocaso = 0;
	$saiu19idsupervisorcaso = 0;
	$saiu19idresponsablecaso = 0;
	$saiu19tiemprespdias = 0;
	$saiu19tiempresphoras = 0;
	if (isset($DATA['saiu19temasolicitud']) == 0) {
		$DATA['saiu19temasolicitud'] = '';
	}
	if ($DATA['saiu19temasolicitud'] == '') {
		$sError = $ERR['saiu19temasolicitud'] . $sSepara . $sError;
	}
	if ($sError == '') {
		$sSQL = 'SELECT saiu03idunidadresp1, saiu03idequiporesp1, saiu03idliderrespon1, saiu03tiemprespdias1, saiu03tiempresphoras1
		FROM saiu03temasol
		WHERE saiu03id = ' . $DATA['saiu19temasolicitud'] . '';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' Consulta responsable solicitud ' . $sSQL . '<br>';
		}
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			$saiu19idunidadcaso = $fila['saiu03idunidadresp1'];
			$saiu19idequipocaso = $fila['saiu03idequiporesp1'];
			$saiu19idsupervisorcaso = $fila['saiu03idliderrespon1'];
			$saiu19idresponsablecaso = $saiu19idsupervisorcaso;
			$saiu19tiemprespdias = $fila['saiu03tiemprespdias1'];
			$saiu19tiempresphoras = $fila['saiu03tiempresphoras1'];
		} else {
			$sError = $sError . 'No se ha configurado el tema de solicitud.';
		}
	}
	return array($saiu19idunidadcaso, $saiu19idequipocaso, $saiu19idsupervisorcaso, $saiu19idresponsablecaso, $saiu19tiemprespdias, $saiu19tiempresphoras, $sError, $iTipoError, $sDebug);
}
function f3019_ActualizarAtiende($DATA, $objDB, $bDebug = false, $idTercero)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_3019 = 'lg/lg_3019_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_3019)) {
		$mensajes_3019 = 'lg/lg_3019_es.php';
	}
	require $mensajes_todas;
	require $mensajes_3019;
	$sTabla19 = 'saiu19chat_' . $DATA['saiu19agno'];
	$sResultado = '';
	$sError = '';
	$sDebug = '';
	$iTipoError = 0;
	if (!$objDB->bexistetabla($sTabla19)) {
		$sError = $sError . 'No ha sido posible acceder al contenedor de datos';
	}
	if ($sError == '') {
		$sSQL = 'SELECT saiu19agno, saiu19mes, saiu19estado, saiu19temasolicitud FROM ' . $sTabla19 . ' WHERE saiu19id=' . $DATA['saiu19id'] . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			list($DATA['saiu19idunidadcaso'], $DATA['saiu19idequipocaso'], $DATA['saiu19idsupervisorcaso'], $DATA['saiu19idresponsablecaso'], $saiu19tiemprespdias, $saiu19tiempresphoras, $sErrorF, $iTipoError, $sDebugF) = f3019_ConsultaResponsable($fila, $objDB, $bDebug);
			$sError = $sError . $sErrorF;
			if ($bDebug) {
				$sDebug = $sDebug . $sDebugF;
			}
		} else {
			$sError = $sError . $ETI['saiu19noexiste'];
		}
	}
	if ($sError == '') {
		if ($saiu19tiemprespdias > 0) { // Cálculo fecha probable de respuesta
			$iFechaBase = fecha_DiaMod();
			$saiu19fecharespprob = fecha_NumSumarDias($iFechaBase, $saiu19tiemprespdias);
		}
		list($DATA, $sErrorE, $iTipoError, $sDebugGuardar) = f3019_db_GuardarV2($DATA, $objDB, $bDebug, $idTercero);
		$sError = $sError . $sErrorE;
		$sDebug = $sDebug . $sDebugGuardar;
		if ($sError == '') {
			$sError = '<b>' . $ETI['msg_itemguardado'] . '</b>';
			$iTipoError = 1;
		}
	}
	return array($DATA, $sError, $iTipoError, $sDebug);
}
function elimina_archivo_saiu19idarchivo($idpadre, $iAgno)
{
	$_SESSION['u_ultimominuto'] = iminutoavance();
	require './app.php';
	$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto != '') {
		$objDB->dbPuerto = $APP->dbpuerto;
	}
	$objDB->xajax();
	$sTabla19 = 'saiu19chat_' . $iAgno;
	archivo_eliminar($sTabla19, 'saiu19id', 'saiu19idorigen', 'saiu19idarchivo', $idpadre, $objDB);
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->call("limpia_saiu19idarchivo");
	return $objResponse;
}
function elimina_archivo_saiu19idarchivorta($idpadre, $iAgno)
{
	$_SESSION['u_ultimominuto'] = iminutoavance();
	require './app.php';
	$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto != '') {
		$objDB->dbPuerto = $APP->dbpuerto;
	}
	$objDB->xajax();
	$sTabla19 = 'saiu19chat_' . $iAgno;
	archivo_eliminar($sTabla19, 'saiu19id', 'saiu19idorigenrta', 'saiu19idarchivorta', $idpadre, $objDB);
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->call("limpia_saiu19idarchivorta");
	return $objResponse;
}
function f3019_HTMLComboV2_btema($objDB, $objCombos, $valor, $vrbtipo)
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
		$objCombos->sAccion = 'paginarf3019()';
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
function f3019_Combobtema($aParametros)
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
	$html_btema = f3019_HTMLComboV2_btema($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_btema', 'innerHTML', $html_btema);
	$objResponse->call('jQuery("#btema").chosen({no_results_text: "No existen coincidencias: ",width: "100%"})');
	$objResponse->call('paginarf3019');
	return $objResponse;
}