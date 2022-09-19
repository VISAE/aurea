<?php
if (file_exists('./err_control.php')){require './err_control.php';}
require './app.php';
require $APP->rutacomun.'unad_todas.php';
require $APP->rutacomun.'libs/clsdbadmin.php';
require $APP->rutacomun.'unad_librerias.php';
require $APP->rutacomun.'libdatos.php';

$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}

$bDebug = false;
$sError = '';

if (isset($_REQUEST['paso']) == 0) {
    $_REQUEST['paso'] = 0;
}

if ($_REQUEST['paso'] == 0) {
	if (isset($_GET['u'])!=0){
        //Esta recibiendo una peticion de recuperacion.
        $sURL=url_decode_simple($_GET['u']);
        if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Dato de llegada: '.$sURL.' <br>';}
        $aURL=explode('|', $sURL);
		if (count($aURL)<3){
			$sError = 'Codigo incorrecto.';
		} else {
			$iMes = $aURL[0];
			$idEncuesta = $aURL[1];
			$sError = 'Dato a consultar : ' . $iMes . ' - ' . $idEncuesta . '';
		}
    }
}
?>
<html>
<title>Plataforma Aurea, Universidad Abierta y a Distancia UNAD de Colombia</title>
<body>
<form id="frmedita" name="frmedita" method="post" action="" autocomplete="off">
<input id="bNoAutocompletar" name="bNoAutocompletar" type="password" value="" style="display:none;"/>
<input id="paso" name="paso" type="hidden" value="<?php echo $_REQUEST['paso']; ?>" />
</form>
<?php
echo $sError;
?>
</body>
</html>