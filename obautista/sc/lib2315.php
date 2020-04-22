<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2019 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.22.7 viernes, 1 de febrero de 2019
--- 2315 cara15factordeserta
*/
function f2315_ExisteDato($datos){
	if(!is_array($datos)){$datos=json_decode(str_replace('\"','"',$datos),true);}
	$_SESSION['u_ultimominuto']=iminutoavance();
	$bHayLlave=true;
	$cara15consec=numeros_validar($datos[1]);
	if ($cara15consec==''){$bHayLlave=false;}
	if ($bHayLlave){
		require './app.php';
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		$sSQL='SELECT cara15consec FROM cara15factordeserta WHERE cara15consec='.$cara15consec.'';
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
function f2315_Busquedas($aParametros){
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2315='lg/lg_2315_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2315)){$mensajes_2315='lg/lg_2315_es.php';}
	require $mensajes_todas;
	require $mensajes_2315;
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
	$sTitulo='<h2>'.$ETI['titulo_2315'].' - '.$sTitulo.'</h2>';
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97titulo', 'innerHTML', $sTitulo);
	$objResponse->assign('div_97params', 'innerHTML', $sParams);
	$objResponse->assign('div_97tabla', 'innerHTML', $sTabla);
	$objResponse->setFunction('paginarbusqueda','',$sJavaBusqueda);
	$objResponse->call('expandesector(97)');
	return $objResponse;
	}
function f2315_HtmlBusqueda($aParametros){
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
function f2315_TablaDetalleV2($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2315='lg/lg_2315_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2315)){$mensajes_2315='lg/lg_2315_es.php';}
	require $mensajes_todas;
	require $mensajes_2315;
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
	$sTitulos='Consec, Id, Activa, Orden, Nombre, Grupo';
	$sSQL='SELECT TB.cara15consec, TB.cara15id, TB.cara15activa, TB.cara15orden, TB.cara15nombre, T6.cara22nombre, TB.cara15grupo 
FROM cara15factordeserta AS TB, cara22gruposfactores AS T6 
WHERE '.$sSQLadd1.' TB.cara15grupo=T6.cara22id '.$sSQLadd.'
ORDER BY TB.cara15grupo, TB.cara15orden, TB.cara15nombre';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_2315" name="consulta_2315" type="hidden" value="'.$sSQLlista.'"/>
<input id="titulos_2315" name="titulos_2315" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objDB->ejecutasql($sSQL);
	if ($tabladetalle==false){
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 2315: '.$sSQL.'<br>';}
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		//$sLeyenda=$sSQL;
		}else{
		$registros=$objDB->nf($tabladetalle);
		if ($registros==0){
			//return array(utf8_encode($sErrConsulta.'<input id="paginaf2315" name="paginaf2315" type="hidden" value="'.$pagina.'"/><input id="lppf2315" name="lppf2315" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
<td><b>'.$ETI['cara15consec'].'</b></td>
<td><b>'.$ETI['cara15activa'].'</b></td>
<td><b>'.$ETI['cara15orden'].'</b></td>
<td><b>'.$ETI['cara15nombre'].'</b></td>
<td><b>'.$ETI['cara15grupo'].'</b></td>
<td align="right">
'.html_paginador('paginaf2315', $registros, $lineastabla, $pagina, 'paginarf2315()').'
'.html_lpp('lppf2315', $lineastabla, 'paginarf2315()').'
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
		$et_cara15activa=$ETI['no'];
		if ($filadet['cara15activa']=='S'){$et_cara15activa=$ETI['si'];}
		if ($babierta){
			$sLink='<a href="javascript:cargaridf2315('.$filadet['cara15id'].')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$res=$res.'<tr'.$sClass.'>
<td>'.$sPrefijo.$filadet['cara15consec'].$sSufijo.'</td>
<td>'.$sPrefijo.$et_cara15activa.$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['cara15orden'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['cara15nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['cara22nombre']).$sSufijo.'</td>
<td>'.$sLink.'</td>
</tr>';
		}
	$res=$res.'</table>';
	$objDB->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
	}
function f2315_HtmlTabla($aParametros){
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
	list($sDetalle, $sDebugTabla)=f2315_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f2315detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f2315_db_CargarPadre($DATA, $objDB, $bDebug=false){
	$sError='';
	$iTipoError=0;
	$sDebug='';
	require './app.php';
	if ($DATA['paso']==1){
		$sSQLcondi='cara15consec='.$DATA['cara15consec'].'';
		}else{
		$sSQLcondi='cara15id='.$DATA['cara15id'].'';
		}
	$sSQL='SELECT * FROM cara15factordeserta WHERE '.$sSQLcondi;
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$DATA['cara15consec']=$fila['cara15consec'];
		$DATA['cara15id']=$fila['cara15id'];
		$DATA['cara15activa']=$fila['cara15activa'];
		$DATA['cara15orden']=$fila['cara15orden'];
		$DATA['cara15nombre']=$fila['cara15nombre'];
		$DATA['cara15grupo']=$fila['cara15grupo'];
		$bcargo=true;
		$DATA['paso']=2;
		$DATA['boculta2315']=0;
		$bLimpiaHijos=true;
		}else{
		$DATA['paso']=0;
		}
	return array($DATA, $sError, $iTipoError, $sDebug);
	}
function f2315_db_GuardarV2($DATA, $objDB, $bDebug=false){
	$iCodModulo=2315;
	$bAudita[2]=true;
	$bAudita[3]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2315='lg/lg_2315_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2315)){$mensajes_2315='lg/lg_2315_es.php';}
	require $mensajes_todas;
	require $mensajes_2315;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	// -- Se inicia validando todas las posibles entradas de usuario.
	/*
	if (isset($DATA['cara15consec'])==0){$DATA['cara15consec']='';}
	if (isset($DATA['cara15id'])==0){$DATA['cara15id']='';}
	if (isset($DATA['cara15activa'])==0){$DATA['cara15activa']='';}
	if (isset($DATA['cara15orden'])==0){$DATA['cara15orden']='';}
	if (isset($DATA['cara15nombre'])==0){$DATA['cara15nombre']='';}
	if (isset($DATA['cara15grupo'])==0){$DATA['cara15grupo']='';}
	*/
	$DATA['cara15consec']=numeros_validar($DATA['cara15consec']);
	$DATA['cara15activa']=htmlspecialchars(trim($DATA['cara15activa']));
	//$DATA['cara15orden']=numeros_validar($DATA['cara15orden']);
	$DATA['cara15nombre']=htmlspecialchars(trim($DATA['cara15nombre']));
	$DATA['cara15grupo']=numeros_validar($DATA['cara15grupo']);
	// -- Se inicializan las variables que puedan pasar vacias {Especialmente números}.
	//if ($DATA['cara15orden']==''){$DATA['cara15orden']=0;}
	//if ($DATA['cara15grupo']==''){$DATA['cara15grupo']=0;}
	// -- Seccion para validar los posibles causales de error.
	$sSepara=', ';
	if (true){
		if ($DATA['cara15grupo']==''){$sError=$ERR['cara15grupo'].$sSepara.$sError;}
		if ($DATA['cara15nombre']==''){$sError=$ERR['cara15nombre'].$sSepara.$sError;}
		if ($DATA['cara15orden']==''){$sError=$ERR['cara15orden'].$sSepara.$sError;}
		if ($DATA['cara15activa']==''){$sError=$ERR['cara15activa'].$sSepara.$sError;}
		//Fin de las valiaciones NO LLAVE.
		}
	//Valiaciones de campos obligatorios en todo guardar.
	// -- Se verifican los valores de campos de otras tablas.
	if ($sError==''){
		if ($DATA['paso']==10){
			if ($DATA['cara15consec']==''){
				$DATA['cara15consec']=tabla_consecutivo('cara15factordeserta', 'cara15consec', '', $objDB);
				if ($DATA['cara15consec']==-1){$sError=$objDB->serror;}
				}else{
				if (!seg_revisa_permiso($iCodModulo, 8, $objDB)){
					$sError=$ERR['8'];
					$DATA['cara15consec']='';
					}
				}
			if ($sError==''){
				$sSQL='SELECT cara15consec FROM cara15factordeserta WHERE cara15consec='.$DATA['cara15consec'].'';
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
			$DATA['cara15id']=tabla_consecutivo('cara15factordeserta','cara15id', '', $objDB);
			if ($DATA['cara15id']==-1){$sError=$objDB->serror;}
			}
		}
	if ($sError==''){
		if ($DATA['cara15orden']==''){$DATA['cara15orden']=$DATA['cara15consec'];}
		$bpasa=false;
		if ($DATA['paso']==10){
			$sCampos2315='cara15consec, cara15id, cara15activa, cara15orden, cara15nombre, cara15grupo';
			$sValores2315=''.$DATA['cara15consec'].', '.$DATA['cara15id'].', "'.$DATA['cara15activa'].'", '.$DATA['cara15orden'].', "'.$DATA['cara15nombre'].'", '.$DATA['cara15grupo'].'';
			if ($APP->utf8==1){
				$sSQL='INSERT INTO cara15factordeserta ('.$sCampos2315.') VALUES ('.utf8_encode($sValores2315).');';
				$sdetalle=$sCampos2315.'['.utf8_encode($sValores2315).']';
				}else{
				$sSQL='INSERT INTO cara15factordeserta ('.$sCampos2315.') VALUES ('.$sValores2315.');';
				$sdetalle=$sCampos2315.'['.$sValores2315.']';
				}
			$idaccion=2;
			$bpasa=true;
			}else{
			$scampo[1]='cara15activa';
			$scampo[2]='cara15orden';
			$scampo[3]='cara15nombre';
			$scampo[4]='cara15grupo';
			$sdato[1]=$DATA['cara15activa'];
			$sdato[2]=$DATA['cara15orden'];
			$sdato[3]=$DATA['cara15nombre'];
			$sdato[4]=$DATA['cara15grupo'];
			$numcmod=4;
			$sWhere='cara15id='.$DATA['cara15id'].'';
			$sSQL='SELECT * FROM cara15factordeserta WHERE '.$sWhere;
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
					$sSQL='UPDATE cara15factordeserta SET '.utf8_encode($sdatos).' WHERE '.$sWhere.';';
					}else{
					$sdetalle=$sdatos.'['.$sWhere.']';
					$sSQL='UPDATE cara15factordeserta SET '.$sdatos.' WHERE '.$sWhere.';';
					}
				$idaccion=3;
				}
			}
		if ($bpasa){
			$result=$objDB->ejecutasql($sSQL);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' [2315] ..<!-- '.$sSQL.' -->';
				if ($idaccion==2){$DATA['cara15id']='';}
				$DATA['paso']=$DATA['paso']-10;
				}else{
				if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Guardar 2315 '.$sSQL.'<br>';}
				if ($bAudita[$idaccion]){seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], $idaccion, $DATA['cara15id'], $sdetalle, $objDB);}
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
function f2315_db_Eliminar($cara15id, $objDB, $bDebug=false){
	$iCodModulo=2315;
	$bAudita[4]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2315='lg/lg_2315_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2315)){$mensajes_2315='lg/lg_2315_es.php';}
	require $mensajes_todas;
	require $mensajes_2315;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	$cara15id=numeros_validar($cara15id);
	// Traer los datos para hacer las validaciones.
	if ($sError==''){
		$sSQL='SELECT * FROM cara15factordeserta WHERE cara15id='.$cara15id.'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$filabase=$objDB->sf($tabla);
			}else{
			$sError='No se encuentra el registro solicitado {Ref: '.$cara15id.'}';
			}
		}
	if ($sError==''){
		if (!seg_revisa_permiso($iCodModulo, 4, $objDB)){
			$sError=$ERR['4'];
			}
		}
	if ($sError==''){
		$sSQL='SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=2315';
		$tablaor=$objDB->ejecutasql($sSQL);
		while ($filaor=$objDB->sf($tablaor)){
			$sSQL='SELECT '.$filaor['campoid'].' FROM '.$filaor['tabla'].' WHERE '.$filaor['camporev'].'='.$_REQUEST['cara15id'].' LIMIT 0, 1';
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
		$sWhere='cara15id='.$cara15id.'';
		//$sWhere='cara15consec='.$filabase['cara15consec'].'';
		$sSQL='DELETE FROM cara15factordeserta WHERE '.$sWhere.';';
		$result=$objDB->ejecutasql($sSQL);
		if ($result==false){
			$sError=$ERR['falla_eliminar'].' .. <!-- '.$sSQL.' -->';
			}else{
			if ($bAudita[4]){seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $cara15id, $sWhere, $objDB);}
			}
		}
	return array($sError, $iTipoError, $sDebug);
	}
function f2315_TituloBusqueda(){
	return 'Busqueda de Factores de desercion';
	}
function f2315_ParametrosBusqueda(){
	$sParams='<label class="Label90">Nombre</label><label><input id="b2315nombre" name="b2315nombre" type="text" value="" onchange="paginarbusqueda()" /></label>';
	return $sParams;
	}
function f2315_JavaScriptBusqueda($iModuloBusca){
	$sRes='var sCampo=window.document.frmedita.scampobusca.value;
var params=new Array();
params[100]=sCampo;
params[101]=window.document.frmedita.paginabusqueda.value;
params[102]=window.document.frmedita.lppfbusqueda.value;
params[103]=window.document.frmedita.b2315nombre.value;
xajax_f'.$iModuloBusca.'_HtmlBusqueda(params);';
	return $sRes;
	}
function f2315_TablaDetalleBusquedas($aParametros, $objDB){
	$res='';
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2315='lg/lg_2315_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2315)){$mensajes_2315='lg/lg_2315_es.php';}
	require $mensajes_todas;
	require $mensajes_2315;
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
	$sTitulos='Consec, Id, Activa, Orden, Nombre, Grupo';
	$sSQL='SELECT TB.cara15consec, TB.cara15id, TB.cara15activa, TB.cara15orden, TB.cara15nombre, T6.cara22nombre, TB.cara15grupo 
FROM cara15factordeserta AS TB, cara22gruposfactores AS T6 
WHERE '.$sSQLadd1.' TB.cara15grupo=T6.cara22id '.$sSQLadd.'
ORDER BY TB.cara15consec';
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
			//return array(utf8_encode($sErrConsulta.'<input id="paginaf2315" name="paginaf2315" type="hidden" value="'.$pagina.'"/><input id="lppf2315" name="lppf2315" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
<td><b>'.$ETI['cara15consec'].'</b></td>
<td><b>'.$ETI['cara15activa'].'</b></td>
<td><b>'.$ETI['cara15orden'].'</b></td>
<td><b>'.$ETI['cara15nombre'].'</b></td>
<td><b>'.$ETI['cara15grupo'].'</b></td>
<td align="right">
'.html_paginador('paginabusqueda', $registros, $lineastabla, $pagina, 'paginarbusqueda()').'
'.html_lpp('lppfbusqueda', $lineastabla, 'paginarbusqueda()').'
</td>
</tr>';
	$tlinea=1;
	while($filadet=$objDB->sf($tabladetalle)){
		$sPrefijo='<a href="javascript:Devuelve(\''.$filadet['cara15id'].'\');">';
		$sSufijo='</a>';
		$tlinea++;
		$et_cara15activa=$ETI['no'];
		if ($filadet['cara15activa']=='S'){$et_cara15activa=$ETI['si'];}
		$res=$res.'<tr onmouseover="cambia_color_over(this);" onmouseout="cambia_color_out(this);">
<td>'.$sPrefijo.$filadet['cara15consec'].$sSufijo.'</td>
<td>'.$sPrefijo.$et_cara15activa.$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['cara15orden'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['cara15nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['cara22nombre']).$sSufijo.'</td>
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