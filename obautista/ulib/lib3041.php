<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2021 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.25.10b lunes, 25 de enero de 2021
--- Modelo Versión 2.25.10c martes, 9 de febrero de 2021
--- Modelo Versión 2.25.10c lunes, 15 de febrero de 2021
--- 3041 saiu41docente
*/
/** Archivo lib3041.php.
* Libreria 3041 saiu41docente.
* @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
* @date lunes, 25 de enero de 2021
*/
function f3041_HTMLComboV2_saiu41tipocontacto($objDB, $objCombos, $valor){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('saiu41tipocontacto', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	//$objCombos->iAncho=450;
	$sSQL='SELECT ceca26id AS id, ceca26nombre AS nombre FROM ceca26tipoacompana';
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f3041_HTMLComboV2_saiu41idperiodo($objDB, $objCombos, $valor, $vrsaiu41idestudiante){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('saiu41idperiodo', $valor, true, '{'.$ETI['msg_na'].'}', 0);
	$objCombos->sAccion='carga_combo_saiu41idcurso()';
	$sCondi='exte02id=-99';
	if ((int)$vrsaiu41idestudiante!=0){
		$sSQL='SELECT core16peraca FROM core16actamatricula WHERE core16tercero='.$vrsaiu41idestudiante.' AND core16peraca>765';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$objCombos->iAncho=400;
			$sCondi='-99';
			while($fila=$objDB->sf($tabla)){
				$sCondi=$sCondi.','.$fila['core16peraca'];
				}
			$sCondi='exte02id IN ('.$sCondi.')';
			}
		}
	$sSQL=f146_ConsultaCombo($sCondi);
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f3041_HTMLComboV2_saiu41idcurso($objDB, $objCombos, $valor, $vrsaiu41idperiodo, $idEstudiante){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('saiu41idcurso', $valor, true, '{'.$ETI['msg_na'].'}', 0);
	$objCombos->sAccion='carga_combo_saiu41idactividad()';
	$sSQL='';
	if ((int)$vrsaiu41idperiodo!=0){
		if ((int)$idEstudiante!=0){
			$objCombos->iAncho=400;
			$sIds40='-99';
			list($idContenedor, $sError)=f1011_BloqueTercero($idEstudiante, $objDB);
			$sSQL='SELECT T40.unad40id, T40.unad40titulo, T40.unad40nombre, TB.core04estado, TB.core04resultado, TB.core04numintento 
			FROM core04matricula_'.$idContenedor.' AS TB, unad40curso AS T40 
			WHERE TB.core04tercero='.$idEstudiante.' AND TB.core04peraca='.$vrsaiu41idperiodo.' AND TB.core04estado<>1
			AND TB.core04idcurso=T40.unad40id
			ORDER BY TB.core04estado, T40.unad40titulo';
			$tabla=$objDB->ejecutasql($sSQL);
			while($fila=$objDB->sf($tabla)){
				$sEstado='';
				$sClase='';
				$sIntento='';
				switch($fila['core04estado']){
					case 9:
					$sEstado=' [CANCELADO]';
					$sClase='color:#FF0000';
					break;
					case 10:
					$sEstado=' [APLAZADO]';
					$sClase='color:#FF0000';
					break;
					}
				if ($fila['core04numintento']>1){
					$sIntento=' [Repetici&oacute;n '.($fila['core04numintento']-1).']';
					}
				$sEtiqueta=$fila['unad40titulo'].' -'.' '.cadena_notildes($fila['unad40nombre']).$sEstado.$sIntento;
				$objCombos->addItem($fila['unad40id'], $sEtiqueta, $sClase);
				}
			}
		}
	$res=$objCombos->html('', $objDB);
	return $res;
	}
function f3041_HTMLComboV2_saiu41idactividad($objDB, $objCombos, $valor, $vrsaiu41idcurso, $vrsaiu41idperiodo, $idEstudiante){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('saiu41idactividad', $valor, true, '{'.$ETI['msg_na'].'}', 0);
	//$objCombos->iAncho=450;
	$sSQL='';
	if ((int)$vrsaiu41idcurso!=0){
		if ((int)$vrsaiu41idperiodo!=0){
			if ((int)$idEstudiante!=0){
				$objCombos->iAncho=400;
				list($idContenedor, $sError)=f1011_BloqueTercero($idEstudiante, $objDB);
				//core05estado, core05puntaje75, core05puntaje25, core05nota
				$sSQL='SELECT T4.ofer04id AS id, CONCAT(T4.ofer04nombre, " [", T3.ceca03nombre, "] ", TRUNCATE(TB.core05nota, 0), "/", (TB.core05puntaje75+TB.core05puntaje25)) AS nombre 
				FROM core05actividades_'.$idContenedor.' AS TB, ofer04cursoactividad AS T4, ceca03estadoactividad AS T3
				WHERE TB.core05tercero='.$idEstudiante.' AND TB.core05idcurso='.$vrsaiu41idcurso.' AND TB.core05peraca='.$vrsaiu41idperiodo.'
				AND TB.core05idactividad=T4.ofer04id AND TB.core05estado=T3.ceca03id
				ORDER BY T4.ofer04nombre';
				}
			}
		}
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f3041_Combosaiu41idperiodo($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$objCombos=new clsHtmlCombos();
	$html_saiu41idperiodo=f3041_HTMLComboV2_saiu41idperiodo($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_saiu41idperiodo', 'innerHTML', $html_saiu41idperiodo);
	//$objResponse->call('$("#saiu41idperiodo").chosen()');
	return $objResponse;
	}
function f3041_Combosaiu41idcurso($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	if (isset($aParametros[1])==0){$aParametros[1]='';}
	$objCombos=new clsHtmlCombos();
	$html_saiu41idcurso=f3041_HTMLComboV2_saiu41idcurso($objDB, $objCombos, '', $aParametros[0], $aParametros[1]);
	$html_saiu41idactividad=f3041_HTMLComboV2_saiu41idactividad($objDB, $objCombos, '', '', $aParametros[0], $aParametros[1]);
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_saiu41idcurso', 'innerHTML', $html_saiu41idcurso);
	$objResponse->assign('div_saiu41idactividad', 'innerHTML', $html_saiu41idactividad);
	//$objResponse->call('$("#saiu41idcurso").chosen()');
	return $objResponse;
	}
function f3041_Combosaiu41idactividad($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	if (isset($aParametros[1])==0){$aParametros[1]='';}
	if (isset($aParametros[2])==0){$aParametros[2]='';}
	$objCombos=new clsHtmlCombos();
	$html_saiu41idactividad=f3041_HTMLComboV2_saiu41idactividad($objDB, $objCombos, '', $aParametros[0], $aParametros[1], $aParametros[2]);
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_saiu41idactividad', 'innerHTML', $html_saiu41idactividad);
	//$objResponse->call('$("#saiu41idactividad").chosen()');
	return $objResponse;
	}
function f3041_ExisteDato($datos){
	if(!is_array($datos)){$datos=json_decode(str_replace('\"','"',$datos),true);}
	$_SESSION['u_ultimominuto']=iminutoavance();
	$bHayLlave=true;
	$saiu41idestudiante=numeros_validar($datos[1]);
	if ($saiu41idestudiante==''){$bHayLlave=false;}
	$saiu41consec=numeros_validar($datos[2]);
	if ($saiu41consec==''){$bHayLlave=false;}
	if ($bHayLlave){
		require './app.php';
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		list($idContTercero, $sErrorE)=f1011_BloqueTercero($saiu41idestudiante, $objDB);
		$sTabla41='saiu41docente_'.$idContTercero;
		$sSQL='SELECT 1 FROM '.$sTabla41.' WHERE saiu41idestudiante='.$saiu41idestudiante.' AND saiu41consec='.$saiu41consec.'';
		$res=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($res)==0){$bHayLlave=false;}
		$objDB->CerrarConexion();
		$objResponse=new xajaxResponse();
		$objResponse->call("MensajeAlarmaV2('".$sSQL."', 1)");
		if ($bHayLlave){
			$objResponse->call('cambiapaginaV2');
			}else{
			$objResponse->call('f3041_InfoInteresado');
			}
		return $objResponse;
		}
	}
function f3041_Busquedas($aParametros){
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3041=$APP->rutacomun.'lg/lg_3041_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3041)){$mensajes_3041=$APP->rutacomun.'lg/lg_3041_es.php';}
	require $mensajes_todas;
	require $mensajes_3041;
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
		case 'saiu41idestudiante':
		require $APP->rutacomun.'lib111.php';
		$sTabla=f111_TablaDetalleBusquedas($aParametrosB, $objDB);
		$sTitulo='Busqueda de terceros';
		$sParams=f111_ParametrosBusqueda();
		$sJavaBusqueda=f111_JavaScriptBusqueda(3041);
		break;
		case 'saiu41idtutor':
		require $APP->rutacomun.'lib111.php';
		$sTabla=f111_TablaDetalleBusquedas($aParametrosB, $objDB);
		$sTitulo='Busqueda de terceros';
		$sParams=f111_ParametrosBusqueda();
		$sJavaBusqueda=f111_JavaScriptBusqueda(3041);
		break;
		}
	$sTitulo='<h2>'.$ETI['titulo_3041'].' - '.$sTitulo.'</h2>';
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97titulo', 'innerHTML', $sTitulo);
	$objResponse->assign('div_97params', 'innerHTML', $sParams);
	$objResponse->assign('div_97tabla', 'innerHTML', $sTabla);
	$objResponse->setFunction('paginarbusqueda','',$sJavaBusqueda);
	$objResponse->call('expandesector(97)');
	return $objResponse;
	}
function f3041_HtmlBusqueda($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$sError='';
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$sDetalle='';
	switch($aParametros[100]){
		case 'saiu41idestudiante':
		require $APP->rutacomun.'lib111.php';
		$sDetalle=f111_TablaDetalleBusquedas($aParametros, $objDB);
		break;
		case 'saiu41idtutor':
		require $APP->rutacomun.'lib111.php';
		$sDetalle=f111_TablaDetalleBusquedas($aParametros, $objDB);
		break;
		}
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97tabla', 'innerHTML', $sDetalle);
	return $objResponse;
	}
function f3041_TablaDetalleV2($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3041=$APP->rutacomun.'lg/lg_3041_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3041)){$mensajes_3041=$APP->rutacomun.'lg/lg_3041_es.php';}
	require $mensajes_todas;
	require $mensajes_3041;
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
	//$aParametros[104]=numeros_validar($aParametros[104]);
	$idTercero=$aParametros[100];
	$sDebug='';
	$pagina=$aParametros[101];
	$lineastabla=$aParametros[102];
	$bNombre=trim($aParametros[103]);
	$bListar=$aParametros[104];
	$bDoc=trim($aParametros[105]);
	$bPeriodo=$aParametros[106];
	$bCodCurso=trim($aParametros[107]);
	$bAbierta=true;
	//$sSQL='SELECT Campo FROM Tabla WHERE Id='.$sValorId;
	//$tabla=$objDB->ejecutasql($sSQL);
	//if ($objDB->nf($tabla)>0){
		//$fila=$objDB->sf($tabla);
		//if ($fila['Campo']!='S'){$bAbierta=true;}
		//}
	$sLeyenda='';
	$sBotones='<input id="paginaf3041" name="paginaf3041" type="hidden" value="'.$pagina.'"/>
	<input id="lppf3041" name="lppf3041" type="hidden" value="'.$lineastabla.'"/>';
	switch($bListar){
		case 2: //Mis acompañamientos pendientes.
		case 3: //Pendientes
		case 4: //Con voluntad de retiro
		break;
		default:
		if ((int)$bPeriodo==0){$sLeyenda='Debe seleccionar un periodo a consultar.';}
		break;
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
	$iBase=0;//Nos basamos en los terceros.
	$sIds='-99';
	$sTabla11='';
	$sTabla40='';
	$sCondi04='';
	$sCondi41='';
	$sCondiUnion='';
	$sCondiUnion2='';
	switch($bListar){
		case 1: //Donde es tutor
		$sCondi04=' AND TB.core04idtutor='.$idTercero.'';
		break;
		case 2: //Mis acompañamientos pendientes.
		case 3: //Pendientes
		$iBase=$bListar;
		break;
		case 4: //Con voluntad de retiro
		$iBase=1;//Nos basamos en la matricula.
		break;
		default:
		if ($bCodCurso==''){
			$iBase=1;//Nos basamos en la matricula.
			}
		break;
		}
	if ($bNombre!=''){
		switch($iBase){
			case 0:
			case 2:
			case 3:
			$sTabla11=', unad11terceros AS T11';
			$sCondiUnion=' AND TB.core04tercero=T11.unad11id';
			break;
			default:
			//$sCondiUnion=' AND TB.core16tercero=T11.unad11id';
			break;
			}
		$sBase=trim(strtoupper($bNombre));
		$aNoms=explode(' ', $sBase);
		for ($k=1;$k<=count($aNoms);$k++){
			$sCadena=$aNoms[$k-1];
			if ($sCadena!=''){
				$sCondiUnion=$sCondiUnion.' AND T11.unad11razonsocial LIKE "%'.$sCadena.'%"';
				//$sSQLadd1=$sSQLadd1.'T1.unad11razonsocial LIKE "%'.$sCadena.'%" AND ';
				}
			}
		}
	if ($bCodCurso!=''){
		$sTabla40=', unad40curso AS T40';
		$sCondiUnion2=' AND TB.core04idcurso=T40.unad40id AND T40.unad40titulo LIKE "%'.$bCodCurso.'%"';
		}
	if ($bDoc!=''){$sCondiUnion=$sCondiUnion.' AND T11.unad11doc LIKE "%'.$bDoc.'%"';}
	switch($iBase){
		case 0: //Los que es tutor.
		$sSQL='SHOW TABLES LIKE "core04%"';
		$tablac=$objDB->ejecutasql($sSQL);
		while($filac=$objDB->sf($tablac)){
			$iContenedor=substr($filac[0], 16);
			if ($iContenedor!=0){
				$sSQL='SELECT TB.core04tercero 
				FROM core04matricula_'.$iContenedor.' AS TB'.$sTabla11.$sTabla40.'
				WHERE TB.core04peraca='.$bPeriodo.$sCondi04.$sCondiUnion.$sCondiUnion2.' 
				GROUP BY TB.core04tercero ';
				if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Contenerdor '.$iContenedor.': '.$sSQL.'<br>';}
				$tabla=$objDB->ejecutasql($sSQL);
				while($fila=$objDB->sf($tabla)){
					$sIds=$sIds.','.$fila['core04tercero'];
					}
				}
			}
		$sCondiUnion='';
		break;
		case 2: //Los que tengo pendientes.
		case 3: //Todos los pendientes
		$sCondiAdd='TB.saiu41idtutor='.$idTercero.' AND ';
		if ($iBase==3){$sCondiAdd='';}
		$sSQL='SHOW TABLES LIKE "core04%"';
		$tablac=$objDB->ejecutasql($sSQL);
		while($filac=$objDB->sf($tablac)){
			$iContenedor=substr($filac[0], 16);
			if ($iContenedor!=0){
				$sSQL='SELECT TB.saiu41idestudiante 
				FROM saiu41docente_'.$iContenedor.' AS TB
				WHERE '.$sCondiAdd.' TB.saiu41cerrada=0';
				if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Base '.$iBase.' Contenerdor '.$iContenedor.': '.$sSQL.'<br>';}
				$tabla=$objDB->ejecutasql($sSQL);
				while($fila=$objDB->sf($tabla)){
					$sIds=$sIds.','.$fila['saiu41idestudiante'];
					}
				}
			}
		break;
		case 4:
		break;
		default:
		break;
		}
	$sSQLadd='';
	$sSQLadd1='';
	//if ($aParametros[104]!=''){$sSQLadd=$sSQLadd.' AND TB.campo2 LIKE "%'.$aParametros[104].'%"';}
	/*
	*/
	$sTitulos='Estudiante, Consec, Id, Tipocontacto, Fecha, Cerrada, Periodo, Curso, Actividad, Tutor, Visiblealest, Contacto_efectivo, Contacto_forma, Contacto_observa, Seretira, Factorprincipaldesc';
	$registros=0;
	$bGigante=false; //En caso de que la tabla sea muy grande pasarlo a true
	$sLimite='';
	if ($bGigante){
		$sSQL='SELECT COUNT(1) AS Total 
		FROM unad11terceros AS TB
		WHERE unad11id IN ('.$sIds.')';
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
	$sTablaBase='';
	$sCondiBase='T11.unad11id IN ('.$sIds.')';
	switch($iBase){
		case 0: //Los que es tutor.
		case 2: //Mis pendientes
		case 3: //Los pendientes
		break;
		default:
		$sTablaBase='core16actamatricula AS TC, ';
		$sCondiBase='TC.core16peraca='.$bPeriodo.' AND TC.core16tercero=T11.unad11id';
		break;
		}
	$sSQL='SELECT T11.unad11id, T11.unad11tipodoc, T11.unad11doc, T11.unad11razonsocial, T11.unad11idtablero, T11.unad11fechaultingreso
	FROM '.$sTablaBase.'unad11terceros AS T11
	WHERE '.$sCondiBase.''.$sCondiUnion.'
	ORDER BY T11.unad11doc';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_3041" name="consulta_3041" type="hidden" value="'.$sSQLlista.'"/>
	<input id="titulos_3041" name="titulos_3041" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objDB->ejecutasql($sSQL.$sLimite);
	if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 3041: '.$sSQL.$sLimite.'<br>';}
	if ($tabladetalle==false){
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		//$sLeyenda=$sSQL;
		}else{
		if (!$bGigante){
			$registros=$objDB->nf($tabladetalle);
			if ($registros==0){
				//return array(utf8_encode($sErrConsulta.'<input id="paginaf3041" name="paginaf3041" type="hidden" value="'.$pagina.'"/><input id="lppf3041" name="lppf3041" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
				}
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
	<td colspan="2"><b>'.$ETI['saiu41idestudiante'].'</b></td>
	<td><b>'.$ETI['msg_ultacceso'].'</b></td>
	<td colspan="2" align="right">
	'.html_paginador('paginaf3041', $registros, $lineastabla, $pagina, 'paginarf3041()').'
	'.html_lpp('lppf3041', $lineastabla, 'paginarf3041()').'
	</td>
	</tr></thead>';
	$tlinea=1;
	while($filadet=$objDB->sf($tabladetalle)){
		$sPrefijo='';
		$sSufijo='';
		$sClass=' class="resaltetabla"';
		$sLink='';
		$sPrevios='';
		if(($tlinea%2)!=0){$sClass='';}
		$tlinea++;
		$et_saiu41idestudiante_doc='';
		$et_saiu41idestudiante_nombre='';
		if ($filadet['unad11id']!=0){
			$et_saiu41idestudiante_doc=$sPrefijo.$filadet['unad11tipodoc'].' '.$filadet['unad11doc'].$sSufijo;
			$et_saiu41idestudiante_nombre=$sPrefijo.cadena_notildes($filadet['unad11razonsocial']).$sSufijo;
			}
		$et_ultacceso='';
		if ($filadet['unad11fechaultingreso']!=0){
			$et_ultacceso=fecha_desdenumero($filadet['unad11fechaultingreso']);
			}
		if ($bAbierta){
			$sLink='<a href="javascript:nuevo3041('.$filadet['unad11id'].')" class="lnkresalte">'.$ETI['lnk_nuevo'].'</a>';
			}
		$sSQL='SELECT TB.saiu41consec, TB.saiu41id, TB.saiu41cerrada, TB.saiu41fecha 
		FROM saiu41docente_'.$filadet['unad11idtablero'].' AS TB
		WHERE TB.saiu41idestudiante='.$filadet['unad11id'].' 
		ORDER BY TB.saiu41fecha';
		//if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Contenerdor '.$iContenedor.': '.$sSQL.'<br>';}
		$tabla=$objDB->ejecutasql($sSQL);
		while($fila=$objDB->sf($tabla)){
			$sPrevios=$sPrevios.'<a href="javascript:cargaridf3041('.$filadet['unad11idtablero'].', '.$fila['saiu41id'].')" class="lnkresalte">'.$fila['saiu41consec'].'</a> ';
			}
		$res=$res.'<tr'.$sClass.'>
		<td>'.$et_saiu41idestudiante_doc.'</td>
		<td>'.$et_saiu41idestudiante_nombre.'</td>
		<td>'.$et_ultacceso.'</td>
		<td>'.$sPrevios.'</td>
		<td>'.$sLink.'</td>
		</tr>';
		}
	$res=$res.'</table>
	<div class="salto5px"></div>
	</div>';
	$objDB->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
	}
function f3041_HtmlTabla($aParametros){
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
	list($sDetalle, $sDebugTabla)=f3041_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f3041detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f3041_db_CargarPadre($DATA, $objDB, $bDebug=false){
	$sError='';
	$iTipoError=0;
	$sDebug='';
	require './app.php';
	$DATA['saiu41idestudiante_td']=$APP->tipo_doc;
	$DATA['saiu41idestudiante_doc']='';
	$DATA['saiu41idtutor_td']=$APP->tipo_doc;
	$DATA['saiu41idtutor_doc']='';
	list($idContTercero, $sError)=f1011_BloqueTercero($DATA['saiu41idestudiante'], $objDB);
	$sTabla41='saiu41docente_'.$idContTercero;
	if ($DATA['paso']==1){
		$sSQLcondi='saiu41idestudiante="'.$DATA['saiu41idestudiante'].'" AND saiu41consec='.$DATA['saiu41consec'].'';
		}else{
		$sSQLcondi='saiu41id='.$DATA['saiu41id'].'';
		}
	$sSQL='SELECT * FROM '.$sTabla41.' WHERE '.$sSQLcondi;
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$DATA['saiu41idestudiante']=$fila['saiu41idestudiante'];
		$DATA['saiu41consec']=$fila['saiu41consec'];
		$DATA['saiu41id']=$fila['saiu41id'];
		$DATA['saiu41tipocontacto']=$fila['saiu41tipocontacto'];
		$DATA['saiu41fecha']=$fila['saiu41fecha'];
		$DATA['saiu41cerrada']=$fila['saiu41cerrada'];
		$DATA['saiu41idperiodo']=$fila['saiu41idperiodo'];
		$DATA['saiu41idcurso']=$fila['saiu41idcurso'];
		$DATA['saiu41idactividad']=$fila['saiu41idactividad'];
		$DATA['saiu41idtutor']=$fila['saiu41idtutor'];
		$DATA['saiu41visiblealest']=$fila['saiu41visiblealest'];
		$DATA['saiu41contacto_efectivo']=$fila['saiu41contacto_efectivo'];
		$DATA['saiu41contacto_forma']=$fila['saiu41contacto_forma'];
		$DATA['saiu41contacto_observa']=$fila['saiu41contacto_observa'];
		$DATA['saiu41seretira']=$fila['saiu41seretira'];
		$DATA['saiu41factorprincipaldesc']=$fila['saiu41factorprincipaldesc'];
		$DATA['saiu41motivocontacto']=$fila['saiu41motivocontacto'];
		$DATA['saiu41acciones']=$fila['saiu41acciones'];
		$DATA['saiu41resultados']=$fila['saiu41resultados'];
		$bcargo=true;
		$DATA['paso']=2;
		$DATA['boculta3041']=0;
		$bLimpiaHijos=true;
		}else{
		$DATA['paso']=0;
		}
	return array($DATA, $sError, $iTipoError, $sDebug);
	}
function f3041_Cerrar($idContTercero, $saiu41id, $objDB, $bDebug=false){
	$sInfo='';
	$sDebug='';
	return array($sInfo, $sDebug);
	}
function f3041_MarcarRetiro($idContTercero, $id41, $objDB, $bDebug=false){
	//9 Retirado y 6 Desertor.
	//Esta funcion llama a la libcore.php
	$sError='';
	$sDebug='';
	$sTabla41='saiu41docente_'.$idContTercero;
	$sSQL='SELECT saiu41idestudiante, saiu41idestprog, saiu41fecha, saiu41idtutor, saiu41seretira, saiu41factorprincipaldesc, 
	saiu41contacto_observa 
	FROM '.$sTabla41.' 
	WHERE saiu41id='.$id41.'';
	if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().'<b>Marcar Retiro</b> Consultando Registro '.$sSQL.'<br>';}
	$tablab=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tablab)>0){
		$filab=$objDB->sf($tablab);
		$sMotivoDeserta=$filab['saiu41contacto_observa'];
		$iFechaReg=$filab['saiu41fecha'];
		if ($filab['saiu41seretira']!=1){$sError='No aplica';}
		}else{
		$sError='No se ha encontrado el registro solicitado.';
		}
	if ($sError==''){
		if ($filab['saiu41idestprog']>0){
			$sCondi='core01id='.$filab['saiu41idestprog'].'';
			}else{
			$sCondi='core01idtercero='.$filab['saiu41idestudiante'].'';
			}
		$sSQL='SELECT core01id, core01idestado, core01factordeserta  
		FROM core01estprograma 
		WHERE '.$sCondi.' AND core01idestado NOT IN (-2, 10, 11, 12, 98, 99)';
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().'<b>Marcar Retiro</b> Consultando Estudiante '.$sSQL.'<br>';}
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)==0){$sError='No se encuentra registro del estudiante';}
		if ($objDB->nf($tabla)>1){$sError='No es posible indicar el registro que se quiere actualizar.';}
		}
	if ($sError==''){
		$fila=$objDB->sf($tabla);
		list($sErrorE, $sDebugE)=f2222_CambiaEstado($fila['core01id'], $fila['core01idestado'], 9, $filab['saiu41idtutor'], $sMotivoDeserta, $objDB, $iFechaReg, $bDebug);
		$sDebug=$sDebug.$sDebugE;
		if ($sErrorE==''){
			$sSQL='UPDATE core01estprograma SET core01factordeserta='.$filab['saiu41factorprincipaldesc'].' WHERE core01id='.$fila['core01id'].'';
			if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' <b>Marcar Retiro</b> Agregando factor de desercion '.$sSQL.'<br>';}
			$result=$objDB->ejecutasql($sSQL);
			//Ahora hacer el analisis de la continuidad. libcore.php
			list($sErrorC, $sDebugC)=f2201_AnalizarContinuidad($fila['core01id'], $objDB, $bDebug);
			$sDebug=$sDebug.$sDebugC;
			}
		}
	return array($sError, $sDebug);
	}
function f3041_db_GuardarV2($DATA, $objDB, $bDebug=false){
	$iCodModulo=3041;
	$bAudita[2]=true;
	$bAudita[3]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3041=$APP->rutacomun.'lg/lg_3041_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3041)){$mensajes_3041=$APP->rutacomun.'lg/lg_3041_es.php';}
	require $mensajes_todas;
	require $mensajes_3041;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	$bCerrando=false;
	$sErrorCerrando='';
	// -- Se inicia validando todas las posibles entradas de usuario.
	/*
	if (isset($DATA['saiu41idestudiante'])==0){$DATA['saiu41idestudiante']='';}
	if (isset($DATA['saiu41consec'])==0){$DATA['saiu41consec']='';}
	if (isset($DATA['saiu41id'])==0){$DATA['saiu41id']='';}
	if (isset($DATA['saiu41tipocontacto'])==0){$DATA['saiu41tipocontacto']='';}
	if (isset($DATA['saiu41fecha'])==0){$DATA['saiu41fecha']='';}
	if (isset($DATA['saiu41cerrada'])==0){$DATA['saiu41cerrada']='';}
	if (isset($DATA['saiu41idperiodo'])==0){$DATA['saiu41idperiodo']='';}
	if (isset($DATA['saiu41idcurso'])==0){$DATA['saiu41idcurso']='';}
	if (isset($DATA['saiu41idactividad'])==0){$DATA['saiu41idactividad']='';}
	if (isset($DATA['saiu41idtutor'])==0){$DATA['saiu41idtutor']='';}
	if (isset($DATA['saiu41visiblealest'])==0){$DATA['saiu41visiblealest']='';}
	if (isset($DATA['saiu41contacto_efectivo'])==0){$DATA['saiu41contacto_efectivo']='';}
	if (isset($DATA['saiu41contacto_forma'])==0){$DATA['saiu41contacto_forma']='';}
	if (isset($DATA['saiu41contacto_observa'])==0){$DATA['saiu41contacto_observa']='';}
	if (isset($DATA['saiu41seretira'])==0){$DATA['saiu41seretira']='';}
	if (isset($DATA['saiu41factorprincipaldesc'])==0){$DATA['saiu41factorprincipaldesc']='';}
	if (isset($DATA['saiu41motivocontacto'])==0){$DATA['saiu41motivocontacto']='';}
	if (isset($DATA['saiu41acciones'])==0){$DATA['saiu41acciones']='';}
	if (isset($DATA['saiu41resultados'])==0){$DATA['saiu41resultados']='';}
	*/
	$DATA['saiu41consec']=numeros_validar($DATA['saiu41consec']);
	$DATA['saiu41tipocontacto']=numeros_validar($DATA['saiu41tipocontacto']);
	$DATA['saiu41idperiodo']=numeros_validar($DATA['saiu41idperiodo']);
	$DATA['saiu41idcurso']=numeros_validar($DATA['saiu41idcurso']);
	$DATA['saiu41idactividad']=numeros_validar($DATA['saiu41idactividad']);
	$DATA['saiu41idtutor']=numeros_validar($DATA['saiu41idtutor']);
	$DATA['saiu41visiblealest']=numeros_validar($DATA['saiu41visiblealest']);
	$DATA['saiu41contacto_efectivo']=numeros_validar($DATA['saiu41contacto_efectivo']);
	$DATA['saiu41contacto_forma']=numeros_validar($DATA['saiu41contacto_forma']);
	$DATA['saiu41contacto_observa']=htmlspecialchars(trim($DATA['saiu41contacto_observa']));
	$DATA['saiu41seretira']=numeros_validar($DATA['saiu41seretira']);
	$DATA['saiu41factorprincipaldesc']=numeros_validar($DATA['saiu41factorprincipaldesc']);
	$DATA['saiu41motivocontacto']=numeros_validar($DATA['saiu41motivocontacto']);
	$DATA['saiu41acciones']=numeros_validar($DATA['saiu41acciones']);
	$DATA['saiu41resultados']=numeros_validar($DATA['saiu41resultados']);
	// -- Se inicializan las variables que puedan pasar vacias {Especialmente números}.
	//if ($DATA['saiu41tipocontacto']==''){$DATA['saiu41tipocontacto']=0;}
	if ($DATA['saiu41cerrada']==''){$DATA['saiu41cerrada']=0;}
	//if ($DATA['saiu41idperiodo']==''){$DATA['saiu41idperiodo']=0;}
	//if ($DATA['saiu41idcurso']==''){$DATA['saiu41idcurso']=0;}
	//if ($DATA['saiu41idactividad']==''){$DATA['saiu41idactividad']=0;}
	//if ($DATA['saiu41visiblealest']==''){$DATA['saiu41visiblealest']=0;}
	//if ($DATA['saiu41contacto_efectivo']==''){$DATA['saiu41contacto_efectivo']=0;}
	//if ($DATA['saiu41contacto_forma']==''){$DATA['saiu41contacto_forma']=0;}
	//if ($DATA['saiu41seretira']==''){$DATA['saiu41seretira']=0;}
	//if ($DATA['saiu41factorprincipaldesc']==''){$DATA['saiu41factorprincipaldesc']=0;}
	//if ($DATA['saiu41motivocontacto']==''){$DATA['saiu41motivocontacto']=0;}
	// -- Seccion para validar los posibles causales de error.
	$sSepara=', ';
	$sTabla41='';
	if ((int)$DATA['saiu41idestudiante']!=0){
		list($idContTercero, $sErrorE)=f1011_BloqueTercero($DATA['saiu41idestudiante'], $objDB);
		$sTabla41='saiu41docente_'.$idContTercero;
		}
	if ($DATA['saiu41tipocontacto']==''){$sError=$ERR['saiu41tipocontacto'].$sSepara.$sError;}
	if ($DATA['saiu41cerrada']==1){
		if ($DATA['saiu41resultados']==''){$sError=$ERR['saiu41resultados'].$sSepara.$sError;}
		if ($DATA['saiu41acciones']==''){$sError=$ERR['saiu41acciones'].$sSepara.$sError;}
		if ($DATA['saiu41motivocontacto']==''){$sError=$ERR['saiu41motivocontacto'].$sSepara.$sError;}
		if ($DATA['saiu41factorprincipaldesc']==''){$sError=$ERR['saiu41factorprincipaldesc'].$sSepara.$sError;}
		if ($DATA['saiu41seretira']==''){$sError=$ERR['saiu41seretira'].$sSepara.$sError;}
		//if ($DATA['saiu41contacto_observa']==''){$sError=$ERR['saiu41contacto_observa'].$sSepara.$sError;}
		if ($DATA['saiu41contacto_forma']==''){$sError=$ERR['saiu41contacto_forma'].$sSepara.$sError;}
		if ($DATA['saiu41contacto_efectivo']==''){$sError=$ERR['saiu41contacto_efectivo'].$sSepara.$sError;}
		if ($DATA['saiu41visiblealest']==''){$sError=$ERR['saiu41visiblealest'].$sSepara.$sError;}
		if ($DATA['saiu41idtutor']==0){$sError=$ERR['saiu41idtutor'].$sSepara.$sError;}
		if ($DATA['saiu41idactividad']==''){$sError=$ERR['saiu41idactividad'].$sSepara.$sError;}
		if ($DATA['saiu41idcurso']==''){$sError=$ERR['saiu41idcurso'].$sSepara.$sError;}
		if ($DATA['saiu41idperiodo']==''){$sError=$ERR['saiu41idperiodo'].$sSepara.$sError;}
		if ($DATA['saiu41fecha']==0){
			//$DATA['saiu41fecha']=fecha_DiaMod();
			$sError=$ERR['saiu41fecha'].$sSepara.$sError;
			}
		if ($sError!=''){
			$DATA['saiu41cerrada']=0;
			if ($DATA['paso']==12){
				$sSQL='SELECT saiu41cerrada FROM '.$sTabla41.' WHERE saiu41id='.$DATA['saiu41id'].'';
				$tabla=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($tabla)>0){
					$fila=$objDB->sf($tabla);
					$DATA['saiu41cerrada']=$fila['saiu41cerrada'];
					}
				}
			}
		$sErrorCerrando=$sError;
		$sError='';
		//Fin de las valiaciones NO LLAVE.
		}
	if (true){
		if ($DATA['saiu41acciones']==''){$DATA['saiu41acciones']=0;}
		if ($DATA['saiu41resultados']==''){$DATA['saiu41resultados']=0;}
		if ($DATA['saiu41motivocontacto']==''){$DATA['saiu41motivocontacto']=0;}
		if ($DATA['saiu41idperiodo']==''){$DATA['saiu41idperiodo']=0;}
		if ($DATA['saiu41idcurso']==''){$DATA['saiu41idcurso']=0;}
		if ($DATA['saiu41idactividad']==''){$DATA['saiu41idactividad']=0;}
		if ($DATA['saiu41contacto_forma']==''){$DATA['saiu41contacto_forma']=0;}
		}
	//Valiaciones de campos obligatorios en todo guardar.
	if ($DATA['saiu41idestudiante']==0){$sError=$ERR['saiu41idestudiante'];}
	// -- Tiene un cerrado.
	if ($DATA['saiu41cerrada']!=0){
		//Validaciones previas a cerrar
		//Aprobó las Validaciones al cerrar
		if ($sError.$sErrorCerrando!=''){
			$DATA['saiu41cerrada']=0;
			$sErrorCerrando=$sError.' '.$sErrorCerrando;
			$sError='';
			}else{
			$bCerrando=true;
			//Acciones del cierre
			}
		}
	// -- Fin del cerrado.
	// -- Se verifican los valores de campos de otras tablas.
	if ($DATA['saiu41idtutor_doc']!=''){
		if ($sError==''){$sError=tabla_terceros_existe($DATA['saiu41idtutor_td'], $DATA['saiu41idtutor_doc'], $objDB, 'El tercero Tutor ');}
		if ($sError==''){
			list($sError, $sInfo)=tercero_Bloqueado($DATA['saiu41idtutor'], $objDB);
			if ($sInfo!=''){$sError=$sError.'<br>'.$sInfo;}
			}
		}
	if ($DATA['saiu41idestudiante_doc']!=''){
		if ($sError==''){$sError=tabla_terceros_existe($DATA['saiu41idestudiante_td'], $DATA['saiu41idestudiante_doc'], $objDB, 'El tercero Estudiante ');}
		if ($sError==''){
			list($sError, $sInfo)=tercero_Bloqueado($DATA['saiu41idestudiante'], $objDB);
			if ($sInfo!=''){$sError=$sError.'<br>'.$sInfo;}
			}
		}
	$bQuitarCodigo=false;
	$sCampoCodigo='';
	if ($sError==''){
		if ($DATA['paso']==10){
			if ($DATA['saiu41consec']==''){
				$DATA['saiu41consec']=tabla_consecutivo($sTabla41, 'saiu41consec', 'saiu41idestudiante='.$DATA['saiu41idestudiante'].'', $objDB);
				if ($DATA['saiu41consec']==-1){$sError=$objDB->serror;}
				$bQuitarCodigo=true;
				$sCampoCodigo='saiu41consec';
				}else{
				if (!seg_revisa_permiso($iCodModulo, 8, $objDB)){
					$sError=$ERR['8'];
					$DATA['saiu41consec']='';
					}
				}
			if ($sError==''){
				$sSQL='SELECT 1 FROM '.$sTabla41.' WHERE saiu41idestudiante="'.$DATA['saiu41idestudiante'].'" AND saiu41consec='.$DATA['saiu41consec'].'';
				$result=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($result)!=0){
					$sError=$ERR['existe'];
					}else{
					if (!seg_revisa_permiso($iCodModulo, 2, $objDB)){$sError=$ERR['2'];}
					}
				}
			}else{
			if ($DATA['saiu41idtutor']!=$_SESSION['unad_id_tercero']){
				$sError=$ERR['3'];
				}else{
				if (!seg_revisa_permiso($iCodModulo, 3, $objDB)){$sError=$ERR['3'];}
				}
			}
		}
	if ($sError==''){
		if ($DATA['paso']==10){
			//Preparar el Id, Si no lo hay se quita la comprobación.
			$DATA['saiu41id']=tabla_consecutivo($sTabla41, 'saiu41id', '', $objDB);
			if ($DATA['saiu41id']==-1){$sError=$objDB->serror;}
			$saiu41idestprog=-1;
			$saiu41idescuela=0;
			$saiu41idprograma=0;
			$saiu41idzona=0;
			$saiu41idcentro=0;
			//Averiguar el plan de estudios.
			if ($DATA['saiu41idperiodo']!=0){
				//$idContTercero
				$sSQL='SELECT core16idestprog, core16idprograma, core16idescuela, core16idcead, core16idzona FROM core16actamatricula WHERE core16tercero='.$DATA['saiu41idestudiante'].' AND core16peraca='.$DATA['saiu41idperiodo'].'';
				$tabla=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($tabla)!=0){
					$fila=$objDB->sf($tabla);
					$saiu41idestprog=$fila['core16idestprog'];
					$saiu41idescuela=$fila['core16idescuela'];
					$saiu41idprograma=$fila['core16idprograma'];
					$saiu41idzona=$fila['core16idzona'];
					$saiu41idcentro=$fila['core16idcead'];
					}
				}
			if ($saiu41idestprog<1){
				//Directo a la core01 le plantamos el ultimo
				$sSQL='SELECT core01id, core01idprograma, core01idescuela, core01idzona, core011idcead FROM core01estprograma WHERE core01idtercero='.$DATA['saiu41idestudiante'].' ORDER BY core01fechainicio DESC';
				$tabla=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($tabla)!=0){
					$fila=$objDB->sf($tabla);
					$saiu41idestprog=$fila['core01id'];
					$saiu41idescuela=$fila['core01idescuela'];
					$saiu41idprograma=$fila['core01idprograma'];
					$saiu41idzona=$fila['core01idzona'];
					$saiu41idcentro=$fila['core011idcead'];
					}
				}
			}
		}
	if ($sError==''){
		if (get_magic_quotes_gpc()==1){$DATA['saiu41contacto_observa']=stripslashes($DATA['saiu41contacto_observa']);}
		//Si el campo saiu41contacto_observa permite html quite la linea htmlspecialchars para el campo y habilite la siguiente linea:
		//$saiu41contacto_observa=addslashes($DATA['saiu41contacto_observa']);
		$saiu41contacto_observa=str_replace('"', '\"', $DATA['saiu41contacto_observa']);
		$bpasa=false;
		if ($DATA['paso']==10){
			$sCampos3041='saiu41idestudiante, saiu41consec, saiu41id, saiu41tipocontacto, saiu41fecha, 
			saiu41cerrada, saiu41idperiodo, saiu41idcurso, saiu41idactividad, saiu41idtutor, 
			saiu41visiblealest, saiu41contacto_efectivo, saiu41contacto_forma, saiu41contacto_observa, saiu41seretira, 
			saiu41factorprincipaldesc, saiu41motivocontacto, saiu41acciones, saiu41resultados, saiu41idestprog, 
			saiu41idescuela, saiu41idprograma, saiu41idzona, saiu41idcentro';
			$sValores3041=''.$DATA['saiu41idestudiante'].', '.$DATA['saiu41consec'].', '.$DATA['saiu41id'].', '.$DATA['saiu41tipocontacto'].', "'.$DATA['saiu41fecha'].'", 
			"'.$DATA['saiu41cerrada'].'", '.$DATA['saiu41idperiodo'].', '.$DATA['saiu41idcurso'].', '.$DATA['saiu41idactividad'].', '.$DATA['saiu41idtutor'].', 
			'.$DATA['saiu41visiblealest'].', '.$DATA['saiu41contacto_efectivo'].', '.$DATA['saiu41contacto_forma'].', "'.$saiu41contacto_observa.'", '.$DATA['saiu41seretira'].', 
			'.$DATA['saiu41factorprincipaldesc'].', '.$DATA['saiu41motivocontacto'].', '.$DATA['saiu41acciones'].', '.$DATA['saiu41resultados'].', '.$saiu41idestprog.', 
			'.$saiu41idescuela.', '.$saiu41idprograma.', '.$saiu41idzona.', '.$saiu41idcentro.'';
			if ($APP->utf8==1){
				$sSQL='INSERT INTO '.$sTabla41.' ('.$sCampos3041.') VALUES ('.utf8_encode($sValores3041).');';
				$sdetalle=$sCampos3041.'['.utf8_encode($sValores3041).']';
				}else{
				$sSQL='INSERT INTO '.$sTabla41.' ('.$sCampos3041.') VALUES ('.$sValores3041.');';
				$sdetalle=$sCampos3041.'['.$sValores3041.']';
				}
			$idAccion=2;
			$bpasa=true;
			}else{
			$scampo[1]='saiu41fecha';
			$scampo[2]='saiu41cerrada';
			$scampo[3]='saiu41idperiodo';
			$scampo[4]='saiu41idcurso';
			$scampo[5]='saiu41idactividad';
			$scampo[6]='saiu41visiblealest';
			$scampo[7]='saiu41contacto_efectivo';
			$scampo[8]='saiu41contacto_forma';
			$scampo[9]='saiu41contacto_observa';
			$scampo[10]='saiu41seretira';
			$scampo[11]='saiu41factorprincipaldesc';
			$scampo[12]='saiu41motivocontacto';
			$scampo[13]='saiu41acciones';
			$scampo[14]='saiu41resultados';
			$sdato[1]=$DATA['saiu41fecha'];
			$sdato[2]=$DATA['saiu41cerrada'];
			$sdato[3]=$DATA['saiu41idperiodo'];
			$sdato[4]=$DATA['saiu41idcurso'];
			$sdato[5]=$DATA['saiu41idactividad'];
			$sdato[6]=$DATA['saiu41visiblealest'];
			$sdato[7]=$DATA['saiu41contacto_efectivo'];
			$sdato[8]=$DATA['saiu41contacto_forma'];
			$sdato[9]=$saiu41contacto_observa;
			$sdato[10]=$DATA['saiu41seretira'];
			$sdato[11]=$DATA['saiu41factorprincipaldesc'];
			$sdato[12]=$DATA['saiu41motivocontacto'];
			$sdato[13]=$DATA['saiu41acciones'];
			$sdato[14]=$DATA['saiu41resultados'];
			$numcmod=14;
			$sWhere='saiu41id='.$DATA['saiu41id'].'';
			$sSQL='SELECT * FROM '.$sTabla41.' WHERE '.$sWhere;
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
					$sSQL='UPDATE '.$sTabla41.' SET '.utf8_encode($sdatos).' WHERE '.$sWhere.';';
					}else{
					$sdetalle=$sdatos.'['.$sWhere.']';
					$sSQL='UPDATE '.$sTabla41.' SET '.$sdatos.' WHERE '.$sWhere.';';
					}
				$idAccion=3;
				}
			}
		if ($bpasa){
			if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Guardar 3041 '.$sSQL.'<br>';}
			$result=$objDB->ejecutasql($sSQL);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' [3041] ..<!-- '.$sSQL.' -->';
				if ($idAccion==2){
					$DATA['saiu41id']='';
					$bQuitarCodigo=true;
					$DATA['paso']=0;
					}else{
					$DATA['paso']=2;
					}
				$bCerrando=false;
				}else{
				if ($bAudita[$idAccion]){seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], $idAccion, $DATA['saiu41id'], $sdetalle, $objDB);}
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
		list($sErrorCerrando, $sDebugCerrar)=f3041_Cerrar($idContTercero, $DATA['saiu41id'], $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugCerrar;
		if ($DATA['saiu41seretira']==1){
			list($sErrorR, $sDebugR)=f3041_MarcarRetiro($idContTercero, $DATA['saiu41id'], $objDB, $bDebug);
			$sDebug=$sDebug.$sDebugR;
			}
		}
	//if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' InfoDepura<br>';}
	return array($DATA, $sError, $iTipoError, $bCerrando, $sErrorCerrando, $sDebug);
	}
function f3041_db_Eliminar($saiu41idestudiante, $saiu41id, $objDB, $bDebug=false){
	$iCodModulo=3041;
	$bAudita[4]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3041=$APP->rutacomun.'lg/lg_3041_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3041)){$mensajes_3041=$APP->rutacomun.'lg/lg_3041_es.php';}
	require $mensajes_todas;
	require $mensajes_3041;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	$saiu41id=numeros_validar($saiu41id);
	$saiu41idestudiante=numeros_validar($saiu41idestudiante);
	if ((int)$saiu41idestudiante==0){$sError='No se ha definido el tercero a consultar.';}
	// Traer los datos para hacer las validaciones.
	if ($sError==''){
		list($idContTercero, $sErrorE)=f1011_BloqueTercero($saiu41idestudiante, $objDB);
		$sTabla41='saiu41docente_'.$idContTercero;
		$sSQL='SELECT * FROM '.$sTabla41.' WHERE saiu41id='.$saiu41id.'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$filabase=$objDB->sf($tabla);
			}else{
			$sError='No se encuentra el registro solicitado {Ref: '.$saiu41id.'}';
			}
		}
	if ($sError==''){
		if (!seg_revisa_permiso($iCodModulo, 4, $objDB)){
			$sError=$ERR['4'];
			}
		}
	if ($sError==''){
		$sSQL='SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=3041';
		$tablaor=$objDB->ejecutasql($sSQL);
		while ($filaor=$objDB->sf($tablaor)){
			$sSQL='SELECT '.$filaor['campoid'].' FROM '.$filaor['tabla'].' WHERE '.$filaor['camporev'].'='.$_REQUEST['saiu41id'].' LIMIT 0, 1';
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
		$sWhere='saiu41id='.$saiu41id.'';
		//$sWhere='saiu41consec='.$filabase['saiu41consec'].' AND saiu41idestudiante="'.$filabase['saiu41idestudiante'].'"';
		$sSQL='DELETE FROM '.$sTabla41.' WHERE '.$sWhere.';';
		$result=$objDB->ejecutasql($sSQL);
		if ($result==false){
			$sError=$ERR['falla_eliminar'].' .. <!-- '.$sSQL.' -->';
			}else{
			if ($bAudita[4]){seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $saiu41id, $sWhere, $objDB);}
			}
		}
	return array($sError, $iTipoError, $sDebug);
	}
function f3041_TituloBusqueda(){
	return 'Busqueda de Acompañamiento a estudiantes';
	}
function f3041_ParametrosBusqueda(){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3041=$APP->rutacomun.'lg/lg_3041_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3041)){$mensajes_3041=$APP->rutacomun.'lg/lg_3041_es.php';}
	require $mensajes_todas;
	require $mensajes_3041;
	$sParams='<label class="Label90">
	'.$ETI['msg_bnombre'].'
	</label>
	<label>
	<input id="b3041nombre" name="b3041nombre" type="text" value="" onchange="paginarbusqueda()" />
	</label>';
	return $sParams;
	}
function f3041_JavaScriptBusqueda($iModuloBusca){
	$sRes='var sCampo=window.document.frmedita.scampobusca.value;
	var params=new Array();
	params[100]=sCampo;
	params[101]=window.document.frmedita.paginabusqueda.value;
	params[102]=window.document.frmedita.lppfbusqueda.value;
	params[103]=window.document.frmedita.b3041nombre.value;
	xajax_f'.$iModuloBusca.'_HtmlBusqueda(params);';
	return $sRes;
	}
function f3041_TablaDetalleBusquedas($aParametros, $objDB){
	$res='';
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3041=$APP->rutacomun.'lg/lg_3041_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3041)){$mensajes_3041=$APP->rutacomun.'lg/lg_3041_es.php';}
	require $mensajes_todas;
	require $mensajes_3041;
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
		return array($sLeyenda.'<input id="paginaf3041" name="paginaf3041" type="hidden" value="'.$pagina.'"/><input id="lppf3041" name="lppf3041" type="hidden" value="'.$lineastabla.'"/>', $sDebug);
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
	list($idContTercero, $sErrorE)=f1011_BloqueTercero($saiu41idestudiante, $objDB);
	$sTabla41='saiu41docente_'.$idContTercero;
	$sTitulos='Estudiante, Consec, Id, Tipocontacto, Fecha, Cerrada, Periodo, Curso, Actividad, Tutor, Visiblealest, Contacto_efectivo, Contacto_forma, Contacto_observa, Seretira, Factorprincipaldesc';
	$sSQL='SELECT T1.unad11razonsocial AS C1_nombre, TB.saiu41consec, TB.saiu41id, T4.ceca26nombre, TB.saiu41fecha, TB.saiu41cerrada, T7.exte02nombre, T8.unad40nombre, T9.ofer04nombre, T10.unad11razonsocial AS C10_nombre, TB.saiu41visiblealest, TB.saiu41contacto_efectivo, T13.cara27titulo, TB.saiu41contacto_observa, TB.saiu41seretira, T16.cara15nombre, TB.saiu41idestudiante, T1.unad11tipodoc AS C1_td, T1.unad11doc AS C1_doc, TB.saiu41tipocontacto, TB.saiu41idperiodo, TB.saiu41idcurso, TB.saiu41idactividad, TB.saiu41idtutor, T10.unad11tipodoc AS C10_td, T10.unad11doc AS C10_doc, TB.saiu41contacto_forma, TB.saiu41factorprincipaldesc 
	FROM '.$sTabla41.' AS TB, unad11terceros AS T1, ceca26tipoacompana AS T4, exte02per_aca AS T7, unad40curso AS T8, ofer04cursoactividad AS T9, unad11terceros AS T10, cara27mediocont AS T13, cara15factordeserta AS T16 
	WHERE '.$sSQLadd1.' TB.saiu41idestudiante=T1.unad11id AND TB.saiu41tipocontacto=T4.ceca26id AND TB.saiu41idperiodo=T7.exte02id AND TB.saiu41idcurso=T8.unad40id AND TB.saiu41idactividad=T9.ofer04id AND TB.saiu41idtutor=T10.unad11id AND TB.saiu41contacto_forma=T13.cara27id AND TB.saiu41factorprincipaldesc=T16.cara15id '.$sSQLadd.'
	ORDER BY TB.saiu41idestudiante, TB.saiu41consec';
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
			//return array(utf8_encode($sErrConsulta.'<input id="paginaf3041" name="paginaf3041" type="hidden" value="'.$pagina.'"/><input id="lppf3041" name="lppf3041" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
			}
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
	<td colspan="2"><b>'.$ETI['saiu41idestudiante'].'</b></td>
	<td><b>'.$ETI['saiu41consec'].'</b></td>
	<td><b>'.$ETI['saiu41tipocontacto'].'</b></td>
	<td><b>'.$ETI['saiu41fecha'].'</b></td>
	<td><b>'.$ETI['saiu41cerrada'].'</b></td>
	<td><b>'.$ETI['saiu41idperiodo'].'</b></td>
	<td><b>'.$ETI['saiu41idcurso'].'</b></td>
	<td><b>'.$ETI['saiu41idactividad'].'</b></td>
	<td colspan="2"><b>'.$ETI['saiu41idtutor'].'</b></td>
	<td><b>'.$ETI['saiu41visiblealest'].'</b></td>
	<td><b>'.$ETI['saiu41contacto_efectivo'].'</b></td>
	<td><b>'.$ETI['saiu41contacto_forma'].'</b></td>
	<td><b>'.$ETI['saiu41contacto_observa'].'</b></td>
	<td><b>'.$ETI['saiu41seretira'].'</b></td>
	<td><b>'.$ETI['saiu41factorprincipaldesc'].'</b></td>
	<td align="right">
	'.html_paginador('paginabusqueda', $registros, $lineastabla, $pagina, 'paginarbusqueda()').'
	'.html_lpp('lppfbusqueda', $lineastabla, 'paginarbusqueda()').'
	</td>
	</tr></thead>';
	$tlinea=1;
	while($filadet=$objDB->sf($tabladetalle)){
		$sPrefijo='<a href="javascript:Devuelve(\''.$filadet['saiu41id'].'\');">';
		$sSufijo='</a>';
		$tlinea++;
		$et_saiu41fecha='';
		if ($filadet['saiu41fecha']!=0){$et_saiu41fecha=fecha_desdenumero($filadet['saiu41fecha']);}
		$et_saiu41cerrada=$ETI['msg_abierto'];
		if ($filadet['saiu41cerrada']=='S'){$et_saiu41cerrada=$ETI['msg_cerrado'];}
		$res=$res.'<tr onmouseover="cambia_color_over(this);" onmouseout="cambia_color_out(this);">
		<td>'.$sPrefijo.$filadet['C1_td'].' '.$filadet['C1_doc'].$sSufijo.'</td>
		<td>'.$sPrefijo.cadena_notildes($filadet['C1_nombre']).$sSufijo.'</td>
		<td>'.$sPrefijo.$filadet['saiu41consec'].$sSufijo.'</td>
		<td>'.$sPrefijo.cadena_notildes($filadet['ceca26nombre']).$sSufijo.'</td>
		<td>'.$sPrefijo.$et_saiu41fecha.$sSufijo.'</td>
		<td>'.$sPrefijo.$et_saiu41cerrada.$sSufijo.'</td>
		<td>'.$sPrefijo.cadena_notildes($filadet['exte02nombre']).$sSufijo.'</td>
		<td>'.$sPrefijo.cadena_notildes($filadet['unad40nombre']).$sSufijo.'</td>
		<td>'.$sPrefijo.cadena_notildes($filadet['ofer04nombre']).$sSufijo.'</td>
		<td>'.$sPrefijo.$filadet['C10_td'].' '.$filadet['C10_doc'].$sSufijo.'</td>
		<td>'.$sPrefijo.cadena_notildes($filadet['C10_nombre']).$sSufijo.'</td>
		<td>'.$sPrefijo.$filadet['saiu41visiblealest'].$sSufijo.'</td>
		<td>'.$sPrefijo.$filadet['saiu41contacto_efectivo'].$sSufijo.'</td>
		<td>'.$sPrefijo.cadena_notildes($filadet['cara27titulo']).$sSufijo.'</td>
		<td>'.$sPrefijo.$filadet['saiu41contacto_observa'].$sSufijo.'</td>
		<td>'.$sPrefijo.$filadet['saiu41seretira'].$sSufijo.'</td>
		<td>'.$sPrefijo.cadena_notildes($filadet['cara15nombre']).$sSufijo.'</td>
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