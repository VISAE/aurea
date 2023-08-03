<?php
/*
--- © Cristhiam Dario Silva Chavez - UNAD - 2022 ---
--- cristhiam.silva@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.28.4b viernes, 21 de octubre de 2022
--- Modelo Versión 2.29.3 viernes, 14 de abril de 2023
--- 3814 cipa14clasecipas
*/
/** Archivo lib3814.php.
 * Libreria 3814 cipa14clasecipas.
 * @author Cristhiam Dario Silva Chavez - cristhiam.silva@unad.edu.co
 * @date viernes, 21 de octubre de 2022
 */
function f3814_ExisteDato($datos)
{
	if (!is_array($datos)) {
		$datos = json_decode(str_replace('\"', '"', $datos), true);
	}
	$_SESSION['u_ultimominuto'] = iminutoavance();
	$bHayLlave = true;
	$cipa14consec = numeros_validar($datos[1]);
	if ($cipa14consec == '') {
		$bHayLlave = false;
	}
	if ($bHayLlave) {
		require './app.php';
		$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto != '') {
			$objDB->dbPuerto = $APP->dbpuerto;
		}
		$objDB->xajax();
		$sSQL = 'SELECT 1 FROM cipa14clasecipas WHERE cipa14consec=' . $cipa14consec . '';
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
function f3814_Busquedas($aParametros)
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
	$mensajes_3814 = 'lg/lg_3814_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_3814)) {
		$mensajes_3814 = 'lg/lg_3814_es.php';
	}
	require $mensajes_todas;
	require $mensajes_3814;
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
	$sTitulo = '<h2>' . $ETI['titulo_3814'] . ' - ' . $sTitulo . '</h2>';
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_97titulo', 'innerHTML', $sTitulo);
	$objResponse->assign('div_97params', 'innerHTML', $sParams);
	$objResponse->assign('div_97tabla', 'innerHTML', $sTabla);
	$objResponse->setFunction('paginarbusqueda', '', $sJavaBusqueda);
	$objResponse->call('expandesector(97)');
	return $objResponse;
}
function f3814_HtmlBusqueda($aParametros)
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
function f3814_TablaDetalleV2($aParametros, $objDB, $bDebug = false)
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
	$mensajes_3814 = 'lg/lg_3814_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_3814)) {
		$mensajes_3814 = 'lg/lg_3814_es.php';
	}
	require $mensajes_3814;
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
	$sBotones = '<input id="paginaf3814" name="paginaf3814" type="hidden" value="' . $pagina . '"/>
	<input id="lppf3814" name="lppf3814" type="hidden" value="' . $lineastabla . '"/>';
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
	$sTitulos = 'Consec, Id, Orden, Activo, Nombre';
	$registros = 0;
	$bGigante = false; //En caso de que la tabla sea muy grande pasarlo a true
	$sLimite = '';
	$sCampos = 'SELECT TB.cipa14consec, TB.cipa14id, TB.cipa14orden, TB.cipa14activo, TB.cipa14nombre';
	$sConsulta = 'FROM cipa14clasecipas AS TB 
	WHERE ' . $sSQLadd1 . ' TB.cipa14id>0 ' . $sSQLadd . '';
	$sOrden = 'ORDER BY TB.cipa14consec';
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
	$sErrConsulta = '<input id="consulta_3814" name="consulta_3814" type="hidden" value="' . $sSQLlista . '"/>
	<input id="titulos_3814" name="titulos_3814" type="hidden" value="' . $sTitulos . '"/>';
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' Consulta 3814: ' . $sSQL . '<br>';
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
	<td><b>' . $ETI['cipa14consec'] . '</b></td>
	<td><b>' . $ETI['cipa14nombre'] . '</b></td>
	<td><b>' . $ETI['cipa14orden'] . '</b></td>
	<td><b>' . $ETI['cipa14activo'] . '</b></td>
	<td align="right">
	' . html_paginador('paginaf3814', $registros, $lineastabla, $pagina, 'paginarf3814()') . '
	' . html_lpp('lppf3814', $lineastabla, 'paginarf3814()') . '
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
		$et_cipa14activo = $sPrefijo . $ETI['si'] . $sSufijo;
		if ($filadet['cipa14activo'] == 0) {
			$et_cipa14activo = $sPrefijo . $ETI['no'] . $sSufijo;
		}
		if ($bAbierta) {
			$sLink = '<a href="javascript:cargaridf3814(' . $filadet['cipa14id'] . ')" class="lnkresalte">' . $ETI['lnk_cargar'] . '</a>';
		}
		$res = $res . '<tr' . $sClass . '>
		<td>' . $sPrefijo . $filadet['cipa14consec'] . $sSufijo . '</td>
		<td>' . $sPrefijo . cadena_notildes($filadet['cipa14nombre']) . $sSufijo . '</td>
		<td>' . $sPrefijo . $filadet['cipa14orden'] . $sSufijo . '</td>
		<td>' . $sPrefijo . $et_cipa14activo . $sSufijo . '</td>
		<td>' . $sLink . '</td>
		</tr>';
	}
	$res = $res . '</table>
	<div class="salto5px"></div>
	</div>';
	$objDB->liberar($tabladetalle);
	return array(cadena_codificar($res), $sDebug);
}
function f3814_HtmlTabla($aParametros)
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
	list($sDetalle, $sDebugTabla) = f3814_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_f3814detalle', 'innerHTML', $sDetalle);
	if ($bDebug) {
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
	}
	return $objResponse;
}
function f3814_db_CargarPadre($DATA, $objDB, $bDebug = false)
{
	$sError = '';
	$iTipoError = 0;
	$sDebug = '';
	require './app.php';
	if ($DATA['paso'] == 1) {
		$sSQLcondi = 'cipa14consec=' . $DATA['cipa14consec'] . '';
	} else {
		$sSQLcondi = 'cipa14id=' . $DATA['cipa14id'] . '';
	}
	$sSQL = 'SELECT * FROM cipa14clasecipas WHERE ' . $sSQLcondi;
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$DATA['cipa14consec'] = $fila['cipa14consec'];
		$DATA['cipa14id'] = $fila['cipa14id'];
		$DATA['cipa14orden'] = $fila['cipa14orden'];
		$DATA['cipa14activo'] = $fila['cipa14activo'];
		$DATA['cipa14nombre'] = $fila['cipa14nombre'];
		$DATA['cipa14tipo'] = $fila['cipa14tipo'];
		$bcargo = true;
		$DATA['paso'] = 2;
		$DATA['boculta3814'] = 0;
		$bLimpiaHijos = true;
	} else {
		$DATA['paso'] = 0;
	}
	return array($DATA, $sError, $iTipoError, $sDebug);
}
function f3814_db_GuardarV2($DATA, $objDB, $bDebug = false, $idTercero = 0)
{
	$iCodModulo = 3814;
	$bAudita[2] = true;
	$bAudita[3] = true;
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_3814 = 'lg/lg_3814_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_3814)) {
		$mensajes_3814 = 'lg/lg_3814_es.php';
	}
	require $mensajes_todas;
	require $mensajes_3814;
	$sError = '';
	$iTipoError = 0;
	$sDebug = '';
	if ($idTercero == 0) {
		$idTercero = $_SESSION['unad_id_tercero'];
	}
	// -- Se inicia validando todas las posibles entradas de usuario.
	/*
	if (isset($DATA['cipa14consec']) == 0) {
		$DATA['cipa14consec'] = '';
	}
	if (isset($DATA['cipa14id']) == 0) {
		$DATA['cipa14id'] = '';
	}
	if (isset($DATA['cipa14orden']) == 0) {
		$DATA['cipa14orden'] = '';
	}
	if (isset($DATA['cipa14activo']) == 0) {
		$DATA['cipa14activo'] = 0;
	}
	if (isset($DATA['cipa14nombre']) == 0) {
		$DATA['cipa14nombre'] = '';
	}
	if (isset($DATA['cipa14tipo']) == 0) {
		$DATA['cipa14tipo'] = 0;
	}
	*/
	$DATA['cipa14consec'] = numeros_validar($DATA['cipa14consec']);
	$DATA['cipa14orden'] = numeros_validar($DATA['cipa14orden']);
	$DATA['cipa14activo'] = numeros_validar($DATA['cipa14activo']);
	$DATA['cipa14nombre'] = htmlspecialchars(trim($DATA['cipa14nombre']));
	$DATA['cipa14tipo'] = numeros_validar($DATA['cipa14tipo']);
	// -- Se inicializan las variables que puedan pasar vacias {Especialmente números}.
	/*
	if ($DATA['cipa14activo'] == '') {
		$DATA['cipa14activo'] = 0;
	}
	if ($DATA['cipa14tipo'] == '') {
		$DATA['cipa14tipo'] = 0;
	}
	*/
	// -- Seccion para validar los posibles causales de error.
	$sSepara = ', ';
	if (true) {
		if ($DATA['cipa14nombre'] == '') {
			$sError = $ERR['cipa14nombre'] . $sSepara . $sError;
		}
		if ($DATA['cipa14activo'] == '') {
			$sError = $ERR['cipa14activo'] . $sSepara . $sError;
		}
		/*
		if ($DATA['cipa14orden'] == '') {
			$sError = $ERR['cipa14orden'] . $sSepara . $sError;
			//ORDEN
		}
		*/
		//Fin de las valiaciones NO LLAVE.
	}
	//Valiaciones de campos obligatorios en todo guardar.
	if ($sError == '') {
		$aListaCampos = array('', 'cipa14nombre');
		$aLargoCampos = array(0, 50);
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
			if ($DATA['cipa14consec'] == '') {
				$DATA['cipa14consec'] = tabla_consecutivo('cipa14clasecipas', 'cipa14consec', '', $objDB);
				if ($DATA['cipa14consec'] == -1) {
					$sError = $objDB->serror;
				}
				$bQuitarCodigo = true;
				$sCampoCodigo = 'cipa14consec';
			} else {
				list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 8, $idTercero, $objDB);
				if (!$bDevuelve) {
					$sError = $ERR['8'];
					$DATA['cipa14consec'] = '';
				}
			}
			if ($sError == '') {
				$sSQL = 'SELECT 1 FROM cipa14clasecipas WHERE cipa14consec=' . $DATA['cipa14consec'] . '';
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
			$DATA['cipa14id'] = tabla_consecutivo('cipa14clasecipas', 'cipa14id', '', $objDB);
			if ($DATA['cipa14id'] == -1) {
				$sError = $objDB->serror;
			}
			//Datos adicionales al iniciar un registro.
		}
	}
	if ($sError == '') {
		if ((int)$DATA['cipa14orden'] == 0) {
			$DATA['cipa14orden'] = $DATA['cipa14consec'];
		}
		$bPasa = false;
		if ($DATA['paso'] == 10) {
			$sCampos3814 = 'cipa14consec, cipa14id, cipa14orden, cipa14activo, cipa14nombre, 
			cipa14tipo';
			$sValores3814 = '' . $DATA['cipa14consec'] . ', ' . $DATA['cipa14id'] . ', "' . $DATA['cipa14orden'] . '", ' . $DATA['cipa14activo'] . ', "' . $DATA['cipa14nombre'] . '", 
			' . $DATA['cipa14tipo'] . '';
			if ($APP->utf8 == 1) {
				$sSQL = 'INSERT INTO cipa14clasecipas (' . $sCampos3814 . ') VALUES (' . cadena_codificar($sValores3814) . ');';
				$sdetalle = $sCampos3814 . '[' . cadena_codificar($sValores3814) . ']';
			} else {
				$sSQL = 'INSERT INTO cipa14clasecipas (' . $sCampos3814 . ') VALUES (' . $sValores3814 . ');';
				$sdetalle = $sCampos3814 . '[' . $sValores3814 . ']';
			}
			$idAccion = 2;
			$bPasa = true;
		} else {
			$scampo[1] = 'cipa14orden';
			$scampo[2] = 'cipa14activo';
			$scampo[3] = 'cipa14nombre';
			$scampo[4] = 'cipa14tipo';
			$sdato[1] = $DATA['cipa14orden'];
			$sdato[2] = $DATA['cipa14activo'];
			$sdato[3] = $DATA['cipa14nombre'];
			$sdato[4] = $DATA['cipa14tipo'];
			$iNumCamposMod = 4;
			$sWhere = 'cipa14id=' . $DATA['cipa14id'] . '';
			$sSQL = 'SELECT * FROM cipa14clasecipas WHERE ' . $sWhere;
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
					$sSQL = 'UPDATE cipa14clasecipas SET ' . cadena_codificar($sdatos) . ' WHERE ' . $sWhere . ';';
				} else {
					$sdetalle = $sdatos . '[' . $sWhere . ']';
					$sSQL = 'UPDATE cipa14clasecipas SET ' . $sdatos . ' WHERE ' . $sWhere . ';';
				}
				$idAccion = 3;
			}
		}
		if ($bPasa) {
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . ' Guardar 3814 ' . $sSQL . '<br>';
			}
			$result = $objDB->ejecutasql($sSQL);
			if ($result == false) {
				$sError = $ERR['falla_guardar'] . ' [3814] ..<!-- ' . $sSQL . ' -->';
				if ($idAccion == 2) {
					$DATA['cipa14id'] = '';
					$bQuitarCodigo = true;
					$DATA['paso'] = 0;
				} else {
					$DATA['paso'] = 2;
				}
			} else {
				if ($bAudita[$idAccion]) {
					seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], $idAccion, $DATA['cipa14id'], $sdetalle, $objDB);
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
function f3814_db_Eliminar($cipa14id, $objDB, $bDebug = false)
{
	$iCodModulo = 3814;
	$bAudita[4] = true;
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_3814 = 'lg/lg_3814_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_3814)) {
		$mensajes_3814 = 'lg/lg_3814_es.php';
	}
	require $mensajes_todas;
	require $mensajes_3814;
	$sError = '';
	$iTipoError = 0;
	$sDebug = '';
	$cipa14id = numeros_validar($cipa14id);
	// Traer los datos para hacer las validaciones.
	if ($sError == '') {
		$sSQL = 'SELECT * FROM cipa14clasecipas WHERE cipa14id=' . $cipa14id . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$filabase = $objDB->sf($tabla);
		} else {
			$sError = 'No se encuentra el registro solicitado {Ref: ' . $cipa14id . '}';
		}
	}
	if ($sError == '') {
		$sSQL = 'SELECT 1 FROM cipa01oferta WHERE cipa01clase=' . $cipa14id . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$sError = 'Ya existe programaci&oacute;n de esta clase de CIPAS';
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
		$sSQL = 'SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=3814';
		$tablaor = $objDB->ejecutasql($sSQL);
		while ($filaor = $objDB->sf($tablaor)) {
			$sSQL = 'SELECT ' . $filaor['campoid'] . ' FROM ' . $filaor['tabla'] . ' WHERE ' . $filaor['camporev'] . '=' . $_REQUEST['cipa14id'] . ' LIMIT 0, 1';
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
		$sWhere = 'cipa14id=' . $cipa14id . '';
		//$sWhere = 'cipa14consec=' . $filabase['cipa14consec'] . '';
		$sSQL = 'DELETE FROM cipa14clasecipas WHERE ' . $sWhere . ';';
		$result = $objDB->ejecutasql($sSQL);
		if ($result == false) {
			$sError = $ERR['falla_eliminar'] . ' .. <!-- ' . $sSQL . ' -->';
		} else {
			if ($bAudita[4]) {
				seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $cipa14id, $sWhere, $objDB);
			}
		}
	}
	return array($sError, $iTipoError, $sDebug);
}
function f3814_TituloBusqueda()
{
	require './app.php';
	$mensajes_3814 = 'lg/lg_3814_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_3814)) {
		$mensajes_3814 = 'lg/lg_3814_es.php';
	}
	require $mensajes_3814;
	return $ETI['titulo_busca_3814'];
}
function f3814_ParametrosBusqueda()
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_3814 = 'lg/lg_3814_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_3814)) {
		$mensajes_3814 = 'lg/lg_3814_es.php';
	}
	require $mensajes_todas;
	require $mensajes_3814;
	$sParams = '<label class="Label90">
	' . $ETI['msg_bnombre'] . '
	</label>
	<label>
	<input id="b3814nombre" name="b3814nombre" type="text" value="" onchange="paginarbusqueda()" />
	</label>';
	return $sParams;
}
function f3814_JavaScriptBusqueda($iModuloBusca)
{
	$sRes = 'let sCampo = window.document.frmedita.scampobusca.value;
	let params = new Array();
	params[100] = sCampo;
	params[101] = window.document.frmedita.paginabusqueda.value;
	params[102] = window.document.frmedita.lppfbusqueda.value;
	params[103] = window.document.frmedita.b3814nombre.value;
	xajax_f' . $iModuloBusca . '_HtmlBusqueda(params);';
	return $sRes;
}
function f3814_TablaDetalleBusquedas($aParametros, $objDB)
{
	$res = '';
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_3814 = 'lg/lg_3814_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_3814)) {
		$mensajes_3814 = 'lg/lg_3814_es.php';
	}
	require $mensajes_todas;
	require $mensajes_3814;
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
	$sBotones = '<input id="paginaf3814" name="paginaf3814" type="hidden" value="' . $pagina . '" />
	<input id="lppf3814" name="lppf3814" type="hidden" value="' . $lineastabla . '" />';
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
	$sTitulos = 'Consec, Id, Orden, Activo, Nombre, Tipo';
	$sCampos = 'SELECT TB.cipa14consec, TB.cipa14id, TB.cipa14orden, TB.cipa14activo, TB.cipa14nombre, T6.cipa15nombre, TB.cipa14tipo';
	$sConsulta = 'FROM cipa14clasecipas AS TB, cipa15tipocipa AS T6 
	WHERE ' . $sSQLadd1 . ' TB.cipa14tipo=T6.cipa15id ' . $sSQLadd . '';
	$sOrden = 'ORDER BY TB.cipa14consec';
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
	<td><b>' . $ETI['cipa14consec'] . '</b></td>
	<td><b>' . $ETI['cipa14orden'] . '</b></td>
	<td><b>' . $ETI['cipa14activo'] . '</b></td>
	<td><b>' . $ETI['cipa14nombre'] . '</b></td>
	<td align="right">
	' . html_paginador('paginabusqueda', $registros, $lineastabla, $pagina, 'paginarbusqueda()') . '
	' . html_lpp('lppfbusqueda', $lineastabla, 'paginarbusqueda()') . '
	</td>
	</tr></thead>';
	$tlinea = 1;
	while ($filadet = $objDB->sf($tabladetalle)) {
		$sPrefijo = '<a href="javascript:Devuelve(\'' . $filadet['cipa14id'] . '\');">';
		$sSufijo = '</a>';
		$tlinea++;
		$et_cipa14activo = $sPrefijo . $ETI['no'] . $sSufijo;
		if ($filadet['cipa14activo'] == 'S') {
			$et_cipa14activo = $sPrefijo . $ETI['si'] . $sSufijo;
		}
		$res = $res . '<tr onmouseover="cambia_color_over(this);" onmouseout="cambia_color_out(this);">
		<td>' . $sPrefijo . $filadet['cipa14consec'] . $sSufijo . '</td>
		<td>' . $sPrefijo . $filadet['cipa14orden'] . $sSufijo . '</td>
		<td>' . $sPrefijo . $filadet['cipa14activo'] . $sSufijo . '</td>
		<td>' . $sPrefijo . cadena_notildes($filadet['cipa14nombre']) . $sSufijo . '</td>
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
