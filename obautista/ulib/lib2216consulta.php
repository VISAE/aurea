<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2020 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versi�n 2.25.1 lunes, 22 de junio de 2020
--- 2216 Actas de matricula
*/
function f2216_TablaDetalleV2Consulta($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2216=$APP->rutacomun.'lg/lg_2216_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2216)){$mensajes_2216=$APP->rutacomun.'lg/lg_2216_es.php';}
	require $mensajes_todas;
	require $mensajes_2216;
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	if (isset($aParametros[0])==0){$aParametros[0]=-1;}
	if (isset($aParametros[98])==0){$aParametros[98]=0;}
	if (isset($aParametros[100])==0){$aParametros[100]=$_SESSION['unad_id_tercero'];}
	if (isset($aParametros[101])==0){$aParametros[101]=1;}
	if (isset($aParametros[102])==0){$aParametros[102]=20;}
	if (isset($aParametros[103])==0){$aParametros[103]='';}
	if (isset($aParametros[104])==0){$aParametros[104]='';}
	if (isset($aParametros[105])==0){$aParametros[105]='';}
	//$aParametros[103]=numeros_validar($aParametros[103]);
	$aParametros[0]=numeros_validar($aParametros[0]);
	if ($aParametros[0]==''){$aParametros[0]=-1;}
	$idTercero=$aParametros[100];
	$sDebug='';
	$core01id=$aParametros[0];
	$pagina=$aParametros[101];
	$lineastabla=$aParametros[102];
	$idEstudiante=numeros_validar($aParametros[103]);
	$idPrograma=numeros_validar($aParametros[104]);
	$idPlanEst=numeros_validar($aParametros[105]);
	$bAbierta=false;
	//$sSQL='SELECT Campo FROM core01estprograma WHERE core01id='.$core01id;
	//$tabla=$objDB->ejecutasql($sSQL);
	//if ($objDB->nf($tabla)>0){
		//$fila=$objDB->sf($tabla);
		//if ($fila['Campo']!='S'){$bAbierta=true;}
		//}
	$sLeyenda='';
	if ((int)$idEstudiante==0){$sLeyenda='No se ha definido el estudiante a consultar';}
	//if ((int)$idPrograma==0){$sLeyenda='No se ha definido el programa a consultar';}
	if ($idPlanEst==''){$idPlanEst=0;}
	if ($sLeyenda!=''){
		$sLeyenda='<div class="salto1px"></div>
		<div class="GrupoCamposAyuda">
		'.$sLeyenda.'
		<div class="salto1px"></div>
		</div>';
		return array($sLeyenda.'<input id="paginaf2216" name="paginaf2216" type="hidden" value="'.$pagina.'"/><input id="lppf2216" name="lppf2216" type="hidden" value="'.$lineastabla.'"/>', $sDebug);
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
	if ($aParametros[98]==1){
		$sSQL='SELECT 1 FROM core01estprograma WHERE core01idtercero='.$idEstudiante.'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>1){
			$bAbierta=true;
			$objCombos=new clsHtmlCombos();
			}
		}
	$bPorPrograma=false;
	if ($idPrograma!=''){
		$bPorPrograma=true;
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
	$sTitulos='Tercero';
	if ($bPorPrograma){
		$sSQLadd=' AND TB.core16idprograma='.$idPrograma.' '.$sSQLadd;
		}
	//, TB.core16parametros, TB.core16fecharecibido, TB.core16minrecibido, TB.core16procesado, TB.core16origen, TB.core16proccarac, TB.core16procagenda, TB.core16tercero, TB.core16idescuela, TB.core16idzona
	$sSQL='SELECT TB.core16peraca, TB.core16idprograma, TB.core16idcead, TB.core16nuevo, TB.core16numcursos, TB.core16numaprobados, 
	TB.core16promedio, TB.core16id, TB.core16fechamatricula, TB.core16aplicada, TB.core16idestprog, TB.core16estado 
	FROM core16actamatricula AS TB 
	WHERE '.$sSQLadd1.' TB.core16tercero='.$idEstudiante.' '.$sSQLadd.'
	ORDER BY TB.core16peraca DESC';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_2216" name="consulta_2216" type="hidden" value="'.$sSQLlista.'"/>
	<input id="titulos_2216" name="titulos_2216" type="hidden" value="'.$sTitulos.'"/>';
	if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 2216: '.$sSQL.'<br>';}
	$tabladetalle=$objDB->ejecutasql($sSQL);
	if ($tabladetalle==false){
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		//$sLeyenda=$sSQL;
		}else{
		$registros=$objDB->nf($tabladetalle);
		if ($registros==0){
			//return array(utf8_encode($sErrConsulta.'<input id="paginaf2216" name="paginaf2216" type="hidden" value="'.$pagina.'"/><input id="lppf2216" name="lppf2216" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
			}
		if ((($registros-1)/$lineastabla)<($pagina-1)){$pagina=(int)(($registros-1)/$lineastabla)+1;}
		if ($registros>$lineastabla){
			$rbase=($pagina-1)*$lineastabla;
			$limite=' LIMIT '.$rbase.', '.$lineastabla;
			$tabladetalle=$objDB->ejecutasql($sSQL.$limite);
			}
		}
	$sTituloPrograma='<td><b>'.$ETI['core16idprograma'].'</b></td>';
	if ($bPorPrograma){
		$sTituloPrograma='';
		}
	$res=$sErrConsulta.$sLeyenda.'<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">
	<tr class="fondoazul">
	<td><b>'.$ETI['core16peraca'].'</b></td>'.$sTituloPrograma.'
	<td><b>'.$ETI['core16fechamatricula'].'</b></td>
	<td><b>'.$ETI['msg_condicion'].'</b></td>
	<td title="'.$ETI['core16numcursos'].'"><b>Aprobaci&oacute;n</b></td>
	<td title="'.$ETI['core16promedio'].'" align="center"><b>Promedio</b></td>
	<td align="right">
	'.html_paginador('paginaf2216', $registros, $lineastabla, $pagina, 'paginarf2216Consulta()').'
	'.html_lpp('lppf2216', $lineastabla, 'paginarf2216Consulta()').'
	</td>
	</tr>';
	$tlinea=1;
	$idCead=-99;
	while($filadet=$objDB->sf($tabladetalle)){
		if ($idCead!=$filadet['core16idcead']){
			$idCead=$filadet['core16idcead'];
			$sNomCEAD='{'.$filadet['core16idcead'].'}';
			$sSQL='SELECT unad24nombre FROM unad24sede WHERE unad24id='.$filadet['core16idcead'].'';
			$tabla=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla)>0){
				$fila=$objDB->sf($tabla);
				$sNomCEAD=cadena_notildes($fila['unad24nombre']);
				}
			$res=$res.'<tr class="fondoazul">
			<td colspan="7" align="center">'.$ETI['core16idcead'].' <b>'.$sNomCEAD.'</b></td>
			</tr>';
			}
		$sNomPeraca='{'.$filadet['core16peraca'].'}';
		$sSQL='SELECT exte02nombre FROM exte02per_aca WHERE exte02id='.$filadet['core16peraca'].'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$fila=$objDB->sf($tabla);
			$sNomPeraca=cadena_notildes($fila['exte02nombre']);
			}
		$sLineaPrograma='';
		if (!$bPorPrograma){
			$sNomPrograma='{'.$filadet['core16idprograma'].'}';
			$sSQL='SELECT core09nombre FROM core09programa WHERE core09id='.$filadet['core16idprograma'].'';
			$tabla=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla)>0){
				$fila=$objDB->sf($tabla);
				$sNomPrograma=cadena_notildes($fila['core09nombre']);
				}
			}
		$et_core16nuevo=$acore16nuevo[$filadet['core16nuevo']];
		$sPrefijo='';
		$sSufijo='';
		$sClass='';
		$et_core16idestprog='';
		switch($filadet['core16estado']){
			case 99:
				$et_core16nuevo='Anulada';
				$sPrefijo='<span class="rojo">';
				$sSufijo='</span>';
				break;
		}
		$iTotalCursos=$filadet['core16numcursos'];
		if ($filadet['core16aplicada']>0){
			if ($iTotalCursos>0){
				if ($filadet['core16numaprobados']==$iTotalCursos){
					$sPrefijo='<b>';
					$sSufijo='</b>';
					}else{
					if ($filadet['core16numaprobados']==0){
						$sPrefijo='<span class="rojo">';
						$sSufijo='</span>';
						}
					}
				}
			}
		if(($tlinea%2)==0){$sClass=' class="resaltetabla"';}
		$tlinea++;
		if (!$bPorPrograma){
			$sLineaPrograma='<td>'.$sPrefijo.$sNomPrograma.$sSufijo.'</td>';
			}
		$et_core16fechamatricula=$sPrefijo.fecha_desdenumero($filadet['core16fechamatricula']).$sSufijo;
		if ($filadet['core16aplicada']>0){
			$et_core16numcursos=$sPrefijo.$filadet['core16numaprobados'].' / '.$iTotalCursos.$sSufijo;
			$et_core16promedio=$sPrefijo.formato_numero($filadet['core16promedio'], 1).$sSufijo;
			}else{
			$et_core16numcursos=$sPrefijo.'- / '.$iTotalCursos.$sSufijo;
			$et_core16promedio='';
			}
		if ($bAbierta){
			$objCombos->nuevo('core16idestprog_'.$filadet['core16id'], $filadet['core16idestprog'], true, '{'.$ETI['msg_seleccione'].'}');
			$objCombos->iAncho=240;
			$objCombos->sAccion='cuadricula2216('.$filadet['core16id'].', this.value)';
			$sSQL='SELECT TB.core01id AS id, CONCAT(T9.core09codigo, " - ", T9.core09nombre, " [", TB.core01idplandeestudios, "]") AS nombre 
			FROM core01estprograma AS TB, core09programa AS T9 
			WHERE TB.core01idtercero='.$idEstudiante.' AND TB.core01id<>'.$filadet['core16idestprog'].' AND TB.core01idprograma=T9.core09id
			ORDER BY TB.core01fechainicio DESC';
			$et_core16idestprog=$objCombos->html($sSQL, $objDB);
			}
		$res=$res.'<tr'.$sClass.'>
		<td>'.$sPrefijo.$sNomPeraca.$sSufijo.'</td>'.$sLineaPrograma.'
		<td>'.$et_core16fechamatricula.'</td>
		<td>'.$sPrefijo.$et_core16nuevo.$sSufijo.'</td>
		<td>'.$et_core16numcursos.'</td>
		<td align="center">'.$et_core16promedio.'</td>
		<td>'.$et_core16idestprog.'</td>
		</tr>';
		}
	$res=$res.'</table>';
	$objDB->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
	}
function f2216_HtmlTablaConsulta($aParametros){
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
	list($sDetalle, $sDebugTabla)=f2216_TablaDetalleV2Consulta($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f2216detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
?>