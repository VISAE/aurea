<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2021 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Version 2.25.10b martes, 2 de febrero de 2021
*/
$ETI['app_nombre']='APP';
$ETI['grupo_nombre']='Grupo';
$ETI['titulo']='Novedades de matricula';
$ETI['titulo_sector2']='Novedades de matricula';
$ETI['titulo_sector2devolver']='Devolver solicitud';
$ETI['titulo_reposicion']='Recibir recurso de reposici&oacute;n';
$ETI['titulo_sector93']='Mudar consecutivo';
$ETI['titulo_12206']='Novedades de matricula';
$ETI['titulo_12244']='Revisi&oacute;n de novedades por zona';
$ETI['titulo_12245']='Revisi&oacute;n de novedades por escuela';
$ETI['titulo_12246']='Aprobar cambios de centro';
$ETI['sigla_12206']='Novedades de matricula';
$ETI['bt_ter_buscar']='Pesquisar terceiro';
$ETI['bt_ter_crear']='Criar terceiro';
$ETI['lnk_cargar']='Editar';
$ETI['corf06idactoadmin']='Acto administrativo';
$ETI['corf06tiponov']='Tiponov';
$ETI['corf06consec']='Consec';
$ETI['msg_corf06consec']='Consecutivo actual';
$ETI['msg_corf06consec_nuevo']='Nuevo consecutivo';
$ETI['corf06id']='Id';
$ETI['corf06estado']='Estado';
$ETI['corf06idestudiante']='Estudiante';
$ETI['corf06idperiodo']='Periodo';
$ETI['corf06idescuela']='Escuela';
$ETI['corf06idprograma']='Programa';
$ETI['corf06fecha']='Fecha';
$ETI['corf06hora']='Hora';
$ETI['corf06min']='Min';
$ETI['corf06autoriza1']='Autoriza1';
$ETI['corf06autoriza2']='Autoriza2';
$ETI['corf06fechaplica']='Fechaplica';
$ETI['corf06horaaplica']='Horaaplica';
$ETI['corf06minaplica']='Minaplica';
$ETI['corf06idsesion']='Sesion';
$ETI['corf06idzona']='Zona';
$ETI['corf06idcentro']='Centro';
$ETI['corf06idzonadest']='Zonadest';
$ETI['corf06idcentrodest']='Centrodest';
$ETI['corf06idestprograma']='Programa';

$ERR['corf06idactoadmin']='Necesita el dato '.$ETI['corf06idactoadmin'];
$ERR['corf06tiponov']='&Eacute; necess&aacute;rio o dado '.$ETI['corf06tiponov'];
$ERR['corf06consec']='&Eacute; necess&aacute;rio o dado '.$ETI['corf06consec'];
$ERR['corf06id']='&Eacute; necess&aacute;rio o dado '.$ETI['corf06id'];
$ERR['corf06estado']='&Eacute; necess&aacute;rio o dado '.$ETI['corf06estado'];
$ERR['corf06idestudiante']='&Eacute; necess&aacute;rio o dado '.$ETI['corf06idestudiante'];
$ERR['corf06idperiodo']='&Eacute; necess&aacute;rio o dado '.$ETI['corf06idperiodo'];
$ERR['corf06idescuela']='&Eacute; necess&aacute;rio o dado '.$ETI['corf06idescuela'];
$ERR['corf06idprograma']='&Eacute; necess&aacute;rio o dado '.$ETI['corf06idprograma'];
$ERR['corf06fecha']='&Eacute; necess&aacute;rio o dado '.$ETI['corf06fecha'];
$ERR['corf06hora']='&Eacute; necess&aacute;rio o dado '.$ETI['corf06hora'];
$ERR['corf06min']='&Eacute; necess&aacute;rio o dado '.$ETI['corf06min'];
$ERR['corf06autoriza1']='&Eacute; necess&aacute;rio o dado '.$ETI['corf06autoriza1'];
$ERR['corf06autoriza2']='&Eacute; necess&aacute;rio o dado '.$ETI['corf06autoriza2'];
$ERR['corf06fechaplica']='&Eacute; necess&aacute;rio o dado '.$ETI['corf06fechaplica'];
$ERR['corf06horaaplica']='&Eacute; necess&aacute;rio o dado '.$ETI['corf06horaaplica'];
$ERR['corf06minaplica']='&Eacute; necess&aacute;rio o dado '.$ETI['corf06minaplica'];
$ERR['corf06idsesion']='&Eacute; necess&aacute;rio o dado '.$ETI['corf06idsesion'];
$ERR['corf06idzona']='Necesita el dato '.$ETI['corf06idzona'];
$ERR['corf06idcentro']='Necesita el dato '.$ETI['corf06idcentro'];
$ERR['corf06idzonadest']='Necesita el dato '.$ETI['corf06idzonadest'];
$ERR['corf06idcentrodest']='Necesita el dato '.$ETI['corf06idcentrodest'];
$ERR['corf06idestprograma']='Necesita el dato '.$ETI['corf06idestprograma'];

$acorf06estado=array('Borrador', 'Solicitada', 'Devuelta', 'En estudio por la escuela', 'En reposici&oacute;n', '', '', 'Aprobada', 'Anulada', 'Negada', 'No procede', 'Estudiante desiste');
$icorf06estado=11;

$ETI['msg_origen']='Origen';
$ETI['msg_destino']='Destino';

$ETI['lnk_aprobar']='Aprobar';
$ETI['lnk_negar']='Negar';
$ETI['lnk_liberar']='Liberar';
?>