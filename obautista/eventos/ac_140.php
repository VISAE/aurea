<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2014 - 2016 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo 1.2.8 viernes, 03 de octubre de 2014
--- Modelo 2.15.8 miércoles, 02 de noviembre de 2016
*/
mb_internal_encoding('UTF-8');
require './app.php';
require $APP->rutacomun.'libs/clsdbadmin.php';
require $APP->rutacomun.'libtextos.php';
if (isset($_GET['q'])==0){return;}
// Si no necesita variables de session puede quitar el inicio de sesion
session_start();
$objdb=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
if ($APP->dbpuerto!=''){$objdb->dbPuerto=$APP->dbpuerto;}
$sql='SELECT unad40consec, unad40nombre FROM unad40curso WHERE CONCAT(unad40consec, unad40nombre) LIKE "%'.$_GET['q'].'%" LIMIT 0,7';
$tabla=$objdb->ejecutasql($sql);
if ($objdb->nf($tabla)==0){return;}
while($fila=$objdb->sf($tabla)){
	$campo1=$fila['unad40consec'].' '.texto_ParaHtml($fila['unad40nombre']).'';
	$campo2=$fila['unad40consec'];
	echo $campo1.'|'.$campo2."\n";
	}
$objdb->CerrarConexion();
echo '|';
return;
?>