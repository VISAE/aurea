<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2020 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.24.1 martes, 11 de febrero de 2020
--- Modelo Versión 2.25.3 martes, 14 de julio de 2020
--- 3003 saiu03temasol
*/
/** Archivo lib3003.php.
* Libreria 3003 saiu03temasol.
* @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
* @date martes, 11 de febrero de 2020
*/
function f3003_HTMLComboV2_saiu03tiposol($objDB, $objCombos, $valor){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('saiu03tiposol', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	//$objCombos->iAncho=450;
	$objCombos->sAccion='RevisaLlave();';
	//$sSQL='SELECT saiu02id AS id, saiu02titulo AS nombre FROM saiu02tiposol WHERE saiu02id>0 ORDER BY saiu02titulo';
	// AND TB.saiu02ordenllamada<9
	$sSQL='SELECT TB.saiu02id AS id, CONCAT(TB.saiu02titulo, " [", T1.saiu01titulo, "]") AS nombre 
	FROM saiu02tiposol AS TB, saiu01claseser AS T1 
	WHERE TB.saiu02id>0 AND TB.saiu02clasesol=T1.saiu01id 
	ORDER BY TB.saiu02titulo';
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f3003_HTMLComboV2_saiu03idequiporesp1($objDB, $objCombos, $valor, $vrsaiu03idunidadresp1){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('saiu03idequiporesp1', $valor, true, '{'.$ETI['msg_ninguno'].'}', 0);
	$sSQL='';
	if ($vrsaiu03idunidadresp1!=''){
		$objCombos->iAncho=300;
		$sSQL='SELECT bita27id AS id, bita27nombre AS nombre FROM bita27equipotrabajo WHERE bita27idunidadfunc="'.$vrsaiu03idunidadresp1.'" ORDER BY bita27nombre';
		}
	$objCombos->sAccion='consultalider(1);';
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f3003_HTMLComboV2_saiu03idequiporesp2($objDB, $objCombos, $valor, $vrsaiu03idunidadresp2){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('saiu03idequiporesp2', $valor, true, '{'.$ETI['msg_ninguno'].'}', 0);
	$sSQL='';
	if ($vrsaiu03idunidadresp2!=''){
		$objCombos->iAncho=300;
		$sSQL='SELECT bita27id AS id, bita27nombre AS nombre FROM bita27equipotrabajo WHERE bita27idunidadfunc="'.$vrsaiu03idunidadresp2.'" ORDER BY bita27nombre';
		}
	$objCombos->sAccion='consultalider(2);';
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f3003_HTMLComboV2_saiu03idequiporesp3($objDB, $objCombos, $valor, $vrsaiu03idunidadresp3){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('saiu03idequiporesp3', $valor, true, '{'.$ETI['msg_ninguno'].'}', 0);
	$sSQL='';
	if ($vrsaiu03idunidadresp3!=''){
		$objCombos->iAncho=300;
		$sSQL='SELECT bita27id AS id, bita27nombre AS nombre FROM bita27equipotrabajo WHERE bita27idunidadfunc="'.$vrsaiu03idunidadresp3.'" ORDER BY bita27nombre';
		}
	$objCombos->sAccion='consultalider(3);';
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f3003_Combosaiu03idequiporesp1($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$objCombos=new clsHtmlCombos('n');
	$html_saiu03idequiporesp1=f3003_HTMLComboV2_saiu03idequiporesp1($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_saiu03idequiporesp1', 'innerHTML', $html_saiu03idequiporesp1);
	//$objResponse->call('$("#saiu03idequiporesp1").chosen()');
	return $objResponse;
	}
function f3003_Combosaiu03idequiporesp2($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$objCombos=new clsHtmlCombos('n');
	$html_saiu03idequiporesp2=f3003_HTMLComboV2_saiu03idequiporesp2($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_saiu03idequiporesp2', 'innerHTML', $html_saiu03idequiporesp2);
	//$objResponse->call('$("#saiu03idequiporesp2").chosen()');
	return $objResponse;
	}
function f3003_Combosaiu03idequiporesp3($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$objCombos=new clsHtmlCombos('n');
	$html_saiu03idequiporesp3=f3003_HTMLComboV2_saiu03idequiporesp3($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_saiu03idequiporesp3', 'innerHTML', $html_saiu03idequiporesp3);
	//$objResponse->call('$("#saiu03idequiporesp3").chosen()');
	return $objResponse;
	}
function f3003_ExisteDato($datos){
	if(!is_array($datos)){$datos=json_decode(str_replace('\"','"',$datos),true);}
	$_SESSION['u_ultimominuto']=iminutoavance();
	$bHayLlave=true;
	$saiu03tiposol=numeros_validar($datos[1]);
	if ($saiu03tiposol==''){$bHayLlave=false;}
	$saiu03consec=numeros_validar($datos[2]);
	if ($saiu03consec==''){$bHayLlave=false;}
	if ($bHayLlave){
		require './app.php';
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		$sSQL='SELECT saiu03consec FROM saiu03temasol WHERE saiu03tiposol='.$saiu03tiposol.' AND saiu03consec='.$saiu03consec.'';
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
function f3003_Busquedas($aParametros){
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3003='lg/lg_3003_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3003)){$mensajes_3003='lg/lg_3003_es.php';}
	require $mensajes_todas;
	require $mensajes_3003;
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
		case 'saiu03idliderrespon1':
		require $APP->rutacomun.'lib111.php';
		$sTabla=f111_TablaDetalleBusquedas($aParametrosB, $objDB);
		$sTitulo='Busqueda de terceros';
		$sParams=f111_ParametrosBusqueda();
		$sJavaBusqueda=f111_JavaScriptBusqueda(3003);
		break;
		case 'saiu03idliderrespon2':
		require $APP->rutacomun.'lib111.php';
		$sTabla=f111_TablaDetalleBusquedas($aParametrosB, $objDB);
		$sTitulo='Busqueda de terceros';
		$sParams=f111_ParametrosBusqueda();
		$sJavaBusqueda=f111_JavaScriptBusqueda(3003);
		break;
		case 'saiu03idliderrespon3':
		require $APP->rutacomun.'lib111.php';
		$sTabla=f111_TablaDetalleBusquedas($aParametrosB, $objDB);
		$sTitulo='Busqueda de terceros';
		$sParams=f111_ParametrosBusqueda();
		$sJavaBusqueda=f111_JavaScriptBusqueda(3003);
		break;
		}
	$sTitulo='<h2>'.$ETI['titulo_3003'].' - '.$sTitulo.'</h2>';
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97titulo', 'innerHTML', $sTitulo);
	$objResponse->assign('div_97params', 'innerHTML', $sParams);
	$objResponse->assign('div_97tabla', 'innerHTML', $sTabla);
	$objResponse->setFunction('paginarbusqueda','',$sJavaBusqueda);
	$objResponse->call('expandesector(97)');
	return $objResponse;
	}
function f3003_HtmlBusqueda($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$sError='';
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$sDetalle='';
	switch($aParametros[100]){
		case 'saiu03idliderrespon1':
		require $APP->rutacomun.'lib111.php';
		$sDetalle=f111_TablaDetalleBusquedas($aParametros, $objDB);
		break;
		case 'saiu03idliderrespon2':
		require $APP->rutacomun.'lib111.php';
		$sDetalle=f111_TablaDetalleBusquedas($aParametros, $objDB);
		break;
		case 'saiu03idliderrespon3':
		require $APP->rutacomun.'lib111.php';
		$sDetalle=f111_TablaDetalleBusquedas($aParametros, $objDB);
		break;
		}
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97tabla', 'innerHTML', $sDetalle);
	return $objResponse;
	}
function f3003_TablaDetalleV2($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3003='lg/lg_3003_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3003)){$mensajes_3003='lg/lg_3003_es.php';}
	require $mensajes_todas;
	require $mensajes_3003;
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	if (isset($aParametros[100])==0){$aParametros[100]=$_SESSION['unad_id_tercero'];}
	if (isset($aParametros[101])==0){$aParametros[101]=1;}
	if (isset($aParametros[102])==0){$aParametros[102]=20;}
	if (isset($aParametros[103])==0){$aParametros[103]='';}
	if (isset($aParametros[104])==0){$aParametros[104]='';}
	if (isset($aParametros[105])==0){$aParametros[105]='';}
	if (isset($aParametros[106])==0){$aParametros[106]='';}
	if (isset($aParametros[107])==0){$aParametros[107]='';}
	//$aParametros[103]=numeros_validar($aParametros[103]);
	$idTercero=$aParametros[100];
	$sDebug='';
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
		return array($sLeyenda.'<input id="paginaf3003" name="paginaf3003" type="hidden" value="'.$pagina.'"/><input id="lppf3003" name="lppf3003" type="hidden" value="'.$lineastabla.'"/>', $sDebug);
		die();
		}
/*
	$aEstado=array();
	$sSQL='SELECT id, nombre FROM tabla';
	$tabla=$objDB->ejecutasql($sSQL);
	while($fila=$objDB->sf($tabla)){
		$aEstado[$fila['id']]=cadena_notildes($fila['nombre']);
		}
*/
	$sSQLadd='';
	$sSQLadd1='';
	//if ((int)$aParametros[103]!=-1){$sSQLadd=$sSQLadd.' AND TB.campo='.$aParametros[103];}
	if ($aParametros[104]!=''){$sSQLadd1=$sSQLadd1.'TB.saiu03tiposol='.$aParametros[104].' AND ';}
	if ($aParametros[105]!=''){$sSQLadd1=$sSQLadd1.'TB.saiu03idunidadresp1='.$aParametros[105].' AND ';}
	if ($aParametros[107]!=''){$sSQLadd1=$sSQLadd1.'TB.saiu03tiposol='.$aParametros[107].' AND ';}
	if ($aParametros[103]!=''){
		$sBase=trim(strtoupper($aParametros[103]));
		$aNoms=explode(' ', $sBase);
		for ($k=1;$k<=count($aNoms);$k++){
			$sCadena=$aNoms[$k-1];
			if ($sCadena!=''){
				//$sSQLadd=$sSQLadd.' AND T6.unad11razonsocial LIKE "%'.$sCadena.'%"';
				$sSQLadd1=$sSQLadd1.'TB.saiu03titulo LIKE "%'.$sCadena.'%" AND ';
				}
			}
		}
	$sOrden='';
	$sTituloAdd='';
	$sCampoOrden='';
	$bConOrden=false;
	switch($aParametros[106]){
		case 3018:
		$sOrden=', TB.saiu03ordenllamada';
		$sCampoOrden=', TB.saiu03ordenllamada AS Orden';
		$bConOrden=true;
		break;
		case 3019:
		$sOrden=', TB.saiu03ordenchat';
		$sCampoOrden=', TB.saiu03ordenchat AS Orden';
		$bConOrden=true;
		break;
		case 3005:
		$sOrden=', TB.saiu03ordenpqrs';
		$sCampoOrden=', TB.saiu03ordenpqrs AS Orden';
		$bConOrden=true;
		break;
		case 3023:
		$sOrden=', TB.saiu03ordensoporte';
		$sCampoOrden=', TB.saiu03ordensoporte AS Orden';
		$bConOrden=true;
		break;
		case 3024:
		$sOrden=', TB.saiu03ordentramites';
		$sCampoOrden=', TB.saiu03ordentramites AS Orden';
		$bConOrden=true;
		break;
		case 3025:
		$sOrden=', TB.saiu03ordencorresp';
		$sCampoOrden=', TB.saiu03ordencorresp AS Orden';
		$bConOrden=true;
		break;
		}
	if ($bConOrden){
		$sTituloAdd='<td><b>'.$ETI['msg_orden'].'</b></td>';
		}
	$sTitulos='Tiposol, Consec, Id, Activo, Titulo, Descripcion, Ayuda, Obligaconf, Numetapas, Nometapa1, Unidadresp1, Equiporesp1, Liderrespon1, Tiemprespdias1, Tiempresphoras1, Nometapa2, Unidadresp2, Equiporesp2, Liderrespon2, Tiemprespdias2, Tiempresphoras2, Nometapa3, Unidadresp3, Equiporesp3, Liderrespon3, Tiemprespdias3, Tiempresphoras3, Otrosusaurios, Consupervisor, Anonimos, Anexoslibres, Moduloasociado';
	//, TB.saiu03descripcion, TB.saiu03ayuda, TB.saiu03obligaconf, TB.saiu03nometapa1, TB.saiu03tiemprespdias1, TB.saiu03tiempresphoras1, TB.saiu03nometapa2, TB.saiu03tiemprespdias2, TB.saiu03tiempresphoras2, TB.saiu03nometapa3, TB.saiu03tiemprespdias3, TB.saiu03tiempresphoras3, TB.saiu03otrosusaurios, TB.saiu03consupervisor, TB.saiu03anonimos, TB.saiu03anexoslibres, TB.saiu03moduloasociado, TB.saiu03tiposol, TB.saiu03idunidadresp1, TB.saiu03idequiporesp1, TB.saiu03idliderrespon1, TB.saiu03idunidadresp2, TB.saiu03idequiporesp2, TB.saiu03idliderrespon2, TB.saiu03idunidadresp3, TB.saiu03idequiporesp3, TB.saiu03idliderrespon3
	$sSQL='SELECT TB.saiu03consec, TB.saiu03id, TB.saiu03activo, TB.saiu03titulo, TB.saiu03numetapas'.$sCampoOrden.' 
FROM saiu03temasol AS TB 
WHERE '.$sSQLadd1.' TB.saiu03id>0 '.$sSQLadd.'
ORDER BY TB.saiu03activo DESC'.$sOrden.', TB.saiu03titulo';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_3003" name="consulta_3003" type="hidden" value="'.$sSQLlista.'"/>
<input id="titulos_3003" name="titulos_3003" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objDB->ejecutasql($sSQL);
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 3003: '.$sSQL.'<br>';}
	if ($tabladetalle==false){
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		//$sLeyenda=$sSQL;
		}else{
		$registros=$objDB->nf($tabladetalle);
		if ($registros==0){
			//return array(utf8_encode($sErrConsulta.'<input id="paginaf3003" name="paginaf3003" type="hidden" value="'.$pagina.'"/><input id="lppf3003" name="lppf3003" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
<td><b>'.$ETI['saiu03consec'].'</b></td>
<td><b>'.$ETI['saiu03activo'].'</b></td>
<td><b>'.$ETI['saiu03titulo'].'</b></td>
<td><b>'.$ETI['saiu03numetapas'].'</b></td>'.$sTituloAdd.'
<td align="right">
'.html_paginador('paginaf3003', $registros, $lineastabla, $pagina, 'paginarf3003()').'
'.html_lpp('lppf3003', $lineastabla, 'paginarf3003()').'
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
		$et_saiu03activo=$sPrefijo.$ETI['no'].$sSufijo;
		if ($filadet['saiu03activo']=='S'){$et_saiu03activo=$sPrefijo.$ETI['si'].$sSufijo;}
		/*
		$et_saiu03obligaconf=$sPrefijo.$ETI['no'].$sSufijo;
		if ($filadet['saiu03obligaconf']=='S'){$et_saiu03obligaconf=$sPrefijo.$ETI['si'].$sSufijo;}
		$et_saiu03otrosusaurios=$sPrefijo.$ETI['no'].$sSufijo;
		if ($filadet['saiu03otrosusaurios']=='S'){$et_saiu03otrosusaurios=$sPrefijo.$ETI['si'].$sSufijo;}
		$et_saiu03consupervisor=$sPrefijo.$ETI['no'].$sSufijo;
		if ($filadet['saiu03consupervisor']=='S'){$et_saiu03consupervisor=$sPrefijo.$ETI['si'].$sSufijo;}
		$et_saiu03anonimos=$sPrefijo.$ETI['no'].$sSufijo;
		if ($filadet['saiu03anonimos']=='S'){$et_saiu03anonimos=$sPrefijo.$ETI['si'].$sSufijo;}
		$et_saiu03anexoslibres=$sPrefijo.$ETI['no'].$sSufijo;
		if ($filadet['saiu03anexoslibres']=='S'){$et_saiu03anexoslibres=$sPrefijo.$ETI['si'].$sSufijo;}
		*/
		if ($bAbierta){
			$sLink='<a href="javascript:cargaridf3003('.$filadet['saiu03id'].')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$sCampoAdd='';
		if ($bConOrden){
			$sCampoAdd='<td>'.$sPrefijo.$asaiu03orden[$filadet['Orden']].$sSufijo.'</td>';
			}
		//<td>'.$sPrefijo.cadena_notildes($filadet['saiu02titulo']).$sSufijo.'</td>
		$res=$res.'<tr'.$sClass.'>
<td>'.$sPrefijo.$filadet['saiu03consec'].$sSufijo.'</td>
<td>'.$sPrefijo.$et_saiu03activo.$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['saiu03titulo']).$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu03numetapas'].$sSufijo.'</td>'.$sCampoAdd.'
<td>'.$sLink.'</td>
</tr>';
		}
	$res=$res.'</table>';
	$objDB->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
	}
function f3003_HtmlTabla($aParametros){
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
	list($sDetalle, $sDebugTabla)=f3003_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f3003detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f3003_db_CargarPadre($DATA, $objDB, $bDebug=false){
	$sError='';
	$iTipoError=0;
	$sDebug='';
	require './app.php';
	$DATA['saiu03idliderrespon1_td']=$APP->tipo_doc;
	$DATA['saiu03idliderrespon1_doc']='';
	$DATA['saiu03idliderrespon2_td']=$APP->tipo_doc;
	$DATA['saiu03idliderrespon2_doc']='';
	$DATA['saiu03idliderrespon3_td']=$APP->tipo_doc;
	$DATA['saiu03idliderrespon3_doc']='';
	if ($DATA['paso']==1){
		$sSQLcondi='saiu03tiposol='.$DATA['saiu03tiposol'].' AND saiu03consec='.$DATA['saiu03consec'].'';
		}else{
		$sSQLcondi='saiu03id='.$DATA['saiu03id'].'';
		}
	$sSQL='SELECT * FROM saiu03temasol WHERE '.$sSQLcondi;
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$DATA['saiu03tiposol']=$fila['saiu03tiposol'];
		$DATA['saiu03consec']=$fila['saiu03consec'];
		$DATA['saiu03id']=$fila['saiu03id'];
		$DATA['saiu03activo']=$fila['saiu03activo'];
		$DATA['saiu03titulo']=$fila['saiu03titulo'];
		$DATA['saiu03descripcion']=$fila['saiu03descripcion'];
		$DATA['saiu03ayuda']=$fila['saiu03ayuda'];
		$DATA['saiu03obligaconf']=$fila['saiu03obligaconf'];
		$DATA['saiu03numetapas']=$fila['saiu03numetapas'];
		$DATA['saiu03nometapa1']=$fila['saiu03nometapa1'];
		$DATA['saiu03idunidadresp1']=$fila['saiu03idunidadresp1'];
		$DATA['saiu03idequiporesp1']=$fila['saiu03idequiporesp1'];
		$DATA['saiu03idliderrespon1']=$fila['saiu03idliderrespon1'];
		$DATA['saiu03tiemprespdias1']=$fila['saiu03tiemprespdias1'];
		$DATA['saiu03tiempresphoras1']=$fila['saiu03tiempresphoras1'];
		$DATA['saiu03nometapa2']=$fila['saiu03nometapa2'];
		$DATA['saiu03idunidadresp2']=$fila['saiu03idunidadresp2'];
		$DATA['saiu03idequiporesp2']=$fila['saiu03idequiporesp2'];
		$DATA['saiu03idliderrespon2']=$fila['saiu03idliderrespon2'];
		$DATA['saiu03tiemprespdias2']=$fila['saiu03tiemprespdias2'];
		$DATA['saiu03tiempresphoras2']=$fila['saiu03tiempresphoras2'];
		$DATA['saiu03nometapa3']=$fila['saiu03nometapa3'];
		$DATA['saiu03idunidadresp3']=$fila['saiu03idunidadresp3'];
		$DATA['saiu03idequiporesp3']=$fila['saiu03idequiporesp3'];
		$DATA['saiu03idliderrespon3']=$fila['saiu03idliderrespon3'];
		$DATA['saiu03tiemprespdias3']=$fila['saiu03tiemprespdias3'];
		$DATA['saiu03tiempresphoras3']=$fila['saiu03tiempresphoras3'];
		$DATA['saiu03otrosusaurios']=$fila['saiu03otrosusaurios'];
		$DATA['saiu03consupervisor']=$fila['saiu03consupervisor'];
		$DATA['saiu03anonimos']=$fila['saiu03anonimos'];
		$DATA['saiu03anexoslibres']=$fila['saiu03anexoslibres'];
		$DATA['saiu03moduloasociado']=$fila['saiu03moduloasociado'];
		$DATA['saiu03nivelrespuesta']=$fila['saiu03nivelrespuesta'];
		$DATA['saiu03consupervisor2']=$fila['saiu03consupervisor2'];
		$DATA['saiu03consupervisor3']=$fila['saiu03consupervisor3'];
		$DATA['saiu03infoprograma']=$fila['saiu03infoprograma'];
		$DATA['saiu03infoperiodos']=$fila['saiu03infoperiodos'];
		$DATA['saiu03requierepago']=$fila['saiu03requierepago'];
		$DATA['saiu03incluyemodelo']=$fila['saiu03incluyemodelo'];
		$DATA['saiu03modelo']=$fila['saiu03modelo'];
		$DATA['saiu03firmacertificada']=$fila['saiu03firmacertificada'];
		$DATA['saiu03ordenllamada']=$fila['saiu03ordenllamada'];
		$DATA['saiu03ordenchat']=$fila['saiu03ordenchat'];
		$DATA['saiu03ordencorreo']=$fila['saiu03ordencorreo'];
		$DATA['saiu03ordenpresencial']=$fila['saiu03ordenpresencial'];
		$DATA['saiu03ordensoporte']=$fila['saiu03ordensoporte'];
		$DATA['saiu03ordenpqrs']=$fila['saiu03ordenpqrs'];
		$DATA['saiu03ordentramites']=$fila['saiu03ordentramites'];
		$DATA['saiu03ordencorresp']=$fila['saiu03ordencorresp'];
		$bcargo=true;
		$DATA['paso']=2;
		$DATA['boculta3003']=0;
		$bLimpiaHijos=true;
		}else{
		$DATA['paso']=0;
		}
	return array($DATA, $sError, $iTipoError, $sDebug);
	}
function f3003_db_GuardarV2($DATA, $objDB, $bDebug=false){
	$iCodModulo=3003;
	$bAudita[2]=true;
	$bAudita[3]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3003='lg/lg_3003_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3003)){$mensajes_3003='lg/lg_3003_es.php';}
	require $mensajes_todas;
	require $mensajes_3003;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	// -- Se inicia validando todas las posibles entradas de usuario.
	/*
	if (isset($DATA['saiu03tiposol'])==0){$DATA['saiu03tiposol']='';}
	if (isset($DATA['saiu03consec'])==0){$DATA['saiu03consec']='';}
	if (isset($DATA['saiu03id'])==0){$DATA['saiu03id']='';}
	if (isset($DATA['saiu03activo'])==0){$DATA['saiu03activo']='';}
	if (isset($DATA['saiu03titulo'])==0){$DATA['saiu03titulo']='';}
	if (isset($DATA['saiu03descripcion'])==0){$DATA['saiu03descripcion']='';}
	if (isset($DATA['saiu03ayuda'])==0){$DATA['saiu03ayuda']='';}
	if (isset($DATA['saiu03obligaconf'])==0){$DATA['saiu03obligaconf']='';}
	if (isset($DATA['saiu03numetapas'])==0){$DATA['saiu03numetapas']='';}
	if (isset($DATA['saiu03nometapa1'])==0){$DATA['saiu03nometapa1']='';}
	if (isset($DATA['saiu03idunidadresp1'])==0){$DATA['saiu03idunidadresp1']='';}
	if (isset($DATA['saiu03idequiporesp1'])==0){$DATA['saiu03idequiporesp1']='';}
	if (isset($DATA['saiu03idliderrespon1'])==0){$DATA['saiu03idliderrespon1']='';}
	if (isset($DATA['saiu03tiemprespdias1'])==0){$DATA['saiu03tiemprespdias1']='';}
	if (isset($DATA['saiu03tiempresphoras1'])==0){$DATA['saiu03tiempresphoras1']='';}
	if (isset($DATA['saiu03nometapa2'])==0){$DATA['saiu03nometapa2']='';}
	if (isset($DATA['saiu03idunidadresp2'])==0){$DATA['saiu03idunidadresp2']='';}
	if (isset($DATA['saiu03idequiporesp2'])==0){$DATA['saiu03idequiporesp2']='';}
	if (isset($DATA['saiu03idliderrespon2'])==0){$DATA['saiu03idliderrespon2']='';}
	if (isset($DATA['saiu03tiemprespdias2'])==0){$DATA['saiu03tiemprespdias2']='';}
	if (isset($DATA['saiu03tiempresphoras2'])==0){$DATA['saiu03tiempresphoras2']='';}
	if (isset($DATA['saiu03nometapa3'])==0){$DATA['saiu03nometapa3']='';}
	if (isset($DATA['saiu03idunidadresp3'])==0){$DATA['saiu03idunidadresp3']='';}
	if (isset($DATA['saiu03idequiporesp3'])==0){$DATA['saiu03idequiporesp3']='';}
	if (isset($DATA['saiu03idliderrespon3'])==0){$DATA['saiu03idliderrespon3']='';}
	if (isset($DATA['saiu03tiemprespdias3'])==0){$DATA['saiu03tiemprespdias3']='';}
	if (isset($DATA['saiu03tiempresphoras3'])==0){$DATA['saiu03tiempresphoras3']='';}
	if (isset($DATA['saiu03otrosusaurios'])==0){$DATA['saiu03otrosusaurios']='';}
	if (isset($DATA['saiu03consupervisor'])==0){$DATA['saiu03consupervisor']='';}
	if (isset($DATA['saiu03anonimos'])==0){$DATA['saiu03anonimos']='';}
	if (isset($DATA['saiu03anexoslibres'])==0){$DATA['saiu03anexoslibres']='';}
	if (isset($DATA['saiu03nivelrespuesta'])==0){$DATA['saiu03nivelrespuesta']='';}
	if (isset($DATA['saiu03consupervisor2'])==0){$DATA['saiu03consupervisor2']='';}
	if (isset($DATA['saiu03consupervisor3'])==0){$DATA['saiu03consupervisor3']='';}
	if (isset($DATA['saiu03infoprograma'])==0){$DATA['saiu03infoprograma']='';}
	if (isset($DATA['saiu03infoperiodos'])==0){$DATA['saiu03infoperiodos']='';}
	if (isset($DATA['saiu03requierepago'])==0){$DATA['saiu03requierepago']='';}
	if (isset($DATA['saiu03incluyemodelo'])==0){$DATA['saiu03incluyemodelo']='';}
	if (isset($DATA['saiu03modelo'])==0){$DATA['saiu03modelo']='';}
	if (isset($DATA['saiu03firmacertificada'])==0){$DATA['saiu03firmacertificada']='';}
	if (isset($DATA['saiu03ordenllamada'])==0){$DATA['saiu03ordenllamada']='';}
	if (isset($DATA['saiu03ordenchat'])==0){$DATA['saiu03ordenchat']='';}
	if (isset($DATA['saiu03ordencorreo'])==0){$DATA['saiu03ordencorreo']='';}
	if (isset($DATA['saiu03ordenpresencial'])==0){$DATA['saiu03ordenpresencial']='';}
	if (isset($DATA['saiu03ordensoporte'])==0){$DATA['saiu03ordensoporte']='';}
	if (isset($DATA['saiu03ordenpqrs'])==0){$DATA['saiu03ordenpqrs']='';}
	if (isset($DATA['saiu03ordentramites'])==0){$DATA['saiu03ordentramites']='';}
	if (isset($DATA['saiu03ordencorresp'])==0){$DATA['saiu03ordencorresp']='';}
	*/
	$DATA['saiu03tiposol']=numeros_validar($DATA['saiu03tiposol']);
	$DATA['saiu03consec']=numeros_validar($DATA['saiu03consec']);
	$DATA['saiu03activo']=htmlspecialchars(trim($DATA['saiu03activo']));
	$DATA['saiu03titulo']=htmlspecialchars(trim($DATA['saiu03titulo']));
	$DATA['saiu03descripcion']=htmlspecialchars(trim($DATA['saiu03descripcion']));
	$DATA['saiu03ayuda']=htmlspecialchars(trim($DATA['saiu03ayuda']));
	$DATA['saiu03obligaconf']=htmlspecialchars(trim($DATA['saiu03obligaconf']));
	$DATA['saiu03numetapas']=numeros_validar($DATA['saiu03numetapas']);
	$DATA['saiu03nometapa1']=htmlspecialchars(trim($DATA['saiu03nometapa1']));
	$DATA['saiu03idunidadresp1']=numeros_validar($DATA['saiu03idunidadresp1']);
	$DATA['saiu03idequiporesp1']=numeros_validar($DATA['saiu03idequiporesp1']);
	$DATA['saiu03tiemprespdias1']=numeros_validar($DATA['saiu03tiemprespdias1']);
	$DATA['saiu03tiempresphoras1']=numeros_validar($DATA['saiu03tiempresphoras1']);
	$DATA['saiu03nometapa2']=htmlspecialchars(trim($DATA['saiu03nometapa2']));
	$DATA['saiu03idunidadresp2']=numeros_validar($DATA['saiu03idunidadresp2']);
	$DATA['saiu03idequiporesp2']=numeros_validar($DATA['saiu03idequiporesp2']);
	$DATA['saiu03tiemprespdias2']=numeros_validar($DATA['saiu03tiemprespdias2']);
	$DATA['saiu03tiempresphoras2']=numeros_validar($DATA['saiu03tiempresphoras2']);
	$DATA['saiu03nometapa3']=htmlspecialchars(trim($DATA['saiu03nometapa3']));
	$DATA['saiu03idunidadresp3']=numeros_validar($DATA['saiu03idunidadresp3']);
	$DATA['saiu03idequiporesp3']=numeros_validar($DATA['saiu03idequiporesp3']);
	$DATA['saiu03tiemprespdias3']=numeros_validar($DATA['saiu03tiemprespdias3']);
	$DATA['saiu03tiempresphoras3']=numeros_validar($DATA['saiu03tiempresphoras3']);
	$DATA['saiu03otrosusaurios']=htmlspecialchars(trim($DATA['saiu03otrosusaurios']));
	$DATA['saiu03consupervisor']=htmlspecialchars(trim($DATA['saiu03consupervisor']));
	$DATA['saiu03anonimos']=htmlspecialchars(trim($DATA['saiu03anonimos']));
	$DATA['saiu03anexoslibres']=htmlspecialchars(trim($DATA['saiu03anexoslibres']));
	$DATA['saiu03nivelrespuesta']=numeros_validar($DATA['saiu03nivelrespuesta']);
	$DATA['saiu03consupervisor2']=htmlspecialchars(trim($DATA['saiu03consupervisor2']));
	$DATA['saiu03consupervisor3']=htmlspecialchars(trim($DATA['saiu03consupervisor3']));
	$DATA['saiu03infoprograma']=numeros_validar($DATA['saiu03infoprograma']);
	$DATA['saiu03infoperiodos']=numeros_validar($DATA['saiu03infoperiodos']);
	$DATA['saiu03requierepago']=numeros_validar($DATA['saiu03requierepago']);
	$DATA['saiu03incluyemodelo']=numeros_validar($DATA['saiu03incluyemodelo']);
	$DATA['saiu03modelo']=htmlspecialchars(trim($DATA['saiu03modelo']));
	$DATA['saiu03firmacertificada']=numeros_validar($DATA['saiu03firmacertificada']);
	$DATA['saiu03ordenllamada']=numeros_validar($DATA['saiu03ordenllamada']);
	$DATA['saiu03ordenchat']=numeros_validar($DATA['saiu03ordenchat']);
	$DATA['saiu03ordencorreo']=numeros_validar($DATA['saiu03ordencorreo']);
	$DATA['saiu03ordenpresencial']=numeros_validar($DATA['saiu03ordenpresencial']);
	$DATA['saiu03ordensoporte']=numeros_validar($DATA['saiu03ordensoporte']);
	$DATA['saiu03ordenpqrs']=numeros_validar($DATA['saiu03ordenpqrs']);
	$DATA['saiu03ordentramites']=numeros_validar($DATA['saiu03ordentramites']);
	$DATA['saiu03ordencorresp']=numeros_validar($DATA['saiu03ordencorresp']);
	// -- Se inicializan las variables que puedan pasar vacias {Especialmente números}.
	//if ($DATA['saiu03numetapas']==''){$DATA['saiu03numetapas']=0;}
	if ($DATA['saiu03idunidadresp1']==''){$DATA['saiu03idunidadresp1']=0;}
	if ($DATA['saiu03idequiporesp1']==''){$DATA['saiu03idequiporesp1']=0;}
	if ($DATA['saiu03tiemprespdias1']==''){$DATA['saiu03tiemprespdias1']=0;}
	if ($DATA['saiu03tiempresphoras1']==''){$DATA['saiu03tiempresphoras1']=0;}
	if ($DATA['saiu03idunidadresp2']==''){$DATA['saiu03idunidadresp2']=0;}
	if ($DATA['saiu03idequiporesp2']==''){$DATA['saiu03idequiporesp2']=0;}
	if ($DATA['saiu03tiemprespdias2']==''){$DATA['saiu03tiemprespdias2']=0;}
	if ($DATA['saiu03tiempresphoras2']==''){$DATA['saiu03tiempresphoras2']=0;}
	if ($DATA['saiu03idunidadresp3']==''){$DATA['saiu03idunidadresp3']=0;}
	if ($DATA['saiu03idequiporesp3']==''){$DATA['saiu03idequiporesp3']=0;}
	if ($DATA['saiu03tiemprespdias3']==''){$DATA['saiu03tiemprespdias3']=0;}
	if ($DATA['saiu03tiempresphoras3']==''){$DATA['saiu03tiempresphoras3']=0;}
	if ($DATA['saiu03moduloasociado']==''){$DATA['saiu03moduloasociado']=0;}
	//if ($DATA['saiu03nivelrespuesta']==''){$DATA['saiu03nivelrespuesta']=0;}
	//if ($DATA['saiu03infoprograma']==''){$DATA['saiu03infoprograma']=0;}
	//if ($DATA['saiu03infoperiodos']==''){$DATA['saiu03infoperiodos']=0;}
	//if ($DATA['saiu03requierepago']==''){$DATA['saiu03requierepago']=0;}
	//if ($DATA['saiu03incluyemodelo']==''){$DATA['saiu03incluyemodelo']=0;}
	if ($DATA['saiu03firmacertificada']==''){$DATA['saiu03firmacertificada']=0;}
	//if ($DATA['saiu03ordenllamada']==''){$DATA['saiu03ordenllamada']=0;}
	//if ($DATA['saiu03ordenchat']==''){$DATA['saiu03ordenchat']=0;}
	//if ($DATA['saiu03ordencorreo']==''){$DATA['saiu03ordencorreo']=0;}
	//if ($DATA['saiu03ordenpresencial']==''){$DATA['saiu03ordenpresencial']=0;}
	//if ($DATA['saiu03ordensoporte']==''){$DATA['saiu03ordensoporte']=0;}
	//if ($DATA['saiu03ordenpqrs']==''){$DATA['saiu03ordenpqrs']=0;}
	//if ($DATA['saiu03ordentramites']==''){$DATA['saiu03ordentramites']=0;}
	//if ($DATA['saiu03ordencorresp']==''){$DATA['saiu03ordencorresp']=0;}
	// -- Seccion para validar los posibles causales de error.
	$sSepara=', ';
	if (true){
		/*
		//if ($DATA['saiu03modelo']==''){$sError=$ERR['saiu03modelo'].$sSepara.$sError;}
		if ($DATA['saiu03incluyemodelo']==''){$sError=$ERR['saiu03incluyemodelo'].$sSepara.$sError;}
		if ($DATA['saiu03requierepago']==''){$sError=$ERR['saiu03requierepago'].$sSepara.$sError;}
		if ($DATA['saiu03infoperiodos']==''){$sError=$ERR['saiu03infoperiodos'].$sSepara.$sError;}
		if ($DATA['saiu03infoprograma']==''){$sError=$ERR['saiu03infoprograma'].$sSepara.$sError;}
		if ($DATA['saiu03consupervisor3']==''){$sError=$ERR['saiu03consupervisor3'].$sSepara.$sError;}
		if ($DATA['saiu03consupervisor2']==''){$sError=$ERR['saiu03consupervisor2'].$sSepara.$sError;}
		if ($DATA['saiu03nivelrespuesta']==''){$sError=$ERR['saiu03nivelrespuesta'].$sSepara.$sError;}
		*/
		if ($DATA['saiu03anexoslibres']==''){$sError=$ERR['saiu03anexoslibres'].$sSepara.$sError;}
		if ($DATA['saiu03anonimos']==''){$sError=$ERR['saiu03anonimos'].$sSepara.$sError;}
		if ($DATA['saiu03consupervisor']==''){$sError=$ERR['saiu03consupervisor'].$sSepara.$sError;}
		if ($DATA['saiu03otrosusaurios']==''){$sError=$ERR['saiu03otrosusaurios'].$sSepara.$sError;}
		/*
		if ($DATA['saiu03tiempresphoras3']==''){$sError=$ERR['saiu03tiempresphoras3'].$sSepara.$sError;}
		if ($DATA['saiu03tiemprespdias3']==''){$sError=$ERR['saiu03tiemprespdias3'].$sSepara.$sError;}
		if ($DATA['saiu03idliderrespon3']==0){$sError=$ERR['saiu03idliderrespon3'].$sSepara.$sError;}
		if ($DATA['saiu03idequiporesp3']==''){$sError=$ERR['saiu03idequiporesp3'].$sSepara.$sError;}
		if ($DATA['saiu03idunidadresp3']==''){$sError=$ERR['saiu03idunidadresp3'].$sSepara.$sError;}
		if ($DATA['saiu03nometapa3']==''){$sError=$ERR['saiu03nometapa3'].$sSepara.$sError;}
		if ($DATA['saiu03tiempresphoras2']==''){$sError=$ERR['saiu03tiempresphoras2'].$sSepara.$sError;}
		if ($DATA['saiu03tiemprespdias2']==''){$sError=$ERR['saiu03tiemprespdias2'].$sSepara.$sError;}
		if ($DATA['saiu03idliderrespon2']==0){$sError=$ERR['saiu03idliderrespon2'].$sSepara.$sError;}
		if ($DATA['saiu03idequiporesp2']==''){$sError=$ERR['saiu03idequiporesp2'].$sSepara.$sError;}
		if ($DATA['saiu03idunidadresp2']==''){$sError=$ERR['saiu03idunidadresp2'].$sSepara.$sError;}
		if ($DATA['saiu03nometapa2']==''){$sError=$ERR['saiu03nometapa2'].$sSepara.$sError;}
		if ($DATA['saiu03tiempresphoras1']==''){$sError=$ERR['saiu03tiempresphoras1'].$sSepara.$sError;}
		if ($DATA['saiu03tiemprespdias1']==''){$sError=$ERR['saiu03tiemprespdias1'].$sSepara.$sError;}
		if ($DATA['saiu03idliderrespon1']==0){$sError=$ERR['saiu03idliderrespon1'].$sSepara.$sError;}
		if ($DATA['saiu03idequiporesp1']==''){$sError=$ERR['saiu03idequiporesp1'].$sSepara.$sError;}
		if ($DATA['saiu03idunidadresp1']==''){$sError=$ERR['saiu03idunidadresp1'].$sSepara.$sError;}
		if ($DATA['saiu03nometapa1']==''){$sError=$ERR['saiu03nometapa1'].$sSepara.$sError;}
		if ($DATA['saiu03numetapas']==''){$sError=$ERR['saiu03numetapas'].$sSepara.$sError;}
		if ($DATA['saiu03obligaconf']==''){$sError=$ERR['saiu03obligaconf'].$sSepara.$sError;}
		*/
		//if ($DATA['saiu03ayuda']==''){$sError=$ERR['saiu03ayuda'].$sSepara.$sError;}
		//if ($DATA['saiu03descripcion']==''){$sError=$ERR['saiu03descripcion'].$sSepara.$sError;}
		if ($DATA['saiu03titulo']==''){$sError=$ERR['saiu03titulo'].$sSepara.$sError;}
		if ($DATA['saiu03activo']==''){$sError=$ERR['saiu03activo'].$sSepara.$sError;}
		//Fin de las valiaciones NO LLAVE.
		}
	//Valiaciones de campos obligatorios en todo guardar.
	if ($DATA['saiu03tiposol']==''){$sError=$ERR['saiu03tiposol'];}
	// -- Se verifican los valores de campos de otras tablas.
	if ($DATA['saiu03idliderrespon3_doc']!=''){
		if ($sError==''){$sError=tabla_terceros_existe($DATA['saiu03idliderrespon3_td'], $DATA['saiu03idliderrespon3_doc'], $objDB, 'El tercero Liderrespon3 ');}
		if ($sError==''){
			list($sError, $sInfo)=tercero_Bloqueado($DATA['saiu03idliderrespon3'], $objDB);
			if ($sInfo!=''){$sError=$sError.'<br>'.sInfo;}
			}
		}
	if ($DATA['saiu03idliderrespon2_doc']!=''){
		if ($sError==''){$sError=tabla_terceros_existe($DATA['saiu03idliderrespon2_td'], $DATA['saiu03idliderrespon2_doc'], $objDB, 'El tercero Liderrespon2 ');}
		if ($sError==''){
			list($sError, $sInfo)=tercero_Bloqueado($DATA['saiu03idliderrespon2'], $objDB);
			if ($sInfo!=''){$sError=$sError.'<br>'.sInfo;}
			}
		}
	if ($DATA['saiu03idliderrespon1_doc']!=''){
		if ($sError==''){$sError=tabla_terceros_existe($DATA['saiu03idliderrespon1_td'], $DATA['saiu03idliderrespon1_doc'], $objDB, 'El tercero Liderrespon1 ');}
		if ($sError==''){
			list($sError, $sInfo)=tercero_Bloqueado($DATA['saiu03idliderrespon1'], $objDB);
			if ($sInfo!=''){$sError=$sError.'<br>'.sInfo;}
			}
		}
	$bQuitarCodigo=false;
	$sCampoCodigo='';
	if ($sError==''){
		if ($DATA['paso']==10){
			if ($DATA['saiu03consec']==''){
				//$DATA['saiu03consec']=tabla_consecutivo('saiu03temasol', 'saiu03consec', 'saiu03tiposol='.$DATA['saiu03tiposol'].'', $objDB);
				$DATA['saiu03consec']=tabla_consecutivo('saiu03temasol', 'saiu03consec', '', $objDB);
				if ($DATA['saiu03consec']==-1){$sError=$objDB->serror;}
				$bQuitarCodigo=true;
				$sCampoCodigo='saiu03consec';
				}else{
				if (!seg_revisa_permiso($iCodModulo, 8, $objDB)){
					$sError=$ERR['8'];
					$DATA['saiu03consec']='';
					}
				}
			if ($sError==''){
				$sSQL='SELECT 1 FROM saiu03temasol WHERE saiu03tiposol='.$DATA['saiu03tiposol'].' AND saiu03consec='.$DATA['saiu03consec'].'';
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
			$DATA['saiu03id']=tabla_consecutivo('saiu03temasol','saiu03id', '', $objDB);
			if ($DATA['saiu03id']==-1){$sError=$objDB->serror;}
			}
		}
	if ($sError==''){
		$DATA['saiu03descripcion']=stripslashes($DATA['saiu03descripcion']);
		//Si el campo saiu03descripcion permite html quite la linea htmlspecialchars para el campo y habilite la siguiente linea:
		//$saiu03descripcion=addslashes($DATA['saiu03descripcion']);
		$saiu03descripcion=str_replace('"', '\"', $DATA['saiu03descripcion']);
		$DATA['saiu03ayuda']=stripslashes($DATA['saiu03ayuda']);
		//Si el campo saiu03ayuda permite html quite la linea htmlspecialchars para el campo y habilite la siguiente linea:
		//$saiu03ayuda=addslashes($DATA['saiu03ayuda']);
		$saiu03ayuda=str_replace('"', '\"', $DATA['saiu03ayuda']);
		$DATA['saiu03modelo']=stripslashes($DATA['saiu03modelo']);
		//Si el campo saiu03modelo permite html quite la linea htmlspecialchars para el campo y habilite la siguiente linea:
		$saiu03modelo=addslashes($DATA['saiu03modelo']);
		//$saiu03modelo=str_replace('"', '\"', $DATA['saiu03modelo']);
		$bpasa=false;
		if ($DATA['paso']==10){
			$DATA['saiu03moduloasociado']=0;
			$sCampos3003='saiu03tiposol, saiu03consec, saiu03id, saiu03activo, saiu03titulo, 
saiu03descripcion, saiu03ayuda, saiu03obligaconf, saiu03numetapas, saiu03nometapa1, 
saiu03idunidadresp1, saiu03idequiporesp1, saiu03idliderrespon1, saiu03tiemprespdias1, saiu03tiempresphoras1, 
saiu03nometapa2, saiu03idunidadresp2, saiu03idequiporesp2, saiu03idliderrespon2, saiu03tiemprespdias2, 
saiu03tiempresphoras2, saiu03nometapa3, saiu03idunidadresp3, saiu03idequiporesp3, saiu03idliderrespon3, 
saiu03tiemprespdias3, saiu03tiempresphoras3, saiu03otrosusaurios, saiu03consupervisor, saiu03anonimos, 
saiu03anexoslibres, saiu03moduloasociado, saiu03nivelrespuesta, saiu03consupervisor2, saiu03consupervisor3, 
saiu03infoprograma, saiu03infoperiodos, saiu03requierepago, saiu03incluyemodelo, saiu03modelo, 
saiu03firmacertificada, saiu03ordenllamada, saiu03ordenchat, saiu03ordencorreo, saiu03ordenpresencial, 
saiu03ordensoporte, saiu03ordenpqrs, saiu03ordentramites, saiu03ordencorresp';
			$sValores3003=''.$DATA['saiu03tiposol'].', '.$DATA['saiu03consec'].', '.$DATA['saiu03id'].', "'.$DATA['saiu03activo'].'", "'.$DATA['saiu03titulo'].'", 
"'.$saiu03descripcion.'", "'.$saiu03ayuda.'", "'.$DATA['saiu03obligaconf'].'", '.$DATA['saiu03numetapas'].', "'.$DATA['saiu03nometapa1'].'", 
'.$DATA['saiu03idunidadresp1'].', '.$DATA['saiu03idequiporesp1'].', '.$DATA['saiu03idliderrespon1'].', '.$DATA['saiu03tiemprespdias1'].', '.$DATA['saiu03tiempresphoras1'].', 
"'.$DATA['saiu03nometapa2'].'", '.$DATA['saiu03idunidadresp2'].', '.$DATA['saiu03idequiporesp2'].', '.$DATA['saiu03idliderrespon2'].', '.$DATA['saiu03tiemprespdias2'].', 
'.$DATA['saiu03tiempresphoras2'].', "'.$DATA['saiu03nometapa3'].'", '.$DATA['saiu03idunidadresp3'].', '.$DATA['saiu03idequiporesp3'].', '.$DATA['saiu03idliderrespon3'].', 
'.$DATA['saiu03tiemprespdias3'].', '.$DATA['saiu03tiempresphoras3'].', "'.$DATA['saiu03otrosusaurios'].'", "'.$DATA['saiu03consupervisor'].'", "'.$DATA['saiu03anonimos'].'", 
"'.$DATA['saiu03anexoslibres'].'", '.$DATA['saiu03moduloasociado'].', '.$DATA['saiu03nivelrespuesta'].', "'.$DATA['saiu03consupervisor2'].'", "'.$DATA['saiu03consupervisor3'].'", 
'.$DATA['saiu03infoprograma'].', '.$DATA['saiu03infoperiodos'].', '.$DATA['saiu03requierepago'].', '.$DATA['saiu03incluyemodelo'].', "'.$saiu03modelo.'", 
'.$DATA['saiu03firmacertificada'].', '.$DATA['saiu03ordenllamada'].', '.$DATA['saiu03ordenchat'].', '.$DATA['saiu03ordencorreo'].', '.$DATA['saiu03ordenpresencial'].', 
'.$DATA['saiu03ordensoporte'].', '.$DATA['saiu03ordenpqrs'].', '.$DATA['saiu03ordentramites'].', '.$DATA['saiu03ordencorresp'].'';
			if ($APP->utf8==1){
				$sSQL='INSERT INTO saiu03temasol ('.$sCampos3003.') VALUES ('.utf8_encode($sValores3003).');';
				$sdetalle=$sCampos3003.'['.utf8_encode($sValores3003).']';
				}else{
				$sSQL='INSERT INTO saiu03temasol ('.$sCampos3003.') VALUES ('.$sValores3003.');';
				$sdetalle=$sCampos3003.'['.$sValores3003.']';
				}
			$idAccion=2;
			$bpasa=true;
			}else{
			$scampo[1]='saiu03activo';
			$scampo[2]='saiu03titulo';
			$scampo[3]='saiu03descripcion';
			$scampo[4]='saiu03ayuda';
			$scampo[5]='saiu03obligaconf';
			$scampo[6]='saiu03numetapas';
			$scampo[7]='saiu03nometapa1';
			$scampo[8]='saiu03idunidadresp1';
			$scampo[9]='saiu03idequiporesp1';
			$scampo[10]='saiu03idliderrespon1';
			$scampo[11]='saiu03tiemprespdias1';
			$scampo[12]='saiu03tiempresphoras1';
			$scampo[13]='saiu03nometapa2';
			$scampo[14]='saiu03idunidadresp2';
			$scampo[15]='saiu03idequiporesp2';
			$scampo[16]='saiu03idliderrespon2';
			$scampo[17]='saiu03tiemprespdias2';
			$scampo[18]='saiu03tiempresphoras2';
			$scampo[19]='saiu03nometapa3';
			$scampo[20]='saiu03idunidadresp3';
			$scampo[21]='saiu03idequiporesp3';
			$scampo[22]='saiu03idliderrespon3';
			$scampo[23]='saiu03tiemprespdias3';
			$scampo[24]='saiu03tiempresphoras3';
			$scampo[25]='saiu03otrosusaurios';
			$scampo[26]='saiu03consupervisor';
			$scampo[27]='saiu03anonimos';
			$scampo[28]='saiu03anexoslibres';
			$scampo[29]='saiu03nivelrespuesta';
			$scampo[30]='saiu03consupervisor2';
			$scampo[31]='saiu03consupervisor3';
			$scampo[32]='saiu03infoprograma';
			$scampo[33]='saiu03infoperiodos';
			$scampo[34]='saiu03requierepago';
			$scampo[35]='saiu03incluyemodelo';
			$scampo[36]='saiu03modelo';
			$scampo[37]='saiu03firmacertificada';
			$scampo[38]='saiu03ordenllamada';
			$scampo[39]='saiu03ordenchat';
			$scampo[40]='saiu03ordencorreo';
			$scampo[41]='saiu03ordenpresencial';
			$scampo[42]='saiu03ordensoporte';
			$scampo[43]='saiu03ordenpqrs';
			$scampo[44]='saiu03ordentramites';
			$scampo[45]='saiu03ordencorresp';
			$scampo[46]='saiu03tiposol';
			$sdato[1]=$DATA['saiu03activo'];
			$sdato[2]=$DATA['saiu03titulo'];
			$sdato[3]=$saiu03descripcion;
			$sdato[4]=$saiu03ayuda;
			$sdato[5]=$DATA['saiu03obligaconf'];
			$sdato[6]=$DATA['saiu03numetapas'];
			$sdato[7]=$DATA['saiu03nometapa1'];
			$sdato[8]=$DATA['saiu03idunidadresp1'];
			$sdato[9]=$DATA['saiu03idequiporesp1'];
			$sdato[10]=$DATA['saiu03idliderrespon1'];
			$sdato[11]=$DATA['saiu03tiemprespdias1'];
			$sdato[12]=$DATA['saiu03tiempresphoras1'];
			$sdato[13]=$DATA['saiu03nometapa2'];
			$sdato[14]=$DATA['saiu03idunidadresp2'];
			$sdato[15]=$DATA['saiu03idequiporesp2'];
			$sdato[16]=$DATA['saiu03idliderrespon2'];
			$sdato[17]=$DATA['saiu03tiemprespdias2'];
			$sdato[18]=$DATA['saiu03tiempresphoras2'];
			$sdato[19]=$DATA['saiu03nometapa3'];
			$sdato[20]=$DATA['saiu03idunidadresp3'];
			$sdato[21]=$DATA['saiu03idequiporesp3'];
			$sdato[22]=$DATA['saiu03idliderrespon3'];
			$sdato[23]=$DATA['saiu03tiemprespdias3'];
			$sdato[24]=$DATA['saiu03tiempresphoras3'];
			$sdato[25]=$DATA['saiu03otrosusaurios'];
			$sdato[26]=$DATA['saiu03consupervisor'];
			$sdato[27]=$DATA['saiu03anonimos'];
			$sdato[28]=$DATA['saiu03anexoslibres'];
			$sdato[29]=$DATA['saiu03nivelrespuesta'];
			$sdato[30]=$DATA['saiu03consupervisor2'];
			$sdato[31]=$DATA['saiu03consupervisor3'];
			$sdato[32]=$DATA['saiu03infoprograma'];
			$sdato[33]=$DATA['saiu03infoperiodos'];
			$sdato[34]=$DATA['saiu03requierepago'];
			$sdato[35]=$DATA['saiu03incluyemodelo'];
			$sdato[36]=$saiu03modelo;
			$sdato[37]=$DATA['saiu03firmacertificada'];
			$sdato[38]=$DATA['saiu03ordenllamada'];
			$sdato[39]=$DATA['saiu03ordenchat'];
			$sdato[40]=$DATA['saiu03ordencorreo'];
			$sdato[41]=$DATA['saiu03ordenpresencial'];
			$sdato[42]=$DATA['saiu03ordensoporte'];
			$sdato[43]=$DATA['saiu03ordenpqrs'];
			$sdato[44]=$DATA['saiu03ordentramites'];
			$sdato[45]=$DATA['saiu03ordencorresp'];
			$sdato[46]=$DATA['saiu03tiposol'];
			$numcmod=46;
			$sWhere='saiu03id='.$DATA['saiu03id'].'';
			$sSQL='SELECT * FROM saiu03temasol WHERE '.$sWhere;
			$sdatos='';
			$bPrimera=true;
			$result=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($result)>0){
				$filabase=$objDB->sf($result);
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
					$sSQL='UPDATE saiu03temasol SET '.utf8_encode($sdatos).' WHERE '.$sWhere.';';
					}else{
					$sdetalle=$sdatos.'['.$sWhere.']';
					$sSQL='UPDATE saiu03temasol SET '.$sdatos.' WHERE '.$sWhere.';';
					}
				$idAccion=3;
				}
			}
		if ($bpasa){
			$result=$objDB->ejecutasql($sSQL);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' [3003] ..<!-- '.$sSQL.' -->';
				if ($idAccion==2){
					$DATA['saiu03id']='';
					$bQuitarCodigo=true;
					$DATA['paso']=0;
					}else{
					$DATA['paso']=2;
					}
				}else{
				if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Guardar 3003 '.$sSQL.'<br>';}
				if ($bAudita[$idAccion]){seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], $idAccion, $DATA['saiu03id'], $sdetalle, $objDB);}
				$DATA['paso']=2;
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
		if ($bQuitarCodigo){
			if ($sCampoCodigo!=''){$DATA[$sCampoCodigo]='';}
			}
		}
	//if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' InfoDepura<br>';}
	return array($DATA, $sError, $iTipoError, $sDebug);
	}
function f3003_db_Eliminar($saiu03id, $objDB, $bDebug=false){
	$iCodModulo=3003;
	$bAudita[4]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3003='lg/lg_3003_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3003)){$mensajes_3003='lg/lg_3003_es.php';}
	require $mensajes_todas;
	require $mensajes_3003;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	$saiu03id=numeros_validar($saiu03id);
	// Traer los datos para hacer las validaciones.
	if ($sError==''){
		$sSQL='SELECT * FROM saiu03temasol WHERE saiu03id='.$saiu03id.'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$filabase=$objDB->sf($tabla);
			}else{
			$sError='No se encuentra el registro solicitado {Ref: '.$saiu03id.'}';
			}
		}
	if ($sError==''){
		$sSQL='SELECT 1 FROM saiu04temaanexo WHERE saiu04idtema='.$filabase['saiu03id'].'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$sError='Existen Anexos creados, no es posible eliminar';
			}
		}
	if ($sError==''){
		if (!seg_revisa_permiso($iCodModulo, 4, $objDB)){
			$sError=$ERR['4'];
			}
		}
	if ($sError==''){
		$sSQL='SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=3003';
		$tablaor=$objDB->ejecutasql($sSQL);
		while ($filaor=$objDB->sf($tablaor)){
			$sSQL='SELECT '.$filaor['campoid'].' FROM '.$filaor['tabla'].' WHERE '.$filaor['camporev'].'='.$_REQUEST['saiu03id'].' LIMIT 0, 1';
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
		//$sSQL='DELETE FROM saiu04temaanexo WHERE saiu04idtema='.$filabase['saiu03id'].'';
		//$tabla=$objDB->ejecutasql($sSQL);
		$sWhere='saiu03id='.$saiu03id.'';
		//$sWhere='saiu03consec='.$filabase['saiu03consec'].' AND saiu03tiposol='.$filabase['saiu03tiposol'].'';
		$sSQL='DELETE FROM saiu03temasol WHERE '.$sWhere.';';
		$result=$objDB->ejecutasql($sSQL);
		if ($result==false){
			$sError=$ERR['falla_eliminar'].' .. <!-- '.$sSQL.' -->';
			}else{
			if ($bAudita[4]){seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $saiu03id, $sWhere, $objDB);}
			}
		}
	return array($sError, $iTipoError, $sDebug);
	}
function f3003_TituloBusqueda(){
	return 'Busqueda de Temas de solicitud';
	}
function f3003_ParametrosBusqueda(){
	$sParams='<label class="Label90">Nombre</label><label><input id="b3003nombre" name="b3003nombre" type="text" value="" onchange="paginarbusqueda()" /></label>';
	return $sParams;
	}
function f3003_JavaScriptBusqueda($iModuloBusca){
	$sRes='var sCampo=window.document.frmedita.scampobusca.value;
var params=new Array();
params[100]=sCampo;
params[101]=window.document.frmedita.paginabusqueda.value;
params[102]=window.document.frmedita.lppfbusqueda.value;
params[103]=window.document.frmedita.b3003nombre.value;
xajax_f'.$iModuloBusca.'_HtmlBusqueda(params);';
	return $sRes;
	}
function f3003_TablaDetalleBusquedas($aParametros, $objDB){
	$res='';
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3003='lg/lg_3003_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3003)){$mensajes_3003='lg/lg_3003_es.php';}
	require $mensajes_todas;
	require $mensajes_3003;
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
		return array($sLeyenda.'<input id="paginaf3003" name="paginaf3003" type="hidden" value="'.$pagina.'"/><input id="lppf3003" name="lppf3003" type="hidden" value="'.$lineastabla.'"/>', $sDebug);
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
	$sTitulos='Tiposol, Consec, Id, Activo, Titulo, Descripcion, Ayuda, Obligaconf, Numetapas, Nometapa1, Unidadresp1, Equiporesp1, Liderrespon1, Tiemprespdias1, Tiempresphoras1, Nometapa2, Unidadresp2, Equiporesp2, Liderrespon2, Tiemprespdias2, Tiempresphoras2, Nometapa3, Unidadresp3, Equiporesp3, Liderrespon3, Tiemprespdias3, Tiempresphoras3, Otrosusaurios, Consupervisor, Anonimos, Anexoslibres, Moduloasociado';
	$sSQL='SELECT T1.saiu02titulo, TB.saiu03consec, TB.saiu03id, TB.saiu03activo, TB.saiu03titulo, TB.saiu03descripcion, TB.saiu03ayuda, TB.saiu03obligaconf, TB.saiu03numetapas, TB.saiu03nometapa1, T11.unae26nombre, T12.bita27nombre, T13.unad11razonsocial AS C13_nombre, TB.saiu03tiemprespdias1, TB.saiu03tiempresphoras1, TB.saiu03nometapa2, T17.unae26nombre, T18.bita27nombre, T19.unad11razonsocial AS C19_nombre, TB.saiu03tiemprespdias2, TB.saiu03tiempresphoras2, TB.saiu03nometapa3, T23.unae26nombre, T24.bita27nombre, T25.unad11razonsocial AS C25_nombre, TB.saiu03tiemprespdias3, TB.saiu03tiempresphoras3, TB.saiu03otrosusaurios, TB.saiu03consupervisor, TB.saiu03anonimos, TB.saiu03anexoslibres, TB.saiu03moduloasociado, TB.saiu03tiposol, TB.saiu03idunidadresp1, TB.saiu03idequiporesp1, TB.saiu03idliderrespon1, T13.unad11tipodoc AS C13_td, T13.unad11doc AS C13_doc, TB.saiu03idunidadresp2, TB.saiu03idequiporesp2, TB.saiu03idliderrespon2, T19.unad11tipodoc AS C19_td, T19.unad11doc AS C19_doc, TB.saiu03idunidadresp3, TB.saiu03idequiporesp3, TB.saiu03idliderrespon3, T25.unad11tipodoc AS C25_td, T25.unad11doc AS C25_doc 
FROM saiu03temasol AS TB, saiu02tiposol AS T1, unae26unidadesfun AS T11, bita27equipotrabajo AS T12, unad11terceros AS T13, unae26unidadesfun AS T17, bita27equipotrabajo AS T18, unad11terceros AS T19, unae26unidadesfun AS T23, bita27equipotrabajo AS T24, unad11terceros AS T25 
WHERE '.$sSQLadd1.' TB.saiu03tiposol=T1.saiu02id AND TB.saiu03idunidadresp1=T11.unae26id AND TB.saiu03idequiporesp1=T12.bita27id AND TB.saiu03idliderrespon1=T13.unad11id AND TB.saiu03idunidadresp2=T17.unae26id AND TB.saiu03idequiporesp2=T18.bita27id AND TB.saiu03idliderrespon2=T19.unad11id AND TB.saiu03idunidadresp3=T23.unae26id AND TB.saiu03idequiporesp3=T24.bita27id AND TB.saiu03idliderrespon3=T25.unad11id '.$sSQLadd.'
ORDER BY TB.saiu03tiposol, TB.saiu03consec';
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
			//return array(utf8_encode($sErrConsulta.'<input id="paginaf3003" name="paginaf3003" type="hidden" value="'.$pagina.'"/><input id="lppf3003" name="lppf3003" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
			}
		if ((($registros-1)/$lineastabla)<($pagina-1)){$pagina=(int)(($registros-1)/$lineastabla)+1;}
		if ($registros>$lineastabla){
			$rbase=($pagina-1)*$lineastabla;
			$limite=' LIMIT '.$rbase.', '.$lineastabla;
			$tabladetalle=$objDB->ejecutasql($sSQL.$limite);
			}
		}
	$res=$sErrConsulta.$sLeyenda.'<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">
<tr class="fondoazul">
<td><b>'.$ETI['saiu03tiposol'].'</b></td>
<td><b>'.$ETI['saiu03consec'].'</b></td>
<td><b>'.$ETI['saiu03activo'].'</b></td>
<td><b>'.$ETI['saiu03titulo'].'</b></td>
<td><b>'.$ETI['saiu03descripcion'].'</b></td>
<td><b>'.$ETI['saiu03ayuda'].'</b></td>
<td><b>'.$ETI['saiu03obligaconf'].'</b></td>
<td><b>'.$ETI['saiu03numetapas'].'</b></td>
<td><b>'.$ETI['saiu03nometapa1'].'</b></td>
<td><b>'.$ETI['saiu03idunidadresp1'].'</b></td>
<td><b>'.$ETI['saiu03idequiporesp1'].'</b></td>
<td colspan="2"><b>'.$ETI['saiu03idliderrespon1'].'</b></td>
<td><b>'.$ETI['saiu03tiemprespdias1'].'</b></td>
<td><b>'.$ETI['saiu03tiempresphoras1'].'</b></td>
<td><b>'.$ETI['saiu03nometapa2'].'</b></td>
<td><b>'.$ETI['saiu03idunidadresp2'].'</b></td>
<td><b>'.$ETI['saiu03idequiporesp2'].'</b></td>
<td colspan="2"><b>'.$ETI['saiu03idliderrespon2'].'</b></td>
<td><b>'.$ETI['saiu03tiemprespdias2'].'</b></td>
<td><b>'.$ETI['saiu03tiempresphoras2'].'</b></td>
<td><b>'.$ETI['saiu03nometapa3'].'</b></td>
<td><b>'.$ETI['saiu03idunidadresp3'].'</b></td>
<td><b>'.$ETI['saiu03idequiporesp3'].'</b></td>
<td colspan="2"><b>'.$ETI['saiu03idliderrespon3'].'</b></td>
<td><b>'.$ETI['saiu03tiemprespdias3'].'</b></td>
<td><b>'.$ETI['saiu03tiempresphoras3'].'</b></td>
<td><b>'.$ETI['saiu03otrosusaurios'].'</b></td>
<td><b>'.$ETI['saiu03consupervisor'].'</b></td>
<td><b>'.$ETI['saiu03anonimos'].'</b></td>
<td><b>'.$ETI['saiu03anexoslibres'].'</b></td>
<td><b>'.$ETI['saiu03moduloasociado'].'</b></td>
<td align="right">
'.html_paginador('paginabusqueda', $registros, $lineastabla, $pagina, 'paginarbusqueda()').'
'.html_lpp('lppfbusqueda', $lineastabla, 'paginarbusqueda()').'
</td>
</tr>';
	$tlinea=1;
	while($filadet=$objDB->sf($tabladetalle)){
		$sPrefijo='<a href="javascript:Devuelve(\''.$filadet['saiu03id'].'\');">';
		$sSufijo='</a>';
		$tlinea++;
		$et_saiu03activo=$sPrefijo.$ETI['no'].$sSufijo;
		if ($filadet['saiu03activo']=='S'){$et_saiu03activo=$sPrefijo.$ETI['si'].$sSufijo;}
		$res=$res.'<tr onmouseover="cambia_color_over(this);" onmouseout="cambia_color_out(this);">
<td>'.$sPrefijo.cadena_notildes($filadet['saiu02titulo']).$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu03consec'].$sSufijo.'</td>
<td>'.$sPrefijo.$et_saiu03activo.$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['saiu03titulo']).$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu03descripcion'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu03ayuda'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu03obligaconf'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu03numetapas'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['saiu03nometapa1']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['unae26nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['bita27nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['C13_td'].' '.$filadet['C13_doc'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['C13_nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu03tiemprespdias1'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu03tiempresphoras1'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['saiu03nometapa2']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['unae26nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['bita27nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['C19_td'].' '.$filadet['C19_doc'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['C19_nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu03tiemprespdias2'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu03tiempresphoras2'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['saiu03nometapa3']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['unae26nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['bita27nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['C25_td'].' '.$filadet['C25_doc'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['C25_nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu03tiemprespdias3'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu03tiempresphoras3'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu03otrosusaurios'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu03consupervisor'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu03anonimos'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu03anexoslibres'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu03moduloasociado'].$sSufijo.'</td>
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
function f3003_saiu03idliderrespon($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	if (isset($aParametros[99])==0){$aParametros[99]=false;}
	if (isset($aParametros[100])==0){$aParametros[100]=$_SESSION['unad_id_tercero'];}
	if (isset($aParametros[101])==0){$aParametros[101]='';}
	if (isset($aParametros[102])==0){$aParametros[102]=0;}
	$sDebug='';
	$idTercero=$aParametros[100];
	$iEtapa=0;
	$saiu03idequiporesp=0;
	if ($aParametros[101]!=''){$iEtapa=$aParametros[101];}
	if ($aParametros[102]!=''){$saiu03idequiporesp=$aParametros[102];}
	$objDB->xajax();
	$objResponse=new xajaxResponse();
	if ($iEtapa > 0) {
		$sDocumento = '';
		if ($saiu03idequiporesp>0){
			$sSQL='SELECT T1.unad11doc FROM bita27equipotrabajo AS TB, unad11terceros AS T1 WHERE TB.bita27idlider=T1.unad11id AND bita27id=' . $saiu03idequiporesp . '';
			$res=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($res)>0){
				$fila=$objDB->sf($res);
				if ($fila['unad11doc']>0){$sDocumento=$fila['unad11doc'];}
			}
		}
		$objResponse->assign('saiu03idliderrespon' . $iEtapa . '_doc', 'value', $sDocumento);
		$objResponse->script('ter_muestra("saiu03idliderrespon' . $iEtapa . '",0);');
	}
	$objDB->CerrarConexion();
	return $objResponse;
}
?>