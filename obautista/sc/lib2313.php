<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2018 - 2025 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.22.3 lunes, 6 de agosto de 2018
--- Modelo Versión 2.28.0 jueves, 24 de febrero de 2022
--- Modelo Versión 2.28.1 jueves, 5 de mayo de 2022
--- 2313 cara13consejeros
*/

/** Archivo lib2313.php.
 * Libreria 2313 cara13consejeros.
 * @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
 * @date jueves, 24 de febrero de 2022
 */
function f2313_HTMLComboV2_cara13peraca($objDB, $objCombos, $valor)
{
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$objCombos->nuevo('cara13peraca', $valor, true, '{' . $ETI['msg_seleccione'] . '}');
	$objCombos->sAccion = 'RevisaLlave();';
	$sSQL = f146_ConsultaCombo(2216, $objDB);
	$res = $objCombos->html($sSQL, $objDB);
	return $res;
}
function f2313_HTMLComboV2_cara01idzona($objDB, $objCombos, $valor)
{
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$objCombos->nuevo('cara01idzona', $valor, true, '{' . $ETI['msg_seleccione'] . '}');
	$objCombos->sAccion = 'carga_combo_cara01idcead()';
	$sSQL = 'SELECT unad23id AS id, unad23nombre AS nombre FROM unad23zona';
	$res = $objCombos->html($sSQL, $objDB);
	return $res;
}
function f2313_HTMLComboV2_cara01idcead($objDB, $objCombos, $valor, $vrcara01idzona)
{
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$objCombos->nuevo('cara01idcead', $valor, true, '{' . $ETI['msg_seleccione'] . '}');
	//$objCombos->iAncho=450;
	$sSQL = '';
	if ((int)$vrcara01idzona != 0) {
		$sSQL = 'SELECT unad24id AS id, unad24nombre AS nombre FROM unad24sede WHERE unad24idzona=' . $vrcara01idzona . '';
	}
	$res = $objCombos->html($sSQL, $objDB);
	return $res;
}
function f2313_Combocara01idcead($aParametros)
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
	$html_cara01idcead = f2313_HTMLComboV2_cara01idcead($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_cara01idcead', 'innerHTML', $html_cara01idcead);
	//$objResponse->call('$("#cara01idcead").chosen()');
	return $objResponse;
}
function f2313_ExisteDato($datos)
{
	if (!is_array($datos)) {
		$datos = json_decode(str_replace('\"', '"', $datos), true);
	}
	$_SESSION['u_ultimominuto'] = iminutoavance();
	$bHayLlave = true;
	$cara13peraca = numeros_validar($datos[1]);
	if ($cara13peraca == '') {
		$bHayLlave = false;
	}
	$cara13idconsejero = numeros_validar($datos[2]);
	if ($cara13idconsejero == '') {
		$bHayLlave = false;
	}
	if ($bHayLlave) {
		require './app.php';
		$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto != '') {
			$objDB->dbPuerto = $APP->dbpuerto;
		}
		$objDB->xajax();
		$sSQL = 'SELECT 1 FROM cara13consejeros WHERE cara13peraca=' . $cara13peraca . ' AND cara13idconsejero=' . $cara13idconsejero . '';
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
function f2313_Busquedas($aParametros)
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
	$mensajes_2313 = 'lg/lg_2313_' . $sIdioma . '.php';
	if (!file_exists($mensajes_2313)) {
		$mensajes_2313 = 'lg/lg_2313_es.php';
	}
	require $mensajes_todas;
	require $mensajes_2313;
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
	$sTituloModulo = $ETI['titulo_2313'];
	$sParams = '';
	$sTabla = '';
	$sJavaBusqueda = '';
	$aParametrosB = array();
	$aParametrosB[101] = 1;
	$aParametrosB[102] = 20;
	switch ($sCampo) {
		case 'cara13idconsejero':
			require $APP->rutacomun . 'lib111.php';
			$sTabla = f111_TablaDetalleBusquedas($aParametrosB, $objDB);
			$sTitulo = 'Busqueda de terceros';
			$sParams = f111_ParametrosBusqueda();
			$sJavaBusqueda = f111_JavaScriptBusqueda(2313);
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
function f2313_HtmlBusqueda($aParametros)
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
		case 'cara13idconsejero':
			require $APP->rutacomun . 'lib111.php';
			$sDetalle = f111_TablaDetalleBusquedas($aParametros, $objDB);
			break;
	}
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_97tabla', 'innerHTML', $sDetalle);
	return $objResponse;
}
function f2313_TablaDetalleV2($aParametros, $objDB, $bDebug = false)
{
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_2313 = 'lg/lg_2313_' . $sIdioma . '.php';
	if (!file_exists($mensajes_2313)) {
		$mensajes_2313 = 'lg/lg_2313_es.php';
	}
	require $mensajes_todas;
	require $mensajes_2313;
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
	$iNumVariables = 107;
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
	$bNombre = trim($aParametros[103]);
	$idZona = trim($aParametros[105]);
	$bDoc = trim($aParametros[106]);
	$bListar = numeros_validar($aParametros[107]);
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
	$sBotones = '<input id="paginaf2313" name="paginaf2313" type="hidden" value="' . $pagina . '"/>
	<input id="lppf2313" name="lppf2313" type="hidden" value="' . $lineastabla . '"/>';
	if ($sLeyenda != '') {
		$sRes = html_salto() . '<div class="GrupoCamposAyuda">' . $sLeyenda . html_salto() . '</div>';
		return array($sRes . $sBotones, $sDebug);
		die();
	}
	$iPiel = iDefinirPiel($APP, 2);
	/*
	$aEstado = array();
	$sSQL = 'SELECT id, nombre FROM tabla';
	$tabla = $objDB->ejecutasql($sSQL);
	while ($fila = $objDB->sf($tabla)) {
		$aEstado[$fila['id']] = cadena_notildes($fila['nombre']);
	}
	*/
	$sSQLadd = '';
	$sSQLadd1 = '';
	switch ($bListar) {
		case 1: // con asignacion por centro. cara13cargacentro
			$sSQLadd1 = $sSQLadd1 . ' TB.cara13cargacentro>0 AND ';
			break;
	}
	if ($bDoc != '') {
		$sSQLadd = $sSQLadd . ' AND T2.unad11doc="' . $bDoc . '"';
	}
	if ($bNombre != '') {
		$sBase = mb_strtoupper($bNombre);
		$aNoms = explode(' ', $sBase);
		for ($k = 1; $k <= count($aNoms); $k++) {
			$sCadena = $aNoms[$k - 1];
			if ($sCadena != '') {
				$sSQLadd = $sSQLadd . ' AND T2.unad11razonsocial LIKE "%' . $sCadena . '%"';
				//$sSQLadd1=$sSQLadd1.'T1.sys11razonsocial LIKE "%'.$sCadena.'%" AND ';
			}
		}
	}
	if ($aParametros[104] != '') {
		$sSQLadd1 = $sSQLadd1 . 'TB.cara13peraca=' . $aParametros[104] . ' AND ';
	}
	switch ($idZona) {
		case '':
			break;
		case '-99':
			$sSQLadd1 = $sSQLadd1 . ' TB.cara01idcead=0 AND ';
			break;
		default:
			list($sCentrosZona, $sDebugZ) = f123_CentrosZona($aParametros[105], $objDB, $bDebug);
			$sSQLadd1 = $sSQLadd1 . ' TB.cara01idcead IN (' . $sCentrosZona . ') AND ';
			break;
	}
	// ------------------------------------------------
	// Fin de las condiciones de la consulta
	// ------------------------------------------------
	$sTitulos = 'Periodo,Tipo doc,Doc consejero,Consejero,Activo,Cead,Carga asignada,Carga final,Carga centro';
	$registros = 0;
	$bGigante = false; //En caso de que la tabla sea muy grande pasarlo a true
	$sLimite = '';
	$sCampos = 'SELECT T1.exte02nombre, T2.unad11tipodoc AS C2_td, T2.unad11doc AS C2_doc, T2.unad11razonsocial AS C2_nombre, 
	TB.cara13activo, T6.unad24nombre, TB.cara01cargaasignada, TB.cara01cargafinal, TB.cara13cargacentro, 
	TB.cara13peraca, TB.cara13idconsejero, TB.cara01idzona, TB.cara01idcead, TB.cara13id';
	$sConsulta = 'FROM cara13consejeros AS TB, exte02per_aca AS T1, unad11terceros AS T2, unad24sede AS T6 
	WHERE ' . $sSQLadd1 . ' TB.cara13peraca=T1.exte02id AND TB.cara13idconsejero=T2.unad11id AND TB.cara01idcead=T6.unad24id ' . $sSQLadd . '';
	$sOrden = 'ORDER BY TB.cara13peraca DESC, T6.unad24nombre, T2.unad11doc';
	$sSQL = $sCampos . ' ' . $sConsulta . ' ' . $sOrden;
	// ------------------------------------------------
	// Fin de la consulta
	// ------------------------------------------------
	$sSQLlista = str_replace("'", "|", $sSQL);
	$sSQLlista = str_replace('"', "|", $sSQLlista);
	if ($bGigante) {
		$sSQLContador = 'SELECT COUNT(1) AS Total ' . $sConsulta . '';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' Totalizando consulta 1916: ' . $sSQLContador . '<br>';
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
	$sErrConsulta = '<input id="consulta_2313" name="consulta_2313" type="hidden" value="' . $sSQLlista . '"/>
	<input id="titulos_2313" name="titulos_2313" type="hidden" value="' . $sTitulos . '"/>';
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' Consulta 2313: ' . $sSQL . $sLimite . '<br>';
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
		$sDebug = $sDebug . fecha_microtiempo() . ' Termina la consulta 1916<br>';
	}
	$res = $sErrConsulta . $sLeyenda;
	$sClaseTabla = 'table--primary';
	if ($iPiel == 1) {
		$sClaseTabla = 'tablaapp';
	}
	$res = $res . '<div class="table-responsive">';
	$res = $res . '<table border="0" align="center" cellpadding="0" cellspacing="2" class="' . $sClaseTabla . '">';
	$res = $res . '<thead class="fondoazul"><tr>';
	$res = $res . '<th colspan="2"><b>' . $ETI['cara13idconsejero'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['cara13activo'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['cara01idcead'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['cara01cargaasignada'] . '</b></th>';
	$res = $res . '<th class="flex gap-1 justify-end">';
	$res = $res . html_paginador('paginaf2313', $registros, $lineastabla, $pagina, 'paginarf2313()') . '';
	$res = $res . html_lpp('lppf2313', $lineastabla, 'paginarf2313()') . '';
	$res = $res . '</th>';
	$res = $res . '</tr></thead><tbody>';
	$idPeraca = -99;
	$tlinea = 1;
	while ($filadet = $objDB->sf($tabladetalle)) {
		if ($idPeraca != $filadet['cara13peraca']) {
			$idPeraca = $filadet['cara13peraca'];
			$res = $res . '<tr class="fondoazul">';
			$res = $res . '<td colspan="6">' . $ETI['cara13peraca'] . ' <b>' . cadena_notildes($filadet['exte02nombre']) . '</b></td>';
			$res = $res . '</tr>';
		}
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
		$et_cara13activo = $ETI['no'];
		if ($filadet['cara13activo'] == 'S') {
			$et_cara13activo = $ETI['si'];
		}
		if ($bAbierta) {
			$sLink = '<a href="javascript:cargaridf2313(' . $filadet['cara13id'] . ')" class="lnkresalte">' . $ETI['lnk_cargar'] . '</a>';
		}
		$res = $res . '<tr' . $sClass . '>';
		$res = $res . '<td>' . $sPrefijo . $filadet['C2_td'] . ' ' . $filadet['C2_doc'] . $sSufijo . '</td>';
		$res = $res . '<td>' . $sPrefijo . cadena_notildes($filadet['C2_nombre']) . $sSufijo . '</td>';
		$res = $res . '<td>' . $sPrefijo . $et_cara13activo . $sSufijo . '</td>';
		$res = $res . '<td>' . $sPrefijo . cadena_notildes($filadet['unad24nombre']) . $sSufijo . '</td>';
		$res = $res . '<td>' . $sPrefijo . $filadet['cara01cargafinal'] . '/' . $filadet['cara01cargaasignada'] . $sSufijo . '</td>';
		$res = $res . '<td align="right">' . $sLink . '</td>';
		$res = $res . '</tr>';
	}
	$res = $res . '</tbody></table>';
	$res = $res . '<div class="salto5px"></div>';
	$res = $res . '</div>';
	$objDB->liberar($tabladetalle);
	return array(cadena_codificar($res), $sDebug);
}
function f2313_HtmlTabla($aParametros)
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
	list($sDetalle, $sDebugTabla) = f2313_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_f2313detalle', 'innerHTML', $sDetalle);
	if ($bDebug) {
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
	}
	return $objResponse;
}
function f2313_db_CargarPadre($DATA, $objDB, $bDebug = false)
{
	$sError = '';
	$iTipoError = 0;
	$sDebug = '';
	require './app.php';
	$DATA['cara13idconsejero_td'] = $APP->tipo_doc;
	$DATA['cara13idconsejero_doc'] = '';
	if ($DATA['paso'] == 1) {
		$sSQLcondi = 'cara13peraca=' . $DATA['cara13peraca'] . ' AND cara13idconsejero="' . $DATA['cara13idconsejero'] . '"';
	} else {
		$sSQLcondi = 'cara13id=' . $DATA['cara13id'] . '';
	}
	$sSQL = 'SELECT * FROM cara13consejeros WHERE ' . $sSQLcondi;
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$DATA['cara13peraca'] = $fila['cara13peraca'];
		$DATA['cara13idconsejero'] = $fila['cara13idconsejero'];
		$DATA['cara13id'] = $fila['cara13id'];
		$DATA['cara13activo'] = $fila['cara13activo'];
		$DATA['cara01idzona'] = $fila['cara01idzona'];
		$DATA['cara01idcead'] = $fila['cara01idcead'];
		$DATA['cara01cargaasignada'] = $fila['cara01cargaasignada'];
		$DATA['cara01cargafinal'] = $fila['cara01cargafinal'];
		$DATA['cara01fechafin'] = $fila['cara01fechafin'];
		$DATA['cara13cargacentro'] = $fila['cara13cargacentro'];
		$DATA['cara13permitircargacentro'] = $fila['cara13permitircargacentro'];
		$bcargo = true;
		$DATA['paso'] = 2;
		$DATA['boculta2313'] = 0;
		$bLimpiaHijos = true;
	} else {
		$DATA['paso'] = 0;
	}
	return array($DATA, $sError, $iTipoError, $sDebug);
}
function f2313_db_GuardarV2($DATA, $objDB, $bDebug = false, $idTercero = 0)
{
	$iCodModulo = 2313;
	$bAudita[2] = true;
	$bAudita[3] = true;
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_2313 = 'lg/lg_2313_' . $sIdioma . '.php';
	if (!file_exists($mensajes_2313)) {
		$mensajes_2313 = 'lg/lg_2313_es.php';
	}
	require $mensajes_todas;
	require $mensajes_2313;
	$sError = '';
	$iTipoError = 0;
	$sDebug = '';
	if ($idTercero == 0) {
		$idTercero = $_SESSION['unad_id_tercero'];
	}
	// -- Se inicia validando todas las posibles entradas de usuario.
	/*
	if (isset($DATA['cara13peraca'])==0){$DATA['cara13peraca']='';}
	if (isset($DATA['cara13idconsejero'])==0){$DATA['cara13idconsejero']='';}
	if (isset($DATA['cara13id'])==0){$DATA['cara13id']='';}
	if (isset($DATA['cara13activo'])==0){$DATA['cara13activo']='';}
	if (isset($DATA['cara01idzona'])==0){$DATA['cara01idzona']='';}
	if (isset($DATA['cara01idcead'])==0){$DATA['cara01idcead']='';}
	if (isset($DATA['cara01cargaasignada'])==0){$DATA['cara01cargaasignada']='';}
	if (isset($DATA['cara01fechafin'])==0){$DATA['cara01fechafin']='';}
	*/
	$DATA['cara13peraca'] = numeros_validar($DATA['cara13peraca']);
	$DATA['cara13activo'] = htmlspecialchars(trim($DATA['cara13activo']));
	$DATA['cara01idzona'] = numeros_validar($DATA['cara01idzona']);
	$DATA['cara01idcead'] = numeros_validar($DATA['cara01idcead']);
	$DATA['cara01cargaasignada'] = numeros_validar($DATA['cara01cargaasignada']);
	$DATA['cara13permitircargacentro'] = numeros_validar($DATA['cara13permitircargacentro']);
	// -- Se inicializan las variables que puedan pasar vacias {Especialmente números}.
	//if ($DATA['cara01idzona']==''){$DATA['cara01idzona']=0;}
	//if ($DATA['cara01idcead']==''){$DATA['cara01idcead']=0;}
	//if ($DATA['cara01cargaasignada']==''){$DATA['cara01cargaasignada']=0;}
	if ($DATA['cara13cargacentro'] == '') {
		$DATA['cara13cargacentro'] = 0;
	}
	if ($DATA['cara13permitircargacentro'] == '') {
		$DATA['cara13permitircargacentro'] = 1;
	}
	// -- Seccion para validar los posibles causales de error.
	$sSepara = ', ';
	if (true) {
		if ($DATA['cara01cargaasignada'] == '') {
			$sError = $ERR['cara01cargaasignada'] . $sSepara . $sError;
		}
		if ($DATA['cara01idcead'] == '') {
			$sError = $ERR['cara01idcead'] . $sSepara . $sError;
		}
		if ($DATA['cara01idzona'] == '') {
			$sError = $ERR['cara01idzona'] . $sSepara . $sError;
		}
		if ($DATA['cara13activo'] == '') {
			$sError = $ERR['cara13activo'] . $sSepara . $sError;
		}
		//Fin de las valiaciones NO LLAVE.
	}
	//Valiaciones de campos obligatorios en todo guardar.
	if ($DATA['cara13idconsejero'] == 0) {
		$sError = $ERR['cara13idconsejero'];
	}
	if ($DATA['cara13peraca'] == '') {
		$sError = $ERR['cara13peraca'];
	}
	// -- Se verifican los valores de campos de otras tablas.
	if ($sError == '') {
		if ($DATA['cara13idconsejero_doc'] != '') {
			$sError = tabla_terceros_existe($DATA['cara13idconsejero_td'], $DATA['cara13idconsejero_doc'], $objDB, 'El tercero Consejero ');
		}
		if ($sError == '') {
			list($sError, $sInfo) = tercero_Bloqueado($DATA['cara13idconsejero'], $objDB);
			if ($sInfo != '') {
				$sError = $sError . '<br>' . $sInfo;
			}
		}
	}
	$bQuitarCodigo = false;
	$sCampoCodigo = '';
	if ($sError == '') {
		if ($DATA['paso'] == 10) {
			$sSQL = 'SELECT 1 FROM cara13consejeros WHERE cara13peraca=' . $DATA['cara13peraca'] . ' AND cara13idconsejero="' . $DATA['cara13idconsejero'] . '"';
			$result = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($result) != 0) {
				$sError = $ERR['existe'];
			} else {
				list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 2, $idTercero, $objDB);
				if (!$bDevuelve) {
					$sError = $ERR['2'];
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
			$DATA['cara13id'] = tabla_consecutivo('cara13consejeros', 'cara13id', '', $objDB);
			if ($DATA['cara13id'] == -1) {
				$sError = $objDB->serror;
			}
			$DATA['cara01cargafinal'] = 0;
		}
	}
	if ($sError == '') {
		$bPasa = false;
		if ($DATA['paso'] == 10) {
			$sCampos2313 = 'cara13peraca, cara13idconsejero, cara13id, cara13activo, cara01idzona, 
			cara01idcead, cara01cargaasignada, cara01cargafinal, cara01fechafin, cara13cargacentro, 
			cara13permitircargacentro';
			$sValores2313 = '' . $DATA['cara13peraca'] . ', ' . $DATA['cara13idconsejero'] . ', ' . $DATA['cara13id'] . ', "' . $DATA['cara13activo'] . '", ' . $DATA['cara01idzona'] . ', 
			' . $DATA['cara01idcead'] . ', ' . $DATA['cara01cargaasignada'] . ', ' . $DATA['cara01cargafinal'] . ', ' . $DATA['cara01fechafin'] . ', ' . $DATA['cara13cargacentro'] . ', 
			' . $DATA['cara13permitircargacentro'] . '';
			if ($APP->utf8 == 1) {
				$sSQL = 'INSERT INTO cara13consejeros (' . $sCampos2313 . ') VALUES (' . cadena_codificar($sValores2313) . ');';
				$sdetalle = $sCampos2313 . '[' . cadena_codificar($sValores2313) . ']';
			} else {
				$sSQL = 'INSERT INTO cara13consejeros (' . $sCampos2313 . ') VALUES (' . $sValores2313 . ');';
				$sdetalle = $sCampos2313 . '[' . $sValores2313 . ']';
			}
			$idAccion = 2;
			$bPasa = true;
		} else {
			$scampo[1] = 'cara13activo';
			$scampo[2] = 'cara01idzona';
			$scampo[3] = 'cara01idcead';
			$scampo[4] = 'cara01cargaasignada';
			$scampo[5] = 'cara01fechafin';
			$scampo[6] = 'cara13permitircargacentro';
			$sdato[1] = $DATA['cara13activo'];
			$sdato[2] = $DATA['cara01idzona'];
			$sdato[3] = $DATA['cara01idcead'];
			$sdato[4] = $DATA['cara01cargaasignada'];
			$sdato[5] = $DATA['cara01fechafin'];
			$sdato[6] = $DATA['cara13permitircargacentro'];
			$numcmod = 6;
			$sWhere = 'cara13id=' . $DATA['cara13id'] . '';
			$sSQL = 'SELECT * FROM cara13consejeros WHERE ' . $sWhere;
			$sdatos = '';
			$bPrimera = true;
			$result = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($result) > 0) {
				$filabase = $objDB->sf($result);
				if ($bDebug && $bPrimera) {
					for ($k = 1; $k <= $numcmod; $k++) {
						if (isset($filabase[$scampo[$k]]) == 0) {
							$sDebug = $sDebug . fecha_microtiempo() . ' FALLA CODIGO: Falta el campo ' . $k . ' ' . $scampo[$k] . '<br>';
						}
					}
					$bPrimera = false;
				}
				$bsepara = false;
				for ($k = 1; $k <= $numcmod; $k++) {
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
					$sSQL = 'UPDATE cara13consejeros SET ' . cadena_codificar($sdatos) . ' WHERE ' . $sWhere . ';';
				} else {
					$sdetalle = $sdatos . '[' . $sWhere . ']';
					$sSQL = 'UPDATE cara13consejeros SET ' . $sdatos . ' WHERE ' . $sWhere . ';';
				}
				$idAccion = 3;
			}
		}
		if ($bPasa) {
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . ' Guardar 2313 ' . $sSQL . '<br>';
			}
			$result = $objDB->ejecutasql($sSQL);
			if ($result == false) {
				$sError = $ERR['falla_guardar'] . ' [2313] ..<!-- ' . $sSQL . ' -->';
				if ($idAccion == 2) {
					$DATA['cara13id'] = '';
					$bQuitarCodigo = true;
					$DATA['paso'] = 0;
				} else {
					$DATA['paso'] = 2;
				}
			} else {
				if ($bAudita[$idAccion]) {
					seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], $idAccion, $DATA['cara13id'], $sdetalle, $objDB);
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
	//if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' InfoDepura<br>';}
	return array($DATA, $sError, $iTipoError, $sDebug);
}
function f2313_db_Eliminar($cara13id, $objDB, $bDebug = false)
{
	$iCodModulo = 2313;
	$bAudita[4] = true;
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_2313 = 'lg/lg_2313_' . $sIdioma . '.php';
	if (!file_exists($mensajes_2313)) {
		$mensajes_2313 = 'lg/lg_2313_es.php';
	}
	require $mensajes_todas;
	require $mensajes_2313;
	$sError = '';
	$iTipoError = 0;
	$sDebug = '';
	$cara13id = numeros_validar($cara13id);
	// Traer los datos para hacer las validaciones.
	if ($sError == '') {
		$sSQL = 'SELECT * FROM cara13consejeros WHERE cara13id=' . $cara13id . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$filabase = $objDB->sf($tabla);
		} else {
			$sError = 'No se encuentra el registro solicitado {Ref: ' . $cara13id . '}';
		}
	}
	if ($sError == '') {
		if (!seg_revisa_permiso($iCodModulo, 4, $objDB)) {
			$sError = $ERR['4'];
		}
	}
	if ($sError == '') {
		$sSQL = 'SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=2313';
		$tablaor = $objDB->ejecutasql($sSQL);
		while ($filaor = $objDB->sf($tablaor)) {
			$sSQL = 'SELECT ' . $filaor['campoid'] . ' FROM ' . $filaor['tabla'] . ' WHERE ' . $filaor['camporev'] . '=' . $_REQUEST['cara13id'] . ' LIMIT 0, 1';
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
		$sWhere = 'cara13id=' . $cara13id . '';
		//$sWhere='cara13idconsejero="'.$filabase['cara13idconsejero'].'" AND cara13peraca='.$filabase['cara13peraca'].'';
		$sSQL = 'DELETE FROM cara13consejeros WHERE ' . $sWhere . ';';
		$result = $objDB->ejecutasql($sSQL);
		if ($result == false) {
			$sError = $ERR['falla_eliminar'] . ' .. <!-- ' . $sSQL . ' -->';
		} else {
			if ($bAudita[4]) {
				seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $cara13id, $sWhere, $objDB);
			}
		}
	}
	return array($sError, $iTipoError, $sDebug);
}
function f2313_TituloBusqueda()
{
	return 'Busqueda de Consejeros';
}
function f2313_ParametrosBusqueda()
{
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_2313 = 'lg/lg_2313_' . $sIdioma . '.php';
	if (!file_exists($mensajes_2313)) {
		$mensajes_2313 = 'lg/lg_2313_es.php';
	}
	require $mensajes_todas;
	require $mensajes_2313;
	$sParams = '<label class="Label90">
	' . $ETI['msg_bnombre'] . '
	</label>
	<label>
	<input id="b2313nombre" name="b2313nombre" type="text" value="" onchange="paginarbusqueda()" />
	</label>';
	return $sParams;
}
function f2313_JavaScriptBusqueda($iModuloBusca)
{
	$sRes = 'var sCampo=window.document.frmedita.scampobusca.value;
	var params=new Array();
	params[100]=sCampo;
	params[101]=window.document.frmedita.paginabusqueda.value;
	params[102]=window.document.frmedita.lppfbusqueda.value;
	params[103]=window.document.frmedita.b2313nombre.value;
	xajax_f' . $iModuloBusca . '_HtmlBusqueda(params);';
	return $sRes;
}
function f2313_TablaDetalleBusquedas($aParametros, $objDB)
{
	$res = '';
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_2313 = 'lg/lg_2313_' . $sIdioma . '.php';
	if (!file_exists($mensajes_2313)) {
		$mensajes_2313 = 'lg/lg_2313_es.php';
	}
	require $mensajes_todas;
	require $mensajes_2313;
	if (!is_array($aParametros)) {
		$aParametros = json_decode(str_replace('\"', '"', $aParametros), true);
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
	//$aParametros[103]=numeros_validar($aParametros[103]);
	$pagina = $aParametros[101];
	$lineastabla = $aParametros[102];
	$bAbierta = true;
	//$sSQL='SELECT Campo FROM Tabla WHERE Id='.$sValorId;
	//$tabla=$objDB->ejecutasql($sSQL);
	//if ($objDB->nf($tabla)>0){
	//$fila=$objDB->sf($tabla);
	//if ($fila['Campo']!='S'){$bAbierta=true;}
	//}
	$sLeyenda = '';
	if (false) {
		$sLeyenda = '<div class="salto1px"></div>
		<div class="GrupoCamposAyuda">
		<b>Importante:</b> Mensaje al usuario
		<div class="salto1px"></div>
		</div>';
	}
	$sSQLadd = '';
	$sSQLadd1 = '';
	//if ($aParametros[103]!=''){$sSQLadd1=$sSQLadd1.'TB.campo2 LIKE "%'.$aParametros[103].'%" AND ';}
	//if ($aParametros[103]!=''){$sSQLadd=$sSQLadd.' AND TB.campo2 LIKE "%'.$aParametros[103].'%"';}
	/*
	if ($aParametros[104]!=''){
		$sBase=trim(strtoupper($aParametros[104]));
		$aNoms=explode(' ', $sBase);
		for ($k=1;$k<=count($aNoms);$k++){
			$sCadena=$aNoms[$k-1];
			if ($sCadena!=''){
				$sSQLadd=$sSQLadd.' AND T6.sys11razonsocial LIKE "%'.$sCadena.'%"';
				//$sSQLadd1=$sSQLadd1.'T1.sys11razonsocial LIKE "%'.$sCadena.'%" AND ';
				}
			}
		}
	*/
	$sTitulos = 'Peraca, Consejero, Id, Activo, Zona, Cead, Cargaasignada, Cargafinal';
	$sSQL = 'SELECT T1.exte02nombre, T2.unad11razonsocial AS C2_nombre, TB.cara13id, TB.cara13activo, T5.unad23nombre, T6.unad24nombre, TB.cara01cargaasignada, TB.cara01cargafinal, TB.cara13peraca, TB.cara13idconsejero, T2.unad11tipodoc AS C2_td, T2.unad11doc AS C2_doc, TB.cara01idzona, TB.cara01idcead 
	FROM cara13consejeros AS TB, exte02per_aca AS T1, unad11terceros AS T2, unad23zona AS T5, unad24sede AS T6 
	WHERE ' . $sSQLadd1 . ' TB.cara13peraca=T1.exte02id AND TB.cara13idconsejero=T2.unad11id AND TB.cara01idzona=T5.unad23id AND TB.cara01idcead=T6.unad24id ' . $sSQLadd . '
	ORDER BY TB.cara13peraca, TB.cara13idconsejero';
	$sSQLlista = str_replace("'", "|", $sSQL);
	$sSQLlista = str_replace('"', "|", $sSQLlista);
	$sErrConsulta = '<input id="consulta_busqueda" name="consulta_busqueda" type="hidden" value="' . $sSQLlista . '"/>
	<input id="titulos_busqueda" name="titulos_busqueda" type="hidden" value="' . $sTitulos . '"/>';
	$tabladetalle = $objDB->ejecutasql($sSQL);
	if ($tabladetalle == false) {
		$registros = 0;
		$sErrConsulta = $sErrConsulta . '..<input id="err" name="err" type="hidden" value="' . $sSQL . ' ' . $objDB->serror . '"/>';
		//$sLeyenda=$sSQL;
	} else {
		$registros = $objDB->nf($tabladetalle);
		if ($registros == 0) {
			//return array(cadena_codificar($sErrConsulta.'<input id="paginaf2313" name="paginaf2313" type="hidden" value="'.$pagina.'"/><input id="lppf2313" name="lppf2313" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
		}
		if ((($registros - 1) / $lineastabla) < ($pagina - 1)) {
			$pagina = (int)(($registros - 1) / $lineastabla) + 1;
		}
		if ($registros > $lineastabla) {
			$rbase = ($pagina - 1) * $lineastabla;
			$limite = ' LIMIT ' . $rbase . ', ' . $lineastabla;
			$tabladetalle = $objDB->ejecutasql($sSQL . $limite);
		}
	}
	$res = $sErrConsulta . $sLeyenda . '<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">
	<tr class="fondoazul">
	<td><b>' . $ETI['cara13peraca'] . '</b></td>
	<td colspan="2"><b>' . $ETI['cara13idconsejero'] . '</b></td>
	<td><b>' . $ETI['cara13activo'] . '</b></td>
	<td><b>' . $ETI['cara01idzona'] . '</b></td>
	<td><b>' . $ETI['cara01idcead'] . '</b></td>
	<td><b>' . $ETI['cara01cargaasignada'] . '</b></td>
	<td><b>' . $ETI['cara01cargafinal'] . '</b></td>
	<td align="right">
	' . html_paginador('paginabusqueda', $registros, $lineastabla, $pagina, 'paginarbusqueda()') . '
	' . html_lpp('lppfbusqueda', $lineastabla, 'paginarbusqueda()') . '
	</td>
	</tr>';
	$tlinea = 1;
	while ($filadet = $objDB->sf($tabladetalle)) {
		$sPrefijo = '<a href="javascript:Devuelve(\'' . $filadet['cara13id'] . '\');">';
		$sSufijo = '</a>';
		$tlinea++;
		$et_cara13activo = $ETI['no'];
		if ($filadet['cara13activo'] == 'S') {
			$et_cara13activo = $ETI['si'];
		}
		$res = $res . '<tr onmouseover="cambia_color_over(this);" onmouseout="cambia_color_out(this);">
		<td>' . $sPrefijo . cadena_notildes($filadet['exte02nombre']) . $sSufijo . '</td>
		<td>' . $sPrefijo . $filadet['C2_td'] . ' ' . $filadet['C2_doc'] . $sSufijo . '</td>
		<td>' . $sPrefijo . cadena_notildes($filadet['C2_nombre']) . $sSufijo . '</td>
		<td>' . $sPrefijo . $et_cara13activo . $sSufijo . '</td>
		<td>' . $sPrefijo . cadena_notildes($filadet['unad23nombre']) . $sSufijo . '</td>
		<td>' . $sPrefijo . cadena_notildes($filadet['unad24nombre']) . $sSufijo . '</td>
		<td>' . $sPrefijo . $filadet['cara01cargaasignada'] . $sSufijo . '</td>
		<td align="right">' . $sPrefijo . formato_moneda($filadet['cara01cargafinal']) . $sSufijo . '</td>
		<td></td>
		</tr>';
	}
	$res = $res . '</table>';
	$objDB->liberar($tabladetalle);
	return cadena_codificar($res);
}
function f2313_ProcesarArchivo($DATA, $ARCHIVO, $objDB, $bDebug = false)
{
	$iCodModulo = 2313;
	$sError = '';
	$iTipoError = 0;
	$sInfoProceso = '';
	$sDebug = '';
	$sArchivo = $ARCHIVO['archivodatos']['tmp_name'];
	$sVerExcel = 'Excel2007';
	switch ($ARCHIVO['archivodatos']['type']) {
		case 'application/vnd.ms-excel':
			$sVerExcel = 'Excel5';
			break;
		case 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet':
			break;
		case '':
		case 'application/download':
			$sExt = pathinfo($ARCHIVO['archivodatos']['name'], PATHINFO_EXTENSION);
			switch ($sExt) {
				case '.xls':
					$sVerExcel = 'Excel5';
					break;
				case 'xlsx':
					break;
				default:
					$sError = 'Tipo de archivo no permitido {' . $ARCHIVO['archivodatos']['type'] . ' - ' . $sExt . ' - ' . $sArchivo . '}';
			}
			break;
		default:
			$sError = 'Tipo de archivo no permitido {' . $ARCHIVO['archivodatos']['type'] . '}';
	}
	if ($sError == '') {
		require './app.php';
		require $APP->rutacomun . 'excel/PHPExcel.php';
		require $APP->rutacomun . 'excel/PHPExcel/Writer/Excel2007.php';
		$objReader = PHPExcel_IOFactory::createReader($sVerExcel);
		$objPHPExcel = @$objReader->load($sArchivo);
		if (!is_object(@$objPHPExcel->getActiveSheet())) {
			$sError = 'El archivo se cargo en forma correcta, pero no fue posible leerlo en ' . $sVerExcel;
		}
	}
	if ($sError == '') {
		$sCampos2313 = 'cara13peraca, cara13idconsejero, cara13id, cara13activo, cara01idzona, cara01idcead, cara01cargaasignada, cara01cargafinal';
		$cara13id = tabla_consecutivo('cara13consejeros', 'cara13id', '', $objDB);
		$iFila = 1;
		$iDatos = 0;
		$iActualizados = 0;
		$aConsejero = array();
		$aLlaves = array();
		$iTotal = 0;
		//Alistamos consejeros
		$sSQL = 'SELECT cara13peraca, T11.unad11doc, TB.cara13idconsejero 
		FROM cara13consejeros AS TB, unad11terceros AS T11 
		WHERE TB.cara13idconsejero=T11.unad11id';
		$tabla = $objDB->ejecutasql($sSQL);
		while ($fila = $objDB->sf($tabla)) {
			$sIdArreglo = $fila['cara13peraca'] . '_' . $fila['unad11doc'];
			$iTotal++;
			$aLlaves[$iTotal] = $sIdArreglo;
			$aConsejero[$sIdArreglo]['id'] = $fila['cara13idconsejero'];
			$aConsejero[$sIdArreglo]['peraca'] = $fila['cara13peraca'];
			$aConsejero[$sIdArreglo]['ajusta'] = 0;
		}
		//$sCampos2313='cara13peraca, cara13idconsejero, cara13id, cara13activo, cara01idzona, cara01idcead, cara01cargaasignada, cara01cargafinal';
		//$cara13id=tabla_consecutivo('cara13consejeros','cara13id', '', $objDB);
		$sDato = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(0, $iFila)->getValue();
		while ($sDato != '') {
			$iDatos++;
			//Aqui se debe procesar
			$sDocConsejero = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(1, $iFila)->getValue();
			$idConsejero = 0;
			$idPeriodo = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(2, $iFila)->getValue();
			if ($idPeriodo == '') {
				if ($DATA['paso'] == 2) {
					$idPeriodo = $DATA['cara13peraca'];
					$idConsejero = $DATA['cara13idconsejero'];
				}
			}
			$sErrLinea = '';
			$sDato2 = numeros_validar($sDato);
			if ($sDato2 != $sDato) {
				$sErrLinea = 'Fila ' . $iFila . ': Documento errado';
			}
			if ($sErrLinea == '') {
				//Los terceros ya deben existir
				$sSQL = 'SELECT unad11id FROM unad11terceros WHERE unad11doc="' . $sDato . '"';
				$tabla = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($tabla) > 0) {
					$fila = $objDB->sf($tabla);
					$unad11id = $fila['unad11id'];
				} else {
					$sErrLinea = 'Fila ' . $iFila . ': Documento de estudiante NO ENCONTRADO [' . $sDato . ']';
				}
			}
			if ($sErrLinea == '') {
				if ($idConsejero == 0) {
					$sIdArreglo = $idPeriodo . '_' . $sDocConsejero;
					if (isset($aConsejero[$sIdArreglo]) == 0) {
						$sSQL = 'SELECT unad11id FROM unad11terceros WHERE unad11doc="' . $sDocConsejero . '"';
						$tabla11 = $objDB->ejecutasql($sSQL);
						if ($objDB->nf($tabla11) == 0) {
							list($sErrorI, $sDebugI) = unad11_importar_V2($sDocConsejero, '', $objDB, $bDebug);
							$sDebug = $sDebug . $sDebugI;
						}
						if ($objDB->nf($tabla11) > 0) {
							$fila11 = $objDB->sf($tabla11);
							$idConsejero = $fila11['unad11id'];
						}
						if ($idConsejero > 0) {
							$sValores2313 = '' . $idPeriodo . ', ' . $idConsejero . ', ' . $cara13id . ', "S", 0, 0, 0, 0';
							$sSQL = 'INSERT INTO cara13consejeros (' . $sCampos2313 . ') VALUES (' . $sValores2313 . ');';
							$sdetalle = $sCampos2313 . '[' . $sValores2313 . ']';
							$result = $objDB->ejecutasql($sSQL);
						} else {
							$result = false;
						}
						if ($result == false) {
							$sErrLinea = 'Fila ' . $iFila . ': No se encuentra registrado el consejero ' . $sDocConsejero . ' en el periodo ' . $idPeriodo . ' <!-- ' . $sSQL . ' -->';
						} else {
							seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 2, $cara13id, $sdetalle, $objDB);
							$cara13id++;
							$iTotal++;
							$aLlaves[$iTotal] = $sIdArreglo;
							$aConsejero[$sIdArreglo]['id'] = $idConsejero;
							$aConsejero[$sIdArreglo]['peraca'] = $idPeriodo;
							$aConsejero[$sIdArreglo]['ajusta'] = 1;
						}
					} else {
						$idConsejero = $aConsejero[$sIdArreglo]['id'];
						$aConsejero[$sIdArreglo]['ajusta'] = 1;
					}
				}
			}
			if ($sErrLinea == '') {
				//Primero dejamos el dato en la core16 si o si.
				$sSQL = 'SELECT core16id, core16idconsejero FROM core16actamatricula WHERE core16tercero=' . $unad11id . ' AND core16peraca=' . $idPeriodo . '';
				$tabla = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($tabla) > 0) {
					$fila = $objDB->sf($tabla);
					if ($fila['core16idconsejero'] != $idConsejero) {
						$sSQL = 'UPDATE core16actamatricula SET core16idconsejero=' . $idConsejero . ' WHERE core16id=' . $fila['core16id'] . '';
						$result = $objDB->ejecutasql($sSQL);
						if ($bDebug) {
							$sDebug = $sDebug . fecha_microtiempo() . ' Fila ' . $iFila . ': Se marca el registro del consejero [' . $sDato . ']<br>';
						}
						//$sErrLinea='Fila '.$iFila.': Estudiante que no ha ingresado, se marca como pendiente ['.$sDato.']';
					}
				} else {
					$sErrLinea = 'Fila ' . $iFila . ': Documento SIN ACTA DE MATRICULA [' . $sDato . '] ';
				}
				//Ver si ya tiene encuesta.
				// AND cara01idperaca='.$idPeriodo.'
				$sSQL01 = 'SELECT cara01id, cara01idconsejero, cara01idperaca, cara01idperiodoacompana, cara01fechacierreacom FROM cara01encuesta WHERE cara01idtercero=' . $unad11id . ' ORDER BY cara01idperaca DESC';
				$tabla = $objDB->ejecutasql($sSQL01);
				if ($objDB->nf($tabla) == 0) {
					//Le iniciamos la encuesta porque no tiene una...
					$sSQL = 'SELECT 1 FROM core01estprograma WHERE core01idtercero=' . $unad11id . ' AND core01peracainicial>0 ORDER BY core01peracainicial DESC';
					$result = $objDB->ejecutasql($sSQL);
					if ($objDB->nf($result) > 0) {
						f2301_IniciarEncuesta($unad11id, 0, $objDB);
						$tabla = $objDB->ejecutasql($sSQL01);
					}
				}
				if ($objDB->nf($tabla) > 0) {
					$fila = $objDB->sf($tabla);
					if ($fila['cara01idconsejero'] != $idConsejero) {
						if ($fila['cara01fechacierreacom'] == 0) {
							$sDatos = 'cara01idconsejero=' . $idConsejero . ', cara01idperiodoacompana=' . $idPeriodo . '';
							$sSQL = 'UPDATE cara01encuesta SET ' . $sDatos . ' WHERE cara01id=' . $fila['cara01id'] . '';
							$result = $objDB->ejecutasql($sSQL);
							seg_auditar(2301, $_SESSION['unad_id_tercero'], 3, $fila['cara01id'], $sDatos, $objDB);
						} else {
							if ($bDebug) {
								$sDebug = $sDebug . fecha_microtiempo() . ' Fila ' . $iFila . ': Al estudiante ya se le hizo acompa&ntilde;amiento [' . $sDato . ']<br>';
							}
						}
					} else {
						if ($bDebug) {
							$sDebug = $sDebug . fecha_microtiempo() . ' Fila ' . $iFila . ': Consejero ya asignado al estudiante [' . $sDato . ']<br>';
						}
					}
				} else {
				}
			}
			if ($sErrLinea == '') {
				$iActualizados++;
			}
			if ($sErrLinea != '') {
				if ($sInfoProceso != '') {
					$sInfoProceso = $sInfoProceso . '<br>';
				}
				$sInfoProceso = $sInfoProceso . $sErrLinea;
			}
			//$iActualizados++;
			//Leer el siguiente dato
			$iFila++;
			$sDato = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(0, $iFila)->getValue();
		}
		for ($k = 1; $k <= $iTotal; $k++) {
			$sIdArreglo = $aLlaves[$k];
			if ($aConsejero[$sIdArreglo]['ajusta'] == 1) {
				list($sErrorC, $sDebugU) = f2313_ActualizarCarga($aConsejero[$sIdArreglo]['peraca'], $objDB, $bDebug, $aConsejero[$sIdArreglo]['id']);
				$sDebug = $sDebug . $sDebugU;
			}
		}
		$sError = 'Registros totales ' . $iDatos;
		if ($iActualizados > 0) {
			$sError = $sError . ' - Registros actualizados ' . $iActualizados;
			$iTipoError = 1;
		}
	}
	return array($sError, $iTipoError, $sInfoProceso, $sDebug);
}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------
function f2313_RegistrarConsejero($idPeriodo, $idTercero, $idZona, $idCentro, $iNumEstudiantes, $objDB, $bDebug = false)
{
	$sError = '';
	$sDebug = '';
	$sSQL = 'SELECT cara13id, cara01idzona, cara01idcead, cara01cargaasignada 
	FROM cara13consejeros 
	WHERE cara13peraca=' . $idPeriodo . ' AND cara13idconsejero=' . $idTercero . '';
	$tabla13 = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla13) == 0) {
		if ($iNumEstudiantes > 0) {
			$sCampos13 = 'INSERT INTO cara13consejeros (cara13peraca, cara13idconsejero, cara13id, cara13activo, cara01idzona, cara01idcead, cara01cargaasignada, cara01cargafinal) VALUES ';
			$cara13id = tabla_consecutivo('cara13consejeros', 'cara13id', '', $objDB);
			$sSQL = $sCampos13 . '(' . $idPeriodo . ', ' . $idTercero . ', ' . $cara13id . ', "S", ' . $idZona . ', ' . $idCentro . ', ' . $iNumEstudiantes . ', 0)';
			$result = $objDB->ejecutasql($sSQL);
		}
	} else {
		$fila13 = $objDB->sf($tabla13);
		//Actualizar el dato
		$sSQL = 'UPDATE cara13consejeros SET cara01idzona=' . $idZona . ', cara01idcead=' . $idCentro . ', cara01cargaasignada=' . $iNumEstudiantes . ' WHERE cara13id=' . $fila13['cara13id'] . '';
		$result = $objDB->ejecutasql($sSQL);
	}
	return array($sError, $sDebug);
}
function f2313_ActualizarCarga($idPeriodo, $objDB, $bDebug = false, $idConsejero = 0, $bCompleta = false)
{
	require './app.php';
	$sError = '';
	$sDebug = '';
	$idEntidad = Traer_Entidad();
	$idPeridoBase = 953;
	if ($idEntidad == 1) {
		$idPeridoBase = 0;
	}
	$iFechaInactiva = 0;
	$sSQL = 'SELECT exte02fechatopetablero FROM exte02per_aca WHERE exte02id=' . $idPeriodo . '';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$iFechaInactiva = $fila['exte02fechatopetablero'];
	}
	if ($idPeriodo > $idPeridoBase) {
		//a partir del periodo 954 con la asignación de grupos, intentamos jalar la data de la tabla core20
		$sIds40 = '-99';
		$sSQL = 'SELECT unad40id FROM unad40curso WHERE unad40catedraunadista=1';
		$tabla = $objDB->ejecutasql($sSQL);
		while ($fila = $objDB->sf($tabla)) {
			$sIds40 = $sIds40 . ',' . $fila['unad40id'];
		}
		$sSQL = 'SELECT core20idtutor, core20idzona, core20idcentro, SUM(core20numestudiantes) AS Total 
		FROM core20asignacion 
		WHERE core20idperaca=' . $idPeriodo . ' AND core20idcurso IN (' . $sIds40 . ') AND core20idtutor<>0
		GROUP BY core20idtutor, core20idzona, core20idcentro';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' Tomando base de asignacion: ' . $sSQL . '<br>';
		}
		$tabla = $objDB->ejecutasql($sSQL);
		$idTercero = -1;
		$idZona = 0;
		$idCentro = 0;
		$iNumEstudiantes = 0;
		while ($fila = $objDB->sf($tabla)) {
			if ($idTercero == -1) {
				$idTercero = $fila['core20idtutor'];
			}
			if ($idTercero != $fila['core20idtutor']) {
				//Guardar el dato
				list($sError, $sDebugR) = f2313_RegistrarConsejero($idPeriodo, $idTercero, $idZona, $idCentro, $iNumEstudiantes, $objDB, $bDebug);
				$sDebug = $sDebug . $sDebugR;
				//reiniciar el contador
				$idTercero = $fila['core20idtutor'];
				$idZona = 0;
				$idCentro = 0;
				$iNumEstudiantes = 0;
			}
			if ($idZona == 0) {
				$idZona = $fila['core20idzona'];
				$idCentro = $fila['core20idcentro'];
			}
			$iNumEstudiantes = $iNumEstudiantes + $fila['Total'];
		}
		if ($iNumEstudiantes > 0) {
			list($sError, $sDebugR) = f2313_RegistrarConsejero($idPeriodo, $idTercero, $idZona, $idCentro, $iNumEstudiantes, $objDB, $bDebug);
			$sDebug = $sDebug . $sDebugR;
		}
	}
	//Ubicar los estudiantes que tengan matriculada la catedra.
	list($sError, $sDebugA) = f2216_UbicarCatedras($idPeriodo, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugA;
	//Asegurarnos de que todos los nuevos tengan consejero asignado.
	//core16idconsejero=0 AND 
	//core16formaconsejero debe llevar el id de la catedra.
	$sIds11 = '-99';
	$sSQL = 'SELECT core16id, core16tercero FROM core16actamatricula WHERE core16peraca=' . $idPeriodo . ' AND core16nuevo=1';
	$tabla = $objDB->ejecutasql($sSQL);
	while ($fila = $objDB->sf($tabla)) {
		f2334_UbicarCaracterizacion($fila['core16id'], $objDB, $bDebug, true);
		$sIds11 = $sIds11 . ',' . $fila['core16tercero'];
	}
	//Ahora organizar los estudiantes antiguos
	list($sError, $sDebugA) = f2216_UbicarConsejero($idPeriodo, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugA;
	//Ahora si sumar lo que ya esta asignado.
	$sAdd = '';
	if ($idConsejero != 0) {
		$sAdd = ' AND cara13idconsejero=' . $idConsejero . '';
	}
	$sSQL = 'SELECT cara13idconsejero, cara13id, cara01cargafinal, cara01fechafin 
	FROM cara13consejeros 
	WHERE cara13peraca=' . $idPeriodo . '' . $sAdd;
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' Revisando la asignacion por consejero: ' . $sSQL . '<br>';
	}
	$tabla = $objDB->ejecutasql($sSQL);
	while ($fila = $objDB->sf($tabla)) {
		//$sSQL='SELECT cara01idperaca, COUNT(cara01id) FROM cara01encuesta WHERE cara01idconsejero='.$fila['cara13idconsejero'].' AND cara01idperiodoacompana='.$idPeraca.' GROUP BY cara01idperaca';
		//$sSQL='SELECT cara01id FROM cara01encuesta WHERE cara01idconsejero='.$fila['cara13idconsejero'].' AND cara01idperiodoacompana='.$idPeraca.'';
		$sSQL = 'SELECT 1 FROM core16actamatricula WHERE core16idconsejero=' . $fila['cara13idconsejero'] . ' AND core16peraca=' . $idPeriodo . '';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' Revisando las actas de matricula: ' . $sSQL . '<br>';
		}
		$result = $objDB->ejecutasql($sSQL);
		$iCarga = $objDB->nf($result);
		//Ahora la carga de centro que es los que core16formaconsejero=0
		$sSQL = 'SELECT 1 FROM core16actamatricula WHERE core16idconsejero=' . $fila['cara13idconsejero'] . ' AND core16peraca=' . $idPeriodo . ' AND core16formaconsejero=0';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' Revisando las actas de matricula: ' . $sSQL . '<br>';
		}
		$result = $objDB->ejecutasql($sSQL);
		$iCargaCentro = $objDB->nf($result);

		$sCompleta = '';
		if ($fila['cara01fechafin'] == 0) {
			$sCompleta = ', cara01fechafin=' . $iFechaInactiva . '';
		}
		$sSQL = 'UPDATE cara13consejeros SET cara01cargafinal=' . $iCarga . ', cara13cargacentro=' . $iCargaCentro . $sCompleta . ' WHERE cara13id=' . $fila['cara13id'] . '';
		$result = $objDB->ejecutasql($sSQL);
	}
	if ($bCompleta) {
		//Ahora los estudiantes nuevos que NO tomaron la Catedra - Paso 1 Por CENTRO. paso 2 por ZONA
		for ($k = 1; $k <= 2; $k++) {
			$bPrimerDebug = $bDebug;
			$sSQL = 'SELECT core16idcead, core16idzona, core16id
			FROM core16actamatricula
			WHERE core16idconsejero=0 AND core16peraca=' . $idPeriodo . ' AND core16nuevo=1 AND core16estado NOT IN (9, 10)';
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . ' Estudiantes nuevos sin consejero paso ' . $k . ': ' . $sSQL . '<br>';
			}
			$tabla = $objDB->ejecutasql($sSQL);
			while ($fila = $objDB->sf($tabla)) {
				$idConsejero = 0;
				//Ubicar un consejero del centro
				// Marzo 30 de 2023 - Si la proporción de asignación, supera el 10 % ya no se hace por centro sino que se hace por zona.
				$sCondi = 'cara01idcead=' . $fila['core16idcead'] . ' AND  AND cara01cargafinal/cara01cargaasignada<1.1';
				if ($k == 2) {
					$sCondi = 'cara01idzona=' . $fila['core16idzona'];
				}
				$sSQL = 'SELECT cara13id, cara13idconsejero, cara01cargafinal, cara13cargacentro  
				FROM cara13consejeros 
				WHERE ' . $sCondi . ' AND cara13peraca=' . $idPeriodo . ' AND cara13permitircargacentro=1 
				ORDER BY cara01cargafinal/cara01cargaasignada
				LIMIT 0, 1';
				$tabla13 = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($tabla13) > 0) {
					$fila13 = $objDB->sf($tabla13);
					$idConsejero = $fila13['cara13idconsejero'];
					$iCarga = $fila13['cara01cargafinal'] + 1;
					$iCargaCentro = $fila13['cara13cargacentro'] + 1;
					$sSQL = 'UPDATE cara13consejeros SET cara01cargafinal=' . $iCarga . ', cara13cargacentro=' . $iCargaCentro . ' WHERE cara13id=' . $fila13['cara13id'] . '';
					$result = $objDB->ejecutasql($sSQL);
				}
				if ($idConsejero != 0) {
					$sSQL = 'UPDATE core16actamatricula SET core16idconsejero=' . $idConsejero . ' WHERE core16id=' . $fila['core16id'] . '';
					$result = $objDB->ejecutasql($sSQL);
				}
			}
		}
	}
	return array($sError, $sDebug);
}
