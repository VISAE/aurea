<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2021 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.25.10c miércoles, 7 de abril de 2021
--- 2931 plab31emonitoresciclo
*/
/** Archivo lib2931.php.
* Libreria 2931 plab31emonitoresciclo.
* @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
* @date miércoles, 7 de abril de 2021
*/
function f2931_ExisteDato($datos){
	if(!is_array($datos)){$datos=json_decode(str_replace('\"','"',$datos),true);}
	$_SESSION['u_ultimominuto']=iminutoavance();
	$bHayLlave=true;
	$plab31consec=numeros_validar($datos[1]);
	if ($plab31consec==''){$bHayLlave=false;}
	if ($bHayLlave){
		require './app.php';
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		$sSQL='SELECT 1 FROM plab31emonitoresciclo WHERE plab31consec='.$plab31consec.'';
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
function f2931_Busquedas($aParametros){
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2931='lg/lg_2931_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2931)){$mensajes_2931='lg/lg_2931_es.php';}
	require $mensajes_todas;
	require $mensajes_2931;
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
		case 'plab32idtercero':
		require $APP->rutacomun.'lib111.php';
		$sTabla=f111_TablaDetalleBusquedas($aParametrosB, $objDB);
		$sTitulo='Busqueda de terceros';
		$sParams=f111_ParametrosBusqueda();
		$sJavaBusqueda=f111_JavaScriptBusqueda(2931);
		break;
		}
	$sTitulo='<h2>'.$ETI['titulo_2931'].' - '.$sTitulo.'</h2>';
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97titulo', 'innerHTML', $sTitulo);
	$objResponse->assign('div_97params', 'innerHTML', $sParams);
	$objResponse->assign('div_97tabla', 'innerHTML', $sTabla);
	$objResponse->setFunction('paginarbusqueda','',$sJavaBusqueda);
	$objResponse->call('expandesector(97)');
	return $objResponse;
	}
function f2931_HtmlBusqueda($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$sError='';
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$sDetalle='';
	switch($aParametros[100]){
		case 'plab32idtercero':
		require $APP->rutacomun.'lib111.php';
		$sDetalle=f111_TablaDetalleBusquedas($aParametros, $objDB);
		break;
		}
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97tabla', 'innerHTML', $sDetalle);
	return $objResponse;
	}
function f2931_TablaDetalleV2($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2931='lg/lg_2931_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2931)){$mensajes_2931='lg/lg_2931_es.php';}
	require $mensajes_todas;
	require $mensajes_2931;
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
	$sBotones='<input id="paginaf2931" name="paginaf2931" type="hidden" value="'.$pagina.'"/>
	<input id="lppf2931" name="lppf2931" type="hidden" value="'.$lineastabla.'"/>';
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
	$sSQLadd='1';
	$sSQLadd1='';
	//if ($aParametros[104]!=''){$sSQLadd=$sSQLadd.' AND TB.campo2 LIKE "%'.$aParametros[104].'%"';}
	//if ($aParametros[104]!=''){$sSQLadd1=$sSQLadd1.'TB.campo2 LIKE "%'.$aParametros[104].'%" AND ';}
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
	$sTitulos='Consec, Id, Vigente, Titulo, Convocatoria, Fechainicio, Fechafinal';
	$registros=0;
	$bGigante=false; //En caso de que la tabla sea muy grande pasarlo a true
	$sLimite='';
	if ($bGigante){
		$sSQL='SELECT COUNT(1) AS Total 
		FROM plab31emonitoresciclo AS TB 
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
	$sSQL='SELECT TB.plab31consec, TB.plab31id, TB.plab31vigente, TB.plab31titulo, TB.plab31idconvocatoria, TB.plab31fechainicio, TB.plab31fechafinal 
	FROM plab31emonitoresciclo AS TB 
	WHERE '.$sSQLadd1.'  '.$sSQLadd.'
	ORDER BY TB.plab31consec';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_2931" name="consulta_2931" type="hidden" value="'.$sSQLlista.'"/>
	<input id="titulos_2931" name="titulos_2931" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objDB->ejecutasql($sSQL.$sLimite);
	if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 2931: '.$sSQL.$sLimite.'<br>';}
	if ($tabladetalle==false){
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		//$sLeyenda=$sSQL;
		}else{
		if (!$bGigante){
			$registros=$objDB->nf($tabladetalle);
			if ($registros==0){
				//return array(cadena_codificar($sErrConsulta.'<input id="paginaf2931" name="paginaf2931" type="hidden" value="'.$pagina.'"/><input id="lppf2931" name="lppf2931" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
	<td><b>'.$ETI['plab31consec'].'</b></td>
	<td><b>'.$ETI['plab31vigente'].'</b></td>
	<td><b>'.$ETI['plab31titulo'].'</b></td>
	<td><b>'.$ETI['plab31idconvocatoria'].'</b></td>
	<td><b>'.$ETI['plab31fechainicio'].'</b></td>
	<td><b>'.$ETI['plab31fechafinal'].'</b></td>
	<td align="right">
	'.html_paginador('paginaf2931', $registros, $lineastabla, $pagina, 'paginarf2931()').'
	'.html_lpp('lppf2931', $lineastabla, 'paginarf2931()').'
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
		$et_plab31vigente=$sPrefijo.$ETI['no'].$sSufijo;
		if ($filadet['plab31vigente']=='S'){$et_plab31vigente=$sPrefijo.$ETI['si'].$sSufijo;}
		$et_plab31fechainicio='';
		if ($filadet['plab31fechainicio']!=0){$et_plab31fechainicio=fecha_desdenumero($filadet['plab31fechainicio']);}
		$et_plab31fechafinal='';
		if ($filadet['plab31fechafinal']!=0){$et_plab31fechafinal=fecha_desdenumero($filadet['plab31fechafinal']);}
		if ($bAbierta){
			$sLink='<a href="javascript:cargaridf2931('.$filadet['plab31id'].')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$res=$res.'<tr'.$sClass.'>
		<td>'.$sPrefijo.$filadet['plab31consec'].$sSufijo.'</td>
		<td>'.$sPrefijo.$filadet['plab31vigente'].$sSufijo.'</td>
		<td>'.$sPrefijo.cadena_notildes($filadet['plab31titulo']).$sSufijo.'</td>
		<td>'.$sPrefijo.$filadet['plab31idconvocatoria'].$sSufijo.'</td>
		<td>'.$sPrefijo.$et_plab31fechainicio.$sSufijo.'</td>
		<td>'.$sPrefijo.$et_plab31fechafinal.$sSufijo.'</td>
		<td>'.$sLink.'</td>
		</tr>';
		}
	$res=$res.'</table>
	<div class="salto5px"></div>
	</div>';
	$objDB->liberar($tabladetalle);
	return array(cadena_codificar($res), $sDebug);
	}
function f2931_HtmlTabla($aParametros){
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
	list($sDetalle, $sDebugTabla)=f2931_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f2931detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f2931_db_CargarPadre($DATA, $objDB, $bDebug=false){
	$sError='';
	$iTipoError=0;
	$sDebug='';
	require './app.php';
	if ($DATA['paso']==1){
		$sSQLcondi='plab31consec='.$DATA['plab31consec'].'';
		}else{
		$sSQLcondi='plab31id='.$DATA['plab31id'].'';
		}
	$sSQL='SELECT * FROM plab31emonitoresciclo WHERE '.$sSQLcondi;
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$DATA['plab31consec']=$fila['plab31consec'];
		$DATA['plab31id']=$fila['plab31id'];
		$DATA['plab31vigente']=$fila['plab31vigente'];
		$DATA['plab31titulo']=$fila['plab31titulo'];
		$DATA['plab31idconvocatoria']=$fila['plab31idconvocatoria'];
		$DATA['plab31fechainicio']=$fila['plab31fechainicio'];
		$DATA['plab31fechafinal']=$fila['plab31fechafinal'];
		$bcargo=true;
		$DATA['paso']=2;
		$DATA['boculta2931']=0;
		$bLimpiaHijos=true;
		}else{
		$DATA['paso']=0;
		}
	return array($DATA, $sError, $iTipoError, $sDebug);
	}
function f2931_db_GuardarV2($DATA, $objDB, $bDebug=false){
	$iCodModulo=2931;
	$bAudita[2]=true;
	$bAudita[3]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2931='lg/lg_2931_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2931)){$mensajes_2931='lg/lg_2931_es.php';}
	require $mensajes_todas;
	require $mensajes_2931;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	// -- Se inicia validando todas las posibles entradas de usuario.
	/*
	if (isset($DATA['plab31consec'])==0){$DATA['plab31consec']='';}
	if (isset($DATA['plab31id'])==0){$DATA['plab31id']='';}
	if (isset($DATA['plab31vigente'])==0){$DATA['plab31vigente']='';}
	if (isset($DATA['plab31titulo'])==0){$DATA['plab31titulo']='';}
	if (isset($DATA['plab31idconvocatoria'])==0){$DATA['plab31idconvocatoria']='';}
	if (isset($DATA['plab31fechainicio'])==0){$DATA['plab31fechainicio']='';}
	if (isset($DATA['plab31fechafinal'])==0){$DATA['plab31fechafinal']='';}
	*/
	$DATA['plab31consec']=numeros_validar($DATA['plab31consec']);
	$DATA['plab31vigente']=numeros_validar($DATA['plab31vigente']);
	$DATA['plab31titulo']=htmlspecialchars(trim($DATA['plab31titulo']));
	$DATA['plab31idconvocatoria']=numeros_validar($DATA['plab31idconvocatoria']);
	// -- Se inicializan las variables que puedan pasar vacias {Especialmente números}.
	//if ($DATA['plab31vigente']==''){$DATA['plab31vigente']=0;}
	//if ($DATA['plab31idconvocatoria']==''){$DATA['plab31idconvocatoria']=0;}
	// -- Seccion para validar los posibles causales de error.
	$sSepara=', ';
	if (true){
		if ($DATA['plab31fechafinal']==0){
			//$DATA['plab31fechafinal']=fecha_DiaMod();
			$sError=$ERR['plab31fechafinal'].$sSepara.$sError;
			}
		if ($DATA['plab31fechainicio']==0){
			//$DATA['plab31fechainicio']=fecha_DiaMod();
			$sError=$ERR['plab31fechainicio'].$sSepara.$sError;
			}
		if ($DATA['plab31idconvocatoria']==''){$sError=$ERR['plab31idconvocatoria'].$sSepara.$sError;}
		if ($DATA['plab31titulo']==''){$sError=$ERR['plab31titulo'].$sSepara.$sError;}
		if ($DATA['plab31vigente']==''){$sError=$ERR['plab31vigente'].$sSepara.$sError;}
		//Fin de las valiaciones NO LLAVE.
		}
	//Valiaciones de campos obligatorios en todo guardar.
	// -- Se verifican los valores de campos de otras tablas.
	$bQuitarCodigo=false;
	$sCampoCodigo='';
	if ($sError==''){
		if ($DATA['paso']==10){
			if ($DATA['plab31consec']==''){
				$DATA['plab31consec']=tabla_consecutivo('plab31emonitoresciclo', 'plab31consec', '', $objDB);
				if ($DATA['plab31consec']==-1){$sError=$objDB->serror;}
				$bQuitarCodigo=true;
				$sCampoCodigo='plab31consec';
				}else{
				if (!seg_revisa_permiso($iCodModulo, 8, $objDB)){
					$sError=$ERR['8'];
					$DATA['plab31consec']='';
					}
				}
			if ($sError==''){
				$sSQL='SELECT 1 FROM plab31emonitoresciclo WHERE plab31consec='.$DATA['plab31consec'].'';
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
			$DATA['plab31id']=tabla_consecutivo('plab31emonitoresciclo','plab31id', '', $objDB);
			if ($DATA['plab31id']==-1){$sError=$objDB->serror;}
			}
		}
	if ($sError==''){
		$bpasa=false;
		if ($DATA['paso']==10){
			$plab31fechainicio=fecha_DiaMod();
			$plab31fechafinal=fecha_DiaMod();
			$sCampos2931='plab31consec, plab31id, plab31vigente, plab31titulo, plab31idconvocatoria, 
			plab31fechainicio, plab31fechafinal';
			$sValores2931=''.$DATA['plab31consec'].', '.$DATA['plab31id'].', '.$DATA['plab31vigente'].', "'.$DATA['plab31titulo'].'", '.$DATA['plab31idconvocatoria'].', 
			"'.$DATA['plab31fechainicio'].'", "'.$DATA['plab31fechafinal'].'"';
			if ($APP->utf8==1){
				$sSQL='INSERT INTO plab31emonitoresciclo ('.$sCampos2931.') VALUES ('.cadena_codificar($sValores2931).');';
				$sdetalle=$sCampos2931.'['.cadena_codificar($sValores2931).']';
				}else{
				$sSQL='INSERT INTO plab31emonitoresciclo ('.$sCampos2931.') VALUES ('.$sValores2931.');';
				$sdetalle=$sCampos2931.'['.$sValores2931.']';
				}
			$idAccion=2;
			$bpasa=true;
			}else{
			$scampo[1]='plab31vigente';
			$scampo[2]='plab31titulo';
			$scampo[3]='plab31idconvocatoria';
			$scampo[4]='plab31fechainicio';
			$scampo[5]='plab31fechafinal';
			$sdato[1]=$DATA['plab31vigente'];
			$sdato[2]=$DATA['plab31titulo'];
			$sdato[3]=$DATA['plab31idconvocatoria'];
			$sdato[4]=$DATA['plab31fechainicio'];
			$sdato[5]=$DATA['plab31fechafinal'];
			$numcmod=5;
			$sWhere='plab31id='.$DATA['plab31id'].'';
			$sSQL='SELECT * FROM plab31emonitoresciclo WHERE '.$sWhere;
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
					$sdetalle=cadena_codificar($sdatos).'['.$sWhere.']';
					$sSQL='UPDATE plab31emonitoresciclo SET '.cadena_codificar($sdatos).' WHERE '.$sWhere.';';
					}else{
					$sdetalle=$sdatos.'['.$sWhere.']';
					$sSQL='UPDATE plab31emonitoresciclo SET '.$sdatos.' WHERE '.$sWhere.';';
					}
				$idAccion=3;
				}
			}
		if ($bpasa){
			if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Guardar 2931 '.$sSQL.'<br>';}
			$result=$objDB->ejecutasql($sSQL);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' [2931] ..<!-- '.$sSQL.' -->';
				if ($idAccion==2){
					$DATA['plab31id']='';
					$bQuitarCodigo=true;
					$DATA['paso']=0;
					}else{
					$DATA['paso']=2;
					}
				}else{
				if ($bAudita[$idAccion]){seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], $idAccion, $DATA['plab31id'], $sdetalle, $objDB);}
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
function f2931_db_Eliminar($plab31id, $objDB, $bDebug=false){
	$iCodModulo=2931;
	$bAudita[4]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2931='lg/lg_2931_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2931)){$mensajes_2931='lg/lg_2931_es.php';}
	require $mensajes_todas;
	require $mensajes_2931;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	$plab31id=numeros_validar($plab31id);
	// Traer los datos para hacer las validaciones.
	if ($sError==''){
		$sSQL='SELECT * FROM plab31emonitoresciclo WHERE plab31id='.$plab31id.'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$filabase=$objDB->sf($tabla);
			}else{
			$sError='No se encuentra el registro solicitado {Ref: '.$plab31id.'}';
			}
		}
	if ($sError==''){
		$sSQL='SELECT 1 FROM plab32emonitor WHERE plab32idciclo='.$filabase['plab31id'].'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$sError='Existen Participantes creados, no es posible eliminar';
			}
		}
	if ($sError==''){
		if (!seg_revisa_permiso($iCodModulo, 4, $objDB)){
			$sError=$ERR['4'];
			}
		}
	if ($sError==''){
		$sSQL='SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=2931';
		$tablaor=$objDB->ejecutasql($sSQL);
		while ($filaor=$objDB->sf($tablaor)){
			$sSQL='SELECT '.$filaor['campoid'].' FROM '.$filaor['tabla'].' WHERE '.$filaor['camporev'].'='.$_REQUEST['plab31id'].' LIMIT 0, 1';
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
		//$sSQL='DELETE FROM plab32emonitor WHERE plab32idciclo='.$filabase['plab31id'].'';
		//$tabla=$objDB->ejecutasql($sSQL);
		$sWhere='plab31id='.$plab31id.'';
		//$sWhere='plab31consec='.$filabase['plab31consec'].'';
		$sSQL='DELETE FROM plab31emonitoresciclo WHERE '.$sWhere.';';
		$result=$objDB->ejecutasql($sSQL);
		if ($result==false){
			$sError=$ERR['falla_eliminar'].' .. <!-- '.$sSQL.' -->';
			}else{
			if ($bAudita[4]){seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $plab31id, $sWhere, $objDB);}
			}
		}
	return array($sError, $iTipoError, $sDebug);
	}
function f2931_TituloBusqueda(){
	return 'Busqueda de E-monitores';
	}
function f2931_ParametrosBusqueda(){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2931='lg/lg_2931_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2931)){$mensajes_2931='lg/lg_2931_es.php';}
	require $mensajes_todas;
	require $mensajes_2931;
	$sParams='<label class="Label90">
	'.$ETI['msg_bnombre'].'
	</label>
	<label>
	<input id="b2931nombre" name="b2931nombre" type="text" value="" onchange="paginarbusqueda()" />
	</label>';
	return $sParams;
	}
function f2931_JavaScriptBusqueda($iModuloBusca){
	$sRes='var sCampo=window.document.frmedita.scampobusca.value;
	var params=new Array();
	params[100]=sCampo;
	params[101]=window.document.frmedita.paginabusqueda.value;
	params[102]=window.document.frmedita.lppfbusqueda.value;
	params[103]=window.document.frmedita.b2931nombre.value;
	xajax_f'.$iModuloBusca.'_HtmlBusqueda(params);';
	return $sRes;
	}
function f2931_TablaDetalleBusquedas($aParametros, $objDB){
	$res='';
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2931='lg/lg_2931_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2931)){$mensajes_2931='lg/lg_2931_es.php';}
	require $mensajes_todas;
	require $mensajes_2931;
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
		return array($sLeyenda.'<input id="paginaf2931" name="paginaf2931" type="hidden" value="'.$pagina.'"/><input id="lppf2931" name="lppf2931" type="hidden" value="'.$lineastabla.'"/>', $sDebug);
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
	$sTitulos='Consec, Id, Vigente, Titulo, Convocatoria, Fechainicio, Fechafinal';
	$sSQL='SELECT TB.plab31consec, TB.plab31id, TB.plab31vigente, TB.plab31titulo, TB.plab31idconvocatoria, TB.plab31fechainicio, TB.plab31fechafinal 
	FROM plab31emonitoresciclo AS TB 
	WHERE '.$sSQLadd1.'  '.$sSQLadd.'
	ORDER BY TB.plab31consec';
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
			//return array(cadena_codificar($sErrConsulta.'<input id="paginaf2931" name="paginaf2931" type="hidden" value="'.$pagina.'"/><input id="lppf2931" name="lppf2931" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
	<td><b>'.$ETI['plab31consec'].'</b></td>
	<td><b>'.$ETI['plab31vigente'].'</b></td>
	<td><b>'.$ETI['plab31titulo'].'</b></td>
	<td><b>'.$ETI['plab31idconvocatoria'].'</b></td>
	<td><b>'.$ETI['plab31fechainicio'].'</b></td>
	<td><b>'.$ETI['plab31fechafinal'].'</b></td>
	<td align="right">
	'.html_paginador('paginabusqueda', $registros, $lineastabla, $pagina, 'paginarbusqueda()').'
	'.html_lpp('lppfbusqueda', $lineastabla, 'paginarbusqueda()').'
	</td>
	</tr></thead>';
	$tlinea=1;
	while($filadet=$objDB->sf($tabladetalle)){
		$sPrefijo='<a href="javascript:Devuelve(\''.$filadet['plab31id'].'\');">';
		$sSufijo='</a>';
		$tlinea++;
		$et_plab31vigente=$sPrefijo.$ETI['no'].$sSufijo;
		if ($filadet['plab31vigente']=='S'){$et_plab31vigente=$sPrefijo.$ETI['si'].$sSufijo;}
		$et_plab31fechainicio='';
		if ($filadet['plab31fechainicio']!=0){$et_plab31fechainicio=fecha_desdenumero($filadet['plab31fechainicio']);}
		$et_plab31fechafinal='';
		if ($filadet['plab31fechafinal']!=0){$et_plab31fechafinal=fecha_desdenumero($filadet['plab31fechafinal']);}
		$res=$res.'<tr onmouseover="cambia_color_over(this);" onmouseout="cambia_color_out(this);">
		<td>'.$sPrefijo.$filadet['plab31consec'].$sSufijo.'</td>
		<td>'.$sPrefijo.$filadet['plab31vigente'].$sSufijo.'</td>
		<td>'.$sPrefijo.cadena_notildes($filadet['plab31titulo']).$sSufijo.'</td>
		<td>'.$sPrefijo.$filadet['plab31idconvocatoria'].$sSufijo.'</td>
		<td>'.$sPrefijo.$et_plab31fechainicio.$sSufijo.'</td>
		<td>'.$sPrefijo.$et_plab31fechafinal.$sSufijo.'</td>
		<td></td>
		</tr>';
		}
	$res=$res.'</table>
	<div class="salto5px"></div>
	</div>';
	$objDB->liberar($tabladetalle);
	return cadena_codificar($res);
	}
/** Función f2931_ProcesarArchivo.
* Esta función recibe un archivo y lo procesa.
* @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
* @param $DATA contiene las variables $_REQUEST del formulario de origen
* @param $ARCHIVO contiene las variables $_FILE del formulario de origen
* @param $objDB Objeto de base datos del tipo clsdbadmin
* @param $bDebug (Opcional), bandera para indicar si se generan datos de depuración
* @date miércoles, 7 de abril de 2021
*/
function f2931_ProcesarArchivo($DATA, $ARCHIVO, $objDB, $bDebug=false){
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
		$iActualizados=0;
		$plab32idciclo=$DATA['plab31id'];
		$plab32id=tabla_consecutivo('plab32emonitor', 'plab32id', '', $objDB);
		$plab32estado=1;
		$plab32fechaingreso=fecha_DiaMod();
		$plab32fechafin=0;
		//$sCampos2931='plab31consec, plab31id, plab31vigente, plab31titulo, plab31idconvocatoria, plab31fechainicio, plab31fechafinal';
		//$plab31id=tabla_consecutivo('plab31emonitoresciclo','plab31id', '', $objDB);
		$sCampos2932='plab32idciclo, plab32idtercero, plab32id, plab32estado, plab32fechaingreso, plab32fechafin';
		//$plab32idciclo=tabla_consecutivo('plab32emonitor','plab32idciclo', '', $objDB);
		$sDato=trim($objPHPExcel->getActiveSheet()->getCellByColumnAndRow(0, $iFila)->getValue());
		while($sDato!=''){
			$iDatos++;
			//Aqui se debe procesar
			$sErrLinea='';
			switch(strtoupper($sDato)){
				case 'CC':
				case 'CE':
				case 'PA':
				case 'DN':
				$sDato=strtoupper($sDato);
				break;
				default:
				$sErrLinea='Tipo de documento incorrecto';
				break;
				}
			if ($sErrLinea==''){
				$sDoc=trim($objPHPExcel->getActiveSheet()->getCellByColumnAndRow(1, $iFila)->getValue());
				if ($sDoc==''){
					$sErrLinea='Documento incorrecto';
					}else{
					if (htmlspecialchars($sDoc)!=$sDoc){$sErrLinea='Documento incorrecto';}
					}
				}
			if ($sErrLinea==''){
				$sSQL='SELECT unad11id FROM unad11terceros WHERE unad11tipodoc="'.$sDato.'" AND unad11doc="'.$sDoc.'"';
				$tabla=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($tabla)>0){
					$fila=$objDB->sf($tabla);
					$plab32idtercero=$fila['unad11id'];
					}else{
					$sErrLinea='Documento no encontrado.';
					}
				}
			if ($sErrLinea==''){
				$iActualizados++;
				$sValores2932=''.$plab32idciclo.', "'.$plab32idtercero.'", '.$plab32id.', '.$plab32estado.', "'.$plab32fechaingreso.'", 
				"'.$plab32fechafin.'"';
				$sSQL='INSERT INTO plab32emonitor ('.$sCampos2932.') VALUES ('.$sValores2932.');';
				$result=$objDB->ejecutasql($sSQL);
				if ($result==false){
					$sErrLinea='Error al intentar insertar el registro ['.$sSQL.']';
					}else{
					seg_auditar(2932, $_SESSION['unad_id_tercero'], 2, $plab32id, $sSQL, $objDB);
					$plab32id++;
					}
				}
			if ($sErrLinea!=''){
				if ($sInfoProceso!=''){$sInfoProceso=$sInfoProceso.'<br>';}
				$sInfoProceso=$sInfoProceso.'Linea '.$iFila.': '.$sErrLinea;
				}
			//$iActualizados++;
			//Leer el siguiente dato
			$iFila++;
			$sDato=trim($objPHPExcel->getActiveSheet()->getCellByColumnAndRow(0, $iFila)->getValue());
			}
		$sError='Registros totales '.$iDatos;
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