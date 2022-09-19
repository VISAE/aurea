<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2020 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.24.1 martes, 11 de febrero de 2020
--- Modelo Versión 2.25.10b miércoles, 27 de enero de 2021
--- 3001 saiu01claseser
*/
/** Archivo lib3001.php.
* Libreria 3001 saiu01claseser.
* @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
* @date martes, 11 de febrero de 2020
*/
function f3001_ExisteDato($datos){
	if(!is_array($datos)){$datos=json_decode(str_replace('\"','"',$datos),true);}
	$_SESSION['u_ultimominuto']=iminutoavance();
	$bHayLlave=true;
	$saiu01consec=numeros_validar($datos[1]);
	if ($saiu01consec==''){$bHayLlave=false;}
	if ($bHayLlave){
		require './app.php';
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		$sSQL='SELECT 1 FROM saiu01claseser WHERE saiu01consec='.$saiu01consec.'';
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
function f3001_Busquedas($aParametros){
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3001='lg/lg_3001_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3001)){$mensajes_3001='lg/lg_3001_es.php';}
	require $mensajes_todas;
	require $mensajes_3001;
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
	$sTitulo='<h2>'.$ETI['titulo_3001'].' - '.$sTitulo.'</h2>';
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97titulo', 'innerHTML', $sTitulo);
	$objResponse->assign('div_97params', 'innerHTML', $sParams);
	$objResponse->assign('div_97tabla', 'innerHTML', $sTabla);
	$objResponse->setFunction('paginarbusqueda','',$sJavaBusqueda);
	$objResponse->call('expandesector(97)');
	return $objResponse;
	}
function f3001_HtmlBusqueda($aParametros){
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
function f3001_TablaDetalleV2($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3001='lg/lg_3001_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3001)){$mensajes_3001='lg/lg_3001_es.php';}
	require $mensajes_todas;
	require $mensajes_3001;
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
	$sBotones='<input id="paginaf3001" name="paginaf3001" type="hidden" value="'.$pagina.'"/>
	<input id="lppf3001" name="lppf3001" type="hidden" value="'.$lineastabla.'"/>';
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
	$sTitulos='Consec, Id, Activa, Orden, Titulo, Descripcion';
	$sSQL='SELECT TB.saiu01consec, TB.saiu01id, TB.saiu01activa, TB.saiu01orden, TB.saiu01titulo, TB.saiu01descripcion 
FROM saiu01claseser AS TB 
WHERE '.$sSQLadd1.'  '.$sSQLadd.'
ORDER BY TB.saiu01consec';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_3001" name="consulta_3001" type="hidden" value="'.$sSQLlista.'"/>
<input id="titulos_3001" name="titulos_3001" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objDB->ejecutasql($sSQL);
	if ($tabladetalle==false){
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 3001: '.$sSQL.'<br>';}
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		//$sLeyenda=$sSQL;
		}else{
		$registros=$objDB->nf($tabladetalle);
		if ($registros==0){
			//return array(utf8_encode($sErrConsulta.'<input id="paginaf3001" name="paginaf3001" type="hidden" value="'.$pagina.'"/><input id="lppf3001" name="lppf3001" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
	<td><b>'.$ETI['saiu01consec'].'</b></td>
	<td><b>'.$ETI['saiu01activa'].'</b></td>
	<td><b>'.$ETI['saiu01orden'].'</b></td>
	<td><b>'.$ETI['saiu01titulo'].'</b></td>
	<td align="right">
	'.html_paginador('paginaf3001', $registros, $lineastabla, $pagina, 'paginarf3001()').'
	'.html_lpp('lppf3001', $lineastabla, 'paginarf3001()').'
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
		$et_saiu01activa=$sPrefijo.$ETI['no'].$sSufijo;
		if ($filadet['saiu01activa']=='S'){$et_saiu01activa=$sPrefijo.$ETI['si'].$sSufijo;}
		if ($bAbierta){
			$sLink='<a href="javascript:cargaridf3001('.$filadet['saiu01id'].')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$res=$res.'<tr'.$sClass.'>
		<td>'.$sPrefijo.$filadet['saiu01consec'].$sSufijo.'</td>
		<td>'.$sPrefijo.$et_saiu01activa.$sSufijo.'</td>
		<td>'.$sPrefijo.$filadet['saiu01orden'].$sSufijo.'</td>
		<td>'.$sPrefijo.cadena_notildes($filadet['saiu01titulo']).$sSufijo.'</td>
		<td>'.$sLink.'</td>
		</tr>';
		}
	$res=$res.'</table>
<div class="salto5px"></div>
</div>';
	$objDB->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
	}
function f3001_HtmlTabla($aParametros){
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
	list($sDetalle, $sDebugTabla)=f3001_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f3001detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f3001_db_CargarPadre($DATA, $objDB, $bDebug=false){
	$sError='';
	$iTipoError=0;
	$sDebug='';
	require './app.php';
	if ($DATA['paso']==1){
		$sSQLcondi='saiu01consec='.$DATA['saiu01consec'].'';
		}else{
		$sSQLcondi='saiu01id='.$DATA['saiu01id'].'';
		}
	$sSQL='SELECT * FROM saiu01claseser WHERE '.$sSQLcondi;
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$DATA['saiu01consec']=$fila['saiu01consec'];
		$DATA['saiu01id']=$fila['saiu01id'];
		$DATA['saiu01activa']=$fila['saiu01activa'];
		$DATA['saiu01orden']=$fila['saiu01orden'];
		$DATA['saiu01titulo']=$fila['saiu01titulo'];
		$DATA['saiu01descripcion']=$fila['saiu01descripcion'];
		$DATA['saiu01salto']=$fila['saiu01salto'];
		$bcargo=true;
		$DATA['paso']=2;
		$DATA['boculta3001']=0;
		$bLimpiaHijos=true;
		}else{
		$DATA['paso']=0;
		}
	return array($DATA, $sError, $iTipoError, $sDebug);
	}
function f3001_db_GuardarV2($DATA, $objDB, $bDebug=false){
	$iCodModulo=3001;
	$bAudita[2]=true;
	$bAudita[3]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3001='lg/lg_3001_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3001)){$mensajes_3001='lg/lg_3001_es.php';}
	require $mensajes_todas;
	require $mensajes_3001;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	// -- Se inicia validando todas las posibles entradas de usuario.
	/*
	if (isset($DATA['saiu01consec'])==0){$DATA['saiu01consec']='';}
	if (isset($DATA['saiu01id'])==0){$DATA['saiu01id']='';}
	if (isset($DATA['saiu01activa'])==0){$DATA['saiu01activa']='';}
	if (isset($DATA['saiu01orden'])==0){$DATA['saiu01orden']='';}
	if (isset($DATA['saiu01titulo'])==0){$DATA['saiu01titulo']='';}
	if (isset($DATA['saiu01descripcion'])==0){$DATA['saiu01descripcion']='';}
	if (isset($DATA['saiu01salto'])==0){$DATA['saiu01salto']='';}
	*/
	$DATA['saiu01consec']=numeros_validar($DATA['saiu01consec']);
	$DATA['saiu01activa']=htmlspecialchars(trim($DATA['saiu01activa']));
	$DATA['saiu01orden']=numeros_validar($DATA['saiu01orden']);
	$DATA['saiu01titulo']=htmlspecialchars(trim($DATA['saiu01titulo']));
	$DATA['saiu01descripcion']=htmlspecialchars(trim($DATA['saiu01descripcion']));
	$DATA['saiu01salto']=htmlspecialchars(trim($DATA['saiu01salto']));
	// -- Se inicializan las variables que puedan pasar vacias {Especialmente números}.
	//if ($DATA['saiu01orden']==''){$DATA['saiu01orden']=0;}
	// -- Seccion para validar los posibles causales de error.
	$sSepara=', ';
	if (true){
		//if ($DATA['saiu01descripcion']==''){$sError=$ERR['saiu01descripcion'].$sSepara.$sError;}
		if ($DATA['saiu01titulo']==''){$sError=$ERR['saiu01titulo'].$sSepara.$sError;}
		if ($DATA['saiu01orden']==''){$sError=$ERR['saiu01orden'].$sSepara.$sError;}
		if ($DATA['saiu01activa']==''){$sError=$ERR['saiu01activa'].$sSepara.$sError;}
		//Fin de las valiaciones NO LLAVE.
		}
	//Valiaciones de campos obligatorios en todo guardar.
	// -- Se verifican los valores de campos de otras tablas.
	$bQuitarCodigo=false;
	$sCampoCodigo='';
	if ($sError==''){
		if ($DATA['paso']==10){
			if ($DATA['saiu01consec']==''){
				$DATA['saiu01consec']=tabla_consecutivo('saiu01claseser', 'saiu01consec', '', $objDB);
				if ($DATA['saiu01consec']==-1){$sError=$objDB->serror;}
				$bQuitarCodigo=true;
				$sCampoCodigo='saiu01consec';
				}else{
				if (!seg_revisa_permiso($iCodModulo, 8, $objDB)){
					$sError=$ERR['8'];
					$DATA['saiu01consec']='';
					}
				}
			if ($sError==''){
				$sSQL='SELECT 1 FROM saiu01claseser WHERE saiu01consec='.$DATA['saiu01consec'].'';
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
	$bQuitarCodigo=false;
	if ($sError==''){
		if ($DATA['paso']==10){
			//Preparar el Id, Si no lo hay se quita la comprobación.
			$DATA['saiu01id']=tabla_consecutivo('saiu01claseser','saiu01id', '', $objDB);
			if ($DATA['saiu01id']==-1){$sError=$objDB->serror;}
			}
		}
	if ($sError==''){
		if (get_magic_quotes_gpc()==1){$DATA['saiu01descripcion']=stripslashes($DATA['saiu01descripcion']);}
		//Si el campo saiu01descripcion permite html quite la linea htmlspecialchars para el campo y habilite la siguiente linea:
		//$saiu01descripcion=addslashes($DATA['saiu01descripcion']);
		$saiu01descripcion=str_replace('"', '\"', $DATA['saiu01descripcion']);
		$bpasa=false;
		if ($DATA['paso']==10){
			$sCampos3001='saiu01consec, saiu01id, saiu01activa, saiu01orden, saiu01titulo, 
saiu01descripcion, saiu01salto';
			$sValores3001=''.$DATA['saiu01consec'].', '.$DATA['saiu01id'].', "'.$DATA['saiu01activa'].'", '.$DATA['saiu01orden'].', "'.$DATA['saiu01titulo'].'", 
"'.$saiu01descripcion.'", "'.$DATA['saiu01salto'].'"';
			if ($APP->utf8==1){
				$sSQL='INSERT INTO saiu01claseser ('.$sCampos3001.') VALUES ('.utf8_encode($sValores3001).');';
				$sdetalle=$sCampos3001.'['.utf8_encode($sValores3001).']';
				}else{
				$sSQL='INSERT INTO saiu01claseser ('.$sCampos3001.') VALUES ('.$sValores3001.');';
				$sdetalle=$sCampos3001.'['.$sValores3001.']';
				}
			$idAccion=2;
			$bpasa=true;
			}else{
			$scampo[1]='saiu01activa';
			$scampo[2]='saiu01orden';
			$scampo[3]='saiu01titulo';
			$scampo[4]='saiu01descripcion';
			$scampo[5]='saiu01salto';
			$sdato[1]=$DATA['saiu01activa'];
			$sdato[2]=$DATA['saiu01orden'];
			$sdato[3]=$DATA['saiu01titulo'];
			$sdato[4]=$saiu01descripcion;
			$sdato[5]=$DATA['saiu01salto'];
			$numcmod=5;
			$sWhere='saiu01id='.$DATA['saiu01id'].'';
			$sSQL='SELECT * FROM saiu01claseser WHERE '.$sWhere;
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
					$sSQL='UPDATE saiu01claseser SET '.utf8_encode($sdatos).' WHERE '.$sWhere.';';
					}else{
					$sdetalle=$sdatos.'['.$sWhere.']';
					$sSQL='UPDATE saiu01claseser SET '.$sdatos.' WHERE '.$sWhere.';';
					}
				$idAccion=3;
				}
			}
		if ($bpasa){
			if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Guardar 3001 '.$sSQL.'<br>';}
			$result=$objDB->ejecutasql($sSQL);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' [3001] ..<!-- '.$sSQL.' -->';
				if ($idAccion==2){
					$DATA['saiu01id']='';
					$bQuitarCodigo=true;
					$DATA['paso']=0;
					}else{
					$DATA['paso']=2;
					}
				}else{
				if ($bAudita[$idAccion]){seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], $idAccion, $DATA['saiu01id'], $sdetalle, $objDB);}
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
function f3001_db_Eliminar($saiu01id, $objDB, $bDebug=false){
	$iCodModulo=3001;
	$bAudita[4]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3001='lg/lg_3001_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3001)){$mensajes_3001='lg/lg_3001_es.php';}
	require $mensajes_todas;
	require $mensajes_3001;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	$saiu01id=numeros_validar($saiu01id);
	// Traer los datos para hacer las validaciones.
	if ($sError==''){
		$sSQL='SELECT * FROM saiu01claseser WHERE saiu01id='.$saiu01id.'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$filabase=$objDB->sf($tabla);
			}else{
			$sError='No se encuentra el registro solicitado {Ref: '.$saiu01id.'}';
			}
		}
	if ($sError==''){
		if (!seg_revisa_permiso($iCodModulo, 4, $objDB)){
			$sError=$ERR['4'];
			}
		}
	if ($sError==''){
		$sSQL='SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=3001';
		$tablaor=$objDB->ejecutasql($sSQL);
		while ($filaor=$objDB->sf($tablaor)){
			$sSQL='SELECT '.$filaor['campoid'].' FROM '.$filaor['tabla'].' WHERE '.$filaor['camporev'].'='.$_REQUEST['saiu01id'].' LIMIT 0, 1';
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
		$sWhere='saiu01id='.$saiu01id.'';
		//$sWhere='saiu01consec='.$filabase['saiu01consec'].'';
		$sSQL='DELETE FROM saiu01claseser WHERE '.$sWhere.';';
		$result=$objDB->ejecutasql($sSQL);
		if ($result==false){
			$sError=$ERR['falla_eliminar'].' .. <!-- '.$sSQL.' -->';
			}else{
			if ($bAudita[4]){seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $saiu01id, $sWhere, $objDB);}
			}
		}
	return array($sError, $iTipoError, $sDebug);
	}
function f3001_TituloBusqueda(){
	return 'Busqueda de Clases de servicios';
	}
function f3001_ParametrosBusqueda(){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3001='lg/lg_3001_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3001)){$mensajes_3001='lg/lg_3001_es.php';}
	require $mensajes_todas;
	require $mensajes_3001;
	$sParams='<label class="Label90">
'.$ETI['msg_bnombre'].'
</label>
<label>
<input id="b3001nombre" name="b3001nombre" type="text" value="" onchange="paginarbusqueda()" />
</label>';
	return $sParams;
	}
function f3001_JavaScriptBusqueda($iModuloBusca){
	$sRes='var sCampo=window.document.frmedita.scampobusca.value;
var params=new Array();
params[100]=sCampo;
params[101]=window.document.frmedita.paginabusqueda.value;
params[102]=window.document.frmedita.lppfbusqueda.value;
params[103]=window.document.frmedita.b3001nombre.value;
xajax_f'.$iModuloBusca.'_HtmlBusqueda(params);';
	return $sRes;
	}
function f3001_TablaDetalleBusquedas($aParametros, $objDB){
	$res='';
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3001='lg/lg_3001_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3001)){$mensajes_3001='lg/lg_3001_es.php';}
	require $mensajes_todas;
	require $mensajes_3001;
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
		return array($sLeyenda.'<input id="paginaf3001" name="paginaf3001" type="hidden" value="'.$pagina.'"/><input id="lppf3001" name="lppf3001" type="hidden" value="'.$lineastabla.'"/>', $sDebug);
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
	$sTitulos='Consec, Id, Activa, Orden, Titulo, Descripcion';
	$sSQL='SELECT TB.saiu01consec, TB.saiu01id, TB.saiu01activa, TB.saiu01orden, TB.saiu01titulo, TB.saiu01descripcion 
FROM saiu01claseser AS TB 
WHERE '.$sSQLadd1.'  '.$sSQLadd.'
ORDER BY TB.saiu01consec';
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
			//return array(utf8_encode($sErrConsulta.'<input id="paginaf3001" name="paginaf3001" type="hidden" value="'.$pagina.'"/><input id="lppf3001" name="lppf3001" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
<td><b>'.$ETI['saiu01consec'].'</b></td>
<td><b>'.$ETI['saiu01activa'].'</b></td>
<td><b>'.$ETI['saiu01orden'].'</b></td>
<td><b>'.$ETI['saiu01titulo'].'</b></td>
<td><b>'.$ETI['saiu01descripcion'].'</b></td>
<td align="right">
'.html_paginador('paginabusqueda', $registros, $lineastabla, $pagina, 'paginarbusqueda()').'
'.html_lpp('lppfbusqueda', $lineastabla, 'paginarbusqueda()').'
</td>
</tr>';
	$tlinea=1;
	while($filadet=$objDB->sf($tabladetalle)){
		$sPrefijo='<a href="javascript:Devuelve(\''.$filadet['saiu01id'].'\');">';
		$sSufijo='</a>';
		$tlinea++;
		$et_saiu01activa=$sPrefijo.$ETI['no'].$sSufijo;
		if ($filadet['saiu01activa']=='S'){$et_saiu01activa=$sPrefijo.$ETI['si'].$sSufijo;}
		$res=$res.'<tr onmouseover="cambia_color_over(this);" onmouseout="cambia_color_out(this);">
<td>'.$sPrefijo.$filadet['saiu01consec'].$sSufijo.'</td>
<td>'.$sPrefijo.$et_saiu01activa.$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu01orden'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['saiu01titulo']).$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu01descripcion'].$sSufijo.'</td>
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