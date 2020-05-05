<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2014 - 2016 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Version 1.0.1 viernes, 25 de abril de 2014
--- Modelo Version 1.0.3 martes, 20 de mayo de 2014
--- Modelo Version 1.2.1 jueves, 17 de julio de 2014
--- Modelo Version 1.2.14 martes, 30 de diciembre de 2014
--- Modelo Version 2.7.2 jueves, 16 de abril de 2015
--- Modelo Version 2.9.1 miércoles, 29 de julio de 2015
*/
$ETI['app_nombre']='APP';
$ETI['grupo_nombre']='Grupo';
$ETI['titulo']='Cursos';
$ETI['titulo_sector2']='Cursos';
$ETI['titulo_sector93']='Cambio de consecutivo';
$ETI['titulo_140']='Cursos';
$ETI['titulo_140comp_existe']='Cursos del componente';
$ETI['titulo_140comp_sin']='Cursos sin componente';
$ETI['sigla_140']='Cursos';
$ETI['titulo_2241']='Contenidos anal&iacute;ticos';
$ETI['lnk_cargar']='Editar';
$ETI['unad40fuente']='Fuente';
$ETI['unad40consec']='Code';
$ETI['msg_unad40consec']='Consecutivo actual';
$ETI['msg_unad40consec_nuevo']='Nuevo consecutivo';
$ETI['unad40id']='Ref :';
$ETI['unad40titulo']='Titulo';
$ETI['unad40nombre']='Nombre';
$ETI['unad40idagenda']='Agenda';
$ETI['unad40diainical']='Diainical';
$ETI['unad40numestudiantes']='Numestudiantes';
$ETI['unad40idnav']='Nav';
$ETI['unad40tipocurso']='Tipocurso';
$ETI['unad40tipostandard']='Tipostandard';
$ETI['unad40nivelformacion']='Nivelformacion';
$ETI['unad40numcreditos']='N&deg; creditos';
$ETI['unad40idevaluacion']='Evaluaci&oacute;n';
$ETI['unad40idcursoncontents']='id en Ncontents';
$ETI['unad40idescuela']='Escuela';
$ETI['unad40idprograma']='Programa';
$ETI['unad40numestaula1']='N&deg; estudiantes aula 1';
$ETI['unad40idrubricacertifica']='Rubricacertifica';
$ETI['unad40incluyelaboratorio']='Incluyelaboratorio';
$ETI['unad40codigoflorida']='Codigoflorida';
$ETI['unad40incluyesalida']='Incluye salidas';
$ETI['unad40idtablero']='Tablero';
$ETI['unad40peracaultacredita']='Peracaultacredita';
$ETI['unad40areaconocimiento']='Areaconocimiento';
$ETI['unad40componenteconoce']='Componenteconoce';
$ETI['unad40unidadprod']='Unidadprod';
$ETI['unad40ofertaperacacorto']='Ofertaperacacorto';
$ETI['unad40homologable']='Homologable';
$ETI['unad40habilitable']='Habilitable';
$ETI['unad40porsuficiencia']='Porsuficiencia';
$ETI['unad40modocalifica']='Modocalifica';
$ETI['unad40incluyesimulador']='Con simulador';
$ETI['unad40idioma']='Idioma';

$ERR['unad40fuente']='Necesita el dato '.$ETI['unad40fuente'];
$ERR['unad40consec']='&Eacute; necess&aacute;rio o dado Consec';
$ERR['unad40id']='&Eacute; necess&aacute;rio o dado Id';
$ERR['unad40titulo']='Necesita el dato '.$ETI['unad40titulo'];
$ERR['unad40titulo_existe']='El titulo que intenta usar ya existe, el titulo debe ser &uacute;nico para el curso.';
$ERR['unad40nombre']='&Eacute; necess&aacute;rio o dado Nombre';
$ERR['unad40idagenda']='&Eacute; necess&aacute;rio o dado Agenda';
$ERR['unad40diainical']='&Eacute; necess&aacute;rio o dado Diainical';
$ERR['unad40numestudiantes']='&Eacute; necess&aacute;rio o dado Numestudiantes';
$ERR['unad40idnav']='&Eacute; necess&aacute;rio o dado Nav';
$ERR['unad40tipocurso']='&Eacute; necess&aacute;rio o dado Tipocurso';
$ERR['unad40tipostandard']='&Eacute; necess&aacute;rio o dado Tipostandard';
$ERR['unad40nivelformacion']='&Eacute; necess&aacute;rio o dado Nivelformacion';
$ERR['unad40numcreditos']='&Eacute; necess&aacute;rio o dado Numcreditos';
$ERR['unad40idevaluacion']='&Eacute; necess&aacute;rio o dado Evaluacion';
$ERR['unad40idcursoncontents']='&Eacute; necess&aacute;rio o dado Cursoncontents';
$ERR['unad40idescuela']='&Eacute; necess&aacute;rio o dado Escuela';
$ERR['unad40idprograma']='&Eacute; necess&aacute;rio o dado Programa';
$ERR['unad40numestaula1']='&Eacute; necess&aacute;rio o dado Numestaula1';
$ERR['unad40idrubricacertifica']='&Eacute; necess&aacute;rio o dado Rubricacertifica';
$ERR['unad40incluyelaboratorio']='Necesita el dato Incluyelaboratorio';
$ERR['unad40codigoflorida']='Necesita el dato Codigoflorida';
$ERR['unad40incluyesalida']='Necesita el dato Incluyesalida';
$ERR['unad40idtablero']='Necesita el dato '.$ETI['unad40idtablero'];
$ERR['unad40peracaultacredita']='Necesita el dato '.$ETI['unad40peracaultacredita'];
$ERR['unad40areaconocimiento']='Necesita el dato '.$ETI['unad40areaconocimiento'];
$ERR['unad40componenteconoce']='Necesita el dato '.$ETI['unad40componenteconoce'];
$ERR['unad40unidadprod']='Necesita el dato '.$ETI['unad40unidadprod'];
$ERR['unad40ofertaperacacorto']='Necesita el dato '.$ETI['unad40ofertaperacacorto'];
$ERR['unad40homologable']='Necesita el dato '.$ETI['unad40homologable'];
$ERR['unad40habilitable']='Necesita el dato '.$ETI['unad40habilitable'];
$ERR['unad40porsuficiencia']='Necesita el dato '.$ETI['unad40porsuficiencia'];
$ERR['unad40modocalifica']='Necesita el dato '.$ETI['unad40modocalifica'];
$ERR['unad40incluyesimulador']='Necesita el dato '.$ETI['unad40incluyesimulador'];
$ERR['unad40idioma']='Necesita el dato '.$ETI['unad40idioma'];
$ETI['unad40idnavalista']='Navalista';
$ETI['unad40espacioalista']='Espacioalista';
$ETI['unad40idnavalistainter']='Navalistainter';
$ETI['unad40espacioalistainter']='Espacioalistainter';
$ERR['unad40idnavalista']='Necesita el dato Navalista';
$ERR['unad40espacioalista']='Necesita el dato Espacioalista';
$ERR['unad40idnavalistainter']='Necesita el dato Navalistainter';
$ERR['unad40espacioalistainter']='Necesita el dato Espacioalistainter';

$ETI['msg_actualizar']='Atualizar';

$ETI['bloque_importacarga']='Importar carga';
$ETI['msg_aplicar']='Aplicar a:';
$ETI['msg_subir']='Importar';
$ETI['msg_descargar']='Descargar';

$ETI['msg_add']='+ Agregar';
$ETI['msg_rem']='Remover -';

$ETI['msg_responsable']='Programa responsable';
$ETI['msg_curso']='Curso';

$ETI['msg_oferta']='Oferta y alistamiento';
?>