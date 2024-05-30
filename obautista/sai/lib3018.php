<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2020 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.25.3 martes, 14 de julio de 2020
--- 3018 saiu18telefonico
*/
/** Archivo lib3018.php.
* Libreria 3018 saiu18telefonico.
* @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
* @date martes, 14 de julio de 2020
*/
function f3018_HTMLComboV2_saiu18agno($objDB, $objCombos, $valor){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('saiu18agno', $valor, false, '{'.$ETI['msg_seleccione'].'}');
	//$objCombos->iAncho=450;
	$objCombos->sAccion='RevisaLlave();';
	$sSQL='SHOW TABLES LIKE "saiu18telefonico%"';
	//if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Total Periodo: Lista de contenedores: '.$sSQL.'<br>';}
	$tablac=$objDB->ejecutasql($sSQL);
	while($filac=$objDB->sf($tablac)){
		$sAgno=substr($filac[0], 17);
		$objCombos->addItem($sAgno, $sAgno);
	}
	$res=$objCombos->html('', $objDB);
	return $res;
	}
function f3018_HTMLComboV2_saiu18mes($objDB, $objCombos, $valor){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	/*
	$objCombos->nuevo('saiu18mes', $valor, false, '{'.$ETI['msg_seleccione'].'}');
	//$objCombos->iAncho=450;
	$objCombos->sAccion='RevisaLlave();';
	$sSQL='SELECT  AS id,  AS nombre FROM ';
	$res=$objCombos->html($sSQL, $objDB);
	*/
	$res=html_ComboMes('saiu18mes', $valor, false, 'RevisaLlave();');
	return $res;
	}
function f3018_HTMLComboV2_saiu18tiporadicado($objDB, $objCombos, $valor){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('saiu18tiporadicado', $valor, false, '{'.$ETI['msg_seleccione'].'}');
	//$objCombos->iAncho=450;
	$objCombos->sAccion='RevisaLlave();';
	$sSQL='SELECT saiu16id AS id, saiu16nombre AS nombre FROM saiu16tiporadicado WHERE saiu16id IN (1, 3) ORDER BY saiu16nombre';
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f3018_HTMLComboV2_saiu18tiposolicitud($objDB, $objCombos, $valor){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('saiu18tiposolicitud', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	$objCombos->sAccion='carga_combo_saiu18temasolicitud();';
	//$objCombos->iAncho=450;
	$sSQL='SELECT TB.saiu02id AS id, CONCAT(TB.saiu02titulo, " [", T1.saiu01titulo, "]") AS nombre 
	FROM saiu02tiposol AS TB, saiu01claseser AS T1 
	WHERE TB.saiu02id>0 AND TB.saiu02ordenllamada<9 AND TB.saiu02clasesol=T1.saiu01id 
	ORDER BY TB.saiu02ordenllamada, TB.saiu02titulo';
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f3018_HTMLComboV2_saiu18temasolicitud($objDB, $objCombos, $valor, $vrsaiu18tiposolicitud){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('saiu18temasolicitud', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	//$objCombos->iAncho=450;
	$sSQL='';
	if ((int)$vrsaiu18tiposolicitud==0){
		$objCombos->sAccion='carga_combo_saiu18tiposolicitud()';
		$sSQL='SELECT TB.saiu03id AS id, CONCAT(TB.saiu03titulo, " [", T2.saiu02titulo, "]") AS nombre 
		FROM saiu03temasol AS TB, saiu02tiposol AS T2 
		WHERE TB.saiu03id>0 AND TB.saiu03ordenllamada<9 AND TB.saiu03tiposol=T2.saiu02id
		ORDER BY TB.saiu03ordensoporte, TB.saiu03titulo';
		}else{
		$sSQL='SELECT saiu03id AS id, saiu03titulo AS nombre 
		FROM saiu03temasol 
		WHERE saiu03id>0 AND saiu03ordenllamada<9 AND saiu03tiposol='.$vrsaiu18tiposolicitud.'
		ORDER BY saiu03ordenllamada, saiu03titulo';
		}
	//if ((int)$vrsaiu18tiposolicitud!=0){}
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f3018_HTMLComboV2_saiu18idcentro($objDB, $objCombos, $valor, $vrsaiu18idzona){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$sCondi='';
	$objCombos->nuevo('saiu18idcentro', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	//$objCombos->iAncho=450;
	$sSQL='';
	if ((int)$vrsaiu18idzona!=0) {
		$sSQL='SELECT unad24id AS id, CONCAT(unad24nombre, CASE unad24activa WHEN "S" THEN "" ELSE " [INACTIVA]" END) AS nombre 
		FROM unad24sede 
		WHERE unad24idzona='.$vrsaiu18idzona.' AND unad24id>0 
		ORDER BY unad24activa DESC, unad24nombre';
	}
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f3018_HTMLComboV2_saiu18coddepto($objDB, $objCombos, $valor, $vrsaiu18codpais){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('saiu18coddepto', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	//$objCombos->iAncho=450;
	$objCombos->sAccion='carga_combo_saiu18codciudad()';
	$sSQL='';
	if ((int)$vrsaiu18codpais!=0){
		$sSQL='SELECT unad19codigo AS id, unad19nombre AS nombre FROM unad19depto WHERE unad19codpais="'.$vrsaiu18codpais.'" ORDER BY unad19nombre';
		}
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f3018_HTMLComboV2_saiu18codciudad($objDB, $objCombos, $valor, $vrsaiu18coddepto){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('saiu18codciudad', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	//$objCombos->iAncho=450;
	$sSQL='';
	if ((int)$vrsaiu18coddepto!=0){
		$sSQL='SELECT unad20codigo AS id, unad20nombre AS nombre FROM unad20ciudad WHERE unad20coddepto="'.$vrsaiu18coddepto.'" ORDER BY unad20nombre';
		}
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f3018_Combosaiu18tiposolicitud($aParametros){
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
		$html_saiu18tiposolicitud=f3018_HTMLComboV2_saiu18tiposolicitud($objDB, $objCombos, $idTipo);
		$html_saiu18temasolicitud=f3018_HTMLComboV2_saiu18temasolicitud($objDB, $objCombos, $idTema, $idTipo);
		$objDB->CerrarConexion();
		$objResponse=new xajaxResponse();
		$objResponse->assign('div_saiu18tiposolicitud', 'innerHTML', $html_saiu18tiposolicitud);
		$objResponse->assign('div_saiu18temasolicitud', 'innerHTML', $html_saiu18temasolicitud);
		$objResponse->call('$("#saiu18tiposolicitud").chosen()');
		$objResponse->call('$("#saiu18temasolicitud").chosen()');
		return $objResponse;
		}else{
		$objDB->CerrarConexion();
		}
	}
function f3018_Combosaiu18temasolicitud($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$objCombos=new clsHtmlCombos();
	$html_saiu18temasolicitud=f3018_HTMLComboV2_saiu18temasolicitud($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_saiu18temasolicitud', 'innerHTML', $html_saiu18temasolicitud);
	$objResponse->call('$("#saiu18temasolicitud").chosen({width:"100%"})');
	return $objResponse;
	}
function f3018_HTMLComboV2_saiu18idprograma($objDB, $objCombos, $valor, $vrsaiu18idescuela){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('saiu18idprograma', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	$objCombos->addItem('0', $ETI['msg_na']);
	//$objCombos->iAncho=450;
	$sCondiEscuela=' AND TB.core09idescuela="'.$vrsaiu18idescuela.'"';
	$sTabla2='';
	$sCampos2='';
	if ($vrsaiu18idescuela==''){
		$sCondiEscuela=' AND TB.core09idescuela=T12.core12id';
		$sTabla2=', core12escuela AS T12';
		$sCampos2=', " [", T12.core12sigla, "]"';
		}
	$sSQL='SELECT TB.core09id AS id, CONCAT(TB.core09nombre, " [", TB.core09codigo, "]"'.$sCampos2.') AS nombre FROM core09programa AS TB'.$sTabla2.' WHERE TB.core09id>0'.$sCondiEscuela;
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f3018_Combosaiu18idcentro($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$objCombos=new clsHtmlCombos();
	$html_saiu18idcentro=f3018_HTMLComboV2_saiu18idcentro($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_saiu18idcentro', 'innerHTML', $html_saiu18idcentro);
	$objResponse->call('$("#saiu18idcentro").chosen({width:"100%"})');
	return $objResponse;
	}
function f3018_Combosaiu18coddepto($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$objCombos=new clsHtmlCombos();
	$html_saiu18coddepto=f3018_HTMLComboV2_saiu18coddepto($objDB, $objCombos, '', $aParametros[0]);
	$html_saiu18codciudad=f3018_HTMLComboV2_saiu18codciudad($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_saiu18coddepto', 'innerHTML', $html_saiu18coddepto);
	$objResponse->call('$("#saiu18coddepto").chosen({width:"100%"})');
	$objResponse->assign('div_saiu18codciudad', 'innerHTML', $html_saiu18codciudad);
	$objResponse->call('$("#saiu18codciudad").chosen({width:"100%"})');
	return $objResponse;
	}
function f3018_Combosaiu18codciudad($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$objCombos=new clsHtmlCombos();
	$html_saiu18codciudad=f3018_HTMLComboV2_saiu18codciudad($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_saiu18codciudad', 'innerHTML', $html_saiu18codciudad);
	$objResponse->call('$("#saiu18codciudad").chosen({width:"100%"})');
	return $objResponse;
	}
function f3018_Combosaiu18idprograma($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$objCombos=new clsHtmlCombos();
	$html_saiu18idprograma=f3018_HTMLComboV2_saiu18idprograma($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_saiu18idprograma', 'innerHTML', $html_saiu18idprograma);
	$objResponse->call('$("#saiu18idprograma").chosen({width:"100%"})');
	return $objResponse;
	}
function f3018_ExisteDato($datos){
	if(!is_array($datos)){$datos=json_decode(str_replace('\"','"',$datos),true);}
	$_SESSION['u_ultimominuto']=iminutoavance();
	$bHayLlave=true;
	$saiu18agno=numeros_validar($datos[1]);
	if ($saiu18agno==''){$bHayLlave=false;}
	$saiu18mes=numeros_validar($datos[2]);
	if ($saiu18mes==''){$bHayLlave=false;}
	$saiu18tiporadicado=numeros_validar($datos[3]);
	if ($saiu18tiporadicado==''){$bHayLlave=false;}
	$saiu18consec=numeros_validar($datos[4]);
	if ($saiu18consec==''){$bHayLlave=false;}
	if ($bHayLlave){
		require './app.php';
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		$sSQL='SELECT 1 FROM saiu18telefonico_'.$saiu18agno.' WHERE saiu18agno='.$saiu18agno.' AND saiu18mes='.$saiu18mes.' AND saiu18tiporadicado='.$saiu18tiporadicado.' AND saiu18consec='.$saiu18consec.'';
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
function f3018_Busquedas($aParametros){
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3018='lg/lg_3018_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3018)){$mensajes_3018='lg/lg_3018_es.php';}
	require $mensajes_todas;
	require $mensajes_3018;
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
		case 'saiu18idsolicitante':
		require $APP->rutacomun.'lib111.php';
		$sTabla=f111_TablaDetalleBusquedas($aParametrosB, $objDB);
		$sTitulo='Busqueda de terceros';
		$sParams=f111_ParametrosBusqueda();
		$sJavaBusqueda=f111_JavaScriptBusqueda(3018);
		break;
		case 'saiu18idresponsable':
		require $APP->rutacomun.'lib111.php';
		$sTabla=f111_TablaDetalleBusquedas($aParametrosB, $objDB);
		$sTitulo='Busqueda de terceros';
		$sParams=f111_ParametrosBusqueda();
		$sJavaBusqueda=f111_JavaScriptBusqueda(3018);
		break;
		}
	$sTitulo='<h2>'.$ETI['titulo_3018'].' - '.$sTitulo.'</h2>';
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97titulo', 'innerHTML', $sTitulo);
	$objResponse->assign('div_97params', 'innerHTML', $sParams);
	$objResponse->assign('div_97tabla', 'innerHTML', $sTabla);
	$objResponse->setFunction('paginarbusqueda','',$sJavaBusqueda);
	$objResponse->call('expandesector(97)');
	return $objResponse;
	}
function f3018_HtmlBusqueda($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$sError='';
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$sDetalle='';
	switch($aParametros[100]){
		case 'saiu18idsolicitante':
		require $APP->rutacomun.'lib111.php';
		$sDetalle=f111_TablaDetalleBusquedas($aParametros, $objDB);
		break;
		case 'saiu18idresponsable':
		require $APP->rutacomun.'lib111.php';
		$sDetalle=f111_TablaDetalleBusquedas($aParametros, $objDB);
		break;
		}
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97tabla', 'innerHTML', $sDetalle);
	return $objResponse;
	}
function f3018_TablaDetalleV2($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3018='lg/lg_3018_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3018)){$mensajes_3018='lg/lg_3018_es.php';}
	require $mensajes_todas;
	require $mensajes_3018;
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
		return array($sLeyenda.'<input id="paginaf3018" name="paginaf3018" type="hidden" value="'.$pagina.'"/><input id="lppf3018" name="lppf3018" type="hidden" value="'.$lineastabla.'"/>', $sDebug);
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
		$sSQLadd = $sSQLadd . ' AND TB.saiu18estado=' . $iEstado . '';
	}
	switch($bListar){
		case 1:
			$sSQLadd=$sSQLadd.' AND TB.saiu18idresponsable=' . $idTercero. '';		
			break;
		case 2:
			$sSQLadd=$sSQLadd.' AND TB.saiu18idresponsablecaso=' . $idTercero. '';		
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
				$sSQLadd = $sSQLadd . ' AND TB.saiu18idequipocaso IN (' . $sEquipos . ')';
			} else {
				$sSQLadd = $sSQLadd . ' AND TB.saiu18idresponsablecaso=' . $idTercero . '';
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
		$sSQLadd = $sSQLadd . ' AND TB.saiu18tiposolicitud=' . $bcategoria . '';
	}
	if ($btema !== '') {
		$sSQLadd = $sSQLadd . ' AND TB.saiu18temasolicitud=' . $btema . '';
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
FROM saiu18telefonico_'.$iAgno.' AS TB, unad11terceros AS T12
WHERE '.$sSQLadd1.' TB.saiu18idsolicitante=T12.unad11id '.$sSQLadd.'';
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
	//, TB.saiu18idpqrs, TB.saiu18detalle, TB.saiu18horafin, TB.saiu18minutofin, TB.saiu18paramercadeo, TB.saiu18tiemprespdias, TB.saiu18tiempresphoras, TB.saiu18tiemprespminutos, TB.saiu18tipointeresado, TB.saiu18clasesolicitud, TB.saiu18tiposolicitud, TB.saiu18temasolicitud, TB.saiu18idzona, TB.saiu18idcentro, TB.saiu18codpais, TB.saiu18coddepto, TB.saiu18codciudad, TB.saiu18idescuela, TB.saiu18idprograma, TB.saiu18idperiodo, TB.saiu18idresponsable
	$sSQL='SELECT TB.saiu18agno, TB.saiu18mes, TB.saiu18consec, TB.saiu18id, TB.saiu18dia, TB.saiu18hora, TB.saiu18minuto, 
T12.unad11razonsocial AS C12_nombre, TB.saiu18tiposolicitud, saiu18temasolicitud, TB.saiu18estado, T12.unad11tipodoc AS C12_td, 
T12.unad11doc AS C12_doc, TB.saiu18idsolicitante, TB.saiu18tiporadicado, TB.saiu18solucion 
FROM saiu18telefonico_'.$iAgno.' AS TB, unad11terceros AS T12
WHERE '.$sSQLadd1.' TB.saiu18idsolicitante=T12.unad11id '.$sSQLadd.'
ORDER BY TB.saiu18agno DESC, TB.saiu18mes DESC, TB.saiu18dia DESC, TB.saiu18tiporadicado, TB.saiu18consec DESC';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_3018" name="consulta_3018" type="hidden" value="'.$sSQLlista.'"/>
<input id="titulos_3018" name="titulos_3018" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objDB->ejecutasql($sSQL.$sLimite);
	if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 3018: '.$sSQL.$sLimite.'<br>';}
	if ($tabladetalle==false){
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		//$sLeyenda=$sSQL;
		}else{
		if (!$bGigante){
			$registros=$objDB->nf($tabladetalle);
			if ($registros==0){
				//return array(cadena_codificar($sErrConsulta.'<input id="paginaf3018" name="paginaf3018" type="hidden" value="'.$pagina.'"/><input id="lppf3018" name="lppf3018" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
<td><b>'.$ETI['saiu18consec'].'</b></td>
<td><b>'.$ETI['saiu18estado'].'</b></td>
<td colspan="2"><b>'.$ETI['saiu18idsolicitante'].'</b></td>
<td><b>'.$ETI['saiu18tiposolicitud'].'</b></td>
<td><b>'.$ETI['saiu18temasolicitud'].'</b></td>
<td><b>'.$ETI['saiu18solucion'].'</b></td>
<td align="right">
'.html_paginador('paginaf3018', $registros, $lineastabla, $pagina, 'paginarf3018()').'
'.html_lpp('lppf3018', $lineastabla, 'paginarf3018()').'
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
		$et_saiu18hora=html_TablaHoraMin($filadet['saiu18hora'], $filadet['saiu18minuto']);
		$et_saiu18idsolicitante_doc='';
		$et_saiu18idsolicitante_nombre='';
		if ($filadet['saiu18idsolicitante']!=0){
			$et_saiu18idsolicitante_doc=$sPrefijo.$filadet['C12_td'].' '.$filadet['C12_doc'].$sSufijo;
			$et_saiu18idsolicitante_nombre=$sPrefijo.cadena_notildes($filadet['C12_nombre']).$sSufijo;
			}
		$sEstado = '';
		if (isset($aEstado[$filadet['saiu18estado']])==0) {
			$sEstado = $ETI['definir'];
		} else {
			$sEstado = $aEstado[$filadet['saiu18estado']];
		}
		$sCategoria = '';
		if (isset($aCategoria[$filadet['saiu18tiposolicitud']])==0) {
			$sCategoria = $ETI['definir'];
		} else {
			$sCategoria = $aCategoria[$filadet['saiu18tiposolicitud']];
		}
		$sTema = '';
		if (isset($aTema[$filadet['saiu18temasolicitud']])==0) {
			$sTema = $ETI['definir'];
		} else {
			$sTema = $aTema[$filadet['saiu18temasolicitud']];
		}
		$sSolucion = '';
		if (isset($asaiu18solucion[$filadet['saiu18solucion']])==0) {
			$sSolucion = $ETI['definir'];
		} else {
			$sSolucion = $asaiu18solucion[$filadet['saiu18solucion']];
		}
		/*
		$et_saiu18horafin=html_TablaHoraMin($filadet['saiu18horafin'], $filadet['saiu18minutofin']);
		$et_saiu18idresponsable_doc='';
		$et_saiu18idresponsable_nombre='';
		if ($filadet['saiu18idresponsable']!=0){
			$et_saiu18idresponsable_doc=$sPrefijo.$filadet['C31_td'].' '.$filadet['C31_doc'].$sSufijo;
			$et_saiu18idresponsable_nombre=$sPrefijo.cadena_notildes($filadet['C31_nombre']).$sSufijo;
			}
		*/
		if ($bAbierta){
			$sLink='<a href="javascript:cargaridf3018('.$filadet['saiu18agno'].','.$filadet['saiu18id'].')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$et_fecha=fecha_armar($filadet['saiu18dia'], $filadet['saiu18mes'], $filadet['saiu18agno']);
		$res=$res.'<tr'.$sClass.'>
<td>'.$sPrefijo.$et_fecha.$sSufijo.'</td>
<td>'.$sPrefijo.$et_saiu18hora.$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu18consec'].$sSufijo.'</td>
<td>'.$sPrefijo.$sEstado.$sSufijo.'</td>
<td>'.$et_saiu18idsolicitante_doc.'</td>
<td>'.$et_saiu18idsolicitante_nombre.'</td>
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
function f3018_HtmlTabla($aParametros){
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
	list($sDetalle, $sDebugTabla)=f3018_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f3018detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f3018_db_CargarPadre($DATA, $objDB, $bDebug=false){
	$sError='';
	$iTipoError=0;
	$sDebug='';
	require './app.php';
	$DATA['saiu18idsolicitante_td']=$APP->tipo_doc;
	$DATA['saiu18idsolicitante_doc']='';
	$DATA['saiu18idresponsable_td']=$APP->tipo_doc;
	$DATA['saiu18idresponsable_doc']='';
	if ($DATA['paso']==1){
		$sSQLcondi='saiu18agno='.$DATA['saiu18agno'].' AND saiu18mes='.$DATA['saiu18mes'].' AND saiu18tiporadicado='.$DATA['saiu18tiporadicado'].' AND saiu18consec='.$DATA['saiu18consec'].'';
		}else{
		$sSQLcondi='saiu18id='.$DATA['saiu18id'].'';
		}
	$sSQL='SELECT * FROM saiu18telefonico_'.$DATA['saiu18agno'].' WHERE '.$sSQLcondi;
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$DATA['saiu18agno']=$fila['saiu18agno'];
		$DATA['saiu18mes']=$fila['saiu18mes'];
		$DATA['saiu18tiporadicado']=$fila['saiu18tiporadicado'];
		$DATA['saiu18consec']=$fila['saiu18consec'];
		$DATA['saiu18id']=$fila['saiu18id'];
		$DATA['saiu18dia']=$fila['saiu18dia'];
		$DATA['saiu18hora']=$fila['saiu18hora'];
		$DATA['saiu18minuto']=$fila['saiu18minuto'];
		$DATA['saiu18estado']=$fila['saiu18estado'];
		$DATA['saiu18idtelefono']=$fila['saiu18idtelefono'];
		$DATA['saiu18numtelefono']=$fila['saiu18numtelefono'];
		$DATA['saiu18idsolicitante']=$fila['saiu18idsolicitante'];
		$DATA['saiu18tipointeresado']=$fila['saiu18tipointeresado'];
		$DATA['saiu18clasesolicitud']=$fila['saiu18clasesolicitud'];
		$DATA['saiu18tiposolicitud']=$fila['saiu18tiposolicitud'];
		$DATA['saiu18temasolicitud']=$fila['saiu18temasolicitud'];
		$DATA['saiu18idzona']=$fila['saiu18idzona'];
		$DATA['saiu18idcentro']=$fila['saiu18idcentro'];
		$DATA['saiu18codpais']=$fila['saiu18codpais'];
		$DATA['saiu18coddepto']=$fila['saiu18coddepto'];
		$DATA['saiu18codciudad']=$fila['saiu18codciudad'];
		$DATA['saiu18idescuela']=$fila['saiu18idescuela'];
		$DATA['saiu18idprograma']=$fila['saiu18idprograma'];
		$DATA['saiu18idperiodo']=$fila['saiu18idperiodo'];
		$DATA['saiu18numorigen']=$fila['saiu18numorigen'];
		$DATA['saiu18idpqrs']=$fila['saiu18idpqrs'];
		$DATA['saiu18detalle']=$fila['saiu18detalle'];
		$DATA['saiu18horafin']=$fila['saiu18horafin'];
		$DATA['saiu18minutofin']=$fila['saiu18minutofin'];
		$DATA['saiu18paramercadeo']=$fila['saiu18paramercadeo'];
		$DATA['saiu18idresponsable']=$fila['saiu18idresponsable'];
		$DATA['saiu18tiemprespdias']=$fila['saiu18tiemprespdias'];
		$DATA['saiu18tiempresphoras']=$fila['saiu18tiempresphoras'];
		$DATA['saiu18tiemprespminutos']=$fila['saiu18tiemprespminutos'];
		$DATA['saiu18solucion']=$fila['saiu18solucion'];
		$DATA['saiu18idcaso']=$fila['saiu18idcaso'];
		$DATA['saiu18respuesta']=$fila['saiu18respuesta'];
		$bcargo=true;
		$DATA['paso']=2;
		$DATA['boculta3018']=0;
		$bLimpiaHijos=true;
		}else{
		$DATA['paso']=0;
		}
	return array($DATA, $sError, $iTipoError, $sDebug);
	}
function f3018_Cerrar($saiu18id, $objDB, $bDebug=false){
	$sInfo='';
	$sDebug='';
	return array($sInfo, $sDebug);
	}
function f3018_db_GuardarV2($DATA, $objDB, $bDebug=false){
	$iCodModulo=3018;
	$bAudita[2]=true;
	$bAudita[3]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3018='lg/lg_3018_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3018)){$mensajes_3018='lg/lg_3018_es.php';}
	require $mensajes_todas;
	require $mensajes_3018;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	$bCerrando=false;
	$sErrorCerrando='';
	// -- Se inicia validando todas las posibles entradas de usuario.
	/*
	if (isset($DATA['saiu18agno'])==0){$DATA['saiu18agno']='';}
	if (isset($DATA['saiu18mes'])==0){$DATA['saiu18mes']='';}
	if (isset($DATA['saiu18tiporadicado'])==0){$DATA['saiu18tiporadicado']='';}
	if (isset($DATA['saiu18consec'])==0){$DATA['saiu18consec']='';}
	if (isset($DATA['saiu18id'])==0){$DATA['saiu18id']='';}
	if (isset($DATA['saiu18dia'])==0){$DATA['saiu18dia']='';}
	if (isset($DATA['saiu18hora'])==0){$DATA['saiu18hora']='';}
	if (isset($DATA['saiu18minuto'])==0){$DATA['saiu18minuto']='';}
	if (isset($DATA['saiu18idtelefono'])==0){$DATA['saiu18idtelefono']='';}
	if (isset($DATA['saiu18numtelefono'])==0){$DATA['saiu18numtelefono']='';}
	if (isset($DATA['saiu18idsolicitante'])==0){$DATA['saiu18idsolicitante']='';}
	if (isset($DATA['saiu18tipointeresado'])==0){$DATA['saiu18tipointeresado']='';}
	if (isset($DATA['saiu18clasesolicitud'])==0){$DATA['saiu18clasesolicitud']='';}
	if (isset($DATA['saiu18tiposolicitud'])==0){$DATA['saiu18tiposolicitud']='';}
	if (isset($DATA['saiu18temasolicitud'])==0){$DATA['saiu18temasolicitud']='';}
	if (isset($DATA['saiu18idzona'])==0){$DATA['saiu18idzona']='';}
	if (isset($DATA['saiu18idcentro'])==0){$DATA['saiu18idcentro']='';}
	if (isset($DATA['saiu18codpais'])==0){$DATA['saiu18codpais']='';}
	if (isset($DATA['saiu18coddepto'])==0){$DATA['saiu18coddepto']='';}
	if (isset($DATA['saiu18codciudad'])==0){$DATA['saiu18codciudad']='';}
	if (isset($DATA['saiu18idescuela'])==0){$DATA['saiu18idescuela']='';}
	if (isset($DATA['saiu18idprograma'])==0){$DATA['saiu18idprograma']='';}
	if (isset($DATA['saiu18idperiodo'])==0){$DATA['saiu18idperiodo']='';}
	if (isset($DATA['saiu18numorigen'])==0){$DATA['saiu18numorigen']='';}
	if (isset($DATA['saiu18detalle'])==0){$DATA['saiu18detalle']='';}
	if (isset($DATA['saiu18horafin'])==0){$DATA['saiu18horafin']='';}
	if (isset($DATA['saiu18minutofin'])==0){$DATA['saiu18minutofin']='';}
	if (isset($DATA['saiu18paramercadeo'])==0){$DATA['saiu18paramercadeo']='';}
	if (isset($DATA['saiu18idresponsable'])==0){$DATA['saiu18idresponsable']='';}
	if (isset($DATA['saiu18solucion'])==0){$DATA['saiu18solucion']='';}
	if (isset($DATA['saiu18respuesta'])==0){$DATA['saiu18respuesta']='';}
	*/
	$DATA['saiuid']=18;
	$DATA['saiu18agno']=numeros_validar($DATA['saiu18agno']);
	$DATA['saiu18mes']=numeros_validar($DATA['saiu18mes']);
	$DATA['saiu18tiporadicado']=numeros_validar($DATA['saiu18tiporadicado']);
	$DATA['saiu18consec']=numeros_validar($DATA['saiu18consec']);
	$DATA['saiu18dia']=numeros_validar($DATA['saiu18dia']);
	$DATA['saiu18hora']=numeros_validar($DATA['saiu18hora']);
	$DATA['saiu18minuto']=numeros_validar($DATA['saiu18minuto']);
	$DATA['saiu18idtelefono']=numeros_validar($DATA['saiu18idtelefono']);
	$DATA['saiu18numtelefono']=htmlspecialchars(trim($DATA['saiu18numtelefono']));
	$DATA['saiu18tipointeresado']=numeros_validar($DATA['saiu18tipointeresado']);
	$DATA['saiu18clasesolicitud']=numeros_validar($DATA['saiu18clasesolicitud']);
	$DATA['saiu18tiposolicitud']=numeros_validar($DATA['saiu18tiposolicitud']);
	$DATA['saiu18temasolicitud']=numeros_validar($DATA['saiu18temasolicitud']);
	$DATA['saiu18idzona']=numeros_validar($DATA['saiu18idzona']);
	$DATA['saiu18idcentro']=numeros_validar($DATA['saiu18idcentro']);
	$DATA['saiu18codpais']=htmlspecialchars(trim($DATA['saiu18codpais']));
	$DATA['saiu18coddepto']=htmlspecialchars(trim($DATA['saiu18coddepto']));
	$DATA['saiu18codciudad']=htmlspecialchars(trim($DATA['saiu18codciudad']));
	$DATA['saiu18idescuela']=numeros_validar($DATA['saiu18idescuela']);
	$DATA['saiu18idprograma']=numeros_validar($DATA['saiu18idprograma']);
	$DATA['saiu18idperiodo']=numeros_validar($DATA['saiu18idperiodo']);
	$DATA['saiu18numorigen']=htmlspecialchars(trim($DATA['saiu18numorigen']));
	$DATA['saiu18detalle']=htmlspecialchars(trim($DATA['saiu18detalle']));
	$DATA['saiu18fechafin']=numeros_validar($DATA['saiu18fechafin']);
	$DATA['saiu18horafin']=numeros_validar($DATA['saiu18horafin']);
	$DATA['saiu18minutofin']=numeros_validar($DATA['saiu18minutofin']);
	$DATA['saiu18paramercadeo']=numeros_validar($DATA['saiu18paramercadeo']);
	$DATA['saiu18solucion']=numeros_validar($DATA['saiu18solucion']);
	$DATA['saiu18respuesta']=htmlspecialchars(trim($DATA['saiu18respuesta']));
	$DATA['saiu18idcaso']=numeros_validar($DATA['saiu18idcaso']);
	$DATA['saiu18fecharespcaso']=numeros_validar($DATA['saiu18fecharespcaso']);
	$DATA['saiu18horarespcaso']=numeros_validar($DATA['saiu18horarespcaso']);
	$DATA['saiu18minrespcaso']=numeros_validar($DATA['saiu18minrespcaso']);
	$DATA['saiu18idunidadcaso']=numeros_validar($DATA['saiu18idunidadcaso']);
	$DATA['saiu18idequipocaso']=numeros_validar($DATA['saiu18idequipocaso']);
	$DATA['saiu18idsupervisorcaso']=numeros_validar($DATA['saiu18idsupervisorcaso']);
	$DATA['saiu18idresponsablecaso']=numeros_validar($DATA['saiu18idresponsablecaso']);
	$DATA['saiu18numref']=htmlspecialchars(trim($DATA['saiu18numref']));
	// -- Se inicializan las variables que puedan pasar vacias {Especialmente números}.
	//if ($DATA['saiu18dia']==''){$DATA['saiu18dia']=0;}
	//if ($DATA['saiu18hora']==''){$DATA['saiu18hora']=0;}
	//if ($DATA['saiu18minuto']==''){$DATA['saiu18minuto']=0;}
	if ($DATA['saiu18estado']==''){$DATA['saiu18estado']=0;}
	if ($DATA['saiu18estadoorigen']==''){$DATA['saiu18estadoorigen']=0;}
	//if ($DATA['saiu18idtelefono']==''){$DATA['saiu18idtelefono']=0;}	
	//if ($DATA['saiu18tipointeresado']==''){$DATA['saiu18tipointeresado']=0;}
	if ($DATA['saiu18idpqrs']==''){$DATA['saiu18idpqrs']=0;}
	//if ($DATA['saiu18paramercadeo']==''){$DATA['saiu18paramercadeo']=0;}
	//if ($DATA['saiu18solucion']==''){$DATA['saiu18solucion']=0;}
	// -- Seccion para validar los posibles causales de error.
	$sSepara=', ';
	$bConCierre=false;
	$bEnviaEncuesta=false;
	$bEnviaCaso=false;
	if ($DATA['saiu18temasolicitud']==''){$sError=$ERR['saiu18temasolicitud'].$sSepara.$sError;}
	if ($DATA['saiu18tiposolicitud']==''){$sError=$ERR['saiu18tiposolicitud'].$sSepara.$sError;}
	// if ($DATA['saiu18clasesolicitud']==''){$sError=$ERR['saiu18clasesolicitud'].$sSepara.$sError;}
	if ($DATA['saiu18tipointeresado']==''){$sError=$ERR['saiu18tipointeresado'].$sSepara.$sError;}
	if ($DATA['saiu18idsolicitante']==0){$sError=$ERR['saiu18idsolicitante'].$sSepara.$sError;}
	if ($DATA['saiu18minuto']==''){$sError=$ERR['saiu18minuto'].$sSepara.$sError;}
	if ($DATA['saiu18hora']==''){$sError=$ERR['saiu18hora'].$sSepara.$sError;}
	if ($DATA['saiu18dia']==''){$sError=$ERR['saiu18dia'].$sSepara.$sError;}
	if ($DATA['saiu18mes']==''){$sError=$ERR['saiu18mes'].$sSepara.$sError;}
	if ($DATA['saiu18agno']==''){$sError=$ERR['saiu18agno'].$sSepara.$sError;}
	if ($DATA['saiu18idcentro']==''){$sError=$ERR['saiu18idcentro'].$sSepara.$sError;}
	if ($DATA['saiu18idzona']==''){$sError=$ERR['saiu18idzona'].$sSepara.$sError;}
	if ($DATA['saiu18detalle']==''){$sError=$ERR['saiu18detalle'].$sSepara.$sError;}
	if ($DATA['saiu18fecharespcaso']==''){$DATA['saiu18fecharespcaso']=0;}
	if ($DATA['saiu18estado']==1 || $DATA['saiu18estado']==7){
		if ($DATA['saiu18estado']==7) {
			$bConCierre=true;
		}
		if ($DATA['saiu18solucion']=='') {
			$sError=$ERR['saiu18solucion'].$sSepara.$sError;
			}else{
			if ((int)$DATA['saiu18solucion']==0){
				$sError=$ERR['saiu18solucion_proceso'].$sSepara.$sError;
				}
			}
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Estado: '.$DATA['saiu18estado']. ' - Solución: '. $DATA['saiu18solucion'] .'<br>';}
		if ($DATA['saiu18idresponsable']==0){$sError=$ERR['saiu18idresponsable'].$sSepara.$sError;}
		if ($DATA['saiu18paramercadeo']==''){$sError=$ERR['saiu18paramercadeo'].$sSepara.$sError;}
		//if ($DATA['saiu18minutofin']==''){$sError=$ERR['saiu18minutofin'].$sSepara.$sError;}
		//if ($DATA['saiu18horafin']==''){$sError=$ERR['saiu18horafin'].$sSepara.$sError;}
		//if ($DATA['saiu18numorigen']==''){$sError=$ERR['saiu18numorigen'].$sSepara.$sError;}
		if ($DATA['saiu18idperiodo']==''){$sError=$ERR['saiu18idperiodo'].$sSepara.$sError;}
		if ($DATA['saiu18idprograma']==''){$sError=$ERR['saiu18idprograma'].$sSepara.$sError;}
		if ($DATA['saiu18idescuela']==''){$sError=$ERR['saiu18idescuela'].$sSepara.$sError;}
		if ($DATA['saiu18codciudad']==''){$sError=$ERR['saiu18codciudad'].$sSepara.$sError;}
		if ($DATA['saiu18coddepto']==''){$sError=$ERR['saiu18coddepto'].$sSepara.$sError;}
		if ($DATA['saiu18codpais']==''){$sError=$ERR['saiu18codpais'].$sSepara.$sError;}
		//if ($DATA['saiu18hora']==''){$DATA['saiu18hora']=fecha_hora();}
		if ($DATA['saiu18solucion']==1){ // Resuelto en la atención
			if ($DATA['saiu18respuesta']==''){$sError=$ERR['saiu18respuesta'].$sSepara.$sError;}
		}
		if ($DATA['saiu18solucion']==3) { // Se inicia caso
			if ($DATA['saiu18temasolicitud'] != $DATA['saiu18temasolicitudorigen']) {
				$DATA['saiu18idresponsablecaso']=0;
			}
			if ($DATA['saiu18idresponsablecaso']==0) {
				list($DATA['saiu18idunidadcaso'], $DATA['saiu18idequipocaso'], $DATA['saiu18idsupervisorcaso'], $DATA['saiu18idresponsablecaso'], $saiu18tiemprespdias, $saiu18tiempresphoras, $sErrorF, $iTipoError, $sDebugF) = f3018_ConsultaResponsable($DATA, $objDB, $bDebug);
				if ($sErrorF!=''){$sError=$sError.'<br>'.$sErrorF;}
				if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta Responsable: '.$sDebugF.'<br>';}
				if ($DATA['saiu18idunidadcaso']==0){$sError=$ERR['saiu18idunidadcaso'].$sSepara.$sError;}
				if ($DATA['saiu18idequipocaso']==0){$sError=$ERR['saiu18idequipocaso'].$sSepara.$sError;}
				if ($DATA['saiu18idsupervisorcaso']==0){$sError=$ERR['saiu18idsupervisorcaso'].$sSepara.$sError;}
				if ($DATA['saiu18idresponsablecaso']==0){$sError=$ERR['saiu18idresponsablecaso'].$sSepara.$sError;}
				if ($sError!='') {
					$DATA['saiu18idunidadcaso']=0;
					$DATA['saiu18idequipocaso']=0;
					$DATA['saiu18idsupervisorcaso']=0;
					$DATA['saiu18idresponsablecaso']=0;
				}
			}
		}
		if ($DATA['saiu18idtelefono']==''){
			$sError=$ERR['saiu18idtelefono'].$sSepara.$sError;
		}else{
			if ($DATA['saiu18idtelefono']=='-1'){
				if ($DATA['saiu18numtelefono']==''){$sError=$ERR['saiu18numtelefono'].$sSepara.$sError;}
			}
		}	
		if ($sError=='') {
			if ($DATA['saiu18solucion']==5) { // Se inicia PQRS
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
				$aParams['saiu05idsolicitante']=$DATA['saiu18idsolicitante'];
				$aParams['saiu05tipointeresado']=$DATA['saiu18tipointeresado'];
				$aParams['saiu05detalle']=$DATA['saiu18detalle'];
				$aParams['saiu05idtiposolorigen']=$DATA['saiu18tiposolicitud'];
				$aParams['saiu05idtemaorigen']=$DATA['saiu18temasolicitud'];
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
					$DATA['saiu18idpqrs']=$aParams['saiu05id'];
					$DATA['saiu18numref']=$aParams['saiu05numref'];
					if ($DATA['saiu18idpqrs']==-1){$sError=$ERR['saiu18idpqrs'].$sSepara.$sError;}
					if ($DATA['saiu18numref']==''){$sError=$ERR['saiu18numref'].$sSepara.$sError;}
				}
			}
		}
		if ($sError!=''){
			if ($DATA['saiu18estado']!=1) {	// Asignado
				$DATA['saiu18estado']=2;	// En tramite
			}
		}
		$sErrorCerrando=$sError;
		//Fin de las valiaciones NO LLAVE.
		}
	if ($sError==''){
		switch($DATA['saiu18estado']){
			case 1: //Caso Asignado
			break;
			case 7: //Logra cerrar			
			switch($DATA['saiu18solucion']){
				case 1: // Resuelto en la atención
				case 5: // Se inicia PQRS
				$DATA['saiu18fecharespcaso']=0;
				$DATA['saiu18fechafin']=fecha_DiaMod();
				$DATA['saiu18horafin']=fecha_hora();
				$DATA['saiu18minutofin']=fecha_minuto();
				$bEnviaEncuesta=true;
				break;
				case 3: // Se inicia caso
				if ($DATA['saiu18estadoorigen']==1) {
					if ($DATA['saiu18respuesta']==''){
						$sError=$ERR['saiu18respuesta'].$sSepara.$sError;
					} else {
						$DATA['saiu18fecharespcaso']=fecha_DiaMod();
						$DATA['saiu18horarespcaso']=fecha_hora();
						$DATA['saiu18minrespcaso']=fecha_minuto();
						$bEnviaEncuesta=true;
					}
				} else {
					$DATA['saiu18fecharespcaso']=0;
					$DATA['saiu18fechafin']=fecha_DiaMod();
					$DATA['saiu18horafin']=fecha_hora();
					$DATA['saiu18minutofin']=fecha_minuto();
					$iDiaIni=($DATA['saiu18agno']*10000)+($DATA['saiu18mes']*100)+$DATA['saiu18dia'];
					list($DATA['saiu18tiemprespdias'], $DATA['saiu18tiempresphoras'], $DATA['saiu18tiemprespminutos'])=Tiempo_MinutosCalendario($iDiaIni, $DATA['saiu18hora'], $DATA['saiu18minuto'], $DATA['saiu18fechafin'], $DATA['saiu18horafin'], $DATA['saiu18minutofin']);
					$DATA['saiu18idcaso']=(int)(fecha_DiaMod().$DATA['saiu18id'].'');
					$DATA['saiu18respuesta']='';
					$DATA['saiu18estado']=1;
					$bEnviaCaso=true;
				}
				break;
				case 8: //Solicitud abandonada
				case 9: //Cancelada por el usuario
				$DATA['saiu18respuesta']='';
				break;
				default:
				$sError=$ERR['saiu18solucion'].$sSepara.$sError;
				break;
			}
			break;
			case 8: //Solicitud abandonada
			case 9: //Cancelada por el usuario
			if (trim($DATA['saiu18fechafin'])==''){$DATA['saiu18fechafin']=fecha_DiaMod();}
			if (trim($DATA['saiu18minutofin'])==''){$DATA['saiu18minutofin']=fecha_minuto();}
			if (trim($DATA['saiu18horafin'])==''){$DATA['saiu18horafin']=fecha_hora();}
			//$DATA['saiu18minutofin']=fecha_minuto();
			//$DATA['saiu18horafin']=fecha_hora();
			break; 
			default:
			$DATA['saiu18estado']=2;
			if ($DATA['saiu18hora']==''){$DATA['saiu18hora']=fecha_hora();}
			if ($DATA['saiu18minuto']==''){$DATA['saiu18minuto']=fecha_minuto();}
			if ($DATA['saiu18fechafin']==''){$DATA['saiu18fechafin']=0;}
			if ($DATA['saiu18horafin']==''){$DATA['saiu18horafin']=0;}
			if ($DATA['saiu18minutofin']==''){$DATA['saiu18minutofin']=0;}
			break;
		}
		if ($DATA['saiu18clasesolicitud']==''){$DATA['saiu18clasesolicitud']=0;}
		if ($DATA['saiu18tiposolicitud']==''){$DATA['saiu18tiposolicitud']=0;}
		if ($DATA['saiu18temasolicitud']==''){$DATA['saiu18temasolicitud']=0;}
		if ($DATA['saiu18idzona']==''){$DATA['saiu18idzona']=0;}
		if ($DATA['saiu18idcentro']==''){$DATA['saiu18idcentro']=0;}
		if ($DATA['saiu18idescuela']==''){$DATA['saiu18idescuela']=0;}
		if ($DATA['saiu18idprograma']==''){$DATA['saiu18idprograma']=0;}
		if ($DATA['saiu18idperiodo']==''){$DATA['saiu18idperiodo']=0;}
	}
	//Valiaciones de campos obligatorios en todo guardar.
	if ($DATA['saiu18tiporadicado']==''){$sError=$ERR['saiu18tiporadicado'];}
	if ($DATA['saiu18mes']==''){$sError=$ERR['saiu18mes'];}
	if ($DATA['saiu18agno']==''){$sError=$ERR['saiu18agno'];}
	// -- Tiene un cerrado.
	$iDiaIni=fecha_ArmarNumero($DATA['saiu18dia'],$DATA['saiu18mes'],$DATA['saiu18agno']);
	if ($bConCierre && $sError==''){
		//Validaciones previas a cerrar
		if ($DATA['saiu18estado']==7){
			switch($DATA['saiu18solucion']){
				case 1: // Resuelto en la atención
				case 5: // Se inicia PQRS
				list($DATA['saiu18tiemprespdias'], $DATA['saiu18tiempresphoras'], $DATA['saiu18tiemprespminutos'])=Tiempo_MinutosCalendario($iDiaIni, $DATA['saiu18hora'], $DATA['saiu18minuto'], $DATA['saiu18fechafin'], $DATA['saiu18horafin'], $DATA['saiu18minutofin']);
				break;
			}
		}
		//Aprobó las Validaciones al cerrar
		if ($sError.$sErrorCerrando!=''){
			if ($DATA['saiu18estado']==7) {
				$DATA['saiu18estado'] = $DATA['saiu18estadoorigen'];
			} else if ($DATA['saiu18estado']!=1) {
				$DATA['saiu18estado']=2;
			}
			$sErrorCerrando=$sError.' '.$sErrorCerrando;
		}else{
			$bCerrando=true;
			//Acciones del cierre
		}
	}
	// -- Fin del cerrado.
	// -- Se verifican los valores de campos de otras tablas.
	$sTabla18='saiu18telefonico_'.$DATA['saiu18agno'];
	if ($DATA['saiu18idresponsable_doc']!=''){
		if ($sError==''){$sError=tabla_terceros_existe($DATA['saiu18idresponsable_td'], $DATA['saiu18idresponsable_doc'], $objDB, 'El tercero Responsable ');}
		if ($sError==''){
			list($sError, $sInfo)=tercero_Bloqueado($DATA['saiu18idresponsable'], $objDB);
			if ($sInfo!=''){$sError=$sError.'<br>'.$sInfo;}
			}
		}
	if ($DATA['saiu18idsolicitante_doc']!=''){
		if ($sError==''){$sError=tabla_terceros_existe($DATA['saiu18idsolicitante_td'], $DATA['saiu18idsolicitante_doc'], $objDB, 'El tercero Solicitante ');}
		if ($sError==''){
			list($sError, $sInfo)=tercero_Bloqueado($DATA['saiu18idsolicitante'], $objDB);
			if ($sInfo!=''){$sError=$sError.'<br>'.$sInfo;}
			}
		}
	if ($sError == '') {
		list($sErrorR, $sDebugR) = f3018_RevTabla_saiu18telefonico($DATA['saiu18agno'], $objDB);
		$sError = $sError . $sErrorR;
	}
	$bQuitarCodigo=false;
	$sCampoCodigo='';
	if ($sError==''){
		if ($DATA['paso']==10){
			if ($DATA['saiu18consec']==''){
				$DATA['saiu18consec']=tabla_consecutivo($sTabla18, 'saiu18consec', 'saiu18agno='.$DATA['saiu18agno'].' AND saiu18mes='.$DATA['saiu18mes'].' AND saiu18tiporadicado='.$DATA['saiu18tiporadicado'].'', $objDB);
				if ($DATA['saiu18consec']==-1){$sError=$objDB->serror;}
				$bQuitarCodigo=true;
				$sCampoCodigo='saiu18consec';
				}else{
				if (!seg_revisa_permiso($iCodModulo, 8, $objDB)){
					$sError=$ERR['8'];
					$DATA['saiu18consec']='';
					}
				}
			if ($sError==''){
				$sSQL='SELECT 1 FROM '.$sTabla18.' WHERE saiu18agno='.$DATA['saiu18agno'].' AND saiu18mes='.$DATA['saiu18mes'].' AND saiu18tiporadicado='.$DATA['saiu18tiporadicado'].' AND saiu18consec='.$DATA['saiu18consec'].'';
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
			$DATA['saiu18id']=tabla_consecutivo($sTabla18,'saiu18id', '', $objDB);
			if ($DATA['saiu18id']==-1){$sError=$objDB->serror;}
			}
		}
	if ($sError==''){
		//Encontrar la clase
		$sSQL='SELECT saiu02clasesol FROM saiu02tiposol WHERE saiu02id='.$DATA['saiu18tiposolicitud'].'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$fila=$objDB->sf($tabla);
			$DATA['saiu18clasesolicitud']=$fila['saiu02clasesol'];
			}
		}
	$idSolicitantePrevio=0;
	if ($sError==''){
		$DATA['saiu18detalle']=stripslashes($DATA['saiu18detalle']);
		//Si el campo saiu18detalle permite html quite la linea htmlspecialchars para el campo y habilite la siguiente linea:
		//$saiu18detalle=addslashes($DATA['saiu18detalle']);
		$saiu18detalle=str_replace('"', '\"', $DATA['saiu18detalle']);
		$DATA['saiu18respuesta']=stripslashes($DATA['saiu18respuesta']);
		//$saiu18respuesta=addslashes($DATA['saiu18respuesta']);
		$saiu18respuesta=str_replace('"', '\"', $DATA['saiu18respuesta']);
		$bpasa=false;
		if ($DATA['paso']==10){
			$DATA['saiu18idpqrs']=0;
			$DATA['saiu18tiemprespdias']=0;
			$DATA['saiu18tiempresphoras']=0;
			$DATA['saiu18tiemprespminutos']=0;
			$DATA['saiu18agno']=fecha_agno();
			$DATA['saiu18mes']=fecha_mes();
			$DATA['saiu18dia']=fecha_dia();
			$DATA['saiu18hora']=fecha_hora();
			$DATA['saiu18minuto']=fecha_minuto();
			$DATA['saiu18idcaso']=0;
			$sCampos3018='saiu18agno, saiu18mes, saiu18tiporadicado, saiu18consec, saiu18id, 
			saiu18dia, saiu18hora, saiu18minuto, saiu18estado, saiu18idtelefono, saiu18numtelefono, saiu18idsolicitante, 
			saiu18tipointeresado, saiu18clasesolicitud, saiu18tiposolicitud, saiu18temasolicitud, saiu18idzona, 
			saiu18idcentro, saiu18codpais, saiu18coddepto, saiu18codciudad, saiu18idescuela, 
			saiu18idprograma, saiu18idperiodo, saiu18numorigen, saiu18idpqrs, saiu18detalle, saiu18fechafin,
			saiu18horafin, saiu18minutofin, saiu18paramercadeo, saiu18idresponsable, saiu18tiemprespdias, 
			saiu18tiempresphoras, saiu18tiemprespminutos, saiu18solucion, saiu18idcaso, saiu18respuesta';
			$sValores3018=''.$DATA['saiu18agno'].', '.$DATA['saiu18mes'].', '.$DATA['saiu18tiporadicado'].', '.$DATA['saiu18consec'].', '.$DATA['saiu18id'].', 
			'.$DATA['saiu18dia'].', '.$DATA['saiu18hora'].', '.$DATA['saiu18minuto'].', '.$DATA['saiu18estado'].', '.$DATA['saiu18idtelefono'].', "'.$DATA['saiu18numtelefono'].'", '.$DATA['saiu18idsolicitante'].', 
			'.$DATA['saiu18tipointeresado'].', '.$DATA['saiu18clasesolicitud'].', '.$DATA['saiu18tiposolicitud'].', '.$DATA['saiu18temasolicitud'].', '.$DATA['saiu18idzona'].', 
			'.$DATA['saiu18idcentro'].', "'.$DATA['saiu18codpais'].'", "'.$DATA['saiu18coddepto'].'", "'.$DATA['saiu18codciudad'].'", '.$DATA['saiu18idescuela'].', 
			'.$DATA['saiu18idprograma'].', '.$DATA['saiu18idperiodo'].', "'.$DATA['saiu18numorigen'].'", '.$DATA['saiu18idpqrs'].', "'.$saiu18detalle.'", '.$DATA['saiu18fechafin'].', 
			'.$DATA['saiu18horafin'].', '.$DATA['saiu18minutofin'].', '.$DATA['saiu18paramercadeo'].', '.$DATA['saiu18idresponsable'].', '.$DATA['saiu18tiemprespdias'].', 
			'.$DATA['saiu18tiempresphoras'].', '.$DATA['saiu18tiemprespminutos'].', '.$DATA['saiu18solucion'].', '.$DATA['saiu18idcaso'].', "'.$saiu18respuesta.'"';
			if ($APP->utf8==1){
				$sSQL='INSERT INTO '.$sTabla18.' ('.$sCampos3018.') VALUES ('.cadena_codificar($sValores3018).');';
				$sdetalle=$sCampos3018.'['.cadena_codificar($sValores3018).']';
				}else{
				$sSQL='INSERT INTO '.$sTabla18.' ('.$sCampos3018.') VALUES ('.$sValores3018.');';
				$sdetalle=$sCampos3018.'['.$sValores3018.']';
				}
			$idAccion=2;
			$bpasa=true;
			}else{
			$scampo[1]='saiu18dia';
			$scampo[2]='saiu18hora';
			$scampo[3]='saiu18minuto';
			$scampo[4]='saiu18idtelefono';
			$scampo[5]='saiu18numtelefono';
			$scampo[6]='saiu18idsolicitante';
			$scampo[7]='saiu18tipointeresado';
			$scampo[8]='saiu18clasesolicitud';
			$scampo[9]='saiu18temasolicitud';
			$scampo[10]='saiu18idzona';
			$scampo[11]='saiu18idcentro';
			$scampo[12]='saiu18codpais';
			$scampo[13]='saiu18coddepto';
			$scampo[14]='saiu18codciudad';
			$scampo[15]='saiu18idescuela';
			$scampo[16]='saiu18idprograma';
			$scampo[17]='saiu18idperiodo';
			$scampo[18]='saiu18numorigen';
			$scampo[19]='saiu18detalle';
			$scampo[20]='saiu18horafin';
			$scampo[21]='saiu18minutofin';
			$scampo[22]='saiu18paramercadeo';
			$scampo[23]='saiu18idresponsable';
			$scampo[24]='saiu18solucion';
			$scampo[25]='saiu18tiposolicitud';
			$scampo[26]='saiu18estado';
			$scampo[27]='saiu18tiemprespdias';
			$scampo[28]='saiu18tiempresphoras';
			$scampo[29]='saiu18tiemprespminutos';
			$scampo[30]='saiu18respuesta';
			$scampo[31]='saiu18idcaso';
			$scampo[32]='saiu18fecharespcaso';
			$scampo[33]='saiu18horarespcaso';
			$scampo[34]='saiu18minrespcaso';
			$scampo[35]='saiu18idunidadcaso';
			$scampo[36]='saiu18idequipocaso';
			$scampo[37]='saiu18idsupervisorcaso';
			$scampo[38]='saiu18idresponsablecaso';
			$scampo[39]='saiu18idpqrs';
			$scampo[40]='saiu18numref';
			$scampo[41]='saiu18fechafin';
			$sdato[1]=$DATA['saiu18dia'];
			$sdato[2]=$DATA['saiu18hora'];
			$sdato[3]=$DATA['saiu18minuto'];
			$sdato[4]=$DATA['saiu18idtelefono'];
			$sdato[5]=$DATA['saiu18numtelefono'];
			$sdato[6]=$DATA['saiu18idsolicitante'];
			$sdato[7]=$DATA['saiu18tipointeresado'];
			$sdato[8]=$DATA['saiu18clasesolicitud'];
			$sdato[9]=$DATA['saiu18temasolicitud'];
			$sdato[10]=$DATA['saiu18idzona'];
			$sdato[11]=$DATA['saiu18idcentro'];
			$sdato[12]=$DATA['saiu18codpais'];
			$sdato[13]=$DATA['saiu18coddepto'];
			$sdato[14]=$DATA['saiu18codciudad'];
			$sdato[15]=$DATA['saiu18idescuela'];
			$sdato[16]=$DATA['saiu18idprograma'];
			$sdato[17]=$DATA['saiu18idperiodo'];
			$sdato[18]=$DATA['saiu18numorigen'];
			$sdato[19]=$saiu18detalle;
			$sdato[20]=$DATA['saiu18horafin'];
			$sdato[21]=$DATA['saiu18minutofin'];
			$sdato[22]=$DATA['saiu18paramercadeo'];
			$sdato[23]=$DATA['saiu18idresponsable'];
			$sdato[24]=$DATA['saiu18solucion'];
			$sdato[25]=$DATA['saiu18tiposolicitud'];
			$sdato[26]=$DATA['saiu18estado'];
			$sdato[27]=$DATA['saiu18tiemprespdias'];
			$sdato[28]=$DATA['saiu18tiempresphoras'];
			$sdato[29]=$DATA['saiu18tiemprespminutos'];
			$sdato[30]=$saiu18respuesta;
			$sdato[31]=$DATA['saiu18idcaso'];
			$sdato[32]=$DATA['saiu18fecharespcaso'];
			$sdato[33]=$DATA['saiu18horarespcaso'];
			$sdato[34]=$DATA['saiu18minrespcaso'];
			$sdato[35]=$DATA['saiu18idunidadcaso'];
			$sdato[36]=$DATA['saiu18idequipocaso'];
			$sdato[37]=$DATA['saiu18idsupervisorcaso'];
			$sdato[38]=$DATA['saiu18idresponsablecaso'];
			$sdato[39]=$DATA['saiu18idpqrs'];
			$sdato[40]=$DATA['saiu18numref'];
			$sdato[41]=$DATA['saiu18fechafin'];
			$numcmod=41;
			$sWhere='saiu18id='.$DATA['saiu18id'].'';
			$sSQL='SELECT * FROM '.$sTabla18.' WHERE '.$sWhere;
			$sdatos='';
			$bPrimera=true;
			$result=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($result)>0){
				$filabase=$objDB->sf($result);
				if ($DATA['saiu18idsolicitante']!=$filabase['saiu18idsolicitante']){
					$idSolicitantePrevio=$filabase['saiu18idsolicitante'];
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
					$sSQL='UPDATE '.$sTabla18.' SET '.cadena_codificar($sdatos).' WHERE '.$sWhere.';';
					}else{
					$sdetalle=$sdatos.'['.$sWhere.']';
					$sSQL='UPDATE '.$sTabla18.' SET '.$sdatos.' WHERE '.$sWhere.';';
					}
				$idAccion=3;
				}
			}
		if ($bpasa){
			if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Guardar 3018 '.$sSQL.'<br>';}
			$result=$objDB->ejecutasql($sSQL);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' [3018] ..<!-- '.$sSQL.' -->';
				if ($idAccion==2){
					$DATA['saiu18id']='';
					$bQuitarCodigo=true;
					$DATA['paso']=0;
					}else{
					$DATA['paso']=2;
					}
				$bCerrando=false;
				}else{
				if ($bAudita[$idAccion]){seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], $idAccion, $DATA['saiu18id'], $sdetalle, $objDB);}
				$DATA['paso']=2;
				//Registrar en el inventario.
				$valores3000[2]=$iCodModulo;
				$valores3000[3]=$DATA['saiu18agno'];
				$valores3000[4]=$DATA['saiu18id'];
				if ($idSolicitantePrevio!=0){
					//Retirar al anterior.
					$valores3000[1]=$idSolicitantePrevio;
					f3000_Retirar($valores3000, $objDB, $bDebug);
					}
				if ($DATA['saiu18idsolicitante']!=0){
					$valores3000[1]=$DATA['saiu18idsolicitante'];
					$valores3000[5]=$iDiaIni;
					$valores3000[6]=$DATA['saiu18tiposolicitud'];
					$valores3000[7]=$DATA['saiu18temasolicitud'];
					$valores3000[8]=$DATA['saiu18estado'];
					f3000_Registrar($valores3000, $objDB, $bDebug);
					}
				if ($bEnviaCaso) {
					list($sMensaje, $sErrorE, $sDebugE) = f3000_EnviaCorreosAtencion($DATA, $DATA['saiu18agno'], $objDB, $bDebug, true);
					$sError = $sError . $sErrorE;
					$sDebug = $sDebug . $sDebugE;
					}
				if ($bEnviaEncuesta) {
					list($sMensaje, $sErrorE, $sDebugE) = f3000_EnviaCorreosAtencion($DATA, $DATA['saiu18agno'], $objDB, $bDebug);
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
		list($sErrorCerrando, $sDebugCerrar)=f3018_Cerrar($DATA['saiu18id'], $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugCerrar;
		}
	//if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' InfoDepura<br>';}
	return array($DATA, $sError, $iTipoError, $bCerrando, $sErrorCerrando, $sDebug);
	}
function f3018_db_Eliminar($saiu18agno, $saiu18id, $objDB, $bDebug=false){
	$iCodModulo=3018;
	$bAudita[4]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3018='lg/lg_3018_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3018)){$mensajes_3018='lg/lg_3018_es.php';}
	require $mensajes_todas;
	require $mensajes_3018;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	$saiu18id=numeros_validar($saiu18id);
	// Traer los datos para hacer las validaciones.
	$sTabla18='saiu18telefonico_'.$saiu18agno;
	if ($sError==''){
		$sSQL='SELECT * FROM '.$sTabla18.' WHERE saiu18id='.$saiu18id.'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$filabase=$objDB->sf($tabla);
			}else{
			$sError='No se encuentra el registro solicitado {Ref: '.$saiu18id.'}';
			}
		}
	if ($sError==''){
		if (!seg_revisa_permiso($iCodModulo, 4, $objDB)){
			$sError=$ERR['4'];
			}
		}
	if ($sError==''){
		$sSQL='SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=3018';
		$tablaor=$objDB->ejecutasql($sSQL);
		while ($filaor=$objDB->sf($tablaor)){
			$sSQL='SELECT '.$filaor['campoid'].' FROM '.$filaor['tabla'].' WHERE '.$filaor['camporev'].'='.$_REQUEST['saiu18id'].' LIMIT 0, 1';
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
		if ($filabase['saiu18idsolicitante']!=0){
			//Retirar al anterior.
			$valores3000[1]=$filabase['saiu18idsolicitante'];
			$valores3000[2]=$iCodModulo;
			$valores3000[3]=$filabase['saiu18agno'];
			$valores3000[4]=$filabase['saiu18id'];
			f3000_Retirar($valores3000, $objDB, $bDebug);
			}
		$sWhere='saiu18id='.$saiu18id.'';
		//$sWhere='saiu18consec='.$filabase['saiu18consec'].' AND saiu18tiporadicado='.$filabase['saiu18tiporadicado'].' AND saiu18mes='.$filabase['saiu18mes'].' AND saiu18agno='.$filabase['saiu18agno'].'';
		$sSQL='DELETE FROM '.$sTabla18.' WHERE '.$sWhere.';';
		$result=$objDB->ejecutasql($sSQL);
		if ($result==false){
			$sError=$ERR['falla_eliminar'].' .. <!-- '.$sSQL.' -->';
			}else{
			if ($bAudita[4]){seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $saiu18id, $sWhere, $objDB);}
			}
		}
	return array($sError, $iTipoError, $sDebug);
	}
function f3018_TituloBusqueda(){
	return 'B&uacute;squeda de Registro de llamadas';
	}
function f3018_ParametrosBusqueda(){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3018='lg/lg_3018_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3018)){$mensajes_3018='lg/lg_3018_es.php';}
	require $mensajes_todas;
	require $mensajes_3018;
	$sParams='<label class="Label90">
	'.$ETI['msg_bnombre'].'
	</label>
	<label>
	<input id="b3018nombre" name="b3018nombre" type="text" value="" onchange="paginarbusqueda()" />
	</label>';
	return $sParams;
	}
function f3018_JavaScriptBusqueda($iModuloBusca){
	$sRes='var sCampo=window.document.frmedita.scampobusca.value;
	var params=new Array();
	params[100]=sCampo;
	params[101]=window.document.frmedita.paginabusqueda.value;
	params[102]=window.document.frmedita.lppfbusqueda.value;
	params[103]=window.document.frmedita.b3018nombre.value;
	xajax_f'.$iModuloBusca.'_HtmlBusqueda(params);';
	return $sRes;
	}
function f3018_TablaDetalleBusquedas($aParametros, $objDB){
	$res='';
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3018='lg/lg_3018_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3018)){$mensajes_3018='lg/lg_3018_es.php';}
	require $mensajes_todas;
	require $mensajes_3018;
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
		return array($sLeyenda.'<input id="paginaf3018" name="paginaf3018" type="hidden" value="'.$pagina.'"/><input id="lppf3018" name="lppf3018" type="hidden" value="'.$lineastabla.'"/>', $sDebug);
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
	$sSQL='SELECT TB.saiu18agno, TB.saiu18mes, T3.saiu16nombre, TB.saiu18consec, TB.saiu18id, TB.saiu18dia, TB.saiu18hora, TB.saiu18minuto, T9.saiu11nombre, T10.saiu22nombre, TB.saiu18numtelefono, T12.unad11razonsocial AS C12_nombre, T13.bita07nombre, T14.saiu01titulo, T15.saiu02titulo, T16.saiu03titulo, T17.unad23nombre, T18.unad24nombre, T19.unad18nombre, T20.unad19nombre, T21.unad20nombre, T22.core12nombre, T23.core09nombre, T24.exte02nombre, TB.saiu18numorigen, TB.saiu18idpqrs, TB.saiu18detalle, TB.saiu18horafin, TB.saiu18minutofin, TB.saiu18paramercadeo, T31.unad11razonsocial AS C31_nombre, TB.saiu18tiemprespdias, TB.saiu18tiempresphoras, TB.saiu18tiemprespminutos, TB.saiu18tiporadicado, TB.saiu18estado, TB.saiu18idtelefono, TB.saiu18idsolicitante, T12.unad11tipodoc AS C12_td, T12.unad11doc AS C12_doc, TB.saiu18tipointeresado, TB.saiu18clasesolicitud, TB.saiu18tiposolicitud, TB.saiu18temasolicitud, TB.saiu18idzona, TB.saiu18idcentro, TB.saiu18codpais, TB.saiu18coddepto, TB.saiu18codciudad, TB.saiu18idescuela, TB.saiu18idprograma, TB.saiu18idperiodo, TB.saiu18idresponsable, T31.unad11tipodoc AS C31_td, T31.unad11doc AS C31_doc 
FROM saiu18telefonico AS TB, saiu16tiporadicado AS T3, saiu11estadosol AS T9, saiu22telefonos AS T10, unad11terceros AS T12, bita07tiposolicitante AS T13, saiu01claseser AS T14, saiu02tiposol AS T15, saiu03temasol AS T16, unad23zona AS T17, unad24sede AS T18, unad18pais AS T19, unad19depto AS T20, unad20ciudad AS T21, core12escuela AS T22, core09programa AS T23, exte02per_aca AS T24, unad11terceros AS T31 
WHERE '.$sSQLadd1.' TB.saiu18tiporadicado=T3.saiu16id AND TB.saiu18estado=T9.saiu11id AND TB.saiu18idtelefono=T10.saiu22id AND TB.saiu18idsolicitante=T12.unad11id AND TB.saiu18tipointeresado=T13.bita07id AND TB.saiu18clasesolicitud=T14.saiu01id AND TB.saiu18tiposolicitud=T15.saiu02id AND TB.saiu18temasolicitud=T16.saiu03id AND TB.saiu18idzona=T17.unad23id AND TB.saiu18idcentro=T18.unad24id AND TB.saiu18codpais=T19.unad18codigo AND TB.saiu18coddepto=T20.unad19codigo AND TB.saiu18codciudad=T21.unad20codigo AND TB.saiu18idescuela=T22.core12id AND TB.saiu18idprograma=T23.core09id AND TB.saiu18idperiodo=T24.exte02id AND TB.saiu18idresponsable=T31.unad11id '.$sSQLadd.'
ORDER BY TB.saiu18agno, TB.saiu18mes, TB.saiu18tiporadicado, TB.saiu18consec';
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
			//return array(cadena_codificar($sErrConsulta.'<input id="paginaf3018" name="paginaf3018" type="hidden" value="'.$pagina.'"/><input id="lppf3018" name="lppf3018" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
<td><b>'.$ETI['saiu18agno'].'</b></td>
<td><b>'.$ETI['saiu18mes'].'</b></td>
<td><b>'.$ETI['saiu18tiporadicado'].'</b></td>
<td><b>'.$ETI['saiu18consec'].'</b></td>
<td><b>'.$ETI['saiu18dia'].'</b></td>
<td><b>'.$ETI['saiu18hora'].'</b></td>
<td><b>'.$ETI['saiu18estado'].'</b></td>
<td><b>'.$ETI['saiu18idtelefono'].'</b></td>
<td><b>'.$ETI['saiu18numtelefono'].'</b></td>
<td colspan="2"><b>'.$ETI['saiu18idsolicitante'].'</b></td>
<td><b>'.$ETI['saiu18tipointeresado'].'</b></td>
<td><b>'.$ETI['saiu18clasesolicitud'].'</b></td>
<td><b>'.$ETI['saiu18tiposolicitud'].'</b></td>
<td><b>'.$ETI['saiu18temasolicitud'].'</b></td>
<td><b>'.$ETI['saiu18idzona'].'</b></td>
<td><b>'.$ETI['saiu18idcentro'].'</b></td>
<td><b>'.$ETI['saiu18codpais'].'</b></td>
<td><b>'.$ETI['saiu18coddepto'].'</b></td>
<td><b>'.$ETI['saiu18codciudad'].'</b></td>
<td><b>'.$ETI['saiu18idescuela'].'</b></td>
<td><b>'.$ETI['saiu18idprograma'].'</b></td>
<td><b>'.$ETI['saiu18idperiodo'].'</b></td>
<td><b>'.$ETI['saiu18numorigen'].'</b></td>
<td><b>'.$ETI['saiu18idpqrs'].'</b></td>
<td><b>'.$ETI['saiu18detalle'].'</b></td>
<td><b>'.$ETI['saiu18horafin'].'</b></td>
<td><b>'.$ETI['saiu18paramercadeo'].'</b></td>
<td colspan="2"><b>'.$ETI['saiu18idresponsable'].'</b></td>
<td><b>'.$ETI['saiu18tiemprespdias'].'</b></td>
<td><b>'.$ETI['saiu18tiempresphoras'].'</b></td>
<td><b>'.$ETI['saiu18tiemprespminutos'].'</b></td>
<td align="right">
'.html_paginador('paginabusqueda', $registros, $lineastabla, $pagina, 'paginarbusqueda()').'
'.html_lpp('lppfbusqueda', $lineastabla, 'paginarbusqueda()').'
</td>
</tr></thead>';
	$tlinea=1;
	while($filadet=$objDB->sf($tabladetalle)){
		$sPrefijo='<a href="javascript:Devuelve(\''.$filadet['saiu18id'].'\');">';
		$sSufijo='</a>';
		$tlinea++;
		$et_saiu18hora=html_TablaHoraMin($filadet['saiu18hora'], $filadet['saiu18minuto']);
		$et_saiu18horafin=html_TablaHoraMin($filadet['saiu18horafin'], $filadet['saiu18minutofin']);
		$res=$res.'<tr onmouseover="cambia_color_over(this);" onmouseout="cambia_color_out(this);">
<td>'.$sPrefijo.$filadet['saiu18agno'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu18mes'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['saiu16nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu18consec'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu18dia'].$sSufijo.'</td>
<td>'.$sPrefijo.$et_saiu18hora.$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['saiu11nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['saiu22nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['saiu18numtelefono']).$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['C12_td'].' '.$filadet['C12_doc'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['C12_nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['bita07nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['saiu01titulo']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['saiu02titulo']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['saiu03titulo']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['unad23nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['unad24nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu18codpais'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu18coddepto'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu18codciudad'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['core12nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['core09nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['exte02nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['saiu18numorigen']).$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu18idpqrs'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu18detalle'].$sSufijo.'</td>
<td>'.$sPrefijo.$et_saiu18horafin.$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu18paramercadeo'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['C31_td'].' '.$filadet['C31_doc'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['C31_nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu18tiemprespdias'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu18tiempresphoras'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu18tiemprespminutos'].$sSufijo.'</td>
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
function f3018_RevTabla_saiu18telefonico($sContenedor, $objDB, $bDebug = false)
{
	list($sError, $sDebug) = f3018_RevisarTabla($sContenedor, $objDB, $bDebug);
	return array($sError, $sDebug);
}
function f3018_ConsultaResponsable($DATA, $objDB, $bDebug = false)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_3018 = 'lg/lg_3018_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_3018)) {
		$mensajes_3018 = 'lg/lg_3018_es.php';
	}
	require $mensajes_todas;
	require $mensajes_3018;
	$sError = '';
	$iTipoError = 0;
	$sDebug = '';
	$saiu18idunidadcaso = 0;
	$saiu18idequipocaso = 0;
	$saiu18idsupervisorcaso = 0;
	$saiu18idresponsablecaso = 0;
	$saiu18tiemprespdias = 0;
	$saiu18tiempresphoras = 0;
	if (isset($DATA['saiu18temasolicitud']) == 0) {
		$DATA['saiu18temasolicitud'] = '';
	}
	if ($DATA['saiu18temasolicitud'] == '') {
		$sError = $ERR['saiu18temasolicitud'] . $sSepara . $sError;
	}
	if ($sError == '') {
		$sSQL = 'SELECT saiu03idunidadresp1, saiu03idequiporesp1, saiu03idliderrespon1, saiu03tiemprespdias1, saiu03tiempresphoras1
		FROM saiu03temasol
		WHERE saiu03id = ' . $DATA['saiu18temasolicitud'] . '';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' Consulta responsable solicitud ' . $sSQL . '<br>';
		}
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			$saiu18idunidadcaso = $fila['saiu03idunidadresp1'];
			$saiu18idequipocaso = $fila['saiu03idequiporesp1'];
			$saiu18idsupervisorcaso = $fila['saiu03idliderrespon1'];
			$saiu18idresponsablecaso = $saiu18idsupervisorcaso;
			$saiu18tiemprespdias = $fila['saiu03tiemprespdias1'];
			$saiu18tiempresphoras = $fila['saiu03tiempresphoras1'];
		} else {
			$sError = $sError . 'No se ha configurado el tema de solicitud.';
		}
	}
	return array($saiu18idunidadcaso, $saiu18idequipocaso, $saiu18idsupervisorcaso, $saiu18idresponsablecaso, $saiu18tiemprespdias, $saiu18tiempresphoras, $sError, $iTipoError, $sDebug);
}
function f3018_ActualizarAtiende($DATA, $objDB, $bDebug = false, $idTercero)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_3018 = 'lg/lg_3018_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_3018)) {
		$mensajes_3018 = 'lg/lg_3018_es.php';
	}
	require $mensajes_todas;
	require $mensajes_3018;
	$sTabla18 = 'saiu18telefonico_' . $DATA['saiu18agno'];
	$sResultado = '';
	$sError = '';
	$sDebug = '';
	$iTipoError = 0;
	if (!$objDB->bexistetabla($sTabla18)) {
		$sError = $sError . 'No ha sido posible acceder al contenedor de datos';
	}
	if ($sError == '') {
		$sSQL = 'SELECT saiu18agno, saiu18mes, saiu18estado, saiu18temasolicitud FROM ' . $sTabla18 . ' WHERE saiu18id=' . $DATA['saiu18id'] . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			list($DATA['saiu18idunidadcaso'], $DATA['saiu18idequipocaso'], $DATA['saiu18idsupervisorcaso'], $DATA['saiu18idresponsablecaso'], $saiu18tiemprespdias, $saiu18tiempresphoras, $sErrorF, $iTipoError, $sDebugF) = f3018_ConsultaResponsable($fila, $objDB, $bDebug);
			$sError = $sError . $sErrorF;
			if ($bDebug) {
				$sDebug = $sDebug . $sDebugF;
			}
		} else {
			$sError = $sError . $ETI['saiu18noexiste'];
		}
	}
	if ($sError == '') {
		if ($saiu18tiemprespdias > 0) { // Cálculo fecha probable de respuesta
			$iFechaBase = fecha_DiaMod();
			$saiu18fecharespprob = fecha_NumSumarDias($iFechaBase, $saiu18tiemprespdias);
		}
		list($DATA, $sErrorE, $iTipoError, $sDebugGuardar) = f3018_db_GuardarV2($DATA, $objDB, $bDebug, $idTercero);
		$sError = $sError . $sErrorE;
		$sDebug = $sDebug . $sDebugGuardar;
		if ($sError == '') {
			$sError = '<b>' . $ETI['msg_itemguardado'] . '</b>';
			$iTipoError = 1;
		}
	}
	return array($DATA, $sError, $iTipoError, $sDebug);
}
function elimina_archivo_saiu18idarchivo($idpadre, $iAgno)
{
	$_SESSION['u_ultimominuto'] = iminutoavance();
	require './app.php';
	$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto != '') {
		$objDB->dbPuerto = $APP->dbpuerto;
	}
	$objDB->xajax();
	$sTabla18 = 'saiu18telefonico_' . $iAgno;
	archivo_eliminar($sTabla18, 'saiu18id', 'saiu18idorigen', 'saiu18idarchivo', $idpadre, $objDB);
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->call("limpia_saiu18idarchivo");
	return $objResponse;
}
function elimina_archivo_saiu18idarchivorta($idpadre, $iAgno)
{
	$_SESSION['u_ultimominuto'] = iminutoavance();
	require './app.php';
	$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto != '') {
		$objDB->dbPuerto = $APP->dbpuerto;
	}
	$objDB->xajax();
	$sTabla18 = 'saiu18telefonico_' . $iAgno;
	archivo_eliminar($sTabla18, 'saiu18id', 'saiu18idorigenrta', 'saiu18idarchivorta', $idpadre, $objDB);
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->call("limpia_saiu18idarchivorta");
	return $objResponse;
}
function f3018_HTMLComboV2_btema($objDB, $objCombos, $valor, $vrbtipo)
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
		$objCombos->sAccion = 'paginarf3018()';
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
function f3018_Combobtema($aParametros)
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
	$html_btema = f3018_HTMLComboV2_btema($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_btema', 'innerHTML', $html_btema);
	$objResponse->call('jQuery("#btema").chosen({no_results_text: "No existen coincidencias: ",width: "100%"})');
	$objResponse->call('paginarf3018');
	return $objResponse;
}