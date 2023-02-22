<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2023 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.29.2 lunes, 13 de febrero de 2023
--- 251 aure51bitacora
*/
/** Archivo lib251.php.
 * Libreria 251 aure51bitacora.
 * @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
 * @date lunes, 13 de febrero de 2023
 */
function f251_HTMLComboV2_aure51idproyecto($objDB, $objCombos, $valor)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$objCombos->nuevo('aure51idproyecto', $valor, true, '{' . $ETI['msg_seleccione'] . '}');
	//$objCombos->iAncho = 450;
	$objCombos->sAccion = 'RevisaLlave();';
	$sSQL = 'SELECT bita09id AS id, bita09titulo AS nombre FROM bita09proyecto';
	$res = $objCombos->html($sSQL, $objDB);
	return $res;
}
function f251_ExisteDato($datos)
{
	if (!is_array($datos)) {
		$datos = json_decode(str_replace('\"', '"', $datos), true);
	}
	$_SESSION['u_ultimominuto'] = iminutoavance();
	$bHayLlave = true;
	$aure51idproyecto = numeros_validar($datos[1]);
	if ($aure51idproyecto == '') {
		$bHayLlave = false;
	}
	$aure51consec = numeros_validar($datos[2]);
	if ($aure51consec == '') {
		$bHayLlave = false;
	}
	$aure51idpadre = numeros_validar($datos[3]);
	if ($aure51idpadre == '') {
		$bHayLlave = false;
	}
	$aure51orden = htmlspecialchars($datos[4]);
	if ($aure51orden == '') {
		$bHayLlave = false;
	}
	if ($bHayLlave) {
		require './app.php';
		$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto != '') {
			$objDB->dbPuerto = $APP->dbpuerto;
		}
		$objDB->xajax();
		$sSQL = 'SELECT 1 FROM aure51bitacora WHERE aure51idproyecto=' . $aure51idproyecto . ' AND aure51consec=' . $aure51consec . ' AND aure51idpadre=' . $aure51idpadre . ' AND aure51orden="' . $aure51orden . '"';
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
function f251_Busquedas($aParametros)
{
	require './app.php';
	$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto != '') {
		$objDB->dbPuerto = $APP->dbpuerto;
	}
	$objDB->xajax();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_251 = 'lg/lg_251_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_251)) {
		$mensajes_251 = 'lg/lg_251_es.php';
	}
	require $mensajes_todas;
	require $mensajes_251;
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
	$iPiel = iDefinirPiel($APP, 1);
	$sParams = '';
	$sTabla = '';
	$sJavaBusqueda = '';
	$aParametrosB = array();
	$aParametrosB[101] = 1;
	$aParametrosB[102] = 20;
	switch ($sCampo) {
		case 'aure51idresponsable':
			require $APP->rutacomun . 'lib111.php';
			$sTabla = f111_TablaDetalleBusquedas($aParametrosB, $objDB);
			$sTitulo = $ETI['aure51idresponsable_busca'];
			$sParams = f111_ParametrosBusqueda();
			$sJavaBusqueda = f111_JavaScriptBusqueda(251);
			break;
		case 'aure52idtercero':
			require $APP->rutacomun . 'lib111.php';
			$sTabla = f111_TablaDetalleBusquedas($aParametrosB, $objDB);
			$sTitulo = $ETI['aure52idtercero_busca'];
			$sParams = f111_ParametrosBusqueda();
			$sJavaBusqueda = f111_JavaScriptBusqueda(251);
			break;
		case 'aure58idusuario':
			require $APP->rutacomun . 'lib111.php';
			$sTabla = f111_TablaDetalleBusquedas($aParametrosB, $objDB);
			$sTitulo = $ETI['aure58idusuario_busca'];
			$sParams = f111_ParametrosBusqueda();
			$sJavaBusqueda = f111_JavaScriptBusqueda(251);
			break;
		case 'aure82idtester':
			require $APP->rutacomun . 'lib111.php';
			$sTabla = f111_TablaDetalleBusquedas($aParametrosB, $objDB);
			$sTitulo = $ETI['aure82idtester_busca'];
			$sParams = f111_ParametrosBusqueda();
			$sJavaBusqueda = f111_JavaScriptBusqueda(251);
			break;
	}
	$sTitulo = '<h2>' . $ETI['titulo_251'] . ' - ' . $sTitulo . '</h2>';
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_97titulo', 'innerHTML', $sTitulo);
	$objResponse->assign('div_97params', 'innerHTML', $sParams);
	$objResponse->assign('div_97tabla', 'innerHTML', $sTabla);
	$objResponse->setFunction('paginarbusqueda', '', $sJavaBusqueda);
	$objResponse->call('expandesector(97)');
	return $objResponse;
}
function f251_HtmlBusqueda($aParametros)
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
		case 'aure51idresponsable':
			require $APP->rutacomun . 'lib111.php';
			$sDetalle = f111_TablaDetalleBusquedas($aParametros, $objDB);
			break;
		case 'aure52idtercero':
			require $APP->rutacomun . 'lib111.php';
			$sDetalle = f111_TablaDetalleBusquedas($aParametros, $objDB);
			break;
		case 'aure58idusuario':
			require $APP->rutacomun . 'lib111.php';
			$sDetalle = f111_TablaDetalleBusquedas($aParametros, $objDB);
			break;
		case 'aure82idtester':
			require $APP->rutacomun . 'lib111.php';
			$sDetalle = f111_TablaDetalleBusquedas($aParametros, $objDB);
			break;
	}
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_97tabla', 'innerHTML', $sDetalle);
	return $objResponse;
}
function f251_TablaDetalleV2($aParametros, $objDB, $bDebug = false)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	/*
	$mensajes_200 = 'lg/lg_200_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_200)) {
		$mensajes_200 = 'lg/lg_200_es.php';
	}
	require $mensajes_200;
	*/
	$mensajes_251 = 'lg/lg_251_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_251)) {
		$mensajes_251 = 'lg/lg_251_es.php';
	}
	require $mensajes_251;
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
	for ($k = 103; $k <= 102; $k++) {
		if (isset($aParametros[$k]) == 0) {
			$aParametros[$k] = '';
		}
	}
	$idTercero = $aParametros[100];
	$sDebug = '';
	if (true) {
		//Leemos los parametros de entrada.
		$pagina = $aParametros[101];
		$lineastabla = $aParametros[102];
		//$bNombre = trim($aParametros[103]);
		//$bListar = numeros_validar($aParametros[104]);
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
	$sLeyenda = '';
	$sBotones = '<input id="paginaf251" name="paginaf251" type="hidden" value="' . $pagina . '"/>
	<input id="lppf251" name="lppf251" type="hidden" value="' . $lineastabla . '"/>';
	if ($sLeyenda != '') {
		$sLeyenda = '<div class="salto1px"></div>
		<div class="GrupoCamposAyuda">
		' . $sLeyenda . '
		<div class="salto1px"></div>
		</div>';
		return array($sLeyenda . $sBotones, $sDebug);
		die();
	}
	$iPiel = iDefinirPiel($APP, 1);
	/*
	$aEstado=array();
	$sSQL = 'SELECT id, nombre FROM tabla';
	$tabla = $objDB->ejecutasql($sSQL);
	while ($fila = $objDB->sf($tabla)) {
		$aEstado[$fila['id']] = cadena_notildes($fila['nombre']);
	}
	*/
	if (true) {
		//Esta condición la ponemos para mantener la conparación con los arhcivos tipo e
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
			$aNoms=explode(' ', $sBase);
			for ($k = 1; $k <= count($aNoms); $k++) {
				$sCadena = $aNoms[$k - 1];
					if ($sCadena != '') {
					$sSQLadd = $sSQLadd . ' AND T6.unad11razonsocial LIKE "%' . $sCadena . '%"';
					//$sSQLadd1 = $sSQLadd1 . 'T1.unad11razonsocial LIKE "%' . $sCadena . '%" AND ';
				}
			}
		}
		*/
	}
	//-- Area para saltar comparaciones con los archivos tipo e
	//-- Fin del area no comparada
	$sTitulos = 'Proyecto, Consec, Padre, Orden, Id, Estado, Fecha, Horaini, Minini, Horafin, Minfin, Sistema, Actividad, Lugar, Detalleactiv, Objetivo, Resultado, Responsable, Tiporesultado';
	$registros = 0;
	$bGigante = false; //En caso de que la tabla sea muy grande pasarlo a true
	$sLimite = '';
	$sCampos = 'SELECT T1.bita09titulo, TB.aure51consec, TB.aure51idpadre, TB.aure51orden, TB.aure51id, TB.aure51estado, TB.aure51fecha, TB.aure51horaini, TB.aure51minini, TB.aure51horafin, TB.aure51minfin, T12.unad01nombre, TB.aure51actividad, TB.aure51lugar, TB.aure51detalleactiv, TB.aure51objetivo, TB.aure51resultado, T18.unad11razonsocial AS C18_nombre, T19.aure59nombre, TB.aure51idproyecto, TB.aure51idsistema, TB.aure51idresponsable, T18.unad11tipodoc AS C18_td, T18.unad11doc AS C18_doc, TB.aure51tiporesultado';
	$sConsulta = 'FROM aure51bitacora AS TB, bita09proyecto AS T1, unad01sistema AS T12, unad11terceros AS T18, aure59tiporesult AS T19 
	WHERE ' . $sSQLadd1 . ' TB.aure51idproyecto=T1.bita09id AND TB.aure51idsistema=T12.unad01id AND TB.aure51idresponsable=T18.unad11id AND TB.aure51tiporesultado=T19.aure59id ' . $sSQLadd . '';
	$sOrden = 'ORDER BY TB.aure51idproyecto, TB.aure51consec, TB.aure51idpadre, TB.aure51orden';
	$sSQL = $sCampos . ' ' . $sConsulta . ' ' . $sOrden;
	$sSQLlista = str_replace("'", "|", $sSQL);
	$sSQLlista = str_replace('"', "|", $sSQLlista);
	if ($bGigante) {
		$sSQLContador = 'SELECT COUNT(1) AS Total ' . $sConsulta . '';
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
	$sErrConsulta = '<input id="consulta_251" name="consulta_251" type="hidden" value="' . $sSQLlista . '"/>
	<input id="titulos_251" name="titulos_251" type="hidden" value="' . $sTitulos . '"/>';
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' Consulta 251: ' . $sSQL . '<br>';
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
	$res = $sErrConsulta . $sLeyenda;
	$res = $res . '<div class="table-responsive">
	<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">
	<thead class="fondoazul"><tr>
	<td><b>' . $ETI['aure51idproyecto'] . '</b></td>
	<td><b>' . $ETI['aure51consec'] . '</b></td>
	<td><b>' . $ETI['aure51idpadre'] . '</b></td>
	<td><b>' . $ETI['aure51orden'] . '</b></td>
	<td><b>' . $ETI['aure51estado'] . '</b></td>
	<td><b>' . $ETI['aure51fecha'] . '</b></td>
	<td><b>' . $ETI['aure51horaini'] . '</b></td>
	<td><b>' . $ETI['aure51horafin'] . '</b></td>
	<td><b>' . $ETI['aure51idsistema'] . '</b></td>
	<td><b>' . $ETI['aure51actividad'] . '</b></td>
	<td><b>' . $ETI['aure51lugar'] . '</b></td>
	<td><b>' . $ETI['aure51detalleactiv'] . '</b></td>
	<td><b>' . $ETI['aure51objetivo'] . '</b></td>
	<td><b>' . $ETI['aure51resultado'] . '</b></td>
	<td colspan="2"><b>' . $ETI['aure51idresponsable'] . '</b></td>
	<td><b>' . $ETI['aure51tiporesultado'] . '</b></td>
	<td align="right">
	' . html_paginador('paginaf251', $registros, $lineastabla, $pagina, 'paginarf251()') . '
	' . html_lpp('lppf251', $lineastabla, 'paginarf251()') . '
	</td>
	</tr></thead>';
	$tlinea = 1;
	while ($filadet = $objDB->sf($tabladetalle)) {
		$sPrefijo = '';
		$sSufijo = '';
		$sClass = ' class="resaltetabla"';
		$sLink = '';
		switch ($filadet['aure51estado']) {
			case 7:
			$sPrefijo = '<b>';
			$sSufijo = '</b>';
			break;
		}
		if (($tlinea % 2) != 0) {
			$sClass = '';
		}
		$tlinea++;
		$et_aure51idproyecto = $sPrefijo . cadena_notildes($filadet['bita09titulo']) . $sSufijo;
		$et_aure51consec = $sPrefijo . $filadet['aure51consec'] . $sSufijo;
		$et_aure51orden = $sPrefijo . $filadet['aure51orden'] . $sSufijo;
		$et_aure51id = $sPrefijo . $filadet['aure51id'] . $sSufijo;
		$et_aure51estado = $ETI['msg_abierto'];
		if ($filadet['aure51estado'] == 7) {
			$et_aure51estado = $ETI['msg_cerrado'];
		}
		$et_aure51fecha = '';
		if ($filadet['aure51fecha'] != 0) {
			$et_aure51fecha = $sPrefijo . fecha_desdenumero($filadet['aure51fecha']) . $sSufijo;
		}
		$et_aure51horaini = $sPrefijo . html_TablaHoraMin($filadet['aure51horaini'], $filadet['aure51minini']) . $sSufijo;
		$et_aure51horafin = $sPrefijo . html_TablaHoraMin($filadet['aure51horafin'], $filadet['aure51minfin']) . $sSufijo;
		$et_aure51idsistema = $sPrefijo . cadena_notildes($filadet['unad01nombre']) . $sSufijo;
		$et_aure51actividad = $sPrefijo . cadena_notildes($filadet['aure51actividad']) . $sSufijo;
		$et_aure51lugar = $sPrefijo . cadena_notildes($filadet['aure51lugar']) . $sSufijo;
		$et_aure51detalleactiv = $sPrefijo . $filadet['aure51detalleactiv'] . $sSufijo;
		$et_aure51objetivo = $sPrefijo . $filadet['aure51objetivo'] . $sSufijo;
		$et_aure51resultado = $sPrefijo . $filadet['aure51resultado'] . $sSufijo;
		$et_aure51idresponsable_doc = '';
		$et_aure51idresponsable_nombre = '';
		if ($filadet['aure51idresponsable'] != 0) {
			$et_aure51idresponsable_doc = $sPrefijo . $filadet['C18_td'] . ' ' . $filadet['C18_doc'] . $sSufijo;
			$et_aure51idresponsable_nombre = $sPrefijo . cadena_notildes($filadet['C18_nombre']) . $sSufijo;
		}
		$et_aure51tiporesultado = $sPrefijo . cadena_notildes($filadet['aure59nombre']) . $sSufijo;
		if ($bAbierta) {
			$sLink = '<a href="javascript:cargaridf251(' . $filadet['aure51id'] . ')" class="lnkresalte">' . $ETI['lnk_cargar'] . '</a>';
		}
		$res = $res . '<tr' . $sClass . '>
		<td>' . $sPrefijo . cadena_notildes($filadet['bita09titulo']) . $sSufijo . '</td>
		<td>' . $sPrefijo . $filadet['aure51consec'] . $sSufijo . '</td>
		<td>' . $sPrefijo . $filadet['aure51idpadre'] . $sSufijo . '</td>
		<td>' . $sPrefijo . $filadet['aure51orden'] . $sSufijo . '</td>
		<td>' . $sPrefijo . $et_aure51estado . $sSufijo . '</td>
		<td>' . $sPrefijo . $et_aure51fecha . $sSufijo . '</td>
		<td>' . $sPrefijo . $et_aure51horaini . $sSufijo . '</td>
		<td>' . $sPrefijo . $et_aure51horafin . $sSufijo . '</td>
		<td>' . $sPrefijo . cadena_notildes($filadet['unad01nombre']) . $sSufijo . '</td>
		<td>' . $sPrefijo . cadena_notildes($filadet['aure51actividad']) . $sSufijo . '</td>
		<td>' . $sPrefijo . cadena_notildes($filadet['aure51lugar']) . $sSufijo . '</td>
		<td>' . $sPrefijo . $filadet['aure51detalleactiv'] . $sSufijo . '</td>
		<td>' . $sPrefijo . $filadet['aure51objetivo'] . $sSufijo . '</td>
		<td>' . $sPrefijo . $filadet['aure51resultado'] . $sSufijo . '</td>
		<td>' . $et_aure51idresponsable_doc . '</td>
		<td>' . $et_aure51idresponsable_nombre . '</td>
		<td>' . $sPrefijo . cadena_notildes($filadet['aure59nombre']) . $sSufijo . '</td>
		<td>' . $sLink . '</td>
		</tr>';
	}
	$res = $res . '</table>
	<div class="salto5px"></div>
	</div>';
	$objDB->liberar($tabladetalle);
	return array(cadena_codificar($res), $sDebug);
}
function f251_HtmlTabla($aParametros)
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
	list($sDetalle, $sDebugTabla) = f251_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_f251detalle', 'innerHTML', $sDetalle);
	if ($bDebug) {
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
	}
	return $objResponse;
}
function f251_db_CargarPadre($DATA, $objDB, $bDebug = false)
{
	$sError = '';
	$iTipoError = 0;
	$sDebug = '';
	require './app.php';
	$DATA['aure51idresponsable_td'] = $APP->tipo_doc;
	$DATA['aure51idresponsable_doc'] = '';
	if ($DATA['paso'] == 1) {
		$sSQLcondi = 'aure51idproyecto=' . $DATA['aure51idproyecto'] . ' AND aure51consec=' . $DATA['aure51consec'] . ' AND aure51idpadre=' . $DATA['aure51idpadre'] . ' AND aure51orden="' . $DATA['aure51orden'] . '"';
	} else {
		$sSQLcondi = 'aure51id=' . $DATA['aure51id'] . '';
	}
	$sSQL = 'SELECT * FROM aure51bitacora WHERE ' . $sSQLcondi;
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$DATA['aure51idproyecto'] = $fila['aure51idproyecto'];
		$DATA['aure51consec'] = $fila['aure51consec'];
		$DATA['aure51idpadre'] = $fila['aure51idpadre'];
		$DATA['aure51orden'] = $fila['aure51orden'];
		$DATA['aure51id'] = $fila['aure51id'];
		$DATA['aure51estado'] = $fila['aure51estado'];
		$DATA['aure51fecha'] = $fila['aure51fecha'];
		$DATA['aure51horaini'] = $fila['aure51horaini'];
		$DATA['aure51minini'] = $fila['aure51minini'];
		$DATA['aure51horafin'] = $fila['aure51horafin'];
		$DATA['aure51minfin'] = $fila['aure51minfin'];
		$DATA['aure51idsistema'] = $fila['aure51idsistema'];
		$DATA['aure51actividad'] = $fila['aure51actividad'];
		$DATA['aure51lugar'] = $fila['aure51lugar'];
		$DATA['aure51detalleactiv'] = $fila['aure51detalleactiv'];
		$DATA['aure51objetivo'] = $fila['aure51objetivo'];
		$DATA['aure51resultado'] = $fila['aure51resultado'];
		$DATA['aure51idresponsable'] = $fila['aure51idresponsable'];
		$DATA['aure51tiporesultado'] = $fila['aure51tiporesultado'];
		$bcargo = true;
		$DATA['paso'] = 2;
		$DATA['boculta251'] = 0;
		$bLimpiaHijos = true;
	} else {
		$DATA['paso'] = 0;
	}
	return array($DATA, $sError, $iTipoError, $sDebug);
}
function f251_Cerrar($aure51id, $objDB, $bDebug = false)
{
	$sInfo = '';
	$sDebug = '';
	return array($sInfo, $sDebug);
}
function f251_db_GuardarV2($DATA, $objDB, $bDebug = false, $idTercero = 0)
{
	$iCodModulo = 251;
	$bAudita[2] = true;
	$bAudita[3] = true;
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_251 = 'lg/lg_251_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_251)) {
		$mensajes_251 = 'lg/lg_251_es.php';
	}
	require $mensajes_todas;
	require $mensajes_251;
	$sError = '';
	$iTipoError = 0;
	$sDebug = '';
	if ($idTercero == 0) {
		$idTercero = $_SESSION['unad_id_tercero'];
	}
	$bCerrando = false;
	$sErrorCerrando = '';
	// -- Se inicia validando todas las posibles entradas de usuario.
	/*
	if (isset($DATA['aure51idproyecto']) == 0) {
		$DATA['aure51idproyecto'] = 0;
	}
	if (isset($DATA['aure51consec']) == 0) {
		$DATA['aure51consec'] = '';
	}
	if (isset($DATA['aure51id']) == 0) {
		$DATA['aure51id'] = '';
	}
	if (isset($DATA['aure51estado']) == 0) {
		$DATA['aure51estado'] = '';
	}
	if (isset($DATA['aure51fecha']) == 0) {
		$DATA['aure51fecha'] = 0;
	}
	if (isset($DATA['aure51horaini']) == 0) {
		$DATA['aure51horaini'] = 0;
	}
	if (isset($DATA['aure51minini']) == 0) {
		$DATA['aure51minini'] = 0;
	}
	if (isset($DATA['aure51horafin']) == 0) {
		$DATA['aure51horafin'] = 0;
	}
	if (isset($DATA['aure51minfin']) == 0) {
		$DATA['aure51minfin'] = 0;
	}
	if (isset($DATA['aure51idsistema']) == 0) {
		$DATA['aure51idsistema'] = 0;
	}
	if (isset($DATA['aure51actividad']) == 0) {
		$DATA['aure51actividad'] = '';
	}
	if (isset($DATA['aure51lugar']) == 0) {
		$DATA['aure51lugar'] = '';
	}
	if (isset($DATA['aure51detalleactiv']) == 0) {
		$DATA['aure51detalleactiv'] = '';
	}
	if (isset($DATA['aure51objetivo']) == 0) {
		$DATA['aure51objetivo'] = '';
	}
	if (isset($DATA['aure51resultado']) == 0) {
		$DATA['aure51resultado'] = '';
	}
	if (isset($DATA['aure51idresponsable']) == 0) {
		$DATA['aure51idresponsable'] = '';
	}
	if (isset($DATA['aure51tiporesultado']) == 0) {
		$DATA['aure51tiporesultado'] = 0;
	}
	*/
	$DATA['aure51idproyecto'] = numeros_validar($DATA['aure51idproyecto']);
	$DATA['aure51consec'] = numeros_validar($DATA['aure51consec']);
	$DATA['aure51horaini'] = numeros_validar($DATA['aure51horaini']);
	$DATA['aure51minini'] = numeros_validar($DATA['aure51minini']);
	$DATA['aure51horafin'] = numeros_validar($DATA['aure51horafin']);
	$DATA['aure51minfin'] = numeros_validar($DATA['aure51minfin']);
	$DATA['aure51actividad'] = htmlspecialchars(trim($DATA['aure51actividad']));
	$DATA['aure51lugar'] = htmlspecialchars(trim($DATA['aure51lugar']));
	$DATA['aure51detalleactiv'] = htmlspecialchars(trim($DATA['aure51detalleactiv']));
	$DATA['aure51objetivo'] = htmlspecialchars(trim($DATA['aure51objetivo']));
	$DATA['aure51resultado'] = htmlspecialchars(trim($DATA['aure51resultado']));
	$DATA['aure51tiporesultado'] = numeros_validar($DATA['aure51tiporesultado']);
	// -- Se inicializan las variables que puedan pasar vacias {Especialmente números}.
	/*
	*/
	if ($DATA['aure51estado'] == '') {
		$DATA['aure51estado'] = 0;
	}
		/*
	if ($DATA['aure51horaini'] == '') {
		$DATA['aure51horaini'] = 0;
	}
	if ($DATA['aure51minini'] == '') {
		$DATA['aure51minini'] = 0;
	}
	if ($DATA['aure51horafin'] == '') {
		$DATA['aure51horafin'] = 0;
	}
	if ($DATA['aure51minfin'] == '') {
		$DATA['aure51minfin'] = 0;
	}
	if ($DATA['aure51idsistema'] == '') {
		$DATA['aure51idsistema'] = 0;
	}
	if ($DATA['aure51tiporesultado'] == '') {
		$DATA['aure51tiporesultado'] = 0;
	}
	*/
	// -- Seccion para validar los posibles causales de error.
	$sSepara = ', ';
	switch ($DATA['aure51estado']) {
		case 7:
			if ($DATA['aure51tiporesultado'] == '') {
				$sError = $ERR['aure51tiporesultado'] . $sSepara . $sError;
			}
			if ($DATA['aure51idresponsable'] == 0) {
				$sError = $ERR['aure51idresponsable'] . $sSepara . $sError;
			}
			/*
			if ($DATA['aure51resultado'] == '') {
				$sError = $ERR['aure51resultado'] . $sSepara . $sError;
			}
			*/
			/*
			if ($DATA['aure51objetivo'] == '') {
				$sError = $ERR['aure51objetivo'] . $sSepara . $sError;
			}
			*/
			/*
			if ($DATA['aure51detalleactiv'] == '') {
				$sError = $ERR['aure51detalleactiv'] . $sSepara . $sError;
			}
			*/
			if ($DATA['aure51lugar'] == '') {
				$sError = $ERR['aure51lugar'] . $sSepara . $sError;
			}
			if ($DATA['aure51actividad'] == '') {
				$sError = $ERR['aure51actividad'] . $sSepara . $sError;
			}
			if ($DATA['aure51minfin'] == '') {
				$sError = $ERR['aure51minfin'] . $sSepara . $sError;
			}
			if ($DATA['aure51horafin'] == '') {
				$sError = $ERR['aure51horafin'] . $sSepara . $sError;
			}
			if ($DATA['aure51minini'] == '') {
				$sError = $ERR['aure51minini'] . $sSepara . $sError;
			}
			if ($DATA['aure51horaini'] == '') {
				$sError = $ERR['aure51horaini'] . $sSepara . $sError;
			}
			if (!fecha_NumValido($DATA['aure51fecha'])) {
				//$DATA['aure51fecha'] = fecha_DiaMod();
				$sError = $ERR['aure51fecha'] . $sSepara . $sError;
			}
			//Fin de las valiaciones NO LLAVE.
			if ($sError != '') {
				$DATA['aure51estado'] = 0;
			}
			$sErrorCerrando = $sError;
			$sError = '';
			break;
	}
	//Valiaciones de campos obligatorios en todo guardar.
	if ($sError == '') {
		$aListaCampos = array('', 'aure51lugar', 'aure51actividad');
		$aLargoCampos = array(0, 250, 250);
		for ($k = 1; $k <= 2; $k++) {
			$iLargoCampo = strlen($DATA[$aListaCampos[$k]]);
			if ($iLargoCampo > $aLargoCampos[$k]) {
				$sError = $ETI['error_cadena_1'] . $ETI[$aListaCampos[$k]] . $ETI['error_cadena_2'] . ' [' . $iLargoCampo . '/' . $aLargoCampos[$k] . ']' . $sSepara . $sError;
			}
		}
	}
	if ($DATA['aure51idproyecto'] == '') {
		$sError = $ERR['aure51idproyecto'];
	}
	// -- Tiene un cerrado.
	if ($DATA['aure51estado'] == 7) {
		//Validaciones previas a cerrar
		//Aprobó las Validaciones al cerrar
		if ($sError.$sErrorCerrando != '') {
			$DATA['aure51estado'] = 0;
			$sErrorCerrando = $sError . ' ' . $sErrorCerrando;
			$sError = '';
		} else {
			$bCerrando = true;
			//Acciones del cierre
		}
	}
	// -- Fin del cerrado.
	// -- Se verifican los valores de campos de otras tablas.
	if ($DATA['aure51idresponsable_doc'] != '') {
		if ($sError == '') {
			$sError = tabla_terceros_existe($DATA['aure51idresponsable_td'], $DATA['aure51idresponsable_doc'], $objDB, 'El tercero Responsable ');
		}
		if ($sError == '') {
			list($sError, $sInfo) = tercero_Bloqueado($DATA['aure51idresponsable'], $objDB);
			if ($sInfo != '') {
				$sError = $sError . '<br>' . $sInfo;
			}
		}
	}
	$bQuitarCodigo = false;
	$sCampoCodigo = '';
	if ($sError == '') {
		if ($DATA['paso'] == 10) {
			if ($DATA['aure51consec'] == '') {
				$DATA['aure51consec'] = tabla_consecutivo('aure51bitacora', 'aure51consec', 'aure51idproyecto=' . $DATA['aure51idproyecto'] . ' AND aure51idpadre=' . $DATA['aure51idpadre'] . ' AND aure51orden="' . $DATA['aure51orden'] . '"', $objDB);
				if ($DATA['aure51consec'] == -1) {
					$sError = $objDB->serror;
				}
				$bQuitarCodigo = true;
				$sCampoCodigo = 'aure51consec';
			} else {
				list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 8, $idTercero, $objDB);
				if (!$bDevuelve) {
					$sError = $ERR['8'];
					$DATA['aure51consec'] = '';
				}
			}
			if ($sError == '') {
				$sSQL = 'SELECT 1 FROM aure51bitacora WHERE aure51idproyecto=' . $DATA['aure51idproyecto'] . ' AND aure51consec=' . $DATA['aure51consec'] . ' AND aure51idpadre=' . $DATA['aure51idpadre'] . ' AND aure51orden="' . $DATA['aure51orden'] . '"';
				$result = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($result) != 0) {
					$sError = $ERR['existe'];
				} else {
					list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 2, $idTercero, $objDB);
					if (!$bDevuelve) {
						$sError = $ERR['2'];
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
			$DATA['aure51id'] = tabla_consecutivo('aure51bitacora', 'aure51id', '', $objDB);
			if ($DATA['aure51id'] == -1) {
				$sError = $objDB->serror;
			}
			//Datos adicionales al iniciar un registro.
			$DATA['aure51estado'] = 0;
			$aure51fecha = fecha_DiaMod();
			$DATA['aure51idsistema'] = 0;
		}
	}
	if ($sError == '') {
		//$aure51detalleactiv = addslashes($DATA['aure51detalleactiv']);
		$aure51detalleactiv = str_replace('"', '\"', $DATA['aure51detalleactiv']);
		//$aure51objetivo = addslashes($DATA['aure51objetivo']);
		$aure51objetivo = str_replace('"', '\"', $DATA['aure51objetivo']);
		//$aure51resultado = addslashes($DATA['aure51resultado']);
		$aure51resultado = str_replace('"', '\"', $DATA['aure51resultado']);
		$bPasa = false;
		if ($DATA['paso'] == 10) {
			$sCampos251 = 'aure51idproyecto, aure51consec, aure51idpadre, aure51orden, aure51id, 
			aure51estado, aure51fecha, aure51horaini, aure51minini, aure51horafin, 
			aure51minfin, aure51idsistema, aure51actividad, aure51lugar, aure51detalleactiv, 
			aure51objetivo, aure51resultado, aure51idresponsable, aure51tiporesultado';
			$sValores251 = '' . $DATA['aure51idproyecto'] . ', ' . $DATA['aure51consec'] . ', ' . $DATA['aure51idpadre'] . ', "' . $DATA['aure51orden'] . '", ' . $DATA['aure51id'] . ', 
			' . $DATA['aure51estado'] . ', ' . $DATA['aure51fecha'] . ', ' . $DATA['aure51horaini'] . ', ' . $DATA['aure51minini'] . ', ' . $DATA['aure51horafin'] . ', 
			' . $DATA['aure51minfin'] . ', ' . $DATA['aure51idsistema'] . ', "' . $DATA['aure51actividad'] . '", "' . $DATA['aure51lugar'] . '", "' . $aure51detalleactiv . '", 
			"' . $aure51objetivo . '", "' . $aure51resultado . '", ' . $DATA['aure51idresponsable'] . ', ' . $DATA['aure51tiporesultado'] . '';
			if ($APP->utf8 == 1) {
				$sSQL = 'INSERT INTO aure51bitacora (' . $sCampos251 . ') VALUES (' . cadena_codificar($sValores251) . ');';
				$sdetalle = $sCampos251 . '[' . cadena_codificar($sValores251) . ']';
			} else {
				$sSQL = 'INSERT INTO aure51bitacora (' . $sCampos251 . ') VALUES (' . $sValores251 . ');';
				$sdetalle = $sCampos251 . '[' . $sValores251 . ']';
			}
			$idAccion = 2;
			$bPasa = true;
		} else {
			$scampo[1] = 'aure51estado';
			$scampo[2] = 'aure51fecha';
			$scampo[3] = 'aure51horaini';
			$scampo[4] = 'aure51minini';
			$scampo[5] = 'aure51horafin';
			$scampo[6] = 'aure51minfin';
			$scampo[7] = 'aure51actividad';
			$scampo[8] = 'aure51lugar';
			$scampo[9] = 'aure51detalleactiv';
			$scampo[10] = 'aure51objetivo';
			$scampo[11] = 'aure51resultado';
			$scampo[12] = 'aure51idresponsable';
			$scampo[13] = 'aure51tiporesultado';
			$sdato[1] = $DATA['aure51estado'];
			$sdato[2] = $DATA['aure51fecha'];
			$sdato[3] = $DATA['aure51horaini'];
			$sdato[4] = $DATA['aure51minini'];
			$sdato[5] = $DATA['aure51horafin'];
			$sdato[6] = $DATA['aure51minfin'];
			$sdato[7] = $DATA['aure51actividad'];
			$sdato[8] = $DATA['aure51lugar'];
			$sdato[9] = $aure51detalleactiv;
			$sdato[10] = $aure51objetivo;
			$sdato[11] = $aure51resultado;
			$sdato[12] = $DATA['aure51idresponsable'];
			$sdato[13] = $DATA['aure51tiporesultado'];
			$iNumCamposMod = 13;
			$sWhere = 'aure51id=' . $DATA['aure51id'] . '';
			$sSQL = 'SELECT * FROM aure51bitacora WHERE ' . $sWhere;
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
					$sSQL = 'UPDATE aure51bitacora SET ' . cadena_codificar($sdatos) . ' WHERE ' . $sWhere . ';';
				} else {
					$sdetalle = $sdatos . '[' . $sWhere . ']';
					$sSQL = 'UPDATE aure51bitacora SET ' . $sdatos . ' WHERE ' . $sWhere . ';';
				}
				$idAccion = 3;
			}
		}
		if ($bPasa) {
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . ' Guardar 251 ' . $sSQL . '<br>';
			}
			$result = $objDB->ejecutasql($sSQL);
			if ($result == false) {
				$sError = $ERR['falla_guardar'] . ' [251] ..<!-- ' . $sSQL . ' -->';
				if ($idAccion == 2) {
					$DATA['aure51id'] = '';
					$bQuitarCodigo = true;
					$DATA['paso'] = 0;
				} else {
					$DATA['paso'] = 2;
				}
				$bCerrando = false;
			} else {
				if ($bAudita[$idAccion]) {
					seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], $idAccion, $DATA['aure51id'], $sdetalle, $objDB);
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
		$bCerrando = false;
		if ($bQuitarCodigo) {
			if ($sCampoCodigo != '') {
				$DATA[$sCampoCodigo] = '';
			}
		}
	}
	$sInfoCierre = '';
	if ($bCerrando) {
		list($sErrorCerrando, $sDebugCerrar) = f251_Cerrar($DATA['aure51id'], $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugCerrar;
	}
	/*
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' InfoDepura<br>';
	}
	*/
	return array($DATA, $sError, $iTipoError, $bCerrando, $sErrorCerrando, $sDebug);
}
function f251_db_Eliminar($aure51id, $objDB, $bDebug = false)
{
	$iCodModulo = 251;
	$bAudita[4] = true;
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_251 = 'lg/lg_251_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_251)) {
		$mensajes_251 = 'lg/lg_251_es.php';
	}
	require $mensajes_todas;
	require $mensajes_251;
	$sError = '';
	$iTipoError = 0;
	$sDebug = '';
	$aure51id = numeros_validar($aure51id);
	// Traer los datos para hacer las validaciones.
	if ($sError == '') {
		$sSQL = 'SELECT * FROM aure51bitacora WHERE aure51id=' . $aure51id . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$filabase = $objDB->sf($tabla);
		} else {
			$sError = 'No se encuentra el registro solicitado {Ref: ' . $aure51id . '}';
		}
	}
	if ($sError == '') {
		$sSQL = 'SELECT 1 FROM aure52bitaparticipa WHERE aure52idbitacora=' . $filabase['aure51id'] . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$sError = 'Existen Participantes creados, no es posible eliminar';
		}
	}
	if ($sError == '') {
		$sSQL = 'SELECT 1 FROM aure58anexos WHERE aure58idbitacora=' . $filabase['aure51id'] . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$sError = 'Existen Anexos creados, no es posible eliminar';
		}
	}
	if ($sError == '') {
		$sSQL = 'SELECT 1 FROM aure57riesgobitacora WHERE aure57idbitacora=' . $filabase['aure51id'] . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$sError = 'Existen Riesgos creados, no es posible eliminar';
		}
	}
	if ($sError == '') {
		$sSQL = 'SELECT 1 FROM aure80historiaus WHERE aure80idbitacora=' . $filabase['aure51id'] . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$sError = 'Existen Historia de usuario creados, no es posible eliminar';
		}
	}
	if ($sError == '') {
		$sSQL = 'SELECT 1 FROM aure81tareaing WHERE aure81idbitacora=' . $filabase['aure51id'] . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$sError = 'Existen Tareas de ingenieria creados, no es posible eliminar';
		}
	}
	if ($sError == '') {
		$sSQL = 'SELECT 1 FROM aure82pruebaac WHERE aure82idbitacora=' . $filabase['aure51id'] . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$sError = 'Existen Pruebas de aceptacion creados, no es posible eliminar';
		}
	}
	if ($sError == '') {
		$sSQL = 'SELECT 1 FROM aure83tarjetacrc WHERE aure83idbitacora=' . $filabase['aure51id'] . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$sError = 'Existen Tarjetas CRC creados, no es posible eliminar';
		}
	}
	if ($sError == '') {
		if (isset($idTercero) == 0) {
			$idTercero = $_SESSION['unad_id_tercero'];
		}
		list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 4, $idTercero, $objDB);
		if (!$bDevuelve) {
			$sError = $ERR['4'];
		}
	}
	if ($sError == '') {
		$sSQL = 'SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=251';
		$tablaor = $objDB->ejecutasql($sSQL);
		while ($filaor = $objDB->sf($tablaor)) {
			$sSQL = 'SELECT ' . $filaor['campoid'] . ' FROM ' . $filaor['tabla'] . ' WHERE ' . $filaor['camporev'] . '=' . $_REQUEST['aure51id'] . ' LIMIT 0, 1';
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
		//$sSQL = 'DELETE FROM aure52bitaparticipa WHERE aure52idbitacora=' . $filabase['aure51id'] . '';
		//$tabla = $objDB->ejecutasql($sSQL);
		//$sSQL = 'DELETE FROM aure58anexos WHERE aure58idbitacora=' . $filabase['aure51id'] . '';
		//$tabla = $objDB->ejecutasql($sSQL);
		//$sSQL = 'DELETE FROM aure57riesgobitacora WHERE aure57idbitacora=' . $filabase['aure51id'] . '';
		//$tabla = $objDB->ejecutasql($sSQL);
		//$sSQL = 'DELETE FROM aure80historiaus WHERE aure80idbitacora=' . $filabase['aure51id'] . '';
		//$tabla = $objDB->ejecutasql($sSQL);
		//$sSQL = 'DELETE FROM aure81tareaing WHERE aure81idbitacora=' . $filabase['aure51id'] . '';
		//$tabla = $objDB->ejecutasql($sSQL);
		//$sSQL = 'DELETE FROM aure82pruebaac WHERE aure82idbitacora=' . $filabase['aure51id'] . '';
		//$tabla = $objDB->ejecutasql($sSQL);
		//$sSQL = 'DELETE FROM aure83tarjetacrc WHERE aure83idbitacora=' . $filabase['aure51id'] . '';
		//$tabla = $objDB->ejecutasql($sSQL);
		$sWhere = 'aure51id=' . $aure51id . '';
		//$sWhere = 'aure51orden="' . $filabase['aure51orden'] . '" AND aure51idpadre=' . $filabase['aure51idpadre'] . ' AND aure51consec=' . $filabase['aure51consec'] . ' AND aure51idproyecto=' . $filabase['aure51idproyecto'] . '';
		$sSQL = 'DELETE FROM aure51bitacora WHERE ' . $sWhere . ';';
		$result = $objDB->ejecutasql($sSQL);
		if ($result == false) {
			$sError = $ERR['falla_eliminar'] . ' .. <!-- ' . $sSQL . ' -->';
		} else {
			if ($bAudita[4]) {
				seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $aure51id, $sWhere, $objDB);
			}
		}
	}
	return array($sError, $iTipoError, $sDebug);
}
function f251_TituloBusqueda()
{
	require './app.php';
	$mensajes_251 = 'lg/lg_251_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_251)) {
		$mensajes_251 = 'lg/lg_251_es.php';
	}
	require $mensajes_251;
	return $ETI['titulo_busca_251'];
}
function f251_ParametrosBusqueda()
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_251 = 'lg/lg_251_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_251)) {
		$mensajes_251 = 'lg/lg_251_es.php';
	}
	require $mensajes_todas;
	require $mensajes_251;
	$sParams = '<label class="Label90">
	' . $ETI['msg_bnombre'] . '
	</label>
	<label>
	<input id="b251nombre" name="b251nombre" type="text" value="" onchange="paginarbusqueda()" />
	</label>';
	return $sParams;
}
function f251_JavaScriptBusqueda($iModuloBusca)
{
	$sRes = 'let sCampo = window.document.frmedita.scampobusca.value;
	let params = new Array();
	params[100] = sCampo;
	params[101] = window.document.frmedita.paginabusqueda.value;
	params[102] = window.document.frmedita.lppfbusqueda.value;
	params[103] = window.document.frmedita.b251nombre.value;
	xajax_f' . $iModuloBusca . '_HtmlBusqueda(params);';
	return $sRes;
}
function f251_TablaDetalleBusquedas($aParametros, $objDB)
{
	$res = '';
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_251 = 'lg/lg_251_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_251)) {
		$mensajes_251 = 'lg/lg_251_es.php';
	}
	require $mensajes_todas;
	require $mensajes_251;
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
	if (isset($aParametros[103]) == 0) {
		$aParametros[103] = '';
	}
	if (isset($aParametros[104]) == 0) {
		$aParametros[104] = '';
	}
	$idTercero = $aParametros[100];
	$pagina = $aParametros[101];
	$lineastabla = $aParametros[102];
	//$bNombre = trim($aParametros[103]);
	//$bListar = numeros_validar($aParametros[104]);
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
	$sBotones = '<input id="paginaf251" name="paginaf251" type="hidden" value="' . $pagina . '" />
	<input id="lppf251" name="lppf251" type="hidden" value="' . $lineastabla . '" />';
	if ($sLeyenda != '') {
		$sLeyenda = '<div class="salto1px"></div>
		<div class="GrupoCamposAyuda">
		' . $sLeyenda . '
		<div class="salto1px"></div>
		</div>';
		return array($sLeyenda . $sBotones, $sDebug);
		die();
	}
	$iPiel = iDefinirPiel($APP, 1);
	$sSQLadd = '';
	$sSQLadd1 = '';
	/*
	if ($aParametros[103] != '') {
		$sSQLadd1 = $sSQLadd1 . 'TB.campo2 LIKE "%' . $aParametros[103] . '%" AND ';
	}
	if ($aParametros[103] != '') {
		$sSQLadd = $sSQLadd . ' AND TB.campo2 LIKE "%' . $aParametros[103] . '%"';
	}
	if ($aParametros[104] != '') {
		$sBase = trim(mb_strtoupper($aParametros[104]));
		$aNoms = explode(' ', $sBase);
		for ($k = 1; $k <= count($aNoms); $k++) {
			$sCadena = $aNoms[$k - 1];
			if ($sCadena != '') {
				$sSQLadd = $sSQLadd . ' AND T6.unad11razonsocial LIKE "%' . $sCadena . '%"';
				//$sSQLadd1 = $sSQLadd1 . 'T1.unad11razonsocial LIKE "%' . $sCadena . '%" AND ';
			}
		}
	}
	*/
	$sTitulos = 'Proyecto, Consec, Padre, Orden, Id, Estado, Fecha, Horaini, Minini, Horafin, Minfin, Sistema, Actividad, Lugar, Detalleactiv, Objetivo, Resultado, Responsable, Tiporesultado';
	$sCampos = 'SELECT T1.bita09titulo, TB.aure51consec, TB.aure51idpadre, TB.aure51orden, TB.aure51id, TB.aure51estado, TB.aure51fecha, TB.aure51horaini, TB.aure51minini, TB.aure51horafin, TB.aure51minfin, T12.unad01nombre, TB.aure51actividad, TB.aure51lugar, TB.aure51detalleactiv, TB.aure51objetivo, TB.aure51resultado, T18.unad11razonsocial AS C18_nombre, T19.aure59nombre, TB.aure51idproyecto, TB.aure51idsistema, TB.aure51idresponsable, T18.unad11tipodoc AS C18_td, T18.unad11doc AS C18_doc, TB.aure51tiporesultado';
	$sConsulta = 'FROM aure51bitacora AS TB, bita09proyecto AS T1, unad01sistema AS T12, unad11terceros AS T18, aure59tiporesult AS T19 
	WHERE ' . $sSQLadd1 . ' TB.aure51idproyecto=T1.bita09id AND TB.aure51idsistema=T12.unad01id AND TB.aure51idresponsable=T18.unad11id AND TB.aure51tiporesultado=T19.aure59id ' . $sSQLadd . '';
	$sOrden = 'ORDER BY TB.aure51idproyecto, TB.aure51consec, TB.aure51idpadre, TB.aure51orden';
	$sSQL = $sCampos . ' ' . $sConsulta . ' ' . $sOrden;
	$sSQLlista = str_replace("'", "|", $sSQL);
	$sSQLlista = str_replace('"', "|", $sSQLlista);
	$sErrConsulta = '<input id="consulta_busqueda" name="consulta_busqueda" type="hidden" value="' . $sSQLlista . '" />
	<input id="titulos_busqueda" name="titulos_busqueda" type="hidden" value="' . $sTitulos . '" />';
	$tabladetalle = $objDB->ejecutasql($sSQL);
	if ($tabladetalle == false) {
		$registros = 0;
		$sErrConsulta = $sErrConsulta . '..<input id="err" name="err" type="hidden" value="' . $sSQL . ' ' . $objDB->serror . '" />';
		//$sLeyenda = $sSQL;
	} else {
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
	$res = $sErrConsulta . $sLeyenda;
	$res = $res . '<div class="table-responsive">
	<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">
	<thead class="fondoazul"><tr>
	<td><b>' . $ETI['aure51idproyecto'] . '</b></td>
	<td><b>' . $ETI['aure51consec'] . '</b></td>
	<td><b>' . $ETI['aure51idpadre'] . '</b></td>
	<td><b>' . $ETI['aure51orden'] . '</b></td>
	<td><b>' . $ETI['aure51estado'] . '</b></td>
	<td><b>' . $ETI['aure51fecha'] . '</b></td>
	<td><b>' . $ETI['aure51horaini'] . '</b></td>
	<td><b>' . $ETI['aure51horafin'] . '</b></td>
	<td><b>' . $ETI['aure51idsistema'] . '</b></td>
	<td><b>' . $ETI['aure51actividad'] . '</b></td>
	<td><b>' . $ETI['aure51lugar'] . '</b></td>
	<td><b>' . $ETI['aure51detalleactiv'] . '</b></td>
	<td><b>' . $ETI['aure51objetivo'] . '</b></td>
	<td><b>' . $ETI['aure51resultado'] . '</b></td>
	<td colspan="2"><b>' . $ETI['aure51idresponsable'] . '</b></td>
	<td><b>' . $ETI['aure51tiporesultado'] . '</b></td>
	<td align="right">
	' . html_paginador('paginabusqueda', $registros, $lineastabla, $pagina, 'paginarbusqueda()') . '
	' . html_lpp('lppfbusqueda', $lineastabla, 'paginarbusqueda()') . '
	</td>
	</tr></thead>';
	$tlinea = 1;
	while ($filadet = $objDB->sf($tabladetalle)) {
		$sPrefijo = '<a href="javascript:Devuelve(\'' . $filadet['aure51id'] . '\');">';
		$sSufijo = '</a>';
		$tlinea++;
		$et_aure51estado = $ETI['msg_abierto'];
		if ($filadet['aure51estado'] == 7) {
			$et_aure51estado = $ETI['msg_cerrado'];
		}
		$et_aure51fecha = '';
		if ($filadet['aure51fecha'] != 0) {
			$et_aure51fecha = fecha_desdenumero($filadet['aure51fecha']);
		}
		$et_aure51horaini =  $sPrefijo . html_TablaHoraMin($filadet['aure51horaini'], $filadet['aure51minini']) . $sSufijo;
		$et_aure51horafin =  $sPrefijo . html_TablaHoraMin($filadet['aure51horafin'], $filadet['aure51minfin']) . $sSufijo;
		$res = $res . '<tr onmouseover="cambia_color_over(this);" onmouseout="cambia_color_out(this);">
		<td>' . $sPrefijo . cadena_notildes($filadet['bita09titulo']) . $sSufijo . '</td>
		<td>' . $sPrefijo . $filadet['aure51consec'] . $sSufijo . '</td>
		<td>' . $sPrefijo . $filadet['aure51idpadre'] . $sSufijo . '</td>
		<td>' . $sPrefijo . $filadet['aure51orden'] . $sSufijo . '</td>
		<td>' . $sPrefijo . $et_aure51estado . $sSufijo . '</td>
		<td>' . $sPrefijo . $et_aure51fecha . $sSufijo . '</td>
		<td>' . $sPrefijo . $et_aure51horaini . $sSufijo . '</td>
		<td>' . $sPrefijo . $et_aure51horafin . $sSufijo . '</td>
		<td>' . $sPrefijo . cadena_notildes($filadet['unad01nombre']) . $sSufijo . '</td>
		<td>' . $sPrefijo . cadena_notildes($filadet['aure51actividad']) . $sSufijo . '</td>
		<td>' . $sPrefijo . cadena_notildes($filadet['aure51lugar']) . $sSufijo . '</td>
		<td>' . $sPrefijo . $filadet['aure51detalleactiv'] . $sSufijo . '</td>
		<td>' . $sPrefijo . $filadet['aure51objetivo'] . $sSufijo . '</td>
		<td>' . $sPrefijo . $filadet['aure51resultado'] . $sSufijo . '</td>
		<td>' . $sPrefijo . $filadet['C18_td'] . ' ' . $filadet['C18_doc'] . $sSufijo . '</td>
		<td>' . $sPrefijo . cadena_notildes($filadet['C18_nombre']) . $sSufijo . '</td>
		<td>' . $sPrefijo . cadena_notildes($filadet['aure59nombre']) . $sSufijo . '</td>
		<td></td>
		</tr>';
	}
	$res = $res . '</table>
	<div class="salto5px"></div>
	</div>';
	$objDB->liberar($tabladetalle);
	return cadena_codificar($res);
}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------

