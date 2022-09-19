<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2020 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.24.1 martes, 11 de febrero de 2020
--- Modelo Versión 2.24.1 lunes, 17 de febrero de 2020
--- Modelo Versión 2.25.3 martes, 14 de julio de 2020
--- 3002 saiu02tiposol
*/
/** Archivo lib3002.php.
* Libreria 3002 saiu02tiposol.
* @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
* @date martes, 11 de febrero de 2020
*/
function f3002_HTMLComboV2_saiu02idequiporesp($objDB, $objCombos, $valor, $vrsaiu02idunidadresp){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('saiu02idequiporesp', $valor, true, '{'.$ETI['msg_ninguno'].'}', 0);
	$sSQL='';
	if ($vrsaiu02idunidadresp!=''){
		$objCombos->iAncho=300;
		$sSQL='SELECT bita27id AS id, bita27nombre AS nombre FROM bita27equipotrabajo WHERE bita27idunidadfunc="'.$vrsaiu02idunidadresp.'" ORDER BY bita27nombre';
		}
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f3002_Combosaiu02idequiporesp($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$objCombos=new clsHtmlCombos('n');
	$html_saiu02idequiporesp=f3002_HTMLComboV2_saiu02idequiporesp($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_saiu02idequiporesp', 'innerHTML', $html_saiu02idequiporesp);
	//$objResponse->call('$("#saiu02idequiporesp").chosen()');
	return $objResponse;
	}
function f3002_ExisteDato($datos){
	if(!is_array($datos)){$datos=json_decode(str_replace('\"','"',$datos),true);}
	$_SESSION['u_ultimominuto']=iminutoavance();
	$bHayLlave=true;
	$saiu02consec=numeros_validar($datos[1]);
	if ($saiu02consec==''){$bHayLlave=false;}
	if ($bHayLlave){
		require './app.php';
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		$sSQL='SELECT saiu02consec FROM saiu02tiposol WHERE saiu02consec='.$saiu02consec.'';
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
function f3002_Busquedas($aParametros){
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3002='lg/lg_3002_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3002)){$mensajes_3002='lg/lg_3002_es.php';}
	require $mensajes_todas;
	require $mensajes_3002;
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
		case 'saiu02idliderrespon':
		require $APP->rutacomun.'lib111.php';
		$sTabla=f111_TablaDetalleBusquedas($aParametrosB, $objDB);
		$sTitulo='Busqueda de terceros';
		$sParams=f111_ParametrosBusqueda();
		$sJavaBusqueda=f111_JavaScriptBusqueda(3002);
		break;
		}
	$sTitulo='<h2>'.$ETI['titulo_3002'].' - '.$sTitulo.'</h2>';
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97titulo', 'innerHTML', $sTitulo);
	$objResponse->assign('div_97params', 'innerHTML', $sParams);
	$objResponse->assign('div_97tabla', 'innerHTML', $sTabla);
	$objResponse->setFunction('paginarbusqueda','',$sJavaBusqueda);
	$objResponse->call('expandesector(97)');
	return $objResponse;
	}
function f3002_HtmlBusqueda($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$sError='';
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$sDetalle='';
	switch($aParametros[100]){
		case 'saiu02idliderrespon':
		require $APP->rutacomun.'lib111.php';
		$sDetalle=f111_TablaDetalleBusquedas($aParametros, $objDB);
		break;
		}
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97tabla', 'innerHTML', $sDetalle);
	return $objResponse;
	}
function f3002_TablaDetalleV2($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3002='lg/lg_3002_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3002)){$mensajes_3002='lg/lg_3002_es.php';}
	require $mensajes_todas;
	require $mensajes_3002;
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	if (isset($aParametros[100])==0){$aParametros[100]=$_SESSION['unad_id_tercero'];}
	if (isset($aParametros[101])==0){$aParametros[101]=1;}
	if (isset($aParametros[102])==0){$aParametros[102]=20;}
	//if (isset($aParametros[103])==0){$aParametros[103]='';}
	//if (isset($aParametros[104])==0){$aParametros[104]='';}
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
		return array($sLeyenda.'<input id="paginaf3002" name="paginaf3002" type="hidden" value="'.$pagina.'"/><input id="lppf3002" name="lppf3002" type="hidden" value="'.$lineastabla.'"/>', $sDebug);
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
	if ($aParametros[104]!=''){$sSQLadd1=$sSQLadd1.'TB.saiu02idunidadresp='.$aParametros[104].' AND ';}
	if ($aParametros[103]!=''){
		$sBase=trim(strtoupper($aParametros[103]));
		$aNoms=explode(' ', $sBase);
		for ($k=1;$k<=count($aNoms);$k++){
			$sCadena=$aNoms[$k-1];
			if ($sCadena!=''){
				$sSQLadd=$sSQLadd.' AND TB.saiu02titulo LIKE "%'.$sCadena.'%"';
				//$sSQLadd1=$sSQLadd1.'T1.unad11razonsocial LIKE "%'.$sCadena.'%" AND ';
				}
			}
		}
	$sTitulos='Consec, Id, Titulo, Clasesol, Detalle, Unidadresp, Equiporesp, Liderrespon';
	$sSQL='SELECT TB.saiu02consec, TB.saiu02id, TB.saiu02titulo, T4.saiu01titulo, TB.saiu02detalle, TB.saiu02idequiporesp, TB.saiu02clasesol, TB.saiu02idunidadresp, TB.saiu02idliderrespon 
FROM saiu02tiposol AS TB, saiu01claseser AS T4 
WHERE '.$sSQLadd1.' TB.saiu02clasesol=T4.saiu01id '.$sSQLadd.'
ORDER BY T4.saiu01titulo, TB.saiu02consec';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_3002" name="consulta_3002" type="hidden" value="'.$sSQLlista.'"/>
<input id="titulos_3002" name="titulos_3002" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objDB->ejecutasql($sSQL);
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 3002: '.$sSQL.'<br>';}
	if ($tabladetalle==false){
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		//$sLeyenda=$sSQL;
		}else{
		$registros=$objDB->nf($tabladetalle);
		if ($registros==0){
			//return array(utf8_encode($sErrConsulta.'<input id="paginaf3002" name="paginaf3002" type="hidden" value="'.$pagina.'"/><input id="lppf3002" name="lppf3002" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
<td><b>'.$ETI['saiu02consec'].'</b></td>
<td><b>'.$ETI['saiu02titulo'].'</b></td>
<td><b>'.$ETI['saiu02idunidadresp'].'</b></td>
<td align="right">
'.html_paginador('paginaf3002', $registros, $lineastabla, $pagina, 'paginarf3002()').'
'.html_lpp('lppf3002', $lineastabla, 'paginarf3002()').'
</td>
</tr></thead>';
	$tlinea=1;
	$idClase=-99;
	while($filadet=$objDB->sf($tabladetalle)){
		if ($idClase!=$filadet['saiu02clasesol']){
			$idClase=$filadet['saiu02clasesol'];
			$res=$res.'<tr class="fondoazul">
<td colspan="4">'.$ETI['saiu02clasesol'].' <b>'.cadena_notildes($filadet['saiu01titulo']).'</b></td>
</tr>';
			}
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
		if ($bAbierta){
			$sLink='<a href="javascript:cargaridf3002('.$filadet['saiu02id'].')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$et_saiu02idunidadresp=f226_TituloUnidad($filadet['saiu02idunidadresp'], $objDB);
		$res=$res.'<tr'.$sClass.'>
<td>'.$sPrefijo.$filadet['saiu02consec'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['saiu02titulo']).$sSufijo.'</td>
<td>'.$sPrefijo.$et_saiu02idunidadresp.$sSufijo.'</td>
<td>'.$sLink.'</td>
</tr>';
		}
	$res=$res.'</table>';
	$objDB->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
	}
function f3002_HtmlTabla($aParametros){
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
	list($sDetalle, $sDebugTabla)=f3002_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f3002detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f3002_db_CargarPadre($DATA, $objDB, $bDebug=false){
	$sError='';
	$iTipoError=0;
	$sDebug='';
	require './app.php';
	$DATA['saiu02idliderrespon_td']=$APP->tipo_doc;
	$DATA['saiu02idliderrespon_doc']='';
	if ($DATA['paso']==1){
		$sSQLcondi='saiu02consec='.$DATA['saiu02consec'].'';
		}else{
		$sSQLcondi='saiu02id='.$DATA['saiu02id'].'';
		}
	$sSQL='SELECT * FROM saiu02tiposol WHERE '.$sSQLcondi;
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$DATA['saiu02consec']=$fila['saiu02consec'];
		$DATA['saiu02id']=$fila['saiu02id'];
		$DATA['saiu02titulo']=$fila['saiu02titulo'];
		$DATA['saiu02clasesol']=$fila['saiu02clasesol'];
		$DATA['saiu02detalle']=$fila['saiu02detalle'];
		$DATA['saiu02idunidadresp']=$fila['saiu02idunidadresp'];
		$DATA['saiu02idequiporesp']=$fila['saiu02idequiporesp'];
		$DATA['saiu02idliderrespon']=$fila['saiu02idliderrespon'];
		$DATA['saiu02ordenllamada']=$fila['saiu02ordenllamada'];
		$DATA['saiu02ordenchat']=$fila['saiu02ordenchat'];
		$DATA['saiu02ordencorreo']=$fila['saiu02ordencorreo'];
		$DATA['saiu02ordenpresencial']=$fila['saiu02ordenpresencial'];
		$DATA['saiu02ordensoporte']=$fila['saiu02ordensoporte'];
		$DATA['saiu02ordenpqrs']=$fila['saiu02ordenpqrs'];
		$DATA['saiu02ordentramites']=$fila['saiu02ordentramites'];
		$DATA['saiu02ordencorresp']=$fila['saiu02ordencorresp'];
		$bcargo=true;
		$DATA['paso']=2;
		$DATA['boculta3002']=0;
		$bLimpiaHijos=true;
		}else{
		$DATA['paso']=0;
		}
	return array($DATA, $sError, $iTipoError, $sDebug);
	}
function f3002_db_GuardarV2($DATA, $objDB, $bDebug=false){
	$iCodModulo=3002;
	$bAudita[2]=true;
	$bAudita[3]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3002='lg/lg_3002_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3002)){$mensajes_3002='lg/lg_3002_es.php';}
	require $mensajes_todas;
	require $mensajes_3002;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	// -- Se inicia validando todas las posibles entradas de usuario.
	/*
	if (isset($DATA['saiu02consec'])==0){$DATA['saiu02consec']='';}
	if (isset($DATA['saiu02id'])==0){$DATA['saiu02id']='';}
	if (isset($DATA['saiu02titulo'])==0){$DATA['saiu02titulo']='';}
	if (isset($DATA['saiu02clasesol'])==0){$DATA['saiu02clasesol']='';}
	if (isset($DATA['saiu02detalle'])==0){$DATA['saiu02detalle']='';}
	if (isset($DATA['saiu02idunidadresp'])==0){$DATA['saiu02idunidadresp']='';}
	if (isset($DATA['saiu02idequiporesp'])==0){$DATA['saiu02idequiporesp']='';}
	if (isset($DATA['saiu02idliderrespon'])==0){$DATA['saiu02idliderrespon']='';}
	if (isset($DATA['saiu02ordenllamada'])==0){$DATA['saiu02ordenllamada']='';}
	if (isset($DATA['saiu02ordenchat'])==0){$DATA['saiu02ordenchat']='';}
	if (isset($DATA['saiu02ordencorreo'])==0){$DATA['saiu02ordencorreo']='';}
	if (isset($DATA['saiu02ordenpresencial'])==0){$DATA['saiu02ordenpresencial']='';}
	if (isset($DATA['saiu02ordensoporte'])==0){$DATA['saiu02ordensoporte']='';}
	if (isset($DATA['saiu02ordenpqrs'])==0){$DATA['saiu02ordenpqrs']='';}
	if (isset($DATA['saiu02ordentramites'])==0){$DATA['saiu02ordentramites']='';}
	if (isset($DATA['saiu02ordencorresp'])==0){$DATA['saiu02ordencorresp']='';}
	*/
	$DATA['saiu02consec']=numeros_validar($DATA['saiu02consec']);
	$DATA['saiu02titulo']=htmlspecialchars(trim($DATA['saiu02titulo']));
	$DATA['saiu02clasesol']=numeros_validar($DATA['saiu02clasesol']);
	$DATA['saiu02detalle']=htmlspecialchars(trim($DATA['saiu02detalle']));
	$DATA['saiu02idunidadresp']=numeros_validar($DATA['saiu02idunidadresp']);
	$DATA['saiu02idequiporesp']=numeros_validar($DATA['saiu02idequiporesp']);
	$DATA['saiu02ordenllamada']=numeros_validar($DATA['saiu02ordenllamada']);
	$DATA['saiu02ordenchat']=numeros_validar($DATA['saiu02ordenchat']);
	$DATA['saiu02ordencorreo']=numeros_validar($DATA['saiu02ordencorreo']);
	$DATA['saiu02ordenpresencial']=numeros_validar($DATA['saiu02ordenpresencial']);
	$DATA['saiu02ordensoporte']=numeros_validar($DATA['saiu02ordensoporte']);
	$DATA['saiu02ordenpqrs']=numeros_validar($DATA['saiu02ordenpqrs']);
	$DATA['saiu02ordentramites']=numeros_validar($DATA['saiu02ordentramites']);
	$DATA['saiu02ordencorresp']=numeros_validar($DATA['saiu02ordencorresp']);
	// -- Se inicializan las variables que puedan pasar vacias {Especialmente números}.
	//if ($DATA['saiu02clasesol']==''){$DATA['saiu02clasesol']=0;}
	//if ($DATA['saiu02idunidadresp']==''){$DATA['saiu02idunidadresp']=0;}
	//if ($DATA['saiu02idequiporesp']==''){$DATA['saiu02idequiporesp']=0;}
	// -- Seccion para validar los posibles causales de error.
	$sSepara=', ';
	if (true){
		//if ($DATA['saiu02idliderrespon']==0){$sError=$ERR['saiu02idliderrespon'].$sSepara.$sError;}
		if ($DATA['saiu02idequiporesp']==''){$sError=$ERR['saiu02idequiporesp'].$sSepara.$sError;}
		if ($DATA['saiu02idunidadresp']==''){$sError=$ERR['saiu02idunidadresp'].$sSepara.$sError;}
		//if ($DATA['saiu02detalle']==''){$sError=$ERR['saiu02detalle'].$sSepara.$sError;}
		if ($DATA['saiu02clasesol']==''){$sError=$ERR['saiu02clasesol'].$sSepara.$sError;}
		if ($DATA['saiu02titulo']==''){$sError=$ERR['saiu02titulo'].$sSepara.$sError;}
		//Fin de las valiaciones NO LLAVE.
		}
	//Valiaciones de campos obligatorios en todo guardar.
	// -- Se verifican los valores de campos de otras tablas.
	if ($DATA['saiu02idliderrespon_doc']!=''){
		if ($sError==''){$sError=tabla_terceros_existe($DATA['saiu02idliderrespon_td'], $DATA['saiu02idliderrespon_doc'], $objDB, 'El tercero Liderrespon ');}
		if ($sError==''){
			list($sError, $sInfo)=tercero_Bloqueado($DATA['saiu02idliderrespon'], $objDB);
			if ($sInfo!=''){$sError=$sError.'<br>'.sInfo;}
			}
		}
	$bQuitarCodigo=false;
	$sCampoCodigo='';
	if ($sError==''){
		if ($DATA['paso']==10){
			if ($DATA['saiu02consec']==''){
				$DATA['saiu02consec']=tabla_consecutivo('saiu02tiposol', 'saiu02consec', '', $objDB);
				if ($DATA['saiu02consec']==-1){$sError=$objDB->serror;}
				$bQuitarCodigo=true;
				$sCampoCodigo='saiu02consec';
				}else{
				if (!seg_revisa_permiso($iCodModulo, 8, $objDB)){
					$sError=$ERR['8'];
					$DATA['saiu02consec']='';
					}
				}
			if ($sError==''){
				$sSQL='SELECT 1 FROM saiu02tiposol WHERE saiu02consec='.$DATA['saiu02consec'].'';
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
			$DATA['saiu02id']=tabla_consecutivo('saiu02tiposol','saiu02id', '', $objDB);
			if ($DATA['saiu02id']==-1){$sError=$objDB->serror;}
			}
		}
	if ($sError==''){
		if (get_magic_quotes_gpc()==1){$DATA['saiu02detalle']=stripslashes($DATA['saiu02detalle']);}
		//Si el campo saiu02detalle permite html quite la linea htmlspecialchars para el campo y habilite la siguiente linea:
		//$saiu02detalle=addslashes($DATA['saiu02detalle']);
		$saiu02detalle=str_replace('"', '\"', $DATA['saiu02detalle']);
		$bpasa=false;
		if ($DATA['paso']==10){
			$sCampos3002='saiu02consec, saiu02id, saiu02titulo, saiu02clasesol, saiu02detalle, 
saiu02idunidadresp, saiu02idequiporesp, saiu02idliderrespon, saiu02ordenllamada, saiu02ordenchat, 
saiu02ordencorreo, saiu02ordenpresencial, saiu02ordensoporte, saiu02ordenpqrs, saiu02ordentramites, 
saiu02ordencorresp';
			$sValores3002=''.$DATA['saiu02consec'].', '.$DATA['saiu02id'].', "'.$DATA['saiu02titulo'].'", '.$DATA['saiu02clasesol'].', "'.$saiu02detalle.'", 
'.$DATA['saiu02idunidadresp'].', '.$DATA['saiu02idequiporesp'].', '.$DATA['saiu02idliderrespon'].', '.$DATA['saiu02ordenllamada'].', '.$DATA['saiu02ordenchat'].', 
'.$DATA['saiu02ordencorreo'].', '.$DATA['saiu02ordenpresencial'].', '.$DATA['saiu02ordensoporte'].', '.$DATA['saiu02ordenpqrs'].', '.$DATA['saiu02ordentramites'].', 
'.$DATA['saiu02ordencorresp'].'';
			if ($APP->utf8==1){
				$sSQL='INSERT INTO saiu02tiposol ('.$sCampos3002.') VALUES ('.utf8_encode($sValores3002).');';
				$sdetalle=$sCampos3002.'['.utf8_encode($sValores3002).']';
				}else{
				$sSQL='INSERT INTO saiu02tiposol ('.$sCampos3002.') VALUES ('.$sValores3002.');';
				$sdetalle=$sCampos3002.'['.$sValores3002.']';
				}
			$idAccion=2;
			$bpasa=true;
			}else{
			$scampo[1]='saiu02titulo';
			$scampo[2]='saiu02clasesol';
			$scampo[3]='saiu02detalle';
			$scampo[4]='saiu02idunidadresp';
			$scampo[5]='saiu02idequiporesp';
			$scampo[6]='saiu02idliderrespon';
			$scampo[7]='saiu02ordenllamada';
			$scampo[8]='saiu02ordenchat';
			$scampo[9]='saiu02ordencorreo';
			$scampo[10]='saiu02ordenpresencial';
			$scampo[11]='saiu02ordensoporte';
			$scampo[12]='saiu02ordenpqrs';
			$scampo[13]='saiu02ordentramites';
			$scampo[14]='saiu02ordencorresp';
			$sdato[1]=$DATA['saiu02titulo'];
			$sdato[2]=$DATA['saiu02clasesol'];
			$sdato[3]=$saiu02detalle;
			$sdato[4]=$DATA['saiu02idunidadresp'];
			$sdato[5]=$DATA['saiu02idequiporesp'];
			$sdato[6]=$DATA['saiu02idliderrespon'];
			$sdato[7]=$DATA['saiu02ordenllamada'];
			$sdato[8]=$DATA['saiu02ordenchat'];
			$sdato[9]=$DATA['saiu02ordencorreo'];
			$sdato[10]=$DATA['saiu02ordenpresencial'];
			$sdato[11]=$DATA['saiu02ordensoporte'];
			$sdato[12]=$DATA['saiu02ordenpqrs'];
			$sdato[13]=$DATA['saiu02ordentramites'];
			$sdato[14]=$DATA['saiu02ordencorresp'];
			$numcmod=14;
			$sWhere='saiu02id='.$DATA['saiu02id'].'';
			$sSQL='SELECT * FROM saiu02tiposol WHERE '.$sWhere;
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
					$sSQL='UPDATE saiu02tiposol SET '.utf8_encode($sdatos).' WHERE '.$sWhere.';';
					}else{
					$sdetalle=$sdatos.'['.$sWhere.']';
					$sSQL='UPDATE saiu02tiposol SET '.$sdatos.' WHERE '.$sWhere.';';
					}
				$idAccion=3;
				}
			}
		if ($bpasa){
			$result=$objDB->ejecutasql($sSQL);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' [3002] ..<!-- '.$sSQL.' -->';
				if ($idAccion==2){
					$DATA['saiu02id']='';
					$bQuitarCodigo=true;
					$DATA['paso']=0;
					}else{
					$DATA['paso']=2;
					}
				}else{
				if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Guardar 3002 '.$sSQL.'<br>';}
				if ($bAudita[$idAccion]){seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], $idAccion, $DATA['saiu02id'], $sdetalle, $objDB);}
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
function f3002_db_Eliminar($saiu02id, $objDB, $bDebug=false){
	$iCodModulo=3002;
	$bAudita[4]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3002='lg/lg_3002_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3002)){$mensajes_3002='lg/lg_3002_es.php';}
	require $mensajes_todas;
	require $mensajes_3002;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	$saiu02id=numeros_validar($saiu02id);
	// Traer los datos para hacer las validaciones.
	if ($sError==''){
		$sSQL='SELECT * FROM saiu02tiposol WHERE saiu02id='.$saiu02id.'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$filabase=$objDB->sf($tabla);
			}else{
			$sError='No se encuentra el registro solicitado {Ref: '.$saiu02id.'}';
			}
		}
	if ($sError==''){
		if (!seg_revisa_permiso($iCodModulo, 4, $objDB)){
			$sError=$ERR['4'];
			}
		}
	if ($sError==''){
		$sSQL='SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=3002';
		$tablaor=$objDB->ejecutasql($sSQL);
		while ($filaor=$objDB->sf($tablaor)){
			$sSQL='SELECT '.$filaor['campoid'].' FROM '.$filaor['tabla'].' WHERE '.$filaor['camporev'].'='.$_REQUEST['saiu02id'].' LIMIT 0, 1';
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
		$sWhere='saiu02id='.$saiu02id.'';
		//$sWhere='saiu02consec='.$filabase['saiu02consec'].'';
		$sSQL='DELETE FROM saiu02tiposol WHERE '.$sWhere.';';
		$result=$objDB->ejecutasql($sSQL);
		if ($result==false){
			$sError=$ERR['falla_eliminar'].' .. <!-- '.$sSQL.' -->';
			}else{
			if ($bAudita[4]){seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $saiu02id, $sWhere, $objDB);}
			}
		}
	return array($sError, $iTipoError, $sDebug);
	}
function f3002_TituloBusqueda(){
	return 'Busqueda de Tipos de servicios';
	}
function f3002_ParametrosBusqueda(){
	$sParams='<label class="Label90">Nombre</label><label><input id="b3002nombre" name="b3002nombre" type="text" value="" onchange="paginarbusqueda()" /></label>';
	return $sParams;
	}
function f3002_JavaScriptBusqueda($iModuloBusca){
	$sRes='var sCampo=window.document.frmedita.scampobusca.value;
var params=new Array();
params[100]=sCampo;
params[101]=window.document.frmedita.paginabusqueda.value;
params[102]=window.document.frmedita.lppfbusqueda.value;
params[103]=window.document.frmedita.b3002nombre.value;
xajax_f'.$iModuloBusca.'_HtmlBusqueda(params);';
	return $sRes;
	}
function f3002_TablaDetalleBusquedas($aParametros, $objDB){
	$res='';
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3002='lg/lg_3002_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3002)){$mensajes_3002='lg/lg_3002_es.php';}
	require $mensajes_todas;
	require $mensajes_3002;
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
		return array($sLeyenda.'<input id="paginaf3002" name="paginaf3002" type="hidden" value="'.$pagina.'"/><input id="lppf3002" name="lppf3002" type="hidden" value="'.$lineastabla.'"/>', $sDebug);
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
	$sTitulos='Consec, Id, Titulo, Clasesol, Detalle, Unidadresp, Equiporesp, Liderrespon';
	$sSQL='SELECT TB.saiu02consec, TB.saiu02id, TB.saiu02titulo, T4.saiu01titulo, TB.saiu02detalle, T6.unae26nombre, TB.saiu02idequiporesp, T8.unad11razonsocial AS C8_nombre, TB.saiu02clasesol, TB.saiu02idunidadresp, TB.saiu02idliderrespon, T8.unad11tipodoc AS C8_td, T8.unad11doc AS C8_doc 
FROM saiu02tiposol AS TB, saiu01claseser AS T4, unae26unidadesfun AS T6, unad11terceros AS T8 
WHERE '.$sSQLadd1.' TB.saiu02clasesol=T4.saiu01id AND TB.saiu02idunidadresp=T6.unae26id AND TB.saiu02idliderrespon=T8.unad11id '.$sSQLadd.'
ORDER BY TB.saiu02consec';
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
			//return array(utf8_encode($sErrConsulta.'<input id="paginaf3002" name="paginaf3002" type="hidden" value="'.$pagina.'"/><input id="lppf3002" name="lppf3002" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
<td><b>'.$ETI['saiu02consec'].'</b></td>
<td><b>'.$ETI['saiu02titulo'].'</b></td>
<td><b>'.$ETI['saiu02clasesol'].'</b></td>
<td><b>'.$ETI['saiu02detalle'].'</b></td>
<td><b>'.$ETI['saiu02idunidadresp'].'</b></td>
<td><b>'.$ETI['saiu02idequiporesp'].'</b></td>
<td colspan="2"><b>'.$ETI['saiu02idliderrespon'].'</b></td>
<td align="right">
'.html_paginador('paginabusqueda', $registros, $lineastabla, $pagina, 'paginarbusqueda()').'
'.html_lpp('lppfbusqueda', $lineastabla, 'paginarbusqueda()').'
</td>
</tr>';
	$tlinea=1;
	while($filadet=$objDB->sf($tabladetalle)){
		$sPrefijo='<a href="javascript:Devuelve(\''.$filadet['saiu02id'].'\');">';
		$sSufijo='</a>';
		$tlinea++;
		$res=$res.'<tr onmouseover="cambia_color_over(this);" onmouseout="cambia_color_out(this);">
<td>'.$sPrefijo.$filadet['saiu02consec'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['saiu02titulo']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['saiu01titulo']).$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu02detalle'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['unae26nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu02idequiporesp'].$sSufijo.'</td>
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