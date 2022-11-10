<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2021 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Version 2.25.10b martes, 2 de febrero de 2021
*/
$ETI['app_nombre']='APP';
$ETI['grupo_nombre']='Grupo';
$ETI['titulo']='Novedades de matricula';
$ETI['titulo_sector2']='Solicitud no procedente';
$ETI['titulo_sector2devolver']='Devolver solicitud';
$ETI['titulo_reposicion']='Recibir recurso de reposici&oacute;n';
$ETI['titulo_sector93']='Cambio de consecutivo';
$ETI['titulo_12206']='Novedades de matricula';
$ETI['titulo_12244']='Revisi&oacute;n de novedades por zona';
$ETI['titulo_12245']='Revisi&oacute;n de novedades por escuela';
$ETI['titulo_12246']='Aprobar cambios de centro';
$ETI['sigla_12206']='Novedades de matricula';
$ETI['bt_ter_buscar']='Buscar tercero';
$ETI['bt_ter_crear']='Crear tercero';
$ETI['lnk_cargar']='Editar';
$ETI['corf06idactoadmin']='Acto administrativo';
$ETI['corf06tiponov']='Tipo';
$ETI['corf06consec']='Consecutivo';
$ETI['msg_corf06consec']='Consecutivo actual';
$ETI['msg_corf06consec_nuevo']='Nuevo consecutivo';
$ETI['corf06id']='Ref :';
$ETI['corf06estado']='Estado';
$ETI['corf06idestudiante']='Estudiante';
$ETI['corf06idperiodo']='Periodo';
$ETI['corf06idescuela']='Escuela';
$ETI['corf06idprograma']='Programa';
$ETI['corf06fecha']='Fecha';
$ETI['corf06hora']='Hora';
$ETI['corf06min']='Min';
$ETI['corf06autoriza1']='Autoriza por';
$ETI['corf06autoriza2']='Segunda autorizaci&oacute';
$ETI['corf06fechaplica']='Fecha aplica';
$ETI['corf06horaaplica']='Hora aplica';
$ETI['corf06minaplica']='Min aplica';
$ETI['corf06idsesion']='N&deg; de sesion';
$ETI['corf06idzona']='Zona';
$ETI['corf06idcentro']='Centro';
$ETI['corf06idzonadest']='Zona';
$ETI['corf06idcentrodest']='Centro';
$ETI['corf06idestprograma']='Programa';

$ERR['corf06idactoadmin']='Necesita el dato '.$ETI['corf06idactoadmin'];
$ERR['corf06tiponov']='Necesita el dato '.$ETI['corf06tiponov'];
$ERR['corf06consec']='Necesita el dato '.$ETI['corf06consec'];
$ERR['corf06id']='Necesita el dato '.$ETI['corf06id'];
$ERR['corf06estado']='Necesita el dato '.$ETI['corf06estado'];
$ERR['corf06idestudiante']='Necesita el dato '.$ETI['corf06idestudiante'];
$ERR['corf06idperiodo']='Necesita el dato '.$ETI['corf06idperiodo'];
$ERR['corf06idescuela']='Necesita el dato '.$ETI['corf06idescuela'];
$ERR['corf06idprograma']='Necesita el dato '.$ETI['corf06idprograma'];
$ERR['corf06fecha']='Necesita el dato '.$ETI['corf06fecha'];
$ERR['corf06hora']='Necesita el dato '.$ETI['corf06hora'];
$ERR['corf06min']='Necesita el dato '.$ETI['corf06min'];
$ERR['corf06autoriza1']='Necesita el dato '.$ETI['corf06autoriza1'];
$ERR['corf06autoriza2']='Necesita el dato '.$ETI['corf06autoriza2'];
$ERR['corf06fechaplica']='Necesita el dato '.$ETI['corf06fechaplica'];
$ERR['corf06horaaplica']='Necesita el dato '.$ETI['corf06horaaplica'];
$ERR['corf06minaplica']='Necesita el dato '.$ETI['corf06minaplica'];
$ERR['corf06idsesion']='Necesita el dato '.$ETI['corf06idsesion'];
$ERR['corf06idzona']='Necesita el dato '.$ETI['corf06idzona'];
$ERR['corf06idcentro']='Necesita el dato '.$ETI['corf06idcentro'];
$ERR['corf06idzonadest']='Necesita el dato '.$ETI['corf06idzonadest'];
$ERR['corf06idcentrodest']='Necesita el dato '.$ETI['corf06idcentrodest'];
$ERR['corf06idestprograma']='Necesita el dato '.$ETI['corf06idestprograma'];

$acorf06estado=array('Borrador', 'Solicitada', 'Devuelta', 'En estudio por la escuela', 'En reposici&oacute;n', '', 'Desistida por t&eacute;rminos', 'Aprobada', 'Anulada', 'Negada', 'No procede', 'Estudiante desiste');
$icorf06estado=11;

$ETI['msg_origen']='Origen';
$ETI['msg_destino']='Destino';

$ETI['lnk_aprobar']='Aprobar';
$ETI['lnk_negar']='Negar';
$ETI['lnk_liberar']='Liberar';
?>