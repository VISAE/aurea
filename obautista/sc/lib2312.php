<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2018 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.22.3 lunes, 6 de agosto de 2018
--- 2312 cara01encuesta
*/
function f2312_HTMLComboV2_cara01idperaca($objDB, $objCombos, $valor){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('cara01idperaca', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	$objCombos->sAccion='RevisaLlave();';
	$sSQL=f146_ConsultaCombo(2301, $objDB);
	/*
	$sIds='-99';
	$sSQL='SELECT cara01idperaca FROM cara01encuesta GROUP BY cara01idperaca';
	$tabla=$objDB->ejecutasql($sSQL);
	while($fila=$objDB->sf($tabla)){
		$sIds=$sIds.','.$fila['cara01idperaca'];
		}
	$sSQL='SELECT exte02id AS id, CONCAT(CASE exte02vigente WHEN "S" THEN "" ELSE "[" END, exte02nombre," {",exte02id,"} ",CASE exte02vigente WHEN "S" THEN "" ELSE " - INACTIVO]" END) AS nombre FROM exte02per_aca WHERE exte02id IN ('.$sIds.') ORDER BY exte02vigente DESC, exte02id DESC';
	*/
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f2312_HTMLComboV2_cara01idperaca_nuevo($objDB, $objCombos, $valor){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('cara01idperaca_nuevo', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	$objCombos->sAccion='';
	$sSQL=f146_ConsultaCombo('', $objDB);
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f2312_HTMLComboV2_cara01idzona($objDB, $objCombos, $valor){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('cara01idzona', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	$objCombos->sAccion='carga_combo_cara01idcead()';
	$sSQL='SELECT unad23id AS id, unad23nombre AS nombre FROM unad23zona';
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f2312_HTMLComboV2_cara01idcead($objDB, $objCombos, $valor, $vrcara01idzona){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	//@@ Se debe arreglar la condicion..
	$sCondi='unad24idzona="'.$vrcara01idzona.'"';
	if ($sCondi!=''){$sCondi=' WHERE '.$sCondi;}
	$objCombos->nuevo('cara01idcead', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	$sSQL='SELECT unad24id AS id, unad24nombre AS nombre FROM unad24sede'.$sCondi.'';
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f2312_HTMLComboV2_cara01tipocaracterizacion($objDB, $objCombos, $valor){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('cara01tipocaracterizacion', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	$sSQL='SELECT cara11id AS id, cara11nombre AS nombre FROM cara11tipocaract';
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f2312_Combocara01idcead($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$objCombos=new clsHtmlCombos('n');
	$html_cara01idcead=f2312_HTMLComboV2_cara01idcead($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_cara01idcead', 'innerHTML', $html_cara01idcead);
	return $objResponse;
	}
function f2312_ExisteDato($datos){
	if(!is_array($datos)){$datos=json_decode(str_replace('\"','"',$datos),true);}
	$_SESSION['u_ultimominuto']=iminutoavance();
	$bHayLlave=true;
	$cara01idperaca=numeros_validar($datos[1]);
	if ($cara01idperaca==''){$bHayLlave=false;}
	$cara01idtercero=numeros_validar($datos[2]);
	if ($cara01idtercero==''){$bHayLlave=false;}
	if ($bHayLlave){
		require './app.php';
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		$sSQL='SELECT cara01idtercero FROM cara01encuesta WHERE cara01idperaca='.$cara01idperaca.' AND cara01idtercero='.$cara01idtercero.'';
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
function f2312_Busquedas($aParametros){
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2312=$APP->rutacomun.'lg/lg_2301_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2312)){$mensajes_2312=$APP->rutacomun.'lg/lg_2301_es.php';}
	require $mensajes_todas;
	require $mensajes_2312;
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
		case 'cara01idtercero':
		require $APP->rutacomun.'lib111.php';
		$sTabla=f111_TablaDetalleBusquedas($aParametrosB, $objDB);
		$sTitulo='Busqueda de terceros';
		$sParams=f111_ParametrosBusqueda();
		$sJavaBusqueda=f111_JavaScriptBusqueda(2312);
		break;
		case 'cara01idconsejero':
		require $APP->rutacomun.'lib111.php';
		$sTabla=f111_TablaDetalleBusquedas($aParametrosB, $objDB);
		$sTitulo='Busqueda de terceros';
		$sParams=f111_ParametrosBusqueda();
		$sJavaBusqueda=f111_JavaScriptBusqueda(2312);
		break;
		}
	$sTitulo='<h2>'.$ETI['titulo_2312'].' - '.$sTitulo.'</h2>';
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97titulo', 'innerHTML', $sTitulo);
	$objResponse->assign('div_97params', 'innerHTML', $sParams);
	$objResponse->assign('div_97tabla', 'innerHTML', $sTabla);
	$objResponse->setFunction('paginarbusqueda','',$sJavaBusqueda);
	$objResponse->call('expandesector(97)');
	return $objResponse;
	}
function f2312_HtmlBusqueda($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$sError='';
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$sDetalle='';
	switch($aParametros[100]){
		case 'cara01idtercero':
		require $APP->rutacomun.'lib111.php';
		$sDetalle=f111_TablaDetalleBusquedas($aParametros, $objDB);
		break;
		case 'cara01idconsejero':
		require $APP->rutacomun.'lib111.php';
		$sDetalle=f111_TablaDetalleBusquedas($aParametros, $objDB);
		break;
		}
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97tabla', 'innerHTML', $sDetalle);
	return $objResponse;
	}
function f2312_TablaDetalleV2($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2312=$APP->rutacomun.'lg/lg_2301_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2312)){$mensajes_2312=$APP->rutacomun.'lg/lg_2301_es.php';}
	require $mensajes_todas;
	require $mensajes_2312;
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
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
	if ($aParametros[103]!=''){$sSQLadd=$sSQLadd.' AND T2.unad11doc LIKE "%'.$aParametros[103].'%"';}
	if ($aParametros[104]!=''){
		$sBase=trim(strtoupper($aParametros[104]));
		$aNoms=explode(' ', $sBase);
		for ($k=1;$k<=count($aNoms);$k++){
			$sCadena=$aNoms[$k-1];
			if ($sCadena!=''){
				$sSQLadd=$sSQLadd.' AND T2.unad11razonsocial LIKE "%'.$sCadena.'%"';
				//$sSQLadd1=$sSQLadd1.'T1.sys11razonsocial LIKE "%'.$sCadena.'%" AND ';
				}
			}
		}
	if ($aParametros[105]!=''){$sSQLadd1=$sSQLadd1.'TB.cara01idperaca='.$aParametros[105].' AND ';}
	//cara01tipocaracterizacion
	if ($aParametros[111]!=''){$sSQLadd1=$sSQLadd1.'TB.cara01tipocaracterizacion='.$aParametros[111].' AND ';}
	if ($aParametros[108]!=''){
		$sSQLadd1=$sSQLadd1.'TB.cara01idprograma='.$aParametros[108].' AND ';
		}else{
		if ($aParametros[107]!=''){$sSQLadd1=$sSQLadd1.'TB.cara01idescuela='.$aParametros[107].' AND ';}
		}
	if ($aParametros[110]!=''){
		$sSQLadd1=$sSQLadd1.'TB.cara01idcead='.$aParametros[110].' AND ';
		}else{
		if ($aParametros[109]!=''){$sSQLadd1=$sSQLadd1.'TB.cara01idzona='.$aParametros[109].' AND ';}
		}
	switch($aParametros[106]){
		case 1: //Donde es consejero
		$sSQLadd1=$sSQLadd1.'TB.cara01idconsejero='.$idTercero.' AND ';
		break;
		case 11: //Terminadas
		$sSQLadd1=$sSQLadd1.'TB.cara01completa="S" AND ';
		break;
		case 12: // Incompletas.
		$sSQLadd1=$sSQLadd1.'TB.cara01completa<>"S" AND ';
		break;
		}
	$sTitulos='Periodo,TipoDoc,Documento,Estudiante,Completa,Fecha encuesta';
	$sSQL='SELECT T1.exte02nombre, T2.unad11tipodoc AS C2_td, T2.unad11doc AS C2_doc, T2.unad11razonsocial AS C2_nombre, TB.cara01completa, TB.cara01fechaencuesta, TB.cara01id, TB.cara01idperaca 
FROM cara01encuesta AS TB, exte02per_aca AS T1, unad11terceros AS T2 
WHERE '.$sSQLadd1.' TB.cara01idperaca=T1.exte02id AND TB.cara01idtercero=T2.unad11id '.$sSQLadd.'
ORDER BY TB.cara01idperaca DESC, T2.unad11doc';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_2312" name="consulta_2312" type="hidden" value="'.$sSQLlista.'"/>
<input id="titulos_2312" name="titulos_2312" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objDB->ejecutasql($sSQL);
	if ($tabladetalle==false){
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 2312: '.$sSQL.'<br>';}
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		//$sLeyenda=$sSQL;
		}else{
		$registros=$objDB->nf($tabladetalle);
		if ($registros==0){
			//return array(utf8_encode($sErrConsulta.'<input id="paginaf2312" name="paginaf2312" type="hidden" value="'.$pagina.'"/><input id="lppf2312" name="lppf2312" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
<td colspan="2"><b>'.$ETI['cara01idtercero'].'</b></td>
<td><b>'.$ETI['cara01completa'].'</b></td>
<td><b>'.$ETI['cara01fechaencuesta'].'</b></td>
<td align="right">
'.html_paginador('paginaf2312', $registros, $lineastabla, $pagina, 'paginarf2312()').'
'.html_lpp('lppf2312', $lineastabla, 'paginarf2312()').'
</td>
</tr>';
	$tlinea=1;
	$idPeraca=-1;
	while($filadet=$objDB->sf($tabladetalle)){
		if ($idPeraca!=$filadet['cara01idperaca']){
			$idPeraca=$filadet['cara01idperaca'];
			$res=$res.'<tr class="fondoazul">
<td colspan="5">'.$ETI['cara01idperaca'].' <b>'.cadena_notildes($filadet['exte02nombre']).'</b></td>
</tr>';
			}
		$sPrefijo='';
		$sSufijo='';
		$sClass='';
		$sLink='';
		$et_cara01completa=$ETI['msg_abierto'];
		$et_cara01fechaencuesta='';
		if ($filadet['cara01completa']=='S'){
			$sPrefijo='<b>';
			$sSufijo='</b>';
			$et_cara01completa=$ETI['msg_cerrado'];
			$et_cara01fechaencuesta=fecha_desdenumero($filadet['cara01fechaencuesta']);
			}
		if(($tlinea%2)==0){$sClass=' class="resaltetabla"';}
		$tlinea++;
		if ($babierta){
			$sLink='<a href="javascript:cargaridf2312('.$filadet['cara01id'].')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$res=$res.'<tr'.$sClass.'>
<td>'.$sPrefijo.$filadet['C2_td'].' '.$filadet['C2_doc'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['C2_nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.$et_cara01completa.$sSufijo.'</td>
<td>'.$sPrefijo.$et_cara01fechaencuesta.$sSufijo.'</td>
<td>'.$sLink.'</td>
</tr>';
		}
	$res=$res.'</table>';
	$objDB->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
	}
function f2312_HtmlTabla($aParametros){
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
	list($sDetalle, $sDebugTabla)=f2312_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f2312detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f2312_db_CargarPadre($DATA, $objDB, $bDebug=false){
	$sError='';
	$iTipoError=0;
	$sDebug='';
	require './app.php';
	$DATA['cara01idtercero_td']=$APP->tipo_doc;
	$DATA['cara01idtercero_doc']='';
	$DATA['cara01idconsejero_td']=$APP->tipo_doc;
	$DATA['cara01idconsejero_doc']='';
	if ($DATA['paso']==1){
		$sSQLcondi='cara01idperaca='.$DATA['cara01idperaca'].' AND cara01idtercero="'.$DATA['cara01idtercero'].'"';
		}else{
		$sSQLcondi='cara01id='.$DATA['cara01id'].'';
		}
	$sSQL='SELECT * FROM cara01encuesta WHERE '.$sSQLcondi;
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$DATA['cara01idperaca']=$fila['cara01idperaca'];
		$DATA['cara01idtercero']=$fila['cara01idtercero'];
		$DATA['cara01id']=$fila['cara01id'];
		$DATA['cara01completa']=$fila['cara01completa'];
		$DATA['cara01idzona']=$fila['cara01idzona'];
		$DATA['cara01idcead']=$fila['cara01idcead'];
		$DATA['cara01tipocaracterizacion']=$fila['cara01tipocaracterizacion'];
		$DATA['cara01idconsejero']=$fila['cara01idconsejero'];
		$bcargo=true;
		$DATA['paso']=2;
		$DATA['boculta2312']=0;
		$bLimpiaHijos=true;
		}else{
		$DATA['paso']=0;
		}
	return array($DATA, $sError, $iTipoError, $sDebug);
	}
function f2312_Cerrar($cara01id, $objDB, $bDebug=false){
	$sInfo='';
	$sDebug='';
	return array($sInfo, $sDebug);
	}
function f2312_db_GuardarV2($DATA, $objDB, $bDebug=false){
	$iCodModulo=2312;
	$bAudita[2]=true;
	$bAudita[3]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2312=$APP->rutacomun.'lg/lg_2301_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2312)){$mensajes_2312=$APP->rutacomun.'lg/lg_2301_es.php';}
	require $mensajes_todas;
	require $mensajes_2312;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	$bCerrando=false;
	$sErrorCerrando='';
	// -- Se inicia validando todas las posibles entradas de usuario.
	/*
	if (isset($DATA['cara01idperaca'])==0){$DATA['cara01idperaca']='';}
	if (isset($DATA['cara01idtercero'])==0){$DATA['cara01idtercero']='';}
	if (isset($DATA['cara01id'])==0){$DATA['cara01id']='';}
	if (isset($DATA['cara01completa'])==0){$DATA['cara01completa']='';}
	if (isset($DATA['cara01idzona'])==0){$DATA['cara01idzona']='';}
	if (isset($DATA['cara01idcead'])==0){$DATA['cara01idcead']='';}
	if (isset($DATA['cara01tipocaracterizacion'])==0){$DATA['cara01tipocaracterizacion']='';}
	*/
	$DATA['cara01idperaca']=numeros_validar($DATA['cara01idperaca']);
	$DATA['cara01idzona']=numeros_validar($DATA['cara01idzona']);
	$DATA['cara01idcead']=numeros_validar($DATA['cara01idcead']);
	$DATA['cara01tipocaracterizacion']=numeros_validar($DATA['cara01tipocaracterizacion']);
	// -- Se inicializan las variables que puedan pasar vacias {Especialmente números}.
	if ($DATA['cara01completa']==''){$DATA['cara01completa']='N';}
	//if ($DATA['cara01idzona']==''){$DATA['cara01idzona']=0;}
	//if ($DATA['cara01idcead']==''){$DATA['cara01idcead']=0;}
	//if ($DATA['cara01tipocaracterizacion']==''){$DATA['cara01tipocaracterizacion']=0;}
	// -- Seccion para validar los posibles causales de error.
	$sSepara=', ';
	//if ($DATA['cara01completa']=='S'){
		//if ($DATA['cara01idconsejero']==0){$sError=$ERR['cara01idconsejero'].$sSepara.$sError;}
		if ($DATA['cara01tipocaracterizacion']==''){$sError=$ERR['cara01tipocaracterizacion'].$sSepara.$sError;}
		if ($DATA['cara01idcead']==''){$sError=$ERR['cara01idcead'].$sSepara.$sError;}
		if ($DATA['cara01idzona']==''){$sError=$ERR['cara01idzona'].$sSepara.$sError;}
		if ($sError!=''){$DATA['cara01completa']='N';}
		$sErrorCerrando=$sError;
		$sError='';
		//Fin de las valiaciones NO LLAVE.
		//}
	//Valiaciones de campos obligatorios en todo guardar.
	if ($DATA['cara01idtercero']==0){$sError=$ERR['cara01idtercero'];}
	if ($DATA['cara01idperaca']==''){$sError=$ERR['cara01idperaca'];}
	// -- Tiene un cerrado.
	if ($DATA['cara01completa']=='S'){
		//Validaciones previas a cerrar
		//Aprobó las Validaciones al cerrar
		if ($sError.$sErrorCerrando!=''){
			$DATA['cara01completa']='N';
			$sErrorCerrando=$sError.' '.$sErrorCerrando;
			$sError='';
			}else{
			$bCerrando=true;
			//Acciones del cierre
			}
		}
	// -- Fin del cerrado.
	// -- Se verifican los valores de campos de otras tablas.
	//if ($sError==''){$sError=tabla_terceros_existe($DATA['cara01idconsejero_td'], $DATA['cara01idconsejero_doc'], $objDB, 'El tercero Consejero ');}
	//if ($sError==''){
		//list($sError, $sInfo)=tercero_Bloqueado($DATA['cara01idconsejero'], $objDB);
		//if ($sInfo!=''){$sError=$sError.'<br>'.sInfo;}
		//}
	if ($sError==''){$sError=tabla_terceros_existe($DATA['cara01idtercero_td'], $DATA['cara01idtercero_doc'], $objDB, 'El tercero Tercero ');}
	if ($sError==''){
		list($sError, $sInfo)=tercero_Bloqueado($DATA['cara01idtercero'], $objDB);
		if ($sInfo!=''){$sError=$sError.'<br>'.sInfo;}
		}
	$bAjustaTipoEncuesta=false;
	if ($sError==''){
		if ($DATA['paso']==10){
			$sError=$ERR['2'];
			}else{
			if (!seg_revisa_permiso($iCodModulo, 3, $objDB)){$sError=$ERR['3'];}
			}
		}
	if ($sError==''){
		if ($DATA['paso']==10){
			}
		}
	if ($sError==''){
		$bpasa=false;
		if ($DATA['paso']==10){
			}else{
			$scampo[1]='cara01completa';
			$scampo[2]='cara01idzona';
			$scampo[3]='cara01idcead';
			$scampo[4]='cara01tipocaracterizacion';
			$sdato[1]=$DATA['cara01completa'];
			$sdato[2]=$DATA['cara01idzona'];
			$sdato[3]=$DATA['cara01idcead'];
			$sdato[4]=$DATA['cara01tipocaracterizacion'];
			$numcmod=4;
			$sWhere='cara01id='.$DATA['cara01id'].'';
			$sSQL='SELECT * FROM cara01encuesta WHERE '.$sWhere;
			$sdatos='';
			$bPrimera=true;
			$result=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($result)>0){
				$filabase=$objDB->sf($result);
				if ($filabase['cara01tipocaracterizacion']!=$DATA['cara01tipocaracterizacion']){
					$bAjustaTipoEncuesta=true;
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
					$sSQL='UPDATE cara01encuesta SET '.utf8_encode($sdatos).' WHERE '.$sWhere.';';
					}else{
					$sdetalle=$sdatos.'['.$sWhere.']';
					$sSQL='UPDATE cara01encuesta SET '.$sdatos.' WHERE '.$sWhere.';';
					}
				$idaccion=3;
				}
			}
		if ($bpasa){
			$result=$objDB->ejecutasql($sSQL);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' [2312] ..<!-- '.$sSQL.' -->';
				if ($idaccion==2){$DATA['cara01id']='';}
				$DATA['paso']=$DATA['paso']-10;
				$bCerrando=false;
				}else{
				if ($bAjustaTipoEncuesta){
					list($sErrorP, $sDebugP)=f2301_AjustarTipoEncuesta($DATA['cara01id'], $objDB, $bDebug);
					$sDebug=$sDebug.$sDebugP;
					}
				if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Guardar 2312 '.$sSQL.'<br>';}
				if ($bAudita[$idaccion]){seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], $idaccion, $DATA['cara01id'], $sdetalle, $objDB);}
				$DATA['paso']=2;
				}
			}else{
			$DATA['paso']=2;
			}
		}else{
		$DATA['paso']=$DATA['paso']-10;
		$bCerrando=false;
		}
	$sInfoCierre='';
	if ($bCerrando){
		list($sErrorCerrando, $sDebugCerrar)=f2312_Cerrar($DATA['cara01id'], $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugCerrar;
		}
	//if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' InfoDepura<br>';}
	return array($DATA, $sError, $iTipoError, $bCerrando, $sErrorCerrando, $sDebug);
	}
function f2312_db_Eliminar($cara01id, $objDB, $bDebug=false){
	$iCodModulo=2312;
	$bAudita[4]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2312=$APP->rutacomun.'lg/lg_2301_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2312)){$mensajes_2312=$APP->rutacomun.'lg/lg_2301_es.php';}
	require $mensajes_todas;
	require $mensajes_2312;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	$cara01id=numeros_validar($cara01id);
	// Traer los datos para hacer las validaciones.
	if ($sError==''){
		$sSQL='SELECT * FROM cara01encuesta WHERE cara01id='.$cara01id.'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$filabase=$objDB->sf($tabla);
			}else{
			$sError='No se encuentra el registro solicitado {Ref: '.$cara01id.'}';
			}
		}
	if ($sError==''){
		if (!seg_revisa_permiso($iCodModulo, 4, $objDB)){
			$sError=$ERR['4'];
			}
		}
	if ($sError==''){
		$sSQL='SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=2312';
		$tablaor=$objDB->ejecutasql($sSQL);
		while ($filaor=$objDB->sf($tablaor)){
			$sSQL='SELECT '.$filaor['campoid'].' FROM '.$filaor['tabla'].' WHERE '.$filaor['camporev'].'='.$_REQUEST['cara01id'].' LIMIT 0, 1';
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
		$sSQL='DELETE FROM cara10pregprueba WHERE cara10idcara='.$cara01id.'';
		$result=$objDB->ejecutasql($sSQL);
		$sWhere='cara01id='.$cara01id.'';
		//$sWhere='cara01idtercero="'.$filabase['cara01idtercero'].'" AND cara01idperaca='.$filabase['cara01idperaca'].'';
		$sSQL='DELETE FROM cara01encuesta WHERE '.$sWhere.';';
		$result=$objDB->ejecutasql($sSQL);
		if ($result==false){
			$sError=$ERR['falla_eliminar'].' .. <!-- '.$sSQL.' -->';
			}else{
			if ($bAudita[4]){seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $cara01id, $sWhere, $objDB);}
			}
		}
	return array($sError, $iTipoError, $sDebug);
	}
function f2312_TituloBusqueda(){
	return 'Busqueda de Ajustar encuesta';
	}
function f2312_ParametrosBusqueda(){
	$sParams='<label class="Label90">Nombre</label><label><input id="b2312nombre" name="b2312nombre" type="text" value="" onchange="paginarbusqueda()" /></label>';
	return $sParams;
	}
function f2312_JavaScriptBusqueda($iModuloBusca){
	$sRes='var sCampo=window.document.frmedita.scampobusca.value;
var params=new Array();
params[100]=sCampo;
params[101]=window.document.frmedita.paginabusqueda.value;
params[102]=window.document.frmedita.lppfbusqueda.value;
params[103]=window.document.frmedita.b2312nombre.value;
xajax_f'.$iModuloBusca.'_HtmlBusqueda(params);';
	return $sRes;
	}
function f2312_TablaDetalleBusquedas($aParametros, $objDB){
	$res='';
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2312=$APP->rutacomun.'lg/lg_2301_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2312)){$mensajes_2312=$APP->rutacomun.'lg/lg_2301_es.php';}
	require $mensajes_todas;
	require $mensajes_2312;
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	if (isset($aParametros[101])==0){$aParametros[101]=1;}
	if (isset($aParametros[102])==0){$aParametros[102]=20;}
	if (isset($aParametros[104])==0){$aParametros[104]='';}
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
	$sTitulos='Peraca, Tercero, Id, Completa, Zona, Cead, Tipocaracterizacion, Consejero';
	$sSQL='SELECT T1.exte02nombre, T2.unad11razonsocial AS C2_nombre, TB.cara01id, TB.cara01completa, T5.unad23nombre, T6.unad24nombre, T7.cara11nombre, T8.unad11razonsocial AS C8_nombre, TB.cara01idperaca, TB.cara01idtercero, T2.unad11tipodoc AS C2_td, T2.unad11doc AS C2_doc, TB.cara01idzona, TB.cara01idcead, TB.cara01tipocaracterizacion, TB.cara01idconsejero, T8.unad11tipodoc AS C8_td, T8.unad11doc AS C8_doc 
FROM cara01encuesta AS TB, exte02per_aca AS T1, unad11terceros AS T2, unad23zona AS T5, unad24sede AS T6, cara11tipocaract AS T7, unad11terceros AS T8 
WHERE '.$sSQLadd1.' TB.cara01idperaca=T1.exte02id AND TB.cara01idtercero=T2.unad11id AND TB.cara01idzona=T5.unad23id AND TB.cara01idcead=T6.unad24id AND TB.cara01tipocaracterizacion=T7.cara11id AND TB.cara01idconsejero=T8.unad11id '.$sSQLadd.'
ORDER BY TB.cara01idperaca, TB.cara01idtercero';
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
			//return array(utf8_encode($sErrConsulta.'<input id="paginaf2312" name="paginaf2312" type="hidden" value="'.$pagina.'"/><input id="lppf2312" name="lppf2312" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
<td><b>'.$ETI['cara01idperaca'].'</b></td>
<td colspan="2"><b>'.$ETI['cara01idtercero'].'</b></td>
<td><b>'.$ETI['cara01completa'].'</b></td>
<td><b>'.$ETI['cara01idzona'].'</b></td>
<td><b>'.$ETI['cara01idcead'].'</b></td>
<td><b>'.$ETI['cara01tipocaracterizacion'].'</b></td>
<td colspan="2"><b>'.$ETI['cara01idconsejero'].'</b></td>
<td align="right">
'.html_paginador('paginabusqueda', $registros, $lineastabla, $pagina, 'paginarbusqueda()').'
'.html_lpp('lppfbusqueda', $lineastabla, 'paginarbusqueda()').'
</td>
</tr>';
	$tlinea=1;
	while($filadet=$objDB->sf($tabladetalle)){
		$sPrefijo='<a href="javascript:Devuelve(\''.$filadet['cara01id'].'\');">';
		$sSufijo='</a>';
		$tlinea++;
		$et_cara01completa=$ETI['msg_abierto'];
		if ($filadet['cara01completa']=='S'){$et_cara01completa=$ETI['msg_cerrado'];}
		$res=$res.'<tr onmouseover="cambia_color_over(this);" onmouseout="cambia_color_out(this);">
<td>'.$sPrefijo.cadena_notildes($filadet['exte02nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['C2_td'].' '.$filadet['C2_doc'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['C2_nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.$et_cara01completa.$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['unad23nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['unad24nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['cara11nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['C8_td'].' '.$filadet['C8_doc'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['C8_nombre']).$sSufijo.'</td>
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