<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2018 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.21.0 jueves, 21 de junio de 2018
--- 2303 cara03centroreclusion
*/
function f2303_ExisteDato($datos){
	if(!is_array($datos)){$datos=json_decode(str_replace('\"','"',$datos),true);}
	$_SESSION['u_ultimominuto']=iminutoavance();
	$bHayLlave=true;
	$cara03consec=numeros_validar($datos[1]);
	if ($cara03consec==''){$bHayLlave=false;}
	if ($bHayLlave){
		require './app.php';
		$objdb=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objdb->dbPuerto=$APP->dbpuerto;}
		$objdb->xajax();
		$sql='SELECT cara03consec FROM cara03centroreclusion WHERE cara03consec='.$cara03consec.'';
		$res=$objdb->ejecutasql($sql);
		if ($objdb->nf($res)==0){$bHayLlave=false;}
		$objdb->CerrarConexion();
		if ($bHayLlave){
			$objResponse=new xajaxResponse();
			$objResponse->call('cambiapaginaV2');
			return $objResponse;
			}
		}
	}
function f2303_Busquedas($params){
	require './app.php';
	$objdb=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objdb->dbPuerto=$APP->dbpuerto;}
	$objdb->xajax();
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2303='lg/lg_2303_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2303)){$mensajes_2303='lg/lg_2303_es.php';}
	require $mensajes_todas;
	require $mensajes_2303;
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	$sCampo=$params[1];
	$sTitulo=' {'.$sCampo.'}';
	if (isset($params[2])==0){$params[2]=0;}
	if (isset($params[3])==0){$params[3]=0;}
	$sParams='';
	$sTabla='';
	$sJavaBusqueda='';
	$paramsb=array();
	$paramsb[101]=1;
	$paramsb[102]=20;
	switch($sCampo){
		}
	$sTitulo='<h2>'.$ETI['titulo_2303'].' - '.$sTitulo.'</h2>';
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97titulo', 'innerHTML', $sTitulo);
	$objResponse->assign('div_97params', 'innerHTML', $sParams);
	$objResponse->assign('div_97tabla', 'innerHTML', $sTabla);
	$objResponse->setFunction('paginarbusqueda','',$sJavaBusqueda);
	$objResponse->call('expandesector(97)');
	return $objResponse;
	}
function f2303_HtmlBusqueda($params){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	$sError='';
	require './app.php';
	$objdb=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objdb->dbPuerto=$APP->dbpuerto;}
	$objdb->xajax();
	$sDetalle='';
	switch($params[100]){
		}
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97tabla', 'innerHTML', $sDetalle);
	return $objResponse;
	}
function f2303_TablaDetalleV2($params, $objdb, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2303='lg/lg_2303_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2303)){$mensajes_2303='lg/lg_2303_es.php';}
	require $mensajes_todas;
	require $mensajes_2303;
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	if (isset($params[101])==0){$params[101]=1;}
	if (isset($params[102])==0){$params[102]=20;}
	//if (isset($params[103])==0){$params[103]='';}
	//$params[103]=numeros_validar($params[103]);
	$sDebug='';
	$pagina=$params[101];
	$lineastabla=$params[102];
	$babierta=true;
	//$sql='SELECT Campo FROM Tabla WHERE Id='.$sValorId;
	//$tabla=$objdb->ejecutasql($sql);
	//if ($objdb->nf($tabla)>0){
		//$fila=$objdb->sf($tabla);
		//if ($fila['Campo']!='S'){$babierta=true;}
		//}
	$sLeyenda='';
	if (false){
		$sLeyenda='<div class="salto1px"></div>
<div class="GrupoCamposAyuda">
<b>Importante:</b> Mensaje al usuario
<div class="salto1px"></div>
</div>';
		}
	$sqladd='1';
	$sqladd1='';
	//if ((int)$params[103]!=-1){$sqladd=$sqladd.' AND TB.campo='.$params[103];}
	//if ($params[103]!=''){$sqladd=$sqladd.' AND TB.campo2 LIKE "%'.$params[103].'%"';}
	/*
	if ($params[104]!=''){
		$sBase=trim(strtoupper($params[104]));
		$aNoms=explode(' ', $sBase);
		for ($k=1;$k<=count($aNoms);$k++){
			$sCadena=$aNoms[$k-1];
			if ($sCadena!=''){
				$sqladd=$sqladd.' AND T6.sys11razonsocial LIKE "%'.$sCadena.'%"';
				//$sqladd1=$sqladd1.'T1.sys11razonsocial LIKE "%'.$sCadena.'%" AND ';
				}
			}
		}
	*/
	$sTitulos='Consec, Id, Nombre';
	$sql='SELECT TB.cara03consec, TB.cara03id, TB.cara03nombre 
FROM cara03centroreclusion AS TB 
WHERE '.$sqladd1.'  '.$sqladd.'
ORDER BY TB.cara03consec';
	$sqllista=str_replace("'","|",$sql);
	$sqllista=str_replace('"',"|",$sqllista);
	$sErrConsulta='<input id="consulta_2303" name="consulta_2303" type="hidden" value="'.$sqllista.'"/>
<input id="titulos_2303" name="titulos_2303" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objdb->ejecutasql($sql);
	if ($tabladetalle==false){
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 2303: '.$sql.'<br>';}
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sql.' '.$objdb->serror.'"/>';
		//$sLeyenda=$sql;
		}else{
		$registros=$objdb->nf($tabladetalle);
		if ($registros==0){
			//return array(utf8_encode($sErrConsulta.'<input id="paginaf2303" name="paginaf2303" type="hidden" value="'.$pagina.'"/><input id="lppf2303" name="lppf2303" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
			}
		if ((($registros-1)/$lineastabla)<($pagina-1)){$pagina=(int)(($registros-1)/$lineastabla)+1;}
		if ($registros>$lineastabla){
			$rbase=($pagina-1)*$lineastabla;
			$limite=' LIMIT '.$rbase.', '.$lineastabla;
			$tabladetalle=$objdb->ejecutasql($sql.$limite);
			}
		}
	$res=$sErrConsulta.$sLeyenda.'<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">
<tr class="fondoazul">
<td><b>'.$ETI['cara03consec'].'</b></td>
<td><b>'.$ETI['cara03nombre'].'</b></td>
<td align="right">
'.html_paginador('paginaf2303', $registros, $lineastabla, $pagina, 'paginarf2303()').'
'.html_lpp('lppf2303', $lineastabla, 'paginarf2303()').'
</td>
</tr>';
	$tlinea=1;
	while($filadet=$objdb->sf($tabladetalle)){
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
		if ($babierta){
			$sLink='<a href="javascript:cargaridf2303('.$filadet['cara03id'].')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$res=$res.'<tr'.$sClass.'>
<td>'.$sPrefijo.$filadet['cara03consec'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['cara03nombre']).$sSufijo.'</td>
<td>'.$sLink.'</td>
</tr>';
		}
	$res=$res.'</table>';
	$objdb->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
	}
function f2303_HtmlTabla($params){
	$_SESSION['u_ultimominuto']=iminutoavance();
	$sError='';
	$bDebug=false;
	$sDebug='';
	$opts=$params;
	if(!is_array($opts)){$opts=json_decode(str_replace('\"','"',$opts),true);}
	if (isset($opts[99])!=0){if ($opts[99]==1){$bDebug=true;}}
	require './app.php';
	$objdb=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objdb->dbPuerto=$APP->dbpuerto;}
	$objdb->xajax();
	list($sDetalle, $sDebugTabla)=f2303_TablaDetalleV2($params, $objdb, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objdb->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f2303detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f2303_db_CargarPadre($DATA, $objdb, $bDebug=false){
	$sError='';
	$iTipoError=0;
	$sDebug='';
	require './app.php';
	if ($DATA['paso']==1){
		$sqlcondi='cara03consec='.$DATA['cara03consec'].'';
		}else{
		$sqlcondi='cara03id='.$DATA['cara03id'].'';
		}
	$sql='SELECT * FROM cara03centroreclusion WHERE '.$sqlcondi;
	$tabla=$objdb->ejecutasql($sql);
	if ($objdb->nf($tabla)>0){
		$fila=$objdb->sf($tabla);
		$DATA['cara03consec']=$fila['cara03consec'];
		$DATA['cara03id']=$fila['cara03id'];
		$DATA['cara03nombre']=$fila['cara03nombre'];
		$bcargo=true;
		$DATA['paso']=2;
		$DATA['boculta2303']=0;
		$bLimpiaHijos=true;
		}else{
		$DATA['paso']=0;
		}
	return array($DATA, $sError, $iTipoError, $sDebug);
	}
function f2303_db_GuardarV2($DATA, $objdb, $bDebug=false){
	$icodmodulo=2303;
	$bAudita[2]=true;
	$bAudita[3]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2303='lg/lg_2303_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2303)){$mensajes_2303='lg/lg_2303_es.php';}
	require $mensajes_todas;
	require $mensajes_2303;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	// -- Se inicia validando todas las posibles entradas de usuario.
	/*
	if (isset($DATA['cara03consec'])==0){$DATA['cara03consec']='';}
	if (isset($DATA['cara03id'])==0){$DATA['cara03id']='';}
	if (isset($DATA['cara03nombre'])==0){$DATA['cara03nombre']='';}
	*/
	$DATA['cara03consec']=numeros_validar($DATA['cara03consec']);
	$DATA['cara03nombre']=htmlspecialchars(trim($DATA['cara03nombre']));
	// -- Se inicializan las variables que puedan pasar vacias {Especialmente números}.
	// -- Seccion para validar los posibles causales de error.
	$sSepara=', ';
	if (true){
		if ($DATA['cara03nombre']==''){$sError=$ERR['cara03nombre'].$sSepara.$sError;}
		//Fin de las valiaciones NO LLAVE.
		}
	//Valiaciones de campos obligatorios en todo guardar.
	// -- Se verifican los valores de campos de otras tablas.
	if ($sError==''){
		if ($DATA['paso']==10){
			if ($DATA['cara03consec']==''){
				$DATA['cara03consec']=tabla_consecutivo('cara03centroreclusion', 'cara03consec', '', $objdb);
				if ($DATA['cara03consec']==-1){$sError=$objdb->serror;}
				}else{
				if (!seg_revisa_permiso($icodmodulo, 8, $objdb)){
					$sError=$ERR['8'];
					$DATA['cara03consec']='';
					}
				}
			if ($sError==''){
				$sql='SELECT cara03consec FROM cara03centroreclusion WHERE cara03consec='.$DATA['cara03consec'].'';
				$result=$objdb->ejecutasql($sql);
				if ($objdb->nf($result)!=0){
					$sError=$ERR['existe'];
					}else{
					if (!seg_revisa_permiso($icodmodulo, 2, $objdb)){$sError=$ERR['2'];}
					}
				}
			}else{
			if (!seg_revisa_permiso($icodmodulo, 3, $objdb)){$sError=$ERR['3'];}
			}
		}
	if ($sError==''){
		if ($DATA['paso']==10){
			//Preparar el Id, Si no lo hay se quita la comprobación.
			$DATA['cara03id']=tabla_consecutivo('cara03centroreclusion','cara03id', '', $objdb);
			if ($DATA['cara03id']==-1){$sError=$objdb->serror;}
			}
		}
	if ($sError==''){
		$bpasa=false;
		if ($DATA['paso']==10){
			$sCampos2303='cara03consec, cara03id, cara03nombre';
			$sValores2303=''.$DATA['cara03consec'].', '.$DATA['cara03id'].', "'.$DATA['cara03nombre'].'"';
			if ($APP->utf8==1){
				$sql='INSERT INTO cara03centroreclusion ('.$sCampos2303.') VALUES ('.utf8_encode($sValores2303).');';
				$sdetalle=$sCampos2303.'['.utf8_encode($sValores2303).']';
				}else{
				$sql='INSERT INTO cara03centroreclusion ('.$sCampos2303.') VALUES ('.$sValores2303.');';
				$sdetalle=$sCampos2303.'['.$sValores2303.']';
				}
			$idaccion=2;
			$bpasa=true;
			}else{
			$scampo[1]='cara03nombre';
			$sdato[1]=$DATA['cara03nombre'];
			$numcmod=1;
			$sWhere='cara03id='.$DATA['cara03id'].'';
			$sql='SELECT * FROM cara03centroreclusion WHERE '.$sWhere;
			$sdatos='';
			$bPrimera=true;
			$result=$objdb->ejecutasql($sql);
			if ($objdb->nf($result)>0){
				$filabase=$objdb->sf($result);
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
					$sql='UPDATE cara03centroreclusion SET '.utf8_encode($sdatos).' WHERE '.$sWhere.';';
					}else{
					$sdetalle=$sdatos.'['.$sWhere.']';
					$sql='UPDATE cara03centroreclusion SET '.$sdatos.' WHERE '.$sWhere.';';
					}
				$idaccion=3;
				}
			}
		if ($bpasa){
			$result=$objdb->ejecutasql($sql);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' [2303] ..<!-- '.$sql.' -->';
				if ($idaccion==2){$DATA['cara03id']='';}
				$DATA['paso']=$DATA['paso']-10;
				}else{
				if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Guardar 2303 '.$sql.'<br>';}
				if ($bAudita[$idaccion]){seg_auditar($icodmodulo, $_SESSION['unad_id_tercero'], $idaccion, $DATA['cara03id'], $sdetalle, $objdb);}
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
function f2303_db_Eliminar($cara03id, $objdb, $bDebug=false){
	$icodmodulo=2303;
	$bAudita[2]=true;
	$bAudita[3]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2303='lg/lg_2303_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2303)){$mensajes_2303='lg/lg_2303_es.php';}
	require $mensajes_todas;
	require $mensajes_2303;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	$cara03id=numeros_validar($cara03id);
	// Traer los datos para hacer las validaciones.
	if ($sError==''){
		$sql='SELECT * FROM cara03centroreclusion WHERE cara03id='.$cara03id.'';
		$tabla=$objdb->ejecutasql($sql);
		if ($objdb->nf($tabla)>0){
			$filabase=$objdb->sf($tabla);
			}else{
			$sError='No se encuentra el registro solicitado {Ref: '.$cara03id.'}';
			}
		}
	if ($sError==''){
		if (!seg_revisa_permiso($icodmodulo, 4, $objdb)){
			$sError=$ERR['4'];
			}
		}
	if ($sError==''){
		$sql='SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=2303';
		$tablaor=$objdb->ejecutasql($sql);
		while ($filaor=$objdb->sf($tablaor)){
			$sql='SELECT '.$filaor['campoid'].' FROM '.$filaor['tabla'].' WHERE '.$filaor['camporev'].'='.$_REQUEST['cara03id'].' LIMIT 0, 1';
			$tabla=$objdb->ejecutasql($sql);
			if ($objdb->nf($tabla)>0){
				$sError=$filaor['mensaje'];
				if ($filaor['etiqueta']!=''){
					if (isset($ERR[$filaor['etiqueta']])!=0){$sError=$ERR[$filaor['etiqueta']];}
					}
				break;
				}
			}
		}
	if ($sError==''){
		$sWhere='cara03id='.$cara03id.'';
		//$sWhere='cara03consec='.$filabase['cara03consec'].'';
		$sql='DELETE FROM cara03centroreclusion WHERE '.$sWhere.';';
		$result=$objdb->ejecutasql($sql);
		if ($result==false){
			$sError=$ERR['falla_eliminar'].' .. <!-- '.$sql.' -->';
			}else{
			if ($bAudita[4]){seg_auditar($icodmodulo, $_SESSION['unad_id_tercero'], 4, $cara03id, $sWhere, $objdb);}
			}
		}
	return array($sError, $iTipoError, $sDebug);
	}
function f2303_TituloBusqueda(){
	return 'Busqueda de Centros de reclusion';
	}
function f2303_ParametrosBusqueda(){
	$sParams='<label class="Label90">Nombre</label><label><input id="b2303nombre" name="b2303nombre" type="text" value="" onchange="paginarbusqueda()" /></label>';
	return $sParams;
	}
function f2303_JavaScriptBusqueda($iModuloBusca){
	$sRes='var sCampo=window.document.frmedita.scampobusca.value;
var params=new Array();
params[100]=sCampo;
params[101]=window.document.frmedita.paginabusqueda.value;
params[102]=window.document.frmedita.lppfbusqueda.value;
params[103]=window.document.frmedita.b2303nombre.value;
xajax_f'.$iModuloBusca.'_HtmlBusqueda(params);';
	return $sRes;
	}
function f2303_TablaDetalleBusquedas($params, $objdb){
	$res='';
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2303='lg/lg_2303_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2303)){$mensajes_2303='lg/lg_2303_es.php';}
	require $mensajes_todas;
	require $mensajes_2303;
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	if (isset($params[101])==0){$params[101]=1;}
	if (isset($params[102])==0){$params[102]=20;}
	if (isset($params[103])==0){$params[103]='';}
	//$params[103]=numeros_validar($params[103]);
	$pagina=$params[101];
	$lineastabla=$params[102];
	$babierta=true;
	//$sql='SELECT Campo FROM Tabla WHERE Id='.$sValorId;
	//$tabla=$objdb->ejecutasql($sql);
	//if ($objdb->nf($tabla)>0){
		//$fila=$objdb->sf($tabla);
		//if ($fila['Campo']!='S'){$babierta=true;}
		//}
	$sLeyenda='';
	if (false){
		$sLeyenda='<div class="salto1px"></div>
<div class="GrupoCamposAyuda">
<b>Importante:</b> Mensaje al usuario
<div class="salto1px"></div>
</div>';
		}
	$sqladd='1';
	$sqladd1='';
	//if ($params[103]!=''){$sqladd1=$sqladd1.'TB.campo2 LIKE "%'.$params[103].'%" AND ';}
	//if ($params[103]!=''){$sqladd=$sqladd.' AND TB.campo2 LIKE "%'.$params[103].'%"';}
	/*
	if ($params[104]!=''){
		$sBase=trim(strtoupper($params[104]));
		$aNoms=explode(' ', $sBase);
		for ($k=1;$k<=count($aNoms);$k++){
			$sCadena=$aNoms[$k-1];
			if ($sCadena!=''){
				$sqladd=$sqladd.' AND T6.sys11razonsocial LIKE "%'.$sCadena.'%"';
				//$sqladd1=$sqladd1.'T1.sys11razonsocial LIKE "%'.$sCadena.'%" AND ';
				}
			}
		}
	*/
	$sTitulos='Consec, Id, Nombre';
	$sql='SELECT TB.cara03consec, TB.cara03id, TB.cara03nombre 
FROM cara03centroreclusion AS TB 
WHERE '.$sqladd1.'  '.$sqladd.'
ORDER BY TB.cara03consec';
	$sqllista=str_replace("'","|",$sql);
	$sqllista=str_replace('"',"|",$sqllista);
	$sErrConsulta='<input id="consulta_busqueda" name="consulta_busqueda" type="hidden" value="'.$sqllista.'"/>
<input id="titulos_busqueda" name="titulos_busqueda" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objdb->ejecutasql($sql);
	if ($tabladetalle==false){
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sql.' '.$objdb->serror.'"/>';
		//$sLeyenda=$sql;
		}else{
		$registros=$objdb->nf($tabladetalle);
		if ($registros==0){
			//return array(utf8_encode($sErrConsulta.'<input id="paginaf2303" name="paginaf2303" type="hidden" value="'.$pagina.'"/><input id="lppf2303" name="lppf2303" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
			}
		if ((($registros-1)/$lineastabla)<($pagina-1)){$pagina=(int)(($registros-1)/$lineastabla)+1;}
		if ($registros>$lineastabla){
			$rbase=($pagina-1)*$lineastabla;
			$limite=' LIMIT '.$rbase.', '.$lineastabla;
			$tabladetalle=$objdb->ejecutasql($sql.$limite);
			}
		}
	$res=$sErrConsulta.$sLeyenda.'<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">
<tr class="fondoazul">
<td><b>'.$ETI['cara03consec'].'</b></td>
<td><b>'.$ETI['cara03nombre'].'</b></td>
<td align="right">
'.html_paginador('paginabusqueda', $registros, $lineastabla, $pagina, 'paginarbusqueda()').'
'.html_lpp('lppfbusqueda', $lineastabla, 'paginarbusqueda()').'
</td>
</tr>';
	$tlinea=1;
	while($filadet=$objdb->sf($tabladetalle)){
		$sPrefijo='<a href="javascript:Devuelve(\''.$filadet['cara03id'].'\');">';
		$sSufijo='</a>';
		$tlinea++;
		$res=$res.'<tr onmouseover="cambia_color_over(this);" onmouseout="cambia_color_out(this);">
<td>'.$sPrefijo.$filadet['cara03consec'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['cara03nombre']).$sSufijo.'</td>
<td></td>
</tr>';
		}
	$res=$res.'</table>';
	$objdb->liberar($tabladetalle);
	return utf8_encode($res);
	}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------
?>