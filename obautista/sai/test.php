<?php
require './app.php';
require $APP->rutacomun.'libs/clsdbadmin.php';
require $APP->rutacomun.'unad_librerias.php';
require $APP->rutacomun.'librest.php';
$sUrl='https://sivisae.unad.edu.co/sivisae/pages/sivisae_consulta_atenciones.php?doc=1013626625';
$aResultado=array();
$objRest=new UNAD_Rest(3);
$aRes=$objRest->leer($sUrl);
print_r($aRes);
?>