<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2022 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.28.0 lunes, 14 de febrero de 2022
--- 3067 saiu67dias
*/
/** Archivo lib3067.php.
* Libreria 3067 saiu67dias.
* @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
* @date lunes, 14 de febrero de 2022
*/
function f3067_ExisteDato($datos){
	if(!is_array($datos)){$datos=json_decode(str_replace('\"','"',$datos),true);}
	$_SESSION['u_ultimominuto']=iminutoavance();
	$bHayLlave=true;
	$saiu67dia=htmlspecialchars($datos[1]);
	if ($saiu67dia==''){$bHayLlave=false;}
	$saiu67idgrupo=numeros_validar($datos[2]);
	if ($saiu67idgrupo==''){$bHayLlave=false;}
	if ($bHayLlave){
		require './app.php';
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		$sSQL='SELECT 1 FROM saiu67dias WHERE saiu67dia="'.$saiu67dia.'" AND saiu67idgrupo='.$saiu67idgrupo.'';
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
function f3067_Busquedas($aParametros){
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3067='lg/lg_3067_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3067)){$mensajes_3067='lg/lg_3067_es.php';}
	require $mensajes_todas;
	require $mensajes_3067;
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$sCampo=$aParametros[1];
	$sTitulo=' {'.$sCampo.'}';
	if (isset($aParametros[2])==0){$aParametros[2]=0;}
	if (isset($aParametros[3])==0){$aParametros[3]=0;}
	$iPiel=iDefinirPiel($APP, 1);
	$sParams='';
	$sTabla='';
	$sJavaBusqueda='';
	$aParametrosB=array();
	$aParametrosB[101]=1;
	$aParametrosB[102]=20;
	switch($sCampo){
		}
	$sTitulo='<h2>'.$ETI['titulo_3067'].' - '.$sTitulo.'</h2>';
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97titulo', 'innerHTML', $sTitulo);
	$objResponse->assign('div_97params', 'innerHTML', $sParams);
	$objResponse->assign('div_97tabla', 'innerHTML', $sTabla);
	$objResponse->setFunction('paginarbusqueda','',$sJavaBusqueda);
	$objResponse->call('expandesector(97)');
	return $objResponse;
	}
function f3067_HtmlBusqueda($aParametros){
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
function f3067_TablaDetalleV2($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	//$mensajes_3000='lg/lg_3000_'.$_SESSION['unad_idioma'].'.php';
	//if (!file_exists($mensajes_3000)){$mensajes_3000='lg/lg_3000_es.php';}
	$mensajes_3067='lg/lg_3067_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3067)){$mensajes_3067='lg/lg_3067_es.php';}
	require $mensajes_todas;
	//require $mensajes_3000;
	require $mensajes_3067;
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	if (isset($aParametros[100])==0){$aParametros[100]=$_SESSION['unad_id_tercero'];}
	if (isset($aParametros[101])==0){$aParametros[101]=1;}
	if (isset($aParametros[102])==0){$aParametros[102]=20;}
	//if (isset($aParametros[103])==0){$aParametros[103]='';}
	//if (isset($aParametros[104])==0){$aParametros[104]='';}
	$idTercero=$aParametros[100];
	$sDebug='';
	if (true){
		//Leemos los parametros de entrada.
		$pagina=$aParametros[101];
		$lineastabla=$aParametros[102];
		//$bNombre=trim($aParametros[103]);
		//$bListar=numeros_validar($aParametros[104]);
		}
	$bAbierta=true;
	//$sSQL='SELECT Campo FROM Tabla WHERE Id='.$sValorId;
	//$tabla=$objDB->ejecutasql($sSQL);
	//if ($objDB->nf($tabla)>0){
		//$fila=$objDB->sf($tabla);
		//if ($fila['Campo']!='S'){$bAbierta=true;}
		//}
	$sLeyenda='';
	$sBotones='<input id="paginaf3067" name="paginaf3067" type="hidden" value="'.$pagina.'"/>
	<input id="lppf3067" name="lppf3067" type="hidden" value="'.$lineastabla.'"/>';
	if ($sLeyenda!=''){
		$sLeyenda='<div class="salto1px"></div>
		<div class="GrupoCamposAyuda">
		'.$sLeyenda.'
		<div class="salto1px"></div>
		</div>';
		return array($sLeyenda.$sBotones, $sDebug);
		die();
		}
	$iPiel=iDefinirPiel($APP, 1);
	/*
	$aEstado=array();
	$sSQL='SELECT id, nombre FROM tabla';
	$tabla=$objDB->ejecutasql($sSQL);
	while($fila=$objDB->sf($tabla)){
		$aEstado[$fila['id']]=cadena_notildes($fila['nombre']);
		}
	*/
	if (true){
		//Esta condición la ponemos para mantener la conparación con los arhcivos tipo e
		$sSQLadd='1';
		$sSQLadd1='';
		//if ($aParametros[104]!=''){$sSQLadd=$sSQLadd.' AND TB.campo2 LIKE "%'.$aParametros[104].'%"';}
		//if ($aParametros[104]!=''){$sSQLadd1=$sSQLadd1.'TB.campo2 LIKE "%'.$aParametros[104].'%" AND ';}
		/*
		if ($bNombre!=''){
			$sBase=strtoupper($bNombre);
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
		}
	//-- Area para saltar comparaciones con los archivos tipo e
	//-- Fin del area no comparada
	$sTitulos='Dia, Grupo, Diasem, Habil, Orden';
	$registros=0;
	$bGigante=false; //En caso de que la tabla sea muy grande pasarlo a true
	$sLimite='';
	if ($bGigante){
		$sSQL='SELECT COUNT(1) AS Total 
		FROM saiu67dias AS TB 
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
	$sSQL='SELECT TB.saiu67dia, TB.saiu67idgrupo, TB.saiu67diasem, TB.saiu67habil, TB.saiu67orden 
	FROM saiu67dias AS TB 
	WHERE '.$sSQLadd1.'  '.$sSQLadd.'
	ORDER BY TB.saiu67dia, TB.saiu67idgrupo';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_3067" name="consulta_3067" type="hidden" value="'.$sSQLlista.'"/>
	<input id="titulos_3067" name="titulos_3067" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objDB->ejecutasql($sSQL.$sLimite);
	if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 3067: '.$sSQL.$sLimite.'<br>';}
	if ($tabladetalle==false){
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		//$sLeyenda=$sSQL;
		}else{
		if (!$bGigante){
			$registros=$objDB->nf($tabladetalle);
			//if ($registros==0){
				//return array($sErrConsulta.$sBotones, $sDebug);
				//}
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
	<td><b>'.$ETI['saiu67dia'].'</b></td>
	<td><b>'.$ETI['saiu67idgrupo'].'</b></td>
	<td><b>'.$ETI['saiu67diasem'].'</b></td>
	<td><b>'.$ETI['saiu67habil'].'</b></td>
	<td><b>'.$ETI['saiu67orden'].'</b></td>
	<td align="right">
	'.html_paginador('paginaf3067', $registros, $lineastabla, $pagina, 'paginarf3067()').'
	'.html_lpp('lppf3067', $lineastabla, 'paginarf3067()').'
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
		$et_saiu67dia='';
		if ($filadet['saiu67dia']!=0){$et_saiu67dia=fecha_desdenumero($filadet['saiu67dia']);}
		if ($bAbierta){
			$sLink='<a href="javascript:cargadato('."'".$filadet['saiu67dia']."','".$filadet['saiu67idgrupo']."'".')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$res=$res.'<tr'.$sClass.'>
		<td>'.$sPrefijo.$et_saiu67dia.$sSufijo.'</td>
		<td>'.$sPrefijo.$filadet['saiu67idgrupo'].$sSufijo.'</td>
		<td>'.$sPrefijo.$filadet['saiu67diasem'].$sSufijo.'</td>
		<td>'.$sPrefijo.$filadet['saiu67habil'].$sSufijo.'</td>
		<td>'.$sPrefijo.$filadet['saiu67orden'].$sSufijo.'</td>
		<td>'.$sLink.'</td>
		</tr>';
		}
	$res=$res.'</table>
	<div class="salto5px"></div>
	</div>';
	$objDB->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
	}
function f3067_HtmlTabla($aParametros){
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
	list($sDetalle, $sDebugTabla)=f3067_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f3067detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f3067_db_CargarPadre($DATA, $objDB, $bDebug=false){
	$sError='';
	$iTipoError=0;
	$sDebug='';
	require './app.php';
	$sSQL='SELECT * FROM saiu67dias WHERE saiu67dia="'.$DATA['saiu67dia'].'" AND saiu67idgrupo='.$DATA['saiu67idgrupo'].'';
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$DATA['saiu67dia']=$fila['saiu67dia'];
		$DATA['saiu67idgrupo']=$fila['saiu67idgrupo'];
		$DATA['saiu67diasem']=$fila['saiu67diasem'];
		$DATA['saiu67habil']=$fila['saiu67habil'];
		$DATA['saiu67orden']=$fila['saiu67orden'];
		$bcargo=true;
		$DATA['paso']=2;
		$DATA['boculta3067']=0;
		$bLimpiaHijos=true;
		}else{
		$DATA['paso']=0;
		}
	return array($DATA, $sError, $iTipoError, $sDebug);
	}
function f3067_db_GuardarV2($DATA, $objDB, $bDebug=false, $idTercero=0){
	$iCodModulo=3067;
	$bAudita[2]=true;
	$bAudita[3]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3067='lg/lg_3067_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3067)){$mensajes_3067='lg/lg_3067_es.php';}
	require $mensajes_todas;
	require $mensajes_3067;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	if ($idTercero==0){$idTercero=$_SESSION['unad_id_tercero'];}
	// -- Se inicia validando todas las posibles entradas de usuario.
	/*
	if (isset($DATA['saiu67dia'])==0){$DATA['saiu67dia']='';}
	if (isset($DATA['saiu67idgrupo'])==0){$DATA['saiu67idgrupo']='';}
	if (isset($DATA['saiu67diasem'])==0){$DATA['saiu67diasem']='';}
	if (isset($DATA['saiu67habil'])==0){$DATA['saiu67habil']='';}
	if (isset($DATA['saiu67orden'])==0){$DATA['saiu67orden']='';}
	*/
	$DATA['saiu67idgrupo']=numeros_validar($DATA['saiu67idgrupo']);
	$DATA['saiu67diasem']=numeros_validar($DATA['saiu67diasem']);
	$DATA['saiu67habil']=numeros_validar($DATA['saiu67habil']);
	$DATA['saiu67orden']=numeros_validar($DATA['saiu67orden']);
	// -- Se inicializan las variables que puedan pasar vacias {Especialmente números}.
	//if ($DATA['saiu67diasem']==''){$DATA['saiu67diasem']=0;}
	//if ($DATA['saiu67habil']==''){$DATA['saiu67habil']=0;}
	// -- Seccion para validar los posibles causales de error.
	$sSepara=', ';
	if (true){
		//if ($DATA['saiu67orden']==''){$sError=$ERR['saiu67orden'].$sSepara.$sError;}//ORDEN
		if ($DATA['saiu67habil']==''){$sError=$ERR['saiu67habil'].$sSepara.$sError;}
		if ($DATA['saiu67diasem']==''){$sError=$ERR['saiu67diasem'].$sSepara.$sError;}
		//Fin de las valiaciones NO LLAVE.
		}
	//Valiaciones de campos obligatorios en todo guardar.
	if ($DATA['saiu67idgrupo']==''){$sError=$ERR['saiu67idgrupo'];}
	if ($DATA['saiu67dia']==0){
		//$DATA['saiu67dia']=fecha_DiaMod();
		$sError=$ERR['saiu67dia'];
		}
	// -- Se verifican los valores de campos de otras tablas.
	$bQuitarCodigo=false;
	$sCampoCodigo='';
	if ($sError==''){
		if ($DATA['paso']==10){
			$sSQL='SELECT 1 FROM saiu67dias WHERE saiu67dia="'.$DATA['saiu67dia'].'" AND saiu67idgrupo='.$DATA['saiu67idgrupo'].'';
			$result=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($result)!=0){
				$sError=$ERR['existe'];
				}else{
				list($bDevuelve, $sDebugP)=seg_revisa_permisoV3($iCodModulo, 2, $idTercero, $objDB);
				if (!$bDevuelve){$sError=$ERR['2'];}
				}
			}else{
			list($bDevuelve, $sDebugP)=seg_revisa_permisoV3($iCodModulo, 3, $idTercero, $objDB);
			if (!$bDevuelve){$sError=$ERR['3'];}
			}
		}
	if ($sError==''){
		if ($DATA['paso']==10){
			//Preparar el Id, Si no lo hay se quita la comprobación.
			//Datos adicionales al iniciar un registro.
			}
		}
	if ($sError==''){
		if ((int)$DATA['saiu67orden']==0){$DATA['saiu67orden']=$DATA['CampoOrden'];}
		$bPasa=false;
		if ($DATA['paso']==10){
			$sCampos3067='saiu67dia, saiu67idgrupo, saiu67diasem, saiu67habil, saiu67orden';
			$sValores3067=''.$DATA['saiu67dia'].', '.$DATA['saiu67idgrupo'].', '.$DATA['saiu67diasem'].', '.$DATA['saiu67habil'].', "'.$DATA['saiu67orden'].'"';
			if ($APP->utf8==1){
				$sSQL='INSERT INTO saiu67dias ('.$sCampos3067.') VALUES ('.utf8_encode($sValores3067).');';
				$sdetalle=$sCampos3067.'['.utf8_encode($sValores3067).']';
				}else{
				$sSQL='INSERT INTO saiu67dias ('.$sCampos3067.') VALUES ('.$sValores3067.');';
				$sdetalle=$sCampos3067.'['.$sValores3067.']';
				}
			$idAccion=2;
			$bPasa=true;
			}else{
			$scampo[1]='saiu67diasem';
			$scampo[2]='saiu67habil';
			$scampo[3]='saiu67orden';
			$sdato[1]=$DATA['saiu67diasem'];
			$sdato[2]=$DATA['saiu67habil'];
			$sdato[3]=$DATA['saiu67orden'];
			$numcmod=3;
			$sWhere='saiu67dia="'.$DATA['saiu67dia'].'" AND saiu67idgrupo='.$DATA['saiu67idgrupo'].'';
			$sSQL='SELECT * FROM saiu67dias WHERE '.$sWhere;
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
						$bPasa=true;
						}
					}
				}
			if ($bPasa){
				if ($APP->utf8==1){
					$sdetalle=utf8_encode($sdatos).'['.$sWhere.']';
					$sSQL='UPDATE saiu67dias SET '.utf8_encode($sdatos).' WHERE '.$sWhere.';';
					}else{
					$sdetalle=$sdatos.'['.$sWhere.']';
					$sSQL='UPDATE saiu67dias SET '.$sdatos.' WHERE '.$sWhere.';';
					}
				$idAccion=3;
				}
			}
		if ($bPasa){
			if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Guardar 3067 '.$sSQL.'<br>';}
			$result=$objDB->ejecutasql($sSQL);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' [3067] ..<!-- '.$sSQL.' -->';
				if ($idAccion==2){
					$bQuitarCodigo=true;
					$DATA['paso']=0;
					}else{
					$DATA['paso']=2;
					}
				}else{
				if ($bAudita[$idAccion]){seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], $idAccion, 0, $sdetalle, $objDB);}
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
function f3067_db_Eliminar($id3067, $objDB, $bDebug=false){
	$iCodModulo=3067;
	$bAudita[4]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3067='lg/lg_3067_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3067)){$mensajes_3067='lg/lg_3067_es.php';}
	require $mensajes_todas;
	require $mensajes_3067;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	// Traer los datos para hacer las validaciones.
	if ($sError==''){
		$sSQL='SELECT * FROM saiu67dias WHERE id3067='.$id3067.'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$filabase=$objDB->sf($tabla);
			}else{
			$sError='No se encuentra el registro solicitado {Ref: '.$id3067.'}';
			}
		}
	if ($sError==''){
		if (isset($idTercero)==0){$idTercero=$_SESSION['unad_id_tercero'];}
		list($bDevuelve, $sDebugP)=seg_revisa_permisoV3($iCodModulo, 4, $idTercero, $objDB);
		if (!$bDevuelve){
			$sError=$ERR['4'];
			}
		}
	/*
	if ($sError==''){
		$sSQL='SELECT * FROM tablaexterna WHERE idexterno='.$_REQUEST['CampoRevisa'].' LIMIT 0, 1';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$sError=$ERR['p1'];//Incluya la explicacion al error en el archivo de idioma
			}
		}
	*/
	if ($sError==''){
		$sWhere='saiu67idgrupo='.$filabase['saiu67idgrupo'].' AND saiu67dia="'.$filabase['saiu67dia'].'"';
		$sSQL='DELETE FROM saiu67dias WHERE '.$sWhere.';';
		$result=$objDB->ejecutasql($sSQL);
		if ($result==false){
			$sError=$ERR['falla_eliminar'].' .. <!-- '.$sSQL.' -->';
			}else{
			if ($bAudita[4]){seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, 0, $sWhere, $objDB);}
			}
		}
	return array($sError, $iTipoError, $sDebug);
	}
function f3067_TituloBusqueda(){
	return 'Busqueda de Dias habiles';
	}
function f3067_ParametrosBusqueda(){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3067='lg/lg_3067_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3067)){$mensajes_3067='lg/lg_3067_es.php';}
	require $mensajes_todas;
	require $mensajes_3067;
	$sParams='<label class="Label90">
	'.$ETI['msg_bnombre'].'
	</label>
	<label>
	<input id="b3067nombre" name="b3067nombre" type="text" value="" onchange="paginarbusqueda()" />
	</label>';
	return $sParams;
	}
function f3067_JavaScriptBusqueda($iModuloBusca){
	$sRes='var sCampo=window.document.frmedita.scampobusca.value;
	var params=new Array();
	params[100]=sCampo;
	params[101]=window.document.frmedita.paginabusqueda.value;
	params[102]=window.document.frmedita.lppfbusqueda.value;
	params[103]=window.document.frmedita.b3067nombre.value;
	xajax_f'.$iModuloBusca.'_HtmlBusqueda(params);';
	return $sRes;
	}
function f3067_TablaDetalleBusquedas($aParametros, $objDB){
	$res='';
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3067='lg/lg_3067_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3067)){$mensajes_3067='lg/lg_3067_es.php';}
	require $mensajes_todas;
	require $mensajes_3067;
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	if (isset($aParametros[100])==0){$aParametros[100]=$_SESSION['unad_id_tercero'];}
	if (isset($aParametros[101])==0){$aParametros[101]=1;}
	if (isset($aParametros[102])==0){$aParametros[102]=20;}
	if (isset($aParametros[103])==0){$aParametros[103]='';}
	if (isset($aParametros[104])==0){$aParametros[104]='';}
	$idTercero=$aParametros[100];
	$pagina=$aParametros[101];
	$lineastabla=$aParametros[102];
	//$bNombre=trim($aParametros[103]);
	//$bListar=numeros_validar($aParametros[104]);
	$bAbierta=true;
	//$sSQL='SELECT Campo FROM Tabla WHERE Id='.$sValorId;
	//$tabla=$objDB->ejecutasql($sSQL);
	//if ($objDB->nf($tabla)>0){
		//$fila=$objDB->sf($tabla);
		//if ($fila['Campo']!='S'){$bAbierta=true;}
		//}
	$sLeyenda='';
	$sBotones='<input id="paginaf3067" name="paginaf3067" type="hidden" value="'.$pagina.'"/>
	<input id="lppf3067" name="lppf3067" type="hidden" value="'.$lineastabla.'"/>';
	if ($sLeyenda!=''){
		$sLeyenda='<div class="salto1px"></div>
		<div class="GrupoCamposAyuda">
		'.$sLeyenda.'
		<div class="salto1px"></div>
		</div>';
		return array($sLeyenda.$sBotones, $sDebug);
		die();
		}
	$iPiel=iDefinirPiel($APP, 1);
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
	$sTitulos='Dia, Grupo, Diasem, Habil, Orden';
	$sSQL='SELECT TB.saiu67dia, TB.saiu67idgrupo, TB.saiu67diasem, TB.saiu67habil, TB.saiu67orden 
	FROM saiu67dias AS TB 
	WHERE '.$sSQLadd1.'  '.$sSQLadd.'
	ORDER BY TB.saiu67dia, TB.saiu67idgrupo';
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
		//if ($registros==0){
			//return array($sErrConsulta.$sBotones, $sDebug);
			//}
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
	<td><b>'.$ETI['saiu67dia'].'</b></td>
	<td><b>'.$ETI['saiu67idgrupo'].'</b></td>
	<td><b>'.$ETI['saiu67diasem'].'</b></td>
	<td><b>'.$ETI['saiu67habil'].'</b></td>
	<td><b>'.$ETI['saiu67orden'].'</b></td>
	<td align="right">
	'.html_paginador('paginabusqueda', $registros, $lineastabla, $pagina, 'paginarbusqueda()').'
	'.html_lpp('lppfbusqueda', $lineastabla, 'paginarbusqueda()').'
	</td>
	</tr></thead>';
	$tlinea=1;
	while($filadet=$objDB->sf($tabladetalle)){
		$sPrefijo='<a href="javascript:Devuelve(\''.$filadet[''].'\');">';
		$sSufijo='</a>';
		$tlinea++;
		$et_saiu67dia='';
		if ($filadet['saiu67dia']!=0){$et_saiu67dia=fecha_desdenumero($filadet['saiu67dia']);}
		$res=$res.'<tr onmouseover="cambia_color_over(this);" onmouseout="cambia_color_out(this);">
		<td>'.$sPrefijo.$et_saiu67dia.$sSufijo.'</td>
		<td>'.$sPrefijo.$filadet['saiu67idgrupo'].$sSufijo.'</td>
		<td>'.$sPrefijo.$filadet['saiu67diasem'].$sSufijo.'</td>
		<td>'.$sPrefijo.$filadet['saiu67habil'].$sSufijo.'</td>
		<td>'.$sPrefijo.$filadet['saiu67orden'].$sSufijo.'</td>
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