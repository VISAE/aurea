<?php
/*
--- © Juan David Avellaneda Molina - UNAD - 2026 ---
--- juand.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 3.2.5 miércoles, 16 de septiembre de 2026
--- 2957 visa57actividad
*/
/** Archivo lib2957.php.
 * Libreria 2957 visa57actividad.
 * @author Juan David Avellaneda Molina - juand.avellaneda@unad.edu.co
 * @date miércoles, 16 de septiembre de 2026
 */
function f2957_NombreTabla() {
	return 'visa57actividad';
}
function f2957_HTMLComboV2_visa57idpersemanal($objDB, $objCombos, $valor)
{
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$objCombos->nuevo('visa57idpersemanal', $valor, true, '{' . $ETI['msg_seleccione'] . '}');
	//$objCombos->iAncho = 450;
	//$objCombos->bEsCombobox = true;
	$objCombos->sAccion = 'RevisaLlave();';
	$sSQL = 'SELECT TB.visa56id AS id, TB.visa56fechaini AS nombre 
	FROM visa56persemanal AS TB
	WHERE TB.visa56id>0
	ORDER BY TB.visa56fechaini';
	$res = $objCombos->html($sSQL, $objDB); //, 0, '', 'et', 2957, $sIdioma
	return $res;
}
function f2957_HTMLComboV2_visa57idsistema($objDB, $objCombos, $valor)
{
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$objCombos->nuevo('visa57idsistema', $valor, true, '{' . $ETI['msg_seleccione'] . '}');
	//$objCombos->iAncho = 450;
	//$objCombos->bEsCombobox = true;
	$objCombos->sAccion = 'RevisaLlave();';
	$sSQL = 'SELECT TB.visa55id AS id, TB.visa55nombre AS nombre 
	FROM visa55sistema AS TB
	WHERE TB.visa55id>0
	ORDER BY TB.visa55nombre';
	$res = $objCombos->html($sSQL, $objDB); //, 0, '', 'et', 2957, $sIdioma
	return $res;
}
function f2957_ExisteDato($datos)
{
	if (!is_array($datos)) {
		$datos = json_decode(str_replace('\"', '"', $datos), true);
	}
	$_SESSION['u_ultimominuto'] = iminutoavance();
	$bHayLlave = true;
	$visa57idpersemanal = numeros_validar($datos[1]);
	if ($visa57idpersemanal == '') {
		$bHayLlave = false;
	}
	$visa57idsistema = numeros_validar($datos[2]);
	if ($visa57idsistema == '') {
		$bHayLlave = false;
	}
	$visa57consec = numeros_validar($datos[3]);
	if ($visa57consec == '') {
		$bHayLlave = false;
	}
	if ($bHayLlave) {
		require './app.php';
		$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto != '') {
			$objDB->dbPuerto = $APP->dbpuerto;
		}
		$objDB->xajax();
		$sNomTabla2957 = f2957_NombreTabla();
		$sSQL = 'SELECT 1 FROM ' . $sNomTabla2957 . ' WHERE visa57idpersemanal=' . $visa57idpersemanal . ' AND visa57idsistema=' . $visa57idsistema . ' AND visa57consec=' . $visa57consec . '';
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
function f2957_Busquedas($aParametros)
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
	$mensajes_2957 = 'lg/lg_2957_' . $sIdioma . '.php';
	if (!file_exists($mensajes_2957)) {
		$mensajes_2957 = 'lg/lg_2957_es.php';
	}
	require $mensajes_todas;
	require $mensajes_2957;
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
	$sTituloModulo = $ETI['titulo_2957'];
	$sParams = '';
	$sTabla = '';
	$sJavaBusqueda = '';
	$aParametrosB = array();
	$aParametrosB[101] = 1;
	$aParametrosB[102] = 20;
	switch ($sCampo) {
		case 'visa59idusuario':
			require $APP->rutacomun . 'lib111.php';
			$sTabla = f111_TablaDetalleBusquedas($aParametrosB, $objDB);
			if (isset($ETI['visa59idusuario_busca']) == 0) {
				$ETI['visa59idusuario_busca'] = 'Busqueda de Usuario';
			}
			$sTitulo = $ETI['visa59idusuario_busca'];
			$sParams = f111_ParametrosBusqueda();
			$sJavaBusqueda = f111_JavaScriptBusqueda(2957);
			break;
		case 'visa60idusuario':
			require $APP->rutacomun . 'lib111.php';
			$sTabla = f111_TablaDetalleBusquedas($aParametrosB, $objDB);
			if (isset($ETI['visa60idusuario_busca']) == 0) {
				$ETI['visa60idusuario_busca'] = 'Busqueda de Usuario';
			}
			$sTitulo = $ETI['visa60idusuario_busca'];
			$sParams = f111_ParametrosBusqueda();
			$sJavaBusqueda = f111_JavaScriptBusqueda(2957);
			break;
		case 'visa62idresponsable':
			require $APP->rutacomun . 'lib111.php';
			$sTabla = f111_TablaDetalleBusquedas($aParametrosB, $objDB);
			if (isset($ETI['visa62idresponsable_busca']) == 0) {
				$ETI['visa62idresponsable_busca'] = 'Busqueda de Responsable';
			}
			$sTitulo = $ETI['visa62idresponsable_busca'];
			$sParams = f111_ParametrosBusqueda();
			$sJavaBusqueda = f111_JavaScriptBusqueda(2957);
			break;
		case 'visa63idcolaborador':
			require $APP->rutacomun . 'lib111.php';
			$sTabla = f111_TablaDetalleBusquedas($aParametrosB, $objDB);
			if (isset($ETI['visa63idcolaborador_busca']) == 0) {
				$ETI['visa63idcolaborador_busca'] = 'Busqueda de Colaborador';
			}
			$sTitulo = $ETI['visa63idcolaborador_busca'];
			$sParams = f111_ParametrosBusqueda();
			$sJavaBusqueda = f111_JavaScriptBusqueda(2957);
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
function f2957_HtmlBusqueda($aParametros)
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
		case 'visa59idusuario':
			require $APP->rutacomun . 'lib111.php';
			$sDetalle = f111_TablaDetalleBusquedas($aParametros, $objDB);
			break;
		case 'visa60idusuario':
			require $APP->rutacomun . 'lib111.php';
			$sDetalle = f111_TablaDetalleBusquedas($aParametros, $objDB);
			break;
		case 'visa62idresponsable':
			require $APP->rutacomun . 'lib111.php';
			$sDetalle = f111_TablaDetalleBusquedas($aParametros, $objDB);
			break;
		case 'visa63idcolaborador':
			require $APP->rutacomun . 'lib111.php';
			$sDetalle = f111_TablaDetalleBusquedas($aParametros, $objDB);
			break;
	}
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_97tabla', 'innerHTML', $sDetalle);
	return $objResponse;
}
function f2957_TablaDetalleV2($aParametros, $objDB, $bDebug = false)
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
	$mensajes_2957 = 'lg/lg_2957_' . $sIdioma . '.php';
	if (!file_exists($mensajes_2957)) {
		$mensajes_2957 = 'lg/lg_2957_es.php';
	}
	require $mensajes_2957;
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
	$iNumVariables = 108;
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
	$bsistema = numeros_validar($aParametros[103]);
	$btitulo = cadena_Validar(trim($aParametros[104]));
	$bestado = numeros_validar($aParametros[105]);
	$bprioridad = numeros_validar($aParametros[106]);
	$bfechaini = numeros_validar($aParametros[107]);
	$bfechafin = numeros_validar($aParametros[108]);
	$sNomTabla2957 = f2957_NombreTabla();
	$sLeyenda = '';
	$sBotones = '<input id="paginaf2957" name="paginaf2957" type="hidden" value="' . $pagina . '"/>';
	$sBotones = $sBotones . '<input id="lppf2957" name="lppf2957" type="hidden" value="' . $lineastabla . '"/>';
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
	$avisa57estado = array('');
	$sSQL = 'SELECT unad96id, unad96nombre, unad96etiqueta FROM unad96estado WHERE unad96idmodulo=2957';
	$tabla = $objDB->ejecutasql($sSQL);
	while ($fila = $objDB->sf($tabla)) {
		$et_estado = cadena_notildes($fila['unad96nombre']);
		if ($sIdioma != 'es') {
			$et_estado = Etiqueta_Valor(2957, $fila['unad96etiqueta'], $sIdioma, $objDB);
		}
		$avisa57estado[$fila['unad96id']] = $et_estado;
	}
	/*
	*/
	$sSQLadd = '';
	$sSQLadd1 = '';
	/*
	if ($bsistema != '') {
		$sSQLadd1 = $sSQLadd1 . 'visa57idsistema=' . $bsistema . ' AND ';
	}
	if ($btitulo != '') {
		$sSQLadd = $sSQLadd . ' AND visa57titulo LIKE "%' . $btitulo . '%"';
	}
	if ($bestado != '') {
		$sSQLadd1 = $sSQLadd1 . 'visa57estado=' . $bestado . ' AND ';
	}
	if ($bprioridad != '') {
		$sSQLadd1 = $sSQLadd1 . 'visa57prioridad=' . $bprioridad . ' AND ';
	}
	if (fecha_NumValido($bfechaini)) {
		$sSQLadd1 = $sSQLadd1 . 'visa57fechaprogini>=' . $bfechaini . ' AND ';
	}
	if (fecha_NumValido($bfechafin)) {
		$sSQLadd1 = $sSQLadd1 . 'visa57fechaprogfin>=' . $bfechafin . ' AND ';
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
	$sTitulos = 'Id, Sistema, Titulo, Fechaprogini, Fechaprogfin, Estado, Prioridad, Porcavance';
	$registros = 0;
	$bGigante = false; //En caso de que la tabla sea muy grande pasarlo a true
	$sLimite = '';
	$sCampos = 'SELECT TB.visa57id, T2.visa55nombre, TB.visa57titulo, TB.visa57fechaprogini, TB.visa57fechaprogfin, TB.visa57estado, TB.visa57prioridad, TB.visa57porcavance, TB.visa57idsistema';
	$sConsulta = 'FROM ' . $sNomTabla2957 . ' AS TB, visa55sistema AS T2 
	WHERE ' . $sSQLadd1 . ' TB.visa57id>0 AND TB.visa57idsistema=T2.visa55id ' . $sSQLadd . '';
	$sOrden = 'ORDER BY TB.visa57idsistema';
	$sSQL = $sCampos . ' ' . $sConsulta . ' ' . $sOrden;
	// ------------------------------------------------
	// Fin de la consulta
	// ------------------------------------------------
	$sSQLlista = str_replace("'", "|", $sSQL);
	$sSQLlista = str_replace('"', "|", $sSQLlista);
	if ($bGigante) {
		$sSQLContador = 'SELECT COUNT(1) AS Total ' . $sConsulta . '';
		if ($bDebug) {
			$sDebug = $sDebug . log_debug(' Totalizando consulta 2957: ' . $sSQLContador . '');
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
	$sErrConsulta = '<input id="consulta_2957" name="consulta_2957" type="hidden" value="' . $sSQLlista . '"/>';
	$sErrConsulta = $sErrConsulta . '<input id="titulos_2957" name="titulos_2957" type="hidden" value="' . $sTitulos . '"/>';
	if ($bDebug) {
		$sDebug = $sDebug . log_debug('Consulta 2957: ' . $sSQL . '');
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
		$sDebug = $sDebug . log_debug('Termina la consulta 2957');
	}
	$res = $sErrConsulta . $sLeyenda;
	$sClaseTabla = 'table--primary';
	if ($iPiel == 1) {
		$sClaseTabla = 'tablaapp';
	}
	$res = $res . '<div class="table-responsive">';
	$res = $res . '<table border="0" align="center" cellpadding="0" cellspacing="2" class="' . $sClaseTabla . '">';
	$res = $res . '<thead class="fondoazul"><tr>';
	$res = $res . '<th><b>' . $ETI['visa57idsistema'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['visa57titulo'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['visa57fechaprogini'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['visa57fechaprogfin'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['visa57estado'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['visa57prioridad'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['visa57porcavance'] . '</b></th>';
	$res = $res . '<th class="flex gap-1 justify-end">';
	$res = $res . html_paginador('paginaf2957', $registros, $lineastabla, $pagina, 'paginarf2957()');
	$res = $res . html_lpp('lppf2957', $lineastabla, 'paginarf2957()');
	$res = $res . '</th>';
	$res = $res . '</tr></thead><tbody>';
	$tlinea = 1;
	while ($filadet = $objDB->sf($tabladetalle)) {
		$sPrefijo = '';
		$sSufijo = '';
		$sClass = ' class="resaltetabla"';
		$sLink = '';
		switch ($filadet['visa57estado']) {
			case 7:
			$sPrefijo = '<b>';
			$sSufijo = '</b>';
			break;
		}
		if (($tlinea % 2) != 0) {
			$sClass = '';
		}
		$tlinea++;
		$et_visa57idsistema = $sPrefijo . cadena_notildes($filadet['visa55nombre']) . $sSufijo;
		$et_visa57titulo = $sPrefijo . cadena_notildes($filadet['visa57titulo']) . $sSufijo;
		$et_visa57fechaprogini = '';
		if ($filadet['visa57fechaprogini'] != 0) {
			$et_visa57fechaprogini = $sPrefijo . fecha_desdenumero($filadet['visa57fechaprogini']) . $sSufijo;
		}
		$et_visa57fechaprogfin = '';
		if ($filadet['visa57fechaprogfin'] != 0) {
			$et_visa57fechaprogfin = $sPrefijo . fecha_desdenumero($filadet['visa57fechaprogfin']) . $sSufijo;
		}
		$et_visa57estado = $avisa57estado[$filadet['visa57estado']];
		$et_visa57prioridad = $sPrefijo . $ETI['si'] . $sSufijo;
		if ($filadet['visa57prioridad'] == 0) {
			$et_visa57prioridad = $sPrefijo . $ETI['no'] . $sSufijo;
		}
		$et_visa57porcavance = $sPrefijo . $filadet['visa57porcavance'] . $sSufijo;
		if ($bAbierta) {
			$sLink = '<a href="javascript:cargaridf2957(' . $filadet['visa57id'] . ')" class="lnkresalte">' . $ETI['lnk_cargar'] . '</a>';
		}
		$res = $res . '<tr' . $sClass . '>';
		$res = $res . '<td>' . $et_visa57idsistema . '</td>';
		$res = $res . '<td>' . $et_visa57titulo . '</td>';
		$res = $res . '<td>' . $et_visa57fechaprogini . '</td>';
		$res = $res . '<td>' . $et_visa57fechaprogfin . '</td>';
		$res = $res . '<td>' . $et_visa57estado . '</td>';
		$res = $res . '<td>' . $et_visa57prioridad . '</td>';
		$res = $res . '<td>' . $et_visa57porcavance . '</td>';
		$res = $res . '<td align="right">' . $sLink . '</td>';
		$res = $res . '</tr>';
	}
	$res = $res . '</tbody></table>';
	$res = $res . '<div class="salto5px"></div>';
	$res = $res . '</div>';
	$objDB->liberar($tabladetalle);
	return array(cadena_codificar($res), $sDebug);
}
function f2957_HtmlTabla($aParametros)
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
	list($sDetalle, $sDebugTabla) = f2957_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_f2957detalle', 'innerHTML', $sDetalle);
	if ($bDebug) {
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
	}
	return $objResponse;
}
function f2957_Cerrar($visa57id, $objDB, $bDebug = false)
{
	$sInfo = '';
	$sDebug = '';
	return array($sInfo, $sDebug);
}
function f2957_db_GuardarV2b($DATA, $objDB, $bDebug = false, $idTercero = 0, $iCodModulo = 2957)
{
	$iCodModuloAudita = 2957;
	$bAudita[2] = true;
	$bAudita[3] = true;
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_2957 = 'lg/lg_2957_' . $sIdioma . '.php';
	if (!file_exists($mensajes_2957)) {
		$mensajes_2957 = 'lg/lg_2957_es.php';
	}
	require $mensajes_todas;
	require $mensajes_2957;
	$sError = '';
	$iTipoError = 0;
	$sDebug = '';
	if ($idTercero == 0) {
		$idTercero = $_SESSION['unad_id_tercero'];
	}
	/*
	if (isset($DATA['visa57idpersemanal']) == 0) {
		$DATA['visa57idpersemanal'] = 0;
	}
	if (isset($DATA['visa57idsistema']) == 0) {
		$DATA['visa57idsistema'] = 0;
	}
	if (isset($DATA['visa57consec']) == 0) {
		$DATA['visa57consec'] = '';
	}
	if (isset($DATA['visa57id']) == 0) {
		$DATA['visa57id'] = '';
	}
	if (isset($DATA['visa57titulo']) == 0) {
		$DATA['visa57titulo'] = '';
	}
	if (isset($DATA['visa57descripcion']) == 0) {
		$DATA['visa57descripcion'] = '';
	}
	if (isset($DATA['visa57tipoactividad']) == 0) {
		$DATA['visa57tipoactividad'] = 0;
	}
	if (isset($DATA['visa57estado']) == 0) {
		$DATA['visa57estado'] = '';
	}
	if (isset($DATA['visa57prioridad']) == 0) {
		$DATA['visa57prioridad'] = 0;
	}
	if (isset($DATA['visa57fechaprogini']) == 0) {
		$DATA['visa57fechaprogini'] = 0;
	}
	if (isset($DATA['visa57fechaprogfin']) == 0) {
		$DATA['visa57fechaprogfin'] = 0;
	}
	if (isset($DATA['visa57fechaejecini']) == 0) {
		$DATA['visa57fechaejecini'] = 0;
	}
	if (isset($DATA['visa57fechaejecfin']) == 0) {
		$DATA['visa57fechaejecfin'] = 0;
	}
	if (isset($DATA['visa57porcavance']) == 0) {
		$DATA['visa57porcavance'] = '';
	}
	if (isset($DATA['visa57fechacrea']) == 0) {
		$DATA['visa57fechacrea'] = 0;
	}
	if (isset($DATA['visa57fechaactualiza']) == 0) {
		$DATA['visa57fechaactualiza'] = 0;
	}
	*/
	// -- Se inicia validando todas las posibles entradas de usuario.
	$DATA['visa57idpersemanal'] = numeros_validar($DATA['visa57idpersemanal']);
	$DATA['visa57idsistema'] = numeros_validar($DATA['visa57idsistema']);
	$DATA['visa57consec'] = numeros_validar($DATA['visa57consec']);
	$DATA['visa57titulo'] = cadena_Validar(trim($DATA['visa57titulo']));
	$DATA['visa57descripcion'] = cadena_Validar(trim($DATA['visa57descripcion']));
	$DATA['visa57tipoactividad'] = numeros_validar($DATA['visa57tipoactividad']);
	$DATA['visa57prioridad'] = numeros_validar($DATA['visa57prioridad']);
	$DATA['visa57fechaprogini'] = numeros_validar($DATA['visa57fechaprogini']);
	$DATA['visa57fechaprogfin'] = numeros_validar($DATA['visa57fechaprogfin']);
	$DATA['visa57fechaejecini'] = numeros_validar($DATA['visa57fechaejecini']);
	$DATA['visa57fechaejecfin'] = numeros_validar($DATA['visa57fechaejecfin']);
	$DATA['visa57porcavance'] = numeros_validar($DATA['visa57porcavance'], true);
	$DATA['visa57fechacrea'] = numeros_validar($DATA['visa57fechacrea']);
	$DATA['visa57fechaactualiza'] = numeros_validar($DATA['visa57fechaactualiza']);
	// -- Se inicializan las variables que puedan pasar vacias {Especialmente números}.
	/*
	if ($DATA['visa57tipoactividad'] == '') {
		$DATA['visa57tipoactividad'] = 0;
	}
	*/
	if ($DATA['visa57estado'] == '') {
		$DATA['visa57estado'] = 0;
	}
		/*
	if ($DATA['visa57prioridad'] == '') {
		$DATA['visa57prioridad'] = 0;
	}
	if ($DATA['visa57fechaprogini'] == '') {
		$DATA['visa57fechaprogini'] = 0;
	}
	if ($DATA['visa57fechaprogfin'] == '') {
		$DATA['visa57fechaprogfin'] = 0;
	}
	if ($DATA['visa57fechaejecini'] == '') {
		$DATA['visa57fechaejecini'] = 0;
	}
	if ($DATA['visa57fechaejecfin'] == '') {
		$DATA['visa57fechaejecfin'] = 0;
	}
	if ($DATA['visa57porcavance'] == '') {
		$DATA['visa57porcavance'] = 0;
	}
	if ($DATA['visa57fechacrea'] == '') {
		$DATA['visa57fechacrea'] = 0;
	}
	if ($DATA['visa57fechaactualiza'] == '') {
		$DATA['visa57fechaactualiza'] = 0;
	}
	*/
	// -- Seccion para validar los posibles causales de error.
	$sSepara = ', ';
	if (!fecha_NumValido($DATA['visa57fechaactualiza'])) {
		//$DATA['visa57fechaactualiza'] = fecha_DiaMod();
		$sError = $ERR['visa57fechaactualiza'] . $sSepara . $sError;
	}
	if (!fecha_NumValido($DATA['visa57fechacrea'])) {
		//$DATA['visa57fechacrea'] = fecha_DiaMod();
		$sError = $ERR['visa57fechacrea'] . $sSepara . $sError;
	}
	if ($DATA['visa57porcavance'] == '') {
		$sError = $ERR['visa57porcavance'] . $sSepara . $sError;
	}
	if (!fecha_NumValido($DATA['visa57fechaejecfin'])) {
		//$DATA['visa57fechaejecfin'] = fecha_DiaMod();
		$sError = $ERR['visa57fechaejecfin'] . $sSepara . $sError;
	}
	if (!fecha_NumValido($DATA['visa57fechaejecini'])) {
		//$DATA['visa57fechaejecini'] = fecha_DiaMod();
		$sError = $ERR['visa57fechaejecini'] . $sSepara . $sError;
	}
	if (!fecha_NumValido($DATA['visa57fechaprogfin'])) {
		//$DATA['visa57fechaprogfin'] = fecha_DiaMod();
		$sError = $ERR['visa57fechaprogfin'] . $sSepara . $sError;
	}
	if (!fecha_NumValido($DATA['visa57fechaprogini'])) {
		//$DATA['visa57fechaprogini'] = fecha_DiaMod();
		$sError = $ERR['visa57fechaprogini'] . $sSepara . $sError;
	}
	if ($DATA['visa57prioridad'] == '') {
		$sError = $ERR['visa57prioridad'] . $sSepara . $sError;
	}
	if ($DATA['visa57tipoactividad'] == '') {
		$sError = $ERR['visa57tipoactividad'] . $sSepara . $sError;
	}
	/*
	if ($DATA['visa57descripcion'] == '') {
		$sError = $ERR['visa57descripcion'] . $sSepara . $sError;
	}
	*/
	if ($DATA['visa57titulo'] == '') {
		$sError = $ERR['visa57titulo'] . $sSepara . $sError;
	}
	//Fin de las valiaciones NO LLAVE.
	//Valiaciones de campos obligatorios en todo guardar.
	if ($sError == '') {
		$aListaCampos = array('', 'visa57titulo');
		$aLargoCampos = array(0, 200);
		for ($k = 1; $k <= 1; $k++) {
			$iLargoCampo = strlen($DATA[$aListaCampos[$k]]);
			if ($iLargoCampo > $aLargoCampos[$k]) {
				$sError = $ETI['error_cadena_1'] . $ETI[$aListaCampos[$k]] . $ETI['error_cadena_2'] . ' [' . $iLargoCampo . '/' . $aLargoCampos[$k] . ']' . $sSepara . $sError;
			}
		}
	}
	if ($DATA['visa57idsistema'] == '') {
		$sError = $ERR['visa57idsistema'];
	}
	if ($DATA['visa57idpersemanal'] == '') {
		$sError = $ERR['visa57idpersemanal'];
	}
	// -- Se verifican los valores de campos de otras tablas.
	$bQuitarCodigo = false;
	$sCampoCodigo = '';
	$sNomTabla2957 = f2957_NombreTabla();
	if ($sError == '') {
		if ($DATA['paso'] == 10) {
			if ($DATA['visa57consec'] == '') {
				$DATA['visa57consec'] = tabla_consecutivo($sNomTabla2957, 'visa57consec', 'visa57idpersemanal=' . $DATA['visa57idpersemanal'] . ' AND visa57idsistema=' . $DATA['visa57idsistema'] . '', $objDB);
				if ($DATA['visa57consec'] == -1) {
					$sError = $objDB->serror;
				}
				$bQuitarCodigo = true;
				$sCampoCodigo = 'visa57consec';
			} else {
				list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 8, $idTercero, $objDB);
				if (!$bDevuelve) {
					$sError = $ERR['8'];
					$DATA['visa57consec'] = '';
				}
			}
			if ($sError == '') {
				$sSQL = 'SELECT 1 FROM ' . $sNomTabla2957 . ' WHERE visa57idpersemanal=' . $DATA['visa57idpersemanal'] . ' AND visa57idsistema=' . $DATA['visa57idsistema'] . ' AND visa57consec=' . $DATA['visa57consec'] . '';
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
			$DATA['visa57id'] = tabla_consecutivo($sNomTabla2957, 'visa57id', '', $objDB);
			if ($DATA['visa57id'] == -1) {
				$sError = $objDB->serror;
			}
			//Datos adicionales al iniciar un registro.
			$DATA['visa57estado'] = 0;
			$visa57fechaprogini = 0; //fecha_DiaMod();
			$visa57fechaprogfin = 0; //fecha_DiaMod();
			$visa57fechaejecini = 0; //fecha_DiaMod();
			$visa57fechaejecfin = 0; //fecha_DiaMod();
		}
	}
	if ($sError == '') {
		//$visa57descripcion = addslashes($DATA['visa57descripcion']);
		$visa57descripcion = str_replace('"', '\"', $DATA['visa57descripcion']);
		$bPasa = false;
		if ($DATA['paso'] == 10) {
			$sCampos2957 = 'visa57idpersemanal, visa57idsistema, visa57consec, visa57id, visa57titulo, 
			visa57descripcion, visa57tipoactividad, visa57estado, visa57prioridad, visa57fechaprogini, 
			visa57fechaprogfin, visa57fechaejecini, visa57fechaejecfin, visa57porcavance, visa57fechacrea, 
			visa57fechaactualiza';
			$sValores2957 = '' . $DATA['visa57idpersemanal'] . ', ' . $DATA['visa57idsistema'] . ', ' . $DATA['visa57consec'] . ', ' . $DATA['visa57id'] . ', "' . $DATA['visa57titulo'] . '", 
			"' . $visa57descripcion . '", ' . $DATA['visa57tipoactividad'] . ', ' . $DATA['visa57estado'] . ', ' . $DATA['visa57prioridad'] . ', ' . $DATA['visa57fechaprogini'] . ', 
			' . $DATA['visa57fechaprogfin'] . ', ' . $DATA['visa57fechaejecini'] . ', ' . $DATA['visa57fechaejecfin'] . ', ' . $DATA['visa57porcavance'] . ', ' . $DATA['visa57fechacrea'] . ', 
			' . $DATA['visa57fechaactualiza'] . '';
			if ($APP->utf8 == 1) {
				$sSQL = 'INSERT INTO ' . $sNomTabla2957 . ' (' . $sCampos2957 . ') VALUES (' . cadena_codificar($sValores2957) . ');';
				$sDetalle = $sCampos2957 . '[' . cadena_codificar($sValores2957) . ']';
			} else {
				$sSQL = 'INSERT INTO ' . $sNomTabla2957 . ' (' . $sCampos2957 . ') VALUES (' . $sValores2957 . ');';
				$sDetalle = $sCampos2957 . '[' . $sValores2957 . ']';
			}
			$idAccion = 2;
			$bPasa = true;
		} else {
			$sCampo[1] = 'visa57titulo';
			$sCampo[2] = 'visa57descripcion';
			$sCampo[3] = 'visa57tipoactividad';
			$sCampo[4] = 'visa57prioridad';
			$sCampo[5] = 'visa57fechaprogini';
			$sCampo[6] = 'visa57fechaprogfin';
			$sCampo[7] = 'visa57fechaejecini';
			$sCampo[8] = 'visa57fechaejecfin';
			$sCampo[9] = 'visa57porcavance';
			$sCampo[10] = 'visa57fechacrea';
			$sCampo[11] = 'visa57fechaactualiza';
			$sDato[1] = $DATA['visa57titulo'];
			$sDato[2] = $visa57descripcion;
			$sDato[3] = $DATA['visa57tipoactividad'];
			$sDato[4] = $DATA['visa57prioridad'];
			$sDato[5] = $DATA['visa57fechaprogini'];
			$sDato[6] = $DATA['visa57fechaprogfin'];
			$sDato[7] = $DATA['visa57fechaejecini'];
			$sDato[8] = $DATA['visa57fechaejecfin'];
			$sDato[9] = $DATA['visa57porcavance'];
			$sDato[10] = $DATA['visa57fechacrea'];
			$sDato[11] = $DATA['visa57fechaactualiza'];
			$iNumCamposMod = 11;
			$sWhere = 'visa57id=' . $DATA['visa57id'] . '';
			$sSQL = 'SELECT * FROM ' . $sNomTabla2957 . ' WHERE ' . $sWhere;
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
					$sSQL = 'UPDATE ' . $sNomTabla2957 . ' SET ' . cadena_codificar($sDatos) . ' WHERE ' . $sWhere . ';';
				} else {
					$sDetalle = $sDatos . '[' . $sWhere . ']';
					$sSQL = 'UPDATE ' . $sNomTabla2957 . ' SET ' . $sDatos . ' WHERE ' . $sWhere . ';';
				}
				$idAccion = 3;
			}
		}
		if ($bPasa) {
			if ($bDebug) {
				$sDebug = $sDebug . log_debug(' Guardar 2957 ' . $sSQL . '');
			}
			$result = $objDB->ejecutasql($sSQL);
			if ($result == false) {
				$sError = $ERR['falla_guardar'] . ' [2957] ..<!-- ' . $sSQL . ' -->';
				if ($idAccion == 2) {
					$DATA['visa57id'] = '';
					$bQuitarCodigo = true;
					$DATA['paso'] = 0;
				} else {
					$DATA['paso'] = 2;
				}
			} else {
				if ($bAudita[$idAccion]) {
					seg_auditar($iCodModuloAudita, $_SESSION['unad_id_tercero'], $idAccion, $DATA['visa57id'], $sDetalle, $objDB);
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
function f2957_db_Eliminar($visa57id, $objDB, $bDebug = false)
{
	$iCodModulo = 2957;
	$bAudita[4] = true;
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_2957 = 'lg/lg_2957_' . $sIdioma . '.php';
	if (!file_exists($mensajes_2957)) {
		$mensajes_2957 = 'lg/lg_2957_es.php';
	}
	require $mensajes_todas;
	require $mensajes_2957;
	$sError = '';
	$iTipoError = 0;
	$sDebug = '';
	$visa57id = numeros_validar($visa57id);
	// Traer los datos para hacer las validaciones.
	if ($sError == '') {
		$sNomTabla2957 = f2957_NombreTabla();
		$sNomTabla2958 = f2958_NombreTabla();
		$sNomTabla2959 = f2959_NombreTabla();
		$sNomTabla2960 = f2960_NombreTabla();
		$sNomTabla2961 = f2961_NombreTabla();
		$sNomTabla2962 = f2962_NombreTabla();
		$sNomTabla2963 = f2963_NombreTabla();
		$sSQL = 'SELECT * FROM ' . $sNomTabla2957 . ' WHERE visa57id=' . $visa57id . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$filabase = $objDB->sf($tabla);
		} else {
			$sError = 'No se encuentra el registro solicitado {Ref: ' . $visa57id . '}';
		}
	}
	if ($sError == '') {
		$sSQL = 'SELECT 1 FROM ' . $sNomTabla2958 . ' WHERE visa58idactividad=' . $filabase['visa57id'] . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$sError = 'Existen Resultados creados, no es posible eliminar';
		}
	}
	if ($sError == '') {
		$sSQL = 'SELECT 1 FROM ' . $sNomTabla2959 . ' WHERE visa59idactividad=' . $filabase['visa57id'] . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$sError = 'Existen Evidencias creados, no es posible eliminar';
		}
	}
	if ($sError == '') {
		$sSQL = 'SELECT 1 FROM ' . $sNomTabla2960 . ' WHERE visa60idactividad=' . $filabase['visa57id'] . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$sError = 'Existen Reprogramación creados, no es posible eliminar';
		}
	}
	if ($sError == '') {
		$sSQL = 'SELECT 1 FROM ' . $sNomTabla2961 . ' WHERE visa61idactividad=' . $filabase['visa57id'] . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$sError = 'Existen Dificultades creados, no es posible eliminar';
		}
	}
	if ($sError == '') {
		$sSQL = 'SELECT 1 FROM ' . $sNomTabla2962 . ' WHERE visa62idactividad=' . $filabase['visa57id'] . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$sError = 'Existen Compromisos creados, no es posible eliminar';
		}
	}
	if ($sError == '') {
		$sSQL = 'SELECT 1 FROM ' . $sNomTabla2963 . ' WHERE visa63idactividad=' . $filabase['visa57id'] . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$sError = 'Existen Solicitud de apoyo creados, no es posible eliminar';
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
		$sSQL = 'SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=2957';
		$tablaor = $objDB->ejecutasql($sSQL);
		while ($filaor = $objDB->sf($tablaor)) {
			$sSQL = 'SELECT ' . $filaor['campoid'] . ' FROM ' . $filaor['tabla'] . ' WHERE ' . $filaor['camporev'] . '=' . $_REQUEST['visa57id'] . ' LIMIT 0, 1';
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
		//$sSQL = 'DELETE FROM ' . $sNomTabla2958 . ' WHERE visa58idactividad=' . $filabase['visa57id'] . '';
		//$tabla = $objDB->ejecutasql($sSQL);
		//$sSQL = 'DELETE FROM ' . $sNomTabla2959 . ' WHERE visa59idactividad=' . $filabase['visa57id'] . '';
		//$tabla = $objDB->ejecutasql($sSQL);
		//$sSQL = 'DELETE FROM ' . $sNomTabla2960 . ' WHERE visa60idactividad=' . $filabase['visa57id'] . '';
		//$tabla = $objDB->ejecutasql($sSQL);
		//$sSQL = 'DELETE FROM ' . $sNomTabla2961 . ' WHERE visa61idactividad=' . $filabase['visa57id'] . '';
		//$tabla = $objDB->ejecutasql($sSQL);
		//$sSQL = 'DELETE FROM ' . $sNomTabla2962 . ' WHERE visa62idactividad=' . $filabase['visa57id'] . '';
		//$tabla = $objDB->ejecutasql($sSQL);
		//$sSQL = 'DELETE FROM ' . $sNomTabla2963 . ' WHERE visa63idactividad=' . $filabase['visa57id'] . '';
		//$tabla = $objDB->ejecutasql($sSQL);
		$sWhere = 'visa57id=' . $visa57id . '';
		//$sWhere = 'visa57consec=' . $filabase['visa57consec'] . ' AND visa57idsistema=' . $filabase['visa57idsistema'] . ' AND visa57idpersemanal=' . $filabase['visa57idpersemanal'] . '';
		$sSQL = 'DELETE FROM ' . $sNomTabla2957 . ' WHERE ' . $sWhere . ';';
		$result = $objDB->ejecutasql($sSQL);
		if ($result == false) {
			$sError = $ERR['falla_eliminar'] . ' .. <!-- ' . $sSQL . ' -->';
		} else {
			if ($bAudita[4]) {
				seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $visa57id, $sWhere, $objDB);
			}
		}
	}
	return array($sError, $iTipoError, $sDebug);
}

function f2957_CambiaEstado($visa57id, $iEstadoOrigen, $iEstadoDestino, $sDetalle, $idUsuario, $objDB, $bDebug = false)
{
	$iCodModulo = 2957;
	$sError = '';
	$sDebug = '';
	$sMensaje = '';
	$bNotificar = false;
	$sNomTabla2957 = f2957_NombreTabla();
	$sSQL = 'SELECT visa57estado FROM ' . $sNomTabla2957 . ' WHERE visa57id=' . $visa57id . '';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$filabase = $objDB->sf($tabla);
		if ($filabase['visa57estado'] != $iEstadoOrigen) {
			$sError = 'El estado de origen no coincide [' . $filabase['visa57estado'] . '].';
		}
	} else {
		$sError = 'No se ha encontrado el registro solicitado [Ref ' . $visa57id . ']';
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
		$sSQL = 'UPDATE ' . $sNomTabla2957 . ' SET visa57estado=' . $iEstadoDestino . '' . $sDatosAdd . ' WHERE visa57id=' . $visa57id . '';
		$result = $objDB->ejecutasql($sSQL);
		seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 3, $_REQUEST['visa57id'], $sInfoCambio, $objDB);
	}
	if ($bNotificar) {
		list($sError, $sDebugN, $sMensaje) = f2957_Notificar($visa57id, $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugN;
	}
	return array($sError, $sDebug, $sMensaje);
}

function f2957_Notificar($visa57id, $objDB, $bDebug = false)
{
	$sError = '';
	$sDebug = '';
	$sMensaje = '';
	$iHoy = fecha_DiaMod();
	$idInteresado = 0;
	$sNomTabla2957 = f2957_NombreTabla();
	$sSQL = 'SELECT * 
	FROM ' . $sNomTabla2957 . ' AS TB
	WHERE TB.visa57id=' . $visa57id . '';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$filabase = $objDB->sf($tabla);
		$idInteresado = $filabase['id_interesado'];
		$visa57estado = $filabase['visa57estado'];
	} else {
		$sError = 'No se ha encontrado el registro solicitado [Ref ' . $visa57id . ']';
	}
	if ($sError == '') {
		$sTituloMensaje = 'Notificación de ... ' . fecha_hoy() . ' ' . html_TablaHoraMin(fecha_hora(), fecha_minuto()) . '';
		$sCuerpo = 'Estimado usuario:<br><br>';
		switch ($visa57estado) {
			case 0:
				break;
		}
	}
	if ($sError == '') {
		list($sCorreoUsuario, $sErrorN, $sDebugM) = AUREA_CorreoNotifica($idInteresado, $objDB, $bDebug);
		if ($sCorreoUsuario == '') {
			$sError = 'El usuario no registra correo de notificaciones.';
		}
	}
	if ($sError == '') {
		$sCuerpo = $sCuerpo . AUREA_HTML_NoResponderSII();
		$sCorreoCopia = '';
		$sCuerpo = AUREA_HTML_EncabezadoCorreo($sTituloMensaje) . $sCuerpo . AUREA_HTML_PieCorreo();
		$sMes = date('Ym');
		$sNomTabla = 'aure01login' . $sMes;
		list($idSMTP, $sDebugS) = AUREA_SmtpMejor($sNomTabla, $objDB, $bDebug);
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

