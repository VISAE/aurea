<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2019 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.23.3 martes, 16 de julio de 2019
--- 2407 ceca07solicitudrecal
*/
/** Archivo lib2407.php.
* Libreria 2407 ceca07solicitudrecal.
* @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
* @date martes, 16 de julio de 2019
*/
function f2407_HTMLComboV2_ceca07idperaca($objDB, $objCombos, $valor, $vrceca07idtercero){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$sIds='-99';
	if ((int)$vrceca07idtercero!=0){
		$sSQL='SELECT core16peraca FROM core16actamatricula WHERE core16tercero='.$vrceca07idtercero.' AND core16peraca>611';
		$tabla=$objDB->ejecutasql($sSQL);
		while($fila=$objDB->sf($tabla)){
			$sIds=$sIds.','.$fila['core16peraca'];
			}
		}
	$sWhere='exte02id IN ('.$sIds.')';
	$objCombos->nuevo('ceca07idperaca', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	$objCombos->sAccion='carga_combo_ceca07idcurso()';
	$sSQL=f146_ConsultaCombo($sWhere, $objDB);
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f2407_HTMLComboV2_ceca07idcurso($objDB, $objCombos, $valor, $vrceca07idperaca, $vrceca07idtercero){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$sIds='-99';
	$sBase='';
	$bConTodos=false;
	if (((int)$vrceca07idtercero!=0)&&((int)$vrceca07idperaca!=0)){
		$bConTodos=true;
		list($iContenedor, $sError)=f1011_BloqueTercero($vrceca07idtercero, $objDB);
		$sSQL='SELECT core04idcurso FROM core04matricula_'.$iContenedor.' WHERE core04peraca='.$vrceca07idperaca.' AND core04tercero='.$vrceca07idtercero.'';
		$tabla=$objDB->ejecutasql($sSQL);
		while($fila=$objDB->sf($tabla)){
			$sIds=$sIds.','.$fila['core04idcurso'];
			}
		}
	$sWhere=' WHERE unad40id IN ('.$sIds.')';
	$objCombos->nuevo('ceca07idcurso', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	$objCombos->sAccion='carga_combo_ceca07idactividad()';
	if ($bConTodos){
		$objCombos->addItem('0', '{'.$ETI['msg_todos'].'}');
		}
	$sSQL='SELECT unad40id AS id, CONCAT(unad40consec, " - ", unad40nombre) AS nombre FROM unad40curso'.$sWhere.' ORDER BY unad40id';
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f2407_HTMLComboV2_ceca07idactividad($objDB, $objCombos, $valor, $vrceca07idperaca, $vrceca07idcurso, $vrceca07idtercero){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$sSQL='';
	$bConTodos=false;
	if (((int)$vrceca07idtercero!=0)&&((int)$vrceca07idperaca!=0)){
		if ($vrceca07idcurso!=''){
			if ($vrceca07idcurso=='0'){
				$bConTodos=true;
				}else{
				list($iContenedor, $sError)=f1011_BloqueTercero($vrceca07idtercero, $objDB);
				$sSQL='SELECT T4.ofer04id AS id, CONCAT(T4.ofer04nombre, " - ", T5.core05nota, "/", (T5.core05puntaje75+T5.core05puntaje25)) AS nombre 
FROM core04matricula_'.$iContenedor.' AS TB, core05actividades_'.$iContenedor.' AS T5, ofer04cursoactividad AS T4 
WHERE TB.core04peraca='.$vrceca07idperaca.' AND TB.core04tercero='.$vrceca07idtercero.' AND TB.core04idcurso='.$vrceca07idcurso.' AND TB.core04id=T5.core05idmatricula AND T5.core05estado IN (5, 7) AND T5.core05idactividad=T4.ofer04id';
				}
			}
		}
	$objCombos->nuevo('ceca07idactividad', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	if ($bConTodos){
		$objCombos->addItem('0', '{'.$ETI['msg_todos'].'}');
		}
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f2407_HTMLComboV2_ceca07idmotivosol($objDB, $objCombos, $valor){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('ceca07idmotivosol', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	$sSQL='SELECT ceca11id AS id, ceca11nombre AS nombre FROM ceca11motivosrecalifica';
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f2407_Comboceca07idperaca($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$objCombos=new clsHtmlCombos('n');
	$html_ceca07idperaca=f2407_HTMLComboV2_ceca07idperaca($objDB, $objCombos, '', $aParametros[0]);
	$html_ceca07idcurso=f2407_HTMLComboV2_ceca07idcurso($objDB, $objCombos, '', '', '');
	$html_ceca07idactividad=f2407_HTMLComboV2_ceca07idactividad($objDB, $objCombos, '', '', '', '');
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_ceca07idperaca', 'innerHTML', $html_ceca07idperaca);
	$objResponse->assign('div_ceca07idcurso', 'innerHTML', $html_ceca07idcurso);
	$objResponse->assign('div_ceca07idactividad', 'innerHTML', $html_ceca07idactividad);
	return $objResponse;
	}
function f2407_Comboceca07idcurso($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$objCombos=new clsHtmlCombos('n');
	$html_ceca07idcurso=f2407_HTMLComboV2_ceca07idcurso($objDB, $objCombos, '', $aParametros[0], $aParametros[1]);
	$html_ceca07idactividad=f2407_HTMLComboV2_ceca07idactividad($objDB, $objCombos, '', '', '', '');
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_ceca07idcurso', 'innerHTML', $html_ceca07idcurso);
	$objResponse->assign('div_ceca07idactividad', 'innerHTML', $html_ceca07idactividad);
	return $objResponse;
	}
function f2407_Comboceca07idactividad($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$objCombos=new clsHtmlCombos('n');
	$html_ceca07idactividad=f2407_HTMLComboV2_ceca07idactividad($objDB, $objCombos, '', $aParametros[0], $aParametros[1], $aParametros[2]);
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_ceca07idactividad', 'innerHTML', $html_ceca07idactividad);
	return $objResponse;
	}
function f2407_ExisteDato($datos){
	if(!is_array($datos)){$datos=json_decode(str_replace('\"','"',$datos),true);}
	$_SESSION['u_ultimominuto']=iminutoavance();
	$bHayLlave=true;
	$ceca07consec=numeros_validar($datos[1]);
	if ($ceca07consec==''){$bHayLlave=false;}
	$ceca07serie=numeros_validar($datos[2]);
	if ($ceca07serie==''){$bHayLlave=false;}
	if ($bHayLlave){
		require './app.php';
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		$sSQL='SELECT ceca07serie FROM ceca07solicitudrecal WHERE ceca07consec='.$ceca07consec.' AND ceca07serie='.$ceca07serie.'';
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
function f2407_Busquedas($aParametros){
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2407=$APP->rutacomun.'lg/lg_2407_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2407)){$mensajes_2407=$APP->rutacomun.'lg/lg_2407_es.php';}
	require $mensajes_todas;
	require $mensajes_2407;
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
		case 'ceca07idestudiante':
		require $APP->rutacomun.'lib111.php';
		$sTabla=f111_TablaDetalleBusquedas($aParametrosB, $objDB);
		$sTitulo='Busqueda de terceros';
		$sParams=f111_ParametrosBusqueda();
		$sJavaBusqueda=f111_JavaScriptBusqueda(2407);
		break;
		case 'ceca07idsolicita':
		require $APP->rutacomun.'lib111.php';
		$sTabla=f111_TablaDetalleBusquedas($aParametrosB, $objDB);
		$sTitulo='Busqueda de terceros';
		$sParams=f111_ParametrosBusqueda();
		$sJavaBusqueda=f111_JavaScriptBusqueda(2407);
		break;
		}
	$sTitulo='<h2>'.$ETI['titulo_2407'].' - '.$sTitulo.'</h2>';
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97titulo', 'innerHTML', $sTitulo);
	$objResponse->assign('div_97params', 'innerHTML', $sParams);
	$objResponse->assign('div_97tabla', 'innerHTML', $sTabla);
	$objResponse->setFunction('paginarbusqueda','',$sJavaBusqueda);
	$objResponse->call('expandesector(97)');
	return $objResponse;
	}
function f2407_HtmlBusqueda($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$sError='';
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$sDetalle='';
	switch($aParametros[100]){
		case 'ceca07idestudiante':
		require $APP->rutacomun.'lib111.php';
		$sDetalle=f111_TablaDetalleBusquedas($aParametros, $objDB);
		break;
		case 'ceca07idsolicita':
		require $APP->rutacomun.'lib111.php';
		$sDetalle=f111_TablaDetalleBusquedas($aParametros, $objDB);
		break;
		}
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97tabla', 'innerHTML', $sDetalle);
	return $objResponse;
	}
function f2407_TablaDetalleV2($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2407=$APP->rutacomun.'lg/lg_2407_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2407)){$mensajes_2407=$APP->rutacomun.'lg/lg_2407_es.php';}
	require $mensajes_todas;
	require $mensajes_2407;
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	if (isset($aParametros[101])==0){$aParametros[101]=1;}
	if (isset($aParametros[102])==0){$aParametros[102]=20;}
	//if (isset($aParametros[103])==0){$aParametros[103]='';}
	//$aParametros[103]=numeros_validar($aParametros[103]);
	$sDebug='';
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
	//if ((int)$aParametros[103]!=-1){$sSQLadd=$sSQLadd.' AND TB.campo='.$aParametros[103];}
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
	$sTitulos='Consec, Serie, Id, Estado, Estudiante, Peraca, Curso, Actividad, Grupo, Tutor, Presentada, Notaactual, Solicita, Rolsolicita, Fechaactualiza, Minutoactualiza, Notafinal, Motivorechazo, Fechasol, Motivosol';
	$sSQL='SELECT TB.ceca07consec, TB.ceca07serie, TB.ceca07id, T4.ceca14nombre, T5.unad11razonsocial AS C5_nombre, TB.ceca07idgrupo, TB.ceca07idtutor, TB.ceca07presentada, TB.ceca07notaactual, TB.ceca07fechaactualiza, TB.ceca07minutoactualiza, TB.ceca07notafinal, TB.ceca07motivorechazo, TB.ceca07fechasol, TB.ceca07estado, TB.ceca07idestudiante, T5.unad11tipodoc AS C5_td, T5.unad11doc AS C5_doc, TB.ceca07idperaca, TB.ceca07idcurso, TB.ceca07idactividad, TB.ceca07idsolicita, TB.ceca07idrolsolicita, TB.ceca07idmotivosol 
FROM ceca07solicitudrecal AS TB, ceca14estadorecal AS T4, unad11terceros AS T5 
WHERE '.$sSQLadd1.' TB.ceca07serie=0 AND TB.ceca07estado=T4.ceca14id AND TB.ceca07idestudiante=T5.unad11id '.$sSQLadd.'
ORDER BY TB.ceca07estado, TB.ceca07idperaca DESC, TB.ceca07consec';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_2407" name="consulta_2407" type="hidden" value="'.$sSQLlista.'"/>
<input id="titulos_2407" name="titulos_2407" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objDB->ejecutasql($sSQL);
	if ($tabladetalle==false){
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 2407: '.$sSQL.'<br>';}
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		//$sLeyenda=$sSQL;
		}else{
		$registros=$objDB->nf($tabladetalle);
		if ($registros==0){
			//return array(utf8_encode($sErrConsulta.'<input id="paginaf2407" name="paginaf2407" type="hidden" value="'.$pagina.'"/><input id="lppf2407" name="lppf2407" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
<td><b>'.$ETI['ceca07consec'].'</b></td>
<td colspan="2"><b>'.$ETI['ceca07idestudiante'].'</b></td>
<td><b>'.$ETI['ceca07estado'].'</b></td>
<td><b>'.$ETI['ceca07fechasol'].'</b></td>
<td><b>'.$ETI['ceca07idmotivosol'].'</b></td>
<td align="right">
'.html_paginador('paginaf2407', $registros, $lineastabla, $pagina, 'paginarf2407()').'
'.html_lpp('lppf2407', $lineastabla, 'paginarf2407()').'
</td>
</tr>';
	$tlinea=1;
	while($filadet=$objDB->sf($tabladetalle)){
		$sPrefijo='';
		$sSufijo='';
		$sClass='';
		$sLink='';
		if (false){
			$sPrefijo='<b>';
			$sSufijo='</b>';
			}
		if(($tlinea%2)==0){$sClass=' class="resaltetabla"';}
		$tlinea++;
		$et_ceca07fechaactualiza='';
		if ($filadet['ceca07fechaactualiza']!=0){$et_ceca07fechaactualiza=fecha_desdenumero($filadet['ceca07fechaactualiza']);}
		$et_ceca07fechasol='';
		if ($filadet['ceca07fechasol']!=0){$et_ceca07fechasol=fecha_desdenumero($filadet['ceca07fechasol']);}
		if ($babierta){
			$sLink='<a href="javascript:cargaridf2407('.$filadet['ceca07id'].')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$res=$res.'<tr'.$sClass.'>
<td>'.$sPrefijo.$filadet['ceca07consec'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['C5_td'].' '.$filadet['C5_doc'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['C5_nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['ceca14nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.$et_ceca07fechasol.$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['ceca11nombre']).$sSufijo.'</td>
<td>'.$sLink.'</td>
</tr>';
		}
	$res=$res.'</table>';
	$objDB->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
	}
function f2407_HtmlTabla($aParametros){
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
	list($sDetalle, $sDebugTabla)=f2407_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f2407detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f2407_db_CargarPadre($DATA, $objDB, $bDebug=false){
	$sError='';
	$iTipoError=0;
	$sDebug='';
	require './app.php';
	$DATA['ceca07idestudiante_td']=$APP->tipo_doc;
	$DATA['ceca07idestudiante_doc']='';
	$DATA['ceca07idsolicita_td']=$APP->tipo_doc;
	$DATA['ceca07idsolicita_doc']='';
	if ($DATA['paso']==1){
		$sSQLcondi='ceca07consec='.$DATA['ceca07consec'].' AND ceca07serie='.$DATA['ceca07serie'].'';
		}else{
		$sSQLcondi='ceca07id='.$DATA['ceca07id'].'';
		}
	$sSQL='SELECT * FROM ceca07solicitudrecal WHERE '.$sSQLcondi;
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$DATA['ceca07consec']=$fila['ceca07consec'];
		$DATA['ceca07serie']=$fila['ceca07serie'];
		$DATA['ceca07id']=$fila['ceca07id'];
		$DATA['ceca07estado']=$fila['ceca07estado'];
		$DATA['ceca07idestudiante']=$fila['ceca07idestudiante'];
		$DATA['ceca07idperaca']=$fila['ceca07idperaca'];
		$DATA['ceca07idcurso']=$fila['ceca07idcurso'];
		$DATA['ceca07idactividad']=$fila['ceca07idactividad'];
		$DATA['ceca07idgrupo']=$fila['ceca07idgrupo'];
		$DATA['ceca07idtutor']=$fila['ceca07idtutor'];
		$DATA['ceca07presentada']=$fila['ceca07presentada'];
		$DATA['ceca07notaactual']=$fila['ceca07notaactual'];
		$DATA['ceca07idsolicita']=$fila['ceca07idsolicita'];
		$DATA['ceca07idrolsolicita']=$fila['ceca07idrolsolicita'];
		$DATA['ceca07fechaactualiza']=$fila['ceca07fechaactualiza'];
		$DATA['ceca07minutoactualiza']=$fila['ceca07minutoactualiza'];
		$DATA['ceca07notafinal']=$fila['ceca07notafinal'];
		$DATA['ceca07motivorechazo']=$fila['ceca07motivorechazo'];
		$DATA['ceca07fechasol']=$fila['ceca07fechasol'];
		$DATA['ceca07idmotivosol']=$fila['ceca07idmotivosol'];
		$bcargo=true;
		$DATA['paso']=2;
		$DATA['boculta2407']=0;
		$bLimpiaHijos=true;
		}else{
		$DATA['paso']=0;
		}
	return array($DATA, $sError, $iTipoError, $sDebug);
	}
function f2407_db_GuardarV2($DATA, $objDB, $bDebug=false){
	$iCodModulo=2407;
	$bAudita[2]=true;
	$bAudita[3]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2407=$APP->rutacomun.'lg/lg_2407_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2407)){$mensajes_2407=$APP->rutacomun.'lg/lg_2407_es.php';}
	require $mensajes_todas;
	require $mensajes_2407;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	// -- Se inicia validando todas las posibles entradas de usuario.
	/*
	if (isset($DATA['ceca07consec'])==0){$DATA['ceca07consec']='';}
	if (isset($DATA['ceca07serie'])==0){$DATA['ceca07serie']='';}
	if (isset($DATA['ceca07id'])==0){$DATA['ceca07id']='';}
	if (isset($DATA['ceca07idestudiante'])==0){$DATA['ceca07idestudiante']='';}
	if (isset($DATA['ceca07idperaca'])==0){$DATA['ceca07idperaca']='';}
	if (isset($DATA['ceca07idcurso'])==0){$DATA['ceca07idcurso']='';}
	if (isset($DATA['ceca07idactividad'])==0){$DATA['ceca07idactividad']='';}
	if (isset($DATA['ceca07fechaactualiza'])==0){$DATA['ceca07fechaactualiza']='';}
	if (isset($DATA['ceca07motivorechazo'])==0){$DATA['ceca07motivorechazo']='';}
	if (isset($DATA['ceca07fechasol'])==0){$DATA['ceca07fechasol']='';}
	if (isset($DATA['ceca07idmotivosol'])==0){$DATA['ceca07idmotivosol']='';}
	*/
	$DATA['ceca07consec']=numeros_validar($DATA['ceca07consec']);
	$DATA['ceca07serie']=numeros_validar($DATA['ceca07serie']);
	$DATA['ceca07idperaca']=numeros_validar($DATA['ceca07idperaca']);
	$DATA['ceca07idcurso']=numeros_validar($DATA['ceca07idcurso']);
	$DATA['ceca07idactividad']=numeros_validar($DATA['ceca07idactividad']);
	$DATA['ceca07motivorechazo']=htmlspecialchars(trim($DATA['ceca07motivorechazo']));
	$DATA['ceca07idmotivosol']=numeros_validar($DATA['ceca07idmotivosol']);
	// -- Se inicializan las variables que puedan pasar vacias {Especialmente números}.
	if ($DATA['ceca07estado']==''){$DATA['ceca07estado']=0;}
	//if ($DATA['ceca07idperaca']==''){$DATA['ceca07idperaca']=0;}
	//if ($DATA['ceca07idcurso']==''){$DATA['ceca07idcurso']=0;}
	//if ($DATA['ceca07idactividad']==''){$DATA['ceca07idactividad']=0;}
	if ($DATA['ceca07idgrupo']==''){$DATA['ceca07idgrupo']=0;}
	if ($DATA['ceca07idtutor']==''){$DATA['ceca07idtutor']=0;}
	if ($DATA['ceca07presentada']==''){$DATA['ceca07presentada']=0;}
	if ($DATA['ceca07notaactual']==''){$DATA['ceca07notaactual']=0;}
	if ($DATA['ceca07idrolsolicita']==''){$DATA['ceca07idrolsolicita']=0;}
	if ($DATA['ceca07minutoactualiza']==''){$DATA['ceca07minutoactualiza']=0;}
	if ($DATA['ceca07notafinal']==''){$DATA['ceca07notafinal']=0;}
	// -- Seccion para validar los posibles causales de error.
	$sSepara=', ';
	if (true){
		if ($DATA['ceca07idmotivosol']==''){$sError=$ERR['ceca07idmotivosol'].$sSepara.$sError;}
		if ($DATA['ceca07fechasol']==0){
			//$DATA['ceca07fechasol']=fecha_DiaMod();
			$sError=$ERR['ceca07fechasol'].$sSepara.$sError;
			}
		if ($DATA['ceca07estado']==9){
			if ($DATA['ceca07motivorechazo']==''){$sError=$ERR['ceca07motivorechazo'].$sSepara.$sError;}
			}
		if ($DATA['ceca07fechaactualiza']==0){
			//$DATA['ceca07fechaactualiza']=fecha_DiaMod();
			//$sError=$ERR['ceca07fechaactualiza'].$sSepara.$sError;
			}
		if ($DATA['ceca07idsolicita']==0){$sError=$ERR['ceca07idsolicita'].$sSepara.$sError;}
		if ($DATA['ceca07idactividad']==''){$sError=$ERR['ceca07idactividad'].$sSepara.$sError;}
		if ($DATA['ceca07idcurso']==''){$sError=$ERR['ceca07idcurso'].$sSepara.$sError;}
		if ($DATA['ceca07idperaca']==''){$sError=$ERR['ceca07idperaca'].$sSepara.$sError;}
		if ($DATA['ceca07idestudiante']==0){$sError=$ERR['ceca07idestudiante'].$sSepara.$sError;}
		//Fin de las valiaciones NO LLAVE.
		}
	//Valiaciones de campos obligatorios en todo guardar.
	if ($DATA['ceca07serie']==''){$sError=$ERR['ceca07serie'];}
	// -- Se verifican los valores de campos de otras tablas.
	if ($DATA['ceca07idsolicita_doc']!=''){
		if ($sError==''){$sError=tabla_terceros_existe($DATA['ceca07idsolicita_td'], $DATA['ceca07idsolicita_doc'], $objDB, 'El tercero Solicita ');}
		if ($sError==''){
			list($sError, $sInfo)=tercero_Bloqueado($DATA['ceca07idsolicita'], $objDB);
			if ($sInfo!=''){$sError=$sError.'<br>'.sInfo;}
			}
		}
	if ($DATA['ceca07idestudiante_doc']!=''){
		if ($sError==''){$sError=tabla_terceros_existe($DATA['ceca07idestudiante_td'], $DATA['ceca07idestudiante_doc'], $objDB, 'El tercero Estudiante ');}
		if ($sError==''){
			list($sError, $sInfo)=tercero_Bloqueado($DATA['ceca07idestudiante'], $objDB);
			if ($sInfo!=''){$sError=$sError.'<br>'.sInfo;}
			}
		}
	if ($sError==''){
		if ($DATA['paso']==10){
			if ($DATA['ceca07consec']==''){
				$DATA['ceca07consec']=tabla_consecutivo('ceca07solicitudrecal', 'ceca07consec', 'ceca07serie='.$DATA['ceca07serie'].'', $objDB);
				if ($DATA['ceca07consec']==-1){$sError=$objDB->serror;}
				}else{
				if (!seg_revisa_permiso($iCodModulo, 8, $objDB)){
					$sError=$ERR['8'];
					$DATA['ceca07consec']='';
					}
				}
			if ($sError==''){
				$sSQL='SELECT ceca07consec FROM ceca07solicitudrecal WHERE ceca07consec='.$DATA['ceca07consec'].' AND ceca07serie='.$DATA['ceca07serie'].'';
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
			$DATA['ceca07id']=tabla_consecutivo('ceca07solicitudrecal','ceca07id', '', $objDB);
			if ($DATA['ceca07id']==-1){$sError=$objDB->serror;}
			}
		}
	if ($sError==''){
		$bpasa=false;
		if ($DATA['paso']==10){
			$DATA['ceca07estado']=0;
			$DATA['ceca07idgrupo']=0;
			$DATA['ceca07idtutor']=0;
			$DATA['ceca07presentada']=0;
			$DATA['ceca07notaactual']=0;
			//$DATA['ceca07idsolicita']=0; //$_SESSION['u_idtercero'];
			$DATA['ceca07idrolsolicita']=0;
			$ceca07fechaactualiza=0;
			$DATA['ceca07minutoactualiza']=0;
			$DATA['ceca07notafinal']=0;
			$sCampos2407='ceca07consec, ceca07serie, ceca07id, ceca07estado, ceca07idestudiante, ceca07idperaca, ceca07idcurso, ceca07idactividad, ceca07idgrupo, ceca07idtutor, 
ceca07presentada, ceca07notaactual, ceca07idsolicita, ceca07idrolsolicita, ceca07fechaactualiza, ceca07minutoactualiza, ceca07notafinal, ceca07motivorechazo, ceca07fechasol, ceca07idmotivosol';
			$sValores2407=''.$DATA['ceca07consec'].', '.$DATA['ceca07serie'].', '.$DATA['ceca07id'].', '.$DATA['ceca07estado'].', '.$DATA['ceca07idestudiante'].', '.$DATA['ceca07idperaca'].', '.$DATA['ceca07idcurso'].', '.$DATA['ceca07idactividad'].', '.$DATA['ceca07idgrupo'].', '.$DATA['ceca07idtutor'].', 
'.$DATA['ceca07presentada'].', '.$DATA['ceca07notaactual'].', '.$DATA['ceca07idsolicita'].', '.$DATA['ceca07idrolsolicita'].', "'.$DATA['ceca07fechaactualiza'].'", '.$DATA['ceca07minutoactualiza'].', '.$DATA['ceca07notafinal'].', "'.$DATA['ceca07motivorechazo'].'", "'.$DATA['ceca07fechasol'].'", '.$DATA['ceca07idmotivosol'].'';
			if ($APP->utf8==1){
				$sSQL='INSERT INTO ceca07solicitudrecal ('.$sCampos2407.') VALUES ('.utf8_encode($sValores2407).');';
				$sdetalle=$sCampos2407.'['.utf8_encode($sValores2407).']';
				}else{
				$sSQL='INSERT INTO ceca07solicitudrecal ('.$sCampos2407.') VALUES ('.$sValores2407.');';
				$sdetalle=$sCampos2407.'['.$sValores2407.']';
				}
			$idaccion=2;
			$bpasa=true;
			}else{
			$scampo[1]='ceca07idestudiante';
			$scampo[2]='ceca07idperaca';
			$scampo[3]='ceca07idcurso';
			$scampo[4]='ceca07idactividad';
			$scampo[5]='ceca07fechaactualiza';
			$scampo[6]='ceca07motivorechazo';
			$sdato[1]=$DATA['ceca07idestudiante'];
			$sdato[2]=$DATA['ceca07idperaca'];
			$sdato[3]=$DATA['ceca07idcurso'];
			$sdato[4]=$DATA['ceca07idactividad'];
			$sdato[5]=$DATA['ceca07fechaactualiza'];
			$sdato[6]=$DATA['ceca07motivorechazo'];
			$numcmod=6;
			$sWhere='ceca07id='.$DATA['ceca07id'].'';
			$sSQL='SELECT * FROM ceca07solicitudrecal WHERE '.$sWhere;
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
					$sSQL='UPDATE ceca07solicitudrecal SET '.utf8_encode($sdatos).' WHERE '.$sWhere.';';
					}else{
					$sdetalle=$sdatos.'['.$sWhere.']';
					$sSQL='UPDATE ceca07solicitudrecal SET '.$sdatos.' WHERE '.$sWhere.';';
					}
				$idaccion=3;
				}
			}
		if ($bpasa){
			$result=$objDB->ejecutasql($sSQL);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' [2407] ..<!-- '.$sSQL.' -->';
				if ($idaccion==2){$DATA['ceca07id']='';}
				$DATA['paso']=$DATA['paso']-10;
				}else{
				if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Guardar 2407 '.$sSQL.'<br>';}
				if ($bAudita[$idaccion]){seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], $idaccion, $DATA['ceca07id'], $sdetalle, $objDB);}
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
function f2407_db_Eliminar($ceca07id, $objDB, $bDebug=false){
	$iCodModulo=2407;
	$bAudita[4]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2407=$APP->rutacomun.'lg/lg_2407_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2407)){$mensajes_2407=$APP->rutacomun.'lg/lg_2407_es.php';}
	require $mensajes_todas;
	require $mensajes_2407;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	$ceca07id=numeros_validar($ceca07id);
	// Traer los datos para hacer las validaciones.
	if ($sError==''){
		$sSQL='SELECT * FROM ceca07solicitudrecal WHERE ceca07id='.$ceca07id.'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$filabase=$objDB->sf($tabla);
			}else{
			$sError='No se encuentra el registro solicitado {Ref: '.$ceca07id.'}';
			}
		}
	if ($sError==''){
		if (!seg_revisa_permiso($iCodModulo, 4, $objDB)){
			$sError=$ERR['4'];
			}
		}
	if ($sError==''){
		$sSQL='SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=2407';
		$tablaor=$objDB->ejecutasql($sSQL);
		while ($filaor=$objDB->sf($tablaor)){
			$sSQL='SELECT '.$filaor['campoid'].' FROM '.$filaor['tabla'].' WHERE '.$filaor['camporev'].'='.$_REQUEST['ceca07id'].' LIMIT 0, 1';
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
		$sWhere='ceca07id='.$ceca07id.'';
		//$sWhere='ceca07serie='.$filabase['ceca07serie'].' AND ceca07consec='.$filabase['ceca07consec'].'';
		$sSQL='DELETE FROM ceca07solicitudrecal WHERE '.$sWhere.';';
		$result=$objDB->ejecutasql($sSQL);
		if ($result==false){
			$sError=$ERR['falla_eliminar'].' .. <!-- '.$sSQL.' -->';
			}else{
			if ($bAudita[4]){seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $ceca07id, $sWhere, $objDB);}
			}
		}
	return array($sError, $iTipoError, $sDebug);
	}
function f2407_TituloBusqueda(){
	return 'Busqueda de Solicitudes de recalificacion';
	}
function f2407_ParametrosBusqueda(){
	$sParams='<label class="Label90">Nombre</label><label><input id="b2407nombre" name="b2407nombre" type="text" value="" onchange="paginarbusqueda()" /></label>';
	return $sParams;
	}
function f2407_JavaScriptBusqueda($iModuloBusca){
	$sRes='var sCampo=window.document.frmedita.scampobusca.value;
var params=new Array();
params[100]=sCampo;
params[101]=window.document.frmedita.paginabusqueda.value;
params[102]=window.document.frmedita.lppfbusqueda.value;
params[103]=window.document.frmedita.b2407nombre.value;
xajax_f'.$iModuloBusca.'_HtmlBusqueda(params);';
	return $sRes;
	}
function f2407_TablaDetalleBusquedas($aParametros, $objDB){
	$res='';
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2407=$APP->rutacomun.'lg/lg_2407_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2407)){$mensajes_2407=$APP->rutacomun.'lg/lg_2407_es.php';}
	require $mensajes_todas;
	require $mensajes_2407;
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	if (isset($aParametros[101])==0){$aParametros[101]=1;}
	if (isset($aParametros[102])==0){$aParametros[102]=20;}
	if (isset($aParametros[103])==0){$aParametros[103]='';}
	//$aParametros[103]=numeros_validar($aParametros[103]);
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
	$sTitulos='Consec, Serie, Id, Estado, Estudiante, Peraca, Curso, Actividad, Grupo, Tutor, Presentada, Notaactual, Solicita, Rolsolicita, Fechaactualiza, Minutoactualiza, Notafinal, Motivorechazo';
	$sSQL='SELECT TB.ceca07consec, TB.ceca07serie, TB.ceca07id, T4.ceca14nombre, T5.unad11razonsocial AS C5_nombre, T6.exte02nombre, T7.unad40nombre, T8.ofer04nombre, TB.ceca07idgrupo, TB.ceca07idtutor, TB.ceca07presentada, TB.ceca07notaactual, T13.unad11razonsocial AS C13_nombre, T14.ceca13nombre, TB.ceca07fechaactualiza, TB.ceca07minutoactualiza, TB.ceca07notafinal, TB.ceca07motivorechazo, TB.ceca07estado, TB.ceca07idestudiante, T5.unad11tipodoc AS C5_td, T5.unad11doc AS C5_doc, TB.ceca07idperaca, TB.ceca07idcurso, TB.ceca07idactividad, TB.ceca07idsolicita, T13.unad11tipodoc AS C13_td, T13.unad11doc AS C13_doc, TB.ceca07idrolsolicita 
FROM ceca07solicitudrecal AS TB, ceca14estadorecal AS T4, unad11terceros AS T5, exte02per_aca AS T6, unad40curso AS T7, ofer04cursoactividad AS T8, unad11terceros AS T13, ceca13rolsolicita AS T14 
WHERE '.$sSQLadd1.' TB.ceca07estado=T4.ceca14id AND TB.ceca07idestudiante=T5.unad11id AND TB.ceca07idperaca=T6.exte02id AND TB.ceca07idcurso=T7.unad40id AND TB.ceca07idactividad=T8.ofer04id AND TB.ceca07idsolicita=T13.unad11id AND TB.ceca07idrolsolicita=T14.ceca13id '.$sSQLadd.'
ORDER BY TB.ceca07consec, TB.ceca07serie';
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
			//return array(utf8_encode($sErrConsulta.'<input id="paginaf2407" name="paginaf2407" type="hidden" value="'.$pagina.'"/><input id="lppf2407" name="lppf2407" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
<td><b>'.$ETI['ceca07consec'].'</b></td>
<td><b>'.$ETI['ceca07serie'].'</b></td>
<td><b>'.$ETI['ceca07estado'].'</b></td>
<td colspan="2"><b>'.$ETI['ceca07idestudiante'].'</b></td>
<td><b>'.$ETI['ceca07idperaca'].'</b></td>
<td><b>'.$ETI['ceca07idcurso'].'</b></td>
<td><b>'.$ETI['ceca07idactividad'].'</b></td>
<td><b>'.$ETI['ceca07idgrupo'].'</b></td>
<td><b>'.$ETI['ceca07idtutor'].'</b></td>
<td><b>'.$ETI['ceca07presentada'].'</b></td>
<td><b>'.$ETI['ceca07notaactual'].'</b></td>
<td colspan="2"><b>'.$ETI['ceca07idsolicita'].'</b></td>
<td><b>'.$ETI['ceca07idrolsolicita'].'</b></td>
<td><b>'.$ETI['ceca07fechaactualiza'].'</b></td>
<td><b>'.$ETI['ceca07minutoactualiza'].'</b></td>
<td><b>'.$ETI['ceca07notafinal'].'</b></td>
<td><b>'.$ETI['ceca07motivorechazo'].'</b></td>
<td align="right">
'.html_paginador('paginabusqueda', $registros, $lineastabla, $pagina, 'paginarbusqueda()').'
'.html_lpp('lppfbusqueda', $lineastabla, 'paginarbusqueda()').'
</td>
</tr>';
	$tlinea=1;
	while($filadet=$objDB->sf($tabladetalle)){
		$sPrefijo='<a href="javascript:Devuelve(\''.$filadet['ceca07id'].'\');">';
		$sSufijo='</a>';
		$tlinea++;
		$et_ceca07fechaactualiza='';
		if ($filadet['ceca07fechaactualiza']!=0){$et_ceca07fechaactualiza=fecha_desdenumero($filadet['ceca07fechaactualiza']);}
		$res=$res.'<tr onmouseover="cambia_color_over(this);" onmouseout="cambia_color_out(this);">
<td>'.$sPrefijo.$filadet['ceca07consec'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['ceca07serie'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['ceca14nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['C5_td'].' '.$filadet['C5_doc'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['C5_nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['exte02nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['unad40nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['ofer04nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['ceca07idgrupo'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['ceca07idtutor'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['ceca07presentada'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['ceca07notaactual'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['C13_td'].' '.$filadet['C13_doc'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['C13_nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['ceca13nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.$et_ceca07fechaactualiza.$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['ceca07minutoactualiza'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['ceca07notafinal'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['ceca07motivorechazo']).$sSufijo.'</td>
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