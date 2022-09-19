<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2021 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.25.10c lunes, 5 de abril de 2021
--- 3053 saiu53manuales
*/
/** Archivo lib3053.php.
* Libreria 3053 saiu53manuales.
* @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
* @date lunes, 5 de abril de 2021
*/
function f3053_ExisteDato($datos){
	if(!is_array($datos)){$datos=json_decode(str_replace('\"','"',$datos),true);}
	$_SESSION['u_ultimominuto']=iminutoavance();
	$bHayLlave=true;
	$saiu53consec=numeros_validar($datos[1]);
	if ($saiu53consec==''){$bHayLlave=false;}
	if ($bHayLlave){
		require './app.php';
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		$sSQL='SELECT 1 FROM saiu53manuales WHERE saiu53consec='.$saiu53consec.'';
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
function f3053_Busquedas($aParametros){
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3053='lg/lg_3053_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3053)){$mensajes_3053='lg/lg_3053_es.php';}
	require $mensajes_todas;
	require $mensajes_3053;
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
	$sTitulo='<h2>'.$ETI['titulo_3053'].' - '.$sTitulo.'</h2>';
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97titulo', 'innerHTML', $sTitulo);
	$objResponse->assign('div_97params', 'innerHTML', $sParams);
	$objResponse->assign('div_97tabla', 'innerHTML', $sTabla);
	$objResponse->setFunction('paginarbusqueda','',$sJavaBusqueda);
	$objResponse->call('expandesector(97)');
	return $objResponse;
	}
function f3053_HtmlBusqueda($aParametros){
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
function f3053_TablaDetalleV2($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3053='lg/lg_3053_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3053)){$mensajes_3053='lg/lg_3053_es.php';}
	require $mensajes_todas;
	require $mensajes_3053;
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	if (isset($aParametros[100])==0){$aParametros[100]=$_SESSION['unad_id_tercero'];}
	if (isset($aParametros[101])==0){$aParametros[101]=1;}
	if (isset($aParametros[102])==0){$aParametros[102]=20;}
	//if (isset($aParametros[103])==0){$aParametros[103]='';}
	//if (isset($aParametros[104])==0){$aParametros[104]='';}
	//$aParametros[103]=numeros_validar($aParametros[103]);
	//$aParametros[104]=numeros_validar($aParametros[104]);
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
	$sBotones='<input id="paginaf3053" name="paginaf3053" type="hidden" value="'.$pagina.'"/>
	<input id="lppf3053" name="lppf3053" type="hidden" value="'.$lineastabla.'"/>';
	if ($sLeyenda!=''){
		$sLeyenda='<div class="salto1px"></div>
		<div class="GrupoCamposAyuda">
		'.$sLeyenda.'
		<div class="salto1px"></div>
		</div>';
		return array($sLeyenda.$sBotones, $sDebug);
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
	//if ($aParametros[104]!=''){$sSQLadd=$sSQLadd.' AND TB.campo2 LIKE "%'.$aParametros[104].'%"';}
	//if ($aParametros[104]!=''){$sSQLadd1=$sSQLadd1.'TB.campo2 LIKE "%'.$aParametros[104].'%" AND ';}
	
	if ($aParametros[103]!=''){
		$sBase=trim(strtoupper($aParametros[103]));
		$aNoms=explode(' ', $sBase);
		for ($k=1;$k<=count($aNoms);$k++){
			$sCadena=$aNoms[$k-1];
			if ($sCadena!=''){
				//$sSQLadd=$sSQLadd.' AND T6.unad11razonsocial LIKE "%'.$sCadena.'%"';
				if ($sSQLadd1!=''){$sSQLadd1=$sSQLadd1.' AND ';}
				$sSQLadd1=$sSQLadd1.'TB.saiu53titulo LIKE "%'.$sCadena.'%"';
				}
			}
		}
	if ($sSQLadd1!=''){$sSQLadd1=' WHERE '.$sSQLadd1;}
	$sTitulos='Consec, Id, Vigente, Publico, Titulo, Descripcion';
	$registros=0;
	$bGigante=false; //En caso de que la tabla sea muy grande pasarlo a true
	$sLimite='';
	if ($bGigante){
		$sSQL='SELECT COUNT(1) AS Total 
		FROM saiu53manuales AS TB 
		'.$sSQLadd1.'';
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
	$sSQL='SELECT TB.saiu53consec, TB.saiu53id, TB.saiu53vigente, TB.saiu53publico, TB.saiu53titulo, TB.saiu53descripcion 
	FROM saiu53manuales AS TB 
	'.$sSQLadd1.' 
	ORDER BY TB.saiu53titulo, TB.saiu53consec';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_3053" name="consulta_3053" type="hidden" value="'.$sSQLlista.'"/>
	<input id="titulos_3053" name="titulos_3053" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objDB->ejecutasql($sSQL.$sLimite);
	if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 3053: '.$sSQL.$sLimite.'<br>';}
	if ($tabladetalle==false){
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		//$sLeyenda=$sSQL;
		}else{
		if (!$bGigante){
			$registros=$objDB->nf($tabladetalle);
			if ($registros==0){
				//return array(utf8_encode($sErrConsulta.'<input id="paginaf3053" name="paginaf3053" type="hidden" value="'.$pagina.'"/><input id="lppf3053" name="lppf3053" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
				}
			if ((($registros-1)/$lineastabla)<($pagina-1)){$pagina=(int)(($registros-1)/$lineastabla)+1;}
			if ($registros>$lineastabla){
				$rbase=($pagina-1)*$lineastabla;
				$sLimite=' LIMIT '.$rbase.', '.$lineastabla;
				$tabladetalle=$objDB->ejecutasql($sSQL.$sLimite);
				}
			}
		}
	$res=$sErrConsulta.$sLeyenda;
	$res=$res.'<div class="table-responsive">
	<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">
	<thead class="fondoazul"><tr>
	<td><b>'.$ETI['saiu53consec'].'</b></td>
	<td><b>'.$ETI['saiu53vigente'].'</b></td>
	<td><b>'.$ETI['saiu53publico'].'</b></td>
	<td><b>'.$ETI['saiu53titulo'].'</b></td>
	<td align="right">
	'.html_paginador('paginaf3053', $registros, $lineastabla, $pagina, 'paginarf3053()').'
	'.html_lpp('lppf3053', $lineastabla, 'paginarf3053()').'
	</td>
	</tr></thead>';
	$tlinea=1;
	while($filadet=$objDB->sf($tabladetalle)){
		$sPrefijo='';
		$sSufijo='';
		$sClass=' class="resaltetabla"';
		$sLink='';
		$et_saiu53vigente=$sPrefijo.$ETI['si'].$sSufijo;
		if ($filadet['saiu53vigente']==0){
			$sPrefijo='<span class="rojo">';
			$sSufijo='</span>';
			$et_saiu53vigente=$sPrefijo.$ETI['no'].$sSufijo;
			}
		if(($tlinea%2)!=0){$sClass='';}
		$tlinea++;
		if ($bAbierta){
			$sLink='<a href="javascript:cargaridf3053('.$filadet['saiu53id'].')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$res=$res.'<tr'.$sClass.'>
		<td>'.$sPrefijo.$filadet['saiu53consec'].$sSufijo.'</td>
		<td>'.$sPrefijo.$et_saiu53vigente.$sSufijo.'</td>
		<td>'.$sPrefijo.$asaiu53publico[$filadet['saiu53publico']].$sSufijo.'</td>
		<td>'.$sPrefijo.cadena_notildes($filadet['saiu53titulo']).$sSufijo.'</td>
		<td>'.$sLink.'</td>
		</tr>';
		}
	$res=$res.'</table>
	<div class="salto5px"></div>
	</div>';
	$objDB->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
	}
function f3053_HtmlTabla($aParametros){
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
	list($sDetalle, $sDebugTabla)=f3053_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f3053detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f3053_db_CargarPadre($DATA, $objDB, $bDebug=false){
	$sError='';
	$iTipoError=0;
	$sDebug='';
	require './app.php';
	if ($DATA['paso']==1){
		$sSQLcondi='saiu53consec='.$DATA['saiu53consec'].'';
		}else{
		$sSQLcondi='saiu53id='.$DATA['saiu53id'].'';
		}
	$sSQL='SELECT * FROM saiu53manuales WHERE '.$sSQLcondi;
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$DATA['saiu53consec']=$fila['saiu53consec'];
		$DATA['saiu53id']=$fila['saiu53id'];
		$DATA['saiu53vigente']=$fila['saiu53vigente'];
		$DATA['saiu53publico']=$fila['saiu53publico'];
		$DATA['saiu53titulo']=$fila['saiu53titulo'];
		$DATA['saiu53descripcion']=$fila['saiu53descripcion'];
		$bcargo=true;
		$DATA['paso']=2;
		$DATA['boculta3053']=0;
		$bLimpiaHijos=true;
		}else{
		$DATA['paso']=0;
		}
	return array($DATA, $sError, $iTipoError, $sDebug);
	}
function f3053_db_GuardarV2($DATA, $objDB, $bDebug=false){
	$iCodModulo=3053;
	$bAudita[2]=true;
	$bAudita[3]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3053='lg/lg_3053_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3053)){$mensajes_3053='lg/lg_3053_es.php';}
	require $mensajes_todas;
	require $mensajes_3053;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	// -- Se inicia validando todas las posibles entradas de usuario.
	/*
	if (isset($DATA['saiu53consec'])==0){$DATA['saiu53consec']='';}
	if (isset($DATA['saiu53id'])==0){$DATA['saiu53id']='';}
	if (isset($DATA['saiu53vigente'])==0){$DATA['saiu53vigente']='';}
	if (isset($DATA['saiu53publico'])==0){$DATA['saiu53publico']='';}
	if (isset($DATA['saiu53titulo'])==0){$DATA['saiu53titulo']='';}
	if (isset($DATA['saiu53descripcion'])==0){$DATA['saiu53descripcion']='';}
	*/
	$DATA['saiu53consec']=numeros_validar($DATA['saiu53consec']);
	$DATA['saiu53vigente']=numeros_validar($DATA['saiu53vigente']);
	$DATA['saiu53publico']=numeros_validar($DATA['saiu53publico']);
	$DATA['saiu53titulo']=htmlspecialchars(trim($DATA['saiu53titulo']));
	$DATA['saiu53descripcion']=htmlspecialchars(trim($DATA['saiu53descripcion']));
	// -- Se inicializan las variables que puedan pasar vacias {Especialmente números}.
	//if ($DATA['saiu53vigente']==''){$DATA['saiu53vigente']=0;}
	//if ($DATA['saiu53publico']==''){$DATA['saiu53publico']=0;}
	// -- Seccion para validar los posibles causales de error.
	$sSepara=', ';
	if (true){
		//if ($DATA['saiu53descripcion']==''){$sError=$ERR['saiu53descripcion'].$sSepara.$sError;}
		if ($DATA['saiu53titulo']==''){$sError=$ERR['saiu53titulo'].$sSepara.$sError;}
		if ($DATA['saiu53publico']==''){$sError=$ERR['saiu53publico'].$sSepara.$sError;}
		if ($DATA['saiu53vigente']==''){$sError=$ERR['saiu53vigente'].$sSepara.$sError;}
		//Fin de las valiaciones NO LLAVE.
		}
	//Valiaciones de campos obligatorios en todo guardar.
	// -- Se verifican los valores de campos de otras tablas.
	$bQuitarCodigo=false;
	$sCampoCodigo='';
	if ($sError==''){
		if ($DATA['paso']==10){
			if ($DATA['saiu53consec']==''){
				$DATA['saiu53consec']=tabla_consecutivo('saiu53manuales', 'saiu53consec', '', $objDB);
				if ($DATA['saiu53consec']==-1){$sError=$objDB->serror;}
				$bQuitarCodigo=true;
				$sCampoCodigo='saiu53consec';
				}else{
				if (!seg_revisa_permiso($iCodModulo, 8, $objDB)){
					$sError=$ERR['8'];
					$DATA['saiu53consec']='';
					}
				}
			if ($sError==''){
				$sSQL='SELECT 1 FROM saiu53manuales WHERE saiu53consec='.$DATA['saiu53consec'].'';
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
			$DATA['saiu53id']=tabla_consecutivo('saiu53manuales','saiu53id', '', $objDB);
			if ($DATA['saiu53id']==-1){$sError=$objDB->serror;}
			}
		}
	if ($sError==''){
		if (get_magic_quotes_gpc()==1){$DATA['saiu53descripcion']=stripslashes($DATA['saiu53descripcion']);}
		//Si el campo saiu53descripcion permite html quite la linea htmlspecialchars para el campo y habilite la siguiente linea:
		//$saiu53descripcion=addslashes($DATA['saiu53descripcion']);
		$saiu53descripcion=str_replace('"', '\"', $DATA['saiu53descripcion']);
		$bpasa=false;
		if ($DATA['paso']==10){
			$sCampos3053='saiu53consec, saiu53id, saiu53vigente, saiu53publico, saiu53titulo, 
			saiu53descripcion';
			$sValores3053=''.$DATA['saiu53consec'].', '.$DATA['saiu53id'].', '.$DATA['saiu53vigente'].', '.$DATA['saiu53publico'].', "'.$DATA['saiu53titulo'].'", 
			"'.$saiu53descripcion.'"';
			if ($APP->utf8==1){
				$sSQL='INSERT INTO saiu53manuales ('.$sCampos3053.') VALUES ('.utf8_encode($sValores3053).');';
				$sdetalle=$sCampos3053.'['.utf8_encode($sValores3053).']';
				}else{
				$sSQL='INSERT INTO saiu53manuales ('.$sCampos3053.') VALUES ('.$sValores3053.');';
				$sdetalle=$sCampos3053.'['.$sValores3053.']';
				}
			$idAccion=2;
			$bpasa=true;
			}else{
			$scampo[1]='saiu53vigente';
			$scampo[2]='saiu53publico';
			$scampo[3]='saiu53titulo';
			$scampo[4]='saiu53descripcion';
			$sdato[1]=$DATA['saiu53vigente'];
			$sdato[2]=$DATA['saiu53publico'];
			$sdato[3]=$DATA['saiu53titulo'];
			$sdato[4]=$saiu53descripcion;
			$numcmod=4;
			$sWhere='saiu53id='.$DATA['saiu53id'].'';
			$sSQL='SELECT * FROM saiu53manuales WHERE '.$sWhere;
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
					$sSQL='UPDATE saiu53manuales SET '.utf8_encode($sdatos).' WHERE '.$sWhere.';';
					}else{
					$sdetalle=$sdatos.'['.$sWhere.']';
					$sSQL='UPDATE saiu53manuales SET '.$sdatos.' WHERE '.$sWhere.';';
					}
				$idAccion=3;
				}
			}
		if ($bpasa){
			if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Guardar 3053 '.$sSQL.'<br>';}
			$result=$objDB->ejecutasql($sSQL);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' [3053] ..<!-- '.$sSQL.' -->';
				if ($idAccion==2){
					$DATA['saiu53id']='';
					$bQuitarCodigo=true;
					$DATA['paso']=0;
					}else{
					$DATA['paso']=2;
					}
				}else{
				if ($bAudita[$idAccion]){seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], $idAccion, $DATA['saiu53id'], $sdetalle, $objDB);}
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
function f3053_db_Eliminar($saiu53id, $objDB, $bDebug=false){
	$iCodModulo=3053;
	$bAudita[4]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3053='lg/lg_3053_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3053)){$mensajes_3053='lg/lg_3053_es.php';}
	require $mensajes_todas;
	require $mensajes_3053;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	$saiu53id=numeros_validar($saiu53id);
	// Traer los datos para hacer las validaciones.
	if ($sError==''){
		$sSQL='SELECT * FROM saiu53manuales WHERE saiu53id='.$saiu53id.'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$filabase=$objDB->sf($tabla);
			}else{
			$sError='No se encuentra el registro solicitado {Ref: '.$saiu53id.'}';
			}
		}
	if ($sError==''){
		$sSQL='SELECT 1 FROM saiu54manualperfil WHERE saiu54idmanual='.$filabase['saiu53id'].'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$sError='Existen Perfiles creados, no es posible eliminar';
			}
		}
	if ($sError==''){
		$sSQL='SELECT 1 FROM saiu55manualversion WHERE saiu55idmanual='.$filabase['saiu53id'].'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$sError='Existen Manuales creados, no es posible eliminar';
			}
		}
	if ($sError==''){
		if (!seg_revisa_permiso($iCodModulo, 4, $objDB)){
			$sError=$ERR['4'];
			}
		}
	if ($sError==''){
		$sSQL='SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=3053';
		$tablaor=$objDB->ejecutasql($sSQL);
		while ($filaor=$objDB->sf($tablaor)){
			$sSQL='SELECT '.$filaor['campoid'].' FROM '.$filaor['tabla'].' WHERE '.$filaor['camporev'].'='.$_REQUEST['saiu53id'].' LIMIT 0, 1';
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
		//$sSQL='DELETE FROM saiu54manualperfil WHERE saiu54idmanual='.$filabase['saiu53id'].'';
		//$tabla=$objDB->ejecutasql($sSQL);
		//$sSQL='DELETE FROM saiu55manualversion WHERE saiu55idmanual='.$filabase['saiu53id'].'';
		//$tabla=$objDB->ejecutasql($sSQL);
		$sWhere='saiu53id='.$saiu53id.'';
		//$sWhere='saiu53consec='.$filabase['saiu53consec'].'';
		$sSQL='DELETE FROM saiu53manuales WHERE '.$sWhere.';';
		$result=$objDB->ejecutasql($sSQL);
		if ($result==false){
			$sError=$ERR['falla_eliminar'].' .. <!-- '.$sSQL.' -->';
			}else{
			if ($bAudita[4]){seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $saiu53id, $sWhere, $objDB);}
			}
		}
	return array($sError, $iTipoError, $sDebug);
	}
function f3053_TituloBusqueda(){
	return 'Busqueda de Repositorio de manuales';
	}
function f3053_ParametrosBusqueda(){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3053='lg/lg_3053_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3053)){$mensajes_3053='lg/lg_3053_es.php';}
	require $mensajes_todas;
	require $mensajes_3053;
	$sParams='<label class="Label90">
	'.$ETI['msg_bnombre'].'
	</label>
	<label>
	<input id="b3053nombre" name="b3053nombre" type="text" value="" onchange="paginarbusqueda()" />
	</label>';
	return $sParams;
	}
function f3053_JavaScriptBusqueda($iModuloBusca){
	$sRes='var sCampo=window.document.frmedita.scampobusca.value;
	var params=new Array();
	params[100]=sCampo;
	params[101]=window.document.frmedita.paginabusqueda.value;
	params[102]=window.document.frmedita.lppfbusqueda.value;
	params[103]=window.document.frmedita.b3053nombre.value;
	xajax_f'.$iModuloBusca.'_HtmlBusqueda(params);';
	return $sRes;
	}
function f3053_TablaDetalleBusquedas($aParametros, $objDB){
	$res='';
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3053='lg/lg_3053_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3053)){$mensajes_3053='lg/lg_3053_es.php';}
	require $mensajes_todas;
	require $mensajes_3053;
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
		return array($sLeyenda.'<input id="paginaf3053" name="paginaf3053" type="hidden" value="'.$pagina.'"/><input id="lppf3053" name="lppf3053" type="hidden" value="'.$lineastabla.'"/>', $sDebug);
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
	$sTitulos='Consec, Id, Vigente, Publico, Titulo, Descripcion';
	$sSQL='SELECT TB.saiu53consec, TB.saiu53id, TB.saiu53vigente, TB.saiu53publico, TB.saiu53titulo, TB.saiu53descripcion 
	FROM saiu53manuales AS TB 
	WHERE '.$sSQLadd1.'  '.$sSQLadd.'
	ORDER BY TB.saiu53consec';
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
			//return array(utf8_encode($sErrConsulta.'<input id="paginaf3053" name="paginaf3053" type="hidden" value="'.$pagina.'"/><input id="lppf3053" name="lppf3053" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
			}
		if ((($registros-1)/$lineastabla)<($pagina-1)){$pagina=(int)(($registros-1)/$lineastabla)+1;}
		if ($registros>$lineastabla){
			$rbase=($pagina-1)*$lineastabla;
			$limite=' LIMIT '.$rbase.', '.$lineastabla;
			$tabladetalle=$objDB->ejecutasql($sSQL.$limite);
			}
		}
	$res=$sErrConsulta.$sLeyenda;
	$res=$res.'<div class="table-responsive">
	<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">
	<thead class="fondoazul"><tr>
	<td><b>'.$ETI['saiu53consec'].'</b></td>
	<td><b>'.$ETI['saiu53vigente'].'</b></td>
	<td><b>'.$ETI['saiu53publico'].'</b></td>
	<td><b>'.$ETI['saiu53titulo'].'</b></td>
	<td><b>'.$ETI['saiu53descripcion'].'</b></td>
	<td align="right">
	'.html_paginador('paginabusqueda', $registros, $lineastabla, $pagina, 'paginarbusqueda()').'
	'.html_lpp('lppfbusqueda', $lineastabla, 'paginarbusqueda()').'
	</td>
	</tr></thead>';
	$tlinea=1;
	while($filadet=$objDB->sf($tabladetalle)){
		$sPrefijo='<a href="javascript:Devuelve(\''.$filadet['saiu53id'].'\');">';
		$sSufijo='</a>';
		$tlinea++;
		$et_saiu53vigente=$sPrefijo.$ETI['no'].$sSufijo;
		if ($filadet['saiu53vigente']=='S'){$et_saiu53vigente=$sPrefijo.$ETI['si'].$sSufijo;}
		$res=$res.'<tr onmouseover="cambia_color_over(this);" onmouseout="cambia_color_out(this);">
		<td>'.$sPrefijo.$filadet['saiu53consec'].$sSufijo.'</td>
		<td>'.$sPrefijo.$filadet['saiu53vigente'].$sSufijo.'</td>
		<td>'.$sPrefijo.$filadet['saiu53publico'].$sSufijo.'</td>
		<td>'.$sPrefijo.cadena_notildes($filadet['saiu53titulo']).$sSufijo.'</td>
		<td>'.$sPrefijo.$filadet['saiu53descripcion'].$sSufijo.'</td>
		<td></td>
		</tr>';
		}
	$res=$res.'</table>
	<div class="salto5px"></div>
	</div>';
	$objDB->liberar($tabladetalle);
	return utf8_encode($res);
	}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------
?>