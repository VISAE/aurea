<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2018 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versi�n 2.21.0 martes, 17 de abril de 2018
--- Modelo Versión 2.21.0 lunes, 25 de junio de 2018
--- Auxiliar para tablero de unad11terceros
*/
function f17_HTMLComboV2_unad11idcead($objdb, $objCombos, $valor, $vrunad11idzona){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	//@@ Se debe arreglar la condicion..
	$sCondi='unad24idzona="'.$vrunad11idzona.'"';
	if ($sCondi!=''){$sCondi=' WHERE '.$sCondi;}
	$objCombos->nuevo('unad11idcead', $valor, true, '{'.$ETI['msg_ninguno'].'}', 0);
	$res=$objCombos->html('SELECT unad24id AS id, unad24nombre AS nombre FROM unad24sede'.$sCondi, $objdb);
	return $res;
	}
function f17_HTMLComboV2_unad11idprograma($objdb, $objCombos, $valor, $vrunad11idescuela){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	//@@ Se debe arreglar la condicion..
	$sCondi='exte03idescuela="'.$vrunad11idescuela.'"';
	if ($sCondi!=''){$sCondi=' WHERE '.$sCondi;}
	$objCombos->nuevo('unad11idprograma', $valor, true, '{'.$ETI['msg_ninguno'].'}', 0);
	$res=$objCombos->html('SELECT exte03id AS id, exte03nombre AS nombre FROM exte03programa'.$sCondi, $objdb);
	return $res;
	}
function f17_HTMLComboV2_unad11deptodoc($objdb, $objCombos, $valor, $vrunad11pais){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	//@@ Se debe arreglar la condicion..
	$sCondi='unad19codpais="'.$vrunad11pais.'"';
	if ($sCondi!=''){$sCondi=' WHERE '.$sCondi;}
	$sVacio='{'.$ETI['msg_seleccione'].'}';
	if ($vrunad11pais!='057'){$sVacio='{'.$ETI['msg_otro'].'}';}
	$objCombos->nuevo('unad11deptodoc', $valor, true, $sVacio);
	$objCombos->sAccion='carga_combo_unad11ciudaddoc()';
	$res=$objCombos->html('SELECT unad19codigo AS id, unad19nombre AS nombre FROM unad19depto'.$sCondi, $objdb);
	return $res;
	}
function f17_HTMLComboV2_unad11ciudaddoc($objdb, $objCombos, $valor, $vrunad11deptodoc){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	//@@ Se debe arreglar la condicion..
	$sCondi='unad20coddepto="'.$vrunad11deptodoc.'"';
	if ($sCondi!=''){$sCondi=' WHERE '.$sCondi;}
	$sVacio='{'.$ETI['msg_seleccione'].'}';
	if (substr($vrunad11deptodoc, 0, 3)!='057'){$sVacio='{'.$ETI['msg_otra'].'}';}
	$objCombos->nuevo('unad11ciudaddoc', $valor, true, $sVacio);
	$res=$objCombos->html('SELECT unad20codigo AS id, unad20nombre AS nombre FROM unad20ciudad'.$sCondi, $objdb);
	return $res;
	}
function f17_Combounad11idcead($params){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	require './app.php';
	$objdb=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objdb->dbPuerto=$APP->dbpuerto;}
	$objdb->xajax();
	$objCombos=new clsHtmlCombos('n');
	$html_unad11idcead=f17_HTMLComboV2_unad11idcead($objdb, $objCombos, '', $params[0]);
	$objdb->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_unad11idcead', 'innerHTML', $html_unad11idcead);
	return $objResponse;
	}
function f17_Combounad11idprograma($params){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	require './app.php';
	$objdb=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objdb->dbPuerto=$APP->dbpuerto;}
	$objdb->xajax();
	$objCombos=new clsHtmlCombos('n');
	$html_unad11idprograma=f17_HTMLComboV2_unad11idprograma($objdb, $objCombos, '', $params[0]);
	$objdb->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_unad11idprograma', 'innerHTML', $html_unad11idprograma);
	return $objResponse;
	}
function f17_Combounad11deptodoc($params){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	require './app.php';
	$objdb=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objdb->dbPuerto=$APP->dbpuerto;}
	$objdb->xajax();
	$objCombos=new clsHtmlCombos('n');
	$html_unad11deptodoc=f17_HTMLComboV2_unad11deptodoc($objdb, $objCombos, '', $params[0]);
	$html_unad11ciudaddoc=f17_HTMLComboV2_unad11ciudaddoc($objdb, $objCombos, '', '');
	$objdb->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_unad11deptodoc', 'innerHTML', $html_unad11deptodoc);
	$objResponse->assign('div_unad11ciudaddoc', 'innerHTML', $html_unad11ciudaddoc);
	return $objResponse;
	}
function f17_Combounad11ciudaddoc($params){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	require './app.php';
	$objdb=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objdb->dbPuerto=$APP->dbpuerto;}
	$objdb->xajax();
	$objCombos=new clsHtmlCombos('n');
	$html_unad11ciudaddoc=f17_HTMLComboV2_unad11ciudaddoc($objdb, $objCombos, '', $params[0]);
	$objdb->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_unad11ciudaddoc', 'innerHTML', $html_unad11ciudaddoc);
	return $objResponse;
	}
?>