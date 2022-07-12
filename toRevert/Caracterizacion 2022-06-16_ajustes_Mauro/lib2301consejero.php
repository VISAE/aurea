<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2020 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.25.6 lunes, 7 de septiembre de 2020
--- 2206 Grupos de estudiantes
*/
function f2301_db_GuardarDiscapacidad($DATA, $objDB, $bDebug = false)
{
	$iCodModulo = 2301;
	$bAudita[3] = true;
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	$mensajes_2301 = $APP->rutacomun . 'lg/lg_2301_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_2301)) {
		$mensajes_2301 = $APP->rutacomun . 'lg/lg_2301_es.php';
	}
	require $mensajes_todas;
	require $mensajes_2301;
	$sError = '';
	$iTipoError = 0;
	$sDebug = '';
	$bCerrando = false;
	$sErrorCerrando = '';
	// -- Se inicia validando todas las posibles entradas de usuario.
	$DATA['cara01discsensorial'] = htmlspecialchars(trim($DATA['cara01discsensorial']));
	$DATA['cara01discfisica'] = htmlspecialchars(trim($DATA['cara01discfisica']));
	$DATA['cara01disccognitiva'] = htmlspecialchars(trim($DATA['cara01disccognitiva']));
	// -- Se inicializan las variables que puedan pasar vacias {Especialmente n�meros}.
	// -- Seccion para validar los posibles causales de error.
	//Primero hacer un caso de revision de los encabezados.
	$aFicha = array('', 'cara01fichaper', 'cara01fichafam', 'cara01fichaaca', 'cara01fichalab', 'cara01fichabien', 'cara01fichapsico', 'cara01fichadigital', 'cara01fichalectura', 'cara01ficharazona', 'cara01fichaingles', 'cara01fichabiolog', 'cara01fichafisica', 'cara01fichaquimica');
	//Fin de revisar los casos de revision de encabezados
	$sSepara = ', ';
	switch ($DATA['cara01discversion']) {
		case 0:
			if ($DATA['cara01disccognitiva'] == '') {
				$sError = $ERR['cara01disccognitiva'] . $sSepara . $sError;
			}
			if ($DATA['cara01discfisica'] == '') {
				$sError = $ERR['cara01discfisica'] . $sSepara . $sError;
			}
			if ($DATA['cara01discsensorial'] == '') {
				$sError = $ERR['cara01discsensorial'] . $sSepara . $sError;
			}
			break;
		case 1:
			//cara01discv2sensorial, cara02discv2intelectura, cara02discv2fisica, cara02discv2psico, cara02discv2sistemica, 
			//cara02discv2sistemicaotro, cara02discv2multiple, cara02discv2multipleotro, cara01perayuda, cara01perotraayuda
			break;
		case 2:
			//cara01discv2tiene
			if ($DATA['cara01discv2tiene'] == 1) {
				$bHayUna = false;
				if ($DATA['cara01discv2sensorial'] != 0) {
					$bHayUna = true;
				}
				if ($DATA['cara02discv2intelectura'] != 0) {
					$bHayUna = true;
				}
				if ($DATA['cara02discv2fisica'] != 0) {
					$bHayUna = true;
				}
				if ($DATA['cara02discv2psico'] != 0) {
					$bHayUna = true;
				}
				if ($DATA['cara02discv2multiple'] != 0) {
					$bHayUna = true;
					if (trim($DATA['cara02discv2multipleotro']) == '') {
						$sError = $ERR['cara02discv2multipleotro'] . $sSepara . $sError;
					}
				}
				if (!$bHayUna) {
					$sError = 'El alumno informa que tiene una discapacidad pero no indica cual.' . $sSepara . $sError;
				}
			}
			if ($DATA['cara01discv2trastornos'] == 1) {
				$bHayUna = false;
				if ($DATA['cara01discv2trastaprende'] != 0) {
					$bHayUna = true;
				}
				if (!$bHayUna) {
					$sError = 'El alumno informa que tiene un trastorno de aprendizaje pero no indica cual.' . $sSepara . $sError;
				}
			}
			//$sError='No se ha encontrado el juego de variables para la versi&oacute;n '.$DATA['cara01discversion'].' de las discapacidades.';
			break;
		default:
			$sError = 'No se ha encontrado el juego de variables para la versi&oacute;n ' . $DATA['cara01discversion'] . ' de las discapacidades.';
			break;
	}
	//Valiaciones de campos obligatorios en todo guardar.
	if ($DATA['cara01idtercero'] == 0) {
		$sError = $ERR['cara01idtercero'];
	}
	if ($DATA['cara01idperaca'] == '') {
		$sError = $ERR['cara01idperaca'];
	}
	// -- Se verifican los valores de campos de otras tablas.
	if ($sError == '') {
		$sError = tabla_terceros_existe($DATA['cara01idtercero_td'], $DATA['cara01idtercero_doc'], $objDB, 'El tercero Tercero ');
	}
	if ($sError == '') {
		list($sError, $sInfo) = tercero_Bloqueado($DATA['cara01idtercero'], $objDB);
		if ($sInfo != '') {
			$sError = $sError . '<br>' . $sInfo;
		}
	}
	if ($sError == '') {
		if ($DATA['paso'] == 10) {
			$sError = $ERR['2'];
		} else {
			if (!seg_revisa_permiso($iCodModulo, 14, $objDB)) {
				$sError = $ERR['3'] . ' [Permiso 14 en 2301]';
			}
		}
	}
	if ($sError == '') {
		$bpasa = false;
		if ($DATA['paso'] == 10) {
		} else {
			$scampo[1] = 'cara01idconfirmadisc';
			$scampo[2] = 'cara01fechaconfirmadisc';

			$sdato[1] = $_SESSION['unad_id_tercero'];
			$sdato[2] = fecha_DiaMod();
			switch ($DATA['cara01discversion']) {
				case 0:
					$scampo[3] = 'cara01discsensorial';
					$scampo[4] = 'cara01discfisica';
					$scampo[5] = 'cara01disccognitiva';
					$scampo[6] = 'cara01perayuda';
					$scampo[7] = 'cara01discsensorialotra';
					$scampo[8] = 'cara01discfisicaotra';
					$scampo[9] = 'cara01disccognitivaotra';
					$scampo[10] = 'cara01perotraayuda';
					$sdato[3] = $DATA['cara01discsensorial'];
					$sdato[4] = $DATA['cara01discfisica'];
					$sdato[5] = $DATA['cara01disccognitiva'];
					$sdato[6] = $DATA['cara01perayuda'];
					$sdato[7] = $DATA['cara01discsensorialotra'];
					$sdato[8] = $DATA['cara01discfisicaotra'];
					$sdato[9] = $DATA['cara01disccognitivaotra'];
					$sdato[10] = $DATA['cara01perotraayuda'];
					$numcmod = 10;
					break;
				case 1:
					//cara01discv2sensorial, cara02discv2intelectura, cara02discv2fisica, cara02discv2psico, cara02discv2sistemica, 
					//cara02discv2sistemicaotro, cara02discv2multiple, cara02discv2multipleotro, cara01perayuda, cara01perotraayuda
					$scampo[3] = 'cara01discv2sensorial';
					$scampo[4] = 'cara02discv2intelectura';
					$scampo[5] = 'cara02discv2fisica';
					$scampo[6] = 'cara02discv2psico';
					$scampo[7] = 'cara02discv2sistemica';
					$scampo[8] = 'cara02discv2sistemicaotro';
					$scampo[9] = 'cara02discv2multiple';
					$scampo[10] = 'cara02discv2multipleotro';
					$scampo[11] = 'cara01perayuda';
					$scampo[12] = 'cara01perotraayuda';
					$sdato[3] = $DATA['cara01discv2sensorial'];
					$sdato[4] = $DATA['cara02discv2intelectura'];
					$sdato[5] = $DATA['cara02discv2fisica'];
					$sdato[6] = $DATA['cara02discv2psico'];
					$sdato[7] = $DATA['cara02discv2sistemica'];
					$sdato[8] = $DATA['cara02discv2sistemicaotro'];
					$sdato[9] = $DATA['cara02discv2multiple'];
					$sdato[10] = $DATA['cara02discv2multipleotro'];
					$sdato[11] = $DATA['cara01perayuda'];
					$sdato[12] = $DATA['cara01perotraayuda'];
					$numcmod = 12;
					break;
				case 2:
					$scampo[3] = 'cara01discv2tiene';
					$scampo[4] = 'cara01discv2sensorial';
					$scampo[5] = 'cara02discv2intelectura';
					$scampo[6] = 'cara02discv2fisica';
					$scampo[7] = 'cara02discv2psico';
					$scampo[8] = 'cara02discv2multiple';
					$scampo[9] = 'cara01perayuda';
					$scampo[10] = 'cara01discv2trastornos';
					$scampo[11] = 'cara01discv2trastaprende';
					$scampo[12] = 'cara01discv2contalento';
					$scampo[13] = 'cara01discv2pruebacoeficiente';
					$scampo[14] = 'cara01discv2condicionmedica';
					$scampo[15] = 'cara01discv2condmeddet';

					$sdato[3] = $DATA['cara01discv2tiene'];
					$sdato[4] = $DATA['cara01discv2sensorial'];
					$sdato[5] = $DATA['cara02discv2intelectura'];
					$sdato[6] = $DATA['cara02discv2fisica'];
					$sdato[7] = $DATA['cara02discv2psico'];
					$sdato[8] = $DATA['cara02discv2multiple'];
					$sdato[9] = $DATA['cara01perayuda'];
					$sdato[10] = $DATA['cara01discv2trastornos'];
					$sdato[11] = $DATA['cara01discv2trastaprende'];
					$sdato[12] = $DATA['cara01discv2contalento'];
					$sdato[13] = $DATA['cara01discv2pruebacoeficiente'];
					$sdato[14] = $DATA['cara01discv2condicionmedica'];
					$sdato[15] = $DATA['cara01discv2condmeddet'];
					$numcmod = 15;
					break;
			}
			$sWhere = 'cara01id=' . $DATA['cara01id'] . '';
			$sSQL = 'SELECT * FROM cara01encuesta WHERE ' . $sWhere;
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
						$bpasa = true;
					}
				}
			}
			if ($bpasa) {
				if ($APP->utf8 == 1) {
					$sdetalle = utf8_encode($sdatos) . '[' . $sWhere . ']';
					$sSQL = 'UPDATE cara01encuesta SET ' . utf8_encode($sdatos) . ' WHERE ' . $sWhere . ';';
				} else {
					$sdetalle = $sdatos . '[' . $sWhere . ']';
					$sSQL = 'UPDATE cara01encuesta SET ' . $sdatos . ' WHERE ' . $sWhere . ';';
				}
				$idaccion = 3;
			}
		}
		if ($bpasa) {
			$result = $objDB->ejecutasql($sSQL);
			if ($result == false) {
				$sError = $ERR['falla_guardar'] . ' [2301] ..<!-- ' . $sSQL . ' -->';
				if ($idaccion == 2) {
					$DATA['cara01id'] = '';
				}
				$DATA['paso'] = $DATA['paso'] - 10;
				$bCerrando = false;
			} else {
				if ($bDebug) {
					$sDebug = $sDebug . fecha_microtiempo() . ' Guardar 2301 ' . $sSQL . '<br>';
				}
				if ($bAudita[$idaccion]) {
					seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], $idaccion, $DATA['cara01id'], $sdetalle, $objDB);
				}
				$DATA['paso'] = 2;
				//Pasar la necesidad especial al tercero.
				$bIncluyeEspeciales = false;
				$sNecesidad = '';
				switch ($DATA['cara01discversion']) {
					case 0:
						if (true) {
							if ($DATA['cara01discsensorial'] != 'N') {
								$sNecesidad = trim('Discapacidad sensorial ' . $acara01discsensorial[$DATA['cara01discsensorial']] . ' ' . $DATA['cara01discsensorialotra']);
							}
							if ($DATA['cara01discfisica'] != 'N') {
								if ($sNecesidad != '') {
									$sNecesidad = $sNecesidad . ' - ';
								}
								$sNecesidad = $sNecesidad . trim('Discapacidad fisica ' . $acara01discfisica[$DATA['cara01discfisica']] . ' ' . $DATA['cara01discfisicaotra']);
							}
							if ($DATA['cara01disccognitiva'] != 'N') {
								if ($sNecesidad != '') {
									$sNecesidad = $sNecesidad . ' - ';
								}
								$sNecesidad = $sNecesidad . trim('Discapacidad cognitiva ' . $acara01disccognitiva[$DATA['cara01disccognitiva']] . ' ' . $DATA['cara01disccognitivaotra']);
							}
							$bIncluyeEspeciales = true;
							//Termina las necesidades versión 0
						}
						break;
					case 1:
						//cara01discv2sensorial, cara02discv2intelectura, cara02discv2fisica, cara02discv2psico, cara02discv2sistemica, 
						//cara02discv2sistemicaotro, cara02discv2multiple, cara02discv2multipleotro, cara01perayuda, cara01perotraayuda
						if (true) {
							$aTipoDisc = array('cara01discv2sensorial', 'cara02discv2intelectura', 'cara02discv2fisica', 'cara02discv2psico');
							$aTituloDisc = array('Discapacidad Sensorial', 'Discapacidad Intelectual', 'Discapacidad Física o Motora', 'Diversidad o Discapacidad Mental Psicosocial');
							for ($k = 0; $k <= 3; $k++) {
								$sTipoDisc = $aTipoDisc[$k];
								if ($DATA[$sTipoDisc] != 0) {
									$sInfoNecesidad = '{' . $DATA[$sTipoDisc] . '}';
									$sSQL = 'SELECT cara37nombre FROM cara37discapacidades WHERE cara37id=' . $DATA[$sTipoDisc] . '';
									$tablae = $objDB->ejecutasql($sSQL);
									if ($objDB->nf($tablae) > 0) {
										$filae = $objDB->sf($tablae);
										$sInfoNecesidad = $filae['cara37nombre'];
									}
									if ($sNecesidad != '') {
										$sNecesidad = $sNecesidad . ' - ';
									}
									$sNecesidad = $sNecesidad . $aTituloDisc[$k] . ': ' . $sInfoNecesidad;
								}
							}
							//las que siguen
							if ($DATA['cara02discv2sistemica'] == 1) {
								if ($sNecesidad != '') {
									$sNecesidad = $sNecesidad . ' - ';
								}
								$sNecesidad = $sNecesidad . 'Discapacidad Sistémica';
								if (trim($DATA['cara02discv2sistemicaotro']) != '') {
									$sNecesidad = $sNecesidad . ' [' . $DATA['cara02discv2sistemicaotro'] . ']';
								}
							}
							if ($DATA['cara02discv2multiple'] == 1) {
								if ($sNecesidad != '') {
									$sNecesidad = $sNecesidad . ' - ';
								}
								$sNecesidad = $sNecesidad . 'Discapacidad Múltiple';
								if (trim($DATA['cara02discv2multipleotro']) != '') {
									$sNecesidad = $sNecesidad . ' [' . $DATA['cara02discv2multipleotro'] . ']';
								}
							}
							$bIncluyeEspeciales = true;
						}
						break;
					case 2:
						if (true) {
							if ($DATA['cara01discv2tiene'] == 1) {
								$aTipoDisc = array('cara01discv2sensorial', 'cara02discv2intelectura', 'cara02discv2fisica', 'cara02discv2psico');
								$aTituloDisc = array('Discapacidad Sensorial', 'Discapacidad Intelectual', 'Discapacidad Física o Motora', 'Diversidad o Discapacidad Mental Psicosocial');
								for ($k = 0; $k <= 3; $k++) {
									$sTipoDisc = $aTipoDisc[$k];
									if ($DATA[$sTipoDisc] != 0) {
										$sInfoNecesidad = '{' . $DATA[$sTipoDisc] . '}';
										$sSQL = 'SELECT cara37nombre FROM cara37discapacidades WHERE cara37id=' . $DATA[$sTipoDisc] . '';
										$tablae = $objDB->ejecutasql($sSQL);
										if ($objDB->nf($tablae) > 0) {
											$filae = $objDB->sf($tablae);
											$sInfoNecesidad = $filae['cara37nombre'];
										}
										if ($sNecesidad != '') {
											$sNecesidad = $sNecesidad . ' - ';
										}
										$sNecesidad = $sNecesidad . $aTituloDisc[$k] . ': ' . $sInfoNecesidad;
									}
								}
								if ($DATA['cara02discv2multiple'] != 0) {
									if ($sNecesidad != '') {
										$sNecesidad = $sNecesidad . ' - ';
									}
									$sNecesidad = $sNecesidad . 'Discapacidad Múltiple: ' . trim($DATA['cara02discv2multipleotro']);
								}
								$bIncluyeEspeciales = true;
							}
						}
						break;
				}
				if ($bIncluyeEspeciales) {
					if ($DATA['cara01perayuda'] == 0) {
						$bIncluyeEspeciales = false;
					}
					if ($DATA['cara01perayuda'] == -1) {
						$bIncluyeEspeciales = false;
						if ($sNecesidad != '') {
							$sNecesidad = $sNecesidad . ' - ';
						}
						$sNecesidad = $sNecesidad . trim('Ajustes razonables: ' . $DATA['cara01perotraayuda']);
					}
					if ($bIncluyeEspeciales) {
						$sOtra = '{' . $DATA['cara01perayuda'] . '}';
						$sSQL = 'SELECT cara14nombre FROM cara14ayudaajuste WHERE cara14id=' . $DATA['cara01perayuda'] . '';
						$tablae = $objDB->ejecutasql($sSQL);
						if ($objDB->nf($tablae) > 0) {
							$filae = $objDB->sf($tablae);
							$sOtra = $filae['cara14nombre'];
						}
						if ($sNecesidad != '') {
							$sNecesidad = $sNecesidad . ' - ';
						}
						$sNecesidad = $sNecesidad . 'Ajustes razonables: ' . $sOtra;
					}
				}
				$sSQL = 'UPDATE unad11terceros SET unad11necesidadesp="' . $sNecesidad . '" WHERE unad11id=' . $DATA['cara01idtercero'] . '';
				$result = $objDB->ejecutasql($sSQL);
				$DATA['cara01idconfirmadisc'] = $sdato[1];
				$DATA['cara01fechaconfirmadisc'] = $sdato[2];
			}
		} else {
			$DATA['paso'] = 2;
		}
	} else {
		$DATA['paso'] = $DATA['paso'] - 10;
		$bCerrando = false;
	}
	$sInfoCierre = '';
	return array($DATA, $sError, $iTipoError, $bCerrando, $sErrorCerrando, $sDebug);
}
function f2301_VerificarMatricula($idPeriodo, $objDB, $bDebug = false)
{
	$iProcesados = 0;
	$sError = '';
	$sDebug = '';
	$iTipoPruebaAntiguos = 3;
	$aTipoPruebaPrograma = array();
	$sSQL = 'SELECT cara01id, cara01idtercero, cara01idescuela, cara01idprograma, cara01idzona, cara01idcead, cara01completa, 
	cara01tipocaracterizacion, cara01condicicionmatricula 
	FROM cara01encuesta 
	WHERE cara01idperaca=' . $idPeriodo . '';
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' Datos de las encuestas: ' . $sSQL . '<br>';
	}
	$tabla = $objDB->ejecutasql($sSQL);
	while ($fila = $objDB->sf($tabla)) {
		$sSQL = 'SELECT core16idescuela, core16idprograma, core16idzona, core16idcead, core16nuevo 
		FROM core16actamatricula 
		WHERE core16tercero=' . $fila['cara01idtercero'] . ' AND core16peraca=' . $idPeriodo . '';
		//if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Datos de la matricula: '.$sSQL.'<br>';}
		$tabla16 = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla16) > 0) {
			$fila16 = $objDB->sf($tabla16);
			$bActualizar = false;
			$bCambiaTipoPrueba = false;
			if ($fila['cara01idprograma'] == 0) {
				$bActualizar = true;
			} else {
				if ($fila['cara01idzona'] != $fila16['core16idzona']) {
					$bActualizar = true;
				}
				if ($fila['cara01idcead'] != $fila16['core16idcead']) {
					$bActualizar = true;
				}
				if ($fila['cara01idprograma'] != $fila16['core16idprograma']) {
					$bActualizar = true;
				}
				if ($fila['cara01idescuela'] != $fila16['core16idescuela']) {
					$bActualizar = true;
				}
				if ($fila['cara01condicicionmatricula'] != $fila16['core16nuevo']) {
					$bActualizar = true;
				}
			}
			$sAdd = '';
			if ($fila['cara01tipocaracterizacion'] == 0) {
				$bCambiaTipoPrueba = true;
			} else {
				if ($fila16['core16nuevo'] == 1) {
					if ($fila['cara01tipocaracterizacion'] == $iTipoPruebaAntiguos) {
						$bCambiaTipoPrueba = true;
					}
				}
			}
			if ($bCambiaTipoPrueba) {
				//$iTipoPrueba = $iTipoPruebaAntiguos;
				$bActualizar = true;
				if (isset($aTipoPruebaPrograma[$fila16['core16idprograma']]) == 0){
					$iTipoPruebaPrograma = $iTipoPruebaAntiguos;
					$sSQL = 'SELECT core09idtipocaracterizacion FROM core09programa WHERE core09id=' . $fila16['core16idprograma'] . '';
					$tabla9 = $objDB->ejecutasql($sSQL);
					if ($objDB->nf($tabla9) > 0) {
						$fila9 = $objDB->sf($tabla9);
						if ($fila9['core09idtipocaracterizacion'] != 0) {
							$iTipoPruebaPrograma = $fila9['core09idtipocaracterizacion'];
						}
					}
					$aTipoPruebaPrograma[$fila16['core16idprograma']] = $iTipoPruebaPrograma;
				}
				$iTipoPrueba = $aTipoPruebaPrograma[$fila16['core16idprograma']];
				$sAdd = ', cara01tipocaracterizacion=' . $iTipoPrueba . '';
				if ($fila['cara01completa'] == 'S') {
					$sAdd = $sAdd . ', cara01completa="N"';
				}
			}
			if ($bActualizar) {
				$sSQL = 'UPDATE cara01encuesta SET cara01idescuela=' . $fila16['core16idescuela'] . ', cara01idprograma=' . $fila16['core16idprograma'] . ', 
				cara01idzona=' . $fila16['core16idzona'] . ', cara01idcead=' . $fila16['core16idcead'] . ', cara01condicicionmatricula=' . $fila16['core16nuevo'] . $sAdd. ' 
				WHERE cara01id=' . $fila['cara01id'] . '';
				//if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Actualizando encuesta: '.$sSQL.'<br>';}
				$result = $objDB->ejecutasql($sSQL);
				$iProcesados++;
				if ($bCambiaTipoPrueba) {
					//Actualizar las preguntas
					list($sErrorP, $sDebugP) = f2301_AjustarTipoEncuesta($fila['cara01id'], $objDB, $bDebug);
					$sDebug = $sDebug . $sDebugP;
					list($sErrorP, $sDebugP) = f2301_IniciarPreguntas($fila['cara01id'], $objDB, $bDebug);
					$sDebug = $sDebug . $sDebugP;
				}
			}
		}
	}
	return array($iProcesados, $sError, $sDebug);
}

?>