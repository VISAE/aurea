<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2020 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.24.0 Thursday, January 2, 2020
*/

/** Archivo lib152.php.
 * Libreria 152 unae52revtildes.
 * @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
 * @date Thursday, January 2, 2020
 */
function f152_HTMLComboV2_unae52campo($objDB, $objCombos, $valor, $vrunae52tabla)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$objCombos->nuevo('unae52campo', $valor, true, '{' . $ETI['msg_seleccione'] . '}');
	if ($vrunae52tabla != '') {
		$sSQL = 'DESCRIBE ' . $vrunae52tabla;
		$tabla = $objDB->ejecutasql($sSQL);
		while ($fila = $objDB->sf($tabla)) {
			switch (substr($fila['Type'], 0, 3)) {
				case 'var':
				case 'tex':
					$objCombos->addItem($fila['Field'], $fila['Field']);
					break;
			}
		}
	}
	$res = $objCombos->html('', $objDB);
	return $res;
}
function f152_Combounae52campo($aParametros)
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
	$html_unae52campo = f152_HTMLComboV2_unae52campo($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_unae52campo', 'innerHTML', $html_unae52campo);
	//$objResponse->call('$("#unae52campo").chosen()');
	return $objResponse;
}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------
function f152_RevisarTildes($unae52tabla, $unae52campo, $objDB, $bDebug)
{
	$sError = '';
	$iTipoError = 1;
	$sDebug = '';
	//, 'í³', 'íº', 'í±'
	$aOrigen = array('Ã¡', 'Ã©', 'Ã³', 'Ãº', 'Ã±', 'Ã§', 'Ã£', 'Ãµ', 'Ã', 'í‰', 'í“');
	$aDestino = array('á', 'é',  'ó',  'ú',  'ñ',  'ç',  'ã',  'õ',  'í', 'É', 'Ó');

	//, 'ó',  'ú',  'ñ'
	$iTotal = count($aOrigen);
	$iSaltos = 0;
	$iCambios = 0;
	$iNumLlaves = 1;
	$aLlaves = array();
	//Primero ubicar el indice
	$sSQL = 'SHOW INDEXES FROM ' . $unae52tabla . ' WHERE Key_name="PRIMARY"';
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' Llave primaria: ' . $sSQL . '<br>';
	}
	$tabla = $objDB->ejecutasql($sSQL);
	$iCampos = $objDB->nf($tabla);
	if ($iCampos == 1) {
		$fila = $objDB->sf($tabla);
		$sCampoId = $fila['Column_name'];
	} else {
		if ($iCampos == 0) {
			$iTotal = 0;
			$iTipoError = 0;
			$sError = 'La tabla no registra un indice PRIMARY';
		} else {
			//$sError='El indice PRIMARY de la tabla es compuesto.';
			$iNumLlaves = 0;
			$sCampoId = '';
			while ($fila = $objDB->sf($tabla)) {
				$iNumLlaves++;
				$aLlaves[$iNumLlaves] = $fila['Column_name'];
				if ($sCampoId != '') {
					$sCampoId = $sCampoId . ', ';
				}
				$sCampoId = $sCampoId . $fila['Column_name'];
			}
		}
	}
	if ($sError == '') {
		//Lo dejamos en 0 porque no vamos a hacerlo de esta manera.
		$iPasadas = $iTotal;
		for ($k = 0; $k < $iPasadas; $k++) {
			$sOrigen = $aOrigen[$k];
			$sDestino = $aDestino[$k];
			$sSQL = 'SELECT ' . $sCampoId . ', ' . $unae52campo . ' 
			FROM ' . $unae52tabla . ' 
			WHERE (' . $unae52campo . ' LIKE "%' . $sOrigen . '%")';
			$tabla = $objDB->ejecutasql($sSQL);
			$iSubTotal = $objDB->nf($tabla);
			if ($iSubTotal > 0) {
				if ($bDebug) {
					$sDebug = $sDebug . fecha_microtiempo() . ' Origen: ' . $sSQL . '<br>';
				}
				$iSaltos++;
				$iCambios = $iCambios + $iSubTotal;
				while ($fila = $objDB->sf($tabla)) {
					$sAjuste = cadena_Reemplazar($fila[$unae52campo], $sOrigen, $sDestino);
					if ($iNumLlaves == 1) {
						$sWhere = '' . $sCampoId . '=' . $fila[$sCampoId] . '';
					} else {
						$sWhere = '';
						for ($j = 1; $j <= $iNumLlaves; $j++) {
							if ($sWhere != '') {
								$sWhere = $sWhere . ' AND ';
							}
							$sCampoLlave = $aLlaves[$j];
							$sWhere = $sWhere . '' . $sCampoLlave . '="' . $fila[$sCampoLlave] . '"';
						}
					}
					$sSQL = 'UPDATE ' . $unae52tabla . ' SET ' . $unae52campo . '="' . $sAjuste . '" WHERE ' . $sWhere;
					if ($bDebug) {
						$sDebug = $sDebug . fecha_microtiempo() . ' ' . $sSQL . '<br>';
					}
					$result = $objDB->ejecutasql($sSQL);
				}
			}
		}
		//  ---------------- CONSULTA PARA UBICAR LOS CODIGOS EXAGESIMALES QUE SE ESTAN USANDO ---------------
/*
SELECT unad24nombre, HEX('õ') AS Origen,  HEX(unad24nombre) AS HexTotal 
FROM unad24sede
WHERE unad24id=3
*/
		//Ahora hacer lo mismo pero en hexagesimal
		// $aOrigenH = array('C3A1', 'C3A9', 'C3AD', 'C3B3', 'C3BA', 
		// 'C381', 'C389', 'C38D', 'C393', 'C39A', 
		// 'C3B1', 'C391', 'C3A7', 'C3A3', 'C3B5');
		// $aDestinoH = array('C383C2A1', 'C383C2A9',  'C383C2AD',  'C383C2B3',  'C383C2BA',  
		// 'C383C281', 'C383E280B0', 'C383C28D', 'C383E2809C', 'C383C5A1', 
		// 'C383C2B1', 'C383E28098', 'C383C2A7', 'C383C2A3', 'C383C2A3');
		// Se cambia origen a destino para los datos de softwaretest
		$aOrigenH = array('C383C2A1', 'C383C2A9',  'C383C2AD',  'C383C2B3',  'C383C2BA',  
		'C383C281', 'C383E280B0', 'C383C28D', 'C383E2809C', 'C383C5A1', 
		'C383C2B1', 'C383E28098', 'C383C2A7', 'C383C2A3', 'C383C2A3');
		$aDestinoH = array('C3A1', 'C3A9', 'C3AD', 'C3B3', 'C3BA', 
		'C381', 'C389', 'C38D', 'C393', 'C39A', 
		'C3B1', 'C391', 'C3A7', 'C3A3', 'C3B5');
		$iPasadas = count($aOrigenH);
		//$iPasadas = 1;
		for ($k = 0; $k < $iPasadas; $k++) {
			$sOrigen = $aOrigenH[$k];
			$sDestino = $aDestinoH[$k];
			$sSQL = 'SELECT ' . $sCampoId . ', HEX(' . $unae52campo . ') AS Dato 
			FROM ' . $unae52tabla . ' 
			WHERE HEX(' . $unae52campo . ') LIKE "%' . $sOrigen . '%"';
			$tabla = $objDB->ejecutasql($sSQL);
			$iSubTotal = $objDB->nf($tabla);
			if ($iSubTotal > 0) {
				if ($bDebug) {
					$sDebug = $sDebug . fecha_microtiempo() . ' Origen HEX: ' . $sSQL . '<br>';
				}
				$iSaltos++;
				$iCambios = $iCambios + $iSubTotal;
				while ($fila = $objDB->sf($tabla)) {
					$sAjuste = cadena_Reemplazar($fila['Dato'], $sOrigen, $sDestino);
					if ($iNumLlaves == 1) {
						$sWhere = '' . $sCampoId . '=' . $fila[$sCampoId] . '';
					} else {
						$sWhere = '';
						for ($j = 1; $j <= $iNumLlaves; $j++) {
							if ($sWhere != '') {
								$sWhere = $sWhere . ' AND ';
							}
							$sCampoLlave = $aLlaves[$j];
							$sWhere = $sWhere . '' . $sCampoLlave . '="' . $fila[$sCampoLlave] . '"';
						}
					}
					$sSQL = 'UPDATE ' . $unae52tabla . ' SET ' . $unae52campo . '=0x' . $sAjuste . ' WHERE ' . $sWhere;
					echo $sSQL.'<br>';
					if ($bDebug) {
						$sDebug = $sDebug . fecha_microtiempo() . ' ' . $sSQL . '<br>';
					}
					$result = $objDB->ejecutasql($sSQL);
				}
			}
		}
		//Fin del proceso.
	}
	if ($sError == '') {
		$sError = 'Se han realizado ' . $iCambios . ' cambios en ' . $iSaltos . ' iteraciones.';
	}
	return array($sError, $iTipoError, $sDebug);
}
