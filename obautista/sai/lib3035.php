<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2020 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.25.4 miércoles, 5 de agosto de 2020
--- 3035 Cobertura
*/
function f3035_HTMLComboV2_saiu35idzona($objDB, $objCombos, $valor){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('saiu35idzona', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	//$objCombos->iAncho=450;
	$objCombos->sAccion='carga_combo_saiu35idcentro()';
	$sSQL='SELECT unad23id AS id, unad23nombre AS nombre FROM unad23zona';
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f3035_HTMLComboV2_saiu35idcentro($objDB, $objCombos, $valor, $vrsaiu35idzona){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	//@@ Se debe arreglar la condicion..
	$sCondi='unad24idzona="'.$vrsaiu35idzona.'"';
	if ($sCondi!=''){$sCondi=' WHERE '.$sCondi;}
	$objCombos->nuevo('saiu35idcentro', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	//$objCombos->iAncho=450;
	$objCombos->sAccion='revisaf3035()';
	$sSQL='SELECT unad24id AS id, unad24nombre AS nombre FROM unad24sede'.$sCondi;
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f3035_Combosaiu35idcentro($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$objCombos=new clsHtmlCombos();
	$html_saiu35idcentro=f3035_HTMLComboV2_saiu35idcentro($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_saiu35idcentro', 'innerHTML', $html_saiu35idcentro);
	//$objResponse->call('$("#saiu35idcentro").chosen()');
	return $objResponse;
	}
function f3035_db_Guardar($valores, $objDB, $bDebug=false){
	$iCodModulo=3035;
	$bAudita[2]=false;
	$bAudita[3]=false;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3035='lg/lg_3035_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3035)){$mensajes_3035='lg/lg_3035_es.php';}
	require $mensajes_todas;
	require $mensajes_3035;
	$sError='';
	$sDebug='';
	$bInserta=false;
	$iAccion=3;
	if(!is_array($valores)){$valores=json_decode(str_replace('\"','"',$valores),true);}
	$saiu35idbasecon=numeros_validar($valores[1]);
	$saiu35idzona=numeros_validar($valores[2]);
	$saiu35idcentro=numeros_validar($valores[3]);
	$saiu35id=numeros_validar($valores[4], true);
	$saiu35activo=numeros_validar($valores[5]);
	//if ($saiu35activo==''){$saiu35activo=0;}
	$sSepara=', ';
	if ($saiu35activo==''){$sError=$ERR['saiu35activo'].$sSepara.$sError;}
	//if ($saiu35id==''){$sError=$ERR['saiu35id'].$sSepara.$sError;}//CONSECUTIVO
	if ($saiu35idcentro==''){$sError=$ERR['saiu35idcentro'].$sSepara.$sError;}
	if ($saiu35idzona==''){$sError=$ERR['saiu35idzona'].$sSepara.$sError;}
	if ($saiu35idbasecon==''){$sError=$ERR['saiu35idbasecon'].$sSepara.$sError;}
	if ($sError==''){
		if ((int)$saiu35id==0){
			if ($sError==''){
				$sSQL='SELECT saiu35idbasecon FROM saiu35cobertura WHERE saiu35idbasecon='.$saiu35idbasecon.' AND saiu35idzona='.$saiu35idzona.' AND saiu35idcentro='.$saiu35idcentro.'';
				$result=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($result)!=0){
					$sError=$ERR['existe'];
					}else{
					if (!seg_revisa_permiso($iCodModulo, 2, $objDB)){$sError=$ERR['2'];}
					}
				}
			if ($sError==''){
				$saiu35id=tabla_consecutivo('saiu35cobertura', 'saiu35id', '', $objDB);
				if ($saiu35id==-1){$sError=$objDB->serror;}
				$bInserta=true;
				$iAccion=2;
				}
			}else{
			if (!seg_revisa_permiso($iCodModulo, 3, $objDB)){$sError=$ERR['3'];}
			}
		}
	if ($sError==''){
		if ($bInserta){
			}
		}
	if ($sError==''){
		if ($bInserta){
			$sCampos3035='saiu35idbasecon, saiu35idzona, saiu35idcentro, saiu35id, saiu35activo';
			$sValores3035=''.$saiu35idbasecon.', '.$saiu35idzona.', '.$saiu35idcentro.', '.$saiu35id.', '.$saiu35activo.'';
			if ($APP->utf8==1){
				$sSQL='INSERT INTO saiu35cobertura ('.$sCampos3035.') VALUES ('.utf8_encode($sValores3035).');';
				}else{
				$sSQL='INSERT INTO saiu35cobertura ('.$sCampos3035.') VALUES ('.$sValores3035.');';
				}
			if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Guardar 3035 '.$sSQL.'<br>';}
			$result=$objDB->ejecutasql($sSQL);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' [3035].<!-- '.$sSQL.' -->';
				}else{
				if ($bAudita[2]){
					seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 2, $saiu35id, $sSQL, $objDB);
					}
				}
			}else{
			$scampo3035[1]='saiu35activo';
			$svr3035[1]=$saiu35activo;
			$inumcampos=1;
			$sWhere='saiu35id='.$saiu35id.'';
			//$sWhere='saiu35idbasecon='.$saiu35idbasecon.' AND saiu35idzona='.$saiu35idzona.' AND saiu35idcentro='.$saiu35idcentro.'';
			$sSQL='SELECT * FROM saiu35cobertura WHERE '.$sWhere;
			$sdatos='';
			$bpasa=false;
			$result=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($result)>0){
				$filaorigen=$objDB->sf($result);
				for ($k=1;$k<=$inumcampos;$k++){
					if ($filaorigen[$scampo3035[$k]]!=$svr3035[$k]){
						if ($sdatos!=''){$sdatos=$sdatos.', ';}
						$sdatos=$sdatos.$scampo3035[$k].'="'.$svr3035[$k].'"';
						$bpasa=true;
						}
					}
				}
			if ($bpasa){
				if ($APP->utf8==1){
					$sSQL='UPDATE saiu35cobertura SET '.utf8_encode($sdatos).' WHERE '.$sWhere.';';
					}else{
					$sSQL='UPDATE saiu35cobertura SET '.$sdatos.' WHERE '.$sWhere.';';
					}
				$result=$objDB->ejecutasql($sSQL);
				if ($result==false){
					$sError=$ERR['falla_guardar'].' {Cobertura}. <!-- '.$sSQL.' -->';
					}else{
					if ($bAudita[3]){
						seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 3, $saiu35id, $sSQL, $objDB);
						}
					}
				}
			}
		}
	return array($sError, $iAccion, $saiu35id, $sDebug);
	}
function f3035_db_Eliminar($aParametros, $objDB, $bDebug=false){
	$iCodModulo=3035;
	$bAudita[4]=false;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3035='lg/lg_3035_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3035)){$mensajes_3035='lg/lg_3035_es.php';}
	require $mensajes_todas;
	require $mensajes_3035;
	$sError='';
	$sDebug='';
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$saiu35idbasecon=numeros_validar($aParametros[1]);
	$saiu35idzona=numeros_validar($aParametros[2]);
	$saiu35idcentro=numeros_validar($aParametros[3]);
	$saiu35id=numeros_validar($aParametros[4]);
	if ($sError==''){
		if (!seg_revisa_permiso($iCodModulo, 4, $objDB)){
			$sError=$ERR['4'];
			}
		}
	if ($sError==''){
		$sSQL='SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=3035';
		$tablaor=$objDB->ejecutasql($sSQL);
		while ($filaor=$objDB->sf($tablaor)){
			$sSQL='SELECT '.$filaor['campoid'].' FROM '.$filaor['tabla'].' WHERE '.$filaor['camporev'].'='.$saiu35id.' LIMIT 0, 1';
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
		//acciones previas
		$sWhere='saiu35id='.$saiu35id.'';
		//$sWhere='saiu35idbasecon='.$saiu35idbasecon.' AND saiu35idzona='.$saiu35idzona.' AND saiu35idcentro='.$saiu35idcentro.'';
		$sSQL='DELETE FROM saiu35cobertura WHERE '.$sWhere.';';
		$result=$objDB->ejecutasql($sSQL);
		if ($result==false){
			$sError=$ERR['falla_eliminar'].' {3035 Cobertura}.<!-- '.$sSQL.' -->';
			}else{
			if ($bAudita[4]){
				seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $saiu35id, $sSQL, $objDB);
				}
			}
		}
	return array($sError, $sDebug);
	}
function f3035_TablaDetalleV2($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3035='lg/lg_3035_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3035)){$mensajes_3035='lg/lg_3035_es.php';}
	require $mensajes_todas;
	require $mensajes_3035;
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	if (isset($aParametros[0])==0){$aParametros[0]=-1;}
	if (isset($aParametros[100])==0){$aParametros[100]=$_SESSION['unad_id_tercero'];}
	if (isset($aParametros[101])==0){$aParametros[101]=1;}
	if (isset($aParametros[102])==0){$aParametros[102]=20;}
	//if (isset($aParametros[103])==0){$aParametros[103]='';}
	//if (isset($aParametros[104])==0){$aParametros[104]='';}
	//$aParametros[103]=numeros_validar($aParametros[103]);
	$aParametros[0]=numeros_validar($aParametros[0]);
	if ($aParametros[0]==''){$aParametros[0]=-1;}
	$idTercero=$aParametros[100];
	$sDebug='';
	$saiu31id=$aParametros[0];
	$pagina=$aParametros[101];
	$lineastabla=$aParametros[102];
	$bAbierta=true;
	//$sSQL='SELECT Campo FROM saiu31baseconocimiento WHERE saiu31id='.$saiu31id;
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
		return array($sLeyenda.'<input id="paginaf3035" name="paginaf3035" type="hidden" value="'.$pagina.'"/><input id="lppf3035" name="lppf3035" type="hidden" value="'.$lineastabla.'"/>', $sDebug);
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
	$sTitulos='Basecon, Zona, Centro, Id, Activo';
	$sSQL='SELECT TB.saiu35idbasecon, T2.unad23nombre, T3.unad24nombre, TB.saiu35id, TB.saiu35activo, TB.saiu35idzona, TB.saiu35idcentro 
FROM saiu35cobertura AS TB, unad23zona AS T2, unad24sede AS T3 
WHERE '.$sSQLadd1.' TB.saiu35idbasecon='.$saiu31id.' AND TB.saiu35idzona=T2.unad23id AND TB.saiu35idcentro=T3.unad24id '.$sSQLadd.'
ORDER BY TB.saiu35idzona, TB.saiu35idcentro';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_3035" name="consulta_3035" type="hidden" value="'.$sSQLlista.'"/>
<input id="titulos_3035" name="titulos_3035" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objDB->ejecutasql($sSQL);
	if ($tabladetalle==false){
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 3035: '.$sSQL.'<br>';}
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		//$sLeyenda=$sSQL;
		}else{
		$registros=$objDB->nf($tabladetalle);
		if ($registros==0){
			return array(utf8_encode($sErrConsulta.'<input id="paginaf3035" name="paginaf3035" type="hidden" value="'.$pagina.'"/><input id="lppf3035" name="lppf3035" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
<td><b>'.$ETI['saiu35idzona'].'</b></td>
<td><b>'.$ETI['saiu35idcentro'].'</b></td>
<td><b>'.$ETI['saiu35activo'].'</b></td>
<td align="right">
'.html_paginador('paginaf3035', $registros, $lineastabla, $pagina, 'paginarf3035()').'
'.html_lpp('lppf3035', $lineastabla, 'paginarf3035()').'
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
		$et_saiu35idzona=$sPrefijo.cadena_notildes($filadet['unad23nombre']).$sSufijo;
		$et_saiu35idcentro=$sPrefijo.cadena_notildes($filadet['unad24nombre']).$sSufijo;
		$et_saiu35activo=$sPrefijo.$filadet['saiu35activo'].$sSufijo;
		if ($bAbierta){
			$sLink='<a href="javascript:cargaridf3035('.$filadet['saiu35id'].')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$res=$res.'<tr'.$sClass.'>
<td>'.$et_saiu35idzona.'</td>
<td>'.$et_saiu35idcentro.'</td>
<td>'.$et_saiu35activo.'</td>
<td>'.$sLink.'</td>
</tr>';
		}
	$res=$res.'</table>';
	$objDB->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
	}
// -- 3035 Cobertura XAJAX 
function f3035_Guardar($valores, $aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$sError='';
	$bDebug=false;
	$sDebug='';
	$bHayDb=false;
	$opts=$aParametros;
	if(!is_array($opts)){$opts=json_decode(str_replace('\"','"',$opts),true);}
	if (isset($opts[99])!=0){if ($opts[99]==1){$bDebug=true;}}
	//if(!is_array($valores)){$datos=json_decode(str_replace('\"','"',$valores),true);}
	//if (isset($datos[0])==0){$datos[0]='';}
	//if ($datos[0]==''){$sError=$ERR[''];}
	if ($sError==''){
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		$bHayDb=true;
		list($sError, $iAccion, $saiu35id, $sDebugGuardar)=f3035_db_Guardar($valores, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugGuardar;
		}
	$objResponse=new xajaxResponse();
	if ($sError==''){
		list($sdetalle, $sDebugTabla)=f3035_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugTabla;
		$objResponse->assign('div_f3035detalle', 'innerHTML', $sdetalle);
		//if ($iAccion==2){
			//$objResponse->call('cargaridf3035('.$saiu35id.')');
			//}else{
			$objResponse->call('limpiaf3035');
			//}
		$objResponse->call("MensajeAlarmaV2('".$ETI['msg_itemguardado']."', 1)");
		}else{
		$objResponse->call("MensajeAlarmaV2('".$sError."', 0)");
		}
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	if ($bHayDb){
		$objDB->CerrarConexion();
		}
	return $objResponse;
	}
function f3035_Traer($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$sError='';
	$bHayDb=false;
	$besta=false;
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$paso=$aParametros[0];
	if ($paso==1){
		$saiu35idbasecon=numeros_validar($aParametros[1]);
		$saiu35idzona=numeros_validar($aParametros[2]);
		$saiu35idcentro=numeros_validar($aParametros[3]);
		if (($saiu35idbasecon!='')&&($saiu35idzona!='')&&($saiu35idcentro!='')){$besta=true;}
		}else{
		$saiu35id=$aParametros[103];
		if ((int)$saiu35id!=0){$besta=true;}
		}
	if ($besta){
		$besta=false;
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		$bHayDb=true;
		$sSQLcondi='';
		if ($paso==1){
			$sSQLcondi=$sSQLcondi.'saiu35idbasecon='.$saiu35idbasecon.' AND saiu35idzona='.$saiu35idzona.' AND saiu35idcentro='.$saiu35idcentro.'';
			}else{
			$sSQLcondi=$sSQLcondi.'saiu35id='.$saiu35id.'';
			}
		$sSQL='SELECT * FROM saiu35cobertura WHERE '.$sSQLcondi;
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$fila=$objDB->sf($tabla);
			$besta=true;
			}
		}
	$objResponse=new xajaxResponse();
	if ($besta){
		if (isset($APP->piel)==0){$APP->piel=1;}
		$iPiel=$APP->piel;
		list($saiu35idzona_nombre, $serror_det)=tabla_campoxid('unad23zona','unad23nombre','unad23id', $fila['saiu35idzona'],'{'.$ETI['msg_sindato'].'}', $objDB);
		$html_saiu35idzona=html_oculto('saiu35idzona', $fila['saiu35idzona'], $saiu35idzona_nombre);
		$objResponse->assign('div_saiu35idzona', 'innerHTML', $html_saiu35idzona);
		list($saiu35idcentro_nombre, $serror_det)=tabla_campoxid('unad24sede','unad24nombre','unad24id', $fila['saiu35idcentro'],'{'.$ETI['msg_sindato'].'}', $objDB);
		$html_saiu35idcentro=html_oculto('saiu35idcentro', $fila['saiu35idcentro'], $saiu35idcentro_nombre);
		$objResponse->assign('div_saiu35idcentro', 'innerHTML', $html_saiu35idcentro);
		$saiu35id_nombre='';
		$html_saiu35id=html_oculto('saiu35id', $fila['saiu35id'], $saiu35id_nombre);
		$objResponse->assign('div_saiu35id', 'innerHTML', $html_saiu35id);
		$objResponse->assign('saiu35activo', 'value', $fila['saiu35activo']);
		$objResponse->call("MensajeAlarmaV2('', 0)");
		$objResponse->call("verboton('belimina3035','block')");
		}else{
		if ($paso==1){
			$objResponse->assign('saiu35idzona', 'value', $saiu35idzona);
			$objResponse->assign('saiu35idcentro', 'value', $saiu35idcentro);
			}else{
			$objResponse->call('MensajeAlarmaV2("No se encontro el registro de referencia:'.$saiu35id.'", 0)');
			}
		}
	if ($bHayDb){
		$objDB->CerrarConexion();
		}
	return $objResponse;
	}
function f3035_Eliminar($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$sError='';
	$iTipoError=0;
	$bDebug=false;
	$sDebug='';
	$opts=$aParametros;
	if(!is_array($opts)){$opts=json_decode(str_replace('\"','"',$opts),true);}
	if (isset($opts[99])!=0){if ($opts[99]==1){$bDebug=true;}}
	//if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	list($sError, $sDebugElimina)=f3035_db_Eliminar($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugElimina;
	$objResponse=new xajaxResponse();
	if ($sError==''){
		list($sDetalle, $sDebugTabla)=f3035_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugTabla;
		$objResponse->assign('div_f3035detalle', 'innerHTML', $sDetalle);
		$objResponse->call('limpiaf3035');
		$sError=$ETI['msg_itemeliminado'];
		$iTipoError=1;
		}
	$objResponse->call("MensajeAlarmaV2('".$sError."', ".$iTipoError.")");
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	$objDB->CerrarConexion();
	return $objResponse;
	}
function f3035_HtmlTabla($aParametros){
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
	list($sDetalle, $sDebugTabla)=f3035_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f3035detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f3035_PintarLlaves($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	//if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	if (isset($APP->piel)==0){$APP->piel=1;}
	$iPiel=$APP->piel;
	$objCombos=new clsHtmlCombos();
	$html_saiu35idzona=f3035_HTMLComboV2_saiu35idzona($objDB, $objCombos, 0);
	$html_saiu35idcentro=f3035_HTMLComboV2_saiu35idcentro($objDB, $objCombos, 0);
	$html_saiu35id='<input id="saiu35id" name="saiu35id" type="hidden" value=""/>';
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_saiu35idzona','innerHTML', $html_saiu35idzona);
	$objResponse->assign('div_saiu35idcentro','innerHTML', $html_saiu35idcentro);
	$objResponse->assign('div_saiu35id','innerHTML', $html_saiu35id);
	return $objResponse;
	}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------
?>