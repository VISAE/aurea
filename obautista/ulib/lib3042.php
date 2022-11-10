<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2021 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.25.10c martes, 16 de febrero de 2021
--- 3042 Historico
*/
function f3042_TablaDetalleV2($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3042=$APP->rutacomun.'lg/lg_3041_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3042)){$mensajes_3042=$APP->rutacomun.'lg/lg_3041_es.php';}
	require $mensajes_todas;
	require $mensajes_3042;
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	if (isset($aParametros[100])==0){$aParametros[100]=$_SESSION['unad_id_tercero'];}
	if (isset($aParametros[91])==0){$aParametros[91]=1;}
	if (isset($aParametros[92])==0){$aParametros[92]=20;}
	if (isset($aParametros[93])==0){$aParametros[93]=0;}
	if (isset($aParametros[103])==0){$aParametros[103]='';}
	$aParametros[103]=numeros_validar($aParametros[103]);
	$idTercero=$aParametros[100];
	$sDebug='';
	$saiu41idestudiante=$aParametros[103];
	$pagina=$aParametros[91];
	$lineastabla=$aParametros[92];
	$id41=(int)$aParametros[93];
	$bAbierta=true;
	if ($id41==-1){$bAbierta=false;}
	$sLeyenda='';
	$sBotones='<input id="paginaf3042" name="paginaf3042" type="hidden" value="'.$pagina.'"/>
	<input id="lppf3042" name="lppf3042" type="hidden" value="'.$lineastabla.'"/>';
	if ((int)$saiu41idestudiante==0){
		$sLeyenda='No hay estudiante';
		}
	if ($sLeyenda!=''){
		return array($sBotones, $sDebug);
		die();
		}
	list($idContTercero, $sErrorB)=f1011_BloqueTercero($saiu41idestudiante, $objDB);
	$sTabla41='saiu41docente_'.$idContTercero;
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
	$sTitulos='Estudiante, Previo';
	$sSQL='SELECT TB.saiu41consec, TB.saiu41id, TB.saiu41fecha, TB.saiu41cerrada, T11.unad11razonsocial, TB.saiu41contacto_observa, 
	TB.saiu41idperiodo, TB.saiu41idcurso, TB.saiu41idactividad, TB.saiu41idtutor 
	FROM '.$sTabla41.' AS TB, unad11terceros AS T11 
	WHERE TB.saiu41idestudiante='.$saiu41idestudiante.' AND '.$sSQLadd1.' TB.saiu41id<>'.$id41.' AND TB.saiu41idtutor=T11.unad11id '.$sSQLadd.'
	ORDER BY TB.saiu41fecha DESC, TB.saiu41consec DESC';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_3042" name="consulta_3042" type="hidden" value="'.$sSQLlista.'"/>
	<input id="titulos_3042" name="titulos_3042" type="hidden" value="'.$sTitulos.'"/>';
	if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 3042: '.$sSQL.'<br>';}
	$tabladetalle=$objDB->ejecutasql($sSQL);
	if ($tabladetalle==false){
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		//$sLeyenda=$sSQL;
		}else{
		$registros=$objDB->nf($tabladetalle);
		if ($registros==0){
			return array(utf8_encode($sErrConsulta.'<input id="paginaf3042" name="paginaf3042" type="hidden" value="'.$pagina.'"/><input id="lppf3042" name="lppf3042" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
<td><b>'.$ETI['saiu41consec'].'</b></td>
<td><b>'.$ETI['saiu41fecha'].'</b></td>
<td><b>'.$ETI['saiu41cerrada'].'</b></td>
<td><b>'.$ETI['saiu41idtutor'].'</b></td>
<td align="right">
'.html_paginador('paginaf3042', $registros, $lineastabla, $pagina, 'paginarf3042()').'
'.html_lpp('lppf3042', $lineastabla, 'paginarf3042()').'
</td>
</tr></thead>';
	$tlinea=1;
	$idPeriodo=0;
	while($filadet=$objDB->sf($tabladetalle)){
		if ($idPeriodo!=$filadet['saiu41idperiodo']){
			$idPeriodo=$filadet['saiu41idperiodo'];
			$sNomPeriodo='{'.$idPeriodo.'}';
			$sSQL='SELECT exte02nombre FROM exte02per_aca WHERE exte02id='.$idPeriodo.'';
			$tablae=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae)>0){
				$filae=$objDB->sf($tablae);
				$sNomPeriodo=cadena_notildes($filae['exte02nombre']);
				}
			$res=$res.'<tr>
			<td colspan="5" align="center">'.$ETI['saiu41idperiodo'].': <b>'.$sNomPeriodo.'</b></td>
			</tr>';
			}
		$sPrefijo='';
		$sSufijo='';
		$sClass=' class="resaltetabla"';
		$sLink='';
		switch($filadet['saiu41cerrada']){
			case 1:
			$sPrefijo='<b>';
			$sSufijo='</b>';
			break;
			case 9:
			$sPrefijo='<span class="rojo">';
			$sSufijo='</span>';
			break;
			}
		if(($tlinea%2)!=0){$sClass='';}
		$tlinea++;
		$et_saiu41consec=$sPrefijo.$filadet['saiu41consec'].$sSufijo;
		$et_saiu41fecha=$sPrefijo.fecha_desdenumero($filadet['saiu41fecha']).$sSufijo;
		$et_saiu41cerrada=$sPrefijo.$asaiu41cerrada[$filadet['saiu41cerrada']].$sSufijo;
		$et_saiu41idtutor=$sPrefijo.cadena_notildes($filadet['unad11razonsocial']).$sSufijo;
		//saiu41contacto_observa
		if ($bAbierta){
			$sLink='<a href="javascript:cargaridf3041('.$idContTercero.', '.$filadet['saiu41id'].')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$res=$res.'<tr'.$sClass.'>
		<td>'.$et_saiu41consec.'</td>
		<td>'.$et_saiu41fecha.'</td>
		<td>'.$et_saiu41cerrada.'</td>
		<td>'.$et_saiu41idtutor.'</td>
		<td>'.$sLink.'</td>
		</tr>';
		//saiu41idperiodo, saiu41idcurso, TB.saiu41idactividad, TB.saiu41idtutor
		if ($filadet['saiu41idcurso']!=0){
			$et_curso='{'.$filadet['saiu41idcurso'].'}';
			$sSQL='SELECT unad40titulo, unad40nombre FROM unad40curso WHERE unad40id='.$filadet['saiu41idcurso'].'';
			$tablae=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae)>0){
				$filae=$objDB->sf($tablae);
				$et_curso='<b>'.$filae['unad40titulo'].'</b> - '.cadena_notildes($filae['unad40nombre']);
				}
			$et_actividad='';
			if ($filadet['saiu41idactividad']!=0){
				$et_actividad=' =&gt; '.$ETI['saiu41idactividad'].': {'.$filadet['saiu41idactividad'].'}';
				$sSQL='SELECT ofer04nombre FROM ofer04cursoactividad WHERE ofer04id='.$filadet['saiu41idactividad'].'';
				$tablae=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($tablae)>0){
					$filae=$objDB->sf($tablae);
					$et_actividad=' =&gt; '.$ETI['saiu41idactividad'].': '.cadena_notildes($filae['ofer04nombre']);
					}
				}
			$res=$res.'<tr'.$sClass.'>
			<td></td>
			<td colspan="4">'.$ETI['saiu41idcurso'].': '.$et_curso.$et_actividad.'</td>
			</tr>';
			}
		if ($filadet['saiu41contacto_observa']!=''){
			$et_saiu41contacto_observa=$sPrefijo.cadena_notildes($filadet['saiu41contacto_observa']).$sSufijo;
			$res=$res.'<tr'.$sClass.'>
			<td></td>
			<td colspan="4">'.$et_saiu41contacto_observa.'</td>
			</tr>';
			}
		}
	$res=$res.'</table>
	<div class="salto5px"></div>
	</div>';
	$objDB->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
	}
function f3042_HtmlTabla($aParametros){
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
	list($sDetalle, $sDebugTabla)=f3042_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f3042detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------
?>