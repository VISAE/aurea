<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2020 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.25.0 viernes, 3 de abril de 2020
--- 2337 cara37discapacidades
*/
/** Archivo lib2337.php.
* Libreria 2337 cara37discapacidades.
* @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
* @date viernes, 3 de abril de 2020
*/
function f2337_ExisteDato($datos){
	if(!is_array($datos)){$datos=json_decode(str_replace('\"','"',$datos),true);}
	$_SESSION['u_ultimominuto']=iminutoavance();
	$bHayLlave=true;
	$cara37consec=numeros_validar($datos[1]);
	if ($cara37consec==''){$bHayLlave=false;}
	if ($bHayLlave){
		require './app.php';
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		$sSQL='SELECT cara37consec FROM cara37discapacidades WHERE cara37consec='.$cara37consec.'';
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
function f2337_Busquedas($aParametros){
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2337='lg/lg_2337_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2337)){$mensajes_2337='lg/lg_2337_es.php';}
	require $mensajes_todas;
	require $mensajes_2337;
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
	$sTitulo='<h2>'.$ETI['titulo_2337'].' - '.$sTitulo.'</h2>';
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97titulo', 'innerHTML', $sTitulo);
	$objResponse->assign('div_97params', 'innerHTML', $sParams);
	$objResponse->assign('div_97tabla', 'innerHTML', $sTabla);
	$objResponse->setFunction('paginarbusqueda','',$sJavaBusqueda);
	$objResponse->call('expandesector(97)');
	return $objResponse;
	}
function f2337_HtmlBusqueda($aParametros){
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
function f2337_TablaDetalleV2($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2337='lg/lg_2337_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2337)){$mensajes_2337='lg/lg_2337_es.php';}
	require $mensajes_todas;
	require $mensajes_2337;
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	if (isset($aParametros[100])==0){$aParametros[100]=$_SESSION['unad_id_tercero'];}
	if (isset($aParametros[101])==0){$aParametros[101]=1;}
	if (isset($aParametros[102])==0){$aParametros[102]=20;}
	//if (isset($aParametros[103])==0){$aParametros[103]='';}
	//if (isset($aParametros[104])==0){$aParametros[104]='';}
	//$aParametros[103]=numeros_validar($aParametros[103]);
	$idTercero=$aParametros[100];
	$sDebug='';
	$pagina=$aParametros[101];
	$lineastabla=$aParametros[102];
	$bAbierta=true;
	//$sSQL='SELECT Campo FROM Tabla WHERE Id='.$sValorId;
	//$tabla=$objDB->ejecutasql($sSQL);
	//if ($objDB->nf($tabla)>0){
		//$fila=$objDB->sf($tabla);
		//if ($fila['Campo']!='S'){$bAbierta=true;}
		//}
	$sLeyenda='';
	if ($sLeyenda!=''){
		$sLeyenda='<div class="salto1px"></div>
<div class="GrupoCamposAyuda">
'.$sLeyenda.'
<div class="salto1px"></div>
</div>';
		return array($sLeyenda.'<input id="paginaf2337" name="paginaf2337" type="hidden" value="'.$pagina.'"/><input id="lppf2337" name="lppf2337" type="hidden" value="'.$lineastabla.'"/>', $sDebug);
		die();
		}
/*
	$aEstado=array();
	$sSQL='SELECT id, nombre FROM tabla';
	$tabla=$objDB->ejecutasql($sSQL);
	while($fila=$objDB->sf($tabla)){
		$aEstado[$fila['id']]=cadena_notildes($fila['nombre']);
		}
*/
	$sSQLadd='';
	$sSQLadd1='';
	//if ((int)$aParametros[103]!=-1){$sSQLadd=$sSQLadd.' AND TB.campo='.$aParametros[103];}
	if ($aParametros[104]!=''){$sSQLadd1=$sSQLadd1.'TB.cara37tipodisc LIKE "%'.$aParametros[104].'%" AND ';}
	if ($aParametros[103]!=''){
		$sBase=trim(strtoupper($aParametros[103]));
		$aNoms=explode(' ', $sBase);
		for ($k=1;$k<=count($aNoms);$k++){
			$sCadena=$aNoms[$k-1];
			if ($sCadena!=''){
				//$sSQLadd=$sSQLadd.' AND T6.unad11razonsocial LIKE "%'.$sCadena.'%"';
				$sSQLadd1=$sSQLadd1.'TB.cara37nombre LIKE "%'.$sCadena.'%" AND ';
				}
			}
		}
	$sTitulos='Consec, Id, Tipodisc, Activa, Orden, Nombre';
	$registros=0;
	$bGigante=false; //En caso de que la tabla sea muy grande pasarlo a true
	$sLimite='';
	if ($bGigante){
		$sSQL='SELECT COUNT(1) AS Total 
FROM cara37discapacidades AS TB, cara36tipodisc AS T3 
WHERE '.$sSQLadd1.' TB.cara37tipodisc=T3.cara36id '.$sSQLadd.'';
		$tabladetalle=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabladetalle)>0){
			$fila=$objDB->sf($tabladetalle);
			$registros=$fila['Total'];
			}
		if ((($registros-1)/$lineastabla)<($pagina-1)){$pagina=(int)(($registros-1)/$lineastabla)+1;}
		if ($registros>$lineastabla){
			$rbase=($pagina-1)*$lineastabla;
			$sLimite=' LIMIT '.$rbase.', '.$lineastabla;
			}
		}
	$sSQL='SELECT TB.cara37consec, TB.cara37id, T3.cara36nombre, TB.cara37activa, TB.cara37orden, TB.cara37nombre, TB.cara37tipodisc 
FROM cara37discapacidades AS TB, cara36tipodisc AS T3 
WHERE '.$sSQLadd1.' TB.cara37tipodisc=T3.cara36id '.$sSQLadd.'
ORDER BY TB.cara37tipodisc, TB.cara37orden, TB.cara37nombre';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_2337" name="consulta_2337" type="hidden" value="'.$sSQLlista.'"/>
<input id="titulos_2337" name="titulos_2337" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objDB->ejecutasql($sSQL.$sLimite);
	if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 2337: '.$sSQL.$sLimite.'<br>';}
	if ($tabladetalle==false){
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		//$sLeyenda=$sSQL;
		}else{
		if (!$bGigante){
			$registros=$objDB->nf($tabladetalle);
			if ($registros==0){
				//return array(utf8_encode($sErrConsulta.'<input id="paginaf2337" name="paginaf2337" type="hidden" value="'.$pagina.'"/><input id="lppf2337" name="lppf2337" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
				}
			if ((($registros-1)/$lineastabla)<($pagina-1)){$pagina=(int)(($registros-1)/$lineastabla)+1;}
			if ($registros>$lineastabla){
				$rbase=($pagina-1)*$lineastabla;
				$sLimite=' LIMIT '.$rbase.', '.$lineastabla;
				$tabladetalle=$objDB->ejecutasql($sSQL.$sLimite);
				}
			}
		}
	$res=$sErrConsulta.$sLeyenda.'<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">
<tr class="fondoazul">
<td><b>'.$ETI['cara37tipodisc'].'</b></td>
<td><b>'.$ETI['cara37consec'].'</b></td>
<td><b>'.$ETI['cara37activa'].'</b></td>
<td><b>'.$ETI['cara37orden'].'</b></td>
<td><b>'.$ETI['cara37nombre'].'</b></td>
<td align="right">
'.html_paginador('paginaf2337', $registros, $lineastabla, $pagina, 'paginarf2337()').'
'.html_lpp('lppf2337', $lineastabla, 'paginarf2337()').'
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
		$et_cara37activa=$sPrefijo.$ETI['no'].$sSufijo;
		if ($filadet['cara37activa']=='S'){$et_cara37activa=$sPrefijo.$ETI['si'].$sSufijo;}
		if ($bAbierta){
			$sLink='<a href="javascript:cargaridf2337('.$filadet['cara37id'].')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$res=$res.'<tr'.$sClass.'>
<td>'.$sPrefijo.cadena_notildes($filadet['cara36nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['cara37consec'].$sSufijo.'</td>
<td>'.$sPrefijo.$et_cara37activa.$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['cara37orden'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['cara37nombre']).$sSufijo.'</td>
<td>'.$sLink.'</td>
</tr>';
		}
	$res=$res.'</table>';
	$objDB->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
	}
function f2337_HtmlTabla($aParametros){
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
	list($sDetalle, $sDebugTabla)=f2337_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f2337detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f2337_db_CargarPadre($DATA, $objDB, $bDebug=false){
	$sError='';
	$iTipoError=0;
	$sDebug='';
	require './app.php';
	if ($DATA['paso']==1){
		$sSQLcondi='cara37consec='.$DATA['cara37consec'].'';
		}else{
		$sSQLcondi='cara37id='.$DATA['cara37id'].'';
		}
	$sSQL='SELECT * FROM cara37discapacidades WHERE '.$sSQLcondi;
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$DATA['cara37consec']=$fila['cara37consec'];
		$DATA['cara37id']=$fila['cara37id'];
		$DATA['cara37tipodisc']=$fila['cara37tipodisc'];
		$DATA['cara37activa']=$fila['cara37activa'];
		$DATA['cara37orden']=$fila['cara37orden'];
		$DATA['cara37nombre']=$fila['cara37nombre'];
		$bcargo=true;
		$DATA['paso']=2;
		$DATA['boculta2337']=0;
		$bLimpiaHijos=true;
		}else{
		$DATA['paso']=0;
		}
	return array($DATA, $sError, $iTipoError, $sDebug);
	}
function f2337_db_GuardarV2($DATA, $objDB, $bDebug=false){
	$iCodModulo=2337;
	$bAudita[2]=true;
	$bAudita[3]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2337='lg/lg_2337_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2337)){$mensajes_2337='lg/lg_2337_es.php';}
	require $mensajes_todas;
	require $mensajes_2337;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	// -- Se inicia validando todas las posibles entradas de usuario.
	/*
	if (isset($DATA['cara37consec'])==0){$DATA['cara37consec']='';}
	if (isset($DATA['cara37id'])==0){$DATA['cara37id']='';}
	if (isset($DATA['cara37tipodisc'])==0){$DATA['cara37tipodisc']='';}
	if (isset($DATA['cara37activa'])==0){$DATA['cara37activa']='';}
	if (isset($DATA['cara37orden'])==0){$DATA['cara37orden']='';}
	if (isset($DATA['cara37nombre'])==0){$DATA['cara37nombre']='';}
	*/
	$DATA['cara37consec']=numeros_validar($DATA['cara37consec']);
	$DATA['cara37tipodisc']=numeros_validar($DATA['cara37tipodisc']);
	$DATA['cara37activa']=htmlspecialchars(trim($DATA['cara37activa']));
	$DATA['cara37orden']=numeros_validar($DATA['cara37orden']);
	$DATA['cara37nombre']=htmlspecialchars(trim($DATA['cara37nombre']));
	// -- Se inicializan las variables que puedan pasar vacias {Especialmente números}.
	//if ($DATA['cara37tipodisc']==''){$DATA['cara37tipodisc']=0;}
	//if ($DATA['cara37orden']==''){$DATA['cara37orden']=0;}
	// -- Seccion para validar los posibles causales de error.
	$sSepara=', ';
	if (true){
		if ($DATA['cara37nombre']==''){$sError=$ERR['cara37nombre'].$sSepara.$sError;}
		//if ($DATA['cara37orden']==''){$sError=$ERR['cara37orden'].$sSepara.$sError;}
		if ($DATA['cara37activa']==''){$sError=$ERR['cara37activa'].$sSepara.$sError;}
		if ($DATA['cara37tipodisc']==''){$sError=$ERR['cara37tipodisc'].$sSepara.$sError;}
		//Fin de las valiaciones NO LLAVE.
		}
	//Valiaciones de campos obligatorios en todo guardar.
	// -- Se verifican los valores de campos de otras tablas.
	$bQuitarCodigo=false;
	$sCampoCodigo='';
	if ($sError==''){
		if ($DATA['paso']==10){
			if ($DATA['cara37consec']==''){
				$DATA['cara37consec']=tabla_consecutivo('cara37discapacidades', 'cara37consec', '', $objDB);
				if ($DATA['cara37consec']==-1){$sError=$objDB->serror;}
				$bQuitarCodigo=true;
				$sCampoCodigo='cara37consec';
				}else{
				if (!seg_revisa_permiso($iCodModulo, 8, $objDB)){
					$sError=$ERR['8'];
					$DATA['cara37consec']='';
					}
				}
			if ($sError==''){
				$sSQL='SELECT 1 FROM cara37discapacidades WHERE cara37consec='.$DATA['cara37consec'].'';
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
			$DATA['cara37id']=tabla_consecutivo('cara37discapacidades','cara37id', '', $objDB);
			if ($DATA['cara37id']==-1){$sError=$objDB->serror;}
			}
		}
	if ($sError==''){
		if ($DATA['cara37orden']==''){$DATA['cara37orden']=$DATA['cara37consec'];}
		$bpasa=false;
		if ($DATA['paso']==10){
			$sCampos2337='cara37consec, cara37id, cara37tipodisc, cara37activa, cara37orden, 
cara37nombre';
			$sValores2337=''.$DATA['cara37consec'].', '.$DATA['cara37id'].', '.$DATA['cara37tipodisc'].', "'.$DATA['cara37activa'].'", '.$DATA['cara37orden'].', 
"'.$DATA['cara37nombre'].'"';
			if ($APP->utf8==1){
				$sSQL='INSERT INTO cara37discapacidades ('.$sCampos2337.') VALUES ('.utf8_encode($sValores2337).');';
				$sdetalle=$sCampos2337.'['.utf8_encode($sValores2337).']';
				}else{
				$sSQL='INSERT INTO cara37discapacidades ('.$sCampos2337.') VALUES ('.$sValores2337.');';
				$sdetalle=$sCampos2337.'['.$sValores2337.']';
				}
			$idAccion=2;
			$bpasa=true;
			}else{
			$scampo[1]='cara37tipodisc';
			$scampo[2]='cara37activa';
			$scampo[3]='cara37orden';
			$scampo[4]='cara37nombre';
			$sdato[1]=$DATA['cara37tipodisc'];
			$sdato[2]=$DATA['cara37activa'];
			$sdato[3]=$DATA['cara37orden'];
			$sdato[4]=$DATA['cara37nombre'];
			$numcmod=4;
			$sWhere='cara37id='.$DATA['cara37id'].'';
			$sSQL='SELECT * FROM cara37discapacidades WHERE '.$sWhere;
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
					$sSQL='UPDATE cara37discapacidades SET '.utf8_encode($sdatos).' WHERE '.$sWhere.';';
					}else{
					$sdetalle=$sdatos.'['.$sWhere.']';
					$sSQL='UPDATE cara37discapacidades SET '.$sdatos.' WHERE '.$sWhere.';';
					}
				$idAccion=3;
				}
			}
		if ($bpasa){
			$result=$objDB->ejecutasql($sSQL);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' [2337] ..<!-- '.$sSQL.' -->';
				if ($idAccion==2){
					$DATA['cara37id']='';
					$bQuitarCodigo=true;
					$DATA['paso']=0;
					}else{
					$DATA['paso']=2;
					}
				}else{
				if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Guardar 2337 '.$sSQL.'<br>';}
				if ($bAudita[$idAccion]){seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], $idAccion, $DATA['cara37id'], $sdetalle, $objDB);}
				$DATA['paso']=2;
				}
			}else{
			$DATA['paso']=2;
			}
		}else{
		if ($DATA['paso']==10){
			$DATA['paso']=0;
			}else{
			$DATA['paso']=2;
			}
		if ($bQuitarCodigo){
			if ($sCampoCodigo!=''){$DATA[$sCampoCodigo]='';}
			}
		}
	//if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' InfoDepura<br>';}
	return array($DATA, $sError, $iTipoError, $sDebug);
	}
function f2337_db_Eliminar($cara37id, $objDB, $bDebug=false){
	$iCodModulo=2337;
	$bAudita[4]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2337='lg/lg_2337_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2337)){$mensajes_2337='lg/lg_2337_es.php';}
	require $mensajes_todas;
	require $mensajes_2337;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	$cara37id=numeros_validar($cara37id);
	// Traer los datos para hacer las validaciones.
	if ($sError==''){
		$sSQL='SELECT * FROM cara37discapacidades WHERE cara37id='.$cara37id.'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$filabase=$objDB->sf($tabla);
			}else{
			$sError='No se encuentra el registro solicitado {Ref: '.$cara37id.'}';
			}
		}
	if ($sError==''){
		if (!seg_revisa_permiso($iCodModulo, 4, $objDB)){
			$sError=$ERR['4'];
			}
		}
	if ($sError==''){
		$sSQL='SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=2337';
		$tablaor=$objDB->ejecutasql($sSQL);
		while ($filaor=$objDB->sf($tablaor)){
			$sSQL='SELECT '.$filaor['campoid'].' FROM '.$filaor['tabla'].' WHERE '.$filaor['camporev'].'='.$_REQUEST['cara37id'].' LIMIT 0, 1';
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
		$sWhere='cara37id='.$cara37id.'';
		//$sWhere='cara37consec='.$filabase['cara37consec'].'';
		$sSQL='DELETE FROM cara37discapacidades WHERE '.$sWhere.';';
		$result=$objDB->ejecutasql($sSQL);
		if ($result==false){
			$sError=$ERR['falla_eliminar'].' .. <!-- '.$sSQL.' -->';
			}else{
			if ($bAudita[4]){seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $cara37id, $sWhere, $objDB);}
			}
		}
	return array($sError, $iTipoError, $sDebug);
	}
function f2337_TituloBusqueda(){
	return 'Busqueda de Discapacidades';
	}
function f2337_ParametrosBusqueda(){
	$sParams='<label class="Label90">Nombre</label><label><input id="b2337nombre" name="b2337nombre" type="text" value="" onchange="paginarbusqueda()" /></label>';
	return $sParams;
	}
function f2337_JavaScriptBusqueda($iModuloBusca){
	$sRes='var sCampo=window.document.frmedita.scampobusca.value;
var params=new Array();
params[100]=sCampo;
params[101]=window.document.frmedita.paginabusqueda.value;
params[102]=window.document.frmedita.lppfbusqueda.value;
params[103]=window.document.frmedita.b2337nombre.value;
xajax_f'.$iModuloBusca.'_HtmlBusqueda(params);';
	return $sRes;
	}
function f2337_TablaDetalleBusquedas($aParametros, $objDB){
	$res='';
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2337='lg/lg_2337_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2337)){$mensajes_2337='lg/lg_2337_es.php';}
	require $mensajes_todas;
	require $mensajes_2337;
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	if (isset($aParametros[100])==0){$aParametros[100]=$_SESSION['unad_id_tercero'];}
	if (isset($aParametros[101])==0){$aParametros[101]=1;}
	if (isset($aParametros[102])==0){$aParametros[102]=20;}
	if (isset($aParametros[103])==0){$aParametros[103]='';}
	if (isset($aParametros[104])==0){$aParametros[104]='';}
	//$aParametros[103]=numeros_validar($aParametros[103]);
	$idTercero=$aParametros[100];
	$pagina=$aParametros[101];
	$lineastabla=$aParametros[102];
	$bAbierta=true;
	//$sSQL='SELECT Campo FROM Tabla WHERE Id='.$sValorId;
	//$tabla=$objDB->ejecutasql($sSQL);
	//if ($objDB->nf($tabla)>0){
		//$fila=$objDB->sf($tabla);
		//if ($fila['Campo']!='S'){$bAbierta=true;}
		//}
	$sLeyenda='';
	if ($sLeyenda!=''){
		$sLeyenda='<div class="salto1px"></div>
<div class="GrupoCamposAyuda">
'.$sLeyenda.'
<div class="salto1px"></div>
</div>';
		return array($sLeyenda.'<input id="paginaf2337" name="paginaf2337" type="hidden" value="'.$pagina.'"/><input id="lppf2337" name="lppf2337" type="hidden" value="'.$lineastabla.'"/>', $sDebug);
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
	$sTitulos='Consec, Id, Tipodisc, Activa, Orden, Nombre';
	$sSQL='SELECT TB.cara37consec, TB.cara37id, T3.cara36nombre, TB.cara37activa, TB.cara37orden, TB.cara37nombre, TB.cara37tipodisc 
FROM cara37discapacidades AS TB, cara36tipodisc AS T3 
WHERE '.$sSQLadd1.' TB.cara37tipodisc=T3.cara36id '.$sSQLadd.'
ORDER BY TB.cara37consec';
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
			//return array(utf8_encode($sErrConsulta.'<input id="paginaf2337" name="paginaf2337" type="hidden" value="'.$pagina.'"/><input id="lppf2337" name="lppf2337" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
<td><b>'.$ETI['cara37consec'].'</b></td>
<td><b>'.$ETI['cara37tipodisc'].'</b></td>
<td><b>'.$ETI['cara37activa'].'</b></td>
<td><b>'.$ETI['cara37orden'].'</b></td>
<td><b>'.$ETI['cara37nombre'].'</b></td>
<td align="right">
'.html_paginador('paginabusqueda', $registros, $lineastabla, $pagina, 'paginarbusqueda()').'
'.html_lpp('lppfbusqueda', $lineastabla, 'paginarbusqueda()').'
</td>
</tr>';
	$tlinea=1;
	while($filadet=$objDB->sf($tabladetalle)){
		$sPrefijo='<a href="javascript:Devuelve(\''.$filadet['cara37id'].'\');">';
		$sSufijo='</a>';
		$tlinea++;
		$et_cara37activa=$sPrefijo.$ETI['no'].$sSufijo;
		if ($filadet['cara37activa']=='S'){$et_cara37activa=$sPrefijo.$ETI['si'].$sSufijo;}
		$res=$res.'<tr onmouseover="cambia_color_over(this);" onmouseout="cambia_color_out(this);">
<td>'.$sPrefijo.$filadet['cara37consec'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['cara36nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.$et_cara37activa.$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['cara37orden'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['cara37nombre']).$sSufijo.'</td>
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