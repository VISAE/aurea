<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2023 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.29.6 lunes, 31 de julio de 2023
--- 3073 saiu73solusuario
*/
/** Archivo lib3073.php.
 * Libreria 3073 saiu73solusuario.
 * @author Omar Augusto Bautista Mora - omar.bautista@unad.edu.co
 * @date jueves, 17 de octubre de 2024
 */
function f3073_HTMLComboV2_saiu73agno($objDB, $objCombos, $valor){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('saiu73agno', $valor, false, '{'.$ETI['msg_seleccione'].'}');
	//$objCombos->iAncho=450;
	$objCombos->sAccion='RevisaLlave();';
	$sSQL='SHOW TABLES LIKE "saiu73solusuario%"';
	//if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Total Periodo: Lista de contenedores: '.$sSQL.'<br>';}
	$tablac=$objDB->ejecutasql($sSQL);
	while($filac=$objDB->sf($tablac)){
		$sAgno=substr($filac[0], 17);
		$objCombos->addItem($sAgno, $sAgno);
	}
	$res=$objCombos->html('', $objDB);
	return $res;
	}
function f3073_HTMLComboV2_saiu73mes($objDB, $objCombos, $valor){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	/*
	$objCombos->nuevo('saiu73mes', $valor, false, '{'.$ETI['msg_seleccione'].'}');
	//$objCombos->iAncho=450;
	$objCombos->sAccion='RevisaLlave();';
	$sSQL='SELECT  AS id,  AS nombre FROM ';
	$res=$objCombos->html($sSQL, $objDB);
	*/
	$res=html_ComboMes('saiu73mes', $valor, false, 'RevisaLlave();');
	return $res;
	}
function f3073_HTMLComboV2_saiu73tiporadicado($objDB, $objCombos, $valor){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('saiu73tiporadicado', $valor, false, '{'.$ETI['msg_seleccione'].'}');
	//$objCombos->iAncho=450;
	$objCombos->sAccion='RevisaLlave();';
	$sSQL='SELECT saiu16id AS id, saiu16nombre AS nombre FROM saiu16tiporadicado WHERE saiu16id IN (1, 3) ORDER BY saiu16nombre';
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f3073_HTMLComboV2_saiu73tiposolicitud($objDB, $objCombos, $valor){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('saiu73tiposolicitud', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	$objCombos->sAccion='carga_combo_saiu73temasolicitud();';
	//$objCombos->iAncho=450;
	$sSQL='SELECT TB.saiu02id AS id, CONCAT(TB.saiu02titulo, " [", T1.saiu01titulo, "]") AS nombre 
	FROM saiu02tiposol AS TB, saiu01claseser AS T1 
	WHERE TB.saiu02id>0 AND TB.saiu02ordenllamada<9 AND TB.saiu02clasesol=T1.saiu01id 
	ORDER BY TB.saiu02ordenllamada, TB.saiu02titulo';
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f3073_HTMLComboV2_saiu73temasolicitud($objDB, $objCombos, $valor, $vrsaiu73tiposolicitud){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('saiu73temasolicitud', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	//$objCombos->iAncho=450;
	$sSQL='';
	if ((int)$vrsaiu73tiposolicitud==0){
		$objCombos->sAccion='carga_combo_saiu73tiposolicitud()';
		$sSQL='SELECT TB.saiu03id AS id, CONCAT(TB.saiu03titulo, " [", T2.saiu02titulo, "]") AS nombre 
		FROM saiu03temasol AS TB, saiu02tiposol AS T2 
		WHERE TB.saiu03id>0 AND TB.saiu03ordenllamada<9 AND TB.saiu03tiposol=T2.saiu02id
		ORDER BY TB.saiu03ordensoporte, TB.saiu03titulo';
		}else{
		$sSQL='SELECT saiu03id AS id, saiu03titulo AS nombre 
		FROM saiu03temasol 
		WHERE saiu03id>0 AND saiu03ordenllamada<9 AND saiu03tiposol='.$vrsaiu73tiposolicitud.'
		ORDER BY saiu03ordenllamada, saiu03titulo';
		}
	//if ((int)$vrsaiu73tiposolicitud!=0){}
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f3073_HTMLComboV2_saiu73idcentro($objDB, $objCombos, $valor, $vrsaiu73idzona){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$sCondi='';
	$objCombos->nuevo('saiu73idcentro', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	//$objCombos->iAncho=450;
	$sSQL='';
	if ((int)$vrsaiu73idzona!=0) {
		$sSQL='SELECT unad24id AS id, CONCAT(unad24nombre, CASE unad24activa WHEN "S" THEN "" ELSE " [INACTIVA]" END) AS nombre 
		FROM unad24sede 
		WHERE unad24idzona='.$vrsaiu73idzona.' AND unad24id>0 
		ORDER BY unad24activa DESC, unad24nombre';
	}
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f3073_HTMLComboV2_saiu73coddepto($objDB, $objCombos, $valor, $vrsaiu73codpais){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('saiu73coddepto', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	//$objCombos->iAncho=450;
	$objCombos->sAccion='carga_combo_saiu73codciudad()';
	$sSQL='';
	if ((int)$vrsaiu73codpais!=0){
		$sSQL='SELECT unad19codigo AS id, unad19nombre AS nombre FROM unad19depto WHERE unad19codpais="'.$vrsaiu73codpais.'" ORDER BY unad19nombre';
		}
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f3073_HTMLComboV2_saiu73codciudad($objDB, $objCombos, $valor, $vrsaiu73coddepto){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('saiu73codciudad', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	//$objCombos->iAncho=450;
	$sSQL='';
	if ((int)$vrsaiu73coddepto!=0){
		$sSQL='SELECT unad20codigo AS id, unad20nombre AS nombre FROM unad20ciudad WHERE unad20coddepto="'.$vrsaiu73coddepto.'" ORDER BY unad20nombre';
		}
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f3073_Combosaiu73tiposolicitud($aParametros){
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
		$html_saiu73tiposolicitud=f3073_HTMLComboV2_saiu73tiposolicitud($objDB, $objCombos, $idTipo);
		$html_saiu73temasolicitud=f3073_HTMLComboV2_saiu73temasolicitud($objDB, $objCombos, $idTema, $idTipo);
		$objDB->CerrarConexion();
		$objResponse=new xajaxResponse();
		$objResponse->assign('div_saiu73tiposolicitud', 'innerHTML', $html_saiu73tiposolicitud);
		$objResponse->assign('div_saiu73temasolicitud', 'innerHTML', $html_saiu73temasolicitud);
		$objResponse->call('$("#saiu73tiposolicitud").chosen()');
		$objResponse->call('$("#saiu73temasolicitud").chosen()');
		return $objResponse;
		}else{
		$objDB->CerrarConexion();
		}
	}
function f3073_Combosaiu73temasolicitud($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$objCombos=new clsHtmlCombos();
	$html_saiu73temasolicitud=f3073_HTMLComboV2_saiu73temasolicitud($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_saiu73temasolicitud', 'innerHTML', $html_saiu73temasolicitud);
	$objResponse->call('$("#saiu73temasolicitud").chosen({width:"100%"})');
	return $objResponse;
	}
function f3073_HTMLComboV2_saiu73idprograma($objDB, $objCombos, $valor, $vrsaiu73idescuela){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('saiu73idprograma', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	$objCombos->addItem('0', $ETI['msg_na']);
	//$objCombos->iAncho=450;
	$sCondiEscuela=' AND TB.core09idescuela="'.$vrsaiu73idescuela.'"';
	$sTabla2='';
	$sCampos2='';
	if ($vrsaiu73idescuela==''){
		$sCondiEscuela=' AND TB.core09idescuela=T12.core12id';
		$sTabla2=', core12escuela AS T12';
		$sCampos2=', " [", T12.core12sigla, "]"';
		}
	$sSQL='SELECT TB.core09id AS id, CONCAT(TB.core09nombre, " [", TB.core09codigo, "]"'.$sCampos2.') AS nombre FROM core09programa AS TB'.$sTabla2.' WHERE TB.core09id>0'.$sCondiEscuela;
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f3073_Combosaiu73idcentro($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$objCombos=new clsHtmlCombos();
	$html_saiu73idcentro=f3073_HTMLComboV2_saiu73idcentro($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_saiu73idcentro', 'innerHTML', $html_saiu73idcentro);
	$objResponse->call('$("#saiu73idcentro").chosen({width:"100%"})');
	return $objResponse;
	}
function f3073_Combosaiu73coddepto($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$objCombos=new clsHtmlCombos();
	$html_saiu73coddepto=f3073_HTMLComboV2_saiu73coddepto($objDB, $objCombos, '', $aParametros[0]);
	$html_saiu73codciudad=f3073_HTMLComboV2_saiu73codciudad($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_saiu73coddepto', 'innerHTML', $html_saiu73coddepto);
	$objResponse->call('$("#saiu73coddepto").chosen({width:"100%"})');
	$objResponse->assign('div_saiu73codciudad', 'innerHTML', $html_saiu73codciudad);
	$objResponse->call('$("#saiu73codciudad").chosen({width:"100%"})');
	return $objResponse;
	}
function f3073_Combosaiu73codciudad($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$objCombos=new clsHtmlCombos();
	$html_saiu73codciudad=f3073_HTMLComboV2_saiu73codciudad($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_saiu73codciudad', 'innerHTML', $html_saiu73codciudad);
	$objResponse->call('$("#saiu73codciudad").chosen({width:"100%"})');
	return $objResponse;
	}
function f3073_Combosaiu73idprograma($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$objCombos=new clsHtmlCombos();
	$html_saiu73idprograma=f3073_HTMLComboV2_saiu73idprograma($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_saiu73idprograma', 'innerHTML', $html_saiu73idprograma);
	$objResponse->call('$("#saiu73idprograma").chosen({width:"100%"})');
	return $objResponse;
	}
function f3073_ExisteDato($datos){
	if(!is_array($datos)){$datos=json_decode(str_replace('\"','"',$datos),true);}
	$_SESSION['u_ultimominuto']=iminutoavance();
	$bHayLlave=true;
	$saiu73agno=numeros_validar($datos[1]);
	if ($saiu73agno==''){$bHayLlave=false;}
	$saiu73mes=numeros_validar($datos[2]);
	if ($saiu73mes==''){$bHayLlave=false;}
	$saiu73tiporadicado=numeros_validar($datos[3]);
	if ($saiu73tiporadicado==''){$bHayLlave=false;}
	$saiu73consec=numeros_validar($datos[4]);
	if ($saiu73consec==''){$bHayLlave=false;}
	if ($bHayLlave){
		require './app.php';
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		$sSQL='SELECT 1 FROM saiu73solusuario_'.$saiu73agno.' WHERE saiu73agno='.$saiu73agno.' AND saiu73mes='.$saiu73mes.' AND saiu73tiporadicado='.$saiu73tiporadicado.' AND saiu73consec='.$saiu73consec.'';
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
function f3073_Busquedas($aParametros){
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3073=$APP->rutacomun.'lg/lg_3073_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3073)){$mensajes_3073=$APP->rutacomun.'lg/lg_3073_es.php';}
	require $mensajes_todas;
	require $mensajes_3073;
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
		case 'saiu73idsolicitante':
		require $APP->rutacomun.'lib111.php';
		$sTabla=f111_TablaDetalleBusquedas($aParametrosB, $objDB);
		$sTitulo='Busqueda de terceros';
		$sParams=f111_ParametrosBusqueda();
		$sJavaBusqueda=f111_JavaScriptBusqueda(3073);
		break;
		case 'saiu73idresponsable':
		require $APP->rutacomun.'lib111.php';
		$sTabla=f111_TablaDetalleBusquedas($aParametrosB, $objDB);
		$sTitulo='Busqueda de terceros';
		$sParams=f111_ParametrosBusqueda();
		$sJavaBusqueda=f111_JavaScriptBusqueda(3073);
		break;
		}
	$sTitulo='<h2>'.$ETI['titulo_3073'].' - '.$sTitulo.'</h2>';
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97titulo', 'innerHTML', $sTitulo);
	$objResponse->assign('div_97params', 'innerHTML', $sParams);
	$objResponse->assign('div_97tabla', 'innerHTML', $sTabla);
	$objResponse->setFunction('paginarbusqueda','',$sJavaBusqueda);
	$objResponse->call('expandesector(97)');
	return $objResponse;
	}
function f3073_HtmlBusqueda($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$sError='';
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$sDetalle='';
	switch($aParametros[100]){
		case 'saiu73idsolicitante':
		require $APP->rutacomun.'lib111.php';
		$sDetalle=f111_TablaDetalleBusquedas($aParametros, $objDB);
		break;
		case 'saiu73idresponsable':
		require $APP->rutacomun.'lib111.php';
		$sDetalle=f111_TablaDetalleBusquedas($aParametros, $objDB);
		break;
		}
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97tabla', 'innerHTML', $sDetalle);
	return $objResponse;
	}
function f3073_TablaDetalleV2($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3073=$APP->rutacomun.'lg/lg_3073_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3073)){$mensajes_3073=$APP->rutacomun.'lg/lg_3073_es.php';}
	require $mensajes_todas;
	require $mensajes_3073;
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
	if (isset($aParametros[112])==0){$aParametros[112]='';}
	if (isset($aParametros[113])==0){$aParametros[113]='';}
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
	$bcampus = $aParametros[110];
	$bzona = $aParametros[111];
	$bcead = $aParametros[112];
	$iCanal = $aParametros[113];
	$bAbierta=true;
	//$sSQL='SELECT Campo FROM Tabla WHERE Id='.$sValorId;
	//$tabla=$objDB->ejecutasql($sSQL);
	//if ($objDB->nf($tabla)>0){
		//$fila=$objDB->sf($tabla);
		//if ($fila['Campo']!='S'){$bAbierta=true;}
		//}
	$sLeyenda='';
	switch ($iCanal) {
		case 3018:
			$asaiu73solucion=$aSolucion3018;
			break;
		case 3019:
			$asaiu73solucion=$aSolucion3019;
			break;
		case 3020:
			$asaiu73solucion=$aSolucion3020;
			break;
		case 3021:
		case 3073:
			break;
		default:
			$sLeyenda = $ERR['saiu73idcanal'] . '<br>';
			break;
	}
	//Verificamos que exista la tabla.
	list($sErrorR, $sDebugR) = f3073_RevTabla_saiu73solusuario($iAgno, $objDB);
	if ($sErrorR != '') {
		$sLeyenda = $sLeyenda . $sErrorR . '<br>';
	}
	if ($iAgno == '') {
		$sLeyenda = $sLeyenda . $ERR['saiu73agno'];
	}
	if ($sLeyenda!=''){
		$sLeyenda='<div class="salto1px"></div>
<div class="GrupoCamposAyuda">
'.$sLeyenda.'
<div class="salto1px"></div>
</div>';
		return array($sLeyenda.'<input id="paginaf3073" name="paginaf3073" type="hidden" value="'.$pagina.'"/><input id="lppf3073" name="lppf3073" type="hidden" value="'.$lineastabla.'"/>', $sDebug);
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
	$aZona=array();
	$sSQL = 'SELECT unad23id AS id, unad23nombre AS nombre FROM unad23zona WHERE unad23conestudiantes="S"';
	$tabla=$objDB->ejecutasql($sSQL);
	while($fila=$objDB->sf($tabla)){
		$aZona[$fila['id']]=cadena_notildes($fila['nombre']);
		}
	$aCead=array();
	$sSQL = 'SELECT unad24id AS id, unad24nombre AS nombre FROM unad24sede';
	$tabla=$objDB->ejecutasql($sSQL);
	while($fila=$objDB->sf($tabla)){
		$aCead[$fila['id']]=cadena_notildes($fila['nombre']);
		}
	$sSQLadd='';
	$sSQLadd1='';
	if ($iEstado !== '') {
		$sSQLadd = $sSQLadd . ' AND TB.saiu73estado=' . $iEstado . '';
	}
	switch($bListar){
		case 1:
			$sSQLadd=$sSQLadd.' AND TB.saiu73idresponsable=' . $idTercero. '';		
			break;
		case 2:
			$sSQLadd=$sSQLadd.' AND TB.saiu73idresponsablecaso=' . $idTercero. '';		
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
				$sSQLadd = $sSQLadd . ' AND TB.saiu73idequipocaso IN (' . $sEquipos . ')';
			} else {
				$sSQLadd = $sSQLadd . ' AND TB.saiu73idresponsablecaso=' . $idTercero . '';
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
		$sSQLadd = $sSQLadd . ' AND TB.saiu73tiposolicitud=' . $bcategoria . '';
	}
	if ($btema !== '') {
		$sSQLadd = $sSQLadd . ' AND TB.saiu73temasolicitud=' . $btema . '';
	}
	if ($bcampus !== '') {
		$sSQLadd = $sSQLadd . ' AND TB.saiu73idsolicitante=' . $idTercero . '';
	}
	if ($bzona !== '') {
		$sSQLadd = $sSQLadd . ' AND TB.saiu73idzona=' . $bzona . '';
	}
	if ($bcead !== '') {
		$sSQLadd = $sSQLadd . ' AND TB.saiu73idcentro=' . $bcead . '';
	}
	$sSQLadd = $sSQLadd . ' AND TB.saiu73idcanal=' . $iCanal . '';
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
	$sTitulos='Agno, Mes, Tiporadicado, Consec, Id, Dia, Hora, Minuto, Estado, Telefono, Numtelefono, Chat, Correo, Solicitante, Tipointeresado, Clasesolicitud, Tiposolicitud, Temasolicitud, Zona, Centro, Codpais, Coddepto, Codciudad, Escuela, Programa, Periodo, Numorigen, Pqrs, Detalle, Horafin, Minutofin, Paramercadeo, Responsable, Tiemprespdias, Tiempresphoras, Tiemprespminutos';
	$registros=0;
	$bGigante=false; //En caso de que la tabla sea muy grande pasarlo a true
	$sLimite='';
	if ($bGigante){
		$sSQL='SELECT COUNT(1) AS Total 
FROM saiu73solusuario_'.$iAgno.' AS TB, unad11terceros AS T11
WHERE '.$sSQLadd1.' TB.saiu73idsolicitante=T11.unad11id '.$sSQLadd.'';
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
	//, TB.saiu73idpqrs, TB.saiu73detalle, TB.saiu73horafin, TB.saiu73minutofin, TB.saiu73paramercadeo, TB.saiu73tiemprespdias, TB.saiu73tiempresphoras, TB.saiu73tiemprespminutos, TB.saiu73tipointeresado, TB.saiu73clasesolicitud, TB.saiu73tiposolicitud, TB.saiu73temasolicitud, TB.saiu73idzona, TB.saiu73idcentro, TB.saiu73codpais, TB.saiu73coddepto, TB.saiu73codciudad, TB.saiu73idescuela, TB.saiu73idprograma, TB.saiu73idperiodo, TB.saiu73idresponsable
	$sSQL='SELECT TB.saiu73agno, TB.saiu73mes, TB.saiu73consec, TB.saiu73id, TB.saiu73dia, TB.saiu73hora, TB.saiu73minuto, 
T11.unad11razonsocial AS C12_nombre, TB.saiu73tiposolicitud, saiu73temasolicitud, TB.saiu73estado, T11.unad11tipodoc AS C12_td, 
T11.unad11doc AS C12_doc, TB.saiu73idsolicitante, TB.saiu73tiporadicado, TB.saiu73solucion, TB.saiu73idzona, TB.saiu73idcentro, 
TB.saiu73idcanal 
FROM saiu73solusuario_'.$iAgno.' AS TB, unad11terceros AS T11
WHERE '.$sSQLadd1.' TB.saiu73idsolicitante=T11.unad11id '.$sSQLadd.'
ORDER BY TB.saiu73agno DESC, TB.saiu73mes DESC, TB.saiu73dia DESC, TB.saiu73tiporadicado, TB.saiu73consec DESC';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_3073" name="consulta_3073" type="hidden" value="'.$sSQLlista.'"/>
<input id="titulos_3073" name="titulos_3073" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objDB->ejecutasql($sSQL.$sLimite);
	if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 3073: '.$sSQL.$sLimite.'<br>';}
	if ($tabladetalle==false){
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		//$sLeyenda=$sSQL;
		}else{
		if (!$bGigante){
			$registros=$objDB->nf($tabladetalle);
			if ($registros==0){
				//return array(cadena_codificar($sErrConsulta.'<input id="paginaf3073" name="paginaf3073" type="hidden" value="'.$pagina.'"/><input id="lppf3073" name="lppf3073" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
<td><b>'.$ETI['saiu73consec'].'</b></td>
<td colspan="2"><b>'.$ETI['saiu73idsolicitante'].'</b></td>
<td><b>'.$ETI['saiu73idzona'].'</b></td>
<td><b>'.$ETI['saiu73idcentro'].'</b></td>';
if ($iCanal != 3073) {
	$res=$res.'<td><b>'.$ETI['saiu73tiposolicitud'].'</b></td>';
}
$res=$res.'<td><b>'.$ETI['saiu73temasolicitud'].'</b></td>
<td><b>'.$ETI['saiu73solucion'].'</b></td>
<td><b>'.$ETI['saiu73estado'].'</b></td>
<td align="right">
'.html_paginador('paginaf3073', $registros, $lineastabla, $pagina, 'paginarf3073()').'
'.html_lpp('lppf3073', $lineastabla, 'paginarf3073()').'
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
		$et_saiu73hora=html_TablaHoraMin($filadet['saiu73hora'], $filadet['saiu73minuto']);
		$et_saiu73idsolicitante_doc='';
		$et_saiu73idsolicitante_nombre='';
		if ($filadet['saiu73idsolicitante']!=0){
			$et_saiu73idsolicitante_doc=$sPrefijo.$filadet['C12_td'].' '.$filadet['C12_doc'].$sSufijo;
			$et_saiu73idsolicitante_nombre=$sPrefijo.cadena_notildes($filadet['C12_nombre']).$sSufijo;
			}
		$sEstado = '';
		if (isset($aEstado[$filadet['saiu73estado']])==0) {
			$sEstado = $ETI['definir'];
		} else {
			$sEstado = $aEstado[$filadet['saiu73estado']];
		}
		$sCategoria = '';
		if (isset($aCategoria[$filadet['saiu73tiposolicitud']])==0) {
			$sCategoria = $ETI['definir'];
		} else {
			$sCategoria = $aCategoria[$filadet['saiu73tiposolicitud']];
		}
		$sTema = '';
		if (isset($aTema[$filadet['saiu73temasolicitud']])==0) {
			$sTema = $ETI['definir'];
		} else {
			$sTema = $aTema[$filadet['saiu73temasolicitud']];
		}
		$sSolucion = '';
		if (isset($asaiu73solucion[$filadet['saiu73solucion']])==0) {
			$sSolucion = $ETI['definir'];
		} else {
			$sSolucion = $asaiu73solucion[$filadet['saiu73solucion']];
		}
		$sZona = '';
		if (isset($aZona[$filadet['saiu73idzona']])==0) {
			$sZona = $ETI['definir'];
		} else {
			$sZona = $aZona[$filadet['saiu73idzona']];
		}
		$sCead = '';
		if (isset($aCead[$filadet['saiu73idcentro']])==0) {
			$sCead = $ETI['definir'];
		} else {
			$sCead = $aCead[$filadet['saiu73idcentro']];
		}
		/*
		$et_saiu73horafin=html_TablaHoraMin($filadet['saiu73horafin'], $filadet['saiu73minutofin']);
		$et_saiu73idresponsable_doc='';
		$et_saiu73idresponsable_nombre='';
		if ($filadet['saiu73idresponsable']!=0){
			$et_saiu73idresponsable_doc=$sPrefijo.$filadet['C31_td'].' '.$filadet['C31_doc'].$sSufijo;
			$et_saiu73idresponsable_nombre=$sPrefijo.cadena_notildes($filadet['C31_nombre']).$sSufijo;
			}
		*/
		if ($bAbierta){
			$sLink='<a href="javascript:cargaridf3073('.$filadet['saiu73agno'].','.$filadet['saiu73id'].')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$et_fecha=fecha_armar($filadet['saiu73dia'], $filadet['saiu73mes'], $filadet['saiu73agno']);
		$res=$res.'<tr'.$sClass.'>
<td>'.$sPrefijo.$et_fecha.$sSufijo.'</td>
<td>'.$sPrefijo.$et_saiu73hora.$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu73consec'].$sSufijo.'</td>
<td>'.$et_saiu73idsolicitante_doc.'</td>
<td>'.$et_saiu73idsolicitante_nombre.'</td>
<td>'.$sPrefijo.$sZona.$sSufijo.'</td>
<td>'.$sPrefijo.$sCead.$sSufijo.'</td>';
if ($iCanal != 3073) {
	$res=$res.'<td>'.$sPrefijo.$sCategoria.$sSufijo.'</td>';
}
$res=$res.'<td>'.$sPrefijo.$sTema.$sSufijo.'</td>
<td>'.$sPrefijo.$sSolucion.$sSufijo.'</td>
<td>'.$sPrefijo.$sEstado.$sSufijo.'</td>
<td>'.$sLink.'</td>
</tr>';
		}
	$res=$res.'</table>';
	$objDB->liberar($tabladetalle);
	return array(cadena_codificar($res), $sDebug);
	}
function f3073_HtmlTabla($aParametros){
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
	list($sDetalle, $sDebugTabla)=f3073_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f3073detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f3073_db_CargarPadre($DATA, $objDB, $bDebug=false){
	$sError='';
	$iTipoError=0;
	$sDebug='';
	require './app.php';
	$DATA['saiu73idsolicitante_td']=$APP->tipo_doc;
	$DATA['saiu73idsolicitante_doc']='';
	$DATA['saiu73idresponsable_td']=$APP->tipo_doc;
	$DATA['saiu73idresponsable_doc']='';
	if ($DATA['paso']==1){
		$sSQLcondi='saiu73agno='.$DATA['saiu73agno'].' AND saiu73mes='.$DATA['saiu73mes'].' AND saiu73tiporadicado='.$DATA['saiu73tiporadicado'].' AND saiu73consec='.$DATA['saiu73consec'].'';
		}else{
		$sSQLcondi='saiu73id='.$DATA['saiu73id'].'';
		}
	$sSQL='SELECT * FROM saiu73solusuario_'.$DATA['saiu73agno'].' WHERE '.$sSQLcondi;
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$DATA['saiu73agno']=$fila['saiu73agno'];
		$DATA['saiu73mes']=$fila['saiu73mes'];
		$DATA['saiu73tiporadicado']=$fila['saiu73tiporadicado'];
		$DATA['saiu73consec']=$fila['saiu73consec'];
		$DATA['saiu73id']=$fila['saiu73id'];
		$DATA['saiu73dia']=$fila['saiu73dia'];
		$DATA['saiu73hora']=$fila['saiu73hora'];
		$DATA['saiu73minuto']=$fila['saiu73minuto'];
		$DATA['saiu73estado']=$fila['saiu73estado'];
		$DATA['saiu73idsolicitante']=$fila['saiu73idsolicitante'];
		$DATA['saiu73tipointeresado']=$fila['saiu73tipointeresado'];
		$DATA['saiu73clasesolicitud']=$fila['saiu73clasesolicitud'];
		$DATA['saiu73tiposolicitud']=$fila['saiu73tiposolicitud'];
		$DATA['saiu73temasolicitud']=$fila['saiu73temasolicitud'];
		$DATA['saiu73idzona']=$fila['saiu73idzona'];
		$DATA['saiu73idcentro']=$fila['saiu73idcentro'];
		$DATA['saiu73codpais']=$fila['saiu73codpais'];
		$DATA['saiu73coddepto']=$fila['saiu73coddepto'];
		$DATA['saiu73codciudad']=$fila['saiu73codciudad'];
		$DATA['saiu73idescuela']=$fila['saiu73idescuela'];
		$DATA['saiu73idprograma']=$fila['saiu73idprograma'];
		$DATA['saiu73idperiodo']=$fila['saiu73idperiodo'];
		$DATA['saiu73idpqrs']=$fila['saiu73idpqrs'];
		$DATA['saiu73detalle']=$fila['saiu73detalle'];
		$DATA['saiu73horafin']=$fila['saiu73horafin'];
		$DATA['saiu73minutofin']=$fila['saiu73minutofin'];
		$DATA['saiu73paramercadeo']=$fila['saiu73paramercadeo'];
		$DATA['saiu73idresponsable']=$fila['saiu73idresponsable'];
		$DATA['saiu73tiemprespdias']=$fila['saiu73tiemprespdias'];
		$DATA['saiu73tiempresphoras']=$fila['saiu73tiempresphoras'];
		$DATA['saiu73tiemprespminutos']=$fila['saiu73tiemprespminutos'];
		$DATA['saiu73solucion']=$fila['saiu73solucion'];
		$DATA['saiu73idcaso']=$fila['saiu73idcaso'];
		$DATA['saiu73respuesta']=$fila['saiu73respuesta'];
		$DATA['saiu73idcanal']=$fila['saiu73idcanal'];
		$DATA['saiu73idtelefono']=$fila['saiu73idtelefono'];
		$DATA['saiu73numtelefono']=$fila['saiu73numtelefono'];
		$DATA['saiu73numorigen']=$fila['saiu73numorigen'];
		$DATA['saiu73idchat']=$fila['saiu73idchat'];
		$DATA['saiu73numsesionchat']=$fila['saiu73numsesionchat'];
		$DATA['saiu73idcorreo']=$fila['saiu73idcorreo'];
		$DATA['saiu73correoorigen']=$fila['saiu73correoorigen'];
		$bcargo=true;
		$DATA['paso']=2;
		$DATA['boculta3073']=0;
		$bLimpiaHijos=true;
		}else{
		$DATA['paso']=0;
		}
	return array($DATA, $sError, $iTipoError, $sDebug);
	}
function f3073_Cerrar($saiu73id, $objDB, $bDebug=false){
	$sInfo='';
	$sDebug='';
	return array($sInfo, $sDebug);
	}
function f3073_db_GuardarV2($DATA, $objDB, $bDebug=false){
	$iCodModulo=3073;
	$bAudita[2]=true;
	$bAudita[3]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3073=$APP->rutacomun.'lg/lg_3073_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3073)){$mensajes_3073=$APP->rutacomun.'lg/lg_3073_es.php';}
	require $mensajes_todas;
	require $mensajes_3073;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	$bCerrando=false;
	$sErrorCerrando='';
	// -- Se inicia validando todas las posibles entradas de usuario.
	/*
	if (isset($DATA['saiu73agno'])==0){$DATA['saiu73agno']='';}
	if (isset($DATA['saiu73mes'])==0){$DATA['saiu73mes']='';}
	if (isset($DATA['saiu73tiporadicado'])==0){$DATA['saiu73tiporadicado']='';}
	if (isset($DATA['saiu73consec'])==0){$DATA['saiu73consec']='';}
	if (isset($DATA['saiu73id'])==0){$DATA['saiu73id']='';}
	if (isset($DATA['saiu73dia'])==0){$DATA['saiu73dia']='';}
	if (isset($DATA['saiu73hora'])==0){$DATA['saiu73hora']='';}
	if (isset($DATA['saiu73minuto'])==0){$DATA['saiu73minuto']='';}
	if (isset($DATA['saiu73idsolicitante'])==0){$DATA['saiu73idsolicitante']='';}
	if (isset($DATA['saiu73tipointeresado'])==0){$DATA['saiu73tipointeresado']='';}
	if (isset($DATA['saiu73clasesolicitud'])==0){$DATA['saiu73clasesolicitud']='';}
	if (isset($DATA['saiu73tiposolicitud'])==0){$DATA['saiu73tiposolicitud']='';}
	if (isset($DATA['saiu73temasolicitud'])==0){$DATA['saiu73temasolicitud']='';}
	if (isset($DATA['saiu73idzona'])==0){$DATA['saiu73idzona']='';}
	if (isset($DATA['saiu73idcentro'])==0){$DATA['saiu73idcentro']='';}
	if (isset($DATA['saiu73codpais'])==0){$DATA['saiu73codpais']='';}
	if (isset($DATA['saiu73coddepto'])==0){$DATA['saiu73coddepto']='';}
	if (isset($DATA['saiu73codciudad'])==0){$DATA['saiu73codciudad']='';}
	if (isset($DATA['saiu73idescuela'])==0){$DATA['saiu73idescuela']='';}
	if (isset($DATA['saiu73idprograma'])==0){$DATA['saiu73idprograma']='';}
	if (isset($DATA['saiu73idperiodo'])==0){$DATA['saiu73idperiodo']='';}
	if (isset($DATA['saiu73detalle'])==0){$DATA['saiu73detalle']='';}
	if (isset($DATA['saiu73horafin'])==0){$DATA['saiu73horafin']='';}
	if (isset($DATA['saiu73minutofin'])==0){$DATA['saiu73minutofin']='';}
	if (isset($DATA['saiu73paramercadeo'])==0){$DATA['saiu73paramercadeo']='';}
	if (isset($DATA['saiu73idresponsable'])==0){$DATA['saiu73idresponsable']='';}
	if (isset($DATA['saiu73solucion'])==0){$DATA['saiu73solucion']='';}
	if (isset($DATA['saiu73respuesta'])==0){$DATA['saiu73respuesta']='';}
	*/
	if (isset($DATA['bcampus'])==0){$DATA['bcampus']=0;}
	if (isset($DATA['saiu73idchat'])==0){$DATA['saiu73idchat']=0;}
	if (isset($DATA['saiu73idcorreo'])==0){$DATA['saiu73idcorreo']=0;}
	$DATA['saiuid']=73;
	$DATA['saiu73agno']=numeros_validar($DATA['saiu73agno']);
	$DATA['saiu73mes']=numeros_validar($DATA['saiu73mes']);
	$DATA['saiu73tiporadicado']=numeros_validar($DATA['saiu73tiporadicado']);
	$DATA['saiu73consec']=numeros_validar($DATA['saiu73consec']);
	$DATA['saiu73dia']=numeros_validar($DATA['saiu73dia']);
	$DATA['saiu73hora']=numeros_validar($DATA['saiu73hora']);
	$DATA['saiu73minuto']=numeros_validar($DATA['saiu73minuto']);
	$DATA['saiu73tipointeresado']=numeros_validar($DATA['saiu73tipointeresado']);
	$DATA['saiu73clasesolicitud']=numeros_validar($DATA['saiu73clasesolicitud']);
	$DATA['saiu73tiposolicitud']=numeros_validar($DATA['saiu73tiposolicitud']);
	$DATA['saiu73temasolicitud']=numeros_validar($DATA['saiu73temasolicitud']);
	$DATA['saiu73idzona']=numeros_validar($DATA['saiu73idzona']);
	$DATA['saiu73idcentro']=numeros_validar($DATA['saiu73idcentro']);
	$DATA['saiu73codpais']=htmlspecialchars(trim($DATA['saiu73codpais']));
	$DATA['saiu73coddepto']=htmlspecialchars(trim($DATA['saiu73coddepto']));
	$DATA['saiu73codciudad']=htmlspecialchars(trim($DATA['saiu73codciudad']));
	$DATA['saiu73idescuela']=numeros_validar($DATA['saiu73idescuela']);
	$DATA['saiu73idprograma']=numeros_validar($DATA['saiu73idprograma']);
	$DATA['saiu73idperiodo']=numeros_validar($DATA['saiu73idperiodo']);
	$DATA['saiu73detalle']=htmlspecialchars(trim($DATA['saiu73detalle']));
	$DATA['saiu73fechafin']=numeros_validar($DATA['saiu73fechafin']);
	$DATA['saiu73horafin']=numeros_validar($DATA['saiu73horafin']);
	$DATA['saiu73minutofin']=numeros_validar($DATA['saiu73minutofin']);
	$DATA['saiu73paramercadeo']=numeros_validar($DATA['saiu73paramercadeo']);
	$DATA['saiu73solucion']=numeros_validar($DATA['saiu73solucion']);
	$DATA['saiu73respuesta']=htmlspecialchars(trim($DATA['saiu73respuesta']));
	$DATA['saiu73idcaso']=numeros_validar($DATA['saiu73idcaso']);
	$DATA['saiu73fecharespcaso']=numeros_validar($DATA['saiu73fecharespcaso']);
	$DATA['saiu73horarespcaso']=numeros_validar($DATA['saiu73horarespcaso']);
	$DATA['saiu73minrespcaso']=numeros_validar($DATA['saiu73minrespcaso']);
	$DATA['saiu73idunidadcaso']=numeros_validar($DATA['saiu73idunidadcaso']);
	$DATA['saiu73idequipocaso']=numeros_validar($DATA['saiu73idequipocaso']);
	$DATA['saiu73idsupervisorcaso']=numeros_validar($DATA['saiu73idsupervisorcaso']);
	$DATA['saiu73idresponsablecaso']=numeros_validar($DATA['saiu73idresponsablecaso']);
	$DATA['saiu73numref']=htmlspecialchars(trim($DATA['saiu73numref']));
	$DATA['saiu73idcanal']=numeros_validar($DATA['saiu73idcanal']);
	$DATA['saiu73idtelefono']=numeros_validar($DATA['saiu73idtelefono']);
	$DATA['saiu73numtelefono']=htmlspecialchars(trim($DATA['saiu73numtelefono']));
	$DATA['saiu73numorigen']=htmlspecialchars(trim($DATA['saiu73numorigen']));
	$DATA['saiu73idchat']=numeros_validar($DATA['saiu73idchat']);
	$DATA['saiu73numsesionchat']=htmlspecialchars(trim($DATA['saiu73numsesionchat']));
	$DATA['saiu73idcorreo']=numeros_validar($DATA['saiu73idcorreo']);
	$DATA['saiu73idcorreootro']=htmlspecialchars(trim($DATA['saiu73idcorreootro']));
	$DATA['saiu73correoorigen']=htmlspecialchars(trim($DATA['saiu73correoorigen']));
	// -- Se inicializan las variables que puedan pasar vacias {Especialmente números}.
	//if ($DATA['saiu73dia']==''){$DATA['saiu73dia']=0;}
	//if ($DATA['saiu73hora']==''){$DATA['saiu73hora']=0;}
	//if ($DATA['saiu73minuto']==''){$DATA['saiu73minuto']=0;}
	if ($DATA['saiu73estado']==''){$DATA['saiu73estado']=-1;}
	if ($DATA['saiu73estadoorigen']==''){$DATA['saiu73estadoorigen']=-1;}
	//if ($DATA['saiu73tipointeresado']==''){$DATA['saiu73tipointeresado']=0;}
	if ($DATA['saiu73idpqrs']==''){$DATA['saiu73idpqrs']=0;}
	//if ($DATA['saiu73paramercadeo']==''){$DATA['saiu73paramercadeo']=0;}
	//if ($DATA['saiu73solucion']==''){$DATA['saiu73solucion']=0;}
	// -- Seccion para validar los posibles causales de error.
	$sSepara=', ';
	$bConCierre=false;
	$bEnviaEncuesta=false;
	$bEnviaCaso=false;
	if ($DATA['saiu73temasolicitud']==''){$sError=$ERR['saiu73temasolicitud'].$sSepara.$sError;}
	if ($DATA['saiu73tiposolicitud']==''){$sError=$ERR['saiu73tiposolicitud'].$sSepara.$sError;}
	// if ($DATA['saiu73clasesolicitud']==''){$sError=$ERR['saiu73clasesolicitud'].$sSepara.$sError;}
	if ($DATA['saiu73tipointeresado']==''){$sError=$ERR['saiu73tipointeresado'].$sSepara.$sError;}
	if ($DATA['saiu73idsolicitante']==0){$sError=$ERR['saiu73idsolicitante'].$sSepara.$sError;}
	if ($DATA['saiu73minuto']==''){$sError=$ERR['saiu73minuto'].$sSepara.$sError;}
	if ($DATA['saiu73hora']==''){$sError=$ERR['saiu73hora'].$sSepara.$sError;}
	if ($DATA['saiu73dia']==''){$sError=$ERR['saiu73dia'].$sSepara.$sError;}
	if ($DATA['saiu73mes']==''){$sError=$ERR['saiu73mes'].$sSepara.$sError;}
	if ($DATA['saiu73agno']==''){$sError=$ERR['saiu73agno'].$sSepara.$sError;}
	if ($DATA['saiu73idcentro']==''){$sError=$ERR['saiu73idcentro'].$sSepara.$sError;}
	if ($DATA['saiu73idzona']==''){$sError=$ERR['saiu73idzona'].$sSepara.$sError;}
	if ($DATA['saiu73detalle']==''){$sError=$ERR['saiu73detalle'].$sSepara.$sError;}
	if ($DATA['saiu73fecharespcaso']==''){$DATA['saiu73fecharespcaso']=0;}
	if ($DATA['saiu73estado']==1 || $DATA['saiu73estado']==7){
		if ($DATA['saiu73solucion']=='') {
			$sError=$ERR['saiu73solucion'].$sSepara.$sError;
			}else{
			if ((int)$DATA['saiu73solucion']==0){
				$sError=$ERR['saiu73solucion_proceso'].$sSepara.$sError;
				}
			}
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Estado: '.$DATA['saiu73estado']. ' - Solución: '. $DATA['saiu73solucion'] .'<br>';}
		if ($DATA['saiu73idresponsable']==0){$sError=$ERR['saiu73idresponsable'].$sSepara.$sError;}
		if ($DATA['saiu73paramercadeo']==''){$sError=$ERR['saiu73paramercadeo'].$sSepara.$sError;}
		//if ($DATA['saiu73minutofin']==''){$sError=$ERR['saiu73minutofin'].$sSepara.$sError;}
		//if ($DATA['saiu73horafin']==''){$sError=$ERR['saiu73horafin'].$sSepara.$sError;}
		if ($DATA['saiu73idperiodo']==''){$sError=$ERR['saiu73idperiodo'].$sSepara.$sError;}
		if ($DATA['saiu73idprograma']==''){$sError=$ERR['saiu73idprograma'].$sSepara.$sError;}
		if ($DATA['saiu73idescuela']==''){$sError=$ERR['saiu73idescuela'].$sSepara.$sError;}
		if ($DATA['saiu73codciudad']==''){$sError=$ERR['saiu73codciudad'].$sSepara.$sError;}
		if ($DATA['saiu73coddepto']==''){$sError=$ERR['saiu73coddepto'].$sSepara.$sError;}
		if ($DATA['saiu73codpais']==''){$sError=$ERR['saiu73codpais'].$sSepara.$sError;}
		if ($DATA['saiu73idcanal']==''){$sError=$ERR['saiu73idcanal'].$sSepara.$sError;}
		//if ($DATA['saiu73hora']==''){$DATA['saiu73hora']=fecha_hora();}
		if ($DATA['saiu73solucion']==1){ // Resuelto en la atención
			if ($DATA['saiu73respuesta']==''){$sError=$ERR['saiu73respuesta'].$sSepara.$sError;}
		}
		if ($DATA['saiu73solucion']==3) { // Se inicia caso
			if ($DATA['saiu73temasolicitud'] != $DATA['saiu73temasolicitudorigen']) {
				$DATA['saiu73idresponsablecaso']=0;
			}
			if ($DATA['saiu73idresponsablecaso']==0) {
				list($DATA['saiu73idunidadcaso'], $DATA['saiu73idequipocaso'], $DATA['saiu73idsupervisorcaso'], $DATA['saiu73idresponsablecaso'], $saiu73tiemprespdias, $saiu73tiempresphoras, $sErrorF, $iTipoError, $sDebugF) = f3073_ConsultaResponsable($DATA, $objDB, $bDebug);
				if ($sErrorF!=''){$sError=$sError.'<br>'.$sErrorF;}
				if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta Responsable: '.$sDebugF.'<br>';}
				if ($DATA['saiu73idunidadcaso']==0){$sError=$ERR['saiu73idunidadcaso'].$sSepara.$sError;}
				if ($DATA['saiu73idequipocaso']==0){$sError=$ERR['saiu73idequipocaso'].$sSepara.$sError;}
				if ($DATA['saiu73idsupervisorcaso']==0){$sError=$ERR['saiu73idsupervisorcaso'].$sSepara.$sError;}
				if ($DATA['saiu73idresponsablecaso']==0){$sError=$ERR['saiu73idresponsablecaso'].$sSepara.$sError;}
				if ($sError!='') {
					$DATA['saiu73idunidadcaso']=0;
					$DATA['saiu73idequipocaso']=0;
					$DATA['saiu73idsupervisorcaso']=0;
					$DATA['saiu73idresponsablecaso']=0;
				}
			}
		}
		switch ($_REQUEST['saiu73idcanal']) {
			case 3018:
				if ($DATA['saiu73idtelefono']==''){
					$sError=$ERR['saiu73idtelefono'].$sSepara.$sError;
				}else{
					if ($DATA['saiu73idtelefono']=='-1'){
						if ($DATA['saiu73numtelefono']==''){$sError=$ERR['saiu73numtelefono'].$sSepara.$sError;}
					}
				}
	            break;
			case 3019:
				if ($DATA['saiu73idchat']==''){$sError=$ERR['saiu73idchat'].$sSepara.$sError;}
	            break;
			case 3020:
				if ($DATA['saiu73correoorigen']==''){$sError=$ERR['saiu73correoorigen'].$sSepara.$sError;}
				if ($DATA['saiu73idcorreo']==''){$sError=$ERR['saiu73idcorreo'].$sSepara.$sError;}
				if ($DATA['saiu73idcorreo']==3){
					if ($DATA['saiu73idcorreootro']==''){$sError=$ERR['saiu73idcorreo'].$sSepara.$sError;}
				} else {
					$DATA['saiu73idcorreootro']=='';
				}
	            break;
			case 3021:
	            break;
			default:
		    break;
		}
		if ($DATA['saiu73estado']==7) {
			$bConCierre=true;
			if ($DATA['saiu73estadoorigen']==1) {
				if ($DATA['saiu73respuesta']==''){$sError=$ERR['saiu73respuesta'].$sSepara.$sError;}
				if ($sError=='') {
					$DATA['saiu73idresponsablecaso']=$_SESSION['unad_id_tercero'];
					if ($DATA['saiu73idresponsable']==$DATA['saiu73idsolicitante']) {
						$DATA['saiu73idresponsable']=$_SESSION['unad_id_tercero'];
						$DATA['saiu73fechafin']=fecha_DiaMod();
						$DATA['saiu73horafin']=fecha_hora();
						$DATA['saiu73minutofin']=fecha_minuto();
						$iDiaIni=($DATA['saiu73agno']*10000)+($DATA['saiu73mes']*100)+$DATA['saiu73dia'];
						list($DATA['saiu73tiemprespdias'], $DATA['saiu73tiempresphoras'], $DATA['saiu73tiemprespminutos'])=Tiempo_MinutosCalendario($iDiaIni, $DATA['saiu73hora'], $DATA['saiu73minuto'], $DATA['saiu73fechafin'], $DATA['saiu73horafin'], $DATA['saiu73minutofin']);
					}
				}	
			}
		}
		if ($sError=='') {
			if ($DATA['saiu73solucion']==5) { // Se inicia PQRS
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
				$aParams['saiu05idsolicitante']=$DATA['saiu73idsolicitante'];
				$aParams['saiu05tipointeresado']=$DATA['saiu73tipointeresado'];
				$aParams['saiu05detalle']=$DATA['saiu73detalle'];
				$aParams['saiu05idtiposolorigen']=$DATA['saiu73tiposolicitud'];
				$aParams['saiu05idtemaorigen']=$DATA['saiu73temasolicitud'];
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
					$DATA['saiu73idpqrs']=$aParams['saiu05id'];
					$DATA['saiu73numref']=$aParams['saiu05numref'];
					if ($DATA['saiu73idpqrs']==-1){$sError=$ERR['saiu73idpqrs'].$sSepara.$sError;}
					if ($DATA['saiu73numref']==''){$sError=$ERR['saiu73numref'].$sSepara.$sError;}
				}
			}
		}
		if ($sError!=''){
			if ($DATA['saiu73estado']!=1) {	// Asignado
				$DATA['saiu73estado']=$DATA['saiu73estadoorigen'];
			}
		}
		$sErrorCerrando=$sError;
		//Fin de las valiaciones NO LLAVE.
		}
	if ($sError==''){
		switch($DATA['saiu73estado']){
			case 1: //Caso Asignado
			break;
			case 7: //Logra cerrar			
			switch($DATA['saiu73solucion']){
				case 1: // Resuelto en la atención
				case 5: // Se inicia PQRS
				$DATA['saiu73fecharespcaso']=0;
				$DATA['saiu73fechafin']=fecha_DiaMod();
				$DATA['saiu73horafin']=fecha_hora();
				$DATA['saiu73minutofin']=fecha_minuto();
				$bEnviaEncuesta=true;
				break;
				case 3: // Se inicia caso
				if ($DATA['saiu73estadoorigen']==1) {
					if ($DATA['saiu73respuesta']==''){
						$sError=$ERR['saiu73respuesta'].$sSepara.$sError;
					} else {
						$DATA['saiu73fecharespcaso']=fecha_DiaMod();
						$DATA['saiu73horarespcaso']=fecha_hora();
						$DATA['saiu73minrespcaso']=fecha_minuto();
						$bEnviaEncuesta=true;
					}
				} else {
					if ($DATA['saiu73estadoorigen']==-1) {
						$DATA['saiu73agno']=fecha_agno();
						$DATA['saiu73mes']=fecha_mes();
						$DATA['saiu73dia']=fecha_dia();
						$DATA['saiu73hora']=fecha_hora();
						$DATA['saiu73minuto']=fecha_minuto();
					}
					$DATA['saiu73fecharespcaso']=0;
					$DATA['saiu73fechafin']=fecha_DiaMod();
					$DATA['saiu73horafin']=fecha_hora();
					$DATA['saiu73minutofin']=fecha_minuto();
					$iDiaIni=($DATA['saiu73agno']*10000)+($DATA['saiu73mes']*100)+$DATA['saiu73dia'];
					list($DATA['saiu73tiemprespdias'], $DATA['saiu73tiempresphoras'], $DATA['saiu73tiemprespminutos'])=Tiempo_MinutosCalendario($iDiaIni, $DATA['saiu73hora'], $DATA['saiu73minuto'], $DATA['saiu73fechafin'], $DATA['saiu73horafin'], $DATA['saiu73minutofin']);
					$DATA['saiu73idcaso']=(int)($DATA['saiu73agno'].$DATA['saiu73id'].'');
					$DATA['saiu73respuesta']='';
					$DATA['saiu73estado']=1;
					$bEnviaCaso=true;
				}
				break;
				case 8: //Cancelada por el usuario
				$DATA['saiu73respuesta']=$ETI['termina_usuario'];
				break;
				case 9: //Terminada por el asesor
				$DATA['saiu73respuesta']=$ETI['termina_asesor'];
				break;
				default:
				$sError=$ERR['saiu73solucion'].$sSepara.$sError;
				break;
			}
			break;
			case 8: //Solicitud abandonada
			case 9: //Cancelada por el usuario
			if (trim($DATA['saiu73fechafin'])==''){$DATA['saiu73fechafin']=fecha_DiaMod();}
			if (trim($DATA['saiu73minutofin'])==''){$DATA['saiu73minutofin']=fecha_minuto();}
			if (trim($DATA['saiu73horafin'])==''){$DATA['saiu73horafin']=fecha_hora();}
			//$DATA['saiu73minutofin']=fecha_minuto();
			//$DATA['saiu73horafin']=fecha_hora();
			break; 
			default:
			$DATA['saiu73estado']=$DATA['saiu73estadoorigen'];
			if ($DATA['saiu73hora']==''){$DATA['saiu73hora']=fecha_hora();}
			if ($DATA['saiu73minuto']==''){$DATA['saiu73minuto']=fecha_minuto();}
			if ($DATA['saiu73fechafin']==''){$DATA['saiu73fechafin']=0;}
			if ($DATA['saiu73horafin']==''){$DATA['saiu73horafin']=0;}
			if ($DATA['saiu73minutofin']==''){$DATA['saiu73minutofin']=0;}
			break;
		}
		if ($DATA['saiu73clasesolicitud']==''){$DATA['saiu73clasesolicitud']=0;}
		if ($DATA['saiu73tiposolicitud']==''){$DATA['saiu73tiposolicitud']=0;}
		if ($DATA['saiu73temasolicitud']==''){$DATA['saiu73temasolicitud']=0;}
		if ($DATA['saiu73idzona']==''){$DATA['saiu73idzona']=0;}
		if ($DATA['saiu73idcentro']==''){$DATA['saiu73idcentro']=0;}
		if ($DATA['saiu73idescuela']==''){$DATA['saiu73idescuela']=0;}
		if ($DATA['saiu73idprograma']==''){$DATA['saiu73idprograma']=0;}
		if ($DATA['saiu73idperiodo']==''){$DATA['saiu73idperiodo']=0;}
	}
	//Valiaciones de campos obligatorios en todo guardar.
	if ($DATA['saiu73tiporadicado']==''){$sError=$ERR['saiu73tiporadicado'];}
	if ($DATA['saiu73mes']==''){$sError=$ERR['saiu73mes'];}
	if ($DATA['saiu73agno']==''){$sError=$ERR['saiu73agno'];}
	// -- Tiene un cerrado.
	$iDiaIni=fecha_ArmarNumero($DATA['saiu73dia'],$DATA['saiu73mes'],$DATA['saiu73agno']);
	if ($bConCierre && $sError==''){
		//Validaciones previas a cerrar
		if ($DATA['saiu73estado']==7){
			switch($DATA['saiu73solucion']){
				case 1: // Resuelto en la atención
				case 5: // Se inicia PQRS
				list($DATA['saiu73tiemprespdias'], $DATA['saiu73tiempresphoras'], $DATA['saiu73tiemprespminutos'])=Tiempo_MinutosCalendario($iDiaIni, $DATA['saiu73hora'], $DATA['saiu73minuto'], $DATA['saiu73fechafin'], $DATA['saiu73horafin'], $DATA['saiu73minutofin']);
				break;
			}
		}
		//Aprobó las Validaciones al cerrar
		if ($sError.$sErrorCerrando!=''){			
			$DATA['saiu73estado'] = $DATA['saiu73estadoorigen'];
			$sErrorCerrando=$sError.' '.$sErrorCerrando;
		}else{
			$bCerrando=true;
			//Acciones del cierre
		}
	}
	// -- Fin del cerrado.
	// -- Se verifican los valores de campos de otras tablas.
	$sTabla21='saiu73solusuario_'.$DATA['saiu73agno'];
	if ($DATA['saiu73idresponsable_doc']!=''){
		if ($sError==''){$sError=tabla_terceros_existe($DATA['saiu73idresponsable_td'], $DATA['saiu73idresponsable_doc'], $objDB, 'El tercero Responsable ');}
		if ($sError==''){
			list($sError, $sInfo)=tercero_Bloqueado($DATA['saiu73idresponsable'], $objDB);
			if ($sInfo!=''){$sError=$sError.'<br>'.$sInfo;}
			}
		}
	if ($DATA['saiu73idsolicitante_doc']!=''){
		if ($sError==''){$sError=tabla_terceros_existe($DATA['saiu73idsolicitante_td'], $DATA['saiu73idsolicitante_doc'], $objDB, 'El tercero Solicitante ');}
		if ($sError==''){
			list($sError, $sInfo)=tercero_Bloqueado($DATA['saiu73idsolicitante'], $objDB);
			if ($sInfo!=''){$sError=$sError.'<br>'.$sInfo;}
			}
		}
	if ($sError == '') {
		list($sErrorR, $sDebugR) = f3073_RevTabla_saiu73solusuario($DATA['saiu73agno'], $objDB);
		$sError = $sError . $sErrorR;
	}
	$bQuitarCodigo=false;
	$sCampoCodigo='';
	if ($sError==''){
		if ($DATA['paso']==10){
			if ($DATA['saiu73consec']==''){
				$DATA['saiu73consec']=tabla_consecutivo($sTabla21, 'saiu73consec', 'saiu73agno='.$DATA['saiu73agno'].' AND saiu73mes='.$DATA['saiu73mes'].' AND saiu73tiporadicado='.$DATA['saiu73tiporadicado'].'', $objDB);
				if ($DATA['saiu73consec']==-1){$sError=$objDB->serror;}
				$bQuitarCodigo=true;
				$sCampoCodigo='saiu73consec';
				}else{
				if (!seg_revisa_permiso($iCodModulo, 8, $objDB)){
					$sError=$ERR['8'];
					$DATA['saiu73consec']='';
					}
				}
			if ($sError==''){
				$sSQL='SELECT 1 FROM '.$sTabla21.' WHERE saiu73agno='.$DATA['saiu73agno'].' AND saiu73mes='.$DATA['saiu73mes'].' AND saiu73tiporadicado='.$DATA['saiu73tiporadicado'].' AND saiu73consec='.$DATA['saiu73consec'].'';
				$result=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($result)!=0){
					$sError=$ERR['existe'];
					}else{
						if ($DATA['bcampus'] == 0) {
							if (!seg_revisa_permiso($iCodModulo, 2, $objDB)){$sError=$ERR['2'];}
						}
					}
				}
			}else{
				if ($DATA['bcampus'] == 0) {
					if (!seg_revisa_permiso($iCodModulo, 3, $objDB)){$sError=$ERR['3'];}
				}
			}
		}
	if ($sError==''){
		if ($DATA['paso']==10){
			//Preparar el Id, Si no lo hay se quita la comprobación.
			$DATA['saiu73id']=tabla_consecutivo($sTabla21,'saiu73id', '', $objDB);
			if ($DATA['saiu73id']==-1){$sError=$objDB->serror;}
			}
		}
	if ($sError==''){
		//Encontrar la clase
		$sSQL='SELECT saiu02clasesol FROM saiu02tiposol WHERE saiu02id='.$DATA['saiu73tiposolicitud'].'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$fila=$objDB->sf($tabla);
			$DATA['saiu73clasesolicitud']=$fila['saiu02clasesol'];
			}
		}
	$idSolicitantePrevio=0;
	if ($sError==''){
		$DATA['saiu73detalle']=stripslashes($DATA['saiu73detalle']);
		//Si el campo saiu73detalle permite html quite la linea htmlspecialchars para el campo y habilite la siguiente linea:
		//$saiu73detalle=addslashes($DATA['saiu73detalle']);
		$saiu73detalle=str_replace('"', '\"', $DATA['saiu73detalle']);
		$DATA['saiu73respuesta']=stripslashes($DATA['saiu73respuesta']);
		//$saiu73respuesta=addslashes($DATA['saiu73respuesta']);
		$saiu73respuesta=str_replace('"', '\"', $DATA['saiu73respuesta']);
		$bpasa=false;
		if ($DATA['paso']==10){
			$DATA['saiu73idpqrs']=0;
			$DATA['saiu73tiemprespdias']=0;
			$DATA['saiu73tiempresphoras']=0;
			$DATA['saiu73tiemprespminutos']=0;
			$DATA['saiu73agno']=fecha_agno();
			$DATA['saiu73mes']=fecha_mes();
			$DATA['saiu73dia']=fecha_dia();
			$DATA['saiu73hora']=fecha_hora();
			$DATA['saiu73minuto']=fecha_minuto();
			$DATA['saiu73idcaso']=0;
			$sCampos3073='saiu73agno, saiu73mes, saiu73tiporadicado, saiu73consec, saiu73id, 
			saiu73dia, saiu73hora, saiu73minuto, saiu73estado, saiu73idsolicitante, 
			saiu73tipointeresado, saiu73clasesolicitud, saiu73tiposolicitud, saiu73temasolicitud, saiu73idzona, 
			saiu73idcentro, saiu73codpais, saiu73coddepto, saiu73codciudad, saiu73idescuela, 
			saiu73idprograma, saiu73idperiodo, saiu73idpqrs, saiu73detalle, saiu73fechafin,
			saiu73horafin, saiu73minutofin, saiu73paramercadeo, saiu73idresponsable, saiu73tiemprespdias, 
			saiu73tiempresphoras, saiu73tiemprespminutos, saiu73solucion, saiu73idcaso, saiu73respuesta, 
			saiu73idcanal, saiu73idtelefono, saiu73numtelefono, saiu73numorigen, saiu73idchat, 
			saiu73numsesionchat, saiu73idcorreo, saiu73idcorreootro, saiu73correoorigen';
			$sValores3073=''.$DATA['saiu73agno'].', '.$DATA['saiu73mes'].', '.$DATA['saiu73tiporadicado'].', '.$DATA['saiu73consec'].', '.$DATA['saiu73id'].', 
			'.$DATA['saiu73dia'].', '.$DATA['saiu73hora'].', '.$DATA['saiu73minuto'].', '.$DATA['saiu73estado'].', '.$DATA['saiu73idsolicitante'].', 
			'.$DATA['saiu73tipointeresado'].', '.$DATA['saiu73clasesolicitud'].', '.$DATA['saiu73tiposolicitud'].', '.$DATA['saiu73temasolicitud'].', '.$DATA['saiu73idzona'].', 
			'.$DATA['saiu73idcentro'].', "'.$DATA['saiu73codpais'].'", "'.$DATA['saiu73coddepto'].'", "'.$DATA['saiu73codciudad'].'", '.$DATA['saiu73idescuela'].', 
			'.$DATA['saiu73idprograma'].', '.$DATA['saiu73idperiodo'].', '.$DATA['saiu73idpqrs'].', "'.$saiu73detalle.'", '.$DATA['saiu73fechafin'].', 
			'.$DATA['saiu73horafin'].', '.$DATA['saiu73minutofin'].', '.$DATA['saiu73paramercadeo'].', '.$DATA['saiu73idresponsable'].', '.$DATA['saiu73tiemprespdias'].', 
			'.$DATA['saiu73tiempresphoras'].', '.$DATA['saiu73tiemprespminutos'].', '.$DATA['saiu73solucion'].', '.$DATA['saiu73idcaso'].', "'.$saiu73respuesta.'",
			'.$DATA['saiu73idcanal'].', '.$DATA['saiu73idtelefono'].', "'.$DATA['saiu73numtelefono'].'", "'.$DATA['saiu73numorigen'].'", '.$DATA['saiu73idchat'].', 
			"'.$DATA['saiu73numsesionchat'].'", '.$DATA['saiu73idcorreo'].', "'.$DATA['saiu73idcorreootro'].'", "'.$DATA['saiu73correoorigen'].'"';
			if ($APP->utf8==1){
				$sSQL='INSERT INTO '.$sTabla21.' ('.$sCampos3073.') VALUES ('.cadena_codificar($sValores3073).');';
				$sdetalle=$sCampos3073.'['.cadena_codificar($sValores3073).']';
				}else{
				$sSQL='INSERT INTO '.$sTabla21.' ('.$sCampos3073.') VALUES ('.$sValores3073.');';
				$sdetalle=$sCampos3073.'['.$sValores3073.']';
				}
			$idAccion=2;
			$bpasa=true;
			}else{
			$scampo[1]='saiu73dia';
			$scampo[2]='saiu73hora';
			$scampo[3]='saiu73minuto';
			$scampo[4]='saiu73idsolicitante';
			$scampo[5]='saiu73tipointeresado';
			$scampo[6]='saiu73clasesolicitud';
			$scampo[7]='saiu73temasolicitud';
			$scampo[8]='saiu73idzona';
			$scampo[9]='saiu73idcentro';
			$scampo[10]='saiu73codpais';
			$scampo[11]='saiu73coddepto';
			$scampo[12]='saiu73codciudad';
			$scampo[13]='saiu73idescuela';
			$scampo[14]='saiu73idprograma';
			$scampo[15]='saiu73idperiodo';
			$scampo[16]='saiu73detalle';
			$scampo[17]='saiu73horafin';
			$scampo[18]='saiu73minutofin';
			$scampo[19]='saiu73paramercadeo';
			$scampo[20]='saiu73idresponsable';
			$scampo[21]='saiu73solucion';
			$scampo[22]='saiu73tiposolicitud';
			$scampo[23]='saiu73estado';
			$scampo[24]='saiu73tiemprespdias';
			$scampo[25]='saiu73tiempresphoras';
			$scampo[26]='saiu73tiemprespminutos';
			$scampo[27]='saiu73respuesta';
			$scampo[28]='saiu73idcaso';
			$scampo[29]='saiu73fecharespcaso';
			$scampo[30]='saiu73horarespcaso';
			$scampo[31]='saiu73minrespcaso';
			$scampo[32]='saiu73idunidadcaso';
			$scampo[33]='saiu73idequipocaso';
			$scampo[34]='saiu73idsupervisorcaso';
			$scampo[35]='saiu73idresponsablecaso';
			$scampo[36]='saiu73idpqrs';
			$scampo[37]='saiu73numref';
			$scampo[38]='saiu73fechafin';
			$scampo[39]='saiu73agno';
			$scampo[40]='saiu73mes';
			$scampo[41]='saiu73idcanal';
			$scampo[42]='saiu73idtelefono';
			$scampo[43]='saiu73numtelefono';
			$scampo[44]='saiu73numorigen';
			$scampo[45]='saiu73idchat';
			$scampo[46]='saiu73numsesionchat';
			$scampo[47]='saiu73idcorreo';
			$scampo[48]='saiu73idcorreootro';
			$scampo[49]='saiu73correoorigen';
			$sdato[1]=$DATA['saiu73dia'];
			$sdato[2]=$DATA['saiu73hora'];
			$sdato[3]=$DATA['saiu73minuto'];
			$sdato[4]=$DATA['saiu73idsolicitante'];
			$sdato[5]=$DATA['saiu73tipointeresado'];
			$sdato[6]=$DATA['saiu73clasesolicitud'];
			$sdato[7]=$DATA['saiu73temasolicitud'];
			$sdato[8]=$DATA['saiu73idzona'];
			$sdato[9]=$DATA['saiu73idcentro'];
			$sdato[10]=$DATA['saiu73codpais'];
			$sdato[11]=$DATA['saiu73coddepto'];
			$sdato[12]=$DATA['saiu73codciudad'];
			$sdato[13]=$DATA['saiu73idescuela'];
			$sdato[14]=$DATA['saiu73idprograma'];
			$sdato[15]=$DATA['saiu73idperiodo'];
			$sdato[16]=$saiu73detalle;
			$sdato[17]=$DATA['saiu73horafin'];
			$sdato[18]=$DATA['saiu73minutofin'];
			$sdato[19]=$DATA['saiu73paramercadeo'];
			$sdato[20]=$DATA['saiu73idresponsable'];
			$sdato[21]=$DATA['saiu73solucion'];
			$sdato[22]=$DATA['saiu73tiposolicitud'];
			$sdato[23]=$DATA['saiu73estado'];
			$sdato[24]=$DATA['saiu73tiemprespdias'];
			$sdato[25]=$DATA['saiu73tiempresphoras'];
			$sdato[26]=$DATA['saiu73tiemprespminutos'];
			$sdato[27]=$saiu73respuesta;
			$sdato[28]=$DATA['saiu73idcaso'];
			$sdato[29]=$DATA['saiu73fecharespcaso'];
			$sdato[30]=$DATA['saiu73horarespcaso'];
			$sdato[31]=$DATA['saiu73minrespcaso'];
			$sdato[32]=$DATA['saiu73idunidadcaso'];
			$sdato[33]=$DATA['saiu73idequipocaso'];
			$sdato[34]=$DATA['saiu73idsupervisorcaso'];
			$sdato[35]=$DATA['saiu73idresponsablecaso'];
			$sdato[36]=$DATA['saiu73idpqrs'];
			$sdato[37]=$DATA['saiu73numref'];
			$sdato[38]=$DATA['saiu73fechafin'];
			$sdato[39]=$DATA['saiu73agno'];
			$sdato[40]=$DATA['saiu73mes'];
			$sdato[41]=$DATA['saiu73idcanal'];
			$sdato[42]=$DATA['saiu73idtelefono'];
			$sdato[43]=$DATA['saiu73numtelefono'];
			$sdato[44]=$DATA['saiu73numorigen'];
			$sdato[45]=$DATA['saiu73idchat'];
			$sdato[46]=$DATA['saiu73numsesionchat'];
			$sdato[47]=$DATA['saiu73idcorreo'];
			$sdato[48]=$DATA['saiu73idcorreootro'];
			$sdato[49]=$DATA['saiu73correoorigen'];
			$numcmod=49;
			$sWhere='saiu73id='.$DATA['saiu73id'].'';
			$sSQL='SELECT * FROM '.$sTabla21.' WHERE '.$sWhere;
			$sdatos='';
			$bPrimera=true;
			$result=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($result)>0){
				$filabase=$objDB->sf($result);
				if ($DATA['saiu73idsolicitante']!=$filabase['saiu73idsolicitante']){
					$idSolicitantePrevio=$filabase['saiu73idsolicitante'];
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
			if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Guardar 3073 '.$sSQL.'<br>';}
			$result=$objDB->ejecutasql($sSQL);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' [3073] ..<!-- '.$sSQL.' -->';
				if ($idAccion==2){
					$DATA['saiu73id']='';
					$bQuitarCodigo=true;
					$DATA['paso']=0;
					}else{
					$DATA['paso']=2;
					}
				$bCerrando=false;
				}else{
				if ($bAudita[$idAccion]){seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], $idAccion, $DATA['saiu73id'], $sdetalle, $objDB);}
				$DATA['paso']=2;
				//Registrar en el inventario.
				$valores3000[2]=$iCodModulo;
				$valores3000[3]=$DATA['saiu73agno'];
				$valores3000[4]=$DATA['saiu73id'];
				if ($idSolicitantePrevio!=0){
					//Retirar al anterior.
					$valores3000[1]=$idSolicitantePrevio;
					f3000_Retirar($valores3000, $objDB, $bDebug);
					}
				if ($DATA['saiu73idsolicitante']!=0){
					$valores3000[1]=$DATA['saiu73idsolicitante'];
					$valores3000[5]=$iDiaIni;
					$valores3000[6]=$DATA['saiu73tiposolicitud'];
					$valores3000[7]=$DATA['saiu73temasolicitud'];
					$valores3000[8]=$DATA['saiu73estado'];
					f3000_Registrar($valores3000, $objDB, $bDebug);
					}
				if ($bEnviaCaso) {
					// Notifica responsable
					// list($sMensaje, $sErrorE, $sDebugE) = f3000_EnviaCorreosAtencion($DATA, $DATA['saiu73agno'], $objDB, $bDebug, true);
					// $sError = $sError . $sErrorE;
					// $sDebug = $sDebug . $sDebugE;
					if ($DATA['bcampus']==1) {
						// Notifica usuario
						list($sMensaje, $sErrorE, $sDebugE) = f3000_EnviaCorreosAtencion($DATA, $DATA['saiu73agno'], $objDB, $bDebug);
						$sError = $sError . $sErrorE;
						$sDebug = $sDebug . $sDebugE;
						}
					}
				if ($bEnviaEncuesta) {
					list($sMensaje, $sErrorE, $sDebugE) = f3000_EnviaCorreosAtencion($DATA, $DATA['saiu73agno'], $objDB, $bDebug);
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
		list($sErrorCerrando, $sDebugCerrar)=f3073_Cerrar($DATA['saiu73id'], $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugCerrar;
		}
	//if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' InfoDepura<br>';}
	return array($DATA, $sError, $iTipoError, $bCerrando, $sErrorCerrando, $sDebug);
	}
function f3073_db_Eliminar($saiu73agno, $saiu73id, $objDB, $bDebug=false){
	$iCodModulo=3073;
	$bAudita[4]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3073=$APP->rutacomun.'lg/lg_3073_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3073)){$mensajes_3073=$APP->rutacomun.'lg/lg_3073_es.php';}
	require $mensajes_todas;
	require $mensajes_3073;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	$saiu73id=numeros_validar($saiu73id);
	// Traer los datos para hacer las validaciones.
	$sTabla21='saiu73solusuario_'.$saiu73agno;
	if ($sError==''){
		$sSQL='SELECT * FROM '.$sTabla21.' WHERE saiu73id='.$saiu73id.'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$filabase=$objDB->sf($tabla);
			}else{
			$sError='No se encuentra el registro solicitado {Ref: '.$saiu73id.'}';
			}
		}
	if ($sError==''){
		if (!seg_revisa_permiso($iCodModulo, 4, $objDB)){
			$sError=$ERR['4'];
			}
		}
	if ($sError==''){
		$sSQL='SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=3073';
		$tablaor=$objDB->ejecutasql($sSQL);
		while ($filaor=$objDB->sf($tablaor)){
			$sSQL='SELECT '.$filaor['campoid'].' FROM '.$filaor['tabla'].' WHERE '.$filaor['camporev'].'='.$_REQUEST['saiu73id'].' LIMIT 0, 1';
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
		if ($filabase['saiu73idsolicitante']!=0){
			//Retirar al anterior.
			$valores3000[1]=$filabase['saiu73idsolicitante'];
			$valores3000[2]=$iCodModulo;
			$valores3000[3]=$filabase['saiu73agno'];
			$valores3000[4]=$filabase['saiu73id'];
			f3000_Retirar($valores3000, $objDB, $bDebug);
			}
		$sWhere='saiu73id='.$saiu73id.'';
		//$sWhere='saiu73consec='.$filabase['saiu73consec'].' AND saiu73tiporadicado='.$filabase['saiu73tiporadicado'].' AND saiu73mes='.$filabase['saiu73mes'].' AND saiu73agno='.$filabase['saiu73agno'].'';
		$sSQL='DELETE FROM '.$sTabla21.' WHERE '.$sWhere.';';
		$result=$objDB->ejecutasql($sSQL);
		if ($result==false){
			$sError=$ERR['falla_eliminar'].' .. <!-- '.$sSQL.' -->';
			}else{
			if ($bAudita[4]){seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $saiu73id, $sWhere, $objDB);}
			}
		}
	return array($sError, $iTipoError, $sDebug);
	}
function f3073_TituloBusqueda(){
	return 'B&uacute;squeda de Registro de Atenciones';
	}
function f3073_ParametrosBusqueda(){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3073=$APP->rutacomun.'lg/lg_3073_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3073)){$mensajes_3073=$APP->rutacomun.'lg/lg_3073_es.php';}
	require $mensajes_todas;
	require $mensajes_3073;
	$sParams='<label class="Label90">
	'.$ETI['msg_bnombre'].'
	</label>
	<label>
	<input id="b3073nombre" name="b3073nombre" type="text" value="" onchange="paginarbusqueda()" />
	</label>';
	return $sParams;
	}
function f3073_JavaScriptBusqueda($iModuloBusca){
	$sRes='var sCampo=window.document.frmedita.scampobusca.value;
	var params=new Array();
	params[100]=sCampo;
	params[101]=window.document.frmedita.paginabusqueda.value;
	params[102]=window.document.frmedita.lppfbusqueda.value;
	params[103]=window.document.frmedita.b3073nombre.value;
	xajax_f'.$iModuloBusca.'_HtmlBusqueda(params);';
	return $sRes;
	}
function f3073_TablaDetalleBusquedas($aParametros, $objDB){
	$res='';
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3073=$APP->rutacomun.'lg/lg_3073_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3073)){$mensajes_3073=$APP->rutacomun.'lg/lg_3073_es.php';}
	require $mensajes_todas;
	require $mensajes_3073;
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
		return array($sLeyenda.'<input id="paginaf3073" name="paginaf3073" type="hidden" value="'.$pagina.'"/><input id="lppf3073" name="lppf3073" type="hidden" value="'.$lineastabla.'"/>', $sDebug);
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
	$sSQL='SELECT TB.saiu73agno, TB.saiu73mes, T3.saiu16nombre, TB.saiu73consec, TB.saiu73id, TB.saiu73dia, TB.saiu73hora, TB.saiu73minuto, T9.saiu11nombre, T10.saiu22nombre, T12.unad11razonsocial AS C12_nombre, T13.bita07nombre, T14.saiu01titulo, T15.saiu02titulo, T16.saiu03titulo, T17.unad23nombre, T18.unad24nombre, T19.unad18nombre, T20.unad19nombre, T21.unad20nombre, T22.core12nombre, T23.core09nombre, T24.exte02nombre, TB.saiu73idpqrs, TB.saiu73detalle, TB.saiu73horafin, TB.saiu73minutofin, TB.saiu73paramercadeo, T31.unad11razonsocial AS C31_nombre, TB.saiu73tiemprespdias, TB.saiu73tiempresphoras, TB.saiu73tiemprespminutos, TB.saiu73tiporadicado, TB.saiu73estado, TB.saiu73idsolicitante, T12.unad11tipodoc AS C12_td, T12.unad11doc AS C12_doc, TB.saiu73tipointeresado, TB.saiu73clasesolicitud, TB.saiu73tiposolicitud, TB.saiu73temasolicitud, TB.saiu73idzona, TB.saiu73idcentro, TB.saiu73codpais, TB.saiu73coddepto, TB.saiu73codciudad, TB.saiu73idescuela, TB.saiu73idprograma, TB.saiu73idperiodo, TB.saiu73idresponsable, T31.unad11tipodoc AS C31_td, T31.unad11doc AS C31_doc, TB.saiu73idcanal
FROM saiu73solusuario AS TB, saiu16tiporadicado AS T3, saiu11estadosol AS T9, saiu22telefonos AS T10, unad11terceros AS T12, bita07tiposolicitante AS T13, saiu01claseser AS T14, saiu02tiposol AS T15, saiu03temasol AS T16, unad23zona AS T17, unad24sede AS T18, unad18pais AS T19, unad19depto AS T20, unad20ciudad AS T21, core12escuela AS T22, core09programa AS T23, exte02per_aca AS T24, unad11terceros AS T31 
WHERE '.$sSQLadd1.' TB.saiu73tiporadicado=T3.saiu16id AND TB.saiu73estado=T9.saiu11id AND TB.saiu73idsolicitante=T12.unad11id AND TB.saiu73tipointeresado=T13.bita07id AND TB.saiu73clasesolicitud=T14.saiu01id AND TB.saiu73tiposolicitud=T15.saiu02id AND TB.saiu73temasolicitud=T16.saiu03id AND TB.saiu73idzona=T17.unad23id AND TB.saiu73idcentro=T18.unad24id AND TB.saiu73codpais=T19.unad18codigo AND TB.saiu73coddepto=T20.unad19codigo AND TB.saiu73codciudad=T21.unad20codigo AND TB.saiu73idescuela=T22.core12id AND TB.saiu73idprograma=T23.core09id AND TB.saiu73idperiodo=T24.exte02id AND TB.saiu73idresponsable=T31.unad11id '.$sSQLadd.'
ORDER BY TB.saiu73agno, TB.saiu73mes, TB.saiu73tiporadicado, TB.saiu73consec';
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
			//return array(cadena_codificar($sErrConsulta.'<input id="paginaf3073" name="paginaf3073" type="hidden" value="'.$pagina.'"/><input id="lppf3073" name="lppf3073" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
<td><b>'.$ETI['saiu73agno'].'</b></td>
<td><b>'.$ETI['saiu73mes'].'</b></td>
<td><b>'.$ETI['saiu73tiporadicado'].'</b></td>
<td><b>'.$ETI['saiu73consec'].'</b></td>
<td><b>'.$ETI['saiu73dia'].'</b></td>
<td><b>'.$ETI['saiu73hora'].'</b></td>
<td><b>'.$ETI['saiu73estado'].'</b></td>
<td colspan="2"><b>'.$ETI['saiu73idsolicitante'].'</b></td>
<td><b>'.$ETI['saiu73tipointeresado'].'</b></td>
<td><b>'.$ETI['saiu73clasesolicitud'].'</b></td>
<td><b>'.$ETI['saiu73tiposolicitud'].'</b></td>
<td><b>'.$ETI['saiu73temasolicitud'].'</b></td>
<td><b>'.$ETI['saiu73idzona'].'</b></td>
<td><b>'.$ETI['saiu73idcentro'].'</b></td>
<td><b>'.$ETI['saiu73codpais'].'</b></td>
<td><b>'.$ETI['saiu73coddepto'].'</b></td>
<td><b>'.$ETI['saiu73codciudad'].'</b></td>
<td><b>'.$ETI['saiu73idescuela'].'</b></td>
<td><b>'.$ETI['saiu73idprograma'].'</b></td>
<td><b>'.$ETI['saiu73idperiodo'].'</b></td>
<td><b>'.$ETI['saiu73idpqrs'].'</b></td>
<td><b>'.$ETI['saiu73detalle'].'</b></td>
<td><b>'.$ETI['saiu73horafin'].'</b></td>
<td><b>'.$ETI['saiu73paramercadeo'].'</b></td>
<td colspan="2"><b>'.$ETI['saiu73idresponsable'].'</b></td>
<td><b>'.$ETI['saiu73tiemprespdias'].'</b></td>
<td><b>'.$ETI['saiu73tiempresphoras'].'</b></td>
<td><b>'.$ETI['saiu73tiemprespminutos'].'</b></td>
<td align="right">
'.html_paginador('paginabusqueda', $registros, $lineastabla, $pagina, 'paginarbusqueda()').'
'.html_lpp('lppfbusqueda', $lineastabla, 'paginarbusqueda()').'
</td>
</tr></thead>';
	$tlinea=1;
	while($filadet=$objDB->sf($tabladetalle)){
		$sPrefijo='<a href="javascript:Devuelve(\''.$filadet['saiu73id'].'\');">';
		$sSufijo='</a>';
		$tlinea++;
		$et_saiu73hora=html_TablaHoraMin($filadet['saiu73hora'], $filadet['saiu73minuto']);
		$et_saiu73horafin=html_TablaHoraMin($filadet['saiu73horafin'], $filadet['saiu73minutofin']);
		$res=$res.'<tr onmouseover="cambia_color_over(this);" onmouseout="cambia_color_out(this);">
<td>'.$sPrefijo.$filadet['saiu73agno'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu73mes'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['saiu16nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu73consec'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu73dia'].$sSufijo.'</td>
<td>'.$sPrefijo.$et_saiu73hora.$sSufijo.'</td>
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
<td>'.$sPrefijo.$filadet['saiu73codpais'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu73coddepto'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu73codciudad'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['core12nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['core09nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['exte02nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu73idpqrs'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu73detalle'].$sSufijo.'</td>
<td>'.$sPrefijo.$et_saiu73horafin.$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu73paramercadeo'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['C31_td'].' '.$filadet['C31_doc'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['C31_nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu73tiemprespdias'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu73tiempresphoras'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu73tiemprespminutos'].$sSufijo.'</td>
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
function f3073_RevTabla_saiu73solusuario($sContenedor, $objDB, $bDebug = false)
{
	list($sError, $sDebug) = f3073_RevisarTabla($sContenedor, $objDB, $bDebug);
	return array($sError, $sDebug);
}
function f3073_ConsultaResponsable($DATA, $objDB, $bDebug = false)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_3073 = $APP->rutacomun.'lg/lg_3073_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_3073)) {
		$mensajes_3073 = $APP->rutacomun.'lg/lg_3073_es.php';
	}
	require $mensajes_todas;
	require $mensajes_3073;
	$sError = '';
	$iTipoError = 0;
	$sDebug = '';
	$saiu73idunidadcaso = 0;
	$saiu73idequipocaso = 0;
	$saiu73idsupervisorcaso = 0;
	$saiu73idresponsablecaso = 0;
	$saiu73tiemprespdias = 0;
	$saiu73tiempresphoras = 0;
	if (isset($DATA['saiu73temasolicitud']) == 0) {
		$DATA['saiu73temasolicitud'] = '';
	}
	if ($DATA['saiu73temasolicitud'] == '') {
		$sError = $ERR['saiu73temasolicitud'] . $sSepara . $sError;
	}
	if ($sError == '') {
		$sSQL = 'SELECT saiu03idunidadresp1, saiu03idequiporesp1, saiu03idliderrespon1, saiu03tiemprespdias1, saiu03tiempresphoras1
		FROM saiu03temasol
		WHERE saiu03id = ' . $DATA['saiu73temasolicitud'] . '';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' Consulta responsable solicitud ' . $sSQL . '<br>';
		}
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			$saiu73idunidadcaso = $fila['saiu03idunidadresp1'];
			$saiu73idequipocaso = $fila['saiu03idequiporesp1'];
			$saiu73idsupervisorcaso = $fila['saiu03idliderrespon1'];
			$saiu73idresponsablecaso = $saiu73idsupervisorcaso;
			$saiu73tiemprespdias = $fila['saiu03tiemprespdias1'];
			$saiu73tiempresphoras = $fila['saiu03tiempresphoras1'];
		} else {
			$sError = $sError . 'No se ha configurado el tema de solicitud.';
		}
	}
	return array($saiu73idunidadcaso, $saiu73idequipocaso, $saiu73idsupervisorcaso, $saiu73idresponsablecaso, $saiu73tiemprespdias, $saiu73tiempresphoras, $sError, $iTipoError, $sDebug);
}
function f3073_ActualizarAtiende($DATA, $objDB, $bDebug = false, $idTercero)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_3073 = $APP->rutacomun.'lg/lg_3073_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_3073)) {
		$mensajes_3073 = $APP->rutacomun.'lg/lg_3073_es.php';
	}
	require $mensajes_todas;
	require $mensajes_3073;
	$sTabla21 = 'saiu73solusuario_' . $DATA['saiu73agno'];
	$sResultado = '';
	$sError = '';
	$sDebug = '';
	$iTipoError = 0;
	if (!$objDB->bexistetabla($sTabla21)) {
		$sError = $sError . 'No ha sido posible acceder al contenedor de datos';
	}
	if ($sError == '') {
		$sSQL = 'SELECT saiu73agno, saiu73mes, saiu73estado, saiu73temasolicitud FROM ' . $sTabla21 . ' WHERE saiu73id=' . $DATA['saiu73id'] . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			list($DATA['saiu73idunidadcaso'], $DATA['saiu73idequipocaso'], $DATA['saiu73idsupervisorcaso'], $DATA['saiu73idresponsablecaso'], $saiu73tiemprespdias, $saiu73tiempresphoras, $sErrorF, $iTipoError, $sDebugF) = f3073_ConsultaResponsable($fila, $objDB, $bDebug);
			$sError = $sError . $sErrorF;
			if ($bDebug) {
				$sDebug = $sDebug . $sDebugF;
			}
		} else {
			$sError = $sError . $ETI['saiu73noexiste'];
		}
	}
	if ($sError == '') {
		if ($saiu73tiemprespdias > 0) { // Cálculo fecha probable de respuesta
			$iFechaBase = fecha_DiaMod();
			$saiu73fecharespprob = fecha_NumSumarDias($iFechaBase, $saiu73tiemprespdias);
		}
		list($DATA, $sErrorE, $iTipoError, $sDebugGuardar) = f3073_db_GuardarV2($DATA, $objDB, $bDebug, $idTercero);
		$sError = $sError . $sErrorE;
		$sDebug = $sDebug . $sDebugGuardar;
		if ($sError == '') {
			$sError = '<b>' . $ETI['msg_itemguardado'] . '</b>';
			$iTipoError = 1;
		}
	}
	return array($DATA, $sError, $iTipoError, $sDebug);
}
function elimina_archivo_saiu73idarchivo($idpadre, $iAgno)
{
	$_SESSION['u_ultimominuto'] = iminutoavance();
	require './app.php';
	$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto != '') {
		$objDB->dbPuerto = $APP->dbpuerto;
	}
	$objDB->xajax();
	$sTabla21 = 'saiu73solusuario_' . $iAgno;
	archivo_eliminar($sTabla21, 'saiu73id', 'saiu73idorigen', 'saiu73idarchivo', $idpadre, $objDB);
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->call("limpia_saiu73idarchivo");
	return $objResponse;
}
function elimina_archivo_saiu73idarchivorta($idpadre, $iAgno)
{
	$_SESSION['u_ultimominuto'] = iminutoavance();
	require './app.php';
	$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto != '') {
		$objDB->dbPuerto = $APP->dbpuerto;
	}
	$objDB->xajax();
	$sTabla21 = 'saiu73solusuario_' . $iAgno;
	archivo_eliminar($sTabla21, 'saiu73id', 'saiu73idorigenrta', 'saiu73idarchivorta', $idpadre, $objDB);
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->call("limpia_saiu73idarchivorta");
	return $objResponse;
}
function f3073_HTMLComboV2_btema($objDB, $objCombos, $valor, $vrbtipo)
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
		$objCombos->sAccion = 'paginarf3073()';
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
function f3073_Combobtema($aParametros)
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
	$html_btema = f3073_HTMLComboV2_btema($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_btema', 'innerHTML', $html_btema);
	$objResponse->call('jQuery("#btema").chosen({no_results_text: "No existen coincidencias: ",width: "100%"})');
	$objResponse->call('paginarf3073');
	return $objResponse;
}
function f3073_HTMLComboV2_bcead($objDB, $objCombos, $valor, $idzona)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$sCondi = 'unad24idzona="' . $idzona . '"';
	if ($sCondi != '') {
		$sCondi = ' WHERE ' . $sCondi;
	}
	$objCombos->nuevo('bcead', $valor, true, '{' . $ETI['msg_todos'] . '}');
	$objCombos->sAccion = 'paginarf3073()';
	$res = $objCombos->html('SELECT unad24id AS id, unad24nombre AS nombre FROM unad24sede' . $sCondi, $objDB);
	return $res;
}
function f3073_Combobcead($aParametros)
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
	$objCombos = new clsHtmlCombos();
	$html_bcead = f3073_HTMLComboV2_bcead($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_bcead', 'innerHTML', $html_bcead);
	$objResponse->call('paginarf3073');
	return $objResponse;
}