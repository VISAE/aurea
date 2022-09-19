<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2020 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.25.3 sábado, 18 de julio de 2020
--- 3000 Historial de solicitudes
*/
function f3000_ContenedorTercero($idTercero, $objDB, $bDebug=false){
	$idContenedor=0;
	$sError='';
	$sDebug='';
	list($idContenedor, $sError)=f1011_BloqueTercero($idTercero, $objDB);
	$sTabla='saiu23inventario_'.$idContenedor;
	if (!$objDB->bexistetabla($sTabla)){
		list($sError, $sDebug)=f3000_TablasInventario($idContenedor, $objDB);
		}
	return array($idContenedor, $sError, $sDebug);
	}
function f3000_Registrar($valores, $objDB, $bDebug=false){
	$iCodModulo=3000;
	$bAudita[2]=false;
	$bAudita[3]=false;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3000='lg/lg_3000_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3000)){$mensajes_3000='lg/lg_3000_es.php';}
	require $mensajes_todas;
	require $mensajes_3000;
	$sError='';
	$sDebug='';
	$bInserta=false;
	$iAccion=3;
	if(!is_array($valores)){$valores=json_decode(str_replace('\"','"',$valores),true);}
	$saiu23idtercero=numeros_validar($valores[1]);
	$saiu23modulo=numeros_validar($valores[2]);
	$saiu23tabla=numeros_validar($valores[3]);
	$saiu23idtabla=numeros_validar($valores[4]);
	$saiu23fecha=$valores[5];
	$saiu23idtipo=numeros_validar($valores[6]);
	$saiu23idtema=numeros_validar($valores[7]);
	$saiu23estado=numeros_validar($valores[8]);
	list($idContenedor, $sError, $sDebug)=f3000_ContenedorTercero($saiu23idtercero, $objDB, $bDebug);
	$sTabla='saiu23inventario_'.$idContenedor;
	//if ($saiu23idtipo==''){$saiu23idtipo=0;}
	//if ($saiu23idtema==''){$saiu23idtema=0;}
	//if ($saiu23estado==''){$saiu23estado=0;}
	$sSepara=', ';
	if ($saiu23estado==''){$sError=$ERR['saiu23estado'].$sSepara.$sError;}
	if ($saiu23idtema==''){$sError=$ERR['saiu23idtema'].$sSepara.$sError;}
	if ($saiu23idtipo==''){$sError=$ERR['saiu23idtipo'].$sSepara.$sError;}
	if ($saiu23fecha==0){
		//$saiu23fecha=fecha_DiaMod();
		$sError=$ERR['saiu23fecha'].$sSepara.$sError;
		}
	if ($saiu23idtabla==''){$sError=$ERR['saiu23idtabla'].$sSepara.$sError;}
	if ($saiu23tabla==''){$sError=$ERR['saiu23tabla'].$sSepara.$sError;}
	if ($saiu23modulo==''){$sError=$ERR['saiu23modulo'].$sSepara.$sError;}
	if ($saiu23idtercero==''){$sError=$ERR['saiu23idtercero'].$sSepara.$sError;}
	if ($sError==''){
		$sSQL='SELECT saiu23idtercero FROM '.$sTabla.' WHERE saiu23idtercero='.$saiu23idtercero.' AND saiu23modulo='.$saiu23modulo.' AND saiu23tabla='.$saiu23tabla.' AND saiu23idtabla='.$saiu23idtabla.'';
		$result=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($result)==0){
			$bInserta=true;
			$iAccion=2;
			}
		}
	if ($sError==''){
		if ($bInserta){
			$sCampos3000='saiu23idtercero, saiu23modulo, saiu23tabla, saiu23idtabla, saiu23fecha, 
			saiu23idtipo, saiu23idtema, saiu23estado';
			$sValores3000=''.$saiu23idtercero.', '.$saiu23modulo.', '.$saiu23tabla.', '.$saiu23idtabla.', "'.$saiu23fecha.'", 
			'.$saiu23idtipo.', '.$saiu23idtema.', '.$saiu23estado.'';
			if ($APP->utf8==1){
				$sSQL='INSERT INTO '.$sTabla.' ('.$sCampos3000.') VALUES ('.utf8_encode($sValores3000).');';
				}else{
				$sSQL='INSERT INTO '.$sTabla.' ('.$sCampos3000.') VALUES ('.$sValores3000.');';
				}
			if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Guardar 3000 '.$sSQL.'<br>';}
			$result=$objDB->ejecutasql($sSQL);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' [3000].<!-- '.$sSQL.' -->';
				}
			}else{
			$scampo3000[1]='saiu23fecha';
			$scampo3000[2]='saiu23idtipo';
			$scampo3000[3]='saiu23idtema';
			$scampo3000[4]='saiu23estado';
			$svr3000[1]=$saiu23fecha;
			$svr3000[2]=$saiu23idtipo;
			$svr3000[3]=$saiu23idtema;
			$svr3000[4]=$saiu23estado;
			$inumcampos=4;
			$sWhere='saiu23idtercero='.$saiu23idtercero.' AND saiu23modulo='.$saiu23modulo.' AND saiu23tabla='.$saiu23tabla.' AND saiu23idtabla='.$saiu23idtabla.'';
			$sSQL='SELECT * FROM '.$sTabla.' WHERE '.$sWhere;
			$sdatos='';
			$bpasa=false;
			$result=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($result)>0){
				$filaorigen=$objDB->sf($result);
				for ($k=1;$k<=$inumcampos;$k++){
					if ($filaorigen[$scampo3000[$k]]!=$svr3000[$k]){
						if ($sdatos!=''){$sdatos=$sdatos.', ';}
						$sdatos=$sdatos.$scampo3000[$k].'="'.$svr3000[$k].'"';
						$bpasa=true;
						}
					}
				}
			if ($bpasa){
				if ($APP->utf8==1){
					$sSQL='UPDATE '.$sTabla.' SET '.utf8_encode($sdatos).' WHERE '.$sWhere.';';
					}else{
					$sSQL='UPDATE '.$sTabla.' SET '.$sdatos.' WHERE '.$sWhere.';';
					}
				$result=$objDB->ejecutasql($sSQL);
				if ($result==false){
					$sError=$ERR['falla_guardar'].' {Historial de solicitudes}. <!-- '.$sSQL.' -->';
					}
				}
			}
		}
	return array($sError, $iAccion, $sDebug);
	}
function f3000_Retirar($aParametros, $objDB, $bDebug=false){
	$iCodModulo=3000;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3000='lg/lg_3000_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3000)){$mensajes_3000='lg/lg_3000_es.php';}
	require $mensajes_todas;
	require $mensajes_3000;
	$sError='';
	$sDebug='';
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$saiu23idtercero=numeros_validar($aParametros[1]);
	$saiu23modulo=numeros_validar($aParametros[2]);
	$saiu23tabla=numeros_validar($aParametros[3]);
	$saiu23idtabla=numeros_validar($aParametros[4]);
	list($idContenedor, $sError, $sDebug)=f3000_ContenedorTercero($saiu23idtercero, $objDB, $bDebug);
	$sTabla='saiu23inventario_'.$idContenedor;
	if ($sError==''){
		//acciones previas
		$sWhere='saiu23idtercero='.$saiu23idtercero.' AND saiu23modulo='.$saiu23modulo.' AND saiu23tabla='.$saiu23tabla.' AND saiu23idtabla='.$saiu23idtabla.'';
		$sSQL='DELETE FROM '.$sTabla.' WHERE '.$sWhere.';';
		$result=$objDB->ejecutasql($sSQL);
		if ($result==false){
			$sError=$ERR['falla_eliminar'].' {3000 Historial de solicitudes}.<!-- '.$sSQL.' -->';
			}
		}
	return array($sError, $sDebug);
	}
function f3000_TablaDetalleV2($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3000=$APP->rutacomun.'lg/lg_3000_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3000)){$mensajes_3000=$APP->rutacomun.'lg/lg_3000_es.php';}
	require $mensajes_todas;
	require $mensajes_3000;
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	if (isset($aParametros[0])==0){$aParametros[0]=-1;}
	if (isset($aParametros[100])==0){$aParametros[100]=0;}
	if (isset($aParametros[101])==0){$aParametros[101]=1;}
	if (isset($aParametros[102])==0){$aParametros[102]=20;}
	//if (isset($aParametros[103])==0){$aParametros[103]='';}
	//if (isset($aParametros[104])==0){$aParametros[104]='';}
	//$aParametros[103]=numeros_validar($aParametros[103]);
	//$saiu23idtercero=numeros_validar($aParametros[0]);
	if ($aParametros[0]==''){$aParametros[0]=-1;}
	$idTercero=$aParametros[0];
	$sDebug='';
	$saiu18idsolicitante=$aParametros[100];
	$pagina=$aParametros[101];
	$lineastabla=$aParametros[102];
	$bAbierta=false;
	$sLeyenda='';
	if ((int)$saiu18idsolicitante==0){$sLeyenda='No se ha ingresado un documento v&aacute;lido a consultar';}
	if ($sLeyenda!=''){
		$sLeyenda='<div class="salto1px"></div>
		<div class="GrupoCamposAyuda">
		'.$sLeyenda.'
		<div class="salto1px"></div>
		</div>';
		return array($sLeyenda.'<input id="paginaf3000" name="paginaf3000" type="hidden" value="'.$pagina.'"/><input id="lppf3000" name="lppf3000" type="hidden" value="'.$lineastabla.'"/>', $sDebug);
		die();
		}
	list($idContenedor, $sError, $sDebug)=f3000_ContenedorTercero($saiu18idsolicitante, $objDB, $bDebug);
	$sTabla='saiu23inventario_'.$idContenedor;

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
	$sTitulos='Tercero, Modulo, Tabla, Tabla, Fecha, Tipo, Tema, Estado';
	$sSQL='SELECT TB.saiu23idtercero, T2.saiu24nombre, TB.saiu23tabla, TB.saiu23idtabla, TB.saiu23fecha, T6.saiu02titulo, T7.saiu03titulo, T8.saiu11nombre, TB.saiu23modulo, TB.saiu23idtipo, TB.saiu23idtema, TB.saiu23estado 
	FROM '.$sTabla.' AS TB, saiu24modulossai AS T2, saiu02tiposol AS T6, saiu03temasol AS T7, saiu11estadosol AS T8 
	WHERE '.$sSQLadd1.' TB.saiu23idtercero='.$saiu18idsolicitante.' AND TB.saiu23modulo=T2.saiu24id AND TB.saiu23idtipo=T6.saiu02id AND TB.saiu23idtema=T7.saiu03id AND TB.saiu23estado=T8.saiu11id '.$sSQLadd.'
	ORDER BY TB.saiu23fecha DESC, TB.saiu23modulo, TB.saiu23tabla, TB.saiu23idtabla';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_3000" name="consulta_3000" type="hidden" value="'.$sSQLlista.'"/>
	<input id="titulos_3000" name="titulos_3000" type="hidden" value="'.$sTitulos.'"/>';
	if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 3000: '.$sSQL.'<br>';}
	$tabladetalle=$objDB->ejecutasql($sSQL);
	if ($tabladetalle==false){
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		//$sLeyenda=$sSQL;
		}else{
		$registros=$objDB->nf($tabladetalle);
		if ($registros==0){
			//return array(utf8_encode($sErrConsulta.'<input id="paginaf3000" name="paginaf3000" type="hidden" value="'.$pagina.'"/><input id="lppf3000" name="lppf3000" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
	<td><b>'.$ETI['saiu00idmodulo'].'</b></td>
	<td><b>'.$ETI['saiu00fecha'].'</b></td>
	<td><b>'.$ETI['saiu00idtipo'].'</b></td>
	<td><b>'.$ETI['saiu00idtema'].'</b></td>
	<td><b>'.$ETI['saiu00estado'].'</b></td>
	<td align="right">
	'.html_paginador('paginaf3000', $registros, $lineastabla, $pagina, 'paginarf3000()').'
	'.html_lpp('lppf3000', $lineastabla, 'paginarf3000()').'
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
		$et_saiu23modulo=$sPrefijo.cadena_notildes($filadet['saiu24nombre']).$sSufijo;
		//$et_saiu23tabla=$sPrefijo.$filadet['saiu23tabla'].$sSufijo;
		//$et_saiu23idtabla=$sPrefijo.$filadet['saiu23idtabla'].$sSufijo;
		$et_saiu23fecha='';
		if ($filadet['saiu23fecha']!=0){$et_saiu23fecha=$sPrefijo.fecha_desdenumero($filadet['saiu23fecha']).$sSufijo;}
		$et_saiu23idtipo=$sPrefijo.cadena_notildes($filadet['saiu02titulo']).$sSufijo;
		$et_saiu23idtema=$sPrefijo.cadena_notildes($filadet['saiu03titulo']).$sSufijo;
		$et_saiu23estado=$sPrefijo.cadena_notildes($filadet['saiu11nombre']).$sSufijo;
		if ($bAbierta){
			$sLink='<a href="javascript:cargaridf3000('.$filadet['CampoIdHijo'].')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$res=$res.'<tr'.$sClass.'>
		<td>'.$et_saiu23modulo.'</td>
		<td>'.$et_saiu23fecha.'</td>
		<td>'.$et_saiu23idtipo.'</td>
		<td>'.$et_saiu23idtema.'</td>
		<td>'.$et_saiu23estado.'</td>
		<td>'.$sLink.'</td>
		</tr>';
		}
	$res=$res.'</table>';
	$objDB->liberar($tabladetalle);
	//Ahora los historicos.
	$sSQL='SELECT unad11doc FROM unad11terceros WHERE unad11id='.$saiu18idsolicitante.'';
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$sDoc=$fila['unad11doc'];
		$sLlave = "f79a3e797d3d944d847addf158a34548"; //valor fijo y privado en ambos servidores
		$sToken = md5($sDoc."||".$sLlave);
		$sUrlDestino='https://sau.unad.edu.co/app_pqrs/admin/service/reporte_usuario.php?sai&doc='.$sDoc.'&token='.$sToken.'';
		$res=$res.'<div class="salto1px"></div>
		<iframe height="300" width="100%" scrolling="auto" src="'.$sUrlDestino.'"></iframe>
		';
		}
	//Fin de los historicos.
	return array(utf8_encode($res), $sDebug);
	}
function f3000_HtmlTabla($aParametros){
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
	list($sDetalle, $sDebugTabla)=f3000_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f3000detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------
function f3000_TablaDetalleAcad($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_12206=$APP->rutacomun.'lg/lg_12206_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_12206)){$mensajes_12206=$APP->rutacomun.'lg/lg_12206_es.php';}
	$mensajes_3000=$APP->rutacomun.'lg/lg_3000_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3000)){$mensajes_3000=$APP->rutacomun.'lg/lg_3000_es.php';}
	require $mensajes_todas;
	require $mensajes_12206;
	require $mensajes_3000;
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	if (isset($aParametros[0])==0){$aParametros[0]=-1;}
	if (isset($aParametros[100])==0){$aParametros[100]=0;}
	if (isset($aParametros[101])==0){$aParametros[101]=1;}
	if (isset($aParametros[102])==0){$aParametros[102]=20;}
	//if (isset($aParametros[103])==0){$aParametros[103]='';}
	//if (isset($aParametros[104])==0){$aParametros[104]='';}
	//$aParametros[103]=numeros_validar($aParametros[103]);
	//$saiu23idtercero=numeros_validar($aParametros[0]);
	if ($aParametros[0]==''){$aParametros[0]=-1;}
	$idTercero=$aParametros[0];
	$sDebug='';
	$saiu18idsolicitante=$aParametros[100];
	$pagina=$aParametros[101];
	$lineastabla=$aParametros[102];
	$bAbierta=false;
	$sLeyenda='';
	if ((int)$saiu18idsolicitante==0){$sLeyenda='No se ha ingresado un documento v&aacute;lido a consultar';}
	$sBotones='<input id="paginaf3000" name="paginaf3000" type="hidden" value="'.$pagina.'"/>
	<input id="lppf3000" name="lppf3000" type="hidden" value="'.$lineastabla.'"/>';
	if ($sLeyenda!=''){
		$sLeyenda='<div class="salto1px"></div>
		<div class="GrupoCamposAyuda">
		'.$sLeyenda.'
		<div class="salto1px"></div>
		</div>';
		return array($sLeyenda.$sBotones, $sDebug);
		die();
		}
	$iHoy=fecha_DiaMod();
	list($idContenedor, $sError, $sDebug)=f3000_ContenedorTercero($saiu18idsolicitante, $objDB, $bDebug);
	$sTabla04='core04matricula_'.$idContenedor;
	
	$aEstado=array();
	$sSQL='SELECT core33id, core33nombre FROM core33estadomatricula ';
	$tabla=$objDB->ejecutasql($sSQL);
	while($fila=$objDB->sf($tabla)){
		$aEstado[$fila['core33id']]=cadena_notildes($fila['core33nombre']);
		}
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
	$sIds2='-99';
	/*
	$sSQL='SELECT exte02id FROM exte02per_aca WHERE exte02fechatopetablero>='.$iHoy.'';
	$tabla=$objDB->ejecutasql($sSQL);
	while($fila=$objDB->sf($tabla)){
		$sIds2=$sIds2.','.$fila['exte02id'];
		}
	*/
	$sTitulos='Tercero, Modulo, Tabla, Tabla, Fecha, Tipo, Tema, Estado';
	$sSQL='SELECT TB.core16peraca, TB.core16idprograma, TB.core16id, T2.exte02nombre, T9.core09codigo, T9.core09nombre, 
	T2.exte02fechatopetablero, T2.exte02oferfechatopecancela, T2.exte02fechatopeaplaza, TB.core16numcursos, TB.cara16numcursosext, 
	TB.cara16numcursospost, T2.exte02tipoperiodo  
	FROM core16actamatricula AS TB, exte02per_aca AS T2, core09programa AS T9 
	WHERE TB.core16tercero='.$saiu18idsolicitante.' 
	AND TB.core16peraca=T2.exte02id AND TB.core16idprograma=T9.core09id 
	ORDER BY TB.core16peraca DESC';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_3000" name="consulta_3000" type="hidden" value="'.$sSQLlista.'"/>
	<input id="titulos_3000" name="titulos_3000" type="hidden" value="'.$sTitulos.'"/>';
	if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 3000: '.$sSQL.'<br>';}
	$tabladetalle=$objDB->ejecutasql($sSQL);
	if ($tabladetalle==false){
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		//$sLeyenda=$sSQL;
		}else{
		$registros=$objDB->nf($tabladetalle);
		if ($registros==0){
			$sInfoAlUsuario='No hemos encontrado registros de matricula vigentes.';
			return array(utf8_encode($sInfoAlUsuario.$sBotones), $sDebug);
			}
		}
	$res=$sErrConsulta.$sLeyenda.$sBotones.'<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">';
	$tlinea=1;
	$idPrograma=-99;
	$idPeriodo=-99;
	$bPuedeAplazar=false;
	$bPuedeAplazarTardio=false;
	$bPuedeCancelar=false;
	while($filadet=$objDB->sf($tabladetalle)){
		if ($idPrograma!=$filadet['core16idprograma']){
			$idPrograma=$filadet['core16idprograma'];
			$sNomPrograma=$filadet['core09codigo'].' '.cadena_notildes($filadet['core09nombre']).'';
			$res=$res.'<tr class="fondoazul">
			<td colspan="7">'.$ETI['msg_programa'].': <b>'.$sNomPrograma.'</b></td>
			</tr>';
			}
		if ($idPeriodo!=$filadet['core16peraca']){
			$bConInfoNoAplaza=false;
			$sComplementoAplaza='';
			if ($idPeriodo>765){
				//Los acompañamientos de ese periodo...
				list ($sAcomp, $sDebugF)=f3000_FilaAcompana($saiu18idsolicitante, $idContenedor, $idPeriodo, 0, $objDB, $bDebug);
				$res=$res.$sAcomp;
				}
			$idPeriodo=$filadet['core16peraca'];
			$sNomPeriodo=cadena_notildes($filadet['exte02nombre']).' ';
			$bPuedeAplazar=false;
			$bPuedeCancelar=false;
			$sBotonAplaza='<td></td>';
			$sBotonCancela='<td></td>';
			$iFechaTopeCancela=$filadet['exte02oferfechatopecancela'];
			$sInfoAdicionalPeriodo='';
			if ($filadet['exte02fechatopetablero']>=$iHoy){
				//Ver si tiene una excepcion podria cambiar la fecha tope de cancelaciones.
				$sSQL='SELECT corf39nuevafecha FROM corf39prorrogas WHERE corf39idtercero='.$saiu18idsolicitante.' AND corf39periodo='.$idPeriodo.' AND corf39estado=7 AND corf39nuevafecha>='.$iHoy.'';
				$tabla39=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($tabla39)>0){
					$fila39=$objDB->sf($tabla39);
					$iFechaTopeCancela=$fila39['corf39nuevafecha'];
					$sInfoAdicionalPeriodo='Se ha concedido la oportunidad de hacer aplazamientos extemporaneo sin requerir autorizaci&oacute;n por parte de la escuela hasta el d&iacute;a '.formato_FechaLargaDesdeNumero($iFechaTopeCancela, true).'.';
					}
				$iTotalCursos=$filadet['core16numcursos']+$filadet['cara16numcursosext']+$filadet['cara16numcursospost'];
				if ($iFechaTopeCancela>=$iHoy){
					if ($iTotalCursos>0){
						if ($filadet['exte02tipoperiodo']==0){
							$bPuedeAplazar=true;
							$bPuedeCancelar=true;
							$sBotonAplaza='<td><input id="CmdAplazarTodo" name="CmdAplazarTodo" type="button" class="BotonAzul" value="Aplazar" onclick="aplazasem('.$idPeriodo.')" title="Aplazar periodo"/></td>';
							$sBotonCancela='<td><input id="CmdCancelaTodo" name="CmdCancelaTodo" type="button" class="BotonAzul" value="Cancelar" onclick="cancelasem('.$idPeriodo.')" title="Cancelar periodo"/></td>';
							}else{
							if ($filadet['exte02fechatopeaplaza']>=$iHoy){
								$bPuedeAplazarTardio=true;
								}else{
								$bPuedeAplazarTardio=true;
								$bConInfoNoAplaza=true;
									//$sComplementoAplaza='<br>Recuerde que: <b>el evento que genera la solicitud debi&oacute; de haber sucedido dentro de los primeros 10 d&iacute;as h&aacute;biles del periodo acad&eacute;mico</b>';
								}
							}
						}
					}else{
					if ($iFechaTopeCancela>0){
						$sBotonAplaza='';
						$sBotonCancela='<td colspan="2">'.$ETI['msg_fechanovedades'].' <b>'.formato_FechaLargaDesdeNumero($iFechaTopeCancela).'</b></td>';
						}
					if ($filadet['exte02fechatopeaplaza']>=$iHoy){
						if ($iTotalCursos>0){$bPuedeAplazarTardio=true;}
						}else{
						if ($iTotalCursos>0){
							$bPuedeAplazarTardio=true;
							$bConInfoNoAplaza=true;
							}
						}
					}
				}else{
				$sBotonAplaza='';
				$sBotonCancela='<td colspan="2">Periodo no disponible</td>';
				}
			if ($bPuedeAplazarTardio){
				if ($filadet['exte02tipoperiodo']==0){
					$sComplementoAplaza='<br>Recuerde que: <b>el evento que genera la solicitud debi&oacute; de haber sucedido dentro de las primeras 8 semanas del periodo acad&eacute;mico</b>';
					}
				}
			$res=$res.'<tr class="fondoazul">
			<td colspan="5">'.$ETI['msg_periodo'].': <b>'.$sNomPeriodo.'</b></td>
			'.$sBotonAplaza.'
			'.$sBotonCancela.'
			</tr>';
			if ($bConInfoNoAplaza){
				$res=$res.'<tr class="fondoazul">
				<td colspan="7" align="center">'.$AYU['msg_aplazatiempos'].$sComplementoAplaza.'</td>
				</tr>';
				}
			if ($sInfoAdicionalPeriodo!=''){
				$res=$res.'<tr>
				<td colspan="7" align="center">'.$sInfoAdicionalPeriodo.'</td>
				</tr>';
				}
			}
		if ($filadet['exte02fechatopetablero']>=$iHoy){
			//Ahora por cada matricula mostrar los cursos
			$sSQL='SELECT TB.core04id, TB.core04idcurso, T40.unad40titulo, T40.unad40nombre, TB.core04estado, T40.unad40numcreditos 
			FROM '.$sTabla04.' AS TB, unad40curso AS T40 
			WHERE TB.core04tercero='.$saiu18idsolicitante.' AND TB.core04idmatricula='.$filadet['core16id'].' AND TB.core04estado<>1 
			AND TB.core04idcurso=T40.unad40id
			ORDER BY TB.core04estado, T40.unad40titulo';
			//$res=$res.'<tr><td colspan="4">'.$sSQL.'</td></tr>';
			$tabla=$objDB->ejecutasql($sSQL);
			while($fila=$objDB->sf($tabla)){
				$sPrefijo='';
				$sSufijo='';
				$sClass=' class="resaltetabla"';
				switch($fila['core04estado']){
					case 9: //Cancelado
					case 10: //Aplazado
					$sPrefijo='<span class="rojo">';
					$sSufijo='</span>';
					break;
					}
				if(($tlinea%2)!=0){$sClass='';}
				$tlinea++;
				$id04=$fila['core04id'];
				$idCurso=$fila['core04idcurso'];
				$et_curso=$sPrefijo.$fila['unad40titulo'].$sSufijo;
				$et_nomCurso=$sPrefijo.cadena_notildes($fila['unad40nombre']).$sSufijo;
				$et_Creditos=$sPrefijo.$fila['unad40numcreditos'].$sSufijo;
				$et_Estado=$sPrefijo.$aEstado[$fila['core04estado']].$sSufijo;
				$sBotonCambia='';
				$sBotonAplaza='';
				$sBotonCancela='';
				switch($fila['core04estado']){
					case 0: //Matriculado
					case 2: //Curso Externo
					if ($bPuedeAplazar){
						//$sBotonCambia='<a href="javascript:cambiacurso('.$idPeriodo.', '.$idCurso.')" class="lnkresalte">'.'Cambiar curso'.'</a>';
						$sBotonAplaza='<a href="javascript:aplaza('.$idPeriodo.', '.$idCurso.')" class="lnkresalte">'.'Aplazar curso'.'</a>';
						//$sBotonAplaza='<input id="CmdAplazar" name="CmdAplazar" type="button" class="BotonAzul" value="Aplazar" onclick="aplaza('.$id04.')" title="Aplazar curso"/>';
						}
					if ($bPuedeCancelar){
						$sBotonCancela='<a href="javascript:cancela('.$idPeriodo.', '.$idCurso.')" class="lnkresalte">'.'Cancelar curso'.'</a>';
						//$sBotonCancela='<input id="CmdCancela" name="CmdCancela" type="button" class="BotonAzul" value="Cancelar" onclick="cancela('.$id04.')" title="Cancelar curso"/>';
						}
					if ($bPuedeAplazarTardio){
						//Verificar que no este incluido en una solicitud tardia
						$bEntra=true;
						$sSQL='SELECT TB.corf06estado, TB.corf06fecha 
						FROM corf06novedad AS TB, corf07novedadcurso AS T7 
						WHERE TB.corf06tiponov=7 AND TB.corf06idestudiante='.$saiu18idsolicitante.' AND TB.corf06idperiodo='.$idPeriodo.' 
						AND TB.corf06estado NOT IN (0,2,8) AND TB.corf06id=T7.corf07idnovedad AND T7.corf07idcurso='.$idCurso.'';
						//if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Revisando si aplica '.$sSQL.'<br>';}
						$tabla7=$objDB->ejecutasql($sSQL);
						if ($objDB->nf($tabla7)>0){
							$bEntra=false;
							$fila7=$objDB->sf($tabla7);
							$sBotonAplaza='Estado de la solicitud de fecha '.fecha_desdenumero($fila7['corf06fecha']).' : <b>'.$acorf06estado[$fila7['corf06estado']].'<b>';
							}
						if ($bEntra){
							$sBotonAplaza='<a href="javascript:aplazatardio('.$idPeriodo.', '.$idCurso.')" class="lnkresalte">'.'Aplazar curso'.'</a>';
							}
						}
					break;
					}
				//$et_saiu23idtabla=$sPrefijo.$filadet['core04idcurso'].$sSufijo;
				$res=$res.'<tr'.$sClass.'>
				<td>'.$et_curso.'</td>
				<td>'.$et_nomCurso.'</td>
				<td>'.$et_Estado.'</td>
				<td>'.$et_Creditos.'</td>
				<td>'.$sBotonCambia.'</td>
				<td>'.$sBotonAplaza.'</td>
				<td>'.$sBotonCancela.'</td>
				</tr>';
				}
			}
		}
	//Los acompañamientos del ultimo 
	if ($idPeriodo>765){
		//Los acompañamientos de ese periodo...
		list ($sAcomp, $sDebugF)=f3000_FilaAcompana($saiu18idsolicitante, $idContenedor, $idPeriodo, 0, $objDB, $bDebug);
		$res=$res.$sAcomp;
		}
	//Ahora mostrar los acompañamientos que no esten asociados a un periodo.
	list ($sAcomp, $sDebugF)=f3000_FilaAcompana($saiu18idsolicitante, $idContenedor, 0, 0, $objDB, $bDebug);
	$res=$res.$sAcomp;
	$res=$res.'</table>';
	$objDB->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
	}
function f3000_FilaAcompana($idTercero, $idContTercero, $idPeriodo, $idCurso, $objDB, $bDebug=false){
	$sRes='';
	$sDebug='';
	$sTabla41='saiu41docente_'.$idContTercero;
	$sSQL='SELECT TB.saiu41fecha, TB.saiu41cerrada, TB.saiu41idactividad, TB.saiu41idtutor, TB.saiu41contacto_observa, T11.unad11razonsocial 
	FROM '.$sTabla41.' AS TB, unad11terceros AS T11 
	WHERE TB.saiu41idestudiante='.$idTercero.' AND TB.saiu41idperiodo='.$idPeriodo.' AND TB.saiu41idcurso='.$idCurso.' AND TB.saiu41visiblealest=1
	AND TB.saiu41idtutor=T11.unad11id 
	ORDER BY TB.saiu41fecha DESC';
	if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Acompanamientos: '.$sSQL.'<br>';}
	$tabla=$objDB->ejecutasql($sSQL);
	while($fila=$objDB->sf($tabla)){
		$sRes=$sRes.'<tr>
		<td></td>
		<td colspan="6">'.fecha_desdenumero($fila['saiu41fecha']).' '.cadena_notildes($fila['unad11razonsocial']).': <b>'.cadena_notildes($fila['saiu41contacto_observa']).'</b></td>
		</tr>';
		}
	return array($sRes, $sDebug);
	}
function f3000_InfoPeriodo($aParametros){
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
	if (isset($aParametros[1])==0){$aParametros[1]='';}
	if (isset($aParametros[2])==0){$aParametros[2]=0;}
	$idPeriodo=numeros_validar($aParametros[1]);
	$idEntrada=numeros_validar($aParametros[2]);
	$sDetalle='Periodo {'.$idPeriodo.'}';
	$sSQL='SELECT exte02titulo FROM exte02per_aca WHERE exte02id='.$idPeriodo.'';
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$sDetalle='Periodo <b>'.cadena_notildes($fila['exte02titulo']).'</b>';
		}
	//Info periodo
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	switch($idEntrada){
		case 2: //Cancelacion
		$objResponse->assign('div_f3000periodoCancela', 'innerHTML', $sDetalle);
		break;
		default:
		$objResponse->assign('div_f3000periodo', 'innerHTML', $sDetalle);
		break;
		}
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
//Db para desistir
function f3000_db_InfoDesistir($id06, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_12207=$APP->rutacomun.'lg/lg_12207_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_12207)){$mensajes_12207=$APP->rutacomun.'lg/lg_12207_es.php';}
	require $mensajes_todas;
	require $mensajes_12207;
	$res='';
	$sDebug='';
	$iSolicitados=0;
	$sSQL='SELECT TB.corf07id, TB.corf07idcurso, T40.unad40titulo, T40.unad40nombre, TB.corf07tipo, TB.corf07paradesistir 
	FROM corf07novedadcurso AS TB, unad40curso AS T40 
	WHERE TB.corf07idnovedad='.$id06.' AND TB.corf07idcurso=T40.unad40id 
	ORDER BY T40.unad40titulo';
	$tabladetalle=$objDB->ejecutasql($sSQL);
	$res='<div class="table-responsive">
	<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">
	<thead class="fondoazul"><tr>
	<td colspan="2"><b>'.$ETI['corf07idcurso'].'</b></td>
	<td colspan="3"></td>
	</tr></thead>';
	$tlinea=1;
	while($filadet=$objDB->sf($tabladetalle)){
		$sPrefijo='';
		$sSufijo='';
		if ($filadet['corf07paradesistir']==1){
			$sPrefijo='<b>';
			$sSufijo='</b>';
			}
		$sClass=' class="resaltetabla"';
		if(($tlinea%2)!=0){$sClass='';}
		$tlinea++;
		$sLink='';
		$sLink2='';
		$et_corf07idcurso=$sPrefijo.cadena_notildes($filadet['unad40nombre']).$sSufijo;
		$et_corf07tipo=$sPrefijo.$acorf07tipo[$filadet['corf07tipo']].$sSufijo;
		if ($filadet['corf07tipo']==2){
			if ($filadet['corf07paradesistir']==0){
				$sLink='<a href="javascript:desistir12207(1, '.$filadet['corf07id'].')" class="lnkresalte">Desistir</a>';
				}else{
				$iSolicitados=1;
				$sLink2='<a href="javascript:desistir12207(0, '.$filadet['corf07id'].')" class="lnkresalte">No desistir</a>';
				}
			}
		$res=$res.'<tr'.$sClass.'>
		<td>'.$sPrefijo.$filadet['unad40titulo'].$sSufijo.'</td>
		<td>'.$et_corf07idcurso.'</td>
		<td>'.$et_corf07tipo.'</td>
		<td>'.$sLink.'</td>
		<td>'.$sLink2.'</td>
		</tr>';
		}
	$res=$res.'</table>
	<div class="salto5px"></div>
	</div>';
	return array($res, $iSolicitados, $sDebug);
	}
//DB para info periodo
function f3000_db_InfoPeriodoCurso($idPeriodo, $idCurso, $idEntrada, $idEstudiante, $objDB, $bDebug=false){
	$sDetalle='Periodo {'.$idPeriodo.'}';
	$sDetalle2='Curso {'.$idCurso.'}';
	$sHistorial='';
	$sNotaNovedad='';
	$id06='';
	$id08='';
	$sSQL='SELECT exte02titulo FROM exte02per_aca WHERE exte02id='.$idPeriodo.'';
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$sDetalle='Periodo <b>'.cadena_notildes($fila['exte02titulo']).'</b>';
		}
	$sSQL='SELECT unad40titulo, unad40nombre FROM unad40curso WHERE unad40id='.$idCurso.'';
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$sDetalle2='Curso <b>'.$fila['unad40titulo'].' '.cadena_notildes($fila['unad40nombre']).'</b>';
		}
	$sDetalle=$sDetalle.'<br>'.$sDetalle2;
	//Ahora ver si ya tiene una solicitud  y el estado...
	if ($idEntrada==7){
		require './app.php';
		$mensajes_12206=$APP->rutacomun.'lg/lg_12206_'.$_SESSION['unad_idioma'].'.php';
		if (!file_exists($mensajes_12206)){$mensajes_12206=$APP->rutacomun.'lg/lg_12206_es.php';}
		require $mensajes_12206;
		$sSQL='SELECT TB.corf06estado, TB.corf06id, TB.corf06fecha, TB.corf06hora, TB.corf06min
		FROM corf06novedad AS TB 
		WHERE TB.corf06idestudiante='.$idEstudiante.' AND TB.corf06tiponov=7 AND TB.corf06idperiodo='.$idPeriodo.'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$sHistorial='<b>Solicitudes de este periodo</b>';
			}
		while($fila=$objDB->sf($tabla)){
			//if ($sHistorial!=''){$sHistorial=$sHistorial.html_salto();}
			$sPrefEstado='<b>';
			$sSufEstado='</b>';
			switch($fila['corf06estado']){
				case 0:
				case 2:
				$sPrefEstado='<span class="rojo">';
				$sSufEstado='</span>';
				break;
				}
			$sHistorial=$sHistorial.html_salto().fecha_desdenumero($fila['corf06fecha']).' '.html_TablaHoraMin($fila['corf06hora'], $fila['corf06min']).' Estado: '.$sPrefEstado.$acorf06estado[$fila['corf06estado']].$sSufEstado;
			$bTraeNota=false;
			switch($fila['corf06estado']){
				case 0: // Borrador
				case 1: // Solicitada
				case 2: // Devuelta
				$id06=$fila['corf06id'];
				$bTraeNota=true;
				break;
				}
			//Agregarle los cursos solicitados.
			$sSQL='SELECT T2.unad40titulo, T2.unad40nombre, TB.corf07tipo, TB.corf07idcurso 
			FROM corf07novedadcurso AS TB, unad40curso AS T2 
			WHERE TB.corf07idnovedad='.$id06.' AND TB.corf07idcurso=T2.unad40id 
			ORDER BY T2.unad40titulo';
			$tabla7=$objDB->ejecutasql($sSQL);
			while($fila7=$objDB->sf($tabla7)){
				$sHistorial=$sHistorial.html_salto().'<b>'.$fila7['unad40titulo'].'</b> '.cadena_notildes($fila7['unad40nombre']).'';
				}
			if ($bTraeNota){
				$sSQL='SELECT corf08id, corf08nota, corf08idorigenanexo, corf08idarchivoanexo 
				FROM corf08novedadnota 
				WHERE corf08idnovedad='.$id06.' AND corf08idusuario='.$idEstudiante.' AND corf08cerrada=0';
				//$sNotaNovedad=$sSQL;
				$tabla8=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($tabla8)==0){
					//Insertar la nota.
					$corf08fecha=fecha_DiaMod();
					$corf08hora=fecha_hora();
					$corf08min=fecha_minuto();
					$corf08idusuario=$idEstudiante;
					$corf08consec=tabla_consecutivo('corf08novedadnota', 'corf08consec', 'corf08idnovedad='.$id06.'', $objDB);
					$corf08id=tabla_consecutivo('corf08novedadnota', 'corf08id', '', $objDB);
					$sCampos12208='corf08idnovedad, corf08consec, corf08id, corf08fecha, corf08hora, 
					corf08min, corf08nota, corf08idorigenanexo, corf08idarchivoanexo, corf08idusuario';
					$sValores12208=''.$id06.', '.$corf08consec.', '.$corf08id.', "'.$corf08fecha.'", '.$corf08hora.', 
					'.$corf08min.', "", 0, 0, '.$corf08idusuario.'';
					$sSQL12208='INSERT INTO corf08novedadnota ('.$sCampos12208.') VALUES ('.$sValores12208.')';
					$result=$objDB->ejecutasql($sSQL12208);
					$tabla8=$objDB->ejecutasql($sSQL);
					}
				if ($objDB->nf($tabla8)>0){
					$fila8=$objDB->sf($tabla8);
					$id08=$fila8['corf08id'];
					$sNotaNovedad=$fila8['corf08nota'];
					if ($fila['corf06estado']==2){
						$sHistorial=$sHistorial.html_salto().'<label><b>Su proceso esta devuelto, debe volver a cargar evidencias</b></label>';
						}else{
						$sHistorial=$sHistorial.html_salto().'<label><b>Este proceso requiere evidencias</b></label>';
						}
					if ($fila8['corf08idarchivoanexo']!=0){
						$sHistorial=$sHistorial.'<div id="div_corf08idarchivoanexo" class="Campo220">'.html_lnkarchivo($fila8['corf08idorigenanexo'], $fila8['corf08idarchivoanexo']).'</div>';
						}else{
						$sHistorial=$sHistorial.'<label><span class="rojo">No se han cargado evidencias</span></label>';
						}
					$sHistorial=$sHistorial.'<label class="Label130">
					<input type="button" id="banexacorf08idarchivoanexo" name="banexacorf08idarchivoanexo" value="Anexar" class="BotonAzul" onclick="carga_corf08idarchivoanexo()" title="Cargar evidencia"/>
					</label>';
					}
				}
			}
		if ($sHistorial!=''){
			if ((int)$id06!=0){
				$sHistorial=$sHistorial.html_salto().'<span class="naranja">'.'Al solicitar aplazar mas cursos, estos ser&aacute;n agregados a la solicitud actual.'.'</span>';
				}
			}
		}
	return array($sDetalle,$sHistorial, $sNotaNovedad, $id06, $id08);
	}
function f3000_InfoDesistir($aParametros){
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
	if (isset($aParametros[1])==0){$aParametros[1]='';}
	$id06=numeros_validar($aParametros[1]);
	list ($sDetalle, $iSolicitados, $sDebugP)=f3000_db_InfoDesistir($id06, $objDB, $bDebug);
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f3000curso8', 'innerHTML', $sDetalle);
	$objResponse->assign('marca07', 'value', $iSolicitados);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f3000_InfoPeriodoCurso($aParametros){
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
	if (isset($aParametros[1])==0){$aParametros[1]='';}
	if (isset($aParametros[2])==0){$aParametros[2]='';}
	if (isset($aParametros[3])==0){$aParametros[3]=0;}
	if (isset($aParametros[4])==0){$aParametros[4]=0;}
	$idPeriodo=numeros_validar($aParametros[1]);
	$idCurso=numeros_validar($aParametros[2]);
	$idEntrada=numeros_validar($aParametros[3]);
	$idEstudiante=numeros_validar($aParametros[4]);
	list($sDetalle,$sHistorial, $sNotaNovedad, $id06, $id08)=f3000_db_InfoPeriodoCurso($idPeriodo, $idCurso, $idEntrada, $idEstudiante, $objDB, $bDebug);
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	switch($idEntrada){
		case 2: //Cancelacion
		$objResponse->assign('div_f3000CursoCancela', 'innerHTML', $sDetalle);
		break;
		case 7: //Aplazamiento tardio. 
		$objResponse->assign('div_f3000curso7', 'innerHTML', $sDetalle);
		$objResponse->assign('div_f3000cursoexistentes', 'innerHTML', $sHistorial);
		$objResponse->assign('corf08nota', 'value', $sNotaNovedad);
		$objResponse->assign('corf06id', 'value', $id06);
		$objResponse->assign('corf08id', 'value', $id08);
		break;
		default:
		$objResponse->assign('div_f3000curso', 'innerHTML', $sDetalle);
		break;
		}
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}	
//Marcar el registro de que desiste
function f3000_MarcaDesistir($aParametros){
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
	if (isset($aParametros[1])==0){$aParametros[1]='';}
	$id06=numeros_validar($aParametros[1]);
	$id07=numeros_validar($aParametros[2]);
	$iEstado=numeros_validar($aParametros[3]);
	$sSQL='UPDATE corf07novedadcurso SET corf07paradesistir='.$iEstado.' WHERE corf07id='.$id07.'';
	$result=$objDB->ejecutasql($sSQL);
	list ($sDetalle, $iSolicitados, $sDebugP)=f3000_db_InfoDesistir($id06, $objDB, $bDebug);
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f3000curso8', 'innerHTML', $sDetalle);
	$objResponse->assign('marca07', 'value', $iSolicitados);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
?>