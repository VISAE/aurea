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
function f3020_HTMLComboV2_saiu20tiposolicitud($objDB, $objCombos, $valor){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('saiu20tiposolicitud', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	//$objCombos->iAncho=450;
	$sSQL='SELECT saiu02id AS id, saiu02titulo AS nombre FROM saiu02tiposol';
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f3020_HTMLComboV2_saiu20idcentro($objDB, $objCombos, $valor, $vrsaiu20idzona){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$sCondi='unad24idzona="'.$vrsaiu20idzona.'"';
	$objCombos->nuevo('saiu20idcentro', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	//$objCombos->iAncho=450;
	$sSQL='SELECT unad24id AS id, unad24nombre AS nombre FROM unad24sede WHERE '.$sCondi.' AND unad24id>0';
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f3020_HTMLComboV2_saiu20coddepto($objDB, $objCombos, $valor, $vrsaiu20codpais){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	//@@ Se debe arreglar la condicion..
	$sCondi='unad19codpais="'.$vrsaiu20codpais.'"';
	if ($sCondi!=''){$sCondi=' WHERE '.$sCondi;}
	$objCombos->nuevo('saiu20coddepto', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	//$objCombos->iAncho=450;
	$objCombos->sAccion='carga_combo_saiu20codciudad()';
	$sSQL='SELECT unad19codigo AS id, unad19nombre AS nombre FROM unad19depto'.$sCondi;
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f3020_HTMLComboV2_saiu20codciudad($objDB, $objCombos, $valor, $vrsaiu20coddepto){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	//@@ Se debe arreglar la condicion..
	$sCondi='unad20coddepto="'.$vrsaiu20coddepto.'"';
	if ($sCondi!=''){$sCondi=' WHERE '.$sCondi;}
	$objCombos->nuevo('saiu20codciudad', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	//$objCombos->iAncho=450;
	$sSQL='SELECT unad20codigo AS id, unad20nombre AS nombre FROM unad20ciudad'.$sCondi;
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
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
	$objResponse->call('$("#saiu20idcentro").chosen()');
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
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_saiu20coddepto', 'innerHTML', $html_saiu20coddepto);
	$objResponse->call('$("#saiu20coddepto").chosen()');
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
	$objResponse->call('$("#saiu20codciudad").chosen()');
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
	$objResponse->call('$("#saiu20idprograma").chosen()');
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
		return array($sLeyenda.'<input id="paginaf3020" name="paginaf3020" type="hidden" value="'.$pagina.'"/><input id="lppf3020" name="lppf3020" type="hidden" value="'.$lineastabla.'"/>', $sDebug);
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
	switch($aParametros[105]){
		case 1:
		$sSQLadd1=$sSQLadd1.'TB.saiu20idresponsable='.$idTercero.' AND ';
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
	$sTitulos='Agno, Mes, Tiporadicado, Consec, Id, Dia, Hora, Minuto, Estado, Correo, Solicitante, Tipointeresado, Clasesolicitud, Tiposolicitud, Temasolicitud, Zona, Centro, Codpais, Coddepto, Codciudad, Escuela, Programa, Periodo, Numorigen, Pqrs, Detalle, Horafin, Minutofin, Paramercadeo, Responsable, Tiemprespdias, Tiempresphoras, Tiemprespminutos, Solucion, Caso';
	$registros=0;
	$bGigante=false; //En caso de que la tabla sea muy grande pasarlo a true
	$sLimite='';
	if ($bGigante){
		$sSQL='SELECT COUNT(1) AS Total 
FROM saiu20correo_'.$iVigencia.' AS TB, unad11terceros AS T12, saiu03temasol AS T16 
WHERE '.$sSQLadd1.' TB.saiu20idsolicitante=T12.unad11id AND TB.saiu20temasolicitud=T16.saiu03id '.$sSQLadd.'';
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
	//, TB.saiu20numtelefono, TB.saiu20correoorigen, TB.saiu20idpqrs, TB.saiu20detalle, TB.saiu20horafin, TB.saiu20minutofin, TB.saiu20paramercadeo, TB.saiu20tiemprespdias, TB.saiu20tiempresphoras, TB.saiu20tiemprespminutos, TB.saiu20tiporadicado, TB.saiu20idtelefono, TB.saiu20tipointeresado, TB.saiu20clasesolicitud, TB.saiu20tiposolicitud, TB.saiu20temasolicitud, TB.saiu20idzona, TB.saiu20idcentro, TB.saiu20codpais, TB.saiu20coddepto, TB.saiu20codciudad, TB.saiu20idescuela, TB.saiu20idprograma, TB.saiu20idperiodo, TB.saiu20idresponsable
	$sSQL='SELECT TB.saiu20agno, TB.saiu20mes, TB.saiu20consec, TB.saiu20id, TB.saiu20dia, TB.saiu20hora, TB.saiu20minuto, T12.unad11razonsocial AS C12_nombre, T16.saiu03titulo, TB.saiu20estado, T12.unad11tipodoc AS C12_td, T12.unad11doc AS C12_doc, TB.saiu20idsolicitante 
FROM saiu20correo_'.$iVigencia.' AS TB, unad11terceros AS T12, saiu03temasol AS T16 
WHERE '.$sSQLadd1.' TB.saiu20idsolicitante=T12.unad11id AND TB.saiu20temasolicitud=T16.saiu03id '.$sSQLadd.'
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
				//return array(utf8_encode($sErrConsulta.'<input id="paginaf3020" name="paginaf3020" type="hidden" value="'.$pagina.'"/><input id="lppf3020" name="lppf3020" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
<td><b>'.$ETI['saiu20temasolicitud'].'</b></td>
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
		/*
		$et_saiu20horafin=html_TablaHoraMin($filadet['saiu20horafin'], $filadet['saiu20minutofin']);
		$et_saiu20idresponsable_doc='';
		$et_saiu20idresponsable_nombre='';
		if ($filadet['saiu20idresponsable']!=0){
			$et_saiu20idresponsable_doc=$sPrefijo.$filadet['C30_td'].' '.$filadet['C30_doc'].$sSufijo;
			$et_saiu20idresponsable_nombre=$sPrefijo.cadena_notildes($filadet['C30_nombre']).$sSufijo;
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
<td>'.$sPrefijo.$aEstado[$filadet['saiu20estado']].$sSufijo.'</td>
<td>'.$et_saiu20idsolicitante_doc.'</td>
<td>'.$et_saiu20idsolicitante_nombre.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['saiu03titulo']).$sSufijo.'</td>
<td>'.$sLink.'</td>
</tr>';
		}
	$res=$res.'</table>';
	$objDB->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
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
	$DATA['saiu20agno']=numeros_validar($DATA['saiu20agno']);
	$DATA['saiu20mes']=numeros_validar($DATA['saiu20mes']);
	$DATA['saiu20tiporadicado']=numeros_validar($DATA['saiu20tiporadicado']);
	$DATA['saiu20consec']=numeros_validar($DATA['saiu20consec']);
	$DATA['saiu20dia']=numeros_validar($DATA['saiu20dia']);
	$DATA['saiu20hora']=numeros_validar($DATA['saiu20hora']);
	$DATA['saiu20minuto']=numeros_validar($DATA['saiu20minuto']);
	$DATA['saiu20idcorreo']=numeros_validar($DATA['saiu20idcorreo']);
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
	$DATA['saiu20horafin']=numeros_validar($DATA['saiu20horafin']);
	$DATA['saiu20minutofin']=numeros_validar($DATA['saiu20minutofin']);
	$DATA['saiu20paramercadeo']=numeros_validar($DATA['saiu20paramercadeo']);
	$DATA['saiu20solucion']=numeros_validar($DATA['saiu20solucion']);
	$DATA['saiu20respuesta']=htmlspecialchars(trim($DATA['saiu20respuesta']));
	// -- Se inicializan las variables que puedan pasar vacias {Especialmente números}.
	//if ($DATA['saiu20dia']==''){$DATA['saiu20dia']=0;}
	//if ($DATA['saiu20hora']==''){$DATA['saiu20hora']=0;}
	//if ($DATA['saiu20minuto']==''){$DATA['saiu20minuto']=0;}
	if ($DATA['saiu20estado']==''){$DATA['saiu20estado']=0;}
	//if ($DATA['saiu20idcorreo']==''){$DATA['saiu20idcorreo']=0;}
	//if ($DATA['saiu20tipointeresado']==''){$DATA['saiu20tipointeresado']=0;}
	if ($DATA['saiu20idpqrs']==''){$DATA['saiu20idpqrs']=0;}
	//if ($DATA['saiu20paramercadeo']==''){$DATA['saiu20paramercadeo']=0;}
	//if ($DATA['saiu20solucion']==''){$DATA['saiu20solucion']=0;}
	// -- Seccion para validar los posibles causales de error.
	$sSepara=', ';
	$bConCierre=false;
	if ($DATA['saiu20estado']==7){
		$bConCierre=true;
		if ($DATA['saiu20solucion']==''){
			$sError=$ERR['saiu20solucion'].$sSepara.$sError;
			}else{
			if ((int)$DATA['saiu20solucion']==0){
				$sError=$ERR['saiu20solucion_proceso'].$sSepara.$sError;
				}
			}
		if ($DATA['saiu20idresponsable']==0){$sError=$ERR['saiu20idresponsable'].$sSepara.$sError;}
		if ($DATA['saiu20paramercadeo']==''){$sError=$ERR['saiu20paramercadeo'].$sSepara.$sError;}
		//if ($DATA['saiu20minutofin']==''){$sError=$ERR['saiu20minutofin'].$sSepara.$sError;}
		//if ($DATA['saiu20horafin']==''){$sError=$ERR['saiu20horafin'].$sSepara.$sError;}
		if ($DATA['saiu20detalle']==''){$sError=$ERR['saiu20detalle'].$sSepara.$sError;}
		if ($DATA['saiu20correoorigen']==''){$sError=$ERR['saiu20correoorigen'].$sSepara.$sError;}
		if ($DATA['saiu20idperiodo']==''){$sError=$ERR['saiu20idperiodo'].$sSepara.$sError;}
		if ($DATA['saiu20idprograma']==''){$sError=$ERR['saiu20idprograma'].$sSepara.$sError;}
		if ($DATA['saiu20idescuela']==''){$sError=$ERR['saiu20idescuela'].$sSepara.$sError;}
		if ($DATA['saiu20codciudad']==''){$sError=$ERR['saiu20codciudad'].$sSepara.$sError;}
		if ($DATA['saiu20coddepto']==''){$sError=$ERR['saiu20coddepto'].$sSepara.$sError;}
		if ($DATA['saiu20codpais']==''){$sError=$ERR['saiu20codpais'].$sSepara.$sError;}
		if ($DATA['saiu20idcentro']==''){$sError=$ERR['saiu20idcentro'].$sSepara.$sError;}
		if ($DATA['saiu20idzona']==''){$sError=$ERR['saiu20idzona'].$sSepara.$sError;}
		if ($DATA['saiu20temasolicitud']==''){$sError=$ERR['saiu20temasolicitud'].$sSepara.$sError;}
		if ($DATA['saiu20tiposolicitud']==''){$sError=$ERR['saiu20tiposolicitud'].$sSepara.$sError;}
		if ($DATA['saiu20clasesolicitud']==''){$sError=$ERR['saiu20clasesolicitud'].$sSepara.$sError;}
		if ($DATA['saiu20tipointeresado']==''){$sError=$ERR['saiu20tipointeresado'].$sSepara.$sError;}
		if ($DATA['saiu20idsolicitante']==0){$sError=$ERR['saiu20idsolicitante'].$sSepara.$sError;}
		if ($DATA['saiu20idcorreo']==''){$sError=$ERR['saiu20idcorreo'].$sSepara.$sError;}
		if ($DATA['saiu20minuto']==''){$sError=$ERR['saiu20minuto'].$sSepara.$sError;}
		if ($DATA['saiu20hora']==''){$sError=$ERR['saiu20hora'].$sSepara.$sError;}
		if ($DATA['saiu20dia']==''){$sError=$ERR['saiu20dia'].$sSepara.$sError;}
		//if ($DATA['saiu20hora']==''){$DATA['saiu20hora']=fecha_hora();}
		if ($sError!=''){$DATA['saiu20estado']=2;}
		$sErrorCerrando=$sError;
		$sError='';
		//Fin de las valiaciones NO LLAVE.
		}
	if (true){
		switch($DATA['saiu20estado']){
			case 7: //Logra cerrar
			case 8: //Solicitud abandonada
			case 9: //Cancelada por el usuario
			if ($DATA['saiu20minutofin']==''){$DATA['saiu20minutofin']=fecha_minuto();}
			if ($DATA['saiu20horafin']==''){$DATA['saiu20horafin']=fecha_hora();}
			//$DATA['saiu20minutofin']=fecha_minuto();
			//$DATA['saiu20horafin']=fecha_hora();
			break; 
			default:
			$DATA['saiu20estado']=2;
			if ($DATA['saiu20hora']==''){$DATA['saiu20hora']=fecha_hora();}
			if ($DATA['saiu20minuto']==''){$DATA['saiu20minuto']=fecha_minuto();}
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
	$iDiaIni=($DATA['saiu20agno']*10000)+($DATA['saiu20mes']*100)+$DATA['saiu20dia'];
	if ($bConCierre){
		//Validaciones previas a cerrar
		if ($DATA['saiu20estado']==7){
			list($DATA['saiu20tiemprespdias'], $DATA['saiu20tiempresphoras'], $DATA['saiu20tiemprespminutos'])=Tiempo_MinutosCalendario($iDiaIni, $DATA['saiu20hora'], $DATA['saiu20minuto'], $iDiaIni, $DATA['saiu20horafin'], $DATA['saiu20minutofin']);
			}
		//Aprobó las Validaciones al cerrar
		if ($sError.$sErrorCerrando!=''){
			$DATA['saiu20estado']=2;
			$sErrorCerrando=$sError.' '.$sErrorCerrando;
			$sError='';
			}else{
			$bCerrando=true;
			//Acciones del cierre
			}
		}
	// -- Fin del cerrado.
	// -- Se verifican los valores de campos de otras tablas.
	$sTabla19='saiu20correo_'.$DATA['saiu20agno'];
	if ($DATA['saiu20idresponsable_doc']!=''){
		if ($sError==''){$sError=tabla_terceros_existe($DATA['saiu20idresponsable_td'], $DATA['saiu20idresponsable_doc'], $objDB, 'El tercero Responsable ');}
		if ($sError==''){
			list($sError, $sInfo)=tercero_Bloqueado($DATA['saiu20idresponsable'], $objDB);
			if ($sInfo!=''){$sError=$sError.'<br>'.sInfo;}
			}
		}
	if ($DATA['saiu20idsolicitante_doc']!=''){
		if ($sError==''){$sError=tabla_terceros_existe($DATA['saiu20idsolicitante_td'], $DATA['saiu20idsolicitante_doc'], $objDB, 'El tercero Solicitante ');}
		if ($sError==''){
			list($sError, $sInfo)=tercero_Bloqueado($DATA['saiu20idsolicitante'], $objDB);
			if ($sInfo!=''){$sError=$sError.'<br>'.sInfo;}
			}
		}
	$bQuitarCodigo=false;
	$sCampoCodigo='';
	if ($sError==''){
		if ($DATA['paso']==10){
			if ($DATA['saiu20consec']==''){
				$DATA['saiu20consec']=tabla_consecutivo($sTabla19, 'saiu20consec', 'saiu20agno='.$DATA['saiu20agno'].' AND saiu20mes='.$DATA['saiu20mes'].' AND saiu20tiporadicado='.$DATA['saiu20tiporadicado'].'', $objDB);
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
				$sSQL='SELECT 1 FROM '.$sTabla19.' WHERE saiu20agno='.$DATA['saiu20agno'].' AND saiu20mes='.$DATA['saiu20mes'].' AND saiu20tiporadicado='.$DATA['saiu20tiporadicado'].' AND saiu20consec='.$DATA['saiu20consec'].'';
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
			$DATA['saiu20id']=tabla_consecutivo($sTabla19,'saiu20id', '', $objDB);
			if ($DATA['saiu20id']==-1){$sError=$objDB->serror;}
			}else{
			//Validar que no haya error en la hora y el minuto inicial.
			}
		}
	$idSolicitantePrevio=0;
	if ($sError==''){
		if (get_magic_quotes_gpc()==1){$DATA['saiu20detalle']=stripslashes($DATA['saiu20detalle']);}
		//Si el campo saiu20detalle permite html quite la linea htmlspecialchars para el campo y habilite la siguiente linea:
		//$saiu20detalle=addslashes($DATA['saiu20detalle']);
		$saiu20detalle=str_replace('"', '\"', $DATA['saiu20detalle']);
		$bpasa=false;
		if ($DATA['paso']==10){
			$DATA['saiu20idpqrs']=0;
			$DATA['saiu20tiemprespdias']=0;
			$DATA['saiu20tiempresphoras']=0;
			$DATA['saiu20tiemprespminutos']=0;
			$DATA['saiu20idcaso']=0;
			$sCampos3020='saiu20agno, saiu20mes, saiu20tiporadicado, saiu20consec, saiu20id, 
saiu20dia, saiu20hora, saiu20minuto, saiu20estado, saiu20idcorreo, 
saiu20idsolicitante, saiu20tipointeresado, saiu20clasesolicitud, saiu20tiposolicitud, saiu20temasolicitud, 
saiu20idzona, saiu20idcentro, saiu20codpais, saiu20coddepto, saiu20codciudad, 
saiu20idescuela, saiu20idprograma, saiu20idperiodo, saiu20correoorigen, saiu20idpqrs, 
saiu20detalle, saiu20horafin, saiu20minutofin, saiu20paramercadeo, saiu20idresponsable, 
saiu20tiemprespdias, saiu20tiempresphoras, saiu20tiemprespminutos, saiu20solucion, saiu20idcaso, 
saiu20respuesta';
			$sValores3020=''.$DATA['saiu20agno'].', '.$DATA['saiu20mes'].', '.$DATA['saiu20tiporadicado'].', '.$DATA['saiu20consec'].', '.$DATA['saiu20id'].', 
'.$DATA['saiu20dia'].', '.$DATA['saiu20hora'].', '.$DATA['saiu20minuto'].', '.$DATA['saiu20estado'].', '.$DATA['saiu20idcorreo'].', 
'.$DATA['saiu20idsolicitante'].', '.$DATA['saiu20tipointeresado'].', '.$DATA['saiu20clasesolicitud'].', '.$DATA['saiu20tiposolicitud'].', '.$DATA['saiu20temasolicitud'].', 
'.$DATA['saiu20idzona'].', '.$DATA['saiu20idcentro'].', "'.$DATA['saiu20codpais'].'", "'.$DATA['saiu20coddepto'].'", "'.$DATA['saiu20codciudad'].'", 
'.$DATA['saiu20idescuela'].', '.$DATA['saiu20idprograma'].', '.$DATA['saiu20idperiodo'].', "'.$DATA['saiu20correoorigen'].'", '.$DATA['saiu20idpqrs'].', 
"'.$saiu20detalle.'", '.$DATA['saiu20horafin'].', '.$DATA['saiu20minutofin'].', '.$DATA['saiu20paramercadeo'].', '.$DATA['saiu20idresponsable'].', 
'.$DATA['saiu20tiemprespdias'].', '.$DATA['saiu20tiempresphoras'].', '.$DATA['saiu20tiemprespminutos'].', '.$DATA['saiu20solucion'].', '.$DATA['saiu20idcaso'].', 
"'.$DATA['saiu20respuesta'].'"';
			if ($APP->utf8==1){
				$sSQL='INSERT INTO '.$sTabla19.' ('.$sCampos3020.') VALUES ('.utf8_encode($sValores3020).');';
				$sdetalle=$sCampos3020.'['.utf8_encode($sValores3020).']';
				}else{
				$sSQL='INSERT INTO '.$sTabla19.' ('.$sCampos3020.') VALUES ('.$sValores3020.');';
				$sdetalle=$sCampos3020.'['.$sValores3020.']';
				}
			$idAccion=2;
			$bpasa=true;
			}else{
			$scampo[1]='saiu20dia';
			$scampo[2]='saiu20hora';
			$scampo[3]='saiu20minuto';
			$scampo[4]='saiu20idcorreo';
			$scampo[5]='saiu20idsolicitante';
			$scampo[6]='saiu20tipointeresado';
			$scampo[7]='saiu20clasesolicitud';
			$scampo[8]='saiu20temasolicitud';
			$scampo[9]='saiu20idzona';
			$scampo[10]='saiu20idcentro';
			$scampo[11]='saiu20codpais';
			$scampo[12]='saiu20coddepto';
			$scampo[13]='saiu20codciudad';
			$scampo[14]='saiu20idescuela';
			$scampo[15]='saiu20idprograma';
			$scampo[16]='saiu20idperiodo';
			$scampo[17]='saiu20correoorigen';
			$scampo[18]='saiu20detalle';
			$scampo[19]='saiu20horafin';
			$scampo[20]='saiu20minutofin';
			$scampo[21]='saiu20paramercadeo';
			$scampo[22]='saiu20idresponsable';
			$scampo[23]='saiu20solucion';
			$scampo[24]='saiu20tiposolicitud';
			$scampo[25]='saiu20estado';
			$scampo[26]='saiu20tiemprespdias';
			$scampo[27]='saiu20tiempresphoras';
			$scampo[28]='saiu20tiemprespminutos';
			$scampo[29]='saiu20respuesta';
			$sdato[1]=$DATA['saiu20dia'];
			$sdato[2]=$DATA['saiu20hora'];
			$sdato[3]=$DATA['saiu20minuto'];
			$sdato[4]=$DATA['saiu20idcorreo'];
			$sdato[5]=$DATA['saiu20idsolicitante'];
			$sdato[6]=$DATA['saiu20tipointeresado'];
			$sdato[7]=$DATA['saiu20clasesolicitud'];
			$sdato[8]=$DATA['saiu20temasolicitud'];
			$sdato[9]=$DATA['saiu20idzona'];
			$sdato[10]=$DATA['saiu20idcentro'];
			$sdato[11]=$DATA['saiu20codpais'];
			$sdato[12]=$DATA['saiu20coddepto'];
			$sdato[13]=$DATA['saiu20codciudad'];
			$sdato[14]=$DATA['saiu20idescuela'];
			$sdato[15]=$DATA['saiu20idprograma'];
			$sdato[16]=$DATA['saiu20idperiodo'];
			$sdato[17]=$DATA['saiu20correoorigen'];
			$sdato[18]=$saiu20detalle;
			$sdato[19]=$DATA['saiu20horafin'];
			$sdato[20]=$DATA['saiu20minutofin'];
			$sdato[21]=$DATA['saiu20paramercadeo'];
			$sdato[22]=$DATA['saiu20idresponsable'];
			$sdato[23]=$DATA['saiu20solucion'];
			$sdato[24]=$DATA['saiu20tiposolicitud'];
			$sdato[25]=$DATA['saiu20estado'];
			$sdato[26]=$DATA['saiu20tiemprespdias'];
			$sdato[27]=$DATA['saiu20tiempresphoras'];
			$sdato[28]=$DATA['saiu20tiemprespminutos'];
			$sdato[29]=$DATA['saiu20respuesta'];
			$numcmod=29;
			$sWhere='saiu20id='.$DATA['saiu20id'].'';
			$sSQL='SELECT * FROM '.$sTabla19.' WHERE '.$sWhere;
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
	$sTabla19='saiu20correo_'.$saiu20agno;
	if ($sError==''){
		$sSQL='SELECT * FROM '.$sTabla19.' WHERE saiu20id='.$saiu20id.'';
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
		$sSQL='DELETE FROM '.$sTabla19.' WHERE '.$sWhere.';';
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
	return 'Busqueda de Atenciones por Correo';
	}
function f3020_ParametrosBusqueda(){
	$sParams='<label class="Label90">Nombre</label><label><input id="b3020nombre" name="b3020nombre" type="text" value="" onchange="paginarbusqueda()" /></label>';
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
			//return array(utf8_encode($sErrConsulta.'<input id="paginaf3020" name="paginaf3020" type="hidden" value="'.$pagina.'"/><input id="lppf3020" name="lppf3020" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
<td>'.$sPrefijo.$filadet['saiu20tiporadicado'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu20consec'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu20dia'].$sSufijo.'</td>
<td>'.$sPrefijo.$et_saiu20hora.$sSufijo.'</td>
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
<td>'.$sPrefijo.$filadet['saiu20codpais'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu20coddepto'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu20codciudad'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['core12nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['core09nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['exte02nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['saiu20correoorigen']).$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu20idpqrs'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu20detalle'].$sSufijo.'</td>
<td>'.$sPrefijo.$et_saiu20horafin.$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu20paramercadeo'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['C30_td'].' '.$filadet['C30_doc'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['C30_nombre']).$sSufijo.'</td>
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
	return utf8_encode($res);
	}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------
?>