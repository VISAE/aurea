<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2021 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Version 2.25.10c jueves, 6 de mayo de 2021
--- Modelo Version 2.26.3b miércoles, 21 de julio de 2021
*/
$ETI['app_nombre']='APP';
$ETI['grupo_nombre']='Grupo';
$ETI['titulo']='Comunicados';
$ETI['titulo_sector2']='Comunicados';
$ETI['titulo_sector93']='Cambio de consecutivo';
$ETI['titulo_3061']='Comunicados';
$ETI['sigla_3061']='Comunicados';
$ETI['bt_ter_buscar']='Buscar tercero';
$ETI['bt_ter_crear']='Crear tercero';
$ETI['lnk_cargar']='Editar';
$ETI['msg_plano3061']='Importaci&oacute;n masiva';
$ETI['msg_infoplano3061']='Se debe cargar un archivo MsExcel 2 columnas. <br>
Columna A: Tipo de documento.<br>
Columna B: Documento.';
$ETI['saiu61consec']='Consecutivo';
$ETI['msg_saiu61consec']='Consecutivo actual';
$ETI['msg_saiu61consec_nuevo']='Nuevo consecutivo';
$ETI['saiu61id']='Ref :';
$ETI['saiu61orden']='Orden';
$ETI['saiu61vigente']='Vigente';
$ETI['saiu61titulo']='Titulo';
$ETI['saiu61idunidad']='Unidad';
$ETI['saiu61fecha']='Fecha';
$ETI['saiu61fechapublica']='Fecha publica';
$ETI['saiu61fechadespublica']='Fecha despublica';
$ETI['saiu61cuerpo']='Cuerpo';
$ETI['saiu61poblacion']='Poblaci&oacute;n';
$ETI['saiu61formaentrega']='Forma entrega';

$ERR['saiu61consec']='Necesita el dato '.$ETI['saiu61consec'];
$ERR['saiu61id']='Necesita el dato '.$ETI['saiu61id'];
$ERR['saiu61orden']='Necesita el dato '.$ETI['saiu61orden'];
$ERR['saiu61vigente']='Necesita el dato '.$ETI['saiu61vigente'];
$ERR['saiu61titulo']='Necesita el dato '.$ETI['saiu61titulo'];
$ERR['saiu61idunidad']='Necesita el dato '.$ETI['saiu61idunidad'];
$ERR['saiu61fecha']='Necesita el dato '.$ETI['saiu61fecha'];
$ERR['saiu61fechapublica']='Necesita el dato '.$ETI['saiu61fechapublica'];
$ERR['saiu61fechadespublica']='Necesita el dato '.$ETI['saiu61fechadespublica'];
$ERR['saiu61cuerpo']='Necesita el dato '.$ETI['saiu61cuerpo'];
$ERR['saiu61poblacion']='Necesita el dato '.$ETI['saiu61poblacion'];
$ERR['saiu61formaentrega']='Necesita el dato '.$ETI['saiu61formaentrega'];
$asaiu61vigente=array('', '');
$isaiu61vigente=0;
$asaiu61poblacion=array('Listado', '', '', '', 'Tutores', 'Estudiantes');
$isaiu61poblacion=5;
$asaiu61formaentrega=array('Campus', 'Correo electr&oacute;nico', 'Campus + Correo');
$isaiu61formaentrega=2;
?>