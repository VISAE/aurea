<?php
/*
--- © Cristhiam Dario Silva Chavez - UNAD - 2022 ---
--- cristhiam.silva@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.28.4b viernes, 21 de octubre de 2022
--- 3803 Cupos
*/
function f3803_db_Guardar($valores, $objDB, $bDebug = false, $idTercero = 0)
{
	$iCodModulo = 3803;
	$bAudita[2] = false;
	$bAudita[3] = false;
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_3803 = 'lg/lg_3803_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_3803)) {
		$mensajes_3803 = 'lg/lg_3803_es.php';
	}
	require $mensajes_todas;
	require $mensajes_3803;
	$sError = '';
	$iTipoError = 0;
	$sDebug = '';
	if ($idTercero == 0) {
		$idTercero = $_SESSION['unad_id_tercero'];
	}
	$bInserta = false;
	$iAccion = 3;
	if (!is_array($valores)) {
		$valores = json_decode(str_replace('\"', '"', $valores), true);
	}
	$cipa03idoferta = numeros_validar($valores[1]);
	$cipa03idinscrito = numeros_validar($valores[2]);
	$cipa03id = numeros_validar($valores[3], true);
	$cipa03asistencia = numeros_validar($valores[4]);
	$cipa03jornada_1 = numeros_validar($valores[5]);
	$cipa03jornada_2 = numeros_validar($valores[6]);
	$cipa03jornada_3 = numeros_validar($valores[7]);
	$cipa03jornada_4 = numeros_validar($valores[8]);
	$cipa03jornada_5 = numeros_validar($valores[9]);
	$cipa03idmatricula = numeros_validar($valores[10]);
	$cipa03valoracion = numeros_validar($valores[11]);
	$cipa03retroalimentacion = htmlspecialchars(trim($valores[12]));
	/*
	if ($cipa03asistencia == '') {
		$cipa03asistencia = 0;
	}
	if ($cipa03jornada_1 == '') {
		$cipa03jornada_1 = 0;
	}
	if ($cipa03jornada_2 == '') {
		$cipa03jornada_2 = 0;
	}
	if ($cipa03jornada_3 == '') {
		$cipa03jornada_3 = 0;
	}
	if ($cipa03jornada_4 == '') {
		$cipa03jornada_4 = 0;
	}
	if ($cipa03jornada_5 == '') {
		$cipa03jornada_5 = 0;
	}
	if ($cipa03idmatricula == '') {
		$cipa03idmatricula = 0;
	}
	if ($cipa03valoracion == '') {
		$cipa03valoracion = 0;
	}
	*/
	// -- Seccion para validar los posibles causales de error.
	$sSepara = ', ';
	if ($cipa03retroalimentacion == '') {
		$sError = $ERR['cipa03retroalimentacion'] . $sSepara.$sError;
	}
	if ($cipa03valoracion == '') {
		$sError = $ERR['cipa03valoracion'] . $sSepara.$sError;
	}
	/*
	if ($cipa03id == '') {
		$sError = $ERR['cipa03id'] . $sSepara . $sError;
	}
	*/
	// -- Se verifican los valores de campos de otras tablas.
	if ($sError == '') {
		list($sError, $sInfo) = tercero_Bloqueado($cipa03idinscrito, $objDB);
		if ($sInfo != '') {
			$sError = $sError . '<br>' . $sInfo;
		}
	}
	$bQuitarCodigo = false;
	$sCampoCodigo = '';
	if ($sError == '') {
		if ((int)$cipa03id == 0) {
			if ($sError == '') {
				$sSQL = 'SELECT 1 FROM cipa03cupos WHERE cipa03idoferta=' . $cipa03idoferta . ' AND cipa03idinscrito="' . $cipa03idinscrito . '"';
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
			if ($sError == '') {
				$cipa03id = tabla_consecutivo('cipa03cupos', 'cipa03id', '', $objDB);
				if ($cipa03id == -1) {
					$sError = $objDB->serror;
				}
				$bInserta = true;
				$iAccion = 2;
			}
		} else {
			list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 3, $idTercero, $objDB);
			if (!$bDevuelve) {
				$sError = $ERR['3'];
			}
		}
	}
	if ($sError == '') {
		if ($bInserta) {
			//Preparar el Id, Si no lo hay se quita la comprobación.
			$cipa03asistencia = 0;
			$cipa03jornada_1 = 0;
			$cipa03jornada_2 = 0;
			$cipa03jornada_3 = 0;
			$cipa03jornada_4 = 0;
			$cipa03jornada_5 = 0;
			$cipa03idmatricula = 0;
			/*
			$sSQL = 'SELECT Campo FROM Tabla WHERE Id=' . $sValorId;
			$tabla = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla) > 0) {
				$fila = $objDB->sf($tabla);
				$sCampo = $fila['sCampo'];
			}
			*/
			$sError = 'INFORMACION PARA EL ADMINISTRADOR DEL SISTEMA: No ha definido valores para los campos ocultos . ';
		}
	}
	if ($sError == '') {
		if ($bInserta) {
			$sCampos3803 = 'cipa03idoferta, cipa03idinscrito, cipa03id, cipa03asistencia, cipa03jornada_1, 
			cipa03jornada_2, cipa03jornada_3, cipa03jornada_4, cipa03jornada_5, cipa03idmatricula, 
			cipa03valoracion, cipa03retroalimentacion';
			$sValores3803 = '' . $cipa03idoferta . ', ' . $cipa03idinscrito . ', ' . $cipa03id . ', ' . $cipa03asistencia . ', ' . $cipa03jornada_1 . ', 
			' . $cipa03jornada_2 . ', ' . $cipa03jornada_3 . ', ' . $cipa03jornada_4 . ', ' . $cipa03jornada_5 . ', ' . $cipa03idmatricula . ', 
			' . $cipa03valoracion . ', "' . $cipa03retroalimentacion . '"';
			if ($APP->utf8 == 1) {
				$sSQL = 'INSERT INTO cipa03cupos (' . $sCampos3803 . ') VALUES (' . cadena_codificar($sValores3803) . ');';
			} else {
				$sSQL = 'INSERT INTO cipa03cupos (' . $sCampos3803 . ') VALUES (' . $sValores3803 . ');';
			}
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . ' Guardar 3803 ' . $sSQL . '<br>';
			}
			$result = $objDB->ejecutasql($sSQL);
			if ($result == false) {
				$sError = $ERR['falla_guardar'] . ' [3803].<!-- ' . $sSQL . ' -->';
			} else {
				if ($bAudita[2]) {
					seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 2, $cipa03id, $sSQL, $objDB);
				}
			}
		} else {
			$scampo3803[1] = 'cipa03valoracion';
			$scampo3803[2] = 'cipa03retroalimentacion';
			$svr3803[1] = $cipa03valoracion;
			$svr3803[2] = $cipa03retroalimentacion;
			$iNumCampos = 2;
			$sWhere = 'cipa03id=' . $cipa03id . '';
			//$sWhere = 'cipa03idoferta=' . $cipa03idoferta . ' AND cipa03idinscrito="' . $cipa03idinscrito . '"';
			$sSQL = 'SELECT * FROM cipa03cupos WHERE ' . $sWhere;
			$sdatos = '';
			$bPasa = false;
			$result = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($result) > 0) {
				$filaorigen = $objDB->sf($result);
				for ($k = 1; $k <= $iNumCampos; $k++) {
					if ($filaorigen[$scampo3803[$k]] != $svr3803[$k]) {
						if ($sdatos != '') {
							$sdatos = $sdatos . ', ';
						}
						$sdatos = $sdatos . $scampo3803[$k] . '="' . $svr3803[$k] . '"';
						$bPasa = true;
					}
				}
			}
			if ($bPasa) {
				if ($APP->utf8 == 1) {
					$sSQL = 'UPDATE cipa03cupos SET ' . cadena_codificar($sdatos) . ' WHERE ' . $sWhere . ';';
				} else {
					$sSQL = 'UPDATE cipa03cupos SET ' . $sdatos . ' WHERE ' . $sWhere . ';';
				}
				if ($bDebug) {
					$sDebug = $sDebug . fecha_microtiempo() . ' Guardar 3803 ' . $sSQL . '<br>';
				}
				$result = $objDB->ejecutasql($sSQL);
				if ($result == false) {
					$sError = $ERR['falla_guardar'] . ' {Cupos}. <!-- ' . $sSQL . ' -->';
				} else {
					if ($bAudita[3]) {
						seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 3, $cipa03id, $sSQL, $objDB);
					}
				}
			}
		}
	}
	return array($sError, $iAccion, $cipa03id, $sDebug);
}
function f3803_db_Eliminar($aParametros, $objDB, $bDebug = false, $idTercero = 0)
{
	$iCodModulo = 3803;
	$bAudita[4] = false;
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_3803 = 'lg/lg_3803_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_3803)) {
		$mensajes_3803 = 'lg/lg_3803_es.php';
	}
	require $mensajes_todas;
	require $mensajes_3803;
	$sError = '';
	$sDebug = '';
	if ($idTercero == 0) {
		$idTercero = $_SESSION['unad_id_tercero'];
	}
	if (!is_array($aParametros)) {
		$aParametros = json_decode(str_replace('\"', '"', $aParametros), true);
	}
	$cipa03idoferta = numeros_validar($aParametros[1]);
	$cipa03idinscrito = numeros_validar($aParametros[2]);
	$cipa03id = numeros_validar($aParametros[3]);
	if ($sError == '') {
		list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 4, $idTercero, $objDB);
		if (!$bDevuelve) {
			$sError = $ERR['4'];
		}
	}
	if ($sError == '') {
		$sSQL = 'SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=3803';
		$tablaor = $objDB->ejecutasql($sSQL);
		while ($filaor = $objDB->sf($tablaor)) {
			$sSQL = 'SELECT ' . $filaor['campoid'] . ' FROM ' . $filaor['tabla'] . ' WHERE ' . $filaor['camporev'] . '=' . $cipa03id . ' LIMIT 0, 1';
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
		//acciones previas
		$sWhere = 'cipa03id=' . $cipa03id . '';
		//$sWhere = 'cipa03idoferta=' . $cipa03idoferta . ' AND cipa03idinscrito="' . $cipa03idinscrito . '"';
		$sSQL = 'DELETE FROM cipa03cupos WHERE ' . $sWhere . ';';
		$result = $objDB->ejecutasql($sSQL);
		if ($result == false) {
			$sError = $ERR['falla_eliminar'] . ' {3803 Cupos}.<!-- ' . $sSQL . ' -->';
		} else {
			if ($bAudita[4]) {
				seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $cipa03id, $sSQL, $objDB);
			}
		}
	}
	return array($sError, $sDebug);
}
function f3803_TablaDetalleV2($aParametros, $objDB, $bDebug = false)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_3803 = 'lg/lg_3803_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_3803)) {
		$mensajes_3803 = 'lg/lg_3803_es.php';
	}
	require $mensajes_todas;
	require $mensajes_3803;
	if (!is_array($aParametros)) {
		$aParametros = json_decode(str_replace('\"', '"', $aParametros), true);
	}
	if (isset($aParametros[0]) == 0) {
		$aParametros[0] = -1;
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
	$idTercero = $aParametros[100];
	$aParametros[0] = numeros_validar($aParametros[0]);
	if ($aParametros[0] == '') {
		$aParametros[0] = -1;
	}
	$cipa01id = $aParametros[0];
	if (true) {
		//Leemos los parametros de entrada.
		$pagina = $aParametros[101];
		$lineastabla = $aParametros[102];
		$bDoc = trim($aParametros[103]);
		$bNombre = trim($aParametros[104]);
		$bEstado = trim($aParametros[105]);
	}
	$sDebug = '';
	$bAbierta = false;
	$cipa01estado = 0;
	$idPeriodo = 0;
	$sSQL = 'SELECT cipa01estado, cipa01periodo FROM cipa01oferta WHERE cipa01id=' . $cipa01id;
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$cipa01estado = $fila['cipa01estado'];
		$idPeriodo = $fila['cipa01periodo'];
	}
	$sLeyenda = '';
	$sBotones = '<input id="paginaf3803" name="paginaf3803" type="hidden" value="' . $pagina . '"/>
	<input id="lppf3803" name="lppf3803" type="hidden" value="' . $lineastabla . '"/>';
	if ($cipa01estado == 0) {
		$sLeyenda = 'Los cupos ser&aacute;n visibles una vez se oferte el CIPAS.';
	}
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
	$sTabla = 'cipa03cupos_' . $idPeriodo;
	if (!$objDB->bexistetabla($sTabla)){
		f3803_IniciarContenedor($idPeriodo, $objDB, $bDebug);
	}
	/*
	$aEstado=array();
	$sSQL = 'SELECT id, nombre FROM tabla';
	$tabla = $objDB->ejecutasql($sSQL);
	while ($fila = $objDB->sf($tabla)) {
		$aEstado[$fila['id']] = cadena_notildes($fila['nombre']);
	}
	*/
	$sSQLadd = '';
	$sSQLadd1 = '';
	if ($bDoc != -1) {
		$sSQLadd = $sSQLadd . ' AND T2.unad11doc LIKE "%' . $bDoc . '%"';
	}
	if ($bEstado != '') {
		$sSQLadd = $sSQLadd . ' AND TB.cipa03asistencia=' . $bEstado . '';
	}
	if ($bNombre != '') {
		$sBase = strtoupper($bNombre);
		$aNoms=explode(' ', $sBase);
		for ($k = 1; $k <= count($aNoms); $k++) {
			$sCadena = $aNoms[$k - 1];
			if ($sCadena != '') {
				$sSQLadd = $sSQLadd . ' AND T2.unad11razonsocial LIKE "%' . $sCadena . '%"';
			}
		}
	}
	$sTitulos = 'Oferta, Inscrito, Id, Asistencia, Jornada_1, Jornada_2, Jornada_3, Jornada_4, Jornada_5, Matricula, Valoracion, Retroalimentacion';
	$sSQL = 'SELECT TB.cipa03idoferta, T2.unad11razonsocial AS C2_nombre, TB.cipa03id, TB.cipa03asistencia, TB.cipa03jornada_1, 
	TB.cipa03jornada_2, TB.cipa03jornada_3, TB.cipa03jornada_4, TB.cipa03jornada_5, TB.cipa03idmatricula, TB.cipa03valoracion, 
	TB.cipa03retroalimentacion, TB.cipa03idinscrito, T2.unad11tipodoc AS C2_td, T2.unad11doc AS C2_doc 
	FROM ' . $sTabla . ' AS TB, unad11terceros AS T2 
	WHERE ' . $sSQLadd1 . ' TB.cipa03idoferta=' . $cipa01id . ' AND TB.cipa03idinscrito=T2.unad11id ' . $sSQLadd . '
	ORDER BY T2.unad11doc';
	$sSQLlista = str_replace("'", "|", $sSQL);
	$sSQLlista = str_replace('"', "|", $sSQLlista);
	$sErrConsulta = '<input id="consulta_3803" name="consulta_3803" type="hidden" value="' . $sSQLlista . '"/>
	<input id="titulos_3803" name="titulos_3803" type="hidden" value="' . $sTitulos . '"/>';
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' Consulta 3803: ' . $sSQL . '<br>';
	}
	$tabladetalle = $objDB->ejecutasql($sSQL);
	if ($tabladetalle == false) {
		$registros = 0;
		$sErrConsulta = $sErrConsulta . '..<input id="err" name="err" type="hidden" value="' . $sSQL . ' ' . $objDB->serror . '"/>';
		//$sLeyenda = $sSQL;
	} else {
		$registros = $objDB->nf($tabladetalle);
		if ($registros == 0) {
			//return array($sErrConsulta . $sBotones, $sDebug);
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
	$res = $sErrConsulta . $sLeyenda;
	$res = $res . '<div class="table-responsive">
	<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">
	<thead class="fondoazul"><tr>
	<td colspan="2"><b>' . $ETI['cipa03idinscrito'] . '</b></td>
	<td><b>' . $ETI['cipa03asistencia'] . '</b></td>
	<td align="right">
	' . html_paginador('paginaf3803', $registros, $lineastabla, $pagina, 'paginarf3803()') . '
	' . html_lpp('lppf3803', $lineastabla, 'paginarf3803()') . '
	</td>
	</tr></thead>';
	$tlinea = 1;
	while ($filadet = $objDB->sf($tabladetalle)) {
		$sPrefijo = '';
		$sSufijo = '';
		$sClass = ' class="resaltetabla"';
		$sLink = '';
		switch ($filadet['cipa03asistencia']){
			case '-2':
				$et_cipa03asistencia = $sPrefijo . $ETI['msg_preinscrito'] . $sSufijo;
				break;
			case '-1':
				$sPrefijo = '<b>';
				$sSufijo = '</b>';
				$et_cipa03asistencia = $sPrefijo . $ETI['msg_inscrito'] . $sSufijo;
				break;
			case 0:
				$sPrefijo = '<span class="rojo">';
				$sSufijo = '</span>';
				$et_cipa03asistencia = $sPrefijo . $ETI['msg_noasistente'] . $sSufijo;
				break;
			case 1:
				$sPrefijo = '<span class="verde">';
				$sSufijo = '</span>';
				$et_cipa03asistencia = $sPrefijo . $ETI['msg_asistente'] . $sSufijo;
				break;
			default:
				$et_cipa03asistencia = $sPrefijo . $filadet['cipa03asistencia'] . $sSufijo;
				break;
		}
		if (($tlinea % 2) != 0) {
			$sClass = '';
		}
		$tlinea++;
		$et_cipa03idinscrito_doc = '';
		$et_cipa03idinscrito_nombre = '';
		if ($filadet['cipa03idinscrito'] != 0) {
			$et_cipa03idinscrito_doc = $sPrefijo . $filadet['C2_td'] . ' ' . $filadet['C2_doc'] . $sSufijo;
			$et_cipa03idinscrito_nombre = $sPrefijo . cadena_notildes($filadet['C2_nombre']) . $sSufijo;
		}
		if ($bAbierta) {
			//$sLink = '<a href="javascript:cargaridf3803(' . $filadet['cipa03id'] . ')" class="lnkresalte">' . $ETI['lnk_cargar'] . '</a>';
		}
		$res = $res . '<tr' . $sClass . '>
		<td>' . $et_cipa03idinscrito_doc . '</td>
		<td>' . $et_cipa03idinscrito_nombre . '</td>
		<td>' . $et_cipa03asistencia . '</td>
		<td>' . $sLink . '</td>
		</tr>';
	}
	$res = $res . '</table>
	<div class="salto5px"></div>
	</div>';
	$objDB->liberar($tabladetalle);
	return array(cadena_codificar($res), $sDebug);
}
// -- 3803 Cupos XAJAX 
function f3803_Guardar($valores, $aParametros)
{
	$_SESSION['u_ultimominuto'] = iminutoavance();
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$sError = '';
	$bDebug = false;
	$sDebug = '';
	$bHayDb = false;
	$opts = $aParametros;
	if (!is_array($opts)) {
		$opts = json_decode(str_replace('\"', '"', $opts), true);
	}
	if (isset($opts[99]) != 0) {
		if ($opts[99] == 1) {
			$bDebug = true;
		}
	}
	if (isset($opts[100]) == 0) {
		$opts[100] = 0;
	}
	/*
	if (!is_array($valores)) {
		$datos = json_decode(str_replace('\"', '"', $valores), true);
	}
	if (isset($datos[0]) == 0) {
		$datos[0] = '';
	}
	if ($datos[0] == '') {
		$sError = $ERR[''];
	}
	*/
	$idTercero = $opts[100];
	if ($sError == '') {
		$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto != '') {
			$objDB->dbPuerto = $APP->dbpuerto;
		}
		$objDB->xajax();
		$bHayDb = true;
		list($sError, $iAccion, $cipa03id, $sDebugGuardar) = f3803_db_Guardar($valores, $objDB, $bDebug, $idTercero);
		$sDebug = $sDebug . $sDebugGuardar;
	}
	$objResponse = new xajaxResponse();
	if ($sError == '') {
		list($sdetalle, $sDebugTabla) = f3803_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugTabla;
		$objResponse->assign('div_f3803detalle', 'innerHTML', $sdetalle);
		//if ($iAccion == 2) {
			//$objResponse->call('cargaridf3803(' . $cipa03id . ')');
			//} else {
			$objResponse->call('limpiaf3803');
			//}
		$objResponse->call("MensajeAlarmaV2('" . $ETI['msg_itemguardado'] . "', 1)");
	} else {
		$objResponse->call("MensajeAlarmaV2('" . $sError . "', 0)");
	}
	if ($bDebug) {
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
	}
	if ($bHayDb) {
		$objDB->CerrarConexion();
	}
	return $objResponse;
}
function f3803_Traer($aParametros)
{
	$_SESSION['u_ultimominuto'] = iminutoavance();
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$sError = '';
	$bHayDb = false;
	$besta = false;
	if (!is_array($aParametros)) {
		$aParametros = json_decode(str_replace('\"', '"', $aParametros), true);
	}
	$paso = $aParametros[0];
	if ($paso == 1) {
		$cipa03idoferta = numeros_validar($aParametros[1]);
		$cipa03idinscrito = numeros_validar($aParametros[2]);
		if (($cipa03idoferta != '') && ($cipa03idinscrito != '')) {
			$besta = true;
		}
	} else {
		$cipa03id = $aParametros[103];
		if ((int)$cipa03id != 0) {
			$besta = true;
		}
	}
	if ($besta) {
		$besta = false;
		$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto != '') {
			$objDB->dbPuerto = $APP->dbpuerto;
		}
		$objDB->xajax();
		$bHayDb = true;
		$sSQLcondi = '';
		if ($paso == 1) {
			$sSQLcondi = $sSQLcondi . 'cipa03idoferta=' . $cipa03idoferta . ' AND cipa03idinscrito=' . $cipa03idinscrito . '';
		} else {
			$sSQLcondi = $sSQLcondi . 'cipa03id=' . $cipa03id . '';
		}
		$sSQL = 'SELECT * FROM cipa03cupos WHERE ' . $sSQLcondi;
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			$besta = true;
		}
	}
	$objResponse = new xajaxResponse();
	if ($besta) {
		$iPiel = iDefinirPiel($APP, 1);
		$cipa03idinscrito_id = (int)$fila['cipa03idinscrito'];
		$cipa03idinscrito_td = $APP->tipo_doc;
		$cipa03idinscrito_doc = '';
		$cipa03idinscrito_nombre = '';
		if ($cipa03idinscrito_id != 0) {
			list($cipa03idinscrito_nombre, $cipa03idinscrito_id, $cipa03idinscrito_td, $cipa03idinscrito_doc) = html_tercero($cipa03idinscrito_td, $cipa03idinscrito_doc, $cipa03idinscrito_id, 0, $objDB);
		}
		$html_cipa03idinscrito_llaves = html_DivTerceroV2('cipa03idinscrito', $cipa03idinscrito_td, $cipa03idinscrito_doc, true, 2, 'Ingrese el documento');
		$objResponse->assign('cipa03idinscrito', 'value', $cipa03idinscrito_id);
		$objResponse->assign('div_cipa03idinscrito_llaves', 'innerHTML', $html_cipa03idinscrito_llaves);
		$objResponse->assign('div_cipa03idinscrito', 'innerHTML', $cipa03idinscrito_nombre);
		$cipa03id_nombre = '';
		$html_cipa03id = html_oculto('cipa03id', $fila['cipa03id'], $cipa03id_nombre);
		$objResponse->assign('div_cipa03id', 'innerHTML', $html_cipa03id);
		$cipa03asistencia_eti = $fila['cipa03asistencia'];
		$html_cipa03asistencia = html_oculto('cipa03asistencia', $fila['cipa03asistencia'], $cipa03asistencia_eti);
		$objResponse->assign('div_cipa03asistencia', 'innerHTML', $html_cipa03asistencia);
		$cipa03jornada_1_eti = $fila['cipa03jornada_1'];
		$html_cipa03jornada_1 = html_oculto('cipa03jornada_1', $fila['cipa03jornada_1'], $cipa03jornada_1_eti);
		$objResponse->assign('div_cipa03jornada_1', 'innerHTML', $html_cipa03jornada_1);
		$cipa03jornada_2_eti = $fila['cipa03jornada_2'];
		$html_cipa03jornada_2 = html_oculto('cipa03jornada_2', $fila['cipa03jornada_2'], $cipa03jornada_2_eti);
		$objResponse->assign('div_cipa03jornada_2', 'innerHTML', $html_cipa03jornada_2);
		$cipa03jornada_3_eti = $fila['cipa03jornada_3'];
		$html_cipa03jornada_3 = html_oculto('cipa03jornada_3', $fila['cipa03jornada_3'], $cipa03jornada_3_eti);
		$objResponse->assign('div_cipa03jornada_3', 'innerHTML', $html_cipa03jornada_3);
		$cipa03jornada_4_eti = $fila['cipa03jornada_4'];
		$html_cipa03jornada_4 = html_oculto('cipa03jornada_4', $fila['cipa03jornada_4'], $cipa03jornada_4_eti);
		$objResponse->assign('div_cipa03jornada_4', 'innerHTML', $html_cipa03jornada_4);
		$cipa03jornada_5_eti = $fila['cipa03jornada_5'];
		$html_cipa03jornada_5 = html_oculto('cipa03jornada_5', $fila['cipa03jornada_5'], $cipa03jornada_5_eti);
		$objResponse->assign('div_cipa03jornada_5', 'innerHTML', $html_cipa03jornada_5);
		$cipa03idmatricula_eti = $fila['cipa03idmatricula'];
		$html_cipa03idmatricula = html_oculto('cipa03idmatricula', $fila['cipa03idmatricula'], $cipa03idmatricula_eti);
		$objResponse->assign('div_cipa03idmatricula', 'innerHTML', $html_cipa03idmatricula);
		$objResponse->assign('cipa03valoracion', 'value', $fila['cipa03valoracion']);
		$objResponse->assign('cipa03retroalimentacion', 'value', $fila['cipa03retroalimentacion']);
		$objResponse->call("MensajeAlarmaV2('', 0)");
		$objResponse->call("verboton('belimina3803', 'block')");
	} else {
		if ($paso == 1) {
		} else {
			$objResponse->call('MensajeAlarmaV2("No se encontro el registro de referencia:' . $cipa03id . '", 0)');
		}
	}
	if ($bHayDb) {
		$objDB->CerrarConexion();
	}
	return $objResponse;
}
function f3803_Eliminar($aParametros)
{
	$_SESSION['u_ultimominuto'] = iminutoavance();
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$sError = '';
	$iTipoError = 0;
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
	if (isset($opts[100]) == 0) {
		$opts[100] = 0;
	}
	$idTercero = $opts[100];
	/*
	if (!is_array($aParametros)) {
		$aParametros = json_decode(str_replace('\"', '"', $aParametros), true);
	}
	*/
	$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto != '') {
		$objDB->dbPuerto = $APP->dbpuerto;
	}
	$objDB->xajax();
	list($sError, $sDebugElimina) = f3803_db_Eliminar($aParametros, $objDB, $bDebug, $idTercero);
	$sDebug = $sDebug . $sDebugElimina;
	$objResponse = new xajaxResponse();
	if ($sError == '') {
		list($sDetalle, $sDebugTabla) = f3803_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugTabla;
		$objResponse->assign('div_f3803detalle', 'innerHTML', $sDetalle);
		$objResponse->call('limpiaf3803');
		$sError = $ETI['msg_itemeliminado'];
		$iTipoError = 1;
	}
	$objResponse->call("MensajeAlarmaV2('" . $sError . "', " . $iTipoError . ")");
	if ($bDebug) {
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
	}
	$objDB->CerrarConexion();
	return $objResponse;
}
function f3803_HtmlTabla($aParametros)
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
	list($sDetalle, $sDebugTabla) = f3803_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_f3803detalle', 'innerHTML', $sDetalle);
	if ($bDebug) {
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
	}
	return $objResponse;
}
function f3803_PintarLlaves($aParametros)
{
	$_SESSION['u_ultimominuto'] = iminutoavance();
	/*
	if (!is_array($aParametros)) {
		$aParametros = json_decode(str_replace('\"', '"', $aParametros), true);
	}
	*/
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$iPiel = iDefinirPiel($APP, 1);
	$cipa03idinscrito = 0;
	$cipa03idinscrito_rs = '';
	$html_cipa03idinscrito_llaves = html_DivTerceroV2('cipa03idinscrito', $APP->tipo_doc, '', false, 2, $ETI['ing_doc']);
	$html_cipa03id = '<input id="cipa03id" name="cipa03id" type="hidden" value="" />';
	$html_cipa03asistencia = html_oculto('cipa03asistencia', '');
	$html_cipa03jornada_1 = html_oculto('cipa03jornada_1', '');
	$html_cipa03jornada_2 = html_oculto('cipa03jornada_2', '');
	$html_cipa03jornada_3 = html_oculto('cipa03jornada_3', '');
	$html_cipa03jornada_4 = html_oculto('cipa03jornada_4', '');
	$html_cipa03jornada_5 = html_oculto('cipa03jornada_5', '');
	$html_cipa03idmatricula = html_oculto('cipa03idmatricula', '');
	$objResponse = new xajaxResponse();
	$objResponse->assign('cipa03idinscrito', 'value', $cipa03idinscrito);
	$objResponse->assign('div_cipa03idinscrito_llaves', 'innerHTML', $html_cipa03idinscrito_llaves);
	$objResponse->assign('div_cipa03idinscrito', 'innerHTML', $cipa03idinscrito_rs);
	$objResponse->assign('div_cipa03id', 'innerHTML', $html_cipa03id);
	$objResponse->assign('div_cipa03asistencia', 'innerHTML', $html_cipa03asistencia);
	$objResponse->assign('div_cipa03jornada_1', 'innerHTML', $html_cipa03jornada_1);
	$objResponse->assign('div_cipa03jornada_2', 'innerHTML', $html_cipa03jornada_2);
	$objResponse->assign('div_cipa03jornada_3', 'innerHTML', $html_cipa03jornada_3);
	$objResponse->assign('div_cipa03jornada_4', 'innerHTML', $html_cipa03jornada_4);
	$objResponse->assign('div_cipa03jornada_5', 'innerHTML', $html_cipa03jornada_5);
	$objResponse->assign('div_cipa03idmatricula', 'innerHTML', $html_cipa03idmatricula);
	return $objResponse;
}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------
?>