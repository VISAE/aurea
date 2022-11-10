<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2020 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Version 2.25.0 martes, 17 de marzo de 2020
--- Modelo Version 2.25.0 domingo, 29 de marzo de 2020
*/
$ETI['titulo_2211']='Plan de estudios';
$ETI['titulo_2211curso']='Plan de estudios en los que se encuentra incluido el curso';
$ETI['sigla_2211']='Plan de estudios';
$ETI['parametros_2211']='Parametros para consultar cursos incluidos en el Plan de estudios';
$ETI['titulo_2247']='Plan de estudios';
$ETI['sigla_2247']='Plan de estudios';
$ETI['bt_mini_guardar_2211']='Salvar Plan de estudios';
$ETI['bt_mini_limpiar_2211']='Limpar Plan de estudios';
$ETI['bt_mini_eliminar_2211']='Remover Plan de estudios';
$ETI['si']='Sim';
$ETI['no']='N&atilde;o';
$ETI['lnk_cargar']='Editar';
$ETI['msg_plano']='Importaci&oacute;n masiva del plan de estudios.';
$ETI['msg_infoplano']='Esta carga masiva es aplicable a los cursos que aplican a todos los periodos academicos, los cursos que apliquen solo a un periodo especifico no se pueden subir masivamente.<br>
Puede descargar la plantilla de ejemplo haciendo clic en el boton con el logo de MsExcel en la parte derecha, el archivo debe contener las siguientes columnas:<br>
1) Codigo del curso a ofertar.<br>
2) Codigo del Tipo de registro (0 B&aacute;sico, 1 Especifico, 2 Electivo, 3 requisito de grado).<br>
3) Codigo de obligatoriedad (0 No, 1 Si, 2 Si Forzar en estudiante nuevo).<br>
4) Nivel en el aplica el curso (Semestre).<br>
5) Codigo de curso Prerequisto (En blanco si no hay prerrequisito).<br>
6) Se oferta en periodo corto (S para Si, N para no).<br>';
$ETI['core11idversionprograma']='Version programa';
$ETI['core11idcurso']='Curso';
$ETI['core11idversionprograma']='Versionprograma';
$ETI['core11id']='Id';
$ETI['core11idlineaprof']='Lineaprof';
$ETI['core11idprograma']='Programa';
$ETI['core11tiporegistro']='Tipo';
$ETI['core11obligarorio']='Obligatorio';
$ETI['core11numcreditos']='N&deg; de cr&eacute;ditos';
$ETI['core11nivelaplica']='Nivel aplica';
$ETI['core11idprerequisito']='Prerrequisito';
$ETI['core11idcorrequisito']='Correquisito';
$ETI['core11fechaingresa']='Fecha de ingreso';
$ETI['core11areaconocimiento']='Campo de formaci&oacute;n';
$ETI['core11componeteconoce']='Componente de conocimiento';
$ETI['core11ofertaperacacorto']='Se oferta en per&iacute;odos cortos';
$ETI['core11homologable']='Homologable';
$ETI['core11habilitable']='Habilitable';
$ETI['core11porsuficiencia']='Por suficiencia';
$ETI['core11notaaprobatoria']='Nota aprobatoria';
$ETI['core11idorigencontprog']='Origencontprog';
$ETI['core11idarchivocontprog']='Contenido anal&iacute;tico';
$ETI['core11idcontenido']='Contenido anal&iacute;tico';
$ETI['core11idprerequisito2']='Prerrequisito 2';
$ETI['core11idprerequisito3']='Prerrequisito 3';
$ETI['core11fechaaprobado']='Fecha aprobado';
$ETI['core11idaprueba']='Aprobado por';
$ETI['core11idequivalente']='Equivalente';
$ETI['core11idcursoreemp1']='En reemplazo de';
$ETI['core11idcursoreemp2']='y en reemplazo de';
$ETI['core11tieneequivalente']='Tiene equivalente';
$ETI['core11justificamod']='Justificaci&oacute;n para la modificaci&oacute;n';

$ERR['core11idversionprograma']='Necesita el dato '.$ETI['core11idversionprograma'];
$ETI['core10estado']='Estado';

$ETI['unad40consupletorio']='Tiene supletorio';
$ETI['unad40habilitable']='Habilitable';
$ETI['unad40homologable']='Homologable';
$ETI['unad40porsuficiencia']='Por suficiencia';

$ERR['core11idcurso']='&Eacute; necess&aacute;rio o dado '.$ETI['core11idcurso'];
$ERR['core11idversionprograma']='&Eacute; necess&aacute;rio o dado '.$ETI['core11idversionprograma'];
$ERR['core11id']='&Eacute; necess&aacute;rio o dado '.$ETI['core11id'];
$ERR['core11idlineaprof']='&Eacute; necess&aacute;rio o dado '.$ETI['core11idlineaprof'];
$ERR['core11idprograma']='&Eacute; necess&aacute;rio o dado '.$ETI['core11idprograma'];
$ERR['core11tiporegistro']='Necesita el dato '.$ETI['core11tiporegistro'];
$ERR['core11obligarorio']='Necesita el dato '.$ETI['core11obligarorio'];
$ERR['core11numcreditos']='Necesita el dato '.$ETI['core11numcreditos'];
$ERR['core11nivelaplica']='Necesita el dato '.$ETI['core11nivelaplica'];
$ERR['core11idprerequisito']='Necesita el dato '.$ETI['core11idprerequisito'];
$ERR['core11idcorrequisito']='Necesita el dato '.$ETI['core11idcorrequisito'];
$ERR['core11fechaingresa']='Necesita el dato '.$ETI['core11fechaingresa'];
$ERR['core11areaconocimiento']='Necesita el dato '.$ETI['core11areaconocimiento'];
$ERR['core11componeteconoce']='Necesita el dato '.$ETI['core11componeteconoce'];
$ERR['core11ofertaperacacorto']='Necesita el dato '.$ETI['core11ofertaperacacorto'];
$ERR['core11homologable']='Necesita el dato '.$ETI['core11homologable'];
$ERR['core11habilitable']='Necesita el dato '.$ETI['core11habilitable'];
$ERR['core11porsuficiencia']='Necesita el dato '.$ETI['core11porsuficiencia'];
$ERR['core11notaaprobatoria']='Necesita el dato '.$ETI['core11notaaprobatoria'];
$ERR['core11idorigencontprog']='Necesita el dato '.$ETI['core11idorigencontprog'];
$ERR['core11idarchivocontprog']='Necesita el dato '.$ETI['core11idarchivocontprog'];
$ERR['core11idcontenido']='Necesita el dato '.$ETI['core11idcontenido'];
$ERR['core11idprerequisito2']='Necesita el dato '.$ETI['core11idprerequisito2'];
$ERR['core11idprerequisito3']='Necesita el dato '.$ETI['core11idprerequisito3'];
$ERR['core11fechaaprobado']='Necesita el dato '.$ETI['core11fechaaprobado'];
$ERR['core11idaprueba']='Necesita el dato '.$ETI['core11idaprueba'];
$ERR['core11idequivalente']='Necesita el dato '.$ETI['core11idequivalente'];
$ERR['core11idcursoreemp1']='Necesita el dato '.$ETI['core11idcursoreemp1'];
$ERR['core11idcursoreemp2']='Necesita el dato '.$ETI['core11idcursoreemp2'];
$ERR['core11tieneequivalente']='Necesita el dato '.$ETI['core11tieneequivalente'];
$ERR['core11justificamod']='Necesita el dato '.$ETI['core11justificamod'];

$ETI['msg_resumencomp']='Resumen por componente';
$ETI['msg_creditos']='Creditos';
$ETI['msg_nivel']='Nivel';
$ETI['msg_nota']='Nota';
$ETI['msg_ayudape']='Puede consultar la capacitaci&oacute;n sobre planes de estudio en el <a href="http://conferencia2.unad.edu.co/p14cp3rpguo4/" target="_blank" class="lnkresalte">este enlace</a>';
$ETI['msg_tipologia']='Tipolog&iacute;a';

$ETI['msg_bcodigo2211']='Codigo curso';
$ETI['msg_bnombre2211']='Nombre curso';

$ETI['msg_supletorio']='Sup';
$ETI['msg_habilitable']='Hab';
$ETI['msg_homologable']='Hom';
$ETI['msg_suficiencia']='Suf';
?>