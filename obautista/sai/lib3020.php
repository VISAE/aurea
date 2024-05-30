<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2020 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.25.4 domingo, 19 de julio de 2020
--- 3020 saiu20correos
*/
/** Archivo lib3020.php.
* Libreria 3020 saiu20correos
* @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
* @date domingo, 19 de julio de 2020
*/
function f3020_HTMLComboV2_saiu20agno($objDB, $objCombos, $valor){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('saiu20agno', $valor, false, '{'.$ETI['msg_seleccione'].'}');
	//$objCombos->iAncho=450;
	$objCombos->sAccion='RevisaLlave();';
	$sSQL='SHOW TABLES LIKE "saiu20correo%"';
	//if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Total Periodo: Lista de contenedores: '.$sSQL.'<br>';}
	$tablac=$objDB->ejecutasql($sSQL);
	while($filac=$objDB->sf($tablac)){
		$sAgno=substr($filac[0], 13);
		$objCombos->addItem($sAgno, $sAgno);
	}
	$res=$objCombos->html('', $objDB);
	return $res;
	}
function f3020_HTMLComboV2_saiu20mes($objDB, $objCombos, $valor){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	/*
	$objCombos->nuevo('saiu20mes', $valor, false, '{'.$ETI['msg_seleccione'].'}');
	//$objCombos->iAncho=450;
	$objCombos->sAccion='RevisaLlave();';
	$sSQL='SELECT  AS id,  AS nombre FROM ';
	$res=$objCombos->html($sSQL, $objDB);
	*/
	$res=html_ComboMes('saiu20mes', $valor, false, 'RevisaLlave();');
	return $res;
	}
function f3020_HTMLComboV2_saiu20tiporadicado($objDB, $objCombos, $valor){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('saiu20tiporadicado', $valor, false, '{'.$ETI['msg_seleccione'].'}');
	//$objCombos->iAncho=450;
	$objCombos->sAccion='RevisaLlave();';
	$sSQL='SELECT saiu16id AS id, saiu16nombre AS nombre FROM saiu16tiporadicado WHERE saiu16id IN (1, 3) ORDER BY saiu16nombre';
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f3020_HTMLComboV2_saiu20tiposolicitud($objDB, $objCombos, $valor){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('saiu20tiposolicitud', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	$objCombos->sAccion='carga_combo_saiu20temasolicitud();';
	//$objCombos->iAncho=450;
	$sSQL='SELECT TB.saiu02id AS id, CONCAT(TB.saiu02titulo, " [", T1.saiu01titulo, "]") AS nombre 
	FROM saiu02tiposol AS TB, saiu01claseser AS T1 
	WHERE TB.saiu02id>0 AND TB.saiu02ordenllamada<9 AND TB.saiu02clasesol=T1.saiu01id 
	ORDER BY TB.saiu02ordenllamada, TB.saiu02titulo';
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f3020_HTMLComboV2_saiu20temasolicitud($objDB, $objCombos, $valor, $vrsaiu20tiposolicitud){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('saiu20temasolicitud', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	//$objCombos->iAncho=450;
	$sSQL='';
	if ((int)$vrsaiu20tiposolicitud==0){
		$objCombos->sAccion='carga_combo_saiu20tiposolicitud()';
		$sSQL='SELECT TB.saiu03id AS id, CONCAT(TB.saiu03titulo, " [", T2.saiu02titulo, "]") AS nombre 
		FROM saiu03temasol AS TB, saiu02tiposol AS T2 
		WHERE TB.saiu03id>0 AND TB.saiu03ordenllamada<9 AND TB.saiu03tiposol=T2.saiu02id
		ORDER BY TB.saiu03ordensoporte, TB.saiu03titulo';
		}else{
		$sSQL='SELECT saiu03id AS id, saiu03titulo AS nombre 
		FROM saiu03temasol 
		WHERE saiu03id>0 AND saiu03ordenllamada<9 AND saiu03tiposol='.$vrsaiu20tiposolicitud.'
		ORDER BY saiu03ordenllamada, saiu03titulo';
		}
	//if ((int)$vrsaiu20tiposolicitud!=0){}
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f3020_HTMLComboV2_saiu20idcentro($objDB, $objCombos, $valor, $vrsaiu20idzona){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$sCondi='';
	$objCombos->nuevo('saiu20idcentro', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	//$objCombos->iAncho=450;
	$sSQL='';
	if ((int)$vrsaiu20idzona!=0) {
		$sSQL='SELECT unad24id AS id, CONCAT(unad24nombre, CASE unad24activa WHEN "S" THEN "" ELSE " [INACTIVA]" END) AS nombre 
		FROM unad24sede 
		WHERE unad24idzona='.$vrsaiu20idzona.' AND unad24id>0 
		ORDER BY unad24activa DESC, unad24nombre';
	}
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f3020_HTMLComboV2_saiu20coddepto($objDB, $objCombos, $valor, $vrsaiu20codpais){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('saiu20coddepto', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	//$objCombos->iAncho=450;
	$objCombos->sAccion='carga_combo_saiu20codciudad()';
	$sSQL='';
	if ((int)$vrsaiu20codpais!=0){
		$sSQL='SELECT unad19codigo AS id, unad19nombre AS nombre FROM unad19depto WHERE unad19codpais="'.$vrsaiu20codpais.'" ORDER BY unad19nombre';
		}
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f3020_HTMLComboV2_saiu20codciudad($objDB, $objCombos, $valor, $vrsaiu20coddepto){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('saiu20codciudad', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	//$objCombos->iAncho=450;
	$sSQL='';
	if ((int)$vrsaiu20coddepto!=0){
		$sSQL='SELECT unad20codigo AS id, unad20nombre AS nombre FROM unad20ciudad WHERE unad20coddepto="'.$vrsaiu20coddepto.'" ORDER BY unad20nombre';
		}
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f3020_Combosaiu20tiposolicitud($aParametros){
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
		$html_saiu20tiposolicitud=f3020_HTMLComboV2_saiu20tiposolicitud($objDB, $objCombos, $idTipo);
		$html_saiu20temasolicitud=f3020_HTMLComboV2_saiu20temasolicitud($objDB, $objCombos, $idTema, $idTipo);
		$objDB->CerrarConexion();
		$objResponse=new xajaxResponse();
		$objResponse->assign('div_saiu20tiposolicitud', 'innerHTML', $html_saiu20tiposolicitud);
		$objResponse->assign('div_saiu20temasolicitud', 'innerHTML', $html_saiu20temasolicitud);
		$objResponse->call('$("#saiu20tiposolicitud").chosen()');
		$objResponse->call('$("#saiu20temasolicitud").chosen()');
		return $objResponse;
		}else{
		$objDB->CerrarConexion();
		}
	}
function f3020_Combosaiu20temasolicitud($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$objCombos=new clsHtmlCombos();
	$html_saiu20temasolicitud=f3020_HTMLComboV2_saiu20temasolicitud($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_saiu20temasolicitud', 'innerHTML', $html_saiu20temasolicitud);
	$objResponse->call('$("#saiu20temasolicitud").chosen({width:"100%"})');
	return $objResponse;
	}
function f3020_HTMLComboV2_saiu20idprograma($objDB, $objCombos, $valor, $vrsaiu20idescuela){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('saiu20idprograma', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	$objCombos->addItem('0', $ETI['msg_na']);
	//$objCombos->iAncho=450;
	$sCondiEscuela=' AND TB.core09idescuela="'.$vrsaiu20idescuela.'"';
	$sTabla2='';
	$sCampos2='';
	if ($vrsaiu20idescuela==''){
		$sCondiEscuela=' AND TB.core09idescuela=T12.core12id';
		$sTabla2=', core12escuela AS T12';
		$sCampos2=', " [", T12.core12sigla, "]"';
		}
	$sSQL='SELECT TB.core09id AS id, CONCAT(TB.core09nombre, " [", TB.core09codigo, "]"'.$sCampos2.') AS nombre FROM core09programa AS TB'.$sTabla2.' WHERE TB.core09id>0'.$sCondiEscuela;
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f3020_Combosaiu20idcentro($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$objCombos=new clsHtmlCombos();
	$html_saiu20idcentro=f3020_HTMLComboV2_saiu20idcentro($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_saiu20idcentro', 'innerHTML', $html_saiu20idcentro);
	$objResponse->call('$("#saiu20idcentro").chosen({width:"100%"})');
	return $objResponse;
	}
function f3020_Combosaiu20coddepto($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$objCombos=new clsHtmlCombos();
	$html_saiu20coddepto=f3020_HTMLComboV2_saiu20coddepto($objDB, $objCombos, '', $aParametros[0]);
	$html_saiu20codciudad=f3020_HTMLComboV2_saiu20codciudad($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_saiu20coddepto', 'innerHTML', $html_saiu20coddepto);
	$objResponse->call('$("#saiu20coddepto").chosen({width:"100%"})');
	$objResponse->assign('div_saiu20codciudad', 'innerHTML', $html_saiu20codciudad);
	$objResponse->call('$("#saiu20codciudad").chosen({width:"100%"})');
	return $objResponse;
	}
function f3020_Combosaiu20codciudad($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$objCombos=new clsHtmlCombos();
	$html_saiu20codciudad=f3020_HTMLComboV2_saiu20codciudad($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_saiu20codciudad', 'innerHTML', $html_saiu20codciudad);
	$objResponse->call('$("#saiu20codciudad").chosen({width:"100%"})');
	return $objResponse;
	}
function f3020_Combosaiu20idprograma($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$objCombos=new clsHtmlCombos();
	$html_saiu20idprograma=f3020_HTMLComboV2_saiu20idprograma($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_saiu20idprograma', 'innerHTML', $html_saiu20idprograma);
	$objResponse->call('$("#saiu20idprograma").chosen({width:"100%"})');
	return $objResponse;
	}
function f3020_ExisteDato($datos){
	if(!is_array($datos)){$datos=json_decode(str_replace('\"','"',$datos),true);}
	$_SESSION['u_ultimominuto']=iminutoavance();
	$bHayLlave=true;
	$saiu20agno=numeros_validar($datos[1]);
	if ($saiu20agno==''){$bHayLlave=false;}
	$saiu20mes=numeros_validar($datos[2]);
	if ($saiu20mes==''){$bHayLlave=false;}
	$saiu20tiporadicado=numeros_validar($datos[3]);
	if ($saiu20tiporadicado==''){$bHayLlave=false;}
	$saiu20consec=numeros_validar($datos[4]);
	if ($saiu20consec==''){$bHayLlave=false;}
	if ($bHayLlave){
		require './app.php';
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		$sSQL='SELECT 1 FROM saiu20correo_'.$saiu20agno.' WHERE saiu20agno='.$saiu20agno.' AND saiu20mes='.$saiu20mes.' AND saiu20tiporadicado='.$saiu20tiporadicado.' AND saiu20consec='.$saiu20consec.'';
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
function f3020_Busquedas($aParametros){
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3020='lg/lg_3020_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3020)){$mensajes_3020='lg/lg_3020_es.php';}
	require $mensajes_todas;
	require $mensajes_3020;
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
		case 'saiu20idsolicitante':
		require $APP->rutacomun.'lib111.php';
		$sTabla=f111_TablaDetalleBusquedas($aParametrosB, $objDB);
		$sTitulo='Busqueda de terceros';
		$sParams=f111_ParametrosBusqueda();
		$sJavaBusqueda=f111_JavaScriptBusqueda(3020);
		break;
		case 'saiu20idresponsable':
		require $APP->rutacomun.'lib111.php';
		$sTabla=f111_TablaDetalleBusquedas($aParametrosB, $objDB);
		$sTitulo='Busqueda de terceros';
		$sParams=f111_ParametrosBusqueda();
		$sJavaBusqueda=f111_JavaScriptBusqueda(3020);
		break;
		}
	$sTitulo='<h2>'.$ETI['titulo_3020'].' - '.$sTitulo.'</h2>';
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97titulo', 'innerHTML', $sTitulo);
	$objResponse->assign('div_97params', 'innerHTML', $sParams);
	$objResponse->assign('div_97tabla', 'innerHTML', $sTabla);
	$objResponse->setFunction('paginarbusqueda','',$sJavaBusqueda);
	$objResponse->call('expandesector(97)');
	return $objResponse;
	}
function f3020_HtmlBusqueda($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$sError='';
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$sDetalle='';
	switch($aParametros[100]){
		case 'saiu20idsolicitante':
		require $APP->rutacomun.'lib111.php';
		$sDetalle=f111_TablaDetalleBusquedas($aParametros, $objDB);
		break;
		case 'saiu20idresponsable':
		require $APP->rutacomun.'lib111.php';
		$sDetalle=f111_TablaDetalleBusquedas($aParametros, $objDB);
		break;
		}
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97tabla', 'innerHTML', $sDetalle);
	return $objResponse;
	}
function f3020_TablaDetalleV2($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3020='lg/lg_3020_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3020)){$mensajes_3020='lg/lg_3020_es.php';}
	require $mensajes_todas;
	require $mensajes_3020;
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
		return array($sLeyenda.'<input id="paginaf3020" name="paginaf3020" type="hidden" value="'.$pagina.'"/><input id="lppf3020" name="lppf3020" type="hidden" value="'.$lineastabla.'"/>', $sDebug);
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
		$sSQLadd = $sSQLadd . ' AND TB.saiu20estado=' . $iEstado . '';
	}
	switch($bListar){
		case 1:
			$sSQLadd=$sSQLadd.' AND TB.saiu20idresponsable=' . $idTercero. '';		
			break;
		case 2:
			$sSQLadd=$sSQLadd.' AND TB.saiu20idresponsablecaso=' . $idTercero. '';		
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
				$sSQLadd = $sSQLadd . ' AND TB.saiu20idequipocaso IN (' . $sEquipos . ')';
			} else {
				$sSQLadd = $sSQLadd . ' AND TB.saiu20idresponsablecaso=' . $idTercero . '';
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
		$sSQLadd = $sSQLadd . ' AND TB.saiu20tiposolicitud=' . $bcategoria . '';
	}
	if ($btema !== '') {
		$sSQLadd = $sSQLadd . ' AND TB.saiu20temasolicitud=' . $btema . '';
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
	$sTitulos='Agno, Mes, Tiporadicado, Consec, Id, Dia, Hora, Minuto, Estado, Correo, Solicitante, Tipointeresado, Clasesolicitud, Tiposolicitud, Temasolicitud, Zona, Centro, Codpais, Coddepto, Codciudad, Escuela, Programa, Periodo, Numorigen, Pqrs, Detalle, Horafin, Minutofin, Paramercadeo, Responsable, Tiemprespdias, Tiempresphoras, Tiemprespminutos';
	$registros=0;
	$bGigante=false; //En caso de que la tabla sea muy grande pasarlo a true
	$sLimite='';
	if ($bGigante){
		$sSQL='SELECT COUNT(1) AS Total 
FROM saiu20correo_'.$iAgno.' AS TB, unad11terceros AS T12
WHERE '.$sSQLadd1.' TB.saiu20idsolicitante=T12.unad11id '.$sSQLadd.'';
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
	//, TB.saiu20idpqrs, TB.saiu20detalle, TB.saiu20horafin, TB.saiu20minutofin, TB.saiu20paramercadeo, TB.saiu20tiemprespdias, TB.saiu20tiempresphoras, TB.saiu20tiemprespminutos, TB.saiu20tipointeresado, TB.saiu20clasesolicitud, TB.saiu20tiposolicitud, TB.saiu20temasolicitud, TB.saiu20idzona, TB.saiu20idcentro, TB.saiu20codpais, TB.saiu20coddepto, TB.saiu20codciudad, TB.saiu20idescuela, TB.saiu20idprograma, TB.saiu20idperiodo, TB.saiu20idresponsable
	$sSQL='SELECT TB.saiu20agno, TB.saiu20mes, TB.saiu20consec, TB.saiu20id, TB.saiu20dia, TB.saiu20hora, TB.saiu20minuto, 
T12.unad11razonsocial AS C12_nombre, TB.saiu20tiposolicitud, saiu20temasolicitud, TB.saiu20estado, T12.unad11tipodoc AS C12_td, 
T12.unad11doc AS C12_doc, TB.saiu20idsolicitante, TB.saiu20tiporadicado, TB.saiu20solucion 
FROM saiu20correo_'.$iAgno.' AS TB, unad11terceros AS T12
WHERE '.$sSQLadd1.' TB.saiu20idsolicitante=T12.unad11id '.$sSQLadd.'
ORDER BY TB.saiu20agno DESC, TB.saiu20mes DESC, TB.saiu20dia DESC, TB.saiu20tiporadicado, TB.saiu20consec DESC';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_3020" name="consulta_3020" type="hidden" value="'.$sSQLlista.'"/>
<input id="titulos_3020" name="titulos_3020" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objDB->ejecutasql($sSQL.$sLimite);
	if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 3020: '.$sSQL.$sLimite.'<br>';}
	if ($tabladetalle==false){
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		//$sLeyenda=$sSQL;
		}else{
		if (!$bGigante){
			$registros=$objDB->nf($tabladetalle);
			if ($registros==0){
				//return array(cadena_codificar($sErrConsulta.'<input id="paginaf3020" name="paginaf3020" type="hidden" value="'.$pagina.'"/><input id="lppf3020" name="lppf3020" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
<td><b>'.$ETI['saiu20consec'].'</b></td>
<td><b>'.$ETI['saiu20estado'].'</b></td>
<td colspan="2"><b>'.$ETI['saiu20idsolicitante'].'</b></td>
<td><b>'.$ETI['saiu20tiposolicitud'].'</b></td>
<td><b>'.$ETI['saiu20temasolicitud'].'</b></td>
<td><b>'.$ETI['saiu20solucion'].'</b></td>
<td align="right">
'.html_paginador('paginaf3020', $registros, $lineastabla, $pagina, 'paginarf3020()').'
'.html_lpp('lppf3020', $lineastabla, 'paginarf3020()').'
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
		$et_saiu20hora=html_TablaHoraMin($filadet['saiu20hora'], $filadet['saiu20minuto']);
		$et_saiu20idsolicitante_doc='';
		$et_saiu20idsolicitante_nombre='';
		if ($filadet['saiu20idsolicitante']!=0){
			$et_saiu20idsolicitante_doc=$sPrefijo.$filadet['C12_td'].' '.$filadet['C12_doc'].$sSufijo;
			$et_saiu20idsolicitante_nombre=$sPrefijo.cadena_notildes($filadet['C12_nombre']).$sSufijo;
			}
		$sEstado = '';
		if (isset($aEstado[$filadet['saiu20estado']])==0) {
			$sEstado = $ETI['definir'];
		} else {
			$sEstado = $aEstado[$filadet['saiu20estado']];
		}
		$sCategoria = '';
		if (isset($aCategoria[$filadet['saiu20tiposolicitud']])==0) {
			$sCategoria = $ETI['definir'];
		} else {
			$sCategoria = $aCategoria[$filadet['saiu20tiposolicitud']];
		}
		$sTema = '';
		if (isset($aTema[$filadet['saiu20temasolicitud']])==0) {
			$sTema = $ETI['definir'];
		} else {
			$sTema = $aTema[$filadet['saiu20temasolicitud']];
		}
		$sSolucion = '';
		if (isset($asaiu20solucion[$filadet['saiu20solucion']])==0) {
			$sSolucion = $ETI['definir'];
		} else {
			$sSolucion = $asaiu20solucion[$filadet['saiu20solucion']];
		}
		/*
		$et_saiu20horafin=html_TablaHoraMin($filadet['saiu20horafin'], $filadet['saiu20minutofin']);
		$et_saiu20idresponsable_doc='';
		$et_saiu20idresponsable_nombre='';
		if ($filadet['saiu20idresponsable']!=0){
			$et_saiu20idresponsable_doc=$sPrefijo.$filadet['C31_td'].' '.$filadet['C31_doc'].$sSufijo;
			$et_saiu20idresponsable_nombre=$sPrefijo.cadena_notildes($filadet['C31_nombre']).$sSufijo;
			}
		*/
		if ($bAbierta){
			$sLink='<a href="javascript:cargaridf3020('.$filadet['saiu20agno'].','.$filadet['saiu20id'].')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$et_fecha=fecha_armar($filadet['saiu20dia'], $filadet['saiu20mes'], $filadet['saiu20agno']);
		$res=$res.'<tr'.$sClass.'>
<td>'.$sPrefijo.$et_fecha.$sSufijo.'</td>
<td>'.$sPrefijo.$et_saiu20hora.$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu20consec'].$sSufijo.'</td>
<td>'.$sPrefijo.$sEstado.$sSufijo.'</td>
<td>'.$et_saiu20idsolicitante_doc.'</td>
<td>'.$et_saiu20idsolicitante_nombre.'</td>
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
function f3020_HtmlTabla($aParametros){
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
	list($sDetalle, $sDebugTabla)=f3020_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f3020detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f3020_db_CargarPadre($DATA, $objDB, $bDebug=false){
	$sError='';
	$iTipoError=0;
	$sDebug='';
	require './app.php';
	$DATA['saiu20idsolicitante_td']=$APP->tipo_doc;
	$DATA['saiu20idsolicitante_doc']='';
	$DATA['saiu20idresponsable_td']=$APP->tipo_doc;
	$DATA['saiu20idresponsable_doc']='';
	if ($DATA['paso']==1){
		$sSQLcondi='saiu20agno='.$DATA['saiu20agno'].' AND saiu20mes='.$DATA['saiu20mes'].' AND saiu20tiporadicado='.$DATA['saiu20tiporadicado'].' AND saiu20consec='.$DATA['saiu20consec'].'';
		}else{
		$sSQLcondi='saiu20id='.$DATA['saiu20id'].'';
		}
	$sSQL='SELECT * FROM saiu20correo_'.$DATA['saiu20agno'].' WHERE '.$sSQLcondi;
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$DATA['saiu20agno']=$fila['saiu20agno'];
		$DATA['saiu20mes']=$fila['saiu20mes'];
		$DATA['saiu20tiporadicado']=$fila['saiu20tiporadicado'];
		$DATA['saiu20consec']=$fila['saiu20consec'];
		$DATA['saiu20id']=$fila['saiu20id'];
		$DATA['saiu20dia']=$fila['saiu20dia'];
		$DATA['saiu20hora']=$fila['saiu20hora'];
		$DATA['saiu20minuto']=$fila['saiu20minuto'];
		$DATA['saiu20estado']=$fila['saiu20estado'];
		$DATA['saiu20idcorreo']=$fila['saiu20idcorreo'];
		$DATA['saiu20idsolicitante']=$fila['saiu20idsolicitante'];
		$DATA['saiu20tipointeresado']=$fila['saiu20tipointeresado'];
		$DATA['saiu20clasesolicitud']=$fila['saiu20clasesolicitud'];
		$DATA['saiu20tiposolicitud']=$fila['saiu20tiposolicitud'];
		$DATA['saiu20temasolicitud']=$fila['saiu20temasolicitud'];
		$DATA['saiu20idzona']=$fila['saiu20idzona'];
		$DATA['saiu20idcentro']=$fila['saiu20idcentro'];
		$DATA['saiu20codpais']=$fila['saiu20codpais'];
		$DATA['saiu20coddepto']=$fila['saiu20coddepto'];
		$DATA['saiu20codciudad']=$fila['saiu20codciudad'];
		$DATA['saiu20idescuela']=$fila['saiu20idescuela'];
		$DATA['saiu20idprograma']=$fila['saiu20idprograma'];
		$DATA['saiu20idperiodo']=$fila['saiu20idperiodo'];
		$DATA['saiu20correoorigen']=$fila['saiu20correoorigen'];
		$DATA['saiu20idpqrs']=$fila['saiu20idpqrs'];
		$DATA['saiu20detalle']=$fila['saiu20detalle'];
		$DATA['saiu20horafin']=$fila['saiu20horafin'];
		$DATA['saiu20minutofin']=$fila['saiu20minutofin'];
		$DATA['saiu20paramercadeo']=$fila['saiu20paramercadeo'];
		$DATA['saiu20idresponsable']=$fila['saiu20idresponsable'];
		$DATA['saiu20tiemprespdias']=$fila['saiu20tiemprespdias'];
		$DATA['saiu20tiempresphoras']=$fila['saiu20tiempresphoras'];
		$DATA['saiu20tiemprespminutos']=$fila['saiu20tiemprespminutos'];
		$DATA['saiu20solucion']=$fila['saiu20solucion'];
		$DATA['saiu20idcaso']=$fila['saiu20idcaso'];
		$DATA['saiu20respuesta']=$fila['saiu20respuesta'];
		$bcargo=true;
		$DATA['paso']=2;
		$DATA['boculta3020']=0;
		$bLimpiaHijos=true;
		}else{
		$DATA['paso']=0;
		}
	return array($DATA, $sError, $iTipoError, $sDebug);
	}
function f3020_Cerrar($saiu20id, $objDB, $bDebug=false){
	$sInfo='';
	$sDebug='';
	return array($sInfo, $sDebug);
	}
function f3020_db_GuardarV2($DATA, $objDB, $bDebug=false){
	$iCodModulo=3020;
	$bAudita[2]=true;
	$bAudita[3]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3020='lg/lg_3020_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3020)){$mensajes_3020='lg/lg_3020_es.php';}
	require $mensajes_todas;
	require $mensajes_3020;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	$bCerrando=false;
	$sErrorCerrando='';
	// -- Se inicia validando todas las posibles entradas de usuario.
	/*
	if (isset($DATA['saiu20agno'])==0){$DATA['saiu20agno']='';}
	if (isset($DATA['saiu20mes'])==0){$DATA['saiu20mes']='';}
	if (isset($DATA['saiu20tiporadicado'])==0){$DATA['saiu20tiporadicado']='';}
	if (isset($DATA['saiu20consec'])==0){$DATA['saiu20consec']='';}
	if (isset($DATA['saiu20id'])==0){$DATA['saiu20id']='';}
	if (isset($DATA['saiu20dia'])==0){$DATA['saiu20dia']='';}
	if (isset($DATA['saiu20hora'])==0){$DATA['saiu20hora']='';}
	if (isset($DATA['saiu20minuto'])==0){$DATA['saiu20minuto']='';}
	if (isset($DATA['saiu20idcorreo'])==0){$DATA['saiu20idcorreo']='';}
	if (isset($DATA['saiu20idsolicitante'])==0){$DATA['saiu20idsolicitante']='';}
	if (isset($DATA['saiu20tipointeresado'])==0){$DATA['saiu20tipointeresado']='';}
	if (isset($DATA['saiu20clasesolicitud'])==0){$DATA['saiu20clasesolicitud']='';}
	if (isset($DATA['saiu20tiposolicitud'])==0){$DATA['saiu20tiposolicitud']='';}
	if (isset($DATA['saiu20temasolicitud'])==0){$DATA['saiu20temasolicitud']='';}
	if (isset($DATA['saiu20idzona'])==0){$DATA['saiu20idzona']='';}
	if (isset($DATA['saiu20idcentro'])==0){$DATA['saiu20idcentro']='';}
	if (isset($DATA['saiu20codpais'])==0){$DATA['saiu20codpais']='';}
	if (isset($DATA['saiu20coddepto'])==0){$DATA['saiu20coddepto']='';}
	if (isset($DATA['saiu20codciudad'])==0){$DATA['saiu20codciudad']='';}
	if (isset($DATA['saiu20idescuela'])==0){$DATA['saiu20idescuela']='';}
	if (isset($DATA['saiu20idprograma'])==0){$DATA['saiu20idprograma']='';}
	if (isset($DATA['saiu20idperiodo'])==0){$DATA['saiu20idperiodo']='';}
	if (isset($DATA['saiu20correoorigen'])==0){$DATA['saiu20correoorigen']='';}
	if (isset($DATA['saiu20detalle'])==0){$DATA['saiu20detalle']='';}
	if (isset($DATA['saiu20horafin'])==0){$DATA['saiu20horafin']='';}
	if (isset($DATA['saiu20minutofin'])==0){$DATA['saiu20minutofin']='';}
	if (isset($DATA['saiu20paramercadeo'])==0){$DATA['saiu20paramercadeo']='';}
	if (isset($DATA['saiu20idresponsable'])==0){$DATA['saiu20idresponsable']='';}
	if (isset($DATA['saiu20solucion'])==0){$DATA['saiu20solucion']='';}
	if (isset($DATA['saiu20respuesta'])==0){$DATA['saiu20respuesta']='';}
	*/
	$DATA['saiuid']=20;
	$DATA['saiu20agno']=numeros_validar($DATA['saiu20agno']);
	$DATA['saiu20mes']=numeros_validar($DATA['saiu20mes']);
	$DATA['saiu20tiporadicado']=numeros_validar($DATA['saiu20tiporadicado']);
	$DATA['saiu20consec']=numeros_validar($DATA['saiu20consec']);
	$DATA['saiu20dia']=numeros_validar($DATA['saiu20dia']);
	$DATA['saiu20hora']=numeros_validar($DATA['saiu20hora']);
	$DATA['saiu20minuto']=numeros_validar($DATA['saiu20minuto']);
	$DATA['saiu20idcorreo']=numeros_validar($DATA['saiu20idcorreo']);
	$DATA['saiu20idcorreootro']=htmlspecialchars(trim($DATA['saiu20idcorreootro']));
	$DATA['saiu20tipointeresado']=numeros_validar($DATA['saiu20tipointeresado']);
	$DATA['saiu20clasesolicitud']=numeros_validar($DATA['saiu20clasesolicitud']);
	$DATA['saiu20tiposolicitud']=numeros_validar($DATA['saiu20tiposolicitud']);
	$DATA['saiu20temasolicitud']=numeros_validar($DATA['saiu20temasolicitud']);
	$DATA['saiu20idzona']=numeros_validar($DATA['saiu20idzona']);
	$DATA['saiu20idcentro']=numeros_validar($DATA['saiu20idcentro']);
	$DATA['saiu20codpais']=htmlspecialchars(trim($DATA['saiu20codpais']));
	$DATA['saiu20coddepto']=htmlspecialchars(trim($DATA['saiu20coddepto']));
	$DATA['saiu20codciudad']=htmlspecialchars(trim($DATA['saiu20codciudad']));
	$DATA['saiu20idescuela']=numeros_validar($DATA['saiu20idescuela']);
	$DATA['saiu20idprograma']=numeros_validar($DATA['saiu20idprograma']);
	$DATA['saiu20idperiodo']=numeros_validar($DATA['saiu20idperiodo']);
	$DATA['saiu20correoorigen']=htmlspecialchars(trim($DATA['saiu20correoorigen']));
	$DATA['saiu20detalle']=htmlspecialchars(trim($DATA['saiu20detalle']));
	$DATA['saiu20fechafin']=numeros_validar($DATA['saiu20fechafin']);
	$DATA['saiu20horafin']=numeros_validar($DATA['saiu20horafin']);
	$DATA['saiu20minutofin']=numeros_validar($DATA['saiu20minutofin']);
	$DATA['saiu20paramercadeo']=numeros_validar($DATA['saiu20paramercadeo']);
	$DATA['saiu20solucion']=numeros_validar($DATA['saiu20solucion']);
	$DATA['saiu20respuesta']=htmlspecialchars(trim($DATA['saiu20respuesta']));
	$DATA['saiu20idcaso']=numeros_validar($DATA['saiu20idcaso']);
	$DATA['saiu20fecharespcaso']=numeros_validar($DATA['saiu20fecharespcaso']);
	$DATA['saiu20horarespcaso']=numeros_validar($DATA['saiu20horarespcaso']);
	$DATA['saiu20minrespcaso']=numeros_validar($DATA['saiu20minrespcaso']);
	$DATA['saiu20idunidadcaso']=numeros_validar($DATA['saiu20idunidadcaso']);
	$DATA['saiu20idequipocaso']=numeros_validar($DATA['saiu20idequipocaso']);
	$DATA['saiu20idsupervisorcaso']=numeros_validar($DATA['saiu20idsupervisorcaso']);
	$DATA['saiu20idresponsablecaso']=numeros_validar($DATA['saiu20idresponsablecaso']);
	$DATA['saiu20numref']=htmlspecialchars(trim($DATA['saiu20numref']));
	// -- Se inicializan las variables que puedan pasar vacias {Especialmente números}.
	//if ($DATA['saiu20dia']==''){$DATA['saiu20dia']=0;}
	//if ($DATA['saiu20hora']==''){$DATA['saiu20hora']=0;}
	//if ($DATA['saiu20minuto']==''){$DATA['saiu20minuto']=0;}
	if ($DATA['saiu20estado']==''){$DATA['saiu20estado']=0;}
	if ($DATA['saiu20estadoorigen']==''){$DATA['saiu20estadoorigen']=0;}
	//if ($DATA['saiu20idcorreo']==''){$DATA['saiu20idcorreo']=0;}
	//if ($DATA['saiu20tipointeresado']==''){$DATA['saiu20tipointeresado']=0;}
	if ($DATA['saiu20idpqrs']==''){$DATA['saiu20idpqrs']=0;}
	//if ($DATA['saiu20paramercadeo']==''){$DATA['saiu20paramercadeo']=0;}
	//if ($DATA['saiu20solucion']==''){$DATA['saiu20solucion']=0;}
	// -- Seccion para validar los posibles causales de error.
	$sSepara=', ';
	$bConCierre=false;
	$bEnviaEncuesta=false;
	$bEnviaCaso=false;
	if ($DATA['saiu20temasolicitud']==''){$sError=$ERR['saiu20temasolicitud'].$sSepara.$sError;}
	if ($DATA['saiu20tiposolicitud']==''){$sError=$ERR['saiu20tiposolicitud'].$sSepara.$sError;}
	// if ($DATA['saiu20clasesolicitud']==''){$sError=$ERR['saiu20clasesolicitud'].$sSepara.$sError;}
	if ($DATA['saiu20tipointeresado']==''){$sError=$ERR['saiu20tipointeresado'].$sSepara.$sError;}
	if ($DATA['saiu20idsolicitante']==0){$sError=$ERR['saiu20idsolicitante'].$sSepara.$sError;}
	if ($DATA['saiu20minuto']==''){$sError=$ERR['saiu20minuto'].$sSepara.$sError;}
	if ($DATA['saiu20hora']==''){$sError=$ERR['saiu20hora'].$sSepara.$sError;}
	if ($DATA['saiu20dia']==''){$sError=$ERR['saiu20dia'].$sSepara.$sError;}
	if ($DATA['saiu20mes']==''){$sError=$ERR['saiu20mes'].$sSepara.$sError;}
	if ($DATA['saiu20agno']==''){$sError=$ERR['saiu20agno'].$sSepara.$sError;}
	if ($DATA['saiu20idcentro']==''){$sError=$ERR['saiu20idcentro'].$sSepara.$sError;}
	if ($DATA['saiu20idzona']==''){$sError=$ERR['saiu20idzona'].$sSepara.$sError;}
	if ($DATA['saiu20detalle']==''){$sError=$ERR['saiu20detalle'].$sSepara.$sError;}
	if ($DATA['saiu20fecharespcaso']==''){$DATA['saiu20fecharespcaso']=0;}
	if ($DATA['saiu20estado']==1 || $DATA['saiu20estado']==7){
		if ($DATA['saiu20estado']==7) {
			$bConCierre=true;
		}
		if ($DATA['saiu20solucion']=='') {
			$sError=$ERR['saiu20solucion'].$sSepara.$sError;
			}else{
			if ((int)$DATA['saiu20solucion']==0){
				$sError=$ERR['saiu20solucion_proceso'].$sSepara.$sError;
				}
			}
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Estado: '.$DATA['saiu20estado']. ' - Solución: '. $DATA['saiu20solucion'] .'<br>';}
		if ($DATA['saiu20idresponsable']==0){$sError=$ERR['saiu20idresponsable'].$sSepara.$sError;}
		if ($DATA['saiu20paramercadeo']==''){$sError=$ERR['saiu20paramercadeo'].$sSepara.$sError;}
		//if ($DATA['saiu20minutofin']==''){$sError=$ERR['saiu20minutofin'].$sSepara.$sError;}
		//if ($DATA['saiu20horafin']==''){$sError=$ERR['saiu20horafin'].$sSepara.$sError;}
		if ($DATA['saiu20correoorigen']==''){$sError=$ERR['saiu20correoorigen'].$sSepara.$sError;}
		if ($DATA['saiu20idperiodo']==''){$sError=$ERR['saiu20idperiodo'].$sSepara.$sError;}
		if ($DATA['saiu20idprograma']==''){$sError=$ERR['saiu20idprograma'].$sSepara.$sError;}
		if ($DATA['saiu20idescuela']==''){$sError=$ERR['saiu20idescuela'].$sSepara.$sError;}
		if ($DATA['saiu20codciudad']==''){$sError=$ERR['saiu20codciudad'].$sSepara.$sError;}
		if ($DATA['saiu20coddepto']==''){$sError=$ERR['saiu20coddepto'].$sSepara.$sError;}
		if ($DATA['saiu20codpais']==''){$sError=$ERR['saiu20codpais'].$sSepara.$sError;}
		if ($DATA['saiu20idcorreo']==''){$sError=$ERR['saiu20idcorreo'].$sSepara.$sError;}
		if ($DATA['saiu20idcorreo']==3){
			if ($DATA['saiu20idcorreootro']==''){$sError=$ERR['saiu20idcorreo'].$sSepara.$sError;}
		} else {
			$DATA['saiu20idcorreootro']=='';
		}
		//if ($DATA['saiu20hora']==''){$DATA['saiu20hora']=fecha_hora();}
		if ($DATA['saiu20solucion']==1){ // Resuelto en la atención
			if ($DATA['saiu20respuesta']==''){$sError=$ERR['saiu20respuesta'].$sSepara.$sError;}
		}
		if ($DATA['saiu20solucion']==3) { // Se inicia caso
			if ($DATA['saiu20temasolicitud'] != $DATA['saiu20temasolicitudorigen']) {
				$DATA['saiu20idresponsablecaso']=0;
			}
			if ($DATA['saiu20idresponsablecaso']==0) {
				list($DATA['saiu20idunidadcaso'], $DATA['saiu20idequipocaso'], $DATA['saiu20idsupervisorcaso'], $DATA['saiu20idresponsablecaso'], $saiu20tiemprespdias, $saiu20tiempresphoras, $sErrorF, $iTipoError, $sDebugF) = f3020_ConsultaResponsable($DATA, $objDB, $bDebug);
				if ($sErrorF!=''){$sError=$sError.'<br>'.$sErrorF;}
				if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta Responsable: '.$sDebugF.'<br>';}
				if ($DATA['saiu20idunidadcaso']==0){$sError=$ERR['saiu20idunidadcaso'].$sSepara.$sError;}
				if ($DATA['saiu20idequipocaso']==0){$sError=$ERR['saiu20idequipocaso'].$sSepara.$sError;}
				if ($DATA['saiu20idsupervisorcaso']==0){$sError=$ERR['saiu20idsupervisorcaso'].$sSepara.$sError;}
				if ($DATA['saiu20idresponsablecaso']==0){$sError=$ERR['saiu20idresponsablecaso'].$sSepara.$sError;}
				if ($sError!='') {
					$DATA['saiu20idunidadcaso']=0;
					$DATA['saiu20idequipocaso']=0;
					$DATA['saiu20idsupervisorcaso']=0;
					$DATA['saiu20idresponsablecaso']=0;
				}
			}
		}
		if ($sError=='') {
			if ($DATA['saiu20solucion']==5) { // Se inicia PQRS
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
				$aParams['saiu05idsolicitante']=$DATA['saiu20idsolicitante'];
				$aParams['saiu05tipointeresado']=$DATA['saiu20tipointeresado'];
				$aParams['saiu05detalle']=$DATA['saiu20detalle'];
				$aParams['saiu05idtiposolorigen']=$DATA['saiu20tiposolicitud'];
				$aParams['saiu05idtemaorigen']=$DATA['saiu20temasolicitud'];
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
					$DATA['saiu20idpqrs']=$aParams['saiu05id'];
					$DATA['saiu20numref']=$aParams['saiu05numref'];
					if ($DATA['saiu20idpqrs']==-1){$sError=$ERR['saiu20idpqrs'].$sSepara.$sError;}
					if ($DATA['saiu20numref']==''){$sError=$ERR['saiu20numref'].$sSepara.$sError;}
				}
			}
		}
		if ($sError!=''){
			if ($DATA['saiu20estado']!=1) {	// Asignado
				$DATA['saiu20estado']=2;	// En tramite
			}
		}
		$sErrorCerrando=$sError;
		//Fin de las valiaciones NO LLAVE.
		}
	if ($sError==''){
		switch($DATA['saiu20estado']){
			case 1: //Caso Asignado
			break;
			case 7: //Logra cerrar			
			switch($DATA['saiu20solucion']){
				case 1: // Resuelto en la atención
				case 5: // Se inicia PQRS
				$DATA['saiu20fecharespcaso']=0;
				$DATA['saiu20fechafin']=fecha_DiaMod();
				$DATA['saiu20horafin']=fecha_hora();
				$DATA['saiu20minutofin']=fecha_minuto();
				$bEnviaEncuesta=true;
				break;
				case 3: // Se inicia caso
				if ($DATA['saiu20estadoorigen']==1) {
					if ($DATA['saiu20respuesta']==''){
						$sError=$ERR['saiu20respuesta'].$sSepara.$sError;
					} else {
						$DATA['saiu20fecharespcaso']=fecha_DiaMod();
						$DATA['saiu20horarespcaso']=fecha_hora();
						$DATA['saiu20minrespcaso']=fecha_minuto();
						$bEnviaEncuesta=true;
					}
				} else {
					$DATA['saiu20fecharespcaso']=0;
					$DATA['saiu20fechafin']=fecha_DiaMod();
					$DATA['saiu20horafin']=fecha_hora();
					$DATA['saiu20minutofin']=fecha_minuto();
					$iDiaIni=($DATA['saiu20agno']*10000)+($DATA['saiu20mes']*100)+$DATA['saiu20dia'];
					list($DATA['saiu20tiemprespdias'], $DATA['saiu20tiempresphoras'], $DATA['saiu20tiemprespminutos'])=Tiempo_MinutosCalendario($iDiaIni, $DATA['saiu20hora'], $DATA['saiu20minuto'], $DATA['saiu20fechafin'], $DATA['saiu20horafin'], $DATA['saiu20minutofin']);
					$DATA['saiu20idcaso']=(int)(fecha_DiaMod().$DATA['saiu20id'].'');
					$DATA['saiu20respuesta']='';
					$DATA['saiu20estado']=1;
					$bEnviaCaso=true;
				}
				break;
				case 8: // Sin resolver
				$DATA['saiu20respuesta']='';
				break;
				default:
				$sError=$ERR['saiu20solucion'].$sSepara.$sError;
				break;
			}
			break;
			case 8: //Solicitud abandonada
			case 9: //Cancelada por el usuario
			if (trim($DATA['saiu20fechafin'])==''){$DATA['saiu20fechafin']=fecha_DiaMod();}
			if (trim($DATA['saiu20minutofin'])==''){$DATA['saiu20minutofin']=fecha_minuto();}
			if (trim($DATA['saiu20horafin'])==''){$DATA['saiu20horafin']=fecha_hora();}
			//$DATA['saiu20minutofin']=fecha_minuto();
			//$DATA['saiu20horafin']=fecha_hora();
			break; 
			default:
			$DATA['saiu20estado']=2;
			if ($DATA['saiu20hora']==''){$DATA['saiu20hora']=fecha_hora();}
			if ($DATA['saiu20minuto']==''){$DATA['saiu20minuto']=fecha_minuto();}
			if ($DATA['saiu20fechafin']==''){$DATA['saiu20fechafin']=0;}
			if ($DATA['saiu20horafin']==''){$DATA['saiu20horafin']=0;}
			if ($DATA['saiu20minutofin']==''){$DATA['saiu20minutofin']=0;}
			break;
		}
		if ($DATA['saiu20clasesolicitud']==''){$DATA['saiu20clasesolicitud']=0;}
		if ($DATA['saiu20tiposolicitud']==''){$DATA['saiu20tiposolicitud']=0;}
		if ($DATA['saiu20temasolicitud']==''){$DATA['saiu20temasolicitud']=0;}
		if ($DATA['saiu20idzona']==''){$DATA['saiu20idzona']=0;}
		if ($DATA['saiu20idcentro']==''){$DATA['saiu20idcentro']=0;}
		if ($DATA['saiu20idescuela']==''){$DATA['saiu20idescuela']=0;}
		if ($DATA['saiu20idprograma']==''){$DATA['saiu20idprograma']=0;}
		if ($DATA['saiu20idperiodo']==''){$DATA['saiu20idperiodo']=0;}
	}
	//Valiaciones de campos obligatorios en todo guardar.
	if ($DATA['saiu20tiporadicado']==''){$sError=$ERR['saiu20tiporadicado'];}
	if ($DATA['saiu20mes']==''){$sError=$ERR['saiu20mes'];}
	if ($DATA['saiu20agno']==''){$sError=$ERR['saiu20agno'];}
	// -- Tiene un cerrado.
	$iDiaIni=fecha_ArmarNumero($DATA['saiu20dia'],$DATA['saiu20mes'],$DATA['saiu20agno']);
	if ($bConCierre && $sError==''){
		//Validaciones previas a cerrar
		if ($DATA['saiu20estado']==7){
			switch($DATA['saiu20solucion']){
				case 1: // Resuelto en la atención
				case 5: // Se inicia PQRS
				list($DATA['saiu20tiemprespdias'], $DATA['saiu20tiempresphoras'], $DATA['saiu20tiemprespminutos'])=Tiempo_MinutosCalendario($iDiaIni, $DATA['saiu20hora'], $DATA['saiu20minuto'], $DATA['saiu20fechafin'], $DATA['saiu20horafin'], $DATA['saiu20minutofin']);
				break;
			}
		}
		//Aprobó las Validaciones al cerrar
		if ($sError.$sErrorCerrando!=''){
			if ($DATA['saiu20estado']==7) {
				$DATA['saiu20estado'] = $DATA['saiu20estadoorigen'];
			} else if ($DATA['saiu20estado']!=1) {
				$DATA['saiu20estado']=2;
			}
			$sErrorCerrando=$sError.' '.$sErrorCerrando;
		}else{
			$bCerrando=true;
			//Acciones del cierre
		}
	}
	// -- Fin del cerrado.
	// -- Se verifican los valores de campos de otras tablas.
	$sTabla20='saiu20correo_'.$DATA['saiu20agno'];
	if ($DATA['saiu20idresponsable_doc']!=''){
		if ($sError==''){$sError=tabla_terceros_existe($DATA['saiu20idresponsable_td'], $DATA['saiu20idresponsable_doc'], $objDB, 'El tercero Responsable ');}
		if ($sError==''){
			list($sError, $sInfo)=tercero_Bloqueado($DATA['saiu20idresponsable'], $objDB);
			if ($sInfo!=''){$sError=$sError.'<br>'.$sInfo;}
			}
		}
	if ($DATA['saiu20idsolicitante_doc']!=''){
		if ($sError==''){$sError=tabla_terceros_existe($DATA['saiu20idsolicitante_td'], $DATA['saiu20idsolicitante_doc'], $objDB, 'El tercero Solicitante ');}
		if ($sError==''){
			list($sError, $sInfo)=tercero_Bloqueado($DATA['saiu20idsolicitante'], $objDB);
			if ($sInfo!=''){$sError=$sError.'<br>'.$sInfo;}
			}
		}
	if ($sError == '') {
		list($sErrorR, $sDebugR) = f3020_RevTabla_saiu20correo($DATA['saiu20agno'], $objDB);
		$sError = $sError . $sErrorR;
	}
	$bQuitarCodigo=false;
	$sCampoCodigo='';
	if ($sError==''){
		if ($DATA['paso']==10){
			if ($DATA['saiu20consec']==''){
				$DATA['saiu20consec']=tabla_consecutivo($sTabla20, 'saiu20consec', 'saiu20agno='.$DATA['saiu20agno'].' AND saiu20mes='.$DATA['saiu20mes'].' AND saiu20tiporadicado='.$DATA['saiu20tiporadicado'].'', $objDB);
				if ($DATA['saiu20consec']==-1){$sError=$objDB->serror;}
				$bQuitarCodigo=true;
				$sCampoCodigo='saiu20consec';
				}else{
				if (!seg_revisa_permiso($iCodModulo, 8, $objDB)){
					$sError=$ERR['8'];
					$DATA['saiu20consec']='';
					}
				}
			if ($sError==''){
				$sSQL='SELECT 1 FROM '.$sTabla20.' WHERE saiu20agno='.$DATA['saiu20agno'].' AND saiu20mes='.$DATA['saiu20mes'].' AND saiu20tiporadicado='.$DATA['saiu20tiporadicado'].' AND saiu20consec='.$DATA['saiu20consec'].'';
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
			$DATA['saiu20id']=tabla_consecutivo($sTabla20,'saiu20id', '', $objDB);
			if ($DATA['saiu20id']==-1){$sError=$objDB->serror;}
			}
		}
	if ($sError==''){
		//Encontrar la clase
		$sSQL='SELECT saiu02clasesol FROM saiu02tiposol WHERE saiu02id='.$DATA['saiu20tiposolicitud'].'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$fila=$objDB->sf($tabla);
			$DATA['saiu20clasesolicitud']=$fila['saiu02clasesol'];
			}
		}
	$idSolicitantePrevio=0;
	if ($sError==''){
		$DATA['saiu20detalle']=stripslashes($DATA['saiu20detalle']);
		//Si el campo saiu20detalle permite html quite la linea htmlspecialchars para el campo y habilite la siguiente linea:
		//$saiu20detalle=addslashes($DATA['saiu20detalle']);
		$saiu20detalle=str_replace('"', '\"', $DATA['saiu20detalle']);
		$DATA['saiu20respuesta']=stripslashes($DATA['saiu20respuesta']);
		//$saiu20respuesta=addslashes($DATA['saiu20respuesta']);
		$saiu20respuesta=str_replace('"', '\"', $DATA['saiu20respuesta']);
		$bpasa=false;
		if ($DATA['paso']==10){
			$DATA['saiu20idpqrs']=0;
			$DATA['saiu20tiemprespdias']=0;
			$DATA['saiu20tiempresphoras']=0;
			$DATA['saiu20tiemprespminutos']=0;
			$DATA['saiu20agno']=fecha_agno();
			$DATA['saiu20mes']=fecha_mes();
			$DATA['saiu20dia']=fecha_dia();
			$DATA['saiu20hora']=fecha_hora();
			$DATA['saiu20minuto']=fecha_minuto();
			$DATA['saiu20idcaso']=0;
			$sCampos3020='saiu20agno, saiu20mes, saiu20tiporadicado, saiu20consec, saiu20id, 
			saiu20dia, saiu20hora, saiu20minuto, saiu20estado, saiu20idcorreo, saiu20idcorreootro, saiu20idsolicitante, 
			saiu20tipointeresado, saiu20clasesolicitud, saiu20tiposolicitud, saiu20temasolicitud, saiu20idzona, 
			saiu20idcentro, saiu20codpais, saiu20coddepto, saiu20codciudad, saiu20idescuela, 
			saiu20idprograma, saiu20idperiodo, saiu20correoorigen, saiu20idpqrs, saiu20detalle, saiu20fechafin,
			saiu20horafin, saiu20minutofin, saiu20paramercadeo, saiu20idresponsable, saiu20tiemprespdias, 
			saiu20tiempresphoras, saiu20tiemprespminutos, saiu20solucion, saiu20idcaso, saiu20respuesta';
			$sValores3020=''.$DATA['saiu20agno'].', '.$DATA['saiu20mes'].', '.$DATA['saiu20tiporadicado'].', '.$DATA['saiu20consec'].', '.$DATA['saiu20id'].', 
			'.$DATA['saiu20dia'].', '.$DATA['saiu20hora'].', '.$DATA['saiu20minuto'].', '.$DATA['saiu20estado'].', '.$DATA['saiu20idcorreo'].', "'.$DATA['saiu20idcorreootro'].'", '.$DATA['saiu20idsolicitante'].', 
			'.$DATA['saiu20tipointeresado'].', '.$DATA['saiu20clasesolicitud'].', '.$DATA['saiu20tiposolicitud'].', '.$DATA['saiu20temasolicitud'].', '.$DATA['saiu20idzona'].', 
			'.$DATA['saiu20idcentro'].', "'.$DATA['saiu20codpais'].'", "'.$DATA['saiu20coddepto'].'", "'.$DATA['saiu20codciudad'].'", '.$DATA['saiu20idescuela'].', 
			'.$DATA['saiu20idprograma'].', '.$DATA['saiu20idperiodo'].', "'.$DATA['saiu20correoorigen'].'", '.$DATA['saiu20idpqrs'].', "'.$saiu20detalle.'", '.$DATA['saiu20fechafin'].', 
			'.$DATA['saiu20horafin'].', '.$DATA['saiu20minutofin'].', '.$DATA['saiu20paramercadeo'].', '.$DATA['saiu20idresponsable'].', '.$DATA['saiu20tiemprespdias'].', 
			'.$DATA['saiu20tiempresphoras'].', '.$DATA['saiu20tiemprespminutos'].', '.$DATA['saiu20solucion'].', '.$DATA['saiu20idcaso'].', "'.$saiu20respuesta.'"';
			if ($APP->utf8==1){
				$sSQL='INSERT INTO '.$sTabla20.' ('.$sCampos3020.') VALUES ('.cadena_codificar($sValores3020).');';
				$sdetalle=$sCampos3020.'['.cadena_codificar($sValores3020).']';
				}else{
				$sSQL='INSERT INTO '.$sTabla20.' ('.$sCampos3020.') VALUES ('.$sValores3020.');';
				$sdetalle=$sCampos3020.'['.$sValores3020.']';
				}
			$idAccion=2;
			$bpasa=true;
			}else{
			$scampo[1]='saiu20dia';
			$scampo[2]='saiu20hora';
			$scampo[3]='saiu20minuto';
			$scampo[4]='saiu20idcorreo';
			$scampo[5]='saiu20idcorreootro';
			$scampo[6]='saiu20idsolicitante';
			$scampo[7]='saiu20tipointeresado';
			$scampo[8]='saiu20clasesolicitud';
			$scampo[9]='saiu20temasolicitud';
			$scampo[10]='saiu20idzona';
			$scampo[11]='saiu20idcentro';
			$scampo[12]='saiu20codpais';
			$scampo[13]='saiu20coddepto';
			$scampo[14]='saiu20codciudad';
			$scampo[15]='saiu20idescuela';
			$scampo[16]='saiu20idprograma';
			$scampo[17]='saiu20idperiodo';
			$scampo[18]='saiu20correoorigen';
			$scampo[19]='saiu20detalle';
			$scampo[20]='saiu20horafin';
			$scampo[21]='saiu20minutofin';
			$scampo[22]='saiu20paramercadeo';
			$scampo[23]='saiu20idresponsable';
			$scampo[24]='saiu20solucion';
			$scampo[25]='saiu20tiposolicitud';
			$scampo[26]='saiu20estado';
			$scampo[27]='saiu20tiemprespdias';
			$scampo[28]='saiu20tiempresphoras';
			$scampo[29]='saiu20tiemprespminutos';
			$scampo[30]='saiu20respuesta';
			$scampo[31]='saiu20idcaso';
			$scampo[32]='saiu20fecharespcaso';
			$scampo[33]='saiu20horarespcaso';
			$scampo[34]='saiu20minrespcaso';
			$scampo[35]='saiu20idunidadcaso';
			$scampo[36]='saiu20idequipocaso';
			$scampo[37]='saiu20idsupervisorcaso';
			$scampo[38]='saiu20idresponsablecaso';
			$scampo[39]='saiu20idpqrs';
			$scampo[40]='saiu20numref';
			$scampo[41]='saiu20fechafin';
			$sdato[1]=$DATA['saiu20dia'];
			$sdato[2]=$DATA['saiu20hora'];
			$sdato[3]=$DATA['saiu20minuto'];
			$sdato[4]=$DATA['saiu20idcorreo'];
			$sdato[5]=$DATA['saiu20idcorreootro'];
			$sdato[6]=$DATA['saiu20idsolicitante'];
			$sdato[7]=$DATA['saiu20tipointeresado'];
			$sdato[8]=$DATA['saiu20clasesolicitud'];
			$sdato[9]=$DATA['saiu20temasolicitud'];
			$sdato[10]=$DATA['saiu20idzona'];
			$sdato[11]=$DATA['saiu20idcentro'];
			$sdato[12]=$DATA['saiu20codpais'];
			$sdato[13]=$DATA['saiu20coddepto'];
			$sdato[14]=$DATA['saiu20codciudad'];
			$sdato[15]=$DATA['saiu20idescuela'];
			$sdato[16]=$DATA['saiu20idprograma'];
			$sdato[17]=$DATA['saiu20idperiodo'];
			$sdato[18]=$DATA['saiu20correoorigen'];
			$sdato[19]=$saiu20detalle;
			$sdato[20]=$DATA['saiu20horafin'];
			$sdato[21]=$DATA['saiu20minutofin'];
			$sdato[22]=$DATA['saiu20paramercadeo'];
			$sdato[23]=$DATA['saiu20idresponsable'];
			$sdato[24]=$DATA['saiu20solucion'];
			$sdato[25]=$DATA['saiu20tiposolicitud'];
			$sdato[26]=$DATA['saiu20estado'];
			$sdato[27]=$DATA['saiu20tiemprespdias'];
			$sdato[28]=$DATA['saiu20tiempresphoras'];
			$sdato[29]=$DATA['saiu20tiemprespminutos'];
			$sdato[30]=$saiu20respuesta;
			$sdato[31]=$DATA['saiu20idcaso'];
			$sdato[32]=$DATA['saiu20fecharespcaso'];
			$sdato[33]=$DATA['saiu20horarespcaso'];
			$sdato[34]=$DATA['saiu20minrespcaso'];
			$sdato[35]=$DATA['saiu20idunidadcaso'];
			$sdato[36]=$DATA['saiu20idequipocaso'];
			$sdato[37]=$DATA['saiu20idsupervisorcaso'];
			$sdato[38]=$DATA['saiu20idresponsablecaso'];
			$sdato[39]=$DATA['saiu20idpqrs'];
			$sdato[40]=$DATA['saiu20numref'];
			$sdato[41]=$DATA['saiu20fechafin'];
			$numcmod=41;
			$sWhere='saiu20id='.$DATA['saiu20id'].'';
			$sSQL='SELECT * FROM '.$sTabla20.' WHERE '.$sWhere;
			$sdatos='';
			$bPrimera=true;
			$result=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($result)>0){
				$filabase=$objDB->sf($result);
				if ($DATA['saiu20idsolicitante']!=$filabase['saiu20idsolicitante']){
					$idSolicitantePrevio=$filabase['saiu20idsolicitante'];
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
					$sSQL='UPDATE '.$sTabla20.' SET '.cadena_codificar($sdatos).' WHERE '.$sWhere.';';
					}else{
					$sdetalle=$sdatos.'['.$sWhere.']';
					$sSQL='UPDATE '.$sTabla20.' SET '.$sdatos.' WHERE '.$sWhere.';';
					}
				$idAccion=3;
				}
			}
		if ($bpasa){
			if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Guardar 3020 '.$sSQL.'<br>';}
			$result=$objDB->ejecutasql($sSQL);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' [3020] ..<!-- '.$sSQL.' -->';
				if ($idAccion==2){
					$DATA['saiu20id']='';
					$bQuitarCodigo=true;
					$DATA['paso']=0;
					}else{
					$DATA['paso']=2;
					}
				$bCerrando=false;
				}else{
				if ($bAudita[$idAccion]){seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], $idAccion, $DATA['saiu20id'], $sdetalle, $objDB);}
				$DATA['paso']=2;
				//Registrar en el inventario.
				$valores3000[2]=$iCodModulo;
				$valores3000[3]=$DATA['saiu20agno'];
				$valores3000[4]=$DATA['saiu20id'];
				if ($idSolicitantePrevio!=0){
					//Retirar al anterior.
					$valores3000[1]=$idSolicitantePrevio;
					f3000_Retirar($valores3000, $objDB, $bDebug);
					}
				if ($DATA['saiu20idsolicitante']!=0){
					$valores3000[1]=$DATA['saiu20idsolicitante'];
					$valores3000[5]=$iDiaIni;
					$valores3000[6]=$DATA['saiu20tiposolicitud'];
					$valores3000[7]=$DATA['saiu20temasolicitud'];
					$valores3000[8]=$DATA['saiu20estado'];
					f3000_Registrar($valores3000, $objDB, $bDebug);
					}
				if ($bEnviaCaso) {
					list($sMensaje, $sErrorE, $sDebugE) = f3000_EnviaCorreosAtencion($DATA, $DATA['saiu20agno'], $objDB, $bDebug, true);
					$sError = $sError . $sErrorE;
					$sDebug = $sDebug . $sDebugE;
					}
				if ($bEnviaEncuesta) {
					list($sMensaje, $sErrorE, $sDebugE) = f3000_EnviaCorreosAtencion($DATA, $DATA['saiu20agno'], $objDB, $bDebug);
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
		list($sErrorCerrando, $sDebugCerrar)=f3020_Cerrar($DATA['saiu20id'], $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugCerrar;
		}
	//if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' InfoDepura<br>';}
	return array($DATA, $sError, $iTipoError, $bCerrando, $sErrorCerrando, $sDebug);
	}
function f3020_db_Eliminar($saiu20agno, $saiu20id, $objDB, $bDebug=false){
	$iCodModulo=3020;
	$bAudita[4]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3020='lg/lg_3020_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3020)){$mensajes_3020='lg/lg_3020_es.php';}
	require $mensajes_todas;
	require $mensajes_3020;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	$saiu20id=numeros_validar($saiu20id);
	// Traer los datos para hacer las validaciones.
	$sTabla20='saiu20correo_'.$saiu20agno;
	if ($sError==''){
		$sSQL='SELECT * FROM '.$sTabla20.' WHERE saiu20id='.$saiu20id.'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$filabase=$objDB->sf($tabla);
			}else{
			$sError='No se encuentra el registro solicitado {Ref: '.$saiu20id.'}';
			}
		}
	if ($sError==''){
		if (!seg_revisa_permiso($iCodModulo, 4, $objDB)){
			$sError=$ERR['4'];
			}
		}
	if ($sError==''){
		$sSQL='SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=3020';
		$tablaor=$objDB->ejecutasql($sSQL);
		while ($filaor=$objDB->sf($tablaor)){
			$sSQL='SELECT '.$filaor['campoid'].' FROM '.$filaor['tabla'].' WHERE '.$filaor['camporev'].'='.$_REQUEST['saiu20id'].' LIMIT 0, 1';
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
		if ($filabase['saiu20idsolicitante']!=0){
			//Retirar al anterior.
			$valores3000[1]=$filabase['saiu20idsolicitante'];
			$valores3000[2]=$iCodModulo;
			$valores3000[3]=$filabase['saiu20agno'];
			$valores3000[4]=$filabase['saiu20id'];
			f3000_Retirar($valores3000, $objDB, $bDebug);
			}
		$sWhere='saiu20id='.$saiu20id.'';
		//$sWhere='saiu20consec='.$filabase['saiu20consec'].' AND saiu20tiporadicado='.$filabase['saiu20tiporadicado'].' AND saiu20mes='.$filabase['saiu20mes'].' AND saiu20agno='.$filabase['saiu20agno'].'';
		$sSQL='DELETE FROM '.$sTabla20.' WHERE '.$sWhere.';';
		$result=$objDB->ejecutasql($sSQL);
		if ($result==false){
			$sError=$ERR['falla_eliminar'].' .. <!-- '.$sSQL.' -->';
			}else{
			if ($bAudita[4]){seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $saiu20id, $sWhere, $objDB);}
			}
		}
	return array($sError, $iTipoError, $sDebug);
	}
function f3020_TituloBusqueda(){
	return 'B&uacute;squeda de Atenciones por Correo';
	}
function f3020_ParametrosBusqueda(){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3020='lg/lg_3020_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3020)){$mensajes_3020='lg/lg_3020_es.php';}
	require $mensajes_todas;
	require $mensajes_3020;
	$sParams='<label class="Label90">
	'.$ETI['msg_bnombre'].'
	</label>
	<label>
	<input id="b3020nombre" name="b3020nombre" type="text" value="" onchange="paginarbusqueda()" />
	</label>';
	return $sParams;
	}
function f3020_JavaScriptBusqueda($iModuloBusca){
	$sRes='var sCampo=window.document.frmedita.scampobusca.value;
	var params=new Array();
	params[100]=sCampo;
	params[101]=window.document.frmedita.paginabusqueda.value;
	params[102]=window.document.frmedita.lppfbusqueda.value;
	params[103]=window.document.frmedita.b3020nombre.value;
	xajax_f'.$iModuloBusca.'_HtmlBusqueda(params);';
	return $sRes;
	}
function f3020_TablaDetalleBusquedas($aParametros, $objDB){
	$res='';
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3020='lg/lg_3020_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3020)){$mensajes_3020='lg/lg_3020_es.php';}
	require $mensajes_todas;
	require $mensajes_3020;
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
		return array($sLeyenda.'<input id="paginaf3020" name="paginaf3020" type="hidden" value="'.$pagina.'"/><input id="lppf3020" name="lppf3020" type="hidden" value="'.$lineastabla.'"/>', $sDebug);
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
	$sTitulos='Agno, Mes, Tiporadicado, Consec, Id, Dia, Hora, Minuto, Estado, Correo, Solicitante, Tipointeresado, Clasesolicitud, Tiposolicitud, Temasolicitud, Zona, Centro, Codpais, Coddepto, Codciudad, Escuela, Programa, Periodo, Numorigen, Pqrs, Detalle, Horafin, Minutofin, Paramercadeo, Responsable, Tiemprespdias, Tiempresphoras, Tiemprespminutos, Solucion, Caso';
	$sSQL='SELECT TB.saiu20agno, TB.saiu20mes, TB.saiu20tiporadicado, TB.saiu20consec, TB.saiu20id, TB.saiu20dia, TB.saiu20hora, TB.saiu20minuto, T9.saiu11nombre, T10.saiu57titulo, T11.unad11razonsocial AS C11_nombre, T12.bita07nombre, T13.saiu01titulo, T14.saiu02titulo, T15.saiu03titulo, T16.unad23nombre, T17.unad24nombre, T18.unad18nombre, T19.unad19nombre, T20.unad20nombre, T21.core12nombre, T22.core09nombre, T23.exte02nombre, TB.saiu20correoorigen, TB.saiu20idpqrs, TB.saiu20detalle, TB.saiu20horafin, TB.saiu20minutofin, TB.saiu20paramercadeo, T30.unad11razonsocial AS C30_nombre, TB.saiu20tiemprespdias, TB.saiu20tiempresphoras, TB.saiu20tiemprespminutos, TB.saiu20solucion, TB.saiu20idcaso, TB.saiu20estado, TB.saiu20idcorreo, TB.saiu20idsolicitante, T11.unad11tipodoc AS C11_td, T11.unad11doc AS C11_doc, TB.saiu20tipointeresado, TB.saiu20clasesolicitud, TB.saiu20tiposolicitud, TB.saiu20temasolicitud, TB.saiu20idzona, TB.saiu20idcentro, TB.saiu20codpais, TB.saiu20coddepto, TB.saiu20codciudad, TB.saiu20idescuela, TB.saiu20idprograma, TB.saiu20idperiodo, TB.saiu20idresponsable, T30.unad11tipodoc AS C30_td, T30.unad11doc AS C30_doc 
FROM saiu20correo AS TB, saiu11estadosol AS T9, saiu57correos AS T10, unad11terceros AS T11, bita07tiposolicitante AS T12, saiu01claseser AS T13, saiu02tiposol AS T14, saiu03temasol AS T15, unad23zona AS T16, unad24sede AS T17, unad18pais AS T18, unad19depto AS T19, unad20ciudad AS T20, core12escuela AS T21, core09programa AS T22, exte02per_aca AS T23, unad11terceros AS T30 
WHERE '.$sSQLadd1.' TB.saiu20estado=T9.saiu11id AND TB.saiu20idcorreo=T10.saiu57id AND TB.saiu20idsolicitante=T11.unad11id AND TB.saiu20tipointeresado=T12.bita07id AND TB.saiu20clasesolicitud=T13.saiu01id AND TB.saiu20tiposolicitud=T14.saiu02id AND TB.saiu20temasolicitud=T15.saiu03id AND TB.saiu20idzona=T16.unad23id AND TB.saiu20idcentro=T17.unad24id AND TB.saiu20codpais=T18.unad18codigo AND TB.saiu20coddepto=T19.unad19codigo AND TB.saiu20codciudad=T20.unad20codigo AND TB.saiu20idescuela=T21.core12id AND TB.saiu20idprograma=T22.core09id AND TB.saiu20idperiodo=T23.exte02id AND TB.saiu20idresponsable=T30.unad11id '.$sSQLadd.'
ORDER BY TB.saiu20agno, TB.saiu20mes, TB.saiu20tiporadicado, TB.saiu20consec';
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
			//return array(cadena_codificar($sErrConsulta.'<input id="paginaf3020" name="paginaf3020" type="hidden" value="'.$pagina.'"/><input id="lppf3020" name="lppf3020" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
<td><b>'.$ETI['saiu20agno'].'</b></td>
<td><b>'.$ETI['saiu20mes'].'</b></td>
<td><b>'.$ETI['saiu20tiporadicado'].'</b></td>
<td><b>'.$ETI['saiu20consec'].'</b></td>
<td><b>'.$ETI['saiu20dia'].'</b></td>
<td><b>'.$ETI['saiu20hora'].'</b></td>
<td><b>'.$ETI['saiu20estado'].'</b></td>
<td><b>'.$ETI['saiu20idcorreo'].'</b></td>
<td colspan="2"><b>'.$ETI['saiu20idsolicitante'].'</b></td>
<td><b>'.$ETI['saiu20tipointeresado'].'</b></td>
<td><b>'.$ETI['saiu20clasesolicitud'].'</b></td>
<td><b>'.$ETI['saiu20tiposolicitud'].'</b></td>
<td><b>'.$ETI['saiu20temasolicitud'].'</b></td>
<td><b>'.$ETI['saiu20idzona'].'</b></td>
<td><b>'.$ETI['saiu20idcentro'].'</b></td>
<td><b>'.$ETI['saiu20codpais'].'</b></td>
<td><b>'.$ETI['saiu20coddepto'].'</b></td>
<td><b>'.$ETI['saiu20codciudad'].'</b></td>
<td><b>'.$ETI['saiu20idescuela'].'</b></td>
<td><b>'.$ETI['saiu20idprograma'].'</b></td>
<td><b>'.$ETI['saiu20idperiodo'].'</b></td>
<td><b>'.$ETI['saiu20correoorigen'].'</b></td>
<td><b>'.$ETI['saiu20idpqrs'].'</b></td>
<td><b>'.$ETI['saiu20detalle'].'</b></td>
<td><b>'.$ETI['saiu20horafin'].'</b></td>
<td><b>'.$ETI['saiu20paramercadeo'].'</b></td>
<td colspan="2"><b>'.$ETI['saiu20idresponsable'].'</b></td>
<td><b>'.$ETI['saiu20tiemprespdias'].'</b></td>
<td><b>'.$ETI['saiu20tiempresphoras'].'</b></td>
<td><b>'.$ETI['saiu20tiemprespminutos'].'</b></td>
<td><b>'.$ETI['saiu20solucion'].'</b></td>
<td><b>'.$ETI['saiu20idcaso'].'</b></td>
<td align="right">
'.html_paginador('paginabusqueda', $registros, $lineastabla, $pagina, 'paginarbusqueda()').'
'.html_lpp('lppfbusqueda', $lineastabla, 'paginarbusqueda()').'
</td>
</tr></thead>';
	$tlinea=1;
	while($filadet=$objDB->sf($tabladetalle)){
		$sPrefijo='<a href="javascript:Devuelve(\''.$filadet['saiu20id'].'\');">';
		$sSufijo='</a>';
		$tlinea++;
		$et_saiu20hora=html_TablaHoraMin($filadet['saiu20hora'], $filadet['saiu20minuto']);
		$et_saiu20horafin=html_TablaHoraMin($filadet['saiu20horafin'], $filadet['saiu20minutofin']);
		$res=$res.'<tr onmouseover="cambia_color_over(this);" onmouseout="cambia_color_out(this);">
<td>'.$sPrefijo.$filadet['saiu20agno'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu20mes'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['saiu16nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu20consec'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu20dia'].$sSufijo.'</td>
<td>'.$sPrefijo.$et_saiu20hora.$sSufijo.'</td>
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
<td>'.$sPrefijo.$filadet['saiu20codpais'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu20coddepto'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu20codciudad'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['core12nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['core09nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['exte02nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu20idpqrs'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu20detalle'].$sSufijo.'</td>
<td>'.$sPrefijo.$et_saiu20horafin.$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu20paramercadeo'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['C31_td'].' '.$filadet['C31_doc'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['C31_nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu20tiemprespdias'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu20tiempresphoras'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu20tiemprespminutos'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu20solucion'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu20idcaso'].$sSufijo.'</td>
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
function f3020_RevTabla_saiu20correo($sContenedor, $objDB, $bDebug = false)
{
	list($sError, $sDebug) = f3020_RevisarTabla($sContenedor, $objDB, $bDebug);
	return array($sError, $sDebug);
}
function f3020_ConsultaResponsable($DATA, $objDB, $bDebug = false)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_3020 = 'lg/lg_3020_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_3020)) {
		$mensajes_3020 = 'lg/lg_3020_es.php';
	}
	require $mensajes_todas;
	require $mensajes_3020;
	$sError = '';
	$iTipoError = 0;
	$sDebug = '';
	$saiu20idunidadcaso = 0;
	$saiu20idequipocaso = 0;
	$saiu20idsupervisorcaso = 0;
	$saiu20idresponsablecaso = 0;
	$saiu20tiemprespdias = 0;
	$saiu20tiempresphoras = 0;
	if (isset($DATA['saiu20temasolicitud']) == 0) {
		$DATA['saiu20temasolicitud'] = '';
	}
	if ($DATA['saiu20temasolicitud'] == '') {
		$sError = $ERR['saiu20temasolicitud'] . $sSepara . $sError;
	}
	if ($sError == '') {
		$sSQL = 'SELECT saiu03idunidadresp1, saiu03idequiporesp1, saiu03idliderrespon1, saiu03tiemprespdias1, saiu03tiempresphoras1
		FROM saiu03temasol
		WHERE saiu03id = ' . $DATA['saiu20temasolicitud'] . '';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' Consulta responsable solicitud ' . $sSQL . '<br>';
		}
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			$saiu20idunidadcaso = $fila['saiu03idunidadresp1'];
			$saiu20idequipocaso = $fila['saiu03idequiporesp1'];
			$saiu20idsupervisorcaso = $fila['saiu03idliderrespon1'];
			$saiu20idresponsablecaso = $saiu20idsupervisorcaso;
			$saiu20tiemprespdias = $fila['saiu03tiemprespdias1'];
			$saiu20tiempresphoras = $fila['saiu03tiempresphoras1'];
		} else {
			$sError = $sError . 'No se ha configurado el tema de solicitud.';
		}
	}
	return array($saiu20idunidadcaso, $saiu20idequipocaso, $saiu20idsupervisorcaso, $saiu20idresponsablecaso, $saiu20tiemprespdias, $saiu20tiempresphoras, $sError, $iTipoError, $sDebug);
}
function f3020_ActualizarAtiende($DATA, $objDB, $bDebug = false, $idTercero)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_3020 = 'lg/lg_3020_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_3020)) {
		$mensajes_3020 = 'lg/lg_3020_es.php';
	}
	require $mensajes_todas;
	require $mensajes_3020;
	$sTabla20 = 'saiu20correo_' . $DATA['saiu20agno'];
	$sResultado = '';
	$sError = '';
	$sDebug = '';
	$iTipoError = 0;
	if (!$objDB->bexistetabla($sTabla20)) {
		$sError = $sError . 'No ha sido posible acceder al contenedor de datos';
	}
	if ($sError == '') {
		$sSQL = 'SELECT saiu20agno, saiu20mes, saiu20estado, saiu20temasolicitud FROM ' . $sTabla20 . ' WHERE saiu20id=' . $DATA['saiu20id'] . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			list($DATA['saiu20idunidadcaso'], $DATA['saiu20idequipocaso'], $DATA['saiu20idsupervisorcaso'], $DATA['saiu20idresponsablecaso'], $saiu20tiemprespdias, $saiu20tiempresphoras, $sErrorF, $iTipoError, $sDebugF) = f3020_ConsultaResponsable($fila, $objDB, $bDebug);
			$sError = $sError . $sErrorF;
			if ($bDebug) {
				$sDebug = $sDebug . $sDebugF;
			}
		} else {
			$sError = $sError . $ETI['saiu20noexiste'];
		}
	}
	if ($sError == '') {
		if ($saiu20tiemprespdias > 0) { // Cálculo fecha probable de respuesta
			$iFechaBase = fecha_DiaMod();
			$saiu20fecharespprob = fecha_NumSumarDias($iFechaBase, $saiu20tiemprespdias);
		}
		list($DATA, $sErrorE, $iTipoError, $sDebugGuardar) = f3020_db_GuardarV2($DATA, $objDB, $bDebug, $idTercero);
		$sError = $sError . $sErrorE;
		$sDebug = $sDebug . $sDebugGuardar;
		if ($sError == '') {
			$sError = '<b>' . $ETI['msg_itemguardado'] . '</b>';
			$iTipoError = 1;
		}
	}
	return array($DATA, $sError, $iTipoError, $sDebug);
}
function elimina_archivo_saiu20idarchivo($idpadre, $iAgno)
{
	$_SESSION['u_ultimominuto'] = iminutoavance();
	require './app.php';
	$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto != '') {
		$objDB->dbPuerto = $APP->dbpuerto;
	}
	$objDB->xajax();
	$sTabla20 = 'saiu20correo_' . $iAgno;
	archivo_eliminar($sTabla20, 'saiu20id', 'saiu20idorigen', 'saiu20idarchivo', $idpadre, $objDB);
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->call("limpia_saiu20idarchivo");
	return $objResponse;
}
function elimina_archivo_saiu20idarchivorta($idpadre, $iAgno)
{
	$_SESSION['u_ultimominuto'] = iminutoavance();
	require './app.php';
	$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto != '') {
		$objDB->dbPuerto = $APP->dbpuerto;
	}
	$objDB->xajax();
	$sTabla20 = 'saiu20correo_' . $iAgno;
	archivo_eliminar($sTabla20, 'saiu20id', 'saiu20idorigenrta', 'saiu20idarchivorta', $idpadre, $objDB);
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->call("limpia_saiu20idarchivorta");
	return $objResponse;
}
function f3020_HTMLComboV2_btema($objDB, $objCombos, $valor, $vrbtipo)
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
		$objCombos->sAccion = 'paginarf3020()';
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
function f3020_Combobtema($aParametros)
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
	$html_btema = f3020_HTMLComboV2_btema($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_btema', 'innerHTML', $html_btema);
	$objResponse->call('jQuery("#btema").chosen({no_results_text: "No existen coincidencias: ",width: "100%"})');
	$objResponse->call('paginarf3020');
	return $objResponse;
}