<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2019 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.22.6c martes, 15 de enero de 2019
--- 1759 ofer59estrategiaaprende
*/
function f1759_ExisteDato($datos){
	if(!is_array($datos)){$datos=json_decode(str_replace('\"','"',$datos),true);}
	$_SESSION['u_ultimominuto']=iminutoavance();
	$bHayLlave=true;
	$ofer59consec=numeros_validar($datos[1]);
	if ($ofer59consec==''){$bHayLlave=false;}
	if ($bHayLlave){
		require './app.php';
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		$sSQL='SELECT ofer59consec FROM ofer59estrategiaaprende WHERE ofer59consec='.$ofer59consec.'';
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
function f1759_Busquedas($aParametros){
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_1759=$APP->rutacomun.'lg/lg_1759_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1759)){$mensajes_1759=$APP->rutacomun.'lg/lg_1759_es.php';}
	require $mensajes_todas;
	require $mensajes_1759;
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
	$sTitulo='<h2>'.$ETI['titulo_1759'].' - '.$sTitulo.'</h2>';
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97titulo', 'innerHTML', $sTitulo);
	$objResponse->assign('div_97params', 'innerHTML', $sParams);
	$objResponse->assign('div_97tabla', 'innerHTML', $sTabla);
	$objResponse->setFunction('paginarbusqueda','',$sJavaBusqueda);
	$objResponse->call('expandesector(97)');
	return $objResponse;
	}
function f1759_HtmlBusqueda($aParametros){
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
function f1759_TablaDetalleV2($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_1759=$APP->rutacomun.'lg/lg_1759_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1759)){$mensajes_1759=$APP->rutacomun.'lg/lg_1759_es.php';}
	require $mensajes_todas;
	require $mensajes_1759;
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
	if (false){
		$sLeyenda='<div class="salto1px"></div>
<div class="GrupoCamposAyuda">
<b>Importante:</b> Mensaje al usuario
<div class="salto1px"></div>
</div>';
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
	$sTitulos='Consec, Id, Activa, Nombre, Escuela';
	$sSQL='SELECT TB.ofer59consec, TB.ofer59id, TB.ofer59activa, TB.ofer59nombre, T5.core12nombre, TB.ofer59idescuela 
FROM ofer59estrategiaaprende AS TB, core12escuela AS T5 
WHERE '.$sSQLadd1.' TB.ofer59id>0 AND TB.ofer59idescuela=T5.core12id '.$sSQLadd.'
ORDER BY TB.ofer59activa DESC, TB.ofer59nombre';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_1759" name="consulta_1759" type="hidden" value="'.$sSQLlista.'"/>
<input id="titulos_1759" name="titulos_1759" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objDB->ejecutasql($sSQL);
	if ($tabladetalle==false){
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 1759: '.$sSQL.'<br>';}
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		//$sLeyenda=$sSQL;
		}else{
		$registros=$objDB->nf($tabladetalle);
		if ($registros==0){
			//return array(utf8_encode($sErrConsulta.'<input id="paginaf1759" name="paginaf1759" type="hidden" value="'.$pagina.'"/><input id="lppf1759" name="lppf1759" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
<td><b>'.$ETI['ofer59consec'].'</b></td>
<td><b>'.$ETI['ofer59activa'].'</b></td>
<td><b>'.$ETI['ofer59nombre'].'</b></td>
<td><b>'.$ETI['ofer59idescuela'].'</b></td>
<td align="right">
'.html_paginador('paginaf1759', $registros, $lineastabla, $pagina, 'paginarf1759()').'
'.html_lpp('lppf1759', $lineastabla, 'paginarf1759()').'
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
		$et_ofer59activa=$ETI['no'];
		if ($filadet['ofer59activa']=='S'){$et_ofer59activa=$ETI['si'];}
		if ($filadet['ofer59idescuela']==0){
			$eti_ofer59idescuela='{'.$ETI['msg_todas'].'}';
			}else{
			$eti_ofer59idescuela=cadena_notildes($filadet['core12nombre']);
			}
		if ($babierta){
			$sLink='<a href="javascript:cargaridf1759('.$filadet['ofer59id'].')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$res=$res.'<tr'.$sClass.'>
<td>'.$sPrefijo.$filadet['ofer59consec'].$sSufijo.'</td>
<td>'.$sPrefijo.$et_ofer59activa.$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['ofer59nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.$eti_ofer59idescuela.$sSufijo.'</td>
<td>'.$sLink.'</td>
</tr>';
		}
	$res=$res.'</table>';
	$objDB->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
	}
function f1759_HtmlTabla($aParametros){
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
	list($sDetalle, $sDebugTabla)=f1759_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f1759detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f1759_db_CargarPadre($DATA, $objDB, $bDebug=false){
	$sError='';
	$iTipoError=0;
	$sDebug='';
	require './app.php';
	if ($DATA['paso']==1){
		$sSQLcondi='ofer59consec='.$DATA['ofer59consec'].'';
		}else{
		$sSQLcondi='ofer59id='.$DATA['ofer59id'].'';
		}
	$sSQL='SELECT * FROM ofer59estrategiaaprende WHERE '.$sSQLcondi;
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$DATA['ofer59consec']=$fila['ofer59consec'];
		$DATA['ofer59id']=$fila['ofer59id'];
		$DATA['ofer59activa']=$fila['ofer59activa'];
		$DATA['ofer59nombre']=$fila['ofer59nombre'];
		$DATA['ofer59idescuela']=$fila['ofer59idescuela'];
		$bcargo=true;
		$DATA['paso']=2;
		$DATA['boculta1759']=0;
		$bLimpiaHijos=true;
		}else{
		$DATA['paso']=0;
		}
	return array($DATA, $sError, $iTipoError, $sDebug);
	}
function f1759_db_GuardarV2($DATA, $objDB, $bDebug=false){
	$iCodModulo=1759;
	$bAudita[2]=true;
	$bAudita[3]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_1759=$APP->rutacomun.'lg/lg_1759_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1759)){$mensajes_1759=$APP->rutacomun.'lg/lg_1759_es.php';}
	require $mensajes_todas;
	require $mensajes_1759;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	// -- Se inicia validando todas las posibles entradas de usuario.
	/*
	if (isset($DATA['ofer59consec'])==0){$DATA['ofer59consec']='';}
	if (isset($DATA['ofer59id'])==0){$DATA['ofer59id']='';}
	if (isset($DATA['ofer59activa'])==0){$DATA['ofer59activa']='';}
	if (isset($DATA['ofer59nombre'])==0){$DATA['ofer59nombre']='';}
	if (isset($DATA['ofer59idescuela'])==0){$DATA['ofer59idescuela']='';}
	*/
	$DATA['ofer59consec']=numeros_validar($DATA['ofer59consec']);
	$DATA['ofer59activa']=htmlspecialchars(trim($DATA['ofer59activa']));
	$DATA['ofer59nombre']=htmlspecialchars(trim($DATA['ofer59nombre']));
	$DATA['ofer59idescuela']=numeros_validar($DATA['ofer59idescuela']);
	// -- Se inicializan las variables que puedan pasar vacias {Especialmente números}.
	//if ($DATA['ofer59idescuela']==''){$DATA['ofer59idescuela']=0;}
	// -- Seccion para validar los posibles causales de error.
	$sSepara=', ';
	if (true){
		if ($DATA['ofer59idescuela']==''){$sError=$ERR['ofer59idescuela'].$sSepara.$sError;}
		if ($DATA['ofer59nombre']==''){$sError=$ERR['ofer59nombre'].$sSepara.$sError;}
		if ($DATA['ofer59activa']==''){$sError=$ERR['ofer59activa'].$sSepara.$sError;}
		//Fin de las valiaciones NO LLAVE.
		}
	//Valiaciones de campos obligatorios en todo guardar.
	// -- Se verifican los valores de campos de otras tablas.
	if ($sError==''){
		if ($DATA['paso']==10){
			if ($DATA['ofer59consec']==''){
				$DATA['ofer59consec']=tabla_consecutivo('ofer59estrategiaaprende', 'ofer59consec', '', $objDB);
				if ($DATA['ofer59consec']==-1){$sError=$objDB->serror;}
				}else{
				if (!seg_revisa_permiso($iCodModulo, 8, $objDB)){
					$sError=$ERR['8'];
					$DATA['ofer59consec']='';
					}
				}
			if ($sError==''){
				$sSQL='SELECT ofer59consec FROM ofer59estrategiaaprende WHERE ofer59consec='.$DATA['ofer59consec'].'';
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
			$DATA['ofer59id']=tabla_consecutivo('ofer59estrategiaaprende','ofer59id', '', $objDB);
			if ($DATA['ofer59id']==-1){$sError=$objDB->serror;}
			}
		}
	if ($sError==''){
		$bpasa=false;
		if ($DATA['paso']==10){
			$sCampos1759='ofer59consec, ofer59id, ofer59activa, ofer59nombre, ofer59idescuela';
			$sValores1759=''.$DATA['ofer59consec'].', '.$DATA['ofer59id'].', "'.$DATA['ofer59activa'].'", "'.$DATA['ofer59nombre'].'", '.$DATA['ofer59idescuela'].'';
			if ($APP->utf8==1){
				$sSQL='INSERT INTO ofer59estrategiaaprende ('.$sCampos1759.') VALUES ('.utf8_encode($sValores1759).');';
				$sdetalle=$sCampos1759.'['.utf8_encode($sValores1759).']';
				}else{
				$sSQL='INSERT INTO ofer59estrategiaaprende ('.$sCampos1759.') VALUES ('.$sValores1759.');';
				$sdetalle=$sCampos1759.'['.$sValores1759.']';
				}
			$idaccion=2;
			$bpasa=true;
			}else{
			$scampo[1]='ofer59activa';
			$scampo[2]='ofer59nombre';
			$scampo[3]='ofer59idescuela';
			$sdato[1]=$DATA['ofer59activa'];
			$sdato[2]=$DATA['ofer59nombre'];
			$sdato[3]=$DATA['ofer59idescuela'];
			$numcmod=3;
			$sWhere='ofer59id='.$DATA['ofer59id'].'';
			$sSQL='SELECT * FROM ofer59estrategiaaprende WHERE '.$sWhere;
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
					$sSQL='UPDATE ofer59estrategiaaprende SET '.utf8_encode($sdatos).' WHERE '.$sWhere.';';
					}else{
					$sdetalle=$sdatos.'['.$sWhere.']';
					$sSQL='UPDATE ofer59estrategiaaprende SET '.$sdatos.' WHERE '.$sWhere.';';
					}
				$idaccion=3;
				}
			}
		if ($bpasa){
			$result=$objDB->ejecutasql($sSQL);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' [1759] ..<!-- '.$sSQL.' -->';
				if ($idaccion==2){$DATA['ofer59id']='';}
				$DATA['paso']=$DATA['paso']-10;
				}else{
				if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Guardar 1759 '.$sSQL.'<br>';}
				if ($bAudita[$idaccion]){seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], $idaccion, $DATA['ofer59id'], $sdetalle, $objDB);}
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
function f1759_db_Eliminar($ofer59id, $objDB, $bDebug=false){
	$iCodModulo=1759;
	$bAudita[4]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_1759=$APP->rutacomun.'lg/lg_1759_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1759)){$mensajes_1759=$APP->rutacomun.'lg/lg_1759_es.php';}
	require $mensajes_todas;
	require $mensajes_1759;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	$ofer59id=numeros_validar($ofer59id);
	// Traer los datos para hacer las validaciones.
	if ($sError==''){
		$sSQL='SELECT * FROM ofer59estrategiaaprende WHERE ofer59id='.$ofer59id.'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$filabase=$objDB->sf($tabla);
			}else{
			$sError='No se encuentra el registro solicitado {Ref: '.$ofer59id.'}';
			}
		}
	if ($sError==''){
		if (!seg_revisa_permiso($iCodModulo, 4, $objDB)){
			$sError=$ERR['4'];
			}
		}
	if ($sError==''){
		$sSQL='SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=1759';
		$tablaor=$objDB->ejecutasql($sSQL);
		while ($filaor=$objDB->sf($tablaor)){
			$sSQL='SELECT '.$filaor['campoid'].' FROM '.$filaor['tabla'].' WHERE '.$filaor['camporev'].'='.$_REQUEST['ofer59id'].' LIMIT 0, 1';
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
		$sWhere='ofer59id='.$ofer59id.'';
		//$sWhere='ofer59consec='.$filabase['ofer59consec'].'';
		$sSQL='DELETE FROM ofer59estrategiaaprende WHERE '.$sWhere.';';
		$result=$objDB->ejecutasql($sSQL);
		if ($result==false){
			$sError=$ERR['falla_eliminar'].' .. <!-- '.$sSQL.' -->';
			}else{
			if ($bAudita[4]){seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $ofer59id, $sWhere, $objDB);}
			}
		}
	return array($sError, $iTipoError, $sDebug);
	}
function f1759_TituloBusqueda(){
	return 'Busqueda de Estrategias de aprendizaje';
	}
function f1759_ParametrosBusqueda(){
	$sParams='<label class="Label90">Nombre</label><label><input id="b1759nombre" name="b1759nombre" type="text" value="" onchange="paginarbusqueda()" /></label>';
	return $sParams;
	}
function f1759_JavaScriptBusqueda($iModuloBusca){
	$sRes='var sCampo=window.document.frmedita.scampobusca.value;
var params=new Array();
params[100]=sCampo;
params[101]=window.document.frmedita.paginabusqueda.value;
params[102]=window.document.frmedita.lppfbusqueda.value;
params[103]=window.document.frmedita.b1759nombre.value;
xajax_f'.$iModuloBusca.'_HtmlBusqueda(params);';
	return $sRes;
	}
function f1759_TablaDetalleBusquedas($aParametros, $objDB){
	$res='';
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_1759=$APP->rutacomun.'lg/lg_1759_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1759)){$mensajes_1759=$APP->rutacomun.'lg/lg_1759_es.php';}
	require $mensajes_todas;
	require $mensajes_1759;
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
	$sTitulos='Consec, Id, Activa, Nombre, Escuela';
	$sSQL='SELECT TB.ofer59consec, TB.ofer59id, TB.ofer59activa, TB.ofer59nombre, T5.core12nombre, TB.ofer59idescuela 
FROM ofer59estrategiaaprende AS TB, core12escuela AS T5 
WHERE '.$sSQLadd1.' TB.ofer59idescuela=T5.core12id '.$sSQLadd.'
ORDER BY TB.ofer59consec';
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
			//return array(utf8_encode($sErrConsulta.'<input id="paginaf1759" name="paginaf1759" type="hidden" value="'.$pagina.'"/><input id="lppf1759" name="lppf1759" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
<td><b>'.$ETI['ofer59consec'].'</b></td>
<td><b>'.$ETI['ofer59activa'].'</b></td>
<td><b>'.$ETI['ofer59nombre'].'</b></td>
<td><b>'.$ETI['ofer59idescuela'].'</b></td>
<td align="right">
'.html_paginador('paginabusqueda', $registros, $lineastabla, $pagina, 'paginarbusqueda()').'
'.html_lpp('lppfbusqueda', $lineastabla, 'paginarbusqueda()').'
</td>
</tr>';
	$tlinea=1;
	while($filadet=$objDB->sf($tabladetalle)){
		$sPrefijo='<a href="javascript:Devuelve(\''.$filadet['ofer59id'].'\');">';
		$sSufijo='</a>';
		$tlinea++;
		$et_ofer59activa=$ETI['no'];
		if ($filadet['ofer59activa']=='S'){$et_ofer59activa=$ETI['si'];}
		$res=$res.'<tr onmouseover="cambia_color_over(this);" onmouseout="cambia_color_out(this);">
<td>'.$sPrefijo.$filadet['ofer59consec'].$sSufijo.'</td>
<td>'.$sPrefijo.$et_ofer59activa.$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['ofer59nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['core12nombre']).$sSufijo.'</td>
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