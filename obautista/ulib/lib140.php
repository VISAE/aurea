<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2015 - 2020 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versi�n 2.9.1 mi�rcoles, 29 de julio de 2015
--- Modelo Versi�n 2.12.13 mi�rcoles, 22 de junio de 2016
--- Modelo Versi�n 2.17.0 lunes, 06 de marzo de 2017
--- Modelo Versi�n 2.22.2 martes, 24 de julio de 2018
--- Modelo Versi�n 2.23.2 viernes, 7 de junio de 2019
--- Modelo Versi�n 2.23.6 Tuesday, October 1, 2019
--- Modelo Versión 2.24.1 sábado, 1 de febrero de 2020
--- Modelo Versión 2.24.1 lunes, 24 de febrero de 2020
--- 140 unad40curso
*/
/** Archivo lib140.php.
* Libreria 140 unad40curso.
* @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
* @date jueves, 6 de junio de 2019
* @date Tuesday, October 1, 2019
*/
function f140_HTMLComboV2_unad40fuente($objDB, $objCombos, $valor){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('unad40fuente', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	//$objCombos->iAncho=450;
	$objCombos->sAccion='RevisaLlave();';
	$sSQL='SELECT  AS id,  AS nombre FROM ';
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f140_HTMLComboV2_bprograma($objDB, $objCombos, $valor, $vrbescuela){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	//@@ Se debe arreglar la condicion..
	$sCondi='core09idescuela="'.$vrbescuela.'"';
	if ($sCondi!=''){$sCondi=' WHERE '.$sCondi;}
	$objCombos->nuevo('bprograma', $valor, true, '{'.$ETI['msg_todos'].'}');
	$objCombos->addItem('0', '{Sin Programa}');
	$objCombos->sAccion='paginarf140()';
	//
	$sSQL='SELECT core09id AS id, CONCAT(core09nombre, " - ", core09codigo, CASE core09activo WHEN "S" THEN "" ELSE " [INACTIVO]" END) AS nombre FROM core09programa'.$sCondi.' ORDER BY core09activo DESC, core09nombre';
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f140_Combobprograma($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$objCombos=new clsHtmlCombos('n');
	$html_bprograma=f140_HTMLComboV2_bprograma($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_bprograma', 'innerHTML', $html_bprograma);
	$objResponse->call('paginarf140');
	$objResponse->call('$("#bprograma").chosen()');
	return $objResponse;
	}
function html_combo_unad48per_aca($objDB, $valor){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$res=html_combo('unad48per_aca', 'exte02id', 'exte02nombre', 'exte02per_aca', '', 'exte02nombre', $valor, $objDB, 'revisaf148()', true, '{'.$ETI['msg_seleccione'].'}', '');
	return $res;
	}
function Cargar_RubricasCurso($aParametros){
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$_SESSION['u_ultimominuto']=iminutoavance();
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$html_unad40idevaluacion=html_combo_unad40idevaluacion($objDB, '', $aParametros[0]);
	$html_unad40idrubricacertifica=html_combo_unad40idrubricacertifica($objDB, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_unad40idevaluacion', 'innerHTML', $html_unad40idevaluacion);
	$objResponse->assign('div_unad40idrubricacertifica', 'innerHTML', $html_unad40idrubricacertifica);
	return $objResponse;
	}
function html_combo_unad40idevaluacion($objDB, $valor, $vrunad40tipostandard){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	//@@ Se debe arreglar la condicion..
	$scondi='ofer24estandard="'.$vrunad40tipostandard.'"';
	$res=html_combo('unad40idevaluacion', 'ofer24id', 'ofer24titulo', 'ofer24evaluacion', $scondi, 'ofer24titulo', $valor, $objDB, '', true, '{'.$ETI['msg_seleccione'].'}', '');
	return utf8_encode($res);
	}
function html_combo_unad40idrubricacertifica($objDB, $valor, $vrunad40tipostandard){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	//@@ Se debe arreglar la condicion..
	$scondi='ofer24estandard="'.$vrunad40tipostandard.'"';
	$res=html_combo('unad40idrubricacertifica', 'ofer24id', 'ofer24titulo', 'ofer24evaluacion', $scondi, 'ofer24titulo', $valor, $objDB, '', true, '{'.$ETI['msg_seleccione'].'}', '');
	return utf8_encode($res);
	}
function f140_HTMLComboV2_unad40componenteconoce($objDB, $objCombos, $valor, $vrunad40areaconocimiento){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	//@@ Se debe arreglar la condicion..
	$sCondi='core15idareaconoce="'.$vrunad40areaconocimiento.'"';
	if ($sCondi!=''){$sCondi=' WHERE '.$sCondi;}
	$objCombos->nuevo('unad40componenteconoce', $valor, true, '{'.$ETI['msg_ninguno'].'}', 0);
	$res=$objCombos->html('SELECT core15id AS id, core15nombre AS nombre FROM core15componentes'.$sCondi, $objDB);
	return $res;
	}
function Cargar_unad40idevaluacion($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	//$objCombos=new clsHtmlCombos('n');
	$html_unad40idevaluacion=html_combo_unad40idevaluacion($objDB, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_unad40idevaluacion', 'innerHTML', $html_unad40idevaluacion);
	//$objResponse->call('$("#unad40idevaluacion").chosen()');
	return $objResponse;
	}
function Cargar_unad40idrubricacertifica($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$html_unad40idrubricacertifica=html_combo_unad40idrubricacertifica($objDB, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_unad40idrubricacertifica', 'innerHTML', $html_unad40idrubricacertifica);
	//$objResponse->call('$("#unad40idrubricacertifica").chosen()');
	return $objResponse;
	}
function f140_Combounad40componenteconoce($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$objCombos=new clsHtmlCombos('n');
	$html_unad40componenteconoce=f140_HTMLComboV2_unad40componenteconoce($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_unad40componenteconoce', 'innerHTML', $html_unad40componenteconoce);
	//$objResponse->call('$("#unad40componenteconoce").chosen()');
	return $objResponse;
	}
function f140_ExisteDato($datos){
	if(!is_array($datos)){$datos=json_decode(str_replace('\"','"',$datos),true);}
	$_SESSION['u_ultimominuto']=iminutoavance();
	$bHayLlave=true;
	$unad40fuente=numeros_validar($datos[1]);
	if ($unad40fuente==''){$bHayLlave=false;}
	$unad40consec=numeros_validar($datos[2]);
	if ($unad40consec==''){$bHayLlave=false;}
	if ($bHayLlave){
		require './app.php';
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		$sSQL='SELECT unad40consec FROM unad40curso WHERE unad40fuente='.$unad40fuente.' AND unad40consec='.$unad40consec.'';
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
function f140_Busquedas($aParametros){
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_140=$APP->rutacomun.'lg/lg_140_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_140)){$mensajes_140=$APP->rutacomun.'lg/lg_140_es.php';}
	require $mensajes_todas;
	require $mensajes_140;
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
	$sTitulo='<h2>'.$ETI['titulo_140'].' - '.$sTitulo.'</h2>';
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97titulo', 'innerHTML', $sTitulo);
	$objResponse->assign('div_97params', 'innerHTML', $sParams);
	$objResponse->assign('div_97tabla', 'innerHTML', $sTabla);
	$objResponse->setFunction('paginarbusqueda','',$sJavaBusqueda);
	$objResponse->call('expandesector(97)');
	return $objResponse;
	}
function f140_HtmlBusqueda($aParametros){
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
function f140_TablaDetalleV2($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_140=$APP->rutacomun.'lg/lg_140_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_140)){$mensajes_140=$APP->rutacomun.'lg/lg_140_es.php';}
	require $mensajes_todas;
	require $mensajes_140;
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	if (isset($aParametros[100])==0){$aParametros[100]=$_SESSION['unad_id_tercero'];}
	if (isset($aParametros[101])==0){$aParametros[101]=1;}
	if (isset($aParametros[102])==0){$aParametros[102]=20;}
	if (isset($aParametros[103])==0){$aParametros[103]='';}
	if (isset($aParametros[104])==0){$aParametros[104]='';}
	if (isset($aParametros[105])==0){$aParametros[105]='';}
	if (isset($aParametros[106])==0){$aParametros[106]='';}
	if (isset($aParametros[107])==0){$aParametros[107]='';}
	if (isset($aParametros[108])==0){$aParametros[108]='';}
	$idTercero=$aParametros[100];
	$sDebug='';
	$pagina=$aParametros[101];
	$lineastabla=$aParametros[102];
	$babierta=true;
	$sLeyenda='';
	if ($sLeyenda!=''){
		$sLeyenda='<div class="salto1px"></div>
<div class="GrupoCamposAyuda">
'.$sLeyenda.'
<div class="salto1px"></div>
</div>';
		return array($sLeyenda.'<input id="paginaf140" name="paginaf140" type="hidden" value="'.$pagina.'"/><input id="lppf140" name="lppf140" type="hidden" value="'.$lineastabla.'"/>', $sDebug);
		die();
		}
	$sSQLadd='';
	$sSQLadd1='';
	//if ((int)$aParametros[0]!=-1){$sSQLadd=$sSQLadd.' AND TB.campo='.$aParametros[0];}
	if ($aParametros[103]!=''){$sSQLadd1=$sSQLadd1.'TB.unad40consec LIKE "%'.$aParametros[103].'%" AND ';}
	if ($aParametros[104]!=''){$sSQLadd1=$sSQLadd1.'TB.unad40nombre LIKE "%'.$aParametros[104].'%" AND ';}
	switch ($aParametros[105]){
		case 1: //Sin Agenda
		$sSQLadd1=$sSQLadd1.'TB.unad40idagenda=0 AND ';
		break;
		case 2: //Sin estudiantes
		$sSQLadd1=$sSQLadd1.'TB.unad40numestudiantes=0 AND ';
		break;
		case 3: //Sin NAV
		$sSQLadd1=$sSQLadd1.'TB.unad40idnav=0 AND ';
		break;
		case 4: //Sin evaluacion
		$sSQLadd1=$sSQLadd1.'TB.unad40idevaluacion=0 AND ';
		break;
		case 5: //Sin rubrica acreditaci�n.
		$sSQLadd1=$sSQLadd1.'TB.unad40idrubricacertifica=0 AND ';
		break;
		case 6: //Con componente pr�ctico.
		$sSQLadd1=$sSQLadd1.'TB.unad40incluyelaboratorio="S" AND ';
		break;
		case 7: //Con salidas.
		$sSQLadd1=$sSQLadd1.'TB.unad40incluyesalida="S" AND ';
		break;
		case 8: //Por suficiencia
		$sSQLadd1=$sSQLadd1.'TB.unad40porsuficiencia="S" AND ';
		break;
		}
	if ($aParametros[106]!=''){
		$sIds='-99';
		$sSQL='SELECT TS.ofer08idcurso FROM ofer08oferta AS TS WHERE TS.ofer08idper_aca='.$aParametros[106].' AND TS.ofer08estadooferta=1';
		$tabla=$objDB->ejecutasql($sSQL);
		while($fila=$objDB->sf($tabla)){
			$sIds=$sIds.','.$fila['ofer08idcurso'];
			}
		$sSQLadd1='TB.unad40id IN ('.$sIds.') AND '.$sSQLadd1;
		}
	if ($aParametros[108]!=''){
		if ($aParametros[108]=='0'){
			$sSQLadd1=$sSQLadd1.'TB.unad40idescuela='.$aParametros[107].' AND ';
			}
		$sSQLadd1=$sSQLadd1.'TB.unad40idprograma='.$aParametros[108].' AND ';
		}else{
		if ($aParametros[107]!=''){$sSQLadd1=$sSQLadd1.'TB.unad40idescuela='.$aParametros[107].' AND ';}
		}
	$sTitulos='Codigo, Nombre, Tipo de curso, Standard, Num creditos, 
Programa, Incluye laboratorio, Incluye salida,Incluye Simulador,Homologable,
Habilitable,Por Suficiencia,Idioma, Codigo florida';
	//, TB.unad40diainical, TB.unad40numestudiantes
	//, TB.unad40idagenda, TB.unad40idnav, TB.unad40tipocurso, TB.unad40tipostandard, TB.unad40nivelformacion
	$sSQL='SELECT TB.unad40titulo, TB.unad40nombre, T8.unad41nombre, T9.unad42nombre, TB.unad40numcreditos, 
T5.core09nombre,TB.unad40incluyelaboratorio, TB.unad40incluyesalida, TB.unad40incluyesimulador, TB.unad40homologable, 
TB.unad40habilitable, TB.unad40porsuficiencia, TB.unad40idioma, TB.unad40codigoflorida, 
TB.unad40consec, TB.unad40id 
FROM (((unad40curso AS TB LEFT JOIN unad41tipocurso AS T8 ON (TB.unad40tipocurso=T8.unad41id))) LEFT JOIN unad42tipostandard AS T9 ON (TB.unad40tipostandard=T9.unad42id)) LEFT JOIN core09programa AS T5 ON (TB.unad40idprograma=T5.core09id) 
WHERE '.$sSQLadd1.' TB.unad40id>0 '.$sSQLadd.' 
ORDER BY TB.unad40nombre';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_140" name="consulta_140" type="hidden" value="'.$sSQLlista.'"/>
<input id="titulos_140" name="titulos_140" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objDB->ejecutasql($sSQL);
	if ($tabladetalle==false){
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 140: '.$sSQL.'<br>';}
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		//$sLeyenda=$sSQL;
		}else{
		$registros=$objDB->nf($tabladetalle);
		if ($registros==0){
			//return array(utf8_encode($sErrConsulta.'<input id="paginaf140" name="paginaf140" type="hidden" value="'.$pagina.'"/><input id="lppf140" name="lppf140" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
<td><b>'.$ETI['unad40titulo'].'</b></td>
<td><b>'.$ETI['unad40nombre'].'</b></td>
<td><b>'.$ETI['unad40numcreditos'].'</b></td>
<td><b>'.$ETI['unad40tipocurso'].'</b></td>
<td><b>'.$ETI['unad40tipostandard'].'</b></td>
<td><b>'.$ETI['unad40idprograma'].'</b></td>
<td align="right">
'.html_paginador('paginaf140', $registros, $lineastabla, $pagina, 'paginarf140()').'
'.html_lpp('lppf140', $lineastabla, 'paginarf140()').'
</td>
</tr>';
	$tlinea=1;
	while($filadet=$objDB->sf($tabladetalle)){
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
		if ($babierta){
			$sLink='<a href="javascript:cargaridf140('.$filadet['unad40id'].')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$res=$res.'<tr'.$sClass.'>
<td>'.$sPrefijo.$filadet['unad40titulo'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['unad40nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['unad40numcreditos'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['unad41nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['unad42nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['core09nombre']).$sSufijo.'</td>
<td>'.$sLink.'</td>
</tr>';
		}
	$res=$res.'</table>';
	$objDB->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
	}
function f140_HtmlTabla($aParametros){
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
	list($sDetalle, $sDebugTabla)=f140_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f140detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f140_db_CargarPadre($DATA, $objDB, $bDebug=false){
	$sError='';
	$iTipoError=0;
	$sDebug='';
	require './app.php';
	if ($DATA['paso']==1){
		$sSQLcondi='unad40fuente='.$DATA['unad40fuente'].' AND unad40consec='.$DATA['unad40consec'].'';
		}else{
		$sSQLcondi='unad40id='.$DATA['unad40id'].'';
		}
	$sSQL='SELECT * FROM unad40curso WHERE '.$sSQLcondi;
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$DATA['unad40fuente']=$fila['unad40fuente'];
		$DATA['unad40consec']=$fila['unad40consec'];
		$DATA['unad40id']=$fila['unad40id'];
		$DATA['unad40titulo']=$fila['unad40titulo'];
		$DATA['unad40nombre']=$fila['unad40nombre'];
		$DATA['unad40idagenda']=$fila['unad40idagenda'];
		$DATA['unad40diainical']=$fila['unad40diainical'];
		$DATA['unad40numestudiantes']=$fila['unad40numestudiantes'];
		$DATA['unad40idnav']=$fila['unad40idnav'];
		$DATA['unad40tipocurso']=$fila['unad40tipocurso'];
		$DATA['unad40tipostandard']=$fila['unad40tipostandard'];
		$DATA['unad40nivelformacion']=$fila['unad40nivelformacion'];
		$DATA['unad40numcreditos']=$fila['unad40numcreditos'];
		$DATA['unad40idevaluacion']=$fila['unad40idevaluacion'];
		$DATA['unad40idcursoncontents']=$fila['unad40idcursoncontents'];
		$DATA['unad40idescuela']=$fila['unad40idescuela'];
		$DATA['unad40idprograma']=$fila['unad40idprograma'];
		$DATA['unad40numestaula1']=$fila['unad40numestaula1'];
		$DATA['unad40idrubricacertifica']=$fila['unad40idrubricacertifica'];
		$DATA['unad40incluyelaboratorio']=$fila['unad40incluyelaboratorio'];
		$DATA['unad40codigoflorida']=$fila['unad40codigoflorida'];
		$DATA['unad40incluyesalida']=$fila['unad40incluyesalida'];
		$DATA['unad40idtablero']=$fila['unad40idtablero'];
		$DATA['unad40peracaultacredita']=$fila['unad40peracaultacredita'];
		$DATA['unad40areaconocimiento']=$fila['unad40areaconocimiento'];
		$DATA['unad40componenteconoce']=$fila['unad40componenteconoce'];
		$DATA['unad40unidadprod']=$fila['unad40unidadprod'];
		$DATA['unad40ofertaperacacorto']=$fila['unad40ofertaperacacorto'];
		$DATA['unad40homologable']=$fila['unad40homologable'];
		$DATA['unad40habilitable']=$fila['unad40habilitable'];
		$DATA['unad40porsuficiencia']=$fila['unad40porsuficiencia'];
		$DATA['unad40modocalifica']=$fila['unad40modocalifica'];
		$DATA['unad40incluyesimulador']=$fila['unad40incluyesimulador'];
		$DATA['unad40idioma']=$fila['unad40idioma'];
		$bcargo=true;
		$DATA['paso']=2;
		$DATA['boculta140']=0;
		$bLimpiaHijos=true;
		}else{
		$DATA['paso']=0;
		}
	return array($DATA, $sError, $iTipoError, $sDebug);
	}
function f140_db_GuardarV2($DATA, $objDB, $bDebug=false){
	$iCodModulo=140;
	$bAudita[2]=true;
	$bAudita[3]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_140=$APP->rutacomun.'lg/lg_140_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_140)){$mensajes_140=$APP->rutacomun.'lg/lg_140_es.php';}
	require $mensajes_todas;
	require $mensajes_140;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	// -- Se inicia validando todas las posibles entradas de usuario.
	/*
	if (isset($DATA['unad40fuente'])==0){$DATA['unad40fuente']='';}
	if (isset($DATA['unad40consec'])==0){$DATA['unad40consec']='';}
	if (isset($DATA['unad40id'])==0){$DATA['unad40id']='';}
	if (isset($DATA['unad40nombre'])==0){$DATA['unad40nombre']='';}
	if (isset($DATA['unad40idagenda'])==0){$DATA['unad40idagenda']='';}
	if (isset($DATA['unad40diainical'])==0){$DATA['unad40diainical']='';}
	if (isset($DATA['unad40numestudiantes'])==0){$DATA['unad40numestudiantes']='';}
	if (isset($DATA['unad40idnav'])==0){$DATA['unad40idnav']='';}
	if (isset($DATA['unad40tipocurso'])==0){$DATA['unad40tipocurso']='';}
	if (isset($DATA['unad40tipostandard'])==0){$DATA['unad40tipostandard']='';}
	if (isset($DATA['unad40nivelformacion'])==0){$DATA['unad40nivelformacion']='';}
	if (isset($DATA['unad40numcreditos'])==0){$DATA['unad40numcreditos']='';}
	if (isset($DATA['unad40idevaluacion'])==0){$DATA['unad40idevaluacion']='';}
	if (isset($DATA['unad40idcursoncontents'])==0){$DATA['unad40idcursoncontents']='';}
	if (isset($DATA['unad40idescuela'])==0){$DATA['unad40idescuela']='';}
	if (isset($DATA['unad40idprograma'])==0){$DATA['unad40idprograma']='';}
	if (isset($DATA['unad40numestaula1'])==0){$DATA['unad40numestaula1']='';}
	if (isset($DATA['unad40idrubricacertifica'])==0){$DATA['unad40idrubricacertifica']='';}
	if (isset($DATA['unad40incluyelaboratorio'])==0){$DATA['unad40incluyelaboratorio']='';}
	if (isset($DATA['unad40codigoflorida'])==0){$DATA['unad40codigoflorida']='';}
	if (isset($DATA['unad40incluyesalida'])==0){$DATA['unad40incluyesalida']='';}
	if (isset($DATA['unad40idtablero'])==0){$DATA['unad40idtablero']='';}
	if (isset($DATA['unad40areaconocimiento'])==0){$DATA['unad40areaconocimiento']='';}
	if (isset($DATA['unad40componenteconoce'])==0){$DATA['unad40componenteconoce']='';}
	if (isset($DATA['unad40unidadprod'])==0){$DATA['unad40unidadprod']='';}
	if (isset($DATA['unad40ofertaperacacorto'])==0){$DATA['unad40ofertaperacacorto']='';}
	if (isset($DATA['unad40homologable'])==0){$DATA['unad40homologable']='';}
	if (isset($DATA['unad40habilitable'])==0){$DATA['unad40habilitable']='';}
	if (isset($DATA['unad40porsuficiencia'])==0){$DATA['unad40porsuficiencia']='';}
	if (isset($DATA['unad40modocalifica'])==0){$DATA['unad40modocalifica']=0;}
	if (isset($DATA['unad40incluyesimulador'])==0){$DATA['unad40incluyesimulador']='';}
	if (isset($DATA['unad40idioma'])==0){$DATA['unad40idioma']='es';}
	*/
	$DATA['unad40fuente']=numeros_validar($DATA['unad40fuente']);
	$DATA['unad40consec']=numeros_validar($DATA['unad40consec']);
	$DATA['unad40titulo']=htmlspecialchars(trim($DATA['unad40titulo']));
	$DATA['unad40nombre']=htmlspecialchars(trim($DATA['unad40nombre']));
	$DATA['unad40idagenda']=numeros_validar($DATA['unad40idagenda']);
	$DATA['unad40diainical']=numeros_validar($DATA['unad40diainical']);
	$DATA['unad40numestudiantes']=numeros_validar($DATA['unad40numestudiantes']);
	$DATA['unad40idnav']=numeros_validar($DATA['unad40idnav']);
	$DATA['unad40tipocurso']=numeros_validar($DATA['unad40tipocurso']);
	$DATA['unad40tipostandard']=numeros_validar($DATA['unad40tipostandard']);
	$DATA['unad40nivelformacion']=numeros_validar($DATA['unad40nivelformacion']);
	$DATA['unad40numcreditos']=numeros_validar($DATA['unad40numcreditos']);
	$DATA['unad40idevaluacion']=numeros_validar($DATA['unad40idevaluacion']);
	$DATA['unad40idcursoncontents']=numeros_validar($DATA['unad40idcursoncontents']);
	$DATA['unad40idescuela']=numeros_validar($DATA['unad40idescuela']);
	$DATA['unad40idprograma']=numeros_validar($DATA['unad40idprograma']);
	$DATA['unad40numestaula1']=numeros_validar($DATA['unad40numestaula1']);
	$DATA['unad40idrubricacertifica']=numeros_validar($DATA['unad40idrubricacertifica']);
	$DATA['unad40incluyelaboratorio']=htmlspecialchars(trim($DATA['unad40incluyelaboratorio']));
	$DATA['unad40codigoflorida']=htmlspecialchars(trim($DATA['unad40codigoflorida']));
	$DATA['unad40incluyesalida']=htmlspecialchars(trim($DATA['unad40incluyesalida']));
	$DATA['unad40idtablero']=numeros_validar($DATA['unad40idtablero']);
	$DATA['unad40areaconocimiento']=numeros_validar($DATA['unad40areaconocimiento']);
	$DATA['unad40componenteconoce']=numeros_validar($DATA['unad40componenteconoce']);
	$DATA['unad40unidadprod']=numeros_validar($DATA['unad40unidadprod']);
	$DATA['unad40ofertaperacacorto']=htmlspecialchars(trim($DATA['unad40ofertaperacacorto']));
	$DATA['unad40homologable']=htmlspecialchars(trim($DATA['unad40homologable']));
	$DATA['unad40habilitable']=htmlspecialchars(trim($DATA['unad40habilitable']));
	$DATA['unad40porsuficiencia']=htmlspecialchars(trim($DATA['unad40porsuficiencia']));
	$DATA['unad40modocalifica']=numeros_validar($DATA['unad40modocalifica']);
	$DATA['unad40incluyesimulador']=htmlspecialchars(trim($DATA['unad40incluyesimulador']));
	$DATA['unad40idioma']=htmlspecialchars(trim($DATA['unad40idioma']));
	// -- Se inicializan las variables que puedan pasar vacias {Especialmente n�meros}.
	if ($DATA['unad40idagenda']==''){$DATA['unad40idagenda']=0;}
	if ($DATA['unad40diainical']==''){$DATA['unad40diainical']=0;}
	if ($DATA['unad40numestudiantes']==''){$DATA['unad40numestudiantes']=0;}
	if ($DATA['unad40idnav']==''){$DATA['unad40idnav']=0;}
	if ($DATA['unad40tipocurso']==''){$DATA['unad40tipocurso']=0;}
	if ($DATA['unad40tipostandard']==''){$DATA['unad40tipostandard']=0;}
	if ($DATA['unad40nivelformacion']==''){$DATA['unad40nivelformacion']=0;}
	if ($DATA['unad40numcreditos']==''){$DATA['unad40numcreditos']=0;}
	if ($DATA['unad40idevaluacion']==''){$DATA['unad40idevaluacion']=0;}
	if ($DATA['unad40idcursoncontents']==''){$DATA['unad40idcursoncontents']=0;}
	if ($DATA['unad40idescuela']==''){$DATA['unad40idescuela']=0;}
	if ($DATA['unad40idprograma']==''){$DATA['unad40idprograma']=0;}
	if ($DATA['unad40numestaula1']==''){$DATA['unad40numestaula1']=0;}
	if ($DATA['unad40idrubricacertifica']==''){$DATA['unad40idrubricacertifica']=0;}
	if ($DATA['unad40idtablero']==''){$DATA['unad40idtablero']=0;}
	if ($DATA['unad40peracaultacredita']==''){$DATA['unad40peracaultacredita']=0;}
	if ($DATA['unad40areaconocimiento']==''){$DATA['unad40areaconocimiento']=0;}
	if ($DATA['unad40componenteconoce']==''){$DATA['unad40componenteconoce']=0;}
	if ($DATA['unad40unidadprod']==''){$DATA['unad40unidadprod']=0;}
	if ($DATA['unad40modocalifica']==''){$DATA['unad40modocalifica']=0;}
	// -- Seccion para validar los posibles causales de error.
	$sSepara=', ';
	if (true){
		if ($DATA['unad40idioma']==''){$sError=$ERR['unad40idioma'].$sSepara.$sError;}
		//if ($DATA['unad40incluyesimulador']==''){$sError=$ERR['unad40incluyesimulador'].$sSepara.$sError;}
		//if ($DATA['unad40modocalifica']==''){$sError=$ERR['unad40modocalifica'].$sSepara.$sError;}
		if ($DATA['unad40porsuficiencia']==''){$sError=$ERR['unad40porsuficiencia'].$sSepara.$sError;}
		if ($DATA['unad40habilitable']==''){$sError=$ERR['unad40habilitable'].$sSepara.$sError;}
		if ($DATA['unad40homologable']==''){$sError=$ERR['unad40homologable'].$sSepara.$sError;}
		if ($DATA['unad40ofertaperacacorto']==''){$sError=$ERR['unad40ofertaperacacorto'].$sSepara.$sError;}
		if ($DATA['unad40unidadprod']==''){$sError=$ERR['unad40unidadprod'].$sSepara.$sError;}
		if ($DATA['unad40incluyesalida']==''){$sError=$ERR['unad40incluyesalida'];}
		//if ($DATA['unad40codigoflorida']==''){$sError=$ERR['unad40codigoflorida'];}
		if ($DATA['unad40incluyelaboratorio']==''){$sError=$ERR['unad40incluyelaboratorio'];}
		if ($DATA['unad40fuente']==0){
			}else{
			if ($DATA['unad40titulo']==''){$sError=$ERR['unad40titulo'];}
			}
		
		/*
		if ($DATA['unad40idrubricacertifica']==''){$sError=$ERR['unad40idrubricacertifica'];}
		if ($DATA['unad40numestaula1']==''){$sError=$ERR['unad40numestaula1'];}
		if ($DATA['unad40idprograma']==''){$sError=$ERR['unad40idprograma'];}
		if ($DATA['unad40idescuela']==''){$sError=$ERR['unad40idescuela'];}
		if ($DATA['unad40idcursoncontents']==''){$sError=$ERR['unad40idcursoncontents'];}
		if ($DATA['unad40idevaluacion']==''){$sError=$ERR['unad40idevaluacion'];}
		if ($DATA['unad40numcreditos']==''){$sError=$ERR['unad40numcreditos'];}
		if ($DATA['unad40nivelformacion']==''){$sError=$ERR['unad40nivelformacion'];}
		if ($DATA['unad40tipostandard']==''){$sError=$ERR['unad40tipostandard'];}
		if ($DATA['unad40tipocurso']==''){$sError=$ERR['unad40tipocurso'];}
		if ($DATA['unad40idnav']==''){$sError=$ERR['unad40idnav'];}
		if ($DATA['unad40numestudiantes']==''){$sError=$ERR['unad40numestudiantes'];}
		if ($DATA['unad40diainical']==''){$sError=$ERR['unad40diainical'];}
		if ($DATA['unad40idagenda']==''){$sError=$ERR['unad40idagenda'];}
		*/
		//Fin de las valiaciones NO LLAVE.
		}
	//Valiaciones de campos obligatorios en todo guardar.
	if ($DATA['unad40nombre']==''){$sError=$ERR['unad40nombre'];}
	//if ($DATA['unad40consec']==''){$sError=$ERR['unad40consec'];}
	// -- Se verifican los valores de campos de otras tablas.
	$bQuitarCodigo=false;
	if ($sError==''){
		if ($DATA['paso']==10){
			if ($DATA['unad40consec']==''){
				$DATA['unad40consec']=tabla_consecutivo('unad40curso', 'unad40consec', 'unad40fuente='.$DATA['unad40fuente'].'', $objDB);
				if ($DATA['unad40consec']==-1){$sError=$objDB->serror;}
				$bQuitarCodigo=true;
				}else{
				}
			if ($sError==''){
				$sSQL='SELECT 1 FROM unad40curso WHERE unad40fuente='.$DATA['unad40fuente'].' AND unad40consec='.$DATA['unad40consec'].'';
				$result=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($result)!=0){
					$sError=$ERR['existe'];
					}else{
					if (!seg_revisa_permiso($iCodModulo, 2, $objDB)){$sError=$ERR['2'];}
					}
				}
			}else{
			if (!seg_revisa_permiso($iCodModulo, 3, $objDB)){$sError=$ERR['3'];}
			}
		}
	if ($sError==''){
		//El titulo se debe validar como una llave primaria...
		if ($DATA['unad40fuente']==0){
			if ($DATA['unad40titulo']==''){$ERR['unad40titulo']=$DATA['unad40consec'];}
			}
		if ($DATA['paso']==10){
			$sSQL='SELECT 1 FROM unad40curso WHERE unad40titulo="'.trim($DATA['unad40titulo']).'"';
			}else{
			$sSQL='SELECT 1 FROM unad40curso WHERE unad40titulo="'.trim($DATA['unad40titulo']).'" AND unad40id<>'.$DATA['unad40id'].'';
			}
		$result=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($result)!=0){
			$sError=$ERR['unad40titulo_existe'];
			}
		}
	if ($sError==''){
		if ($DATA['paso']==10){
			//Preparar el Id, Si no lo hay se quita la comprobaci�n.
			if ($DATA['unad40fuente']==0){
				$DATA['unad40id']=$DATA['unad40consec'];
				}else{
				$DATA['unad40id']=tabla_consecutivo('unad40curso','unad40id', '', $objDB);
				if ($DATA['unad40id']==-1){$sError=$objDB->serror;}
				}
			}
		}
	if ($sError==''){
		$bpasa=false;
		if ($DATA['paso']==10){
			$DATA['unad40peracaultacredita']=0;
			$sCampos140='unad40fuente, unad40consec, unad40id, unad40titulo, unad40nombre, unad40idagenda, unad40diainical, unad40numestudiantes, unad40idnav, unad40tipocurso, 
unad40tipostandard, unad40nivelformacion, unad40numcreditos, unad40idevaluacion, unad40idcursoncontents, unad40idescuela, unad40idprograma, unad40numestaula1, unad40idrubricacertifica, unad40incluyelaboratorio, 
unad40codigoflorida, unad40incluyesalida, unad40idtablero, unad40peracaultacredita, unad40areaconocimiento, unad40componenteconoce, unad40unidadprod, unad40ofertaperacacorto, unad40homologable, unad40habilitable, 
unad40porsuficiencia, unad40modocalifica, unad40incluyesimulador, unad40idioma';
			$sValores140=''.$DATA['unad40fuente'].', '.$DATA['unad40consec'].', '.$DATA['unad40id'].', "'.$DATA['unad40titulo'].'", "'.$DATA['unad40nombre'].'", '.$DATA['unad40idagenda'].', '.$DATA['unad40diainical'].', '.$DATA['unad40numestudiantes'].', '.$DATA['unad40idnav'].', '.$DATA['unad40tipocurso'].', 
'.$DATA['unad40tipostandard'].', '.$DATA['unad40nivelformacion'].', '.$DATA['unad40numcreditos'].', '.$DATA['unad40idevaluacion'].', '.$DATA['unad40idcursoncontents'].', '.$DATA['unad40idescuela'].', '.$DATA['unad40idprograma'].', '.$DATA['unad40numestaula1'].', '.$DATA['unad40idrubricacertifica'].', "'.$DATA['unad40incluyelaboratorio'].'", 
"'.$DATA['unad40codigoflorida'].'", "'.$DATA['unad40incluyesalida'].'", '.$DATA['unad40idtablero'].', '.$DATA['unad40peracaultacredita'].', '.$DATA['unad40areaconocimiento'].', '.$DATA['unad40componenteconoce'].', '.$DATA['unad40unidadprod'].', "'.$DATA['unad40ofertaperacacorto'].'", "'.$DATA['unad40homologable'].'", "'.$DATA['unad40habilitable'].'", 
"'.$DATA['unad40porsuficiencia'].'", '.$DATA['unad40modocalifica'].', "'.$DATA['unad40incluyesimulador'].'", "'.$DATA['unad40idioma'].'"';
			if ($APP->utf8==1){
				$sSQL='INSERT INTO unad40curso ('.$sCampos140.') VALUES ('.utf8_encode($sValores140).');';
				$sdetalle=$sCampos140.'['.utf8_encode($sValores140).']';
				}else{
				$sSQL='INSERT INTO unad40curso ('.$sCampos140.') VALUES ('.$sValores140.');';
				$sdetalle=$sCampos140.'['.$sValores140.']';
				}
			$idAccion=2;
			$bpasa=true;
			}else{
			$scampo[1]='unad40nombre';
			$scampo[2]='unad40idagenda';
			$scampo[3]='unad40diainical';
			$scampo[4]='unad40numestudiantes';
			$scampo[5]='unad40idnav';
			$scampo[6]='unad40tipocurso';
			$scampo[7]='unad40tipostandard';
			$scampo[8]='unad40nivelformacion';
			$scampo[9]='unad40numcreditos';
			$scampo[10]='unad40idevaluacion';
			$scampo[11]='unad40idcursoncontents';
			$scampo[12]='unad40idescuela';
			$scampo[13]='unad40idprograma';
			$scampo[14]='unad40numestaula1';
			$scampo[15]='unad40idrubricacertifica';
			$scampo[16]='unad40incluyelaboratorio';
			$scampo[17]='unad40codigoflorida';
			$scampo[18]='unad40incluyesalida';
			$scampo[19]='unad40idtablero';
			$scampo[20]='unad40areaconocimiento';
			$scampo[21]='unad40componenteconoce';
			$scampo[22]='unad40unidadprod';
			$scampo[23]='unad40ofertaperacacorto';
			$scampo[24]='unad40homologable';
			$scampo[25]='unad40habilitable';
			$scampo[26]='unad40porsuficiencia';
			$scampo[27]='unad40modocalifica';
			$scampo[28]='unad40incluyesimulador';
			$scampo[29]='unad40idioma';
			$scampo[30]='unad40titulo';
			$sdato[1]=$DATA['unad40nombre'];
			$sdato[2]=$DATA['unad40idagenda'];
			$sdato[3]=$DATA['unad40diainical'];
			$sdato[4]=$DATA['unad40numestudiantes'];
			$sdato[5]=$DATA['unad40idnav'];
			$sdato[6]=$DATA['unad40tipocurso'];
			$sdato[7]=$DATA['unad40tipostandard'];
			$sdato[8]=$DATA['unad40nivelformacion'];
			$sdato[9]=$DATA['unad40numcreditos'];
			$sdato[10]=$DATA['unad40idevaluacion'];
			$sdato[11]=$DATA['unad40idcursoncontents'];
			$sdato[12]=$DATA['unad40idescuela'];
			$sdato[13]=$DATA['unad40idprograma'];
			$sdato[14]=$DATA['unad40numestaula1'];
			$sdato[15]=$DATA['unad40idrubricacertifica'];
			$sdato[16]=$DATA['unad40incluyelaboratorio'];
			$sdato[17]=$DATA['unad40codigoflorida'];
			$sdato[18]=$DATA['unad40incluyesalida'];
			$sdato[19]=$DATA['unad40idtablero'];
			$sdato[20]=$DATA['unad40areaconocimiento'];
			$sdato[21]=$DATA['unad40componenteconoce'];
			$sdato[22]=$DATA['unad40unidadprod'];
			$sdato[23]=$DATA['unad40ofertaperacacorto'];
			$sdato[24]=$DATA['unad40homologable'];
			$sdato[25]=$DATA['unad40habilitable'];
			$sdato[26]=$DATA['unad40porsuficiencia'];
			$sdato[27]=$DATA['unad40modocalifica'];
			$sdato[28]=$DATA['unad40incluyesimulador'];
			$sdato[29]=$DATA['unad40idioma'];
			$sdato[30]=trim($DATA['unad40titulo']);
			$numcmod=30;
			$sWhere='unad40id='.$DATA['unad40id'].'';
			$sSQL='SELECT * FROM unad40curso WHERE '.$sWhere;
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
					$sSQL='UPDATE unad40curso SET '.utf8_encode($sdatos).' WHERE '.$sWhere.';';
					}else{
					$sdetalle=$sdatos.'['.$sWhere.']';
					$sSQL='UPDATE unad40curso SET '.$sdatos.' WHERE '.$sWhere.';';
					}
				$idAccion=3;
				}
			}
		if ($bpasa){
			$result=$objDB->ejecutasql($sSQL);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' [140] ..<!-- '.$sSQL.' -->';
				if ($idAccion==2){
					$DATA['unad40id']='';
					$bQuitarCodigo=true;
					$DATA['paso']=0;
					}else{
					$DATA['paso']=2;
					}
				}else{
				if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Guardar 140 '.$sSQL.'<br>';}
				if ($bAudita[$idAccion]){seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], $idAccion, $DATA['unad40id'], $sdetalle, $objDB);}
				$DATA['paso']=2;
				}
			}else{
			$DATA['paso']=2;
			}
		}else{
		$DATA['paso']=$DATA['paso']-10;
		if ($bQuitarCodigo){
			$DATA['unad40consec']='';
			}
		}
	//if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' InfoDepura<br>';}
	return array($DATA, $sError, $iTipoError, $sDebug);
	}
function f140_db_Eliminar($unad40id, $objDB, $bDebug=false){
	$iCodModulo=140;
	$bAudita[4]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_140='lg/lg_140_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_140)){$mensajes_140='lg/lg_140_es.php';}
	require $mensajes_todas;
	require $mensajes_140;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	$unad40id=numeros_validar($unad40id);
	// Traer los datos para hacer las validaciones.
	if ($sError==''){
		$sSQL='SELECT * FROM unad40curso WHERE unad40id='.$unad40id.'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$filabase=$objDB->sf($tabla);
			}else{
			$sError='No se encuentra el registro solicitado {Ref: '.$unad40id.'}';
			}
		}
	if ($sError==''){
		$sSQL='SELECT unad48idcurso FROM unad48cursoaula WHERE unad48idcurso='.$filabase['unad40id'].'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$sError='Existen Aulas adicionales creados, no es posible eliminar';
			}
		}
	if ($sError==''){
		$sSQL='SELECT ofer43idcurso FROM ofer43cursoforo WHERE ofer43idcurso='.$filabase['unad40id'].'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$sError='Existen Foro creados, no es posible eliminar';
			}
		}
	if ($sError==''){
		$sSQL='SELECT ofer44idcurso FROM ofer44cursoanexo WHERE ofer44idcurso='.$filabase['unad40id'].'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$sError='Existen Anexos creados, no es posible eliminar';
			}
		}
	if ($sError==''){
		if (!seg_revisa_permiso($iCodModulo, 4, $objDB)){
			$sError=$ERR['4'];
			}
		}
	if ($sError==''){
		$sSQL='SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=140';
		$tablaor=$objDB->ejecutasql($sSQL);
		while ($filaor=$objDB->sf($tablaor)){
			$sSQL='SELECT '.$filaor['campoid'].' FROM '.$filaor['tabla'].' WHERE '.$filaor['camporev'].'='.$_REQUEST['unad40id'].' LIMIT 0, 1';
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
		//$sSQL='DELETE FROM unad48cursoaula WHERE unad48idcurso='.$filabase['unad40id'].'';
		//$tabla=$objDB->ejecutasql($sSQL);
		//$sSQL='DELETE FROM ofer43cursoforo WHERE ofer43idcurso='.$filabase['unad40id'].'';
		//$tabla=$objDB->ejecutasql($sSQL);
		//$sSQL='DELETE FROM ofer44cursoanexo WHERE ofer44idcurso='.$filabase['unad40id'].'';
		//$tabla=$objDB->ejecutasql($sSQL);
		$sWhere='unad40id='.$unad40id.'';
		//$sWhere='unad40consec='.$filabase['unad40consec'].' AND unad40fuente='.$filabase['unad40fuente'].'';
		$sSQL='DELETE FROM unad40curso WHERE '.$sWhere.';';
		$result=$objDB->ejecutasql($sSQL);
		if ($result==false){
			$sError=$ERR['falla_eliminar'].' .. <!-- '.$sSQL.' -->';
			}else{
			if ($bAudita[4]){seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $unad40id, $sWhere, $objDB);}
			}
		}
	return array($sError, $iTipoError, $sDebug);
	}
function f140_TituloBusqueda(){
	return 'Busqueda de Cursos';
	}
function f140_ParametrosBusqueda(){
	$sParams='<label class="Label90">Nombre</label><label><input id="b140nombre" name="b140nombre" type="text" value="" onchange="paginarbusqueda()" /></label>';
	return $sParams;
	}
function f140_JavaScriptBusqueda($iModuloBusca){
	$sRes='var sCampo=window.document.frmedita.scampobusca.value;
var params=new Array();
params[100]=sCampo;
params[101]=window.document.frmedita.paginabusqueda.value;
params[102]=window.document.frmedita.lppfbusqueda.value;
params[103]=window.document.frmedita.b140nombre.value;
xajax_f'.$iModuloBusca.'_HtmlBusqueda(params);';
	return $sRes;
	}
function f140_TablaDetalleBusquedas($aParametros, $objDB){
	$res='';
	return utf8_encode($res);
	}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------
?>