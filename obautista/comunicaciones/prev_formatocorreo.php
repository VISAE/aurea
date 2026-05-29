<?php
require './app.php';
require $APP->rutacomun . 'unad_sesion.php';
require $APP->rutacomun . 'unad_todas.php';
require $APP->rutacomun . 'libs/clsdbadmin.php';
require $APP->rutacomun . 'unad_librerias.php';
$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
if ($APP->dbpuerto != '') {
	$objDB->dbPuerto = $APP->dbpuerto;
}
if (isset($_REQUEST['v3']) == 0) {
	$_REQUEST['v3'] = 0;
}
$_REQUEST['ve'] = numeros_validar($_REQUEST['v3']);
$sSalto = '
';
$sSQLcondi = 'masi10id=' . $_REQUEST['v3'] . '';
$sNomTabla1210 = 'masi10formato';
$sSQL = 'SELECT * FROM ' . $sNomTabla1210 . ' WHERE ' . $sSQLcondi;
$tabla = $objDB->ejecutasql($sSQL);
if ($objDB->nf($tabla) > 0) {
	$fila = $objDB->sf($tabla);
	/* 
		$_REQUEST['masi10encabezado'] = $fila['masi10encabezado'];
		$_REQUEST['masi10divcuerpo'] = $fila['masi10divcuerpo'];
		$_REQUEST['masi10divcodigocorreo'] = $fila['masi10divcodigocorreo'];
		$_REQUEST['masi10divcodigoconfirma'] = $fila['masi10divcodigoconfirma'];
		$_REQUEST['masi10divcodigorecupera'] = $fila['masi10divcodigorecupera'];
		$_REQUEST['masi10divfirma'] = $fila['masi10divfirma'];
		$_REQUEST['masi10piedepagina'] = $fila['masi10piedepagina'];
	*/
	echo $fila['masi10encabezado'];
	echo '';
	//echo '<hr>CUERPO:<hr>';
	echo '';
	$sMensaje = '<h1>Un titulo dentro del mensaje.</h1> Este es el mensaje que se esta enviando...<br>Y esto esta en una nueva linea. <p>Esto es un parrafo</p>';
	if (trim($fila['masi10divcuerpo']) == '') {
		$sCuerpto = $sMensaje;
	} else {
		$sCuerpo = str_replace('|@CUERPO@|', $sMensaje, $fila['masi10divcuerpo']);
	}
	echo '<!-- Cuerpo -->' . $sSalto . $sCuerpo . $sSalto . '<!-- Fin del Cuerpo -->' . $sSalto;
	$sMensaje = '123456789';
	if (trim($fila['masi10divcodigocorreo']) != '') {
		echo '<!-- DIV CODIGO CORREO -->' . $sSalto;
		$sCuerpo = str_replace('|@CODIGO@|', $sMensaje, $fila['masi10divcodigocorreo']);
		echo $sCuerpo;
	}
	if (trim($fila['masi10divcodigoconfirma']) != '') {
		echo '<!-- DIV CODIGO CONFIRMA -->' . $sSalto;
		$sCuerpo = str_replace('|@CODIGO@|', $sMensaje, $fila['masi10divcodigoconfirma']);
		echo $sCuerpo;
	}

	if (trim($fila['masi10divcodigorecupera']) != '') {
		echo '<!-- DIV CODIGO RECUPERA -->' . $sSalto;
		$sCuerpo = str_replace('|@CODIGO@|', $sMensaje, $fila['masi10divcodigorecupera']);
		echo $sCuerpo;
	}

	if (trim($fila['masi10divfirma']) != '') {
		echo '<!-- DIV FIRMA -->' . $sSalto;
		echo $fila['masi10divfirma'];
	}

	echo '<!-- Pie de pagina -->' . $sSalto;
	echo $fila['masi10piedepagina'];
} else {
	echo 'No encontrado';
}