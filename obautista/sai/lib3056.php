<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2021 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.25.10c lunes, 5 de abril de 2021
--- 3056 saiu53manuales
*/
/** Archivo lib3056.php.
* Libreria 3056 saiu53manuales.
* @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
* @date lunes, 5 de abril de 2021
*/
function f3056_Busquedas($aParametros){
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3056='lg/lg_3056_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3056)){$mensajes_3056='lg/lg_3056_es.php';}
	require $mensajes_todas;
	require $mensajes_3056;
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
	$sTitulo='<h2>'.$ETI['titulo_3056'].' - '.$sTitulo.'</h2>';
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97titulo', 'innerHTML', $sTitulo);
	$objResponse->assign('div_97params', 'innerHTML', $sParams);
	$objResponse->assign('div_97tabla', 'innerHTML', $sTabla);
	$objResponse->setFunction('paginarbusqueda','',$sJavaBusqueda);
	$objResponse->call('expandesector(97)');
	return $objResponse;
	}
function f3056_HtmlBusqueda($aParametros){
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
function f3056_TablaDetalleV2($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3056='lg/lg_3056_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3056)){$mensajes_3056='lg/lg_3056_es.php';}
	require $mensajes_todas;
	require $mensajes_3056;
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	if (isset($aParametros[100])==0){$aParametros[100]=$_SESSION['unad_id_tercero'];}
	if (isset($aParametros[101])==0){$aParametros[101]=1;}
	if (isset($aParametros[102])==0){$aParametros[102]=20;}
	if (isset($aParametros[103])==0){$aParametros[103]='';}
	$idTercero=$aParametros[100];
	$sDebug='';
	$pagina=$aParametros[101];
	$lineastabla=$aParametros[102];
	$saiu53titulo=$aParametros[103];
	$bAbierta=true;
	//$sSQL='SELECT Campo FROM Tabla WHERE Id='.$sValorId;
	//$tabla=$objDB->ejecutasql($sSQL);
	//if ($objDB->nf($tabla)>0){
		//$fila=$objDB->sf($tabla);
		//if ($fila['Campo']!='S'){$bAbierta=true;}
		//}
	$sLeyenda='';
	$sBotones='<input id="paginaf3056" name="paginaf3056" type="hidden" value="'.$pagina.'"/>
	<input id="lppf3056" name="lppf3056" type="hidden" value="'.$lineastabla.'"/>';
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
	$sSQLadd='';
	if ((int)$idTercero!=0){
		$sIds='-99';
		$sPerfiles='-99';
		$sCondiPerfil='';
		//Ver los perfiles del usuario.
		$sSQL='SELECT unad07idperfil FROM unad07usuarios WHERE unad07idtercero='.$idTercero.' AND unad07vigente="S" GROUP BY unad07idperfil';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			while($fila=$objDB->sf($tabla)){
				$sPerfiles=$sPerfiles.','.$fila['unad07idperfil'];
				}
			//Ahora ver cuales manuales estan disponibles para eos perfiles.
			$sSQL='SELECT TB.saiu54idmanual
			FROM saiu54manualperfil AS TB, saiu53manuales AS T1
			WHERE TB.saiu54idperfil IN (1) AND TB.saiu54vigente=1 AND TB.saiu54idmanual=T1.saiu53id AND T1.saiu53vigente=1
			GROUP BY TB.saiu54idmanual';
			$tabla=$objDB->ejecutasql($sSQL);
			while($fila=$objDB->sf($tabla)){
				$sIds=$sIds.','.$fila['saiu54idmanual'];
				}
			if ($sIds!='-99'){
				$sCondiPerfil=' OR TB.saiu53id ('.$sIds.')';
				}
			}
		$sSQLadd=' AND (TB.saiu53publico IN (0,1)'.$sCondiPerfil.')';
		}else{
		$sSQLadd=' AND TB.saiu53publico=0';
		}
	$sSQLadd=' TB.saiu53vigente=1'.$sSQLadd;
	$sSQLadd1='';
	//if ($saiu53consec!=''){$sSQLadd1=$sSQLadd1.'TB.saiu53consec='.$saiu53consec.' AND ';}
	//if ($saiu53id!=''){$sSQLadd1=$sSQLadd1.'TB.saiu53id='.$saiu53id.' AND ';}
	//if ($saiu53vigente!=''){$sSQLadd1=$sSQLadd1.'TB.saiu53vigente='.$saiu53vigente.' AND ';}
	//if ($saiu53publico!=''){$sSQLadd1=$sSQLadd1.'TB.saiu53publico='.$saiu53publico.' AND ';}
	//if ($saiu53titulo!=''){$sSQLadd1=$sSQLadd1.'TB.saiu53titulo="'.$saiu53titulo.'" AND ';}
	if ($aParametros[103]!=''){
		$sBase=trim(strtoupper($aParametros[103]));
		$aNoms=explode(' ', $sBase);
		for ($k=1;$k<=count($aNoms);$k++){
			$sCadena=$aNoms[$k-1];
			if ($sCadena!=''){
				$sSQLadd1=$sSQLadd1.'TB.saiu53titulo LIKE "%'.$sCadena.'%" AND ';
				}
			}
		}
	$sTitulos='Consec, Id, Vigente, Publico, Titulo';
	$registros=0;
	$bGigante=false; //En caso de que la tabla sea muy grande pasarlo a true
	$sLimite='';
	if ($bGigante){
		$sSQL='SELECT COUNT(1) AS Total 
		FROM saiu53manuales AS TB 
		WHERE '.$sSQLadd1.'  '.$sSQLadd.'';
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
	$sSQL='SELECT TB.saiu53consec, TB.saiu53id, TB.saiu53vigente, TB.saiu53publico, TB.saiu53titulo, TB.saiu53descripcion 
	FROM saiu53manuales AS TB 
	WHERE '.$sSQLadd1.'  '.$sSQLadd.'
	ORDER BY TB.saiu53titulo DESC';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_3056" name="consulta_3056" type="hidden" value="'.$sSQLlista.'"/>
	<input id="titulos_3056" name="titulos_3056" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objDB->ejecutasql($sSQL.$sLimite);
	if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 3056: '.$sSQL.$sLimite.'<br>';}
	if ($tabladetalle==false){
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		//$sLeyenda=$sSQL;
		}else{
		if (!$bGigante){
			$registros=$objDB->nf($tabladetalle);
			if ($registros==0){
				//return array(utf8_encode($sErrConsulta.'<input id="paginaf3056" name="paginaf3056" type="hidden" value="'.$pagina.'"/><input id="lppf3056" name="lppf3056" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
	<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">';
	$tlinea=1;
	$bPrimera=true;
	while($filadet=$objDB->sf($tabladetalle)){
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
		if ($bPrimera){
			$res=$res.'<thead class="fondoazul"><tr class="fondoazul">
			<td>'.$sPrefijo.cadena_notildes($filadet['saiu53titulo']).$sSufijo.'</td>
			<td align="right">
			'.html_paginador('paginaf3056', $registros, $lineastabla, $pagina, 'paginarf3056()').'
			'.html_lpp('lppf3056', $lineastabla, 'paginarf3056()').'
			</td>
			</tr>
			</thead>';
			$bPrimera=false;
			}else{
			$res=$res.'<tr class="fondoazul">
			<td>'.$sPrefijo.cadena_notildes($filadet['saiu53titulo']).$sSufijo.'</td>
			<td></td>
			</tr>';
			}
		$sSQL='SELECT saiu55infoversion, saiu55formaenlace, saiu55ruta, saiu55idorigen, saiu55idarchivo FROM saiu55manualversion WHERE saiu55idmanual='.$filadet['saiu53id'].' ORDER BY saiu55fecha DESC, saiu55consec LIMIT 0,1';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$fila=$objDB->sf($tabla);
			if ($fila['saiu55formaenlace']==0){
				$sLink=html_lnkarchivoV2((int)$fila['saiu55idorigen'], (int)$fila['saiu55idarchivo'], 'Descargar', 'dp.php');
				}else{
				if ($fila['saiu55ruta']!=''){
					$sLink='<a href="'.$fila['saiu55ruta'].'" class="lnkresalte" target="_blank">'.$ETI['lnk_cargarenlace'].'</a>';
					}
				}
			}
		$res=$res.'<tr'.$sClass.'>
		<td>'.$sPrefijo.cadena_notildes(nl2br($filadet['saiu53descripcion'])).$sSufijo.'</td>
		<td>'.$sLink.'</td>
		</tr>';
		}
	$res=$res.'</table>
	<div class="salto5px"></div>
	</div>';
	if ($bPrimera){$res=$res.$sBotones;}
	$objDB->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
	}
function f3056_HtmlTabla($aParametros){
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
	list($sDetalle, $sDebugTabla)=f3056_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f3056detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
//
function f3056_TablaDetalleV2Comunica($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3056='lg/lg_3056_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3056)){$mensajes_3056='lg/lg_3056_es.php';}
	require $mensajes_todas;
	require $mensajes_3056;
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	if (isset($aParametros[100])==0){$aParametros[100]=$_SESSION['unad_id_tercero'];}
	if (isset($aParametros[101])==0){$aParametros[101]=1;}
	if (isset($aParametros[102])==0){$aParametros[102]=20;}
	if (isset($aParametros[103])==0){$aParametros[103]='';}
	$idTercero=$aParametros[100];
	$sDebug='';
	$pagina=$aParametros[101];
	$lineastabla=$aParametros[102];
	$saiu53titulo=$aParametros[103];
	$bAbierta=true;
	//$sSQL='SELECT Campo FROM Tabla WHERE Id='.$sValorId;
	//$tabla=$objDB->ejecutasql($sSQL);
	//if ($objDB->nf($tabla)>0){
		//$fila=$objDB->sf($tabla);
		//if ($fila['Campo']!='S'){$bAbierta=true;}
		//}
	$sLeyenda='';
	$sBotones='<input id="paginaf3056" name="paginaf3056" type="hidden" value="'.$pagina.'"/>
	<input id="lppf3056" name="lppf3056" type="hidden" value="'.$lineastabla.'"/>';
	if ((int)$idTercero==0){
		$sLeyenda='No es posible mostrar comunicados.';
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
	$sIds='-99';
	$sSQL='SELECT saiu62idcomunicado FROM saiu62comunicadonoti WHERE saiu62idtercero='.$idTercero.'';
	$tabla=$objDB->ejecutasql($sSQL);
	while($fila=$objDB->sf($tabla)){
		$sIds=$sIds.','.$fila['saiu62idcomunicado'];
		}
	$sSQLadd='TB.saiu61id IN ('.$sIds.')';

	//if ($saiu53consec!=''){$sSQLadd1=$sSQLadd1.'TB.saiu53consec='.$saiu53consec.' AND ';}
	//if ($saiu53id!=''){$sSQLadd1=$sSQLadd1.'TB.saiu53id='.$saiu53id.' AND ';}
	//if ($saiu53vigente!=''){$sSQLadd1=$sSQLadd1.'TB.saiu53vigente='.$saiu53vigente.' AND ';}
	//if ($saiu53publico!=''){$sSQLadd1=$sSQLadd1.'TB.saiu53publico='.$saiu53publico.' AND ';}
	//if ($saiu53titulo!=''){$sSQLadd1=$sSQLadd1.'TB.saiu53titulo="'.$saiu53titulo.'" AND ';}
	if ($aParametros[103]!=''){
		$sBase=trim(strtoupper($aParametros[103]));
		$aNoms=explode(' ', $sBase);
		for ($k=1;$k<=count($aNoms);$k++){
			$sCadena=$aNoms[$k-1];
			if ($sCadena!=''){
				$sSQLadd=$sSQLadd.' AND TB.saiu61titulo LIKE "%'.$sCadena.'%"';
				}
			}
		}
	$sTitulos='Consec, Id, Vigente, Publico, Titulo';
	$registros=0;
	$bGigante=false; //En caso de que la tabla sea muy grande pasarlo a true
	$sLimite='';
	$sSQL='SELECT TB.saiu61id, TB.saiu61titulo, TB.saiu61fecha 
	FROM saiu61comunicados AS TB 
	WHERE '.$sSQLadd.'
	ORDER BY TB.saiu61fechapublica DESC';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_3056" name="consulta_3056" type="hidden" value="'.$sSQLlista.'"/>
	<input id="titulos_3056" name="titulos_3056" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objDB->ejecutasql($sSQL.$sLimite);
	if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 3056: '.$sSQL.$sLimite.'<br>';}
	if ($tabladetalle==false){
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		//$sLeyenda=$sSQL;
		}else{
		if (!$bGigante){
			$registros=$objDB->nf($tabladetalle);
			if ($registros==0){
				//return array(utf8_encode($sErrConsulta.'<input id="paginaf3056" name="paginaf3056" type="hidden" value="'.$pagina.'"/><input id="lppf3056" name="lppf3056" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
	<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">';
	$tlinea=1;
	$bPrimera=true;
	while($filadet=$objDB->sf($tabladetalle)){
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
		if ($bPrimera){
			$res=$res.'<thead class="fondoazul"><tr class="fondoazul">
			<td>'.$sPrefijo.cadena_notildes($filadet['saiu61titulo']).$sSufijo.'</td>
			<td align="right">
			'.html_paginador('paginaf3056', $registros, $lineastabla, $pagina, 'paginarf3056()').'
			'.html_lpp('lppf3056', $lineastabla, 'paginarf3056()').'
			</td>
			</tr>
			</thead>';
			$bPrimera=false;
			}else{
			$res=$res.'<tr class="fondoazul">
			<td>'.$sPrefijo.cadena_notildes($filadet['saiu61titulo']).$sSufijo.'</td>
			<td></td>
			</tr>';
			}
		$sLink='<a href="javascript:vercomunicado('.$filadet['saiu61id'].')" class="lnkresalte">Ver</a>';
		$res=$res.'<tr'.$sClass.'>
		<td>'.$sPrefijo.'Fecha: '.formato_FechaLargaDesdeNumero($filadet['saiu61fecha']).$sSufijo.'</td>
		<td>'.$sLink.'</td>
		</tr>';
		}
	$res=$res.'</table>
	<div class="salto5px"></div>
	</div>';
	if ($bPrimera){$res=$res.$sBotones;}
	$objDB->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
	}
function f3056_HtmlTablaComunica($aParametros){
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
	list($sDetalle, $sDebugTabla)=f3056_TablaDetalleV2Comunica($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f3056detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f3056_InfoComunicado($aParametros){
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
	$id46=$aParametros[0];
	$idTercero=$aParametros[1];
	list($sDetalle, $sDebug)=f3046_HtmlComunicado($id46, $idTercero, $objDB, $bDebug);
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f3056comunicado', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
?>