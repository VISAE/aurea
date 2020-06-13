<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2019 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.23.5 Monday, September 9, 2019
--- Modelo Versión 2.23.6 Wednesday, September 18, 2019
--- Modelo Versión 2.24.0 Sunday, November 24, 2019
--- Modelo Versión 2.24.0 Tuesday, December 17, 2019
--- 2323 cara23acompanamento
*/
/** Archivo lib2323.php.
* Libreria 2323 cara23acompanamento.
* @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
* @date Monday, September 9, 2019
*/
function f2323_HTMLComboV2_cara23idencuesta($objDB, $objCombos, $valor, $vrcara23idtercero){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$sSQL='';
	if ($vrcara23idtercero!=0){
		$sSQL='SELECT TB.cara01id AS id, CONCAT(TB.cara01fechaencuesta, " - ", T9.core09nombre) AS nombre 
FROM cara01encuesta AS TB, core09programa AS T9 
WHERE TB.cara01idtercero='.$vrcara23idtercero.' AND TB.cara01completa="S" AND TB.cara01idprograma=T9.core09id 
ORDER BY TB.cara01id DESC';
		}
	$objCombos->nuevo('cara23idencuesta', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	$objCombos->sAccion='RevisaLlave();';
	$objCombos->iAncho=300;
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f2323_HTMLComboV2_cara23consec($objDB, $objCombos, $valor){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('cara23consec', $valor, true, '-');
	$objCombos->sAccion='RevisaLlave();';
	$sSQL='';
	for ($k=1;$k<11;$k++){
		$objCombos->addItem($k, $k);
		}
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f2323_HTMLComboV2_cara23idtipo($objDB, $objCombos, $valor){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('cara23idtipo', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	$sSQL='SELECT  AS id,  AS nombre FROM ';
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f2323_Combocara23idencuesta($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$objCombos=new clsHtmlCombos('n');
	$html_cara23idencuesta=f2323_HTMLComboV2_cara23idencuesta($objDB, $objCombos, $aParametros[1], $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_cara23idencuesta', 'innerHTML', $html_cara23idencuesta);
	if ((int)$aParametros[1]!=0){
		$objResponse->call("expandepanel(2323,'block',0)");
		$objResponse->call("window.scrollTo(0, 200)");
		}
	return $objResponse;
	}
function f2323_ExisteDato($datos){
	if(!is_array($datos)){$datos=json_decode(str_replace('\"','"',$datos),true);}
	$_SESSION['u_ultimominuto']=iminutoavance();
	$bHayLlave=true;
	$cara23idtercero=numeros_validar($datos[1]);
	if ($cara23idtercero==''){$bHayLlave=false;}
	$cara23idencuesta=numeros_validar($datos[2]);
	if ($cara23idencuesta==''){$bHayLlave=false;}
	$cara23consec=numeros_validar($datos[3]);
	if ($cara23consec==''){$bHayLlave=false;}
	if ($bHayLlave){
		require './app.php';
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		$sSQL='SELECT cara23consec FROM cara23acompanamento WHERE cara23idencuesta='.$cara23idencuesta.' AND cara23consec='.$cara23consec.'';
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
function f2323_Busquedas($aParametros){
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2323='lg/lg_2323_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2323)){$mensajes_2323='lg/lg_2323_es.php';}
	require $mensajes_todas;
	require $mensajes_2323;
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
		case 'cara23idtercero':
		require $APP->rutacomun.'lib111.php';
		$sTabla=f111_TablaDetalleBusquedas($aParametrosB, $objDB);
		$sTitulo='Busqueda de terceros';
		$sParams=f111_ParametrosBusqueda();
		$sJavaBusqueda=f111_JavaScriptBusqueda(2323);
		break;
		case 'cara23zonal_idlider':
		require $APP->rutacomun.'lib111.php';
		$sTabla=f111_TablaDetalleBusquedas($aParametrosB, $objDB);
		$sTitulo='Busqueda de terceros';
		$sParams=f111_ParametrosBusqueda();
		$sJavaBusqueda=f111_JavaScriptBusqueda(2323);
		break;
		}
	$sTitulo='<h2>'.$ETI['titulo_2323'].' - '.$sTitulo.'</h2>';
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97titulo', 'innerHTML', $sTitulo);
	$objResponse->assign('div_97params', 'innerHTML', $sParams);
	$objResponse->assign('div_97tabla', 'innerHTML', $sTabla);
	$objResponse->setFunction('paginarbusqueda','',$sJavaBusqueda);
	$objResponse->call('expandesector(97)');
	return $objResponse;
	}
function f2323_HtmlBusqueda($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$sError='';
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$sDetalle='';
	switch($aParametros[100]){
		case 'cara23idtercero':
		require $APP->rutacomun.'lib111.php';
		$sDetalle=f111_TablaDetalleBusquedas($aParametros, $objDB);
		break;
		case 'cara23zonal_idlider':
		require $APP->rutacomun.'lib111.php';
		$sDetalle=f111_TablaDetalleBusquedas($aParametros, $objDB);
		break;
		}
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97tabla', 'innerHTML', $sDetalle);
	return $objResponse;
	}
function f2323_TablaDetalleV2($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2301=$APP->rutacomun.'lg/lg_2301_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2301)){$mensajes_2301=$APP->rutacomun.'lg/lg_2301_es.php';}
	$mensajes_2323='lg/lg_2323_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2323)){$mensajes_2323='lg/lg_2323_es.php';}
	require $mensajes_todas;
	require $mensajes_2301;
	require $mensajes_2323;
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
	$sDebug='';
	$idTercero=numeros_validar($aParametros[100]);
	$pagina=$aParametros[101];
	$lineastabla=$aParametros[102];
	$babierta=true;
	$bEsConsejero=false;
	$sLeyenda='';
	if ($sLeyenda!=''){
		$sLeyenda='<div class="salto1px"></div>
<div class="GrupoCamposAyuda">
'.$sLeyenda.'
<div class="salto1px"></div>
</div>';
		return array($sLeyenda.'<input id="paginaf2323" name="paginaf2323" type="hidden" value="'.$pagina.'"/><input id="lppf2323" name="lppf2323" type="hidden" value="'.$lineastabla.'"/>', $sDebug);
		die();
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
				//$sSQLadd1=$sSQLadd1.'T1.unad11razonsocial LIKE "%'.$sCadena.'%" AND ';
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
	if ($aParametros[112]==1){
		$sSQLadd1=$sSQLadd1.'((TB.cara01discsensorial<>"N") OR (TB.cara01discfisica<>"N") OR (TB.cara01disccognitiva<>"N") OR (TB.cara01perayuda<>0)) AND ';
		}
	if ($aParametros[112]==2){
		$sSQLadd1=$sSQLadd1.'((TB.cara01discsensorial<>"N") OR (TB.cara01discfisica<>"N") OR (TB.cara01disccognitiva<>"N") OR (TB.cara01perayuda<>0)) AND TB.cara01fechaconfirmadisc=0 AND ';
		}
	$sTablaConvenio='';
	if ($aParametros[113]!=''){
		$sTablaConvenio=', core51convenioest AS T51';
		$sSQLadd1=$sSQLadd1.'TB.cara01idtercero=T51.core51idtercero AND T51.core51idconvenio='.$aParametros[113].' AND T51.core51activo="S" AND ';
		}
	$sTitulos='Periodo,TipoDoc,Documento,Estudiante,Completa,Fecha encuesta';
	$sSQL='SELECT T1.exte02nombre, T2.unad11tipodoc AS C2_td, T2.unad11doc AS C2_doc, T2.unad11razonsocial AS C2_nombre, TB.cara01completa, TB.cara01fechaencuesta, TB.cara01id, TB.cara01idperaca, TB.cara01idconsejero, TB.cara01idtercero, TB.cara01numacompanamentos, TB.cara01idperiodoacompana, TB.cara01fechacierreacom, T5.cara11nombre, TB.cara01factorriesgoacomp  
FROM cara01encuesta AS TB'.$sTablaConvenio.', exte02per_aca AS T1, unad11terceros AS T2, cara11tipocaract AS T5 
WHERE '.$sSQLadd1.' TB.cara01idperaca=T1.exte02id AND TB.cara01idtercero=T2.unad11id AND TB.cara01tipocaracterizacion=T5.cara11id '.$sSQLadd.'
ORDER BY TB.cara01idperaca DESC, TB.cara01factorriesgoacomp DESC, T2.unad11doc';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_2323" name="consulta_2323" type="hidden" value="'.$sSQLlista.'"/>
<input id="titulos_2323" name="titulos_2323" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objDB->ejecutasql($sSQL);
	if ($tabladetalle==false){
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 2323: '.$sSQL.'<br>';}
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		//$sLeyenda=$sSQL;
		}else{
		$registros=$objDB->nf($tabladetalle);
		if ($registros==0){
			//return array(utf8_encode($sErrConsulta.'<input id="paginaf2323" name="paginaf2323" type="hidden" value="'.$pagina.'"/><input id="lppf2323" name="lppf2323" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
			}
		if ((($registros-1)/$lineastabla)<($pagina-1)){$pagina=(int)(($registros-1)/$lineastabla)+1;}
		if ($registros>$lineastabla){
			$rbase=($pagina-1)*$lineastabla;
			$limite=' LIMIT '.$rbase.', '.$lineastabla;
			$tabladetalle=$objDB->ejecutasql($sSQL.$limite);
			}
		}
	$res='';
	$sHTMLConsejero='';
	$aPeriodos=array();
	if (true){
		$sSQL='SELECT cara13peraca, cara01cargaasignada FROM cara13consejeros WHERE cara13idconsejero='.$idTercero.' AND cara13activo="S" ORDER BY cara13peraca DESC';
		$tabla13=$objDB->ejecutasql($sSQL);
		while ($fila=$objDB->sf($tabla13)){
			$bEsConsejero=true;
			$aPeriodos[$fila['cara13peraca']]=$fila['cara01cargaasignada'];
			}
		}
	$res=$sErrConsulta.$sLeyenda.$res.'<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">
<tr class="fondoazul">
<td colspan="2"><b>'.$ETI['cara01idtercero'].'</b></td>
<td><b>'.$ETI['cara01tipocaracterizacion'].'</b></td>
<td><b>'.$ETI['cara01numacompanamentos'].'</b></td>
<td><b>'.$ETI['msg_riesgofin'].'</b></td>
<td align="right" colspan="4">
'.html_paginador('paginaf2323', $registros, $lineastabla, $pagina, 'paginarf2323()').'
'.html_lpp('lppf2323', $lineastabla, 'paginarf2323()').'
</td>
</tr>';
	$tlinea=1;
	$idPeraca=-1;
	while($filadet=$objDB->sf($tabladetalle)){
		if ($idPeraca!=$filadet['cara01idperaca']){
			$idPeraca=$filadet['cara01idperaca'];
			$res=$res.'<tr class="fondoazul">
<td colspan="9">'.$ETI['cara01idperaca'].' <b>'.cadena_notildes($filadet['exte02nombre']).'</b></td>
</tr>';
			}
		$sPrefijo='';
		$sSufijo='';
		$sClass='';
		$sLink='';
		$sLink2='';
		$sLink3='';
		//$et_cara01completa=$ETI['msg_cerrado'];
		$et_cara01fechacierreacom='';
		//$et_cara01fechaencuesta='';
		if ($filadet['cara01completa']!='S'){
			$sPrefijo='<span class="rojo">';
			$sSufijo='</span>';
			//$et_cara01completa=$ETI['msg_abierto'];
			//$et_cara01fechaencuesta=fecha_desdenumero($filadet['cara01fechaencuesta']);
			}else{
			if ($filadet['cara01fechacierreacom']==0){
				if ($filadet['cara01idperiodoacompana']!=0){
					$et_cara01fechacierreacom='['.$ETI['msg_acargo'].' '.$filadet['cara01idperiodoacompana'].']';
					}
				}else{
				$et_cara01fechacierreacom=fecha_desdenumero($filadet['cara01fechacierreacom']);
				$sPrefijo='<b>';
				$sSufijo='</b>';
				}
			}
		$et_riesgofin=$ariesgo[$filadet['cara01factorriesgoacomp']];
		$sSemaforo='';
		switch($filadet['cara01factorriesgoacomp']){
			case 1:// Sin riesgo - Verde
			$sSemaforo=' bgcolor="#8FC875"';
			break;
			case 2:// Bajo - Amario
			$sSemaforo=' bgcolor="#FED77A"';
			break;
			case 3:// Alto - rojo
			$sSemaforo=' bgcolor="#FD9684"';
			break;
			}
		$sLinkAcomp='<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp"><tr>';
		$sSQL='SELECT cara23consec, cara23estado, cara23idtipo, cara23factorriesgo FROM cara23acompanamento WHERE cara23idencuesta='.$filadet['cara01id'].' ORDER BY cara23idtipo, cara23consec';
		$tabla23=$objDB->ejecutasql($sSQL);
		while($fila23=$objDB->sf($tabla23)){
			$k=$fila23['cara23consec'];
			$sPref='';
			$sSuf='';
			$sClase=' bgcolor="#8FC875"';
			// '.$fila23['cara23estado'].'
			if ($fila23['cara23estado']==0){
				$sClase=' bgcolor="#FD9684"';
				}
			// 
			$sLinkAcomp=$sLinkAcomp.'<td'.$sClase.'><a href="javascript:cargadato('.$filadet['cara01idtercero'].', '.$filadet['cara01id'].', '.$k.')" class="lnkresalte">'.$k.'</a></td>
<td'.$sClase.'>'.$ariesgo[$fila23['cara23factorriesgo']].'</td>';
			}
		/*
		for ($k=1;$k<=$filadet['cara01numacompanamentos'];$k++){
			$sLinkAcomp=$sLinkAcomp.'<a href="javascript:cargadato('.$filadet['cara01idtercero'].', '.$filadet['cara01id'].', '.$k.')" class="lnkresalte">'.$k.'</a>';
			}
		//$filadet['cara01numacompanamentos']
		*/
		$et_cara01numacompanamentos=$sLinkAcomp.'</tr></table>';
		if(($tlinea%2)==0){$sClass=' class="resaltetabla"';}
		$tlinea++;
		if ($filadet['cara01idconsejero']==$idTercero){
			$sLink2=$ETI['lnk_acargo'];
			}
		if ($babierta){
			//cara01idtercero
			$sLink='<a href="javascript:cargaridf2301('.$filadet['cara01idtercero'].', '.$filadet['cara01id'].')" class="lnkresalte">'.$ETI['msg_nuevo'].'</a>';
			if ($bEsConsejero){
				if (isset($aPeriodos[$idPeraca])!=0){
					if ($filadet['cara01idconsejero']==0){
						$sLink2='<a href="javascript:soyconsejeroidf2301('.$filadet['cara01id'].')" class="lnkresalte">'.$ETI['lnk_soyconsejero'].'</a>';
						}
					}
				}
			}
		$res=$res.'<tr'.$sClass.'>
<td>'.$sPrefijo.$filadet['C2_td'].' '.$filadet['C2_doc'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['C2_nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['cara11nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.$et_cara01numacompanamentos.$sSufijo.'</td>
<td'.$sSemaforo.' align="center">'.$sPrefijo.$et_riesgofin.$sSufijo.'</td>
<td>'.$sPrefijo.$et_cara01fechacierreacom.$sSufijo.'</td>
<td><div id="div_lnkconsejero'.$filadet['cara01id'].'">'.$sLink2.'</div></td>
<td>'.$sLink.'</td>
</tr>';
		}
	$res=$res.'</table>'.$sHTMLConsejero;
	$objDB->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
	}
function f2323_HtmlTabla($aParametros){
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
	list($sDetalle, $sDebugTabla)=f2323_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f2323detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f2323_db_CargarPadre($DATA, $objDB, $bDebug=false){
	$sError='';
	$iTipoError=0;
	$sDebug='';
	require './app.php';
	$DATA['cara23idtercero_td']=$APP->tipo_doc;
	$DATA['cara23idtercero_doc']='';
	$DATA['cara23zonal_idlider_td']=$APP->tipo_doc;
	$DATA['cara23zonal_idlider_doc']='';
	if ($DATA['paso']==1){
		$sSQLcondi='cara23idencuesta='.$DATA['cara23idencuesta'].' AND cara23consec='.$DATA['cara23consec'].'';
		}else{
		$sSQLcondi='cara23id='.$DATA['cara23id'].'';
		}
	$sSQL='SELECT * FROM cara23acompanamento WHERE '.$sSQLcondi;
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$DATA['cara23idtercero']=$fila['cara23idtercero'];
		$DATA['cara23idencuesta']=$fila['cara23idencuesta'];
		$DATA['cara23consec']=$fila['cara23consec'];
		$DATA['cara23id']=$fila['cara23id'];
		$DATA['cara23idtipo']=$fila['cara23idtipo'];
		$DATA['cara23estado']=$fila['cara23estado'];
		$DATA['cara23asisteinduccion']=$fila['cara23asisteinduccion'];
		$DATA['cara23asisteinmersioncv']=$fila['cara23asisteinmersioncv'];
		$DATA['cara23catedra_skype']=$fila['cara23catedra_skype'];
		$DATA['cara23catedra_bler1']=$fila['cara23catedra_bler1'];
		$DATA['cara23catedra_bler2']=$fila['cara23catedra_bler2'];
		$DATA['cara23catedra_webconf']=$fila['cara23catedra_webconf'];
		$DATA['cara23catedra_avance']=$fila['cara23catedra_avance'];
		$DATA['cara23catedra_criterio']=$fila['cara23catedra_criterio'];
		$DATA['cara23catedra_acciones']=$fila['cara23catedra_acciones'];
		$DATA['cara23catedra_resultados']=$fila['cara23catedra_resultados'];
		$DATA['cara23catedra_segprev']=$fila['cara23catedra_segprev'];
		$DATA['cara23cursos_total']=$fila['cara23cursos_total'];
		$DATA['cara23cursos_siningre']=$fila['cara23cursos_siningre'];
		$DATA['cara23cursos_porcing']=$fila['cara23cursos_porcing'];
		$DATA['cara23cursos_menor200']=$fila['cara23cursos_menor200'];
		$DATA['cara23cursos_porcperdida']=$fila['cara23cursos_porcperdida'];
		$DATA['cara23cursos_criterio']=$fila['cara23cursos_criterio'];
		$DATA['cara23cursos_otros']=$fila['cara23cursos_otros'];
		$DATA['cara23cursos_accionlider']=$fila['cara23cursos_accionlider'];
		$DATA['cara23aler_sociodem']=$fila['cara23aler_sociodem'];
		$DATA['cara23aler_psico']=$fila['cara23aler_psico'];
		$DATA['cara23aler_academ']=$fila['cara23aler_academ'];
		$DATA['cara23aler_econom']=$fila['cara23aler_econom'];
		$DATA['cara23aler_externo']=$fila['cara23aler_externo'];
		$DATA['cara23aler_interno']=$fila['cara23aler_interno'];
		$DATA['cara23aler_nivel']=$fila['cara23aler_nivel'];
		$DATA['cara23aler_criterio']=$fila['cara23aler_criterio'];
		$DATA['cara23comp_digital']=$fila['cara23comp_digital'];
		$DATA['cara23comp_cuanti']=$fila['cara23comp_cuanti'];
		$DATA['cara23comp_lectora']=$fila['cara23comp_lectora'];
		$DATA['cara23comp_ingles']=$fila['cara23comp_ingles'];
		$DATA['cara23comp_criterio']=$fila['cara23comp_criterio'];
		$DATA['cara23nivela_digital']=$fila['cara23nivela_digital'];
		$DATA['cara23nivela_cuanti']=$fila['cara23nivela_cuanti'];
		$DATA['cara23nivela_lecto']=$fila['cara23nivela_lecto'];
		$DATA['cara23nivela_ingles']=$fila['cara23nivela_ingles'];
		$DATA['cara23nivela_exito']=$fila['cara23nivela_exito'];
		$DATA['cara23contacto_efectivo']=$fila['cara23contacto_efectivo'];
		$DATA['cara23contacto_forma']=$fila['cara23contacto_forma'];
		$DATA['cara23contacto_observa']=$fila['cara23contacto_observa'];
		$DATA['cara23aplaza']=$fila['cara23aplaza'];
		$DATA['cara23seretira']=$fila['cara23seretira'];
		$DATA['cara23factorriesgo']=$fila['cara23factorriesgo'];
		$DATA['cara23zonal_retro']=$fila['cara23zonal_retro'];
		$DATA['cara23zonal_fecha']=$fila['cara23zonal_fecha'];
		$DATA['cara23zonal_idlider']=$fila['cara23zonal_idlider'];
		$DATA['cara23fecha']=$fila['cara23fecha'];
		$DATA['cara23cursos_ac1']=$fila['cara23cursos_ac1'];
		$DATA['cara23cursos_ac2']=$fila['cara23cursos_ac2'];
		$DATA['cara23cursos_ac3']=$fila['cara23cursos_ac3'];
		$DATA['cara23cursos_ac4']=$fila['cara23cursos_ac4'];
		$DATA['cara23cursos_ac5']=$fila['cara23cursos_ac5'];
		$DATA['cara23cursos_ac6']=$fila['cara23cursos_ac6'];
		$DATA['cara23cursos_ac7']=$fila['cara23cursos_ac7'];
		$DATA['cara23cursos_ac8']=$fila['cara23cursos_ac8'];
		$DATA['cara23cursos_ac9']=$fila['cara23cursos_ac9'];
		$DATA['cara23catedra_aprueba']=$fila['cara23catedra_aprueba'];
		$DATA['cara23permanece']=$fila['cara23permanece'];
		$bcargo=true;
		$DATA['paso']=2;
		$DATA['boculta2323']=0;
		$bLimpiaHijos=true;
		}else{
		$DATA['paso']=0;
		}
	return array($DATA, $sError, $iTipoError, $sDebug);
	}
function f2323_Cerrar($cara23id, $objDB, $bDebug=false){
	$sInfo='';
	$sDebug='';
	return array($sInfo, $sDebug);
	}
function f2323_db_GuardarV2($DATA, $objDB, $bDebug=false){
	$iCodModulo=2323;
	$bAudita[2]=true;
	$bAudita[3]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2301=$APP->rutacomun.'lg/lg_2301_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2301)){$mensajes_2301=$APP->rutacomun.'lg/lg_2301_es.php';}
	$mensajes_2323='lg/lg_2323_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2323)){$mensajes_2323='lg/lg_2323_es.php';}
	require $mensajes_todas;
	require $mensajes_2301;
	require $mensajes_2323;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	$bCerrando=false;
	$sErrorCerrando='';
	// -- Se inicia validando todas las posibles entradas de usuario.
	/*
	if (isset($DATA['cara23idtercero'])==0){$DATA['cara23idtercero']='';}
	if (isset($DATA['cara23idencuesta'])==0){$DATA['cara23idencuesta']='';}
	if (isset($DATA['cara23consec'])==0){$DATA['cara23consec']='';}
	if (isset($DATA['cara23id'])==0){$DATA['cara23id']='';}
	if (isset($DATA['cara23idtipo'])==0){$DATA['cara23idtipo']='';}
	if (isset($DATA['cara23asisteinduccion'])==0){$DATA['cara23asisteinduccion']='';}
	if (isset($DATA['cara23asisteinmersioncv'])==0){$DATA['cara23asisteinmersioncv']='';}
	if (isset($DATA['cara23catedra_skype'])==0){$DATA['cara23catedra_skype']='';}
	if (isset($DATA['cara23catedra_bler1'])==0){$DATA['cara23catedra_bler1']='';}
	if (isset($DATA['cara23catedra_bler2'])==0){$DATA['cara23catedra_bler2']='';}
	if (isset($DATA['cara23catedra_webconf'])==0){$DATA['cara23catedra_webconf']='';}
	if (isset($DATA['cara23catedra_avance'])==0){$DATA['cara23catedra_avance']='';}
	if (isset($DATA['cara23catedra_acciones'])==0){$DATA['cara23catedra_acciones']='';}
	if (isset($DATA['cara23catedra_resultados'])==0){$DATA['cara23catedra_resultados']='';}
	if (isset($DATA['cara23catedra_segprev'])==0){$DATA['cara23catedra_segprev']='';}
	if (isset($DATA['cara23cursos_total'])==0){$DATA['cara23cursos_total']='';}
	if (isset($DATA['cara23cursos_siningre'])==0){$DATA['cara23cursos_siningre']='';}
	if (isset($DATA['cara23cursos_menor200'])==0){$DATA['cara23cursos_menor200']='';}
	if (isset($DATA['cara23cursos_otros'])==0){$DATA['cara23cursos_otros']='';}
	if (isset($DATA['cara23cursos_accionlider'])==0){$DATA['cara23cursos_accionlider']='';}
	if (isset($DATA['cara23aler_sociodem'])==0){$DATA['cara23aler_sociodem']='';}
	if (isset($DATA['cara23aler_psico'])==0){$DATA['cara23aler_psico']='';}
	if (isset($DATA['cara23aler_academ'])==0){$DATA['cara23aler_academ']='';}
	if (isset($DATA['cara23aler_econom'])==0){$DATA['cara23aler_econom']='';}
	if (isset($DATA['cara23aler_externo'])==0){$DATA['cara23aler_externo']='';}
	if (isset($DATA['cara23aler_interno'])==0){$DATA['cara23aler_interno']='';}
	if (isset($DATA['cara23aler_nivel'])==0){$DATA['cara23aler_nivel']='';}
	if (isset($DATA['cara23aler_criterio'])==0){$DATA['cara23aler_criterio']='';}
	if (isset($DATA['cara23comp_digital'])==0){$DATA['cara23comp_digital']='';}
	if (isset($DATA['cara23comp_cuanti'])==0){$DATA['cara23comp_cuanti']='';}
	if (isset($DATA['cara23comp_lectora'])==0){$DATA['cara23comp_lectora']='';}
	if (isset($DATA['cara23comp_ingles'])==0){$DATA['cara23comp_ingles']='';}
	if (isset($DATA['cara23nivela_digital'])==0){$DATA['cara23nivela_digital']='';}
	if (isset($DATA['cara23nivela_cuanti'])==0){$DATA['cara23nivela_cuanti']='';}
	if (isset($DATA['cara23nivela_lecto'])==0){$DATA['cara23nivela_lecto']='';}
	if (isset($DATA['cara23nivela_ingles'])==0){$DATA['cara23nivela_ingles']='';}
	if (isset($DATA['cara23nivela_exito'])==0){$DATA['cara23nivela_exito']='';}
	if (isset($DATA['cara23contacto_efectivo'])==0){$DATA['cara23contacto_efectivo']='';}
	if (isset($DATA['cara23contacto_forma'])==0){$DATA['cara23contacto_forma']='';}
	if (isset($DATA['cara23contacto_observa'])==0){$DATA['cara23contacto_observa']='';}
	if (isset($DATA['cara23aplaza'])==0){$DATA['cara23aplaza']='';}
	if (isset($DATA['cara23seretira'])==0){$DATA['cara23seretira']='';}
	if (isset($DATA['cara23zonal_fecha'])==0){$DATA['cara23zonal_fecha']='';}
	if (isset($DATA['cara23fecha'])==0){$DATA['cara23fecha']='';}
	if (isset($DATA['cara23cursos_ac1'])==0){$DATA['cara23cursos_ac1']='';}
	if (isset($DATA['cara23cursos_ac2'])==0){$DATA['cara23cursos_ac2']='';}
	if (isset($DATA['cara23cursos_ac3'])==0){$DATA['cara23cursos_ac3']='';}
	if (isset($DATA['cara23cursos_ac4'])==0){$DATA['cara23cursos_ac4']='';}
	if (isset($DATA['cara23cursos_ac5'])==0){$DATA['cara23cursos_ac5']='';}
	if (isset($DATA['cara23cursos_ac6'])==0){$DATA['cara23cursos_ac6']='';}
	if (isset($DATA['cara23cursos_ac7'])==0){$DATA['cara23cursos_ac7']='';}
	if (isset($DATA['cara23cursos_ac8'])==0){$DATA['cara23cursos_ac8']='';}
	if (isset($DATA['cara23cursos_ac9'])==0){$DATA['cara23cursos_ac9']='';}
	if (isset($DATA['cara23catedra_aprueba'])==0){$DATA['cara23catedra_aprueba']='';}
	if (isset($DATA['cara23permanece'])==0){$DATA['cara23permanece']='';}
	*/
	$DATA['cara23idencuesta']=numeros_validar($DATA['cara23idencuesta']);
	$DATA['cara23consec']=numeros_validar($DATA['cara23consec']);
	$DATA['cara23idtipo']=numeros_validar($DATA['cara23idtipo']);
	$DATA['cara23asisteinduccion']=numeros_validar($DATA['cara23asisteinduccion']);
	$DATA['cara23asisteinmersioncv']=numeros_validar($DATA['cara23asisteinmersioncv']);
	$DATA['cara23catedra_skype']=numeros_validar($DATA['cara23catedra_skype']);
	$DATA['cara23catedra_bler1']=numeros_validar($DATA['cara23catedra_bler1']);
	$DATA['cara23catedra_bler2']=numeros_validar($DATA['cara23catedra_bler2']);
	$DATA['cara23catedra_webconf']=numeros_validar($DATA['cara23catedra_webconf']);
	$DATA['cara23catedra_avance']=numeros_validar($DATA['cara23catedra_avance']);
	$DATA['cara23catedra_acciones']=numeros_validar($DATA['cara23catedra_acciones']);
	$DATA['cara23catedra_resultados']=numeros_validar($DATA['cara23catedra_resultados']);
	$DATA['cara23catedra_segprev']=htmlspecialchars(trim($DATA['cara23catedra_segprev']));
	$DATA['cara23cursos_total']=numeros_validar($DATA['cara23cursos_total']);
	$DATA['cara23cursos_siningre']=numeros_validar($DATA['cara23cursos_siningre']);
	$DATA['cara23cursos_menor200']=numeros_validar($DATA['cara23cursos_menor200']);
	$DATA['cara23cursos_otros']=htmlspecialchars(trim($DATA['cara23cursos_otros']));
	$DATA['cara23cursos_accionlider']=htmlspecialchars(trim($DATA['cara23cursos_accionlider']));
	$DATA['cara23aler_sociodem']=htmlspecialchars(trim($DATA['cara23aler_sociodem']));
	$DATA['cara23aler_psico']=htmlspecialchars(trim($DATA['cara23aler_psico']));
	$DATA['cara23aler_academ']=htmlspecialchars(trim($DATA['cara23aler_academ']));
	$DATA['cara23aler_econom']=htmlspecialchars(trim($DATA['cara23aler_econom']));
	$DATA['cara23aler_externo']=htmlspecialchars(trim($DATA['cara23aler_externo']));
	$DATA['cara23aler_interno']=htmlspecialchars(trim($DATA['cara23aler_interno']));
	$DATA['cara23aler_nivel']=htmlspecialchars(trim($DATA['cara23aler_nivel']));
	$DATA['cara23aler_criterio']=numeros_validar($DATA['cara23aler_criterio']);
	$DATA['cara23comp_digital']=numeros_validar($DATA['cara23comp_digital']);
	$DATA['cara23comp_cuanti']=numeros_validar($DATA['cara23comp_cuanti']);
	$DATA['cara23comp_lectora']=numeros_validar($DATA['cara23comp_lectora']);
	$DATA['cara23comp_ingles']=numeros_validar($DATA['cara23comp_ingles']);
	$DATA['cara23nivela_digital']=numeros_validar($DATA['cara23nivela_digital']);
	$DATA['cara23nivela_cuanti']=numeros_validar($DATA['cara23nivela_cuanti']);
	$DATA['cara23nivela_lecto']=numeros_validar($DATA['cara23nivela_lecto']);
	$DATA['cara23nivela_ingles']=numeros_validar($DATA['cara23nivela_ingles']);
	$DATA['cara23nivela_exito']=numeros_validar($DATA['cara23nivela_exito']);
	$DATA['cara23contacto_efectivo']=numeros_validar($DATA['cara23contacto_efectivo']);
	$DATA['cara23contacto_forma']=numeros_validar($DATA['cara23contacto_forma']);
	$DATA['cara23contacto_observa']=htmlspecialchars(trim($DATA['cara23contacto_observa']));
	$DATA['cara23aplaza']=numeros_validar($DATA['cara23aplaza']);
	$DATA['cara23seretira']=numeros_validar($DATA['cara23seretira']);
	$DATA['cara23zonal_retro']=htmlspecialchars(trim($DATA['cara23zonal_retro']));
	$DATA['cara23cursos_ac1']=numeros_validar($DATA['cara23cursos_ac1']);
	$DATA['cara23cursos_ac2']=numeros_validar($DATA['cara23cursos_ac2']);
	$DATA['cara23cursos_ac3']=numeros_validar($DATA['cara23cursos_ac3']);
	$DATA['cara23cursos_ac4']=numeros_validar($DATA['cara23cursos_ac4']);
	$DATA['cara23cursos_ac5']=numeros_validar($DATA['cara23cursos_ac5']);
	$DATA['cara23cursos_ac6']=numeros_validar($DATA['cara23cursos_ac6']);
	$DATA['cara23cursos_ac7']=numeros_validar($DATA['cara23cursos_ac7']);
	$DATA['cara23cursos_ac8']=numeros_validar($DATA['cara23cursos_ac8']);
	$DATA['cara23cursos_ac9']=numeros_validar($DATA['cara23cursos_ac9']);
	$DATA['cara23catedra_aprueba']=numeros_validar($DATA['cara23catedra_aprueba']);
	$DATA['cara23permanece']=numeros_validar($DATA['cara23permanece']);
	// -- Se inicializan las variables que puedan pasar vacias {Especialmente números}.
	//if ($DATA['cara23idtipo']==''){$DATA['cara23idtipo']=0;}
	if ($DATA['cara23estado']==''){$DATA['cara23estado']=0;}
	//if ($DATA['cara23asisteinduccion']==''){$DATA['cara23asisteinduccion']=0;}
	//if ($DATA['cara23asisteinmersioncv']==''){$DATA['cara23asisteinmersioncv']=0;}
	//if ($DATA['cara23catedra_skype']==''){$DATA['cara23catedra_skype']=0;}
	//if ($DATA['cara23catedra_bler1']==''){$DATA['cara23catedra_bler1']=0;}
	//if ($DATA['cara23catedra_bler2']==''){$DATA['cara23catedra_bler2']=0;}
	//if ($DATA['cara23catedra_webconf']==''){$DATA['cara23catedra_webconf']=0;}
	//if ($DATA['cara23catedra_avance']==''){$DATA['cara23catedra_avance']=0;}
	if ($DATA['cara23catedra_criterio']==''){$DATA['cara23catedra_criterio']=0;}
	//if ($DATA['cara23catedra_acciones']==''){$DATA['cara23catedra_acciones']=0;}
	//if ($DATA['cara23catedra_resultados']==''){$DATA['cara23catedra_resultados']=0;}
	//if ($DATA['cara23cursos_total']==''){$DATA['cara23cursos_total']=0;}
	//if ($DATA['cara23cursos_siningre']==''){$DATA['cara23cursos_siningre']=0;}
	if ($DATA['cara23cursos_porcing']==''){$DATA['cara23cursos_porcing']=0;}
	//if ($DATA['cara23cursos_menor200']==''){$DATA['cara23cursos_menor200']=0;}
	if ($DATA['cara23cursos_porcperdida']==''){$DATA['cara23cursos_porcperdida']=0;}
	if ($DATA['cara23cursos_criterio']==''){$DATA['cara23cursos_criterio']=0;}
	//if ($DATA['cara23aler_criterio']==''){$DATA['cara23aler_criterio']=0;}
	//if ($DATA['cara23comp_digital']==''){$DATA['cara23comp_digital']=0;}
	//if ($DATA['cara23comp_cuanti']==''){$DATA['cara23comp_cuanti']=0;}
	//if ($DATA['cara23comp_lectora']==''){$DATA['cara23comp_lectora']=0;}
	//if ($DATA['cara23comp_ingles']==''){$DATA['cara23comp_ingles']=0;}
	if ($DATA['cara23comp_criterio']==''){$DATA['cara23comp_criterio']=0;}
	//if ($DATA['cara23nivela_digital']==''){$DATA['cara23nivela_digital']=0;}
	//if ($DATA['cara23nivela_cuanti']==''){$DATA['cara23nivela_cuanti']=0;}
	//if ($DATA['cara23nivela_lecto']==''){$DATA['cara23nivela_lecto']=0;}
	//if ($DATA['cara23nivela_ingles']==''){$DATA['cara23nivela_ingles']=0;}
	//if ($DATA['cara23nivela_exito']==''){$DATA['cara23nivela_exito']=0;}
	//if ($DATA['cara23contacto_efectivo']==''){$DATA['cara23contacto_efectivo']=0;}
	//if ($DATA['cara23contacto_forma']==''){$DATA['cara23contacto_forma']=0;}
	//if ($DATA['cara23aplaza']==''){$DATA['cara23aplaza']=0;}
	//if ($DATA['cara23seretira']==''){$DATA['cara23seretira']=0;}
	if ($DATA['cara23factorriesgo']==''){$DATA['cara23factorriesgo']=0;}
	//if ($DATA['cara23cursos_ac1']==''){$DATA['cara23cursos_ac1']=0;}
	//if ($DATA['cara23cursos_ac2']==''){$DATA['cara23cursos_ac2']=0;}
	//if ($DATA['cara23cursos_ac3']==''){$DATA['cara23cursos_ac3']=0;}
	//if ($DATA['cara23cursos_ac4']==''){$DATA['cara23cursos_ac4']=0;}
	//if ($DATA['cara23cursos_ac5']==''){$DATA['cara23cursos_ac5']=0;}
	//if ($DATA['cara23cursos_ac6']==''){$DATA['cara23cursos_ac6']=0;}
	//if ($DATA['cara23cursos_ac7']==''){$DATA['cara23cursos_ac7']=0;}
	//if ($DATA['cara23cursos_ac8']==''){$DATA['cara23cursos_ac8']=0;}
	//if ($DATA['cara23cursos_ac9']==''){$DATA['cara23cursos_ac9']=0;}
	//if ($DATA['cara23catedra_aprueba']==''){$DATA['cara23catedra_aprueba']=0;}
	//if ($DATA['cara23permanece']==''){$DATA['cara23permanece']=0;}
	// -- Calcular los factores de riesgo.
	$DATA['cara23catedra_criterio']=0;
	//cara23catedra_avance
	if ((int)$DATA['cara23catedra_avance']>0){
		$sSQL='SELECT cara24vrriesgo FROM cara24avancecatedra WHERE cara24id='.$DATA['cara23catedra_avance'].'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$fila=$objDB->sf($tabla);
			if ($fila['cara24vrriesgo']>0){$DATA['cara23catedra_criterio']=1;}
			if ($fila['cara24vrriesgo']>9){$DATA['cara23catedra_criterio']=2;}
			if ($fila['cara24vrriesgo']>14){$DATA['cara23catedra_criterio']=3;}
			}
		}
	//FActor total
	$iTipoSeg=$DATA['cara23idtipo'];
	$DATA['cara23factorriesgo']=0;
	switch($iTipoSeg){
		case 1:
		$iPuntaje=0;
		if (($DATA['cara23asisteinduccion']==0)&&($DATA['cara23asisteinmersioncv']==0)){
			$iPuntaje=5;
			}
		if ($DATA['cara23catedra_criterio']==1){$iPuntaje=$iPuntaje+1;}
		if ($DATA['cara23catedra_criterio']==2){$iPuntaje=$iPuntaje+3;}
		if ($DATA['cara23catedra_criterio']==3){$iPuntaje=$iPuntaje+5;}
		if ($DATA['cara23aler_criterio']==1){$iPuntaje=$iPuntaje+1;}
		if ($DATA['cara23aler_criterio']==2){$iPuntaje=$iPuntaje+3;}
		if ($DATA['cara23aler_criterio']==3){$iPuntaje=$iPuntaje+5;}
		if ($DATA['cara23comp_criterio']==1){$iPuntaje=$iPuntaje+1;}
		if ($DATA['cara23comp_criterio']==2){$iPuntaje=$iPuntaje+3;}
		if ($DATA['cara23comp_criterio']==3){$iPuntaje=$iPuntaje+5;}
		if ($iPuntaje>0){$DATA['cara23factorriesgo']=1;}
		if ($iPuntaje>4){$DATA['cara23factorriesgo']=2;}
		if ($iPuntaje>9){$DATA['cara23factorriesgo']=3;}
		break;
		case 2:
		case 3:
		$DATA['cara23factorriesgo']=$DATA['cara23cursos_criterio'];
		break;
		}
	// -- Seccion para validar los posibles causales de error.
	
$bInducciones=false;
$bCatedra1=false;
$bCatedra2=false;
$bCatedra3=false;
$bCursos1=false;
$bAlertasIniciales=true;
$bSegZonal=false;
$bGuardarDesercion=false;
if ($DATA['cara23estado']==7){
	$bSegZonal=true;
	}
switch($iTipoSeg){
	case 1:
	$bInducciones=true;
	$bSegZonal=false;
	break;
	case 2:
	$bCatedra3=true;
	$bCursos1=true;
	$bAlertasIniciales=false;
	break;
	case 3:
	$bCatedra3=true;
	$bCursos1=true;
	$bAlertasIniciales=false;
	break;
	}
	$sSepara=', ';
	if (true){
		//if ($DATA['cara23zonal_idlider']==0){$sError=$ERR['cara23zonal_idlider'].$sSepara.$sError;}
		//if ($DATA['cara23zonal_fecha']==0){
			//$DATA['cara23zonal_fecha']=fecha_DiaMod();
			//$sError=$ERR['cara23zonal_fecha'].$sSepara.$sError;
			//}
		if ($DATA['cara23seretira']==''){$sError=$ERR['cara23seretira'].$sSepara.$sError;}
		if ($DATA['cara23aplaza']==''){$sError=$ERR['cara23aplaza'].$sSepara.$sError;}
		//if ($DATA['cara23contacto_observa']==''){$sError=$ERR['cara23contacto_observa'].$sSepara.$sError;}
		//if ($DATA['cara23contacto_forma']==''){$sError=$ERR['cara23contacto_forma'].$sSepara.$sError;}
		if ($DATA['cara23contacto_efectivo']==''){$sError=$ERR['cara23contacto_efectivo'].$sSepara.$sError;}
		//if ($DATA['cara23nivela_ingles']==''){$sError=$ERR['cara23nivela_ingles'].$sSepara.$sError;}
		//if ($DATA['cara23nivela_lecto']==''){$sError=$ERR['cara23nivela_lecto'].$sSepara.$sError;}
		//if ($DATA['cara23nivela_cuanti']==''){$sError=$ERR['cara23nivela_cuanti'].$sSepara.$sError;}
		//if ($DATA['cara23nivela_digital']==''){$sError=$ERR['cara23nivela_digital'].$sSepara.$sError;}
		$bValida=false;
		if ($DATA['cara23estado']==7){
			if ($iTipoSeg==1){$bValida=true;}
			}
		if ($bValida){
			if ($DATA['cara23nivela_exito']==''){$sError=$ERR['cara23nivela_exito'].$sSepara.$sError;}
			if ($DATA['cara23comp_ingles']==''){$sError=$ERR['cara23comp_ingles'].$sSepara.$sError;}
			if ($DATA['cara23comp_lectora']==''){$sError=$ERR['cara23comp_lectora'].$sSepara.$sError;}
			if ($DATA['cara23comp_cuanti']==''){$sError=$ERR['cara23comp_cuanti'].$sSepara.$sError;}
			if ($DATA['cara23comp_digital']==''){$sError=$ERR['cara23comp_digital'].$sSepara.$sError;}
			if ($DATA['cara23aler_criterio']==''){$sError=$ERR['cara23aler_criterio'].$sSepara.$sError;}
			}else{
			if ($DATA['cara23nivela_exito']==''){$DATA['cara23nivela_exito']=0;}
			if ($DATA['cara23comp_ingles']==''){$DATA['cara23comp_ingles']=0;}
			if ($DATA['cara23comp_lectora']==''){$DATA['cara23comp_lectora']=0;}
			if ($DATA['cara23comp_cuanti']==''){$DATA['cara23comp_cuanti']=0;}
			if ($DATA['cara23comp_digital']==''){$DATA['cara23comp_digital']=0;}
			//$DATA['cara23aler_criterio']=0;
			}
		//if ($DATA['cara23aler_nivel']==''){$sError=$ERR['cara23aler_nivel'].$sSepara.$sError;}
		//if ($DATA['cara23aler_interno']==''){$sError=$ERR['cara23aler_interno'].$sSepara.$sError;}
		//if ($DATA['cara23aler_externo']==''){$sError=$ERR['cara23aler_externo'].$sSepara.$sError;}
		//if ($DATA['cara23aler_econom']==''){$sError=$ERR['cara23aler_econom'].$sSepara.$sError;}
		//if ($DATA['cara23aler_academ']==''){$sError=$ERR['cara23aler_academ'].$sSepara.$sError;}
		//if ($DATA['cara23aler_psico']==''){$sError=$ERR['cara23aler_psico'].$sSepara.$sError;}
		//if ($DATA['cara23aler_sociodem']==''){$sError=$ERR['cara23aler_sociodem'].$sSepara.$sError;}
		$bValida=false;
		if ($DATA['cara23estado']==7){
			if ($iTipoSeg==2){$bValida=true;}
			}
		if ($bValida){
			//if ($DATA['cara23cursos_ac9']==''){$sError=$ERR['cara23cursos_ac9'].$sSepara.$sError;}
			//if ($DATA['cara23cursos_ac8']==''){$sError=$ERR['cara23cursos_ac8'].$sSepara.$sError;}
			if ($DATA['cara23cursos_ac7']==''){$sError=$ERR['cara23cursos_ac7'].$sSepara.$sError;}
			if ($DATA['cara23cursos_ac6']==''){$sError=$ERR['cara23cursos_ac6'].$sSepara.$sError;}
			if ($DATA['cara23cursos_ac5']==''){$sError=$ERR['cara23cursos_ac5'].$sSepara.$sError;}
			if ($DATA['cara23cursos_ac4']==''){$sError=$ERR['cara23cursos_ac4'].$sSepara.$sError;}
			if ($DATA['cara23cursos_ac3']==''){$sError=$ERR['cara23cursos_ac3'].$sSepara.$sError;}
			if ($DATA['cara23cursos_ac2']==''){$sError=$ERR['cara23cursos_ac2'].$sSepara.$sError;}
			if ($DATA['cara23cursos_ac1']==''){$sError=$ERR['cara23cursos_ac1'].$sSepara.$sError;}
			$bHayAccion=false;
			if ($DATA['cara23cursos_ac7']==1){$bHayAccion=true;}
			if ($DATA['cara23cursos_ac6']==1){$bHayAccion=true;}
			if ($DATA['cara23cursos_ac5']==1){$bHayAccion=true;}
			if ($DATA['cara23cursos_ac4']==1){$bHayAccion=true;}
			if ($DATA['cara23cursos_ac3']==1){$bHayAccion=true;}
			if ($DATA['cara23cursos_ac2']==1){$bHayAccion=true;}
			if ($DATA['cara23cursos_ac1']==1){$bHayAccion=true;}
			if (!$bHayAccion){$sError=$ERR['cara23cursos_acciones'].$sSepara.$sError;}
			}else{
			if ($DATA['cara23cursos_ac7']==''){$DATA['cara23cursos_ac7']=-1;}
			if ($DATA['cara23cursos_ac6']==''){$DATA['cara23cursos_ac6']=-1;}
			if ($DATA['cara23cursos_ac5']==''){$DATA['cara23cursos_ac5']=-1;}
			if ($DATA['cara23cursos_ac4']==''){$DATA['cara23cursos_ac4']=-1;}
			if ($DATA['cara23cursos_ac3']==''){$DATA['cara23cursos_ac3']=-1;}
			if ($DATA['cara23cursos_ac2']==''){$DATA['cara23cursos_ac2']=-1;}
			if ($DATA['cara23cursos_ac1']==''){$DATA['cara23cursos_ac1']=-1;}
			}
		if ($DATA['cara23cursos_ac9']==''){$DATA['cara23cursos_ac9']=-1;}
		if ($DATA['cara23cursos_ac8']==''){$DATA['cara23cursos_ac8']=-1;}
		if ($bCursos1){
			//if ($DATA['cara23cursos_accionlider']==''){$sError=$ERR['cara23cursos_accionlider'].$sSepara.$sError;}
			//if ($DATA['cara23cursos_otros']==''){$sError=$ERR['cara23cursos_otros'].$sSepara.$sError;}
			if ($DATA['cara23estado']==7){
				if ($DATA['cara23cursos_menor200']==''){$sError=$ERR['cara23cursos_menor200'].$sSepara.$sError;}
				if ($DATA['cara23cursos_siningre']==''){$sError=$ERR['cara23cursos_siningre'].$sSepara.$sError;}
				if ($DATA['cara23cursos_total']==''){$sError=$ERR['cara23cursos_total'].$sSepara.$sError;}
				}else{
				if ($DATA['cara23cursos_menor200']==''){$DATA['cara23cursos_menor200']=0;}
				if ($DATA['cara23cursos_siningre']==''){$DATA['cara23cursos_siningre']=0;}
				if ($DATA['cara23cursos_total']==''){$DATA['cara23cursos_total']=0;}
				}
			}else{
			$DATA['cara23cursos_menor200']=0;
			$DATA['cara23cursos_siningre']=0;
			$DATA['cara23cursos_total']=0;
			}
		//if ($DATA['cara23catedra_segprev']==''){$sError=$ERR['cara23catedra_segprev'].$sSepara.$sError;}
		//if ($DATA['cara23catedra_resultados']==''){$sError=$ERR['cara23catedra_resultados'].$sSepara.$sError;}
		//if ($DATA['cara23catedra_acciones']==''){$sError=$ERR['cara23catedra_acciones'].$sSepara.$sError;}
		if ($DATA['cara23estado']==7){
			if ($iTipoSeg!=3){
				if ($DATA['cara23catedra_avance']=='-1'){$sError=$ERR['cara23catedra_avance'].$sSepara.$sError;}
				}
			}else{
			if ($DATA['cara23catedra_avance']==''){$DATA['cara23catedra_avance']=0;}
			}
		if ($bCatedra1){
			if ($DATA['cara23catedra_webconf']==''){$sError=$ERR['cara23catedra_webconf'].$sSepara.$sError;}
			if ($DATA['cara23catedra_bler2']==''){$sError=$ERR['cara23catedra_bler2'].$sSepara.$sError;}
			if ($DATA['cara23catedra_bler1']==''){$sError=$ERR['cara23catedra_bler1'].$sSepara.$sError;}
			if ($DATA['cara23catedra_skype']==''){$sError=$ERR['cara23catedra_skype'].$sSepara.$sError;}
			}else{
			$DATA['cara23catedra_webconf']=-1;
			$DATA['cara23catedra_bler2']=-1;
			$DATA['cara23catedra_bler1']=-1;
			$DATA['cara23catedra_skype']=-1;
			}
		if ($bInducciones){
			if ($DATA['cara23asisteinmersioncv']==''){$sError=$ERR['cara23asisteinmersioncv'].$sSepara.$sError;}
			if ($DATA['cara23asisteinduccion']==''){$sError=$ERR['cara23asisteinduccion'].$sSepara.$sError;}
			}else{
			$DATA['cara23asisteinmersioncv']=-1;
			$DATA['cara23asisteinduccion']=-1;
			}
		if ($DATA['cara23idtipo']==''){$sError=$ERR['cara23idtipo'].$sSepara.$sError;}
		if ($DATA['cara23idtercero']==0){$sError=$ERR['cara23idtercero'].$sSepara.$sError;}
		if ($DATA['cara23estado']==7){
			if ($DATA['cara23aplaza']==-1){$sError=$ERR['cara23aplaza'].$sSepara.$sError;}
			if ($DATA['cara23seretira']==-1){
				$sError=$ERR['cara23seretira'].$sSepara.$sError;
				}else{
				if ($DATA['cara23seretira']==1){
					if ($DATA['cara01factorprincipaldesc']==0){
						$sError=$ERR['cara01factorprincipaldesc'].$sSepara.$sError;
						}else{
						$bGuardarDesercion=true;
						}
					}else{
					if ($iTipoSeg==3){
						if ($DATA['cara01factorprincpermanencia']==0){
							$sError=$ERR['cara01factorprincpermanencia'].$sSepara.$sError;
							}else{
							$bGuardarDesercion=true;
							}
						}
					}
				}
			}
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Terminando las validaciones del guardado. '.$sError.'<br>';}
		if ($sError!=''){$DATA['cara23estado']=0;}
		$sErrorCerrando=$sError;
		$sError='';
		//Fin de las valiaciones NO LLAVE.
		}
	//Valiaciones de campos obligatorios en todo guardar.
	if ($DATA['cara23idencuesta']==''){$sError=$ERR['cara23idencuesta'];}
	// -- Se verifican los valores de campos de otras tablas.
	/*
	if ($DATA['cara23zonal_idlider_doc']!=''){
		if ($sError==''){$sError=tabla_terceros_existe($DATA['cara23zonal_idlider_td'], $DATA['cara23zonal_idlider_doc'], $objDB, 'El tercero Zonal_idlider ');}
		if ($sError==''){
			list($sError, $sInfo)=tercero_Bloqueado($DATA['cara23zonal_idlider'], $objDB);
			if ($sInfo!=''){$sError=$sError.'<br>'.sInfo;}
			}
		}
	*/
	if ($DATA['cara23idtercero_doc']!=''){
		if ($sError==''){$sError=tabla_terceros_existe($DATA['cara23idtercero_td'], $DATA['cara23idtercero_doc'], $objDB, 'El tercero Tercero ');}
		if ($sError==''){
			list($sError, $sInfo)=tercero_Bloqueado($DATA['cara23idtercero'], $objDB);
			if ($sInfo!=''){$sError=$sError.'<br>'.sInfo;}
			}
		}
	if ($sError==''){
		//Vamos a mostar el dato del criterio de riesgo
		switch($iTipoSeg){
			case 2:
			case 3:
			if ($DATA['paso']!=10){}
			if (true){
				if ($DATA['cara23catedra_avance']!=''){
					//Traer el riesgo anterior.
					$sSQLTope='';
					if ($DATA['paso']!=10){
						$sSQLTope=' AND cara23consec<'.$DATA['cara23consec'];
						}
					$sSQL='SELECT cara23catedra_criterio, cara23estado
FROM cara23acompanamento
WHERE cara23idencuesta='.$DATA['cara23idencuesta'].$sSQLTope.' AND cara23idtercero='.$DATA['cara23idtercero'].' 
ORDER BY cara23idtipo DESC, cara23consec DESC
LIMIT 0, 1';
					$tabla=$objDB->ejecutasql($sSQL);
					if ($objDB->nf($tabla)>0){
						$fila=$objDB->sf($tabla);
						switch($fila['cara23catedra_criterio']){
							case 2: // bajo
							case 3: //alto
							switch($DATA['cara23catedra_criterio']){
								case 2: // bajo
								case 3: //alto
								$DATA['cara23catedra_segprev']='Continua en alerta.';
								break;
								case 1:
								$DATA['cara23catedra_segprev']='No continua en alerta.';
								break;
								}
							break;
							case 1:
							switch($DATA['cara23catedra_criterio']){
								case 2: // bajo
								case 3: //alto
								$DATA['cara23catedra_segprev']='Nueva alerta.';
								break;
								case 1:
								$DATA['cara23catedra_segprev']='Sin alerta.';
								break;
								}
							break;
							}
						}
					}else{
					//Quitar la alerta.
					$DATA['cara23catedra_segprev']='No se ha definido la situación de riesgo.';
					}
				}
			}
		}
	if ($sError==''){
		//Vamos a evitar que abra seguimientos si no ha cumplido con el ciclo anterior.
		switch($iTipoSeg){
			case 2:
			case 3:
			$sSQL='SELECT cara23id
FROM cara23acompanamento
WHERE cara23idencuesta='.$DATA['cara23idencuesta'].' AND cara23idtipo<'.$iTipoSeg.' AND cara23idtercero='.$DATA['cara23idtercero'].' AND cara23estado<>7';
			$tabla=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla)>0){
				$sError='No es posible iniciar un nuevo acompa&ntilde;amiento sin haber completado los registros del momento anterior.';
				}
			break;
			}
		}
	if ($sError==''){
		//Vamos a evitar que abra seguimientos si no existe cuando menos un seguiiento anterior.
		switch($iTipoSeg){
			case 2:
			case 3:
			$sSQL='SELECT cara23id
FROM cara23acompanamento
WHERE cara23idencuesta='.$DATA['cara23idencuesta'].' AND cara23idtipo='.($iTipoSeg-1).' AND cara23idtercero='.$DATA['cara23idtercero'].' AND cara23estado=7';
			$tabla=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla)==0){
				$sNomPrevio='inicial';
				$sNomActual='intermedio';
				if ($iTipoSeg==3){
					$sNomPrevio='intermedio';
					$sNomActual='final';
					}
				$sError='No es posible iniciar un acompa&ntilde;amiento '.$sNomActual.' sin un acompa&ntilde;amiento '.$sNomPrevio.'.';
				}
			break;
			}
		}
	$bQuitarCodigo=false;
	if ($sError==''){
		if ($DATA['paso']==10){
			if ($DATA['cara23consec']==''){
				$DATA['cara23consec']=tabla_consecutivo('cara23acompanamento', 'cara23consec', 'cara23idencuesta='.$DATA['cara23idencuesta'].'', $objDB);
				if ($DATA['cara23consec']==-1){$sError=$objDB->serror;}
				$bQuitarCodigo=true;
				}
			if ($sError==''){
				$sSQL='SELECT cara23idencuesta FROM cara23acompanamento WHERE cara23idencuesta='.$DATA['cara23idencuesta'].' AND cara23consec='.$DATA['cara23consec'].'';
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
			//No puede haber otra inicial si ya existe una.
            if($iTipoSeg==1 || $iTipoSeg==3){
                $sSeguimiento='';
                if($iTipoSeg==1){
                    $sSeguimiento=$acara23idtipo[1];
                    }else{
                    $sSeguimiento=$acara23idtipo[3];
                    }
                $sSQL='SELECT cara23idencuesta FROM cara23acompanamento WHERE cara23idencuesta='.$DATA['cara23idencuesta'].' AND cara23idtipo='.$iTipoSeg.'';
                $result=$objDB->ejecutasql($sSQL);
                if ($objDB->nf($result)!=0){
                    $sError='Existe un seguimiento '.$sSeguimiento;
                    }else{
                    if (!seg_revisa_permiso($iCodModulo, 2, $objDB)){$sError=$ERR['2'];}
                    }
                }
			}
		}
	if ($sError==''){
		if ($DATA['paso']==10){
			//valida cantidad intermedios.
            if($iTipoSeg==2){
                $sSQL='SELECT cara23idencuesta FROM cara23acompanamento WHERE cara23idencuesta='.$DATA['cara23idencuesta'].' AND cara23idtipo=2'.'';
                $result=$objDB->ejecutasql($sSQL);
                if ($objDB->nf($result)==4){
                    $sError='M&aacute;ximo cuatro acompa&ntilde;amientos intermedios';
                    }else{
                    if (!seg_revisa_permiso($iCodModulo, 2, $objDB)){$sError=$ERR['2'];}
                    }
                }
			}
		}
	if ($sError==''){
		if ($DATA['paso']==10){
			//No valida para permitir acompañamiento final.
            if($iTipoSeg==3){
                $sSQL='SELECT cara23idencuesta FROM cara23acompanamento WHERE cara23idencuesta='.$DATA['cara23idencuesta'].' AND cara23idtipo=2 AND cara23estado=7'.'';
                $result=$objDB->ejecutasql($sSQL);
                if ($objDB->nf($result)<2){
                    $sError='Debe tener m&iacute;nimo dos acompa&ntilde;amientos intermedios';
                    }else{
                    if (!seg_revisa_permiso($iCodModulo, 2, $objDB)){$sError=$ERR['2'];}
                    }
                }
			}
		}
	if ($sError==''){
		if ($DATA['paso']==10){
			//Preparar el Id, Si no lo hay se quita la comprobación.
			$DATA['cara23id']=tabla_consecutivo('cara23acompanamento','cara23id', '', $objDB);
			if ($DATA['cara23id']==-1){$sError=$objDB->serror;}
			}
		}
	$bCambiaCatedra=false;
	if ($sError==''){
		if (get_magic_quotes_gpc()==1){$DATA['cara23catedra_segprev']=stripslashes($DATA['cara23catedra_segprev']);}
		//Si el campo cara23catedra_segprev permite html quite la linea htmlspecialchars para el campo y habilite la siguiente linea:
		//$cara23catedra_segprev=addslashes($DATA['cara23catedra_segprev']);
		$cara23catedra_segprev=str_replace('"', '\"', $DATA['cara23catedra_segprev']);
		if (get_magic_quotes_gpc()==1){$DATA['cara23cursos_otros']=stripslashes($DATA['cara23cursos_otros']);}
		//Si el campo cara23cursos_otros permite html quite la linea htmlspecialchars para el campo y habilite la siguiente linea:
		//$cara23cursos_otros=addslashes($DATA['cara23cursos_otros']);
		$cara23cursos_otros=str_replace('"', '\"', $DATA['cara23cursos_otros']);
		if (get_magic_quotes_gpc()==1){$DATA['cara23cursos_accionlider']=stripslashes($DATA['cara23cursos_accionlider']);}
		//Si el campo cara23cursos_accionlider permite html quite la linea htmlspecialchars para el campo y habilite la siguiente linea:
		//$cara23cursos_accionlider=addslashes($DATA['cara23cursos_accionlider']);
		$cara23cursos_accionlider=str_replace('"', '\"', $DATA['cara23cursos_accionlider']);
		if (get_magic_quotes_gpc()==1){$DATA['cara23aler_sociodem']=stripslashes($DATA['cara23aler_sociodem']);}
		//Si el campo cara23aler_sociodem permite html quite la linea htmlspecialchars para el campo y habilite la siguiente linea:
		//$cara23aler_sociodem=addslashes($DATA['cara23aler_sociodem']);
		$cara23aler_sociodem=str_replace('"', '\"', $DATA['cara23aler_sociodem']);
		if (get_magic_quotes_gpc()==1){$DATA['cara23aler_psico']=stripslashes($DATA['cara23aler_psico']);}
		//Si el campo cara23aler_psico permite html quite la linea htmlspecialchars para el campo y habilite la siguiente linea:
		//$cara23aler_psico=addslashes($DATA['cara23aler_psico']);
		$cara23aler_psico=str_replace('"', '\"', $DATA['cara23aler_psico']);
		if (get_magic_quotes_gpc()==1){$DATA['cara23aler_academ']=stripslashes($DATA['cara23aler_academ']);}
		//Si el campo cara23aler_academ permite html quite la linea htmlspecialchars para el campo y habilite la siguiente linea:
		//$cara23aler_academ=addslashes($DATA['cara23aler_academ']);
		$cara23aler_academ=str_replace('"', '\"', $DATA['cara23aler_academ']);
		if (get_magic_quotes_gpc()==1){$DATA['cara23aler_econom']=stripslashes($DATA['cara23aler_econom']);}
		//Si el campo cara23aler_econom permite html quite la linea htmlspecialchars para el campo y habilite la siguiente linea:
		//$cara23aler_econom=addslashes($DATA['cara23aler_econom']);
		$cara23aler_econom=str_replace('"', '\"', $DATA['cara23aler_econom']);
		if (get_magic_quotes_gpc()==1){$DATA['cara23aler_externo']=stripslashes($DATA['cara23aler_externo']);}
		//Si el campo cara23aler_externo permite html quite la linea htmlspecialchars para el campo y habilite la siguiente linea:
		//$cara23aler_externo=addslashes($DATA['cara23aler_externo']);
		$cara23aler_externo=str_replace('"', '\"', $DATA['cara23aler_externo']);
		if (get_magic_quotes_gpc()==1){$DATA['cara23aler_interno']=stripslashes($DATA['cara23aler_interno']);}
		//Si el campo cara23aler_interno permite html quite la linea htmlspecialchars para el campo y habilite la siguiente linea:
		//$cara23aler_interno=addslashes($DATA['cara23aler_interno']);
		$cara23aler_interno=str_replace('"', '\"', $DATA['cara23aler_interno']);
		if (get_magic_quotes_gpc()==1){$DATA['cara23aler_nivel']=stripslashes($DATA['cara23aler_nivel']);}
		//Si el campo cara23aler_nivel permite html quite la linea htmlspecialchars para el campo y habilite la siguiente linea:
		//$cara23aler_nivel=addslashes($DATA['cara23aler_nivel']);
		$cara23aler_nivel=str_replace('"', '\"', $DATA['cara23aler_nivel']);
		if (get_magic_quotes_gpc()==1){$DATA['cara23contacto_observa']=stripslashes($DATA['cara23contacto_observa']);}
		//Si el campo cara23contacto_observa permite html quite la linea htmlspecialchars para el campo y habilite la siguiente linea:
		//$cara23contacto_observa=addslashes($DATA['cara23contacto_observa']);
		$cara23contacto_observa=str_replace('"', '\"', $DATA['cara23contacto_observa']);
		if (get_magic_quotes_gpc()==1){$DATA['cara23zonal_retro']=stripslashes($DATA['cara23zonal_retro']);}
		//Si el campo cara23zonal_retro permite html quite la linea htmlspecialchars para el campo y habilite la siguiente linea:
		//$cara23zonal_retro=addslashes($DATA['cara23zonal_retro']);
		$cara23zonal_retro=str_replace('"', '\"', $DATA['cara23zonal_retro']);
		$bpasa=false;
		if ($DATA['paso']==10){
			$DATA['cara23estado']=0;
			$DATA['cara23zonal_fecha']=0;
			$cara23fecha=0;
			$sCampos2323='cara23idencuesta, cara23consec, cara23id, cara23idtercero, cara23idtipo, cara23estado, cara23asisteinduccion, cara23asisteinmersioncv, cara23catedra_skype, cara23catedra_bler1, 
cara23catedra_bler2, cara23catedra_webconf, cara23catedra_avance, cara23catedra_criterio, cara23catedra_acciones, cara23catedra_resultados, cara23catedra_segprev, cara23cursos_total, cara23cursos_siningre, cara23cursos_porcing, 
cara23cursos_menor200, cara23cursos_porcperdida, cara23cursos_criterio, cara23cursos_otros, cara23cursos_accionlider, cara23aler_sociodem, cara23aler_psico, cara23aler_academ, cara23aler_econom, cara23aler_externo, 
cara23aler_interno, cara23aler_nivel, cara23aler_criterio, cara23comp_digital, cara23comp_cuanti, cara23comp_lectora, cara23comp_ingles, cara23comp_criterio, cara23nivela_digital, cara23nivela_cuanti, 
cara23nivela_lecto, cara23nivela_ingles, cara23nivela_exito, cara23contacto_efectivo, cara23contacto_forma, cara23contacto_observa, cara23aplaza, cara23seretira, cara23factorriesgo, cara23zonal_retro, 
cara23zonal_fecha, cara23zonal_idlider, cara23fecha, cara23cursos_ac1, cara23cursos_ac2, cara23cursos_ac3, cara23cursos_ac4, cara23cursos_ac5, cara23cursos_ac6, cara23cursos_ac7, 
cara23cursos_ac8, cara23cursos_ac9, cara23catedra_aprueba, cara23permanece';
			$sValores2323=''.$DATA['cara23idencuesta'].', '.$DATA['cara23consec'].', '.$DATA['cara23id'].', '.$DATA['cara23idtercero'].', '.$DATA['cara23idtipo'].', '.$DATA['cara23estado'].', '.$DATA['cara23asisteinduccion'].', '.$DATA['cara23asisteinmersioncv'].', '.$DATA['cara23catedra_skype'].', '.$DATA['cara23catedra_bler1'].', 
'.$DATA['cara23catedra_bler2'].', '.$DATA['cara23catedra_webconf'].', '.$DATA['cara23catedra_avance'].', '.$DATA['cara23catedra_criterio'].', '.$DATA['cara23catedra_acciones'].', '.$DATA['cara23catedra_resultados'].', "'.$cara23catedra_segprev.'", '.$DATA['cara23cursos_total'].', '.$DATA['cara23cursos_siningre'].', '.$DATA['cara23cursos_porcing'].', 
'.$DATA['cara23cursos_menor200'].', '.$DATA['cara23cursos_porcperdida'].', '.$DATA['cara23cursos_criterio'].', "'.$cara23cursos_otros.'", "'.$cara23cursos_accionlider.'", "'.$cara23aler_sociodem.'", "'.$cara23aler_psico.'", "'.$cara23aler_academ.'", "'.$cara23aler_econom.'", "'.$cara23aler_externo.'", 
"'.$cara23aler_interno.'", "'.$cara23aler_nivel.'", '.$DATA['cara23aler_criterio'].', '.$DATA['cara23comp_digital'].', '.$DATA['cara23comp_cuanti'].', '.$DATA['cara23comp_lectora'].', '.$DATA['cara23comp_ingles'].', '.$DATA['cara23comp_criterio'].', '.$DATA['cara23nivela_digital'].', '.$DATA['cara23nivela_cuanti'].', 
'.$DATA['cara23nivela_lecto'].', '.$DATA['cara23nivela_ingles'].', '.$DATA['cara23nivela_exito'].', '.$DATA['cara23contacto_efectivo'].', '.$DATA['cara23contacto_forma'].', "'.$cara23contacto_observa.'", '.$DATA['cara23aplaza'].', '.$DATA['cara23seretira'].', '.$DATA['cara23factorriesgo'].', "'.$cara23zonal_retro.'", 
"'.$DATA['cara23zonal_fecha'].'", '.$DATA['cara23zonal_idlider'].', "'.$DATA['cara23fecha'].'", '.$DATA['cara23cursos_ac1'].', '.$DATA['cara23cursos_ac2'].', '.$DATA['cara23cursos_ac3'].', '.$DATA['cara23cursos_ac4'].', '.$DATA['cara23cursos_ac5'].', '.$DATA['cara23cursos_ac6'].', '.$DATA['cara23cursos_ac7'].', 
'.$DATA['cara23cursos_ac8'].', '.$DATA['cara23cursos_ac9'].', '.$DATA['cara23catedra_aprueba'].', '.$DATA['cara23permanece'].'';
			if ($APP->utf8==1){
				$sSQL='INSERT INTO cara23acompanamento ('.$sCampos2323.') VALUES ('.utf8_encode($sValores2323).');';
				$sdetalle=$sCampos2323.'['.utf8_encode($sValores2323).']';
				}else{
				$sSQL='INSERT INTO cara23acompanamento ('.$sCampos2323.') VALUES ('.$sValores2323.');';
				$sdetalle=$sCampos2323.'['.$sValores2323.']';
				}
			$idaccion=2;
			$bpasa=true;
			}else{
			$scampo[1]='cara23asisteinduccion';
			$scampo[2]='cara23asisteinmersioncv';
			$scampo[3]='cara23catedra_skype';
			$scampo[4]='cara23catedra_bler1';
			$scampo[5]='cara23catedra_bler2';
			$scampo[6]='cara23catedra_webconf';
			$scampo[7]='cara23catedra_avance';
			$scampo[8]='cara23catedra_acciones';
			$scampo[9]='cara23catedra_resultados';
			$scampo[10]='cara23catedra_segprev';
			$scampo[11]='cara23cursos_total';
			$scampo[12]='cara23cursos_siningre';
			$scampo[13]='cara23cursos_menor200';
			$scampo[14]='cara23cursos_otros';
			$scampo[15]='cara23cursos_accionlider';
			$scampo[16]='cara23aler_sociodem';
			$scampo[17]='cara23aler_psico';
			$scampo[18]='cara23aler_academ';
			$scampo[19]='cara23aler_econom';
			$scampo[20]='cara23aler_externo';
			$scampo[21]='cara23aler_interno';
			$scampo[22]='cara23aler_nivel';
			$scampo[23]='cara23aler_criterio';
			$scampo[24]='cara23comp_digital';
			$scampo[25]='cara23comp_cuanti';
			$scampo[26]='cara23comp_lectora';
			$scampo[27]='cara23comp_ingles';
			$scampo[28]='cara23nivela_digital';
			$scampo[29]='cara23nivela_cuanti';
			$scampo[30]='cara23nivela_lecto';
			$scampo[31]='cara23nivela_ingles';
			$scampo[32]='cara23nivela_exito';
			$scampo[33]='cara23contacto_efectivo';
			$scampo[34]='cara23contacto_forma';
			$scampo[35]='cara23contacto_observa';
			$scampo[36]='cara23aplaza';
			$scampo[37]='cara23seretira';
			$scampo[38]='cara23catedra_criterio';
			$scampo[39]='cara23fecha';
			$scampo[40]='cara23cursos_ac1';
			$scampo[41]='cara23cursos_ac2';
			$scampo[42]='cara23cursos_ac3';
			$scampo[43]='cara23cursos_ac4';
			$scampo[44]='cara23cursos_ac5';
			$scampo[45]='cara23cursos_ac6';
			$scampo[46]='cara23cursos_ac7';
			$scampo[47]='cara23cursos_ac8';
			$scampo[48]='cara23cursos_ac9';
			$scampo[49]='cara23aler_criterio';
			$scampo[50]='cara23comp_criterio';
			$scampo[51]='cara23factorriesgo';
			$scampo[52]='cara23estado';
			$scampo[53]='cara23cursos_porcing';
			$scampo[54]='cara23cursos_porcperdida';
			$scampo[55]='cara23cursos_criterio';
			$scampo[56]='cara23catedra_aprueba';
			$scampo[57]='cara23permanece';
			$sdato[1]=$DATA['cara23asisteinduccion'];
			$sdato[2]=$DATA['cara23asisteinmersioncv'];
			$sdato[3]=$DATA['cara23catedra_skype'];
			$sdato[4]=$DATA['cara23catedra_bler1'];
			$sdato[5]=$DATA['cara23catedra_bler2'];
			$sdato[6]=$DATA['cara23catedra_webconf'];
			$sdato[7]=$DATA['cara23catedra_avance'];
			$sdato[8]=$DATA['cara23catedra_acciones'];
			$sdato[9]=$DATA['cara23catedra_resultados'];
			$sdato[10]=$cara23catedra_segprev;
			$sdato[11]=$DATA['cara23cursos_total'];
			$sdato[12]=$DATA['cara23cursos_siningre'];
			$sdato[13]=$DATA['cara23cursos_menor200'];
			$sdato[14]=$cara23cursos_otros;
			$sdato[15]=$cara23cursos_accionlider;
			$sdato[16]=$cara23aler_sociodem;
			$sdato[17]=$cara23aler_psico;
			$sdato[18]=$cara23aler_academ;
			$sdato[19]=$cara23aler_econom;
			$sdato[20]=$cara23aler_externo;
			$sdato[21]=$cara23aler_interno;
			$sdato[22]=$cara23aler_nivel;
			$sdato[23]=$DATA['cara23aler_criterio'];
			$sdato[24]=$DATA['cara23comp_digital'];
			$sdato[25]=$DATA['cara23comp_cuanti'];
			$sdato[26]=$DATA['cara23comp_lectora'];
			$sdato[27]=$DATA['cara23comp_ingles'];
			$sdato[28]=$DATA['cara23nivela_digital'];
			$sdato[29]=$DATA['cara23nivela_cuanti'];
			$sdato[30]=$DATA['cara23nivela_lecto'];
			$sdato[31]=$DATA['cara23nivela_ingles'];
			$sdato[32]=$DATA['cara23nivela_exito'];
			$sdato[33]=$DATA['cara23contacto_efectivo'];
			$sdato[34]=$DATA['cara23contacto_forma'];
			$sdato[35]=$cara23contacto_observa;
			$sdato[36]=$DATA['cara23aplaza'];
			$sdato[37]=$DATA['cara23seretira'];
			$sdato[38]=$DATA['cara23catedra_criterio'];
			$sdato[39]=$DATA['cara23fecha'];
			$sdato[40]=$DATA['cara23cursos_ac1'];
			$sdato[41]=$DATA['cara23cursos_ac2'];
			$sdato[42]=$DATA['cara23cursos_ac3'];
			$sdato[43]=$DATA['cara23cursos_ac4'];
			$sdato[44]=$DATA['cara23cursos_ac5'];
			$sdato[45]=$DATA['cara23cursos_ac6'];
			$sdato[46]=$DATA['cara23cursos_ac7'];
			$sdato[47]=$DATA['cara23cursos_ac8'];
			$sdato[48]=$DATA['cara23cursos_ac9'];
			$sdato[49]=$DATA['cara23aler_criterio'];
			$sdato[50]=$DATA['cara23comp_criterio'];
			$sdato[51]=$DATA['cara23factorriesgo'];
			$sdato[52]=$DATA['cara23estado'];
			$sdato[53]=$DATA['cara23cursos_porcing'];
			$sdato[54]=$DATA['cara23cursos_porcperdida'];
			$sdato[55]=$DATA['cara23cursos_criterio'];
			$sdato[56]=$DATA['cara23catedra_aprueba'];
			$sdato[57]=$DATA['cara23permanece'];
			$numcmod=57;
			if ($DATA['cara23estado']==7){
				$bCerrando=true;
				}
			$sWhere='cara23id='.$DATA['cara23id'].'';
			$sSQL='SELECT * FROM cara23acompanamento WHERE '.$sWhere;
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
					$sSQL='UPDATE cara23acompanamento SET '.utf8_encode($sdatos).' WHERE '.$sWhere.';';
					}else{
					$sdetalle=$sdatos.'['.$sWhere.']';
					$sSQL='UPDATE cara23acompanamento SET '.$sdatos.' WHERE '.$sWhere.';';
					}
				$idaccion=3;
				}
			}
		$sInfo01='';
		if ($bpasa){
			if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Intentando Guardar 2323 '.$sSQL.'<br>';}
			$result=$objDB->ejecutasql($sSQL);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' [2323] ..<!-- '.$sSQL.' -->';
				if ($idaccion==2){
					$DATA['cara23id']='';
					$bQuitarCodigo=true;
					$bGuardarDesercion=false;
					$DATA['paso']=0;
					}else{
					$DATA['paso']=2;
					}
				$bCerrando=false;
				}else{
				if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Guardar 2323 '.$sSQL.'<br>';}
				if ($bAudita[$idaccion]){seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], $idaccion, $DATA['cara23id'], $sdetalle, $objDB);}
				$sDebugT=f2323_TotalizarAcompanamentos($DATA['cara23idencuesta'], $objDB, $bDebug);
				$sDebug=$sDebug.$sDebugT;
				$DATA['paso']=2;
				}
			}else{
			if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' No hay nada para guardar. '.$sError.' -- '.$sErrorCerrando.'<br>';}
			$DATA['paso']=2;
			}
		if ($bGuardarDesercion){
			$sInfo01='cara01factorprincipaldesc='.$DATA['cara01factorprincipaldesc'].', cara01factorprincpermanencia='.$DATA['cara01factorprincpermanencia'].'';
			}
		//Intentamos guardar la tabla padre.
		$sSQL='SELECT cara01idcursocatedra FROM cara01encuesta WHERE cara01id='.$DATA['cara23idencuesta'].'';
		$tabla1=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla1)>0){
			$fila1=$objDB->sf($tabla1);
			if ($fila1['cara01idcursocatedra']!=$DATA['cara01idcursocatedra']){
				if ($sInfo01!=''){$sInfo01=$sInfo01.', ';}
				$sInfo01=$sInfo01.'cara01idcursocatedra='.$DATA['cara01idcursocatedra'].'';
				$bCambiaCatedra=true;
				}
			}
		if ($sInfo01!=''){
			$sSQL='UPDATE cara01encuesta SET '.$sInfo01.' WHERE cara01id='.$DATA['cara23idencuesta'].'';
			if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Marcando los valores de la tabla superior. '.$sSQL.'<br>';}
			$result=$objDB->ejecutasql($sSQL);
			}
		//Termina de guardar.
		}else{
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Error antes de intentar guardar '.$sError.'<br>';}
		$DATA['paso']=$DATA['paso']-10;
		if ($bQuitarCodigo){
			$DATA['cara23consec']='';
			}
		$bCerrando=false;
		}
	$sInfoCierre='';
	if ($bCerrando){
		list($sErrorCerrando2, $sDebugCerrar)=f2323_Cerrar($DATA['cara23id'], $objDB, $bDebug);
		$sErrorCerrando=$sErrorCerrando.$sErrorCerrando2;
		$sDebug=$sDebug.$sDebugCerrar;
		list($sErrorTotal, $sDebugTotal)=f2323_Totalizar($DATA['cara23idencuesta'], $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugTotal;
		}
	//if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' InfoDepura<br>';}
	return array($DATA, $sError, $iTipoError, $bCerrando, $sErrorCerrando, $sDebug, $bCambiaCatedra);
	}
function f2323_db_Eliminar($cara23id, $objDB, $bDebug=false){
	$iCodModulo=2323;
	$bAudita[4]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2323='lg/lg_2323_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2323)){$mensajes_2323='lg/lg_2323_es.php';}
	require $mensajes_todas;
	require $mensajes_2323;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	$cara23id=numeros_validar($cara23id);
	// Traer los datos para hacer las validaciones.
	if ($sError==''){
		$sSQL='SELECT cara23idencuesta FROM cara23acompanamento WHERE cara23id='.$cara23id.'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$filabase=$objDB->sf($tabla);
			}else{
			$sError='No se encuentra el registro solicitado {Ref: '.$cara23id.'}';
			}
		}
	if ($sError==''){
		if (!seg_revisa_permiso($iCodModulo, 4, $objDB)){
			$sError=$ERR['4'];
			}
		}
	if ($sError==''){
		$sSQL='SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=2323';
		$tablaor=$objDB->ejecutasql($sSQL);
		while ($filaor=$objDB->sf($tablaor)){
			$sSQL='SELECT '.$filaor['campoid'].' FROM '.$filaor['tabla'].' WHERE '.$filaor['camporev'].'='.$_REQUEST['cara23id'].' LIMIT 0, 1';
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
		$sWhere='cara23id='.$cara23id.'';
		//$sWhere='cara23consec='.$filabase['cara23consec'].' AND cara23idencuesta='.$filabase['cara23idencuesta'].'';
		$sSQL='DELETE FROM cara23acompanamento WHERE '.$sWhere.';';
		$result=$objDB->ejecutasql($sSQL);
		if ($result==false){
			$sError=$ERR['falla_eliminar'].' .. <!-- '.$sSQL.' -->';
			}else{
			if ($bAudita[4]){seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $cara23id, $sWhere, $objDB);}
			f2323_TotalizarAcompanamentos($filabase['cara23idencuesta'], $objDB);
			list($sErrorTotal, $sDebugTotal)=f2323_Totalizar($filabase['cara23idencuesta'], $objDB, $bDebug);
			$sDebug=$sDebug.$sDebugTotal;
			}
		}
	return array($sError, $iTipoError, $sDebug);
	}
function f2323_TituloBusqueda(){
	return 'Busqueda de Acompanamiento';
	}
function f2323_ParametrosBusqueda(){
	$sParams='<label class="Label90">Nombre</label><label><input id="b2323nombre" name="b2323nombre" type="text" value="" onchange="paginarbusqueda()" /></label>';
	return $sParams;
	}
function f2323_JavaScriptBusqueda($iModuloBusca){
	$sRes='var sCampo=window.document.frmedita.scampobusca.value;
var params=new Array();
params[100]=sCampo;
params[101]=window.document.frmedita.paginabusqueda.value;
params[102]=window.document.frmedita.lppfbusqueda.value;
params[103]=window.document.frmedita.b2323nombre.value;
xajax_f'.$iModuloBusca.'_HtmlBusqueda(params);';
	return $sRes;
	}
function f2323_TablaDetalleBusquedas($aParametros, $objDB){
	$res='';
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2323='lg/lg_2323_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2323)){$mensajes_2323='lg/lg_2323_es.php';}
	require $mensajes_todas;
	require $mensajes_2323;
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
	if ($sLeyenda!=''){
		$sLeyenda='<div class="salto1px"></div>
<div class="GrupoCamposAyuda">
'.$sLeyenda.'
<div class="salto1px"></div>
</div>';
		return array($sLeyenda.'<input id="paginaf2323" name="paginaf2323" type="hidden" value="'.$pagina.'"/><input id="lppf2323" name="lppf2323" type="hidden" value="'.$lineastabla.'"/>', $sDebug);
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
	$sTitulos='Encuesta, Consec, Id, Tercero, Tipo, Estado, Asisteinduccion, Asisteinmersioncv, Catedra_skype, Catedra_bler1, Catedra_bler2, Catedra_webconf, Catedra_avance, Catedra_criterio, Catedra_acciones, Catedra_resultados, Catedra_segprev, Cursos_total, Cursos_siningre, Cursos_porcing, Cursos_menor200, Cursos_porcperdida, Cursos_criterio, Cursos_otros, Cursos_accionlider, Aler_sociodem, Aler_psico, Aler_academ, Aler_econom, Aler_externo, Aler_interno, Aler_nivel, Aler_criterio, Comp_digital, Comp_cuanti, Comp_lectora, Comp_ingles, Comp_criterio, Nivela_digital, Nivela_cuanti, Nivela_lecto, Nivela_ingles, Nivela_exito, Contacto_efectivo, Contacto_forma, Contacto_observa, Contacto_novedad, Factorriesgo, Zonal_retro, Zonal_fecha, Zonal_idlider';
	$sSQL='SELECT T1.cara01idtercero, TB.cara23consec, TB.cara23id, T4.unad11razonsocial AS C4_nombre, TB.cara23idtipo, TB.cara23estado, TB.cara23asisteinduccion, TB.cara23asisteinmersioncv, TB.cara23catedra_skype, TB.cara23catedra_bler1, TB.cara23catedra_bler2, TB.cara23catedra_webconf, T13.cara24titulo, TB.cara23catedra_criterio, TB.cara23catedra_acciones, TB.cara23catedra_resultados, TB.cara23catedra_segprev, TB.cara23cursos_total, TB.cara23cursos_siningre, TB.cara23cursos_porcing, TB.cara23cursos_menor200, TB.cara23cursos_porcperdida, TB.cara23cursos_criterio, TB.cara23cursos_otros, TB.cara23cursos_accionlider, TB.cara23aler_sociodem, TB.cara23aler_psico, TB.cara23aler_academ, TB.cara23aler_econom, TB.cara23aler_externo, TB.cara23aler_interno, TB.cara23aler_nivel, TB.cara23aler_criterio, TB.cara23comp_digital, TB.cara23comp_cuanti, TB.cara23comp_lectora, TB.cara23comp_ingles, TB.cara23comp_criterio, TB.cara23nivela_digital, TB.cara23nivela_cuanti, TB.cara23nivela_lecto, TB.cara23nivela_ingles, TB.cara23nivela_exito, TB.cara23contacto_efectivo, TB.cara23contacto_forma, TB.cara23contacto_observa, TB.cara23contacto_novedad, TB.cara23factorriesgo, TB.cara23zonal_retro, TB.cara23zonal_fecha, T51.unad11razonsocial AS C51_nombre, TB.cara23idencuesta, TB.cara23idtercero, T4.unad11tipodoc AS C4_td, T4.unad11doc AS C4_doc, TB.cara23catedra_avance, TB.cara23zonal_idlider, T51.unad11tipodoc AS C51_td, T51.unad11doc AS C51_doc 
FROM cara23acompanamento AS TB, cara01encuesta AS T1, unad11terceros AS T4, cara24avancecatedra AS T13, unad11terceros AS T51 
WHERE '.$sSQLadd1.' TB.cara23idencuesta=T1.cara01id AND TB.cara23idtercero=T4.unad11id AND TB.cara23catedra_avance=T13.cara24id AND TB.cara23zonal_idlider=T51.unad11id '.$sSQLadd.'
ORDER BY TB.cara23idencuesta, TB.cara23consec';
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
			//return array(utf8_encode($sErrConsulta.'<input id="paginaf2323" name="paginaf2323" type="hidden" value="'.$pagina.'"/><input id="lppf2323" name="lppf2323" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
<td><b>'.$ETI['cara23idencuesta'].'</b></td>
<td><b>'.$ETI['cara23consec'].'</b></td>
<td colspan="2"><b>'.$ETI['cara23idtercero'].'</b></td>
<td><b>'.$ETI['cara23idtipo'].'</b></td>
<td><b>'.$ETI['cara23estado'].'</b></td>
<td><b>'.$ETI['cara23asisteinduccion'].'</b></td>
<td><b>'.$ETI['cara23asisteinmersioncv'].'</b></td>
<td><b>'.$ETI['cara23catedra_skype'].'</b></td>
<td><b>'.$ETI['cara23catedra_bler1'].'</b></td>
<td><b>'.$ETI['cara23catedra_bler2'].'</b></td>
<td><b>'.$ETI['cara23catedra_webconf'].'</b></td>
<td><b>'.$ETI['cara23catedra_avance'].'</b></td>
<td><b>'.$ETI['cara23catedra_criterio'].'</b></td>
<td><b>'.$ETI['cara23catedra_acciones'].'</b></td>
<td><b>'.$ETI['cara23catedra_resultados'].'</b></td>
<td><b>'.$ETI['cara23catedra_segprev'].'</b></td>
<td><b>'.$ETI['cara23cursos_total'].'</b></td>
<td><b>'.$ETI['cara23cursos_siningre'].'</b></td>
<td><b>'.$ETI['cara23cursos_porcing'].'</b></td>
<td><b>'.$ETI['cara23cursos_menor200'].'</b></td>
<td><b>'.$ETI['cara23cursos_porcperdida'].'</b></td>
<td><b>'.$ETI['cara23cursos_criterio'].'</b></td>
<td><b>'.$ETI['cara23cursos_otros'].'</b></td>
<td><b>'.$ETI['cara23cursos_accionlider'].'</b></td>
<td><b>'.$ETI['cara23aler_sociodem'].'</b></td>
<td><b>'.$ETI['cara23aler_psico'].'</b></td>
<td><b>'.$ETI['cara23aler_academ'].'</b></td>
<td><b>'.$ETI['cara23aler_econom'].'</b></td>
<td><b>'.$ETI['cara23aler_externo'].'</b></td>
<td><b>'.$ETI['cara23aler_interno'].'</b></td>
<td><b>'.$ETI['cara23aler_nivel'].'</b></td>
<td><b>'.$ETI['cara23aler_criterio'].'</b></td>
<td><b>'.$ETI['cara23comp_digital'].'</b></td>
<td><b>'.$ETI['cara23comp_cuanti'].'</b></td>
<td><b>'.$ETI['cara23comp_lectora'].'</b></td>
<td><b>'.$ETI['cara23comp_ingles'].'</b></td>
<td><b>'.$ETI['cara23comp_criterio'].'</b></td>
<td><b>'.$ETI['cara23nivela_digital'].'</b></td>
<td><b>'.$ETI['cara23nivela_cuanti'].'</b></td>
<td><b>'.$ETI['cara23nivela_lecto'].'</b></td>
<td><b>'.$ETI['cara23nivela_ingles'].'</b></td>
<td><b>'.$ETI['cara23nivela_exito'].'</b></td>
<td><b>'.$ETI['cara23contacto_efectivo'].'</b></td>
<td><b>'.$ETI['cara23contacto_forma'].'</b></td>
<td><b>'.$ETI['cara23contacto_observa'].'</b></td>
<td><b>'.$ETI['cara23contacto_novedad'].'</b></td>
<td><b>'.$ETI['cara23factorriesgo'].'</b></td>
<td><b>'.$ETI['cara23zonal_retro'].'</b></td>
<td><b>'.$ETI['cara23zonal_fecha'].'</b></td>
<td colspan="2"><b>'.$ETI['cara23zonal_idlider'].'</b></td>
<td align="right">
'.html_paginador('paginabusqueda', $registros, $lineastabla, $pagina, 'paginarbusqueda()').'
'.html_lpp('lppfbusqueda', $lineastabla, 'paginarbusqueda()').'
</td>
</tr>';
	$tlinea=1;
	while($filadet=$objDB->sf($tabladetalle)){
		$sPrefijo='<a href="javascript:Devuelve(\''.$filadet['cara23id'].'\');">';
		$sSufijo='</a>';
		$tlinea++;
		$et_cara23zonal_fecha='';
		if ($filadet['cara23zonal_fecha']!=0){$et_cara23zonal_fecha=fecha_desdenumero($filadet['cara23zonal_fecha']);}
		$res=$res.'<tr onmouseover="cambia_color_over(this);" onmouseout="cambia_color_out(this);">
<td>'.$sPrefijo.cadena_notildes($filadet['cara01idtercero']).$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['cara23consec'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['C4_td'].' '.$filadet['C4_doc'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['C4_nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['cara23idtipo'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['cara23estado'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['cara23asisteinduccion'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['cara23asisteinmersioncv'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['cara23catedra_skype'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['cara23catedra_bler1'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['cara23catedra_bler2'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['cara23catedra_webconf'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['cara24titulo']).$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['cara23catedra_criterio'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['cara23catedra_acciones'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['cara23catedra_resultados'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['cara23catedra_segprev'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['cara23cursos_total'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['cara23cursos_siningre'].$sSufijo.'</td>
<td align="right">'.$sPrefijo.formato_moneda($filadet['cara23cursos_porcing']).$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['cara23cursos_menor200'].$sSufijo.'</td>
<td align="right">'.$sPrefijo.formato_moneda($filadet['cara23cursos_porcperdida']).$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['cara23cursos_criterio'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['cara23cursos_otros'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['cara23cursos_accionlider'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['cara23aler_sociodem'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['cara23aler_psico'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['cara23aler_academ'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['cara23aler_econom'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['cara23aler_externo'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['cara23aler_interno'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['cara23aler_nivel'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['cara23aler_criterio'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['cara23comp_digital'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['cara23comp_cuanti'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['cara23comp_lectora'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['cara23comp_ingles'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['cara23comp_criterio'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['cara23nivela_digital'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['cara23nivela_cuanti'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['cara23nivela_lecto'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['cara23nivela_ingles'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['cara23nivela_exito'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['cara23contacto_efectivo'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['cara23contacto_forma'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['cara23contacto_observa'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['cara23contacto_novedad'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['cara23factorriesgo'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['cara23zonal_retro'].$sSufijo.'</td>
<td>'.$sPrefijo.$et_cara23zonal_fecha.$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['C51_td'].' '.$filadet['C51_doc'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['C51_nombre']).$sSufijo.'</td>
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
function f2323_TotalizarAcompanamentos($cara23idencuesta, $objDB, $bDebug=true){
	$iTotal=0;
	$sDebug='';
	$sSQL='SELECT 1 FROM cara23acompanamento WHERE cara23idencuesta='.$cara23idencuesta.'';
	$tabla=$objDB->ejecutasql($sSQL);
	$iTotal=$objDB->nf($tabla);
	$sSQL='UPDATE cara01encuesta SET cara01numacompanamentos='.$iTotal.' WHERE cara01id='.$cara23idencuesta.'';
	if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Totalizando acompanamientos '.$sSQL.'<br>';}
	$result=$objDB->ejecutasql($sSQL);
	return $sDebug;
	}
function f2323_Totalizar($cara01id, $objDB, $bDebug=false){
	$sInfo='';
	$sDebug='';
	//Traslada la informacion de los acompañamientos a la tabla de caracterizacion.
	$iFactorTotal=0;
	$iFechaCierre=0;
	$sSQL='SELECT cara23factorriesgo, cara23idtipo, cara23fecha FROM cara23acompanamento WHERE cara23idencuesta='.$cara01id.' AND cara23estado=7 ORDER BY cara23idtipo DESC, cara23consec DESC LIMIT 0, 1';
	if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Trayendo los acompanamientos: '.$sSQL.'<br>';}
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$iFactorTotal=$fila['cara23factorriesgo'];
		if ($fila['cara23idtipo']==3){
			$iFechaCierre=$fila['cara23fecha'];
			}
		}
	$sSQL='UPDATE cara01encuesta SET cara01factorriesgoacomp='.$iFactorTotal.', cara01fechacierreacom='.$iFechaCierre.' WHERE cara01id='.$cara01id.'';
	if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Actualizando factor de riesgo: '.$sSQL.'<br>';}
	$result=$objDB->ejecutasql($sSQL);
	return array($sInfo, $sDebug);
	}
?>