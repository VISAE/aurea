<?php
/*
--- © Juan David Avellaneda Molina - UNAD - 2026 ---
--- juand.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Version 3.2.5 miércoles, 16 de septiembre de 2026
*/
/*
/** Archivo para reportes tipo csv 2957.
 * Aquí se genera un archivo tipo csv con la siguiente estructura (indicar estructura).
 * @author Juan David Avellaneda Molina - juand.avellaneda@unad.edu.co
 * @date miércoles, 16 de septiembre de 2026
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
	$mensajes_2957 = 'lg/lg_2957_' . $sIdioma . '.php';
	if (!file_exists($mensajes_2957)) {
		$mensajes_2957 = 'lg/lg_2957_es.php';
	}
	require $mensajes_todas;
	require $mensajes_2957;
	$visa57idpersemanal_lg = 'Idpersemanal';
	$visa57idsistema_lg = 'Sistema';
	$visa57consec_lg = 'Consecutivo';
	$visa57id_lg = 'Ref';
	$visa57titulo_lg = 'Titulo';
	$visa57descripcion_lg = 'Descripcion';
	$visa57tipoactividad_lg = 'Tipoactividad';
	$visa57estado_lg = 'Estado';
	$visa57prioridad_lg = 'Prioridad';
	$visa57fechaprogini_lg = 'Fecha inicio';
	$visa57fechaprogfin_lg = 'Fecha fin';
	$visa57fechaejecini_lg = 'Fechaejecini';
	$visa57fechaejecfin_lg = 'Fechaejecfin';
	$visa57porcavance_lg = 'Avance';
	$visa57fechacrea_lg = 'Fechacrea';
	$visa57fechaactualiza_lg = 'Fechaactualiza';
	$bsistema_lg = 'Sistema';
	$btitulo_lg = 'Titulo';
	$bestado_lg = 'Estado';
	$bprioridad_lg = 'Prioridad';
	$bfechaini_lg = 'Fecha inicio';
	$bfechafin_lg = 'Fecha fin';
	$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto != '') {
		$objDB->dbPuerto = $APP->dbpuerto;
	}
	// ----------- Espacio para los parametros.
	$sCondi = 'WHERE visa57idpersemanal=' . $_REQUEST['visa57idpersemanal'] . ' AND visa57idsistema=' . $_REQUEST['visa57idsistema'] . ' AND visa57consec=' . $_REQUEST['visa57consec'] . '';
	// ----------- Fin del bloque de parametros.
	$sPath = dirname(__FILE__);
	$sSeparador = archivos_separador($sPath);
	$sPath = archivos_rutaservidor($sPath, $sSeparador);
	$sNombrePlano = 't2957.csv';
	$sTituloRpt = 'visa57actividad';
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
	$sDato = cadena_codificar('visa57actividad');
	$objplano->AdicionarLinea($sDato);
	$sDato = '';
	$objplano->AdicionarLinea($sDato);
	/* Alistar los arreglos para las tablas hijas */
	$avisa57idpersemanal = array('');
	/*
	$sSQL = 'SELECT visa56id, visa56fechaini FROM visa56persemanal';
	$tabla = $objDB->ejecutasql($sSQL);
	while ($fila = $objDB->sf($tabla)) {
		$avisa57idpersemanal[$fila['visa56id']] = cadena_codificar($fila['visa56fechaini']);
	}
	*/
	$avisa57idsistema = array('');
	/*
	$sSQL = 'SELECT visa55id, visa55nombre FROM visa55sistema';
	$tabla = $objDB->ejecutasql($sSQL);
	while ($fila = $objDB->sf($tabla)) {
		$avisa57idsistema[$fila['visa55id']] = cadena_codificar($fila['visa55nombre']);
	}
	*/
	$avisa57tipoactividad = array('');
	$avisa57prioridad = array('');
	$sTitulo1 = 'Titulo 1';
	for ($l = 1; $l <= 20; $l++) {
		$sTitulo1 = $sTitulo1 . $cSepara;
	}
	$sBloque1 = '' . $visa57idpersemanal_lg . $cSepara . $visa57idsistema_lg . $cSepara . $visa57consec_lg . $cSepara . $visa57titulo_lg . $cSepara . $visa57descripcion_lg . $cSepara
	 . $visa57tipoactividad_lg . $cSepara . $visa57estado_lg . $cSepara . $visa57prioridad_lg . $cSepara . $visa57fechaprogini_lg . $cSepara . $visa57fechaprogfin_lg . $cSepara
	 . $visa57fechaejecini_lg . $cSepara . $visa57fechaejecfin_lg . $cSepara . $visa57porcavance_lg . $cSepara . $visa57fechacrea_lg . $cSepara . $visa57fechaactualiza_lg . $cSepara
	 . $bsistema_lg . $cSepara . $btitulo_lg . $cSepara . $bestado_lg . $cSepara . $bprioridad_lg . $cSepara . $bfechaini_lg;
	$sTitulo2 = 'Titulo 2';
	for ($l = 1; $l <= 1; $l++) {
		$sTitulo2 = $sTitulo2 . $cSepara;
	}
	$sBloque2 = '' . $cSepara . $bfechafin_lg;
	//$objplano->AdicionarLinea($sTitulo1 . $sTitulo2);
	$objplano->AdicionarLinea($sBloque1 . $sBloque2);
	$sCampos = 'SELECT TB.*
	$sConsulta = 'FROM visa57actividad 
	' . $sCondi . '';
	$sOrden = '';
	$sSQL = $sCampos . ' ' . $sConsulta . ' ' . $sOrden;
	if ($bDebug) {
		$objplano->AdicionarLinea($sSQL);
	}
	$tabla = $objDB->ejecutasql($sSQL);
	while ($fila = $objDB->sf($tabla)) {
		$lin_visa57idpersemanal = '';
		$lin_visa57idsistema = $cSepara;
		$lin_visa57consec = $cSepara;
		$lin_visa57titulo = $cSepara;
		$lin_visa57descripcion = $cSepara;
		$lin_visa57tipoactividad = $cSepara;
		$lin_visa57estado = $cSepara;
		$lin_visa57prioridad = $cSepara;
		$lin_visa57fechaprogini = $cSepara;
		$lin_visa57fechaprogfin = $cSepara;
		$lin_visa57fechaejecini = $cSepara;
		$lin_visa57fechaejecfin = $cSepara;
		$lin_visa57porcavance = $cSepara;
		$lin_visa57fechacrea = $cSepara;
		$lin_visa57fechaactualiza = $cSepara;
		$lin_bsistema = $cSepara;
		$lin_btitulo = $cSepara;
		$lin_bestado = $cSepara;
		$lin_bprioridad = $cSepara;
		$lin_bfechaini = $cSepara;
		$lin_bfechafin = $cSepara;
		$i_visa57idpersemanal = $fila['visa57idpersemanal'];
		if (isset($avisa57idpersemanal[$i_visa57idpersemanal]) == 0) {
			$sSQL = 'SELECT visa56fechaini FROM visa56persemanal WHERE visa56id=' . $i_visa57idpersemanal . '';
			$tablae = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae) > 0) {
				$filae = $objDB->sf($tablae);
				$avisa57idpersemanal[$i_visa57idpersemanal] = str_replace($cSepara, $cComplementa, $filae['visa56fechaini']);
			} else {
				$avisa57idpersemanal[$i_visa57idpersemanal] = '';
			}
		}
		$lin_visa57idpersemanal = cadena_codificar($avisa57idpersemanal[$i_visa57idpersemanal]);
		$i_visa57idsistema = $fila['visa57idsistema'];
		if (isset($avisa57idsistema[$i_visa57idsistema]) == 0) {
			$sSQL = 'SELECT visa55nombre FROM visa55sistema WHERE visa55id=' . $i_visa57idsistema . '';
			$tablae = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae) > 0) {
				$filae = $objDB->sf($tablae);
				$avisa57idsistema[$i_visa57idsistema] = str_replace($cSepara, $cComplementa, $filae['visa55nombre']);
			} else {
				$avisa57idsistema[$i_visa57idsistema] = '';
			}
		}
		$lin_visa57idsistema = $cSepara . cadena_codificar($avisa57idsistema[$i_visa57idsistema]);
		$lin_visa57consec = $cSepara . $fila['visa57consec'];
		$lin_visa57titulo = $cSepara . str_replace($cSepara, $cComplementa, cadena_codificar($fila['visa57titulo']));
		$lin_visa57descripcion = $cSepara . str_replace($cSepara, $cComplementa, cadena_QuitarSaltos(cadena_codificar($fila['visa57descripcion'])));
		$lin_visa57tipoactividad = $cSepara . '[' . $fila['visa57tipoactividad'] . ']';
		if (isset($avisa57tipoactividad[$fila['visa57tipoactividad']]) != 0) {
			$lin_visa57tipoactividad = $cSepara . cadena_codificar($avisa57tipoactividad[$fila['visa57tipoactividad']]);
		}
		$lin_visa57estado = $cSepara . $fila['visa57estado'];
		$lin_visa57prioridad = $cSepara . '[' . $fila['visa57prioridad'] . ']';
		if (isset($avisa57prioridad[$fila['visa57prioridad']]) != 0) {
			$lin_visa57prioridad = $cSepara . cadena_codificar($avisa57prioridad[$fila['visa57prioridad']]);
		}
		$lin_visa57fechaprogini = $cSepara . fecha_desdenumero($fila['visa57fechaprogini']);
		$lin_visa57fechaprogfin = $cSepara . fecha_desdenumero($fila['visa57fechaprogfin']);
		$lin_visa57fechaejecini = $cSepara . fecha_desdenumero($fila['visa57fechaejecini']);
		$lin_visa57fechaejecfin = $cSepara . fecha_desdenumero($fila['visa57fechaejecfin']);
		$lin_visa57porcavance = $cSepara . $fila['visa57porcavance'];
		$lin_visa57fechacrea = $cSepara . fecha_desdenumero($fila['visa57fechacrea']);
		$lin_visa57fechaactualiza = $cSepara . fecha_desdenumero($fila['visa57fechaactualiza']);
		$lin_bsistema = $cSepara . $fila['bsistema'];
		$lin_btitulo = $cSepara . $fila['btitulo'];
		$lin_bestado = $cSepara . $fila['bestado'];
		$lin_bprioridad = $cSepara . $fila['bprioridad'];
		$lin_bfechaini = $cSepara . $fila['bfechaini'];
		$lin_bfechafin = $cSepara . $fila['bfechafin'];
		$sBloque1 = '' . $lin_visa57idpersemanal . $lin_visa57idsistema . $lin_visa57consec . $lin_visa57titulo . $lin_visa57descripcion
		 . $lin_visa57tipoactividad . $lin_visa57estado . $lin_visa57prioridad . $lin_visa57fechaprogini . $lin_visa57fechaprogfin
		 . $lin_visa57fechaejecini . $lin_visa57fechaejecfin . $lin_visa57porcavance . $lin_visa57fechacrea . $lin_visa57fechaactualiza
		 . $lin_bsistema . $lin_btitulo . $lin_bestado . $lin_bprioridad . $lin_bfechaini;
		$sBloque2 = '' . $lin_bfechafin;
		$objplano->AdicionarLinea($sBloque1.$sBloque2);
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
