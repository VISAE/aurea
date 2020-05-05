<?php
session_start();
if (isset($_SESSION['unad_id_tercero'])==0){$_SESSION['unad_id_tercero']=0;}
if ($_SESSION['unad_id_tercero']==0){
	//Ver si se debe iniciar la sesion..
	require './app.php';
	$idEntidad=0;
	if (isset($APP->entidad)!=0){
		if ($APP->entidad==1){$idEntidad=1;}
		}
	switch($idEntidad){
		case 1:
		$pageURL='./index.php';
		break;
		default:
		$pageURL='../index.php?app='.$APP->idsistema;
		break;
		}
	header('Location:'.$pageURL);
	}
?>