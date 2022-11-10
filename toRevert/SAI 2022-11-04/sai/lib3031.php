<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2020 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.25.4 martes, 4 de agosto de 2020
--- 3031 saiu31baseconocimiento
*/
/** Archivo lib3031.php.
* Libreria 3031 saiu31baseconocimiento.
* @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
* @date martes, 4 de agosto de 2020
*/
function f3031_HTMLComboV2_saiu31idtemageneral($objDB, $objCombos, $valor, $vrsaiu31idunidadresp){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$vrsaiu31idunidadresp=numeros_validar($vrsaiu31idunidadresp);
	$objCombos->nuevo('saiu31idtemageneral', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	//$objCombos->iAncho=450;
	$sCondi='';
	$sTablas='';
	$sCondi2='';
	$sCampos='';
	if ($vrsaiu31idunidadresp!=''){
		$sCondi='TB.saiu03idunidadresp1='.$vrsaiu31idunidadresp.' AND ';
		}else{
		$sTablas=', unae26unidadesfun AS T2';
		$sCondi2=' AND TB.saiu03idunidadresp1=T2.unae26id ';
		$sCampos=', " [", T2.unae26nombre, "]"';
		}
	$sSQL='SELECT TB.saiu03id AS id, CONCAT(TB.saiu03titulo'.$sCampos.') AS nombre FROM saiu03temasol AS TB'.$sTablas.' WHERE '.$sCondi.' TB.saiu03id>0 '.$sCondi2.' ORDER BY TB.saiu03titulo';
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f3031_Combosaiu31idtemageneral($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$objCombos=new clsHtmlCombos();
	$html_saiu31idtemageneral=f3031_HTMLComboV2_saiu31idtemageneral($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_saiu31idtemageneral', 'innerHTML', $html_saiu31idtemageneral);
	$objResponse->call('$("#saiu31idtemageneral").chosen()');
	return $objResponse;
	}
function f3031_ExisteDato($datos){
	if(!is_array($datos)){$datos=json_decode(str_replace('\"','"',$datos),true);}
	$_SESSION['u_ultimominuto']=iminutoavance();
	$bHayLlave=true;
	$saiu31consec=numeros_validar($datos[1]);
	if ($saiu31consec==''){$bHayLlave=false;}
	$saiu31version=numeros_validar($datos[2]);
	if ($saiu31version==''){$bHayLlave=false;}
	if ($bHayLlave){
		require './app.php';
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		$sSQL='SELECT 1 FROM saiu31baseconocimiento WHERE saiu31consec='.$saiu31consec.' AND saiu31version='.$saiu31version.'';
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
function f3031_Busquedas($aParametros){
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3031='lg/lg_3031_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3031)){$mensajes_3031='lg/lg_3031_es.php';}
	require $mensajes_todas;
	require $mensajes_3031;
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
		case 'saiu31usuario':
		require $APP->rutacomun.'lib111.php';
		$sTabla=f111_TablaDetalleBusquedas($aParametrosB, $objDB);
		$sTitulo='Busqueda de terceros';
		$sParams=f111_ParametrosBusqueda();
		$sJavaBusqueda=f111_JavaScriptBusqueda(3031);
		break;
		case 'saiu31usuarioaprueba':
		require $APP->rutacomun.'lib111.php';
		$sTabla=f111_TablaDetalleBusquedas($aParametrosB, $objDB);
		$sTitulo='Busqueda de terceros';
		$sParams=f111_ParametrosBusqueda();
		$sJavaBusqueda=f111_JavaScriptBusqueda(3031);
		break;
		case 'saiu38usuario':
		require $APP->rutacomun.'lib111.php';
		$sTabla=f111_TablaDetalleBusquedas($aParametrosB, $objDB);
		$sTitulo='Busqueda de terceros';
		$sParams=f111_ParametrosBusqueda();
		$sJavaBusqueda=f111_JavaScriptBusqueda(3031);
		break;
		}
	$sTitulo='<h2>'.$ETI['titulo_3031'].' - '.$sTitulo.'</h2>';
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97titulo', 'innerHTML', $sTitulo);
	$objResponse->assign('div_97params', 'innerHTML', $sParams);
	$objResponse->assign('div_97tabla', 'innerHTML', $sTabla);
	$objResponse->setFunction('paginarbusqueda','',$sJavaBusqueda);
	$objResponse->call('expandesector(97)');
	return $objResponse;
	}
function f3031_HtmlBusqueda($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$sError='';
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$sDetalle='';
	switch($aParametros[100]){
		case 'saiu31usuario':
		require $APP->rutacomun.'lib111.php';
		$sDetalle=f111_TablaDetalleBusquedas($aParametros, $objDB);
		break;
		case 'saiu31usuarioaprueba':
		require $APP->rutacomun.'lib111.php';
		$sDetalle=f111_TablaDetalleBusquedas($aParametros, $objDB);
		break;
		case 'saiu38usuario':
		require $APP->rutacomun.'lib111.php';
		$sDetalle=f111_TablaDetalleBusquedas($aParametros, $objDB);
		break;
		}
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97tabla', 'innerHTML', $sDetalle);
	return $objResponse;
	}
function f3031_TablaDetalleV2($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3031='lg/lg_3031_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3031)){$mensajes_3031='lg/lg_3031_es.php';}
	require $mensajes_todas;
	require $mensajes_3031;
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	if (isset($aParametros[100])==0){$aParametros[100]=$_SESSION['unad_id_tercero'];}
	if (isset($aParametros[101])==0){$aParametros[101]=1;}
	if (isset($aParametros[102])==0){$aParametros[102]=20;}
	if (isset($aParametros[103])==0){$aParametros[103]='';}
	if (isset($aParametros[104])==0){$aParametros[104]='';}
	if (isset($aParametros[105])==0){$aParametros[105]='';}
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
		return array($sLeyenda.'<input id="paginaf3031" name="paginaf3031" type="hidden" value="'.$pagina.'"/><input id="lppf3031" name="lppf3031" type="hidden" value="'.$lineastabla.'"/>', $sDebug);
		die();
		}
	$aEstado=array();
	$sSQL='SELECT saiu36id, saiu36nombre FROM saiu36estadobase';
	$tabla=$objDB->ejecutasql($sSQL);
	while($fila=$objDB->sf($tabla)){
		$aEstado[$fila['saiu36id']]=cadena_notildes($fila['saiu36nombre']);
		}
	$sSQLadd='';
	$sSQLadd1='';
	//if ((int)$aParametros[103]!=-1){$sSQLadd=$sSQLadd.' AND TB.campo='.$aParametros[103];}
	if ($aParametros[105]!=''){$sSQLadd1=$sSQLadd1.'TB.saiu31estado='.$aParametros[105].' AND ';}
	switch($aParametros[105]){
		case 1:
		$sSQLadd1=$sSQLadd1.'TB.saiu31usuario='.$idTercero.' AND ';
		break;
		}
	if ($aParametros[103]!=''){
		$sBase=trim(strtoupper($aParametros[103]));
		$aNoms=explode(' ', $sBase);
		for ($k=1;$k<=count($aNoms);$k++){
			$sCadena=$aNoms[$k-1];
			if ($sCadena!=''){
				$sSQLadd=$sSQLadd.' AND TB.saiu31titulo LIKE "%'.$sCadena.'%"';
				//$sSQLadd1=$sSQLadd1.'T1.unad11razonsocial LIKE "%'.$sCadena.'%" AND ';
				}
			}
		}
	$sTitulos='Consec, Version, Id, Estado, Temageneral, Titulo, Resumen, Contenido, Unidadresp, Temporal, Fechaini, Fechafin, Cobertura, Entornodeuso, Aplicaaspirante, Aplicaestudiante, Aplicaegresado, Aplicadocentes, Aplicaadministra, Aplicaotros, Enlaceinfo, Enlaceproceso, Aplicanotificacion, Fechaparanotificar, Prioridadnotifica, Usuario, Fecha, Usuarioaprueba, Fechaaprueba';
	$registros=0;
	$bGigante=false; //En caso de que la tabla sea muy grande pasarlo a true
	$sLimite='';
	if ($bGigante){
		$sSQL='SELECT COUNT(1) AS Total 
		FROM saiu31baseconocimiento AS TB, saiu03temasol AS T5, unae26unidadesfun AS T9, saiu17nivelatencion AS T13, unad11terceros AS T26, unad11terceros AS T28 
		WHERE '.$sSQLadd1.' TB.saiu31idtemageneral=T5.saiu03id AND TB.saiu31idunidadresp=T9.unae26id AND TB.saiu31cobertura=T13.saiu17id AND TB.saiu31usuario=T26.unad11id AND TB.saiu31usuarioaprueba=T28.unad11id '.$sSQLadd.'';
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
	$sSQL='SELECT TB.saiu31consec, TB.saiu31id, TB.saiu31estado, TB.saiu31titulo, TB.saiu31resumen 
	FROM saiu31baseconocimiento AS TB 
	WHERE '.$sSQLadd1.' TB.saiu31version=0 '.$sSQLadd.'
	ORDER BY TB.saiu31titulo';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_3031" name="consulta_3031" type="hidden" value="'.$sSQLlista.'"/>
	<input id="titulos_3031" name="titulos_3031" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objDB->ejecutasql($sSQL.$sLimite);
	if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 3031: '.$sSQL.$sLimite.'<br>';}
	if ($tabladetalle==false){
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		//$sLeyenda=$sSQL;
		}else{
		if (!$bGigante){
			$registros=$objDB->nf($tabladetalle);
			if ($registros==0){
				//return array(utf8_encode($sErrConsulta.'<input id="paginaf3031" name="paginaf3031" type="hidden" value="'.$pagina.'"/><input id="lppf3031" name="lppf3031" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
	<td><b>'.$ETI['saiu31consec'].'</b></td>
	<td><b>'.$ETI['saiu31titulo'].'</b></td>
	<td><b>'.$ETI['saiu31estado'].'</b></td>
	<td align="right">
	'.html_paginador('paginaf3031', $registros, $lineastabla, $pagina, 'paginarf3031()').'
	'.html_lpp('lppf3031', $lineastabla, 'paginarf3031()').'
	</td>
	</tr></thead>';
	$tlinea=1;
	while($filadet=$objDB->sf($tabladetalle)){
		$sPrefijo='';
		$sSufijo='';
		$sClass=' class="resaltetabla"';
		$sLink='';
		switch($filadet['saiu31estado']){
			case 7:
			$sPrefijo='<b>';
			$sSufijo='</b>';
			break;
			case 8: //Rechazado
			$sPrefijo='<span class="rojo">';
			$sSufijo='</span>';
			break;
			}
		if(($tlinea%2)!=0){$sClass='';}
		$tlinea++;
		/*
		$et_saiu31estado=$ETI['msg_abierto'];
		if ($filadet['saiu31estado']=='S'){$et_saiu31estado=$ETI['msg_cerrado'];}
		$et_saiu31fechaini='';
		if ($filadet['saiu31fechaini']!=0){$et_saiu31fechaini=fecha_desdenumero($filadet['saiu31fechaini']);}
		$et_saiu31fechafin='';
		if ($filadet['saiu31fechafin']!=0){$et_saiu31fechafin=fecha_desdenumero($filadet['saiu31fechafin']);}
		$et_saiu31fechaparanotificar='';
		if ($filadet['saiu31fechaparanotificar']!=0){$et_saiu31fechaparanotificar=fecha_desdenumero($filadet['saiu31fechaparanotificar']);}
		$et_saiu31usuario_doc='';
		$et_saiu31usuario_nombre='';
		if ($filadet['saiu31usuario']!=0){
			$et_saiu31usuario_doc=$sPrefijo.$filadet['C26_td'].' '.$filadet['C26_doc'].$sSufijo;
			$et_saiu31usuario_nombre=$sPrefijo.cadena_notildes($filadet['C26_nombre']).$sSufijo;
			}
		$et_saiu31fecha='';
		if ($filadet['saiu31fecha']!=0){$et_saiu31fecha=fecha_desdenumero($filadet['saiu31fecha']);}
		$et_saiu31usuarioaprueba_doc='';
		$et_saiu31usuarioaprueba_nombre='';
		if ($filadet['saiu31usuarioaprueba']!=0){
			$et_saiu31usuarioaprueba_doc=$sPrefijo.$filadet['C28_td'].' '.$filadet['C28_doc'].$sSufijo;
			$et_saiu31usuarioaprueba_nombre=$sPrefijo.cadena_notildes($filadet['C28_nombre']).$sSufijo;
			}
		$et_saiu31fechaaprueba='';
		if ($filadet['saiu31fechaaprueba']!=0){$et_saiu31fechaaprueba=fecha_desdenumero($filadet['saiu31fechaaprueba']);}
		*/
		$et_saiu31estado=$aEstado[$filadet['saiu31estado']];
		if ($bAbierta){
			$sLink='<a href="javascript:cargaridf3031('.$filadet['saiu31id'].')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$res=$res.'<tr'.$sClass.'>
		<td>'.$sPrefijo.$filadet['saiu31consec'].$sSufijo.'</td>
		<td>'.$sPrefijo.cadena_notildes($filadet['saiu31titulo']).$sSufijo.'</td>
		<td>'.$sPrefijo.$et_saiu31estado.$sSufijo.'</td>
		<td>'.$sLink.'</td>
		</tr>';
		}
	$res=$res.'</table>';
	$objDB->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
	}
function f3031_HtmlTabla($aParametros){
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
	list($sDetalle, $sDebugTabla)=f3031_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f3031detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f3031_db_CargarPadre($DATA, $objDB, $bDebug=false){
	$sError='';
	$iTipoError=0;
	$sDebug='';
	require './app.php';
	$DATA['saiu31usuario_td']=$APP->tipo_doc;
	$DATA['saiu31usuario_doc']='';
	$DATA['saiu31usuarioaprueba_td']=$APP->tipo_doc;
	$DATA['saiu31usuarioaprueba_doc']='';
	if ($DATA['paso']==1){
		$sSQLcondi='saiu31consec='.$DATA['saiu31consec'].' AND saiu31version='.$DATA['saiu31version'].'';
		}else{
		$sSQLcondi='saiu31id='.$DATA['saiu31id'].'';
		}
	$sSQL='SELECT * FROM saiu31baseconocimiento WHERE '.$sSQLcondi;
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$DATA['saiu31consec']=$fila['saiu31consec'];
		$DATA['saiu31version']=$fila['saiu31version'];
		$DATA['saiu31id']=$fila['saiu31id'];
		$DATA['saiu31estado']=$fila['saiu31estado'];
		$DATA['saiu31idunidadresp']=$fila['saiu31idunidadresp'];
		$DATA['saiu31idtemageneral']=$fila['saiu31idtemageneral'];
		$DATA['saiu31titulo']=$fila['saiu31titulo'];
		$DATA['saiu31resumen']=$fila['saiu31resumen'];
		$DATA['saiu31contenido']=$fila['saiu31contenido'];
		$DATA['saiu31temporal']=$fila['saiu31temporal'];
		$DATA['saiu31fechaini']=$fila['saiu31fechaini'];
		$DATA['saiu31fechafin']=$fila['saiu31fechafin'];
		$DATA['saiu31cobertura']=$fila['saiu31cobertura'];
		$DATA['saiu31entornodeuso']=$fila['saiu31entornodeuso'];
		$DATA['saiu31aplicaaspirante']=$fila['saiu31aplicaaspirante'];
		$DATA['saiu31aplicaestudiante']=$fila['saiu31aplicaestudiante'];
		$DATA['saiu31aplicaegresado']=$fila['saiu31aplicaegresado'];
		$DATA['saiu31aplicadocentes']=$fila['saiu31aplicadocentes'];
		$DATA['saiu31aplicaadministra']=$fila['saiu31aplicaadministra'];
		$DATA['saiu31aplicaotros']=$fila['saiu31aplicaotros'];
		$DATA['saiu31enlaceinfo']=$fila['saiu31enlaceinfo'];
		$DATA['saiu31enlaceproceso']=$fila['saiu31enlaceproceso'];
		$DATA['saiu31aplicanotificacion']=$fila['saiu31aplicanotificacion'];
		$DATA['saiu31fechaparanotificar']=$fila['saiu31fechaparanotificar'];
		$DATA['saiu31prioridadnotifica']=$fila['saiu31prioridadnotifica'];
		$DATA['saiu31usuario']=$fila['saiu31usuario'];
		$DATA['saiu31fecha']=$fila['saiu31fecha'];
		$DATA['saiu31usuarioaprueba']=$fila['saiu31usuarioaprueba'];
		$DATA['saiu31fechaaprueba']=$fila['saiu31fechaaprueba'];
		$DATA['saiu31terminos']=$fila['saiu31terminos'];
		$bcargo=true;
		$DATA['paso']=2;
		$DATA['boculta3031']=0;
		$bLimpiaHijos=true;
		}else{
		$DATA['paso']=0;
		}
	return array($DATA, $sError, $iTipoError, $sDebug);
	}
function f3031_Cerrar($saiu31id, $objDB, $bDebug=false){
	$sInfo='';
	$sDebug='';
	return array($sInfo, $sDebug);
	}
function f3031_db_GuardarV2($DATA, $objDB, $bDebug=false){
	$iCodModulo=3031;
	$bAudita[2]=true;
	$bAudita[3]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3031='lg/lg_3031_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3031)){$mensajes_3031='lg/lg_3031_es.php';}
	require $mensajes_todas;
	require $mensajes_3031;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	$bCerrando=false;
	$sErrorCerrando='';
	// -- Se inicia validando todas las posibles entradas de usuario.
	/*
	if (isset($DATA['saiu31consec'])==0){$DATA['saiu31consec']='';}
	if (isset($DATA['saiu31version'])==0){$DATA['saiu31version']='';}
	if (isset($DATA['saiu31id'])==0){$DATA['saiu31id']='';}
	if (isset($DATA['saiu31idunidadresp'])==0){$DATA['saiu31idunidadresp']='';}
	if (isset($DATA['saiu31estado'])==0){$DATA['saiu31estado']='';}
	if (isset($DATA['saiu31idtemageneral'])==0){$DATA['saiu31idtemageneral']='';}
	if (isset($DATA['saiu31titulo'])==0){$DATA['saiu31titulo']='';}
	if (isset($DATA['saiu31resumen'])==0){$DATA['saiu31resumen']='';}
	if (isset($DATA['saiu31contenido'])==0){$DATA['saiu31contenido']='';}
	if (isset($DATA['saiu31temporal'])==0){$DATA['saiu31temporal']='';}
	if (isset($DATA['saiu31fechaini'])==0){$DATA['saiu31fechaini']='';}
	if (isset($DATA['saiu31fechafin'])==0){$DATA['saiu31fechafin']='';}
	if (isset($DATA['saiu31cobertura'])==0){$DATA['saiu31cobertura']='';}
	if (isset($DATA['saiu31entornodeuso'])==0){$DATA['saiu31entornodeuso']='';}
	if (isset($DATA['saiu31aplicaaspirante'])==0){$DATA['saiu31aplicaaspirante']='';}
	if (isset($DATA['saiu31aplicaestudiante'])==0){$DATA['saiu31aplicaestudiante']='';}
	if (isset($DATA['saiu31aplicaegresado'])==0){$DATA['saiu31aplicaegresado']='';}
	if (isset($DATA['saiu31aplicadocentes'])==0){$DATA['saiu31aplicadocentes']='';}
	if (isset($DATA['saiu31aplicaadministra'])==0){$DATA['saiu31aplicaadministra']='';}
	if (isset($DATA['saiu31aplicaotros'])==0){$DATA['saiu31aplicaotros']='';}
	if (isset($DATA['saiu31enlaceinfo'])==0){$DATA['saiu31enlaceinfo']='';}
	if (isset($DATA['saiu31enlaceproceso'])==0){$DATA['saiu31enlaceproceso']='';}
	if (isset($DATA['saiu31aplicanotificacion'])==0){$DATA['saiu31aplicanotificacion']='';}
	if (isset($DATA['saiu31fechaparanotificar'])==0){$DATA['saiu31fechaparanotificar']='';}
	if (isset($DATA['saiu31prioridadnotifica'])==0){$DATA['saiu31prioridadnotifica']='';}
	if (isset($DATA['saiu31fecha'])==0){$DATA['saiu31fecha']='';}
	if (isset($DATA['saiu31fechaaprueba'])==0){$DATA['saiu31fechaaprueba']='';}
	if (isset($DATA['saiu31terminos'])==0){$DATA['saiu31terminos']='';}
	*/
	$DATA['saiu31consec']=numeros_validar($DATA['saiu31consec']);
	$DATA['saiu31version']=numeros_validar($DATA['saiu31version']);
	$DATA['saiu31idtemageneral']=numeros_validar($DATA['saiu31idtemageneral']);
	$DATA['saiu31titulo']=htmlspecialchars(trim($DATA['saiu31titulo']));
	$DATA['saiu31resumen']=htmlspecialchars(trim($DATA['saiu31resumen']));
	//$DATA['saiu31contenido']=htmlspecialchars(trim($DATA['saiu31contenido']));
	$DATA['saiu31idunidadresp']=numeros_validar($DATA['saiu31idunidadresp']);
	$DATA['saiu31temporal']=numeros_validar($DATA['saiu31temporal']);
	$DATA['saiu31cobertura']=numeros_validar($DATA['saiu31cobertura']);
	$DATA['saiu31entornodeuso']=numeros_validar($DATA['saiu31entornodeuso']);
	$DATA['saiu31aplicaaspirante']=numeros_validar($DATA['saiu31aplicaaspirante']);
	$DATA['saiu31aplicaestudiante']=numeros_validar($DATA['saiu31aplicaestudiante']);
	$DATA['saiu31aplicaegresado']=numeros_validar($DATA['saiu31aplicaegresado']);
	$DATA['saiu31aplicadocentes']=numeros_validar($DATA['saiu31aplicadocentes']);
	$DATA['saiu31aplicaadministra']=numeros_validar($DATA['saiu31aplicaadministra']);
	$DATA['saiu31aplicaotros']=numeros_validar($DATA['saiu31aplicaotros']);
	$DATA['saiu31enlaceinfo']=htmlspecialchars(trim($DATA['saiu31enlaceinfo']));
	$DATA['saiu31enlaceproceso']=htmlspecialchars(trim($DATA['saiu31enlaceproceso']));
	$DATA['saiu31aplicanotificacion']=numeros_validar($DATA['saiu31aplicanotificacion']);
	$DATA['saiu31prioridadnotifica']=numeros_validar($DATA['saiu31prioridadnotifica']);
	// -- Se inicializan las variables que puedan pasar vacias {Especialmente números}.
	if ($DATA['saiu31estado']==''){$DATA['saiu31estado']=0;}
	//if ($DATA['saiu31idtemageneral']==''){$DATA['saiu31idtemageneral']=0;}
	//if ($DATA['saiu31idunidadresp']==''){$DATA['saiu31idunidadresp']=0;}
	if ($DATA['saiu31temporal']==''){$DATA['saiu31temporal']=0;}
	if ($DATA['saiu31cobertura']==''){$DATA['saiu31cobertura']=0;}
	if ($DATA['saiu31entornodeuso']==''){$DATA['saiu31entornodeuso']=0;}
	if ($DATA['saiu31aplicaaspirante']==''){$DATA['saiu31aplicaaspirante']=0;}
	if ($DATA['saiu31aplicaestudiante']==''){$DATA['saiu31aplicaestudiante']=0;}
	if ($DATA['saiu31aplicaegresado']==''){$DATA['saiu31aplicaegresado']=0;}
	if ($DATA['saiu31aplicadocentes']==''){$DATA['saiu31aplicadocentes']=0;}
	if ($DATA['saiu31aplicaadministra']==''){$DATA['saiu31aplicaadministra']=0;}
	if ($DATA['saiu31aplicaotros']==''){$DATA['saiu31aplicaotros']=0;}
	if ($DATA['saiu31aplicanotificacion']==''){$DATA['saiu31aplicanotificacion']=0;}
	if ($DATA['saiu31prioridadnotifica']==''){$DATA['saiu31prioridadnotifica']=0;}
	// -- Seccion para validar los posibles causales de error.
	$sSepara=', ';
	$bValida=false;
	if ($DATA['saiu31estado']==1){$bValida=true;}
	if ($DATA['saiu31estado']==7){$bValida=true;}
	if ($bValida){
		if ($DATA['saiu31estado']==7){
			if ($DATA['saiu31fechaaprueba']==0){
				$DATA['saiu31fechaaprueba']=fecha_DiaMod();
				//$sError=$ERR['saiu31fechaaprueba'].$sSepara.$sError;
				}
			if ($DATA['saiu31usuarioaprueba']==0){
				$DATA['saiu31usuarioaprueba']=$_SESSION['unad_id_tercero'];
				//$sError=$ERR['saiu31usuarioaprueba'].$sSepara.$sError;
				}
			}
		if ($DATA['saiu31fecha']==0){
			//$DATA['saiu31fecha']=fecha_DiaMod();
			$sError=$ERR['saiu31fecha'].$sSepara.$sError;
			}
		if ($DATA['saiu31usuario']==0){$sError=$ERR['saiu31usuario'].$sSepara.$sError;}
		if ($DATA['saiu31prioridadnotifica']==''){$sError=$ERR['saiu31prioridadnotifica'].$sSepara.$sError;}
		if ($DATA['saiu31aplicanotificacion']==1){
			if ($DATA['saiu31fechaparanotificar']==0){
				//$DATA['saiu31fechaparanotificar']=fecha_DiaMod();
				$sError=$ERR['saiu31fechaparanotificar'].$sSepara.$sError;
				}
			}
		if ($DATA['saiu31aplicanotificacion']==''){$sError=$ERR['saiu31aplicanotificacion'].$sSepara.$sError;}
		//if ($DATA['saiu31enlaceproceso']==''){$sError=$ERR['saiu31enlaceproceso'].$sSepara.$sError;}
		//if ($DATA['saiu31enlaceinfo']==''){$sError=$ERR['saiu31enlaceinfo'].$sSepara.$sError;}
		if ($DATA['saiu31aplicaotros']==''){$sError=$ERR['saiu31aplicaotros'].$sSepara.$sError;}
		if ($DATA['saiu31aplicaadministra']==''){$sError=$ERR['saiu31aplicaadministra'].$sSepara.$sError;}
		if ($DATA['saiu31aplicadocentes']==''){$sError=$ERR['saiu31aplicadocentes'].$sSepara.$sError;}
		if ($DATA['saiu31aplicaegresado']==''){$sError=$ERR['saiu31aplicaegresado'].$sSepara.$sError;}
		if ($DATA['saiu31aplicaestudiante']==''){$sError=$ERR['saiu31aplicaestudiante'].$sSepara.$sError;}
		if ($DATA['saiu31aplicaaspirante']==''){$sError=$ERR['saiu31aplicaaspirante'].$sSepara.$sError;}
		if ($DATA['saiu31entornodeuso']==''){$sError=$ERR['saiu31entornodeuso'].$sSepara.$sError;}
		if ($DATA['saiu31cobertura']==''){$sError=$ERR['saiu31cobertura'].$sSepara.$sError;}
		if ($DATA['saiu31temporal']==1){
			if ($DATA['saiu31fechafin']==0){
				//$DATA['saiu31fechafin']=fecha_DiaMod();
				$sError=$ERR['saiu31fechafin'].$sSepara.$sError;
				}
			if ($DATA['saiu31fechaini']==0){
				//$DATA['saiu31fechaini']=fecha_DiaMod();
				$sError=$ERR['saiu31fechaini'].$sSepara.$sError;
				}
			}
		if ($DATA['saiu31temporal']==''){$sError=$ERR['saiu31temporal'].$sSepara.$sError;}
		//if ($DATA['saiu31contenido']==''){$sError=$ERR['saiu31contenido'].$sSepara.$sError;}
		if ($DATA['saiu31resumen']==''){$sError=$ERR['saiu31resumen'].$sSepara.$sError;}
		if ($DATA['saiu31titulo']==''){$sError=$ERR['saiu31titulo'].$sSepara.$sError;}
		if ($DATA['saiu31idtemageneral']==''){$sError=$ERR['saiu31idtemageneral'].$sSepara.$sError;}
		//if ($sError!=''){$DATA['saiu31estado']='N';}
		$sErrorCerrando=$sError;
		$sError='';
		//Fin de las valiaciones NO LLAVE.
		}else{
		if ($DATA['saiu31idtemageneral']==''){$DATA['saiu31idtemageneral']=0;}
		}
	if ($DATA['saiu31idunidadresp']==''){$sError=$ERR['saiu31idunidadresp'].$sSepara.$sError;}
	//Valiaciones de campos obligatorios en todo guardar.
	if ($DATA['saiu31version']==''){$sError=$ERR['saiu31version'];}
	// -- Tiene un cerrado.
	if ($DATA['saiu31estado']==1){
		//Validaciones previas a cerrar
		//Aprobó las Validaciones al cerrar
		if ($sError.$sErrorCerrando!=''){
			$DATA['saiu31estado']=0;
			$sErrorCerrando=$sError.' '.$sErrorCerrando;
			$sError='';
			}else{
			$bCerrando=true;
			//Acciones del cierre
			}
		}
	// -- Fin del cerrado.
	// -- Se verifican los valores de campos de otras tablas.
	if ($DATA['saiu31usuarioaprueba_doc']!=''){
		if ($sError==''){$sError=tabla_terceros_existe($DATA['saiu31usuarioaprueba_td'], $DATA['saiu31usuarioaprueba_doc'], $objDB, 'El tercero Usuarioaprueba ');}
		if ($sError==''){
			list($sError, $sInfo)=tercero_Bloqueado($DATA['saiu31usuarioaprueba'], $objDB);
			if ($sInfo!=''){$sError=$sError.'<br>'.sInfo;}
			}
		}
	if ($DATA['saiu31usuario_doc']!=''){
		if ($sError==''){$sError=tabla_terceros_existe($DATA['saiu31usuario_td'], $DATA['saiu31usuario_doc'], $objDB, 'El tercero Usuario ');}
		if ($sError==''){
			list($sError, $sInfo)=tercero_Bloqueado($DATA['saiu31usuario'], $objDB);
			if ($sInfo!=''){$sError=$sError.'<br>'.sInfo;}
			}
		}
	$bQuitarCodigo=false;
	$sCampoCodigo='';
	if ($sError==''){
		if ($DATA['paso']==10){
			if ($DATA['saiu31consec']==''){
				$DATA['saiu31consec']=tabla_consecutivo('saiu31baseconocimiento', 'saiu31consec', 'saiu31version='.$DATA['saiu31version'].'', $objDB);
				if ($DATA['saiu31consec']==-1){$sError=$objDB->serror;}
				$bQuitarCodigo=true;
				$sCampoCodigo='saiu31consec';
				}else{
				if (!seg_revisa_permiso($iCodModulo, 8, $objDB)){
					$sError=$ERR['8'];
					$DATA['saiu31consec']='';
					}
				}
			if ($sError==''){
				$sSQL='SELECT 1 FROM saiu31baseconocimiento WHERE saiu31consec='.$DATA['saiu31consec'].' AND saiu31version='.$DATA['saiu31version'].'';
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
			$DATA['saiu31id']=tabla_consecutivo('saiu31baseconocimiento','saiu31id', '', $objDB);
			if ($DATA['saiu31id']==-1){$sError=$objDB->serror;}
			}
		}
	if ($sError==''){
		if (get_magic_quotes_gpc()==1){$DATA['saiu31contenido']=stripslashes($DATA['saiu31contenido']);}
		//Si el campo saiu31contenido permite html quite la linea htmlspecialchars para el campo y habilite la siguiente linea:
		$saiu31contenido=addslashes($DATA['saiu31contenido']);
		//$saiu31contenido=str_replace('"', '\"', $DATA['saiu31contenido']);
		if (get_magic_quotes_gpc()==1){$DATA['saiu31terminos']=stripslashes($DATA['saiu31terminos']);}
		//Si el campo saiu31terminos permite html quite la linea htmlspecialchars para el campo y habilite la siguiente linea:
		//$saiu31terminos=addslashes($DATA['saiu31terminos']);
		$saiu31terminos=str_replace('"', '\"', $DATA['saiu31terminos']);
		$bpasa=false;
		if ($DATA['paso']==10){
			$DATA['saiu31estado']=0;
			//$saiu31fechaini=fecha_DiaMod();
			//$saiu31fechafin=fecha_DiaMod();
			//$saiu31fechaparanotificar=fecha_DiaMod();
			//$DATA['saiu31usuario']=0; //$_SESSION['u_idtercero'];
			//$saiu31fecha=fecha_DiaMod();
			//$DATA['saiu31usuarioaprueba']=0; //$_SESSION['u_idtercero'];
			//$saiu31fechaaprueba=fecha_DiaMod();
			$DATA['saiu31fechaaprueba']=0;
			$sCampos3031='saiu31consec, saiu31version, saiu31id, saiu31estado, saiu31idtemageneral, 
saiu31titulo, saiu31resumen, saiu31contenido, saiu31idunidadresp, saiu31temporal, 
saiu31fechaini, saiu31fechafin, saiu31cobertura, saiu31entornodeuso, saiu31aplicaaspirante, 
saiu31aplicaestudiante, saiu31aplicaegresado, saiu31aplicadocentes, saiu31aplicaadministra, saiu31aplicaotros, 
saiu31enlaceinfo, saiu31enlaceproceso, saiu31aplicanotificacion, saiu31fechaparanotificar, saiu31prioridadnotifica, 
saiu31usuario, saiu31fecha, saiu31usuarioaprueba, saiu31fechaaprueba, saiu31terminos';
			$sValores3031=''.$DATA['saiu31consec'].', '.$DATA['saiu31version'].', '.$DATA['saiu31id'].', "'.$DATA['saiu31estado'].'", '.$DATA['saiu31idtemageneral'].', 
"'.$DATA['saiu31titulo'].'", "'.$DATA['saiu31resumen'].'", "'.$saiu31contenido.'", '.$DATA['saiu31idunidadresp'].', '.$DATA['saiu31temporal'].', 
"'.$DATA['saiu31fechaini'].'", "'.$DATA['saiu31fechafin'].'", '.$DATA['saiu31cobertura'].', '.$DATA['saiu31entornodeuso'].', '.$DATA['saiu31aplicaaspirante'].', 
'.$DATA['saiu31aplicaestudiante'].', '.$DATA['saiu31aplicaegresado'].', '.$DATA['saiu31aplicadocentes'].', '.$DATA['saiu31aplicaadministra'].', '.$DATA['saiu31aplicaotros'].', 
"'.$DATA['saiu31enlaceinfo'].'", "'.$DATA['saiu31enlaceproceso'].'", '.$DATA['saiu31aplicanotificacion'].', "'.$DATA['saiu31fechaparanotificar'].'", '.$DATA['saiu31prioridadnotifica'].', 
'.$DATA['saiu31usuario'].', "'.$DATA['saiu31fecha'].'", '.$DATA['saiu31usuarioaprueba'].', "'.$DATA['saiu31fechaaprueba'].'", "'.$saiu31terminos.'"';
			if ($APP->utf8==1){
				$sSQL='INSERT INTO saiu31baseconocimiento ('.$sCampos3031.') VALUES ('.utf8_encode($sValores3031).');';
				$sdetalle=$sCampos3031.'['.utf8_encode($sValores3031).']';
				}else{
				$sSQL='INSERT INTO saiu31baseconocimiento ('.$sCampos3031.') VALUES ('.$sValores3031.');';
				$sdetalle=$sCampos3031.'['.$sValores3031.']';
				}
			$idAccion=2;
			$bpasa=true;
			}else{
			$scampo[1]='saiu31estado';
			$scampo[2]='saiu31idtemageneral';
			$scampo[3]='saiu31titulo';
			$scampo[4]='saiu31resumen';
			$scampo[5]='saiu31contenido';
			$scampo[6]='saiu31idunidadresp';
			$scampo[7]='saiu31temporal';
			$scampo[8]='saiu31fechaini';
			$scampo[9]='saiu31fechafin';
			$scampo[10]='saiu31cobertura';
			$scampo[11]='saiu31entornodeuso';
			$scampo[12]='saiu31aplicaaspirante';
			$scampo[13]='saiu31aplicaestudiante';
			$scampo[14]='saiu31aplicaegresado';
			$scampo[15]='saiu31aplicadocentes';
			$scampo[16]='saiu31aplicaadministra';
			$scampo[17]='saiu31aplicaotros';
			$scampo[18]='saiu31enlaceinfo';
			$scampo[19]='saiu31enlaceproceso';
			$scampo[20]='saiu31aplicanotificacion';
			$scampo[21]='saiu31fechaparanotificar';
			$scampo[22]='saiu31prioridadnotifica';
			$scampo[23]='saiu31fecha';
			$scampo[24]='saiu31fechaaprueba';
			$scampo[25]='saiu31terminos';
			$sdato[1]=$DATA['saiu31estado'];
			$sdato[2]=$DATA['saiu31idtemageneral'];
			$sdato[3]=$DATA['saiu31titulo'];
			$sdato[4]=$DATA['saiu31resumen'];
			$sdato[5]=$saiu31contenido;
			$sdato[6]=$DATA['saiu31idunidadresp'];
			$sdato[7]=$DATA['saiu31temporal'];
			$sdato[8]=$DATA['saiu31fechaini'];
			$sdato[9]=$DATA['saiu31fechafin'];
			$sdato[10]=$DATA['saiu31cobertura'];
			$sdato[11]=$DATA['saiu31entornodeuso'];
			$sdato[12]=$DATA['saiu31aplicaaspirante'];
			$sdato[13]=$DATA['saiu31aplicaestudiante'];
			$sdato[14]=$DATA['saiu31aplicaegresado'];
			$sdato[15]=$DATA['saiu31aplicadocentes'];
			$sdato[16]=$DATA['saiu31aplicaadministra'];
			$sdato[17]=$DATA['saiu31aplicaotros'];
			$sdato[18]=$DATA['saiu31enlaceinfo'];
			$sdato[19]=$DATA['saiu31enlaceproceso'];
			$sdato[20]=$DATA['saiu31aplicanotificacion'];
			$sdato[21]=$DATA['saiu31fechaparanotificar'];
			$sdato[22]=$DATA['saiu31prioridadnotifica'];
			$sdato[23]=$DATA['saiu31fecha'];
			$sdato[24]=$DATA['saiu31fechaaprueba'];
			$sdato[25]=$saiu31terminos;
			$numcmod=25;
			$sWhere='saiu31id='.$DATA['saiu31id'].'';
			$sSQL='SELECT * FROM saiu31baseconocimiento WHERE '.$sWhere;
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
					$sSQL='UPDATE saiu31baseconocimiento SET '.utf8_encode($sdatos).' WHERE '.$sWhere.';';
					}else{
					$sdetalle=$sdatos.'['.$sWhere.']';
					$sSQL='UPDATE saiu31baseconocimiento SET '.$sdatos.' WHERE '.$sWhere.';';
					}
				$idAccion=3;
				}
			}
		if ($bpasa){
			if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Guardar 3031 '.$sSQL.'<br>';}
			$result=$objDB->ejecutasql($sSQL);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' [3031] ..<!-- '.$sSQL.' -->';
				if ($idAccion==2){
					$DATA['saiu31id']='';
					$bQuitarCodigo=true;
					$DATA['paso']=0;
					}else{
					$DATA['paso']=2;
					}
				$bCerrando=false;
				}else{
				if ($bAudita[$idAccion]){seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], $idAccion, $DATA['saiu31id'], $sdetalle, $objDB);}
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
		$bCerrando=false;
		if ($bQuitarCodigo){
			if ($sCampoCodigo!=''){$DATA[$sCampoCodigo]='';}
			}
		}
	$sInfoCierre='';
	if ($bCerrando){
		list($sErrorCerrando, $sDebugCerrar)=f3031_Cerrar($DATA['saiu31id'], $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugCerrar;
		}
	//if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' InfoDepura<br>';}
	return array($DATA, $sError, $iTipoError, $bCerrando, $sErrorCerrando, $sDebug);
	}
function f3031_db_Eliminar($saiu31id, $objDB, $bDebug=false){
	$iCodModulo=3031;
	$bAudita[4]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3031='lg/lg_3031_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3031)){$mensajes_3031='lg/lg_3031_es.php';}
	require $mensajes_todas;
	require $mensajes_3031;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	$saiu31id=numeros_validar($saiu31id);
	// Traer los datos para hacer las validaciones.
	if ($sError==''){
		$sSQL='SELECT * FROM saiu31baseconocimiento WHERE saiu31id='.$saiu31id.'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$filabase=$objDB->sf($tabla);
			}else{
			$sError='No se encuentra el registro solicitado {Ref: '.$saiu31id.'}';
			}
		}
	if ($sError==''){
		$sSQL='SELECT 1 FROM saiu32basecontema WHERE saiu32idbasecon='.$filabase['saiu31id'].'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$sError='Existen Temas asociados creados, no es posible eliminar';
			}
		}
	if ($sError==''){
		$sSQL='SELECT 1 FROM saiu35cobertura WHERE saiu35idbasecon='.$filabase['saiu31id'].'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$sError='Existen Cobertura creados, no es posible eliminar';
			}
		}
	if ($sError==''){
		$sSQL='SELECT 1 FROM saiu38basecambioest WHERE saiu38idbasecon='.$filabase['saiu31id'].'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$sError='Existen Cambios de estado creados, no es posible eliminar';
			}
		}
	if ($sError==''){
		$sSQL='SELECT 1 FROM saiu33basepalabraclave WHERE saiu33idbasecon='.$filabase['saiu31id'].'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$sError='Existen Palabras claves creados, no es posible eliminar';
			}
		}
	if ($sError==''){
		if (!seg_revisa_permiso($iCodModulo, 4, $objDB)){
			$sError=$ERR['4'];
			}
		}
	if ($sError==''){
		$sSQL='SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=3031';
		$tablaor=$objDB->ejecutasql($sSQL);
		while ($filaor=$objDB->sf($tablaor)){
			$sSQL='SELECT '.$filaor['campoid'].' FROM '.$filaor['tabla'].' WHERE '.$filaor['camporev'].'='.$_REQUEST['saiu31id'].' LIMIT 0, 1';
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
		//$sSQL='DELETE FROM saiu32basecontema WHERE saiu32idbasecon='.$filabase['saiu31id'].'';
		//$tabla=$objDB->ejecutasql($sSQL);
		//$sSQL='DELETE FROM saiu35cobertura WHERE saiu35idbasecon='.$filabase['saiu31id'].'';
		//$tabla=$objDB->ejecutasql($sSQL);
		//$sSQL='DELETE FROM saiu38basecambioest WHERE saiu38idbasecon='.$filabase['saiu31id'].'';
		//$tabla=$objDB->ejecutasql($sSQL);
		//$sSQL='DELETE FROM saiu33basepalabraclave WHERE saiu33idbasecon='.$filabase['saiu31id'].'';
		//$tabla=$objDB->ejecutasql($sSQL);
		$sWhere='saiu31id='.$saiu31id.'';
		//$sWhere='saiu31version='.$filabase['saiu31version'].' AND saiu31consec='.$filabase['saiu31consec'].'';
		$sSQL='DELETE FROM saiu31baseconocimiento WHERE '.$sWhere.';';
		$result=$objDB->ejecutasql($sSQL);
		if ($result==false){
			$sError=$ERR['falla_eliminar'].' .. <!-- '.$sSQL.' -->';
			}else{
			if ($bAudita[4]){seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $saiu31id, $sWhere, $objDB);}
			}
		}
	return array($sError, $iTipoError, $sDebug);
	}
function f3031_TituloBusqueda(){
	return 'Busqueda de Base de conocimiento';
	}
function f3031_ParametrosBusqueda(){
	$sParams='<label class="Label90">Nombre</label><label><input id="b3031nombre" name="b3031nombre" type="text" value="" onchange="paginarbusqueda()" /></label>';
	return $sParams;
	}
function f3031_JavaScriptBusqueda($iModuloBusca){
	$sRes='var sCampo=window.document.frmedita.scampobusca.value;
var params=new Array();
params[100]=sCampo;
params[101]=window.document.frmedita.paginabusqueda.value;
params[102]=window.document.frmedita.lppfbusqueda.value;
params[103]=window.document.frmedita.b3031nombre.value;
xajax_f'.$iModuloBusca.'_HtmlBusqueda(params);';
	return $sRes;
	}
function f3031_TablaDetalleBusquedas($aParametros, $objDB){
	$res='';
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3031='lg/lg_3031_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3031)){$mensajes_3031='lg/lg_3031_es.php';}
	require $mensajes_todas;
	require $mensajes_3031;
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
		return array($sLeyenda.'<input id="paginaf3031" name="paginaf3031" type="hidden" value="'.$pagina.'"/><input id="lppf3031" name="lppf3031" type="hidden" value="'.$lineastabla.'"/>', $sDebug);
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
	$sTitulos='Consec, Version, Id, Estado, Temageneral, Titulo, Resumen, Contenido, Unidadresp, Temporal, Fechaini, Fechafin, Cobertura, Entornodeuso, Aplicaaspirante, Aplicaestudiante, Aplicaegresado, Aplicadocentes, Aplicaadministra, Aplicaotros, Enlaceinfo, Enlaceproceso, Aplicanotificacion, Fechaparanotificar, Prioridadnotifica, Usuario, Fecha, Usuarioaprueba, Fechaaprueba';
	$sSQL='SELECT TB.saiu31consec, TB.saiu31version, TB.saiu31id, TB.saiu31estado, T5.saiu03titulo, TB.saiu31titulo, TB.saiu31resumen, TB.saiu31contenido, T9.unae26nombre, TB.saiu31temporal, TB.saiu31fechaini, TB.saiu31fechafin, T13.saiu17nombre, TB.saiu31entornodeuso, TB.saiu31aplicaaspirante, TB.saiu31aplicaestudiante, TB.saiu31aplicaegresado, TB.saiu31aplicadocentes, TB.saiu31aplicaadministra, TB.saiu31aplicaotros, TB.saiu31enlaceinfo, TB.saiu31enlaceproceso, TB.saiu31aplicanotificacion, TB.saiu31fechaparanotificar, TB.saiu31prioridadnotifica, T26.unad11razonsocial AS C26_nombre, TB.saiu31fecha, T28.unad11razonsocial AS C28_nombre, TB.saiu31fechaaprueba, TB.saiu31idtemageneral, TB.saiu31idunidadresp, TB.saiu31cobertura, TB.saiu31usuario, T26.unad11tipodoc AS C26_td, T26.unad11doc AS C26_doc, TB.saiu31usuarioaprueba, T28.unad11tipodoc AS C28_td, T28.unad11doc AS C28_doc 
FROM saiu31baseconocimiento AS TB, saiu03temasol AS T5, unae26unidadesfun AS T9, saiu17nivelatencion AS T13, unad11terceros AS T26, unad11terceros AS T28 
WHERE '.$sSQLadd1.' TB.saiu31idtemageneral=T5.saiu03id AND TB.saiu31idunidadresp=T9.unae26id AND TB.saiu31cobertura=T13.saiu17id AND TB.saiu31usuario=T26.unad11id AND TB.saiu31usuarioaprueba=T28.unad11id '.$sSQLadd.'
ORDER BY TB.saiu31consec, TB.saiu31version';
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
			//return array(utf8_encode($sErrConsulta.'<input id="paginaf3031" name="paginaf3031" type="hidden" value="'.$pagina.'"/><input id="lppf3031" name="lppf3031" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
<td><b>'.$ETI['saiu31consec'].'</b></td>
<td><b>'.$ETI['saiu31version'].'</b></td>
<td><b>'.$ETI['saiu31estado'].'</b></td>
<td><b>'.$ETI['saiu31idtemageneral'].'</b></td>
<td><b>'.$ETI['saiu31titulo'].'</b></td>
<td><b>'.$ETI['saiu31resumen'].'</b></td>
<td><b>'.$ETI['saiu31contenido'].'</b></td>
<td><b>'.$ETI['saiu31idunidadresp'].'</b></td>
<td><b>'.$ETI['saiu31temporal'].'</b></td>
<td><b>'.$ETI['saiu31fechaini'].'</b></td>
<td><b>'.$ETI['saiu31fechafin'].'</b></td>
<td><b>'.$ETI['saiu31cobertura'].'</b></td>
<td><b>'.$ETI['saiu31entornodeuso'].'</b></td>
<td><b>'.$ETI['saiu31aplicaaspirante'].'</b></td>
<td><b>'.$ETI['saiu31aplicaestudiante'].'</b></td>
<td><b>'.$ETI['saiu31aplicaegresado'].'</b></td>
<td><b>'.$ETI['saiu31aplicadocentes'].'</b></td>
<td><b>'.$ETI['saiu31aplicaadministra'].'</b></td>
<td><b>'.$ETI['saiu31aplicaotros'].'</b></td>
<td><b>'.$ETI['saiu31enlaceinfo'].'</b></td>
<td><b>'.$ETI['saiu31enlaceproceso'].'</b></td>
<td><b>'.$ETI['saiu31aplicanotificacion'].'</b></td>
<td><b>'.$ETI['saiu31fechaparanotificar'].'</b></td>
<td><b>'.$ETI['saiu31prioridadnotifica'].'</b></td>
<td colspan="2"><b>'.$ETI['saiu31usuario'].'</b></td>
<td><b>'.$ETI['saiu31fecha'].'</b></td>
<td colspan="2"><b>'.$ETI['saiu31usuarioaprueba'].'</b></td>
<td><b>'.$ETI['saiu31fechaaprueba'].'</b></td>
<td align="right">
'.html_paginador('paginabusqueda', $registros, $lineastabla, $pagina, 'paginarbusqueda()').'
'.html_lpp('lppfbusqueda', $lineastabla, 'paginarbusqueda()').'
</td>
</tr></thead>';
	$tlinea=1;
	while($filadet=$objDB->sf($tabladetalle)){
		$sPrefijo='<a href="javascript:Devuelve(\''.$filadet['saiu31id'].'\');">';
		$sSufijo='</a>';
		$tlinea++;
		$et_saiu31estado=$ETI['msg_abierto'];
		if ($filadet['saiu31estado']=='S'){$et_saiu31estado=$ETI['msg_cerrado'];}
		$et_saiu31fechaini='';
		if ($filadet['saiu31fechaini']!=0){$et_saiu31fechaini=fecha_desdenumero($filadet['saiu31fechaini']);}
		$et_saiu31fechafin='';
		if ($filadet['saiu31fechafin']!=0){$et_saiu31fechafin=fecha_desdenumero($filadet['saiu31fechafin']);}
		$et_saiu31fechaparanotificar='';
		if ($filadet['saiu31fechaparanotificar']!=0){$et_saiu31fechaparanotificar=fecha_desdenumero($filadet['saiu31fechaparanotificar']);}
		$et_saiu31fecha='';
		if ($filadet['saiu31fecha']!=0){$et_saiu31fecha=fecha_desdenumero($filadet['saiu31fecha']);}
		$et_saiu31fechaaprueba='';
		if ($filadet['saiu31fechaaprueba']!=0){$et_saiu31fechaaprueba=fecha_desdenumero($filadet['saiu31fechaaprueba']);}
		$res=$res.'<tr onmouseover="cambia_color_over(this);" onmouseout="cambia_color_out(this);">
<td>'.$sPrefijo.$filadet['saiu31consec'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu31version'].$sSufijo.'</td>
<td>'.$sPrefijo.$et_saiu31estado.$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['saiu03titulo']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['saiu31titulo']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['saiu31resumen']).$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu31contenido'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['unae26nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu31temporal'].$sSufijo.'</td>
<td>'.$sPrefijo.$et_saiu31fechaini.$sSufijo.'</td>
<td>'.$sPrefijo.$et_saiu31fechafin.$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['saiu17nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu31entornodeuso'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu31aplicaaspirante'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu31aplicaestudiante'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu31aplicaegresado'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu31aplicadocentes'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu31aplicaadministra'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu31aplicaotros'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['saiu31enlaceinfo']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['saiu31enlaceproceso']).$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu31aplicanotificacion'].$sSufijo.'</td>
<td>'.$sPrefijo.$et_saiu31fechaparanotificar.$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu31prioridadnotifica'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['C26_td'].' '.$filadet['C26_doc'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['C26_nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.$et_saiu31fecha.$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['C28_td'].' '.$filadet['C28_doc'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['C28_nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.$et_saiu31fechaaprueba.$sSufijo.'</td>
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