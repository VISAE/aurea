<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2020 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.25.4 domingo, 19 de julio de 2020
--- Modelo Versión 2.25.5 jueves, 13 de agosto de 2020
--- 3028 saiu28mesaayuda
*/
/** Archivo lib3028.php.
* Libreria 3028 saiu28mesaayuda.
* @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
* @date domingo, 19 de julio de 2020
*/
function f3028_HTMLComboV2_saiu28agno($objDB, $objCombos, $valor){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('saiu28agno', $valor, false, '{'.$ETI['msg_seleccione'].'}');
	//$objCombos->iAncho=450;
	$objCombos->sAccion='RevisaLlave();';
	$sSQL='SHOW TABLES LIKE "saiu28mesaayuda%"';
	//if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Total Periodo: Lista de contenedores: '.$sSQL.'<br>';}
	$tablac=$objDB->ejecutasql($sSQL);
	while($filac=$objDB->sf($tablac)){
		$sAgno=substr($filac[0], 16);
		$objCombos->addItem($sAgno, $sAgno);
		}
	$res=$objCombos->html('', $objDB);
	return $res;
	}
function f3028_HTMLComboV2_saiu28mes($objDB, $objCombos, $valor){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	/*
	$objCombos->nuevo('saiu28mes', $valor, false, '{'.$ETI['msg_seleccione'].'}');
	//$objCombos->iAncho=450;
	$objCombos->sAccion='RevisaLlave();';
	$sSQL='SELECT  AS id,  AS nombre FROM ';
	$res=$objCombos->html($sSQL, $objDB);
	*/
	$res=html_ComboMes('saiu28mes', $valor, false, 'RevisaLlave();');
	return $res;
	}
function f3028_HTMLComboV2_saiu28tiporadicado($objDB, $objCombos, $valor){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('saiu28tiporadicado', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	//$objCombos->iAncho=450;
	$objCombos->sAccion='RevisaLlave();';
	$sSQL='SELECT saiu16id AS id, saiu16nombre AS nombre FROM saiu16tiporadicado';
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f3028_HTMLComboV2_saiu28temasolicitud($objDB, $objCombos, $valor, $vrsaiu28tiposolicitud){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('saiu28temasolicitud', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	$objCombos->sAccion='limpiarresponsable()';
	//$objCombos->iAncho=450;
	if ((int)$vrsaiu28tiposolicitud==0){
		$objCombos->sAccion='carga_combo_saiu28tiposolicitud()';
		$sSQL='SELECT TB.saiu03id AS id, CONCAT(TB.saiu03titulo, " [", T2.saiu02titulo, "]") AS nombre 
		FROM saiu03temasol AS TB, saiu02tiposol AS T2 
		WHERE TB.saiu03id>0 AND TB.saiu03ordensoporte<9 AND TB.saiu03tiposol=T2.saiu02id
		ORDER BY TB.saiu03ordensoporte, TB.saiu03titulo';
		}else{
		$sSQL='SELECT saiu03id AS id, saiu03titulo AS nombre 
		FROM saiu03temasol 
		WHERE saiu03tiposol='.(int)$vrsaiu28tiposolicitud.' AND saiu03id>0 AND saiu03ordensoporte<9 
		ORDER BY saiu03ordensoporte, saiu03titulo';
		}
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f3028_HTMLComboV2_saiu28tiposolicitud($objDB, $objCombos, $valor){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('saiu28tiposolicitud', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	//$objCombos->iAncho=450;
	$objCombos->sAccion='carga_combo_saiu28temasolicitud();';
	$sSQL='SELECT TB.saiu02id AS id, CONCAT(TB.saiu02titulo, " [", T1.saiu01titulo, "]") AS nombre 
	FROM saiu02tiposol AS TB, saiu01claseser AS T1 
	WHERE TB.saiu02id>0 AND TB.saiu02ordensoporte<9 AND TB.saiu02clasesol=T1.saiu01id 
	ORDER BY TB.saiu02ordensoporte, TB.saiu02titulo';
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f3028_HTMLComboV2_saiu28idcentro($objDB, $objCombos, $valor, $vrsaiu28idzona){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$sCondi='unad24idzona="'.$vrsaiu28idzona.'"';
	$objCombos->nuevo('saiu28idcentro', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	//$objCombos->iAncho=450;
	$sSQL='SELECT unad24id AS id, unad24nombre AS nombre FROM unad24sede WHERE '.$sCondi.' AND unad24id>0';
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f3028_HTMLComboV2_saiu28coddepto($objDB, $objCombos, $valor, $vrsaiu28codpais){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	//@@ Se debe arreglar la condicion..
	$sCondi='unad19codpais="'.$vrsaiu28codpais.'"';
	if ($sCondi!=''){$sCondi=' WHERE '.$sCondi;}
	$objCombos->nuevo('saiu28coddepto', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	//$objCombos->iAncho=450;
	$objCombos->sAccion='carga_combo_saiu28codciudad()';
	$sSQL='SELECT unad19codigo AS id, unad19nombre AS nombre FROM unad19depto'.$sCondi;
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f3028_HTMLComboV2_saiu28codciudad($objDB, $objCombos, $valor, $vrsaiu28coddepto){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	//@@ Se debe arreglar la condicion..
	$sCondi='unad20coddepto="'.$vrsaiu28coddepto.'"';
	if ($sCondi!=''){$sCondi=' WHERE '.$sCondi;}
	$objCombos->nuevo('saiu28codciudad', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	//$objCombos->iAncho=450;
	$sSQL='SELECT unad20codigo AS id, unad20nombre AS nombre FROM unad20ciudad'.$sCondi;
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f3028_HTMLComboV2_saiu28idprograma($objDB, $objCombos, $valor, $vrsaiu28idescuela){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('saiu28idprograma', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	$objCombos->addItem('0', $ETI['msg_na']);
	//$objCombos->iAncho=450;
	$sCondiEscuela=' AND TB.core09idescuela="'.$vrsaiu28idescuela.'"';
	$sTabla2='';
	$sCampos2='';
	if ($vrsaiu28idescuela==''){
		$sCondiEscuela=' AND TB.core09idescuela=T12.core12id';
		$sTabla2=', core12escuela AS T12';
		$sCampos2=', " [", T12.core12sigla, "]"';
		}
	$sSQL='SELECT TB.core09id AS id, CONCAT(TB.core09nombre, " [", TB.core09codigo, "]"'.$sCampos2.') AS nombre FROM core09programa AS TB'.$sTabla2.' WHERE TB.core09id>0'.$sCondiEscuela;
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f3028_Combosaiu28tiposolicitud($aParametros){
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
		$html_saiu28tiposolicitud=f3028_HTMLComboV2_saiu28tiposolicitud($objDB, $objCombos, $idTipo);
		$html_saiu28temasolicitud=f3028_HTMLComboV2_saiu28temasolicitud($objDB, $objCombos, $idTema, $idTipo);
		$objDB->CerrarConexion();
		$objResponse=new xajaxResponse();
		$objResponse->assign('div_saiu28tiposolicitud', 'innerHTML', $html_saiu28tiposolicitud);
		$objResponse->assign('div_saiu28temasolicitud', 'innerHTML', $html_saiu28temasolicitud);
		$objResponse->call('$("#saiu28tiposolicitud").chosen()');
		$objResponse->call('$("#saiu28temasolicitud").chosen()');
		return $objResponse;
		}else{
		$objDB->CerrarConexion();
		}
	}
function f3028_Combosaiu28temasolicitud($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$objCombos=new clsHtmlCombos();
	$html_saiu28temasolicitud=f3028_HTMLComboV2_saiu28temasolicitud($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_saiu28temasolicitud', 'innerHTML', $html_saiu28temasolicitud);
	$objResponse->call('$("#saiu28temasolicitud").chosen()');
	return $objResponse;
	}
function f3028_Combosaiu28idcentro($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$objCombos=new clsHtmlCombos();
	$html_saiu28idcentro=f3028_HTMLComboV2_saiu28idcentro($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_saiu28idcentro', 'innerHTML', $html_saiu28idcentro);
	$objResponse->call('$("#saiu28idcentro").chosen()');
	return $objResponse;
	}
function f3028_Combosaiu28coddepto($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$objCombos=new clsHtmlCombos();
	$html_saiu28coddepto=f3028_HTMLComboV2_saiu28coddepto($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_saiu28coddepto', 'innerHTML', $html_saiu28coddepto);
	$objResponse->call('$("#saiu28coddepto").chosen()');
	return $objResponse;
	}
function f3028_Combosaiu28codciudad($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$objCombos=new clsHtmlCombos();
	$html_saiu28codciudad=f3028_HTMLComboV2_saiu28codciudad($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_saiu28codciudad', 'innerHTML', $html_saiu28codciudad);
	$objResponse->call('$("#saiu28codciudad").chosen()');
	return $objResponse;
	}
function f3028_Combosaiu28idprograma($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$objCombos=new clsHtmlCombos();
	$html_saiu28idprograma=f3028_HTMLComboV2_saiu28idprograma($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_saiu28idprograma', 'innerHTML', $html_saiu28idprograma);
	$objResponse->call('$("#saiu28idprograma").chosen()');
	return $objResponse;
	}
function f3028_ExisteDato($datos){
	if(!is_array($datos)){$datos=json_decode(str_replace('\"','"',$datos),true);}
	$_SESSION['u_ultimominuto']=iminutoavance();
	$bHayLlave=true;
	$saiu28agno=numeros_validar($datos[1]);
	if ($saiu28agno==''){$bHayLlave=false;}
	$saiu28mes=numeros_validar($datos[2]);
	if ($saiu28mes==''){$bHayLlave=false;}
	$saiu28tiporadicado=numeros_validar($datos[3]);
	if ($saiu28tiporadicado==''){$bHayLlave=false;}
	$saiu28consec=numeros_validar($datos[4]);
	if ($saiu28consec==''){$bHayLlave=false;}
	if ($bHayLlave){
		require './app.php';
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		$sSQL='SELECT 1 FROM saiu28mesaayuda_'.$saiu28agno.' WHERE saiu28agno='.$saiu28agno.' AND saiu28mes='.$saiu28mes.' AND saiu28tiporadicado='.$saiu28tiporadicado.' AND saiu28consec='.$saiu28consec.'';
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
function f3028_Busquedas($aParametros){
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3028='lg/lg_3028_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3028)){$mensajes_3028='lg/lg_3028_es.php';}
	require $mensajes_todas;
	require $mensajes_3028;
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
		case 'saiu28idsolicitante':
		require $APP->rutacomun.'lib111.php';
		$sTabla=f111_TablaDetalleBusquedas($aParametrosB, $objDB);
		$sTitulo='Busqueda de terceros';
		$sParams=f111_ParametrosBusqueda();
		$sJavaBusqueda=f111_JavaScriptBusqueda(3028);
		break;
		case 'saiu28idresponsable':
		require $APP->rutacomun.'lib111.php';
		$sTabla=f111_TablaDetalleBusquedas($aParametrosB, $objDB);
		$sTitulo='Busqueda de terceros';
		$sParams=f111_ParametrosBusqueda();
		$sJavaBusqueda=f111_JavaScriptBusqueda(3028);
		break;
		case 'saiu28idliderrespon1':
		require $APP->rutacomun.'lib111.php';
		$sTabla=f111_TablaDetalleBusquedas($aParametrosB, $objDB);
		$sTitulo='Busqueda de terceros';
		$sParams=f111_ParametrosBusqueda();
		$sJavaBusqueda=f111_JavaScriptBusqueda(3028);
		break;
		case 'saiu28idliderrespon2':
		require $APP->rutacomun.'lib111.php';
		$sTabla=f111_TablaDetalleBusquedas($aParametrosB, $objDB);
		$sTitulo='Busqueda de terceros';
		$sParams=f111_ParametrosBusqueda();
		$sJavaBusqueda=f111_JavaScriptBusqueda(3028);
		break;
		case 'saiu28idliderrespon3':
		require $APP->rutacomun.'lib111.php';
		$sTabla=f111_TablaDetalleBusquedas($aParametrosB, $objDB);
		$sTitulo='Busqueda de terceros';
		$sParams=f111_ParametrosBusqueda();
		$sJavaBusqueda=f111_JavaScriptBusqueda(3028);
		break;
		case 'saiu28idsupervisor':
		require $APP->rutacomun.'lib111.php';
		$sTabla=f111_TablaDetalleBusquedas($aParametrosB, $objDB);
		$sTitulo='Busqueda de terceros';
		$sParams=f111_ParametrosBusqueda();
		$sJavaBusqueda=f111_JavaScriptBusqueda(3028);
		break;
		case 'saiu30idusuario':
		require $APP->rutacomun.'lib111.php';
		$sTabla=f111_TablaDetalleBusquedas($aParametrosB, $objDB);
		$sTitulo='Busqueda de terceros';
		$sParams=f111_ParametrosBusqueda();
		$sJavaBusqueda=f111_JavaScriptBusqueda(3028);
		break;
		case 'saiu39idresponsable':
		require $APP->rutacomun.'lib111.php';
		$sTabla=f111_TablaDetalleBusquedas($aParametrosB, $objDB);
		$sTitulo='Busqueda de terceros';
		$sParams=f111_ParametrosBusqueda();
		$sJavaBusqueda=f111_JavaScriptBusqueda(3028);
		break;
		case 'saiu39usuario':
		require $APP->rutacomun.'lib111.php';
		$sTabla=f111_TablaDetalleBusquedas($aParametrosB, $objDB);
		$sTitulo='Busqueda de terceros';
		$sParams=f111_ParametrosBusqueda();
		$sJavaBusqueda=f111_JavaScriptBusqueda(3028);
		break;
		}
	$sTitulo='<h2>'.$ETI['titulo_3028'].' - '.$sTitulo.'</h2>';
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97titulo', 'innerHTML', $sTitulo);
	$objResponse->assign('div_97params', 'innerHTML', $sParams);
	$objResponse->assign('div_97tabla', 'innerHTML', $sTabla);
	$objResponse->setFunction('paginarbusqueda','',$sJavaBusqueda);
	$objResponse->call('expandesector(97)');
	return $objResponse;
	}
function f3028_HtmlBusqueda($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$sError='';
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$sDetalle='';
	switch($aParametros[100]){
		case 'saiu28idsolicitante':
		require $APP->rutacomun.'lib111.php';
		$sDetalle=f111_TablaDetalleBusquedas($aParametros, $objDB);
		break;
		case 'saiu28idresponsable':
		require $APP->rutacomun.'lib111.php';
		$sDetalle=f111_TablaDetalleBusquedas($aParametros, $objDB);
		break;
		case 'saiu28idliderrespon1':
		require $APP->rutacomun.'lib111.php';
		$sDetalle=f111_TablaDetalleBusquedas($aParametros, $objDB);
		break;
		case 'saiu28idliderrespon2':
		require $APP->rutacomun.'lib111.php';
		$sDetalle=f111_TablaDetalleBusquedas($aParametros, $objDB);
		break;
		case 'saiu28idliderrespon3':
		require $APP->rutacomun.'lib111.php';
		$sDetalle=f111_TablaDetalleBusquedas($aParametros, $objDB);
		break;
		case 'saiu28idsupervisor':
		require $APP->rutacomun.'lib111.php';
		$sDetalle=f111_TablaDetalleBusquedas($aParametros, $objDB);
		break;
		case 'saiu30idusuario':
		require $APP->rutacomun.'lib111.php';
		$sDetalle=f111_TablaDetalleBusquedas($aParametros, $objDB);
		break;
		case 'saiu39idresponsable':
		require $APP->rutacomun.'lib111.php';
		$sDetalle=f111_TablaDetalleBusquedas($aParametros, $objDB);
		break;
		case 'saiu39usuario':
		require $APP->rutacomun.'lib111.php';
		$sDetalle=f111_TablaDetalleBusquedas($aParametros, $objDB);
		break;
		}
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97tabla', 'innerHTML', $sDetalle);
	return $objResponse;
	}
function f3028_TablaDetalleV2($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3028='lg/lg_3028_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3028)){$mensajes_3028='lg/lg_3028_es.php';}
	require $mensajes_todas;
	require $mensajes_3028;
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
	//$aParametros[103]=numeros_validar($aParametros[103]);
	$idTercero=$aParametros[100];
	$sDebug='';
	$pagina=$aParametros[101];
	$lineastabla=$aParametros[102];
	$bNombre=$aParametros[103];
	$iVigencia=$aParametros[104];
	$iTipo=$aParametros[105];
	$iTema=$aParametros[106];
	$bDoc=$aParametros[107];
	$bEstado=$aParametros[108];
	$iFechaIni=$aParametros[109];
	$iFechaFin=$aParametros[110];
	$bDetalle=$aParametros[111];
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
		return array($sLeyenda.'<input id="paginaf3028" name="paginaf3028" type="hidden" value="'.$pagina.'"/><input id="lppf3028" name="lppf3028" type="hidden" value="'.$lineastabla.'"/>', $sDebug);
		die();
		}
	$aEstado=array();
	$sSQL='SELECT saiu11id, saiu11nombre FROM saiu11estadosol';
	$tabla=$objDB->ejecutasql($sSQL);
	while($fila=$objDB->sf($tabla)){
		$aEstado[$fila['saiu11id']]=cadena_notildes($fila['saiu11nombre']);
		}
	$sSQLadd='';
	$sSQLadd1='';
	switch($iTipo){
		case 1:
		$sSQLadd1=$sSQLadd1.'TB.saiu28idresponsable='.$idTercero.' AND ';
		break;
		}
	if ($iTema!=''){$sSQLadd1=$sSQLadd1.'TB.saiu28temasolicitud='.$iTema.' AND ';}
	if ($bEstado!=''){$sSQLadd1=$sSQLadd1.'TB.saiu28estado='.$bEstado.' AND ';}
	if ((int)$iFechaIni!=0){$sSQLadd1=$sSQLadd1.'((TB.saiu28agno*10000)+(TB.saiu28mes*100)+(TB.saiu28dia))>='.$iFechaIni.' AND ';}
	if ((int)$iFechaFin!=0){$sSQLadd1=$sSQLadd1.'((TB.saiu28agno*10000)+(TB.saiu28mes*100)+(TB.saiu28dia))<='.$iFechaFin.' AND ';}
	if ($bDoc!=''){$sSQLadd=$sSQLadd.' AND T12.unad11doc LIKE "%'.$bDoc.'%"';}
	if ($aParametros[103]!=''){
		$sBase=trim(strtoupper($aParametros[103]));
		$aNoms=explode(' ', $sBase);
		for ($k=1;$k<=count($aNoms);$k++){
			$sCadena=$aNoms[$k-1];
			if ($sCadena!=''){
				$sSQLadd=$sSQLadd.' AND T12.unad11razonsocial LIKE "%'.$sCadena.'%"';
				//$sSQLadd1=$sSQLadd1.'T1.unad11razonsocial LIKE "%'.$sCadena.'%" AND ';
				}
			}
		}
	if ($bDetalle!=''){
		$sBase=trim(strtoupper($bDetalle));
		$aNoms=explode(' ', $sBase);
		for ($k=1;$k<=count($aNoms);$k++){
			$sCadena=$aNoms[$k-1];
			if ($sCadena!=''){
				$sSQLadd1=$sSQLadd1.'TB.saiu28detalle LIKE "%'.$sCadena.'%" AND ';
				}
			}
		}
	$sTitulos='Agno, Mes, Tiporadicado, Consec, Id, Dia, Hora, Minuto, Estado, Chat, Solicitante, Tipointeresado, Clasesolicitud, Tiposolicitud, Temasolicitud, Zona, Centro, Codpais, Coddepto, Codciudad, Escuela, Programa, Periodo, Numorigen, Pqrs, Detalle, Horafin, Minutofin, Paramercadeo, Responsable, Tiemprespdias, Tiempresphoras, Tiemprespminutos, Solucion, Caso';
	$registros=0;
	$bGigante=false; //En caso de que la tabla sea muy grande pasarlo a true
	$sLimite='';
	if ($bGigante){
		$sSQL='SELECT COUNT(1) AS Total 
		FROM saiu28mesaayuda_'.$iVigencia.' AS TB, unad11terceros AS T12, saiu03temasol AS T16 
		WHERE '.$sSQLadd1.' TB.saiu28idsolicitante=T12.unad11id AND TB.saiu28temasolicitud=T16.saiu03id '.$sSQLadd.'';
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
	//, TB.saiu28numtelefono, TB.saiu28idpqrs, TB.saiu28detalle, TB.saiu28horafin, TB.saiu28minutofin, TB.saiu28tiemprespdias, TB.saiu28tiempresphoras, TB.saiu28tiemprespminutos, TB.saiu28tiporadicado, TB.saiu28idtelefono, TB.saiu28tipointeresado, TB.saiu28clasesolicitud, TB.saiu28tiposolicitud, TB.saiu28temasolicitud, TB.saiu28idzona, TB.saiu28idcentro, TB.saiu28codpais, TB.saiu28coddepto, TB.saiu28codciudad, TB.saiu28idescuela, TB.saiu28idprograma, TB.saiu28idperiodo, TB.saiu28idresponsable
	$sSQL='SELECT TB.saiu28agno, TB.saiu28mes, TB.saiu28consec, TB.saiu28id, TB.saiu28dia, TB.saiu28hora, TB.saiu28minuto, T12.unad11razonsocial AS C12_nombre, T16.saiu03titulo, TB.saiu28estado, T12.unad11tipodoc AS C12_td, T12.unad11doc AS C12_doc, TB.saiu28idsolicitante 
	FROM saiu28mesaayuda_'.$iVigencia.' AS TB, unad11terceros AS T12, saiu03temasol AS T16 
	WHERE '.$sSQLadd1.' TB.saiu28idsolicitante=T12.unad11id AND TB.saiu28temasolicitud=T16.saiu03id '.$sSQLadd.'
	ORDER BY TB.saiu28agno DESC, TB.saiu28mes DESC, TB.saiu28dia DESC, TB.saiu28tiporadicado, TB.saiu28consec DESC';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_3028" name="consulta_3028" type="hidden" value="'.$sSQLlista.'"/>
	<input id="titulos_3028" name="titulos_3028" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objDB->ejecutasql($sSQL.$sLimite);
	if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 3028: '.$sSQL.$sLimite.'<br>';}
	if ($tabladetalle==false){
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		//$sLeyenda=$sSQL;
		}else{
		if (!$bGigante){
			$registros=$objDB->nf($tabladetalle);
			if ($registros==0){
				//return array(utf8_encode($sErrConsulta.'<input id="paginaf3028" name="paginaf3028" type="hidden" value="'.$pagina.'"/><input id="lppf3028" name="lppf3028" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
	<td><b>'.$ETI['saiu28consec'].'</b></td>
	<td><b>'.$ETI['saiu28estado'].'</b></td>
	<td colspan="2"><b>'.$ETI['saiu28idsolicitante'].'</b></td>
	<td><b>'.$ETI['saiu28temasolicitud'].'</b></td>
	<td align="right">
	'.html_paginador('paginaf3028', $registros, $lineastabla, $pagina, 'paginarf3028()').'
	'.html_lpp('lppf3028', $lineastabla, 'paginarf3028()').'
	</td>
	</tr></thead>';
	$tlinea=1;
	while($filadet=$objDB->sf($tabladetalle)){
		$sPrefijo='';
		$sSufijo='';
		$sClass=' class="resaltetabla"';
		$sLink='';
		switch($filadet['saiu28estado']){
			case 7:
			$sPrefijo='<span class="verde">';
			$sSufijo='</span>';
			break;
			case 8:
			case 9:
			$sPrefijo='<span class="rojo">';
			$sSufijo='</span>';
			break;
			}
		if(($tlinea%2)!=0){$sClass='';}
		$tlinea++;
		$et_saiu28hora=html_TablaHoraMin($filadet['saiu28hora'], $filadet['saiu28minuto']);
		$et_saiu28idsolicitante_doc='';
		$et_saiu28idsolicitante_nombre='';
		if ($filadet['saiu28idsolicitante']!=0){
			$et_saiu28idsolicitante_doc=$sPrefijo.$filadet['C12_td'].' '.$filadet['C12_doc'].$sSufijo;
			$et_saiu28idsolicitante_nombre=$sPrefijo.cadena_notildes($filadet['C12_nombre']).$sSufijo;
			}
		/*
		$et_saiu28horafin=html_TablaHoraMin($filadet['saiu28horafin'], $filadet['saiu28minutofin']);
		$et_saiu28idresponsable_doc='';
		$et_saiu28idresponsable_nombre='';
		if ($filadet['saiu28idresponsable']!=0){
			$et_saiu28idresponsable_doc=$sPrefijo.$filadet['C30_td'].' '.$filadet['C30_doc'].$sSufijo;
			$et_saiu28idresponsable_nombre=$sPrefijo.cadena_notildes($filadet['C30_nombre']).$sSufijo;
			}
		*/
		if ($bAbierta){
			$sLink='<a href="javascript:cargaridf3028('.$filadet['saiu28id'].')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$et_fecha=fecha_armar($filadet['saiu28dia'], $filadet['saiu28mes'], $filadet['saiu28agno']);
		$res=$res.'<tr'.$sClass.'>
		<td>'.$sPrefijo.$et_fecha.$sSufijo.'</td>
		<td>'.$sPrefijo.$et_saiu28hora.$sSufijo.'</td>
		<td>'.$sPrefijo.$filadet['saiu28consec'].$sSufijo.'</td>
		<td>'.$sPrefijo.$aEstado[$filadet['saiu28estado']].$sSufijo.'</td>
		<td>'.$et_saiu28idsolicitante_doc.'</td>
		<td>'.$et_saiu28idsolicitante_nombre.'</td>
		<td>'.$sPrefijo.cadena_notildes($filadet['saiu03titulo']).$sSufijo.'</td>
		<td>'.$sLink.'</td>
		</tr>';
		}
	$res=$res.'</table>';
	$objDB->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
	}
function f3028_HtmlTabla($aParametros){
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
	list($sDetalle, $sDebugTabla)=f3028_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f3028detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f3028_db_CargarPadre($DATA, $objDB, $bDebug=false){
	$sError='';
	$iTipoError=0;
	$sDebug='';
	require './app.php';
	$DATA['saiu28idsolicitante_td']=$APP->tipo_doc;
	$DATA['saiu28idsolicitante_doc']='';
	$DATA['saiu28idresponsable_td']=$APP->tipo_doc;
	$DATA['saiu28idresponsable_doc']='';
	$DATA['saiu28idliderrespon1_td']=$APP->tipo_doc;
	$DATA['saiu28idliderrespon1_doc']='';
	$DATA['saiu28idliderrespon2_td']=$APP->tipo_doc;
	$DATA['saiu28idliderrespon2_doc']='';
	$DATA['saiu28idliderrespon3_td']=$APP->tipo_doc;
	$DATA['saiu28idliderrespon3_doc']='';
	$DATA['saiu28idsupervisor_td']=$APP->tipo_doc;
	$DATA['saiu28idsupervisor_doc']='';
	if ($DATA['paso']==1){
		$sSQLcondi='saiu28agno='.$DATA['saiu28agno'].' AND saiu28mes='.$DATA['saiu28mes'].' AND saiu28tiporadicado='.$DATA['saiu28tiporadicado'].' AND saiu28consec='.$DATA['saiu28consec'].'';
		}else{
		$sSQLcondi='saiu28id='.$DATA['saiu28id'].'';
		}
	$sSQL='SELECT * FROM saiu28mesaayuda_'.$DATA['saiu28agno'].' WHERE '.$sSQLcondi;
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$DATA['saiu28agno']=$fila['saiu28agno'];
		$DATA['saiu28mes']=$fila['saiu28mes'];
		$DATA['saiu28tiporadicado']=$fila['saiu28tiporadicado'];
		$DATA['saiu28consec']=$fila['saiu28consec'];
		$DATA['saiu28id']=$fila['saiu28id'];
		$DATA['saiu28dia']=$fila['saiu28dia'];
		$DATA['saiu28hora']=$fila['saiu28hora'];
		$DATA['saiu28minuto']=$fila['saiu28minuto'];
		$DATA['saiu28estado']=$fila['saiu28estado'];
		$DATA['saiu28idsolicitante']=$fila['saiu28idsolicitante'];
		$DATA['saiu28tipointeresado']=$fila['saiu28tipointeresado'];
		$DATA['saiu28clasesolicitud']=$fila['saiu28clasesolicitud'];
		$DATA['saiu28tiposolicitud']=$fila['saiu28tiposolicitud'];
		$DATA['saiu28temasolicitud']=$fila['saiu28temasolicitud'];
		$DATA['saiu28idzona']=$fila['saiu28idzona'];
		$DATA['saiu28idcentro']=$fila['saiu28idcentro'];
		$DATA['saiu28codpais']=$fila['saiu28codpais'];
		$DATA['saiu28coddepto']=$fila['saiu28coddepto'];
		$DATA['saiu28codciudad']=$fila['saiu28codciudad'];
		$DATA['saiu28idescuela']=$fila['saiu28idescuela'];
		$DATA['saiu28idprograma']=$fila['saiu28idprograma'];
		$DATA['saiu28idperiodo']=$fila['saiu28idperiodo'];
		$DATA['saiu28idpqrs']=$fila['saiu28idpqrs'];
		$DATA['saiu28detalle']=$fila['saiu28detalle'];
		$DATA['saiu28horafin']=$fila['saiu28horafin'];
		$DATA['saiu28minutofin']=$fila['saiu28minutofin'];
		$DATA['saiu28idresponsable']=$fila['saiu28idresponsable'];
		$DATA['saiu28tiemprespdias']=$fila['saiu28tiemprespdias'];
		$DATA['saiu28tiempresphoras']=$fila['saiu28tiempresphoras'];
		$DATA['saiu28tiemprespminutos']=$fila['saiu28tiemprespminutos'];
		$DATA['saiu28solucion']=$fila['saiu28solucion'];
		$DATA['saiu28numetapas']=$fila['saiu28numetapas'];
		$DATA['saiu28idunidadresp1']=$fila['saiu28idunidadresp1'];
		$DATA['saiu28idequiporesp1']=$fila['saiu28idequiporesp1'];
		$DATA['saiu28idliderrespon1']=$fila['saiu28idliderrespon1'];
		$DATA['saiu28tiemprespdias1']=$fila['saiu28tiemprespdias1'];
		$DATA['saiu28tiempresphoras1']=$fila['saiu28tiempresphoras1'];
		$DATA['saiu28centrotarea1']=$fila['saiu28centrotarea1'];
		$DATA['saiu28tiempousado1']=$fila['saiu28tiempousado1'];
		$DATA['saiu28tiempocalusado1']=$fila['saiu28tiempocalusado1'];
		$DATA['saiu28idunidadresp2']=$fila['saiu28idunidadresp2'];
		$DATA['saiu28idequiporesp2']=$fila['saiu28idequiporesp2'];
		$DATA['saiu28idliderrespon2']=$fila['saiu28idliderrespon2'];
		$DATA['saiu28tiemprespdias2']=$fila['saiu28tiemprespdias2'];
		$DATA['saiu28tiempresphoras2']=$fila['saiu28tiempresphoras2'];
		$DATA['saiu28centrotarea2']=$fila['saiu28centrotarea2'];
		$DATA['saiu28tiempousado2']=$fila['saiu28tiempousado2'];
		$DATA['saiu28tiempocalusado2']=$fila['saiu28tiempocalusado2'];
		$DATA['saiu28idunidadresp3']=$fila['saiu28idunidadresp3'];
		$DATA['saiu28idequiporesp3']=$fila['saiu28idequiporesp3'];
		$DATA['saiu28idliderrespon3']=$fila['saiu28idliderrespon3'];
		$DATA['saiu28tiemprespdias3']=$fila['saiu28tiemprespdias3'];
		$DATA['saiu28tiempresphoras3']=$fila['saiu28tiempresphoras3'];
		$DATA['saiu28centrotarea3']=$fila['saiu28centrotarea3'];
		$DATA['saiu28tiempousado3']=$fila['saiu28tiempousado3'];
		$DATA['saiu28tiempocalusado3']=$fila['saiu28tiempocalusado3'];
		$DATA['saiu28idsupervisor']=$fila['saiu28idsupervisor'];
		$DATA['saiu28moduloasociado']=$fila['saiu28moduloasociado'];
		$bcargo=true;
		$DATA['paso']=2;
		$DATA['boculta3028']=0;
		$bLimpiaHijos=true;
		}else{
		$DATA['paso']=0;
		}
	return array($DATA, $sError, $iTipoError, $sDebug);
	}
function f3028_Cerrar($saiu28id, $objDB, $bDebug=false){
	$sInfo='';
	$sDebug='';
	return array($sInfo, $sDebug);
	}
function f3028_db_GuardarV2($DATA, $objDB, $bDebug=false){
	$iCodModulo=3028;
	$bAudita[2]=true;
	$bAudita[3]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3028='lg/lg_3028_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3028)){$mensajes_3028='lg/lg_3028_es.php';}
	require $mensajes_todas;
	require $mensajes_3028;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	$bCerrando=false;
	$sErrorCerrando='';
	// -- Se inicia validando todas las posibles entradas de usuario.
	/*
	if (isset($DATA['saiu28agno'])==0){$DATA['saiu28agno']='';}
	if (isset($DATA['saiu28mes'])==0){$DATA['saiu28mes']='';}
	if (isset($DATA['saiu28consec'])==0){$DATA['saiu28consec']='';}
	if (isset($DATA['saiu28id'])==0){$DATA['saiu28id']='';}
	if (isset($DATA['saiu28dia'])==0){$DATA['saiu28dia']='';}
	if (isset($DATA['saiu28hora'])==0){$DATA['saiu28hora']='';}
	if (isset($DATA['saiu28minuto'])==0){$DATA['saiu28minuto']='';}
	if (isset($DATA['saiu28idsolicitante'])==0){$DATA['saiu28idsolicitante']='';}
	if (isset($DATA['saiu28tipointeresado'])==0){$DATA['saiu28tipointeresado']='';}
	if (isset($DATA['saiu28tiposolicitud'])==0){$DATA['saiu28tiposolicitud']='';}
	if (isset($DATA['saiu28temasolicitud'])==0){$DATA['saiu28temasolicitud']='';}
	if (isset($DATA['saiu28idzona'])==0){$DATA['saiu28idzona']='';}
	if (isset($DATA['saiu28idcentro'])==0){$DATA['saiu28idcentro']='';}
	if (isset($DATA['saiu28codpais'])==0){$DATA['saiu28codpais']='';}
	if (isset($DATA['saiu28coddepto'])==0){$DATA['saiu28coddepto']='';}
	if (isset($DATA['saiu28codciudad'])==0){$DATA['saiu28codciudad']='';}
	if (isset($DATA['saiu28idescuela'])==0){$DATA['saiu28idescuela']='';}
	if (isset($DATA['saiu28idprograma'])==0){$DATA['saiu28idprograma']='';}
	if (isset($DATA['saiu28idperiodo'])==0){$DATA['saiu28idperiodo']='';}
	if (isset($DATA['saiu28detalle'])==0){$DATA['saiu28detalle']='';}
	if (isset($DATA['saiu28horafin'])==0){$DATA['saiu28horafin']='';}
	if (isset($DATA['saiu28minutofin'])==0){$DATA['saiu28minutofin']='';}
	if (isset($DATA['saiu28idresponsable'])==0){$DATA['saiu28idresponsable']='';}
	if (isset($DATA['saiu28solucion'])==0){$DATA['saiu28solucion']='';}
	*/
	$DATA['saiu28agno']=numeros_validar($DATA['saiu28agno']);
	$DATA['saiu28mes']=numeros_validar($DATA['saiu28mes']);
	$DATA['saiu28consec']=numeros_validar($DATA['saiu28consec']);
	$DATA['saiu28dia']=numeros_validar($DATA['saiu28dia']);
	$DATA['saiu28hora']=numeros_validar($DATA['saiu28hora']);
	$DATA['saiu28minuto']=numeros_validar($DATA['saiu28minuto']);
	$DATA['saiu28tipointeresado']=numeros_validar($DATA['saiu28tipointeresado']);
	$DATA['saiu28tiposolicitud']=numeros_validar($DATA['saiu28tiposolicitud']);
	$DATA['saiu28temasolicitud']=numeros_validar($DATA['saiu28temasolicitud']);
	$DATA['saiu28idzona']=numeros_validar($DATA['saiu28idzona']);
	$DATA['saiu28idcentro']=numeros_validar($DATA['saiu28idcentro']);
	$DATA['saiu28codpais']=htmlspecialchars(trim($DATA['saiu28codpais']));
	$DATA['saiu28coddepto']=htmlspecialchars(trim($DATA['saiu28coddepto']));
	$DATA['saiu28codciudad']=htmlspecialchars(trim($DATA['saiu28codciudad']));
	$DATA['saiu28idescuela']=numeros_validar($DATA['saiu28idescuela']);
	$DATA['saiu28idprograma']=numeros_validar($DATA['saiu28idprograma']);
	$DATA['saiu28idperiodo']=numeros_validar($DATA['saiu28idperiodo']);
	$DATA['saiu28detalle']=htmlspecialchars(trim($DATA['saiu28detalle']));
	$DATA['saiu28horafin']=numeros_validar($DATA['saiu28horafin']);
	$DATA['saiu28minutofin']=numeros_validar($DATA['saiu28minutofin']);
	$DATA['saiu28solucion']=numeros_validar($DATA['saiu28solucion']);
	// -- Se inicializan las variables que puedan pasar vacias {Especialmente números}.
	//if ($DATA['saiu28dia']==''){$DATA['saiu28dia']=0;}
	//if ($DATA['saiu28hora']==''){$DATA['saiu28hora']=0;}
	//if ($DATA['saiu28minuto']==''){$DATA['saiu28minuto']=0;}
	if ($DATA['saiu28estado']==''){$DATA['saiu28estado']=0;}
	//if ($DATA['saiu28tipointeresado']==''){$DATA['saiu28tipointeresado']=0;}
	//if ($DATA['saiu28tiposolicitud']==''){$DATA['saiu28tiposolicitud']=0;}
	//if ($DATA['saiu28temasolicitud']==''){$DATA['saiu28temasolicitud']=0;}
	//if ($DATA['saiu28idzona']==''){$DATA['saiu28idzona']=0;}
	//if ($DATA['saiu28idcentro']==''){$DATA['saiu28idcentro']=0;}
	//if ($DATA['saiu28idescuela']==''){$DATA['saiu28idescuela']=0;}
	//if ($DATA['saiu28idprograma']==''){$DATA['saiu28idprograma']=0;}
	//if ($DATA['saiu28idperiodo']==''){$DATA['saiu28idperiodo']=0;}
	if ($DATA['saiu28idpqrs']==''){$DATA['saiu28idpqrs']=0;}
	//if ($DATA['saiu28horafin']==''){$DATA['saiu28horafin']=0;}
	//if ($DATA['saiu28minutofin']==''){$DATA['saiu28minutofin']=0;}
	//if ($DATA['saiu28solucion']==''){$DATA['saiu28solucion']=0;}
	if ($DATA['saiu28numetapas']==''){$DATA['saiu28numetapas']=0;}
	if ($DATA['saiu28idunidadresp1']==''){$DATA['saiu28idunidadresp1']=0;}
	if ($DATA['saiu28idequiporesp1']==''){$DATA['saiu28idequiporesp1']=0;}
	if ($DATA['saiu28tiemprespdias1']==''){$DATA['saiu28tiemprespdias1']=0;}
	if ($DATA['saiu28tiempresphoras1']==''){$DATA['saiu28tiempresphoras1']=0;}
	if ($DATA['saiu28centrotarea1']==''){$DATA['saiu28centrotarea1']=0;}
	if ($DATA['saiu28tiempousado1']==''){$DATA['saiu28tiempousado1']=0;}
	if ($DATA['saiu28tiempocalusado1']==''){$DATA['saiu28tiempocalusado1']=0;}
	if ($DATA['saiu28idunidadresp2']==''){$DATA['saiu28idunidadresp2']=0;}
	if ($DATA['saiu28idequiporesp2']==''){$DATA['saiu28idequiporesp2']=0;}
	if ($DATA['saiu28tiemprespdias2']==''){$DATA['saiu28tiemprespdias2']=0;}
	if ($DATA['saiu28tiempresphoras2']==''){$DATA['saiu28tiempresphoras2']=0;}
	if ($DATA['saiu28centrotarea2']==''){$DATA['saiu28centrotarea2']=0;}
	if ($DATA['saiu28tiempousado2']==''){$DATA['saiu28tiempousado2']=0;}
	if ($DATA['saiu28tiempocalusado2']==''){$DATA['saiu28tiempocalusado2']=0;}
	if ($DATA['saiu28idunidadresp3']==''){$DATA['saiu28idunidadresp3']=0;}
	if ($DATA['saiu28idequiporesp3']==''){$DATA['saiu28idequiporesp3']=0;}
	if ($DATA['saiu28tiemprespdias3']==''){$DATA['saiu28tiemprespdias3']=0;}
	if ($DATA['saiu28tiempresphoras3']==''){$DATA['saiu28tiempresphoras3']=0;}
	if ($DATA['saiu28centrotarea3']==''){$DATA['saiu28centrotarea3']=0;}
	if ($DATA['saiu28tiempousado3']==''){$DATA['saiu28tiempousado3']=0;}
	if ($DATA['saiu28tiempocalusado3']==''){$DATA['saiu28tiempocalusado3']=0;}
	if ($DATA['saiu28moduloasociado']==''){$DATA['saiu28moduloasociado']=0;}
	// -- Seccion para validar los posibles causales de error.
	$sSepara=', ';
	$bConCierre=false;
	if ($DATA['saiu28estado']==7){
		$bConCierre=true;
		if ($DATA['saiu28solucion']==''){
			$sError=$ERR['saiu28solucion'].$sSepara.$sError;
			}else{
			if ((int)$DATA['saiu28solucion']==0){
				$sError=$ERR['saiu28solucion_proceso'].$sSepara.$sError;
				}
			}
		//if ($DATA['saiu28idresponsable']==0){$sError=$ERR['saiu28idresponsable'].$sSepara.$sError;}
		//if ($DATA['saiu28minutofin']==''){$sError=$ERR['saiu28minutofin'].$sSepara.$sError;}
		//if ($DATA['saiu28horafin']==''){$sError=$ERR['saiu28horafin'].$sSepara.$sError;}
		if ($DATA['saiu28detalle']==''){$sError=$ERR['saiu28detalle'].$sSepara.$sError;}
		if ($DATA['saiu28idperiodo']==''){$sError=$ERR['saiu28idperiodo'].$sSepara.$sError;}
		if ($DATA['saiu28idprograma']==''){$sError=$ERR['saiu28idprograma'].$sSepara.$sError;}
		if ($DATA['saiu28idescuela']==''){$sError=$ERR['saiu28idescuela'].$sSepara.$sError;}
		if ($DATA['saiu28codciudad']==''){$sError=$ERR['saiu28codciudad'].$sSepara.$sError;}
		if ($DATA['saiu28coddepto']==''){$sError=$ERR['saiu28coddepto'].$sSepara.$sError;}
		if ($DATA['saiu28codpais']==''){$sError=$ERR['saiu28codpais'].$sSepara.$sError;}
		if ($DATA['saiu28idcentro']==''){$sError=$ERR['saiu28idcentro'].$sSepara.$sError;}
		if ($DATA['saiu28idzona']==''){$sError=$ERR['saiu28idzona'].$sSepara.$sError;}
		if ($DATA['saiu28temasolicitud']==''){$sError=$ERR['saiu28temasolicitud'].$sSepara.$sError;}
		if ($DATA['saiu28tiposolicitud']==''){$sError=$ERR['saiu28tiposolicitud'].$sSepara.$sError;}
		if ($DATA['saiu28clasesolicitud']==''){$sError=$ERR['saiu28clasesolicitud'].$sSepara.$sError;}
		if ($DATA['saiu28tipointeresado']==''){$sError=$ERR['saiu28tipointeresado'].$sSepara.$sError;}
		if ($DATA['saiu28idsolicitante']==0){$sError=$ERR['saiu28idsolicitante'].$sSepara.$sError;}
		if ($DATA['saiu28minuto']==''){$sError=$ERR['saiu28minuto'].$sSepara.$sError;}
		if ($DATA['saiu28hora']==''){$sError=$ERR['saiu28hora'].$sSepara.$sError;}
		if ($DATA['saiu28dia']==''){$sError=$ERR['saiu28dia'].$sSepara.$sError;}
		//if ($DATA['saiu28hora']==''){$DATA['saiu28hora']=fecha_hora();}
		//Ver que la solucion sea coherente con el resultado
		if ($sError==''){
			switch($DATA['saiu28solucion']){
				case 0: // Sin resolver
				//case 5: // Se abre pqr
				case 8: // Sin resolver
				$sError='No se puede cerrar el caso sin ser solucionado.';
				break;	
				}
			}
		if ($sError!=''){$DATA['saiu28estado']=2;}
		$sErrorCerrando=$sError;
		$sError='';
		//Fin de las valiaciones NO LLAVE.
		}
	if (true){
		switch($DATA['saiu28estado']){
			case 7: //Logra cerrar
			case 8: //Solicitud abandonada
			case 9: //Cancelada por el usuario
			if ($DATA['saiu28minutofin']==''){$DATA['saiu28minutofin']=fecha_minuto();}
			if ($DATA['saiu28horafin']==''){$DATA['saiu28horafin']=fecha_hora();}
			//$DATA['saiu28minutofin']=fecha_minuto();
			//$DATA['saiu28horafin']=fecha_hora();
			break; 
			default:
			$DATA['saiu28estado']=2;
			if ($DATA['saiu28hora']==''){$DATA['saiu28hora']=fecha_hora();}
			if ($DATA['saiu28minuto']==''){$DATA['saiu28minuto']=fecha_minuto();}
			if ($DATA['saiu28horafin']==''){$DATA['saiu28horafin']=0;}
			if ($DATA['saiu28minutofin']==''){$DATA['saiu28minutofin']=0;}
			break;
			}
		if ($DATA['saiu28clasesolicitud']==''){$DATA['saiu28clasesolicitud']=0;}
		if ($DATA['saiu28tiposolicitud']==''){$DATA['saiu28tiposolicitud']=0;}
		if ($DATA['saiu28temasolicitud']==''){$DATA['saiu28temasolicitud']=0;}
		if ($DATA['saiu28idzona']==''){$DATA['saiu28idzona']=0;}
		if ($DATA['saiu28idcentro']==''){$DATA['saiu28idcentro']=0;}
		if ($DATA['saiu28idescuela']==''){$DATA['saiu28idescuela']=0;}
		if ($DATA['saiu28idprograma']==''){$DATA['saiu28idprograma']=0;}
		if ($DATA['saiu28idperiodo']==''){$DATA['saiu28idperiodo']=0;}
		}
	//Valiaciones de campos obligatorios en todo guardar.
	if ($DATA['saiu28tiporadicado']==''){$sError=$ERR['saiu28tiporadicado'];}
	if ($DATA['saiu28mes']==''){$sError=$ERR['saiu28mes'];}
	if ($DATA['saiu28agno']==''){$sError=$ERR['saiu28agno'];}
	// -- Tiene un cerrado.
	$iDiaIni=($DATA['saiu28agno']*10000)+($DATA['saiu28mes']*100)+$DATA['saiu28dia'];
	if ($bConCierre){
		//Validaciones previas a cerrar
		if ($DATA['saiu28estado']==7){
			list($DATA['saiu28tiemprespdias'], $DATA['saiu28tiempresphoras'], $DATA['saiu28tiemprespminutos'])=Tiempo_MinutosCalendario($iDiaIni, $DATA['saiu28hora'], $DATA['saiu28minuto'], $iDiaIni, $DATA['saiu28horafin'], $DATA['saiu28minutofin']);
			}
		//Aprobó las Validaciones al cerrar
		if ($sError.$sErrorCerrando!=''){
			$DATA['saiu28estado']=2;
			$sErrorCerrando=$sError.' '.$sErrorCerrando;
			$sError='';
			}else{
			$bCerrando=true;
			//Acciones del cierre
			}
		}
	// -- Fin del cerrado.
	// -- Se verifican los valores de campos de otras tablas.
	$sTabla19='saiu28mesaayuda_'.$DATA['saiu28agno'];
	if ($DATA['saiu28idresponsable_doc']!=''){
		if ($sError==''){$sError=tabla_terceros_existe($DATA['saiu28idresponsable_td'], $DATA['saiu28idresponsable_doc'], $objDB, 'El tercero Responsable ');}
		if ($sError==''){
			list($sError, $sInfo)=tercero_Bloqueado($DATA['saiu28idresponsable'], $objDB);
			if ($sInfo!=''){$sError=$sError.'<br>'.$sInfo;}
			}
		}
	if ($DATA['saiu28idsolicitante_doc']!=''){
		if ($sError==''){$sError=tabla_terceros_existe($DATA['saiu28idsolicitante_td'], $DATA['saiu28idsolicitante_doc'], $objDB, 'El tercero Solicitante ');}
		if ($sError==''){
			list($sError, $sInfo)=tercero_Bloqueado($DATA['saiu28idsolicitante'], $objDB);
			if ($sInfo!=''){$sError=$sError.'<br>'.$sInfo;}
			}
		}
	$bQuitarCodigo=false;
	$sCampoCodigo='';
	if ($sError==''){
		if ($DATA['paso']==10){
			if ($DATA['saiu28consec']==''){
				$DATA['saiu28consec']=tabla_consecutivo($sTabla19, 'saiu28consec', 'saiu28agno='.$DATA['saiu28agno'].' AND saiu28mes='.$DATA['saiu28mes'].' AND saiu28tiporadicado='.$DATA['saiu28tiporadicado'].'', $objDB);
				if ($DATA['saiu28consec']==-1){$sError=$objDB->serror;}
				$bQuitarCodigo=true;
				$sCampoCodigo='saiu28consec';
				}else{
				if (!seg_revisa_permiso($iCodModulo, 8, $objDB)){
					$sError=$ERR['8'];
					$DATA['saiu28consec']='';
					}
				}
			if ($sError==''){
				$sSQL='SELECT 1 FROM '.$sTabla19.' WHERE saiu28agno='.$DATA['saiu28agno'].' AND saiu28mes='.$DATA['saiu28mes'].' AND saiu28tiporadicado='.$DATA['saiu28tiporadicado'].' AND saiu28consec='.$DATA['saiu28consec'].'';
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
			//preparar el responsable
			/*
			list($idUnidad, $idEquipo, $idResponsable, $sInfo, $sDebugR)=f3000_Responsable($DATA['saiu28tiposolicitud'], $DATA['saiu28idsolicitante'], true, $objDB, $bDebug);
			$sDebug=$sDebug.$sDebugR;
			$DATA['saiu28idresponsable']=$idResponsable;
			$DATA['saiu28idunidadresp1']=$idUnidad;
			$DATA['saiu28idequiporesp1']=$idEquipo;
			$DATA['saiu28idliderrespon1']=$idResponsable; //$_SESSION['u_idtercero'];
			*/
			//Preparar el Id, Si no lo hay se quita la comprobación.
			$DATA['saiu28id']=tabla_consecutivo($sTabla19,'saiu28id', '', $objDB);
			if ($DATA['saiu28id']==-1){$sError=$objDB->serror;}
			}else{
			//Validar que no haya error en la hora y el minuto inicial.
			}
		}
	if ($sError==''){
		if ($DATA['paso']==10){
			$DATA['saiu28idpqrs']=0;
			$DATA['saiu28tiemprespdias']=0;
			$DATA['saiu28tiempresphoras']=0;
			$DATA['saiu28tiemprespminutos']=0;
			$DATA['saiu28numetapas']=1;
			$DATA['saiu28tiemprespdias1']=0;
			$DATA['saiu28tiempresphoras1']=0;
			$DATA['saiu28centrotarea1']=0;
			$DATA['saiu28tiempousado1']=0;
			$DATA['saiu28tiempocalusado1']=0;
			$DATA['saiu28idunidadresp2']=0;
			$DATA['saiu28idequiporesp2']=0;
			$DATA['saiu28tiemprespdias2']=0;
			$DATA['saiu28tiempresphoras2']=0;
			$DATA['saiu28centrotarea2']=0;
			$DATA['saiu28tiempousado2']=0;
			$DATA['saiu28tiempocalusado2']=0;
			$DATA['saiu28idunidadresp3']=0;
			$DATA['saiu28idequiporesp3']=0;
			$DATA['saiu28tiemprespdias3']=0;
			$DATA['saiu28tiempresphoras3']=0;
			$DATA['saiu28centrotarea3']=0;
			$DATA['saiu28tiempousado3']=0;
			$DATA['saiu28tiempocalusado3']=0;
			$DATA['saiu28moduloasociado']=0;
			$DATA['saiu28fechalimite']=0; 
			$DATA['saiu28horalimite']=0;
			$DATA['saiu28minlimite']=0;
			}
		if ($DATA['saiu28idresponsable']==0){
			$iFechaIni=fecha_ArmarNumero($DATA['saiu28dia'], $DATA['saiu28mes'], $DATA['saiu28agno']);
			list($iFechaLim, $iHoraLim, $iMinutoLim, $sError, $sDebugL)=f3000_CalcularFechaLimite($DATA['saiu28temasolicitud'], $iFechaIni, $DATA['saiu28hora'], $DATA['saiu28minuto'], $objDB, $bDebug);
			$sDebug=$sDebug.$sDebugL;
			$DATA['saiu28fechalimite']=$iFechaLim;
			$DATA['saiu28horalimite']=$iHoraLim;
			$DATA['saiu28minlimite']=$iMinutoLim;
			//Esta cerrando, debemos ver si no se resolvió entonces el responsable debe ser segun el tema.
			$DATA['saiu28etapaactual']=1;
			switch($DATA['saiu28solucion']){
				case 0: // Sin resolver
				case 5: // Se abre pqr
				case 8: // Sin resolver
				list($idUnidad, $idEquipo, $idResponsable, $sInfo, $sDebugR)=f3000_Responsable($DATA['saiu28temasolicitud'], $DATA['saiu28idsolicitante'], true, $objDB, $bDebug);
				$sDebug=$sDebug.$sDebugR;
				$DATA['saiu28idresponsable']=$idResponsable;
				$DATA['saiu28idunidadresp1']=$idUnidad;
				$DATA['saiu28idequiporesp1']=$idEquipo;
				$DATA['saiu28idliderrespon1']=$idResponsable; //$_SESSION['u_idtercero'];
				break;
				default: //Paa los resueltos el responsable es quien lo resuelve y ya.
				$DATA['saiu28idresponsable']=$_SESSION['u_idtercero'];
				$DATA['saiu28idunidadresp1']=0;
				$DATA['saiu28idequiporesp1']=0;
				$DATA['saiu28idliderrespon1']=$_SESSION['u_idtercero'];
				break;
				}
			}
		}
	$idSolicitantePrevio=0;
	if ($sError==''){
		//Verificamos si el tercero tiene ciudad y departamento.
		$sSQL='SELECT unad11pais, unad11deptoorigen, unad11ciudadorigen, unad11telefono 
		FROM unad11terceros 
		WHERE unad11id='.$DATA['saiu28idsolicitante'].'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$fila11=$objDB->sf($tabla);
			$sCambia='';
			if (trim($fila11['unad11ciudadorigen'])==''){
				$sCambia='unad11pais="'.$DATA['saiu28codpais'].'", unad11deptoorigen="'.$DATA['saiu28coddepto'].'", unad11ciudadorigen="'.$DATA['saiu28codciudad'].'"';
				}
			if ($sCambia!=''){
				$sSQL='UPDATE unad11terceros SET '.$sCambia.' WHERE unad11id='.$DATA['saiu28idsolicitante'].'';
				$result=$objDB->ejecutasql($sSQL);
				}
			}
		}
	if ($sError==''){
		if (get_magic_quotes_gpc()==1){$DATA['saiu28detalle']=stripslashes($DATA['saiu28detalle']);}
		//Si el campo saiu28detalle permite html quite la linea htmlspecialchars para el campo y habilite la siguiente linea:
		//$saiu28detalle=addslashes($DATA['saiu28detalle']);
		$saiu28detalle=str_replace('"', '\"', $DATA['saiu28detalle']);
		$bpasa=false;
		if ($DATA['paso']==10){

			$sCampos3028='saiu28agno, saiu28mes, saiu28tiporadicado, saiu28consec, saiu28id, 
			saiu28dia, saiu28hora, saiu28minuto, saiu28estado, saiu28idsolicitante, 
			saiu28tipointeresado, saiu28clasesolicitud, saiu28tiposolicitud, saiu28temasolicitud, saiu28idzona, 
			saiu28idcentro, saiu28codpais, saiu28coddepto, saiu28codciudad, saiu28idescuela, 
			saiu28idprograma, saiu28idperiodo, saiu28idpqrs, saiu28detalle, saiu28horafin, 
			saiu28minutofin, saiu28idresponsable, saiu28tiemprespdias, saiu28tiempresphoras, saiu28tiemprespminutos, 
			saiu28solucion, saiu28numetapas, saiu28idunidadresp1, saiu28idequiporesp1, saiu28idliderrespon1, 
			saiu28tiemprespdias1, saiu28tiempresphoras1, saiu28centrotarea1, saiu28tiempousado1, saiu28tiempocalusado1, 
			saiu28idunidadresp2, saiu28idequiporesp2, saiu28idliderrespon2, saiu28tiemprespdias2, saiu28tiempresphoras2, 
			saiu28centrotarea2, saiu28tiempousado2, saiu28tiempocalusado2, saiu28idunidadresp3, saiu28idequiporesp3, 
			saiu28idliderrespon3, saiu28tiemprespdias3, saiu28tiempresphoras3, saiu28centrotarea3, saiu28tiempousado3, 
			saiu28tiempocalusado3, saiu28idsupervisor, saiu28moduloasociado, saiu28fechalimite, saiu28horalimite, 
			saiu28minlimite';
			$sValores3028=''.$DATA['saiu28agno'].', '.$DATA['saiu28mes'].', '.$DATA['saiu28tiporadicado'].', '.$DATA['saiu28consec'].', '.$DATA['saiu28id'].', 
			'.$DATA['saiu28dia'].', '.$DATA['saiu28hora'].', '.$DATA['saiu28minuto'].', '.$DATA['saiu28estado'].', '.$DATA['saiu28idsolicitante'].', 
			'.$DATA['saiu28tipointeresado'].', '.$DATA['saiu28clasesolicitud'].', '.$DATA['saiu28tiposolicitud'].', '.$DATA['saiu28temasolicitud'].', '.$DATA['saiu28idzona'].', 
			'.$DATA['saiu28idcentro'].', "'.$DATA['saiu28codpais'].'", "'.$DATA['saiu28coddepto'].'", "'.$DATA['saiu28codciudad'].'", '.$DATA['saiu28idescuela'].', 
			'.$DATA['saiu28idprograma'].', '.$DATA['saiu28idperiodo'].', '.$DATA['saiu28idpqrs'].', "'.$saiu28detalle.'", '.$DATA['saiu28horafin'].', 
			'.$DATA['saiu28minutofin'].', '.$DATA['saiu28idresponsable'].', '.$DATA['saiu28tiemprespdias'].', '.$DATA['saiu28tiempresphoras'].', '.$DATA['saiu28tiemprespminutos'].', 
			'.$DATA['saiu28solucion'].', '.$DATA['saiu28numetapas'].', '.$DATA['saiu28idunidadresp1'].', '.$DATA['saiu28idequiporesp1'].', '.$DATA['saiu28idliderrespon1'].', 
			'.$DATA['saiu28tiemprespdias1'].', '.$DATA['saiu28tiempresphoras1'].', '.$DATA['saiu28centrotarea1'].', '.$DATA['saiu28tiempousado1'].', '.$DATA['saiu28tiempocalusado1'].', 
			'.$DATA['saiu28idunidadresp2'].', '.$DATA['saiu28idequiporesp2'].', '.$DATA['saiu28idliderrespon2'].', '.$DATA['saiu28tiemprespdias2'].', '.$DATA['saiu28tiempresphoras2'].', 
			'.$DATA['saiu28centrotarea2'].', '.$DATA['saiu28tiempousado2'].', '.$DATA['saiu28tiempocalusado2'].', '.$DATA['saiu28idunidadresp3'].', '.$DATA['saiu28idequiporesp3'].', 
			'.$DATA['saiu28idliderrespon3'].', '.$DATA['saiu28tiemprespdias3'].', '.$DATA['saiu28tiempresphoras3'].', '.$DATA['saiu28centrotarea3'].', '.$DATA['saiu28tiempousado3'].', 
			'.$DATA['saiu28tiempocalusado3'].', '.$DATA['saiu28idsupervisor'].', '.$DATA['saiu28moduloasociado'].', '.$DATA['saiu28fechalimite'].', '.$DATA['saiu28horalimite'].', 
			'.$DATA['saiu28minlimite'].'';
			if ($APP->utf8==1){
				$sSQL='INSERT INTO '.$sTabla19.' ('.$sCampos3028.') VALUES ('.utf8_encode($sValores3028).');';
				$sdetalle=$sCampos3028.'['.utf8_encode($sValores3028).']';
				}else{
				$sSQL='INSERT INTO '.$sTabla19.' ('.$sCampos3028.') VALUES ('.$sValores3028.');';
				$sdetalle=$sCampos3028.'['.$sValores3028.']';
				}
			$idAccion=2;
			$bpasa=true;
			}else{
			$scampo[1]='saiu28dia';
			$scampo[2]='saiu28hora';
			$scampo[3]='saiu28minuto';
			$scampo[4]='saiu28idsolicitante';
			$scampo[5]='saiu28tipointeresado';
			$scampo[21]='saiu28tiposolicitud';
			$scampo[7]='saiu28temasolicitud';
			$scampo[8]='saiu28idzona';
			$scampo[9]='saiu28idcentro';
			$scampo[10]='saiu28codpais';
			$scampo[11]='saiu28coddepto';
			$scampo[12]='saiu28codciudad';
			$scampo[13]='saiu28idescuela';
			$scampo[14]='saiu28idprograma';
			$scampo[15]='saiu28idperiodo';
			$scampo[16]='saiu28detalle';
			$scampo[17]='saiu28horafin';
			$scampo[18]='saiu28minutofin';
			$scampo[19]='saiu28idresponsable';
			$scampo[20]='saiu28solucion';
			$scampo[6]='saiu28clasesolicitud';
			$scampo[22]='saiu28estado';
			$scampo[23]='saiu28tiemprespdias';
			$scampo[24]='saiu28tiempresphoras';
			$scampo[25]='saiu28tiemprespminutos';
			$scampo[26]='saiu28etapaactual';
			$scampo[27]='saiu28idunidadresp1';
			$scampo[28]='saiu28idequiporesp1';
			$scampo[29]='saiu28idliderrespon1';
			$sdato[1]=$DATA['saiu28dia'];
			$sdato[2]=$DATA['saiu28hora'];
			$sdato[3]=$DATA['saiu28minuto'];
			$sdato[4]=$DATA['saiu28idsolicitante'];
			$sdato[5]=$DATA['saiu28tipointeresado'];
			$sdato[21]=$DATA['saiu28tiposolicitud'];
			$sdato[7]=$DATA['saiu28temasolicitud'];
			$sdato[8]=$DATA['saiu28idzona'];
			$sdato[9]=$DATA['saiu28idcentro'];
			$sdato[10]=$DATA['saiu28codpais'];
			$sdato[11]=$DATA['saiu28coddepto'];
			$sdato[12]=$DATA['saiu28codciudad'];
			$sdato[13]=$DATA['saiu28idescuela'];
			$sdato[14]=$DATA['saiu28idprograma'];
			$sdato[15]=$DATA['saiu28idperiodo'];
			$sdato[16]=$saiu28detalle;
			$sdato[17]=$DATA['saiu28horafin'];
			$sdato[18]=$DATA['saiu28minutofin'];
			$sdato[19]=$DATA['saiu28idresponsable'];
			$sdato[20]=$DATA['saiu28solucion'];
			$sdato[6]=$DATA['saiu28clasesolicitud'];
			$sdato[22]=$DATA['saiu28estado'];
			$sdato[23]=$DATA['saiu28tiemprespdias'];
			$sdato[24]=$DATA['saiu28tiempresphoras'];
			$sdato[25]=$DATA['saiu28tiemprespminutos'];
			$sdato[26]=$DATA['saiu28etapaactual'];
			$sdato[27]=$DATA['saiu28idunidadresp1'];
			$sdato[28]=$DATA['saiu28idequiporesp1'];
			$sdato[29]=$DATA['saiu28idliderrespon1'];
			$numcmod=29;
			$sWhere='saiu28id='.$DATA['saiu28id'].'';
			$sSQL='SELECT * FROM '.$sTabla19.' WHERE '.$sWhere;
			$sdatos='';
			$bPrimera=true;
			$result=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($result)>0){
				$filabase=$objDB->sf($result);
				if ($DATA['saiu28idsolicitante']!=$filabase['saiu28idsolicitante']){
					$idSolicitantePrevio=$filabase['saiu28idsolicitante'];
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
					$sSQL='UPDATE '.$sTabla19.' SET '.utf8_encode($sdatos).' WHERE '.$sWhere.';';
					}else{
					$sdetalle=$sdatos.'['.$sWhere.']';
					$sSQL='UPDATE '.$sTabla19.' SET '.$sdatos.' WHERE '.$sWhere.';';
					}
				$idAccion=3;
				}
			}
		if ($bpasa){
			if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Guardar 3028 '.$sSQL.'<br>';}
			$result=$objDB->ejecutasql($sSQL);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' [3028] ..<!-- '.$sSQL.' -->';
				if ($idAccion==2){
					$DATA['saiu28id']='';
					$bQuitarCodigo=true;
					$DATA['paso']=0;
					}else{
					$DATA['paso']=2;
					}
				$bCerrando=false;
				}else{
				if ($bAudita[$idAccion]){seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], $idAccion, $DATA['saiu28id'], $sdetalle, $objDB);}
				$DATA['paso']=2;
				//Registrar en el inventario.
				$valores3000[2]=$iCodModulo;
				$valores3000[3]=$DATA['saiu28agno'];
				$valores3000[4]=$DATA['saiu28id'];
				if ($idSolicitantePrevio!=0){
					//Retirar al anterior.
					$valores3000[1]=$idSolicitantePrevio;
					f3000_Retirar($valores3000, $objDB, $bDebug);
					}
				if ($DATA['saiu28idsolicitante']!=0){
					$valores3000[1]=$DATA['saiu28idsolicitante'];
					$valores3000[5]=$iDiaIni;
					$valores3000[6]=$DATA['saiu28tiposolicitud'];
					$valores3000[7]=$DATA['saiu28temasolicitud'];
					$valores3000[8]=$DATA['saiu28estado'];
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
		list($sErrorCerrando, $sDebugCerrar)=f3028_Cerrar($DATA['saiu28id'], $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugCerrar;
		}
	//if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' InfoDepura<br>';}
	return array($DATA, $sError, $iTipoError, $bCerrando, $sErrorCerrando, $sDebug);
	}
function f3028_db_Eliminar($saiu28agno, $saiu28id, $objDB, $bDebug=false){
	$iCodModulo=3028;
	$bAudita[4]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3028='lg/lg_3028_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3028)){$mensajes_3028='lg/lg_3028_es.php';}
	require $mensajes_todas;
	require $mensajes_3028;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	$saiu28id=numeros_validar($saiu28id);
	// Traer los datos para hacer las validaciones.
	$sTabla28='saiu28mesaayuda_'.$saiu28agno;
	if ($sError==''){
		$sSQL='SELECT * FROM '.$sTabla28.' WHERE saiu28id='.$saiu28id.'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$filabase=$objDB->sf($tabla);
			}else{
			$sError='No se encuentra el registro solicitado {Ref: '.$saiu28id.'}';
			}
		}
	if ($sError==''){
		if (!seg_revisa_permiso($iCodModulo, 4, $objDB)){
			$sError=$ERR['4'];
			}
		}
	if ($sError==''){
		$sSQL='SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=3028';
		$tablaor=$objDB->ejecutasql($sSQL);
		while ($filaor=$objDB->sf($tablaor)){
			$sSQL='SELECT '.$filaor['campoid'].' FROM '.$filaor['tabla'].' WHERE '.$filaor['camporev'].'='.$_REQUEST['saiu28id'].' LIMIT 0, 1';
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
		if ($filabase['saiu28idsolicitante']!=0){
			//Retirar al anterior.
			$valores3000[1]=$filabase['saiu28idsolicitante'];
			$valores3000[2]=$iCodModulo;
			$valores3000[3]=$filabase['saiu28agno'];
			$valores3000[4]=$filabase['saiu28id'];
			f3000_Retirar($valores3000, $objDB, $bDebug);
			}
		//$sSQL='DELETE FROM saiu29anexos WHERE saiu29idsolicitud='.$filabase['saiu28id'].'';
		//$tabla=$objDB->ejecutasql($sSQL);
		//$sSQL='DELETE FROM saiu30anotaciones WHERE saiu30idsolicitud='.$filabase['saiu28id'].'';
		//$tabla=$objDB->ejecutasql($sSQL);
		//$sSQL='DELETE FROM saiu39cambioestmesa WHERE saiu39idsolicitud='.$filabase['saiu28id'].'';
		//$tabla=$objDB->ejecutasql($sSQL);
		$sWhere='saiu28id='.$saiu28id.'';
		//$sWhere='saiu28consec='.$filabase['saiu28consec'].' AND saiu28tiporadicado='.$filabase['saiu28tiporadicado'].' AND saiu28mes='.$filabase['saiu28mes'].' AND saiu28agno='.$filabase['saiu28agno'].'';
		$sSQL='DELETE FROM '.$sTabla28.' WHERE '.$sWhere.';';
		$result=$objDB->ejecutasql($sSQL);
		if ($result==false){
			$sError=$ERR['falla_eliminar'].' .. <!-- '.$sSQL.' -->';
			}else{
			if ($bAudita[4]){seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $saiu28id, $sWhere, $objDB);}
			}
		}
	return array($sError, $iTipoError, $sDebug);
	}
function f3028_TituloBusqueda(){
	return 'Busqueda de Mesa de ayuda';
	}
function f3028_ParametrosBusqueda(){
	$sParams='<label class="Label90">Nombre</label><label><input id="b3028nombre" name="b3028nombre" type="text" value="" onchange="paginarbusqueda()" /></label>';
	return $sParams;
	}
function f3028_JavaScriptBusqueda($iModuloBusca){
	$sRes='var sCampo=window.document.frmedita.scampobusca.value;
var params=new Array();
params[100]=sCampo;
params[101]=window.document.frmedita.paginabusqueda.value;
params[102]=window.document.frmedita.lppfbusqueda.value;
params[103]=window.document.frmedita.b3028nombre.value;
xajax_f'.$iModuloBusca.'_HtmlBusqueda(params);';
	return $sRes;
	}
function f3028_TablaDetalleBusquedas($aParametros, $objDB){
	$res='';
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3028='lg/lg_3028_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3028)){$mensajes_3028='lg/lg_3028_es.php';}
	require $mensajes_todas;
	require $mensajes_3028;
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
		return array($sLeyenda.'<input id="paginaf3028" name="paginaf3028" type="hidden" value="'.$pagina.'"/><input id="lppf3028" name="lppf3028" type="hidden" value="'.$lineastabla.'"/>', $sDebug);
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
	$sSQL='SELECT TB.saiu28agno, TB.saiu28mes, TB.saiu28tiporadicado, TB.saiu28consec, TB.saiu28id, TB.saiu28dia, TB.saiu28hora, TB.saiu28minuto, T9.saiu11nombre, T10.saiu27nombre, T11.unad11razonsocial AS C11_nombre, T12.bita07nombre, T13.saiu01titulo, T14.saiu02titulo, T15.saiu03titulo, T16.unad23nombre, T17.unad24nombre, T18.unad18nombre, T19.unad19nombre, T20.unad20nombre, T21.core12nombre, T22.core09nombre, T23.exte02nombre, TB.saiu28idpqrs, TB.saiu28detalle, TB.saiu28horafin, TB.saiu28minutofin, T30.unad11razonsocial AS C30_nombre, TB.saiu28tiemprespdias, TB.saiu28tiempresphoras, TB.saiu28tiemprespminutos, TB.saiu28solucion, TB.saiu28estado, TB.saiu28idsolicitante, T11.unad11tipodoc AS C11_td, T11.unad11doc AS C11_doc, TB.saiu28tipointeresado, TB.saiu28clasesolicitud, TB.saiu28tiposolicitud, TB.saiu28temasolicitud, TB.saiu28idzona, TB.saiu28idcentro, TB.saiu28codpais, TB.saiu28coddepto, TB.saiu28codciudad, TB.saiu28idescuela, TB.saiu28idprograma, TB.saiu28idperiodo, TB.saiu28idresponsable, T30.unad11tipodoc AS C30_td, T30.unad11doc AS C30_doc 
FROM saiu28mesaayuda AS TB, saiu11estadosol AS T9, unad11terceros AS T11, bita07tiposolicitante AS T12, saiu01claseser AS T13, saiu02tiposol AS T14, saiu03temasol AS T15, unad23zona AS T16, unad24sede AS T17, unad18pais AS T18, unad19depto AS T19, unad20ciudad AS T20, core12escuela AS T21, core09programa AS T22, exte02per_aca AS T23, unad11terceros AS T30 
WHERE '.$sSQLadd1.' TB.saiu28estado=T9.saiu11id AND TB.saiu28idsolicitante=T11.unad11id AND TB.saiu28tipointeresado=T12.bita07id AND TB.saiu28clasesolicitud=T13.saiu01id AND TB.saiu28tiposolicitud=T14.saiu02id AND TB.saiu28temasolicitud=T15.saiu03id AND TB.saiu28idzona=T16.unad23id AND TB.saiu28idcentro=T17.unad24id AND TB.saiu28codpais=T18.unad18codigo AND TB.saiu28coddepto=T19.unad19codigo AND TB.saiu28codciudad=T20.unad20codigo AND TB.saiu28idescuela=T21.core12id AND TB.saiu28idprograma=T22.core09id AND TB.saiu28idperiodo=T23.exte02id AND TB.saiu28idresponsable=T30.unad11id '.$sSQLadd.'
ORDER BY TB.saiu28agno, TB.saiu28mes, TB.saiu28tiporadicado, TB.saiu28consec';
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
			//return array(utf8_encode($sErrConsulta.'<input id="paginaf3028" name="paginaf3028" type="hidden" value="'.$pagina.'"/><input id="lppf3028" name="lppf3028" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
<td><b>'.$ETI['saiu28agno'].'</b></td>
<td><b>'.$ETI['saiu28mes'].'</b></td>
<td><b>'.$ETI['saiu28tiporadicado'].'</b></td>
<td><b>'.$ETI['saiu28consec'].'</b></td>
<td><b>'.$ETI['saiu28dia'].'</b></td>
<td><b>'.$ETI['saiu28hora'].'</b></td>
<td><b>'.$ETI['saiu28estado'].'</b></td>
<td colspan="2"><b>'.$ETI['saiu28idsolicitante'].'</b></td>
<td><b>'.$ETI['saiu28tipointeresado'].'</b></td>
<td><b>'.$ETI['saiu28clasesolicitud'].'</b></td>
<td><b>'.$ETI['saiu28tiposolicitud'].'</b></td>
<td><b>'.$ETI['saiu28temasolicitud'].'</b></td>
<td><b>'.$ETI['saiu28idzona'].'</b></td>
<td><b>'.$ETI['saiu28idcentro'].'</b></td>
<td><b>'.$ETI['saiu28codpais'].'</b></td>
<td><b>'.$ETI['saiu28coddepto'].'</b></td>
<td><b>'.$ETI['saiu28codciudad'].'</b></td>
<td><b>'.$ETI['saiu28idescuela'].'</b></td>
<td><b>'.$ETI['saiu28idprograma'].'</b></td>
<td><b>'.$ETI['saiu28idperiodo'].'</b></td>
<td><b>'.$ETI['saiu28idpqrs'].'</b></td>
<td><b>'.$ETI['saiu28detalle'].'</b></td>
<td><b>'.$ETI['saiu28horafin'].'</b></td>
<td colspan="2"><b>'.$ETI['saiu28idresponsable'].'</b></td>
<td><b>'.$ETI['saiu28tiemprespdias'].'</b></td>
<td><b>'.$ETI['saiu28tiempresphoras'].'</b></td>
<td><b>'.$ETI['saiu28tiemprespminutos'].'</b></td>
<td><b>'.$ETI['saiu28solucion'].'</b></td>
<td align="right">
'.html_paginador('paginabusqueda', $registros, $lineastabla, $pagina, 'paginarbusqueda()').'
'.html_lpp('lppfbusqueda', $lineastabla, 'paginarbusqueda()').'
</td>
</tr></thead>';
	$tlinea=1;
	while($filadet=$objDB->sf($tabladetalle)){
		$sPrefijo='<a href="javascript:Devuelve(\''.$filadet['saiu28id'].'\');">';
		$sSufijo='</a>';
		$tlinea++;
		$et_saiu28hora=html_TablaHoraMin($filadet['saiu28hora'], $filadet['saiu28minuto']);
		$et_saiu28horafin=html_TablaHoraMin($filadet['saiu28horafin'], $filadet['saiu28minutofin']);
		$res=$res.'<tr onmouseover="cambia_color_over(this);" onmouseout="cambia_color_out(this);">
<td>'.$sPrefijo.$filadet['saiu28agno'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu28mes'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu28tiporadicado'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu28consec'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu28dia'].$sSufijo.'</td>
<td>'.$sPrefijo.$et_saiu28hora.$sSufijo.'</td>
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
<td>'.$sPrefijo.$filadet['saiu28codpais'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu28coddepto'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu28codciudad'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['core12nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['core09nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['exte02nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu28idpqrs'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu28detalle'].$sSufijo.'</td>
<td>'.$sPrefijo.$et_saiu28horafin.$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['C30_td'].' '.$filadet['C30_doc'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['C30_nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu28tiemprespdias'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu28tiempresphoras'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu28tiemprespminutos'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu28solucion'].$sSufijo.'</td>
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