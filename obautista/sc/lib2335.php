<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2019 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.24.0 Thursday, December 19, 2019
--- 2335 cara35factorpermanece
*/
/** Archivo lib2335.php.
* Libreria 2335 cara35factorpermanece.
* @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
* @date Thursday, December 19, 2019
*/
function f2335_ExisteDato($datos){
	if(!is_array($datos)){$datos=json_decode(str_replace('\"','"',$datos),true);}
	$_SESSION['u_ultimominuto']=iminutoavance();
	$bHayLlave=true;
	$cara35consec=numeros_validar($datos[1]);
	if ($cara35consec==''){$bHayLlave=false;}
	if ($bHayLlave){
		require './app.php';
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		$sSQL='SELECT cara35consec FROM cara35factorpermanece WHERE cara35consec='.$cara35consec.'';
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
function f2335_Busquedas($aParametros){
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2335='lg/lg_2335_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2335)){$mensajes_2335='lg/lg_2335_es.php';}
	require $mensajes_todas;
	require $mensajes_2335;
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
	$sTitulo='<h2>'.$ETI['titulo_2335'].' - '.$sTitulo.'</h2>';
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97titulo', 'innerHTML', $sTitulo);
	$objResponse->assign('div_97params', 'innerHTML', $sParams);
	$objResponse->assign('div_97tabla', 'innerHTML', $sTabla);
	$objResponse->setFunction('paginarbusqueda','',$sJavaBusqueda);
	$objResponse->call('expandesector(97)');
	return $objResponse;
	}
function f2335_HtmlBusqueda($aParametros){
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
function f2335_TablaDetalleV2($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2335='lg/lg_2335_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2335)){$mensajes_2335='lg/lg_2335_es.php';}
	require $mensajes_todas;
	require $mensajes_2335;
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
		return array($sLeyenda.'<input id="paginaf2335" name="paginaf2335" type="hidden" value="'.$pagina.'"/><input id="lppf2335" name="lppf2335" type="hidden" value="'.$lineastabla.'"/>', $sDebug);
		die();
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
	$sSQL='SELECT TB.cara35consec, TB.cara35id, TB.cara35activa, TB.cara35orden, TB.cara35nombre, T6.cara22nombre, TB.cara35grupo 
FROM cara35factorpermanece AS TB, cara22gruposfactores AS T6 
WHERE '.$sSQLadd1.' TB.cara35id>0 AND TB.cara35grupo=T6.cara22id '.$sSQLadd.'
ORDER BY TB.cara35activa DESC, TB.cara35orden, TB.cara35nombre';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_2335" name="consulta_2335" type="hidden" value="'.$sSQLlista.'"/>
<input id="titulos_2335" name="titulos_2335" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objDB->ejecutasql($sSQL);
	if ($tabladetalle==false){
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 2335: '.$sSQL.'<br>';}
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		//$sLeyenda=$sSQL;
		}else{
		$registros=$objDB->nf($tabladetalle);
		if ($registros==0){
			//return array(utf8_encode($sErrConsulta.'<input id="paginaf2335" name="paginaf2335" type="hidden" value="'.$pagina.'"/><input id="lppf2335" name="lppf2335" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
<td><b>'.$ETI['cara35consec'].'</b></td>
<td><b>'.$ETI['cara35activa'].'</b></td>
<td><b>'.$ETI['cara35orden'].'</b></td>
<td><b>'.$ETI['cara35nombre'].'</b></td>
<td><b>'.$ETI['cara35grupo'].'</b></td>
<td align="right">
'.html_paginador('paginaf2335', $registros, $lineastabla, $pagina, 'paginarf2335()').'
'.html_lpp('lppf2335', $lineastabla, 'paginarf2335()').'
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
		$et_cara35activa=$ETI['no'];
		if ($filadet['cara35activa']=='S'){$et_cara35activa=$ETI['si'];}
		if ($babierta){
			$sLink='<a href="javascript:cargaridf2335('.$filadet['cara35id'].')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$res=$res.'<tr'.$sClass.'>
<td>'.$sPrefijo.$filadet['cara35consec'].$sSufijo.'</td>
<td>'.$sPrefijo.$et_cara35activa.$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['cara35orden'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['cara35nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['cara22nombre']).$sSufijo.'</td>
<td>'.$sLink.'</td>
</tr>';
		}
	$res=$res.'</table>';
	$objDB->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
	}
function f2335_HtmlTabla($aParametros){
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
	list($sDetalle, $sDebugTabla)=f2335_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f2335detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f2335_db_CargarPadre($DATA, $objDB, $bDebug=false){
	$sError='';
	$iTipoError=0;
	$sDebug='';
	require './app.php';
	if ($DATA['paso']==1){
		$sSQLcondi='cara35consec='.$DATA['cara35consec'].'';
		}else{
		$sSQLcondi='cara35id='.$DATA['cara35id'].'';
		}
	$sSQL='SELECT * FROM cara35factorpermanece WHERE '.$sSQLcondi;
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$DATA['cara35consec']=$fila['cara35consec'];
		$DATA['cara35id']=$fila['cara35id'];
		$DATA['cara35activa']=$fila['cara35activa'];
		$DATA['cara35orden']=$fila['cara35orden'];
		$DATA['cara35nombre']=$fila['cara35nombre'];
		$DATA['cara35grupo']=$fila['cara35grupo'];
		$bcargo=true;
		$DATA['paso']=2;
		$DATA['boculta2335']=0;
		$bLimpiaHijos=true;
		}else{
		$DATA['paso']=0;
		}
	return array($DATA, $sError, $iTipoError, $sDebug);
	}
function f2335_db_GuardarV2($DATA, $objDB, $bDebug=false){
	$iCodModulo=2335;
	$bAudita[2]=true;
	$bAudita[3]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2335='lg/lg_2335_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2335)){$mensajes_2335='lg/lg_2335_es.php';}
	require $mensajes_todas;
	require $mensajes_2335;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	// -- Se inicia validando todas las posibles entradas de usuario.
	/*
	if (isset($DATA['cara35consec'])==0){$DATA['cara35consec']='';}
	if (isset($DATA['cara35id'])==0){$DATA['cara35id']='';}
	if (isset($DATA['cara35activa'])==0){$DATA['cara35activa']='';}
	if (isset($DATA['cara35orden'])==0){$DATA['cara35orden']='';}
	if (isset($DATA['cara35nombre'])==0){$DATA['cara35nombre']='';}
	if (isset($DATA['cara35grupo'])==0){$DATA['cara35grupo']='';}
	*/
	$DATA['cara35consec']=numeros_validar($DATA['cara35consec']);
	$DATA['cara35activa']=htmlspecialchars(trim($DATA['cara35activa']));
	$DATA['cara35orden']=numeros_validar($DATA['cara35orden']);
	$DATA['cara35nombre']=htmlspecialchars(trim($DATA['cara35nombre']));
	$DATA['cara35grupo']=numeros_validar($DATA['cara35grupo']);
	// -- Se inicializan las variables que puedan pasar vacias {Especialmente números}.
	//if ($DATA['cara35orden']==''){$DATA['cara35orden']=0;}
	//if ($DATA['cara35grupo']==''){$DATA['cara35grupo']=0;}
	// -- Seccion para validar los posibles causales de error.
	$sSepara=', ';
	if (true){
		if ($DATA['cara35grupo']==''){$sError=$ERR['cara35grupo'].$sSepara.$sError;}
		if ($DATA['cara35nombre']==''){$sError=$ERR['cara35nombre'].$sSepara.$sError;}
		//if ($DATA['cara35orden']==''){$sError=$ERR['cara35orden'].$sSepara.$sError;}
		if ($DATA['cara35activa']==''){$sError=$ERR['cara35activa'].$sSepara.$sError;}
		//Fin de las valiaciones NO LLAVE.
		}
	//Valiaciones de campos obligatorios en todo guardar.
	// -- Se verifican los valores de campos de otras tablas.
	$bQuitarCodigo=false;
	if ($sError==''){
		if ($DATA['paso']==10){
			if ($DATA['cara35consec']==''){
				$DATA['cara35consec']=tabla_consecutivo('cara35factorpermanece', 'cara35consec', '', $objDB);
				if ($DATA['cara35consec']==-1){$sError=$objDB->serror;}
				$bQuitarCodigo=true;
				}else{
				if (!seg_revisa_permiso($iCodModulo, 8, $objDB)){
					$sError=$ERR['8'];
					$DATA['cara35consec']='';
					}
				}
			if ($sError==''){
				$sSQL='SELECT 1 FROM cara35factorpermanece WHERE cara35consec='.$DATA['cara35consec'].'';
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
			$DATA['cara35id']=tabla_consecutivo('cara35factorpermanece','cara35id', '', $objDB);
			if ($DATA['cara35id']==-1){$sError=$objDB->serror;}
			}
		}
	if ($sError==''){
		if ($DATA['cara35orden']==''){$DATA['cara35orden']=$DATA['cara35consec'];}
		$bpasa=false;
		if ($DATA['paso']==10){
			$sCampos2335='cara35consec, cara35id, cara35activa, cara35orden, cara35nombre, cara35grupo';
			$sValores2335=''.$DATA['cara35consec'].', '.$DATA['cara35id'].', "'.$DATA['cara35activa'].'", '.$DATA['cara35orden'].', "'.$DATA['cara35nombre'].'", '.$DATA['cara35grupo'].'';
			if ($APP->utf8==1){
				$sSQL='INSERT INTO cara35factorpermanece ('.$sCampos2335.') VALUES ('.utf8_encode($sValores2335).');';
				$sdetalle=$sCampos2335.'['.utf8_encode($sValores2335).']';
				}else{
				$sSQL='INSERT INTO cara35factorpermanece ('.$sCampos2335.') VALUES ('.$sValores2335.');';
				$sdetalle=$sCampos2335.'['.$sValores2335.']';
				}
			$idaccion=2;
			$bpasa=true;
			}else{
			$scampo[1]='cara35activa';
			$scampo[2]='cara35orden';
			$scampo[3]='cara35nombre';
			$scampo[4]='cara35grupo';
			$sdato[1]=$DATA['cara35activa'];
			$sdato[2]=$DATA['cara35orden'];
			$sdato[3]=$DATA['cara35nombre'];
			$sdato[4]=$DATA['cara35grupo'];
			$numcmod=4;
			$sWhere='cara35id='.$DATA['cara35id'].'';
			$sSQL='SELECT * FROM cara35factorpermanece WHERE '.$sWhere;
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
					$sSQL='UPDATE cara35factorpermanece SET '.utf8_encode($sdatos).' WHERE '.$sWhere.';';
					}else{
					$sdetalle=$sdatos.'['.$sWhere.']';
					$sSQL='UPDATE cara35factorpermanece SET '.$sdatos.' WHERE '.$sWhere.';';
					}
				$idaccion=3;
				}
			}
		if ($bpasa){
			$result=$objDB->ejecutasql($sSQL);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' [2335] ..<!-- '.$sSQL.' -->';
				if ($idaccion==2){
					$DATA['cara35id']='';
					$bQuitarCodigo=true;
					$DATA['paso']=0;
					}else{
					$DATA['paso']=2;
					}
				}else{
				if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Guardar 2335 '.$sSQL.'<br>';}
				if ($bAudita[$idaccion]){seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], $idaccion, $DATA['cara35id'], $sdetalle, $objDB);}
				$DATA['paso']=2;
				}
			}else{
			$DATA['paso']=2;
			}
		}else{
		$DATA['paso']=$DATA['paso']-10;
		if ($bQuitarCodigo){
			$DATA['cara35consec']='';
			}
		}
	//if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' InfoDepura<br>';}
	return array($DATA, $sError, $iTipoError, $sDebug);
	}
function f2335_db_Eliminar($cara35id, $objDB, $bDebug=false){
	$iCodModulo=2335;
	$bAudita[4]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2335='lg/lg_2335_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2335)){$mensajes_2335='lg/lg_2335_es.php';}
	require $mensajes_todas;
	require $mensajes_2335;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	$cara35id=numeros_validar($cara35id);
	// Traer los datos para hacer las validaciones.
	if ($sError==''){
		$sSQL='SELECT * FROM cara35factorpermanece WHERE cara35id='.$cara35id.'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$filabase=$objDB->sf($tabla);
			}else{
			$sError='No se encuentra el registro solicitado {Ref: '.$cara35id.'}';
			}
		}
	if ($sError==''){
		if (!seg_revisa_permiso($iCodModulo, 4, $objDB)){
			$sError=$ERR['4'];
			}
		}
	if ($sError==''){
		$sSQL='SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=2335';
		$tablaor=$objDB->ejecutasql($sSQL);
		while ($filaor=$objDB->sf($tablaor)){
			$sSQL='SELECT '.$filaor['campoid'].' FROM '.$filaor['tabla'].' WHERE '.$filaor['camporev'].'='.$_REQUEST['cara35id'].' LIMIT 0, 1';
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
		$sWhere='cara35id='.$cara35id.'';
		//$sWhere='cara35consec='.$filabase['cara35consec'].'';
		$sSQL='DELETE FROM cara35factorpermanece WHERE '.$sWhere.';';
		$result=$objDB->ejecutasql($sSQL);
		if ($result==false){
			$sError=$ERR['falla_eliminar'].' .. <!-- '.$sSQL.' -->';
			}else{
			if ($bAudita[4]){seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $cara35id, $sWhere, $objDB);}
			}
		}
	return array($sError, $iTipoError, $sDebug);
	}
function f2335_TituloBusqueda(){
	return 'Busqueda de Factores de permanencia';
	}
function f2335_ParametrosBusqueda(){
	$sParams='<label class="Label90">Nombre</label><label><input id="b2335nombre" name="b2335nombre" type="text" value="" onchange="paginarbusqueda()" /></label>';
	return $sParams;
	}
function f2335_JavaScriptBusqueda($iModuloBusca){
	$sRes='var sCampo=window.document.frmedita.scampobusca.value;
var params=new Array();
params[100]=sCampo;
params[101]=window.document.frmedita.paginabusqueda.value;
params[102]=window.document.frmedita.lppfbusqueda.value;
params[103]=window.document.frmedita.b2335nombre.value;
xajax_f'.$iModuloBusca.'_HtmlBusqueda(params);';
	return $sRes;
	}
function f2335_TablaDetalleBusquedas($aParametros, $objDB){
	$res='';
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2335='lg/lg_2335_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2335)){$mensajes_2335='lg/lg_2335_es.php';}
	require $mensajes_todas;
	require $mensajes_2335;
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
		return array($sLeyenda.'<input id="paginaf2335" name="paginaf2335" type="hidden" value="'.$pagina.'"/><input id="lppf2335" name="lppf2335" type="hidden" value="'.$lineastabla.'"/>', $sDebug);
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
	$sTitulos='Consec, Id, Activa, Orden, Nombre, Grupo';
	$sSQL='SELECT TB.cara35consec, TB.cara35id, TB.cara35activa, TB.cara35orden, TB.cara35nombre, T6.cara22nombre, TB.cara35grupo 
FROM cara35factorpermanece AS TB, cara22gruposfactores AS T6 
WHERE '.$sSQLadd1.' TB.cara35grupo=T6.cara22id '.$sSQLadd.'
ORDER BY TB.cara35consec';
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
			//return array(utf8_encode($sErrConsulta.'<input id="paginaf2335" name="paginaf2335" type="hidden" value="'.$pagina.'"/><input id="lppf2335" name="lppf2335" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
<td><b>'.$ETI['cara35consec'].'</b></td>
<td><b>'.$ETI['cara35activa'].'</b></td>
<td><b>'.$ETI['cara35orden'].'</b></td>
<td><b>'.$ETI['cara35nombre'].'</b></td>
<td><b>'.$ETI['cara35grupo'].'</b></td>
<td align="right">
'.html_paginador('paginabusqueda', $registros, $lineastabla, $pagina, 'paginarbusqueda()').'
'.html_lpp('lppfbusqueda', $lineastabla, 'paginarbusqueda()').'
</td>
</tr>';
	$tlinea=1;
	while($filadet=$objDB->sf($tabladetalle)){
		$sPrefijo='<a href="javascript:Devuelve(\''.$filadet['cara35id'].'\');">';
		$sSufijo='</a>';
		$tlinea++;
		$et_cara35activa=$ETI['no'];
		if ($filadet['cara35activa']=='S'){$et_cara35activa=$ETI['si'];}
		$res=$res.'<tr onmouseover="cambia_color_over(this);" onmouseout="cambia_color_out(this);">
<td>'.$sPrefijo.$filadet['cara35consec'].$sSufijo.'</td>
<td>'.$sPrefijo.$et_cara35activa.$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['cara35orden'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['cara35nombre']).$sSufijo.'</td>
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