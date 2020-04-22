<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2018 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.22.3 lunes, 6 de agosto de 2018
--- 2313 cara13consejeros
*/
function f2313_HTMLComboV2_cara13peraca($objDB, $objCombos, $valor){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('cara13peraca', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	$objCombos->sAccion='RevisaLlave();';
	$sSQL=f146_ConsultaCombo(2216, $objDB);
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f2313_HTMLComboV2_cara01idzona($objDB, $objCombos, $valor){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('cara01idzona', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	$objCombos->sAccion='carga_combo_cara01idcead()';
	$sSQL='SELECT unad23id AS id, unad23nombre AS nombre FROM unad23zona';
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f2313_HTMLComboV2_cara01idcead($objDB, $objCombos, $valor, $vrcara01idzona){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	//@@ Se debe arreglar la condicion..
	$sCondi='unad24idzona="'.$vrcara01idzona.'"';
	if ($sCondi!=''){$sCondi=' WHERE '.$sCondi;}
	$objCombos->nuevo('cara01idcead', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	$sSQL='SELECT unad24id AS id, unad24nombre AS nombre FROM unad24sede'.$sCondi;
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f2313_Combocara01idcead($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$objCombos=new clsHtmlCombos('n');
	$html_cara01idcead=f2313_HTMLComboV2_cara01idcead($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_cara01idcead', 'innerHTML', $html_cara01idcead);
	return $objResponse;
	}
function f2313_ExisteDato($datos){
	if(!is_array($datos)){$datos=json_decode(str_replace('\"','"',$datos),true);}
	$_SESSION['u_ultimominuto']=iminutoavance();
	$bHayLlave=true;
	$cara13peraca=numeros_validar($datos[1]);
	if ($cara13peraca==''){$bHayLlave=false;}
	$cara13idconsejero=numeros_validar($datos[2]);
	if ($cara13idconsejero==''){$bHayLlave=false;}
	if ($bHayLlave){
		require './app.php';
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		$sSQL='SELECT cara13idconsejero FROM cara13consejeros WHERE cara13peraca='.$cara13peraca.' AND cara13idconsejero='.$cara13idconsejero.'';
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
function f2313_Busquedas($aParametros){
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2313='lg/lg_2313_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2313)){$mensajes_2313='lg/lg_2313_es.php';}
	require $mensajes_todas;
	require $mensajes_2313;
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
		case 'cara13idconsejero':
		require $APP->rutacomun.'lib111.php';
		$sTabla=f111_TablaDetalleBusquedas($aParametrosB, $objDB);
		$sTitulo='Busqueda de terceros';
		$sParams=f111_ParametrosBusqueda();
		$sJavaBusqueda=f111_JavaScriptBusqueda(2313);
		break;
		}
	$sTitulo='<h2>'.$ETI['titulo_2313'].' - '.$sTitulo.'</h2>';
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97titulo', 'innerHTML', $sTitulo);
	$objResponse->assign('div_97params', 'innerHTML', $sParams);
	$objResponse->assign('div_97tabla', 'innerHTML', $sTabla);
	$objResponse->setFunction('paginarbusqueda','',$sJavaBusqueda);
	$objResponse->call('expandesector(97)');
	return $objResponse;
	}
function f2313_HtmlBusqueda($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$sError='';
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$sDetalle='';
	switch($aParametros[100]){
		case 'cara13idconsejero':
		require $APP->rutacomun.'lib111.php';
		$sDetalle=f111_TablaDetalleBusquedas($aParametros, $objDB);
		break;
		}
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97tabla', 'innerHTML', $sDetalle);
	return $objResponse;
	}
function f2313_TablaDetalleV2($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2313='lg/lg_2313_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2313)){$mensajes_2313='lg/lg_2313_es.php';}
	require $mensajes_todas;
	require $mensajes_2313;
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	if (isset($aParametros[101])==0){$aParametros[101]=1;}
	if (isset($aParametros[102])==0){$aParametros[102]=20;}
	if (isset($aParametros[103])==0){$aParametros[103]='';}
	if (isset($aParametros[104])==0){$aParametros[104]='';}
	if (isset($aParametros[105])==0){$aParametros[105]='';}
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
	if ($aParametros[103]!=''){
		$sBase=trim(strtoupper($aParametros[103]));
		$aNoms=explode(' ', $sBase);
		for ($k=1;$k<=count($aNoms);$k++){
			$sCadena=$aNoms[$k-1];
			if ($sCadena!=''){
				$sSQLadd=$sSQLadd.' AND T2.unad11razonsocial LIKE "%'.$sCadena.'%"';
				//$sSQLadd1=$sSQLadd1.'T1.sys11razonsocial LIKE "%'.$sCadena.'%" AND ';
				}
			}
		}
	if ($aParametros[104]!=''){$sSQLadd1=$sSQLadd1.'TB.cara13peraca='.$aParametros[104].' AND ';}
	if ($aParametros[105]!=''){
		list($sCentrosZona, $sDebugZ)=f123_CentrosZona($aParametros[105], $objDB, $bDebug);
		$sSQLadd1=$sSQLadd1.' TB.cara01idcead IN ('.$sCentrosZona.') AND ';
		}
	$sTitulos='Peraca, Consejero, Id, Activo, Zona, Cead, Cargaasignada, Cargafinal';
	$sSQL='SELECT T1.exte02nombre, T2.unad11razonsocial AS C2_nombre, TB.cara13id, TB.cara13activo, T6.unad24nombre, TB.cara01cargaasignada, TB.cara01cargafinal, TB.cara13peraca, TB.cara13idconsejero, T2.unad11tipodoc AS C2_td, T2.unad11doc AS C2_doc, TB.cara01idzona, TB.cara01idcead 
FROM cara13consejeros AS TB, exte02per_aca AS T1, unad11terceros AS T2, unad24sede AS T6 
WHERE '.$sSQLadd1.' TB.cara13peraca=T1.exte02id AND TB.cara13idconsejero=T2.unad11id AND TB.cara01idcead=T6.unad24id '.$sSQLadd.'
ORDER BY TB.cara13peraca DESC, T6.unad24nombre, T2.unad11doc';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_2313" name="consulta_2313" type="hidden" value="'.$sSQLlista.'"/>
<input id="titulos_2313" name="titulos_2313" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objDB->ejecutasql($sSQL);
	if ($tabladetalle==false){
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 2313: '.$sSQL.'<br>';}
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		//$sLeyenda=$sSQL;
		}else{
		$registros=$objDB->nf($tabladetalle);
		if ($registros==0){
			//return array(utf8_encode($sErrConsulta.'<input id="paginaf2313" name="paginaf2313" type="hidden" value="'.$pagina.'"/><input id="lppf2313" name="lppf2313" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
<td colspan="2"><b>'.$ETI['cara13idconsejero'].'</b></td>
<td><b>'.$ETI['cara13activo'].'</b></td>
<td><b>'.$ETI['cara01idcead'].'</b></td>
<td><b>'.$ETI['cara01cargaasignada'].'</b></td>
<td align="right">
'.html_paginador('paginaf2313', $registros, $lineastabla, $pagina, 'paginarf2313()').'
'.html_lpp('lppf2313', $lineastabla, 'paginarf2313()').'
</td>
</tr>';
	$idPeraca=-99;
	$tlinea=1;
	while($filadet=$objDB->sf($tabladetalle)){
		if ($idPeraca!=$filadet['cara13peraca']){
			$idPeraca=$filadet['cara13peraca'];
			$res=$res.'<tr class="fondoazul">
<td colspan="6">'.$ETI['cara13peraca'].' <b>'.cadena_notildes($filadet['exte02nombre']).'</b></td>
</tr>';
			}
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
		$et_cara13activo=$ETI['no'];
		if ($filadet['cara13activo']=='S'){$et_cara13activo=$ETI['si'];}
		if ($babierta){
			$sLink='<a href="javascript:cargaridf2313('.$filadet['cara13id'].')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$res=$res.'<tr'.$sClass.'>
<td>'.$sPrefijo.$filadet['C2_td'].' '.$filadet['C2_doc'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['C2_nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.$et_cara13activo.$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['unad24nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['cara01cargafinal'].'/'.$filadet['cara01cargaasignada'].$sSufijo.'</td>
<td>'.$sLink.'</td>
</tr>';
		}
	$res=$res.'</table>';
	$objDB->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
	}
function f2313_HtmlTabla($aParametros){
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
	list($sDetalle, $sDebugTabla)=f2313_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f2313detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f2313_db_CargarPadre($DATA, $objDB, $bDebug=false){
	$sError='';
	$iTipoError=0;
	$sDebug='';
	require './app.php';
	$DATA['cara13idconsejero_td']=$APP->tipo_doc;
	$DATA['cara13idconsejero_doc']='';
	if ($DATA['paso']==1){
		$sSQLcondi='cara13peraca='.$DATA['cara13peraca'].' AND cara13idconsejero="'.$DATA['cara13idconsejero'].'"';
		}else{
		$sSQLcondi='cara13id='.$DATA['cara13id'].'';
		}
	$sSQL='SELECT * FROM cara13consejeros WHERE '.$sSQLcondi;
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$DATA['cara13peraca']=$fila['cara13peraca'];
		$DATA['cara13idconsejero']=$fila['cara13idconsejero'];
		$DATA['cara13id']=$fila['cara13id'];
		$DATA['cara13activo']=$fila['cara13activo'];
		$DATA['cara01idzona']=$fila['cara01idzona'];
		$DATA['cara01idcead']=$fila['cara01idcead'];
		$DATA['cara01cargaasignada']=$fila['cara01cargaasignada'];
		$DATA['cara01cargafinal']=$fila['cara01cargafinal'];
		$bcargo=true;
		$DATA['paso']=2;
		$DATA['boculta2313']=0;
		$bLimpiaHijos=true;
		}else{
		$DATA['paso']=0;
		}
	return array($DATA, $sError, $iTipoError, $sDebug);
	}
function f2313_db_GuardarV2($DATA, $objDB, $bDebug=false){
	$iCodModulo=2313;
	$bAudita[2]=true;
	$bAudita[3]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2313='lg/lg_2313_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2313)){$mensajes_2313='lg/lg_2313_es.php';}
	require $mensajes_todas;
	require $mensajes_2313;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	// -- Se inicia validando todas las posibles entradas de usuario.
	/*
	if (isset($DATA['cara13peraca'])==0){$DATA['cara13peraca']='';}
	if (isset($DATA['cara13idconsejero'])==0){$DATA['cara13idconsejero']='';}
	if (isset($DATA['cara13id'])==0){$DATA['cara13id']='';}
	if (isset($DATA['cara13activo'])==0){$DATA['cara13activo']='';}
	if (isset($DATA['cara01idzona'])==0){$DATA['cara01idzona']='';}
	if (isset($DATA['cara01idcead'])==0){$DATA['cara01idcead']='';}
	if (isset($DATA['cara01cargaasignada'])==0){$DATA['cara01cargaasignada']='';}
	*/
	$DATA['cara13peraca']=numeros_validar($DATA['cara13peraca']);
	$DATA['cara13activo']=htmlspecialchars(trim($DATA['cara13activo']));
	$DATA['cara01idzona']=numeros_validar($DATA['cara01idzona']);
	$DATA['cara01idcead']=numeros_validar($DATA['cara01idcead']);
	$DATA['cara01cargaasignada']=numeros_validar($DATA['cara01cargaasignada']);
	// -- Se inicializan las variables que puedan pasar vacias {Especialmente números}.
	//if ($DATA['cara01idzona']==''){$DATA['cara01idzona']=0;}
	//if ($DATA['cara01idcead']==''){$DATA['cara01idcead']=0;}
	//if ($DATA['cara01cargaasignada']==''){$DATA['cara01cargaasignada']=0;}
	// -- Seccion para validar los posibles causales de error.
	$sSepara=', ';
	if (true){
		if ($DATA['cara01cargaasignada']==''){$sError=$ERR['cara01cargaasignada'].$sSepara.$sError;}
		if ($DATA['cara01idcead']==''){$sError=$ERR['cara01idcead'].$sSepara.$sError;}
		if ($DATA['cara01idzona']==''){$sError=$ERR['cara01idzona'].$sSepara.$sError;}
		if ($DATA['cara13activo']==''){$sError=$ERR['cara13activo'].$sSepara.$sError;}
		//Fin de las valiaciones NO LLAVE.
		}
	//Valiaciones de campos obligatorios en todo guardar.
	if ($DATA['cara13idconsejero']==0){$sError=$ERR['cara13idconsejero'];}
	if ($DATA['cara13peraca']==''){$sError=$ERR['cara13peraca'];}
	// -- Se verifican los valores de campos de otras tablas.
	if ($sError==''){$sError=tabla_terceros_existe($DATA['cara13idconsejero_td'], $DATA['cara13idconsejero_doc'], $objDB, 'El tercero Consejero ');}
	if ($sError==''){
		list($sError, $sInfo)=tercero_Bloqueado($DATA['cara13idconsejero'], $objDB);
		if ($sInfo!=''){$sError=$sError.'<br>'.sInfo;}
		}
	if ($sError==''){
		if ($DATA['paso']==10){
			$sSQL='SELECT cara13peraca FROM cara13consejeros WHERE cara13peraca='.$DATA['cara13peraca'].' AND cara13idconsejero="'.$DATA['cara13idconsejero'].'"';
			$result=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($result)!=0){
				$sError=$ERR['existe'];
				}else{
				if (!seg_revisa_permiso($iCodModulo, 2, $objDB)){$sError=$ERR['2'];}
				}
			}else{
			if (!seg_revisa_permiso($iCodModulo, 3, $objDB)){$sError=$ERR['3'];}
			}
		}
	if ($sError==''){
		if ($DATA['paso']==10){
			//Preparar el Id, Si no lo hay se quita la comprobación.
			$DATA['cara13id']=tabla_consecutivo('cara13consejeros','cara13id', '', $objDB);
			if ($DATA['cara13id']==-1){$sError=$objDB->serror;}
			}
		}
	if ($sError==''){
		$bpasa=false;
		if ($DATA['paso']==10){
			$DATA['cara01cargafinal']=0;
			$sCampos2313='cara13peraca, cara13idconsejero, cara13id, cara13activo, cara01idzona, cara01idcead, cara01cargaasignada, cara01cargafinal';
			$sValores2313=''.$DATA['cara13peraca'].', '.$DATA['cara13idconsejero'].', '.$DATA['cara13id'].', "'.$DATA['cara13activo'].'", '.$DATA['cara01idzona'].', '.$DATA['cara01idcead'].', '.$DATA['cara01cargaasignada'].', '.$DATA['cara01cargafinal'].'';
			if ($APP->utf8==1){
				$sSQL='INSERT INTO cara13consejeros ('.$sCampos2313.') VALUES ('.utf8_encode($sValores2313).');';
				$sdetalle=$sCampos2313.'['.utf8_encode($sValores2313).']';
				}else{
				$sSQL='INSERT INTO cara13consejeros ('.$sCampos2313.') VALUES ('.$sValores2313.');';
				$sdetalle=$sCampos2313.'['.$sValores2313.']';
				}
			$idaccion=2;
			$bpasa=true;
			}else{
			$scampo[1]='cara13activo';
			$scampo[2]='cara01idzona';
			$scampo[3]='cara01idcead';
			$scampo[4]='cara01cargaasignada';
			$sdato[1]=$DATA['cara13activo'];
			$sdato[2]=$DATA['cara01idzona'];
			$sdato[3]=$DATA['cara01idcead'];
			$sdato[4]=$DATA['cara01cargaasignada'];
			$numcmod=4;
			$sWhere='cara13id='.$DATA['cara13id'].'';
			$sSQL='SELECT * FROM cara13consejeros WHERE '.$sWhere;
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
					$sSQL='UPDATE cara13consejeros SET '.utf8_encode($sdatos).' WHERE '.$sWhere.';';
					}else{
					$sdetalle=$sdatos.'['.$sWhere.']';
					$sSQL='UPDATE cara13consejeros SET '.$sdatos.' WHERE '.$sWhere.';';
					}
				$idaccion=3;
				}
			}
		if ($bpasa){
			$result=$objDB->ejecutasql($sSQL);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' [2313] ..<!-- '.$sSQL.' -->';
				if ($idaccion==2){$DATA['cara13id']='';}
				$DATA['paso']=$DATA['paso']-10;
				}else{
				if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Guardar 2313 '.$sSQL.'<br>';}
				if ($bAudita[$idaccion]){seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], $idaccion, $DATA['cara13id'], $sdetalle, $objDB);}
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
function f2313_db_Eliminar($cara13id, $objDB, $bDebug=false){
	$iCodModulo=2313;
	$bAudita[4]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2313='lg/lg_2313_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2313)){$mensajes_2313='lg/lg_2313_es.php';}
	require $mensajes_todas;
	require $mensajes_2313;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	$cara13id=numeros_validar($cara13id);
	// Traer los datos para hacer las validaciones.
	if ($sError==''){
		$sSQL='SELECT * FROM cara13consejeros WHERE cara13id='.$cara13id.'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$filabase=$objDB->sf($tabla);
			}else{
			$sError='No se encuentra el registro solicitado {Ref: '.$cara13id.'}';
			}
		}
	if ($sError==''){
		if (!seg_revisa_permiso($iCodModulo, 4, $objDB)){
			$sError=$ERR['4'];
			}
		}
	if ($sError==''){
		$sSQL='SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=2313';
		$tablaor=$objDB->ejecutasql($sSQL);
		while ($filaor=$objDB->sf($tablaor)){
			$sSQL='SELECT '.$filaor['campoid'].' FROM '.$filaor['tabla'].' WHERE '.$filaor['camporev'].'='.$_REQUEST['cara13id'].' LIMIT 0, 1';
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
		$sWhere='cara13id='.$cara13id.'';
		//$sWhere='cara13idconsejero="'.$filabase['cara13idconsejero'].'" AND cara13peraca='.$filabase['cara13peraca'].'';
		$sSQL='DELETE FROM cara13consejeros WHERE '.$sWhere.';';
		$result=$objDB->ejecutasql($sSQL);
		if ($result==false){
			$sError=$ERR['falla_eliminar'].' .. <!-- '.$sSQL.' -->';
			}else{
			if ($bAudita[4]){seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $cara13id, $sWhere, $objDB);}
			}
		}
	return array($sError, $iTipoError, $sDebug);
	}
function f2313_TituloBusqueda(){
	return 'Busqueda de Consejeros';
	}
function f2313_ParametrosBusqueda(){
	$sParams='<label class="Label90">Nombre</label><label><input id="b2313nombre" name="b2313nombre" type="text" value="" onchange="paginarbusqueda()" /></label>';
	return $sParams;
	}
function f2313_JavaScriptBusqueda($iModuloBusca){
	$sRes='var sCampo=window.document.frmedita.scampobusca.value;
var params=new Array();
params[100]=sCampo;
params[101]=window.document.frmedita.paginabusqueda.value;
params[102]=window.document.frmedita.lppfbusqueda.value;
params[103]=window.document.frmedita.b2313nombre.value;
xajax_f'.$iModuloBusca.'_HtmlBusqueda(params);';
	return $sRes;
	}
function f2313_TablaDetalleBusquedas($aParametros, $objDB){
	$res='';
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2313='lg/lg_2313_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2313)){$mensajes_2313='lg/lg_2313_es.php';}
	require $mensajes_todas;
	require $mensajes_2313;
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
				$sSQLadd=$sSQLadd.' AND T6.sys11razonsocial LIKE "%'.$sCadena.'%"';
				//$sSQLadd1=$sSQLadd1.'T1.sys11razonsocial LIKE "%'.$sCadena.'%" AND ';
				}
			}
		}
	*/
	$sTitulos='Peraca, Consejero, Id, Activo, Zona, Cead, Cargaasignada, Cargafinal';
	$sSQL='SELECT T1.exte02nombre, T2.unad11razonsocial AS C2_nombre, TB.cara13id, TB.cara13activo, T5.unad23nombre, T6.unad24nombre, TB.cara01cargaasignada, TB.cara01cargafinal, TB.cara13peraca, TB.cara13idconsejero, T2.unad11tipodoc AS C2_td, T2.unad11doc AS C2_doc, TB.cara01idzona, TB.cara01idcead 
FROM cara13consejeros AS TB, exte02per_aca AS T1, unad11terceros AS T2, unad23zona AS T5, unad24sede AS T6 
WHERE '.$sSQLadd1.' TB.cara13peraca=T1.exte02id AND TB.cara13idconsejero=T2.unad11id AND TB.cara01idzona=T5.unad23id AND TB.cara01idcead=T6.unad24id '.$sSQLadd.'
ORDER BY TB.cara13peraca, TB.cara13idconsejero';
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
			//return array(utf8_encode($sErrConsulta.'<input id="paginaf2313" name="paginaf2313" type="hidden" value="'.$pagina.'"/><input id="lppf2313" name="lppf2313" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
<td><b>'.$ETI['cara13peraca'].'</b></td>
<td colspan="2"><b>'.$ETI['cara13idconsejero'].'</b></td>
<td><b>'.$ETI['cara13activo'].'</b></td>
<td><b>'.$ETI['cara01idzona'].'</b></td>
<td><b>'.$ETI['cara01idcead'].'</b></td>
<td><b>'.$ETI['cara01cargaasignada'].'</b></td>
<td><b>'.$ETI['cara01cargafinal'].'</b></td>
<td align="right">
'.html_paginador('paginabusqueda', $registros, $lineastabla, $pagina, 'paginarbusqueda()').'
'.html_lpp('lppfbusqueda', $lineastabla, 'paginarbusqueda()').'
</td>
</tr>';
	$tlinea=1;
	while($filadet=$objDB->sf($tabladetalle)){
		$sPrefijo='<a href="javascript:Devuelve(\''.$filadet['cara13id'].'\');">';
		$sSufijo='</a>';
		$tlinea++;
		$et_cara13activo=$ETI['no'];
		if ($filadet['cara13activo']=='S'){$et_cara13activo=$ETI['si'];}
		$res=$res.'<tr onmouseover="cambia_color_over(this);" onmouseout="cambia_color_out(this);">
<td>'.$sPrefijo.cadena_notildes($filadet['exte02nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['C2_td'].' '.$filadet['C2_doc'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['C2_nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.$et_cara13activo.$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['unad23nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['unad24nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['cara01cargaasignada'].$sSufijo.'</td>
<td align="right">'.$sPrefijo.formato_moneda($filadet['cara01cargafinal']).$sSufijo.'</td>
<td></td>
</tr>';
		}
	$res=$res.'</table>';
	$objDB->liberar($tabladetalle);
	return utf8_encode($res);
	}
function f2313_ProcesarArchivo($DATA, $ARCHIVO, $objDB, $bDebug=false){
	$iCodModulo=2313;
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
		$sCampos2313='cara13peraca, cara13idconsejero, cara13id, cara13activo, cara01idzona, cara01idcead, cara01cargaasignada, cara01cargafinal';
		$cara13id=tabla_consecutivo('cara13consejeros','cara13id', '', $objDB);
		$iFila=1;
		$iDatos=0;
		$iActualizados=0;
		$aConsejero=array();
		$aLlaves=array();
		$iTotal=0;
		//Alistamos consejeros
		$sSQL='SELECT cara13peraca, T11.unad11doc, TB.cara13idconsejero 
FROM cara13consejeros AS TB, unad11terceros AS T11 
WHERE TB.cara13idconsejero=T11.unad11id';
		$tabla=$objDB->ejecutasql($sSQL);
		while($fila=$objDB->sf($tabla)){
			$sIdArreglo=$fila['cara13peraca'].'_'.$fila['unad11doc'];
			$iTotal++;
			$aLlaves[$iTotal]=$sIdArreglo;
			$aConsejero[$sIdArreglo]['id']=$fila['cara13idconsejero'];
			$aConsejero[$sIdArreglo]['peraca']=$fila['cara13peraca'];
			$aConsejero[$sIdArreglo]['ajusta']=0;
			}
		//$sCampos2313='cara13peraca, cara13idconsejero, cara13id, cara13activo, cara01idzona, cara01idcead, cara01cargaasignada, cara01cargafinal';
		//$cara13id=tabla_consecutivo('cara13consejeros','cara13id', '', $objDB);
		$sDato=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(0, $iFila)->getValue();
		while($sDato!=''){
			$iDatos++;
			//Aqui se debe procesar
			$sDocConsejero=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(1, $iFila)->getValue();
			$idConsejero=0;
			$sPeraca=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(2, $iFila)->getValue();
			if ($sPeraca==''){
				if ($DATA['paso']==2){
					$sPeraca=$DATA['cara13peraca'];
					$idConsejero=$DATA['cara13idconsejero'];
					}
				}
			$sErrLinea='';
			$sDato2=numeros_validar($sDato);
			if ($sDato2!=$sDato){
				$sErrLinea='Fila '.$iFila.': Documento errado';
				}
			if ($sErrLinea==''){
				//Los terceros ya deben existir
				$sSQL='SELECT unad11id FROM unad11terceros WHERE unad11doc="'.$sDato.'"';
				$tabla=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($tabla)>0){
					$fila=$objDB->sf($tabla);
					$unad11id=$fila['unad11id'];
					}else{
					$sErrLinea='Fila '.$iFila.': Documento de estudiante NO ENCONTRADO ['.$sDato.']';
					}
				}
			if ($sErrLinea==''){
				if ($idConsejero==0){
					$sIdArreglo=$sPeraca.'_'.$sDocConsejero;
					if (isset($aConsejero[$sIdArreglo])==0){
						$sSQL='SELECT unad11id FROM unad11terceros WHERE unad11doc="'.$sDocConsejero.'"';
						$tabla11=$objDB->ejecutasql($sSQL);
						if ($objDB->nf($tabla11)>0){
							$fila11=$objDB->sf($tabla11);
							$idConsejero=$fila11['unad11id'];
							}
						if ($idConsejero>0){
							$sValores2313=''.$sPeraca.', '.$idConsejero.', '.$cara13id.', "S", 0, 0, 0, 0';
							$sSQL='INSERT INTO cara13consejeros ('.$sCampos2313.') VALUES ('.$sValores2313.');';
							$sdetalle=$sCampos2313.'['.$sValores2313.']';
							$result=$objDB->ejecutasql($sSQL);
							}else{
							$result=false;
							}
						if ($result==false){
							$sErrLinea='Fila '.$iFila.': No se encuentra registrado el consejero '.$sDocConsejero.' en el periodo '.$sPeraca.' <!-- '.$sSQL.' -->';
							}else{
							seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 2, $cara13id, $sdetalle, $objDB);
							$cara13id++;
							$iTotal++;
							$aLlaves[$iTotal]=$sIdArreglo;
							$aConsejero[$sIdArreglo]['id']=$idConsejero;
							$aConsejero[$sIdArreglo]['peraca']=$sPeraca;
							$aConsejero[$sIdArreglo]['ajusta']=1;
							}
						}else{
						$idConsejero=$aConsejero[$sIdArreglo]['id'];
						$aConsejero[$sIdArreglo]['ajusta']=1;
						}
					}
				}
			if ($sErrLinea==''){
				//Ver si ya tiene encuesta.
				// AND cara01idperaca='.$sPeraca.'
				$sSQL01='SELECT cara01id, cara01idconsejero, cara01idperaca, cara01idperiodoacompana, cara01fechacierreacom FROM cara01encuesta WHERE cara01idtercero='.$unad11id.' ORDER BY cara01idperaca DESC';
				$tabla=$objDB->ejecutasql($sSQL01);
				if ($objDB->nf($tabla)==0){
					//Le iniciamos la encuesta porque no tiene una...
					$sSQL='SELECT 1 FROM core01estprograma WHERE core01idtercero='.$unad11id.' AND core01peracainicial>0 ORDER BY core01peracainicial DESC';
					$result=$objDB->ejecutasql($sSQL);
					if ($objDB->nf($result)>0){
						f2301_IniciarEncuesta($unad11id, 0, $objDB);
						$tabla=$objDB->ejecutasql($sSQL01);
						}
					}
				if ($objDB->nf($tabla)>0){
					$fila=$objDB->sf($tabla);
					if ($fila['cara01idconsejero']!=$idConsejero){
						if ($fila['cara01fechacierreacom']==0){
							$sDatos='cara01idconsejero='.$idConsejero.', cara01idperiodoacompana='.$sPeraca.'';
							$sSQL='UPDATE cara01encuesta SET '.$sDatos.' WHERE cara01id='.$fila['cara01id'].'';
							$result=$objDB->ejecutasql($sSQL);
							seg_auditar(2301, $_SESSION['unad_id_tercero'], 3, $fila['cara01id'], $sDatos, $objDB);
							}else{
							if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Fila '.$iFila.': Al estudiante ya se le hizo acompa&ntilde;amiento ['.$sDato.']<br>';}
							}
						}else{
						if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Fila '.$iFila.': Consejero ya asignado al estudiante ['.$sDato.']<br>';}
						}
					}else{
					if (true){
					//Intentamos dejar el dato
					$sSQL='SELECT core16id FROM core16actamatricula WHERE core16tercero='.$unad11id.' AND core16peraca='.$sPeraca.'';
					$tabla=$objDB->ejecutasql($sSQL);
					if ($objDB->nf($tabla)>0){
						$fila=$objDB->sf($tabla);
						$sSQL='UPDATE core16actamatricula SET core16idconsejero='.$idConsejero.' WHERE core16id='.$fila['core16id'].'';
						$result=$objDB->ejecutasql($sSQL);
						if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Fila '.$iFila.': Estudiante que no ha ingresado, se marca como pendiente ['.$sDato.']<br>';}
						//$sErrLinea='Fila '.$iFila.': Estudiante que no ha ingresado, se marca como pendiente ['.$sDato.']';
						}else{
						$sErrLinea='Fila '.$iFila.': Documento SIN ACTA DE MATRICULA ['.$sDato.'] ';
						}
						}
					}
				}
			if ($sErrLinea==''){
				$iActualizados++;
				}
			if ($sErrLinea!=''){
				if ($sInfoProceso!=''){$sInfoProceso=$sInfoProceso.'<br>';}
				$sInfoProceso=$sInfoProceso.$sErrLinea;
				}
			//$iActualizados++;
			//Leer el siguiente dato
			$iFila++;
			$sDato=$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(0, $iFila)->getValue();
			}
		for ($k=1;$k<=$iTotal;$k++){
			$sIdArreglo=$aLlaves[$k];
			if ($aConsejero[$sIdArreglo]['ajusta']==1){
				list($sErrorC, $sDebugU)=f2313_ActualizarCarga($aConsejero[$sIdArreglo]['peraca'], $objDB, $bDebug, $aConsejero[$sIdArreglo]['id']);
				$sDebug=$sDebug.$sDebugU;
				}
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
function f2313_ActualizarCarga($idPeraca, $objDB, $bDebug=false, $idConsejero=0){
	$sError='';
	$sDebug='';
	$sAdd='';
	if ($idConsejero!=0){$sAdd=' AND cara13idconsejero='.$idConsejero.'';}
	$sSQL='SELECT cara13idconsejero, cara13id, cara01cargafinal FROM cara13consejeros WHERE cara13peraca='.$idPeraca.'';
	$tabla=$objDB->ejecutasql($sSQL);
	while($fila=$objDB->sf($tabla)){
		$sSQL='SELECT cara01id FROM cara01encuesta WHERE cara01idconsejero='.$fila['cara13idconsejero'].' AND cara01idperaca='.$idPeraca.'';
		$result=$objDB->ejecutasql($sSQL);
		$iCarga=$objDB->nf($result);
		$sSQL='UPDATE cara13consejeros SET cara01cargafinal='.$iCarga.' WHERE cara13id='.$fila['cara13id'].'';
		$result=$objDB->ejecutasql($sSQL);
		}
	return array($sError, $sDebug);
	}
?>