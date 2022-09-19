<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2020 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.25.3 martes, 14 de julio de 2020
--- 3022 saiu22telefonos
*/
/** Archivo lib3022.php.
* Libreria 3022 saiu22telefonos.
* @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
* @date martes, 14 de julio de 2020
*/
function f3022_ExisteDato($datos){
	if(!is_array($datos)){$datos=json_decode(str_replace('\"','"',$datos),true);}
	$_SESSION['u_ultimominuto']=iminutoavance();
	$bHayLlave=true;
	$saiu22consec=numeros_validar($datos[1]);
	if ($saiu22consec==''){$bHayLlave=false;}
	if ($bHayLlave){
		require './app.php';
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		$sSQL='SELECT 1 FROM saiu22telefonos WHERE saiu22consec='.$saiu22consec.'';
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
function f3022_Busquedas($aParametros){
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3022='lg/lg_3022_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3022)){$mensajes_3022='lg/lg_3022_es.php';}
	require $mensajes_todas;
	require $mensajes_3022;
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
	$sTitulo='<h2>'.$ETI['titulo_3022'].' - '.$sTitulo.'</h2>';
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97titulo', 'innerHTML', $sTitulo);
	$objResponse->assign('div_97params', 'innerHTML', $sParams);
	$objResponse->assign('div_97tabla', 'innerHTML', $sTabla);
	$objResponse->setFunction('paginarbusqueda','',$sJavaBusqueda);
	$objResponse->call('expandesector(97)');
	return $objResponse;
	}
function f3022_HtmlBusqueda($aParametros){
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
function f3022_TablaDetalleV2($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3022='lg/lg_3022_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3022)){$mensajes_3022='lg/lg_3022_es.php';}
	require $mensajes_todas;
	require $mensajes_3022;
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
		return array($sLeyenda.'<input id="paginaf3022" name="paginaf3022" type="hidden" value="'.$pagina.'"/><input id="lppf3022" name="lppf3022" type="hidden" value="'.$lineastabla.'"/>', $sDebug);
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
	//if ($aParametros[103]!=''){$sSQLadd=$sSQLadd.' AND TB.campo2 LIKE "%'.$aParametros[103].'%"';}
	/*
	if ($aParametros[103]!=''){
		$sBase=trim(strtoupper($aParametros[103]));
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
	$sTitulos='Consec, Id, Activo, Predet, Orden, Nombre, Numero';
	$registros=0;
	$bGigante=false; //En caso de que la tabla sea muy grande pasarlo a true
	$sLimite='';
	if ($bGigante){
		$sSQL='SELECT COUNT(1) AS Total 
FROM saiu22telefonos AS TB 
WHERE '.$sSQLadd1.'  '.$sSQLadd.'';
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
	$sSQL='SELECT TB.saiu22consec, TB.saiu22id, TB.saiu22activo, TB.saiu22predet, TB.saiu22orden, TB.saiu22nombre, TB.saiu22numero 
FROM saiu22telefonos AS TB 
WHERE '.$sSQLadd1.' TB.saiu22id>0 '.$sSQLadd.'
ORDER BY TB.saiu22orden, TB.saiu22consec';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_3022" name="consulta_3022" type="hidden" value="'.$sSQLlista.'"/>
<input id="titulos_3022" name="titulos_3022" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objDB->ejecutasql($sSQL.$sLimite);
	if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 3022: '.$sSQL.$sLimite.'<br>';}
	if ($tabladetalle==false){
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		//$sLeyenda=$sSQL;
		}else{
		if (!$bGigante){
			$registros=$objDB->nf($tabladetalle);
			if ($registros==0){
				//return array(utf8_encode($sErrConsulta.'<input id="paginaf3022" name="paginaf3022" type="hidden" value="'.$pagina.'"/><input id="lppf3022" name="lppf3022" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
<thead class="fondoazul"><tr>
<td><b>'.$ETI['saiu22consec'].'</b></td>
<td><b>'.$ETI['saiu22activo'].'</b></td>
<td><b>'.$ETI['saiu22nombre'].'</b></td>
<td><b>'.$ETI['saiu22predet'].'</b></td>
<td><b>'.$ETI['saiu22orden'].'</b></td>
<td align="right">
'.html_paginador('paginaf3022', $registros, $lineastabla, $pagina, 'paginarf3022()').'
'.html_lpp('lppf3022', $lineastabla, 'paginarf3022()').'
</td>
</tr></thead>';
	$tlinea=1;
	while($filadet=$objDB->sf($tabladetalle)){
		$sPrefijo='';
		$sSufijo='';
		$sClass=' class="resaltetabla"';
		$sLink='';
		if (false){
			$sPrefijo='<b>';
			$sSufijo='</b>';
			}
		if(($tlinea%2)!=0){$sClass='';}
		$tlinea++;
		$et_saiu22activo=$sPrefijo.$ETI['no'].$sSufijo;
		if ($filadet['saiu22activo']=='S'){$et_saiu22activo=$sPrefijo.$ETI['si'].$sSufijo;}
		if ($bAbierta){
			$sLink='<a href="javascript:cargaridf3022('.$filadet['saiu22id'].')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$res=$res.'<tr'.$sClass.'>
<td>'.$sPrefijo.$filadet['saiu22consec'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu22activo'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['saiu22nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu22predet'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu22orden'].$sSufijo.'</td>
<td>'.$sLink.'</td>
</tr>';
		}
	$res=$res.'</table>';
	$objDB->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
	}
function f3022_HtmlTabla($aParametros){
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
	list($sDetalle, $sDebugTabla)=f3022_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f3022detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f3022_db_CargarPadre($DATA, $objDB, $bDebug=false){
	$sError='';
	$iTipoError=0;
	$sDebug='';
	require './app.php';
	if ($DATA['paso']==1){
		$sSQLcondi='saiu22consec='.$DATA['saiu22consec'].'';
		}else{
		$sSQLcondi='saiu22id='.$DATA['saiu22id'].'';
		}
	$sSQL='SELECT * FROM saiu22telefonos WHERE '.$sSQLcondi;
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$DATA['saiu22consec']=$fila['saiu22consec'];
		$DATA['saiu22id']=$fila['saiu22id'];
		$DATA['saiu22activo']=$fila['saiu22activo'];
		$DATA['saiu22predet']=$fila['saiu22predet'];
		$DATA['saiu22orden']=$fila['saiu22orden'];
		$DATA['saiu22nombre']=$fila['saiu22nombre'];
		$DATA['saiu22numero']=$fila['saiu22numero'];
		$bcargo=true;
		$DATA['paso']=2;
		$DATA['boculta3022']=0;
		$bLimpiaHijos=true;
		}else{
		$DATA['paso']=0;
		}
	return array($DATA, $sError, $iTipoError, $sDebug);
	}
function f3022_db_GuardarV2($DATA, $objDB, $bDebug=false){
	$iCodModulo=3022;
	$bAudita[2]=true;
	$bAudita[3]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3022='lg/lg_3022_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3022)){$mensajes_3022='lg/lg_3022_es.php';}
	require $mensajes_todas;
	require $mensajes_3022;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	// -- Se inicia validando todas las posibles entradas de usuario.
	/*
	if (isset($DATA['saiu22consec'])==0){$DATA['saiu22consec']='';}
	if (isset($DATA['saiu22id'])==0){$DATA['saiu22id']='';}
	if (isset($DATA['saiu22activo'])==0){$DATA['saiu22activo']='';}
	if (isset($DATA['saiu22predet'])==0){$DATA['saiu22predet']='';}
	if (isset($DATA['saiu22orden'])==0){$DATA['saiu22orden']='';}
	if (isset($DATA['saiu22nombre'])==0){$DATA['saiu22nombre']='';}
	if (isset($DATA['saiu22numero'])==0){$DATA['saiu22numero']='';}
	*/
	$DATA['saiu22consec']=numeros_validar($DATA['saiu22consec']);
	$DATA['saiu22activo']=numeros_validar($DATA['saiu22activo']);
	$DATA['saiu22predet']=numeros_validar($DATA['saiu22predet']);
	$DATA['saiu22orden']=numeros_validar($DATA['saiu22orden']);
	$DATA['saiu22nombre']=htmlspecialchars(trim($DATA['saiu22nombre']));
	$DATA['saiu22numero']=htmlspecialchars(trim($DATA['saiu22numero']));
	// -- Se inicializan las variables que puedan pasar vacias {Especialmente números}.
	//if ($DATA['saiu22activo']==''){$DATA['saiu22activo']=0;}
	//if ($DATA['saiu22predet']==''){$DATA['saiu22predet']=0;}
	//if ($DATA['saiu22orden']==''){$DATA['saiu22orden']=0;}
	// -- Seccion para validar los posibles causales de error.
	$sSepara=', ';
	if (true){
		if ($DATA['saiu22numero']==''){$sError=$ERR['saiu22numero'].$sSepara.$sError;}
		if ($DATA['saiu22nombre']==''){$sError=$ERR['saiu22nombre'].$sSepara.$sError;}
		//if ($DATA['saiu22orden']==''){$sError=$ERR['saiu22orden'].$sSepara.$sError;}
		if ($DATA['saiu22predet']==''){$sError=$ERR['saiu22predet'].$sSepara.$sError;}
		if ($DATA['saiu22activo']==''){$sError=$ERR['saiu22activo'].$sSepara.$sError;}
		//Fin de las valiaciones NO LLAVE.
		}
	//Valiaciones de campos obligatorios en todo guardar.
	// -- Se verifican los valores de campos de otras tablas.
	$bQuitarCodigo=false;
	$sCampoCodigo='';
	if ($sError==''){
		if ($DATA['paso']==10){
			if ($DATA['saiu22consec']==''){
				$DATA['saiu22consec']=tabla_consecutivo('saiu22telefonos', 'saiu22consec', '', $objDB);
				if ($DATA['saiu22consec']==-1){$sError=$objDB->serror;}
				$bQuitarCodigo=true;
				$sCampoCodigo='saiu22consec';
				}else{
				if (!seg_revisa_permiso($iCodModulo, 8, $objDB)){
					$sError=$ERR['8'];
					$DATA['saiu22consec']='';
					}
				}
			if ($sError==''){
				$sSQL='SELECT 1 FROM saiu22telefonos WHERE saiu22consec='.$DATA['saiu22consec'].'';
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
			$DATA['saiu22id']=tabla_consecutivo('saiu22telefonos','saiu22id', '', $objDB);
			if ($DATA['saiu22id']==-1){$sError=$objDB->serror;}
			}
		}
	if ($sError==''){
		if ($DATA['saiu22orden']==''){$DATA['saiu22orden']=$DATA['saiu22consec'];}
		$bpasa=false;
		if ($DATA['paso']==10){
			$sCampos3022='saiu22consec, saiu22id, saiu22activo, saiu22predet, saiu22orden, 
saiu22nombre, saiu22numero';
			$sValores3022=''.$DATA['saiu22consec'].', '.$DATA['saiu22id'].', '.$DATA['saiu22activo'].', '.$DATA['saiu22predet'].', '.$DATA['saiu22orden'].', 
"'.$DATA['saiu22nombre'].'", "'.$DATA['saiu22numero'].'"';
			if ($APP->utf8==1){
				$sSQL='INSERT INTO saiu22telefonos ('.$sCampos3022.') VALUES ('.utf8_encode($sValores3022).');';
				$sdetalle=$sCampos3022.'['.utf8_encode($sValores3022).']';
				}else{
				$sSQL='INSERT INTO saiu22telefonos ('.$sCampos3022.') VALUES ('.$sValores3022.');';
				$sdetalle=$sCampos3022.'['.$sValores3022.']';
				}
			$idAccion=2;
			$bpasa=true;
			}else{
			$scampo[1]='saiu22activo';
			$scampo[2]='saiu22predet';
			$scampo[3]='saiu22orden';
			$scampo[4]='saiu22nombre';
			$scampo[5]='saiu22numero';
			$sdato[1]=$DATA['saiu22activo'];
			$sdato[2]=$DATA['saiu22predet'];
			$sdato[3]=$DATA['saiu22orden'];
			$sdato[4]=$DATA['saiu22nombre'];
			$sdato[5]=$DATA['saiu22numero'];
			$numcmod=5;
			$sWhere='saiu22id='.$DATA['saiu22id'].'';
			$sSQL='SELECT * FROM saiu22telefonos WHERE '.$sWhere;
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
					$sSQL='UPDATE saiu22telefonos SET '.utf8_encode($sdatos).' WHERE '.$sWhere.';';
					}else{
					$sdetalle=$sdatos.'['.$sWhere.']';
					$sSQL='UPDATE saiu22telefonos SET '.$sdatos.' WHERE '.$sWhere.';';
					}
				$idAccion=3;
				}
			}
		if ($bpasa){
			$result=$objDB->ejecutasql($sSQL);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' [3022] ..<!-- '.$sSQL.' -->';
				if ($idAccion==2){
					$DATA['saiu22id']='';
					$bQuitarCodigo=true;
					$DATA['paso']=0;
					}else{
					$DATA['paso']=2;
					}
				}else{
				if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Guardar 3022 '.$sSQL.'<br>';}
				if ($bAudita[$idAccion]){seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], $idAccion, $DATA['saiu22id'], $sdetalle, $objDB);}
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
function f3022_db_Eliminar($saiu22id, $objDB, $bDebug=false){
	$iCodModulo=3022;
	$bAudita[4]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3022='lg/lg_3022_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3022)){$mensajes_3022='lg/lg_3022_es.php';}
	require $mensajes_todas;
	require $mensajes_3022;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	$saiu22id=numeros_validar($saiu22id);
	// Traer los datos para hacer las validaciones.
	if ($sError==''){
		$sSQL='SELECT * FROM saiu22telefonos WHERE saiu22id='.$saiu22id.'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$filabase=$objDB->sf($tabla);
			}else{
			$sError='No se encuentra el registro solicitado {Ref: '.$saiu22id.'}';
			}
		}
	if ($sError==''){
		if (!seg_revisa_permiso($iCodModulo, 4, $objDB)){
			$sError=$ERR['4'];
			}
		}
	if ($sError==''){
		$sSQL='SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=3022';
		$tablaor=$objDB->ejecutasql($sSQL);
		while ($filaor=$objDB->sf($tablaor)){
			$sSQL='SELECT '.$filaor['campoid'].' FROM '.$filaor['tabla'].' WHERE '.$filaor['camporev'].'='.$_REQUEST['saiu22id'].' LIMIT 0, 1';
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
		$sWhere='saiu22id='.$saiu22id.'';
		//$sWhere='saiu22consec='.$filabase['saiu22consec'].'';
		$sSQL='DELETE FROM saiu22telefonos WHERE '.$sWhere.';';
		$result=$objDB->ejecutasql($sSQL);
		if ($result==false){
			$sError=$ERR['falla_eliminar'].' .. <!-- '.$sSQL.' -->';
			}else{
			if ($bAudita[4]){seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $saiu22id, $sWhere, $objDB);}
			}
		}
	return array($sError, $iTipoError, $sDebug);
	}
function f3022_TituloBusqueda(){
	return 'Busqueda de Telefonos';
	}
function f3022_ParametrosBusqueda(){
	$sParams='<label class="Label90">Nombre</label><label><input id="b3022nombre" name="b3022nombre" type="text" value="" onchange="paginarbusqueda()" /></label>';
	return $sParams;
	}
function f3022_JavaScriptBusqueda($iModuloBusca){
	$sRes='var sCampo=window.document.frmedita.scampobusca.value;
var params=new Array();
params[100]=sCampo;
params[101]=window.document.frmedita.paginabusqueda.value;
params[102]=window.document.frmedita.lppfbusqueda.value;
params[103]=window.document.frmedita.b3022nombre.value;
xajax_f'.$iModuloBusca.'_HtmlBusqueda(params);';
	return $sRes;
	}
function f3022_TablaDetalleBusquedas($aParametros, $objDB){
	$res='';
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3022='lg/lg_3022_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3022)){$mensajes_3022='lg/lg_3022_es.php';}
	require $mensajes_todas;
	require $mensajes_3022;
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
		return array($sLeyenda.'<input id="paginaf3022" name="paginaf3022" type="hidden" value="'.$pagina.'"/><input id="lppf3022" name="lppf3022" type="hidden" value="'.$lineastabla.'"/>', $sDebug);
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
	$sTitulos='Consec, Id, Activo, Predet, Orden, Nombre, Numero';
	$sSQL='SELECT TB.saiu22consec, TB.saiu22id, TB.saiu22activo, TB.saiu22predet, TB.saiu22orden, TB.saiu22nombre, TB.saiu22numero 
FROM saiu22telefonos AS TB 
WHERE '.$sSQLadd1.'  '.$sSQLadd.'
ORDER BY TB.saiu22consec';
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
			//return array(utf8_encode($sErrConsulta.'<input id="paginaf3022" name="paginaf3022" type="hidden" value="'.$pagina.'"/><input id="lppf3022" name="lppf3022" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
			}
		if ((($registros-1)/$lineastabla)<($pagina-1)){$pagina=(int)(($registros-1)/$lineastabla)+1;}
		if ($registros>$lineastabla){
			$rbase=($pagina-1)*$lineastabla;
			$limite=' LIMIT '.$rbase.', '.$lineastabla;
			$tabladetalle=$objDB->ejecutasql($sSQL.$limite);
			}
		}
	$res=$sErrConsulta.$sLeyenda.'<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">
<thead class="fondoazul"><tr>
<td><b>'.$ETI['saiu22consec'].'</b></td>
<td><b>'.$ETI['saiu22activo'].'</b></td>
<td><b>'.$ETI['saiu22predet'].'</b></td>
<td><b>'.$ETI['saiu22orden'].'</b></td>
<td><b>'.$ETI['saiu22nombre'].'</b></td>
<td><b>'.$ETI['saiu22numero'].'</b></td>
<td align="right">
'.html_paginador('paginabusqueda', $registros, $lineastabla, $pagina, 'paginarbusqueda()').'
'.html_lpp('lppfbusqueda', $lineastabla, 'paginarbusqueda()').'
</td>
</tr></thead>';
	$tlinea=1;
	while($filadet=$objDB->sf($tabladetalle)){
		$sPrefijo='<a href="javascript:Devuelve(\''.$filadet['saiu22id'].'\');">';
		$sSufijo='</a>';
		$tlinea++;
		$et_saiu22activo=$sPrefijo.$ETI['no'].$sSufijo;
		if ($filadet['saiu22activo']=='S'){$et_saiu22activo=$sPrefijo.$ETI['si'].$sSufijo;}
		$res=$res.'<tr onmouseover="cambia_color_over(this);" onmouseout="cambia_color_out(this);">
<td>'.$sPrefijo.$filadet['saiu22consec'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu22activo'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu22predet'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu22orden'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['saiu22nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['saiu22numero']).$sSufijo.'</td>
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