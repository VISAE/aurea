<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2022 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.27.5 jueves, 6 de enero de 2022
--- 3063 saiu63mensajenotifica
*/
/** Archivo lib3063.php.
* Libreria 3063 saiu63mensajenotifica.
* @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
* @date jueves, 6 de enero de 2022
*/
function f3063_HTMLComboV2_saiu63idmodulo($objDB, $objCombos, $valor){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('saiu63idmodulo', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	//$objCombos->iAncho=450;
	$objCombos->sAccion='RevisaLlave();';
	$sSQL='SELECT  AS id,  AS nombre FROM ';
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f3063_HTMLComboV2_saiu63idcentro($objDB, $objCombos, $valor, $vrsaiu63idzona){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('saiu63idcentro', $valor, true, '{'.$ETI['msg_todos'].'}', 0);
	//$objCombos->iAncho=450;
	$sSQL='';
	if ((int)$vrsaiu63idzona!=0){
		$sSQL='SELECT unad24id AS id, unad24nombre AS nombre FROM unad24sede WHERE unad24idzona='.$vrsaiu63idzona.'';
		}
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f3063_HTMLComboV2_saiu63idprograma($objDB, $objCombos, $valor, $vrsaiu63idescuela){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('saiu63idprograma', $valor, true, '{'.$ETI['msg_todos'].'}', 0);
	//$objCombos->iAncho=450;
	$sSQL='';
	if ((int)$vrsaiu63idescuela!=0){
		$sSQL='SELECT core09id AS id, core09nombre AS nombre FROM core09programa WHERE core10idprograma='.$vrsaiu63idescuela.'';
		}
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f3063_Combosaiu63idcentro($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$objCombos=new clsHtmlCombos();
	$html_saiu63idcentro=f3063_HTMLComboV2_saiu63idcentro($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_saiu63idcentro', 'innerHTML', $html_saiu63idcentro);
	//$objResponse->call('$("#saiu63idcentro").chosen()');
	return $objResponse;
	}
function f3063_Combosaiu63idprograma($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$objCombos=new clsHtmlCombos();
	$html_saiu63idprograma=f3063_HTMLComboV2_saiu63idprograma($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_saiu63idprograma', 'innerHTML', $html_saiu63idprograma);
	$objResponse->call('$("#saiu63idprograma").chosen()');
	return $objResponse;
	}
function f3063_ExisteDato($datos){
	if(!is_array($datos)){$datos=json_decode(str_replace('\"','"',$datos),true);}
	$_SESSION['u_ultimominuto']=iminutoavance();
	$bHayLlave=true;
	$saiu63idmodulo=numeros_validar($datos[1]);
	if ($saiu63idmodulo==''){$bHayLlave=false;}
	$saiu63consec=numeros_validar($datos[2]);
	if ($saiu63consec==''){$bHayLlave=false;}
	if ($bHayLlave){
		require './app.php';
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		$sSQL='SELECT 1 FROM saiu63mensajenotifica WHERE saiu63idmodulo='.$saiu63idmodulo.' AND saiu63consec='.$saiu63consec.'';
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
function f3063_Busquedas($aParametros){
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3063='lg/lg_3063_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3063)){$mensajes_3063='lg/lg_3063_es.php';}
	require $mensajes_todas;
	require $mensajes_3063;
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$sCampo=$aParametros[1];
	$sTitulo=' {'.$sCampo.'}';
	if (isset($aParametros[2])==0){$aParametros[2]=0;}
	if (isset($aParametros[3])==0){$aParametros[3]=0;}
	$iPiel=iDefinirPiel($APP, 1);
	$sParams='';
	$sTabla='';
	$sJavaBusqueda='';
	$aParametrosB=array();
	$aParametrosB[101]=1;
	$aParametrosB[102]=20;
	switch($sCampo){
		case 'saiu64idtercero':
		require $APP->rutacomun.'lib111.php';
		$sTabla=f111_TablaDetalleBusquedas($aParametrosB, $objDB);
		$sTitulo='Busqueda de terceros';
		$sParams=f111_ParametrosBusqueda();
		$sJavaBusqueda=f111_JavaScriptBusqueda(3063);
		break;
		}
	$sTitulo='<h2>'.$ETI['titulo_3063'].' - '.$sTitulo.'</h2>';
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97titulo', 'innerHTML', $sTitulo);
	$objResponse->assign('div_97params', 'innerHTML', $sParams);
	$objResponse->assign('div_97tabla', 'innerHTML', $sTabla);
	$objResponse->setFunction('paginarbusqueda','',$sJavaBusqueda);
	$objResponse->call('expandesector(97)');
	return $objResponse;
	}
function f3063_HtmlBusqueda($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$sError='';
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$sDetalle='';
	switch($aParametros[100]){
		case 'saiu64idtercero':
		require $APP->rutacomun.'lib111.php';
		$sDetalle=f111_TablaDetalleBusquedas($aParametros, $objDB);
		break;
		}
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97tabla', 'innerHTML', $sDetalle);
	return $objResponse;
	}
function f3063_TablaDetalleV2($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3063='lg/lg_3063_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3063)){$mensajes_3063='lg/lg_3063_es.php';}
	require $mensajes_todas;
	require $mensajes_3063;
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	if (isset($aParametros[100])==0){$aParametros[100]=$_SESSION['unad_id_tercero'];}
	if (isset($aParametros[101])==0){$aParametros[101]=1;}
	if (isset($aParametros[102])==0){$aParametros[102]=20;}
	if (isset($aParametros[103])==0){$aParametros[103]='';}
	//if (isset($aParametros[104])==0){$aParametros[104]='';}
	$idTercero=$aParametros[100];
	$sDebug='';
	$pagina=$aParametros[101];
	$lineastabla=$aParametros[102];
	$bNombre=trim($aParametros[103]);
	$saiu63idmodulo=0;
	//$bListar=numeros_validar($aParametros[104]);
	$bAbierta=true;
	//$sSQL='SELECT Campo FROM Tabla WHERE Id='.$sValorId;
	//$tabla=$objDB->ejecutasql($sSQL);
	//if ($objDB->nf($tabla)>0){
		//$fila=$objDB->sf($tabla);
		//if ($fila['Campo']!='S'){$bAbierta=true;}
		//}
	$sLeyenda='';
	$sBotones='<input id="paginaf3063" name="paginaf3063" type="hidden" value="'.$pagina.'"/>
	<input id="lppf3063" name="lppf3063" type="hidden" value="'.$lineastabla.'"/>';
	if ($sLeyenda!=''){
		$sLeyenda='<div class="salto1px"></div>
		<div class="GrupoCamposAyuda">
		'.$sLeyenda.'
		<div class="salto1px"></div>
		</div>';
		return array($sLeyenda.$sBotones, $sDebug);
		die();
		}
	$iPiel=iDefinirPiel($APP, 1);
	/*
	$aEstado=array();
	$sSQL='SELECT id, nombre FROM tabla';
	$tabla=$objDB->ejecutasql($sSQL);
	while($fila=$objDB->sf($tabla)){
		$aEstado[$fila['id']]=cadena_notildes($fila['nombre']);
		}
	*/
	if (true){
		//Esta condición la ponemos para mantener la conparación con los arhcivos tipo e
		$sSQLadd='';
		$sSQLadd1='';
		//if ($aParametros[104]!=''){$sSQLadd=$sSQLadd.' AND TB.campo2 LIKE "%'.$aParametros[104].'%"';}
		//if ($aParametros[104]!=''){$sSQLadd1=$sSQLadd1.'TB.campo2 LIKE "%'.$aParametros[104].'%" AND ';}
		if ($bNombre!=''){
			$sBase=strtoupper($bNombre);
			$aNoms=explode(' ', $sBase);
			for ($k=1;$k<=count($aNoms);$k++){
				$sCadena=$aNoms[$k-1];
					if ($sCadena!=''){
					//$sSQLadd=$sSQLadd.' AND T6.unad11razonsocial LIKE "%'.$sCadena.'%"';
					$sSQLadd1=$sSQLadd1.'TB.saiu63titulo LIKE "%'.$sCadena.'%" AND ';
					}
				}
			}
		}
	//-- Area para saltar comparaciones con los archivos tipo e
	//-- Fin del area no comparada
	$sTitulos='Modulo, Consec, Id, Fecha, Estado, Titulo, Contenido, Periodo, Curso, Tutor';
	$registros=0;
	$bGigante=false; //En caso de que la tabla sea muy grande pasarlo a true
	$sLimite='';
	if ($bGigante){
		$sSQL='SELECT COUNT(1) AS Total 
		FROM saiu63mensajenotifica AS TB 
		WHERE '.$sSQLadd1.' TB.saiu63idmodulo='.$saiu63idmodulo.' '.$sSQLadd.'';
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
	$sSQL='SELECT TB.saiu63idmodulo, TB.saiu63consec, TB.saiu63id, TB.saiu63fecha, TB.saiu63estado, TB.saiu63titulo, TB.saiu63contenido, TB.saiu63periodo, TB.saiu63curso, TB.saiu63idtutor 
	FROM saiu63mensajenotifica AS TB 
	WHERE '.$sSQLadd1.' TB.saiu63idmodulo='.$saiu63idmodulo.' '.$sSQLadd.'
	ORDER BY TB.saiu63consec DESC';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_3063" name="consulta_3063" type="hidden" value="'.$sSQLlista.'"/>
	<input id="titulos_3063" name="titulos_3063" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objDB->ejecutasql($sSQL.$sLimite);
	if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 3063: '.$sSQL.$sLimite.'<br>';}
	if ($tabladetalle==false){
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		//$sLeyenda=$sSQL;
		}else{
		if (!$bGigante){
			$registros=$objDB->nf($tabladetalle);
			//if ($registros==0){
				//return array($sErrConsulta.$sBotones, $sDebug);
				//}
			if ((($registros-1)/$lineastabla)<($pagina-1)){$pagina=(int)(($registros-1)/$lineastabla)+1;}
			if ($registros>$lineastabla){
				$rbase=($pagina-1)*$lineastabla;
				$sLimite=' LIMIT '.$rbase.', '.$lineastabla;
				$tabladetalle=$objDB->ejecutasql($sSQL.$sLimite);
				}
			}
		}
	$res=$sErrConsulta.$sLeyenda;
	$res=$res.'<div class="table-responsive">
	<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">
	<thead class="fondoazul"><tr>
	<td><b>'.$ETI['saiu63consec'].'</b></td>
	<td><b>'.$ETI['saiu63fecha'].'</b></td>
	<td><b>'.$ETI['saiu63estado'].'</b></td>
	<td><b>'.$ETI['saiu63titulo'].'</b></td>
	<td align="right">
	'.html_paginador('paginaf3063', $registros, $lineastabla, $pagina, 'paginarf3063()').'
	'.html_lpp('lppf3063', $lineastabla, 'paginarf3063()').'
	</td>
	</tr></thead>';
	$tlinea=1;
	while($filadet=$objDB->sf($tabladetalle)){
		$sPrefijo='';
		$sSufijo='';
		$sClass=' class="resaltetabla"';
		$sLink='';
		switch($filadet['saiu63estado']){
			case 7:
			$sPrefijo='<b>';
			$sSufijo='</b>';
			break;
			}
		if(($tlinea%2)!=0){$sClass='';}
		$tlinea++;
		$et_saiu63fecha='';
		if ($filadet['saiu63fecha']!=0){$et_saiu63fecha=fecha_desdenumero($filadet['saiu63fecha']);}
		$et_saiu63estado=$ETI['msg_abierto'];
		if ($filadet['saiu63estado']==7){$et_saiu63estado=$ETI['msg_cerrado'];}
		if ($bAbierta){
			$sLink='<a href="javascript:cargaridf3063('.$filadet['saiu63id'].')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$res=$res.'<tr'.$sClass.'>
		<td>'.$sPrefijo.$filadet['saiu63consec'].$sSufijo.'</td>
		<td>'.$sPrefijo.$et_saiu63fecha.$sSufijo.'</td>
		<td>'.$sPrefijo.$et_saiu63estado.$sSufijo.'</td>
		<td>'.$sPrefijo.cadena_notildes($filadet['saiu63titulo']).$sSufijo.'</td>
		<td>'.$sLink.'</td>
		</tr>';
		}
	$res=$res.'</table>
	<div class="salto5px"></div>
	</div>';
	$objDB->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
	}
function f3063_HtmlTabla($aParametros){
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
	list($sDetalle, $sDebugTabla)=f3063_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f3063detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f3063_db_CargarPadre($DATA, $objDB, $bDebug=false){
	$sError='';
	$iTipoError=0;
	$sDebug='';
	require './app.php';
	if ($DATA['paso']==1){
		$sSQLcondi='saiu63idmodulo='.$DATA['saiu63idmodulo'].' AND saiu63consec='.$DATA['saiu63consec'].'';
		}else{
		$sSQLcondi='saiu63id='.$DATA['saiu63id'].'';
		}
	$sSQL='SELECT * FROM saiu63mensajenotifica WHERE '.$sSQLcondi;
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$DATA['saiu63idmodulo']=$fila['saiu63idmodulo'];
		$DATA['saiu63consec']=$fila['saiu63consec'];
		$DATA['saiu63id']=$fila['saiu63id'];
		$DATA['saiu63fecha']=$fila['saiu63fecha'];
		$DATA['saiu63estado']=$fila['saiu63estado'];
		$DATA['saiu63titulo']=$fila['saiu63titulo'];
		$DATA['saiu63contenido']=$fila['saiu63contenido'];
		$DATA['saiu63periodo']=$fila['saiu63periodo'];
		$DATA['saiu63curso']=$fila['saiu63curso'];
		$DATA['saiu63idtutor']=$fila['saiu63idtutor'];
		$DATA['saiu63tiponotifica']=$fila['saiu63tiponotifica'];
		$DATA['saiu63idzona']=$fila['saiu63idzona'];
		$DATA['saiu63idcentro']=$fila['saiu63idcentro'];
		$DATA['saiu63idescuela']=$fila['saiu63idescuela'];
		$DATA['saiu63idprograma']=$fila['saiu63idprograma'];
		$bcargo=true;
		$DATA['paso']=2;
		$DATA['boculta3063']=0;
		$bLimpiaHijos=true;
		}else{
		$DATA['paso']=0;
		}
	return array($DATA, $sError, $iTipoError, $sDebug);
	}
function f3063_Cerrar($saiu63id, $objDB, $bDebug=false){
	$sInfo='';
	$sDebug='';
	//Usamos el cerrar para generar la notificación
	$sIds11='';
	$sMensaje='';
	$iPrioridad=0;
	$aParametros=array();
	$aParametros['mensaje']=$saiu63id;
	$sIds64='-99';
	$sIds65='-99';
	$sSQL='SELECT saiu64idtercero, saiu64id FROM saiu64mensajetercero WHERE saiu64idmensaje='.$saiu63id.' AND saiu64fechaaplicado=0';
	$tabla=$objDB->ejecutasql($sSQL);
	while($fila=$objDB->sf($tabla)){
		if ($sIds11!=''){$sIds11=$sIds11.',';}
		$sIds11=$sIds11.$fila['saiu64idtercero'];
		$sIds64=$sIds64.','.$fila['saiu64id'];
		}
	$sSQL='SELECT saiu65idgrupo, saiu65id FROM saiu65mensajegrupo WHERE saiu65idmensaje='.$saiu63id.' AND saiu65fechaaplicado=0';
	$tabla=$objDB->ejecutasql($sSQL);
	while($fila=$objDB->sf($tabla)){
		//Ahora recorrer a todos los integrantes del grupo
		$sSQL='SELECT bita28idtercero FROM bita28eqipoparte WHERE bita28idequipotrab='.$fila['saiu65idgrupo'].' AND bita28activo="S"';
		$tabla28=$objDB->ejecutasql($sSQL);
		while($fila28=$objDB->sf($tabla28)){
			if ($sIds11!=''){$sIds11=$sIds11.',';}
			$sIds11=$sIds11.$fila28['bita28idtercero'];
			}
		$sIds65=$sIds65.','.$fila['saiu65id'];
		}
	if ($sIds11!=''){
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Enviando notificaciones: '.$sIds11.'<br>';}
		list($iIncluidas, $sError, $sDebugN)=f238_RegistrarNotificacion($sIds11, $sMensaje, $iPrioridad, $objDB, $aParametros, $bDebug);
		$sDebug=$sDebug.$sDebugN;
		if ($sError==''){
			$iHoy=fecha_DiaMod();
			//Ahora marcamos los registros como enviados.
			if ($sIds64!='-99'){
				$sSQL='UPDATE saiu64mensajetercero SET saiu64fechaaplicado='.$iHoy.' WHERE saiu64idmensaje='.$saiu63id.'';
				$result=$objDB->ejecutasql($sSQL);
				}
			if ($sIds65!='-99'){
				$sSQL='UPDATE saiu65mensajegrupo SET saiu65fechaaplicado='.$iHoy.' WHERE saiu65idmensaje='.$saiu63id.'';
				$result=$objDB->ejecutasql($sSQL);
				}
			$sInfo='Se han enviado '.$iIncluidas.' notificaciones.';
			}
		}
	return array($sInfo, $sDebug);
	}
function f3063_db_GuardarV2($DATA, $objDB, $bDebug=false, $idTercero=0){
	$iCodModulo=3063;
	$bAudita[2]=true;
	$bAudita[3]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3063='lg/lg_3063_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3063)){$mensajes_3063='lg/lg_3063_es.php';}
	require $mensajes_todas;
	require $mensajes_3063;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	if ($idTercero==0){$idTercero=$_SESSION['unad_id_tercero'];}
	$bCerrando=false;
	$sErrorCerrando='';
	// -- Se inicia validando todas las posibles entradas de usuario.
	/*
	if (isset($DATA['saiu63consec'])==0){$DATA['saiu63consec']='';}
	if (isset($DATA['saiu63id'])==0){$DATA['saiu63id']='';}
	if (isset($DATA['saiu63fecha'])==0){$DATA['saiu63fecha']='';}
	if (isset($DATA['saiu63estado'])==0){$DATA['saiu63estado']='';}
	if (isset($DATA['saiu63titulo'])==0){$DATA['saiu63titulo']='';}
	if (isset($DATA['saiu63contenido'])==0){$DATA['saiu63contenido']='';}
	if (isset($DATA['saiu63tiponotifica'])==0){$DATA['saiu63tiponotifica']='';}
	if (isset($DATA['saiu63idzona'])==0){$DATA['saiu63idzona']='';}
	if (isset($DATA['saiu63idcentro'])==0){$DATA['saiu63idcentro']='';}
	if (isset($DATA['saiu63idescuela'])==0){$DATA['saiu63idescuela']='';}
	if (isset($DATA['saiu63idprograma'])==0){$DATA['saiu63idprograma']='';}
	*/
	$DATA['saiu63consec']=numeros_validar($DATA['saiu63consec']);
	$DATA['saiu63titulo']=htmlspecialchars(trim($DATA['saiu63titulo']));
	//$DATA['saiu63contenido']=htmlspecialchars(trim($DATA['saiu63contenido']));
	$DATA['saiu63tiponotifica']=numeros_validar($DATA['saiu63tiponotifica']);
	$DATA['saiu63idzona']=numeros_validar($DATA['saiu63idzona']);
	$DATA['saiu63idcentro']=numeros_validar($DATA['saiu63idcentro']);
	$DATA['saiu63idescuela']=numeros_validar($DATA['saiu63idescuela']);
	$DATA['saiu63idprograma']=numeros_validar($DATA['saiu63idprograma']);
	// -- Se inicializan las variables que puedan pasar vacias {Especialmente números}.
	if ($DATA['saiu63estado']==''){$DATA['saiu63estado']=0;}
	if ($DATA['saiu63tiponotifica']==''){$DATA['saiu63tiponotifica']=0;}
	if ($DATA['saiu63idzona']==''){$DATA['saiu63idzona']=0;}
	if ($DATA['saiu63idcentro']==''){$DATA['saiu63idcentro']=0;}
	if ($DATA['saiu63idescuela']==''){$DATA['saiu63idescuela']=0;}
	if ($DATA['saiu63idprograma']==''){$DATA['saiu63idprograma']=0;}
	// -- Seccion para validar los posibles causales de error.
	$sSepara=', ';
	switch ($DATA['saiu63estado']){
		case 7:
		//if ($DATA['saiu63contenido']==''){$sError=$ERR['saiu63contenido'].$sSepara.$sError;}
		if ($DATA['saiu63titulo']==''){$sError=$ERR['saiu63titulo'].$sSepara.$sError;}
		if ($DATA['saiu63fecha']==0){
			//$DATA['saiu63fecha']=fecha_DiaMod();
			$sError=$ERR['saiu63fecha'].$sSepara.$sError;
			}
		//Fin de las valiaciones NO LLAVE.
		if ($sError!=''){$DATA['saiu63estado']=0;}
		$sErrorCerrando=$sError;
		$sError='';
		break;
		}
	//Valiaciones de campos obligatorios en todo guardar.
	// -- Tiene un cerrado.
	if ($DATA['saiu63estado']==7){
		//Validaciones previas a cerrar
		//Aprobó las Validaciones al cerrar
		if ($sError.$sErrorCerrando!=''){
			$DATA['saiu63estado']=0;
			$sErrorCerrando=$sError.' '.$sErrorCerrando;
			$sError='';
			}else{
			$bCerrando=true;
			//Acciones del cierre
			}
		}
	// -- Fin del cerrado.
	// -- Se verifican los valores de campos de otras tablas.
	$bQuitarCodigo=false;
	$sCampoCodigo='';
	if ($sError==''){
		if ($DATA['paso']==10){
			if ($DATA['saiu63consec']==''){
				$DATA['saiu63consec']=tabla_consecutivo('saiu63mensajenotifica', 'saiu63consec', 'saiu63idmodulo='.$DATA['saiu63idmodulo'].'', $objDB);
				if ($DATA['saiu63consec']==-1){$sError=$objDB->serror;}
				$bQuitarCodigo=true;
				$sCampoCodigo='saiu63consec';
				}else{
				list($bDevuelve, $sDebugP)=seg_revisa_permisoV3($iCodModulo, 8, $idTercero, $objDB);
				if (!$bDevuelve){
					$sError=$ERR['8'];
					$DATA['saiu63consec']='';
					}
				}
			if ($sError==''){
				$sSQL='SELECT 1 FROM saiu63mensajenotifica WHERE saiu63idmodulo='.$DATA['saiu63idmodulo'].' AND saiu63consec='.$DATA['saiu63consec'].'';
				$result=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($result)!=0){
					$sError=$ERR['existe'];
					}else{
					list($bDevuelve, $sDebugP)=seg_revisa_permisoV3($iCodModulo, 2, $idTercero, $objDB);
					if (!$bDevuelve){$sError=$ERR['2'];}
					}
				}
			}else{
			list($bDevuelve, $sDebugP)=seg_revisa_permisoV3($iCodModulo, 3, $idTercero, $objDB);
			if (!$bDevuelve){$sError=$ERR['3'];}
			}
		}
	if ($sError==''){
		if ($DATA['paso']==10){
			//Preparar el Id, Si no lo hay se quita la comprobación.
			$DATA['saiu63id']=tabla_consecutivo('saiu63mensajenotifica','saiu63id', '', $objDB);
			if ($DATA['saiu63id']==-1){$sError=$objDB->serror;}
			//Datos adicionales al iniciar un registro.
			$saiu63fecha=fecha_DiaMod();
			$DATA['saiu63estado']=0;
			$DATA['saiu63periodo']=0;
			$DATA['saiu63curso']=0;
			$DATA['saiu63idtutor']=0;
			}
		}
	if ($sError==''){
		$saiu63contenido=addslashes($DATA['saiu63contenido']);
		//$saiu63contenido=str_replace('"', '\"', $DATA['saiu63contenido']);
		$bPasa=false;
		if ($DATA['paso']==10){
			$sCampos3063='saiu63idmodulo, saiu63consec, saiu63id, saiu63fecha, saiu63estado, 
			saiu63titulo, saiu63contenido, saiu63periodo, saiu63curso, saiu63idtutor, 
			saiu63tiponotifica, saiu63idzona, saiu63idcentro, saiu63idescuela, saiu63idprograma';
			$sValores3063=''.$DATA['saiu63idmodulo'].', '.$DATA['saiu63consec'].', '.$DATA['saiu63id'].', '.$DATA['saiu63fecha'].', '.$DATA['saiu63estado'].', 
			"'.$DATA['saiu63titulo'].'", "'.$saiu63contenido.'", '.$DATA['saiu63periodo'].', '.$DATA['saiu63curso'].', '.$DATA['saiu63idtutor'].', 
			'.$DATA['saiu63tiponotifica'].', '.$DATA['saiu63idzona'].', '.$DATA['saiu63idcentro'].', '.$DATA['saiu63idescuela'].', '.$DATA['saiu63idprograma'].'';
			if ($APP->utf8==1){
				$sSQL='INSERT INTO saiu63mensajenotifica ('.$sCampos3063.') VALUES ('.utf8_encode($sValores3063).');';
				$sdetalle=$sCampos3063.'['.utf8_encode($sValores3063).']';
				}else{
				$sSQL='INSERT INTO saiu63mensajenotifica ('.$sCampos3063.') VALUES ('.$sValores3063.');';
				$sdetalle=$sCampos3063.'['.$sValores3063.']';
				}
			$idAccion=2;
			$bPasa=true;
			}else{
			$scampo[1]='saiu63fecha';
			$scampo[2]='saiu63estado';
			$scampo[3]='saiu63titulo';
			$scampo[4]='saiu63contenido';
			$scampo[5]='saiu63tiponotifica';
			$scampo[6]='saiu63idzona';
			$scampo[7]='saiu63idcentro';
			$scampo[8]='saiu63idescuela';
			$scampo[9]='saiu63idprograma';
			$sdato[1]=$DATA['saiu63fecha'];
			$sdato[2]=$DATA['saiu63estado'];
			$sdato[3]=$DATA['saiu63titulo'];
			$sdato[4]=$saiu63contenido;
			$sdato[5]=$DATA['saiu63tiponotifica'];
			$sdato[6]=$DATA['saiu63idzona'];
			$sdato[7]=$DATA['saiu63idcentro'];
			$sdato[8]=$DATA['saiu63idescuela'];
			$sdato[9]=$DATA['saiu63idprograma'];
			$numcmod=9;
			$sWhere='saiu63id='.$DATA['saiu63id'].'';
			$sSQL='SELECT * FROM saiu63mensajenotifica WHERE '.$sWhere;
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
						$bPasa=true;
						}
					}
				}
			if ($bPasa){
				if ($APP->utf8==1){
					$sdetalle=utf8_encode($sdatos).'['.$sWhere.']';
					$sSQL='UPDATE saiu63mensajenotifica SET '.utf8_encode($sdatos).' WHERE '.$sWhere.';';
					}else{
					$sdetalle=$sdatos.'['.$sWhere.']';
					$sSQL='UPDATE saiu63mensajenotifica SET '.$sdatos.' WHERE '.$sWhere.';';
					}
				$idAccion=3;
				}
			}
		if ($bPasa){
			if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Guardar 3063 '.$sSQL.'<br>';}
			$result=$objDB->ejecutasql($sSQL);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' [3063] ..<!-- '.$sSQL.' -->';
				if ($idAccion==2){
					$DATA['saiu63id']='';
					$bQuitarCodigo=true;
					$DATA['paso']=0;
					}else{
					$DATA['paso']=2;
					}
				$bCerrando=false;
				}else{
				if ($bAudita[$idAccion]){seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], $idAccion, $DATA['saiu63id'], $sdetalle, $objDB);}
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
		list($sErrorCerrando, $sDebugCerrar)=f3063_Cerrar($DATA['saiu63id'], $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugCerrar;
		}
	//if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' InfoDepura<br>';}
	return array($DATA, $sError, $iTipoError, $bCerrando, $sErrorCerrando, $sDebug);
	}
function f3063_db_Eliminar($saiu63id, $objDB, $bDebug=false){
	$iCodModulo=3063;
	$bAudita[4]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3063='lg/lg_3063_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3063)){$mensajes_3063='lg/lg_3063_es.php';}
	require $mensajes_todas;
	require $mensajes_3063;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	$saiu63id=numeros_validar($saiu63id);
	// Traer los datos para hacer las validaciones.
	if ($sError==''){
		$sSQL='SELECT * FROM saiu63mensajenotifica WHERE saiu63id='.$saiu63id.'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$filabase=$objDB->sf($tabla);
			}else{
			$sError='No se encuentra el registro solicitado {Ref: '.$saiu63id.'}';
			}
		}
	if ($sError==''){
		if (isset($idTercero)==0){$idTercero=$_SESSION['unad_id_tercero'];}
		list($bDevuelve, $sDebugP)=seg_revisa_permisoV3($iCodModulo, 4, $idTercero, $objDB);
		if (!$bDevuelve){
			$sError=$ERR['4'];
			}
		}
	if ($sError==''){
		$sSQL='SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=3063';
		$tablaor=$objDB->ejecutasql($sSQL);
		while ($filaor=$objDB->sf($tablaor)){
			$sSQL='SELECT '.$filaor['campoid'].' FROM '.$filaor['tabla'].' WHERE '.$filaor['camporev'].'='.$_REQUEST['saiu63id'].' LIMIT 0, 1';
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
		$sSQL='DELETE FROM saiu64mensajetercero WHERE saiu64idmensaje='.$filabase['saiu63id'].'';
		$tabla=$objDB->ejecutasql($sSQL);
		$sSQL='DELETE FROM saiu65mensajegrupo WHERE saiu65idmensaje='.$filabase['saiu63id'].'';
		$tabla=$objDB->ejecutasql($sSQL);
		$sWhere='saiu63id='.$saiu63id.'';
		//$sWhere='saiu63consec='.$filabase['saiu63consec'].' AND saiu63idmodulo='.$filabase['saiu63idmodulo'].'';
		$sSQL='DELETE FROM saiu63mensajenotifica WHERE '.$sWhere.';';
		$result=$objDB->ejecutasql($sSQL);
		if ($result==false){
			$sError=$ERR['falla_eliminar'].' .. <!-- '.$sSQL.' -->';
			}else{
			if ($bAudita[4]){seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $saiu63id, $sWhere, $objDB);}
			}
		}
	return array($sError, $iTipoError, $sDebug);
	}
function f3063_TituloBusqueda(){
	return 'Busqueda de Mensajes para notificaciones';
	}
function f3063_ParametrosBusqueda(){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3063='lg/lg_3063_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3063)){$mensajes_3063='lg/lg_3063_es.php';}
	require $mensajes_todas;
	require $mensajes_3063;
	$sParams='<label class="Label90">
	'.$ETI['msg_bnombre'].'
	</label>
	<label>
	<input id="b3063nombre" name="b3063nombre" type="text" value="" onchange="paginarbusqueda()" />
	</label>';
	return $sParams;
	}
function f3063_JavaScriptBusqueda($iModuloBusca){
	$sRes='var sCampo=window.document.frmedita.scampobusca.value;
	var params=new Array();
	params[100]=sCampo;
	params[101]=window.document.frmedita.paginabusqueda.value;
	params[102]=window.document.frmedita.lppfbusqueda.value;
	params[103]=window.document.frmedita.b3063nombre.value;
	xajax_f'.$iModuloBusca.'_HtmlBusqueda(params);';
	return $sRes;
	}
function f3063_TablaDetalleBusquedas($aParametros, $objDB){
	$res='';
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3063='lg/lg_3063_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3063)){$mensajes_3063='lg/lg_3063_es.php';}
	require $mensajes_todas;
	require $mensajes_3063;
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	if (isset($aParametros[100])==0){$aParametros[100]=$_SESSION['unad_id_tercero'];}
	if (isset($aParametros[101])==0){$aParametros[101]=1;}
	if (isset($aParametros[102])==0){$aParametros[102]=20;}
	if (isset($aParametros[103])==0){$aParametros[103]='';}
	if (isset($aParametros[104])==0){$aParametros[104]='';}
	$idTercero=$aParametros[100];
	$pagina=$aParametros[101];
	$lineastabla=$aParametros[102];
	//$bNombre=trim($aParametros[103]);
	//$bListar=numeros_validar($aParametros[104]);
	$bAbierta=true;
	//$sSQL='SELECT Campo FROM Tabla WHERE Id='.$sValorId;
	//$tabla=$objDB->ejecutasql($sSQL);
	//if ($objDB->nf($tabla)>0){
		//$fila=$objDB->sf($tabla);
		//if ($fila['Campo']!='S'){$bAbierta=true;}
		//}
	$sLeyenda='';
	$sBotones='<input id="paginaf3063" name="paginaf3063" type="hidden" value="'.$pagina.'"/>
	<input id="lppf3063" name="lppf3063" type="hidden" value="'.$lineastabla.'"/>';
	if ($sLeyenda!=''){
		$sLeyenda='<div class="salto1px"></div>
		<div class="GrupoCamposAyuda">
		'.$sLeyenda.'
		<div class="salto1px"></div>
		</div>';
		return array($sLeyenda.$sBotones, $sDebug);
		die();
		}
	$iPiel=iDefinirPiel($APP, 1);
	$sSQLadd='1';
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
	$sTitulos='Modulo, Consec, Id, Fecha, Estado, Titulo, Contenido, Periodo, Curso, Tutor';
	$sSQL='SELECT TB.saiu63idmodulo, TB.saiu63consec, TB.saiu63id, TB.saiu63fecha, TB.saiu63estado, TB.saiu63titulo, TB.saiu63contenido, TB.saiu63periodo, TB.saiu63curso, TB.saiu63idtutor 
	FROM saiu63mensajenotifica AS TB 
	WHERE '.$sSQLadd1.'  '.$sSQLadd.'
	ORDER BY TB.saiu63idmodulo, TB.saiu63consec';
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
		//if ($registros==0){
			//return array($sErrConsulta.$sBotones, $sDebug);
			//}
		if ((($registros-1)/$lineastabla)<($pagina-1)){$pagina=(int)(($registros-1)/$lineastabla)+1;}
		if ($registros>$lineastabla){
			$rbase=($pagina-1)*$lineastabla;
			$limite=' LIMIT '.$rbase.', '.$lineastabla;
			$tabladetalle=$objDB->ejecutasql($sSQL.$limite);
			}
		}
	$res=$sErrConsulta.$sLeyenda;
	$res=$res.'<div class="table-responsive">
	<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">
	<thead class="fondoazul"><tr>
	<td><b>'.$ETI['saiu63idmodulo'].'</b></td>
	<td><b>'.$ETI['saiu63consec'].'</b></td>
	<td><b>'.$ETI['saiu63fecha'].'</b></td>
	<td><b>'.$ETI['saiu63estado'].'</b></td>
	<td><b>'.$ETI['saiu63titulo'].'</b></td>
	<td><b>'.$ETI['saiu63contenido'].'</b></td>
	<td><b>'.$ETI['saiu63periodo'].'</b></td>
	<td><b>'.$ETI['saiu63curso'].'</b></td>
	<td><b>'.$ETI['saiu63idtutor'].'</b></td>
	<td align="right">
	'.html_paginador('paginabusqueda', $registros, $lineastabla, $pagina, 'paginarbusqueda()').'
	'.html_lpp('lppfbusqueda', $lineastabla, 'paginarbusqueda()').'
	</td>
	</tr></thead>';
	$tlinea=1;
	while($filadet=$objDB->sf($tabladetalle)){
		$sPrefijo='<a href="javascript:Devuelve(\''.$filadet['saiu63id'].'\');">';
		$sSufijo='</a>';
		$tlinea++;
		$et_saiu63fecha='';
		if ($filadet['saiu63fecha']!=0){$et_saiu63fecha=fecha_desdenumero($filadet['saiu63fecha']);}
		$et_saiu63estado=$ETI['msg_abierto'];
		if ($filadet['saiu63estado']==7){$et_saiu63estado=$ETI['msg_cerrado'];}
		$res=$res.'<tr onmouseover="cambia_color_over(this);" onmouseout="cambia_color_out(this);">
		<td>'.$sPrefijo.$filadet['saiu63idmodulo'].$sSufijo.'</td>
		<td>'.$sPrefijo.$filadet['saiu63consec'].$sSufijo.'</td>
		<td>'.$sPrefijo.$et_saiu63fecha.$sSufijo.'</td>
		<td>'.$sPrefijo.$et_saiu63estado.$sSufijo.'</td>
		<td>'.$sPrefijo.cadena_notildes($filadet['saiu63titulo']).$sSufijo.'</td>
		<td>'.$sPrefijo.$filadet['saiu63contenido'].$sSufijo.'</td>
		<td>'.$sPrefijo.$filadet['saiu63periodo'].$sSufijo.'</td>
		<td>'.$sPrefijo.$filadet['saiu63curso'].$sSufijo.'</td>
		<td>'.$sPrefijo.$filadet['saiu63idtutor'].$sSufijo.'</td>
		<td></td>
		</tr>';
		}
	$res=$res.'</table>
	<div class="salto5px"></div>
	</div>';
	$objDB->liberar($tabladetalle);
	return utf8_encode($res);
	}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------
?>