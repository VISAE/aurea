<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2026 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Model 3.1.5b lunes, 23 de marzo de 2026
*/
$ETI['titulo'] = 'Mensajes masivos';
$ETI['titulo_sector2'] = 'Mensajes masivos';
$ETI['titulo_sector93'] = 'Change of consecutive';
$ETI['titulo_1205'] = 'Mensajes masivos';
$ETI['titulo_busca_1205'] = 'Mensajes masivos search';
$ETI['sigla_1205'] = 'Mensajes masivos';
$ETI['bt_ter_buscar'] = 'Search document';
$ETI['bt_ter_crear'] = 'Make document';
$ETI['lnk_cargar'] = 'Edit';
$ETI['masi05idproceso'] = 'Proceso';
$ETI['masi05consec'] = 'Consecutive';
$ETI['msg_masi05consec'] = 'Current consecutive';
$ETI['msg_masi05consec_nuevo'] = 'New consecutive';
$ETI['masi05id'] = 'Ref :';
$ETI['masi05estado'] = 'Estado';
$ETI['masi05asunto'] = 'Asunto';
$ETI['masi05cuerpo'] = 'Cuerpo';
$ETI['masi05admiterpta'] = 'Admiterpta';
$ETI['masi05correorpta'] = 'Correorpta';
$ETI['masi05firma'] = 'Firma';
$ETI['masi05idusuario'] = 'Usuario';
$ETI['masi05idusuario_busca'] = 'Usuario search';
$ETI['masi05fecha'] = 'Fecha';
$ETI['masi05hora'] = 'Hora';
$ETI['masi05min'] = 'Min';
$ETI['masi05unidadfunc'] = 'Unidadfunc';
$ETI['masi05zona'] = 'Zona';
$ETI['masi05centro'] = 'Centro';
$ETI['masi05escuela'] = 'Escuela';
$ETI['masi05programa'] = 'Programa';
$ETI['masi05idperiodo'] = 'Periodo';
$ETI['masi05curso'] = 'Curso';
$ETI['masi05docente'] = 'Docente';
$ETI['masi05total_usuarios'] = 'Total_usuarios';
$ETI['masi05total_envios'] = 'Total_envios';
$ETI['masi05tiponotifica'] = 'Tiponotifica';
$ETI['masi05periodicidad'] = 'Periodicidad';
$ETI['masi05idrelacion'] = 'Relacion';
$ETI['masi05idrelacion_2711'] = 'Cohorte';
$ETI['masi05idrelacion2'] = 'Relacion2';
$ETI['masi05idrelacion2_2711'] = 'Estado postulaci&oacute;n';
$ETI['masi05idrelacion3'] = 'Relacion3';
$ETI['msg_cierre1205'] = '&iquest;Are you sure to close?<br>after close no modification allowed.';

$ERR['masi05idproceso'] = 'The field ' . $ETI['masi05idproceso'] . ' is required';
$ERR['masi05consec'] = 'The field ' . $ETI['masi05consec'] . ' is required';
$ERR['masi05id'] = 'The field ' . $ETI['masi05id'] . ' is required';
$ERR['masi05estado'] = 'The field ' . $ETI['masi05estado'] . ' is required';
$ERR['masi05asunto'] = 'The field ' . $ETI['masi05asunto'] . ' is required';
$ERR['masi05cuerpo'] = 'The field ' . $ETI['masi05cuerpo'] . ' is required';
$ERR['masi05admiterpta'] = 'The field ' . $ETI['masi05admiterpta'] . ' is required';
$ERR['masi05correorpta'] = 'The field ' . $ETI['masi05correorpta'] . ' is required';
$ERR['masi05firma'] = 'The field ' . $ETI['masi05firma'] . ' is required';
$ERR['masi05idusuario'] = 'The field ' . $ETI['masi05idusuario'] . ' is required';
$ERR['masi05fecha'] = $ETI['masi05fecha'] . ' incorrect';
$ERR['masi05hora'] = 'The field ' . $ETI['masi05hora'] . ' is required';
$ERR['masi05min'] = 'The field ' . $ETI['masi05min'] . ' is required';
$ERR['masi05unidadfunc'] = 'The field ' . $ETI['masi05unidadfunc'] . ' is required';
$ERR['masi05zona'] = 'The field ' . $ETI['masi05zona'] . ' is required';
$ERR['masi05centro'] = 'The field ' . $ETI['masi05centro'] . ' is required';
$ERR['masi05escuela'] = 'The field ' . $ETI['masi05escuela'] . ' is required';
$ERR['masi05programa'] = 'The field ' . $ETI['masi05programa'] . ' is required';
$ERR['masi05idperiodo'] = 'The field ' . $ETI['masi05idperiodo'] . ' is required';
$ERR['masi05curso'] = 'The field ' . $ETI['masi05curso'] . ' is required';
$ERR['masi05docente'] = 'The field ' . $ETI['masi05docente'] . ' is required';
$ERR['masi05total_usuarios'] = 'The field ' . $ETI['masi05total_usuarios'] . ' is required';
$ERR['masi05total_envios'] = 'The field ' . $ETI['masi05total_envios'] . ' is required';
$ERR['masi05tiponotifica'] = 'The field ' . $ETI['masi05tiponotifica'] . ' is required';
$ERR['masi05periodicidad'] = 'The field ' . $ETI['masi05periodicidad'] . ' is required';
$ERR['masi05idrelacion'] = 'The field ' . $ETI['masi05idrelacion'] . ' is required';
$ERR['masi05idrelacion2'] = 'The field ' . $ETI['masi05idrelacion2'] . ' is required';
$ERR['masi05idrelacion3'] = 'The field ' . $ETI['masi05idrelacion3'] . ' is required';

$amasi05admiterpta = array('', '');
$imasi05admiterpta = 0;
$amasi05idperiodo = array('', '');
$imasi05idperiodo = 0;
$amasi05curso = array('', '');
$imasi05curso = 0;
$amasi05docente = array('', '');
$imasi05docente = 0;
$amasi05periodicidad = array('No aplica', 'Semanal', 'Quincenal', 'Mensual');
$imasi05periodicidad = 4;

$ETI['msg_bmes'] = 'Mes';
$ETI['msg_basunto'] = 'Asunto';
$ETI['msg_bcuerpo'] = 'Cuerpo';
$ETI['msg_bfechainicia'] = 'Fechainicia';
$ETI['msg_bfechafinal'] = 'Fechafinal';
$ETI['msg_bunidadfunc'] = 'Unidadfunc';
$ETI['msg_bzona'] = 'Zona';
$ETI['msg_bcentro'] = 'Centro';
$ETI['msg_bescuela'] = 'Escuela';
$ETI['msg_bprograma'] = 'Programa';
$ETI['msg_bcurso'] = 'Curso';
$ETI['msg_bproceso'] = 'Proceso';

