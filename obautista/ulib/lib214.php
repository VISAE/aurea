<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2019 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.23.2 lunes, 10 de junio de 2019
--- 214 unae14logcron
*/
/** Archivo lib214.php.
* Libreria 214 unae14logcron.
* @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
* @date lunes, 10 de junio de 2019
*/
function f214_ExisteDato($datos){
	if(!is_array($datos)){$datos=json_decode(str_replace('\"','"',$datos),true);}
	$_SESSION['u_ultimominuto']=iminutoavance();
	$bHayLlave=true;
	$unae14fecha=htmlspecialchars($datos[1]);
	if ($unae14fecha==''){$bHayLlave=false;}
	$unae14consec=numeros_validar($datos[2]);
	if ($unae14consec==''){$bHayLlave=false;}
	if ($bHayLlave){
		require './app.php';
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		$sSQL='SELECT unae14consec FROM unae14logcron WHERE unae14fecha="'.$unae14fecha.'" AND unae14consec='.$unae14consec.'';
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
function f214_Busquedas($aParametros){
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_214=$APP->rutacomun.'lg/lg_214_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_214)){$mensajes_214=$APP->rutacomun.'lg/lg_214_es.php';}
	require $mensajes_todas;
	require $mensajes_214;
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
	$sTitulo='<h2>'.$ETI['titulo_214'].' - '.$sTitulo.'</h2>';
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97titulo', 'innerHTML', $sTitulo);
	$objResponse->assign('div_97params', 'innerHTML', $sParams);
	$objResponse->assign('div_97tabla', 'innerHTML', $sTabla);
	$objResponse->setFunction('paginarbusqueda','',$sJavaBusqueda);
	$objResponse->call('expandesector(97)');
	return $objResponse;
	}
function f214_HtmlBusqueda($aParametros){
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
function f214_TablaDetalleV2($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_214=$APP->rutacomun.'lg/lg_214_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_214)){$mensajes_214=$APP->rutacomun.'lg/lg_214_es.php';}
	require $mensajes_todas;
	require $mensajes_214;
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	if (isset($aParametros[101])==0){$aParametros[101]=1;}
	if (isset($aParametros[102])==0){$aParametros[102]=20;}
	if (isset($aParametros[103])==0){$aParametros[103]='';}
	if (isset($aParametros[104])==0){$aParametros[104]='';}
	if (isset($aParametros[105])==0){$aParametros[105]='';}
	$iFecha=numeros_validar($aParametros[103]);
	$bDetallado=false;
	if ($aParametros[104]==1){$bDetallado=true;}
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
	$sSQLadd='1';
	$sSQLadd1='';
	//if ((int)$aParametros[103]!=-1){$sSQLadd=$sSQLadd.' AND TB.campo='.$aParametros[103];}
	if ($iFecha!=''){$sSQLadd1=$sSQLadd1.'TB.unae14fecha='.$iFecha.' AND ';}
	if ($aParametros[105]!=''){
		$sSQLadd1=$sSQLadd1.'TB.unae14id IN (SELECT T15.unae15idlogcron FROM unae15cronregistro AS T15 WHERE T15.unae15idaccion='.$aParametros[105].') AND ';
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
	$sTitulos='Fecha, Consec, Id, Minuto, Minutofin';
	$sSQL='SELECT TB.unae14fecha, TB.unae14consec, TB.unae14id, TB.unae14minuto, TB.unae14minutofin 
FROM unae14logcron AS TB 
WHERE '.$sSQLadd1.'  '.$sSQLadd.'
ORDER BY TB.unae14fecha DESC, TB.unae14minuto DESC';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_214" name="consulta_214" type="hidden" value="'.$sSQLlista.'"/>
<input id="titulos_214" name="titulos_214" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objDB->ejecutasql($sSQL);
	if ($tabladetalle==false){
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 214: '.$sSQL.'<br>';}
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		//$sLeyenda=$sSQL;
		}else{
		$registros=$objDB->nf($tabladetalle);
		if ($registros==0){
			//return array(utf8_encode($sErrConsulta.'<input id="paginaf214" name="paginaf214" type="hidden" value="'.$pagina.'"/><input id="lppf214" name="lppf214" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
<td><b>'.$ETI['unae14fecha'].'</b></td>
<td><b>'.$ETI['unae14consec'].'</b></td>
<td><b>'.$ETI['unae14minuto'].'</b></td>
<td><b>'.$ETI['unae14minutofin'].'</b></td>
<td align="right">
'.html_paginador('paginaf214', $registros, $lineastabla, $pagina, 'paginarf214()').'
'.html_lpp('lppf214', $lineastabla, 'paginarf214()').'
</td>
</tr>';
	$tlinea=1;
	while($filadet=$objDB->sf($tabladetalle)){
		$sPrefijo='';
		$sSufijo='';
		$sClass='';
		$sLink='';
		if ($bDetallado){
			$sPrefijo='<b>';
			$sSufijo='</b>';
			}
		if(($tlinea%2)==0){$sClass=' class="resaltetabla"';}
		$tlinea++;
		$et_unae14fecha='';
		if ($filadet['unae14fecha']!=0){$et_unae14fecha=fecha_desdenumero($filadet['unae14fecha']);}
		if ($babierta){
			$sLink='<a href="javascript:cargaridf214('.$filadet['unae14id'].')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$res=$res.'<tr'.$sClass.'>
<td>'.$sPrefijo.$et_unae14fecha.$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['unae14consec'].$sSufijo.'</td>
<td>'.$sPrefijo.html_TablaHoraMinDesdeNumero($filadet['unae14minuto']).$sSufijo.'</td>
<td>'.$sPrefijo.html_TablaHoraMinDesdeNumero($filadet['unae14minutofin']).$sSufijo.'</td>
<td>'.$sLink.'</td>
</tr>';
		if ($bDetallado){
			$sSQL='SELECT TB.unae15consec, T4.unae16accion, TB.unae15detalle, TB.unae15minuto, TB.unae15idaccion 
FROM unae15cronregistro AS TB, unae16cronaccion AS T4 
WHERE TB.unae15idlogcron='.$filadet['unae14id'].' AND TB.unae15idaccion=T4.unae16id 
ORDER BY TB.unae15consec';
			$tabla15=$objDB->ejecutasql($sSQL);
			while($fila15=$objDB->sf($tabla15)){
				$et_unae15idaccion=cadena_notildes($fila15['unae16accion']);
				$et_unae15detalle=cadena_notildes($fila15['unae15detalle']);
				$et_unae15minuto=html_TablaHoraMinDesdeNumero($fila15['unae15minuto']);
			$res=$res.'<tr'.$sClass.'>
<td colspan="2">'.$et_unae15idaccion.'</td>
<td>'.$et_unae15detalle.'</td>
<td>'.$et_unae15minuto.'</td>
<td></td>
</tr>';
				}
			}
		}
	$res=$res.'</table>';
	$objDB->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
	}
function f214_HtmlTabla($aParametros){
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
	list($sDetalle, $sDebugTabla)=f214_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f214detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f214_db_CargarPadre($DATA, $objDB, $bDebug=false){
	$sError='';
	$iTipoError=0;
	$sDebug='';
	require './app.php';
	if ($DATA['paso']==1){
		$sSQLcondi='unae14fecha="'.$DATA['unae14fecha'].'" AND unae14consec='.$DATA['unae14consec'].'';
		}else{
		$sSQLcondi='unae14id='.$DATA['unae14id'].'';
		}
	$sSQL='SELECT * FROM unae14logcron WHERE '.$sSQLcondi;
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$DATA['unae14fecha']=$fila['unae14fecha'];
		$DATA['unae14consec']=$fila['unae14consec'];
		$DATA['unae14id']=$fila['unae14id'];
		$DATA['unae14minuto']=$fila['unae14minuto'];
		$DATA['unae14minutofin']=$fila['unae14minutofin'];
		$bcargo=true;
		$DATA['paso']=2;
		$DATA['boculta214']=0;
		$bLimpiaHijos=true;
		}else{
		$DATA['paso']=0;
		}
	return array($DATA, $sError, $iTipoError, $sDebug);
	}
function f214_db_GuardarV2($DATA, $objDB, $bDebug=false){
	$iCodModulo=214;
	$bAudita[2]=true;
	$bAudita[3]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_214=$APP->rutacomun.'lg/lg_214_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_214)){$mensajes_214=$APP->rutacomun.'lg/lg_214_es.php';}
	require $mensajes_todas;
	require $mensajes_214;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	// -- Se inicia validando todas las posibles entradas de usuario.
	/*
	if (isset($DATA['unae14fecha'])==0){$DATA['unae14fecha']='';}
	if (isset($DATA['unae14consec'])==0){$DATA['unae14consec']='';}
	if (isset($DATA['unae14id'])==0){$DATA['unae14id']='';}
	*/
	$DATA['unae14consec']=numeros_validar($DATA['unae14consec']);
	// -- Se inicializan las variables que puedan pasar vacias {Especialmente números}.
	if ($DATA['unae14minuto']==''){$DATA['unae14minuto']=0;}
	if ($DATA['unae14minutofin']==''){$DATA['unae14minutofin']=0;}
	// -- Seccion para validar los posibles causales de error.
	$sSepara=', ';
	if (true){
		//Fin de las valiaciones NO LLAVE.
		}
	//Valiaciones de campos obligatorios en todo guardar.
	if ($DATA['unae14fecha']==0){
		//$DATA['unae14fecha']=fecha_DiaMod();
		$sError=$ERR['unae14fecha'];
		}
	// -- Se verifican los valores de campos de otras tablas.
	if ($sError==''){
		if ($DATA['paso']==10){
			if ($DATA['unae14consec']==''){
				$DATA['unae14consec']=tabla_consecutivo('unae14logcron', 'unae14consec', 'unae14fecha="'.$DATA['unae14fecha'].'"', $objDB);
				if ($DATA['unae14consec']==-1){$sError=$objDB->serror;}
				}else{
				if (!seg_revisa_permiso($iCodModulo, 8, $objDB)){
					$sError=$ERR['8'];
					$DATA['unae14consec']='';
					}
				}
			if ($sError==''){
				$sSQL='SELECT unae14fecha FROM unae14logcron WHERE unae14fecha="'.$DATA['unae14fecha'].'" AND unae14consec='.$DATA['unae14consec'].'';
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
			$DATA['unae14id']=tabla_consecutivo('unae14logcron','unae14id', '', $objDB);
			if ($DATA['unae14id']==-1){$sError=$objDB->serror;}
			}
		}
	if ($sError==''){
		$bpasa=false;
		if ($DATA['paso']==10){
			$DATA['unae14minuto']=0;
			$DATA['unae14minutofin']=0;
			$sCampos214='unae14fecha, unae14consec, unae14id, unae14minuto, unae14minutofin';
			$sValores214='"'.$DATA['unae14fecha'].'", '.$DATA['unae14consec'].', '.$DATA['unae14id'].', '.$DATA['unae14minuto'].', '.$DATA['unae14minutofin'].'';
			if ($APP->utf8==1){
				$sSQL='INSERT INTO unae14logcron ('.$sCampos214.') VALUES ('.utf8_encode($sValores214).');';
				$sdetalle=$sCampos214.'['.utf8_encode($sValores214).']';
				}else{
				$sSQL='INSERT INTO unae14logcron ('.$sCampos214.') VALUES ('.$sValores214.');';
				$sdetalle=$sCampos214.'['.$sValores214.']';
				}
			$idaccion=2;
			$bpasa=true;
			}else{
			$numcmod=0;
			$sWhere='unae14id='.$DATA['unae14id'].'';
			$sSQL='SELECT * FROM unae14logcron WHERE '.$sWhere;
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
					$sSQL='UPDATE unae14logcron SET '.utf8_encode($sdatos).' WHERE '.$sWhere.';';
					}else{
					$sdetalle=$sdatos.'['.$sWhere.']';
					$sSQL='UPDATE unae14logcron SET '.$sdatos.' WHERE '.$sWhere.';';
					}
				$idaccion=3;
				}
			}
		if ($bpasa){
			$result=$objDB->ejecutasql($sSQL);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' [214] ..<!-- '.$sSQL.' -->';
				if ($idaccion==2){$DATA['unae14id']='';}
				$DATA['paso']=$DATA['paso']-10;
				}else{
				if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Guardar 214 '.$sSQL.'<br>';}
				if ($bAudita[$idaccion]){seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], $idaccion, $DATA['unae14id'], $sdetalle, $objDB);}
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
function f214_db_Eliminar($unae14id, $objDB, $bDebug=false){
	$iCodModulo=214;
	$bAudita[4]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_214=$APP->rutacomun.'lg/lg_214_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_214)){$mensajes_214=$APP->rutacomun.'lg/lg_214_es.php';}
	require $mensajes_todas;
	require $mensajes_214;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	$unae14id=numeros_validar($unae14id);
	// Traer los datos para hacer las validaciones.
	if ($sError==''){
		$sSQL='SELECT * FROM unae14logcron WHERE unae14id='.$unae14id.'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$filabase=$objDB->sf($tabla);
			}else{
			$sError='No se encuentra el registro solicitado {Ref: '.$unae14id.'}';
			}
		}
	if ($sError==''){
		$sSQL='SELECT unae15idlogcron FROM unae15cronregistro WHERE unae15idlogcron='.$filabase['unae14id'].'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$sError='Existen Actividades ejecutadas creados, no es posible eliminar';
			}
		}
	if ($sError==''){
		if (!seg_revisa_permiso($iCodModulo, 4, $objDB)){
			$sError=$ERR['4'];
			}
		}
	if ($sError==''){
		$sSQL='SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=214';
		$tablaor=$objDB->ejecutasql($sSQL);
		while ($filaor=$objDB->sf($tablaor)){
			$sSQL='SELECT '.$filaor['campoid'].' FROM '.$filaor['tabla'].' WHERE '.$filaor['camporev'].'='.$_REQUEST['unae14id'].' LIMIT 0, 1';
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
		//$sSQL='DELETE FROM unae15cronregistro WHERE unae15idlogcron='.$filabase['unae14id'].'';
		//$tabla=$objDB->ejecutasql($sSQL);
		$sWhere='unae14id='.$unae14id.'';
		//$sWhere='unae14consec='.$filabase['unae14consec'].' AND unae14fecha="'.$filabase['unae14fecha'].'"';
		$sSQL='DELETE FROM unae14logcron WHERE '.$sWhere.';';
		$result=$objDB->ejecutasql($sSQL);
		if ($result==false){
			$sError=$ERR['falla_eliminar'].' .. <!-- '.$sSQL.' -->';
			}else{
			if ($bAudita[4]){seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $unae14id, $sWhere, $objDB);}
			}
		}
	return array($sError, $iTipoError, $sDebug);
	}
function f214_TituloBusqueda(){
	return 'Busqueda de Registro de actividades recurrentes';
	}
function f214_ParametrosBusqueda(){
	$sParams='<label class="Label90">Nombre</label><label><input id="b214nombre" name="b214nombre" type="text" value="" onchange="paginarbusqueda()" /></label>';
	return $sParams;
	}
function f214_JavaScriptBusqueda($iModuloBusca){
	$sRes='var sCampo=window.document.frmedita.scampobusca.value;
var params=new Array();
params[100]=sCampo;
params[101]=window.document.frmedita.paginabusqueda.value;
params[102]=window.document.frmedita.lppfbusqueda.value;
params[103]=window.document.frmedita.b214nombre.value;
xajax_f'.$iModuloBusca.'_HtmlBusqueda(params);';
	return $sRes;
	}
function f214_TablaDetalleBusquedas($aParametros, $objDB){
	$res='';
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_214=$APP->rutacomun.'lg/lg_214_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_214)){$mensajes_214=$APP->rutacomun.'lg/lg_214_es.php';}
	require $mensajes_todas;
	require $mensajes_214;
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
	if (false){
		$sLeyenda='<div class="salto1px"></div>
<div class="GrupoCamposAyuda">
<b>Importante:</b> Mensaje al usuario
<div class="salto1px"></div>
</div>';
		}
	$sSQLadd='1';
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
	$sTitulos='Fecha, Consec, Id, Minuto, Minutofin';
	$sSQL='SELECT TB.unae14fecha, TB.unae14consec, TB.unae14id, TB.unae14minuto, TB.unae14minutofin 
FROM unae14logcron AS TB 
WHERE '.$sSQLadd1.'  '.$sSQLadd.'
ORDER BY TB.unae14fecha, TB.unae14consec';
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
			//return array(utf8_encode($sErrConsulta.'<input id="paginaf214" name="paginaf214" type="hidden" value="'.$pagina.'"/><input id="lppf214" name="lppf214" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
<td><b>'.$ETI['unae14fecha'].'</b></td>
<td><b>'.$ETI['unae14consec'].'</b></td>
<td><b>'.$ETI['unae14minuto'].'</b></td>
<td><b>'.$ETI['unae14minutofin'].'</b></td>
<td align="right">
'.html_paginador('paginabusqueda', $registros, $lineastabla, $pagina, 'paginarbusqueda()').'
'.html_lpp('lppfbusqueda', $lineastabla, 'paginarbusqueda()').'
</td>
</tr>';
	$tlinea=1;
	while($filadet=$objDB->sf($tabladetalle)){
		$sPrefijo='<a href="javascript:Devuelve(\''.$filadet['unae14id'].'\');">';
		$sSufijo='</a>';
		$tlinea++;
		$et_unae14fecha='';
		if ($filadet['unae14fecha']!=0){$et_unae14fecha=fecha_desdenumero($filadet['unae14fecha']);}
		$res=$res.'<tr onmouseover="cambia_color_over(this);" onmouseout="cambia_color_out(this);">
<td>'.$sPrefijo.$et_unae14fecha.$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['unae14consec'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['unae14minuto'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['unae14minutofin'].$sSufijo.'</td>
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