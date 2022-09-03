<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2022 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.28.2 jueves, 16 de junio de 2022
--- 240 unae40historialcambdoc
*/
/** Archivo lib240.php.
* Libreria 240 unae40historialcambdoc.
* @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
* @date jueves, 16 de junio de 2022
*/
function elimina_archivo_unae40idarchivo($idPadre, $bDebug = false) {
	$_SESSION['u_ultimominuto'] = iminutoavance();
	require './app.php';
	$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto != '') {
		$objDB->dbPuerto = $APP->dbpuerto;
	}
	$objDB->xajax();
	$sError = '';
	$sDebug = '';
	$bPuedeEliminar = true;
	// Definir las condiciones para que se pueda eliminar y el mensaje de error que se debe presentar
	if ($bPuedeEliminar) {
		archivo_eliminar('unae40historialcambdoc', 'unae40id', 'unae40idorigen', 'unae40idarchivo', $idPadre, $objDB);
	}
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	if ($bPuedeEliminar) {
		$objResponse->call("limpia_unae40idarchivo");
	} else {
		$objResponse->call("MensajeAlarmaV2('".$sError."', 0);");
	}
	if ($bDebug) {
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
	}
	return $objResponse;
}
function f240_ExisteDato($datos)
{
	if (!is_array($datos)) {
		$datos = json_decode(str_replace('\"', '"', $datos), true);
	}
	$_SESSION['u_ultimominuto'] = iminutoavance();
	$bHayLlave = true;
	$unae40idtercero = numeros_validar($datos[1]);
	if ($unae40idtercero == '') {
		$bHayLlave = false;
	}
	$unae40consec = numeros_validar($datos[2]);
	if ($unae40consec == '') {
		$bHayLlave = false;
	}
	if ($bHayLlave) {
		require './app.php';
		$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto != '') {
			$objDB->dbPuerto = $APP->dbpuerto;
		}
		$objDB->xajax();
		$sSQL = 'SELECT 1 FROM unae40historialcambdoc WHERE unae40idtercero=' . $unae40idtercero . ' AND unae40consec=' . $unae40consec . '';
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
function f240_Busquedas($aParametros)
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
	$mensajes_240 = 'lg/lg_240_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_240)) {
		$mensajes_240 = 'lg/lg_240_es.php';
	}
	require $mensajes_todas;
	require $mensajes_240;
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
		case 'unae40idtercero':
			require $APP->rutacomun . 'lib111.php';
			$sTabla = f111_TablaDetalleBusquedas($aParametrosB, $objDB);
			$sTitulo = 'Busqueda de terceros';
			$sParams = f111_ParametrosBusqueda();
			$sJavaBusqueda = f111_JavaScriptBusqueda(240);
			break;
		case 'unae40idsolicita':
			require $APP->rutacomun . 'lib111.php';
			$sTabla = f111_TablaDetalleBusquedas($aParametrosB, $objDB);
			$sTitulo = 'Busqueda de terceros';
			$sParams = f111_ParametrosBusqueda();
			$sJavaBusqueda = f111_JavaScriptBusqueda(240);
			break;
		case 'unae40idaprueba':
			require $APP->rutacomun . 'lib111.php';
			$sTabla = f111_TablaDetalleBusquedas($aParametrosB, $objDB);
			$sTitulo = 'Busqueda de terceros';
			$sParams = f111_ParametrosBusqueda();
			$sJavaBusqueda = f111_JavaScriptBusqueda(240);
			break;
	}
	$sTitulo = '<h2>' . $ETI['titulo_240'] . ' - ' . $sTitulo . '</h2>';
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_97titulo', 'innerHTML', $sTitulo);
	$objResponse->assign('div_97params', 'innerHTML', $sParams);
	$objResponse->assign('div_97tabla', 'innerHTML', $sTabla);
	$objResponse->setFunction('paginarbusqueda', '', $sJavaBusqueda);
	$objResponse->call('expandesector(97)');
	return $objResponse;
}
function f240_HtmlBusqueda($aParametros)
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
		case 'unae40idtercero':
			require $APP->rutacomun . 'lib111.php';
			$sDetalle = f111_TablaDetalleBusquedas($aParametros, $objDB);
			break;
		case 'unae40idsolicita':
			require $APP->rutacomun . 'lib111.php';
			$sDetalle = f111_TablaDetalleBusquedas($aParametros, $objDB);
			break;
		case 'unae40idaprueba':
			require $APP->rutacomun . 'lib111.php';
			$sDetalle = f111_TablaDetalleBusquedas($aParametros, $objDB);
			break;
	}
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_97tabla', 'innerHTML', $sDetalle);
	return $objResponse;
}
function f240_TablaDetalleV2($aParametros, $objDB, $bDebug = false)
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
	$mensajes_240 = 'lg/lg_240_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_240)) {
		$mensajes_240 = 'lg/lg_240_es.php';
	}
	require $mensajes_240;
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
	/*
	if (isset($aParametros[103]) == 0) {
		$aParametros[103] = '';
	}
	if (isset($aParametros[104]) == 0) {
		$aParametros[104] = '';
	}
	*/
	$idTercero = $aParametros[100];
	$sDebug = '';
	if (true) {
		//Leemos los parametros de entrada.
		$pagina = $aParametros[101];
		$lineastabla = $aParametros[102];
		//$bNombre=trim($aParametros[103]);
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
	$sBotones = '<input id="paginaf240" name="paginaf240" type="hidden" value="' . $pagina . '"/>
	<input id="lppf240" name="lppf240" type="hidden" value="' . $lineastabla . '"/>';
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
			$sBase = strtoupper($bNombre);
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
	$sTitulos = 'Tercero, Consec, Id, Tipodocorigen, Docorigen, Or_nombre1, Or_nombre2, Or_apellido1, Or_apellido2, Or_sexo, Or_fechanac, Or_fechadoc, Tipodocdestino, Docdestino, Des_nombre1, Des_nombre2, Des_apellido1, Des_apellido2, Des_sexo, Des_fechanac, Des_fechadoc, Solicita, Fechasol, Horasol, Minsol, Origen, Archivo, Estado, Detalle, Aprueba, Fechaapr, Horaaprueba, Minaprueba, Tiempod, Tiempoh';
	$registros = 0;
	$bGigante = false; //En caso de que la tabla sea muy grande pasarlo a true
	$sLimite = '';
	if ($bGigante) {
		$sSQL = 'SELECT COUNT(1) AS Total 
		FROM unae40historialcambdoc AS TB, unad11terceros AS T1, unad11terceros AS T22, unad11terceros AS T30 
		WHERE ' . $sSQLadd1 . ' TB.unae40idtercero=T1.unad11id AND TB.unae40idsolicita=T22.unad11id AND TB.unae40idaprueba=T30.unad11id ' . $sSQLadd . '';
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
	$sSQL = 'SELECT T1.unad11razonsocial AS C1_nombre, TB.unae40consec, TB.unae40id, TB.unae40tipodocorigen, TB.unae40docorigen, TB.unae40or_nombre1, TB.unae40or_nombre2, TB.unae40or_apellido1, TB.unae40or_apellido2, TB.unae40or_sexo, TB.unae40or_fechanac, TB.unae40or_fechadoc, TB.unae40tipodocdestino, TB.unae40docdestino, TB.unae40des_nombre1, TB.unae40des_nombre2, TB.unae40des_apellido1, TB.unae40des_apellido2, TB.unae40des_sexo, TB.unae40des_fechanac, TB.unae40des_fechadoc, T22.unad11razonsocial AS C22_nombre, TB.unae40fechasol, TB.unae40horasol, TB.unae40minsol, TB.unae40idorigen, TB.unae40idarchivo, TB.unae40estado, TB.unae40detalle, T30.unad11razonsocial AS C30_nombre, TB.unae40fechaapr, TB.unae40horaaprueba, TB.unae40minaprueba, TB.unae40tiempod, TB.unae40tiempoh, TB.unae40idtercero, T1.unad11tipodoc AS C1_td, T1.unad11doc AS C1_doc, TB.unae40idsolicita, T22.unad11tipodoc AS C22_td, T22.unad11doc AS C22_doc, TB.unae40idaprueba, T30.unad11tipodoc AS C30_td, T30.unad11doc AS C30_doc 
	FROM unae40historialcambdoc AS TB, unad11terceros AS T1, unad11terceros AS T22, unad11terceros AS T30 
	WHERE ' . $sSQLadd1 . ' TB.unae40idtercero=T1.unad11id AND TB.unae40idsolicita=T22.unad11id AND TB.unae40idaprueba=T30.unad11id ' . $sSQLadd . '
	ORDER BY TB.unae40idtercero, TB.unae40consec';
	$sSQLlista = str_replace("'", "|", $sSQL);
	$sSQLlista = str_replace('"', "|", $sSQLlista);
	$sErrConsulta = '<input id="consulta_240" name="consulta_240" type="hidden" value="' . $sSQLlista . '"/>
	<input id="titulos_240" name="titulos_240" type="hidden" value="' . $sTitulos . '"/>';
	$tabladetalle = $objDB->ejecutasql($sSQL . $sLimite);
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' Consulta 240: ' . $sSQL . $sLimite . '<br>';
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
	$res = $sErrConsulta . $sLeyenda;
	$res = $res . '<div class="table-responsive">
	<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">
	<thead class="fondoazul"><tr>
	<td><b>' . $ETI['unae40fechasol'] . '</b></td>
	<td><b>' . $ETI['unae40horasol'] . '</b></td>
	<td><b>' . $ETI['unae40consec'] . '</b></td>
	<td><b>' . $ETI['unae40estado'] . '</b></td>
	<td><b>' . $ETI['unae40tipodocdestino'] . '</b></td>
	<td><b>' . $ETI['unae40docdestino'] . '</b></td>
	<td align="right">
	' . html_paginador('paginaf240', $registros, $lineastabla, $pagina, 'paginarf240()') . '
	' . html_lpp('lppf240', $lineastabla, 'paginarf240()') . '
	</td>
	</tr></thead>';
	$tlinea = 1;
	while ($filadet = $objDB->sf($tabladetalle)) {
		$sPrefijo = '';
		$sSufijo = '';
		$sClass = ' class="resaltetabla"';
		$sLink = '';
		switch ($filadet['unae40estado']) {
			case 7:
			$sPrefijo = '<b>';
			$sSufijo = '</b>';
			break;
		}
		if (($tlinea % 2) != 0) {
			$sClass = '';
		}
		$tlinea++;
		$et_unae40idtercero_doc = '';
		$et_unae40idtercero_nombre = '';
		if ($filadet['unae40idtercero'] != 0) {
			$et_unae40idtercero_doc = $sPrefijo . $filadet['C1_td'] . ' ' . $filadet['C1_doc'] . $sSufijo;
			$et_unae40idtercero_nombre = $sPrefijo . cadena_notildes($filadet['C1_nombre']) . $sSufijo;
		}
		$et_unae40or_sexo = $sPrefijo . $ETI['no'] . $sSufijo;
		if ($filadet['unae40or_sexo'] == 'S') {
			$et_unae40or_sexo = $sPrefijo . $ETI['si'] . $sSufijo;
		}
		$et_unae40or_fechanac = '';
		if ($filadet['unae40or_fechanac'] != '00/00/0000') {
			$et_unae40or_fechanac = $filadet['unae40or_fechanac'];
		}
		$et_unae40or_fechadoc = '';
		if ($filadet['unae40or_fechadoc'] != '00/00/0000') {
			$et_unae40or_fechadoc = $filadet['unae40or_fechadoc'];
		}
		$et_unae40des_sexo = $sPrefijo . $ETI['no'] . $sSufijo;
		if ($filadet['unae40des_sexo'] == 'S') {
			$et_unae40des_sexo = $sPrefijo . $ETI['si'] . $sSufijo;
		}
		$et_unae40des_fechanac = '';
		if ($filadet['unae40des_fechanac'] != '00/00/0000') {
			$et_unae40des_fechanac = $filadet['unae40des_fechanac'];
		}
		$et_unae40des_fechadoc = '';
		if ($filadet['unae40des_fechadoc'] != '00/00/0000') {
			$et_unae40des_fechadoc = $filadet['unae40des_fechadoc'];
		}
		$et_unae40idsolicita_doc = '';
		$et_unae40idsolicita_nombre = '';
		if ($filadet['unae40idsolicita'] != 0) {
			$et_unae40idsolicita_doc = $sPrefijo . $filadet['C22_td'] . ' ' . $filadet['C22_doc'] . $sSufijo;
			$et_unae40idsolicita_nombre = $sPrefijo . cadena_notildes($filadet['C22_nombre']) . $sSufijo;
		}
		$et_unae40fechasol = '';
		if ($filadet['unae40fechasol'] != 0) {
			$et_unae40fechasol = fecha_desdenumero($filadet['unae40fechasol']);
		}
		$et_unae40horasol = html_TablaHoraMin($filadet['unae40horasol'], $filadet['unae40minsol']);
		$et_unae40idarchivo = '';
		if ($filadet['unae40idarchivo'] != 0) {
			//$et_unae40idarchivo = '<img src="verarchivo.php?cont=' . $filadet['unae40idorigen'] . '&id=' . $filadet['unae40idarchivo'] . '&maxx=150"/>';
			$et_unae40idarchivo = html_lnkarchivo((int)$filadet['unae40idorigen'], (int)$filadet['unae40idarchivo']);
		}
		$et_unae40estado = $aunae40estado[$filadet['unae40estado']];
		$et_unae40idaprueba_doc = '';
		$et_unae40idaprueba_nombre = '';
		if ($filadet['unae40idaprueba'] != 0) {
			$et_unae40idaprueba_doc = $sPrefijo . $filadet['C30_td'] . ' ' . $filadet['C30_doc'] . $sSufijo;
			$et_unae40idaprueba_nombre = $sPrefijo . cadena_notildes($filadet['C30_nombre']) . $sSufijo;
		}
		$et_unae40fechaapr = '';
		if ($filadet['unae40fechaapr'] != 0) {
			$et_unae40fechaapr = fecha_desdenumero($filadet['unae40fechaapr']);
		}
		$et_unae40horaaprueba = html_TablaHoraMin($filadet['unae40horaaprueba'], $filadet['unae40minaprueba']);
		if ($bAbierta) {
			$sLink = '<a href="javascript:cargaridf240(' . $filadet['unae40id'] . ')" class="lnkresalte">' . $ETI['lnk_cargar'] . '</a>';
		}
		$res = $res . '<tr' . $sClass . '>
		<td>' . $sPrefijo . $et_unae40fechasol . $sSufijo . '</td>
		<td>' . $sPrefijo . $et_unae40horasol . $sSufijo . '</td>
		<td>' . $sPrefijo.$filadet['unae40consec'] . $sSufijo . '</td>
		<td>' . $sPrefijo . $et_unae40estado . $sSufijo . '</td>
		<td>' . $sPrefijo.cadena_notildes($filadet['unae40tipodocdestino']).$sSufijo . '</td>
		<td>' . $sPrefijo.cadena_notildes($filadet['unae40docdestino']).$sSufijo . '</td>
		<td>' . $sLink . '</td>
		</tr>';
	}
	$res = $res . '</table>
	<div class="salto5px"></div>
	</div>';
	$objDB->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
}
function f240_HtmlTabla($aParametros)
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
	list($sDetalle, $sDebugTabla) = f240_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_f240detalle', 'innerHTML', $sDetalle);
	if ($bDebug) {
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
	}
	return $objResponse;
}
function f240_db_CargarPadre($DATA, $objDB, $bDebug = false)
{
	$sError = '';
	$iTipoError = 0;
	$sDebug = '';
	require './app.php';
	$DATA['unae40idtercero_td'] = $APP->tipo_doc;
	$DATA['unae40idtercero_doc'] = '';
	$DATA['unae40idsolicita_td'] = $APP->tipo_doc;
	$DATA['unae40idsolicita_doc'] = '';
	$DATA['unae40idaprueba_td'] = $APP->tipo_doc;
	$DATA['unae40idaprueba_doc'] = '';
	if ($DATA['paso'] == 1) {
		$sSQLcondi = 'unae40idtercero="' . $DATA['unae40idtercero'] . '" AND unae40consec=' . $DATA['unae40consec'] . '';
	} else {
		$sSQLcondi = 'unae40id=' . $DATA['unae40id'] . '';
	}
	$sSQL = 'SELECT * FROM unae40historialcambdoc WHERE ' . $sSQLcondi;
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$DATA['unae40idtercero'] = $fila['unae40idtercero'];
		$DATA['unae40consec'] = $fila['unae40consec'];
		$DATA['unae40id'] = $fila['unae40id'];
		$DATA['unae40tipodocorigen'] = $fila['unae40tipodocorigen'];
		$DATA['unae40docorigen'] = $fila['unae40docorigen'];
		$DATA['unae40or_nombre1'] = $fila['unae40or_nombre1'];
		$DATA['unae40or_nombre2'] = $fila['unae40or_nombre2'];
		$DATA['unae40or_apellido1'] = $fila['unae40or_apellido1'];
		$DATA['unae40or_apellido2'] = $fila['unae40or_apellido2'];
		$DATA['unae40or_sexo'] = $fila['unae40or_sexo'];
		$DATA['unae40or_fechanac'] = $fila['unae40or_fechanac'];
		$DATA['unae40or_fechadoc'] = $fila['unae40or_fechadoc'];
		$DATA['unae40tipodocdestino'] = $fila['unae40tipodocdestino'];
		$DATA['unae40docdestino'] = $fila['unae40docdestino'];
		$DATA['unae40des_nombre1'] = $fila['unae40des_nombre1'];
		$DATA['unae40des_nombre2'] = $fila['unae40des_nombre2'];
		$DATA['unae40des_apellido1'] = $fila['unae40des_apellido1'];
		$DATA['unae40des_apellido2'] = $fila['unae40des_apellido2'];
		$DATA['unae40des_sexo'] = $fila['unae40des_sexo'];
		$DATA['unae40des_fechanac'] = $fila['unae40des_fechanac'];
		$DATA['unae40des_fechadoc'] = $fila['unae40des_fechadoc'];
		$DATA['unae40idsolicita'] = $fila['unae40idsolicita'];
		$DATA['unae40fechasol'] = $fila['unae40fechasol'];
		$DATA['unae40horasol'] = $fila['unae40horasol'];
		$DATA['unae40minsol'] = $fila['unae40minsol'];
		$DATA['unae40idorigen'] = $fila['unae40idorigen'];
		$DATA['unae40idarchivo'] = $fila['unae40idarchivo'];
		$DATA['unae40estado'] = $fila['unae40estado'];
		$DATA['unae40detalle'] = $fila['unae40detalle'];
		$DATA['unae40idaprueba'] = $fila['unae40idaprueba'];
		$DATA['unae40fechaapr'] = $fila['unae40fechaapr'];
		$DATA['unae40horaaprueba'] = $fila['unae40horaaprueba'];
		$DATA['unae40minaprueba'] = $fila['unae40minaprueba'];
		$DATA['unae40tiempod'] = $fila['unae40tiempod'];
		$DATA['unae40tiempoh'] = $fila['unae40tiempoh'];
		$bcargo = true;
		$DATA['paso'] = 2;
		$DATA['boculta240'] = 0;
		$bLimpiaHijos = true;
	} else {
		$DATA['paso'] = 0;
	}
	return array($DATA, $sError, $iTipoError, $sDebug);
}
function f240_Cerrar($unae40id, $objDB, $bDebug = false)
{
	$sInfo = '';
	$sDebug = '';
	return array($sInfo, $sDebug);
}
function f240_db_GuardarV2($DATA, $objDB, $bDebug = false, $idTercero = 0)
{
	$iCodModulo = 240;
	$bAudita[2] = true;
	$bAudita[3] = true;
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_240 = 'lg/lg_240_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_240)) {
		$mensajes_240 = 'lg/lg_240_es.php';
	}
	require $mensajes_todas;
	require $mensajes_240;
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
	if (isset($DATA['unae40consec']) == 0) {
		$DATA['unae40consec'] = '';
	}
	if (isset($DATA['unae40id']) == 0) {
		$DATA['unae40id'] = '';
	}
	if (isset($DATA['unae40tipodocdestino']) == 0) {
		$DATA['unae40tipodocdestino'] = '';
	}
	if (isset($DATA['unae40docdestino']) == 0) {
		$DATA['unae40docdestino'] = '';
	}
	if (isset($DATA['unae40des_nombre1']) == 0) {
		$DATA['unae40des_nombre1'] = '';
	}
	if (isset($DATA['unae40des_nombre2']) == 0) {
		$DATA['unae40des_nombre2'] = '';
	}
	if (isset($DATA['unae40des_apellido1']) == 0) {
		$DATA['unae40des_apellido1'] = '';
	}
	if (isset($DATA['unae40des_apellido2']) == 0) {
		$DATA['unae40des_apellido2'] = '';
	}
	if (isset($DATA['unae40des_sexo']) == 0) {
		$DATA['unae40des_sexo'] = '';
	}
	if (isset($DATA['unae40des_fechanac']) == 0) {
		$DATA['unae40des_fechanac'] = '';
	}
	if (isset($DATA['unae40des_fechadoc']) == 0) {
		$DATA['unae40des_fechadoc'] = '';
	}
	if (isset($DATA['unae40fechasol']) == 0) {
		$DATA['unae40fechasol'] = '';
	}
	if (isset($DATA['unae40estado']) == 0) {
		$DATA['unae40estado'] = '';
	}
	if (isset($DATA['unae40detalle']) == 0) {
		$DATA['unae40detalle'] = '';
	}
	if (isset($DATA['unae40fechaapr']) == 0) {
		$DATA['unae40fechaapr'] = '';
	}
	*/
	$DATA['unae40consec'] = numeros_validar($DATA['unae40consec']);
	$DATA['unae40tipodocorigen'] = htmlspecialchars(trim($DATA['unae40tipodocorigen']));
	$DATA['unae40docorigen'] = htmlspecialchars(trim($DATA['unae40docorigen']));
	$DATA['unae40or_nombre1'] = htmlspecialchars(trim($DATA['unae40or_nombre1']));
	$DATA['unae40or_nombre2'] = htmlspecialchars(trim($DATA['unae40or_nombre2']));
	$DATA['unae40or_apellido1'] = htmlspecialchars(trim($DATA['unae40or_apellido1']));
	$DATA['unae40or_apellido2'] = htmlspecialchars(trim($DATA['unae40or_apellido2']));
	$DATA['unae40or_sexo'] = htmlspecialchars(trim($DATA['unae40or_sexo']));
	$DATA['unae40tipodocdestino'] = htmlspecialchars(trim($DATA['unae40tipodocdestino']));
	$DATA['unae40docdestino'] = htmlspecialchars(trim($DATA['unae40docdestino']));
	$DATA['unae40des_nombre1'] = htmlspecialchars(trim($DATA['unae40des_nombre1']));
	$DATA['unae40des_nombre2'] = htmlspecialchars(trim($DATA['unae40des_nombre2']));
	$DATA['unae40des_apellido1'] = htmlspecialchars(trim($DATA['unae40des_apellido1']));
	$DATA['unae40des_apellido2'] = htmlspecialchars(trim($DATA['unae40des_apellido2']));
	$DATA['unae40des_sexo'] = htmlspecialchars(trim($DATA['unae40des_sexo']));
	$DATA['unae40horasol'] = numeros_validar($DATA['unae40horasol']);
	$DATA['unae40minsol'] = numeros_validar($DATA['unae40minsol']);
	$DATA['unae40idorigen'] = numeros_validar($DATA['unae40idorigen']);
	$DATA['unae40idarchivo'] = numeros_validar($DATA['unae40idarchivo']);
	$DATA['unae40detalle'] = htmlspecialchars(trim($DATA['unae40detalle']));
	$DATA['unae40horaaprueba'] = numeros_validar($DATA['unae40horaaprueba']);
	$DATA['unae40minaprueba'] = numeros_validar($DATA['unae40minaprueba']);
	// -- Se inicializan las variables que puedan pasar vacias {Especialmente números}.
	/*
	if ($DATA['unae40horasol'] == '') {
		$DATA['unae40horasol'] = 0;
	}
	if ($DATA['unae40minsol'] == '') {
		$DATA['unae40minsol'] = 0;
	}
	*/
	if ($DATA['unae40estado'] == '') {
		$DATA['unae40estado'] = 0;
	}
	if ($DATA['unae40fechaapr'] == '') {
		$DATA['unae40fechaapr'] = 0;
	}
		/*
	if ($DATA['unae40horaaprueba'] == '') {
		$DATA['unae40horaaprueba'] = 0;
	}
	if ($DATA['unae40minaprueba'] == '') {
		$DATA['unae40minaprueba'] = 0;
	}
	*/
	// -- Seccion para validar los posibles causales de error.
	$sSepara = ', ';
	$iBorrador = 0;
	$iRadicado = 0;
	switch ($DATA['unae40estado']) {
		case 0:
		case 3:
		if ($DATA['unae40fechasol'] == '') {
			$DATA['unae40fechasol'] = fecha_DiaMod();
			$DATA['unae40horasol'] = fecha_hora();
			$DATA['unae40minsol'] = fecha_minuto();
			//$sError = $ERR['unae40fechasol'] . $sSepara . $sError;
			}
		if ($DATA['unae40idsolicita'] == 0) {
			$sError = $ERR['unae40idsolicita'] . $sSepara . $sError;
		}
		if (!fecha_esvalida($DATA['unae40des_fechadoc'])) {
			//$DATA['unae40des_fechadoc'] = '00/00/0000';
			$sError = $ERR['unae40des_fechadoc'] . $sSepara . $sError;
			}
		if (!fecha_esvalida($DATA['unae40des_fechanac'])) {
			//$DATA['unae40des_fechanac'] = '00/00/0000';
			$sError = $ERR['unae40des_fechanac'] . $sSepara . $sError;
			}
		if ($DATA['unae40des_sexo'] == '') {
			$sError = $ERR['unae40des_sexo'] . $sSepara . $sError;
		}
		if ($DATA['unae40des_apellido1'] == '') {
			$sError = $ERR['unae40des_apellido1'] . $sSepara . $sError;
		}
		if ($DATA['unae40des_nombre1'] == '') {
			$sError = $ERR['unae40des_nombre1'] . $sSepara . $sError;
		}
		if ($DATA['unae40docdestino'] == '') {
			$sError = $ERR['unae40docdestino'] . $sSepara . $sError;
		}
		if ($DATA['unae40tipodocdestino'] == '') {
			$sError = $ERR['unae40tipodocdestino'] . $sSepara . $sError;
		}
		//if (!fecha_esvalida($DATA['unae40or_fechadoc'])) {
			//$DATA['unae40or_fechadoc'] = '00/00/0000';
			//$sError = $ERR['unae40or_fechadoc'] . $sSepara . $sError;
			//}
		//if (!fecha_esvalida($DATA['unae40or_fechanac'])) {
			//$DATA['unae40or_fechanac'] = '00/00/0000';
			//$sError = $ERR['unae40or_fechanac'] . $sSepara . $sError;
			//}
		if ($DATA['paso'] == 10) {
			$sSQL = 'SELECT SUM(CASE WHEN unae40estado=0 THEN 1 ELSE 0 END) AS borrador, 
			SUM(CASE WHEN unae40estado=3 THEN 1 ELSE 0 END) AS radicado
			FROM unae40historialcambdoc WHERE unae40idsolicita=' . $DATA['unae40idsolicita'] . '';
			$result = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($result) != 0) {
				$fila = $objDB->sf($result);
				if($fila['borrador']>0) {
					$sError = $ERR['unae40solabierta'];
				} else if ($fila['radicado']>0) {
					$sError = $ERR['unae40radexistente'];
				}
			}
		}
		//Fin de las valiaciones NO LLAVE.
		if ($sError != '') {
			$DATA['unae40estado'] = 0;
		}
		$sErrorCerrando = $sError;
		// $sError = '';
		break;
	}
	if ($sError == '') {
		$sSQLcondi = 'unae40tipodocdestino="' . $DATA['unae40tipodocdestino'] . '" AND unae40docdestino="' . $DATA['unae40docdestino'] . '" AND unae40des_fechadoc="' . $DATA['unae40des_fechadoc'] . 
		'" AND unae40des_nombre1="' . $DATA['unae40des_nombre1'] . '" AND unae40des_nombre2="' . $DATA['unae40des_nombre2'] . '" AND unae40des_apellido1="' . $DATA['unae40des_apellido1'] . 
		'" AND unae40des_apellido2="' . $DATA['unae40des_apellido2'] . '" AND unae40des_sexo="' . $DATA['unae40des_sexo'] . '" AND unae40des_fechanac="' . $DATA['unae40des_fechanac'] . 
		'" AND unae40idsolicita=' . $DATA['unae40idsolicita'] . '';
		$sSQL = 'SELECT 1 FROM unae40historialcambdoc WHERE ' . $sSQLcondi . '';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' Solicitud existente ' . $sSQL . '<br>';
		}
		$result = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($result) != 0) {
			if ($DATA['unae40estado'] == 0) {
				$sError = $ERR['unae40solexistente'];
			}
		} else {
			$bDevuelve = true;
			// list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 2, $idTercero, $objDB);
			if (!$bDevuelve) {
				$sError = $ERR['2'];
			}
		}
	}
	//Valiaciones de campos obligatorios en todo guardar.
	if ($DATA['unae40idtercero'] == 0) {
		$sError = $ERR['unae40idtercero'];
	}
	// -- Tiene un cerrado.
	if ($DATA['unae40estado'] == 3) {
		//Validaciones previas a cerrar
		//Vamos a validar que exista un archivo pero sobre la tabla..
		if ($sError.$sErrorCerrando == '') {
			$unae40idarchivo = 0;
			$sSQL = 'SELECT unae40idarchivo FROM unae40historialcambdoc WHERE unae40id=' . $DATA['unae40id'] . '';
			$tabla = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla) > 0){
				$fila = $objDB->sf($tabla);
				$unae40idarchivo = $fila['unae40idarchivo'];
			}
			if ($unae40idarchivo == 0) {
				$sErrorCerrando = $ERR['unae40idarchivo'] . ' ' . $sErrorCerrando;
			}
		}
		//Aprobó las Validaciones al cerrar
		if ($sError.$sErrorCerrando != '') {
			$DATA['unae40estado'] = 0;
			$sErrorCerrando = $sError . ' ' . $sErrorCerrando;
			$sError = '';
		} else {
			$bCerrando = true;
			//Acciones del cierre
			$DATA['unae40fechasol'] = fecha_DiaMod();
			$DATA['unae40horasol'] = fecha_hora();
			$DATA['unae40minsol'] = fecha_minuto();
		}
	}
	// -- Fin del cerrado.
	// -- Se verifican los valores de campos de otras tablas.
	if ($DATA['unae40idaprueba_doc'] != '') {
		if ($sError == '') {
			$sError = tabla_terceros_existe($DATA['unae40idaprueba_td'], $DATA['unae40idaprueba_doc'], $objDB, 'El tercero Aprueba ');
		}
		if ($sError == '') {
			list($sError, $sInfo) = tercero_Bloqueado($DATA['unae40idaprueba'], $objDB);
			if ($sInfo != '') {
				$sError = $sError . '<br>' . $sInfo;
			}
		}
	}
	if ($DATA['unae40idsolicita_doc'] != '') {
		if ($sError == '') {
			$sError = tabla_terceros_existe($DATA['unae40idsolicita_td'], $DATA['unae40idsolicita_doc'], $objDB, 'El tercero Solicita ');
		}
		if ($sError == '') {
			list($sError, $sInfo) = tercero_Bloqueado($DATA['unae40idsolicita'], $objDB);
			if ($sInfo != '') {
				$sError = $sError . '<br>' . $sInfo;
			}
		}
	}
	if ($DATA['unae40idtercero_doc'] != '') {
		if ($sError == '') {
			$sError = tabla_terceros_existe($DATA['unae40idtercero_td'], $DATA['unae40idtercero_doc'], $objDB, 'El tercero Tercero ');
		}
		if ($sError == '') {
			list($sError, $sInfo) = tercero_Bloqueado($DATA['unae40idtercero'], $objDB);
			if ($sInfo != '') {
				$sError = $sError . '<br>' . $sInfo;
			}
		}
	}
	$bQuitarCodigo = false;
	$sCampoCodigo = '';
	if ($sError == '') {
		if ($DATA['paso'] == 10) {
			if ($DATA['unae40consec'] == '') {
				$DATA['unae40consec'] = tabla_consecutivo('unae40historialcambdoc', 'unae40consec', 'unae40idtercero=' . $DATA['unae40idtercero'] . '', $objDB);
				if ($DATA['unae40consec'] == -1) {
					$sError = $objDB->serror;
				}
				$bQuitarCodigo = true;
				$sCampoCodigo = 'unae40consec';
			} else {
				$bDevuelve = true;
				// list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 8, $idTercero, $objDB);
				if (!$bDevuelve) {
					$sError = $ERR['8'];
					$DATA['unae40consec'] = '';
				}
			}
			if ($sError == '') {
				$sSQL = 'SELECT 1 FROM unae40historialcambdoc WHERE unae40idtercero="' . $DATA['unae40idtercero'] . '" AND unae40consec=' . $DATA['unae40consec'] . '';
				$result = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($result) != 0) {
					$sError = $ERR['existe'];
				} else {
					$bDevuelve = true;
					// list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 2, $idTercero, $objDB);
					if (!$bDevuelve) {
						$sError = $ERR['2'];
					}
				}
			}
		} else {
			$bDevuelve = true;
			// list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 3, $idTercero, $objDB);
			if (!$bDevuelve) {
				$sError = $ERR['3'];
			}
		}
	}
	if ($sError == '') {
		if ($DATA['paso'] == 10) {
			//Preparar el Id, Si no lo hay se quita la comprobación.
			$DATA['unae40id'] = tabla_consecutivo('unae40historialcambdoc', 'unae40id', '', $objDB);
			if ($DATA['unae40id'] == -1) {
				$sError = $objDB->serror;
			}
			//Datos adicionales al iniciar un registro.
			$unae40tipodoc='';
			$unae40doc='';
			$unae40_fechadoc='';
			$unae40_nombre1='';
			$unae40_nombre2='';
			$unae40_apellido1='';
			$unae40_apellido2='';
			$unae40_sexo='';
			$unae40_fechanac='';
			$sSQLcondi = 'unad11id="' . $_SESSION['unad_id_tercero'] . '"' . '';
			$sSQL = 'SELECT unad11tipodoc, unad11doc, unad11fechadoc, unad11nombre1, unad11nombre2, unad11apellido1, unad11apellido2, unad11genero, unad11fechanace FROM unad11terceros WHERE ' . $sSQLcondi;
			$tabla = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla) > 0) {
				$fila = $objDB->sf($tabla);
				$unae40tipodoc=$fila['unad11tipodoc'];
				$unae40doc=$fila['unad11doc'];
				$unae40_fechadoc=$fila['unad11fechadoc'];
				$unae40_nombre1=$fila['unad11nombre1'];
				$unae40_nombre2=$fila['unad11nombre2'];
				$unae40_apellido1=$fila['unad11apellido1'];
				$unae40_apellido2=$fila['unad11apellido2'];
				$unae40_sexo=$fila['unad11genero'];
				$unae40_fechanac=$fila['unad11fechanace'];
			}
			$DATA['unae40tipodocorigen'] = $unae40tipodoc;
			$DATA['unae40docorigen'] = $unae40doc;
			$DATA['unae40or_nombre1'] = $unae40_nombre1;
			$DATA['unae40or_nombre2'] = $unae40_nombre2;
			$DATA['unae40or_apellido1'] = $unae40_apellido1;
			$DATA['unae40or_apellido2'] = $unae40_apellido2;
			$DATA['unae40or_sexo'] = $unae40_sexo;
			$DATA['unae40or_fechanac'] = $unae40_fechanac; //fecha_hoy();
			$DATA['unae40or_fechadoc'] = $unae40_fechadoc; //fecha_hoy();
			$DATA['unae40idsolicita'] = $_SESSION['unad_id_tercero'];
			$DATA['unae40idorigen'] = $DATA['unae40idsolicita'];
			$DATA['unae40idarchivo'] = 0;
			$DATA['unae40estado'] = 0;
			//$DATA['unae40idaprueba'] = 0; //$_SESSION['u_idtercero'];
			$DATA['unae40horaaprueba'] = 0;
			$DATA['unae40minaprueba'] = 0;
			$DATA['unae40tiempod'] = 0;
			$DATA['unae40tiempoh'] = 0;
		}
	}
	if ($sError == '') {
		//$unae40detalle = addslashes($DATA['unae40detalle']);
		$unae40detalle = str_replace('"', '\"', $DATA['unae40detalle']);
		$bPasa = false;
		if ($DATA['paso'] == 10) {
			$sCampos240 = 'unae40idtercero, unae40consec, unae40id, unae40tipodocorigen, unae40docorigen, 
			unae40or_nombre1, unae40or_nombre2, unae40or_apellido1, unae40or_apellido2, unae40or_sexo, 
			unae40or_fechanac, unae40or_fechadoc, unae40tipodocdestino, unae40docdestino, unae40des_nombre1, 
			unae40des_nombre2, unae40des_apellido1, unae40des_apellido2, unae40des_sexo, unae40des_fechanac, 
			unae40des_fechadoc, unae40idsolicita, unae40fechasol, unae40horasol, unae40minsol, 
			unae40idorigen, unae40idarchivo, unae40estado, unae40detalle, unae40idaprueba, 
			unae40fechaapr, unae40horaaprueba, unae40minaprueba, unae40tiempod, unae40tiempoh';
			$sValores240 = '' . $DATA['unae40idtercero'] . ', ' . $DATA['unae40consec'] . ', ' . $DATA['unae40id'] . ', "' . $DATA['unae40tipodocorigen'] . '", "' . $DATA['unae40docorigen'] . '", 
			"' . $DATA['unae40or_nombre1'] . '", "' . $DATA['unae40or_nombre2'] . '", "' . $DATA['unae40or_apellido1'] . '", "' . $DATA['unae40or_apellido2'] . '", "' . $DATA['unae40or_sexo'] . '", 
			"' . $DATA['unae40or_fechanac'] . '", "' . $DATA['unae40or_fechadoc'] . '", "' . $DATA['unae40tipodocdestino'] . '", "' . $DATA['unae40docdestino'] . '", "' . $DATA['unae40des_nombre1'] . '", 
			"' . $DATA['unae40des_nombre2'] . '", "' . $DATA['unae40des_apellido1'] . '", "' . $DATA['unae40des_apellido2'] . '", "' . $DATA['unae40des_sexo'] . '", "' . $DATA['unae40des_fechanac'] . '", 
			"' . $DATA['unae40des_fechadoc'] . '", ' . $DATA['unae40idsolicita'] . ', ' . $DATA['unae40fechasol'] . ', ' . $DATA['unae40horasol'] . ', ' . $DATA['unae40minsol'] . ',
			' . $DATA['unae40idorigen'] . ', ' . $DATA['unae40idarchivo'] . ', ' . $DATA['unae40estado'] . ', "' . $unae40detalle . '", ' . $DATA['unae40idaprueba'] . ', 
			' . $DATA['unae40fechaapr'] . ', ' . $DATA['unae40horaaprueba'] . ', ' . $DATA['unae40minaprueba'] . ', ' . $DATA['unae40tiempod'] . ', ' . $DATA['unae40tiempoh'] . '';
			if ($APP->utf8 == 1) {
				$sSQL = 'INSERT INTO unae40historialcambdoc (' . $sCampos240 . ') VALUES (' . utf8_encode($sValores240) . ');';
				$sdetalle = $sCampos240 . '[' . utf8_encode($sValores240) . ']';
			} else {
				$sSQL = 'INSERT INTO unae40historialcambdoc (' . $sCampos240 . ') VALUES (' . $sValores240 . ');';
				$sdetalle = $sCampos240 . '[' . $sValores240 . ']';
			}
			$idAccion = 2;
			$bPasa = true;
		} else {
			$scampo[1] = 'unae40tipodocdestino';
			$scampo[2] = 'unae40docdestino';
			$scampo[3] = 'unae40des_nombre1';
			$scampo[4] = 'unae40des_nombre2';
			$scampo[5] = 'unae40des_apellido1';
			$scampo[6] = 'unae40des_apellido2';
			$scampo[7] = 'unae40des_sexo';
			$scampo[8] = 'unae40des_fechanac';
			$scampo[9] = 'unae40des_fechadoc';
			$scampo[10] = 'unae40estado';
			$scampo[11] = 'unae40detalle';
			$scampo[12] = 'unae40fechasol';
			$scampo[13] = 'unae40horasol';
			$scampo[14] = 'unae40minsol';
			$scampo[15] = 'unae40fechaapr';
			$sdato[1] = $DATA['unae40tipodocdestino'];
			$sdato[2] = $DATA['unae40docdestino'];
			$sdato[3] = $DATA['unae40des_nombre1'];
			$sdato[4] = $DATA['unae40des_nombre2'];
			$sdato[5] = $DATA['unae40des_apellido1'];
			$sdato[6] = $DATA['unae40des_apellido2'];
			$sdato[7] = $DATA['unae40des_sexo'];
			$sdato[8] = $DATA['unae40des_fechanac'];
			$sdato[9] = $DATA['unae40des_fechadoc'];
			$sdato[10] = $DATA['unae40estado'];
			$sdato[11] = $unae40detalle;
			$sdato[12] = $DATA['unae40fechasol'];
			$sdato[13] = $DATA['unae40horasol'];
			$sdato[14] = $DATA['unae40minsol'];
			$sdato[15] = $DATA['unae40fechaapr'];
			$numcmod=15;
			$sWhere = 'unae40id=' . $DATA['unae40id'] . '';
			$sSQL = 'SELECT * FROM unae40historialcambdoc WHERE ' . $sWhere;
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
					$sSQL = 'UPDATE unae40historialcambdoc SET ' . utf8_encode($sdatos) . ' WHERE ' . $sWhere . ';';
				} else {
					$sdetalle = $sdatos . '[' . $sWhere . ']';
					$sSQL = 'UPDATE unae40historialcambdoc SET ' . $sdatos . ' WHERE ' . $sWhere . ';';
				}
				$idAccion = 3;
			}
		}
		if ($bPasa) {
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . ' Guardar 240 ' . $sSQL . '<br>';
			}
			$result = $objDB->ejecutasql($sSQL);
			if ($result == false) {
				$sError = $ERR['falla_guardar'] . ' [240] ..<!-- ' . $sSQL . ' -->';
				if ($idAccion == 2) {
					$DATA['unae40id'] = '';
					$bQuitarCodigo = true;
					$DATA['paso'] = 0;
				} else {
					$DATA['paso'] = 2;
				}
				$bCerrando = false;
			} else {
				if ($bAudita[$idAccion]) {
					seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], $idAccion, $DATA['unae40id'], $sdetalle, $objDB);
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
		list($sErrorCerrando, $sDebugCerrar) = f240_Cerrar($DATA['unae40id'], $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugCerrar;
	}
	/*
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' InfoDepura<br>';
	}
	*/
	return array($DATA, $sError, $iTipoError, $bCerrando, $sErrorCerrando, $sDebug);
}
function f240_db_Eliminar($unae40id, $objDB, $bDebug = false)
{
	$iCodModulo = 240;
	$bAudita[4] = true;
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_240 = 'lg/lg_240_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_240)) {
		$mensajes_240 = 'lg/lg_240_es.php';
	}
	require $mensajes_todas;
	require $mensajes_240;
	$sError = '';
	$iTipoError = 0;
	$sDebug = '';
	$unae40id = numeros_validar($unae40id);
	// Traer los datos para hacer las validaciones.
	if ($sError == '') {
		$sSQL = 'SELECT * FROM unae40historialcambdoc WHERE unae40id=' . $unae40id . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$filabase = $objDB->sf($tabla);
		} else {
			$sError = 'No se encuentra el registro solicitado {Ref: ' . $unae40id . '}';
		}
	}
	if ($sError == '') {
		if (isset($idTercero) == 0) {
			$idTercero = $_SESSION['unad_id_tercero'];
		}
		$bDevuelve = true;
		// list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 4, $idTercero, $objDB);
		if (!$bDevuelve) {
			$sError = $ERR['4'];
		}
	}
	if ($sError == '') {
		$sSQL = 'SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=240';
		$tablaor = $objDB->ejecutasql($sSQL);
		while ($filaor = $objDB->sf($tablaor)) {
			$sSQL = 'SELECT ' . $filaor['campoid'] . ' FROM ' . $filaor['tabla'] . ' WHERE ' . $filaor['camporev'] . '=' . $_REQUEST['unae40id'] . ' LIMIT 0, 1';
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
		$sWhere = 'unae40id=' . $unae40id . '';
		//$sWhere = 'unae40consec=' . $filabase['unae40consec'] . ' AND unae40idtercero="' . $filabase['unae40idtercero'] . '"';
		$sSQL = 'DELETE FROM unae40historialcambdoc WHERE ' . $sWhere . ';';
		$result = $objDB->ejecutasql($sSQL);
		if ($result == false) {
			$sError = $ERR['falla_eliminar'] . ' .. <!-- ' . $sSQL . ' -->';
		} else {
			if ($bAudita[4]) {
				seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $unae40id, $sWhere, $objDB);
			}
		}
	}
	return array($sError, $iTipoError, $sDebug);
}
function f240_TituloBusqueda()
{
	return 'Busqueda de Solicitud de cambio de documento';
}
function f240_ParametrosBusqueda()
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_240 = 'lg/lg_240_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_240)) {
		$mensajes_240 = 'lg/lg_240_es.php';
	}
	require $mensajes_todas;
	require $mensajes_240;
	$sParams = '<label class="Label90">
	' . $ETI['msg_bnombre'] . '
	</label>
	<label>
	<input id="b240nombre" name="b240nombre" type="text" value="" onchange="paginarbusqueda()" />
	</label>';
	return $sParams;
}
function f240_JavaScriptBusqueda($iModuloBusca)
{
	$sRes = 'var sCampo = window.document.frmedita.scampobusca.value;
	var params = new Array();
	params[100] = sCampo;
	params[101] = window.document.frmedita.paginabusqueda.value;
	params[102] = window.document.frmedita.lppfbusqueda.value;
	params[103] = window.document.frmedita.b240nombre.value;
	xajax_f' . $iModuloBusca . '_HtmlBusqueda(params);';
	return $sRes;
}
function f240_TablaDetalleBusquedas($aParametros, $objDB)
{
	$res = '';
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_240 = 'lg/lg_240_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_240)) {
		$mensajes_240 = 'lg/lg_240_es.php';
	}
	require $mensajes_todas;
	require $mensajes_240;
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
	$sBotones = '<input id="paginaf240" name="paginaf240" type="hidden" value="' . $pagina . '" />
	<input id="lppf240" name="lppf240" type="hidden" value="' . $lineastabla . '" />';
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
	$sTitulos = 'Tercero, Consec, Id, Tipodocorigen, Docorigen, Or_nombre1, Or_nombre2, Or_apellido1, Or_apellido2, Or_sexo, Or_fechanac, Or_fechadoc, Tipodocdestino, Docdestino, Des_nombre1, Des_nombre2, Des_apellido1, Des_apellido2, Des_sexo, Des_fechanac, Des_fechadoc, Solicita, Fechasol, Horasol, Minsol, Origen, Archivo, Estado, Detalle, Aprueba, Fechaapr, Horaaprueba, Minaprueba, Tiempod, Tiempoh';
	$sSQL = 'SELECT T1.unad11razonsocial AS C1_nombre, TB.unae40consec, TB.unae40id, TB.unae40tipodocorigen, TB.unae40docorigen, TB.unae40or_nombre1, TB.unae40or_nombre2, TB.unae40or_apellido1, TB.unae40or_apellido2, TB.unae40or_sexo, TB.unae40or_fechanac, TB.unae40or_fechadoc, TB.unae40tipodocdestino, TB.unae40docdestino, TB.unae40des_nombre1, TB.unae40des_nombre2, TB.unae40des_apellido1, TB.unae40des_apellido2, TB.unae40des_sexo, TB.unae40des_fechanac, TB.unae40des_fechadoc, T22.unad11razonsocial AS C22_nombre, TB.unae40fechasol, TB.unae40horasol, TB.unae40minsol, TB.unae40idorigen, TB.unae40idarchivo, TB.unae40estado, TB.unae40detalle, T30.unad11razonsocial AS C30_nombre, TB.unae40fechaapr, TB.unae40horaaprueba, TB.unae40minaprueba, TB.unae40tiempod, TB.unae40tiempoh, TB.unae40idtercero, T1.unad11tipodoc AS C1_td, T1.unad11doc AS C1_doc, TB.unae40idsolicita, T22.unad11tipodoc AS C22_td, T22.unad11doc AS C22_doc, TB.unae40idaprueba, T30.unad11tipodoc AS C30_td, T30.unad11doc AS C30_doc 
	FROM unae40historialcambdoc AS TB, unad11terceros AS T1, unad11terceros AS T22, unad11terceros AS T30 
	WHERE ' . $sSQLadd1 . ' TB.unae40idtercero=T1.unad11id AND TB.unae40idsolicita=T22.unad11id AND TB.unae40idaprueba=T30.unad11id ' . $sSQLadd . '
	ORDER BY TB.unae40idtercero, TB.unae40consec';
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
			$limite = ' LIMIT ' . $rbase . ', ' . $lineastabla;
			$tabladetalle = $objDB->ejecutasql($sSQL . $limite);
		}
	}
	$res = $sErrConsulta . $sLeyenda;
	$res = $res . '<div class="table-responsive">
	<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">
	<thead class="fondoazul"><tr>
	<td colspan="2"><b>' . $ETI['unae40idtercero'] . '</b></td>
	<td><b>' . $ETI['unae40consec'] . '</b></td>
	<td><b>' . $ETI['unae40tipodocorigen'] . '</b></td>
	<td><b>' . $ETI['unae40docorigen'] . '</b></td>
	<td><b>' . $ETI['unae40tipodocdestino'] . '</b></td>
	<td><b>' . $ETI['unae40docdestino'] . '</b></td>
	<td colspan="2"><b>' . $ETI['unae40idsolicita'] . '</b></td>
	<td><b>' . $ETI['unae40fechasol'] . '</b></td>
	<td><b>' . $ETI['unae40horasol'] . '</b></td>
	<td><b>' . $ETI['unae40idarchivo'] . '</b></td>
	<td><b>' . $ETI['unae40estado'] . '</b></td>
	<td><b>' . $ETI['unae40detalle'] . '</b></td>
	<td colspan="2"><b>' . $ETI['unae40idaprueba'] . '</b></td>
	<td><b>' . $ETI['unae40fechaapr'] . '</b></td>
	<td><b>' . $ETI['unae40horaaprueba'] . '</b></td>
	<td><b>' . $ETI['unae40tiempod'] . '</b></td>
	<td><b>' . $ETI['unae40tiempoh'] . '</b></td>
	<td align="right">
	' . html_paginador('paginabusqueda', $registros, $lineastabla, $pagina, 'paginarbusqueda()') . '
	' . html_lpp('lppfbusqueda', $lineastabla, 'paginarbusqueda()') . '
	</td>
	</tr></thead>';
	$tlinea = 1;
	while ($filadet = $objDB->sf($tabladetalle)) {
		$sPrefijo = '<a href="javascript:Devuelve(\'' . $filadet['unae40id'] . '\');">';
		$sSufijo = '</a>';
		$tlinea++;
		$et_unae40fechasol = '';
		if ($filadet['unae40fechasol'] != 0) {
			$et_unae40fechasol = fecha_desdenumero($filadet['unae40fechasol']);
		}
		$et_unae40horasol = html_TablaHoraMin($filadet['unae40horasol'], $filadet['unae40minsol']);
		$et_unae40idarchivo = '';
		if ($filadet['unae40idarchivo'] != 0) {
			//$et_unae40idarchivo = '<img src="verarchivo.php?cont=' . $filadet['unae40idorigen'] . '&id=' . $filadet['unae40idarchivo'] . '&maxx=150" />';
			$et_unae40idarchivo = html_lnkarchivo((int)$filadet['unae40idorigen'], (int)$filadet['unae40idarchivo']);
		}
		$et_unae40estado = $ETI['msg_abierto'];
		if ($filadet['unae40estado'] == 7) {
			$et_unae40estado = $ETI['msg_cerrado'];
		}
		$et_unae40fechaapr = '';
		if ($filadet['unae40fechaapr'] != 0) {
			$et_unae40fechaapr = fecha_desdenumero($filadet['unae40fechaapr']);
		}
		$et_unae40horaaprueba = html_TablaHoraMin($filadet['unae40horaaprueba'], $filadet['unae40minaprueba']);
		$res = $res . '<tr onmouseover="cambia_color_over(this);" onmouseout="cambia_color_out(this);">
		<td>' . $sPrefijo . $filadet['C1_td'] . ' ' . $filadet['C1_doc'] . $sSufijo . '</td>
		<td>' . $sPrefijo . cadena_notildes($filadet['C1_nombre']) . $sSufijo . '</td>
		<td>' . $sPrefijo . $filadet['unae40consec'] . $sSufijo . '</td>
		<td>' . $sPrefijo . cadena_notildes($filadet['unae40tipodocorigen']) . $sSufijo . '</td>
		<td>' . $sPrefijo . cadena_notildes($filadet['unae40docorigen']) . $sSufijo . '</td>
		<td>' . $sPrefijo . $filadet['unae40tipodocdestino'] . $sSufijo . '</td>
		<td>' . $sPrefijo . cadena_notildes($filadet['unae40docdestino']) . $sSufijo . '</td>
		<td>' . $sPrefijo.$filadet['C8_td'] . ' ' . $filadet['C8_doc'] . $sSufijo . '</td>
		<td>' . $sPrefijo.cadena_notildes($filadet['C8_nombre']) . $sSufijo . '</td>
		<td>' . $sPrefijo . $et_unae40fechasol . $sSufijo . '</td>
		<td>' . $sPrefijo . $et_unae40horasol . $sSufijo . '</td>
		<td>' . $et_unae40idarchivo . '</td>
		<td>' . $sPrefijo . $et_unae40estado . $sSufijo . '</td>
		<td>' . $sPrefijo . $filadet['unae40detalle'] . $sSufijo . '</td>
		<td>' . $sPrefijo . $filadet['C30_td'] . ' ' . $filadet['C30_doc'] . $sSufijo . '</td>
		<td>' . $sPrefijo . cadena_notildes($filadet['C30_nombre']) . $sSufijo . '</td>
		<td>' . $sPrefijo . $et_unae40fechaapr . $sSufijo . '</td>
		<td>' . $sPrefijo . $et_unae40horaaprueba . $sSufijo . '</td>
		<td>' . $sPrefijo . $filadet['unae40tiempod'] . $sSufijo . '</td>
		<td>' . $sPrefijo . $filadet['unae40tiempoh'] . $sSufijo . '</td>
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