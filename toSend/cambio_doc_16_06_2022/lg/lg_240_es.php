<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2022 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Version 2.28.2 jueves, 26 de mayo de 2022
*/
$ETI['app_nombre'] = 'APP';
$ETI['grupo_nombre'] = 'Grupo';
$ETI['titulo'] = 'Solicitud de cambio de documento';
$ETI['titulo_sector2'] = 'Solicitud de cambio de documento';
$ETI['titulo_sector93'] = 'Cambio de consecutivo';
$ETI['titulo_240'] = 'Solicitud de cambio de documento';
$ETI['sigla_240'] = 'Solicitud de cambio de documento';
$ETI['bt_ter_buscar'] = 'Buscar tercero';
$ETI['bt_ter_crear'] = 'Crear tercero';
$ETI['lnk_cargar'] = 'Editar';
$ETI['unae24idtercero'] = 'Tercero';
$ETI['unae24consec'] = 'Consecutivo';
$ETI['msg_unae24consec'] = 'Consecutivo actual';
$ETI['msg_unae24consec_nuevo'] = 'Nuevo consecutivo';
$ETI['unae24id'] = 'Ref :';
$ETI['unae24tipodocorigen'] = 'Tipodocorigen';
$ETI['unae24docorigen'] = 'Docorigen';
$ETI['unae24destino'] = 'Cambios';
$ETI['unae24tipodocdestino'] = 'Tipo documento';
$ETI['unae24docdestino'] = 'Documento';
$ETI['unae24idsolicita'] = 'Solicitante';
$ETI['unae24fechasol'] = 'Fecha Solicitud';
$ETI['unae24horasol'] = 'Hora Solicitud';
$ETI['unae24minsol'] = 'Minsol';
$ETI['unae24idorigen'] = 'Origen';
$ETI['eliminar_unae24idarchivo'] = 'Desea eliminar el archivo?';
$ETI['unae24idarchivo'] = 'Archivo';
$ETI['unae24estado'] = 'Estado';
$ETI['unae24detalle'] = 'Detalle';
$ETI['unae24idaprueba'] = 'Aprueba';
$ETI['unae24fechaapr'] = 'Fechaapr';
$ETI['unae24horaaprueba'] = 'Horaaprueba';
$ETI['unae24minaprueba'] = 'Minaprueba';
$ETI['unae24tiempod'] = 'Tiempod';
$ETI['unae24tiempoh'] = 'Tiempoh';
$ETI['msg_cierre240'] = '&iquest;Est&aacute; seguro de cerrar el registro?<br>luego de cerrado no se permite modificar.';
$ETI['unae24archivo'] = 'Adjuntar soporte';
$ETI['bloquehistorial'] = 'Historial de solicitudes';
$ETI['ayuda_unae24idarchivo'] = 'Apreciado estudiante: para enviar la solicitud es necesario adjuntar el documento que justifica el cambio en el documento.';

$aunae24estado = array('Borrador','','','Radicado','','','','Aprobado','','Negado');

$ERR['unae24idtercero'] = 'Necesita el dato ' . $ETI['unae24idtercero'];
$ERR['unae24consec'] = 'Necesita el dato ' . $ETI['unae24consec'];
$ERR['unae24id'] = 'Necesita el dato ' . $ETI['unae24id'];
$ERR['unae24tipodocorigen'] = 'Necesita el dato ' . $ETI['unae24tipodocorigen'];
$ERR['unae24docorigen'] = 'Necesita el dato ' . $ETI['unae24docorigen'];
$ERR['unae24tipodocdestino'] = 'Necesita el dato ' . $ETI['unae24tipodocdestino'];
$ERR['unae24docdestino'] = 'Necesita el dato ' . $ETI['unae24docdestino'];
$ERR['unae24idsolicita'] = 'Necesita el dato ' . $ETI['unae24idsolicita'];
$ERR['unae24fechasol'] = 'Necesita el dato ' . $ETI['unae24fechasol'];
$ERR['unae24horasol'] = 'Necesita el dato ' . $ETI['unae24horasol'];
$ERR['unae24minsol'] = 'Necesita el dato ' . $ETI['unae24minsol'];
$ERR['unae24idorigen'] = 'Necesita el dato ' . $ETI['unae24idorigen'];
$ERR['unae24idarchivo'] = 'Necesita el dato ' . $ETI['unae24idarchivo'];
$ERR['unae24estado'] = 'Necesita el dato ' . $ETI['unae24estado'];
$ERR['unae24detalle'] = 'Necesita el dato ' . $ETI['unae24detalle'];
$ERR['unae24idaprueba'] = 'Necesita el dato ' . $ETI['unae24idaprueba'];
$ERR['unae24fechaapr'] = 'Necesita el dato ' . $ETI['unae24fechaapr'];
$ERR['unae24horaaprueba'] = 'Necesita el dato ' . $ETI['unae24horaaprueba'];
$ERR['unae24minaprueba'] = 'Necesita el dato ' . $ETI['unae24minaprueba'];
$ERR['unae24tiempod'] = 'Necesita el dato ' . $ETI['unae24tiempod'];
$ERR['unae24tiempoh'] = 'Necesita el dato ' . $ETI['unae24tiempoh'];
$ERR['unae24solexistente'] = 'Ya se inici&oacute; solicitud con estos cambios'
?>