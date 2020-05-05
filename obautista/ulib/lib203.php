<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2018 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.22.4 miércoles, 29 de agosto de 2018
--- 203 unae03noconforme
*/
function f203_CodModulo(){
	$iCodModulo=203;
	require './app.php';
	switch($APP->idsistema){
		case 17:
		$iCodModulo=1758;
		break;
		}
	return $iCodModulo;
	}
function f203_HTMLComboV2_unae03idproceso($objDB, $objCombos, $valor){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('unae03idproceso', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	$objCombos->sAccion='RevisaLlave();';
	$sWhere=' WHERE unae06id=-99';
	switch($APP->idsistema){
		case 1:
		$sWhere='';
		break;
		case 17:
		$sWhere=' WHERE unae06idsistema=17';
		break;
		default:
		$sWhere=' WHERE unae06idsistema=-99';
		break;
		}
	$sSQL='SELECT unae06id AS id, unae06nombre AS nombre FROM unae06procesonc'.$sWhere.' ORDER BY unae06id';
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f203_ExisteDato($datos){
	if(!is_array($datos)){$datos=json_decode(str_replace('\"','"',$datos),true);}
	$_SESSION['u_ultimominuto']=iminutoavance();
	$bHayLlave=true;
	$unae03idproceso=numeros_validar($datos[1]);
	if ($unae03idproceso==''){$bHayLlave=false;}
	$unae03consec=numeros_validar($datos[2]);
	if ($unae03consec==''){$bHayLlave=false;}
	if ($bHayLlave){
		require './app.php';
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		$sSQL='SELECT unae03consec FROM unae03noconforme WHERE unae03idproceso='.$unae03idproceso.' AND unae03consec='.$unae03consec.'';
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
function f203_Busquedas($aParametros){
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_203=$APP->rutacomun.'lg/lg_203_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_203)){$mensajes_203=$APP->rutacomun.'lg/lg_203_es.php';}
	require $mensajes_todas;
	require $mensajes_203;
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
		case 'unae03idresponsable':
		require $APP->rutacomun.'lib111.php';
		$sTabla=f111_TablaDetalleBusquedas($aParametrosB, $objDB);
		$sTitulo='Busqueda de terceros';
		$sParams=f111_ParametrosBusqueda();
		$sJavaBusqueda=f111_JavaScriptBusqueda(203);
		break;
		case 'unae03idautoriza':
		require $APP->rutacomun.'lib111.php';
		$sTabla=f111_TablaDetalleBusquedas($aParametrosB, $objDB);
		$sTitulo='Busqueda de terceros';
		$sParams=f111_ParametrosBusqueda();
		$sJavaBusqueda=f111_JavaScriptBusqueda(203);
		break;
		case 'unae07idtercero':
		require $APP->rutacomun.'lib111.php';
		$sTabla=f111_TablaDetalleBusquedas($aParametrosB, $objDB);
		$sTitulo='Busqueda de terceros';
		$sParams=f111_ParametrosBusqueda();
		$sJavaBusqueda=f111_JavaScriptBusqueda(203);
		break;
		}
	$sTitulo='<h2>'.$ETI['titulo_203'].' - '.$sTitulo.'</h2>';
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97titulo', 'innerHTML', $sTitulo);
	$objResponse->assign('div_97params', 'innerHTML', $sParams);
	$objResponse->assign('div_97tabla', 'innerHTML', $sTabla);
	$objResponse->setFunction('paginarbusqueda','',$sJavaBusqueda);
	$objResponse->call('expandesector(97)');
	return $objResponse;
	}
function f203_HtmlBusqueda($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$sError='';
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$sDetalle='';
	switch($aParametros[100]){
		case 'unae03idresponsable':
		require $APP->rutacomun.'lib111.php';
		$sDetalle=f111_TablaDetalleBusquedas($aParametros, $objDB);
		break;
		case 'unae03idautoriza':
		require $APP->rutacomun.'lib111.php';
		$sDetalle=f111_TablaDetalleBusquedas($aParametros, $objDB);
		break;
		case 'unae07idtercero':
		require $APP->rutacomun.'lib111.php';
		$sDetalle=f111_TablaDetalleBusquedas($aParametros, $objDB);
		break;
		}
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97tabla', 'innerHTML', $sDetalle);
	return $objResponse;
	}
function f203_TablaDetalleV2($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_203=$APP->rutacomun.'lg/lg_203_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_203)){$mensajes_203=$APP->rutacomun.'lg/lg_203_es.php';}
	require $mensajes_todas;
	require $mensajes_203;
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	if (isset($aParametros[100])==0){$aParametros[100]=$_SESSION['unad_id_tercero'];}
	if (isset($aParametros[101])==0){$aParametros[101]=1;}
	if (isset($aParametros[102])==0){$aParametros[102]=20;}
	if (isset($aParametros[103])==0){$aParametros[103]='';}
	if (isset($aParametros[104])==0){$aParametros[104]='';}
	if (isset($aParametros[105])==0){$aParametros[105]='';}
	if (isset($aParametros[106])==0){$aParametros[106]='';}
	//$aParametros[103]=numeros_validar($aParametros[103]);
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
	//if ((int)$aParametros[103]!=-1){$sSQLadd=$sSQLadd.' AND TB.campo='.$aParametros[103];}
	/*
	if ($aParametros[104]!=''){
		$sBase=trim(strtoupper($aParametros[104]));
		$aNoms=explode(' ', $sBase);
		for ($k=1;$k<=count($aNoms);$k++){
			$sCadena=$aNoms[$k-1];
			if ($sCadena!=''){
				$sSQLadd=$sSQLadd.' AND T6.sys11razonsocial LIKE "%'.$sCadena.'%"';
				//$sSQLadd1=$sSQLadd1.'T1.sys11razonsocial LIKE "%'.$sCadena.'%" AND ';
				}
			}
		}
	*/
	switch($aParametros[104]){
		case 1:
		$sSQLadd1=$sSQLadd1.'TB.unae03idresponsable='.$idTercero.' AND TB.unae03estado IN (0, 4) AND ';
		break;
		case 2:
		$sSQLadd1=$sSQLadd1.'TB.unae03idresponsable='.$idTercero.' AND ';
		break;
		case 3:
		$sSQLadd1=$sSQLadd1.'TB.unae03idautoriza='.$idTercero.' AND TB.unae03estado IN (1) AND ';
		break;
		case 4:
		$sSQLadd1=$sSQLadd1.'TB.unae03idautoriza='.$idTercero.' ';
		break;
		case 5:
		$sSQLadd1=$sSQLadd1.'TB.unae03idautoriza=0 AND ';
		break;
		}
	if ($aParametros[105]!=''){$sSQLadd1=$sSQLadd1.'TB.unae03idproceso='.$aParametros[105].' AND ';}
	if ($aParametros[106]!=''){$sSQLadd1=$sSQLadd1.'TB.unae03peraca='.$aParametros[106].' AND ';}
	$sTitulos='Proceso, Consecutivo, Periodo, Curso, TipoDocResponable, DocResponable, Responsable, Estado, Fecha generada, Fecha responde, Respuesta, Justificacion, Fecha autoriza, Escuela';
	$sSQL='SELECT T1.unae06nombre, TB.unae03consec, TB.unae03peraca, TB.unae03curso, T7.unad11tipodoc AS C7_td, T7.unad11doc AS C7_doc, T7.unad11razonsocial AS C7_nombre, T8.unae04nombre, TB.unae03fechagenera, TB.unae03fecharesponde, TB.unae03respuesta, TB.unae03idjustificacion, TB.unae03fechaautoriza, TB.unae03escuela, TB.unae03idproceso, TB.unae03idresponsable, TB.unae03estado, TB.unae03idautoriza, TB.unae03id 
FROM unae03noconforme AS TB, unae06procesonc AS T1, unad11terceros AS T7, unae04estadonoconf AS T8 
WHERE '.$sSQLadd1.' TB.unae03idproceso=T1.unae06id AND T1.unae06idsistema='.(int)$APP->idsistema.' AND TB.unae03idresponsable=T7.unad11id AND TB.unae03estado=T8.unae04id '.$sSQLadd.'
ORDER BY TB.unae03peraca DESC, TB.unae03idproceso, TB.unae03consec DESC';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_203" name="consulta_203" type="hidden" value="'.$sSQLlista.'"/>
<input id="titulos_203" name="titulos_203" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objDB->ejecutasql($sSQL);
	if ($tabladetalle==false){
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 203: '.$sSQL.'<br>';}
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		//$sLeyenda=$sSQL;
		}else{
		$registros=$objDB->nf($tabladetalle);
		if ($registros==0){
			//return array(utf8_encode($sErrConsulta.'<input id="paginaf203" name="paginaf203" type="hidden" value="'.$pagina.'"/><input id="lppf203" name="lppf203" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
<td><b>'.$ETI['unae03consec'].'</b></td>
<td><b>'.$ETI['unae03peraca'].'</b></td>
<td><b>'.$ETI['unae03curso'].'</b></td>
<td colspan="2"><b>'.$ETI['unae03idresponsable'].'</b></td>
<td><b>'.$ETI['unae03estado'].'</b></td>
<td align="right">
'.html_paginador('paginaf203', $registros, $lineastabla, $pagina, 'paginarf203()').'
'.html_lpp('lppf203', $lineastabla, 'paginarf203()').'
</td>
</tr>';
	$tlinea=1;
	$idProceso=-1;
	while($filadet=$objDB->sf($tabladetalle)){
		if ($idProceso!=$filadet['unae03idproceso']){
			$idProceso=$filadet['unae03idproceso'];
			$res=$res.'<tr class="fondoazul">
<td colspan="8">'.$ETI['unae03idproceso'].' <b>'.cadena_notildes($filadet['unae06nombre']).'</b></td>
</tr>';
			}
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
		if ($babierta){
			$sLink='<a href="javascript:cargaridf203('.$filadet['unae03id'].')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$res=$res.'<tr'.$sClass.'>
<td>'.$sPrefijo.$filadet['unae03consec'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['unae03peraca'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['unae03curso'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['C7_td'].' '.$filadet['C7_doc'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['C7_nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['unae04nombre']).$sSufijo.'</td>
<td>'.$sLink.'</td>
</tr>';
		}
	$res=$res.'</table>';
	$objDB->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
	}
function f203_HtmlTabla($aParametros){
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
	list($sDetalle, $sDebugTabla)=f203_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f203detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f203_db_CargarPadre($DATA, $objDB, $bDebug=false){
	$sError='';
	$iTipoError=0;
	$sDebug='';
	require './app.php';
	$DATA['unae03idresponsable_td']=$APP->tipo_doc;
	$DATA['unae03idresponsable_doc']='';
	$DATA['unae03idautoriza_td']=$APP->tipo_doc;
	$DATA['unae03idautoriza_doc']='';
	if ($DATA['paso']==1){
		$sSQLcondi='unae03idproceso='.$DATA['unae03idproceso'].' AND unae03consec='.$DATA['unae03consec'].'';
		}else{
		$sSQLcondi='unae03id='.$DATA['unae03id'].'';
		}
	$sSQL='SELECT * FROM unae03noconforme WHERE '.$sSQLcondi;
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$DATA['unae03idproceso']=$fila['unae03idproceso'];
		$DATA['unae03consec']=$fila['unae03consec'];
		$DATA['unae03id']=$fila['unae03id'];
		$DATA['unae03peraca']=$fila['unae03peraca'];
		$DATA['unae03curso']=$fila['unae03curso'];
		$DATA['unae03escuela']=$fila['unae03escuela'];
		$DATA['unae03idresponsable']=$fila['unae03idresponsable'];
		$DATA['unae03estado']=$fila['unae03estado'];
		$DATA['unae03fechagenera']=$fila['unae03fechagenera'];
		$DATA['unae03fecharesponde']=$fila['unae03fecharesponde'];
		$DATA['unae03respuesta']=$fila['unae03respuesta'];
		$DATA['unae03idjustificacion']=$fila['unae03idjustificacion'];
		$DATA['unae03idautoriza']=$fila['unae03idautoriza'];
		$DATA['unae03fechaautoriza']=$fila['unae03fechaautoriza'];
		$bcargo=true;
		$DATA['paso']=2;
		$DATA['boculta203']=0;
		$bLimpiaHijos=true;
		}else{
		$DATA['paso']=0;
		}
	return array($DATA, $sError, $iTipoError, $sDebug);
	}
function f203_db_GuardarV2($DATA, $objDB, $bDebug=false){
	$iCodModulo=f203_CodModulo();
	$bAudita[2]=true;
	$bAudita[3]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_203=$APP->rutacomun.'lg/lg_203_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_203)){$mensajes_203=$APP->rutacomun.'lg/lg_203_es.php';}
	require $mensajes_todas;
	require $mensajes_203;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	// -- Se inicia validando todas las posibles entradas de usuario.
	/*
	if (isset($DATA['unae03idproceso'])==0){$DATA['unae03idproceso']='';}
	if (isset($DATA['unae03consec'])==0){$DATA['unae03consec']='';}
	if (isset($DATA['unae03id'])==0){$DATA['unae03id']='';}
	if (isset($DATA['unae03peraca'])==0){$DATA['unae03peraca']='';}
	if (isset($DATA['unae03curso'])==0){$DATA['unae03curso']='';}
	if (isset($DATA['unae03escuela'])==0){$DATA['unae03escuela']='';}
	if (isset($DATA['unae03idresponsable'])==0){$DATA['unae03idresponsable']='';}
	if (isset($DATA['unae03estado'])==0){$DATA['unae03estado']='';}
	if (isset($DATA['unae03fechagenera'])==0){$DATA['unae03fechagenera']='';}
	if (isset($DATA['unae03fecharesponde'])==0){$DATA['unae03fecharesponde']='';}
	if (isset($DATA['unae03respuesta'])==0){$DATA['unae03respuesta']='';}
	if (isset($DATA['unae03idjustificacion'])==0){$DATA['unae03idjustificacion']='';}
	if (isset($DATA['unae03fechaautoriza'])==0){$DATA['unae03fechaautoriza']='';}
	*/
	$DATA['unae03idproceso']=numeros_validar($DATA['unae03idproceso']);
	$DATA['unae03consec']=numeros_validar($DATA['unae03consec']);
	$DATA['unae03peraca']=numeros_validar($DATA['unae03peraca']);
	$DATA['unae03curso']=numeros_validar($DATA['unae03curso']);
	$DATA['unae03escuela']=numeros_validar($DATA['unae03escuela']);
	$DATA['unae03estado']=numeros_validar($DATA['unae03estado']);
	$DATA['unae03respuesta']=htmlspecialchars(trim($DATA['unae03respuesta']));
	$DATA['unae03idjustificacion']=numeros_validar($DATA['unae03idjustificacion']);
	// -- Se inicializan las variables que puedan pasar vacias {Especialmente números}.
	//if ($DATA['unae03peraca']==''){$DATA['unae03peraca']=0;}
	//if ($DATA['unae03curso']==''){$DATA['unae03curso']=0;}
	//if ($DATA['unae03escuela']==''){$DATA['unae03escuela']=0;}
	//if ($DATA['unae03estado']==''){$DATA['unae03estado']=0;}
	//if ($DATA['unae03idjustificacion']==''){$DATA['unae03idjustificacion']=0;}
	// -- Seccion para validar los posibles causales de error.
	$sSepara=', ';
	if (true){
		if ($DATA['unae03fechaautoriza']==0){
			//$DATA['unae03fechaautoriza']=fecha_DiaMod();
			$sError=$ERR['unae03fechaautoriza'].$sSepara.$sError;
			}
		if ($DATA['unae03idautoriza']==0){$sError=$ERR['unae03idautoriza'].$sSepara.$sError;}
		if ($DATA['unae03idjustificacion']==''){$sError=$ERR['unae03idjustificacion'].$sSepara.$sError;}
		//if ($DATA['unae03respuesta']==''){$sError=$ERR['unae03respuesta'].$sSepara.$sError;}
		if ($DATA['unae03fecharesponde']==0){
			//$DATA['unae03fecharesponde']=fecha_DiaMod();
			$sError=$ERR['unae03fecharesponde'].$sSepara.$sError;
			}
		if ($DATA['unae03fechagenera']==0){
			//$DATA['unae03fechagenera']=fecha_DiaMod();
			$sError=$ERR['unae03fechagenera'].$sSepara.$sError;
			}
		if ($DATA['unae03estado']==''){$sError=$ERR['unae03estado'].$sSepara.$sError;}
		if ($DATA['unae03idresponsable']==0){$sError=$ERR['unae03idresponsable'].$sSepara.$sError;}
		if ($DATA['unae03escuela']==''){$sError=$ERR['unae03escuela'].$sSepara.$sError;}
		if ($DATA['unae03curso']==''){$sError=$ERR['unae03curso'].$sSepara.$sError;}
		if ($DATA['unae03peraca']==''){$sError=$ERR['unae03peraca'].$sSepara.$sError;}
		//Fin de las valiaciones NO LLAVE.
		}
	//Valiaciones de campos obligatorios en todo guardar.
	if ($DATA['unae03idproceso']==''){$sError=$ERR['unae03idproceso'];}
	// -- Se verifican los valores de campos de otras tablas.
	if ($sError==''){$sError=tabla_terceros_existe($DATA['unae03idautoriza_td'], $DATA['unae03idautoriza_doc'], $objDB, 'El tercero Autoriza ');}
	if ($sError==''){
		list($sError, $sInfo)=tercero_Bloqueado($DATA['unae03idautoriza'], $objDB);
		if ($sInfo!=''){$sError=$sError.'<br>'.sInfo;}
		}
	if ($sError==''){$sError=tabla_terceros_existe($DATA['unae03idresponsable_td'], $DATA['unae03idresponsable_doc'], $objDB, 'El tercero Responsable ');}
	if ($sError==''){
		list($sError, $sInfo)=tercero_Bloqueado($DATA['unae03idresponsable'], $objDB);
		if ($sInfo!=''){$sError=$sError.'<br>'.sInfo;}
		}
	if ($sError==''){
		if ($DATA['paso']==10){
			if ($DATA['unae03consec']==''){
				$DATA['unae03consec']=tabla_consecutivo('unae03noconforme', 'unae03consec', 'unae03idproceso='.$DATA['unae03idproceso'].'', $objDB);
				if ($DATA['unae03consec']==-1){$sError=$objDB->serror;}
				}else{
				if (!seg_revisa_permiso($iCodModulo, 8, $objDB)){
					$sError=$ERR['8'];
					$DATA['unae03consec']='';
					}
				}
			if ($sError==''){
				$sSQL='SELECT unae03idproceso FROM unae03noconforme WHERE unae03idproceso='.$DATA['unae03idproceso'].' AND unae03consec='.$DATA['unae03consec'].'';
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
			$DATA['unae03id']=tabla_consecutivo('unae03noconforme','unae03id', '', $objDB);
			if ($DATA['unae03id']==-1){$sError=$objDB->serror;}
			}
		}
	if ($sError==''){
		if (get_magic_quotes_gpc()==1){$DATA['unae03respuesta']=stripslashes($DATA['unae03respuesta']);}
		//Si el campo unae03respuesta permite html quite la linea htmlspecialchars para el campo y habilite la siguiente linea:
		//$unae03respuesta=addslashes($DATA['unae03respuesta']);
		$unae03respuesta=str_replace('"', '\"', $DATA['unae03respuesta']);
		$bpasa=false;
		if ($DATA['paso']==10){
			$unae03fechagenera=fecha_DiaMod();
			$unae03fecharesponde=fecha_DiaMod();
			//$DATA['unae03idautoriza']=0; //$_SESSION['u_idtercero'];
			$unae03fechaautoriza=fecha_DiaMod();
			$sCampos203='unae03idproceso, unae03consec, unae03id, unae03peraca, unae03curso, unae03escuela, unae03idresponsable, unae03estado, unae03fechagenera, unae03fecharesponde, 
unae03respuesta, unae03idjustificacion, unae03idautoriza, unae03fechaautoriza';
			$sValores203=''.$DATA['unae03idproceso'].', '.$DATA['unae03consec'].', '.$DATA['unae03id'].', '.$DATA['unae03peraca'].', '.$DATA['unae03curso'].', '.$DATA['unae03escuela'].', '.$DATA['unae03idresponsable'].', '.$DATA['unae03estado'].', "'.$DATA['unae03fechagenera'].'", "'.$DATA['unae03fecharesponde'].'", 
"'.$unae03respuesta.'", '.$DATA['unae03idjustificacion'].', '.$DATA['unae03idautoriza'].', "'.$DATA['unae03fechaautoriza'].'"';
			if ($APP->utf8==1){
				$sSQL='INSERT INTO unae03noconforme ('.$sCampos203.') VALUES ('.utf8_encode($sValores203).');';
				$sdetalle=$sCampos203.'['.utf8_encode($sValores203).']';
				}else{
				$sSQL='INSERT INTO unae03noconforme ('.$sCampos203.') VALUES ('.$sValores203.');';
				$sdetalle=$sCampos203.'['.$sValores203.']';
				}
			$idaccion=2;
			$bpasa=true;
			}else{
			$scampo[1]='unae03peraca';
			$scampo[2]='unae03curso';
			$scampo[3]='unae03escuela';
			$scampo[4]='unae03idresponsable';
			$scampo[5]='unae03estado';
			$scampo[6]='unae03fechagenera';
			$scampo[7]='unae03fecharesponde';
			$scampo[8]='unae03respuesta';
			$scampo[9]='unae03idjustificacion';
			$scampo[10]='unae03fechaautoriza';
			$sdato[1]=$DATA['unae03peraca'];
			$sdato[2]=$DATA['unae03curso'];
			$sdato[3]=$DATA['unae03escuela'];
			$sdato[4]=$DATA['unae03idresponsable'];
			$sdato[5]=$DATA['unae03estado'];
			$sdato[6]=$DATA['unae03fechagenera'];
			$sdato[7]=$DATA['unae03fecharesponde'];
			$sdato[8]=$unae03respuesta;
			$sdato[9]=$DATA['unae03idjustificacion'];
			$sdato[10]=$DATA['unae03fechaautoriza'];
			$numcmod=10;
			$sWhere='unae03id='.$DATA['unae03id'].'';
			$sSQL='SELECT * FROM unae03noconforme WHERE '.$sWhere;
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
					$sSQL='UPDATE unae03noconforme SET '.utf8_encode($sdatos).' WHERE '.$sWhere.';';
					}else{
					$sdetalle=$sdatos.'['.$sWhere.']';
					$sSQL='UPDATE unae03noconforme SET '.$sdatos.' WHERE '.$sWhere.';';
					}
				$idaccion=3;
				}
			}
		if ($bpasa){
			$result=$objDB->ejecutasql($sSQL);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' [203] ..<!-- '.$sSQL.' -->';
				if ($idaccion==2){$DATA['unae03id']='';}
				$DATA['paso']=$DATA['paso']-10;
				}else{
				if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Guardar 203 '.$sSQL.'<br>';}
				if ($bAudita[$idaccion]){seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], $idaccion, $DATA['unae03id'], $sdetalle, $objDB);}
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
function f203_db_Eliminar($unae03id, $objDB, $bDebug=false){
	$iCodModulo=f203_CodModulo();
	$bAudita[4]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_203=$APP->rutacomun.'lg/lg_203_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_203)){$mensajes_203=$APP->rutacomun.'lg/lg_203_es.php';}
	require $mensajes_todas;
	require $mensajes_203;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	$unae03id=numeros_validar($unae03id);
	// Traer los datos para hacer las validaciones.
	if ($sError==''){
		$sSQL='SELECT * FROM unae03noconforme WHERE unae03id='.$unae03id.'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$filabase=$objDB->sf($tabla);
			}else{
			$sError='No se encuentra el registro solicitado {Ref: '.$unae03id.'}';
			}
		}
	if ($sError==''){
		$sSQL='SELECT unae07idnoconformidad FROM unae07noconfornota WHERE unae07idnoconformidad='.$filabase['unae03id'].'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$sError='Existen Anotaciones creados, no es posible eliminar';
			}
		}
	if ($sError==''){
		if (!seg_revisa_permiso($iCodModulo, 4, $objDB)){
			$sError=$ERR['4'];
			}
		}
	if ($sError==''){
		$sSQL='SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=203';
		$tablaor=$objDB->ejecutasql($sSQL);
		while ($filaor=$objDB->sf($tablaor)){
			$sSQL='SELECT '.$filaor['campoid'].' FROM '.$filaor['tabla'].' WHERE '.$filaor['camporev'].'='.$_REQUEST['unae03id'].' LIMIT 0, 1';
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
		//$sSQL='DELETE FROM unae07noconfornota WHERE unae07idnoconformidad='.$filabase['unae03id'].'';
		//$tabla=$objDB->ejecutasql($sSQL);
		$sWhere='unae03id='.$unae03id.'';
		//$sWhere='unae03consec='.$filabase['unae03consec'].' AND unae03idproceso='.$filabase['unae03idproceso'].'';
		$sSQL='DELETE FROM unae03noconforme WHERE '.$sWhere.';';
		$result=$objDB->ejecutasql($sSQL);
		if ($result==false){
			$sError=$ERR['falla_eliminar'].' .. <!-- '.$sSQL.' -->';
			}else{
			if ($bAudita[4]){seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $unae03id, $sWhere, $objDB);}
			}
		}
	return array($sError, $iTipoError, $sDebug);
	}
function f203_TituloBusqueda(){
	return 'Busqueda de No conformidades';
	}
function f203_ParametrosBusqueda(){
	$sParams='<label class="Label90">Nombre</label><label><input id="b203nombre" name="b203nombre" type="text" value="" onchange="paginarbusqueda()" /></label>';
	return $sParams;
	}
function f203_JavaScriptBusqueda($iModuloBusca){
	$sRes='var sCampo=window.document.frmedita.scampobusca.value;
var params=new Array();
params[100]=sCampo;
params[101]=window.document.frmedita.paginabusqueda.value;
params[102]=window.document.frmedita.lppfbusqueda.value;
params[103]=window.document.frmedita.b203nombre.value;
xajax_f'.$iModuloBusca.'_HtmlBusqueda(params);';
	return $sRes;
	}
function f203_TablaDetalleBusquedas($aParametros, $objDB){
	$res='';
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_203=$APP->rutacomun.'lg/lg_203_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_203)){$mensajes_203=$APP->rutacomun.'lg/lg_203_es.php';}
	require $mensajes_todas;
	require $mensajes_203;
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
				$sSQLadd=$sSQLadd.' AND T6.sys11razonsocial LIKE "%'.$sCadena.'%"';
				//$sSQLadd1=$sSQLadd1.'T1.sys11razonsocial LIKE "%'.$sCadena.'%" AND ';
				}
			}
		}
	*/
	$sTitulos='Proceso, Consec, Id, Peraca, Curso, Escuela, Responsable, Estado, Fechagenera, Fecharesponde, Respuesta, Justificacion, Autoriza, Fechaautoriza';
	$sSQL='SELECT T1.unae06nombre, TB.unae03consec, TB.unae03id, TB.unae03peraca, TB.unae03curso, TB.unae03escuela, T7.unad11razonsocial AS C7_nombre, T8.unae04nombre, TB.unae03fechagenera, TB.unae03fecharesponde, TB.unae03respuesta, T12.unae05titulo, T13.unad11razonsocial AS C13_nombre, TB.unae03fechaautoriza, TB.unae03idproceso, TB.unae03idresponsable, T7.unad11tipodoc AS C7_td, T7.unad11doc AS C7_doc, TB.unae03estado, TB.unae03idjustificacion, TB.unae03idautoriza, T13.unad11tipodoc AS C13_td, T13.unad11doc AS C13_doc 
FROM unae03noconforme AS TB, unae06procesonc AS T1, unad11terceros AS T7, unae04estadonoconf AS T8, unae04justificacionnoconf AS T12, unad11terceros AS T13 
WHERE '.$sSQLadd1.' TB.unae03idproceso=T1.unae06id AND TB.unae03idresponsable=T7.unad11id AND TB.unae03estado=T8.unae04id AND TB.unae03idjustificacion=T12.unae05id AND TB.unae03idautoriza=T13.unad11id '.$sSQLadd.'
ORDER BY TB.unae03idproceso, TB.unae03consec';
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
			//return array(utf8_encode($sErrConsulta.'<input id="paginaf203" name="paginaf203" type="hidden" value="'.$pagina.'"/><input id="lppf203" name="lppf203" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
<td><b>'.$ETI['unae03idproceso'].'</b></td>
<td><b>'.$ETI['unae03consec'].'</b></td>
<td><b>'.$ETI['unae03peraca'].'</b></td>
<td><b>'.$ETI['unae03curso'].'</b></td>
<td><b>'.$ETI['unae03escuela'].'</b></td>
<td colspan="2"><b>'.$ETI['unae03idresponsable'].'</b></td>
<td><b>'.$ETI['unae03estado'].'</b></td>
<td><b>'.$ETI['unae03fechagenera'].'</b></td>
<td><b>'.$ETI['unae03fecharesponde'].'</b></td>
<td><b>'.$ETI['unae03respuesta'].'</b></td>
<td><b>'.$ETI['unae03idjustificacion'].'</b></td>
<td colspan="2"><b>'.$ETI['unae03idautoriza'].'</b></td>
<td><b>'.$ETI['unae03fechaautoriza'].'</b></td>
<td align="right">
'.html_paginador('paginabusqueda', $registros, $lineastabla, $pagina, 'paginarbusqueda()').'
'.html_lpp('lppfbusqueda', $lineastabla, 'paginarbusqueda()').'
</td>
</tr>';
	$tlinea=1;
	while($filadet=$objDB->sf($tabladetalle)){
		$sPrefijo='<a href="javascript:Devuelve(\''.$filadet['unae03id'].'\');">';
		$sSufijo='</a>';
		$tlinea++;
		$et_unae03fechagenera='';
		if ($filadet['unae03fechagenera']!=0){$et_unae03fechagenera=fecha_desdenumero($filadet['unae03fechagenera']);}
		$et_unae03fecharesponde='';
		if ($filadet['unae03fecharesponde']!=0){$et_unae03fecharesponde=fecha_desdenumero($filadet['unae03fecharesponde']);}
		$et_unae03fechaautoriza='';
		if ($filadet['unae03fechaautoriza']!=0){$et_unae03fechaautoriza=fecha_desdenumero($filadet['unae03fechaautoriza']);}
		$res=$res.'<tr onmouseover="cambia_color_over(this);" onmouseout="cambia_color_out(this);">
<td>'.$sPrefijo.cadena_notildes($filadet['unae06nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['unae03consec'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['unae03peraca'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['unae03curso'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['unae03escuela'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['C7_td'].' '.$filadet['C7_doc'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['C7_nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['unae04nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.$et_unae03fechagenera.$sSufijo.'</td>
<td>'.$sPrefijo.$et_unae03fecharesponde.$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['unae03respuesta'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['unae05titulo']).$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['C13_td'].' '.$filadet['C13_doc'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['C13_nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.$et_unae03fechaautoriza.$sSufijo.'</td>
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
function f203_db_GuardarRespuesta($DATA, $objDB, $bDebug=false){
	$iCodModulo=f203_CodModulo();
	$bAudita[3]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_203=$APP->rutacomun.'lg/lg_203_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_203)){$mensajes_203=$APP->rutacomun.'lg/lg_203_es.php';}
	require $mensajes_todas;
	require $mensajes_203;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	// -- Se inicia validando todas las posibles entradas de usuario.
	/*
	if (isset($DATA['unae03idproceso'])==0){$DATA['unae03idproceso']='';}
	if (isset($DATA['unae03consec'])==0){$DATA['unae03consec']='';}
	if (isset($DATA['unae03id'])==0){$DATA['unae03id']='';}
	if (isset($DATA['unae03peraca'])==0){$DATA['unae03peraca']='';}
	if (isset($DATA['unae03curso'])==0){$DATA['unae03curso']='';}
	if (isset($DATA['unae03escuela'])==0){$DATA['unae03escuela']='';}
	if (isset($DATA['unae03idresponsable'])==0){$DATA['unae03idresponsable']='';}
	if (isset($DATA['unae03estado'])==0){$DATA['unae03estado']='';}
	if (isset($DATA['unae03fechagenera'])==0){$DATA['unae03fechagenera']='';}
	if (isset($DATA['unae03fecharesponde'])==0){$DATA['unae03fecharesponde']='';}
	if (isset($DATA['unae03respuesta'])==0){$DATA['unae03respuesta']='';}
	if (isset($DATA['unae03idjustificacion'])==0){$DATA['unae03idjustificacion']='';}
	if (isset($DATA['unae03fechaautoriza'])==0){$DATA['unae03fechaautoriza']='';}
	*/
	$DATA['unae03estado']=numeros_validar($DATA['unae03estado']);
	$DATA['unae03respuesta']=htmlspecialchars(trim($DATA['unae03respuesta']));
	$DATA['unae03idjustificacion']=numeros_validar($DATA['unae03idjustificacion']);
	// -- Se inicializan las variables que puedan pasar vacias {Especialmente números}.
	//if ($DATA['unae03peraca']==''){$DATA['unae03peraca']=0;}
	//if ($DATA['unae03curso']==''){$DATA['unae03curso']=0;}
	//if ($DATA['unae03escuela']==''){$DATA['unae03escuela']=0;}
	//if ($DATA['unae03estado']==''){$DATA['unae03estado']=0;}
	//if ($DATA['unae03idjustificacion']==''){$DATA['unae03idjustificacion']=0;}
	// -- Seccion para validar los posibles causales de error.
	$sSepara=', ';
	if (true){
		if ($DATA['unae03idjustificacion']==''){$sError=$ERR['unae03idjustificacion'].$sSepara.$sError;}
		if ($DATA['unae03respuesta']==''){$sError=$ERR['unae03respuesta'].$sSepara.$sError;}
		//Fin de las valiaciones NO LLAVE.
		}
	//Valiaciones de campos obligatorios en todo guardar.
	if ($DATA['unae03idproceso']==''){$sError=$ERR['unae03idproceso'];}
	// -- Se verifican los valores de campos de otras tablas.
	if ($sError==''){
		if (!seg_revisa_permiso($iCodModulo, 3, $objDB)){$sError=$ERR['3'];}
		}
	if ($sError==''){
		if (get_magic_quotes_gpc()==1){$DATA['unae03respuesta']=stripslashes($DATA['unae03respuesta']);}
		//Si el campo unae03respuesta permite html quite la linea htmlspecialchars para el campo y habilite la siguiente linea:
		//$unae03respuesta=addslashes($DATA['unae03respuesta']);
		$unae03respuesta=str_replace('"', '\"', $DATA['unae03respuesta']);
		$bpasa=false;
		if ($DATA['paso']==10){
			}else{
			$DATA['unae03estado']=1;
			$DATA['unae03fecharesponde']=fecha_DiaMod();
			$scampo[1]='unae03estado';
			$scampo[2]='unae03fecharesponde';
			$scampo[3]='unae03respuesta';
			$scampo[4]='unae03idjustificacion';
			$sdato[1]=$DATA['unae03estado'];
			$sdato[2]=$DATA['unae03fecharesponde'];
			$sdato[3]=$unae03respuesta;
			$sdato[4]=$DATA['unae03idjustificacion'];
			$numcmod=4;
			$sWhere='unae03id='.$DATA['unae03id'].'';
			$sSQL='SELECT * FROM unae03noconforme WHERE '.$sWhere;
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
					$sSQL='UPDATE unae03noconforme SET '.utf8_encode($sdatos).' WHERE '.$sWhere.';';
					}else{
					$sdetalle=$sdatos.'['.$sWhere.']';
					$sSQL='UPDATE unae03noconforme SET '.$sdatos.' WHERE '.$sWhere.';';
					}
				$idaccion=3;
				}
			}
		if ($bpasa){
			$result=$objDB->ejecutasql($sSQL);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' [203] ..<!-- '.$sSQL.' -->';
				if ($idaccion==2){$DATA['unae03id']='';}
				$DATA['paso']=$DATA['paso']-10;
				}else{
				if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Guardar 203 '.$sSQL.'<br>';}
				if ($bAudita[$idaccion]){seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], $idaccion, $DATA['unae03id'], $sdetalle, $objDB);}
				}
			}
		}
	$DATA['paso']=2;
	//if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' InfoDepura<br>';}
	return array($DATA, $sError, $iTipoError, $sDebug);
	}
function f203_db_GuardarAprobar($DATA, $objDB, $bDebug=false){
	$iCodModulo=f203_CodModulo();
	$bAudita[3]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_203=$APP->rutacomun.'lg/lg_203_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_203)){$mensajes_203=$APP->rutacomun.'lg/lg_203_es.php';}
	require $mensajes_todas;
	require $mensajes_203;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	// -- Se inicia validando todas las posibles entradas de usuario.
	/*
	if (isset($DATA['unae03idproceso'])==0){$DATA['unae03idproceso']='';}
	if (isset($DATA['unae03consec'])==0){$DATA['unae03consec']='';}
	if (isset($DATA['unae03id'])==0){$DATA['unae03id']='';}
	if (isset($DATA['unae03peraca'])==0){$DATA['unae03peraca']='';}
	if (isset($DATA['unae03curso'])==0){$DATA['unae03curso']='';}
	if (isset($DATA['unae03escuela'])==0){$DATA['unae03escuela']='';}
	if (isset($DATA['unae03idresponsable'])==0){$DATA['unae03idresponsable']='';}
	if (isset($DATA['unae03estado'])==0){$DATA['unae03estado']='';}
	if (isset($DATA['unae03fechagenera'])==0){$DATA['unae03fechagenera']='';}
	if (isset($DATA['unae03fecharesponde'])==0){$DATA['unae03fecharesponde']='';}
	if (isset($DATA['unae03respuesta'])==0){$DATA['unae03respuesta']='';}
	if (isset($DATA['unae03idjustificacion'])==0){$DATA['unae03idjustificacion']='';}
	if (isset($DATA['unae03fechaautoriza'])==0){$DATA['unae03fechaautoriza']='';}
	*/
	$DATA['unae03estado']=numeros_validar($DATA['unae03estado']);
	// -- Se inicializan las variables que puedan pasar vacias {Especialmente números}.
	//if ($DATA['unae03peraca']==''){$DATA['unae03peraca']=0;}
	//if ($DATA['unae03curso']==''){$DATA['unae03curso']=0;}
	//if ($DATA['unae03escuela']==''){$DATA['unae03escuela']=0;}
	//if ($DATA['unae03estado']==''){$DATA['unae03estado']=0;}
	//if ($DATA['unae03idjustificacion']==''){$DATA['unae03idjustificacion']=0;}
	// -- Seccion para validar los posibles causales de error.
	$sSepara=', ';
	if (true){
		//Fin de las valiaciones NO LLAVE.
		}
	//Valiaciones de campos obligatorios en todo guardar.
	if ($DATA['unae03idproceso']==''){$sError=$ERR['unae03idproceso'];}
	// -- Se verifican los valores de campos de otras tablas.
	if ($sError==''){
		if (!seg_revisa_permiso($iCodModulo, 3, $objDB)){$sError=$ERR['3'];}
		}
	if ($sError==''){
		$bpasa=false;
		if ($DATA['paso']==10){
			}else{
			if ($DATA['unae03estado']==7){
				$DATA['unae03fechaautoriza']=fecha_DiaMod();
				}
			$scampo[1]='unae03estado';
			$scampo[2]='unae03fechaautoriza';
			$sdato[1]=$DATA['unae03estado'];
			$sdato[2]=$DATA['unae03fechaautoriza'];
			$numcmod=2;
			$sWhere='unae03id='.$DATA['unae03id'].'';
			$sSQL='SELECT * FROM unae03noconforme WHERE '.$sWhere;
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
					$sSQL='UPDATE unae03noconforme SET '.utf8_encode($sdatos).' WHERE '.$sWhere.';';
					}else{
					$sdetalle=$sdatos.'['.$sWhere.']';
					$sSQL='UPDATE unae03noconforme SET '.$sdatos.' WHERE '.$sWhere.';';
					}
				$idaccion=3;
				}
			}
		if ($bpasa){
			$result=$objDB->ejecutasql($sSQL);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' [203] ..<!-- '.$sSQL.' -->';
				if ($idaccion==2){$DATA['unae03id']='';}
				$DATA['paso']=$DATA['paso']-10;
				}else{
				if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Guardar 203 '.$sSQL.'<br>';}
				if ($bAudita[$idaccion]){seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], $idaccion, $DATA['unae03id'], $sdetalle, $objDB);}
				}
			}
		}
	$DATA['paso']=2;
	//if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' InfoDepura<br>';}
	return array($DATA, $sError, $iTipoError, $sDebug);
	}
?>