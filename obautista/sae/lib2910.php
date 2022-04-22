<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2019 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.23.6 Wednesday, October 9, 2019
--- 2910 plab10oferta
*/
/** Archivo lib2910.php.
* Libreria 2910 plab10oferta.
* @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
* @date Wednesday, October 9, 2019
*/
function f2910_HTMLComboV2_plab10emprbolsempleo($objDB, $objCombos, $valor){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('plab10emprbolsempleo', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	$objCombos->sAccion='RevisaLlave();';
	$sSQL='SELECT plab08id AS id, plab08nombre AS nombre FROM plab08emprbolsempleo';
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f2910_HTMLComboV2_plab10ubidep($objDB, $objCombos, $valor, $vrplab10ubipais){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	//@@ Se debe arreglar la condicion..
	$sCondi='unad19codpais="'.$vrplab10ubipais.'"';
	if ($sCondi!=''){$sCondi=' WHERE '.$sCondi;}
	$objCombos->nuevo('plab10ubidep', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	$objCombos->sAccion='carga_combo_plab10ubiciu()';
	$sSQL='SELECT unad19codigo AS id, unad19nombre AS nombre FROM unad19depto'.$sCondi;
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f2910_HTMLComboV2_plab10ubiciu($objDB, $objCombos, $valor, $vrplab10ubidep){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	//@@ Se debe arreglar la condicion..
	$sCondi='unad20coddepto="'.$vrplab10ubidep.'"';
	if ($sCondi!=''){$sCondi=' WHERE '.$sCondi;}
	$objCombos->nuevo('plab10ubiciu', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	$sSQL='SELECT unad20codigo AS id, unad20nombre AS nombre FROM unad20ciudad'.$sCondi;
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f2910_Comboplab10ubidep($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$objCombos=new clsHtmlCombos('n');
	$html_plab10ubidep=f2910_HTMLComboV2_plab10ubidep($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_plab10ubidep', 'innerHTML', $html_plab10ubidep);
	return $objResponse;
	}
function f2910_Comboplab10ubiciu($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$objCombos=new clsHtmlCombos('n');
	$html_plab10ubiciu=f2910_HTMLComboV2_plab10ubiciu($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_plab10ubiciu', 'innerHTML', $html_plab10ubiciu);
	return $objResponse;
	}
function f2910_ExisteDato($datos){
	if(!is_array($datos)){$datos=json_decode(str_replace('\"','"',$datos),true);}
	$_SESSION['u_ultimominuto']=iminutoavance();
	$bHayLlave=true;
	$plab10emprbolsempleo=numeros_validar($datos[1]);
	if ($plab10emprbolsempleo==''){$bHayLlave=false;}
	$plab10consecutivo=numeros_validar($datos[2]);
	if ($plab10consecutivo==''){$bHayLlave=false;}
	if ($bHayLlave){
		require './app.php';
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		$sSQL='SELECT plab10consecutivo FROM plab10oferta WHERE plab10emprbolsempleo='.$plab10emprbolsempleo.' AND plab10consecutivo='.$plab10consecutivo.'';
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
function f2910_Busquedas($aParametros){
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2910='lg/lg_2910_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2910)){$mensajes_2910='lg/lg_2910_es.php';}
	require $mensajes_todas;
	require $mensajes_2910;
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
		}
	$sTitulo='<h2>'.$ETI['titulo_2910'].' - '.$sTitulo.'</h2>';
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97titulo', 'innerHTML', $sTitulo);
	$objResponse->assign('div_97params', 'innerHTML', $sParams);
	$objResponse->assign('div_97tabla', 'innerHTML', $sTabla);
	$objResponse->setFunction('paginarbusqueda','',$sJavaBusqueda);
	$objResponse->call('expandesector(97)');
	return $objResponse;
	}
function f2910_HtmlBusqueda($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$sError='';
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$sDetalle='';
	switch($aParametros[100]){
		}
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97tabla', 'innerHTML', $sDetalle);
	return $objResponse;
	}
function f2910_TablaDetalleV2($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2910='lg/lg_2910_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2910)){$mensajes_2910='lg/lg_2910_es.php';}
	require $mensajes_todas;
	require $mensajes_2910;
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
	if ($sLeyenda!=''){
		$sLeyenda='<div class="salto1px"></div>
<div class="GrupoCamposAyuda">
'.$sLeyenda.'
<div class="salto1px"></div>
</div>';
		return array($sLeyenda.'<input id="paginaf2910" name="paginaf2910" type="hidden" value="'.$pagina.'"/><input id="lppf2910" name="lppf2910" type="hidden" value="'.$lineastabla.'"/>', $sDebug);
		die();
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
	$sTitulos='Emprbolsempleo, Consecutivo, Id, Refoferta, Empresa, Titofer, Ubicacion, Ubipais, Ubidep, Ubiciu, Fechapub, Tipocont, Estaprob, Rangsala, Segmento, Totalapli, Numvac, Profesion';
	$sSQL='SELECT T1.plab08nombre, TB.plab10consecutivo, TB.plab10id, TB.plab10refoferta, T5.plab09nombre, TB.plab10titofer, TB.plab10ubicacion, TB.plab10ubipais, T9.unad19nombre, T10.unad20nombre, TB.plab10fechapub, T12.plab11nombre, T13.plab12nombre, T14.plab03nombre, T15.plab13nombre, TB.plab10totalapli, TB.plab10numvac, T18.plab02nombre, TB.plab10emprbolsempleo, TB.plab10empresa, TB.plab10ubidep, TB.plab10ubiciu, TB.plab10tipocont, TB.plab10estaprob, TB.plab10rangsala, TB.plab10segmento, TB.plab10profesion 
FROM plab10oferta AS TB, plab08emprbolsempleo AS T1, plab09empresa AS T5, unad19depto AS T9, unad20ciudad AS T10, plab11tipocont AS T12, plab12estaprob AS T13, plab03rangsala AS T14, plab13segmento AS T15, plab02prof AS T18 
WHERE '.$sSQLadd1.' TB.plab10emprbolsempleo=T1.plab08id AND TB.plab10empresa=T5.plab09id AND TB.plab10ubidep=T9.unad19codigo AND TB.plab10ubiciu=T10.unad20codigo AND TB.plab10tipocont=T12.plab11id AND TB.plab10estaprob=T13.plab12id AND TB.plab10rangsala=T14.plab03id AND TB.plab10segmento=T15.plab13id AND TB.plab10profesion=T18.plab02id '.$sSQLadd.'
ORDER BY TB.plab10emprbolsempleo, TB.plab10consecutivo';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_2910" name="consulta_2910" type="hidden" value="'.$sSQLlista.'"/>
<input id="titulos_2910" name="titulos_2910" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objDB->ejecutasql($sSQL);
	if ($tabladetalle==false){
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 2910: '.$sSQL.'<br>';}
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		//$sLeyenda=$sSQL;
		}else{
		$registros=$objDB->nf($tabladetalle);
		if ($registros==0){
			//return array(utf8_encode($sErrConsulta.'<input id="paginaf2910" name="paginaf2910" type="hidden" value="'.$pagina.'"/><input id="lppf2910" name="lppf2910" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
<td><b>'.$ETI['plab10emprbolsempleo'].'</b></td>
<td><b>'.$ETI['plab10consecutivo'].'</b></td>
<td><b>'.$ETI['plab10refoferta'].'</b></td>
<td><b>'.$ETI['plab10empresa'].'</b></td>
<td><b>'.$ETI['plab10titofer'].'</b></td>
<td><b>'.$ETI['plab10ubicacion'].'</b></td>
<td><b>'.$ETI['plab10ubipais'].'</b></td>
<td><b>'.$ETI['plab10ubidep'].'</b></td>
<td><b>'.$ETI['plab10ubiciu'].'</b></td>
<td><b>'.$ETI['plab10fechapub'].'</b></td>
<td><b>'.$ETI['plab10tipocont'].'</b></td>
<td><b>'.$ETI['plab10estaprob'].'</b></td>
<td><b>'.$ETI['plab10rangsala'].'</b></td>
<td><b>'.$ETI['plab10segmento'].'</b></td>
<td><b>'.$ETI['plab10totalapli'].'</b></td>
<td><b>'.$ETI['plab10numvac'].'</b></td>
<td><b>'.$ETI['plab10profesion'].'</b></td>
<td align="right">
'.html_paginador('paginaf2910', $registros, $lineastabla, $pagina, 'paginarf2910()').'
'.html_lpp('lppf2910', $lineastabla, 'paginarf2910()').'
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
		$et_plab10fechapub='';
		if ($filadet['plab10fechapub']!=0){$et_plab10fechapub=fecha_desdenumero($filadet['plab10fechapub']);}
		if ($babierta){
			$sLink='<a href="javascript:cargaridf2910('.$filadet['plab10id'].')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$res=$res.'<tr'.$sClass.'>
<td>'.$sPrefijo.cadena_notildes($filadet['plab08nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['plab10consecutivo'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['plab10refoferta']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['plab09nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['plab10titofer']).$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['plab10ubicacion'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['plab10ubipais']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['plab10ubidep']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['plab10ubiciu']).$sSufijo.'</td>
<td>'.$sPrefijo.$et_plab10fechapub.$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['plab11nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['plab12nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['plab03nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['plab13nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['plab10totalapli'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['plab10numvac'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['plab02nombre']).$sSufijo.'</td>
<td>'.$sLink.'</td>
</tr>';
		}
	$res=$res.'</table>';
	$objDB->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
	}
function f2910_HtmlTabla($aParametros){
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
	list($sDetalle, $sDebugTabla)=f2910_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f2910detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f2910_db_CargarPadre($DATA, $objDB, $bDebug=false){
	$sError='';
	$iTipoError=0;
	$sDebug='';
	require './app.php';
	if ($DATA['paso']==1){
		$sSQLcondi='plab10emprbolsempleo='.$DATA['plab10emprbolsempleo'].' AND plab10consecutivo='.$DATA['plab10consecutivo'].'';
		}else{
		$sSQLcondi='plab10id='.$DATA['plab10id'].'';
		}
	$sSQL='SELECT * FROM plab10oferta WHERE '.$sSQLcondi;
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$DATA['plab10emprbolsempleo']=$fila['plab10emprbolsempleo'];
		$DATA['plab10consecutivo']=$fila['plab10consecutivo'];
		$DATA['plab10id']=$fila['plab10id'];
		$DATA['plab10refoferta']=$fila['plab10refoferta'];
		$DATA['plab10empresa']=$fila['plab10empresa'];
		$DATA['plab10titofer']=$fila['plab10titofer'];
		$DATA['plab10ubicacion']=$fila['plab10ubicacion'];
		$DATA['plab10ubipais']=$fila['plab10ubipais'];
		$DATA['plab10ubidep']=$fila['plab10ubidep'];
		$DATA['plab10ubiciu']=$fila['plab10ubiciu'];
		$DATA['plab10fechapub']=$fila['plab10fechapub'];
		$DATA['plab10tipocont']=$fila['plab10tipocont'];
		$DATA['plab10estaprob']=$fila['plab10estaprob'];
		$DATA['plab10rangsala']=$fila['plab10rangsala'];
		$DATA['plab10segmento']=$fila['plab10segmento'];
		$DATA['plab10totalapli']=$fila['plab10totalapli'];
		$DATA['plab10numvac']=$fila['plab10numvac'];
		$DATA['plab10profesion']=$fila['plab10profesion'];
		$bcargo=true;
		$DATA['paso']=2;
		$DATA['boculta2910']=0;
		$bLimpiaHijos=true;
		}else{
		$DATA['paso']=0;
		}
	return array($DATA, $sError, $iTipoError, $sDebug);
	}
function f2910_db_GuardarV2($DATA, $objDB, $bDebug=false){
	$iCodModulo=2910;
	$bAudita[2]=true;
	$bAudita[3]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2910='lg/lg_2910_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2910)){$mensajes_2910='lg/lg_2910_es.php';}
	require $mensajes_todas;
	require $mensajes_2910;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	// -- Se inicia validando todas las posibles entradas de usuario.
	/*
	if (isset($DATA['plab10emprbolsempleo'])==0){$DATA['plab10emprbolsempleo']='';}
	if (isset($DATA['plab10consecutivo'])==0){$DATA['plab10consecutivo']='';}
	if (isset($DATA['plab10id'])==0){$DATA['plab10id']='';}
	if (isset($DATA['plab10refoferta'])==0){$DATA['plab10refoferta']='';}
	if (isset($DATA['plab10empresa'])==0){$DATA['plab10empresa']='';}
	if (isset($DATA['plab10titofer'])==0){$DATA['plab10titofer']='';}
	if (isset($DATA['plab10ubicacion'])==0){$DATA['plab10ubicacion']='';}
	if (isset($DATA['plab10ubipais'])==0){$DATA['plab10ubipais']='';}
	if (isset($DATA['plab10ubidep'])==0){$DATA['plab10ubidep']='';}
	if (isset($DATA['plab10ubiciu'])==0){$DATA['plab10ubiciu']='';}
	if (isset($DATA['plab10fechapub'])==0){$DATA['plab10fechapub']='';}
	if (isset($DATA['plab10tipocont'])==0){$DATA['plab10tipocont']='';}
	if (isset($DATA['plab10estaprob'])==0){$DATA['plab10estaprob']='';}
	if (isset($DATA['plab10rangsala'])==0){$DATA['plab10rangsala']='';}
	if (isset($DATA['plab10segmento'])==0){$DATA['plab10segmento']='';}
	if (isset($DATA['plab10totalapli'])==0){$DATA['plab10totalapli']='';}
	if (isset($DATA['plab10numvac'])==0){$DATA['plab10numvac']='';}
	if (isset($DATA['plab10profesion'])==0){$DATA['plab10profesion']='';}
	*/
	$DATA['plab10emprbolsempleo']=numeros_validar($DATA['plab10emprbolsempleo']);
	$DATA['plab10consecutivo']=numeros_validar($DATA['plab10consecutivo']);
	$DATA['plab10refoferta']=htmlspecialchars(trim($DATA['plab10refoferta']));
	$DATA['plab10empresa']=numeros_validar($DATA['plab10empresa']);
	$DATA['plab10titofer']=htmlspecialchars(trim($DATA['plab10titofer']));
	$DATA['plab10ubicacion']=htmlspecialchars(trim($DATA['plab10ubicacion']));
	$DATA['plab10ubipais']=htmlspecialchars(trim($DATA['plab10ubipais']));
	$DATA['plab10ubidep']=htmlspecialchars(trim($DATA['plab10ubidep']));
	$DATA['plab10ubiciu']=htmlspecialchars(trim($DATA['plab10ubiciu']));
	$DATA['plab10tipocont']=numeros_validar($DATA['plab10tipocont']);
	$DATA['plab10estaprob']=numeros_validar($DATA['plab10estaprob']);
	$DATA['plab10rangsala']=numeros_validar($DATA['plab10rangsala']);
	$DATA['plab10segmento']=numeros_validar($DATA['plab10segmento']);
	$DATA['plab10totalapli']=numeros_validar($DATA['plab10totalapli']);
	$DATA['plab10numvac']=numeros_validar($DATA['plab10numvac']);
	$DATA['plab10profesion']=numeros_validar($DATA['plab10profesion']);
	// -- Se inicializan las variables que puedan pasar vacias {Especialmente números}.
	//if ($DATA['plab10empresa']==''){$DATA['plab10empresa']=0;}
	//if ($DATA['plab10tipocont']==''){$DATA['plab10tipocont']=0;}
	//if ($DATA['plab10estaprob']==''){$DATA['plab10estaprob']=0;}
	//if ($DATA['plab10rangsala']==''){$DATA['plab10rangsala']=0;}
	//if ($DATA['plab10segmento']==''){$DATA['plab10segmento']=0;}
	//if ($DATA['plab10totalapli']==''){$DATA['plab10totalapli']=0;}
	//if ($DATA['plab10numvac']==''){$DATA['plab10numvac']=0;}
	//if ($DATA['plab10profesion']==''){$DATA['plab10profesion']=0;}
	// -- Seccion para validar los posibles causales de error.
	$sSepara=', ';
	if (true){
		if ($DATA['plab10profesion']==''){$sError=$ERR['plab10profesion'].$sSepara.$sError;}
		if ($DATA['plab10numvac']==''){$sError=$ERR['plab10numvac'].$sSepara.$sError;}
		if ($DATA['plab10totalapli']==''){$sError=$ERR['plab10totalapli'].$sSepara.$sError;}
		if ($DATA['plab10segmento']==''){$sError=$ERR['plab10segmento'].$sSepara.$sError;}
		if ($DATA['plab10rangsala']==''){$sError=$ERR['plab10rangsala'].$sSepara.$sError;}
		if ($DATA['plab10estaprob']==''){$sError=$ERR['plab10estaprob'].$sSepara.$sError;}
		if ($DATA['plab10tipocont']==''){$sError=$ERR['plab10tipocont'].$sSepara.$sError;}
		if ($DATA['plab10fechapub']==0){
			//$DATA['plab10fechapub']=fecha_DiaMod();
			$sError=$ERR['plab10fechapub'].$sSepara.$sError;
			}
		if ($DATA['plab10ubiciu']==''){$sError=$ERR['plab10ubiciu'].$sSepara.$sError;}
		if ($DATA['plab10ubidep']==''){$sError=$ERR['plab10ubidep'].$sSepara.$sError;}
		if ($DATA['plab10ubipais']==''){$sError=$ERR['plab10ubipais'].$sSepara.$sError;}
		//if ($DATA['plab10ubicacion']==''){$sError=$ERR['plab10ubicacion'].$sSepara.$sError;}
		if ($DATA['plab10titofer']==''){$sError=$ERR['plab10titofer'].$sSepara.$sError;}
		if ($DATA['plab10empresa']==''){$sError=$ERR['plab10empresa'].$sSepara.$sError;}
		if ($DATA['plab10refoferta']==''){$sError=$ERR['plab10refoferta'].$sSepara.$sError;}
		//Fin de las valiaciones NO LLAVE.
		}
	//Valiaciones de campos obligatorios en todo guardar.
	if ($DATA['plab10emprbolsempleo']==''){$sError=$ERR['plab10emprbolsempleo'];}
	// -- Se verifican los valores de campos de otras tablas.
	if ($sError==''){
		if ($DATA['paso']==10){
			if ($DATA['plab10consecutivo']==''){
				$DATA['plab10consecutivo']=tabla_consecutivo('plab10oferta', 'plab10consecutivo', 'plab10emprbolsempleo='.$DATA['plab10emprbolsempleo'].'', $objDB);
				if ($DATA['plab10consecutivo']==-1){$sError=$objDB->serror;}
				}else{
				if (!seg_revisa_permiso($iCodModulo, 8, $objDB)){
					$sError=$ERR['8'];
					$DATA['plab10consecutivo']='';
					}
				}
			if ($sError==''){
				$sSQL='SELECT 1 FROM plab10oferta WHERE plab10emprbolsempleo='.$DATA['plab10emprbolsempleo'].' AND plab10consecutivo='.$DATA['plab10consecutivo'].'';
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
			$DATA['plab10id']=tabla_consecutivo('plab10oferta','plab10id', '', $objDB);
			if ($DATA['plab10id']==-1){$sError=$objDB->serror;}
			}
		}
	if ($sError==''){
		if (get_magic_quotes_gpc()==1){$DATA['plab10ubicacion']=stripslashes($DATA['plab10ubicacion']);}
		//Si el campo plab10ubicacion permite html quite la linea htmlspecialchars para el campo y habilite la siguiente linea:
		//$plab10ubicacion=addslashes($DATA['plab10ubicacion']);
		$plab10ubicacion=str_replace('"', '\"', $DATA['plab10ubicacion']);
		$bpasa=false;
		if ($DATA['paso']==10){
			$plab10fechapub=fecha_DiaMod();
			$sCampos2910='plab10emprbolsempleo, plab10consecutivo, plab10id, plab10refoferta, plab10empresa, plab10titofer, plab10ubicacion, plab10ubipais, plab10ubidep, plab10ubiciu, 
plab10fechapub, plab10tipocont, plab10estaprob, plab10rangsala, plab10segmento, plab10totalapli, plab10numvac, plab10profesion';
			$sValores2910=''.$DATA['plab10emprbolsempleo'].', '.$DATA['plab10consecutivo'].', '.$DATA['plab10id'].', "'.$DATA['plab10refoferta'].'", '.$DATA['plab10empresa'].', "'.$DATA['plab10titofer'].'", "'.$plab10ubicacion.'", "'.$DATA['plab10ubipais'].'", "'.$DATA['plab10ubidep'].'", "'.$DATA['plab10ubiciu'].'", 
"'.$DATA['plab10fechapub'].'", '.$DATA['plab10tipocont'].', '.$DATA['plab10estaprob'].', '.$DATA['plab10rangsala'].', '.$DATA['plab10segmento'].', '.$DATA['plab10totalapli'].', '.$DATA['plab10numvac'].', '.$DATA['plab10profesion'].'';
			if ($APP->utf8==1){
				$sSQL='INSERT INTO plab10oferta ('.$sCampos2910.') VALUES ('.utf8_encode($sValores2910).');';
				$sdetalle=$sCampos2910.'['.utf8_encode($sValores2910).']';
				}else{
				$sSQL='INSERT INTO plab10oferta ('.$sCampos2910.') VALUES ('.$sValores2910.');';
				$sdetalle=$sCampos2910.'['.$sValores2910.']';
				}
			$idaccion=2;
			$bpasa=true;
			}else{
			$scampo[1]='plab10refoferta';
			$scampo[2]='plab10empresa';
			$scampo[3]='plab10titofer';
			$scampo[4]='plab10ubicacion';
			$scampo[5]='plab10ubipais';
			$scampo[6]='plab10ubidep';
			$scampo[7]='plab10ubiciu';
			$scampo[8]='plab10fechapub';
			$scampo[9]='plab10tipocont';
			$scampo[10]='plab10estaprob';
			$scampo[11]='plab10rangsala';
			$scampo[12]='plab10segmento';
			$scampo[13]='plab10totalapli';
			$scampo[14]='plab10numvac';
			$scampo[15]='plab10profesion';
			$sdato[1]=$DATA['plab10refoferta'];
			$sdato[2]=$DATA['plab10empresa'];
			$sdato[3]=$DATA['plab10titofer'];
			$sdato[4]=$plab10ubicacion;
			$sdato[5]=$DATA['plab10ubipais'];
			$sdato[6]=$DATA['plab10ubidep'];
			$sdato[7]=$DATA['plab10ubiciu'];
			$sdato[8]=$DATA['plab10fechapub'];
			$sdato[9]=$DATA['plab10tipocont'];
			$sdato[10]=$DATA['plab10estaprob'];
			$sdato[11]=$DATA['plab10rangsala'];
			$sdato[12]=$DATA['plab10segmento'];
			$sdato[13]=$DATA['plab10totalapli'];
			$sdato[14]=$DATA['plab10numvac'];
			$sdato[15]=$DATA['plab10profesion'];
			$numcmod=15;
			$sWhere='plab10id='.$DATA['plab10id'].'';
			$sSQL='SELECT * FROM plab10oferta WHERE '.$sWhere;
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
					$sSQL='UPDATE plab10oferta SET '.utf8_encode($sdatos).' WHERE '.$sWhere.';';
					}else{
					$sdetalle=$sdatos.'['.$sWhere.']';
					$sSQL='UPDATE plab10oferta SET '.$sdatos.' WHERE '.$sWhere.';';
					}
				$idaccion=3;
				}
			}
		if ($bpasa){
			$result=$objDB->ejecutasql($sSQL);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' [2910] ..<!-- '.$sSQL.' -->';
				if ($idaccion==2){$DATA['plab10id']='';}
				$DATA['paso']=$DATA['paso']-10;
				}else{
				if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Guardar 2910 '.$sSQL.'<br>';}
				if ($bAudita[$idaccion]){seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], $idaccion, $DATA['plab10id'], $sdetalle, $objDB);}
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
function f2910_db_Eliminar($plab10id, $objDB, $bDebug=false){
	$iCodModulo=2910;
	$bAudita[4]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2910='lg/lg_2910_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2910)){$mensajes_2910='lg/lg_2910_es.php';}
	require $mensajes_todas;
	require $mensajes_2910;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	$plab10id=numeros_validar($plab10id);
	// Traer los datos para hacer las validaciones.
	if ($sError==''){
		$sSQL='SELECT * FROM plab10oferta WHERE plab10id='.$plab10id.'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$filabase=$objDB->sf($tabla);
			}else{
			$sError='No se encuentra el registro solicitado {Ref: '.$plab10id.'}';
			}
		}
	if ($sError==''){
		$sSQL='SELECT plab14oferta FROM plab14aplicaofer WHERE plab14oferta='.$filabase['plab10id'].'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$sError='Existen aplicacion a oferta creados, no es posible eliminar';
			}
		}
	if ($sError==''){
		if (!seg_revisa_permiso($iCodModulo, 4, $objDB)){
			$sError=$ERR['4'];
			}
		}
	if ($sError==''){
		$sSQL='SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=2910';
		$tablaor=$objDB->ejecutasql($sSQL);
		while ($filaor=$objDB->sf($tablaor)){
			$sSQL='SELECT '.$filaor['campoid'].' FROM '.$filaor['tabla'].' WHERE '.$filaor['camporev'].'='.$_REQUEST['plab10id'].' LIMIT 0, 1';
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
		//$sSQL='DELETE FROM plab14aplicaofer WHERE plab14oferta='.$filabase['plab10id'].'';
		//$tabla=$objDB->ejecutasql($sSQL);
		$sWhere='plab10id='.$plab10id.'';
		//$sWhere='plab10consecutivo='.$filabase['plab10consecutivo'].' AND plab10emprbolsempleo='.$filabase['plab10emprbolsempleo'].'';
		$sSQL='DELETE FROM plab10oferta WHERE '.$sWhere.';';
		$result=$objDB->ejecutasql($sSQL);
		if ($result==false){
			$sError=$ERR['falla_eliminar'].' .. <!-- '.$sSQL.' -->';
			}else{
			if ($bAudita[4]){seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $plab10id, $sWhere, $objDB);}
			}
		}
	return array($sError, $iTipoError, $sDebug);
	}
function f2910_TituloBusqueda(){
	return 'Busqueda de ofertas';
	}
function f2910_ParametrosBusqueda(){
	$sParams='<label class="Label90">Nombre</label><label><input id="b2910nombre" name="b2910nombre" type="text" value="" onchange="paginarbusqueda()" /></label>';
	return $sParams;
	}
function f2910_JavaScriptBusqueda($iModuloBusca){
	$sRes='var sCampo=window.document.frmedita.scampobusca.value;
var params=new Array();
params[100]=sCampo;
params[101]=window.document.frmedita.paginabusqueda.value;
params[102]=window.document.frmedita.lppfbusqueda.value;
params[103]=window.document.frmedita.b2910nombre.value;
xajax_f'.$iModuloBusca.'_HtmlBusqueda(params);';
	return $sRes;
	}
function f2910_TablaDetalleBusquedas($aParametros, $objDB){
	$res='';
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2910='lg/lg_2910_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2910)){$mensajes_2910='lg/lg_2910_es.php';}
	require $mensajes_todas;
	require $mensajes_2910;
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
		return array($sLeyenda.'<input id="paginaf2910" name="paginaf2910" type="hidden" value="'.$pagina.'"/><input id="lppf2910" name="lppf2910" type="hidden" value="'.$lineastabla.'"/>', $sDebug);
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
	$sTitulos='Emprbolsempleo, Consecutivo, Id, Refoferta, Empresa, Titofer, Ubicacion, Ubipais, Ubidep, Ubiciu, Fechapub, Tipocont, Estaprob, Rangsala, Segmento, Totalapli, Numvac, Profesion';
	$sSQL='SELECT T1.plab08nombre, TB.plab10consecutivo, TB.plab10id, TB.plab10refoferta, T5.plab09nombre, TB.plab10titofer, TB.plab10ubicacion, TB.plab10ubipais, T9.unad19nombre, T10.unad20nombre, TB.plab10fechapub, T12.plab11nombre, T13.plab12nombre, T14.plab03nombre, T15.plab13nombre, TB.plab10totalapli, TB.plab10numvac, T18.plab02nombre, TB.plab10emprbolsempleo, TB.plab10empresa, TB.plab10ubidep, TB.plab10ubiciu, TB.plab10tipocont, TB.plab10estaprob, TB.plab10rangsala, TB.plab10segmento, TB.plab10profesion 
FROM plab10oferta AS TB, plab08emprbolsempleo AS T1, plab09empresa AS T5, unad19depto AS T9, unad20ciudad AS T10, plab11tipocont AS T12, plab12estaprob AS T13, plab03rangsala AS T14, plab13segmento AS T15, plab02prof AS T18 
WHERE '.$sSQLadd1.' TB.plab10emprbolsempleo=T1.plab08id AND TB.plab10empresa=T5.plab09id AND TB.plab10ubidep=T9.unad19codigo AND TB.plab10ubiciu=T10.unad20codigo AND TB.plab10tipocont=T12.plab11id AND TB.plab10estaprob=T13.plab12id AND TB.plab10rangsala=T14.plab03id AND TB.plab10segmento=T15.plab13id AND TB.plab10profesion=T18.plab02id '.$sSQLadd.'
ORDER BY TB.plab10emprbolsempleo, TB.plab10consecutivo';
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
			//return array(utf8_encode($sErrConsulta.'<input id="paginaf2910" name="paginaf2910" type="hidden" value="'.$pagina.'"/><input id="lppf2910" name="lppf2910" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
<td><b>'.$ETI['plab10emprbolsempleo'].'</b></td>
<td><b>'.$ETI['plab10consecutivo'].'</b></td>
<td><b>'.$ETI['plab10refoferta'].'</b></td>
<td><b>'.$ETI['plab10empresa'].'</b></td>
<td><b>'.$ETI['plab10titofer'].'</b></td>
<td><b>'.$ETI['plab10ubicacion'].'</b></td>
<td><b>'.$ETI['plab10ubipais'].'</b></td>
<td><b>'.$ETI['plab10ubidep'].'</b></td>
<td><b>'.$ETI['plab10ubiciu'].'</b></td>
<td><b>'.$ETI['plab10fechapub'].'</b></td>
<td><b>'.$ETI['plab10tipocont'].'</b></td>
<td><b>'.$ETI['plab10estaprob'].'</b></td>
<td><b>'.$ETI['plab10rangsala'].'</b></td>
<td><b>'.$ETI['plab10segmento'].'</b></td>
<td><b>'.$ETI['plab10totalapli'].'</b></td>
<td><b>'.$ETI['plab10numvac'].'</b></td>
<td><b>'.$ETI['plab10profesion'].'</b></td>
<td align="right">
'.html_paginador('paginabusqueda', $registros, $lineastabla, $pagina, 'paginarbusqueda()').'
'.html_lpp('lppfbusqueda', $lineastabla, 'paginarbusqueda()').'
</td>
</tr>';
	$tlinea=1;
	while($filadet=$objDB->sf($tabladetalle)){
		$sPrefijo='<a href="javascript:Devuelve(\''.$filadet['plab10id'].'\');">';
		$sSufijo='</a>';
		$tlinea++;
		$et_plab10fechapub='';
		if ($filadet['plab10fechapub']!=0){$et_plab10fechapub=fecha_desdenumero($filadet['plab10fechapub']);}
		$res=$res.'<tr onmouseover="cambia_color_over(this);" onmouseout="cambia_color_out(this);">
<td>'.$sPrefijo.cadena_notildes($filadet['plab08nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['plab10consecutivo'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['plab10refoferta']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['plab09nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['plab10titofer']).$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['plab10ubicacion'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['plab10ubipais'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['plab10ubidep'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['plab10ubiciu'].$sSufijo.'</td>
<td>'.$sPrefijo.$et_plab10fechapub.$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['plab11nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['plab12nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['plab03nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['plab13nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['plab10totalapli'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['plab10numvac'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['plab02nombre']).$sSufijo.'</td>
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