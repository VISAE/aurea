<?php
/*
--- © Omar Augusto Bautista Mora - UNAD - 2026 ---
--- omar.bautista@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 3.2.5 miércoles, 16 de septiembre de 2026
*/
/** Archivo lib2964.php.
 * Libreria 2964 visa57actividad.
 * @author Omar Augusto Bautista Mora - omar.bautista@unad.edu.co
 * @date martes, 22 de septiembre de 2026
 */
function f2964_HTMLComboV2_visa64idpersemanal($objDB, $objCombos, $valor)
{
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$iHoy = fecha_DiaMod();
	$objCombos->nuevo('visa64idpersemanal', $valor, true, '{' . $ETI['msg_seleccione'] . '}');
	//$objCombos->iAncho = 450;
	$objCombos->bEsCombobox = true;
	$objCombos->sAccion = 'cambiapagina();';
	$sSQL = 'SELECT TB.visa56id, TB.visa56fechaini, TB.visa56fechafin
	FROM visa56persemanal AS TB
	WHERE TB.visa56id>0 AND TB.visa56estado=1 
	ORDER BY TB.visa56fechaini';
	$tabla = $objDB->ejecutasql($sSQL);
	while ($fila = $objDB->sf($tabla)) {
		$sFechaIni = formato_FechaLargaDesdeNumero($fila['visa56fechaini'], true);
		$sFechaFin = formato_FechaLargaDesdeNumero($fila['visa56fechafin'], true);
		$visa57idpersemanal_nombre = $sFechaIni . ' - ' . $sFechaFin;
		$objCombos->addItem($fila['visa56id'], $visa57idpersemanal_nombre);
	}
	$sSQL = '';
	$res = $objCombos->html($sSQL, $objDB); //, 0, '', 'et', 2957, $sIdioma
	return $res;
}
function f2964_HTMLComboV2_visa64idsistema($objDB, $objCombos, $valor)
{
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$objCombos->nuevo('visa64idsistema', $valor, true, '{' . $ETI['msg_todos'] . '}');
	//$objCombos->iAncho = 450;
	$objCombos->bEsCombobox = true;
	$objCombos->sAccion = 'cambiapagina();';
	$sSQL = 'SELECT TB.visa55id AS id, CONCAT(TB.visa55codigo, " - ", TB.visa55nombre) AS nombre 
	FROM visa55sistema AS TB
	WHERE TB.visa55id>0
	ORDER BY TB.visa55nombre';
	$res = $objCombos->html($sSQL, $objDB); //, 0, '', 'et', 2957, $sIdioma
	return $res;
}
function f2964_HTMLComboV2_visa64idmacroproyecto($objDB, $objCombos, $valor)
{
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$objCombos->nuevo('visa64idmacroproyecto', $valor, true, '{' . $ETI['msg_todos'] . '}');
	//$objCombos->iAncho = 450;
	$objCombos->bEsCombobox = true;
	$objCombos->sAccion = 'carga_combo_visa64idproyecto();';
	$sSQL = 'SELECT TB.plan03id AS id, CONCAT(TB.plan03sigla, " - ", TB.plan03titulo) AS nombre 
	FROM plan03macroproy AS TB
	WHERE TB.plan03id>0
	ORDER BY TB.plan03orden';
	$res = $objCombos->html($sSQL, $objDB); //, 0, '', 'et', 2957, $sIdioma
	return $res;
}
function f2964_HTMLComboV2_visa64idproyecto($objDB, $objCombos, $valor, $vr_visa64idmacroproyecto)
{
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$objCombos->nuevo('visa64idproyecto', $valor, true, '{' . $ETI['msg_todos'] . '}');
	//$objCombos->iAncho = 450;
	$objCombos->bEsCombobox = true;
	$objCombos->sAccion = 'cambiapagina();';
	$sSQL = 'SELECT TB.plan04id AS id, CONCAT("P-", TB.plan04numero, " - ", TB.plan04nombre) AS nombre 
	FROM plan04proyecto AS TB
	WHERE TB.plan04id>0 AND TB.plan04macroproy=' . (int)$vr_visa64idmacroproyecto . '
	ORDER BY TB.plan04numero';
	$res = $objCombos->html($sSQL, $objDB); //, 0, '', 'et', 2957, $sIdioma
	return $res;
}
function f2964_Combovisa64idproyecto($aParametros)
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
	$html_visa64idproyecto = f2964_HTMLComboV2_visa64idproyecto($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_visa64idproyecto', 'innerHTML', $html_visa64idproyecto);
	$objResponse->call('createComboboxById("visa64idproyecto")');
	$objResponse->call('cambiapagina()');
	return $objResponse;
}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------
function f2964_DashboardResultados($aParametros, $objDB, $bDebug) {
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
	$mensajes_2964 = 'lg/lg_2964_' . $sIdioma . '.php';
	if (!file_exists($mensajes_2964)) {
		$mensajes_2964 = 'lg/lg_2964_es.php';
	}
	require $mensajes_2964;
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
	$iNumVariables = 106;
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
	$visa64idpersemanal = numeros_validar($aParametros[103]);
	$visa64idsistema = numeros_validar($aParametros[104]);
	$visa64idmacroproyecto = numeros_validar($aParametros[105]);
	$visa64idproyecto = numeros_validar($aParametros[106]);
	// Consulta principal
	$iTotalProgrm = 0;
	$iTotalEjecut = 0;
	$dPorceEjecut = 0;
	$iTotalEnProc = 0;
	$dPorceEnProc = 0;
	$iTotalReprog = 0;
	$dPorceReprog = 0;
	$iTotalNoejec = 0;
	$dPorceNoejec = 0;
	$iTotalEmerge = 0;
	$iTotalEviden = 0;
	$iTotalReqDec = 0;
	$iFechaActualizaUlt = 0;
	$sGrph1Sistemas = '';
	$sGrph1Programadas = '';
	$sGrph1Ejecutadas = '';
	$sGrph1PorceEjecut = '';
	$sGrph2Totales = '';
	$sGrph3Sistemas = '';
	$sGrph3CumpleMeta = '';
	$sGrph3ColorSistema = '';
	$aSistemas = array();
	$aPerSemanal = array();
	$resTbl1 = '';
	$resTbl2 = '';
	$resTbl3 = '';
	$resTbl4 = '';
	$resTbl5 = '';
	$sSQLadd = '';
	$sSQL = 'SELECT visa56fechaini, visa56fechafin FROM visa56persemanal WHERE visa56id=' . $visa64idpersemanal . '';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$iFechaDia = $fila['visa56fechaini'];
		$iFechaFin = $fila['visa56fechafin'];
		$resTbl5 = $resTbl5 . '<tr>';
		$resTbl5 = $resTbl5 . '<td></td>';
		while ($iFechaDia <= $iFechaFin) {
			$aPerSemanal[$iFechaDia] = 0;
			$sFecha = formato_FechaLargaDesdeNumero($iFechaDia, true);
			$aCamposFecha = explode(' ', $sFecha);
			$sDia = substr(cadena_tildes($aCamposFecha[0]), 0, 3);
			$iDia = $aCamposFecha[1];
			$resTbl5 = $resTbl5 . '<td>' . $sDia . ' ' . $iDia . '</td>';
			$iFechaDia = fecha_NumSumarDias($iFechaDia, 1);
		}
		$resTbl5 = $resTbl5 . '</tr>';
	}
	if ($visa64idsistema != '') {
		$sSQLadd = $sSQLadd . ' AND TB.visa57idsistema=' . $visa64idsistema;
	}
	if ($visa64idmacroproyecto != '') {
		$sSQLadd = $sSQLadd . ' AND T4.plan04macroproy=' . $visa64idmacroproyecto;
	}
	if ($visa64idproyecto != '') {
		$sSQLadd = $sSQLadd . ' AND T55.visa55idproyecto=' . $visa64idproyecto;
	}
	$sSQL = 'SELECT TB.visa57id, TB.visa57titulo, TB.visa57tipoactividad, TB.visa57estado, TB.visa57prioridad, 
	TB.visa57fechaprogini, TB.visa57fechaprogfin, TB.visa57porcavance, TB.visa57fechaactualiza, T55.visa55codigo, 
	T55.visa55nombre, T55.visa55color, CONCAT("P-", T4.plan04numero) AS proyecto, T3.plan03sigla, T11.unad11razonsocial 
	FROM visa57actividad AS TB, visa55sistema AS T55, plan04proyecto AS T4, plan03macroproy AS T3, unad11terceros AS T11
	WHERE TB.visa57idsistema=T55.visa55id AND T55.visa55idproyecto=T4.plan04id AND T4.plan04macroproy=T3.plan03id AND T55.visa55idresponsable=T11.unad11id 
	AND TB.visa57estado>0 AND TB.visa57idpersemanal=' . $visa64idpersemanal . ' ' . $sSQLadd . '
	ORDER BY TB.visa57prioridad, TB.visa57estado';
	$tabla = $objDB->ejecutasql($sSQL);
	while ($fila = $objDB->sf($tabla)) {
		$visa57id = $fila['visa57id'];
		$visa57fechaprogini = $fila['visa57fechaprogini'];
		$visa57titulo = $fila['visa57titulo'];
		$visa57prioridad = $avisa64idprioridad[$fila['visa57prioridad']];
		if (isset($aSistemas[$fila['visa55codigo']]) == 0) {
			$aSistemas[$fila['visa55codigo']] = array(
				'nombre' => $fila['visa55nombre'], 
				'color' => $fila['visa55color'], 
				'proyecto' => $fila['proyecto'], 
				'macroproyecto' => $fila['plan03sigla'], 
				'lider' => $fila['unad11razonsocial'], 
				'actividades' => array(
					'programadas' => 0,
					'ejecutadas' => 0,
					'enproceso' => 0,
					'reprogramadas' => 0,
					'noejecutadas' => 0,
					'emergentes' => 0,
					'fechas' => $aPerSemanal,
					'fechaactualiza' => $fila['visa57fechaactualiza']
					),
				'alertas' => array(),
				'apoyos' => array(),
				'logros' => array());
		}
		$aActividad =& $aSistemas[$fila['visa55codigo']]['actividades'];
		$aAlerta =& $aSistemas[$fila['visa55codigo']]['alertas'];
		$aApoyo =& $aSistemas[$fila['visa55codigo']]['apoyos'];
		$aLogro =& $aSistemas[$fila['visa55codigo']]['logros'];
		$aFechas =& $aSistemas[$fila['visa55codigo']]['actividades']['fechas'];
		switch ($fila['visa57estado']) {
			case 5:
				$aActividad['enproceso']++;
				$iTotalEnProc++;
				break;
			case 7:
				$aActividad['ejecutadas']++;
				$iTotalEjecut++;
				break;
			case 11:
				$aActividad['noejecutadas']++;
				$iTotalNoejec++;
				$sTitulo = mb_strimwidth($visa57titulo, 0, 60, '...');
				$aAlerta[] = array($sTitulo, 11);
				break;
		}
		$sSQL = 'SELECT visa58descripcion FROM visa58resultado WHERE visa58idactividad=' . $visa57id . ' AND visa58cumplimiento IN (3, 5) ORDER BY visa58cumplimiento';
		$tabla1 = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla1) > 0) {
			$fila1 = $objDB->sf($tabla1);
			$sTitulo = mb_strimwidth($fila1['visa58descripcion'], 0, 60, '...');
			$aLogro[] = $sTitulo;
		}
		$sSQL = 'SELECT 1 FROM visa59evidencia WHERE visa59idactividad=' . $visa57id . '';
		$tabla1 = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla1) > 0) {
			$iTotalEviden++;
		}
		$sSQL = 'SELECT visa60fechareproini FROM visa60reprograma WHERE visa60idactividad=' . $visa57id . ' ORDER BY visa60fechareproini DESC';
		$tabla1 = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla1) > 0) {
			$fila1 = $objDB->sf($tabla1);
			$sTitulo = mb_strimwidth($visa57titulo, 0, 40, '...');
			$sNuevaFecha = fecha_desdenumero($fila1['visa60fechareproini']);
			$aAlerta[] = array($sTitulo . '<br>' . $ETI['msg_nuevafecha'] . $sNuevaFecha, 9);
			$aActividad['reprogramadas']++;
			$iTotalReprog++;
		}		
		$sSQL = 'SELECT visa61descripcion FROM visa61dificultad WHERE visa61idactividad=' . $visa57id . ' ORDER BY visa61impacto';
		$tabla1 = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla1) > 0) {
			$fila1 = $objDB->sf($tabla1);
			$sTitulo = mb_strimwidth($fila1['visa61descripcion'], 0, 60, '...');
			$aAlerta[] = array($sTitulo, 5);
		}
		$sSQL = 'SELECT visa63descripcion FROM visa63solicitaapoyo WHERE visa63idactividad=' . $visa57id . '';
		$tabla1 = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla1) > 0) {
			$fila1 = $objDB->sf($tabla1);
			$sTitulo = mb_strimwidth($fila1['visa63descripcion'], 0, 60, '...');
			$aApoyo[] = $sTitulo . '|' . $visa57prioridad;
			$iTotalReqDec++;
		}
		if ($fila['visa57tipoactividad'] == 2) {
			$aActividad['emergentes']++;
			$iTotalEmerge++;
		}
		if (isset($aFechas[$visa57fechaprogini]) == 0) {
			$aFechas[$visa57fechaprogini] = 0;
		}
		$aFechas[$visa57fechaprogini]++;
		$aActividad['programadas']++;
		$iTotalProgrm++;
	}
	if ($iTotalProgrm > 0) {
		$dPorceEjecut = formato_numero(($iTotalEjecut / $iTotalProgrm) * 100);
		$dPorceEnProc = formato_numero(($iTotalEnProc / $iTotalProgrm) * 100);
		$dPorceReprog = formato_numero(($iTotalReprog / $iTotalProgrm) * 100);
		$dPorceNoejec = formato_numero(($iTotalNoejec / $iTotalProgrm) * 100);
	}	
	$resTbl1 = $resTbl1 . '<table>';
	$resTbl1 = $resTbl1 . '<tr>';
	$resTbl1 = $resTbl1 . '<th colspan="2">' . $ETI['visa64idsistema'] . '</th>';
	$resTbl1 = $resTbl1 . '<th>' . $ETI['visa64idlider'] . '</th>';
	$resTbl1 = $resTbl1 . '<th>' . $ETI['msg_programadas'] . '</th>';
	$resTbl1 = $resTbl1 . '<th>' . $ETI['msg_ejecutadas'] . '</th>';
	$resTbl1 = $resTbl1 . '<th>' . $ETI['msg_enproceso'] . '</th>';
	$resTbl1 = $resTbl1 . '<th>' . $ETI['msg_reprograma'] . '</th>';
	$resTbl1 = $resTbl1 . '<th>' . $ETI['msg_noejecuta'] . '</th>';
	$resTbl1 = $resTbl1 . '<th>' . $ETI['msg_emergentes'] . '</th>';
	$resTbl1 = $resTbl1 . '<th>' . $ETI['msg_porceejec'] . '</th>';
	$resTbl1 = $resTbl1 . '</tr>';
	foreach($aSistemas as $sCodigo => $aDatos) {
		$sColor = '#' . sprintf('%06x', $aDatos['color']);
		$sLogo = '<div class="dashboard__circulo_grande" style="--fondo-color:' . $sColor . ';"></div>';
		$sLogoMd = '<div class="dashboard__circulo_mediano" style="--fondo-color:' . $sColor . ';"></div>';
		$sSistema = '<b>' . $sCodigo . '</b><br>' . $aDatos['nombre'];
		$sLider = $aDatos['lider'];
		$sProyecto = $aDatos['proyecto'];
		$iProgramadas = $aDatos['actividades']['programadas'];
		$iEjecutadas = $aDatos['actividades']['ejecutadas'];
		$iEnProceso = $aDatos['actividades']['enproceso'];
		$iReprogramadas = $aDatos['actividades']['reprogramadas'];
		$iNoEjecutadas = $aDatos['actividades']['noejecutadas'];
		$iEmergentes = $aDatos['actividades']['emergentes'];
		$iFechaActualiza = $aDatos['actividades']['fechaactualiza'];
		$aFechas = $aDatos['actividades']['fechas'];
		$iPorceEjecuta = 0;
		if ($iProgramadas > 0) {
			$iPorceEjecuta = formato_numero(($iEjecutadas / $iProgramadas) * 100);
		}
		if ($iFechaActualiza > $iFechaActualizaUlt) {
			$iFechaActualizaUlt = $iFechaActualiza;
		}
		if ($sGrph1Sistemas != '') {
			$sGrph1Sistemas = $sGrph1Sistemas . ', ';
		}
		$sGrph1Sistemas = $sGrph1Sistemas . '"' . $sCodigo . '"';
		if ($sGrph1Programadas != '') {
			$sGrph1Programadas = $sGrph1Programadas . ', ';
		}
		$sGrph1Programadas = $sGrph1Programadas . $iProgramadas;
		if ($sGrph1Ejecutadas != '') {
			$sGrph1Ejecutadas = $sGrph1Ejecutadas . ', ';
		}
		$sGrph1Ejecutadas = $sGrph1Ejecutadas . $iEjecutadas;
		if ($sGrph1PorceEjecut != '') {
			$sGrph1PorceEjecut = $sGrph1PorceEjecut . ', ';
		}
		$sGrph1PorceEjecut = $sGrph1PorceEjecut . $iPorceEjecuta;
		if ($sGrph3Sistemas != '') {
			$sGrph3Sistemas = $sGrph3Sistemas . ', ';
		}
		$sGrph3Sistemas = $sGrph3Sistemas . '"' . $sCodigo . ' (' . $sProyecto . ')"';
		if ($sGrph3CumpleMeta != '') {
			$sGrph3CumpleMeta = $sGrph3CumpleMeta . ', ';
		}
		$sGrph3CumpleMeta = $sGrph3CumpleMeta . rand(0, 100);
		if ($sGrph3ColorSistema != '') {
			$sGrph3ColorSistema = $sGrph3ColorSistema . ', ';
		}
		$sGrph3ColorSistema = $sGrph3ColorSistema . '"' . $sColor . '"';
		$resTbl1 = $resTbl1 . '<tr>';
		$resTbl1 = $resTbl1 . '<td>' . $sLogo . '</td>';
		$resTbl1 = $resTbl1 . '<td>' . $sSistema . '</td>';
		$resTbl1 = $resTbl1 . '<td>' . $sLider . '</td>';
		$resTbl1 = $resTbl1 . '<td align="center">' . $iProgramadas . '</td>';
		$resTbl1 = $resTbl1 . '<td align="center">' . $iEjecutadas . '</td>';
		$resTbl1 = $resTbl1 . '<td align="center">' . $iEnProceso . '</td>';
		$resTbl1 = $resTbl1 . '<td align="center">' . $iReprogramadas . '</td>';
		$resTbl1 = $resTbl1 . '<td align="center">' . $iNoEjecutadas . '</td>';
		$resTbl1 = $resTbl1 . '<td align="center">' . $iEmergentes . '</td>';
		$resTbl1 = $resTbl1 . '<td align="center">' . $iPorceEjecuta . '%<br><progress value="' . $iPorceEjecuta . '" max="100"></progress></td>';
		$resTbl1 = $resTbl1 . '</tr>';

		if (count($aDatos['alertas']) > 0) {
			$aDatosAlerta = $aDatos['alertas'][0];
			$sAlerta = '';
			$sClase = '';
			switch ($aDatosAlerta[1]) {
				case 5:
					$sAlerta = $ETI['msg_enespera'];
					$sClase = 'dashboard__alerta5';
					break;
				case 9:
					$sAlerta = $ETI['msg_reprograma'];
					$sClase = 'dashboard__alerta9';
					break;
				case 11:
					$sAlerta = $ETI['msg_noejecuta'];
					$sClase = 'dashboard__alerta11';
					break;
			}
			$resTbl2 = $resTbl2 . '<tr>';
			$resTbl2 = $resTbl2 . '<td align="center">' . $sLogoMd . '</td>';
			$resTbl2 = $resTbl2 . '<td align="left"><b>' . $sCodigo . '</b><br>' . $aDatosAlerta[0] . '</td>';
			$resTbl2 = $resTbl2 . '<td align="center"><div class="' . $sClase . '">' . $sAlerta . '</div></td>';
			$resTbl2 = $resTbl2 . '</tr>';
		}
		
		if (count($aDatos['apoyos']) > 0) {
			$aDatosApoyo = explode('|', $aDatos['apoyos'][0]);
			$sApoyo = '';
			$sPrioridad = '';
			if (count($aDatosApoyo) == 2) {
				$sApoyo = $aDatosApoyo[0];
				$sPrioridad = $aDatosApoyo[1];
			}
			$resTbl3 = $resTbl3 . '<tr>';
			$resTbl3 = $resTbl3 . '<td><div class="dashboard__etiqueta" style="--fondo-color:' . $sColor . ';">' . $sCodigo . '</div></td>';
			$resTbl3 = $resTbl3 . '<td>' . $sApoyo . '</td>';
			$resTbl3 = $resTbl3 . '<td>' . $ETI['msg_prioridad'] . '<br><b>' . $sPrioridad . '</b></td>';
			$resTbl3 = $resTbl3 . '</tr>';
		}
		
		if (count($aDatos['logros']) > 0) {
			$sLogro = $aDatos['logros'][0];
			$resTbl4 = $resTbl4 . '<tr>';
			$resTbl4 = $resTbl4 . '<td><div class="dashboard__etiqueta" style="--fondo-color:' . $sColor . ';">' . $sCodigo . '</div></td>';
			$resTbl4 = $resTbl4 . '<td>' . $sLogro . '</td>';
			$resTbl4 = $resTbl4 . '</tr>';
		}

		if (count($aFechas) > 0) {
			$resTbl5 = $resTbl5 . '<tr>';
			$resTbl5 = $resTbl5 . '<td><b>' . $sCodigo . '</b></td>';
			foreach ($aFechas as $iFecha => $iCantidad) {
				$resTbl5 = $resTbl5 . '<td>';
				if ($iCantidad > 0) {
					$resTbl5 = $resTbl5 . '<span style="color:' . $sColor . ';">&#9679; ' . $iCantidad . '</span>';
				}
				$resTbl5 = $resTbl5 . '</td>';
			}			
			$resTbl5 = $resTbl5 . '</tr>';
		}
	}
	$resTbl1 = $resTbl1 . '<tr>';
	$resTbl1 = $resTbl1 . '<td colspan="3" align="center"><b>' . $ETI['msg_total'] . '</b></td>';
	$resTbl1 = $resTbl1 . '<td align="center"><b>' . $iTotalProgrm . '</b></td>';
	$resTbl1 = $resTbl1 . '<td align="center"><b>' . $iTotalEjecut . '</b></td>';
	$resTbl1 = $resTbl1 . '<td align="center"><b>' . $iTotalEnProc . '</b></td>';
	$resTbl1 = $resTbl1 . '<td align="center"><b>' . $iTotalReprog . '</b></td>';
	$resTbl1 = $resTbl1 . '<td align="center"><b>' . $iTotalNoejec . '</b></td>';
	$resTbl1 = $resTbl1 . '<td align="center"><b>' . $iTotalEmerge . '</b></td>';
	$resTbl1 = $resTbl1 . '<td align="center"><b>' . $dPorceEjecut . '%</b><br><progress value="' . $dPorceEjecut . '" max="100"></progress></td>';
	$resTbl1 = $resTbl1 . '</tr>';
	$resTbl1 = $resTbl1 . '</table>';
	if ($sGrph1Sistemas != '') {
		$sGrph1Sistemas = $sGrph1Sistemas . ', ';
	}
	$sGrph1Sistemas = $sGrph1Sistemas . '"' . $ETI['msg_visae'] . '"';
	if ($sGrph1Programadas != '') {
		$sGrph1Programadas = $sGrph1Programadas . ', ';
	}
	$sGrph1Programadas = $sGrph1Programadas . $iTotalProgrm;
	if ($sGrph1Ejecutadas != '') {
		$sGrph1Ejecutadas = $sGrph1Ejecutadas . ', ';
	}
	$sGrph1Ejecutadas = $sGrph1Ejecutadas . $iTotalEjecut;
	if ($sGrph1PorceEjecut != '') {
		$sGrph1PorceEjecut = $sGrph1PorceEjecut . ', ';
	}
	$sGrph1PorceEjecut = $sGrph1PorceEjecut . $dPorceEjecut;
	$sGrph2Totales = $sGrph2Totales . $iTotalEjecut . ', ' . $iTotalEnProc . ', ' . $iTotalReprog . ', ' . $iTotalNoejec;
	$sFechaActualizaUlt = $ETI['msg_ultactualiza'] . '<br><b>' . formato_FechaLargaDesdeNumero($iFechaActualizaUlt, true) . '</b>';
	$res = '';
	// Divs Totales
	$res = $res . '<div class="dashboard__datos">';
	$res = $res . '<div class="dashboard__dato">';
	$res = $res . '<div class="container--lower">';
	$res = $res . '<h2>' . $ETI['msg_actividades'] . ' ' . $ETI['msg_programadas'] . '</h2>';
	$res = $res . '</div>';
	$res = $res . '<div class="container--lower">';
	$res = $res . '<strong>' . $iTotalProgrm . '</strong>';
	$res = $res . '<p>100% del total</p>';
	$res = $res . '</div>';
	$res = $res . '</div>';
	$res = $res . '<div class="dashboard__dato">';
	$res = $res . '<div class="container--lower">';
	$res = $res . '<h2>' . $ETI['msg_actividades'] . ' ' . $ETI['msg_ejecutadas'] . '</h2>';
	$res = $res . '</div>';
	$res = $res . '<div class="container--lower">';
	$res = $res . '<strong>' . $iTotalEjecut . '</strong>';
	$res = $res . '<p>' . $dPorceEjecut . '% del total</p>';
	$res = $res . '</div>';
	$res = $res . '</div>';
	$res = $res . '<div class="dashboard__dato">';
	$res = $res . '<div class="container--lower">';
	$res = $res . '<h2>' . $ETI['msg_actividades'] . ' ' . $ETI['msg_enproceso'] . '</h2>';
	$res = $res . '</div>';
	$res = $res . '<div class="container--lower">';
	$res = $res . '<strong>' . $iTotalEnProc . '</strong>';
	$res = $res . '<p>' . $dPorceEnProc . '% del total</p>';
	$res = $res . '</div>';
	$res = $res . '</div>';
	$res = $res . '<div class="dashboard__dato">';
	$res = $res . '<div class="container--lower">';
	$res = $res . '<h2>' . $ETI['msg_actividades'] . ' ' . $ETI['msg_reprograma'] . '</h2>';
	$res = $res . '</div>';
	$res = $res . '<div class="container--lower">';
	$res = $res . '<strong>' . $iTotalReprog . '</strong>';
	$res = $res . '<p>' . $dPorceReprog . '% del total</p>';
	$res = $res . '</div>';
	$res = $res . '</div>';
	$res = $res . '<div class="dashboard__dato">';
	$res = $res . '<div class="container--lower">';
	$res = $res . '<h2>' . $ETI['msg_noejecuta'] . '</h2>';
	$res = $res . '</div>';
	$res = $res . '<div class="container--lower">';
	$res = $res . '<strong>' . $iTotalNoejec . '</strong>';
	$res = $res . '<p>' . $dPorceNoejec . '% del total</p>';
	$res = $res . '</div>';
	$res = $res . '</div>';
	$res = $res . '<div class="dashboard__dato">';
	$res = $res . '<div class="container--lower">';
	$res = $res . '<h2>' . $ETI['msg_actividades'] . ' ' . $ETI['msg_emergentes'] . '</h2>';
	$res = $res . '</div>';
	$res = $res . '<div class="container--lower">';
	$res = $res . '<strong>' . $iTotalEmerge . '</strong>';
	$res = $res . '<p>Esta semana</p>';
	$res = $res . '</div>';
	$res = $res . '</div>';
	$res = $res . '<div class="dashboard__dato">';
	$res = $res . '<div class="container--lower">';
	$res = $res . '<h2>' . $ETI['msg_evidencias'] . '</h2>';
	$res = $res . '</div>';
	$res = $res . '<div class="container--lower">';
	$res = $res . '<strong>' . $iTotalEviden . '</strong>';
	$res = $res . '<p>Total semana</p>';
	$res = $res . '</div>';
	$res = $res . '</div>';
	$res = $res . '<div class="dashboard__dato">';
	$res = $res . '<div class="container--lower">';
	$res = $res . '<h2>' . $ETI['msg_reqdecision'] . '</h2>';
	$res = $res . '</div>';
	$res = $res . '<div class="container--lower">';
	$res = $res . '<strong>' . $iTotalReqDec . '</strong>';
	$res = $res . '<p>Ver detalles</p>';
	$res = $res . '</div>';
	$res = $res . '</div>';
	$res = $res . '</div>';
	$res = $res . '<div class=""dashboard__title"">' . html_Alerta($sFechaActualizaUlt) . '</div>';
	$res = $res . '<div class="dashboard__table">';
	$res = $res . '<fieldset>';
	$res = $res . '<legend>' . $ETI['msg_ejecsistema'] . '</legend>';
	$res = $res . $resTbl1;
	$res = $res . '</fieldset>';
	$res = $res . '</div>';
	
	$res = $res . '<div class="dashboard__charts">';
	$res = $res . '<div class="dashboard__bar">';
	$res = $res . '<h4>' . $ETI['msg_progvsejec'] . '</h4>';
	$res = $res . '<div>';
	$res = $res . '<canvas id="bar-1"></canvas>';
	$res = $res . '</div>';
	$res = $res . '</div>';
	$res = $res . '<div class="dashboard__bar">';
	$res = $res . '<h4>' . $ETI['msg_estadoactiv'] . '</h4>';
	$res = $res . '<div>';
	$res = $res . '<canvas id="pie-1"></canvas>';
	$res = $res . '</div>';
	$res = $res . '</div>';
	$res = $res . '<div class="dashboard__bar">';
	$res = $res . '<h4>' . $ETI['msg_cumplemetas'] . '</h4>';
	$res = $res . '<div>';
	$res = $res . '<canvas id="bar-2"></canvas>';
	$res = $res . '</div>';
	$res = $res . '</div>';
	$res = $res . '</div>';
	
	$res = $res . '<script>';
	$res = $res . 'graficobarrasvf2964([' . $sGrph1Sistemas . '], [' . $sGrph1Programadas . '], [' . $sGrph1Ejecutadas . '], [' . $sGrph1PorceEjecut . ']);';
	$res = $res . 'graficodonaf2964("' . $iTotalProgrm . '", [' . $sGrph2Totales . ']);';
	$res = $res . 'graficobarrashf2964([' . $sGrph3Sistemas . '], [' . $sGrph3CumpleMeta . '], [' . $sGrph3ColorSistema . ']);';
	$res = $res . '</script>';

	$res = $res . '<div class="dashboard__datos">';
	$res = $res . '<div class="dashboard__dato">';
	$res = $res . '<div class="container--lower">';
	$res = $res . '<h2>' . $ETI['msg_alertas'] . '</h2>';
	$res = $res . '</div>';
	$res = $res . '<div class="container--lower">';
	$res = $res . '<table>';
	$res = $res . '<tbody>';
	$res = $res . $resTbl2;
	$res = $res . '</tbody>';
	$res = $res . '</table>';
	$res = $res . '</div>';
	$res = $res . '</div>';
	$res = $res . '<div class="dashboard__dato">';
	$res = $res . '<div class="container--lower">';
	$res = $res . '<h2>' . $ETI['msg_decisiones'] . '</h2>';
	$res = $res . '</div>';
	$res = $res . '<div class="container--lower">';
	$res = $res . '<table>';
	$res = $res . '<tbody>';
	$res = $res . $resTbl3;
	$res = $res . '</tbody>';
	$res = $res . '</table>';
	$res = $res . '</div>';
	$res = $res . '</div>';
	$res = $res . '</div>';

	$res = $res . '<div class="dashboard__datos">';
	$res = $res . '<div class="dashboard__dato">';
	$res = $res . '<div class="container--lower">';
	$res = $res . '<h2>' . $ETI['msg_logros'] . '</h2>';
	$res = $res . '</div>';
	$res = $res . '<div class="container--lower">';
	$res = $res . '<table>';
	$res = $res . '<tbody>';
	$res = $res . $resTbl4;
	$res = $res . '</tbody>';
	$res = $res . '</table>';
	$res = $res . '</div>';
	$res = $res . '</div>';
	$res = $res . '<div class="dashboard__dato">';
	$res = $res . '<div class="container--lower">';
	$res = $res . '<h2>' . $ETI['msg_calendario'] . '</h2>';
	$res = $res . '</div>';
	$res = $res . '<div class="container--lower">';
	$res = $res . '<table>';
	$res = $res . '<tbody>';
	$res = $res . $resTbl5;
	$res = $res . '</tbody>';
	$res = $res . '</table>';
	$res = $res . '</div>';
	$res = $res . '</div>';
	$res = $res . '</div>';

	
	
	return array(cadena_codificar($res), $sDebug);
}
