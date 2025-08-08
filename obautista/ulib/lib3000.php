<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2020 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.25.3 sábado, 18 de julio de 2020
--- 3000 Historial de solicitudes
*/
function f3000_ContenedorTercero($idTercero, $objDB, $bDebug = false)
{
	$idContenedor = 0;
	$sError = '';
	$sDebug = '';
	list($idContenedor, $sError) = f1011_BloqueTercero($idTercero, $objDB);
	$sTabla = 'saiu23inventario_' . $idContenedor;
	if (!$objDB->bexistetabla($sTabla)) {
		list($sError, $sDebug) = f3000_TablasInventario($idContenedor, $objDB);
	}
	return array($idContenedor, $sError, $sDebug);
}
function f3000_Registrar($valores, $objDB, $bDebug = false)
{
	$iCodModulo = 3000;
	$bAudita[2] = false;
	$bAudita[3] = false;
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_3000 = 'lg/lg_3000_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_3000)) {
		$mensajes_3000 = 'lg/lg_3000_es.php';
	}
	require $mensajes_todas;
	require $mensajes_3000;
	$sError = '';
	$sDebug = '';
	$bInserta = false;
	$iAccion = 3;
	if (!is_array($valores)) {
		$valores = json_decode(str_replace('\"', '"', $valores), true);
	}
	$saiu23idtercero = numeros_validar($valores[1]);
	$saiu23modulo = numeros_validar($valores[2]);
	$saiu23tabla = numeros_validar($valores[3]);
	$saiu23idtabla = numeros_validar($valores[4]);
	$saiu23fecha = $valores[5];
	$saiu23idtipo = numeros_validar($valores[6]);
	$saiu23idtema = numeros_validar($valores[7]);
	$saiu23estado = numeros_validar($valores[8]);
	list($idContenedor, $sError, $sDebug) = f3000_ContenedorTercero($saiu23idtercero, $objDB, $bDebug);
	$sTabla = 'saiu23inventario_' . $idContenedor;
	//if ($saiu23idtipo==''){$saiu23idtipo=0;}
	//if ($saiu23idtema==''){$saiu23idtema=0;}
	//if ($saiu23estado==''){$saiu23estado=0;}
	$sSepara = ', ';
	if ($saiu23estado == '') {
		$sError = $ERR['saiu23estado'] . $sSepara . $sError;
	}
	if ($saiu23idtema == '') {
		$sError = $ERR['saiu23idtema'] . $sSepara . $sError;
	}
	if ($saiu23idtipo == '') {
		$sError = $ERR['saiu23idtipo'] . $sSepara . $sError;
	}
	if ($saiu23fecha == 0) {
		//$saiu23fecha=fecha_DiaMod();
		$sError = $ERR['saiu23fecha'] . $sSepara . $sError;
	}
	if ($saiu23idtabla == '') {
		$sError = $ERR['saiu23idtabla'] . $sSepara . $sError;
	}
	if ($saiu23tabla == '') {
		$sError = $ERR['saiu23tabla'] . $sSepara . $sError;
	}
	if ($saiu23modulo == '') {
		$sError = $ERR['saiu23modulo'] . $sSepara . $sError;
	}
	if ($saiu23idtercero == '') {
		$sError = $ERR['saiu23idtercero'] . $sSepara . $sError;
	}
	if ($sError == '') {
		$sSQL = 'SELECT saiu23idtercero FROM ' . $sTabla . ' WHERE saiu23idtercero=' . $saiu23idtercero . ' AND saiu23modulo=' . $saiu23modulo . ' AND saiu23tabla=' . $saiu23tabla . ' AND saiu23idtabla=' . $saiu23idtabla . '';
		$result = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($result) == 0) {
			$bInserta = true;
			$iAccion = 2;
		}
	}
	if ($sError == '') {
		if ($bInserta) {
			$sCampos3000 = 'saiu23idtercero, saiu23modulo, saiu23tabla, saiu23idtabla, saiu23fecha, 
			saiu23idtipo, saiu23idtema, saiu23estado';
			$sValores3000 = '' . $saiu23idtercero . ', ' . $saiu23modulo . ', ' . $saiu23tabla . ', ' . $saiu23idtabla . ', "' . $saiu23fecha . '", 
			' . $saiu23idtipo . ', ' . $saiu23idtema . ', ' . $saiu23estado . '';
			if ($APP->utf8 == 1) {
				$sSQL = 'INSERT INTO ' . $sTabla . ' (' . $sCampos3000 . ') VALUES (' . cadena_codificar($sValores3000) . ');';
			} else {
				$sSQL = 'INSERT INTO ' . $sTabla . ' (' . $sCampos3000 . ') VALUES (' . $sValores3000 . ');';
			}
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . ' Guardar 3000 ' . $sSQL . '<br>';
			}
			$result = $objDB->ejecutasql($sSQL);
			if ($result == false) {
				$sError = $ERR['falla_guardar'] . ' [3000].<!-- ' . $sSQL . ' -->';
			}
		} else {
			$scampo3000[1] = 'saiu23fecha';
			$scampo3000[2] = 'saiu23idtipo';
			$scampo3000[3] = 'saiu23idtema';
			$scampo3000[4] = 'saiu23estado';
			$svr3000[1] = $saiu23fecha;
			$svr3000[2] = $saiu23idtipo;
			$svr3000[3] = $saiu23idtema;
			$svr3000[4] = $saiu23estado;
			$iNumCampos = 4;
			$sWhere = 'saiu23idtercero=' . $saiu23idtercero . ' AND saiu23modulo=' . $saiu23modulo . ' AND saiu23tabla=' . $saiu23tabla . ' AND saiu23idtabla=' . $saiu23idtabla . '';
			$sSQL = 'SELECT * FROM ' . $sTabla . ' WHERE ' . $sWhere;
			$sdatos = '';
			$bPasa = false;
			$result = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($result) > 0) {
				$filaorigen = $objDB->sf($result);
				for ($k = 1; $k <= $iNumCampos; $k++) {
					if ($filaorigen[$scampo3000[$k]] != $svr3000[$k]) {
						if ($sdatos != '') {
							$sdatos = $sdatos . ', ';
						}
						$sdatos = $sdatos . $scampo3000[$k] . '="' . $svr3000[$k] . '"';
						$bPasa = true;
					}
				}
			}
			if ($bPasa) {
				if ($APP->utf8 == 1) {
					$sSQL = 'UPDATE ' . $sTabla . ' SET ' . cadena_codificar($sdatos) . ' WHERE ' . $sWhere . ';';
				} else {
					$sSQL = 'UPDATE ' . $sTabla . ' SET ' . $sdatos . ' WHERE ' . $sWhere . ';';
				}
				$result = $objDB->ejecutasql($sSQL);
				if ($result == false) {
					$sError = $ERR['falla_guardar'] . ' {Historial de solicitudes}. <!-- ' . $sSQL . ' -->';
				}
			}
		}
	}
	return array($sError, $iAccion, $sDebug);
}
function f3000_Retirar($aParametros, $objDB, $bDebug = false)
{
	$iCodModulo = 3000;
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_3000 = 'lg/lg_3000_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_3000)) {
		$mensajes_3000 = 'lg/lg_3000_es.php';
	}
	require $mensajes_todas;
	require $mensajes_3000;
	$sError = '';
	$sDebug = '';
	if (!is_array($aParametros)) {
		$aParametros = json_decode(str_replace('\"', '"', $aParametros), true);
	}
	$saiu23idtercero = numeros_validar($aParametros[1]);
	$saiu23modulo = numeros_validar($aParametros[2]);
	$saiu23tabla = numeros_validar($aParametros[3]);
	$saiu23idtabla = numeros_validar($aParametros[4]);
	list($idContenedor, $sError, $sDebug) = f3000_ContenedorTercero($saiu23idtercero, $objDB, $bDebug);
	$sTabla = 'saiu23inventario_' . $idContenedor;
	if ($sError == '') {
		//acciones previas
		$sWhere = 'saiu23idtercero=' . $saiu23idtercero . ' AND saiu23modulo=' . $saiu23modulo . ' AND saiu23tabla=' . $saiu23tabla . ' AND saiu23idtabla=' . $saiu23idtabla . '';
		$sSQL = 'DELETE FROM ' . $sTabla . ' WHERE ' . $sWhere . ';';
		$result = $objDB->ejecutasql($sSQL);
		if ($result == false) {
			$sError = $ERR['falla_eliminar'] . ' {3000 Historial de solicitudes}.<!-- ' . $sSQL . ' -->';
		}
	}
	return array($sError, $sDebug);
}
function f3000_TablaDetalleV2($aParametros, $objDB, $bDebug = false)
{
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_3000 = $APP->rutacomun . 'lg/lg_3000_' . $sIdioma . '.php';
	if (!file_exists($mensajes_3000)) {
		$mensajes_3000 = $APP->rutacomun . 'lg/lg_3000_es.php';
	}
	require $mensajes_todas;
	require $mensajes_3000;
	if (!is_array($aParametros)) {
		$aParametros = json_decode(str_replace('\"', '"', $aParametros), true);
	}
	if (isset($aParametros[0]) == 0) {
		$aParametros[0] = -1;
	}
	if (isset($aParametros[100]) == 0) {
		$aParametros[100] = 0;
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
	if ($aParametros[0] == '') {
		$aParametros[0] = -1;
	}
	$idTercero = numeros_validar($aParametros[0]);
	$sDebug = '';
	// ------------------------------------------------
	// Leemos los parametros de entrada.
	// ------------------------------------------------
	$idsolicitante = numeros_validar($aParametros[100]);
	$pagina = numeros_validar($aParametros[101]);
	$lineastabla = numeros_validar($aParametros[102]);
	$bAbierta = true;
	$sLeyenda = '';
	$sBotones = '<input id="paginaf3000" name="paginaf3000" type="hidden" value="' . $pagina . '"/>';
	$sBotones = $sBotones . '<input id="lppf3000" name="lppf3000" type="hidden" value="' . $lineastabla . '"/>';
	if ((int)$idsolicitante == 0) {
		$sLeyenda = 'No se ha ingresado un documento v&aacute;lido a consultar';
	}
	if ($sLeyenda != '') {
		$sRes = html_salto() . '<div class="GrupoCamposAyuda">' . $sLeyenda . html_salto() . '</div>';
		return array($sRes . $sBotones, $sDebug);
		die();
	}
	$iPiel = iDefinirPiel($APP, 2);
	$sSQLadd = '';
	$sSQLadd1 = '';
	if ($idsolicitante != '') {
		$sSQLadd1 = $sSQLadd1 . ' AND TB.saiu23idtercero=' . $idsolicitante . '';
	}
	// ------------------------------------------------
	// Fin de las condiciones de la consulta
	// ------------------------------------------------
	$sTitulos = 'Tercero, Modulo, Tabla, Tabla, Fecha, Tipo, Tema, Estado';
	$registros = 0;
	list($idContenedor, $sError, $sDebug) = f3000_ContenedorTercero($idsolicitante, $objDB, $bDebug);
	$sTabla = 'saiu23inventario_' . $idContenedor;
	$sCampos = 'SELECT TB.saiu23idtercero, T2.saiu24nombre, TB.saiu23tabla, TB.saiu23idtabla, TB.saiu23fecha, T6.saiu02titulo, T7.saiu03titulo, T8.saiu11nombre, TB.saiu23modulo, TB.saiu23idtipo, TB.saiu23idtema, TB.saiu23estado';
	$sConsulta = 'FROM ' . $sTabla . ' AS TB, saiu24modulossai AS T2, saiu02tiposol AS T6, saiu03temasol AS T7, saiu11estadosol AS T8 
	WHERE TB.saiu23modulo=T2.saiu24id AND TB.saiu23idtipo=T6.saiu02id AND TB.saiu23idtema=T7.saiu03id AND TB.saiu23estado=T8.saiu11id ' . $sSQLadd1 . '';
	$sOrden = 'ORDER BY TB.saiu23fecha DESC, TB.saiu23modulo, TB.saiu23tabla, TB.saiu23idtabla';
	$sSQL = $sCampos . ' ' . $sConsulta . ' ' . $sOrden;
	// ------------------------------------------------
	// Fin de la consulta
	// ------------------------------------------------
	$sSQLlista = str_replace("'", "|", $sSQL);
	$sSQLlista = str_replace('"', "|", $sSQLlista);
	$sErrConsulta = '<input id="consulta_3000" name="consulta_3000" type="hidden" value="' . $sSQLlista . '"/>';
	$sErrConsulta = $sErrConsulta . '<input id="titulos_3000" name="titulos_3000" type="hidden" value="' . $sTitulos . '"/>';
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' Consulta 3000: ' . $sSQL . '<br>';
	}
	$tabladetalle = $objDB->ejecutasql($sSQL);
	if ($tabladetalle == false) {
		$registros = 0;
		$sErrConsulta = $sErrConsulta . '..<input id="err" name="err" type="hidden" value="' . $sSQL . ' ' . $objDB->serror . '"/>';
		//$sLeyenda=$sSQL;
	} else {
		$registros = $objDB->nf($tabladetalle);
		if ($registros == 0) {
			$sLeyenda = $sLeyenda . 'No existen registros de solicitudes.';
			$sErrConsulta = $sErrConsulta . '<div class="salto1px"></div><div class="GrupoCamposAyuda">' . $sLeyenda . '<div class="salto1px"></div></div>';
			return array($sErrConsulta . $sBotones, $sDebug);
		}
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
	$sClaseTabla = 'table--primary';
	if ($iPiel == 1) {
		$sClaseTabla = 'tablaapp';
	}
	$res = $res . '<div class="table-responsive">';
	$res = $res . '<table border="0" align="center" cellpadding="0" cellspacing="2" class="' . $sClaseTabla . '">';
	$res = $res . '<thead class="fondoazul"><tr>';
	$res = $res . '<th><b>' . $ETI['saiu00fecha'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['saiu00idtipo'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['saiu00idtema'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['saiu00estado'] . '</b></th>';
	$res = $res . '<th class="flex gap-1 justify-end">';
	$res = $res . html_paginador('paginaf3000', $registros, $lineastabla, $pagina, 'paginarf3000()');
	$res = $res . html_lpp('lppf3000', $lineastabla, 'paginarf3000()');
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
		$et_boton = $ETI['lnk_consultar'];
		$et_saiu23fecha = '';
		if ($filadet['saiu23fecha'] != 0) {
			$et_saiu23fecha = $sPrefijo . fecha_desdenumero($filadet['saiu23fecha']) . $sSufijo;
		}
		$et_saiu23idtipo = $sPrefijo . cadena_notildes($filadet['saiu02titulo']) . $sSufijo;
		$et_saiu23idtema = $sPrefijo . cadena_notildes($filadet['saiu03titulo']) . $sSufijo;
		$et_saiu23estado = $sPrefijo . cadena_notildes($filadet['saiu11nombre']) . $sSufijo;
		if ($bAbierta) {
			$sURLSaiMod = 'https://aurea2.unad.edu.co/sai/saiusolusuario.php';
			$sArgs = url_encode($filadet['saiu23tabla'] . '|' . $filadet['saiu23idtabla']);
			$sLink = '<a href="' . $sURLSaiMod . '?u=' . $sArgs . '" target="_blank" class="lnkresalte">' . $et_boton . '</a>';
		}
		$res = $res . '<tr' . $sClass . '>';
		$res = $res . '<td>' . $et_saiu23fecha . '</td>';
		$res = $res . '<td>' . $et_saiu23idtipo . '</td>';
		$res = $res . '<td>' . $et_saiu23idtema . '</td>';
		$res = $res . '<td>' . $et_saiu23estado . '</td>';
		$res = $res . '<td align="right">' . $sLink . '</td>';
		$res = $res . '</tr>';
	}
	$res = $res . '</tbody></table>';
	$res = $res . '<div class="salto5px"></div>';
	$res = $res . '</div>';
	$objDB->liberar($tabladetalle);
	return array(cadena_codificar($res), $sDebug);
}
function f3000_HtmlTabla($aParametros)
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
	list($sDetalle, $sDebugTabla) = f3000_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_f3000detalle', 'innerHTML', $sDetalle);
	if ($bDebug) {
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
	}
	return $objResponse;
}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------
function f3000_TablaDetalleAcad($aParametros, $objDB, $bDebug = false)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_12206 = $APP->rutacomun . 'lg/lg_12206_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_12206)) {
		$mensajes_12206 = $APP->rutacomun . 'lg/lg_12206_es.php';
	}
	$mensajes_3000 = $APP->rutacomun . 'lg/lg_3000_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_3000)) {
		$mensajes_3000 = $APP->rutacomun . 'lg/lg_3000_es.php';
	}
	require $mensajes_todas;
	require $mensajes_12206;
	require $mensajes_3000;
	if (!is_array($aParametros)) {
		$aParametros = json_decode(str_replace('\"', '"', $aParametros), true);
	}
	if (isset($aParametros[0]) == 0) {
		$aParametros[0] = -1;
	}
	if (isset($aParametros[100]) == 0) {
		$aParametros[100] = 0;
	}
	if (isset($aParametros[101]) == 0) {
		$aParametros[101] = 1;
	}
	if (isset($aParametros[102]) == 0) {
		$aParametros[102] = 20;
	}
	//if (isset($aParametros[103])==0){$aParametros[103]='';}
	//if (isset($aParametros[104])==0){$aParametros[104]='';}
	//$aParametros[103]=numeros_validar($aParametros[103]);
	//$saiu23idtercero=numeros_validar($aParametros[0]);
	if ($aParametros[0] == '') {
		$aParametros[0] = -1;
	}
	$idTercero = $aParametros[0];
	$sDebug = '';
	$saiu18idsolicitante = $aParametros[100];
	$pagina = $aParametros[101];
	$lineastabla = $aParametros[102];
	$bAbierta = false;
	$sLeyenda = '';
	if ((int)$saiu18idsolicitante == 0) {
		$sLeyenda = 'No se ha ingresado un documento v&aacute;lido a consultar';
	}
	$sBotones = '<input id="paginaf3000" name="paginaf3000" type="hidden" value="' . $pagina . '"/>';
	$sBotones = $sBotones . '<input id="lppf3000" name="lppf3000" type="hidden" value="' . $lineastabla . '"/>';
	if ($sLeyenda != '') {
		$sLeyenda = '<div class="salto1px"></div>
		<div class="GrupoCamposAyuda">
		' . $sLeyenda . '
		<div class="salto1px"></div>
		</div>';
		return array($sLeyenda . $sBotones, $sDebug);
		die();
	}
	$iHoy = fecha_DiaMod();
	list($idContenedor, $sError, $sDebug) = f3000_ContenedorTercero($saiu18idsolicitante, $objDB, $bDebug);
	$sTabla04 = 'core04matricula_' . $idContenedor;

	$aEstado = array();
	$sSQL = 'SELECT core33id, core33nombre FROM core33estadomatricula ';
	$tabla = $objDB->ejecutasql($sSQL);
	while ($fila = $objDB->sf($tabla)) {
		$aEstado[$fila['core33id']] = cadena_notildes($fila['core33nombre']);
	}
	$sSQLadd = '';
	$sSQLadd1 = '';
	$bPuedeRehacer = false;
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' Verificando grupos especiales<br>';
		list($bPuedeRehacer, $sDebugP) = f107_PerfilPertenece($_SESSION['unad_id_tercero'], 1, $objDB);
		if ($bPuedeRehacer) {
			$sDebug = $sDebug . fecha_microtiempo() . ' Tiene permisos especiales<br>';
		}
	}
	//if ((int)$aParametros[103]!=-1){$sSQLadd=$sSQLadd.' AND TB.campo='.$aParametros[103];}
	//if ($aParametros[103]!=''){$sSQLadd=$sSQLadd.' AND TB.campo2 LIKE "%'.$aParametros[103].'%"';}
	/*
	if ($aParametros[103]!=''){
		$sBase=trim(strtoupper($aParametros[103]));
		$aNoms=explode(' ', $sBase);
		for ($k=1;$k<=count($aNoms);$k++){
			$sCadena=$aNoms[$k-1];
			if ($sCadena!=''){
				$sSQLadd=$sSQLadd.' AND T6.unad11razonsocial LIKE "%'.$sCadena.'%"';
				//$sSQLadd1=$sSQLadd1.'T1.unad11razonsocial LIKE "%'.$sCadena.'%" AND ';
				}
			}
		}
	*/
	$sIds2 = '-99';
	/*
	$sSQL='SELECT exte02id FROM exte02per_aca WHERE exte02fechatopetablero>='.$iHoy.'';
	$tabla=$objDB->ejecutasql($sSQL);
	while($fila=$objDB->sf($tabla)){
		$sIds2=$sIds2.','.$fila['exte02id'];
		}
	*/
	$sTitulos = 'Tercero, Modulo, Tabla, Tabla, Fecha, Tipo, Tema, Estado';
	$sSQL = 'SELECT TB.core16peraca, TB.core16idprograma, TB.core16id, T2.exte02nombre, T9.core09codigo, T9.core09nombre, 
	T2.exte02fechatopetablero, T2.exte02oferfechatopecancela, T2.exte02fechatopeaplaza, TB.core16numcursos, TB.cara16numcursosext, 
	TB.cara16numcursospost, T2.exte02tipoperiodo  
	FROM core16actamatricula AS TB, exte02per_aca AS T2, core09programa AS T9 
	WHERE TB.core16tercero=' . $saiu18idsolicitante . ' 
	AND TB.core16peraca=T2.exte02id AND TB.core16idprograma=T9.core09id 
	ORDER BY TB.core16peraca DESC';
	$sSQLlista = str_replace("'", "|", $sSQL);
	$sSQLlista = str_replace('"', "|", $sSQLlista);
	$sErrConsulta = '<input id="consulta_3000" name="consulta_3000" type="hidden" value="' . $sSQLlista . '"/>
	<input id="titulos_3000" name="titulos_3000" type="hidden" value="' . $sTitulos . '"/>';
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' Consulta 3000: ' . $sSQL . '<br>';
	}
	$tabladetalle = $objDB->ejecutasql($sSQL);
	if ($tabladetalle == false) {
		$registros = 0;
		$sErrConsulta = $sErrConsulta . '..<input id="err" name="err" type="hidden" value="' . $sSQL . ' ' . $objDB->serror . '"/>';
		//$sLeyenda=$sSQL;
	} else {
		$registros = $objDB->nf($tabladetalle);
		if ($registros == 0) {
			$sInfoAlUsuario = 'No hemos encontrado registros de matricula vigentes.';
			return array(cadena_codificar($sInfoAlUsuario . $sBotones), $sDebug);
		}
	}
	$res = $sErrConsulta . $sLeyenda . $sBotones . '<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">';
	$tlinea = 1;
	$idPrograma = -99;
	$idPeriodo = -99;
	$bPuedeAplazar = false;
	$bPuedeAplazarTardio = false;
	$bPuedeCancelar = false;
	while ($filadet = $objDB->sf($tabladetalle)) {
		if ($idPrograma != $filadet['core16idprograma']) {
			$idPrograma = $filadet['core16idprograma'];
			$sNomPrograma = $filadet['core09codigo'] . ' ' . cadena_notildes($filadet['core09nombre']) . '';
			$res = $res . '<tr class="fondoazul">
			<td colspan="7">' . $ETI['msg_programa'] . ': <b>' . $sNomPrograma . '</b></td>
			</tr>';
		}
		if ($idPeriodo != $filadet['core16peraca']) {
			$bConInfoNoAplaza = false;
			$sComplementoAplaza = '';
			if ($idPeriodo > 765) {
				//Los acompañamientos de ese periodo...
				list($sAcomp, $sDebugF) = f3000_FilaAcompana($saiu18idsolicitante, $idContenedor, $idPeriodo, 0, $objDB, $bDebug);
				$res = $res . $sAcomp;
			}
			$idPeriodo = $filadet['core16peraca'];
			$sNomPeriodo = cadena_notildes($filadet['exte02nombre']) . ' ';
			$bPuedeAplazar = false;
			$bPuedeCancelar = false;
			$sBotonAplaza = '<td></td>';
			$sBotonCancela = '<td></td>';
			$iFechaTopeCancela = $filadet['exte02oferfechatopecancela'];
			$sInfoAdicionalPeriodo = '';
			if ($filadet['exte02fechatopetablero'] >= $iHoy) {
				//Ver si tiene una excepcion podria cambiar la fecha tope de cancelaciones.
				$sSQL = 'SELECT corf39nuevafecha FROM corf39prorrogas WHERE corf39idtercero=' . $saiu18idsolicitante . ' AND corf39periodo=' . $idPeriodo . ' AND corf39estado=7 AND corf39nuevafecha>=' . $iHoy . '';
				if ($bDebug) {
					$sDebug = $sDebug . fecha_microtiempo() . ' Verifica excepcion para cambiar la fecha: ' . $sSQL . '<br>';
				}
				$tabla39 = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($tabla39) > 0) {
					$fila39 = $objDB->sf($tabla39);
					$iFechaTopeCancela = $fila39['corf39nuevafecha'];
					$sInfoAdicionalPeriodo = 'Se ha concedido la oportunidad de hacer aplazamientos extemporaneo sin requerir autorizaci&oacute;n por parte de la escuela hasta el d&iacute;a ' . formato_FechaLargaDesdeNumero($iFechaTopeCancela, true) . '.';
				}
				$iTotalCursos = $filadet['core16numcursos'] + $filadet['cara16numcursosext'] + $filadet['cara16numcursospost'];
				if ($iFechaTopeCancela >= $iHoy) {
					if ($iTotalCursos > 0) {
						if ($filadet['exte02tipoperiodo'] == 0) {
							$bPuedeAplazar = true;
							$bPuedeCancelar = true;
							$sBotonAplaza = '<td><input id="CmdAplazarTodo" name="CmdAplazarTodo" type="button" class="BotonAzul" value="Aplazar" onclick="aplazasem(' . $idPeriodo . ')" title="Aplazar periodo"/></td>';
							$sBotonCancela = '<td><input id="CmdCancelaTodo" name="CmdCancelaTodo" type="button" class="BotonAzul" value="Cancelar" onclick="cancelasem(' . $idPeriodo . ')" title="Cancelar periodo"/></td>';
						} else {
							if ($filadet['exte02fechatopeaplaza'] >= $iHoy) {
								$bPuedeAplazarTardio = true;
							} else {
								$bPuedeAplazarTardio = true;
								$bConInfoNoAplaza = true;
								//$sComplementoAplaza='<br>Recuerde que: <b>el evento que genera la solicitud debi&oacute; de haber sucedido dentro de los primeros 10 d&iacute;as h&aacute;biles del periodo acad&eacute;mico</b>';
							}
						}
					}
				} else {
					if ($iFechaTopeCancela > 0) {
						$sBotonAplaza = '';
						$sBotonCancela = '<td colspan="2">' . $ETI['msg_fechanovedades'] . ' <b>' . formato_FechaLargaDesdeNumero($iFechaTopeCancela) . '</b></td>';
					}
					if ($filadet['exte02fechatopeaplaza'] >= $iHoy) {
						if ($iTotalCursos > 0) {
							$bPuedeAplazarTardio = true;
						}
					} else {
						if ($iTotalCursos > 0) {
							$bPuedeAplazarTardio = true;
							$bConInfoNoAplaza = true;
						}
					}
					//Acciones con el debug para rehacer la solicitud.
					if ($bPuedeRehacer) {
						if ($iTotalCursos > 0) {
							if ($filadet['exte02tipoperiodo'] == 0) {
								//Ver si tiene una solicitud aprobada.
								$sSQL = 'SELECT corf06id FROM corf06novedad WHERE corf06tiponov=1 AND corf06estado=7 AND corf06idestudiante=' . $saiu18idsolicitante . ' AND corf06idperiodo=' . $idPeriodo . '';
								if ($bDebug) {
									$sDebug = $sDebug . fecha_microtiempo() . ' Solicitudes aprobadas: ' . $sSQL . '<br>';
								}
								$tabla7 = $objDB->ejecutasql($sSQL);
								if ($objDB->nf($tabla7) > 0) {
									$sBotonAplaza = '<td><input id="CmdAplazarTodo" name="CmdAplazarTodo" type="button" class="BotonAzul" value="Aplazar" onclick="aplazasem(' . $idPeriodo . ')" title="Aplazar periodo"/></td>';
								}
							}
						}
					}
					// Termina
				}
			} else {
				$sBotonAplaza = '';
				$sBotonCancela = '<td colspan="2">Periodo no disponible</td>';
			}
			if ($bPuedeAplazarTardio) {
				if ($filadet['exte02tipoperiodo'] == 0) {
					$sComplementoAplaza = '<br>Recuerde que: <b>el evento que genera la solicitud debi&oacute; de haber sucedido dentro de las primeras 8 semanas del periodo acad&eacute;mico</b>';
				}
			}
			$res = $res . '<tr class="fondoazul">
			<td colspan="5">' . $ETI['msg_periodo'] . ': <b>' . $sNomPeriodo . '</b></td>
			' . $sBotonAplaza . '
			' . $sBotonCancela . '
			</tr>';
			if ($bConInfoNoAplaza) {
				$res = $res . '<tr class="fondoazul">
				<td colspan="7" align="center">' . $AYU['msg_aplazatiempos'] . $sComplementoAplaza . '</td>
				</tr>';
			}
			if ($sInfoAdicionalPeriodo != '') {
				$res = $res . '<tr>
				<td colspan="7" align="center">' . $sInfoAdicionalPeriodo . '</td>
				</tr>';
			}
		}
		if ($filadet['exte02fechatopetablero'] >= $iHoy) {
			//Ahora por cada matricula mostrar los cursos
			$sSQL = 'SELECT TB.core04id, TB.core04idcurso, T40.unad40titulo, T40.unad40nombre, TB.core04estado, T40.unad40numcreditos 
			FROM ' . $sTabla04 . ' AS TB, unad40curso AS T40 
			WHERE TB.core04tercero=' . $saiu18idsolicitante . ' AND TB.core04idmatricula=' . $filadet['core16id'] . ' AND TB.core04estado<>1 
			AND TB.core04idcurso=T40.unad40id
			ORDER BY TB.core04estado, T40.unad40titulo';
			//$res=$res.'<tr><td colspan="4">'.$sSQL.'</td></tr>';
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . ' Mostrar cursos por matricula: ' . $sSQL . '<br>';
			}
			$tabla = $objDB->ejecutasql($sSQL);
			while ($fila = $objDB->sf($tabla)) {
				$sPrefijo = '';
				$sSufijo = '';
				$sClass = ' class="resaltetabla"';
				switch ($fila['core04estado']) {
					case 9: //Cancelado
					case 10: //Aplazado
						$sPrefijo = '<span class="rojo">';
						$sSufijo = '</span>';
						break;
				}
				if (($tlinea % 2) != 0) {
					$sClass = '';
				}
				$tlinea++;
				$id04 = $fila['core04id'];
				$idCurso = $fila['core04idcurso'];
				$et_curso = $sPrefijo . $fila['unad40titulo'] . $sSufijo;
				$et_nomCurso = $sPrefijo . cadena_notildes($fila['unad40nombre']) . $sSufijo;
				$et_Creditos = $sPrefijo . $fila['unad40numcreditos'] . $sSufijo;
				$et_Estado = $sPrefijo . $aEstado[$fila['core04estado']] . $sSufijo;
				$sBotonCambia = '';
				$sBotonAplaza = '';
				$sBotonCancela = '';
				switch ($fila['core04estado']) {
					case 0: //Matriculado
					case 2: //Curso Externo
					case 5: //Reportado 75 %
						if ($bPuedeAplazar) {
							//$sBotonCambia='<a href="javascript:cambiacurso('.$idPeriodo.', '.$idCurso.')" class="lnkresalte">'.'Cambiar curso'.'</a>';
							$sBotonAplaza = '<a href="javascript:aplaza(' . $idPeriodo . ', ' . $idCurso . ')" class="lnkresalte">' . 'Aplazar curso' . '</a>';
							//$sBotonAplaza='<input id="CmdAplazar" name="CmdAplazar" type="button" class="BotonAzul" value="Aplazar" onclick="aplaza('.$id04.')" title="Aplazar curso"/>';
						}
						if ($bPuedeCancelar) {
							$sBotonCancela = '<a href="javascript:cancela(' . $idPeriodo . ', ' . $idCurso . ')" class="lnkresalte">' . 'Cancelar curso' . '</a>';
							//$sBotonCancela='<input id="CmdCancela" name="CmdCancela" type="button" class="BotonAzul" value="Cancelar" onclick="cancela('.$id04.')" title="Cancelar curso"/>';
						}
						if ($bPuedeAplazarTardio) {
							//Verificar que no este incluido en una solicitud tardia
							$bEntra = true;
							$sSQL = 'SELECT TB.corf06estado, TB.corf06fecha 
							FROM corf06novedad AS TB, corf07novedadcurso AS T7 
							WHERE TB.corf06tiponov=7 AND TB.corf06idestudiante=' . $saiu18idsolicitante . ' AND TB.corf06idperiodo=' . $idPeriodo . ' 
							AND TB.corf06estado NOT IN (0,2,8) AND TB.corf06id=T7.corf07idnovedad AND T7.corf07idcurso=' . $idCurso . '';
							//if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Revisando si aplica '.$sSQL.'<br>';}
							if ($bDebug) {
								$sDebug = $sDebug . fecha_microtiempo() . ' Verifica solicitud tardia: ' . $sSQL . '<br>';
							}
							$tabla7 = $objDB->ejecutasql($sSQL);
							if ($objDB->nf($tabla7) > 0) {
								$bEntra = false;
								$fila7 = $objDB->sf($tabla7);
								$sBotonAplaza = 'Estado de la solicitud de fecha ' . fecha_desdenumero($fila7['corf06fecha']) . ' : <b>' . $acorf06estado[$fila7['corf06estado']] . '<b>';
							}
							if ($bEntra) {
								$sBotonAplaza = '<a href="javascript:aplazatardio(' . $idPeriodo . ', ' . $idCurso . ')" class="lnkresalte">' . 'Aplazar curso' . '</a>';
							}
						}
						break;
				}
				//$et_saiu23idtabla=$sPrefijo.$filadet['core04idcurso'].$sSufijo;
				$res = $res . '<tr' . $sClass . '>
				<td>' . $et_curso . '</td>
				<td>' . $et_nomCurso . '</td>
				<td>' . $et_Estado . '</td>
				<td>' . $et_Creditos . '</td>
				<td>' . $sBotonCambia . '</td>
				<td>' . $sBotonAplaza . '</td>
				<td>' . $sBotonCancela . '</td>
				</tr>';
			}
		}
	}
	//Los acompañamientos del ultimo 
	if ($idPeriodo > 765) {
		//Los acompañamientos de ese periodo...
		list($sAcomp, $sDebugF) = f3000_FilaAcompana($saiu18idsolicitante, $idContenedor, $idPeriodo, 0, $objDB, $bDebug);
		$res = $res . $sAcomp;
	}
	//Ahora mostrar los acompañamientos que no esten asociados a un periodo.
	list($sAcomp, $sDebugF) = f3000_FilaAcompana($saiu18idsolicitante, $idContenedor, 0, 0, $objDB, $bDebug);
	$res = $res . $sAcomp;
	$res = $res . '</table>';
	$objDB->liberar($tabladetalle);
	return array(cadena_codificar($res), $sDebug);
}
function f3000_FilaAcompana($idTercero, $idContTercero, $idPeriodo, $idCurso, $objDB, $bDebug = false)
{
	$sRes = '';
	$sDebug = '';
	$sTabla41 = 'saiu41docente_' . $idContTercero;
	$sSQL = 'SELECT TB.saiu41fecha, TB.saiu41cerrada, TB.saiu41idactividad, TB.saiu41idtutor, TB.saiu41contacto_observa, T11.unad11razonsocial 
	FROM ' . $sTabla41 . ' AS TB, unad11terceros AS T11 
	WHERE TB.saiu41idestudiante=' . $idTercero . ' AND TB.saiu41idperiodo=' . $idPeriodo . ' AND TB.saiu41idcurso=' . $idCurso . ' AND TB.saiu41visiblealest=1
	AND TB.saiu41idtutor=T11.unad11id 
	ORDER BY TB.saiu41fecha DESC';
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' Acompanamientos: ' . $sSQL . '<br>';
	}
	$tabla = $objDB->ejecutasql($sSQL);
	while ($fila = $objDB->sf($tabla)) {
		$sRes = $sRes . '<tr>
		<td></td>
		<td colspan="6">' . fecha_desdenumero($fila['saiu41fecha']) . ' ' . cadena_notildes($fila['unad11razonsocial']) . ': <b>' . cadena_notildes($fila['saiu41contacto_observa']) . '</b></td>
		</tr>';
	}
	return array($sRes, $sDebug);
}
function f3000_InfoPeriodo($aParametros)
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
	if (isset($aParametros[1]) == 0) {
		$aParametros[1] = '';
	}
	if (isset($aParametros[2]) == 0) {
		$aParametros[2] = 0;
	}
	$idPeriodo = numeros_validar($aParametros[1]);
	$idEntrada = numeros_validar($aParametros[2]);
	$sDetalle = 'Periodo {' . $idPeriodo . '}';
	$sSQL = 'SELECT exte02titulo FROM exte02per_aca WHERE exte02id=' . $idPeriodo . '';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$sDetalle = 'Periodo <b>' . cadena_notildes($fila['exte02titulo']) . '</b>';
	}
	//Info periodo
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	switch ($idEntrada) {
		case 2: //Cancelacion
			$objResponse->assign('div_f3000periodoCancela', 'innerHTML', $sDetalle);
			break;
		default:
			$objResponse->assign('div_f3000periodo', 'innerHTML', $sDetalle);
			break;
	}
	if ($bDebug) {
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
	}
	return $objResponse;
}
//Db para desistir
function f3000_db_InfoDesistir($id06, $objDB, $bDebug = false)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_12207 = $APP->rutacomun . 'lg/lg_12207_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_12207)) {
		$mensajes_12207 = $APP->rutacomun . 'lg/lg_12207_es.php';
	}
	require $mensajes_todas;
	require $mensajes_12207;
	$res = '';
	$sDebug = '';
	$iSolicitados = 0;
	$sSQL = 'SELECT TB.corf07id, TB.corf07idcurso, T40.unad40titulo, T40.unad40nombre, TB.corf07tipo, TB.corf07paradesistir 
	FROM corf07novedadcurso AS TB, unad40curso AS T40 
	WHERE TB.corf07idnovedad=' . $id06 . ' AND TB.corf07idcurso=T40.unad40id 
	ORDER BY T40.unad40titulo';
	$tabladetalle = $objDB->ejecutasql($sSQL);
	$res = '<div class="table-responsive">
	<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">
	<thead class="fondoazul"><tr>
	<td colspan="2"><b>' . $ETI['corf07idcurso'] . '</b></td>
	<td colspan="3"></td>
	</tr></thead>';
	$tlinea = 1;
	while ($filadet = $objDB->sf($tabladetalle)) {
		$sPrefijo = '';
		$sSufijo = '';
		if ($filadet['corf07paradesistir'] == 1) {
			$sPrefijo = '<b>';
			$sSufijo = '</b>';
		}
		$sClass = ' class="resaltetabla"';
		if (($tlinea % 2) != 0) {
			$sClass = '';
		}
		$tlinea++;
		$sLink = '';
		$sLink2 = '';
		$et_corf07idcurso = $sPrefijo . cadena_notildes($filadet['unad40nombre']) . $sSufijo;
		$et_corf07tipo = $sPrefijo . $acorf07tipo[$filadet['corf07tipo']] . $sSufijo;
		if ($filadet['corf07tipo'] == 2) {
			if ($filadet['corf07paradesistir'] == 0) {
				$sLink = '<a href="javascript:desistir12207(1, ' . $filadet['corf07id'] . ')" class="lnkresalte">Desistir</a>';
			} else {
				$iSolicitados = 1;
				$sLink2 = '<a href="javascript:desistir12207(0, ' . $filadet['corf07id'] . ')" class="lnkresalte">No desistir</a>';
			}
		}
		$res = $res . '<tr' . $sClass . '>
		<td>' . $sPrefijo . $filadet['unad40titulo'] . $sSufijo . '</td>
		<td>' . $et_corf07idcurso . '</td>
		<td>' . $et_corf07tipo . '</td>
		<td>' . $sLink . '</td>
		<td>' . $sLink2 . '</td>
		</tr>';
	}
	$res = $res . '</table>
	<div class="salto5px"></div>
	</div>';
	return array($res, $iSolicitados, $sDebug);
}
//DB para info periodo
function f3000_db_InfoPeriodoCurso($idPeriodo, $idCurso, $idEntrada, $idEstudiante, $objDB, $bDebug = false)
{
	$sDetalle = 'Periodo {' . $idPeriodo . '}';
	$sDetalle2 = 'Curso {' . $idCurso . '}';
	$sHistorial = '';
	$sNotaNovedad = '';
	$id06 = '';
	$id08 = '';
	$sSQL = 'SELECT exte02titulo FROM exte02per_aca WHERE exte02id=' . $idPeriodo . '';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$sDetalle = 'Periodo <b>' . cadena_notildes($fila['exte02titulo']) . '</b>';
	}
	$sSQL = 'SELECT unad40titulo, unad40nombre FROM unad40curso WHERE unad40id=' . $idCurso . '';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$sDetalle2 = 'Curso <b>' . $fila['unad40titulo'] . ' ' . cadena_notildes($fila['unad40nombre']) . '</b>';
	}
	$sDetalle = $sDetalle . '<br>' . $sDetalle2;
	//Ahora ver si ya tiene una solicitud  y el estado...
	if ($idEntrada == 7) {
		require './app.php';
		$mensajes_12206 = $APP->rutacomun . 'lg/lg_12206_' . $_SESSION['unad_idioma'] . '.php';
		if (!file_exists($mensajes_12206)) {
			$mensajes_12206 = $APP->rutacomun . 'lg/lg_12206_es.php';
		}
		require $mensajes_12206;
		$sSQL = 'SELECT TB.corf06estado, TB.corf06id, TB.corf06fecha, TB.corf06hora, TB.corf06min
		FROM corf06novedad AS TB 
		WHERE TB.corf06idestudiante=' . $idEstudiante . ' AND TB.corf06tiponov=7 AND TB.corf06idperiodo=' . $idPeriodo . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$sHistorial = '<b>Solicitudes de este periodo</b>';
		}
		while ($fila = $objDB->sf($tabla)) {
			//if ($sHistorial!=''){$sHistorial=$sHistorial.html_salto();}
			$sPrefEstado = '<b>';
			$sSufEstado = '</b>';
			switch ($fila['corf06estado']) {
				case 0:
				case 2:
					$sPrefEstado = '<span class="rojo">';
					$sSufEstado = '</span>';
					break;
			}
			$sHistorial = $sHistorial . html_salto() . fecha_desdenumero($fila['corf06fecha']) . ' ' . html_TablaHoraMin($fila['corf06hora'], $fila['corf06min']) . ' Estado: ' . $sPrefEstado . $acorf06estado[$fila['corf06estado']] . $sSufEstado;
			$bTraeNota = false;
			switch ($fila['corf06estado']) {
				case 0: // Borrador
				case 1: // Solicitada
				case 2: // Devuelta
					$id06 = $fila['corf06id'];
					$bTraeNota = true;
					break;
			}
			//Agregarle los cursos solicitados.
			$sSQL = 'SELECT T2.unad40titulo, T2.unad40nombre, TB.corf07tipo, TB.corf07idcurso 
			FROM corf07novedadcurso AS TB, unad40curso AS T2 
			WHERE TB.corf07idnovedad=' . $id06 . ' AND TB.corf07idcurso=T2.unad40id 
			ORDER BY T2.unad40titulo';
			$tabla7 = $objDB->ejecutasql($sSQL);
			while ($fila7 = $objDB->sf($tabla7)) {
				$sHistorial = $sHistorial . html_salto() . '<b>' . $fila7['unad40titulo'] . '</b> ' . cadena_notildes($fila7['unad40nombre']) . '';
			}
			if ($bTraeNota) {
				$sSQL = 'SELECT corf08id, corf08nota, corf08idorigenanexo, corf08idarchivoanexo 
				FROM corf08novedadnota 
				WHERE corf08idnovedad=' . $id06 . ' AND corf08idusuario=' . $idEstudiante . ' AND corf08cerrada=0';
				//$sNotaNovedad=$sSQL;
				$tabla8 = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($tabla8) == 0) {
					//Insertar la nota.
					$corf08fecha = fecha_DiaMod();
					$corf08hora = fecha_hora();
					$corf08min = fecha_minuto();
					$corf08idusuario = $idEstudiante;
					$corf08consec = tabla_consecutivo('corf08novedadnota', 'corf08consec', 'corf08idnovedad=' . $id06 . '', $objDB);
					$corf08id = tabla_consecutivo('corf08novedadnota', 'corf08id', '', $objDB);
					$sCampos12208 = 'corf08idnovedad, corf08consec, corf08id, corf08fecha, corf08hora, 
					corf08min, corf08nota, corf08idorigenanexo, corf08idarchivoanexo, corf08idusuario';
					$sValores12208 = '' . $id06 . ', ' . $corf08consec . ', ' . $corf08id . ', "' . $corf08fecha . '", ' . $corf08hora . ', 
					' . $corf08min . ', "", 0, 0, ' . $corf08idusuario . '';
					$sSQL12208 = 'INSERT INTO corf08novedadnota (' . $sCampos12208 . ') VALUES (' . $sValores12208 . ')';
					$result = $objDB->ejecutasql($sSQL12208);
					$tabla8 = $objDB->ejecutasql($sSQL);
				}
				if ($objDB->nf($tabla8) > 0) {
					$fila8 = $objDB->sf($tabla8);
					$id08 = $fila8['corf08id'];
					$sNotaNovedad = $fila8['corf08nota'];
					if ($fila['corf06estado'] == 2) {
						$sHistorial = $sHistorial . html_salto() . '<label><b>Su proceso esta devuelto, debe volver a cargar evidencias</b></label>';
					} else {
						$sHistorial = $sHistorial . html_salto() . '<label><b>Este proceso requiere evidencias</b></label>';
					}
					if ($fila8['corf08idarchivoanexo'] != 0) {
						$sHistorial = $sHistorial . '<div id="div_corf08idarchivoanexo" class="Campo220">' . html_lnkarchivo($fila8['corf08idorigenanexo'], $fila8['corf08idarchivoanexo']) . '</div>';
					} else {
						$sHistorial = $sHistorial . '<label><span class="rojo">No se han cargado evidencias</span></label>';
					}
					$sHistorial = $sHistorial . '<label class="Label130">
					<input type="button" id="banexacorf08idarchivoanexo" name="banexacorf08idarchivoanexo" value="Anexar" class="BotonAzul" onclick="carga_corf08idarchivoanexo()" title="Cargar evidencia"/>
					</label>';
				}
			}
		}
		if ($sHistorial != '') {
			if ((int)$id06 != 0) {
				$sHistorial = $sHistorial . html_salto() . '<span class="naranja">' . 'Al solicitar aplazar mas cursos, estos ser&aacute;n agregados a la solicitud actual.' . '</span>';
			}
		}
	}
	return array($sDetalle, $sHistorial, $sNotaNovedad, $id06, $id08);
}
function f3000_InfoDesistir($aParametros)
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
	if (isset($aParametros[1]) == 0) {
		$aParametros[1] = '';
	}
	$id06 = numeros_validar($aParametros[1]);
	list($sDetalle, $iSolicitados, $sDebugP) = f3000_db_InfoDesistir($id06, $objDB, $bDebug);
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_f3000curso8', 'innerHTML', $sDetalle);
	$objResponse->assign('marca07', 'value', $iSolicitados);
	if ($bDebug) {
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
	}
	return $objResponse;
}
function f3000_InfoPeriodoCurso($aParametros)
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
	if (isset($aParametros[1]) == 0) {
		$aParametros[1] = '';
	}
	if (isset($aParametros[2]) == 0) {
		$aParametros[2] = '';
	}
	if (isset($aParametros[3]) == 0) {
		$aParametros[3] = 0;
	}
	if (isset($aParametros[4]) == 0) {
		$aParametros[4] = 0;
	}
	$idPeriodo = numeros_validar($aParametros[1]);
	$idCurso = numeros_validar($aParametros[2]);
	$idEntrada = numeros_validar($aParametros[3]);
	$idEstudiante = numeros_validar($aParametros[4]);
	list($sDetalle, $sHistorial, $sNotaNovedad, $id06, $id08) = f3000_db_InfoPeriodoCurso($idPeriodo, $idCurso, $idEntrada, $idEstudiante, $objDB, $bDebug);
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	switch ($idEntrada) {
		case 2: //Cancelacion
			$objResponse->assign('div_f3000CursoCancela', 'innerHTML', $sDetalle);
			break;
		case 7: //Aplazamiento tardio. 
			$objResponse->assign('div_f3000curso7', 'innerHTML', $sDetalle);
			$objResponse->assign('div_f3000cursoexistentes', 'innerHTML', $sHistorial);
			$objResponse->assign('corf08nota', 'value', $sNotaNovedad);
			$objResponse->assign('corf06id', 'value', $id06);
			$objResponse->assign('corf08id', 'value', $id08);
			break;
		default:
			$objResponse->assign('div_f3000curso', 'innerHTML', $sDetalle);
			break;
	}
	if ($bDebug) {
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
	}
	return $objResponse;
}
//Marcar el registro de que desiste
function f3000_MarcaDesistir($aParametros)
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
	if (isset($aParametros[1]) == 0) {
		$aParametros[1] = '';
	}
	$id06 = numeros_validar($aParametros[1]);
	$id07 = numeros_validar($aParametros[2]);
	$iEstado = numeros_validar($aParametros[3]);
	$sSQL = 'UPDATE corf07novedadcurso SET corf07paradesistir=' . $iEstado . ' WHERE corf07id=' . $id07 . '';
	$result = $objDB->ejecutasql($sSQL);
	list($sDetalle, $iSolicitados, $sDebugP) = f3000_db_InfoDesistir($id06, $objDB, $bDebug);
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_f3000curso8', 'innerHTML', $sDetalle);
	$objResponse->assign('marca07', 'value', $iSolicitados);
	if ($bDebug) {
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
	}
	return $objResponse;
}
function f3000pqrs_HtmlTabla($aParametros)
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
	list($sDetalle, $sDebugTabla) = f3000pqrs_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_f3000pqrsdetalle', 'innerHTML', $sDetalle);
	if ($bDebug) {
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
	}
	return $objResponse;
}
function f3000_HistoricosSAU($idTercero, $objDB, $bDebug = false)
{
	$res = '';
	$sSQL = 'SELECT unad11doc FROM unad11terceros WHERE unad11id="' . $idTercero . '"';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$sDoc = $fila['unad11doc'];
		$sLlave = "f79a3e797d3d944d847addf158a34548"; //valor fijo y privado en ambos servidores
		$sToken = md5($sDoc . "||" . $sLlave);
		$sUrlDestino = 'https://sau.unad.edu.co/app_pqrs/admin/service/reporte_usuario.php?sai&doc=' . $sDoc . '&token=' . $sToken . '';
		$res = $res . '<div class="salto1px"></div>
		<iframe height="300" width="100%" scrolling="auto" src="' . $sUrlDestino . '"></iframe>
		';
	}
	return $res;
}
function f3000pqrs_TablaDetalleV2($aParametros, $objDB, $bDebug = false)
{
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_3000 = $APP->rutacomun . 'lg/lg_3000_' . $sIdioma . '.php';
	if (!file_exists($mensajes_3000)) {
		$mensajes_3000 = $APP->rutacomun . 'lg/lg_3000_es.php';
	}
	$mensajes_3005 = $APP->rutacomun . 'lg/lg_3005_' . $sIdioma . '.php';
	if (!file_exists($mensajes_3005)) {
		$mensajes_3000 = $APP->rutacomun . 'lg/lg_3005_es.php';
	}
	require $mensajes_todas;
	require $mensajes_3000;
	require $mensajes_3005;
	if (!is_array($aParametros)) {
		$aParametros = json_decode(str_replace('\"', '"', $aParametros), true);
	}
	if (isset($aParametros[0]) == 0) {
		$aParametros[0] = -1;
	}
	if (isset($aParametros[100]) == 0) {
		$aParametros[100] = 0;
	}
	if (isset($aParametros[101]) == 0) {
		$aParametros[101] = 1;
	}
	if (isset($aParametros[102]) == 0) {
		$aParametros[102] = 20;
	}
	$iNumVariables = 105;
	for ($k = 103; $k <= $iNumVariables; $k++) {
		if (isset($aParametros[$k]) == 0) {
			$aParametros[$k] = '';
		}
	}
	if ($aParametros[0] == '') {
		$aParametros[0] = -1;
	}
	$idTercero = numeros_validar($aParametros[0]);
	$sDebug = '';
	// ------------------------------------------------
	// Leemos los parametros de entrada.
	// ------------------------------------------------
	$idsolicitante = numeros_validar($aParametros[100]);
	$pagina = numeros_validar($aParametros[101]);
	$lineastabla = numeros_validar($aParametros[102]);
	$iAgnopqrs = numeros_validar($aParametros[103]);
	$bAbierta = true;
	$sLeyenda = '';
	$sBotones = '<input id="paginaf3000pqrs" name="paginaf3000pqrs" type="hidden" value="' . $pagina . '"/>';
	$sBotones = $sBotones . '<input id="lppf3000pqrs" name="lppf3000pqrs" type="hidden" value="' . $lineastabla . '"/>';
	if ((int)$idsolicitante == 0) {
		$sLeyenda = 'No se ha ingresado un documento v&aacute;lido a consultar';
	}
	if ($iAgnopqrs == '') {
		$sLeyenda = 'No ha seleccionado un a&ntilde;o a consultar';
	}
	if ($sLeyenda != '') {
		$sRes = html_salto() . '<div class="GrupoCamposAyuda">' . $sLeyenda . html_salto() . '</div>';
		return array($sRes . $sBotones, $sDebug);
		die();
	}
	$iPiel = iDefinirPiel($APP, 2);
	$aTemas = array();
	$sSQL = 'SELECT saiu03id, saiu03titulo FROM saiu03temasol WHERE saiu03activo="S"';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		while ($fila = $objDB->sf($tabla)) {
			$aTemas[$fila['saiu03id']] = cadena_notildes($fila['saiu03titulo']);
		}
	}
	$sSQLadd = '';
	$sSQLadd1 = '';
	$sSQLadd1 = $sSQLadd1 . ' AND TB.saiu05estado IN (0,2,7) '; // 0: Solicitado, 2: En Trámite, 7: Resuelto
	if ($iAgnopqrs != '') {
		$sSQLadd = $sSQLadd . ' AND saiu15agno=' . $iAgnopqrs . '';
	}
	if ($idsolicitante != '') {
		$sSQLadd = $sSQLadd . ' AND saiu15idinteresado=' . $idsolicitante . '';
		$sSQLadd1 = $sSQLadd1 . ' AND TB.saiu05idsolicitante=' . $idsolicitante . '';
	}
	// ------------------------------------------------
	// Fin de las condiciones de la consulta
	// ------------------------------------------------
	$aTablas = array();
	$iTablas = 0;
	$iNumSolicitudes = 0;
	$sSQL = 'SELECT saiu15agno, saiu15mes, SUM(saiu15numsolicitudes) AS Solicitudes 
	FROM saiu15historico 
	WHERE saiu15tiporadicado=1' . $sSQLadd . '
	GROUP BY saiu15agno, saiu15mes';
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' Historico: ' . $sSQL . '<br>';
	}
	$tabla15 = $objDB->ejecutasql($sSQL);
	while ($fila15 = $objDB->sf($tabla15)) {
		$iNumSolicitudes = $iNumSolicitudes + $fila15['Solicitudes'];
		if ($fila15['saiu15mes'] < 10) {
			$sContenedor = $fila15['saiu15agno'] . '0' . $fila15['saiu15mes'];
		} else {
			$sContenedor = $fila15['saiu15agno'] . $fila15['saiu15mes'];
		}
		$iTablas++;
		$aTablas[$iTablas] = $sContenedor;
	}
	$sTitulos = 'Agno, Mes, Dia, Consecutivo, Estado, Hora, Minuto';
	$registros = $iNumSolicitudes;
	$sLimite = '';
	$sErrConsulta = '';
	
	$sSQL = '';
	for ($k = 1; $k <= $iTablas; $k++) {
		if ($k != 1) {
			$sSQL = $sSQL . ' UNION ';
		}
		$sContenedor = $aTablas[$k];
		$sSQL = $sSQL . 'SELECT TB.saiu05agno, TB.saiu05mes, TB.saiu05dia, TB.saiu05consec, T12.saiu11nombre, TB.saiu05hora, 
		TB.saiu05minuto, TB.saiu05id, TB.saiu05estado, T11.unad11tipodoc, T11.unad11doc, 
		T11.unad11razonsocial, T13.saiu68nombre, TB.saiu05idtemaorigen, TB.saiu05numref
		FROM saiu05solicitud_' . $sContenedor . ' AS TB, saiu11estadosol AS T12, unad11terceros AS T11, saiu68categoria AS T13 
		WHERE TB.saiu05tiporadicado=1 AND TB.saiu05estado=T12.saiu11id AND TB.saiu05idsolicitante=T11.unad11id AND TB.saiu05idcategoria=T13.saiu68id ' . $sSQLadd1 . '';
	}
	if ($sSQL != '') {
		$sSQL = $sSQL . ' ORDER BY saiu05agno DESC, saiu05mes DESC, saiu05consec DESC' . $sLimite;
		$sSQLlista = str_replace("'", "|", $sSQL);
		$sSQLlista = str_replace('"', "|", $sSQLlista);
		$sErrConsulta = '<input id="consulta_3005" name="consulta_3005" type="hidden" value="' . $sSQLlista . '"/>';
		$sErrConsulta = $sErrConsulta . '<input id="titulos_3005" name="titulos_3005" type="hidden" value="' . $sTitulos . '"/>';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' Consulta 3005: ' . $sSQL . '';
		}
		$tabladetalle = $objDB->ejecutasql($sSQL);
		if ($tabladetalle == false) {
			$registros = 0;
			$sErrConsulta = $sErrConsulta . '..<input id="err" name="err" type="hidden" value="' . $sSQL . ' ' . $objDB->serror . '"/>';
			//$sLeyenda=$sSQL;
		} else {
			$registros = $objDB->nf($tabladetalle);
			if ($registros == 0) {
				$sLeyenda = $sLeyenda . 'No existen registros PQRS durante el a&ntilde;o ' . $iAgnopqrs . '.';
				$sErrConsulta = $sErrConsulta . html_salto() . '<div class="GrupoCamposAyuda">' . $sLeyenda . html_salto() . '</div>';
				$sErrConsulta = $sErrConsulta . f3000_HistoricosSAU($idsolicitante, $objDB, $bDebug);
				return array($sErrConsulta . $sBotones, $sDebug);
			}
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
	$sClaseTabla = 'table--primary';
	if ($iPiel == 1) {
		$sClaseTabla = 'tablaapp';
	}
	$res = $res . '<div class="table-responsive">';
	$res = $res . '<table border="0" align="center" cellpadding="0" cellspacing="2" class="' . $sClaseTabla . '">';
	$res = $res . '<thead class="fondoazul"><tr>';
	$res = $res . '<th><b>' . $ETI['saiu05numref'] . '</b></th>';
	$res = $res . '<th colspan="2"><b>' . $ETI['saiu05dia'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['saiu05idcategoria'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['saiu05idtemaorigen'] . '</b></th>';
	$res = $res . '<th><b>' . $ETI['saiu05estado'] . '</b></th>';
	$res = $res . '<th class="flex gap-1 justify-end">';
	$res = $res . html_paginador('paginaf3000pqrs', $registros, $lineastabla, $pagina, 'paginarf3000pqrs()');
	$res = $res . html_lpp('lppf3000pqrs', $lineastabla, 'paginarf3000pqrs()');
	$res = $res . '</th>';
	$res = $res . '</tr></thead><tbody>';
	if ($sSQL != '') {
		$tlinea = 1;
		while ($filadet = $objDB->sf($tabladetalle)) {
			$sPrefijo = '';
			$sSufijo = '';
			$sClass = ' class="resaltetabla"';
			$sLink = '';
			$sTema = '';
			if (false) {
				$sPrefijo = '<b>';
				$sSufijo = '</b>';
			}
			if (($tlinea % 2) == 0) {
				$sClass = '';
			}
			$tlinea++;
			$et_boton = $ETI['lnk_consultar'];
			$et_NumRef = $sPrefijo . $filadet['saiu05numref'] . $sSufijo;
			$et_saiu05dia = $sPrefijo . fecha_armar($filadet['saiu05dia'], $filadet['saiu05mes'], $filadet['saiu05agno']) . $sSufijo;
			$et_saiu05hora = $sPrefijo . html_TablaHoraMin($filadet['saiu05hora'], $filadet['saiu05minuto']) . $sSufijo;
			$et_saiu05idcategoria = $sPrefijo . cadena_notildes($filadet['saiu68nombre']) . $sSufijo;
			$et_saiu05estado = $sPrefijo . cadena_notildes($filadet['saiu11nombre']) . $sSufijo;
			if ($bAbierta) {
				$sURLSaiMod = 'https://aurea2.unad.edu.co/sai/saiusolcitudes.php';
				$sArgs = url_encode($filadet['saiu05agno'] . '|' . $filadet['saiu05mes'] . '|' . $filadet['saiu05id']);
				$sLink = '<a href="' . $sURLSaiMod . '?u=' . $sArgs . '" target="_blank" class="lnkresalte">' . $et_boton . '</a>';
			}
			if (isset($aTemas[$filadet['saiu05idtemaorigen']]) != 0) {
				$sTema = $sPrefijo . $aTemas[$filadet['saiu05idtemaorigen']] . $sSufijo;
			}
			$res = $res . '<tr' . $sClass . '>';
			$res = $res . '<td>' . $et_NumRef . '</td>';
			$res = $res . '<td>' . $et_saiu05dia . '</td>';
			$res = $res . '<td>' . $et_saiu05hora . '</td>';
			$res = $res . '<td>' . $et_saiu05idcategoria . '</td>';
			$res = $res . '<td>' . $sTema . '</td>';
			$res = $res . '<td>' . $et_saiu05estado . '</td>';
			$res = $res . '<td align="right">' . $sLink . '</td>';
			$res = $res . '</tr>';
		}
		$objDB->liberar($tabladetalle);
	}
	$res = $res . '</tbody></table>';
	$res = $res . '<div class="salto5px"></div>';
	$res = $res . '</div>';
	$objDB->liberar($tabla15);
	//Ahora los historicos.
	$res = $res . f3000_HistoricosSAU($idsolicitante, $objDB, $bDebug);
	//Fin de los historicos.
	return array(cadena_codificar($res), $sDebug);
}

/**
 * Busca encuesta del registro de atención
 * 
 * Esta función realiza la consulta del registro de atención 
 * (presencial, chat, telefónico o correos)
 * A partir de los campos de un código de atención
 * 
 * @param array $aParametros documento y código de atención
 * @return object Objeto ajax con el formulario html de encuesta o mensaje no encontrado.
 */
function f3000_BuscaCodigoEncuesta($aParametros)
{
	$_SESSION['u_ultimominuto'] = iminutoavance();
	if (!is_array($aParametros)) {
		$aParametros = json_decode(str_replace('\"', '"', $aParametros), true);
	}
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_3000 = $APP->rutacomun . 'lg/lg_3000_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_3000)) {
		$mensajes_3000 = $APP->rutacomun . 'lg/lg_3000_es.php';
	}
	require $mensajes_todas;
	require $mensajes_3000;
	$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto != '') {
		$objDB->dbPuerto = $APP->dbpuerto;
	}

	$sError = '';
	if (isset($aParametros[100]) == 0) {
		$aParametros[100] = '';
	}
	if (isset($aParametros[101]) == 0) {
		$aParametros[101] = '';
	}
	$sauiNNdoc = numeros_validar($aParametros[101]);
	$sauiNNnumref = trim(cadena_Validar($aParametros[101], true));
	$saiuNNid = 0;
	$sSepara = ', ';
	$html_encuesta = '';
	$sContenedor = '';
	$sTabla = '';
	if (true) {
		if ($sauiNNdoc == '') {
			$sError = $ERR['saiu00doc'] . $sSepara . $sError;
		}
		if ($sauiNNnumref == '') {
			$sError = $ERR['saiu00codigo'] . $sSepara . $sError;
		}
		//Fin de las valiaciones NO LLAVE.
	}

	// $objDB->xajax();
	if ($sError == '') {
		$aCodigo = explode('-', $sauiNNnumref);
		if (count($aCodigo) < 3) {
			$sError = $sError . $ERR['saui00numref'];
		} else {
			$sContenedor = $aCodigo[0];
			$saiuNNid = $aCodigo[1];
			$saiuid = $aCodigo[2];
			list($sTabla, $sErrorE) = f3000_ValidaTablasAtencion($sContenedor, $saiuid, $objDB);
			$sError = $sError . $sErrorE;
		}
	}
	if ($sError == '') {
		list($html_encuesta, $sErrorE) = f3000_HTMLForm_Encuesta($sTabla, $saiuid, $saiuNNid, $objDB, $saiuNNdoc);
		$sError = $sError . $sErrorE;
	}
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	if ($sError == '') {
		$objResponse->assign('div_saiu'.$saiuid.'formencuesta', 'innerHTML', $html_encuesta);
		$objResponse->assign('div_saiu'.$saiuid.'formencuesta', 'style.display', 'block');
		$objResponse->assign('div_saiu'.$saiuid.'formcodigo', 'style.display', 'none');
		$objResponse->script('$("select").addClass("form-control");');
	} else {
		$objResponse->call('muestramensajes("danger", "' . $sError . '")');
	}
	return $objResponse;
}
function f3000_HTMLForm_Encuesta($sTabla, $saiuid, $saiuNNid, $objDB, $saiuNNdoc='')
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_3000 = $APP->rutacomun . 'lg/lg_3000_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_3000)) {
		$mensajes_3000 = $APP->rutacomun . 'lg/lg_3000_es.php';
	}
	require $mensajes_todas;
	require $mensajes_3000;	
	$sSQLadd = '';
	$sHTML = '';
	$sError = '';
	if ($saiuNNdoc != '') {
		$sSQLadd = $sSQLadd . ' AND T5.unad11doc="' . $saiuNNdoc . '"';
	}
	$sSQL = 'SELECT TB.saiu'.$saiuid.'evalfecha, T5.unad11razonsocial, T1.core12nombre, 
	T2.core09nombre, T3.unad23nombre, T4.unad24nombre
	FROM ' . $sTabla . ' AS TB, core12escuela AS T1, core09programa AS T2, 
	unad23zona AS T3, unad24sede AS T4, unad11terceros AS T5
	WHERE TB.saiu'.$saiuid.'id=' . $saiuNNid . ' AND TB.saiu'.$saiuid.'idescuela=T1.core12id AND TB.saiu'.$saiuid.'idprograma=T2.core09id 
	AND TB.saiu'.$saiuid.'idzona=T3.unad23id AND TB.saiu'.$saiuid.'idcentro=T4.unad24id AND TB.saiu'.$saiuid.'idsolicitante=T5.unad11id' . $sSQLadd . '';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		if ($fila['saiu'.$saiuid.'evalfecha'] == 0) {
			$aPreguntas = array(
				['saiu00evalamabilidad', 'saiu00evalamabmotivo'], 
				['saiu00evalrapidez', 'saiu00evalrapidmotivo'], 
				['saiu00evalclaridad', 'saiu00evalcalridmotivo'],
				['saiu00evalresolvio', 'saiu00evalsugerencias'], 
				['saiu00evalconocimiento', 'saiu00evalconocmotivo'], 
				['saiu00evalutilidad', 'saiu00evalutilmotivo']
			);
			$aOpciones = array(
				['saiu'.$saiuid.'evalamabilidad', 'saiu'.$saiuid.'evalamabmotivo'], 
				['saiu'.$saiuid.'evalrapidez', 'saiu'.$saiuid.'evalrapidmotivo'], 
				['saiu'.$saiuid.'evalclaridad', 'saiu'.$saiuid.'evalcalridmotivo'],
				['saiu'.$saiuid.'evalresolvio', 'saiu'.$saiuid.'evalsugerencias'], 
				['saiu'.$saiuid.'evalconocimiento', 'saiu'.$saiuid.'evalconocmotivo'], 
				['saiu'.$saiuid.'evalutilidad', 'saiu'.$saiuid.'evalutilmotivo']
			);
			$sHTML = $sHTML . '<form id="frmencuesta" name="frmencuesta" method="post" action="" autocomplete="off">
			<input id="sContenedor" name="sContenedor" type="hidden" value="" />
			<input id="sTabla" name="sTabla" type="hidden" value="' . $sTabla . '" />
			<input id="saiuid" name="saiuid" type="hidden" value="' . $saiuid . '" />
			<input id="saiu'.$saiuid.'id" name="saiu'.$saiuid.'id" type="hidden" value="' . $saiuNNid . '" />
			<div class="form-group row">
				<div class="col-sm-6">
					<label>' . $ETI['texto_solicitante'] . '</label>
				</div>
				<div class="col-sm-6">
					<label>' . $ETI['saiu00razonsocial'] . '</label>
					<p class="font-weight-bold">' . $fila['unad11razonsocial'] . '</p>
				</div>
			</div>

			<div class="form-group row">
				<div class="col-sm-6">
					<label>' . $ETI['saiu00idzona'] . '</label>
					<p class="font-weight-bold">' . $fila['unad23nombre'] . '</p>
				</div>
				<div class="col-sm-6">
					<label>' . $ETI['saiu00idcentro'] . '</label>
					<p class="font-weight-bold">' . $fila['unad24nombre'] . '</p>
				</div>
			</div>
			
			<div class="form-group row">
				<div class="col-sm-6">
					<label>' . $ETI['saiu00idescuela'] . '</label>
					<p class="font-weight-bold">' . $fila['core12nombre'] . '</p>
				</div>
				<div class="col-sm-6">
					<label>' . $ETI['saiu00idprograma'] . '</label>
					<p class="font-weight-bold">' . $fila['core09nombre'] . '</p>
				</div>
			</div><hr>';
			$sHTML = $sHTML . '
			<div class="text-center">
				<p>
				<strong>' . $ETI['texto_encuesta'] . '</strong>
				</p>
			</div>
			<div class="table-responsive">
				<table class="table table-sm table-hover">
					<thead>
						<tr>
						<th scope="col"></th>
						<th scope="col">' . $ETI['valor5'] . '</th>
						<th scope="col">' . $ETI['valor4'] . '</th>
						<th scope="col">' . $ETI['valor3'] . '</th>
						<th scope="col">' . $ETI['valor2'] . '</th>
						<th scope="col">' . $ETI['valor1'] . '</th>
						</tr>
					</thead>
					<tbody>';
			$iOpcion = 0;
			foreach ($aPreguntas as $aPregunta) {
				$sHTML = $sHTML . '
				<tr>
					<th scope="row">' . $ETI[$aPregunta[0]] . '
						<a class="badge badge-info float-right" data-toggle="collapse" href="#' . $aOpciones[$iOpcion][1] . '" role="button" aria-expanded="false" aria-controls="' . $aOpciones[$iOpcion][1] . '">
							' . $ETI['bt_motivo'] . '
						</a>					
					</th>';
				for ($i = 5; $i > 0; $i--) {
					$sId = $aOpciones[$iOpcion][0] . $i;
					$sHTML = $sHTML . '
					<td class="text-center">
						<div class="custom-control custom-radio">
							<input class="custom-control-input" type="radio" name="' . $aOpciones[$iOpcion][0] . '" id="' . $sId . '" value="' . $i . '" required />
							<label class="custom-control-label" for="' . $sId . '"></label>
						</div>
					</td>';
				}
				$sHTML = $sHTML . '
				</tr>
				<tr class="collapse" id="' . $aOpciones[$iOpcion][1] . '">
					<td colspan="6">
						<input class="form-control form-control-sm" type="text" name="' . $aOpciones[$iOpcion][1] . '" placeholder="' . $ETI['motivo'] . '">
					</td>
				</tr>';
				$iOpcion = $iOpcion + 1;
			}
			$sHTML = $sHTML . '
					</tbody>
				</table>
			</div>
			<hr>';
			$sHTML = $sHTML . '<input type="button" id="cmdEnviaEncuesta" name="cmdEnviaEncuesta" class="btn btn-aurea px-4 float-right" title="' . $ETI['bt_enviar'] . '" value="' . $ETI['bt_enviar'] . '" onclick="enviaencuesta()">
			</form>';
		} else {
			$sHTML = $sHTML . htmlAlertas('naranja', $ETI['saiu00cerrada']);
		}
	} else {
		$sHTML = $sHTML . htmlAlertas('rojo', $ERR['saiu00noexiste']);
	}
	return array($sHTML, $sError);
}
function f3000_GuardaEncuesta($aParametros)
{
	$_SESSION['u_ultimominuto'] = iminutoavance();
	if (!is_array($aParametros)) {
		$aParametros = json_decode(str_replace('\"', '"', $aParametros), true);
	}
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_3000 = $APP->rutacomun . 'lg/lg_3000_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_3000)) {
		$mensajes_3000 = $APP->rutacomun . 'lg/lg_3000_es.php';
	}
	require $mensajes_todas;
	require $mensajes_3000;
	$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto != '') {
		$objDB->dbPuerto = $APP->dbpuerto;
	}
	$sError = '';
	// -- Se inicia validando todas las posibles entradas de usuario.
	if (isset($aParametros[100]) == 0) {
		$aParametros[100] = '';
	}
	if (isset($aParametros[101]) == 0) {
		$aParametros[101] = 0;
	}
	if (isset($aParametros[102]) == 0) {
		$aParametros[102] = 0;
	}
	if (isset($aParametros[103]) == 0) {
		$aParametros[103] = '';
	}
	if (isset($aParametros[104]) == 0) {
		$aParametros[104] = 0;
	}
	if (isset($aParametros[105]) == 0) {
		$aParametros[105] = '';
	}
	if (isset($aParametros[106]) == 0) {
		$aParametros[106] = 0;
	}
	if (isset($aParametros[107]) == 0) {
		$aParametros[107] = '';
	}
	if (isset($aParametros[108]) == 0) {
		$aParametros[108] = 0;
	}
	if (isset($aParametros[109]) == 0) {
		$aParametros[109] = '';
	}
	if (isset($aParametros[110]) == 0) {
		$aParametros[110] = 0;
	}
	if (isset($aParametros[111]) == 0) {
		$aParametros[111] = '';
	}
	if (isset($aParametros[112]) == 0) {
		$aParametros[112] = 0;
	}
	if (isset($aParametros[113]) == 0) {
		$aParametros[113] = '';
	}
	if (isset($aParametros[114]) == 0) {
		$aParametros[114] = '';
	}
	if (isset($aParametros[115]) == 0) {
		$aParametros[115] = '';
	}
	$sContenedor = cadena_Validar($aParametros[100]);
	$saiuNNid = numeros_validar($aParametros[101]);
	$saiuNNevalamabilidad = numeros_validar($aParametros[102]);
	$saiuNNevalamabmotivo = cadena_Validar($aParametros[103]);
	$saiuNNevalrapidez = numeros_validar($aParametros[104]);
	$saiuNNevalrapidmotivo = cadena_Validar($aParametros[105]);
	$saiuNNevalclaridad = numeros_validar($aParametros[106]);
	$saiuNNevalcalridmotivo = cadena_Validar($aParametros[107]);
	$saiuNNevalresolvio = numeros_validar($aParametros[108]);
	$saiuNNevalsugerencias = cadena_Validar($aParametros[109]);
	$saiuNNevalconocimiento = numeros_validar($aParametros[110]);
	$saiuNNevalconocmotivo = cadena_Validar($aParametros[111]);
	$saiuNNevalutilidad = numeros_validar($aParametros[112]);
	$saiuNNevalutilmotivo = cadena_Validar($aParametros[113]);
	$sTabla = cadena_Validar($aParametros[114]);
	$saiuid = numeros_validar($aParametros[115]);
	// -- Seccion para validar los posibles causales de error.
	$sSepara = ', ';
	if ($sContenedor == '') {
		// $sError = $ERR['saiu00fecha'] . $sSepara . $sError;
	}
	if ($sTabla == '') {
		$sError = $ERR['tabla'] . $sSepara . $sError;
	}
	if ($saiuid == '' || $saiuid == 0) {
		$sError = $ERR['saiuid'] . $sSepara . $sError;
	}
	if ($saiuNNid == '' || $saiuNNid == 0) {
		$sError = $ERR['saiu00id'] . $sSepara . $sError;
	}
	if ($saiuNNevalamabilidad == '' || $saiuNNevalamabilidad == 0) {
		$sError = $ERR['saiu00evalamabilidad'] . $sSepara . $sError;
	}
	if ($saiuNNevalrapidez == '' || $saiuNNevalrapidez == 0) {
		$sError = $ERR['saiu00evalrapidez'] . $sSepara . $sError;
	}
	if ($saiuNNevalclaridad == '' || $saiuNNevalclaridad == 0) {
		$sError = $ERR['saiu00evalclaridad'] . $sSepara . $sError;
	}
	if ($saiuNNevalresolvio == '' || $saiuNNevalresolvio == 0) {
		$sError = $ERR['saiu00evalresolvio'] . $sSepara . $sError;
	}
	if ($saiuNNevalconocimiento == '' || $saiuNNevalconocimiento == 0) {
		$sError = $ERR['saiu00evalconocimiento'] . $sSepara . $sError;
	}
	if ($saiuNNevalutilidad == '' || $saiuNNevalutilidad == 0) {
		$sError = $ERR['saiu00evalutilidad'] . $sSepara . $sError;
	}

	if ($sError == '') {
		if ($objDB->bexistetabla($sTabla)) {
			if (!strstr($sTabla, 'saiu'.$saiuid)) {
				$sError = $ERR['contenedor'] . $sSepara . $sError;
			}
		} else {
			$sError = $ERR['contenedor'] . $sSepara . $sError;
		}
	}

	if ($sError == '') {
		$saiuNNevalacepta = 1;
		$saiuNNevalfecha = fecha_ArmarNumero();
		$bPasa = false;
		$sHTML = '';
		$sSQL = 'SELECT saiu'.$saiuid.'evalfecha FROM ' . $sTabla . ' WHERE saiu'.$saiuid.'id=' . $saiuNNid . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			if ($fila['saiu'.$saiuid.'evalfecha'] == 0) {
				$scampo[1] = 'saiu'.$saiuid.'evalamabilidad';
				$scampo[2] = 'saiu'.$saiuid.'evalamabmotivo';
				$scampo[3] = 'saiu'.$saiuid.'evalrapidez';
				$scampo[4] = 'saiu'.$saiuid.'evalrapidmotivo';
				$scampo[5] = 'saiu'.$saiuid.'evalclaridad';
				$scampo[6] = 'saiu'.$saiuid.'evalcalridmotivo';
				$scampo[7] = 'saiu'.$saiuid.'evalresolvio';
				$scampo[8] = 'saiu'.$saiuid.'evalsugerencias';
				$scampo[9] = 'saiu'.$saiuid.'evalconocimiento';
				$scampo[10] = 'saiu'.$saiuid.'evalconocmotivo';
				$scampo[11] = 'saiu'.$saiuid.'evalutilidad';
				$scampo[12] = 'saiu'.$saiuid.'evalutilmotivo';
				$scampo[13] = 'saiu'.$saiuid.'evalacepta';
				$scampo[14] = 'saiu'.$saiuid.'evalfecha';
				$sdato[1] = $saiuNNevalamabilidad;
				$sdato[2] = $saiuNNevalamabmotivo;
				$sdato[3] = $saiuNNevalrapidez;
				$sdato[4] = $saiuNNevalrapidmotivo;
				$sdato[5] = $saiuNNevalclaridad;
				$sdato[6] = $saiuNNevalcalridmotivo;
				$sdato[7] = $saiuNNevalresolvio;
				$sdato[8] = $saiuNNevalsugerencias;
				$sdato[9] = $saiuNNevalconocimiento;
				$sdato[10] = $saiuNNevalconocmotivo;
				$sdato[11] = $saiuNNevalutilidad;
				$sdato[12] = $saiuNNevalutilmotivo;
				$sdato[13] = $saiuNNevalacepta;
				$sdato[14] = $saiuNNevalfecha;
				$numcmod = 14;
				$sWhere = 'saiu'.$saiuid.'id=' . $saiuNNid . '';
				$sSQL = 'SELECT * FROM ' . $sTabla . ' WHERE ' . $sWhere;
				$sdatos = '';
				$result = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($result) > 0) {
					$filabase = $objDB->sf($result);
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
						$sSQL = 'UPDATE ' . $sTabla . ' SET ' . cadena_codificar($sdatos) . ' WHERE ' . $sWhere . ';';
					} else {
						$sdetalle = $sdatos . '[' . $sWhere . ']';
						$sSQL = 'UPDATE ' . $sTabla . ' SET ' . $sdatos . ' WHERE ' . $sWhere . ';';
					}
				}
				if ($bPasa) {
					$result = $objDB->ejecutasql($sSQL);
					if ($result == false) {
						$sError = $ERR['falla_guardar'];
					} else {
						$sHTML = htmlAlertas('verde', $ETI['saiu00gracias']);
					}
				}
			} else {
				$sHTML = htmlAlertas('naranja', $ETI['saiu00cerrada']);
			}
		} else {
			$sHTML = htmlAlertas('rojo', $ERR['saiu00noexiste']);
		}
	}
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	if ($sError == '') {
		$objResponse->assign('div_saiu'.$saiuid.'formencuesta', 'innerHTML', $sHTML);
		$objResponse->assign('div_saiu'.$saiuid.'formencuesta', 'style.display', 'block');
		$objResponse->assign('div_saiu05formcodigo', 'style.display', 'none');
	} else {
		$objResponse->call('muestramensajes("danger", "' . $sError . '")');
	}
	return $objResponse;
}
function f3000_HTMLNoRespondeEncuesta($sTabla, $saiuid, $saiuNNid, $objDB, $bDebug = false)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_3000 = $APP->rutacomun . 'lg/lg_3000_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_3000)) {
		$mensajes_3000 = $APP->rutacomun . 'lg/lg_3000_es.php';
	}
	require $mensajes_todas;
	require $mensajes_3000;
	$saiuNNevalacepta = 0;
	$saiuNNevalfecha = fecha_ArmarNumero();
	$bPasa = false;	
	$sHTML = '';
	$sError = '';
	$sDebug = '';
	$sSQL = 'SELECT saiu'.$saiuid.'evalfecha FROM ' . $sTabla . ' WHERE saiu'.$saiuid.'id=' . $saiuNNid . '';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		if ($fila['saiu'.$saiuid.'evalfecha'] == 0) {
			$scampo[1] = 'saiu'.$saiuid.'evalacepta';
			$scampo[2] = 'saiu'.$saiuid.'evalfecha';
			$sdato[1] = $saiuNNevalacepta;
			$sdato[2] = $saiuNNevalfecha;
			$numcmod = 2;
			$sWhere = 'saiu'.$saiuid.'id=' . $saiuNNid . '';
			$sSQL = 'SELECT * FROM ' . $sTabla . ' WHERE ' . $sWhere;
			$sdatos = '';
			$result = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($result) > 0) {
				$filabase = $objDB->sf($result);
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
					$sSQL = 'UPDATE ' . $sTabla . ' SET ' . cadena_codificar($sdatos) . ' WHERE ' . $sWhere . ';';
				} else {
					$sdetalle = $sdatos . '[' . $sWhere . ']';
					$sSQL = 'UPDATE ' . $sTabla . ' SET ' . $sdatos . ' WHERE ' . $sWhere . ';';
				}
			}
			if ($bPasa) {
				$result = $objDB->ejecutasql($sSQL);
				if ($result == false) {
					$sHTML = htmlAlertas('rojo', $ERR['falla_guardar']);
				} else {
					$sHTML = htmlAlertas('verde', $ETI['saiu00gracias']);
				}
			}
		} else {
			$sHTML = $sHTML . htmlAlertas('naranja', $ETI['saiu00cerrada']);
		}
	} else {
		$sHTML = $sHTML . htmlAlertas('rojo', $ERR['saiu00noexiste']);
	}
	return $sHTML;
}
function f3000_ValidaTablasAtencion($sContenedor, $saiuid, $objDB)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_3000 = $APP->rutacomun . 'lg/lg_3000_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_3000)) {
		$mensajes_3000 = $APP->rutacomun . 'lg/lg_3000_es.php';
	}
	require $mensajes_todas;
	require $mensajes_3000;
	$sTabla = '';
	$sError = '';
	switch ($saiuid) {
		case '18':
			$sTabla = 'saiu'.$saiuid.'telefonico_' . $sContenedor;
			break;
		case '19':
			$sTabla = 'saiu'.$saiuid.'chat_' . $sContenedor;
			break;
		case '20':
			$sTabla = 'saiu'.$saiuid.'correo_' . $sContenedor;
			break;
		case '21':
			$sTabla = 'saiu'.$saiuid.'directa_' . $sContenedor;
			break;
		case '73':
			$sTabla = 'saiu'.$saiuid.'solusuario_' . $sContenedor;
			break;
		default:
			$sError = $sError . $ERR['saui00numref'];
			break;
	}
	if ($sError == '') {
		if ($objDB->bexistetabla($sTabla)) {
			switch ($saiuid) {
				case '18':
					list($sErrorR, $sDebugR) = f3018_RevisarTabla($sContenedor, $objDB);
					$sError = $sError . $sErrorR;
					break;
				case '19':
					list($sErrorR, $sDebugR) = f3019_RevisarTabla($sContenedor, $objDB);
					$sError = $sError . $sErrorR;
					break;
				case '20':
					list($sErrorR, $sDebugR) = f3020_RevisarTabla($sContenedor, $objDB);
					$sError = $sError . $sErrorR;
					break;
				case '21':
					list($sErrorR, $sDebugR) = f3021_RevisarTabla($sContenedor, $objDB);
					$sError = $sError . $sErrorR;
					break;					
				case '73':
					list($sErrorR, $sDebugR) = f3073_RevisarTabla($sContenedor, $objDB);
					$sError = $sError . $sErrorR;
					break;					
			}					
		} else  {
			$sError = $sError . $ERR['contenedor'];
		}
	}
	return array($sTabla, $sError);
}
function f3000_NotificarResponsables($aParametros) {
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_3000 = $APP->rutacomun . 'lg/lg_3000_' . $sIdioma . '.php';
	if (!file_exists($mensajes_3000)) {
		$mensajes_3000 = $APP->rutacomun . 'lg/lg_3000_es.php';
	}
	require $mensajes_todas;
	require $mensajes_3000;
	require $APP->rutacomun . 'libmail.php';
	$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto != '') {
		$objDB->dbPuerto = $APP->dbpuerto;
	}
	$sError = '';
	$sDebug = '';
	$sMensaje = '';
	// -- Se inicia validando todas las posibles entradas de usuario.
	$_SESSION['u_ultimominuto'] = iminutoavance();
	if (!is_array($aParametros)) {
		$aParametros = json_decode(str_replace('\"', '"', $aParametros), true);
	}
	if (isset($aParametros[99]) == 0) {
		$aParametros[99] = false;
	}
	if (isset($aParametros[100]) == 0) {
		$aParametros[100] = $_SESSION['unad_id_tercero'];
	}
	if (isset($aParametros[101]) == 0) {
		$aParametros[101] = 0;
	}
	$bDebug = $aParametros[99];
	$idTercero = numeros_validar($aParametros[100]);
	$sPendientes = cadena_validar($aParametros[101]);
	$aPendientes = array();
	// -- Seccion para validar los posibles causales de error.
	$sSepara = ', ';
	if ($sPendientes == '') {
		$sError = $ERR['pendientes_vacia'] . $sSepara . $sError;
	}

	if ($sError == '') {
		$aPendientes = json_decode($sPendientes, true);
		if ($aPendientes == null) {
			$sError = $ERR['pendientes_invalida'] . $sSepara . $sError;
		}
	}

	if ($sError == '') {
		$sNomEntidad = '';
		$sMailSeguridad = '';
		$sURLCampus = '';
		$sCorreoCopia = 'sai@unad.edu.co';
		$sMensaje = $sMensaje . $ETI['pendientes_ok'];
		if ($sCorreoCopia != '') {
			$sMensaje = $sMensaje . ' (con copia a ' . $sCorreoCopia . '):<br>';
		}
		$idEntidad = Traer_Entidad();
		$iHoy = fecha_DiaMod();
		$sFechaLargaHoy = formato_FechaLargaDesdeNumero($iHoy, true);
		switch ($idEntidad) {
			case 1: // UNAD FLORIDA
				$sNomEntidad = 'UNAD FLORIDA INC';
				$sMailSeguridad = 'aluna@unad.us';
				$sURLCampus = 'http://unad.us/campus/';
				break;
			default: // UNAD Colombia
				$sNomEntidad = 'UNIVERSIDAD NACIONAL ABIERTA Y A DISTANCIA - UNAD';
				$sMailSeguridad = 'soporte.campus@unad.edu.co';
				$sURLCampus = 'https://campus0c.unad.edu.co/campus/';
				break;
		}
		$sMes = date('Ym');
		$sTabla = 'aure01login' . $sMes;
		list($idSMTP, $sDebugS) = AUREA_SmtpMejor($sTabla, $objDB, $bDebug);
		$objMail = new clsMail_Unad($objDB);
		$objMail->TraerSMTP($idSMTP);
		foreach($aPendientes as $idResponsable => $aLista) {
			$bCorreoValido = false;
			list($sCorreoMensajes, $unad11idgrupocorreo, $sError, $sDebugN) = AUREA_CorreoPrimario($idResponsable, $objDB, $bDebug);
			if ($sError == '') {
				list($bCorreoValido, $sDebugC) = correo_VerificarV2($sCorreoMensajes);
			}
			if ($bCorreoValido) {
				list($unad11razonsocial, $sErrorDet) = tabla_campoxid('unad11terceros', 'unad11razonsocial', 'unad11id', $idResponsable, '{' . 'An&oacute;nimo' . '}', $objDB);
				$sTituloMensaje = $ETI['mail_pend_titulo'] . $sFechaLargaHoy . ' ' . $sNomEntidad . '';
				$sCuerpo = '<p style="text-align: justify;">Cordial saludo.<br>';
				$sCuerpo = $sCuerpo . 'Estimado(a) <b>' . $unad11razonsocial . '</b><br><br>';
				$sCuerpo = $sCuerpo . 'Desde el Sistema de Atenci&oacute;n Integral (SAI) queremos recordar la importancia de gestionar de manera efectiva y responsable las PQRS. ';
				$sCuerpo = $sCuerpo . 'Al hacerlo, no solo respondemos a inquietudes y solicitudes, sino que tambi&eacute;n demostramos el compromiso de nuestra instituci&oacute;n con la calidad y excelencia en el servicio.<br>';
				$sCuerpo = $sCuerpo . 'Por ello, le invitamos a asegurar que cada solicitud y PQRS sea atendida dentro de los plazos establecidos, con empat&iacute;a, transparencia y soluciones efectivas.</p>';
				$sCuerpo = $sCuerpo . 'A continuaci&oacute;n, se listan las PQRS pendientes por gestionar.<br><br>';
				$sCuerpo = $sCuerpo . '<table style="border: 1px solid black; border-collapse: collapse;"><thead style="background-color: black; color: white;"><tr><th style="width: 25%;">No. referencia<br>solicitud</th><th>Fecha de solicitud</th><th>Tema</th><th>Días h&aacute;biles de vencimiento</th></tr></thead><tbody>';
				$iCuenta = 0;
				foreach($aLista as $aSolicitud) {
					$sFechaLargaIni = formato_FechaLargaDesdeNumero($aSolicitud['fechainicio'], true);
					$i_saiu05idtemaorigen = $aSolicitud['idtemaorigen'];
					if (isset($asaiu05idtemaorigen[$i_saiu05idtemaorigen]) == 0) {
						$sSQL = 'SELECT saiu03titulo FROM saiu03temasol WHERE saiu03id=' . $i_saiu05idtemaorigen . '';
						$tablae = $objDB->ejecutasql($sSQL);
						if ($objDB->nf($tablae) > 0) {
							$filae = $objDB->sf($tablae);
							$asaiu05idtemaorigen[$i_saiu05idtemaorigen] = cadena_LimpiarTildes($filae['saiu03titulo']);
						} else {
							$asaiu05idtemaorigen[$i_saiu05idtemaorigen] = '';
						}
					}
					$saiu05idtemaorigen = ($asaiu05idtemaorigen[$i_saiu05idtemaorigen]);
					$sFondo = '';
					if ($iCuenta % 2 != 0) {
						$sFondo = 'style="background-color:lightgray;"';
					}
					$sArgs = url_encode($aSolicitud['agno'] . '|' . $aSolicitud['mes'] . '|' . $aSolicitud['id']);
					$sUrlNumRef = 'https://aurea2.unad.edu.co/sai/saiusolcitudes.php?u='.$sArgs;
					$sCuerpo = $sCuerpo . '<tr ' . $sFondo .'><td><a href="'.$sUrlNumRef.'" target="_blank">' . $aSolicitud['numref'] . '</a></td><td>' . $sFechaLargaIni . '</td><td style="text-align: center;">' . $saiu05idtemaorigen . '</td><td style="text-align: center;">' . $aSolicitud['dias'] . '</td></tr>';
					$iCuenta = $iCuenta + 1;
				}
				$sCuerpo = $sCuerpo . '</tbody></table><br><br>';
				$sCuerpo = $sCuerpo . 'Agradecemos su colaboración para seguir fortaleciendo la cultura del buen servicio Unadista<br><br>';
				$sCuerpo = $sCuerpo . 'Cordialmente,<br>';
				$sCuerpo = $sCuerpo . '<b>Sistema de Atención Integral - SAI</b><br>';
				$sCuerpo = AUREA_HTML_EncabezadoCorreo($sTituloMensaje) . $sCuerpo . AUREA_HTML_NoResponder() . AUREA_NotificaPieDePagina() . AUREA_HTML_PieCorreo();
				$objMail->NuevoMensaje();
				$objMail->sAsunto = cadena_codificar($sTituloMensaje);
				$sMensaje = $sMensaje . '<div class="flex gap-2"><div>Se notifica al correo ' . $sCorreoMensajes . '</div><i class="icon-check"></i></div>';
				$objMail->addCorreo($sCorreoMensajes, $sCorreoMensajes);
				if ($sCorreoCopia != '') {
					$objMail->addCorreo($sCorreoCopia, $sCorreoCopia, 'O');					
				}
				if ($sError == '') {
					$objMail->sCuerpo = $sCuerpo;
					if ($bDebug) {
						$sDebug = $sDebug . fecha_microtiempo() . ' Enviando respuesta de solicitud a : ' . $sCorreoMensajes . '<br>';
					}
					list($sErrorM, $sDebugM) = $objMail->EnviarV2($bDebug);
					$sError = $sError . $sErrorM;
					$sDebug = $sDebug . $sDebugM;
					if ($sError != '') {
						$sMensaje = '<div class="flex gap-2"><div>' . $ERR['mail_pend_error'] . '</div><i class="icon-closed"></i></div>';
					}
				}
			} else {
				$sMensaje = '<div class="flex gap-2"><div>' . $ERR['mail_valido'] . '</div><i class="icon-closed"></i></div>';
			}
		}
	}
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	if ($sError == '') {
		// @@@ 07-10-2024 Ajustar esto.
		$objResponse->call("MensajeAlarmaV2('" . $sMensaje . "', 2)");
	} else {
		$objResponse->call("MensajeAlarmaV2('" . $sError . "', 0)");
	}
	return $objResponse;
}

// -- Devolver el responsable de una red de servicio.
function f3074_ActorRedServicio($id73, $idZona, $idCentro, $objDB, $bDebug = false) 
{
	$idUnidad = 0;
	$idGrupoTrabajo = 0;
	$idResponsable = 0;
	$sError = '';
	$sDebug = '';
	$bResuelve = false;
	$bTraerLider = false;
	$idAdministrador = 0;
	$sSQL = 'SELECT saiu74idunidad, saiu74idadministrador, saiu74activa FROM saiu74reddeservicio WHERE saiu74id=' . $id73 . '';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$idUnidad = $fila['saiu74idunidad'];
		$idAdministrador = $fila['saiu74idadministrador'];
		if ($fila['saiu74activa'] == 0) {
			$sError = 'La red de servicio no se encuentra activa.';
		}
	} else {
		$sError = 'No se ha encontrado la red de servicio Ref ' . $id73 . '';
	}
	if (($sError == '') && ((int)$idCentro > 0)) {
		//Si el centro es mayor a 0 buscamos para el centro.
		$sSQL = 'SELECT saiu75idequipo FROM saiu75redequipo WHERE saiu75idred=' . $id73 . ' AND saiu75idcentro=' . $idCentro . ' AND saiu75idequipo>0';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			$idGrupoTrabajo = $fila['saiu75idequipo'];
			$bResuelve = true;
			$bTraerLider = true;
		}
	}
	if ((!$bResuelve) && ($sError == '') && ((int)$idZona > 0)) {
		//No lo ha encontrado por centro, vamos por zona
		$sSQL = 'SELECT saiu75idequipo FROM saiu75redequipo WHERE saiu75idred=' . $id73 . ' AND saiu75idzona=' . $idZona . ' AND saiu75idcentro=0 AND saiu75idequipo>0';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			$idGrupoTrabajo = $fila['saiu75idequipo'];
			$bResuelve = true;
			$bTraerLider = true;
		}
	}
	if ((!$bResuelve) && ($sError == '')) {
		//Si no hay datos, se envia al administrador
		$idResponsable = $idAdministrador;
		//busca equipo de líderes nacionales
		$sSQL = 'SELECT saiu75idequipo FROM saiu75redequipo WHERE saiu75idred=' . $id73 . ' AND saiu75idzona=0 AND saiu75idcentro=0 AND saiu75idequipo>0';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			$idGrupoTrabajo = $fila['saiu75idequipo'];
		}
	}
	if ($bTraerLider) {
		//Traer el lider del equipo de trabajo.
		$sSQL = 'SELECT bita27idlider FROM bita27equipotrabajo WHERE bita27id=' . $idGrupoTrabajo . ' AND bita27idlider>0';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			$idAdministrador = $fila['bita27idlider'];
		}
		//Traer integrantes del equipo de trabajo.
		$aIntegrantes = array();
		$iIntegrantes = 0;
		$sSQL = 'SELECT bita28idtercero FROM bita28eqipoparte WHERE bita28idequipotrab=' . $idGrupoTrabajo . ' AND bita28activo="S"';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			while ($fila = $objDB->sf($tabla)) {
				$aIntegrantes[] = $fila['bita28idtercero'];
				$iIntegrantes = $iIntegrantes + 1;
			}
			$iSeleccionado = rand(0, $iIntegrantes-1);
			$idResponsable = $aIntegrantes[$iSeleccionado];
		} else {
			$idResponsable = $idAdministrador;
		}
	}
	return array($idUnidad, $idGrupoTrabajo, $idAdministrador, $idResponsable, $sError, $sDebug);
}
function f3000_ConsultaResponsable($idTema, $idZona, $idCentro, $objDB, $bDebug = false)
{
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_3000 = $APP->rutacomun . 'lg/lg_3000_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_3000)) {
		$mensajes_3000 = $APP->rutacomun . 'lg/lg_3000_es.php';
	}
	require $mensajes_todas;
	require $mensajes_3000;
	$idTema = numeros_validar($idTema);
	$idZona = numeros_validar($idZona);
	$idCentro = numeros_validar($idCentro);
	$sError = '';
	$iTipoError = 0;
	$sDebug = '';
	$sSepara = '<br>';
	$aParametros = array(
		'idunidad' => 0,
		'idequipo' => 0,
		'idsupervisor' => 0,
		'idresponsable' => 0,
		'tiemprespdias' => 0,
		'tiempresphoras' => 0
	);
	if ($idTema == '') {
		$sError = $sError. $ERR['saiu00idtema'] . $sSepara;
	}
	if ($idZona == '') {
		$sError = $sError. $ERR['saiu00idzona'] . $sSepara;
	}
	if ($idCentro == '') {
		$sError = $sError. $ERR['saiu00idcentro'] . $sSepara;
	}
	if ($sError == '') {
		$sSQL = 'SELECT saiu03idunidadresp1, saiu03idequiporesp1, saiu03idliderrespon1, saiu03tiemprespdias1, saiu03tiempresphoras1, 
		saiu03reddeservicio
		FROM saiu03temasol
		WHERE saiu03id = ' . $idTema . '';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' Consulta responsable solicitud ' . $sSQL . '<br>';
		}
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			$aParametros['idunidad'] = $fila['saiu03idunidadresp1'];
			$aParametros['idequipo'] = $fila['saiu03idequiporesp1'];
			$aParametros['idsupervisor'] = $fila['saiu03idliderrespon1'];
			$aParametros['idresponsable'] = $fila['saiu03idliderrespon1'];
			$aParametros['tiemprespdias'] = $fila['saiu03tiemprespdias1'];
			$aParametros['tiempresphoras'] = $fila['saiu03tiempresphoras1'];
			if ($fila['saiu03reddeservicio'] != 0) {
				list($idUnidad, $idEquipo, $idSupervisor, $idResponsable, $sError, $sDebug) = f3074_ActorRedServicio($fila['saiu03reddeservicio'], $idZona, $idCentro, $objDB, $bDebug);
				$aParametros['idunidad'] = $idUnidad;
				$aParametros['idequipo'] = $idEquipo;
				$aParametros['idsupervisor'] = $idSupervisor;
				$aParametros['idresponsable'] = $idResponsable;
			}
		} else {
			$sError = $sError . $ERR['saiu00noidtema'];
		}
	}
	return array($aParametros, $sError, $iTipoError, $sDebug);
}