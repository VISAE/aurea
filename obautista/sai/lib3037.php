<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2020 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.25.4 martes, 4 de agosto de 2020
--- 3037 Temas relativos
*/
function f3037_HTMLComboV2_saiu37idbaserel($objDB, $objCombos, $valor){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('saiu37idbaserel', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	//$objCombos->iAncho=450;
	$objCombos->sAccion='revisaf3037()';
	$sSQL='SELECT saiu31id AS id, saiu31titulo AS nombre FROM saiu31baseconocimiento';
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f3037_db_Guardar($valores, $objDB, $bDebug=false){
	$iCodModulo=3037;
	$bAudita[2]=false;
	$bAudita[3]=false;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3037='lg/lg_3037_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3037)){$mensajes_3037='lg/lg_3037_es.php';}
	require $mensajes_todas;
	require $mensajes_3037;
	$sError='';
	$sDebug='';
	$bInserta=false;
	$iAccion=3;
	if(!is_array($valores)){$valores=json_decode(str_replace('\"','"',$valores),true);}
	$saiu37idbasecon=numeros_validar($valores[1]);
	$saiu37idbaserel=numeros_validar($valores[2]);
	$saiu37id=numeros_validar($valores[3], true);
	$saiu37porcentaje=numeros_validar($valores[4]);
	$saiu37porcpalclave=numeros_validar($valores[5]);
	//if ($saiu37porcentaje==''){$saiu37porcentaje=0;}
	//if ($saiu37porcpalclave==''){$saiu37porcpalclave=0;}
	$sSepara=', ';
	if ($saiu37porcpalclave==''){$sError=$ERR['saiu37porcpalclave'].$sSepara.$sError;}
	if ($saiu37porcentaje==''){$sError=$ERR['saiu37porcentaje'].$sSepara.$sError;}
	//if ($saiu37id==''){$sError=$ERR['saiu37id'].$sSepara.$sError;}//CONSECUTIVO
	if ($saiu37idbaserel==''){$sError=$ERR['saiu37idbaserel'].$sSepara.$sError;}
	if ($saiu37idbasecon==''){$sError=$ERR['saiu37idbasecon'].$sSepara.$sError;}
	if ($sError==''){
		if ((int)$saiu37id==0){
			if ($sError==''){
				$sSQL='SELECT saiu37idbasecon FROM saiu37baserelativos WHERE saiu37idbasecon='.$saiu37idbasecon.' AND saiu37idbaserel='.$saiu37idbaserel.'';
				$result=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($result)!=0){
					$sError=$ERR['existe'];
					}else{
					if (!seg_revisa_permiso($iCodModulo, 2, $objDB)){$sError=$ERR['2'];}
					}
				}
			if ($sError==''){
				$saiu37id=tabla_consecutivo('saiu37baserelativos', 'saiu37id', '', $objDB);
				if ($saiu37id==-1){$sError=$objDB->serror;}
				$bInserta=true;
				$iAccion=2;
				}
			}else{
			if (!seg_revisa_permiso($iCodModulo, 3, $objDB)){$sError=$ERR['3'];}
			}
		}
	if ($sError==''){
		if ($bInserta){
			$saiu37porcpalclave=0;
			//$sSQL='SELECT Campo FROM Tabla WHERE Id='.$sValorId;
			//$tabla=$objDB->ejecutasql($sSQL);
			//if ($objDB->nf($tabla)>0){
				//$fila=$objDB->sf($tabla);
				//$sCampo=$fila['sCampo'];
				//}
			$sError='INFORMACION PARA EL ADMINISTRADOR DEL SISTEMA: No ha definido valores para los campos ocultos.';
			}
		}
	if ($sError==''){
		if ($bInserta){
			$sCampos3037='saiu37idbasecon, saiu37idbaserel, saiu37id, saiu37porcentaje, saiu37porcpalclave';
			$sValores3037=''.$saiu37idbasecon.', '.$saiu37idbaserel.', '.$saiu37id.', '.$saiu37porcentaje.', '.$saiu37porcpalclave.'';
			if ($APP->utf8==1){
				$sSQL='INSERT INTO saiu37baserelativos ('.$sCampos3037.') VALUES ('.utf8_encode($sValores3037).');';
				}else{
				$sSQL='INSERT INTO saiu37baserelativos ('.$sCampos3037.') VALUES ('.$sValores3037.');';
				}
			if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Guardar 3037 '.$sSQL.'<br>';}
			$result=$objDB->ejecutasql($sSQL);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' [3037].<!-- '.$sSQL.' -->';
				}else{
				if ($bAudita[2]){
					seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 2, $saiu37id, $sSQL, $objDB);
					}
				}
			}else{
			$scampo3037[1]='saiu37porcentaje';
			$svr3037[1]=$saiu37porcentaje;
			$inumcampos=1;
			$sWhere='saiu37id='.$saiu37id.'';
			//$sWhere='saiu37idbasecon='.$saiu37idbasecon.' AND saiu37idbaserel='.$saiu37idbaserel.'';
			$sSQL='SELECT * FROM saiu37baserelativos WHERE '.$sWhere;
			$sdatos='';
			$bpasa=false;
			$result=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($result)>0){
				$filaorigen=$objDB->sf($result);
				for ($k=1;$k<=$inumcampos;$k++){
					if ($filaorigen[$scampo3037[$k]]!=$svr3037[$k]){
						if ($sdatos!=''){$sdatos=$sdatos.', ';}
						$sdatos=$sdatos.$scampo3037[$k].'="'.$svr3037[$k].'"';
						$bpasa=true;
						}
					}
				}
			if ($bpasa){
				if ($APP->utf8==1){
					$sSQL='UPDATE saiu37baserelativos SET '.utf8_encode($sdatos).' WHERE '.$sWhere.';';
					}else{
					$sSQL='UPDATE saiu37baserelativos SET '.$sdatos.' WHERE '.$sWhere.';';
					}
				$result=$objDB->ejecutasql($sSQL);
				if ($result==false){
					$sError=$ERR['falla_guardar'].' {Temas relativos}. <!-- '.$sSQL.' -->';
					}else{
					if ($bAudita[3]){
						seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 3, $saiu37id, $sSQL, $objDB);
						}
					}
				}
			}
		}
	return array($sError, $iAccion, $saiu37id, $sDebug);
	}
function f3037_db_Eliminar($aParametros, $objDB, $bDebug=false){
	$iCodModulo=3037;
	$bAudita[4]=false;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3037='lg/lg_3037_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3037)){$mensajes_3037='lg/lg_3037_es.php';}
	require $mensajes_todas;
	require $mensajes_3037;
	$sError='';
	$sDebug='';
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$saiu37idbasecon=numeros_validar($aParametros[1]);
	$saiu37idbaserel=numeros_validar($aParametros[2]);
	$saiu37id=numeros_validar($aParametros[3]);
	if ($sError==''){
		if (!seg_revisa_permiso($iCodModulo, 4, $objDB)){
			$sError=$ERR['4'];
			}
		}
	if ($sError==''){
		$sSQL='SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=3037';
		$tablaor=$objDB->ejecutasql($sSQL);
		while ($filaor=$objDB->sf($tablaor)){
			$sSQL='SELECT '.$filaor['campoid'].' FROM '.$filaor['tabla'].' WHERE '.$filaor['camporev'].'='.$saiu37id.' LIMIT 0, 1';
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
		$sWhere='saiu37id='.$saiu37id.'';
		//$sWhere='saiu37idbasecon='.$saiu37idbasecon.' AND saiu37idbaserel='.$saiu37idbaserel.'';
		$sSQL='DELETE FROM saiu37baserelativos WHERE '.$sWhere.';';
		$result=$objDB->ejecutasql($sSQL);
		if ($result==false){
			$sError=$ERR['falla_eliminar'].' {3037 Temas relativos}.<!-- '.$sSQL.' -->';
			}else{
			if ($bAudita[4]){
				seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $saiu37id, $sSQL, $objDB);
				}
			}
		}
	return array($sError, $sDebug);
	}
function f3037_TablaDetalleV2($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3037='lg/lg_3037_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3037)){$mensajes_3037='lg/lg_3037_es.php';}
	require $mensajes_todas;
	require $mensajes_3037;
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
	$bAbierta=false;
	$sSQL='SELECT saiu31estado FROM saiu31baseconocimiento WHERE saiu31id='.$saiu31id;
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		if ($fila['saiu31estado']!='S'){$bAbierta=true;}
		}
	$sLeyenda='';
	if ($sLeyenda!=''){
		$sLeyenda='<div class="salto1px"></div>
<div class="GrupoCamposAyuda">
'.$sLeyenda.'
<div class="salto1px"></div>
</div>';
		return array($sLeyenda.'<input id="paginaf3037" name="paginaf3037" type="hidden" value="'.$pagina.'"/><input id="lppf3037" name="lppf3037" type="hidden" value="'.$lineastabla.'"/>', $sDebug);
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
	$sTitulos='Basecon, Baserel, Id, Porcentaje, Porcpalclave';
	$sSQL='SELECT TB.saiu37idbasecon, T2.saiu31titulo, TB.saiu37id, TB.saiu37porcentaje, TB.saiu37porcpalclave, TB.saiu37idbaserel 
FROM saiu37baserelativos AS TB, saiu31baseconocimiento AS T2 
WHERE '.$sSQLadd1.' TB.saiu37idbasecon='.$saiu31id.' AND TB.saiu37idbaserel=T2.saiu31id '.$sSQLadd.'
ORDER BY TB.saiu37idbaserel';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_3037" name="consulta_3037" type="hidden" value="'.$sSQLlista.'"/>
<input id="titulos_3037" name="titulos_3037" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objDB->ejecutasql($sSQL);
	if ($tabladetalle==false){
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 3037: '.$sSQL.'<br>';}
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		//$sLeyenda=$sSQL;
		}else{
		$registros=$objDB->nf($tabladetalle);
		if ($registros==0){
			//return array(utf8_encode($sErrConsulta.'<input id="paginaf3037" name="paginaf3037" type="hidden" value="'.$pagina.'"/><input id="lppf3037" name="lppf3037" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
<td><b>'.$ETI['saiu37idbaserel'].'</b></td>
<td><b>'.$ETI['saiu37porcentaje'].'</b></td>
<td><b>'.$ETI['saiu37porcpalclave'].'</b></td>
<td align="right">
'.html_paginador('paginaf3037', $registros, $lineastabla, $pagina, 'paginarf3037()').'
'.html_lpp('lppf3037', $lineastabla, 'paginarf3037()', 500).'
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
		$et_saiu37idbaserel=$sPrefijo.cadena_notildes($filadet['saiu31titulo']).$sSufijo;
		$et_saiu37porcentaje=$sPrefijo.$filadet['saiu37porcentaje'].$sSufijo;
		$et_saiu37porcpalclave=$sPrefijo.$filadet['saiu37porcpalclave'].$sSufijo;
		if ($bAbierta){
			$et_saiu37idbaserel=html_combo('saiu37idbaserel_'.$filadet['saiu37id'], 'saiu31id', 'saiu31titulo', 'saiu31baseconocimiento', '', 'saiu31titulo', $filadet['saiu37idbaserel'], $objDB, 'cuadricula3037(2, '.$filadet['saiu37id'].', this.value)', true, '{'.$ETI['msg_ninguna'].'}', '0');
			$et_saiu37porcentaje='<input id="saiu37porcentaje_'.$filadet['saiu37id'].'" name="saiu37porcentaje_'.$filadet['saiu37id'].'" type="text" value="'.$filadet['saiu37porcentaje'].'" onchange="cuadricula3037(4, '.$filadet['saiu37id'].', this.value)"/>';
			$et_saiu37porcpalclave='<input id="saiu37porcpalclave_'.$filadet['saiu37id'].'" name="saiu37porcpalclave_'.$filadet['saiu37id'].'" type="text" value="'.$filadet['saiu37porcpalclave'].'" onchange="cuadricula3037(5, '.$filadet['saiu37id'].', this.value)"/>';
			$sLink='<input id="btMenos3037_saiu37id" name="btMenos3037_saiu37id" type="button" value="Retirar" class="btMiniMenos" onclick="menosuno3037('.$filadet['saiu37id'].');" title="Retirar fila"/>';
			}
		$res=$res.'<tr'.$sClass.'>
<td>'.$et_saiu37idbaserel.'</td>
<td>'.$et_saiu37porcentaje.'</td>
<td>'.$et_saiu37porcpalclave.'</td>
<td>'.$sLink.'</td>
</tr>';
		}
	$res=$res.'</table>';
	$objDB->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
	}
// -- 3037 Temas relativos XAJAX 
function f3037_Guardar($valores, $aParametros){
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
		list($sError, $iAccion, $saiu37id, $sDebugGuardar)=f3037_db_Guardar($valores, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugGuardar;
		}
	$objResponse=new xajaxResponse();
	if ($sError==''){
		list($sdetalle, $sDebugTabla)=f3037_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugTabla;
		$objResponse->assign('div_f3037detalle', 'innerHTML', $sdetalle);
		//if ($iAccion==2){
			//$objResponse->call('cargaridf3037('.$saiu37id.')');
			//}else{
			$objResponse->call('limpiaf3037');
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
function f3037_Traer($aParametros){
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
		$saiu37idbasecon=numeros_validar($aParametros[1]);
		$saiu37idbaserel=numeros_validar($aParametros[2]);
		if (($saiu37idbasecon!='')&&($saiu37idbaserel!='')){$besta=true;}
		}else{
		$saiu37id=$aParametros[103];
		if ((int)$saiu37id!=0){$besta=true;}
		}
	if ($besta){
		$besta=false;
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		$bHayDb=true;
		$sSQLcondi='';
		if ($paso==1){
			$sSQLcondi=$sSQLcondi.'saiu37idbasecon='.$saiu37idbasecon.' AND saiu37idbaserel='.$saiu37idbaserel.'';
			}else{
			$sSQLcondi=$sSQLcondi.'saiu37id='.$saiu37id.'';
			}
		$sSQL='SELECT * FROM saiu37baserelativos WHERE '.$sSQLcondi;
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
		list($saiu37idbaserel_nombre, $serror_det)=tabla_campoxid('saiu31baseconocimiento','saiu31titulo','saiu31id', $fila['saiu37idbaserel'],'{'.$ETI['msg_sindato'].'}', $objDB);
		$html_saiu37idbaserel=html_oculto('saiu37idbaserel', $fila['saiu37idbaserel'], $saiu37idbaserel_nombre);
		$objResponse->assign('div_saiu37idbaserel', 'innerHTML', $html_saiu37idbaserel);
		$saiu37id_nombre='';
		$html_saiu37id=html_oculto('saiu37id', $fila['saiu37id'], $saiu37id_nombre);
		$objResponse->assign('div_saiu37id', 'innerHTML', $html_saiu37id);
		$objResponse->assign('saiu37porcentaje', 'value', $fila['saiu37porcentaje']);
		$saiu37porcpalclave_eti=$fila['saiu37porcpalclave'];
		$html_saiu37porcpalclave=html_oculto('saiu37porcpalclave', $fila['saiu37porcpalclave'], $saiu37porcpalclave_eti);
		$objResponse->assign('div_saiu37porcpalclave', 'innerHTML', $html_saiu37porcpalclave);
		$objResponse->call("MensajeAlarmaV2('', 0)");
		$objResponse->call("verboton('belimina3037','block')");
		}else{
		if ($paso==1){
			$objResponse->assign('saiu37idbaserel', 'value', $saiu37idbaserel);
			}else{
			$objResponse->call('MensajeAlarmaV2("No se encontro el registro de referencia:'.$saiu37id.'", 0)');
			}
		}
	if ($bHayDb){
		$objDB->CerrarConexion();
		}
	return $objResponse;
	}
function f3037_Eliminar($aParametros){
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
	list($sError, $sDebugElimina)=f3037_db_Eliminar($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugElimina;
	$objResponse=new xajaxResponse();
	if ($sError==''){
		list($sDetalle, $sDebugTabla)=f3037_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugTabla;
		$objResponse->assign('div_f3037detalle', 'innerHTML', $sDetalle);
		$objResponse->call('limpiaf3037');
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
function f3037_HtmlTabla($aParametros){
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
	list($sDetalle, $sDebugTabla)=f3037_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f3037detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f3037_PintarLlaves($aParametros){
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
	$html_saiu37idbaserel=f3037_HTMLComboV2_saiu37idbaserel($objDB, $objCombos, 0);
	$html_saiu37id='<input id="saiu37id" name="saiu37id" type="hidden" value=""/>';
	$html_saiu37porcpalclave=html_oculto('saiu37porcpalclave', '');
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_saiu37idbaserel','innerHTML', $html_saiu37idbaserel);
	$objResponse->assign('div_saiu37id','innerHTML', $html_saiu37id);
	$objResponse->assign('div_saiu37porcpalclave','innerHTML', $html_saiu37porcpalclave);
	return $objResponse;
	}
function f3037_MasUno($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	$sError='';
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$saiu37idbasecon=$aParametros[1];
	$saiu37idbaserel=$aParametros[2];
	$saiu37id=tabla_consecutivo('saiu37baserelativos', 'saiu37id', '', $objDB);
	$sCampos='saiu37idbasecon, saiu37idbaserel, saiu37id, saiu37porcentaje, saiu37porcpalclave';
	$sValores=''.$saiu37idbasecon.', '.$saiu37idbaserel.', '.$saiu37id.', 0, 0';
	$sSQL='INSERT INTO saiu37baserelativos ('.$sCampos.') VALUES ('.$sValores.');';
	$result=$objDB->ejecutasql($sSQL);
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->call('paginarf3037');
	$objResponse->call('MensajeAlarmaV2("Se ha agregado una fila al proceso", 1)');
	return $objResponse;
	}
function f3037_MenosUno($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	$sError='';
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$idPadre=$aParametros[1];
	$saiu37id=$aParametros[2];
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$sSQL='DELETE FROM saiu37baserelativos WHERE saiu37id='.$saiu37id.';';
	$result=$objDB->ejecutasql($sSQL);
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->call('paginarf3037');
	$objResponse->call("MensajeAlarmaV2('Se ha retirado la fila {Ref: ".$saiu37id."}', 1)");
	return $objResponse;
	}
function f3037_GuardarCuadro($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	$sError='';
	$sDebug='';
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$idcampo=$aParametros[1];
	$saiu37id=$aParametros[2];
	$valor=htmlspecialchars($aParametros[3]);
	$sValor2=htmlspecialchars($aParametros[4]);
	$origen=$aParametros[3];
	//$idPadre=$aParametros[4];
	$sCampo='';
	$iTipoError=0;
	switch($idcampo){
		case 4:
		$sCampo='saiu37porcentaje';
		$valor=numeros_validar($valor);
		if ($valor==''){$valor=0;}
		break;
		case 5:
		$sCampo='saiu37porcpalclave';
		$valor=numeros_validar($valor);
		if ($valor==''){$valor=0;}
		break;
		}
	$bPaginar=false;
	if ($sError==''){
		switch($sCampo){
			case 'saiu37porcentaje':
			case 'saiu37porcpalclave':
			$sSQL='UPDATE saiu37baserelativos SET '.$sCampo.'="'.$valor.'" WHERE saiu37id='.$saiu37id.';';
			$result=$objDB->ejecutasql($sSQL);
			break;
			case '':
			$sError='No se ha encontrado el campo '.$idcampo;
			break;
			}
		}
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	//$objResponse->call("MensajeAlarmaV2('".$sError."', ".$iTipoError.")");
	if (($sError=='')){
		if ($bPaginar){
			$objResponse->call('paginarf3037');
			}else{
			//$objResponse->call('MensajeAlarmaV2("Se ha acualizado el valor en la lista {'.$saiu37idbasecon.'}", 1)');
			$objResponse->assign('div_fila'.$saiu37id, 'innerHTML', '<span class="verde">Guardado</span>');
			if ($origen===$valor){
				}else{
				$objResponse->assign($sCampo.'_'.$saiu37id, 'value', $valor);
				}
			}
		}else{
		$objResponse->assign('div_fila'.$saiu37id, 'innerHTML', '<span class="rojo">'.$sError.'</span>');
		}
	return $objResponse;
	}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------
?>