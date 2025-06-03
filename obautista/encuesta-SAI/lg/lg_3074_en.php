<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2025 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Model 3.0.15 miércoles, 14 de mayo de 2025
*/
$ETI['app_nombre'] = 'Mis Cursos Virtuales';
$ETI['grupo_nombre'] = 'Encuestas';
$ETI['titulo'] = 'Encuestas de satisfacción';
$ETI['titulo_sector2'] = 'Encuesta de satisfacci&oacute;n';
$ETI['titulo_3074'] = 'Encuesta de satisfacci&oacute;n';
$ETI['sigla_3074'] = 'Encuesta de satisfacci&oacute;n';
$ETI['lnk_cargar'] = 'Editar';
$ETI['saiu74codmodulo'] = 'C&oacute;digo del m&oacute;dulo';
$ETI['saiu74idreg'] = 'Id del registro de atenci&oacute;n';
$ETI['saiu74id'] = 'Ref :';
$ETI['saiu74agno'] = 'A&ntilde;o';
$ETI['saiu74acepta'] = 'Acepta responder encuesta';
$ETI['saiu74fecharespuesta'] = 'Fecha de respuesta';
$ETI['saiu74preg1'] = '¿Califique la amabilidad y empat&iacute;a por parte del gestor del servicio&quest;';
$ETI['saiu74preg2'] = '¿El tiempo de respuesta a su solicitud fue el adecuado&quest;';
$ETI['saiu74preg3'] = '¿El lenguaje utilizado en la respuesta fue claro&quest;';
$ETI['saiu74preg4'] = '¿La respuesta brindada permiti&oacute; atender su necesidad&quest;';
$ETI['saiu74preg5'] = 'Califique la apropiaci&oacute;n y conocimiento de la informaci&oacute;n por parte del gestor del servicio';
$ETI['saiu74preg6'] = '¿Como califica la accesibilidad, utilidad e innovaci&oacute;n del Sistema de Atenci&oacute;n Integral&quest;';
$ETI['saiu74comentario'] = 'Observaciones y sugerencias';
$ETI['msg_comentario'] = 'Ingrese aqu&iacute; sus ' . $ETI['saiu74comentario'];
$ETI['msg_vuelveindex'] = 'Para volver al tablero haga clic en <a href="index.php">este enlace</a>';
$ETI['msg_termina_encuesta'] = 'Gracias por su tiempo<br>&iquest;Confirma que ha respondido todas las preguntas?';
$ETI['msg_responder'] = 'Responder';
$ETI['msg_registraok'] = '¡Gracias por su participación! Sus respuestas se han guardado correctamente.';
$ETI['msg_justifica'] = 'Con el objetivo de mejorar continuamente la calidad de nuestros servicios y responder de manera m&aacute;s efectiva a las necesidades de nuestros usuarios, se ha diseñado esta encuesta de satisfacci&oacute;n. La informaci&oacute;n recopilada nos permitir&aacute; identificar fortalezas, &aacute;reas de mejora y oportunidades para optimizar la experiencia de quienes interact&uacute;an con nuestra organizaci&oacute;n. Su opini&oacute;n es fundamental para avanzar hacia un servicio m&aacute;s eficiente, cercano y de calidad.';
$ETI['msg_noencuesta'] = 'No se registran encuestas por diligenciar, gracias por su visita, para volver por favor haga clic en';
$ETI['msg_noencuesta_link'] = 'este enlace';
$ETI['mail_enc']='Apreciado Usuario, para la universidad Nacional Abierta y a Distancia - UNAD es muy importante su opini&oacute;n respecto a la calidad de nuestro servicio.<br>Agradecemos dar respuesta a la siguiente encuesta.';

$ERR['saiu74codmodulo'] = 'The ' . $ETI['saiu74codmodulo'] . ' data is necessary';
$ERR['saiu74idreg'] = 'The ' . $ETI['saiu74idreg'] . ' data is necessary';
$ERR['saiu74id'] = 'The ' . $ETI['saiu74id'] . ' data is necessary';
$ERR['saiu74agno'] = 'The ' . $ETI['saiu74agno'] . ' data is necessary';
$ERR['saiu74acepta'] = 'The ' . $ETI['saiu74acepta'] . ' data is necessary';
$ERR['saiu74fecharespuesta'] = $ETI['saiu74fecharespuesta'] . ' incorrect';
$ERR['saiu74preg1'] = 'The ' . $ETI['saiu74preg1'] . ' data is necessary';
$ERR['saiu74preg2'] = 'The ' . $ETI['saiu74preg2'] . ' data is necessary';
$ERR['saiu74preg3'] = 'The ' . $ETI['saiu74preg3'] . ' data is necessary';
$ERR['saiu74preg4'] = 'The ' . $ETI['saiu74preg4'] . ' data is necessary';
$ERR['saiu74preg5'] = 'The ' . $ETI['saiu74preg5'] . ' data is necessary';
$ERR['saiu74preg6'] = 'The ' . $ETI['saiu74preg6'] . ' data is necessary';
$ERR['saiu74comentario'] = 'The ' . $ETI['saiu74comentario'] . ' data is necessary';
$ERR['msg_contenedor'] = 'No ha sido posible acceder al contenedor de datos';
$ERR['msg_registrar'] = 'Error al intentar registrar la encuesta.';
$ERR['msg_yaregistrada'] = 'La encuesta ya se encuentra registrada.';

$aValores = array(1,2,3,4,5);
$iValores = 5;
$aEtiquetas = array('Deficiente (1)', 'Malo (2)', 'Aceptable (3)', 'Bueno (4)', 'Excelente (5)');
$iEtiquetas = 5;