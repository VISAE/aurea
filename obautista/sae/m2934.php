<?php
/*
--- © Omar Augusto Bautista - UNAD - 2026 ---
--- omar.bautista@unad.edu.co - http://www.unad.edu.co
--- Modelo Version 3.1.5 viernes, 27 de febrero de 2026
*/

/*
error_reporting(E_ALL);
ini_set("display_errors", 1);
*/
if (!file_exists('./app.php')) {
	echo '<b>Error N 1 de instalaci&oacute;n</b><br>No se ha establecido un archivo de configuraci&oacute;n, por favor comuniquese con el administrador del sistema.';
	die();
}
mb_internal_encoding('UTF-8');
require './app.php';
require $APP->rutacomun . 'libs/clsdbadmin.php';
require $APP->rutacomun . 'unad_librerias.php';
$bDebug = false;
if (isset($_GET['debug']) != 0) {
	if ($_GET['debug'] == 1) {
		$bDebug = true;
	}
}
if ($bDebug) {
	$base = $_GET['data'];
} else {
	$data = file_get_contents('php://input');
	$base = htmlspecialchars(trim($data));
}
$datos = explode('||',$base);
$bResponde = false;
$sError = '';
if (isset($datos[0]) == 0) {
	$datos[0] = '';
}
if (isset($datos[1]) == 0) {
	$datos[1] = '';
}
if (isset($datos[2]) == 0) {
	$datos[2] = '';
}
if (isset($datos[3]) == 0) {
	$datos[3] = '';
}
if (isset($datos[4]) == 0) {
	$datos[4] = '';
}
if ($bDebug) {
	echo 'Se ha recibido el proceso ' . $datos[0] . '<br>';
}
switch ($datos[0]) {
	case '2934': /* visa34convtipo */
	if (isset($datos[1]) == 0) {
		$datos[1] = '';
	}
	if (isset($datos[2]) == 0) {
		$datos[2] = '';
	}
	if (isset($datos[3]) == 0) {
		$datos[3] = '';
	}
	if (isset($datos[4]) == 0) {
		$datos[4] = '';
	}
	$idEntidad = numeros_validar($datos[1]);
	$sIdTercero = numeros_validar($datos[2]);
	$sIdMovil = numeros_validar($datos[3]);
	$sListaIds = cadena_Validar(trim($datos[4]));
	if ($bDebug) {echo 'Enviando visa34convtipo a ' . $idEntidad . ' ' . $sIdTercero . ' ' . $sIdMovil . ' ' . $sListaIds . '<br>';
	}
	/* Validamos que no esten intentando inyectar codigo en el usuario */
	if ($sListaIds != $datos[4]) {
		$sError = '-99';
	}
	$sCondicion = '';
	if (($sError == '') && ($sListaIds!=-99)) {
		$sIds = '';
		$aSubData=explode('|',$sListaIds);
		$iTotal=count($aSubData);
		for ($k = 1; $k <= $iTotal; $k++) {
			$sInfo = numeros_validar($aSubData[$k - 1]);
			if ($sInfo != '') {
				if ($sIds != '') {
					$sIds = $sIds . ', ';
				}
				$sIds = $sIds.$sInfo;
			}
		}
		if ($sIds == '') {
			$res = array();
			$res[] = -1;
			$res[] = '';
			$sError = '-1';
			if ($bDebug) {
				echo 'No se ha enviado informaci&oacute;n a sincronizar <br>';
			}
		} else {
			$sCondicion = ' WHERE visa34id IN (' . $sIds . ')';
		}
	}
	if ($sError == '') {
		$data1 = array();
		$bResponde = true;
		$iTotal = 0;
		$sIdsPadre = '-99';
		$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto != '') {
			$objDB->dbPuerto = $APP->dbpuerto;
		}
		$sSQL = 'SELECT visa34consec, visa34id, visa34nombre, visa34rolestudiante, visa34roladministrativo, visa34rolacademico, visa34rolaspirante, visa34rolegresado, visa34rolexterno, visa34grupotipologia, visa34activo, visa34aplicazona, visa34aplicacentro, visa34aplicaescuela, visa34aplicaprograma, bnombre FROM visa34convtipo' . $sCondicion;
		if ($bDebug) {
			echo 'Ejecutando: ' . $sSQL . '<br>';
		}
		$tabla = $objDB->ejecutasql($sSQL);
		while ($fila = $objDB->sf($tabla)) {
			$data1[] = $fila;
			$sIdsPadre = $sIdsPadre . ', ' . $fila['visa34id'];
			$iTotal++;
		}
		$iTotal2942 = 0;
		$sSQL = 'SELECT visa42idtipo, visa42consec, visa42id, visa42titulo, visa42descripcion, visa42activo, visa42orden, visa42obligatorio, visa42tipodocumento FROM visa42convanexo WHERE visa42idtipo IN (' . $sIdsPadre . ')';
		if ($bDebug) {echo 'Ejecutando: ' . $sSQL . '<br>';
		}
		$tabla = $objDB->ejecutasql($sSQL);
		while ($fila = $objDB->sf($tabla)) {
			$data2942[] = $fila;
			$iTotal2942++;
		}
		$res = array();
		$res[] = $iTotal;
		$res[] = $data1;
		$res[] = $data2942;
	}
	if ($sError != '') {
		$res = array();
		$res[] = -2;
		$res[] = $sError;
		$bResponde = true;
	}
	break;
	default:
	if ($bDebug) {
		echo 'No se ha encontrado la petici&oacute;n "' . $datos[0] . '"';
	} else {
		header('Location:index.php');
		die();
	}
}
if ($bResponde) {
	print json_encode($res);
}
?>