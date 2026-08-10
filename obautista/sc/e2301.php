<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2025 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Version 3.0.12c lunes, 21 de abril de 2025
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
require $APP->rutacomun . 'libaurea.php';
require $APP->rutacomun . 'libexcel_ss.php';
require $APP->rutacomun . 'vendor/autoload.php';
require $APP->rutacomun . 'lib2301.php';
$sIdioma = AUREA_Idioma();
$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
if (!file_exists($mensajes_todas)) {
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
}
$mensajes_2301 = $APP->rutacomun . 'lg/lg_2301_' . $sIdioma . '.php';
if (!file_exists($mensajes_2301)) {
	$mensajes_2301 = $APP->rutacomun . 'lg/lg_2301_es.php';
}
$mensajes_2344 = $APP->rutacomun . 'lg/lg_2344_' . $sIdioma . '.php';
if (!file_exists($mensajes_2344)) {
	$mensajes_2344 = $APP->rutacomun . 'lg/lg_2344_es.php';
}
$mensajes_2350 = 'lg/lg_2350_' . $sIdioma . '.php';
if (!file_exists($mensajes_2350)) {
	$mensajes_2350 = 'lg/lg_2350_es.php';
}
require $mensajes_todas;
require $mensajes_2301;
require $mensajes_2344;
require $mensajes_2350;
if ((int)$_SESSION['unad_id_tercero'] == 0) {
	die();
} else {
	$idTercero = numeros_validar($_SESSION['unad_id_tercero']);
	if ($idTercero != $_SESSION['unad_id_tercero']) {
		die();
	}
}
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$_SESSION['u_ultimominuto'] = iminutoavance();
$sError = '';
$iReporte = 0;
$iCodModulo=2350;
$bDebug = false;
if (isset($_REQUEST['clave']) == 0) {
	$_REQUEST['clave'] = '';
}
if (isset($_REQUEST['rdebug']) == 0) {
	$_REQUEST['rdebug'] = 0;
}
$aNombres = array(
	'', '', '', 'idperaca', 'idzona', 'idcentro', 'idtipo', 'poblacion', 'convenio', 'idescuela', 'idprograma', 'periodoacomp', 'periodomatricula', 'tipomatricula', 'listadoc'
);
$aTipos = array(
	0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1
);
$iNumVariables = 14;
for ($k = 3; $k <= $iNumVariables; $k++) {
	if (isset($_REQUEST['v' . $k]) == 0) {
		$_REQUEST['v' . $k] = '';
	} else {
		//Validar las variables.
		if ($aTipos[$k] == 1) {
			$vVr = cadena_Validar($_REQUEST['v' . $k]);
		} else {
			$vVr = numeros_validar($_REQUEST['v' . $k]);
		}
		if ($vVr != $_REQUEST['v' . $k]) {
			$sError = 'No fue posible validar el contenido de la variable ' . $aNombres[$k];
		}
	}
}
$sDebug = '';
if ($sError == '') {
	for ($k = 3; $k <= $iNumVariables; $k++){
		switch($k){
			case 31: //Variable tipo texto
				$iVr = cadena_Validar($_REQUEST['v' . $k]);
				break;
			default:
				$iVr = numeros_validar($_REQUEST['v' . $k]);
				break;
		}
		if ($iVr != $_REQUEST['v' . $k]) {
			$sError = 'No fue posible validar el contenido de la variable ' . $k . '';
			$k = $iNumVariables + 1;
		}
	}
}
if ($sError == '') {
	//Validar permisos.
	$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto != '') {
		$objDB->dbPuerto = $APP->dbpuerto;
	}
	list($bEntra, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 6, $idTercero, $objDB);
	if (!$bEntra) {
		$sError = 'No tiene permiso para consultar este reporte [Mod 2301 : 6]';
	}
}
if ($sError == '') {
	if ($_REQUEST['rdebug'] == 1) {
		$bDebug = true;
	}
	$sTituloRpt = 'consolidado_caracterizacion';
	$sFormato = 'formato.xlsx';
	if (!file_exists($sFormato)) {
		$sError = 'Formato no encontrado {' . $sFormato . '}';
	}
}
if ($sError == '') {
	// ------------------------------------------------
	// Leemos los parametros de entrada.
	// ------------------------------------------------
	$cara50idperiodo = numeros_validar($_REQUEST['v3']);
	$cara50idzona = numeros_validar($_REQUEST['v4']);
	$cara50idcentro = numeros_validar($_REQUEST['v5']);
	$core50tipo = numeros_validar($_REQUEST['v6']);
	$cara50poblacion = numeros_validar($_REQUEST['v7']);
	$cara50convenio = numeros_validar($_REQUEST['v8']);
	$core50idescuela = numeros_validar($_REQUEST['v9']);
	$core50idprograma = numeros_validar($_REQUEST['v10']);
	$cara50periodoacomp = numeros_validar($_REQUEST['v11']);
	$cara50periodomatricula = numeros_validar($_REQUEST['v12']);
	$cara50tipomatricula = numeros_validar($_REQUEST['v13']);
	$cara50listadoc = cadena_Validar($_REQUEST['v14']);
	$sSubtitulo = '';
	$sCondi = '';
	$bVerBienV1 = false;
	$bVerBienV2 = false;
	$bVerBienV3 = false;
	$bConPeriodo = false;
	$sWhere = '';
	$sWhereAdd = '';
	$sDetalle = '';
	$sSQLadd = '';
	$sSQLadd1 = '';
	if ($cara50idperiodo != '') {
		$sTituloRpt = $sTituloRpt . 'P' . $cara50idperiodo . '';
		$sNomPeraca = '{' . $cara50idperiodo . '}';
		$sSQL = 'SELECT exte02nombre FROM exte02per_aca WHERE exte02id=' . $cara50idperiodo . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			$sNomPeraca = $fila['exte02nombre'];
		}
		$sDetalle = cadena_codificar('Consolidado de caracterizacion periodo: ' . $sNomPeraca);
		$bConPeriodo = true;
	}
	if ($cara50poblacion == '9') {
		//Es un total, tenemos que limitar la zona...
		list($bEntra, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 12, $idTercero, $objDB);
		if (!$bEntra) {
			if ($cara50idzona != '') {
				//Verificar que la zona sea la que esta solicitando.
				$sSQL = 'SELECT cara21idzona FROM cara21lidereszona WHERE cara21idlider=' . $idTercero . ' AND cara21activo="S" AND cara21idzona=' . $cara50idzona . '';
				$tabla = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($tabla) > 0) {
					//No problema es un zonal y esta consultando su zona.
				} else {
					$sSQLadd1 = $sSQLadd1 . 'TB.cara01idconsejero=' . $_SESSION['unad_id_tercero'] . ' AND ';
				}
			} else {
				//Puede ver lo suyo....
				$sSQLadd1 = $sSQLadd1 . 'TB.cara01idconsejero=' . $_SESSION['unad_id_tercero'] . ' AND ';
			}
		}
	} else {
		$sSQLadd1 = $sSQLadd1 . 'TB.cara01idconsejero=' . $_SESSION['unad_id_tercero'] . ' AND ';
	}
	$bConConsejero = true;
	if ($cara50idcentro != '') {
		$sSQLadd1 = $sSQLadd1 . 'TB.cara01idcead=' . $cara50idcentro . ' AND ';
	} else {
		if ($cara50idzona != '') {
			$sSQLadd1 = $sSQLadd1 . 'TB.cara01idzona=' . $cara50idzona . ' AND ';
		}
	}
	$bPorTipo = false;
	if ($core50tipo != '') {
		$sSQLadd1 = $sSQLadd1 . 'TB.cara01tipocaracterizacion=' . $core50tipo . ' AND ';
		$bPorTipo = true;
		//Definimos de una vez el tipo de bloques.
		for ($k = 2; $k < 8; $k++) {
			$aBloque[$k] = false;
		}
		//Traer el tipo de caracterizacion para ver si tiene alguna pregunta, si no tiene pues se quita el bloque.
		$sSQL = 'SELECT cara11nombre, cara11fichafamilia, cara11ficha1, cara11ficha2, cara11ficha3, cara11ficha4, cara11ficha5, cara11ficha6, cara11ficha7 FROM cara11tipocaract WHERE cara11id=' . $core50tipo . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			if ($fila['cara11fichafamilia'] == 'S') {
				for ($k = 2; $k < 7; $k++) {
					$aBloque[$k] = true;
				}
			}
			if ($fila['cara11ficha1'] == 'S') {
				$aBloque[7] = true;
			}
			if ($fila['cara11ficha2'] == 'S') {
				$aBloque[7] = true;
			}
			if ($fila['cara11ficha3'] == 'S') {
				$aBloque[7] = true;
			}
			if ($fila['cara11ficha4'] == 'S') {
				$aBloque[7] = true;
			}
			if ($fila['cara11ficha5'] == 'S') {
				$aBloque[7] = true;
			}
			if ($fila['cara11ficha6'] == 'S') {
				$aBloque[7] = true;
			}
			if ($fila['cara11ficha7'] == 'S') {
				$aBloque[7] = true;
			}
		}
	}
	$sTablaConvenio = '';
	if ($cara50convenio != '') {
		$sTablaConvenio = ', core51convenioest AS T51';
		$sSQLadd1 = $sSQLadd1 . 'TB.cara01idtercero=T51.core51idtercero AND T51.core51idconvenio=' . $cara50convenio . ' AND T51.core51activo="S" AND ';
	}
	if ($cara50periodoacomp != '') {
		$sTituloRpt = $sTituloRpt . 'ACOMP' . $cara50periodoacomp . '';
		$sNomPeraca = '{' . $cara50periodoacomp . '}';
		$sSQL = 'SELECT exte02nombre FROM exte02per_aca WHERE exte02id=' . $cara50periodoacomp . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			$sNomPeraca = $fila['exte02nombre'];
		}
		$sDetalle = cadena_codificar('Periodo de acompañamiento: ' . $sNomPeraca);
		$bConPeriodo = true;
	}
	//28 - Abril - 2022 - Se agregaron las variables.
	if ($cara50periodomatricula != '') {
		$sTituloRpt = $sTituloRpt . 'MAT' . $cara50periodomatricula . '';
		$sNomPeraca = '{' . $cara50periodomatricula . '}';
		$sSQL = 'SELECT exte02nombre FROM exte02per_aca WHERE exte02id=' . $cara50periodomatricula . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			$sNomPeraca = $fila['exte02nombre'];
		}
		$sAddTitulo = '';
		$sCondi16 = '';
		$bTotalMatricula = false;
		switch ($cara50tipomatricula) {
			case '':
				break;
			case '0':
				$sAddTitulo = ' antiguos';
				$sCondi16 = ' AND core16nuevo=0';
				$sTituloRpt = $sTituloRpt . 'ANT';
				break;
			case 1:
				$sAddTitulo = ' nuevos';
				$sCondi16 = ' AND core16nuevo=1';
				$sTituloRpt = $sTituloRpt . 'NUEVO';
				$bTotalMatricula = true;
				break;
			case 2:
				$sAddTitulo = ' de reintegro';
				$sCondi16 = ' AND core16nuevo=2';
				$sTituloRpt = $sTituloRpt . 'REIN';
				break;
		}
		$sDetalle = cadena_codificar('Estudiantes' . $sAddTitulo . ' matriculados en el periodo: ' . $sNomPeraca);
		//
		$sIds = '-99';
		$sSQL = 'SELECT core16tercero FROM core16actamatricula WHERE core16peraca=' . $cara50periodomatricula . $sCondi16 . '';
		$tabla = $objDB->ejecutasql($sSQL);
		while ($fila = $objDB->sf($tabla)) {
			$sIds = $sIds . ',' . $fila['core16tercero'];
		}
		if ($bTotalMatricula) {
			$sSQLadd1 = $sSQLadd1 . 'TB.cara01idtercero IN (' . $sIds . ') AND ';
		} else {
			//Aqui la cosa cambia, porque tenemos que traer solo la ultima encuesta...
			$sIds01 = '-99';
			$sSQL = 'SELECT cara01id, cara01idtercero 
			FROM cara01encuesta 
			WHERE cara01idperaca<=' . $cara50periodomatricula . ' AND cara01idtercero IN (' . $sIds . ') AND cara01completa="S"
			ORDER BY cara01idtercero, cara01idperaca';
			$tabla = $objDB->ejecutasql($sSQL);
			$idTercero = -99;
			while ($fila = $objDB->sf($tabla)) {
				if ($idTercero != $fila['cara01idtercero']) {
					$sIds01 = $sIds01 . ',' . $fila['cara01id'];
					$idTercero = $fila['cara01idtercero'];
				}
			}
			$sSQLadd1 = $sSQLadd1 . 'TB.cara01id IN (' . $sIds01 . ') AND ';
		}
		$bConPeriodo = true;
	}
	if ($core50idprograma != '') {
		$sSQLadd1 = $sSQLadd1 . 'TB.cara01idprograma=' . $core50idprograma . ' AND ';
	} else {
		if ($core50idescuela != '') {
			$sSQLadd1 = $sSQLadd1 . 'TB.cara01idescuela=' . $core50idescuela . ' AND ';
		}
	}
	if ($cara50idperiodo != '') {
		list($bVerBienV1, $bVerBienV2, $bVerBienV3) = f2301_VerBienestarVersion($cara50idperiodo);
		if ($cara50periodoacomp != '') {
			$sSQLadd1 = $sSQLadd1 . 'TB.cara01idperaca=' . $cara50idperiodo . ' AND TB.cara01idperiodoacompana=' . $cara50periodoacomp . ' AND ';
		} else {
			$sSQLadd1 = '' . $sSQLadd1 . 'TB.cara01idperaca=' . $cara50idperiodo . ' AND ';
		}
	} else {
		if ($cara50periodoacomp != '') {
			$sSQLadd1 = '' . $sSQLadd1 . 'TB.cara01idperiodoacompana=' . $cara50periodoacomp . ' AND ';
		}
	}
	if ($cara50listadoc != '') {
		$sdatos = '';		
		$cara50listadoc = cadena_limpiar($cara50listadoc, "0123456789\n");
		$sListaDoc = implode('","', array_filter(explode("\n", $cara50listadoc)));
		if ($sListaDoc != '') {
			$sSQL = 'SELECT unad11id FROM unad11terceros WHERE unad11doc IN ("' . $sListaDoc . '")';
			$tabla = $objDB->ejecutasql($sSQL);
			while ($fila = $objDB->sf($tabla)) {
				if ($sdatos != '') {
					$sdatos = $sdatos . ', ';
				}
				$sdatos = $sdatos . $fila['unad11id'];
			}
			if ($sdatos != '') {
				$sSQLadd1 = $sSQLadd1 . 'cara01idtercero IN (' . $sdatos . ') AND ';
			}
		}
	}
	// ------------------------------------------------
	// Fin de las condiciones de la consulta
	// ------------------------------------------------
	$sCampos = 'SELECT * ';
	$sConsulta = 'FROM cara01encuesta AS TB LEFT JOIN cara44encuesta AS T44 ON (TB.cara01id=T44.cara44id)' . $sTablaConvenio . ' 
	WHERE ' . $sSQLadd1 . ' TB.cara01completa="S" ' . $sSQLadd . '';
	$sOrden = '';
	$sSQLReporte = $sCampos . ' ' . $sConsulta . ' ' . $sOrden;
	// ------------------------------------------------
	// Fin de la consulta
	// ------------------------------------------------
	if (!$bConPeriodo) {
		$sError = $ERR['msg_periodo'];
	}
}
if ($sError == '') {
	$sProtocolo = 'http';	if (isset($_SERVER['HTTPS']) != 0) {
		if ($_SERVER['HTTPS'] == 'on') {
			$sProtocolo = 'https';		}
	}
	$sServerRpt = $sProtocolo . '://' . $_SERVER['SERVER_NAME'];
	// - Quien esta descargando el reporte.
	$sNombreUsuario = '[' . $idTercero . ']';
	$sSQL = 'SELECT unad11razonsocial FROM unad11terceros WHERE unad11id=' . $idTercero . '';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$sNombreUsuario = cadena_LimpiarTildes($fila['unad11razonsocial']) . ' [' . $idTercero . ']';
	}
	$objReader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
	$objExcel = $objReader->load($sFormato);
	$objExcel->getProperties()->setCreator($sNombreUsuario . ' - http://www.unad.edu.co');
	$objExcel->getProperties()->setLastModifiedBy($sNombreUsuario . ' - http://www.unad.edu.co');
	$objExcel->getProperties()->setTitle($sTituloRpt);
	$objExcel->getProperties()->setSubject($sTituloRpt);
	$objExcel->getProperties()->setDescription('Reporte 3073 del SII 4.0 en ' . $sServerRpt . ' creado en ' . fecha_hoy() . ' ' . formato_horaminuto(fecha_hora(), fecha_minuto()));
	$objHoja = $objExcel->getActiveSheet();
	$objHoja->setTitle(substr($sTituloRpt, 0, 30));
	$objContenedor = $objHoja;
	$sColTope = 'M';
	//Imagen del encabezado
	$sImagenSuperior = $APP->rutacomun . 'imagenes/rpt_cabeza.jpg';
	PHPExcel_Justificar_Celda_HorizontalCentro($objContenedor, 'A1');
	if (file_exists($sImagenSuperior)) {
		PHPExcel_Agrega_Dibujo($objContenedor, 'Logo', 'Logo', $sImagenSuperior, '161', 'A1', '0',false, '0');
	}
	$sFechaImpreso = formato_fechalarga(fecha_hoy(), true) . ' ' . html_TablaHoraMin(fecha_hora(), fecha_minuto());
	PHPExcel_Texto_Tres_Partes($objContenedor, $sColTope . '9', ' ', 'Fecha impresión: ', $sFechaImpreso, 'AmOsUn', true, false, 9, 'Calibri', 'AzOsUn');
	PHPExcel_Alinear_Celda_Derecha($objContenedor, $sColTope . '9');
	$iFilaHoja1 = 12;
	PHPEXCEL_Escribir($objHoja, 0, $iFilaHoja1, $sTituloRpt);
	PHPExcel_Mexclar_Celdas($objContenedor, 'A' . $iFilaHoja1 . ':' . $sColTope . $iFilaHoja1);
	PHPExcel_Justificar_Celda_HorizontalCentro($objContenedor, 'A' . $iFilaHoja1);
	PHPExcel_Formato_Fuente_Celda($objContenedor, 'A' . $iFilaHoja1, '14', 'Yu Gothic', 'AzOsUn', true, false, false);
	//Espacio para el encabezado
	if ($sSubtitulo != '') {
		$iFilaHoja1++;
		PHPEXCEL_Escribir($objHoja, 0, $iFilaHoja1, $sSubtitulo);
		PHPExcel_Justificar_Celda_HorizontalCentro($objContenedor, 'A' . $iFilaHoja1);
		PHPExcel_Formato_Fuente_Celda($objContenedor, 'A' . $iFilaHoja1, '12', 'Yu Gothic', 'AmOsUn', true, false, false);
		PHPExcel_Mexclar_Celdas($objContenedor, 'A' . $iFilaHoja1 . ':' . $sColTope . $iFilaHoja1);
	}
	if ($sDetalle != '') {
		$iFilaHoja1++;
		PHPEXCEL_Escribir($objHoja, 0, $iFilaHoja1, $sDetalle);
		PHPExcel_Justificar_Celda_HorizontalCentro($objContenedor, 'A' . $iFilaHoja1);
		PHPExcel_Formato_Fuente_Celda($objContenedor, 'A' . $iFilaHoja1, '10', 'Yu Gothic', 'Ne', true, false, false);
		PHPExcel_Mexclar_Celdas($objContenedor, 'A' . $iFilaHoja1 . ':' . $sColTope . $iFilaHoja1);
	}
	PHPExcel_RellenarCeldas($objContenedor, 'A1:' . $sColTope . $iFilaHoja1, 'Bl', false);
	$iFilaHoja1++;
	$iFilaBase = $iFilaHoja1;
	$aTitulos = array(
		'Datos personales', '', '', '', '', 
		'', '', '', '', '', 
		'', '', '', '', '', 
		'', '', '', '', '', 
		'', '', '', 
		'Grupos poblacionales', '', '', '', '', 
		'', '', '', '', '', 
		'', '', '', '', 
		'Discapacidades V 1.', '', '', '', '',
		'Discapacidades V 2.', '', '', '', '', 
		'', '', '', '', '', 
		'', '', '', '', '',
		'', '', 
		'Datos familiares', '', '', '', '', 
		'', '', '', '', '', 
		'', '', 
		'Datos academicos', '', '', '', '',  
		'', '', '', '', '', 
		'', '', '', '', 
		'Con cuales equipos electronicos cuenta para acceder al campus virtual de la UNAD', '', '', '', '',  
		'', '', '', '', '', 
		'', '', '', '', 
		'La informacion que consulta la aprende mejor con', '', '', '', '', 
		'', '', 
		'Datos laborales', '', '', '', '',  
		'', '', '', '', '', 
		'', '', 		
		'Psicosocial', '', '', '', '', 
		'', '', '', '', '', 
		'', 
		'Competencias', '', '', '', '', 
		'', '', '', 
		'Consejero', 
	);
	$aAnchos = array(
		// Datos personales
		13, 13, 13, 13, 13, 
		13, 13, 13, 13, 13, 
		13, 13, 13, 13, 13, 
		13, 13, 13, 13, 13, 
		13, 13, 13, 
		// Grupos poblacionales
		13, 13, 13, 13, 13, 
		13, 13, 13, 13, 13, 
		13, 13, 13, 13,
		// Discapacidades V 1.
		13, 13, 13, 13, 13,
		// Discapacidades V 2.
		13, 13, 13, 13, 13, 
		13, 13, 13, 13, 13, 
		13, 13, 13, 13, 13,
		13, 13, 
		// Datos familiares
		13, 13, 13, 13, 13, 
		13, 13, 13, 13, 13, 
		13, 13, 
		// Datos academicos
		13, 13, 13, 13, 13,  
		13, 13, 13, 13, 13, 
		13, 13, 13, 13, 
		// Con cuales equipos electronicos cuenta para acceder al campus virtual de la UNAD
		13, 13, 13, 13, 13,  
		13, 13, 13, 13, 13, 
		13, 13, 13, 13, 
		// La informacion que consulta la aprende mejor con
		13, 13, 13, 13, 13, 
		13, 13, 
		// Datos laborales
		13, 13, 13, 13, 13,  
		13, 13, 13, 13, 13, 
		13, 13, 
		// Psicosocial
		13, 13, 13, 13, 13, 
		13, 13, 13, 13, 13, 
		13, 
		// Competencias
		13, 13, 13, 13, 13, 
		13, 13, 13, 
		// Consejero
		13, 
	);
	$iTotalCol = count($aTitulos) - 1;
	$sColumna = 'A';
	for ($k = 0; $k <= $iTotalCol; $k++) {
		PHPEXCEL_Escribir($objHoja, $k, $iFilaHoja1, $aTitulos[$k]);
		$sColumna = columna_Letra($k);
		// $objHoja->getColumnDimension($sColumna)->setWidth($aAnchos[$k]);
		PHPExcel_Justificar_Celda_HorizontalIzquierda($objContenedor, $sColumna . $iFilaHoja1);
	}
	PHPExcel_Formato_Fuente_Celda($objContenedor, 'A' . $iFilaHoja1 . ':' . $sColumna . $iFilaHoja1, '11', 'Yu Gothic', 'RoOs', true, false, false);
	$iFilaHoja1++;
	$iFilaBase = $iFilaHoja1;
	$aTitulos = array(
		// Datos personales
		'Tipo Caracterizacion', 'TD', 'Doc', 'Estudiante', 'Ultimo Acceso Campus',  
		'Fecha encuesta', 'Edad', 'Sexo', 'Identidad de genero', 'Orientacion sexual',  
		'Pais', 'Departamento', 'Ciudad', 'Estrato', 'Zona de residencia', 
		'Estado civil', 'Nombre del contacto', 'Parentezco del contacto', 'Zona', 'CEAD', 
		'Escuela', 'Programa', 'Matricula en convenio', 
		// Grupos poblacionales
		'Raizal', 'Palenquero', 'Afrocolombiano', 'Otra comunidad negra', 'ROM',  
		'Indigena', 'Campesinado', 'Frontera', 'Victima desplazado', 'Victima ACR', 
		'Funcionario INPEC', 'Recluso INPEC', 'Tiempo de condena', 'Centro de reclusion', 
		// Discapacidades V 1.
		'Sensorial', 'Fisica', 'Cognitiva', 'Ajustes razonables', 'Ajustes razonables Otra Ayuda', 
		// Discapacidades V 2.
		'Sensorial v2', 'Intelectual', 'Fisica o motora', 'Mental Psicosocial', 'Sistemica', 
		'Sistemica Otro', 'Multiple', 'Multiple Otro', 'Certificado', 'Tiene Trastorno en el aprendizaje', 
		'Trastorno especifico en el aprendizaje', 'Talento Excepcional', 'Pruebas para definir el coeficiente intelectual', 'Con condicion medica', 'Cual condicion medica especifica', 
		'Asiste a algún tipo de terapia', 'Cual tipo de terapia', 
		// Datos familiares
		'Cual es su tipo de vivienda actual', 'Con quien vive actualmente', 'Cuantas personas conforman su grupo familiar incluyendolo a usted', 'Cuantos hijos tiene', 'Es usted madre cabeza de hogar', 
		'Cuantas personas tiene a su cargo', 'Usted depende economicamente de alguien', 'Cual es el maximo nivel de escolaridad de su padre', 'Cual es el maximo nivel de escolaridad de su madre', 'Cuantos hermanos tiene', 
		'Cual es la posicion entre sus hermanos', 'Usted tiene familiares estudiando actualmente o que hayan estudiado en la UNAD', 
		// Datos academicos
		'Tipo de colegio donde termino su bachillerato', 'La modalidad en la que obtuvo su grado de bachiller es', 'Usted ha realizado otros estudios antes de llegar a la UNAD', 'Cual fue el ultimo nivel de estudios cursado', 'Cuanto tiempo lleva sin estudiar', 
		'Obtuvo certificacion o diploma de estos estudios', 'Usted ha tomado cursos virtuales', 'Cual es la principal razon para elegir el programa academico en el que se matriculo', 'El programa en el que se matriculo representa su primera opcion', 'Por favor indique el programa que le hubiera gustado estudiar.', 
		'Cual es la principal razon para estudiar en la UNAD', 'Ha tenido recesos en su proceso formativo', 'La razon del receso academico', 'Otra razon del receso academico', 
		// Con cuales equipos electronicos cuenta para acceder al campus virtual de la UNAD
		'Computador de escritorio', 'Computador portatil', 'Tableta', 'Telefono inteligente', 'El lugar donde reside cuenta con servicio de energia electrica', 
		'El lugar donde reside cuenta con servicio de Internet', 'Ha usado plataformas virtuales con anterioridad', 'Maneja paquetes ofimaticos como Office (Word Excel Powerpoint) o similares', 'Ha participado en foros virtuales', 'Sabe convertir archivos digitales de un formato a otro', 
		'Su uso del correo electronico es', 'El uso del correo electronico institucional de la UNAD es', 'Indique porque no usa el correo institucional', 'Otra razon porque no usa el correo institucional', 
		// La informacion que consulta la aprende mejor con
		'Texto', 'Video', 'Organizadores graficos', 'Animaciones', 'Cual es el medio que mas utiliza para comunicarse con amigos. conocidos. familiares o docentes a traves de internet', 
		'Indique el medio por el cual se ha enterando de las actividades y procesos de la Universidad', 'Otro medio por el cual se ha enterando de las actividades y procesos de la Universidad', 
		// Datos laborales
		'Cual es su situacion laboral actual', 'A que sector economico pertenece', 'Cual es el caracter juridico de la empresa', 'Cual es el cargo que ocupa', 'Cual es su antigüedad en el cargo actual', 
		'Que tipo de contrato tiene actualmente', 'Cuanto suman sus ingresos mensuales', 'Con que tiempo cuenta para desarrollar las actividades academicas', 'Que tipo de empresa es', 'Hace cuanto tiempo constituyo su empresa', 
		'Debe buscar trabajo para continuar sus estudios en la UNAD', 'De donde provienen los recursos economicos con los que usted estudiara en la UNAD', 
		// Psicosocial
		'Le cuesta expresar sus emociones con palabras', 'Como reacciona ante un cambio imprevisto aparentemente negativo', 'Cuando esta estresado o tienes varias preocupaciones ¿como lo maneja', 'Cuando tiene poco tiempo para el desarrollo de sus actividades academicas laborales y familiares ¿como lo asume?', 'Con respecto a su actitud frente la vida ¿como se describiria?', 
		'Que hace cuando presenta alguna dificultad o duda frente a una tarea asignada', 'Cuando esta afrontando una dificultad personal laboral emocional o familiar ¿cual es su actitud?', 'En terminos generales ¿esta satisfecho con quien es?', 'Como actua frente a una discusion', 'Como reacciona ante las siguientes situaciones sociales', 
		'Puntaje', 
		// Competencias
		'Competencias digitales', 'Lectura critica', 'Razonamiento cuantitativo', 'Ingles', 'Biologia',  
		'Fisica', 'Quimica', 'Competencias ciudadanas', 
		// Consejero
		'Consejero', 
	);
	$sColumna = 'A';
	for ($k = 0; $k <= $iTotalCol; $k++) {
		PHPEXCEL_Escribir($objHoja, $k, $iFilaHoja1, $aTitulos[$k]);
		$sColumna = columna_Letra($k);
		// $objHoja->getColumnDimension($sColumna)->setWidth($aAnchos[$k]);
		PHPExcel_Justificar_Celda_HorizontalIzquierda($objContenedor, $sColumna . $iFilaHoja1);
	}
	//PHPExcel_Mexclar_Celdas($objContenedor, 'A' . $iFilaHoja1 . ':B' . $iFilaHoja1 . '');
	PHPExcel_Formato_Fuente_Celda($objContenedor, 'A' . $iFilaHoja1 . ':' . $sColumna . $iFilaHoja1, '10', 'Yu Gothic', 'Ne', true, false, false);
	PHPExcel_RellenarCeldas($objHoja, 'A1:' . $sColumna . $iFilaHoja1, 'Bl', false);
	$iFilaHoja1++;

	$objExcel->createSheet();
	$objExcel->setActiveSheetIndex(1);
	$objHoja2 = $objExcel->getActiveSheet();
	$objHoja2->setTitle('Bienestar V 1.');
	// $objHoja->setCellValueByColumnAndRow(0, 1, 'Bienestar Version 1.');
	// $objHoja->getStyleByColumnAndRow(0, 1)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	$sColTope2='M';
	$iFilaHoja2 = 1;
	PHPEXCEL_Escribir($objHoja2, 0, $iFilaHoja2, 'Bienestar Version 1.');
	PHPExcel_Mexclar_Celdas($objHoja2, 'A' . $iFilaHoja2 . ':' . $sColTope2 . $iFilaHoja2);
	PHPExcel_Justificar_Celda_HorizontalCentro($objHoja2, 'A' . $iFilaHoja2);
	PHPExcel_Formato_Fuente_Celda($objHoja2, 'A' . $iFilaHoja2, '14', 'Yu Gothic', 'AzOsUn', true, false, false);
	$iFilaHoja2++;
	$iFilaBase2 = $iFilaHoja2;
	$aTitulos = array(
		'', '', '', 
		'Deporte y Recreacion - Usted practica regularmente alguna de las siguientes actividades deportivas o recreativas', '', '', '', '', 
		'', '', '', '', 
		'Usted practica regularmente alguna de las siguientes actividades artisticas o culturales', '', '', '', '', 
		'', '', '', '', '', 
		'Si usted practica danza por favor indique el genero', '', '', '',  
		'Emprendimiento', '', '', 
		'Estilo de vida saludable', '', 
		'Proyecto de vida', 
		'Medio ambiente', 
		'Cual de estos habitos cotidianos realiza usted como una practica de respeto hacia Medio Ambiente', '', '', '', 
	);
	$aAnchos = array(
		15, 15, 15, 
		// Deporte y Recreacion
		// Usted practica regularmente alguna de las siguientes actividades deportivas o recreativas
		15, 15, 15, 15, 15, 
		15, 15, 15, 15, 
		// Usted practica regularmente alguna de las siguientes actividades artisticas o culturales
		15, 15, 15, 15, 15, 
		15, 15, 15, 15, 15, 
		// Si usted practica danza por favor indique el genero
		15, 15, 15, 15, 
		// Emprendimiento
		15, 15, 15, 
		// Estilo de vida saludable
		20, 20, 
		// Proyecto de vida
		30, 
		// Medio ambiente
		30, 
		// Cual de estos habitos cotidianos realiza usted como una practica de respeto hacia Medio Ambiente
		15, 15, 15, 15, 
	);
	$iTotalCol = count($aTitulos) - 1;
	$sColumna2 = 'A';
	for ($k = 0; $k <= $iTotalCol; $k++) {
		PHPEXCEL_Escribir($objHoja2, $k, $iFilaHoja2, $aTitulos[$k]);
		$sColumna2 = columna_Letra($k);
		// $objHoja2->getColumnDimension($sColumna2)->setWidth($aAnchos[$k]);
		PHPExcel_Justificar_Celda_HorizontalIzquierda($objHoja2, $sColumna2 . $iFilaHoja2);
	}
	PHPExcel_Formato_Fuente_Celda($objHoja2, 'A' . $iFilaHoja2 . ':' . $sColumna2 . $iFilaHoja2, '11', 'Yu Gothic', 'RoOs', true, false, false);
	PHPExcel_RellenarCeldas($objHoja2, 'A1:' . $sColumna2 . $iFilaHoja2, 'Bl', true);
	$iFilaHoja2++;
	$iFilaBase2 = $iFilaHoja2;
	$aTitulos = array(
		'TD', 'Doc', 'Estudiante', 
		// Deporte y Recreacion
		// Usted practica regularmente alguna de las siguientes actividades deportivas o recreativas
		'Baloncesto', 'Voleibol', 'Futbol sala', 'Artes marciales', 'Tenis de mesa', 
		'Ajedrez', 'Juegos autoctonos', 'Esta interesado en hacer parte de un grupo representativo en deportes', 'Especifique a cual grupo deportivo', 
		// Usted practica regularmente alguna de las siguientes actividades artisticas o culturales
		'Teatro', 'Danza', 'Musica', 'Circo', 'Artes plasticas',  
		'Cuenteria', 'Esta interesado en hacer parte de un grupo representativo en artes y cultura', 'Seleccione en cual', 'Si usted interpreta un instrumento musical por favor seleccionelo', 'En escala de 1 a 10 su dominio del instrumento musical es',  
		// Si usted practica danza por favor indique el genero
		'Ritmos modernos (Salsa - Bachata)', 'Danza clasica', 'Danza contemporanea', 'Danza folklorica colombiana', 
		// Emprendimiento
		'Cuenta Ud. con una empresa que de respuesta a una necesidad social en su comunidad', 'Que necesidad cubre', 'En que temas de emprendimiento le gustaria recibir capacitacion', 
		// Estilo de vida saludable
		'Cuales cree que son las causas mas frecuentes del estres', 'A traves de que estrategias le gustaria conocer el autocuidado', 
		// Proyecto de vida
		'Que temas le gustaria abordar en la UNAD para su crecimiento personal', 
		// Medio ambiente
		'Como define la educacion ambiental', 
		// Cual de estos habitos cotidianos realiza usted como una practica de respeto hacia Medio Ambiente
		'Ahorras de agua en la ducha y/o al cepillarse', 'Usas bombillas ahorradoras', 'Desconectas el cargador del celular cuando no esta en uso', 'Apagas las luces que no se requieran', 
	);
	$sColumna2 = 'A';
	for ($k = 0; $k <= $iTotalCol; $k++) {
		PHPEXCEL_Escribir($objHoja2, $k, $iFilaHoja2, $aTitulos[$k]);
		$sColumna2 = columna_Letra($k);
		// $objHoja2->getColumnDimension($sColumna2)->setWidth($aAnchos[$k]);
		PHPExcel_Justificar_Celda_HorizontalIzquierda($objHoja2, $sColumna2 . $iFilaHoja2);
	}
	PHPExcel_Formato_Fuente_Celda($objHoja2, 'A' . $iFilaHoja2 . ':' . $sColumna2 . $iFilaHoja2, '10', 'Yu Gothic', 'Ne', true, false, false);
	$iFilaHoja2++;

	
	$objExcel->createSheet();
	$objExcel->setActiveSheetIndex(2);
	$objHoja3 = $objExcel->getActiveSheet();
	$objHoja3->setTitle('Bienestar V 2.');
	$sColTope3='M';
	$iFilaHoja3 = 1;
	PHPEXCEL_Escribir($objHoja3, 0, $iFilaHoja3, 'Bienestar Version 2.');
	PHPExcel_Mexclar_Celdas($objHoja3, 'A' . $iFilaHoja3 . ':' . $sColTope3 . $iFilaHoja3);
	PHPExcel_Justificar_Celda_HorizontalCentro($objHoja3, 'A' . $iFilaHoja3);
	PHPExcel_Formato_Fuente_Celda($objHoja3, 'A' . $iFilaHoja3, '14', 'Yu Gothic', 'AzOsUn', true, false, false);
	$iFilaHoja3++;
	$iFilaBase3 = $iFilaHoja3;
	$aTitulos = array(
		'', '', '', 
		'Deporte y Recreacion', 
		'¿Que deporte practica?', '', '', '', '', 
		'', '', '', '', '', 
		'Arte y Cultura - Usted practica regularmente alguna de las siguientes actividades artisticas o culturales', '', '', '', '', 
		'', '', 
		'A que clase de eventos artisticos y culturales le gustaria asistir', '', '', '', '', 
		'', '', '', '', 
		'Emprendimiento', '', 
		'Cual es el estado en que se encuentra su emprendimiento', '', '', '', '',  
		'', '', '',  
		'En que temas le gustaria recibir informacion con respecto al emprendimiento', '', '', '', 
		'Estilo de vida saludable - Causas mas frecuentes del estres', '', '', '', '', 
		'Estrategias para conocer el autocuidado', '', '', '', 
		'Crecimiento Personal - Temas de interes para su crecimiento personal', '', '', '', '', 
		'', '', '', 
		'Le gustaria hacer parte de algun grupo de bienestar', '', '', '', '', 
		'Medio ambiente - Realiza alguna de estas acciones frente al cuidado del medio ambiente', '', '', '', '', 
		'', '', '', '', 
		'En su tiempo libre ha participado en alguna actividad ambiental', '', '', '', '', 
		'',  
		'Cual tema desde el enfoque ambiental le gustaria conocer o profundizar', '', '', '', '', 
		'', '', '', '', '', 
		'', '', '', '', '', 
	);
	$aAnchos = array(
		15, 15, 15, 
		// Deporte y Recreacion
		15, 
		// ¿Que deporte practica?
		15, 15, 15, 15, 15, 
		15, 15, 15, 15, 15, 
		// Arte y Cultura
		// Usted practica regularmente alguna de las siguientes actividades artisticas o culturales
		15, 15, 15, 15, 15, 
		15, 15, 
		// A que clase de eventos artisticos y culturales le gustaria asistir
		15, 15, 15, 15, 15, 
		15, 15, 15, 15, 
		// Emprendimiento
		15, 15, 
		// Cual es el estado en que se encuentra su emprendimiento
		15, 15, 15, 15, 15,  
		15, 15, 15,  
		// En que temas le gustaria recibir informacion con respecto al emprendimiento
		15, 15, 15, 15, 
		// Estilo de vida saludable
		// Causas mas frecuentes del estres
		15, 15, 15, 15, 15, 
		// Estrategias para conocer el autocuidado
		15, 15, 15, 15, 
		// Crecimiento Personal
		// Temas de interes para su crecimiento personal
		15, 15, 15, 15, 15, 
		15, 15, 15, 
		// Le gustaria hacer parte de algun grupo de bienestar
		15, 15, 15, 15, 15, 
		// Medio ambiente
		// Realiza alguna de estas acciones frente al cuidado del medio ambiente
		15, 15, 15, 15, 15, 
		15, 15, 15, 15, 
		// En su tiempo libre ha participado en alguna actividad ambiental
		15, 15, 15, 15, 15, 
		15,  
		// Cual tema desde el enfoque ambiental le gustaria conocer o profundizar
		15, 15, 15, 15, 15, 
		15, 15, 15, 15, 15, 
		15, 15, 15, 15, 15, 
	);
	$iTotalCol = count($aTitulos) - 1;
	$sColumna3 = 'A';
	for ($k = 0; $k <= $iTotalCol; $k++) {
		PHPEXCEL_Escribir($objHoja3, $k, $iFilaHoja3, $aTitulos[$k]);
		$sColumna3 = columna_Letra($k);
		// $objHoja3->getColumnDimension($sColumna3)->setWidth($aAnchos[$k]);
		PHPExcel_Justificar_Celda_HorizontalIzquierda($objHoja3, $sColumna3 . $iFilaHoja3);
	}
	PHPExcel_Formato_Fuente_Celda($objHoja3, 'A' . $iFilaHoja3 . ':' . $sColumna3 . $iFilaHoja3, '11', 'Yu Gothic', 'RoOs', true, false, false);
	PHPExcel_RellenarCeldas($objHoja3, 'A1:' . $sColumna3 . $iFilaHoja3, 'Bl', true);
	$iFilaHoja3++;
	$iFilaBase3 = $iFilaHoja3;
	$aTitulos = array(
		'TD', 'Doc', 'Estudiante', 
		// Deporte y Recreacion
		'Es usted deportista de alto rendimiento o de competencia profesional', 
		// ¿Que deporte practica?
		'Atletismo', 'Baloncesto', 'Futbol', 'Gimnasia', 'Natacion', 
		'Voleibol', 'Tenis', 'Paralimpico', 'Otro deporte', 'Cual deporte', 
		// Arte y Cultura
		// Usted practica regularmente alguna de las siguientes actividades artisticas o culturales
		'Danza', 'Musica', 'Teatro (circo)', 'Artes plasticas (pintura, dibujo, escultura, grabado, fotografia, entre otras)', 'Literatura (Poesia, cuenteria, escritura, etc)', 
		'Otra actividad', 'Cual actividad', 
		// A que clase de eventos artisticos y culturales le gustaria asistir
		'Festivales Folcloricos', 'Exposiciones de Arte', 'Historia del Arte', 'Galeria Fotografica', 'Literatura',  
		'Teatro', 'Cine', 'Otro evento', 'Cual evento',  
		// Emprendimiento
		'Tengo un emprendimiento', 'Tengo una empresa', 
		// Cual es el estado en que se encuentra su emprendimiento
		'Mi emprendimiento se encuentra en marcha, pero busco recursos para avanzar.', 'Mi emprendimiento se encuentra en marcha, pero busco incrementar mis conocimientos para avanzar', 'Tengo una idea para emprender, pero no se como formular el plan de negocio y/o no se como iniciar su ejecucion.', 'Tengo un plan de negocio formulado con objetivos claros, el alcance, los recursos y las actividades, pero no tengo claro como iniciar su ejecucion.', 'No me interesa emprender por ahora, pero me interesa fortalecer mis conocimientos y habilidades.', 
		'Me interesa emprender, pero no tengo identificado el problema o necesidad en el mercado.', 'Otro estado', 'Cual estado', 
		// En que temas le gustaria recibir informacion con respecto al emprendimiento
		'Marketing Digital', 'Plan de negocios', 'Como generar ideas de negocio', 'Creacion de empresa desde lo legal', 
		// Estilo de vida saludable
		// Causas mas frecuentes del estres
		'Factores Economicos', 'Preocupaciones constantes', 'Consumir sustancias psicoactivas o relajantes', 'Complicaciones del Insomnio', 'Clima Laboral',  
		// Estrategias para conocer el autocuidado
		'Alimentacion', 'Autocuidado emocional', 'Estado de Salud', 'Meditacion', 
		// Crecimiento Personal
		// Temas de interes para su crecimiento personal
		'Educacion Sexual', 'Cultura Ciudadana', 'Relacion de Pareja', 'Relaciones Interpersonales', 'Dinamicas Familiares y formacion Integral para los Hijos', 
		'Autoestima', 'Inclusion y Diversidad', 'Regulacion e Inteligencia Emocional', 
		// Le gustaria hacer parte de algun grupo de bienestar
		'Cultural', 'Artistico', 'Deportivo', 'Ambiental', 'Crecimiento Personal (Fortalecer habilidades Socioemocionales)', 
		// Medio ambiente
		// Realiza alguna de estas acciones frente al cuidado del medio ambiente
		'Separo la basura.', 'Uso productos que puedan reutilizarse', 'Apago las luces.', 'Consumo frutas y verduras ecologicas.', 'Evito dejar los aparatos enchufados.', 
		'Cierro los grifos correctamente.', 'Uso bicicleta.', 'Me muevo en transporte publico.', 'Ducha de 5 minutos.', 
		// En su tiempo libre ha participado en alguna actividad ambiental
		'Caminatas ecologicas', 'Siembra de arboles', 'Conferencias de temas ambientales', 'Campañas de reciclaje', 'Otra actividad', 
		'Cual actividad', 
		// Cual tema desde el enfoque ambiental le gustaria conocer o profundizar
		'Reforestacion', 'Movilidad y medio ambiente', 'Cambio Climatico', 'Ecofeminismo', 'Biodiversidad', 
		'Que es Ecologia', 'Economia Circular', 'Recursos naturales', 'Reciclaje', 'Tenencia responsable de mascotas', 
		'Cartografia Humana', 'Valor espiritual y religioso de la naturaleza', 'Capacidad de carga del medio ambiente', 'Otro tema', 'Cual tema', 
	);
	$sColumna3 = 'A';
	for ($k = 0; $k <= $iTotalCol; $k++) {
		PHPEXCEL_Escribir($objHoja3, $k, $iFilaHoja3, $aTitulos[$k]);
		$sColumna3 = columna_Letra($k);
		// $objHoja3->getColumnDimension($sColumna3)->setWidth($aAnchos[$k]);
		PHPExcel_Justificar_Celda_HorizontalIzquierda($objHoja3, $sColumna3 . $iFilaHoja3);
	}
	PHPExcel_Formato_Fuente_Celda($objHoja3, 'A' . $iFilaHoja3 . ':' . $sColumna3 . $iFilaHoja3, '10', 'Yu Gothic', 'Ne', true, false, false);
	$iFilaHoja3++;
	
	$objExcel->createSheet();
	$objExcel->setActiveSheetIndex(3);
	$objHoja4 = $objExcel->getActiveSheet();
	$objHoja4->setTitle('Bienestar V 3.');
	$sColTope4='M';
	$iFilaHoja4 = 1;
	PHPEXCEL_Escribir($objHoja4, 0, $iFilaHoja4, 'Bienestar Version 3.');
	PHPExcel_Mexclar_Celdas($objHoja4, 'A' . $iFilaHoja4 . ':' . $sColTope4 . $iFilaHoja4);
	PHPExcel_Justificar_Celda_HorizontalCentro($objHoja4, 'A' . $iFilaHoja4);
	PHPExcel_Formato_Fuente_Celda($objHoja4, 'A' . $iFilaHoja4, '14', 'Yu Gothic', 'AzOsUn', true, false, false);
	$iFilaHoja4++;
	$iFilaBase4 = $iFilaHoja4;
	$aTitulos = array(
		'', '', '', 
		'Emprendimiento Solidario', '', '', '', '',  
		'',  
		'Medio Ambiente - En que tematica le gustaria participar?', '', '', '', '',  
		'', '', '', '', '',  
		'', '', '',  
		'Promocion de la Salud y Prevencion de la Enfermedad', '', '', '', '',
		'Deporte y Recreacion', '', '', '', '', 
    	'', 
		'Crecimiento Personal - Cuales temas son de su interes para fortalecer su crecimiento personal?', '', '', '', '', 
    	'', '', 
		'Que habilidades considera que le ayudarian a desarrollar su maximo potencial?', '', '', '', '', 
		'', '', '', '', '', 
		'', '', '', 
		'Que lo motiva a seguir buscando su crecimiento personal?', '', '', '', '', 
		'', '', 
		'Salud Mental', '', 
		'Seleccione temas de interes para el cuidado de su Salud Mental', '', '', '', '', 
		'', '', '', '', '', 
		'', '', '', '', '', 
		'', 
		'Arte y Cultura', '', '', '', 
	);
	$aAnchos = array(
		15, 15, 15, 
		// Emprendimiento Solidario
		15, 15, 15, 15, 15, 
		15, 
		// Medio Ambiente
		// En que tematica le gustaria participar?
		15, 15, 15, 15, 15, 
		15, 15, 15, 15, 15, 
		15, 15, 15, 
		// Promocion de la Salud y Prevencion de la Enfermedad
		15, 15, 15, 15, 15, 
		// Deporte y Recreacion
		15, 15, 15, 15, 15, 
    	15, 
		// Crecimiento Personal
		// Cuales temas son de su interes para fortalecer su crecimiento personal?
		15, 15, 15, 15, 15, 
    	15, 15, 
		// Que habilidades considera que le ayudarian a desarrollar su maximo potencial?
		15, 15, 15, 15, 15, 
		15, 15, 15, 15, 15, 
		15, 15, 15, 
		// Que lo motiva a seguir buscando su crecimiento personal?
		15, 15, 15, 15, 15, 
		15, 15, 
		// Salud Mental
		15, 15, 
		// Seleccione temas de interes para el cuidado de su Salud Mental
		15, 15, 15, 15, 15, 
		15, 15, 15, 15, 15, 
		15, 15, 15, 15, 15, 
		15, 
		// Arte y Cultura
		15, 15, 15, 15, 
	);
	$iTotalCol = count($aTitulos) - 1;
	$sColumna4 = 'A';
	for ($k = 0; $k <= $iTotalCol; $k++) {
		PHPEXCEL_Escribir($objHoja4, $k, $iFilaHoja4, $aTitulos[$k]);
		$sColumna4 = columna_Letra($k);
		// $objHoja4->getColumnDimension($sColumna4)->setWidth($aAnchos[$k]);
		PHPExcel_Justificar_Celda_HorizontalIzquierda($objHoja4, $sColumna4 . $iFilaHoja4);
	}
	PHPExcel_Formato_Fuente_Celda($objHoja4, 'A' . $iFilaHoja4 . ':' . $sColumna4 . $iFilaHoja4, '11', 'Yu Gothic', 'RoOs', true, false, false);
	PHPExcel_RellenarCeldas($objHoja4, 'A1:' . $sColumna4 . $iFilaHoja4, 'Bl', true);
	$iFilaHoja4++;
	$iFilaBase4 = $iFilaHoja4;
	$aTitulos = array(
		'TD', 'Doc', 'Estudiante', 
		// Emprendimiento Solidario
		'Etapa en la que se encuentra su emprendimiento', 'Cual es su necesidad actual?', 'Hace cuantos años su emprendimiento y/o empresa inicio operaciones en el mercado?', 'Sector economico al que pertenece su idea de negocio, emprendimiento o empresa o al que se dedica desde su quehacer como intraemprendedor.', 'Otro',  
		'En cual de los siguientes temas le gustaria fortalecer principalmente su aprendizaje?',  
		// Medio Ambiente
		// En que tematica le gustaria participar?
		'Cambio climatico', 'Justicia ambiental y eco-pedagogia', 'Agroecologia y soberania alimentaria', 'Economia circular y residuos solidos', 'Educacion ambiental comunitaria',  
		'Biodiversidad y conservacion', 'Ecoturismo sostenible', 'Otro', 'Otra tematica', 'Le gustaria ser parte de grupos ambientales de la UNAD, que promuevan el fortalecimiento de la cultura ecologica, prevencion y reduccion de impactos negativos ambientales a traves de la implementacion de experiencias ambientales?',  
		'Que tipo de experiencias eco-pedagogicas consideras mas efectivas para tu aprendizaje ambiental?', 'Como considera que el Sistema de Bienestar podria contribuir a mejorar tu bienestar ambiental como estudiante?', 'Ha participado en actividades o proyectos ambientales dentro o fuera de la universidad?',  
		// Promocion de la Salud y Prevencion de la Enfermedad
		'Que experiencias de Bienestar le motivarian para mejorar sus habitos alimenticios?', 'Que tipo de estrategia considera util para prevenir el consumo de sustancias psicoactivas?', 'Que apoyo le gustaria recibir en relacion con su salud visual?', 'Que experiencias consideraria utiles para fortalecer su salud bucal?', 'Que temas considera mas importantes para el cuidado de la salud sexual y reproductiva?', 
		// Deporte y Recreacion
		'Cual es su nivel de practica deportiva?', 'Con que frecuencia practica usted deporte?', 'Que deporte practica?', 'Cual otro deporte?', 'Que tipo de experiencias recreativas le gustaria que promoviera Bienestar?', 
	    'Quisiera hacer parte de un equipo representativo de la Universidad?', 
		// Crecimiento Personal
		// Cuales temas son de su interes para fortalecer su crecimiento personal?
		'Ciudadania, inclusion y diversidad', 'Familia y relaciones afectivas', 'Habilidades para fortalecer su practica academica', 'Empleabilidad y desarrollo profesional', 'Habilidades para la vida (comunicacion, toma de decisiones, etc.)', 
	    'Espiritualidad', 'Formacion practica para la vida (cocina, belleza, costura, herramientas digitales)', 
		// Que habilidades considera que le ayudarian a desarrollar su maximo potencial?
		'Liderazgo', 'Trabajo en equipo', 'Comunicacion asertiva', 'Planeacion y gestion del tiempo', 'Manejo de conflictos', 
		'Adaptacion al cambio', 'Empatia', 'Gestion del ser', 'Toma de decisiones', 'Pensamiento creativo', 
		'Pensamiento critico', 'Otro', 'Otra Habilidad', 
		// Que lo motiva a seguir buscando su crecimiento personal?
		'Deseo de alcanzar metas personales, profesionales y laborales', 'Satisfaccion personal de aprender y crecer', 'Adquirir habilidades para acceder al mundo laboral', 'Otra motivacion', 'Cual otra motivacion', 
		'Recibe apoyo de su familia o entorno cercano en su proceso academico?', 'Que tan preparado/a se siente para enfrentar el mundo laboral?', 
		// Salud Mental
		'Que importancia le da al cuidado de su salud mental actualmente?', 'Que le motiva a aprender estrategias de cuidado a la salud mental?', 
		// Seleccione temas de interes para el cuidado de su Salud Mental
		'Manejo del estres', 'Ansiedad y tecnicas de regulacion', 'Depresion y prevencion del malestar emocional', 'Autoconocimiento y desarrollo personal', 'Inteligencia emocional y Mindfulness', 
		'Autoestima y autoconfianza', 'Afrontamiento de crisis o duelos', 'Prevencion del burnout (agotamiento emocional)', 'Sexualidad y salud mental', 'Uso saludable de redes sociales y tecnologia', 
		'Inclusion y equidad', 
		'Que espera obtener al participar en actividades de salud mental?', 'Le gustaria recibir acompañamiento psicosocial en la UNAD?', 'Alguna vez un profesional de la salud le ha dado un diagnostico relacionado con salud mental?', 'cuál fue el diagnostico recibido?', 'Cual otro diagnostico', 
		// Arte y Cultura
		'De que manera le gustaria integrar el arte y la cultura en su vida universitaria?', 'en que aspecto le gustaria recibir formacion?', 'Le gustaria pertenecer a un grupo representativo de la UNAD?', 'Acorde a sus intereses les gustaria recibir informacion sobre:', 
	);
	$sColumna4 = 'A';
	for ($k = 0; $k <= $iTotalCol; $k++) {
		PHPEXCEL_Escribir($objHoja4, $k, $iFilaHoja4, $aTitulos[$k]);
		$sColumna4 = columna_Letra($k);
		// $objHoja4->getColumnDimension($sColumna4)->setWidth($aAnchos[$k]);
		PHPExcel_Justificar_Celda_HorizontalIzquierda($objHoja4, $sColumna4 . $iFilaHoja4);
	}
	PHPExcel_Formato_Fuente_Celda($objHoja4, 'A' . $iFilaHoja4 . ':' . $sColumna4 . $iFilaHoja4, '10', 'Yu Gothic', 'Ne', true, false, false);
	$iFilaHoja4++;

	$objExcel->createSheet();
	$objExcel->setActiveSheetIndex(4);
	$objHoja5 = $objExcel->getActiveSheet();
	$objHoja5->setTitle('DyVBG');
	$sColTope5='M';
	$iFilaHoja5 = 1;
	PHPEXCEL_Escribir($objHoja5, 0, $iFilaHoja5, 'Discriminación y Violencias Basadas en Género');
	PHPExcel_Mexclar_Celdas($objHoja5, 'A' . $iFilaHoja5 . ':' . $sColTope5 . $iFilaHoja5);
	PHPExcel_Justificar_Celda_HorizontalCentro($objHoja5, 'A' . $iFilaHoja5);
	PHPExcel_Formato_Fuente_Celda($objHoja5, 'A' . $iFilaHoja5, '14', 'Yu Gothic', 'AzOsUn', true, false, false);
	$iFilaHoja5++;
	$iFilaBase5 = $iFilaHoja5;
	$aTitulos = array( // son 12 preguntas
		'TD', 'Doc', 'Estudiante', 
		'Considero importante que las instituciones de educación superior cuenten con acciones para prevenir la discriminación y las violencias basadas en género (DyVBG).', 
		'Me genera confianza que existan mecanismos institucionales para atender situaciones relacionadas con discriminación y violencias basadas en género (DyVBG) y el acoso sexual.', 
		'Resulta necesario que las instituciones de educación superior desarrollen acciones de prevención y formación frente a la discriminación y las violencias basadas en género (DyVBG) y el acoso sexual.', 
		'Ante una situación de discriminación y violencias basadas en género (DyVBG) o acoso sexual, acudiría a los canales institucionales de atención y reporte.', 
		'En mis experiencias educativas previas se promovieron acciones de respeto y prevención frente a la discriminación y las violencias basadas en género (DyVBG) y el acoso sexual.', 
		'Es importante que las instituciones de educación superior implementen medidas de protección y atención para las personas afectadas por situaciones de discriminación y violencias basadas en género (DyVBG) y acoso sexual.', 
		'Es fundamental contribuir a la construcción de ambientes educativos libres de discriminación y violencias basadas en género (DyVBG) y acoso sexual.', 
		'Resulta necesario que las instituciones de educación superior promuevan ambientes de respeto, inclusión y convivencia libres de discriminación y violencias basadas en género (DyVBG) y acoso sexual.', 
		'La discriminación y las violencias basadas en género (DyVBG) y el acoso sexual pueden afectar el bienestar emocional, la salud mental y el desempeño académico de las personas.', 
		'La presencia de conductas asociadas a discriminación y violencias basadas en género (DyVBG) y acoso sexual puede impactar negativamente la convivencia universitaria y el desempeño académico estudiantil.', 
		'En mi experiencia personal, han estado presentes situaciones de discriminación y violencias basadas en género (DyVBG) asociadas a la orientación sexual o identidad/expresión de género.', 
		'En algún contexto de vida, es posible que se presentaran situaciones de violencia sexual, acoso sexual o ciberacoso.',
		' '
	);
	$aAnchos = array(
		15, 15, 15, 
		15, 15, 15, 15, 15, 
		15, 15, 15, 15, 15, 
		15, 15, 5
	);
	$iTotalCol = count($aTitulos) - 1;
	$sColumna5 = 'A';
	for ($k = 0; $k <= $iTotalCol; $k++) {
		PHPEXCEL_Escribir($objHoja5, $k, $iFilaHoja5, $aTitulos[$k]);
		$sColumna5 = columna_Letra($k);
		// $objHoja5->getColumnDimension($sColumna5)->setWidth($aAnchos[$k]);
		PHPExcel_Justificar_Celda_HorizontalIzquierda($objHoja5, $sColumna5 . $iFilaHoja5);
	}
	PHPExcel_Formato_Fuente_Celda($objHoja5, 'A' . $iFilaHoja5 . ':' . $sColumna5 . $iFilaHoja5, '10', 'Yu Gothic', 'Ne', true, false, false);
	PHPExcel_RellenarCeldas($objHoja5, 'A1:' . $sColumna5 . $iFilaHoja5, 'Bl', false);
	$iFilaHoja5++;

	$objExcel->setActiveSheetIndex(0);

	$acara01idperaca = array();
	$acara01estrato = array();
	$acara01idzona = array();
	$acara01idcead = array();
	$acara01indigenas = array();
	$acara01indigenas[0] = 'Ninguno';
	$acara01centroreclusion = array();
	$acara01acad_razonestudio = array();
	$acara01acad_razonunad = array();
	$acara01tipocaracterizacion = array();
	$acara01perayuda = array();
	$acara01perayuda[0] = 'Ninguno';
	$aSys11 = array();
	$tabla = $objDB->ejecutasql($sSQLReporte);
	if ($bDebug) {
		PHPEXCEL_Escribir($objHoja, 1, $iFilaHoja1, $sSQL);
		$iFilaHoja1++;
	}
	while ($fila = $objDB->sf($tabla)) {
		if (!$bPorTipo) {
			//Vamos a tener 7 bloques segun el tipo de caracterizacion.
			for ($k = 2; $k < 8; $k++) {
				$aBloque[$k] = false;
			}
			if ($fila['cara01fichafam'] != -1) {
				$aBloque[2] = true;
			}
			if ($fila['cara01fichaaca'] != -1) {
				$aBloque[3] = true;
			}
			if ($fila['cara01fichalab'] != -1) {
				$aBloque[4] = true;
			}
			if ($fila['cara01fichabien'] != -1) {
				$aBloque[5] = true;
			}
			if ($fila['cara01fichapsico'] != -1) {
				$aBloque[6] = true;
			}
			$aBloque[7] = true;
		}
		$bHayFilascara44 = false;
		if ($fila['cara44sexoversion'] != '') {
			$bHayFilascara44 = true;
		}
		// HOJA 1
		// Datos personales
		$lin_cara01tipocaracterizacion = '';
		$lin_cara01idtercero_td = '';
		$lin_cara01idtercero_doc = '';
		$lin_cara01idtercero_nom = '';
		$lin_unad11fechaultingreso = '';
		$lin_cara01fechaencuesta = '';
		$lin_cara01agnos = '';
		$lin_cara01sexo = '';
		$lin_identidadgen = '';
		$lin_orientasexo = '';
		$lin_cara01pais = '';
		$lin_cara01depto = '';
		$lin_cara01ciudad = '';
		$lin_cara01estrato = '';
		$lin_cara01zonares = '';
		$lin_cara01estcivil = '';
		$lin_cara01nomcontacto = '';
		$lin_cara01parentezcocontacto = '';
		$lin_cara01idzona = '';
		$lin_cara01idcead = '';
		$lin_cara01idescuela = '';
		$lin_cara01idprograma = '';
		$lin_cara01matconvenio = '';
		// Grupos poblacionales
		$lin_cara01raizal = '';
		$lin_cara01palenquero = '';
		$lin_cara01afrocolombiano = '';
		$lin_cara01otracomunnegras = '';
		$lin_cara01rom = '';
		$lin_cara01indigenas = '';
		$lin_cara44campesinado = '';
		$lin_cara44frontera = '';
		$lin_cara01victimadesplazado = '';
		$lin_cara01victimaacr = '';
		$lin_cara01inpecfuncionario = '';
		$lin_cara01inpecrecluso = '';
		$lin_cara01inpectiempocondena = '';
		$lin_cara01centroreclusion = '';
		// Discapacidades V 1.
		$lin_cara01discsensorial = '';
		$lin_cara01discfisica = '';
		$lin_cara01disccognitiva = '';
		$lin_cara01perayuda = '';
		$lin_cara01perotraayuda = '';
		// Discapacidades V 2.
		$lin_cara01discv2sensorial = '';
		$lin_cara02discv2intelectura = '';
		$lin_cara02discv2fisica = '';
		$lin_cara02discv2psico = '';
		$lin_cara02discv2sistemica = '';
		$lin_cara02discv2sistemicaotro = '';
		$lin_cara02discv2multiple = '';
		$lin_cara02discv2multipleotro = '';
		$lin_cara01discv2archivoorigen = '' . 'No';
		$lin_cara01discv2trastornos = '';
		$lin_cara01discv2trastaprende = '';
		$lin_cara01discv2contalento = '';
		$lin_cara01discv2pruebacoeficiente = '';
		$lin_cara01discv2condicionmedica = '';
		$lin_cara01discv2condmeddet = '';
		$lin_cara44med_tratamiento = 'No';
		$lin_cara44med_trat_cual = '';
		// Datos familiares
		$lin_cara01fam_tipovivienda = '';
		$lin_cara01fam_vivecon = '';
		$lin_cara01fam_numpersgrupofam = '';
		$lin_cara01fam_hijos = '';
		$lin_cara44fam_madrecabeza = '';
		$lin_cara01fam_personasacargo = '';
		$lin_cara01fam_dependeecon = '';
		$lin_cara01fam_escolaridadpadre = '';
		$lin_cara01fam_escolaridadmadre = '';
		$lin_cara01fam_numhermanos = '';
		$lin_cara01fam_posicionherm = '';
		$lin_cara01fam_familiaunad = '';
		// Datos academicos
		$lin_cara01acad_tipocolegio = '';
		$lin_cara01acad_modalidadbach = '';
		$lin_cara01acad_estudioprev = '';
		$lin_cara01acad_ultnivelest = '';
		$lin_cara01acad_tiemposinest = '';
		$lin_cara01acad_obtubodiploma = '';
		$lin_cara01acad_hatomadovirtual = '';
		$lin_cara01acad_razonestudio = '';
		$lin_cara01acad_primeraopc = '';
		$lin_cara01acad_programagusto = '';
		$lin_cara01acad_razonunad = '';
		$lin_cara44acadhatenidorecesos = '';
		$lin_cara44acadrazonreceso = '';
		$lin_cara44acadrazonrecesodetalle = '';
		// Con cuales equipos electronicos cuenta para acceder al campus virtual de la UNAD
		$lin_cara01campus_compescrito = '';
		$lin_cara01campus_portatil = '';
		$lin_cara01campus_tableta = '';
		$lin_cara01campus_telefono = '';
		$lin_cara01campus_energia = '';
		$lin_cara01campus_internetreside = '';
		$lin_cara01campus_expvirtual = '';
		$lin_cara01campus_ofimatica = '';
		$lin_cara01campus_foros = '';
		$lin_cara01campus_conversiones = '';
		$lin_cara01campus_usocorreo = '';
		$lin_cara44campus_usocorreounad = '';
		$lin_cara44campus_usocorreounadno = '';
		$lin_cara44campus_usocorreounadnodetalle = '';
		// La informacion que consulta la aprende mejor con
		$lin_cara01campus_aprendtexto = '';
		$lin_cara01campus_aprendvideo = '';
		$lin_cara01campus_aprendmapas = '';
		$lin_cara01campus_aprendeanima = '';
		$lin_cara01campus_mediocomunica = '';
		$lin_cara44campus_medioactivunad = '';
		$lin_cara44campus_medioactivunaddetalle = '';
		// Datos laborales
		$lin_cara01lab_situacion = '';
		$lin_cara01lab_sector = '';
		$lin_cara01lab_caracterjuri = '';
		$lin_cara01lab_cargo = '';
		$lin_cara01lab_antiguedad = '';
		$lin_cara01lab_tipocontrato = '';
		$lin_cara01lab_rangoingreso = '';
		$lin_cara01lab_tiempoacadem = '';
		$lin_cara01lab_tipoempresa = '';
		$lin_cara01lab_tiempoindepen = '';
		$lin_cara01lab_debebusctrab = '';
		$lin_cara01lab_origendinero = '';
		// Psicosocial
		$lin_cara01psico_costoemocion = '';
		$lin_cara01psico_reaccionimpre = '';
		$lin_cara01psico_estres = '';
		$lin_cara01psico_pocotiempo = '';
		$lin_cara01psico_actitudvida = '';
		$lin_cara01psico_duda = '';
		$lin_cara01psico_problemapers = '';
		$lin_cara01psico_satisfaccion = '';
		$lin_cara01psico_discusiones = '';
		$lin_cara01psico_atencion = '';
		$lin_cara01psico_puntaje = '';
		// Competencias
		$lin_cara01fichadigital = '';
		$lin_cara01fichalectura = '';
		$lin_cara01ficharazona = '';
		$lin_cara01fichaingles = '';
		$lin_cara01fichafisica = '';
		$lin_cara01fichaquimica = '';
		$lin_cara01fichabiolog = '';
		$lin_cara01fichaciudad = '';
		// Consejero
		$lin_cara01idconsejero = '';
		$i_cara01tipocaracterizacion = $fila['cara01tipocaracterizacion'];
		if (isset($acara01tipocaracterizacion[$i_cara01tipocaracterizacion]) == 0) {
			$sSQL = 'SELECT cara11nombre FROM cara11tipocaract WHERE cara11id=' . $i_cara01tipocaracterizacion . '';
			$tablae = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae) > 0) {
				$filae = $objDB->sf($tablae);
				$acara01tipocaracterizacion[$i_cara01tipocaracterizacion] = $filae['cara11nombre'];
			} else {
				$acara01tipocaracterizacion[$i_cara01tipocaracterizacion] = '';
			}
		}
		$lin_cara01tipocaracterizacion = $acara01tipocaracterizacion[$i_cara01tipocaracterizacion];
		$iTer = $fila['cara01idtercero'];
		if (isset($aSys11[$iTer]['doc']) == 0) {
			list($aSys11[$iTer]['td'], $aSys11[$iTer]['doc'], $aSys11[$iTer]['razon'], $aSys11[$iTer]['ult_ing']) = f2301_InfoParaPlano($iTer, $objDB);
		}
		$lin_cara01idtercero_td = $aSys11[$iTer]['td'];
		$lin_cara01idtercero_doc = $aSys11[$iTer]['doc'];
		$lin_cara01idtercero_nom = $aSys11[$iTer]['razon'];		
		$lin_unad11fechaultingreso = $aSys11[$iTer]['ult_ing'];
		$lin_cara01fechaencuesta = $fila['cara01fechaencuesta'];
		$lin_cara01agnos = $fila['cara01agnos'];
		$lin_cara01sexo = $ETI['msg_noresponde'];
		if ($fila['cara01sexo'] != '') {
			$lin_cara01sexo = $fila['cara01sexo'];
		}
		$i_cara01pais = $fila['cara01pais'];
		if (isset($acara01pais['"' . $i_cara01pais . '"']) == 0) {
			$sSQL = 'SELECT unad18nombre FROM unad18pais WHERE unad18codigo="' . $i_cara01pais . '"';
			$tablae = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae) > 0) {
				$filae = $objDB->sf($tablae);
				$acara01pais['"' . $i_cara01pais . '"'] = $filae['unad18nombre'];
			} else {
				$acara01pais['"' . $i_cara01pais . '"'] = '';
			}
		}
		$lin_cara01pais = $acara01pais['"' . $i_cara01pais . '"'];
		$i_cara01depto = $fila['cara01depto'];
		if (isset($acara01depto['"' . $i_cara01depto . '"']) == 0) {
			$sSQL = 'SELECT unad19nombre FROM unad19depto WHERE unad19codigo="' . $i_cara01depto . '"';
			$tablae = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae) > 0) {
				$filae = $objDB->sf($tablae);
				$acara01depto['"' . $i_cara01depto . '"'] = $filae['unad19nombre'];
			} else {
				$acara01depto['"' . $i_cara01depto . '"'] = '';
			}
		}
		$lin_cara01depto = $acara01depto['"' . $i_cara01depto . '"'];
		$i_cara01ciudad = $fila['cara01ciudad'];
		if (isset($acara01ciudad['"' . $i_cara01ciudad . '"']) == 0) {
			$sSQL = 'SELECT unad20nombre FROM unad20ciudad WHERE unad20codigo="' . $i_cara01ciudad . '"';
			$tablae = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae) > 0) {
				$filae = $objDB->sf($tablae);
				$acara01ciudad['"' . $i_cara01ciudad . '"'] = $filae['unad20nombre'];
			} else {
				$acara01ciudad['"' . $i_cara01ciudad . '"'] = '';
			}
		}
		$lin_cara01ciudad = $acara01ciudad['"' . $i_cara01ciudad . '"'];
		$lin_cara01estrato = f2301_NomEstrato($fila['cara01estrato']);
		if ($fila['cara01zonares'] == 'U') {
			$lin_cara01zonares = 'Urbana';
		}
		if ($fila['cara01zonares'] == 'R') {
			$lin_cara01zonares = 'Rural';
		}
		$i_cara01estcivil = $fila['cara01estcivil'];
		if (isset($acara01estcivil['"' . $i_cara01estcivil . '"']) == 0) {
			$sSQL = 'SELECT unad21nombre FROM unad21estadocivil WHERE unad21codigo="' . $i_cara01estcivil . '"';
			$tablae = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae) > 0) {
				$filae = $objDB->sf($tablae);
				$acara01estcivil['"' . $i_cara01estcivil . '"'] = $filae['unad21nombre'];
			} else {
				$acara01estcivil['"' . $i_cara01estcivil . '"'] = '[' . $i_cara01estcivil . ']';
			}
		}
		$lin_cara01estcivil = $acara01estcivil['"' . $i_cara01estcivil . '"'];
		$lin_cara01nomcontacto = $fila['cara01nomcontacto'];
		$lin_cara01parentezcocontacto = $fila['cara01parentezcocontacto'];
		//$lin_cara01celcontacto=$fila['cara01celcontacto'];
		//$lin_cara01correocontacto=$fila['cara01correocontacto'];
		$i_cara01idzona = $fila['cara01idzona'];
		if (isset($acara01idzona[$i_cara01idzona]) == 0) {
			$sSQL = 'SELECT unad23nombre FROM unad23zona WHERE unad23id=' . $i_cara01idzona . '';
			$tablae = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae) > 0) {
				$filae = $objDB->sf($tablae);
				$acara01idzona[$i_cara01idzona] = $filae['unad23nombre'];
			} else {
				$acara01idzona[$i_cara01idzona] = '';
			}
		}
		$lin_cara01idzona = $acara01idzona[$i_cara01idzona];
		$i_cara01idcead = $fila['cara01idcead'];
		if (isset($acara01idcead[$i_cara01idcead]) == 0) {
			$sSQL = 'SELECT unad24nombre FROM unad24sede WHERE unad24id=' . $i_cara01idcead . '';
			$tablae = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae) > 0) {
				$filae = $objDB->sf($tablae);
				$acara01idcead[$i_cara01idcead] = $filae['unad24nombre'];
			} else {
				$acara01idcead[$i_cara01idcead] = '';
			}
		}
		$lin_cara01idcead = $acara01idcead[$i_cara01idcead];
		$i_cara01idprograma = $fila['cara01idprograma'];
		if (isset($acara01idprograma[$i_cara01idprograma]) == 0) {
			$sSQL = 'SELECT core09nombre FROM core09programa WHERE core09id=' . $i_cara01idprograma . '';
			$tablae = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae) > 0) {
				$filae = $objDB->sf($tablae);
				$acara01idprograma[$i_cara01idprograma] = $filae['core09nombre'];
			} else {
				$acara01idprograma[$i_cara01idprograma] = '[' . $i_cara01idprograma . ']';
			}
		}
		$lin_cara01idprograma = $acara01idprograma[$i_cara01idprograma];
		$i_cara01idescuela = $fila['cara01idescuela'];
		if (isset($acara01idescuela[$i_cara01idescuela]) == 0) {
			$sSQL = 'SELECT core12nombre FROM core12escuela WHERE core12id=' . $i_cara01idescuela . '';
			$tablae = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae) > 0) {
				$filae = $objDB->sf($tablae);
				$acara01idescuela[$i_cara01idescuela] = $filae['core12nombre'];
			} else {
				$acara01idescuela[$i_cara01idescuela] = '';
			}
		}
		$lin_cara01idescuela = $acara01idescuela[$i_cara01idescuela];
		$lin_cara01matconvenio = $fila['cara01matconvenio'];
		$lin_cara01raizal = $fila['cara01raizal'];
		$lin_cara01palenquero = $fila['cara01palenquero'];
		$lin_cara01afrocolombiano = $fila['cara01afrocolombiano'];
		$lin_cara01otracomunnegras = $fila['cara01otracomunnegras'];
		$lin_cara01rom = $fila['cara01rom'];
		$i_cara01indigenas = $fila['cara01indigenas'];
		if (isset($acara01indigenas[$i_cara01indigenas]) == 0) {
			$sSQL = 'SELECT cara02nombre FROM cara02indigenas WHERE cara02id=' . $i_cara01indigenas . '';
			$tablae = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae) > 0) {
				$filae = $objDB->sf($tablae);
				$acara01indigenas[$i_cara01indigenas] = $filae['cara02nombre'];
			} else {
				$acara01indigenas[$i_cara01indigenas] = '';
			}
		}
		$lin_cara01indigenas = $acara01indigenas[$i_cara01indigenas];
		$lin_cara01victimadesplazado = $fila['cara01victimadesplazado'];
		$lin_cara01victimaacr = $fila['cara01victimaacr'];
		$lin_cara01inpecfuncionario = $fila['cara01inpecfuncionario'];
		$lin_cara01inpecrecluso = $fila['cara01inpecrecluso'];
		if ($fila['cara01inpecrecluso'] == 'S') {
			$lin_cara01inpectiempocondena = $fila['cara01inpectiempocondena'];
			$i_cara01centroreclusion = $fila['cara01centroreclusion'];
			if (isset($acara01centroreclusion[$i_cara01centroreclusion]) == 0) {
				$sSQL = 'SELECT cara03nombre FROM cara03centroreclusion WHERE cara03id=' . $i_cara01centroreclusion . '';
				$tablae = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($tablae) > 0) {
					$filae = $objDB->sf($tablae);
					$acara01centroreclusion[$i_cara01centroreclusion] = $filae['cara03nombre'];
				} else {
					$acara01centroreclusion[$i_cara01centroreclusion] = '';
				}
			}
			$lin_cara01centroreclusion = $acara01centroreclusion[$i_cara01centroreclusion];
		}
		$lin_cara01discsensorial = $fila['cara01discsensorial'];
		if (isset($acara01discsensorial[$fila['cara01discsensorial']]) != 0) {
			$lin_cara01discsensorial = $acara01discsensorial[$fila['cara01discsensorial']];
		}
		$lin_cara01discfisica = $fila['cara01discfisica'];
		if (isset($acara01discfisica[$fila['cara01discfisica']]) != 0) {
			$lin_cara01discfisica = $acara01discfisica[$fila['cara01discfisica']];
		}
		$lin_cara01disccognitiva = $fila['cara01disccognitiva'];
		if (isset($acara01disccognitiva[$fila['cara01disccognitiva']]) != 0) {
			$lin_cara01disccognitiva = $acara01disccognitiva[$fila['cara01disccognitiva']];
		}
		$acara01discv2 = array($fila['cara01discv2sensorial'], $fila['cara02discv2intelectura'], $fila['cara02discv2fisica'], $fila['cara02discv2psico']);
		foreach ($acara01discv2 as $i_id) {
			if (isset($acara37discapacidades[$i_id]) == 0) {
				$sSQL = 'SELECT cara37nombre FROM cara37discapacidades WHERE cara37id=' . $i_id . '';
				$tablae = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($tablae) > 0) {
					$filae = $objDB->sf($tablae);
					$acara37discapacidades[$i_id] = $filae['cara37nombre'];
				} else {
					$acara37discapacidades[$i_id] = '';
				}
			}
		}
		$lin_cara01discv2sensorial = $acara37discapacidades[$fila['cara01discv2sensorial']];
		$lin_cara02discv2intelectura = $acara37discapacidades[$fila['cara02discv2intelectura']];
		$lin_cara02discv2fisica = $acara37discapacidades[$fila['cara02discv2fisica']];
		$lin_cara02discv2psico = $acara37discapacidades[$fila['cara02discv2psico']];
		$lin_cara02discv2sistemica = $acara02discv2sistemica[$fila['cara02discv2sistemica']];
		$lin_cara02discv2sistemicaotro = $fila['cara02discv2sistemicaotro'];
		$lin_cara02discv2multiple = $acara02discv2multiple[$fila['cara02discv2multiple']];
		$lin_cara02discv2multipleotro = $fila['cara02discv2multipleotro'];
		if ($fila['cara01discv2archivoorigen'] != 0) {
			$lin_cara01discv2archivoorigen = 'Si';
		}
		$i_cara02talentoexcepcional = $fila['cara02talentoexcepcional'];
		if (isset($acara02talentoexcepcional[$i_cara02talentoexcepcional]) == 0) {
			$sSQL = 'SELECT cara38nombre FROM cara38talentos WHERE cara38id=' . $i_cara02talentoexcepcional . '';
			$tablae = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae) > 0) {
				$filae = $objDB->sf($tablae);
				$acara02talentoexcepcional[$i_cara02talentoexcepcional] = $filae['cara38nombre'];
			} else {
				$acara02talentoexcepcional[$i_cara02talentoexcepcional] = '';
			}
		}
		$lin_cara02talentoexcepcional = html_entity_decode($acara02talentoexcepcional[$i_cara02talentoexcepcional]);
		$bEntra = true;
		if ($fila['cara01perayuda'] == -1) {
			$bEntra = false;
			$lin_cara01perayuda = 'Otra';
			$lin_cara01perotraayuda = $fila['cara01perotraayuda'];
		}
		if ($bEntra) {
			$i_cara01perayuda = $fila['cara01perayuda'];
			if (isset($acara01perayuda[$i_cara01perayuda]) == 0) {
				$sSQL = 'SELECT cara14nombre FROM cara14ayudaajuste WHERE cara14id=' . $i_cara01perayuda . '';
				$tablae = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($tablae) > 0) {
					$filae = $objDB->sf($tablae);
					$acara01perayuda[$i_cara01perayuda] = $filae['cara14nombre'];
				} else {
					$acara01perayuda[$i_cara01perayuda] = '';
				}
			}
			$lin_cara01perayuda = html_entity_decode($acara01perayuda[$i_cara01perayuda]);
		}
		$lin_cara01discv2tiene = '[' . $fila['cara01discv2tiene'] . ']';
		if (isset($acara01discv2tiene[$fila['cara01discv2tiene']]) != 0) {
			$lin_cara01discv2tiene = html_entity_decode($acara01discv2tiene[$fila['cara01discv2tiene']]);
		}
		$lin_cara01discv2trastornos = 'No';
		if ($fila['cara01discv2trastornos'] != 0) {
			$lin_cara01discv2trastornos = 'Si';
			$i_cara01discv2trastaprende = $fila['cara01discv2trastaprende'];
			if (isset($acara01discv2trastaprende[$i_cara01discv2trastaprende]) == 0) {
				$sSQL = 'SELECT cara37nombre FROM cara37discapacidades WHERE cara37id=' . $i_cara01discv2trastaprende . '';
				$tablae = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($tablae) > 0) {
					$filae = $objDB->sf($tablae);
					$acara01discv2trastaprende[$i_cara01discv2trastaprende] = $filae['cara37nombre'];
				} else {
					$acara01discv2trastaprende[$i_cara01discv2trastaprende] = '';
				}
			}
			$lin_cara01discv2trastaprende = html_entity_decode($acara01discv2trastaprende[$i_cara01discv2trastaprende]);
		}
		$lin_cara01discv2contalento = 'No';
		if ($fila['cara01discv2contalento'] != 0) {
			$lin_cara01discv2contalento = 'Si';
		}
		$lin_cara01discv2condicionmedica = 'No';
		if ($fila['cara01discv2condicionmedica'] != 0) {
			$lin_cara01discv2condicionmedica = 'Si';
			$lin_cara01discv2condmeddet = $fila['cara01discv2condmeddet'];
		}		
		$lin_cara01discv2pruebacoeficiente = '[' . $fila['cara01discv2pruebacoeficiente'] . ']';
		if (isset($acara01discv2pruebacoeficiente[$fila['cara01discv2pruebacoeficiente']]) != 0) {
			$lin_cara01discv2pruebacoeficiente = html_entity_decode($acara01discv2pruebacoeficiente[$fila['cara01discv2pruebacoeficiente']]);
		}
		if ($aBloque[2]) {
			$lin_cara01fam_tipovivienda = '[' . $fila['cara01fam_tipovivienda'] . ']';
			if (isset($afam_tipovivienda[$fila['cara01fam_tipovivienda']]) != 0) {
				$lin_cara01fam_tipovivienda = $afam_tipovivienda[$fila['cara01fam_tipovivienda']];
			}
			$lin_cara01fam_vivecon = '[' . $fila['cara01fam_vivecon'] . ']';
			if (isset($afam_vivecon[$fila['cara01fam_vivecon']]) != 0) {
				$lin_cara01fam_vivecon = $afam_vivecon[$fila['cara01fam_vivecon']];
			}
			$lin_cara01fam_numpersgrupofam = '[' . $fila['cara01fam_numpersgrupofam'] . ']';
			if (isset($afam_numpersgrupofam[$fila['cara01fam_numpersgrupofam']]) != 0) {
				$lin_cara01fam_numpersgrupofam = $afam_numpersgrupofam[$fila['cara01fam_numpersgrupofam']];
			}
			$lin_cara01fam_hijos = '[' . $fila['cara01fam_hijos'] . ']';
			if (isset($afam_hijos[$fila['cara01fam_hijos']]) != 0) {
				$lin_cara01fam_hijos = $afam_hijos[$fila['cara01fam_hijos']];
			}
			$lin_cara01fam_personasacargo = '[' . $fila['cara01fam_personasacargo'] . ']';
			if (isset($afam_personasacargo[$fila['cara01fam_personasacargo']]) != 0) {
				$lin_cara01fam_personasacargo = $afam_personasacargo[$fila['cara01fam_personasacargo']];
			}
			$lin_cara01fam_dependeecon = $fila['cara01fam_dependeecon'];
			$lin_cara01fam_escolaridadpadre = '[' . $fila['cara01fam_escolaridadpadre'] . ']';
			if (isset($aescolaridad[$fila['cara01fam_escolaridadpadre']]) != 0) {
				$lin_cara01fam_escolaridadpadre = $aescolaridad[$fila['cara01fam_escolaridadpadre']];
			}
			$lin_cara01fam_escolaridadmadre = '[' . $fila['cara01fam_escolaridadmadre'] . ']';
			if (isset($aescolaridad[$fila['cara01fam_escolaridadmadre']]) != 0) {
				$lin_cara01fam_escolaridadmadre = $aescolaridad[$fila['cara01fam_escolaridadmadre']];
			}
			$lin_cara01fam_numhermanos = '[' . $fila['cara01fam_numhermanos'] . ']';
			if (isset($afam_numhermanos[$fila['cara01fam_numhermanos']]) != 0) {
				$lin_cara01fam_numhermanos = $afam_numhermanos[$fila['cara01fam_numhermanos']];
			}
			$lin_cara01fam_posicionherm = '[' . $fila['cara01fam_posicionherm'] . ']';
			if (isset($afam_posicionherm[$fila['cara01fam_posicionherm']]) != 0) {
				$lin_cara01fam_posicionherm = $afam_posicionherm[$fila['cara01fam_posicionherm']];
			}
			$lin_cara01fam_familiaunad = $fila['cara01fam_familiaunad'];
		}
		if ($aBloque[3]) {
			$lin_cara01acad_tipocolegio = '[' . $fila['cara01acad_tipocolegio'] . ']';
			if (isset($aacad_tipocolegio[$fila['cara01acad_tipocolegio']]) != 0) {
				$lin_cara01acad_tipocolegio = $aacad_tipocolegio[$fila['cara01acad_tipocolegio']];
			}
			$lin_cara01acad_modalidadbach = '[' . $fila['cara01acad_modalidadbach'] . ']';
			if (isset($aacad_modalidadbach[$fila['cara01acad_modalidadbach']]) != 0) {
				$lin_cara01acad_modalidadbach = $aacad_modalidadbach[$fila['cara01acad_modalidadbach']];
			}
			$lin_cara01acad_estudioprev = $fila['cara01acad_estudioprev'];
			$lin_cara01acad_ultnivelest = '[' . $fila['cara01acad_ultnivelest'] . ']';
			if (isset($aacad_ultnivelest[$fila['cara01acad_ultnivelest']]) != 0) {
				$lin_cara01acad_ultnivelest = $aacad_ultnivelest[$fila['cara01acad_ultnivelest']];
			}
			$lin_cara01acad_obtubodiploma = $fila['cara01acad_obtubodiploma'];
			$lin_cara01acad_hatomadovirtual = $fila['cara01acad_hatomadovirtual'];
			$lin_cara01acad_tiemposinest = '[' . $fila['cara01acad_tiemposinest'] . ']';
			if (isset($acara01acad_tiemposinest[$fila['cara01acad_tiemposinest']]) != 0) {
				$lin_cara01acad_tiemposinest = $acara01acad_tiemposinest[$fila['cara01acad_tiemposinest']];
			}
			$i_cara01acad_razonestudio = $fila['cara01acad_razonestudio'];
			if (isset($acara01acad_razonestudio[$i_cara01acad_razonestudio]) == 0) {
				$sSQL = 'SELECT cara04nombre FROM cara04razonestudio WHERE cara04id=' . $i_cara01acad_razonestudio . '';
				$tablae = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($tablae) > 0) {
					$filae = $objDB->sf($tablae);
					$acara01acad_razonestudio[$i_cara01acad_razonestudio] = $filae['cara04nombre'];
				} else {
					$acara01acad_razonestudio[$i_cara01acad_razonestudio] = '';
				}
			}
			$lin_cara01acad_razonestudio = $acara01acad_razonestudio[$i_cara01acad_razonestudio];
			$lin_cara01acad_primeraopc = $fila['cara01acad_primeraopc'];
			$lin_cara01acad_programagusto = cadena_codificar($fila['cara01acad_programagusto']);
			$i_cara01acad_razonunad = $fila['cara01acad_razonunad'];
			if (isset($acara01acad_razonunad[$i_cara01acad_razonunad]) == 0) {
				$sSQL = 'SELECT cara05nombre FROM cara05razonunad WHERE cara05id=' . $i_cara01acad_razonunad . '';
				$tablae = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($tablae) > 0) {
					$filae = $objDB->sf($tablae);
					$acara01acad_razonunad[$i_cara01acad_razonunad] = $filae['cara05nombre'];
				} else {
					$acara01acad_razonunad[$i_cara01acad_razonunad] = '';
				}
			}
			$lin_cara01acad_razonunad = $acara01acad_razonunad[$i_cara01acad_razonunad];
			$lin_cara01campus_compescrito = $fila['cara01campus_compescrito'];
			$lin_cara01campus_portatil = $fila['cara01campus_portatil'];
			$lin_cara01campus_tableta = $fila['cara01campus_tableta'];
			$lin_cara01campus_telefono = $fila['cara01campus_telefono'];
			$lin_cara01campus_energia = '[' . $fila['cara01campus_energia'] . ']';
			if (isset($acara01campus_energia[$fila['cara01campus_energia']]) != 0) {
				$lin_cara01campus_energia = $acara01campus_energia[$fila['cara01campus_energia']];
			}
			$lin_cara01campus_internetreside = '[' . $fila['cara01campus_internetreside'] . ']';
			if (isset($acara01campus_internetreside[$fila['cara01campus_internetreside']]) != 0) {
				$lin_cara01campus_internetreside = $acara01campus_internetreside[$fila['cara01campus_internetreside']];
			}
			$lin_cara01campus_expvirtual = $fila['cara01campus_expvirtual'];
			$lin_cara01campus_ofimatica = $fila['cara01campus_ofimatica'];
			$lin_cara01campus_foros = $fila['cara01campus_foros'];
			$lin_cara01campus_conversiones = $fila['cara01campus_conversiones'];
			$lin_cara01campus_usocorreo = '[' . $fila['cara01campus_usocorreo'] . ']';
			if (isset($acara01campus_usocorreo[$fila['cara01campus_usocorreo']]) != 0) {
				$lin_cara01campus_usocorreo = $acara01campus_usocorreo[$fila['cara01campus_usocorreo']];
			}
			$lin_cara01campus_aprendtexto = $fila['cara01campus_aprendtexto'];
			$lin_cara01campus_aprendvideo = $fila['cara01campus_aprendvideo'];
			$lin_cara01campus_aprendmapas = $fila['cara01campus_aprendmapas'];
			$lin_cara01campus_aprendeanima = $fila['cara01campus_aprendeanima'];
			$lin_cara01campus_mediocomunica = '[' . $fila['cara01campus_mediocomunica'] . ']';
			if (isset($acara01campus_mediocomunica[$fila['cara01campus_mediocomunica']]) != 0) {
				$lin_cara01campus_mediocomunica = $acara01campus_mediocomunica[$fila['cara01campus_mediocomunica']];
			}
		}
		if ($aBloque[4]) {
			//Laboral
			$bBloque1 = false;
			$bBloque2 = false;
			$bBloque3 = false;
			$bBloque4 = false;
			$bBloque5 = false;
			$bBloque6 = false;
			if ($fila['cara01lab_situacion'] == 1) {
				$bBloque1 = true;
				$bBloque2 = true;
				$bBloque3 = true;
				$bBloque6 = true;
			}
			if ($fila['cara01lab_situacion'] == 2) {
				$bBloque1 = true;
				$bBloque4 = true;
				$bBloque6 = true;
			}
			if ($fila['cara01lab_situacion'] == 3) {
				$bBloque3 = true;
				$bBloque5 = true;
				$bBloque6 = true;
			}
			if ($fila['cara01lab_situacion'] == 4) {
				$bBloque5 = true;
				$bBloque6 = true;
			}
			$lin_cara01lab_situacion = '[' . $fila['cara01lab_situacion'] . ']';
			if (isset($acara01lab_situacion[$fila['cara01lab_situacion']]) != 0) {
				$lin_cara01lab_situacion = $acara01lab_situacion[$fila['cara01lab_situacion']];
			}
			if ($bBloque1) {
				$lin_cara01lab_sector = '[' . $fila['cara01lab_sector'] . ']';
				if (isset($acara01lab_sector[$fila['cara01lab_sector']]) != 0) {
					$lin_cara01lab_sector = $acara01lab_sector[$fila['cara01lab_sector']];
				}
				$lin_cara01lab_caracterjuri = '[' . $fila['cara01lab_caracterjuri'] . ']';
				if (isset($acara01lab_caracterjuri[$fila['cara01lab_caracterjuri']]) != 0) {
					$lin_cara01lab_caracterjuri = $acara01lab_caracterjuri[$fila['cara01lab_caracterjuri']];
				}
				$lin_cara01lab_cargo = '[' . $fila['cara01lab_cargo'] . ']';
				if (isset($acara01lab_cargo[$fila['cara01lab_cargo']]) != 0) {
					$lin_cara01lab_cargo = $acara01lab_cargo[$fila['cara01lab_cargo']];
				}
				$lin_cara01lab_antiguedad = '[' . $fila['cara01lab_antiguedad'] . ']';
				if (isset($acara01lab_antiguedad[$fila['cara01lab_antiguedad']]) != 0) {
					$lin_cara01lab_antiguedad = $acara01lab_antiguedad[$fila['cara01lab_antiguedad']];
				}
			}
			if ($bBloque2) {
				$lin_cara01lab_tipocontrato = '[' . $fila['cara01lab_tipocontrato'] . ']';
				if (isset($acara01lab_tipocontrato[$fila['cara01lab_tipocontrato']]) != 0) {
					$lin_cara01lab_tipocontrato = $acara01lab_tipocontrato[$fila['cara01lab_tipocontrato']];
				}
			}
			if ($bBloque3) {
				$lin_cara01lab_rangoingreso = '[' . $fila['cara01lab_rangoingreso'] . ']';
				if (isset($acara01lab_rangoingreso[$fila['cara01lab_rangoingreso']]) != 0) {
					$lin_cara01lab_rangoingreso = $acara01lab_rangoingreso[$fila['cara01lab_rangoingreso']];
				}
			}
			$lin_cara01lab_tiempoacadem = '[' . $fila['cara01lab_tiempoacadem'] . ']';
			if (isset($acara01lab_tiempoacadem[$fila['cara01lab_tiempoacadem']]) != 0) {
				$lin_cara01lab_tiempoacadem = $acara01lab_tiempoacadem[$fila['cara01lab_tiempoacadem']];
			}
			if ($bBloque4) {
				$lin_cara01lab_tipoempresa = '[' . $fila['cara01lab_tipoempresa'] . ']';
				if (isset($acara01lab_tipoempresa[$fila['cara01lab_tipoempresa']]) != 0) {
					$lin_cara01lab_tipoempresa = $acara01lab_tipoempresa[$fila['cara01lab_tipoempresa']];
				}
				$lin_cara01lab_tiempoindepen = '[' . $fila['cara01lab_tiempoindepen'] . ']';
				if (isset($acara01lab_tiempoindepen[$fila['cara01lab_tiempoindepen']]) != 0) {
					$lin_cara01lab_tiempoindepen = $acara01lab_tiempoindepen[$fila['cara01lab_tiempoindepen']];
				}
			}
			if ($bBloque5) {
				$lin_cara01lab_debebusctrab = $fila['cara01lab_debebusctrab'];
			}
			if ($bBloque6) {
				$lin_cara01lab_origendinero = '[' . $fila['cara01lab_origendinero'] . ']';
				if (isset($acara01lab_origendinero[$fila['cara01lab_origendinero']]) != 0) {
					$lin_cara01lab_origendinero = $acara01lab_origendinero[$fila['cara01lab_origendinero']];
				}
			}
		}
		if ($aBloque[6]) {
			//Psicologia.
			$lin_cara01psico_costoemocion = '[' . $fila['cara01psico_costoemocion'] . ']';
			if (isset($aCAEN[$fila['cara01psico_costoemocion']]) != 0) {
				$lin_cara01psico_costoemocion = $aCAEN[$fila['cara01psico_costoemocion']];
			}
			$lin_cara01psico_reaccionimpre = '[' . $fila['cara01psico_reaccionimpre'] . ']';
			if (isset($apsico_reaccionimpre[$fila['cara01psico_reaccionimpre']]) != 0) {
				$lin_cara01psico_reaccionimpre = $apsico_reaccionimpre[$fila['cara01psico_reaccionimpre']];
			}
			$lin_cara01psico_estres = '[' . $fila['cara01psico_estres'] . ']';
			if (isset($apsico_estres[$fila['cara01psico_estres']]) != 0) {
				$lin_cara01psico_estres = $apsico_estres[$fila['cara01psico_estres']];
			}
			$lin_cara01psico_pocotiempo = '[' . $fila['cara01psico_pocotiempo'] . ']';
			if (isset($apsico_pocotiempo[$fila['cara01psico_pocotiempo']]) != 0) {
				$lin_cara01psico_pocotiempo = $apsico_pocotiempo[$fila['cara01psico_pocotiempo']];
			}
			$lin_cara01psico_actitudvida = '[' . $fila['cara01psico_actitudvida'] . ']';
			if (isset($apsico_actitudvida[$fila['cara01psico_actitudvida']]) != 0) {
				$lin_cara01psico_actitudvida = $apsico_actitudvida[$fila['cara01psico_actitudvida']];
			}
			$lin_cara01psico_duda = '[' . $fila['cara01psico_duda'] . ']';
			if (isset($apsico_duda[$fila['cara01psico_duda']]) != 0) {
				$lin_cara01psico_duda = $apsico_duda[$fila['cara01psico_duda']];
			}
			$lin_cara01psico_problemapers = '[' . $fila['cara01psico_problemapers'] . ']';
			if (isset($apsico_problemapers[$fila['cara01psico_problemapers']]) != 0) {
				$lin_cara01psico_problemapers = $apsico_problemapers[$fila['cara01psico_problemapers']];
			}
			$lin_cara01psico_satisfaccion = '[' . $fila['cara01psico_satisfaccion'] . ']';
			if (isset($apsico_satisfaccion[$fila['cara01psico_satisfaccion']]) != 0) {
				$lin_cara01psico_satisfaccion = $apsico_satisfaccion[$fila['cara01psico_satisfaccion']];
			}
			$lin_cara01psico_discusiones = '[' . $fila['cara01psico_discusiones'] . ']';
			if (isset($apsico_discusiones[$fila['cara01psico_discusiones']]) != 0) {
				$lin_cara01psico_discusiones = $apsico_discusiones[$fila['cara01psico_discusiones']];
			}
			$lin_cara01psico_atencion = '[' . $fila['cara01psico_atencion'] . ']';
			if (isset($apsico_atencion[$fila['cara01psico_atencion']]) != 0) {
				$lin_cara01psico_atencion = $apsico_atencion[$fila['cara01psico_atencion']];
			}
			$lin_cara01psico_puntaje = f2301_NombrePuntaje('puntaje', $fila['cara01psico_puntaje']);
		}
		if ($aBloque[7]) {
			if ($fila['cara01fichadigital'] != -1) {
				$lin_cara01fichadigital = f2301_NombrePuntaje('digital', $fila['cara01niveldigital']);
			}
			if ($fila['cara01fichalectura'] != -1) {
				$lin_cara01fichalectura = f2301_NombrePuntaje('lectura', $fila['cara01nivellectura']);
			}
			if ($fila['cara01ficharazona'] != -1) {
				$lin_cara01ficharazona = f2301_NombrePuntaje('razona', $fila['cara01nivelrazona']);
			}
			if ($fila['cara01fichaingles'] != -1) {
				$lin_cara01fichaingles = f2301_NombrePuntaje('ingles', $fila['cara01nivelingles']);
			}
			if ($fila['cara01fichafisica'] != -1) {
				$lin_cara01fichafisica = f2301_NombrePuntaje('fisica', $fila['cara01nivelfisica']);
			}
			if ($fila['cara01fichaquimica'] != -1) {
				$lin_cara01fichaquimica = f2301_NombrePuntaje('quimica', $fila['cara01nivelquimica']);
			}
			if ($fila['cara01fichabiolog'] != -1) {
				$lin_cara01fichabiolog = f2301_NombrePuntaje('biolog', $fila['cara01nivelbiolog']);
			}
			if ($fila['cara01fichaciudad'] != -1) {
				$lin_cara01fichaciudad = f2301_NombrePuntaje('ciudadanas', $fila['cara01nivelciudad']);
			}
		}
		if ($bConConsejero) {
			if ($fila['cara01idconsejero'] == 0) {
				$lin_cara01idconsejero = 'Sin asignar';
			} else {
				$iTer = $fila['cara01idconsejero'];
				if (isset($aSys11[$iTer]['doc']) == 0) {
					list($aSys11[$iTer]['td'], $aSys11[$iTer]['doc'], $aSys11[$iTer]['razon'], $aSys11[$iTer]['ult_ing']) = f2301_InfoParaPlano($iTer, $objDB);
				}
				//$cSepara.$aSys11[$iTer]['td'].$cSepara.$aSys11[$iTer]['doc'].
				$lin_cara01idconsejero = $aSys11[$iTer]['razon'];
			}
		}
		if ($bHayFilascara44) {
			$aidentidadgen = array();
			$aorientasexo = array();
			switch ($fila['cara44sexoversion']) {
				case 1:
					$aidentidadgen = $acara44sexov1identidadgen;
					$aorientasexo = $acara44sexov1orientasexo;
					break;
				case 2:
					$aidentidadgen = $acara44sexov2identidadgen;
					$aorientasexo = $acara44sexov2orientasexo;
					break;
			}
			if (isset($aidentidadgen[$fila['cara44sexov1identidadgen']]) != 0) {
				$lin_identidadgen = html_entity_decode($aidentidadgen[$fila['cara44sexov1identidadgen']]);
			}
			if (isset($aorientasexo[$fila['cara44sexov1orientasexo']]) != 0) {
				$lin_orientasexo = html_entity_decode($aorientasexo[$fila['cara44sexov1orientasexo']]);
			}
			$lin_cara44campesinado = $fila['cara44campesinado'];
			$lin_cara44frontera = 'Si';
			if ($fila['cara44frontera'] == 0) {
				$lin_cara44frontera = 'No';
			}
			if ($aBloque[2]) {
				$lin_cara44fam_madrecabeza = $fila['cara44fam_madrecabeza'];
			}
			if ($aBloque[3]) {
				$lin_cara44acadhatenidorecesos = $fila['cara44fam_madrecabeza'];
				if (isset($acara44acadrazonreceso[$fila['cara44acadrazonreceso']]) != 0) {
					$lin_cara44acadrazonreceso = html_entity_decode($acara44acadrazonreceso[$fila['cara44acadrazonreceso']]);
				}
				$lin_cara44acadrazonrecesodetalle = $fila['cara44acadrazonrecesodetalle'];
				if (isset($acara44campus_usocorreounad[$fila['cara44campus_usocorreounad']]) != 0) {
					$lin_cara44campus_usocorreounad = html_entity_decode($acara44campus_usocorreounad[$fila['cara44campus_usocorreounad']]);
				}
				if (isset($acara44campus_usocorreounadno[$fila['cara44campus_usocorreounadno']]) != 0) {
					$lin_cara44campus_usocorreounadno = html_entity_decode($acara44campus_usocorreounadno[$fila['cara44campus_usocorreounadno']]);
				}
				$lin_cara44campus_usocorreounadnodetalle = $fila['cara44campus_usocorreounadnodetalle'];
				if (isset($acara44campus_medioactivunad[$fila['cara44campus_medioactivunad']]) != 0) {
					$lin_cara44campus_medioactivunad = html_entity_decode($acara44campus_medioactivunad[$fila['cara44campus_medioactivunad']]);
				}
				$lin_cara44campus_medioactivunaddetalle = $fila['cara44campus_medioactivunaddetalle'];
			}
			if (isset($fila['cara44med_tratamiento']) != 0) {
				if ($fila['cara44med_tratamiento'] != 0) {
					$lin_cara44med_tratamiento = 'Si';
					$lin_cara44med_trat_cual = $fila['cara44med_trat_cual'];
				}
			}
		}
		$objExcel->setActiveSheetIndex(0);
		$objHoja1 = $objExcel->getActiveSheet();
		// Datos personales
		$aCampos = array(
			$lin_cara01tipocaracterizacion,
$lin_cara01idtercero_td,
$lin_cara01idtercero_doc,
$lin_cara01idtercero_nom,
$lin_unad11fechaultingreso,
$lin_cara01fechaencuesta,
$lin_cara01agnos,
$lin_cara01sexo,
$lin_identidadgen,
$lin_orientasexo,
$lin_cara01pais,
$lin_cara01depto,
$lin_cara01ciudad,
$lin_cara01estrato,
$lin_cara01zonares,
$lin_cara01estcivil,
$lin_cara01nomcontacto,
$lin_cara01parentezcocontacto,
$lin_cara01idzona,
$lin_cara01idcead,
$lin_cara01idescuela,
$lin_cara01idprograma,
$lin_cara01matconvenio,
// Grupos poblacionales
$lin_cara01raizal,
$lin_cara01palenquero,
$lin_cara01afrocolombiano,
$lin_cara01otracomunnegras,
$lin_cara01rom,
$lin_cara01indigenas,
$lin_cara44campesinado,
$lin_cara44frontera,
$lin_cara01victimadesplazado,
$lin_cara01victimaacr,
$lin_cara01inpecfuncionario,
$lin_cara01inpecrecluso,
$lin_cara01inpectiempocondena,
$lin_cara01centroreclusion,
// Discapacidades V 1.
$lin_cara01discsensorial,
$lin_cara01discfisica,
$lin_cara01disccognitiva,
$lin_cara01perayuda,
$lin_cara01perotraayuda,
// Discapacidades V 2.
$lin_cara01discv2sensorial,
$lin_cara02discv2intelectura,
$lin_cara02discv2fisica,
$lin_cara02discv2psico,
$lin_cara02discv2sistemica,
$lin_cara02discv2sistemicaotro,
$lin_cara02discv2multiple,
$lin_cara02discv2multipleotro,
$lin_cara01discv2archivoorigen,
$lin_cara01discv2trastornos,
$lin_cara01discv2trastaprende,
$lin_cara01discv2contalento,
$lin_cara01discv2pruebacoeficiente,
$lin_cara01discv2condicionmedica,
$lin_cara01discv2condmeddet,
$lin_cara44med_tratamiento,
$lin_cara44med_trat_cual,
// Datos familiares
$lin_cara01fam_tipovivienda,
$lin_cara01fam_vivecon,
$lin_cara01fam_numpersgrupofam,
$lin_cara01fam_hijos,
$lin_cara44fam_madrecabeza,
$lin_cara01fam_personasacargo,
$lin_cara01fam_dependeecon,
$lin_cara01fam_escolaridadpadre,
$lin_cara01fam_escolaridadmadre,
$lin_cara01fam_numhermanos,
$lin_cara01fam_posicionherm,
$lin_cara01fam_familiaunad,
// Datos academicos
$lin_cara01acad_tipocolegio,
$lin_cara01acad_modalidadbach,
$lin_cara01acad_estudioprev,
$lin_cara01acad_ultnivelest,
$lin_cara01acad_tiemposinest,
$lin_cara01acad_obtubodiploma,
$lin_cara01acad_hatomadovirtual,
$lin_cara01acad_razonestudio,
$lin_cara01acad_primeraopc,
$lin_cara01acad_programagusto,
$lin_cara01acad_razonunad,
$lin_cara44acadhatenidorecesos,
$lin_cara44acadrazonreceso,
$lin_cara44acadrazonrecesodetalle,
// Con cuales equipos electronicos cuenta para acceder al campus virtual de la UNAD
$lin_cara01campus_compescrito,
$lin_cara01campus_portatil,
$lin_cara01campus_tableta,
$lin_cara01campus_telefono,
$lin_cara01campus_energia,
$lin_cara01campus_internetreside,
$lin_cara01campus_expvirtual,
$lin_cara01campus_ofimatica,
$lin_cara01campus_foros,
$lin_cara01campus_conversiones,
$lin_cara01campus_usocorreo,
$lin_cara44campus_usocorreounad,
$lin_cara44campus_usocorreounadno,
$lin_cara44campus_usocorreounadnodetalle,
// La informacion que consulta la aprende mejor con
$lin_cara01campus_aprendtexto,
$lin_cara01campus_aprendvideo,
$lin_cara01campus_aprendmapas,
$lin_cara01campus_aprendeanima,
$lin_cara01campus_mediocomunica,
$lin_cara44campus_medioactivunad,
$lin_cara44campus_medioactivunaddetalle,
// Datos laborales
$lin_cara01lab_situacion,
$lin_cara01lab_sector,
$lin_cara01lab_caracterjuri,
$lin_cara01lab_cargo,
$lin_cara01lab_antiguedad,
$lin_cara01lab_tipocontrato,
$lin_cara01lab_rangoingreso,
$lin_cara01lab_tiempoacadem,
$lin_cara01lab_tipoempresa,
$lin_cara01lab_tiempoindepen,
$lin_cara01lab_debebusctrab,
$lin_cara01lab_origendinero,
// Psicosocial
$lin_cara01psico_costoemocion,
$lin_cara01psico_reaccionimpre,
$lin_cara01psico_estres,
$lin_cara01psico_pocotiempo,
$lin_cara01psico_actitudvida,
$lin_cara01psico_duda,
$lin_cara01psico_problemapers,
$lin_cara01psico_satisfaccion,
$lin_cara01psico_discusiones,
$lin_cara01psico_atencion,
$lin_cara01psico_puntaje,
// Competencias
$lin_cara01fichadigital,
$lin_cara01fichalectura,
$lin_cara01ficharazona,
$lin_cara01fichaingles,
$lin_cara01fichafisica,
$lin_cara01fichaquimica,
$lin_cara01fichabiolog,
$lin_cara01fichaciudad,
// Consejero
$lin_cara01idconsejero
		);
		$objHoja1->fromArray($aCampos, null, 'A' . $iFilaHoja1);
		/*PHPEXCEL_Escribir($objHoja1, 0, $iFilaHoja1, $lin_cara01tipocaracterizacion);
		PHPEXCEL_Escribir($objHoja1, 1, $iFilaHoja1, $lin_cara01idtercero_td);
		PHPEXCEL_Escribir($objHoja1, 2, $iFilaHoja1, $lin_cara01idtercero_doc);
		PHPEXCEL_Escribir($objHoja1, 3, $iFilaHoja1, $lin_cara01idtercero_nom);
		PHPEXCEL_Escribir($objHoja1, 4, $iFilaHoja1, $lin_unad11fechaultingreso);
		PHPEXCEL_Escribir($objHoja1, 5, $iFilaHoja1, $lin_cara01fechaencuesta);
		PHPEXCEL_Escribir($objHoja1, 6, $iFilaHoja1, $lin_cara01agnos);
		PHPEXCEL_Escribir($objHoja1, 7, $iFilaHoja1, $lin_cara01sexo);
		PHPEXCEL_Escribir($objHoja1, 8, $iFilaHoja1, $lin_identidadgen);
		PHPEXCEL_Escribir($objHoja1, 9, $iFilaHoja1, $lin_orientasexo);
		PHPEXCEL_Escribir($objHoja1, 10, $iFilaHoja1, $lin_cara01pais);
		PHPEXCEL_Escribir($objHoja1, 11, $iFilaHoja1, $lin_cara01depto);
		PHPEXCEL_Escribir($objHoja1, 12, $iFilaHoja1, $lin_cara01ciudad);
		PHPEXCEL_Escribir($objHoja1, 13, $iFilaHoja1, $lin_cara01estrato);
		PHPEXCEL_Escribir($objHoja1, 14, $iFilaHoja1, $lin_cara01zonares);
		PHPEXCEL_Escribir($objHoja1, 15, $iFilaHoja1, $lin_cara01estcivil);
		PHPEXCEL_Escribir($objHoja1, 16, $iFilaHoja1, $lin_cara01nomcontacto);
		PHPEXCEL_Escribir($objHoja1, 17, $iFilaHoja1, $lin_cara01parentezcocontacto);
		PHPEXCEL_Escribir($objHoja1, 18, $iFilaHoja1, $lin_cara01idzona);
		PHPEXCEL_Escribir($objHoja1, 19, $iFilaHoja1, $lin_cara01idcead);
		PHPEXCEL_Escribir($objHoja1, 20, $iFilaHoja1, $lin_cara01idescuela);
		PHPEXCEL_Escribir($objHoja1, 21, $iFilaHoja1, $lin_cara01idprograma);
		PHPEXCEL_Escribir($objHoja1, 22, $iFilaHoja1, $lin_cara01matconvenio);
		// Grupos poblacionales
		PHPEXCEL_Escribir($objHoja1, 23, $iFilaHoja1, $lin_cara01raizal);
		PHPEXCEL_Escribir($objHoja1, 24, $iFilaHoja1, $lin_cara01palenquero);
		PHPEXCEL_Escribir($objHoja1, 25, $iFilaHoja1, $lin_cara01afrocolombiano);
		PHPEXCEL_Escribir($objHoja1, 26, $iFilaHoja1, $lin_cara01otracomunnegras);
		PHPEXCEL_Escribir($objHoja1, 27, $iFilaHoja1, $lin_cara01rom);
		PHPEXCEL_Escribir($objHoja1, 28, $iFilaHoja1, $lin_cara01indigenas);
		PHPEXCEL_Escribir($objHoja1, 29, $iFilaHoja1, $lin_cara44campesinado);
		PHPEXCEL_Escribir($objHoja1, 30, $iFilaHoja1, $lin_cara44frontera);
		PHPEXCEL_Escribir($objHoja1, 31, $iFilaHoja1, $lin_cara01victimadesplazado);
		PHPEXCEL_Escribir($objHoja1, 32, $iFilaHoja1, $lin_cara01victimaacr);
		PHPEXCEL_Escribir($objHoja1, 33, $iFilaHoja1, $lin_cara01inpecfuncionario);
		PHPEXCEL_Escribir($objHoja1, 34, $iFilaHoja1, $lin_cara01inpecrecluso);
		PHPEXCEL_Escribir($objHoja1, 35, $iFilaHoja1, $lin_cara01inpectiempocondena);
		PHPEXCEL_Escribir($objHoja1, 36, $iFilaHoja1, $lin_cara01centroreclusion);
		// Discapacidades V 1.
		PHPEXCEL_Escribir($objHoja1, 37, $iFilaHoja1, $lin_cara01discsensorial);
		PHPEXCEL_Escribir($objHoja1, 38, $iFilaHoja1, $lin_cara01discfisica);
		PHPEXCEL_Escribir($objHoja1, 39, $iFilaHoja1, $lin_cara01disccognitiva);
		PHPEXCEL_Escribir($objHoja1, 40, $iFilaHoja1, $lin_cara01perayuda);
		PHPEXCEL_Escribir($objHoja1, 41, $iFilaHoja1, $lin_cara01perotraayuda);
		// Discapacidades V 2.
		PHPEXCEL_Escribir($objHoja1, 42, $iFilaHoja1, $lin_cara01discv2sensorial);
		PHPEXCEL_Escribir($objHoja1, 43, $iFilaHoja1, $lin_cara02discv2intelectura);
		PHPEXCEL_Escribir($objHoja1, 44, $iFilaHoja1, $lin_cara02discv2fisica);
		PHPEXCEL_Escribir($objHoja1, 45, $iFilaHoja1, $lin_cara02discv2psico);
		PHPEXCEL_Escribir($objHoja1, 46, $iFilaHoja1, $lin_cara02discv2sistemica);
		PHPEXCEL_Escribir($objHoja1, 47, $iFilaHoja1, $lin_cara02discv2sistemicaotro);
		PHPEXCEL_Escribir($objHoja1, 48, $iFilaHoja1, $lin_cara02discv2multiple);
		PHPEXCEL_Escribir($objHoja1, 49, $iFilaHoja1, $lin_cara02discv2multipleotro);
		PHPEXCEL_Escribir($objHoja1, 50, $iFilaHoja1, $lin_cara01discv2archivoorigen);
		PHPEXCEL_Escribir($objHoja1, 51, $iFilaHoja1, $lin_cara01discv2trastornos);
		PHPEXCEL_Escribir($objHoja1, 52, $iFilaHoja1, $lin_cara01discv2trastaprende);
		PHPEXCEL_Escribir($objHoja1, 53, $iFilaHoja1, $lin_cara01discv2contalento);
		PHPEXCEL_Escribir($objHoja1, 54, $iFilaHoja1, $lin_cara01discv2pruebacoeficiente);
		PHPEXCEL_Escribir($objHoja1, 55, $iFilaHoja1, $lin_cara01discv2condicionmedica);
		PHPEXCEL_Escribir($objHoja1, 56, $iFilaHoja1, $lin_cara01discv2condmeddet);
		PHPEXCEL_Escribir($objHoja1, 57, $iFilaHoja1, $lin_cara44med_tratamiento);
		PHPEXCEL_Escribir($objHoja1, 58, $iFilaHoja1, $lin_cara44med_trat_cual);
		// Datos familiares
		PHPEXCEL_Escribir($objHoja1, 59, $iFilaHoja1, $lin_cara01fam_tipovivienda);
		PHPEXCEL_Escribir($objHoja1, 60, $iFilaHoja1, $lin_cara01fam_vivecon);
		PHPEXCEL_Escribir($objHoja1, 61, $iFilaHoja1, $lin_cara01fam_numpersgrupofam);
		PHPEXCEL_Escribir($objHoja1, 62, $iFilaHoja1, $lin_cara01fam_hijos);
		PHPEXCEL_Escribir($objHoja1, 63, $iFilaHoja1, $lin_cara44fam_madrecabeza);
		PHPEXCEL_Escribir($objHoja1, 64, $iFilaHoja1, $lin_cara01fam_personasacargo);
		PHPEXCEL_Escribir($objHoja1, 65, $iFilaHoja1, $lin_cara01fam_dependeecon);
		PHPEXCEL_Escribir($objHoja1, 66, $iFilaHoja1, $lin_cara01fam_escolaridadpadre);
		PHPEXCEL_Escribir($objHoja1, 67, $iFilaHoja1, $lin_cara01fam_escolaridadmadre);
		PHPEXCEL_Escribir($objHoja1, 68, $iFilaHoja1, $lin_cara01fam_numhermanos);
		PHPEXCEL_Escribir($objHoja1, 69, $iFilaHoja1, $lin_cara01fam_posicionherm);
		PHPEXCEL_Escribir($objHoja1, 70, $iFilaHoja1, $lin_cara01fam_familiaunad);
		// Datos academicos
		PHPEXCEL_Escribir($objHoja1, 71, $iFilaHoja1, $lin_cara01acad_tipocolegio);
		PHPEXCEL_Escribir($objHoja1, 72, $iFilaHoja1, $lin_cara01acad_modalidadbach);
		PHPEXCEL_Escribir($objHoja1, 73, $iFilaHoja1, $lin_cara01acad_estudioprev);
		PHPEXCEL_Escribir($objHoja1, 74, $iFilaHoja1, $lin_cara01acad_ultnivelest);
		PHPEXCEL_Escribir($objHoja1, 75, $iFilaHoja1, $lin_cara01acad_tiemposinest);
		PHPEXCEL_Escribir($objHoja1, 76, $iFilaHoja1, $lin_cara01acad_obtubodiploma);
		PHPEXCEL_Escribir($objHoja1, 77, $iFilaHoja1, $lin_cara01acad_hatomadovirtual);
		PHPEXCEL_Escribir($objHoja1, 78, $iFilaHoja1, $lin_cara01acad_razonestudio);
		PHPEXCEL_Escribir($objHoja1, 79, $iFilaHoja1, $lin_cara01acad_primeraopc);
		PHPEXCEL_Escribir($objHoja1, 80, $iFilaHoja1, $lin_cara01acad_programagusto);
		PHPEXCEL_Escribir($objHoja1, 81, $iFilaHoja1, $lin_cara01acad_razonunad);
		PHPEXCEL_Escribir($objHoja1, 82, $iFilaHoja1, $lin_cara44acadhatenidorecesos);
		PHPEXCEL_Escribir($objHoja1, 83, $iFilaHoja1, $lin_cara44acadrazonreceso);
		PHPEXCEL_Escribir($objHoja1, 84, $iFilaHoja1, $lin_cara44acadrazonrecesodetalle);
		// Con cuales equipos electronicos cuenta para acceder al campus virtual de la UNAD
		PHPEXCEL_Escribir($objHoja1, 85, $iFilaHoja1, $lin_cara01campus_compescrito);
		PHPEXCEL_Escribir($objHoja1, 86, $iFilaHoja1, $lin_cara01campus_portatil);
		PHPEXCEL_Escribir($objHoja1, 87, $iFilaHoja1, $lin_cara01campus_tableta);
		PHPEXCEL_Escribir($objHoja1, 88, $iFilaHoja1, $lin_cara01campus_telefono);
		PHPEXCEL_Escribir($objHoja1, 89, $iFilaHoja1, $lin_cara01campus_energia);
		PHPEXCEL_Escribir($objHoja1, 90, $iFilaHoja1, $lin_cara01campus_internetreside);
		PHPEXCEL_Escribir($objHoja1, 91, $iFilaHoja1, $lin_cara01campus_expvirtual);
		PHPEXCEL_Escribir($objHoja1, 92, $iFilaHoja1, $lin_cara01campus_ofimatica);
		PHPEXCEL_Escribir($objHoja1, 93, $iFilaHoja1, $lin_cara01campus_foros);
		PHPEXCEL_Escribir($objHoja1, 94, $iFilaHoja1, $lin_cara01campus_conversiones);
		PHPEXCEL_Escribir($objHoja1, 95, $iFilaHoja1, $lin_cara01campus_usocorreo);
		PHPEXCEL_Escribir($objHoja1, 96, $iFilaHoja1, $lin_cara44campus_usocorreounad);
		PHPEXCEL_Escribir($objHoja1, 97, $iFilaHoja1, $lin_cara44campus_usocorreounadno);
		PHPEXCEL_Escribir($objHoja1, 98, $iFilaHoja1, $lin_cara44campus_usocorreounadnodetalle);
		// La informacion que consulta la aprende mejor con
		PHPEXCEL_Escribir($objHoja1, 99, $iFilaHoja1, $lin_cara01campus_aprendtexto);
		PHPEXCEL_Escribir($objHoja1, 100, $iFilaHoja1, $lin_cara01campus_aprendvideo);
		PHPEXCEL_Escribir($objHoja1, 101, $iFilaHoja1, $lin_cara01campus_aprendmapas);
		PHPEXCEL_Escribir($objHoja1, 102, $iFilaHoja1, $lin_cara01campus_aprendeanima);
		PHPEXCEL_Escribir($objHoja1, 103, $iFilaHoja1, $lin_cara01campus_mediocomunica);
		PHPEXCEL_Escribir($objHoja1, 104, $iFilaHoja1, $lin_cara44campus_medioactivunad);
		PHPEXCEL_Escribir($objHoja1, 105, $iFilaHoja1, $lin_cara44campus_medioactivunaddetalle);
		// Datos laborales
		PHPEXCEL_Escribir($objHoja1, 106, $iFilaHoja1, $lin_cara01lab_situacion);
		PHPEXCEL_Escribir($objHoja1, 107, $iFilaHoja1, $lin_cara01lab_sector);
		PHPEXCEL_Escribir($objHoja1, 108, $iFilaHoja1, $lin_cara01lab_caracterjuri);
		PHPEXCEL_Escribir($objHoja1, 109, $iFilaHoja1, $lin_cara01lab_cargo);
		PHPEXCEL_Escribir($objHoja1, 110, $iFilaHoja1, $lin_cara01lab_antiguedad);
		PHPEXCEL_Escribir($objHoja1, 111, $iFilaHoja1, $lin_cara01lab_tipocontrato);
		PHPEXCEL_Escribir($objHoja1, 112, $iFilaHoja1, $lin_cara01lab_rangoingreso);
		PHPEXCEL_Escribir($objHoja1, 113, $iFilaHoja1, $lin_cara01lab_tiempoacadem);
		PHPEXCEL_Escribir($objHoja1, 114, $iFilaHoja1, $lin_cara01lab_tipoempresa);
		PHPEXCEL_Escribir($objHoja1, 115, $iFilaHoja1, $lin_cara01lab_tiempoindepen);
		PHPEXCEL_Escribir($objHoja1, 116, $iFilaHoja1, $lin_cara01lab_debebusctrab);
		PHPEXCEL_Escribir($objHoja1, 117, $iFilaHoja1, $lin_cara01lab_origendinero);
		// Psicosocial
		PHPEXCEL_Escribir($objHoja1, 118, $iFilaHoja1, $lin_cara01psico_costoemocion);
		PHPEXCEL_Escribir($objHoja1, 119, $iFilaHoja1, $lin_cara01psico_reaccionimpre);
		PHPEXCEL_Escribir($objHoja1, 120, $iFilaHoja1, $lin_cara01psico_estres);
		PHPEXCEL_Escribir($objHoja1, 121, $iFilaHoja1, $lin_cara01psico_pocotiempo);
		PHPEXCEL_Escribir($objHoja1, 122, $iFilaHoja1, $lin_cara01psico_actitudvida);
		PHPEXCEL_Escribir($objHoja1, 123, $iFilaHoja1, $lin_cara01psico_duda);
		PHPEXCEL_Escribir($objHoja1, 124, $iFilaHoja1, $lin_cara01psico_problemapers);
		PHPEXCEL_Escribir($objHoja1, 125, $iFilaHoja1, $lin_cara01psico_satisfaccion);
		PHPEXCEL_Escribir($objHoja1, 126, $iFilaHoja1, $lin_cara01psico_discusiones);
		PHPEXCEL_Escribir($objHoja1, 127, $iFilaHoja1, $lin_cara01psico_atencion);
		PHPEXCEL_Escribir($objHoja1, 128, $iFilaHoja1, $lin_cara01psico_puntaje);
		// Competencias
		PHPEXCEL_Escribir($objHoja1, 129, $iFilaHoja1, $lin_cara01fichadigital);
		PHPEXCEL_Escribir($objHoja1, 130, $iFilaHoja1, $lin_cara01fichalectura);
		PHPEXCEL_Escribir($objHoja1, 131, $iFilaHoja1, $lin_cara01ficharazona);
		PHPEXCEL_Escribir($objHoja1, 132, $iFilaHoja1, $lin_cara01fichaingles);
		PHPEXCEL_Escribir($objHoja1, 133, $iFilaHoja1, $lin_cara01fichafisica);
		PHPEXCEL_Escribir($objHoja1, 134, $iFilaHoja1, $lin_cara01fichaquimica);
		PHPEXCEL_Escribir($objHoja1, 135, $iFilaHoja1, $lin_cara01fichabiolog);
		PHPEXCEL_Escribir($objHoja1, 136, $iFilaHoja1, $lin_cara01fichaciudad);
		// Consejero
		PHPEXCEL_Escribir($objHoja1, 137, $iFilaHoja1, $lin_cara01idconsejero);*/
		$iFilaHoja1++;

		// HOJA 2 - Bienestar v.1
		// Deporte y Recreacion
		// Usted practica regularmente alguna de las siguientes actividades deportivas o recreativas
		$lin_cara01bien_baloncesto = '';
		$lin_cara01bien_voleibol = '';
		$lin_cara01bien_futbolsala = '';
		$lin_cara01bien_artesmarc = '';
		$lin_cara01bien_tenisdemesa = '';
		$lin_cara01bien_ajedrez = '';
		$lin_cara01bien_juegosautoc = '';
		$lin_cara01bien_interesrepdeporte = '';
		$lin_cara01bien_deporteint = '';
		// Usted practica regularmente alguna de las siguientes actividades artisticas o culturales
		$lin_cara01bien_teatro = '';
		$lin_cara01bien_danza = '';
		$lin_cara01bien_musica = '';
		$lin_cara01bien_circo = '';
		$lin_cara01bien_artplast = '';
		$lin_cara01bien_cuenteria = '';
		$lin_cara01bien_interesreparte = '';
		$lin_cara01bien_arteint = '';
		$lin_cara01bien_interpreta = '';
		$lin_cara01bien_nivelinter = '';
		// Si usted practica danza por favor indique el genero
		$lin_cara01bien_danza_mod = '';
		$lin_cara01bien_danza_clas = '';
		$lin_cara01bien_danza_cont = '';
		$lin_cara01bien_danza_folk = '';
		// Emprendimiento
		$lin_cara01bien_emprendedor = '';
		$lin_cara01bien_nombreemp = '';
		$lin_cara01bien_capacempren = '';
		// Estilo de vida saludable
		$lin_cara01bien_impvidasalud = '';
		$lin_cara01bien_estraautocuid = '';
		// Proyecto de vida
		$lin_cara01bien_pv_personal = '';
		// Medio ambiente
		$lin_cara01bien_amb = '';
		// Cual de estos habitos cotidianos realiza usted como una practica de respeto hacia Medio Ambiente
		$lin_cara01bien_amb_agu = '';
		$lin_cara01bien_amb_bom = '';
		$lin_cara01bien_amb_car = '';
		$lin_cara01bien_amb_info = '';
		if ($aBloque[5] && $bVerBienV1) {
			//Bienestar
			$lin_cara01bien_baloncesto = $fila['cara01bien_baloncesto'];
			$lin_cara01bien_voleibol = $fila['cara01bien_voleibol'];
			$lin_cara01bien_futbolsala = $fila['cara01bien_futbolsala'];
			$lin_cara01bien_artesmarc = $fila['cara01bien_artesmarc'];
			$lin_cara01bien_tenisdemesa = $fila['cara01bien_tenisdemesa'];
			$lin_cara01bien_ajedrez = $fila['cara01bien_ajedrez'];
			$lin_cara01bien_juegosautoc = $fila['cara01bien_juegosautoc'];
			$lin_cara01bien_interesrepdeporte = $fila['cara01bien_interesrepdeporte'];
			$lin_cara01bien_deporteint = $fila['cara01bien_deporteint'];
			$lin_cara01bien_teatro = $fila['cara01bien_teatro'];
			$lin_cara01bien_danza = $fila['cara01bien_danza'];
			$lin_cara01bien_musica = $fila['cara01bien_musica'];
			$lin_cara01bien_circo = $fila['cara01bien_circo'];
			$lin_cara01bien_artplast = $fila['cara01bien_artplast'];
			$lin_cara01bien_cuenteria = $fila['cara01bien_cuenteria'];
			$lin_cara01bien_interesreparte = $fila['cara01bien_interesreparte'];
			$lin_cara01bien_arteint = $fila['cara01bien_arteint'];
			if ($fila['cara01bien_interpreta'] == -1) {
				$lin_cara01bien_interpreta = 'Ninguno';
			} else {
				$lin_cara01bien_interpreta = '[' . $fila['cara01bien_interpreta'] . ']';
				if (isset($acara01bien_interpreta[$fila['cara01bien_interpreta']]) != 0) {
					$lin_cara01bien_interpreta = $acara01bien_interpreta[$fila['cara01bien_interpreta']];
				}
				$lin_cara01bien_nivelinter = '[' . $fila['cara01bien_nivelinter'] . ']';
				if (isset($acara01bien_nivelinter[$fila['cara01bien_nivelinter']]) != 0) {
					$lin_cara01bien_nivelinter = $acara01bien_nivelinter[$fila['cara01bien_nivelinter']];
				}
			}
			$lin_cara01bien_danza_mod = $fila['cara01bien_danza_mod'];
			$lin_cara01bien_danza_clas = $fila['cara01bien_danza_clas'];
			$lin_cara01bien_danza_cont = $fila['cara01bien_danza_cont'];
			$lin_cara01bien_danza_folk = $fila['cara01bien_danza_folk'];
			$lin_cara01bien_niveldanza = '[' . $fila['cara01bien_niveldanza'] . ']';
			if (isset($acara01bien_niveldanza[$fila['cara01bien_niveldanza']]) != 0) {
				$lin_cara01bien_niveldanza = $acara01bien_niveldanza[$fila['cara01bien_niveldanza']];
			}
			$lin_cara01bien_emprendedor = $fila['cara01bien_emprendedor'];
			$lin_cara01bien_nombreemp = $fila['cara01bien_nombreemp'];
			$lin_cara01bien_capacempren = $fila['cara01bien_capacempren'];
			if (isset($acara01bien_capacempren[$fila['cara01bien_capacempren']]) != 0) {
				$lin_cara01bien_capacempren = $acara01bien_capacempren[$fila['cara01bien_capacempren']];
			}
			$lin_cara01bien_tipocapacita = $fila['cara01bien_tipocapacita'];
			$lin_cara01bien_impvidasalud = $fila['cara01bien_impvidasalud'];
			if (isset($acara01bien_impvidasalud[$fila['cara01bien_impvidasalud']]) != 0) {
				$lin_cara01bien_impvidasalud = $acara01bien_impvidasalud[$fila['cara01bien_impvidasalud']];
			}
			$lin_cara01bien_estraautocuid = $fila['cara01bien_estraautocuid'];
			if (isset($acara01bien_estraautocuid[$fila['cara01bien_estraautocuid']]) != 0) {
				$lin_cara01bien_estraautocuid = $acara01bien_estraautocuid[$fila['cara01bien_estraautocuid']];
			}
			$lin_cara01bien_pv_personal = $fila['cara01bien_pv_personal'];
			if (isset($acara01bien_pv_personal[$fila['cara01bien_pv_personal']]) != 0) {
				$lin_cara01bien_pv_personal = $acara01bien_pv_personal[$fila['cara01bien_pv_personal']];
			}
			$lin_cara01bien_pv_familiar = $fila['cara01bien_pv_familiar'];
			$lin_cara01bien_pv_academ = $fila['cara01bien_pv_academ'];
			$lin_cara01bien_pv_labora = $fila['cara01bien_pv_labora'];
			$lin_cara01bien_pv_pareja = $fila['cara01bien_pv_pareja'];
			$lin_cara01bien_amb = $fila['cara01bien_amb'];
			if (isset($acara01bien_amb[$fila['cara01bien_amb']]) != 0) {
				$lin_cara01bien_amb = $acara01bien_amb[$fila['cara01bien_amb']];
			}
			$lin_cara01bien_amb_agu = $fila['cara01bien_amb_agu'];
			$lin_cara01bien_amb_bom = $fila['cara01bien_amb_bom'];
			$lin_cara01bien_amb_car = $fila['cara01bien_amb_car'];
			$lin_cara01bien_amb_info = $fila['cara01bien_amb_info'];
			$lin_cara01bien_amb_temas = $fila['cara01bien_amb_temas'];

			$objExcel->setActiveSheetIndex(1);
			$objHoja2 = $objExcel->getActiveSheet();
			PHPEXCEL_Escribir($objHoja2, 0, $iFilaHoja2, $lin_cara01idtercero_td);
			PHPEXCEL_Escribir($objHoja2, 1, $iFilaHoja2, $lin_cara01idtercero_doc);
			PHPEXCEL_Escribir($objHoja2, 2, $iFilaHoja2, $lin_cara01idtercero_nom);
			// Usted practica regularmente alguna de las siguientes actividades deportivas o recreativas
			PHPEXCEL_Escribir($objHoja2, 3, $iFilaHoja2, $lin_cara01bien_baloncesto);
			PHPEXCEL_Escribir($objHoja2, 4, $iFilaHoja2, $lin_cara01bien_voleibol);
			PHPEXCEL_Escribir($objHoja2, 5, $iFilaHoja2, $lin_cara01bien_futbolsala);
			PHPEXCEL_Escribir($objHoja2, 6, $iFilaHoja2, $lin_cara01bien_artesmarc);
			PHPEXCEL_Escribir($objHoja2, 7, $iFilaHoja2, $lin_cara01bien_tenisdemesa);
			PHPEXCEL_Escribir($objHoja2, 8, $iFilaHoja2, $lin_cara01bien_ajedrez);
			PHPEXCEL_Escribir($objHoja2, 9, $iFilaHoja2, $lin_cara01bien_juegosautoc);
			PHPEXCEL_Escribir($objHoja2, 10, $iFilaHoja2, $lin_cara01bien_interesrepdeporte);
			PHPEXCEL_Escribir($objHoja2, 11, $iFilaHoja2, $lin_cara01bien_deporteint);
			// Usted practica regularmente alguna de las siguientes actividades artisticas o culturales
			PHPEXCEL_Escribir($objHoja2, 12, $iFilaHoja2, $lin_cara01bien_teatro);
			PHPEXCEL_Escribir($objHoja2, 13, $iFilaHoja2, $lin_cara01bien_danza);
			PHPEXCEL_Escribir($objHoja2, 14, $iFilaHoja2, $lin_cara01bien_musica);
			PHPEXCEL_Escribir($objHoja2, 15, $iFilaHoja2, $lin_cara01bien_circo);
			PHPEXCEL_Escribir($objHoja2, 16, $iFilaHoja2, $lin_cara01bien_artplast);
			PHPEXCEL_Escribir($objHoja2, 17, $iFilaHoja2, $lin_cara01bien_cuenteria);
			PHPEXCEL_Escribir($objHoja2, 18, $iFilaHoja2, $lin_cara01bien_interesreparte);
			PHPEXCEL_Escribir($objHoja2, 19, $iFilaHoja2, $lin_cara01bien_arteint);
			PHPEXCEL_Escribir($objHoja2, 20, $iFilaHoja2, $lin_cara01bien_interpreta);
			PHPEXCEL_Escribir($objHoja2, 21, $iFilaHoja2, $lin_cara01bien_nivelinter);
			// Si usted practica danza por favor indique el genero
			PHPEXCEL_Escribir($objHoja2, 22, $iFilaHoja2, $lin_cara01bien_danza_mod);
			PHPEXCEL_Escribir($objHoja2, 23, $iFilaHoja2, $lin_cara01bien_danza_clas);
			PHPEXCEL_Escribir($objHoja2, 24, $iFilaHoja2, $lin_cara01bien_danza_cont);
			PHPEXCEL_Escribir($objHoja2, 25, $iFilaHoja2, $lin_cara01bien_danza_folk);
			// Emprendimiento
			PHPEXCEL_Escribir($objHoja2, 26, $iFilaHoja2, $lin_cara01bien_emprendedor);
			PHPEXCEL_Escribir($objHoja2, 27, $iFilaHoja2, $lin_cara01bien_nombreemp);
			PHPEXCEL_Escribir($objHoja2, 28, $iFilaHoja2, $lin_cara01bien_capacempren);
			// Estilo de vida saludable
			PHPEXCEL_Escribir($objHoja2, 29, $iFilaHoja2, $lin_cara01bien_impvidasalud);
			PHPEXCEL_Escribir($objHoja2, 30, $iFilaHoja2, $lin_cara01bien_estraautocuid);
			// Proyecto de vida
			PHPEXCEL_Escribir($objHoja2, 31, $iFilaHoja2, $lin_cara01bien_pv_personal);
			// Medio ambiente
			PHPEXCEL_Escribir($objHoja2, 32, $iFilaHoja2, $lin_cara01bien_amb);
			// Cual de estos habitos cotidianos realiza usted como una practica de respeto hacia Medio Ambiente
			PHPEXCEL_Escribir($objHoja2, 33, $iFilaHoja2, $lin_cara01bien_amb_agu);
			PHPEXCEL_Escribir($objHoja2, 34, $iFilaHoja2, $lin_cara01bien_amb_bom);
			PHPEXCEL_Escribir($objHoja2, 35, $iFilaHoja2, $lin_cara01bien_amb_car);
			PHPEXCEL_Escribir($objHoja2, 36, $iFilaHoja2, $lin_cara01bien_amb_info);
			$iFilaHoja2++;
		}

		// HOJA 3 - Bienestar v.2
		// Deporte y Recreacion
		$lin_cara44bienv2altoren = '';
		// ¿Que deporte practica?
		$lin_cara44bienv2atletismo = '';
		$lin_cara44bienv2baloncesto = '';
		$lin_cara44bienv2futbol = '';
		$lin_cara44bienv2gimnasia = '';
		$lin_cara44bienv2natacion = '';
		$lin_cara44bienv2voleibol = '';
		$lin_cara44bienv2tenis = '';
		$lin_cara44bienv2paralimpico = '';
		$lin_cara44bienv2otrodeporte = '';
		$lin_cara44bienv2otrodeportedetalle = '';
		// Arte y Cultura
		// Usted practica regularmente alguna de las siguientes actividades artisticas o culturales
		$lin_cara44bienv2activdanza = '';
		$lin_cara44bienv2activmusica = '';
		$lin_cara44bienv2activteatro = '';
		$lin_cara44bienv2activartes = '';
		$lin_cara44bienv2activliteratura = '';
		$lin_cara44bienv2activculturalotra = '';
		$lin_cara44bienv2activculturalotradetalle = '';
		// A que clase de eventos artisticos y culturales le gustaria asistir
		$lin_cara44bienv2evenfestfolc = '';
		$lin_cara44bienv2evenexpoarte = '';
		$lin_cara44bienv2evenhistarte = '';
		$lin_cara44bienv2evengalfoto = '';
		$lin_cara44bienv2evenliteratura = '';
		$lin_cara44bienv2eventeatro = '';
		$lin_cara44bienv2evencine = '';
		$lin_cara44bienv2evenculturalotro = '';
		$lin_cara44bienv2evenculturalotrodetalle = '';
		// Emprendimiento
		$lin_cara44bienv2emprendimiento = '';
		$lin_cara44bienv2empresa = '';
		// Cual es el estado en que se encuentra su emprendimiento
		$lin_cara44bienv2emprenrecursos = '';
		$lin_cara44bienv2emprenconocim = '';
		$lin_cara44bienv2emprenplan = '';
		$lin_cara44bienv2emprenejecutar = '';
		$lin_cara44bienv2emprenfortconocim = '';
		$lin_cara44bienv2emprenidentproblema = '';
		$lin_cara44bienv2emprenotro = '';
		$lin_cara44bienv2emprenotrodetalle = '';
		// En que temas le gustaria recibir informacion con respecto al emprendimiento
		$lin_cara44bienv2emprenmarketing = '';
		$lin_cara44bienv2emprenplannegocios = '';
		$lin_cara44bienv2emprenideas = '';
		$lin_cara44bienv2emprencreacion = '';
		// Estilo de vida saludable
		// Causas mas frecuentes del estres
		$lin_cara44bienv2saludfacteconom = '';
		$lin_cara44bienv2saludpreocupacion = '';
		$lin_cara44bienv2saludconsumosust = '';
		$lin_cara44bienv2saludinsomnio = '';
		$lin_cara44bienv2saludclimalab = '';
		// Estrategias para conocer el autocuidado
		$lin_cara44bienv2saludalimenta = '';
		$lin_cara44bienv2saludemocion = '';
		$lin_cara44bienv2saludestado = '';
		$lin_cara44bienv2saludmedita = '';
		// Crecimiento Personal
		// Temas de interes para su crecimiento personal
		$lin_cara44bienv2crecimedusexual = '';
		$lin_cara44bienv2crecimcultciudad = '';
		$lin_cara44bienv2crecimrelpareja = '';
		$lin_cara44bienv2crecimrelinterp = '';
		$lin_cara44bienv2crecimdinamicafam = '';
		$lin_cara44bienv2crecimautoestima = '';
		$lin_cara44bienv2creciminclusion = '';
		$lin_cara44bienv2creciminteliemoc = '';
		// Le gustaria hacer parte de algun grupo de bienestar
		$lin_cara44bienv2crecimcultural = '';
		$lin_cara44bienv2crecimartistico = '';
		$lin_cara44bienv2crecimdeporte = '';
		$lin_cara44bienv2crecimambiente = '';
		$lin_cara44bienv2crecimhabsocio = '';
		// Medio ambiente
		// Realiza alguna de estas acciones frente al cuidado del medio ambiente
		$lin_cara44bienv2ambienbasura = '';
		$lin_cara44bienv2ambienreutiliza = '';
		$lin_cara44bienv2ambienluces = '';
		$lin_cara44bienv2ambienfrutaverd = '';
		$lin_cara44bienv2ambienenchufa = '';
		$lin_cara44bienv2ambiengrifo = '';
		$lin_cara44bienv2ambienbicicleta = '';
		$lin_cara44bienv2ambientranspub = '';
		$lin_cara44bienv2ambienducha = '';
		// En su tiempo libre ha participado en alguna actividad ambiental
		$lin_cara44bienv2ambiencaminata = '';
		$lin_cara44bienv2ambiensiembra = '';
		$lin_cara44bienv2ambienconferencia = '';
		$lin_cara44bienv2ambienrecicla = '';
		$lin_cara44bienv2ambienotraactiv = '';
		$lin_cara44bienv2ambienotraactivdetalle = '';
		// Cual tema desde el enfoque ambiental le gustaria conocer o profundizar
		$lin_cara44bienv2ambienreforest = '';
		$lin_cara44bienv2ambienmovilidad = '';
		$lin_cara44bienv2ambienclimatico = '';
		$lin_cara44bienv2ambienecofemin = '';
		$lin_cara44bienv2ambienbiodiver = '';
		$lin_cara44bienv2ambienecologia = '';
		$lin_cara44bienv2ambieneconomia = '';
		$lin_cara44bienv2ambienrecnatura = '';
		$lin_cara44bienv2ambienreciclaje = '';
		$lin_cara44bienv2ambienmascota = '';
		$lin_cara44bienv2ambiencartohum = '';
		$lin_cara44bienv2ambienespiritu = '';
		$lin_cara44bienv2ambiencarga = '';
		$lin_cara44bienv2ambienotroenfoq = '';
		$lin_cara44bienv2ambienotroenfoqdetalle = '';
		if ($bHayFilascara44) {
			if ($aBloque[5] && $bVerBienV2) {
				$lin_cara44bienv2altoren = $fila['cara44bienv2altoren'];
				$lin_cara44bienv2atletismo = $fila['cara44bienv2atletismo'];
				$lin_cara44bienv2baloncesto = $fila['cara44bienv2baloncesto'];
				$lin_cara44bienv2futbol = $fila['cara44bienv2futbol'];
				$lin_cara44bienv2gimnasia = $fila['cara44bienv2gimnasia'];
				$lin_cara44bienv2natacion = $fila['cara44bienv2natacion'];
				$lin_cara44bienv2voleibol = $fila['cara44bienv2voleibol'];
				$lin_cara44bienv2tenis = $fila['cara44bienv2tenis'];
				$lin_cara44bienv2paralimpico = $fila['cara44bienv2paralimpico'];
				$lin_cara44bienv2otrodeporte = $fila['cara44bienv2otrodeporte'];
				$lin_cara44bienv2otrodeportedetalle = $fila['cara44bienv2otrodeportedetalle'];
				$lin_cara44bienv2activdanza = $fila['cara44bienv2activdanza'];
				$lin_cara44bienv2activmusica = $fila['cara44bienv2activmusica'];
				$lin_cara44bienv2activteatro = $fila['cara44bienv2activteatro'];
				$lin_cara44bienv2activartes = $fila['cara44bienv2activartes'];
				$lin_cara44bienv2activliteratura = $fila['cara44bienv2activliteratura'];
				$lin_cara44bienv2activculturalotra = $fila['cara44bienv2activculturalotra'];
				$lin_cara44bienv2activculturalotradetalle = $fila['cara44bienv2activculturalotradetalle'];
				$lin_cara44bienv2evenfestfolc = $fila['cara44bienv2evenfestfolc'];
				$lin_cara44bienv2evenexpoarte = $fila['cara44bienv2evenexpoarte'];
				$lin_cara44bienv2evenhistarte = $fila['cara44bienv2evenhistarte'];
				$lin_cara44bienv2evengalfoto = $fila['cara44bienv2evengalfoto'];
				$lin_cara44bienv2evenliteratura = $fila['cara44bienv2evenliteratura'];
				$lin_cara44bienv2eventeatro = $fila['cara44bienv2eventeatro'];
				$lin_cara44bienv2evencine = $fila['cara44bienv2evencine'];
				$lin_cara44bienv2evenculturalotro = $fila['cara44bienv2evenculturalotro'];
				$lin_cara44bienv2evenculturalotrodetalle = $fila['cara44bienv2evenculturalotrodetalle'];
				$lin_cara44bienv2emprendimiento = $fila['cara44bienv2emprendimiento'];
				$lin_cara44bienv2empresa = $fila['cara44bienv2empresa'];
				$lin_cara44bienv2emprenrecursos = $fila['cara44bienv2emprenrecursos'];
				$lin_cara44bienv2emprenconocim = $fila['cara44bienv2emprenconocim'];
				$lin_cara44bienv2emprenplan = $fila['cara44bienv2emprenplan'];
				$lin_cara44bienv2emprenejecutar = $fila['cara44bienv2emprenejecutar'];
				$lin_cara44bienv2emprenfortconocim = $fila['cara44bienv2emprenfortconocim'];
				$lin_cara44bienv2emprenidentproblema = $fila['cara44bienv2emprenidentproblema'];
				$lin_cara44bienv2emprenotro = $fila['cara44bienv2emprenotro'];
				$lin_cara44bienv2emprenotrodetalle = $fila['cara44bienv2emprenotrodetalle'];
				$lin_cara44bienv2emprenmarketing = $fila['cara44bienv2emprenmarketing'];
				$lin_cara44bienv2emprenplannegocios = $fila['cara44bienv2emprenplannegocios'];
				$lin_cara44bienv2emprenideas = $fila['cara44bienv2emprenideas'];
				$lin_cara44bienv2emprencreacion = $fila['cara44bienv2emprencreacion'];
				$lin_cara44bienv2saludfacteconom = $fila['cara44bienv2saludfacteconom'];
				$lin_cara44bienv2saludpreocupacion = $fila['cara44bienv2saludpreocupacion'];
				$lin_cara44bienv2saludconsumosust = $fila['cara44bienv2saludconsumosust'];
				$lin_cara44bienv2saludinsomnio = $fila['cara44bienv2saludinsomnio'];
				$lin_cara44bienv2saludclimalab = $fila['cara44bienv2saludclimalab'];
				$lin_cara44bienv2saludalimenta = $fila['cara44bienv2saludalimenta'];
				$lin_cara44bienv2saludemocion = $fila['cara44bienv2saludemocion'];
				$lin_cara44bienv2saludestado = $fila['cara44bienv2saludestado'];
				$lin_cara44bienv2saludmedita = $fila['cara44bienv2saludmedita'];
				$lin_cara44bienv2crecimedusexual = $fila['cara44bienv2crecimedusexual'];
				$lin_cara44bienv2crecimcultciudad = $fila['cara44bienv2crecimcultciudad'];
				$lin_cara44bienv2crecimrelpareja = $fila['cara44bienv2crecimrelpareja'];
				$lin_cara44bienv2crecimrelinterp = $fila['cara44bienv2crecimrelinterp'];
				$lin_cara44bienv2crecimdinamicafam = $fila['cara44bienv2crecimdinamicafam'];
				$lin_cara44bienv2crecimautoestima = $fila['cara44bienv2crecimautoestima'];
				$lin_cara44bienv2creciminclusion = $fila['cara44bienv2creciminclusion'];
				$lin_cara44bienv2creciminteliemoc = $fila['cara44bienv2creciminteliemoc'];
				$lin_cara44bienv2crecimcultural = $fila['cara44bienv2crecimcultural'];
				$lin_cara44bienv2crecimartistico = $fila['cara44bienv2crecimartistico'];
				$lin_cara44bienv2crecimdeporte = $fila['cara44bienv2crecimdeporte'];
				$lin_cara44bienv2crecimambiente = $fila['cara44bienv2crecimambiente'];
				$lin_cara44bienv2crecimhabsocio = $fila['cara44bienv2crecimhabsocio'];
				$lin_cara44bienv2ambienbasura = $fila['cara44bienv2ambienbasura'];
				$lin_cara44bienv2ambienreutiliza = $fila['cara44bienv2ambienreutiliza'];
				$lin_cara44bienv2ambienluces = $fila['cara44bienv2ambienluces'];
				$lin_cara44bienv2ambienfrutaverd = $fila['cara44bienv2ambienfrutaverd'];
				$lin_cara44bienv2ambienenchufa = $fila['cara44bienv2ambienenchufa'];
				$lin_cara44bienv2ambiengrifo = $fila['cara44bienv2ambiengrifo'];
				$lin_cara44bienv2ambienbicicleta = $fila['cara44bienv2ambienbicicleta'];
				$lin_cara44bienv2ambientranspub = $fila['cara44bienv2ambientranspub'];
				$lin_cara44bienv2ambienducha = $fila['cara44bienv2ambienducha'];
				$lin_cara44bienv2ambiencaminata = $fila['cara44bienv2ambiencaminata'];
				$lin_cara44bienv2ambiensiembra = $fila['cara44bienv2ambiensiembra'];
				$lin_cara44bienv2ambienconferencia = $fila['cara44bienv2ambienconferencia'];
				$lin_cara44bienv2ambienrecicla = $fila['cara44bienv2ambienrecicla'];
				$lin_cara44bienv2ambienotraactiv = $fila['cara44bienv2ambienotraactiv'];
				$lin_cara44bienv2ambienotraactivdetalle = $fila['cara44bienv2ambienotraactivdetalle'];
				$lin_cara44bienv2ambienreforest = $fila['cara44bienv2ambienreforest'];
				$lin_cara44bienv2ambienmovilidad = $fila['cara44bienv2ambienmovilidad'];
				$lin_cara44bienv2ambienclimatico = $fila['cara44bienv2ambienclimatico'];
				$lin_cara44bienv2ambienecofemin = $fila['cara44bienv2ambienecofemin'];
				$lin_cara44bienv2ambienbiodiver = $fila['cara44bienv2ambienbiodiver'];
				$lin_cara44bienv2ambienecologia = $fila['cara44bienv2ambienecologia'];
				$lin_cara44bienv2ambieneconomia = $fila['cara44bienv2ambieneconomia'];
				$lin_cara44bienv2ambienrecnatura = $fila['cara44bienv2ambienrecnatura'];
				$lin_cara44bienv2ambienreciclaje = $fila['cara44bienv2ambienreciclaje'];
				$lin_cara44bienv2ambienmascota = $fila['cara44bienv2ambienmascota'];
				$lin_cara44bienv2ambiencartohum = $fila['cara44bienv2ambiencartohum'];
				$lin_cara44bienv2ambienespiritu = $fila['cara44bienv2ambienespiritu'];
				$lin_cara44bienv2ambiencarga = $fila['cara44bienv2ambiencarga'];
				$lin_cara44bienv2ambienotroenfoq = $fila['cara44bienv2ambienotroenfoq'];
				$lin_cara44bienv2ambienotroenfoqdetalle = $fila['cara44bienv2ambienotroenfoqdetalle'];

				$objExcel->setActiveSheetIndex(2);
				$objHoja3 = $objExcel->getActiveSheet();
				$aCampos = array(
$lin_cara01idtercero_td,
$lin_cara01idtercero_doc,
$lin_cara01idtercero_nom,
// Deporte y Recreacion
$lin_cara44bienv2altoren,
// ¿Que deporte practica?
$lin_cara44bienv2atletismo,
$lin_cara44bienv2baloncesto,
$lin_cara44bienv2futbol,
$lin_cara44bienv2gimnasia,
$lin_cara44bienv2natacion,
$lin_cara44bienv2voleibol,
$lin_cara44bienv2tenis,
$lin_cara44bienv2paralimpico,
$lin_cara44bienv2otrodeporte,
$lin_cara44bienv2otrodeportedetalle,
// Arte y Cultura
// Usted practica regularmente alguna de las siguientes actividades artisticas o culturales
$lin_cara44bienv2activdanza,
$lin_cara44bienv2activmusica,
$lin_cara44bienv2activteatro,
$lin_cara44bienv2activartes,
$lin_cara44bienv2activliteratura,
$lin_cara44bienv2activculturalotra,
$lin_cara44bienv2activculturalotradetalle,
// A que clase de eventos artisticos y culturales le gustaria asistir
$lin_cara44bienv2evenfestfolc,
$lin_cara44bienv2evenexpoarte,
$lin_cara44bienv2evenhistarte,
$lin_cara44bienv2evengalfoto,
$lin_cara44bienv2evenliteratura,
$lin_cara44bienv2eventeatro,
$lin_cara44bienv2evencine,
$lin_cara44bienv2evenculturalotro,
$lin_cara44bienv2evenculturalotrodetalle,
// Emprendimiento
$lin_cara44bienv2emprendimiento,
$lin_cara44bienv2empresa,
// Cual es el estado en que se encuentra su emprendimiento
$lin_cara44bienv2emprenrecursos,
$lin_cara44bienv2emprenconocim,
$lin_cara44bienv2emprenplan,
$lin_cara44bienv2emprenejecutar,
$lin_cara44bienv2emprenfortconocim,
$lin_cara44bienv2emprenidentproblema,
$lin_cara44bienv2emprenotro,
$lin_cara44bienv2emprenotrodetalle,
// En que temas le gustaria recibir informacion con respecto al emprendimiento
$lin_cara44bienv2emprenmarketing,
$lin_cara44bienv2emprenplannegocios,
$lin_cara44bienv2emprenideas,
$lin_cara44bienv2emprencreacion,
// Estilo de vida saludable
// Causas mas frecuentes del estres
$lin_cara44bienv2saludfacteconom,
$lin_cara44bienv2saludpreocupacion,
$lin_cara44bienv2saludconsumosust,
$lin_cara44bienv2saludinsomnio,
$lin_cara44bienv2saludclimalab,
// Estrategias para conocer el autocuidado
$lin_cara44bienv2saludalimenta,
$lin_cara44bienv2saludemocion,
$lin_cara44bienv2saludestado,
$lin_cara44bienv2saludmedita,
// Crecimiento Personal
// Temas de interes para su crecimiento personal
$lin_cara44bienv2crecimedusexual,
$lin_cara44bienv2crecimcultciudad,
$lin_cara44bienv2crecimrelpareja,
$lin_cara44bienv2crecimrelinterp,
$lin_cara44bienv2crecimdinamicafam,
$lin_cara44bienv2crecimautoestima,
$lin_cara44bienv2creciminclusion,
$lin_cara44bienv2creciminteliemoc,
// Le gustaria hacer parte de algun grupo de bienestar
$lin_cara44bienv2crecimcultural,
$lin_cara44bienv2crecimartistico,
$lin_cara44bienv2crecimdeporte,
$lin_cara44bienv2crecimambiente,
$lin_cara44bienv2crecimhabsocio,
// Medio ambiente
// Realiza alguna de estas acciones frente al cuidado del medio ambiente
$lin_cara44bienv2ambienbasura,
$lin_cara44bienv2ambienreutiliza,
$lin_cara44bienv2ambienluces,
$lin_cara44bienv2ambienfrutaverd,
$lin_cara44bienv2ambienenchufa,
$lin_cara44bienv2ambiengrifo,
$lin_cara44bienv2ambienbicicleta,
$lin_cara44bienv2ambientranspub,
$lin_cara44bienv2ambienducha,
// En su tiempo libre ha participado en alguna actividad ambiental
$lin_cara44bienv2ambiencaminata,
$lin_cara44bienv2ambiensiembra,
$lin_cara44bienv2ambienconferencia,
$lin_cara44bienv2ambienrecicla,
$lin_cara44bienv2ambienotraactiv,
$lin_cara44bienv2ambienotraactivdetalle,
// Cual tema desde el enfoque ambiental le gustaria conocer o profundizar
$lin_cara44bienv2ambienreforest,
$lin_cara44bienv2ambienmovilidad,
$lin_cara44bienv2ambienclimatico,
$lin_cara44bienv2ambienecofemin,
$lin_cara44bienv2ambienbiodiver,
$lin_cara44bienv2ambienecologia,
$lin_cara44bienv2ambieneconomia,
$lin_cara44bienv2ambienrecnatura,
$lin_cara44bienv2ambienreciclaje,
$lin_cara44bienv2ambienmascota,
$lin_cara44bienv2ambiencartohum,
$lin_cara44bienv2ambienespiritu,
$lin_cara44bienv2ambiencarga,
$lin_cara44bienv2ambienotroenfoq,
$lin_cara44bienv2ambienotroenfoqdetalle
				);
				$objHoja3->fromArray($aCampos, null, 'A' . $iFilaHoja3);
				/*PHPEXCEL_Escribir($objHoja3, 0, $iFilaHoja3, $lin_cara01idtercero_td);
				PHPEXCEL_Escribir($objHoja3, 1, $iFilaHoja3, $lin_cara01idtercero_doc);
				PHPEXCEL_Escribir($objHoja3, 2, $iFilaHoja3, $lin_cara01idtercero_nom);
				// Deporte y Recreacion
				PHPEXCEL_Escribir($objHoja3, 3, $iFilaHoja3, $lin_cara44bienv2altoren);
				// ¿Que deporte practica?
				PHPEXCEL_Escribir($objHoja3, 4, $iFilaHoja3, $lin_cara44bienv2atletismo);
				PHPEXCEL_Escribir($objHoja3, 5, $iFilaHoja3, $lin_cara44bienv2baloncesto);
				PHPEXCEL_Escribir($objHoja3, 6, $iFilaHoja3, $lin_cara44bienv2futbol);
				PHPEXCEL_Escribir($objHoja3, 7, $iFilaHoja3, $lin_cara44bienv2gimnasia);
				PHPEXCEL_Escribir($objHoja3, 8, $iFilaHoja3, $lin_cara44bienv2natacion);
				PHPEXCEL_Escribir($objHoja3, 9, $iFilaHoja3, $lin_cara44bienv2voleibol);
				PHPEXCEL_Escribir($objHoja3, 10, $iFilaHoja3, $lin_cara44bienv2tenis);
				PHPEXCEL_Escribir($objHoja3, 11, $iFilaHoja3, $lin_cara44bienv2paralimpico);
				PHPEXCEL_Escribir($objHoja3, 12, $iFilaHoja3, $lin_cara44bienv2otrodeporte);
				PHPEXCEL_Escribir($objHoja3, 13, $iFilaHoja3, $lin_cara44bienv2otrodeportedetalle);
				// Arte y Cultura
				// Usted practica regularmente alguna de las siguientes actividades artisticas o culturales
				PHPEXCEL_Escribir($objHoja3, 14, $iFilaHoja3, $lin_cara44bienv2activdanza);
				PHPEXCEL_Escribir($objHoja3, 15, $iFilaHoja3, $lin_cara44bienv2activmusica);
				PHPEXCEL_Escribir($objHoja3, 16, $iFilaHoja3, $lin_cara44bienv2activteatro);
				PHPEXCEL_Escribir($objHoja3, 17, $iFilaHoja3, $lin_cara44bienv2activartes);
				PHPEXCEL_Escribir($objHoja3, 18, $iFilaHoja3, $lin_cara44bienv2activliteratura);
				PHPEXCEL_Escribir($objHoja3, 19, $iFilaHoja3, $lin_cara44bienv2activculturalotra);
				PHPEXCEL_Escribir($objHoja3, 20, $iFilaHoja3, $lin_cara44bienv2activculturalotradetalle);
				// A que clase de eventos artisticos y culturales le gustaria asistir
				PHPEXCEL_Escribir($objHoja3, 21, $iFilaHoja3, $lin_cara44bienv2evenfestfolc);
				PHPEXCEL_Escribir($objHoja3, 22, $iFilaHoja3, $lin_cara44bienv2evenexpoarte);
				PHPEXCEL_Escribir($objHoja3, 23, $iFilaHoja3, $lin_cara44bienv2evenhistarte);
				PHPEXCEL_Escribir($objHoja3, 24, $iFilaHoja3, $lin_cara44bienv2evengalfoto);
				PHPEXCEL_Escribir($objHoja3, 25, $iFilaHoja3, $lin_cara44bienv2evenliteratura);
				PHPEXCEL_Escribir($objHoja3, 26, $iFilaHoja3, $lin_cara44bienv2eventeatro);
				PHPEXCEL_Escribir($objHoja3, 27, $iFilaHoja3, $lin_cara44bienv2evencine);
				PHPEXCEL_Escribir($objHoja3, 28, $iFilaHoja3, $lin_cara44bienv2evenculturalotro);
				PHPEXCEL_Escribir($objHoja3, 29, $iFilaHoja3, $lin_cara44bienv2evenculturalotrodetalle);
				// Emprendimiento
				PHPEXCEL_Escribir($objHoja3, 30, $iFilaHoja3, $lin_cara44bienv2emprendimiento);
				PHPEXCEL_Escribir($objHoja3, 31, $iFilaHoja3, $lin_cara44bienv2empresa);
				// Cual es el estado en que se encuentra su emprendimiento
				PHPEXCEL_Escribir($objHoja3, 32, $iFilaHoja3, $lin_cara44bienv2emprenrecursos);
				PHPEXCEL_Escribir($objHoja3, 33, $iFilaHoja3, $lin_cara44bienv2emprenconocim);
				PHPEXCEL_Escribir($objHoja3, 34, $iFilaHoja3, $lin_cara44bienv2emprenplan);
				PHPEXCEL_Escribir($objHoja3, 35, $iFilaHoja3, $lin_cara44bienv2emprenejecutar);
				PHPEXCEL_Escribir($objHoja3, 36, $iFilaHoja3, $lin_cara44bienv2emprenfortconocim);
				PHPEXCEL_Escribir($objHoja3, 37, $iFilaHoja3, $lin_cara44bienv2emprenidentproblema);
				PHPEXCEL_Escribir($objHoja3, 38, $iFilaHoja3, $lin_cara44bienv2emprenotro);
				PHPEXCEL_Escribir($objHoja3, 39, $iFilaHoja3, $lin_cara44bienv2emprenotrodetalle);
				// En que temas le gustaria recibir informacion con respecto al emprendimiento
				PHPEXCEL_Escribir($objHoja3, 40, $iFilaHoja3, $lin_cara44bienv2emprenmarketing);
				PHPEXCEL_Escribir($objHoja3, 41, $iFilaHoja3, $lin_cara44bienv2emprenplannegocios);
				PHPEXCEL_Escribir($objHoja3, 42, $iFilaHoja3, $lin_cara44bienv2emprenideas);
				PHPEXCEL_Escribir($objHoja3, 43, $iFilaHoja3, $lin_cara44bienv2emprencreacion);
				// Estilo de vida saludable
				// Causas mas frecuentes del estres
				PHPEXCEL_Escribir($objHoja3, 44, $iFilaHoja3, $lin_cara44bienv2saludfacteconom);
				PHPEXCEL_Escribir($objHoja3, 45, $iFilaHoja3, $lin_cara44bienv2saludpreocupacion);
				PHPEXCEL_Escribir($objHoja3, 46, $iFilaHoja3, $lin_cara44bienv2saludconsumosust);
				PHPEXCEL_Escribir($objHoja3, 47, $iFilaHoja3, $lin_cara44bienv2saludinsomnio);
				PHPEXCEL_Escribir($objHoja3, 48, $iFilaHoja3, $lin_cara44bienv2saludclimalab);
				// Estrategias para conocer el autocuidado
				PHPEXCEL_Escribir($objHoja3, 49, $iFilaHoja3, $lin_cara44bienv2saludalimenta);
				PHPEXCEL_Escribir($objHoja3, 50, $iFilaHoja3, $lin_cara44bienv2saludemocion);
				PHPEXCEL_Escribir($objHoja3, 51, $iFilaHoja3, $lin_cara44bienv2saludestado);
				PHPEXCEL_Escribir($objHoja3, 52, $iFilaHoja3, $lin_cara44bienv2saludmedita);
				// Crecimiento Personal
				// Temas de interes para su crecimiento personal
				PHPEXCEL_Escribir($objHoja3, 53, $iFilaHoja3, $lin_cara44bienv2crecimedusexual);
				PHPEXCEL_Escribir($objHoja3, 54, $iFilaHoja3, $lin_cara44bienv2crecimcultciudad);
				PHPEXCEL_Escribir($objHoja3, 55, $iFilaHoja3, $lin_cara44bienv2crecimrelpareja);
				PHPEXCEL_Escribir($objHoja3, 56, $iFilaHoja3, $lin_cara44bienv2crecimrelinterp);
				PHPEXCEL_Escribir($objHoja3, 57, $iFilaHoja3, $lin_cara44bienv2crecimdinamicafam);
				PHPEXCEL_Escribir($objHoja3, 58, $iFilaHoja3, $lin_cara44bienv2crecimautoestima);
				PHPEXCEL_Escribir($objHoja3, 59, $iFilaHoja3, $lin_cara44bienv2creciminclusion);
				PHPEXCEL_Escribir($objHoja3, 60, $iFilaHoja3, $lin_cara44bienv2creciminteliemoc);
				// Le gustaria hacer parte de algun grupo de bienestar
				PHPEXCEL_Escribir($objHoja3, 61, $iFilaHoja3, $lin_cara44bienv2crecimcultural);
				PHPEXCEL_Escribir($objHoja3, 62, $iFilaHoja3, $lin_cara44bienv2crecimartistico);
				PHPEXCEL_Escribir($objHoja3, 63, $iFilaHoja3, $lin_cara44bienv2crecimdeporte);
				PHPEXCEL_Escribir($objHoja3, 64, $iFilaHoja3, $lin_cara44bienv2crecimambiente);
				PHPEXCEL_Escribir($objHoja3, 65, $iFilaHoja3, $lin_cara44bienv2crecimhabsocio);
				// Medio ambiente
				// Realiza alguna de estas acciones frente al cuidado del medio ambiente
				PHPEXCEL_Escribir($objHoja3, 66, $iFilaHoja3, $lin_cara44bienv2ambienbasura);
				PHPEXCEL_Escribir($objHoja3, 67, $iFilaHoja3, $lin_cara44bienv2ambienreutiliza);
				PHPEXCEL_Escribir($objHoja3, 68, $iFilaHoja3, $lin_cara44bienv2ambienluces);
				PHPEXCEL_Escribir($objHoja3, 69, $iFilaHoja3, $lin_cara44bienv2ambienfrutaverd);
				PHPEXCEL_Escribir($objHoja3, 70, $iFilaHoja3, $lin_cara44bienv2ambienenchufa);
				PHPEXCEL_Escribir($objHoja3, 71, $iFilaHoja3, $lin_cara44bienv2ambiengrifo);
				PHPEXCEL_Escribir($objHoja3, 72, $iFilaHoja3, $lin_cara44bienv2ambienbicicleta);
				PHPEXCEL_Escribir($objHoja3, 73, $iFilaHoja3, $lin_cara44bienv2ambientranspub);
				PHPEXCEL_Escribir($objHoja3, 74, $iFilaHoja3, $lin_cara44bienv2ambienducha);
				// En su tiempo libre ha participado en alguna actividad ambiental
				PHPEXCEL_Escribir($objHoja3, 75, $iFilaHoja3, $lin_cara44bienv2ambiencaminata);
				PHPEXCEL_Escribir($objHoja3, 76, $iFilaHoja3, $lin_cara44bienv2ambiensiembra);
				PHPEXCEL_Escribir($objHoja3, 77, $iFilaHoja3, $lin_cara44bienv2ambienconferencia);
				PHPEXCEL_Escribir($objHoja3, 78, $iFilaHoja3, $lin_cara44bienv2ambienrecicla);
				PHPEXCEL_Escribir($objHoja3, 79, $iFilaHoja3, $lin_cara44bienv2ambienotraactiv);
				PHPEXCEL_Escribir($objHoja3, 80, $iFilaHoja3, $lin_cara44bienv2ambienotraactivdetalle);
				// Cual tema desde el enfoque ambiental le gustaria conocer o profundizar
				PHPEXCEL_Escribir($objHoja3, 81, $iFilaHoja3, $lin_cara44bienv2ambienreforest);
				PHPEXCEL_Escribir($objHoja3, 82, $iFilaHoja3, $lin_cara44bienv2ambienmovilidad);
				PHPEXCEL_Escribir($objHoja3, 83, $iFilaHoja3, $lin_cara44bienv2ambienclimatico);
				PHPEXCEL_Escribir($objHoja3, 84, $iFilaHoja3, $lin_cara44bienv2ambienecofemin);
				PHPEXCEL_Escribir($objHoja3, 85, $iFilaHoja3, $lin_cara44bienv2ambienbiodiver);
				PHPEXCEL_Escribir($objHoja3, 86, $iFilaHoja3, $lin_cara44bienv2ambienecologia);
				PHPEXCEL_Escribir($objHoja3, 87, $iFilaHoja3, $lin_cara44bienv2ambieneconomia);
				PHPEXCEL_Escribir($objHoja3, 88, $iFilaHoja3, $lin_cara44bienv2ambienrecnatura);
				PHPEXCEL_Escribir($objHoja3, 89, $iFilaHoja3, $lin_cara44bienv2ambienreciclaje);
				PHPEXCEL_Escribir($objHoja3, 90, $iFilaHoja3, $lin_cara44bienv2ambienmascota);
				PHPEXCEL_Escribir($objHoja3, 91, $iFilaHoja3, $lin_cara44bienv2ambiencartohum);
				PHPEXCEL_Escribir($objHoja3, 92, $iFilaHoja3, $lin_cara44bienv2ambienespiritu);
				PHPEXCEL_Escribir($objHoja3, 93, $iFilaHoja3, $lin_cara44bienv2ambiencarga);
				PHPEXCEL_Escribir($objHoja3, 94, $iFilaHoja3, $lin_cara44bienv2ambienotroenfoq);
				PHPEXCEL_Escribir($objHoja3, 95, $iFilaHoja3, $lin_cara44bienv2ambienotroenfoqdetalle);*/
				$iFilaHoja3++;
			}
		}

		// HOJA 4 - Bienestar v.3
		// Emprendimiento Solidario
		$lin_cara44bienv3emprenetapa = '';
		$lin_cara44bienv3emprennecesita = '';
		$lin_cara44bienv3emprenanioini = '';
		$lin_cara44bienv3emprensector = '';
		$lin_cara44bienv3emprensectorotro = '';
		$lin_cara44bienv3emprentemas = '';
		// Medio Ambiente
		// En que tematica le gustaria participar?
		$lin_cara44bienv3ambienclima = '';
		$lin_cara44bienv3ambienjusticia = '';
		$lin_cara44bienv3ambienagroeco = '';
		$lin_cara44bienv3ambieneconomia = '';
		$lin_cara44bienv3ambieneducacion = '';
		$lin_cara44bienv3ambienbiodiverso = '';
		$lin_cara44bienv3ambienecoturismo = '';
		$lin_cara44bienv3ambienotro = '';
		$lin_cara44bienv3ambienotrodetalle = '';
		$lin_cara44bienv3ambienexper = '';
		$lin_cara44bienv3ambienaprende = '';
		$lin_cara44bienv3ambienestudiante = '';
		$lin_cara44bienv3ambienactividad = '';
		// Promocion de la Salud y Prevencion de la Enfermedad
		$lin_cara44bienv3pyphabitoalim = '';
		$lin_cara44bienv3pypsustanciapsico = '';
		$lin_cara44bienv3pypsaludvisual = '';
		$lin_cara44bienv3pypsaludbucal = '';
		$lin_cara44bienv3pypsaludsexual = '';
		// Deporte y Recreacion
		$lin_cara44bienv3deportenivel = '';
		$lin_cara44bienv3deportefrec = '';
		$lin_cara44bienv3deportecual = '';
		$lin_cara44bienv3deportecualotro = '';
		$lin_cara44bienv3deporterecrea = '';
		$lin_cara44bienv3deporteunad = '';
		// Crecimiento Personal
		// Cuales temas son de su interes para fortalecer su crecimiento personal?
		$lin_cara44bienv3creciminclusion = '';
		$lin_cara44bienv3crecimfamilia = '';
		$lin_cara44bienv3crecimhabilidad = '';
		$lin_cara44bienv3crecimempleable = '';
		$lin_cara44bienv3crecimhabilvida = '';
		$lin_cara44bienv3crecimespiritual = '';
		$lin_cara44bienv3crecimpractica = '';
		// Que habilidades considera que le ayudarian a desarrollar su maximo potencial?
		$lin_cara44bienv3crecimliderazgo = '';
		$lin_cara44bienv3crecimtrabequipo = '';
		$lin_cara44bienv3crecimasertiva = '';
		$lin_cara44bienv3crecimgesttiempo = '';
		$lin_cara44bienv3crecimconflictos = '';
		$lin_cara44bienv3crecimadapcambio = '';
		$lin_cara44bienv3crecimempatia = '';
		$lin_cara44bienv3crecimgestionser = '';
		$lin_cara44bienv3crecimtomadecide = '';
		$lin_cara44bienv3crecimpenscreativo = '';
		$lin_cara44bienv3crecimpenscritico = '';
		$lin_cara44bienv3crecimhabilotro = '';
		$lin_cara44bienv3crecimhabilotrodetalle = '';
		// Que lo motiva a seguir buscando su crecimiento personal?
		$lin_cara44bienv3crecimalcancemeta = '';
		$lin_cara44bienv3crecimsatifpersonal = '';
		$lin_cara44bienv3crecimaccesolaboral = '';
		$lin_cara44bienv3crecimotramotiv = '';
		$lin_cara44bienv3crecimotramotivdetalle = '';
		$lin_cara44bienv3crecimapoyo = '';
		$lin_cara44bienv3crecimlaboral = '';
		// Salud Mental
		$lin_cara44bienv3mentalcuidado = '';
		$lin_cara44bienv3mentalestrategia = '';
		// Seleccione temas de interes para el cuidado de su Salud Mental
		$lin_cara44bienv3mentalestres = '';
		$lin_cara44bienv3mentalansiedad = '';
		$lin_cara44bienv3mentaldepresion = '';
		$lin_cara44bienv3mentalautoconoc = '';
		$lin_cara44bienv3mentalmindfulness = '';
		$lin_cara44bienv3mentalautoestima = '';
		$lin_cara44bienv3mentalcrisis = '';
		$lin_cara44bienv3mentalburnout = '';
		$lin_cara44bienv3mentalsexualidad = '';
		$lin_cara44bienv3mentalusoredes = '';
		$lin_cara44bienv3mentalinclusion = '';
		$lin_cara44bienv3mentalactividad = '';
		$lin_cara44bienv3mentalacompana = '';
		$lin_cara44bienv3mentaldiagnostico = '';
		$lin_cara44bienv3mentaldiagcual = '';
		$lin_cara44bienv3mentaldiagotro = '';
		// Arte y Cultura
		$lin_cara44bienv3arteintegrar = '';
		$lin_cara44bienv3arteformacion = '';
		$lin_cara44bienv3arteunad = '';
		$lin_cara44bienv3arteinformacion = '';
		if ($bHayFilascara44) {
			if ($aBloque[5] && $bVerBienV3) {
				if (isset($acara44bienv3emprenetapa[$fila['cara44bienv3emprenetapa']]) != 0) {
					$lin_cara44bienv3emprenetapa = html_entity_decode($acara44bienv3emprenetapa[$fila['cara44bienv3emprenetapa']]);
				}
				if (isset($acara44bienv3emprennecesita[$fila['cara44bienv3emprennecesita']]) != 0) {
					$lin_cara44bienv3emprennecesita = html_entity_decode($acara44bienv3emprennecesita[$fila['cara44bienv3emprennecesita']]);
				}
				if (isset($acara44bienv3emprenanioini[$fila['cara44bienv3emprenanioini']]) != 0) {
					$lin_cara44bienv3emprenanioini = html_entity_decode($acara44bienv3emprenanioini[$fila['cara44bienv3emprenanioini']]);
				}
				if (isset($acara44bienv3emprensector[$fila['cara44bienv3emprensector']]) != 0) {
					$lin_cara44bienv3emprensector = html_entity_decode($acara44bienv3emprensector[$fila['cara44bienv3emprensector']]);
				}
				$lin_cara44bienv3emprensectorotro = $fila['cara44bienv3emprensectorotro'];
				if (isset($acara44bienv3emprentemas[$fila['cara44bienv3emprentemas']]) != 0) {
					$lin_cara44bienv3emprentemas = html_entity_decode($acara44bienv3emprentemas[$fila['cara44bienv3emprentemas']]);
				}
				$lin_cara44bienv3ambienclima = 'Si';
				if ($fila['cara44bienv3ambienclima'] == 0) {
					$lin_cara44bienv3ambienclima = 'No';
				}
				$lin_cara44bienv3ambienjusticia = 'Si';
				if ($fila['cara44bienv3ambienjusticia'] == 0) {
					$lin_cara44bienv3ambienjusticia = 'No';
				}
				$lin_cara44bienv3ambienagroeco = 'Si';
				if ($fila['cara44bienv3ambienagroeco'] == 0) {
					$lin_cara44bienv3ambienagroeco = 'No';
				}
				$lin_cara44bienv3ambieneconomia = 'Si';
				if ($fila['cara44bienv3ambieneconomia'] == 0) {
					$lin_cara44bienv3ambieneconomia = 'No';
				}
				$lin_cara44bienv3ambieneducacion = 'Si';
				if ($fila['cara44bienv3ambieneducacion'] == 0) {
					$lin_cara44bienv3ambieneducacion = 'No';
				}
				$lin_cara44bienv3ambienbiodiverso = 'Si';
				if ($fila['cara44bienv3ambienbiodiverso'] == 0) {
					$lin_cara44bienv3ambienbiodiverso = 'No';
				}
				$lin_cara44bienv3ambienecoturismo = 'Si';
				if ($fila['cara44bienv3ambienecoturismo'] == 0) {
					$lin_cara44bienv3ambienecoturismo = 'No';
				}
				$lin_cara44bienv3ambienotro = 'Si';
				if ($fila['cara44bienv3ambienotro'] == 0) {
					$lin_cara44bienv3ambienotro = 'No';
				}
				$lin_cara44bienv3ambienotrodetalle = $fila['cara44bienv3ambienotrodetalle'];
				if (isset($acara44bienv3ambienexper[$fila['cara44bienv3ambienexper']]) != 0) {
					$lin_cara44bienv3ambienexper = html_entity_decode($acara44bienv3ambienexper[$fila['cara44bienv3ambienexper']]);
				}
				if (isset($acara44bienv3ambienaprende[$fila['cara44bienv3ambienaprende']]) != 0) {
					$lin_cara44bienv3ambienaprende = html_entity_decode($acara44bienv3ambienaprende[$fila['cara44bienv3ambienaprende']]);
				}
				if (isset($acara44bienv3ambienestudiante[$fila['cara44bienv3ambienestudiante']]) != 0) {
					$lin_cara44bienv3ambienestudiante = html_entity_decode($acara44bienv3ambienestudiante[$fila['cara44bienv3ambienestudiante']]);
				}
				if (isset($acara44bienv3ambienactividad[$fila['cara44bienv3ambienactividad']]) != 0) {
					$lin_cara44bienv3ambienactividad = html_entity_decode($acara44bienv3ambienactividad[$fila['cara44bienv3ambienactividad']]);
				}
				if (isset($acara44bienv3pyphabitoalim[$fila['cara44bienv3pyphabitoalim']]) != 0) {
					$lin_cara44bienv3pyphabitoalim = html_entity_decode($acara44bienv3pyphabitoalim[$fila['cara44bienv3pyphabitoalim']]);
				}
				if (isset($acara44bienv3pypsustanciapsico[$fila['cara44bienv3pypsustanciapsico']]) != 0) {
					$lin_cara44bienv3pypsustanciapsico = html_entity_decode($acara44bienv3pypsustanciapsico[$fila['cara44bienv3pypsustanciapsico']]);
				}
				if (isset($acara44bienv3pypsaludvisual[$fila['cara44bienv3pypsaludvisual']]) != 0) {
					$lin_cara44bienv3pypsaludvisual = html_entity_decode($acara44bienv3pypsaludvisual[$fila['cara44bienv3pypsaludvisual']]);
				}
				if (isset($acara44bienv3pypsaludbucal[$fila['cara44bienv3pypsaludbucal']]) != 0) {
					$lin_cara44bienv3pypsaludbucal = html_entity_decode($acara44bienv3pypsaludbucal[$fila['cara44bienv3pypsaludbucal']]);
				}
				if (isset($acara44bienv3pypsaludsexual[$fila['cara44bienv3pypsaludsexual']]) != 0) {
					$lin_cara44bienv3pypsaludsexual = html_entity_decode($acara44bienv3pypsaludsexual[$fila['cara44bienv3pypsaludsexual']]);
				}
				if (isset($acara44bienv3deportenivel[$fila['cara44bienv3deportenivel']]) != 0) {
					$lin_cara44bienv3deportenivel = html_entity_decode($acara44bienv3deportenivel[$fila['cara44bienv3deportenivel']]);
				}
				if (isset($acara44bienv3deportefrec[$fila['cara44bienv3deportefrec']]) != 0) {
					$lin_cara44bienv3deportefrec = html_entity_decode($acara44bienv3deportefrec[$fila['cara44bienv3deportefrec']]);
				}
				if (isset($acara44bienv3deportecual[$fila['cara44bienv3deportecual']]) != 0) {
					$lin_cara44bienv3deportecual = html_entity_decode($acara44bienv3deportecual[$fila['cara44bienv3deportecual']]);
				}
				$lin_cara44bienv3deportecualotro = $fila['cara44bienv3deportecualotro'];
				if (isset($acara44bienv3deporterecrea[$fila['cara44bienv3deporterecrea']]) != 0) {
					$lin_cara44bienv3deporterecrea = html_entity_decode($acara44bienv3deporterecrea[$fila['cara44bienv3deporterecrea']]);
				}
				$lin_cara44bienv3deporteunad = 'Si';
				if ($fila['cara44bienv3deporteunad'] == 0) {
					$lin_cara44bienv3deporteunad = 'No';
				}
				$lin_cara44bienv3creciminclusion = 'Si';
				if ($fila['cara44bienv3creciminclusion'] == 0) {
					$lin_cara44bienv3creciminclusion = 'No';
				}
				$lin_cara44bienv3crecimfamilia = 'Si';
				if ($fila['cara44bienv3crecimfamilia'] == 0) {
					$lin_cara44bienv3crecimfamilia = 'No';
				}
				$lin_cara44bienv3crecimhabilidad = 'Si';
				if ($fila['cara44bienv3crecimhabilidad'] == 0) {
					$lin_cara44bienv3crecimhabilidad = 'No';
				}
				$lin_cara44bienv3crecimempleable = 'Si';
				if ($fila['cara44bienv3crecimempleable'] == 0) {
					$lin_cara44bienv3crecimempleable = 'No';
				}
				$lin_cara44bienv3crecimhabilvida = 'Si';
				if ($fila['cara44bienv3crecimhabilvida'] == 0) {
					$lin_cara44bienv3crecimhabilvida = 'No';
				}
				$lin_cara44bienv3crecimespiritual = 'Si';
				if ($fila['cara44bienv3crecimespiritual'] == 0) {
					$lin_cara44bienv3crecimespiritual = 'No';
				}
				$lin_cara44bienv3crecimpractica = 'Si';
				if ($fila['cara44bienv3crecimpractica'] == 0) {
					$lin_cara44bienv3crecimpractica = 'No';
				}
				$lin_cara44bienv3crecimliderazgo = 'Si';
				if ($fila['cara44bienv3crecimliderazgo'] == 0) {
					$lin_cara44bienv3crecimliderazgo = 'No';
				}
				$lin_cara44bienv3crecimtrabequipo = 'Si';
				if ($fila['cara44bienv3crecimtrabequipo'] == 0) {
					$lin_cara44bienv3crecimtrabequipo = 'No';
				}
				$lin_cara44bienv3crecimasertiva = 'Si';
				if ($fila['cara44bienv3crecimasertiva'] == 0) {
					$lin_cara44bienv3crecimasertiva = 'No';
				}
				$lin_cara44bienv3crecimgesttiempo = 'Si';
				if ($fila['cara44bienv3crecimgesttiempo'] == 0) {
					$lin_cara44bienv3crecimgesttiempo = 'No';
				}
				$lin_cara44bienv3crecimconflictos = 'Si';
				if ($fila['cara44bienv3crecimconflictos'] == 0) {
					$lin_cara44bienv3crecimconflictos = 'No';
				}
				$lin_cara44bienv3crecimadapcambio = 'Si';
				if ($fila['cara44bienv3crecimadapcambio'] == 0) {
					$lin_cara44bienv3crecimadapcambio = 'No';
				}
				$lin_cara44bienv3crecimempatia = 'Si';
				if ($fila['cara44bienv3crecimempatia'] == 0) {
					$lin_cara44bienv3crecimempatia = 'No';
				}
				$lin_cara44bienv3crecimgestionser = 'Si';
				if ($fila['cara44bienv3crecimgestionser'] == 0) {
					$lin_cara44bienv3crecimgestionser = 'No';
				}
				$lin_cara44bienv3crecimtomadecide = 'Si';
				if ($fila['cara44bienv3crecimtomadecide'] == 0) {
					$lin_cara44bienv3crecimtomadecide = 'No';
				}
				$lin_cara44bienv3crecimpenscreativo = 'Si';
				if ($fila['cara44bienv3crecimpenscreativo'] == 0) {
					$lin_cara44bienv3crecimpenscreativo = 'No';
				}
				$lin_cara44bienv3crecimpenscritico = 'Si';
				if ($fila['cara44bienv3crecimpenscritico'] == 0) {
					$lin_cara44bienv3crecimpenscritico = 'No';
				}
				$lin_cara44bienv3crecimhabilotro = 'Si';
				if ($fila['cara44bienv3crecimhabilotro'] == 0) {
					$lin_cara44bienv3crecimhabilotro = 'No';
				}
				$lin_cara44bienv3crecimhabilotrodetalle = $fila['cara44bienv3crecimhabilotrodetalle'];
				$lin_cara44bienv3crecimalcancemeta = 'Si';
				if ($fila['cara44bienv3crecimalcancemeta'] == 0) {
					$lin_cara44bienv3crecimalcancemeta = 'No';
				}
				$lin_cara44bienv3crecimsatifpersonal = 'Si';
				if ($fila['cara44bienv3crecimsatifpersonal'] == 0) {
					$lin_cara44bienv3crecimsatifpersonal = 'No';
				}
				$lin_cara44bienv3crecimaccesolaboral = 'Si';
				if ($fila['cara44bienv3crecimaccesolaboral'] == 0) {
					$lin_cara44bienv3crecimaccesolaboral = 'No';
				}
				$lin_cara44bienv3crecimotramotiv = 'Si';
				if ($fila['cara44bienv3crecimotramotiv'] == 0) {
					$lin_cara44bienv3crecimotramotiv = 'No';
				}
				$lin_cara44bienv3crecimotramotivdetalle = $fila['cara44bienv3crecimotramotivdetalle'];
				if (isset($acara44bienv3crecimapoyo[$fila['cara44bienv3crecimapoyo']]) != 0) {
					$lin_cara44bienv3crecimapoyo = html_entity_decode($acara44bienv3crecimapoyo[$fila['cara44bienv3crecimapoyo']]);
				}
				if (isset($acara44bienv3crecimlaboral[$fila['cara44bienv3crecimlaboral']]) != 0) {
					$lin_cara44bienv3crecimlaboral = html_entity_decode($acara44bienv3crecimlaboral[$fila['cara44bienv3crecimlaboral']]);
				}
				if (isset($acara44bienv3mentalcuidado[$fila['cara44bienv3mentalcuidado']]) != 0) {
					$lin_cara44bienv3mentalcuidado = html_entity_decode($acara44bienv3mentalcuidado[$fila['cara44bienv3mentalcuidado']]);
				}
				if (isset($acara44bienv3mentalestrategia[$fila['cara44bienv3mentalestrategia']]) != 0) {
					$lin_cara44bienv3mentalestrategia = html_entity_decode($acara44bienv3mentalestrategia[$fila['cara44bienv3mentalestrategia']]);
				}
				$lin_cara44bienv3mentalestres = 'Si';
				if ($fila['cara44bienv3mentalestres'] == 0) {
					$lin_cara44bienv3mentalestres = 'No';
				}
				$lin_cara44bienv3mentalansiedad = 'Si';
				if ($fila['cara44bienv3mentalansiedad'] == 0) {
					$lin_cara44bienv3mentalansiedad = 'No';
				}
				$lin_cara44bienv3mentaldepresion = 'Si';
				if ($fila['cara44bienv3mentaldepresion'] == 0) {
					$lin_cara44bienv3mentaldepresion = 'No';
				}
				$lin_cara44bienv3mentalautoconoc = 'Si';
				if ($fila['cara44bienv3mentalautoconoc'] == 0) {
					$lin_cara44bienv3mentalautoconoc = 'No';
				}
				$lin_cara44bienv3mentalmindfulness = 'Si';
				if ($fila['cara44bienv3mentalmindfulness'] == 0) {
					$lin_cara44bienv3mentalmindfulness = 'No';
				}
				$lin_cara44bienv3mentalautoestima = 'Si';
				if ($fila['cara44bienv3mentalautoestima'] == 0) {
					$lin_cara44bienv3mentalautoestima = 'No';
				}
				$lin_cara44bienv3mentalcrisis = 'Si';
				if ($fila['cara44bienv3mentalcrisis'] == 0) {
					$lin_cara44bienv3mentalcrisis = 'No';
				}
				$lin_cara44bienv3mentalburnout = 'Si';
				if ($fila['cara44bienv3mentalburnout'] == 0) {
					$lin_cara44bienv3mentalburnout = 'No';
				}
				$lin_cara44bienv3mentalsexualidad = 'Si';
				if ($fila['cara44bienv3mentalsexualidad'] == 0) {
					$lin_cara44bienv3mentalsexualidad = 'No';
				}
				$lin_cara44bienv3mentalusoredes = 'Si';
				if ($fila['cara44bienv3mentalusoredes'] == 0) {
					$lin_cara44bienv3mentalusoredes = 'No';
				}
				$lin_cara44bienv3mentalinclusion = 'Si';
				if ($fila['cara44bienv3mentalinclusion'] == 0) {
					$lin_cara44bienv3mentalinclusion = 'No';
				}
				if (isset($acara44bienv3mentalactividad[$fila['cara44bienv3mentalactividad']]) != 0) {
					$lin_cara44bienv3mentalactividad = html_entity_decode($acara44bienv3mentalactividad[$fila['cara44bienv3mentalactividad']]);
				}
				$lin_cara44bienv3mentalacompana = 'Si';
				if ($fila['cara44bienv3mentalacompana'] == 0) {
					$lin_cara44bienv3mentalacompana = 'No';
				}
				$lin_cara44bienv3mentaldiagnostico = 'Si';
				if ($fila['cara44bienv3mentaldiagnostico'] == 0) {
					$lin_cara44bienv3mentaldiagnostico = 'No';
				}
				if (isset($acara44bienv3mentaldiagcual[$fila['cara44bienv3mentaldiagcual']]) != 0) {
					$lin_cara44bienv3mentaldiagcual = html_entity_decode($acara44bienv3mentaldiagcual[$fila['cara44bienv3mentaldiagcual']]);
				}
				$lin_cara44bienv3mentaldiagotro = $fila['cara44bienv3mentaldiagotro'];
				if (isset($acara44bienv3arteintegrar[$fila['cara44bienv3arteintegrar']]) != 0) {
					$lin_cara44bienv3arteintegrar = html_entity_decode($acara44bienv3arteintegrar[$fila['cara44bienv3arteintegrar']]);
				}
				if (isset($acara44bienv3arteformacion[$fila['cara44bienv3arteformacion']]) != 0) {
					$lin_cara44bienv3arteformacion = html_entity_decode($acara44bienv3arteformacion[$fila['cara44bienv3arteformacion']]);
				}
				$lin_cara44bienv3arteunad = 'Si';
				if ($fila['cara44bienv3arteunad'] == 0) {
					$lin_cara44bienv3arteunad = 'No';
				}
				if (isset($acara44bienv3arteinformacion[$fila['cara44bienv3arteinformacion']]) != 0) {
					$lin_cara44bienv3arteinformacion = html_entity_decode($acara44bienv3arteinformacion[$fila['cara44bienv3arteinformacion']]);
				}

				$objExcel->setActiveSheetIndex(3);
				$objHoja4 = $objExcel->getActiveSheet();
				PHPEXCEL_Escribir($objHoja4, 0, $iFilaHoja4, $lin_cara01idtercero_td);
				PHPEXCEL_Escribir($objHoja4, 1, $iFilaHoja4, $lin_cara01idtercero_doc);
				PHPEXCEL_Escribir($objHoja4, 2, $iFilaHoja4, $lin_cara01idtercero_nom);
				// Emprendimiento Solidario
				PHPEXCEL_Escribir($objHoja4, 3, $iFilaHoja4, $lin_cara44bienv3emprenetapa);
				PHPEXCEL_Escribir($objHoja4, 4, $iFilaHoja4, $lin_cara44bienv3emprennecesita);
				PHPEXCEL_Escribir($objHoja4, 5, $iFilaHoja4, $lin_cara44bienv3emprenanioini);
				PHPEXCEL_Escribir($objHoja4, 6, $iFilaHoja4, $lin_cara44bienv3emprensector);
				PHPEXCEL_Escribir($objHoja4, 7, $iFilaHoja4, $lin_cara44bienv3emprensectorotro);
				PHPEXCEL_Escribir($objHoja4, 8, $iFilaHoja4, $lin_cara44bienv3emprentemas);
				// Medio Ambiente
				// En que tematica le gustaria participar?
				PHPEXCEL_Escribir($objHoja4, 9, $iFilaHoja4, $lin_cara44bienv3ambienclima);
				PHPEXCEL_Escribir($objHoja4, 10, $iFilaHoja4, $lin_cara44bienv3ambienjusticia);
				PHPEXCEL_Escribir($objHoja4, 11, $iFilaHoja4, $lin_cara44bienv3ambienagroeco);
				PHPEXCEL_Escribir($objHoja4, 12, $iFilaHoja4, $lin_cara44bienv3ambieneconomia);
				PHPEXCEL_Escribir($objHoja4, 13, $iFilaHoja4, $lin_cara44bienv3ambieneducacion);
				PHPEXCEL_Escribir($objHoja4, 14, $iFilaHoja4, $lin_cara44bienv3ambienbiodiverso);
				PHPEXCEL_Escribir($objHoja4, 15, $iFilaHoja4, $lin_cara44bienv3ambienecoturismo);
				PHPEXCEL_Escribir($objHoja4, 16, $iFilaHoja4, $lin_cara44bienv3ambienotro);
				PHPEXCEL_Escribir($objHoja4, 17, $iFilaHoja4, $lin_cara44bienv3ambienotrodetalle);
				PHPEXCEL_Escribir($objHoja4, 18, $iFilaHoja4, $lin_cara44bienv3ambienexper);
				PHPEXCEL_Escribir($objHoja4, 19, $iFilaHoja4, $lin_cara44bienv3ambienaprende);
				PHPEXCEL_Escribir($objHoja4, 20, $iFilaHoja4, $lin_cara44bienv3ambienestudiante);
				PHPEXCEL_Escribir($objHoja4, 21, $iFilaHoja4, $lin_cara44bienv3ambienactividad);
				// Promocion de la Salud y Prevencion de la Enfermedad
				PHPEXCEL_Escribir($objHoja4, 22, $iFilaHoja4, $lin_cara44bienv3pyphabitoalim);
				PHPEXCEL_Escribir($objHoja4, 23, $iFilaHoja4, $lin_cara44bienv3pypsustanciapsico);
				PHPEXCEL_Escribir($objHoja4, 24, $iFilaHoja4, $lin_cara44bienv3pypsaludvisual);
				PHPEXCEL_Escribir($objHoja4, 25, $iFilaHoja4, $lin_cara44bienv3pypsaludbucal);
				PHPEXCEL_Escribir($objHoja4, 26, $iFilaHoja4, $lin_cara44bienv3pypsaludsexual);
				// Deporte y Recreacion
				PHPEXCEL_Escribir($objHoja4, 27, $iFilaHoja4, $lin_cara44bienv3deportenivel);
				PHPEXCEL_Escribir($objHoja4, 28, $iFilaHoja4, $lin_cara44bienv3deportefrec);
				PHPEXCEL_Escribir($objHoja4, 29, $iFilaHoja4, $lin_cara44bienv3deportecual);
				PHPEXCEL_Escribir($objHoja4, 30, $iFilaHoja4, $lin_cara44bienv3deportecualotro);
				PHPEXCEL_Escribir($objHoja4, 31, $iFilaHoja4, $lin_cara44bienv3deporterecrea);
				PHPEXCEL_Escribir($objHoja4, 32, $iFilaHoja4, $lin_cara44bienv3deporteunad);
				// Crecimiento Personal
				// Cuales temas son de su interes para fortalecer su crecimiento personal?
				PHPEXCEL_Escribir($objHoja4, 33, $iFilaHoja4, $lin_cara44bienv3creciminclusion);
				PHPEXCEL_Escribir($objHoja4, 34, $iFilaHoja4, $lin_cara44bienv3crecimfamilia);
				PHPEXCEL_Escribir($objHoja4, 35, $iFilaHoja4, $lin_cara44bienv3crecimhabilidad);
				PHPEXCEL_Escribir($objHoja4, 36, $iFilaHoja4, $lin_cara44bienv3crecimempleable);
				PHPEXCEL_Escribir($objHoja4, 37, $iFilaHoja4, $lin_cara44bienv3crecimhabilvida);
				PHPEXCEL_Escribir($objHoja4, 38, $iFilaHoja4, $lin_cara44bienv3crecimespiritual);
				PHPEXCEL_Escribir($objHoja4, 39, $iFilaHoja4, $lin_cara44bienv3crecimpractica);
				// Que habilidades considera que le ayudarian a desarrollar su maximo potencial?
				PHPEXCEL_Escribir($objHoja4, 40, $iFilaHoja4, $lin_cara44bienv3crecimliderazgo);
				PHPEXCEL_Escribir($objHoja4, 41, $iFilaHoja4, $lin_cara44bienv3crecimtrabequipo);
				PHPEXCEL_Escribir($objHoja4, 42, $iFilaHoja4, $lin_cara44bienv3crecimasertiva);
				PHPEXCEL_Escribir($objHoja4, 43, $iFilaHoja4, $lin_cara44bienv3crecimgesttiempo);
				PHPEXCEL_Escribir($objHoja4, 44, $iFilaHoja4, $lin_cara44bienv3crecimconflictos);
				PHPEXCEL_Escribir($objHoja4, 45, $iFilaHoja4, $lin_cara44bienv3crecimadapcambio);
				PHPEXCEL_Escribir($objHoja4, 46, $iFilaHoja4, $lin_cara44bienv3crecimempatia);
				PHPEXCEL_Escribir($objHoja4, 47, $iFilaHoja4, $lin_cara44bienv3crecimgestionser);
				PHPEXCEL_Escribir($objHoja4, 48, $iFilaHoja4, $lin_cara44bienv3crecimtomadecide);
				PHPEXCEL_Escribir($objHoja4, 49, $iFilaHoja4, $lin_cara44bienv3crecimpenscreativo);
				PHPEXCEL_Escribir($objHoja4, 50, $iFilaHoja4, $lin_cara44bienv3crecimpenscritico);
				PHPEXCEL_Escribir($objHoja4, 51, $iFilaHoja4, $lin_cara44bienv3crecimhabilotro);
				PHPEXCEL_Escribir($objHoja4, 52, $iFilaHoja4, $lin_cara44bienv3crecimhabilotrodetalle);
				// Que lo motiva a seguir buscando su crecimiento personal?
				PHPEXCEL_Escribir($objHoja4, 53, $iFilaHoja4, $lin_cara44bienv3crecimalcancemeta);
				PHPEXCEL_Escribir($objHoja4, 54, $iFilaHoja4, $lin_cara44bienv3crecimsatifpersonal);
				PHPEXCEL_Escribir($objHoja4, 55, $iFilaHoja4, $lin_cara44bienv3crecimaccesolaboral);
				PHPEXCEL_Escribir($objHoja4, 56, $iFilaHoja4, $lin_cara44bienv3crecimotramotiv);
				PHPEXCEL_Escribir($objHoja4, 57, $iFilaHoja4, $lin_cara44bienv3crecimotramotivdetalle);
				PHPEXCEL_Escribir($objHoja4, 58, $iFilaHoja4, $lin_cara44bienv3crecimapoyo);
				PHPEXCEL_Escribir($objHoja4, 59, $iFilaHoja4, $lin_cara44bienv3crecimlaboral);
				// Salud Mental
				PHPEXCEL_Escribir($objHoja4, 60, $iFilaHoja4, $lin_cara44bienv3mentalcuidado);
				PHPEXCEL_Escribir($objHoja4, 61, $iFilaHoja4, $lin_cara44bienv3mentalestrategia);
				// Seleccione temas de interes para el cuidado de su Salud Mental
				PHPEXCEL_Escribir($objHoja4, 62, $iFilaHoja4, $lin_cara44bienv3mentalestres);
				PHPEXCEL_Escribir($objHoja4, 63, $iFilaHoja4, $lin_cara44bienv3mentalansiedad);
				PHPEXCEL_Escribir($objHoja4, 64, $iFilaHoja4, $lin_cara44bienv3mentaldepresion);
				PHPEXCEL_Escribir($objHoja4, 65, $iFilaHoja4, $lin_cara44bienv3mentalautoconoc);
				PHPEXCEL_Escribir($objHoja4, 66, $iFilaHoja4, $lin_cara44bienv3mentalmindfulness);
				PHPEXCEL_Escribir($objHoja4, 67, $iFilaHoja4, $lin_cara44bienv3mentalautoestima);
				PHPEXCEL_Escribir($objHoja4, 68, $iFilaHoja4, $lin_cara44bienv3mentalcrisis);
				PHPEXCEL_Escribir($objHoja4, 69, $iFilaHoja4, $lin_cara44bienv3mentalburnout);
				PHPEXCEL_Escribir($objHoja4, 70, $iFilaHoja4, $lin_cara44bienv3mentalsexualidad);
				PHPEXCEL_Escribir($objHoja4, 71, $iFilaHoja4, $lin_cara44bienv3mentalusoredes);
				PHPEXCEL_Escribir($objHoja4, 72, $iFilaHoja4, $lin_cara44bienv3mentalinclusion);
				PHPEXCEL_Escribir($objHoja4, 73, $iFilaHoja4, $lin_cara44bienv3mentalactividad);
				PHPEXCEL_Escribir($objHoja4, 74, $iFilaHoja4, $lin_cara44bienv3mentalacompana);
				PHPEXCEL_Escribir($objHoja4, 75, $iFilaHoja4, $lin_cara44bienv3mentaldiagnostico);
				PHPEXCEL_Escribir($objHoja4, 76, $iFilaHoja4, $lin_cara44bienv3mentaldiagcual);
				PHPEXCEL_Escribir($objHoja4, 77, $iFilaHoja4, $lin_cara44bienv3mentaldiagotro);
				// Arte y Cultura
				PHPEXCEL_Escribir($objHoja4, 78, $iFilaHoja4, $lin_cara44bienv3arteintegrar);
				PHPEXCEL_Escribir($objHoja4, 79, $iFilaHoja4, $lin_cara44bienv3arteformacion);
				PHPEXCEL_Escribir($objHoja4, 80, $iFilaHoja4, $lin_cara44bienv3arteunad);
				PHPEXCEL_Escribir($objHoja4, 81, $iFilaHoja4, $lin_cara44bienv3arteinformacion);
				$iFilaHoja4++;
			}
		}

		// HOJA 5 - Discriminación y Violencias Basadas en Género DyVBG
		if ($fila['cara44sexoversion'] > 1) {
			$objExcel->setActiveSheetIndex(4);
			$objHoja5 = $objExcel->getActiveSheet();
			PHPEXCEL_Escribir($objHoja5, 0, $iFilaHoja5, $lin_cara01idtercero_td);
			PHPEXCEL_Escribir($objHoja5, 1, $iFilaHoja5, $lin_cara01idtercero_doc);
			PHPEXCEL_Escribir($objHoja5, 2, $iFilaHoja5, $lin_cara01idtercero_nom);
			$sSQL = 'SELECT TB.cara10idrpta, T9.cara09contenido 
			FROM cara10pregprueba AS TB, cara09pregrpta AS T9 
			WHERE TB.cara10idrpta=T9.cara09id AND TB.cara10idcara=' . $fila['cara01id'] . ' AND TB.cara10idbloque=9
			ORDER BY TB.cara10idpregunta';
			$iCelda = 3;
			$tabla2 = $objDB->ejecutasql($sSQL);
			while ($fila2 = $objDB->sf($tabla2)) {
				if ($fila2['cara10idrpta'] > 0) {
					PHPEXCEL_Escribir($objHoja5, $iCelda, $iFilaHoja5, $fila2['cara09contenido']);
				}
				$iCelda++;
			}
			$iFilaHoja5++;
		}
	}
	$objDB->CerrarConexion();
	PHPExcel_RellenarCeldas($objContenedor, 'A' . $iFilaBase . ':' . $sColumna . $iFilaHoja1, 'Bl', true);
	PHPEXCEL_Escribir($objHoja, 0, 1, '');
	PHPExcel_RellenarCeldas($objContenedor, 'A1', 'Bl', true);
	if ($_REQUEST['clave'] != '') {
		/* Bloquear la hoja. */
		$objHoja->getProtection()->setPassword($_REQUEST['clave']);
		$objHoja->getProtection()->setSheet(true);
		$objHoja->getProtection()->setSort(true);
	}
	$objExcel->setActiveSheetIndex(1);
	$objHoja2 = $objExcel->getActiveSheet();
	PHPExcel_RellenarCeldas($objHoja2, 'A' . $iFilaBase2 . ':' . $sColumna2 . $iFilaHoja2, 'Bl', true);
	$objExcel->setActiveSheetIndex(2);
	$objHoja3 = $objExcel->getActiveSheet();
	PHPExcel_RellenarCeldas($objHoja3, 'A' . $iFilaBase3 . ':' . $sColumna3 . $iFilaHoja3, 'Bl', true);
	$objExcel->setActiveSheetIndex(3);
	$objHoja4 = $objExcel->getActiveSheet();
	PHPExcel_RellenarCeldas($objHoja4, 'A' . $iFilaBase4 . ':' . $sColumna4 . $iFilaHoja4, 'Bl', true);
	$objExcel->setActiveSheetIndex(4);
	$objHoja5 = $objExcel->getActiveSheet();
	PHPExcel_RellenarCeldas($objHoja5, 'A' . $iFilaBase5 . ':' . $sColumna5 . $iFilaHoja5, 'Bl', true);
	$objExcel->setActiveSheetIndex(0);
	/* descargar el resultado */
	header('Expires: Thu, 27 Mar 1980 23:59:00 GMT'); /* la pagina expira en una fecha pasada */
	header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT'); /* ultima actualizacion ahora cuando la cargamos */
	header('Cache-Control: no-cache, must-revalidate'); /* no guardar en CACHE */
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename="' . $sTituloRpt . '.xlsx"');
	header('Cache-Control: max-age=0');
	$objWriter = new Xlsx($objExcel);
	$objWriter->save('php://output');
	die();
} else {
	echo $sError;
}

