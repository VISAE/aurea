<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2019 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.23.6 Wednesday, September 18, 2019
--- 2325 cara25accionescat
*/
/** Archivo lib2325.php.
* Libreria 2325 cara25accionescat.
* @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
* @date Wednesday, September 18, 2019
*/
function f2325_ExisteDato($datos){
	if(!is_array($datos)){$datos=json_decode(str_replace('\"','"',$datos),true);}
	$_SESSION['u_ultimominuto']=iminutoavance();
	$bHayLlave=true;
	$cara25consec=numeros_validar($datos[1]);
	if ($cara25consec==''){$bHayLlave=false;}
	if ($bHayLlave){
		require './app.php';
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		$sSQL='SELECT cara25consec FROM cara25accionescat WHERE cara25consec='.$cara25consec.'';
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
function f2325_Busquedas($aParametros){
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2325='lg/lg_2325_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2325)){$mensajes_2325='lg/lg_2325_es.php';}
	require $mensajes_todas;
	require $mensajes_2325;
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
	$sTitulo='<h2>'.$ETI['titulo_2325'].' - '.$sTitulo.'</h2>';
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97titulo', 'innerHTML', $sTitulo);
	$objResponse->assign('div_97params', 'innerHTML', $sParams);
	$objResponse->assign('div_97tabla', 'innerHTML', $sTabla);
	$objResponse->setFunction('paginarbusqueda','',$sJavaBusqueda);
	$objResponse->call('expandesector(97)');
	return $objResponse;
	}
function f2325_HtmlBusqueda($aParametros){
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
function f2325_TablaDetalleV2($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2325='lg/lg_2325_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2325)){$mensajes_2325='lg/lg_2325_es.php';}
	require $mensajes_todas;
	require $mensajes_2325;
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
		return array($sLeyenda.'<input id="paginaf2325" name="paginaf2325" type="hidden" value="'.$pagina.'"/><input id="lppf2325" name="lppf2325" type="hidden" value="'.$lineastabla.'"/>', $sDebug);
		die();
		}
	$sSQLadd='1';
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
	$sTitulos='Consec, Id, Orden, Activa, Titulo, Puntaje';
	$sSQL='SELECT TB.cara25consec, TB.cara25id, TB.cara25orden, TB.cara25activa, TB.cara25titulo, TB.cara25puntaje 
FROM cara25accionescat AS TB 
WHERE '.$sSQLadd1.'  '.$sSQLadd.'
ORDER BY TB.cara25orden, TB.cara25titulo';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_2325" name="consulta_2325" type="hidden" value="'.$sSQLlista.'"/>
<input id="titulos_2325" name="titulos_2325" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objDB->ejecutasql($sSQL);
	if ($tabladetalle==false){
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 2325: '.$sSQL.'<br>';}
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		//$sLeyenda=$sSQL;
		}else{
		$registros=$objDB->nf($tabladetalle);
		if ($registros==0){
			//return array(utf8_encode($sErrConsulta.'<input id="paginaf2325" name="paginaf2325" type="hidden" value="'.$pagina.'"/><input id="lppf2325" name="lppf2325" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
<td><b>'.$ETI['cara25consec'].'</b></td>
<td><b>'.$ETI['cara25orden'].'</b></td>
<td><b>'.$ETI['cara25activa'].'</b></td>
<td><b>'.$ETI['cara25titulo'].'</b></td>
<td><b>'.$ETI['cara25puntaje'].'</b></td>
<td align="right">
'.html_paginador('paginaf2325', $registros, $lineastabla, $pagina, 'paginarf2325()').'
'.html_lpp('lppf2325', $lineastabla, 'paginarf2325()').'
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
		$et_cara25activa=$ETI['no'];
		if ($filadet['cara25activa']=='S'){$et_cara25activa=$ETI['si'];}
		if ($babierta){
			$sLink='<a href="javascript:cargaridf2325('.$filadet['cara25id'].')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$res=$res.'<tr'.$sClass.'>
<td>'.$sPrefijo.$filadet['cara25consec'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['cara25orden'].$sSufijo.'</td>
<td>'.$sPrefijo.$et_cara25activa.$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['cara25titulo']).$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['cara25puntaje'].$sSufijo.'</td>
<td>'.$sLink.'</td>
</tr>';
		}
	$res=$res.'</table>';
	$objDB->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
	}
function f2325_HtmlTabla($aParametros){
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
	list($sDetalle, $sDebugTabla)=f2325_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f2325detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f2325_db_CargarPadre($DATA, $objDB, $bDebug=false){
	$sError='';
	$iTipoError=0;
	$sDebug='';
	require './app.php';
	if ($DATA['paso']==1){
		$sSQLcondi='cara25consec='.$DATA['cara25consec'].'';
		}else{
		$sSQLcondi='cara25id='.$DATA['cara25id'].'';
		}
	$sSQL='SELECT * FROM cara25accionescat WHERE '.$sSQLcondi;
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$DATA['cara25consec']=$fila['cara25consec'];
		$DATA['cara25id']=$fila['cara25id'];
		$DATA['cara25orden']=$fila['cara25orden'];
		$DATA['cara25activa']=$fila['cara25activa'];
		$DATA['cara25titulo']=$fila['cara25titulo'];
		$DATA['cara25puntaje']=$fila['cara25puntaje'];
		$bcargo=true;
		$DATA['paso']=2;
		$DATA['boculta2325']=0;
		$bLimpiaHijos=true;
		}else{
		$DATA['paso']=0;
		}
	return array($DATA, $sError, $iTipoError, $sDebug);
	}
function f2325_db_GuardarV2($DATA, $objDB, $bDebug=false){
	$iCodModulo=2325;
	$bAudita[2]=true;
	$bAudita[3]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2325='lg/lg_2325_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2325)){$mensajes_2325='lg/lg_2325_es.php';}
	require $mensajes_todas;
	require $mensajes_2325;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	// -- Se inicia validando todas las posibles entradas de usuario.
	/*
	if (isset($DATA['cara25consec'])==0){$DATA['cara25consec']='';}
	if (isset($DATA['cara25id'])==0){$DATA['cara25id']='';}
	if (isset($DATA['cara25orden'])==0){$DATA['cara25orden']='';}
	if (isset($DATA['cara25activa'])==0){$DATA['cara25activa']='';}
	if (isset($DATA['cara25titulo'])==0){$DATA['cara25titulo']='';}
	if (isset($DATA['cara25puntaje'])==0){$DATA['cara25puntaje']='';}
	*/
	$DATA['cara25consec']=numeros_validar($DATA['cara25consec']);
	$DATA['cara25orden']=numeros_validar($DATA['cara25orden']);
	$DATA['cara25activa']=htmlspecialchars(trim($DATA['cara25activa']));
	$DATA['cara25titulo']=htmlspecialchars(trim($DATA['cara25titulo']));
	$DATA['cara25puntaje']=numeros_validar($DATA['cara25puntaje']);
	// -- Se inicializan las variables que puedan pasar vacias {Especialmente números}.
	//if ($DATA['cara25orden']==''){$DATA['cara25orden']=0;}
	if ($DATA['cara25puntaje']==''){$DATA['cara25puntaje']=0;}
	// -- Seccion para validar los posibles causales de error.
	$sSepara=', ';
	if (true){
		if ($DATA['cara25titulo']==''){$sError=$ERR['cara25titulo'].$sSepara.$sError;}
		if ($DATA['cara25activa']==''){$sError=$ERR['cara25activa'].$sSepara.$sError;}
		//if ($DATA['cara25orden']==''){$sError=$ERR['cara25orden'].$sSepara.$sError;}
		//Fin de las valiaciones NO LLAVE.
		}
	//Valiaciones de campos obligatorios en todo guardar.
	// -- Se verifican los valores de campos de otras tablas.
	if ($sError==''){
		if ($DATA['paso']==10){
			if ($DATA['cara25consec']==''){
				$DATA['cara25consec']=tabla_consecutivo('cara25accionescat', 'cara25consec', '', $objDB);
				if ($DATA['cara25consec']==-1){$sError=$objDB->serror;}
				}else{
				if (!seg_revisa_permiso($iCodModulo, 8, $objDB)){
					$sError=$ERR['8'];
					$DATA['cara25consec']='';
					}
				}
			if ($sError==''){
				$sSQL='SELECT cara25consec FROM cara25accionescat WHERE cara25consec='.$DATA['cara25consec'].'';
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
			$DATA['cara25id']=tabla_consecutivo('cara25accionescat','cara25id', '', $objDB);
			if ($DATA['cara25id']==-1){$sError=$objDB->serror;}
			}
		}
	if ($sError==''){
		if ($DATA['cara25orden']==''){$DATA['cara25orden']=$DATA['cara25consec'];}
		$bpasa=false;
		if ($DATA['paso']==10){
			$sCampos2325='cara25consec, cara25id, cara25orden, cara25activa, cara25titulo, cara25puntaje';
			$sValores2325=''.$DATA['cara25consec'].', '.$DATA['cara25id'].', '.$DATA['cara25orden'].', "'.$DATA['cara25activa'].'", "'.$DATA['cara25titulo'].'", '.$DATA['cara25puntaje'].'';
			if ($APP->utf8==1){
				$sSQL='INSERT INTO cara25accionescat ('.$sCampos2325.') VALUES ('.utf8_encode($sValores2325).');';
				$sdetalle=$sCampos2325.'['.utf8_encode($sValores2325).']';
				}else{
				$sSQL='INSERT INTO cara25accionescat ('.$sCampos2325.') VALUES ('.$sValores2325.');';
				$sdetalle=$sCampos2325.'['.$sValores2325.']';
				}
			$idaccion=2;
			$bpasa=true;
			}else{
			$scampo[1]='cara25orden';
			$scampo[2]='cara25activa';
			$scampo[3]='cara25titulo';
			$scampo[4]='cara25puntaje';
			$sdato[1]=$DATA['cara25orden'];
			$sdato[2]=$DATA['cara25activa'];
			$sdato[3]=$DATA['cara25titulo'];
			$sdato[4]=$DATA['cara25puntaje'];
			$numcmod=4;
			$sWhere='cara25id='.$DATA['cara25id'].'';
			$sSQL='SELECT * FROM cara25accionescat WHERE '.$sWhere;
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
					$sSQL='UPDATE cara25accionescat SET '.utf8_encode($sdatos).' WHERE '.$sWhere.';';
					}else{
					$sdetalle=$sdatos.'['.$sWhere.']';
					$sSQL='UPDATE cara25accionescat SET '.$sdatos.' WHERE '.$sWhere.';';
					}
				$idaccion=3;
				}
			}
		if ($bpasa){
			$result=$objDB->ejecutasql($sSQL);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' [2325] ..<!-- '.$sSQL.' -->';
				if ($idaccion==2){$DATA['cara25id']='';}
				$DATA['paso']=$DATA['paso']-10;
				}else{
				if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Guardar 2325 '.$sSQL.'<br>';}
				if ($bAudita[$idaccion]){seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], $idaccion, $DATA['cara25id'], $sdetalle, $objDB);}
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
function f2325_db_Eliminar($cara25id, $objDB, $bDebug=false){
	$iCodModulo=2325;
	$bAudita[4]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2325='lg/lg_2325_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2325)){$mensajes_2325='lg/lg_2325_es.php';}
	require $mensajes_todas;
	require $mensajes_2325;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	$cara25id=numeros_validar($cara25id);
	// Traer los datos para hacer las validaciones.
	if ($sError==''){
		$sSQL='SELECT * FROM cara25accionescat WHERE cara25id='.$cara25id.'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$filabase=$objDB->sf($tabla);
			}else{
			$sError='No se encuentra el registro solicitado {Ref: '.$cara25id.'}';
			}
		}
	if ($sError==''){
		if (!seg_revisa_permiso($iCodModulo, 4, $objDB)){
			$sError=$ERR['4'];
			}
		}
	if ($sError==''){
		$sSQL='SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=2325';
		$tablaor=$objDB->ejecutasql($sSQL);
		while ($filaor=$objDB->sf($tablaor)){
			$sSQL='SELECT '.$filaor['campoid'].' FROM '.$filaor['tabla'].' WHERE '.$filaor['camporev'].'='.$_REQUEST['cara25id'].' LIMIT 0, 1';
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
		$sWhere='cara25id='.$cara25id.'';
		//$sWhere='cara25consec='.$filabase['cara25consec'].'';
		$sSQL='DELETE FROM cara25accionescat WHERE '.$sWhere.';';
		$result=$objDB->ejecutasql($sSQL);
		if ($result==false){
			$sError=$ERR['falla_eliminar'].' .. <!-- '.$sSQL.' -->';
			}else{
			if ($bAudita[4]){seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $cara25id, $sWhere, $objDB);}
			}
		}
	return array($sError, $iTipoError, $sDebug);
	}
function f2325_TituloBusqueda(){
	return 'Busqueda de Acciones catedra';
	}
function f2325_ParametrosBusqueda(){
	$sParams='<label class="Label90">Nombre</label><label><input id="b2325nombre" name="b2325nombre" type="text" value="" onchange="paginarbusqueda()" /></label>';
	return $sParams;
	}
function f2325_JavaScriptBusqueda($iModuloBusca){
	$sRes='var sCampo=window.document.frmedita.scampobusca.value;
var params=new Array();
params[100]=sCampo;
params[101]=window.document.frmedita.paginabusqueda.value;
params[102]=window.document.frmedita.lppfbusqueda.value;
params[103]=window.document.frmedita.b2325nombre.value;
xajax_f'.$iModuloBusca.'_HtmlBusqueda(params);';
	return $sRes;
	}
function f2325_TablaDetalleBusquedas($aParametros, $objDB){
	$res='';
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2325='lg/lg_2325_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2325)){$mensajes_2325='lg/lg_2325_es.php';}
	require $mensajes_todas;
	require $mensajes_2325;
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
		return array($sLeyenda.'<input id="paginaf2325" name="paginaf2325" type="hidden" value="'.$pagina.'"/><input id="lppf2325" name="lppf2325" type="hidden" value="'.$lineastabla.'"/>', $sDebug);
		die();
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
	$sTitulos='Consec, Id, Orden, Activa, Titulo';
	$sSQL='SELECT TB.cara25consec, TB.cara25id, TB.cara25orden, TB.cara25activa, TB.cara25titulo 
FROM cara25accionescat AS TB 
WHERE '.$sSQLadd1.'  '.$sSQLadd.'
ORDER BY TB.cara25consec';
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
			//return array(utf8_encode($sErrConsulta.'<input id="paginaf2325" name="paginaf2325" type="hidden" value="'.$pagina.'"/><input id="lppf2325" name="lppf2325" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
<td><b>'.$ETI['cara25consec'].'</b></td>
<td><b>'.$ETI['cara25orden'].'</b></td>
<td><b>'.$ETI['cara25activa'].'</b></td>
<td><b>'.$ETI['cara25titulo'].'</b></td>
<td align="right">
'.html_paginador('paginabusqueda', $registros, $lineastabla, $pagina, 'paginarbusqueda()').'
'.html_lpp('lppfbusqueda', $lineastabla, 'paginarbusqueda()').'
</td>
</tr>';
	$tlinea=1;
	while($filadet=$objDB->sf($tabladetalle)){
		$sPrefijo='<a href="javascript:Devuelve(\''.$filadet['cara25id'].'\');">';
		$sSufijo='</a>';
		$tlinea++;
		$et_cara25activa=$ETI['no'];
		if ($filadet['cara25activa']=='S'){$et_cara25activa=$ETI['si'];}
		$res=$res.'<tr onmouseover="cambia_color_over(this);" onmouseout="cambia_color_out(this);">
<td>'.$sPrefijo.$filadet['cara25consec'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['cara25orden'].$sSufijo.'</td>
<td>'.$sPrefijo.$et_cara25activa.$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['cara25titulo']).$sSufijo.'</td>
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