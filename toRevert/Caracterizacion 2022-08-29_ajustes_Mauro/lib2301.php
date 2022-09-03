<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2018 - 2022 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.21.0 viernes, 22 de junio de 2018
--- Modelo Versión 2.22.2 martes, 17 de julio de 2018
--- 2301 cara01encuesta
--- Modelo Versión 2.23.6 Tuesday, October 1, 2019
--- Modelo Versión 2.25.0 viernes, 3 de abril de 2020
--- Modelo Versión 2.25.5 domingo, 16 de agosto de 2020
--- Modelo Versión 2.28.1 miércoles, 23 de marzo de 2022
*/
function f2301_AjustarTipoEncuesta($cara01id, $objDB, $bDebug = false)
{
	$sError = '';
	$sDebug = '';
	$cara01fichafam = -1;
	$cara01fichaaca = -1;
	$cara01fichalab = -1;
	$cara01fichabien = -1;
	$cara01fichapsico = -1;
	$cara01fichadigital = -1;
	$cara01fichalectura = -1;
	$cara01ficharazona = -1;
	$cara01fichaingles = -1;
	$cara01fichaquimica = -1;
	$cara01fichabiolog = -1;
	$cara01fichafisica = -1;
	$cara01tipocaracterizacion = 0;
	//Ver que tipo de caracterizacion es.
	$sSQL = 'SELECT cara01tipocaracterizacion FROM cara01encuesta WHERE cara01id=' . $cara01id . '';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$cara01tipocaracterizacion = $fila['cara01tipocaracterizacion'];
	}
	//Segun el tipo de caracterizacion algunos campus pueden pasar a 0
	if ($cara01tipocaracterizacion != 0) {
		$sSQL = 'SELECT cara11fichafamilia, cara11ficha1, cara11ficha2, cara11ficha3, cara11ficha4, cara11ficha5, cara11ficha6, cara11ficha7 FROM cara11tipocaract WHERE cara11id=' . $cara01tipocaracterizacion . '';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' Se cargan los datos del tipo de caracterizacion: ' . $sSQL . '<br>';
		}
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			if ($fila['cara11fichafamilia'] == 'S') {
				$cara01fichafam = 0;
				$cara01fichaaca = 0;
				$cara01fichalab = 0;
				$cara01fichabien = 0;
				$cara01fichapsico = 0;
			}
			if ($fila['cara11ficha1'] == 'S') {
				$cara01fichadigital = 0;
			}
			if ($fila['cara11ficha2'] == 'S') {
				$cara01fichalectura = 0;
			}
			if ($fila['cara11ficha3'] == 'S') {
				$cara01ficharazona = 0;
			}
			if ($fila['cara11ficha4'] == 'S') {
				$cara01fichaingles = 0;
			}
			if ($fila['cara11ficha5'] == 'S') {
				$cara01fichaquimica = 0;
			}
			if ($fila['cara11ficha6'] == 'S') {
				$cara01fichabiolog = 0;
			}
			if ($fila['cara11ficha7'] == 'S') {
				$cara01fichafisica = 0;
			}
		}
	}
	$sSQL = 'UPDATE cara01encuesta SET cara01fichafam=' . $cara01fichafam . ', cara01fichaaca=' . $cara01fichaaca . ', 
	cara01fichalab=' . $cara01fichalab . ', cara01fichabien=' . $cara01fichabien . ', cara01fichapsico=' . $cara01fichapsico . ', 
	cara01fichadigital=' . $cara01fichadigital . ', cara01fichalectura=' . $cara01fichalectura . ', cara01ficharazona=' . $cara01ficharazona . ', 
	cara01fichaingles=' . $cara01fichaingles . ', cara01fichabiolog=' . $cara01fichabiolog . ', cara01fichafisica=' . $cara01fichafisica . ', cara01fichaquimica=' . $cara01fichaquimica . ' WHERE cara01id=' . $cara01id . '';
	$tabla = $objDB->ejecutasql($sSQL);
	return array($sError, $sDebug);
}
//Febrero 24 de 2022 - Se ajusta esta funcion para incluir el cargue de encuestas para estudiantes antiguos.
function f2301_IniciarEncuesta($idTercero, $idPeriodo, $objDB, $bDebug = false, $bForzarNueva = false)
{
	$sError = '';
	$sDebug = '';
	$sCondi = '';
	$idEntidad = Traer_Entidad();
	$sDirBase = __DIR__ . '/';
	require $sDirBase . 'app.php';
	if ($idPeriodo != 0) {
		$idPeriodoBase = $idPeriodo;
		if ($bForzarNueva) {
			$sCondi = ' AND cara01idperaca=' . $idPeriodo . '';
		}
	}
	$idPeriodoBaseAntiguos = 1142;
	$idPeriodoBase = 474;
	if ($idEntidad != 0) {
		$idPeriodoBase = 0;
		$idPeriodoBaseAntiguos = 0;
	}
	$cara01tipocaracterizacion = 0;
	$bInsertarEncuesta = false;
	$bEsAntiguo = false;
	//Necesitamos saber a que programa pertenece.
	$sCondiMat = '';
	if ($idPeriodo != 0) {
		$sCondiMat = ' AND core16peraca=' . $idPeriodo . '';
	}
	$idPrograma = 0;
	$sSQL = 'SELECT core16idprograma 
	FROM core16actamatricula 
	WHERE core16tercero=' . $idTercero . $sCondiMat . '
	ORDER BY core16peraca DESC';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$idPrograma = $fila['core16idprograma'];
	} else {
		$sError = 'No se registra matricula para el estudiante, no es posible cargar una caracterizaci&oacute;n.';
	}
	//Ahora si vemos si tiene encuesta o no en el periodo.
	$sSQL = 'SELECT cara01id, cara01fechaencuesta 
	FROM cara01encuesta 
	WHERE cara01idtercero=' . $idTercero . '' . $sCondi . ' 
	ORDER BY cara01fechaencuesta DESC';
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' <b>Caracterizaci&oacute;n</b> Verificando encuestas de: ' . $sSQL . '<br>';
	}
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) == 0) {
		$bInsertarEncuesta = true;
		//Agosto 5 de 2022 - Vamos a excluir los programas que no ofrezcan titulo y no tengan encuesta.
		if ($idPrograma != 0) {
			$sSQL = 'SELECT core09idtipocaracterizacion, core09ofrecetitulo FROM core09programa WHERE core09id=' . $idPrograma . '';
			$tabla = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla) > 0) {
				$fila = $objDB->sf($tabla);
				if ($fila['core09idtipocaracterizacion'] == 0){
					if ($fila['core09ofrecetitulo'] == 0){
						$bInsertarEncuesta = false;
					} 
				} 
			}
		}
	} else {
		// Febrero 24 Se deja inhabilitado hasta que se monte la nueva versión de la caracterización. 
		//(No olvidar retirar la restriccion a 1143)
		//Si lleva mas de un año se debe repetir la encuesta pero con el tipo antiguos.
		$bEsAntiguo = true;
		$fila = $objDB->sf($tabla);
		$iFechaUltimaCaracterizacion = $fila['cara01fechaencuesta'];
		$idUltimaEncuesta = $fila['cara01id'];
		//Ahora tenemos que ver si la fecha de ultima matricula es mayor a un año. (CUALQUIER PROGRAMA, ES SOLO PARA ACTUALIZAR EL TERCERO.)
		$sSQL = 'SELECT core16fecharecibido, core16peraca 
		FROM core16actamatricula 
		WHERE core16tercero=' . $idTercero . ' AND core16peraca>' . $idPeriodoBaseAntiguos . '';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' <b>Caracterizaci&oacute;n</b> Verificando ultima matricula: ' . $sSQL . '<br>';
		}
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			$iDiasSeparacion = fecha_DiasEntreFechasDesdeNumero($iFechaUltimaCaracterizacion, $fila['core16fecharecibido']);
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . ' <b>Caracterizaci&oacute;n</b> Dias desde la caracterizacion: ' . $iDiasSeparacion . ' [Ult Carac: ' . $iFechaUltimaCaracterizacion . ' - Matricula: '. $fila['core16fecharecibido'] . ']<br>';
			}
			if ($iDiasSeparacion > 364) {
				if ($bDebug) {
					$sDebug = $sDebug . fecha_microtiempo() . ' <b>Caracterizaci&oacute;n</b> Se inicia encuesta de antiguo.<br>';
				}
				$bInsertarEncuesta = true;
				$cara01tipocaracterizacion = 3;
				$idPeriodo = $fila['core16peraca'];
			}
		}
	}
	if ($bInsertarEncuesta) {
		//hacer el insert... Si el periodo viene en 0 hay que buscarlo.
		if ($idPeriodo == 0) {
			$sSQL = 'SELECT core01peracainicial 
			FROM core01estprograma 
			WHERE core01idtercero=' . $idTercero . ' AND core01peracainicial>0 
			ORDER BY core01peracainicial DESC';
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . ' Consulta de verificacion ' . $sSQL . '<br>';
			}
			$tabla = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla) > 0) {
				$fila = $objDB->sf($tabla);
				$idPeriodo = $fila['core01peracainicial'];
				$idPeriodoBase = $fila['core01peracainicial'];
			}
		}
	}
	if ($bInsertarEncuesta) {
		$cara01fechainicio = fecha_DiaMod();
		$cara01agnos = 0;
		$cara01sexo = '';
		$cara01pais = '057';
		$cara01depto = '';
		$cara01ciudad = '';
		$cara01direccion = '';
		$cara01estrato = 0;
		$cara01zonares = '';
		$cara01estcivil = 0;
		$cara01idzona = 0;
		$cara01idcead = 0;
		$cara01condicicionmatricula = -1;
		//Cargar los datos del tercero
		$sSQL = 'SELECT unad11fechanace, unad11genero, unad11pais, unad11deptodoc, unad11ciudaddoc, unad11direccion, unad11ecivil, 
		unad11correo, unad11telefono 
		FROM unad11terceros 
		WHERE unad11id=' . $idTercero . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			$sFechaNace = $fila['unad11fechanace'];
			$cara01agnos = 0;
			$cara01sexo = $fila['unad11genero'];
			$cara01pais = $fila['unad11pais'];
			$cara01depto = $fila['unad11deptodoc'];
			$cara01ciudad = $fila['unad11ciudaddoc'];
			$cara01direccion = $fila['unad11direccion'];
			$cara01estcivil = $fila['unad11ecivil'];
			$cara01telefono1 = substr($fila['unad11telefono'], 0, 20);
			$cara01correopersonal = $fila['unad11correo'];
			if (fecha_esvalida($sFechaNace)) {
				list($cara01agnos, $iMedida) = fecha_edad($sFechaNace);
				if ($iMedida != 1) {
					$cara01agnos = 0;
				}
			} else {
				//@@@@ - Tratar de buscar una solucion cuando no hay fecha de nacimiento.
			}
		} else {
			$sError = 'No se encuentran datos del tercero Ref ' . $idTercero . '';
		}
		if ($sError == '') {
			$cara01idprograma = 0;
			$cara01idescuela = 0;
			$cara01idconsejero = 0;
			//Cargamos los datos del plan de estudios.
			$sSQL = 'SELECT core01idzona, core011idcead, core01idprograma, core01idescuela 
			FROM core01estprograma 
			WHERE core01idtercero=' . $idTercero . ' AND core01peracainicial=' . $idPeriodo . '';
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . ' Se traen los datos de la matricula: ' . $sSQL . '<br>';
			}
			$tabla = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla) == 0) {
				//No lo encontro en ese periodo.... por tanto no es un estudiante nuevo....
				$idProgramaPeriodo = -99;
				$sSQL = 'SELECT core16idprograma, core16idplanestudio
				FROM core16actamatricula 
				WHERE core16peraca=' . $idPeriodo . ' AND core16tercero=' . $idTercero . '';
				$tabla16 = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($tabla16) > 0) {
					$fila16 = $objDB->sf($tabla16);
					$idProgramaPeriodo = $fila16['core16idprograma'];
					$sSQL = 'SELECT core01idzona, core011idcead, core01idprograma, core01idescuela 
					FROM core01estprograma 
					WHERE core01idtercero=' . $idTercero . ' AND core01idprograma=' . $idProgramaPeriodo . '';
					if ($bDebug) {
						$sDebug = $sDebug . fecha_microtiempo() . ' Se traen los datos de la matricula para peraca 87: ' . $sSQL . '<br>';
					}
					$tabla = $objDB->ejecutasql($sSQL);
				}
			}
			if ($objDB->nf($tabla) > 0) {
				$fila = $objDB->sf($tabla);
				$cara01idzona = $fila['core01idzona'];
				$cara01idcead = $fila['core011idcead'];
				$cara01idprograma = $fila['core01idprograma'];
				$cara01idescuela = $fila['core01idescuela'];
				if ($cara01tipocaracterizacion == 0) {
					//Con el programa traemos el tipo de caracterizacion.
					$sSQL = 'SELECT core10idtipocaracterizacion FROM core10programaversion WHERE core10idprograma=' . $cara01idprograma . ' ORDER BY core10estado DESC, core10consec DESC';
					if ($bDebug) {
						$sDebug = $sDebug . fecha_microtiempo() . ' Se traen los datos de la matricula: ' . $sSQL . '<br>';
					}
					$tabla = $objDB->ejecutasql($sSQL);
					if ($objDB->nf($tabla) > 0) {
						$fila = $objDB->sf($tabla);
						$cara01tipocaracterizacion = $fila['core10idtipocaracterizacion'];
					}
				}
			}
			if ($cara01tipocaracterizacion == 0) {
				$sSQL = 'SELECT core09idtipocaracterizacion FROM core09programa WHERE core09id=' . $cara01idprograma . '';
				if ($bDebug) {
					$sDebug = $sDebug . fecha_microtiempo() . ' Se traen los datos del programa: ' . $sSQL . '<br>';
				}
				$tabla = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($tabla) > 0) {
					$fila = $objDB->sf($tabla);
					$cara01tipocaracterizacion = $fila['core09idtipocaracterizacion'];
				}
			}
			//Intentamos ver si ya tiene asignado un consejero
			$sSQL = 'SELECT core16idconsejero, core16idescuela, core16idprograma, core16idzona, core16idcead, core16nuevo 
			FROM core16actamatricula 
			WHERE core16tercero=' . $idTercero . ' AND core16peraca=' . $idPeriodo . '';
			$tabla = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla) > 0) {
				$fila = $objDB->sf($tabla);
				$cara01idconsejero = $fila['core16idconsejero'];
				$cara01idzona = $fila['core16idzona'];
				$cara01idcead = $fila['core16idcead'];
				$cara01idprograma = $fila['core16idprograma'];
				$cara01idescuela = $fila['core16idescuela'];
				$cara01condicicionmatricula = $fila['core16nuevo'];
			}
			//Terminamos de cargar los datos de la matricula.
		}
		if ($sError == '') {
			if ($cara01tipocaracterizacion == 0) {
				$sError = 'No ha sido posible determinar el programa del estudiante (PEI)';
			}
		}
		if ($sError == '') {
			$cara01id = tabla_consecutivo('cara01encuesta', 'cara01id', '', $objDB);
			//Abril 3 de 2020, se agregan las variables de discapacidad por version.
			//Agosto 17 de 2020, se agregan las variables de discapacidad por version.
			$cara01discversion = 2;
			$sCampos2301 = 'cara01idperaca, cara01idtercero, cara01id, cara01completa, cara01fechainicio, 
			cara01agnos, cara01sexo, cara01pais, cara01depto, cara01ciudad, 
			cara01direccion, cara01estrato, cara01zonares, cara01estcivil, cara01idzona, 
			cara01idcead, cara01matconvenio, cara01victimadesplazado, cara01victimaacr, cara01inpecfuncionario, 
			cara01inpecrecluso, cara01discsensorial, cara01discfisica, cara01disccognitiva, cara01bien_interesrepdeporte, 
			cara01bien_baloncesto, cara01bien_voleibol, cara01bien_futbolsala, cara01bien_artesmarc, cara01bien_tenisdemesa, 
			cara01bien_ajedrez, cara01bien_juegosautoc, cara01telefono1, cara01correopersonal, cara01idprograma, 
			cara01idescuela, cara01tipocaracterizacion, cara01idconsejero, cara01discversion, cara01condicicionmatricula';
			$sValores2301 = '' . $idPeriodo . ', ' . $idTercero . ', ' . $cara01id . ', "N", ' . $cara01fechainicio . ', 
			' . $cara01agnos . ', "' . $cara01sexo . '", "' . $cara01pais . '", "' . $cara01depto . '", "' . $cara01ciudad . '", 
			"' . $cara01direccion . '", ' . $cara01estrato . ', "' . $cara01zonares . '", "' . $cara01estcivil . '", ' . $cara01idzona . ', 
			' . $cara01idcead . ', "N", "N", "N", "N", 
			"N", "N", "N", "N", "N", 
			"N", "N", "N", "N", "N", 
			"N", "N", "' . $cara01telefono1 . '", "' . $cara01correopersonal . '", ' . $cara01idprograma . ', 
			' . $cara01idescuela . ', ' . $cara01tipocaracterizacion . ', ' . $cara01idconsejero . ', ' . $cara01discversion . ', ' . $cara01condicicionmatricula . '';
			if ($APP->utf8 == 1) {
				$sSQL = 'INSERT INTO cara01encuesta (' . $sCampos2301 . ') VALUES (' . utf8_encode($sValores2301) . ');';
				$sdetalle = $sCampos2301 . '[' . utf8_encode($sValores2301) . ']';
			} else {
				$sSQL = 'INSERT INTO cara01encuesta (' . $sCampos2301 . ') VALUES (' . $sValores2301 . ');';
				$sdetalle = $sCampos2301 . '[' . $sValores2301 . ']';
			}
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . ' Insertando encuesta: ' . $sSQL . '<br>';
			}
			$tabla = $objDB->ejecutasql($sSQL);
			if ($tabla == false) {
			} else {
				$cara44id = $cara01id;
				$cara44sexoversion = 1;
				$cara44bienversion = 2;
				$sCampos2344 = 'cara44id, cara44sexoversion, cara44bienversion';
				$sValores2344 = '' . $cara01id . ', ' . $cara44sexoversion . ', ' . $cara44bienversion . '';
				if ($APP->utf8 == 1) {
					$sSQL = 'INSERT INTO cara44encuesta (' . $sCampos2344 . ') VALUES (' . utf8_encode($sValores2344) . ');';
					$sdetalle = $sCampos2344 . '[' . utf8_encode($sValores2344) . ']';
				} else {
					$sSQL = 'INSERT INTO cara44encuesta (' . $sCampos2344 . ') VALUES (' . $sValores2344 . ');';
					$sdetalle = $sCampos2344 . '[' . $sValores2344 . ']';
				}
				if ($bDebug) {
					$sDebug = $sDebug . fecha_microtiempo() . ' Insertando preguntas actualizadas 2022: ' . $sSQL . '<br>';
				}
				$tabla = $objDB->ejecutasql($sSQL);
				// Fin insertando nuevas preguntas de la encuesta
				if ($cara01tipocaracterizacion == 3) {
					// Precargue de respuestas anteriores
					f2301_AlimentarEncuesta($idUltimaEncuesta, $cara01id, $objDB, $bDebug);
				}
				list($sErrorP, $sDebugP) = f2301_AjustarTipoEncuesta($cara01id, $objDB, $bDebug);
				$sDebug = $sDebug . $sDebugP;
				list($sErrorP, $sDebugP) = f2301_IniciarPreguntas($cara01id, $objDB, $bDebug);
				$sDebug = $sDebug . $sDebugP;
				seg_auditar(2301, $_SESSION['unad_id_tercero'], 2, $cara01id, 'Inicia la encuesta de caracterizacion para el tercero ' . $idTercero . '', $objDB);
			}
		}
	}
	return array($sError, $sDebug);
}
function f2301_IniciarPreguntas($cara01id, $objDB, $bDebug = false)
{
	$sError = '';
	$sDebug = '';
	//Lo que hacemos es ver cuantas preguntas hay y cuantas necesitamos
	//Primero alistamos el juego de variables.
	$iBloques = 14;
	for ($k = 1; $k <= $iBloques; $k++) {
		$aPreguntas[$k] = 0;
		$aCarga[$k] = 0;
		$aIds[$k] = '-99';
	}
	//Ahora basados en el tipo de caracterizacion. cargamos cuanta preguntas necesitamos.
	$sSQL = 'SELECT TB.cara01tipocaracterizacion, T1.cara11ficha1, T1.cara11ficha1pregbas, T1.cara11ficha1pregprof, T1.cara11ficha2, T1.cara11ficha2pregbas, T1.cara11ficha2pregprof, T1.cara11ficha3, T1.cara11ficha3pregbas, T1.cara11ficha3pregprof, T1.cara11ficha4, T1.cara11ficha4pregbas, T1.cara11ficha4pregprof, T1.cara11ficha5, T1.cara11ficha5pregbas, T1.cara11ficha5pregprof, T1.cara11ficha6, T1.cara11ficha6pregbas, T1.cara11ficha6pregprof, T1.cara11ficha7, T1.cara11ficha7pregbas, T1.cara11ficha7pregprof 
	FROM cara01encuesta AS TB, cara11tipocaract AS T1 
	WHERE TB.cara01id=' . $cara01id . ' AND TB.cara01tipocaracterizacion=T1.cara11id';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		for ($k = 1; $k <= 7; $k++) {
			if ($fila['cara11ficha' . $k] == 'S') {
				$aPreguntas[$k] = $fila['cara11ficha' . $k . 'pregbas'];
				$aPreguntas[$k + 7] = $fila['cara11ficha' . $k . 'pregprof'];
			}
		}
	}
	//Ahora miramos cuantas hay por cada bloque.
	$sSQL = 'SELECT cara10idbloque, cara10idpregunta, cara10nivelpregunta FROM cara10pregprueba WHERE cara10idcara=' . $cara01id . '';
	$tabla = $objDB->ejecutasql($sSQL);
	while ($fila = $objDB->sf($tabla)) {
		$k = $fila['cara10idbloque'];
		if ($fila['cara10nivelpregunta'] == 1) {
			$k = $k + 7;
		}
		$aCarga[$k]++;
		$aIds[$k] = $aIds[$k] . ',' . $fila['cara10idpregunta'];
	}
	for ($k = 1; $k <= $iBloques; $k++) {
		$iPendiente = $aPreguntas[$k] - $aCarga[$k];
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' Bloque ' . $k . ' Preguntas ' . $aPreguntas[$k] . ' Cargadas ' . $aCarga[$k] . ' <br>';
		}
		if ($iPendiente > 0) {
			//Agregar la iPendientes a la consulta.
			$sAgregadas = '-99';
			$iAgregadas = 0;
			$svalores2310 = '';
			$iBloqueReal = $k;
			$iNivelPreg = 0;
			if ($k > 7) {
				$iBloqueReal = $k - 7;
				$iNivelPreg = 1;
			}
			$cara10consec = tabla_consecutivo('cara10pregprueba', 'cara10consec', 'cara10idcara=' . $cara01id . ' AND cara10idbloque=' . $iBloqueReal . '', $objDB);
			$cara10id = tabla_consecutivo('cara10pregprueba', 'cara10id', '', $objDB);
			$sSQL = 'SELECT cara08id FROM cara08pregunta WHERE cara08idbloque=' . $iBloqueReal . ' AND cara08nivelpregunta=' . $iNivelPreg . ' AND cara08activa="S" AND cara08id NOT IN (' . $aIds[$k] . ') ORDER BY (cara08usosiniciales+cara08usostotales), cara08consec';
			$tabla = $objDB->ejecutasql($sSQL);
			while ($fila = $objDB->sf($tabla)) {
				if ($iPendiente > $iAgregadas) {
					$sAgregadas = $sAgregadas . ',' . $fila['cara08id'];
					$iAgregadas++;
					if ($svalores2310 != '') {
						$svalores2310 = $svalores2310 . ', ';
					}
					$svalores2310 = $svalores2310 . '(' . $cara01id . ', ' . $iBloqueReal . ', ' . $cara10consec . ', ' . $cara10id . ', ' . $fila['cara08id'] . ', 0, 0, ' . $iNivelPreg . ')';
					$cara10consec++;
					$cara10id++;
				}
			}
			//Termino de seleccionar cuales, ahora hacer el insert e informarle a las preguntas que han sido usadas.
			if ($iAgregadas > 0) {
				$sSQL = 'INSERT INTO cara10pregprueba (cara10idcara, cara10idbloque, cara10consec, cara10id, cara10idpregunta, cara10idrpta, cara10puntaje, cara10nivelpregunta) VALUES ' . $svalores2310 . ';';
				$tabla = $objDB->ejecutasql($sSQL);
				if ($bDebug) {
					$sDebug = $sDebug . fecha_microtiempo() . ' Consulta de insersion de preguntas: ' . $sSQL . '<br>';
				}
				//Informar que fueron usadas...
				$sSQL = 'UPDATE cara08pregunta SET cara08usostotales=cara08usostotales+1 WHERE cara08id IN (' . $sAgregadas . ')';
				$tabla = $objDB->ejecutasql($sSQL);
				if ($bDebug) {
					$sDebug = $sDebug . fecha_microtiempo() . ' Consulta de actualizacion de totales: ' . $sSQL . '<br>';
				}
			}
		}
	}
	return array($sError, $sDebug);
}
function f2301_HTMLComboV2_cara01idperaca($objDB, $objCombos, $valor, $idTercero = 0)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$sCondi = '';
	if ($idTercero != 0) {
		$sIds = '-99';
		$sSQL = 'SELECT core01peracainicial FROM core01estprograma WHERE core01idtercero=' . $idTercero . ' GROUP BY core01peracainicial';
		$tabla = $objDB->ejecutasql($sSQL);
		while ($fila = $objDB->sf($tabla)) {
			if ($fila['core01peracainicial'] != 87) {
				$sIds = $sIds . ',' . $fila['core01peracainicial'];
			}
		}
		//Agregamos aquellos en los que se le ha hecho caracterizacion.
		$sSQL = 'SELECT cara01idperaca FROM cara01encuesta WHERE cara01idtercero=' . $idTercero . '';
		$tabla = $objDB->ejecutasql($sSQL);
		while ($fila = $objDB->sf($tabla)) {
			$sIds = $sIds . ',' . $fila['cara01idperaca'];
		}
		//$sCondi='WHERE exte02id IN ('.$sIds.')';
		$sCondi = $sIds;
	}
	$objCombos->nuevo('cara01idperaca', $valor, true, '{' . $ETI['msg_seleccione'] . '}');
	//$objCombos->iAncho=450;
	$objCombos->sAccion = 'RevisaLlave();';
	$sSQL = f146_ConsultaCombo($sCondi);
	$res = $objCombos->html($sSQL, $objDB);
	return $res;
}
function f2301_HTMLComboV2_cara01depto($objDB, $objCombos, $valor, $vrcara01pais)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	//@@ Se debe arreglar la condicion..
	$sCondi = 'unad19codpais="' . $vrcara01pais . '"';
	if ($sCondi != '') {
		$sCondi = ' WHERE ' . $sCondi;
	}
	$objCombos->nuevo('cara01depto', $valor, true, '{' . $ETI['msg_seleccione'] . '}');
	if ($vrcara01pais != '057') {
		$objCombos->addItem('00000', $ETI['msg_otro']);
	}
	$objCombos->sAccion = 'carga_combo_cara01ciudad()';
	$res = $objCombos->html('SELECT unad19codigo AS id, unad19nombre AS nombre FROM unad19depto' . $sCondi, $objDB);
	return $res;
}
function f2301_HTMLComboV2_cara01ciudad($objDB, $objCombos, $valor, $vrcara01depto)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$objCombos->nuevo('cara01ciudad', $valor, true, '{' . $ETI['msg_seleccione'] . '}');
	$sSQL = '';
	if ($vrcara01depto != '') {
		$sSQL = 'SELECT unad20codigo AS id, unad20nombre AS nombre FROM unad20ciudad WHERE unad20coddepto="' . $vrcara01depto . '"';
		if (substr($vrcara01depto, 0, 3) != '057') {
			if ($vrcara01depto != '00000') {
				$objCombos->addItem('00000000', $ETI['msg_otra']);
			}
		}
	}
	$res = $objCombos->html($sSQL, $objDB);
	return $res;
}
function f2301_HTMLComboV2_cara01idzona($objDB, $objCombos, $valor)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$objCombos->nuevo('cara01idzona', $valor, true, '{' . $ETI['msg_seleccione'] . '}');
	$objCombos->sAccion = 'carga_combo_cara01idcead()';
	$res = $objCombos->html('SELECT unad23id AS id, unad23nombre AS nombre FROM unad23zona', $objDB);
	return $res;
}
function f2301_HTMLComboV2_cara01idcead($objDB, $objCombos, $valor, $vrcara01idzona)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	//@@ Se debe arreglar la condicion..
	$sCondi = 'unad24idzona="' . $vrcara01idzona . '"';
	if ($sCondi != '') {
		$sCondi = ' WHERE ' . $sCondi;
	}
	$objCombos->nuevo('cara01idcead', $valor, true, '{' . $ETI['msg_seleccione'] . '}');
	$res = $objCombos->html('SELECT unad24id AS id, unad24nombre AS nombre FROM unad24sede' . $sCondi, $objDB);
	return $res;
}
function f2301_HTMLComboV2_cara01tipocaracterizacion($objDB, $objCombos, $valor)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$objCombos->nuevo('cara01tipocaracterizacion', $valor, true, '{' . $ETI['msg_seleccione'] . '}');
	$res = $objCombos->html('SELECT cara11id AS id, cara11nombre AS nombre FROM cara11tipocaract', $objDB);
	return $res;
}
function f2301_Combocara01depto($aParametros)
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
	$objCombos = new clsHtmlCombos('n');
	$html_cara01depto = f2301_HTMLComboV2_cara01depto($objDB, $objCombos, '', $aParametros[0]);
	$html_cara01ciudad = f2301_HTMLComboV2_cara01ciudad($objDB, $objCombos, '', '');
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_cara01depto', 'innerHTML', $html_cara01depto);
	$objResponse->assign('div_cara01ciudad', 'innerHTML', $html_cara01ciudad);
	return $objResponse;
}
function f2301_Combocara01ciudad($aParametros)
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
	$objCombos = new clsHtmlCombos('n');
	$html_cara01ciudad = f2301_HTMLComboV2_cara01ciudad($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_cara01ciudad', 'innerHTML', $html_cara01ciudad);
	return $objResponse;
}
function f2301_Combocara01idcead($aParametros)
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
	$objCombos = new clsHtmlCombos('n');
	$html_cara01idcead = f2301_HTMLComboV2_cara01idcead($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_cara01idcead', 'innerHTML', $html_cara01idcead);
	return $objResponse;
}
function elimina_archivo_cara01discv2archivoorigen($idPadre, $bDebug = false)
{
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
		archivo_eliminar('cara01encuesta', 'cara01id', 'cara01discv2soporteorigen', 'cara01discv2archivoorigen', $idPadre, $objDB);
	}
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	if ($bPuedeEliminar) {
		$objResponse->call("limpia_cara01discv2archivoorigen");
	} else {
		MensajeAlarmaV2('".$sError."', 0);
	}
	if ($bDebug) {
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
	}
	return $objResponse;
}
function f2301_ExisteDato($datos)
{
	if (!is_array($datos)) {
		$datos = json_decode(str_replace('\"', '"', $datos), true);
	}
	$_SESSION['u_ultimominuto'] = iminutoavance();
	$bHayLlave = true;
	$cara01idperaca = numeros_validar($datos[1]);
	if ($cara01idperaca == '') {
		$bHayLlave = false;
	}
	$cara01idtercero = numeros_validar($datos[2]);
	if ($cara01idtercero == '') {
		$bHayLlave = false;
	}
	if ($bHayLlave) {
		require './app.php';
		$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto != '') {
			$objDB->dbPuerto = $APP->dbpuerto;
		}
		$objDB->xajax();
		$sSQL = 'SELECT cara01idtercero FROM cara01encuesta WHERE cara01idperaca=' . $cara01idperaca . ' AND cara01idtercero=' . $cara01idtercero . '';
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
function f2301_Busquedas($aParametros)
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
	$mensajes_2301 = $APP->rutacomun . 'lg/lg_2301_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_2301)) {
		$mensajes_2301 = $APP->rutacomun . 'lg/lg_2301_es.php';
	}
	require $mensajes_todas;
	require $mensajes_2301;
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
	$sParams = '';
	$sTabla = '';
	$sJavaBusqueda = '';
	$aParametrosB = array();
	$aParametrosB[101] = 1;
	$aParametrosB[102] = 20;
	switch ($sCampo) {
		case 'cara01idtercero':
			require $APP->rutacomun . 'lib111.php';
			$sTabla = f111_TablaDetalleBusquedas($aParametrosB, $objDB);
			$sTitulo = 'Busqueda de terceros';
			$sParams = f111_ParametrosBusqueda();
			$sJavaBusqueda = f111_JavaScriptBusqueda(2301);
			break;
		case 'cara01idconfirmadesp':
			require $APP->rutacomun . 'lib111.php';
			$sTabla = f111_TablaDetalleBusquedas($aParametrosB, $objDB);
			$sTitulo = 'Busqueda de terceros';
			$sParams = f111_ParametrosBusqueda();
			$sJavaBusqueda = f111_JavaScriptBusqueda(2301);
			break;
		case 'cara01idconfirmacr':
			require $APP->rutacomun . 'lib111.php';
			$sTabla = f111_TablaDetalleBusquedas($aParametrosB, $objDB);
			$sTitulo = 'Busqueda de terceros';
			$sParams = f111_ParametrosBusqueda();
			$sJavaBusqueda = f111_JavaScriptBusqueda(2301);
			break;
		case 'cara01idconfirmadisc':
			require $APP->rutacomun . 'lib111.php';
			$sTabla = f111_TablaDetalleBusquedas($aParametrosB, $objDB);
			$sTitulo = 'Busqueda de terceros';
			$sParams = f111_ParametrosBusqueda();
			$sJavaBusqueda = f111_JavaScriptBusqueda(2301);
			break;
		case 'cara01idconsejero':
			require $APP->rutacomun . 'lib111.php';
			$sTabla = f111_TablaDetalleBusquedas($aParametrosB, $objDB);
			$sTitulo = 'Busqueda de terceros';
			$sParams = f111_ParametrosBusqueda();
			$sJavaBusqueda = f111_JavaScriptBusqueda(2301);
			break;
	}
	$sTitulo = '<h2>' . $ETI['titulo_2301'] . ' - ' . $sTitulo . '</h2>';
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_97titulo', 'innerHTML', $sTitulo);
	$objResponse->assign('div_97params', 'innerHTML', $sParams);
	$objResponse->assign('div_97tabla', 'innerHTML', $sTabla);
	$objResponse->setFunction('paginarbusqueda', '', $sJavaBusqueda);
	$objResponse->call('expandesector(97)');
	return $objResponse;
}
function f2301_HtmlBusqueda($aParametros)
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
		case 'cara01idtercero':
			require $APP->rutacomun . 'lib111.php';
			$sDetalle = f111_TablaDetalleBusquedas($aParametros, $objDB);
			break;
		case 'cara01idconfirmadesp':
			require $APP->rutacomun . 'lib111.php';
			$sDetalle = f111_TablaDetalleBusquedas($aParametros, $objDB);
			break;
		case 'cara01idconfirmacr':
			require $APP->rutacomun . 'lib111.php';
			$sDetalle = f111_TablaDetalleBusquedas($aParametros, $objDB);
			break;
		case 'cara01idconfirmadisc':
			require $APP->rutacomun . 'lib111.php';
			$sDetalle = f111_TablaDetalleBusquedas($aParametros, $objDB);
			break;
		case 'cara01idconsejero':
			require $APP->rutacomun . 'lib111.php';
			$sDetalle = f111_TablaDetalleBusquedas($aParametros, $objDB);
			break;
	}
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_97tabla', 'innerHTML', $sDetalle);
	return $objResponse;
}
function f2301_TablaDetalleV2($aParametros, $objDB, $bDebug = false)
{
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
	if (isset($aParametros[105]) == 0) {
		$aParametros[105] = '';
	}
	if (isset($aParametros[106]) == 0) {
		$aParametros[106] = '';
	}
	if (isset($aParametros[107]) == 0) {
		$aParametros[107] = '';
	}
	if (isset($aParametros[108]) == 0) {
		$aParametros[108] = '';
	}
	if (isset($aParametros[109]) == 0) {
		$aParametros[109] = '';
	}
	if (isset($aParametros[110]) == 0) {
		$aParametros[110] = '';
	}
	if (isset($aParametros[111]) == 0) {
		$aParametros[111] = '';
	}
	if (isset($aParametros[112]) == 0) {
		$aParametros[112] = '';
	}
	if (isset($aParametros[113]) == 0) {
		$aParametros[113] = '';
	}
	$sDebug = '';
	$idTercero = numeros_validar($aParametros[100]);
	$pagina = $aParametros[101];
	$lineastabla = $aParametros[102];
	$bListar = numeros_validar($aParametros[106]);
	$babierta = true;
	$bEsConsejero = false;
	$sLeyenda = '';
	if ($sLeyenda != '') {
		$sLeyenda = '<div class="salto1px"></div>
		<div class="GrupoCamposAyuda">
		' . $sLeyenda . '
		<div class="salto1px"></div>
		</div>';
		return array($sLeyenda . '<input id="paginaf2301" name="paginaf2301" type="hidden" value="' . $pagina . '"/><input id="lppf2301" name="lppf2301" type="hidden" value="' . $lineastabla . '"/>', $sDebug);
		die();
	}
	$sSQLadd = '';
	$sSQLadd1 = '';
	//if ((int)$aParametros[103]!=-1){$sSQLadd=$sSQLadd.' AND TB.campo='.$aParametros[103];}
	if ($aParametros[103] != '') {
		$sSQLadd = $sSQLadd . ' AND T2.unad11doc LIKE "%' . $aParametros[103] . '%"';
	}
	if ($aParametros[104] != '') {
		$sBase = trim(strtoupper($aParametros[104]));
		$aNoms = explode(' ', $sBase);
		for ($k = 1; $k <= count($aNoms); $k++) {
			$sCadena = $aNoms[$k - 1];
			if ($sCadena != '') {
				$sSQLadd = $sSQLadd . ' AND T2.unad11razonsocial LIKE "%' . $sCadena . '%"';
				//$sSQLadd1=$sSQLadd1.'T1.sys11razonsocial LIKE "%'.$sCadena.'%" AND ';
			}
		}
	}
	if ($aParametros[105] != '') {
		$sSQLadd1 = $sSQLadd1 . 'TB.cara01idperaca=' . $aParametros[105] . ' AND ';
	}
	//cara01tipocaracterizacion
	if ($aParametros[111] != '') {
		$sSQLadd1 = $sSQLadd1 . 'TB.cara01tipocaracterizacion=' . $aParametros[111] . ' AND ';
	}
	if ($aParametros[108] != '') {
		$sSQLadd1 = $sSQLadd1 . 'TB.cara01idprograma=' . $aParametros[108] . ' AND ';
	} else {
		if ($aParametros[107] != '') {
			$sSQLadd1 = $sSQLadd1 . 'TB.cara01idescuela=' . $aParametros[107] . ' AND ';
		}
	}
	if ($aParametros[110] != '') {
		$sSQLadd1 = $sSQLadd1 . 'TB.cara01idcead=' . $aParametros[110] . ' AND ';
	} else {
		if ($aParametros[109] != '') {
			$sSQLadd1 = $sSQLadd1 . 'TB.cara01idzona=' . $aParametros[109] . ' AND ';
		}
	}
	switch ($bListar) {
		case 1: //Donde es consejero
			$sSQLadd1 = $sSQLadd1 . 'TB.cara01idconsejero=' . $idTercero . ' AND ';
			break;
		case 11: //Terminadas
			$sSQLadd1 = $sSQLadd1 . 'TB.cara01completa="S" AND ';
			break;
		case 12: // Incompletas.
			$sSQLadd1 = $sSQLadd1 . 'TB.cara01completa<>"S" AND ';
			break;
		case 13: // Nuevos
			$sSQLadd1 = $sSQLadd1 . 'TB.cara01condicicionmatricula=1 AND ';
			break;
		case 14: // Antiguos
			$sSQLadd1 = $sSQLadd1 . 'TB.cara01condicicionmatricula=0 AND ';
			break;
		case 15: // Regingreso
			$sSQLadd1 = $sSQLadd1 . 'TB.cara01condicicionmatricula=8 AND ';
			break;
	}
	$sCondiDiscapacidad = '((TB.cara01discversion=0 AND ((TB.cara01discsensorial<>"N") OR (TB.cara01discfisica<>"N") OR (TB.cara01disccognitiva<>"N") OR (TB.cara01perayuda<>0))) OR (TB.cara01discversion=2 AND (TB.cara01discv2tiene=1 OR (TB.cara01perayuda<>0)))) AND ';
	if ($aParametros[112] == 1) {
		$sSQLadd1 = $sSQLadd1 . $sCondiDiscapacidad . '';
	}
	if ($aParametros[112] == 2) {
		$sSQLadd1 = $sSQLadd1 . $sCondiDiscapacidad . 'TB.cara01fechaconfirmadisc=0 AND ';
	}
	$sTablaConvenio = '';
	if ($aParametros[113] != '') {
		$sTablaConvenio = ', core51convenioest AS T51';
		$sSQLadd1 = $sSQLadd1 . 'TB.cara01idtercero=T51.core51idtercero AND T51.core51idconvenio=' . $aParametros[113] . ' AND T51.core51activo="S" AND ';
	}
	//
	$sTitulos = 'Periodo,TipoDoc,Documento,Estudiante,Completa,Fecha encuesta';
	$sSQL = 'SELECT T1.exte02nombre, T2.unad11tipodoc AS C2_td, T2.unad11doc AS C2_doc, T2.unad11razonsocial AS C2_nombre, TB.cara01completa, TB.cara01fechaencuesta, TB.cara01id, TB.cara01idperaca, TB.cara01idconsejero 
	FROM cara01encuesta AS TB' . $sTablaConvenio . ', exte02per_aca AS T1, unad11terceros AS T2 
	WHERE ' . $sSQLadd1 . ' TB.cara01idperaca=T1.exte02id AND TB.cara01idtercero=T2.unad11id ' . $sSQLadd . '
	ORDER BY TB.cara01idperaca DESC, T2.unad11doc';
	$sSQLlista = str_replace("'", "|", $sSQL);
	$sSQLlista = str_replace('"', "|", $sSQLlista);
	$sErrConsulta = '<input id="consulta_2301" name="consulta_2301" type="hidden" value="' . $sSQLlista . '"/>
	<input id="titulos_2301" name="titulos_2301" type="hidden" value="' . $sTitulos . '"/>';
	$tabladetalle = $objDB->ejecutasql($sSQL);
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' Consulta 2301: ' . $sSQL . '<br>';
	}
	if ($tabladetalle == false) {
		$registros = 0;
		$sErrConsulta = $sErrConsulta . '..<input id="err" name="err" type="hidden" value="' . $sSQL . ' ' . $objDB->serror . '"/>';
		//$sLeyenda=$sSQL;
	} else {
		$registros = $objDB->nf($tabladetalle);
		if ($registros == 0) {
			//return array(utf8_encode($sErrConsulta.'<input id="paginaf2301" name="paginaf2301" type="hidden" value="'.$pagina.'"/><input id="lppf2301" name="lppf2301" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
	$res = '';
	$sHTMLConsejero = '';
	$aPeriodos = array();
	if (true) {
		$sSQL = 'SELECT cara13peraca, cara01cargaasignada FROM cara13consejeros WHERE cara13idconsejero=' . $idTercero . ' AND cara13activo="S" ORDER BY cara13peraca DESC';
		$tabla13 = $objDB->ejecutasql($sSQL);
		while ($fila = $objDB->sf($tabla13)) {
			$bEsConsejero = true;
			$aPeriodos[$fila['cara13peraca']] = $fila['cara01cargaasignada'];
		}
	}
	$res = $sErrConsulta . $sLeyenda . $res . '<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">
	<thead class="fondoazul"><tr>
	<td colspan="2"><b>' . $ETI['cara01idtercero'] . '</b></td>
	<td><b>' . $ETI['cara01completa'] . '</b></td>
	<td><b>' . $ETI['cara01fechaencuesta'] . '</b></td>
	<td align="right" colspan="2">
	' . html_paginador('paginaf2301', $registros, $lineastabla, $pagina, 'paginarf2301()') . '
	' . html_lpp('lppf2301', $lineastabla, 'paginarf2301()') . '
	</td>
	</tr></thead>';
	$tlinea = 1;
	$idPeriodo = -1;
	while ($filadet = $objDB->sf($tabladetalle)) {
		if ($idPeriodo != $filadet['cara01idperaca']) {
			$idPeriodo = $filadet['cara01idperaca'];
			$res = $res . '<tr class="fondoazul">
			<td colspan="6">' . $ETI['cara01idperaca'] . ' <b>' . cadena_notildes($filadet['exte02nombre']) . '</b></td>
			</tr>';
		}
		$sPrefijo = '';
		$sSufijo = '';
		$sClass = '';
		$sLink = '';
		$sLink2 = '';
		$et_cara01completa = $ETI['msg_abierto'];
		$et_cara01fechaencuesta = '';
		if ($filadet['cara01completa'] == 'S') {
			$sPrefijo = '<b>';
			$sSufijo = '</b>';
			$et_cara01completa = $ETI['msg_cerrado'];
			$et_cara01fechaencuesta = fecha_desdenumero($filadet['cara01fechaencuesta']);
		}
		if (($tlinea % 2) == 0) {
			$sClass = ' class="resaltetabla"';
		}
		$tlinea++;
		if ($filadet['cara01idconsejero'] == $idTercero) {
			$sLink2 = $ETI['lnk_acargo'];
		}
		if ($babierta) {
			$sLink = '<a href="javascript:cargaridf2301(' . $filadet['cara01id'] . ')" class="lnkresalte">' . $ETI['lnk_cargar'] . '</a>';
			if ($bEsConsejero) {
				if (isset($aPeriodos[$idPeriodo]) != 0) {
					if ($filadet['cara01idconsejero'] == 0) {
						$sLink2 = '<a href="javascript:soyconsejeroidf2301(' . $filadet['cara01id'] . ')" class="lnkresalte">' . $ETI['lnk_soyconsejero'] . '</a>';
					}
				}
			}
		}
		$res = $res . '<tr' . $sClass . '>
		<td>' . $sPrefijo . $filadet['C2_td'] . ' ' . $filadet['C2_doc'] . $sSufijo . '</td>
		<td>' . $sPrefijo . cadena_notildes($filadet['C2_nombre']) . $sSufijo . '</td>
		<td>' . $sPrefijo . $et_cara01completa . $sSufijo . '</td>
		<td>' . $sPrefijo . $et_cara01fechaencuesta . $sSufijo . '</td>
		<td><div id="div_lnkconsejero' . $filadet['cara01id'] . '">' . $sLink2 . '</div></td>
		<td>' . $sLink . '</td>
		</tr>';
	}
	$res = $res . '</table>' . $sHTMLConsejero;
	$objDB->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
}
function f2301_HtmlTabla($aParametros)
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
	list($sDetalle, $sDebugTabla) = f2301_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_f2301detalle', 'innerHTML', $sDetalle);
	if ($bDebug) {
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
	}
	return $objResponse;
}
function f2301_db_CargarPadre($DATA, $objDB, $bDebug = false)
{
	$sError = '';
	$iTipoError = 0;
	$sDebug = '';
	require './app.php';
	$DATA['cara01idtercero_td'] = $APP->tipo_doc;
	$DATA['cara01idtercero_doc'] = '';
	$DATA['cara01idconfirmadesp_td'] = $APP->tipo_doc;
	$DATA['cara01idconfirmadesp_doc'] = '';
	$DATA['cara01idconfirmacr_td'] = $APP->tipo_doc;
	$DATA['cara01idconfirmacr_doc'] = '';
	$DATA['cara01idconfirmadisc_td'] = $APP->tipo_doc;
	$DATA['cara01idconfirmadisc_doc'] = '';
	$DATA['cara01idconsejero_td'] = $APP->tipo_doc;
	$DATA['cara01idconsejero_doc'] = '';
	if ($DATA['paso'] == 1) {
		$sSQLcondi = 'cara01idperaca=' . $DATA['cara01idperaca'] . ' AND cara01idtercero="' . $DATA['cara01idtercero'] . '"';
	} else {
		$sSQLcondi = 'cara01id=' . $DATA['cara01id'] . '';
	}
	$sSQL = 'SELECT * FROM cara01encuesta WHERE ' . $sSQLcondi;
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$DATA['cara01idperaca'] = $fila['cara01idperaca'];
		$DATA['cara01idtercero'] = $fila['cara01idtercero'];
		$DATA['cara01id'] = $fila['cara01id'];
		$DATA['cara01completa'] = $fila['cara01completa'];
		$DATA['cara01fichaper'] = $fila['cara01fichaper'];
		$DATA['cara01fichafam'] = $fila['cara01fichafam'];
		$DATA['cara01fichaaca'] = $fila['cara01fichaaca'];
		$DATA['cara01fichalab'] = $fila['cara01fichalab'];
		$DATA['cara01fichabien'] = $fila['cara01fichabien'];
		$DATA['cara01fichapsico'] = $fila['cara01fichapsico'];
		$DATA['cara01fichadigital'] = $fila['cara01fichadigital'];
		$DATA['cara01fichalectura'] = $fila['cara01fichalectura'];
		$DATA['cara01ficharazona'] = $fila['cara01ficharazona'];
		$DATA['cara01fichaingles'] = $fila['cara01fichaingles'];
		$DATA['cara01fechaencuesta'] = $fila['cara01fechaencuesta'];
		$DATA['cara01agnos'] = $fila['cara01agnos'];
		$DATA['cara01sexo'] = $fila['cara01sexo'];
		$DATA['cara01pais'] = $fila['cara01pais'];
		$DATA['cara01depto'] = $fila['cara01depto'];
		$DATA['cara01ciudad'] = $fila['cara01ciudad'];
		$DATA['cara01nomciudad'] = $fila['cara01nomciudad'];
		$DATA['cara01direccion'] = $fila['cara01direccion'];
		$DATA['cara01estrato'] = $fila['cara01estrato'];
		$DATA['cara01zonares'] = $fila['cara01zonares'];
		$DATA['cara01estcivil'] = $fila['cara01estcivil'];
		$DATA['cara01nomcontacto'] = $fila['cara01nomcontacto'];
		$DATA['cara01parentezcocontacto'] = $fila['cara01parentezcocontacto'];
		$DATA['cara01celcontacto'] = $fila['cara01celcontacto'];
		$DATA['cara01correocontacto'] = $fila['cara01correocontacto'];
		$DATA['cara01idzona'] = $fila['cara01idzona'];
		$DATA['cara01idcead'] = $fila['cara01idcead'];
		$DATA['cara01matconvenio'] = $fila['cara01matconvenio'];
		$DATA['cara01raizal'] = $fila['cara01raizal'];
		$DATA['cara01palenquero'] = $fila['cara01palenquero'];
		$DATA['cara01afrocolombiano'] = $fila['cara01afrocolombiano'];
		$DATA['cara01otracomunnegras'] = $fila['cara01otracomunnegras'];
		$DATA['cara01rom'] = $fila['cara01rom'];
		$DATA['cara01indigenas'] = $fila['cara01indigenas'];
		$DATA['cara01victimadesplazado'] = $fila['cara01victimadesplazado'];
		$DATA['cara01idconfirmadesp'] = $fila['cara01idconfirmadesp'];
		$DATA['cara01fechaconfirmadesp'] = $fila['cara01fechaconfirmadesp'];
		$DATA['cara01victimaacr'] = $fila['cara01victimaacr'];
		$DATA['cara01idconfirmacr'] = $fila['cara01idconfirmacr'];
		$DATA['cara01fechaconfirmacr'] = $fila['cara01fechaconfirmacr'];
		$DATA['cara01inpecfuncionario'] = $fila['cara01inpecfuncionario'];
		$DATA['cara01inpecrecluso'] = $fila['cara01inpecrecluso'];
		$DATA['cara01inpectiempocondena'] = $fila['cara01inpectiempocondena'];
		$DATA['cara01centroreclusion'] = $fila['cara01centroreclusion'];
		$DATA['cara01discsensorial'] = $fila['cara01discsensorial'];
		$DATA['cara01discfisica'] = $fila['cara01discfisica'];
		$DATA['cara01disccognitiva'] = $fila['cara01disccognitiva'];
		$DATA['cara01idconfirmadisc'] = $fila['cara01idconfirmadisc'];
		$DATA['cara01fechaconfirmadisc'] = $fila['cara01fechaconfirmadisc'];
		$DATA['cara01fam_tipovivienda'] = $fila['cara01fam_tipovivienda'];
		$DATA['cara01fam_vivecon'] = $fila['cara01fam_vivecon'];
		$DATA['cara01fam_numpersgrupofam'] = $fila['cara01fam_numpersgrupofam'];
		$DATA['cara01fam_hijos'] = $fila['cara01fam_hijos'];
		$DATA['cara01fam_personasacargo'] = $fila['cara01fam_personasacargo'];
		$DATA['cara01fam_dependeecon'] = $fila['cara01fam_dependeecon'];
		$DATA['cara01fam_escolaridadpadre'] = $fila['cara01fam_escolaridadpadre'];
		$DATA['cara01fam_escolaridadmadre'] = $fila['cara01fam_escolaridadmadre'];
		$DATA['cara01fam_numhermanos'] = $fila['cara01fam_numhermanos'];
		$DATA['cara01fam_posicionherm'] = $fila['cara01fam_posicionherm'];
		$DATA['cara01fam_familiaunad'] = $fila['cara01fam_familiaunad'];
		$DATA['cara01acad_tipocolegio'] = $fila['cara01acad_tipocolegio'];
		$DATA['cara01acad_modalidadbach'] = $fila['cara01acad_modalidadbach'];
		$DATA['cara01acad_estudioprev'] = $fila['cara01acad_estudioprev'];
		$DATA['cara01acad_ultnivelest'] = $fila['cara01acad_ultnivelest'];
		$DATA['cara01acad_obtubodiploma'] = $fila['cara01acad_obtubodiploma'];
		$DATA['cara01acad_hatomadovirtual'] = $fila['cara01acad_hatomadovirtual'];
		$DATA['cara01acad_tiemposinest'] = $fila['cara01acad_tiemposinest'];
		$DATA['cara01acad_razonestudio'] = $fila['cara01acad_razonestudio'];
		$DATA['cara01acad_primeraopc'] = $fila['cara01acad_primeraopc'];
		$DATA['cara01acad_programagusto'] = $fila['cara01acad_programagusto'];
		$DATA['cara01acad_razonunad'] = $fila['cara01acad_razonunad'];
		$DATA['cara01campus_compescrito'] = $fila['cara01campus_compescrito'];
		$DATA['cara01campus_portatil'] = $fila['cara01campus_portatil'];
		$DATA['cara01campus_tableta'] = $fila['cara01campus_tableta'];
		$DATA['cara01campus_telefono'] = $fila['cara01campus_telefono'];
		$DATA['cara01campus_energia'] = $fila['cara01campus_energia'];
		$DATA['cara01campus_internetreside'] = $fila['cara01campus_internetreside'];
		$DATA['cara01campus_expvirtual'] = $fila['cara01campus_expvirtual'];
		$DATA['cara01campus_ofimatica'] = $fila['cara01campus_ofimatica'];
		$DATA['cara01campus_foros'] = $fila['cara01campus_foros'];
		$DATA['cara01campus_conversiones'] = $fila['cara01campus_conversiones'];
		$DATA['cara01campus_usocorreo'] = $fila['cara01campus_usocorreo'];
		$DATA['cara01campus_aprendtexto'] = $fila['cara01campus_aprendtexto'];
		$DATA['cara01campus_aprendvideo'] = $fila['cara01campus_aprendvideo'];
		$DATA['cara01campus_aprendmapas'] = $fila['cara01campus_aprendmapas'];
		$DATA['cara01campus_aprendeanima'] = $fila['cara01campus_aprendeanima'];
		$DATA['cara01campus_mediocomunica'] = $fila['cara01campus_mediocomunica'];
		$DATA['cara01lab_situacion'] = $fila['cara01lab_situacion'];
		$DATA['cara01lab_sector'] = $fila['cara01lab_sector'];
		$DATA['cara01lab_caracterjuri'] = $fila['cara01lab_caracterjuri'];
		$DATA['cara01lab_cargo'] = $fila['cara01lab_cargo'];
		$DATA['cara01lab_antiguedad'] = $fila['cara01lab_antiguedad'];
		$DATA['cara01lab_tipocontrato'] = $fila['cara01lab_tipocontrato'];
		$DATA['cara01lab_rangoingreso'] = $fila['cara01lab_rangoingreso'];
		$DATA['cara01lab_tiempoacadem'] = $fila['cara01lab_tiempoacadem'];
		$DATA['cara01lab_tipoempresa'] = $fila['cara01lab_tipoempresa'];
		$DATA['cara01lab_tiempoindepen'] = $fila['cara01lab_tiempoindepen'];
		$DATA['cara01lab_debebusctrab'] = $fila['cara01lab_debebusctrab'];
		$DATA['cara01lab_origendinero'] = $fila['cara01lab_origendinero'];
		$DATA['cara01bien_baloncesto'] = $fila['cara01bien_baloncesto'];
		$DATA['cara01bien_voleibol'] = $fila['cara01bien_voleibol'];
		$DATA['cara01bien_futbolsala'] = $fila['cara01bien_futbolsala'];
		$DATA['cara01bien_artesmarc'] = $fila['cara01bien_artesmarc'];
		$DATA['cara01bien_tenisdemesa'] = $fila['cara01bien_tenisdemesa'];
		$DATA['cara01bien_ajedrez'] = $fila['cara01bien_ajedrez'];
		$DATA['cara01bien_juegosautoc'] = $fila['cara01bien_juegosautoc'];
		$DATA['cara01bien_interesrepdeporte'] = $fila['cara01bien_interesrepdeporte'];
		$DATA['cara01bien_deporteint'] = $fila['cara01bien_deporteint'];
		$DATA['cara01bien_teatro'] = $fila['cara01bien_teatro'];
		$DATA['cara01bien_danza'] = $fila['cara01bien_danza'];
		$DATA['cara01bien_musica'] = $fila['cara01bien_musica'];
		$DATA['cara01bien_circo'] = $fila['cara01bien_circo'];
		$DATA['cara01bien_artplast'] = $fila['cara01bien_artplast'];
		$DATA['cara01bien_cuenteria'] = $fila['cara01bien_cuenteria'];
		$DATA['cara01bien_interesreparte'] = $fila['cara01bien_interesreparte'];
		$DATA['cara01bien_arteint'] = $fila['cara01bien_arteint'];
		$DATA['cara01bien_interpreta'] = $fila['cara01bien_interpreta'];
		$DATA['cara01bien_nivelinter'] = $fila['cara01bien_nivelinter'];
		$DATA['cara01bien_danza_mod'] = $fila['cara01bien_danza_mod'];
		$DATA['cara01bien_danza_clas'] = $fila['cara01bien_danza_clas'];
		$DATA['cara01bien_danza_cont'] = $fila['cara01bien_danza_cont'];
		$DATA['cara01bien_danza_folk'] = $fila['cara01bien_danza_folk'];
		$DATA['cara01bien_niveldanza'] = $fila['cara01bien_niveldanza'];
		$DATA['cara01bien_emprendedor'] = $fila['cara01bien_emprendedor'];
		$DATA['cara01bien_nombreemp'] = $fila['cara01bien_nombreemp'];
		$DATA['cara01bien_capacempren'] = $fila['cara01bien_capacempren'];
		$DATA['cara01bien_tipocapacita'] = $fila['cara01bien_tipocapacita'];
		$DATA['cara01bien_impvidasalud'] = $fila['cara01bien_impvidasalud'];
		$DATA['cara01bien_estraautocuid'] = $fila['cara01bien_estraautocuid'];
		$DATA['cara01bien_pv_personal'] = $fila['cara01bien_pv_personal'];
		$DATA['cara01bien_pv_familiar'] = $fila['cara01bien_pv_familiar'];
		$DATA['cara01bien_pv_academ'] = $fila['cara01bien_pv_academ'];
		$DATA['cara01bien_pv_labora'] = $fila['cara01bien_pv_labora'];
		$DATA['cara01bien_pv_pareja'] = $fila['cara01bien_pv_pareja'];
		$DATA['cara01bien_amb'] = $fila['cara01bien_amb'];
		$DATA['cara01bien_amb_agu'] = $fila['cara01bien_amb_agu'];
		$DATA['cara01bien_amb_bom'] = $fila['cara01bien_amb_bom'];
		$DATA['cara01bien_amb_car'] = $fila['cara01bien_amb_car'];
		$DATA['cara01bien_amb_info'] = $fila['cara01bien_amb_info'];
		$DATA['cara01bien_amb_temas'] = $fila['cara01bien_amb_temas'];
		$DATA['cara01psico_costoemocion'] = $fila['cara01psico_costoemocion'];
		$DATA['cara01psico_reaccionimpre'] = $fila['cara01psico_reaccionimpre'];
		$DATA['cara01psico_estres'] = $fila['cara01psico_estres'];
		$DATA['cara01psico_pocotiempo'] = $fila['cara01psico_pocotiempo'];
		$DATA['cara01psico_actitudvida'] = $fila['cara01psico_actitudvida'];
		$DATA['cara01psico_duda'] = $fila['cara01psico_duda'];
		$DATA['cara01psico_problemapers'] = $fila['cara01psico_problemapers'];
		$DATA['cara01psico_satisfaccion'] = $fila['cara01psico_satisfaccion'];
		$DATA['cara01psico_discusiones'] = $fila['cara01psico_discusiones'];
		$DATA['cara01psico_atencion'] = $fila['cara01psico_atencion'];
		$DATA['cara01niveldigital'] = $fila['cara01niveldigital'];
		$DATA['cara01nivellectura'] = $fila['cara01nivellectura'];
		$DATA['cara01nivelrazona'] = $fila['cara01nivelrazona'];
		$DATA['cara01nivelingles'] = $fila['cara01nivelingles'];
		$DATA['cara01idconsejero'] = $fila['cara01idconsejero'];
		$DATA['cara01fechainicio'] = $fila['cara01fechainicio'];
		$DATA['cara01telefono1'] = $fila['cara01telefono1'];
		$DATA['cara01telefono2'] = $fila['cara01telefono2'];
		$DATA['cara01correopersonal'] = $fila['cara01correopersonal'];
		$DATA['cara01idprograma'] = $fila['cara01idprograma'];
		$DATA['cara01idescuela'] = $fila['cara01idescuela'];
		$DATA['cara01fichabiolog'] = $fila['cara01fichabiolog'];
		$DATA['cara01nivelbiolog'] = $fila['cara01nivelbiolog'];
		$DATA['cara01fichafisica'] = $fila['cara01fichafisica'];
		$DATA['cara01nivelfisica'] = $fila['cara01nivelfisica'];
		$DATA['cara01fichaquimica'] = $fila['cara01fichaquimica'];
		$DATA['cara01nivelquimica'] = $fila['cara01nivelquimica'];
		$DATA['cara01tipocaracterizacion'] = $fila['cara01tipocaracterizacion'];
		$DATA['cara01perayuda'] = $fila['cara01perayuda'];
		$DATA['cara01perotraayuda'] = $fila['cara01perotraayuda'];
		$DATA['cara01discsensorialotra'] = $fila['cara01discsensorialotra'];
		$DATA['cara01discfisicaotra'] = $fila['cara01discfisicaotra'];
		$DATA['cara01disccognitivaotra'] = $fila['cara01disccognitivaotra'];
		$DATA['cara01idcursocatedra'] = $fila['cara01idcursocatedra'];
		$DATA['cara01idgrupocatedra'] = $fila['cara01idgrupocatedra'];
		$DATA['cara01factordescper'] = $fila['cara01factordescper'];
		$DATA['cara01factordescpsico'] = $fila['cara01factordescpsico'];
		$DATA['cara01factordescinsti'] = $fila['cara01factordescinsti'];
		$DATA['cara01factordescacad'] = $fila['cara01factordescacad'];
		$DATA['cara01factordesc'] = $fila['cara01factordesc'];
		$DATA['cara01criteriodesc'] = $fila['cara01criteriodesc'];
		$DATA['cara01desertor'] = $fila['cara01desertor'];
		$DATA['cara01factorprincipaldesc'] = $fila['cara01factorprincipaldesc'];
		$DATA['cara01psico_puntaje'] = $fila['cara01psico_puntaje'];
		$DATA['cara01numacompanamentos'] = $fila['cara01numacompanamentos'];
		$DATA['cara01idperiodoacompana'] = $fila['cara01idperiodoacompana'];
		$DATA['cara01fechacierreacom'] = $fila['cara01fechacierreacom'];
		$DATA['cara01formaacomp'] = $fila['cara01formaacomp'];
		$DATA['cara01factorriesgoacomp'] = $fila['cara01factorriesgoacomp'];
		$DATA['cara01factorprincpermanencia'] = $fila['cara01factorprincpermanencia'];
		$DATA['cara01discversion'] = $fila['cara01discversion'];
		$DATA['cara01discv2sensorial'] = $fila['cara01discv2sensorial'];
		$DATA['cara02discv2intelectura'] = $fila['cara02discv2intelectura'];
		$DATA['cara02discv2fisica'] = $fila['cara02discv2fisica'];
		$DATA['cara02discv2psico'] = $fila['cara02discv2psico'];
		$DATA['cara02discv2sistemica'] = $fila['cara02discv2sistemica'];
		$DATA['cara02discv2sistemicaotro'] = $fila['cara02discv2sistemicaotro'];
		$DATA['cara02discv2multiple'] = $fila['cara02discv2multiple'];
		$DATA['cara02discv2multipleotro'] = $fila['cara02discv2multipleotro'];
		$DATA['cara02talentoexcepcional'] = $fila['cara02talentoexcepcional'];
		$DATA['cara01discv2tiene'] = $fila['cara01discv2tiene'];
		$DATA['cara01discv2trastaprende'] = $fila['cara01discv2trastaprende'];
		$DATA['cara01discv2soporteorigen'] = $fila['cara01discv2soporteorigen'];
		$DATA['cara01discv2archivoorigen'] = $fila['cara01discv2archivoorigen'];
		$DATA['cara01discv2trastornos'] = $fila['cara01discv2trastornos'];
		$DATA['cara01discv2contalento'] = $fila['cara01discv2contalento'];
		$DATA['cara01discv2condicionmedica'] = $fila['cara01discv2condicionmedica'];
		$DATA['cara01discv2condmeddet'] = $fila['cara01discv2condmeddet'];
		$DATA['cara01discv2pruebacoeficiente'] = $fila['cara01discv2pruebacoeficiente'];
		$sSQLcondi = 'cara44id=' . $DATA['cara01id'] . '';
		$sSQL = 'SELECT * FROM cara44encuesta WHERE ' . $sSQLcondi;
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			$DATA['cara44campesinado'] = $fila['cara44campesinado'];
			$DATA['cara44sexoversion'] = $fila['cara44sexoversion'];
			$DATA['cara44sexov1identidadgen'] = $fila['cara44sexov1identidadgen'];
			$DATA['cara44sexov1orientasexo'] = $fila['cara44sexov1orientasexo'];
			$DATA['cara44bienversion'] = $fila['cara44bienversion'];
			$DATA['cara44bienv2altoren'] = $fila['cara44bienv2altoren'];
			$DATA['cara44bienv2atletismo'] = $fila['cara44bienv2atletismo'];
			$DATA['cara44bienv2baloncesto'] = $fila['cara44bienv2baloncesto'];
			$DATA['cara44bienv2futbol'] = $fila['cara44bienv2futbol'];
			$DATA['cara44bienv2gimnasia'] = $fila['cara44bienv2gimnasia'];
			$DATA['cara44bienv2natacion'] = $fila['cara44bienv2natacion'];
			$DATA['cara44bienv2voleibol'] = $fila['cara44bienv2voleibol'];
			$DATA['cara44bienv2tenis'] = $fila['cara44bienv2tenis'];
			$DATA['cara44bienv2paralimpico'] = $fila['cara44bienv2paralimpico'];
			$DATA['cara44bienv2otrodeporte'] = $fila['cara44bienv2otrodeporte'];
			$DATA['cara44bienv2otrodeportedetalle'] = $fila['cara44bienv2otrodeportedetalle'];
			$DATA['cara44bienv2activdanza'] = $fila['cara44bienv2activdanza'];
			$DATA['cara44bienv2activmusica'] = $fila['cara44bienv2activmusica'];
			$DATA['cara44bienv2activteatro'] = $fila['cara44bienv2activteatro'];
			$DATA['cara44bienv2activartes'] = $fila['cara44bienv2activartes'];
			$DATA['cara44bienv2activliteratura'] = $fila['cara44bienv2activliteratura'];
			$DATA['cara44bienv2activculturalotra'] = $fila['cara44bienv2activculturalotra'];
			$DATA['cara44bienv2activculturalotradetalle'] = $fila['cara44bienv2activculturalotradetalle'];
			$DATA['cara44bienv2evenfestfolc'] = $fila['cara44bienv2evenfestfolc'];
			$DATA['cara44bienv2evenexpoarte'] = $fila['cara44bienv2evenexpoarte'];
			$DATA['cara44bienv2evenhistarte'] = $fila['cara44bienv2evenhistarte'];
			$DATA['cara44bienv2evengalfoto'] = $fila['cara44bienv2evengalfoto'];
			$DATA['cara44bienv2evenliteratura'] = $fila['cara44bienv2evenliteratura'];
			$DATA['cara44bienv2eventeatro'] = $fila['cara44bienv2eventeatro'];
			$DATA['cara44bienv2evencine'] = $fila['cara44bienv2evencine'];
			$DATA['cara44bienv2evenculturalotro'] = $fila['cara44bienv2evenculturalotro'];
			$DATA['cara44bienv2evenculturalotrodetalle'] = $fila['cara44bienv2evenculturalotrodetalle'];
			$DATA['cara44bienv2emprendimiento'] = $fila['cara44bienv2emprendimiento'];
			$DATA['cara44bienv2empresa'] = $fila['cara44bienv2empresa'];
			$DATA['cara44bienv2emprenrecursos'] = $fila['cara44bienv2emprenrecursos'];
			$DATA['cara44bienv2emprenconocim'] = $fila['cara44bienv2emprenconocim'];
			$DATA['cara44bienv2emprenplan'] = $fila['cara44bienv2emprenplan'];
			$DATA['cara44bienv2emprenejecutar'] = $fila['cara44bienv2emprenejecutar'];
			$DATA['cara44bienv2emprenfortconocim'] = $fila['cara44bienv2emprenfortconocim'];
			$DATA['cara44bienv2emprenidentproblema'] = $fila['cara44bienv2emprenidentproblema'];
			$DATA['cara44bienv2emprenotro'] = $fila['cara44bienv2emprenotro'];
			$DATA['cara44bienv2emprenotrodetalle'] = $fila['cara44bienv2emprenotrodetalle'];
			$DATA['cara44bienv2emprenmarketing'] = $fila['cara44bienv2emprenmarketing'];
			$DATA['cara44bienv2emprenplannegocios'] = $fila['cara44bienv2emprenplannegocios'];
			$DATA['cara44bienv2emprenideas'] = $fila['cara44bienv2emprenideas'];
			$DATA['cara44bienv2emprencreacion'] = $fila['cara44bienv2emprencreacion'];
			$DATA['cara44bienv2saludfacteconom'] = $fila['cara44bienv2saludfacteconom'];
			$DATA['cara44bienv2saludpreocupacion'] = $fila['cara44bienv2saludpreocupacion'];
			$DATA['cara44bienv2saludconsumosust'] = $fila['cara44bienv2saludconsumosust'];
			$DATA['cara44bienv2saludinsomnio'] = $fila['cara44bienv2saludinsomnio'];
			$DATA['cara44bienv2saludclimalab'] = $fila['cara44bienv2saludclimalab'];
			$DATA['cara44bienv2saludalimenta'] = $fila['cara44bienv2saludalimenta'];
			$DATA['cara44bienv2saludemocion'] = $fila['cara44bienv2saludemocion'];
			$DATA['cara44bienv2saludestado'] = $fila['cara44bienv2saludestado'];
			$DATA['cara44bienv2saludmedita'] = $fila['cara44bienv2saludmedita'];
			$DATA['cara44bienv2crecimedusexual'] = $fila['cara44bienv2crecimedusexual'];
			$DATA['cara44bienv2crecimcultciudad'] = $fila['cara44bienv2crecimcultciudad'];
			$DATA['cara44bienv2crecimrelpareja'] = $fila['cara44bienv2crecimrelpareja'];
			$DATA['cara44bienv2crecimrelinterp'] = $fila['cara44bienv2crecimrelinterp'];
			$DATA['cara44bienv2crecimdinamicafam'] = $fila['cara44bienv2crecimdinamicafam'];
			$DATA['cara44bienv2crecimautoestima'] = $fila['cara44bienv2crecimautoestima'];
			$DATA['cara44bienv2creciminclusion'] = $fila['cara44bienv2creciminclusion'];
			$DATA['cara44bienv2creciminteliemoc'] = $fila['cara44bienv2creciminteliemoc'];
			$DATA['cara44bienv2crecimcultural'] = $fila['cara44bienv2crecimcultural'];
			$DATA['cara44bienv2crecimartistico'] = $fila['cara44bienv2crecimartistico'];
			$DATA['cara44bienv2crecimdeporte'] = $fila['cara44bienv2crecimdeporte'];
			$DATA['cara44bienv2crecimambiente'] = $fila['cara44bienv2crecimambiente'];
			$DATA['cara44bienv2crecimhabsocio'] = $fila['cara44bienv2crecimhabsocio'];
			$DATA['cara44bienv2ambienbasura'] = $fila['cara44bienv2ambienbasura'];
			$DATA['cara44bienv2ambienreutiliza'] = $fila['cara44bienv2ambienreutiliza'];
			$DATA['cara44bienv2ambienluces'] = $fila['cara44bienv2ambienluces'];
			$DATA['cara44bienv2ambienfrutaverd'] = $fila['cara44bienv2ambienfrutaverd'];
			$DATA['cara44bienv2ambienenchufa'] = $fila['cara44bienv2ambienenchufa'];
			$DATA['cara44bienv2ambiengrifo'] = $fila['cara44bienv2ambiengrifo'];
			$DATA['cara44bienv2ambienbicicleta'] = $fila['cara44bienv2ambienbicicleta'];
			$DATA['cara44bienv2ambientranspub'] = $fila['cara44bienv2ambientranspub'];
			$DATA['cara44bienv2ambienducha'] = $fila['cara44bienv2ambienducha'];
			$DATA['cara44bienv2ambiencaminata'] = $fila['cara44bienv2ambiencaminata'];
			$DATA['cara44bienv2ambiensiembra'] = $fila['cara44bienv2ambiensiembra'];
			$DATA['cara44bienv2ambienconferencia'] = $fila['cara44bienv2ambienconferencia'];
			$DATA['cara44bienv2ambienrecicla'] = $fila['cara44bienv2ambienrecicla'];
			$DATA['cara44bienv2ambienotraactiv'] = $fila['cara44bienv2ambienotraactiv'];
			$DATA['cara44bienv2ambienotraactivdetalle'] = $fila['cara44bienv2ambienotraactivdetalle'];
			$DATA['cara44bienv2ambienreforest'] = $fila['cara44bienv2ambienreforest'];
			$DATA['cara44bienv2ambienmovilidad'] = $fila['cara44bienv2ambienmovilidad'];
			$DATA['cara44bienv2ambienclimatico'] = $fila['cara44bienv2ambienclimatico'];
			$DATA['cara44bienv2ambienecofemin'] = $fila['cara44bienv2ambienecofemin'];
			$DATA['cara44bienv2ambienbiodiver'] = $fila['cara44bienv2ambienbiodiver'];
			$DATA['cara44bienv2ambienecologia'] = $fila['cara44bienv2ambienecologia'];
			$DATA['cara44bienv2ambieneconomia'] = $fila['cara44bienv2ambieneconomia'];
			$DATA['cara44bienv2ambienrecnatura'] = $fila['cara44bienv2ambienrecnatura'];
			$DATA['cara44bienv2ambienreciclaje'] = $fila['cara44bienv2ambienreciclaje'];
			$DATA['cara44bienv2ambienmascota'] = $fila['cara44bienv2ambienmascota'];
			$DATA['cara44bienv2ambiencartohum'] = $fila['cara44bienv2ambiencartohum'];
			$DATA['cara44bienv2ambienespiritu'] = $fila['cara44bienv2ambienespiritu'];
			$DATA['cara44bienv2ambiencarga'] = $fila['cara44bienv2ambiencarga'];
			$DATA['cara44bienv2ambienotroenfoq'] = $fila['cara44bienv2ambienotroenfoq'];
			$DATA['cara44bienv2ambienotroenfoqdetalle'] = $fila['cara44bienv2ambienotroenfoqdetalle'];
			$DATA['cara44fam_madrecabeza'] = $fila['cara44fam_madrecabeza'];
			$DATA['cara44acadhatenidorecesos'] = $fila['cara44acadhatenidorecesos'];
			$DATA['cara44acadrazonreceso'] = $fila['cara44acadrazonreceso'];
			$DATA['cara44acadrazonrecesodetalle'] = $fila['cara44acadrazonrecesodetalle'];
			$DATA['cara44campus_usocorreounad'] = $fila['cara44campus_usocorreounad'];
			$DATA['cara44campus_usocorreounadno'] = $fila['cara44campus_usocorreounadno'];
			$DATA['cara44campus_usocorreounadnodetalle'] = $fila['cara44campus_usocorreounadnodetalle'];
			$DATA['cara44campus_medioactivunad'] = $fila['cara44campus_medioactivunad'];
			$DATA['cara44campus_medioactivunaddetalle'] = $fila['cara44campus_medioactivunaddetalle'];
		}
		$bcargo = true;
		$DATA['paso'] = 2;
		$DATA['boculta2301'] = 0;
		$bLimpiaHijos = true;
	} else {
		$DATA['paso'] = 0;
	}
	return array($DATA, $sError, $iTipoError, $sDebug);
}
function f2301_Cerrar($cara01id, $objDB, $bDebug = false)
{
	$sInfo = '';
	$sDebug = '';
	return array($sInfo, $sDebug);
}
function f2301_VerificarPreguntas($cara01id, $idBloque, $objDB, $bDebug = false)
{
	$sDebug = '';
	$sPreguntas = '';
	$iPuntaje = 0;
	$sSQL = 'SELECT cara10consec, cara10idrpta, cara10puntaje FROM cara10pregprueba WHERE cara10idcara=' . $cara01id . ' AND cara10idbloque=' . $idBloque . '';
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' Consultando preguntas: ' . $sSQL . '<br>';
	}
	$tabla = $objDB->ejecutasql($sSQL);
	while ($fila = $objDB->sf($tabla)) {
		if ($fila['cara10idrpta'] == 0) {
			$sPreguntas = $sPreguntas . $fila['cara10consec'] . ', ';
		} else {
			$iPuntaje = $iPuntaje + $fila['cara10puntaje'];
		}
	}
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' Resultado de la ficha ' . $idBloque . ' [' . $sPreguntas . ' - ' . $iPuntaje . ']<br>';
	}
	return array($sPreguntas, $iPuntaje, $sDebug);
}
function f2301_db_GuardarV2($DATA, $objDB, $bDebug = false)
{
	$iCodModulo = 2301;
	$bAudita[2] = true;
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
	$mensajes_2344 = $APP->rutacomun . 'lg/lg_2344_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_2344)) {
		$mensajes_2344 = $APP->rutacomun . 'lg/lg_2344_es.php';
	}
	require $mensajes_todas;
	require $mensajes_2301;
	require $mensajes_2344;
	$sError = '';
	$iTipoError = 0;
	$sDebug = '';
	$bCerrando = false;
	$sErrorCerrando = '';
	// -- Se inicia validando todas las posibles entradas de usuario.
	/*
	if (isset($DATA['cara01idperaca'])==0){$DATA['cara01idperaca']='';}
	if (isset($DATA['cara01idtercero'])==0){$DATA['cara01idtercero']='';}
	if (isset($DATA['cara01id'])==0){$DATA['cara01id']='';}
	if (isset($DATA['cara01completa'])==0){$DATA['cara01completa']='';}
	if (isset($DATA['cara01fichaper'])==0){$DATA['cara01fichaper']='';}
	if (isset($DATA['cara01fichafam'])==0){$DATA['cara01fichafam']='';}
	if (isset($DATA['cara01fichaaca'])==0){$DATA['cara01fichaaca']='';}
	if (isset($DATA['cara01fichalab'])==0){$DATA['cara01fichalab']='';}
	if (isset($DATA['cara01fichabien'])==0){$DATA['cara01fichabien']='';}
	if (isset($DATA['cara01fichapsico'])==0){$DATA['cara01fichapsico']='';}
	if (isset($DATA['cara01fichadigital'])==0){$DATA['cara01fichadigital']='';}
	if (isset($DATA['cara01fichalectura'])==0){$DATA['cara01fichalectura']='';}
	if (isset($DATA['cara01ficharazona'])==0){$DATA['cara01ficharazona']='';}
	if (isset($DATA['cara01fichaingles'])==0){$DATA['cara01fichaingles']='';}
	if (isset($DATA['cara01fechaencuesta'])==0){$DATA['cara01fechaencuesta']='';}
	if (isset($DATA['cara01sexo'])==0){$DATA['cara01sexo']='';}
	if (isset($DATA['cara01pais'])==0){$DATA['cara01pais']='';}
	if (isset($DATA['cara01depto'])==0){$DATA['cara01depto']='';}
	if (isset($DATA['cara01ciudad'])==0){$DATA['cara01ciudad']='';}
	if (isset($DATA['cara01nomciudad'])==0){$DATA['cara01nomciudad']='';}
	if (isset($DATA['cara01direccion'])==0){$DATA['cara01direccion']='';}
	if (isset($DATA['cara01estrato'])==0){$DATA['cara01estrato']='';}
	if (isset($DATA['cara01zonares'])==0){$DATA['cara01zonares']='';}
	if (isset($DATA['cara01estcivil'])==0){$DATA['cara01estcivil']='';}
	if (isset($DATA['cara01nomcontacto'])==0){$DATA['cara01nomcontacto']='';}
	if (isset($DATA['cara01parentezcocontacto'])==0){$DATA['cara01parentezcocontacto']='';}
	if (isset($DATA['cara01celcontacto'])==0){$DATA['cara01celcontacto']='';}
	if (isset($DATA['cara01correocontacto'])==0){$DATA['cara01correocontacto']='';}
	if (isset($DATA['cara01idzona'])==0){$DATA['cara01idzona']='';}
	if (isset($DATA['cara01idcead'])==0){$DATA['cara01idcead']='';}
	if (isset($DATA['cara01matconvenio'])==0){$DATA['cara01matconvenio']='';}
	if (isset($DATA['cara01raizal'])==0){$DATA['cara01raizal']='';}
	if (isset($DATA['cara01palenquero'])==0){$DATA['cara01palenquero']='';}
	if (isset($DATA['cara01afrocolombiano'])==0){$DATA['cara01afrocolombiano']='';}
	if (isset($DATA['cara01otracomunnegras'])==0){$DATA['cara01otracomunnegras']='';}
	if (isset($DATA['cara01rom'])==0){$DATA['cara01rom']='';}
	if (isset($DATA['cara01indigenas'])==0){$DATA['cara01indigenas']='';}
	if (isset($DATA['cara01victimadesplazado'])==0){$DATA['cara01victimadesplazado']='';}
	if (isset($DATA['cara01fechaconfirmadesp'])==0){$DATA['cara01fechaconfirmadesp']='';}
	if (isset($DATA['cara01victimaacr'])==0){$DATA['cara01victimaacr']='';}
	if (isset($DATA['cara01fechaconfirmacr'])==0){$DATA['cara01fechaconfirmacr']='';}
	if (isset($DATA['cara01inpecfuncionario'])==0){$DATA['cara01inpecfuncionario']='';}
	if (isset($DATA['cara01inpecrecluso'])==0){$DATA['cara01inpecrecluso']='';}
	if (isset($DATA['cara01inpectiempocondena'])==0){$DATA['cara01inpectiempocondena']='';}
	if (isset($DATA['cara01centroreclusion'])==0){$DATA['cara01centroreclusion']='';}
	if (isset($DATA['cara01discsensorial'])==0){$DATA['cara01discsensorial']='';}
	if (isset($DATA['cara01discfisica'])==0){$DATA['cara01discfisica']='';}
	if (isset($DATA['cara01disccognitiva'])==0){$DATA['cara01disccognitiva']='';}
	if (isset($DATA['cara01fechaconfirmadisc'])==0){$DATA['cara01fechaconfirmadisc']='';}
	if (isset($DATA['cara01fam_tipovivienda'])==0){$DATA['cara01fam_tipovivienda']='';}
	if (isset($DATA['cara01fam_vivecon'])==0){$DATA['cara01fam_vivecon']='';}
	if (isset($DATA['cara01fam_numpersgrupofam'])==0){$DATA['cara01fam_numpersgrupofam']='';}
	if (isset($DATA['cara01fam_hijos'])==0){$DATA['cara01fam_hijos']='';}
	if (isset($DATA['cara01fam_personasacargo'])==0){$DATA['cara01fam_personasacargo']='';}
	if (isset($DATA['cara01fam_dependeecon'])==0){$DATA['cara01fam_dependeecon']='';}
	if (isset($DATA['cara01fam_escolaridadpadre'])==0){$DATA['cara01fam_escolaridadpadre']='';}
	if (isset($DATA['cara01fam_escolaridadmadre'])==0){$DATA['cara01fam_escolaridadmadre']='';}
	if (isset($DATA['cara01fam_numhermanos'])==0){$DATA['cara01fam_numhermanos']='';}
	if (isset($DATA['cara01fam_posicionherm'])==0){$DATA['cara01fam_posicionherm']='';}
	if (isset($DATA['cara01fam_familiaunad'])==0){$DATA['cara01fam_familiaunad']='';}
	if (isset($DATA['cara01acad_tipocolegio'])==0){$DATA['cara01acad_tipocolegio']='';}
	if (isset($DATA['cara01acad_modalidadbach'])==0){$DATA['cara01acad_modalidadbach']='';}
	if (isset($DATA['cara01acad_estudioprev'])==0){$DATA['cara01acad_estudioprev']='';}
	if (isset($DATA['cara01acad_ultnivelest'])==0){$DATA['cara01acad_ultnivelest']='';}
	if (isset($DATA['cara01acad_obtubodiploma'])==0){$DATA['cara01acad_obtubodiploma']='';}
	if (isset($DATA['cara01acad_hatomadovirtual'])==0){$DATA['cara01acad_hatomadovirtual']='';}
	if (isset($DATA['cara01acad_tiemposinest'])==0){$DATA['cara01acad_tiemposinest']='';}
	if (isset($DATA['cara01acad_razonestudio'])==0){$DATA['cara01acad_razonestudio']='';}
	if (isset($DATA['cara01acad_primeraopc'])==0){$DATA['cara01acad_primeraopc']='';}
	if (isset($DATA['cara01acad_programagusto'])==0){$DATA['cara01acad_programagusto']='';}
	if (isset($DATA['cara01acad_razonunad'])==0){$DATA['cara01acad_razonunad']='';}
	if (isset($DATA['cara01campus_compescrito'])==0){$DATA['cara01campus_compescrito']='';}
	if (isset($DATA['cara01campus_portatil'])==0){$DATA['cara01campus_portatil']='';}
	if (isset($DATA['cara01campus_tableta'])==0){$DATA['cara01campus_tableta']='';}
	if (isset($DATA['cara01campus_telefono'])==0){$DATA['cara01campus_telefono']='';}
	if (isset($DATA['cara01campus_energia'])==0){$DATA['cara01campus_energia']='';}
	if (isset($DATA['cara01campus_internetreside'])==0){$DATA['cara01campus_internetreside']='';}
	if (isset($DATA['cara01campus_expvirtual'])==0){$DATA['cara01campus_expvirtual']='';}
	if (isset($DATA['cara01campus_ofimatica'])==0){$DATA['cara01campus_ofimatica']='';}
	if (isset($DATA['cara01campus_foros'])==0){$DATA['cara01campus_foros']='';}
	if (isset($DATA['cara01campus_conversiones'])==0){$DATA['cara01campus_conversiones']='';}
	if (isset($DATA['cara01campus_usocorreo'])==0){$DATA['cara01campus_usocorreo']='';}
	if (isset($DATA['cara01campus_aprendtexto'])==0){$DATA['cara01campus_aprendtexto']='';}
	if (isset($DATA['cara01campus_aprendvideo'])==0){$DATA['cara01campus_aprendvideo']='';}
	if (isset($DATA['cara01campus_aprendmapas'])==0){$DATA['cara01campus_aprendmapas']='';}
	if (isset($DATA['cara01campus_aprendeanima'])==0){$DATA['cara01campus_aprendeanima']='';}
	if (isset($DATA['cara01campus_mediocomunica'])==0){$DATA['cara01campus_mediocomunica']='';}
	if (isset($DATA['cara01lab_situacion'])==0){$DATA['cara01lab_situacion']='';}
	if (isset($DATA['cara01lab_sector'])==0){$DATA['cara01lab_sector']='';}
	if (isset($DATA['cara01lab_caracterjuri'])==0){$DATA['cara01lab_caracterjuri']='';}
	if (isset($DATA['cara01lab_cargo'])==0){$DATA['cara01lab_cargo']='';}
	if (isset($DATA['cara01lab_antiguedad'])==0){$DATA['cara01lab_antiguedad']='';}
	if (isset($DATA['cara01lab_tipocontrato'])==0){$DATA['cara01lab_tipocontrato']='';}
	if (isset($DATA['cara01lab_rangoingreso'])==0){$DATA['cara01lab_rangoingreso']='';}
	if (isset($DATA['cara01lab_tiempoacadem'])==0){$DATA['cara01lab_tiempoacadem']='';}
	if (isset($DATA['cara01lab_tipoempresa'])==0){$DATA['cara01lab_tipoempresa']='';}
	if (isset($DATA['cara01lab_tiempoindepen'])==0){$DATA['cara01lab_tiempoindepen']='';}
	if (isset($DATA['cara01lab_debebusctrab'])==0){$DATA['cara01lab_debebusctrab']='';}
	if (isset($DATA['cara01lab_origendinero'])==0){$DATA['cara01lab_origendinero']='';}
	if (isset($DATA['cara01bien_baloncesto'])==0){$DATA['cara01bien_baloncesto']='';}
	if (isset($DATA['cara01bien_voleibol'])==0){$DATA['cara01bien_voleibol']='';}
	if (isset($DATA['cara01bien_futbolsala'])==0){$DATA['cara01bien_futbolsala']='';}
	if (isset($DATA['cara01bien_artesmarc'])==0){$DATA['cara01bien_artesmarc']='';}
	if (isset($DATA['cara01bien_tenisdemesa'])==0){$DATA['cara01bien_tenisdemesa']='';}
	if (isset($DATA['cara01bien_ajedrez'])==0){$DATA['cara01bien_ajedrez']='';}
	if (isset($DATA['cara01bien_juegosautoc'])==0){$DATA['cara01bien_juegosautoc']='';}
	if (isset($DATA['cara01bien_interesrepdeporte'])==0){$DATA['cara01bien_interesrepdeporte']='';}
	if (isset($DATA['cara01bien_deporteint'])==0){$DATA['cara01bien_deporteint']='';}
	if (isset($DATA['cara01bien_teatro'])==0){$DATA['cara01bien_teatro']='';}
	if (isset($DATA['cara01bien_danza'])==0){$DATA['cara01bien_danza']='';}
	if (isset($DATA['cara01bien_musica'])==0){$DATA['cara01bien_musica']='';}
	if (isset($DATA['cara01bien_circo'])==0){$DATA['cara01bien_circo']='';}
	if (isset($DATA['cara01bien_artplast'])==0){$DATA['cara01bien_artplast']='';}
	if (isset($DATA['cara01bien_cuenteria'])==0){$DATA['cara01bien_cuenteria']='';}
	if (isset($DATA['cara01bien_interesreparte'])==0){$DATA['cara01bien_interesreparte']='';}
	if (isset($DATA['cara01bien_arteint'])==0){$DATA['cara01bien_arteint']='';}
	if (isset($DATA['cara01bien_interpreta'])==0){$DATA['cara01bien_interpreta']='';}
	if (isset($DATA['cara01bien_nivelinter'])==0){$DATA['cara01bien_nivelinter']='';}
	if (isset($DATA['cara01bien_danza_mod'])==0){$DATA['cara01bien_danza_mod']='';}
	if (isset($DATA['cara01bien_danza_clas'])==0){$DATA['cara01bien_danza_clas']='';}
	if (isset($DATA['cara01bien_danza_cont'])==0){$DATA['cara01bien_danza_cont']='';}
	if (isset($DATA['cara01bien_danza_folk'])==0){$DATA['cara01bien_danza_folk']='';}
	if (isset($DATA['cara01bien_niveldanza'])==0){$DATA['cara01bien_niveldanza']='';}
	if (isset($DATA['cara01bien_emprendedor'])==0){$DATA['cara01bien_emprendedor']='';}
	if (isset($DATA['cara01bien_nombreemp'])==0){$DATA['cara01bien_nombreemp']='';}
	if (isset($DATA['cara01bien_capacempren'])==0){$DATA['cara01bien_capacempren']='';}
	if (isset($DATA['cara01bien_tipocapacita'])==0){$DATA['cara01bien_tipocapacita']='';}
	if (isset($DATA['cara01bien_impvidasalud'])==0){$DATA['cara01bien_impvidasalud']='';}
	if (isset($DATA['cara01bien_estraautocuid'])==0){$DATA['cara01bien_estraautocuid']='';}
	if (isset($DATA['cara01bien_pv_personal'])==0){$DATA['cara01bien_pv_personal']='';}
	if (isset($DATA['cara01bien_pv_familiar'])==0){$DATA['cara01bien_pv_familiar']='';}
	if (isset($DATA['cara01bien_pv_academ'])==0){$DATA['cara01bien_pv_academ']='';}
	if (isset($DATA['cara01bien_pv_labora'])==0){$DATA['cara01bien_pv_labora']='';}
	if (isset($DATA['cara01bien_pv_pareja'])==0){$DATA['cara01bien_pv_pareja']='';}
	if (isset($DATA['cara01bien_amb'])==0){$DATA['cara01bien_amb']='';}
	if (isset($DATA['cara01bien_amb_agu'])==0){$DATA['cara01bien_amb_agu']='';}
	if (isset($DATA['cara01bien_amb_bom'])==0){$DATA['cara01bien_amb_bom']='';}
	if (isset($DATA['cara01bien_amb_car'])==0){$DATA['cara01bien_amb_car']='';}
	if (isset($DATA['cara01bien_amb_info'])==0){$DATA['cara01bien_amb_info']='';}
	if (isset($DATA['cara01bien_amb_temas'])==0){$DATA['cara01bien_amb_temas']='';}
	if (isset($DATA['cara01psico_costoemocion'])==0){$DATA['cara01psico_costoemocion']='';}
	if (isset($DATA['cara01psico_reaccionimpre'])==0){$DATA['cara01psico_reaccionimpre']='';}
	if (isset($DATA['cara01psico_estres'])==0){$DATA['cara01psico_estres']='';}
	if (isset($DATA['cara01psico_pocotiempo'])==0){$DATA['cara01psico_pocotiempo']='';}
	if (isset($DATA['cara01psico_actitudvida'])==0){$DATA['cara01psico_actitudvida']='';}
	if (isset($DATA['cara01psico_duda'])==0){$DATA['cara01psico_duda']='';}
	if (isset($DATA['cara01psico_problemapers'])==0){$DATA['cara01psico_problemapers']='';}
	if (isset($DATA['cara01psico_satisfaccion'])==0){$DATA['cara01psico_satisfaccion']='';}
	if (isset($DATA['cara01psico_discusiones'])==0){$DATA['cara01psico_discusiones']='';}
	if (isset($DATA['cara01psico_atencion'])==0){$DATA['cara01psico_atencion']='';}
	if (isset($DATA['cara01niveldigital'])==0){$DATA['cara01niveldigital']='';}
	if (isset($DATA['cara01nivellectura'])==0){$DATA['cara01nivellectura']='';}
	if (isset($DATA['cara01nivelrazona'])==0){$DATA['cara01nivelrazona']='';}
	if (isset($DATA['cara01nivelingles'])==0){$DATA['cara01nivelingles']='';}
	if (isset($DATA['cara01fechainicio'])==0){$DATA['cara01fechainicio']='';}
	if (isset($DATA['cara01telefono1'])==0){$DATA['cara01telefono1']='';}
	if (isset($DATA['cara01telefono2'])==0){$DATA['cara01telefono2']='';}
	if (isset($DATA['cara01correopersonal'])==0){$DATA['cara01correopersonal']='';}
	if (isset($DATA['cara01fichabiolog'])==0){$DATA['cara01fichabiolog']='';}
	if (isset($DATA['cara01fichafisica'])==0){$DATA['cara01fichafisica']='';}
	if (isset($DATA['cara01fichaquimica'])==0){$DATA['cara01fichaquimica']='';}
	if (isset($DATA['cara01tipocaracterizacion'])==0){$DATA['cara01tipocaracterizacion']='';}
	*/
	if (isset($DATA['cara01discversion']) == 0) {
		$DATA['cara01discversion'] = 2;
	}
	if (isset($DATA['cara01discv2sensorial']) == 0) {
		$DATA['cara01discv2sensorial'] = '';
	}
	if (isset($DATA['cara02discv2intelectura']) == 0) {
		$DATA['cara02discv2intelectura'] = '';
	}
	if (isset($DATA['cara02discv2fisica']) == 0) {
		$DATA['cara02discv2fisica'] = '';
	}
	if (isset($DATA['cara02discv2psico']) == 0) {
		$DATA['cara02discv2psico'] = '';
	}
	if (isset($DATA['cara02discv2sistemica']) == 0) {
		$DATA['cara02discv2sistemica'] = '';
	}
	if (isset($DATA['cara02discv2sistemicaotro']) == 0) {
		$DATA['cara02discv2sistemicaotro'] = '';
	}
	if (isset($DATA['cara02discv2multiple']) == 0) {
		$DATA['cara02discv2multiple'] = '';
	}
	if (isset($DATA['cara02discv2multipleotro']) == 0) {
		$DATA['cara02discv2multipleotro'] = '';
	}
	if (isset($DATA['cara02talentoexcepcional']) == 0) {
		$DATA['cara02talentoexcepcional'] = '';
	}
	if (isset($DATA['cara01discv2tiene']) == 0) {
		$DATA['cara01discv2tiene'] = 0;
	}
	if (isset($DATA['cara01discv2trastaprende']) == 0) {
		$DATA['cara01discv2trastaprende'] = 0;
	}
	if (isset($DATA['cara01discv2trastornos']) == 0) {
		$DATA['cara01discv2trastornos'] = 0;
	}
	if (isset($DATA['cara01discv2contalento']) == 0) {
		$DATA['cara01discv2contalento'] = 0;
	}
	if (isset($DATA['cara01discv2condicionmedica']) == 0) {
		$DATA['cara01discv2condicionmedica'] = 0;
	}
	if (isset($DATA['cara01discv2condmeddet']) == 0) {
		$DATA['cara01discv2condmeddet'] = '';
	}
	if (isset($DATA['cara01discv2pruebacoeficiente']) == 0) {
		$DATA['cara01discv2pruebacoeficiente'] = '';
	}
	if (isset($DATA['cara44campesinado']) == 0) {
		$DATA['cara44campesinado'] = '';
	}
	if (isset($DATA['cara44sexoversion']) == 0) {
		$DATA['cara44sexoversion'] = 1;
	}
	if (isset($DATA['cara44sexov1identidadgen']) == 0) {
		$DATA['cara44sexov1identidadgen'] = 0;
	}
	if (isset($DATA['cara44sexov1orientasexo']) == 0) {
		$DATA['cara44sexov1orientasexo'] = 0;
	}
	if (isset($DATA['cara44bienversion']) == 0) {
		$DATA['cara44bienversion'] = 2;
	}
	if (isset($DATA['cara44bienv2altoren']) == 0) {
		$DATA['cara44bienv2altoren'] = '';
	}
	if (isset($DATA['cara44bienv2atletismo']) == 0) {
		$DATA['cara44bienv2atletismo'] = '';
	}
	if (isset($DATA['cara44bienv2baloncesto']) == 0) {
		$DATA['cara44bienv2baloncesto'] = '';
	}
	if (isset($DATA['cara44bienv2futbol']) == 0) {
		$DATA['cara44bienv2futbol'] = '';
	}
	if (isset($DATA['cara44bienv2gimnasia']) == 0) {
		$DATA['cara44bienv2gimnasia'] = '';
	}
	if (isset($DATA['cara44bienv2natacion']) == 0) {
		$DATA['cara44bienv2natacion'] = '';
	}
	if (isset($DATA['cara44bienv2voleibol']) == 0) {
		$DATA['cara44bienv2voleibol'] = '';
	}
	if (isset($DATA['cara44bienv2tenis']) == 0) {
		$DATA['cara44bienv2tenis'] = '';
	}
	if (isset($DATA['cara44bienv2paralimpico']) == 0) {
		$DATA['cara44bienv2paralimpico'] = '';
	}
	if (isset($DATA['cara44bienv2otrodeporte']) == 0) {
		$DATA['cara44bienv2otrodeporte'] = '';
	}
	if (isset($DATA['cara44bienv2otrodeportedetalle']) == 0) {
		$DATA['cara44bienv2otrodeportedetalle'] = '';
	}
	if (isset($DATA['cara44bienv2activdanza']) == 0) {
		$DATA['cara44bienv2activdanza'] = '';
	}
	if (isset($DATA['cara44bienv2activmusica']) == 0) {
		$DATA['cara44bienv2activmusica'] = '';
	}
	if (isset($DATA['cara44bienv2activteatro']) == 0) {
		$DATA['cara44bienv2activteatro'] = '';
	}
	if (isset($DATA['cara44bienv2activartes']) == 0) {
		$DATA['cara44bienv2activartes'] = '';
	}
	if (isset($DATA['cara44bienv2activliteratura']) == 0) {
		$DATA['cara44bienv2activliteratura'] = '';
	}
	if (isset($DATA['cara44bienv2activculturalotra']) == 0) {
		$DATA['cara44bienv2activculturalotra'] = '';
	}
	if (isset($DATA['cara44bienv2activculturalotradetalle']) == 0) {
		$DATA['cara44bienv2activculturalotradetalle'] = '';
	}
	if (isset($DATA['cara44bienv2evenfestfolc']) == 0) {
		$DATA['cara44bienv2evenfestfolc'] = '';
	}
	if (isset($DATA['cara44bienv2evenexpoarte']) == 0) {
		$DATA['cara44bienv2evenexpoarte'] = '';
	}
	if (isset($DATA['cara44bienv2evenhistarte']) == 0) {
		$DATA['cara44bienv2evenhistarte'] = '';
	}
	if (isset($DATA['cara44bienv2evengalfoto']) == 0) {
		$DATA['cara44bienv2evengalfoto'] = '';
	}
	if (isset($DATA['cara44bienv2evenliteratura']) == 0) {
		$DATA['cara44bienv2evenliteratura'] = '';
	}
	if (isset($DATA['cara44bienv2eventeatro']) == 0) {
		$DATA['cara44bienv2eventeatro'] = '';
	}
	if (isset($DATA['cara44bienv2evencine']) == 0) {
		$DATA['cara44bienv2evencine'] = '';
	}
	if (isset($DATA['cara44bienv2evenculturalotro']) == 0) {
		$DATA['cara44bienv2evenculturalotro'] = '';
	}
	if (isset($DATA['cara44bienv2evenculturalotrodetalle']) == 0) {
		$DATA['cara44bienv2evenculturalotrodetalle'] = '';
	}
	if (isset($DATA['cara44bienv2emprendimiento']) == 0) {
		$DATA['cara44bienv2emprendimiento'] = '';
	}
	if (isset($DATA['cara44bienv2empresa']) == 0) {
		$DATA['cara44bienv2empresa'] = '';
	}
	if (isset($DATA['cara44bienv2emprenrecursos']) == 0) {
		$DATA['cara44bienv2emprenrecursos'] = '';
	}
	if (isset($DATA['cara44bienv2emprenconocim']) == 0) {
		$DATA['cara44bienv2emprenconocim'] = '';
	}
	if (isset($DATA['cara44bienv2emprenplan']) == 0) {
		$DATA['cara44bienv2emprenplan'] = '';
	}
	if (isset($DATA['cara44bienv2emprenejecutar']) == 0) {
		$DATA['cara44bienv2emprenejecutar'] = '';
	}
	if (isset($DATA['cara44bienv2emprenfortconocim']) == 0) {
		$DATA['cara44bienv2emprenfortconocim'] = '';
	}
	if (isset($DATA['cara44bienv2emprenidentproblema']) == 0) {
		$DATA['cara44bienv2emprenidentproblema'] = '';
	}
	if (isset($DATA['cara44bienv2emprenotro']) == 0) {
		$DATA['cara44bienv2emprenotro'] = '';
	}
	if (isset($DATA['cara44bienv2emprenotrodetalle']) == 0) {
		$DATA['cara44bienv2emprenotrodetalle'] = '';
	}
	if (isset($DATA['cara44bienv2emprenmarketing']) == 0) {
		$DATA['cara44bienv2emprenmarketing'] = '';
	}
	if (isset($DATA['cara44bienv2emprenplannegocios']) == 0) {
		$DATA['cara44bienv2emprenplannegocios'] = '';
	}
	if (isset($DATA['cara44bienv2emprenideas']) == 0) {
		$DATA['cara44bienv2emprenideas'] = '';
	}
	if (isset($DATA['cara44bienv2emprencreacion']) == 0) {
		$DATA['cara44bienv2emprencreacion'] = '';
	}
	if (isset($DATA['cara44bienv2saludfacteconom']) == 0) {
		$DATA['cara44bienv2saludfacteconom'] = '';
	}
	if (isset($DATA['cara44bienv2saludpreocupacion']) == 0) {
		$DATA['cara44bienv2saludpreocupacion'] = '';
	}
	if (isset($DATA['cara44bienv2saludconsumosust']) == 0) {
		$DATA['cara44bienv2saludconsumosust'] = '';
	}
	if (isset($DATA['cara44bienv2saludinsomnio']) == 0) {
		$DATA['cara44bienv2saludinsomnio'] = '';
	}
	if (isset($DATA['cara44bienv2saludclimalab']) == 0) {
		$DATA['cara44bienv2saludclimalab'] = '';
	}
	if (isset($DATA['cara44bienv2saludalimenta']) == 0) {
		$DATA['cara44bienv2saludalimenta'] = '';
	}
	if (isset($DATA['cara44bienv2saludemocion']) == 0) {
		$DATA['cara44bienv2saludemocion'] = '';
	}
	if (isset($DATA['cara44bienv2saludestado']) == 0) {
		$DATA['cara44bienv2saludestado'] = '';
	}
	if (isset($DATA['cara44bienv2saludmedita']) == 0) {
		$DATA['cara44bienv2saludmedita'] = '';
	}
	if (isset($DATA['cara44bienv2crecimedusexual']) == 0) {
		$DATA['cara44bienv2crecimedusexual'] = '';
	}
	if (isset($DATA['cara44bienv2crecimcultciudad']) == 0) {
		$DATA['cara44bienv2crecimcultciudad'] = '';
	}
	if (isset($DATA['cara44bienv2crecimrelpareja']) == 0) {
		$DATA['cara44bienv2crecimrelpareja'] = '';
	}
	if (isset($DATA['cara44bienv2crecimrelinterp']) == 0) {
		$DATA['cara44bienv2crecimrelinterp'] = '';
	}
	if (isset($DATA['cara44bienv2crecimdinamicafam']) == 0) {
		$DATA['cara44bienv2crecimdinamicafam'] = '';
	}
	if (isset($DATA['cara44bienv2crecimautoestima']) == 0) {
		$DATA['cara44bienv2crecimautoestima'] = '';
	}
	if (isset($DATA['cara44bienv2creciminclusion']) == 0) {
		$DATA['cara44bienv2creciminclusion'] = '';
	}
	if (isset($DATA['cara44bienv2creciminteliemoc']) == 0) {
		$DATA['cara44bienv2creciminteliemoc'] = '';
	}
	if (isset($DATA['cara44bienv2crecimcultural']) == 0) {
		$DATA['cara44bienv2crecimcultural'] = '';
	}
	if (isset($DATA['cara44bienv2crecimartistico']) == 0) {
		$DATA['cara44bienv2crecimartistico'] = '';
	}
	if (isset($DATA['cara44bienv2crecimdeporte']) == 0) {
		$DATA['cara44bienv2crecimdeporte'] = '';
	}
	if (isset($DATA['cara44bienv2crecimambiente']) == 0) {
		$DATA['cara44bienv2crecimambiente'] = '';
	}
	if (isset($DATA['cara44bienv2crecimhabsocio']) == 0) {
		$DATA['cara44bienv2crecimhabsocio'] = '';
	}
	if (isset($DATA['cara44bienv2ambienbasura']) == 0) {
		$DATA['cara44bienv2ambienbasura'] = '';
	}
	if (isset($DATA['cara44bienv2ambienreutiliza']) == 0) {
		$DATA['cara44bienv2ambienreutiliza'] = '';
	}
	if (isset($DATA['cara44bienv2ambienluces']) == 0) {
		$DATA['cara44bienv2ambienluces'] = '';
	}
	if (isset($DATA['cara44bienv2ambienfrutaverd']) == 0) {
		$DATA['cara44bienv2ambienfrutaverd'] = '';
	}
	if (isset($DATA['cara44bienv2ambienenchufa']) == 0) {
		$DATA['cara44bienv2ambienenchufa'] = '';
	}
	if (isset($DATA['cara44bienv2ambiengrifo']) == 0) {
		$DATA['cara44bienv2ambiengrifo'] = '';
	}
	if (isset($DATA['cara44bienv2ambienbicicleta']) == 0) {
		$DATA['cara44bienv2ambienbicicleta'] = '';
	}
	if (isset($DATA['cara44bienv2ambientranspub']) == 0) {
		$DATA['cara44bienv2ambientranspub'] = '';
	}
	if (isset($DATA['cara44bienv2ambienducha']) == 0) {
		$DATA['cara44bienv2ambienducha'] = '';
	}
	if (isset($DATA['cara44bienv2ambiencaminata']) == 0) {
		$DATA['cara44bienv2ambiencaminata'] = '';
	}
	if (isset($DATA['cara44bienv2ambiensiembra']) == 0) {
		$DATA['cara44bienv2ambiensiembra'] = '';
	}
	if (isset($DATA['cara44bienv2ambienconferencia']) == 0) {
		$DATA['cara44bienv2ambienconferencia'] = '';
	}
	if (isset($DATA['cara44bienv2ambienrecicla']) == 0) {
		$DATA['cara44bienv2ambienrecicla'] = '';
	}
	if (isset($DATA['cara44bienv2ambienotraactiv']) == 0) {
		$DATA['cara44bienv2ambienotraactiv'] = '';
	}
	if (isset($DATA['cara44bienv2ambienotraactivdetalle']) == 0) {
		$DATA['cara44bienv2ambienotraactivdetalle'] = '';
	}
	if (isset($DATA['cara44bienv2ambienreforest']) == 0) {
		$DATA['cara44bienv2ambienreforest'] = '';
	}
	if (isset($DATA['cara44bienv2ambienmovilidad']) == 0) {
		$DATA['cara44bienv2ambienmovilidad'] = '';
	}
	if (isset($DATA['cara44bienv2ambienclimatico']) == 0) {
		$DATA['cara44bienv2ambienclimatico'] = '';
	}
	if (isset($DATA['cara44bienv2ambienecofemin']) == 0) {
		$DATA['cara44bienv2ambienecofemin'] = '';
	}
	if (isset($DATA['cara44bienv2ambienbiodiver']) == 0) {
		$DATA['cara44bienv2ambienbiodiver'] = '';
	}
	if (isset($DATA['cara44bienv2ambienecologia']) == 0) {
		$DATA['cara44bienv2ambienecologia'] = '';
	}
	if (isset($DATA['cara44bienv2ambieneconomia']) == 0) {
		$DATA['cara44bienv2ambieneconomia'] = '';
	}
	if (isset($DATA['cara44bienv2ambienrecnatura']) == 0) {
		$DATA['cara44bienv2ambienrecnatura'] = '';
	}
	if (isset($DATA['cara44bienv2ambienreciclaje']) == 0) {
		$DATA['cara44bienv2ambienreciclaje'] = '';
	}
	if (isset($DATA['cara44bienv2ambienmascota']) == 0) {
		$DATA['cara44bienv2ambienmascota'] = '';
	}
	if (isset($DATA['cara44bienv2ambiencartohum']) == 0) {
		$DATA['cara44bienv2ambiencartohum'] = '';
	}
	if (isset($DATA['cara44bienv2ambienespiritu']) == 0) {
		$DATA['cara44bienv2ambienespiritu'] = '';
	}
	if (isset($DATA['cara44bienv2ambiencarga']) == 0) {
		$DATA['cara44bienv2ambiencarga'] = '';
	}
	if (isset($DATA['cara44bienv2ambienotroenfoq']) == 0) {
		$DATA['cara44bienv2ambienotroenfoq'] = '';
	}
	if (isset($DATA['cara44bienv2ambienotroenfoqdetalle']) == 0) {
		$DATA['cara44bienv2ambienotroenfoqdetalle'] = '';
	}
	if (isset($DATA['cara44fam_madrecabeza']) == 0) {
		$DATA['cara44fam_madrecabeza'] = '';
	}
	if (isset($DATA['cara44acadhatenidorecesos']) == 0) {
		$DATA['cara44acadhatenidorecesos'] = '';
	}
	if (isset($DATA['cara44acadrazonreceso']) == 0) {
		$DATA['cara44acadrazonreceso'] = 0;
	}
	if (isset($DATA['cara44acadrazonrecesodetalle']) == 0) {
		$DATA['cara44acadrazonrecesodetalle'] = '';
	}
	if (isset($DATA['cara44campus_usocorreounad']) == 0) {
		$DATA['cara44campus_usocorreounad'] = 0;
	}
	if (isset($DATA['cara44campus_usocorreounadno']) == 0) {
		$DATA['cara44campus_usocorreounadno'] = 0;
	}
	if (isset($DATA['cara44campus_usocorreounadnodetalle']) == 0) {
		$DATA['cara44campus_usocorreounadnodetalle'] = '';
	}
	if (isset($DATA['cara44campus_medioactivunad']) == 0) {
		$DATA['cara44campus_medioactivunad'] = 0;
	}
	if (isset($DATA['cara44campus_medioactivunaddetalle']) == 0) {
		$DATA['cara44campus_medioactivunaddetalle'] = '';
	}
	$DATA['cara01idperaca'] = numeros_validar($DATA['cara01idperaca']);
	$DATA['cara01fichaper'] = numeros_validar($DATA['cara01fichaper']);
	$DATA['cara01fichafam'] = numeros_validar($DATA['cara01fichafam']);
	$DATA['cara01fichaaca'] = numeros_validar($DATA['cara01fichaaca']);
	$DATA['cara01fichalab'] = numeros_validar($DATA['cara01fichalab']);
	$DATA['cara01fichabien'] = numeros_validar($DATA['cara01fichabien']);
	$DATA['cara01fichapsico'] = numeros_validar($DATA['cara01fichapsico']);
	$DATA['cara01fichadigital'] = numeros_validar($DATA['cara01fichadigital']);
	$DATA['cara01fichalectura'] = numeros_validar($DATA['cara01fichalectura']);
	$DATA['cara01ficharazona'] = numeros_validar($DATA['cara01ficharazona']);
	$DATA['cara01fichaingles'] = numeros_validar($DATA['cara01fichaingles']);
	$DATA['cara01sexo'] = htmlspecialchars(trim($DATA['cara01sexo']));
	$DATA['cara01pais'] = htmlspecialchars(trim($DATA['cara01pais']));
	$DATA['cara01depto'] = htmlspecialchars(trim($DATA['cara01depto']));
	$DATA['cara01ciudad'] = htmlspecialchars(trim($DATA['cara01ciudad']));
	$DATA['cara01nomciudad'] = htmlspecialchars(trim($DATA['cara01nomciudad']));
	$DATA['cara01direccion'] = htmlspecialchars(trim($DATA['cara01direccion']));
	$DATA['cara01estrato'] = numeros_validar($DATA['cara01estrato']);
	$DATA['cara01zonares'] = htmlspecialchars(trim($DATA['cara01zonares']));
	$DATA['cara01estcivil'] = htmlspecialchars(trim($DATA['cara01estcivil']));
	$DATA['cara01nomcontacto'] = htmlspecialchars(trim($DATA['cara01nomcontacto']));
	$DATA['cara01parentezcocontacto'] = htmlspecialchars(trim($DATA['cara01parentezcocontacto']));
	$DATA['cara01celcontacto'] = htmlspecialchars(trim($DATA['cara01celcontacto']));
	$DATA['cara01correocontacto'] = htmlspecialchars(trim($DATA['cara01correocontacto']));
	$DATA['cara01idzona'] = numeros_validar($DATA['cara01idzona']);
	$DATA['cara01idcead'] = numeros_validar($DATA['cara01idcead']);
	$DATA['cara01matconvenio'] = htmlspecialchars(trim($DATA['cara01matconvenio']));
	$DATA['cara01raizal'] = htmlspecialchars(trim($DATA['cara01raizal']));
	$DATA['cara01palenquero'] = htmlspecialchars(trim($DATA['cara01palenquero']));
	$DATA['cara01afrocolombiano'] = htmlspecialchars(trim($DATA['cara01afrocolombiano']));
	$DATA['cara01otracomunnegras'] = htmlspecialchars(trim($DATA['cara01otracomunnegras']));
	$DATA['cara01rom'] = htmlspecialchars(trim($DATA['cara01rom']));
	$DATA['cara01indigenas'] = numeros_validar($DATA['cara01indigenas']);
	$DATA['cara01victimadesplazado'] = htmlspecialchars(trim($DATA['cara01victimadesplazado']));
	$DATA['cara01victimaacr'] = htmlspecialchars(trim($DATA['cara01victimaacr']));
	$DATA['cara01inpecfuncionario'] = htmlspecialchars(trim($DATA['cara01inpecfuncionario']));
	$DATA['cara01inpecrecluso'] = htmlspecialchars(trim($DATA['cara01inpecrecluso']));
	$DATA['cara01inpectiempocondena'] = numeros_validar($DATA['cara01inpectiempocondena']);
	$DATA['cara01centroreclusion'] = numeros_validar($DATA['cara01centroreclusion']);
	$DATA['cara01discsensorial'] = htmlspecialchars(trim($DATA['cara01discsensorial']));
	$DATA['cara01discfisica'] = htmlspecialchars(trim($DATA['cara01discfisica']));
	$DATA['cara01disccognitiva'] = htmlspecialchars(trim($DATA['cara01disccognitiva']));
	$DATA['cara01fam_tipovivienda'] = numeros_validar($DATA['cara01fam_tipovivienda']);
	$DATA['cara01fam_vivecon'] = numeros_validar($DATA['cara01fam_vivecon']);
	$DATA['cara01fam_numpersgrupofam'] = numeros_validar($DATA['cara01fam_numpersgrupofam']);
	$DATA['cara01fam_hijos'] = numeros_validar($DATA['cara01fam_hijos']);
	$DATA['cara01fam_personasacargo'] = numeros_validar($DATA['cara01fam_personasacargo']);
	$DATA['cara01fam_dependeecon'] = htmlspecialchars(trim($DATA['cara01fam_dependeecon']));
	$DATA['cara01fam_escolaridadpadre'] = numeros_validar($DATA['cara01fam_escolaridadpadre']);
	$DATA['cara01fam_escolaridadmadre'] = numeros_validar($DATA['cara01fam_escolaridadmadre']);
	$DATA['cara01fam_numhermanos'] = numeros_validar($DATA['cara01fam_numhermanos']);
	$DATA['cara01fam_posicionherm'] = numeros_validar($DATA['cara01fam_posicionherm']);
	$DATA['cara01fam_familiaunad'] = htmlspecialchars(trim($DATA['cara01fam_familiaunad']));
	$DATA['cara01acad_tipocolegio'] = numeros_validar($DATA['cara01acad_tipocolegio']);
	$DATA['cara01acad_modalidadbach'] = numeros_validar($DATA['cara01acad_modalidadbach']);
	$DATA['cara01acad_estudioprev'] = htmlspecialchars(trim($DATA['cara01acad_estudioprev']));
	$DATA['cara01acad_ultnivelest'] = numeros_validar($DATA['cara01acad_ultnivelest']);
	$DATA['cara01acad_obtubodiploma'] = htmlspecialchars(trim($DATA['cara01acad_obtubodiploma']));
	$DATA['cara01acad_hatomadovirtual'] = htmlspecialchars(trim($DATA['cara01acad_hatomadovirtual']));
	$DATA['cara01acad_tiemposinest'] = numeros_validar($DATA['cara01acad_tiemposinest']);
	$DATA['cara01acad_razonestudio'] = numeros_validar($DATA['cara01acad_razonestudio']);
	$DATA['cara01acad_primeraopc'] = htmlspecialchars(trim($DATA['cara01acad_primeraopc']));
	$DATA['cara01acad_programagusto'] = htmlspecialchars(trim($DATA['cara01acad_programagusto']));
	$DATA['cara01acad_razonunad'] = numeros_validar($DATA['cara01acad_razonunad']);
	$DATA['cara01campus_compescrito'] = htmlspecialchars(trim($DATA['cara01campus_compescrito']));
	$DATA['cara01campus_portatil'] = htmlspecialchars(trim($DATA['cara01campus_portatil']));
	$DATA['cara01campus_tableta'] = htmlspecialchars(trim($DATA['cara01campus_tableta']));
	$DATA['cara01campus_telefono'] = htmlspecialchars(trim($DATA['cara01campus_telefono']));
	$DATA['cara01campus_energia'] = numeros_validar($DATA['cara01campus_energia']);
	$DATA['cara01campus_internetreside'] = numeros_validar($DATA['cara01campus_internetreside']);
	$DATA['cara01campus_expvirtual'] = htmlspecialchars(trim($DATA['cara01campus_expvirtual']));
	$DATA['cara01campus_ofimatica'] = htmlspecialchars(trim($DATA['cara01campus_ofimatica']));
	$DATA['cara01campus_foros'] = htmlspecialchars(trim($DATA['cara01campus_foros']));
	$DATA['cara01campus_conversiones'] = htmlspecialchars(trim($DATA['cara01campus_conversiones']));
	$DATA['cara01campus_usocorreo'] = numeros_validar($DATA['cara01campus_usocorreo']);
	$DATA['cara01campus_aprendtexto'] = htmlspecialchars(trim($DATA['cara01campus_aprendtexto']));
	$DATA['cara01campus_aprendvideo'] = htmlspecialchars(trim($DATA['cara01campus_aprendvideo']));
	$DATA['cara01campus_aprendmapas'] = htmlspecialchars(trim($DATA['cara01campus_aprendmapas']));
	$DATA['cara01campus_aprendeanima'] = htmlspecialchars(trim($DATA['cara01campus_aprendeanima']));
	$DATA['cara01campus_mediocomunica'] = numeros_validar($DATA['cara01campus_mediocomunica']);
	$DATA['cara01lab_situacion'] = numeros_validar($DATA['cara01lab_situacion']);
	$DATA['cara01lab_sector'] = numeros_validar($DATA['cara01lab_sector']);
	$DATA['cara01lab_caracterjuri'] = numeros_validar($DATA['cara01lab_caracterjuri']);
	$DATA['cara01lab_cargo'] = numeros_validar($DATA['cara01lab_cargo']);
	$DATA['cara01lab_antiguedad'] = numeros_validar($DATA['cara01lab_antiguedad']);
	$DATA['cara01lab_tipocontrato'] = numeros_validar($DATA['cara01lab_tipocontrato']);
	$DATA['cara01lab_rangoingreso'] = numeros_validar($DATA['cara01lab_rangoingreso']);
	$DATA['cara01lab_tiempoacadem'] = numeros_validar($DATA['cara01lab_tiempoacadem']);
	$DATA['cara01lab_tipoempresa'] = numeros_validar($DATA['cara01lab_tipoempresa']);
	$DATA['cara01lab_tiempoindepen'] = numeros_validar($DATA['cara01lab_tiempoindepen']);
	$DATA['cara01lab_debebusctrab'] = htmlspecialchars(trim($DATA['cara01lab_debebusctrab']));
	$DATA['cara01lab_origendinero'] = numeros_validar($DATA['cara01lab_origendinero']);
	$DATA['cara01bien_baloncesto'] = htmlspecialchars(trim($DATA['cara01bien_baloncesto']));
	$DATA['cara01bien_voleibol'] = htmlspecialchars(trim($DATA['cara01bien_voleibol']));
	$DATA['cara01bien_futbolsala'] = htmlspecialchars(trim($DATA['cara01bien_futbolsala']));
	$DATA['cara01bien_artesmarc'] = htmlspecialchars(trim($DATA['cara01bien_artesmarc']));
	$DATA['cara01bien_tenisdemesa'] = htmlspecialchars(trim($DATA['cara01bien_tenisdemesa']));
	$DATA['cara01bien_ajedrez'] = htmlspecialchars(trim($DATA['cara01bien_ajedrez']));
	$DATA['cara01bien_juegosautoc'] = htmlspecialchars(trim($DATA['cara01bien_juegosautoc']));
	$DATA['cara01bien_interesrepdeporte'] = htmlspecialchars(trim($DATA['cara01bien_interesrepdeporte']));
	$DATA['cara01bien_deporteint'] = htmlspecialchars(trim($DATA['cara01bien_deporteint']));
	$DATA['cara01bien_teatro'] = htmlspecialchars(trim($DATA['cara01bien_teatro']));
	$DATA['cara01bien_danza'] = htmlspecialchars(trim($DATA['cara01bien_danza']));
	$DATA['cara01bien_musica'] = htmlspecialchars(trim($DATA['cara01bien_musica']));
	$DATA['cara01bien_circo'] = htmlspecialchars(trim($DATA['cara01bien_circo']));
	$DATA['cara01bien_artplast'] = htmlspecialchars(trim($DATA['cara01bien_artplast']));
	$DATA['cara01bien_cuenteria'] = htmlspecialchars(trim($DATA['cara01bien_cuenteria']));
	$DATA['cara01bien_interesreparte'] = htmlspecialchars(trim($DATA['cara01bien_interesreparte']));
	$DATA['cara01bien_arteint'] = htmlspecialchars(trim($DATA['cara01bien_arteint']));
	$DATA['cara01bien_interpreta'] = numeros_validar($DATA['cara01bien_interpreta']);
	$DATA['cara01bien_nivelinter'] = numeros_validar($DATA['cara01bien_nivelinter']);
	$DATA['cara01bien_danza_mod'] = htmlspecialchars(trim($DATA['cara01bien_danza_mod']));
	$DATA['cara01bien_danza_clas'] = htmlspecialchars(trim($DATA['cara01bien_danza_clas']));
	$DATA['cara01bien_danza_cont'] = htmlspecialchars(trim($DATA['cara01bien_danza_cont']));
	$DATA['cara01bien_danza_folk'] = htmlspecialchars(trim($DATA['cara01bien_danza_folk']));
	$DATA['cara01bien_niveldanza'] = numeros_validar($DATA['cara01bien_niveldanza']);
	$DATA['cara01bien_emprendedor'] = htmlspecialchars(trim($DATA['cara01bien_emprendedor']));
	$DATA['cara01bien_nombreemp'] = htmlspecialchars(trim($DATA['cara01bien_nombreemp']));
	$DATA['cara01bien_capacempren'] = htmlspecialchars(trim($DATA['cara01bien_capacempren']));
	$DATA['cara01bien_tipocapacita'] = htmlspecialchars(trim($DATA['cara01bien_tipocapacita']));
	$DATA['cara01bien_impvidasalud'] = htmlspecialchars(trim($DATA['cara01bien_impvidasalud']));
	$DATA['cara01bien_estraautocuid'] = htmlspecialchars(trim($DATA['cara01bien_estraautocuid']));
	$DATA['cara01bien_pv_personal'] = htmlspecialchars(trim($DATA['cara01bien_pv_personal']));
	$DATA['cara01bien_pv_familiar'] = htmlspecialchars(trim($DATA['cara01bien_pv_familiar']));
	$DATA['cara01bien_pv_academ'] = htmlspecialchars(trim($DATA['cara01bien_pv_academ']));
	$DATA['cara01bien_pv_labora'] = htmlspecialchars(trim($DATA['cara01bien_pv_labora']));
	$DATA['cara01bien_pv_pareja'] = htmlspecialchars(trim($DATA['cara01bien_pv_pareja']));
	$DATA['cara01bien_amb'] = htmlspecialchars(trim($DATA['cara01bien_amb']));
	$DATA['cara01bien_amb_agu'] = htmlspecialchars(trim($DATA['cara01bien_amb_agu']));
	$DATA['cara01bien_amb_bom'] = htmlspecialchars(trim($DATA['cara01bien_amb_bom']));
	$DATA['cara01bien_amb_car'] = htmlspecialchars(trim($DATA['cara01bien_amb_car']));
	$DATA['cara01bien_amb_info'] = htmlspecialchars(trim($DATA['cara01bien_amb_info']));
	$DATA['cara01bien_amb_temas'] = htmlspecialchars(trim($DATA['cara01bien_amb_temas']));
	$DATA['cara01psico_costoemocion'] = numeros_validar($DATA['cara01psico_costoemocion']);
	$DATA['cara01psico_reaccionimpre'] = numeros_validar($DATA['cara01psico_reaccionimpre']);
	$DATA['cara01psico_estres'] = numeros_validar($DATA['cara01psico_estres']);
	$DATA['cara01psico_pocotiempo'] = numeros_validar($DATA['cara01psico_pocotiempo']);
	$DATA['cara01psico_actitudvida'] = numeros_validar($DATA['cara01psico_actitudvida']);
	$DATA['cara01psico_duda'] = numeros_validar($DATA['cara01psico_duda']);
	$DATA['cara01psico_problemapers'] = numeros_validar($DATA['cara01psico_problemapers']);
	$DATA['cara01psico_satisfaccion'] = numeros_validar($DATA['cara01psico_satisfaccion']);
	$DATA['cara01psico_discusiones'] = numeros_validar($DATA['cara01psico_discusiones']);
	$DATA['cara01psico_atencion'] = numeros_validar($DATA['cara01psico_atencion']);
	$DATA['cara01telefono1'] = htmlspecialchars(trim($DATA['cara01telefono1']));
	$DATA['cara01telefono2'] = htmlspecialchars(trim($DATA['cara01telefono2']));
	$DATA['cara01correopersonal'] = htmlspecialchars(trim($DATA['cara01correopersonal']));
	$DATA['cara01fichabiolog'] = numeros_validar($DATA['cara01fichabiolog']);
	$DATA['cara01fichafisica'] = numeros_validar($DATA['cara01fichafisica']);
	$DATA['cara01fichaquimica'] = numeros_validar($DATA['cara01fichaquimica']);
	$DATA['cara01tipocaracterizacion'] = numeros_validar($DATA['cara01tipocaracterizacion']);
	$DATA['cara01discv2sensorial'] = numeros_validar($DATA['cara01discv2sensorial']);
	$DATA['cara02discv2intelectura'] = numeros_validar($DATA['cara02discv2intelectura']);
	$DATA['cara02discv2fisica'] = numeros_validar($DATA['cara02discv2fisica']);
	$DATA['cara02discv2psico'] = numeros_validar($DATA['cara02discv2psico']);
	$DATA['cara02discv2sistemica'] = numeros_validar($DATA['cara02discv2sistemica']);
	$DATA['cara02discv2sistemicaotro'] = htmlspecialchars(trim($DATA['cara02discv2sistemicaotro']));
	$DATA['cara02discv2multiple'] = numeros_validar($DATA['cara02discv2multiple']);
	$DATA['cara02discv2multipleotro'] = htmlspecialchars(trim($DATA['cara02discv2multipleotro']));
	$DATA['cara02talentoexcepcional'] = numeros_validar($DATA['cara02talentoexcepcional']);
	$DATA['cara01discv2tiene'] = numeros_validar($DATA['cara01discv2tiene']);
	$DATA['cara01discv2trastaprende'] = numeros_validar($DATA['cara01discv2trastaprende']);
	$DATA['cara01discv2soporteorigen'] = numeros_validar($DATA['cara01discv2soporteorigen']);
	$DATA['cara01discv2archivoorigen'] = numeros_validar($DATA['cara01discv2archivoorigen']);
	$DATA['cara01discv2trastornos'] = numeros_validar($DATA['cara01discv2trastornos']);
	$DATA['cara01discv2contalento'] = numeros_validar($DATA['cara01discv2contalento']);
	$DATA['cara01discv2condicionmedica'] = numeros_validar($DATA['cara01discv2condicionmedica']);
	$DATA['cara01discv2condmeddet'] = htmlspecialchars(trim($DATA['cara01discv2condmeddet']));
	$DATA['cara01discv2pruebacoeficiente'] = numeros_validar($DATA['cara01discv2pruebacoeficiente']);
	$DATA['cara44campesinado'] = htmlspecialchars(trim($DATA['cara44campesinado']));
	$DATA['cara44sexov1identidadgen'] = numeros_validar($DATA['cara44sexov1identidadgen']);
	$DATA['cara44sexov1orientasexo'] = numeros_validar($DATA['cara44sexov1orientasexo']);
	$DATA['cara44bienv2altoren'] = htmlspecialchars(trim($DATA['cara44bienv2altoren']));
	$DATA['cara44bienv2atletismo'] = htmlspecialchars(trim($DATA['cara44bienv2atletismo']));
	$DATA['cara44bienv2baloncesto'] = htmlspecialchars(trim($DATA['cara44bienv2baloncesto']));
	$DATA['cara44bienv2futbol'] = htmlspecialchars(trim($DATA['cara44bienv2futbol']));
	$DATA['cara44bienv2gimnasia'] = htmlspecialchars(trim($DATA['cara44bienv2gimnasia']));
	$DATA['cara44bienv2natacion'] = htmlspecialchars(trim($DATA['cara44bienv2natacion']));
	$DATA['cara44bienv2voleibol'] = htmlspecialchars(trim($DATA['cara44bienv2voleibol']));
	$DATA['cara44bienv2tenis'] = htmlspecialchars(trim($DATA['cara44bienv2tenis']));
	$DATA['cara44bienv2paralimpico'] = htmlspecialchars(trim($DATA['cara44bienv2paralimpico']));
	$DATA['cara44bienv2otrodeporte'] = htmlspecialchars(trim($DATA['cara44bienv2otrodeporte']));
	$DATA['cara44bienv2otrodeportedetalle'] = htmlspecialchars(trim($DATA['cara44bienv2otrodeportedetalle']));
	$DATA['cara44bienv2activdanza'] = htmlspecialchars(trim($DATA['cara44bienv2activdanza']));
	$DATA['cara44bienv2activmusica'] = htmlspecialchars(trim($DATA['cara44bienv2activmusica']));
	$DATA['cara44bienv2activteatro'] = htmlspecialchars(trim($DATA['cara44bienv2activteatro']));
	$DATA['cara44bienv2activartes'] = htmlspecialchars(trim($DATA['cara44bienv2activartes']));
	$DATA['cara44bienv2activliteratura'] = htmlspecialchars(trim($DATA['cara44bienv2activliteratura']));
	$DATA['cara44bienv2activculturalotra'] = htmlspecialchars(trim($DATA['cara44bienv2activculturalotra']));
	$DATA['cara44bienv2activculturalotradetalle'] = htmlspecialchars(trim($DATA['cara44bienv2activculturalotradetalle']));
	$DATA['cara44bienv2evenfestfolc'] = htmlspecialchars(trim($DATA['cara44bienv2evenfestfolc']));
	$DATA['cara44bienv2evenexpoarte'] = htmlspecialchars(trim($DATA['cara44bienv2evenexpoarte']));
	$DATA['cara44bienv2evenhistarte'] = htmlspecialchars(trim($DATA['cara44bienv2evenhistarte']));
	$DATA['cara44bienv2evengalfoto'] = htmlspecialchars(trim($DATA['cara44bienv2evengalfoto']));
	$DATA['cara44bienv2evenliteratura'] = htmlspecialchars(trim($DATA['cara44bienv2evenliteratura']));
	$DATA['cara44bienv2eventeatro'] = htmlspecialchars(trim($DATA['cara44bienv2eventeatro']));
	$DATA['cara44bienv2evencine'] = htmlspecialchars(trim($DATA['cara44bienv2evencine']));
	$DATA['cara44bienv2evenculturalotro'] = htmlspecialchars(trim($DATA['cara44bienv2evenculturalotro']));
	$DATA['cara44bienv2evenculturalotrodetalle'] = htmlspecialchars(trim($DATA['cara44bienv2evenculturalotrodetalle']));
	$DATA['cara44bienv2emprendimiento'] = htmlspecialchars(trim($DATA['cara44bienv2emprendimiento']));
	$DATA['cara44bienv2empresa'] = htmlspecialchars(trim($DATA['cara44bienv2empresa']));
	$DATA['cara44bienv2emprenrecursos'] = htmlspecialchars(trim($DATA['cara44bienv2emprenrecursos']));
	$DATA['cara44bienv2emprenconocim'] = htmlspecialchars(trim($DATA['cara44bienv2emprenconocim']));
	$DATA['cara44bienv2emprenplan'] = htmlspecialchars(trim($DATA['cara44bienv2emprenplan']));
	$DATA['cara44bienv2emprenejecutar'] = htmlspecialchars(trim($DATA['cara44bienv2emprenejecutar']));
	$DATA['cara44bienv2emprenfortconocim'] = htmlspecialchars(trim($DATA['cara44bienv2emprenfortconocim']));
	$DATA['cara44bienv2emprenidentproblema'] = htmlspecialchars(trim($DATA['cara44bienv2emprenidentproblema']));
	$DATA['cara44bienv2emprenotro'] = htmlspecialchars(trim($DATA['cara44bienv2emprenotro']));
	$DATA['cara44bienv2emprenotrodetalle'] = htmlspecialchars(trim($DATA['cara44bienv2emprenotrodetalle']));
	$DATA['cara44bienv2emprenmarketing'] = htmlspecialchars(trim($DATA['cara44bienv2emprenmarketing']));
	$DATA['cara44bienv2emprenplannegocios'] = htmlspecialchars(trim($DATA['cara44bienv2emprenplannegocios']));
	$DATA['cara44bienv2emprenideas'] = htmlspecialchars(trim($DATA['cara44bienv2emprenideas']));
	$DATA['cara44bienv2emprencreacion'] = htmlspecialchars(trim($DATA['cara44bienv2emprencreacion']));
	$DATA['cara44bienv2saludfacteconom'] = htmlspecialchars(trim($DATA['cara44bienv2saludfacteconom']));
	$DATA['cara44bienv2saludpreocupacion'] = htmlspecialchars(trim($DATA['cara44bienv2saludpreocupacion']));
	$DATA['cara44bienv2saludconsumosust'] = htmlspecialchars(trim($DATA['cara44bienv2saludconsumosust']));
	$DATA['cara44bienv2saludinsomnio'] = htmlspecialchars(trim($DATA['cara44bienv2saludinsomnio']));
	$DATA['cara44bienv2saludclimalab'] = htmlspecialchars(trim($DATA['cara44bienv2saludclimalab']));
	$DATA['cara44bienv2saludalimenta'] = htmlspecialchars(trim($DATA['cara44bienv2saludalimenta']));
	$DATA['cara44bienv2saludemocion'] = htmlspecialchars(trim($DATA['cara44bienv2saludemocion']));
	$DATA['cara44bienv2saludestado'] = htmlspecialchars(trim($DATA['cara44bienv2saludestado']));
	$DATA['cara44bienv2saludmedita'] = htmlspecialchars(trim($DATA['cara44bienv2saludmedita']));
	$DATA['cara44bienv2crecimedusexual'] = htmlspecialchars(trim($DATA['cara44bienv2crecimedusexual']));
	$DATA['cara44bienv2crecimcultciudad'] = htmlspecialchars(trim($DATA['cara44bienv2crecimcultciudad']));
	$DATA['cara44bienv2crecimrelpareja'] = htmlspecialchars(trim($DATA['cara44bienv2crecimrelpareja']));
	$DATA['cara44bienv2crecimrelinterp'] = htmlspecialchars(trim($DATA['cara44bienv2crecimrelinterp']));
	$DATA['cara44bienv2crecimdinamicafam'] = htmlspecialchars(trim($DATA['cara44bienv2crecimdinamicafam']));
	$DATA['cara44bienv2crecimautoestima'] = htmlspecialchars(trim($DATA['cara44bienv2crecimautoestima']));
	$DATA['cara44bienv2creciminclusion'] = htmlspecialchars(trim($DATA['cara44bienv2creciminclusion']));
	$DATA['cara44bienv2creciminteliemoc'] = htmlspecialchars(trim($DATA['cara44bienv2creciminteliemoc']));
	$DATA['cara44bienv2crecimcultural'] = htmlspecialchars(trim($DATA['cara44bienv2crecimcultural']));
	$DATA['cara44bienv2crecimartistico'] = htmlspecialchars(trim($DATA['cara44bienv2crecimartistico']));
	$DATA['cara44bienv2crecimdeporte'] = htmlspecialchars(trim($DATA['cara44bienv2crecimdeporte']));
	$DATA['cara44bienv2crecimambiente'] = htmlspecialchars(trim($DATA['cara44bienv2crecimambiente']));
	$DATA['cara44bienv2crecimhabsocio'] = htmlspecialchars(trim($DATA['cara44bienv2crecimhabsocio']));
	$DATA['cara44bienv2ambienbasura'] = htmlspecialchars(trim($DATA['cara44bienv2ambienbasura']));
	$DATA['cara44bienv2ambienreutiliza'] = htmlspecialchars(trim($DATA['cara44bienv2ambienreutiliza']));
	$DATA['cara44bienv2ambienluces'] = htmlspecialchars(trim($DATA['cara44bienv2ambienluces']));
	$DATA['cara44bienv2ambienfrutaverd'] = htmlspecialchars(trim($DATA['cara44bienv2ambienfrutaverd']));
	$DATA['cara44bienv2ambienenchufa'] = htmlspecialchars(trim($DATA['cara44bienv2ambienenchufa']));
	$DATA['cara44bienv2ambiengrifo'] = htmlspecialchars(trim($DATA['cara44bienv2ambiengrifo']));
	$DATA['cara44bienv2ambienbicicleta'] = htmlspecialchars(trim($DATA['cara44bienv2ambienbicicleta']));
	$DATA['cara44bienv2ambientranspub'] = htmlspecialchars(trim($DATA['cara44bienv2ambientranspub']));
	$DATA['cara44bienv2ambienducha'] = htmlspecialchars(trim($DATA['cara44bienv2ambienducha']));
	$DATA['cara44bienv2ambiencaminata'] = htmlspecialchars(trim($DATA['cara44bienv2ambiencaminata']));
	$DATA['cara44bienv2ambiensiembra'] = htmlspecialchars(trim($DATA['cara44bienv2ambiensiembra']));
	$DATA['cara44bienv2ambienconferencia'] = htmlspecialchars(trim($DATA['cara44bienv2ambienconferencia']));
	$DATA['cara44bienv2ambienrecicla'] = htmlspecialchars(trim($DATA['cara44bienv2ambienrecicla']));
	$DATA['cara44bienv2ambienotraactiv'] = htmlspecialchars(trim($DATA['cara44bienv2ambienotraactiv']));
	$DATA['cara44bienv2ambienotraactivdetalle'] = htmlspecialchars(trim($DATA['cara44bienv2ambienotraactivdetalle']));
	$DATA['cara44bienv2ambienreforest'] = htmlspecialchars(trim($DATA['cara44bienv2ambienreforest']));
	$DATA['cara44bienv2ambienmovilidad'] = htmlspecialchars(trim($DATA['cara44bienv2ambienmovilidad']));
	$DATA['cara44bienv2ambienclimatico'] = htmlspecialchars(trim($DATA['cara44bienv2ambienclimatico']));
	$DATA['cara44bienv2ambienecofemin'] = htmlspecialchars(trim($DATA['cara44bienv2ambienecofemin']));
	$DATA['cara44bienv2ambienbiodiver'] = htmlspecialchars(trim($DATA['cara44bienv2ambienbiodiver']));
	$DATA['cara44bienv2ambienecologia'] = htmlspecialchars(trim($DATA['cara44bienv2ambienecologia']));
	$DATA['cara44bienv2ambieneconomia'] = htmlspecialchars(trim($DATA['cara44bienv2ambieneconomia']));
	$DATA['cara44bienv2ambienrecnatura'] = htmlspecialchars(trim($DATA['cara44bienv2ambienrecnatura']));
	$DATA['cara44bienv2ambienreciclaje'] = htmlspecialchars(trim($DATA['cara44bienv2ambienreciclaje']));
	$DATA['cara44bienv2ambienmascota'] = htmlspecialchars(trim($DATA['cara44bienv2ambienmascota']));
	$DATA['cara44bienv2ambiencartohum'] = htmlspecialchars(trim($DATA['cara44bienv2ambiencartohum']));
	$DATA['cara44bienv2ambienespiritu'] = htmlspecialchars(trim($DATA['cara44bienv2ambienespiritu']));
	$DATA['cara44bienv2ambiencarga'] = htmlspecialchars(trim($DATA['cara44bienv2ambiencarga']));
	$DATA['cara44bienv2ambienotroenfoq'] = htmlspecialchars(trim($DATA['cara44bienv2ambienotroenfoq']));
	$DATA['cara44bienv2ambienotroenfoqdetalle'] = htmlspecialchars(trim($DATA['cara44bienv2ambienotroenfoqdetalle']));
	$DATA['cara44fam_madrecabeza'] = htmlspecialchars(trim($DATA['cara44fam_madrecabeza']));
	$DATA['cara44acadhatenidorecesos'] = htmlspecialchars(trim($DATA['cara44acadhatenidorecesos']));
	$DATA['cara44acadrazonreceso'] = numeros_validar($DATA['cara44acadrazonreceso']);
	$DATA['cara44acadrazonrecesodetalle'] = htmlspecialchars(trim($DATA['cara44acadrazonrecesodetalle']));
	$DATA['cara44campus_usocorreounad'] = numeros_validar($DATA['cara44campus_usocorreounad']);
	$DATA['cara44campus_usocorreounadno'] = numeros_validar($DATA['cara44campus_usocorreounadno']);
	$DATA['cara44campus_usocorreounadnodetalle'] = htmlspecialchars(trim($DATA['cara44campus_usocorreounadnodetalle']));
	$DATA['cara44campus_medioactivunad'] = numeros_validar($DATA['cara44campus_medioactivunad']);
	$DATA['cara44campus_medioactivunaddetalle'] = htmlspecialchars(trim($DATA['cara44campus_medioactivunaddetalle']));
	// -- Se inicializan las variables que puedan pasar vacias {Especialmente números}.
	if ($DATA['cara01completa'] == '') {
		$DATA['cara01completa'] = 'N';
	}
	if ($DATA['cara01fichaper'] == '') {
		$DATA['cara01fichaper'] = 0;
	}
	if ($DATA['cara01fichafam'] == '') {
		$DATA['cara01fichafam'] = 0;
	}
	if ($DATA['cara01fichaaca'] == '') {
		$DATA['cara01fichaaca'] = 0;
	}
	if ($DATA['cara01fichalab'] == '') {
		$DATA['cara01fichalab'] = 0;
	}
	if ($DATA['cara01fichabien'] == '') {
		$DATA['cara01fichabien'] = 0;
	}
	if ($DATA['cara01fichapsico'] == '') {
		$DATA['cara01fichapsico'] = 0;
	}
	if ($DATA['cara01fichadigital'] == '') {
		$DATA['cara01fichadigital'] = 0;
	}
	if ($DATA['cara01fichalectura'] == '') {
		$DATA['cara01fichalectura'] = 0;
	}
	if ($DATA['cara01ficharazona'] == '') {
		$DATA['cara01ficharazona'] = 0;
	}
	if ($DATA['cara01fichaingles'] == '') {
		$DATA['cara01fichaingles'] = 0;
	}
	if ($DATA['cara01agnos'] == '') {
		$DATA['cara01agnos'] = 0;
	}
	if ($DATA['cara01estrato'] == '') {
		$DATA['cara01estrato'] = 0;
	}
	if ($DATA['cara01idzona'] == '') {
		$DATA['cara01idzona'] = 0;
	}
	if ($DATA['cara01idcead'] == '') {
		$DATA['cara01idcead'] = 0;
	}
	if ($DATA['cara01indigenas'] == '') {
		$DATA['cara01indigenas'] = 0;
	}
	if ($DATA['cara01inpectiempocondena'] == '') {
		$DATA['cara01inpectiempocondena'] = 0;
	}
	if ($DATA['cara01centroreclusion'] == '') {
		$DATA['cara01centroreclusion'] = 0;
	}
	if ($DATA['cara01fam_tipovivienda'] == '') {
		$DATA['cara01fam_tipovivienda'] = 0;
	}
	if ($DATA['cara01fam_vivecon'] == '') {
		$DATA['cara01fam_vivecon'] = 0;
	}
	if ($DATA['cara01fam_numpersgrupofam'] == '') {
		$DATA['cara01fam_numpersgrupofam'] = 0;
	}
	if ($DATA['cara01fam_hijos'] == '') {
		$DATA['cara01fam_hijos'] = 0;
	}
	if ($DATA['cara01fam_personasacargo'] == '') {
		$DATA['cara01fam_personasacargo'] = 0;
	}
	if ($DATA['cara01fam_escolaridadpadre'] == '') {
		$DATA['cara01fam_escolaridadpadre'] = 0;
	}
	if ($DATA['cara01fam_escolaridadmadre'] == '') {
		$DATA['cara01fam_escolaridadmadre'] = 0;
	}
	if ($DATA['cara01fam_numhermanos'] == '') {
		$DATA['cara01fam_numhermanos'] = 0;
	}
	if ($DATA['cara01fam_posicionherm'] == '') {
		$DATA['cara01fam_posicionherm'] = 0;
	}
	if ($DATA['cara01acad_tipocolegio'] == '') {
		$DATA['cara01acad_tipocolegio'] = 0;
	}
	if ($DATA['cara01acad_modalidadbach'] == '') {
		$DATA['cara01acad_modalidadbach'] = 0;
	}
	if ($DATA['cara01acad_ultnivelest'] == '') {
		$DATA['cara01acad_ultnivelest'] = 0;
	}
	if ($DATA['cara01acad_tiemposinest'] == '') {
		$DATA['cara01acad_tiemposinest'] = 0;
	}
	if ($DATA['cara01acad_razonestudio'] == '') {
		$DATA['cara01acad_razonestudio'] = 0;
	}
	if ($DATA['cara01acad_razonunad'] == '') {
		$DATA['cara01acad_razonunad'] = 0;
	}
	if ($DATA['cara01campus_energia'] == '') {
		$DATA['cara01campus_energia'] = 0;
	}
	if ($DATA['cara01campus_internetreside'] == '') {
		$DATA['cara01campus_internetreside'] = 0;
	}
	if ($DATA['cara01campus_usocorreo'] == '') {
		$DATA['cara01campus_usocorreo'] = 0;
	}
	if ($DATA['cara01campus_mediocomunica'] == '') {
		$DATA['cara01campus_mediocomunica'] = 0;
	}
	if ($DATA['cara01lab_situacion'] == '') {
		$DATA['cara01lab_situacion'] = 0;
	}
	if ($DATA['cara01lab_sector'] == '') {
		$DATA['cara01lab_sector'] = 0;
	}
	if ($DATA['cara01lab_caracterjuri'] == '') {
		$DATA['cara01lab_caracterjuri'] = 0;
	}
	if ($DATA['cara01lab_cargo'] == '') {
		$DATA['cara01lab_cargo'] = 0;
	}
	if ($DATA['cara01lab_antiguedad'] == '') {
		$DATA['cara01lab_antiguedad'] = 0;
	}
	if ($DATA['cara01lab_tipocontrato'] == '') {
		$DATA['cara01lab_tipocontrato'] = 0;
	}
	if ($DATA['cara01lab_rangoingreso'] == '') {
		$DATA['cara01lab_rangoingreso'] = 0;
	}
	if ($DATA['cara01lab_tiempoacadem'] == '') {
		$DATA['cara01lab_tiempoacadem'] = 0;
	}
	if ($DATA['cara01lab_tipoempresa'] == '') {
		$DATA['cara01lab_tipoempresa'] = 0;
	}
	if ($DATA['cara01lab_tiempoindepen'] == '') {
		$DATA['cara01lab_tiempoindepen'] = 0;
	}
	if ($DATA['cara01lab_origendinero'] == '') {
		$DATA['cara01lab_origendinero'] = 0;
	}
	if ($DATA['cara01bien_interpreta'] == '') {
		$DATA['cara01bien_interpreta'] = 0;
	}
	if ($DATA['cara01bien_nivelinter'] == '') {
		$DATA['cara01bien_nivelinter'] = 0;
	}
	if ($DATA['cara01bien_niveldanza'] == '') {
		$DATA['cara01bien_niveldanza'] = 0;
	}
	if ($DATA['cara01psico_costoemocion'] == '') {
		$DATA['cara01psico_costoemocion'] = 0;
	}
	if ($DATA['cara01psico_reaccionimpre'] == '') {
		$DATA['cara01psico_reaccionimpre'] = 0;
	}
	if ($DATA['cara01psico_estres'] == '') {
		$DATA['cara01psico_estres'] = 0;
	}
	if ($DATA['cara01psico_pocotiempo'] == '') {
		$DATA['cara01psico_pocotiempo'] = 0;
	}
	if ($DATA['cara01psico_actitudvida'] == '') {
		$DATA['cara01psico_actitudvida'] = 0;
	}
	if ($DATA['cara01psico_duda'] == '') {
		$DATA['cara01psico_duda'] = 0;
	}
	if ($DATA['cara01psico_problemapers'] == '') {
		$DATA['cara01psico_problemapers'] = 0;
	}
	if ($DATA['cara01psico_satisfaccion'] == '') {
		$DATA['cara01psico_satisfaccion'] = 0;
	}
	if ($DATA['cara01psico_discusiones'] == '') {
		$DATA['cara01psico_discusiones'] = 0;
	}
	if ($DATA['cara01psico_atencion'] == '') {
		$DATA['cara01psico_atencion'] = 0;
	}
	if ($DATA['cara01niveldigital'] == '') {
		$DATA['cara01niveldigital'] = 0;
	}
	if ($DATA['cara01nivellectura'] == '') {
		$DATA['cara01nivellectura'] = 0;
	}
	if ($DATA['cara01nivelrazona'] == '') {
		$DATA['cara01nivelrazona'] = 0;
	}
	if ($DATA['cara01nivelingles'] == '') {
		$DATA['cara01nivelingles'] = 0;
	}
	if ($DATA['cara01idprograma'] == '') {
		$DATA['cara01idprograma'] = 0;
	}
	if ($DATA['cara01idescuela'] == '') {
		$DATA['cara01idescuela'] = 0;
	}
	//if ($DATA['cara01fichabiolog']==''){$DATA['cara01fichabiolog']=0;}
	if ($DATA['cara01nivelbiolog'] == '') {
		$DATA['cara01nivelbiolog'] = 0;
	}
	//if ($DATA['cara01fichafisica']==''){$DATA['cara01fichafisica']=0;}
	if ($DATA['cara01nivelfisica'] == '') {
		$DATA['cara01nivelfisica'] = 0;
	}
	//if ($DATA['cara01fichaquimica']==''){$DATA['cara01fichaquimica']=0;}
	if ($DATA['cara01nivelquimica'] == '') {
		$DATA['cara01nivelquimica'] = 0;
	}
	//if ($DATA['cara01tipocaracterizacion']==''){$DATA['cara01tipocaracterizacion']=0;}
	if ($DATA['cara01discv2sensorial'] == '') {
		$DATA['cara01discv2sensorial'] = 0;
	}
	if ($DATA['cara02discv2intelectura'] == '') {
		$DATA['cara02discv2intelectura'] = 0;
	}
	if ($DATA['cara02discv2fisica'] == '') {
		$DATA['cara02discv2fisica'] = 0;
	}
	if ($DATA['cara02discv2psico'] == '') {
		$DATA['cara02discv2psico'] = 0;
	}
	if ($DATA['cara02discv2sistemica'] == '') {
		$DATA['cara02discv2sistemica'] = 0;
	}
	if ($DATA['cara02discv2multiple'] == '') {
		$DATA['cara02discv2multiple'] = 0;
	}
	if ($DATA['cara02talentoexcepcional'] == '') {
		$DATA['cara02talentoexcepcional'] = 0;
	}
	if ($DATA['cara01discv2tiene'] == '') {
		$DATA['cara01discv2tiene'] = 0;
	}
	if ($DATA['cara01discv2trastaprende'] == '') {
		$DATA['cara01discv2trastaprende'] = 0;
	}
	if ($DATA['cara01discv2trastornos'] == '') {
		$DATA['cara01discv2trastornos'] = 0;
	}
	if ($DATA['cara01discv2contalento'] == '') {
		$DATA['cara01discv2contalento'] = 0;
	}
	if ($DATA['cara01discv2condicionmedica'] == '') {
		$DATA['cara01discv2condicionmedica'] = 0;
	}
	if ($DATA['cara01discv2pruebacoeficiente'] == '') {
		$DATA['cara01discv2pruebacoeficiente'] = 0;
	}
	if ($DATA['cara44sexov1identidadgen'] == '') {
		$DATA['cara44sexov1identidadgen'] = 0;
	}
	if ($DATA['cara44sexov1orientasexo'] == '') {
		$DATA['cara44sexov1orientasexo'] = 0;
	}
	if ($DATA['cara44acadrazonreceso'] == '') {
		$DATA['cara44acadrazonreceso'] = 0;
	}
	if ($DATA['cara44campus_usocorreounad'] == '') {
		$DATA['cara44campus_usocorreounad'] = 0;
	}
	if ($DATA['cara44campus_usocorreounadno'] == '') {
		$DATA['cara44campus_usocorreounadno'] = 0;
	}
	if ($DATA['cara44campus_medioactivunad'] == '') {
		$DATA['cara44campus_medioactivunad'] = 0;
	}
	// -- Seccion para validar los posibles causales de error.
	//Primero hacer un caso de revision de los encabezados.
	if ($DATA['ficha'] == 1) {
		$DATA['cara01fichaper'] = 1;
	}
	if ($DATA['ficha'] == 2) {
		if ($DATA['cara01fichafam'] != -1) {
			$DATA['cara01fichafam'] = 1;
		}
	}
	if ($DATA['ficha'] == 3) {
		if ($DATA['cara01fichaaca'] != -1) {
			$DATA['cara01fichaaca'] = 1;
		}
	}
	if ($DATA['ficha'] == 4) {
		if ($DATA['cara01fichalab'] != -1) {
			$DATA['cara01fichalab'] = 1;
		}
	}
	if ($DATA['ficha'] == 5) {
		if ($DATA['cara01fichabien'] != -1) {
			$DATA['cara01fichabien'] = 1;
		}
	}
	if ($DATA['ficha'] == 6) {
		if ($DATA['cara01fichapsico'] != -1) {
			$DATA['cara01fichapsico'] = 1;
		}
	}
	if ($DATA['ficha'] == 7) {
		if ($DATA['cara01fichadigital'] != -1) {
			$DATA['cara01fichadigital'] = 1;
		}
	}
	if ($DATA['ficha'] == 8) {
		if ($DATA['cara01fichalectura'] != -1) {
			$DATA['cara01fichalectura'] = 1;
		}
	}
	if ($DATA['ficha'] == 9) {
		if ($DATA['cara01ficharazona'] != -1) {
			$DATA['cara01ficharazona'] = 1;
		}
	}
	if ($DATA['ficha'] == 10) {
		if ($DATA['cara01fichaingles'] != -1) {
			$DATA['cara01fichaingles'] = 1;
		}
	}
	if ($DATA['ficha'] == 11) {
		if ($DATA['cara01fichabiolog'] != -1) {
			$DATA['cara01fichabiolog'] = 1;
		}
	}
	if ($DATA['ficha'] == 12) {
		if ($DATA['cara01fichafisica'] != -1) {
			$DATA['cara01fichafisica'] = 1;
		}
	}
	if ($DATA['ficha'] == 13) {
		if ($DATA['cara01fichaquimica'] != -1) {
			$DATA['cara01fichaquimica'] = 1;
		}
	}
	$aFicha = array('', 'cara01fichaper', 'cara01fichafam', 'cara01fichaaca', 'cara01fichalab', 'cara01fichabien', 'cara01fichapsico', 'cara01fichadigital', 'cara01fichalectura', 'cara01ficharazona', 'cara01fichaingles', 'cara01fichabiolog', 'cara01fichafisica', 'cara01fichaquimica');
	//Fin de revisar los casos de revision de encabezados
	$bAntiguo = false;
	if ($DATA['cara01tipocaracterizacion'] == 3) {
		$bAntiguo = true;
	}
	$sSepara = ', ';
	if ($DATA['ficha'] == 13) {
		list($sPreguntas, $DATA['cara01nivelquimica'], $sDebugP) = f2301_VerificarPreguntas($DATA['cara01id'], 7, $objDB, $bDebug);
		if ($sPreguntas != '') {
			$sDebug = $sDebug . $sDebugP;
			$sError = $ERR['msg_noresueltas'] . ' ' . $sPreguntas;
		}
		if ($sError != '') {
			$DATA['cara01fichaquimica'] = 0;
		}
	}
	if ($DATA['ficha'] == 12) {
		list($sPreguntas, $DATA['cara01nivelfisica'], $sDebugP) = f2301_VerificarPreguntas($DATA['cara01id'], 6, $objDB, $bDebug);
		if ($sPreguntas != '') {
			$sDebug = $sDebug . $sDebugP;
			$sError = $ERR['msg_noresueltas'] . ' ' . $sPreguntas;
		}
		if ($sError != '') {
			$DATA['cara01fichafisica'] = 0;
		}
	}
	if ($DATA['ficha'] == 11) {
		list($sPreguntas, $DATA['cara01nivelbiolog'], $sDebugP) = f2301_VerificarPreguntas($DATA['cara01id'], 5, $objDB, $bDebug);
		if ($sPreguntas != '') {
			$sDebug = $sDebug . $sDebugP;
			$sError = $ERR['msg_noresueltas'] . ' ' . $sPreguntas;
		}
		if ($sError != '') {
			$DATA['cara01fichabiolog'] = 0;
		}
	}
	if ($DATA['ficha'] == 10) {
		list($sPreguntas, $DATA['cara01nivelingles'], $sDebugP) = f2301_VerificarPreguntas($DATA['cara01id'], 4, $objDB, $bDebug);
		if ($sPreguntas != '') {
			$sDebug = $sDebug . $sDebugP;
			$sError = $ERR['msg_noresueltas'] . ' ' . $sPreguntas;
		}
		if ($sError != '') {
			$DATA['cara01fichaingles'] = 0;
		}
	}
	if ($DATA['ficha'] == 9) {
		list($sPreguntas, $DATA['cara01nivelrazona'], $sDebugP) = f2301_VerificarPreguntas($DATA['cara01id'], 3, $objDB, $bDebug);
		if ($sPreguntas != '') {
			$sDebug = $sDebug . $sDebugP;
			$sError = $ERR['msg_noresueltas'] . ' ' . $sPreguntas;
		}
		if ($sError != '') {
			$DATA['cara01ficharazona'] = 0;
		}
	}
	if ($DATA['ficha'] == 8) {
		list($sPreguntas, $DATA['cara01nivellectura'], $sDebugP) = f2301_VerificarPreguntas($DATA['cara01id'], 2, $objDB, $bDebug);
		if ($sPreguntas != '') {
			$sDebug = $sDebug . $sDebugP;
			$sError = $ERR['msg_noresueltas'] . ' ' . $sPreguntas;
		}
		if ($sError != '') {
			$DATA['cara01fichalectura'] = 0;
		}
	}
	if ($DATA['ficha'] == 7) {
		list($sPreguntas, $DATA['cara01niveldigital'], $sDebugP) = f2301_VerificarPreguntas($DATA['cara01id'], 1, $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugF;
		if ($sPreguntas != '') {
			$sDebug = $sDebug . $sDebugP;
			$sError = $ERR['msg_noresueltas'] . ' ' . $sPreguntas;
		}
		if ($sError != '') {
			$DATA['cara01fichadigital'] = 0;
		}
	}
	if ($DATA['ficha'] == 6) {
		if ($DATA['cara01psico_atencion'] == '') {
			$sError = $ERR['cara01psico_atencion'] . $sSepara . $sError;
		}
		if ($DATA['cara01psico_discusiones'] == '') {
			$sError = $ERR['cara01psico_discusiones'] . $sSepara . $sError;
		}
		if ($DATA['cara01psico_satisfaccion'] == '') {
			$sError = $ERR['cara01psico_satisfaccion'] . $sSepara . $sError;
		}
		if ($DATA['cara01psico_problemapers'] == '') {
			$sError = $ERR['cara01psico_problemapers'] . $sSepara . $sError;
		}
		if ($DATA['cara01psico_duda'] == '') {
			$sError = $ERR['cara01psico_duda'] . $sSepara . $sError;
		}
		if ($DATA['cara01psico_actitudvida'] == '') {
			$sError = $ERR['cara01psico_actitudvida'] . $sSepara . $sError;
		}
		if ($DATA['cara01psico_pocotiempo'] == '') {
			$sError = $ERR['cara01psico_pocotiempo'] . $sSepara . $sError;
		}
		if ($DATA['cara01psico_estres'] == '') {
			$sError = $ERR['cara01psico_estres'] . $sSepara . $sError;
		}
		if ($DATA['cara01psico_reaccionimpre'] == '') {
			$sError = $ERR['cara01psico_reaccionimpre'] . $sSepara . $sError;
		}
		if ($DATA['cara01psico_costoemocion'] == '') {
			$sError = $ERR['cara01psico_costoemocion'] . $sSepara . $sError;
		}
		if ($sError != '') {
			$DATA['cara01fichapsico'] = 0;
		} else {
			$DATA['cara01psico_puntaje'] = $DATA['cara01psico_atencion'] + $DATA['cara01psico_discusiones'] + $DATA['cara01psico_satisfaccion'] + $DATA['cara01psico_problemapers'] + $DATA['cara01psico_duda'] + $DATA['cara01psico_actitudvida'] + $DATA['cara01psico_pocotiempo'] + $DATA['cara01psico_estres'] + $DATA['cara01psico_reaccionimpre'] + $DATA['cara01psico_costoemocion'];
		}
	}
	if ($DATA['ficha'] == 5) {
		if ($DATA['cara44bienversion'] < 2) {
			if ($DATA['cara01bien_amb_info'] == 'S') {
				//if ($DATA['cara01bien_amb_temas']==''){$sError=$ERR['cara01bien_amb_temas'].$sSepara.$sError;}
			}
			if ($DATA['cara01bien_amb_info'] == '') {
				$sError = $ERR['cara01bien_amb_info'] . $sSepara . $sError;
			}
			if ($DATA['cara01bien_amb_car'] == '') {
				$sError = $ERR['cara01bien_amb_car'] . $sSepara . $sError;
			}
			if ($DATA['cara01bien_amb_bom'] == '') {
				$sError = $ERR['cara01bien_amb_bom'] . $sSepara . $sError;
			}
			if ($DATA['cara01bien_amb_agu'] == '') {
				$sError = $ERR['cara01bien_amb_agu'] . $sSepara . $sError;
			}
			if ($DATA['cara01bien_amb'] == '') {
				$sError = $ERR['cara01bien_amb'] . $sSepara . $sError;
			}
			//if ($DATA['cara01bien_pv_pareja']==''){$sError=$ERR['cara01bien_pv_pareja'].$sSepara.$sError;}
			//if ($DATA['cara01bien_pv_labora']==''){$sError=$ERR['cara01bien_pv_labora'].$sSepara.$sError;}
			//if ($DATA['cara01bien_pv_academ']==''){$sError=$ERR['cara01bien_pv_academ'].$sSepara.$sError;}
			//if ($DATA['cara01bien_pv_familiar']==''){$sError=$ERR['cara01bien_pv_familiar'].$sSepara.$sError;}
			if ($DATA['cara01bien_pv_personal'] == '') {
				$sError = $ERR['cara01bien_pv_personal'] . $sSepara . $sError;
			}
			if ($DATA['cara01bien_estraautocuid'] == '') {
				$sError = $ERR['cara01bien_estraautocuid'] . $sSepara . $sError;
			}
			if ($DATA['cara01bien_impvidasalud'] == '') {
				$sError = $ERR['cara01bien_impvidasalud'] . $sSepara . $sError;
			}
			if ($DATA['cara01bien_capacempren'] == 'S') {
				if ($DATA['cara01bien_tipocapacita'] == '') {
					$sError = $ERR['cara01bien_tipocapacita'] . $sSepara . $sError;
				}
			}
			if ($DATA['cara01bien_capacempren'] == '') {
				$sError = $ERR['cara01bien_capacempren'] . $sSepara . $sError;
			}
			if ($DATA['cara01bien_emprendedor'] == 'S') {
				if ($DATA['cara01bien_nombreemp'] == '') {
					$sError = $ERR['cara01bien_nombreemp'] . $sSepara . $sError;
				}
			}
			if ($DATA['cara01bien_emprendedor'] == '') {
				$sError = $ERR['cara01bien_emprendedor'] . $sSepara . $sError;
			}
			$bEntra = false;
			if ($DATA['cara01bien_danza_folk'] == 'S') {
				$bEntra = true;
			}
			if ($DATA['cara01bien_danza_cont'] == 'S') {
				$bEntra = true;
			}
			if ($DATA['cara01bien_danza_clas'] == 'S') {
				$bEntra = true;
			}
			if ($DATA['cara01bien_danza_mod'] == 'S') {
				$bEntra = true;
			}
			if ($bEntra) {
				if ($DATA['cara01bien_niveldanza'] == '') {
					$sError = $ERR['cara01bien_niveldanza'] . $sSepara . $sError;
				}
			}
			if ($DATA['cara01bien_danza_folk'] == '') {
				$sError = $ERR['cara01bien_danza_folk'] . $sSepara . $sError;
			}
			if ($DATA['cara01bien_danza_cont'] == '') {
				$sError = $ERR['cara01bien_danza_cont'] . $sSepara . $sError;
			}
			if ($DATA['cara01bien_danza_clas'] == '') {
				$sError = $ERR['cara01bien_danza_clas'] . $sSepara . $sError;
			}
			if ($DATA['cara01bien_danza_mod'] == '') {
				$sError = $ERR['cara01bien_danza_mod'] . $sSepara . $sError;
			}
			$bEntra = false;
			if ($DATA['cara01bien_interpreta'] != '') {
				if ($DATA['cara01bien_interpreta'] != -1) {
					$bEntra = true;
				}
			}
			if ($bEntra) {
				if ($DATA['cara01bien_nivelinter'] == '') {
					$sError = $ERR['cara01bien_nivelinter'] . $sSepara . $sError;
				}
			}
			if ($DATA['cara01bien_interpreta'] == '') {
				$sError = $ERR['cara01bien_interpreta'] . $sSepara . $sError;
			}
			if ($DATA['cara01bien_interesreparte'] == 'S') {
				if ($DATA['cara01bien_arteint'] == '') {
					$sError = $ERR['cara01bien_arteint'] . $sSepara . $sError;
				}
			}
			if ($DATA['cara01bien_interesreparte'] == '') {
				$sError = $ERR['cara01bien_interesreparte'] . $sSepara . $sError;
			}
			if ($DATA['cara01bien_cuenteria'] == '') {
				$sError = $ERR['cara01bien_cuenteria'] . $sSepara . $sError;
			}
			if ($DATA['cara01bien_artplast'] == '') {
				$sError = $ERR['cara01bien_artplast'] . $sSepara . $sError;
			}
			if ($DATA['cara01bien_circo'] == '') {
				$sError = $ERR['cara01bien_circo'] . $sSepara . $sError;
			}
			if ($DATA['cara01bien_musica'] == '') {
				$sError = $ERR['cara01bien_musica'] . $sSepara . $sError;
			}
			if ($DATA['cara01bien_danza'] == '') {
				$sError = $ERR['cara01bien_danza'] . $sSepara . $sError;
			}
			if ($DATA['cara01bien_teatro'] == '') {
				$sError = $ERR['cara01bien_teatro'] . $sSepara . $sError;
			}
			if ($DATA['cara01bien_interesrepdeporte'] == 'S') {
				if ($DATA['cara01bien_deporteint'] == '') {
					$sError = $ERR['cara01bien_deporteint'] . $sSepara . $sError;
				}
			}
			if ($DATA['cara01bien_interesrepdeporte'] == '') {
				$sError = $ERR['cara01bien_interesrepdeporte'] . $sSepara . $sError;
			}
			if ($DATA['cara01bien_juegosautoc'] == '') {
				$sError = $ERR['cara01bien_juegosautoc'] . $sSepara . $sError;
			}
			if ($DATA['cara01bien_ajedrez'] == '') {
				$sError = $ERR['cara01bien_ajedrez'] . $sSepara . $sError;
			}
			if ($DATA['cara01bien_tenisdemesa'] == '') {
				$sError = $ERR['cara01bien_tenisdemesa'] . $sSepara . $sError;
			}
			if ($DATA['cara01bien_artesmarc'] == '') {
				$sError = $ERR['cara01bien_artesmarc'] . $sSepara . $sError;
			}
			if ($DATA['cara01bien_futbolsala'] == '') {
				$sError = $ERR['cara01bien_futbolsala'] . $sSepara . $sError;
			}
			if ($DATA['cara01bien_voleibol'] == '') {
				$sError = $ERR['cara01bien_voleibol'] . $sSepara . $sError;
			}
			if ($DATA['cara01bien_baloncesto'] == '') {
				$sError = $ERR['cara01bien_baloncesto'] . $sSepara . $sError;
			}
		} else {
			if ($DATA['cara44bienv2altoren'] == '') {
				$sError = $ERR['cara44bienv2altoren'] . $sSepara . $sError;
			}
			if ($DATA['cara44bienv2atletismo'] == '') {
				$sError = $ERR['cara44bienv2atletismo'] . $sSepara . $sError;
			}
			if ($DATA['cara44bienv2baloncesto'] == '') {
				$sError = $ERR['cara44bienv2baloncesto'] . $sSepara . $sError;
			}
			if ($DATA['cara44bienv2futbol'] == '') {
				$sError = $ERR['cara44bienv2futbol'] . $sSepara . $sError;
			}
			if ($DATA['cara44bienv2gimnasia'] == '') {
				$sError = $ERR['cara44bienv2gimnasia'] . $sSepara . $sError;
			}
			if ($DATA['cara44bienv2natacion'] == '') {
				$sError = $ERR['cara44bienv2natacion'] . $sSepara . $sError;
			}
			if ($DATA['cara44bienv2voleibol'] == '') {
				$sError = $ERR['cara44bienv2voleibol'] . $sSepara . $sError;
			}
			if ($DATA['cara44bienv2tenis'] == '') {
				$sError = $ERR['cara44bienv2tenis'] . $sSepara . $sError;
			}
			if ($DATA['cara44bienv2paralimpico'] == '') {
				$sError = $ERR['cara44bienv2paralimpico'] . $sSepara . $sError;
			}
			if ($DATA['cara44bienv2otrodeporte'] == '') {
				$sError = $ERR['cara44bienv2otrodeporte'] . $sSepara . $sError;
			}
			if ($DATA['cara44bienv2otrodeporte'] == 'S') {
				if ($DATA['cara44bienv2otrodeportedetalle'] == '') {
					$sError = $ERR['cara44bienv2otrodeportedetalle'] . $sSepara . $sError;
				}
			}
			if ($DATA['cara44bienv2activdanza'] == '') {
				$sError = $ERR['cara44bienv2activdanza'] . $sSepara . $sError;
			}
			if ($DATA['cara44bienv2activmusica'] == '') {
				$sError = $ERR['cara44bienv2activmusica'] . $sSepara . $sError;
			}
			if ($DATA['cara44bienv2activteatro'] == '') {
				$sError = $ERR['cara44bienv2activteatro'] . $sSepara . $sError;
			}
			if ($DATA['cara44bienv2activartes'] == '') {
				$sError = $ERR['cara44bienv2activartes'] . $sSepara . $sError;
			}
			if ($DATA['cara44bienv2activliteratura'] == '') {
				$sError = $ERR['cara44bienv2activliteratura'] . $sSepara . $sError;
			}
			if ($DATA['cara44bienv2activculturalotra'] == '') {
				$sError = $ERR['cara44bienv2activculturalotra'] . $sSepara . $sError;
			}
			if ($DATA['cara44bienv2activculturalotra'] == 'S') {
				if ($DATA['cara44bienv2activculturalotradetalle'] == '') {
					$sError = $ERR['cara44bienv2activculturalotradetalle'] . $sSepara . $sError;
				}
			}
			if ($DATA['cara44bienv2evenfestfolc'] == '') {
				$sError = $ERR['cara44bienv2evenfestfolc'] . $sSepara . $sError;
			}
			if ($DATA['cara44bienv2evenexpoarte'] == '') {
				$sError = $ERR['cara44bienv2evenexpoarte'] . $sSepara . $sError;
			}
			if ($DATA['cara44bienv2evenhistarte'] == '') {
				$sError = $ERR['cara44bienv2evenhistarte'] . $sSepara . $sError;
			}
			if ($DATA['cara44bienv2evengalfoto'] == '') {
				$sError = $ERR['cara44bienv2evengalfoto'] . $sSepara . $sError;
			}
			if ($DATA['cara44bienv2evenliteratura'] == '') {
				$sError = $ERR['cara44bienv2evenliteratura'] . $sSepara . $sError;
			}
			if ($DATA['cara44bienv2eventeatro'] == '') {
				$sError = $ERR['cara44bienv2eventeatro'] . $sSepara . $sError;
			}
			if ($DATA['cara44bienv2evencine'] == '') {
				$sError = $ERR['cara44bienv2evencine'] . $sSepara . $sError;
			}
			if ($DATA['cara44bienv2evenculturalotro'] == '') {
				$sError = $ERR['cara44bienv2evenculturalotro'] . $sSepara . $sError;
			}
			if ($DATA['cara44bienv2evenculturalotro'] == 'S') {
				if ($DATA['cara44bienv2evenculturalotrodetalle'] == '') {
					$sError = $ERR['cara44bienv2evenculturalotrodetalle'] . $sSepara . $sError;
				}
			}
			if ($DATA['cara44bienv2emprendimiento'] == '') {
				$sError = $ERR['cara44bienv2emprendimiento'] . $sSepara . $sError;
			}
			if ($DATA['cara44bienv2empresa'] == '') {
				$sError = $ERR['cara44bienv2empresa'] . $sSepara . $sError;
			}
			if ($DATA['cara44bienv2emprenrecursos'] == '') {
				$sError = $ERR['cara44bienv2emprenrecursos'] . $sSepara . $sError;
			}
			if ($DATA['cara44bienv2emprenconocim'] == '') {
				$sError = $ERR['cara44bienv2emprenconocim'] . $sSepara . $sError;
			}
			if ($DATA['cara44bienv2emprenplan'] == '') {
				$sError = $ERR['cara44bienv2emprenplan'] . $sSepara . $sError;
			}
			if ($DATA['cara44bienv2emprenejecutar'] == '') {
				$sError = $ERR['cara44bienv2emprenejecutar'] . $sSepara . $sError;
			}
			if ($DATA['cara44bienv2emprenfortconocim'] == '') {
				$sError = $ERR['cara44bienv2emprenfortconocim'] . $sSepara . $sError;
			}
			if ($DATA['cara44bienv2emprenidentproblema'] == '') {
				$sError = $ERR['cara44bienv2emprenidentproblema'] . $sSepara . $sError;
			}
			if ($DATA['cara44bienv2emprenotro'] == '') {
				$sError = $ERR['cara44bienv2emprenotro'] . $sSepara . $sError;
			}
			if ($DATA['cara44bienv2emprenotro'] == 'S') {
				if ($DATA['cara44bienv2emprenotrodetalle'] == '') {
					$sError = $ERR['cara44bienv2emprenotrodetalle'] . $sSepara . $sError;
				}
			}
			if ($DATA['cara44bienv2emprenmarketing'] == '') {
				$sError = $ERR['cara44bienv2emprenmarketing'] . $sSepara . $sError;
			}
			if ($DATA['cara44bienv2emprenplannegocios'] == '') {
				$sError = $ERR['cara44bienv2emprenplannegocios'] . $sSepara . $sError;
			}
			if ($DATA['cara44bienv2emprenideas'] == '') {
				$sError = $ERR['cara44bienv2emprenideas'] . $sSepara . $sError;
			}
			if ($DATA['cara44bienv2emprencreacion'] == '') {
				$sError = $ERR['cara44bienv2emprencreacion'] . $sSepara . $sError;
			}
			if ($DATA['cara44bienv2saludfacteconom'] == '') {
				$sError = $ERR['cara44bienv2saludfacteconom'] . $sSepara . $sError;
			}
			if ($DATA['cara44bienv2saludpreocupacion'] == '') {
				$sError = $ERR['cara44bienv2saludpreocupacion'] . $sSepara . $sError;
			}
			if ($DATA['cara44bienv2saludconsumosust'] == '') {
				$sError = $ERR['cara44bienv2saludconsumosust'] . $sSepara . $sError;
			}
			if ($DATA['cara44bienv2saludinsomnio'] == '') {
				$sError = $ERR['cara44bienv2saludinsomnio'] . $sSepara . $sError;
			}
			if ($DATA['cara44bienv2saludclimalab'] == '') {
				$sError = $ERR['cara44bienv2saludclimalab'] . $sSepara . $sError;
			}
			if ($DATA['cara44bienv2saludalimenta'] == '') {
				$sError = $ERR['cara44bienv2saludalimenta'] . $sSepara . $sError;
			}
			if ($DATA['cara44bienv2saludemocion'] == '') {
				$sError = $ERR['cara44bienv2saludemocion'] . $sSepara . $sError;
			}
			if ($DATA['cara44bienv2saludestado'] == '') {
				$sError = $ERR['cara44bienv2saludestado'] . $sSepara . $sError;
			}
			if ($DATA['cara44bienv2saludmedita'] == '') {
				$sError = $ERR['cara44bienv2saludmedita'] . $sSepara . $sError;
			}
			if ($DATA['cara44bienv2crecimedusexual'] == '') {
				$sError = $ERR['cara44bienv2crecimedusexual'] . $sSepara . $sError;
			}
			if ($DATA['cara44bienv2crecimcultciudad'] == '') {
				$sError = $ERR['cara44bienv2crecimcultciudad'] . $sSepara . $sError;
			}
			if ($DATA['cara44bienv2crecimrelpareja'] == '') {
				$sError = $ERR['cara44bienv2crecimrelpareja'] . $sSepara . $sError;
			}
			if ($DATA['cara44bienv2crecimrelinterp'] == '') {
				$sError = $ERR['cara44bienv2crecimrelinterp'] . $sSepara . $sError;
			}
			if ($DATA['cara44bienv2crecimdinamicafam'] == '') {
				$sError = $ERR['cara44bienv2crecimdinamicafam'] . $sSepara . $sError;
			}
			if ($DATA['cara44bienv2crecimautoestima'] == '') {
				$sError = $ERR['cara44bienv2crecimautoestima'] . $sSepara . $sError;
			}
			if ($DATA['cara44bienv2creciminclusion'] == '') {
				$sError = $ERR['cara44bienv2creciminclusion'] . $sSepara . $sError;
			}
			if ($DATA['cara44bienv2creciminteliemoc'] == '') {
				$sError = $ERR['cara44bienv2creciminteliemoc'] . $sSepara . $sError;
			}
			if ($DATA['cara44bienv2crecimcultural'] == '') {
				$sError = $ERR['cara44bienv2crecimcultural'] . $sSepara . $sError;
			}
			if ($DATA['cara44bienv2crecimartistico'] == '') {
				$sError = $ERR['cara44bienv2crecimartistico'] . $sSepara . $sError;
			}
			if ($DATA['cara44bienv2crecimdeporte'] == '') {
				$sError = $ERR['cara44bienv2crecimdeporte'] . $sSepara . $sError;
			}
			if ($DATA['cara44bienv2crecimambiente'] == '') {
				$sError = $ERR['cara44bienv2crecimambiente'] . $sSepara . $sError;
			}
			if ($DATA['cara44bienv2crecimhabsocio'] == '') {
				$sError = $ERR['cara44bienv2crecimhabsocio'] . $sSepara . $sError;
			}
			if ($DATA['cara44bienv2ambienbasura'] == '') {
				$sError = $ERR['cara44bienv2ambienbasura'] . $sSepara . $sError;
			}
			if ($DATA['cara44bienv2ambienreutiliza'] == '') {
				$sError = $ERR['cara44bienv2ambienreutiliza'] . $sSepara . $sError;
			}
			if ($DATA['cara44bienv2ambienluces'] == '') {
				$sError = $ERR['cara44bienv2ambienluces'] . $sSepara . $sError;
			}
			if ($DATA['cara44bienv2ambienfrutaverd'] == '') {
				$sError = $ERR['cara44bienv2ambienfrutaverd'] . $sSepara . $sError;
			}
			if ($DATA['cara44bienv2ambienenchufa'] == '') {
				$sError = $ERR['cara44bienv2ambienenchufa'] . $sSepara . $sError;
			}
			if ($DATA['cara44bienv2ambiengrifo'] == '') {
				$sError = $ERR['cara44bienv2ambiengrifo'] . $sSepara . $sError;
			}
			if ($DATA['cara44bienv2ambienbicicleta'] == '') {
				$sError = $ERR['cara44bienv2ambienbicicleta'] . $sSepara . $sError;
			}
			if ($DATA['cara44bienv2ambientranspub'] == '') {
				$sError = $ERR['cara44bienv2ambientranspub'] . $sSepara . $sError;
			}
			if ($DATA['cara44bienv2ambienducha'] == '') {
				$sError = $ERR['cara44bienv2ambienducha'] . $sSepara . $sError;
			}
			if ($DATA['cara44bienv2ambiencaminata'] == '') {
				$sError = $ERR['cara44bienv2ambiencaminata'] . $sSepara . $sError;
			}
			if ($DATA['cara44bienv2ambiensiembra'] == '') {
				$sError = $ERR['cara44bienv2ambiensiembra'] . $sSepara . $sError;
			}
			if ($DATA['cara44bienv2ambienconferencia'] == '') {
				$sError = $ERR['cara44bienv2ambienconferencia'] . $sSepara . $sError;
			}
			if ($DATA['cara44bienv2ambienrecicla'] == '') {
				$sError = $ERR['cara44bienv2ambienrecicla'] . $sSepara . $sError;
			}
			if ($DATA['cara44bienv2ambienotraactiv'] == '') {
				$sError = $ERR['cara44bienv2ambienotraactiv'] . $sSepara . $sError;
			}
			if ($DATA['cara44bienv2ambienotraactiv'] == 'S') {
				if ($DATA['cara44bienv2ambienotraactivdetalle'] == '') {
					$sError = $ERR['cara44bienv2ambienotraactivdetalle'] . $sSepara . $sError;
				}
			}
			if ($DATA['cara44bienv2ambienreforest'] == '') {
				$sError = $ERR['cara44bienv2ambienreforest'] . $sSepara . $sError;
			}
			if ($DATA['cara44bienv2ambienmovilidad'] == '') {
				$sError = $ERR['cara44bienv2ambienmovilidad'] . $sSepara . $sError;
			}
			if ($DATA['cara44bienv2ambienclimatico'] == '') {
				$sError = $ERR['cara44bienv2ambienclimatico'] . $sSepara . $sError;
			}
			if ($DATA['cara44bienv2ambienecofemin'] == '') {
				$sError = $ERR['cara44bienv2ambienecofemin'] . $sSepara . $sError;
			}
			if ($DATA['cara44bienv2ambienbiodiver'] == '') {
				$sError = $ERR['cara44bienv2ambienbiodiver'] . $sSepara . $sError;
			}
			if ($DATA['cara44bienv2ambienecologia'] == '') {
				$sError = $ERR['cara44bienv2ambienecologia'] . $sSepara . $sError;
			}
			if ($DATA['cara44bienv2ambieneconomia'] == '') {
				$sError = $ERR['cara44bienv2ambieneconomia'] . $sSepara . $sError;
			}
			if ($DATA['cara44bienv2ambienrecnatura'] == '') {
				$sError = $ERR['cara44bienv2ambienrecnatura'] . $sSepara . $sError;
			}
			if ($DATA['cara44bienv2ambienreciclaje'] == '') {
				$sError = $ERR['cara44bienv2ambienreciclaje'] . $sSepara . $sError;
			}
			if ($DATA['cara44bienv2ambienmascota'] == '') {
				$sError = $ERR['cara44bienv2ambienmascota'] . $sSepara . $sError;
			}
			if ($DATA['cara44bienv2ambiencartohum'] == '') {
				$sError = $ERR['cara44bienv2ambiencartohum'] . $sSepara . $sError;
			}
			if ($DATA['cara44bienv2ambienespiritu'] == '') {
				$sError = $ERR['cara44bienv2ambienespiritu'] . $sSepara . $sError;
			}
			if ($DATA['cara44bienv2ambiencarga'] == '') {
				$sError = $ERR['cara44bienv2ambiencarga'] . $sSepara . $sError;
			}
			if ($DATA['cara44bienv2ambienotroenfoq'] == '') {
				$sError = $ERR['cara44bienv2ambienotroenfoq'] . $sSepara . $sError;
			}
			if ($DATA['cara44bienv2ambienotroenfoq'] == 'S') {
				if ($DATA['cara44bienv2ambienotroenfoqdetalle'] == '') {
					$sError = $ERR['cara44bienv2ambienotroenfoqdetalle'] . $sSepara . $sError;
				}
			}
		}
		if ($sError != '') {
			$DATA['cara01fichabien'] = 0;
		}
	}
	if ($DATA['ficha'] == 4) {
		$bBloque1 = false;
		$bBloque2 = false;
		$bBloque3 = false;
		$bBloque4 = false;
		$bBloque5 = false;
		$bBloque6 = false;
		if ($DATA['cara01lab_situacion'] == 1) {
			$bBloque1 = true;
			$bBloque2 = true;
			$bBloque3 = true;
			$bBloque6 = true;
		}
		if ($DATA['cara01lab_situacion'] == 2) {
			$bBloque1 = true;
			$bBloque4 = true;
			$bBloque6 = true;
		}
		if ($DATA['cara01lab_situacion'] == 3) {
			$bBloque3 = true;
			$bBloque5 = true;
			$bBloque6 = true;
		}
		if ($DATA['cara01lab_situacion'] == 4) {
			$bBloque5 = true;
			$bBloque6 = true;
		}
		if ($bBloque6) {
			if ($DATA['cara01lab_origendinero'] == '') {
				$sError = $ERR['cara01lab_origendinero'] . $sSepara . $sError;
			}
		} else {
			$DATA['cara01lab_origendinero'] = 0;
		}
		if ($bBloque5) {
			if ($DATA['cara01lab_debebusctrab'] == '') {
				$sError = $ERR['cara01lab_debebusctrab'] . $sSepara . $sError;
			}
		} else {
			$DATA['cara01lab_debebusctrab'] = '';
		}
		if ($bBloque4) {
			if ($DATA['cara01lab_tiempoindepen'] == '') {
				$sError = $ERR['cara01lab_tiempoindepen'] . $sSepara . $sError;
			}
			if ($DATA['cara01lab_tipoempresa'] == '') {
				$sError = $ERR['cara01lab_tipoempresa'] . $sSepara . $sError;
			}
		} else {
			$DATA['cara01lab_tiempoindepen'] = 0;
			$DATA['cara01lab_tipoempresa'] = 0;
		}
		if ($DATA['cara01lab_tiempoacadem'] == '') {
			$sError = $ERR['cara01lab_tiempoacadem'] . $sSepara . $sError;
		}
		if ($bBloque3) {
			if ($DATA['cara01lab_rangoingreso'] == '') {
				$sError = $ERR['cara01lab_rangoingreso'] . $sSepara . $sError;
			}
		} else {
			$DATA['cara01lab_rangoingreso'] = 0;
		}
		if ($bBloque2) {
			if ($DATA['cara01lab_tipocontrato'] == '') {
				$sError = $ERR['cara01lab_tipocontrato'] . $sSepara . $sError;
			}
		} else {
			$DATA['cara01lab_tipocontrato'] = 0;
		}
		if ($bBloque1) {
			if ($DATA['cara01lab_antiguedad'] == '') {
				$sError = $ERR['cara01lab_antiguedad'] . $sSepara . $sError;
			}
			if ($DATA['cara01lab_cargo'] == '') {
				$sError = $ERR['cara01lab_cargo'] . $sSepara . $sError;
			}
			if ($DATA['cara01lab_caracterjuri'] == '') {
				$sError = $ERR['cara01lab_caracterjuri'] . $sSepara . $sError;
			}
			if ($DATA['cara01lab_sector'] == '') {
				$sError = $ERR['cara01lab_sector'] . $sSepara . $sError;
			}
		} else {
			$DATA['cara01lab_antiguedad'] = 0;
			$DATA['cara01lab_cargo'] = 0;
			$DATA['cara01lab_caracterjuri'] = 0;
			$DATA['cara01lab_sector'] = 0;
		}
		if ($DATA['cara01lab_situacion'] == '') {
			$sError = $ERR['cara01lab_situacion'] . $sSepara . $sError;
		}
		if ($sError != '') {
			$DATA['cara01fichalab'] = 0;
		}
	}
	if ($DATA['ficha'] == 3) {
		if ($DATA['cara01campus_mediocomunica'] == '') {
			$sError = $ERR['cara01campus_mediocomunica'] . $sSepara . $sError;
		}
		if ($DATA['cara01campus_aprendeanima'] == '') {
			$sError = $ERR['cara01campus_aprendeanima'] . $sSepara . $sError;
		}
		if ($DATA['cara01campus_aprendmapas'] == '') {
			$sError = $ERR['cara01campus_aprendmapas'] . $sSepara . $sError;
		}
		if ($DATA['cara01campus_aprendvideo'] == '') {
			$sError = $ERR['cara01campus_aprendvideo'] . $sSepara . $sError;
		}
		if ($DATA['cara01campus_aprendtexto'] == '') {
			$sError = $ERR['cara01campus_aprendtexto'] . $sSepara . $sError;
		}
		if ($DATA['cara01campus_conversiones'] == '') {
			$sError = $ERR['cara01campus_conversiones'] . $sSepara . $sError;
		}
		if ($DATA['cara01campus_ofimatica'] == '') {
			$sError = $ERR['cara01campus_ofimatica'] . $sSepara . $sError;
		}
		if ($DATA['cara01campus_internetreside'] == '') {
			$sError = $ERR['cara01campus_internetreside'] . $sSepara . $sError;
		}
		if ($DATA['cara01campus_energia'] == '') {
			$sError = $ERR['cara01campus_energia'] . $sSepara . $sError;
		}
		if ($DATA['cara01campus_telefono'] == '') {
			$sError = $ERR['cara01campus_telefono'] . $sSepara . $sError;
		}
		if ($DATA['cara01campus_tableta'] == '') {
			$sError = $ERR['cara01campus_tableta'] . $sSepara . $sError;
		}
		if ($DATA['cara01campus_portatil'] == '') {
			$sError = $ERR['cara01campus_portatil'] . $sSepara . $sError;
		}
		if ($DATA['cara01campus_compescrito'] == '') {
			$sError = $ERR['cara01campus_compescrito'] . $sSepara . $sError;
		}
		if ($DATA['cara01acad_razonunad'] == '') {
			$sError = $ERR['cara01acad_razonunad'] . $sSepara . $sError;
		}
		if ($DATA['cara01acad_primeraopc'] == 'N') {
			if ($DATA['cara01acad_programagusto'] == '') {
				$sError = $ERR['cara01acad_programagusto'] . $sSepara . $sError;
			}
		}
		if ($DATA['cara01acad_primeraopc'] == '') {
			$sError = $ERR['cara01acad_primeraopc'] . $sSepara . $sError;
		}
		if ($DATA['cara01acad_razonestudio'] == '') {
			$sError = $ERR['cara01acad_razonestudio'] . $sSepara . $sError;
		}
		if ($DATA['cara01acad_obtubodiploma'] == '') {
			$sError = $ERR['cara01acad_obtubodiploma'] . $sSepara . $sError;
		}
		if ($DATA['cara01acad_ultnivelest'] == '') {
			$sError = $ERR['cara01acad_ultnivelest'] . $sSepara . $sError;
		}
		if ($DATA['cara01acad_estudioprev'] == '') {
			$sError = $ERR['cara01acad_estudioprev'] . $sSepara . $sError;
		}
		if (!$bAntiguo) {
			if ($DATA['cara01campus_usocorreo'] == '') {
				$sError = $ERR['cara01campus_usocorreo'] . $sSepara . $sError;
			}
			if ($DATA['cara01campus_expvirtual'] == '') {
				$sError = $ERR['cara01campus_expvirtual'] . $sSepara . $sError;
			}
			if ($DATA['cara01campus_foros'] == '') {
				$sError = $ERR['cara01campus_foros'] . $sSepara . $sError;
			}
			if ($DATA['cara01acad_hatomadovirtual'] == '') {
				$sError = $ERR['cara01acad_hatomadovirtual'] . $sSepara . $sError;
			}
			if ($DATA['cara01acad_tiemposinest'] == '') {
				$sError = $ERR['cara01acad_tiemposinest'] . $sSepara . $sError;
			}
			if ($DATA['cara01acad_modalidadbach'] == '') {
				$sError = $ERR['cara01acad_modalidadbach'] . $sSepara . $sError;
			}
			if ($DATA['cara01acad_tipocolegio'] == '') {
				$sError = $ERR['cara01acad_tipocolegio'] . $sSepara . $sError;
			}
		} else {
/*
// -- 23 de Agosto de 2022 - Esta opcion se maneja aqui, el error era de la forma....
*/
			if ($DATA['cara44acadhatenidorecesos'] == '') {
				$sError = $ERR['cara44acadhatenidorecesos'] . $sSepara . $sError;
			}
			if ($DATA['cara44acadhatenidorecesos'] == 'S') {
				if ($DATA['cara44acadrazonreceso'] == 0) {
					$sError = $ERR['cara44acadrazonreceso'] . $sSepara . $sError;
				}
				if ($DATA['cara44acadrazonreceso'] == 10) {
					if ($DATA['cara44acadrazonrecesodetalle'] == '') {
						$sError = $ERR['cara44acadrazonrecesodetalle'] . $sSepara . $sError;
					}
				}
			}
/*
*/
			if ($DATA['cara44campus_usocorreounad'] == 0) {
				$sError = $ERR['cara44campus_usocorreounad'] . $sSepara . $sError;
			}
			if ($DATA['cara44campus_usocorreounad'] == 4) {
				if ($DATA['cara44campus_usocorreounadno'] == 0) {
					$sError = $ERR['cara44campus_usocorreounadno'] . $sSepara . $sError;
				}
				if ($DATA['cara44campus_usocorreounadno'] == 5) {
					if ($DATA['cara44campus_usocorreounadnodetalle'] == '') {
						$sError = $ERR['cara44campus_usocorreounadnodetalle'] . $sSepara . $sError;
					}
				}
			}
			if ($DATA['cara44campus_medioactivunad'] == 0) {
				$sError = $ERR['cara44campus_medioactivunad'] . $sSepara . $sError;
			}
			if ($DATA['cara44campus_medioactivunad'] == 6) {
				if ($DATA['cara44campus_medioactivunaddetalle'] == '') {
					$sError = $ERR['cara44campus_medioactivunaddetalle'] . $sSepara . $sError;
				}
			}
		}
/*
// -- 23 de Agosto de 2022 - Se retira la opcion y paso unas lineas arriba....
		//El de los recesos
		if (!$bAntiguo) {
			if ($DATA['cara44acadhatenidorecesos'] == '') {
				$sError = $ERR['cara44acadhatenidorecesos'] . $sSepara . $sError;
			}
			if ($DATA['cara44acadhatenidorecesos'] == 'S') {
				if ($DATA['cara44acadrazonreceso'] == 0) {
					$sError = $ERR['cara44acadrazonreceso'] . $sSepara . $sError;
				}
				if ($DATA['cara44acadrazonreceso'] == 10) {
					if ($DATA['cara44acadrazonrecesodetalle'] == '') {
						$sError = $ERR['cara44acadrazonrecesodetalle'] . $sSepara . $sError;
					}
				}
			}
		}
	//fin de los recesos
*/
		if ($sError != '') {
			$DATA['cara01fichaaca'] = 0;
		}
	}
	if ($DATA['ficha'] == 2) {
		if ($DATA['cara01fam_familiaunad'] == '') {
			$sError = $ERR['cara01fam_familiaunad'] . $sSepara . $sError;
		}
		if ($DATA['cara01fam_posicionherm'] == '') {
			$sError = $ERR['cara01fam_posicionherm'] . $sSepara . $sError;
		}
		if ($DATA['cara01fam_numhermanos'] == '') {
			$sError = $ERR['cara01fam_numhermanos'] . $sSepara . $sError;
		}
		if ($DATA['cara01fam_escolaridadmadre'] == '') {
			$sError = $ERR['cara01fam_escolaridadmadre'] . $sSepara . $sError;
		}
		if ($DATA['cara01fam_escolaridadpadre'] == '') {
			$sError = $ERR['cara01fam_escolaridadpadre'] . $sSepara . $sError;
		}
		if ($DATA['cara01fam_dependeecon'] == '') {
			$sError = $ERR['cara01fam_dependeecon'] . $sSepara . $sError;
		}
		if ($DATA['cara01fam_personasacargo'] == '') {
			$sError = $ERR['cara01fam_personasacargo'] . $sSepara . $sError;
		}
		if ($DATA['cara01fam_hijos'] == '') {
			$sError = $ERR['cara01fam_hijos'] . $sSepara . $sError;
		}
		$bTieneHijos = false;
		$bFemenino = false;
		if ($DATA['cara01fam_hijos'] >= 1 && $DATA['cara01fam_hijos'] <= 4) {
			$bTieneHijos = true;
		}
		if ($DATA['cara01sexo'] == 'F') {
			$bFemenino = true;
		}
		if ($bTieneHijos && $bFemenino) {
			if ($DATA['cara44fam_madrecabeza'] == '') {
				$sError = $ERR['cara44fam_madrecabeza'] . $sSepara . $sError;
			}
		}
		if ($DATA['cara01fam_numpersgrupofam'] == '') {
			$sError = $ERR['cara01fam_numpersgrupofam'] . $sSepara . $sError;
		}
		if ($DATA['cara01fam_vivecon'] == '') {
			$sError = $ERR['cara01fam_vivecon'] . $sSepara . $sError;
		}
		if ($DATA['cara01fam_tipovivienda'] == '') {
			$sError = $ERR['cara01fam_tipovivienda'] . $sSepara . $sError;
		}
		if ($sError != '') {
			$DATA['cara01fichafam'] = 0;
		}
	}
	if ($DATA['ficha'] == 1) {
		if ($DATA['cara01correopersonal'] == '') {
			$sError = $ERR['cara01correopersonal'] . $sSepara . $sError;
		}
		//if ($DATA['cara01telefono2']==''){$sError=$ERR['cara01telefono2'].$sSepara.$sError;}
		if ($DATA['cara01telefono1'] == '') {
			$sError = $ERR['cara01telefono1'] . $sSepara . $sError;
		}
		if ($DATA['cara01fechaconfirmadisc'] == 0) {
			//$DATA['cara01fechaconfirmadisc']=fecha_DiaMod();
			//$sError=$ERR['cara01fechaconfirmadisc'].$sSepara.$sError;
		}
		//if ($DATA['cara01idconfirmadisc']==0){$sError=$ERR['cara01idconfirmadisc'].$sSepara.$sError;}
		$bValida = false;
		if ($DATA['cara01discversion'] == 2) {
			$bValida = true;
		}
		if ($bValida) {
			if ($DATA['cara01discv2condicionmedica'] == '') {
				$sError = $ERR['cara01discv2condicionmedica'] . $sSepara . $sError;
			} else {
				if ($DATA['cara01discv2condicionmedica'] != 0) {
					if ($DATA['cara01discv2condmeddet'] == '') {
						$sError = $ERR['cara01discv2condmeddet'] . $sSepara . $sError;
					}
				}
			}
			if ($DATA['cara01discv2condicionmedica'] == '') {
				$sError = $ERR['cara01discv2condicionmedica'] . $sSepara . $sError;
			}
			if ($DATA['cara01discv2contalento'] == '') {
				$sError = $ERR['cara01discv2contalento'] . $sSepara . $sError;
			}
			if ($DATA['cara01discv2trastornos'] == '') {
				$sError = $ERR['cara01discv2trastornos'] . $sSepara . $sError;
			}
			if ($DATA['cara01discv2trastaprende'] == '') {
				$sError = $ERR['cara01discv2trastaprende'] . $sSepara . $sError;
			}
			if ($DATA['cara01discv2tiene'] == '') {
				$sError = $ERR['cara01discv2tiene'] . $sSepara . $sError;
			}
			if ($DATA['cara02talentoexcepcional'] == '') {
				$sError = $ERR['cara02talentoexcepcional'] . $sSepara . $sError;
			}
		}
		$bValida = false;
		if ($DATA['cara01discversion'] == 1) {
			$bValida = true;
		}
		if ($DATA['cara01discversion'] == 2) {
			$bValida = true;
		}
		if ($bValida) {
			//if ($DATA['cara02discv2multipleotro']==''){$sError=$ERR['cara02discv2multipleotro'].$sSepara.$sError;}
			if ($DATA['cara02discv2multiple'] == '') {
				$sError = $ERR['cara02discv2multiple'] . $sSepara . $sError;
			}
			//if ($DATA['cara02discv2sistemicaotro']==''){$sError=$ERR['cara02discv2sistemicaotro'].$sSepara.$sError;}
			if ($DATA['cara02discv2sistemica'] == '') {
				$sError = $ERR['cara02discv2sistemica'] . $sSepara . $sError;
			}
			if ($DATA['cara02discv2psico'] == '') {
				$sError = $ERR['cara02discv2psico'] . $sSepara . $sError;
			}
			if ($DATA['cara02discv2fisica'] == '') {
				$sError = $ERR['cara02discv2fisica'] . $sSepara . $sError;
			}
			if ($DATA['cara02discv2intelectura'] == '') {
				$sError = $ERR['cara02discv2intelectura'] . $sSepara . $sError;
			}
			if ($DATA['cara01discv2sensorial'] == '') {
				$sError = $ERR['cara01discv2sensorial'] . $sSepara . $sError;
			}
		}
		if ($DATA['cara01discversion'] == 0) {
			if ($DATA['cara01disccognitiva'] == '') {
				$sError = $ERR['cara01disccognitiva'] . $sSepara . $sError;
			}
			if ($DATA['cara01discfisica'] == '') {
				$sError = $ERR['cara01discfisica'] . $sSepara . $sError;
			}
			if ($DATA['cara01discsensorial'] == '') {
				$sError = $ERR['cara01discsensorial'] . $sSepara . $sError;
			}
		}
		if ($DATA['cara01discv2tiene'] == 1) {
			if ($DATA['cara02discv2psico'] + $DATA['cara02discv2fisica'] + $DATA['cara02discv2intelectura'] + $DATA['cara01discv2sensorial'] + $DATA['cara02discv2multiple'] == 0) {
				$sError = $ERR['cara01discv2tienealguna'] . $sSepara . $sError;
			}
		}
		if ($DATA['cara01inpecrecluso'] == 'S') {
			if ($DATA['cara01centroreclusion'] == '') {
				$sError = $ERR['cara01centroreclusion'] . $sSepara . $sError;
			}
			if ($DATA['cara01inpectiempocondena'] == '') {
				$sError = $ERR['cara01inpectiempocondena'] . $sSepara . $sError;
			}
		} else {
			$DATA['cara01centroreclusion'] = 0;
			$DATA['cara01inpectiempocondena'] = 0;
		}
		if ($DATA['cara01inpecrecluso'] == '') {
			$sError = $ERR['cara01inpecrecluso'] . $sSepara . $sError;
		}
		if ($DATA['cara01inpecfuncionario'] == '') {
			$sError = $ERR['cara01inpecfuncionario'] . $sSepara . $sError;
		}
		if ($DATA['cara01fechaconfirmacr'] == 0) {
			//$DATA['cara01fechaconfirmacr']=fecha_DiaMod();
			//$sError=$ERR['cara01fechaconfirmacr'].$sSepara.$sError;
		}
		//if ($DATA['cara01idconfirmacr']==0){$sError=$ERR['cara01idconfirmacr'].$sSepara.$sError;}
		if ($DATA['cara01victimaacr'] == '') {
			$sError = $ERR['cara01victimaacr'] . $sSepara . $sError;
		}
		if ($DATA['cara01fechaconfirmadesp'] == 0) {
			//$DATA['cara01fechaconfirmadesp']=fecha_DiaMod();
			//$sError=$ERR['cara01fechaconfirmadesp'].$sSepara.$sError;
		}
		//if ($DATA['cara01idconfirmadesp']==0){$sError=$ERR['cara01idconfirmadesp'].$sSepara.$sError;}
		if ($DATA['cara01victimadesplazado'] == '') {
			$sError = $ERR['cara01victimadesplazado'] . $sSepara . $sError;
		}
		if ($DATA['cara01indigenas'] == '') {
			$sError = $ERR['cara01indigenas'] . $sSepara . $sError;
		}
		if ($DATA['cara01rom'] == '') {
			$sError = $ERR['cara01rom'] . $sSepara . $sError;
		}
		if ($DATA['cara01otracomunnegras'] == '') {
			$sError = $ERR['cara01otracomunnegras'] . $sSepara . $sError;
		}
		if ($DATA['cara01afrocolombiano'] == '') {
			$sError = $ERR['cara01afrocolombiano'] . $sSepara . $sError;
		}
		if ($DATA['cara01palenquero'] == '') {
			$sError = $ERR['cara01palenquero'] . $sSepara . $sError;
		}
		if ($DATA['cara01raizal'] == '') {
			$sError = $ERR['cara01raizal'] . $sSepara . $sError;
		}
		if ($DATA['cara44campesinado'] == '') {
			$sError = $ERR['cara44campesinado'] . $sSepara . $sError;
		}
		if ($DATA['cara01matconvenio'] == '') {
			$sError = $ERR['cara01matconvenio'] . $sSepara . $sError;
		}
		if ($DATA['cara01idcead'] == '') {
			$sError = $ERR['cara01idcead'] . $sSepara . $sError;
		}
		if ($DATA['cara01idzona'] == '') {
			$sError = $ERR['cara01idzona'] . $sSepara . $sError;
		}
		//if ($DATA['cara01correocontacto']==''){$sError=$ERR['cara01correocontacto'].$sSepara.$sError;}
		if ($DATA['cara01celcontacto'] == '') {
			$sError = $ERR['cara01celcontacto'] . $sSepara . $sError;
		}
		if ($DATA['cara01parentezcocontacto'] == '') {
			$sError = $ERR['cara01parentezcocontacto'] . $sSepara . $sError;
		}
		if ($DATA['cara01nomcontacto'] == '') {
			$sError = $ERR['cara01nomcontacto'] . $sSepara . $sError;
		}
		if ($DATA['cara01estcivil'] == '') {
			$sError = $ERR['cara01estcivil'] . $sSepara . $sError;
		}
		if ($DATA['cara01zonares'] == '') {
			$sError = $ERR['cara01zonares'] . $sSepara . $sError;
		}
		if ($DATA['cara01estrato'] == '') {
			$sError = $ERR['cara01estrato'] . $sSepara . $sError;
		}
		if ($DATA['cara01direccion'] == '') {
			$sError = $ERR['cara01direccion'] . $sSepara . $sError;
		}
		//if ($DATA['cara01nomciudad']==''){$sError=$ERR['cara01nomciudad'].$sSepara.$sError;}
		if ($DATA['cara01ciudad'] == '') {
			$sError = $ERR['cara01ciudad'] . $sSepara . $sError;
		}
		if ($DATA['cara01depto'] == '') {
			$sError = $ERR['cara01depto'] . $sSepara . $sError;
		}
		if ($DATA['cara01pais'] == '') {
			$sError = $ERR['cara01pais'] . $sSepara . $sError;
		}
		if ($DATA['cara01sexo'] == '') {
			$sError = $ERR['cara01sexo'] . $sSepara . $sError;
		}
		if ($DATA['cara01fechaencuesta'] == 0) {
			//$DATA['cara01fechaencuesta']=fecha_DiaMod();
			//$sError=$ERR['cara01fechaencuesta'].$sSepara.$sError;
		}
		if ($DATA['cara44sexoversion'] == 1) {
			if ($DATA['cara44sexov1identidadgen'] == 0) {
				$sError = $ERR['cara44sexov1identidadgen'] . $sSepara . $sError;
			}
			if ($DATA['cara44sexov1orientasexo'] == 0) {
				$sError = $ERR['cara44sexov1orientasexo'] . $sSepara . $sError;
			}
		} else {
			
		}
		//Fin de las valiaciones NO LLAVE.
		if ($sError != '') {
			$DATA['cara01fichaper'] = 0;
		}
	}
	if ($DATA['cara01completa'] == 'S') {
		if ($DATA['cara01fichaquimica'] == 0) {
			$sError = $ERR['cara01fichaquimica'] . $sSepara . $sError;
		}
		if ($DATA['cara01fichafisica'] == 0) {
			$sError = $ERR['cara01fichafisica'] . $sSepara . $sError;
		}
		if ($DATA['cara01fichabiolog'] == 0) {
			$sError = $ERR['cara01fichabiolog'] . $sSepara . $sError;
		}
		if ($DATA['cara01fichaingles'] == 0) {
			$sError = $ERR['cara01fichaingles'] . $sSepara . $sError;
		}
		if ($DATA['cara01ficharazona'] == 0) {
			$sError = $ERR['cara01ficharazona'] . $sSepara . $sError;
		}
		if ($DATA['cara01fichalectura'] == 0) {
			$sError = $ERR['cara01fichalectura'] . $sSepara . $sError;
		}
		if ($DATA['cara01fichadigital'] == 0) {
			$sError = $ERR['cara01fichadigital'] . $sSepara . $sError;
		}
		if ($DATA['cara01fichapsico'] == 0) {
			$sError = $ERR['cara01fichapsico'] . $sSepara . $sError;
		}
		if ($DATA['cara01fichabien'] == 0) {
			$sError = $ERR['cara01fichabien'] . $sSepara . $sError;
		}
		if ($DATA['cara01fichalab'] == 0) {
			$sError = $ERR['cara01fichalab'] . $sSepara . $sError;
		}
		if ($DATA['cara01fichaaca'] == 0) {
			$sError = $ERR['cara01fichaaca'] . $sSepara . $sError;
		}
		if ($DATA['cara01fichafam'] == 0) {
			$sError = $ERR['cara01fichafam'] . $sSepara . $sError;
		}
		if ($DATA['cara01fichaper'] == 0) {
			$sError = $ERR['cara01fichaper'] . $sSepara . $sError;
		}
		if ($sError != '') {
			$DATA['cara01completa'] = 'N';
			$DATA['cara01fechaencuesta'] = 0;
		}
		$sErrorCerrando = $sError;
		$sError = '';
	}
	//Valiaciones de campos obligatorios en todo guardar.
	if ($DATA['cara01idtercero'] == 0) {
		$sError = $ERR['cara01idtercero'];
	}
	if ($DATA['cara01idperaca'] == '') {
		$sError = $ERR['cara01idperaca'];
	}
	// -- Tiene un cerrado.
	if ($DATA['cara01completa'] == 'S') {
		//Validaciones previas a cerrar
		//Aprobó las Validaciones al cerrar
		if ($sError . $sErrorCerrando != '') {
			$DATA['cara01completa'] = 'N';
			$sErrorCerrando = $sError . ' ' . $sErrorCerrando;
			$sError = '';
		} else {
			$bCerrando = true;
			//Acciones del cierre
		}
	}
	// -- Fin del cerrado.
	// -- Se verifican los valores de campos de otras tablas.
	if (false) {
		if ($sError == '') {
			$sError = tabla_terceros_existe($DATA['cara01idconsejero_td'], $DATA['cara01idconsejero_doc'], $objDB, 'El tercero Consejero ');
		}
		if ($sError == '') {
			list($sError, $sInfo) = tercero_Bloqueado($DATA['cara01idconsejero'], $objDB);
			if ($sInfo != '') {
				$sError = $sError . '<br>' . sInfo;
			}
		}
		if ($sError == '') {
			$sError = tabla_terceros_existe($DATA['cara01idconfirmadisc_td'], $DATA['cara01idconfirmadisc_doc'], $objDB, 'El tercero Confirmadisc ');
		}
		if ($sError == '') {
			list($sError, $sInfo) = tercero_Bloqueado($DATA['cara01idconfirmadisc'], $objDB);
			if ($sInfo != '') {
				$sError = $sError . '<br>' . sInfo;
			}
		}
		if ($sError == '') {
			$sError = tabla_terceros_existe($DATA['cara01idconfirmacr_td'], $DATA['cara01idconfirmacr_doc'], $objDB, 'El tercero Confirmacr ');
		}
		if ($sError == '') {
			list($sError, $sInfo) = tercero_Bloqueado($DATA['cara01idconfirmacr'], $objDB);
			if ($sInfo != '') {
				$sError = $sError . '<br>' . sInfo;
			}
		}
		if ($sError == '') {
			$sError = tabla_terceros_existe($DATA['cara01idconfirmadesp_td'], $DATA['cara01idconfirmadesp_doc'], $objDB, 'El tercero Confirmadesp ');
		}
		if ($sError == '') {
			list($sError, $sInfo) = tercero_Bloqueado($DATA['cara01idconfirmadesp'], $objDB);
			if ($sInfo != '') {
				$sError = $sError . '<br>' . sInfo;
			}
		}
		// No se tiene en cuenta estas validaciones.
	}
	if ($sError == '') {
		$sError = tabla_terceros_existe($DATA['cara01idtercero_td'], $DATA['cara01idtercero_doc'], $objDB, 'El tercero Tercero ');
	}
	if ($sError == '') {
		list($sError, $sInfo) = tercero_Bloqueado($DATA['cara01idtercero'], $objDB);
		if ($sInfo != '') {
			$sError = $sError . '<br>' . sInfo;
		}
	}
	$bQuitarCodigo = false;
	$sCampoCodigo = '';
	if ($sError == '') {
		if ($DATA['paso'] == 10) {
			$sSQL = 'SELECT 1 FROM cara01encuesta WHERE cara01idperaca=' . $DATA['cara01idperaca'] . ' AND cara01idtercero="' . $DATA['cara01idtercero'] . '"';
			$result = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($result) != 0) {
				$sError = $ERR['existe'];
			} else {
				if ($DATA['cara01idtercero'] != $_SESSION['unad_id_tercero']) {
					$sError = $ERR['2'];
				}
			}
		} else {
			if ($DATA['cara01idtercero'] != $_SESSION['unad_id_tercero']) {
				list($devuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 13, $_SESSION['unad_id_tercero'], $objDB, $bDebug);
				if (!$devuelve) {
					$sError = $ERR['3'];
				}
			}
		}
	}
	if ($sError == '') {
		if ($DATA['paso'] == 10) {
			//Preparar el Id, Si no lo hay se quita la comprobación.
			$DATA['cara01id'] = tabla_consecutivo('cara01encuesta', 'cara01id', '', $objDB);
			if ($DATA['cara01id'] == -1) {
				$sError = $objDB->serror;
			}
			$DATA['cara44id'] = $DATA['cara01id'];
		}
	}
	if ($sError == '') {
		$bpasa = false;
		if ($DATA['paso'] == 10) {
			$DATA['cara01completa'] = 'N';
			$DATA['cara01fichaper'] = 0;
			$DATA['cara01fichafam'] = 0;
			$DATA['cara01fichaaca'] = 0;
			$DATA['cara01fichalab'] = 0;
			$DATA['cara01fichabien'] = 0;
			$DATA['cara01fichapsico'] = 0;
			$cara01fechaencuesta = fecha_DiaMod();
			$DATA['cara01agnos'] = 0;
			//$DATA['cara01idconfirmadesp']=0; //$_SESSION['u_idtercero'];
			$cara01fechaconfirmadesp = 0;
			//$DATA['cara01idconfirmacr']=0; //$_SESSION['u_idtercero'];
			$cara01fechaconfirmacr = 0;
			//$DATA['cara01idconfirmadisc']=0; //$_SESSION['u_idtercero'];
			$cara01fechaconfirmadisc = 0;
			$DATA['cara01fichadigital'] = 0;
			$DATA['cara01fichalectura'] = 0;
			$DATA['cara01ficharazona'] = 0;
			$DATA['cara01fichaingles'] = 0;
			//$DATA['cara01idconsejero']=0; //$_SESSION['u_idtercero'];
			$cara01fechainicio = fecha_DiaMod();
			$DATA['cara01nivelbiolog'] = 0;
			$DATA['cara01nivelfisica'] = 0;
			$DATA['cara01nivelquimica'] = 0;
			$DATA['cara01discversion'] = 1;
			$sCampos2301 = 'cara01idperaca, cara01idtercero, cara01id, cara01completa, cara01fichaper, 
cara01fichafam, cara01fichaaca, cara01fichalab, cara01fichabien, cara01fichapsico, 
cara01fichadigital, cara01fichalectura, cara01ficharazona, cara01fichaingles, cara01fechaencuesta, 
cara01agnos, cara01sexo, cara01pais, cara01depto, cara01ciudad, 
cara01nomciudad, cara01direccion, cara01estrato, cara01zonares, cara01estcivil, 
cara01nomcontacto, cara01parentezcocontacto, cara01celcontacto, cara01correocontacto, cara01idzona, 
cara01idcead, cara01matconvenio, cara01raizal, cara01palenquero, cara01afrocolombiano, 
cara01otracomunnegras, cara01rom, cara01indigenas, cara01victimadesplazado, cara01idconfirmadesp, 
cara01fechaconfirmadesp, cara01victimaacr, cara01idconfirmacr, cara01fechaconfirmacr, cara01inpecfuncionario, 
cara01inpecrecluso, cara01inpectiempocondena, cara01centroreclusion, cara01discsensorial, cara01discfisica, 
cara01disccognitiva, cara01idconfirmadisc, cara01fechaconfirmadisc, cara01fam_tipovivienda, cara01fam_vivecon, 
cara01fam_numpersgrupofam, cara01fam_hijos, cara01fam_personasacargo, cara01fam_dependeecon, cara01fam_escolaridadpadre, 
cara01fam_escolaridadmadre, cara01fam_numhermanos, cara01fam_posicionherm, cara01fam_familiaunad, cara01acad_tipocolegio, 
cara01acad_modalidadbach, cara01acad_estudioprev, cara01acad_ultnivelest, cara01acad_obtubodiploma, cara01acad_hatomadovirtual, 
cara01acad_tiemposinest, cara01acad_razonestudio, cara01acad_primeraopc, cara01acad_programagusto, cara01acad_razonunad, 
cara01campus_compescrito, cara01campus_portatil, cara01campus_tableta, cara01campus_telefono, cara01campus_energia, 
cara01campus_internetreside, cara01campus_expvirtual, cara01campus_ofimatica, cara01campus_foros, cara01campus_conversiones, 
cara01campus_usocorreo, cara01campus_aprendtexto, cara01campus_aprendvideo, cara01campus_aprendmapas, cara01campus_aprendeanima, 
cara01campus_mediocomunica, cara01lab_situacion, cara01lab_sector, cara01lab_caracterjuri, cara01lab_cargo, 
cara01lab_antiguedad, cara01lab_tipocontrato, cara01lab_rangoingreso, cara01lab_tiempoacadem, cara01lab_tipoempresa, 
cara01lab_tiempoindepen, cara01lab_debebusctrab, cara01lab_origendinero, cara01bien_baloncesto, cara01bien_voleibol, 
cara01bien_futbolsala, cara01bien_artesmarc, cara01bien_tenisdemesa, cara01bien_ajedrez, cara01bien_juegosautoc, 
cara01bien_interesrepdeporte, cara01bien_deporteint, cara01bien_teatro, cara01bien_danza, cara01bien_musica, 
cara01bien_circo, cara01bien_artplast, cara01bien_cuenteria, cara01bien_interesreparte, cara01bien_arteint, 
cara01bien_interpreta, cara01bien_nivelinter, cara01bien_danza_mod, cara01bien_danza_clas, cara01bien_danza_cont, 
cara01bien_danza_folk, cara01bien_niveldanza, cara01bien_emprendedor, cara01bien_nombreemp, cara01bien_capacempren, 
cara01bien_tipocapacita, cara01bien_impvidasalud, cara01bien_estraautocuid, cara01bien_pv_personal, cara01bien_pv_familiar, 
cara01bien_pv_academ, cara01bien_pv_labora, cara01bien_pv_pareja, cara01bien_amb, cara01bien_amb_agu, 
cara01bien_amb_bom, cara01bien_amb_car, cara01bien_amb_info, cara01bien_amb_temas, cara01psico_costoemocion, 
cara01psico_reaccionimpre, cara01psico_estres, cara01psico_pocotiempo, cara01psico_actitudvida, cara01psico_duda, 
cara01psico_problemapers, cara01psico_satisfaccion, cara01psico_discusiones, cara01psico_atencion, cara01niveldigital, 
cara01nivellectura, cara01nivelrazona, cara01nivelingles, cara01idconsejero, cara01fechainicio, 
cara01telefono1, cara01telefono2, cara01correopersonal, cara01idprograma, cara01idescuela, 
cara01fichabiolog, cara01nivelbiolog, cara01fichafisica, cara01nivelfisica, cara01fichaquimica, 
cara01nivelquimica, cara01tipocaracterizacion,



cara01discversion, 
cara01discv2sensorial, cara02discv2intelectura, cara02discv2fisica, cara02discv2psico, cara02discv2sistemica, 
cara02discv2sistemicaotro, cara02discv2multiple, cara02discv2multipleotro, cara02talentoexcepcional, 
cara01discv2tiene, cara01discv2trastaprende, cara01discv2soporteorigen, 
cara01discv2archivoorigen, cara01discv2trastornos, cara01discv2contalento, cara01discv2condicionmedica, cara01discv2condmeddet, 
cara01discv2pruebacoeficiente';
			$sCampos2344 = 'cara44id, cara44campesinado, cara44sexoversion, cara44sexov1identidadgen, cara44sexov1orientasexo, cara44bienversion, cara44bienv2altoren, 
cara44bienv2atletismo, cara44bienv2baloncesto, cara44bienv2futbol, cara44bienv2gimnasia, cara44bienv2natacion, cara44bienv2voleibol, cara44bienv2tenis, cara44bienv2paralimpico, cara44bienv2otrodeporte, 
cara44bienv2otrodeportedetalle, cara44bienv2activdanza, cara44bienv2activmusica, cara44bienv2activteatro, cara44bienv2activartes, cara44bienv2activliteratura, cara44bienv2activculturalotra, 
cara44bienv2activculturalotradetalle, cara44bienv2evenfestfolc, cara44bienv2evenexpoarte, cara44bienv2evenhistarte, cara44bienv2evengalfoto, cara44bienv2evenliteratura, cara44bienv2eventeatro, 
cara44bienv2evencine, cara44bienv2evenculturalotro, cara44bienv2evenculturalotrodetalle, cara44bienv2emprendimiento, cara44bienv2empresa, cara44bienv2emprenrecursos, cara44bienv2emprenconocim, 
cara44bienv2emprenplan, cara44bienv2emprenejecutar, cara44bienv2emprenfortconocim, cara44bienv2emprenidentproblema, cara44bienv2emprenotro, cara44bienv2emprenotrodetalle, cara44bienv2emprenmarketing, 
cara44bienv2emprenplannegocios, cara44bienv2emprenideas, cara44bienv2emprencreacion, cara44bienv2saludfacteconom, cara44bienv2saludpreocupacion, cara44bienv2saludconsumosust, cara44bienv2saludinsomnio, 
cara44bienv2saludclimalab, cara44bienv2saludalimenta, cara44bienv2saludemocion, cara44bienv2saludestado, cara44bienv2saludmedita, cara44bienv2crecimedusexual, cara44bienv2crecimcultciudad, 
cara44bienv2crecimrelpareja, cara44bienv2crecimrelinterp, cara44bienv2crecimdinamicafam, cara44bienv2crecimautoestima, cara44bienv2creciminclusion, cara44bienv2creciminteliemoc, cara44bienv2crecimcultural, 
cara44bienv2crecimartistico, cara44bienv2crecimdeporte, cara44bienv2crecimambiente, cara44bienv2crecimhabsocio, cara44bienv2ambienbasura, cara44bienv2ambienreutiliza, cara44bienv2ambienluces, 
cara44bienv2ambienfrutaverd, cara44bienv2ambienenchufa, cara44bienv2ambiengrifo, cara44bienv2ambienbicicleta, cara44bienv2ambientranspub, cara44bienv2ambienducha, cara44bienv2ambiencaminata, 
cara44bienv2ambiensiembra, cara44bienv2ambienconferencia, cara44bienv2ambienrecicla, cara44bienv2ambienotraactiv, cara44bienv2ambienotraactivdetalle, cara44bienv2ambienreforest, cara44bienv2ambienmovilidad, 
cara44bienv2ambienclimatico, cara44bienv2ambienecofemin, cara44bienv2ambienbiodiver, cara44bienv2ambienecologia, cara44bienv2ambieneconomia, cara44bienv2ambienrecnatura, cara44bienv2ambienreciclaje, 
cara44bienv2ambienmascota, cara44bienv2ambiencartohum, cara44bienv2ambienespiritu, cara44bienv2ambiencarga, cara44bienv2ambienotroenfoq, cara44bienv2ambienotroenfoqdetalle, cara44fam_madrecabeza, 
cara44acadhatenidorecesos, cara44acadrazonreceso, cara44acadrazonrecesodetalle, cara44campus_usocorreounad, cara44campus_usocorreounadno, cara44campus_usocorreounadnodetalle, cara44campus_medioactivunad, cara44campus_medioactivunaddetalle';
			$sValores2301 = '' . $DATA['cara01idperaca'] . ', ' . $DATA['cara01idtercero'] . ', ' . $DATA['cara01id'] . ', "' . $DATA['cara01completa'] . '", ' . $DATA['cara01fichaper'] . ', 
' . $DATA['cara01fichafam'] . ', ' . $DATA['cara01fichaaca'] . ', ' . $DATA['cara01fichalab'] . ', ' . $DATA['cara01fichabien'] . ', ' . $DATA['cara01fichapsico'] . ', 
' . $DATA['cara01fichadigital'] . ', ' . $DATA['cara01fichalectura'] . ', ' . $DATA['cara01ficharazona'] . ', ' . $DATA['cara01fichaingles'] . ', "' . $DATA['cara01fechaencuesta'] . '", 
' . $DATA['cara01agnos'] . ', "' . $DATA['cara01sexo'] . '", "' . $DATA['cara01pais'] . '", "' . $DATA['cara01depto'] . '", "' . $DATA['cara01ciudad'] . '", 
"' . $DATA['cara01nomciudad'] . '", "' . $DATA['cara01direccion'] . '", ' . $DATA['cara01estrato'] . ', "' . $DATA['cara01zonares'] . '", "' . $DATA['cara01estcivil'] . '", 
"' . $DATA['cara01nomcontacto'] . '", "' . $DATA['cara01parentezcocontacto'] . '", "' . $DATA['cara01celcontacto'] . '", "' . $DATA['cara01correocontacto'] . '", ' . $DATA['cara01idzona'] . ', 
' . $DATA['cara01idcead'] . ', "' . $DATA['cara01matconvenio'] . '", "' . $DATA['cara01raizal'] . '", "' . $DATA['cara01palenquero'] . '", "' . $DATA['cara01afrocolombiano'] . '", 
"' . $DATA['cara01otracomunnegras'] . '", "' . $DATA['cara01rom'] . '", ' . $DATA['cara01indigenas'] . ', "' . $DATA['cara01victimadesplazado'] . '", ' . $DATA['cara01idconfirmadesp'] . ', 
"' . $DATA['cara01fechaconfirmadesp'] . '", "' . $DATA['cara01victimaacr'] . '", ' . $DATA['cara01idconfirmacr'] . ', "' . $DATA['cara01fechaconfirmacr'] . '", "' . $DATA['cara01inpecfuncionario'] . '", 
"' . $DATA['cara01inpecrecluso'] . '", ' . $DATA['cara01inpectiempocondena'] . ', ' . $DATA['cara01centroreclusion'] . ', "' . $DATA['cara01discsensorial'] . '", "' . $DATA['cara01discfisica'] . '", 
"' . $DATA['cara01disccognitiva'] . '", ' . $DATA['cara01idconfirmadisc'] . ', "' . $DATA['cara01fechaconfirmadisc'] . '", ' . $DATA['cara01fam_tipovivienda'] . ', ' . $DATA['cara01fam_vivecon'] . ', 
' . $DATA['cara01fam_numpersgrupofam'] . ', ' . $DATA['cara01fam_hijos'] . ', ' . $DATA['cara01fam_personasacargo'] . ', "' . $DATA['cara01fam_dependeecon'] . '", ' . $DATA['cara01fam_escolaridadpadre'] . ', 
' . $DATA['cara01fam_escolaridadmadre'] . ', ' . $DATA['cara01fam_numhermanos'] . ', ' . $DATA['cara01fam_posicionherm'] . ', "' . $DATA['cara01fam_familiaunad'] . '", ' . $DATA['cara01acad_tipocolegio'] . ', 
' . $DATA['cara01acad_modalidadbach'] . ', "' . $DATA['cara01acad_estudioprev'] . '", ' . $DATA['cara01acad_ultnivelest'] . ', "' . $DATA['cara01acad_obtubodiploma'] . '", "' . $DATA['cara01acad_hatomadovirtual'] . '", 
' . $DATA['cara01acad_tiemposinest'] . ', ' . $DATA['cara01acad_razonestudio'] . ', "' . $DATA['cara01acad_primeraopc'] . '", "' . $DATA['cara01acad_programagusto'] . '", ' . $DATA['cara01acad_razonunad'] . ', 
"' . $DATA['cara01campus_compescrito'] . '", "' . $DATA['cara01campus_portatil'] . '", "' . $DATA['cara01campus_tableta'] . '", "' . $DATA['cara01campus_telefono'] . '", ' . $DATA['cara01campus_energia'] . ', 
' . $DATA['cara01campus_internetreside'] . ', "' . $DATA['cara01campus_expvirtual'] . '", "' . $DATA['cara01campus_ofimatica'] . '", "' . $DATA['cara01campus_foros'] . '", "' . $DATA['cara01campus_conversiones'] . '", 
' . $DATA['cara01campus_usocorreo'] . ', "' . $DATA['cara01campus_aprendtexto'] . '", "' . $DATA['cara01campus_aprendvideo'] . '", "' . $DATA['cara01campus_aprendmapas'] . '", "' . $DATA['cara01campus_aprendeanima'] . '", 
' . $DATA['cara01campus_mediocomunica'] . ', ' . $DATA['cara01lab_situacion'] . ', ' . $DATA['cara01lab_sector'] . ', ' . $DATA['cara01lab_caracterjuri'] . ', ' . $DATA['cara01lab_cargo'] . ', 
' . $DATA['cara01lab_antiguedad'] . ', ' . $DATA['cara01lab_tipocontrato'] . ', ' . $DATA['cara01lab_rangoingreso'] . ', ' . $DATA['cara01lab_tiempoacadem'] . ', ' . $DATA['cara01lab_tipoempresa'] . ', 
' . $DATA['cara01lab_tiempoindepen'] . ', "' . $DATA['cara01lab_debebusctrab'] . '", ' . $DATA['cara01lab_origendinero'] . ', "' . $DATA['cara01bien_baloncesto'] . '", "' . $DATA['cara01bien_voleibol'] . '", 
"' . $DATA['cara01bien_futbolsala'] . '", "' . $DATA['cara01bien_artesmarc'] . '", "' . $DATA['cara01bien_tenisdemesa'] . '", "' . $DATA['cara01bien_ajedrez'] . '", "' . $DATA['cara01bien_juegosautoc'] . '", 
"' . $DATA['cara01bien_interesrepdeporte'] . '", "' . $DATA['cara01bien_deporteint'] . '", "' . $DATA['cara01bien_teatro'] . '", "' . $DATA['cara01bien_danza'] . '", "' . $DATA['cara01bien_musica'] . '", 
"' . $DATA['cara01bien_circo'] . '", "' . $DATA['cara01bien_artplast'] . '", "' . $DATA['cara01bien_cuenteria'] . '", "' . $DATA['cara01bien_interesreparte'] . '", "' . $DATA['cara01bien_arteint'] . '", 
' . $DATA['cara01bien_interpreta'] . ', ' . $DATA['cara01bien_nivelinter'] . ', "' . $DATA['cara01bien_danza_mod'] . '", "' . $DATA['cara01bien_danza_clas'] . '", "' . $DATA['cara01bien_danza_cont'] . '", 
"' . $DATA['cara01bien_danza_folk'] . '", ' . $DATA['cara01bien_niveldanza'] . ', "' . $DATA['cara01bien_emprendedor'] . '", "' . $DATA['cara01bien_nombreemp'] . '", "' . $DATA['cara01bien_capacempren'] . '", 
"' . $DATA['cara01bien_tipocapacita'] . '", "' . $DATA['cara01bien_impvidasalud'] . '", "' . $DATA['cara01bien_estraautocuid'] . '", "' . $DATA['cara01bien_pv_personal'] . '", "' . $DATA['cara01bien_pv_familiar'] . '", 
"' . $DATA['cara01bien_pv_academ'] . '", "' . $DATA['cara01bien_pv_labora'] . '", "' . $DATA['cara01bien_pv_pareja'] . '", "' . $DATA['cara01bien_amb'] . '", "' . $DATA['cara01bien_amb_agu'] . '", 
"' . $DATA['cara01bien_amb_bom'] . '", "' . $DATA['cara01bien_amb_car'] . '", "' . $DATA['cara01bien_amb_info'] . '", "' . $DATA['cara01bien_amb_temas'] . '", ' . $DATA['cara01psico_costoemocion'] . ', 
' . $DATA['cara01psico_reaccionimpre'] . ', ' . $DATA['cara01psico_estres'] . ', ' . $DATA['cara01psico_pocotiempo'] . ', ' . $DATA['cara01psico_actitudvida'] . ', ' . $DATA['cara01psico_duda'] . ', 
' . $DATA['cara01psico_problemapers'] . ', ' . $DATA['cara01psico_satisfaccion'] . ', ' . $DATA['cara01psico_discusiones'] . ', ' . $DATA['cara01psico_atencion'] . ', ' . $DATA['cara01niveldigital'] . ', 
' . $DATA['cara01nivellectura'] . ', ' . $DATA['cara01nivelrazona'] . ', ' . $DATA['cara01nivelingles'] . ', ' . $DATA['cara01idconsejero'] . ', "' . $DATA['cara01fechainicio'] . '", 
"' . $DATA['cara01telefono1'] . '", "' . $DATA['cara01telefono2'] . '", "' . $DATA['cara01correopersonal'] . '", ' . $DATA['cara01idprograma'] . ', ' . $DATA['cara01idescuela'] . ', 
' . $DATA['cara01fichabiolog'] . ', ' . $DATA['cara01nivelbiolog'] . ', ' . $DATA['cara01fichafisica'] . ', ' . $DATA['cara01nivelfisica'] . ', ' . $DATA['cara01fichaquimica'] . ', 
' . $DATA['cara01nivelquimica'] . ', ' . $DATA['cara01tipocaracterizacion'] . ', 



' . $DATA['cara01discversion'] . ', 
' . $DATA['cara01discv2sensorial'] . ', ' . $DATA['cara02discv2intelectura'] . ', ' . $DATA['cara02discv2fisica'] . ', ' . $DATA['cara02discv2psico'] . ', ' . $DATA['cara02discv2sistemica'] . ', 
"' . $DATA['cara02discv2sistemicaotro'] . '", ' . $DATA['cara02discv2multiple'] . ', "' . $DATA['cara02discv2multipleotro'] . '", ' . $DATA['cara02talentoexcepcional'] . ', 
' . $DATA['cara01discv2tiene'] . ', ' . $DATA['cara01discv2trastaprende'] . ', ' . $DATA['cara01discv2soporteorigen'] . ', 
' . $DATA['cara01discv2archivoorigen'] . ', ' . $DATA['cara01discv2trastornos'] . ', ' . $DATA['cara01discv2contalento'] . ', ' . $DATA['cara01discv2condicionmedica'] . ', "' . $DATA['cara01discv2condmeddet'] . '", 
' . $DATA['cara01discv2pruebacoeficiente'] . '';
			$sValores2344 = '' . $DATA['cara44id'] . ', ' . $DATA['cara44campesinado'] . ', ' . $DATA['cara44sexoversion'] . ', ' . $DATA['cara44sexov1identidadgen'] . ', ' . $DATA['cara44sexov1orientasexo'] . ', ' . $DATA['cara44bienversion'] . ', "' . $DATA['cara44bienv2altoren'] . '", "' . $DATA['cara44bienv2atletismo'] . '", "' . $DATA['cara44bienv2baloncesto'] . '", "' . $DATA['cara44bienv2futbol'] . '", 
"' . $DATA['cara44bienv2gimnasia'] . '", "' . $DATA['cara44bienv2natacion'] . '", "' . $DATA['cara44bienv2voleibol'] . '", "' . $DATA['cara44bienv2tenis'] . '", "' . $DATA['cara44bienv2paralimpico'] . '", "' . $DATA['cara44bienv2otrodeporte'] . '", 
"' . $DATA['cara44bienv2otrodeportedetalle'] . '", "' . $DATA['cara44bienv2activdanza'] . '", "' . $DATA['cara44bienv2activmusica'] . '", "' . $DATA['cara44bienv2activteatro'] . '", "' . $DATA['cara44bienv2activartes'] . '", 
"' . $DATA['cara44bienv2activliteratura'] . '", "' . $DATA['cara44bienv2activculturalotra'] . '", "' . $DATA['cara44bienv2activculturalotradetalle'] . '", "' . $DATA['cara44bienv2evenfestfolc'] . '", "' . $DATA['cara44bienv2evenexpoarte'] . '", 
"' . $DATA['cara44bienv2evenhistarte'] . '", "' . $DATA['cara44bienv2evengalfoto'] . '", "' . $DATA['cara44bienv2evenliteratura'] . '", "' . $DATA['cara44bienv2eventeatro'] . '", "' . $DATA['cara44bienv2evencine'] . '", 
"' . $DATA['cara44bienv2evenculturalotro'] . '", "' . $DATA['cara44bienv2evenculturalotrodetalle'] . '", "' . $DATA['cara44bienv2emprendimiento'] . '", "' . $DATA['cara44bienv2empresa'] . '", "' . $DATA['cara44bienv2emprenrecursos'] . '", 
"' . $DATA['cara44bienv2emprenconocim'] . '", "' . $DATA['cara44bienv2emprenplan'] . '", "' . $DATA['cara44bienv2emprenejecutar'] . '", "' . $DATA['cara44bienv2emprenfortconocim'] . '", "' . $DATA['cara44bienv2emprenidentproblema'] . '", 
"' . $DATA['cara44bienv2emprenotro'] . '", "' . $DATA['cara44bienv2emprenotrodetalle'] . '", "' . $DATA['cara44bienv2emprenmarketing'] . '", "' . $DATA['cara44bienv2emprenplannegocios'] . '", "' . $DATA['cara44bienv2emprenideas'] . '", 
"' . $DATA['cara44bienv2emprencreacion'] . '", "' . $DATA['cara44bienv2saludfacteconom'] . '", "' . $DATA['cara44bienv2saludpreocupacion'] . '", "' . $DATA['cara44bienv2saludconsumosust'] . '", "' . $DATA['cara44bienv2saludinsomnio'] . '", 
"' . $DATA['cara44bienv2saludclimalab'] . '", "' . $DATA['cara44bienv2saludalimenta'] . '", "' . $DATA['cara44bienv2saludemocion'] . '", "' . $DATA['cara44bienv2saludestado'] . '", "' . $DATA['cara44bienv2saludmedita'] . '", 
"' . $DATA['cara44bienv2crecimedusexual'] . '", "' . $DATA['cara44bienv2crecimcultciudad'] . '", "' . $DATA['cara44bienv2crecimrelpareja'] . '", "' . $DATA['cara44bienv2crecimrelinterp'] . '", "' . $DATA['cara44bienv2crecimdinamicafam'] . '", 
"' . $DATA['cara44bienv2crecimautoestima'] . '", "' . $DATA['cara44bienv2creciminclusion'] . '", "' . $DATA['cara44bienv2creciminteliemoc'] . '", "' . $DATA['cara44bienv2crecimcultural'] . '", "' . $DATA['cara44bienv2crecimartistico'] . '", 
"' . $DATA['cara44bienv2crecimdeporte'] . '", "' . $DATA['cara44bienv2crecimambiente'] . '", "' . $DATA['cara44bienv2crecimhabsocio'] . '", "' . $DATA['cara44bienv2ambienbasura'] . '", "' . $DATA['cara44bienv2ambienreutiliza'] . '", 
"' . $DATA['cara44bienv2ambienluces'] . '", "' . $DATA['cara44bienv2ambienfrutaverd'] . '", "' . $DATA['cara44bienv2ambienenchufa'] . '", "' . $DATA['cara44bienv2ambiengrifo'] . '", "' . $DATA['cara44bienv2ambienbicicleta'] . '", 
"' . $DATA['cara44bienv2ambientranspub'] . '", "' . $DATA['cara44bienv2ambienducha'] . '", "' . $DATA['cara44bienv2ambiencaminata'] . '", "' . $DATA['cara44bienv2ambiensiembra'] . '", "' . $DATA['cara44bienv2ambienconferencia'] . '", 
"' . $DATA['cara44bienv2ambienrecicla'] . '", "' . $DATA['cara44bienv2ambienotraactiv'] . '", "' . $DATA['cara44bienv2ambienotraactivdetalle'] . '", "' . $DATA['cara44bienv2ambienreforest'] . '", "' . $DATA['cara44bienv2ambienmovilidad'] . '", 
"' . $DATA['cara44bienv2ambienclimatico'] . '", "' . $DATA['cara44bienv2ambienecofemin'] . '", "' . $DATA['cara44bienv2ambienbiodiver'] . '", "' . $DATA['cara44bienv2ambienecologia'] . '", "' . $DATA['cara44bienv2ambieneconomia'] . '", 
"' . $DATA['cara44bienv2ambienrecnatura'] . '", "' . $DATA['cara44bienv2ambienreciclaje'] . '", "' . $DATA['cara44bienv2ambienmascota'] . '", "' . $DATA['cara44bienv2ambiencartohum'] . '", "' . $DATA['cara44bienv2ambienespiritu'] . '", 
"' . $DATA['cara44bienv2ambiencarga'] . '", "' . $DATA['cara44bienv2ambienotroenfoq'] . '", "' . $DATA['cara44bienv2ambienotroenfoqdetalle'] . '", "' . $DATA['cara44fam_madrecabeza'] . '", "' . $DATA['cara44acadhatenidorecesos'] . '", 
' . $DATA['cara44acadrazonreceso'] . ', "' . $DATA['cara44acadrazonrecesodetalle'] . '", ' . $DATA['cara44campus_usocorreounad'] . ', ' . $DATA['cara44campus_usocorreounadno'] . ', "' . $DATA['cara44campus_usocorreounadnodetalle'] . '", 
' . $DATA['cara44campus_medioactivunad'] . ', "' . $DATA['cara44campus_medioactivunaddetalle'] . '';
			if ($APP->utf8 == 1) {
				$sSQL = 'INSERT INTO cara01encuesta (' . $sCampos2301 . ') VALUES (' . utf8_encode($sValores2301) . ');';
				$sdetalle = $sCampos2301 . '[' . utf8_encode($sValores2301) . ']';
				$sSQL1 = 'INSERT INTO cara44encuesta (' . $sCampos2344 . ') VALUES (' . utf8_encode($sValores2344) . ');';
				$sdetalle1 = $sCampos2344 . '[' . utf8_encode($sValores2344) . ']';
			} else {
				$sSQL = 'INSERT INTO cara01encuesta (' . $sCampos2301 . ') VALUES (' . $sValores2301 . ');';
				$sdetalle = $sCampos2301 . '[' . $sValores2301 . ']';
				$sSQL1 = 'INSERT INTO cara44encuesta (' . $sCampos2344 . ') VALUES (' . $sValores2344 . ');';
				$sdetalle1 = $sCampos2344 . '[' . $sValores2344 . ']';
			}
			$idAccion = 2;
			$bpasa = true;
		} else {
			$scampo[1] = 'cara01completa';
			$scampo[2] = 'cara01fichaper';
			$scampo[3] = 'cara01fichafam';
			$scampo[4] = 'cara01fichaaca';
			$scampo[5] = 'cara01fichalab';
			$scampo[6] = 'cara01fichabien';
			$scampo[7] = 'cara01fichapsico';
			$scampo[8] = 'cara01fichadigital';
			$scampo[9] = 'cara01fichalectura';
			$scampo[10] = 'cara01ficharazona';
			$scampo[11] = 'cara01fichaingles';
			$scampo[12] = 'cara01fechaencuesta';
			$scampo[13] = 'cara01sexo';
			$scampo[14] = 'cara01pais';
			$scampo[15] = 'cara01depto';
			$scampo[16] = 'cara01ciudad';
			$scampo[17] = 'cara01nomciudad';
			$scampo[18] = 'cara01direccion';
			$scampo[19] = 'cara01estrato';
			$scampo[20] = 'cara01zonares';
			$scampo[21] = 'cara01estcivil';
			$scampo[22] = 'cara01nomcontacto';
			$scampo[23] = 'cara01parentezcocontacto';
			$scampo[24] = 'cara01celcontacto';
			$scampo[25] = 'cara01correocontacto';
			$scampo[26] = 'cara01idcead';
			$scampo[27] = 'cara01matconvenio';
			$scampo[28] = 'cara01raizal';
			$scampo[29] = 'cara01palenquero';
			$scampo[30] = 'cara01afrocolombiano';
			$scampo[31] = 'cara01otracomunnegras';
			$scampo[32] = 'cara01rom';
			$scampo[33] = 'cara01indigenas';
			$scampo[34] = 'cara01victimadesplazado';
			$scampo[35] = 'cara01fechaconfirmadesp';
			$scampo[36] = 'cara01victimaacr';
			$scampo[37] = 'cara01fechaconfirmacr';
			$scampo[38] = 'cara01inpecfuncionario';
			$scampo[39] = 'cara01inpecrecluso';
			$scampo[40] = 'cara01inpectiempocondena';
			$scampo[41] = 'cara01centroreclusion';
			$scampo[42] = 'cara01discsensorial';
			$scampo[43] = 'cara01discfisica';
			$scampo[44] = 'cara01disccognitiva';
			$scampo[45] = 'cara01fechaconfirmadisc';
			$scampo[46] = 'cara01fam_tipovivienda';
			$scampo[47] = 'cara01fam_vivecon';
			$scampo[48] = 'cara01fam_numpersgrupofam';
			$scampo[49] = 'cara01fam_hijos';
			$scampo[50] = 'cara01fam_personasacargo';
			$scampo[51] = 'cara01fam_dependeecon';
			$scampo[52] = 'cara01fam_escolaridadpadre';
			$scampo[53] = 'cara01fam_escolaridadmadre';
			$scampo[54] = 'cara01fam_numhermanos';
			$scampo[55] = 'cara01fam_posicionherm';
			$scampo[56] = 'cara01fam_familiaunad';
			$scampo[57] = 'cara01acad_tipocolegio';
			$scampo[58] = 'cara01acad_modalidadbach';
			$scampo[59] = 'cara01acad_estudioprev';
			$scampo[60] = 'cara01acad_ultnivelest';
			$scampo[61] = 'cara01acad_obtubodiploma';
			$scampo[62] = 'cara01acad_hatomadovirtual';
			$scampo[63] = 'cara01acad_tiemposinest';
			$scampo[64] = 'cara01acad_razonestudio';
			$scampo[65] = 'cara01acad_primeraopc';
			$scampo[66] = 'cara01acad_programagusto';
			$scampo[67] = 'cara01acad_razonunad';
			$scampo[68] = 'cara01campus_compescrito';
			$scampo[69] = 'cara01campus_portatil';
			$scampo[70] = 'cara01campus_tableta';
			$scampo[71] = 'cara01campus_telefono';
			$scampo[72] = 'cara01campus_energia';
			$scampo[73] = 'cara01campus_internetreside';
			$scampo[74] = 'cara01campus_expvirtual';
			$scampo[75] = 'cara01campus_ofimatica';
			$scampo[76] = 'cara01campus_foros';
			$scampo[77] = 'cara01campus_conversiones';
			$scampo[78] = 'cara01campus_usocorreo';
			$scampo[79] = 'cara01campus_aprendtexto';
			$scampo[80] = 'cara01campus_aprendvideo';
			$scampo[81] = 'cara01campus_aprendmapas';
			$scampo[82] = 'cara01campus_aprendeanima';
			$scampo[83] = 'cara01campus_mediocomunica';
			$scampo[84] = 'cara01lab_situacion';
			$scampo[85] = 'cara01lab_sector';
			$scampo[86] = 'cara01lab_caracterjuri';
			$scampo[87] = 'cara01lab_cargo';
			$scampo[88] = 'cara01lab_antiguedad';
			$scampo[89] = 'cara01lab_tipocontrato';
			$scampo[90] = 'cara01lab_rangoingreso';
			$scampo[91] = 'cara01lab_tiempoacadem';
			$scampo[92] = 'cara01lab_tipoempresa';
			$scampo[93] = 'cara01lab_tiempoindepen';
			$scampo[94] = 'cara01lab_debebusctrab';
			$scampo[95] = 'cara01lab_origendinero';
			$scampo[96] = 'cara01bien_baloncesto';
			$scampo[97] = 'cara01bien_voleibol';
			$scampo[98] = 'cara01bien_futbolsala';
			$scampo[99] = 'cara01bien_artesmarc';
			$scampo[100] = 'cara01bien_tenisdemesa';
			$scampo[101] = 'cara01bien_ajedrez';
			$scampo[102] = 'cara01bien_juegosautoc';
			$scampo[103] = 'cara01bien_interesrepdeporte';
			$scampo[104] = 'cara01bien_deporteint';
			$scampo[105] = 'cara01bien_teatro';
			$scampo[106] = 'cara01bien_danza';
			$scampo[107] = 'cara01bien_musica';
			$scampo[108] = 'cara01bien_circo';
			$scampo[109] = 'cara01bien_artplast';
			$scampo[110] = 'cara01bien_cuenteria';
			$scampo[111] = 'cara01bien_interesreparte';
			$scampo[112] = 'cara01bien_arteint';
			$scampo[113] = 'cara01bien_interpreta';
			$scampo[114] = 'cara01bien_nivelinter';
			$scampo[115] = 'cara01bien_danza_mod';
			$scampo[116] = 'cara01bien_danza_clas';
			$scampo[117] = 'cara01bien_danza_cont';
			$scampo[118] = 'cara01bien_danza_folk';
			$scampo[119] = 'cara01bien_niveldanza';
			$scampo[120] = 'cara01bien_emprendedor';
			$scampo[121] = 'cara01bien_nombreemp';
			$scampo[122] = 'cara01bien_capacempren';
			$scampo[123] = 'cara01bien_tipocapacita';
			$scampo[124] = 'cara01bien_impvidasalud';
			$scampo[125] = 'cara01bien_estraautocuid';
			$scampo[126] = 'cara01bien_pv_personal';
			$scampo[127] = 'cara01bien_pv_familiar';
			$scampo[128] = 'cara01bien_pv_academ';
			$scampo[129] = 'cara01bien_pv_labora';
			$scampo[130] = 'cara01bien_pv_pareja';
			$scampo[131] = 'cara01bien_amb';
			$scampo[132] = 'cara01bien_amb_agu';
			$scampo[133] = 'cara01bien_amb_bom';
			$scampo[134] = 'cara01bien_amb_car';
			$scampo[135] = 'cara01bien_amb_info';
			$scampo[136] = 'cara01bien_amb_temas';
			$scampo[137] = 'cara01psico_costoemocion';
			$scampo[138] = 'cara01psico_reaccionimpre';
			$scampo[139] = 'cara01psico_estres';
			$scampo[140] = 'cara01psico_pocotiempo';
			$scampo[141] = 'cara01psico_actitudvida';
			$scampo[142] = 'cara01psico_duda';
			$scampo[143] = 'cara01psico_problemapers';
			$scampo[144] = 'cara01psico_satisfaccion';
			$scampo[145] = 'cara01psico_discusiones';
			$scampo[146] = 'cara01psico_atencion';
			$scampo[147] = 'cara01fechainicio';
			$scampo[148] = 'cara01telefono1';
			$scampo[149] = 'cara01telefono2';
			$scampo[150] = 'cara01correopersonal';
			$scampo[151] = 'cara01fichabiolog';
			$scampo[152] = 'cara01fichafisica';
			$scampo[153] = 'cara01fichaquimica';

			//Los niveles.
			$scampo[154] = 'cara01niveldigital';
			$scampo[155] = 'cara01nivellectura';
			$scampo[156] = 'cara01nivelrazona';
			$scampo[157] = 'cara01nivelingles';
			$scampo[158] = 'cara01nivelbiolog';
			$scampo[159] = 'cara01nivelfisica';
			$scampo[160] = 'cara01nivelquimica';
			$scampo[161] = 'cara01psico_puntaje';
			//Los faltantes de discapacidad
			$scampo[162] = 'cara01perayuda';
			$scampo[163] = 'cara01discsensorialotra';
			$scampo[164] = 'cara01discv2sensorial';
			$scampo[165] = 'cara02discv2intelectura';
			$scampo[166] = 'cara02discv2fisica';
			$scampo[167] = 'cara02discv2psico';
			$scampo[168] = 'cara02discv2sistemica';
			$scampo[169] = 'cara02discv2sistemicaotro';
			$scampo[170] = 'cara02discv2multiple';
			$scampo[171] = 'cara02discv2multipleotro';
			$scampo[172] = 'cara02talentoexcepcional';
			//Aqui esta alterado el orden...
			$scampo[173] = 'cara01discfisicaotra';
			$scampo[174] = 'cara01disccognitivaotra';
			$scampo[175] = 'cara01perotraayuda';
			$scampo[176] = 'cara01discv2tiene';
			$scampo[177] = 'cara01discv2trastaprende';
			$scampo[178] = 'cara01discv2trastornos';
			$scampo[179] = 'cara01discv2contalento';
			$scampo[180] = 'cara01discv2condicionmedica';
			$scampo[181] = 'cara01discv2condmeddet';
			$scampo[182] = 'cara01discv2pruebacoeficiente';
			//
			$sdato[1] = $DATA['cara01completa'];
			$sdato[2] = $DATA['cara01fichaper'];
			$sdato[3] = $DATA['cara01fichafam'];
			$sdato[4] = $DATA['cara01fichaaca'];
			$sdato[5] = $DATA['cara01fichalab'];
			$sdato[6] = $DATA['cara01fichabien'];
			$sdato[7] = $DATA['cara01fichapsico'];
			$sdato[8] = $DATA['cara01fichadigital'];
			$sdato[9] = $DATA['cara01fichalectura'];
			$sdato[10] = $DATA['cara01ficharazona'];
			$sdato[11] = $DATA['cara01fichaingles'];
			$sdato[12] = $DATA['cara01fechaencuesta'];
			$sdato[13] = $DATA['cara01sexo'];
			$sdato[14] = $DATA['cara01pais'];
			$sdato[15] = $DATA['cara01depto'];
			$sdato[16] = $DATA['cara01ciudad'];
			$sdato[17] = $DATA['cara01nomciudad'];
			$sdato[18] = $DATA['cara01direccion'];
			$sdato[19] = $DATA['cara01estrato'];
			$sdato[20] = $DATA['cara01zonares'];
			$sdato[21] = $DATA['cara01estcivil'];
			$sdato[22] = $DATA['cara01nomcontacto'];
			$sdato[23] = $DATA['cara01parentezcocontacto'];
			$sdato[24] = $DATA['cara01celcontacto'];
			$sdato[25] = $DATA['cara01correocontacto'];
			$sdato[26] = $DATA['cara01idcead'];
			$sdato[27] = $DATA['cara01matconvenio'];
			$sdato[28] = $DATA['cara01raizal'];
			$sdato[29] = $DATA['cara01palenquero'];
			$sdato[30] = $DATA['cara01afrocolombiano'];
			$sdato[31] = $DATA['cara01otracomunnegras'];
			$sdato[32] = $DATA['cara01rom'];
			$sdato[33] = $DATA['cara01indigenas'];
			$sdato[34] = $DATA['cara01victimadesplazado'];
			$sdato[35] = $DATA['cara01fechaconfirmadesp'];
			$sdato[36] = $DATA['cara01victimaacr'];
			$sdato[37] = $DATA['cara01fechaconfirmacr'];
			$sdato[38] = $DATA['cara01inpecfuncionario'];
			$sdato[39] = $DATA['cara01inpecrecluso'];
			$sdato[40] = $DATA['cara01inpectiempocondena'];
			$sdato[41] = $DATA['cara01centroreclusion'];
			$sdato[42] = $DATA['cara01discsensorial'];
			$sdato[43] = $DATA['cara01discfisica'];
			$sdato[44] = $DATA['cara01disccognitiva'];
			$sdato[45] = $DATA['cara01fechaconfirmadisc'];
			$sdato[46] = $DATA['cara01fam_tipovivienda'];
			$sdato[47] = $DATA['cara01fam_vivecon'];
			$sdato[48] = $DATA['cara01fam_numpersgrupofam'];
			$sdato[49] = $DATA['cara01fam_hijos'];
			$sdato[50] = $DATA['cara01fam_personasacargo'];
			$sdato[51] = $DATA['cara01fam_dependeecon'];
			$sdato[52] = $DATA['cara01fam_escolaridadpadre'];
			$sdato[53] = $DATA['cara01fam_escolaridadmadre'];
			$sdato[54] = $DATA['cara01fam_numhermanos'];
			$sdato[55] = $DATA['cara01fam_posicionherm'];
			$sdato[56] = $DATA['cara01fam_familiaunad'];
			$sdato[57] = $DATA['cara01acad_tipocolegio'];
			$sdato[58] = $DATA['cara01acad_modalidadbach'];
			$sdato[59] = $DATA['cara01acad_estudioprev'];
			$sdato[60] = $DATA['cara01acad_ultnivelest'];
			$sdato[61] = $DATA['cara01acad_obtubodiploma'];
			$sdato[62] = $DATA['cara01acad_hatomadovirtual'];
			$sdato[63] = $DATA['cara01acad_tiemposinest'];
			$sdato[64] = $DATA['cara01acad_razonestudio'];
			$sdato[65] = $DATA['cara01acad_primeraopc'];
			$sdato[66] = $DATA['cara01acad_programagusto'];
			$sdato[67] = $DATA['cara01acad_razonunad'];
			$sdato[68] = $DATA['cara01campus_compescrito'];
			$sdato[69] = $DATA['cara01campus_portatil'];
			$sdato[70] = $DATA['cara01campus_tableta'];
			$sdato[71] = $DATA['cara01campus_telefono'];
			$sdato[72] = $DATA['cara01campus_energia'];
			$sdato[73] = $DATA['cara01campus_internetreside'];
			$sdato[74] = $DATA['cara01campus_expvirtual'];
			$sdato[75] = $DATA['cara01campus_ofimatica'];
			$sdato[76] = $DATA['cara01campus_foros'];
			$sdato[77] = $DATA['cara01campus_conversiones'];
			$sdato[78] = $DATA['cara01campus_usocorreo'];
			$sdato[79] = $DATA['cara01campus_aprendtexto'];
			$sdato[80] = $DATA['cara01campus_aprendvideo'];
			$sdato[81] = $DATA['cara01campus_aprendmapas'];
			$sdato[82] = $DATA['cara01campus_aprendeanima'];
			$sdato[83] = $DATA['cara01campus_mediocomunica'];
			$sdato[84] = $DATA['cara01lab_situacion'];
			$sdato[85] = $DATA['cara01lab_sector'];
			$sdato[86] = $DATA['cara01lab_caracterjuri'];
			$sdato[87] = $DATA['cara01lab_cargo'];
			$sdato[88] = $DATA['cara01lab_antiguedad'];
			$sdato[89] = $DATA['cara01lab_tipocontrato'];
			$sdato[90] = $DATA['cara01lab_rangoingreso'];
			$sdato[91] = $DATA['cara01lab_tiempoacadem'];
			$sdato[92] = $DATA['cara01lab_tipoempresa'];
			$sdato[93] = $DATA['cara01lab_tiempoindepen'];
			$sdato[94] = $DATA['cara01lab_debebusctrab'];
			$sdato[95] = $DATA['cara01lab_origendinero'];
			$sdato[96] = $DATA['cara01bien_baloncesto'];
			$sdato[97] = $DATA['cara01bien_voleibol'];
			$sdato[98] = $DATA['cara01bien_futbolsala'];
			$sdato[99] = $DATA['cara01bien_artesmarc'];
			$sdato[100] = $DATA['cara01bien_tenisdemesa'];
			$sdato[101] = $DATA['cara01bien_ajedrez'];
			$sdato[102] = $DATA['cara01bien_juegosautoc'];
			$sdato[103] = $DATA['cara01bien_interesrepdeporte'];
			$sdato[104] = $DATA['cara01bien_deporteint'];
			$sdato[105] = $DATA['cara01bien_teatro'];
			$sdato[106] = $DATA['cara01bien_danza'];
			$sdato[107] = $DATA['cara01bien_musica'];
			$sdato[108] = $DATA['cara01bien_circo'];
			$sdato[109] = $DATA['cara01bien_artplast'];
			$sdato[110] = $DATA['cara01bien_cuenteria'];
			$sdato[111] = $DATA['cara01bien_interesreparte'];
			$sdato[112] = $DATA['cara01bien_arteint'];
			$sdato[113] = $DATA['cara01bien_interpreta'];
			$sdato[114] = $DATA['cara01bien_nivelinter'];
			$sdato[115] = $DATA['cara01bien_danza_mod'];
			$sdato[116] = $DATA['cara01bien_danza_clas'];
			$sdato[117] = $DATA['cara01bien_danza_cont'];
			$sdato[118] = $DATA['cara01bien_danza_folk'];
			$sdato[119] = $DATA['cara01bien_niveldanza'];
			$sdato[120] = $DATA['cara01bien_emprendedor'];
			$sdato[121] = $DATA['cara01bien_nombreemp'];
			$sdato[122] = $DATA['cara01bien_capacempren'];
			$sdato[123] = $DATA['cara01bien_tipocapacita'];
			$sdato[124] = $DATA['cara01bien_impvidasalud'];
			$sdato[125] = $DATA['cara01bien_estraautocuid'];
			$sdato[126] = $DATA['cara01bien_pv_personal'];
			$sdato[127] = $DATA['cara01bien_pv_familiar'];
			$sdato[128] = $DATA['cara01bien_pv_academ'];
			$sdato[129] = $DATA['cara01bien_pv_labora'];
			$sdato[130] = $DATA['cara01bien_pv_pareja'];
			$sdato[131] = $DATA['cara01bien_amb'];
			$sdato[132] = $DATA['cara01bien_amb_agu'];
			$sdato[133] = $DATA['cara01bien_amb_bom'];
			$sdato[134] = $DATA['cara01bien_amb_car'];
			$sdato[135] = $DATA['cara01bien_amb_info'];
			$sdato[136] = $DATA['cara01bien_amb_temas'];
			$sdato[137] = $DATA['cara01psico_costoemocion'];
			$sdato[138] = $DATA['cara01psico_reaccionimpre'];
			$sdato[139] = $DATA['cara01psico_estres'];
			$sdato[140] = $DATA['cara01psico_pocotiempo'];
			$sdato[141] = $DATA['cara01psico_actitudvida'];
			$sdato[142] = $DATA['cara01psico_duda'];
			$sdato[143] = $DATA['cara01psico_problemapers'];
			$sdato[144] = $DATA['cara01psico_satisfaccion'];
			$sdato[145] = $DATA['cara01psico_discusiones'];
			$sdato[146] = $DATA['cara01psico_atencion'];
			$sdato[147] = $DATA['cara01fechainicio'];
			$sdato[148] = $DATA['cara01telefono1'];
			$sdato[149] = $DATA['cara01telefono2'];
			$sdato[150] = $DATA['cara01correopersonal'];
			$sdato[151] = $DATA['cara01fichabiolog'];
			$sdato[152] = $DATA['cara01fichafisica'];
			$sdato[153] = $DATA['cara01fichaquimica'];
			//Los niveles.
			$sdato[154] = $DATA['cara01niveldigital'];
			$sdato[155] = $DATA['cara01nivellectura'];
			$sdato[156] = $DATA['cara01nivelrazona'];
			$sdato[157] = $DATA['cara01nivelingles'];
			$sdato[158] = $DATA['cara01nivelbiolog'];
			$sdato[159] = $DATA['cara01nivelfisica'];
			$sdato[160] = $DATA['cara01nivelquimica'];
			//
			$sdato[161] = $DATA['cara01psico_puntaje'];
			//Los faltantes de discapacidad
			$sdato[162] = $DATA['cara01perayuda'];
			$sdato[163] = $DATA['cara01discsensorialotra'];
			$sdato[164] = $DATA['cara01discv2sensorial'];
			$sdato[165] = $DATA['cara02discv2intelectura'];
			$sdato[166] = $DATA['cara02discv2fisica'];
			$sdato[167] = $DATA['cara02discv2psico'];
			$sdato[168] = $DATA['cara02discv2sistemica'];
			$sdato[169] = $DATA['cara02discv2sistemicaotro'];
			$sdato[170] = $DATA['cara02discv2multiple'];
			$sdato[171] = $DATA['cara02discv2multipleotro'];
			$sdato[172] = $DATA['cara02talentoexcepcional'];
			//Aqui esta alterado el orden...
			$sdato[173] = $DATA['cara01discfisicaotra'];
			$sdato[174] = $DATA['cara01disccognitivaotra'];
			$sdato[175] = $DATA['cara01perotraayuda'];
			$sdato[176] = $DATA['cara01discv2tiene'];
			$sdato[177] = $DATA['cara01discv2trastaprende'];
			$sdato[178] = $DATA['cara01discv2trastornos'];
			$sdato[179] = $DATA['cara01discv2contalento'];
			$sdato[180] = $DATA['cara01discv2condicionmedica'];
			$sdato[181] = $DATA['cara01discv2condmeddet'];
			$sdato[182] = $DATA['cara01discv2pruebacoeficiente'];
			$numcmod = 182;
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
				$idAccion = 3;
			}

			// Inicio valida si existen nuevos campos caracterización
			$sSQL44 = 'SELECT 1 FROM cara44encuesta WHERE cara44id=' . $DATA['cara01id'] . '';
			$tabla = $objDB->ejecutasql($sSQL44);
			if ($objDB->nf($tabla) == 0) {
				$cara44id = tabla_consecutivo('cara44encuesta', 'cara44id', '', $objDB);
				$cara44sexoversion = 0;
				$cara44bienversion = 1;
				if ($DATA['cara01idperaca'] > 1142){
					$cara44sexoversion = 1;
					$cara44bienversion = 2;
				}
				$sCampos2344 = 'cara44id, cara44sexoversion, cara44bienversion';
				$sValores2344 = '' . $DATA['cara01id'] . ', ' . $cara44sexoversion . ', ' . $cara44bienversion . '';
				if ($APP->utf8 == 1) {
					$sSQL = 'INSERT INTO cara44encuesta (' . $sCampos2344 . ') VALUES (' . utf8_encode($sValores2344) . ');';
					$sdetalle = $sCampos2344 . '[' . utf8_encode($sValores2344) . ']';
				} else {
					$sSQL = 'INSERT INTO cara44encuesta (' . $sCampos2344 . ') VALUES (' . $sValores2344 . ');';
					$sdetalle = $sCampos2344 . '[' . $sValores2344 . ']';
				}
				$tabla = $objDB->ejecutasql($sSQL44);
			}
			// Fin valida si existen nuevos campos caracterización
			//Nuevas preguntas caracterización nuevos y antiguos
			$scampo[1] = 'cara44campesinado';
			$scampo[2] = 'cara44sexoversion';
			$scampo[3] = 'cara44sexov1identidadgen';
			$scampo[4] = 'cara44sexov1orientasexo';
			$scampo[5] = 'cara44bienversion';
			$scampo[6] = 'cara44bienv2altoren';
			$scampo[7] = 'cara44bienv2atletismo';
			$scampo[8] = 'cara44bienv2baloncesto';
			$scampo[9] = 'cara44bienv2futbol';
			$scampo[10] = 'cara44bienv2gimnasia';
			$scampo[11] = 'cara44bienv2natacion';
			$scampo[12] = 'cara44bienv2voleibol';
			$scampo[13] = 'cara44bienv2tenis';
			$scampo[14] = 'cara44bienv2paralimpico';
			$scampo[15] = 'cara44bienv2otrodeporte';
			$scampo[16] = 'cara44bienv2otrodeportedetalle';
			$scampo[17] = 'cara44bienv2activdanza';
			$scampo[18] = 'cara44bienv2activmusica';
			$scampo[19] = 'cara44bienv2activteatro';
			$scampo[20] = 'cara44bienv2activartes';
			$scampo[21] = 'cara44bienv2activliteratura';
			$scampo[22] = 'cara44bienv2activculturalotra';
			$scampo[23] = 'cara44bienv2activculturalotradetalle';
			$scampo[24] = 'cara44bienv2evenfestfolc';
			$scampo[25] = 'cara44bienv2evenexpoarte';
			$scampo[26] = 'cara44bienv2evenhistarte';
			$scampo[27] = 'cara44bienv2evengalfoto';
			$scampo[28] = 'cara44bienv2evenliteratura';
			$scampo[29] = 'cara44bienv2eventeatro';
			$scampo[30] = 'cara44bienv2evencine';
			$scampo[31] = 'cara44bienv2evenculturalotro';
			$scampo[32] = 'cara44bienv2evenculturalotrodetalle';
			$scampo[33] = 'cara44bienv2emprendimiento';
			$scampo[34] = 'cara44bienv2empresa';
			$scampo[35] = 'cara44bienv2emprenrecursos';
			$scampo[36] = 'cara44bienv2emprenconocim';
			$scampo[37] = 'cara44bienv2emprenplan';
			$scampo[38] = 'cara44bienv2emprenejecutar';
			$scampo[39] = 'cara44bienv2emprenfortconocim';
			$scampo[40] = 'cara44bienv2emprenidentproblema';
			$scampo[41] = 'cara44bienv2emprenotro';
			$scampo[42] = 'cara44bienv2emprenotrodetalle';
			$scampo[43] = 'cara44bienv2emprenmarketing';
			$scampo[44] = 'cara44bienv2emprenplannegocios';
			$scampo[45] = 'cara44bienv2emprenideas';
			$scampo[46] = 'cara44bienv2emprencreacion';
			$scampo[47] = 'cara44bienv2saludfacteconom';
			$scampo[48] = 'cara44bienv2saludpreocupacion';
			$scampo[49] = 'cara44bienv2saludconsumosust';
			$scampo[50] = 'cara44bienv2saludinsomnio';
			$scampo[51] = 'cara44bienv2saludclimalab';
			$scampo[52] = 'cara44bienv2saludalimenta';
			$scampo[53] = 'cara44bienv2saludemocion';
			$scampo[54] = 'cara44bienv2saludestado';
			$scampo[55] = 'cara44bienv2saludmedita';
			$scampo[56] = 'cara44bienv2crecimedusexual';
			$scampo[57] = 'cara44bienv2crecimcultciudad';
			$scampo[58] = 'cara44bienv2crecimrelpareja';
			$scampo[59] = 'cara44bienv2crecimrelinterp';
			$scampo[60] = 'cara44bienv2crecimdinamicafam';
			$scampo[61] = 'cara44bienv2crecimautoestima';
			$scampo[62] = 'cara44bienv2creciminclusion';
			$scampo[63] = 'cara44bienv2creciminteliemoc';
			$scampo[64] = 'cara44bienv2crecimcultural';
			$scampo[65] = 'cara44bienv2crecimartistico';
			$scampo[66] = 'cara44bienv2crecimdeporte';
			$scampo[67] = 'cara44bienv2crecimambiente';
			$scampo[68] = 'cara44bienv2crecimhabsocio';
			$scampo[69] = 'cara44bienv2ambienbasura';
			$scampo[70] = 'cara44bienv2ambienreutiliza';
			$scampo[71] = 'cara44bienv2ambienluces';
			$scampo[72] = 'cara44bienv2ambienfrutaverd';
			$scampo[73] = 'cara44bienv2ambienenchufa';
			$scampo[74] = 'cara44bienv2ambiengrifo';
			$scampo[75] = 'cara44bienv2ambienbicicleta';
			$scampo[76] = 'cara44bienv2ambientranspub';
			$scampo[77] = 'cara44bienv2ambienducha';
			$scampo[78] = 'cara44bienv2ambiencaminata';
			$scampo[79] = 'cara44bienv2ambiensiembra';
			$scampo[80] = 'cara44bienv2ambienconferencia';
			$scampo[81] = 'cara44bienv2ambienrecicla';
			$scampo[82] = 'cara44bienv2ambienotraactiv';
			$scampo[83] = 'cara44bienv2ambienotraactivdetalle';
			$scampo[84] = 'cara44bienv2ambienreforest';
			$scampo[85] = 'cara44bienv2ambienmovilidad';
			$scampo[86] = 'cara44bienv2ambienclimatico';
			$scampo[87] = 'cara44bienv2ambienecofemin';
			$scampo[88] = 'cara44bienv2ambienbiodiver';
			$scampo[89] = 'cara44bienv2ambienecologia';
			$scampo[90] = 'cara44bienv2ambieneconomia';
			$scampo[91] = 'cara44bienv2ambienrecnatura';
			$scampo[92] = 'cara44bienv2ambienreciclaje';
			$scampo[93] = 'cara44bienv2ambienmascota';
			$scampo[94] = 'cara44bienv2ambiencartohum';
			$scampo[95] = 'cara44bienv2ambienespiritu';
			$scampo[96] = 'cara44bienv2ambiencarga';
			$scampo[97] = 'cara44bienv2ambienotroenfoq';
			$scampo[98] = 'cara44bienv2ambienotroenfoqdetalle';
			$scampo[99] = 'cara44fam_madrecabeza';
			$scampo[100] = 'cara44acadhatenidorecesos';
			$scampo[101] = 'cara44acadrazonreceso';
			$scampo[102] = 'cara44acadrazonrecesodetalle';
			$scampo[103] = 'cara44campus_usocorreounad';
			$scampo[104] = 'cara44campus_usocorreounadno';
			$scampo[105] = 'cara44campus_usocorreounadnodetalle';
			$scampo[106] = 'cara44campus_medioactivunad';
			$scampo[107] = 'cara44campus_medioactivunaddetalle';
			//
			$sdato[1] = $DATA['cara44campesinado'];
			$sdato[2] = $DATA['cara44sexoversion'];
			$sdato[3] = $DATA['cara44sexov1identidadgen'];
			$sdato[4] = $DATA['cara44sexov1orientasexo'];
			$sdato[5] = $DATA['cara44bienversion'];
			$sdato[6] = $DATA['cara44bienv2altoren'];
			$sdato[7] = $DATA['cara44bienv2atletismo'];
			$sdato[8] = $DATA['cara44bienv2baloncesto'];
			$sdato[9] = $DATA['cara44bienv2futbol'];
			$sdato[10] = $DATA['cara44bienv2gimnasia'];
			$sdato[11] = $DATA['cara44bienv2natacion'];
			$sdato[12] = $DATA['cara44bienv2voleibol'];
			$sdato[13] = $DATA['cara44bienv2tenis'];
			$sdato[14] = $DATA['cara44bienv2paralimpico'];
			$sdato[15] = $DATA['cara44bienv2otrodeporte'];
			$sdato[16] = $DATA['cara44bienv2otrodeportedetalle'];
			$sdato[17] = $DATA['cara44bienv2activdanza'];
			$sdato[18] = $DATA['cara44bienv2activmusica'];
			$sdato[19] = $DATA['cara44bienv2activteatro'];
			$sdato[20] = $DATA['cara44bienv2activartes'];
			$sdato[21] = $DATA['cara44bienv2activliteratura'];
			$sdato[22] = $DATA['cara44bienv2activculturalotra'];
			$sdato[23] = $DATA['cara44bienv2activculturalotradetalle'];
			$sdato[24] = $DATA['cara44bienv2evenfestfolc'];
			$sdato[25] = $DATA['cara44bienv2evenexpoarte'];
			$sdato[26] = $DATA['cara44bienv2evenhistarte'];
			$sdato[27] = $DATA['cara44bienv2evengalfoto'];
			$sdato[28] = $DATA['cara44bienv2evenliteratura'];
			$sdato[29] = $DATA['cara44bienv2eventeatro'];
			$sdato[30] = $DATA['cara44bienv2evencine'];
			$sdato[31] = $DATA['cara44bienv2evenculturalotro'];
			$sdato[32] = $DATA['cara44bienv2evenculturalotrodetalle'];
			$sdato[33] = $DATA['cara44bienv2emprendimiento'];
			$sdato[34] = $DATA['cara44bienv2empresa'];
			$sdato[35] = $DATA['cara44bienv2emprenrecursos'];
			$sdato[36] = $DATA['cara44bienv2emprenconocim'];
			$sdato[37] = $DATA['cara44bienv2emprenplan'];
			$sdato[38] = $DATA['cara44bienv2emprenejecutar'];
			$sdato[39] = $DATA['cara44bienv2emprenfortconocim'];
			$sdato[40] = $DATA['cara44bienv2emprenidentproblema'];
			$sdato[41] = $DATA['cara44bienv2emprenotro'];
			$sdato[42] = $DATA['cara44bienv2emprenotrodetalle'];
			$sdato[43] = $DATA['cara44bienv2emprenmarketing'];
			$sdato[44] = $DATA['cara44bienv2emprenplannegocios'];
			$sdato[45] = $DATA['cara44bienv2emprenideas'];
			$sdato[46] = $DATA['cara44bienv2emprencreacion'];
			$sdato[47] = $DATA['cara44bienv2saludfacteconom'];
			$sdato[48] = $DATA['cara44bienv2saludpreocupacion'];
			$sdato[49] = $DATA['cara44bienv2saludconsumosust'];
			$sdato[50] = $DATA['cara44bienv2saludinsomnio'];
			$sdato[51] = $DATA['cara44bienv2saludclimalab'];
			$sdato[52] = $DATA['cara44bienv2saludalimenta'];
			$sdato[53] = $DATA['cara44bienv2saludemocion'];
			$sdato[54] = $DATA['cara44bienv2saludestado'];
			$sdato[55] = $DATA['cara44bienv2saludmedita'];
			$sdato[56] = $DATA['cara44bienv2crecimedusexual'];
			$sdato[57] = $DATA['cara44bienv2crecimcultciudad'];
			$sdato[58] = $DATA['cara44bienv2crecimrelpareja'];
			$sdato[59] = $DATA['cara44bienv2crecimrelinterp'];
			$sdato[60] = $DATA['cara44bienv2crecimdinamicafam'];
			$sdato[61] = $DATA['cara44bienv2crecimautoestima'];
			$sdato[62] = $DATA['cara44bienv2creciminclusion'];
			$sdato[63] = $DATA['cara44bienv2creciminteliemoc'];
			$sdato[64] = $DATA['cara44bienv2crecimcultural'];
			$sdato[65] = $DATA['cara44bienv2crecimartistico'];
			$sdato[66] = $DATA['cara44bienv2crecimdeporte'];
			$sdato[67] = $DATA['cara44bienv2crecimambiente'];
			$sdato[68] = $DATA['cara44bienv2crecimhabsocio'];
			$sdato[69] = $DATA['cara44bienv2ambienbasura'];
			$sdato[70] = $DATA['cara44bienv2ambienreutiliza'];
			$sdato[71] = $DATA['cara44bienv2ambienluces'];
			$sdato[72] = $DATA['cara44bienv2ambienfrutaverd'];
			$sdato[73] = $DATA['cara44bienv2ambienenchufa'];
			$sdato[74] = $DATA['cara44bienv2ambiengrifo'];
			$sdato[75] = $DATA['cara44bienv2ambienbicicleta'];
			$sdato[76] = $DATA['cara44bienv2ambientranspub'];
			$sdato[77] = $DATA['cara44bienv2ambienducha'];
			$sdato[78] = $DATA['cara44bienv2ambiencaminata'];
			$sdato[79] = $DATA['cara44bienv2ambiensiembra'];
			$sdato[80] = $DATA['cara44bienv2ambienconferencia'];
			$sdato[81] = $DATA['cara44bienv2ambienrecicla'];
			$sdato[82] = $DATA['cara44bienv2ambienotraactiv'];
			$sdato[83] = $DATA['cara44bienv2ambienotraactivdetalle'];
			$sdato[84] = $DATA['cara44bienv2ambienreforest'];
			$sdato[85] = $DATA['cara44bienv2ambienmovilidad'];
			$sdato[86] = $DATA['cara44bienv2ambienclimatico'];
			$sdato[87] = $DATA['cara44bienv2ambienecofemin'];
			$sdato[88] = $DATA['cara44bienv2ambienbiodiver'];
			$sdato[89] = $DATA['cara44bienv2ambienecologia'];
			$sdato[90] = $DATA['cara44bienv2ambieneconomia'];
			$sdato[91] = $DATA['cara44bienv2ambienrecnatura'];
			$sdato[92] = $DATA['cara44bienv2ambienreciclaje'];
			$sdato[93] = $DATA['cara44bienv2ambienmascota'];
			$sdato[94] = $DATA['cara44bienv2ambiencartohum'];
			$sdato[95] = $DATA['cara44bienv2ambienespiritu'];
			$sdato[96] = $DATA['cara44bienv2ambiencarga'];
			$sdato[97] = $DATA['cara44bienv2ambienotroenfoq'];
			$sdato[98] = $DATA['cara44bienv2ambienotroenfoqdetalle'];
			$sdato[99] = $DATA['cara44fam_madrecabeza'];
			$sdato[100] = $DATA['cara44acadhatenidorecesos'];
			$sdato[101] = $DATA['cara44acadrazonreceso'];
			$sdato[102] = $DATA['cara44acadrazonrecesodetalle'];
			$sdato[103] = $DATA['cara44campus_usocorreounad'];
			$sdato[104] = $DATA['cara44campus_usocorreounadno'];
			$sdato[105] = $DATA['cara44campus_usocorreounadnodetalle'];
			$sdato[106] = $DATA['cara44campus_medioactivunad'];
			$sdato[107] = $DATA['cara44campus_medioactivunaddetalle'];
			$numcmod44 = 107;
			$sWhere = 'cara44id=' . $DATA['cara01id'] . '';
			$sSQL44 = 'SELECT * FROM cara44encuesta WHERE ' . $sWhere;
			$sdatos = '';
			$bPrimera = true;
			$bpasa44 = false;
			$result = $objDB->ejecutasql($sSQL44);
			if ($objDB->nf($result) > 0) {
				$filabase = $objDB->sf($result);
				if ($bDebug && $bPrimera) {
					for ($k = 1; $k <= $numcmod44; $k++) {
						if (isset($filabase[$scampo[$k]]) == 0) {
							$sDebug = $sDebug . fecha_microtiempo() . ' FALLA CODIGO: Falta el campo ' . $k . ' ' . $scampo[$k] . '<br>';
						}
					}
					$bPrimera = false;
				}
				$bsepara = false;
				for ($k = 1; $k <= $numcmod44; $k++) {
					if ($filabase[$scampo[$k]] != $sdato[$k]) {
						if ($sdatos != '') {
							$sdatos = $sdatos . ', ';
						}
						$sdatos = $sdatos . $scampo[$k] . '="' . $sdato[$k] . '"';
						$bpasa44 = true;
					}
				}
			}
			if ($bpasa44) {
				if ($APP->utf8 == 1) {
					$sdetalle = utf8_encode($sdatos) . '[' . $sWhere . ']';
					$sSQL44 = 'UPDATE cara44encuesta SET ' . utf8_encode($sdatos) . ' WHERE ' . $sWhere . ';';
				} else {
					$sdetalle = $sdatos . '[' . $sWhere . ']';
					$sSQL44 = 'UPDATE cara44encuesta SET ' . $sdatos . ' WHERE ' . $sWhere . ';';
				}
				$idAccion = 3;
			}
		}
		if ($bpasa) {
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . ' Guardar 2301 ' . $sSQL . '<br>';
			}
			$result = $objDB->ejecutasql($sSQL);
			if ($result == false) {
				$sError = $ERR['falla_guardar'] . ' [2301] ..<!-- ' . $sSQL . ' -->';
				if ($idAccion == 2) {
					$DATA['cara01id'] = '';
					$bQuitarCodigo = true;
					$DATA['paso'] = 0;
				} else {
					$DATA['paso'] = 2;
				}
				$bCerrando = false;
			} else {
				if ($bAudita[$idAccion]) {
					seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], $idAccion, $DATA['cara01id'], $sdetalle, $objDB);
				}
				$DATA['paso'] = 2;
			}
		} else {
			$DATA['paso'] = 2;
		}
		if ($bpasa44) {
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . ' Guardar 2344 ' . $sSQL44 . '<br>';
			}
			$result = $objDB->ejecutasql($sSQL44);
			if ($result == false) {
				$sError = $ERR['falla_guardar'] . ' [2344] ..<!-- ' . $sSQL44 . ' -->';
				if ($idAccion == 2) {
					$DATA['cara01id'] = '';
					$bQuitarCodigo = true;
					$DATA['paso'] = 0;
				} else {
					$DATA['paso'] = 2;
				}
				$bCerrando = false;
			} else {
				if ($bAudita[$idAccion]) {
					seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], $idAccion, $DATA['cara01id'], $sdetalle, $objDB);
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
		list($sErrorCerrando, $sDebugCerrar) = f2301_Cerrar($DATA['cara01id'], $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugCerrar;
	}
	//if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' InfoDepura<br>';}
	return array($DATA, $sError, $iTipoError, $bCerrando, $sErrorCerrando, $sDebug);
}
function f2301_db_Eliminar($cara01id, $objDB, $bDebug = false)
{
	$iCodModulo = 2301;
	$bAudita[4] = true;
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
	$cara01id = numeros_validar($cara01id);
	// Traer los datos para hacer las validaciones.
	if ($sError == '') {
		$sSQL = 'SELECT * FROM cara01encuesta WHERE cara01id=' . $cara01id . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$filabase = $objDB->sf($tabla);
		} else {
			$sError = 'No se encuentra el registro solicitado {Ref: ' . $cara01id . '}';
		}
	}
	if ($sError == '') {
		if (!seg_revisa_permiso($iCodModulo, 4, $objDB)) {
			$sError = $ERR['4'];
		}
	}
	if ($sError == '') {
		$sSQL = 'SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=2301';
		$tablaor = $objDB->ejecutasql($sSQL);
		while ($filaor = $objDB->sf($tablaor)) {
			$sSQL = 'SELECT ' . $filaor['campoid'] . ' FROM ' . $filaor['tabla'] . ' WHERE ' . $filaor['camporev'] . '=' . $_REQUEST['cara01id'] . ' LIMIT 0, 1';
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
		//$sSQL='DELETE FROM cara10pregprueba WHERE cara10idcara='.$filabase['cara01id'].'';
		//$tabla=$objDB->ejecutasql($sSQL);
		$sWhere = 'cara01id=' . $cara01id . '';
		//$sWhere='cara01idtercero="'.$filabase['cara01idtercero'].'" AND cara01idperaca='.$filabase['cara01idperaca'].'';
		$sSQL = 'DELETE FROM cara01encuesta WHERE ' . $sWhere . ';';
		$result = $objDB->ejecutasql($sSQL);
		if ($result == false) {
			$sError = $ERR['falla_eliminar'] . ' .. <!-- ' . $sSQL . ' -->';
		} else {
			if ($bAudita[4]) {
				seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $cara01id, $sWhere, $objDB);
			}
		}
	}
	return array($sError, $iTipoError, $sDebug);
}
function f2301_TituloBusqueda()
{
	return 'Busqueda de Encuesta';
}
function f2301_ParametrosBusqueda()
{
	$sParams = '<label class="Label90">Nombre</label><label><input id="b2301nombre" name="b2301nombre" type="text" value="" onchange="paginarbusqueda()" /></label>';
	return $sParams;
}
function f2301_JavaScriptBusqueda($iModuloBusca)
{
	$sRes = 'var sCampo=window.document.frmedita.scampobusca.value;
var params=new Array();
params[100]=sCampo;
params[101]=window.document.frmedita.paginabusqueda.value;
params[102]=window.document.frmedita.lppfbusqueda.value;
params[103]=window.document.frmedita.b2301nombre.value;
xajax_f' . $iModuloBusca . '_HtmlBusqueda(params);';
	return $sRes;
}
function f2301_TablaDetalleBusquedas($aParametros, $objDB)
{
	$res = '';
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
	//$aParametros[103]=numeros_validar($aParametros[103]);
	$idTercero = $aParametros[100];
	$pagina = $aParametros[101];
	$lineastabla = $aParametros[102];
	$babierta = true;
	//$sSQL='SELECT Campo FROM Tabla WHERE Id='.$sValorId;
	//$tabla=$objDB->ejecutasql($sSQL);
	//if ($objDB->nf($tabla)>0){
	//$fila=$objDB->sf($tabla);
	//if ($fila['Campo']!='S'){$babierta=true;}
	//}
	$sLeyenda = '';
	if (false) {
		$sLeyenda = '<div class="salto1px"></div>
<div class="GrupoCamposAyuda">
<b>Importante:</b> Mensaje al usuario
<div class="salto1px"></div>
</div>';
	}
	$sSQLadd = '';
	$sSQLadd1 = '';
	//if ($aParametros[103]!=''){$sSQLadd1=$sSQLadd1.'TB.campo2 LIKE "%'.$aParametros[103].'%" AND ';}
	//if ($aParametros[103]!=''){$sSQLadd=$sSQLadd.' AND TB.campo2 LIKE "%'.$aParametros[103].'%"';}
	/*
	if ($aParametros[104]!=''){
		$sBase=trim(strtoupper($aParametros[104]));
		$aNoms=explode(' ', $sBase);
		for ($k=1;$k<=count($aNoms);$k++){
			$sCadena=$aNoms[$k-1];
			if ($sCadena!=''){
				$sSQLadd=$sSQLadd.' AND T6.sys11razonsocial LIKE "%'.$sCadena.'%"';
				//$sSQLadd1=$sSQLadd1.'T1.sys11razonsocial LIKE "%'.$sCadena.'%" AND ';
				}
			}
		}
	*/
	$sTitulos = 'Peraca, Tercero, Id, Completa, Fichaper, Fichafam, Fichaaca, Fichalab, Fichabien, Fichapsico, Fichadigital, Fichalectura, Ficharazona, Fichaingles, Fechaencuesta, Agnos, Sexo, Pais, Depto, Ciudad, Nomciudad, Direccion, Estrato, Zonares, Estcivil, Nomcontacto, Parentezcocontacto, Celcontacto, Correocontacto, Zona, Cead, Matconvenio, Raizal, Palenquero, Afrocolombiano, Otracomunnegras, Rom, Indigenas, Victimadesplazado, Confirmadesp, Fechaconfirmadesp, Victimaacr, Confirmacr, Fechaconfirmacr, Inpecfuncionario, Inpecrecluso, Inpectiempocondena, Centroreclusion, Discsensorial, Discfisica, Disccognitiva, Confirmadisc, Fechaconfirmadisc, Fam_tipovivienda, Fam_vivecon, Fam_numpersgrupofam, Fam_hijos, Fam_personasacargo, Fam_dependeecon, Fam_escolaridadpadre, Fam_escolaridadmadre, Fam_numhermanos, Fam_posicionherm, Fam_familiaunad, Acad_tipocolegio, Acad_modalidadbach, Acad_estudioprev, Acad_ultnivelest, Acad_obtubodiploma, Acad_hatomadovirtual, Acad_tiemposinest, Acad_razonestudio, Acad_primeraopc, Acad_programagusto, Acad_razonunad, Campus_compescrito, Campus_portatil, Campus_tableta, Campus_telefono, Campus_energia, Campus_internetreside, Campus_expvirtual, Campus_ofimatica, Campus_foros, Campus_conversiones, Campus_usocorreo, Campus_aprendtexto, Campus_aprendvideo, Campus_aprendmapas, Campus_aprendeanima, Campus_mediocomunica, Lab_situacion, Lab_sector, Lab_caracterjuri, Lab_cargo, Lab_antiguedad, Lab_tipocontrato, Lab_rangoingreso, Lab_tiempoacadem, Lab_tipoempresa, Lab_tiempoindepen, Lab_debebusctrab, Lab_origendinero, Bien_baloncesto, Bien_voleibol, Bien_futbolsala, Bien_artesmarc, Bien_tenisdemesa, Bien_ajedrez, Bien_juegosautoc, Bien_interesrepdeporte, Bien_deporteint, Bien_teatro, Bien_danza, Bien_musica, Bien_circo, Bien_artplast, Bien_cuenteria, Bien_interesreparte, Bien_arteint, Bien_interpreta, Bien_nivelinter, Bien_danza_mod, Bien_danza_clas, Bien_danza_cont, Bien_danza_folk, Bien_niveldanza, Bien_emprendedor, Bien_nombreemp, Bien_capacempren, Bien_tipocapacita, Bien_impvidasalud, Bien_estraautocuid, Bien_pv_personal, Bien_pv_familiar, Bien_pv_academ, Bien_pv_labora, Bien_pv_pareja, Bien_amb, Bien_amb_agu, Bien_amb_bom, Bien_amb_car, Bien_amb_info, Bien_amb_temas, Psico_costoemocion, Psico_reaccionimpre, Psico_estres, Psico_pocotiempo, Psico_actitudvida, Psico_duda, Psico_problemapers, Psico_satisfaccion, Psico_discusiones, Psico_atencion, Niveldigital, Nivellectura, Nivelrazona, Nivelingles';
	$sSQL = 'SELECT T1.exte02nombre, T2.unad11razonsocial AS C2_nombre, TB.cara01id, TB.cara01completa, TB.cara01fichaper, TB.cara01fichafam, TB.cara01fichaaca, TB.cara01fichalab, TB.cara01fichabien, TB.cara01fichapsico, TB.cara01fichadigital, TB.cara01fichalectura, TB.cara01ficharazona, TB.cara01fichaingles, TB.cara01fechaencuesta, TB.cara01agnos, TB.cara01sexo, T18.unad18nombre, T19.unad19nombre, T20.unad20nombre, TB.cara01nomciudad, TB.cara01direccion, TB.cara01estrato, TB.cara01zonares, T25.unad21nombre, TB.cara01nomcontacto, TB.cara01parentezcocontacto, TB.cara01celcontacto, TB.cara01correocontacto, T30.unad23nombre, T31.unad24nombre, TB.cara01matconvenio, TB.cara01raizal, TB.cara01palenquero, TB.cara01afrocolombiano, TB.cara01otracomunnegras, TB.cara01rom, T38.cara02nombre, TB.cara01victimadesplazado, T40.unad11razonsocial AS C40_nombre, TB.cara01fechaconfirmadesp, TB.cara01victimaacr, T43.unad11razonsocial AS C43_nombre, TB.cara01fechaconfirmacr, TB.cara01inpecfuncionario, TB.cara01inpecrecluso, TB.cara01inpectiempocondena, TB.cara01centroreclusion, TB.cara01discsensorial, TB.cara01discfisica, TB.cara01disccognitiva, T52.unad11razonsocial AS C52_nombre, TB.cara01fechaconfirmadisc, TB.cara01fam_tipovivienda, TB.cara01fam_vivecon, TB.cara01fam_numpersgrupofam, TB.cara01fam_hijos, TB.cara01fam_personasacargo, TB.cara01fam_dependeecon, TB.cara01fam_escolaridadpadre, TB.cara01fam_escolaridadmadre, TB.cara01fam_numhermanos, TB.cara01fam_posicionherm, TB.cara01fam_familiaunad, TB.cara01acad_tipocolegio, TB.cara01acad_modalidadbach, TB.cara01acad_estudioprev, TB.cara01acad_ultnivelest, TB.cara01acad_obtubodiploma, TB.cara01acad_hatomadovirtual, TB.cara01acad_tiemposinest, T72.cara04nombre, TB.cara01acad_primeraopc, TB.cara01acad_programagusto, T75.cara05nombre, TB.cara01campus_compescrito, TB.cara01campus_portatil, TB.cara01campus_tableta, TB.cara01campus_telefono, TB.cara01campus_energia, TB.cara01campus_internetreside, TB.cara01campus_expvirtual, TB.cara01campus_ofimatica, TB.cara01campus_foros, TB.cara01campus_conversiones, TB.cara01campus_usocorreo, TB.cara01campus_aprendtexto, TB.cara01campus_aprendvideo, TB.cara01campus_aprendmapas, TB.cara01campus_aprendeanima, TB.cara01campus_mediocomunica, TB.cara01lab_situacion, TB.cara01lab_sector, TB.cara01lab_caracterjuri, TB.cara01lab_cargo, TB.cara01lab_antiguedad, TB.cara01lab_tipocontrato, TB.cara01lab_rangoingreso, TB.cara01lab_tiempoacadem, TB.cara01lab_tipoempresa, TB.cara01lab_tiempoindepen, TB.cara01lab_debebusctrab, TB.cara01lab_origendinero, TB.cara01bien_baloncesto, TB.cara01bien_voleibol, TB.cara01bien_futbolsala, TB.cara01bien_artesmarc, TB.cara01bien_tenisdemesa, TB.cara01bien_ajedrez, TB.cara01bien_juegosautoc, TB.cara01bien_interesrepdeporte, TB.cara01bien_deporteint, TB.cara01bien_teatro, TB.cara01bien_danza, TB.cara01bien_musica, TB.cara01bien_circo, TB.cara01bien_artplast, TB.cara01bien_cuenteria, TB.cara01bien_interesreparte, TB.cara01bien_arteint, TB.cara01bien_interpreta, TB.cara01bien_nivelinter, TB.cara01bien_danza_mod, TB.cara01bien_danza_clas, TB.cara01bien_danza_cont, TB.cara01bien_danza_folk, TB.cara01bien_niveldanza, TB.cara01bien_emprendedor, TB.cara01bien_nombreemp, TB.cara01bien_capacempren, TB.cara01bien_tipocapacita, TB.cara01bien_impvidasalud, TB.cara01bien_estraautocuid, TB.cara01bien_pv_personal, TB.cara01bien_pv_familiar, TB.cara01bien_pv_academ, TB.cara01bien_pv_labora, TB.cara01bien_pv_pareja, TB.cara01bien_amb, TB.cara01bien_amb_agu, TB.cara01bien_amb_bom, TB.cara01bien_amb_car, TB.cara01bien_amb_info, TB.cara01bien_amb_temas, TB.cara01psico_costoemocion, TB.cara01psico_reaccionimpre, TB.cara01psico_estres, TB.cara01psico_pocotiempo, TB.cara01psico_actitudvida, TB.cara01psico_duda, TB.cara01psico_problemapers, TB.cara01psico_satisfaccion, TB.cara01psico_discusiones, TB.cara01psico_atencion, TB.cara01niveldigital, TB.cara01nivellectura, TB.cara01nivelrazona, TB.cara01nivelingles, TB.cara01idperaca, TB.cara01idtercero, T2.unad11tipodoc AS C2_td, T2.unad11doc AS C2_doc, TB.cara01pais, TB.cara01depto, TB.cara01ciudad, TB.cara01estcivil, TB.cara01idzona, TB.cara01idcead, TB.cara01indigenas, TB.cara01idconfirmadesp, T40.unad11tipodoc AS C40_td, T40.unad11doc AS C40_doc, TB.cara01idconfirmacr, T43.unad11tipodoc AS C43_td, T43.unad11doc AS C43_doc, TB.cara01idconfirmadisc, T52.unad11tipodoc AS C52_td, T52.unad11doc AS C52_doc, TB.cara01acad_razonestudio, TB.cara01acad_razonunad 
FROM cara01encuesta AS TB, exte02per_aca AS T1, unad11terceros AS T2, unad18pais AS T18, unad19depto AS T19, unad20ciudad AS T20, unad21estadocivil AS T25, unad23zona AS T30, unad24sede AS T31, cara02indigenas AS T38, unad11terceros AS T40, unad11terceros AS T43, unad11terceros AS T52, cara04razonestudio AS T72, cara05razonunad AS T75 
WHERE ' . $sSQLadd1 . ' TB.cara01idperaca=T1.exte02id AND TB.cara01idtercero=T2.unad11id AND TB.cara01pais=T18.unad18codigo AND TB.cara01depto=T19.unad19codigo AND TB.cara01ciudad=T20.unad20codigo AND TB.cara01estcivil=T25.unad21codigo AND TB.cara01idzona=T30.unad23id AND TB.cara01idcead=T31.unad24id AND TB.cara01indigenas=T38.cara02id AND TB.cara01idconfirmadesp=T40.unad11id AND TB.cara01idconfirmacr=T43.unad11id AND TB.cara01idconfirmadisc=T52.unad11id AND TB.cara01acad_razonestudio=T72.cara04id AND TB.cara01acad_razonunad=T75.cara05id ' . $sSQLadd . '
ORDER BY TB.cara01idperaca, TB.cara01idtercero';
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
		if ($registros == 0) {
			//return array(utf8_encode($sErrConsulta.'<input id="paginaf2301" name="paginaf2301" type="hidden" value="'.$pagina.'"/><input id="lppf2301" name="lppf2301" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
<td><b>' . $ETI['cara01idperaca'] . '</b></td>
<td colspan="2"><b>' . $ETI['cara01idtercero'] . '</b></td>
<td><b>' . $ETI['cara01completa'] . '</b></td>
<td><b>' . $ETI['cara01fichaper'] . '</b></td>
<td><b>' . $ETI['cara01fichafam'] . '</b></td>
<td><b>' . $ETI['cara01fichaaca'] . '</b></td>
<td><b>' . $ETI['cara01fichalab'] . '</b></td>
<td><b>' . $ETI['cara01fichabien'] . '</b></td>
<td><b>' . $ETI['cara01fichapsico'] . '</b></td>
<td><b>' . $ETI['cara01fichadigital'] . '</b></td>
<td><b>' . $ETI['cara01fichalectura'] . '</b></td>
<td><b>' . $ETI['cara01ficharazona'] . '</b></td>
<td><b>' . $ETI['cara01fichaingles'] . '</b></td>
<td><b>' . $ETI['cara01fechaencuesta'] . '</b></td>
<td><b>' . $ETI['cara01agnos'] . '</b></td>
<td><b>' . $ETI['cara01sexo'] . '</b></td>
<td><b>' . $ETI['cara01pais'] . '</b></td>
<td><b>' . $ETI['cara01depto'] . '</b></td>
<td><b>' . $ETI['cara01ciudad'] . '</b></td>
<td><b>' . $ETI['cara01nomciudad'] . '</b></td>
<td><b>' . $ETI['cara01direccion'] . '</b></td>
<td><b>' . $ETI['cara01estrato'] . '</b></td>
<td><b>' . $ETI['cara01zonares'] . '</b></td>
<td><b>' . $ETI['cara01estcivil'] . '</b></td>
<td><b>' . $ETI['cara01nomcontacto'] . '</b></td>
<td><b>' . $ETI['cara01parentezcocontacto'] . '</b></td>
<td><b>' . $ETI['cara01celcontacto'] . '</b></td>
<td><b>' . $ETI['cara01correocontacto'] . '</b></td>
<td><b>' . $ETI['cara01idzona'] . '</b></td>
<td><b>' . $ETI['cara01idcead'] . '</b></td>
<td><b>' . $ETI['cara01matconvenio'] . '</b></td>
<td><b>' . $ETI['cara01raizal'] . '</b></td>
<td><b>' . $ETI['cara01palenquero'] . '</b></td>
<td><b>' . $ETI['cara01afrocolombiano'] . '</b></td>
<td><b>' . $ETI['cara01otracomunnegras'] . '</b></td>
<td><b>' . $ETI['cara01rom'] . '</b></td>
<td><b>' . $ETI['cara01indigenas'] . '</b></td>
<td><b>' . $ETI['cara01victimadesplazado'] . '</b></td>
<td colspan="2"><b>' . $ETI['cara01idconfirmadesp'] . '</b></td>
<td><b>' . $ETI['cara01fechaconfirmadesp'] . '</b></td>
<td><b>' . $ETI['cara01victimaacr'] . '</b></td>
<td colspan="2"><b>' . $ETI['cara01idconfirmacr'] . '</b></td>
<td><b>' . $ETI['cara01fechaconfirmacr'] . '</b></td>
<td><b>' . $ETI['cara01inpecfuncionario'] . '</b></td>
<td><b>' . $ETI['cara01inpecrecluso'] . '</b></td>
<td><b>' . $ETI['cara01inpectiempocondena'] . '</b></td>
<td><b>' . $ETI['cara01centroreclusion'] . '</b></td>
<td><b>' . $ETI['cara01discsensorial'] . '</b></td>
<td><b>' . $ETI['cara01discfisica'] . '</b></td>
<td><b>' . $ETI['cara01disccognitiva'] . '</b></td>
<td colspan="2"><b>' . $ETI['cara01idconfirmadisc'] . '</b></td>
<td><b>' . $ETI['cara01fechaconfirmadisc'] . '</b></td>
<td><b>' . $ETI['cara01fam_tipovivienda'] . '</b></td>
<td><b>' . $ETI['cara01fam_vivecon'] . '</b></td>
<td><b>' . $ETI['cara01fam_numpersgrupofam'] . '</b></td>
<td><b>' . $ETI['cara01fam_hijos'] . '</b></td>
<td><b>' . $ETI['cara01fam_personasacargo'] . '</b></td>
<td><b>' . $ETI['cara01fam_dependeecon'] . '</b></td>
<td><b>' . $ETI['cara01fam_escolaridadpadre'] . '</b></td>
<td><b>' . $ETI['cara01fam_escolaridadmadre'] . '</b></td>
<td><b>' . $ETI['cara01fam_numhermanos'] . '</b></td>
<td><b>' . $ETI['cara01fam_posicionherm'] . '</b></td>
<td><b>' . $ETI['cara01fam_familiaunad'] . '</b></td>
<td><b>' . $ETI['cara01acad_tipocolegio'] . '</b></td>
<td><b>' . $ETI['cara01acad_modalidadbach'] . '</b></td>
<td><b>' . $ETI['cara01acad_estudioprev'] . '</b></td>
<td><b>' . $ETI['cara01acad_ultnivelest'] . '</b></td>
<td><b>' . $ETI['cara01acad_obtubodiploma'] . '</b></td>
<td><b>' . $ETI['cara01acad_hatomadovirtual'] . '</b></td>
<td><b>' . $ETI['cara01acad_tiemposinest'] . '</b></td>
<td><b>' . $ETI['cara01acad_razonestudio'] . '</b></td>
<td><b>' . $ETI['cara01acad_primeraopc'] . '</b></td>
<td><b>' . $ETI['cara01acad_programagusto'] . '</b></td>
<td><b>' . $ETI['cara01acad_razonunad'] . '</b></td>
<td><b>' . $ETI['cara01campus_compescrito'] . '</b></td>
<td><b>' . $ETI['cara01campus_portatil'] . '</b></td>
<td><b>' . $ETI['cara01campus_tableta'] . '</b></td>
<td><b>' . $ETI['cara01campus_telefono'] . '</b></td>
<td><b>' . $ETI['cara01campus_energia'] . '</b></td>
<td><b>' . $ETI['cara01campus_internetreside'] . '</b></td>
<td><b>' . $ETI['cara01campus_expvirtual'] . '</b></td>
<td><b>' . $ETI['cara01campus_ofimatica'] . '</b></td>
<td><b>' . $ETI['cara01campus_foros'] . '</b></td>
<td><b>' . $ETI['cara01campus_conversiones'] . '</b></td>
<td><b>' . $ETI['cara01campus_usocorreo'] . '</b></td>
<td><b>' . $ETI['cara01campus_aprendtexto'] . '</b></td>
<td><b>' . $ETI['cara01campus_aprendvideo'] . '</b></td>
<td><b>' . $ETI['cara01campus_aprendmapas'] . '</b></td>
<td><b>' . $ETI['cara01campus_aprendeanima'] . '</b></td>
<td><b>' . $ETI['cara01campus_mediocomunica'] . '</b></td>
<td><b>' . $ETI['cara01lab_situacion'] . '</b></td>
<td><b>' . $ETI['cara01lab_sector'] . '</b></td>
<td><b>' . $ETI['cara01lab_caracterjuri'] . '</b></td>
<td><b>' . $ETI['cara01lab_cargo'] . '</b></td>
<td><b>' . $ETI['cara01lab_antiguedad'] . '</b></td>
<td><b>' . $ETI['cara01lab_tipocontrato'] . '</b></td>
<td><b>' . $ETI['cara01lab_rangoingreso'] . '</b></td>
<td><b>' . $ETI['cara01lab_tiempoacadem'] . '</b></td>
<td><b>' . $ETI['cara01lab_tipoempresa'] . '</b></td>
<td><b>' . $ETI['cara01lab_tiempoindepen'] . '</b></td>
<td><b>' . $ETI['cara01lab_debebusctrab'] . '</b></td>
<td><b>' . $ETI['cara01lab_origendinero'] . '</b></td>
<td><b>' . $ETI['cara01bien_baloncesto'] . '</b></td>
<td><b>' . $ETI['cara01bien_voleibol'] . '</b></td>
<td><b>' . $ETI['cara01bien_futbolsala'] . '</b></td>
<td><b>' . $ETI['cara01bien_artesmarc'] . '</b></td>
<td><b>' . $ETI['cara01bien_tenisdemesa'] . '</b></td>
<td><b>' . $ETI['cara01bien_ajedrez'] . '</b></td>
<td><b>' . $ETI['cara01bien_juegosautoc'] . '</b></td>
<td><b>' . $ETI['cara01bien_interesrepdeporte'] . '</b></td>
<td><b>' . $ETI['cara01bien_deporteint'] . '</b></td>
<td><b>' . $ETI['cara01bien_teatro'] . '</b></td>
<td><b>' . $ETI['cara01bien_danza'] . '</b></td>
<td><b>' . $ETI['cara01bien_musica'] . '</b></td>
<td><b>' . $ETI['cara01bien_circo'] . '</b></td>
<td><b>' . $ETI['cara01bien_artplast'] . '</b></td>
<td><b>' . $ETI['cara01bien_cuenteria'] . '</b></td>
<td><b>' . $ETI['cara01bien_interesreparte'] . '</b></td>
<td><b>' . $ETI['cara01bien_arteint'] . '</b></td>
<td><b>' . $ETI['cara01bien_interpreta'] . '</b></td>
<td><b>' . $ETI['cara01bien_nivelinter'] . '</b></td>
<td><b>' . $ETI['cara01bien_danza_mod'] . '</b></td>
<td><b>' . $ETI['cara01bien_danza_clas'] . '</b></td>
<td><b>' . $ETI['cara01bien_danza_cont'] . '</b></td>
<td><b>' . $ETI['cara01bien_danza_folk'] . '</b></td>
<td><b>' . $ETI['cara01bien_niveldanza'] . '</b></td>
<td><b>' . $ETI['cara01bien_emprendedor'] . '</b></td>
<td><b>' . $ETI['cara01bien_nombreemp'] . '</b></td>
<td><b>' . $ETI['cara01bien_capacempren'] . '</b></td>
<td><b>' . $ETI['cara01bien_tipocapacita'] . '</b></td>
<td><b>' . $ETI['cara01bien_impvidasalud'] . '</b></td>
<td><b>' . $ETI['cara01bien_estraautocuid'] . '</b></td>
<td><b>' . $ETI['cara01bien_pv_personal'] . '</b></td>
<td><b>' . $ETI['cara01bien_pv_familiar'] . '</b></td>
<td><b>' . $ETI['cara01bien_pv_academ'] . '</b></td>
<td><b>' . $ETI['cara01bien_pv_labora'] . '</b></td>
<td><b>' . $ETI['cara01bien_pv_pareja'] . '</b></td>
<td><b>' . $ETI['cara01bien_amb'] . '</b></td>
<td><b>' . $ETI['cara01bien_amb_agu'] . '</b></td>
<td><b>' . $ETI['cara01bien_amb_bom'] . '</b></td>
<td><b>' . $ETI['cara01bien_amb_car'] . '</b></td>
<td><b>' . $ETI['cara01bien_amb_info'] . '</b></td>
<td><b>' . $ETI['cara01bien_amb_temas'] . '</b></td>
<td><b>' . $ETI['cara01psico_costoemocion'] . '</b></td>
<td><b>' . $ETI['cara01psico_reaccionimpre'] . '</b></td>
<td><b>' . $ETI['cara01psico_estres'] . '</b></td>
<td><b>' . $ETI['cara01psico_pocotiempo'] . '</b></td>
<td><b>' . $ETI['cara01psico_actitudvida'] . '</b></td>
<td><b>' . $ETI['cara01psico_duda'] . '</b></td>
<td><b>' . $ETI['cara01psico_problemapers'] . '</b></td>
<td><b>' . $ETI['cara01psico_satisfaccion'] . '</b></td>
<td><b>' . $ETI['cara01psico_discusiones'] . '</b></td>
<td><b>' . $ETI['cara01psico_atencion'] . '</b></td>
<td><b>' . $ETI['cara01niveldigital'] . '</b></td>
<td><b>' . $ETI['cara01nivellectura'] . '</b></td>
<td><b>' . $ETI['cara01nivelrazona'] . '</b></td>
<td><b>' . $ETI['cara01nivelingles'] . '</b></td>
<td align="right">
' . html_paginador('paginabusqueda', $registros, $lineastabla, $pagina, 'paginarbusqueda()') . '
' . html_lpp('lppfbusqueda', $lineastabla, 'paginarbusqueda()') . '
</td>
</tr>';
	$tlinea = 1;
	while ($filadet = $objDB->sf($tabladetalle)) {
		$sPrefijo = '<a href="javascript:Devuelve(\'' . $filadet['cara01id'] . '\');">';
		$sSufijo = '</a>';
		$tlinea++;
		$et_cara01completa = $ETI['msg_abierto'];
		if ($filadet['cara01completa'] == 'S') {
			$et_cara01completa = $ETI['msg_cerrado'];
		}
		$et_cara01fechaencuesta = '';
		if ($filadet['cara01fechaencuesta'] != 0) {
			$et_cara01fechaencuesta = fecha_desdenumero($filadet['cara01fechaencuesta']);
		}
		$et_cara01fechaconfirmadesp = '';
		if ($filadet['cara01fechaconfirmadesp'] != 0) {
			$et_cara01fechaconfirmadesp = fecha_desdenumero($filadet['cara01fechaconfirmadesp']);
		}
		$et_cara01fechaconfirmacr = '';
		if ($filadet['cara01fechaconfirmacr'] != 0) {
			$et_cara01fechaconfirmacr = fecha_desdenumero($filadet['cara01fechaconfirmacr']);
		}
		$et_cara01fechaconfirmadisc = '';
		if ($filadet['cara01fechaconfirmadisc'] != 0) {
			$et_cara01fechaconfirmadisc = fecha_desdenumero($filadet['cara01fechaconfirmadisc']);
		}
		$res = $res . '<tr onmouseover="cambia_color_over(this);" onmouseout="cambia_color_out(this);">
<td>' . $sPrefijo . cadena_notildes($filadet['exte02nombre']) . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['C2_td'] . ' ' . $filadet['C2_doc'] . $sSufijo . '</td>
<td>' . $sPrefijo . cadena_notildes($filadet['C2_nombre']) . $sSufijo . '</td>
<td>' . $sPrefijo . $et_cara01completa . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['cara01fichaper'] . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['cara01fichafam'] . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['cara01fichaaca'] . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['cara01fichalab'] . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['cara01fichabien'] . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['cara01fichapsico'] . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['cara01fichadigital'] . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['cara01fichalectura'] . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['cara01ficharazona'] . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['cara01fichaingles'] . $sSufijo . '</td>
<td>' . $sPrefijo . $et_cara01fechaencuesta . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['cara01agnos'] . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['cara01sexo'] . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['cara01pais'] . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['cara01depto'] . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['cara01ciudad'] . $sSufijo . '</td>
<td>' . $sPrefijo . cadena_notildes($filadet['cara01nomciudad']) . $sSufijo . '</td>
<td>' . $sPrefijo . cadena_notildes($filadet['cara01direccion']) . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['cara01estrato'] . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['cara01zonares'] . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['cara01estcivil'] . $sSufijo . '</td>
<td>' . $sPrefijo . cadena_notildes($filadet['cara01nomcontacto']) . $sSufijo . '</td>
<td>' . $sPrefijo . cadena_notildes($filadet['cara01parentezcocontacto']) . $sSufijo . '</td>
<td>' . $sPrefijo . cadena_notildes($filadet['cara01celcontacto']) . $sSufijo . '</td>
<td>' . $sPrefijo . cadena_notildes($filadet['cara01correocontacto']) . $sSufijo . '</td>
<td>' . $sPrefijo . cadena_notildes($filadet['unad23nombre']) . $sSufijo . '</td>
<td>' . $sPrefijo . cadena_notildes($filadet['unad24nombre']) . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['cara01matconvenio'] . $sSufijo . '</td>
<td>' . $sPrefijo . cadena_notildes($filadet['cara01raizal']) . $sSufijo . '</td>
<td>' . $sPrefijo . cadena_notildes($filadet['cara01palenquero']) . $sSufijo . '</td>
<td>' . $sPrefijo . cadena_notildes($filadet['cara01afrocolombiano']) . $sSufijo . '</td>
<td>' . $sPrefijo . cadena_notildes($filadet['cara01otracomunnegras']) . $sSufijo . '</td>
<td>' . $sPrefijo . cadena_notildes($filadet['cara01rom']) . $sSufijo . '</td>
<td>' . $sPrefijo . cadena_notildes($filadet['cara02nombre']) . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['cara01victimadesplazado'] . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['C40_td'] . ' ' . $filadet['C40_doc'] . $sSufijo . '</td>
<td>' . $sPrefijo . cadena_notildes($filadet['C40_nombre']) . $sSufijo . '</td>
<td>' . $sPrefijo . $et_cara01fechaconfirmadesp . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['cara01victimaacr'] . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['C43_td'] . ' ' . $filadet['C43_doc'] . $sSufijo . '</td>
<td>' . $sPrefijo . cadena_notildes($filadet['C43_nombre']) . $sSufijo . '</td>
<td>' . $sPrefijo . $et_cara01fechaconfirmacr . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['cara01inpecfuncionario'] . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['cara01inpecrecluso'] . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['cara01inpectiempocondena'] . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['cara01centroreclusion'] . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['cara01discsensorial'] . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['cara01discfisica'] . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['cara01disccognitiva'] . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['C52_td'] . ' ' . $filadet['C52_doc'] . $sSufijo . '</td>
<td>' . $sPrefijo . cadena_notildes($filadet['C52_nombre']) . $sSufijo . '</td>
<td>' . $sPrefijo . $et_cara01fechaconfirmadisc . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['cara01fam_tipovivienda'] . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['cara01fam_vivecon'] . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['cara01fam_numpersgrupofam'] . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['cara01fam_hijos'] . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['cara01fam_personasacargo'] . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['cara01fam_dependeecon'] . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['cara01fam_escolaridadpadre'] . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['cara01fam_escolaridadmadre'] . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['cara01fam_numhermanos'] . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['cara01fam_posicionherm'] . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['cara01fam_familiaunad'] . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['cara01acad_tipocolegio'] . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['cara01acad_modalidadbach'] . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['cara01acad_estudioprev'] . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['cara01acad_ultnivelest'] . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['cara01acad_obtubodiploma'] . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['cara01acad_hatomadovirtual'] . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['cara01acad_tiemposinest'] . $sSufijo . '</td>
<td>' . $sPrefijo . cadena_notildes($filadet['cara04nombre']) . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['cara01acad_primeraopc'] . $sSufijo . '</td>
<td>' . $sPrefijo . cadena_notildes($filadet['cara01acad_programagusto']) . $sSufijo . '</td>
<td>' . $sPrefijo . cadena_notildes($filadet['cara05nombre']) . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['cara01campus_compescrito'] . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['cara01campus_portatil'] . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['cara01campus_tableta'] . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['cara01campus_telefono'] . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['cara01campus_energia'] . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['cara01campus_internetreside'] . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['cara01campus_expvirtual'] . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['cara01campus_ofimatica'] . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['cara01campus_foros'] . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['cara01campus_conversiones'] . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['cara01campus_usocorreo'] . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['cara01campus_aprendtexto'] . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['cara01campus_aprendvideo'] . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['cara01campus_aprendmapas'] . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['cara01campus_aprendeanima'] . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['cara01campus_mediocomunica'] . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['cara01lab_situacion'] . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['cara01lab_sector'] . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['cara01lab_caracterjuri'] . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['cara01lab_cargo'] . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['cara01lab_antiguedad'] . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['cara01lab_tipocontrato'] . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['cara01lab_rangoingreso'] . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['cara01lab_tiempoacadem'] . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['cara01lab_tipoempresa'] . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['cara01lab_tiempoindepen'] . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['cara01lab_debebusctrab'] . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['cara01lab_origendinero'] . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['cara01bien_baloncesto'] . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['cara01bien_voleibol'] . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['cara01bien_futbolsala'] . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['cara01bien_artesmarc'] . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['cara01bien_tenisdemesa'] . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['cara01bien_ajedrez'] . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['cara01bien_juegosautoc'] . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['cara01bien_interesrepdeporte'] . $sSufijo . '</td>
<td>' . $sPrefijo . cadena_notildes($filadet['cara01bien_deporteint']) . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['cara01bien_teatro'] . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['cara01bien_danza'] . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['cara01bien_musica'] . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['cara01bien_circo'] . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['cara01bien_artplast'] . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['cara01bien_cuenteria'] . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['cara01bien_interesreparte'] . $sSufijo . '</td>
<td>' . $sPrefijo . cadena_notildes($filadet['cara01bien_arteint']) . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['cara01bien_interpreta'] . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['cara01bien_nivelinter'] . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['cara01bien_danza_mod'] . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['cara01bien_danza_clas'] . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['cara01bien_danza_cont'] . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['cara01bien_danza_folk'] . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['cara01bien_niveldanza'] . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['cara01bien_emprendedor'] . $sSufijo . '</td>
<td>' . $sPrefijo . cadena_notildes($filadet['cara01bien_nombreemp']) . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['cara01bien_capacempren'] . $sSufijo . '</td>
<td>' . $sPrefijo . cadena_notildes($filadet['cara01bien_tipocapacita']) . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['cara01bien_impvidasalud'] . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['cara01bien_estraautocuid'] . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['cara01bien_pv_personal'] . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['cara01bien_pv_familiar'] . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['cara01bien_pv_academ'] . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['cara01bien_pv_labora'] . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['cara01bien_pv_pareja'] . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['cara01bien_amb'] . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['cara01bien_amb_agu'] . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['cara01bien_amb_bom'] . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['cara01bien_amb_car'] . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['cara01bien_amb_info'] . $sSufijo . '</td>
<td>' . $sPrefijo . cadena_notildes($filadet['cara01bien_amb_temas']) . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['cara01psico_costoemocion'] . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['cara01psico_reaccionimpre'] . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['cara01psico_estres'] . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['cara01psico_pocotiempo'] . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['cara01psico_actitudvida'] . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['cara01psico_duda'] . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['cara01psico_problemapers'] . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['cara01psico_satisfaccion'] . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['cara01psico_discusiones'] . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['cara01psico_atencion'] . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['cara01niveldigital'] . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['cara01nivellectura'] . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['cara01nivelrazona'] . $sSufijo . '</td>
<td>' . $sPrefijo . $filadet['cara01nivelingles'] . $sSufijo . '</td>
<td></td>
</tr>';
	}
	$res = $res . '</table>';
	$objDB->liberar($tabladetalle);
	return utf8_encode($res);
}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------
function f2301_HTMLComboV2_bprograma($objDB, $objCombos, $valor, $vrbescuela)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	//@@ Se debe arreglar la condicion..
	$sCondi = 'core09idescuela="' . $vrbescuela . '"';
	if ($sCondi != '') {
		$sCondi = ' WHERE ' . $sCondi;
	}
	$objCombos->nuevo('bprograma', $valor, true, '{' . $ETI['msg_todos'] . '}');
	$objCombos->sAccion = 'paginarf2301()';
	$res = $objCombos->html('SELECT core09id AS id, core09nombre AS nombre FROM core09programa' . $sCondi, $objDB);
	return $res;
}
function f2301_Combobprograma($aParametros)
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
	$objCombos = new clsHtmlCombos('n');
	$html_bprograma = f2301_HTMLComboV2_bprograma($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_bprograma', 'innerHTML', $html_bprograma);
	$objResponse->call('paginarf2301');
	return $objResponse;
}
function f2301_HTMLComboV2_bcead($objDB, $objCombos, $valor, $vrcara01idzona)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	//@@ Se debe arreglar la condicion..
	$sCondi = 'unad24idzona="' . $vrcara01idzona . '"';
	if ($sCondi != '') {
		$sCondi = ' WHERE ' . $sCondi;
	}
	$objCombos->nuevo('bcead', $valor, true, '{' . $ETI['msg_todos'] . '}');
	$objCombos->sAccion = 'paginarf2301()';
	$res = $objCombos->html('SELECT unad24id AS id, unad24nombre AS nombre FROM unad24sede' . $sCondi, $objDB);
	return $res;
}
function f2301_Combobcead($aParametros)
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
	$objCombos = new clsHtmlCombos('n');
	$html_bcead = f2301_HTMLComboV2_bcead($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_bcead', 'innerHTML', $html_bcead);
	$objResponse->call('paginarf2301');
	return $objResponse;
}
function f2301_Pendiente($idTercero, $objDB, $bDebug = false)
{
	$iRes = 0;
	$iDiaIni = 0;
	$sDebug = '';
	//Ver si tiene una encuesta no terminada.
	$sSQL = 'SELECT cara01completa, cara01fechainicio FROM cara01encuesta WHERE cara01idtercero=' . $idTercero . ' ORDER BY cara01completa';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$iDiaIni = $fila['cara01fechainicio'];
		if ($fila['cara01completa'] != 'S') {
			$iRes = 2;
		}
	} else {
		//Ver que sea estudiante.
		$sSQL = 'SELECT TB.core01idprograma, T1.core09idtipocaracterizacion FROM core01estprograma AS TB, core09programa AS T1 WHERE TB.core01idtercero=' . $idTercero . ' AND TB.core01idestado=0 AND TB.core01idprograma=T1.core09id';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			if ($fila['core09idtipocaracterizacion'] != 0) {
				$iRes = 1;
			}
		}
	}
	return array($iRes, $iDiaIni, $sDebug);
}
function f2301_MarcarConsejero($aParametros)
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
	$id2301 = $aParametros[0];
	$sSQL = 'UPDATE cara01encuesta SET cara01idconsejero=' . $aParametros[1] . ', cara01idperiodoacompana=cara01idperaca WHERE cara01id=' . $id2301 . ' AND cara01fechacierreacom=0';
	$result = $objDB->ejecutasql($sSQL);
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_lnkconsejero' . $id2301, 'innerHTML', 'Asignado');
	return $objResponse;
}
function f2301_TablaDetalleV2Consejero($aParametros, $objDB, $bDebug = false)
{
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
	if (!is_array($aParametros)) {
		$aParametros = json_decode(str_replace('\"', '"', $aParametros), true);
	}
	if (isset($aParametros[100]) == 0) {
		$aParametros[100] = -1;
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
	//$aParametros[103]=numeros_validar($aParametros[103]);
	$aParametros[100] = numeros_validar($aParametros[100]);
	if ($aParametros[100] == '') {
		$aParametros[100] = -1;
	}
	$sDebug = '';
	$cara13idconsejero = $aParametros[100];
	$pagina = $aParametros[101];
	$lineastabla = $aParametros[102];
	$cara13peraca = $aParametros[103];
	$babierta = true;
	$sSQLadd = '';
	$sSQLadd1 = '';
	$sLeyenda = '';
	$sBotones = '<input id="paginaf2301" name="paginaf2301" type="hidden" value="' . $pagina . '"/>
	<input id="lppf2301" name="lppf2301" type="hidden" value="' . $lineastabla . '"/>';
	if ($cara13idconsejero == 0) {
		$sLeyenda = '<div class="salto1px"></div>
		<div class="GrupoCamposAyuda">
		<b>No se ha definido el consejero</b>
		<div class="salto1px"></div>
		</div>' . $sBotones;
		return array($sLeyenda, $sDebug);
		die();
	}
	//if ((int)$aParametros[103]!=-1){$sSQLadd=$sSQLadd.' AND TB.campo='.$aParametros[103];}
	//if ($aParametros[103]!=''){$sSQLadd=$sSQLadd.' AND TB.campo2 LIKE "%'.$aParametros[103].'%"';}
	/*
	if ($aParametros[104]!=''){
		$sBase=trim(strtoupper($aParametros[104]));
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
	$sTitulos = 'Consejero, Tercero, Id, Completa, Tipocaracterizacion';
	/*
	$sSQL='SELECT TB.cara01idconsejero, T2.unad11razonsocial AS C2_nombre, TB.cara01id, TB.cara01completa, T5.cara11nombre, 
	TB.cara01idtercero, T2.unad11tipodoc AS C2_td, T2.unad11doc AS C2_doc, TB.cara01tipocaracterizacion, TB.cara01idperaca 
	FROM cara01encuesta AS TB, unad11terceros AS T2, cara11tipocaract AS T5 
	WHERE '.$sSQLadd1.' TB.cara01idconsejero='.$cara13idconsejero.' AND TB.cara01idperiodoacompana='.$cara13peraca.' AND TB.cara01idtercero=T2.unad11id AND TB.cara01tipocaracterizacion=T5.cara11id '.$sSQLadd.'
	ORDER BY T2.unad11doc';
	*/
	$sSQL = 'SELECT TB.core16tercero, T2.unad11razonsocial AS C2_nombre, T2.unad11tipodoc AS C2_td, T2.unad11doc AS C2_doc
	FROM core16actamatricula AS TB, unad11terceros AS T2 
	WHERE TB.core16peraca=' . $cara13peraca . ' AND TB.core16idconsejero=' . $cara13idconsejero . ' AND TB.core16tercero=T2.unad11id';
	$sSQLlista = str_replace("'", "|", $sSQL);
	$sSQLlista = str_replace('"', "|", $sSQLlista);
	$sErrConsulta = '<input id="consulta_2301" name="consulta_2301" type="hidden" value="' . $sSQLlista . '"/>
	<input id="titulos_2301" name="titulos_2301" type="hidden" value="' . $sTitulos . '"/>';
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' Consulta de asignados: ' . $sSQL . '<br>';
	}
	$tabladetalle = $objDB->ejecutasql($sSQL);
	if ($tabladetalle == false) {
		$registros = 0;
		$sErrConsulta = $sErrConsulta . '..<input id="err" name="err" type="hidden" value="' . $sSQL . ' ' . $objDB->serror . '"/>';
		//$sLeyenda=$sSQL;
	} else {
		$registros = $objDB->nf($tabladetalle);
		if ($registros == 0) {
			//return array(utf8_encode($sErrConsulta.''), $sDebug);
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
	<td colspan="2"><b>' . $ETI['cara01idtercero'] . '</b></td>
	<td><b>' . $ETI['cara01completa'] . '</b></td>
	<td><b>' . $ETI['cara01tipocaracterizacion'] . '</b></td>
	<td align="right">
	' . html_paginador('paginaf2301', $registros, $lineastabla, $pagina, 'paginarf2301()') . '
	' . html_lpp('lppf2301', $lineastabla, 'paginarf2301()', 500) . '
	</td>
	</tr>';
	$tlinea = 1;
	while ($filadet = $objDB->sf($tabladetalle)) {
		$sPrefijo = '';
		$sSufijo = '';
		$sClass = '';
		$sLink = '';
		$et_cara01completa = '';
		/*
		$et_cara01completa=$ETI['msg_abierto'];
		if ($filadet['cara01completa']=='S'){
			$sPrefijo='<b>';
			$sSufijo='</b>';
			$et_cara01completa=$ETI['msg_cerrado'];
			}
		*/
		if (($tlinea % 2) == 0) {
			$sClass = ' class="resaltetabla"';
		}
		$tlinea++;
		$et_cara01tipocaracterizacion = '';
		//$et_cara01tipocaracterizacion=$sPrefijo.cadena_notildes($filadet['cara11nombre']).$sSufijo;
		if ($babierta) {
			//$sLink='<a href="javascript:cargaridf2301('.$filadet['cara01id'].')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
		}
		$res = $res . '<tr' . $sClass . '>
		<td>' . $sPrefijo . $filadet['C2_td'] . ' ' . $filadet['C2_doc'] . $sSufijo . '</td>
		<td>' . $sPrefijo . cadena_notildes($filadet['C2_nombre']) . $sSufijo . '</td>
		<td>' . $sPrefijo . $et_cara01completa . $sSufijo . '</td>
		<td colspan="2">' . $et_cara01tipocaracterizacion . '</td>
		</tr>';
	}
	$res = $res . '</table>';
	$objDB->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
}
function f2301_HtmlTablaConsejero($aParametros)
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
	list($sDetalle, $sDebugTabla) = f2301_TablaDetalleV2Consejero($aParametros, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_f2301detalle', 'innerHTML', $sDetalle);
	if ($bDebug) {
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
	}
	return $objResponse;
}
function f2301_AlimentarEncuesta($idUltimaEncuesta, $idNuevaEncuesta, $objDB, $bDebug = false)
{
	$sError = '';
	$sDebug = '';
	$sCondi = '';	
	$sDirBase = __DIR__ . '/';
	require $sDirBase . 'app.php';
	$sSQL = 'SELECT * FROM cara01encuesta WHERE cara01id=' . $idUltimaEncuesta . '';
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' <b>Precargue de la &uacute;ltima caracterizaci&oacute;n</b>: ' . $sSQL . '<br>';
	}
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) == 0) {
	} else {
		$fila = $objDB->sf($tabla);
		$sSQL = 'UPDATE cara01encuesta SET '
			.'cara01agnos="' . $fila['cara01agnos'] . '", '
			.'cara01sexo="' . $fila['cara01sexo'] . '", '
			.'cara01pais="' . $fila['cara01pais'] . '", '
			.'cara01depto="' . $fila['cara01depto'] . '", '
			.'cara01ciudad="' . $fila['cara01ciudad'] . '", '
			.'cara01nomciudad="' . $fila['cara01nomciudad'] . '", '
			.'cara01direccion="' . $fila['cara01direccion'] . '", '
			.'cara01estrato="' . $fila['cara01estrato'] . '", '
			.'cara01zonares="' . $fila['cara01zonares'] . '", '
			.'cara01estcivil="' . $fila['cara01estcivil'] . '", '
			.'cara01nomcontacto="' . $fila['cara01nomcontacto'] . '", '
			.'cara01parentezcocontacto="' . $fila['cara01parentezcocontacto'] . '", '
			.'cara01celcontacto="' . $fila['cara01celcontacto'] . '", '
			.'cara01correocontacto="' . $fila['cara01correocontacto'] . '", '
			.'cara01raizal="' . $fila['cara01raizal'] . '", '
			.'cara01palenquero="' . $fila['cara01palenquero'] . '", '
			.'cara01afrocolombiano="' . $fila['cara01afrocolombiano'] . '", '
			.'cara01otracomunnegras="' . $fila['cara01otracomunnegras'] . '", '
			.'cara01rom="' . $fila['cara01rom'] . '", '
			.'cara01indigenas="' . $fila['cara01indigenas'] . '", '
			.'cara01victimadesplazado="' . $fila['cara01victimadesplazado'] . '", '
			.'cara01idconfirmadesp="' . $fila['cara01idconfirmadesp'] . '", '
			.'cara01fechaconfirmadesp="' . $fila['cara01fechaconfirmadesp'] . '", '
			.'cara01victimaacr="' . $fila['cara01victimaacr'] . '", '
			.'cara01idconfirmacr="' . $fila['cara01idconfirmacr'] . '", '
			.'cara01fechaconfirmacr="' . $fila['cara01fechaconfirmacr'] . '", '
			.'cara01inpecfuncionario="' . $fila['cara01inpecfuncionario'] . '", '
			.'cara01inpecrecluso="' . $fila['cara01inpecrecluso'] . '", '
			.'cara01inpectiempocondena="' . $fila['cara01inpectiempocondena'] . '", '
			.'cara01centroreclusion="' . $fila['cara01centroreclusion'] . '", '
			.'cara01discsensorial="' . $fila['cara01discsensorial'] . '", '
			.'cara01discfisica="' . $fila['cara01discfisica'] . '", '
			.'cara01disccognitiva="' . $fila['cara01disccognitiva'] . '", '
			.'cara01idconfirmadisc="' . $fila['cara01idconfirmadisc'] . '", '
			.'cara01fechaconfirmadisc="' . $fila['cara01fechaconfirmadisc'] . '", '
			.'cara01fam_tipovivienda="' . $fila['cara01fam_tipovivienda'] . '", '
			.'cara01fam_vivecon="' . $fila['cara01fam_vivecon'] . '", '
			.'cara01fam_numpersgrupofam="' . $fila['cara01fam_numpersgrupofam'] . '", '
			.'cara01fam_hijos="' . $fila['cara01fam_hijos'] . '", '
			.'cara01fam_personasacargo="' . $fila['cara01fam_personasacargo'] . '", '
			.'cara01fam_dependeecon="' . $fila['cara01fam_dependeecon'] . '", '
			.'cara01fam_escolaridadpadre="' . $fila['cara01fam_escolaridadpadre'] . '", '
			.'cara01fam_escolaridadmadre="' . $fila['cara01fam_escolaridadmadre'] . '", '
			.'cara01fam_numhermanos="' . $fila['cara01fam_numhermanos'] . '", '
			.'cara01fam_posicionherm="' . $fila['cara01fam_posicionherm'] . '", '
			.'cara01fam_familiaunad="' . $fila['cara01fam_familiaunad'] . '", '
			.'cara01acad_tipocolegio="' . $fila['cara01acad_tipocolegio'] . '", '
			.'cara01acad_modalidadbach="' . $fila['cara01acad_modalidadbach'] . '", '
			.'cara01acad_estudioprev="' . $fila['cara01acad_estudioprev'] . '", '
			.'cara01acad_ultnivelest="' . $fila['cara01acad_ultnivelest'] . '", '
			.'cara01acad_obtubodiploma="' . $fila['cara01acad_obtubodiploma'] . '", '
			.'cara01acad_hatomadovirtual="' . $fila['cara01acad_hatomadovirtual'] . '", '
			.'cara01acad_tiemposinest="' . $fila['cara01acad_tiemposinest'] . '", '
			.'cara01acad_razonestudio="' . $fila['cara01acad_razonestudio'] . '", '
			.'cara01acad_primeraopc="' . $fila['cara01acad_primeraopc'] . '", '
			.'cara01acad_programagusto="' . $fila['cara01acad_programagusto'] . '", '
			.'cara01acad_razonunad="' . $fila['cara01acad_razonunad'] . '", '
			.'cara01campus_compescrito="' . $fila['cara01campus_compescrito'] . '", '
			.'cara01campus_portatil="' . $fila['cara01campus_portatil'] . '", '
			.'cara01campus_tableta="' . $fila['cara01campus_tableta'] . '", '
			.'cara01campus_telefono="' . $fila['cara01campus_telefono'] . '", '
			.'cara01campus_energia="' . $fila['cara01campus_energia'] . '", '
			.'cara01campus_internetreside="' . $fila['cara01campus_internetreside'] . '", '
			.'cara01campus_expvirtual="' . $fila['cara01campus_expvirtual'] . '", '
			.'cara01campus_ofimatica="' . $fila['cara01campus_ofimatica'] . '", '
			.'cara01campus_foros="' . $fila['cara01campus_foros'] . '", '
			.'cara01campus_conversiones="' . $fila['cara01campus_conversiones'] . '", '
			.'cara01campus_usocorreo="' . $fila['cara01campus_usocorreo'] . '", '
			.'cara01campus_aprendtexto="' . $fila['cara01campus_aprendtexto'] . '", '
			.'cara01campus_aprendvideo="' . $fila['cara01campus_aprendvideo'] . '", '
			.'cara01campus_aprendmapas="' . $fila['cara01campus_aprendmapas'] . '", '
			.'cara01campus_aprendeanima="' . $fila['cara01campus_aprendeanima'] . '", '
			.'cara01campus_mediocomunica="' . $fila['cara01campus_mediocomunica'] . '", '
			.'cara01lab_situacion="' . $fila['cara01lab_situacion'] . '", '
			.'cara01lab_sector="' . $fila['cara01lab_sector'] . '", '
			.'cara01lab_caracterjuri="' . $fila['cara01lab_caracterjuri'] . '", '
			.'cara01lab_cargo="' . $fila['cara01lab_cargo'] . '", '
			.'cara01lab_antiguedad="' . $fila['cara01lab_antiguedad'] . '", '
			.'cara01lab_tipocontrato="' . $fila['cara01lab_tipocontrato'] . '", '
			.'cara01lab_rangoingreso="' . $fila['cara01lab_rangoingreso'] . '", '
			.'cara01lab_tiempoacadem="' . $fila['cara01lab_tiempoacadem'] . '", '
			.'cara01lab_tipoempresa="' . $fila['cara01lab_tipoempresa'] . '", '
			.'cara01lab_tiempoindepen="' . $fila['cara01lab_tiempoindepen'] . '", '
			.'cara01lab_debebusctrab="' . $fila['cara01lab_debebusctrab'] . '", '
			.'cara01lab_origendinero="' . $fila['cara01lab_origendinero'] . '", '
			.'cara01bien_baloncesto="' . $fila['cara01bien_baloncesto'] . '", '
			.'cara01bien_voleibol="' . $fila['cara01bien_voleibol'] . '", '
			.'cara01bien_futbolsala="' . $fila['cara01bien_futbolsala'] . '", '
			.'cara01bien_artesmarc="' . $fila['cara01bien_artesmarc'] . '", '
			.'cara01bien_tenisdemesa="' . $fila['cara01bien_tenisdemesa'] . '", '
			.'cara01bien_ajedrez="' . $fila['cara01bien_ajedrez'] . '", '
			.'cara01bien_juegosautoc="' . $fila['cara01bien_juegosautoc'] . '", '
			.'cara01bien_interesrepdeporte="' . $fila['cara01bien_interesrepdeporte'] . '", '
			.'cara01bien_deporteint="' . $fila['cara01bien_deporteint'] . '", '
			.'cara01bien_teatro="' . $fila['cara01bien_teatro'] . '", '
			.'cara01bien_danza="' . $fila['cara01bien_danza'] . '", '
			.'cara01bien_musica="' . $fila['cara01bien_musica'] . '", '
			.'cara01bien_circo="' . $fila['cara01bien_circo'] . '", '
			.'cara01bien_artplast="' . $fila['cara01bien_artplast'] . '", '
			.'cara01bien_cuenteria="' . $fila['cara01bien_cuenteria'] . '", '
			.'cara01bien_interesreparte="' . $fila['cara01bien_interesreparte'] . '", '
			.'cara01bien_arteint="' . $fila['cara01bien_arteint'] . '", '
			.'cara01bien_interpreta="' . $fila['cara01bien_interpreta'] . '", '
			.'cara01bien_nivelinter="' . $fila['cara01bien_nivelinter'] . '", '
			.'cara01bien_danza_mod="' . $fila['cara01bien_danza_mod'] . '", '
			.'cara01bien_danza_clas="' . $fila['cara01bien_danza_clas'] . '", '
			.'cara01bien_danza_cont="' . $fila['cara01bien_danza_cont'] . '", '
			.'cara01bien_danza_folk="' . $fila['cara01bien_danza_folk'] . '", '
			.'cara01bien_niveldanza="' . $fila['cara01bien_niveldanza'] . '", '
			.'cara01bien_emprendedor="' . $fila['cara01bien_emprendedor'] . '", '
			.'cara01bien_nombreemp="' . $fila['cara01bien_nombreemp'] . '", '
			.'cara01bien_capacempren="' . $fila['cara01bien_capacempren'] . '", '
			.'cara01bien_tipocapacita="' . $fila['cara01bien_tipocapacita'] . '", '
			.'cara01bien_impvidasalud="' . $fila['cara01bien_impvidasalud'] . '", '
			.'cara01bien_estraautocuid="' . $fila['cara01bien_estraautocuid'] . '", '
			.'cara01bien_pv_personal="' . $fila['cara01bien_pv_personal'] . '", '
			.'cara01bien_pv_familiar="' . $fila['cara01bien_pv_familiar'] . '", '
			.'cara01bien_pv_academ="' . $fila['cara01bien_pv_academ'] . '", '
			.'cara01bien_pv_labora="' . $fila['cara01bien_pv_labora'] . '", '
			.'cara01bien_pv_pareja="' . $fila['cara01bien_pv_pareja'] . '", '
			.'cara01bien_amb="' . $fila['cara01bien_amb'] . '", '
			.'cara01bien_amb_agu="' . $fila['cara01bien_amb_agu'] . '", '
			.'cara01bien_amb_bom="' . $fila['cara01bien_amb_bom'] . '", '
			.'cara01bien_amb_car="' . $fila['cara01bien_amb_car'] . '", '
			.'cara01bien_amb_info="' . $fila['cara01bien_amb_info'] . '", '
			.'cara01bien_amb_temas="' . $fila['cara01bien_amb_temas'] . '", '
			.'cara01psico_costoemocion="' . $fila['cara01psico_costoemocion'] . '", '
			.'cara01psico_reaccionimpre="' . $fila['cara01psico_reaccionimpre'] . '", '
			.'cara01psico_estres="' . $fila['cara01psico_estres'] . '", '
			.'cara01psico_pocotiempo="' . $fila['cara01psico_pocotiempo'] . '", '
			.'cara01psico_actitudvida="' . $fila['cara01psico_actitudvida'] . '", '
			.'cara01psico_duda="' . $fila['cara01psico_duda'] . '", '
			.'cara01psico_problemapers="' . $fila['cara01psico_problemapers'] . '", '
			.'cara01psico_satisfaccion="' . $fila['cara01psico_satisfaccion'] . '", '
			.'cara01psico_discusiones="' . $fila['cara01psico_discusiones'] . '", '
			.'cara01psico_atencion="' . $fila['cara01psico_atencion'] . '", '
			.'cara01telefono1="' . $fila['cara01telefono1'] . '", '
			.'cara01telefono2="' . $fila['cara01telefono2'] . '", '
			.'cara01correopersonal="' . $fila['cara01correopersonal'] . '", '
			.'cara01perayuda="' . $fila['cara01perayuda'] . '", '
			.'cara01perotraayuda="' . $fila['cara01perotraayuda'] . '", '
			.'cara01discsensorialotra="' . $fila['cara01discsensorialotra'] . '", '
			.'cara01discfisicaotra="' . $fila['cara01discfisicaotra'] . '", '
			.'cara01disccognitivaotra="' . $fila['cara01disccognitivaotra'] . '", '
			.'cara01discv2sensorial="' . $fila['cara01discv2sensorial'] . '", '
			.'cara02discv2intelectura="' . $fila['cara02discv2intelectura'] . '", '
			.'cara02discv2fisica="' . $fila['cara02discv2fisica'] . '", '
			.'cara02discv2psico="' . $fila['cara02discv2psico'] . '", '
			.'cara02discv2sistemica="' . $fila['cara02discv2sistemica'] . '", '
			.'cara02discv2sistemicaotro="' . $fila['cara02discv2sistemicaotro'] . '", '
			.'cara02discv2multiple="' . $fila['cara02discv2multiple'] . '", '
			.'cara02discv2multipleotro="' . $fila['cara02discv2multipleotro'] . '", '
			.'cara02talentoexcepcional="' . $fila['cara02talentoexcepcional'] . '", '
			.'cara01discv2tiene="' . $fila['cara01discv2tiene'] . '", '
			.'cara01discv2trastaprende="' . $fila['cara01discv2trastaprende'] . '", '
			.'cara01discv2soporteorigen="' . $fila['cara01discv2soporteorigen'] . '", '
			.'cara01discv2archivoorigen="' . $fila['cara01discv2archivoorigen'] . '", '
			.'cara01discv2trastornos="' . $fila['cara01discv2trastornos'] . '", '
			.'cara01discv2contalento="' . $fila['cara01discv2contalento'] . '", '
			.'cara01discv2condicionmedica="' . $fila['cara01discv2condicionmedica'] . '", '
			.'cara01discv2condmeddet="' . $fila['cara01discv2condmeddet'] . '", '
			.'cara01discv2pruebacoeficiente="' . $fila['cara01discv2pruebacoeficiente'] . '" '
			.'WHERE cara01id=' . $idNuevaEncuesta . '';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' Precargue 2301 ' . $sSQL . '<br>';
		}
		$result = $objDB->ejecutasql($sSQL);
		if ($result == false) {
			$sError = $ERR['falla_guardar'] . ' [2301] ..<!-- ' . $sSQL . ' -->';
		} else {
		}
		$sSQL = 'SELECT * FROM cara44encuesta WHERE cara44id=' . $idUltimaEncuesta . '';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' <b>Precargue de las preguntas complementarias</b>: ' . $sSQL . '<br>';
		}
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			$sSQL = 'UPDATE cara44encuesta SET '
				.'cara44campesinado="' . $fila['cara44campesinado'] . '", '
				.'cara44sexoversion="' . $fila['cara44sexoversion'] . '", '
				.'cara44sexov1identidadgen="' . $fila['cara44sexov1identidadgen'] . '", '
				.'cara44sexov1orientasexo="' . $fila['cara44sexov1orientasexo'] . '", '
				.'cara44bienversion="' . $fila['cara44bienversion'] . '", '
				.'cara44bienv2altoren="' . $fila['cara44bienv2altoren'] . '", '
				.'cara44bienv2atletismo="' . $fila['cara44bienv2atletismo'] . '", '
				.'cara44bienv2baloncesto="' . $fila['cara44bienv2baloncesto'] . '", '
				.'cara44bienv2futbol="' . $fila['cara44bienv2futbol'] . '", '
				.'cara44bienv2gimnasia="' . $fila['cara44bienv2gimnasia'] . '", '
				.'cara44bienv2natacion="' . $fila['cara44bienv2natacion'] . '", '
				.'cara44bienv2voleibol="' . $fila['cara44bienv2voleibol'] . '", '
				.'cara44bienv2tenis="' . $fila['cara44bienv2tenis'] . '", '
				.'cara44bienv2paralimpico="' . $fila['cara44bienv2paralimpico'] . '", '
				.'cara44bienv2otrodeporte="' . $fila['cara44bienv2otrodeporte'] . '", '
				.'cara44bienv2otrodeportedetalle="' . $fila['cara44bienv2otrodeportedetalle'] . '", '
				.'cara44bienv2activdanza="' . $fila['cara44bienv2activdanza'] . '", '
				.'cara44bienv2activmusica="' . $fila['cara44bienv2activmusica'] . '", '
				.'cara44bienv2activteatro="' . $fila['cara44bienv2activteatro'] . '", '
				.'cara44bienv2activartes="' . $fila['cara44bienv2activartes'] . '", '
				.'cara44bienv2activliteratura="' . $fila['cara44bienv2activliteratura'] . '", '
				.'cara44bienv2activculturalotra="' . $fila['cara44bienv2activculturalotra'] . '", '
				.'cara44bienv2activculturalotradetalle="' . $fila['cara44bienv2activculturalotradetalle'] . '", '
				.'cara44bienv2evenfestfolc="' . $fila['cara44bienv2evenfestfolc'] . '", '
				.'cara44bienv2evenexpoarte="' . $fila['cara44bienv2evenexpoarte'] . '", '
				.'cara44bienv2evenhistarte="' . $fila['cara44bienv2evenhistarte'] . '", '
				.'cara44bienv2evengalfoto="' . $fila['cara44bienv2evengalfoto'] . '", '
				.'cara44bienv2evenliteratura="' . $fila['cara44bienv2evenliteratura'] . '", '
				.'cara44bienv2eventeatro="' . $fila['cara44bienv2eventeatro'] . '", '
				.'cara44bienv2evencine="' . $fila['cara44bienv2evencine'] . '", '
				.'cara44bienv2evenculturalotro="' . $fila['cara44bienv2evenculturalotro'] . '", '
				.'cara44bienv2evenculturalotrodetalle="' . $fila['cara44bienv2evenculturalotrodetalle'] . '", '
				.'cara44bienv2emprendimiento="' . $fila['cara44bienv2emprendimiento'] . '", '
				.'cara44bienv2empresa="' . $fila['cara44bienv2empresa'] . '", '
				.'cara44bienv2emprenrecursos="' . $fila['cara44bienv2emprenrecursos'] . '", '
				.'cara44bienv2emprenconocim="' . $fila['cara44bienv2emprenconocim'] . '", '
				.'cara44bienv2emprenplan="' . $fila['cara44bienv2emprenplan'] . '", '
				.'cara44bienv2emprenejecutar="' . $fila['cara44bienv2emprenejecutar'] . '", '
				.'cara44bienv2emprenfortconocim="' . $fila['cara44bienv2emprenfortconocim'] . '", '
				.'cara44bienv2emprenidentproblema="' . $fila['cara44bienv2emprenidentproblema'] . '", '
				.'cara44bienv2emprenotro="' . $fila['cara44bienv2emprenotro'] . '", '
				.'cara44bienv2emprenotrodetalle="' . $fila['cara44bienv2emprenotrodetalle'] . '", '
				.'cara44bienv2emprenmarketing="' . $fila['cara44bienv2emprenmarketing'] . '", '
				.'cara44bienv2emprenplannegocios="' . $fila['cara44bienv2emprenplannegocios'] . '", '
				.'cara44bienv2emprenideas="' . $fila['cara44bienv2emprenideas'] . '", '
				.'cara44bienv2emprencreacion="' . $fila['cara44bienv2emprencreacion'] . '", '
				.'cara44bienv2saludfacteconom="' . $fila['cara44bienv2saludfacteconom'] . '", '
				.'cara44bienv2saludpreocupacion="' . $fila['cara44bienv2saludpreocupacion'] . '", '
				.'cara44bienv2saludconsumosust="' . $fila['cara44bienv2saludconsumosust'] . '", '
				.'cara44bienv2saludinsomnio="' . $fila['cara44bienv2saludinsomnio'] . '", '
				.'cara44bienv2saludclimalab="' . $fila['cara44bienv2saludclimalab'] . '", '
				.'cara44bienv2saludalimenta="' . $fila['cara44bienv2saludalimenta'] . '", '
				.'cara44bienv2saludemocion="' . $fila['cara44bienv2saludemocion'] . '", '
				.'cara44bienv2saludestado="' . $fila['cara44bienv2saludestado'] . '", '
				.'cara44bienv2saludmedita="' . $fila['cara44bienv2saludmedita'] . '", '
				.'cara44bienv2crecimedusexual="' . $fila['cara44bienv2crecimedusexual'] . '", '
				.'cara44bienv2crecimcultciudad="' . $fila['cara44bienv2crecimcultciudad'] . '", '
				.'cara44bienv2crecimrelpareja="' . $fila['cara44bienv2crecimrelpareja'] . '", '
				.'cara44bienv2crecimrelinterp="' . $fila['cara44bienv2crecimrelinterp'] . '", '
				.'cara44bienv2crecimdinamicafam="' . $fila['cara44bienv2crecimdinamicafam'] . '", '
				.'cara44bienv2crecimautoestima="' . $fila['cara44bienv2crecimautoestima'] . '", '
				.'cara44bienv2creciminclusion="' . $fila['cara44bienv2creciminclusion'] . '", '
				.'cara44bienv2creciminteliemoc="' . $fila['cara44bienv2creciminteliemoc'] . '", '
				.'cara44bienv2crecimcultural="' . $fila['cara44bienv2crecimcultural'] . '", '
				.'cara44bienv2crecimartistico="' . $fila['cara44bienv2crecimartistico'] . '", '
				.'cara44bienv2crecimdeporte="' . $fila['cara44bienv2crecimdeporte'] . '", '
				.'cara44bienv2crecimambiente="' . $fila['cara44bienv2crecimambiente'] . '", '
				.'cara44bienv2crecimhabsocio="' . $fila['cara44bienv2crecimhabsocio'] . '", '
				.'cara44bienv2ambienbasura="' . $fila['cara44bienv2ambienbasura'] . '", '
				.'cara44bienv2ambienreutiliza="' . $fila['cara44bienv2ambienreutiliza'] . '", '
				.'cara44bienv2ambienluces="' . $fila['cara44bienv2ambienluces'] . '", '
				.'cara44bienv2ambienfrutaverd="' . $fila['cara44bienv2ambienfrutaverd'] . '", '
				.'cara44bienv2ambienenchufa="' . $fila['cara44bienv2ambienenchufa'] . '", '
				.'cara44bienv2ambiengrifo="' . $fila['cara44bienv2ambiengrifo'] . '", '
				.'cara44bienv2ambienbicicleta="' . $fila['cara44bienv2ambienbicicleta'] . '", '
				.'cara44bienv2ambientranspub="' . $fila['cara44bienv2ambientranspub'] . '", '
				.'cara44bienv2ambienducha="' . $fila['cara44bienv2ambienducha'] . '", '
				.'cara44bienv2ambiencaminata="' . $fila['cara44bienv2ambiencaminata'] . '", '
				.'cara44bienv2ambiensiembra="' . $fila['cara44bienv2ambiensiembra'] . '", '
				.'cara44bienv2ambienconferencia="' . $fila['cara44bienv2ambienconferencia'] . '", '
				.'cara44bienv2ambienrecicla="' . $fila['cara44bienv2ambienrecicla'] . '", '
				.'cara44bienv2ambienotraactiv="' . $fila['cara44bienv2ambienotraactiv'] . '", '
				.'cara44bienv2ambienotraactivdetalle="' . $fila['cara44bienv2ambienotraactivdetalle'] . '", '
				.'cara44bienv2ambienreforest="' . $fila['cara44bienv2ambienreforest'] . '", '
				.'cara44bienv2ambienmovilidad="' . $fila['cara44bienv2ambienmovilidad'] . '", '
				.'cara44bienv2ambienclimatico="' . $fila['cara44bienv2ambienclimatico'] . '", '
				.'cara44bienv2ambienecofemin="' . $fila['cara44bienv2ambienecofemin'] . '", '
				.'cara44bienv2ambienbiodiver="' . $fila['cara44bienv2ambienbiodiver'] . '", '
				.'cara44bienv2ambienecologia="' . $fila['cara44bienv2ambienecologia'] . '", '
				.'cara44bienv2ambieneconomia="' . $fila['cara44bienv2ambieneconomia'] . '", '
				.'cara44bienv2ambienrecnatura="' . $fila['cara44bienv2ambienrecnatura'] . '", '
				.'cara44bienv2ambienreciclaje="' . $fila['cara44bienv2ambienreciclaje'] . '", '
				.'cara44bienv2ambienmascota="' . $fila['cara44bienv2ambienmascota'] . '", '
				.'cara44bienv2ambiencartohum="' . $fila['cara44bienv2ambiencartohum'] . '", '
				.'cara44bienv2ambienespiritu="' . $fila['cara44bienv2ambienespiritu'] . '", '
				.'cara44bienv2ambiencarga="' . $fila['cara44bienv2ambiencarga'] . '", '
				.'cara44bienv2ambienotroenfoq="' . $fila['cara44bienv2ambienotroenfoq'] . '", '
				.'cara44bienv2ambienotroenfoqdetalle="' . $fila['cara44bienv2ambienotroenfoqdetalle'] . '", '
				.'cara44fam_madrecabeza="' . $fila['cara44fam_madrecabeza'] . '", '
				.'cara44acadhatenidorecesos="' . $fila['cara44acadhatenidorecesos'] . '", '
				.'cara44acadrazonreceso="' . $fila['cara44acadrazonreceso'] . '", '
				.'cara44acadrazonrecesodetalle="' . $fila['cara44acadrazonrecesodetalle'] . '", '
				.'cara44campus_usocorreounad="' . $fila['cara44campus_usocorreounad'] . '", '
				.'cara44campus_usocorreounadno="' . $fila['cara44campus_usocorreounadno'] . '", '
				.'cara44campus_usocorreounadnodetalle="' . $fila['cara44campus_usocorreounadnodetalle'] . '", '
				.'cara44campus_medioactivunad="' . $fila['cara44campus_medioactivunad'] . '", '
				.'cara44campus_medioactivunaddetalle="' . $fila['cara44campus_medioactivunaddetalle'] . '" '
				.'WHERE cara44id=' . $idNuevaEncuesta . '';
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . ' Precargue 2344 ' . $sSQL . '<br>';
			}
			$result = $objDB->ejecutasql($sSQL);
			if ($result == false) {
				$sError = $ERR['falla_guardar'] . ' [2344] ..<!-- ' . $sSQL . ' -->';
			} else {
			}
		}
	}
}
?>
