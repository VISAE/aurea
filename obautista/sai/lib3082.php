<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2021 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.25.10c jueves, 4 de febrero de 2021
--- 3082 saiu23inventario
*/
/** Archivo lib3082.php.
* Libreria 3082 saiu23inventario.
* @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
* @date jueves, 4 de febrero de 2021
*/
function f3082_HTMLComboV2_saiu23agno($objDB, $objCombos, $valor){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('saiu23agno', $valor, true, '');
	$objCombos->sAccion='paginarf3082()';
	//$objCombos->iAncho=450;
	//$objCombos->sAccion='RevisaLlave();';
	$objCombos->numeros(2020, fecha_agno(), 1);
	$res=$objCombos->html('', $objDB);
	return $res;
	}
function f3082_HTMLComboV2_saiu23cead($objDB, $objCombos, $valor, $vrsaiu23idzona){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('saiu23cead', $valor, true, '{'.$ETI['msg_todos'].'}');
	//$objCombos->iAncho=450;
	$objCombos->sAccion='paginarf3082();';
	$sSQL='';
	if ((int)$vrsaiu23idzona!=0){
		$sSQL='SELECT unad24id AS id, unad24nombre AS nombre FROM unad24sede WHERE unad24idzona='.$vrsaiu23idzona.'';
		}
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f3082_HTMLComboV2_saiu23idprograma($objDB, $objCombos, $valor, $vrsaiu23idescuela){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('saiu23idprograma', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	//$objCombos->iAncho=450;
	$objCombos->sAccion='paginarf3082();';
	$sSQL='';
	if ((int)$vrsaiu23idescuela!=0){
		$sSQL='SELECT core09id AS id, core09nombre AS nombre FROM core09programa WHERE core09idescuela='.$vrsaiu23idescuela.'';
		}
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f3082_Combosaiu23cead($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$objCombos=new clsHtmlCombos();
	$html_saiu23cead=f3082_HTMLComboV2_saiu23cead($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_saiu23cead', 'innerHTML', $html_saiu23cead);
	//$objResponse->call('$("#saiu23cead").chosen()');
	$objResponse->call('paginarf3082()');
	return $objResponse;
	}
function f3082_Combosaiu23idprograma($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$objCombos=new clsHtmlCombos();
	$html_saiu23idprograma=f3082_HTMLComboV2_saiu23idprograma($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_saiu23idprograma', 'innerHTML', $html_saiu23idprograma);
	//$objResponse->call('$("#saiu23idprograma").chosen()');
	$objResponse->call('paginarf3082()');
	return $objResponse;
	}
function f3082_Busquedas($aParametros){
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3082='lg/lg_3082_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3082)){$mensajes_3082='lg/lg_3082_es.php';}
	require $mensajes_todas;
	require $mensajes_3082;
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
		case 'saiu23idsolicitante':
		require $APP->rutacomun.'lib111.php';
		$sTabla=f111_TablaDetalleBusquedas($aParametrosB, $objDB);
		$sTitulo='Busqueda de terceros';
		$sParams=f111_ParametrosBusqueda();
		$sJavaBusqueda=f111_JavaScriptBusqueda(3082);
		break;
		case 'saiu23idresponsable':
		require $APP->rutacomun.'lib111.php';
		$sTabla=f111_TablaDetalleBusquedas($aParametrosB, $objDB);
		$sTitulo='Busqueda de terceros';
		$sParams=f111_ParametrosBusqueda();
		$sJavaBusqueda=f111_JavaScriptBusqueda(3082);
		break;
		}
	$sTitulo='<h2>'.$ETI['titulo_3082'].' - '.$sTitulo.'</h2>';
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97titulo', 'innerHTML', $sTitulo);
	$objResponse->assign('div_97params', 'innerHTML', $sParams);
	$objResponse->assign('div_97tabla', 'innerHTML', $sTabla);
	$objResponse->setFunction('paginarbusqueda','',$sJavaBusqueda);
	$objResponse->call('expandesector(97)');
	return $objResponse;
	}
function f3082_HtmlBusqueda($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$sError='';
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$sDetalle='';
	switch($aParametros[100]){
		case 'saiu23idsolicitante':
		require $APP->rutacomun.'lib111.php';
		$sDetalle=f111_TablaDetalleBusquedas($aParametros, $objDB);
		break;
		case 'saiu23idresponsable':
		require $APP->rutacomun.'lib111.php';
		$sDetalle=f111_TablaDetalleBusquedas($aParametros, $objDB);
		break;
		}
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97tabla', 'innerHTML', $sDetalle);
	return $objResponse;
	}
function f3082_TablaDetalleV2($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3082='lg/lg_3082_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3082)){$mensajes_3082='lg/lg_3082_es.php';}
	require $mensajes_todas;
	require $mensajes_3082;
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
	if (isset($aParametros[114])==0){$aParametros[114]='';}
	if (isset($aParametros[115])==0){$aParametros[115]='';}
	if (isset($aParametros[116])==0){$aParametros[116]='';}
	$idTercero=$aParametros[100];
	$sDebug='';
	$pagina=$aParametros[101];
	$lineastabla=$aParametros[102];
	$saiu23agno=$aParametros[103];
	$saiu23mes=$aParametros[104];
	$saiu23estado=$aParametros[105];
	$saiu23idmedio=$aParametros[106];
	$saiu23idtema=$aParametros[107];
	$saiu23idtiposol=$aParametros[108];
	$saiu23idsolicitante=$aParametros[109];
	$saiu23idzona=$aParametros[110];
	$saiu23cead=$aParametros[111];
	$saiu23idresponsable=$aParametros[112];
	$saiu23idescuela=$aParametros[113];
	$saiu23idprograma=$aParametros[114];
	$saiu23idperiodo=$aParametros[115];
	$saiu23idcurso=$aParametros[116];
	$bAbierta=true;
	//$sSQL='SELECT Campo FROM Tabla WHERE Id='.$sValorId;
	//$tabla=$objDB->ejecutasql($sSQL);
	//if ($objDB->nf($tabla)>0){
		//$fila=$objDB->sf($tabla);
		//if ($fila['Campo']!='S'){$bAbierta=true;}
		//}
	$sSQLadd='';
	$sSQLadd1='';
	$sLeyenda='';
	$sBotones='<input id="paginaf3082" name="paginaf3082" type="hidden" value="'.$pagina.'"/>
	<input id="lppf3082" name="lppf3082" type="hidden" value="'.$lineastabla.'"/>';
	if ((int)$saiu23agno==0){$sLeyenda='Se requiere un a&ntilde;o';}
	if ($sLeyenda==''){
		switch($saiu23idmedio){
			case 3018: //Telefonico
			$sTabla='saiu18telefonico_'.$saiu23agno;
			if ((int)$saiu23mes!=0){$sSQLadd1='saiu18mes='.$saiu23mes.'';}
			break;
			case 3019: //Chat
			$sTabla='saiu19chat_'.$saiu23agno.'';
			if ((int)$saiu23mes!=0){$sSQLadd1='saiu19mes='.$saiu23mes.'';}
			break;
			case 3028: //Mesa de ayuda
			$sTabla='saiu28mesaayuda_'.$saiu23agno.'';
			if ((int)$saiu23mes!=0){$sSQLadd1='saiu28mes='.$saiu23mes.'';}
			break;
			case '':
			$sLeyenda='Debe seleccionar el medio.';
			break;
			default:
			$sLeyenda='No se reconoce el reporte solicitado.';
			break;
			}
		}
	if ($sLeyenda!=''){
		$sLeyenda='<div class="salto1px"></div>
		<div class="GrupoCamposAyuda">
		'.$sLeyenda.'
		<div class="salto1px"></div>
		</div>';
		return array($sLeyenda.$sBotones, $sDebug);
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
	//if ($saiu23agno!=''){$sSQLadd1=$sSQLadd1.'TB.saiu23agno='.$saiu23agno.' AND ';}
	//if ($saiu23mes!=''){$sSQLadd1=$sSQLadd1.'TB.saiu23mes='.$saiu23mes.' AND ';}
	//if ($saiu23estado!=''){$sSQLadd1=$sSQLadd1.'TB.saiu23estado='.$saiu23estado.' AND ';}
	//if ($saiu23idmedio!=''){$sSQLadd1=$sSQLadd1.'TB.saiu23idmedio='.$saiu23idmedio.' AND ';}
	//if ($saiu23idtema!=''){$sSQLadd1=$sSQLadd1.'TB.saiu23idtema='.$saiu23idtema.' AND ';}
	//if ($saiu23idtiposol!=''){$sSQLadd1=$sSQLadd1.'TB.saiu23idtiposol='.$saiu23idtiposol.' AND ';}
	//if ($saiu23idsolicitante!=''){$sSQLadd1=$sSQLadd1.'TB.saiu23idsolicitante='.$saiu23idsolicitante.' AND ';}
	//if ($saiu23idzona!=''){$sSQLadd1=$sSQLadd1.'TB.saiu23idzona='.$saiu23idzona.' AND ';}
	//if ($saiu23cead!=''){$sSQLadd1=$sSQLadd1.'TB.saiu23cead='.$saiu23cead.' AND ';}
	//if ($saiu23idresponsable!=''){$sSQLadd1=$sSQLadd1.'TB.saiu23idresponsable='.$saiu23idresponsable.' AND ';}
	//if ($saiu23idescuela!=''){$sSQLadd1=$sSQLadd1.'TB.saiu23idescuela='.$saiu23idescuela.' AND ';}
	//if ($saiu23idprograma!=''){$sSQLadd1=$sSQLadd1.'TB.saiu23idprograma='.$saiu23idprograma.' AND ';}
	//if ($saiu23idperiodo!=''){$sSQLadd1=$sSQLadd1.'TB.saiu23idperiodo='.$saiu23idperiodo.' AND ';}
	//if ($saiu23idcurso!=''){$sSQLadd1=$sSQLadd1.'TB.saiu23idcurso='.$saiu23idcurso.' AND ';}
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
	$sTitulos='Agno, Mes, Estado, Medio, Tema, Tiposol, Solicitante, Zona, Cead, Responsable, Escuela, Programa, Periodo, Curso';
	$registros=0;
	$bGigante=false; //En caso de que la tabla sea muy grande pasarlo a true
	$sLimite='';
	if ($sSQLadd1!=''){$sSQLadd1=' WHERE '.$sSQLadd1.'';}
	$sSQL='SELECT 1 
	FROM '.$sTabla.' 
	'.$sSQLadd1.'';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_3082" name="consulta_3082" type="hidden" value="'.$sSQLlista.'"/>
	<input id="titulos_3082" name="titulos_3082" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objDB->ejecutasql($sSQL.$sLimite);
	if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 3082: '.$sSQL.$sLimite.'<br>';}
	if ($tabladetalle==false){
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		//$sLeyenda=$sSQL;
		}else{
		$registros=$objDB->nf($tabladetalle);
		}
	$sLeyenda='<div class="salto1px"></div>
	<div class="GrupoCamposAyuda">
	Total registros: <b>'.formato_numero($registros).'</b>
	<div class="salto1px"></div>
	</div>';
	$res=$sErrConsulta.$sLeyenda.$sBotones;
	$objDB->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
	}
function f3082_HtmlTabla($aParametros){
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
	list($sDetalle, $sDebugTabla)=f3082_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f3082detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
?>