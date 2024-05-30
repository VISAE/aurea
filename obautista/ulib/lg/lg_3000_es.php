<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2020 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Version 2.25.3 sábado, 18 de julio de 2020
--- Modelo Version 2.25.10b lunes, 25 de enero de 2021
*/
$ETI['titulo_3000']='Historial de solicitudes';
$ETI['titulo_3028']='Mesa de ayuda';
$ETI['titulo_sai']='Sistema de Atenci&oacute;n Integral';
$ETI['titulo_saiacad']='Gesti&oacute;n Acad&eacute;mica - SAI';
$ETI['titulo_saifinan']='Gesti&oacute;n Financiera - SAI';
$ETI['titulo_saitram']='Tramites - SAI';
$ETI['titulo_saiinfo']='Consulta de informaci&oacute;n - SAI';
$ETI['titulo_sector2']='Aplazamiento de periodo';
$ETI['titulo_sector3']='Cancelaci&oacute;n de periodo';
$ETI['titulo_sector4']='Aplazamiento de curso';
$ETI['titulo_sector5']='Cancelaci&oacute;n de curso';
$ETI['titulo_sector6']='Cambio de curso';
$ETI['titulo_sector7']='Aplazamiento de curso extemporaneo';
$ETI['titulo_sector8']='Desistimiento de solicitud de aplazamiento';
$ETI['titulo_cambiocentro']='Cambio de centro';
$ETI['titulo_3005_pqrs']='Historial de PQRS';

$ETI['sigla_3000']='Historial de solicitudes';
$ETI['bt_mini_guardar_3000']='Guardar Historial de solicitudes';
$ETI['bt_mini_limpiar_3000']='Limpiar Historial de solicitudes';
$ETI['bt_mini_eliminar_3000']='Eliminar Historial de solicitudes';
$ETI['si']='Si';
$ETI['no']='No';
$ETI['lnk_cargar']='Editar';
$ETI['saiu00idtercero']='Tercero';
$ETI['saiu00idmodulo']='Modulo';
$ETI['saiu00fecha']='Fecha';
$ETI['saiu00idtipo']='Tipo';
$ETI['saiu00idtema']='Tema';
$ETI['saiu00estado']='Estado';
$ETI['saiu00detalle']='Detalle';
$ETI['corf06tiponov']='Tiponov';
$ETI['corf06consec']='Consecutivo';
$ETI['msg_corf06consec']='Consecutivo actual';
$ETI['msg_corf06consec_nuevo']='Nuevo consecutivo';
$ETI['corf06id']='Ref :';
$ETI['corf06estado']='Estado';
$ETI['corf06idestudiante']='Estudiante';
$ETI['corf06idescuela']='Escuela';
$ETI['corf06idprograma']='Programa';
$ETI['corf06idzona']='Zona';
$ETI['corf06idcentro']='Centro';
$ETI['corf06idzonadest']='Zona destino';
$ETI['corf06idcentrodest']='Centro destino';
$ETI['corf08nota']='Motivo';
$ETI['corf08nota_cancela']='Nos gustaria saber el motivo por el cual hace esta cancelaci&oacute;n';
$ETI['corf08nota_aplaza']='Nos gustaria saber el motivo por el cual hace este aplazamiento';
$ETI['corf08nota_det']='Nos gustaria saber el motivo por el cual cambia de centro';
$ETI['corf08nota_aplazatarde']='Por favor ingrese el motivo por el cual solicita el aplazamiento';
$ETI['corf08nota_desiste']='Por favor ingrese el motivo por el cual desiste de su solicitud';

$ERR['saiu00idtercero']='Necesita el dato '.$ETI['saiu00idtercero'];
$ERR['saiu00idmodulo']='Necesita el dato '.$ETI['saiu00idmodulo'];
$ERR['saiu00fecha']='Necesita el dato '.$ETI['saiu00fecha'];
$ERR['saiu00idtipo']='Necesita el dato '.$ETI['saiu00idtipo'];
$ERR['saiu00idtema']='Necesita el dato '.$ETI['saiu00idtema'];
$ERR['saiu00estado']='Necesita el dato '.$ETI['saiu00estado'];
$ERR['saiu00detalle']='Necesita el dato '.$ETI['saiu00detalle'];

$ETI['msg_docente']='Acompa&ntilde;amiento Docente';
$ETI['msg_tramites']='Tramites';
$ETI['msg_solicitudes']='Solicitudes';
$ETI['msg_matricula']='Gesti&oacute;n acad&eacute;mica';

$ETI['msg_programa']='Programa';
$ETI['msg_periodo']='Periodo';
$ETI['msg_curso']='Curso';
$ETI['msg_fechanovedades']='La fecha tope de novedades venci&oacute; el ';

$ETI['msg_asignacion_tercero']='Resumen de solicitudes a cargo en el SAI';
$ETI['msg_modulo']='Modulo';
$ETI['msg_asignadas']='Asignadas';
$ETI['msg_vencidas']='Vencidas';
$ETI['msg_supervisor']='Para supervisar';

$AYU['msg_aplazasem']='<b>Tenga en cuenta que:</b> Se aplaza curso por solicitud del estudiante dentro de las fechas establecidas para las novedades. Se informa que debe legalizar el aplazamiento de per&iacute;odo a m&aacute;s tardar en la vigencia subsiguiente (m&aacute;ximo 6 meses). Al momento de legalizar su aplazamiento del per&iacute;odo, s&oacute;lo debe pagar el seguro estudiantil si se encuentra dentro del mismo año.<br>
<b>NOTA</b>: si el aplazamiento ya sea de curso o de per&iacute;odo aplica cambio de año, por ejemplo un aplazamiento en 2020-2 y se legaliza en 2021-1, debe pagar tambi&eacute;n el cambio de año fiscal (diferencia del costo del cr&eacute;dito).';
$AYU['msg_cancelasem']='<b>Tenga en cuenta que:</b> Se cancela periodo por solicitud del estudiante dentro de las fechas establecidas para las novedades. <br>
Se informa que como se establece en el art&iacute;culo 30 del Reglamento General Estudiantil, no da lugar a devoluci&oacute;n de los derechos pecuniarios.';
$AYU['msg_aplazacurso']='<b>Tenga en cuenta que:</b> Se aplaza curso por solicitud del estudiante dentro de las fechas establecidas para las novedades. <br>
Se informa que debe legalizar el aplazamiento de curso a m&aacute;s tardar en la vigencia subsiguiente (m&aacute;ximo 6 meses). <br>
Debe realizar su matr&iacute;cula y en la fecha de novedades del per&iacute;odo solicitar la adici&oacute;n del curso o la aplicaci&oacute;n de saldo a favor por el costo de los cr&eacute;ditos aplazados.';
$AYU['msg_cancelacurso']='<b>Tenga en cuenta que:</b> Se cancela curso por solicitud del estudiante dentro de las fechas establecidas para las novedades. 
<br>Se informa que como se establece en el art&iacute;culo 30 del Reglamento General Estudiantil, <b>no da lugar a devoluci&oacute;n de los derechos pecuniarios</b>.';
$AYU['msg_cambiacurso']='Aqu&iacute; podr&aacute; reemplazar cursos que ha matriculado siempre y cuando conserve la cantidad de cursos que han presentado.<br>
Para ejecutar este proceso su plan de estudio individual debe estar cargado.';
$AYU['msg_aplazacursotarde']='<b>Tenga en cuenta que:</b> La solicitud de aplazamiento de curso extemporanea ocurre cuando se presenten motivos de fuerza mayor, Seg&uacute;n lo establecido en el c&oacute;digo civil colombiano en su artículo 64, y ley 95 de 1980 (Congreso de la Rep&uacute;blica de Colombia).<br>
La escuela en la que esta registrado ser&aacute; la encargada de autorizar o no el aplazamiento. 
<br>Se informa que debe legalizar el aplazamiento de curso a m&aacute;s tardar en la vigencia subsiguiente (m&aacute;ximo 6 meses). <br>
Debe realizar su matr&iacute;cula y en la fecha de novedades del per&iacute;odo solicitar la adici&oacute;n del curso o la aplicaci&oacute;n de saldo a favor por el costo de los cr&eacute;ditos aplazados.<br>
<br>
<b>Soportes que correspondan según sea el evento</b>, por ejemplo:<br>
Fallecimiento de un familiar con vínculo de consanguinidad en primer grado anexar acta de defunción y registro civil de nacimiento y/o matrimonio del solicitante.<br> 
Situación de salud adjuntar diagnóstico e incapacidad médica expedida por la EPS.<br>
Situación de un familiar con primer grado de consanguinidad';

$AYU['msg_aplazatiempos']='Los tiempos para aplazamientos extemporaneo estan definidos en el <a href="https://sgeneral.unad.edu.co/images/documentos/consejoAcademico/acuerdos/2020/COAC_ACUE_082_20200904.pdf" target="_blank" class="lnkresalte">Acuerdo n&uacute;mero 082 del 04 de septiembre de 2020</a> Art&iacute;culo 2 Literal B ';
$AYU['msg_bloqueomatricula']='En este momento no es posible tramitar la solicitud dado que tiene matricula activa en los siguientes cursos:';
$AYU['msg_bloqueomatricula_2']='Estos cursos tienen componente pr&aacute;tico, debe esperar a que estos cursos concluyan para poder proceder a solicitar el cambio de centro.';
$AYU['msg_cambiocentro']='Una vez cambie de centro su documentaci&oacute;n y los procesos administrativos ser&aacute;n trasladados al lugar de su elecci&oacute;n.';
$AYU['msg_desiste']='Una vez usted desista de su proceso su solicitud se comprender&aacute; como resuelta, y  NO procede volver a solicitarla.<br>Le recomendamos que antes de desisitir se comunique con su lider zonal de escuela para recibir orientaciones al respecto.';
$AYU['msg_enproceso']='Su solicitud esta siendo procesada, una vez sea resuelta le ser&aacute; notificada la respuesta.';

$asaiu11=array('Solicitado', 'Asignado', 'En tramite', 'Para reasignar', 'En pausa', '', '', 'Resuelto', 'Solicitud abandonada', 'Cancelado por el usuario');
$isaiu11=9;

$ETI['mail_asig_titulo']='Tiene asignado un caso de atención a solicitud';
$ETI['mail_enc_titulo']='Opini&oacute;n sobre la atenci&oacute;n';
$ETI['mail_enc_parte1']='Agradecemos su opini&oacute;n respecto a la atenci&oacute;n recibida el d&iacute;a ';
$ETI['mail_enc_parte2']=' y nos gustar&iacute;a su opini&oacute;n respecto a la calidad de nuestro servicio.';
$ETI['saiu18']='Telef&oacute;nica';
$ETI['saiu19']='Sesi&oacute;n de Chat';
$ETI['saiu20']='Correo Eletr&oacute;nico';
$ETI['saiu21']='Presencial';
$ETI['titulo_encuesta'] = 'Encuesta';
$ETI['bt_enviar'] = 'Enviar';
$ETI['fecha'] = 'Fecha de encuesta';
$ETI['texto_encuesta'] = 'Califique de 1 a 5 los siguientes aspectos, en el que 5 es excelente y 1 deficiente: ';
$ETI['texto_solicitante'] = 'Informaci&oacute;n del solicitante:';
$ETI['saiuid']='Id M&oacute;dulo';
$ETI['saiu00id']='Id';
$ETI['saiu00doc']='Documento';
$ETI['saiu00razonsocial']='Nombre';
$ETI['saiu00codigo']='C&oacute;digo';
$ETI['saiu00idzona']='Zona';
$ETI['saiu00idcentro']='Centro';
$ETI['saiu00idescuela']='Escuela';
$ETI['saiu00idprograma']='Programa';
$ETI['saiu00cerrada']='Ya se ha dado respuesta a esta encuesta. Gracias.';
$ETI['saiu00gracias'] = 'Gracias por su respuesta';
$ETI['saiu00evalamabilidad'] = '¿Califique la amabilidad y empat&iacute;a por parte del gestor del servicio&quest;';
$ETI['saiu00evalamabmotivo'] = 'Observaciones y sugerencias';
$ETI['saiu00evalrapidez'] = '¿El tiempo de respuesta a su solicitud fue el adecuado&quest;';
$ETI['saiu00evalrapidmotivo'] = 'Observaciones y sugerencias';
$ETI['saiu00evalclaridad'] = '¿El lenguaje utilizado en la respuesta fue claro&quest;';
$ETI['saiu00evalcalridmotivo'] = 'Observaciones y sugerencias';
$ETI['saiu00evalresolvio'] = '¿La respuesta brindada permiti&oacute; atender su necesidad&quest;';
$ETI['saiu00evalsugerencias'] = 'Observaciones y sugerencias';
$ETI['saiu00evalconocimiento'] = 'Califique la apropiaci&oacute;n y conocimiento de la informaci&oacute;n por parte del gestor del servicio';
$ETI['saiu00evalconocmotivo'] = 'Observaciones y sugerencias';
$ETI['saiu00evalutilidad'] = '¿Como califica la accesibilidad, utilidad e innovaci&oacute;n del Sistema de Atenci&oacute;n Integral&quest;';
$ETI['saiu00evalutilmotivo'] = 'Observaciones y sugerencias';
$ETI['saiu00evalcontrtamotivo'] = 'Observaciones y sugerencias';
$ETI['valor5'] = 'Excelente';
$ETI['valor4'] = 'Bueno';
$ETI['valor3'] = 'Aceptable';
$ETI['valor2'] = 'Malo';
$ETI['valor1'] = 'Deficiente';
$ETI['bt_motivo'] = 'Comentarios';
$ETI['motivo'] = 'Sugerencias o comentarios';

$ERR['mail_valido']='No se ha definido un correo electr&oacute;nico v&aacute;lidado para notificar.';
$ERR['mail_asig_error']='Ha ocurrido un error intentando enviar la notificaci&oacute;n de la asignaci&oacute;n';
$ERR['mail_enc_error']='Ha ocurrido un error intentando enviar la encuesta de satisfacci&oacute;n';
$ERR['saiuid']='Necesita el dato '.$ETI['saiuid'];
$ERR['saiu00id']='Necesita el dato '.$ETI['saiu00id'];
$ERR['saiu00doc']='Necesita el dato '.$ETI['saiu00doc'];
$ERR['saiu00codigo']='Necesita el dato '.$ETI['saiu00codigo'];
$ERR['saiu00evalamabilidad']='Necesita el dato '.$ETI['saiu00evalamabilidad'];
$ERR['saiu00evalrapidez']='Necesita el dato '.$ETI['saiu00evalrapidez'];
$ERR['saiu00evalclaridad']='Necesita el dato '.$ETI['saiu00evalclaridad'];
$ERR['saiu00evalresolvio']='Necesita el dato '.$ETI['saiu00evalresolvio'];
$ERR['saiu00evalconocimiento']='Necesita el dato '.$ETI['saiu00evalconocimiento'];
$ERR['saiu00evalutilidad']='Necesita el dato '.$ETI['saiu00evalutilidad'];
$ERR['saui00numref']='C&oacute;digo incorrecto';
$ERR['contenedor']='No ha sido posible acceder al contenedor de datos';
$ERR['tabla']='Necesita el dato Referencia a BD';
$ERR['saiu00noexiste']='La encuesta no existe.&lt;br&gt;Verifique que el documento no contenga puntos, comas o espacios&lt;br&gt;verifique que el código de sea correcto.';

$ETI['saiucanal']='Canal de atenci&oacute;n';
$aCanal=array('','Atenci&oacute;n presencial', 'Registro de llamadas', 'Sesiones de chat', 'Registro de correos');
$iCanal=4;
?>