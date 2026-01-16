<?php
/*
--- © Omar Augusto Bautista Mora - UNAD - 2025
--- omar.bautista@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 1.0 lunes, 24 de noviembre de 2025
--- 3076 Anotaciones FAV
*/
function f3076_db_Guardar($iContenedor, $valores, $objDB, $bDebug = false)
{
	$iCodModulo = 3076;
	$bAudita[2] = false;
	$bAudita[3] = false;
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_3076 = $APP->rutacomun . 'lg/lg_3076_' . $sIdioma . '.php';
	if (!file_exists($mensajes_3076)) {
		$mensajes_3076 = $APP->rutacomun . 'lg/lg_3076_es.php';
	}
	require $mensajes_todas;
	require $mensajes_3076;
	$sError = '';
	$sDebug = '';
	$bInserta = false;
	$iAccion = 3;
	if (!is_array($valores)) {
		$valores = json_decode(str_replace('\"', '"', $valores), true);
	}
	$saiu76idsolicitud = numeros_validar($valores[1]);
	$saiu76consec = numeros_validar($valores[2]);
	$saiu76id = numeros_validar($valores[3], true);
	$saiu76anotacion = cadena_Validar(trim($valores[4]));
	$saiu76visible = numeros_validar($valores[5]);
	$saiu76idusuario = numeros_validar($valores[9]);
	$saiu76fecha = numeros_validar($valores[10]);
	$saiu76hora = numeros_validar($valores[11]);
	$saiu76minuto = numeros_validar($valores[12]);
	//if ($saiu76hora==''){$saiu76hora=0;}
	//if ($saiu76minuto==''){$saiu76minuto=0;}
	$sSepara = ', ';
	if ($saiu76idusuario == 0) {
		$sError = $ERR['saiu76idusuario'] . $sSepara . $sError;
	}
	if ($saiu76visible == '') {
		$sError = $ERR['saiu76visible'] . $sSepara . $sError;
	}
	if ($saiu76anotacion == '') {
		$sError = $ERR['saiu76anotacion'] . $sSepara . $sError;
	}
	//if ($saiu76id==''){$sError=$ERR['saiu76id'].$sSepara.$sError;}//CONSECUTIVO
	//if ($saiu76consec==''){$sError=$ERR['saiu76consec'].$sSepara.$sError;}//CONSECUTIVO
	if ($saiu76idsolicitud == '') {
		$sError = $ERR['saiu76idsolicitud'] . $sSepara . $sError;
	}
	if ($sError == '') {
		if ((int)$saiu76id != 0) {
			if ($saiu76idusuario != $_SESSION['unad_id_tercero']) {
				$sError = $ERR['noeditar'];
			}
		}
	}
	if ($sError == '') {
		list($sError, $sInfo) = tercero_Bloqueado($saiu76idusuario, $objDB);
		if ($sInfo != '') {
			$sError = $sError . '<br>' . $sInfo;
		}
	}
	if ($sError == '') {
		$sTabla76 = 'saiu76anotaciones_' . $iContenedor;
		$bPermiso = false;
		$sSQL='SELECT bita27id FROM bita27equipotrabajo WHERE bita27activo=1 AND bita27idlider=' . $saiu76idusuario . '';
		$tabla= $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0) {
			$bPermiso = true;
		} else {
			$sSQL='SELECT bita28idequipotrab FROM bita28eqipoparte WHERE bita28activo="S" AND bita28idtercero=' . $saiu76idusuario . '';
			$tabla= $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla)>0) {
				$bPermiso = true;
			}
		}
		if ((int)$saiu76id == 0) {
			if ((int)$saiu76consec == 0) {
				$saiu76consec = tabla_consecutivo($sTabla76, 'saiu76consec', 'saiu76idsolicitud=' . $saiu76idsolicitud . '', $objDB);
				if ($saiu76consec == -1) {
					$sError = $objDB->serror;
				}
			} else {
				if (!seg_revisa_permiso($iCodModulo, 8, $objDB)) {
					$sError = $ERR['8'];
				}
			}
			if ($sError == '') {
				$sSQL = 'SELECT saiu76idsolicitud FROM ' . $sTabla76 . ' WHERE saiu76idsolicitud=' . $saiu76idsolicitud . ' AND saiu76consec=' . $saiu76consec . '';
				$result = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($result) != 0) {
					$sError = $ERR['existe'];
				} else {
					if (seg_revisa_permiso($iCodModulo, 2, $objDB)) {
						$bPermiso = true;
					}
					if (!$bPermiso) {
						$sError = $ERR['2'];
					}
				}
			}
			if ($sError == '') {
				$saiu76id = tabla_consecutivo($sTabla76, 'saiu76id', '', $objDB);
				if ($saiu76id == -1) {
					$sError = $objDB->serror;
				}
				$bInserta = true;
				$iAccion = 2;
			}
		} else {
			if (seg_revisa_permiso($iCodModulo, 3, $objDB)) {
				$bPermiso = true;
			}
			if (!$bPermiso) {
				$sError = $ERR['3'];
			}
		}
	}
	if ($sError == '') {
		if ($bInserta) {
			$saiu76idorigen = 0;
			$saiu76idarchivo = 0;
			$saiu76fecha = fecha_DiaMod();
			$saiu76hora = fecha_hora();
			$saiu76minuto = fecha_minuto();
		}
	}
	if ($sError == '') {
		//Si el campo saiu76anotacion permite html quite la linea cadena_Validar para el campo y habilite la siguiente linea:
		//$saiu76anotacion=str_replace('"', '\"', $saiu76anotacion);
		$saiu76anotacion = str_replace('"', '\"', $saiu76anotacion);
		if ($bInserta) {
			$sCampos3076 = 'saiu76idsolicitud, saiu76consec, saiu76id, saiu76anotacion, saiu76visible, 
			saiu76idorigen, saiu76idarchivo, saiu76idusuario, saiu76fecha, saiu76hora, 
			saiu76minuto';
			$sValores3076 = '' . $saiu76idsolicitud . ', ' . $saiu76consec . ', ' . $saiu76id . ', "' . $saiu76anotacion . '", "' . $saiu76visible . '", 
			0, 0, "' . $saiu76idusuario . '", "' . $saiu76fecha . '", ' . $saiu76hora . ', 
			' . $saiu76minuto . '';
			if ($APP->utf8 == 1) {
				$sSQL = 'INSERT INTO ' . $sTabla76 . ' (' . $sCampos3076 . ') VALUES (' . cadena_codificar($sValores3076) . ');';
			} else {
				$sSQL = 'INSERT INTO ' . $sTabla76 . ' (' . $sCampos3076 . ') VALUES (' . $sValores3076 . ');';
			}
			$result = $objDB->ejecutasql($sSQL);
			if ($result == false) {
				$sError = $ERR['falla_guardar'] . ' {Anotaciones}.<!-- ' . $sSQL . ' -->';
			} else {
				if ($bAudita[2]) {
					seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 2, $saiu76id, $sSQL, $objDB);
				}
			}
		} else {
			$scampo3076[1] = 'saiu76anotacion';
			$scampo3076[2] = 'saiu76visible';
			$scampo3076[3] = 'saiu76idusuario';
			$scampo3076[4] = 'saiu76fecha';
			$svr3076[1] = $saiu76anotacion;
			$svr3076[2] = $saiu76visible;
			$svr3076[3] = $saiu76idusuario;
			$svr3076[4] = $saiu76fecha;
			$inumcampos = 4;
			$sWhere = 'saiu76id=' . $saiu76id . '';
			//$sWhere='saiu76idsolicitud='.$saiu76idsolicitud.' AND saiu76consec='.$saiu76consec.'';
			$sSQL = 'SELECT * FROM ' . $sTabla76 . ' WHERE ' . $sWhere;
			$sdatos = '';
			$bpasa = false;
			$result = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($result) > 0) {
				$filaorigen = $objDB->sf($result);
				for ($k = 1; $k <= $inumcampos; $k++) {
					if ($filaorigen[$scampo3076[$k]] != $svr3076[$k]) {
						if ($sdatos != '') {
							$sdatos = $sdatos . ', ';
						}
						$sdatos = $sdatos . $scampo3076[$k] . '="' . $svr3076[$k] . '"';
						$bpasa = true;
					}
				}
			}
			if ($bpasa) {
				if ($APP->utf8 == 1) {
					$sSQL = 'UPDATE ' . $sTabla76 . ' SET ' . cadena_codificar($sdatos) . ' WHERE ' . $sWhere . ';';
				} else {
					$sSQL = 'UPDATE ' . $sTabla76 . ' SET ' . $sdatos . ' WHERE ' . $sWhere . ';';
				}
				$result = $objDB->ejecutasql($sSQL);
				if ($result == false) {
					$sError = $ERR['falla_guardar'] . ' {Anotaciones}. <!-- ' . $sSQL . ' -->';
				} else {
					if ($bAudita[3]) {
						seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 3, $saiu76id, $sSQL, $objDB);
					}
				}
			}
		}
	}
	return array($sError, $iAccion, $saiu76id, $sDebug);
}
function f3076_db_Eliminar($aParametros, $objDB, $bDebug = false)
{
	$iCodModulo = 3076;
	$bAudita[4] = false;
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_3076 = $APP->rutacomun . 'lg/lg_3076_' . $sIdioma . '.php';
	if (!file_exists($mensajes_3076)) {
		$mensajes_3076 = $APP->rutacomun . 'lg/lg_3076_es.php';
	}
	require $mensajes_todas;
	require $mensajes_3076;
	$sError = '';
	$sDebug = '';
	if (!is_array($aParametros)) {
		$aParametros = json_decode(str_replace('\"', '"', $aParametros), true);
	}
	$saiu76idsolicitud = numeros_validar($aParametros[1]);
	$saiu76consec = numeros_validar($aParametros[2]);
	$saiu76id = numeros_validar($aParametros[3]);
	$saiu76idusuario = numeros_validar($aParametros[9]);
	$saiu73agno = numeros_validar($aParametros[97]);
	$saiu73mes = numeros_validar($aParametros[98]);
	$sTabla76 = 'saiu76anotaciones_' . $saiu73agno;
	if ($sError == '') {
		if ($saiu76idusuario != $_SESSION['unad_id_tercero']) {
			$sError = $ERR['noeliminar'];
		}
	}
	if ($sError == '') {
		if (!seg_revisa_permiso($iCodModulo, 4, $objDB)) {
			$sError = $ERR['4'];
		}
	}
	if ($sError == '') {
		$sSQL = 'SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=3076';
		$tablaor = $objDB->ejecutasql($sSQL);
		while ($filaor = $objDB->sf($tablaor)) {
			$sSQL = 'SELECT ' . $filaor['campoid'] . ' FROM ' . $filaor['tabla'] . ' WHERE ' . $filaor['camporev'] . '=' . $saiu76id . ' LIMIT 0, 1';
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
		$sWhere = 'saiu76id=' . $saiu76id . '';
		//$sWhere='saiu76idsolicitud='.$saiu76idsolicitud.' AND saiu76consec='.$saiu76consec.'';
		$sSQL = 'DELETE FROM ' . $sTabla76 . ' WHERE ' . $sWhere . ';';
		$result = $objDB->ejecutasql($sSQL);
		if ($result == false) {
			$sError = $ERR['falla_eliminar'] . ' {3076 Anotaciones}.<!-- ' . $sSQL . ' -->';
		} else {
			if ($bAudita[4]) {
				seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $saiu76id, $sSQL, $objDB);
			}
		}
	}
	return array($sError, $sDebug);
}
function f3076_TablaDetalleV2($aParametros, $objDB, $bDebug = false)
{
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_3076 = $APP->rutacomun . 'lg/lg_3076_' . $sIdioma . '.php';
	if (!file_exists($mensajes_3076)) {
		$mensajes_3076 = $APP->rutacomun . 'lg/lg_3076_es.php';
	}
	require $mensajes_todas;
	require $mensajes_3076;
	if (!is_array($aParametros)) {
		$aParametros = json_decode(str_replace('\"', '"', $aParametros), true);
	}
	if (isset($aParametros[0]) == 0) {
		$aParametros[0] = -1;
	}
	if (isset($aParametros[97]) == 0) {
		$aParametros[97] = fecha_agno();
	}
	if (isset($aParametros[98]) == 0) {
		$aParametros[98] = fecha_mes();
	}
	if (isset($aParametros[99]) == 0) {
		$aParametros[99] = false;
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
	//if (isset($aParametros[103])==0){$aParametros[103]='';}
	//if (isset($aParametros[104])==0){$aParametros[104]='';}
	//$aParametros[103]=numeros_validar($aParametros[103]);
	$aParametros[0] = numeros_validar($aParametros[0]);
	if ($aParametros[0] == '') {
		$aParametros[0] = -1;
	}
	$sDebug = '';
	$saiu73id = $aParametros[0];
	$saiu73agno = numeros_validar($aParametros[97]);
	$saiu73mes = numeros_validar($aParametros[98]);
	$sTabla76 = 'saiu76anotaciones_' . $saiu73agno;
	$bVistaUsr = $aParametros[99];
	$idTercero = $aParametros[100];
	$pagina = $aParametros[101];
	$lineastabla = $aParametros[102];
	$bAbierta = true;
	$sSQLadd = '';
	$sSQLadd1 = '';
	$sLeyenda = '';
	if ($sLeyenda != '') {
		$sLeyenda = '<div class="salto1px"></div>
<div class="GrupoCamposAyuda">
' . $sLeyenda . '
<div class="salto1px"></div>
</div>';
	}
	/*
	$aEstado=array();
	$sSQL='SELECT id, nombre FROM tabla';
	$tabla=$objDB->ejecutasql($sSQL);
	while($fila=$objDB->sf($tabla)){
		$aEstado[$fila['id']]=cadena_notildes($fila['nombre']);
		}
	*/
	//if ((int)$aParametros[103]!=-1){$sSQLadd=$sSQLadd.' AND TB.campo='.$aParametros[103];}
	//if ($aParametros[103]!=''){$sSQLadd=$sSQLadd.' AND TB.campo2 LIKE "%'.$aParametros[103].'%"';}
	if ($bVistaUsr){$sSQLadd=$sSQLadd.' AND TB.saiu76visible = 1 ' . '';}
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
	$sTitulos = 'Solicitud, Consec, Id, Anotacion, Visible, Descartada, Origen, Archivo, Usuario, Fecha, Hora, Minuto';
	$sSQL = 'SELECT TB.saiu76idsolicitud, TB.saiu76consec, TB.saiu76id, TB.saiu76anotacion, TB.saiu76visible, TB.saiu76idorigen, TB.saiu76idarchivo, T9.unad11razonsocial AS C9_nombre, TB.saiu76fecha, TB.saiu76hora, TB.saiu76minuto, TB.saiu76idusuario, T9.unad11tipodoc AS C9_td, T9.unad11doc AS C9_doc 
	FROM ' . $sTabla76 . ' AS TB, unad11terceros AS T9 
	WHERE ' . $sSQLadd1 . ' TB.saiu76idsolicitud=' . $saiu73id . ' AND TB.saiu76idusuario=T9.unad11id ' . $sSQLadd . '
	ORDER BY TB.saiu76consec';
	$sSQLlista = str_replace("'", "|", $sSQL);
	$sSQLlista = str_replace('"', "|", $sSQLlista);
	$sErrConsulta = '<input id="consulta_3076" name="consulta_3076" type="hidden" value="' . $sSQLlista . '"/>
	<input id="titulos_3076" name="titulos_3076" type="hidden" value="' . $sTitulos . '"/>';
	$tabladetalle = $objDB->ejecutasql($sSQL);
	if ($tabladetalle == false) {
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' Consulta 3076: ' . $sSQL . '<br>';
		}
		$registros = 0;
		$sErrConsulta = $sErrConsulta . '..<input id="err" name="err" type="hidden" value="' . $sSQL . ' ' . $objDB->serror . '"/>';
		//$sLeyenda=$sSQL;
	} else {
		$registros = $objDB->nf($tabladetalle);
		if ($registros == 0) {
			return array(cadena_codificar($sErrConsulta . '<input id="paginaf3076" name="paginaf3076" type="hidden" value="' . $pagina . '"/><input id="lppf3076" name="lppf3076" type="hidden" value="' . $lineastabla . '"/>'), $sDebug);
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
	<td><b>' . $ETI['saiu76consec'] . '</b></td>';
	if ($bVistaUsr) {
		$res = $res . '<td><b>' . $ETI['saiu76anotacion'] . '</b></td>';
	} else {
		$res = $res . '<td colspan="2"><b>' . $ETI['saiu76idusuario'] . '</b></td>';
	}
	$res = $res . '<td><b>' . $ETI['saiu76fecha'] . '</b></td>
	<td><b>' . $ETI['saiu76hora'] . '</b></td>
	<td align="right">
	' . html_paginador('paginaf3076', $registros, $lineastabla, $pagina, 'paginarf3076()') . '
	' . html_lpp('lppf3076', $lineastabla, 'paginarf3076()') . '
	</td>
	</tr>';
	$tlinea = 1;
	while ($filadet = $objDB->sf($tabladetalle)) {
		$sPrefijo = '';
		$sSufijo = '';
		$sClass = '';
		$sLink = '';
		if (false) {
			$sPrefijo = '<b>';
			$sSufijo = '</b>';
		}
		if (($tlinea % 2) == 0) {
			$sClass = ' class="resaltetabla"';
		}
		$tlinea++;
		$et_saiu76consec = $sPrefijo . $filadet['saiu76consec'] . $sSufijo;
		$et_saiu76anotacion = $sPrefijo . cadena_notildes($filadet['saiu76anotacion']) . $sSufijo;
		$et_saiu76visible = $sPrefijo . $ETI['no'] . $sSufijo;
		if ($filadet['saiu76visible'] == 1) {
			$et_saiu76visible = $sPrefijo . $ETI['si'] . $sSufijo;
		}
		$et_saiu76idarchivo = '';
		if ($filadet['saiu76idarchivo'] != 0) {
			//$et_saiu76idarchivo='<img src="verarchivo.php?cont='.$filadet['saiu76idorigen'].'&id='.$filadet['saiu76idarchivo'].'&maxx=150"/>';
			$et_saiu76idarchivo = html_lnkarchivo((int)$filadet['saiu76idorigen'], (int)$filadet['saiu76idarchivo']);
		}
		$et_saiu76idusuario = $sPrefijo . $filadet['saiu76idusuario'] . $sSufijo;
		$et_saiu76fecha = '';
		if ($filadet['saiu76fecha'] != 0) {
			$et_saiu76fecha = $sPrefijo . fecha_desdenumero($filadet['saiu76fecha']) . $sSufijo;
		}
		$et_saiu76hora = html_TablaHoraMin($filadet['saiu76hora'], $filadet['saiu76minuto']);
		$et_saiu76minuto = $sPrefijo . $filadet['saiu76minuto'] . $sSufijo;
		if ($bAbierta) {
			$lnk = $ETI['lnk_cargar'];
			if ($bVistaUsr) {
				$lnk = $ETI['lnk_consultar'];
			}
			$sLink = '<a href="javascript:cargaridf3076(' . $filadet['saiu76id'] . ')" class="lnkresalte">' . $lnk . '</a>';
		}
		$res = $res . '<tr' . $sClass . '>
		<td>' . $et_saiu76consec . '</td>';
		if ($bVistaUsr) {
			$res = $res . '<td>' . mb_strimwidth($et_saiu76anotacion, 0, 20, '...') . '</td>';
		} else {
			$res = $res . '<td>' . $sPrefijo . $filadet['C9_td'] . ' ' . $filadet['C9_doc'] . $sSufijo . '</td>
			<td>' . $sPrefijo . cadena_notildes($filadet['C9_nombre']) . $sSufijo . '</td>';
		}
		$res = $res . '<td>' . $et_saiu76fecha . '</td>
		<td>' . $et_saiu76hora . '</td>
		<td>' . $sLink . '</td>
		</tr>';
	}
	$res = $res . '</table>';
	$objDB->liberar($tabladetalle);
	return array(cadena_codificar($res), $sDebug);
}
function f3076_Clonar($iContenedor, $saiu76idsolicitud, $saiu76idsolicitudPadre, $objDB)
{
	$sError = '';
	$sTabla76 = 'saiu76anotaciones_' . $iContenedor;
	$saiu76consec = tabla_consecutivo($sTabla76, 'saiu76consec', 'saiu76idsolicitud=' . $saiu76idsolicitud . '', $objDB);
	if ($saiu76consec == -1) {
		$sError = $objDB->serror;
	}
	$saiu76id = tabla_consecutivo($sTabla76, 'saiu76id', '', $objDB);
	if ($saiu76id == -1) {
		$sError = $objDB->serror;
	}
	if ($sError == '') {
		$sCampos3076 = 'saiu76idsolicitud, saiu76consec, saiu76id, saiu76anotacion, saiu76visible, saiu76idorigen, saiu76idarchivo, saiu76idusuario, saiu76fecha, saiu76hora, saiu76minuto';
		$sValores3076 = '';
		$sSQL = 'SELECT * FROM ' . $sTabla76 . ' WHERE saiu76idsolicitud=' . $saiu76idsolicitudPadre . '';
		$tabla = $objDB->ejecutasql($sSQL);
		while ($fila = $objDB->sf($tabla)) {
			if ($sValores3076 != '') {
				$sValores3076 = $sValores3076 . ', ';
			}
			$sValores3076 = $sValores3076 . '(' . $saiu76idsolicitud . ', ' . $saiu76consec . ', ' . $saiu76id . ', "' . $fila['saiu76anotacion'] . '", "' . $fila['saiu76visible'] . '", "' . $fila['saiu76idorigen'] . '", "' . $fila['saiu76idarchivo'] . '", ' . $fila['saiu76idusuario'] . ', "' . $fila['saiu76fecha'] . '", ' . $fila['saiu76hora'] . ', ' . $fila['saiu76minuto'] . ')';
			$saiu76consec++;
			$saiu76id++;
		}
		if ($sValores3076 != '') {
			$sSQL = 'INSERT INTO ' . $sTabla76 . '(' . $sCampos3076 . ') VALUES ' . $sValores3076 . '';
			$result = $objDB->ejecutasql($sSQL);
		}
	}
	return $sError;
}
// -- 3076 Anotaciones XAJAX 
function elimina_archivo_saiu76idarchivo($idpadre, $iAgno, $iMes)
{
	$_SESSION['u_ultimominuto'] = iminutoavance();
	require './app.php';
	$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto != '') {
		$objDB->dbPuerto = $APP->dbpuerto;
	}
	$objDB->xajax();
	$sTabla76 = 'saiu76anotaciones_' . $iAgno;
	archivo_eliminar($sTabla76, 'saiu76id', 'saiu76idorigen', 'saiu76idarchivo', $idpadre, $objDB);
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->call("limpia_saiu76idarchivo");
	return $objResponse;
}
function f3076_Guardar($valores, $aParametros)
{
	$_SESSION['u_ultimominuto'] = iminutoavance();
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
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
	//if(!is_array($valores)){$datos=json_decode(str_replace('\"','"',$valores),true);}
	//if (isset($datos[0])==0){$datos[0]='';}
	//if ($datos[0]==''){$sError=$ERR[''];}
	if ($sError == '') {
		$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto != '') {
			$objDB->dbPuerto = $APP->dbpuerto;
		}
		$objDB->xajax();
		$bHayDb = true;
		$iContenedor = numeros_validar($aParametros[97]);
		list($sError, $iAccion, $saiu76id, $sDebugGuardar) = f3076_db_Guardar($iContenedor, $valores, $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugGuardar;
	}
	$objResponse = new xajaxResponse();
	if ($sError == '') {
		list($sdetalle, $sDebugTabla) = f3076_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugTabla;
		$objResponse->assign('div_f3076detalle', 'innerHTML', $sdetalle);
		//if ($iAccion==2){
		//$objResponse->call('cargaridf3076('.$saiu76id.')');
		//}else{
		//$objResponse->call('limpiaf3076');
		$objResponse->call('cargaridf3076(' . $saiu76id . ')');
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
function f3076_Traer($aParametros)
{
	$_SESSION['u_ultimominuto'] = iminutoavance();
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
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
		$saiu76idsolicitud = numeros_validar($aParametros[1]);
		$saiu76consec = numeros_validar($aParametros[2]);
		if (($saiu76idsolicitud != '') && ($saiu76consec != '')) {
			$besta = true;
		}
	} else {
		$saiu76id = $aParametros[103];
		if ((int)$saiu76id != 0) {
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
		$iContenedor = numeros_validar($aParametros[97]);
		$sTabla76 = 'saiu76anotaciones_' . $iContenedor;
		$sSQLcondi = '';
		if ($paso == 1) {
			$sSQLcondi = $sSQLcondi . 'saiu76idsolicitud=' . $saiu76idsolicitud . ' AND saiu76consec=' . $saiu76consec . '';
		} else {
			$sSQLcondi = $sSQLcondi . 'saiu76id=' . $saiu76id . '';
		}
		$sSQL = 'SELECT * FROM ' . $sTabla76 . ' WHERE ' . $sSQLcondi;
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			$besta = true;
		}
	}
	$objResponse = new xajaxResponse();
	if ($besta) {
		if (isset($APP->piel) == 0) {
			$APP->piel = 1;
		}
		$iPiel = $APP->piel;
		$saiu76idusuario_id = (int)$fila['saiu76idusuario'];
		$saiu76idusuario_td = $APP->tipo_doc;
		$saiu76idusuario_doc = '';
		$saiu76idusuario_nombre = '';
		if ($saiu76idusuario_id != 0) {
			list($saiu76idusuario_nombre, $saiu76idusuario_id, $saiu76idusuario_td, $saiu76idusuario_doc) = html_tercero($saiu76idusuario_td, $saiu76idusuario_doc, $saiu76idusuario_id, 0, $objDB);
		}
		$saiu76consec_nombre = '';
		$html_saiu76consec = html_oculto('saiu76consec', $fila['saiu76consec'], $saiu76consec_nombre);
		$objResponse->assign('div_saiu76consec', 'innerHTML', $html_saiu76consec);
		$saiu76id_nombre = '';
		$html_saiu76id = html_oculto('saiu76id', $fila['saiu76id'], $saiu76id_nombre);
		$objResponse->assign('div_saiu76id', 'innerHTML', $html_saiu76id);
		$objResponse->assign('saiu76anotacion', 'value', $fila['saiu76anotacion']);
		$objResponse->assign('saiu76visible', 'value', $fila['saiu76visible']);
		$objResponse->assign('saiu76idorigen', 'value', $fila['saiu76idorigen']);
		$idorigen = (int)$fila['saiu76idorigen'];
		$objResponse->assign('saiu76idarchivo', 'value', $fila['saiu76idarchivo']);
		$objResponse->call("verboton('banexasaiu76idarchivo', 'block')");
		$stemp = 'none';
		$stemp2 = html_lnkarchivo($idorigen, (int)$fila['saiu76idarchivo']);
		if ((int)$fila['saiu76idarchivo'] != 0) {
			$stemp = 'block';
		}
		$objResponse->assign('div_saiu76idarchivo', 'innerHTML', $stemp2);
		$objResponse->call("verboton('beliminasaiu76idarchivo','" . $stemp . "')");
		$objResponse->assign('saiu76idusuario', 'value', $fila['saiu76idusuario']);
		$objResponse->assign('saiu76idusuario_td', 'value', $saiu76idusuario_td);
		$objResponse->assign('saiu76idusuario_doc', 'value', $saiu76idusuario_doc);
		$objResponse->assign('div_saiu76idusuario', 'innerHTML', $saiu76idusuario_nombre);
		$objResponse->assign('saiu76fecha', 'value', $fila['saiu76fecha']);
		$objResponse->assign('div_saiu76fecha', 'innerHTML', fecha_desdenumero($fila['saiu76fecha']));
		list($iDia, $iMes, $iAgno) = fecha_DividirNumero($fila['saiu76fecha'], true);
		$objResponse->assign('saiu76fecha_dia', 'value', $iDia);
		$objResponse->assign('saiu76fecha_mes', 'value', $iMes);
		$objResponse->assign('saiu76fecha_agno', 'value', $iAgno);
		$html_saiu76hora = html_HoraMin('saiu76hora', $fila['saiu76hora'], 'saiu76minuto', $fila['saiu76minuto'], true);
		$objResponse->assign('div_saiu76hora', 'innerHTML', $html_saiu76hora);
		$objResponse->call("MensajeAlarmaV2('', 0)");
		$objResponse->call("verboton('belimina3076','block')");
		$objResponse->assign('div_p3076_Campos', 'style.display', 'block');
	} else {
		if ($paso == 1) {
			$objResponse->assign('saiu76consec', 'value', $saiu76consec);
		} else {
			$objResponse->call('MensajeAlarmaV2("No se encontro el registro de referencia:' . $saiu76id . '", 0)');
		}
	}
	if ($bHayDb) {
		$objDB->CerrarConexion();
	}
	return $objResponse;
}
function f3076_Eliminar($aParametros)
{
	$_SESSION['u_ultimominuto'] = iminutoavance();
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
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
	//if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto != '') {
		$objDB->dbPuerto = $APP->dbpuerto;
	}
	$objDB->xajax();
	list($sError, $sDebugElimina) = f3076_db_Eliminar($aParametros, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugElimina;
	$objResponse = new xajaxResponse();
	if ($sError == '') {
		list($sDetalle, $sDebugTabla) = f3076_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugTabla;
		$objResponse->assign('div_f3076detalle', 'innerHTML', $sDetalle);
		$objResponse->call('limpiaf3076');
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
function f3076_HtmlTabla($aParametros)
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
	list($sDetalle, $sDebugTabla) = f3076_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_f3076detalle', 'innerHTML', $sDetalle);
	if ($bDebug) {
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
	}
	return $objResponse;
}
function f3076_PintarLlaves($aParametros)
{
	$_SESSION['u_ultimominuto'] = iminutoavance();
	//if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$sIdioma = AUREA_Idioma();
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $sIdioma . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	if (isset($APP->piel) == 0) {
		$APP->piel = 1;
	}
	$iPiel = $APP->piel;
	$html_saiu76consec = '<input id="saiu76consec" name="saiu76consec" type="text" value="" onchange="revisaf3076()" class="cuatro"/>';
	$html_saiu76id = '<input id="saiu76id" name="saiu76id" type="hidden" value=""/>';
	$et_saiu76fecha='00/00/0000';
	$html_saiu76fecha=html_oculto('saiu76fecha', 0, $et_saiu76fecha);
	$html_saiu76hora = html_HoraMin('saiu76hora', fecha_hora(), 'saiu76minuto', fecha_minuto(), true);
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_saiu76consec', 'innerHTML', $html_saiu76consec);
	$objResponse->assign('div_saiu76id', 'innerHTML', $html_saiu76id);
	$objResponse->assign('div_saiu76fecha','innerHTML', $html_saiu76fecha);
	$objResponse->assign('div_saiu76hora', 'innerHTML', $html_saiu76hora);
	return $objResponse;
}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------
?>