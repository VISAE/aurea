<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2015 - 2017 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.9.7 domingo, 29 de noviembre de 2015
--- Modelo Versión 2.12.5 lunes, 14 de marzo de 2016
--- Modelo Versión 2.15.8 miércoles, 02 de noviembre de 2016
--- Modelo Versión 2.16.3 miércoles, 04 de enero de 2017
--- Modelo Versión 2.18.1 martes, 16 de mayo de 2017
--- 1916 even16encuesta
*/
function html_combo_even16idproceso($objdb, $valor, $vr){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	//@@ Se debe arreglar la condicion..
	$scondi='';
	$res=html_combo('even16idproceso', 'even17id', 'even17nombre', 'even17proceso', $scondi, 'even17nombre', $valor, $objdb, '', true, '{'.$ETI['msg_seleccione'].'}', '');
	return utf8_encode($res);
	}
function html_combo_even16idbloqueo($objdb, $valor, $vr){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	//@@ Se debe arreglar la condicion..
	$scondi='';
	$res=html_combo('even16idbloqueo', 'unad63id', 'unad63titulo', 'unad63bloqueo', $scondi, 'unad63titulo', $valor, $objdb, '', true, '{'.$ETI['msg_seleccione'].'}', '');
	return utf8_encode($res);
	}
function f1916_ExisteDato($datos){
	if(!is_array($datos)){$datos=json_decode(str_replace('\"','"',$datos),true);}
	$_SESSION['u_ultimominuto']=iminutoavance();
	$bHayLlave=true;
	$even16consec=numeros_validar($datos[1]);
	if ($even16consec==''){$bHayLlave=false;}
	if ($bHayLlave){
		require './app.php';
		$objdb=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objdb->dbPuerto=$APP->dbpuerto;}
		$objdb->xajax();
		$sql='SELECT even16consec FROM even16encuesta WHERE even16consec='.$even16consec.'';
		$res=$objdb->ejecutasql($sql);
		if ($objdb->nf($res)==0){$bHayLlave=false;}
		$objdb->CerrarConexion();
		if ($bHayLlave){
			$objResponse=new xajaxResponse();
			$objResponse->call('cambiapaginaV2');
			return $objResponse;
			}
		}
	}
function f1916_Busquedas($params){
	require './app.php';
	$objdb=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objdb->dbPuerto=$APP->dbpuerto;}
	$objdb->xajax();
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_1916='lg/lg_1916_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1916)){$mensajes_1916='lg/lg_1916_es.php';}
	require $mensajes_todas;
	require $mensajes_1916;
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
		case 'even16idusuario':
		require $APP->rutacomun.'lib111.php';
		$sTabla=f111_TablaDetalleBusquedas($paramsb, $objdb);
		$sTitulo='Busqueda de terceros';
		$sParams=f111_ParametrosBusqueda();
		$sJavaBusqueda=f111_JavaScriptBusqueda(1916);
		break;
		case 'even21idtercero':
		require $APP->rutacomun.'lib111.php';
		$sTabla=f111_TablaDetalleBusquedas($paramsb, $objdb);
		$sTitulo='Busqueda de terceros';
		$sParams=f111_ParametrosBusqueda();
		$sJavaBusqueda=f111_JavaScriptBusqueda(1916);
		break;
		case 'even21idcurso':
		$bExiste=true;
		if (file_exists('lib140.php')){
			require 'lib140.php';
			}else{
			$bExiste=false;
			}
		if ($bExiste){
			if (!function_exists('f140_TablaDetalleBusquedas')){$bExiste=false;}
			}
		if ($bExiste){
			$sTabla=f140_TablaDetalleBusquedas($paramsb, $objdb);
			$sTitulo=f140_TituloBusqueda();
			$sParams=f140_ParametrosBusqueda();
			$sJavaBusqueda=f140_JavaScriptBusqueda(1916);
			}else{
			$sTitulo='Busquedas';
			$sTabla='<div class="MarquesinaMedia">No se ha definido la busqueda 140, por favor informe al administrador del sistema.</div>';
			}
		break;
		case 'even25idcurso':
		$bExiste=true;
		if (file_exists('lib140.php')){
			require 'lib140.php';
			}else{
			$bExiste=false;
			}
		if ($bExiste){
			if (!function_exists('f140_TablaDetalleBusquedas')){$bExiste=false;}
			}
		if ($bExiste){
			$sTabla=f140_TablaDetalleBusquedas($paramsb, $objdb);
			$sTitulo=f140_TituloBusqueda();
			$sParams=f140_ParametrosBusqueda();
			$sJavaBusqueda=f140_JavaScriptBusqueda(1916);
			}else{
			$sTitulo='Busquedas';
			$sTabla='<div class="MarquesinaMedia">No se ha definido la busqueda 140, por favor informe al administrador del sistema.</div>';
			}
		break;
		case 'even40idpropietario':
		require $APP->rutacomun.'lib111.php';
		$sTabla=f111_TablaDetalleBusquedas($paramsb, $objdb);
		$sTitulo='Busqueda de terceros';
		$sParams=f111_ParametrosBusqueda();
		$sJavaBusqueda=f111_JavaScriptBusqueda(1916);
		break;
		}
	$sTitulo='<h2>'.$ETI['titulo_1916'].' - '.$sTitulo.'</h2>';
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97titulo', 'innerHTML', $sTitulo);
	$objResponse->assign('div_97params', 'innerHTML', $sParams);
	$objResponse->assign('div_97tabla', 'innerHTML', $sTabla);
	$objResponse->setFunction('paginarbusqueda','',$sJavaBusqueda);
	$objResponse->call('expandesector(97)');
	return $objResponse;
	}
function f1916_HtmlBusqueda($params){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	$sError='';
	require './app.php';
	$objdb=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objdb->dbPuerto=$APP->dbpuerto;}
	$objdb->xajax();
	$sDetalle='';
	switch($params[100]){
		case 'even16idusuario':
		require $APP->rutacomun.'lib111.php';
		$sDetalle=f111_TablaDetalleBusquedas($params, $objdb);
		break;
		case 'even21idtercero':
		require $APP->rutacomun.'lib111.php';
		$sDetalle=f111_TablaDetalleBusquedas($params, $objdb);
		break;
		case 'even21idcurso':
		if (file_exists('lib140.php')){
			require 'lib140.php';
			$sDetalle=f140_TablaDetalleBusquedas($params, $objdb);
			}else{
			$sDetalle='No se encuentra la libreria '.'lib140, por favor informe al administrador del sistema.';
			}
		break;
		case 'even25idcurso':
		if (file_exists('lib140.php')){
			require 'lib140.php';
			$sDetalle=f140_TablaDetalleBusquedas($params, $objdb);
			}else{
			$sDetalle='No se encuentra la libreria '.'lib140, por favor informe al administrador del sistema.';
			}
		break;
		case 'even40idpropietario':
		require $APP->rutacomun.'lib111.php';
		$sDetalle=f111_TablaDetalleBusquedas($params, $objdb);
		break;
		}
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97tabla', 'innerHTML', $sDetalle);
	return $objResponse;
	}
function f1916_TablaDetalleV2($params, $objdb, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_1916='lg/lg_1916_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1916)){$mensajes_1916='lg/lg_1916_es.php';}
	require $mensajes_todas;
	require $mensajes_1916;
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	if (isset($params[101])==0){$params[101]=1;}
	if (isset($params[102])==0){$params[102]=20;}
	if (isset($params[103])==0){$params[103]='';}
	if (isset($params[104])==0){$params[104]='';}
	$params[104]=numeros_validar($params[104]);
	$sDebug='';
	$pagina=$params[101];
	$lineastabla=$params[102];
	$babierta=true;
	//$sql='SELECT Campo FROM Tabla WHERE Id='.$sValorId;
	//$tabla=$objdb->ejecutasql($sql);
	//if ($objdb->nf($tabla)>0){
		//$fila=$objdb->sf($tabla);
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
	$sqladd='';
	$sqladd1='';
	//if ((int)$params[103]!=-1){$sqladd=$sqladd.' AND TB.campo='.$params[103];}
	if ($params[103]!=''){$sqladd1=$sqladd1.'TB.even16encabezado LIKE "%'.$params[103].'%" AND ';}
	if ($params[104]!=''){$sqladd1=$sqladd1.'TB.even16idproceso='.$params[104].' AND ';}
	/*
	if ($params[104]!=''){
		$sBase=trim(strtoupper($params[104]));
		$aNoms=explode(' ', $sBase);
		for ($k=1;$k<=count($aNoms);$k++){
			$sCadena=$aNoms[$k-1];
			if ($sCadena!=''){
				$sqladd=$sqladd.' AND T6.sys11razonsocial LIKE "%'.$sCadena.'%"';
				//$sqladd1=$sqladd1.'T1.sys11razonsocial LIKE "%'.$sCadena.'%" AND ';
				}
			}
		}
	*/
	$sTitulos='Consec, Id, Proceso, Porperiodo, Porcurso, Porbloqueo, Peraca, Curso, Bloqueo, Encabezado, Usuario, Fechainicio, Fechafin, Publicada';
	$sql='SELECT TB.even16consec, TB.even16id, T3.even17nombre, TB.even16porperiodo, TB.even16porcurso, TB.even16porbloqueo, TB.even16encabezado, TB.even16fechainicio, TB.even16fechafin, TB.even16publicada, TB.even16idproceso, TB.even16idbloqueo, TB.even16idusuario, TB.even16tienepropietario 
FROM even16encuesta AS TB, even17proceso AS T3 
WHERE '.$sqladd1.' TB.even16idproceso=T3.even17id '.$sqladd.'
ORDER BY TB.even16consec DESC';
	$sqllista=str_replace("'","|",$sql);
	$sqllista=str_replace('"',"|",$sqllista);
	$sErrConsulta='<input id="consulta_1916" name="consulta_1916" type="hidden" value="'.$sqllista.'"/>
<input id="titulos_1916" name="titulos_1916" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objdb->ejecutasql($sql);
	if ($tabladetalle==false){
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 1916: '.$sql.'<br>';}
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sql.' '.$objdb->serror.'"/>';
		//$sLeyenda=$sql;
		}else{
		$registros=$objdb->nf($tabladetalle);
		if ($registros==0){
			//return array(utf8_encode($sErrConsulta.'<input id="paginaf1916" name="paginaf1916" type="hidden" value="'.$pagina.'"/><input id="lppf1916" name="lppf1916" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
			}
		if ((($registros-1)/$lineastabla)<($pagina-1)){$pagina=(int)(($registros-1)/$lineastabla)+1;}
		if ($registros>$lineastabla){
			$rbase=($pagina-1)*$lineastabla;
			$limite=' LIMIT '.$rbase.', '.$lineastabla;
			$tabladetalle=$objdb->ejecutasql($sql.$limite);
			}
		}
	$res=$sErrConsulta.$sLeyenda.'<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">
<tr class="fondoazul">
<td><b>'.$ETI['even16consec'].'</b></td>
<td><b>'.$ETI['even16idproceso'].'</b></td>
<td><b>'.$ETI['even16encabezado'].'</b></td>
<td><b>'.$ETI['even16publicada'].'</b></td>
<td><b>'.$ETI['even16tienepropietario'].'</b></td>
<td align="right">
'.html_paginador('paginaf1916', $registros, $lineastabla, $pagina, 'paginarf1916()').'
'.html_lpp('lppf1916', $lineastabla, 'paginarf1916()').'
</td>
</tr>';
	$tlinea=1;
	while($filadet=$objdb->sf($tabladetalle)){
		$sPrefijo='';
		$sSufijo='';
		$sClass='';
		$sLink='';
		$et_even16publicada=$ETI['no'];
		if ($filadet['even16publicada']=='S'){
			$sPrefijo='<b>';
			$sSufijo='</b>';
			$et_even16publicada=$ETI['si'];
			}
		if(($tlinea%2)==0){$sClass=' class="resaltetabla"';}
		$tlinea++;
		$et_even16tienepropietario=$ETI['no'];
		if ($filadet['even16tienepropietario']=='S'){$et_even16tienepropietario=$ETI['si'];}
		if ($babierta){
			$sLink='<a href="javascript:cargaridf1916('.$filadet['even16id'].')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$res=$res.'<tr'.$sClass.'>
<td>'.$sPrefijo.$filadet['even16consec'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['even17nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['even16encabezado']).$sSufijo.'</td>
<td>'.$sPrefijo.$et_even16publicada.$sSufijo.'</td>
<td>'.$sPrefijo.$et_even16tienepropietario.$sSufijo.'</td>
<td>'.$sLink.'</td>
</tr>';
		}
	$res=$res.'</table>';
	$objdb->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
	}
function f1916_HtmlTabla($params){
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
	list($sDetalle, $sDebugTabla)=f1916_TablaDetalleV2($params, $objdb, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objdb->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f1916detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f1916_db_CargarPadre($DATA, $objdb, $bDebug=false){
	$sError='';
	$iTipoError=0;
	$sDebug='';
	require './app.php';
	$DATA['even16idusuario_td']=$APP->tipo_doc;
	$DATA['even16idusuario_doc']='';
	if ($DATA['paso']==1){
		$sqlcondi='even16consec='.$DATA['even16consec'].'';
		}else{
		$sqlcondi='even16id='.$DATA['even16id'].'';
		}
	$sql='SELECT * FROM even16encuesta WHERE '.$sqlcondi;
	$tabla=$objdb->ejecutasql($sql);
	if ($objdb->nf($tabla)>0){
		$fila=$objdb->sf($tabla);
		$DATA['even16consec']=$fila['even16consec'];
		$DATA['even16id']=$fila['even16id'];
		$DATA['even16idproceso']=$fila['even16idproceso'];
		$DATA['even16porperiodo']=$fila['even16porperiodo'];
		$DATA['even16porcurso']=$fila['even16porcurso'];
		$DATA['even16porbloqueo']=$fila['even16porbloqueo'];
		$DATA['even16idbloqueo']=$fila['even16idbloqueo'];
		$DATA['even16encabezado']=$fila['even16encabezado'];
		$DATA['even16idusuario']=$fila['even16idusuario'];
		$DATA['even16fechainicio']=$fila['even16fechainicio'];
		$DATA['even16fechafin']=$fila['even16fechafin'];
		$DATA['even16publicada']=$fila['even16publicada'];
		$DATA['even16caracter']=$fila['even16caracter'];
		$DATA['even16tamanomuestra']=$fila['even16tamanomuestra'];
		$DATA['even16porrol']=$fila['even16porrol'];
		$DATA['even16tienepropietario']=$fila['even16tienepropietario'];
		$bcargo=true;
		$DATA['paso']=2;
		$DATA['boculta1916']=0;
		$bLimpiaHijos=true;
		}else{
		$DATA['paso']=0;
		}
	return array($DATA, $sError, $iTipoError, $sDebug);
	}
function f1916_Cerrar($even16id, $objdb, $bDebug=false){
	$icodmodulo=1916;
	$sInfo='';
	$sDebug='';
	$sql='UPDATE even16encuesta SET even16publicada="S" WHERE even16id='.$even16id.'';
	$tabla=$objdb->ejecutasql($sql);
	seg_auditar($icodmodulo, $_SESSION['unad_id_tercero'], 3, $even16id, 'Publica la encuesta', $objdb);
	return array($sInfo, $sDebug);
	}
function f1916_db_GuardarV2($DATA, $objdb, $bDebug=false){
	$icodmodulo=1916;
	$bAudita[2]=true;
	$bAudita[3]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_1916='lg/lg_1916_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1916)){$mensajes_1916='lg/lg_1916_es.php';}
	require $mensajes_todas;
	require $mensajes_1916;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	$bCerrando=false;
	$sErrorCerrando='';
	// -- Se inicia validando todas las posibles entradas de usuario.
	/*
	if (isset($DATA['even16consec'])==0){$DATA['even16consec']='';}
	if (isset($DATA['even16id'])==0){$DATA['even16id']='';}
	if (isset($DATA['even16idproceso'])==0){$DATA['even16idproceso']='';}
	if (isset($DATA['even16porperiodo'])==0){$DATA['even16porperiodo']='';}
	if (isset($DATA['even16porcurso'])==0){$DATA['even16porcurso']='';}
	if (isset($DATA['even16porbloqueo'])==0){$DATA['even16porbloqueo']='';}
	if (isset($DATA['even16idbloqueo'])==0){$DATA['even16idbloqueo']='';}
	if (isset($DATA['even16encabezado'])==0){$DATA['even16encabezado']='';}
	if (isset($DATA['even16fechainicio'])==0){$DATA['even16fechainicio']='';}
	if (isset($DATA['even16fechafin'])==0){$DATA['even16fechafin']='';}
	if (isset($DATA['even16publicada'])==0){$DATA['even16publicada']='';}
	if (isset($DATA['even16caracter'])==0){$DATA['even16caracter']='';}
	if (isset($DATA['even16tamanomuestra'])==0){$DATA['even16tamanomuestra']='';}
	if (isset($DATA['even16porrol'])==0){$DATA['even16porrol']='';}
	if (isset($DATA['even16tienepropietario'])==0){$DATA['even16tienepropietario']='';}
	*/
	$DATA['even16consec']=numeros_validar($DATA['even16consec']);
	$DATA['even16idproceso']=numeros_validar($DATA['even16idproceso']);
	$DATA['even16porperiodo']=htmlspecialchars(trim($DATA['even16porperiodo']));
	$DATA['even16porcurso']=htmlspecialchars(trim($DATA['even16porcurso']));
	$DATA['even16porbloqueo']=htmlspecialchars(trim($DATA['even16porbloqueo']));
	$DATA['even16idbloqueo']=numeros_validar($DATA['even16idbloqueo']);
	$DATA['even16encabezado']=htmlspecialchars(trim($DATA['even16encabezado']));
	$DATA['even16caracter']=numeros_validar($DATA['even16caracter']);
	$DATA['even16tamanomuestra']=numeros_validar($DATA['even16tamanomuestra']);
	$DATA['even16porrol']=htmlspecialchars(trim($DATA['even16porrol']));
	$DATA['even16tienepropietario']=htmlspecialchars(trim($DATA['even16tienepropietario']));
	// -- Se inicializan las variables que puedan pasar vacias {Especialmente números}.
	//if ($DATA['even16idproceso']==''){$DATA['even16idproceso']=0;}
	if ($DATA['even16tamanomuestra']==''){$DATA['even16tamanomuestra']=0;}
	if ($DATA['even16idbloqueo']==''){$DATA['even16idbloqueo']=0;}
	if ($DATA['even16publicada']==''){$DATA['even16publicada']='N';}
	//if ($DATA['even16caracter']==''){$DATA['even16caracter']=0;}
	//if ($DATA['even16tamanomuestra']==''){$DATA['even16tamanomuestra']=0;}
	// -- Seccion para validar los posibles causales de error.
	if (!fecha_esvalida($DATA['even16fechafin'])){
		$DATA['even16fechafin']='00/00/0000';
		}
	if (!fecha_esvalida($DATA['even16fechainicio'])){
		$DATA['even16fechainicio']='00/00/0000';
		}
	if ($DATA['even16idusuario']==0){$sError=$ERR['even16idusuario'];}
	//if ($DATA['even16encabezado']==''){$sError=$ERR['even16encabezado'];}
	//if ($DATA['even16idbloqueo']==''){$sError=$ERR['even16idbloqueo'];}
	if ($DATA['even16porbloqueo']==''){$sError=$ERR['even16porbloqueo'];}
	if ($DATA['even16porcurso']==''){$sError=$ERR['even16porcurso'];}
	if ($DATA['even16porperiodo']==''){$sError=$ERR['even16porperiodo'];}
	if ($DATA['even16idproceso']==''){$sError=$ERR['even16idproceso'];}
	// -- Tiene un cerrado.
	if ($DATA['even16publicada']=='S'){
		//Validaciones previas a cerrar
		if ($DATA['even16porperiodo']!='S'){
			if (!fecha_esvalida($DATA['even16fechafin'])){
				$sError=$ERR['even16fechafin'];
				}
			if (!fecha_esvalida($DATA['even16fechainicio'])){
				$sError=$ERR['even16fechainicio'];
				}
			}else{
			$DATA['even16fechainicio']='00/00/0000';
			$DATA['even16fechafin']='00/00/0000';
			}
		if ($sError.$sErrorCerrando!=''){
			$DATA['even16publicada']='N';
			$sErrorCerrando=$sError.' '.$sErrorCerrando;
			$sError='';
			}else{
			$bCerrando=true;
			//Acciones del cierre
			}
		}
	// -- Fin del cerrado.
	// -- Se verifican los valores de campos de otras tablas.
	if ($sError==''){$sError=tabla_terceros_existe($DATA['even16idusuario_td'], $DATA['even16idusuario_doc'], $objdb, 'El tercero Usuario ');}
	if ($sError==''){
		list($sError, $sInfo)=tercero_Bloqueado($DATA['even16idusuario'], $objdb);
		if ($sInfo!=''){$sError=$sError.'<br>'.sInfo;}
		}
	if ($sError==''){
		if ($DATA['paso']==10){
			if ($DATA['even16consec']==''){
				$DATA['even16consec']=tabla_consecutivo('even16encuesta', 'even16consec', '', $objdb);
				if ($DATA['even16consec']==-1){$sError=$objdb->serror;}
				}else{
				if (!seg_revisa_permiso($icodmodulo, 8, $objdb)){
					$sError=$ERR['8'];
					$DATA['even16consec']='';
					}
				}
			if ($sError==''){
				$sql='SELECT even16consec FROM even16encuesta WHERE even16consec='.$DATA['even16consec'].'';
				$result=$objdb->ejecutasql($sql);
				if ($objdb->nf($result)!=0){
					$sError=$ERR['existe'];
					}else{
					if (!seg_revisa_permiso($icodmodulo, 2, $objdb)){$sError=$ERR['2'];}
					}
				}
			}else{
			if (!seg_revisa_permiso($icodmodulo, 3, $objdb)){$sError=$ERR['3'];}
			}
		}
	if ($sError==''){
		if ($DATA['paso']==10){
			//Preparar el Id, Si no lo hay se quita la comprobación.
			$DATA['even16id']=tabla_consecutivo('even16encuesta','even16id', '', $objdb);
			if ($DATA['even16id']==-1){$sError=$objdb->serror;}
			}
		}
	if ($sError==''){
		if (get_magic_quotes_gpc()==1){$DATA['even16encabezado']=stripslashes($DATA['even16encabezado']);}
		//Si el campo even16encabezado permite html quite la linea htmlspecialchars para el campo y habilite la siguiente linea:
		//$even16encabezado=addslashes($DATA['even16encabezado']);
		$even16encabezado=str_replace('"', '\"', $DATA['even16encabezado']);
		$bpasa=false;
		if ($DATA['paso']==10){
			//$DATA['even16idusuario']=0; //$_SESSION['u_idtercero'];
			$DATA['even16publicada']='N';
			$sCampos1916='even16consec, even16id, even16idproceso, even16porperiodo, even16porcurso, even16porbloqueo, even16idbloqueo, even16encabezado, even16idusuario, even16fechainicio, 
even16fechafin, even16publicada, even16caracter, even16tamanomuestra, even16porrol, even16tienepropietario';
			$sValores1916=''.$DATA['even16consec'].', '.$DATA['even16id'].', '.$DATA['even16idproceso'].', "'.$DATA['even16porperiodo'].'", "'.$DATA['even16porcurso'].'", "'.$DATA['even16porbloqueo'].'", '.$DATA['even16idbloqueo'].', "'.$even16encabezado.'", '.$DATA['even16idusuario'].', "'.$DATA['even16fechainicio'].'", 
"'.$DATA['even16fechafin'].'", "'.$DATA['even16publicada'].'", '.$DATA['even16caracter'].', '.$DATA['even16tamanomuestra'].', "'.$DATA['even16porrol'].'", "'.$DATA['even16tienepropietario'].'"';
			if ($APP->utf8==1){
				$sql='INSERT INTO even16encuesta ('.$sCampos1916.') VALUES ('.utf8_encode($sValores1916).');';
				$sdetalle=$sCampos1916.'['.utf8_encode($sValores1916).']';
				}else{
				$sql='INSERT INTO even16encuesta ('.$sCampos1916.') VALUES ('.$sValores1916.');';
				$sdetalle=$sCampos1916.'['.$sValores1916.']';
				}
			$idaccion=2;
			$bpasa=true;
			}else{
			$scampo[1]='even16porperiodo';
			$scampo[2]='even16porcurso';
			$scampo[3]='even16porbloqueo';
			$scampo[4]='even16encabezado';
			$scampo[5]='even16fechainicio';
			$scampo[6]='even16fechafin';
			$scampo[7]='even16publicada';
			$scampo[8]='even16caracter';
			$scampo[9]='even16tamanomuestra';
			$scampo[10]='even16porrol';
			$scampo[11]='even16tienepropietario';
			$scampo[12]='even16idbloqueo';
			$sdato[1]=$DATA['even16porperiodo'];
			$sdato[2]=$DATA['even16porcurso'];
			$sdato[3]=$DATA['even16porbloqueo'];
			$sdato[4]=$even16encabezado;
			$sdato[5]=$DATA['even16fechainicio'];
			$sdato[6]=$DATA['even16fechafin'];
			$sdato[7]=$DATA['even16publicada'];
			$sdato[8]=$DATA['even16caracter'];
			$sdato[9]=$DATA['even16tamanomuestra'];
			$sdato[10]=$DATA['even16porrol'];
			$sdato[11]=$DATA['even16tienepropietario'];
			$sdato[12]=$DATA['even16idbloqueo'];
			$numcmod=12;
			$sWhere='even16id='.$DATA['even16id'].'';
			$sql='SELECT * FROM even16encuesta WHERE '.$sWhere;
			$sdatos='';
			$result=$objdb->ejecutasql($sql);
			if ($objdb->nf($result)>0){
				$filabase=$objdb->sf($result);
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
					$sql='UPDATE even16encuesta SET '.utf8_encode($sdatos).' WHERE '.$sWhere.';';
					}else{
					$sdetalle=$sdatos.'['.$sWhere.']';
					$sql='UPDATE even16encuesta SET '.$sdatos.' WHERE '.$sWhere.';';
					}
				$idaccion=3;
				}
			}
		if ($bpasa){
			$result=$objdb->ejecutasql($sql);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' ..<!-- '.$sql.' -->';
				if ($idaccion==2){$DATA['even16id']='';}
				$DATA['paso']=$DATA['paso']-10;
				$bCerrando=false;
				}else{
				if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Guardar 1916 '.$sql.'<br>';}
				if ($bAudita[$idaccion]){seg_auditar($icodmodulo, $_SESSION['unad_id_tercero'], $idaccion, $DATA['even16id'], $sdetalle, $objdb);}
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
		list($sErrorCerrando, $sDebugCerrar)=f1916_Cerrar($DATA['even16id'], $objdb, $bDebug);
		$sDebug=$sDebug.$sDebugCerrar;
		}
	//if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' InfoDepura<br>';}
	return array($DATA, $sError, $iTipoError, $bCerrando, $sErrorCerrando, $sDebug);
	}
function f1916_TituloBusqueda(){
	return 'Busqueda de Encuesta';
	}
function f1916_ParametrosBusqueda(){
	$sParams='<label class="Label90">Nombre</label><label><input id="b1916nombre" name="b1916nombre" type="text" value="" onchange="paginarbusqueda()" /></label>';
	return $sParams;
	}
function f1916_JavaScriptBusqueda($iModuloBusca){
	$sRes='var sCampo=window.document.frmedita.scampobusca.value;
var params=new Array();
params[100]=sCampo;
params[101]=window.document.frmedita.paginabusqueda.value;
params[102]=window.document.frmedita.lppfbusqueda.value;
params[103]=window.document.frmedita.b1916nombre.value;
xajax_f'.$iModuloBusca.'_HtmlBusqueda(params);';
	return $sRes;
	}
function f1916_TablaDetalleBusquedas($params, $objdb){
	$res='';
	return utf8_encode($res);
	}
function f1916_ProcesarArchivo($DATA, $ARCHIVO, $objdb, $bDebug=false){
	$sError='';
	$iTipoError=0;
	$sDebug='';
	$sArchivo=$ARCHIVO['archivodatos']['tmp_name'];
	$sVerExcel='Excel2007';
	switch($ARCHIVO['archivodatos']['type']){
		case 'application/vnd.ms-excel':
		$sVerExcel='Excel5';
		break;
		case 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet':
		break;
		case '':
		case 'application/download':
		$sExt=strtolower(substr($sArchivo,strlen($sArchivo)-4));
		switch ($sExt){
			case '.xls':
			$sVerExcel='Excel5';
			break;
			case 'xlsx':
			break;
			default:
			$sError='Tipo de archivo no permitido {'.$ARCHIVO['archivodatos']['type'].' - '.$sExt.' - '.$sArchivo.'}';
			}
		break;
		default:
		$sError='Tipo de archivo no permitido {'.$ARCHIVO['archivodatos']['type'].'}';
		}
	if ($sError==''){
		require './app.php';
		require $APP->rutacomun.'excel/PHPExcel.php';
		require $APP->rutacomun.'excel/PHPExcel/Writer/Excel2007.php';
		$objReader=PHPExcel_IOFactory::createReader($sVerExcel);
		$objPHPExcel=@$objReader->load($sArchivo);
		if (!is_object(@$objPHPExcel->getActiveSheet())){
			$sError='El archivo se cargo en forma correcta, pero no fue posible leerlo en '.$sVerExcel;
			}
		}
	if ($sError==''){
		$iFila=1;
		$iDatos=0;
		$iActualizados=0;
		//$sCampos1925='even16consec, even16id, even16idproceso, even16porperiodo, even16porcurso, even16porbloqueo, even16idbloqueo, even16encabezado, even16idusuario, even16fechainicio, even16fechafin, even16publicada, even16caracter, even16tamanomuestra, even16porrol, even16tienepropietario';
		//$even16id=tabla_consecutivo('even16encuesta','even16id', '', $objdb);
		//$sCampos1918='even18idencuesta, even18consec, even18id, even18idgrupo, even18pregunta, even18tiporespuesta, even18opcional, even18concomentario, even18rpta0, even18rpta1, even18rpta2, even18rpta3, even18rpta4, even18rpta5, even18rpta6, even18rpta7, even18rpta8, even18rpta9, even18orden, even18divergente, even18rptatotal, even18idpregcondiciona, even18valorcondiciona';
		//$even18idencuesta=tabla_consecutivo('even18encuestapregunta','even18idencuesta', '', $objdb);
		//$sCampos1919='even19idencuesta, even19consec, even19id, even19nombre';
		//$even19idencuesta=tabla_consecutivo('even19encuestagrupo','even19idencuesta', '', $objdb);
		//$sCampos1921='even21idencuesta, even21idtercero, even21idperaca, even21idcurso, even21idbloquedo, even21id, even21fechapresenta, even21terminada';
		//$even21idencuesta=tabla_consecutivo('even21encuestaaplica','even21idencuesta', '', $objdb);
		//$sCampos1924='even24idencuesta, even24idperaca, even24id, even24fechainicial, even24fechafinal';
		//$even24idencuesta=tabla_consecutivo('even24encuestaperaca','even24idencuesta', '', $objdb);
		$even25idencuesta=$DATA['even16id'];
		$sCampos1925='even25idencuesta, even25idcurso, even25id, even25activo';
		$even25id=tabla_consecutivo('even25encuestacurso','even25id', '', $objdb);
		//$sCampos1932='even32idencuesta, even32idrol, even32id, even32activo';
		//$even32idencuesta=tabla_consecutivo('even32encuestarol','even32idencuesta', '', $objdb);
		//$sCampos1940='even40idencuesta, even40idpropietario, even40id, even40activo';
		//$even40idencuesta=tabla_consecutivo('even40encuestaprop','even40idencuesta', '', $objdb);
		$sDato=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(0, $iFila)->getValue();
		while($sDato!=''){
			//if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Fila '.$iFila.' procesando dato ['.$sDato.']<br>';}
			//Ver que el curso exista.
			$sql='SELECT unad40id FROM unad40curso WHERE unad40id='.$sDato.'';
			$tabla=$objdb->ejecutasql($sql);
			if ($objdb->nf($tabla)>0){
				//Agregar el curso.
				$sql='SELECT even25id, even25activo FROM even25encuestacurso WHERE even25idcurso='.$sDato.' AND even25idencuesta='.$even25idencuesta.'';
				$tabla=$objdb->ejecutasql($sql);
				if ($objdb->nf($tabla)>0){
					$fila=$objdb->sf($tabla);
					if ($fila['even25activo']!='S'){
						//Actualizar el registro.
						$sql='UPDATE even25encuestacurso SET even25activo="S" WHERE even25id='.$fila['even25id'].'';
						$tabla=$objdb->ejecutasql($sql);
						$iActualizados++;
						}
					}else{
					//Insertar el registro.
					$sql='INSERT INTO even25encuestacurso(even25idencuesta, even25idcurso, even25id, even25activo) VALUES ('.$even25idencuesta.', '.$sDato.', '.$even25id.', "S")';
					$tabla=$objdb->ejecutasql($sql);
					$even25id++;
					$iDatos++;
					if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Fila '.$iFila.' <b>Se agrega el curso ['.$sDato.']</b><br>';}
					}
				}else{
				if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Fila '.$iFila.' Curso no encontrado ['.$sDato.']<br>';}
				}
			$iFila++;
			$sDato=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(0, $iFila)->getValue();
			}
		$sError='Registros insertados '.$iDatos;
		if ($iActualizados>0){
			$sError=$sError.' - Registros actualizados '.$iActualizados;
			$iTipoError=1;
			}
		}
	return array($sError, $iTipoError, $sDebug);
	}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------
function f1916_TablaDetalleV2Resultado($params, $objdb, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_1916='lg/lg_1916_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1916)){$mensajes_1916='lg/lg_1916_es.php';}
	require $mensajes_todas;
	require $mensajes_1916;
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	if (isset($params[101])==0){$params[101]=1;}
	if (isset($params[102])==0){$params[102]=20;}
	if (isset($params[103])==0){$params[103]='';}
	//$params[103]=numeros_validar($params[103]);
	$sDebug='';
	$pagina=$params[101];
	$lineastabla=$params[102];
	$babierta=true;
	//$sql='SELECT Campo FROM Tabla WHERE Id='.$sValorId;
	//$tabla=$objdb->ejecutasql($sql);
	//if ($objdb->nf($tabla)>0){
		//$fila=$objdb->sf($tabla);
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
	$idTercero=$_SESSION['unad_id_tercero'];
	$sqladd='';
	$sIds='-99';
	$sql='SELECT even16id FROM even16encuesta WHERE even16tienepropietario="N"';
	$tabla=$objdb->ejecutasql($sql);
	while ($fila=$objdb->sf($tabla)){
		$sIds=$sIds.','.$fila['even16id'];
		}
	$sql='SELECT even40idencuesta FROM even40encuestaprop WHERE even40idpropietario='.$idTercero.' AND even40activo="S"';
	$tabla=$objdb->ejecutasql($sql);
	while ($fila=$objdb->sf($tabla)){
		$sIds=$sIds.','.$fila['even40idencuesta'];
		}
	$sqladd1='TB.even16id IN ('.$sIds.') AND ';
	//if ((int)$params[103]!=-1){$sqladd=$sqladd.' AND TB.campo='.$params[103];}
	if ($params[103]!=''){$sqladd=$sqladd.' AND TB.even16encabezado LIKE "%'.$params[103].'%"';}
	$sTitulos='Consec, Id, Proceso, Porperiodo, Porcurso, Porbloqueo, Peraca, Curso, Bloqueo, Encabezado, Usuario, Fechainicio, Fechafin, Publicada';
	$sql='SELECT TB.even16consec, TB.even16id, T3.even17nombre, TB.even16porperiodo, TB.even16porcurso, TB.even16porbloqueo, TB.even16encabezado, TB.even16fechainicio, TB.even16fechafin, TB.even16publicada, TB.even16idproceso, TB.even16idbloqueo, TB.even16idusuario, TB.even16tienepropietario 
FROM even16encuesta AS TB, even17proceso AS T3 
WHERE '.$sqladd1.' TB.even16idproceso=T3.even17id '.$sqladd.'
ORDER BY TB.even16consec DESC';
	$sqllista=str_replace("'","|",$sql);
	$sqllista=str_replace('"',"|",$sqllista);
	$sErrConsulta='<input id="consulta_1916" name="consulta_1916" type="hidden" value="'.$sqllista.'"/>
<input id="titulos_1916" name="titulos_1916" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objdb->ejecutasql($sql);
	if ($tabladetalle==false){
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 1916: '.$sql.'<br>';}
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sql.' '.$objdb->serror.'"/>';
		//$sLeyenda=$sql;
		}else{
		$registros=$objdb->nf($tabladetalle);
		if ($registros==0){
			//return array(utf8_encode($sErrConsulta.'<input id="paginaf1916" name="paginaf1916" type="hidden" value="'.$pagina.'"/><input id="lppf1916" name="lppf1916" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
			}
		if ((($registros-1)/$lineastabla)<($pagina-1)){$pagina=(int)(($registros-1)/$lineastabla)+1;}
		if ($registros>$lineastabla){
			$rbase=($pagina-1)*$lineastabla;
			$limite=' LIMIT '.$rbase.', '.$lineastabla;
			$tabladetalle=$objdb->ejecutasql($sql.$limite);
			}
		}
	$res=$sErrConsulta.$sLeyenda.'<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">
<tr class="fondoazul">
<td><b>'.$ETI['even16consec'].'</b></td>
<td><b>'.$ETI['even16idproceso'].'</b></td>
<td><b>'.$ETI['even16encabezado'].'</b></td>
<td><b>'.$ETI['even16publicada'].'</b></td>
<td align="right">
'.html_paginador('paginaf1916', $registros, $lineastabla, $pagina, 'paginarf1916()').'
'.html_lpp('lppf1916', $lineastabla, 'paginarf1916()').'
</td>
</tr>';
	$tlinea=1;
	while($filadet=$objdb->sf($tabladetalle)){
		$sPrefijo='';
		$sSufijo='';
		$sClass='';
		$sLink='';
		$et_even16publicada=$ETI['no'];
		if ($filadet['even16publicada']=='S'){
			$sPrefijo='<b>';
			$sSufijo='</b>';
			$et_even16publicada=$ETI['si'];
			}
		if(($tlinea%2)==0){$sClass=' class="resaltetabla"';}
		$tlinea++;
		if ($babierta){
			$sLink='<a href="javascript:cargaridf1916('.$filadet['even16id'].')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$res=$res.'<tr'.$sClass.'>
<td>'.$sPrefijo.$filadet['even16consec'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['even17nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['even16encabezado']).$sSufijo.'</td>
<td>'.$sPrefijo.$et_even16publicada.$sSufijo.'</td>
<td>'.$sLink.'</td>
</tr>';
		}
	$res=$res.'</table>';
	return array(utf8_encode($res), $sDebug);
	}
function f1916_HtmlTablaResultado($params){
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
	list($sDetalle, $sDebugTabla)=f1916_TablaDetalleV2Resultado($params, $objdb, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objdb->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f1916detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
?>