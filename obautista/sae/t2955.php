<?php
/*
--- © Juan David Avellaneda Molina - UNAD - 2026 ---
--- juand.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Version 3.2.5 martes, 15 de septiembre de 2026
*/
/*
/** Archivo para reportes tipo csv 2955.
 * Aquí se genera un archivo tipo csv con la siguiente estructura (indicar estructura).
 * @author Juan David Avellaneda Molina - juand.avellaneda@unad.edu.co
 * @date martes, 15 de septiembre de 2026
 */

/*
error_reporting(E_ALL);
ini_set("display_errors", 1);
*/

if (file_exists('./err_control.php')) {
	require './err_control.php';
}
if (!file_exists('./app.php')) {
	echo '<b>Error N 1 de instalaci&oacute;n</b><br>No se ha establecido un archivo de configuraci&oacute;n, por favor comuniquese con el administrador del sistema.';
	die();
}
mb_internal_encoding('UTF-8');
require './app.php';
require $APP->rutacomun . 'unad_todas.php';
require $APP->rutacomun . 'libs/clsdbadmin.php';
require $APP->rutacomun . 'unad_librerias.php';
require $APP->rutacomun . 'libs/clsplanos.php';
require $APP->rutacomun . 'libaurea.php';
require $APP->rutacomun . 'libdatos.php';
if ($_SESSION['unad_id_tercero'] == 0) {
	header('Location:./nopermiso.php');
	die();
} else {
	$idTercero = numeros_validar($_SESSION['unad_id_tercero']);
	if ($idTercero != $_SESSION['unad_id_tercero']) {
		die();
	}
}
$_SESSION['u_ultimominuto'] = iminutoavance();
$sError = '';
$sDebug = '';
$bDebug = false;
if (isset($_REQUEST['clave']) == 0) {
	$_REQUEST['clave'] = '';
}
if (isset($_REQUEST['v3']) == 0) {
	$_REQUEST['v3'] = '';
}
if (isset($_REQUEST['v4']) == 0) {
	$_REQUEST['v4'] = '';
}
if (isset($_REQUEST['v5']) == 0) {
	$_REQUEST['v5'] = '';
}
if (isset($_REQUEST['rdebug']) == 0) {
	$_REQUEST['rdebug'] = 0;
}
if ($sError == '') {
	$idVar3 = numeros_validar($_REQUEST['v3']);
	if ($idVar3 != $_REQUEST['v3']) {
		$sError = 'No es posible iniciar el sistema.';
	}
	$idVar4 = numeros_validar($_REQUEST['v4']);
	if ($idVar4 != $_REQUEST['v4']) {
		$sError = 'No es posible iniciar el sistema.';
	}
	$idVar5 = numeros_validar($_REQUEST['v5']);
	if ($idVar5 != $_REQUEST['v5']) {
		$sError = 'No es posible iniciar el sistema.';
	}
}
if ($sError == '') {
	if ((int)$idVar3 == 0) {
		$sError = 'No se ha determinado el valor';
	}
	if ((int)$idVar4 == 0) {
		$sError = 'No se ha determinado el valor';
	}
	if ((int)$idVar5 == 0) {
		$sError = 'No se ha determinado el valor';
	}
}
if ($sError == '') {
	$sDebug = '';
	if ($_REQUEST['rdebug'] == 1) {
		$bDebug = true;
	}
	$cSepara = ';';
	$cEvita = ',';
	$cComplementa = '.';
	if (isset($_REQUEST['separa']) != 0) {
		if ($_REQUEST['separa'] != ';') {
			$cSepara = ',';
			$cEvita = ';';
		}
	}
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	/*
	$mensajes_2900 = 'lg/lg_2900_' . $sIdioma . '.php';
	if (!file_exists($mensajes_2900)) {
		$mensajes_2900 = 'lg/lg_2900_es.php';
	}
	require $mensajes_2900;
	*/
	$mensajes_2955 = 'lg/lg_2955_' . $sIdioma . '.php';
	if (!file_exists($mensajes_2955)) {
		$mensajes_2955 = 'lg/lg_2955_es.php';
	}
	require $mensajes_todas;
	require $mensajes_2955;
	$visa55idproyecto_lg = 'Proyecto';
	$visa55codigo_lg = 'Código';
	$visa55id_lg = 'Ref';
	$visa55nombre_lg = 'Nombre';
	$visa55descripcion_lg = 'Descripcion';
	$visa55idresponsable_lg = 'Responsable';
	$visa55vigente_lg = 'Vigente';
	$visa55color_lg = 'Color';
	$visa55fechacreacion_lg = 'Fechacreacion';
	$visa55fechaactualiza_lg = 'Fecha Actualización';
	$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto != '') {
		$objDB->dbPuerto = $APP->dbpuerto;
	}
	// ----------- Espacio para los parametros.
	$sCondi = 'WHERE visa55idproyecto=' . $_REQUEST['visa55idproyecto'] . ' AND visa55codigo="' . $_REQUEST['visa55codigo'] . '"';
	// ----------- Fin del bloque de parametros.
	$sPath = dirname(__FILE__);
	$sSeparador = archivos_separador($sPath);
	$sPath = archivos_rutaservidor($sPath, $sSeparador);
	$sNombrePlano = 't2955.csv';
	$sTituloRpt = 'visa55sistema';
	$sNombrePlanoFinal = $sTituloRpt . '.csv';
	$objplano = new clsPlanos($sPath . $sNombrePlano);
	$idEntidad = Traer_Entidad();
	$sDato = 'UNIVERSIDAD NACIONAL ABIERTA Y A DISTANCIA - UNAD';
	switch ($idEntidad) {
		case 1: // Unad Florida
			$sDato = 'UNAD FLORIDA INC';
			break;
		case 2: // Unad UE
			$sDato = 'UNAD UNION EUROPEA';
			break;
	}
	$objplano->AdicionarLinea($sDato);
	$sDato = cadena_codificar('visa55sistema');
	$objplano->AdicionarLinea($sDato);
	$sDato = '';
	$objplano->AdicionarLinea($sDato);
	/* Alistar los arreglos para las tablas hijas */
	$avisa55idproyecto = array('');
	/*
	$sSQL = 'SELECT plan04id, plan04numero FROM plan04proyecto';
	$tabla = $objDB->ejecutasql($sSQL);
	while ($fila = $objDB->sf($tabla)) {
		$avisa55idproyecto[$fila['plan04id']] = cadena_codificar($fila['plan04numero']);
	}
	*/
	$avisa55vigente = array('');
	$avisa55color = array('');
	$aSys11 = array();
	$sTitulo1 = 'Titulo 1';
	for ($l = 1; $l <= 11; $l++) {
		$sTitulo1 = $sTitulo1 . $cSepara;
	}
	$sBloque1 = '' . $visa55idproyecto_lg . $cSepara . $visa55codigo_lg . $cSepara . $visa55nombre_lg . $cSepara . $visa55descripcion_lg . $cSepara . 'TD' . $cSepara . 'Doc' . $cSepara . $visa55idresponsable_lg . $cSepara
	 . $visa55vigente_lg . $cSepara . $visa55color_lg . $cSepara . $visa55fechacreacion_lg . $cSepara . $visa55fechaactualiza_lg;
	//$objplano->AdicionarLinea($sTitulo1);
	$objplano->AdicionarLinea($sBloque1);
	$sCampos = 'SELECT TB.*
	$sConsulta = 'FROM visa55sistema 
	' . $sCondi . '';
	$sOrden = '';
	$sSQL = $sCampos . ' ' . $sConsulta . ' ' . $sOrden;
	if ($bDebug) {
		$objplano->AdicionarLinea($sSQL);
	}
	$tabla = $objDB->ejecutasql($sSQL);
	while ($fila = $objDB->sf($tabla)) {
		$lin_visa55idproyecto = '';
		$lin_visa55codigo = $cSepara;
		$lin_visa55nombre = $cSepara;
		$lin_visa55descripcion = $cSepara;
		$lin_visa55idresponsable = $cSepara . $cSepara . $cSepara;
		$lin_visa55vigente = $cSepara;
		$lin_visa55color = $cSepara;
		$lin_visa55fechacreacion = $cSepara;
		$lin_visa55fechaactualiza = $cSepara;
		$i_visa55idproyecto = $fila['visa55idproyecto'];
		if (isset($avisa55idproyecto[$i_visa55idproyecto]) == 0) {
			$sSQL = 'SELECT plan04numero FROM plan04proyecto WHERE plan04id=' . $i_visa55idproyecto . '';
			$tablae = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae) > 0) {
				$filae = $objDB->sf($tablae);
				$avisa55idproyecto[$i_visa55idproyecto] = str_replace($cSepara, $cComplementa, $filae['plan04numero']);
			} else {
				$avisa55idproyecto[$i_visa55idproyecto] = '';
			}
		}
		$lin_visa55idproyecto = cadena_codificar($avisa55idproyecto[$i_visa55idproyecto]);
		$lin_visa55codigo = $cSepara . str_replace($cSepara, $cComplementa, cadena_codificar($fila['visa55codigo']));
		$lin_visa55nombre = $cSepara . str_replace($cSepara, $cComplementa, cadena_codificar($fila['visa55nombre']));
		$lin_visa55descripcion = $cSepara . str_replace($cSepara, $cComplementa, cadena_QuitarSaltos(cadena_codificar($fila['visa55descripcion'])));
		$iTer = $fila['visa55idresponsable'];
		if (isset($aSys11[$iTer]['doc']) == 0) {
			$sSQL = 'SELECT unad11tipodoc, unad11doc, unad11razonsocial FROM unad11terceros WHERE unad11id=' . $iTer . '';
			$tabla11 = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla11) > 0) {
				$fila11 = $objDB->sf($tabla11);
				$aSys11[$iTer]['td'] = $fila11['unad11tipodoc'];
				$aSys11[$iTer]['doc'] = $fila11['unad11doc'];
				$aSys11[$iTer]['razon'] = $fila11['unad11razonsocial'];
			} else {
				$aSys11[$iTer]['td'] = '';
				$aSys11[$iTer]['doc'] = '[' . $iTer . ']';
				$aSys11[$iTer]['razon'] = '';
			}
		}
		$lin_visa55idresponsable = $cSepara . $aSys11[$iTer]['td'] . $cSepara . $aSys11[$iTer]['doc'] . $cSepara . cadena_codificar($aSys11[$iTer]['razon']);
		$lin_visa55vigente = $cSepara . '[' . $fila['visa55vigente'] . ']';
		if (isset($avisa55vigente[$fila['visa55vigente']]) != 0) {
			$lin_visa55vigente = $cSepara . cadena_codificar($avisa55vigente[$fila['visa55vigente']]);
		}
		$lin_visa55color = $cSepara . '[' . $fila['visa55color'] . ']';
		if (isset($avisa55color[$fila['visa55color']]) != 0) {
			$lin_visa55color = $cSepara . cadena_codificar($avisa55color[$fila['visa55color']]);
		}
		$lin_visa55fechacreacion = $cSepara . fecha_desdenumero($fila['visa55fechacreacion']);
		$lin_visa55fechaactualiza = $cSepara . fecha_desdenumero($fila['visa55fechaactualiza']);
		$sBloque1 = '' . $lin_visa55idproyecto . $lin_visa55codigo . $lin_visa55nombre . $lin_visa55descripcion . $lin_visa55idresponsable
		 . $lin_visa55vigente . $lin_visa55color . $lin_visa55fechacreacion . $lin_visa55fechaactualiza;
		$objplano->AdicionarLinea($sBloque1);
	}
	$objDB->CerrarConexion();
	$objplano->Generar();
	header('Content-Description: File Transfer');
	header('Content-Type: text/csv; charset=UTF-8');
	header('Content-Length: ' . filesize($sPath . $sNombrePlano));
	header('Content-Disposition: attachment; filename=' . basename($sNombrePlanoFinal));
	readfile($sPath . $sNombrePlano);
} else {
	echo $sError;
}
