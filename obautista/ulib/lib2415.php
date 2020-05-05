<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2019 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.23.3 miércoles, 24 de julio de 2019
--- 2415 ceca15ejecut
*/
/** Archivo lib2415.php.
* Libreria 2415 ceca15ejecut.
* @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
* @date miércoles, 24 de julio de 2019
*/
function f2415_HTMLComboV2_ceca15idperaca($objDB, $objCombos, $valor, $vrceca15tipoperiodo){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('ceca15idperaca', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	$objCombos->sAccion='paginarf2415()';
	$sSQL=f146_ConsultaCombo('exte02tipoperiodo="'.$vrceca15tipoperiodo.'" AND exte02vigente="S"');
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f2415_HTMLComboV2_ceca15idprograma($objDB, $objCombos, $valor, $vrceca15idescuela){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	//@@ Se debe arreglar la condicion..
	$sCondi='core09idescuela="'.$vrceca15idescuela.'"';
	if ($sCondi!=''){$sCondi=' WHERE '.$sCondi;}
	$objCombos->nuevo('ceca15idprograma', $valor, true, '{'.$ETI['msg_todos'].'}');
	$objCombos->sAccion='paginarf2415()';
	$sSQL='SELECT core09id AS id, CONCAT(core09nombre, " [", core09codigo, "]") AS nombre FROM core09programa'.$sCondi;
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f2415_Comboceca15idperaca($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$objCombos=new clsHtmlCombos('n');
	$html_ceca15idperaca=f2415_HTMLComboV2_ceca15idperaca($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_ceca15idperaca', 'innerHTML', $html_ceca15idperaca);
	$objResponse->call('paginarf2415');
	return $objResponse;
	}
function f2415_Comboceca15idprograma($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$objCombos=new clsHtmlCombos('n');
	$html_ceca15idprograma=f2415_HTMLComboV2_ceca15idprograma($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_ceca15idprograma', 'innerHTML', $html_ceca15idprograma);
	$objResponse->call('$("#ceca15idprograma").chosen()');
	$objResponse->call('paginarf2415');
	return $objResponse;
	}
function f2415_ExisteDato($datos){}
function f2415_Busquedas($aParametros){
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2415=$APP->rutacomun.'lg/lg_2415_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2415)){$mensajes_2415=$APP->rutacomun.'lg/lg_2415_es.php';}
	require $mensajes_todas;
	require $mensajes_2415;
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
	$sTitulo='<h2>'.$ETI['titulo_2415'].' - '.$sTitulo.'</h2>';
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97titulo', 'innerHTML', $sTitulo);
	$objResponse->assign('div_97params', 'innerHTML', $sParams);
	$objResponse->assign('div_97tabla', 'innerHTML', $sTabla);
	$objResponse->setFunction('paginarbusqueda','',$sJavaBusqueda);
	$objResponse->call('expandesector(97)');
	return $objResponse;
	}
function f2415_HtmlBusqueda($aParametros){
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
function f2415_TablaDetalleV2($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2415=$APP->rutacomun.'lg/lg_2415_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2415)){$mensajes_2415=$APP->rutacomun.'lg/lg_2415_es.php';}
	require $mensajes_todas;
	require $mensajes_2415;
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	if (isset($aParametros[100])==0){$aParametros[100]=$_SESSION['unad_id_tercero'];}
	if (isset($aParametros[101])==0){$aParametros[101]=1;}
	if (isset($aParametros[102])==0){$aParametros[102]=20;}
	if (isset($aParametros[103])==0){$aParametros[103]='';}
	if (isset($aParametros[104])==0){$aParametros[104]='';}
	if (isset($aParametros[105])==0){$aParametros[105]='';}
	$idPeraca=numeros_validar($aParametros[103]);
	$idEscuela=($aParametros[104]);
	$idPrograma=($aParametros[105]);
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
	if ($idPeraca==''){
		$sLeyenda='No se ha seleccionado un periodo a consultar';
		}
	if ($sLeyenda==''){
		if ($idEscuela==''){
			$sLeyenda='No se ha seleccionado una escuela a consultar';
			}
		}
	if ($sLeyenda!=''){
		$sLeyenda='<div class="salto1px"></div>
<div class="GrupoCamposAyuda">
<b>'.$sLeyenda.'</b>
<div class="salto1px"></div>
</div>';
		return array(utf8_encode($sLeyenda.'<input id="paginaf2415" name="paginaf2415" type="hidden" value="'.$pagina.'"/><input id="lppf2415" name="lppf2415" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
		die();
		}
	$sRes='';
	$sTabulador='&nbsp;&nbsp;&nbsp;&nbsp;';
	//Traer los datos de fecha inicial y final de clases...
	$iHoy=fecha_DiaMod();
	$sFechaIniClase='';
	$sFechaFinMedio='';
	$sFechaFinPOA='';
	$sFechaFinPOC='';
	$bIniciado75=false;
	$bVencido75=false;
	$bVencidoFinales=false;
	$idContPeraca=f146_Contenedor($idPeraca, $objDB);
	$sRes='';
	$sConvenciones='<div class="GrupoCampos" style="float:left;min-width:500px;">'.$ETI['msg_convenciones'].html_salto().'</div>'.html_salto();
	$sSQL='SELECT ofer14fechaini60, ofer14fechafin60, ofer14fechafin40, ofer14fechafin25poc FROM ofer14per_acaparams WHERE ofer14idper_aca='.$idPeraca.'';
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$sFechaIniClase=$fila['ofer14fechaini60'];
		$sFechaFinMedio=$fila['ofer14fechafin60'];
		$sFechaFinPOA=$fila['ofer14fechafin40'];
		$sFechaFinPOC=$fila['ofer14fechafin25poc'];
		$sPref1='';
		$sPref2='';
		$sPref3='';
		$sPref4='';
		$sSuf1='';
		$sSuf2='';
		$sSuf3='';
		$sSuf4='';
		if ($iHoy<fecha_EnNumero($sFechaIniClase)){
			}else{
			$bIniciado75=true;
			$sPref1='<b>';
			$sSuf1='</b>';
			if ($iHoy<fecha_EnNumero($sFechaFinMedio)){
				}else{
				$bVencido75=true;
				$sPref1='<span class="rojo">';
				$sSuf1='</span>';
				$sPref3='<b>';
				$sPref4='<b>';
				$sSuf3='</b>';
				$sSuf4='</b>';
				if ($iHoy<fecha_EnNumero($sFechaFinPOA)){
					}else{
					$bVencidoFinales=true;
					$sPref3='<span class="rojo">';
					$sSuf3='</span>';
					}
				if ($iHoy<fecha_EnNumero($sFechaFinPOC)){
					}else{
					$bVencidoFinales=true;
					$sPref4='<span class="rojo">';
					$sSuf4='</span>';
					}
				}
			}
		$sRes='<div class="GrupoCampos450">
Fecha de inicio de actividades: '.$sPref1.$sFechaIniClase.$sSuf1.', '.html_salto().'
Fecha final para el 75%: '.$sPref1.$sFechaFinMedio.$sSuf1.', '.html_salto().'
Fecha fin evaluaciones POA: '.$sPref3.$sFechaFinPOA.$sSuf3.', '.html_salto().'
Fecha fin evaluaciones POC: '.$sPref4.$sFechaFinPOC.$sSuf4.''.html_salto().'
</div>'.$sConvenciones;
		}else{
		$sRes=$sRes.'<div class="GrupoCampos450">
<span class="rojo">No se han configurado fechas extremas para el periodo (OAI - Configurar - Periodos Acad&eacute;micos)</span>'.html_salto().'
</div>'.$sConvenciones;
		}
	//Traer las escuelas.
	$sWhereEscuelas='core12tieneestudiantes="S" AND core12id>0';
	if ($idEscuela>0){
		$sWhereEscuelas='core12id='.$idEscuela.'';
		}
	$sSQL='SELECT core12id, core12nombre FROM core12escuela WHERE '.$sWhereEscuelas.' ORDER BY core12nombre';
	$tabla=$objDB->ejecutasql($sSQL);
	while($fila=$objDB->sf($tabla)){
		$sRes=$sRes.'<hr>';
		//Cantidad de cursos ofertados
		$iTotalCursos=0;
		$iNoCompletos=0;
		$sIdsPrograma='-99';
		$aProgramas=array();
		$iNumProgramas=0;
		$sCursos='-99';
		$iNumOfertaCurso=0;
		$iNumImcompletosPrograma=0;
		$idPrograma=-99999;
		$sSQL='SELECT TB.ofer08idprograma, TB.ofer08estadocampus, TB.ofer08idcurso, T9.core09codigo, T9.core09nombre 
FROM ofer08oferta AS TB, core09programa AS T9 
WHERE TB.ofer08idper_aca='.$idPeraca.' AND TB.ofer08idescuela='.$fila['core12id'].' AND TB.ofer08obligaacreditar IN ("S", "N") AND TB.ofer08estadooferta=1 AND TB.ofer08idprograma=T9.core09id
ORDER BY TB.ofer08idprograma';
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Oferta para la escuela: '.$sSQL.'<br>';}
		$tabla8=$objDB->ejecutasql($sSQL);
		while($fila8=$objDB->sf($tabla8)){
			//Cargar a memoria los datos del programa.
			if ($idPrograma!=$fila8['ofer08idprograma']){
				if ($idPrograma!=-99999){
					$iNumProgramas++;
					$aProgramas[$iNumProgramas]['cursos']=$sCursos;
					$aProgramas[$iNumProgramas]['id']=$idPrograma;
					$aProgramas[$iNumProgramas]['codigo']=$sCodPrograma;
					$aProgramas[$iNumProgramas]['nombre']=$sNomPrograma;
					$aProgramas[$iNumProgramas]['ofertados']=$iNumOfertaCurso;
					$aProgramas[$iNumProgramas]['incompletos']=$iNumImcompletosPrograma;
					$iNumImcompletosPrograma=0;
					$sCursos='-99';
					$iNumOfertaCurso=0;
					}
				$idPrograma=$fila8['ofer08idprograma'];
				$sCodPrograma=$fila8['core09codigo'];
				$sNomPrograma=cadena_notildes($fila8['core09nombre']);
				$sIdsPrograma=$sIdsPrograma.','.$fila8['ofer08idprograma'];
				}
			$iTotalCursos++;
			$iNumOfertaCurso++;
			switch($fila8['ofer08estadocampus']){
				case 10:
				case 12:
				$sCursos=$sCursos.','.$fila8['ofer08idcurso'];
				break;
				default:
				$iNoCompletos++;
				$iNumImcompletosPrograma++;
				break;
				}
			}
		if ($idPrograma!=-99999){
			$iNumProgramas++;
			$aProgramas[$iNumProgramas]['cursos']=$sCursos;
			$aProgramas[$iNumProgramas]['id']=$idPrograma;
			$aProgramas[$iNumProgramas]['codigo']=$sCodPrograma;
			$aProgramas[$iNumProgramas]['nombre']=$sNomPrograma;
			$aProgramas[$iNumProgramas]['ofertados']=$iNumOfertaCurso;
			$aProgramas[$iNumProgramas]['incompletos']=$iNumImcompletosPrograma;
			}
		$sNuCompletos='';
		if ($iNoCompletos!=0){
			$sNuCompletos=' <span class="rojo">('.$ETI['msg_incompleto'].': '.$iNoCompletos.')</span>';
			}
		$sRes=$sRes.'<b>'.cadena_notildes($fila['core12nombre']).'</b> '.$ETI['msg_ofertados'].': <b>'.$iTotalCursos.'</b>'.$sNuCompletos.''.html_salto();
		//Ahora el resumen por programa.
		if ($iTotalCursos>0){
			$sRes=$sRes.'<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">
<tr class="fondoazul">
<td colspan="2"><b>Programa</b></td>
<td colspan="2" width="8%"><b>CO</b></td>
<td width="8%"><b>NC1</b>
<td width="8%"><b>NC2</b>
<td width="8%"><b>PR75</b>
<td width="8%"><b>NC3</b>
<td width="8%"><b>PR25</b>
</tr>';
			for ($k=1;$k<=$iNumProgramas;$k++){
				$idPrograma=$aProgramas[$k]['id'];
				$sCodPrograma=$aProgramas[$k]['codigo'];
				$sNomPrograma=$aProgramas[$k]['nombre'];
				$iNumOfertaCurso=$aProgramas[$k]['ofertados'];
				$iNumImcompletosPrograma=$aProgramas[$k]['incompletos'];
				$sCursos=$aProgramas[$k]['cursos'];
				$sNuCompletos='';
				if ($iNumImcompletosPrograma!=0){
					$sNuCompletos=' <span class="rojo">'.$iNumImcompletosPrograma.'</span>';
					}
				$sCodPrograma='<b>'.$sCodPrograma.'</b>';
				$sNomPrograma='<b>'.($sNomPrograma).'</b>';
				if ($idPrograma==0){
					$sCodPrograma='';
					$sNomPrograma=$ETI['msg_noasociada'];
					}
				$sBloque75='';
				$sBloque25='';
				$iEst0=0;
				$iEst1=0;
				$iEst2=0;
				$iEst7=0;
				$iEst8=0;
				if ($bIniciado75){
					//ahora listar los que tienen actividades vencidas
					$sSQL='SELECT ceca02idestado, COUNT(DISTINCT(ceca02idcurso)) AS Total 
FROM ceca02actividadtutor_'.$idContPeraca.' AS TB
WHERE ceca02idperaca='.$idPeraca.' AND ceca02idestado=1 AND ceca02fecharetro<'.$iHoy.' AND ceca02idcurso IN ('.$sCursos.') AND ceca02momentoest=0
GROUP BY ceca02idestado';
					if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Revisando cursos con actividades vencidas : '.$sSQL.'<br>';}
					$tabla2=$objDB->ejecutasql($sSQL);
					if ($objDB->nf($tabla2)>0){
						$fila2=$objDB->sf($tabla2);
						$iEst0=$fila2['Total'];
						}
					$sSQL='SELECT ceca02idestado, COUNT(DISTINCT(ceca02idcurso)) AS Total 
FROM ceca02actividadtutor_'.$idContPeraca.' AS TB
WHERE ceca02idperaca='.$idPeraca.' AND ceca02idestado=1 AND ceca02fecharetro<'.$iHoy.' AND ceca02idcurso IN ('.$sCursos.') AND ceca02momentoest=1
GROUP BY ceca02idestado';
					if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Revisando cursos con actividades vencidas : '.$sSQL.'<br>';}
					$tabla2=$objDB->ejecutasql($sSQL);
					if ($objDB->nf($tabla2)>0){
						$fila2=$objDB->sf($tabla2);
						$iEst1=$fila2['Total'];
						}
					if ($bVencido75){
						//ahora listar los que no han reportado 75
						$sSQL='SELECT COUNT(DISTINCT(ceca02idcurso)) AS Total 
FROM ceca02actividadtutor_'.$idContPeraca.' AS TB
WHERE ceca02idperaca='.$idPeraca.' AND ceca02idcurso IN ('.$sCursos.') AND ceca02idestado<>8 AND ceca02momentoest IN(0,1)';
						$tabla2=$objDB->ejecutasql($sSQL);
						if ($objDB->nf($tabla2)>0){
							$fila2=$objDB->sf($tabla2);
							$iEst7=$fila2['Total'];
							}
						}
					}
				if ($bVencido75){
					//Ahora que no han reportado de las notas del 25
					//ahora listar los que tienen actividades vencidas
					$sSQL='SELECT ceca02idestado, COUNT(DISTINCT(ceca02idcurso)) AS Total 
FROM ceca02actividadtutor_'.$idContPeraca.' AS TB
WHERE ceca02idperaca='.$idPeraca.' AND ceca02idestado=1 AND ceca02fecharetro<'.$iHoy.' AND ceca02idcurso IN ('.$sCursos.') AND ceca02momentoest=2
GROUP BY ceca02idestado';
					if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Revisando cursos con actividades vencidas : '.$sSQL.'<br>';}
					$tabla2=$objDB->ejecutasql($sSQL);
					if ($objDB->nf($tabla2)>0){
						$fila2=$objDB->sf($tabla2);
						$iEst2=$fila2['Total'];
						}
					if ($bVencidoFinales){
						//ahora listar los que no han reportado 25
						$sSQL='SELECT COUNT(DISTINCT(ceca02idcurso)) AS Total 
FROM ceca02actividadtutor_'.$idContPeraca.' AS TB
WHERE ceca02idperaca='.$idPeraca.' AND ceca02idcurso IN ('.$sCursos.') AND ceca02idestado<>8 AND ceca02momentoest=2';
						$tabla2=$objDB->ejecutasql($sSQL);
						if ($objDB->nf($tabla2)>0){
							$fila2=$objDB->sf($tabla2);
							$iEst8=$fila2['Total'];
							}
						}
					//Termina
					}
				if ($bIniciado75){
					if (($iEst0+$iEst1+$iEst2+$iEst7+$iEst8)==0){
						$sBloque75='<td colspan="5"><span class="verde">'.$ETI['msg_aldia'].'</span></td>';
						}else{
						if ($iEst0!=0){
							$sBloque75='<td><span class="rojo">'.$iEst0.'</span></td>';
							}else{
							$sBloque75='<td></td>';
							}
						if ($iEst1!=0){
							$sBloque75=$sBloque75.'<td><span class="rojo">'.$iEst1.'</span></td>';
							}else{
							$sBloque75=$sBloque75.'<td></td>';
							}
						if ($iEst7!=0){
							$sBloque75=$sBloque75.'<td><span class="rojo">'.$iEst7.'</span></td>';
							}else{
							$sBloque75=$sBloque75.'<td></td>';
							}
						if ($iEst2!=0){
							$sBloque25='<td><span class="rojo">'.$iEst2.'</span></td>';
							}else{
							$sBloque25='<td></td>';
							}
						if ($iEst8!=0){
							$sBloque25=$sBloque25.'<td><span class="rojo">'.$iEst8.'</span></td>';
							}else{
							$sBloque25=$sBloque25.'<td></td>';
							}
						}
					}else{
					$sBloque75='<td colspan="5"></td>';
					}
				$sRes=$sRes.'<tr>
<td>'.$sCodPrograma.'</td>
<td>'.$sNomPrograma.'</td>
<td><b>'.$iNumOfertaCurso.'</b></td>
<td>'.$sNuCompletos.'</td>
'.$sBloque75.'
'.$sBloque25.'
</tr>';
				}
			$sRes=$sRes.'</table>';
			/*
			if (false){
			for ($k=1;$k<=$iNumProgramas;$k++){
				$idPrograma=$aProgramas[$k]['id'];
				$sCodPrograma=$aProgramas[$k]['codigo'];
				$sNomPrograma=$aProgramas[$k]['nombre'];
				$iNumOfertaCurso=$aProgramas[$k]['ofertados'];
				$iNumImcompletosPrograma=$aProgramas[$k]['incompletos'];
				$sCursos=$aProgramas[$k]['cursos'];
				$sNuCompletos='';
				if ($iNumImcompletosPrograma!=0){
					$sNuCompletos=' <span class="rojo">('.$iNumImcompletosPrograma.')</span>';
					}
				$sNomPrograma='Programa <b>'.($sNomPrograma).'</b>';
				if ($idPrograma==0){
					$sNomPrograma='Oferta no asociada a un programa';
					}
				$sRes=$sRes.$sTabulador.$sNomPrograma.' - Cursos Ofertados: <b>'.$iNumOfertaCurso.'</b>'.$sNuCompletos.''.html_salto();
				if ($bIniciado75){
					$iEst1=0;
					$iEst7=0;
					$iEst8=0;
					//ahora listar los que tienen actividades vencidas
					$sSQL='SELECT ceca02idestado, COUNT(DISTINCT(ceca02idcurso)) AS Total 
FROM ceca02actividadtutor_'.$idContPeraca.' AS TB
WHERE ceca02idperaca='.$idPeraca.' AND ceca02idestado=1 AND ceca02fecharetro<'.$iHoy.' AND ceca02idcurso IN ('.$sCursos.') AND ceca02momentoest IN(0,1)
GROUP BY ceca02idestado';
					if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Revisando cursos con actividades vencidas : '.$sSQL.'<br>';}
					$tabla2=$objDB->ejecutasql($sSQL);
					if ($objDB->nf($tabla2)>0){
						$fila2=$objDB->sf($tabla2);
						$iEst1=$fila2['Total'];
						}
					if ($bVencido75){
						//ahora listar los que no han reportado 75
						$sSQL='SELECT COUNT(DISTINCT(ceca02idcurso)) AS Total 
FROM ceca02actividadtutor_'.$idContPeraca.' AS TB
WHERE ceca02idperaca='.$idPeraca.' AND ceca02idcurso IN ('.$sCursos.') AND ceca02idestado<>8 AND ceca02momentoest IN(0,1)';
						$tabla2=$objDB->ejecutasql($sSQL);
						if ($objDB->nf($tabla2)>0){
							$fila2=$objDB->sf($tabla2);
							$iEst7=$fila2['Total'];
							}
						}
					if ($iEst1!=0){
						$sRes=$sRes.$sTabulador.$sTabulador.'Cursos con actividadades iniciales vencidas: <span class="rojo">'.$iEst1.'</span>'.html_salto();
						}
					if ($iEst7!=0){
						$sRes=$sRes.$sTabulador.$sTabulador.'Cursos que no han reportado 75 %: <span class="rojo">'.$iEst7.'</span>'.html_salto();
						}
					if (($iEst1+$iEst7+$iEst8)==0){
						$sRes=$sRes.$sTabulador.$sTabulador.'<span class="verde">No se detectan demoras en el proceso de calificaci&oacute;n</span>'.html_salto();
						}
					}
				}
				}
			*/
			}
		}
	$sRes=$sRes.'<input id="paginaf2415" name="paginaf2415" type="hidden" value="'.$pagina.'"/><input id="lppf2415" name="lppf2415" type="hidden" value="'.$lineastabla.'"/>';
	return array(utf8_encode($sRes), $sDebug);
	}
function f2415_HtmlTabla($aParametros){
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
	list($sDetalle, $sDebugTabla)=f2415_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f2415detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f2415_db_CargarPadre($DATA, $objDB, $bDebug=false){}
function f2415_db_GuardarV2($DATA, $objDB, $bDebug=false){}
function f2415_db_Eliminar($id2415, $objDB, $bDebug=false){}
function f2415_TituloBusqueda(){
	return 'Busqueda de Resumen ejecutivo';
	}
function f2415_ParametrosBusqueda(){
	$sParams='<label class="Label90">Nombre</label><label><input id="b2415nombre" name="b2415nombre" type="text" value="" onchange="paginarbusqueda()" /></label>';
	return $sParams;
	}
function f2415_JavaScriptBusqueda($iModuloBusca){
	$sRes='var sCampo=window.document.frmedita.scampobusca.value;
var params=new Array();
params[100]=sCampo;
params[101]=window.document.frmedita.paginabusqueda.value;
params[102]=window.document.frmedita.lppfbusqueda.value;
params[103]=window.document.frmedita.b2415nombre.value;
xajax_f'.$iModuloBusca.'_HtmlBusqueda(params);';
	return $sRes;
	}
function f2415_TablaDetalleBusquedas($aParametros, $objDB){}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------
?>