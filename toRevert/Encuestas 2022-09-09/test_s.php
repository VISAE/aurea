<?php
require './app.php';
require $APP->rutacomun.'libs/clsdbadmin.php';
require $APP->rutacomun.'unad_librerias.php';
require $APP->rutacomun.'libdatos.php';
require $APP->rutacomun.'libaurea.php';
$err_level = error_reporting(E_ALL);
error_reporting($err_level);
ini_set("display_errors", 1);
$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
list($aure73codigo, $sError, $sDebug)=AUREA_IniciarEncuestaPublica(4, 1, 3018, 0, 0, $objDB);
if ($sError == '') {
    echo 'Se ha enviado el correo';
} else {
    echo $sError;
}
?>