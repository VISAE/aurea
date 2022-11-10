<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2019 - 2020 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.22.7 miércoles, 20 de marzo de 2019
--- Modelo Versión 2.24.1 martes, 11 de febrero de 2020
--- Modelo Versión 2.25.0 martes, 17 de marzo de 2020
--- Modelo Versión 2.25.6 jueves, 10 de septiembre de 2020
--- Modelo Versión 2.28.2 viernes, 17 de junio de 2022
--- 1527 bita27equipotrabajo
*/

/** Archivo lib1527.php.
 * Libreria 1527 bita27equipotrabajo.
 * @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
 * @date martes, 11 de febrero de 2020
 */
function f1527_HTMLComboV2_bita27cead($objDB, $objCombos, $valor, $vrbita27idzona)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$objCombos->nuevo('bita27cead', $valor, true, '{' . $ETI['msg_todos'] . '}', 0);
	//$objCombos->iAncho=450;
	$sSQL = '';
	if ((int)$vrbita27idzona != 0) {
		$sCondi = ' WHERE unad24idzona=' . $vrbita27idzona . '';
		$sSQL = 'SELECT unad24id AS id, unad24nombre AS nombre 
		FROM unad24sede 
		WHERE unad24idzona=' . $vrbita27idzona . ' AND unad24id>0
		ORDER BY unad24activa DESC, unad24nombre';
	}
	$res = $objCombos->html($sSQL, $objDB);
	return $res;
}
function f1527_HTMLComboV2_bita27idprograma($objDB, $objCombos, $valor, $vrbita27idescuela)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$objCombos->nuevo('bita27idprograma', $valor, true, '{' . $ETI['msg_todos'] . '}', 0);
	//$objCombos->iAncho=450;
	$sSQL = '';
	if ((int)$vrbita27idescuela != 0) {
		$sSQL = 'SELECT core09id AS id, CONCAT(core09nombre, " [", core09codigo, "]") AS nombre 
		FROM core09programa
		WHERE core09idescuela=' . $vrbita27idescuela . '
		ORDER BY core09nombre';
	}
	$res = $objCombos->html($sSQL, $objDB);
	return $res;
}
function f1527_Combobita27cead($aParametros)
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
	$html_bita27cead = f1527_HTMLComboV2_bita27cead($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_bita27cead', 'innerHTML', $html_bita27cead);
	//$objResponse->call('$("#bita27cead").chosen()');
	return $objResponse;
}
function f1527_Combobita27idprograma($aParametros)
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
	$html_bita27idprograma = f1527_HTMLComboV2_bita27idprograma($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_bita27idprograma', 'innerHTML', $html_bita27idprograma);
	$objResponse->call('$("#bita27idprograma").chosen()');
	return $objResponse;
}
function f1527_ExisteDato($datos)
{
	if (!is_array($datos)) {
		$datos = json_decode(str_replace('\"', '"', $datos), true);
	}
	$_SESSION['u_ultimominuto'] = iminutoavance();
	$bHayLlave = true;
	$bita27consec = numeros_validar($datos[1]);
	if ($bita27consec == '') {
		$bHayLlave = false;
	}
	if ($bita27consec == 0) {
		$bHayLlave = false;
	}
	if ($bHayLlave) {
		require './app.php';
		$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto != '') {
			$objDB->dbPuerto = $APP->dbpuerto;
		}
		$objDB->xajax();
		$sSQL = 'SELECT 1 FROM bita27equipotrabajo WHERE bita27consec=' . $bita27consec . '';
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
function f1527_Busquedas($aParametros)
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
	$mensajes_1527 = 'lg/lg_1527_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_1527)) {
		$mensajes_1527 = 'lg/lg_1527_es.php';
	}
	require $mensajes_todas;
	require $mensajes_1527;
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
		case 'bita27idlider':
			require $APP->rutacomun . 'lib111.php';
			$sTabla = f111_TablaDetalleBusquedas($aParametrosB, $objDB);
			$sTitulo = 'Busqueda de terceros';
			$sParams = f111_ParametrosBusqueda();
			$sJavaBusqueda = f111_JavaScriptBusqueda(1527);
			break;
		case 'bita27propietario':
			require $APP->rutacomun . 'lib111.php';
			$sTabla = f111_TablaDetalleBusquedas($aParametrosB, $objDB);
			$sTitulo = 'Busqueda de terceros';
			$sParams = f111_ParametrosBusqueda();
			$sJavaBusqueda = f111_JavaScriptBusqueda(1527);
			break;
		case 'bita28idtercero':
			require $APP->rutacomun . 'lib111.php';
			$sTabla = f111_TablaDetalleBusquedas($aParametrosB, $objDB);
			$sTitulo = 'Busqueda de terceros';
			$sParams = f111_ParametrosBusqueda();
			$sJavaBusqueda = f111_JavaScriptBusqueda(1527);
			break;
	}
	$sTitulo = '<h2>' . $ETI['titulo_1527'] . ' - ' . $sTitulo . '</h2>';
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_97titulo', 'innerHTML', $sTitulo);
	$objResponse->assign('div_97params', 'innerHTML', $sParams);
	$objResponse->assign('div_97tabla', 'innerHTML', $sTabla);
	$objResponse->setFunction('paginarbusqueda', '', $sJavaBusqueda);
	$objResponse->call('expandesector(97)');
	return $objResponse;
}
function f1527_HtmlBusqueda($aParametros)
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
		case 'bita27idlider':
			require $APP->rutacomun . 'lib111.php';
			$sDetalle = f111_TablaDetalleBusquedas($aParametros, $objDB);
			break;
		case 'bita27propietario':
			require $APP->rutacomun . 'lib111.php';
			$sDetalle = f111_TablaDetalleBusquedas($aParametros, $objDB);
			break;
		case 'bita28idtercero':
			require $APP->rutacomun . 'lib111.php';
			$sDetalle = f111_TablaDetalleBusquedas($aParametros, $objDB);
			break;
	}
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_97tabla', 'innerHTML', $sDetalle);
	return $objResponse;
}
function f1527_TablaDetalleV2($aParametros, $objDB, $bDebug = false)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$mensajes_1527 = 'lg/lg_1527_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_1527)) {
		$mensajes_1527 = 'lg/lg_1527_es.php';
	}
	require $mensajes_1527;
	if (!is_array($aParametros)) {
		$aParametros = json_decode(str_replace('\"', '"', $aParametros), true);
	}
	if (isset($aParametros[98]) == 0) {
		$aParametros[98] = '';
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
	if (isset($aParametros[105]) == 0) {
		$aParametros[105] = '';
	}
	if (isset($aParametros[106]) == 0) {
		$aParametros[106] = '';
	}
	if (isset($aParametros[107]) == 0) {
		$aParametros[107] = '';
	}
	//$aParametros[103]=numeros_validar($aParametros[103]);
	$idLider = numeros_validar($aParametros[98]);
	$idTercero = numeros_validar($aParametros[100]);
	$sDebug = '';
	if (true) {
		//Leemos los parametros de entrada.
	$pagina = $aParametros[101];
	$lineastabla = $aParametros[102];
	$idUnidadFuncional = $aParametros[104];
	$sDoc = $aParametros[105];
	$sNombre = trim($aParametros[106]);
	$idNivelRespuesta = $aParametros[107];
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
	$sBotones = '<input id="paginaf1527" name="paginaf1527" type="hidden" value="' . $pagina . '"/>
	<input id="lppf1527" name="lppf1527" type="hidden" value="' . $lineastabla . '"/>';
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
	$bConLider = false;
	if (true) {
		//Esta condición la ponemos para mantener la conparación con los arhcivos tipo e
		$sSQLadd = '';
		$sSQLadd1 = '';
		if ($idLider != '') {
			$sSQLadd1 = $sSQLadd1 . '((TB.bita27idlider=' . $idLider . ') OR (TB.bita27propietario=' . $idLider . ')) AND ';
			$bConLider = true;
		}
	//Los integrantes<br />
	$bFiltraIntegrantes = false;
	$sCondiIntegrante = '';
	if ($sDoc != '') {
		$bFiltraIntegrantes = true;
		$sCondiIntegrante = ' AND T11.unad11doc LIKE "%' . $sDoc . '%"';
	}
	if ($sNombre != '') {
		$bFiltraIntegrantes = true;
		$sBase = trim(strtoupper($sNombre));
		$aNoms = explode(' ', $sBase);
		for ($k = 1; $k <= count($aNoms); $k++) {
			$sCadena = $aNoms[$k - 1];
			if ($sCadena != '') {
				$sCondiIntegrante = $sCondiIntegrante . ' AND T11.unad11razonsocial LIKE "%' . $sCadena . '%"';
			}
		}
	}
	if ($bFiltraIntegrantes) {
		$sId27 = '-99';
		$sSQL = 'SELECT TB.bita28idequipotrab 
		FROM bita28eqipoparte AS TB, unad11terceros AS T11 
		WHERE TB.bita28idtercero=T11.unad11id ' . $sCondiIntegrante . '
		GROUP BY TB.bita28idequipotrab';
		$tabla = $objDB->ejecutasql($sSQL);
		while ($fila = $objDB->sf($tabla)) {
			$sId27 = $sId27 . ',' . $fila['bita28idequipotrab'];
		}
		$sSQLadd1 = $sSQLadd1 . 'TB.bita27id IN (' . $sId27 . ') AND ';
	}
	//Fin de busqueda de integrantes.
	if ($idUnidadFuncional != '') {
		$sSQLadd1 = $sSQLadd1 . 'TB.bita27idunidadfunc=' . $idUnidadFuncional . ' AND ';
	}
	if ($idNivelRespuesta != '') {
		$sSQLadd1 = $sSQLadd1 . 'TB.bita27nivelrespuesta=' . $idNivelRespuesta . ' AND ';
	}
	if ($aParametros[103] != '') {
		//$sSQLadd1=$sSQLadd1.'TB.bita27nombre LIKE "%'.$aParametros[103].'%" AND ';
		$sBase = trim(strtoupper($aParametros[103]));
		$aNoms = explode(' ', $sBase);
		for ($k = 1; $k <= count($aNoms); $k++) {
			$sCadena = $aNoms[$k - 1];
			if ($sCadena != '') {
				$sSQLadd1 = $sSQLadd1 . 'TB.bita27nombre LIKE "%' . $sCadena . '%" AND ';
				}
			}
		}
	}
	//-- Area para saltar comparaciones con los archivos tipo e
	//-- Fin del area no comparada
	$sTitulos = 'Consec, Id, Nombre, Lider, Perfil';
	$registros = 0;
	$bGigante = false; //En caso de que la tabla sea muy grande pasarlo a true
	$sLimite = '';
	if ($bGigante) {
		$sSQL = 'SELECT COUNT(1) AS Total 
		FROM bita27equipotrabajo AS TB, unad11terceros AS T4, unad05perfiles AS T5, unae26unidadesfun AS T7, saiu17nivelatencion AS T8, unad23zona AS T9, unad24sede AS T10 
		WHERE ' . $sSQLadd1 . ' TB.bita27idlider=T4.unad11id AND TB.bita27idperfil=T5.unad05id AND TB.bita27idunidadfunc=T7.unae26id AND TB.bita27nivelrespuesta=T8.saiu17id AND TB.bita27idzona=T9.unad23id AND TB.bita27cead=T10.unad24id ' . $sSQLadd . '';
		$tabladetalle = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabladetalle) > 0) {
			$fila = $objDB->sf($tabladetalle);
			$registros = $fila['Total'];
		}
		if ((($registros - 1) / $lineastabla) < ($pagina - 1)) {
			$pagina = (int)(($registros - 1) / $lineastabla) + 1;
		}
		if ($registros > $lineastabla) {
			$rbase = ($pagina - 1) * $lineastabla;
			$sLimite = ' LIMIT ' . $rbase . ', ' . $lineastabla;
		}
	}
	$sSQL = 'SELECT TB.bita27consec, TB.bita27id, TB.bita27nombre, T4.unad11razonsocial AS C4_nombre, TB.bita27idlider, 
	T4.unad11tipodoc AS C4_td, T4.unad11doc AS C4_doc, TB.bita27idperfil, TB.bita27activo 
	FROM bita27equipotrabajo AS TB, unad11terceros AS T4 
	WHERE ' . $sSQLadd1 . ' TB.bita27id>0 AND TB.bita27idlider=T4.unad11id ' . $sSQLadd . '
	ORDER BY TB.bita27activo DESC, TB.bita27nombre';
	$sSQLlista = str_replace("'", "|", $sSQL);
	$sSQLlista = str_replace('"', "|", $sSQLlista);
	$sErrConsulta = '<input id="consulta_1527" name="consulta_1527" type="hidden" value="' . $sSQLlista . '"/>
	<input id="titulos_1527" name="titulos_1527" type="hidden" value="' . $sTitulos . '"/>';
	$tabladetalle = $objDB->ejecutasql($sSQL . $sLimite);
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' Consulta 1527: ' . $sSQL . $sLimite . '<br>';
	}
	if ($tabladetalle == false) {
		$registros = 0;
		$sErrConsulta = $sErrConsulta . '..<input id="err" name="err" type="hidden" value="' . $sSQL . ' ' . $objDB->serror . '"/>';
		//$sLeyenda = $sSQL;
	} else {
		if (!$bGigante) {
			$registros = $objDB->nf($tabladetalle);
			//if ($registros == 0) {
				//return array($sErrConsulta . $sBotones, $sDebug);
				//}
			if ((($registros - 1) / $lineastabla) < ($pagina - 1)) {
				$pagina = (int)(($registros - 1) / $lineastabla) + 1;
			}
			if ($registros > $lineastabla) {
				$rbase = ($pagina - 1) * $lineastabla;
				$sLimite = ' LIMIT ' . $rbase . ', ' . $lineastabla;
				$tabladetalle = $objDB->ejecutasql($sSQL . $sLimite);
			}
		}
	}
	$sTituloLider = '<td colspan="2"><b>' . $ETI['bita27idlider'] . '</b></td>';
	if ($bConLider) {
		$sTituloLider = '';
	}
	$res = $sErrConsulta . $sLeyenda;
	$res = $res . '<div class="table-responsive">
	<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">
	<thead class="fondoazul"><tr>
	<td><b>' . $ETI['bita27consec'] . '</b></td>
	<td><b>' . $ETI['bita27nombre'] . '</b></td>
	<td><b>' . $ETI['bita27activo'] . '</b></td>' . $sTituloLider . '
	<td align="right">
	' . html_paginador('paginaf1527', $registros, $lineastabla, $pagina, 'paginarf1527()') . '
	' . html_lpp('lppf1527', $lineastabla, 'paginarf1527()') . '
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
		$sInfoLider = '';
		if (!$bConLider) {
			$et_bita27idlider_doc = '';
			$et_bita27idlider_nombre = '';
			if ($filadet['bita27idlider'] != 0) {
				$et_bita27idlider_doc = $sPrefijo . $filadet['C4_td'] . ' ' . $filadet['C4_doc'] . $sSufijo;
				$et_bita27idlider_nombre = $sPrefijo . cadena_notildes($filadet['C4_nombre']) . $sSufijo;
			}
			$sInfoLider = '<td>' . $et_bita27idlider_doc . '</td>
			<td>' . $et_bita27idlider_nombre . '</td>';
		}
		$et_bita27activo = $sPrefijo . $ETI['no'] . $sSufijo;
		if ($filadet['bita27activo'] == 1) {
			$et_bita27activo = $sPrefijo . $ETI['si'] . $sSufijo;
		}
		if ($bAbierta) {
			$sLink = '<a href="javascript:cargaridf1527(' . $filadet['bita27id'] . ')" class="lnkresalte">' . $ETI['lnk_cargar'] . '</a>';
		}
		$res = $res . '<tr' . $sClass . '>
		<td>' . $sPrefijo . $filadet['bita27consec'] . $sSufijo . '</td>
		<td>' . $sPrefijo . cadena_notildes($filadet['bita27nombre']) . $sSufijo . '</td>
		<td>' . $sPrefijo . $et_bita27activo . $sSufijo . '</td>' . $sInfoLider . '
		<td>' . $sLink . '</td>
		</tr>';
	}
	$res = $res . '</table>
	<div class="salto5px"></div>
	</div>';
	$objDB->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
}
function f1527_HtmlTabla($aParametros)
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
	list($sDetalle, $sDebugTabla) = f1527_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_f1527detalle', 'innerHTML', $sDetalle);
	if ($bDebug) {
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
	}
	return $objResponse;
}
function f1527_db_CargarPadre($DATA, $objDB, $bDebug = false)
{
	$sError = '';
	$iTipoError = 0;
	$sDebug = '';
	require './app.php';
	$DATA['bita27idlider_td'] = $APP->tipo_doc;
	$DATA['bita27idlider_doc'] = '';
	$DATA['bita27propietario_td'] = $APP->tipo_doc;
	$DATA['bita27propietario_doc'] = '';
	if ($DATA['paso'] == 1) {
		$sSQLcondi = 'bita27consec=' . $DATA['bita27consec'] . '';
	} else {
		$sSQLcondi = 'bita27id=' . $DATA['bita27id'] . '';
	}
	$sSQL = 'SELECT * FROM bita27equipotrabajo WHERE ' . $sSQLcondi;
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$DATA['bita27consec'] = $fila['bita27consec'];
		$DATA['bita27id'] = $fila['bita27id'];
		$DATA['bita27nombre'] = $fila['bita27nombre'];
		$DATA['bita27idlider'] = $fila['bita27idlider'];
		$DATA['bita27idperfil'] = $fila['bita27idperfil'];
		$DATA['bita27correogrupo'] = $fila['bita27correogrupo'];
		$DATA['bita27idunidadfunc'] = $fila['bita27idunidadfunc'];
		$DATA['bita27nivelrespuesta'] = $fila['bita27nivelrespuesta'];
		$DATA['bita27idzona'] = $fila['bita27idzona'];
		$DATA['bita27cead'] = $fila['bita27cead'];
		$DATA['bita27idescuela'] = $fila['bita27idescuela'];
		$DATA['bita27idprograma'] = $fila['bita27idprograma'];
		$DATA['bita27propietario'] = $fila['bita27propietario'];
		$DATA['bita27activo'] = $fila['bita27activo'];
		$bcargo = true;
		$DATA['paso'] = 2;
		$DATA['boculta1527'] = 0;
		$bLimpiaHijos = true;
	} else {
		$DATA['paso'] = 0;
	}
	return array($DATA, $sError, $iTipoError, $sDebug);
}
function f1527_db_GuardarV2($DATA, $objDB, $bDebug = false, $idTercero = 0, $iCodModulo = 1527)
{
	$bAudita[2] = true;
	$bAudita[3] = true;
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_1527 = 'lg/lg_1527_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_1527)) {
		$mensajes_1527 = 'lg/lg_1527_es.php';
	}
	require $mensajes_todas;
	require $mensajes_1527;
	$sError = '';
	$iTipoError = 0;
	$sDebug = '';
	if ($idTercero == 0) {
		$idTercero = $_SESSION['unad_id_tercero'];
	}
	// -- Se inicia validando todas las posibles entradas de usuario.
	/*
	if (isset($DATA['bita27consec']) == 0) {
		$DATA['bita27consec'] = '';
	}
	if (isset($DATA['bita27id']) == 0) {
		$DATA['bita27id'] = '';
	}
	if (isset($DATA['bita27nombre']) == 0) {
		$DATA['bita27nombre'] = '';
	}
	if (isset($DATA['bita27idlider']) == 0) {
		$DATA['bita27idlider'] = '';
	}
	if (isset($DATA['bita27idperfil']) == 0) {
		$DATA['bita27idperfil'] = '';
	}
	if (isset($DATA['bita27correogrupo']) == 0) {
		$DATA['bita27correogrupo'] = '';
	}
	if (isset($DATA['bita27idunidadfunc']) == 0) {
		$DATA['bita27idunidadfunc'] = '';
	}
	if (isset($DATA['bita27nivelrespuesta']) == 0) {
		$DATA['bita27nivelrespuesta'] = '';
	}
	if (isset($DATA['bita27idzona']) == 0) {
		$DATA['bita27idzona'] = '';
	}
	if (isset($DATA['bita27cead']) == 0) {
		$DATA['bita27cead'] = '';
	}
	if (isset($DATA['bita27idescuela']) == 0) {
		$DATA['bita27idescuela'] = '';
	}
	if (isset($DATA['bita27idprograma']) == 0) {
		$DATA['bita27idprograma'] = '';
	}
	if (isset($DATA['bita27propietario']) == 0) {
		$DATA['bita27propietario'] = '';
	}
	if (isset($DATA['bita27activo']) == 0) {
		$DATA['bita27activo'] = 1;
	}
	*/
	$DATA['bita27consec'] = numeros_validar($DATA['bita27consec']);
	$DATA['bita27nombre'] = htmlspecialchars(trim($DATA['bita27nombre']));
	$DATA['bita27idperfil'] = numeros_validar($DATA['bita27idperfil']);
	$DATA['bita27correogrupo'] = htmlspecialchars(trim($DATA['bita27correogrupo']));
	$DATA['bita27idunidadfunc'] = numeros_validar($DATA['bita27idunidadfunc']);
	$DATA['bita27nivelrespuesta'] = numeros_validar($DATA['bita27nivelrespuesta']);
	$DATA['bita27idzona'] = numeros_validar($DATA['bita27idzona']);
	$DATA['bita27cead'] = numeros_validar($DATA['bita27cead']);
	$DATA['bita27idescuela'] = numeros_validar($DATA['bita27idescuela']);
	$DATA['bita27idprograma'] = numeros_validar($DATA['bita27idprograma']);
	$DATA['bita27activo'] = numeros_validar($DATA['bita27activo']);
	// -- Se inicializan las variables que puedan pasar vacias {Especialmente números}.
	/*
	if ($DATA['bita27idperfil'] == '') {
		$DATA['bita27idperfil'] = 0;
	}
	*/
	if ($DATA['bita27idunidadfunc'] == '') {
		$DATA['bita27idunidadfunc'] = 0;
	}
	/*
	if ($DATA['bita27nivelrespuesta'] == '') {
		$DATA['bita27nivelrespuesta'] = 0;
	}
	if ($DATA['bita27idzona'] == '') {
		$DATA['bita27idzona'] = 0;
	}
	if ($DATA['bita27cead'] == '') {
		$DATA['bita27cead'] = 0;
	}
	if ($DATA['bita27idescuela'] == '') {
		$DATA['bita27idescuela'] = 0;
	}
	if ($DATA['bita27idprograma'] == '') {
		$DATA['bita27idprograma'] = 0;
	}
	if ($DATA['bita27activo'] == '') {
		$DATA['bita27activo'] = 1;
	}
	*/
	// -- Seccion para validar los posibles causales de error.
	$sSepara = ', ';
	if (true) {
		if ($DATA['bita27idprograma'] == '') {
			$sError = $ERR['bita27idprograma'] . $sSepara . $sError;
		}
		if ($DATA['bita27idescuela'] == '') {
			$sError = $ERR['bita27idescuela'] . $sSepara . $sError;
		}
		if ($DATA['bita27cead'] == '') {
			$sError = $ERR['bita27cead'] . $sSepara . $sError;
		}
		if ($DATA['bita27idzona'] == '') {
			$sError = $ERR['bita27idzona'] . $sSepara . $sError;
		}
		if ($DATA['bita27nivelrespuesta'] == '') {
			$sError = $ERR['bita27nivelrespuesta'] . $sSepara . $sError;
		}
		//if ($DATA['bita27idunidadfunc']==''){$sError=$ERR['bita27idunidadfunc'].$sSepara.$sError;}
		if ($DATA['bita27idperfil'] == '') {
			$sError = $ERR['bita27idperfil'] . $sSepara . $sError;
		}
		//if ($DATA['bita27idlider']==0){$sError=$ERR['bita27idlider'].$sSepara.$sError;}
		if ($DATA['bita27nombre'] == '') {
			$sError = $ERR['bita27nombre'] . $sSepara . $sError;
		}
		//Fin de las valiaciones NO LLAVE.
	}
	//Valiaciones de campos obligatorios en todo guardar.
	// -- Se verifican los valores de campos de otras tablas.
	if ($DATA['bita27propietario_doc'] != '') {
		if ($sError == '') {
			$sError = tabla_terceros_existe($DATA['bita27propietario_td'], $DATA['bita27propietario_doc'], $objDB, 'El tercero Propietario ');
		}
		if ($sError == '') {
			list($sError, $sInfo) = tercero_Bloqueado($DATA['bita27propietario'], $objDB);
			if ($sInfo != '') {
				$sError = $sError . '<br>' . $sInfo;
			}
		}
	}
	if ($DATA['bita27idlider_doc'] != '') {
		if ($sError == '') {
			$sError = tabla_terceros_existe($DATA['bita27idlider_td'], $DATA['bita27idlider_doc'], $objDB, 'El tercero Lider ');
		}
		if ($sError == '') {
			list($sError, $sInfo) = tercero_Bloqueado($DATA['bita27idlider'], $objDB);
			if ($sInfo != '') {
				$sError = $sError . '<br>' . $sInfo;
			}
		}
	}
	$bQuitarCodigo = false;
	$sCampoCodigo = '';
	if ($sError == '') {
		if ($DATA['paso'] == 10) {
			if ($DATA['bita27consec'] == '') {
				$DATA['bita27consec'] = tabla_consecutivo('bita27equipotrabajo', 'bita27consec', '', $objDB);
				if ($DATA['bita27consec'] == -1) {
					$sError = $objDB->serror;
				}
				$bQuitarCodigo = true;
				$sCampoCodigo = 'bita27consec';
			} else {
				list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 8, $idTercero, $objDB);
				if (!$bDevuelve) {
					$sError = $ERR['8'];
					$DATA['bita27consec'] = '';
				}
			}
			if ($sError == '') {
				$sSQL = 'SELECT 1 FROM bita27equipotrabajo WHERE bita27consec=' . $DATA['bita27consec'] . '';
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
			$DATA['bita27id'] = tabla_consecutivo('bita27equipotrabajo', 'bita27id', '', $objDB);
			if ($DATA['bita27id'] == -1) {
				$sError = $objDB->serror;
			}
		}
	}
	if ($sError == '') {
		$bPasa = false;
		if ($DATA['paso'] == 10) {
			$sCampos1527 = 'bita27consec, bita27id, bita27nombre, bita27idlider, bita27idperfil, 
			bita27correogrupo, bita27idunidadfunc, bita27nivelrespuesta, bita27idzona, bita27cead, 
			bita27idescuela, bita27idprograma, bita27propietario, bita27activo';
			$sValores1527 = '' . $DATA['bita27consec'] . ', ' . $DATA['bita27id'] . ', "' . $DATA['bita27nombre'] . '", ' . $DATA['bita27idlider'] . ', ' . $DATA['bita27idperfil'] . ', 
			"' . $DATA['bita27correogrupo'] . '", ' . $DATA['bita27idunidadfunc'] . ', ' . $DATA['bita27nivelrespuesta'] . ', ' . $DATA['bita27idzona'] . ', ' . $DATA['bita27cead'] . ', 
			' . $DATA['bita27idescuela'] . ', ' . $DATA['bita27idprograma'] . ', ' . $DATA['bita27propietario'] . ', ' . $DATA['bita27activo'] . '';
			if ($APP->utf8 == 1) {
				$sSQL = 'INSERT INTO bita27equipotrabajo (' . $sCampos1527 . ') VALUES (' . utf8_encode($sValores1527) . ');';
				$sdetalle = $sCampos1527 . '[' . utf8_encode($sValores1527) . ']';
			} else {
				$sSQL = 'INSERT INTO bita27equipotrabajo (' . $sCampos1527 . ') VALUES (' . $sValores1527 . ');';
				$sdetalle = $sCampos1527 . '[' . $sValores1527 . ']';
			}
			$idAccion = 2;
			$bPasa = true;
		} else {
			$scampo[1] = 'bita27nombre';
			$scampo[2] = 'bita27idlider';
			$scampo[3] = 'bita27idperfil';
			$scampo[4] = 'bita27correogrupo';
			$scampo[5] = 'bita27idunidadfunc';
			$scampo[6] = 'bita27nivelrespuesta';
			$scampo[7] = 'bita27idzona';
			$scampo[8] = 'bita27cead';
			$scampo[9] = 'bita27idescuela';
			$scampo[10] = 'bita27idprograma';
			$scampo[11] = 'bita27propietario';
			$scampo[12] = 'bita27activo';
			$sdato[1] = $DATA['bita27nombre'];
			$sdato[2] = $DATA['bita27idlider'];
			$sdato[3] = $DATA['bita27idperfil'];
			$sdato[4] = $DATA['bita27correogrupo'];
			$sdato[5] = $DATA['bita27idunidadfunc'];
			$sdato[6] = $DATA['bita27nivelrespuesta'];
			$sdato[7] = $DATA['bita27idzona'];
			$sdato[8] = $DATA['bita27cead'];
			$sdato[9] = $DATA['bita27idescuela'];
			$sdato[10] = $DATA['bita27idprograma'];
			$sdato[11] = $DATA['bita27propietario'];
			$sdato[12] = $DATA['bita27activo'];
			$numcmod=12;
			$sWhere = 'bita27id=' . $DATA['bita27id'] . '';
			$sSQL = 'SELECT * FROM bita27equipotrabajo WHERE ' . $sWhere;
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
					$sdetalle = utf8_encode($sdatos) . '[' . $sWhere . ']';
					$sSQL = 'UPDATE bita27equipotrabajo SET ' . utf8_encode($sdatos) . ' WHERE ' . $sWhere . ';';
				} else {
					$sdetalle = $sdatos . '[' . $sWhere . ']';
					$sSQL = 'UPDATE bita27equipotrabajo SET ' . $sdatos . ' WHERE ' . $sWhere . ';';
				}
				$idAccion = 3;
			}
		}
		if ($bPasa) {
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . ' Guardar 1527 ' . $sSQL . '<br>';
			}
			$result = $objDB->ejecutasql($sSQL);
			if ($result == false) {
				$sError = $ERR['falla_guardar'] . ' [1527] ..<!-- ' . $sSQL . ' -->';
				if ($idAccion == 2) {
					$DATA['bita27id'] = '';
					$bQuitarCodigo = true;
					$DATA['paso'] = 0;
				} else {
					$DATA['paso'] = 2;
				}
			} else {
				if ($bAudita[$idAccion]) {
					seg_auditar(1527, $_SESSION['unad_id_tercero'], $idAccion, $DATA['bita27id'], $sdetalle, $objDB);
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
function f1527_db_Eliminar($bita27id, $objDB, $bDebug = false)
{
	$iCodModulo = 1527;
	$bAudita[4] = true;
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_1527 = 'lg/lg_1527_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_1527)) {
		$mensajes_1527 = 'lg/lg_1527_es.php';
	}
	require $mensajes_todas;
	require $mensajes_1527;
	$sError = '';
	$iTipoError = 0;
	$sDebug = '';
	$bita27id = numeros_validar($bita27id);
	// Traer los datos para hacer las validaciones.
	if ($sError == '') {
		$sSQL = 'SELECT * FROM bita27equipotrabajo WHERE bita27id=' . $bita27id . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$filabase = $objDB->sf($tabla);
		} else {
			$sError = 'No se encuentra el registro solicitado {Ref: ' . $bita27id . '}';
		}
	}
	if ($sError == '') {
		$sSQL = 'SELECT 1 FROM bita28eqipoparte WHERE bita28idequipotrab=' . $filabase['bita27id'] . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$sError = 'Existen Integrantes creados, no es posible eliminar';
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
		$sSQL = 'SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=1527';
		$tablaor = $objDB->ejecutasql($sSQL);
		while ($filaor = $objDB->sf($tablaor)) {
			$sSQL = 'SELECT ' . $filaor['campoid'] . ' FROM ' . $filaor['tabla'] . ' WHERE ' . $filaor['camporev'] . '=' . $_REQUEST['bita27id'] . ' LIMIT 0, 1';
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
		//$sSQL = 'DELETE FROM bita28eqipoparte WHERE bita28idequipotrab=' . $filabase['bita27id'] . '';
		//$tabla = $objDB->ejecutasql($sSQL);
		$sWhere = 'bita27id=' . $bita27id . '';
		//$sWhere = 'bita27consec=' . $filabase['bita27consec'] . '';
		$sSQL = 'DELETE FROM bita27equipotrabajo WHERE ' . $sWhere . ';';
		$result = $objDB->ejecutasql($sSQL);
		if ($result == false) {
			$sError = $ERR['falla_eliminar'] . ' .. <!-- ' . $sSQL . ' -->';
		} else {
			if ($bAudita[4]) {
				seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $bita27id, $sWhere, $objDB);
			}
		}
	}
	return array($sError, $iTipoError, $sDebug);
}
function f1527_TituloBusqueda()
{
	return 'Busqueda de Equipos de trabajo';
}
function f1527_ParametrosBusqueda()
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_1527 = 'lg/lg_1527_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_1527)) {
		$mensajes_1527 = 'lg/lg_1527_es.php';
	}
	require $mensajes_todas;
	require $mensajes_1527;
	$sParams = '<label class="Label90">
	' . $ETI['msg_bnombre'] . '
	</label>
	<label>
	<input id="b1527nombre" name="b1527nombre" type="text" value="" onchange="paginarbusqueda()" />
	</label>';
	return $sParams;
}
function f1527_JavaScriptBusqueda($iModuloBusca)
{
	$sRes = 'var sCampo = window.document.frmedita.scampobusca.value;
	var params = new Array();
	params[100] = sCampo;
	params[101] = window.document.frmedita.paginabusqueda.value;
	params[102] = window.document.frmedita.lppfbusqueda.value;
	params[103] = window.document.frmedita.b1527nombre.value;
	xajax_f' . $iModuloBusca . '_HtmlBusqueda(params);';
	return $sRes;
}
function f1527_TablaDetalleBusquedas($aParametros, $objDB)
{
	$res = '';
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_1527 = 'lg/lg_1527_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_1527)) {
		$mensajes_1527 = 'lg/lg_1527_es.php';
	}
	require $mensajes_todas;
	require $mensajes_1527;
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
	//$bNombre=trim($aParametros[103]);
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
	$sBotones = '<input id="paginaf1527" name="paginaf1527" type="hidden" value="' . $pagina . '" />
	<input id="lppf1527" name="lppf1527" type="hidden" value="' . $lineastabla . '" />';
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
		$sBase = trim(strtoupper($aParametros[104]));
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
	$sTitulos = 'Consec, Id, Nombre, Lider, Perfil';
	$sSQL = 'SELECT TB.bita27consec, TB.bita27id, TB.bita27nombre, T4.unad11razonsocial AS C4_nombre, T5.unad05nombre, TB.bita27idlider, T4.unad11tipodoc AS C4_td, T4.unad11doc AS C4_doc, TB.bita27idperfil 
	FROM bita27equipotrabajo AS TB, unad11terceros AS T4, unad05perfiles AS T5 
	WHERE ' . $sSQLadd1 . ' TB.bita27idlider=T4.unad11id AND TB.bita27idperfil=T5.unad05id ' . $sSQLadd . '
	ORDER BY TB.bita27consec';
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
			$limite = ' LIMIT ' . $rbase . ', ' . $lineastabla;
			$tabladetalle = $objDB->ejecutasql($sSQL . $limite);
		}
	}
	$res = $sErrConsulta . $sLeyenda;
	$res = $res . '<div class="table-responsive">
	<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">
	<thead class="fondoazul"><tr>
	<td><b>' . $ETI['bita27consec'] . '</b></td>
	<td><b>' . $ETI['bita27nombre'] . '</b></td>
	<td colspan="2"><b>' . $ETI['bita27idlider'] . '</b></td>
	<td><b>' . $ETI['bita27idperfil'] . '</b></td>
	<td align="right">
	' . html_paginador('paginabusqueda', $registros, $lineastabla, $pagina, 'paginarbusqueda()') . '
	' . html_lpp('lppfbusqueda', $lineastabla, 'paginarbusqueda()') . '
	</td>
	</tr></thead>';
	$tlinea = 1;
	while ($filadet = $objDB->sf($tabladetalle)) {
		$sPrefijo = '<a href="javascript:Devuelve(\'' . $filadet['bita27id'] . '\');">';
		$sSufijo = '</a>';
		$tlinea++;
		$res = $res . '<tr onmouseover="cambia_color_over(this);" onmouseout="cambia_color_out(this);">
		<td>' . $sPrefijo . $filadet['bita27consec'] . $sSufijo . '</td>
		<td>' . $sPrefijo . cadena_notildes($filadet['bita27nombre']) . $sSufijo . '</td>
		<td>' . $sPrefijo . $filadet['C4_td'] . ' ' . $filadet['C4_doc'] . $sSufijo . '</td>
		<td>' . $sPrefijo . cadena_notildes($filadet['C4_nombre']) . $sSufijo . '</td>
		<td>' . $sPrefijo . cadena_notildes($filadet['unad05nombre']) . $sSufijo . '</td>
		<td></td>
		</tr>';
	}
	$res = $res . '</table>
	<div class="salto5px"></div>
	</div>';
	$objDB->liberar($tabladetalle);
	return utf8_encode($res);
}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------

?>