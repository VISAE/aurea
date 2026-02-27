<?php
/*
--- © Omar Augusto Bautista - UNAD - 2026 ---
--- omar.bautista@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 3.1.5 viernes, 27 de febrero de 2026
--- 2935 visa35convocatoria
*/
/** Archivo lib2935.php.
 * Libreria 2935 visa35convocatoria.
 * @author Omar Augusto Bautista - omar.bautista@unad.edu.co
 * @date viernes, 27 de febrero de 2026
 */
function f2935_HTMLComboV2_visa35idcentro($objDB, $objCombos, $valor, $vrvisa35idzona)
{
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$objCombos->nuevo('visa35idcentro', $valor, true, '{' . $ETI['msg_seleccione'] . '}');
	//$objCombos->iAncho = 450;
	$sSQL = '';
	if ((int)$vrvisa35idzona != 0) {
		//$objCombos->addItem('0', '[Sin Dato]');
		$sSQL = 'SELECT TB.unad24id AS id, TB.unad24nombre AS nombre 
		FROM unad24sede AS TB
		WHERE TB.unad24idzona=' . $vrvisa35idzona . ' 
		ORDER BY TB.unad24nombre';
	}
	$res = $objCombos->html($sSQL, $objDB); //, 0, '', 'et', 2935, $sIdioma
	return $res;
}
function f2935_HTMLComboV2_visa35idprograma($objDB, $objCombos, $valor, $vrvisa35idescuela)
{
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$objCombos->nuevo('visa35idprograma', $valor, true, '{' . $ETI['msg_seleccione'] . '}');
	//$objCombos->iAncho = 450;
	$sSQL = '';
	if ((int)$vrvisa35idescuela != 0) {
		//$objCombos->addItem('0', '[Sin Dato]');
		$sSQL = 'SELECT TB.core09id AS id, TB.core09nombre AS nombre 
		FROM core09programa AS TB
		WHERE TB.core09idescuela=' . $vrvisa35idescuela . ' 
		ORDER BY TB.core09nombre';
	}
	$res = $objCombos->html($sSQL, $objDB); //, 0, '', 'et', 2935, $sIdioma
	return $res;
}
function f2935_HTMLComboV2_visa35nivelforma($objDB, $objCombos, $valor, $vrvisa35gruponivel)
{
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$objCombos->nuevo('visa35nivelforma', $valor, true, '{' . $ETI['msg_seleccione'] . '}');
	//$objCombos->iAncho = 450;
	$sSQL = '';
	if ((int)$vrvisa35gruponivel != 0) {
		//$objCombos->addItem('0', '[Sin Dato]');
		$sSQL = 'SELECT TB.core22id AS id, TB.core22nombre AS nombre 
		FROM core22nivelprograma AS TB
		WHERE TB.core22grupo=' . $vrvisa35gruponivel . ' 
		ORDER BY TB.core22nombre';
	}
	$res = $objCombos->html($sSQL, $objDB); //, 0, '', 'et', 2935, $sIdioma
	return $res;
}
function f2935_Combovisa35idcentro($aParametros)
{
	$_SESSION['u_ultimominuto'] = iminutoavance();
	if (!is_array($aParametros)) {
		$aParametros = json_decode(str_replace('\"', '"', $aParametros), true);
	}
	require './app.php';
	$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto != '') {
		$objDB->dbPuerto = $APP->dbpuerto;
	}
	$objDB->xajax();
	$objCombos = new clsHtmlCombos();
	$html_visa35idcentro = f2935_HTMLComboV2_visa35idcentro($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_visa35idcentro', 'innerHTML', $html_visa35idcentro);
	//$objResponse->call('$("#visa35idcentro").chosen()');
	return $objResponse;
}
function f2935_Combovisa35idprograma($aParametros)
{
	$_SESSION['u_ultimominuto'] = iminutoavance();
	if (!is_array($aParametros)) {
		$aParametros = json_decode(str_replace('\"', '"', $aParametros), true);
	}
	require './app.php';
	$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto != '') {
		$objDB->dbPuerto = $APP->dbpuerto;
	}
	$objDB->xajax();
	$objCombos = new clsHtmlCombos();
	$html_visa35idprograma = f2935_HTMLComboV2_visa35idprograma($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_visa35idprograma', 'innerHTML', $html_visa35idprograma);
	//$objResponse->call('$("#visa35idprograma").chosen()');
	return $objResponse;
}
function f2935_Combovisa35nivelforma($aParametros)
{
	$_SESSION['u_ultimominuto'] = iminutoavance();
	if (!is_array($aParametros)) {
		$aParametros = json_decode(str_replace('\"', '"', $aParametros), true);
	}
	require './app.php';
	$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto != '') {
		$objDB->dbPuerto = $APP->dbpuerto;
	}
	$objDB->xajax();
	$objCombos = new clsHtmlCombos();
	$html_visa35nivelforma = f2935_HTMLComboV2_visa35nivelforma($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_visa35nivelforma', 'innerHTML', $html_visa35nivelforma);
	//$objResponse->call('$("#visa35nivelforma").chosen()');
	return $objResponse;
}
function f2935_ExisteDato($datos)
{
	if (!is_array($datos)) {
		$datos = json_decode(str_replace('\"', '"', $datos), true);
	}
	$_SESSION['u_ultimominuto'] = iminutoavance();
	$bHayLlave = true;
	$visa35consec = numeros_validar($datos[1]);
	if ($visa35consec == '') {
		$bHayLlave = false;
	}
	if ($bHayLlave) {
		require './app.php';
		$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto != '') {
			$objDB->dbPuerto = $APP->dbpuerto;
		}
		$objDB->xajax();
		$sSQL = 'SELECT 1 FROM visa35convocatoria WHERE visa35consec=' . $visa35consec . '';
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
function f2935_Busquedas($aParametros)
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
	$mensajes_2935 = 'lg/lg_2935_' . $sIdioma . '.php';
	if (!file_exists($mensajes_2935)) {
		$mensajes_2935 = 'lg/lg_2935_es.php';
	}
	require $mensajes_todas;
	require $mensajes_2935;
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
	$sTituloModulo = $ETI['titulo_2935'];
	$sParams = '';
	$sTabla = '';
	$sJavaBusqueda = '';
	$aParametrosB = array();
	$aParametrosB[101] = 1;
	$aParametrosB[102] = 20;
	switch ($sCampo) {
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
function f2935_HtmlBusqueda($aParametros)
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
	}
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_97tabla', 'innerHTML', $sDetalle);
	return $objResponse;
}
function f2935_TablaDetalleV2($aParametros, $objDB, $bDebug = false)
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
	$mensajes_2935 = 'lg/lg_2935_' . $sIdioma . '.php';
	if (!file_exists($mensajes_2935)) {
		$mensajes_2935 = 'lg/lg_2935_es.php';
	}
	require $mensajes_2935;
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
	$bestado = numeros_validar($aParametros[104]);
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
	$sBotones = '<input id="paginaf2935" name="paginaf2935" type="hidden" value="' . $pagina . '"/>';
	$sBotones = $sBotones . '<input id="lppf2935" name="lppf2935" type="hidden" value="' . $lineastabla . '"/>';
	if ($sLeyenda != '') {
		$sRes = html_salto() . '<div class="GrupoCamposAyuda">' . $sLeyenda . html_salto() . '</div>';
		return array($sRes . $sBotones, $sDebug);
		die();
	}
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
	if ($bnombre != '') {
		$sSQLadd = $sSQLadd . ' AND visa35nombre LIKE "%' . $bnombre . '%"';
	}
	if ($bestado != '') {
		$sSQLadd1 = $sSQLadd1 . 'visa35estado=' . $bestado . ' AND ';
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
	$sTitulos = 'Consec, Id, Tipo, Nombre, Estado';
	$registros = 0;
	$bGigante = false; //En caso de que la tabla sea muy grande pasarlo a true
	$sLimite = '';
	$sCampos = 'SELECT TB.visa35consec, TB.visa35id, T3.visa34nombre, TB.visa35nombre, TB.visa35estado, TB.visa35idtipo';
	$sConsulta = 'FROM visa35convocatoria AS TB, visa34convtipo AS T3 
	WHERE ' . $sSQLadd1 . ' TB.visa35id>0 AND TB.visa35idtipo=T3.visa34id ' . $sSQLadd . '';
	$sOrden = 'ORDER BY TB.visa35consec';
	$sSQL = $sCampos . ' ' . $sConsulta . ' ' . $sOrden;
	// ------------------------------------------------
	// Fin de la consulta
	// ------------------------------------------------
	$sSQLlista = str_replace("'", "|", $sSQL);
	$sSQLlista = str_replace('"', "|", $sSQLlista);
	if ($bGigante) {
		$sSQLContador = 'SELECT COUNT(1) AS Total ' . $sConsulta . '';
		if ($bDebug) {
			$sDebug = $sDebug . log_debug(' Totalizando consulta 2935: ' . $sSQLContador . '');
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
	$sErrConsulta = '<input id="consulta_2935" name="consulta_2935" type="hidden" value="' . $sSQLlista . '"/>';
	$sErrConsulta = $sErrConsulta . '<input id="titulos_2935" name="titulos_2935" type="hidden" value="' . $sTitulos . '"/>';
	if ($bDebug) {
		$sDebug = $sDebug . log_debug('Consulta 2935: ' . $sSQL . '');
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
		$sDebug = $sDebug . log_debug('Termina la consulta 2935');
	}
	$res = $sErrConsulta . $sLeyenda;
	$sClaseTabla = 'table--primary';
	if ($iPiel == 1) {
		$sClaseTabla = 'tablaapp';
	}
	$res = $res . '<div class="table-responsive">';
	$res = $res . '<table border="0" align="center" cellpadding="0" cellspacing="2" class="' . $sClaseTabla . '">';
	$res = $res . '<thead class="fondoazul"><tr>';
	$res = $res . '<th><b>' . $ETI['visa35consec'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['visa35idtipo'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['visa35nombre'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['visa35estado'] . '</b></th>';
	$res = $res . '<th class="flex gap-1 justify-end">';
	$res = $res . html_paginador('paginaf2935', $registros, $lineastabla, $pagina, 'paginarf2935()');
	$res = $res . html_lpp('lppf2935', $lineastabla, 'paginarf2935()');
	$res = $res . '</th>';
	$res = $res . '</tr></thead><tbody>';
	$tlinea = 1;
	while ($filadet = $objDB->sf($tabladetalle)) {
		$sPrefijo = '';
		$sSufijo = '';
		$sClass = ' class="resaltetabla"';
		$sLink = '';
		switch ($filadet['visa35estado']) {
			case 7:
			$sPrefijo = '<b>';
			$sSufijo = '</b>';
			break;
		}
		if (($tlinea % 2) != 0) {
			$sClass = '';
		}
		$tlinea++;
		$et_visa35consec = $sPrefijo . $filadet['visa35consec'] . $sSufijo;
		$et_visa35idtipo = $sPrefijo . cadena_notildes($filadet['visa34nombre']) . $sSufijo;
		$et_visa35nombre = $sPrefijo . cadena_notildes($filadet['visa35nombre']) . $sSufijo;
		$et_visa35estado = $ETI['msg_abierto'];
		if ($filadet['visa35estado'] == 7) {
			$et_visa35estado = $ETI['msg_cerrado'];
		}
		if ($bAbierta) {
			$sLink = '<a href="javascript:cargaridf2935(' . $filadet['visa35id'] . ')" class="lnkresalte">' . $ETI['lnk_cargar'] . '</a>';
		}
		$res = $res . '<tr' . $sClass . '>';
		$res = $res . '<td>' . $et_visa35consec . '</td>';
		$res = $res . '<td>' . $et_visa35idtipo . '</td>';
		$res = $res . '<td>' . $et_visa35nombre . '</td>';
		$res = $res . '<td>' . $et_visa35estado . '</td>';
		$res = $res . '<td align="right">' . $sLink . '</td>';
		$res = $res . '</tr>';
	}
	$res = $res . '</tbody></table>';
	$res = $res . '<div class="salto5px"></div>';
	$res = $res . '</div>';
	$objDB->liberar($tabladetalle);
	return array(cadena_codificar($res), $sDebug);
}
function f2935_HtmlTabla($aParametros)
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
	list($sDetalle, $sDebugTabla) = f2935_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_f2935detalle', 'innerHTML', $sDetalle);
	if ($bDebug) {
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
	}
	return $objResponse;
}
function f2935_db_CargarPadre($DATA, $objDB, $bDebug = false)
{
	$sError = '';
	$iTipoError = 0;
	$sDebug = '';
	require './app.php';
	if ($DATA['paso'] == 1) {
		$sSQLcondi = 'visa35consec=' . $DATA['visa35consec'] . '';
	} else {
		$sSQLcondi = 'visa35id=' . $DATA['visa35id'] . '';
	}
	$sSQL = 'SELECT * FROM visa35convocatoria WHERE ' . $sSQLcondi;
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$DATA['visa35consec'] = $fila['visa35consec'];
		$DATA['visa35id'] = $fila['visa35id'];
		$DATA['visa35idtipo'] = $fila['visa35idtipo'];
		$DATA['visa35nombre'] = $fila['visa35nombre'];
		$DATA['visa35idzona'] = $fila['visa35idzona'];
		$DATA['visa35idcentro'] = $fila['visa35idcentro'];
		$DATA['visa35idescuela'] = $fila['visa35idescuela'];
		$DATA['visa35idprograma'] = $fila['visa35idprograma'];
		$DATA['visa35gruponivel'] = $fila['visa35gruponivel'];
		$DATA['visa35nivelforma'] = $fila['visa35nivelforma'];
		$DATA['visa35estado'] = $fila['visa35estado'];
		$DATA['visa35numcupos'] = $fila['visa35numcupos'];
		$DATA['visa35fecha_apertura'] = $fila['visa35fecha_apertura'];
		$DATA['visa35fecha_liminscrip'] = $fila['visa35fecha_liminscrip'];
		$DATA['visa35fecha_limrevdoc'] = $fila['visa35fecha_limrevdoc'];
		$DATA['visa35fecha_examenes'] = $fila['visa35fecha_examenes'];
		$DATA['visa35fecha_seleccion'] = $fila['visa35fecha_seleccion'];
		$DATA['visa35fecha_ratificacion'] = $fila['visa35fecha_ratificacion'];
		$DATA['visa35fecha_cierra'] = $fila['visa35fecha_cierra'];
		$DATA['visa35presentacion'] = $fila['visa35presentacion'];
		$DATA['visa35total_inscritos'] = $fila['visa35total_inscritos'];
		$DATA['visa35total_autorizados'] = $fila['visa35total_autorizados'];
		$DATA['visa35total_presentaex'] = $fila['visa35total_presentaex'];
		$DATA['visa35total_aprobados'] = $fila['visa35total_aprobados'];
		$DATA['visa35total_admitidos'] = $fila['visa35total_admitidos'];
		$DATA['visa35idconvenio'] = $fila['visa35idconvenio'];
		$DATA['visa35idresolucion'] = $fila['visa35idresolucion'];
		$DATA['visa35idproducto'] = $fila['visa35idproducto'];
		$bcargo = true;
		$DATA['paso'] = 2;
		$DATA['boculta2935'] = 0;
		$bLimpiaHijos = true;
	} else {
		$DATA['paso'] = 0;
	}
	return array($DATA, $sError, $iTipoError, $sDebug);
}
function f2935_Cerrar($visa35id, $objDB, $bDebug = false)
{
	$sInfo = '';
	$sDebug = '';
	return array($sInfo, $sDebug);
}
function f2935_db_GuardarV2b($DATA, $objDB, $bDebug = false, $idTercero = 0, $iCodModulo = 2935)
{
	$iCodModuloAudita = 2935;
	$bAudita[2] = true;
	$bAudita[3] = true;
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_2935 = 'lg/lg_2935_' . $sIdioma . '.php';
	if (!file_exists($mensajes_2935)) {
		$mensajes_2935 = 'lg/lg_2935_es.php';
	}
	require $mensajes_todas;
	require $mensajes_2935;
	$sError = '';
	$iTipoError = 0;
	$sDebug = '';
	if ($idTercero == 0) {
		$idTercero = $_SESSION['unad_id_tercero'];
	}
	/*
	if (isset($DATA['visa35consec']) == 0) {
		$DATA['visa35consec'] = '';
	}
	if (isset($DATA['visa35id']) == 0) {
		$DATA['visa35id'] = '';
	}
	if (isset($DATA['visa35idtipo']) == 0) {
		$DATA['visa35idtipo'] = 0;
	}
	if (isset($DATA['visa35nombre']) == 0) {
		$DATA['visa35nombre'] = '';
	}
	if (isset($DATA['visa35idzona']) == 0) {
		$DATA['visa35idzona'] = 0;
	}
	if (isset($DATA['visa35idcentro']) == 0) {
		$DATA['visa35idcentro'] = 0;
	}
	if (isset($DATA['visa35idescuela']) == 0) {
		$DATA['visa35idescuela'] = 0;
	}
	if (isset($DATA['visa35idprograma']) == 0) {
		$DATA['visa35idprograma'] = 0;
	}
	if (isset($DATA['visa35gruponivel']) == 0) {
		$DATA['visa35gruponivel'] = 0;
	}
	if (isset($DATA['visa35nivelforma']) == 0) {
		$DATA['visa35nivelforma'] = 0;
	}
	if (isset($DATA['visa35estado']) == 0) {
		$DATA['visa35estado'] = '';
	}
	if (isset($DATA['visa35numcupos']) == 0) {
		$DATA['visa35numcupos'] = 0;
	}
	if (isset($DATA['visa35fecha_apertura']) == 0) {
		$DATA['visa35fecha_apertura'] = 0;
	}
	if (isset($DATA['visa35fecha_liminscrip']) == 0) {
		$DATA['visa35fecha_liminscrip'] = 0;
	}
	if (isset($DATA['visa35fecha_limrevdoc']) == 0) {
		$DATA['visa35fecha_limrevdoc'] = 0;
	}
	if (isset($DATA['visa35fecha_examenes']) == 0) {
		$DATA['visa35fecha_examenes'] = 0;
	}
	if (isset($DATA['visa35fecha_seleccion']) == 0) {
		$DATA['visa35fecha_seleccion'] = 0;
	}
	if (isset($DATA['visa35fecha_ratificacion']) == 0) {
		$DATA['visa35fecha_ratificacion'] = 0;
	}
	if (isset($DATA['visa35fecha_cierra']) == 0) {
		$DATA['visa35fecha_cierra'] = 0;
	}
	if (isset($DATA['visa35presentacion']) == 0) {
		$DATA['visa35presentacion'] = '';
	}
	if (isset($DATA['visa35total_inscritos']) == 0) {
		$DATA['visa35total_inscritos'] = 0;
	}
	if (isset($DATA['visa35total_autorizados']) == 0) {
		$DATA['visa35total_autorizados'] = 0;
	}
	if (isset($DATA['visa35total_presentaex']) == 0) {
		$DATA['visa35total_presentaex'] = 0;
	}
	if (isset($DATA['visa35total_aprobados']) == 0) {
		$DATA['visa35total_aprobados'] = 0;
	}
	if (isset($DATA['visa35total_admitidos']) == 0) {
		$DATA['visa35total_admitidos'] = 0;
	}
	if (isset($DATA['visa35idproducto']) == 0) {
		$DATA['visa35idproducto'] = 0;
	}
	*/
	// -- Se inicia validando todas las posibles entradas de usuario.
	$DATA['visa35consec'] = numeros_validar($DATA['visa35consec']);
	$DATA['visa35idtipo'] = numeros_validar($DATA['visa35idtipo']);
	$DATA['visa35nombre'] = cadena_Validar(trim($DATA['visa35nombre']));
	$DATA['visa35idzona'] = numeros_validar($DATA['visa35idzona']);
	$DATA['visa35idcentro'] = numeros_validar($DATA['visa35idcentro']);
	$DATA['visa35idescuela'] = numeros_validar($DATA['visa35idescuela']);
	$DATA['visa35idprograma'] = numeros_validar($DATA['visa35idprograma']);
	$DATA['visa35gruponivel'] = numeros_validar($DATA['visa35gruponivel']);
	$DATA['visa35nivelforma'] = numeros_validar($DATA['visa35nivelforma']);
	$DATA['visa35numcupos'] = numeros_validar($DATA['visa35numcupos']);
	$DATA['visa35fecha_apertura'] = numeros_validar($DATA['visa35fecha_apertura']);
	$DATA['visa35fecha_liminscrip'] = numeros_validar($DATA['visa35fecha_liminscrip']);
	$DATA['visa35fecha_limrevdoc'] = numeros_validar($DATA['visa35fecha_limrevdoc']);
	$DATA['visa35fecha_examenes'] = numeros_validar($DATA['visa35fecha_examenes']);
	$DATA['visa35fecha_seleccion'] = numeros_validar($DATA['visa35fecha_seleccion']);
	$DATA['visa35fecha_ratificacion'] = numeros_validar($DATA['visa35fecha_ratificacion']);
	$DATA['visa35fecha_cierra'] = numeros_validar($DATA['visa35fecha_cierra']);
	$DATA['visa35presentacion'] = cadena_Validar(trim($DATA['visa35presentacion']));
	$DATA['visa35total_inscritos'] = numeros_validar($DATA['visa35total_inscritos']);
	$DATA['visa35total_autorizados'] = numeros_validar($DATA['visa35total_autorizados']);
	$DATA['visa35total_presentaex'] = numeros_validar($DATA['visa35total_presentaex']);
	$DATA['visa35total_aprobados'] = numeros_validar($DATA['visa35total_aprobados']);
	$DATA['visa35total_admitidos'] = numeros_validar($DATA['visa35total_admitidos']);
	$DATA['visa35idproducto'] = numeros_validar($DATA['visa35idproducto']);
	// -- Se inicializan las variables que puedan pasar vacias {Especialmente números}.
	/*
	if ($DATA['visa35idtipo'] == '') {
		$DATA['visa35idtipo'] = 0;
	}
	if ($DATA['visa35idzona'] == '') {
		$DATA['visa35idzona'] = 0;
	}
	if ($DATA['visa35idcentro'] == '') {
		$DATA['visa35idcentro'] = 0;
	}
	if ($DATA['visa35idescuela'] == '') {
		$DATA['visa35idescuela'] = 0;
	}
	if ($DATA['visa35idprograma'] == '') {
		$DATA['visa35idprograma'] = 0;
	}
	if ($DATA['visa35gruponivel'] == '') {
		$DATA['visa35gruponivel'] = 0;
	}
	if ($DATA['visa35nivelforma'] == '') {
		$DATA['visa35nivelforma'] = 0;
	}
	*/
	if ($DATA['visa35estado'] == '') {
		$DATA['visa35estado'] = 0;
	}
		/*
	if ($DATA['visa35numcupos'] == '') {
		$DATA['visa35numcupos'] = 0;
	}
	if ($DATA['visa35fecha_apertura'] == '') {
		$DATA['visa35fecha_apertura'] = 0;
	}
	if ($DATA['visa35fecha_liminscrip'] == '') {
		$DATA['visa35fecha_liminscrip'] = 0;
	}
	if ($DATA['visa35fecha_limrevdoc'] == '') {
		$DATA['visa35fecha_limrevdoc'] = 0;
	}
	if ($DATA['visa35fecha_examenes'] == '') {
		$DATA['visa35fecha_examenes'] = 0;
	}
	if ($DATA['visa35fecha_seleccion'] == '') {
		$DATA['visa35fecha_seleccion'] = 0;
	}
	if ($DATA['visa35fecha_ratificacion'] == '') {
		$DATA['visa35fecha_ratificacion'] = 0;
	}
	if ($DATA['visa35fecha_cierra'] == '') {
		$DATA['visa35fecha_cierra'] = 0;
	}
	if ($DATA['visa35total_inscritos'] == '') {
		$DATA['visa35total_inscritos'] = 0;
	}
	if ($DATA['visa35total_autorizados'] == '') {
		$DATA['visa35total_autorizados'] = 0;
	}
	if ($DATA['visa35total_presentaex'] == '') {
		$DATA['visa35total_presentaex'] = 0;
	}
	if ($DATA['visa35total_aprobados'] == '') {
		$DATA['visa35total_aprobados'] = 0;
	}
	if ($DATA['visa35total_admitidos'] == '') {
		$DATA['visa35total_admitidos'] = 0;
	}
	if ($DATA['visa35idproducto'] == '') {
		$DATA['visa35idproducto'] = 0;
	}
	*/
	// -- Seccion para validar los posibles causales de error.
	$sSepara = ', ';
	if ($DATA['visa35idproducto'] == '') {
		$sError = $ERR['visa35idproducto'] . $sSepara . $sError;
	}
	if ($DATA['visa35total_admitidos'] == '') {
		$sError = $ERR['visa35total_admitidos'] . $sSepara . $sError;
	}
	if ($DATA['visa35total_aprobados'] == '') {
		$sError = $ERR['visa35total_aprobados'] . $sSepara . $sError;
	}
	if ($DATA['visa35total_presentaex'] == '') {
		$sError = $ERR['visa35total_presentaex'] . $sSepara . $sError;
	}
	if ($DATA['visa35total_autorizados'] == '') {
		$sError = $ERR['visa35total_autorizados'] . $sSepara . $sError;
	}
	if ($DATA['visa35total_inscritos'] == '') {
		$sError = $ERR['visa35total_inscritos'] . $sSepara . $sError;
	}
	/*
	if ($DATA['visa35presentacion'] == '') {
		$sError = $ERR['visa35presentacion'] . $sSepara . $sError;
	}
	*/
	if (!fecha_NumValido($DATA['visa35fecha_cierra'])) {
		//$DATA['visa35fecha_cierra'] = fecha_DiaMod();
		$sError = $ERR['visa35fecha_cierra'] . $sSepara . $sError;
	}
	if (!fecha_NumValido($DATA['visa35fecha_ratificacion'])) {
		//$DATA['visa35fecha_ratificacion'] = fecha_DiaMod();
		$sError = $ERR['visa35fecha_ratificacion'] . $sSepara . $sError;
	}
	if (!fecha_NumValido($DATA['visa35fecha_seleccion'])) {
		//$DATA['visa35fecha_seleccion'] = fecha_DiaMod();
		$sError = $ERR['visa35fecha_seleccion'] . $sSepara . $sError;
	}
	if (!fecha_NumValido($DATA['visa35fecha_examenes'])) {
		//$DATA['visa35fecha_examenes'] = fecha_DiaMod();
		$sError = $ERR['visa35fecha_examenes'] . $sSepara . $sError;
	}
	if (!fecha_NumValido($DATA['visa35fecha_limrevdoc'])) {
		//$DATA['visa35fecha_limrevdoc'] = fecha_DiaMod();
		$sError = $ERR['visa35fecha_limrevdoc'] . $sSepara . $sError;
	}
	if (!fecha_NumValido($DATA['visa35fecha_liminscrip'])) {
		//$DATA['visa35fecha_liminscrip'] = fecha_DiaMod();
		$sError = $ERR['visa35fecha_liminscrip'] . $sSepara . $sError;
	}
	if (!fecha_NumValido($DATA['visa35fecha_apertura'])) {
		//$DATA['visa35fecha_apertura'] = fecha_DiaMod();
		$sError = $ERR['visa35fecha_apertura'] . $sSepara . $sError;
	}
	if ($DATA['visa35numcupos'] == '') {
		$sError = $ERR['visa35numcupos'] . $sSepara . $sError;
	}
	if ($DATA['visa35nivelforma'] == '') {
		$sError = $ERR['visa35nivelforma'] . $sSepara . $sError;
	}
	if ($DATA['visa35gruponivel'] == '') {
		$sError = $ERR['visa35gruponivel'] . $sSepara . $sError;
	}
	if ($DATA['visa35idprograma'] == '') {
		$sError = $ERR['visa35idprograma'] . $sSepara . $sError;
	}
	if ($DATA['visa35idescuela'] == '') {
		$sError = $ERR['visa35idescuela'] . $sSepara . $sError;
	}
	if ($DATA['visa35idcentro'] == '') {
		$sError = $ERR['visa35idcentro'] . $sSepara . $sError;
	}
	if ($DATA['visa35idzona'] == '') {
		$sError = $ERR['visa35idzona'] . $sSepara . $sError;
	}
	if ($DATA['visa35nombre'] == '') {
		$sError = $ERR['visa35nombre'] . $sSepara . $sError;
	}
	if ($DATA['visa35idtipo'] == '') {
		$sError = $ERR['visa35idtipo'] . $sSepara . $sError;
	}
	//Fin de las valiaciones NO LLAVE.
	//Valiaciones de campos obligatorios en todo guardar.
	if ($sError == '') {
		$aListaCampos = array('', 'visa35nombre');
		$aLargoCampos = array(0, 250);
		for ($k = 1; $k <= 1; $k++) {
			$iLargoCampo = strlen($DATA[$aListaCampos[$k]]);
			if ($iLargoCampo > $aLargoCampos[$k]) {
				$sError = $ETI['error_cadena_1'] . $ETI[$aListaCampos[$k]] . $ETI['error_cadena_2'] . ' [' . $iLargoCampo . '/' . $aLargoCampos[$k] . ']' . $sSepara . $sError;
			}
		}
	}
	// -- Se verifican los valores de campos de otras tablas.
	$bQuitarCodigo = false;
	$sCampoCodigo = '';
	if ($sError == '') {
		if ($DATA['paso'] == 10) {
			if ($DATA['visa35consec'] == '') {
				$DATA['visa35consec'] = tabla_consecutivo('visa35convocatoria', 'visa35consec', '', $objDB);
				if ($DATA['visa35consec'] == -1) {
					$sError = $objDB->serror;
				}
				$bQuitarCodigo = true;
				$sCampoCodigo = 'visa35consec';
			} else {
				list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 8, $idTercero, $objDB);
				if (!$bDevuelve) {
					$sError = $ERR['8'];
					$DATA['visa35consec'] = '';
				}
			}
			if ($sError == '') {
				$sSQL = 'SELECT 1 FROM visa35convocatoria WHERE visa35consec=' . $DATA['visa35consec'] . '';
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
				$sError = $ERR['3'] . ' [Mod ' . $iCodModulo . ']';
			}
		}
	}
	if ($sError == '') {
		if ($DATA['paso'] == 10) {
			//Preparar el Id, Si no lo hay se quita la comprobación.
			$DATA['visa35id'] = tabla_consecutivo('visa35convocatoria', 'visa35id', '', $objDB);
			if ($DATA['visa35id'] == -1) {
				$sError = $objDB->serror;
			}
			//Datos adicionales al iniciar un registro.
			$DATA['visa35estado'] = 0;
			$visa35fecha_apertura = 0; //fecha_DiaMod();
			$visa35fecha_liminscrip = 0; //fecha_DiaMod();
			$visa35fecha_limrevdoc = 0; //fecha_DiaMod();
			$visa35fecha_examenes = 0; //fecha_DiaMod();
			$visa35fecha_seleccion = 0; //fecha_DiaMod();
			$visa35fecha_ratificacion = 0; //fecha_DiaMod();
			$visa35fecha_cierra = 0; //fecha_DiaMod();
			$DATA['visa35idconvenio'] = 0;
			$DATA['visa35idresolucion'] = 0;
		}
	}
	if ($sError == '') {
		//$visa35presentacion = addslashes($DATA['visa35presentacion']);
		$visa35presentacion = str_replace('"', '\"', $DATA['visa35presentacion']);
		$bPasa = false;
		if ($DATA['paso'] == 10) {
			$sCampos2935 = 'visa35consec, visa35id, visa35idtipo, visa35nombre, visa35idzona, 
			visa35idcentro, visa35idescuela, visa35idprograma, visa35gruponivel, visa35nivelforma, 
			visa35estado, visa35numcupos, visa35fecha_apertura, visa35fecha_liminscrip, visa35fecha_limrevdoc, 
			visa35fecha_examenes, visa35fecha_seleccion, visa35fecha_ratificacion, visa35fecha_cierra, visa35presentacion, 
			visa35total_inscritos, visa35total_autorizados, visa35total_presentaex, visa35total_aprobados, visa35total_admitidos, 
			visa35idconvenio, visa35idresolucion, visa35idproducto';
			$sValores2935 = '' . $DATA['visa35consec'] . ', ' . $DATA['visa35id'] . ', ' . $DATA['visa35idtipo'] . ', "' . $DATA['visa35nombre'] . '", ' . $DATA['visa35idzona'] . ', 
			' . $DATA['visa35idcentro'] . ', ' . $DATA['visa35idescuela'] . ', ' . $DATA['visa35idprograma'] . ', ' . $DATA['visa35gruponivel'] . ', ' . $DATA['visa35nivelforma'] . ', 
			' . $DATA['visa35estado'] . ', ' . $DATA['visa35numcupos'] . ', ' . $DATA['visa35fecha_apertura'] . ', ' . $DATA['visa35fecha_liminscrip'] . ', ' . $DATA['visa35fecha_limrevdoc'] . ', 
			' . $DATA['visa35fecha_examenes'] . ', ' . $DATA['visa35fecha_seleccion'] . ', ' . $DATA['visa35fecha_ratificacion'] . ', ' . $DATA['visa35fecha_cierra'] . ', "' . $visa35presentacion . '", 
			' . $DATA['visa35total_inscritos'] . ', ' . $DATA['visa35total_autorizados'] . ', ' . $DATA['visa35total_presentaex'] . ', ' . $DATA['visa35total_aprobados'] . ', ' . $DATA['visa35total_admitidos'] . ', 
			' . $DATA['visa35idconvenio'] . ', ' . $DATA['visa35idresolucion'] . ', ' . $DATA['visa35idproducto'] . '';
			if ($APP->utf8 == 1) {
				$sSQL = 'INSERT INTO visa35convocatoria (' . $sCampos2935 . ') VALUES (' . cadena_codificar($sValores2935) . ');';
				$sDetalle = $sCampos2935 . '[' . cadena_codificar($sValores2935) . ']';
			} else {
				$sSQL = 'INSERT INTO visa35convocatoria (' . $sCampos2935 . ') VALUES (' . $sValores2935 . ');';
				$sDetalle = $sCampos2935 . '[' . $sValores2935 . ']';
			}
			$idAccion = 2;
			$bPasa = true;
		} else {
			$sCampo[1] = 'visa35idtipo';
			$sCampo[2] = 'visa35nombre';
			$sCampo[3] = 'visa35idzona';
			$sCampo[4] = 'visa35idcentro';
			$sCampo[5] = 'visa35idescuela';
			$sCampo[6] = 'visa35idprograma';
			$sCampo[7] = 'visa35gruponivel';
			$sCampo[8] = 'visa35nivelforma';
			$sCampo[9] = 'visa35numcupos';
			$sCampo[10] = 'visa35fecha_apertura';
			$sCampo[11] = 'visa35fecha_liminscrip';
			$sCampo[12] = 'visa35fecha_limrevdoc';
			$sCampo[13] = 'visa35fecha_examenes';
			$sCampo[14] = 'visa35fecha_seleccion';
			$sCampo[15] = 'visa35fecha_ratificacion';
			$sCampo[16] = 'visa35fecha_cierra';
			$sCampo[17] = 'visa35presentacion';
			$sCampo[18] = 'visa35total_inscritos';
			$sCampo[19] = 'visa35total_autorizados';
			$sCampo[20] = 'visa35total_presentaex';
			$sCampo[21] = 'visa35total_aprobados';
			$sCampo[22] = 'visa35total_admitidos';
			$sCampo[23] = 'visa35idproducto';
			$sDato[1] = $DATA['visa35idtipo'];
			$sDato[2] = $DATA['visa35nombre'];
			$sDato[3] = $DATA['visa35idzona'];
			$sDato[4] = $DATA['visa35idcentro'];
			$sDato[5] = $DATA['visa35idescuela'];
			$sDato[6] = $DATA['visa35idprograma'];
			$sDato[7] = $DATA['visa35gruponivel'];
			$sDato[8] = $DATA['visa35nivelforma'];
			$sDato[9] = $DATA['visa35numcupos'];
			$sDato[10] = $DATA['visa35fecha_apertura'];
			$sDato[11] = $DATA['visa35fecha_liminscrip'];
			$sDato[12] = $DATA['visa35fecha_limrevdoc'];
			$sDato[13] = $DATA['visa35fecha_examenes'];
			$sDato[14] = $DATA['visa35fecha_seleccion'];
			$sDato[15] = $DATA['visa35fecha_ratificacion'];
			$sDato[16] = $DATA['visa35fecha_cierra'];
			$sDato[17] = $visa35presentacion;
			$sDato[18] = $DATA['visa35total_inscritos'];
			$sDato[19] = $DATA['visa35total_autorizados'];
			$sDato[20] = $DATA['visa35total_presentaex'];
			$sDato[21] = $DATA['visa35total_aprobados'];
			$sDato[22] = $DATA['visa35total_admitidos'];
			$sDato[23] = $DATA['visa35idproducto'];
			$iNumCamposMod = 23;
			$sWhere = 'visa35id=' . $DATA['visa35id'] . '';
			$sSQL = 'SELECT * FROM visa35convocatoria WHERE ' . $sWhere;
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
					$sSQL = 'UPDATE visa35convocatoria SET ' . cadena_codificar($sDatos) . ' WHERE ' . $sWhere . ';';
				} else {
					$sDetalle = $sDatos . '[' . $sWhere . ']';
					$sSQL = 'UPDATE visa35convocatoria SET ' . $sDatos . ' WHERE ' . $sWhere . ';';
				}
				$idAccion = 3;
			}
		}
		if ($bPasa) {
			if ($bDebug) {
				$sDebug = $sDebug . log_debug(' Guardar 2935 ' . $sSQL . '');
			}
			$result = $objDB->ejecutasql($sSQL);
			if ($result == false) {
				$sError = $ERR['falla_guardar'] . ' [2935] ..<!-- ' . $sSQL . ' -->';
				if ($idAccion == 2) {
					$DATA['visa35id'] = '';
					$bQuitarCodigo = true;
					$DATA['paso'] = 0;
				} else {
					$DATA['paso'] = 2;
				}
			} else {
				if ($bAudita[$idAccion]) {
					seg_auditar($iCodModuloAudita, $_SESSION['unad_id_tercero'], $idAccion, $DATA['visa35id'], $sDetalle, $objDB);
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
function f2935_db_Eliminar($visa35id, $objDB, $bDebug = false)
{
	$iCodModulo = 2935;
	$bAudita[4] = true;
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_2935 = 'lg/lg_2935_' . $sIdioma . '.php';
	if (!file_exists($mensajes_2935)) {
		$mensajes_2935 = 'lg/lg_2935_es.php';
	}
	require $mensajes_todas;
	require $mensajes_2935;
	$sError = '';
	$iTipoError = 0;
	$sDebug = '';
	$visa35id = numeros_validar($visa35id);
	// Traer los datos para hacer las validaciones.
	if ($sError == '') {
		$sSQL = 'SELECT * FROM visa35convocatoria WHERE visa35id=' . $visa35id . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$filabase = $objDB->sf($tabla);
		} else {
			$sError = 'No se encuentra el registro solicitado {Ref: ' . $visa35id . '}';
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
		$sSQL = 'SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=2935';
		$tablaor = $objDB->ejecutasql($sSQL);
		while ($filaor = $objDB->sf($tablaor)) {
			$sSQL = 'SELECT ' . $filaor['campoid'] . ' FROM ' . $filaor['tabla'] . ' WHERE ' . $filaor['camporev'] . '=' . $_REQUEST['visa35id'] . ' LIMIT 0, 1';
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
		$sWhere = 'visa35id=' . $visa35id . '';
		//$sWhere = 'visa35consec=' . $filabase['visa35consec'] . '';
		$sSQL = 'DELETE FROM visa35convocatoria WHERE ' . $sWhere . ';';
		$result = $objDB->ejecutasql($sSQL);
		if ($result == false) {
			$sError = $ERR['falla_eliminar'] . ' .. <!-- ' . $sSQL . ' -->';
		} else {
			if ($bAudita[4]) {
				seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $visa35id, $sWhere, $objDB);
			}
		}
	}
	return array($sError, $iTipoError, $sDebug);
}

function f2935_CambiaEstado($visa35id, $iEstadoOrigen, $iEstadoDestino, $sDetalle, $idUsuario, $objDB, $bDebug = false)
{
	$iCodModulo = 2935;
	$sError = '';
	$sDebug = '';
	$sMensaje = '';
	$bNotificar = false;
	$sSQL = 'SELECT visa35estado FROM visa35convocatoria WHERE visa35id=' . $visa35id . '';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$filabase = $objDB->sf($tabla);
		if ($filabase['visa35estado'] != $iEstadoOrigen) {
			$sError = 'El estado de origen no coincide [' . $filabase['visa35estado'] . '].';
		}
	} else {
		$sError = 'No se ha encontrado el registro solicitado [Ref ' . $visa35id . ']';
	}
	if ($sError == '') {
		$sInfoCambio = 'Cambia el estado a ' . $iEstadoDestino;
		$sDatosAdd = '';
		switch ($iEstadoDestino) {
			case 0:
				break;
		}
	}
	if ($sError == '') {
		//Guardar el historial del cambio...
	}
	if ($sError == '') {
		$sSQL = 'UPDATE visa35convocatoria SET visa35estado=' . $iEstadoDestino . '' . $sDatosAdd . ' WHERE visa35id=' . $visa35id . '';
		$result = $objDB->ejecutasql($sSQL);
		seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 3, $_REQUEST['visa35id'], $sInfoCambio, $objDB);
	}
	if ($bNotificar) {
		list($sError, $sDebugN, $sMensaje) = f2935_Notificar($visa35id, $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugN;
	}
	return array($sError, $sDebug, $sMensaje);
}

function f2935_Notificar($visa35id, $objDB, $bDebug = false)
{
	$sError = '';
	$sDebug = '';
	$sMensaje = '';
	$iHoy = fecha_DiaMod();
	$idInteresado = 0;
	$sSQL = 'SELECT * 
	FROM visa35convocatoria AS TB
	WHERE TB.visa35id=' . $visa35id . '';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$filabase = $objDB->sf($tabla);
		$idInteresado = $filabase['id_interesado'];
		$visa35estado = $filabase['visa35estado'];
	} else {
		$sError = 'No se ha encontrado el registro solicitado [Ref ' . $visa35id . ']';
	}
	if ($sError == '') {
		$sTituloMensaje = 'Notificación de ... ' . fecha_hoy() . ' ' . html_TablaHoraMin(fecha_hora(), fecha_minuto()) . '';
		$sCuerpo = 'Estimado usuario:<br><br>';
		switch ($visa35estado) {
			case 0:
				break;
		}
	}
	if ($sError == '') {
		list($sCorreoUsuario, $sErrorN, $sDebugM) = AUREA_CorreoNotifica($idSolicitante, $objDB, $bDebug);
		if ($sCorreoUsuario == '') {
			$sError = 'El usuario no registra correo de notificaciones.';
		}
	}
	if ($sError == '') {
		$sCuerpo = $sCuerpo . AUREA_HTML_NoResponderSII();
		$sCorreoCopia = '';
		$sCuerpo = AUREA_HTML_EncabezadoCorreo($sTituloMensaje) . $sCuerpo . AUREA_HTML_PieCorreo();
		$sMes = date('Ym');
		$sTabla = 'aure01login' . $sMes;
		list($idSMTP, $sDebugS) = AUREA_SmtpMejor($sTabla, $objDB, $bDebug);
		$objMail = new clsMail_Unad($objDB);
		$objMail->TraerSMTP($idSMTP);
		$objMail->sAsunto = cadena_codificar($sTituloMensaje);
		$sMensaje = 'Se notifica al correo ' . $sCorreoUsuario;
		$objMail->addCorreo($sCorreoUsuario, $sCorreoUsuario);
		if ($sCorreoCopia != '') {
			$objMail->addCorreo($sCorreoCopia, $sCorreoCopia, 'O');
			$sMensaje = $sMensaje . ' con copia a ' . $sCorreoCopia;
		}
		if ($sError == '') {
			$objMail->sCuerpo = $sCuerpo;
			$sError = $objMail->Enviar($bDebug);
			if ($sError != '') {
				$sMensaje = '';
			}
		}
	}
	return array($sError, $sDebug, $sMensaje);
}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------

