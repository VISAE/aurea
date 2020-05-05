<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2015 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.9.2 sábado, 01 de agosto de 2015
--- Modelo Versión 2.9.2b sábado, 22 de agosto de 2015
--- Jueves, 8 de Octubre de 2015 se agrengan aulas H Y S
--- Modelo Versión 2.21.0 martes, 17 de abril de 2018
*/
function f17_BotonesLab($iNumLab){
	$res='<div class="ir_derecha" style="width:121px;">
<label class="Label30">
<input id="bayudalab'.$iNumLab.'" name="bayudalab'.$iNumLab.'" type="button" value="Ayuda" class="btMiniAyuda" onclick="ayudalab();" title="Ir a la ayuda">
</label>
<label class="Label30">
<input id="btexplab'.$iNumLab.'" name="btexplab'.$iNumLab.'" type="button" value="Mostrar" class="btMiniExpandir" onclick="expandelab('.$iNumLab.',\'block\');" title="Mostrar" style="display:block"/>
</label>
<label class="Label30">
<input id="btreclab'.$iNumLab.'" name="btreclab'.$iNumLab.'" type="button" value="Ocultar" class="btMiniRecoger" onclick="expandelab('.$iNumLab.',\'none\');" title="Ocultar" style="display:none"/>
</label>
</div>';
	return $res;
	}
function f17_TablaAgenda($params, $objDB, $bDebug=false){
	$sDebug='';
	$mensajes_17='lg/lg_17_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_17)){$mensajes_17='lg/lg_17_es.php';}
	require $mensajes_17;
	$sIdent=array('','A','B','C','D','E','F','G','H','','0','','','','','5','','','','','S');
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	if (isset($params[100])==0){$params[100]=$_SESSION['unad_id_tercero'];}
	if (isset($params[101])==0){$params[101]=1;}
	if (isset($params[102])==0){$params[102]=10;}
	if (isset($params[103])==0){$params[103]=1;}
	if (isset($params[104])==0){$params[104]=5;}
	if (isset($params[105])==0){$params[105]=5;}
	if (isset($params[106])==0){$params[106]='';}
	$idTercero=$params[100];
	$pagina=$params[101];
	$lineastabla=$params[102];
	$paginav=$params[103];
	$lineastablav=$params[104];
	$sSQLadd='';
	$bConVencidas=false;
	//if (isset($params[103])==0){$params[103]='';}
	//if ((int)$params[103]!=-1){$sSQLadd=$sSQLadd.' AND TB.campo='.$params[103];}
	if ((int)$params[105]==0){$params[105]=5;}
	if ($params[106]!=''){
		$aVal=explode('_',$params[106]);
		$iNumAula=$aVal[2];
		$sSQLadd=$sSQLadd.' AND TB.unad47peraca='.$aVal[0].' AND TB.unad47idcurso='.$aVal[1].' AND TB.unad47numaula='.$iNumAula.'';
		}
	$sHoy=fecha_hoy();
	$sListaLlaves='';
	$sMostrar='';
	$sSQL='SELECT unad47peraca, unad47idcurso, unad47numaula, unad47idgrupo, unad47idceadasiste 
FROM unad47tablero AS TB 
WHERE unad47idtercero='.$idTercero.' AND unad47idrol='.$params[105].' AND unad47activo="S" AND unad47retirado="N"'.$sSQLadd;
	$tabla=$objDB->ejecutasql($sSQL);
	while($fila=$objDB->sf($tabla)){
		if ($sListaLlaves!=''){$sListaLlaves=$sListaLlaves.', ';}
		$sListaLlaves=$sListaLlaves.'"'.$fila['unad47peraca'].'_'.$fila['unad47idcurso'].'_'.$fila['unad47numaula'].'"';
		}
	//$sMostrar=$sSQL;
	if ($sListaLlaves==''){
		/* if ($_SESSION['unad_id_tercero']==4){
			$sMostrar=$sSQL;
			} */
		$sListaLlaves='"nada_que_ver"';
		}
	$sTitulos='';
	$sVigentes='AND STR_TO_DATE(TB.ofer18fechacierrre, "%d/%m/%Y")>=STR_TO_DATE("'.$sHoy.'", "%d/%m/%Y") ORDER BY STR_TO_DATE(TB.ofer18fechacierrre, "%d/%m/%Y")';
	$sVencidas='AND STR_TO_DATE(TB.ofer18fechacierrre, "%d/%m/%Y")<STR_TO_DATE("'.$sHoy.'", "%d/%m/%Y") ORDER BY STR_TO_DATE(TB.ofer18fechacierrre, "%d/%m/%Y") DESC';
	$sSQLBase='SELECT TB.ofer18per_aca, TB.ofer18curso, TB.ofer18numaula, TB.ofer18fase, TB.ofer18unidad, TB.ofer18idactividad, T2.ofer02nombre, T3.ofer03nombre, T1.ofer04nombre, TB.ofer18fechainicio, TB.ofer18fechacierrre, TB.ofer18peso, TB.ofer18detalle, T1.ofer04detalle, T4.unad40nombre 
FROM ofer18cargaxnavxdia AS TB, ofer04cursoactividad AS T1, ofer02cursofase AS T2, ofer03cursounidad AS T3, unad40curso AS T4 
WHERE TB.ofer18idactividad=T1.ofer04id AND TB.ofer18fase=T2.ofer02id AND TB.ofer18unidad=T3.ofer03id AND TB.ofer18curso=T4.unad40id AND CONCAT(TB.ofer18per_aca,"_",TB.ofer18curso,"_",TB.ofer18numaula) IN ('.$sListaLlaves.')';
	$sSQL=$sSQLBase.$sVigentes;
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_14" name="consulta_14" type="hidden" value="'.$sSQLlista.'"/>
<input id="titulos_14" name="titulos_14" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objDB->ejecutasql($sSQL);
	if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Agenda: Trae las actividades vigentes<br>';}
	//$tablavencidas=$objDB->ejecutasql($sSQLBase.$sVencidas);
	if ($tabladetalle==false){
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		}else{
		$registros=$objDB->nf($tabladetalle);
		if ($registros==0){
			//return utf8_encode($sErrConsulta.'<input id="paginaf18" name="paginaf18" type="hidden" value="'.$pagina.'"/><input id="lppf18" name="lppf18" type="hidden" value="'.$lineastabla.'"/>');
			//break;
			}
		if ((($registros-1)/$lineastabla)<($pagina-1)){$pagina=(int)(($registros-1)/$lineastabla)+1;}
		if ($registros>$lineastabla){
			$rbase=($pagina-1)*$lineastabla;
			$limite=' LIMIT '.$rbase.', '.$lineastabla;
			$tabladetalle=$objDB->ejecutasql($sSQL.$limite);
			}
		}
	/*
	if ($_SESSION['unad_id_tercero']==4){
		$sMostrar=$sMostrar.' '.$sSQL.' ';
		}
		*/
	$res=$sErrConsulta.$sMostrar.'<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">
<tr style="background-color:#00314A;color:#FFFFFF">
<td colspan="3">
<div class="MarquesinaMedia" style="margin:2px 10px;"><img src="img/unagenda2.png" border="0" height="24" width="24" title="UNAgenDa" align="left" alt="UNAgenDa">&nbsp;UNAgenDa<a href="#" alt="UNAgenDa - Agenda dinamica para estudiantes, consultela para estar al tanto de las actividades de sus cursos">&nbsp;</a></div>
</td>
</tr>
<tr class="fondoazul">
<td>'.$ETI['msg_proximas'].'</td>
<td colspan="2" align="right">
'.html_paginador("paginaf14", $registros, $lineastabla, $pagina, "paginarf14()").'
'.html_lpp("lppf14", $lineastabla, "paginarf14()").'
</td>
</tr>';
	$tlinea=1;
	$babierta=false;
	$sCurso='';
	//$sHoy=fecha_hoy();
	while($filadet=$objDB->sf($tabladetalle)){
		//ofer18per_aca, TB.ofer18curso, TB.ofer18numaula
		
		$sMuestra=$filadet['ofer18curso'].$sIdent[$filadet['ofer18numaula']].'_'.$filadet['ofer18per_aca'];
		if ($sCurso!=$sMuestra){
			$sCurso=$sMuestra;
			//'.$ETI['msg_curso'].' 
			$res=$res.'<tr class="fondoazul">
<td colspan="3" align="center"><a href="#" alt="Curso '.$sMuestra.'">&nbsp;</a><b>'.$sMuestra.'</b> '.cadena_notildes($filadet['unad40nombre']).'</td>
</tr>';
			}
		$sPrefijo='';
		$sSufijo='';
		$sClass='';
		$sLink='';
		$sEstilo='';
		$sClass2='';
		$iDias=fecha_numdiasentrefechas($sHoy,$filadet['ofer18fechacierrre']);
		switch ($iDias){
			case 0:
			case 1:
			case 2:
			$sClass2=' style="color:#FFFFFF;background-color:#E51323"';
			break;
			case 3:
			case 4:
			case 5:
			case 6:
			$sClass2=' style="background-color:#FFFF44"';
			break;
			default:
			$iDias=fecha_numdiasentrefechas($filadet['ofer18fechainicio'], $sHoy);
			if ($iDias>-1){
				$sClass2=' style="background-color:#00DD00"';
				}
			}
		if ($filadet['ofer18fechacierrre']==$sHoy){
			$sPrefijo='<b>';
			$sSufijo='</b>';
			}
		if(($tlinea%2)==0){$sClass='';}
		$tlinea++;
		if ($babierta){
			//$sLink='<a href="javascript:cargadato('."'".''."'".')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$sDetalle=cadena_notildes($filadet['ofer04detalle']);
		$res=$res.'<tr class="resaltetabla">
<td'.$sClass2.'><a href="#" alt="'.cadena_notildes($filadet['ofer04nombre']).'">&nbsp;</a>'.cadena_notildes($filadet['ofer04nombre']).'</td>
<td'.$sClass2.'><b><a href="#" alt="Fecha de cierre '.formato_fechalarga($filadet['ofer18fechacierrre']).'">&nbsp;</a>'.formato_fechalarga($filadet['ofer18fechacierrre']).'</b></td>
<td>'.$sLink.'</td>
</tr><tr>
<td colspan="3"'.$sClass2.'><a href="#" alt="'.$sDetalle.'">&nbsp;</a>'.$sDetalle.'</td>
</tr>';
		}
	if ($registros==0){
		//El mensaje de que no tiene actividades pendientes.
		$res=$res.'<tr><td colspan="3"><a href="#" alt="No se registran actividades pendientes">&nbsp;</a>'.$ETI['msg_nopendientes'].'</td></tr>';
		}
	//Procesar la tabla de vencidas.
	if ($bConVencidas){
	$tabladetalle=$objDB->ejecutasql($sSQLBase.$sVencidas);
	if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Agenda: Trae las actividades vencidas<br>';}
	if ($tabladetalle==false){
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		}else{
		$registros=$objDB->nf($tabladetalle);
		if ($registros==0){
			//return utf8_encode($sErrConsulta.'<input id="paginaf15" name="paginaf15" type="hidden" value="'.$paginav.'"/><input id="lppf15" name="lppf15" type="hidden" value="'.$lineastablav.'"/>');
			//break;
			}
		if ((($registros-1)/$lineastablav)<($paginav-1)){$paginav=(int)(($registros-1)/$lineastablav)+1;}
		if ($registros>$lineastablav){
			$rbase=($paginav-1)*$lineastablav;
			$limite=' LIMIT '.$rbase.', '.$lineastablav;
			$tabladetalle=$objDB->ejecutasql($sSQLBase.$sVencidas.$limite);
			}
		}
	$res=$res.'<tr class="fondoazul">
<td><a href="#" alt="Actividades vencidas">&nbsp;</a>'.$ETI['msg_vencidas'].'</td>
<td colspan="2" align="right">
'.html_paginador("paginaf15", $registros, $lineastablav, $paginav, "paginarf14()").'
'.html_lpp("lppf15", $lineastablav, "paginarf14()").'
</td>
</tr>';
	$babierta=false;
	$sCurso='';
	while($filadet=$objDB->sf($tabladetalle)){
		if ($sCurso!=$filadet['ofer18curso']){
			$sCurso=$filadet['ofer18curso'];
			$res=$res.'<tr class="fondoazul">
<td colspan="3"><a href="#" alt="Curso '.$ETI['msg_curso'].' '.$sCurso.'">&nbsp;</a>'.$ETI['msg_curso'].' <b>'.$sCurso.'</b></td>
</tr>';
			}
		$sPrefijo='';
		$sSufijo='';
		$sLink='';
		if(($tlinea%2)==0){$sClass=' class="resaltetabla"';}
		$tlinea++;
		$sDetalle=cadena_notildes($filadet['ofer04detalle']);
		$res=$res.'<tr'.$sClass.'>
<td><a href="#" alt="'.cadena_notildes($filadet['ofer04nombre']).'">&nbsp;</a> '.cadena_notildes($filadet['ofer04nombre']).'</td>
<td><a href="#" alt="Fecha de cierre '.formato_fechalarga($filadet['ofer18fechacierrre']).'">&nbsp;</a>'.$filadet['ofer18fechacierrre'].'</td>
<td>'.$sLink.'</td>
</tr><tr>
<td colspan="3"><a href="#" alt="'.$sDetalle.'">&nbsp;</a>'.$sDetalle.'</td>
</tr>';
		}
		}
	$res=$res.'</table>';
	if (!$bConVencidas){
		//No lleva vencidas.
		$res=$res.'<input id="paginaf15" name="paginaf15" type="hidden" value="'.$paginav.'"/><input id="lppf15" name="lppf15" type="hidden" value="'.$lineastablav.'"/>';
		}
	return array(utf8_encode($res), $sDebug);
	}
function f17_TablaAgendaV2($params, $objDB, $bDebug=false){
	$sDebug='';
	$mensajes_17='lg/lg_17_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_17)){$mensajes_17='lg/lg_17_es.php';}
	require $mensajes_17;
	$sIdent=array('','A','B','C','D','E','F','G','H','','0','','','','','5','','','','','S');
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	if (isset($params[100])==0){$params[100]=$_SESSION['unad_id_tercero'];}
	if (isset($params[101])==0){$params[101]=1;}
	if (isset($params[102])==0){$params[102]=10;}
	if (isset($params[103])==0){$params[103]=1;}
	if (isset($params[104])==0){$params[104]=5;}
	if (isset($params[105])==0){$params[105]=5;}
	if (isset($params[106])==0){$params[106]='';}
	$idTercero=$params[100];
	$pagina=$params[101];
	$lineastabla=$params[102];
	$paginav=$params[103];
	$lineastablav=$params[104];
	$sSQLadd='';
	$bConVencidas=false;
	//if (isset($params[103])==0){$params[103]='';}
	//if ((int)$params[103]!=-1){$sSQLadd=$sSQLadd.' AND TB.campo='.$params[103];}
	if ((int)$params[105]==0){$params[105]=5;}
	if ($params[106]!=''){
		$aVal=explode('_',$params[106]);
		$iNumAula=$aVal[2];
		$sSQLadd=$sSQLadd.' AND TB.unad47peraca='.$aVal[0].' AND TB.unad47idcurso='.$aVal[1].' AND TB.unad47numaula='.$iNumAula.'';
		}
	$sHoy=fecha_hoy();
	$sListaLlaves='';
	$sMostrar='';
	$sSQL='SELECT unad47peraca, unad47idcurso, unad47numaula, unad47idgrupo, unad47idceadasiste 
FROM unad47tablero AS TB 
WHERE unad47idtercero='.$idTercero.' AND unad47idrol='.$params[105].' AND unad47activo="S" AND unad47retirado="N"'.$sSQLadd;
	$tabla=$objDB->ejecutasql($sSQL);
	while($fila=$objDB->sf($tabla)){
		if ($sListaLlaves!=''){$sListaLlaves=$sListaLlaves.', ';}
		$sListaLlaves=$sListaLlaves.'"'.$fila['unad47peraca'].'_'.$fila['unad47idcurso'].'_'.$fila['unad47numaula'].'"';
		}
	//$sMostrar=$sSQL;
	if ($sListaLlaves==''){
		/* if ($_SESSION['unad_id_tercero']==4){
			$sMostrar=$sSQL;
			} */
		$sListaLlaves='"nada_que_ver"';
		}
	$sTitulos='';
	$sVigentes='AND STR_TO_DATE(TB.ofer18fechacierrre, "%d/%m/%Y")>=STR_TO_DATE("'.$sHoy.'", "%d/%m/%Y") ORDER BY STR_TO_DATE(TB.ofer18fechacierrre, "%d/%m/%Y")';
	$sVencidas='AND STR_TO_DATE(TB.ofer18fechacierrre, "%d/%m/%Y")<STR_TO_DATE("'.$sHoy.'", "%d/%m/%Y") ORDER BY STR_TO_DATE(TB.ofer18fechacierrre, "%d/%m/%Y") DESC';
	$sSQLBase='SELECT TB.ofer18per_aca, TB.ofer18curso, TB.ofer18numaula, TB.ofer18fase, TB.ofer18unidad, TB.ofer18idactividad, T2.ofer02nombre, T3.ofer03nombre, T1.ofer04nombre, TB.ofer18fechainicio, TB.ofer18fechacierrre, TB.ofer18peso, TB.ofer18detalle, T1.ofer04detalle, T4.unad40nombre 
FROM ofer18cargaxnavxdia AS TB, ofer04cursoactividad AS T1, ofer02cursofase AS T2, ofer03cursounidad AS T3, unad40curso AS T4 
WHERE TB.ofer18idactividad=T1.ofer04id AND TB.ofer18fase=T2.ofer02id AND TB.ofer18unidad=T3.ofer03id AND TB.ofer18curso=T4.unad40id AND CONCAT(TB.ofer18per_aca,"_",TB.ofer18curso,"_",TB.ofer18numaula) IN ('.$sListaLlaves.')';
	$sSQL=$sSQLBase.$sVigentes;
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_14" name="consulta_14" type="hidden" value="'.$sSQLlista.'"/>
<input id="titulos_14" name="titulos_14" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objDB->ejecutasql($sSQL);
	if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Agenda: Trae las actividades vigentes<br>';}
	//$tablavencidas=$objDB->ejecutasql($sSQLBase.$sVencidas);
	if ($tabladetalle==false){
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		}else{
		$registros=$objDB->nf($tabladetalle);
		if ($registros==0){
			//return utf8_encode($sErrConsulta.'<input id="paginaf18" name="paginaf18" type="hidden" value="'.$pagina.'"/><input id="lppf18" name="lppf18" type="hidden" value="'.$lineastabla.'"/>');
			//break;
			}
		if ((($registros-1)/$lineastabla)<($pagina-1)){$pagina=(int)(($registros-1)/$lineastabla)+1;}
		if ($registros>$lineastabla){
			$rbase=($pagina-1)*$lineastabla;
			$limite=' LIMIT '.$rbase.', '.$lineastabla;
			$tabladetalle=$objDB->ejecutasql($sSQL.$limite);
			}
		}
	/*
	if ($_SESSION['unad_id_tercero']==4){
		$sMostrar=$sMostrar.' '.$sSQL.' ';
		}
		*/
	$res=$sErrConsulta.$sMostrar.'<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">
<tr class="fondoazul">
<td>'.$ETI['msg_proximas'].'</td>
<td colspan="2" align="right">
'.html_paginador("paginaf14", $registros, $lineastabla, $pagina, "paginarf14()").'
'.html_lpp("lppf14", $lineastabla, "paginarf14()").'
</td>
</tr>';
	$tlinea=1;
	$babierta=false;
	$sCurso='';
	//$sHoy=fecha_hoy();
	while($filadet=$objDB->sf($tabladetalle)){
		//ofer18per_aca, TB.ofer18curso, TB.ofer18numaula
		
		$sMuestra=$filadet['ofer18curso'].$sIdent[$filadet['ofer18numaula']].'_'.$filadet['ofer18per_aca'];
		if ($sCurso!=$sMuestra){
			$sCurso=$sMuestra;
			//'.$ETI['msg_curso'].' 
			$res=$res.'<tr class="fondoazul">
<td colspan="3" align="center"><a href="#" alt="Curso '.$sMuestra.'">&nbsp;</a><b>'.$sMuestra.'</b> '.cadena_notildes($filadet['unad40nombre']).'</td>
</tr>';
			}
		$sPrefijo='';
		$sSufijo='';
		$sClass='';
		$sLink='';
		$sEstilo='';
		$sClass2='';
		$iDias=fecha_numdiasentrefechas($sHoy,$filadet['ofer18fechacierrre']);
		switch ($iDias){
			case 0:
			case 1:
			case 2:
			$sClass2=' style="color:#FFFFFF;background-color:#E51323"';
			break;
			case 3:
			case 4:
			case 5:
			case 6:
			$sClass2=' style="background-color:#FFFF44"';
			break;
			default:
			$iDias=fecha_numdiasentrefechas($filadet['ofer18fechainicio'], $sHoy);
			if ($iDias>-1){
				$sClass2=' style="background-color:#00DD00"';
				}
			}
		if ($filadet['ofer18fechacierrre']==$sHoy){
			$sPrefijo='<b>';
			$sSufijo='</b>';
			}
		if(($tlinea%2)==0){$sClass='';}
		$tlinea++;
		if ($babierta){
			//$sLink='<a href="javascript:cargadato('."'".''."'".')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$sDetalle=cadena_notildes($filadet['ofer04detalle']);
		$res=$res.'<tr class="resaltetabla">
<td'.$sClass2.'><a href="#" alt="'.cadena_notildes($filadet['ofer04nombre']).'">&nbsp;</a>'.cadena_notildes($filadet['ofer04nombre']).'</td>
<td'.$sClass2.'><b><a href="#" alt="Fecha de cierre '.formato_fechalarga($filadet['ofer18fechacierrre']).'">&nbsp;</a>'.formato_fechalarga($filadet['ofer18fechacierrre']).'</b></td>
<td>'.$sLink.'</td>
</tr><tr>
<td colspan="3"'.$sClass2.'><a href="#" alt="'.$sDetalle.'">&nbsp;</a>'.$sDetalle.'</td>
</tr>';
		}
	if ($registros==0){
		//El mensaje de que no tiene actividades pendientes.
		$res=$res.'<tr><td colspan="3"><a href="#" alt="No se registran actividades pendientes">&nbsp;</a>'.$ETI['msg_nopendientes'].'</td></tr>';
		}
	//Procesar la tabla de vencidas.
	if ($bConVencidas){
	$tabladetalle=$objDB->ejecutasql($sSQLBase.$sVencidas);
	if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Agenda: Trae las actividades vencidas<br>';}
	if ($tabladetalle==false){
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		}else{
		$registros=$objDB->nf($tabladetalle);
		if ($registros==0){
			//return utf8_encode($sErrConsulta.'<input id="paginaf15" name="paginaf15" type="hidden" value="'.$paginav.'"/><input id="lppf15" name="lppf15" type="hidden" value="'.$lineastablav.'"/>');
			//break;
			}
		if ((($registros-1)/$lineastablav)<($paginav-1)){$paginav=(int)(($registros-1)/$lineastablav)+1;}
		if ($registros>$lineastablav){
			$rbase=($paginav-1)*$lineastablav;
			$limite=' LIMIT '.$rbase.', '.$lineastablav;
			$tabladetalle=$objDB->ejecutasql($sSQLBase.$sVencidas.$limite);
			}
		}
	$res=$res.'<tr class="fondoazul">
<td><a href="#" alt="Actividades vencidas">&nbsp;</a>'.$ETI['msg_vencidas'].'</td>
<td colspan="2" align="right">
'.html_paginador("paginaf15", $registros, $lineastablav, $paginav, "paginarf14()").'
'.html_lpp("lppf15", $lineastablav, "paginarf14()").'
</td>
</tr>';
	$babierta=false;
	$sCurso='';
	while($filadet=$objDB->sf($tabladetalle)){
		if ($sCurso!=$filadet['ofer18curso']){
			$sCurso=$filadet['ofer18curso'];
			$res=$res.'<tr class="fondoazul">
<td colspan="3"><a href="#" alt="Curso '.$ETI['msg_curso'].' '.$sCurso.'">&nbsp;</a>'.$ETI['msg_curso'].' <b>'.$sCurso.'</b></td>
</tr>';
			}
		$sPrefijo='';
		$sSufijo='';
		$sLink='';
		if(($tlinea%2)==0){$sClass=' class="resaltetabla"';}
		$tlinea++;
		$sDetalle=cadena_notildes($filadet['ofer04detalle']);
		$res=$res.'<tr'.$sClass.'>
<td><a href="#" alt="'.cadena_notildes($filadet['ofer04nombre']).'">&nbsp;</a> '.cadena_notildes($filadet['ofer04nombre']).'</td>
<td><a href="#" alt="Fecha de cierre '.formato_fechalarga($filadet['ofer18fechacierrre']).'">&nbsp;</a>'.$filadet['ofer18fechacierrre'].'</td>
<td>'.$sLink.'</td>
</tr><tr>
<td colspan="3"><a href="#" alt="'.$sDetalle.'">&nbsp;</a>'.$sDetalle.'</td>
</tr>';
		}
		}
	$res=$res.'</table>';
	if (!$bConVencidas){
		//No lleva vencidas.
		$res=$res.'<input id="paginaf15" name="paginaf15" type="hidden" value="'.$paginav.'"/><input id="lppf15" name="lppf15" type="hidden" value="'.$lineastablav.'"/>';
		}
	return array(utf8_encode($res), $sDebug);
	}

function f17_DatosLaboratorio($idTercero, $idPeraca, $idCurso, $iHoy, $iNumLab, $objDB, $bDebug=false){
	// Mayo 17 de 2016 se cambia la estrategia de presentacion de la informacion...
	//Ojo es laboratorio el tipo de oferta es 0
	$sHoy=fecha_hoy();
	$sInfoLab='';
	$sInfoDebug='';
	$bHayFecha=false;
	$bEstaInscrito=false;
	$bHayOferta=false;
	$sIds08='-99';
	$id11=0;
	$sIds2120='-99'; // Ids de las notificaciones de laboratorio que se deben dar por notificadas.
	$sNotificacionCurso='';
	$sSQL='SELECT olab08id FROM olab08oferta 
WHERE olab08idcurso='.$idCurso.' AND olab08idperaca='.$idPeraca.' AND olab08idtipooferta=0 AND olab08cerrado="S"';
	$tablalab=$objDB->ejecutasql($sSQL);
	while($filalab=$objDB->sf($tablalab)){
		$sIds08=$sIds08.','.$filalab['olab08id'];
		$bHayOferta=true;
		}
	if ($bDebug){$sInfoDebug=$sInfoDebug.fecha_microtiempo().' Laboratorios para '.$idPeraca.' '.$idCurso.': '.$sIds08.'<br>';}
	//Ver si esta inscrito
	if ($bHayOferta){
		$sSQL='SELECT TB.olab11id, TB.olab11idoferta, TB.olab11nota, TB.olab11inforetro, TB.olab11puntaje 
FROM olab11cupos AS TB 
WHERE TB.olab11idoferta IN ('.$sIds08.') AND TB.olab11idestudiante='.$idTercero.'';
		if ($bDebug){$sInfoDebug=$sInfoDebug.fecha_microtiempo().' Laboratorios para '.$idPeraca.' '.$idCurso.': '.$sSQL.'<br>';}
		$tablalab=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tablalab)>0){
			$filalab=$objDB->sf($tablalab);
			$id11=$filalab['olab11id'];
			$id08=$filalab['olab11idoferta'];
			$bEstaInscrito=true;
			//Traer la informaci�n del laboratorio.
				$sInfoLab='';
			$sJornadas='';
				$sProximaFechaLab='';
			$sMensajes='';
			$sActualiza='';
			$sInfoTermina='';
			$bTerminado=false;
			//Consultamos la informaci�n del grupo para mostrarsela al estudiante.
			$sSQL='SELECT TB.olab08numgrupo, T24.unad24nombre, T1.olab01nombre, TB.olab08realizado 
FROM olab08oferta AS TB, unad24sede AS T24, olab01laboratorios AS T1 
WHERE TB.olab08id='.$id08.' AND TB.olab08idcead=T24.unad24id AND TB.olab08idlaboratorio=T1.olab01id';
			if ($bDebug){$sInfoDebug=$sInfoDebug.fecha_microtiempo().' Laboratorios para '.$idPeraca.' '.$idCurso.': Informaci&oacute;n del grupo:'.$sSQL.'<br>';}
			$tablaofer=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablaofer)>0){
				$filaofer=$objDB->sf($tablaofer);
				$sInfoLab='CEAD <b>'.cadena_notildes($filaofer['unad24nombre']).'</b><br>Laboratorio <b>'.cadena_notildes($filaofer['olab01nombre']).'</b> Grupo <b>'.$filaofer['olab08numgrupo'].'</b>';
				if ($filaofer['olab08realizado']=='S'){
					$bTerminado=true;
					$sPuntaje='<b>'.$filalab['olab11nota'].'</b> (Puntaje de 0 a 5)';
					$iPuntajeCurso=0;
					$bNotaAplicada=false;
					$bConPuntaje=true;
					$sSQL='SELECT olab25cerrado, olab25conpuntaje, olab25puntajelab FROM olab25centrarnotas WHERE olab25idperaca='.$idPeraca.' AND olab25idcurso='.$idCurso;
					$tabla25=$objDB->ejecutasql($sSQL);
					if ($objDB->nf($tabla25)>0){
						$fila25=$objDB->sf($tabla25);
						if ($fila25['olab25puntajelab']>0){$iPuntajeCurso=$fila25['olab25puntajelab'];}
						if ($fila25['olab25cerrado']=='S'){$bNotaAplicada=true;}
						if ($fila25['olab25conpuntaje']!='S'){$bConPuntaje=false;}
						}
					if ($bConPuntaje){
						if ($iPuntajeCurso>0){
							if ($bNotaAplicada){
								$sPuntaje='<b>'.$filalab['olab11puntaje'].'</b> sobre '.$iPuntajeCurso;
								}else{
								$sPuntaje=''.floor((($filalab['olab11nota']*$iPuntajeCurso)/5)+0.4).' sobre '.$iPuntajeCurso;
								}
							}
						$sInfoTermina='<div style="max-width:540px;">Su nota de laboratorio es '.$sPuntaje.'. Retroalimentaci&oacute;n: '.cadena_notildes($filalab['olab11inforetro']).'</div>';
						}else{
						$sInfoTermina='<div style="max-width:540px;">La actividad no es calificada. Retroalimentaci&oacute;n: '.cadena_notildes($filalab['olab11inforetro']).'</div>';
						}
					//Cargarle la encuesta....
					if ($idTercero==$_SESSION['unad_id_tercero']){
						//Verificar que no haya encuestas abiertas.
						f19_AplicarEncuestaAlumnoLaboratorioV2($idPeraca, $idCurso, $idTercero, 1701, $objDB);
						$sSQL='SELECT TB.even21id FROM even21encuestaaplica AS TB, even16encuesta AS T16 WHERE TB.even21idtercero='.$idTercero.' AND TB.even21idperaca='.$idPeraca.' AND TB.even21idcurso='.$idCurso.' AND TB.even21terminada<>"S" AND TB.even21idencuesta=T16.even16id AND T16.even16idproceso IN (1701, 1704)';
						//$sError=$sSQL;
						$result=$objDB->ejecutasql($sSQL);
						if ($objDB->nf($result)>0){
							$fila=$objDB->sf($result);
							$sInfoTermina='Por favor diligencie la encuesta de laboratorios.';
							//Redirigirlo.
							header('Location:encuesta.php?idencuesta='.$fila['even21id']);
							die();
							}
						}
					}
				}else{
				//@@@ Que pasa si no encuentra informaci�n del grupo???
				}
			if (!$bTerminado){
				//Traer la informaci�n de las jornadas.
				$sSQL='SELECT olab10fecha, olab10hora, olab10minuto, olab10horafinal, olab10minutofinal FROM olab10horarios WHERE olab10idoferta='.$id08.' AND olab10retirado<>"S" ORDER BY STR_TO_DATE(olab10fecha, "%d/%m/%Y"), olab10hora, olab10minuto,  olab10consec';
				$tablaj=$objDB->ejecutasql($sSQL);
				while ($filaj=$objDB->sf($tablaj)){
					$sInfoJornada=formato_fechalarga($filaj['olab10fecha'], true).' '.html_TablaHoraMin($filaj['olab10hora'], $filaj['olab10minuto']).' A '.html_TablaHoraMin($filaj['olab10horafinal'], $filaj['olab10minutofinal']);
					if ($sJornadas!=''){$sJornadas=$sJornadas.'<br>';}
					if ($sProximaFechaLab==''){
						$bPasaFecha=false;
						if (fecha_esmayor($filaj['olab10fecha'], $sHoy)){
							$bPasaFecha=true;
							}else{
							if ($filaj['olab10fecha']==$sHoy){$bPasaFecha=true;}
							}
						if ($bPasaFecha){
							$sProximaFechaLab=' - Pr&oacute;xima jornada <b>'.$sInfoJornada.'</b>';
							}
						}
					$sJornadas=$sJornadas.$sInfoJornada;
					}
				//Traer las notificaciones.
				$sSQL='SELECT olab09nota, olab09fecha, olab09hora, olab09minuto FROM olab09ofertanota WHERE olab09idoferta='.$id08.' AND olab09paraestudiantes="S" ORDER BY olab09consec DESC';
				$tablaj=$objDB->ejecutasql($sSQL);
				while ($filaj=$objDB->sf($tablaj)){
					if ($sMensajes!=''){$sMensajes=$sMensajes.'<br>';}
					$sMensajes=$sMensajes.'- '.cadena_notildes($filaj['olab09nota']).' {Publicado el '.$filaj['olab09fecha'].' '.html_TablaHoraMin($filaj['olab09hora'], $filaj['olab09minuto']).'}';
					}
				//Las actualizaciones.
				$sSQL='SELECT TB.olab20idcambio, TB.olab20fechanotifica, TB.olab20correoenviado, T1.olab19retiro, T1.olab19fechaorigen, T1.olab19horaorigen, T1.olab19minorigen, T1.olab19fecha, T1.olab19hora, T1.olab19minuto, T1.olab19horafinal, T1.olab19minutofinal, T1.olab19motivo, TB.olab20id, T1.olab19idlaboratorio, T1.olab19idlabprevio 
FROM olab20notificahorario AS TB, olab19cambiohorario AS T1 
WHERE TB.olab20idcupo='.$id11.' AND TB.olab20idestudiante='.$idTercero.' AND TB.olab20idcambio=T1.olab19id 
ORDER BY TB.olab20id DESC';
				$tablaj=$objDB->ejecutasql($sSQL);
				while ($filaj=$objDB->sf($tablaj)){
					$sInfoCambio='';
					switch($filaj['olab19retiro']){
						case 'S': // Retiro de jornada.
						$sInfoCambio='- La jornada del '.formato_fechalarga($filaj['olab19fechaorigen'], true).' a las '.html_TablaHoraMin($filaj['olab19horaorigen'], $filaj['olab19minorigen']).' <b>fue retirada</b>: '.cadena_notildes($filaj['olab19motivo']).'';
						break;
						case 'N': // Cambio de fecha
						$sInfoCambio='- La jornada que estaba programada para el '.formato_fechalarga($filaj['olab19fechaorigen'], true).' a las '.html_TablaHoraMin($filaj['olab19horaorigen'], $filaj['olab19minorigen']).' <b>fue modificada</b> para el '.formato_fechalarga($filaj['olab19fecha'], true).' de '.html_TablaHoraMin($filaj['olab19hora'], $filaj['olab19minuto']).' a '.html_TablaHoraMin($filaj['olab19horafinal'], $filaj['olab19minutofinal']).': <b>'.cadena_notildes($filaj['olab19motivo']).'</b>';
						break;
						case 'A': // Adicion de fechas
						$sInfoCambio='- <b>Se ha agregado una nueva jornada</b> para el '.formato_fechalarga($filaj['olab19fecha'], true).' de '.html_TablaHoraMin($filaj['olab19hora'], $filaj['olab19minuto']).' a '.html_TablaHoraMin($filaj['olab19horafinal'], $filaj['olab19minutofinal']).': <b>'.cadena_notildes($filaj['olab19motivo']).'</b>';
						break;
						case 'L': // Cambio de lugar
						$sInfoLugar='{'.$filaj['olab19idlaboratorio'].'}';
						$sSQL='SELECT olab01nombre, olab01detalle FROM olab01laboratorios WHERE olab01id='.$filaj['olab19idlaboratorio'].'';
						$tabla01=$objDB->ejecutasql($sSQL);
						if ($objDB->nf($tabla01)>0){
							$fila01=$objDB->sf($tabla01);
							$sInfoLugar='<b>'.cadena_notildes($fila01['olab01nombre']).'</b> '.cadena_notildes($fila01['olab01detalle']).'';
							}
						$sInfoCambio='- <b>Se ha modificado el lugar de practica</b> La actividad se realizar&aacute; en: '.$sInfoLugar.'<br>Motivo del cambio: <b>'.cadena_notildes($filaj['olab19motivo']).'</b>';
						break;
						}
					if ($sActualiza!=''){$sActualiza=$sActualiza.'<br>';}
					$sActualiza=$sActualiza.$sInfoCambio;
					if ($filaj['olab20fechanotifica']=='00/00/0000'){
						$sIds2120=$sIds2120.','.$filaj['olab20id'];
						if ($sNotificacionCurso!=''){$sNotificacionCurso=$sNotificacionCurso.'<br>';}
						$sNotificacionCurso=$sNotificacionCurso.$sInfoCambio;
						}
					}
				//Termina si el grupo aun esta abierto.
				}
			if ($sMensajes!=''){$sMensajes='<div class="salto1px"></div><b>Informaci&oacute;n importante</b><div class="salto1px"></div>'.$sMensajes.'<div class="salto1px"></div>';}
			if ($sActualiza!=''){$sActualiza='<b>Informaci&oacute;n de cambios en la programaci&oacute;n del laboratorio</b><div class="salto1px"></div>'.$sActualiza.'<div class="salto1px"></div>';}
			//Mostrar el dato.
			if ($bTerminado){
				$sInfoLab=$sInfoTermina;
				//Fin de cargarle la encuesta...
				}else{
				$sInfoLab=f17_BotonesLab($iNumLab).'Componente pr&aacute;ctico inscrito'.$sProximaFechaLab.'.<div class="salto1px"></div><div id="div_lab'.$iNumLab.'" style="display:none">'.$sInfoLab.' <br>N&uacute;mero de comprobaci&oacute;n: <b>'.formato_numero($id11).'</b> Recuerde que debe asistir al laboratorio en las siguientes fechas:<br>'.$sJornadas.$sMensajes.'<div class="salto1px"></div>'.$sActualiza.'</div>';
				}
			}
		//Termina si hay oferta...
		}
	if (!$bEstaInscrito){
		//Revisamos las fechas para le peraca...
		$sSQL='SELECT exte02fechainslab, ext02fechafinlab FROM exte02per_aca WHERE exte02id='.$idPeraca.'';
		$sInfoFechaInsc='';
		$tablalab=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tablalab)>0){
			$filalab=$objDB->sf($tablalab);
			if (fecha_esvalida($filalab['exte02fechainslab'])){
				$bHayFecha=true;
				$iFechaIni=fecha_EnNumero($filalab['exte02fechainslab']);
				if ($iHoy<$iFechaIni){
					$bHayFecha=false;
					$sInfoFechaInsc='La inscripci&oacute;n al componente pr&aacute;ctico (Laboratorio) de este curso se habilitar&aacute; el '.formato_fechalarga($filalab['exte02fechainslab']).'.';
					}else{
					if (fecha_esvalida($filalab['ext02fechafinlab'])){
						$iFechaFin=fecha_EnNumero($filalab['ext02fechafinlab']);
						if ($iHoy>$iFechaFin){
							$bHayFecha=false;
							$sInfoFechaInsc='<div class="rojo">La inscripci&oacute;n al componente pr&aacute;ctico (Laboratorio) de este curso se cerr&oacute; el '.formato_fechalarga($filalab['ext02fechafinlab']).'.</div>';
							}
						}
					}
				}
			}else{
			$sInfoFechaInsc='La inscripci&oacute;n al componente pr&aacute;ctico (Laboratorio) de este curso a&uacute;n no se encuentra habilitada.';
			}
		if ($sInfoFechaInsc!=''){
			$sInfoLab='<div class="ir_derecha" style="width:32px;">
<label class="Label30">
<input id="bayudalab" name="bayudalab" type="button" value="Ayuda" class="btMiniAyuda" onclick="ayudalab();" title="Ir a la ayuda">
</label></div>'.$sInfoFechaInsc;
			}
		}
	if ($bHayOferta){
		if ($id11==0){
			//Asegurarnos de que el proceso no se ha completado.
			$bAbierto=true;
			$sSQL='SELECT olab25cerrado, olab25fechaaplicado, olab25horaaplicado, olab25minaplicado FROM olab25centrarnotas WHERE olab25idperaca='.$idPeraca.' AND olab25idcurso='.$idCurso.'';
			$tabla25=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla25)>0){
				$fila25=$objDB->sf($tabla25);
				if ($fila25['olab25cerrado']=='S'){
					$bAbierto=false;
					$sInfoLab='<div class="rojo">El proceso de inscripci&oacute;n y presentaci&oacute;n de practicas de laboratorio para el curso '.$idCurso.' concluy&oacute; el d&iacute;a '.formato_fechalarga($fila25['olab25fechaaplicado']).' a las '.html_TablaHoraMin($fila25['olab25horaaplicado'], $fila25['olab25minaplicado']).'</div>';
					}
				}
			if ($bAbierto){
				$sInfoLab='<div class="ir_derecha" style="width:32px;">
<label class="Label30">
<input id="bayudalab" name="bayudalab" type="button" value="Ayuda" class="btMiniAyuda" onclick="ayudalab();" title="Ir a la ayuda">
</label></div>
Para inscribirse en el componente pr&aacute;ctico (laboratorio) de este curso <a href="javascript:insclab('.$idPeraca.', '.$idCurso.', 0);">haga clic aqu&iacute;</a>';
				}
			}
		}else{
		if ($sInfoLab==''){
			$sInfoLab='<b>No se han abierto grupos de laboratorio para este curso, ofrecemos disculpas por las demoras, tan pronto sean abiertos podr&aacute; inscribirse.</b>';
			}
		}
	if ($sIds2120!='-99'){
		if ($idTercero==$_SESSION['unad_id_tercero']){
			$sHoy=fecha_hoy();
			$sHora=fecha_hora();
			$sMinuto=fecha_minuto();
			$sSQL='UPDATE olab20notificahorario SET olab20fechanotifica="'.$sHoy.'", olab20horanotifica='.$sHora.', olab20minnotifica='.$sMinuto.' WHERE olab20id IN ('.$sIds2120.')';
			$result=$objDB->ejecutasql($sSQL);
			}
		}
	return array($sInfoLab, $sInfoDebug, $sNotificacionCurso);
	}

function f17_TablaTablero($params, $objDB, $bDebug=false){
	$sDebug='';
	$sNotificar='';
	$mensajes_17='lg/lg_17_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_17)){$mensajes_17='lg/lg_17_es.php';}
	require $mensajes_17;
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	if (isset($params[100])==0){$params[100]=$_SESSION['unad_id_tercero'];}
	if (isset($params[101])==0){$params[101]=1;}
	if (isset($params[102])==0){$params[102]=50;}
	$idTercero=$params[100];
	$pagina=$params[101];
	$lineastabla=$params[102];
	$sSQLadd='';
	$bConT1=false;
	if (isset($params[103])==0){$params[103]='';}
	if (isset($params[105])==0){$params[105]='';}
	//if ((int)$params[103]!=-1){$sSQLadd=$sSQLadd.' AND TB.campo='.$params[103];}
	if ($params[103]!=''){
		$sSQLadd=$sSQLadd.' AND T1.unad40nombre LIKE "%'.$params[103].'%"';
		$bConT1=true;
		}
	if ($params[105]!=''){
		$sSQLadd=$sSQLadd.' AND T1.unad40consec LIKE "%'.$params[105].'%"';
		$bConT1=true;
		}
	$sHoy=fecha_hoy();
	$iHoy=fecha_EnNumero($sHoy);
	$sListaLlaves='';
	$sTitulos='';
	$iRegistro=0;
	$idBloque=-1;
	$iNumLab=0;
	$res='';
	if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Tablero: Iniciando lectura de bloques (Consulta V2)<br>';}
	//Tenemos que mostrar las tablas en bloques.... Se toma el listado de bloques.
	$sSQL='SELECT T44.unad44nombre, T44.unad44id FROM unad44bloque AS T44 ORDER BY T44.unad44orden, T44.unad44id';
	$tablab=$objDB->ejecutasql($sSQL);
	while ($filab=$objDB->sf($tablab)){
		//Se recorre uno a uno los bloques de servidores.
		$sErrConsulta='';
		//Sacar primero la cantidad de registros.
		if ($bConT1){
			$sSQL='SELECT TB.unad47peraca, TB.unad47idnav, TB.unad47idrol, TB.unad47idcurso 
FROM unad47tablero AS TB, unad40curso AS T1, unad39nav AS T3 
WHERE TB.unad47idtercero='.$idTercero.' AND TB.unad47activo="S" AND TB.unad47retirado="N" AND TB.unad47idcurso=T1.unad40id AND TB.unad47idnav=T3.unad39id AND T3.unad39idbloque='.$filab['unad44id'].' AND T3.unad39activo="S" '.$sSQLadd.'';
			}else{
			$sSQL='SELECT TB.unad47peraca, TB.unad47idnav, TB.unad47idrol, TB.unad47idcurso 
FROM unad47tablero AS TB, unad39nav AS T3 
WHERE TB.unad47idtercero='.$idTercero.' AND TB.unad47activo="S" AND TB.unad47retirado="N" AND TB.unad47idnav=T3.unad39id AND T3.unad39idbloque='.$filab['unad44id'].' AND T3.unad39activo="S" '.$sSQLadd.'';
			}
		$tabladetalle=$objDB->ejecutasql($sSQL);
		$registros=$objDB->nf($tabladetalle);
		if ($tabladetalle==false){
			$registros=0;
			}
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Tablero: Completada la consulta para el bloque '.$filab['unad44id'].'<br>';}
		if ($registros!=0){
			$sSQL='SELECT TB.unad47peraca, TB.unad47idnav, T2.exte02titulo, TB.unad47idrol, TB.unad47idcurso, T1.unad40nombre, TB.unad47numaula, TB.unad47idgrupo, TB.unad47idceadasiste, T3.unad39nombrecomun, T3.unad39nombre, T3.unad39idbloque, T1.unad40incluyelaboratorio, T1.unad40incluyesalida 
FROM unad47tablero AS TB, unad40curso AS T1, exte02per_aca AS T2, unad39nav AS T3  
WHERE TB.unad47idtercero='.$idTercero.' AND TB.unad47activo="S" AND TB.unad47retirado="N" AND TB.unad47idcurso=T1.unad40id AND TB.unad47peraca=T2.exte02id AND TB.unad47idnav=T3.unad39id AND T3.unad39idbloque='.$filab['unad44id'].' AND T3.unad39activo="S" '.$sSQLadd.'
ORDER BY TB.unad47peraca DESC, T1.unad40nombre';
			if ((($registros-1)/$lineastabla)<($pagina-1)){$pagina=(int)(($registros-1)/$lineastabla)+1;}
			$limite='';
			if ($registros>$lineastabla){
				$rbase=($pagina-1)*$lineastabla;
				$limite=' LIMIT '.$rbase.', '.$lineastabla;
				}
			$tabladetalle=$objDB->ejecutasql($sSQL.$limite);
			if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Tablero: Se han cargado los datos para el bloque '.$filab['unad44id'].'<br>';}
	$res=$res.$sErrConsulta.'<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">';
	$tlinea=1;
	$idPeraca=-1;
	while($filadet=$objDB->sf($tabladetalle)){
		if ($idBloque==-1){
			//Es la primer linea se agrega el paginador
			$idBloque=$filab['unad44id'];
			$res=$res.'
<tr style="background-color:#00314A;color:#FFFFFF">
<td colspan="2" align="center"><div class="MarquesinaMedia" style="margin:2px 10px;">'.cadena_notildes($filab['unad44nombre']).'</div></td>
<td align="right" width="100px">
'.html_paginador('paginaf17', $registros, $lineastabla, $pagina, 'paginarf17()').'
'.html_lpp('lppf17', $lineastabla, 'paginarf17()', 1000).'
</td>
</tr>';
			}
		if ($idBloque!=$filab['unad44id']){
			//Abrir la tabla.
			$idBloque=$filab['unad44id'];
			$idPeraca=-1;
			$res=$res.'<tr style="background-color:#00314A;color:#FFFFFF">
<td colspan="2" align="center"><div class="MarquesinaMedia" style="margin:2px 10px;">'.cadena_notildes($filab['unad44nombre']).'</div></td>
<td align="right" width="100px">
'.html_paginador('paginaf17_'.$idBloque, $registros, $lineastabla, $pagina, "paginarf17()").'
</td>
</tr>';
			}
		if ($idPeraca!=$filadet['unad47peraca']){
			$idPeraca=$filadet['unad47peraca'];
			$res=$res.'<tr class="fondoazul">
<td width="80px"></td>
<td colspan="2">Periodo <b>'.cadena_notildes($filadet['exte02titulo']).'</b></td>
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
		$babierta=true;
		if ($babierta){
			$sTitNav=cadena_notildes($filadet['unad39nombrecomun']);
			if (trim($sTitNav)==''){
				$sTitNav=cadena_notildes($filadet['unad39nombre']);
				}
			$sLink='<a href="javascript:redir('.$filadet['unad47peraca'].', '.$filadet['unad47idnav'].', '.$filadet['unad47idcurso'].', '.$filadet['unad47numaula'].')" class="lnktablero">'.$sTitNav.'</a>';
			}
		$sNomAula='A';
		switch($filadet['unad47numaula']){
			case 2:$sNomAula='B';break;
			case 3:$sNomAula='C';break;
			case 4:$sNomAula='D';break;
			case 5:$sNomAula='E';break;
			case 6:$sNomAula='F';break;
			case 8:$sNomAula='H';break;
			case 19:$sNomAula='R';break;
			case 20:$sNomAula='S';break;
			}
		$sNombreCurso=$filadet['unad47idcurso'].$sNomAula.'_'.$filadet['unad47peraca'];
		$res=$res.'<tr'.$sClass.'>
<td>'.$sNombreCurso.'</td>
<td>'.cadena_notildes($filadet['unad40nombre']).'</td>
<td>'.$sLink.'</td>
</tr>';
		$bConSalida=false;
		$bConLaboratorio=false;
		$sNotificacionCurso='';
		if ($filadet['unad40incluyelaboratorio']=='S'){
			//Solo los estudiantes se pueden inscribir.
			if ($filadet['unad47idrol']==5){$bConLaboratorio=true;}
			}
		if ($filadet['unad40incluyesalida']=='S'){
			//Solo los estudiantes se pueden inscribir.
			if ($filadet['unad47idrol']==5){$bConSalida=true;}
			}
		if ($bConLaboratorio){
			$iNumLab++;
			list($sInfoLab, $sInfoDebugLab, $sNotificacionCursoL)=f17_DatosLaboratorio($idTercero, $filadet['unad47peraca'], $filadet['unad47idcurso'], $iHoy, $iNumLab, $objDB, $bDebug);
			if ($bDebug){$sDebug=$sDebug.$sInfoDebugLab;}
			$sNotificacionCurso=$sNotificacionCurso.$sNotificacionCursoL;
			$res=$res.'<tr'.$sClass.'>
<td></td><td colspan="2">'.$sInfoLab.'</td>
</tr>';
			//Fin de si tiene laboratorio.
			}
		if ($bConSalida){
			$iNumLab++;
			list($sInfoLab, $sInfoDebugLab)=f17_DatosSalidas($idTercero, $filadet['unad47peraca'], $filadet['unad47idcurso'], $iHoy, $iNumLab, $objDB, $bDebug);
			if ($bDebug){$sDebug=$sDebug.$sInfoDebugLab;}
			$res=$res.'<tr'.$sClass.'>
<td></td><td colspan="2">'.$sInfoLab.'</td>
</tr>';
			//Fin de si tiene laboratorio.
			}
		if ($sNotificacionCurso!=''){
			if ($sNotificar!=''){$sNotificar=$sNotificar.'<br>';}
			$sNotificar=$sNotificar.'<b>Curso '.$sNombreCurso.' '.cadena_notildes($filadet['unad40nombre']).'</b><br>'.$sNotificacionCurso;
			}
		}
	$res=$res.'</table>';
			//Fin de si hay registros.
			}
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Tablero: Termina lectura del bloque '.$filab['unad44id'].'<br>';}
		//Fin de recorrer cada grupo de servidores
		}
	if ($res==''){
		//Sacar la info del usuario para que lo tenga soporte.
		$sInfoTercero='';
		$sSQL='SELECT unad11doc, unad11usuario FROM unad11terceros WHERE unad11id='.$idTercero;
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$fila=$objDB->sf($tabla);
			$sInfoTercero=' No olvide incluir la siguiente informaci&oacute;n: Nombre de usuario <b>'.$fila['unad11usuario'].'</b> Documento <b>'.$fila['unad11doc'].'</b>';
			}
		$res=$ETI['msg_nocursos'].$sInfoTercero;
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Tablero: No tiene cursos<br>';}
		}
	return array(utf8_encode($res), utf8_encode($sNotificar), $sDebug);
	}
function f17_TablaTableroV2($params, $objDB, $bDebug=false){
	// Esta se hace por bloque... de forma tal que las variables de pagina van por bloque...
	$sDebug='';
	$sNotificar='';
	$mensajes_17='lg/lg_17_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_17)){$mensajes_17='lg/lg_17_es.php';}
	require $mensajes_17;
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	if (isset($params[100])==0){$params[100]=$_SESSION['unad_id_tercero'];}
	if (isset($params[101])==0){$params[101]=1;}
	if (isset($params[102])==0){$params[102]=10;}
	if (isset($params[104])==0){$params[104]=0;}
	if (isset($params[106])==0){$params[106]=0;}
	$idTercero=$params[100];
	$pagina=$params[101];
	$lineastabla=$params[102];
	$sSQLadd='';
	$res='';
	$res='';
	if ($params[104]==0){
		return array($res, $sNotificar, $sDebug);
		die();
		}
	$idBloqueCursos=$params[104];
	$bConT1=false;
	if (isset($params[103])==0){$params[103]='';}
	if (isset($params[105])==0){$params[105]='';}
	//if ((int)$params[103]!=-1){$sSQLadd=$sSQLadd.' AND TB.campo='.$params[103];}
	if ($params[103]!=''){
		$sSQLadd=$sSQLadd.' AND T1.unad40nombre LIKE "%'.$params[103].'%"';
		}
	if ($params[105]!=''){
		$sSQLadd=$sSQLadd.' AND T1.unad40consec LIKE "%'.$params[105].'%"';
		}
	$sHoy=fecha_hoy();
	$iHoy=fecha_EnNumero($sHoy);
	$sListaLlaves='';
	$sTitulos='';
	$iRegistro=0;
	$idBloque=-1;
	$iNumLab=0;
	if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Tablero: Iniciando lectura de bloques (Consulta V2)<br>';}
	//Tenemos que mostrar las tablas en bloques.... Se toma el listado de bloques.
	$sSQL='SELECT T44.unad44nombre, T44.unad44id FROM unad44bloque AS T44 WHERE T44.unad44id='.$idBloqueCursos.' ORDER BY T44.unad44orden, T44.unad44id';
	$tablab=$objDB->ejecutasql($sSQL);
	while ($filab=$objDB->sf($tablab)){
		//Se recorre uno a uno los bloques de servidores.
		$sErrConsulta='';
		$sSQL='SELECT TB.unad47peraca, TB.unad47idnav, T2.exte02titulo, TB.unad47idrol, TB.unad47idcurso, T1.unad40nombre, TB.unad47numaula, TB.unad47idgrupo, TB.unad47idceadasiste, T3.unad39nombrecomun, T3.unad39nombre, T3.unad39idbloque, T1.unad40incluyelaboratorio, T1.unad40incluyesalida 
FROM unad47tablero AS TB, unad40curso AS T1, exte02per_aca AS T2, unad39nav AS T3  
WHERE TB.unad47idtercero='.$idTercero.' AND TB.unad47activo="S" AND TB.unad47retirado="N" AND TB.unad47idcurso=T1.unad40id AND TB.unad47peraca=T2.exte02id AND TB.unad47idnav=T3.unad39id AND T3.unad39idbloque='.$idBloqueCursos.' AND T3.unad39activo="S" '.$sSQLadd.'
ORDER BY TB.unad47peraca DESC, T1.unad40nombre';
		$tabladetalle=$objDB->ejecutasql($sSQL);
		$registros=$objDB->nf($tabladetalle);
		if ((($registros-1)/$lineastabla)<($pagina-1)){$pagina=(int)(($registros-1)/$lineastabla)+1;}
		$limite='';
		if ($registros>$lineastabla){
			$rbase=($pagina-1)*$lineastabla;
			$limite=' LIMIT '.$rbase.', '.$lineastabla;
			$tabladetalle=$objDB->ejecutasql($sSQL.$limite);
			}
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Tablero: Se han cargado los datos para el bloque '.$filab['unad44id'].'<br>';}
		if ($registros>0){
			$res=$res.$sErrConsulta.'<div class="tablaCursos" id="div_f17detalle_'.$idBloqueCursos.'">
<h3 class="tituloContenedor">'.cadena_notildes($filab['unad44nombre']).' &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
'.html_paginador('paginaf17_'.$idBloqueCursos, $registros, $lineastabla, $pagina, 'paginarf17('.$idBloqueCursos.')').'
'.html_lpp('lppf17_'.$idBloqueCursos, $lineastabla, 'paginarf17('.$idBloqueCursos.')', 1000).'
</h3>
<table>';
			}else{
			$res='<input id="paginaf17_'.$idBloqueCursos.'" name="paginaf17_'.$idBloqueCursos.'" type="hidden" value="'.$pagina.'"/>
<input id="lppf17_'.$idBloqueCursos.'" name="lppf17_'.$idBloqueCursos.'" type="hidden" value="'.$lineastabla.'"/>';
			}
	$tlinea=1;
	$idPeraca=-1;
	while($filadet=$objDB->sf($tabladetalle)){
		if ($idBloque!=$filab['unad44id']){
			//Abrir la tabla.
			$idBloque=$filab['unad44id'];
			}
		if ($idPeraca!=$filadet['unad47peraca']){
			$idPeraca=$filadet['unad47peraca'];
			$res=$res.'<tr class="subTitulo">
<td width="80px"></td>
<td colspan="2">Periodo <b>'.cadena_notildes($filadet['exte02titulo']).'</b></td>
</tr>';
			}
		$sPrefijo='';
		$sSufijo='';
		$sClass=' class="info"';
		$sLink='';
		//if(($tlinea%2)==0){$sClass=' class="info"';}
		$tlinea++;
		$babierta=true;
		if ($babierta){
			$sTitNav=cadena_notildes($filadet['unad39nombrecomun']);
			if (trim($sTitNav)==''){
				$sTitNav=cadena_notildes($filadet['unad39nombre']);
				}
			$sLink='<a href="javascript:redir('.$filadet['unad47peraca'].', '.$filadet['unad47idnav'].', '.$filadet['unad47idcurso'].', '.$filadet['unad47numaula'].')" class="lnktablero">'.$sTitNav.'</a>';
			}
		$sNomAula='A';
		switch($filadet['unad47numaula']){
			case 2:$sNomAula='B';break;
			case 3:$sNomAula='C';break;
			case 4:$sNomAula='D';break;
			case 5:$sNomAula='E';break;
			case 6:$sNomAula='F';break;
			case 8:$sNomAula='H';break;
			case 19:$sNomAula='R';break;
			case 20:$sNomAula='S';break;
			}
		$sNombreCurso=$filadet['unad47idcurso'].$sNomAula.'_'.$filadet['unad47peraca'];
		//$sNombreCurso=$filadet['unad47idcurso'];
		$res=$res.'<tr'.$sClass.'>
<td>'.$sNombreCurso.'</td>
<td>'.cadena_notildes($filadet['unad40nombre']).'</td>
<td>'.$sLink.'</td>
</tr>';
		$bConSalida=false;
		$bConLaboratorio=false;
		$sNotificacionCurso='';
		if ($filadet['unad40incluyelaboratorio']=='S'){
			//Solo los estudiantes se pueden inscribir.
			if ($filadet['unad47idrol']==5){$bConLaboratorio=true;}
			}
		if ($filadet['unad40incluyesalida']=='S'){
			//Solo los estudiantes se pueden inscribir.
			if ($filadet['unad47idrol']==5){$bConSalida=true;}
			}
		if ($bConLaboratorio){
			$iNumLab++;
			list($sInfoLab, $sInfoDebugLab, $sNotificacionCursoL)=f17_DatosLaboratorio($idTercero, $filadet['unad47peraca'], $filadet['unad47idcurso'], $iHoy, $iNumLab, $objDB, $bDebug);
			if ($bDebug){$sDebug=$sDebug.$sInfoDebugLab;}
			$sNotificacionCurso=$sNotificacionCurso.$sNotificacionCursoL;
			$res=$res.'<tr'.$sClass.'>
<td></td><td colspan="2">'.$sInfoLab.'</td>
</tr>';
			//Fin de si tiene laboratorio.
			}
		if ($bConSalida){
			$iNumLab++;
			list($sInfoLab, $sInfoDebugLab)=f17_DatosSalidas($idTercero, $filadet['unad47peraca'], $filadet['unad47idcurso'], $iHoy, $iNumLab, $objDB, $bDebug);
			if ($bDebug){$sDebug=$sDebug.$sInfoDebugLab;}
			$res=$res.'<tr'.$sClass.'>
<td></td><td colspan="2">'.$sInfoLab.'</td>
</tr>';
			//Fin de si tiene laboratorio.
			}
		if ($sNotificacionCurso!=''){
			if ($sNotificar!=''){$sNotificar=$sNotificar.'<br>';}
			$sNotificar=$sNotificar.'<b>Curso '.$sNombreCurso.' '.cadena_notildes($filadet['unad40nombre']).'</b><br>'.$sNotificacionCurso;
			}
		}
	if ($registros>0){
		$res=$res.'</table></div>';
		}
	if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Tablero: Termina lectura del bloque '.$filab['unad44id'].'<br>';}
		//Fin de recorrer cada grupo de servidores
		}
	return array(utf8_encode($res), utf8_encode($sNotificar), $sDebug);
	}
function f17_NoCursos($idTercero, $objDB, $bDebug=false){
	$mensajes_17='lg/lg_17_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_17)){$mensajes_17='lg/lg_17_es.php';}
	require $mensajes_17;
	$sInfoTercero='';
	$sDebug='';
	$sSQL='SELECT unad11doc, unad11usuario FROM unad11terceros WHERE unad11id='.$idTercero;
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$sInfoTercero=' No olvide incluir la siguiente informaci&oacute;n: Nombre de usuario <b>'.$fila['unad11usuario'].'</b> Documento <b>'.$fila['unad11doc'].'</b>';
		}
	$res=$ETI['msg_nocursos'].$sInfoTercero;
	if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Tablero: No tiene cursos<br>';}
	return array($res, $sDebug);
	}
function f17_HtmlTablaAgenda($params){
	$_SESSION['u_ultimominuto']=iminutoavance();
	$sError='';
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	list($sDetalle, $sNada)=f17_TablaAgendaV2($params, $objDB);
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f14detalle', 'innerHTML', $sDetalle);
	return $objResponse;
	}
function f17_HtmlTabla($params){
	$_SESSION['u_ultimominuto']=iminutoavance();
	$sError='';
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	list($sDetalle, $sInfoNotifica, $sDebug)=f17_TablaTablero($params, $objDB);
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f17detalle', 'innerHTML', $sDetalle);
	return $objResponse;
	}
function f17_HtmlTablaV2($params){
	$_SESSION['u_ultimominuto']=iminutoavance();
	$sError='';
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	list($sDetalle, $sInfoNotifica, $sDebug)=f17_TablaTableroV2($params, $objDB);
	$objDB->CerrarConexion();
	$idBloque=$params[104];
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f17detalle_'.$idBloque, 'innerHTML', $sDetalle);
	return $objResponse;
	}
// -- Espacio para incluir funciones xajax personalizadas.
function AccederNav($params){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	$sError='';
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	//Ver si hay un atenticador para el peraca nav.
	$objResponse=new xajaxResponse();
	$objResponse->assign('frmredir', 'action', 'pepe.php');
	$objResponse->call('termina()');
	return $objResponse;
	}

function f17_ComboAgendas($valor, $idTercero, $objDB){
	$res='<select id="bcurso" name="bcurso" onChange="paginarf14()"><option value="" style="color:#FF0000">{Todos}</option>';
	$sIdent=array('','A','B','C','D','E','F','G','H','9','0','1','2','3','4','5','6','7','8','19','S');
//, unad47idcurso, 
	$sSQL='SELECT unad47peraca, unad47idcurso, unad47numaula FROM unad47tablero WHERE unad47idtercero='.$idTercero.' AND unad47activo="S" AND unad47retirado="N" AND unad47peraca<>87 GROUP BY unad47peraca, unad47idcurso, unad47numaula ORDER BY unad47peraca DESC, unad47idcurso';
	$tabla=$objDB->ejecutasql($sSQL);
	while ($fila=$objDB->sf($tabla)){
		$sMuestra=$fila['unad47idcurso'].$sIdent[$fila['unad47numaula']].'_'.$fila['unad47peraca'];
		$sAdd='';
		if ($valor==$sMuestra){$sAdd=' Selected';}
		$res=$res.'<option value="'.$fila['unad47peraca'].'_'.$fila['unad47idcurso'].'_'.$fila['unad47numaula'].'"'.$sAdd.'>'.$sMuestra.'</option>';
		}
	$res=$res.'</select>';
	return $res;
	}
function ResolverLogin($datos){
	if(!is_array($datos)){$datos=json_decode(str_replace('\"','"',$datos),true);}
	$_SESSION['u_ultimominuto']=iminutoavance();
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	list($sError, $enlacecampus, $username, $userlogin, $sIdMoodle)=infoRedir($datos[1], $datos[2], $datos[3], $datos[4], $objDB);
	$objResponse=new xajaxResponse();
	if ($sError==''){
		$sHTML='<form id="frmlogin" name="frmlogin" method="post" action="'.$enlacecampus.'" target="_blank">
<input id="username" name="username" type=hidden value="'.$username.'"/>
<input id="password" name="password" type=hidden value="'.$userlogin.'"/>
<input id="_COURSE" name="_COURSE" type=hidden value="'.$sIdMoodle.'"/>
</form>';
		$objResponse->assign('div_login', 'innerHTML', $sHTML);
		$objResponse->call('enviaredir');
		}else{
		$objResponse->assign('alarma', 'innerHTML', $sError);
		}
	return $objResponse;
	}
function f17_RevisarBloqueos($idTercero, $objDB, $bDebug=false){
	$bBloqueado=false;
	$sDebug='';
	if ($idTercero==0){
		return false;
		die();
		}
	$iNumBloqueo=0;
	$sInfoBloqueo='';
	$sScript='';
	$sFormBloqueo='';
	if ($bDebug){$sDebug=$sDebug.''.fecha_microtiempo().' Consultando bloqueos<br>';}
	//Vemos en que peracas tienen cursos...
	$sSQL='SELECT * FROM unad63bloqueo WHERE unad63vigente="S" AND unad63bloqueado="N"';
	$tabla63=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla63)>0){
		if ($bDebug){$sDebug=$sDebug.''.fecha_microtiempo().' Se han encontrado '.$objDB->nf($tabla63).' bloqueos activos<br>';}
		//Vamos a necesitar algunas cosas...
		$sSQL='SELECT unad11doc FROM unad11terceros WHERE unad11id='.$idTercero.'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$fila=$objDB->sf($tabla);
			$sDocTercero=$fila['unad11doc'];
			}else{
			if ($bDebug){$sDebug=$sDebug.''.fecha_microtiempo().' ERROR: NO SE HA ENCONTRADO AL ESTUDIANTE EN LA TABLA 11<br>';}
			}
		}
	while ($fila63=$objDB->sf($tabla63)){
		//Cargar la db de origen...
		$bConectaDB=true;
		$bIngresa=true;
		if ($fila63['unad63peraca']!=0){
			//Ver si le aplica el bloqueo para este peraca.
			$sSQL='SELECT unad47idtercero FROM unad47tablero WHERE unad47idtercero='.$idTercero.' AND unad47peraca='.$fila63['unad63peraca'].' AND unad47activo="S"';
			$tabla=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla)==0){
				$bIngresa=false;
				$bConectaDB=false;
				if ($bDebug){$sDebug=$sDebug.''.fecha_microtiempo().' No se registras cursos en el periodo bloqueado {'.$fila63['unad63peraca'].'}<br>';}
				}
			}
		if ($bIngresa){
			if ($fila63['unad63origendatos']==0){
				$objDBext=new clsdbadmin($fila63['unad63dbservidor'], $fila63['unad63dbusuario'], $fila63['unad63dbclave'], $fila63['unad63dbnombre']);
				if ($fila63['unad63dbpuerto']!=''){$objDBext->dbPuerto=$fila63['unad63dbpuerto'];}
				if ($bDebug){$sDebug=$sDebug.''.fecha_microtiempo().' Invocando datos externos para el bloqueo '.$fila63['unad63consec'].' - Usuario '.$fila63['unad63dbusuario'].', Base de datos '.$fila63['unad63dbnombre'].' <!-- Conectando a Servidor:'.$fila63['unad63dbservidor'].' Puerto:'.$fila63['unad63dbpuerto'].', Clave '.$fila63['unad63dbclave'].' --><br>';}
				if (!$objDBext->Conectar()){
					if ($bDebug){$sDebug=$sDebug.''.fecha_microtiempo().' FALLA AL INTENTAR CONECTAR CON LA BASE DE DATOS <b>'.$objDBext->serror.'</b><br>';}
					$bConectaDB=false;
					//Bloquear y enviar el mensaje a soporte.campus
					$sSQL='UPDATE unad63bloqueo SET unad63bloqueado="S" WHERE unad63id='.$fila63['unad63id'].'';
					$tabla=$objDB->ejecutasql($sSQL);
					$idSMTP=2;
					$sSuperior='';
					$sCuerpo='Se ha suspendido el bloqueo '.$fila63['unad63consec'].', NO ha sido posible conectarse a la base de datos <br>'.$objDBext->serror.'';
					$sInferior='';
					$objMail=new clsMail_Unad($objDB);
					$objMail->NuevoMensaje();
					$objMail->TraerSMTP($idSMTP);
					$objMail->addCorreo('soporte.campus@unad.edu.co');
					$objMail->sAsunto='AVISO DE SUSPENCION BLOQUEO EN EL TABLERO';
					$objMail->sCuerpo=$sSuperior.$sCuerpo.$sInferior;
					$sError=$objMail->Enviar();
					if ($bDebug){
						if ($sError==''){
							$sDebug=$sDebug.'Se ha enviado el correo a '.$sMail.'<br>';
							}else{
							$sDebug=$sDebug.'Respuesta al enviar correo a '.$sMail.' {'.$objDBext->serror.'}<br>';
							}
						}
					}
				$sCondi=cadena_Reemplazar($fila63['unad63condicion'], '|@documento|', $sDocTercero);
				$sCondi=cadena_Reemplazar($sCondi, '[', '"');
				$sCondi=cadena_Reemplazar($sCondi, ']', '"');
				$sSQL='SELECT '.$fila63['unad63campo'].' FROM '.$fila63['unad63tabla'].' WHERE '.$sCondi;
				}else{
				$objDBext=$objDB;
				if ($fila63['unad63idconvenio']==0){
					$sSQL='SELECT unad65id FROM unad65bloqueados WHERE unad65idbloqueo='.$fila63['unad63id'].' AND unad65idtercero='.$idTercero.'';
					}else{
					$sSQL='SELECT core51id FROM core51convenioest WHERE core51idconvenio='.$fila63['unad63idconvenio'].' AND core51idtercero='.$idTercero.'';
					}
				}
			}
		if ($bConectaDB){
			$tablaext=$objDBext->ejecutasql($sSQL);
			if ($tablaext==false){
				//Falla la consulta pailas...
				if ($bDebug){$sDebug=$sDebug.''.fecha_microtiempo().' ERROR: CONSULTA NO EJECUTADA: '.$sSQL.'<br>Error reportado: '.$objDBext->serror.'<br>';}
				}else{
				//ahora si analizamos el tipo de bloqueo...
				if ($bDebug){$sDebug=$sDebug.''.fecha_microtiempo().' Analizando el tipo de bloqueo '.$fila63['unad63id'].' - '.$fila63['unad63titulo'].'<br> '.$sSQL.'<br>';}
				$bCumpleBloqueo=false;
				$iNumFilasExt=$objDBext->nf($tablaext);
				if ($fila63['unad63tiporesultado']==0){
					if ($iNumFilasExt==0){$bCumpleBloqueo=true;}
					}else{
					if ($iNumFilasExt!=0){
						//Si es bloqueo compuesto debemos seguir...
						$bCumpleBloqueo=true;
						}
					}
				if ($bCumpleBloqueo){
					if ($bDebug){$sDebug=$sDebug.''.fecha_microtiempo().' <b>Se muestran los datos del bloqueo '.$fila63['unad63id'].'</b><br>';}
					//ahora si cargar la info del bloqueo.
					$iNumBloqueo++;
					if ($fila63['unad63tipocontrol']==1){$bBloqueado=true;}
					if ($sInfoBloqueo!=''){$sInfoBloqueo=$sInfoBloqueo.'<br>';}
					//$sInfoBloqueo=$sInfoBloqueo.$iNumBloqueo.' - '.$fila63['unad63detalle'];
					$sInfoBloqueo=$sInfoBloqueo.''.$fila63['unad63detalle'];
					if ($fila63['unad63redir']!=''){
						$sScript=$sScript.'
function bloqueoredir'.$iNumBloqueo.'(){
window.document.frmredir'.$iNumBloqueo.'.submit();
}
';
						$sMetodo='post';
						if ($fila63['unad63redirmetodo']!='P'){$sMetodo='get';}
						$sVars='';
						$sSQL='SELECT unad64nomvar, unad64valor FROM unad67bloqueovarpost WHERE unad64idbloqueo='.$fila63['unad63id'].' AND unad64idsegcon=0';
						$tabla64=$objDB->ejecutasql($sSQL);
						while ($fila64=$objDB->sf($tabla64)){
							$sVrVar=cadena_Reemplazar($fila64['unad64valor'], '|@documento|', $sDocTercero);
							$sVrVar=cadena_Reemplazar($sVrVar, '[', '"');
							$sVrVar=cadena_Reemplazar($sVrVar, ']', '"');
							$sVars=$sVars.'<input id="'.$fila64['unad64nomvar'].'" name="'.$fila64['unad64nomvar'].'" type="hidden" value="'.$sVrVar.'" />';
							}
						$sFormBloqueo=$sFormBloqueo.'
<form id="frmredir'.$iNumBloqueo.'" name="frmredir'.$iNumBloqueo.'" action="'.$fila63['unad63redir'].'" method="'.$sMetodo.'" target="_blank">'.$sVars.'
</form>';
						$sTituloLink='Ir';
						if ($fila63['unad63titulolink']!=''){$sTituloLink=cadena_notildes($fila63['unad63titulolink']);}
						$sInfoBloqueo=$sInfoBloqueo.' <a href="javascript:bloqueoredir'.$iNumBloqueo.'()" class="lnkresalte">'.$sTituloLink.'</a>';
						}
					}
				}
			//Termina si se pudo conectar a la db del bloqueo.
			}
		}
	if ($sScript!=''){
		$sFormBloqueo='
<script language="javascript">
<!--
'.$sScript.'
// -->
</script>
'.$sFormBloqueo;
		}
	return array($bBloqueado, $sInfoBloqueo, $sFormBloqueo, $sDebug);
	}
function f17_SesionCoordenadas($params){
	require './app.php';
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	if (isset($params[1])==0){$params[1]=0;}
	if (isset($params[2])==0){$params[2]=0;}
	if (isset($params[3])==0){$params[3]='';}
	if (isset($params[4])==0){$params[4]='';}
	if (isset($params[5])==0){$params[5]=-1;}
	if (isset($params[6])==0){$params[6]=0;}
	$params[1]=numeros_validar($params[1]);//El id de la sesion.
	$params[2]=numeros_validar($params[2]);//El idTercero que esta entrando...
	if ($params[1]==''){$params[1]=0;}
	if ($params[2]==''){$params[2]=0;}
	if ($params[1]!=0){
		//Actualizamos la sesion, el tercero y las variables...
		$_SESSION['unad_geo_lat']=$params[3];
		$_SESSION['unad_geo_lon']=$params[4];
		$_SESSION['unad_geo_pre']=$params[5];
		$latGrados=0;
		$latDecimas='';
		$lonGrados=0;
		$lonDecimas='';
		$iIni=strpos($params[3], '.');
		$latGrados=substr($params[3],0, $iIni);
		$latDecimas=substr($params[3], $iIni+1, 10);
		$iIni=strpos($params[4], '.');
		$lonGrados=substr($params[4],0, $iIni);
		$lonDecimas=substr($params[4], $iIni+1, 10);
		if ($params[5]!=-1){
			$iPrecision=(int)$params[5];
			if ($iPrecision==''){$iPrecision=-1;}
			}else{
			$iPrecision=-1;
			}
		$bLocaliza=false;
		if ($latDecimas!=''){
			$bLocaliza=true;
			}else{
			//Si la persona es sospechosa no puede localizar.
			$sSQL='SELECT 1 FROM unae13enrevision WHERE unae13idtercero='.$params[2].' AND unae13estado<>2 LIMIT 0, 1';
			$tabla=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla)==0){
				$bLocaliza=true;
				}
			}
		if ($bLocaliza){
			if (isset($_SESSION['unad_agno'])==0){$_SESSION['unad_agno']='';}
			$iHora=fecha_hora();
			$iMin=fecha_minuto();
			$iSeg=fecha_segundo();
			$sNomTabla71='unad71sesion'.fecha_AgnoMes();
			$sSQL='UPDATE '.$sNomTabla71.' SET unad71latgrados='.$latGrados.', unad71latdecimas="'.$latDecimas.'", unad71longrados='.$lonGrados.', unad71longdecimas="'.$lonDecimas.'", unad71proximidad='.$iPrecision.', unad71horalocaliza='.$iHora.', unad71minlocaliza='.$iMin.', unad71seglocaliza='.$iSeg.' WHERE unad71id='.$params[1];
			$tabla=$objDB->ejecutasql($sSQL);
			$sCompleta='';
			if ($params[6]==1){
				//Primero ver que no tenga una fecha para terminos.
				$sSQL='SELECT unad11fechaterminos FROM unad11terceros WHERE unad11id='.$params[2].'';
				$tabla=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($tabla)>0){
					$fila=$objDB->sf($tabla);
					if ($fila['unad11fechaterminos']<1){
						$iHoy=fecha_DiaMod();
						//$sCompleta=', unad11fechaterminos='.$iHoy.'';
						$sSQL='UPDATE unad11terceros SET unad11fechaterminos='.$iHoy.' WHERE unad11id='.$params[2].'';
						$tabla=$objDB->ejecutasql($sSQL);
						}
					}
				}
			}
		//Noviembre 22 de 2019 - Se cancela la actualizacion de las coordenads del alumno en cada ingreso ahora se hara en un proceso sobre los historicos.
		//$sSQL='UPDATE unad11terceros SET unad11latgrados='.$latGrados.', unad11latdecimas="'.$latDecimas.'", unad11longrados='.$lonGrados.', unad11longdecimas="'.$lonDecimas.'"'.$sCompleta.' WHERE unad11id='.$params[2].'';
		//$tabla=$objDB->ejecutasql($sSQL);
		$objDB->CerrarConexion();
		if ($bLocaliza){
			if ($params[6]==1){
				$objResponse=new xajaxResponse();
				$objResponse->call('procesoterminado');
				return $objResponse;
				}
			}else{
			$objResponse=new xajaxResponse();
			$objResponse->call('msg_nolocaliza');
			return $objResponse;
			}
		}
	}
function f17_MoodleMobile($params){
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	$_SESSION['u_ultimominuto']=iminutoavance();
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$bDebug=false;
	$idTercero=$params[1];
	$sDebug='';
	$sHTML='<div class="MarquesinaMedia">Usted no esta registrado como estudiante o profesor en un curso que cuente con Moodle Mobile.</div>';
	$sSQLadd='';
	$idNav='';
	$idCurso='';
	//Noviembre 21 de 2019 - Aquellos que sean sospechosos no pueden acceder a mobile
	$bEntra=true;
	$iHoy=fecha_DiaMod();
	$iDiaBase=fecha_NumSumarDias($iHoy, -180);
	$sSQL='SELECT 1 FROM unae13enrevision WHERE unae13idia>='.$iDiaBase.' AND unae13idtercero='.$idTercero.' LIMIT 0, 1';
	//$bDebug=true;
	//$sDebug=$sDebug.'...<!-- '.$sSQL.' -->'.
	$tabla=$objDB->ejecutasql($sSQL);
	while($fila=$objDB->sf($tabla)){
		$bEntra=false;
		}
	if ($bEntra){
		$sSQL='SELECT TB.unad47peraca, TB.unad47idnav, TB.unad47idcurso, T1.unad40nombre, T3.unad39nombrecomun, T3.unad39moddlemovurl 
FROM unad47tablero AS TB, unad40curso AS T1, unad39nav AS T3  
WHERE TB.unad47idtercero='.$idTercero.' AND TB.unad47idrol IN (4, 5) AND TB.unad47activo="S" AND TB.unad47retirado="N" AND TB.unad47idcurso=T1.unad40id AND TB.unad47idnav=T3.unad39id AND T3.unad39activo="S" AND T3.unad39moddlemovile="S" '.$sSQLadd.'
ORDER BY TB.unad47idnav DESC, T1.unad40nombre';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$sHTML='';
			}
		while($fila=$objDB->sf($tabla)){
			if ($idNav!=$fila['unad47idnav']){
				$idNav=$fila['unad47idnav'];
				list($sErrorRed, $enlacecampus, $username, $userlogin, $sIdMoodle)=infoRedirV2($fila['unad47peraca'], $fila['unad47idnav'], 0, 1, $idTercero, $objDB, $bDebug);
				$sClaveNav=$userlogin;
				$sHTML=$sHTML.'Para acceder a <b>'.cadena_notildes($fila['unad39nombrecomun']).'</b> utilice el url <b>'.$fila['unad39moddlemovurl'].'</b> Clave: '.$sClaveNav.'<br> Encontrar&aacute; los siguientes cursos:<br>';
				}
			$sHTML=$sHTML.''.$fila['unad47idcurso'].' '.cadena_notildes($fila['unad40nombre']).'<br>';
			}
		}
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_configmoodlemobile', 'innerHTML', $sHTML);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
?>