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
require $APP->rutacomun . 'libexcel_ss.php';
require $APP->rutacomun . 'vendor/autoload.php';
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
			$objplano->AdicionarLinea(cadena_codificar('Tipo de caracterizacion:' . $cSepara . $fila['cara11nombre']));
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
	}
	if ($core50idprograma != '') {
		$sSQLadd1 = $sSQLadd1 . 'TB.cara01idprograma=' . $core50idprograma . ' AND ';
	} else {
		if ($core50idescuela != '') {
			$sSQLadd1 = $sSQLadd1 . 'TB.cara01idescuela=' . $core50idescuela . ' AND ';
		}
	}
	if ($cara50idperiodo != '') {
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
	$sCampos = 'SELECT TB.* ';
	$sConsulta = 'FROM cara01encuesta AS TB ' . $sTablaConvenio . ' 
	WHERE ' . $sSQLadd1 . ' TB.cara01completa="S" ' . $sSQLadd . '';
	$sOrden = '';
	$sSQLReporte = $sCampos . ' ' . $sConsulta . ' ' . $sOrden;
	// ------------------------------------------------
	// Fin de la consulta
	// ------------------------------------------------
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
		'', '', '', 
		'Discapacidades V 1.', '', '', '', '',
		'Discapacidades V 2.', '', '', '', '', 
		'', '', '', '', '', 
		'', '', '', '', '',
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
		'', '', 
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
		13, 13, 13,
		// Discapacidades V 1.
		13, 13, 13, 13, 13,
		// Discapacidades V 2.
		13, 13, 13, 13, 13, 
		13, 13, 13, 13, 13, 
		13, 13, 13, 13, 13,
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
		13, 13, 
		// Consejero
		13, 
	);
	$iTotalCol = count($aTitulos) - 1;
	$sColumna = 'A';
	for ($k = 0; $k <= $iTotalCol; $k++) {
		PHPEXCEL_Escribir($objHoja, $k, $iFilaHoja1, $aTitulos[$k]);
		$sColumna = columna_Letra($k);
		$objHoja->getColumnDimension($sColumna)->setWidth($aAnchos[$k]);
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
		'Indigena', 'Campesinado', 'Victima desplazado', 'Victima ACR', 'Funcionario INPEC',  
		'Recluso INPEC', 'Tiempo de condena', 'Centro de reclusion', 
		// Discapacidades V 1.
		'Sensorial', 'Fisica', 'Cognitiva', 'Ajustes razonables', 'Ajustes razonables Otra Ayuda', 
		// Discapacidades V 2.
		'Sensorial v2', 'Intelectual', 'Fisica o motora', 'Mental Psicosocial', 'Sistemica', 
		'Sistemica Otro', 'Multiple', 'Multiple Otro', 'Certificado', 'Tiene Trastorno en el aprendizaje', 
		'Trastorno especifico en el aprendizaje', 'Talento Excepcional', 'Pruebas para definir el coeficiente intelectual', 'Con condicion medica', 'Cual condicion medica especifica', 
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
		'Fisica', 'Quimica',  
		// Consejero
		'Consejero', 
	);
	$sColumna = 'A';
	for ($k = 0; $k <= $iTotalCol; $k++) {
		PHPEXCEL_Escribir($objHoja, $k, $iFilaHoja1, $aTitulos[$k]);
		$sColumna = columna_Letra($k);
		$objHoja->getColumnDimension($sColumna)->setWidth($aAnchos[$k]);
		PHPExcel_Justificar_Celda_HorizontalIzquierda($objContenedor, $sColumna . $iFilaHoja1);
	}
	//PHPExcel_Mexclar_Celdas($objContenedor, 'A' . $iFilaHoja1 . ':B' . $iFilaHoja1 . '');
	PHPExcel_Formato_Fuente_Celda($objContenedor, 'A' . $iFilaHoja1 . ':' . $sColumna . $iFilaHoja1, '10', 'Yu Gothic', 'Ne', true, false, false);
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
		$objHoja2->getColumnDimension($sColumna2)->setWidth($aAnchos[$k]);
		PHPExcel_Justificar_Celda_HorizontalIzquierda($objHoja2, $sColumna2 . $iFilaHoja2);
	}
	PHPExcel_Formato_Fuente_Celda($objHoja2, 'A' . $iFilaHoja2 . ':' . $sColumna2 . $iFilaHoja2, '11', 'Yu Gothic', 'RoOs', true, false, false);
	PHPExcel_RellenarCeldas($objHoja2, 'A1:' . $sColumna2 . $iFilaHoja2, 'Bl', true);
	$iFilaHoja2++;
	$iFilaBase2 = $iFilaHoja2;
	$aTitulos = array(
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
		$objHoja2->getColumnDimension($sColumna2)->setWidth($aAnchos[$k]);
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
		$objHoja3->getColumnDimension($sColumna3)->setWidth($aAnchos[$k]);
		PHPExcel_Justificar_Celda_HorizontalIzquierda($objHoja3, $sColumna3 . $iFilaHoja3);
	}
	PHPExcel_Formato_Fuente_Celda($objHoja3, 'A' . $iFilaHoja3 . ':' . $sColumna3 . $iFilaHoja3, '11', 'Yu Gothic', 'RoOs', true, false, false);
	PHPExcel_RellenarCeldas($objHoja3, 'A1:' . $sColumna3 . $iFilaHoja3, 'Bl', true);
	$iFilaHoja3++;
	$iFilaBase3 = $iFilaHoja3;
	$aTitulos = array(
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
		$objHoja3->getColumnDimension($sColumna3)->setWidth($aAnchos[$k]);
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
		'', 
		'', '', '', '', '', 
		'Arte y Cultura', '', '', '', 
	);
	$aAnchos = array(
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
		15, 
		15, 15, 15, 15, 15, 
		// Arte y Cultura
		15, 15, 15, 15, 
	);
	$iTotalCol = count($aTitulos) - 1;
	$sColumna4 = 'A';
	for ($k = 0; $k <= $iTotalCol; $k++) {
		PHPEXCEL_Escribir($objHoja4, $k, $iFilaHoja4, $aTitulos[$k]);
		$sColumna4 = columna_Letra($k);
		$objHoja4->getColumnDimension($sColumna4)->setWidth($aAnchos[$k]);
		PHPExcel_Justificar_Celda_HorizontalIzquierda($objHoja4, $sColumna4 . $iFilaHoja4);
	}
	PHPExcel_Formato_Fuente_Celda($objHoja4, 'A' . $iFilaHoja4 . ':' . $sColumna4 . $iFilaHoja4, '11', 'Yu Gothic', 'RoOs', true, false, false);
	PHPExcel_RellenarCeldas($objHoja4, 'A1:' . $sColumna4 . $iFilaHoja4, 'Bl', true);
	$iFilaHoja4++;
	$iFilaBase4 = $iFilaHoja4;
	$aTitulos = array(
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
		$objHoja4->getColumnDimension($sColumna4)->setWidth($aAnchos[$k]);
		PHPExcel_Justificar_Celda_HorizontalIzquierda($objHoja4, $sColumna4 . $iFilaHoja4);
	}
	PHPExcel_Formato_Fuente_Celda($objHoja4, 'A' . $iFilaHoja4 . ':' . $sColumna4 . $iFilaHoja4, '10', 'Yu Gothic', 'Ne', true, false, false);
	$iFilaHoja4++;

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
		// HOJA 1
		// Datos personales
		// Grupos poblacionales
		// Discapacidades V 1.
		// Discapacidades V 2.
		// Datos familiares
		// Datos academicos
		// Con cuales equipos electronicos cuenta para acceder al campus virtual de la UNAD
		// La informacion que consulta la aprende mejor con
		// Datos laborales
		// Psicosocial
		// Competencias
		// Consejero


		// HOJA 2 - Bienestar v.1
		// Deporte y Recreacion
		// Usted practica regularmente alguna de las siguientes actividades deportivas o recreativas

		// Usted practica regularmente alguna de las siguientes actividades artisticas o culturales
		// Si usted practica danza por favor indique el genero
		// Emprendimiento
		// Estilo de vida saludable
		// Proyecto de vida
		// Medio ambiente
		// Cual de estos habitos cotidianos realiza usted como una practica de respeto hacia Medio Ambiente


		// HOJA 3 - Bienestar v.2
		// Deporte y Recreacion
		// ¿Que deporte practica?
		// Arte y Cultura
		// Usted practica regularmente alguna de las siguientes actividades artisticas o culturales

		// A que clase de eventos artisticos y culturales le gustaria asistir
		// Emprendimiento
		// Cual es el estado en que se encuentra su emprendimiento
		// En que temas le gustaria recibir informacion con respecto al emprendimiento
		// Estilo de vida saludable
		// Causas mas frecuentes del estres
		// Estrategias para conocer el autocuidado
		// Crecimiento Personal
		// Temas de interes para su crecimiento personal
		// Le gustaria hacer parte de algun grupo de bienestar
		// Medio ambiente
		// Realiza alguna de estas acciones frente al cuidado del medio ambiente
		// En su tiempo libre ha participado en alguna actividad ambiental
		// Cual tema desde el enfoque ambiental le gustaria conocer o profundizar


		// HOJA 4 - Bienestar v.3
		// Emprendimiento Solidario
		// Medio Ambiente
		// En que tematica le gustaria participar?
		// Promocion de la Salud y Prevencion de la Enfermedad
		// Deporte y Recreacion
		// Crecimiento Personal
		// Cuales temas son de su interes para fortalecer su crecimiento personal?
		// Que habilidades considera que le ayudarian a desarrollar su maximo potencial?
		// Que lo motiva a seguir buscando su crecimiento personal?
		// Salud Mental
		// Seleccione temas de interes para el cuidado de su Salud Mental
		// Arte y Cultura

		$et_saiu73tiporadicado = '';
		if ($fila['saiu73tiporadicado'] != 0) {
			if (isset($asaiu73tiporadicado[$fila['saiu73tiporadicado']]) == 0) {
				$sDato = '{' . $fila['saiu73tiporadicado'] . '}';
				$sSQL = 'SELECT saiu16nombre FROM saiu16tiporadicado WHERE saiu16id=' . $fila['saiu73tiporadicado'] . '';
				$tablad = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($tablad) > 0) {
					$filad = $objDB->sf($tablad);
					$sDato = $filad['saiu16nombre'];
				}
				$asaiu73tiporadicado[$fila['saiu73tiporadicado']] = $sDato;
			}
			$et_saiu73tiporadicado = $asaiu73tiporadicado[$fila['saiu73tiporadicado']];
		}
		PHPEXCEL_Escribir($objHoja, 0, $iFilaHoja1, $et_saiu73tiporadicado);
		PHPEXCEL_Escribir($objHoja, 1, $iFilaHoja1, $fila['saiu73consec']);
		PHPEXCEL_Escribir($objHoja, 2, $iFilaHoja1, $fila['saiu73origenagno']);
		PHPEXCEL_Escribir($objHoja, 3, $iFilaHoja1, $fila['saiu73origenmes']);
		PHPEXCEL_Escribir($objHoja, 4, $iFilaHoja1, $fila['saiu73origenid']);
		PHPEXCEL_Escribir($objHoja, 5, $iFilaHoja1, $fila['saiu73fecha']);
		PHPEXCEL_Escribir($objHoja, 6, $iFilaHoja1, $fila['saiu73hora']);
		PHPEXCEL_Escribir($objHoja, 7, $iFilaHoja1, $fila['saiu73minuto']);
		$iFilaHoja1++;
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

