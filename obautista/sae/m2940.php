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
	case '2940': /* visa40inscripcion */
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
	if ($bDebug) {echo 'Enviando visa40inscripcion a ' . $idEntidad . ' ' . $sIdTercero . ' ' . $sIdMovil . ' ' . $sListaIds . '<br>';
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
			$sCondicion = ' WHERE visa40id IN (' . $sIds . ')';
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
		$sSQL = 'SELECT visa40idconvocatoria, visa40idtercero, visa40id, visa40estado, visa40idperiodo, visa40idescuela, visa40idprograma, visa40idzona, visa40idcentro, visa40fechainsc, visa40fechaadmision, visa40numcupo, visa40idtipologia, visa40idsubtipo, visa40idminuta, visa40idresolucion, bdocumento, bnombre, bconvocatoria, bestado, btipologia, bsubtipologia FROM visa40inscripcion' . $sCondicion;
		if ($bDebug) {
			echo 'Ejecutando: ' . $sSQL . '<br>';
		}
		$tabla = $objDB->ejecutasql($sSQL);
		while ($fila = $objDB->sf($tabla)) {
			$data1[] = $fila;
			$sIdsPadre = $sIdsPadre . ', ' . $fila['visa40id'];
			$iTotal++;
		}
		$iTotal2943 = 0;
		$sSQL = 'SELECT visa43idinscripcion, visa43iddocumento, visa43id, visa43idorigen, visa43idarchivo, visa43fechaaprob, visa43usuarioaprueba FROM visa43inscripdocs WHERE visa43idinscripcion IN (' . $sIdsPadre . ')';
		if ($bDebug) {echo 'Ejecutando: ' . $sSQL . '<br>';
		}
		$tabla = $objDB->ejecutasql($sSQL);
		while ($fila = $objDB->sf($tabla)) {
			$data2943[] = $fila;
			$iTotal2943++;
		}
		$iTotal2944 = 0;
		$sSQL = 'SELECT visa44idinscripcion, visa44consec, visa44id, visa44alcance, visa44nota, visa44usuario, visa44fecha, visa44hora, visa44minuto FROM visa44anotaciones WHERE visa44idinscripcion IN (' . $sIdsPadre . ')';
		if ($bDebug) {echo 'Ejecutando: ' . $sSQL . '<br>';
		}
		$tabla = $objDB->ejecutasql($sSQL);
		while ($fila = $objDB->sf($tabla)) {
			$data2944[] = $fila;
			$iTotal2944++;
		}
		$iTotal2945 = 0;
		$sSQL = 'SELECT visa45idinscripcion, visa45idprueba, visa45id, visa45puntaje FROM visa45convpruebares WHERE visa45idinscripcion IN (' . $sIdsPadre . ')';
		if ($bDebug) {echo 'Ejecutando: ' . $sSQL . '<br>';
		}
		$tabla = $objDB->ejecutasql($sSQL);
		while ($fila = $objDB->sf($tabla)) {
			$data2945[] = $fila;
			$iTotal2945++;
		}
		$res = array();
		$res[] = $iTotal;
		$res[] = $data1;
		$res[] = $data2943;
		$res[] = $data2944;
		$res[] = $data2945;
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