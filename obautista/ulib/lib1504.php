<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2016 - 2019 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.12.0 viernes, 15 de enero de 2016
--- Modelo Versión 2.15.8 lunes, 24 de octubre de 2016
--- Modelo Versión 2.22.7 viernes, 22 de marzo de 2019
--- 1504 bita04bitacora
*/
function html_combo_bita04idtiposolicitud($objDB, $valor, $vr){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	//@@ Se debe arreglar la condicion..
	$scondi='';
	$res=html_combo('bita04idtiposolicitud', 'bita01id', 'bita01nombre', 'bita01tiposolicitud', $scondi, 'bita01nombre', $valor, $objDB, '', true, '{'.$ETI['msg_seleccione'].'}', '');
	return utf8_encode($res);
	}
function f1504_HTMLComboV2_bita04cead($objDB, $objCombos, $valor, $vrbita04idzona){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	//@@ Se debe arreglar la condicion..
	$sCondi='unad24idzona="'.$vrbita04idzona.'"';
	if ($sCondi!=''){$sCondi=' WHERE '.$sCondi;}
	$objCombos->nuevo('bita04cead', $valor, true, '{'.$ETI['msg_ninguno'].'}', 0);
	$sSQL='SELECT unad24id AS id, unad24nombre AS nombre FROM unad24sede'.$sCondi.' ORDER BY unad24activa DESC, unad24nombre';
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f1504_Combobita04cead($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$objCombos=new clsHtmlCombos('n');
	$html_bita04cead=f1504_HTMLComboV2_bita04cead($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_bita04cead', 'innerHTML', $html_bita04cead);
	return $objResponse;
	}
function f1504_ExisteDato($datos){
	if(!is_array($datos)){$datos=json_decode(str_replace('\"','"',$datos),true);}
	$_SESSION['u_ultimominuto']=iminutoavance();
	$bHayLlave=true;
	$bita04consec=numeros_validar($datos[1]);
	if ($bita04consec==''){$bHayLlave=false;}
	if ($bHayLlave){
		require './app.php';
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		$sSQL='SELECT bita04consec FROM bita04bitacora WHERE bita04consec='.$bita04consec.'';
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
function f1504_Busquedas($aParametros){
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_1504=$APP->rutacomun.'lg/lg_1504_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1504)){$mensajes_1504=$APP->rutacomun.'lg/lg_1504_es.php';}
	require $mensajes_todas;
	require $mensajes_1504;
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
		case 'bita04idsolicita':
		require $APP->rutacomun.'lib111.php';
		$sTabla=f111_TablaDetalleBusquedas($aParametrosB, $objDB);
		$sTitulo='Busqueda de terceros';
		$sParams=f111_ParametrosBusqueda();
		$sJavaBusqueda=f111_JavaScriptBusqueda(1504);
		break;
		case 'bita04idatiende':
		require $APP->rutacomun.'lib111.php';
		$sTabla=f111_TablaDetalleBusquedas($aParametrosB, $objDB);
		$sTitulo='Busqueda de terceros';
		$sParams=f111_ParametrosBusqueda();
		$sJavaBusqueda=f111_JavaScriptBusqueda(1504);
		break;
		case 'bita04idelabora':
		require $APP->rutacomun.'lib111.php';
		$sTabla=f111_TablaDetalleBusquedas($aParametrosB, $objDB);
		$sTitulo='Busqueda de terceros';
		$sParams=f111_ParametrosBusqueda();
		$sJavaBusqueda=f111_JavaScriptBusqueda(1504);
		break;
		case 'bita04idsupervisor':
		require $APP->rutacomun.'lib111.php';
		$sTabla=f111_TablaDetalleBusquedas($aParametrosB, $objDB);
		$sTitulo='Busqueda de terceros';
		$sParams=f111_ParametrosBusqueda();
		$sJavaBusqueda=f111_JavaScriptBusqueda(1504);
		break;
		case 'bita05idtercero':
		require $APP->rutacomun.'lib111.php';
		$sTabla=f111_TablaDetalleBusquedas($aParametrosB, $objDB);
		$sTitulo='Busqueda de terceros';
		$sParams=f111_ParametrosBusqueda();
		$sJavaBusqueda=f111_JavaScriptBusqueda(1504);
		break;
		case 'bita29idusuario':
		require $APP->rutacomun.'lib111.php';
		$sTabla=f111_TablaDetalleBusquedas($aParametrosB, $objDB);
		$sTitulo='Busqueda de terceros';
		$sParams=f111_ParametrosBusqueda();
		$sJavaBusqueda=f111_JavaScriptBusqueda(1504);
		break;
		case 'bita30idresponsable':
		require $APP->rutacomun.'lib111.php';
		$sTabla=f111_TablaDetalleBusquedas($aParametrosB, $objDB);
		$sTitulo='Busqueda de terceros';
		$sParams=f111_ParametrosBusqueda();
		$sJavaBusqueda=f111_JavaScriptBusqueda(1504);
		break;
		}
	$sTitulo='<h2>'.$ETI['titulo_1504'].' - '.$sTitulo.'</h2>';
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97titulo', 'innerHTML', $sTitulo);
	$objResponse->assign('div_97params', 'innerHTML', $sParams);
	$objResponse->assign('div_97tabla', 'innerHTML', $sTabla);
	$objResponse->setFunction('paginarbusqueda','',$sJavaBusqueda);
	$objResponse->call('expandesector(97)');
	return $objResponse;
	}
function f1504_HtmlBusqueda($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$sError='';
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$sDetalle='';
	switch($aParametros[100]){
		case 'bita04idsolicita':
		require $APP->rutacomun.'lib111.php';
		$sDetalle=f111_TablaDetalleBusquedas($aParametros, $objDB);
		break;
		case 'bita04idatiende':
		require $APP->rutacomun.'lib111.php';
		$sDetalle=f111_TablaDetalleBusquedas($aParametros, $objDB);
		break;
		case 'bita04idelabora':
		require $APP->rutacomun.'lib111.php';
		$sDetalle=f111_TablaDetalleBusquedas($aParametros, $objDB);
		break;
		case 'bita04idsupervisor':
		require $APP->rutacomun.'lib111.php';
		$sDetalle=f111_TablaDetalleBusquedas($aParametros, $objDB);
		break;
		case 'bita05idtercero':
		require $APP->rutacomun.'lib111.php';
		$sDetalle=f111_TablaDetalleBusquedas($aParametros, $objDB);
		break;
		case 'bita29idusuario':
		require $APP->rutacomun.'lib111.php';
		$sDetalle=f111_TablaDetalleBusquedas($aParametros, $objDB);
		break;
		case 'bita30idresponsable':
		require $APP->rutacomun.'lib111.php';
		$sDetalle=f111_TablaDetalleBusquedas($aParametros, $objDB);
		break;
		}
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97tabla', 'innerHTML', $sDetalle);
	return $objResponse;
	}
function f1504_TablaDetalleV2($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_1504=$APP->rutacomun.'lg/lg_1504_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1504)){$mensajes_1504=$APP->rutacomun.'lg/lg_1504_es.php';}
	require $mensajes_todas;
	require $mensajes_1504;
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
	$aParametros[100]=numeros_validar($aParametros[100]);
	$aParametros[105]=numeros_validar($aParametros[105]);
	$aParametros[106]=numeros_validar($aParametros[106]);
	$aParametros[107]=numeros_validar($aParametros[107]);
	if ($aParametros[106]==''){$aParametros[106]=-99;}
	$sDebug='';
	$idTercero=$aParametros[100];
	$pagina=$aParametros[101];
	$lineastabla=$aParametros[102];
	$babierta=true;
	//$sSQL='SELECT Campo FROM Tabla WHERE Id='.$sValorId;
	//$tabla=$objDB->ejecutasql($sSQL);
	//if ($objDB->nf($tabla)>0){
		//$fila=$objDB->sf($tabla);
		//if ($fila['Campo']!='S'){$babierta=true;}
		//}
	$sLeyenda='';
	if (false){
		$sLeyenda='<div class="salto1px"></div>
<div class="GrupoCamposAyuda">
<b>Importante:</b> Mensaje al usuario
<div class="salto1px"></div>
</div>';
		}
	$sSQLadd='';
	$sSQLadd1='';
	$sSQLwhere1='';
	//if (isset($aParametros[103])==0){$aParametros[103]='';}
	//if ((int)$aParametros[103]!=-1){$sSQLadd=$sSQLadd.' AND TB.campo='.$aParametros[103];}
	if ($aParametros[111]!=''){$sSQLadd=$sSQLadd.' AND T5.unad11doc LIKE "%'.$aParametros[111].'%"';}
	if ($aParametros[103]!=''){$sSQLadd=$sSQLadd.' AND T5.unad11razonsocial LIKE "%'.$aParametros[103].'%"';}
	if ($aParametros[104]!=''){$sSQLwhere1=$sSQLwhere1.'TB.bita04detalle LIKE "%'.$aParametros[104].'%" AND ';}
	if ($aParametros[105]!=''){$sSQLwhere1=$sSQLwhere1.'TB.bita04idtiposolicitud='.$aParametros[105].' AND ';}
	switch ($aParametros[106]){
		case 0:
		$sSQLwhere1=$sSQLwhere1.'TB.bita04idatiende=0 AND ';
		break;
		case 1:
		$sSQLwhere1=$sSQLwhere1.'TB.bita04idatiende='.$idTercero.' AND TB.bita04estado IN (0,1) AND ';
		break;
		case 2:
		$sSQLwhere1=$sSQLwhere1.'TB.bita04idatiende='.$idTercero.' AND ';
		break;
		case 3:
		$sSQLwhere1=$sSQLwhere1.'TB.bita04estado IN (0,1) AND ';
		break;
		case 4: // Abiertas donde soy supervisor
		$sSQLwhere1=$sSQLwhere1.'TB.bita04idsupervisor='.$idTercero.' AND TB.bita04estado IN (0,1) AND ';
		break;
		case 5: // Donde soy supervisor
		$sSQLwhere1=$sSQLwhere1.'TB.bita04idsupervisor='.$idTercero.' AND ';
		break;
		}
	if ($aParametros[107]!=''){$sSQLwhere1=$sSQLwhere1.'TB.bita04idtema='.$aParametros[107].' AND ';}
	if (fecha_esvalida($aParametros[108])){
		$sSQLwhere1=$sSQLwhere1.'STR_TO_DATE(TB.bita04fecha, "%d/%m/%Y")>=STR_TO_DATE("'.$aParametros[108].'", "%d/%m/%Y") AND ';
		}
	if (fecha_esvalida($aParametros[109])){
		$sSQLwhere1=$sSQLwhere1.'STR_TO_DATE(TB.bita04fecha, "%d/%m/%Y")<=STR_TO_DATE("'.$aParametros[109].'", "%d/%m/%Y") AND ';
		}
	if ($aParametros[110]!=''){$sSQLwhere1=$sSQLwhere1.'TB.bita04idatiende='.$aParametros[110].' AND ';}
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
	$sTitulos='Consecutivo, Estado, Tipo solicitud,TipoDoc Solicita,Doc Solicita,Nombre Solicita, Fecha, Hora, Minuto, Cead, Ticket SAU, Prioridad, Detalle, Atiende, Fecha probable, Fecha resuelve,Id Tema,Nombre Tema, Tipo Solicitante';
	$limite='';
	$sSQL='SELECT 1 
FROM bita04bitacora AS TB, bita02estado AS T3, bita01tiposolicitud AS T4, unad11terceros AS T5, bita03prioridad AS T11, unad11terceros AS T13, bita06tema AS T06 
WHERE '.$sSQLwhere1.'TB.bita04estado=T3.bita02id AND TB.bita04idtiposolicitud=T4.bita01id AND TB.bita04idsolicita=T5.unad11id AND TB.bita04prioridad=T11.bita03id AND TB.bita04idatiende=T13.unad11id AND TB.bita04idtema=T06.bita06id '.$sSQLadd.'';
	$tabladetalle=$objDB->ejecutasql($sSQL);
	$registros=$objDB->nf($tabladetalle);
	if ((($registros-1)/$lineastabla)<($pagina-1)){$pagina=(int)(($registros-1)/$lineastabla)+1;}
	if ($registros>$lineastabla){
		$rbase=($pagina-1)*$lineastabla;
		$limite=' LIMIT '.$rbase.', '.$lineastabla;
		}
	$sSQL='SELECT TB.bita04consec, T3.bita02nombre, T4.bita01nombre, T5.unad11tipodoc AS C5_td, T5.unad11doc AS C5_doc, T5.unad11razonsocial AS C5_nombre, TB.bita04fecha, TB.bita04hora, TB.bita04minuto, TB.bita04cead, TB.bita04ticketsau, T11.bita03nombre, TB.bita04detalle, T13.unad11razonsocial AS C13_nombre, TB.bita04fechaprobable, TB.bita04fecharesuelve, TB.bita04idtema, T06.bita06nombre, TB.bita04idtiposolicitante, TB.bita04idtiposolicitud, TB.bita04idsolicita, TB.bita04prioridad, TB.bita04idatiende, T13.unad11tipodoc AS C13_td, T13.unad11doc AS C13_doc, TB.bita04id, TB.bita04estado 
FROM bita04bitacora AS TB, bita02estado AS T3, bita01tiposolicitud AS T4, unad11terceros AS T5, bita03prioridad AS T11, unad11terceros AS T13, bita06tema AS T06 
WHERE '.$sSQLwhere1.'TB.bita04estado=T3.bita02id AND TB.bita04idtiposolicitud=T4.bita01id AND TB.bita04idsolicita=T5.unad11id AND TB.bita04prioridad=T11.bita03id AND TB.bita04idatiende=T13.unad11id AND TB.bita04idtema=T06.bita06id '.$sSQLadd.' 
ORDER BY TB.bita04orden, STR_TO_DATE(TB.bita04fechaprobable, "%d/%m/%Y"), STR_TO_DATE(TB.bita04fecha, "%d/%m/%Y") DESC, TB.bita04consec DESC';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_1504" name="consulta_1504" type="hidden" value="'.$sSQLlista.'"/>
<input id="titulos_1504" name="titulos_1504" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objDB->ejecutasql($sSQL.$limite);
	if ($tabladetalle==false){
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 1504: '.$sSQL.'<br>';}
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		//$sLeyenda=$sSQL;
		}
	//'.$ETI['bita04consec'].'
	$res=$sErrConsulta.$sLeyenda.'<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">
<tr class="fondoazul">
<td><b>N&deg;</b></td>
<td><b>'.$ETI['bita04estado'].'</b></td>
<td><b>'.$ETI['bita04idtiposolicitud'].'</b></td>
<td colspan="2"><b>'.$ETI['bita04idsolicita'].'</b></td>
<td><b>'.$ETI['bita04fecha'].'</b></td>
<td><b>'.$ETI['bita04prioridad'].'</b></td>
<td><b>'.$ETI['bita04fechaprobable'].'</b></td>
<td><b>'.$ETI['bita04fecharesuelve'].'</b></td>
<td align="right">
'.html_paginador('paginaf1504', $registros, $lineastabla, $pagina, 'paginarf1504()').'
'.html_lpp('lppf1504', $lineastabla, 'paginarf1504()').'
</td>
</tr>';
	$tlinea=1;
	$sHoy=fecha_hoy();
	$idAtiende=-1;
	while($filadet=$objDB->sf($tabladetalle)){
		if ($idAtiende!=$filadet['bita04idatiende']){
			$idAtiende=$filadet['bita04idatiende'];
			$sPrefijo='<b>';
			$sSufijo='</b>';
			$res=$res.'<tr>
<td colspan="2" align="right">'.$ETI['bita04idatiende'].' &nbsp;</td>
<td colspan="7">'.$sPrefijo.$filadet['C13_td'].' '.$filadet['C13_doc'].' '.cadena_notildes($filadet['C13_nombre']).$sSufijo.'</td>
<tr>';
			}
		$sPrefijo='';
		$sSufijo='';
		$sClass='';
		$sLink='';
		switch($filadet['bita04estado']){
			case 0:
			case 1:
			$sPrefijo='<b>';
			$sSufijo='</b>';
			break;
			case 9:
			$sPrefijo='<span class="rojo">';
			$sSufijo='</span>';
			break;
			}
		$et_bita04fecha=$filadet['bita04fecha'].' '.html_TablaHoraMin($filadet['bita04hora'], $filadet['bita04minuto']);
		$et_bita04fechaprobable='';
		if ($filadet['bita04fechaprobable']!='00/00/0000'){
			$et_bita04fechaprobable=$sPrefijo.$filadet['bita04fechaprobable'].$sSufijo;
			$iDias=fecha_numdiasentrefechas($sHoy, $filadet['bita04fechaprobable']);
			if ($iDias<0){
				$et_bita04fechaprobable=' <span class="rojo">'.$filadet['bita04fechaprobable'].' (Vencida hace '.($iDias*(-1)).' d&iacute;as)</span>';
				}else{
				if ($iDias>0){
					$et_bita04fechaprobable=$sPrefijo.$et_bita04fechaprobable.$sSufijo.' (Faltan: '.$iDias.' d&iacute;as)';
					}
				}
			}
		$et_bita04fecharesuelve='';
		if ($filadet['bita04fecharesuelve']!='00/00/0000'){$et_bita04fecharesuelve=$filadet['bita04fecharesuelve'];}
		if(($tlinea%2)==0){$sClass=' class="resaltetabla"';}
		$tlinea++;
		if ($babierta){
			$sLink='<a href="javascript:cargaridf1504('.$filadet['bita04id'].')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$res=$res.'<tr'.$sClass.'>
<td rowspan="2" align="center">'.$sPrefijo.formato_numero($filadet['bita04consec'], 0).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['bita02nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['bita01nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['C5_td'].' '.$filadet['C5_doc'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['C5_nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.$et_bita04fecha.$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['bita03nombre']).$sSufijo.'</td>
<td>'.$et_bita04fechaprobable.'</td>
<td>'.$sPrefijo.$et_bita04fecharesuelve.$sSufijo.'</td>
<td>'.$sLink.'</td>
</tr>
<tr'.$sClass.'>
<td colspan="9">'.$sPrefijo.cadena_notildes($filadet['bita04detalle']).$sSufijo.'</td>
</tr>';
		}
	$res=$res.'</table>';
	$objDB->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
	}
function f1504_HtmlTabla($aParametros){
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
	list($sDetalle, $sDebugTabla)=f1504_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f1504detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f1504_db_CargarPadre($DATA, $objDB, $bDebug=false){
	$sError='';
	$iTipoError=0;
	$sDebug='';
	require './app.php';
	$DATA['bita04idsolicita_td']=$APP->tipo_doc;
	$DATA['bita04idsolicita_doc']='';
	$DATA['bita04idatiende_td']=$APP->tipo_doc;
	$DATA['bita04idatiende_doc']='';
	$DATA['bita04idelabora_td']=$APP->tipo_doc;
	$DATA['bita04idelabora_doc']='';
	$DATA['bita04idsupervisor_td']=$APP->tipo_doc;
	$DATA['bita04idsupervisor_doc']='';
	if ($DATA['paso']==1){
		$sSQLcondi='bita04consec='.$DATA['bita04consec'].'';
		}else{
		$sSQLcondi='bita04id='.$DATA['bita04id'].'';
		}
	$sSQL='SELECT * FROM bita04bitacora WHERE '.$sSQLcondi;
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$DATA['bita04consec']=$fila['bita04consec'];
		$DATA['bita04id']=$fila['bita04id'];
		$DATA['bita04estado']=$fila['bita04estado'];
		$DATA['bita04idtiposolicitud']=$fila['bita04idtiposolicitud'];
		$DATA['bita04idsolicita']=$fila['bita04idsolicita'];
		$DATA['bita04fecha']=$fila['bita04fecha'];
		$DATA['bita04hora']=$fila['bita04hora'];
		$DATA['bita04minuto']=$fila['bita04minuto'];
		$DATA['bita04idzona']=$fila['bita04idzona'];
		$DATA['bita04cead']=$fila['bita04cead'];
		$DATA['bita04ticketsau']=$fila['bita04ticketsau'];
		$DATA['bita04prioridad']=$fila['bita04prioridad'];
		$DATA['bita04detalle']=$fila['bita04detalle'];
		$DATA['bita04idatiende']=$fila['bita04idatiende'];
		$DATA['bita04fechaprobable']=$fila['bita04fechaprobable'];
		$DATA['bita04fecharesuelve']=$fila['bita04fecharesuelve'];
		$DATA['bita04idtema']=$fila['bita04idtema'];
		$DATA['bita04idtiposolicitante']=$fila['bita04idtiposolicitante'];
		$DATA['bita04orden']=$fila['bita04orden'];
		$DATA['bita04idelabora']=$fila['bita04idelabora'];
		$DATA['bita04idequipotrabajo']=$fila['bita04idequipotrabajo'];
		$DATA['bita04iddependencia']=$fila['bita04iddependencia'];
		$DATA['bita04idsupervisor']=$fila['bita04idsupervisor'];
		$DATA['bita04infocomplemento']=$fila['bita04infocomplemento'];
		$DATA['bita04mailrespusta']=$fila['bita04mailrespusta'];
		$DATA['bita04fechavence']=$fila['bita04fechavence'];
		$DATA['bita04minutovence']=$fila['bita04minutovence'];
		$DATA['bita04diasrespuesta']=$fila['bita04diasrespuesta'];
		$DATA['bita04minutosrespuesta']=$fila['bita04minutosrespuesta'];
		$bcargo=true;
		$DATA['paso']=2;
		$DATA['boculta1504']=0;
		$bLimpiaHijos=true;
		}else{
		$DATA['paso']=0;
		}
	return array($DATA, $sError, $iTipoError, $sDebug);
	}
function f1504_db_GuardarV2($DATA, $objDB, $bDebug=false){
	$iCodModulo=1504;
	$bAudita[2]=true;
	$bAudita[3]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_1504=$APP->rutacomun.'lg/lg_1504_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1504)){$mensajes_1504=$APP->rutacomun.'lg/lg_1504_es.php';}
	require $mensajes_todas;
	require $mensajes_1504;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	// -- Se inicia validando todas las posibles entradas de usuario.
	/*
	if (isset($DATA['bita04consec'])==0){$DATA['bita04consec']='';}
	if (isset($DATA['bita04id'])==0){$DATA['bita04id']='';}
	if (isset($DATA['bita04estado'])==0){$DATA['bita04estado']='';}
	if (isset($DATA['bita04idtiposolicitud'])==0){$DATA['bita04idtiposolicitud']='';}
	if (isset($DATA['bita04idsolicita'])==0){$DATA['bita04idsolicita']='';}
	if (isset($DATA['bita04fecha'])==0){$DATA['bita04fecha']='';}
	if (isset($DATA['bita04hora'])==0){$DATA['bita04hora']='';}
	if (isset($DATA['bita04minuto'])==0){$DATA['bita04minuto']='';}
	if (isset($DATA['bita04idzona'])==0){$DATA['bita04idzona']='';}
	if (isset($DATA['bita04cead'])==0){$DATA['bita04cead']='';}
	if (isset($DATA['bita04ticketsau'])==0){$DATA['bita04ticketsau']='';}
	if (isset($DATA['bita04prioridad'])==0){$DATA['bita04prioridad']='';}
	if (isset($DATA['bita04detalle'])==0){$DATA['bita04detalle']='';}
	if (isset($DATA['bita04idatiende'])==0){$DATA['bita04idatiende']='';}
	if (isset($DATA['bita04fechaprobable'])==0){$DATA['bita04fechaprobable']='';}
	if (isset($DATA['bita04fecharesuelve'])==0){$DATA['bita04fecharesuelve']='';}
	if (isset($DATA['bita04idtema'])==0){$DATA['bita04idtema']='';}
	if (isset($DATA['bita04idtiposolicitante'])==0){$DATA['bita04idtiposolicitante']='';}
	if (isset($DATA['bita04idelabora'])==0){$DATA['bita04idelabora']='';}
	if (isset($DATA['bita04idequipotrabajo'])==0){$DATA['bita04idequipotrabajo']='';}
	if (isset($DATA['bita04idsupervisor'])==0){$DATA['bita04idsupervisor']='';}
	if (isset($DATA['bita04infocomplemento'])==0){$DATA['bita04infocomplemento']='';}
	if (isset($DATA['bita04fechavence'])==0){$DATA['bita04fechavence']='';}
	if (isset($DATA['bita04minutovence'])==0){$DATA['bita04minutovence']='';}
	*/
	if ($DATA['bita04idequipotrabajo']==''){$DATA['bita04idequipotrabajo']=0;}
	if (isset($DATA['bita04orden'])==0){$DATA['bita04orden']=0;}
	$DATA['bita04consec']=numeros_validar($DATA['bita04consec']);
	$DATA['bita04estado']=numeros_validar($DATA['bita04estado']);
	$DATA['bita04idtiposolicitud']=numeros_validar($DATA['bita04idtiposolicitud']);
	$DATA['bita04hora']=numeros_validar($DATA['bita04hora']);
	$DATA['bita04minuto']=numeros_validar($DATA['bita04minuto']);
	$DATA['bita04idzona']=numeros_validar($DATA['bita04idzona']);
	$DATA['bita04cead']=numeros_validar($DATA['bita04cead']);
	$DATA['bita04ticketsau']=htmlspecialchars(trim($DATA['bita04ticketsau']));
	$DATA['bita04prioridad']=numeros_validar($DATA['bita04prioridad']);
	$DATA['bita04detalle']=htmlspecialchars(trim($DATA['bita04detalle']));
	$DATA['bita04idtema']=numeros_validar($DATA['bita04idtema']);
	$DATA['bita04idtiposolicitante']=numeros_validar($DATA['bita04idtiposolicitante']);
	$DATA['bita04orden']=numeros_validar($DATA['bita04orden']);
	$DATA['bita04idequipotrabajo']=numeros_validar($DATA['bita04idequipotrabajo']);
	$DATA['bita04infocomplemento']=htmlspecialchars(trim($DATA['bita04infocomplemento']));
	$DATA['bita04mailrespusta']=htmlspecialchars(trim($DATA['bita04mailrespusta']));
	$DATA['bita04minutovence']=numeros_validar($DATA['bita04minutovence']);
	// -- Se inicializan las variables que puedan pasar vacias {Especialmente números}.
	if ($DATA['bita04estado']==''){$DATA['bita04estado']=0;}
	//if ($DATA['bita04idtiposolicitud']==''){$DATA['bita04idtiposolicitud']=0;}
	//if ($DATA['bita04hora']==''){$DATA['bita04hora']=0;}
	//if ($DATA['bita04minuto']==''){$DATA['bita04minuto']=0;}
	//if ($DATA['bita04idzona']==''){$DATA['bita04idzona']=0;}
	if ($DATA['bita04cead']==''){$DATA['bita04cead']=0;}
	//if ($DATA['bita04prioridad']==''){$DATA['bita04prioridad']=0;}
	if ($DATA['bita04idtema']==''){$DATA['bita04idtema']=0;}
	//if ($DATA['bita04idtiposolicitante']==''){$DATA['bita04idtiposolicitante']=0;}
	if ($DATA['bita04orden']==''){$DATA['bita04orden']=0;}
	// -- Seccion para validar los posibles causales de error.
	$sSepara=', ';
	if (true){
	if ($DATA['bita04idtiposolicitante']==''){$sError=$ERR['bita04idtiposolicitante'];}
	//if ($DATA['bita04idtema']==''){$sError=$ERR['bita04idtema'];}
	if (!fecha_esvalida($DATA['bita04fecharesuelve'])){
		$DATA['bita04fecharesuelve']='00/00/0000';
		//$sError=$ERR['bita04fecharesuelve'];
		}
	if (!fecha_esvalida($DATA['bita04fechaprobable'])){
		$DATA['bita04fechaprobable']='00/00/0000';
		//$sError=$ERR['bita04fechaprobable'];
		}
	if ($DATA['bita04idatiende']==0){$sError=$ERR['bita04idatiende'];}
	if ($DATA['bita04detalle']==''){$sError=$ERR['bita04detalle'];}
	if ($DATA['bita04prioridad']==''){$sError=$ERR['bita04prioridad'];}
	//if ($DATA['bita04ticketsau']==''){$sError=$ERR['bita04ticketsau'];}
	//if ($DATA['bita04cead']==''){$sError=$ERR['bita04cead'];}
	if ($DATA['bita04minuto']==''){$sError=$ERR['bita04minuto'];}
	if ($DATA['bita04hora']==''){$sError=$ERR['bita04hora'];}
	if (!fecha_esvalida($DATA['bita04fecha'])){
		//$DATA['bita04fecha']='00/00/0000';
		$sError=$ERR['bita04fecha'];
		}
	if ($DATA['bita04idsolicita']==0){$sError=$ERR['bita04idsolicita'];}
	if ($DATA['bita04idtiposolicitud']==''){$sError=$ERR['bita04idtiposolicitud'];}
	if ($DATA['bita04estado']==''){$sError=$ERR['bita04estado'];}
		}
	//Valiaciones de campos obligatorios en todo guardar.
	//if ($DATA['bita04id']==''){$sError=$ERR['bita04id'];}//CONSECUTIVO
	//if ($DATA['bita04consec']==''){$sError=$ERR['bita04consec'];}//CONSECUTIVO
	// -- Se verifican los valores de campos de otras tablas.
	if ($sError==''){$sError=tabla_terceros_existe($DATA['bita04idatiende_td'], $DATA['bita04idatiende_doc'], $objDB, 'El tercero Atiende ');}
	if ($sError==''){
		list($sError, $sInfo)=tercero_Bloqueado($DATA['bita04idatiende'], $objDB);
		if ($sInfo!=''){$sError=$sError.'<br>'.sInfo;}
		}
	if ($sError==''){$sError=tabla_terceros_existe($DATA['bita04idsolicita_td'], $DATA['bita04idsolicita_doc'], $objDB, 'El tercero Solicita ');}
	if ($sError==''){
		if ($DATA['bita04idtema']!=0){
		if ($DATA['paso']==10){
			//Ver que el tema este vigente.
			$sSQL='SELECT bita06activo FROM bita06tema WHERE bita06id='.$DATA['bita04idtema'].'';
			$tabla=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla)>0){
				$fila=$objDB->sf($tabla);
				if ($fila['bita06activo']!='S'){
					$sError='El tema seleccionado no se encuentra activo.';
					}
				}else{
				$sError='No se encuentra el tema Ref {'.$DATA['bita04idtema'].'}';
				}
			}
			}
		}
	if ($sError==''){
		if ($DATA['paso']==10){
			if ($DATA['bita04consec']==''){
				$DATA['bita04consec']=tabla_consecutivo('bita04bitacora', 'bita04consec', '', $objDB);
				if ($DATA['bita04consec']==-1){$sError=$objDB->serror;}
				}else{
				if (!seg_revisa_permiso($iCodModulo, 8, $objDB)){
					$sError=$ERR['8'];
					$DATA['bita04consec']='';
					}
				}
			if ($sError==''){
				$sSQL='SELECT bita04consec FROM bita04bitacora WHERE bita04consec='.$DATA['bita04consec'].'';
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
			$DATA['bita04id']=tabla_consecutivo('bita04bitacora','bita04id', '', $objDB);
			if ($DATA['bita04id']==-1){$sError=$objDB->serror;}
			}
		}
	if ($sError==''){
		if (get_magic_quotes_gpc()==1){$DATA['bita04detalle']=stripslashes($DATA['bita04detalle']);}
		//Si el campo bita04detalle permite html quite la linea htmlspecialchars para el campo y habilite la siguiente linea:
		//$bita04detalle=addslashes($DATA['bita04detalle']);
		$bita04detalle=str_replace('"', '\"', $DATA['bita04detalle']);
		if (get_magic_quotes_gpc()==1){$DATA['bita04infocomplemento']=stripslashes($DATA['bita04infocomplemento']);}
		//Si el campo bita04infocomplemento permite html quite la linea htmlspecialchars para el campo y habilite la siguiente linea:
		//$bita04infocomplemento=addslashes($DATA['bita04infocomplemento']);
		$bita04infocomplemento=str_replace('"', '\"', $DATA['bita04infocomplemento']);
		$bpasa=false;
		if ($DATA['paso']==10){
			$DATA['bita04iddependencia']=0;
			$bita04fechavence=0;
			$DATA['bita04diasrespuesta']=0;
			$DATA['bita04minutosrespuesta']=0;
			$sCampos1504='bita04consec, bita04id, bita04estado, bita04idtiposolicitud, bita04idsolicita, bita04fecha, bita04hora, bita04minuto, bita04idzona, bita04cead, 
bita04ticketsau, bita04prioridad, bita04detalle, bita04idatiende, bita04fechaprobable, bita04fecharesuelve, bita04idtema, bita04idtiposolicitante, bita04orden, bita04idelabora, 
bita04idequipotrabajo, bita04iddependencia, bita04idsupervisor, bita04infocomplemento, bita04mailrespusta, bita04fechavence, bita04minutovence, bita04diasrespuesta, bita04minutosrespuesta';
			$sValores1504=''.$DATA['bita04consec'].', '.$DATA['bita04id'].', '.$DATA['bita04estado'].', '.$DATA['bita04idtiposolicitud'].', '.$DATA['bita04idsolicita'].', "'.$DATA['bita04fecha'].'", '.$DATA['bita04hora'].', '.$DATA['bita04minuto'].', '.$DATA['bita04idzona'].', '.$DATA['bita04cead'].', 
"'.$DATA['bita04ticketsau'].'", '.$DATA['bita04prioridad'].', "'.$bita04detalle.'", '.$DATA['bita04idatiende'].', "'.$DATA['bita04fechaprobable'].'", "'.$DATA['bita04fecharesuelve'].'", '.$DATA['bita04idtema'].', '.$DATA['bita04idtiposolicitante'].', '.$DATA['bita04orden'].', '.$DATA['bita04idelabora'].', 
'.$DATA['bita04idequipotrabajo'].', '.$DATA['bita04iddependencia'].', '.$DATA['bita04idsupervisor'].', "'.$bita04infocomplemento.'", "'.$DATA['bita04mailrespusta'].'", "'.$DATA['bita04fechavence'].'", '.$DATA['bita04minutovence'].', '.$DATA['bita04diasrespuesta'].', '.$DATA['bita04minutosrespuesta'].'';
			if ($APP->utf8==1){
				$sSQL='INSERT INTO bita04bitacora ('.$sCampos1504.') VALUES ('.utf8_encode($sValores1504).');';
				$sdetalle=$sCampos1504.'['.utf8_encode($sValores1504).']';
				}else{
				$sSQL='INSERT INTO bita04bitacora ('.$sCampos1504.') VALUES ('.$sValores1504.');';
				$sdetalle=$sCampos1504.'['.$sValores1504.']';
				}
			$idaccion=2;
			$bpasa=true;
			}else{
			$scampo[1]='bita04idsolicita';
			$scampo[2]='bita04fecha';
			$scampo[3]='bita04hora';
			$scampo[4]='bita04minuto';
			$scampo[5]='bita04idzona';
			$scampo[6]='bita04cead';
			$scampo[7]='bita04ticketsau';
			$scampo[8]='bita04prioridad';
			$scampo[9]='bita04detalle';
			$scampo[10]='bita04idatiende';
			$scampo[11]='bita04fechaprobable';
			$scampo[12]='bita04fecharesuelve';
			$scampo[13]='bita04idtema';
			$scampo[14]='bita04idtiposolicitante';
			$scampo[15]='bita04orden';
			$scampo[16]='bita04idelabora';
			$scampo[17]='bita04idequipotrabajo';
			$scampo[18]='bita04infocomplemento';
			$scampo[19]='bita04fechavence';
			$scampo[20]='bita04minutovence';
			$sdato[1]=$DATA['bita04idsolicita'];
			$sdato[2]=$DATA['bita04fecha'];
			$sdato[3]=$DATA['bita04hora'];
			$sdato[4]=$DATA['bita04minuto'];
			$sdato[5]=$DATA['bita04idzona'];
			$sdato[6]=$DATA['bita04cead'];
			$sdato[7]=$DATA['bita04ticketsau'];
			$sdato[8]=$DATA['bita04prioridad'];
			$sdato[9]=$bita04detalle;
			$sdato[10]=$DATA['bita04idatiende'];
			$sdato[11]=$DATA['bita04fechaprobable'];
			$sdato[12]=$DATA['bita04fecharesuelve'];
			$sdato[13]=$DATA['bita04idtema'];
			$sdato[14]=$DATA['bita04idtiposolicitante'];
			$sdato[15]=$DATA['bita04orden'];
			$sdato[16]=$DATA['bita04idelabora'];
			$sdato[17]=$DATA['bita04idequipotrabajo'];
			$sdato[18]=$bita04infocomplemento;
			$sdato[19]=$DATA['bita04fechavence'];
			$sdato[20]=$DATA['bita04minutovence'];
			$numcmod=20;
			$sWhere='bita04id='.$DATA['bita04id'].'';
			$sSQL='SELECT * FROM bita04bitacora WHERE '.$sWhere;
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
				//Si cambia de responsable, guardamos el historico.
				if ($filabase['bita04idatiende']!=$DATA['bita04idatiende']){
					list($sErrorH, $sDebugH)=f1504_HistoricoResponsable($DATA['bita04id'], $filabase['bita04idatiende'], '', $objDB, $bDebug);
					}
				//Fin de guardar el historico
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
					$sSQL='UPDATE bita04bitacora SET '.utf8_encode($sdatos).' WHERE '.$sWhere.';';
					}else{
					$sdetalle=$sdatos.'['.$sWhere.']';
					$sSQL='UPDATE bita04bitacora SET '.$sdatos.' WHERE '.$sWhere.';';
					}
				$idaccion=3;
				}
			}
		if ($bpasa){
			$result=$objDB->ejecutasql($sSQL);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' [1504] ..<!-- '.$sSQL.' -->';
				if ($idaccion==2){$DATA['bita04id']='';}
				$DATA['paso']=$DATA['paso']-10;
				}else{
				if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Guardar 1504 '.$sSQL.'<br>';}
				if ($bAudita[$idaccion]){seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], $idaccion, $DATA['bita04id'], $sdetalle, $objDB);}
				$DATA['paso']=2;
				}
			}else{
			$DATA['paso']=2;
			}
		}else{
		$DATA['paso']=$DATA['paso']-10;
		}
	//if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' InfoDepura<br>';}
	return array($DATA, $sError, $iTipoError, $sDebug);
	}
function f1504_db_Eliminar($bita04id, $objDB, $bDebug=false){
	$iCodModulo=1504;
	$bAudita[4]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_1504=$APP->rutacomun.'lg/lg_1504_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1504)){$mensajes_1504=$APP->rutacomun.'lg/lg_1504_es.php';}
	require $mensajes_todas;
	require $mensajes_1504;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	$bita04id=numeros_validar($bita04id);
	// Traer los datos para hacer las validaciones.
	if ($sError==''){
		$sSQL='SELECT * FROM bita04bitacora WHERE bita04id='.$bita04id.'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$filabase=$objDB->sf($tabla);
			}else{
			$sError='No se encuentra el registro solicitado {Ref: '.$bita04id.'}';
			}
		}
	if ($sError==''){
		$sSQL='SELECT bita05idbitacora FROM bita05bitacoranota WHERE bita05idbitacora='.$filabase['bita04id'].'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$sError='Existen Anotaciones creados, no es posible eliminar';
			}
		}
	if ($sError==''){
		$sSQL='SELECT bita29idbitacora FROM bita29bitahistestado WHERE bita29idbitacora='.$filabase['bita04id'].'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$sError='Existen Cambios de estado creados, no es posible eliminar';
			}
		}
	if ($sError==''){
		$sSQL='SELECT bita30idbitacora FROM bita30bitahistrespon WHERE bita30idbitacora='.$filabase['bita04id'].'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$sError='Existen Cambios de responsable creados, no es posible eliminar';
			}
		}
	if ($sError==''){
		if (!seg_revisa_permiso($iCodModulo, 4, $objDB)){
			$sError=$ERR['4'];
			}
		}
	if ($sError==''){
		$sSQL='SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=1504';
		$tablaor=$objDB->ejecutasql($sSQL);
		while ($filaor=$objDB->sf($tablaor)){
			$sSQL='SELECT '.$filaor['campoid'].' FROM '.$filaor['tabla'].' WHERE '.$filaor['camporev'].'='.$_REQUEST['bita04id'].' LIMIT 0, 1';
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
		//$sSQL='DELETE FROM bita05bitacoranota WHERE bita05idbitacora='.$filabase['bita04id'].'';
		//$tabla=$objDB->ejecutasql($sSQL);
		//$sSQL='DELETE FROM bita29bitahistestado WHERE bita29idbitacora='.$filabase['bita04id'].'';
		//$tabla=$objDB->ejecutasql($sSQL);
		//$sSQL='DELETE FROM bita30bitahistrespon WHERE bita30idbitacora='.$filabase['bita04id'].'';
		//$tabla=$objDB->ejecutasql($sSQL);
		$sWhere='bita04id='.$bita04id.'';
		//$sWhere='bita04consec='.$filabase['bita04consec'].'';
		$sSQL='DELETE FROM bita04bitacora WHERE '.$sWhere.';';
		$result=$objDB->ejecutasql($sSQL);
		if ($result==false){
			$sError=$ERR['falla_eliminar'].' .. <!-- '.$sSQL.' -->';
			}else{
			if ($bAudita[4]){seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $bita04id, $sWhere, $objDB);}
			}
		}
	return array($sError, $iTipoError, $sDebug);
	}
function f1504_TituloBusqueda(){
	return 'Busqueda de Bitacoras';
	}
function f1504_ParametrosBusqueda(){
	$sParams='<label class="Label90">Nombre</label><label><input id="b1504nombre" name="b1504nombre" type="text" value="" onchange="paginarbusqueda()" /></label>';
	return $sParams;
	}
function f1504_JavaScriptBusqueda($iModuloBusca){
	$sRes='var sCampo=window.document.frmedita.scampobusca.value;
var params=new Array();
params[100]=sCampo;
params[101]=window.document.frmedita.paginabusqueda.value;
params[102]=window.document.frmedita.lppfbusqueda.value;
params[103]=window.document.frmedita.b1504nombre.value;
xajax_f'.$iModuloBusca.'_HtmlBusqueda(params);';
	return $sRes;
	}
function f1504_TablaDetalleBusquedas($aParametros, $objDB){
	$res='';
	return utf8_encode($res);
	}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------
function f1504_HTMLComboV2_EquipoMiembros($objDB, $objCombos, $valor, $vrbita04idequipo){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	//@@ Se debe arreglar la condicion..
	$sCondi='TB.bita28idequipotrab="'.$vrbita04idequipo.'" AND TB.bita28activo="S" AND TB.bita28idtercero=T11.unad11id';
	if ($sCondi!=''){$sCondi=' WHERE '.$sCondi;}
	$objCombos->nuevo('equipomiembros', $valor, true, '{'.$ETI['msg_seleccione'].'}', '');
	$objCombos->sAccion='asignarresponsable()';
	$sSQL='SELECT TB.bita28idtercero AS id, T11.unad11razonsocial AS nombre FROM bita28eqipoparte AS TB, unad11terceros AS T11'.$sCondi.' ORDER BY T11.unad11razonsocial';
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f1504_CambiaEquipo($aParametros){
	$iCodModulo=1504;
	$_SESSION['u_ultimominuto']=iminutoavance();
	$sError='';
	$bDebug=false;
	$sDebug='';
	$opts=$aParametros;
	if(!is_array($opts)){$opts=json_decode(str_replace('\"','"',$opts),true);}
	if (isset($opts[0])!=0){if ($opts[0]==1){$bDebug=true;}}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$idBitacora=(int)$aParametros[1];
	$idEquipo=(int)$aParametros[2];
	$idSupervisor=0;
	$iTipoError=0;
	$objCombos=new clsHtmlCombos('n');
	$html_miembros=f1504_HTMLComboV2_EquipoMiembros($objDB, $objCombos, '', $idEquipo);
	$sDetalleAudita='Asigna el equipo de trabajo '.$idEquipo.'';
	$sActualiza='bita04idequipotrabajo='.$idEquipo.'';
	if ($idEquipo!=0){
		$sSQL='SELECT TB.bita27idlider, T11.unad11tipodoc, T11.unad11doc, T11.unad11razonsocial 
FROM bita27equipotrabajo AS TB, unad11terceros AS T11 
WHERE TB.bita27id='.$idEquipo.' AND TB.bita27idlider=T11.unad11id';
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Datos del equipo de trabajo: '.$sSQL.'<br>';}
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$fila=$objDB->sf($tabla);
			if ($fila['bita27idlider']!=0){
				$idSupervisor=$fila['bita27idlider'];
				$bita04idsupervisor_td=$fila['unad11tipodoc'];
				$bita04idsupervisor_doc=$fila['unad11doc'];
				$bita04idsupervisor_nombre='<b>'.cadena_notildes($fila['unad11razonsocial']).'</b>';
				$sActualiza=$sActualiza.', bita04idsupervisor='.$idSupervisor.'';
				$sDetalleAudita=$sDetalleAudita.', se cambia el supervisor a '.$fila['unad11tipodoc'].''.$fila['unad11doc'].' '.cadena_notildes($fila['unad11razonsocial']).'';
				}
			}
		}
	//Actualizar la bitacora
	if ($idBitacora!=0){
		$sSQL='UPDATE bita04bitacora SET '.$sActualiza.' WHERE bita04id='.$idBitacora.'';
		$tabla=$objDB->ejecutasql($sSQL);
		$sMensaje='Se ha cambiado el grupo de trabajo de la bitacora.';
		$iTipoError=1;
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Actualiza la bitacora '.$sSQL.'<br>';}
		//Auditar porque esto es un tema delicado
		seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 3, $idBitacora, $sDetalleAudita, $objDB);
		}
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_equipomiembros', 'innerHTML', $html_miembros);
	if ($idSupervisor!=0){
		$objResponse->assign('bita04idsupervisor', 'value', $idSupervisor);
		$objResponse->assign('bita04idsupervisor_td', 'value', $bita04idsupervisor_td);
		$objResponse->assign('bita04idsupervisor_doc', 'value', $bita04idsupervisor_doc);
		$objResponse->assign('div_bita04idsupervisor', 'innerHTML', $bita04idsupervisor_nombre);
		}
	if ($idBitacora!=0){
		$objResponse->call("MensajeAlarmaV2('".$sMensaje."', ".$iTipoError.")");
		}
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f1504_HistoricoResponsable($bita30idbitacora, $idPrevio, $bita30nota, $objDB, $bDebug=false){
	$sError='';
	$sDebug='';
	if ($idPrevio==-1){
		$bita30idresponsable=0;
		$sSQL='SELECT bita04idatiende FROM bita04bitacora WHERE bita04id='.$bita30idbitacora.'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$fila=$objDB->sf($tabla);
			$bita30idresponsable=$fila['bita04idatiende'];
			}
		}else{
		$bita30idresponsable=$idPrevio;
		}
	if ($sError==''){
		$bita30consec=tabla_consecutivo('bita30bitahistrespon', 'bita30consec', 'bita30idbitacora='.$bita30idbitacora.'', $objDB);
		$bita30id=tabla_consecutivo('bita30bitahistrespon', 'bita30id', '', $objDB);
		$bita30fechafin=fecha_DiaMod();
		$bita30horafin=fecha_MinutoMod();
		$bita30nota=str_replace('"', '\"', $bita30nota);
		}
	if ($sError==''){
		$scampos='bita30idbitacora, bita30consec, bita30id, bita30idresponsable, bita30fechafin, 
bita30horafin, bita30nota';
		$svalores=''.$bita30idbitacora.', '.$bita30consec.', '.$bita30id.', '.$bita30idresponsable.', '.$bita30fechafin.', 
'.$bita30horafin.', "'.$bita30nota.'"';
		$sSQL='INSERT INTO bita30bitahistrespon ('.$scampos.') VALUES ('.$svalores.');';
		$result=$objDB->ejecutasql($sSQL);
		}
	return array($sError, $sDebug);
	}
function f1504_CambiaEstado($idBitacora, $idEstadoOrigen, $idEstadoDestino, $sDetalle, $objDB, $bDebug=false){
	$iCodModulo=1504;
	$sError='';
	$sDebug='';
	$sComplemento='';
	switch($idEstadoDestino){
		case 7: //Resuelta
		case 9: // Anulada
		$sComplemento=', bita04orden=7, bita04fechaprobable="00/00/0000"';
		break;
		}
	$sql='UPDATE bita04bitacora SET bita04estado='. $idEstadoDestino.$sComplemento.' WHERE bita04id='.$idBitacora.';';
	$result=$objDB->ejecutasql($sql);
	seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 3, $idBitacora, 'Cambia el estado a '. $idEstadoDestino.'', $objDB);
	//Armar el historial
	$bita29consec=tabla_consecutivo('bita29bitahistestado', 'bita29consec', 'bita29idbitacora='.$idBitacora.'', $objDB);
	$bita29id=tabla_consecutivo('bita29bitahistestado', 'bita29id', '', $objDB);
	$bita29fecha=fecha_DiaMod();
	$bita29hora=fecha_MinutoMod();
	$scampos='bita29idbitacora, bita29consec, bita29id, bita29idestadoorigen, bita29idestadofin, 
bita29idusuario, bita29fecha, bita29hora';
	$svalores=''.$idBitacora.', '.$bita29consec.', '.$bita29id.', '.$idEstadoOrigen.', '.$idEstadoDestino.', 
'.$_SESSION['unad_id_tercero'].', '.$bita29fecha.', '.$bita29hora.'';
	$sSQL='INSERT INTO bita29bitahistestado ('.$scampos.') VALUES ('.$svalores.');';
	$result=$objDB->ejecutasql($sSQL);
	//Termina con el historial
	return array($sError, $sDebug);
	}
function f1504_AsignarResponsable($aParametros){
	$iCodModulo=1504;
	$_SESSION['u_ultimominuto']=iminutoavance();
	$sError='';
	$bDebug=false;
	$sDebug='';
	$opts=$aParametros;
	if(!is_array($opts)){$opts=json_decode(str_replace('\"','"',$opts),true);}
	if (isset($opts[0])!=0){if ($opts[0]==1){$bDebug=true;}}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$idBitacora=(int)$aParametros[1];
	$idResponsable=(int)$aParametros[2];
	$iTipoError=0;
	$sActualiza='';
	$sMensaje='';
	if ($idResponsable!=0){
		$sSQL='SELECT T11.unad11tipodoc, T11.unad11doc, T11.unad11razonsocial 
FROM unad11terceros AS T11 
WHERE T11.unad11id='.$idResponsable.'';
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Datos del responsable: '.$sSQL.'<br>';}
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$fila=$objDB->sf($tabla);
			$bita04idatiende_td=$fila['unad11tipodoc'];
			$bita04idatiende_doc=$fila['unad11doc'];
			$bita04idatiende_nombre='<b>'.cadena_notildes($fila['unad11razonsocial']).'</b>';
			$sActualiza='bita04idatiende='.$idResponsable.'';
			$sDetalleAudita='Se cambia el responsable a '.$fila['unad11tipodoc'].''.$fila['unad11doc'].' '.cadena_notildes($fila['unad11razonsocial']).'';
			}
		}
	if ($idBitacora!=0){
		if ($sActualiza!=''){
			list($sErrorH, $sDebugH)=f1504_HistoricoResponsable($idBitacora, -1, '', $objDB, $bDebug);
			$sDebug=$sDebug.$sDebugH;
			$sSQL='UPDATE bita04bitacora SET '.$sActualiza.' WHERE bita04id='.$idBitacora.'';
			$tabla=$objDB->ejecutasql($sSQL);
			$sMensaje='Se ha cambiado el responsable de la bitacora.';
			$iTipoError=1;
			if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Actualiza la bitacora '.$sSQL.'<br>';}
			//Auditar porque esto es un tema delicado
			seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 3, $idBitacora, $sDetalleAudita, $objDB);
			}
		}
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	if ($idResponsable!=0){
		$objResponse->assign('bita04idatiende', 'value', $idResponsable);
		$objResponse->assign('bita04idatiende_td', 'value', $bita04idatiende_td);
		$objResponse->assign('bita04idatiende_doc', 'value', $bita04idatiende_doc);
		$objResponse->assign('div_bita04idatiende', 'innerHTML', $bita04idatiende_nombre);
		}
	if ($sActualiza!=''){
		$objResponse->call('paginarf1530');
		}
	if ($sMensaje!=''){
		$objResponse->call("MensajeAlarmaV2('".$sMensaje."', ".$iTipoError.")");
		}
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
?>