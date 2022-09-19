<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2021 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.25.10c jueves, 6 de mayo de 2021
--- 3062 Notificados
*/
function f3062_HTMLComboV2_saiu62idprograma($objDB, $objCombos, $valor, $vrsaiu62idescuela){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('saiu62idprograma', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	//$objCombos->iAncho=450;
	$sSQL='';
	if ((int)$vrsaiu62idescuela!=0){
		$sSQL='SELECT core09id AS id, core09nombre AS nombre FROM core09programa WHERE core09idescuela='.$vrsaiu62idescuela.'';
		}
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f3062_Combosaiu62idprograma($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$objCombos=new clsHtmlCombos();
	$html_saiu62idprograma=f3062_HTMLComboV2_saiu62idprograma($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_saiu62idprograma', 'innerHTML', $html_saiu62idprograma);
	//$objResponse->call('$("#saiu62idprograma").chosen()');
	return $objResponse;
	}
function f3062_HTMLComboV2_saiu62idcentro($objDB, $objCombos, $valor, $vrsaiu62idzona){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('saiu62idcentro', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	//$objCombos->iAncho=450;
	$sSQL='';
	if ((int)$vrsaiu62idzona!=0){
		$sSQL='SELECT unad24id AS id, unad24nombre AS nombre FROM unad24sede WHERE unad24idzona='.$vrsaiu62idzona.'';
		}
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f3062_Combosaiu62idcentro($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$objCombos=new clsHtmlCombos();
	$html_saiu62idcentro=f3062_HTMLComboV2_saiu62idcentro($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_saiu62idcentro', 'innerHTML', $html_saiu62idcentro);
	//$objResponse->call('$("#saiu62idcentro").chosen()');
	return $objResponse;
	}
function f3062_db_Guardar($valores, $objDB, $bDebug=false){
	$iCodModulo=3062;
	$bAudita[2]=false;
	$bAudita[3]=false;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3062='lg/lg_3062_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3062)){$mensajes_3062='lg/lg_3062_es.php';}
	require $mensajes_todas;
	require $mensajes_3062;
	$sError='';
	$sDebug='';
	$bInserta=false;
	$iAccion=3;
	if(!is_array($valores)){$valores=json_decode(str_replace('\"','"',$valores),true);}
	$saiu62idcomunicado=numeros_validar($valores[1]);
	$saiu62idtercero=numeros_validar($valores[2]);
	$saiu62id=numeros_validar($valores[3], true);
	$saiu62idperiodo=numeros_validar($valores[4]);
	$saiu62idescuela=numeros_validar($valores[5]);
	$saiu62idprograma=numeros_validar($valores[6]);
	$saiu62idzona=numeros_validar($valores[7]);
	$saiu62idcentro=numeros_validar($valores[8]);
	$saiu62estado=numeros_validar($valores[9]);
	$saiu62fecha=$valores[10];
	$saiu62fhora=numeros_validar($valores[11]);
	$saiu62min=numeros_validar($valores[12]);
	$saiu62mailenviado=numeros_validar($valores[13]);
	//if ($saiu62idperiodo==''){$saiu62idperiodo=0;}
	//if ($saiu62idescuela==''){$saiu62idescuela=0;}
	//if ($saiu62idprograma==''){$saiu62idprograma=0;}
	//if ($saiu62idzona==''){$saiu62idzona=0;}
	//if ($saiu62idcentro==''){$saiu62idcentro=0;}
	//if ($saiu62estado==''){$saiu62estado=0;}
	//if ($saiu62fhora==''){$saiu62fhora=0;}
	//if ($saiu62min==''){$saiu62min=0;}
	//if ($saiu62mailenviado==''){$saiu62mailenviado=0;}
	$sSepara=', ';
	if ($saiu62mailenviado==''){$sError=$ERR['saiu62mailenviado'].$sSepara.$sError;}
	if ($saiu62min==''){$sError=$ERR['saiu62min'].$sSepara.$sError;}
	if ($saiu62fhora==''){$sError=$ERR['saiu62fhora'].$sSepara.$sError;}
	if ($saiu62fecha==0){
		//$saiu62fecha=fecha_DiaMod();
		$sError=$ERR['saiu62fecha'].$sSepara.$sError;
		}
	if ($saiu62estado==''){$sError=$ERR['saiu62estado'].$sSepara.$sError;}
	if ($saiu62idcentro==''){$sError=$ERR['saiu62idcentro'].$sSepara.$sError;}
	if ($saiu62idzona==''){$sError=$ERR['saiu62idzona'].$sSepara.$sError;}
	if ($saiu62idprograma==''){$sError=$ERR['saiu62idprograma'].$sSepara.$sError;}
	if ($saiu62idescuela==''){$sError=$ERR['saiu62idescuela'].$sSepara.$sError;}
	if ($saiu62idperiodo==''){$sError=$ERR['saiu62idperiodo'].$sSepara.$sError;}
	//if ($saiu62id==''){$sError=$ERR['saiu62id'].$sSepara.$sError;}//CONSECUTIVO
	if ($saiu62idtercero==0){$sError=$ERR['saiu62idtercero'].$sSepara.$sError;}
	if ($saiu62idcomunicado==''){$sError=$ERR['saiu62idcomunicado'].$sSepara.$sError;}
	if ($sError==''){
		list($sError, $sInfo)=tercero_Bloqueado($saiu62idtercero, $objDB);
		if ($sInfo!=''){$sError=$sError.'<br>'.$sInfo;}
		}
	if ($sError==''){
		if ((int)$saiu62id==0){
			if ($sError==''){
				$sSQL='SELECT saiu62idcomunicado FROM saiu62comunicadonoti WHERE saiu62idcomunicado='.$saiu62idcomunicado.' AND saiu62idtercero="'.$saiu62idtercero.'"';
				$result=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($result)!=0){
					$sError=$ERR['existe'];
					}else{
					if (!seg_revisa_permiso($iCodModulo, 2, $objDB)){$sError=$ERR['2'];}
					}
				}
			if ($sError==''){
				$saiu62id=tabla_consecutivo('saiu62comunicadonoti', 'saiu62id', '', $objDB);
				if ($saiu62id==-1){$sError=$objDB->serror;}
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
			$sCampos3062='saiu62idcomunicado, saiu62idtercero, saiu62id, saiu62idperiodo, saiu62idescuela, 
			saiu62idprograma, saiu62idzona, saiu62idcentro, saiu62estado, saiu62fecha, 
			saiu62fhora, saiu62min, saiu62mailenviado';
			$sValores3062=''.$saiu62idcomunicado.', "'.$saiu62idtercero.'", '.$saiu62id.', '.$saiu62idperiodo.', '.$saiu62idescuela.', 
			'.$saiu62idprograma.', '.$saiu62idzona.', '.$saiu62idcentro.', '.$saiu62estado.', "'.$saiu62fecha.'", 
			'.$saiu62fhora.', '.$saiu62min.', '.$saiu62mailenviado.'';
			if ($APP->utf8==1){
				$sSQL='INSERT INTO saiu62comunicadonoti ('.$sCampos3062.') VALUES ('.utf8_encode($sValores3062).');';
				}else{
				$sSQL='INSERT INTO saiu62comunicadonoti ('.$sCampos3062.') VALUES ('.$sValores3062.');';
				}
			if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Guardar 3062 '.$sSQL.'<br>';}
			$result=$objDB->ejecutasql($sSQL);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' [3062].<!-- '.$sSQL.' -->';
				}else{
				if ($bAudita[2]){
					seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 2, $saiu62id, $sSQL, $objDB);
					}
				}
			}else{
			$scampo3062[1]='saiu62idperiodo';
			$scampo3062[2]='saiu62idescuela';
			$scampo3062[3]='saiu62idprograma';
			$scampo3062[4]='saiu62idzona';
			$scampo3062[5]='saiu62idcentro';
			$scampo3062[6]='saiu62estado';
			$scampo3062[7]='saiu62fecha';
			$scampo3062[8]='saiu62fhora';
			$scampo3062[9]='saiu62min';
			$scampo3062[10]='saiu62mailenviado';
			$svr3062[1]=$saiu62idperiodo;
			$svr3062[2]=$saiu62idescuela;
			$svr3062[3]=$saiu62idprograma;
			$svr3062[4]=$saiu62idzona;
			$svr3062[5]=$saiu62idcentro;
			$svr3062[6]=$saiu62estado;
			$svr3062[7]=$saiu62fecha;
			$svr3062[8]=$saiu62fhora;
			$svr3062[9]=$saiu62min;
			$svr3062[10]=$saiu62mailenviado;
			$inumcampos=10;
			$sWhere='saiu62id='.$saiu62id.'';
			//$sWhere='saiu62idcomunicado='.$saiu62idcomunicado.' AND saiu62idtercero="'.$saiu62idtercero.'"';
			$sSQL='SELECT * FROM saiu62comunicadonoti WHERE '.$sWhere;
			$sdatos='';
			$bpasa=false;
			$result=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($result)>0){
				$filaorigen=$objDB->sf($result);
				for ($k=1;$k<=$inumcampos;$k++){
					if ($filaorigen[$scampo3062[$k]]!=$svr3062[$k]){
						if ($sdatos!=''){$sdatos=$sdatos.', ';}
						$sdatos=$sdatos.$scampo3062[$k].'="'.$svr3062[$k].'"';
						$bpasa=true;
						}
					}
				}
			if ($bpasa){
				if ($APP->utf8==1){
					$sSQL='UPDATE saiu62comunicadonoti SET '.utf8_encode($sdatos).' WHERE '.$sWhere.';';
					}else{
					$sSQL='UPDATE saiu62comunicadonoti SET '.$sdatos.' WHERE '.$sWhere.';';
					}
				$result=$objDB->ejecutasql($sSQL);
				if ($result==false){
					$sError=$ERR['falla_guardar'].' {Notificados}. <!-- '.$sSQL.' -->';
					}else{
					if ($bAudita[3]){
						seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 3, $saiu62id, $sSQL, $objDB);
						}
					}
				}
			}
		}
	return array($sError, $iAccion, $saiu62id, $sDebug);
	}
function f3062_db_Eliminar($aParametros, $objDB, $bDebug=false){
	$iCodModulo=3062;
	$bAudita[4]=false;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3062='lg/lg_3062_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3062)){$mensajes_3062='lg/lg_3062_es.php';}
	require $mensajes_todas;
	require $mensajes_3062;
	$sError='';
	$sDebug='';
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$saiu62idcomunicado=numeros_validar($aParametros[1]);
	$saiu62idtercero=numeros_validar($aParametros[2]);
	$saiu62id=numeros_validar($aParametros[3]);
	if ($sError==''){
		if (!seg_revisa_permiso($iCodModulo, 4, $objDB)){
			$sError=$ERR['4'];
			}
		}
	if ($sError==''){
		$sSQL='SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=3062';
		$tablaor=$objDB->ejecutasql($sSQL);
		while ($filaor=$objDB->sf($tablaor)){
			$sSQL='SELECT '.$filaor['campoid'].' FROM '.$filaor['tabla'].' WHERE '.$filaor['camporev'].'='.$saiu62id.' LIMIT 0, 1';
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
		$sWhere='saiu62id='.$saiu62id.'';
		//$sWhere='saiu62idcomunicado='.$saiu62idcomunicado.' AND saiu62idtercero="'.$saiu62idtercero.'"';
		$sSQL='DELETE FROM saiu62comunicadonoti WHERE '.$sWhere.';';
		$result=$objDB->ejecutasql($sSQL);
		if ($result==false){
			$sError=$ERR['falla_eliminar'].' {3062 Notificados}.<!-- '.$sSQL.' -->';
			}else{
			if ($bAudita[4]){
				seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $saiu62id, $sSQL, $objDB);
				}
			}
		}
	return array($sError, $sDebug);
	}
function f3062_TablaDetalleV2($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3062='lg/lg_3062_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3062)){$mensajes_3062='lg/lg_3062_es.php';}
	require $mensajes_todas;
	require $mensajes_3062;
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
	$saiu61id=$aParametros[0];
	$pagina=$aParametros[101];
	$lineastabla=$aParametros[102];
	$bAbierta=true;
	//$sSQL='SELECT Campo FROM saiu61comunicados WHERE saiu61id='.$saiu61id;
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
		return array($sLeyenda.'<input id="paginaf3062" name="paginaf3062" type="hidden" value="'.$pagina.'"/><input id="lppf3062" name="lppf3062" type="hidden" value="'.$lineastabla.'"/>', $sDebug);
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
	$sInfoAvance='';
	$sSQL='SELECT TB.saiu62estado, T9.saiu59nombre, COUNT(1) AS Total
	FROM saiu62comunicadonoti AS TB, saiu59estadocomunica AS T9
	WHERE TB.saiu62idcomunicado='.$saiu61id.' AND TB.saiu62estado=T9.saiu59id 
	GROUP BY TB.saiu62estado, T9.saiu59nombre
	ORDER BY TB.saiu62estado';
	$tabla=$objDB->ejecutasql($sSQL);
	while($fila=$objDB->sf($tabla)){
		if ($sInfoAvance!=''){$sInfoAvance=$sInfoAvance.', ';}
		$sInfoAvance=$sInfoAvance.cadena_notildes($fila['saiu59nombre']).': <b>'.formato_numero($fila['Total']).'</b>';
		}
	$sTitulos='Comunicado, Tercero, Id, Periodo, Escuela, Programa, Zona, Centro, Estado, Fecha, Fhora, Min, Mailenviado';
	$sSQL='SELECT TB.saiu62idcomunicado, T2.unad11razonsocial AS C2_nombre, TB.saiu62id, 
	T9.saiu59nombre, TB.saiu62fecha, TB.saiu62fhora, TB.saiu62min, 
	TB.saiu62mailenviado, TB.saiu62idtercero, T2.unad11tipodoc AS C2_td, T2.unad11doc AS C2_doc, TB.saiu62idperiodo, 
	TB.saiu62idescuela, TB.saiu62idprograma, TB.saiu62idzona, TB.saiu62idcentro, TB.saiu62estado 
	FROM saiu62comunicadonoti AS TB, unad11terceros AS T2, saiu59estadocomunica AS T9 
	WHERE '.$sSQLadd1.' TB.saiu62idcomunicado='.$saiu61id.' AND TB.saiu62idtercero=T2.unad11id 
	AND TB.saiu62estado=T9.saiu59id '.$sSQLadd.'
	ORDER BY TB.saiu62estado, T2.unad11doc';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_3062" name="consulta_3062" type="hidden" value="'.$sSQLlista.'"/>
	<input id="titulos_3062" name="titulos_3062" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objDB->ejecutasql($sSQL);
	if ($tabladetalle==false){
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 3062: '.$sSQL.'<br>';}
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		//$sLeyenda=$sSQL;
		}else{
		$registros=$objDB->nf($tabladetalle);
		if ($registros==0){
			return array(utf8_encode($sErrConsulta.'<input id="paginaf3062" name="paginaf3062" type="hidden" value="'.$pagina.'"/><input id="lppf3062" name="lppf3062" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
	<td colspan="2"><b>'.$ETI['saiu62idtercero'].'</b></td>
	<td><b>'.$ETI['saiu62estado'].'</b></td>
	<td colspan="2"><b>'.$ETI['saiu62fecha'].'</b></td>
	<td><b>'.$ETI['saiu62mailenviado'].'</b></td>
	<td align="right">
	'.html_paginador('paginaf3062', $registros, $lineastabla, $pagina, 'paginarf3062()').'
	'.html_lpp('lppf3062', $lineastabla, 'paginarf3062()').'
	</td>
	</tr></thead>';
	if ($sInfoAvance!=''){
		$res=$res.'<tr class="fondoazul">
		<td colspan="7" align="center"> Avance: '.$sInfoAvance.'</td>
		</tr>';
		}
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
		$et_saiu62idtercero_doc='';
		$et_saiu62idtercero_nombre='';
		if ($filadet['saiu62idtercero']!=0){
			$et_saiu62idtercero_doc=$sPrefijo.$filadet['C2_td'].' '.$filadet['C2_doc'].$sSufijo;
			$et_saiu62idtercero_nombre=$sPrefijo.cadena_notildes($filadet['C2_nombre']).$sSufijo;
			}
		/*
		$et_saiu62idperiodo=$sPrefijo.cadena_notildes($filadet['exte02nombre']).$sSufijo;
		$et_saiu62idescuela=$sPrefijo.cadena_notildes($filadet['core12nombre']).$sSufijo;
		$et_saiu62idprograma=$sPrefijo.cadena_notildes($filadet['core09nombre']).$sSufijo;
		$et_saiu62idzona=$sPrefijo.cadena_notildes($filadet['unad23nombre']).$sSufijo;
		$et_saiu62idcentro=$sPrefijo.cadena_notildes($filadet['unad24nombre']).$sSufijo;
		*/
		$et_saiu62estado=$sPrefijo.cadena_notildes($filadet['saiu59nombre']).$sSufijo;
		$et_saiu62fecha='';
		$et_saiu62fhora='';
		if ($filadet['saiu62fecha']!=0){
			$et_saiu62fecha=$sPrefijo.fecha_desdenumero($filadet['saiu62fecha']).$sSufijo;
			$et_saiu62fhora=html_TablaHoraMin($filadet['saiu62fhora'], $filadet['saiu62min']);
			}
		$et_saiu62mailenviado=$ETI['no'];
		if ($filadet['saiu62mailenviado']!=0){
			$et_saiu62mailenviado=$ETI['si'];
			//$et_saiu62mailenviado=$sPrefijo.$filadet['saiu62mailenviado'].$sSufijo;
			}
		if ($bAbierta){
			if ($filadet['saiu62estado']==0){
				$sLink='<a href="javascript:eliminaidf3062('.$filadet['saiu62id'].')" class="lnkresalte">'.$ETI['lnk_retirar'].'</a>';
				}
			}
		$res=$res.'<tr'.$sClass.'>
		<td>'.$et_saiu62idtercero_doc.'</td>
		<td>'.$et_saiu62idtercero_nombre.'</td>
		<td>'.$et_saiu62estado.'</td>
		<td>'.$et_saiu62fecha.'</td>
		<td>'.$et_saiu62fhora.'</td>
		<td>'.$et_saiu62mailenviado.'</td>
		<td>'.$sLink.'</td>
		</tr>';
		}
	$res=$res.'</table>
	<div class="salto5px"></div>
	</div>';
	$objDB->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
	}
// -- 3062 Notificados XAJAX 
function f3062_Guardar($valores, $aParametros){
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
		list($sError, $iAccion, $saiu62id, $sDebugGuardar)=f3062_db_Guardar($valores, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugGuardar;
		}
	$objResponse=new xajaxResponse();
	if ($sError==''){
		list($sdetalle, $sDebugTabla)=f3062_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugTabla;
		$objResponse->assign('div_f3062detalle', 'innerHTML', $sdetalle);
		//if ($iAccion==2){
			//$objResponse->call('cargaridf3062('.$saiu62id.')');
			//}else{
			$objResponse->call('limpiaf3062');
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
function f3062_Traer($aParametros){
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
		$saiu62idcomunicado=numeros_validar($aParametros[1]);
		$saiu62idtercero=numeros_validar($aParametros[2]);
		if (($saiu62idcomunicado!='')&&($saiu62idtercero!='')){$besta=true;}
		}else{
		$saiu62id=$aParametros[103];
		if ((int)$saiu62id!=0){$besta=true;}
		}
	if ($besta){
		$besta=false;
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		$bHayDb=true;
		$sSQLcondi='';
		if ($paso==1){
			$sSQLcondi=$sSQLcondi.'saiu62idcomunicado='.$saiu62idcomunicado.' AND saiu62idtercero='.$saiu62idtercero.'';
			}else{
			$sSQLcondi=$sSQLcondi.'saiu62id='.$saiu62id.'';
			}
		$sSQL='SELECT * FROM saiu62comunicadonoti WHERE '.$sSQLcondi;
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
		$saiu62idtercero_id=(int)$fila['saiu62idtercero'];
		$saiu62idtercero_td=$APP->tipo_doc;
		$saiu62idtercero_doc='';
		$saiu62idtercero_nombre='';
		if ($saiu62idtercero_id!=0){
			list($saiu62idtercero_nombre, $saiu62idtercero_id, $saiu62idtercero_td, $saiu62idtercero_doc)=html_tercero($saiu62idtercero_td, $saiu62idtercero_doc, $saiu62idtercero_id, 0, $objDB);
			}
		$html_saiu62idtercero_llaves=html_DivTerceroV2('saiu62idtercero', $saiu62idtercero_td, $saiu62idtercero_doc, true, 2, 'Ingrese el documento');
		$objResponse->assign('saiu62idtercero', 'value', $saiu62idtercero_id);
		$objResponse->assign('div_saiu62idtercero_llaves', 'innerHTML', $html_saiu62idtercero_llaves);
		$objResponse->assign('div_saiu62idtercero', 'innerHTML', $saiu62idtercero_nombre);
		$saiu62id_nombre='';
		$html_saiu62id=html_oculto('saiu62id', $fila['saiu62id'], $saiu62id_nombre);
		$objResponse->assign('div_saiu62id', 'innerHTML', $html_saiu62id);
		$objResponse->assign('saiu62idperiodo', 'value', $fila['saiu62idperiodo']);
		$objResponse->assign('saiu62idescuela', 'value', $fila['saiu62idescuela']);
		$objResponse->assign('saiu62idprograma', 'value', $fila['saiu62idprograma']);
		$objResponse->assign('saiu62idzona', 'value', $fila['saiu62idzona']);
		$objResponse->assign('saiu62idcentro', 'value', $fila['saiu62idcentro']);
		$objResponse->assign('saiu62estado', 'value', $fila['saiu62estado']);
		$objResponse->assign('saiu62fecha', 'value', $fila['saiu62fecha']);
		list($iDia, $iMes, $iAgno)=fecha_DividirNumero($fila['saiu62fecha'], true);
		$objResponse->assign('saiu62fecha_dia', 'value', $iDia);
		$objResponse->assign('saiu62fecha_mes', 'value', $iMes);
		$objResponse->assign('saiu62fecha_agno', 'value', $iAgno);
		$html_saiu62fhora=html_HoraMin('saiu62fhora', $fila['saiu62fhora'], 'saiu62min', $fila['saiu62min'], false);
		$objResponse->assign('div_saiu62fhora', 'innerHTML', $html_saiu62fhora);
		$objResponse->assign('saiu62mailenviado', 'value', $fila['saiu62mailenviado']);
		$objResponse->call("MensajeAlarmaV2('', 0)");
		$objResponse->call("verboton('belimina3062','block')");
		}else{
		if ($paso==1){
			}else{
			$objResponse->call('MensajeAlarmaV2("No se encontro el registro de referencia:'.$saiu62id.'", 0)');
			}
		}
	if ($bHayDb){
		$objDB->CerrarConexion();
		}
	return $objResponse;
	}
function f3062_Eliminar($aParametros){
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
	list($sError, $sDebugElimina)=f3062_db_Eliminar($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugElimina;
	$objResponse=new xajaxResponse();
	if ($sError==''){
		list($sDetalle, $sDebugTabla)=f3062_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugTabla;
		$objResponse->assign('div_f3062detalle', 'innerHTML', $sDetalle);
		$objResponse->call('limpiaf3062');
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
function f3062_HtmlTabla($aParametros){
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
	list($sDetalle, $sDebugTabla)=f3062_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f3062detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f3062_PintarLlaves($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	//if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	if (isset($APP->piel)==0){$APP->piel=1;}
	$iPiel=$APP->piel;
	$saiu62idtercero=0;
	$saiu62idtercero_rs='';
	$html_saiu62idtercero_llaves=html_DivTerceroV2('saiu62idtercero', $APP->tipo_doc, '', false, 2, $ETI['ing_doc']);
	$html_saiu62id='<input id="saiu62id" name="saiu62id" type="hidden" value=""/>';
	$objResponse=new xajaxResponse();
	$objResponse->assign('saiu62idtercero','value', $saiu62idtercero);
	$objResponse->assign('div_saiu62idtercero_llaves','innerHTML', $html_saiu62idtercero_llaves);
	$objResponse->assign('div_saiu62idtercero','innerHTML', $saiu62idtercero_rs);
	$objResponse->assign('div_saiu62id','innerHTML', $html_saiu62id);
	return $objResponse;
	}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------
?>