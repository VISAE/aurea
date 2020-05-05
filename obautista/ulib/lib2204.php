<?php
function f2204_TablaDetalleV2($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2204=$APP->rutacomun.'lg/lg_2204_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2204)){$mensajes_2204=$APP->rutacomun.'lg/lg_2204_es.php';}
	require $mensajes_todas;
	require $mensajes_2204;
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	if (isset($aParametros[100])==0){$aParametros[100]=-1;}
	if (isset($aParametros[101])==0){$aParametros[101]=1;}
	if (isset($aParametros[102])==0){$aParametros[102]=20;}
	//if (isset($aParametros[103])==0){$aParametros[103]='';}
	//$aParametros[103]=numeros_validar($aParametros[103]);
	$aParametros[100]=numeros_validar($aParametros[100]);
	$sDebug='';
	$idTercero=$aParametros[100];
	$pagina=$aParametros[101];
	$lineastabla=$aParametros[102];
	$babierta=false;
	list($idBloque, $sError)=f1011_BloqueTercero($idTercero, $objDB);
	//$sSQL='SELECT Campo FROM core16actamatricula WHERE core16id='.$core16tercero;
	//$tabla=$objDB->ejecutasql($sSQL);
	//if ($objDB->nf($tabla)>0){
		//$fila=$objDB->sf($tabla);
		//if ($fila['Campo']!='S'){$babierta=true;}
		//}
	$sSQLadd='';
	$sSQLadd1='';
	$sLeyenda='';
	if ($idTercero==0){
		$sLeyenda='<div class="salto1px"></div>
<div class="GrupoCamposAyuda">
<b>No ha sido seleccionado un documento a consultar.</b>
<div class="salto1px"></div>
</div>';
		return array($sLeyenda.'<input id="paginaf2204" name="paginaf2204" type="hidden" value="'.$pagina.'"/><input id="lppf2204" name="lppf2204" type="hidden" value="'.$lineastabla.'"/>', $sDebug);
		die();
		}
	//if ((int)$aParametros[103]!=-1){$sSQLadd=$sSQLadd.' AND TB.campo='.$aParametros[103];}
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
	$sTabla='core04matricula_'.$idBloque;
	$sTitulos='Peraca, Tercero, Curso, Aula, Rol, Id, Nav, Grupo';
	$sSQL='SELECT T3.unad40consec, T3.unad40nombre, TB.core04idaula, TB.core04id, TB.core04idgrupo, TB.core04peraca, TB.core04idcurso, TB.core04idrol, TB.core04idnav, TB.core04aplicoagenda 
FROM '.$sTabla.' AS TB, unad40curso AS T3  
WHERE '.$sSQLadd1.' TB.core04tercero='.$idTercero.' AND TB.core04idcurso=T3.unad40id '.$sSQLadd.'
ORDER BY TB.core04peraca DESC, TB.core04idcurso, TB.core04idaula, TB.core04idrol';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_2204" name="consulta_2204" type="hidden" value="'.$sSQLlista.'"/>
<input id="titulos_2204" name="titulos_2204" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objDB->ejecutasql($sSQL);
	if ($tabladetalle==false){
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 2204: '.$sSQL.'<br>';}
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		//$sLeyenda=$sSQL;
		}else{
		$registros=$objDB->nf($tabladetalle);
		if ($registros==0){
			//return array(utf8_encode($sErrConsulta.'<input id="paginaf2204" name="paginaf2204" type="hidden" value="'.$pagina.'"/><input id="lppf2204" name="lppf2204" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
<td colspan="2"><b>'.$ETI['core04idcurso'].'</b></td>
<td><b>'.$ETI['core04idaula'].'</b></td>
<td><b>'.$ETI['core04idgrupo'].'</b></td>
<td><b>'.'Actividades'.'</b></td>
<td align="right">
'.html_paginador('paginaf2204', $registros, $lineastabla, $pagina, 'paginarf2204()').'
'.html_lpp('lppf2204', $lineastabla, 'paginarf2204()').'
</td>
</tr>';
	$tlinea=1;
	$core16peraca=-1;
	while($filadet=$objDB->sf($tabladetalle)){
		if ($core16peraca!=$filadet['core04peraca']){
			$core16peraca=$filadet['core04peraca'];
			$sPeriodo='{'.$core16peraca.'}';
			$res=$res.'<tr class="fondoazul">
<td colspan="6">'.$ETI['core04peraca'].' <b>'.$sPeriodo.'</b></td>
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
		$et_codcurso=$sPrefijo.$filadet['unad40consec'].$sSufijo;
		$et_core04idcurso=$sPrefijo.cadena_notildes($filadet['unad40nombre']).$sSufijo;
		$et_core04idaula=$sPrefijo.$filadet['core04idaula'].$sSufijo;
		$et_core04idgrupo=$sPrefijo.'{'.$ETI['msg_ninguno'].'}'.$sSufijo;
		if ($filadet['core04idgrupo']!=0){
			$et_core04idgrupo=$sPrefijo.'{'.$filadet['core04idgrupo'].'}'.$sSufijo;
			}
		$sActividades='{Pendiente}';
		if ($filadet['core04aplicoagenda']!=0){
			$sActividades='{Sin actividades.}';
			$sSQL='SELECT core05estado, COUNT(core05id) AS Total FROM core05actividades_'.$idBloque.' WHERE core05tercero='.$idTercero.' AND core05idcurso='.$filadet['core04idcurso'].' AND core05peraca='.$core16peraca.' GROUP BY core05estado';
			$tabla=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla)>0){
				$sActividades='';
				while ($fila=$objDB->sf($tabla)){
					switch($fila['core05estado']){
						case 0:
						$sTituloEstado='Pendientes';
						break;
						case 1:
						$sTituloEstado='Iniciadas';
						break;
						case 3:
						$sTituloEstado='Presentadas';
						break;
						case 5:
						$sTituloEstado='No presentadas';
						break;
						case 7:
						$sTituloEstado='Calificadas';
						break;
						default:
						$sTituloEstado='[Estado '.$fila['core05estado'].']';
						break;
						}
					$sActividades=$sActividades.' '.$fila['Total'].' '.$sTituloEstado;
					}
				}
			}
		if ($babierta){
			//$sLink='<a href="javascript:cargaridf2204('.$filadet['core04id'].')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$res=$res.'<tr'.$sClass.'>
<td>'.$et_codcurso.'</td>
<td>'.$et_core04idcurso.'</td>
<td>'.$et_core04idaula.'</td>
<td>'.$et_core04idgrupo.'</td>
<td colspan="2">'.$sActividades.'</td>
</tr>';
		}
	$res=$res.'</table>';
	$objDB->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
	}
function f2204_HtmlTabla($aParametros){
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
	list($sDetalle, $sDebugTabla)=f2204_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f2204detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
?>