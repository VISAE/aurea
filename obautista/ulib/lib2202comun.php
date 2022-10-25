<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2019 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versi�n 2.22.7 martes, 5 de marzo de 2019
--- 2202 core01estprograma
*/
function f2202_HTMLComboV2_bprograma($objDB, $objCombos, $valor, $vrbescuela, $iVer=0, $bNivel=''){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('bprograma', $valor, true, '{'.$ETI['msg_todos'].'}');
	$objCombos->sAccion='carga_combo_bversion()';
	if ($iVer==1){
		$objCombos->sAccion='paginarf2202()';
		}
	$sSQL='';
	if ($vrbescuela != ''){
		$sTablas = '';
		$sSQLadd = '';
		switch ($bNivel) {
			case '':
				break;
			case '-1':
			case '-2':
			case '-3':
			case '-4':
				$iGrupo = $bNivel * (-1);
				$sTablas = ', core22nivelprograma AS T22';
				$sSQLadd = ' AND TB.cara09nivelformacion=T22.core22id AND T22.core22grupo=' . $iGrupo . '';
				break;
			default:
				$sSQLadd = ' AND TB.cara09nivelformacion=' . $bNivel . '';
				break;
		}
		$sSQL='SELECT TB.core09id AS id, CONCAT(TB.core09nombre, " - ", TB.core09codigo, CASE TB.core09activo WHEN "S" THEN "" ELSE " [INACTIVO]" END) AS nombre 
		FROM core09programa AS TB ' . $sTablas . ' 
		WHERE TB.core09idescuela='.$vrbescuela . $sSQLadd .'  
		ORDER BY TB.core09activo DESC, TB.core09nombre';
		$objCombos->iAncho=600;
		}
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f2202_Combobprograma($aParametros){
	$bConNivelForma = true;
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	if (isset($aParametros[1])==0){$aParametros[1]=0;}
	if (isset($aParametros[2])==0){
		$aParametros[2] = '';
		$bConNivelForma = false;
		}
	$iVer=$aParametros[1];
	$bNivelForma = $aParametros[2];
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$objCombos=new clsHtmlCombos();
	$html_bprograma=f2202_HTMLComboV2_bprograma($objDB, $objCombos, '', $aParametros[0], $iVer, $bNivelForma);
	if ($iVer==0){
		$html_bversion=f2202_HTMLComboV2_bversion($objDB, $objCombos, '', '');
		}
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_bprograma', 'innerHTML', $html_bprograma);
	if ($iVer==0){
		$objResponse->assign('div_bversion', 'innerHTML', $html_bversion);
		}
	$objResponse->call('paginarf2202()');
	$objResponse->call('$("#bprograma").chosen()');
	return $objResponse;
	}
function f2202_HTMLComboV2_bversion($objDB, $objCombos, $valor, $vrbprograma){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('bversion', $valor, true, '{'.$ETI['msg_todos'].'}');
	$objCombos->sAccion='paginarf2202()';
	$objCombos->addItem('0', '{'.$ETI['msg_ninguno'].'}');
	$sSQL='';
	if ($vrbprograma!=''){
		$objCombos->iAncho=300;
		$sCondi='';
		$sSQL='SELECT core10id, core10consec, core10numregcalificado, core10fechaversion, core10fechavence, core10estado 
		FROM core10programaversion 
		WHERE core10idprograma="'.$vrbprograma.'" AND core10estado IN ("S", "X") 
		ORDER BY core10consec DESC';
		// - N&deg; Res 
		$tabla=$objDB->ejecutasql($sSQL);
		while($fila=$objDB->sf($tabla)){
			$objCombos->addItem($fila['core10id'], $fila['core10consec'].' - N&deg; Res '.$fila['core10numregcalificado'].' - Vigente desde '.fecha_DesdeNumero($fila['core10fechaversion']).' hasta '.fecha_DesdeNumero($fila['core10fechavence']).'');
			}
		}
	$res=$objCombos->html('', $objDB);
	return $res;
	}
function f2202_Combobversion($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$objCombos=new clsHtmlCombos('n');
	$html_bversion=f2202_HTMLComboV2_bversion($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_bversion', 'innerHTML', $html_bversion);
	$objResponse->call('paginarf2202');
	return $objResponse;
	}
function f2202_HTMLComboV2_bcead($objDB, $objCombos, $valor, $vrcara01idzona){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('bcead', $valor, true, '{'.$ETI['msg_todos'].'}');
	$objCombos->sAccion='paginarf2202()';
	$sSQL='SELECT unad24id AS id, unad24nombre AS nombre FROM unad24sede WHERE unad24idzona="'.$vrcara01idzona.'"';
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f2202_Combobcead($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$objCombos=new clsHtmlCombos('n');
	$html_bcead=f2202_HTMLComboV2_bcead($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_bcead', 'innerHTML', $html_bcead);
	$objResponse->call('paginarf2202');
	$objResponse->call('$("#bcead").chosen()');
	return $objResponse;
	}
// Tipos de homologaciones
function f2202_HTMLComboV2_btipohomol($objDB, $objCombos, $valor, $vrbsituacion){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('btipohomol', $valor, true, '{'.$ETI['msg_todas'].'}');
	$objCombos->sAccion='paginarf2202()';
	$sSQL='';
	if ($vrbsituacion!=''){
		$objCombos->iAncho=600;
		$sSQL='SELECT core66id AS id, CONCAT(core66titulo, CASE core66activa WHEN 0 THEN " [INACTIVA]" ELSE "" END) AS nombre 
		FROM core66tipohomologa 
		WHERE core66clase='.$vrbsituacion.' AND core66validado<>0  
		ORDER BY core66activa DESC, core66titulo';
		}
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f2202_Combobtipohomol($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	if (isset($aParametros[1])==0){$aParametros[1]=0;}
	$iVer=$aParametros[1];
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$objCombos=new clsHtmlCombos();
	$html_btipohomol=f2202_HTMLComboV2_btipohomol($objDB, $objCombos, '', $aParametros[0], $aParametros[1]);
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_btipohomol', 'innerHTML', $html_btipohomol);
	$objResponse->call('paginarf2202()');
	$objResponse->call('$("#btipohomol").chosen()');
	return $objResponse;
	}
?>