<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2019 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.23.5 Monday, September 9, 2019
--- 2324 cara24avancecatedra
*/
/** Archivo lib2324.php.
* Libreria 2324 cara24avancecatedra.
* @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
* @date Monday, September 9, 2019
*/
function f2324_ExisteDato($datos){
	if(!is_array($datos)){$datos=json_decode(str_replace('\"','"',$datos),true);}
	$_SESSION['u_ultimominuto']=iminutoavance();
	$bHayLlave=true;
	$cara24consec=numeros_validar($datos[1]);
	if ($cara24consec==''){$bHayLlave=false;}
	if ($bHayLlave){
		require './app.php';
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		$sSQL='SELECT cara24consec FROM cara24avancecatedra WHERE cara24consec='.$cara24consec.'';
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
function f2324_Busquedas($aParametros){
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2324='lg/lg_2324_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2324)){$mensajes_2324='lg/lg_2324_es.php';}
	require $mensajes_todas;
	require $mensajes_2324;
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
	$sTitulo='<h2>'.$ETI['titulo_2324'].' - '.$sTitulo.'</h2>';
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97titulo', 'innerHTML', $sTitulo);
	$objResponse->assign('div_97params', 'innerHTML', $sParams);
	$objResponse->assign('div_97tabla', 'innerHTML', $sTabla);
	$objResponse->setFunction('paginarbusqueda','',$sJavaBusqueda);
	$objResponse->call('expandesector(97)');
	return $objResponse;
	}
function f2324_HtmlBusqueda($aParametros){
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
function f2324_TablaDetalleV2($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2324='lg/lg_2324_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2324)){$mensajes_2324='lg/lg_2324_es.php';}
	require $mensajes_todas;
	require $mensajes_2324;
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
		return array($sLeyenda.'<input id="paginaf2324" name="paginaf2324" type="hidden" value="'.$pagina.'"/><input id="lppf2324" name="lppf2324" type="hidden" value="'.$lineastabla.'"/>', $sDebug);
		die();
		}
	$sSQLadd='';
	$sSQLadd1='cara24id>0';
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
	$sTitulos='Consec, Id, Orden, Jornada, Activa, Titulo, Vrriesgo';
	$sSQL='SELECT TB.cara24consec, TB.cara24id, TB.cara24orden, TB.cara24jornada, TB.cara24activa, TB.cara24titulo, TB.cara24vrriesgo 
FROM cara24avancecatedra AS TB 
WHERE '.$sSQLadd1.'  '.$sSQLadd.'
ORDER BY TB.cara24jornada, TB.cara24orden, TB.cara24titulo';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_2324" name="consulta_2324" type="hidden" value="'.$sSQLlista.'"/>
<input id="titulos_2324" name="titulos_2324" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objDB->ejecutasql($sSQL);
	if ($tabladetalle==false){
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 2324: '.$sSQL.'<br>';}
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		//$sLeyenda=$sSQL;
		}else{
		$registros=$objDB->nf($tabladetalle);
		if ($registros==0){
			//return array(utf8_encode($sErrConsulta.'<input id="paginaf2324" name="paginaf2324" type="hidden" value="'.$pagina.'"/><input id="lppf2324" name="lppf2324" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
<td><b>'.$ETI['cara24consec'].'</b></td>
<td><b>'.$ETI['cara24orden'].'</b></td>
<td><b>'.$ETI['cara24jornada'].'</b></td>
<td><b>'.$ETI['cara24activa'].'</b></td>
<td><b>'.$ETI['cara24titulo'].'</b></td>
<td><b>'.$ETI['cara24vrriesgo'].'</b></td>
<td align="right">
'.html_paginador('paginaf2324', $registros, $lineastabla, $pagina, 'paginarf2324()').'
'.html_lpp('lppf2324', $lineastabla, 'paginarf2324()').'
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
		$et_cara24activa=$ETI['no'];
		if ($filadet['cara24activa']=='S'){$et_cara24activa=$ETI['si'];}
		if ($babierta){
			$sLink='<a href="javascript:cargaridf2324('.$filadet['cara24id'].')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$res=$res.'<tr'.$sClass.'>
<td>'.$sPrefijo.$filadet['cara24consec'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['cara24orden'].$sSufijo.'</td>
<td>'.$sPrefijo.$acara24jornada[$filadet['cara24jornada']].$sSufijo.'</td>
<td>'.$sPrefijo.$et_cara24activa.$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['cara24titulo']).$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['cara24vrriesgo'].$sSufijo.'</td>
<td>'.$sLink.'</td>
</tr>';
		}
	$res=$res.'</table>';
	$objDB->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
	}
function f2324_HtmlTabla($aParametros){
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
	list($sDetalle, $sDebugTabla)=f2324_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f2324detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f2324_db_CargarPadre($DATA, $objDB, $bDebug=false){
	$sError='';
	$iTipoError=0;
	$sDebug='';
	require './app.php';
	if ($DATA['paso']==1){
		$sSQLcondi='cara24consec='.$DATA['cara24consec'].'';
		}else{
		$sSQLcondi='cara24id='.$DATA['cara24id'].'';
		}
	$sSQL='SELECT * FROM cara24avancecatedra WHERE '.$sSQLcondi;
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$DATA['cara24consec']=$fila['cara24consec'];
		$DATA['cara24id']=$fila['cara24id'];
		$DATA['cara24orden']=$fila['cara24orden'];
		$DATA['cara24jornada']=$fila['cara24jornada'];
		$DATA['cara24activa']=$fila['cara24activa'];
		$DATA['cara24titulo']=$fila['cara24titulo'];
		$DATA['cara24vrriesgo']=$fila['cara24vrriesgo'];
		$bcargo=true;
		$DATA['paso']=2;
		$DATA['boculta2324']=0;
		$bLimpiaHijos=true;
		}else{
		$DATA['paso']=0;
		}
	return array($DATA, $sError, $iTipoError, $sDebug);
	}
function f2324_db_GuardarV2($DATA, $objDB, $bDebug=false){
	$iCodModulo=2324;
	$bAudita[2]=true;
	$bAudita[3]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2324='lg/lg_2324_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2324)){$mensajes_2324='lg/lg_2324_es.php';}
	require $mensajes_todas;
	require $mensajes_2324;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	// -- Se inicia validando todas las posibles entradas de usuario.
	/*
	if (isset($DATA['cara24consec'])==0){$DATA['cara24consec']='';}
	if (isset($DATA['cara24id'])==0){$DATA['cara24id']='';}
	if (isset($DATA['cara24orden'])==0){$DATA['cara24orden']='';}
	if (isset($DATA['cara24jornada'])==0){$DATA['cara24jornada']='';}
	if (isset($DATA['cara24activa'])==0){$DATA['cara24activa']='';}
	if (isset($DATA['cara24titulo'])==0){$DATA['cara24titulo']='';}
	if (isset($DATA['cara24vrriesgo'])==0){$DATA['cara24vrriesgo']='';}
	*/
	$DATA['cara24consec']=numeros_validar($DATA['cara24consec']);
	$DATA['cara24orden']=numeros_validar($DATA['cara24orden']);
	$DATA['cara24jornada']=numeros_validar($DATA['cara24jornada']);
	$DATA['cara24activa']=htmlspecialchars(trim($DATA['cara24activa']));
	$DATA['cara24titulo']=htmlspecialchars(trim($DATA['cara24titulo']));
	$DATA['cara24vrriesgo']=numeros_validar($DATA['cara24vrriesgo']);
	// -- Se inicializan las variables que puedan pasar vacias {Especialmente números}.
	//if ($DATA['cara24orden']==''){$DATA['cara24orden']=0;}
	//if ($DATA['cara24jornada']==''){$DATA['cara24jornada']=0;}
	//if ($DATA['cara24vrriesgo']==''){$DATA['cara24vrriesgo']=0;}
	// -- Seccion para validar los posibles causales de error.
	$sSepara=', ';
	if (true){
		if ($DATA['cara24vrriesgo']==''){$sError=$ERR['cara24vrriesgo'].$sSepara.$sError;}
		if ($DATA['cara24titulo']==''){$sError=$ERR['cara24titulo'].$sSepara.$sError;}
		if ($DATA['cara24activa']==''){$sError=$ERR['cara24activa'].$sSepara.$sError;}
		if ($DATA['cara24jornada']==''){$sError=$ERR['cara24jornada'].$sSepara.$sError;}
		//if ($DATA['cara24orden']==''){$sError=$ERR['cara24orden'].$sSepara.$sError;}
		//Fin de las valiaciones NO LLAVE.
		}
	//Valiaciones de campos obligatorios en todo guardar.
	// -- Se verifican los valores de campos de otras tablas.
	if ($sError==''){
		if ($DATA['paso']==10){
			if ($DATA['cara24consec']==''){
				$DATA['cara24consec']=tabla_consecutivo('cara24avancecatedra', 'cara24consec', '', $objDB);
				if ($DATA['cara24consec']==-1){$sError=$objDB->serror;}
				}else{
				if (!seg_revisa_permiso($iCodModulo, 8, $objDB)){
					$sError=$ERR['8'];
					$DATA['cara24consec']='';
					}
				}
			if ($sError==''){
				$sSQL='SELECT cara24consec FROM cara24avancecatedra WHERE cara24consec='.$DATA['cara24consec'].'';
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
			$DATA['cara24id']=tabla_consecutivo('cara24avancecatedra','cara24id', '', $objDB);
			if ($DATA['cara24id']==-1){$sError=$objDB->serror;}
			}
		}
	if ($sError==''){
		if ($DATA['cara24orden']==''){$DATA['cara24orden']=$DATA['cara24consec'];}
		$bpasa=false;
		if ($DATA['paso']==10){
			$sCampos2324='cara24consec, cara24id, cara24orden, cara24jornada, cara24activa, cara24titulo, cara24vrriesgo';
			$sValores2324=''.$DATA['cara24consec'].', '.$DATA['cara24id'].', '.$DATA['cara24orden'].', '.$DATA['cara24jornada'].', "'.$DATA['cara24activa'].'", "'.$DATA['cara24titulo'].'", '.$DATA['cara24vrriesgo'].'';
			if ($APP->utf8==1){
				$sSQL='INSERT INTO cara24avancecatedra ('.$sCampos2324.') VALUES ('.utf8_encode($sValores2324).');';
				$sdetalle=$sCampos2324.'['.utf8_encode($sValores2324).']';
				}else{
				$sSQL='INSERT INTO cara24avancecatedra ('.$sCampos2324.') VALUES ('.$sValores2324.');';
				$sdetalle=$sCampos2324.'['.$sValores2324.']';
				}
			$idaccion=2;
			$bpasa=true;
			}else{
			$scampo[1]='cara24orden';
			$scampo[2]='cara24jornada';
			$scampo[3]='cara24activa';
			$scampo[4]='cara24titulo';
			$scampo[5]='cara24vrriesgo';
			$sdato[1]=$DATA['cara24orden'];
			$sdato[2]=$DATA['cara24jornada'];
			$sdato[3]=$DATA['cara24activa'];
			$sdato[4]=$DATA['cara24titulo'];
			$sdato[5]=$DATA['cara24vrriesgo'];
			$numcmod=5;
			$sWhere='cara24id='.$DATA['cara24id'].'';
			$sSQL='SELECT * FROM cara24avancecatedra WHERE '.$sWhere;
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
					$sSQL='UPDATE cara24avancecatedra SET '.utf8_encode($sdatos).' WHERE '.$sWhere.';';
					}else{
					$sdetalle=$sdatos.'['.$sWhere.']';
					$sSQL='UPDATE cara24avancecatedra SET '.$sdatos.' WHERE '.$sWhere.';';
					}
				$idaccion=3;
				}
			}
		if ($bpasa){
			$result=$objDB->ejecutasql($sSQL);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' [2324] ..<!-- '.$sSQL.' -->';
				if ($idaccion==2){$DATA['cara24id']='';}
				$DATA['paso']=$DATA['paso']-10;
				}else{
				if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Guardar 2324 '.$sSQL.'<br>';}
				if ($bAudita[$idaccion]){seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], $idaccion, $DATA['cara24id'], $sdetalle, $objDB);}
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
function f2324_db_Eliminar($cara24id, $objDB, $bDebug=false){
	$iCodModulo=2324;
	$bAudita[4]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2324='lg/lg_2324_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2324)){$mensajes_2324='lg/lg_2324_es.php';}
	require $mensajes_todas;
	require $mensajes_2324;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	$cara24id=numeros_validar($cara24id);
	// Traer los datos para hacer las validaciones.
	if ($sError==''){
		$sSQL='SELECT * FROM cara24avancecatedra WHERE cara24id='.$cara24id.'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$filabase=$objDB->sf($tabla);
			}else{
			$sError='No se encuentra el registro solicitado {Ref: '.$cara24id.'}';
			}
		}
	if ($sError==''){
		if (!seg_revisa_permiso($iCodModulo, 4, $objDB)){
			$sError=$ERR['4'];
			}
		}
	if ($sError==''){
		$sSQL='SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=2324';
		$tablaor=$objDB->ejecutasql($sSQL);
		while ($filaor=$objDB->sf($tablaor)){
			$sSQL='SELECT '.$filaor['campoid'].' FROM '.$filaor['tabla'].' WHERE '.$filaor['camporev'].'='.$_REQUEST['cara24id'].' LIMIT 0, 1';
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
		$sWhere='cara24id='.$cara24id.'';
		//$sWhere='cara24consec='.$filabase['cara24consec'].'';
		$sSQL='DELETE FROM cara24avancecatedra WHERE '.$sWhere.';';
		$result=$objDB->ejecutasql($sSQL);
		if ($result==false){
			$sError=$ERR['falla_eliminar'].' .. <!-- '.$sSQL.' -->';
			}else{
			if ($bAudita[4]){seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $cara24id, $sWhere, $objDB);}
			}
		}
	return array($sError, $iTipoError, $sDebug);
	}
function f2324_TituloBusqueda(){
	return 'Busqueda de Avance catedra unadista';
	}
function f2324_ParametrosBusqueda(){
	$sParams='<label class="Label90">Nombre</label><label><input id="b2324nombre" name="b2324nombre" type="text" value="" onchange="paginarbusqueda()" /></label>';
	return $sParams;
	}
function f2324_JavaScriptBusqueda($iModuloBusca){
	$sRes='var sCampo=window.document.frmedita.scampobusca.value;
var params=new Array();
params[100]=sCampo;
params[101]=window.document.frmedita.paginabusqueda.value;
params[102]=window.document.frmedita.lppfbusqueda.value;
params[103]=window.document.frmedita.b2324nombre.value;
xajax_f'.$iModuloBusca.'_HtmlBusqueda(params);';
	return $sRes;
	}
function f2324_TablaDetalleBusquedas($aParametros, $objDB){
	$res='';
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2324='lg/lg_2324_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2324)){$mensajes_2324='lg/lg_2324_es.php';}
	require $mensajes_todas;
	require $mensajes_2324;
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
		return array($sLeyenda.'<input id="paginaf2324" name="paginaf2324" type="hidden" value="'.$pagina.'"/><input id="lppf2324" name="lppf2324" type="hidden" value="'.$lineastabla.'"/>', $sDebug);
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
	$sTitulos='Consec, Id, Orden, Jornada, Activa, Titulo, Vrriesgo';
	$sSQL='SELECT TB.cara24consec, TB.cara24id, TB.cara24orden, TB.cara24jornada, TB.cara24activa, TB.cara24titulo, TB.cara24vrriesgo 
FROM cara24avancecatedra AS TB 
WHERE '.$sSQLadd1.'  '.$sSQLadd.'
ORDER BY TB.cara24consec';
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
			//return array(utf8_encode($sErrConsulta.'<input id="paginaf2324" name="paginaf2324" type="hidden" value="'.$pagina.'"/><input id="lppf2324" name="lppf2324" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
<td><b>'.$ETI['cara24consec'].'</b></td>
<td><b>'.$ETI['cara24orden'].'</b></td>
<td><b>'.$ETI['cara24jornada'].'</b></td>
<td><b>'.$ETI['cara24activa'].'</b></td>
<td><b>'.$ETI['cara24titulo'].'</b></td>
<td><b>'.$ETI['cara24vrriesgo'].'</b></td>
<td align="right">
'.html_paginador('paginabusqueda', $registros, $lineastabla, $pagina, 'paginarbusqueda()').'
'.html_lpp('lppfbusqueda', $lineastabla, 'paginarbusqueda()').'
</td>
</tr>';
	$tlinea=1;
	while($filadet=$objDB->sf($tabladetalle)){
		$sPrefijo='<a href="javascript:Devuelve(\''.$filadet['cara24id'].'\');">';
		$sSufijo='</a>';
		$tlinea++;
		$et_cara24activa=$ETI['no'];
		if ($filadet['cara24activa']=='S'){$et_cara24activa=$ETI['si'];}
		$res=$res.'<tr onmouseover="cambia_color_over(this);" onmouseout="cambia_color_out(this);">
<td>'.$sPrefijo.$filadet['cara24consec'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['cara24orden'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['cara24jornada'].$sSufijo.'</td>
<td>'.$sPrefijo.$et_cara24activa.$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['cara24titulo']).$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['cara24vrriesgo'].$sSufijo.'</td>
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