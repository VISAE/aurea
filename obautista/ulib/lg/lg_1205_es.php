<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2026 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo 3.1.5b lunes, 23 de marzo de 2026
*/
$ETI['titulo'] = 'Mensajes masivos';
$ETI['titulo_sector2'] = 'Mensajes masivos';
$ETI['titulo_sector93'] = 'Cambio de consecutivo';
$ETI['titulo_1205'] = 'Mensajes masivos';
$ETI['titulo_busca_1205'] = 'Busqueda de mensajes masivos';
$ETI['sigla_1205'] = 'Mensajes masivos';
$ETI['bt_ter_buscar'] = 'Buscar tercero';
$ETI['bt_ter_crear'] = 'Crear tercero';
$ETI['lnk_cargar'] = 'Editar';
$ETI['masi05idproceso'] = 'Proceso';
$ETI['masi05consec'] = 'Consecutivo';
$ETI['msg_masi05consec'] = 'Consecutivo actual';
$ETI['msg_masi05consec_nuevo'] = 'Nuevo consecutivo';
$ETI['masi05id'] = 'Ref :';
$ETI['masi05estado'] = 'Estado';
$ETI['masi05asunto'] = 'Asunto';
$ETI['masi05cuerpo'] = 'Cuerpo';
$ETI['masi05admiterpta'] = 'Admite respuesta';
$ETI['masi05correorpta'] = 'Correo respuesta';
$ETI['masi05firma'] = 'Firma';
$ETI['masi05idusuario'] = 'Usuario';
$ETI['masi05idusuario_busca'] = 'Busqueda de idusuario';
$ETI['masi05fecha'] = 'Fecha para env&iacute;o';
$ETI['masi05hora'] = 'Hora';
$ETI['masi05min'] = 'Min';
$ETI['masi05unidadfunc'] = 'Unidad funcional';
$ETI['masi05zona'] = 'Zona';
$ETI['masi05centro'] = 'Centro';
$ETI['masi05escuela'] = 'Escuela';
$ETI['masi05programa'] = 'Programa';
$ETI['masi05idperiodo'] = 'Periodo';
$ETI['masi05curso'] = 'Curso';
$ETI['masi05docente'] = 'Docente';
$ETI['masi05total_usuarios'] = 'Total usuarios';
$ETI['masi05total_envios'] = 'Total envios';
$ETI['masi05tiponotifica'] = 'Tipo notifica';
$ETI['masi05periodicidad'] = 'Periodicidad';
$ETI['masi05idrelacion'] = 'Relacion 1';
$ETI['masi05idrelacion_2711'] = 'Cohorte';
$ETI['masi05idrelacion2'] = 'Relacion 2';
$ETI['masi05idrelacion2_2711'] = 'Estado postulaci&oacute;n';
$ETI['masi05idrelacion3'] = 'Relacion 3';
$ETI['msg_cierre1205'] = '&iquest;Est&aacute; seguro de cerrar el registro?<br>luego de cerrado no se permite modificar.';

$ERR['masi05idproceso'] = 'Necesita el dato ' . $ETI['masi05idproceso'];
$ERR['masi05consec'] = 'Necesita el dato ' . $ETI['masi05consec'];
$ERR['masi05id'] = 'Necesita el dato ' . $ETI['masi05id'];
$ERR['masi05estado'] = 'Necesita el dato ' . $ETI['masi05estado'];
$ERR['masi05asunto'] = 'Necesita el dato ' . $ETI['masi05asunto'];
$ERR['masi05cuerpo'] = 'Necesita el dato ' . $ETI['masi05cuerpo'];
$ERR['masi05admiterpta'] = 'Necesita el dato ' . $ETI['masi05admiterpta'];
$ERR['masi05correorpta'] = 'Necesita el dato ' . $ETI['masi05correorpta'];
$ERR['masi05firma'] = 'Necesita el dato ' . $ETI['masi05firma'];
$ERR['masi05idusuario'] = 'Necesita el dato ' . $ETI['masi05idusuario'];
$ERR['masi05fecha'] = $ETI['masi05fecha'] . ' incorrecta';
$ERR['masi05hora'] = 'Necesita el dato ' . $ETI['masi05hora'];
$ERR['masi05min'] = 'Necesita el dato ' . $ETI['masi05min'];
$ERR['masi05unidadfunc'] = 'Necesita el dato ' . $ETI['masi05unidadfunc'];
$ERR['masi05zona'] = 'Necesita el dato ' . $ETI['masi05zona'];
$ERR['masi05centro'] = 'Necesita el dato ' . $ETI['masi05centro'];
$ERR['masi05escuela'] = 'Necesita el dato ' . $ETI['masi05escuela'];
$ERR['masi05programa'] = 'Necesita el dato ' . $ETI['masi05programa'];
$ERR['masi05idperiodo'] = 'Necesita el dato ' . $ETI['masi05idperiodo'];
$ERR['masi05curso'] = 'Necesita el dato ' . $ETI['masi05curso'];
$ERR['masi05docente'] = 'Necesita el dato ' . $ETI['masi05docente'];
$ERR['masi05total_usuarios'] = 'Necesita el dato ' . $ETI['masi05total_usuarios'];
$ERR['masi05total_envios'] = 'Necesita el dato ' . $ETI['masi05total_envios'];
$ERR['masi05tiponotifica'] = 'Necesita el dato ' . $ETI['masi05tiponotifica'];
$ERR['masi05periodicidad'] = 'Necesita el dato ' . $ETI['masi05periodicidad'];
$ERR['masi05idrelacion'] = 'Necesita el dato ' . $ETI['masi05idrelacion'];
$ERR['masi05idrelacion2'] = 'Necesita el dato ' . $ETI['masi05idrelacion2'];
$ERR['masi05idrelacion3'] = 'Necesita el dato ' . $ETI['masi05idrelacion3'];

$amasi05admiterpta = array('', '');
$imasi05admiterpta = 0;
$amasi05idperiodo = array('', '');
$imasi05idperiodo = 0;
$amasi05curso = array('', '');
$imasi05curso = 0;
$amasi05docente = array('', '');
$imasi05docente = 0;
$amasi05periodicidad = array('No aplica', 'Semanal', 'Quincenal', '', 'Mensual');
$imasi05periodicidad = 4;

$ETI['msg_bmes'] = 'Mes';
$ETI['msg_basunto'] = 'Asunto';
$ETI['msg_bcuerpo'] = 'Cuerpo';
$ETI['msg_bfechainicia'] = 'Fecha inicial';
$ETI['msg_bfechafinal'] = 'Fecha final';
$ETI['msg_bunidadfunc'] = 'Unidad funcional';
$ETI['msg_bzona'] = 'Zona';
$ETI['msg_bcentro'] = 'Centro';
$ETI['msg_bescuela'] = 'Escuela';
$ETI['msg_bprograma'] = 'Programa';
$ETI['msg_bcurso'] = 'Curso';
$ETI['msg_bproceso'] = 'Proceso';

