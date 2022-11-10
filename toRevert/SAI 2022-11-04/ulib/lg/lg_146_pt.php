<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2014 - 2020 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Version 1.0.7 miércoles, 25 de junio de 2014
--- Modelo Version 1.2.8 viernes, 24 de octubre de 2014
--- Modelo Version 2.12.1 viernes, 22 de enero de 2016
--- Modelo Version 2.12.3 jueves, 25 de febrero de 2016
--- Modelo Version 2.22.4 martes, 4 de septiembre de 2018
--- Modelo Version 2.23.2 jueves, 13 de junio de 2019
--- Modelo Version 2.25.7 jueves, 8 de octubre de 2020
*/
$ETI['app_nombre']='APP';
$ETI['grupo_nombre']='Grupo';
$ETI['titulo']='Periodos Acad&eacute;micos';
$ETI['titulo_sector2']='Periodos Academicos';
$ETI['titulo_146']='Periodos Acad&eacute;micos';
$ETI['sigla_146']='Periodos Acad&eacute;micos';
$ETI['lnk_cargar']='Editar';
$ETI['exte02id']='Id';
$ETI['exte02nombre']='Nombre';
$ETI['exte02titulo']='Titulo';
$ETI['exte02tipoperiodo']='Tipoperiodo';
$ETI['exte02vigente']='Vigente';
$ETI['exte02diasactividades']='Diasactividades';
$ETI['exte02permiteditaragenda']='Permiteditaragenda';
$ETI['exte02disponibleagendas']='Agendas disponibles';
$ETI['exte02rutaagendas']='Rutaagendas';
$ETI['exte02controlarNAVs']='Controlar NAVs';
$ETI['exte02previo1']='Ciclo Previo 1';
$ETI['exte02previo2']='Ciclo Previo 2';
$ETI['exte02descripcionperaca']='Descripci&oacute;n';
$ETI['exte02fechainslab']='Inicio';
$ETI['ext02fechafinlab']='Final';
$ETI['ext02fechainssalidas']='Inicio';
$ETI['ext02fechafinsalidas']='Final';
$ETI['ext02ofertalaboratorios']='Oferta de laboratorios';
$ETI['exte02fechalimgrupos']='Fecha limite para armar grupos';
$ETI['exte02contgrupos']='Contenedor';
$ETI['exte02solicitante']='Solicitante';
$ETI['exte02ofertacursos']='Oferta cursos';
$ETI['exte02ofertafechatope']='Fecha tope para la oferta';
$ETI['exte02oferfechatopecancela']='Fecha tope para cancelaciones';
$ETI['exte02fechainimatricula']='Matricula desde ';
$ETI['exte02fechafinmatricula']='Matricula hasta';
$ETI['exte02fechadevolderpec']='Devoluci&oacute;n de derecho pecuniarios hasta';
$ETI['exte02fechainducciones']='Fecha tope inducciones';
$ETI['exte02fechafinactiniciales']='Fecha final actividades iniciales';
$ETI['exte02fechatopeoil']='Fecha tope reporte notas de laboratorios';
$ETI['exte02fechafinactfinales']='Fecha final actividades finales';
$ETI['exte02fechatopecalifhabilita']='Fecha final habilitaciones';
$ETI['exte02fechatopeevaldocente']='Fecha tope evaluaci&oacute;n docente';
$ETI['exte02idciclo']='Ciclo acad&eacute;mico';
$ETI['exte02fechatopetablero']='Fecha tope tablero';
$ETI['exte02saltardobleaut']='Evitar doble autenticaci&oacute;n';
$ETI['exte02programaasociado']='Programa asociado';

$ERR['exte02id']='&Eacute; necess&aacute;rio o dado Id';
$ERR['exte02nombre']='&Eacute; necess&aacute;rio o dado Nombre';
$ERR['exte02titulo']='&Eacute; necess&aacute;rio o dado Titulo';
$ERR['exte02tipoperiodo']='&Eacute; necess&aacute;rio o dado Tipoperiodo';
$ERR['exte02vigente']='&Eacute; necess&aacute;rio o dado Vigente';
$ERR['exte02diasactividades']='&Eacute; necess&aacute;rio o dado Diasactividades';
$ERR['exte02permiteditaragenda']='&Eacute; necess&aacute;rio o dado Permiteditaragenda';
$ERR['exte02disponibleagendas']='&Eacute; necess&aacute;rio o dado Disponibleagendas';
$ERR['exte02rutaagendas']='&Eacute; necess&aacute;rio o dado Rutaagendas';
$ERR['exte02controlarNAVs']='Necesita el dato ControlarNAVs';
$ERR['exte02previo1']='Necesita el dato Previo1';
$ERR['exte02previo2']='Necesita el dato Previo2';
$ERR['exte02descripcionperaca']='Necesita el dato Descripcionperaca';
$ERR['exte02fechainslab']='Necesita el dato Fechainslab';
$ERR['ext02fechafinlab']='Necesita el dato Echafinlab';
$ERR['ext02fechainssalidas']='Necesita el dato Echainssalidas';
$ERR['ext02fechafinsalidas']='Necesita el dato Echafinsalidas';
$ERR['ext02ofertalaboratorios']='Necesita el dato Fertalaboratorios';
$ERR['exte02fechalimgrupos']='Necesita el dato '.$ETI['exte02fechalimgrupos'];
$ERR['exte02contgrupos']='Necesita el dato '.$ETI['exte02contgrupos'];
$ERR['exte02solicitante']='Necesita el dato '.$ETI['exte02solicitante'];
$ERR['exte02ofertacursos']='Necesita el dato '.$ETI['exte02ofertacursos'];
$ERR['exte02ofertafechatope']='Necesita el dato '.$ETI['exte02ofertafechatope'];
$ERR['exte02oferfechatopecancela']='Necesita el dato '.$ETI['exte02oferfechatopecancela'];
$ERR['exte02fechainimatricula']='Necesita el dato '.$ETI['exte02fechainimatricula'];
$ERR['exte02fechafinmatricula']='Necesita el dato '.$ETI['exte02fechafinmatricula'];
$ERR['exte02fechadevolderpec']='Necesita el dato '.$ETI['exte02fechadevolderpec'];
$ERR['exte02fechainducciones']='Necesita el dato '.$ETI['exte02fechainducciones'];
$ERR['exte02fechafinactiniciales']='Necesita el dato '.$ETI['exte02fechafinactiniciales'];
$ERR['exte02fechatopeoil']='Necesita el dato '.$ETI['exte02fechatopeoil'];
$ERR['exte02fechafinactfinales']='Necesita el dato '.$ETI['exte02fechafinactfinales'];
$ERR['exte02fechatopecalifhabilita']='Necesita el dato '.$ETI['exte02fechatopecalifhabilita'];
$ERR['exte02fechatopeevaldocente']='Necesita el dato '.$ETI['exte02fechatopeevaldocente'];
$ERR['exte02idciclo']='Necesita el dato '.$ETI['exte02idciclo'];
$ERR['exte02fechatopetablero']='Necesita el dato '.$ETI['exte02fechatopetablero'];
$ERR['exte02saltardobleaut']='Necesita el dato '.$ETI['exte02saltardobleaut'];
$ERR['exte02programaasociado']='Necesita el dato '.$ETI['exte02programaasociado'];

$aexte02previo2=array('', '');
$iexte02previo2=0;

$ETI['msg_oferta']='Oferta Acad&eacute;mica';
$ETI['msg_grupos']='Grupos de estudiantes';
$ETI['msg_agendas']='Agendas';
$ETI['msg_fechalab']='Inscripciones a laboratorios';
$ETI['msg_fechasalida']='Inscripciones a salidas de campo';
$ETI['msg_nofechas']='No se han definido fechas extremas para el periodo (Fechas de las agendas en OAI - Configurar - Parametros)';
$ETI['msg_topecalificaciones']='Fechas tope para reporte de calificiones';
$ETI['msg_datadescripcion']='Recuerde incluir en la descripci&oacute;n el motivo por el cual se crea el periodo.';
$ETI['msg_noprocesadas']='Agendas no procesadas';
?>
