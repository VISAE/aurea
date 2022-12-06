<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2015 - 2018 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
*/
if (file_exists('./err_control.php')){require './err_control.php';}
$bDebug=false;
$sDebug='';
if (isset($_REQUEST['debug'])!=0){
	if ($_REQUEST['debug']==1){
		$bDebug=true;
		}
	}else{
	$_REQUEST['debug']=0;
	}
if ($bDebug){
	$iSegIni=microtime(true);
	$iSegundos=floor($iSegIni);
	$sMili=floor(($iSegIni-$iSegundos)*1000);
	if ($sMili<100){if ($sMili<10){$sMili=':00'.$sMili;}else{$sMili=':0'.$sMili;}}else{$sMili=':'.$sMili;}
	$sDebug=$sDebug.''.date('H:i:s').$sMili.' Inicia pagina <br>';
	}
@session_start();
if (isset($_SESSION['unad_id_sesion'])!=0){
	require './app.php';
	require $APP->rutacomun.'libs/clsdbadmin.php';
	require $APP->rutacomun.'unad_librerias.php';
	$objdb=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objdb->dbPuerto=$APP->dbpuerto;}
	if ($bDebug){$sDebug=$sDebug.''.fecha_microtiempo().' Cerrando sesion para '.$_SESSION['unad_id_sesion'].'<br>';}
	$sDebugC=login_cerrarsesion_v2($_SESSION['unad_id_sesion'], $objdb, $bDebug);
	$sDebug=$sDebug.$sDebugC;
	}else{
	if ($bDebug){$sDebug=$sDebug.''.fecha_microtiempo().' No hay sesion a cerrar.<br>';}
	}
session_destroy();
@session_start();
if (!$bDebug){
	header('Location:./');
	}else{
	echo $sDebug;
	}
?>