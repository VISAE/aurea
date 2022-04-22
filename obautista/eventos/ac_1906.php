<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2014 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo 1.2.8 viernes, 03 de octubre de 2014
*/
require 'app.php';
require $APP->rutacomun.'libs/clsdbad.php';
if (isset($_GET['q'])==0){return;}
// Si no necesita variables de session puede quitar el inicio de sesion
session_start();
$sql='SELECT even06consec, even06titulo FROM even06certificados WHERE CONCAT(even06consec, even06titulo) LIKE "%'.$_GET['q'].'%" LIMIT 0,7';
$tabla=$objdb->ejecutasql($sql);
while($fila=$objdb->sf($tabla)){
	$campo1=$fila['even06consec'].' '.$fila['even06titulo'];
	$campo2=$fila['even06consec'];
	echo "$campo1|$campo2\n";
	}
echo "|";
return;
?>