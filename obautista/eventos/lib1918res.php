<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2015 - 2016 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
*/
function f1918_TablaDetalleV2Res($params, $objdb, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_1918='lg/lg_1918_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1918)){$mensajes_1918='lg/lg_1918_es.php';}
	require $mensajes_todas;
	require $mensajes_1918;
	$sDebug='';
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	if (isset($params[0])==0){$params[0]=-1;}
	if (isset($params[101])==0){$params[101]=1;}
	if (isset($params[102])==0){$params[102]=20;}
	if (isset($params[103])==0){$params[103]=0;}
	if (isset($params[104])==0){$params[104]='';}
	if (isset($params[105])==0){$params[105]='';}
	$params[104]=numeros_validar($params[104]);
	$params[105]=numeros_validar($params[105]);
	$params[0]=numeros_validar($params[0]);
	if ($params[0]==''){$params[0]=-1;}
	$params[103]=numeros_validar($params[103]);
	$even16id=$params[0];
	$pagina=$params[101];
	$lineastabla=$params[102];
	$sCondi='';
	$sCondi2='';
	$bHayFiltro=false;
	if ($params[104]!=''){
		$sCondi=' AND TB.even21idperaca='.$params[104].'';
		$sCondi2=' AND T1.even21idperaca='.$params[104].'';
		$bHayFiltro=true;
		}
	if ($params[105]!=''){
		$sCondi=' AND TB.even21idcurso='.$params[105].'';
		$sCondi2=' AND T1.even21idcurso='.$params[105].'';
		$bHayFiltro=true;
		}
	$bConResultados=false;
	$iPendientes=0;
	$iUniverso=0;
	$sql='SELECT COUNT(TB.even21id) AS total, TB.even21terminada FROM even21encuestaaplica AS TB WHERE TB.even21idencuesta='.$even16id.$sCondi.' GROUP BY TB.even21terminada';
	$tabla=$objdb->ejecutasql($sql);
	while ($fila=$objdb->sf($tabla)){
		if ($fila['even21terminada']=='S'){
			$iUniverso=$iUniverso+$fila['total'];
			}else{
			$iPendientes=$iPendientes+$fila['total'];
			}
		}
	$babierta=false;
	$sqladd='';
	//if ((int)$params[103]!=-1){$sqladd=$sqladd.' AND TB.campo='.$params[103];}
	//if ($params[103]!=''){$sqladd=$sqladd.' AND TB.campo2 LIKE "%'.$params[103].'%"';}
	$sTitulos='Encuesta, Consec, Id, Grupo, Pregunta, Tiporespuesta, Opcional, Concomentario, Rpta0, Rpta1, Rpta2, Rpta3, Rpta4, Rpta5';
	$sql='SELECT TB.even18idencuesta, TB.even18consec, TB.even18id, T4.even19nombre, TB.even18pregunta, T6.even20nombre, TB.even18opcional, TB.even18concomentario, TB.even18rpta0, TB.even18rpta1, TB.even18rpta2, TB.even18rpta3, TB.even18rpta4, TB.even18rpta5, TB.even18rpta6, TB.even18rpta7, TB.even18rpta8, TB.even18rpta9, TB.even18idgrupo, TB.even18tiporespuesta, TB.even18orden, TB.even18divergente, TB.even18idpregcondiciona, TB.even18valorcondiciona 
FROM even18encuestapregunta AS TB, even19encuestagrupo AS T4, even20tiporespuesta AS T6 
WHERE TB.even18idencuesta='.$even16id.' AND TB.even18idgrupo=T4.even19id AND TB.even18tiporespuesta=T6.even20id '.$sqladd.'
ORDER BY TB.even18orden, TB.even18consec';
	$sqllista=str_replace("'","|",$sql);
	$sqllista=str_replace('"',"|",$sqllista);
	$sErrConsulta='<input id="consulta_1918" name="consulta_1918" type="hidden" value="'.$sqllista.'"/>
<input id="titulos_1918" name="titulos_1918" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objdb->ejecutasql($sql);
	if ($tabladetalle==false){
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 1918: '.$sql.'<br>';}
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sql.' '.$objdb->serror.'"/>';
		}else{
		$registros=$objdb->nf($tabladetalle);
		if ($registros==0){
			return array(utf8_encode($sErrConsulta.'<input id="paginaf1918" name="paginaf1918" type="hidden" value="'.$pagina.'"/><input id="lppf1918" name="lppf1918" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
			}
		if ((($registros-1)/$lineastabla)<($pagina-1)){$pagina=(int)(($registros-1)/$lineastabla)+1;}
		if ($registros>$lineastabla){
			$rbase=($pagina-1)*$lineastabla;
			$limite=' LIMIT '.$rbase.', '.$lineastabla;
			$tabladetalle=$objdb->ejecutasql($sql.$limite);
			}
		}
	$iTope=50;
	$sTituloUniverso='';
	$iTope=200;
	$iTotalAplicadas=$iPendientes+$iUniverso;
	if ($iTotalAplicadas>0){
		$sTituloUniverso='<tr class="fondoazul">
<td colspan="9" align="center">Resueltas <b>'.formato_numero($iUniverso).' ('.formato_numero(($iUniverso*100)/$iTotalAplicadas, 2).' %)</b> - Pendientes <b>'.formato_numero($iPendientes).' ('.formato_numero(($iPendientes*100)/$iTotalAplicadas, 2).' %)</b> Total <b>'.formato_numero($iTotalAplicadas).'</b></td>
</tr>';
		}
	$res=$sErrConsulta.'<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">'.$sTituloUniverso.'
<tr class="fondoazul">
<td>&nbsp;</td>
<td><b>'.$ETI['even18tiporespuesta'].'</b></td>
<td><b>'.$ETI['even18orden'].'</b></td>
<td><b>'.$ETI['even18opcional'].'</b></td>
<td><b>'.$ETI['even18divergente'].'</b></td>
<td colspan="2"></td>
<td align="right">
'.html_paginador('paginaf1918', $registros, $lineastabla, $pagina, 'paginarf1918()').'
'.html_lpp('lppf1918', $lineastabla, 'paginarf1918()', $iTope).'
</td>
</tr>';
	$idPregunta='';
	$tlinea=1;
	while($filadet=$objdb->sf($tabladetalle)){
		
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
		$et_even18opcional='&nbsp;';
		if ($filadet['even18opcional']=='S'){$et_even18opcional=$ETI['si'];}
		$et_even18divergente='&nbsp;';
		if ($filadet['even18divergente']=='S'){$et_even18divergente=$ETI['si'];}
		$et_even18idpregcondiciona='';
		$et_even18valorcondiciona='';
		if ($filadet['even18idpregcondiciona']!=0){
			$et_even18idpregcondiciona=$filadet['even18idpregcondiciona'];
			$et_even18valorcondiciona=$filadet['even18valorcondiciona'];
			}
		if ($babierta){
			$sLink='<a href="javascript:cargaridf1918('."'".$filadet['even18id']."'".')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$res=$res.'<tr'.$sClass.'>
<td colspan="8">'.$filadet['even18consec'].' <b>'.cadena_notildes($filadet['even18pregunta']).'</b></td>
<tr'.$sClass.'>
<td></td>
<td>'.$sPrefijo.cadena_notildes($filadet['even20nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['even18orden'].$sSufijo.'</td>
<td>'.$sPrefijo.$et_even18opcional.$sSufijo.'</td>
<td>'.$sPrefijo.$et_even18divergente.$sSufijo.'</td>
<td>'.$sPrefijo.$et_even18idpregcondiciona.$sSufijo.'</td>
<td>'.$sPrefijo.$et_even18valorcondiciona.$sSufijo.'</td>
<td>'.$sLink.'</td>
</tr>';
		$sRespuestas='<table border="1" align="center" cellpadding="0" cellspacing="2" class="tablaapp">
<tr><td width="71%"></td><td width="2%"></td><td width="10%"></td><td width="2%"></td><td width="15%"></td></tr>';
		switch($filadet['even18tiporespuesta']){
			case 0:
			$iTotal0=0;
			$iTotal1=0;
			$iPorc0='';
			$iPorc1='';
			$sMuestra='';
			if ($iUniverso>0){
				$iTotal0=$filadet['even18rpta0'];
				$iTotal1=$filadet['even18rpta1'];
				if ($bHayFiltro){
					$iTotal0=0;
					$iTotal1=0;
					$sCampoRes='even31r'.$filadet['even18id'].'';
					$sql='SELECT TB.'.$sCampoRes.', COUNT(TB.even31id) AS Total
FROM even31total_'.$even16id.' AS TB, even21encuestaaplica AS T1 
WHERE TB.even31id=T1.even21id '.$sCondi2.' 
GROUP BY TB.'.$sCampoRes.'';
					$sMuestra=$sql;
					$resTotal=$objdb->ejecutasql($sql);
					while ($fila21=$objdb->sf($resTotal)){
						if ($fila21[$sCampoRes]==0){$iTotal0=$fila21['Total'];}
						if ($fila21[$sCampoRes]==1){$iTotal1=$fila21['Total'];}
						}
					}
				$iPorc0=round(($iTotal0*100)/$iUniverso,4).' %';
				$iPorc1=round(($iTotal1*100)/$iUniverso,4).' %';
				}
			$sRespuestas=$sRespuestas.'<tr><td>No</td><td></td><td align="right"><b>'.formato_numero($iTotal0).'</b></td><td></td><td align="right"><b>'.$iPorc0.'</b></td></tr>
<tr><td>Si</td><td></td><td align="right"><b>'.formato_numero($iTotal1).'</b></td><td></td><td align="right"><b>'.$iPorc1.'</b></td></tr>';
			break;
			case 1:
			case 2:
			$sql='SELECT even29consec, even29etiqueta FROM even29encpregresp WHERE even29idpregunta='.$filadet['even18id'].'';
			$tabla29=$objdb->ejecutasql($sql);
			while ($fila29=$objdb->sf($tabla29)){
				$iConseRespuesta=$fila29['even29consec'];
				$iPorc='';
				$iTotal=0;
				$sMuestra='';
				if ($iUniverso>0){
					$iTotal=$filadet['even18rpta'.$iConseRespuesta];
					if ($bHayFiltro){
						$iTotal=0;
						$sCampoRes='even31r'.$filadet['even18id'].'';
						$sql='SELECT TB.'.$sCampoRes.', COUNT(TB.even31id) AS Total
FROM even31total_'.$even16id.' AS TB, even21encuestaaplica AS T1 
WHERE TB.even31id=T1.even21id '.$sCondi2.' AND TB.'.$sCampoRes.'='.$iConseRespuesta.' 
GROUP BY TB.'.$sCampoRes.'';
						$resTotal=$objdb->ejecutasql($sql);
						$sMuestra=$sql;
						if ($objdb->nf($resTotal)>0){
							$fila21=$objdb->sf($resTotal);
							$iTotal=$fila21['Total'];
							}
						}
					$iPorc=round(($iTotal*100)/$iUniverso,4).' %';
					}
				$sRespuestas=$sRespuestas.'<tr><td>'.cadena_notildes($fila29['even29etiqueta']).'</td><td></td><td align="right"><b>'.formato_numero($iTotal).'</b></td><td></td><td align="right"><b>'.$iPorc.'</b></td></tr>';
				}
			break;
			default:
			$sRespuestas=$sRespuestas.'<tr><td colspan="5">{Respuesta abierta - No cuantificable.}</td></tr>';
			break;
			}
		$sRespuestas=$sRespuestas.'</table>';
		$res=$res.'<tr'.$sClass.'>
<td colspan="2"></td>
<td colspan="6">'.$sRespuestas.'</td>
</tr>';
		}
	$res=$res.'</table>';
	return array(utf8_encode($res), $sDebug);
	}
function f1918_HtmlTablaRes($params){
	$_SESSION['u_ultimominuto']=iminutoavance();
	$sError='';
	$bDebug=false;
	$sDebug='';
	require './app.php';
	$objdb=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objdb->dbPuerto=$APP->dbpuerto;}
	$objdb->xajax();
	list($sDetalle, $sDebugTabla)=f1918_TablaDetalleV2Res($params, $objdb, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f1918detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
?>