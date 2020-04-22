<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2018 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.22.3 lunes, 27 de agosto de 2018
--- 2319 cara19rpttercero
*/
function f2319_Busquedas($aParametros){
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2319='lg/lg_2319_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2319)){$mensajes_2319='lg/lg_2319_es.php';}
	require $mensajes_todas;
	require $mensajes_2319;
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
		case 'caraidtercero':
		require $APP->rutacomun.'lib111.php';
		$sTabla=f111_TablaDetalleBusquedas($aParametrosB, $objDB);
		$sTitulo='Busqueda de terceros';
		$sParams=f111_ParametrosBusqueda();
		$sJavaBusqueda=f111_JavaScriptBusqueda(2319);
		break;
		}
	$sTitulo='<h2>'.$ETI['titulo_2319'].' - '.$sTitulo.'</h2>';
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97titulo', 'innerHTML', $sTitulo);
	$objResponse->assign('div_97params', 'innerHTML', $sParams);
	$objResponse->assign('div_97tabla', 'innerHTML', $sTabla);
	$objResponse->setFunction('paginarbusqueda','',$sJavaBusqueda);
	$objResponse->call('expandesector(97)');
	return $objResponse;
	}
function f2319_HtmlBusqueda($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$sError='';
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$sDetalle='';
	switch($aParametros[100]){
		case 'caraidtercero':
		require $APP->rutacomun.'lib111.php';
		$sDetalle=f111_TablaDetalleBusquedas($aParametros, $objDB);
		break;
		}
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97tabla', 'innerHTML', $sDetalle);
	return $objResponse;
	}

function f2318_TablaDetalleV2($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2319='lg/lg_2319_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2319)){$mensajes_2319='lg/lg_2319_es.php';}
	require $mensajes_todas;
	require $mensajes_2319;
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	if (isset($aParametros[103])==0){$aParametros[103]='';}
	$aParametros[103]=numeros_validar($aParametros[103]);
	if ($aParametros[103]==''){$aParametros[103]=0;}
	$sDebug='';
	$babierta=false;
	//$sSQL='SELECT Campo FROM Tabla WHERE Id='.$sValorId;
	//$tabla=$objDB->ejecutasql($sSQL);
	//if ($objDB->nf($tabla)>0){
		//$fila=$objDB->sf($tabla);
		//if ($fila['Campo']!='S'){$babierta=true;}
		//}
	$sLeyenda='';
	if ($aParametros[103]==0){
		$sLeyenda='';
		return array(utf8_encode($sLeyenda.''), $sDebug);
		}
	$idTercero=$aParametros[103];
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
				$sSQLadd=$sSQLadd.' AND T6.sys11razonsocial LIKE "%'.$sCadena.'%"';
				//$sSQLadd1=$sSQLadd1.'T1.sys11razonsocial LIKE "%'.$sCadena.'%" AND ';
				}
			}
		}
	*/
	$sTitulos='Tercero';
	$res='&Uacute;ltimo acceso a Campus: ';
	$sSQL='SELECT TB.unad11fechaultingreso, TB.unad11telefono, TB.unad11correo 
FROM unad11terceros AS TB 
WHERE TB.unad11id='.$idTercero.'';
	$tabladetalle=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabladetalle)>0){
		$fila=$objDB->sf($tabladetalle);
		$sCorreo='';
		if ($fila['unad11fechaultingreso']==0){
			$sCorreo='<br>Correo <b>'.$fila['unad11correo'].'</b>';
			}
		$res=$res.' <b>'.formato_FechaLargaDesdeNumero($fila['unad11fechaultingreso'], true).'</b><br>
Tel&eacute;fono <b>'.$fila['unad11telefono'].'</b>'.$sCorreo.'';
		}else{
		$res=$res.' <span class="rojo">NO REGISTRA</span>';
		}
	//$res=$res.'';
	$objDB->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
	}
function f2318_HtmlTabla($aParametros){
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
	list($sDetalle, $sDebugTabla)=f2318_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f2318detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}

function f2319_TablaDetalleV2($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2319='lg/lg_2319_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2319)){$mensajes_2319='lg/lg_2319_es.php';}
	require $mensajes_todas;
	require $mensajes_2319;
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	if (isset($aParametros[101])==0){$aParametros[101]=1;}
	if (isset($aParametros[102])==0){$aParametros[102]=20;}
	if (isset($aParametros[103])==0){$aParametros[103]='';}
	$aParametros[103]=numeros_validar($aParametros[103]);
	if ($aParametros[103]==''){$aParametros[103]=0;}
	$sDebug='';
	$pagina=$aParametros[101];
	$lineastabla=$aParametros[102];
	$babierta=false;
	//$sSQL='SELECT Campo FROM Tabla WHERE Id='.$sValorId;
	//$tabla=$objDB->ejecutasql($sSQL);
	//if ($objDB->nf($tabla)>0){
		//$fila=$objDB->sf($tabla);
		//if ($fila['Campo']!='S'){$babierta=true;}
		//}
	$sLeyenda='';
	if ($aParametros[103]==0){
		$sLeyenda='<div class="salto1px"></div>
<div class="GrupoCamposAyuda">
<b>No ha sido seleccionado un documento a consultar.</b>
<div class="salto1px"></div>
</div>';
		return array(utf8_encode($sLeyenda.'<input id="paginaf2319" name="paginaf2319" type="hidden" value="'.$pagina.'"/><input id="lppf2319" name="lppf2319" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
		}
	$idTercero=$aParametros[103];
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
				$sSQLadd=$sSQLadd.' AND T6.sys11razonsocial LIKE "%'.$sCadena.'%"';
				//$sSQLadd1=$sSQLadd1.'T1.sys11razonsocial LIKE "%'.$sCadena.'%" AND ';
				}
			}
		}
	*/
	$sTitulos='Tercero';
	$sSQL='SELECT TB.core16id, TB.core16idcead, TB.core16parametros, TB.core16fecharecibido, TB.core16minrecibido, TB.core16procesado, TB.core16numcursos, TB.core16numaprobados, TB.core16promedio, TB.core16origen, TB.core16nuevo, TB.core16proccarac, TB.core16procagenda, TB.core16peraca, TB.core16tercero, TB.core16idprograma, TB.core16idescuela, TB.core16idzona 
FROM core16actamatricula AS TB 
WHERE '.$sSQLadd1.' TB.core16tercero='.$idTercero.' '.$sSQLadd.'
ORDER BY TB.core16peraca DESC, TB.core16idcead, TB.core16idprograma, TB.core16tercero';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_2319" name="consulta_2319" type="hidden" value="'.$sSQLlista.'"/>
<input id="titulos_2319" name="titulos_2319" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objDB->ejecutasql($sSQL);
	if ($tabladetalle==false){
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 2319: '.$sSQL.'<br>';}
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		//$sLeyenda=$sSQL;
		}else{
		$registros=$objDB->nf($tabladetalle);
		if ($registros==0){
			//return array(utf8_encode($sErrConsulta.'<input id="paginaf2319" name="paginaf2319" type="hidden" value="'.$pagina.'"/><input id="lppf2319" name="lppf2319" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
<td><b>'.$ETI['msg_peraca'].'</b></td>
<td><b>'.$ETI['msg_programa'].'</b></td>
<td><b>'.$ETI['msg_cead'].'</b></td>
<td><b>'.$ETI['msg_nuevo'].'</b></td>
<td align="right">
'.html_paginador('paginaf2319', $registros, $lineastabla, $pagina, 'paginarf2319()').'
'.html_lpp('lppf2319', $lineastabla, 'paginarf2319()').'
</td>
</tr>';
	$tlinea=1;
	while($filadet=$objDB->sf($tabladetalle)){
		$sNomPeraca='{'.$filadet['core16peraca'].'}';
		$sSQL='SELECT exte02nombre FROM exte02per_aca WHERE exte02id='.$filadet['core16peraca'].'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$fila=$objDB->sf($tabla);
			$sNomPeraca=cadena_notildes($fila['exte02nombre']);
			}
		$sNomPrograma='{'.$filadet['core16idprograma'].'}';
		$sSQL='SELECT core09nombre FROM core09programa WHERE core09id='.$filadet['core16idprograma'].'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$fila=$objDB->sf($tabla);
			$sNomPrograma=cadena_notildes($fila['core09nombre']);
			}
		$sNomCEAD='{'.$filadet['core16idcead'].'}';
		$sSQL='SELECT unad24nombre FROM unad24sede WHERE unad24id='.$filadet['core16idcead'].'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$fila=$objDB->sf($tabla);
			$sNomCEAD=cadena_notildes($fila['unad24nombre']);
			}
		$et_core16nuevo=$ETI['no'];
		if ($filadet['core16nuevo']==1){
			$et_core16nuevo=$ETI['si'];
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
		$et_core16proccarac=$filadet['core16proccarac'];
		if ($babierta){
			if ($filadet['core16proccarac']==0){
				$sLink='<a href="javascript:procesarmatricula('.$filadet['core16id'].')" class="lnkresalte">'.$ETI['lnk_procesar'].'</a>';
				}
			}
		$res=$res.'<tr'.$sClass.'>
<td>'.$sPrefijo.$sNomPeraca.$sSufijo.'</td>
<td>'.$sPrefijo.$sNomPrograma.$sSufijo.'</td>
<td>'.$sPrefijo.$sNomCEAD.$sSufijo.'</td>
<td>'.$sPrefijo.$et_core16nuevo.$sSufijo.'</td>
<td>'.$sPrefijo.$et_core16proccarac.$sSufijo.'</td>
</tr>';
		}
	$res=$res.'</table>';
	$objDB->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
	}
function f2319_HtmlTabla($aParametros){
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
	list($sDetalle, $sDebugTabla)=f2319_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f2319detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}

function f2320_TablaDetalleV2($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2319='lg/lg_2319_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2319)){$mensajes_2319='lg/lg_2319_es.php';}
	require $mensajes_todas;
	require $mensajes_2319;
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	if (isset($aParametros[101])==0){$aParametros[101]=1;}
	if (isset($aParametros[102])==0){$aParametros[102]=20;}
	if (isset($aParametros[103])==0){$aParametros[103]='';}
	$aParametros[103]=numeros_validar($aParametros[103]);
	if ($aParametros[103]==''){$aParametros[103]=0;}
	$sDebug='';
	$pagina=$aParametros[101];
	$lineastabla=$aParametros[102];
	$babierta=false;
	//$sSQL='SELECT Campo FROM Tabla WHERE Id='.$sValorId;
	//$tabla=$objDB->ejecutasql($sSQL);
	//if ($objDB->nf($tabla)>0){
		//$fila=$objDB->sf($tabla);
		//if ($fila['Campo']!='S'){$babierta=true;}
		//}
	$sLeyenda='';
	if ($aParametros[103]==0){
		$sLeyenda='<div class="salto1px"></div>
<div class="GrupoCamposAyuda">
<b>No ha sido seleccionado un documento a consultar.</b>
<div class="salto1px"></div>
</div>';
		return array(utf8_encode($sLeyenda.'<input id="paginaf2320" name="paginaf2320" type="hidden" value="'.$pagina.'"/><input id="lppf2320" name="lppf2320" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
		}
	$idTercero=$aParametros[103];
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
				$sSQLadd=$sSQLadd.' AND T6.sys11razonsocial LIKE "%'.$sCadena.'%"';
				//$sSQLadd1=$sSQLadd1.'T1.sys11razonsocial LIKE "%'.$sCadena.'%" AND ';
				}
			}
		}
	*/
	$sTitulos='Tercero';
	$sSQL='SELECT T1.exte02nombre, TB.cara01completa, TB.cara01fechaencuesta, TB.cara01id, TB.cara01idperaca, TB.cara01idconsejero, TB.cara01tipocaracterizacion, TB.cara01fichafam, TB.cara01fichadigital, TB.cara01fichalectura, TB.cara01ficharazona, TB.cara01fichaingles, TB.cara01fichabiolog, TB.cara01fichafisica, TB.cara01fichaquimica 
FROM cara01encuesta AS TB, exte02per_aca AS T1 
WHERE '.$sSQLadd1.' TB.cara01idtercero='.$idTercero.' AND TB.cara01idperaca=T1.exte02id '.$sSQLadd.'
ORDER BY TB.cara01idperaca DESC';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_2320" name="consulta_2320" type="hidden" value="'.$sSQLlista.'"/>
<input id="titulos_2320" name="titulos_2320" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objDB->ejecutasql($sSQL);
	//$sLeyenda=$sSQL;
	if ($tabladetalle==false){
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 2320: '.$sSQL.'<br>';}
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		}else{
		$registros=$objDB->nf($tabladetalle);
		if ($registros==0){
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
<td><b>'.$ETI['msg_peraca'].'</b></td>
<td><b>'.$ETI['msg_estado'].'</b></td>
<td><b>'.$ETI['msg_fecha'].'</b></td>
<td><b>'.$ETI['msg_tipo'].'</b></td>
<td align="right" colspan="2">
'.html_paginador('paginaf2320', $registros, $lineastabla, $pagina, 'paginarf2320()').'
'.html_lpp('lppf2320', $lineastabla, 'paginarf2320()').'
</td>
</tr>';
	$tlinea=1;
	while($filadet=$objDB->sf($tabladetalle)){
		$sPrefijo='';
		$sSufijo='';
		$sClass='';
		$sLink='';
		$et_cara01completa=$ETI['msg_abierto'];
		$et_cara01fechaencuesta='';
		$et_tipo='<span class="rojo">'.$ETI['msg_ninguno'].'</span>';
		if ($filadet['cara01tipocaracterizacion']!=0){
			$et_tipo='['.$filadet['cara01tipocaracterizacion'].']';
			$sSQL='SELECT cara11nombre FROM cara11tipocaract WHERE cara11id='.$filadet['cara01tipocaracterizacion'].'';
			$tabla=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla)>0){
				$fila=$objDB->sf($tabla);
				$et_tipo=$fila['cara11nombre'];
				}
			}
		$et_fichas='';
		$et_cara11fichafamilia='';
		$sLink='';
		//Saber el numero de preguntas que se le hacer por cada ficha...
		$aBloque=array();
		for($k=1;$k<=7;$k++){
			$aBloque[$k]=0;
			}
		$sSQL='SELECT cara10idbloque, COUNT(cara10id) AS Total FROM cara10pregprueba WHERE cara10idcara='.$filadet['cara01id'].' GROUP BY cara10idbloque';
		$tabla=$objDB->ejecutasql($sSQL);
		while($fila=$objDB->sf($tabla)){
			$aBloque[$fila['cara10idbloque']]=$fila['Total'];
			}
		$bConPreg=false;
		if ($filadet['cara01fichafam']!=-1){$et_fichas=$ETI['cara11fichafamilia'];}
		if ($filadet['cara01fichadigital']!=-1){$et_fichas=$et_fichas.' - '.$ETI['cara11ficha1'].' <b>'.$aBloque[1].'</b>';$bConPreg=true;}
		if ($filadet['cara01fichalectura']!=-1){$et_fichas=$et_fichas.' - '.$ETI['cara11ficha2'].' <b>'.$aBloque[2].'</b>';$bConPreg=true;}
		if ($filadet['cara01ficharazona']!=-1){$et_fichas=$et_fichas.' - '.$ETI['cara11ficha3'].' <b>'.$aBloque[3].'</b>';$bConPreg=true;}
		if ($filadet['cara01fichaingles']!=-1){$et_fichas=$et_fichas.' - '.$ETI['cara11ficha4'].' <b>'.$aBloque[4].'</b>';$bConPreg=true;}
		if ($filadet['cara01fichaquimica']!=-1){$et_fichas=$et_fichas.' - '.$ETI['cara11ficha5'].' <b>'.$aBloque[5].'</b>';$bConPreg=true;}
		if ($filadet['cara01fichabiolog']!=-1){$et_fichas=$et_fichas.' - '.$ETI['cara11ficha6'].' <b>'.$aBloque[6].'</b>';$bConPreg=true;}
		if ($filadet['cara01fichafisica']!=-1){$et_fichas=$et_fichas.' - '.$ETI['cara11ficha7'].' <b>'.$aBloque[7].'</b>';$bConPreg=true;}
		if ($filadet['cara01completa']=='S'){
			$sPrefijo='<b>';
			$sSufijo='</b>';
			$et_cara01completa=$ETI['msg_cerrado'];
			$et_cara01fechaencuesta=fecha_desdenumero($filadet['cara01fechaencuesta']);
			}else{
			if ($bConPreg){
				$sLink=' <a href="javascript:actualizarpreg('.$filadet['cara01id'].')" class="lnkresalte">Actualizar preguntas</a>';
				}
			}
		$sLink2=' <a href="javascript:verencuesta('.$filadet['cara01id'].')" class="lnkresalte">Ver Encuesta</a>';
		if(($tlinea%2)==0){$sClass=' class="resaltetabla"';}
		$tlinea++;
		$res=$res.'<tr'.$sClass.'>
<td>'.$sPrefijo.cadena_notildes($filadet['exte02nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.$et_cara01completa.$sSufijo.'</td>
<td>'.$sPrefijo.$et_cara01fechaencuesta.$sSufijo.'</td>
<td>'.$sPrefijo.$et_tipo.$sSufijo.'</td>
<td>'.$sLink.'</td>
<td>'.$sLink2.'</td>
</tr>
<td></td>
<td colspan="5">'.$et_fichas.'</td>
</tr>';
		}
	$res=$res.'</table>';
	$objDB->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
	}
function f2320_HtmlTabla($aParametros){
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
	list($sDetalle, $sDebugTabla)=f2320_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f2320detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
?>