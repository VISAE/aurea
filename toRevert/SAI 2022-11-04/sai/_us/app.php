<?php
unset($APP);
$APP=new stdclass();
$APP->idsistema=24;
$APP->tipo_doc='CC';
$APP->tiempolimite=60;

//Parametros de base de datos
$APP->dbhost='172.21.1.32';
$APP->dbpuerto='30001';
$APP->dbname='aurea_us';
$APP->dbuser='usuclassroom';
$APP->dbpass='Qsefthuko.2021';
$APP->rutacomun='../ulib/';
$APP->rutaconfig='../';

//Parametros para el ISys
$APP->dbhostth='unad-us2';
$APP->dbpuertoth='5432';
$APP->dbnameth='unadflorida';
$APP->dbuserth='usuaureaus';
$APP->dbpassth='usu4ur3aUS+';
$APP->dbmodeloth='P';

//Parametros para el tablero.
$APP->dbhostcampus='localhost';
$APP->dbusercampus='root';
$APP->dbpasscampus='';
$APP->dbnamecampus='moodle28';
$APP->dbpuertocampus='';

//Parametros para mostrar los cursos en que esta matriculado.
$APP->tablero_dbhost='192.168.1.70';
$APP->tablero_dbuser='tablero';
$APP->tablero_dbpass='tablerocalve';
$APP->tablero_dbname='tablero';
$APP->tablero_tablabase='edu_courses2013';

$APP->urltablero='miscursos.php';

$APP->utf8=0;
$APP->reducido=0;
$APP->entidad=1;
?>