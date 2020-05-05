<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2018 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.21.0 viernes, 20 de abril de 2018
--- 193 unad93rastros
*/
function f193_Busquedas($params){
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_193=$APP->rutacomun.'lg/lg_193_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_193)){$mensajes_193=$APP->rutacomun.'lg/lg_193_es.php';}
	require $mensajes_todas;
	require $mensajes_193;
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	$sCampo=$params[1];
	$sTitulo=' {'.$sCampo.'}';
	if (isset($params[2])==0){$params[2]=0;}
	if (isset($params[3])==0){$params[3]=0;}
	$sParams='';
	$sTabla='';
	$sJavaBusqueda='';
	$paramsb=array();
	$paramsb[101]=1;
	$paramsb[102]=20;
	switch($sCampo){
		case 'unad93idtercero':
		require $APP->rutacomun.'lib111.php';
		$sTabla=f111_TablaDetalleBusquedas($paramsb, $objDB);
		$sTitulo='Busqueda de terceros';
		$sParams=f111_ParametrosBusqueda();
		$sJavaBusqueda=f111_JavaScriptBusqueda(193);
		break;
		case 'unad93detalle':
		require $APP->rutacomun.'lib111.php';
		$sTabla=f111_TablaDetalleBusquedas($paramsb, $objDB);
		$sTitulo='Busqueda de terceros';
		$sParams=f111_ParametrosBusqueda();
		$sJavaBusqueda=f111_JavaScriptBusqueda(193);
		break;
		}
	$sTitulo='<h2>'.$ETI['titulo_193'].' - '.$sTitulo.'</h2>';
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97titulo', 'innerHTML', $sTitulo);
	$objResponse->assign('div_97params', 'innerHTML', $sParams);
	$objResponse->assign('div_97tabla', 'innerHTML', $sTabla);
	$objResponse->setFunction('paginarbusqueda','',$sJavaBusqueda);
	$objResponse->call('expandesector(97)');
	return $objResponse;
	}
function f193_HtmlBusqueda($params){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	$sError='';
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$sDetalle='';
	switch($params[100]){
		case 'unad93idtercero':
		require $APP->rutacomun.'lib111.php';
		$sDetalle=f111_TablaDetalleBusquedas($params, $objDB);
		break;
		case 'unad93detalle':
		require $APP->rutacomun.'lib111.php';
		$sDetalle=f111_TablaDetalleBusquedas($params, $objDB);
		break;
		}
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97tabla', 'innerHTML', $sDetalle);
	return $objResponse;
	}
function f193_InfoSesion($idTercero, $iFecha, $iMinuto, $objDB, $iAgnoMes, $bDebug=false){
	$sRes='';
	$sDebug='';
	$sSQL='SELECT unad71id, unad71iporigen 
FROM unad71sesion'.$iAgnoMes.' 
WHERE unad71idtercero='.$idTercero.' AND unad71fechaini='.$iFecha.' AND ((unad71horaini*60)+ unad71minutoini)<='.$iMinuto.' AND ((unad71horafin*60)+ unad71minutofin)>='.$iMinuto.'';
	$tabla=$objDB->ejecutasql($sSQL);
	//if ($tabla==false){$sRes=$sSQL;}
	while($fila=$objDB->sf($tabla)){
		if ($sRes!=''){$sRes=$sRes.'<br>';}
		$sRes=$sRes.'Sesion: '.$fila['unad71id'].' IP: '.$fila['unad71iporigen'].'';
		}
	return array($sRes, $sDebug);
	}
function f193_InfoSesionXid($idSesion, $objDB, $iAgnoMes, $bDebug=false){
	$sRes='';
	$sDebug='';
	$sSQL='SELECT unad71id, unad71iporigen 
FROM unad71sesion'.$iAgnoMes.' 
WHERE unad71id='.$idSesion.'';
	$tabla=$objDB->ejecutasql($sSQL);
	//if ($tabla==false){$sRes=$sSQL;}
	while($fila=$objDB->sf($tabla)){
		if ($sRes!=''){$sRes=$sRes.'<br>';}
		$sRes=$sRes.'Sesion: '.$fila['unad71id'].' IP: '.$fila['unad71iporigen'].'';
		}
	return array($sRes, $sDebug);
	}
function f193_TablaDetalleV2($params, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_193=$APP->rutacomun.'lg/lg_193_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_193)){$mensajes_193=$APP->rutacomun.'lg/lg_193_es.php';}
	require $mensajes_todas;
	require $mensajes_193;
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	if (isset($params[101])==0){$params[101]=1;}
	if (isset($params[102])==0){$params[102]=20;}
	if (isset($params[103])==0){$params[103]='';}
	if (isset($params[104])==0){$params[104]='';}
	if (isset($params[105])==0){$params[105]='';}
	if (isset($params[106])==0){$params[106]=0;}
	if (isset($params[107])==0){$params[107]=0;}
	$idTercero=numeros_validar($params[103]);
	if ($idTercero==''){$idTercero=0;}
	$sDebug='';
	$pagina=$params[101];
	$lineastabla=$params[102];
	$babierta=false;
	$bConSesiones=false;
	if ($params[107]==1){$bConSesiones=true;}
	//$sSQL='SELECT Campo FROM Tabla WHERE Id='.$sValorId;
	//$tabla=$objDB->ejecutasql($sSQL);
	//if ($objDB->nf($tabla)>0){
		//$fila=$objDB->sf($tabla);
		//if ($fila['Campo']!='S'){$babierta=true;}
		//}
	$iAgnoIni=fecha_agno();
	$bHayParams=false;
	$iMesIni=(int)fecha_mes();
	$iAgnoFin=$iAgnoIni;
	$iMesFin=$iMesIni;
	$sRangos='';
	$iFechaIni='';
	$iFechaFin='';
	$bHayFechaIni=false;
	if (fecha_esvalida($params[104])){
		list($iDia, $iMesIni, $iAgnoIni)=fecha_Dividir($params[104]);
		$bHayParams=true;
		$bHayFechaIni=true;
		$iFechaIni=($iAgnoIni*10000)+($iMesIni*100)+$iDia;
		$sRangos=' Desde '.$params[104].'';
		}
	if (fecha_esvalida($params[105])){
		list($iDia, $iMesFin, $iAgnoFin)=fecha_Dividir($params[105]);
		$bHayParams=true;
		$iFechaFin=($iAgnoFin*10000)+($iMesFin*100)+$iDia;
		$sRangos=$sRangos.' Hasta '.$params[105].'';
		}
	$bCerrado=true;
	$sMsgLeyenda='<b>No ha seleccionado un usuario para consulta de historial.</b>';
	if ($params[106]!=0){$bHayParams=false;}
	if ($bHayParams){
		$sMsgLeyenda='<b>Acumulados globales del mes '.$iMesFin.' del '.$iAgnoFin.'</b>';
		}else{
		//Solo los ultimos 6 meses
		if (false){
		$iAgnoIni=$iAgnoIni-1;
		if ($iAgnoIni<2018){
			$iMesIni=1;
			$iAgnoIni=2018;
			}
			}
		if (!$bHayFechaIni){
			$iMesIni=$iMesFin-6;
			$iAgnoIni=$iAgnoFin;
			if ($iMesIni<1){
				$iMesIni=$iMesIni+12;
				$iAgnoIni=$iAgnoFin-1;
				}
			}
		}
	if ($idTercero>0){
		//Ver si quien consulta s de soporte.
		$sInfoAdd='';
		$sSQL='SELECT 1 FROM unad07usuarios WHERE unad07idtercero='.$_SESSION['unad_id_tercero'].' AND unad07vigente="S" AND unad07idperfil IN (1501, 1801, 1)';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			//Mirar si el usuario esta sospechoso....
			$sSQL='SELECT unae13idia FROM unae13enrevision WHERE unae13idtercero='.$idTercero.' AND unae13estado<>2 ORDER BY unae13idia DESC';
			$tabla=$objDB->ejecutasql($sSQL);
			$iVeces=$objDB->nf($tabla);
			if ($iVeces>0){
				$fila=$objDB->sf($tabla);
				$sInfoAdd='<br>Favor revisar reporte de seguridad <b>'.fecha_desdenumero($fila['unae13idia']).'</b> + '.formato_numero($iVeces-1).'';
				}
			}
		//Termina de ver si es soporte.
		$bHayParams=true;
		$sMsgLeyenda='<b>No se ha encontrado el usuario Ref '.$idTercero.'.</b>';
		$sSQL='SELECT unad11doc, unad11razonsocial FROM unad11terceros WHERE unad11id='.$idTercero.'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$fila=$objDB->sf($tabla);
			$sMsgLeyenda='Historial de acciones del usuario <b>'.$fila['unad11doc'].' '.cadena_notildes($fila['unad11razonsocial']).'</b>'.$sRangos.$sInfoAdd;
			$bCerrado=false;
			}
		}
	$sLeyenda='<div class="salto1px"></div>
<div class="GrupoCamposAyuda">
'.$sMsgLeyenda.'
<div class="salto1px"></div>
</div>';
	if (!$bHayParams){
		return array(utf8_encode($sLeyenda.'<input id="paginaf193" name="paginaf193" type="hidden" value="'.$pagina.'"/><input id="lppf193" name="lppf193" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
		die();
		}
	if ($params[106]==0){$bCerrado=false;}
	if ($bCerrado){
		//Sacamos los datos generales del ultimo mes...
		$sMes=$iMesFin;
		if ($iMesFin<10){$sMes='0'.$iMesFin;}
		$sTabla='unad93rastros'.$iAgnoFin.$sMes;
		$res='<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">
<tr class="fondoazul">
<td colspan="3"><b>'.$ETI['msg_inicios'].'</b></td>
</tr>';
		$sSQL='SELECT TB.unad93fecha, SUBSTRING(TB.unad93url, 1, 20) AS url, COUNT(TB.unad93id) AS Total
FROM '.$sTabla.' AS TB 
WHERE TB.unad93codaccion=1
GROUP BY TB.unad93fecha, SUBSTRING(TB.unad93url, 1, 20)
ORDER BY TB.unad93fecha DESC';
		$sPrefijo='';
		$sSufijo='';
		$tlinea=1;
		$tabladetalle=$objDB->ejecutasql($sSQL);
		if ($tabladetalle==false){
			$res=$res.'<tr><td colspan="3">'.$sSQL.'</td></tr>';
			}
		while($filadet=$objDB->sf($tabladetalle)){
			$et_unad93fecha=fecha_desdenumero($filadet['unad93fecha']);
			$sClass='';
			if(($tlinea%2)==0){$sClass=' class="resaltetabla"';}
			$tlinea++;
			$res=$res.'<tr'.$sClass.'>
<td>'.$sPrefijo.$et_unad93fecha.$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['url']).$sSufijo.'</td>
<td>'.$sPrefijo.formato_numero($filadet['Total']).$sSufijo.'</td>
</tr>';
			}
		//Ahora los usuarios unicos.
		$res=$res.'<tr class="fondoazul">
<td colspan="3"><b>'.$ETI['msg_usuariosunicos'].'</b></td>
</tr>';
		$sSQL='SELECT TB.unad93fecha, SUBSTRING(TB.unad93url, 1, 20) AS url
FROM '.$sTabla.' AS TB 
GROUP BY TB.unad93fecha, SUBSTRING(TB.unad93url, 1, 20)
ORDER BY TB.unad93fecha DESC';
		$sPrefijo='';
		$sSufijo='';
		$tlinea=1;
		$tabladetalle=$objDB->ejecutasql($sSQL);
		if ($tabladetalle==false){
			$res=$res.'<tr><td colspan="3">'.$sSQL.'</td></tr>';
			}
		while($filadet=$objDB->sf($tabladetalle)){
			$et_unad93fecha=fecha_desdenumero($filadet['unad93fecha']);
			$sTotal='';
			$sSQL='SELECT TB.unad93idtercero
FROM '.$sTabla.' AS TB 
WHERE TB.unad93fecha='.$filadet['unad93fecha'].' AND SUBSTRING(TB.unad93url, 1, 20)="'.$filadet['url'].'"
GROUP BY TB.unad93idtercero';
			$tablab=$objDB->ejecutasql($sSQL);
			$sTotal=formato_numero($objDB->nf($tablab));
			$sClass='';
			if(($tlinea%2)==0){$sClass=' class="resaltetabla"';}
			$tlinea++;
			$res=$res.'<tr'.$sClass.'>
<td>'.$sPrefijo.$et_unad93fecha.$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['url']).$sSufijo.'</td>
<td>'.$sPrefijo.$sTotal.$sSufijo.'</td>
</tr>';
			}		
		$res=$res.'</table>';
		return array(utf8_encode($sLeyenda.$res.'<input id="paginaf193" name="paginaf193" type="hidden" value="'.$pagina.'"/><input id="lppf193" name="lppf193" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
		}
	$sSQLadd='';
	$sSQLadd1='';
	//El listado de rastros detallado..
	if ($iFechaIni!=''){
		$sSQLadd=' AND unad93fecha>='.$iFechaIni.' ';
		}
	if ($iFechaFin!=''){
		$sSQLadd=$sSQLadd.' AND unad93fecha<='.$iFechaFin.' ';
		}
	//if ((int)$params[103]!=-1){$sSQLadd=$sSQLadd.' AND TB.campo='.$params[103];}
	//if ($params[103]!=''){$sSQLadd=$sSQLadd.' AND TB.campo2 LIKE "%'.$params[103].'%"';}
	/*
	if ($params[104]!=''){
		$sBase=trim(strtoupper($params[104]));
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
	$registros=0;
	$aTablas=array();
	$iTablas=0;
	//Primero sacamos el numero de registros....
	$iBaseMes=($iAgnoIni*100)+$iMesIni;
	$iTopeMes=($iAgnoFin*100)+$iMesFin;
	if ($bDebug){$sDebug=$sDebug.' Rango de busqueda: de  '.$params[104].' - '.$iBaseMes.' a '.$params[105].' - '.$iTopeMes.' <br>';}
	//$sLeyenda=$sLeyenda.' agno fin '.$iAgnoFin.' agno ini '.$iAgnoIni;
	/*
	for ($l=$iAgnoFin;$l>=$iAgnoIni;$l--){
		for ($m=12;$m>0;$m--){}
		}
	*/
	for ($l=$iAgnoFin;$l>=$iAgnoIni;$l--){
		//$sLeyenda=$sLeyenda.' l='.$l;
		for ($m=12;$m>0;$m--){
			//$sLeyenda=$sLeyenda.' m='.$m;
			$bEntra=true;
			$iVerifica=($l*100)+$m;
			if ($iVerifica>$iTopeMes){$bEntra=false;}
			if ($iVerifica<$iBaseMes){$bEntra=false;}
			if ($bEntra){
				//$sLeyenda=$sLeyenda.' Entra... ';
				$sMes=$m;
				if ($m<10){$sMes='0'.$m;}
				$sTabla='unad93rastros'.$l.$sMes;
				if ($objDB->bexistetabla($sTabla)){
					$iTablas++;
					$aTablas[$iTablas]['nombre']=$sTabla;
					$sSQL='SELECT COUNT(TB.unad93id) 
FROM '.$sTabla.' AS TB 
WHERE '.$sSQLadd1.' TB.unad93idtercero='.$idTercero.' '.$sSQLadd.'';
					$tabla=$objDB->ejecutasql($sSQL);
					if ($objDB->nf($tabla)>0){
						$fila=$objDB->sf($tabla);
						$iDatosTabla=$fila[0];
						}
					$aTablas[$iTablas]['datos']=$iDatosTabla;
					$registros=$registros+$iDatosTabla;
					}else{
					//$sLeyenda=$sLeyenda.' No existe la tabla '.$sTabla.'... ';
					}
				}
			}
		}
	//$sLeyenda=$sLeyenda.' Tablas '.$iTablas;
	$sTitulos='Id, Tercero, Fecha, Hora, Minuto, Segundo, Url, Codmodulo, Codaccion, Curso, Usuario, Detalle';
	/*
	$sSQL='SELECT TB.unad93id, T2.unad11razonsocial AS C2_nombre, TB.unad93fecha, TB.unad93hora, TB.unad93minuto, TB.unad93segundo, TB.unad93url, T8.unad02nombre, T9.unad94nombre, TB.unad93idcurso, TB.unad93idusuario, T12.unad11razonsocial AS C12_nombre, TB.unad93idtercero, T2.unad11tipodoc AS C2_td, T2.unad11doc AS C2_doc, TB.unad93codmodulo, TB.unad93codaccion, TB.unad93detalle, T12.unad11tipodoc AS C12_td, T12.unad11doc AS C12_doc 
FROM unad93rastros AS TB, unad11terceros AS T2, unad02modulos AS T8, unad94accionrastro AS T9, unad11terceros AS T12 
WHERE '.$sSQLadd1.' TB.unad93idtercero=T2.unad11id AND TB.unad93codmodulo=T8.unad02id AND TB.unad93codaccion=T9.unad94id AND TB.unad93detalle=T12.unad11id '.$sSQLadd.'
ORDER BY TB.unad93id';
	*/
	//$sSQLlista=str_replace("'","|",$sSQL);
	//$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sSQLlista='';
	$sErrConsulta='<input id="consulta_193" name="consulta_193" type="hidden" value="'.$sSQLlista.'"/>
<input id="titulos_193" name="titulos_193" type="hidden" value="'.$sTitulos.'"/>';
	//$tabladetalle=$objDB->ejecutasql($sSQL);
	$res=$sErrConsulta.$sLeyenda.'
<input id="paginaf193" name="paginaf193" type="hidden" value="'.$pagina.'"/>
<input id="lppf193" name="lppf193" type="hidden" value="'.$lineastabla.'"/>
<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">
<tr class="fondoazul">
<td><b>'.$ETI['msg_fecha'].'</b></td>
<td><b>'.$ETI['unad93codmodulo'].'</b></td>
<td><b>'.$ETI['unad93codaccion'].'</b></td>
<td><b>'.$ETI['unad93idcurso'].'</b></td>
<td><b>'.$ETI['unad93detalle'].'</b></td>
</tr>';
	$tlinea=1;
	$iFecha=0;
	for ($k=1;$k<=$iTablas;$k++){
		$sTabla=$aTablas[$k]['nombre'];
		$sTablaRaiz=substr($sTabla, 13);
		$sSQL='SELECT TB.unad93id, TB.unad93fecha, TB.unad93hora, TB.unad93minuto, TB.unad93segundo, TB.unad93url, T9.unad94nombre, TB.unad93idcurso, TB.unad93idusuario, TB.unad93idtercero, TB.unad93codmodulo, TB.unad93codaccion, TB.unad93detalle, TB.unad93idsesion 
FROM '.$sTabla.' AS TB, unad94accionrastro AS T9 
WHERE '.$sSQLadd1.' TB.unad93idtercero='.$idTercero.''.$sSQLadd.' AND TB.unad93codaccion=T9.unad94id 
ORDER BY TB.unad93id DESC';
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consultando '.$sSQL.'<br>';}
		$tabladetalle=$objDB->ejecutasql($sSQL);
		if ($tabladetalle==false){
			$res=$res.'<tr><td colspan="5">'.$sSQL.'</td></tr>';
			}
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
		$et_unad93fecha='';
		if ($filadet['unad93fecha']!=0){
			if ($iFecha!=$filadet['unad93fecha']){
				$et_unad93fecha=formato_FechaLargaDesdeNumero($filadet['unad93fecha'], true);
				$iFecha=$filadet['unad93fecha'];
				$res=$res.'<tr'.$sClass.'><td colspan="7"><b>'.$et_unad93fecha.'</b></td></tr>';
				}
			}
		$et_unad93hora=html_TablaHoraMinSeg($filadet['unad93hora'], $filadet['unad93minuto'], $filadet['unad93segundo']);
		$et_unad93idusuario='';
		if ($filadet['unad93idusuario']!=$idTercero){
			$et_unad93idusuario='{'.$filadet['unad93idusuario'].'}<br>';
			$sSQL='SELECT unad11doc, unad11razonsocial FROM unad11terceros WHERE unad11id='.$filadet['unad93idusuario'].'';
			$tabla11=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla11)>0){
				$fila11=$objDB->sf($tabla11);
				//'.$fila11['unad11doc'].' 
				$et_unad93idusuario='<b>'.cadena_notildes($fila11['unad11razonsocial']).'</b><br>';
				}
			}
		$et_unad93idcurso='';
		if ($filadet['unad93idcurso']!=0){
			$et_unad93idcurso=''.$filadet['unad93idcurso'].'&nbsp;';
			}
		if ($babierta){
			$sLink='<a href="javascript:cargadato('.$filadet['unad93id'].')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$sInfoSesion='';
		if ($bConSesiones){
			$sInfoSesion='';
			if ($iFecha!=0){
				if ($iFecha>20191120){
					list($sInfoSesion, $sDebugT)=f193_InfoSesionXid($filadet['unad93idsesion'], $objDB, $sTablaRaiz);
					}else{
				$iMinuto=($filadet['unad93hora']*60)+$filadet['unad93minuto'];
				list($sInfoSesion, $sDebugT)=f193_InfoSesion($idTercero, $iFecha, $iMinuto, $objDB, $sTablaRaiz);
				if ($sInfoSesion==''){$sInfoSesion='Sin datos de sesi&oacute;n';}
					}
				}
			}
		$res=$res.'<tr'.$sClass.'>
<td>'.$sPrefijo.$et_unad93hora.$sSufijo.'</td>
<td align="center">'.$sPrefijo.cadena_notildes($filadet['unad93codmodulo']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['unad94nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.$et_unad93idcurso.$sSufijo.'</td>
<td>'.$sPrefijo.$et_unad93idusuario.cadena_notildes($filadet['unad93detalle']).$sSufijo.'</td>
</tr>
<tr'.$sClass.'>
<td></td>
<td colspan="3">'.$sInfoSesion.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['unad93url']).$sSufijo.'</td>
</tr>';
		}
		$objDB->liberar($tabladetalle);
		}
	$res=$res.'</table>';
	return array(utf8_encode($res), $sDebug);
	}
function f193_HtmlTabla($params){
	$_SESSION['u_ultimominuto']=iminutoavance();
	$sError='';
	$bDebug=false;
	$sDebug='';
	$opts=$params;
	if(!is_array($opts)){$opts=json_decode(str_replace('\"','"',$opts),true);}
	if (isset($opts[99])!=0){if ($opts[99]==1){$bDebug=true;}}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	list($sDetalle, $sDebugTabla)=f193_TablaDetalleV2($params, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f193detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
?>