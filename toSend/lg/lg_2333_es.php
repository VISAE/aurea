<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2019 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Version 2.23.8 Wednesday, October 30, 2019
---
--- Cambios 19 de mayo de 2020
--- 1. Agrega Etiquetas escuela y programa
--- Omar Augusto Bautista Mora - UNAD - 2020
--- omar.bautista@unad.edu.co
*/
$ETI['app_nombre']='APP';
$ETI['grupo_nombre']='Grupo';
$ETI['titulo']='Consolidado de acompanamientos';
$ETI['titulo_sector2']='Consolidado de acompanamientos';
$ETI['titulo_2333']='Consolidado de acompanamientos';
$ETI['sigla_2333']='Consolidado de acompanamientos';
$ETI['lnk_cargar']='Editar';
$ETI['cara33idperaca']='Periodo';
$ETI['cara33idzona']='Zona';
$ETI['cara33idcentro']='Centro';
$ETI['cara33idescuela']='Escuela';
$ETI['cara33idprograma']='Programa';
$ETI['cara33tipo']='Tipo';
$ETI['cara33poblacion']='Poblaci&oacute;n';

$ERR['cara33idperaca']='Necesita el dato '.$ETI['cara33idperaca'];
$ERR['cara33idzona']='Necesita el dato '.$ETI['cara33idzona'];
$ERR['cara33idcentro']='Necesita el dato '.$ETI['cara33idcentro'];
$ERR['cara33tipo']='Necesita el dato '.$ETI['cara33tipo'];
$ERR['cara33poblacion']='Necesita el dato '.$ETI['cara33poblacion'];
$acara33tipo=array('', '');
$icara33tipo=0;
$acara33poblacion=array('', '');
$icara33poblacion=0;

$ETI['msg_r1zona']='Vive en zona rural';
$ETI['msg_r1indigena']='Indigena';
$ETI['msg_r1recluso']='Recluso';
$ETI['msg_r1discsen']='Discapacidad Sensorial';
$ETI['msg_r1discfis']='Discapacidad Fisica';
$ETI['msg_r1disccog']='Discapacidad Cognitiva';
$ETI['msg_r1desplazado']='Desplazado';
$ETI['msg_r1acr']='ACR';
$ETI['msg_r1depende']='Depende economicamente';

$ETI['msg_r3tiemponoest']='Mas de 5 anios sin estudiar';
$ETI['msg_r3sinequipo']='Sin equipo de acceso a internet';
$ETI['msg_r3sincomputador']='Sin computadora';
$ETI['msg_r3energia']='Sin electricidad permanente';
$ETI['msg_r3internet']='Sin internet permanente';
$ETI['msg_r3ofimatica']='No maneja paquetes ofimaticos';
$ETI['msg_r3nocorreo']='No usa correo electronico';
$ETI['msg_r3otroprog']='';

$ETI['msg_r4desempleado']='Desempleado';
$ETI['msg_r4menos1smm']='Gana menos de un salario minimo';
$ETI['msg_r4pocotiempo']='Menos de 4 horas libres a la semana';
?>