<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2025 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 3.0.16 jueves, 10 de julio de 2025
--- 3074 saiu74reddeservicio
*/
/** Archivo lib3074.php.
 * Libreria 3074 saiu74reddeservicio.
 * @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
 * @date jueves, 10 de julio de 2025
 */
function f3074_HTMLComboV2_saiu74idescuela($objDB, $objCombos, $valor)
{
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$objCombos->nuevo('saiu74idescuela', $valor, true, '{' . $ETI['msg_seleccione'] . '}');
	$objCombos->addItem('0', '{' . $ETI['msg_todas'] . '}');
	//$objCombos->iAncho = 450;
	$objCombos->sAccion = 'RevisaLlave();';
	$sSQL = 'SELECT TB.core12id AS id, TB.core12sigla AS nombre 
	FROM core12escuela AS TB
	WHERE core12id>0 AND core12tieneestudiantes="S" 
	ORDER BY TB.core12nombre';
	$res = $objCombos->html($sSQL, $objDB);
	return $res;
}
function f3074_ExisteDato($datos)
{
	if (!is_array($datos)) {
		$datos = json_decode(str_replace('\"', '"', $datos), true);
	}
	$_SESSION['u_ultimominuto'] = iminutoavance();
	$bHayLlave = true;
	$saiu74consec = numeros_validar($datos[1]);
	if ($saiu74consec == '') {
		$bHayLlave = false;
	}
	$saiu74idescuela = numeros_validar($datos[2]);
	if ($saiu74idescuela == '') {
		$bHayLlave = false;
	}
	if ($bHayLlave) {
		require './app.php';
		$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto != '') {
			$objDB->dbPuerto = $APP->dbpuerto;
		}
		$objDB->xajax();
		$sSQL = 'SELECT 1 FROM saiu74reddeservicio WHERE saiu74consec=' . $saiu74consec . ' AND saiu74idescuela=' . $saiu74idescuela . '';
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
function f3074_Busquedas($aParametros)
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
	$mensajes_3074 = 'lg/lg_3074_' . $sIdioma . '.php';
	if (!file_exists($mensajes_3074)) {
		$mensajes_3074 = 'lg/lg_3074_es.php';
	}
	require $mensajes_todas;
	require $mensajes_3074;
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
	$sTituloModulo = $ETI['titulo_3074'];
	$sParams = '';
	$sTabla = '';
	$sJavaBusqueda = '';
	$aParametrosB = array();
	$aParametrosB[101] = 1;
	$aParametrosB[102] = 20;
	switch ($sCampo) {
		case 'saiu74idadministrador':
			require $APP->rutacomun . 'lib111.php';
			$sTabla = f111_TablaDetalleBusquedas($aParametrosB, $objDB);
			if (isset($ETI['saiu74idadministrador_busca']) == 0) {
				$ETI['saiu74idadministrador_busca'] = 'Busqueda de Administrador';
			}
			$sTitulo = $ETI['saiu74idadministrador_busca'];
			$sParams = f111_ParametrosBusqueda();
			$sJavaBusqueda = f111_JavaScriptBusqueda(3074);
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
function f3074_HtmlBusqueda($aParametros)
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
		case 'saiu74idadministrador':
			require $APP->rutacomun . 'lib111.php';
			$sDetalle = f111_TablaDetalleBusquedas($aParametros, $objDB);
			break;
	}
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_97tabla', 'innerHTML', $sDetalle);
	return $objResponse;
}
function f3074_TablaDetalleV2($aParametros, $objDB, $bDebug = false)
{
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	/*
	$mensajes_3000 = 'lg/lg_3000_' . $sIdioma . '.php';
	if (!file_exists($mensajes_3000)) {
		$mensajes_3000 = 'lg/lg_3000_es.php';
	}
	require $mensajes_3000;
	*/
	$mensajes_3074 = 'lg/lg_3074_' . $sIdioma . '.php';
	if (!file_exists($mensajes_3074)) {
		$mensajes_3074 = 'lg/lg_3074_es.php';
	}
	require $mensajes_3074;
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
	$iNumVariables = 104;
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
	$bNombre = cadena_Validar(trim($aParametros[103]));
	$blistar = numeros_validar($aParametros[104]);
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
	$sLeyenda = '';
	$sBotones = '<input id="paginaf3074" name="paginaf3074" type="hidden" value="' . $pagina . '"/>';
	$sBotones = $sBotones . '<input id="lppf3074" name="lppf3074" type="hidden" value="' . $lineastabla . '"/>';
	if ($sLeyenda != '') {
		$sRes = html_salto() . '<div class="GrupoCamposAyuda">' . $sLeyenda . html_salto() . '</div>';
		return array($sRes . $sBotones, $sDebug);
		die();
	}
	$iPiel = iDefinirPiel($APP, 2);
	$aEscuela = array('');
	$sSQL = 'SELECT core12id, core12sigla FROM core12escuela';
	$tabla = $objDB->ejecutasql($sSQL);
	while ($fila = $objDB->sf($tabla)) {
		$aEscuela[$fila['core12id']] = cadena_notildes($fila['core12sigla']);
	}
	$sSQLadd = '';
	$sSQLadd1 = '';
	switch ($blistar) {
		case 1: // Las que tengo
			$sSQLadd1 = $sSQLadd1 . 'TB.saiu74idadministrador=' . $idTercero . ' AND ';
			break;
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
	// ------------------------------------------------
	// Fin de las condiciones de la consulta
	// ------------------------------------------------
	$sTitulos = 'Consec, Id, Activa, Nombre, Unidad, Administrador';
	$registros = 0;
	$bGigante = false; //En caso de que la tabla sea muy grande pasarlo a true
	$sLimite = '';
	$sCampos = 'SELECT TB.saiu74consec, TB.saiu74idescuela, TB.saiu74id, TB.saiu74activa, TB.saiu74nombre, T6.unad11razonsocial AS C6_nombre, 
	TB.saiu74idunidad, TB.saiu74idadministrador, T6.unad11tipodoc AS C6_td, T6.unad11doc AS C6_doc';
	$sConsulta = 'FROM saiu74reddeservicio AS TB, unad11terceros AS T6 
	WHERE ' . $sSQLadd1 . ' TB.saiu74id>0 AND TB.saiu74idadministrador=T6.unad11id ' . $sSQLadd . '';
	$sOrden = 'ORDER BY TB.saiu74nombre';
	$sSQL = $sCampos . ' ' . $sConsulta . ' ' . $sOrden;
	// ------------------------------------------------
	// Fin de la consulta
	// ------------------------------------------------
	$sSQLlista = str_replace("'", "|", $sSQL);
	$sSQLlista = str_replace('"', "|", $sSQLlista);
	if ($bGigante) {
		$sSQLContador = 'SELECT COUNT(1) AS Total ' . $sConsulta . '';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' Totalizando consulta 3074: ' . $sSQLContador . '<br>';
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
	$sErrConsulta = '<input id="consulta_3074" name="consulta_3074" type="hidden" value="' . $sSQLlista . '"/>';
	$sErrConsulta = $sErrConsulta . '<input id="titulos_3074" name="titulos_3074" type="hidden" value="' . $sTitulos . '"/>';
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' Consulta 3074: ' . $sSQL . '<br>';
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
		$sDebug = $sDebug . fecha_microtiempo() . ' Termina la consulta 3074<br>';
	}
	$res = $sErrConsulta . $sLeyenda;
	$sClaseTabla = 'table--primary';
	if ($iPiel == 1) {
		$sClaseTabla = 'tablaapp';
	}
	$res = $res . '<div class="table-responsive">';
	$res = $res . '<table border="0" align="center" cellpadding="0" cellspacing="2" class="' . $sClaseTabla . '">';
	$res = $res . '<thead class="fondoazul"><tr>';
	$res = $res . '<th><b>' . $ETI['saiu74consec'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['saiu74idescuela'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['saiu74activa'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['saiu74nombre'] . '</b></th>';
	$res = $res . '<th colspan="2"><b>' . $ETI['saiu74idadministrador'] . '</b></th>';
	$res = $res . '<th class="flex gap-1 justify-end">';
	$res = $res . html_paginador('paginaf3074', $registros, $lineastabla, $pagina, 'paginarf3074()');
	$res = $res . html_lpp('lppf3074', $lineastabla, 'paginarf3074()');
	$res = $res . '</th>';
	$res = $res . '</tr></thead><tbody>';
	$tlinea = 1;
	//$iEscuela = -99;
	while ($filadet = $objDB->sf($tabladetalle)) {
		/*
		if ($iEscuela != $filadet['escuela']) {
			$iEscuela = $filadet['escuela'];
			$sNomEscuela = '{' . $filadet['escuela'] . '}';
			//list($sNomEscuela) = f2212_NombreEscuela($idEscuela, $objDB);
			$res = $res . '<tr class="fondoazul">';
			$res = $res . '<td colspan="5" align="center">' . $ETI['msg_escuela'] . ' <b>' . $sNomEscuela . '</b></td>';
			$res = $res . '</tr>';
		}
		*/
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
		$et_saiu74consec = $sPrefijo . $filadet['saiu74consec'] . $sSufijo;
		$et_saiu74idescuela = $sPrefijo . $aEscuela[$filadet['saiu74idescuela']] . $sSufijo;
		$et_saiu74activa = $sPrefijo . $ETI['si'] . $sSufijo;
		if ($filadet['saiu74activa'] == 0) {
			$et_saiu74activa = $sPrefijo . $ETI['no'] . $sSufijo;
		}
		$et_saiu74nombre = $sPrefijo . cadena_notildes($filadet['saiu74nombre']) . $sSufijo;
		$et_saiu74idadministrador_doc = '';
		$et_saiu74idadministrador_nombre = '';
		if ($filadet['saiu74idadministrador'] != 0) {
			$et_saiu74idadministrador_doc = $sPrefijo . $filadet['C6_td'] . ' ' . $filadet['C6_doc'] . $sSufijo;
			$et_saiu74idadministrador_nombre = $sPrefijo . cadena_notildes($filadet['C6_nombre']) . $sSufijo;
		}
		if ($bAbierta) {
			$sLink = '<a href="javascript:cargaridf3074(' . $filadet['saiu74id'] . ')" class="lnkresalte">' . $ETI['lnk_cargar'] . '</a>';
		}
		$res = $res . '<tr' . $sClass . '>';
		$res = $res . '<td>' . $et_saiu74consec . '</td>';
		$res = $res . '<td>' . $et_saiu74idescuela . '</td>';
		$res = $res . '<td>' . $et_saiu74activa . '</td>';
		$res = $res . '<td>' . $et_saiu74nombre . '</td>';
		$res = $res . '<td>' . $et_saiu74idadministrador_doc . '</td>';
		$res = $res . '<td>' . $et_saiu74idadministrador_nombre . '</td>';
		$res = $res . '<td align="right">' . $sLink . '</td>';
		$res = $res . '</tr>';
	}
	$res = $res . '</tbody></table>';
	$res = $res . '<div class="salto5px"></div>';
	$res = $res . '</div>';
	$objDB->liberar($tabladetalle);
	return array(cadena_codificar($res), $sDebug);
}
function f3074_HtmlTabla($aParametros)
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
	list($sDetalle, $sDebugTabla) = f3074_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_f3074detalle', 'innerHTML', $sDetalle);
	if ($bDebug) {
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
	}
	return $objResponse;
}
function f3074_db_CargarPadre($DATA, $objDB, $bDebug = false)
{
	$sError = '';
	$iTipoError = 0;
	$sDebug = '';
	require './app.php';
	$DATA['saiu74idadministrador_td'] = $APP->tipo_doc;
	$DATA['saiu74idadministrador_doc'] = '';
	if ($DATA['paso'] == 1) {
		$sSQLcondi = 'saiu74consec=' . $DATA['saiu74consec'] . ' AND saiu74idescuela=' . $DATA['saiu74idescuela'] . '';
	} else {
		$sSQLcondi = 'saiu74id=' . $DATA['saiu74id'] . '';
	}
	$sSQL = 'SELECT * FROM saiu74reddeservicio WHERE ' . $sSQLcondi;
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$DATA['saiu74consec'] = $fila['saiu74consec'];
		$DATA['saiu74idescuela'] = $fila['saiu74idescuela'];
		$DATA['saiu74id'] = $fila['saiu74id'];
		$DATA['saiu74activa'] = $fila['saiu74activa'];
		$DATA['saiu74nombre'] = $fila['saiu74nombre'];
		$DATA['saiu74idunidad'] = $fila['saiu74idunidad'];
		$DATA['saiu74idadministrador'] = $fila['saiu74idadministrador'];
		$bcargo = true;
		$DATA['paso'] = 2;
		$DATA['boculta3074'] = 0;
		$bLimpiaHijos = true;
	} else {
		$DATA['paso'] = 0;
	}
	return array($DATA, $sError, $iTipoError, $sDebug);
}
function f3074_db_GuardarV2($DATA, $objDB, $bDebug = false, $idTercero = 0, $iCodModulo = 3074)
{
	$bAudita[2] = true;
	$bAudita[3] = true;
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_3074 = 'lg/lg_3074_' . $sIdioma . '.php';
	if (!file_exists($mensajes_3074)) {
		$mensajes_3074 = 'lg/lg_3074_es.php';
	}
	require $mensajes_todas;
	require $mensajes_3074;
	$sError = '';
	$iTipoError = 0;
	$sDebug = '';
	if ($idTercero == 0) {
		$idTercero = $_SESSION['unad_id_tercero'];
	}
	/*
	if (isset($DATA['saiu74consec']) == 0) {
		$DATA['saiu74consec'] = '';
	}
	if (isset($DATA['saiu74idescuela']) == 0) {
		$DATA['saiu74idescuela'] = 0;
	}
	if (isset($DATA['saiu74id']) == 0) {
		$DATA['saiu74id'] = '';
	}
	if (isset($DATA['saiu74activa']) == 0) {
		$DATA['saiu74activa'] = 0;
	}
	if (isset($DATA['saiu74nombre']) == 0) {
		$DATA['saiu74nombre'] = '';
	}
	if (isset($DATA['saiu74idunidad']) == 0) {
		$DATA['saiu74idunidad'] = 0;
	}
	if (isset($DATA['saiu74idadministrador']) == 0) {
		$DATA['saiu74idadministrador'] = '';
	}
	*/
	// -- Se inicia validando todas las posibles entradas de usuario.
	$DATA['saiu74consec'] = numeros_validar($DATA['saiu74consec']);
	$DATA['saiu74idescuela'] = numeros_validar($DATA['saiu74idescuela']);
	$DATA['saiu74activa'] = numeros_validar($DATA['saiu74activa']);
	$DATA['saiu74nombre'] = cadena_Validar(trim($DATA['saiu74nombre']));
	$DATA['saiu74idunidad'] = numeros_validar($DATA['saiu74idunidad']);
	$DATA['saiu74idadministrador'] = numeros_validar($DATA['saiu74idadministrador']);
	$DATA['saiu74idadministrador_td'] = cadena_Validar($DATA['saiu74idadministrador_td']);
	$DATA['saiu74idadministrador_doc'] = cadena_Validar($DATA['saiu74idadministrador_doc']);
	// -- Se inicializan las variables que puedan pasar vacias {Especialmente números}.
	/*
	if ($DATA['saiu74activa'] == '') {
		$DATA['saiu74activa'] = 0;
	}
	if ($DATA['saiu74idunidad'] == '') {
		$DATA['saiu74idunidad'] = 0;
	}
	*/
	// -- Seccion para validar los posibles causales de error.
	$sSepara = ', ';
	if (true) {
		if ($DATA['saiu74idadministrador'] == 0) {
			$sError = $ERR['saiu74idadministrador'] . $sSepara . $sError;
		}
		if ($DATA['saiu74idunidad'] == '') {
			$sError = $ERR['saiu74idunidad'] . $sSepara . $sError;
		}
		if ($DATA['saiu74nombre'] == '') {
			$sError = $ERR['saiu74nombre'] . $sSepara . $sError;
		}
		if ($DATA['saiu74activa'] == '') {
			$sError = $ERR['saiu74activa'] . $sSepara . $sError;
		}
		if ($DATA['saiu74consec'] == '') {
			$sError = $ERR['saiu74consec'] . $sSepara . $sError;
		}
		//Fin de las valiaciones NO LLAVE.
	}
	//Valiaciones de campos obligatorios en todo guardar.
	if ($sError == '') {
		$aListaCampos = array('', 'saiu74nombre');
		$aLargoCampos = array(0, 100);
		for ($k = 1; $k <= 1; $k++) {
			$iLargoCampo = strlen($DATA[$aListaCampos[$k]]);
			if ($iLargoCampo > $aLargoCampos[$k]) {
				$sError = $ETI['error_cadena_1'] . $ETI[$aListaCampos[$k]] . $ETI['error_cadena_2'] . ' [' . $iLargoCampo . '/' . $aLargoCampos[$k] . ']' . $sSepara . $sError;
			}
		}
	}
	if ($DATA['saiu74idescuela'] == '') {
		$sError = $ERR['saiu74idescuela'];
	}
	// -- Se verifican los valores de campos de otras tablas.
	if ($sError == '') {
		if ($DATA['saiu74idadministrador_doc'] != '') {
			$sError = tabla_terceros_existe($DATA['saiu74idadministrador_td'], $DATA['saiu74idadministrador_doc'], $objDB, 'El tercero Administrador ');
		}
		if ($sError == '') {
			list($sError, $sInfo) = tercero_Bloqueado($DATA['saiu74idadministrador'], $objDB);
			if ($sInfo != '') {
				$sError = $sError . '<br>' . $sInfo;
			}
		}
	}
	$bQuitarCodigo = false;
	$sCampoCodigo = '';
	if ($sError == '') {
		if ($DATA['paso'] == 10) {
			/*
			if ($DATA['saiu74consec'] == '') {
				$DATA['saiu74consec'] = tabla_consecutivo('saiu74reddeservicio', 'saiu74consec', 'saiu74idescuela=' . $DATA['saiu74idescuela'] . '', $objDB);
				if ($DATA['saiu74consec'] == -1) {
					$sError = $objDB->serror;
				}
				$bQuitarCodigo = true;
				$sCampoCodigo = 'saiu74consec';
			
			} else {
				list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 8, $idTercero, $objDB);
				if (!$bDevuelve) {
					$sError = $ERR['8'];
					$DATA['saiu74consec'] = '';
				}
			}
			*/
			if ($sError == '') {
				$sSQL = 'SELECT 1 FROM saiu74reddeservicio WHERE saiu74consec=' . $DATA['saiu74consec'] . ' AND saiu74idescuela=' . $DATA['saiu74idescuela'] . '';
				$result = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($result) != 0) {
					$sError = $ERR['existe'];
				} else {
					list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 2, $idTercero, $objDB);
					if (!$bDevuelve) {
						$sError = $ERR['2'] . ' [Mod ' . $iCodModulo . ']';
					}
				}
			}
		} else {
			list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 3, $idTercero, $objDB);
			if (!$bDevuelve) {
				$sError = $ERR['3'];
			}
		}
	}
	if ($sError == '') {
		if ($DATA['paso'] == 10) {
			//Preparar el Id, Si no lo hay se quita la comprobación.
			$DATA['saiu74id'] = tabla_consecutivo('saiu74reddeservicio', 'saiu74id', '', $objDB);
			if ($DATA['saiu74id'] == -1) {
				$sError = $objDB->serror;
			}
			//Datos adicionales al iniciar un registro.
		}
	}
	if ($sError == '') {
		$bPasa = false;
		if ($DATA['paso'] == 10) {
			$sCampos3074 = 'saiu74consec, saiu74idescuela, saiu74id, saiu74activa, saiu74nombre, 
			saiu74idunidad, saiu74idadministrador';
			$sValores3074 = '' . $DATA['saiu74consec'] . ', ' . $DATA['saiu74idescuela'] . ', ' . $DATA['saiu74id'] . ', ' . $DATA['saiu74activa'] . ', "' . $DATA['saiu74nombre'] . '", 
			' . $DATA['saiu74idunidad'] . ', ' . $DATA['saiu74idadministrador'] . '';
			if ($APP->utf8 == 1) {
				$sSQL = 'INSERT INTO saiu74reddeservicio (' . $sCampos3074 . ') VALUES (' . cadena_codificar($sValores3074) . ');';
				$sdetalle = $sCampos3074 . '[' . cadena_codificar($sValores3074) . ']';
			} else {
				$sSQL = 'INSERT INTO saiu74reddeservicio (' . $sCampos3074 . ') VALUES (' . $sValores3074 . ');';
				$sdetalle = $sCampos3074 . '[' . $sValores3074 . ']';
			}
			$idAccion = 2;
			$bPasa = true;
		} else {
			$scampo[1] = 'saiu74activa';
			$scampo[2] = 'saiu74nombre';
			$scampo[3] = 'saiu74idunidad';
			$scampo[4] = 'saiu74idadministrador';
			$sdato[1] = $DATA['saiu74activa'];
			$sdato[2] = $DATA['saiu74nombre'];
			$sdato[3] = $DATA['saiu74idunidad'];
			$sdato[4] = $DATA['saiu74idadministrador'];
			$iNumCamposMod = 4;
			$sWhere = 'saiu74id=' . $DATA['saiu74id'] . '';
			$sSQL = 'SELECT * FROM saiu74reddeservicio WHERE ' . $sWhere;
			$sdatos = '';
			$bPrimera = true;
			$result = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($result) > 0) {
				$filabase = $objDB->sf($result);
				if ($bDebug && $bPrimera) {
					for ($k = 1; $k <= $iNumCamposMod; $k++) {
						if (isset($filabase[$scampo[$k]]) == 0) {
							$sDebug = $sDebug . fecha_microtiempo() . ' FALLA CODIGO: Falta el campo ' . $k . ' ' . $scampo[$k] . '<br>';
						}
					}
					$bPrimera = false;
				}
				$bsepara = false;
				for ($k = 1; $k <= $iNumCamposMod; $k++) {
					if ($filabase[$scampo[$k]] != $sdato[$k]) {
						if ($sdatos != '') {
							$sdatos = $sdatos . ', ';
						}
						$sdatos = $sdatos . $scampo[$k] . '="' . $sdato[$k] . '"';
						$bPasa = true;
					}
				}
			}
			if ($bPasa) {
				if ($APP->utf8 == 1) {
					$sdetalle = cadena_codificar($sdatos) . '[' . $sWhere . ']';
					$sSQL = 'UPDATE saiu74reddeservicio SET ' . cadena_codificar($sdatos) . ' WHERE ' . $sWhere . ';';
				} else {
					$sdetalle = $sdatos . '[' . $sWhere . ']';
					$sSQL = 'UPDATE saiu74reddeservicio SET ' . $sdatos . ' WHERE ' . $sWhere . ';';
				}
				$idAccion = 3;
			}
		}
		if ($bPasa) {
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . ' Guardar 3074 ' . $sSQL . '<br>';
			}
			$result = $objDB->ejecutasql($sSQL);
			if ($result == false) {
				$sError = $ERR['falla_guardar'] . ' [3074] ..<!-- ' . $sSQL . ' -->';
				if ($idAccion == 2) {
					$DATA['saiu74id'] = '';
					$bQuitarCodigo = true;
					$DATA['paso'] = 0;
				} else {
					$DATA['paso'] = 2;
				}
			} else {
				if ($bAudita[$idAccion]) {
					seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], $idAccion, $DATA['saiu74id'], $sdetalle, $objDB);
				}
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
		$sDebug = $sDebug . fecha_microtiempo() . ' InfoDepura<br>';
	}
	*/
	return array($DATA, $sError, $iTipoError, $sDebug);
}
function f3074_db_Eliminar($saiu74id, $objDB, $bDebug = false)
{
	$iCodModulo = 3074;
	$bAudita[4] = true;
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_3074 = 'lg/lg_3074_' . $sIdioma . '.php';
	if (!file_exists($mensajes_3074)) {
		$mensajes_3074 = 'lg/lg_3074_es.php';
	}
	require $mensajes_todas;
	require $mensajes_3074;
	$sError = '';
	$iTipoError = 0;
	$sDebug = '';
	$saiu74id = numeros_validar($saiu74id);
	// Traer los datos para hacer las validaciones.
	if ($sError == '') {
		$sSQL = 'SELECT * FROM saiu74reddeservicio WHERE saiu74id=' . $saiu74id . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$filabase = $objDB->sf($tabla);
		} else {
			$sError = 'No se encuentra el registro solicitado {Ref: ' . $saiu74id . '}';
		}
	}
	if ($sError == '') {
		$sSQL = 'SELECT 1 FROM saiu75redequipo WHERE saiu75idred=' . $filabase['saiu74id'] . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$sError = 'Existen Red de servicio - equipos creados, no es posible eliminar';
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
		$sSQL = 'SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=3074';
		$tablaor = $objDB->ejecutasql($sSQL);
		while ($filaor = $objDB->sf($tablaor)) {
			$sSQL = 'SELECT ' . $filaor['campoid'] . ' FROM ' . $filaor['tabla'] . ' WHERE ' . $filaor['camporev'] . '=' . $_REQUEST['saiu74id'] . ' LIMIT 0, 1';
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
		//$sSQL = 'DELETE FROM saiu75redequipo WHERE saiu75idred=' . $filabase['saiu74id'] . '';
		//$tabla = $objDB->ejecutasql($sSQL);
		$sWhere = 'saiu74id=' . $saiu74id . '';
		//$sWhere = 'saiu74idescuela=' . $filabase['saiu74idescuela'] . ' AND saiu74consec=' . $filabase['saiu74consec'] . '';
		$sSQL = 'DELETE FROM saiu74reddeservicio WHERE ' . $sWhere . ';';
		$result = $objDB->ejecutasql($sSQL);
		if ($result == false) {
			$sError = $ERR['falla_eliminar'] . ' .. <!-- ' . $sSQL . ' -->';
		} else {
			if ($bAudita[4]) {
				seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $saiu74id, $sWhere, $objDB);
			}
		}
	}
	return array($sError, $iTipoError, $sDebug);
}

// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------

