<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2019 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.23.6 Friday, September 20, 2019
--- 2328 cara28actividades
*/
/** Archivo lib2328.php.
* Libreria 2328 cara28actividades.
* @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
* @date Friday, September 20, 2019
*/
function f2328_HTMLComboV2_cara28idcentro($objDB, $objCombos, $valor, $vrcara28idzona){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	//@@ Se debe arreglar la condicion..
	$sCondi='unad24idzona="'.$vrcara28idzona.'"';
	if ($sCondi!=''){$sCondi=' WHERE '.$sCondi;}
	$objCombos->nuevo('cara28idcentro', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	$sSQL='SELECT unad24id AS id, unad24nombre AS nombre FROM unad24sede'.$sCondi;
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f2328_Combocara28idcentro($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$objCombos=new clsHtmlCombos('n');
	$html_cara28idcentro=f2328_HTMLComboV2_cara28idcentro($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_cara28idcentro', 'innerHTML', $html_cara28idcentro);
	return $objResponse;
	}
function f2328_ExisteDato($datos){
	if(!is_array($datos)){$datos=json_decode(str_replace('\"','"',$datos),true);}
	$_SESSION['u_ultimominuto']=iminutoavance();
	$bHayLlave=true;
	$cara28consec=numeros_validar($datos[1]);
	if ($cara28consec==''){$bHayLlave=false;}
	if ($bHayLlave){
		require './app.php';
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		$sSQL='SELECT cara28consec FROM cara28actividades WHERE cara28consec='.$cara28consec.'';
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
function f2328_Busquedas($aParametros){
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2328='lg/lg_2328_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2328)){$mensajes_2328='lg/lg_2328_es.php';}
	require $mensajes_todas;
	require $mensajes_2328;
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
		case 'cara28idresponsable':
		require $APP->rutacomun.'lib111.php';
		$sTabla=f111_TablaDetalleBusquedas($aParametrosB, $objDB);
		$sTitulo='Busqueda de terceros';
		$sParams=f111_ParametrosBusqueda();
		$sJavaBusqueda=f111_JavaScriptBusqueda(2328);
		break;
		case 'cara29idtercero':
		require $APP->rutacomun.'lib111.php';
		$sTabla=f111_TablaDetalleBusquedas($aParametrosB, $objDB);
		$sTitulo='Busqueda de terceros';
		$sParams=f111_ParametrosBusqueda();
		$sJavaBusqueda=f111_JavaScriptBusqueda(2328);
		break;
		}
	$sTitulo='<h2>'.$ETI['titulo_2328'].' - '.$sTitulo.'</h2>';
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97titulo', 'innerHTML', $sTitulo);
	$objResponse->assign('div_97params', 'innerHTML', $sParams);
	$objResponse->assign('div_97tabla', 'innerHTML', $sTabla);
	$objResponse->setFunction('paginarbusqueda','',$sJavaBusqueda);
	$objResponse->call('expandesector(97)');
	return $objResponse;
	}
function f2328_HtmlBusqueda($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$sError='';
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$sDetalle='';
	switch($aParametros[100]){
		case 'cara28idresponsable':
		require $APP->rutacomun.'lib111.php';
		$sDetalle=f111_TablaDetalleBusquedas($aParametros, $objDB);
		break;
		case 'cara29idtercero':
		require $APP->rutacomun.'lib111.php';
		$sDetalle=f111_TablaDetalleBusquedas($aParametros, $objDB);
		break;
		}
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97tabla', 'innerHTML', $sDetalle);
	return $objResponse;
	}
function f2328_TablaDetalleV2($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2328='lg/lg_2328_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2328)){$mensajes_2328='lg/lg_2328_es.php';}
	require $mensajes_todas;
	require $mensajes_2328;
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	if (isset($aParametros[101])==0){$aParametros[101]=1;}
	if (isset($aParametros[102])==0){$aParametros[102]=20;}
	if (isset($aParametros[103])==0){$aParametros[103]='';}
	if (isset($aParametros[104])==0){$aParametros[104]='';}
	if (isset($aParametros[105])==0){$aParametros[105]='';}
	$bDoc=numeros_validar($aParametros[105]);
	if ($bDoc!=trim($aParametros[105])){
		$bDoc='';
		}
	$sDebug='';
	$pagina=$aParametros[101];
	$lineastabla=$aParametros[102];
	$bTipo=$aParametros[103];
	$bFormato=$aParametros[104];
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
		return array($sLeyenda.'<input id="paginaf2328" name="paginaf2328" type="hidden" value="'.$pagina.'"/><input id="lppf2328" name="lppf2328" type="hidden" value="'.$lineastabla.'"/>', $sDebug);
		die();
		}
	$sSQLadd='';
	$sSQLadd1='';
	//if ((int)$aParametros[103]!=-1){$sSQLadd=$sSQLadd.' AND TB.campo='.$aParametros[103];}
	if ($bTipo!=''){$sSQLadd1=$sSQLadd1.'TB.cara28tipoactividad='.$bTipo.' AND ';}
	if ($bFormato!=''){$sSQLadd1=$sSQLadd1.'TB.cara28formato='.$bFormato.' AND ';}
	if ($bDoc!=''){
		$sIds='-99';
		$sSQL='SELECT T29.cara29idactividad 
FROM unad11terceros AS TB, cara29actividadasiste AS T29 
WHERE TB.unad11doc LIKE "%'.$bDoc.'%" AND T29.cara29idtercero=TB.unad11id 
GROUP BY T29.cara29idactividad';
		$tabla=$objDB->ejecutasql($sSQL);
		while ($fila=$objDB->sf($tabla)){
			$sIds=$sIds.','.$fila['cara29idactividad'];
			}
		$sSQLadd1='TB.cara28id IN ('.$sIds.') AND '.$sSQLadd1;
		}
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
	$sTitulos='Consec, Id, Tipoactividad, Estado, Fecha, Horaini, Minini, Horafin, Minfin, Responsable, Zona, Centro, Lugar, Detalle';
	$sSQL='SELECT TB.cara28consec, TB.cara28id, T3.cara30nombre, T4.cara32nombre, TB.cara28fecha, TB.cara28horaini, TB.cara28minini, TB.cara28horafin, TB.cara28minfin, T12.unad24nombre, TB.cara28lugar, TB.cara28detalle, TB.cara28tipoactividad, TB.cara28estado, TB.cara28idresponsable, TB.cara28idzona, TB.cara28idcentro 
FROM cara28actividades AS TB, cara30tipoactividad AS T3, cara32estadoactividad AS T4, unad24sede AS T12 
WHERE '.$sSQLadd1.' TB.cara28tipoactividad=T3.cara30id AND TB.cara28estado=T4.cara32id AND TB.cara28idcentro=T12.unad24id '.$sSQLadd.'
ORDER BY TB.cara28estado, TB.cara28fecha DESC, TB.cara28consec DESC';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_2328" name="consulta_2328" type="hidden" value="'.$sSQLlista.'"/>
<input id="titulos_2328" name="titulos_2328" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objDB->ejecutasql($sSQL);
	if ($tabladetalle==false){
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 2328: '.$sSQL.'<br>';}
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		//$sLeyenda=$sSQL;
		}else{
		$registros=$objDB->nf($tabladetalle);
		if ($registros==0){
			//return array(utf8_encode($sErrConsulta.'<input id="paginaf2328" name="paginaf2328" type="hidden" value="'.$pagina.'"/><input id="lppf2328" name="lppf2328" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
<td><b>'.$ETI['cara28consec'].'</b></td>
<td><b>'.$ETI['cara28tipoactividad'].'</b></td>
<td><b>'.$ETI['cara28estado'].'</b></td>
<td><b>'.$ETI['cara28fecha'].'</b></td>
<td><b>'.$ETI['cara28idcentro'].'</b></td>
<td align="right">
'.html_paginador('paginaf2328', $registros, $lineastabla, $pagina, 'paginarf2328()').'
'.html_lpp('lppf2328', $lineastabla, 'paginarf2328()').'
</td>
</tr>';
	$tlinea=1;
	while($filadet=$objDB->sf($tabladetalle)){
		$sPrefijo='';
		$sSufijo='';
		$sClass='';
		$sLink='';
		if ($filadet['cara28estado']!=0){
			$sPrefijo='<b>';
			$sSufijo='</b>';
			}
		if(($tlinea%2)==0){$sClass=' class="resaltetabla"';}
		$tlinea++;
		$et_cara28fecha='';
		if ($filadet['cara28fecha']!=0){$et_cara28fecha=fecha_desdenumero($filadet['cara28fecha']);}
		$et_cara28horaini=html_TablaHoraMin($filadet['cara28horaini'], $filadet['cara28minini']);
		$et_cara28horafin=html_TablaHoraMin($filadet['cara28horafin'], $filadet['cara28minfin']);
		if ($babierta){
			$sLink='<a href="javascript:cargaridf2328('.$filadet['cara28id'].')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$res=$res.'<tr'.$sClass.'>
<td>'.$sPrefijo.$filadet['cara28consec'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['cara30nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['cara32nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.$et_cara28fecha.$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['unad24nombre']).$sSufijo.'</td>
<td>'.$sLink.'</td>
</tr>';
		}
	$res=$res.'</table>';
	$objDB->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
	}
function f2328_HtmlTabla($aParametros){
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
	list($sDetalle, $sDebugTabla)=f2328_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f2328detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f2328_db_CargarPadre($DATA, $objDB, $bDebug=false){
	$sError='';
	$iTipoError=0;
	$sDebug='';
	require './app.php';
	$DATA['cara28idresponsable_td']=$APP->tipo_doc;
	$DATA['cara28idresponsable_doc']='';
	if ($DATA['paso']==1){
		$sSQLcondi='cara28consec='.$DATA['cara28consec'].'';
		}else{
		$sSQLcondi='cara28id='.$DATA['cara28id'].'';
		}
	$sSQL='SELECT * FROM cara28actividades WHERE '.$sSQLcondi;
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$DATA['cara28consec']=$fila['cara28consec'];
		$DATA['cara28id']=$fila['cara28id'];
		$DATA['cara28tipoactividad']=$fila['cara28tipoactividad'];
		$DATA['cara28estado']=$fila['cara28estado'];
		$DATA['cara28fecha']=$fila['cara28fecha'];
		$DATA['cara28horaini']=$fila['cara28horaini'];
		$DATA['cara28minini']=$fila['cara28minini'];
		$DATA['cara28horafin']=$fila['cara28horafin'];
		$DATA['cara28minfin']=$fila['cara28minfin'];
		$DATA['cara28idresponsable']=$fila['cara28idresponsable'];
		$DATA['cara28idzona']=$fila['cara28idzona'];
		$DATA['cara28idcentro']=$fila['cara28idcentro'];
		$DATA['cara28lugar']=$fila['cara28lugar'];
		$DATA['cara28detalle']=$fila['cara28detalle'];
		$DATA['cara28formato']=$fila['cara28formato'];
		$bcargo=true;
		$DATA['paso']=2;
		$DATA['boculta2328']=0;
		$bLimpiaHijos=true;
		}else{
		$DATA['paso']=0;
		}
	return array($DATA, $sError, $iTipoError, $sDebug);
	}
function f2328_Cerrar($cara28id, $objDB, $bDebug=false){
	$sInfo='';
	$sDebug='';
	return array($sInfo, $sDebug);
	}
function f2328_db_GuardarV2($DATA, $objDB, $bDebug=false){
	$iCodModulo=2328;
	$bAudita[2]=true;
	$bAudita[3]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2328='lg/lg_2328_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2328)){$mensajes_2328='lg/lg_2328_es.php';}
	require $mensajes_todas;
	require $mensajes_2328;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	$bCerrando=false;
	$sErrorCerrando='';
	// -- Se inicia validando todas las posibles entradas de usuario.
	/*
	if (isset($DATA['cara28consec'])==0){$DATA['cara28consec']='';}
	if (isset($DATA['cara28id'])==0){$DATA['cara28id']='';}
	if (isset($DATA['cara28tipoactividad'])==0){$DATA['cara28tipoactividad']='';}
	if (isset($DATA['cara28estado'])==0){$DATA['cara28estado']='';}
	if (isset($DATA['cara28fecha'])==0){$DATA['cara28fecha']='';}
	if (isset($DATA['cara28horaini'])==0){$DATA['cara28horaini']='';}
	if (isset($DATA['cara28minini'])==0){$DATA['cara28minini']='';}
	if (isset($DATA['cara28horafin'])==0){$DATA['cara28horafin']='';}
	if (isset($DATA['cara28minfin'])==0){$DATA['cara28minfin']='';}
	if (isset($DATA['cara28idresponsable'])==0){$DATA['cara28idresponsable']='';}
	if (isset($DATA['cara28idzona'])==0){$DATA['cara28idzona']='';}
	if (isset($DATA['cara28idcentro'])==0){$DATA['cara28idcentro']='';}
	if (isset($DATA['cara28lugar'])==0){$DATA['cara28lugar']='';}
	if (isset($DATA['cara28detalle'])==0){$DATA['cara28detalle']='';}
	if (isset($DATA['cara28formato'])==0){$DATA['cara28formato']='';}
	*/
	$DATA['cara28consec']=numeros_validar($DATA['cara28consec']);
	$DATA['cara28tipoactividad']=numeros_validar($DATA['cara28tipoactividad']);
	$DATA['cara28horaini']=numeros_validar($DATA['cara28horaini']);
	$DATA['cara28minini']=numeros_validar($DATA['cara28minini']);
	$DATA['cara28horafin']=numeros_validar($DATA['cara28horafin']);
	$DATA['cara28minfin']=numeros_validar($DATA['cara28minfin']);
	$DATA['cara28idzona']=numeros_validar($DATA['cara28idzona']);
	$DATA['cara28idcentro']=numeros_validar($DATA['cara28idcentro']);
	$DATA['cara28lugar']=htmlspecialchars(trim($DATA['cara28lugar']));
	$DATA['cara28detalle']=htmlspecialchars(trim($DATA['cara28detalle']));
	$DATA['cara28formato']=numeros_validar($DATA['cara28formato']);
	// -- Se inicializan las variables que puedan pasar vacias {Especialmente números}.
	//if ($DATA['cara28tipoactividad']==''){$DATA['cara28tipoactividad']=0;}
	if ($DATA['cara28estado']==''){$DATA['cara28estado']=0;}
	//if ($DATA['cara28horaini']==''){$DATA['cara28horaini']=0;}
	//if ($DATA['cara28minini']==''){$DATA['cara28minini']=0;}
	//if ($DATA['cara28horafin']==''){$DATA['cara28horafin']=0;}
	//if ($DATA['cara28minfin']==''){$DATA['cara28minfin']=0;}
	//if ($DATA['cara28idzona']==''){$DATA['cara28idzona']=0;}
	//if ($DATA['cara28idcentro']==''){$DATA['cara28idcentro']=0;}
	//if ($DATA['cara28formato']==''){$DATA['cara28formato']=0;}
	// -- Seccion para validar los posibles causales de error.
	$sSepara=', ';
	if ($DATA['cara28idcentro']==''){$sError=$ERR['cara28idcentro'].$sSepara.$sError;}
	if ($DATA['cara28idzona']==''){$sError=$ERR['cara28idzona'].$sSepara.$sError;}
	if ($DATA['cara28estado']!=0){
		if ($DATA['cara28formato']==''){$sError=$ERR['cara28formato'].$sSepara.$sError;}
		//if ($DATA['cara28detalle']==''){$sError=$ERR['cara28detalle'].$sSepara.$sError;}
		if ($DATA['cara28lugar']==''){$sError=$ERR['cara28lugar'].$sSepara.$sError;}
		if ($DATA['cara28idresponsable']==0){$sError=$ERR['cara28idresponsable'].$sSepara.$sError;}
		if ($DATA['cara28minfin']==''){$sError=$ERR['cara28minfin'].$sSepara.$sError;}
		if ($DATA['cara28horafin']==''){$sError=$ERR['cara28horafin'].$sSepara.$sError;}
		if ($DATA['cara28minini']==''){$sError=$ERR['cara28minini'].$sSepara.$sError;}
		if ($DATA['cara28horaini']==''){$sError=$ERR['cara28horaini'].$sSepara.$sError;}
		if ($DATA['cara28fecha']==0){
			//$DATA['cara28fecha']=fecha_DiaMod();
			$sError=$ERR['cara28fecha'].$sSepara.$sError;
			}
		if ($DATA['cara28tipoactividad']==''){$sError=$ERR['cara28tipoactividad'].$sSepara.$sError;}
		if ($sError!=''){$DATA['cara28estado']=0;}
		$sErrorCerrando=$sError;
		$sError='';
		//Fin de las valiaciones NO LLAVE.
		}
	//Valiaciones de campos obligatorios en todo guardar.
	// -- Tiene un cerrado.
	if ($DATA['cara28estado']==1){
		//Validaciones previas a cerrar
		//Aprobó las Validaciones al cerrar
		if ($sError.$sErrorCerrando!=''){
			$DATA['cara28estado']=0;
			$sErrorCerrando=$sError.' '.$sErrorCerrando;
			$sError='';
			}else{
			$bCerrando=true;
			//Acciones del cierre
			}
		}
	// -- Fin del cerrado.
	// -- Se verifican los valores de campos de otras tablas.
	if ($DATA['cara28idresponsable_doc']!=''){
		if ($sError==''){$sError=tabla_terceros_existe($DATA['cara28idresponsable_td'], $DATA['cara28idresponsable_doc'], $objDB, 'El tercero Responsable ');}
		if ($sError==''){
			list($sError, $sInfo)=tercero_Bloqueado($DATA['cara28idresponsable'], $objDB);
			if ($sInfo!=''){$sError=$sError.'<br>'.sInfo;}
			}
		}
	if ($sError==''){
		if ($DATA['paso']==10){
			if ($DATA['cara28consec']==''){
				$DATA['cara28consec']=tabla_consecutivo('cara28actividades', 'cara28consec', '', $objDB);
				if ($DATA['cara28consec']==-1){$sError=$objDB->serror;}
				}else{
				if (!seg_revisa_permiso($iCodModulo, 8, $objDB)){
					$sError=$ERR['8'];
					$DATA['cara28consec']='';
					}
				}
			if ($sError==''){
				$sSQL='SELECT 1 FROM cara28actividades WHERE cara28consec='.$DATA['cara28consec'].'';
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
		if ($DATA['cara28tipoactividad']!=1){
			$DATA['cara28formato']=0;
			}
		}
	if ($sError==''){
		if ($DATA['paso']==10){
			//Preparar el Id, Si no lo hay se quita la comprobación.
			$DATA['cara28id']=tabla_consecutivo('cara28actividades','cara28id', '', $objDB);
			if ($DATA['cara28id']==-1){$sError=$objDB->serror;}
			}
		}
	if ($sError==''){
		if (get_magic_quotes_gpc()==1){$DATA['cara28detalle']=stripslashes($DATA['cara28detalle']);}
		//Si el campo cara28detalle permite html quite la linea htmlspecialchars para el campo y habilite la siguiente linea:
		//$cara28detalle=addslashes($DATA['cara28detalle']);
		$cara28detalle=str_replace('"', '\"', $DATA['cara28detalle']);
		$bpasa=false;
		if ($DATA['paso']==10){
			$DATA['cara28estado']=0;
			//$cara28fecha=fecha_DiaMod();
			$sCampos2328='cara28consec, cara28id, cara28tipoactividad, cara28estado, cara28fecha, cara28horaini, cara28minini, cara28horafin, cara28minfin, cara28idresponsable, 
cara28idzona, cara28idcentro, cara28lugar, cara28detalle, cara28formato';
			$sValores2328=''.$DATA['cara28consec'].', '.$DATA['cara28id'].', '.$DATA['cara28tipoactividad'].', "'.$DATA['cara28estado'].'", "'.$DATA['cara28fecha'].'", '.$DATA['cara28horaini'].', '.$DATA['cara28minini'].', '.$DATA['cara28horafin'].', '.$DATA['cara28minfin'].', '.$DATA['cara28idresponsable'].', 
'.$DATA['cara28idzona'].', '.$DATA['cara28idcentro'].', "'.$DATA['cara28lugar'].'", "'.$cara28detalle.'", '.$DATA['cara28formato'].'';
			if ($APP->utf8==1){
				$sSQL='INSERT INTO cara28actividades ('.$sCampos2328.') VALUES ('.utf8_encode($sValores2328).');';
				$sdetalle=$sCampos2328.'['.utf8_encode($sValores2328).']';
				}else{
				$sSQL='INSERT INTO cara28actividades ('.$sCampos2328.') VALUES ('.$sValores2328.');';
				$sdetalle=$sCampos2328.'['.$sValores2328.']';
				}
			$idaccion=2;
			$bpasa=true;
			}else{
			$scampo[1]='cara28tipoactividad';
			$scampo[2]='cara28estado';
			$scampo[3]='cara28fecha';
			$scampo[4]='cara28horaini';
			$scampo[5]='cara28minini';
			$scampo[6]='cara28horafin';
			$scampo[7]='cara28minfin';
			$scampo[8]='cara28idresponsable';
			$scampo[9]='cara28idzona';
			$scampo[10]='cara28idcentro';
			$scampo[11]='cara28lugar';
			$scampo[12]='cara28detalle';
			$scampo[13]='cara28formato';
			$sdato[1]=$DATA['cara28tipoactividad'];
			$sdato[2]=$DATA['cara28estado'];
			$sdato[3]=$DATA['cara28fecha'];
			$sdato[4]=$DATA['cara28horaini'];
			$sdato[5]=$DATA['cara28minini'];
			$sdato[6]=$DATA['cara28horafin'];
			$sdato[7]=$DATA['cara28minfin'];
			$sdato[8]=$DATA['cara28idresponsable'];
			$sdato[9]=$DATA['cara28idzona'];
			$sdato[10]=$DATA['cara28idcentro'];
			$sdato[11]=$DATA['cara28lugar'];
			$sdato[12]=$cara28detalle;
			$sdato[13]=$DATA['cara28formato'];
			$numcmod=13;
			$sWhere='cara28id='.$DATA['cara28id'].'';
			$sSQL='SELECT * FROM cara28actividades WHERE '.$sWhere;
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
					$sSQL='UPDATE cara28actividades SET '.utf8_encode($sdatos).' WHERE '.$sWhere.';';
					}else{
					$sdetalle=$sdatos.'['.$sWhere.']';
					$sSQL='UPDATE cara28actividades SET '.$sdatos.' WHERE '.$sWhere.';';
					}
				$idaccion=3;
				}
			}
		if ($bpasa){
			$result=$objDB->ejecutasql($sSQL);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' [2328] ..<!-- '.$sSQL.' -->';
				if ($idaccion==2){$DATA['cara28id']='';}
				$DATA['paso']=$DATA['paso']-10;
				$bCerrando=false;
				}else{
				if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Guardar 2328 '.$sSQL.'<br>';}
				if ($bAudita[$idaccion]){seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], $idaccion, $DATA['cara28id'], $sdetalle, $objDB);}
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
		list($sErrorCerrando, $sDebugCerrar)=f2328_Cerrar($DATA['cara28id'], $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugCerrar;
		}
	//if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' InfoDepura<br>';}
	return array($DATA, $sError, $iTipoError, $bCerrando, $sErrorCerrando, $sDebug);
	}
function f2328_db_Eliminar($cara28id, $objDB, $bDebug=false){
	$iCodModulo=2328;
	$bAudita[4]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2328='lg/lg_2328_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2328)){$mensajes_2328='lg/lg_2328_es.php';}
	require $mensajes_todas;
	require $mensajes_2328;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	$cara28id=numeros_validar($cara28id);
	// Traer los datos para hacer las validaciones.
	if ($sError==''){
		$sSQL='SELECT * FROM cara28actividades WHERE cara28id='.$cara28id.'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$filabase=$objDB->sf($tabla);
			}else{
			$sError='No se encuentra el registro solicitado {Ref: '.$cara28id.'}';
			}
		}
	if ($sError==''){
		$sSQL='SELECT cara29idactividad FROM cara29actividadasiste WHERE cara29idactividad='.$filabase['cara28id'].'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$sError='Existen Asistentes creados, no es posible eliminar';
			}
		}
	if ($sError==''){
		if (!seg_revisa_permiso($iCodModulo, 4, $objDB)){
			$sError=$ERR['4'];
			}
		}
	if ($sError==''){
		$sSQL='SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=2328';
		$tablaor=$objDB->ejecutasql($sSQL);
		while ($filaor=$objDB->sf($tablaor)){
			$sSQL='SELECT '.$filaor['campoid'].' FROM '.$filaor['tabla'].' WHERE '.$filaor['camporev'].'='.$_REQUEST['cara28id'].' LIMIT 0, 1';
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
		//$sSQL='DELETE FROM cara29actividadasiste WHERE cara29idactividad='.$filabase['cara28id'].'';
		//$tabla=$objDB->ejecutasql($sSQL);
		$sWhere='cara28id='.$cara28id.'';
		//$sWhere='cara28consec='.$filabase['cara28consec'].'';
		$sSQL='DELETE FROM cara28actividades WHERE '.$sWhere.';';
		$result=$objDB->ejecutasql($sSQL);
		if ($result==false){
			$sError=$ERR['falla_eliminar'].' .. <!-- '.$sSQL.' -->';
			}else{
			if ($bAudita[4]){seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $cara28id, $sWhere, $objDB);}
			}
		}
	return array($sError, $iTipoError, $sDebug);
	}
function f2328_TituloBusqueda(){
	return 'Busqueda de Actividades de acompanamento';
	}
function f2328_ParametrosBusqueda(){
	$sParams='<label class="Label90">Nombre</label><label><input id="b2328nombre" name="b2328nombre" type="text" value="" onchange="paginarbusqueda()" /></label>';
	return $sParams;
	}
function f2328_JavaScriptBusqueda($iModuloBusca){
	$sRes='var sCampo=window.document.frmedita.scampobusca.value;
var params=new Array();
params[100]=sCampo;
params[101]=window.document.frmedita.paginabusqueda.value;
params[102]=window.document.frmedita.lppfbusqueda.value;
params[103]=window.document.frmedita.b2328nombre.value;
xajax_f'.$iModuloBusca.'_HtmlBusqueda(params);';
	return $sRes;
	}
function f2328_TablaDetalleBusquedas($aParametros, $objDB){
	$res='';
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2328='lg/lg_2328_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2328)){$mensajes_2328='lg/lg_2328_es.php';}
	require $mensajes_todas;
	require $mensajes_2328;
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
		return array($sLeyenda.'<input id="paginaf2328" name="paginaf2328" type="hidden" value="'.$pagina.'"/><input id="lppf2328" name="lppf2328" type="hidden" value="'.$lineastabla.'"/>', $sDebug);
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
	$sTitulos='Consec, Id, Tipoactividad, Estado, Fecha, Horaini, Minini, Horafin, Minfin, Responsable, Zona, Centro, Lugar, Detalle';
	$sSQL='SELECT TB.cara28consec, TB.cara28id, T3.cara30nombre, TB.cara28estado, TB.cara28fecha, TB.cara28horaini, TB.cara28minini, TB.cara28horafin, TB.cara28minfin, T10.unad11razonsocial AS C10_nombre, T11.unad23nombre, T12.unad24nombre, TB.cara28lugar, TB.cara28detalle, TB.cara28tipoactividad, TB.cara28idresponsable, T10.unad11tipodoc AS C10_td, T10.unad11doc AS C10_doc, TB.cara28idzona, TB.cara28idcentro 
FROM cara28actividades AS TB, cara30tipoactividad AS T3, unad11terceros AS T10, unad23zona AS T11, unad24sede AS T12 
WHERE '.$sSQLadd1.' TB.cara28tipoactividad=T3.cara30id AND TB.cara28idresponsable=T10.unad11id AND TB.cara28idzona=T11.unad23id AND TB.cara28idcentro=T12.unad24id '.$sSQLadd.'
ORDER BY TB.cara28consec';
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
			//return array(utf8_encode($sErrConsulta.'<input id="paginaf2328" name="paginaf2328" type="hidden" value="'.$pagina.'"/><input id="lppf2328" name="lppf2328" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
<td><b>'.$ETI['cara28consec'].'</b></td>
<td><b>'.$ETI['cara28tipoactividad'].'</b></td>
<td><b>'.$ETI['cara28estado'].'</b></td>
<td><b>'.$ETI['cara28fecha'].'</b></td>
<td><b>'.$ETI['cara28horaini'].'</b></td>
<td><b>'.$ETI['cara28horafin'].'</b></td>
<td colspan="2"><b>'.$ETI['cara28idresponsable'].'</b></td>
<td><b>'.$ETI['cara28idzona'].'</b></td>
<td><b>'.$ETI['cara28idcentro'].'</b></td>
<td><b>'.$ETI['cara28lugar'].'</b></td>
<td><b>'.$ETI['cara28detalle'].'</b></td>
<td align="right">
'.html_paginador('paginabusqueda', $registros, $lineastabla, $pagina, 'paginarbusqueda()').'
'.html_lpp('lppfbusqueda', $lineastabla, 'paginarbusqueda()').'
</td>
</tr>';
	$tlinea=1;
	while($filadet=$objDB->sf($tabladetalle)){
		$sPrefijo='<a href="javascript:Devuelve(\''.$filadet['cara28id'].'\');">';
		$sSufijo='</a>';
		$tlinea++;
		$et_cara28estado=$ETI['msg_abierto'];
		if ($filadet['cara28estado']=='S'){$et_cara28estado=$ETI['msg_cerrado'];}
		$et_cara28fecha='';
		if ($filadet['cara28fecha']!=0){$et_cara28fecha=fecha_desdenumero($filadet['cara28fecha']);}
		$et_cara28horaini=html_TablaHoraMin($filadet['cara28horaini'], $filadet['cara28minini']);
		$et_cara28horafin=html_TablaHoraMin($filadet['cara28horafin'], $filadet['cara28minfin']);
		$res=$res.'<tr onmouseover="cambia_color_over(this);" onmouseout="cambia_color_out(this);">
<td>'.$sPrefijo.$filadet['cara28consec'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['cara30nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.$et_cara28estado.$sSufijo.'</td>
<td>'.$sPrefijo.$et_cara28fecha.$sSufijo.'</td>
<td>'.$sPrefijo.$et_cara28horaini.$sSufijo.'</td>
<td>'.$sPrefijo.$et_cara28horafin.$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['C10_td'].' '.$filadet['C10_doc'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['C10_nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['unad23nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['unad24nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['cara28lugar']).$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['cara28detalle'].$sSufijo.'</td>
<td></td>
</tr>';
		}
	$res=$res.'</table>';
	$objDB->liberar($tabladetalle);
	return utf8_encode($res);
	}
/** Función f2328_ProcesarArchivo.
* Esta función recibe un archivo y lo procesa.
* @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
* @param $DATA contiene las variables $_REQUEST del formulario de origen
* @param $ARCHIVO contiene las variables $_FILE del formulario de origen
* @param $objDB Objeto de base datos del tipo clsdbadmin
* @param $bDebug (Opcional), bandera para indicar si se generan datos de depuración
* @date Friday, September 20, 2019
*/
function f2328_ProcesarArchivo($DATA, $ARCHIVO, $objDB, $bDebug=false){
	$sError='';
	$iTipoError=0;
	$sInfoProceso='';
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
		$sExt=pathinfo($ARCHIVO['archivodatos']['name'], PATHINFO_EXTENSION);
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
		if (!file_exists($sArchivo)){
			$sError='El archivo no fue cargado correctamente ['.$ARCHIVO['archivodatos']['name'].' - '.$ARCHIVO['archivodatos']['tmp_name'].']';
			}
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
		$iAnexados=0;
		$iActualizados=0;
		//$sCampos2328='cara28consec, cara28id, cara28tipoactividad, cara28estado, cara28fecha, cara28horaini, cara28minini, cara28horafin, cara28minfin, cara28idresponsable, cara28idzona, cara28idcentro, cara28lugar, cara28detalle';
		//$cara28id=tabla_consecutivo('cara28actividades','cara28id', '', $objDB);
		$sCampos2329='cara29idactividad, cara29idtercero, cara29id, cara29estado';
		$cara29id=tabla_consecutivo('cara29actividadasiste','cara29id', '', $objDB);
		$sDato=trim($objPHPExcel->getActiveSheet()->getCellByColumnAndRow(0, $iFila)->getValue());
		while($sDato!=''){
			$iDatos++;
			//Aqui se debe procesar
			$sErrLinea='';
			$iEstado=0;
			$sEstado=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(1, $iFila)->getValue();
			if ($sEstado==1){$iEstado=1;}
			if ($sEstado==7){$iEstado=7;}
			$sDoc=numeros_validar($sDato);
			if ($sDoc!=$sDato){
				$sErrLinea='Linea '.$iFila.': No fue posible leer el documento';
				}
			if ($sErrLinea==''){
				//Sacar el tercero.
				$sSQL='SELECT unad11id FROM unad11terceros WHERE unad11doc="'.$sDoc.'"';
				$tabla=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($tabla)>0){
					$fila=$objDB->sf($tabla);
					$idTercero=$fila['unad11id'];
					}else{
					$sErrLinea='Linea '.$iFila.': Documento no encontrado {'.$sDoc.'}';
					}
				}
			if ($sErrLinea==''){
				//Lo agregamos al evento.
				$sSQL='SELECT cara29id, cara29estado FROM cara29actividadasiste WHERE cara29idactividad='.$DATA['cara28id'].' AND cara29idtercero='.$idTercero.'';
				$tabla=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($tabla)>0){
					$fila=$objDB->sf($tabla);
					if ($fila['cara29estado']!=$iEstado){
						$sSQL='UPDATE cara29actividadasiste SET cara29estado='.$iEstado.' WHERE cara29id='.$fila['cara29id'].'';
						$result=$objDB->ejecutasql($sSQL);
						$iActualizados++;
						}
					}else{
					$sSQL='INSERT INTO cara29actividadasiste ('.$sCampos2329.') VALUES ('.$DATA['cara28id'].', '.$idTercero.', '.$cara29id.', '.$iEstado.')';
					$result=$objDB->ejecutasql($sSQL);
					$cara29id++;
					$iAnexados++;
					}
				}
			if ($sErrLinea!=''){
				if ($sInfoProceso!=''){$sInfoProceso=$sInfoProceso.'<br>';}
				$sInfoProceso=$sInfoProceso.$sErrLinea;
				}
			//$iActualizados++;
			//Leer el siguiente dato
			$iFila++;
			$sDato=trim($objPHPExcel->getActiveSheet()->getCellByColumnAndRow(0, $iFila)->getValue());
			}
		$sError='Registros totales '.$iDatos;
		if ($iAnexados>0){
			$sError=$sError.' - Registros anexados '.$iAnexados;
			$iTipoError=1;
			}
		if ($iActualizados>0){
			$sError=$sError.' - Registros actualizados '.$iActualizados;
			$iTipoError=1;
			}
		}
	return array($sError, $iTipoError, $sInfoProceso, $sDebug);
	}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------
?>