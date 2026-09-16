<?php
/*
--- © Juan David Avellaneda Molina - UNAD - 2026 ---
--- juand.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 3.2.5 martes, 15 de septiembre de 2026
--- 2955 visa55sistema
*/
/** Archivo lib2955.php.
 * Libreria 2955 visa55sistema.
 * @author Juan David Avellaneda Molina - juand.avellaneda@unad.edu.co
 * @date martes, 15 de septiembre de 2026
 */
function f2955_NombreTabla() {
	return 'visa55sistema';
}
function f2955_HTMLComboV2_visa55idproyecto($objDB, $objCombos, $valor)
{
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$objCombos->nuevo('visa55idproyecto', $valor, true, '{' . $ETI['msg_seleccione'] . '}');
	//$objCombos->iAncho = 450;
	//$objCombos->bEsCombobox = true;
	$objCombos->sAccion = 'RevisaLlave();';
	$sSQL = 'SELECT TB.plan04id AS id, TB.plan04numero AS nombre 
	FROM plan04proyecto AS TB
	WHERE TB.plan04id>0
	ORDER BY TB.plan04numero';
	$res = $objCombos->html($sSQL, $objDB); //, 0, '', 'et', 2955, $sIdioma
	return $res;
}
function f2955_ExisteDato($datos)
{
	if (!is_array($datos)) {
		$datos = json_decode(str_replace('\"', '"', $datos), true);
	}
	$_SESSION['u_ultimominuto'] = iminutoavance();
	$bHayLlave = true;
	$visa55idproyecto = numeros_validar($datos[1]);
	if ($visa55idproyecto == '') {
		$bHayLlave = false;
	}
	$visa55codigo = cadena_Validar($datos[2]);
	if ($visa55codigo == '') {
		$bHayLlave = false;
	}
	if ($bHayLlave) {
		require './app.php';
		$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto != '') {
			$objDB->dbPuerto = $APP->dbpuerto;
		}
		$objDB->xajax();
		$sNomTabla2955 = f2955_NombreTabla();
		$sSQL = 'SELECT 1 FROM ' . $sNomTabla2955 . ' WHERE visa55idproyecto=' . $visa55idproyecto . ' AND visa55codigo="' . $visa55codigo . '"';
		$res = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($res) == 0) {
			$bHayLlave = false;
		}
		$objDB->CerrarConexion();
		if ($bHayLlave) {
			$objResponse = new xajaxResponse();
			$objResponse->call('cambiapaginaV2');
			return $objResponse;
		}
	}
}
function f2955_Busquedas($aParametros)
{
	require './app.php';
	$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto != '') {
		$objDB->dbPuerto = $APP->dbpuerto;
	}
	$objDB->xajax();
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_2955 = 'lg/lg_2955_' . $sIdioma . '.php';
	if (!file_exists($mensajes_2955)) {
		$mensajes_2955 = 'lg/lg_2955_es.php';
	}
	require $mensajes_todas;
	require $mensajes_2955;
	if (!is_array($aParametros)) {
		$aParametros = json_decode(str_replace('\"', '"', $aParametros), true);
	}
	$sCampo = $aParametros[1];
	$sTitulo = ' {' . $sCampo . '}';
	if (isset($aParametros[2]) == 0) {
		$aParametros[2] = 0;
	}
	if (isset($aParametros[3]) == 0) {
		$aParametros[3] = 0;
	}
	$iPiel = iDefinirPiel($APP, 2);
	$sTituloModulo = $ETI['titulo_2955'];
	$sParams = '';
	$sTabla = '';
	$sJavaBusqueda = '';
	$aParametrosB = array();
	$aParametrosB[101] = 1;
	$aParametrosB[102] = 20;
	switch ($sCampo) {
		case 'visa55idresponsable':
			require $APP->rutacomun . 'lib111.php';
			$sTabla = f111_TablaDetalleBusquedas($aParametrosB, $objDB);
			if (isset($ETI['visa55idresponsable_busca']) == 0) {
				$ETI['visa55idresponsable_busca'] = 'Busqueda de Responsable';
			}
			$sTitulo = $ETI['visa55idresponsable_busca'];
			$sParams = f111_ParametrosBusqueda();
			$sJavaBusqueda = f111_JavaScriptBusqueda(2955);
			break;
	}
	$sTitulo = '<h2>' . $sTituloModulo . ' - ' . $sTitulo . '</h2>';
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_97titulo', 'innerHTML', $sTitulo);
	$objResponse->assign('div_97params', 'innerHTML', $sParams);
	$objResponse->assign('div_97tabla', 'innerHTML', $sTabla);
	$objResponse->setFunction('paginarbusqueda', '', $sJavaBusqueda);
	$objResponse->call('expandesector(97)');
	return $objResponse;
}
function f2955_HtmlBusqueda($aParametros)
{
	$_SESSION['u_ultimominuto'] = iminutoavance();
	if (!is_array($aParametros)) {
		$aParametros = json_decode(str_replace('\"', '"', $aParametros), true);
	}
	$sError = '';
	require './app.php';
	$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto != '') {
		$objDB->dbPuerto = $APP->dbpuerto;
	}
	$objDB->xajax();
	$sDetalle = '';
	switch ($aParametros[100]) {
		case 'visa55idresponsable':
			require $APP->rutacomun . 'lib111.php';
			$sDetalle = f111_TablaDetalleBusquedas($aParametros, $objDB);
			break;
	}
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_97tabla', 'innerHTML', $sDetalle);
	return $objResponse;
}
function f2955_TablaDetalleV2($aParametros, $objDB, $bDebug = false)
{
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
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
	require $mensajes_2955;
	if (!is_array($aParametros)) {
		$aParametros = json_decode(str_replace('\"', '"', $aParametros), true);
	}
	if (isset($aParametros[100]) == 0) {
		$aParametros[100] = $_SESSION['unad_id_tercero'];
	}
	if (isset($aParametros[101]) == 0) {
		$aParametros[101] = 1;
	}
	if (isset($aParametros[102]) == 0) {
		$aParametros[102] = 20;
	}
	$iNumVariables = 102;
	for ($k = 103; $k <= $iNumVariables; $k++) {
		if (isset($aParametros[$k]) == 0) {
			$aParametros[$k] = '';
		}
	}
	$idTercero = numeros_validar($aParametros[100]);
	$sDebug = '';
	// ------------------------------------------------
	// Leemos los parametros de entrada.
	// ------------------------------------------------
	$pagina = numeros_validar($aParametros[101]);
	$lineastabla = numeros_validar($aParametros[102]);
	//$bNombre = trim($aParametros[103]);
	//$bListar = numeros_validar($aParametros[104]);
	$sNomTabla2955 = f2955_NombreTabla();
	$sLeyenda = '';
	$sBotones = '<input id="paginaf2955" name="paginaf2955" type="hidden" value="' . $pagina . '"/>';
	$sBotones = $sBotones . '<input id="lppf2955" name="lppf2955" type="hidden" value="' . $lineastabla . '"/>';
	if ($sLeyenda != '') {
		$sRes = html_salto() . '<div class="GrupoCamposAyuda">' . $sLeyenda . html_salto() . '</div>';
		return array($sRes . $sBotones, $sDebug);
		die();
	}
	$bAbierta = true;
	/*
	$sSQL = 'SELECT Campo FROM Tabla WHERE Id=' . $sValorId;
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		if ($fila['Campo'] != 'S') {
			$bAbierta = true;
		}
	}
	*/
	$iPiel = iDefinirPiel($APP, 2);
	/*
	$aEstado = array('');
	$sSQL = 'SELECT id, nombre FROM tabla';
	$tabla = $objDB->ejecutasql($sSQL);
	while ($fila = $objDB->sf($tabla)) {
		$aEstado[$fila['id']] = cadena_notildes($fila['nombre']);
	}
	*/
	$sSQLadd = '';
	$sSQLadd1 = '';
	/*
	if ($aParametros[104] != '') {
		$sSQLadd = $sSQLadd . ' AND TB.campo2 LIKE "%' . $aParametros[104] . '%"';
	}
	if ($aParametros[104] != '') {
		$sSQLadd1 = $sSQLadd1 . 'TB.campo2 LIKE "%' . $aParametros[104] . '%" AND ';
	}
	if ($bNombre != '') {
		$sBase = mb_strtoupper($bNombre);
		$aNoms = explode(' ', $sBase);
		for ($k = 1; $k <= count($aNoms); $k++) {
			$sCadena = $aNoms[$k - 1];
			if ($sCadena != '') {
				$sSQLadd = $sSQLadd . ' AND T6.unad11razonsocial LIKE "%' . $sCadena . '%"';
				//$sSQLadd1 = $sSQLadd1 . 'TB.unad11razonsocial LIKE "%' . $sCadena . '%" AND ';
			}
		}
	}
	*/
	// ------------------------------------------------
	// Fin de las condiciones de la consulta
	// ------------------------------------------------
	$sTitulos = 'Id, Proyecto, Codigo, Responsable, Vigente, Fechaactualiza';
	$registros = 0;
	$bGigante = false; //En caso de que la tabla sea muy grande pasarlo a true
	$sLimite = '';
	$sCampos = 'SELECT TB.visa55id, T2.plan04numero, TB.visa55codigo, T4.unad11razonsocial AS C4_nombre, TB.visa55vigente, TB.visa55fechaactualiza, TB.visa55idproyecto, TB.visa55idresponsable, T4.unad11tipodoc AS C4_td, T4.unad11doc AS C4_doc';
	$sConsulta = 'FROM ' . $sNomTabla2955 . ' AS TB, plan04proyecto AS T2, unad11terceros AS T4 
	WHERE ' . $sSQLadd1 . ' TB.visa55id>0 AND TB.visa55idproyecto=T2.plan04id AND TB.visa55idresponsable=T4.unad11id ' . $sSQLadd . '';
	$sOrden = 'ORDER BY TB.visa55idproyecto, TB.visa55codigo';
	$sSQL = $sCampos . ' ' . $sConsulta . ' ' . $sOrden;
	// ------------------------------------------------
	// Fin de la consulta
	// ------------------------------------------------
	$sSQLlista = str_replace("'", "|", $sSQL);
	$sSQLlista = str_replace('"', "|", $sSQLlista);
	if ($bGigante) {
		$sSQLContador = 'SELECT COUNT(1) AS Total ' . $sConsulta . '';
		if ($bDebug) {
			$sDebug = $sDebug . log_debug(' Totalizando consulta 2955: ' . $sSQLContador . '');
		}
		$tabladetalle = $objDB->ejecutasql($sSQLContador);
		if ($objDB->nf($tabladetalle) > 0) {
			$fila = $objDB->sf($tabladetalle);
			$registros = $fila['Total'];
		}
		if ((($registros - 1) / $lineastabla) < ($pagina - 1)) {
			$pagina = (int)(($registros - 1) / $lineastabla) + 1;
		}
		if ($registros > $lineastabla) {
			$rbase = ($pagina - 1) * $lineastabla;
			$sSQL = $objDB->sSQLPaginar($sCampos, $sConsulta, $sOrden, $rbase, $lineastabla);
		}
	}
	$sErrConsulta = '<input id="consulta_2955" name="consulta_2955" type="hidden" value="' . $sSQLlista . '"/>';
	$sErrConsulta = $sErrConsulta . '<input id="titulos_2955" name="titulos_2955" type="hidden" value="' . $sTitulos . '"/>';
	if ($bDebug) {
		$sDebug = $sDebug . log_debug('Consulta 2955: ' . $sSQL . '');
	}
	$tabladetalle = $objDB->ejecutasql($sSQL);
	if ($tabladetalle == false) {
		$registros = 0;
		$sErrConsulta = $sErrConsulta . '..<input id="err" name="err" type="hidden" value="' . $sSQL . ' ' . $objDB->serror . '"/>';
		//$sLeyenda = $sSQL;
	} else {
		if (!$bGigante) {
			$registros = $objDB->nf($tabladetalle);
			/*
			if ($registros == 0) {
				return array($sErrConsulta . $sBotones, $sDebug);
			}
			*/
			if ((($registros - 1) / $lineastabla) < ($pagina - 1)) {
				$pagina = (int)(($registros - 1) / $lineastabla) + 1;
			}
			if ($registros > $lineastabla) {
				$rbase = ($pagina - 1) * $lineastabla;
				$sSQLLimitado = $objDB->sSQLPaginar($sCampos, $sConsulta, $sOrden, $rbase, $lineastabla);
				$tabladetalle = $objDB->ejecutasql($sSQLLimitado);
			}
		}
	}
	if ($bDebug) {
		$sDebug = $sDebug . log_debug('Termina la consulta 2955');
	}
	$res = $sErrConsulta . $sLeyenda;
	$sClaseTabla = 'table--primary';
	if ($iPiel == 1) {
		$sClaseTabla = 'tablaapp';
	}
	$res = $res . '<div class="table-responsive">';
	$res = $res . '<table border="0" align="center" cellpadding="0" cellspacing="2" class="' . $sClaseTabla . '">';
	$res = $res . '<thead class="fondoazul"><tr>';
	$res = $res . '<th><b>' . $ETI['visa55idproyecto'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['visa55codigo'] . '</b></th>';
	$res = $res . '<th colspan="2"><b>' . $ETI['visa55idresponsable'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['visa55vigente'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['visa55fechaactualiza'] . '</b></th>';
	$res = $res . '<th class="flex gap-1 justify-end">';
	$res = $res . html_paginador('paginaf2955', $registros, $lineastabla, $pagina, 'paginarf2955()');
	$res = $res . html_lpp('lppf2955', $lineastabla, 'paginarf2955()');
	$res = $res . '</th>';
	$res = $res . '</tr></thead><tbody>';
	$tlinea = 1;
	while ($filadet = $objDB->sf($tabladetalle)) {
		$sPrefijo = '';
		$sSufijo = '';
		$sClass = ' class="resaltetabla"';
		$sLink = '';
		if (false) {
			$sPrefijo = '<b>';
			$sSufijo = '</b>';
		}
		if (($tlinea % 2) != 0) {
			$sClass = '';
		}
		$tlinea++;
		$et_visa55idproyecto = $sPrefijo . cadena_notildes($filadet['plan04numero']) . $sSufijo;
		$et_visa55codigo = $sPrefijo . cadena_notildes($filadet['visa55codigo']) . $sSufijo;
		$et_visa55idresponsable_doc = '';
		$et_visa55idresponsable_nombre = '';
		if ($filadet['visa55idresponsable'] != 0) {
			$et_visa55idresponsable_doc = $sPrefijo . $filadet['C4_td'] . ' ' . $filadet['C4_doc'] . $sSufijo;
			$et_visa55idresponsable_nombre = $sPrefijo . cadena_notildes($filadet['C4_nombre']) . $sSufijo;
		}
		$et_visa55vigente = $sPrefijo . $ETI['si'] . $sSufijo;
		if ($filadet['visa55vigente'] == 0) {
			$et_visa55vigente = $sPrefijo . $ETI['no'] . $sSufijo;
		}
		$et_visa55fechaactualiza = '';
		if ($filadet['visa55fechaactualiza'] != 0) {
			$et_visa55fechaactualiza = $sPrefijo . fecha_desdenumero($filadet['visa55fechaactualiza']) . $sSufijo;
		}
		if ($bAbierta) {
			$sLink = '<a href="javascript:cargaridf2955(' . $filadet['visa55id'] . ')" class="lnkresalte">' . $ETI['lnk_cargar'] . '</a>';
		}
		$res = $res . '<tr' . $sClass . '>';
		$res = $res . '<td>' . $et_visa55idproyecto . '</td>';
		$res = $res . '<td>' . $et_visa55codigo . '</td>';
		$res = $res . '<td>' . $et_visa55idresponsable_doc . '</td>';
		$res = $res . '<td>' . $et_visa55idresponsable_nombre . '</td>';
		$res = $res . '<td>' . $et_visa55vigente . '</td>';
		$res = $res . '<td>' . $et_visa55fechaactualiza . '</td>';
		$res = $res . '<td align="right">' . $sLink . '</td>';
		$res = $res . '</tr>';
	}
	$res = $res . '</tbody></table>';
	$res = $res . '<div class="salto5px"></div>';
	$res = $res . '</div>';
	$objDB->liberar($tabladetalle);
	return array(cadena_codificar($res), $sDebug);
}
function f2955_HtmlTabla($aParametros)
{
	$_SESSION['u_ultimominuto'] = iminutoavance();
	$sError = '';
	$bDebug = false;
	$sDebug = '';
	$opts = $aParametros;
	if (!is_array($opts)) {
		$opts = json_decode(str_replace('\"', '"', $opts), true);
	}
	if (isset($opts[99]) != 0) {
		if ($opts[99] == 1) {
			$bDebug = true;
		}
	}
	require './app.php';
	$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto != '') {
		$objDB->dbPuerto = $APP->dbpuerto;
	}
	$objDB->xajax();
	list($sDetalle, $sDebugTabla) = f2955_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_f2955detalle', 'innerHTML', $sDetalle);
	if ($bDebug) {
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
	}
	return $objResponse;
}
function f2955_db_GuardarV2b($DATA, $objDB, $bDebug = false, $idTercero = 0, $iCodModulo = 2955)
{
	$iCodModuloAudita = 2955;
	$bAudita[2] = true;
	$bAudita[3] = true;
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_2955 = 'lg/lg_2955_' . $sIdioma . '.php';
	if (!file_exists($mensajes_2955)) {
		$mensajes_2955 = 'lg/lg_2955_es.php';
	}
	require $mensajes_todas;
	require $mensajes_2955;
	$sError = '';
	$iTipoError = 0;
	$sDebug = '';
	if ($idTercero == 0) {
		$idTercero = $_SESSION['unad_id_tercero'];
	}
	/*
	if (isset($DATA['visa55idproyecto']) == 0) {
		$DATA['visa55idproyecto'] = 0;
	}
	if (isset($DATA['visa55codigo']) == 0) {
		$DATA['visa55codigo'] = '';
	}
	if (isset($DATA['visa55id']) == 0) {
		$DATA['visa55id'] = '';
	}
	if (isset($DATA['visa55nombre']) == 0) {
		$DATA['visa55nombre'] = '';
	}
	if (isset($DATA['visa55descripcion']) == 0) {
		$DATA['visa55descripcion'] = '';
	}
	if (isset($DATA['visa55idresponsable']) == 0) {
		$DATA['visa55idresponsable'] = 0;
	}
	if (isset($DATA['visa55idresponsable_td']) == 0) {
		$DATA['visa55idresponsable_td'] = 'CC';
	}
	if (isset($DATA['visa55idresponsable_doc']) == 0) {
		$DATA['visa55idresponsable_doc'] = '';
	}
	if (isset($DATA['visa55vigente']) == 0) {
		$DATA['visa55vigente'] = 0;
	}
	if (isset($DATA['visa55color']) == 0) {
		$DATA['visa55color'] = 0;
	}
	if (isset($DATA['visa55fechacreacion']) == 0) {
		$DATA['visa55fechacreacion'] = 0;
	}
	if (isset($DATA['visa55fechaactualiza']) == 0) {
		$DATA['visa55fechaactualiza'] = 0;
	}
	*/
	// -- Se inicia validando todas las posibles entradas de usuario.
	$DATA['visa55idproyecto'] = numeros_validar($DATA['visa55idproyecto']);
	$DATA['visa55codigo'] = cadena_Validar(trim($DATA['visa55codigo']));
	$DATA['visa55nombre'] = cadena_Validar(trim($DATA['visa55nombre']));
	$DATA['visa55descripcion'] = cadena_Validar(trim($DATA['visa55descripcion']));
	$DATA['visa55idresponsable'] = numeros_validar($DATA['visa55idresponsable']);
	$DATA['visa55idresponsable_td'] = cadena_Validar($DATA['visa55idresponsable_td']);
	$DATA['visa55idresponsable_doc'] = cadena_Validar($DATA['visa55idresponsable_doc']);
	$DATA['visa55vigente'] = numeros_validar($DATA['visa55vigente']);
	$DATA['visa55color'] = numeros_validar($DATA['visa55color']);
	$DATA['visa55fechacreacion'] = numeros_validar($DATA['visa55fechacreacion']);
	$DATA['visa55fechaactualiza'] = numeros_validar($DATA['visa55fechaactualiza']);
	// -- Se inicializan las variables que puedan pasar vacias {Especialmente números}.
	/*
	if ($DATA['visa55vigente'] == '') {
		$DATA['visa55vigente'] = 0;
	}
	if ($DATA['visa55color'] == '') {
		$DATA['visa55color'] = 0;
	}
	if ($DATA['visa55fechacreacion'] == '') {
		$DATA['visa55fechacreacion'] = 0;
	}
	if ($DATA['visa55fechaactualiza'] == '') {
		$DATA['visa55fechaactualiza'] = 0;
	}
	*/
	// -- Seccion para validar los posibles causales de error.
	$sSepara = ', ';
	if (!fecha_NumValido($DATA['visa55fechaactualiza'])) {
		//$DATA['visa55fechaactualiza'] = fecha_DiaMod();
		$sError = $ERR['visa55fechaactualiza'] . $sSepara . $sError;
	}
	if (!fecha_NumValido($DATA['visa55fechacreacion'])) {
		//$DATA['visa55fechacreacion'] = fecha_DiaMod();
		$sError = $ERR['visa55fechacreacion'] . $sSepara . $sError;
	}
	if ($DATA['visa55color'] == '') {
		$sError = $ERR['visa55color'] . $sSepara . $sError;
	}
	if ($DATA['visa55vigente'] == '') {
		$sError = $ERR['visa55vigente'] . $sSepara . $sError;
	}
	if ($DATA['visa55idresponsable'] == 0) {
		$sError = $ERR['visa55idresponsable'] . $sSepara . $sError;
	}
	/*
	if ($DATA['visa55descripcion'] == '') {
		$sError = $ERR['visa55descripcion'] . $sSepara . $sError;
	}
	*/
	if ($DATA['visa55nombre'] == '') {
		$sError = $ERR['visa55nombre'] . $sSepara . $sError;
	}
	//Fin de las valiaciones NO LLAVE.
	//Valiaciones de campos obligatorios en todo guardar.
	if ($sError == '') {
		$aListaCampos = array('', 'visa55nombre', 'visa55codigo');
		$aLargoCampos = array(0, 100, 10);
		for ($k = 1; $k <= 2; $k++) {
			$iLargoCampo = strlen($DATA[$aListaCampos[$k]]);
			if ($iLargoCampo > $aLargoCampos[$k]) {
				$sError = $ETI['error_cadena_1'] . $ETI[$aListaCampos[$k]] . $ETI['error_cadena_2'] . ' [' . $iLargoCampo . '/' . $aLargoCampos[$k] . ']' . $sSepara . $sError;
			}
		}
	}
	if ($DATA['visa55codigo'] == '') {
		$sError = $ERR['visa55codigo'];
	}
	if ($DATA['visa55idproyecto'] == '') {
		$sError = $ERR['visa55idproyecto'];
	}
	// -- Se verifican los valores de campos de otras tablas.
	if ($sError == '') {
		if ($DATA['visa55idresponsable_doc'] != '') {
			$sError = tabla_terceros_existe($DATA['visa55idresponsable_td'], $DATA['visa55idresponsable_doc'], $objDB, 'El tercero Responsable ');
		}
		if ($sError == '') {
			list($sError, $sInfo) = tercero_Bloqueado($DATA['visa55idresponsable'], $objDB);
			if ($sInfo != '') {
				$sError = $sError . '<br>' . $sInfo;
			}
		}
	}
	$bQuitarCodigo = false;
	$sCampoCodigo = '';
	$sNomTabla2955 = f2955_NombreTabla();
	if ($sError == '') {
		if ($DATA['paso'] == 10) {
			$sSQL = 'SELECT 1 FROM ' . $sNomTabla2955 . ' WHERE visa55idproyecto=' . $DATA['visa55idproyecto'] . ' AND visa55codigo="' . $DATA['visa55codigo'] . '"';
			$result = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($result) != 0) {
				$sError = $ERR['existe'];
			} else {
				list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 2, $idTercero, $objDB);
				if (!$bDevuelve) {
					$sError = $ERR['2'] . ' [Mod ' . $iCodModulo . ']';
				}
			}
		} else {
			list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 3, $idTercero, $objDB);
			if (!$bDevuelve) {
				$sError = $ERR['3'] . ' [Mod ' . $iCodModulo . ']';
			}
		}
	}
	if ($sError == '') {
		if ($DATA['paso'] == 10) {
			//Preparar el Id, Si no lo hay se quita la comprobación.
			$DATA['visa55id'] = tabla_consecutivo($sNomTabla2955, 'visa55id', '', $objDB);
			if ($DATA['visa55id'] == -1) {
				$sError = $objDB->serror;
			}
			//Datos adicionales al iniciar un registro.
		}
	}
	if ($sError == '') {
		//$visa55descripcion = addslashes($DATA['visa55descripcion']);
		$visa55descripcion = str_replace('"', '\"', $DATA['visa55descripcion']);
		$bPasa = false;
		if ($DATA['paso'] == 10) {
			$sCampos2955 = 'visa55idproyecto, visa55codigo, visa55id, visa55nombre, visa55descripcion, 
			visa55idresponsable, visa55vigente, visa55color, visa55fechacreacion, visa55fechaactualiza';
			$sValores2955 = '' . $DATA['visa55idproyecto'] . ', "' . $DATA['visa55codigo'] . '", ' . $DATA['visa55id'] . ', "' . $DATA['visa55nombre'] . '", "' . $visa55descripcion . '", 
			' . $DATA['visa55idresponsable'] . ', ' . $DATA['visa55vigente'] . ', ' . $DATA['visa55color'] . ', ' . $DATA['visa55fechacreacion'] . ', ' . $DATA['visa55fechaactualiza'] . '';
			if ($APP->utf8 == 1) {
				$sSQL = 'INSERT INTO ' . $sNomTabla2955 . ' (' . $sCampos2955 . ') VALUES (' . cadena_codificar($sValores2955) . ');';
				$sDetalle = $sCampos2955 . '[' . cadena_codificar($sValores2955) . ']';
			} else {
				$sSQL = 'INSERT INTO ' . $sNomTabla2955 . ' (' . $sCampos2955 . ') VALUES (' . $sValores2955 . ');';
				$sDetalle = $sCampos2955 . '[' . $sValores2955 . ']';
			}
			$idAccion = 2;
			$bPasa = true;
		} else {
			$sCampo[1] = 'visa55nombre';
			$sCampo[2] = 'visa55descripcion';
			$sCampo[3] = 'visa55idresponsable';
			$sCampo[4] = 'visa55vigente';
			$sCampo[5] = 'visa55color';
			$sCampo[6] = 'visa55fechacreacion';
			$sCampo[7] = 'visa55fechaactualiza';
			$sDato[1] = $DATA['visa55nombre'];
			$sDato[2] = $visa55descripcion;
			$sDato[3] = $DATA['visa55idresponsable'];
			$sDato[4] = $DATA['visa55vigente'];
			$sDato[5] = $DATA['visa55color'];
			$sDato[6] = $DATA['visa55fechacreacion'];
			$sDato[7] = $DATA['visa55fechaactualiza'];
			$iNumCamposMod = 7;
			$sWhere = 'visa55id=' . $DATA['visa55id'] . '';
			$sSQL = 'SELECT * FROM ' . $sNomTabla2955 . ' WHERE ' . $sWhere;
			$sDatos = '';
			$bPrimera = true;
			$result = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($result) > 0) {
				$filabase = $objDB->sf($result);
				if ($bDebug && $bPrimera) {
					for ($k = 1; $k <= $iNumCamposMod; $k++) {
						if (isset($filabase[$sCampo[$k]]) == 0) {
							$sDebug = $sDebug . log_debug(' FALLA CODIGO: Falta el campo ' . $k . ' ' . $sCampo[$k] . '');
						}
					}
					$bPrimera = false;
				}
				$sSepara = '';
				for ($k = 1; $k <= $iNumCamposMod; $k++) {
					if ($filabase[$sCampo[$k]] != $sDato[$k]) {
						$sDatos = $sDatos . $sSepara . $sCampo[$k] . '="' . $sDato[$k] . '"';
						$bPasa = true;
						$sSepara = ', ';
					}
				}
			}
			if ($bPasa) {
				if ($APP->utf8 == 1) {
					$sDetalle = cadena_codificar($sDatos) . '[' . $sWhere . ']';
					$sSQL = 'UPDATE ' . $sNomTabla2955 . ' SET ' . cadena_codificar($sDatos) . ' WHERE ' . $sWhere . ';';
				} else {
					$sDetalle = $sDatos . '[' . $sWhere . ']';
					$sSQL = 'UPDATE ' . $sNomTabla2955 . ' SET ' . $sDatos . ' WHERE ' . $sWhere . ';';
				}
				$idAccion = 3;
			}
		}
		if ($bPasa) {
			if ($bDebug) {
				$sDebug = $sDebug . log_debug(' Guardar 2955 ' . $sSQL . '');
			}
			$result = $objDB->ejecutasql($sSQL);
			if ($result == false) {
				$sError = $ERR['falla_guardar'] . ' [2955] ..<!-- ' . $sSQL . ' -->';
				if ($idAccion == 2) {
					$DATA['visa55id'] = '';
					$bQuitarCodigo = true;
					$DATA['paso'] = 0;
				} else {
					$DATA['paso'] = 2;
				}
			} else {
				if ($bAudita[$idAccion]) {
					seg_auditar($iCodModuloAudita, $_SESSION['unad_id_tercero'], $idAccion, $DATA['visa55id'], $sDetalle, $objDB);
				}
				//Acciones en el insertar.
				$DATA['paso'] = 2;
			}
		} else {
			$DATA['paso'] = 2;
		}
	} else {
		if ($DATA['paso'] == 10) {
			$DATA['paso'] = 0;
		} else {
			$DATA['paso'] = 2;
		}
		if ($bQuitarCodigo) {
			if ($sCampoCodigo != '') {
				$DATA[$sCampoCodigo] = '';
			}
		}
	}
	/*
	if ($bDebug) {
		$sDebug = $sDebug . log_debug(' InfoDepura');
	}
	*/
	return array($DATA, $sError, $iTipoError, $sDebug);
}
function f2955_db_Eliminar($visa55id, $objDB, $bDebug = false)
{
	$iCodModulo = 2955;
	$bAudita[4] = true;
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_2955 = 'lg/lg_2955_' . $sIdioma . '.php';
	if (!file_exists($mensajes_2955)) {
		$mensajes_2955 = 'lg/lg_2955_es.php';
	}
	require $mensajes_todas;
	require $mensajes_2955;
	$sError = '';
	$iTipoError = 0;
	$sDebug = '';
	$visa55id = numeros_validar($visa55id);
	// Traer los datos para hacer las validaciones.
	if ($sError == '') {
		$sNomTabla2955 = f2955_NombreTabla();
		$sSQL = 'SELECT * FROM ' . $sNomTabla2955 . ' WHERE visa55id=' . $visa55id . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$filabase = $objDB->sf($tabla);
		} else {
			$sError = 'No se encuentra el registro solicitado {Ref: ' . $visa55id . '}';
		}
	}
	if ($sError == '') {
		if (isset($idTercero) == 0) {
			$idTercero = $_SESSION['unad_id_tercero'];
		}
		list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 4, $idTercero, $objDB);
		if (!$bDevuelve) {
			$sError = $ERR['4'] . ' [Mod ' . $iCodModulo . ']';
		}
	}
	if ($sError == '') {
		$sSQL = 'SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=2955';
		$tablaor = $objDB->ejecutasql($sSQL);
		while ($filaor = $objDB->sf($tablaor)) {
			$sSQL = 'SELECT ' . $filaor['campoid'] . ' FROM ' . $filaor['tabla'] . ' WHERE ' . $filaor['camporev'] . '=' . $_REQUEST['visa55id'] . ' LIMIT 0, 1';
			$tabla = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla) > 0) {
				$sError = $filaor['mensaje'];
				if ($filaor['etiqueta'] != '') {
					if (isset($ERR[$filaor['etiqueta']]) != 0) {
						$sError = $ERR[$filaor['etiqueta']];
					}
				}
				break;
			}
		}
	}
	if ($sError == '') {
		$sWhere = 'visa55id=' . $visa55id . '';
		//$sWhere = 'visa55codigo="' . $filabase['visa55codigo'] . '" AND visa55idproyecto=' . $filabase['visa55idproyecto'] . '';
		$sSQL = 'DELETE FROM ' . $sNomTabla2955 . ' WHERE ' . $sWhere . ';';
		$result = $objDB->ejecutasql($sSQL);
		if ($result == false) {
			$sError = $ERR['falla_eliminar'] . ' .. <!-- ' . $sSQL . ' -->';
		} else {
			if ($bAudita[4]) {
				seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $visa55id, $sWhere, $objDB);
			}
		}
	}
	return array($sError, $iTipoError, $sDebug);
}

// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------

