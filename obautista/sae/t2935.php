<?php
/*
--- © Omar Augusto Bautista - UNAD - 2026 ---
--- omar.bautista@unad.edu.co - http://www.unad.edu.co
--- Modelo Version 3.1.5 viernes, 27 de febrero de 2026
*/
/*
/** Archivo para reportes tipo csv 2935.
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
	$mensajes_2935 = 'lg/lg_2935_' . $sIdioma . '.php';
	if (!file_exists($mensajes_2935)) {
		$mensajes_2935 = 'lg/lg_2935_es.php';
	}
	require $mensajes_todas;
	require $mensajes_2935;
	$visa35consec_lg = 'Consecutivo';
	$visa35id_lg = 'Ref';
	$visa35idtipo_lg = 'Tipo convocatoria';
	$visa35nombre_lg = 'Nombre';
	$visa35idzona_lg = 'Zona';
	$visa35idcentro_lg = 'Centro';
	$visa35idescuela_lg = 'Escuela';
	$visa35idprograma_lg = 'Programa';
	$visa35gruponivel_lg = 'Grupo Nivel';
	$visa35nivelforma_lg = 'Nivel';
	$visa35estado_lg = 'Estado';
	$visa35numcupos_lg = 'Numero Cupos';
	$visa35fecha_apertura_lg = 'Fecha apertura';
	$visa35fecha_liminscrip_lg = 'Fecha limite inscripcion';
	$visa35fecha_limrevdoc_lg = 'Fecha limte rev. Documentos';
	$visa35fecha_examenes_lg = 'Fecha examenes';
	$visa35fecha_seleccion_lg = 'Fecha selección';
	$visa35fecha_ratificacion_lg = 'Fecha ratificación';
	$visa35fecha_cierra_lg = 'Fecha cierre';
	$visa35presentacion_lg = 'Presentación convocatoria';
	$visa35total_inscritos_lg = 'Total inscritos';
	$visa35total_autorizados_lg = 'Total autorizados';
	$visa35total_presentaex_lg = 'Total presentan examen';
	$visa35total_aprobados_lg = 'Total aprobados';
	$visa35total_admitidos_lg = 'Total admitidos';
	$visa35idconvenio_lg = 'Idconvenio';
	$visa35idresolucion_lg = 'Idresolucion';
	$visa35idproducto_lg = 'Producto a cobrar';
	$bnombre_lg = 'Convocatoria';
	$bestado_lg = 'Estado';
	$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto != '') {
		$objDB->dbPuerto = $APP->dbpuerto;
	}
	// ----------- Espacio para los parametros.
	$sCondi = 'WHERE visa35consec=' . $DATA['visa35consec'] . '';
	// ----------- Fin del bloque de parametros.
	$sPath = dirname(__FILE__);
	$sSeparador = archivos_separador($sPath);
	$sPath = archivos_rutaservidor($sPath, $sSeparador);
	$sNombrePlano = 't2935.csv';
	$sTituloRpt = 'visa35convocatoria';
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
	$sDato = cadena_codificar('visa35convocatoria');
	$objplano->AdicionarLinea($sDato);
	$sDato = '';
	$objplano->AdicionarLinea($sDato);
	/* Alistar los arreglos para las tablas hijas */
	$avisa35idtipo = array('');
	/*
	$sSQL = 'SELECT visa34id, visa34nombre FROM visa34convtipo';
	$tabla = $objDB->ejecutasql($sSQL);
	while ($fila = $objDB->sf($tabla)) {
		$avisa35idtipo[$fila['visa34id']] = cadena_codificar($fila['visa34nombre']);
	}
	*/
	$avisa35idzona = array('');
	/*
	$sSQL = 'SELECT unad23id, unad23nombre FROM unad23zona';
	$tabla = $objDB->ejecutasql($sSQL);
	while ($fila = $objDB->sf($tabla)) {
		$avisa35idzona[$fila['unad23id']] = cadena_codificar($fila['unad23nombre']);
	}
	*/
	$avisa35idcentro = array('');
	/*
	$sSQL = 'SELECT unad24id, unad24nombre FROM unad24sede';
	$tabla = $objDB->ejecutasql($sSQL);
	while ($fila = $objDB->sf($tabla)) {
		$avisa35idcentro[$fila['unad24id']] = cadena_codificar($fila['unad24nombre']);
	}
	*/
	$avisa35idescuela = array('');
	/*
	$sSQL = 'SELECT core12id, core12nombre FROM core12escuela';
	$tabla = $objDB->ejecutasql($sSQL);
	while ($fila = $objDB->sf($tabla)) {
		$avisa35idescuela[$fila['core12id']] = cadena_codificar($fila['core12nombre']);
	}
	*/
	$avisa35idprograma = array('');
	/*
	$sSQL = 'SELECT core09id, core09nombre FROM core09programa';
	$tabla = $objDB->ejecutasql($sSQL);
	while ($fila = $objDB->sf($tabla)) {
		$avisa35idprograma[$fila['core09id']] = cadena_codificar($fila['core09nombre']);
	}
	*/
	$avisa35gruponivel = array('');
	$avisa35nivelforma = array('');
	/*
	$sSQL = 'SELECT core22id, core22nombre FROM core22nivelprograma';
	$tabla = $objDB->ejecutasql($sSQL);
	while ($fila = $objDB->sf($tabla)) {
		$avisa35nivelforma[$fila['core22id']] = cadena_codificar($fila['core22nombre']);
	}
	*/
	$avisa35idproducto = array('');
	/*
	$sSQL = 'SELECT cart01id, cart01nombre FROM cart01productos';
	$tabla = $objDB->ejecutasql($sSQL);
	while ($fila = $objDB->sf($tabla)) {
		$avisa35idproducto[$fila['cart01id']] = cadena_codificar($fila['cart01nombre']);
	}
	*/
	$sTitulo1 = 'Titulo 1';
	for ($l = 1; $l <= 20; $l++) {
		$sTitulo1 = $sTitulo1 . $cSepara;
	}
	$sBloque1 = '' . $visa35consec_lg . $cSepara . $visa35idtipo_lg . $cSepara . $visa35nombre_lg . $cSepara . $visa35idzona_lg . $cSepara . $visa35idcentro_lg . $cSepara
	 . $visa35idescuela_lg . $cSepara . $visa35idprograma_lg . $cSepara . $visa35gruponivel_lg . $cSepara . $visa35nivelforma_lg . $cSepara . $visa35estado_lg . $cSepara
	 . $visa35numcupos_lg . $cSepara . $visa35fecha_apertura_lg . $cSepara . $visa35fecha_liminscrip_lg . $cSepara . $visa35fecha_limrevdoc_lg . $cSepara . $visa35fecha_examenes_lg . $cSepara
	 . $visa35fecha_seleccion_lg . $cSepara . $visa35fecha_ratificacion_lg . $cSepara . $visa35fecha_cierra_lg . $cSepara . $visa35presentacion_lg . $cSepara . $visa35total_inscritos_lg;
	$sTitulo2 = 'Titulo 2';
	for ($l = 1; $l <= 9; $l++) {
		$sTitulo2 = $sTitulo2 . $cSepara;
	}
	$sBloque2 = '' . $cSepara . $visa35total_autorizados_lg . $cSepara . $visa35total_presentaex_lg . $cSepara . $visa35total_aprobados_lg . $cSepara . $visa35total_admitidos_lg . $cSepara . $visa35idconvenio_lg . $cSepara
	 . $visa35idresolucion_lg . $cSepara . $visa35idproducto_lg . $cSepara . $bnombre_lg . $cSepara . $bestado_lg;
	//$objplano->AdicionarLinea($sTitulo1 . $sTitulo2);
	$objplano->AdicionarLinea($sBloque1 . $sBloque2);
	$sCampos = 'SELECT TB.*
	$sConsulta = 'FROM visa35convocatoria 
	' . $sCondi . '';
	$sOrden = '';
	$sSQL = $sCampos . ' ' . $sConsulta . ' ' . $sOrden;
	if ($bDebug) {
		$objplano->AdicionarLinea($sSQL);
	}
	$tabla = $objDB->ejecutasql($sSQL);
	while ($fila = $objDB->sf($tabla)) {
		$lin_visa35consec = '';
		$lin_visa35idtipo = $cSepara;
		$lin_visa35nombre = $cSepara;
		$lin_visa35idzona = $cSepara;
		$lin_visa35idcentro = $cSepara;
		$lin_visa35idescuela = $cSepara;
		$lin_visa35idprograma = $cSepara;
		$lin_visa35gruponivel = $cSepara;
		$lin_visa35nivelforma = $cSepara;
		$lin_visa35estado = $cSepara;
		$lin_visa35numcupos = $cSepara;
		$lin_visa35fecha_apertura = $cSepara;
		$lin_visa35fecha_liminscrip = $cSepara;
		$lin_visa35fecha_limrevdoc = $cSepara;
		$lin_visa35fecha_examenes = $cSepara;
		$lin_visa35fecha_seleccion = $cSepara;
		$lin_visa35fecha_ratificacion = $cSepara;
		$lin_visa35fecha_cierra = $cSepara;
		$lin_visa35presentacion = $cSepara;
		$lin_visa35total_inscritos = $cSepara;
		$lin_visa35total_autorizados = $cSepara;
		$lin_visa35total_presentaex = $cSepara;
		$lin_visa35total_aprobados = $cSepara;
		$lin_visa35total_admitidos = $cSepara;
		$lin_visa35idconvenio = $cSepara;
		$lin_visa35idresolucion = $cSepara;
		$lin_visa35idproducto = $cSepara;
		$lin_bnombre = $cSepara;
		$lin_bestado = $cSepara;
		$lin_visa35consec = $fila['visa35consec'];
		$i_visa35idtipo = $fila['visa35idtipo'];
		if (isset($avisa35idtipo[$i_visa35idtipo]) == 0) {
			$sSQL = 'SELECT visa34nombre FROM visa34convtipo WHERE visa34id=' . $i_visa35idtipo . '';
			$tablae = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae) > 0) {
				$filae = $objDB->sf($tablae);
				$avisa35idtipo[$i_visa35idtipo] = str_replace($cSepara, $cComplementa, $filae['visa34nombre']);
			} else {
				$avisa35idtipo[$i_visa35idtipo] = '';
			}
		}
		$lin_visa35idtipo = $cSepara . cadena_codificar($avisa35idtipo[$i_visa35idtipo]);
		$lin_visa35nombre = $cSepara . str_replace($cSepara, $cComplementa, cadena_codificar($fila['visa35nombre']));
		$i_visa35idzona = $fila['visa35idzona'];
		if (isset($avisa35idzona[$i_visa35idzona]) == 0) {
			$sSQL = 'SELECT unad23nombre FROM unad23zona WHERE unad23id=' . $i_visa35idzona . '';
			$tablae = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae) > 0) {
				$filae = $objDB->sf($tablae);
				$avisa35idzona[$i_visa35idzona] = str_replace($cSepara, $cComplementa, $filae['unad23nombre']);
			} else {
				$avisa35idzona[$i_visa35idzona] = '';
			}
		}
		$lin_visa35idzona = $cSepara . cadena_codificar($avisa35idzona[$i_visa35idzona]);
		$i_visa35idcentro = $fila['visa35idcentro'];
		if (isset($avisa35idcentro[$i_visa35idcentro]) == 0) {
			$sSQL = 'SELECT unad24nombre FROM unad24sede WHERE unad24id=' . $i_visa35idcentro . '';
			$tablae = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae) > 0) {
				$filae = $objDB->sf($tablae);
				$avisa35idcentro[$i_visa35idcentro] = str_replace($cSepara, $cComplementa, $filae['unad24nombre']);
			} else {
				$avisa35idcentro[$i_visa35idcentro] = '';
			}
		}
		$lin_visa35idcentro = $cSepara . cadena_codificar($avisa35idcentro[$i_visa35idcentro]);
		$i_visa35idescuela = $fila['visa35idescuela'];
		if (isset($avisa35idescuela[$i_visa35idescuela]) == 0) {
			$sSQL = 'SELECT core12nombre FROM core12escuela WHERE core12id=' . $i_visa35idescuela . '';
			$tablae = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae) > 0) {
				$filae = $objDB->sf($tablae);
				$avisa35idescuela[$i_visa35idescuela] = str_replace($cSepara, $cComplementa, $filae['core12nombre']);
			} else {
				$avisa35idescuela[$i_visa35idescuela] = '';
			}
		}
		$lin_visa35idescuela = $cSepara . cadena_codificar($avisa35idescuela[$i_visa35idescuela]);
		$i_visa35idprograma = $fila['visa35idprograma'];
		if (isset($avisa35idprograma[$i_visa35idprograma]) == 0) {
			$sSQL = 'SELECT core09nombre FROM core09programa WHERE core09id=' . $i_visa35idprograma . '';
			$tablae = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae) > 0) {
				$filae = $objDB->sf($tablae);
				$avisa35idprograma[$i_visa35idprograma] = str_replace($cSepara, $cComplementa, $filae['core09nombre']);
			} else {
				$avisa35idprograma[$i_visa35idprograma] = '';
			}
		}
		$lin_visa35idprograma = $cSepara . cadena_codificar($avisa35idprograma[$i_visa35idprograma]);
		$lin_visa35gruponivel = $cSepara . '[' . $fila['visa35gruponivel'] . ']';
		if (isset($avisa35gruponivel[$fila['visa35gruponivel']]) != 0) {
			$lin_visa35gruponivel = $cSepara . cadena_codificar($avisa35gruponivel[$fila['visa35gruponivel']]);
		}
		$i_visa35nivelforma = $fila['visa35nivelforma'];
		if (isset($avisa35nivelforma[$i_visa35nivelforma]) == 0) {
			$sSQL = 'SELECT core22nombre FROM core22nivelprograma WHERE core22id=' . $i_visa35nivelforma . '';
			$tablae = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae) > 0) {
				$filae = $objDB->sf($tablae);
				$avisa35nivelforma[$i_visa35nivelforma] = str_replace($cSepara, $cComplementa, $filae['core22nombre']);
			} else {
				$avisa35nivelforma[$i_visa35nivelforma] = '';
			}
		}
		$lin_visa35nivelforma = $cSepara . cadena_codificar($avisa35nivelforma[$i_visa35nivelforma]);
		$lin_visa35estado = $cSepara . $fila['visa35estado'];
		$lin_visa35numcupos = $cSepara . $fila['visa35numcupos'];
		$lin_visa35fecha_apertura = $cSepara . fecha_desdenumero($fila['visa35fecha_apertura']);
		$lin_visa35fecha_liminscrip = $cSepara . fecha_desdenumero($fila['visa35fecha_liminscrip']);
		$lin_visa35fecha_limrevdoc = $cSepara . fecha_desdenumero($fila['visa35fecha_limrevdoc']);
		$lin_visa35fecha_examenes = $cSepara . fecha_desdenumero($fila['visa35fecha_examenes']);
		$lin_visa35fecha_seleccion = $cSepara . fecha_desdenumero($fila['visa35fecha_seleccion']);
		$lin_visa35fecha_ratificacion = $cSepara . fecha_desdenumero($fila['visa35fecha_ratificacion']);
		$lin_visa35fecha_cierra = $cSepara . fecha_desdenumero($fila['visa35fecha_cierra']);
		$lin_visa35presentacion = $cSepara . str_replace($cSepara, $cComplementa, cadena_QuitarSaltos(cadena_codificar($fila['visa35presentacion'])));
		$lin_visa35total_inscritos = $cSepara . $fila['visa35total_inscritos'];
		$lin_visa35total_autorizados = $cSepara . $fila['visa35total_autorizados'];
		$lin_visa35total_presentaex = $cSepara . $fila['visa35total_presentaex'];
		$lin_visa35total_aprobados = $cSepara . $fila['visa35total_aprobados'];
		$lin_visa35total_admitidos = $cSepara . $fila['visa35total_admitidos'];
		$lin_visa35idconvenio = $cSepara . $fila['visa35idconvenio'];
		$lin_visa35idresolucion = $cSepara . $fila['visa35idresolucion'];
		$i_visa35idproducto = $fila['visa35idproducto'];
		if (isset($avisa35idproducto[$i_visa35idproducto]) == 0) {
			$sSQL = 'SELECT cart01nombre FROM cart01productos WHERE cart01id=' . $i_visa35idproducto . '';
			$tablae = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae) > 0) {
				$filae = $objDB->sf($tablae);
				$avisa35idproducto[$i_visa35idproducto] = str_replace($cSepara, $cComplementa, $filae['cart01nombre']);
			} else {
				$avisa35idproducto[$i_visa35idproducto] = '';
			}
		}
		$lin_visa35idproducto = $cSepara . cadena_codificar($avisa35idproducto[$i_visa35idproducto]);
		$lin_bnombre = $cSepara . $fila['bnombre'];
		$lin_bestado = $cSepara . $fila['bestado'];
		$sBloque1 = '' . $lin_visa35consec . $lin_visa35idtipo . $lin_visa35nombre . $lin_visa35idzona . $lin_visa35idcentro
		 . $lin_visa35idescuela . $lin_visa35idprograma . $lin_visa35gruponivel . $lin_visa35nivelforma . $lin_visa35estado
		 . $lin_visa35numcupos . $lin_visa35fecha_apertura . $lin_visa35fecha_liminscrip . $lin_visa35fecha_limrevdoc . $lin_visa35fecha_examenes
		 . $lin_visa35fecha_seleccion . $lin_visa35fecha_ratificacion . $lin_visa35fecha_cierra . $lin_visa35presentacion . $lin_visa35total_inscritos;
		$sBloque2 = '' . $lin_visa35total_autorizados . $lin_visa35total_presentaex . $lin_visa35total_aprobados . $lin_visa35total_admitidos . $lin_visa35idconvenio
		 . $lin_visa35idresolucion . $lin_visa35idproducto . $lin_bnombre . $lin_bestado;
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
