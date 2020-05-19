<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2019 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.23.1 viernes, 5 de abril de 2019
--- 2451 core05actividades
*/
/** Archivo lib2451.php.
* Libreria 2451 core05actividades.
* @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
* @date viernes, 5 de abril de 2019
*/
function f2451_HTMLComboV2_core05idfase($objDB, $objCombos, $valor){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('core05idfase', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	$objCombos->sAccion='RevisaLlave();';
	$sSQL='SELECT ofer02id AS id, ofer02nombre AS nombre FROM ofer02cursofase';
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f2451_HTMLComboV2_core05idunidad($objDB, $objCombos, $valor){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('core05idunidad', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	$objCombos->sAccion='RevisaLlave();';
	$sSQL='SELECT ofer03id AS id, ofer03nombre AS nombre FROM ofer03cursounidad';
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f2451_HTMLComboV2_core05idactividad($objDB, $objCombos, $valor){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('core05idactividad', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	$objCombos->sAccion='RevisaLlave();';
	$sSQL='SELECT ofer04id AS id, ofer04nombre AS nombre FROM ofer04cursoactividad';
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f2451_HTMLComboV2_core05peraca($objDB, $objCombos, $valor, $vrcore05idtercero){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$sIds='-99';
	if ((int)$vrcore05idtercero!=0){
		$sSQL='SELECT core16peraca FROM core16actamatricula WHERE core16tercero='.$vrcore05idtercero.'';
		$tabla=$objDB->ejecutasql($sSQL);
		while($fila=$objDB->sf($tabla)){
			$sIds=$sIds.','.$fila['core16peraca'];
			}
		}
	$sWhere='exte02id IN ('.$sIds.')';
	$objCombos->nuevo('core05peraca', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	$objCombos->sAccion='carga_combo_core05idcurso()';
	$sSQL=f146_ConsultaCombo($sWhere, $objDB);
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f2451_HTMLComboV2_core05idcurso($objDB, $objCombos, $valor, $vrcore05idtercero, $vrcore05peraca){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$sIds='-99';
	$sBase='';
	$bConTodos=false;
	if (((int)$vrcore05idtercero!=0)&&((int)$vrcore05peraca!=0)){
		$bConTodos=true;
		list($iContenedor, $sError)=f1011_BloqueTercero($vrcore05idtercero, $objDB);
		$sSQL='SELECT core04idcurso FROM core04matricula_'.$iContenedor.' WHERE core04peraca='.$vrcore05peraca.' AND core04tercero='.$vrcore05idtercero.'';
		$tabla=$objDB->ejecutasql($sSQL);
		while($fila=$objDB->sf($tabla)){
			$sIds=$sIds.','.$fila['core04idcurso'];
			}
		}
	$sWhere=' WHERE unad40id IN ('.$sIds.')';
	$objCombos->nuevo('core05idcurso', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	$objCombos->sAccion='paginarf2451()';
	if ($bConTodos){
		$objCombos->addItem('-1', '{'.$ETI['msg_todos'].'}');
		}
	$sSQL='SELECT unad40id AS id, CONCAT(unad40consec, " - ", unad40nombre) AS nombre FROM unad40curso'.$sWhere.' ORDER BY unad40id';
	//$sBase=$sSQL;
	$res=$objCombos->html($sSQL, $objDB);
	return $res.$sBase;
	}
function f2451_Combocore05peraca($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$objCombos=new clsHtmlCombos('n');
	$html_core05peraca=f2451_HTMLComboV2_core05peraca($objDB, $objCombos, '', $aParametros[0]);
	$html_core05idcurso=f2451_HTMLComboV2_core05idcurso($objDB, $objCombos, '', $aParametros[0], '');
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_core05peraca', 'innerHTML', $html_core05peraca);
	$objResponse->assign('div_core05idcurso', 'innerHTML', $html_core05idcurso);
	$objResponse->call('paginarf2451');
	return $objResponse;
	}
function f2451_Combocore05idcurso($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$objCombos=new clsHtmlCombos('n');
	$html_core05idcurso=f2451_HTMLComboV2_core05idcurso($objDB, $objCombos, '', $aParametros[0], $aParametros[1]);
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_core05idcurso', 'innerHTML', $html_core05idcurso);
	$objResponse->call('paginarf2451');
	return $objResponse;
	}
function f2451_ExisteDato($datos){
	if(!is_array($datos)){$datos=json_decode(str_replace('\"','"',$datos),true);}
	$_SESSION['u_ultimominuto']=iminutoavance();
	$bHayLlave=true;
	$core05idmatricula=numeros_validar($datos[1]);
	if ($core05idmatricula==''){$bHayLlave=false;}
	$core05idfase=numeros_validar($datos[2]);
	if ($core05idfase==''){$bHayLlave=false;}
	$core05idunidad=numeros_validar($datos[3]);
	if ($core05idunidad==''){$bHayLlave=false;}
	$core05idactividad=numeros_validar($datos[4]);
	if ($core05idactividad==''){$bHayLlave=false;}
	if ($bHayLlave){
		require './app.php';
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		$sSQL='SELECT core05idactividad FROM core05actividades WHERE core05idmatricula='.$core05idmatricula.' AND core05idfase='.$core05idfase.' AND core05idunidad='.$core05idunidad.' AND core05idactividad='.$core05idactividad.'';
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
function f2451_Busquedas($aParametros){
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2451='lg/lg_2451_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2451)){$mensajes_2451='lg/lg_2451_es.php';}
	require $mensajes_todas;
	require $mensajes_2451;
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
		case 'core05tercero':
		require $APP->rutacomun.'lib111.php';
		$sTabla=f111_TablaDetalleBusquedas($aParametrosB, $objDB);
		$sTitulo='Busqueda de terceros';
		$sParams=f111_ParametrosBusqueda();
		$sJavaBusqueda=f111_JavaScriptBusqueda(2451);
		break;
		case 'core05idtutor':
		require $APP->rutacomun.'lib111.php';
		$sTabla=f111_TablaDetalleBusquedas($aParametrosB, $objDB);
		$sTitulo='Busqueda de terceros';
		$sParams=f111_ParametrosBusqueda();
		$sJavaBusqueda=f111_JavaScriptBusqueda(2451);
		break;
		}
	$sTitulo='<h2>'.$ETI['titulo_2451'].' - '.$sTitulo.'</h2>';
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97titulo', 'innerHTML', $sTitulo);
	$objResponse->assign('div_97params', 'innerHTML', $sParams);
	$objResponse->assign('div_97tabla', 'innerHTML', $sTabla);
	$objResponse->setFunction('paginarbusqueda','',$sJavaBusqueda);
	$objResponse->call('expandesector(97)');
	return $objResponse;
	}
function f2451_HtmlBusqueda($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$sError='';
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$sDetalle='';
	switch($aParametros[100]){
		case 'core05tercero':
		require $APP->rutacomun.'lib111.php';
		$sDetalle=f111_TablaDetalleBusquedas($aParametros, $objDB);
		break;
		case 'core05idtutor':
		require $APP->rutacomun.'lib111.php';
		$sDetalle=f111_TablaDetalleBusquedas($aParametros, $objDB);
		break;
		}
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97tabla', 'innerHTML', $sDetalle);
	return $objResponse;
	}
function f2451_TablaDetalleV2($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2451='lg/lg_2451_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2451)){$mensajes_2451='lg/lg_2451_es.php';}
	require $mensajes_todas;
	require $mensajes_2451;
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	if (isset($aParametros[101])==0){$aParametros[101]=1;}
	if (isset($aParametros[102])==0){$aParametros[102]=20;}
	if (isset($aParametros[103])==0){$aParametros[103]='';}
	if (isset($aParametros[104])==0){$aParametros[104]='';}
	if (isset($aParametros[105])==0){$aParametros[105]='';}
	$idTercero=numeros_validar($aParametros[103]);
	$idPeraca=numeros_validar($aParametros[104]);
	$idCurso=numeros_validar($aParametros[105]);
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
	if ($idCurso==''){
		$sLeyenda='<div class="salto1px"></div>
<div class="GrupoCamposAyuda">
<b>Debe seleccionar un  estudiante, periodo y curso.</b>
<div class="salto1px"></div>
</div>';
		return array(utf8_encode($sLeyenda.'<input id="paginaf2451" name="paginaf2451" type="hidden" value="'.$pagina.'"/><input id="lppf2451" name="lppf2451" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
		}
	$sSQLadd='';
	$sSQLadd1='';
	list($iContenedor, $sError)=f1011_BloqueTercero($idTercero, $objDB);
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
	$sSQL='SELECT ofer28id, ofer28nombre FROM ofer28tipoactividad';
	$aTipoActividad=array();
	$tabla=$objDB->ejecutasql($sSQL);
	while($fila=$objDB->sf($tabla)){
		$aTipoActividad[$fila['ofer28id']]=$fila['ofer28nombre'];
		}
	$sTitulos='Matricula, Fase, Unidad, Actividad, Id, Peraca, Tercero, Curso, Aula, Grupo, Nav, Fechaapertura, Fechacierre, Fecharetro, Tutor, Tipoactividad, Puntaje75, Puntaje25, Nota, Fechanota, Acumula75, Acumula25, Retroalimentacion, Estado, Rezagado';
	$iHoy=fecha_DiaMod();
	$bPrimerCiclo=true;
	//Se puecen mostrar varios cursos
	$res='<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">';
	$sWhere='';
	if ($idCurso!='-1'){
		$sWhere=' AND TB.core04idcurso='.$idCurso.'';
		}
	$sSQL='SELECT TB.core04idcurso, T4.unad40titulo, T4.unad40nombre, TB.core04nota75, TB.core04nota25, TB.core04calificado, TB.core04fechaultacceso, TB.core04minultacceso 
FROM core04matricula_'.$iContenedor.' AS TB, unad40curso AS T4 
WHERE TB.core04peraca='.$idPeraca.' AND TB.core04tercero='.$idTercero.$sWhere.' 
AND TB.core04idcurso=T4.unad40id';
	if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta de cursos '.$sSQL.'<br>';}
	$tabla40=$objDB->ejecutasql($sSQL);
	while($fila40=$objDB->sf($tabla40)){
		$sErrConsulta='';
		$idCursoRev=$fila40['core04idcurso'];
		$sSQL='SELECT TB.core05idmatricula, T4.ofer04nombre, TB.core05id, TB.core05idcurso, TB.core05idaula, TB.core05idgrupo, TB.core05fechaapertura, TB.core05fechacierre, TB.core05fecharetro, TB.core05tipoactividad, TB.core05puntaje75, TB.core05puntaje25, TB.core05nota, TB.core05fechanota, TB.core05acumula75, TB.core05acumula25, TB.core05retroalimentacion, T24.ceca03nombre, TB.core05rezagado, TB.core05idfase, TB.core05idunidad, TB.core05idactividad, TB.core05peraca, TB.core05tercero, TB.core05idnav, TB.core05idtutor, TB.core05estado 
FROM core05actividades_'.$iContenedor.' AS TB, ofer04cursoactividad AS T4, ceca03estadoactividad AS T24 
WHERE '.$sSQLadd1.' TB.core05tercero='.$idTercero.' AND TB.core05idcurso='.$idCursoRev.' AND TB.core05peraca='.$idPeraca.' AND TB.core05idactividad=T4.ofer04id AND TB.core05estado=T24.ceca03id '.$sSQLadd.'
ORDER BY TB.core05idgrupo, TB.core05fechacierre, TB.core05fechaapertura';
		if ($bPrimerCiclo){
			$sSQLlista=str_replace("'","|",$sSQL);
			$sSQLlista=str_replace('"',"|",$sSQLlista);
			$sErrConsulta='<input id="consulta_2451" name="consulta_2451" type="hidden" value="'.$sSQLlista.'"/>
<input id="titulos_2451" name="titulos_2451" type="hidden" value="'.$sTitulos.'"/>';
			}
		$tabladetalle=$objDB->ejecutasql($sSQL);
		if ($tabladetalle==false){
			if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 2451: '.$sSQL.'<br>';}
			$registros=0;
			$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
			//$sLeyenda=$sSQL;
			}else{
			$registros=$objDB->nf($tabladetalle);
			}
	/*
<td><b>'.$ETI['core05idfase'].'</b></td>
<td><b>'.$ETI['core05idunidad'].'</b></td>
	*/
	$sInfoPagina='';
	if ($bPrimerCiclo){
		$sInfoPagina='<input id="paginaf2451" name="paginaf2451" type="hidden" value="'.$pagina.'"/>
<input id="lppf2451" name="lppf2451" type="hidden" value="'.$lineastabla.'"/>';
		}
	if ($idCurso=='-1'){
		$sUltAcceso='';
		if ($fila40['core04fechaultacceso']!=0){
			$sUltAcceso=' - Acceso el '.formato_FechaLargaDesdeNumero($fila40['core04fechaultacceso']).' '.html_TablaHoraMinDesdeNumero($fila40['core04minultacceso']);
			}
		$res=$res.'<tr class="fondoazul">
<td colspan="9" align="center">'.$ETI['core05idcurso'].': <b>'.$fila40['unad40titulo'].' '.cadena_notildes($fila40['unad40nombre']).'</b>'.$sUltAcceso.'</td>
</tr>';
		}
	$res=$sErrConsulta.$sLeyenda.$res.'<tr class="fondoazul">
<td><b>'.$ETI['core05idactividad'].'</b></td>
<td><b>'.$ETI['core05fechaapertura'].'</b></td>
<td><b>'.$ETI['core05fechacierre'].'</b></td>
<td><b>'.$ETI['core05fecharetro'].'</b></td>
<td><b>'.$ETI['core05tipoactividad'].'</b></td>
<td colspan="2" style="min-width:100px"><b>'.$ETI['core05nota'].'</b></td>
<td><b>'.$ETI['core05fechanota'].'</b></td>
<td><b>'.$ETI['core05estado'].'</b>'.$sInfoPagina.'
</td>
</tr>';
	$tlinea=1;
	$idGrupo=-1;
	$iLineaPasa=0;
	$iFactor=(6/10);
	$sVerde=' bgcolor="#009933"';
	$sRojo=' bgcolor="#FF0000"';
	while($filadet=$objDB->sf($tabladetalle)){
		if ($idGrupo!=$filadet['core05idgrupo']){
			$idGrupo=$filadet['core05idgrupo'];
			list($sInfoGrupo, $sDebugG)=f2206_InfoGrupo($idPeraca, $filadet['core05idgrupo'], $objDB, 0, $bDebug);
			$res=$res.'<tr class="fondoazul">
<td colspan="11">'.$ETI['core05idaula'].': <b>'.$filadet['core05idaula'].'</b> '.$ETI['core05idgrupo'].': '.$sInfoGrupo.'</td>
</tr>';
			}
		$sPrefijo='';
		$sSufijo='';
		$sClass='';
		$sLink='';
		if ($filadet['core05rezagado']!=0){
			$sPrefijo='<b>';
			$sSufijo='</b>';
			}
		switch($filadet['core05estado']){
			case 7:
			case 3:
			$sPrefijo='<span class="verde">';
			$sSufijo='</span>';
			break;
			default:
			if ($filadet['core05fechaapertura']<=$iHoy){
				$sPrefijo='<b>';
				$sSufijo='</b>';
				if ($filadet['core05fecharetro']>=$iHoy){
					}else{
					$sPrefijo='<span class="rojo">';
					$sSufijo='</span>';
					}
				}
			break;
			}
		if(($tlinea%2)==0){$sClass=' class="resaltetabla"';}
		$tlinea++;
		$et_core05fechaapertura='';
		if ($filadet['core05fechaapertura']!=0){$et_core05fechaapertura=fecha_desdenumero($filadet['core05fechaapertura']);}
		$et_core05fechacierre='';
		if ($filadet['core05fechacierre']!=0){$et_core05fechacierre=fecha_desdenumero($filadet['core05fechacierre']);}
		$et_core05fecharetro='';
		if ($filadet['core05fecharetro']!=0){$et_core05fecharetro=fecha_desdenumero($filadet['core05fecharetro']);}
		$et_core05fechanota='';
		if ($filadet['core05fechanota']!=0){$et_core05fechanota=fecha_desdenumero($filadet['core05fechanota']);}
		$sNota=formato_numero($filadet['core05nota'], 0);
		$iTope=$filadet['core05puntaje75']+$filadet['core05puntaje25'];
		$sSemaforo='';
		if ($iTope>0){
			$sNota=$sNota.' / '.$iTope;
			$iLineaPasa=(int)($iTope*$iFactor);
			$sSemaforo=$sRojo;
			if ($filadet['core05nota']>=$iLineaPasa){
				$sSemaforo=$sVerde;
				}
			}
		if ($babierta){
			//$sLink='<a href="javascript:cargaridf2451('.$filadet['core05id'].')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$res=$res.'<tr'.$sClass.'>
<td>'.$sPrefijo.cadena_notildes($filadet['ofer04nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.$et_core05fechaapertura.$sSufijo.'&nbsp;&nbsp;</td>
<td>&nbsp;'.$sPrefijo.$et_core05fechacierre.$sSufijo.'&nbsp;&nbsp;</td>
<td>&nbsp;'.$sPrefijo.$et_core05fecharetro.$sSufijo.'</td>
<td>'.$sPrefijo.$aTipoActividad[$filadet['core05tipoactividad']].$sSufijo.'</td>
<td align="center">'.$sPrefijo.$sNota.$sSufijo.'</td>
<td'.$sSemaforo.'>&nbsp;&nbsp;&nbsp;</td>
<td>'.$sPrefijo.$et_core05fechanota.$sSufijo.'&nbsp;&nbsp;</td>
<td>'.$sPrefijo.cadena_notildes($filadet['ceca03nombre']).$sSufijo.'</td>
</tr>';
			}
		$bPrimerCiclo=false;
		$sLeyenda='';
		//Mostrar el avance.
		$res=$res.'<tr><td colspan="9">'.HTML_AvanceCurso($fila40['core04calificado'], $fila40['core04nota75']+$fila40['core04nota25']).'</td></tr>';
		}
	$res=$res.'</table>';
	//$objDB->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
	}
function f2451_HtmlTabla($aParametros){
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
	list($sDetalle, $sDebugTabla)=f2451_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f2451detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f2451_db_CargarPadre($DATA, $objDB, $bDebug=false){
	$sError='';
	$iTipoError=0;
	$sDebug='';
	require './app.php';
	$DATA['core05tercero_td']=$APP->tipo_doc;
	$DATA['core05tercero_doc']='';
	$DATA['core05idtutor_td']=$APP->tipo_doc;
	$DATA['core05idtutor_doc']='';
	if ($DATA['paso']==1){
		$sSQLcondi='core05idmatricula='.$DATA['core05idmatricula'].' AND core05idfase='.$DATA['core05idfase'].' AND core05idunidad='.$DATA['core05idunidad'].' AND core05idactividad='.$DATA['core05idactividad'].'';
		}else{
		$sSQLcondi='core05id='.$DATA['core05id'].'';
		}
	$sSQL='SELECT * FROM core05actividades WHERE '.$sSQLcondi;
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$DATA['core05idmatricula']=$fila['core05idmatricula'];
		$DATA['core05idfase']=$fila['core05idfase'];
		$DATA['core05idunidad']=$fila['core05idunidad'];
		$DATA['core05idactividad']=$fila['core05idactividad'];
		$DATA['core05id']=$fila['core05id'];
		$DATA['core05peraca']=$fila['core05peraca'];
		$DATA['core05tercero']=$fila['core05tercero'];
		$DATA['core05idcurso']=$fila['core05idcurso'];
		$DATA['core05idaula']=$fila['core05idaula'];
		$DATA['core05idgrupo']=$fila['core05idgrupo'];
		$DATA['core05idnav']=$fila['core05idnav'];
		$DATA['core05fechaapertura']=$fila['core05fechaapertura'];
		$DATA['core05fechacierre']=$fila['core05fechacierre'];
		$DATA['core05fecharetro']=$fila['core05fecharetro'];
		$DATA['core05idtutor']=$fila['core05idtutor'];
		$DATA['core05tipoactividad']=$fila['core05tipoactividad'];
		$DATA['core05puntaje75']=$fila['core05puntaje75'];
		$DATA['core05puntaje25']=$fila['core05puntaje25'];
		$DATA['core05nota']=$fila['core05nota'];
		$DATA['core05fechanota']=$fila['core05fechanota'];
		$DATA['core05acumula75']=$fila['core05acumula75'];
		$DATA['core05acumula25']=$fila['core05acumula25'];
		$DATA['core05retroalimentacion']=$fila['core05retroalimentacion'];
		$DATA['core05estado']=$fila['core05estado'];
		$DATA['core05rezagado']=$fila['core05rezagado'];
		$bcargo=true;
		$DATA['paso']=2;
		$DATA['boculta2451']=0;
		$bLimpiaHijos=true;
		}else{
		$DATA['paso']=0;
		}
	return array($DATA, $sError, $iTipoError, $sDebug);
	}
function f2451_db_GuardarV2($DATA, $objDB, $bDebug=false){
	$iCodModulo=2451;
	$bAudita[2]=true;
	$bAudita[3]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2451='lg/lg_2451_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2451)){$mensajes_2451='lg/lg_2451_es.php';}
	require $mensajes_todas;
	require $mensajes_2451;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	// -- Se inicia validando todas las posibles entradas de usuario.
	/*
	if (isset($DATA['core05idmatricula'])==0){$DATA['core05idmatricula']='';}
	if (isset($DATA['core05idfase'])==0){$DATA['core05idfase']='';}
	if (isset($DATA['core05idunidad'])==0){$DATA['core05idunidad']='';}
	if (isset($DATA['core05idactividad'])==0){$DATA['core05idactividad']='';}
	if (isset($DATA['core05id'])==0){$DATA['core05id']='';}
	if (isset($DATA['core05peraca'])==0){$DATA['core05peraca']='';}
	if (isset($DATA['core05tercero'])==0){$DATA['core05tercero']='';}
	if (isset($DATA['core05fechaapertura'])==0){$DATA['core05fechaapertura']='';}
	if (isset($DATA['core05fechacierre'])==0){$DATA['core05fechacierre']='';}
	if (isset($DATA['core05fecharetro'])==0){$DATA['core05fecharetro']='';}
	if (isset($DATA['core05puntaje75'])==0){$DATA['core05puntaje75']='';}
	if (isset($DATA['core05puntaje25'])==0){$DATA['core05puntaje25']='';}
	if (isset($DATA['core05nota'])==0){$DATA['core05nota']='';}
	if (isset($DATA['core05fechanota'])==0){$DATA['core05fechanota']='';}
	if (isset($DATA['core05acumula75'])==0){$DATA['core05acumula75']='';}
	if (isset($DATA['core05acumula25'])==0){$DATA['core05acumula25']='';}
	if (isset($DATA['core05retroalimentacion'])==0){$DATA['core05retroalimentacion']='';}
	*/
	$DATA['core05idmatricula']=numeros_validar($DATA['core05idmatricula']);
	$DATA['core05idfase']=numeros_validar($DATA['core05idfase']);
	$DATA['core05idunidad']=numeros_validar($DATA['core05idunidad']);
	$DATA['core05idactividad']=numeros_validar($DATA['core05idactividad']);
	$DATA['core05peraca']=numeros_validar($DATA['core05peraca']);
	$DATA['core05puntaje75']=numeros_validar($DATA['core05puntaje75']);
	$DATA['core05puntaje25']=numeros_validar($DATA['core05puntaje25']);
	$DATA['core05nota']=numeros_validar($DATA['core05nota'],true);
	$DATA['core05acumula75']=numeros_validar($DATA['core05acumula75']);
	$DATA['core05acumula25']=numeros_validar($DATA['core05acumula25']);
	$DATA['core05retroalimentacion']=htmlspecialchars(trim($DATA['core05retroalimentacion']));
	// -- Se inicializan las variables que puedan pasar vacias {Especialmente números}.
	//if ($DATA['core05peraca']==''){$DATA['core05peraca']=0;}
	if ($DATA['core05idcurso']==''){$DATA['core05idcurso']=0;}
	if ($DATA['core05idaula']==''){$DATA['core05idaula']=0;}
	if ($DATA['core05idgrupo']==''){$DATA['core05idgrupo']=0;}
	if ($DATA['core05idnav']==''){$DATA['core05idnav']=0;}
	if ($DATA['core05tipoactividad']==''){$DATA['core05tipoactividad']=0;}
	//if ($DATA['core05puntaje75']==''){$DATA['core05puntaje75']=0;}
	//if ($DATA['core05puntaje25']==''){$DATA['core05puntaje25']=0;}
	//if ($DATA['core05nota']==''){$DATA['core05nota']=0;}
	//if ($DATA['core05acumula75']==''){$DATA['core05acumula75']=0;}
	//if ($DATA['core05acumula25']==''){$DATA['core05acumula25']=0;}
	if ($DATA['core05rezagado']==''){$DATA['core05rezagado']=0;}
	// -- Seccion para validar los posibles causales de error.
	$sSepara=', ';
	if (true){
		//if ($DATA['core05retroalimentacion']==''){$sError=$ERR['core05retroalimentacion'].$sSepara.$sError;}
		if ($DATA['core05acumula25']==''){$sError=$ERR['core05acumula25'].$sSepara.$sError;}
		if ($DATA['core05acumula75']==''){$sError=$ERR['core05acumula75'].$sSepara.$sError;}
		if ($DATA['core05fechanota']==0){
			//$DATA['core05fechanota']=fecha_DiaMod();
			$sError=$ERR['core05fechanota'].$sSepara.$sError;
			}
		if ($DATA['core05nota']==''){$sError=$ERR['core05nota'].$sSepara.$sError;}
		if ($DATA['core05puntaje25']==''){$sError=$ERR['core05puntaje25'].$sSepara.$sError;}
		if ($DATA['core05puntaje75']==''){$sError=$ERR['core05puntaje75'].$sSepara.$sError;}
		if ($DATA['core05idtutor']==0){$sError=$ERR['core05idtutor'].$sSepara.$sError;}
		if ($DATA['core05fecharetro']==0){
			//$DATA['core05fecharetro']=fecha_DiaMod();
			$sError=$ERR['core05fecharetro'].$sSepara.$sError;
			}
		if ($DATA['core05fechacierre']==0){
			//$DATA['core05fechacierre']=fecha_DiaMod();
			$sError=$ERR['core05fechacierre'].$sSepara.$sError;
			}
		if ($DATA['core05fechaapertura']==0){
			//$DATA['core05fechaapertura']=fecha_DiaMod();
			$sError=$ERR['core05fechaapertura'].$sSepara.$sError;
			}
		if ($DATA['core05tercero']==0){$sError=$ERR['core05tercero'].$sSepara.$sError;}
		if ($DATA['core05peraca']==''){$sError=$ERR['core05peraca'].$sSepara.$sError;}
		//Fin de las valiaciones NO LLAVE.
		}
	//Valiaciones de campos obligatorios en todo guardar.
	if ($DATA['core05idactividad']==''){$sError=$ERR['core05idactividad'];}
	if ($DATA['core05idunidad']==''){$sError=$ERR['core05idunidad'];}
	if ($DATA['core05idfase']==''){$sError=$ERR['core05idfase'];}
	if ($DATA['core05idmatricula']==''){$sError=$ERR['core05idmatricula'];}
	// -- Se verifican los valores de campos de otras tablas.
	if ($sError==''){$sError=tabla_terceros_existe($DATA['core05idtutor_td'], $DATA['core05idtutor_doc'], $objDB, 'El tercero Tutor ');}
	if ($sError==''){
		list($sError, $sInfo)=tercero_Bloqueado($DATA['core05idtutor'], $objDB);
		if ($sInfo!=''){$sError=$sError.'<br>'.sInfo;}
		}
	if ($sError==''){$sError=tabla_terceros_existe($DATA['core05tercero_td'], $DATA['core05tercero_doc'], $objDB, 'El tercero Tercero ');}
	if ($sError==''){
		list($sError, $sInfo)=tercero_Bloqueado($DATA['core05tercero'], $objDB);
		if ($sInfo!=''){$sError=$sError.'<br>'.sInfo;}
		}
	if ($sError==''){
		if ($DATA['paso']==10){
			$sSQL='SELECT core05idmatricula FROM core05actividades WHERE core05idmatricula='.$DATA['core05idmatricula'].' AND core05idfase='.$DATA['core05idfase'].' AND core05idunidad='.$DATA['core05idunidad'].' AND core05idactividad='.$DATA['core05idactividad'].'';
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
			$DATA['core05id']=tabla_consecutivo('core05actividades','core05id', '', $objDB);
			if ($DATA['core05id']==-1){$sError=$objDB->serror;}
			}
		}
	if ($sError==''){
		if (get_magic_quotes_gpc()==1){$DATA['core05retroalimentacion']=stripslashes($DATA['core05retroalimentacion']);}
		//Si el campo core05retroalimentacion permite html quite la linea htmlspecialchars para el campo y habilite la siguiente linea:
		//$core05retroalimentacion=addslashes($DATA['core05retroalimentacion']);
		$core05retroalimentacion=str_replace('"', '\"', $DATA['core05retroalimentacion']);
		$bpasa=false;
		if ($DATA['paso']==10){
			$DATA['core05idcurso']=0;
			$DATA['core05idaula']=0;
			$DATA['core05idgrupo']=0;
			$DATA['core05idnav']=0;
			$core05fechaapertura=fecha_DiaMod();
			$core05fechacierre=fecha_DiaMod();
			$core05fecharetro=fecha_DiaMod();
			//$DATA['core05idtutor']=0; //$_SESSION['u_idtercero'];
			$DATA['core05tipoactividad']=0;
			$core05fechanota=fecha_DiaMod();
			$DATA['core05estado']=0;
			$DATA['core05rezagado']=0;
			$sCampos2451='core05idmatricula, core05idfase, core05idunidad, core05idactividad, core05id, core05peraca, core05tercero, core05idcurso, core05idaula, core05idgrupo, 
core05idnav, core05fechaapertura, core05fechacierre, core05fecharetro, core05idtutor, core05tipoactividad, core05puntaje75, core05puntaje25, core05nota, core05fechanota, 
core05acumula75, core05acumula25, core05retroalimentacion, core05estado, core05rezagado';
			$sValores2451=''.$DATA['core05idmatricula'].', '.$DATA['core05idfase'].', '.$DATA['core05idunidad'].', '.$DATA['core05idactividad'].', '.$DATA['core05id'].', '.$DATA['core05peraca'].', '.$DATA['core05tercero'].', '.$DATA['core05idcurso'].', '.$DATA['core05idaula'].', '.$DATA['core05idgrupo'].', 
'.$DATA['core05idnav'].', "'.$DATA['core05fechaapertura'].'", "'.$DATA['core05fechacierre'].'", "'.$DATA['core05fecharetro'].'", '.$DATA['core05idtutor'].', '.$DATA['core05tipoactividad'].', '.$DATA['core05puntaje75'].', '.$DATA['core05puntaje25'].', '.$DATA['core05nota'].', "'.$DATA['core05fechanota'].'", 
'.$DATA['core05acumula75'].', '.$DATA['core05acumula25'].', "'.$core05retroalimentacion.'", '.$DATA['core05estado'].', '.$DATA['core05rezagado'].'';
			if ($APP->utf8==1){
				$sSQL='INSERT INTO core05actividades ('.$sCampos2451.') VALUES ('.utf8_encode($sValores2451).');';
				$sdetalle=$sCampos2451.'['.utf8_encode($sValores2451).']';
				}else{
				$sSQL='INSERT INTO core05actividades ('.$sCampos2451.') VALUES ('.$sValores2451.');';
				$sdetalle=$sCampos2451.'['.$sValores2451.']';
				}
			$idaccion=2;
			$bpasa=true;
			}else{
			$scampo[1]='core05peraca';
			$scampo[2]='core05tercero';
			$scampo[3]='core05fechaapertura';
			$scampo[4]='core05fechacierre';
			$scampo[5]='core05fecharetro';
			$scampo[6]='core05puntaje75';
			$scampo[7]='core05puntaje25';
			$scampo[8]='core05nota';
			$scampo[9]='core05fechanota';
			$scampo[10]='core05acumula75';
			$scampo[11]='core05acumula25';
			$scampo[12]='core05retroalimentacion';
			$sdato[1]=$DATA['core05peraca'];
			$sdato[2]=$DATA['core05tercero'];
			$sdato[3]=$DATA['core05fechaapertura'];
			$sdato[4]=$DATA['core05fechacierre'];
			$sdato[5]=$DATA['core05fecharetro'];
			$sdato[6]=$DATA['core05puntaje75'];
			$sdato[7]=$DATA['core05puntaje25'];
			$sdato[8]=$DATA['core05nota'];
			$sdato[9]=$DATA['core05fechanota'];
			$sdato[10]=$DATA['core05acumula75'];
			$sdato[11]=$DATA['core05acumula25'];
			$sdato[12]=$core05retroalimentacion;
			$numcmod=12;
			$sWhere='core05id='.$DATA['core05id'].'';
			$sSQL='SELECT * FROM core05actividades WHERE '.$sWhere;
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
					$sSQL='UPDATE core05actividades SET '.utf8_encode($sdatos).' WHERE '.$sWhere.';';
					}else{
					$sdetalle=$sdatos.'['.$sWhere.']';
					$sSQL='UPDATE core05actividades SET '.$sdatos.' WHERE '.$sWhere.';';
					}
				$idaccion=3;
				}
			}
		if ($bpasa){
			$result=$objDB->ejecutasql($sSQL);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' [2451] ..<!-- '.$sSQL.' -->';
				if ($idaccion==2){$DATA['core05id']='';}
				$DATA['paso']=$DATA['paso']-10;
				}else{
				if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Guardar 2451 '.$sSQL.'<br>';}
				if ($bAudita[$idaccion]){seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], $idaccion, $DATA['core05id'], $sdetalle, $objDB);}
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
function f2451_db_Eliminar($core05id, $objDB, $bDebug=false){
	$iCodModulo=2451;
	$bAudita[4]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2451='lg/lg_2451_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2451)){$mensajes_2451='lg/lg_2451_es.php';}
	require $mensajes_todas;
	require $mensajes_2451;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	$core05id=numeros_validar($core05id);
	// Traer los datos para hacer las validaciones.
	if ($sError==''){
		$sSQL='SELECT * FROM core05actividades WHERE core05id='.$core05id.'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$filabase=$objDB->sf($tabla);
			}else{
			$sError='No se encuentra el registro solicitado {Ref: '.$core05id.'}';
			}
		}
	if ($sError==''){
		if (!seg_revisa_permiso($iCodModulo, 4, $objDB)){
			$sError=$ERR['4'];
			}
		}
	if ($sError==''){
		$sSQL='SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=2451';
		$tablaor=$objDB->ejecutasql($sSQL);
		while ($filaor=$objDB->sf($tablaor)){
			$sSQL='SELECT '.$filaor['campoid'].' FROM '.$filaor['tabla'].' WHERE '.$filaor['camporev'].'='.$_REQUEST['core05id'].' LIMIT 0, 1';
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
		$sWhere='core05id='.$core05id.'';
		//$sWhere='core05idactividad='.$filabase['core05idactividad'].' AND core05idunidad='.$filabase['core05idunidad'].' AND core05idfase='.$filabase['core05idfase'].' AND core05idmatricula='.$filabase['core05idmatricula'].'';
		$sSQL='DELETE FROM core05actividades WHERE '.$sWhere.';';
		$result=$objDB->ejecutasql($sSQL);
		if ($result==false){
			$sError=$ERR['falla_eliminar'].' .. <!-- '.$sSQL.' -->';
			}else{
			if ($bAudita[4]){seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $core05id, $sWhere, $objDB);}
			}
		}
	return array($sError, $iTipoError, $sDebug);
	}
function f2451_TituloBusqueda(){
	return 'Busqueda de Actividades por estudiante';
	}
function f2451_ParametrosBusqueda(){
	$sParams='<label class="Label90">Nombre</label><label><input id="b2451nombre" name="b2451nombre" type="text" value="" onchange="paginarbusqueda()" /></label>';
	return $sParams;
	}
function f2451_JavaScriptBusqueda($iModuloBusca){
	$sRes='var sCampo=window.document.frmedita.scampobusca.value;
var params=new Array();
params[100]=sCampo;
params[101]=window.document.frmedita.paginabusqueda.value;
params[102]=window.document.frmedita.lppfbusqueda.value;
params[103]=window.document.frmedita.b2451nombre.value;
xajax_f'.$iModuloBusca.'_HtmlBusqueda(params);';
	return $sRes;
	}
function f2451_TablaDetalleBusquedas($aParametros, $objDB){
	$res='';
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2451='lg/lg_2451_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2451)){$mensajes_2451='lg/lg_2451_es.php';}
	require $mensajes_todas;
	require $mensajes_2451;
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
	$sTitulos='Matricula, Fase, Unidad, Actividad, Id, Peraca, Tercero, Curso, Aula, Grupo, Nav, Fechaapertura, Fechacierre, Fecharetro, Tutor, Tipoactividad, Puntaje75, Puntaje25, Nota, Fechanota, Acumula75, Acumula25, Retroalimentacion, Estado, Rezagado';
	$sSQL='SELECT TB.core05idmatricula, T2.ofer02nombre, T3.ofer03nombre, T4.ofer04nombre, TB.core05id, T6.exte02nombre, T7.unad11razonsocial AS C7_nombre, TB.core05idcurso, TB.core05idaula, TB.core05idgrupo, T11.unad39nombre, TB.core05fechaapertura, TB.core05fechacierre, TB.core05fecharetro, T15.unad11razonsocial AS C15_nombre, TB.core05tipoactividad, TB.core05puntaje75, TB.core05puntaje25, TB.core05nota, TB.core05fechanota, TB.core05acumula75, TB.core05acumula25, TB.core05retroalimentacion, T24.ceca03nombre, TB.core05rezagado, TB.core05idfase, TB.core05idunidad, TB.core05idactividad, TB.core05peraca, TB.core05tercero, T7.unad11tipodoc AS C7_td, T7.unad11doc AS C7_doc, TB.core05idnav, TB.core05idtutor, T15.unad11tipodoc AS C15_td, T15.unad11doc AS C15_doc, TB.core05estado 
FROM core05actividades AS TB, ofer02cursofase AS T2, ofer03cursounidad AS T3, ofer04cursoactividad AS T4, exte02per_aca AS T6, unad11terceros AS T7, unad39nav AS T11, unad11terceros AS T15, ceca03estadoactividad AS T24 
WHERE '.$sSQLadd1.' TB.core05idmatricula='.$nada.' AND TB.core05idfase=T2.ofer02id AND TB.core05idunidad=T3.ofer03id AND TB.core05idactividad=T4.ofer04id AND TB.core05peraca=T6.exte02id AND TB.core05tercero=T7.unad11id AND TB.core05idnav=T11.unad39id AND TB.core05idtutor=T15.unad11id AND TB.core05estado=T24.ceca03id '.$sSQLadd.'
ORDER BY TB.core05idfase, TB.core05idunidad, TB.core05idactividad';
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
			//return array(utf8_encode($sErrConsulta.'<input id="paginaf2451" name="paginaf2451" type="hidden" value="'.$pagina.'"/><input id="lppf2451" name="lppf2451" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
<td><b>'.$ETI['core05idfase'].'</b></td>
<td><b>'.$ETI['core05idunidad'].'</b></td>
<td><b>'.$ETI['core05idactividad'].'</b></td>
<td><b>'.$ETI['core05peraca'].'</b></td>
<td colspan="2"><b>'.$ETI['core05tercero'].'</b></td>
<td><b>'.$ETI['core05idcurso'].'</b></td>
<td><b>'.$ETI['core05idaula'].'</b></td>
<td><b>'.$ETI['core05idgrupo'].'</b></td>
<td><b>'.$ETI['core05idnav'].'</b></td>
<td><b>'.$ETI['core05fechaapertura'].'</b></td>
<td><b>'.$ETI['core05fechacierre'].'</b></td>
<td><b>'.$ETI['core05fecharetro'].'</b></td>
<td colspan="2"><b>'.$ETI['core05idtutor'].'</b></td>
<td><b>'.$ETI['core05tipoactividad'].'</b></td>
<td><b>'.$ETI['core05puntaje75'].'</b></td>
<td><b>'.$ETI['core05puntaje25'].'</b></td>
<td><b>'.$ETI['core05nota'].'</b></td>
<td><b>'.$ETI['core05fechanota'].'</b></td>
<td><b>'.$ETI['core05acumula75'].'</b></td>
<td><b>'.$ETI['core05acumula25'].'</b></td>
<td><b>'.$ETI['core05retroalimentacion'].'</b></td>
<td><b>'.$ETI['core05estado'].'</b></td>
<td><b>'.$ETI['core05rezagado'].'</b></td>
<td align="right">
'.html_paginador('paginabusqueda', $registros, $lineastabla, $pagina, 'paginarbusqueda()').'
'.html_lpp('lppfbusqueda', $lineastabla, 'paginarbusqueda()').'
</td>
</tr>';
	$tlinea=1;
	while($filadet=$objDB->sf($tabladetalle)){
		$sPrefijo='<a href="javascript:Devuelve(\''.$filadet['core05id'].'\');">';
		$sSufijo='</a>';
		$tlinea++;
		$et_core05fechaapertura='';
		if ($filadet['core05fechaapertura']!=0){$et_core05fechaapertura=fecha_desdenumero($filadet['core05fechaapertura']);}
		$et_core05fechacierre='';
		if ($filadet['core05fechacierre']!=0){$et_core05fechacierre=fecha_desdenumero($filadet['core05fechacierre']);}
		$et_core05fecharetro='';
		if ($filadet['core05fecharetro']!=0){$et_core05fecharetro=fecha_desdenumero($filadet['core05fecharetro']);}
		$et_core05fechanota='';
		if ($filadet['core05fechanota']!=0){$et_core05fechanota=fecha_desdenumero($filadet['core05fechanota']);}
		$res=$res.'<tr onmouseover="cambia_color_over(this);" onmouseout="cambia_color_out(this);">
<td>'.$sPrefijo.cadena_notildes($filadet['ofer02nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['ofer03nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['ofer04nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['exte02nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['C7_td'].' '.$filadet['C7_doc'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['C7_nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['core05idcurso'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['core05idaula'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['core05idgrupo'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['unad39nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.$et_core05fechaapertura.$sSufijo.'</td>
<td>'.$sPrefijo.$et_core05fechacierre.$sSufijo.'</td>
<td>'.$sPrefijo.$et_core05fecharetro.$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['C15_td'].' '.$filadet['C15_doc'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['C15_nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['core05tipoactividad'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['core05puntaje75'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['core05puntaje25'].$sSufijo.'</td>
<td align="right">'.$sPrefijo.formato_moneda($filadet['core05nota']).$sSufijo.'</td>
<td>'.$sPrefijo.$et_core05fechanota.$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['core05acumula75'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['core05acumula25'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['core05retroalimentacion'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['ceca03nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['core05rezagado'].$sSufijo.'</td>
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