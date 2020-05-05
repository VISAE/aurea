<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2015 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.9.7 domingo, 29 de noviembre de 2015
--- 1921 even21encuestaaplica
*/
function f1921_TablaDetalle($params, $objdb){
	require 'app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_1921=$APP->rutacomun.'lg/lg_1921_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1921)){$mensajes_1921=$APP->rutacomun.'lg/lg_1921_es.php';}
	require $mensajes_todas;
	require $mensajes_1921;
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	if (isset($params[101])==0){$params[101]=1;}
	if (isset($params[102])==0){$params[102]=20;}
	//if (isset($params[103])==0){$params[103]='';}
	//$params[103]=numeros_validar($params[103]);
	$pagina=$params[101];
	$lineastabla=$params[102];
	$babierta=true;
	$sqladd='';
	//if ((int)$params[103]!=-1){$sqladd=$sqladd.' AND TB.campo='.$params[103];}
	//if ($params[103]!=''){$sqladd=$sqladd.' AND TB.campo2 LIKE "%'.$params[103].'%"';}
	$sTitulos='Encuesta, Tercero, Peraca, Curso, Bloquedo, Id, Fechapresenta, Terminada';
	$sql='SELECT TB.even21idencuesta, T2.unad11razonsocial AS C2_nombre, T3.exte02nombre, T4.unad40nombre, T5.unad63titulo, TB.even21id, TB.even21fechapresenta, TB.even21terminada, TB.even21idtercero, T2.unad11tipodoc AS C2_td, T2.unad11doc AS C2_doc, TB.even21idperaca, TB.even21idcurso, TB.even21idbloquedo 
FROM even21encuestaaplica AS TB, unad11terceros AS T2, exte02per_aca AS T3, unad40curso AS T4, unad63bloqueo AS T5 
WHERE TB.even21idencuesta='.$even21id.' AND TB.even21idtercero=T2.unad11id AND TB.even21idperaca=T3.exte02id AND TB.even21idcurso=T4.unad40id AND TB.even21idbloquedo=T5.unad63id '.$sqladd.'
';// ORDER BY TB.nombre';
	$sqllista=str_replace("'","|",$sql);
	$sqllista=str_replace('"',"|",$sqllista);
	$sErrConsulta='<input id="consulta_1921" name="consulta_1921" type="hidden" value="'.$sqllista.'"/>
<input id="titulos_1921" name="titulos_1921" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objdb->ejecutasql($sql);
	if ($tabladetalle==false){
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sql.' '.$objdb->serror.'"/>';
		}else{
		$registros=$objdb->nf($tabladetalle);
		if ($registros==0){
			//return utf8_encode($sErrConsulta.'<input id="paginaf1921" name="paginaf1921" type="hidden" value="'.$pagina.'"/><input id="lppf1921" name="lppf1921" type="hidden" value="'.$lineastabla.'"/>');
			//break;
			}
		if ((($registros-1)/$lineastabla)<($pagina-1)){$pagina=(int)(($registros-1)/$lineastabla)+1;}
		if ($registros>$lineastabla){
			$rbase=($pagina-1)*$lineastabla;
			$limite=' LIMIT '.$rbase.', '.$lineastabla;
			$tabladetalle=$objdb->ejecutasql($sql.$limite);
			}
		}
	$res=$sErrConsulta.'<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">
<tr class="fondoazul">
<td colspan="2"><b>'.$ETI['even21idtercero'].'</b></td>
<td><b>'.$ETI['even21idperaca'].'</b></td>
<td><b>'.$ETI['even21idcurso'].'</b></td>
<td><b>'.$ETI['even21idbloquedo'].'</b></td>
<td><b>'.$ETI['even21fechapresenta'].'</b></td>
<td><b>'.$ETI['even21terminada'].'</b></td>
<td align="right">
'.html_paginador('paginaf1921', $registros, $lineastabla, $pagina, 'paginarf1921()').'
'.html_lpp('lppf1921', $lineastabla, 'paginarf1921()').'
</td>
</tr>';
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
		$et_even21fechapresenta='';
		if ($filadet['even21fechapresenta']!='00/00/0000'){$et_even21fechapresenta=$filadet['even21fechapresenta'];}
		if ($babierta){
			$sLink='<a href="javascript:cargaridf1921('."'".$filadet['even21id']."'".')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$res=$res.'<tr'.$sClass.'>
<td>'.$sPrefijo.$filadet['C2_td'].' '.$filadet['C2_doc'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['C2_nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['exte02nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['unad40nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['unad63titulo']).$sSufijo.'</td>
<td>'.$sPrefijo.$et_even21fechapresenta.$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['even21terminada'].$sSufijo.'</td>
<td>'.$sLink.'</td>
</tr>';
		}
	$res=$res.'</table>';
	return utf8_encode($res);
	}
function TraerBusqueda_db_even21idcurso($sCodigo, $objdb){
	$sRespuesta='';
	$id=0;
	$sCodigo=trim($sCodigo);
	if ($sCodigo!=''){
		$sql='SELECT unad40id, unad40nombre, unad40id FROM unad40curso WHERE unad40id="'.$sCodigo.'"';
		$res=$objdb->ejecutasql($sql);
		if ($objdb->nf($res)!=0){
			$fila=$objdb->sf($res);
			$sRespuesta='<b>'.$fila['unad40id'].' '.$fila['unad40nombre'].'</b>';
			$id=$fila['unad40id'];
			}
		if ($sRespuesta==''){
			$sRespuesta='<span class="rojo">{'.$sCodigo.' No encontrado}</span>';
			}
		}
	return array($id, $sRespuesta);
	}
function TraerBusqueda_even21idcurso($params){
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	$_SESSION['u_ultimominuto']=iminutoavance();
	$respuesta='';
	$scodigo=$params[0];
	$bxajax=true;
	if (isset($params[3])!=0){if ($params[3]==1){$bxajax=false;}}
	$id=0;
	if ($scodigo!=''){
		require 'app.php';
		$objdb=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objdb->dbPuerto=$APP->dbpuerto;}
		$objdb->xajax();
		list($id, $respuesta)=TraerBusqueda_db_even21idcurso($scodigo, $objdb);
		}
	$objid=$params[1];
	$sdiv=$params[2];
	$objResponse=new xajaxResponse();
	$objResponse->assign($sdiv, 'innerHTML', $respuesta);
	$objResponse->assign($objid, 'value', $id);
	if ((int)$id>0){
		$objResponse->call('RevisaLlave');
		}
	return $objResponse;
	}
function f1921_HtmlTabla($params){
	$_SESSION['u_ultimominuto']=iminutoavance();
	$sError='';
	require 'app.php';
	$objdb=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objdb->dbPuerto=$APP->dbpuerto;}
	$objdb->xajax();
	$sDetalle=f1921_TablaDetalle($params, $objdb);
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f1921detalle', 'innerHTML', $sDetalle);
	return $objResponse;
	}
// -- Espacio para incluir funciones xajax personalizadas.
?>