<?php
/*
--- © Juan David Avellaneda Molina - UNAD - 2026 ---
--- juand.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Version 3.2.5 miércoles, 16 de septiembre de 2026
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
	case '2957': /* visa57actividad */
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
	if ($bDebug) {echo 'Enviando visa57actividad a ' . $idEntidad . ' ' . $sIdTercero . ' ' . $sIdMovil . ' ' . $sListaIds . '<br>';
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
			$sCondicion = ' WHERE visa57id IN (' . $sIds . ')';
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
		$sSQL = 'SELECT visa57idpersemanal, visa57idsistema, visa57consec, visa57id, visa57titulo, visa57descripcion, visa57tipoactividad, visa57estado, visa57prioridad, visa57fechaprogini, visa57fechaprogfin, visa57fechaejecini, visa57fechaejecfin, visa57porcavance, visa57fechacrea, visa57fechaactualiza, bsistema, btitulo, bestado, bprioridad, bfechaini, bfechafin FROM visa57actividad' . $sCondicion;
		if ($bDebug) {
			echo 'Ejecutando: ' . $sSQL . '<br>';
		}
		$tabla = $objDB->ejecutasql($sSQL);
		while ($fila = $objDB->sf($tabla)) {
			$data1[] = $fila;
			$sIdsPadre = $sIdsPadre . ', ' . $fila['visa57id'];
			$iTotal++;
		}
		$iTotal2958 = 0;
		$sSQL = 'SELECT visa58idactividad, visa58consec, visa58id, visa58descripcion, visa58cumplimiento, visa58fecharegistro FROM visa58resultado WHERE visa58idactividad IN (' . $sIdsPadre . ')';
		if ($bDebug) {echo 'Ejecutando: ' . $sSQL . '<br>';
		}
		$tabla = $objDB->ejecutasql($sSQL);
		while ($fila = $objDB->sf($tabla)) {
			$data2958[] = $fila;
			$iTotal2958++;
		}
		$iTotal2959 = 0;
		$sSQL = 'SELECT visa59idactividad, visa59consec, visa59id, visa59titulo, visa59idorigen, visa59idarchivo, visa59tipoarchivo, visa59descripcion, visa59fechacarga, visa59idusuario FROM visa59evidencia WHERE visa59idactividad IN (' . $sIdsPadre . ')';
		if ($bDebug) {echo 'Ejecutando: ' . $sSQL . '<br>';
		}
		$tabla = $objDB->ejecutasql($sSQL);
		while ($fila = $objDB->sf($tabla)) {
			$data2959[] = $fila;
			$iTotal2959++;
		}
		$iTotal2960 = 0;
		$sSQL = 'SELECT visa60idactividad, visa60consec, visa60id, visa60fechareproini, visa60fechareprofin, visa60motivo, visa60fecharegistro, visa60idusuario FROM visa60reprograma WHERE visa60idactividad IN (' . $sIdsPadre . ')';
		if ($bDebug) {echo 'Ejecutando: ' . $sSQL . '<br>';
		}
		$tabla = $objDB->ejecutasql($sSQL);
		while ($fila = $objDB->sf($tabla)) {
			$data2960[] = $fila;
			$iTotal2960++;
		}
		$iTotal2961 = 0;
		$sSQL = 'SELECT visa61idactividad, visa61consec, visa61id, visa61descripcion, visa61impacto, visa61requiereapoyo, visa61fecharegistro FROM visa61dificultad WHERE visa61idactividad IN (' . $sIdsPadre . ')';
		if ($bDebug) {echo 'Ejecutando: ' . $sSQL . '<br>';
		}
		$tabla = $objDB->ejecutasql($sSQL);
		while ($fila = $objDB->sf($tabla)) {
			$data2961[] = $fila;
			$iTotal2961++;
		}
		$iTotal2962 = 0;
		$sSQL = 'SELECT visa62idactividad, visa62consec, visa62id, visa62descripcion, visa62idresponsable, visa62fechalimite, visa62estado, visa62fechacumple, visa62observaciones, visa62fecharegistro FROM visa62compromiso WHERE visa62idactividad IN (' . $sIdsPadre . ')';
		if ($bDebug) {echo 'Ejecutando: ' . $sSQL . '<br>';
		}
		$tabla = $objDB->ejecutasql($sSQL);
		while ($fila = $objDB->sf($tabla)) {
			$data2962[] = $fila;
			$iTotal2962++;
		}
		$iTotal2963 = 0;
		$sSQL = 'SELECT visa63idactividad, visa63consec, visa63id, visa63descripcion, visa63idcolaborador, visa63estado, visa63fechasolicitud, visa63fecharespuesta, visa63observaciones FROM visa63solicitaapoyo WHERE visa63idactividad IN (' . $sIdsPadre . ')';
		if ($bDebug) {echo 'Ejecutando: ' . $sSQL . '<br>';
		}
		$tabla = $objDB->ejecutasql($sSQL);
		while ($fila = $objDB->sf($tabla)) {
			$data2963[] = $fila;
			$iTotal2963++;
		}
		$res = array();
		$res[] = $iTotal;
		$res[] = $data1;
		$res[] = $data2958;
		$res[] = $data2959;
		$res[] = $data2960;
		$res[] = $data2961;
		$res[] = $data2962;
		$res[] = $data2963;
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