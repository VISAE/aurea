<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2021 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.25.10c jueves, 6 de mayo de 2021
--- Modelo Versión 2.26.3b miércoles, 21 de julio de 2021
--- 3061 saiu61comunicados
*/
/** Archivo lib3061.php.
* Libreria 3061 saiu61comunicados.
* @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
* @date jueves, 6 de mayo de 2021
*/
function f3061_ExisteDato($datos){
	if(!is_array($datos)){$datos=json_decode(str_replace('\"','"',$datos),true);}
	$_SESSION['u_ultimominuto']=iminutoavance();
	$bHayLlave=true;
	$saiu61consec=numeros_validar($datos[1]);
	if ($saiu61consec==''){$bHayLlave=false;}
	if ($bHayLlave){
		require './app.php';
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		$sSQL='SELECT 1 FROM saiu61comunicados WHERE saiu61consec='.$saiu61consec.'';
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
function f3061_Busquedas($aParametros){
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3061='lg/lg_3061_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3061)){$mensajes_3061='lg/lg_3061_es.php';}
	require $mensajes_todas;
	require $mensajes_3061;
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
		case 'saiu62idtercero':
		require $APP->rutacomun.'lib111.php';
		$sTabla=f111_TablaDetalleBusquedas($aParametrosB, $objDB);
		$sTitulo='Busqueda de terceros';
		$sParams=f111_ParametrosBusqueda();
		$sJavaBusqueda=f111_JavaScriptBusqueda(3061);
		break;
		}
	$sTitulo='<h2>'.$ETI['titulo_3061'].' - '.$sTitulo.'</h2>';
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97titulo', 'innerHTML', $sTitulo);
	$objResponse->assign('div_97params', 'innerHTML', $sParams);
	$objResponse->assign('div_97tabla', 'innerHTML', $sTabla);
	$objResponse->setFunction('paginarbusqueda','',$sJavaBusqueda);
	$objResponse->call('expandesector(97)');
	return $objResponse;
	}
function f3061_HtmlBusqueda($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$sError='';
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$sDetalle='';
	switch($aParametros[100]){
		case 'saiu62idtercero':
		require $APP->rutacomun.'lib111.php';
		$sDetalle=f111_TablaDetalleBusquedas($aParametros, $objDB);
		break;
		}
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97tabla', 'innerHTML', $sDetalle);
	return $objResponse;
	}
function f3061_TablaDetalleV2($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3061='lg/lg_3061_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3061)){$mensajes_3061='lg/lg_3061_es.php';}
	require $mensajes_todas;
	require $mensajes_3061;
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
	$sBotones='<input id="paginaf3061" name="paginaf3061" type="hidden" value="'.$pagina.'"/>
	<input id="lppf3061" name="lppf3061" type="hidden" value="'.$lineastabla.'"/>';
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
	$sTitulos='Consec, Id, Orden, Vigente, Titulo, Unidad, Fecha, Fechapublica, Fechadespublica, Cuerpo, Poblacion, Formaentrega';
	$registros=0;
	$bGigante=false; //En caso de que la tabla sea muy grande pasarlo a true
	$sLimite='';
	if ($bGigante){
		$sSQL='SELECT COUNT(1) AS Total 
		FROM saiu61comunicados AS TB, unae26unidadesfun AS T6 
		WHERE '.$sSQLadd1.' TB.saiu61idunidad=T6.unae26id '.$sSQLadd.'';
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
	$sSQL='SELECT TB.saiu61consec, TB.saiu61id, TB.saiu61orden, TB.saiu61vigente, TB.saiu61titulo, T6.unae26nombre, TB.saiu61fecha, TB.saiu61fechapublica, TB.saiu61fechadespublica, TB.saiu61cuerpo, TB.saiu61poblacion, TB.saiu61formaentrega, TB.saiu61idunidad 
	FROM saiu61comunicados AS TB, unae26unidadesfun AS T6 
	WHERE '.$sSQLadd1.' TB.saiu61idunidad=T6.unae26id '.$sSQLadd.'
	ORDER BY TB.saiu61consec';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_3061" name="consulta_3061" type="hidden" value="'.$sSQLlista.'"/>
	<input id="titulos_3061" name="titulos_3061" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objDB->ejecutasql($sSQL.$sLimite);
	if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 3061: '.$sSQL.$sLimite.'<br>';}
	if ($tabladetalle==false){
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		//$sLeyenda=$sSQL;
		}else{
		if (!$bGigante){
			$registros=$objDB->nf($tabladetalle);
			if ($registros==0){
				//return array(utf8_encode($sErrConsulta.'<input id="paginaf3061" name="paginaf3061" type="hidden" value="'.$pagina.'"/><input id="lppf3061" name="lppf3061" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
	<td><b>'.$ETI['saiu61consec'].'</b></td>
	<td><b>'.$ETI['saiu61orden'].'</b></td>
	<td><b>'.$ETI['saiu61vigente'].'</b></td>
	<td><b>'.$ETI['saiu61titulo'].'</b></td>
	<td><b>'.$ETI['saiu61idunidad'].'</b></td>
	<td><b>'.$ETI['saiu61fecha'].'</b></td>
	<td align="right">
	'.html_paginador('paginaf3061', $registros, $lineastabla, $pagina, 'paginarf3061()').'
	'.html_lpp('lppf3061', $lineastabla, 'paginarf3061()').'
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
		$et_saiu61vigente=$sPrefijo.$ETI['no'].$sSufijo;
		if ($filadet['saiu61vigente']=='S'){$et_saiu61vigente=$sPrefijo.$ETI['si'].$sSufijo;}
		$et_saiu61fecha='';
		if ($filadet['saiu61fecha']!=0){$et_saiu61fecha=fecha_desdenumero($filadet['saiu61fecha']);}
		/*
		$et_saiu61fechapublica='';
		if ($filadet['saiu61fechapublica']!=0){$et_saiu61fechapublica=fecha_desdenumero($filadet['saiu61fechapublica']);}
		$et_saiu61fechadespublica='';
		if ($filadet['saiu61fechadespublica']!=0){$et_saiu61fechadespublica=fecha_desdenumero($filadet['saiu61fechadespublica']);}
		*/
		if ($bAbierta){
			$sLink='<a href="javascript:cargaridf3061('.$filadet['saiu61id'].')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$res=$res.'<tr'.$sClass.'>
		<td>'.$sPrefijo.$filadet['saiu61consec'].$sSufijo.'</td>
		<td>'.$sPrefijo.$filadet['saiu61orden'].$sSufijo.'</td>
		<td>'.$sPrefijo.$filadet['saiu61vigente'].$sSufijo.'</td>
		<td>'.$sPrefijo.cadena_notildes($filadet['saiu61titulo']).$sSufijo.'</td>
		<td>'.$sPrefijo.cadena_notildes($filadet['unae26nombre']).$sSufijo.'</td>
		<td>'.$sPrefijo.$et_saiu61fecha.$sSufijo.'</td>
		<td>'.$sLink.'</td>
		</tr>';
		}
	$res=$res.'</table>
	<div class="salto5px"></div>
	</div>';
	$objDB->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
	}
function f3061_HtmlTabla($aParametros){
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
	list($sDetalle, $sDebugTabla)=f3061_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f3061detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f3061_db_CargarPadre($DATA, $objDB, $bDebug=false){
	$sError='';
	$iTipoError=0;
	$sDebug='';
	require './app.php';
	if ($DATA['paso']==1){
		$sSQLcondi='saiu61consec='.$DATA['saiu61consec'].'';
		}else{
		$sSQLcondi='saiu61id='.$DATA['saiu61id'].'';
		}
	$sSQL='SELECT * FROM saiu61comunicados WHERE '.$sSQLcondi;
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$DATA['saiu61consec']=$fila['saiu61consec'];
		$DATA['saiu61id']=$fila['saiu61id'];
		$DATA['saiu61orden']=$fila['saiu61orden'];
		$DATA['saiu61vigente']=$fila['saiu61vigente'];
		$DATA['saiu61titulo']=$fila['saiu61titulo'];
		$DATA['saiu61idunidad']=$fila['saiu61idunidad'];
		$DATA['saiu61fecha']=$fila['saiu61fecha'];
		$DATA['saiu61fechapublica']=$fila['saiu61fechapublica'];
		$DATA['saiu61fechadespublica']=$fila['saiu61fechadespublica'];
		$DATA['saiu61cuerpo']=$fila['saiu61cuerpo'];
		$DATA['saiu61poblacion']=$fila['saiu61poblacion'];
		$DATA['saiu61formaentrega']=$fila['saiu61formaentrega'];
		$bcargo=true;
		$DATA['paso']=2;
		$DATA['boculta3061']=0;
		$bLimpiaHijos=true;
		}else{
		$DATA['paso']=0;
		}
	return array($DATA, $sError, $iTipoError, $sDebug);
	}
function f3061_db_GuardarV2($DATA, $objDB, $bDebug=false){
	$iCodModulo=3061;
	$bAudita[2]=true;
	$bAudita[3]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3061='lg/lg_3061_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3061)){$mensajes_3061='lg/lg_3061_es.php';}
	require $mensajes_todas;
	require $mensajes_3061;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	// -- Se inicia validando todas las posibles entradas de usuario.
	/*
	if (isset($DATA['saiu61consec'])==0){$DATA['saiu61consec']='';}
	if (isset($DATA['saiu61id'])==0){$DATA['saiu61id']='';}
	if (isset($DATA['saiu61orden'])==0){$DATA['saiu61orden']='';}
	if (isset($DATA['saiu61vigente'])==0){$DATA['saiu61vigente']='';}
	if (isset($DATA['saiu61titulo'])==0){$DATA['saiu61titulo']='';}
	if (isset($DATA['saiu61idunidad'])==0){$DATA['saiu61idunidad']='';}
	if (isset($DATA['saiu61fecha'])==0){$DATA['saiu61fecha']='';}
	if (isset($DATA['saiu61fechapublica'])==0){$DATA['saiu61fechapublica']='';}
	if (isset($DATA['saiu61fechadespublica'])==0){$DATA['saiu61fechadespublica']='';}
	if (isset($DATA['saiu61cuerpo'])==0){$DATA['saiu61cuerpo']='';}
	if (isset($DATA['saiu61poblacion'])==0){$DATA['saiu61poblacion']='';}
	if (isset($DATA['saiu61formaentrega'])==0){$DATA['saiu61formaentrega']='';}
	*/
	$DATA['saiu61consec']=numeros_validar($DATA['saiu61consec']);
	$DATA['saiu61orden']=numeros_validar($DATA['saiu61orden']);
	$DATA['saiu61vigente']=numeros_validar($DATA['saiu61vigente']);
	$DATA['saiu61titulo']=htmlspecialchars(trim($DATA['saiu61titulo']));
	$DATA['saiu61idunidad']=numeros_validar($DATA['saiu61idunidad']);
	//$DATA['saiu61cuerpo']=htmlspecialchars(trim($DATA['saiu61cuerpo']));
	$DATA['saiu61poblacion']=numeros_validar($DATA['saiu61poblacion']);
	$DATA['saiu61formaentrega']=numeros_validar($DATA['saiu61formaentrega']);
	// -- Se inicializan las variables que puedan pasar vacias {Especialmente números}.
	//if ($DATA['saiu61orden']==''){$DATA['saiu61orden']=0;}
	//if ($DATA['saiu61vigente']==''){$DATA['saiu61vigente']=0;}
	//if ($DATA['saiu61idunidad']==''){$DATA['saiu61idunidad']=0;}
	//if ($DATA['saiu61poblacion']==''){$DATA['saiu61poblacion']=0;}
	//if ($DATA['saiu61formaentrega']==''){$DATA['saiu61formaentrega']=0;}
	// -- Seccion para validar los posibles causales de error.
	$sSepara=', ';
	if (true){
		if ($DATA['saiu61formaentrega']==''){$sError=$ERR['saiu61formaentrega'].$sSepara.$sError;}
		if ($DATA['saiu61poblacion']==''){$sError=$ERR['saiu61poblacion'].$sSepara.$sError;}
		//if ($DATA['saiu61cuerpo']==''){$sError=$ERR['saiu61cuerpo'].$sSepara.$sError;}
		if ($DATA['saiu61fechadespublica']==0){
			//$DATA['saiu61fechadespublica']=fecha_DiaMod();
			//$sError=$ERR['saiu61fechadespublica'].$sSepara.$sError;
			}
		if ($DATA['saiu61fechapublica']==0){
			//$DATA['saiu61fechapublica']=fecha_DiaMod();
			//$sError=$ERR['saiu61fechapublica'].$sSepara.$sError;
			}
		if ($DATA['saiu61fecha']==0){
			//$DATA['saiu61fecha']=fecha_DiaMod();
			$sError=$ERR['saiu61fecha'].$sSepara.$sError;
			}
		if ($DATA['saiu61idunidad']==''){$sError=$ERR['saiu61idunidad'].$sSepara.$sError;}
		if ($DATA['saiu61titulo']==''){$sError=$ERR['saiu61titulo'].$sSepara.$sError;}
		if ($DATA['saiu61vigente']==''){$sError=$ERR['saiu61vigente'].$sSepara.$sError;}
		//if ($DATA['saiu61orden']==''){$sError=$ERR['saiu61orden'].$sSepara.$sError;}
		//Fin de las valiaciones NO LLAVE.
		}
	//Valiaciones de campos obligatorios en todo guardar.
	// -- Se verifican los valores de campos de otras tablas.
	$bQuitarCodigo=false;
	$sCampoCodigo='';
	if ($sError==''){
		if ($DATA['paso']==10){
			if ($DATA['saiu61consec']==''){
				$DATA['saiu61consec']=tabla_consecutivo('saiu61comunicados', 'saiu61consec', '', $objDB);
				if ($DATA['saiu61consec']==-1){$sError=$objDB->serror;}
				$bQuitarCodigo=true;
				$sCampoCodigo='saiu61consec';
				}else{
				if (!seg_revisa_permiso($iCodModulo, 8, $objDB)){
					$sError=$ERR['8'];
					$DATA['saiu61consec']='';
					}
				}
			if ($sError==''){
				$sSQL='SELECT 1 FROM saiu61comunicados WHERE saiu61consec='.$DATA['saiu61consec'].'';
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
			$DATA['saiu61id']=tabla_consecutivo('saiu61comunicados','saiu61id', '', $objDB);
			if ($DATA['saiu61id']==-1){$sError=$objDB->serror;}
			}
		}
	if ($sError==''){
		if ($DATA['saiu61orden']==''){$DATA['saiu61orden']=$DATA['saiu61consec'];}
		//if (get_magic_quotes_gpc()==1){$DATA['saiu61cuerpo']=stripslashes($DATA['saiu61cuerpo']);}
		//Si el campo saiu61cuerpo permite html quite la linea htmlspecialchars para el campo y habilite la siguiente linea:
		$saiu61cuerpo=addslashes($DATA['saiu61cuerpo']);
		//$saiu61cuerpo=str_replace('"', '\"', $DATA['saiu61cuerpo']);
		$bPasa=false;
		if ($DATA['paso']==10){
			$sCampos3061='saiu61consec, saiu61id, saiu61orden, saiu61vigente, saiu61titulo, 
			saiu61idunidad, saiu61fecha, saiu61fechapublica, saiu61fechadespublica, saiu61cuerpo, 
			saiu61poblacion, saiu61formaentrega';
			$sValores3061=''.$DATA['saiu61consec'].', '.$DATA['saiu61id'].', '.$DATA['saiu61orden'].', '.$DATA['saiu61vigente'].', "'.$DATA['saiu61titulo'].'", 
			'.$DATA['saiu61idunidad'].', "'.$DATA['saiu61fecha'].'", "'.$DATA['saiu61fechapublica'].'", "'.$DATA['saiu61fechadespublica'].'", "'.$saiu61cuerpo.'", 
			'.$DATA['saiu61poblacion'].', '.$DATA['saiu61formaentrega'].'';
			if ($APP->utf8==1){
				$sSQL='INSERT INTO saiu61comunicados ('.$sCampos3061.') VALUES ('.utf8_encode($sValores3061).');';
				$sdetalle=$sCampos3061.'['.utf8_encode($sValores3061).']';
				}else{
				$sSQL='INSERT INTO saiu61comunicados ('.$sCampos3061.') VALUES ('.$sValores3061.');';
				$sdetalle=$sCampos3061.'['.$sValores3061.']';
				}
			$idAccion=2;
			$bPasa=true;
			}else{
			$scampo[1]='saiu61orden';
			$scampo[2]='saiu61vigente';
			$scampo[3]='saiu61titulo';
			$scampo[4]='saiu61idunidad';
			$scampo[5]='saiu61fecha';
			$scampo[6]='saiu61fechapublica';
			$scampo[7]='saiu61fechadespublica';
			$scampo[8]='saiu61cuerpo';
			$scampo[9]='saiu61poblacion';
			$scampo[10]='saiu61formaentrega';
			$sdato[1]=$DATA['saiu61orden'];
			$sdato[2]=$DATA['saiu61vigente'];
			$sdato[3]=$DATA['saiu61titulo'];
			$sdato[4]=$DATA['saiu61idunidad'];
			$sdato[5]=$DATA['saiu61fecha'];
			$sdato[6]=$DATA['saiu61fechapublica'];
			$sdato[7]=$DATA['saiu61fechadespublica'];
			$sdato[8]=$saiu61cuerpo;
			$sdato[9]=$DATA['saiu61poblacion'];
			$sdato[10]=$DATA['saiu61formaentrega'];
			$numcmod=10;
			$sWhere='saiu61id='.$DATA['saiu61id'].'';
			$sSQL='SELECT * FROM saiu61comunicados WHERE '.$sWhere;
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
					$sSQL='UPDATE saiu61comunicados SET '.utf8_encode($sdatos).' WHERE '.$sWhere.';';
					}else{
					$sdetalle=$sdatos.'['.$sWhere.']';
					$sSQL='UPDATE saiu61comunicados SET '.$sdatos.' WHERE '.$sWhere.';';
					}
				$idAccion=3;
				}
			}
		if ($bPasa){
			if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Guardar 3061 '.$sSQL.'<br>';}
			$result=$objDB->ejecutasql($sSQL);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' [3061] ..<!-- '.$sSQL.' -->';
				if ($idAccion==2){
					$DATA['saiu61id']='';
					$bQuitarCodigo=true;
					$DATA['paso']=0;
					}else{
					$DATA['paso']=2;
					}
				}else{
				if ($bAudita[$idAccion]){seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], $idAccion, $DATA['saiu61id'], $sdetalle, $objDB);}
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
function f3061_db_Eliminar($saiu61id, $objDB, $bDebug=false){
	$iCodModulo=3061;
	$bAudita[4]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3061='lg/lg_3061_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3061)){$mensajes_3061='lg/lg_3061_es.php';}
	require $mensajes_todas;
	require $mensajes_3061;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	$saiu61id=numeros_validar($saiu61id);
	// Traer los datos para hacer las validaciones.
	if ($sError==''){
		$sSQL='SELECT * FROM saiu61comunicados WHERE saiu61id='.$saiu61id.'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$filabase=$objDB->sf($tabla);
			}else{
			$sError='No se encuentra el registro solicitado {Ref: '.$saiu61id.'}';
			}
		}
	if ($sError==''){
		$sSQL='SELECT 1 FROM saiu62comunicadonoti WHERE saiu62idcomunicado='.$filabase['saiu61id'].'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$sError='Existen Notificados creados, no es posible eliminar';
			}
		}
	if ($sError==''){
		if (!seg_revisa_permiso($iCodModulo, 4, $objDB)){
			$sError=$ERR['4'];
			}
		}
	if ($sError==''){
		$sSQL='SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=3061';
		$tablaor=$objDB->ejecutasql($sSQL);
		while ($filaor=$objDB->sf($tablaor)){
			$sSQL='SELECT '.$filaor['campoid'].' FROM '.$filaor['tabla'].' WHERE '.$filaor['camporev'].'='.$_REQUEST['saiu61id'].' LIMIT 0, 1';
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
		//$sSQL='DELETE FROM saiu62comunicadonoti WHERE saiu62idcomunicado='.$filabase['saiu61id'].'';
		//$tabla=$objDB->ejecutasql($sSQL);
		$sWhere='saiu61id='.$saiu61id.'';
		//$sWhere='saiu61consec='.$filabase['saiu61consec'].'';
		$sSQL='DELETE FROM saiu61comunicados WHERE '.$sWhere.';';
		$result=$objDB->ejecutasql($sSQL);
		if ($result==false){
			$sError=$ERR['falla_eliminar'].' .. <!-- '.$sSQL.' -->';
			}else{
			if ($bAudita[4]){seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $saiu61id, $sWhere, $objDB);}
			}
		}
	return array($sError, $iTipoError, $sDebug);
	}
function f3061_TituloBusqueda(){
	return 'Busqueda de Comunicados';
	}
function f3061_ParametrosBusqueda(){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3061='lg/lg_3061_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3061)){$mensajes_3061='lg/lg_3061_es.php';}
	require $mensajes_todas;
	require $mensajes_3061;
	$sParams='<label class="Label90">
	'.$ETI['msg_bnombre'].'
	</label>
	<label>
	<input id="b3061nombre" name="b3061nombre" type="text" value="" onchange="paginarbusqueda()" />
	</label>';
	return $sParams;
	}
function f3061_JavaScriptBusqueda($iModuloBusca){
	$sRes='var sCampo=window.document.frmedita.scampobusca.value;
	var params=new Array();
	params[100]=sCampo;
	params[101]=window.document.frmedita.paginabusqueda.value;
	params[102]=window.document.frmedita.lppfbusqueda.value;
	params[103]=window.document.frmedita.b3061nombre.value;
	xajax_f'.$iModuloBusca.'_HtmlBusqueda(params);';
	return $sRes;
	}
function f3061_TablaDetalleBusquedas($aParametros, $objDB){
	$res='';
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3061='lg/lg_3061_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3061)){$mensajes_3061='lg/lg_3061_es.php';}
	require $mensajes_todas;
	require $mensajes_3061;
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
		return array($sLeyenda.'<input id="paginaf3061" name="paginaf3061" type="hidden" value="'.$pagina.'"/><input id="lppf3061" name="lppf3061" type="hidden" value="'.$lineastabla.'"/>', $sDebug);
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
	$sTitulos='Consec, Id, Orden, Vigente, Titulo, Unidad, Fecha, Fechapublica, Fechadespublica, Cuerpo, Poblacion, Formaentrega';
	$sSQL='SELECT TB.saiu61consec, TB.saiu61id, TB.saiu61orden, TB.saiu61vigente, TB.saiu61titulo, T6.unae26nombre, TB.saiu61fecha, TB.saiu61fechapublica, TB.saiu61fechadespublica, TB.saiu61cuerpo, TB.saiu61poblacion, TB.saiu61formaentrega, TB.saiu61idunidad 
	FROM saiu61comunicados AS TB, unae26unidadesfun AS T6 
	WHERE '.$sSQLadd1.' TB.saiu61idunidad=T6.unae26id '.$sSQLadd.'
	ORDER BY TB.saiu61consec';
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
			//return array(utf8_encode($sErrConsulta.'<input id="paginaf3061" name="paginaf3061" type="hidden" value="'.$pagina.'"/><input id="lppf3061" name="lppf3061" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
	<td><b>'.$ETI['saiu61consec'].'</b></td>
	<td><b>'.$ETI['saiu61orden'].'</b></td>
	<td><b>'.$ETI['saiu61vigente'].'</b></td>
	<td><b>'.$ETI['saiu61titulo'].'</b></td>
	<td><b>'.$ETI['saiu61idunidad'].'</b></td>
	<td><b>'.$ETI['saiu61fecha'].'</b></td>
	<td><b>'.$ETI['saiu61fechapublica'].'</b></td>
	<td><b>'.$ETI['saiu61fechadespublica'].'</b></td>
	<td><b>'.$ETI['saiu61cuerpo'].'</b></td>
	<td><b>'.$ETI['saiu61poblacion'].'</b></td>
	<td><b>'.$ETI['saiu61formaentrega'].'</b></td>
	<td align="right">
	'.html_paginador('paginabusqueda', $registros, $lineastabla, $pagina, 'paginarbusqueda()').'
	'.html_lpp('lppfbusqueda', $lineastabla, 'paginarbusqueda()').'
	</td>
	</tr></thead>';
	$tlinea=1;
	while($filadet=$objDB->sf($tabladetalle)){
		$sPrefijo='<a href="javascript:Devuelve(\''.$filadet['saiu61id'].'\');">';
		$sSufijo='</a>';
		$tlinea++;
		$et_saiu61vigente=$sPrefijo.$ETI['no'].$sSufijo;
		if ($filadet['saiu61vigente']=='S'){$et_saiu61vigente=$sPrefijo.$ETI['si'].$sSufijo;}
		$et_saiu61fecha='';
		if ($filadet['saiu61fecha']!=0){$et_saiu61fecha=fecha_desdenumero($filadet['saiu61fecha']);}
		$et_saiu61fechapublica='';
		if ($filadet['saiu61fechapublica']!=0){$et_saiu61fechapublica=fecha_desdenumero($filadet['saiu61fechapublica']);}
		$et_saiu61fechadespublica='';
		if ($filadet['saiu61fechadespublica']!=0){$et_saiu61fechadespublica=fecha_desdenumero($filadet['saiu61fechadespublica']);}
		$res=$res.'<tr onmouseover="cambia_color_over(this);" onmouseout="cambia_color_out(this);">
		<td>'.$sPrefijo.$filadet['saiu61consec'].$sSufijo.'</td>
		<td>'.$sPrefijo.$filadet['saiu61orden'].$sSufijo.'</td>
		<td>'.$sPrefijo.$filadet['saiu61vigente'].$sSufijo.'</td>
		<td>'.$sPrefijo.cadena_notildes($filadet['saiu61titulo']).$sSufijo.'</td>
		<td>'.$sPrefijo.cadena_notildes($filadet['unae26nombre']).$sSufijo.'</td>
		<td>'.$sPrefijo.$et_saiu61fecha.$sSufijo.'</td>
		<td>'.$sPrefijo.$et_saiu61fechapublica.$sSufijo.'</td>
		<td>'.$sPrefijo.$et_saiu61fechadespublica.$sSufijo.'</td>
		<td>'.$sPrefijo.$filadet['saiu61cuerpo'].$sSufijo.'</td>
		<td>'.$sPrefijo.$filadet['saiu61poblacion'].$sSufijo.'</td>
		<td>'.$sPrefijo.$filadet['saiu61formaentrega'].$sSufijo.'</td>
		<td></td>
		</tr>';
		}
	$res=$res.'</table>
	<div class="salto5px"></div>
	</div>';
	$objDB->liberar($tabladetalle);
	return utf8_encode($res);
	}
/** Función f3061_ProcesarArchivo.
* Esta función recibe un archivo y lo procesa.
* @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
* @param $DATA contiene las variables $_REQUEST del formulario de origen
* @param $ARCHIVO contiene las variables $_FILE del formulario de origen
* @param $objDB Objeto de base datos del tipo clsdbadmin
* @param $bDebug (Opcional), bandera para indicar si se generan datos de depuración
* @date miércoles, 21 de julio de 2021
*/
function f3061_ProcesarArchivo($DATA, $ARCHIVO, $objDB, $bDebug=false){
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
		$iAdicionados=0;
		$iActualizados=0;
		$sCampos3062='saiu62idcomunicado, saiu62idtercero, saiu62id, saiu62idperiodo, saiu62idescuela, 
		saiu62idprograma, saiu62idzona, saiu62idcentro, saiu62estado, saiu62fecha, 
		saiu62fhora, saiu62min, saiu62mailenviado';
		//$sCampos3061='saiu61consec, saiu61id, saiu61orden, saiu61vigente, saiu61titulo, saiu61idunidad, saiu61fecha, saiu61fechapublica, saiu61fechadespublica, saiu61cuerpo, saiu61poblacion, saiu61formaentrega';
		//$saiu61id=tabla_consecutivo('saiu61comunicados','saiu61id', '', $objDB);
		//$sCampos3062='saiu62idcomunicado, saiu62idtercero, saiu62id, saiu62idperiodo, saiu62idescuela, saiu62idprograma, saiu62idzona, saiu62idcentro, saiu62estado, saiu62fecha, saiu62fhora, saiu62min, saiu62mailenviado';
		$saiu62idcomunicado=$DATA['saiu61id'];
		$saiu62id=tabla_consecutivo('saiu62comunicadonoti','saiu62id', '', $objDB);
		$sDato=trim($objPHPExcel->getActiveSheet()->getCellByColumnAndRow(0, $iFila)->getValue());
		while($sDato!=''){
			$iDatos++;
			$sErrLinea='';
			$saiu62idtercero=0;
			$saiu62estado=0;
			switch($sDato){
				case 'CC':
				case 'cc':
				$sTipoDoc='CC';
				break;
				}
			if ($sErrLinea==''){
				$sDoc=trim($objPHPExcel->getActiveSheet()->getCellByColumnAndRow(1, $iFila)->getValue());
				$sSQL='SELECT unad11id FROM unad11terceros WHERE unad11tipodoc="'.$sTipoDoc.'" AND unad11doc="'.$sDoc.'"';
				$tabla=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($tabla)>0){
					$fila=$objDB->sf($tabla);
					$saiu62idtercero=$fila['unad11id'];
					}else{
					$sErrLinea='No se encuentra el documento '.$sTipoDoc.' '.$sDoc.'';
					}
				}
			if ($sErrLinea==''){
				$saiu62idperiodo=0;
				list($saiu62idescuela, $saiu62idprograma, $saiu62idzona, $saiu62idcentro, $sDebugD)=f111_ProgramaCentro($saiu62idtercero, $objDB);
				}
			if ($sErrLinea==''){
				$sSQL='SELECT saiu62id FROM saiu62comunicadonoti WHERE saiu62idcomunicado='.$saiu62idcomunicado.' AND saiu62idtercero='.$saiu62idtercero.'';
				$tabla62=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($tabla62)==0){
					$sValores3062=''.$saiu62idcomunicado.', '.$saiu62idtercero.', '.$saiu62id.', '.$saiu62idperiodo.', '.$saiu62idescuela.', 
					'.$saiu62idprograma.', '.$saiu62idzona.', '.$saiu62idcentro.', '.$saiu62estado.', 0, 
					0, 0, 0';
					$sSQL='INSERT INTO saiu62comunicadonoti ('.$sCampos3062.') VALUES ('.$sValores3062.');';
					$result=$objDB->ejecutasql($sSQL);
					$saiu62id++;
					$iAdicionados++;
					}else{
					//$iActualizados++;
					}
				//seg_auditar(3061, $_SESSION['unad_id_tercero'], 3, $id, $sdetalle, $objDB);
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
		if ($iAdicionados>0){
			$sError=$sError.' - Registros agregados '.$iAdicionados;
			$iTipoError=1;
			}
		}
	return array($sError, $iTipoError, $sInfoProceso, $sDebug);
	}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------
?>