<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2019 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.23.1 jueves, 9 de mayo de 2019
--- 2602 gedo02tipodoc
*/
/** Archivo lib2602.php.
* Libreria 2602 gedo02tipodoc.
* @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
* @date jueves, 9 de mayo de 2019
*/
function f2602_ExisteDato($datos){
	if(!is_array($datos)){$datos=json_decode(str_replace('\"','"',$datos),true);}
	$_SESSION['u_ultimominuto']=iminutoavance();
	$bHayLlave=true;
	$gedo02consec=numeros_validar($datos[1]);
	if ($gedo02consec==''){$bHayLlave=false;}
	if ($bHayLlave){
		require './app.php';
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		$sSQL='SELECT gedo02consec FROM gedo02tipodoc WHERE gedo02consec='.$gedo02consec.'';
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
function f2602_Busquedas($aParametros){
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2602=$APP->rutacomun.'lg/lg_2602_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2602)){$mensajes_2602=$APP->rutacomun.'lg/lg_2602_es.php';}
	require $mensajes_todas;
	require $mensajes_2602;
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
	$sTitulo='<h2>'.$ETI['titulo_2602'].' - '.$sTitulo.'</h2>';
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97titulo', 'innerHTML', $sTitulo);
	$objResponse->assign('div_97params', 'innerHTML', $sParams);
	$objResponse->assign('div_97tabla', 'innerHTML', $sTabla);
	$objResponse->setFunction('paginarbusqueda','',$sJavaBusqueda);
	$objResponse->call('expandesector(97)');
	return $objResponse;
	}
function f2602_HtmlBusqueda($aParametros){
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
function f2602_TablaDetalleV2($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2602=$APP->rutacomun.'lg/lg_2602_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2602)){$mensajes_2602=$APP->rutacomun.'lg/lg_2602_es.php';}
	require $mensajes_todas;
	require $mensajes_2602;
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
	if (false){
		$sLeyenda='<div class="salto1px"></div>
<div class="GrupoCamposAyuda">
<b>Importante:</b> Mensaje al usuario
<div class="salto1px"></div>
</div>';
		}
	$sSQLadd='';
	$sSQLadd1='';
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
	$sTitulos='Consec, Id, Nombre, Formatipo, Activo, Tienevencimiento, Validezagnos, Proveedor';
	$sSQL='SELECT TB.gedo02consec, TB.gedo02id, TB.gedo02nombre, T4.gedo01nombre, TB.gedo02activo, TB.gedo02tienevencimiento, TB.gedo02validezagnos, T8.gedo03nombre, TB.gedo02formatipo, TB.gedo02proveedor 
FROM gedo02tipodoc AS TB, gedo01formatipo AS T4, gedo03proveedores AS T8 
WHERE '.$sSQLadd1.' TB.gedo02formatipo=T4.gedo01id AND TB.gedo02proveedor=T8.gedo03id '.$sSQLadd.'
ORDER BY TB.gedo02activo DESC, TB.gedo02nombre';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_2602" name="consulta_2602" type="hidden" value="'.$sSQLlista.'"/>
<input id="titulos_2602" name="titulos_2602" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objDB->ejecutasql($sSQL);
	if ($tabladetalle==false){
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 2602: '.$sSQL.'<br>';}
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		//$sLeyenda=$sSQL;
		}else{
		$registros=$objDB->nf($tabladetalle);
		if ($registros==0){
			//return array(utf8_encode($sErrConsulta.'<input id="paginaf2602" name="paginaf2602" type="hidden" value="'.$pagina.'"/><input id="lppf2602" name="lppf2602" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
<td><b>'.$ETI['gedo02consec'].'</b></td>
<td><b>'.$ETI['gedo02nombre'].'</b></td>
<td><b>'.$ETI['gedo02formatipo'].'</b></td>
<td><b>'.$ETI['gedo02activo'].'</b></td>
<td><b>'.$ETI['gedo02tienevencimiento'].'</b></td>
<td><b>'.$ETI['gedo02proveedor'].'</b></td>
<td align="right">
'.html_paginador('paginaf2602', $registros, $lineastabla, $pagina, 'paginarf2602()').'
'.html_lpp('lppf2602', $lineastabla, 'paginarf2602()').'
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
		$et_gedo02activo=$ETI['no'];
		if ($filadet['gedo02activo']=='S'){$et_gedo02activo=$ETI['si'];}
		$et_gedo02tienevencimiento=$ETI['no'];
		if ($filadet['gedo02tienevencimiento']=='S'){
			$et_gedo02tienevencimiento=$ETI['si'].' ['.$filadet['gedo02validezagnos'].' '.$ETI['msg_agnos'].']';
			}
		if ($babierta){
			$sLink='<a href="javascript:cargaridf2602('.$filadet['gedo02id'].')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$res=$res.'<tr'.$sClass.'>
<td>'.$sPrefijo.$filadet['gedo02consec'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['gedo02nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['gedo01nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.$et_gedo02activo.$sSufijo.'</td>
<td>'.$sPrefijo.$et_gedo02tienevencimiento.$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['gedo03nombre']).$sSufijo.'</td>
<td>'.$sLink.'</td>
</tr>';
		}
	$res=$res.'</table>';
	$objDB->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
	}
function f2602_HtmlTabla($aParametros){
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
	list($sDetalle, $sDebugTabla)=f2602_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f2602detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f2602_db_CargarPadre($DATA, $objDB, $bDebug=false){
	$sError='';
	$iTipoError=0;
	$sDebug='';
	require './app.php';
	if ($DATA['paso']==1){
		$sSQLcondi='gedo02consec='.$DATA['gedo02consec'].'';
		}else{
		$sSQLcondi='gedo02id='.$DATA['gedo02id'].'';
		}
	$sSQL='SELECT * FROM gedo02tipodoc WHERE '.$sSQLcondi;
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$DATA['gedo02consec']=$fila['gedo02consec'];
		$DATA['gedo02id']=$fila['gedo02id'];
		$DATA['gedo02nombre']=$fila['gedo02nombre'];
		$DATA['gedo02formatipo']=$fila['gedo02formatipo'];
		$DATA['gedo02activo']=$fila['gedo02activo'];
		$DATA['gedo02tienevencimiento']=$fila['gedo02tienevencimiento'];
		$DATA['gedo02validezagnos']=$fila['gedo02validezagnos'];
		$DATA['gedo02proveedor']=$fila['gedo02proveedor'];
		$bcargo=true;
		$DATA['paso']=2;
		$DATA['boculta2602']=0;
		$bLimpiaHijos=true;
		}else{
		$DATA['paso']=0;
		}
	return array($DATA, $sError, $iTipoError, $sDebug);
	}
function f2602_db_GuardarV2($DATA, $objDB, $bDebug=false){
	$iCodModulo=2602;
	$bAudita[2]=true;
	$bAudita[3]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2602=$APP->rutacomun.'lg/lg_2602_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2602)){$mensajes_2602=$APP->rutacomun.'lg/lg_2602_es.php';}
	require $mensajes_todas;
	require $mensajes_2602;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	// -- Se inicia validando todas las posibles entradas de usuario.
	/*
	if (isset($DATA['gedo02consec'])==0){$DATA['gedo02consec']='';}
	if (isset($DATA['gedo02id'])==0){$DATA['gedo02id']='';}
	if (isset($DATA['gedo02nombre'])==0){$DATA['gedo02nombre']='';}
	if (isset($DATA['gedo02formatipo'])==0){$DATA['gedo02formatipo']='';}
	if (isset($DATA['gedo02activo'])==0){$DATA['gedo02activo']='';}
	if (isset($DATA['gedo02tienevencimiento'])==0){$DATA['gedo02tienevencimiento']='';}
	if (isset($DATA['gedo02validezagnos'])==0){$DATA['gedo02validezagnos']='';}
	if (isset($DATA['gedo02proveedor'])==0){$DATA['gedo02proveedor']='';}
	*/
	$DATA['gedo02consec']=numeros_validar($DATA['gedo02consec']);
	$DATA['gedo02nombre']=htmlspecialchars(trim($DATA['gedo02nombre']));
	$DATA['gedo02formatipo']=numeros_validar($DATA['gedo02formatipo']);
	$DATA['gedo02activo']=htmlspecialchars(trim($DATA['gedo02activo']));
	$DATA['gedo02tienevencimiento']=htmlspecialchars(trim($DATA['gedo02tienevencimiento']));
	$DATA['gedo02validezagnos']=numeros_validar($DATA['gedo02validezagnos']);
	$DATA['gedo02proveedor']=numeros_validar($DATA['gedo02proveedor']);
	// -- Se inicializan las variables que puedan pasar vacias {Especialmente números}.
	//if ($DATA['gedo02formatipo']==''){$DATA['gedo02formatipo']=0;}
	//if ($DATA['gedo02proveedor']==''){$DATA['gedo02proveedor']=0;}
	// -- Seccion para validar los posibles causales de error.
	$sSepara=', ';
	if (true){
		if ($DATA['gedo02proveedor']==''){$sError=$ERR['gedo02proveedor'].$sSepara.$sError;}
		if ($DATA['gedo02tienevencimiento']=='S'){
			if ($DATA['gedo02validezagnos']==''){$sError=$ERR['gedo02validezagnos'].$sSepara.$sError;}
			}else{
			if ($DATA['gedo02validezagnos']==''){$DATA['gedo02validezagnos']=0;}
			}
		if ($DATA['gedo02tienevencimiento']==''){$sError=$ERR['gedo02tienevencimiento'].$sSepara.$sError;}
		if ($DATA['gedo02activo']==''){$sError=$ERR['gedo02activo'].$sSepara.$sError;}
		if ($DATA['gedo02formatipo']==''){$sError=$ERR['gedo02formatipo'].$sSepara.$sError;}
		if ($DATA['gedo02nombre']==''){$sError=$ERR['gedo02nombre'].$sSepara.$sError;}
		//Fin de las valiaciones NO LLAVE.
		}
	//Valiaciones de campos obligatorios en todo guardar.
	// -- Se verifican los valores de campos de otras tablas.
	if ($sError==''){
		if ($DATA['paso']==10){
			if ($DATA['gedo02consec']==''){
				$DATA['gedo02consec']=tabla_consecutivo('gedo02tipodoc', 'gedo02consec', '', $objDB);
				if ($DATA['gedo02consec']==-1){$sError=$objDB->serror;}
				}else{
				if (!seg_revisa_permiso($iCodModulo, 8, $objDB)){
					$sError=$ERR['8'];
					$DATA['gedo02consec']='';
					}
				}
			if ($sError==''){
				$sSQL='SELECT gedo02consec FROM gedo02tipodoc WHERE gedo02consec='.$DATA['gedo02consec'].'';
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
			$DATA['gedo02id']=tabla_consecutivo('gedo02tipodoc','gedo02id', '', $objDB);
			if ($DATA['gedo02id']==-1){$sError=$objDB->serror;}
			}
		}
	if ($sError==''){
		$bpasa=false;
		if ($DATA['paso']==10){
			$sCampos2602='gedo02consec, gedo02id, gedo02nombre, gedo02formatipo, gedo02activo, gedo02tienevencimiento, gedo02validezagnos, gedo02proveedor';
			$sValores2602=''.$DATA['gedo02consec'].', '.$DATA['gedo02id'].', "'.$DATA['gedo02nombre'].'", '.$DATA['gedo02formatipo'].', "'.$DATA['gedo02activo'].'", "'.$DATA['gedo02tienevencimiento'].'", '.$DATA['gedo02validezagnos'].', '.$DATA['gedo02proveedor'].'';
			if ($APP->utf8==1){
				$sSQL='INSERT INTO gedo02tipodoc ('.$sCampos2602.') VALUES ('.utf8_encode($sValores2602).');';
				$sdetalle=$sCampos2602.'['.utf8_encode($sValores2602).']';
				}else{
				$sSQL='INSERT INTO gedo02tipodoc ('.$sCampos2602.') VALUES ('.$sValores2602.');';
				$sdetalle=$sCampos2602.'['.$sValores2602.']';
				}
			$idaccion=2;
			$bpasa=true;
			}else{
			$scampo[1]='gedo02nombre';
			$scampo[2]='gedo02formatipo';
			$scampo[3]='gedo02activo';
			$scampo[4]='gedo02tienevencimiento';
			$scampo[5]='gedo02validezagnos';
			$scampo[6]='gedo02proveedor';
			$sdato[1]=$DATA['gedo02nombre'];
			$sdato[2]=$DATA['gedo02formatipo'];
			$sdato[3]=$DATA['gedo02activo'];
			$sdato[4]=$DATA['gedo02tienevencimiento'];
			$sdato[5]=$DATA['gedo02validezagnos'];
			$sdato[6]=$DATA['gedo02proveedor'];
			$numcmod=6;
			$sWhere='gedo02id='.$DATA['gedo02id'].'';
			$sSQL='SELECT * FROM gedo02tipodoc WHERE '.$sWhere;
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
					$sSQL='UPDATE gedo02tipodoc SET '.utf8_encode($sdatos).' WHERE '.$sWhere.';';
					}else{
					$sdetalle=$sdatos.'['.$sWhere.']';
					$sSQL='UPDATE gedo02tipodoc SET '.$sdatos.' WHERE '.$sWhere.';';
					}
				$idaccion=3;
				}
			}
		if ($bpasa){
			$result=$objDB->ejecutasql($sSQL);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' [2602] ..<!-- '.$sSQL.' -->';
				if ($idaccion==2){$DATA['gedo02id']='';}
				$DATA['paso']=$DATA['paso']-10;
				}else{
				if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Guardar 2602 '.$sSQL.'<br>';}
				if ($bAudita[$idaccion]){seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], $idaccion, $DATA['gedo02id'], $sdetalle, $objDB);}
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
function f2602_db_Eliminar($gedo02id, $objDB, $bDebug=false){
	$iCodModulo=2602;
	$bAudita[4]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2602=$APP->rutacomun.'lg/lg_2602_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2602)){$mensajes_2602=$APP->rutacomun.'lg/lg_2602_es.php';}
	require $mensajes_todas;
	require $mensajes_2602;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	$gedo02id=numeros_validar($gedo02id);
	// Traer los datos para hacer las validaciones.
	if ($sError==''){
		$sSQL='SELECT * FROM gedo02tipodoc WHERE gedo02id='.$gedo02id.'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$filabase=$objDB->sf($tabla);
			}else{
			$sError='No se encuentra el registro solicitado {Ref: '.$gedo02id.'}';
			}
		}
	if ($sError==''){
		if (!seg_revisa_permiso($iCodModulo, 4, $objDB)){
			$sError=$ERR['4'];
			}
		}
	if ($sError==''){
		$sSQL='SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=2602';
		$tablaor=$objDB->ejecutasql($sSQL);
		while ($filaor=$objDB->sf($tablaor)){
			$sSQL='SELECT '.$filaor['campoid'].' FROM '.$filaor['tabla'].' WHERE '.$filaor['camporev'].'='.$_REQUEST['gedo02id'].' LIMIT 0, 1';
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
		$sWhere='gedo02id='.$gedo02id.'';
		//$sWhere='gedo02consec='.$filabase['gedo02consec'].'';
		$sSQL='DELETE FROM gedo02tipodoc WHERE '.$sWhere.';';
		$result=$objDB->ejecutasql($sSQL);
		if ($result==false){
			$sError=$ERR['falla_eliminar'].' .. <!-- '.$sSQL.' -->';
			}else{
			if ($bAudita[4]){seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $gedo02id, $sWhere, $objDB);}
			}
		}
	return array($sError, $iTipoError, $sDebug);
	}
function f2602_TituloBusqueda(){
	return 'Busqueda de Tipos de documentos';
	}
function f2602_ParametrosBusqueda(){
	$sParams='<label class="Label90">Nombre</label><label><input id="b2602nombre" name="b2602nombre" type="text" value="" onchange="paginarbusqueda()" /></label>';
	return $sParams;
	}
function f2602_JavaScriptBusqueda($iModuloBusca){
	$sRes='var sCampo=window.document.frmedita.scampobusca.value;
var params=new Array();
params[100]=sCampo;
params[101]=window.document.frmedita.paginabusqueda.value;
params[102]=window.document.frmedita.lppfbusqueda.value;
params[103]=window.document.frmedita.b2602nombre.value;
xajax_f'.$iModuloBusca.'_HtmlBusqueda(params);';
	return $sRes;
	}
function f2602_TablaDetalleBusquedas($aParametros, $objDB){
	$res='';
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2602=$APP->rutacomun.'lg/lg_2602_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2602)){$mensajes_2602=$APP->rutacomun.'lg/lg_2602_es.php';}
	require $mensajes_todas;
	require $mensajes_2602;
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
	if (false){
		$sLeyenda='<div class="salto1px"></div>
<div class="GrupoCamposAyuda">
<b>Importante:</b> Mensaje al usuario
<div class="salto1px"></div>
</div>';
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
	$sTitulos='Consec, Id, Nombre, Formatipo, Activo, Tienevencimiento, Validezagnos, Proveedor';
	$sSQL='SELECT TB.gedo02consec, TB.gedo02id, TB.gedo02nombre, T4.gedo01nombre, TB.gedo02activo, TB.gedo02tienevencimiento, TB.gedo02validezagnos, T8.gedo03nombre, TB.gedo02formatipo, TB.gedo02proveedor 
FROM gedo02tipodoc AS TB, gedo01formatipo AS T4, gedo03proveedores AS T8 
WHERE '.$sSQLadd1.' TB.gedo02formatipo=T4.gedo01id AND TB.gedo02proveedor=T8.gedo03id '.$sSQLadd.'
ORDER BY TB.gedo02consec';
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
			//return array(utf8_encode($sErrConsulta.'<input id="paginaf2602" name="paginaf2602" type="hidden" value="'.$pagina.'"/><input id="lppf2602" name="lppf2602" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
<td><b>'.$ETI['gedo02consec'].'</b></td>
<td><b>'.$ETI['gedo02nombre'].'</b></td>
<td><b>'.$ETI['gedo02formatipo'].'</b></td>
<td><b>'.$ETI['gedo02activo'].'</b></td>
<td><b>'.$ETI['gedo02tienevencimiento'].'</b></td>
<td><b>'.$ETI['gedo02validezagnos'].'</b></td>
<td><b>'.$ETI['gedo02proveedor'].'</b></td>
<td align="right">
'.html_paginador('paginabusqueda', $registros, $lineastabla, $pagina, 'paginarbusqueda()').'
'.html_lpp('lppfbusqueda', $lineastabla, 'paginarbusqueda()').'
</td>
</tr>';
	$tlinea=1;
	while($filadet=$objDB->sf($tabladetalle)){
		$sPrefijo='<a href="javascript:Devuelve(\''.$filadet['gedo02id'].'\');">';
		$sSufijo='</a>';
		$tlinea++;
		$et_gedo02activo=$ETI['no'];
		if ($filadet['gedo02activo']=='S'){$et_gedo02activo=$ETI['si'];}
		$res=$res.'<tr onmouseover="cambia_color_over(this);" onmouseout="cambia_color_out(this);">
<td>'.$sPrefijo.$filadet['gedo02consec'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['gedo02nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['gedo01nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.$et_gedo02activo.$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['gedo02tienevencimiento'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['gedo02validezagnos'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['gedo03nombre']).$sSufijo.'</td>
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