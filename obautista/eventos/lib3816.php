<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2023 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.29.3 martes, 4 de abril de 2023
--- 3816 cipa02jornada
*/
/** Archivo lib3816.php.
 * Libreria 3816 cipa02jornada.
 * @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
 * @date martes, 4 de abril de 2023
 */
function f3816_HTMLComboV2_cipa01alcance($objDB, $objCombos, $valor)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$objCombos->nuevo('cipa01alcance', $valor, true, '{' . $ETI['msg_seleccione'] . '}');
	//$objCombos->iAncho = 450;
	$objCombos->sAccion = 'paginarf3816();';
	$sSQL = 'SELECT cipa13id AS id, cipa13nombre AS nombre FROM cipa13alcance';
	$res = $objCombos->html($sSQL, $objDB);
	return $res;
}
function f3816_HTMLComboV2_cipa01clase($objDB, $objCombos, $valor)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$objCombos->nuevo('cipa01clase', $valor, true, '{' . $ETI['msg_seleccione'] . '}');
	//$objCombos->iAncho = 450;
	$objCombos->sAccion = 'paginarf3816();';
	$sSQL = 'SELECT cipa14id AS id, cipa14nombre AS nombre FROM cipa14clasecipas';
	$res = $objCombos->html($sSQL, $objDB);
	return $res;
}
function f3816_HTMLComboV2_cipa01programa($objDB, $objCombos, $valor, $vrcipa01escuela)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$objCombos->nuevo('cipa01programa', $valor, true, '{' . $ETI['msg_seleccione'] . '}');
	//$objCombos->iAncho = 450;
	$objCombos->sAccion = 'paginarf3816();';
	$sSQL = '';
	if ((int)$vrcipa01escuela != 0) {
		$sSQL = 'SELECT core09id AS id, core09nombre AS nombre FROM core09programa WHERE core09idescuela=' . $vrcipa01escuela . '';
	}
	$res = $objCombos->html($sSQL, $objDB);
	return $res;
}
function f3816_HTMLComboV2_cipa01centro($objDB, $objCombos, $valor, $vrcipa01zona)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$objCombos->nuevo('cipa01centro', $valor, true, '{' . $ETI['msg_seleccione'] . '}');
	//$objCombos->iAncho = 450;
	$objCombos->sAccion = 'paginarf3816();';
	$sSQL = '';
	if ((int)$vrcipa01zona != 0) {
		$sSQL = 'SELECT unad24id AS id, unad24nombre AS nombre FROM unad24sede WHERE unad24idzona=' . $vrcipa01zona . '';
	}
	$res = $objCombos->html($sSQL, $objDB);
	return $res;
}
function f3816_HTMLComboV2_cipa01idcurso($objDB, $objCombos, $valor, $vrcipa01periodo)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$objCombos->nuevo('cipa01idcurso', $valor, true, '{' . $ETI['msg_seleccione'] . '}');
	//$objCombos->iAncho = 450;
	$objCombos->sAccion = 'paginarf3816();';
	$sSQL = '';
	if ((int)$vrcipa01periodo != 0) {
		$sSQL = 'SELECT TB.ofer08id AS id, T40.unad40nombre AS nombre FROM ofer08oferta AS TB, unad40curso AS T40 WHERE TB.ofer08idcurso=T40.unad40id AND TB.ofer08idper_aca=' . $vrcipa01periodo . '';
	}
	$res = $objCombos->html($sSQL, $objDB);
	return $res;
}
function f3816_Combocipa01programa($aParametros)
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
	$html_cipa01programa = f3816_HTMLComboV2_cipa01programa($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_cipa01programa', 'innerHTML', $html_cipa01programa);
	//$objResponse->call('$("#cipa01programa").chosen()');
	$objResponse->call('paginarf3816()');
	return $objResponse;
}
function f3816_Combocipa01centro($aParametros)
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
	$html_cipa01centro = f3816_HTMLComboV2_cipa01centro($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_cipa01centro', 'innerHTML', $html_cipa01centro);
	//$objResponse->call('$("#cipa01centro").chosen()');
	$objResponse->call('paginarf3816()');
	return $objResponse;
}
function f3816_Combocipa01idcurso($aParametros)
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
	$html_cipa01idcurso = f3816_HTMLComboV2_cipa01idcurso($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_cipa01idcurso', 'innerHTML', $html_cipa01idcurso);
	//$objResponse->call('$("#cipa01idcurso").chosen()');
	$objResponse->call('paginarf3816()');
	return $objResponse;
}
function f3816_Busquedas($aParametros)
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
	$mensajes_3816 = 'lg/lg_3816_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_3816)) {
		$mensajes_3816 = 'lg/lg_3816_es.php';
	}
	require $mensajes_todas;
	require $mensajes_3816;
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
	}
	$sTitulo = '<h2>' . $ETI['titulo_3816'] . ' - ' . $sTitulo . '</h2>';
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_97titulo', 'innerHTML', $sTitulo);
	$objResponse->assign('div_97params', 'innerHTML', $sParams);
	$objResponse->assign('div_97tabla', 'innerHTML', $sTabla);
	$objResponse->setFunction('paginarbusqueda', '', $sJavaBusqueda);
	$objResponse->call('expandesector(97)');
	return $objResponse;
}
function f3816_HtmlBusqueda($aParametros)
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
function f3816_TablaDetalleV2($aParametros, $objDB, $bDebug = false)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	/*
	$mensajes_3800 = 'lg/lg_3800_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_3800)) {
		$mensajes_3800 = 'lg/lg_3800_es.php';
	}
	require $mensajes_3800;
	*/
	$mensajes_3816 = 'lg/lg_3816_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_3816)) {
		$mensajes_3816 = 'lg/lg_3816_es.php';
	}
	$mensajes_3802 = 'lg/lg_3802_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_3802)) {
		$mensajes_3802 = 'lg/lg_3802_es.php';
	}
	require $mensajes_3816;
	require $mensajes_3802;
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
	for ($k = 103; $k <= 114; $k++) {
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
		$cipa01periodo = $aParametros[103];
		$cipa01alcance = $aParametros[104];
		$cipa01clase = $aParametros[105];
		$cipa01estado = $aParametros[106];
		$cipa01escuela = $aParametros[107];
		$cipa01programa = $aParametros[108];
		$cipa01zona = $aParametros[109];
		$cipa01centro = $aParametros[110];
		$cipa01idcurso = $aParametros[111];
		$cipa02forma = $aParametros[112];
		$cipa02fecha = $aParametros[113];
		$cipa02fechafin = $aParametros[114];
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
	$sBotones = '<input id="paginaf3816" name="paginaf3816" type="hidden" value="' . $pagina . '"/>
	<input id="lppf3816" name="lppf3816" type="hidden" value="' . $lineastabla . '"/>';
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
		
		if ($cipa01periodo != '') {
			$sSQLadd1 = $sSQLadd1 . 'T1.cipa01periodo=' . $cipa01periodo . ' AND ';
		}
			if ($cipa01alcance != '') {
			$sSQLadd1 = $sSQLadd1 . 'T1.cipa01alcance=' . $cipa01alcance . ' AND ';
		}
		if ($cipa01clase != '') {
			$sSQLadd1 = $sSQLadd1 . 'T1.cipa01clase=' . $cipa01clase . ' AND ';
		}
		if ($cipa01estado != '') {
			$sSQLadd1 = $sSQLadd1 . 'T1.cipa01estado=' . $cipa01estado . ' AND ';
		}
		if ($cipa01escuela != '') {
			$sSQLadd1 = $sSQLadd1 . 'T1.cipa01escuela=' . $cipa01escuela . ' AND ';
		}
		if ($cipa01programa != '') {
			$sSQLadd1 = $sSQLadd1 . 'T1.cipa01programa=' . $cipa01programa . ' AND ';
		}
		if ($cipa01zona != '') {
			$sSQLadd1 = $sSQLadd1 . 'T1.cipa01zona=' . $cipa01zona . ' AND ';
		}
		if ($cipa01centro != '') {
			$sSQLadd1 = $sSQLadd1 . 'T1.cipa01centro=' . $cipa01centro . ' AND ';
		}
		if ($cipa01idcurso != '') {
			$sSQLadd1 = $sSQLadd1 . 'T1.cipa01idcurso=' . $cipa01idcurso . ' AND ';
		}
		if ($cipa02forma != '') {
			$sSQLadd1 = $sSQLadd1 . 'TB.cipa02forma=' . $cipa02forma . ' AND ';
		}
		if ($cipa02fecha != '') {
			$sSQLadd1 = $sSQLadd1 . 'TB.cipa02fecha>=' . $cipa02fecha . ' AND ';
		}
		if ($cipa02fechafin != '') {
			$sSQLadd1 = $sSQLadd1 . 'TB.cipa02fecha<=' . $cipa02fechafin . ' AND ';
		}
		/*
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
	$sTitulos = 'Periodo, Alcance, Clase, Estado, Escuela, Programa, Zona, Centro, Curso, Forma, Fechaini, Fechafin';
	$registros = 0;
	$bGigante = false; //En caso de que la tabla sea muy grande pasarlo a true
	$sLimite = '';
	$sCampos = 'SELECT TB.cipa02consec, T2.exte02nombre, T12.core12sigla, TB.cipa02forma, TB.cipa02lugar, TB.cipa02link, T9.core09nombre, T40.unad40nombre, T23.unad23nombre, T24.unad24nombre, T13.cipa13nombre, T14.cipa14nombre, T11.cipa11nombre, TB.cipa02forma, TB.cipa02fecha, TB.cipa02horaini, TB.cipa02minini, TB.cipa02horafin, TB.cipa02minfin, TB.cipa02numinscritos, TB.cipa02numparticipantes, TB.cipa02tematica';
	$sConsulta = ' FROM exte02per_aca AS T2, cipa02jornada AS TB, cipa01oferta AS T1, core12escuela AS T12, core09programa AS T9, unad40curso AS T40, unad23zona AS T23, unad24sede AS T24, cipa13alcance AS T13, cipa14clasecipas AS T14, cipa11estado AS T11
	WHERE ' . $sSQLadd1 . ' TB.cipa02idoferta=T1.cipa01id AND T1.cipa01periodo=T2.exte02id AND T1.cipa01escuela=T12.core12id AND T1.cipa01programa=T9.core09id AND T1.cipa01idcurso=T40.unad40id AND T1.cipa01zona=T23.unad23id AND T1.cipa01centro=T24.unad24id AND T1.cipa01alcance=T13.cipa13id AND T1.cipa01clase=T14.cipa14id AND T1.cipa01estado=T11.cipa11id ' . $sSQLadd . '';
	$sOrden = 'ORDER BY TB.cipa02fecha, TB.cipa02horaini, TB.cipa02minini';
	/*
	$sCampos = 'SELECT TB.cipa02consec, TB.cipa02forma, TB.cipa02lugar, TB.cipa02link, TB.cipa02fecha, TB.cipa02horaini, 
	TB.cipa02minini, TB.cipa02horafin, TB.cipa02minfin, TB.cipa02numinscritos, TB.cipa02numparticipantes, TB.cipa02tematica';
	$sConsulta = 'FROM cipa02jornada AS TB, cipa01oferta AS T1 
	WHERE ' . $sSQLadd1 . ' TB.cipa02idoferta=T1.cipa01id ' . $sSQLadd . '';
	$sOrden = 'ORDER BY TB.cipa02fecha, TB.cipa02horaini, TB.cipa02minini';
	*/
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
	$sErrConsulta = '<input id="consulta_3816" name="consulta_3816" type="hidden" value="' . $sSQLlista . '"/>
	<input id="titulos_3816" name="titulos_3816" type="hidden" value="' . $sTitulos . '"/>';
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' Consulta 3816: ' . $sSQL . '<br>';
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
	<td><b>' . $ETI['cipa02fecha'] . '</b></td>
	<td><b>' . $ETI['cipa01periodo'] . '</b></td>
	<td><b>' . $ETI['cipa01escuela'] . '</b></td>
	<td><b>' . $ETI['cipa01programa'] . '</b></td>
	<td><b>' . $ETI['cipa01idcurso'] . '</b></td>
	<td><b>' . $ETI['cipa01zona'] . '</b></td>
	<td><b>' . $ETI['cipa01centro'] . '</b></td>
	<td><b>' . $ETI['cipa01alcance'] . '</b></td>
	<td><b>' . $ETI['cipa01clase'] . '</b></td>
	<td><b>' . $ETI['cipa01estado'] . '</b></td>
	<td align="right">
	' . html_paginador('paginaf3816', $registros, $lineastabla, $pagina, 'paginarf3816()') . '
	' . html_lpp('lppf3816', $lineastabla, 'paginarf3816()') . '
	</td>
	</tr></thead>';
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
		/*
		$et_cipa01periodo = $sPrefijo . cadena_notildes($filadet['exte02nombre']) . $sSufijo;
		$et_cipa01alcance = $sPrefijo . cadena_notildes($filadet['cipa13nombre']) . $sSufijo;
		$et_cipa01clase = $sPrefijo . cadena_notildes($filadet['cipa14nombre']) . $sSufijo;
		$et_cipa01estado = $sPrefijo . cadena_notildes($filadet['cipa11nombre']) . $sSufijo;
		$et_cipa01escuela = $sPrefijo . cadena_notildes($filadet['core12nombre']) . $sSufijo;
		$et_cipa01programa = $sPrefijo . cadena_notildes($filadet['core09nombre']) . $sSufijo;
		$et_cipa01zona = $sPrefijo . cadena_notildes($filadet['unad23nombre']) . $sSufijo;
		$et_cipa01centro = $sPrefijo . cadena_notildes($filadet['unad24nombre']) . $sSufijo;
		$et_cipa01idcurso = $sPrefijo . cadena_notildes($filadet['unad40nombre']) . $sSufijo;
		$et_cipa02forma = $sPrefijo . cadena_notildes($filadet['']) . $sSufijo;
		*/
		$et_cipa02fecha = '';
		if ($filadet['cipa02fecha'] != 0) {
			$et_cipa02fecha = $sPrefijo . fecha_desdenumero($filadet['cipa02fecha']) . $sSufijo;
		}
		if ($bAbierta) {
			//$sLink = '<a href="javascript:cargadato('."'".''."'" . ')" class="lnkresalte">' . $ETI['lnk_cargar'] . '</a>';
		}
		/* 
		<td>' . $sPrefijo . cadena_notildes($filadet['cipa13nombre']) . $sSufijo . '</td>
		<td>' . $sPrefijo . cadena_notildes($filadet['cipa14nombre']) . $sSufijo . '</td>
		<td>' . $sPrefijo . cadena_notildes($filadet['cipa11nombre']) . $sSufijo . '</td>
		<td>' . $sPrefijo . cadena_notildes($filadet['core12nombre']) . $sSufijo . '</td>
		<td>' . $sPrefijo . cadena_notildes($filadet['core09nombre']) . $sSufijo . '</td>
		<td>' . $sPrefijo . cadena_notildes($filadet['unad23nombre']) . $sSufijo . '</td>
		<td>' . $sPrefijo . cadena_notildes($filadet['unad24nombre']) . $sSufijo . '</td>
		<td>' . $sPrefijo . cadena_notildes($filadet['unad40nombre']) . $sSufijo . '</td>
		*/
		$res = $res . '<tr' . $sClass . '>
		<td>' . $sPrefijo . $et_cipa02fecha . $sSufijo . '</td>
		<td>' . $sPrefijo . ($filadet['exte02nombre']) . $sSufijo . '</td>
		<td>' . $sPrefijo . ($filadet['core12sigla']) . $sSufijo . '</td>
		<td>' . $sPrefijo . ($filadet['core09nombre']) . $sSufijo . '</td>
		<td>' . $sPrefijo . ($filadet['unad40nombre']) . $sSufijo . '</td>
		<td>' . $sPrefijo . ($filadet['unad23nombre']) . $sSufijo . '</td>
		<td>' . $sPrefijo . ($filadet['unad24nombre']) . $sSufijo . '</td>
		<td>' . $sPrefijo . ($filadet['cipa13nombre']) . $sSufijo . '</td>
		<td>' . $sPrefijo . ($filadet['cipa14nombre']) . $sSufijo . '</td>
		<td>' . $sPrefijo . ($filadet['cipa11nombre']) . $sSufijo . '</td>
		<td>' . $sPrefijo . ($acipa02forma[$filadet['cipa02forma']]) . $sSufijo . '</td>
		</tr>';
	}
	$res = $res . '</table>
	<div class="salto5px"></div>
	</div>';
	$objDB->liberar($tabladetalle);
	return array(cadena_codificar($res), $sDebug);
}
function f3816_HtmlTabla($aParametros)
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
	list($sDetalle, $sDebugTabla) = f3816_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_f3816detalle', 'innerHTML', $sDetalle);
	if ($bDebug) {
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
	}
	return $objResponse;
}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------

