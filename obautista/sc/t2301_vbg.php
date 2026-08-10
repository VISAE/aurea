<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2018 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Version 2.21.0 viernes, 22 de junio de 2018
--- Modelo Version 2.28.1 sábado, 23 de abril de 2022
---
--- Reporte de Resultados Preguntas de Discriminación y Violencias Basadas en Género (DyVBG)
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
require $APP->rutacomun . 'lib2301.php';
if ($_SESSION['unad_id_tercero'] == 0) {
	header('Location:./nopermiso.php');
	die();
}
$_SESSION['u_ultimominuto'] = iminutoavance();
$sError = '';
$iReporte = 0;
if (isset($_REQUEST['r']) != 0) {
	$iReporte = numeros_validar($_REQUEST['r']);
}
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
if (isset($_REQUEST['v6']) == 0) {
	$_REQUEST['v6'] = '';
}
if (isset($_REQUEST['v7']) == 0) {
	$_REQUEST['v7'] = 1;
}
if (isset($_REQUEST['v8']) == 0) {
	$_REQUEST['v8'] = '';
}
if (isset($_REQUEST['v9']) == 0) {
	$_REQUEST['v9'] = '';
}
if (isset($_REQUEST['v10']) == 0) {
	$_REQUEST['v10'] = '';
}
if (isset($_REQUEST['v11']) == 0) {
	$_REQUEST['v11'] = '';
}
if (isset($_REQUEST['v12']) == 0) {
	$_REQUEST['v12'] = '';
}
if (isset($_REQUEST['v13']) == 0) {
	$_REQUEST['v13'] = '';
}
if (isset($_REQUEST['v14']) == 0) {
	$_REQUEST['v14'] = '';
}
if (isset($_REQUEST['rdebug']) == 0) {
	$_REQUEST['rdebug'] = 0;
}
$bEntra = true;
$bDebug = false;
if ($sError != '') {
	$bEntra = false;
}
if ($bEntra) {
	$idTercero = $_SESSION['unad_id_tercero'];
	$iCodModulo = 2350;
	$sDebug = '';
	if ($_REQUEST['rdebug'] == 1) {
		$bDebug = true;
	}
	$cSepara = ',';
	$cEvita = ';';
	$cComplementa = '.';
	if (isset($_REQUEST['separa']) != 0) {
		if ($_REQUEST['separa'] == ';') {
			$cSepara = ';';
			$cEvita = ',';
		}
	}
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
	require $mensajes_todas;
	require $mensajes_2301;
	require $mensajes_2344;
	$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto != '') {
		$objDB->dbPuerto = $APP->dbpuerto;
	}
	$cara50idperiodo = numeros_validar($_REQUEST['v3']);
	$cara50idzona = numeros_validar($_REQUEST['v4']);
	$cara50idcentro = numeros_validar($_REQUEST['v5']);
	$core50idescuela = numeros_validar($_REQUEST['v9']);
	$core50idprograma = numeros_validar($_REQUEST['v10']);
	$core50tipo = numeros_validar($_REQUEST['v6']);
	$cara50poblacion = numeros_validar($_REQUEST['v7']);
	$cara50periodoacomp = numeros_validar($_REQUEST['v11']);
	$cara50convenio = numeros_validar($_REQUEST['v8']);
	$cara50periodomatricula = numeros_validar($_REQUEST['v12']);
	$cara50tipomatricula = numeros_validar($_REQUEST['v13']);
	$cara50listadoc = cadena_Validar($_REQUEST['v14']);
	$sCondi = '';
	$sPath = dirname(__FILE__);
	$sSeparador = archivos_separador($sPath);
	$sPath = archivos_rutaservidor($sPath, $sSeparador);
	$sNombrePlano = 't2301.csv';
	$sTituloRpt = 'discriminacion_y_VBG_';
	$objplano = new clsPlanos($sPath . $sNombrePlano);
	$sDato = 'UNIVERSIDAD NACIONAL ABIERTA Y A DISTANCIA - UNAD';
	$objplano->AdicionarLinea($sDato);
	$bVerBienV1 = false;
	$bVerBienV2 = false;
	$bVerBienV3 = false;
	$sWhere = '';
	$sWhereAdd = '';
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
		$sDato = cadena_codificar('Resultados Discriminación y Violencias Basadas en Género - periodo: ' . $sNomPeraca);
		$objplano->AdicionarLinea($sDato);
	}
	if ($cara50poblacion == '9') {
		//Es un total, tenemos que limitar la zona...
		$bEntra = false;
		if (seg_revisa_permiso($iCodModulo, 12, $objDB)) {
			$bEntra = true;
		}
		//if (seg_revisa_permiso($iCodModulo, 1710, $objDB)){$bEntra=true;}
		if (!$bEntra) {
			if ($cara50idzona != '') {
				//Verificar que la zona sea la que esta solicitando.
				$sSQL = 'SELECT cara21idzona FROM cara21lidereszona WHERE cara21idlider=' . $idTercero . ' AND cara21activo="S" AND cara21idzona=' . $cara50idzona . '';
				$tabla = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($tabla) > 0) {
					//No problema es un zonal y esta consultando su zona.
				} else {
					$sWhere = $sWhere . 'TB.cara01idconsejero=' . $_SESSION['unad_id_tercero'] . ' AND ';
				}
			} else {
				//Puede ver lo suyo....
				$sWhere = $sWhere . 'TB.cara01idconsejero=' . $_SESSION['unad_id_tercero'] . ' AND ';
			}
		}
	} else {
		$sWhere = $sWhere . 'TB.cara01idconsejero=' . $_SESSION['unad_id_tercero'] . ' AND ';
		$bConConsejero = true;
	}
	$bConConsejero = true;
	if ($cara50idcentro != '') {
		$sWhere = $sWhere . 'TB.cara01idcead=' . $cara50idcentro . ' AND ';
	} else {
		if ($cara50idzona != '') {
			$sWhere = $sWhere . 'TB.cara01idzona=' . $cara50idzona . ' AND ';
		}
	}
	$bPorTipo = false;
	if ($core50tipo != '') {
		$sWhere = $sWhere . 'TB.cara01tipocaracterizacion=' . $core50tipo . ' AND ';
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
		}
	}
	$sTablaConvenio = '';
	if ($cara50convenio != '') {
		$sTablaConvenio = ', core51convenioest AS T51';
		$sWhere = $sWhere . 'TB.cara01idtercero=T51.core51idtercero AND T51.core51idconvenio=' . $cara50convenio . ' AND T51.core51activo="S" AND ';
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
		$sDato = cadena_codificar('Periodo de acompañamiento: ' . $sNomPeraca);
		$objplano->AdicionarLinea($sDato);
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
		$sDato = cadena_codificar('Estudiantes' . $sAddTitulo . ' matriculados en el periodo: ' . $sNomPeraca);
		$objplano->AdicionarLinea($sDato);
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
		$sWhere = $sWhere . 'TB.cara01idprograma=' . $core50idprograma . ' AND ';
	} else {
		if ($core50idescuela != '') {
			$sWhere = $sWhere . 'TB.cara01idescuela=' . $core50idescuela . ' AND ';
		}
	}
	if ($cara50idperiodo != '') {
		list($bVerBienV1, $bVerBienV2, $bVerBienV3) = f2301_VerBienestarVersion($cara50idperiodo);
		if ($cara50periodoacomp != '') {
			$sWhere = $sWhere . 'TB.cara01idperaca=' . $cara50idperiodo . ' AND TB.cara01idperiodoacompana=' . $cara50periodoacomp . ' AND ';
		} else {
			$sWhere = '' . $sWhere . 'TB.cara01idperaca=' . $cara50idperiodo . ' AND ';
		}
	} else {
		if ($cara50periodoacomp != '') {
			$sWhere = '' . $sWhere . 'TB.cara01idperiodoacompana=' . $cara50periodoacomp . ' AND ';
		}
	}
	if ($cara50listadoc != '') {
		$sdatos = '';
		$sListaDoc = cadena_limpiar($cara50listadoc, "0123456789\n");
		$cara50listadoc = implode('","', array_filter(explode("\n", $sListaDoc)));
		if ($cara50listadoc != '') {
			$sSQL = 'SELECT unad11id FROM unad11terceros WHERE unad11doc IN ("' . $cara50listadoc . '")';
			$tabla = $objDB->ejecutasql($sSQL);
			while ($fila = $objDB->sf($tabla)) {
				if ($sdatos != '') {
					$sdatos = $sdatos . ', ';
				}
				$sdatos = $sdatos . $fila['unad11id'];
			}
			if ($sdatos != '') {
				$sWhereAdd = $sWhereAdd . 'cara01idtercero IN (' . $sdatos . ') AND ';
			}
		}
	}
	$sNombrePlanoFinal = $sTituloRpt . '.csv';
	/* Alistar los arreglos para las tablas hijas */
	$acara01idzona = array();
	$acara01idcead = array();
	$acara01idescuela = array();
	$acara01idprograma = array();
	$acara01tipocaracterizacion = array();
	$aSys11 = array();
	$sTitulo1 = 'Datos personales';
	for ($l = 1; $l <= 21; $l++) {
		$sSubTitulo = '';
		if ($l == 9) {
			$sSubTitulo = 'Preguntas DyVBG';
		}
		$sTitulo1 = $sTitulo1 . $cSepara . $sSubTitulo;
	}
	$sBloque1 = 'Tipo Caracterización' . $cSepara . 'TD' . $cSepara . 'Doc' . $cSepara . 'Estudiante' . $cSepara
		. 'Fecha encuesta' . $cSepara . 'Zona' . $cSepara . 'CEAD' . $cSepara . 'Escuela' . $cSepara . 'Programa' . $cSepara
		. 'Considero importante que las instituciones de educación superior cuenten con acciones para prevenir la discriminación y las violencias basadas en género (DyVBG).' . $cSepara
		. 'Me genera confianza que existan mecanismos institucionales para atender situaciones relacionadas con discriminación y violencias basadas en género (DyVBG) y el acoso sexual.' . $cSepara
		. 'Resulta necesario que las instituciones de educación superior desarrollen acciones de prevención y formación frente a la discriminación y las violencias basadas en género (DyVBG) y el acoso sexual.' . $cSepara
		. 'Ante una situación de discriminación y violencias basadas en género (DyVBG) o acoso sexual, acudiría a los canales institucionales de atención y reporte.' . $cSepara
		. 'En mis experiencias educativas previas se promovieron acciones de respeto y prevención frente a la discriminación y las violencias basadas en género (DyVBG) y el acoso sexual.' . $cSepara
		. 'Es importante que las instituciones de educación superior implementen medidas de protección y atención para las personas afectadas por situaciones de discriminación y violencias basadas en género (DyVBG) y acoso sexual.' . $cSepara
		. 'Es fundamental contribuir a la construcción de ambientes educativos libres de discriminación y violencias basadas en género (DyVBG) y acoso sexual.' . $cSepara
		. 'Resulta necesario que las instituciones de educación superior promuevan ambientes de respeto, inclusión y convivencia libres de discriminación y violencias basadas en género (DyVBG) y acoso sexual.' . $cSepara
		. 'La discriminación y las violencias basadas en género (DyVBG) y el acoso sexual pueden afectar el bienestar emocional, la salud mental y el desempeño académico de las personas.' . $cSepara
		. 'La presencia de conductas asociadas a discriminación y violencias basadas en género (DyVBG) y acoso sexual puede impactar negativamente la convivencia universitaria y el desempeño académico estudiantil.' . $cSepara
		. 'En mi experiencia personal, han estado presentes situaciones de discriminación y violencias basadas en género (DyVBG) asociadas a la orientación sexual o identidad/expresión de género.' . $cSepara
		. 'En algún contexto de vida, es posible que se presentaran situaciones de violencia sexual, acoso sexual o ciberacoso.' . $cSepara
		. ' ';
	$sDato = '';
	$objplano->AdicionarLinea($sDato);
	$objplano->AdicionarLinea($sTitulo1);
	$objplano->AdicionarLinea($sBloque1);
	$sSQL = 'SELECT TB.* 
	FROM cara01encuesta AS TB' . $sTablaConvenio . ' 
	WHERE ' . $sSQLadd1 . $sWhere . $sWhereAdd . ' TB.cara01completa="S"';
	if ($bDebug) {
		//$objplano->AdicionarLinea($sSQL);
	}
	$tabla = $objDB->ejecutasql($sSQL);
	while ($fila = $objDB->sf($tabla)) {
		$lin_cara01tipocaracterizacion = '';
		$lin_cara01idtercero = $cSepara . $cSepara . $cSepara;
		$lin_cara01fechaencuesta = $cSepara;
		$lin_cara01idzona = $cSepara;
		$lin_cara01idcead = $cSepara;
		$lin_cara01idescuela = $cSepara;
		$lin_cara01idprograma = $cSepara;
		$lin_cara10idrpta = $cSepara;
		$i_cara01tipocaracterizacion = $fila['cara01tipocaracterizacion'];
		if (isset($acara01tipocaracterizacion[$i_cara01tipocaracterizacion]) == 0) {
			$sSQL = 'SELECT cara11nombre FROM cara11tipocaract WHERE cara11id=' . $i_cara01tipocaracterizacion . '';
			$tablae = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae) > 0) {
				$filae = $objDB->sf($tablae);
				$acara01tipocaracterizacion[$i_cara01tipocaracterizacion] = str_replace($cSepara, $cComplementa, $filae['cara11nombre']);
			} else {
				$acara01tipocaracterizacion[$i_cara01tipocaracterizacion] = '';
			}
		}
		$lin_cara01tipocaracterizacion = cadena_codificar($acara01tipocaracterizacion[$i_cara01tipocaracterizacion]);
		$iTer = $fila['cara01idtercero'];
		if (isset($aSys11[$iTer]['doc']) == 0) {
			list($aSys11[$iTer]['td'], $aSys11[$iTer]['doc'], $aSys11[$iTer]['razon'], $aSys11[$iTer]['ult_ing']) = f2301_InfoParaPlano($iTer, $objDB);
		}
		$lin_cara01idtercero = $cSepara . $aSys11[$iTer]['td'] . $cSepara . $aSys11[$iTer]['doc'] . $cSepara . $aSys11[$iTer]['razon'];
		$lin_cara01fechaencuesta = $cSepara . $fila['cara01fechaencuesta'];
		$i_cara01idzona = $fila['cara01idzona'];
		if (isset($acara01idzona[$i_cara01idzona]) == 0) {
			$sSQL = 'SELECT unad23nombre FROM unad23zona WHERE unad23id=' . $i_cara01idzona . '';
			$tablae = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae) > 0) {
				$filae = $objDB->sf($tablae);
				$acara01idzona[$i_cara01idzona] = str_replace($cSepara, $cComplementa, $filae['unad23nombre']);
			} else {
				$acara01idzona[$i_cara01idzona] = '';
			}
		}
		$lin_cara01idzona = $cSepara . cadena_codificar($acara01idzona[$i_cara01idzona]);
		$i_cara01idcead = $fila['cara01idcead'];
		if (isset($acara01idcead[$i_cara01idcead]) == 0) {
			$sSQL = 'SELECT unad24nombre FROM unad24sede WHERE unad24id=' . $i_cara01idcead . '';
			$tablae = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae) > 0) {
				$filae = $objDB->sf($tablae);
				$acara01idcead[$i_cara01idcead] = str_replace($cSepara, $cComplementa, $filae['unad24nombre']);
			} else {
				$acara01idcead[$i_cara01idcead] = '';
			}
		}
		$lin_cara01idcead = $cSepara . cadena_codificar($acara01idcead[$i_cara01idcead]);
		$i_cara01idprograma = $fila['cara01idprograma'];
		if (isset($acara01idprograma[$i_cara01idprograma]) == 0) {
			$sSQL = 'SELECT core09nombre FROM core09programa WHERE core09id=' . $i_cara01idprograma . '';
			$tablae = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae) > 0) {
				$filae = $objDB->sf($tablae);
				$acara01idprograma[$i_cara01idprograma] = str_replace($cSepara, $cComplementa, $filae['core09nombre']);
			} else {
				$acara01idprograma[$i_cara01idprograma] = '[' . $i_cara01idprograma . ']';
			}
		}
		$lin_cara01idprograma = $cSepara . cadena_codificar($acara01idprograma[$i_cara01idprograma]);
		$i_cara01idescuela = $fila['cara01idescuela'];
		if (isset($acara01idescuela[$i_cara01idescuela]) == 0) {
			$sSQL = 'SELECT core12nombre FROM core12escuela WHERE core12id=' . $i_cara01idescuela . '';
			$tablae = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae) > 0) {
				$filae = $objDB->sf($tablae);
				$acara01idescuela[$i_cara01idescuela] = str_replace($cSepara, $cComplementa, $filae['core12nombre']);
			} else {
				$acara01idescuela[$i_cara01idescuela] = '';
			}
		}
		$lin_cara01idescuela = $cSepara . cadena_codificar($acara01idescuela[$i_cara01idescuela]);
		$sSQL = 'SELECT TB.cara10idrpta, T9.cara09contenido 
		FROM cara10pregprueba AS TB, cara09pregrpta AS T9 
		WHERE TB.cara10idrpta=T9.cara09id AND TB.cara10idcara=' . $fila['cara01id'] . ' AND TB.cara10idbloque=9
		ORDER BY TB.cara10idpregunta';
		$tabla1 = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla1) > 0) {
			$lin_cara10idrpta = '';
			while ($fila1 = $objDB->sf($tabla1)) {
				if ($fila1['cara10idrpta'] > 0) {
					$lin_cara10idrpta = $lin_cara10idrpta . $cSepara . $fila1['cara09contenido'];
				}
			}
		}
		$sBloque1 = '' . $lin_cara01tipocaracterizacion . $lin_cara01idtercero . $lin_cara01fechaencuesta
			. $lin_cara01idzona . $lin_cara01idcead . $lin_cara01idescuela . $lin_cara01idprograma . $lin_cara10idrpta;
		$objplano->AdicionarLinea($sBloque1);
	}
	$objDB->CerrarConexion();
	$objplano->Generar();
	header('Content-Description: File Transfer');
	header('Content-Type: text/csv');
	header('Content-Length: ' . filesize($sPath . $sNombrePlano));
	header('Content-Disposition: attachment; filename=' . basename($sNombrePlanoFinal));
	readfile($sPath . $sNombrePlano);
} else {
	echo $sError;
}
