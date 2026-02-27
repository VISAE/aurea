<?php
/*
--- © Omar Augusto Bautista - UNAD - 2026 ---
--- omar.bautista@unad.edu.co - http://www.unad.edu.co
--- Modelo Version 3.1.5 viernes, 27 de febrero de 2026
*/
/*
/** Archivo para reportes tipo csv 2940.
 * Aquí se genera un archivo tipo csv con la siguiente estructura (indicar estructura).
 * @author Omar Augusto Bautista - omar.bautista@unad.edu.co
 * @date viernes, 27 de febrero de 2026
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
	$mensajes_2940 = 'lg/lg_2940_' . $sIdioma . '.php';
	if (!file_exists($mensajes_2940)) {
		$mensajes_2940 = 'lg/lg_2940_es.php';
	}
	require $mensajes_todas;
	require $mensajes_2940;
	$visa40idconvocatoria_lg = 'Convocatoria';
	$visa40idtercero_lg = 'Candidato';
	$visa40id_lg = 'Ref';
	$visa40estado_lg = 'Estado';
	$visa40idperiodo_lg = 'Periodo';
	$visa40idescuela_lg = 'Escuela';
	$visa40idprograma_lg = 'Programa';
	$visa40idzona_lg = 'Zona';
	$visa40idcentro_lg = 'Centro';
	$visa40fechainsc_lg = 'Fecha inscripción';
	$visa40fechaadmision_lg = 'Fecha admisión';
	$visa40numcupo_lg = 'Cupo número';
	$visa40idtipologia_lg = 'Tipología';
	$visa40idsubtipo_lg = 'Subtipología';
	$visa40idminuta_lg = 'Idminuta';
	$visa40idresolucion_lg = 'Idresolucion';
	$bdocumento_lg = 'Documento';
	$bnombre_lg = 'Nombre';
	$bconvocatoria_lg = 'Convocatoria';
	$bestado_lg = 'Estado';
	$btipologia_lg = 'Tipología';
	$bsubtipologia_lg = 'Subtipología';
	$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto != '') {
		$objDB->dbPuerto = $APP->dbpuerto;
	}
	// ----------- Espacio para los parametros.
	$sCondi = 'WHERE visa40idconvocatoria=' . $DATA['visa40idconvocatoria'] . ' AND visa40idtercero="' . $DATA['visa40idtercero'] . '"';
	// ----------- Fin del bloque de parametros.
	$sPath = dirname(__FILE__);
	$sSeparador = archivos_separador($sPath);
	$sPath = archivos_rutaservidor($sPath, $sSeparador);
	$sNombrePlano = 't2940.csv';
	$sTituloRpt = 'visa40inscripcion';
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
	$sDato = cadena_codificar('visa40inscripcion');
	$objplano->AdicionarLinea($sDato);
	$sDato = '';
	$objplano->AdicionarLinea($sDato);
	/* Alistar los arreglos para las tablas hijas */
	$avisa40idconvocatoria = array('');
	/*
	$sSQL = 'SELECT visa35id, visa35nombre FROM visa35convocatoria';
	$tabla = $objDB->ejecutasql($sSQL);
	while ($fila = $objDB->sf($tabla)) {
		$avisa40idconvocatoria[$fila['visa35id']] = cadena_codificar($fila['visa35nombre']);
	}
	*/
	$avisa40idperiodo = array('');
	/*
	$sSQL = 'SELECT exte02id, exte02nombre FROM exte02per_aca';
	$tabla = $objDB->ejecutasql($sSQL);
	while ($fila = $objDB->sf($tabla)) {
		$avisa40idperiodo[$fila['exte02id']] = cadena_codificar($fila['exte02nombre']);
	}
	*/
	$avisa40idescuela = array('');
	/*
	$sSQL = 'SELECT core12id, core12nombre FROM core12escuela';
	$tabla = $objDB->ejecutasql($sSQL);
	while ($fila = $objDB->sf($tabla)) {
		$avisa40idescuela[$fila['core12id']] = cadena_codificar($fila['core12nombre']);
	}
	*/
	$avisa40idprograma = array('');
	/*
	$sSQL = 'SELECT core09id, core09nombre FROM core09programa';
	$tabla = $objDB->ejecutasql($sSQL);
	while ($fila = $objDB->sf($tabla)) {
		$avisa40idprograma[$fila['core09id']] = cadena_codificar($fila['core09nombre']);
	}
	*/
	$avisa40idzona = array('');
	/*
	$sSQL = 'SELECT unad23id, unad23nombre FROM unad23zona';
	$tabla = $objDB->ejecutasql($sSQL);
	while ($fila = $objDB->sf($tabla)) {
		$avisa40idzona[$fila['unad23id']] = cadena_codificar($fila['unad23nombre']);
	}
	*/
	$avisa40idcentro = array('');
	/*
	$sSQL = 'SELECT unad24id, unad24nombre FROM unad24sede';
	$tabla = $objDB->ejecutasql($sSQL);
	while ($fila = $objDB->sf($tabla)) {
		$avisa40idcentro[$fila['unad24id']] = cadena_codificar($fila['unad24nombre']);
	}
	*/
	$avisa40idtipologia = array('');
	/*
	$sSQL = 'SELECT visa36id, visa36nombre FROM visa36convtipologia';
	$tabla = $objDB->ejecutasql($sSQL);
	while ($fila = $objDB->sf($tabla)) {
		$avisa40idtipologia[$fila['visa36id']] = cadena_codificar($fila['visa36nombre']);
	}
	*/
	$avisa40idsubtipo = array('');
	/*
	$sSQL = 'SELECT visa37id, visa37nombre FROM visa37convsubtipo';
	$tabla = $objDB->ejecutasql($sSQL);
	while ($fila = $objDB->sf($tabla)) {
		$avisa40idsubtipo[$fila['visa37id']] = cadena_codificar($fila['visa37nombre']);
	}
	*/
	$aSys11 = array();
	$sTitulo1 = 'Titulo 1';
	for ($l = 1; $l <= 20; $l++) {
		$sTitulo1 = $sTitulo1 . $cSepara;
	}
	$sBloque1 = '' . $visa40idconvocatoria_lg . $cSepara . 'TD' . $cSepara . 'Doc' . $cSepara . $visa40idtercero_lg . $cSepara . $visa40estado_lg . $cSepara . $visa40idperiodo_lg . $cSepara . $visa40idescuela_lg . $cSepara
	 . $visa40idprograma_lg . $cSepara . $visa40idzona_lg . $cSepara . $visa40idcentro_lg . $cSepara . $visa40fechainsc_lg . $cSepara . $visa40fechaadmision_lg . $cSepara
	 . $visa40numcupo_lg . $cSepara . $visa40idtipologia_lg . $cSepara . $visa40idsubtipo_lg . $cSepara . $visa40idminuta_lg . $cSepara . $visa40idresolucion_lg . $cSepara
	 . $bdocumento_lg . $cSepara . $bnombre_lg . $cSepara . $bconvocatoria_lg;
	$sTitulo2 = 'Titulo 2';
	for ($l = 1; $l <= 3; $l++) {
		$sTitulo2 = $sTitulo2 . $cSepara;
	}
	$sBloque2 = '' . $cSepara . $bestado_lg . $cSepara . $btipologia_lg . $cSepara . $bsubtipologia_lg;
	//$objplano->AdicionarLinea($sTitulo1 . $sTitulo2);
	$objplano->AdicionarLinea($sBloque1 . $sBloque2);
	$sCampos = 'SELECT TB.*
	$sConsulta = 'FROM visa40inscripcion 
	' . $sCondi . '';
	$sOrden = '';
	$sSQL = $sCampos . ' ' . $sConsulta . ' ' . $sOrden;
	if ($bDebug) {
		$objplano->AdicionarLinea($sSQL);
	}
	$tabla = $objDB->ejecutasql($sSQL);
	while ($fila = $objDB->sf($tabla)) {
		$lin_visa40idconvocatoria = '';
		$lin_visa40idtercero = $cSepara . $cSepara . $cSepara;
		$lin_visa40estado = $cSepara;
		$lin_visa40idperiodo = $cSepara;
		$lin_visa40idescuela = $cSepara;
		$lin_visa40idprograma = $cSepara;
		$lin_visa40idzona = $cSepara;
		$lin_visa40idcentro = $cSepara;
		$lin_visa40fechainsc = $cSepara;
		$lin_visa40fechaadmision = $cSepara;
		$lin_visa40numcupo = $cSepara;
		$lin_visa40idtipologia = $cSepara;
		$lin_visa40idsubtipo = $cSepara;
		$lin_visa40idminuta = $cSepara;
		$lin_visa40idresolucion = $cSepara;
		$lin_bdocumento = $cSepara;
		$lin_bnombre = $cSepara;
		$lin_bconvocatoria = $cSepara;
		$lin_bestado = $cSepara;
		$lin_btipologia = $cSepara;
		$lin_bsubtipologia = $cSepara;
		$i_visa40idconvocatoria = $fila['visa40idconvocatoria'];
		if (isset($avisa40idconvocatoria[$i_visa40idconvocatoria]) == 0) {
			$sSQL = 'SELECT visa35nombre FROM visa35convocatoria WHERE visa35id=' . $i_visa40idconvocatoria . '';
			$tablae = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae) > 0) {
				$filae = $objDB->sf($tablae);
				$avisa40idconvocatoria[$i_visa40idconvocatoria] = str_replace($cSepara, $cComplementa, $filae['visa35nombre']);
			} else {
				$avisa40idconvocatoria[$i_visa40idconvocatoria] = '';
			}
		}
		$lin_visa40idconvocatoria = cadena_codificar($avisa40idconvocatoria[$i_visa40idconvocatoria]);
		$iTer = $fila['visa40idtercero'];
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
		$lin_visa40idtercero = $cSepara . $aSys11[$iTer]['td'] . $cSepara . $aSys11[$iTer]['doc'] . $cSepara . cadena_codificar($aSys11[$iTer]['razon']);
		$lin_visa40estado = $cSepara . $fila['visa40estado'];
		$i_visa40idperiodo = $fila['visa40idperiodo'];
		if (isset($avisa40idperiodo[$i_visa40idperiodo]) == 0) {
			$sSQL = 'SELECT exte02nombre FROM exte02per_aca WHERE exte02id=' . $i_visa40idperiodo . '';
			$tablae = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae) > 0) {
				$filae = $objDB->sf($tablae);
				$avisa40idperiodo[$i_visa40idperiodo] = str_replace($cSepara, $cComplementa, $filae['exte02nombre']);
			} else {
				$avisa40idperiodo[$i_visa40idperiodo] = '';
			}
		}
		$lin_visa40idperiodo = $cSepara . cadena_codificar($avisa40idperiodo[$i_visa40idperiodo]);
		$i_visa40idescuela = $fila['visa40idescuela'];
		if (isset($avisa40idescuela[$i_visa40idescuela]) == 0) {
			$sSQL = 'SELECT core12nombre FROM core12escuela WHERE core12id=' . $i_visa40idescuela . '';
			$tablae = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae) > 0) {
				$filae = $objDB->sf($tablae);
				$avisa40idescuela[$i_visa40idescuela] = str_replace($cSepara, $cComplementa, $filae['core12nombre']);
			} else {
				$avisa40idescuela[$i_visa40idescuela] = '';
			}
		}
		$lin_visa40idescuela = $cSepara . cadena_codificar($avisa40idescuela[$i_visa40idescuela]);
		$i_visa40idprograma = $fila['visa40idprograma'];
		if (isset($avisa40idprograma[$i_visa40idprograma]) == 0) {
			$sSQL = 'SELECT core09nombre FROM core09programa WHERE core09id=' . $i_visa40idprograma . '';
			$tablae = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae) > 0) {
				$filae = $objDB->sf($tablae);
				$avisa40idprograma[$i_visa40idprograma] = str_replace($cSepara, $cComplementa, $filae['core09nombre']);
			} else {
				$avisa40idprograma[$i_visa40idprograma] = '';
			}
		}
		$lin_visa40idprograma = $cSepara . cadena_codificar($avisa40idprograma[$i_visa40idprograma]);
		$i_visa40idzona = $fila['visa40idzona'];
		if (isset($avisa40idzona[$i_visa40idzona]) == 0) {
			$sSQL = 'SELECT unad23nombre FROM unad23zona WHERE unad23id=' . $i_visa40idzona . '';
			$tablae = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae) > 0) {
				$filae = $objDB->sf($tablae);
				$avisa40idzona[$i_visa40idzona] = str_replace($cSepara, $cComplementa, $filae['unad23nombre']);
			} else {
				$avisa40idzona[$i_visa40idzona] = '';
			}
		}
		$lin_visa40idzona = $cSepara . cadena_codificar($avisa40idzona[$i_visa40idzona]);
		$i_visa40idcentro = $fila['visa40idcentro'];
		if (isset($avisa40idcentro[$i_visa40idcentro]) == 0) {
			$sSQL = 'SELECT unad24nombre FROM unad24sede WHERE unad24id=' . $i_visa40idcentro . '';
			$tablae = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae) > 0) {
				$filae = $objDB->sf($tablae);
				$avisa40idcentro[$i_visa40idcentro] = str_replace($cSepara, $cComplementa, $filae['unad24nombre']);
			} else {
				$avisa40idcentro[$i_visa40idcentro] = '';
			}
		}
		$lin_visa40idcentro = $cSepara . cadena_codificar($avisa40idcentro[$i_visa40idcentro]);
		$lin_visa40fechainsc = $cSepara . fecha_desdenumero($fila['visa40fechainsc']);
		$lin_visa40fechaadmision = $cSepara . fecha_desdenumero($fila['visa40fechaadmision']);
		$lin_visa40numcupo = $cSepara . $fila['visa40numcupo'];
		$i_visa40idtipologia = $fila['visa40idtipologia'];
		if (isset($avisa40idtipologia[$i_visa40idtipologia]) == 0) {
			$sSQL = 'SELECT visa36nombre FROM visa36convtipologia WHERE visa36id=' . $i_visa40idtipologia . '';
			$tablae = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae) > 0) {
				$filae = $objDB->sf($tablae);
				$avisa40idtipologia[$i_visa40idtipologia] = str_replace($cSepara, $cComplementa, $filae['visa36nombre']);
			} else {
				$avisa40idtipologia[$i_visa40idtipologia] = '';
			}
		}
		$lin_visa40idtipologia = $cSepara . cadena_codificar($avisa40idtipologia[$i_visa40idtipologia]);
		$i_visa40idsubtipo = $fila['visa40idsubtipo'];
		if (isset($avisa40idsubtipo[$i_visa40idsubtipo]) == 0) {
			$sSQL = 'SELECT visa37nombre FROM visa37convsubtipo WHERE visa37id=' . $i_visa40idsubtipo . '';
			$tablae = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae) > 0) {
				$filae = $objDB->sf($tablae);
				$avisa40idsubtipo[$i_visa40idsubtipo] = str_replace($cSepara, $cComplementa, $filae['visa37nombre']);
			} else {
				$avisa40idsubtipo[$i_visa40idsubtipo] = '';
			}
		}
		$lin_visa40idsubtipo = $cSepara . cadena_codificar($avisa40idsubtipo[$i_visa40idsubtipo]);
		$lin_visa40idminuta = $cSepara . $fila['visa40idminuta'];
		$lin_visa40idresolucion = $cSepara . $fila['visa40idresolucion'];
		$lin_bdocumento = $cSepara . $fila['bdocumento'];
		$lin_bnombre = $cSepara . $fila['bnombre'];
		$lin_bconvocatoria = $cSepara . $fila['bconvocatoria'];
		$lin_bestado = $cSepara . $fila['bestado'];
		$lin_btipologia = $cSepara . $fila['btipologia'];
		$lin_bsubtipologia = $cSepara . $fila['bsubtipologia'];
		$sBloque1 = '' . $lin_visa40idconvocatoria . $lin_visa40idtercero . $lin_visa40estado . $lin_visa40idperiodo . $lin_visa40idescuela
		 . $lin_visa40idprograma . $lin_visa40idzona . $lin_visa40idcentro . $lin_visa40fechainsc . $lin_visa40fechaadmision
		 . $lin_visa40numcupo . $lin_visa40idtipologia . $lin_visa40idsubtipo . $lin_visa40idminuta . $lin_visa40idresolucion
		 . $lin_bdocumento . $lin_bnombre . $lin_bconvocatoria;
		$sBloque2 = '' . $lin_bestado . $lin_btipologia . $lin_bsubtipologia;
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
