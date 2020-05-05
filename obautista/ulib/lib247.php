<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2015 - 2017 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.9.2 sábado, 01 de agosto de 2015
--- Modelo Versión 2.18.6 jueves, 24 de agosto de 2017
--- 247 unad47tablero
*/
function TraerBusqueda_db_unad47idcurso($sCodigo, $objdb){
	$sRespuesta='';
	$id=0;
	$sCodigo=trim($sCodigo);
	if ($sCodigo!=''){
		$sql='SELECT unad40id, unad40nombre, unad40id FROM unad40curso WHERE unad40id="'.$sCodigo.'"';
		$res=$objdb->ejecutasql($sql);
		if ($objdb->nf($res)!=0){
			$fila=$objdb->sf($res);
			$sRespuesta='<b>'.$fila['unad40id'].' '.$fila['unad40nombre'].'</b>';
			$id=$fila['unad40id'];
			}
		if ($sRespuesta==''){
			$sRespuesta='<span class="rojo">{'.$sCodigo.' No encontrado}</span>';
			}
		}
	return array($id, $sRespuesta);
	}
function TraerBusqueda_unad47idcurso($params){
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	$_SESSION['u_ultimominuto']=iminutoavance();
	$respuesta='';
	$scodigo=$params[0];
	$bxajax=true;
	if (isset($params[3])!=0){if ($params[3]==1){$bxajax=false;}}
	$id=0;
	if ($scodigo!=''){
		require 'app.php';
		$objdb=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objdb->dbPuerto=$APP->dbpuerto;}
		$objdb->xajax();
		list($id, $respuesta)=TraerBusqueda_db_unad47idcurso($scodigo, $objdb);
		}
	$objid=$params[1];
	$sdiv=$params[2];
	$objResponse=new xajaxResponse();
	$objResponse->assign($sdiv, 'innerHTML', $respuesta);
	$objResponse->assign($objid, 'value', $id);
	if ((int)$id>0){
		$objResponse->call('RevisaLlave');
		}
	return $objResponse;
	}
function f247_HtmlBusqueda($params){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	$sError='';
	require './app.php';
	$objdb=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objdb->dbPuerto=$APP->dbpuerto;}
	$objdb->xajax();
	$sDetalle='';
	switch($params[100]){
		case 'unad47idtercero':
		require $APP->rutacomun.'lib111.php';
		$sDetalle=f111_TablaDetalleBusquedas($params, $objdb);
		break;
		case 'unad47idcurso':
		if (file_exists('lib140.php')){
			require 'lib140.php';
			$sDetalle=f140_TablaDetalleBusquedas($params, $objdb);
			}else{
			$sDetalle='No se encuentra la libreria '.'lib140, por favor informe al administrador del sistema.';
			}
		break;
		}
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97tabla', 'innerHTML', $sDetalle);
	return $objResponse;
	}
function f247_TablaDetalle($params, $objdb){
	list($sRes, $sDebug)=f247_TablaDetalleV2($params, $objdb);
	return $sRes;
	}
function f247_TablaDetalleV2($params, $objdb, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_247=$APP->rutacomun.'lg/lg_247_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_247)){$mensajes_247=$APP->rutacomun.'lg/lg_247_es.php';}
	require $mensajes_todas;
	require $mensajes_247;
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	if (isset($params[101])==0){$params[101]=1;}
	if (isset($params[102])==0){$params[102]=20;}
	$sDebug='';
	$pagina=$params[101];
	$lineastabla=$params[102];
	$babierta=true;
	$sLeyenda='';
	$aOrigen=array();
	$sql='SELECT unad61id, unad61nombre FROM unad61origenmatricula';
	$tabla=$objdb->ejecutasql($sql);
	while ($fila=$objdb->sf($tabla)){
		$aOrigen[$fila['unad61id']]=$fila['unad61nombre'];
		}
	$sqladd='';
	$sqladd1='';
	if (isset($params[103])==0){$params[103]='';}
	if (isset($params[104])==0){$params[104]='';}
	if (isset($params[105])==0){$params[105]='';}
	if (isset($params[106])==0){$params[106]='';}
	if (isset($params[107])==0){$params[107]='';}
	if (isset($params[108])==0){$params[108]='';}
	if ($bDebug){
		$sDebug=$sDebug.fecha_microtiempo().' Recibio Peticiones <br>';
		}
	//if ((int)$params[103]!=-1){$sqladd=$sqladd.' AND TB.campo='.$params[103];}
	if ($params[104]!=''){$sqladd=$sqladd.' AND TB.unad47idcurso LIKE "%'.$params[104].'%"';}
	if ($params[105]!=''){$sqladd=$sqladd.' AND TB.unad47peraca='.$params[105].'';}
	if ($params[106]!=''){$sqladd=$sqladd.' AND TB.unad47idnav='.$params[106].'';}
	if ($params[108]!=''){$sqladd=$sqladd.' AND TB.unad47origen='.$params[108].'';}
	$sqladd1=$sqladd;
	$bConTercero=false;
	$sCondiTercero='';
	if ($params[103]!=''){
		$bConTercero=true;
		$sCondiTercero='unad11usuario LIKE "%'.$params[103].'%"';
		$sqladd=$sqladd.' AND T11.unad11usuario LIKE "%'.$params[103].'%"';
		}
	if ($params[107]!=''){
		if ($bConTercero){$sCondiTercero=$sCondiTercero.' AND ';}
		$bConTercero=true;
		$sCondiTercero=$sCondiTercero.'unad11doc LIKE "%'.$params[107].'%"';
		$sqladd=$sqladd.' AND T11.unad11doc LIKE "%'.$params[107].'%"';
		}
	if ($bConTercero){
		$sIds='-99';
		$sql='SELECT unad11id FROM unad11terceros WHERE '.$sCondiTercero;
		$tabla=$objdb->ejecutasql($sql);
		while ($fila=$objdb->sf($tabla)){
			$sIds=$sIds.','.$fila['unad11id'];
			}
		if ($bDebug){
			$sDebug=$sDebug.fecha_microtiempo().' Proceso cargo los ids: '.$sql.'<br><b>Resultado</b>:'.$sIds.'<br>';
			}
		$sqladd1=$sqladd1.' AND TB.unad47idtercero IN ('.$sIds.')';
		}
	$sTitulos='Peraca, Nav, Rol, Tercero, Curso, Activo, Retirado, Numaula, Grupo, Cead, Ceadasiste';
	//, TB.unad47idnav, TB.unad47idrol, TB.unad47idcurso, TB.unad47idtercero
	$sReservada='';
	if ($sqladd1!=''){$sReservada='WHERE 1';}
	$sql='SELECT TB.unad47idtercero 
FROM unad47tablero AS TB 
'.$sReservada.' '.$sqladd1.'';
	$tabladetalle=$objdb->ejecutasql($sql);
	//$sInfo=$sql;
	$registros=$objdb->nf($tabladetalle);
	if ($bDebug){
		$sDebug=$sDebug.fecha_microtiempo().' Proceso conteo <br>';
		}
	//Una consulta general se va muy larga por eso no se ordena....
	$sOrden='';
	if ($sqladd!=''){
		$sOrden=' ORDER BY T11.unad11usuario, TB.unad47peraca DESC, TB.unad47idcurso, TB.unad47idnav, TB.unad47idrol';
		}else{
		//$sOrden=' ORDER BY TB.unad47idtercero, TB.unad47peraca DESC, TB.unad47idcurso, TB.unad47idnav, TB.unad47idrol';
		}
	$sql='SELECT TB.unad47peraca, TB.unad47idnav, TB.unad47idrol, T11.unad11doc, T11.unad11usuario, T11.unad11razonsocial, TB.unad47idcurso, T40.unad40nombre, TB.unad47activo, TB.unad47retirado, TB.unad47numaula, TB.unad47idgrupo, TB.unad47idcead, TB.unad47idceadasiste, TB.unad47idtercero, TB.unad47idmoodle, TB.unad47origen 
FROM unad47tablero AS TB, unad11terceros AS T11, unad40curso AS T40
WHERE TB.unad47idtercero=T11.unad11id AND TB.unad47idcurso=T40.unad40id '.$sqladd.$sOrden.' 
';
	$sqllista=str_replace("'","|",$sql);
	$sqllista=str_replace('"',"|",$sqllista);
	$sErrConsulta='<input id="consulta_247" name="consulta_247" type="hidden" value="'.$sqllista.'"/>
<input id="titulos_247" name="titulos_247" type="hidden" value="'.$sTitulos.'"/>';
	$limite='';
	if ((($registros-1)/$lineastabla)<($pagina-1)){$pagina=(int)(($registros-1)/$lineastabla)+1;}
	if ($registros>$lineastabla){
		$rbase=($pagina-1)*$lineastabla;
		$limite=' LIMIT '.$rbase.', '.$lineastabla;
		}
	$tabladetalle=$objdb->ejecutasql($sql.$limite);
	//$sInfo=$sql.$limite;
	if ($tabladetalle==false){
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 247: '.$sql.'<br>';}
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sql.' '.$objdb->serror.'"/>';
		}
	if ($bDebug){
		$sDebug=$sDebug.fecha_microtiempo().' Proceso consulta <br>'.$sql.$limite.'<br>';
		}
	$res=$sErrConsulta.$sLeyenda.'<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">
<tr class="fondoazul">
<td colspan="3"><b>'.$ETI['unad47idtercero'].'</b></td>
<td><b>'.$ETI['unad47peraca'].'</b></td>
<td><b>'.$ETI['unad47idnav'].'</b></td>
<td><b>'.$ETI['unad47idrol'].'</b></td>
<td colspan="2"><b>'.$ETI['unad47idcurso'].'</b></td>
<td><b>'.$ETI['unad47numaula'].'</b></td>
<td><b>'.$ETI['unad47activo'].'</b></td>
<td><b>'.$ETI['unad47retirado'].'</b></td>
<td><b>'.$ETI['unad47idmoodle'].'</b></td>
<td><b>'.$ETI['unad47origen'].'</b></td>
<td align="right">
'.html_paginador('paginaf247', $registros, $lineastabla, $pagina, 'paginarf247()').'
'.html_lpp('lppf247', $lineastabla, 'paginarf247()', 200).'
</td>
</tr>';
	if ($bDebug){
		$sDebug=$sDebug.fecha_microtiempo().' Proceso paginado <br>';
		}
	$tlinea=1;
	while($filadet=$objdb->sf($tabladetalle)){
		$sPrefijo='';
		$sSufijo='';
		$sClass='';
		$sLink='';
		$et_unad47activo='Si';
		if ($filadet['unad47activo']!='S'){
			$sPrefijo='<span class="rojo">';
			$sSufijo='</span>';
			$et_unad47activo='No';
			}
		if(($tlinea%2)==0){$sClass=' class="resaltetabla"';}
		$tlinea++;
		$et_unad47retirado='No';
		if ($filadet['unad47retirado']=='S'){$et_unad47retirado='Si';}
		$sOrigen=$aOrigen[$filadet['unad47origen']];
		if ($babierta){
			$sLink='<a href="javascript:cargadato('."'".$filadet['unad47peraca']."','".$filadet['unad47idnav']."','".$filadet['unad47idrol']."','".$filadet['unad47idtercero']."','".$filadet['unad47idcurso']."','".$filadet['unad47numaula']."'".')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$res=$res.'<tr'.$sClass.'>
<td>'.$sPrefijo.$filadet['unad11doc'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['unad11razonsocial']).$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['unad11usuario'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['unad47peraca'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['unad47idnav'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['unad47idrol'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['unad47idcurso'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['unad40nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['unad47numaula'].$sSufijo.'</td>
<td>'.$sPrefijo.$et_unad47activo.$sSufijo.'</td>
<td>'.$sPrefijo.$et_unad47retirado.$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['unad47idmoodle'].$sSufijo.'</td>
<td>'.$sPrefijo.$sOrigen.$sSufijo.'</td>
<td>'.$sLink.'</td>
</tr>';
		}
	$res=$res.'</table>';
	$objdb->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
	}
function f247_HtmlTabla($params){
	$_SESSION['u_ultimominuto']=iminutoavance();
	$sError='';
	$bDebug=false;
	$sDebug='';
	$opts=$params;
	if(!is_array($opts)){$opts=json_decode(str_replace('\"','"',$opts),true);}
	if (isset($opts[99])!=0){if ($opts[99]==1){$bDebug=true;}}
	require './app.php';
	$objdb=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objdb->dbPuerto=$APP->dbpuerto;}
	$objdb->xajax();
	list($sDetalle, $sDebugTabla)=f247_TablaDetalleV2($params, $objdb, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objdb->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f247detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
// -- Espacio para incluir funciones xajax personalizadas.

function f247_TablaDetalleLaboratorio($params, $objdb){
	require 'app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_247='lg/lg_247_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_247)){$mensajes_247='lg/lg_247_es.php';}
	require $mensajes_todas;
	require $mensajes_247;
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	if (isset($params[100])==0){$params[100]='';}
	if (isset($params[101])==0){$params[101]=1;}
	if (isset($params[102])==0){$params[102]=20;}
	$params[100]=numeros_validar($params[100]);
	if ($params[100]==''){$params[100]=-999;}
	if ($params[100]==0){$params[100]=-999;}
	$pagina=$params[101];
	$lineastabla=$params[102];
	$babierta=false;
	$bDebug=false;
	$aOrigen=array();
	$sql='SELECT unad61id, unad61nombre FROM unad61origenmatricula';
	$tabla=$objdb->ejecutasql($sql);
	while ($fila=$objdb->sf($tabla)){
		$aOrigen[$fila['unad61id']]=$fila['unad61nombre'];
		}
	$sqladd='';
	if ($bDebug){
		$idProceso=1;
		$sProceso='';
		$sProceso=$sProceso.'Recibio Peticiones '.$idProceso.date(' H:i:s').'<br>';$idProceso++;
		}
	$sActualiza='';
	if ($params[100]>0){
		$sActualiza='
<label></label>
<label class="Label130">
<input id="cmdActualizarPerfiles" name="cmdActualizarPerfiles" type="button" class="btSoloProceso" onclick="actualizartablero()" value="Actualizar"/>
</label>
<div class="salto1px"></div>';
		}
	$sTitulos='Peraca, Nav, Rol, Tercero, Curso, Activo, Retirado, Numaula, Grupo, Cead, Ceadasiste';
	//, TB.unad47idnav, TB.unad47idrol, TB.unad47idcurso, TB.unad47idtercero
	$sReservada='';
	$sql='SELECT TB.unad47peraca 
FROM unad47tablero AS TB 
WHERE TB.unad47idtercero='.$params[100].'';
	$tabladetalle=$objdb->ejecutasql($sql);
	$registros=$objdb->nf($tabladetalle);
	if ($bDebug){
		$sProceso=$sProceso.'Proceso conteo '.$idProceso.date(' H:i:s').'<br>';$idProceso++;
		}
	$sql='SELECT TB.unad47peraca, TB.unad47idnav, TB.unad47idrol, TB.unad47idcurso, T40.unad40nombre, TB.unad47activo, TB.unad47retirado, TB.unad47numaula, TB.unad47idgrupo, TB.unad47idcead, TB.unad47idceadasiste, TB.unad47idtercero, TB.unad47idmoodle, TB.unad47origen 
FROM unad47tablero AS TB, unad40curso AS T40
WHERE TB.unad47idtercero='.$params[100].' AND TB.unad47idcurso=T40.unad40id '.$sqladd.' 
ORDER BY TB.unad47peraca DESC, TB.unad47idcurso, TB.unad47idnav, TB.unad47idrol';
	$sqllista=str_replace("'","|",$sql);
	$sqllista=str_replace('"',"|",$sqllista);
	$sErrConsulta='<input id="consulta_247" name="consulta_247" type="hidden" value="'.$sqllista.'"/>
<input id="titulos_247" name="titulos_247" type="hidden" value="'.$sTitulos.'"/>';
	$limite='';
	if ((($registros-1)/$lineastabla)<($pagina-1)){$pagina=(int)(($registros-1)/$lineastabla)+1;}
	if ($registros>$lineastabla){
		$rbase=($pagina-1)*$lineastabla;
		$limite=' LIMIT '.$rbase.', '.$lineastabla;
		}
	$tabladetalle=$objdb->ejecutasql($sql.$limite);
	if ($tabladetalle==false){
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sql.' '.$objdb->serror.'"/>';
		}
	if ($bDebug){
		$sProceso=$sProceso.'Proceso consulta '.$idProceso.date(' H:i:s').'<br>'.$sql.$limite.'<br>';$idProceso++;
		}
	$res=$sErrConsulta.$sActualiza.'<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">
<tr class="fondoazul">
<td><b>'.$ETI['unad47peraca'].'</b></td>
<td><b>'.$ETI['unad47idnav'].'</b></td>
<td><b>'.$ETI['unad47idrol'].'</b></td>
<td colspan="2"><b>'.$ETI['unad47idcurso'].'</b></td>
<td><b>'.$ETI['unad47numaula'].'</b></td>
<td><b>'.$ETI['unad47activo'].'</b></td>
<td><b>'.$ETI['unad47retirado'].'</b></td>
<td><b>'.$ETI['unad47idmoodle'].'</b></td>
<td><b>'.$ETI['unad47origen'].'</b></td>
<td align="right">
'.html_paginador('paginaf247', $registros, $lineastabla, $pagina, 'paginarf247()').'
'.html_lpp('lppf247', $lineastabla, 'paginarf247()', 200).'
</td>
</tr>';
	if ($bDebug){
		$sProceso=$sProceso.'Proceso paginado '.$idProceso.date(' H:i:s').'<br>';$idProceso++;
		}
	$tlinea=1;
	while($filadet=$objdb->sf($tabladetalle)){
		$sPrefijo='';
		$sSufijo='';
		$sClass='';
		$sLink='';
		$et_unad47activo='Si';
		if ($filadet['unad47activo']!='S'){
			$sPrefijo='<span class="rojo">';
			$sSufijo='</span>';
			$et_unad47activo='No';
			}
		if(($tlinea%2)==0){$sClass=' class="resaltetabla"';}
		$tlinea++;
		$et_unad47retirado='No';
		if ($filadet['unad47retirado']=='S'){$et_unad47retirado='Si';}
		$sOrigen=$aOrigen[$filadet['unad47origen']];
		if ($babierta){
			$sLink='<a href="javascript:cargadato('."'".$filadet['unad47peraca']."','".$filadet['unad47idnav']."','".$filadet['unad47idrol']."','".$filadet['unad47idtercero']."','".$filadet['unad47idcurso']."','".$filadet['unad47numaula']."'".')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$res=$res.'<tr'.$sClass.'>
<td>'.$sPrefijo.$filadet['unad47peraca'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['unad47idnav'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['unad47idrol'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['unad47idcurso'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['unad40nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['unad47numaula'].$sSufijo.'</td>
<td>'.$sPrefijo.$et_unad47activo.$sSufijo.'</td>
<td>'.$sPrefijo.$et_unad47retirado.$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['unad47idmoodle'].$sSufijo.'</td>
<td>'.$sPrefijo.$sOrigen.$sSufijo.'</td>
<td>'.$sLink.'</td>
</tr>';
		}
	$res=$res.'</table>';
	if ($bDebug){
		$res=$res.$sProceso.'Proceso '.$idProceso.date(' H:i:s').'';
		}
	return utf8_encode($res);
	}
function f247_HtmlTablaLaboratorio($params){
	$_SESSION['u_ultimominuto']=iminutoavance();
	$sError='';
	require 'app.php';
	$objdb=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objdb->dbPuerto=$APP->dbpuerto;}
	$objdb->xajax();
	$sDetalle=f247_TablaDetalleLaboratorio($params, $objdb);
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f247detalle', 'innerHTML', $sDetalle);
	return $objResponse;
	}

function f247_TablaDetalleDirector($params, $objdb){
	require 'app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_247='lg/lg_247_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_247)){$mensajes_247='lg/lg_247_es.php';}
	require $mensajes_todas;
	require $mensajes_247;
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	if (isset($params[100])==0){$params[100]='';}
	if (isset($params[101])==0){$params[101]=1;}
	if (isset($params[102])==0){$params[102]=20;}
	$params[100]=numeros_validar($params[100]);
	if ($params[100]==''){$params[100]=-999;}
	if ($params[100]==0){$params[100]=-999;}
	$pagina=$params[101];
	$lineastabla=$params[102];
	$babierta=false;
	$bDebug=false;
	$aOrigen=array();
	$sql='SELECT unad61id, unad61nombre FROM unad61origenmatricula';
	$tabla=$objdb->ejecutasql($sql);
	while ($fila=$objdb->sf($tabla)){
		$aOrigen[$fila['unad61id']]=$fila['unad61nombre'];
		}
	$sqladd='';
	if ($bDebug){
		$idProceso=1;
		$sProceso='';
		$sProceso=$sProceso.'Recibio Peticiones '.$idProceso.date(' H:i:s').'<br>';$idProceso++;
		}
	$sActualiza='';
	$sTitulos='Peraca, Nav, Rol, Tercero, Curso, Activo, Retirado, Numaula, Grupo, Cead, Ceadasiste';
	//, TB.unad47idnav, TB.unad47idrol, TB.unad47idcurso, TB.unad47idtercero
	$sReservada='';
	$sql='SELECT TB.unad47idtercero 
FROM unad47tablero AS TB 
WHERE TB.unad47idtercero='.$params[100].' AND TB.unad47idrol=3';
	$tabladetalle=$objdb->ejecutasql($sql);
	$registros=$objdb->nf($tabladetalle);
	if ($bDebug){
		$sProceso=$sProceso.'Proceso conteo '.$idProceso.date(' H:i:s').'<br>';$idProceso++;
		}
	$sql='SELECT TB.unad47peraca, TB.unad47idcurso, T40.unad40nombre, TB.unad47activo, TB.unad47retirado, TB.unad47numaula, TB.unad47idgrupo, TB.unad47idcead, TB.unad47idceadasiste, TB.unad47idtercero, TB.unad47idmoodle, TB.unad47origen 
FROM unad47tablero AS TB, unad40curso AS T40
WHERE TB.unad47idtercero='.$params[100].' AND TB.unad47idrol=3 AND TB.unad47activo="S" AND TB.unad47retirado<>"S" AND TB.unad47idcurso=T40.unad40id '.$sqladd.' 
ORDER BY TB.unad47peraca DESC, TB.unad47idcurso';
	$sqllista=str_replace("'","|",$sql);
	$sqllista=str_replace('"',"|",$sqllista);
	$sErrConsulta='<input id="consulta_247dir" name="consulta_247dir" type="hidden" value="'.$sqllista.'"/>
<input id="titulos_247dir" name="titulos_247dir" type="hidden" value="'.$sTitulos.'"/>';
	$limite='';
	if ((($registros-1)/$lineastabla)<($pagina-1)){$pagina=(int)(($registros-1)/$lineastabla)+1;}
	if ($registros>$lineastabla){
		$rbase=($pagina-1)*$lineastabla;
		$limite=' LIMIT '.$rbase.', '.$lineastabla;
		}
	$tabladetalle=$objdb->ejecutasql($sql.$limite);
	if ($tabladetalle==false){
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sql.' '.$objdb->serror.'"/>';
		}
	if ($bDebug){
		$sProceso=$sProceso.'Proceso consulta '.$idProceso.date(' H:i:s').'<br>'.$sql.$limite.'<br>';$idProceso++;
		}
	$res=$sErrConsulta.$sActualiza.'<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">
<tr class="fondoazul">
<td><b>'.$ETI['unad47peraca'].'</b></td>
<td colspan="2"><b>'.$ETI['unad47idcurso'].'</b></td>
<td align="right">
'.html_paginador('paginaf247dir', $registros, $lineastabla, $pagina, 'paginarf247dir()').'
'.html_lpp('lppf247dir', $lineastabla, 'paginarf247dir()', 200).'
</td>
</tr>';
	if ($bDebug){
		$sProceso=$sProceso.'Proceso paginado '.$idProceso.date(' H:i:s').'<br>';$idProceso++;
		}
	$tlinea=1;
	while($filadet=$objdb->sf($tabladetalle)){
		$sPrefijo='';
		$sSufijo='';
		$sClass='';
		$sLink='';
		if(($tlinea%2)==0){$sClass=' class="resaltetabla"';}
		$tlinea++;
		if ($babierta){
			}
		$res=$res.'<tr'.$sClass.'>
<td>'.$sPrefijo.$filadet['unad47peraca'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['unad47idcurso'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['unad40nombre']).$sSufijo.'</td>
<td>'.$sLink.'</td>
</tr>';
		}
	$res=$res.'</table>';
	if ($bDebug){
		$res=$res.$sProceso.'Proceso '.$idProceso.date(' H:i:s').'';
		}
	return utf8_encode($res);
	}
function f247_HtmlTablaDirector($params){
	$_SESSION['u_ultimominuto']=iminutoavance();
	$sError='';
	require 'app.php';
	$objdb=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objdb->dbPuerto=$APP->dbpuerto;}
	$objdb->xajax();
	$sDetalle=f247_TablaDetalleDirector($params, $objdb);
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f247dirdetalle', 'innerHTML', $sDetalle);
	return $objResponse;
	}
?>