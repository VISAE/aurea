<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2016 - 2018 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- jueves, 7 de julio de 2016
--- El proposito de esta libreria es unificar el manejo de datos comunes.... quitandoselo a la unad_librerias.
*/
function debug_Cronometrado($sMensaje, $iSegIni)
{
	$iSegFin = microtime(true);
	$iSegundos = $iSegFin - $iSegIni;
	$sDato = fecha_microtiempo() . '[Tiempo: ' . $iSegundos . '] ' . $sMensaje . '<br>';
	return array($sDato, $iSegFin);
}
// -- Datos locales
function Id_Unadito()
{
	$iRes = 356770;
	switch (Traer_Entidad()) {
		case 1:
			$iRes = 115; // Unad Florida
			break;
	}
	return $iRes;
}
// -- Id_PeriodoBase
function Id_PeriodoInicioC2()
{
	$res = 0;
	$idEntidad = Traer_Entidad();
	switch ($idEntidad) {
		case 0:
			$res = 760;
			break;
	}
	return $res;
}
//
function f101_SiglaModulo($idSistema, $objDB)
{
	$sRes = 'AUREA';
	$sSQL = 'SELECT unad01nombre FROM unad01sistema WHERE unad01id=' . $idSistema . '';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$sRes = $fila['unad01nombre'];
	}
	return $sRes;
}
function f105_Aplicaciones($idTercero, $objDB)
{
	$sLista = '-99';
	//, unad07fechavence
	$sSQL = 'SELECT T5.unad05aplicativo FROM unad07usuarios AS TB, unad05perfiles AS T5 WHERE TB.unad07idtercero=' . $idTercero . ' AND TB.unad07idperfil=T5.unad05id AND TB.unad07vigente="S" AND T5.unad05aplicativo=-1';
	$tabla07 = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla07) > 0) {
		$sSQL = 'SELECT unad01id FROM unad01sistema WHERE unad01publico="S"';
		$tabla07 = $objDB->ejecutasql($sSQL);
		while ($fila07 = $objDB->sf($tabla07)) {
			$sLista = $sLista . ',' . $fila07['unad01id'];
		}
	} else {
		$sSQL = 'SELECT T5.unad05aplicativo FROM unad07usuarios AS TB, unad05perfiles AS T5 WHERE TB.unad07idtercero=' . $idTercero . ' AND TB.unad07idperfil=T5.unad05id AND TB.unad07vigente="S" AND T5.unad05aplicativo>0 GROUP BY T5.unad05aplicativo';
		$tabla07 = $objDB->ejecutasql($sSQL);
		while ($fila07 = $objDB->sf($tabla07)) {
			$sLista = $sLista . ',' . $fila07['unad05aplicativo'];
		}
	}
	return $sLista;
}
// Devuelve la ubicación del usuario logeado.
function f107_UbicacionUsuario($objDB, $bDebug = false)
{
	$iLat = 0;
	$sLat = '';
	$iLong = 0;
	$sLong = '';
	$iProximidad = 0;
	$sError = '';
	$idSesion = 0;
	if (isset($_SESSION['unad_id_sesion']) != 0) {
		$idSesion = $_SESSION['unad_id_sesion'];
	}
	if ($sError == '') {
		$iAgnoMes = fecha_AgnoMes();
		$sTabla71 = 'unad71sesion' . $iAgnoMes;
		$sSQL = 'SELECT unad71latgrados, unad71latdecimas, unad71longrados, unad71longdecimas, unad71proximidad
		FROM ' . $sTabla71 . '
		WHERE unad71id=' . $idSesion . '';
		$tabla71 = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla71) > 0) {
			$fila71 = $objDB->sf($tabla71);
			$iLat = $fila71['unad71latgrados'];
			$sLat = $fila71['unad71latdecimas'];
			$iLong = $fila71['unad71longrados'];
			$sLong = $fila71['unad71longdecimas'];
			$iProximidad = $fila71['unad71proximidad'];
		}
	}
	return array($iLat, $sLat, $iLong, $sLong, $iProximidad);
}
//
function f107_VerificarPerfiles($idTercero, $idPeriodo, $objDB, $bDebug = false, $bConInfo = false)
{
	//Marzo 3 de 2021 - Se reconstruye esta funcion para que no se sobrepongan los permisos, 
	//entonces ya no se hacen por modulo sino que se hagan globales.
	$sError = '';
	$sDebug = '';
	$sInfo = '';
	$iHoy = fecha_DiaMod();
	$bMuestraInfo = $bDebug || $bConInfo;
	//Primero cargamos todos los perfiles reservados y le decimos que no estan activos.
	$aLista = array();
	$aPerfil = array();
	$iPerfiles = 0;
	$sSQL = 'SELECT unad05id, unad05nombre FROM unad05perfiles WHERE unad05reservado="S"';
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' Perfiles reservados [Todos los aplicativos] ' . $sSQL . '<br>';
	}
	$tabla = $objDB->ejecutasql($sSQL);
	while ($fila = $objDB->sf($tabla)) {
		$iPerfiles++;
		$id05 = $fila['unad05id'];
		$aLista[$iPerfiles] = $id05;
		$aPerfil[$id05] = 0;
	}
	$iAdicionales = $iPerfiles;
	//Vamos activando segun la necesidad.
	//Bitacora
	if (true) {
		$sSQL = 'SELECT TB.bita27idperfil 
		FROM bita27equipotrabajo AS TB, bita28eqipoparte AS T2 
		WHERE TB.bita27idperfil>0 AND TB.bita27id=T2.bita28idequipotrab AND T2.bita28idtercero=' . $idTercero . ' AND T2.bita28activo="S"';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' Grupos de trabajo en BITACORA: ' . $sSQL . '<br>';
		}
		$tabla = $objDB->ejecutasql($sSQL);
		while ($fila = $objDB->sf($tabla)) {
			$aPerfil[$fila['bita27idperfil']] = 1;
		}
	}
	// 16 - CCOR 
	if (true) {
		$sCondi = array();
		$sCondi[1] = 'SELECT 1 FROM unad24sede WHERE unad24ccor_admin=' . $idTercero . ' AND unad24activa="S"';
		$sCondi[2] = 'SELECT 1 FROM ccor07actores WHERE ccor07idtercero=' . $idTercero . ' AND ccor07activo="S" AND ccor07nivel=1';
		$sCondi[3] = 'SELECT 1 FROM ccor07actores WHERE ccor07idtercero=' . $idTercero . ' AND ccor07activo="S" AND ccor07nivel=0';
		$iTotalPerfiles = 3;
		$sSQL = 'SELECT * FROM ccor06params WHERE ccor06id=1';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' Configuraci&oacute;n de perfiles de CONSECUTIVOS ' . $sSQL . '<br>';
		}
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			$aCFG[1] = $fila['ccor06idperfilsupervisor'];
			$aCFG[2] = $fila['ccor06idperfilsupervisor'];
			$aCFG[3] = $fila['ccor06idperfilusuario'];
		} else {
			$iTotalPerfiles = 0;
		}
		for ($j = 1; $j <= $iTotalPerfiles; $j++) {
			$tabla = $objDB->ejecutasql($sCondi[$j]);
			if ($objDB->nf($tabla) > 0) {
				$id05 = $aCFG[$j];
				if ($id05 != 0) {
					$aPerfil[$id05] = 1;
				}
			}
		}
	}
	//OAI
	if (true) {
		//Traer la configuracion
		$aAplican = array(0, 0, 0, 0, 0, 0, 0, 0, 0);
		//Para cada tipo de rol hay una configuracion.
		if ((int)$idPeriodo == 0) {
			$sCondi = 'ofer01id=1';
		} else {
			$sCondi = 'ofer01per_aca=' . $idPeriodo . '';
		}
		$sSQL = 'SELECT ofer01perfiladmin, ofer01perfilcoordinador, ofer01perfildecano, ofer01perfildirector, ofer01perfilrevisor, 
		ofer01perfilacreditador, ofer01per_aca 
		FROM ofer01params 
		WHERE ' . $sCondi;
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' Configuracion de roles [OAI] ' . $sSQL . '<br>';
		}
		$tperfiles = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tperfiles) > 0) {
			$fP = $objDB->sf($tperfiles);
			$idPeriodo = $fP['ofer01per_aca'];
			$aAplican = array(
				0,
				$fP['ofer01perfiladmin'],
				$fP['ofer01perfilcoordinador'],
				$fP['ofer01perfildecano'],
				$fP['ofer01perfilrevisor'],
				$fP['ofer01perfilacreditador'],
				0,
				0,
				$fP['ofer01perfildirector']
			);
		} else {
			$sError = 'Se ha modificado el periodo acad&eacute;mico, no es posible procesar la solicitud {Periodo solicitado ' . $idPeriodo . '}';
			$sError = '..';
		}
		//Ahora si aplicar
		if ($sError == '') {
			$sSQL = 'SELECT T1.ofer10claserol 
			FROM ofer11actores AS TB, ofer10rol AS T1, exte02per_aca AS T2 
			WHERE TB.ofer11idtercero=' . $idTercero . ' AND TB.ofer11per_aca=T2.exte02id AND T2.exte02vigente="S" AND TB.ofer11idrol=ofer10id AND T1.ofer10claserol IN (1, 2, 3, 4, 5, 8) 
			GROUP BY T1.ofer10claserol';
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . ' Verificando roles OAI: ' . $sSQL . '<br>';
			}
			$tabla = $objDB->ejecutasql($sSQL);
			while ($fila = $objDB->sf($tabla)) {
				$id05 = $aAplican[$fila['ofer10claserol']];
				if ($id05 != 0) {
					if (isset($aPerfil[$id05]) == 0) {
						$iAdicionales++;
						$aLista[$iAdicionales] = $id05;
					}
					$aPerfil[$id05] = 1;
				}
			}
		}
		if ($sError == '..') {
			$sError = '';
		}
		//Ahora los coordinadores.
		if ((int)$idPeriodo != 0) {
			$id05 = $aAplican[2];
			if ($id05 != 0) {
				$sSQL = 'SELECT 1 
				FROM ofer31programacoordinador 
				WHERE ofer31per_aca=' . $idPeriodo . ' AND ofer31idcoordinador=' . $idTercero . '';
				$tabla = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($tabla) > 0) {
					$aPerfil[$id05] = 1;
				}
			}
		}
	}
	//CORE 
	if (true) {
		$sCondi = array();
		$sCondi[1] = 'SELECT 1 FROM core12escuela WHERE core12iddecano=' . $idTercero . '';
		$sCondi[2] = 'SELECT 1 FROM core12escuela WHERE core12idadministrador=' . $idTercero . '';
		$sCondi[3] = 'SELECT 1 FROM core09programa WHERE core09iddirector=' . $idTercero . '';
		//$sCondi[4] = 'SELECT 1 FROM core19tutores WHERE core19idtercero=' . $idTercero . ' AND core19activo="S"';
		$sCondi[4] = 'SELECT 1 FROM core12escuela WHERE core12idrespcursocomun=' . $idTercero . '';
		$sCondi[5] = 'SELECT 1 FROM unad23zona WHERE unad23director=' . $idTercero . '';
		$sCondi[6] = 'SELECT 1 FROM unad24sede WHERE unad24director=' . $idTercero . ' AND unad24activa="S"';
		$sCondi[7] = 'SELECT 1 FROM corf57comiteescuela WHERE corf57idcoordinador=' . $idTercero . '';
		$sCondi[8] = 'SELECT 1 FROM corf65programaapoyo WHERE corf65idtercero=' . $idTercero . ' AND corf65fechaini<=' . $iHoy . ' AND ((corf65fechafin=0) OR (corf65fechafin>=' . $iHoy . '))';
		$sCondi[9] = 'SELECT 1 FROM unad23zona WHERE unad23administrador=' . $idTercero . '';
		$iTotalPerfiles = 9;
		$sSQL = 'SELECT core00idperfildecano, core00idperfiladminescuela, core00idperfilliderprog, core00idperfiltutor, 
		core00idperfilcursoscomunes, core00idperfildirzona, core00idperfildircentro, core00idperfiladmincomite, 
		core00idperfil_apoyoprog, core00idperfil_zona_admin 
		FROM core00params 
		WHERE core00id=1';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' Configuraci&oacute;n de perfiles de CORE ' . $sSQL . '<br>';
		}
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			$aCFG[1] = $fila['core00idperfildecano'];
			$aCFG[2] = $fila['core00idperfiladminescuela'];
			$aCFG[3] = $fila['core00idperfilliderprog'];
			$aCFG[4] = $fila['core00idperfilcursoscomunes'];
			$aCFG[5] = $fila['core00idperfildirzona'];
			$aCFG[6] = $fila['core00idperfildircentro'];
			$aCFG[7] = $fila['core00idperfiladmincomite'];
			$aCFG[8] = $fila['core00idperfil_apoyoprog'];
			$aCFG[9] = $fila['core00idperfil_zona_admin'];
		} else {
			$iTotalPerfiles = 0;
		}
		for ($j = 1; $j <= $iTotalPerfiles; $j++) {
			$tabla = $objDB->ejecutasql($sCondi[$j]);
			if ($objDB->nf($tabla) > 0) {
				$id05 = $aCFG[$j];
				if ($id05 != 0) {
					$aPerfil[$id05] = 1;
				}
			}
		}
		//Ahora los espejos (Pueden ser varios perfiles)
		$sSQL = 'SELECT T2.core27idperfil 
		FROM core26espejos AS TB, core27tipoespejo AS T2 
		WHERE TB.core26idtercero=' . $idTercero . ' AND TB.core26vigente="S" 
		AND TB.core26idtipoespejo=T2.core27id AND T2.core27vigente="S" AND T2.core27idperfil>0';
		$tabla = $objDB->ejecutasql($sSQL);
		while ($fila = $objDB->sf($tabla)) {
			$aPerfil[$fila['core27idperfil']] = 1;
		}
		// Ahora los miembros de comites (igual varios perfiles)
		$sSQL = 'SELECT T55.cirf55idperfil
		FROM corf58integrantes AS TB, corf57comiteescuela AS T57, corf55comites AS T55
		WHERE TB.corf59idtercero=' . $idTercero . ' AND TB.corf59activo>0 AND TB.corf59idcomiteesc=T57.corf57id 
		AND T57.corf57idtipocomite=T55.corf55id AND T55.cirf55idperfil>0
		GROUP BY T55.cirf55idperfil';
		$tabla = $objDB->ejecutasql($sSQL);
		while ($fila = $objDB->sf($tabla)) {
			$aPerfil[$fila['cirf55idperfil']] = 1;
		}
	}
	//C2
	if (true) {
		$sCondi[1] = 'SELECT 1 FROM ofer08oferta WHERE ofer08idacomanamento=' . $idTercero . ' AND ofer08estadooferta=1';
		$sCondi[2] = 'SELECT 1 FROM core19tutores WHERE core19idtercero=' . $idTercero . ' AND core19activo="S"';
		$sCondi[3] = 'SELECT 1 FROM corf12directores WHERE corf12idtercero=' . $idTercero . ' AND corf12activo<>0';
		$iTotalPerfiles = 3;
		$sSQL = 'SELECT ceca00idperfildirector, ceca00idperfiltutor FROM core00params WHERE core00id=1';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' Configuraci&oacute;n de perfiles de CORE ' . $sSQL . '<br>';
		}
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			$aCFG[1] = $fila['ceca00idperfildirector'];
			$aCFG[2] = $fila['ceca00idperfiltutor'];
			$aCFG[3] = $aCFG[1];
		} else {
			$iTotalPerfiles = 0;
		}
		for ($j = 1; $j <= $iTotalPerfiles; $j++) {
			$tabla = $objDB->ejecutasql($sCondi[$j]);
			if ($objDB->nf($tabla) > 0) {
				$id05 = $aCFG[$j];
				if ($id05 != 0) {
					$aPerfil[$id05] = 1;
				}
			}
		}
	}
	//Grados
	if (true) {
		$sCondi[1] = 'SELECT 1 FROM grad28comitezonal WHERE grad28idtercero=' . $idTercero . ' AND grad28vigente<>0';
		$sCondi[2] = 'SELECT 1 FROM grad29comiteescuela WHERE grad29idtercero=' . $idTercero . ' AND grad29vigente<>0';
		$sCondi[3] = 'SELECT 1 FROM grad30comiteprograma WHERE grad30idtercero=' . $idTercero . ' AND grad30vigente<>0';
		// Revisor
		$sCondi[4] = 'SELECT 1 FROM grad11proyecto WHERE grad11idrevisor=' . $idTercero . ' AND grad11estado > 5 AND grad11estado < 20';
		// Director
		$sCondi[5] = 'SELECT 1 FROM grad11proyecto WHERE grad11director=' . $idTercero . ' AND grad11estado > 20 AND grad11estado <= 90';
		// Jurado
		$sCondi[6] = 'SELECT 1 FROM grad11proyecto WHERE (grad11idjurado1=' . $idTercero . ' OR grad11idjurado2=' . $idTercero . ') AND grad11estado IN (40, 50, 55, 60, 65)';
		// Coevaluador
		$sCondi[7] = 'SELECT 1 FROM grad11proyecto WHERE (grad11idevaluador1=' . $idTercero . ' OR grad11idevaluador2=' . $idTercero . ') AND grad11estado IN (40, 50, 55, 60, 65)';
		$iTotalPerfiles = 7;
		$sSQL = 'SELECT grad00perfilcomiteinvestzonal, 
		grad00perfilcomiteinvestescuela, grad00perfilcomitecurricularprog, grad00perfilrevisor, grad00perfildirector, 
		grad00perfiljurado, grad00perfilcoevaluador FROM grad00params WHERE grad00id=1';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' Configuraci&oacute;n de perfiles de CORE ' . $sSQL . '<br>';
		}
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			$aCFG[1] = $fila['grad00perfilcomiteinvestzonal'];
			$aCFG[2] = $fila['grad00perfilcomiteinvestescuela'];
			$aCFG[3] = $fila['grad00perfilcomitecurricularprog'];
			$aCFG[4] = $fila['grad00perfilrevisor'];
			$aCFG[5] = $fila['grad00perfildirector'];
			$aCFG[6] = $fila['grad00perfiljurado'];
			$aCFG[7] = $fila['grad00perfilcoevaluador'];
		} else {
			$iTotalPerfiles = 0;
		}
		for ($j = 1; $j <= $iTotalPerfiles; $j++) {
			$tabla = $objDB->ejecutasql($sCondi[$j]);
			if ($objDB->nf($tabla) > 0) {
				$id05 = $aCFG[$j];
				if ($id05 != 0) {
					$aPerfil[$id05] = 1;
				}
			}
		}
	}
	//SAI
	if (true) {
		$sCondi = array();
		$sCondi[1] = 'SELECT 1 FROM bita27equipotrabajo WHERE ((bita27idlider=' . $idTercero . ') OR (bita27propietario=' . $idTercero . ')) AND bita27activo=1';
		$iTotalPerfiles = 1;
		$sSQL = 'SELECT saiu00perfillider FROM saiu00config WHERE saiu00id=1';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' Configuraci&oacute;n de perfiles de SAI ' . $sSQL . '<br>';
		}
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			$aCFG[1] = $fila['saiu00perfillider'];
		} else {
			$iTotalPerfiles = 0;
		}
		for ($j = 1; $j <= $iTotalPerfiles; $j++) {
			$tabla = $objDB->ejecutasql($sCondi[$j]);
			if ($objDB->nf($tabla) > 0) {
				$id05 = $aCFG[$j];
				if ($id05 != 0) {
					$aPerfil[$id05] = 1;
				}
			}
		}
	}
	//SC
	if (true) {
		$sCondi[1] = 'SELECT 1 FROM cara13consejeros WHERE cara13idconsejero=' . $idTercero . ' AND cara13activo="S" AND ((cara01fechafin=0) OR (cara01fechafin>=' . $iHoy . '))';
		$sCondi[2] = 'SELECT 1 FROM cara21lidereszona WHERE cara21idlider=' . $idTercero . ' AND cara21activo="S"';
		$iTotalPerfiles = 2;
		$sSQL = 'SELECT cara00idperfilconsejero, cara00idperfillider FROM cara00config WHERE cara00id=1';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			$aCFG[1] = $fila['cara00idperfilconsejero'];
			$aCFG[2] = $fila['cara00idperfillider'];
		} else {
			$iTotalPerfiles = 0;
		}
		for ($j = 1; $j <= $iTotalPerfiles; $j++) {
			$tabla = $objDB->ejecutasql($sCondi[$j]);
			if ($objDB->nf($tabla) > 0) {
				$id05 = $aCFG[$j];
				if ($id05 != 0) {
					$aPerfil[$id05] = 1;
				}
			}
		}
	}
	//Practicas
	if (true) {
		$sCondi[1] = 'SELECT 1 FROM olab41tipopractica WHERE olab41idlider=' . $idTercero . ' AND olab41activa="S"';
		$sCondi[2] = 'SELECT 1 FROM olab45practica WHERE olab45idtutor=' . $idTercero . '';
		$iTotalPerfiles = 2;
		$sSQL = 'SELECT prac00idperfillider, prac00idperfiltutor FROM prac00parametros WHERE prac00id=1';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' Configuraci&oacute;n de perfiles de PRACTICAS ' . $sSQL . '<br>';
		}
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			$aCFG[1] = $fila['prac00idperfillider'];
			$aCFG[2] = $fila['prac00idperfiltutor'];
		} else {
			$iTotalPerfiles = 0;
		}
		for ($j = 1; $j <= $iTotalPerfiles; $j++) {
			$tabla = $objDB->ejecutasql($sCondi[$j]);
			if ($objDB->nf($tabla) > 0) {
				$id05 = $aCFG[$j];
				if ($id05 != 0) {
					$aPerfil[$id05] = 1;
				}
			}
		}
	}
	//OIL
	if (true) {
		$sCondi[1] = 'SELECT 1 FROM olab08oferta AS TB, exte02per_aca AS T2 WHERE TB.olab08idresponsable=' . $idTercero . ' AND TB.olab08cerrado="S" AND TB.olab08idtipooferta=0 AND TB.olab08idperaca=T2.exte02id AND T2.exte02vigente="S" LIMIT 0,1';
		$sCondi[2] = 'SELECT 1 FROM olab08oferta AS TB, exte02per_aca AS T2 WHERE TB.olab08idresponsable=' . $idTercero . ' AND TB.olab08cerrado="S" AND TB.olab08idtipooferta=1 AND TB.olab08idperaca=T2.exte02id AND T2.exte02vigente="S" LIMIT 0,1';
		$sCondi[3] = 'SELECT 1 FROM olab08oferta AS TB, exte02per_aca AS T2 WHERE TB.olab08idresponsable=' . $idTercero . ' AND TB.olab08cerrado="S" AND TB.olab08idtipooferta=2 AND TB.olab08idperaca=T2.exte02id AND T2.exte02vigente="S" LIMIT 0,1';
		$sCondi[4] = 'SELECT 1 FROM olab37tutores AS TB, exte02per_aca AS T2 WHERE TB.olab37idtutor=' . $idTercero . ' AND TB.olab37idproceso=0 AND TB.olab37idperaca=T2.exte02id AND T2.exte02vigente="S" LIMIT 0,1';
		$sCondi[5] = 'SELECT 1 FROM olab37tutores AS TB, exte02per_aca AS T2 WHERE TB.olab37idtutor=' . $idTercero . ' AND TB.olab37idproceso=1 AND TB.olab37idperaca=T2.exte02id AND T2.exte02vigente="S" LIMIT 0,1';
		$sCondi[6] = 'SELECT 1 FROM olab37tutores AS TB, exte02per_aca AS T2 WHERE TB.olab37idtutor=' . $idTercero . ' AND TB.olab37idproceso=2 AND TB.olab37idperaca=T2.exte02id AND T2.exte02vigente="S" LIMIT 0,1';
		$sCondi[7] = 'SELECT 1 FROM olab74ofertasimul WHERE olab74idresponsable=' . $idTercero . ' AND (olab74fechacierre=0 OR olab74fechacierre>' . $iHoy . ')';
		$iTotalPerfiles = 7;
		$sSQL = 'SELECT olab07labperfiltutor, olab07salperfiltutor, olab07bleperfiltutor, olab07sim_docente 
		FROM olab07config 
		WHERE olab07id=1';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' Configuraci&oacute;n de perfiles de OIL ' . $sSQL . '<br>';
		}
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			$aCFG[1] = $fila['olab07labperfiltutor'];
			$aCFG[2] = $fila['olab07salperfiltutor'];
			$aCFG[3] = $fila['olab07bleperfiltutor'];
			$aCFG[4] = $fila['olab07labperfiltutor'];
			$aCFG[5] = $fila['olab07salperfiltutor'];
			$aCFG[6] = $fila['olab07bleperfiltutor'];
			$aCFG[7] = $fila['olab07sim_docente'];
		} else {
			$iTotalPerfiles = 0;
		}
		for ($j = 1; $j <= $iTotalPerfiles; $j++) {
			$id05 = $aCFG[$j];
			if ($id05 != 0) {
				$tabla = $objDB->ejecutasql($sCondi[$j]);
				if ($objDB->nf($tabla) > 0) {
					$aPerfil[$id05] = 1;
				}
			}
		}
	}
	// 36 - SIGMA
	if (true) {
		$sCondi = array();
		$sCondi[1] = 'SELECT 1 FROM plan04proyecto WHERE plan04idadmin=' . $idTercero . '';
		$sCondi[2] = 'SELECT 1 FROM plan04proyecto WHERE plan04idresponsable=' . $idTercero . '';
		//$sCondi[3] = 'SELECT 1 FROM plan12planproymetavr WHERE ((plan12idresponsable=' . $idTercero . ') OR (plan12idnuevoresp=' . $idTercero . '))';
		$sCondi[3] = 'SELECT 1 FROM plan07proymeta WHERE plan07idresponsable=' . $idTercero . '';
		$sCondi[4] = 'SELECT 1 FROM plan14metaprod WHERE plan14idgestor=' . $idTercero . '';
		$sCondi[5] = 'SELECT 1 FROM plan16metaprodcierre WHERE plan16idgestor=' . $idTercero . '';
		//$sCondi[4] = 'SELECT 1 FROM plan15metaactividad WHERE plan15idgestor=' . $idTercero . '';
		$iTotalPerfiles = 5;
		$sSQL = 'SELECT * FROM plan00config WHERE plan00id=1';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' Configuraci&oacute;n de perfiles de SIGMA 2.0 ' . $sSQL . '<br>';
		}
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			$aCFG[1] = $fila['plan00idperfiladmproy'];
			$aCFG[2] = $fila['plan00idperfilrespproy'];
			$aCFG[3] = $fila['plan00idperfilmetaprev'];
			$aCFG[4] = $fila['plan00idcoordevidencia']; // Responsable de producto
			$aCFG[5] = $fila['plan00idperfilevidencia'];
		} else {
			$iTotalPerfiles = 0;
		}
		/* 
		plan00idperfiladmproy
		plan00idperfilrespproy
		plan00idperfilmetaprev
		plan00idcoordevidencia
		plan00idperfilevidencia
		*/
		for ($j = 1; $j <= $iTotalPerfiles; $j++) {
			$tabla = $objDB->ejecutasql($sCondi[$j]);
			if ($objDB->nf($tabla) > 0) {
				$id05 = $aCFG[$j];
				if ($id05 != 0) {
					$aPerfil[$id05] = 1;
				}
			}
		}
	}
	// 41 - CONTRATACION
	if (true) {
		$sCondi = array();
		// unae26idresponsable (se asigna por permiso de la GAF)
		$sCondi[1] = 'SELECT 1 FROM cttc05oficina WHERE cttc05idcoordinador=' . $idTercero . ' AND cttc05activa>0';
		//Solo despues de la tarea 106 es que queda en firme como supervisor.
		$sCondi[2] = 'SELECT 1 FROM cttc07proceso WHERE cttc07e2_idsupervisor=' . $idTercero . ' AND cttc07idtarea>106';
		$sCondi[3] = 'SELECT 1 FROM cttc07proceso WHERE cttc07interventor=' . $idTercero . '';
		$sCondi[4] = 'SELECT 1 FROM cttc07proceso WHERE cttc07e2_contratista=' . $idTercero . '';
		$sCondi[5] = 'SELECT 1 FROM cttc30unidadcttc WHERE cttc30idtercero=' . $idTercero . ' AND (cttc30fechaing<=' . $iHoy . ') AND (cttc30fecharet=0 OR cttc30fecharet>=' . $iHoy . ')';
		$sCondi[6] = 'SELECT 1 FROM unae26unidadesfun WHERE (unae26idresponsable=' . $idTercero . ') AND unae26idzona=0';
		$sCondi[7] = 'SELECT 1 FROM plan16metaprodcierre WHERE plan16idgestor=' . $idTercero . '';
		$iTotalPerfiles = 5;
		$sSQL = 'SELECT * FROM cttc00params WHERE cttc00id=1';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' Configuraci&oacute;n de perfiles de CONTRATACION ' . $sSQL . '<br>';
		}
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			$aCFG[1] = $fila['cttc00perfil_coordunidad'];
			$aCFG[2] = $fila['cttc00perfil_supervisor'];
			$aCFG[3] = $fila['cttc00perfil_interventor'];
			$aCFG[4] = $fila['cttc00perfil_contratista'];
			$aCFG[5] = $fila['cttc00perfil_unidadcoord'];
			$aCFG[6] = $aCFG[5];
			$aCFG[7] = $fila['cttc00perfil_avales'];
		} else {
			$iTotalPerfiles = 0;
		}
		for ($j = 1; $j <= $iTotalPerfiles; $j++) {
			$tabla = $objDB->ejecutasql($sCondi[$j]);
			if ($objDB->nf($tabla) > 0) {
				$id05 = $aCFG[$j];
				if ($id05 != 0) {
					$aPerfil[$id05] = 1;
				}
			}
		}
	}
	// GAF - Comunes
	if (true) {
		$sCondi = array();
		$sCondi[1] = 'SELECT 1 FROM unae26unidadesfun WHERE (unae26jefe=' . $idTercero . ' OR unae26idresponsable=' . $idTercero . ') AND unae26idzona=0';
		$sCondi[2] = 'SELECT 1 FROM unae26unidadesfun WHERE unae26idadministrador=' . $idTercero . ' AND unae26idzona=0';
		$sCondi[3] = 'SELECT 1 FROM unae26unidadesfun WHERE (unae26jefe=' . $idTercero . ' OR unae26idresponsable=' . $idTercero . ') AND unae26idzona<>0';
		$iTotalPerfiles = 3;
		$sSQL = 'SELECT * FROM gafi00config WHERE gafi00id=1';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' Configuraci&oacute;n de perfiles comunes a la GAF ' . $sSQL . '<br>';
		}
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			$aCFG[1] = $fila['gafi00perfil_unidad_jefe'];
			$aCFG[2] = $fila['gafi00perfil_unidad_admin'];
			$aCFG[3] = $fila['gafi00perfil_unidad_fractal'];
		} else {
			$iTotalPerfiles = 0;
		}
		for ($j = 1; $j <= $iTotalPerfiles; $j++) {
			$tabla = $objDB->ejecutasql($sCondi[$j]);
			if ($objDB->nf($tabla) > 0) {
				$id05 = $aCFG[$j];
				if ($id05 != 0) {
					$aPerfil[$id05] = 1;
				}
			}
		}
	}
	//GTR - Tareas y Recursos (Talento Humano)
	if (true) {
		$sHoy = fecha_dia();
		//$sCondiFechas=' AND (STR_TO_DATE(TB.nico06fecha, "%d/%m/%Y")>=STR_TO_DATE("'.$sFecha.'", "%d/%m/%Y"))';
		/* 
		AND (STR_TO_DATE(gthu54ifechaingreso, "%d/%m/%Y")<=STR_TO_DATE("' . $sHoy . '", "%d/%m/%Y")) 
		AND (gthu54ifechafin=0 OR STR_TO_DATE(gthu54ifechafin, "%d/%m/%Y")>STR_TO_DATE(' . $sHoy . ', "%d/%m/%Y"))';
		*/
		$sCondi[1] = 'SELECT 1 FROM gthu54hvlaboral 
		WHERE gthu54idtercero=' . $idTercero . ' AND gthu54funcionario=1 
		AND (gthu54ifechaingreso<=' . $iHoy . ') 
		AND (gthu54ifechafin=0 OR gthu54ifechafin>' . $iHoy . ')';
		$iTotalPerfiles = 1;
		$sSQL = 'SELECT gthu00idperfilfunc FROM gthu00config WHERE gthu00id=1';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' Configuraci&oacute;n de perfiles de GTR ' . $sSQL . '<br>';
		}
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			$aCFG[1] = $fila['gthu00idperfilfunc'];
		} else {
			$iTotalPerfiles = 0;
		}
		for ($j = 1; $j <= $iTotalPerfiles; $j++) {
			$tabla = $objDB->ejecutasql($sCondi[$j]);
			if ($objDB->nf($tabla) > 0) {
				$id05 = $aCFG[$j];
				if ($id05 != 0) {
					$aPerfil[$id05] = 1;
				}
			}
		}
	}
	// 46 - SUEV
	if (true) {
		$sCondi = array();
		$sCondi[1] = 'SELECT 1 FROM empr05participantes WHERE empr05idtercero=' . $idTercero . ' AND empr05activo=1';
		$sCondi[2] = 'SELECT 1 FROM empr04emprendimiento WHERE empr04idasesor=' . $idTercero . '';
		$sCondi[3] = 'SELECT 1 FROM empr WHERE empr07idinversor=' . $idTercero . '';
		$iTotalPerfiles = 2;
		$sSQL = 'SELECT * FROM empr00params WHERE empr00id=1';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' Configuraci&oacute;n de perfiles de SUEV  ' . $sSQL . '<br>';
		}
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			$aCFG[1] = $fila['empr00idperfilemprend'];
			$aCFG[2] = $fila['empr00idperfilmentor'];
			$aCFG[3] = $fila['empr00idperfilinver'];
		} else {
			$iTotalPerfiles = 0;
		}
		for ($j = 1; $j <= $iTotalPerfiles; $j++) {
			$tabla = $objDB->ejecutasql($sCondi[$j]);
			if ($objDB->nf($tabla) > 0) {
				$id05 = $aCFG[$j];
				if ($id05 != 0) {
					$aPerfil[$id05] = 1;
				}
			}
		}
	}
	//Fin de activar los perfiles. Ahora aplicamos los cambios.
	$sIds05 = '-99';
	for ($k = 1; $k <= $iAdicionales; $k++) {
		$id05 = $aLista[$k];
		if ($aPerfil[$id05] == 1) {
			//Activar el perfil (hasta los $iPerfiles son reservados, los adicionales se mantienen por compatilibilidad.)
			login_activaperfil($idTercero, $id05, 'S', $objDB);
		} else {
			if ($k <= $iPerfiles) {
				$sIds05 = $sIds05 . ',' . $id05;
			}
		}
	}
	//Nos aseguramos de inactivar lo que no sirva.
	if ($sIds05 != '-99') {
		$sSQL = 'UPDATE unad07usuarios SET unad07vigente="N" WHERE unad07idtercero=' . $idTercero . ' AND unad07idperfil IN (' . $sIds05 . ') AND unad07vigente<>"N"';
		$result = $objDB->ejecutasql($sSQL);
	}
	//Ahora las alertas de ayuda.
	list($sDebugA) = f269_RegistarAyudas($idTercero, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugA;
	return array($sError, $sDebug, $sInfo);
}
function f107_VerificarPerfilesV0($idTercero, $idPeriodo, $objDB, $bDebug = false)
{
	//Esta funcion aplica para todos los ajustes los grupos.
	//Octubre 28 de 2020 - Debido a que los perfiles de los equipos de trabajo no van por aplicación, debemos cambiar la metodologia.
	// por tanto no se hara un barrido por aplicación, sino que se hará un barrido general de todos los perfiles reservados.
	$sError = '';
	$sDebug = '';
	//Perfiles de la Bitacora.
	if (true) {
		//Miramos que perfiles son reservados - no los mandamos a bloquear sino que los cargamos para tenerlos.
		$aPerfil = array();
		$idPerfil = array();
		$idLinea = array();
		$sCondi = array();
		$iPerfil = 0;
		//unad05aplicativo=15 AND 
		$sSQL = 'SELECT unad05id, unad05nombre FROM unad05perfiles WHERE unad05reservado="S"';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' Perfiles reservados [Todos los aplicativos] ' . $sSQL . '<br>';
		}
		$tabla = $objDB->ejecutasql($sSQL);
		while ($fila = $objDB->sf($tabla)) {
			$iPerfil++;
			$aPerfil[$iPerfil]['id'] = $fila['unad05id'];
			$aPerfil[$iPerfil]['estado'] = 0;
		}
		//Ya tiene todos los perfiles ahora habilitarlos segun corresponda.
		$iNumPerfiles = 0;
		if ($iNumPerfiles != 0) {
			for ($k = 1; $k++; $k < $iNumPerfiles) {
				$idPerfil[$k] = 0;
				$idLinea[$k] = 0;
			}
		}
		$sCondi = array();
		//Equipos de trabajo.
		$sSQL = 'SELECT bita27id, bita27idperfil FROM bita27equipotrabajo WHERE bita27idperfil>0';
		$tabla = $objDB->ejecutasql($sSQL);
		while ($fila = $objDB->sf($tabla)) {
			$iNumPerfiles++;
			$idPerfil[$iNumPerfiles] = $fila['bita27idperfil'];
			$idLinea[$iNumPerfiles] = 0;
			$sCondi[$iNumPerfiles] = 'SELECT 1 FROM bita28eqipoparte WHERE bita28idequipotrab=' . $fila['bita27id'] . ' AND bita28idtercero=' . $idTercero . ' AND bita28activo="S"';
		}
		//Ya se termina de cargar los perfiles dinamicos, ahora si la operacion de cargarlos...
		for ($j = 1; $j <= $iNumPerfiles; $j++) {
			if ($idPerfil[$j] != 0) {
				//if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consultando perfil '.$j.' en CORE<br>';}
				//Ubicar la linea en que el perfil coincide.
				for ($k = 1; $k <= $iPerfil; $k++) {
					//if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Comparando '.$aPerfil[$k]['id'].' a '.$idPerfil[$j].' en CORE<br>';}
					if ($aPerfil[$k]['id'] == $idPerfil[$j]) {
						$idLinea[$j] = $k;
						if ($bDebug) {
							$sDebug = $sDebug . fecha_microtiempo() . ' Aplicando perfil ' . $j . ' en BITACORA<br>';
						}
						$k = $iPerfil + 1;
					}
				}
			}
			if ($idLinea[$j] != 0) {
				//Si hay perfil marcado asi que miramos si cumple el condicional..
				$sSQL = $sCondi[$j];
				if ($bDebug) {
					$sDebug = $sDebug . fecha_microtiempo() . ' Revisando aplicabilidad <b>' . $sSQL . '</b> en BITACORA<br>';
				}
				$tabla = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($tabla) > 0) {
					$aPerfil[$idLinea[$j]]['estado'] = 1;
					if ($bDebug) {
						$sDebug = $sDebug . fecha_microtiempo() . ' <b>Activando perfil ' . $idPerfil[$j] . '</b> en BITACORA<br>';
					}
				}
			}
		}
		// Ahora si marcar los perfiles. (SOLO LOS PERFILES DE LOS GRUPOS DE TRABAJO LOS INACTIVAMOS PORQUE ESTAMOS MARCANDO TODO)
		for ($k = 1; $k <= $iPerfil; $k++) {
			$bExistePerfil = false;
			$sEstado = 'N';
			$idPerfil = $aPerfil[$k]['id'];
			if ($aPerfil[$k]['estado'] == 1) {
				$bExistePerfil = true;
			}
			if ($bExistePerfil) {
				$sEstado = 'S';
			}
			login_activaperfil($idTercero, $idPerfil, $sEstado, $objDB);
		}
	}
	// PERFILES DEL OAI.
	if (true) {
		$sSQL = 'SELECT T1.ofer10claserol 
		FROM ofer11actores AS TB, ofer10rol AS T1, exte02per_aca AS T2 
		WHERE TB.ofer11idtercero=' . $idTercero . ' AND TB.ofer11per_aca=T2.exte02id AND T2.exte02vigente="S" AND TB.ofer11idrol=ofer10id AND T1.ofer10claserol IN (1, 2, 3, 4, 5, 8) 
		GROUP BY T1.ofer10claserol';
		$troles = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($troles) > 0) {
			if ($idPeriodo == '') {
				$sCondi = 'ofer01id=1';
			} else {
				$sCondi = 'ofer01per_aca=' . $idPeriodo . '';
			}
			$sSQL = 'SELECT ofer01perfiladmin, ofer01perfilcoordinador, ofer01perfildecano, ofer01perfildirector, ofer01perfilrevisor, ofer01perfilacreditador, ofer01per_aca 
			FROM ofer01params 
			WHERE ' . $sCondi;
			$tperfiles = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tperfiles) > 0) {
				$fperfiles = $objDB->sf($tperfiles);
				$idPeriodo = $fperfiles['ofer01per_aca'];
			} else {
				if ($bDebug) {
					$sError = 'Se ha modificado el periodo acad&eacute;mico, no es posible procesar la solicitud {Periodo solicitado ' . $idPeriodo . '}';
				} else {
					$sError = '..';
				}
			}
			if ($sError == '') {
				while ($froles = $objDB->sf($troles)) {
					//encontrar el perfil.
					$idperfil = 0;
					$scampo = '';
					switch ($froles['ofer10claserol']) {
						case 1:
							$scampo = 'ofer01perfiladmin';
							break;
						case 2:
							$scampo = 'ofer01perfilcoordinador';
							break;
						case 3:
							$scampo = 'ofer01perfildecano';
							break;
						case 4:
							$scampo = 'ofer01perfilrevisor';
							break;
						case 5:
							$scampo = 'ofer01perfilacreditador';
							break;
						case 8:
							$scampo = 'ofer01perfildirector';
							break;
					}
					if ($scampo != '') {
						$idperfil = $fperfiles[$scampo];
					}
					if ($idperfil != 0) {
						//incluir en la tabla de perfiles.
						login_activaperfil($idTercero, $idperfil, 'S', $objDB);
					}
				}
			}
			if ($sError == '..') {
				$sError = '';
			}
		}
		if ($idPeriodo != '') {
			//Coordinadores van sobre la tabla 31
			$idPerfilCoordinador = 1702;
			$sSQL = 'SELECT ofer31idprograma 
			FROM ofer31programacoordinador 
			WHERE ofer31per_aca=' . $idPeriodo . ' AND ofer31idcoordinador=' . $idTercero . '';
			$tperfiles = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tperfiles) > 0) {
				login_activaperfil($idTercero, $idPerfilCoordinador, 'S', $objDB);
			}
		}
	}
	//Perfiles Core.
	if (true) {
		//Miramos que perfiles son reservados - no los mandamos a bloquear sino que los cargamos para tenerlos.
		$aPerfil = array();
		$idPerfil = array();
		$idLinea = array();
		$sCondi = array();
		$iPerfil = 0;
		$sSQL = 'SELECT unad05id, unad05nombre FROM unad05perfiles WHERE unad05aplicativo=22 AND unad05reservado="S"';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' Perfiles reservados de CORE ' . $sSQL . '<br>';
		}
		$tabla = $objDB->ejecutasql($sSQL);
		while ($fila = $objDB->sf($tabla)) {
			$iPerfil++;
			$aPerfil[$iPerfil]['id'] = $fila['unad05id'];
			$aPerfil[$iPerfil]['estado'] = 0;
		}
		//Ya tiene todos los perfiles ahora habilitarlos segun corresponda.
		//Decano y Administrador.
		$idPerfil[1] = 0;
		$idPerfil[2] = 0;
		$idPerfil[3] = 0;
		$idPerfil[4] = 0;
		$idPerfil[5] = 0;
		$idLinea[1] = 0;
		$idLinea[2] = 0;
		$idLinea[3] = 0;
		$idLinea[4] = 0;
		$idLinea[5] = 0;
		$sCondi = array();
		$sCondi[1] = 'SELECT 1 FROM core12escuela WHERE core12iddecano=' . $idTercero . '';
		$sCondi[2] = 'SELECT 1 FROM core12escuela WHERE core12idadministrador=' . $idTercero . '';
		$sCondi[3] = 'SELECT 1 FROM core09programa WHERE core09iddirector=' . $idTercero . '';
		$sCondi[4] = 'SELECT 1 FROM core19tutores WHERE core19idtercero=' . $idTercero . ' AND core19activo="S"';
		$sCondi[5] = 'SELECT 1 FROM core12escuela WHERE core12idrespcursocomun=' . $idTercero . '';
		$iTotalPerfiles = 5;
		$sSQL = 'SELECT core00idperfildecano, core00idperfiladminescuela, core00idperfilliderprog, core00idperfiltutor, core00idperfilcursoscomunes FROM core00params WHERE core00id=1';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' Configuraci&oacute;n de perfiles de CORE ' . $sSQL . '<br>';
		}
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			$idPerfil[1] = $fila['core00idperfildecano'];
			$idPerfil[2] = $fila['core00idperfiladminescuela'];
			$idPerfil[3] = $fila['core00idperfilliderprog'];
			$idPerfil[4] = $fila['core00idperfiltutor'];
			$idPerfil[5] = $fila['core00idperfilcursoscomunes'];
		} else {
			$iTotalPerfiles = 0;
		}
		//Ahora los perfiles de los espejos, estos pueden funcionar igual que lo de los grupos de trabajo, 
		//es decir una persona puede estar en varios grupos.
		$sSQL = 'SELECT core27id, core27idperfil FROM core27tipoespejo WHERE core27vigente="S" AND core27idperfil>0';
		$tabla = $objDB->ejecutasql($sSQL);
		while ($fila = $objDB->sf($tabla)) {
			$iTotalPerfiles++;
			$idPerfil[$iTotalPerfiles] = $fila['core27idperfil'];
			$sCondi[$iTotalPerfiles] = 'SELECT 1 FROM core26espejos WHERE core26idtercero=' . $idTercero . ' AND core26idtipoespejo=' . $fila['core27id'] . ' AND core26vigente="S"';
			$idLinea[$iTotalPerfiles] = 0;
		}

		for ($j = 1; $j <= $iTotalPerfiles; $j++) {
			if ($idPerfil[$j] != 0) {
				//if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consultando perfil '.$j.' en CORE<br>';}
				//Ubicar la linea en que el perfil coincide.
				for ($k = 1; $k <= $iPerfil; $k++) {
					//if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Comparando '.$aPerfil[$k]['id'].' a '.$idPerfil[$j].' en CORE<br>';}
					if ($aPerfil[$k]['id'] == $idPerfil[$j]) {
						$idLinea[$j] = $k;
						if ($bDebug) {
							$sDebug = $sDebug . fecha_microtiempo() . ' Aplicando perfil ' . $j . ' en CORE<br>';
						}
						$k = $iPerfil + 1;
					}
				}
			}
			if (isset($idLinea[$j]) == 0) {
				$idLinea[$j] = 0;
			}
			if ($idLinea[$j] != 0) {
				//Si hay perfil marcado asi que miramos si cumple el condicional..
				$sSQL = $sCondi[$j];
				if ($bDebug) {
					$sDebug = $sDebug . fecha_microtiempo() . ' Revisando aplicabilidad <b>' . $sSQL . '</b> en CORE<br>';
				}
				$tabla = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($tabla) > 0) {
					$aPerfil[$idLinea[$j]]['estado'] = 1;
					if ($bDebug) {
						$sDebug = $sDebug . fecha_microtiempo() . ' <b>Activando perfil ' . $idPerfil[$j] . '</b> en CORE<br>';
					}
				}
			}
		}
		// Ahora si marcar los perfiles.
		for ($k = 1; $k <= $iPerfil; $k++) {
			$idPerfil = $aPerfil[$k]['id'];
			if ($aPerfil[$k]['estado'] == 1) {
				login_activaperfil($idTercero, $idPerfil, 'S', $objDB);
			}
		}
	}
	//Perfiles del Centralizador de calificaciones.
	if (true) {
		//Miramos que perfiles son reservados - no los mandamos a bloquear sino que los cargamos para tenerlos.
		$aPerfil = array();
		$idPerfil = array();
		$idLinea = array();
		$sCondi = array();
		$iPerfil = 0;
		$sSQL = 'SELECT unad05id, unad05nombre FROM unad05perfiles WHERE unad05aplicativo=24 AND unad05reservado="S"';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' Perfiles reservados de C2 ' . $sSQL . '<br>';
		}
		$tabla = $objDB->ejecutasql($sSQL);
		while ($fila = $objDB->sf($tabla)) {
			$iPerfil++;
			$aPerfil[$iPerfil]['id'] = $fila['unad05id'];
			$aPerfil[$iPerfil]['estado'] = 0;
		}
		//Ya tiene todos los perfiles ahora habilitarlos segun corresponda.
		//Decano y Administrador.
		$idPerfil[1] = 0;
		$idPerfil[2] = 0;
		$idLinea[1] = 0;
		$idLinea[2] = 0;
		$sCondi = array();
		$sCondi[1] = 'SELECT ofer08id FROM ofer08oferta WHERE ofer08idacomanamento=' . $idTercero . ' AND ofer08estadooferta=1';
		$sCondi[2] = 'SELECT core19id FROM core19tutores WHERE core19idtercero=' . $idTercero . ' AND core19activo="S"';
		$sSQL = 'SELECT ceca00idperfildirector, ceca00idperfiltutor FROM core00params WHERE core00id=1';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			$idPerfil[1] = $fila['ceca00idperfildirector'];
			$idPerfil[2] = $fila['ceca00idperfiltutor'];
		}
		for ($j = 1; $j <= 2; $j++) {
			if ($idPerfil[$j] != 0) {
				//if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consultando perfil '.$j.' en C2<br>';}
				//Ubicar la linea en que el perfil coincide.
				for ($k = 1; $k <= $iPerfil; $k++) {
					//if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Comparando '.$aPerfil[$k]['id'].' a '.$idPerfil[$j].' en C2<br>';}
					if ($aPerfil[$k]['id'] == $idPerfil[$j]) {
						$idLinea[$j] = $k;
						if ($bDebug) {
							$sDebug = $sDebug . fecha_microtiempo() . ' Aplicando perfil ' . $j . ' en C2<br>';
						}
						$k = $iPerfil + 1;
					}
				}
			}
			if ($idLinea[$j] != 0) {
				//Si hay perfil marcado asi que miramos si cumple el condicional..
				$sSQL = $sCondi[$j];
				if ($bDebug) {
					$sDebug = $sDebug . fecha_microtiempo() . ' Revisando aplicabilidad <b>' . $sSQL . '</b> en C2<br>';
				}
				$tabla = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($tabla) > 0) {
					$aPerfil[$idLinea[$j]]['estado'] = 1;
					if ($bDebug) {
						$sDebug = $sDebug . fecha_microtiempo() . ' <b>Activando perfil ' . $idPerfil[$j] . '</b> en C2<br>';
					}
				}
			}
		}
		// Ahora si marcar los perfiles.
		for ($k = 1; $k <= $iPerfil; $k++) {
			$idPerfil = $aPerfil[$k]['id'];
			if ($aPerfil[$k]['estado'] == 1) {
				login_activaperfil($idTercero, $idPerfil, 'S', $objDB);
			}
		}
	}
	//Perfiles Caracterizacion.
	if (true) {
		//Miramos que perfiles son reservados - no los mandamos a bloquear sino que los cargamos para tenerlos.
		$aPerfil = array();
		$iPerfil = 0;
		$sSQL = 'SELECT unad05id, unad05nombre FROM unad05perfiles WHERE unad05aplicativo=23 AND unad05reservado="S"';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' Perfiles reservados de CARACTERIZACION ' . $sSQL . '<br>';
		}
		$tabla = $objDB->ejecutasql($sSQL);
		while ($fila = $objDB->sf($tabla)) {
			$iPerfil++;
			$aPerfil[$iPerfil]['id'] = $fila['unad05id'];
			$aPerfil[$iPerfil]['estado'] = 0;
		}
		//Ya tiene todos los perfiles ahora habilitarlos segun corresponda.
		//Consejeros.
		$idLinea = 0;
		$idPerfil = 0;
		$idPerfilLider = 0;
		$idLineaLider = 0;
		$sSQL = 'SELECT cara00idperfilconsejero, cara00idperfillider FROM cara00config WHERE cara00id=1';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			$idPerfil = $fila['cara00idperfilconsejero'];
			$idPerfilLider = $fila['cara00idperfillider'];
		}
		if ($idPerfil != 0) {
			//Ubicar la linea para consejero.
			for ($k = 1; $k <= $iPerfil; $k++) {
				if ($aPerfil[$k]['id'] == $idPerfil) {
					$idLinea = $k;
					$k = $iPerfil + 1;
				}
			}
		}
		if ($idPerfilLider != 0) {
			//Ubicar la linea para lider de programa..
			for ($k = 1; $k <= $iPerfil; $k++) {
				if ($aPerfil[$k]['id'] == $idPerfilLider) {
					$idLineaLider = $k;
					$k = $iPerfil + 1;
				}
			}
		}
		if ($idLinea != 0) {
			//Si hay perfil marcado asi que miramos si es consejero.
			$sSQL = 'SELECT cara13id FROM cara13consejeros WHERE cara13idconsejero=' . $idTercero . ' AND cara13activo="S"';
			$tabla = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla) > 0) {
				$aPerfil[$idLinea]['estado'] = 1;
				if ($bDebug) {
					$sDebug = $sDebug . fecha_microtiempo() . ' Activando perfil de Consejero en CARACTERIZACION perfil ' . $idPerfil . '<br>';
				}
			}
		}
		if ($idLineaLider != 0) {
			//Si hay perfil marcado para lider zonal, marcarlo.
			$sSQL = 'SELECT cara21id FROM cara21lidereszona WHERE cara21idlider=' . $idTercero . ' AND cara21activo="S"';
			$tabla = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla) > 0) {
				$aPerfil[$idLineaLider]['estado'] = 1;
				if ($bDebug) {
					$sDebug = $sDebug . fecha_microtiempo() . ' Activando perfil de Lider Zonal en CARACTERIZACION perfil ' . $idPerfilLider . '<br>';
				}
			}
		}
		// Ahora si marcar los perfiles.
		for ($k = 1; $k <= $iPerfil; $k++) {
			$idPerfil = $aPerfil[$k]['id'];
			if ($aPerfil[$k]['estado'] == 1) {
				login_activaperfil($idTercero, $idPerfil, 'S', $objDB);
			}
		}
	}
	//Perfiles de practicas //31.
	if (true) {
		//Miramos que perfiles son reservados - no los mandamos a bloquear sino que los cargamos para tenerlos.
		$aPerfil = array();
		$idPerfil = array();
		$idLinea = array();
		$sCondi = array();
		$iPerfil = 0;
		$sSQL = 'SELECT unad05id, unad05nombre FROM unad05perfiles WHERE unad05aplicativo=31 AND unad05reservado="S"';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' Perfiles reservados de PRACTICAS ' . $sSQL . '<br>';
		}
		$tabla = $objDB->ejecutasql($sSQL);
		while ($fila = $objDB->sf($tabla)) {
			$iPerfil++;
			$aPerfil[$iPerfil]['id'] = $fila['unad05id'];
			$aPerfil[$iPerfil]['estado'] = 0;
		}
		//Ya tiene todos los perfiles ahora habilitarlos segun corresponda.
		//Decano y Administrador.
		$idPerfil[1] = 0;
		$idPerfil[2] = 0;
		$idPerfil[3] = 0;
		$idPerfil[4] = 0;
		$idPerfil[5] = 0;
		$idLinea[1] = 0;
		$idLinea[2] = 0;
		$idLinea[3] = 0;
		$idLinea[4] = 0;
		$idLinea[5] = 0;
		$sCondi = array();
		$sCondi[1] = 'SELECT 1 FROM olab41tipopractica WHERE olab41idlider=' . $idTercero . ' AND olab41activa="S"';
		$sCondi[2] = 'SELECT 1 FROM olab45practica WHERE olab45idtutor=' . $idTercero . '';
		$sCondi[3] = 'SELECT core09id FROM core09programa WHERE core09iddirector=' . $idTercero . '';
		$sCondi[4] = 'SELECT core19id FROM core19tutores WHERE core19idtercero=' . $idTercero . ' AND core19activo="S"';
		$sCondi[5] = 'SELECT core12id FROM core12escuela WHERE core12idrespcursocomun=' . $idTercero . '';
		$sSQL = 'SELECT prac00idperfillider, prac00idperfiltutor FROM prac00parametros WHERE prac00id=1';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' Configuraci&oacute;n de perfiles de PRACTICAS ' . $sSQL . '<br>';
		}
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			$idPerfil[1] = $fila['prac00idperfillider'];
			$idPerfil[2] = $fila['prac00idperfiltutor'];
		}
		for ($j = 1; $j <= 2; $j++) {
			if ($idPerfil[$j] != 0) {
				//Ubicar la linea en que el perfil coincide.
				for ($k = 1; $k <= $iPerfil; $k++) {
					if ($aPerfil[$k]['id'] == $idPerfil[$j]) {
						$idLinea[$j] = $k;
						if ($bDebug) {
							$sDebug = $sDebug . fecha_microtiempo() . ' Aplicando perfil ' . $j . ' en PRACTICAS<br>';
						}
						$k = $iPerfil + 1;
					}
				}
			}
			if ($idLinea[$j] != 0) {
				//Si hay perfil marcado asi que miramos si cumple el condicional..
				$sSQL = $sCondi[$j];
				if ($bDebug) {
					$sDebug = $sDebug . fecha_microtiempo() . ' Revisando aplicabilidad <b>' . $sSQL . '</b> en PRACTICAS<br>';
				}
				$tabla = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($tabla) > 0) {
					$aPerfil[$idLinea[$j]]['estado'] = 1;
					if ($bDebug) {
						$sDebug = $sDebug . fecha_microtiempo() . ' <b>Activando perfil ' . $idPerfil[$j] . '</b> en PRACTICAS<br>';
					}
				}
			}
		}
		// Ahora si marcar los perfiles.
		for ($k = 1; $k <= $iPerfil; $k++) {
			$idPerfil = $aPerfil[$k]['id'];
			if ($aPerfil[$k]['estado'] == 1) {
				login_activaperfil($idTercero, $idPerfil, 'S', $objDB);
			}
		}
	}
	//Perfiles del OIL
	if (true) {
		/*
		$sRoles='';
		$sSQL='SELECT olab05idrol FROM olab05roles WHERE olab05activo="S" AND olab05idrol>0 GROUP BY olab05idrol';
		$tabla17=$objDB->ejecutasql($sSQL);
		while($fila17=$objDB->sf($tabla17)){
			$sRoles=$sRoles.$fila17['olab05idrol'].' ';
			login_activaperfil($idTercero, $fila17['olab05idrol'], 'N', $objDB);
			}
		login_activaperfil($idTercero, 2108, 'N', $objDB);
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' ACTUALIZANDO PERFILES OIL: Se inactivan los siguientes perfiles: '.$sRoles.' 2108<br>';}
		$sSQL='SELECT T1.olab05idrol 
		FROM olab17actores AS TB, olab05roles AS T1 
		WHERE TB.olab17idactor='.$idTercero.' AND TB.olab17idrol=T1.olab05id AND TB.olab17activo="S" 
		GROUP BY T1.olab05idrol';
		$sRoles='';
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' ACTUALIZANDO PERFILES OIL: Consulta de actores: '.$sSQL.'<br>';}
		$tabla17=$objDB->ejecutasql($sSQL);
		while($fila17=$objDB->sf($tabla17)){
			//Agregar el usuario...
			$sRoles=$sRoles.$fila17['olab05idrol'].' ';
			login_activaperfil($idTercero, $fila17['olab05idrol'], 'S', $objDB);
			//$iDs=$iDs.','.$fila17['olab05idrol'];
			}
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' ACTUALIZANDO PERFILES OIL: Se <b>ACTIVAN</b> los siguientes perfiles: '.$sRoles.'<br>';}
		//Directores de curso.
		$sListaPeracas='-99';
		$sSQL='SELECT olab08idperaca FROM olab08oferta GROUP BY olab08idperaca';
		$tabla17=$objDB->ejecutasql($sSQL);
		while($fila17=$objDB->sf($tabla17)){
			$sListaPeracas=$sListaPeracas.', '.$fila17['olab08idperaca'];
			}
		$sSQL='SELECT olab08idresponsable 
		FROM olab08oferta WHERE olab08idresponsable='.$idTercero.' AND olab08idperaca IN ('.$sListaPeracas.') AND olab08cerrado="S" LIMIT 0, 1';
		$tabla17=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla17)>0){
			if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' ACTUALIZANDO PERFILES OIL: Se <b>ACTIVAN</b> el perfil de director {2104}<br>';}
			login_activaperfil($idTercero, 2104, 'S', $objDB);
			}
		//
		$sSQL='SELECT TB.ofer11per_aca 
		FROM ofer11actores AS TB, ofer10rol AS T1 
		WHERE TB.ofer11idtercero='.$idTercero.' AND TB.ofer11per_aca IN ('.$sListaPeracas.') AND TB.ofer11idcurso<>-1 AND TB.ofer11idrol=T1.ofer10id AND T1.ofer10claserol IN (1,2,3,4)';
		$tabla17=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla17)>0){
			if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' ACTUALIZANDO PERFILES OIL: Se <b>ACTIVAN</b> los perfiles de director {1704 y 2105}<br>';}
			login_activaperfil($idTercero, 1704, 'S', $objDB);
			login_activaperfil($idTercero, 2105, 'S', $objDB);
			}	
		//Tutores de laboratorio.... Se utiliza el perfil. 2108
		$sSQL='SELECT TB.olab37idperaca FROM olab37tutores AS TB, exte02per_aca AS T2 WHERE TB.olab37idtutor='.$idTercero.' AND TB.olab37idproceso=1 AND TB.olab37activo="S" AND TB.olab37idperaca=T2.exte02id AND T2.exte02vigente="S"';
		$tabla17=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla17)>0){
			if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' ACTUALIZANDO PERFILES OIL: Se <b>ACTIVAN</b> el perfil de director de laboratorio {2108}<br>';}
			login_activaperfil($idTercero, 2108, 'S', $objDB);
			}	
		*/
	}
	//Fin de los perfiles.
	return array($sError, $sDebug);
}
// Esta funcion nos permite saber si alguien es SuperAdmin pidiendo el perfil 1
function f107_PerfilPertenece($idTercero, $idPerfil, $objDB, $bDebug = false)
{
	$bRes = false;
	$sDebug = '';
	$sSQL = 'SELECT 1 FROM unad07usuarios WHERE unad07idperfil=' . $idPerfil . ' AND unad07idtercero=' . $idTercero . ' AND unad07vigente="S"';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$bRes = true;
	}
	return array($bRes, $sDebug);
}
//
function f109_GrupoModulo($idModulo, $idOrden, $objDB)
{
	require './app.php';
	if (!function_exists('AUREA_Idioma()')) {
		require_once $APP->rutacomun . 'libaurea.php';
	}
	$sIdioma = AUREA_Idioma();
	$idGrupo = 0;
	$sGrupoMod = '';
	$sPagina = '';
	$sSQL = 'SELECT TB.unad09grupo, T1.unad08nombre, T1.unad08pagina, T1.unad08nombre_en, T1.unad08nombre_pt 
	FROM unad09modulomenu AS TB, unad08grupomenu AS T1 
	WHERE TB.unad09idmodulo=' . $idModulo . ' AND TB.unad09consec=' . $idOrden . ' AND TB.unad09grupo=T1.unad08id';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$idGrupo = $fila['unad09grupo'];
		$sGrupoMod = cadena_notildes($fila['unad08nombre']);
		switch ($sIdioma) {
			case 'en':
			case 'pt':
				$sGrupoMod2 = cadena_notildes($fila['unad08nombre_' . $sIdioma]);
				if (trim($sGrupoMod2) != '') {
					$sGrupoMod = $sGrupoMod2;
				}
				break;
		}
		$sPagina = $fila['unad08pagina'];
	}
	return array($sGrupoMod, $sPagina, $idGrupo);
}
//
function f111_AsignarIdMoodle($idTercero, $objDB, $bDebug = false)
{
	$sDebug = '';
	$idResultado = 0;
	$bEncontrarNuevoId = false;
	$sSQL = 'SELECT unad11idmoodle FROM unad11terceros WHERE unad11id=' . $idTercero . '';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$idResultado = $fila['unad11idmoodle'];
		if ($idResultado == 0) {
			$bEncontrarNuevoId = true;
		}
	}
	if ($bEncontrarNuevoId) {
		$sSQL = 'SELECT unad11id FROM unad11terceros WHERE unad11idmoodle=' . $idTercero . ' AND unad11id<>' . $idTercero . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			//El id ya esta ocupado... que hacemos... Buscar el siguiente id desocupado.
			$sSQL = 'SELECT unat11idmoodle, unat11idtercero 
			FROM unat11idsmoodle
			WHERE unat11idtercero=0 AND unat11idmoodle>200
			ORDER BY unat11idmoodle 
			LIMIT 0,1';
			$tabla = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla) > 0) {
				$fila = $objDB->sf($tabla);
				$idResultado = $fila['unat11idmoodle'];
			}
		} else {
			$idResultado = $idTercero;
		}
		//Lo marcamos de una vez
		$sSQL = 'UPDATE unat11idsmoodle SET unat11idtercero=' . $idTercero . ' WHERE unat11idmoodle=' . $idResultado . '';
		$result = $objDB->ejecutasql($sSQL);
		$sSQL = 'UPDATE unad11terceros SET unad11idmoodle=' . $idResultado . ' WHERE unad11id=' . $idTercero . ' AND unad11idmoodle=0';
		$result = $objDB->ejecutasql($sSQL);
	}
	return array($idResultado, $sDebug);
}
// Septiembre 29 de 2025 - Se inicia el uso de series debido a que se publican los ids en varios escenarios.
function f111_IdSerie($numSerie, $id11, $objDB, $bDebug = false) 
{
	$iRes = 0;
	$sError = '';
	$sDebug = '';
	$bLimpiaT11 = false;
	$sSQL = 'SELECT unaf17p' . $numSerie . ' FROM unaf17series WHERE unaf17id=' . $id11 . '';
	if ($bDebug) {
		//$sDebug = $sDebug . log_debug('');
	}
	$tabla17 = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla17) > 0) {
		$fila17 = $objDB->sf($tabla17);
		$iRes = $fila17['unaf17p' . $numSerie];
	} else {
		$sSQL = 'INSERT INTO unaf17series (unaf17id, unaf17p1, unaf17p2, unaf17p3, unaf17p4, unaf17p5, unaf17p6, unaf17p7) VALUES (' . $id11 . ', 0, 0, 0, 0, 0, 0, 0)';
		$result = $objDB->ejecutasql($sSQL);
	}
	if ($iRes == 0) {
		$sSQL = 'SELECT MAX(unaf17p' . $numSerie . ') AS maximo FROM unaf17series';
		$tabla17 = $objDB->ejecutasql($sSQL);
		$fila17 = $objDB->sf($tabla17);
		$iRes = $fila17['maximo'];
		$iRes++;
		$sSQL = 'UPDATE unaf17series SET unaf17p' . $numSerie . '=' . $iRes .' WHERE unaf17id=' . $id11 . '';
		$tabla17 = $objDB->ejecutasql($sSQL);
	}
	return array($iRes, $sError, $sDebug);
}

function f111_ProgramaCentro($idTercero, $objDB, $bDebug = false)
{
	$idEscuela = 0;
	$idPrograma = 0;
	$idZona = 0;
	$idCentro = 0;
	$sDebug = '';
	$sSQL = 'SELECT core01idestado, core01idescuela, core01idprograma, core01idzona, core011idcead 
	FROM core01estprograma 
	WHERE core01idtercero=' . $idTercero . ' AND core01idestado NOT IN (11, 12) 
	ORDER BY core01fechainicio DESC';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$idEscuela = $fila['core01idescuela'];
		$idPrograma = $fila['core01idprograma'];
		$idZona = $fila['core01idzona'];
		$idCentro = $fila['core011idcead'];
	} else {
		$sSQL = 'SELECT unad11idzona, unad11idcead, unad11idescuela, unad11idprograma FROM unad11terceros WHERE unad11id=' . $idTercero . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			$idEscuela = $fila['unad11idescuela'];
			$idPrograma = $fila['unad11idprograma'];
			$idZona = $fila['unad11idzona'];
			$idCentro = $fila['unad11idcead'];
		}
	}
	return array($idEscuela, $idPrograma, $idZona, $idCentro, $sDebug);
}
//
function f111_ProcesarMatricula($idTercero, $objDB, $bDebug)
{
	$sError = '';
	$sDebug = '';
	$idTercero = -99;
	$iNumProcesados = 0;
	$sSQLAdd = '';
	if ($idTercero != '') {
		$sSQLAdd = 'TB.core16tercero=' . $idTercero . ' AND ';
	}
	$sSQL = 'SELECT TB.core16tercero, TB.core16idescuela, TB.core16idprograma, TB.core16idzona, TB.core16idcead, T11.unad11idescuela, T11.unad11idprograma, T11.unad11idzona, T11.unad11idcead
	FROM core16actamatricula AS TB, unad11terceros AS T11
	WHERE ' . $sSQLAdd . 'TB.core16tercero=T11.unad11id
	GROUP BY TB.core16tercero, TB.core16idescuela, TB.core16idprograma, TB.core16idzona, TB.core16idcead, T11.unad11idescuela, T11.unad11idprograma, T11.unad11idzona, T11.unad11idcead
	ORDER BY TB.core16tercero, TB.core16peraca DESC';
	$tabla = $objDB->ejecutasql($sSQL);
	while ($fila = $objDB->sf($tabla)) {
		if ($idTercero != $fila['core16tercero']) {
			$idTercero = $fila['core16tercero'];
			$bEntra = false;
			if ($fila['core16idescuela'] != $fila['unad11idescuela']) {
				$bEntra = true;
			} else {
				if ($fila['core16idprograma'] != $fila['unad11idprograma']) {
					$bEntra = true;
				} else {
					if ($fila['core16idzona'] != $fila['unad11idzona']) {
						$bEntra = true;
					} else {
						if ($fila['core16idcead'] != $fila['unad11idcead']) {
							$bEntra = true;
						}
					}
				}
			}
			if ($bEntra) {
				$sSQL = 'UPDATE unad11terceros SET unad11idescuela=' . $fila['core16idescuela'] . ', unad11idprograma=' . $fila['core16idprograma'] . ', unad11idzona=' . $fila['core16idzona'] . ', unad11idcead=' . $fila['core16idcead'] . ' WHERE unad11id=' . $idTercero . '';
				$result = $objDB->ejecutasql($sSQL);
				$iNumProcesados++;
			}
		}
	}
	return array($iNumProcesados, $sError, $sDebug);
}
// 2024-07-09 Verificar la condición si se pueden actualizar los datos del usuario
function f111_PuedeActualizarDatos($unad11id, $objDB, $bDebug = false)
{
	$sError = '';
	$sDebug = '';
	$bPuedeEditar = true;
	// Tabla de proveedores (gafi16proveedores)
	// Si el proveedor está Vencido - 3 | En verificación - 5 | Activo - 7 no puede editar
	$sSQL = 'SELECT 1 FROM gafi16proveedores WHERE gafi16idtercero=' . $unad11id . ' AND gafi16estado IN (3, 5, 7)';
	$result = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($result) > 0) {
		$sError = 'El tercero no se puede editar debido a que aparece registrado como proveedor';
		$bPuedeEditar = false;
	}
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' <b>Revisando si es proveedor [gafi16proveedores]</b>: ' . $sSQL . '<br>';
	}
	return array($bPuedeEditar, $sError, $sDebug);
}
// 2024-07-11 Verificar si existen varios usuarios
function f111_VerificarUnicoUsuario($unad11usuario, $objDB, $bDebug = false)
{
	$sError = '';
	$sDebug = '';
	$bUnicoUsuario = true;
	// Cuenta el número de usuarios en el SII
	$sSQL = 'SELECT COUNT(1) AS numusuarios FROM unad11terceros WHERE unad11usuario=' . $unad11usuario . '';
	$result = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($result) > 0) {
		$fila = $objDB->sf($result);
		if ($fila['numusuarios'] > 1) {
			// Si hay más de un usuario devuelve false
			$bUnicoUsuario = false;
		}
	}
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' <b>Verificando si tiene un &uacute;nico usuario</b>: ' . $sSQL . '';
	}
	return array($bUnicoUsuario, $sError, $sDebug);
}
// 2024-07-12 Cambiar los usuarios que no sea el actual de ryc
function f111_CambiarUsuarios($ryctipodoc, $rycdoc, $rycusuario, $objDB, $bDebug = false)
{
	$sError = '';
	$sDebug = '';
	$sTipoDoc = '';
	$sCambios = '';
	$sNuevoUsuario = '';
	$sSQL = 'SELECT TB.unad45codigo 
	FROM unad45tipodoc AS TB 
	WHERE TB.unad45equivrca=' . $ryctipodoc . '';
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' <b>Obteniendo el tipo de documento</b>: ' . $sSQL . '';
	}
	$result = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($result) > 0) {
		$fila = $objDB->sf($result);
		$sTipoDoc = $fila['unad45codigo'];
	}
	// Trae los datos de los usuarios a cambiar
	$sSQL = 'SELECT TB.unad11id, TB.unad11tipodoc, TB.unad11doc 
	FROM unad11terceros AS TB 
	WHERE TB.unad11usuario="' . $rycusuario . '" AND (TB.unad11tipodoc NOT IN ("' . $sTipoDoc . '") OR 
	TB.unad11doc NOT IN (' . $rycdoc . '))';
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' <b>Verificando datos de usuarios a cambiar</b>: ' . $sSQL . '';
	}
	$result = $objDB->ejecutasql($sSQL);
	while ($fila = $objDB->sf($result)) {
		// Cambiar los usuarios menos el que tiene el mismo tipo y documento que vienen de ryc
		// @@@@ se deja el tipo documento y documento del mismo o del ultimo? 
		$sNuevoUsuario = strtolower($fila['unad11tipodoc'] . $fila['unad11doc']);
		// $sNuevoUsuario = $sTipoDoc . $rycdoc;
		$sSQL = 'UPDATE unad11terceros
		SET unad11usuario="' . $sNuevoUsuario . '" 
		WHERE unad11usuario="' . $rycusuario . '" AND unad11tipodoc="' . $fila['unad11tipodoc'] . '" 
		AND unad11doc=' . $fila['unad11doc'] . '';
		$result = $objDB->ejecutasql($sSQL);
		if ($result == false) {
			$sError = ' Falla al actualizar [unad11terceros] ..<!-- ' . $sSQL . ' -->';
		} else {
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . ' <b>Actualizando usuario ' . $rycusuario . '</b>: ' . $sSQL . '';
				$sCambios = $sCambios . 'Cambio de usuario ' . $rycusuario . ' a ' . $sNuevoUsuario . '<br>';
			}
		}
	}
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' <b>Verificando si tiene un &uacute;nico usuario</b>: ' . $sSQL . '';
	}
	return array($sCambios, $sError, $sDebug);
}
function f117_NumMes($sNombreMes)
{
	$iMes = 0;
	switch (strtoupper($sNombreMes)) {
		case 1:
		case 'ENERO':
			$iMes = 1;
			break;
		case 2:
		case 'FEBRERO':
			$iMes = 2;
			break;
		case 3:
		case 'MARZO':
			$iMes = 3;
			break;
		case 4:
		case 'ABRIL':
			$iMes = 4;
			break;
		case 5:
		case 'MAYO':
			$iMes = 5;
			break;
		case 6:
		case 'JUNIO':
			$iMes = 6;
			break;
		case 7:
		case 'JULIO':
			$iMes = 7;
			break;
		case 8:
		case 'AGOSTO':
			$iMes = 8;
			break;
		case 9:
		case 'SEPTIEMBRE':
			$iMes = 9;
			break;
		case 10:
		case 'OCTUBRE':
			$iMes = 10;
			break;
		case 11:
		case 'NOVIEMBRE':
			$iMes = 11;
			break;
		case 12:
		case 'DICIEMBRE':
			$iMes = 12;
			break;
	}
	return $iMes;
}
//Responsables
function f123_Responsables($id23, $objDB)
{
	$iBaseDir = 0;
	$iBaseAdmin = 0;
	$sSQL = 'SELECT unad23id, unad23director, unad23administrador 
	FROM unad23zona 
	WHERE unad23id=' . $id23 . '';
	$tabla23 = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla23) > 0) {
		$fila23 = $objDB->sf($tabla23);
		$iBaseDir = $fila23['unad23director'];
		$iBaseAdmin = $fila23['unad23administrador'];
	}
	return array($iBaseDir, $iBaseAdmin);
}
//Centros asociados a la zona.
function f123_CentrosZona($idZona, $objDB, $bDebug = false)
{
	$sRes = '-99';
	$sDebug = '';
	$sSQL = 'SELECT unad24id FROM unad24sede WHERE unad24idzona=' . $idZona . '';
	$tabla = $objDB->ejecutasql($sSQL);
	while ($fila = $objDB->sf($tabla)) {
		$sRes = $sRes . ',' . $fila['unad24id'];
	}
	return array($sRes, $sDebug);
}
//Saber si se pertenece a una zona
function f123_ZonaPertenece($idTercero, $idRevisa, $objDB, $bDebug = false)
{
	$idZona = '';
	$sDebug = '';
	if ((int)$idRevisa != 0) {
		$sCondi23 = 'TB.unad23id=' . $idRevisa . '';
	} else {
		$sCondi23 = 'TB.unad23director=' . $idTercero . '';
	}
	$sSQL = 'SELECT TB.unad23id, TB.unad23director 
	FROM unad23zona AS TB
	WHERE ' . $sCondi23 . '';
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' Consultando Actores de zona: [' . $idTercero . '] ' . $sSQL . '<br>';
	}
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$bEsta = false;
		if ($idTercero == $fila['unad23director']) {
			$bEsta = true;
		}
		if ($bEsta) {
			$idZona = $fila['unad23id'];
		}
	}
	if (!$bEsta) {
		//Ver que no sea un espejo zonal
		$sSQL = 'SELECT core26idzona FROM core26espejos WHERE core26idzona IN (0,' . $idRevisa . ') AND core26idtercero=' . $idTercero . ' AND core26vigente="S" ORDER BY core26idzona DESC';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' Consultando Espejos: ' . $sSQL . '<br>';
		}
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$bEsta = true;
			$fila = $objDB->sf($tabla);
			$idZona = $fila['core26idzona'];
		}
	}
	return array($idZona, $sDebug);
}
//Saber si se pertenece a un centro
function f124_CentroPertenece($idTercero, $idRevisa, $objDB, $bDebug = false, $bIncluirRCont = true)
{
	$idCentro = '';
	$idZona = '';
	$sDebug = '';
	if ((int)$idRevisa != 0) {
		$sCondi24 = 'TB.unad24id=' . $idRevisa . '';
	} else {
		if ($bIncluirRCont) {
			$sCondi24 = '((TB.unad24director=' . $idTercero . ') OR (TB.unad24idrca=' . $idTercero . '))';
		} else {
			$sCondi24 = '(TB.unad24director=' . $idTercero . ')';
		}
	}
	$sSQL = 'SELECT TB.unad24id, TB.unad24director, TB.unad24idrca, TB.unad24idzona 
	FROM unad24sede AS TB
	WHERE ' . $sCondi24 . '';
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' Consultando Actores de centro: ' . $sSQL . '<br>';
	}
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$bEsta = false;
		if ($idTercero == $fila['unad24director']) {
			$bEsta = true;
		}
		if ($bIncluirRCont) {
			if ($idTercero == $fila['unad24idrca']) {
				$bEsta = true;
			}
		}
		if ($bEsta) {
			$idCentro = $fila['unad24id'];
			$idZona = $fila['unad24idzona'];
		}
	}
	return array($idCentro, $idZona, $sDebug);
}
//Centro de costo por sede
function f1101_CentroCostoSede($idSede, $objDB)
{
	return f124_CentroCostoSede($idSede, $objDB);
}
function f124_CentroCostoSede($idSede, $objDB)
{
	$res = 0;
	if ($idSede == '') {
		$idSede = 0;
	}
	if ($idSede != 0) {
		$sSQL = 'SELECT unad24centrocosto FROM unad24sede WHERE unad24id=' . $idSede . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			$res = $fila['unad24centrocosto'];
		}
	}
	return $res;
}
//Cursos que hacen parte de un periodo 
function f140_CursosPeriodo($idPeriodo, $objDB, $bDebug = false, $idEscuela = 0, $idPrograma = 0, $bOfertados = true, $bSoloPropietario = false, $idTercero = 0)
{
	$sIds = '-99';
	$sDebug = '';
	$sCondi = '';
	if ($bOfertados) {
		$sCondi = ' AND ofer08estadooferta=1';
	}
	//$bSoloPropietario -- Solo aquellos cursos conde es el propietario
	if ($bSoloPropietario) {
		if ($idTercero > 0) {
			$sIds12 = '-99';
			$sSQL = 'SELECT corf12idoferta FROM corf12directores WHERE corf12idtercero=' . $idTercero . ' AND corf12activo=1';
			$tabla = $objDB->ejecutasql($sSQL);
			while ($fila = $objDB->sf($tabla)) {
				$sIds12 = $sIds12 . ',' . $fila['corf12idoferta'];
			}
			// Ahora los cursos donde es un lider de programa
			$sIds09 = '-99';
			$sSQL = 'SELECT TB.core09id FROM core09programa AS TB WHERE TB.core09iddirector=' . $idTercero . '';
			$tabla = $objDB->ejecutasql($sSQL);
			while ($fila = $objDB->sf($tabla)) {
				$sIds09 = $sIds09 . ',' . $fila['core09id'];
			}
			if ($sIds09 != '-99') {
				$sSQL = 'SELECT ofer08id 
				FROM ofer08oferta 
				WHERE ofer08idper_aca=' . $idPeriodo . ' AND ofer08idprograma IN (' . $sIds09 . ')';
				$tabla = $objDB->ejecutasql($sSQL);
				while ($fila = $objDB->sf($tabla)) {
					$sIds12 = $sIds12 . ',' . $fila['ofer08id'];
				}
			}
			// Ahora si la consulta.
			$sCondi = $sCondi . ' AND ((ofer08idacomanamento=' . $idTercero . ') OR (ofer08id IN (' . $sIds12 . ')))';
		} else {
			// Solo el propietario, pero no hay propietario...
			$sCondi = ' AND ofer08id=-99';
		}
	}
	$sSQL = 'SELECT ofer08idcurso 
	FROM ofer08oferta 
	WHERE ofer08idper_aca=' . $idPeriodo . '' . $sCondi;
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' <b>Cursos Ofertados</b>: ' . $sSQL . '<br>';
	}
	$tabla = $objDB->ejecutasql($sSQL);
	while ($fila = $objDB->sf($tabla)) {
		$sIds = $sIds . ',' . $fila['ofer08idcurso'];
	}
	return array($sIds, $sDebug);
}

function f140_IdentificadorCurso($idCurso, $NumAula, $idPeriodo, $objDB, $bDebug = false)
{
	$sRes = '';
	$sDebug = '';
	$sNomAula = '.';
	switch ($NumAula) {
		case 1:
			$sNomAula = 'A';
			break;
		case 2:
			$sNomAula = 'B';
			break;
		case 3:
			$sNomAula = 'C';
			break;
		case 4:
			$sNomAula = 'D';
			break;
		case 5:
			$sNomAula = 'E';
			break;
		case 6:
			$sNomAula = 'F';
			break;
		case 8:
			$sNomAula = 'H';
			break;
		case 19:
			$sNomAula = 'R';
			break;
		case 20:
			$sNomAula = 'S';
			break;
		default:
			if ($NumAula != '') {
				$sNomAula = $NumAula;
			}
			break;
	}
	$sRes = $idCurso . $sNomAula . '_' . $idPeriodo;
	//Dependiendo del alistamiento, los nombres pueden variar.
	$sSQL = 'SELECT unad40titulo FROM unad40curso WHERE unad';
	return array($sRes, $sDebug);
}
// Id del curso
function f140_IdXTitulo($sTitulo, $objDB)
{
	$idCurso = -1;
	if (trim($sTitulo) != '') {
		$sSQL = 'SELECT unad40id FROM unad40curso WHERE unad40titulo="' . trim($sTitulo) . '"';
		$tabla40 = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla40) > 0) {
			$fila40 = $objDB->sf($tabla40);
			$idCurso = $fila40['unad40id'];
		}
	}
	return $idCurso;
}
// Etiqueta del curso... Nombre del curso
function f140_EtiquetaCurso($idCurso, $objDB)
{
	$sRes = '{' . $idCurso . '}';
	$sSQL = 'SELECT unad40titulo, unad40nombre FROM unad40curso WHERE unad40id=' . $idCurso . '';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$sRes = $fila['unad40titulo'] . ' - ' . cadena_notildes($fila['unad40nombre']);
	}
	return $sRes;
}
function f140_TituloCurso($idCurso, $objDB)
{
	$sRes = '{' . $idCurso . '}';
	$sSQL = 'SELECT unad40titulo FROM unad40curso WHERE unad40id=' . $idCurso . '';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$sRes = $fila['unad40titulo'];
	}
	return $sRes;
}
function f146_ConsultaCombo($sWhere = '', $objDB = NULL, $idTercero = 0, $idPrograma = 0)
{
	switch ($sWhere) {
		case 1707: //Solo donde se haya hecho oferta.
			$sIds = '-99';
			$sSQL = 'SELECT ofer08idper_aca FROM ofer08oferta GROUP BY ofer08idper_aca';
			$tabla = $objDB->ejecutasql($sSQL);
			while ($fila = $objDB->sf($tabla)) {
				$sIds = $sIds . ',' . $fila['ofer08idper_aca'];
			}
			$sWhere = 'exte02id IN (' . $sIds . ')';
			break;
		case 1708: //Solo donde el personaje sea responsable de una oferta.
			$sIds = '-99';
			if ($idTercero > 0) {
				$sCondi = '';
				$bTotal = false;
				$sSQL = 'SELECT TB.core09id FROM core09programa AS TB WHERE TB.core09iddirector=' . $idTercero . '';
				$tabla = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($tabla) > 0) {
					$bTotal = true;
				}
				if (!$bTotal) {
					//No tiene una condicion especial, por tanto o solo donde es director de curso.
					$sIds12 = '-99';
					$sSQL = 'SELECT corf12idoferta FROM corf12directores WHERE corf12idtercero=' . $idTercero . ' AND corf12activo=1';
					$tabla = $objDB->ejecutasql($sSQL);
					while ($fila = $objDB->sf($tabla)) {
						$sIds12 = $sIds12 . ',' . $fila['corf12idoferta'];
					}
					$sCondi = ' WHERE ((ofer08idacomanamento=' . $idTercero . ') OR (ofer08id IN (' . $sIds12 . ')))';
				}
			} else {
				$sCondi = ' WHERE ofer08id=-99';
			}
			$sSQL = 'SELECT ofer08idper_aca FROM ofer08oferta ' . $sCondi . ' GROUP BY ofer08idper_aca';
			$tabla = $objDB->ejecutasql($sSQL);
			while ($fila = $objDB->sf($tabla)) {
				$sIds = $sIds . ',' . $fila['ofer08idper_aca'];
			}
			$sWhere = 'exte02id IN (' . $sIds . ')';
			break;
		case 1756: //Solo donde se haya hecho preoferta.
			$sIds = '-99';
			$sSQL = 'SELECT ofer64idperiodo FROM ofer64preofanalisis GROUP BY ofer64idperiodo';
			$tabla = $objDB->ejecutasql($sSQL);
			while ($fila = $objDB->sf($tabla)) {
				$sIds = $sIds . ',' . $fila['ofer64idperiodo'];
			}
			$sWhere = 'exte02id IN (' . $sIds . ')';
			break;
		case 2100:
			//Solo los peracas donde se oferten laboratorios.
			$sWhere = 'ext02ofertalaboratorios="S"';
			break;
		case 2216:
			//Solo los peracas donde haya matricula
			$sIds = '-99';
			$sSQL = 'SELECT core16peraca FROM core16actamatricula GROUP BY core16peraca';
			$tabla = $objDB->ejecutasql($sSQL);
			while ($fila = $objDB->sf($tabla)) {
				$sIds = $sIds . ',' . $fila['core16peraca'];
			}
			$sWhere = 'exte02id IN (' . $sIds . ')';
			break;
		case 2301:
			//Solo los peracas donde haya encuestas.
			$sIds = '-99';
			$sSQL = 'SELECT cara01idperaca FROM cara01encuesta GROUP BY cara01idperaca';
			$tabla = $objDB->ejecutasql($sSQL);
			while ($fila = $objDB->sf($tabla)) {
				$sIds = $sIds . ',' . $fila['cara01idperaca'];
			}
			$sWhere = 'exte02id IN (' . $sIds . ')';
			break;
		case 2492:
			//Solo los peracas donde es profesor.
			$sIds = '-99';
			$sSQL = 'SELECT cara01idperaca FROM cara01encuesta GROUP BY cara01idperaca';
			$tabla = $objDB->ejecutasql($sSQL);
			while ($fila = $objDB->sf($tabla)) {
				$sIds = $sIds . ',' . $fila['cara01idperaca'];
			}
			$sWhere = 'exte02id IN (' . $sIds . ')';
			break;
		case 5115:
			// Solo donde se haya hecho oferta del programa
			$sIds = '-99';
			if ($idPrograma > 0) {
				$sSQL = 'SELECT ofer08idper_aca FROM ofer08oferta WHERE ofer08idprograma=' . $idPrograma . ' GROUP BY ofer08idper_aca';
				$tabla = $objDB->ejecutasql($sSQL);
				while ($fila = $objDB->sf($tabla)) {
					$sIds = $sIds . ',' . $fila['ofer08idper_aca'];
				}
				$sWhere = 'exte02id IN (' . $sIds . ')';
			}
			break;
	}
	if ($sWhere != '') {
		$sWhere = 'WHERE ' . $sWhere;
	}
	return 'SELECT exte02id AS id, CONCAT(exte02id, " - ", CASE exte02vigente WHEN "S" THEN "" ELSE "[" END, exte02nombre, CASE exte02vigente WHEN "S" THEN "" ELSE " - INACTIVO]" END) AS nombre FROM exte02per_aca ' . $sWhere . ' ORDER BY exte02vigente DESC, exte02id DESC';
}
function f146_Contenedor($idPeriodo, $objDB)
{
	$iRes = 0;
	$sSQL = 'SELECT exte02contgrupos FROM exte02per_aca WHERE exte02id=' . $idPeriodo . '';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$iRes = $fila['exte02contgrupos'];
	}
	return $iRes;
}
function f146_ContendoresVariosPeriodos($sListaPeriodos, $objDB, $iFormato = 1)
{
	$sRes = '';
	$aRes = array();
	$iTotal = 0;
	$sSQL = 'SELECT exte02contgrupos FROM exte02per_aca WHERE exte02id IN (' . $sListaPeriodos . ') AND exte02contgrupos>0 GROUP BY exte02contgrupos';
	$tabla = $objDB->ejecutasql($sSQL);
	while ($fila = $objDB->sf($tabla)) {
		switch ($iFormato) {
			case 0:
				if ($sRes != '') {
					$sRes = $sRes . ',';
				}
				$sRes = $sRes . $fila['exte02contgrupos'];
				break;
			default:
				$iTotal++;
				$aRes[$iTotal] = $fila['exte02contgrupos'];
				break;
		}
	}
	switch ($iFormato) {
		case 0:
			return array($sRes, '');
			break;
		default:
			return array($aRes, $iTotal);
			break;
	}
}
function f146_FechaInicial($idPeriodo, $objDB, $bDebug = false, $bForzar = false)
{
	$iFecha = 0;
	$sDebug = '';
	$sSQL = 'SELECT exte02fechaini60 FROM exte02per_aca WHERE exte02id=' . $idPeriodo . '';
	if ($bDebug) {
		$sDebug = $sDebug . ' <b>Fecha Inicial</b> ' . $sSQL . '<br>';
	}
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$iFecha = $fila['exte02fechaini60'];
		$bIgualar = false;
		if ($iFecha == 0) {
			$bIgualar = true;
		}
		if ($bForzar) {
			$bIgualar = true;
		}
		if ($bIgualar) {
			//Mirar que puede estar en una tabla de configuracion, entonces traer la nota.
			$sSQL = 'SELECT ofer14fechaini60, ofer14fechafin60, ofer14fechaini40, ofer14fechafin40, ofer14fechainisupletorio, ofer14fechafinsupletorio, ofer14fechainihabilitacion, ofer14fechafinhabilitacion, ofer14fechaini25poc, ofer14fechafin25poc 
			FROM ofer14per_acaparams 
			WHERE ofer14idper_aca=' . $idPeriodo . '';
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . ' <b>Fecha Inicial</b> Consultando historicos: ' . $sSQL . '<br>';
			}
			$tabla = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla) > 0) {
				$fila = $objDB->sf($tabla);
				if (fecha_esvalida($fila['ofer14fechaini60'])) {
					$iFecha = fecha_EnNumero($fila['ofer14fechaini60']);
					$iFecha60Fin = fecha_EnNumero($fila['ofer14fechafin60']);
					$iFecha40Ini = fecha_EnNumero($fila['ofer14fechaini40']);
					$iFecha40Fin = fecha_EnNumero($fila['ofer14fechafin40']);
					$iFechaSupIni = fecha_EnNumero($fila['ofer14fechainisupletorio']);
					$iFechaSupFin = fecha_EnNumero($fila['ofer14fechafinsupletorio']);
					$iFechaHabIni = fecha_EnNumero($fila['ofer14fechainihabilitacion']);
					$iFechaHabFin = fecha_EnNumero($fila['ofer14fechafinhabilitacion']);
					$iFechaPOCini = fecha_EnNumero($fila['ofer14fechaini25poc']);
					$iFechaPOCfin = fecha_EnNumero($fila['ofer14fechafin25poc']);
					$sSQL = 'UPDATE exte02per_aca SET exte02fechaini60=' . $iFecha . ', exte02fechafin60=' . $iFecha60Fin . ', exte02fechaini40=' . $iFecha40Ini . ', exte02fechafin40=' . $iFecha40Fin . ', exte02fechainisupletorio=' . $iFechaSupIni . ', exte02fechafinsupletorio=' . $iFechaSupFin . ', exte02fechainihabilitacion=' . $iFechaHabIni . ', exte02fechafinhabilitacion=' . $iFechaHabFin . ', exte02fechaini25poc=' . $iFechaPOCini . ', exte02fechafin25poc=' . $iFechaPOCfin . '  
					WHERE exte02id=' . $idPeriodo . '';
					if ($bDebug) {
						$sDebug = $sDebug . ' <b>Fecha Inicial</b> Actualizando: ' . $sSQL . '<br>';
					}
					$result = $objDB->ejecutasql($sSQL);
				}
			}
		}
	}
	return array($iFecha, $sDebug);
}
function f146_PeriodoActual($objDB, $iTipoPeriodo = 1, $bDebug = false)
{
	$iPeriodoActual = '';
	$sDebug = '';
	$sHoy = fecha_hoy();
	$sSQL = 'SELECT TB.ofer14idper_aca 
	FROM ofer14per_acaparams AS TB, exte02per_aca AS T2
	WHERE STR_TO_DATE(TB.ofer14fechaini60, "%d/%m/%Y")<=STR_TO_DATE("' . $sHoy . '", "%d/%m/%Y") AND STR_TO_DATE(TB.ofer14fechafin40, "%d/%m/%Y")>=STR_TO_DATE("' . $sHoy . '", "%d/%m/%Y") AND TB.ofer14idper_aca=T2.exte02id AND T2.exte02vigente="S" AND T2.exte02tipoperiodo=' . $iTipoPeriodo . '
	ORDER BY TB.ofer14idper_aca DESC
	LIMIT 0,1';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$iPeriodoActual = $fila['ofer14idper_aca'];
	}
	return array($iPeriodoActual, $sDebug);
}
function f146_PeriodosVigentes($sFecha, $objDB, $iTipoPeriodo = '', $bDebug = false)
{
	$sPeriodos = '';
	$sDebug = '';
	//AND T2.exte02tipoperiodo=0
	$sSQL = 'SELECT TB.ofer14idper_aca 
	FROM ofer14per_acaparams AS TB, exte02per_aca AS T2
	WHERE STR_TO_DATE(TB.ofer14fechaini60, "%d/%m/%Y")<=STR_TO_DATE("' . $sFecha . '", "%d/%m/%Y") AND STR_TO_DATE(TB.ofer14fechafin40, "%d/%m/%Y")>=STR_TO_DATE("' . $sFecha . '", "%d/%m/%Y") AND TB.ofer14idper_aca=T2.exte02id AND T2.exte02vigente="S" 
	ORDER BY TB.ofer14idper_aca DESC';
	$tabla = $objDB->ejecutasql($sSQL);
	while ($fila = $objDB->sf($tabla)) {
		if ($sPeriodos != '') {
			$sPeriodos = $sPeriodos . ',';
		}
		$sPeriodos = $sPeriodos . $fila['ofer14idper_aca'];
	}
	return array($sPeriodos, $sDebug);
}
function f146_TopeMatricula($idPeriodo, $objDB, $bDebug = false)
{
	$sError = '';
	$sDebug = '';
	$sSQL = 'SELECT exte02fechafinmatricula FROM exte02per_aca WHERE exte02id=' . $idPeriodo . '';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$iDiaTope = $fila['exte02fechafinmatricula'];
		if ($iDiaTope != 0) {
			$iHoy = fecha_DiaMod();
			if ($iHoy > $iDiaTope) {
				$sError = 'Los procesos de matricula para este periodo se cerraron el ' . formato_FechaLargaDesdeNumero($iDiaTope, true) . '';
			}
		}
	}
	return array($sError, $sDebug);
}
function f190_TraerUbicacionV2($sIp, $objDB, $bDebug = false)
{
	$sError = '';
	$sDebug = '';
	$latGrados = 0;
	$latDecimas = '';
	$lonGrados = 0;
	$lonDecimas = '';
	$iFecha = 0;
	$iAgno = fecha_agno();
	for ($k = $iAgno; $k >= 2016; $k--) {
		$sNomTabla = 'unad71sesion' . $k;
		if ($k == 2016) {
			$sNomTabla = 'unad71sesion';
		}
		if ($objDB->bexistetabla($sNomTabla)) {
			$sSQL = 'SELECT TB.unad71fechaini, TB.unad71latgrados, TB.unad71latdecimas, TB.unad71longrados, TB.unad71longdecimas FROM ' . $sNomTabla . ' AS TB WHERE TB.unad71iporigen="' . $sIp . '" AND TB.unad71latdecimas<>"" AND TB.unad71proximidad<100 ORDER BY TB.unad71proximidad LIMIT 0, 2';
			$tabla = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla) > 0) {
				$fila = $objDB->sf($tabla);
				$latGrados = $fila['unad71latgrados'];
				$latDecimas = $fila['unad71latdecimas'];
				$lonGrados = $fila['unad71longrados'];
				$lonDecimas = $fila['unad71longdecimas'];
				$iFecha = $fila['unad71fechaini'];
				$k = 2000;
			}
		}
	}
	return array($sError, $latGrados, $latDecimas, $lonGrados, $lonDecimas, $iFecha, $sDebug);
}
function f190_AddIpV2($sIp, $objDB, $id90 = 0, $bDebug = false, $bConUbicacion = false)
{
	$bRes = false;
	$sDebug = '';
	$sSQL = 'SELECT unad90id FROM unad90controlip WHERE unad90ip="' . $sIp . '"';
	$tabla90 = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla90) == 0) {
		$latGrados = 0;
		$latDecimas = '';
		$lonGrados = 0;
		$lonDecimas = '';
		$iFecha = 0;
		if ($id90 == 0) {
			$id90 = tabla_consecutivo('unad90controlip', 'unad90id', '', $objDB);
		}
		if ($bConUbicacion) {
			list($sError, $latGrados, $latDecimas, $lonGrados, $lonDecimas, $iFecha, $sDebugG) = f190_TraerUbicacionV2($sIp, $objDB, $bDebug);
		}
		$sCampos190 = 'unad90ip, unad90id, unad90accion, unad90latgrados, unad90latdecimas, unad90longrados, unad90longdecimas, unad90detalle, unad90fechageo';
		$sValores190 = '"' . $sIp . '", ' . $id90 . ', 0, ' . $latGrados . ', "' . $latDecimas . '", ' . $lonGrados . ', "' . $lonDecimas . '", "", "' . $iFecha . '"';
		$sSQL = 'INSERT INTO unad90controlip (' . $sCampos190 . ') VALUES (' . $sValores190 . ');';
		$result = $objDB->ejecutasql($sSQL);
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' Insertando ' . $sSQL . '<br>';
		}
		$bRes = true;
	}
	return array($bRes, $sDebug);
}
function f217_CohorteAFecha($iDia, $objDB, $bDebug = false)
{
	$sDebug = '';
	$idCohorte = 0;
	$sSQL = 'SELECT unae17id FROM unae17cicloacadem WHERE unae17fechaini<=' . $iDia . ' ORDER BY unae17fechafin DESC LIMIT 0, 1';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$idCohorte = $fila['unae17id'];
	}
	return array($idCohorte, $sDebug);
}
//
function f226_ConsultaCombo($sWhere = '', $objDB = NULL)
{
	if ($sWhere != '') {
		$sWhere = $sWhere . ' AND ';
	}
	$sSQL = 'SELECT unae26id AS id, CONCAT(unae26prefijo, unae26nombre) AS nombre 
	FROM unae26unidadesfun 
	WHERE unae26idzona=0 AND ' . $sWhere . ' unae26id>0 
	ORDER BY unae26activa DESC, unae26lugar, unae26orden, unae26nombre';
	return $sSQL;
}
function f226_TituloUnidad($id26, $objDB)
{
	$sRes = 'Ninguna';
	if ((int)$id26 != 0) {
		$sRes = '{' . $id26 . '}';
		$sSQL = 'SELECT unae26prefijo, unae26nombre, unae26idzona, unae26activa 
		FROM unae26unidadesfun 
		WHERE unae26id=' . $id26 . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			$sRes = $fila['unae26prefijo'] . cadena_notildes($fila['unae26nombre']);
		}
	}
	return $sRes;
}
//
function f236_Complementos($fila)
{
	$unad01autoriza_tel = -1;
	$unad01autoriza_mat = -1;
	$unad01autoriza_bien = -1;
	$unad01autoriza_bol = -1;
	if (isset($fila['unad01autoriza_tel']) != 0) {
		$unad01autoriza_tel = $fila['unad01autoriza_tel'];
	}
	if (isset($fila['unad01autoriza_mat']) != 0) {
		$unad01autoriza_mat = $fila['unad01autoriza_mat'];
	}
	if (isset($fila['unad01autoriza_tel']) != 0) {
		$unad01autoriza_bien = $fila['unad01autoriza_bien'];
	}
	if (isset($fila['unad01autoriza_tel']) != 0) {
		$unad01autoriza_bol = $fila['unad01autoriza_bol'];
	}
	$sComplementoTel = '';
	$sComplementoCorreo = '';
	$bFull = true;
	$bTodoPendiente = true;
	$sSoloAutoriza = '';
	switch ($unad01autoriza_tel) {
		case '-1':
			break;
		case 1:
			$bTodoPendiente = false;
			$sSoloAutoriza = 'Acompa&ntilde;amiento Cursos';
			break;
		default:
			$bTodoPendiente = false;
			$bFull = false;
			break;
	}
	// Matricula.
	switch ($unad01autoriza_mat) {
		case '-1':
			break;
		case 1:
			$bTodoPendiente = false;
			if ($sSoloAutoriza != '') {
				$sSoloAutoriza = $sSoloAutoriza . ', ';
			}
			$sSoloAutoriza = $sSoloAutoriza . 'Matricula';
			break;
		default:
			$bTodoPendiente = false;
			$bFull = false;
			break;
	}
	// Bienestar
	switch ($unad01autoriza_bien) {
		case '-1':
			break;
		case 1:
			$bTodoPendiente = false;
			if ($sSoloAutoriza != '') {
				$sSoloAutoriza = $sSoloAutoriza . ', ';
			}
			$sSoloAutoriza = $sSoloAutoriza . 'Bienestar';
			break;
		default:
			$bTodoPendiente = false;
			$bFull = false;
			break;
	}
	// ---
	if (!$bFull) {
		if ($bTodoPendiente) {
			$sComplementoTel = ' <span class="rojo">Autorizaci&oacute;n datos personales pendiente</span>';
		} else {
			if ($sSoloAutoriza == '') {
				$sComplementoTel = ' <span class="rojo">NO AUTORIZA contacto telef&oacute;nico</span>';
			} else {
				$sComplementoTel = ' Llamar solo para temas: <span class="verde">' . $sSoloAutoriza . '</span>';
			}
		}
	}
	// ----- Ahora el correo.
	switch ($unad01autoriza_bol) {
		case '-1':
			$sComplementoCorreo = ' <span class="rojo">Autorizaci&oacute;n datos personales pendiente</span>';
			break;
		case 1:
			break;
		default:
			$sComplementoCorreo = ' <span class="rojo">NO AUTORIZA contacto por correo</span>';
			break;
	}
	return array($sComplementoTel, $sComplementoCorreo);
}
function f236_VerInfoPersonal($idTercero, $objDB, $iFormato = 0, $bDebug = false)
{
	$sRes = '';
	$sCorreo = '';
	$sTelefono = '';
	$sError = '';
	$sDebug = '';
	$bConInfo = false;
	if ($_SESSION['unad_id_tercero'] == 0) {
		$sError = 'No es posible procesar datos en este momento.';
	}
	if ($sError == '') {
		$sSQL = 'SELECT unad11correo, unad11telefono, unad01autoriza_tel, unad01autoriza_bol, unad01autoriza_mat, unad01autoriza_bien 
		FROM unad11terceros 
		WHERE unad11id=' . $idTercero . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			if (trim($fila['unad11correo']) != '') {
				$bConInfo = true;
				$sCorreo = trim($fila['unad11correo']);
			}
			if (trim($fila['unad11telefono']) != '') {
				$bConInfo = true;
				$sTelefono = trim($fila['unad11telefono']);
			}
			if ($bConInfo) {
				list($sComplementoTel, $sComplementoCorreo) = f236_Complementos($fila);
				$sRes = 'Tel&eacute;fono: <b>' . $sTelefono . '</b> ' . $sComplementoTel;
				if ($sRes != '') {
					$sRes = $sRes . '<br>';
				}
				$sRes = $sRes . 'Correo personal: <b>' . $sCorreo . '</b>' . $sComplementoCorreo;
			}
			if ($idTercero == $_SESSION['unad_id_tercero']) {
				$bConInfo = false;
			}
		}
	}
	if ($bConInfo) {
		$unae36fecha = fecha_DiaMod();
		$unae36hora = fecha_hora();
		$unae36minuto = fecha_minuto();
		$unae36idconsulta = $_SESSION['unad_id_tercero'];
		$unae36consec = tabla_consecutivo('unae36infopersonal', 'unae36consec', 'unae36idconsulta=' . $unae36idconsulta . '', $objDB);
		$unae36id = tabla_consecutivo('unae36infopersonal', 'unae36id', '', $objDB);
		$sCampos236 = 'unae36idconsulta, unae36consec, unae36id, unae36idtercero, unae36fecha, 
		unae36hora, unae36minuto, unae36telefono, unae36correo';
		$sValores236 = '' . $unae36idconsulta . ', ' . $unae36consec . ', ' . $unae36id . ', ' . $idTercero . ', ' . $unae36fecha . ', 
		' . $unae36hora . ', ' . $unae36minuto . ', "' . $sTelefono . '", "' . $sCorreo . '"';
		$sSQL = 'INSERT INTO unae36infopersonal (' . $sCampos236 . ') VALUES (' . $sValores236 . ');';
		$result = $objDB->ejecutasql($sSQL);
		if ($result == false) {
			$sError = 'Se ha producido un error interno al consultar los datos, por favor intentelo nuevamente. En caso de persistir la falla informe a soporte.campus@unad.edu.co';
			$sTelefono = '';
			$sCorreo = '';
		}
	}
	return array($sRes, $sCorreo, $sTelefono, $sError, $sDebug);
}
function f236_TraerInfoPersonal($aParametros)
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
	$idTercero = $aParametros[1];
	list($sDetalle, $sCorreo, $sTelefono, $sError, $sDebugTabla) = f236_VerInfoPersonal($idTercero, $objDB, 0, $bDebug);
	$sDebug = $sDebug . $sDebugTabla;
	$objDB->CerrarConexion();
	if ($sError != '') {
		$sDetalle = $sError . '<div class="salto1px"></div>
		<input id="cmdInfoPersonal" name="cmdInfoPersonal" type="button" class="BotonAzul160" value="Datos personales" onclick="verinfopersonal(' . $idTercero . ');" title="Ver datos personales"/>
		<div class="salto1px"></div>';
	}
	$objResponse = new xajaxResponse();
	$objResponse->assign('div_infopersonal', 'innerHTML', $sDetalle);
	if ($bDebug) {
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
	}
	return $objResponse;
}
//Modulos
function f269_ModulosTerceroV2($idSistema, $idTercero, $objDB, $bDebug = false)
{
	$sModulos = '0';
	$sDebug = '';
	$sCondiSistema = '';
	if ($idSistema != 0) {
		$sCondiSistema = ' AND T2.unad02idsistema=' . $idSistema . '';
	}
	$sSQL = 'SELECT T6.unad06idmodulo 
	FROM unad07usuarios as T7, unad06perfilmodpermiso AS T6, unad09modulomenu AS T9, unad02modulos AS T2 
	WHERE T7.unad07idtercero=' . $idTercero . ' AND T7.unad07vigente="S" 
	AND T7.unad07idperfil=T6.unad06idperfil AND T6.unad06vigente="S" AND T6.unad06idpermiso=1 
	AND T6.unad06idmodulo=T9.unad09idmodulo AND T6.unad06idmodulo=T2.unad02id ' . $sCondiSistema . '
	GROUP BY T6.unad06idmodulo ';
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' Consulta modulos ' . $idSistema . ' ' . $sSQL . '<br>';
	}
	$resultm = $objDB->ejecutasql($sSQL);
	while ($filam = $objDB->sf($resultm)) {
		$sModulos = $sModulos . ',' . $filam['unad06idmodulo'];
	}
	return array($sModulos, $sDebug);
}
//Alimentar los procesos de ayuda.
function f269_RegistarAyudas($idTercero, $objDB, $bDebug = false)
{
	$sDebug = '';
	$iHoy = fecha_DiaMod();
	$sHace2Meses = fecha_sumardias(fecha_hoy(), -60);
	$sCampos270 = 'INSERT INTO aure70verentrega (aure70idversionado, aure70idtercero, aure70id, aure70idsistema, aure70fechacarga, aure70fechavisto) VALUES ';
	list($sModulos, $sDebugM) = f269_ModulosTerceroV2(0, $idTercero, $objDB, $bDebug);
	$sDebug = $sDebug . $sDebugM;
	$aure70id = tabla_consecutivo('aure70verentrega', 'aure70id', '', $objDB);
	$sSQL = 'SELECT TB.aure69id, TB.aure69idsistema 
	FROM aure69versionado AS TB 
	WHERE TB.aure69idmodulo IN (' . $sModulos . ') AND TB.aure69publico="S" AND STR_TO_DATE(TB.aure69fecha, "%d/%m/%Y")>STR_TO_DATE("' . $sHace2Meses . '", "%d/%m/%Y")
	AND TB.aure69id NOT IN (SELECT T70.aure70idversionado FROM aure70verentrega AS T70 WHERE T70.aure70idtercero=' . $idTercero . ')';
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' <b>Items de ayuda a mostrar</b> ' . $sSQL . '<br>';
	}
	$tabla = $objDB->ejecutasql($sSQL);
	while ($fila = $objDB->sf($tabla)) {
		$sValores270 = '(' . $fila['aure69id'] . ', ' . $idTercero . ', ' . $aure70id . ', ' . $fila['aure69idsistema'] . ', ' . $iHoy . ', 0)';
		$result = $objDB->ejecutasql($sCampos270 . $sValores270);
		$aure70id++;
	}
	return array($sDebug);
}
// Años vigencia
function f503_Agnos($idVigencia, $objDB, $bDebug = false)
{
	$sDebug = '';
	$iAgnoIni = fecha_agno();
	$iMesI = 1;
	$iAgnoFin = $iAgnoIni;
	$iMesF = 12;
	if ((int)$idVigencia != 0) {
		// Las fechas extremas son las de la vigencia.
		$sSQL = 'SELECT ppto03fechainicio, ppto03fechacierre 
		FROM ppto03vigencia 
		WHERE ppto03id=' . $idVigencia . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			//Los comprobantes iniciales la fecha del documento es la del inicio de la vigencia.
			list($iDiaI, $iMesI, $iAgnoIni) = fecha_DividirNumero($fila['ppto03fechainicio']);
			list($iDiaF, $iMesF, $iAgnoFin) = fecha_DividirNumero($fila['ppto03fechacierre']);
		}
	}
	return array($iAgnoIni, $iAgnoFin, $iMesI, $iMesF, $sDebug);
}
//Los años de la vigencia presupuestal pero nos enfocamos en el rezago.
function f503_AgnosV2($idVigencia, $objDB, $bDebug = false)
{
	$sDebug = '';
	$iAgnoIni = fecha_agno();
	$iMesI = 1;
	$iAgnoFin = $iAgnoIni;
	$iMesF = 12;
	if ((int)$idVigencia != 0) {
		// Las fechas extremas son las de la vigencia.
		$sSQL = 'SELECT ppto03fechainicio, ppto03fecharezago, ppto03fechacierre 
		FROM ppto03vigencia 
		WHERE ppto03id=' . $idVigencia . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			//Los comprobantes iniciales la fecha del documento es la del inicio de la vigencia.
			list($iDiaI, $iMesI, $iAgnoIni) = fecha_DividirNumero($fila['ppto03fechainicio']);
			list($iDiaR, $iMesR, $iAgnoRez) = fecha_DividirNumero($fila['ppto03fecharezago']);
			list($iDiaF, $iMesF, $iAgnoFin) = fecha_DividirNumero($fila['ppto03fechacierre']);
		}
	}
	return array($iAgnoIni, $iAgnoRez, $iAgnoFin, $sDebug);
}
//Facturación
function f714_ConsultaCombo($sWhere = '', $iTipoProducto = 0)
{
	$sListaTipos = '1, 2';
	$sCampoTipo = ', CASE TB.fact14tipoproducto WHEN 2 THEN " [Servicio]" WHEN 11 THEN " [Académico]" ELSE "" END';
	switch ($iTipoProducto) {
		case 1: // Productos
		case 2: // Servicios
		case 11: // Académico
			$sListaTipos = $iTipoProducto;
			$sCampoTipo = '';
			break;
	}
	$sRes = 'SELECT TB.fact14id AS id, CONCAT(CASE TB.fact14codunad WHEN "" THEN "" ELSE CONCAT(TB.fact14codunad, " - ") END, TB.fact14nombre, " {", TB.fact14consec, "}"' . $sCampoTipo . ') AS nombre 
	FROM fact14producto AS TB
	WHERE TB.fact14id>0 AND ' . $sWhere . ' TB.fact14tipoproducto IN (' . $sListaTipos . ')
	ORDER BY TB.fact14nombre';
	return $sRes;
}
//
function f1011_BloqueTercero($idTercero, $objDB)
{
	$iRes = 0;
	$sError = '';
	$sSQL = 'SELECT unad11idtablero FROM unad11terceros WHERE unad11id=' . $idTercero . '';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$iRes = $fila['unad11idtablero'];
		if ($iRes == 0) {
			$idBloque = 1;
			$iTopeBloque = 40000;
			//No se le ha asignado un bloque....
			//Buscamos cual es el bloque que sigue... y luego cuandos estudiantes por bloque.
			$sSQL = 'SELECT unad00valor FROM unad00config WHERE unad00codigo="cont11_actual"';
			$tabla = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla) > 0) {
				$fila = $objDB->sf($tabla);
				$idBloque = $fila['unad00valor'];
				$iTopeBloque = f00_Leer('cont11_cupo', $objDB, $iTopeBloque);
			} else {
				$sSQL = 'INSERT INTO unad00config (unad00codigo, unad00nombre, unad00valor) VALUES ("cont11_actual", "Contenedor de terceros", 1), ("cont11_cupo", "Cupo por contenedor de terceros", 40000)';
				$tabla = $objDB->ejecutasql($sSQL);
			}
			//Verificamos que el tope no se supere...
			$iUsosBloque = 0;
			$sSQL = 'SELECT COUNT(unad11id) AS Total FROM unad11terceros WHERE unad11idtablero=' . $idBloque . '';
			$tabla = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla) > 0) {
				$fila = $objDB->sf($tabla);
				$iUsosBloque = $fila['Total'];
			}
			if ($iUsosBloque < $iTopeBloque) {
				$iRes = $idBloque;
			} else {
				//Se lleno esto... hay que agregar otro...
				$iRes = $idBloque + 1;
				$sSQL = 'UPDATE unad00config SET unad00valor=' . $iRes . ' WHERE unad00codigo="cont11_actual"';
				$tabla = $objDB->ejecutasql($sSQL);
			}
			if ($iRes != 0) {
				//Simplemente marcar este tercero en el bloque..
				$sSQL = 'UPDATE unad11terceros SET unad11idtablero=' . $iRes . ' WHERE unad11id=' . $idTercero . '';
				$tabla = $objDB->ejecutasql($sSQL);
			}
			//Termina cuando el usuario no estaba asignado a un contenedor.
		}
		if ($iRes != 0) {
			//Si no existe el contenedor crearlo. (Son varias tablas....)
			$sTabla = 'core03plandeestudios_' . $iRes;
			$bIniciarContenedor = !$objDB->bexistetabla($sTabla);
			if ($bIniciarContenedor) {
				//core03nota homologa Decimal(15,2) NULL DEFAULT 0, core03fechanotahomologa int NULL DEFAULT 0, 
				//core03idusuarionotahomo int NULL DEFAULT 0, core03detallehomologa Text NULL, 
				$sSQL = "CREATE TABLE " . $sTabla . " (core03idestprograma int NOT NULL, core03idcurso int NOT NULL, core03id int NULL DEFAULT 0, 
				core03idtercero int NULL DEFAULT 0, core03idprograma int NULL DEFAULT 0, 
				core03itipocurso int NULL DEFAULT 0, core03obligatorio int NULL DEFAULT 0, core03homologable varchar(1) NULL, 
				core03habilitable varchar(1) NULL, core03porsuficiencia varchar(1) NULL, core03idprerequisito int NULL DEFAULT 0, 
				core03idprerequisito2 int NULL DEFAULT 0, core03idprerequisito3 int NULL DEFAULT 0, core03idcorequisito int NULL DEFAULT 0, 
				core03numcreditos int NULL DEFAULT 0, core03nivelcurso int NULL DEFAULT 0, core03peracaaprueba int NULL DEFAULT 0, 
				core03nota75 Decimal(15,2) NULL DEFAULT 0, core03fechanota75 int NULL DEFAULT 0, core03idusuarionota75 int NULL DEFAULT 0, 
				core03nota25 Decimal(15,2) NULL DEFAULT 0, core03fechanota25 int NULL DEFAULT 0, core03idusuarionota25 int NULL DEFAULT 0, 
				core03idequivalencia int NULL DEFAULT 0, core03idmatricula int NULL DEFAULT 0, 
				core03fechainclusion int NULL DEFAULT 0, core03notafinal Decimal(15,2) NULL DEFAULT 0, core03formanota int NULL DEFAULT 0, 
				core03estado int NULL DEFAULT 0, core03idcursoreemp1 int NULL DEFAULT 0, core03idcursoreemp2 int NULL DEFAULT 0, 
				core03premidperiodo int NULL DEFAULT 0, core03idequivalente int NULL DEFAULT 0, core03tieneequivalente int NULL DEFAULT 0, 
				core03idlineaprof int NULL DEFAULT 0, core03premfecha int NULL DEFAULT 0, core03idhomolcurso int NULL DEFAULT 0,
				core03notahabilita Decimal(15,2) NULL DEFAULT 0, core03fechanotahabilita int NULL DEFAULT 0, 
				core03excepcion int NULL DEFAULT 0, core03idlineaelectiva int NULL DEFAULT 0, core03rcp_orden int NULL DEFAULT 0, 
				core03acumula int NULL DEFAULT 0, core03sissu_orden int NULL DEFAULT 0)";
				$bResultado = $objDB->ejecutasql($sSQL);
				if ($bResultado == false) {
					$sError = 'No ha sido posible iniciar la creaci&oacute;n del contenedor ' . $iRes . ', Por favor informe al administrador del sistema';
					$iRes = 0;
				}
			} else {
				//Ya existe la tabla, verificamos campos adicionales.
				$sSQL = 'SELECT core03idlineaelectiva FROM ' . $sTabla . ' LIMIT 0, 1';
				$bResultado = $objDB->ejecutasql($sSQL);
				if ($bResultado == false) {
					$sSQL = "ALTER TABLE " . $sTabla . " ADD core03idlineaelectiva int NULL DEFAULT 0";
					$bResultado = $objDB->ejecutasql($sSQL);
				}
				//Marzo 31 de 2024 - el RCP_orden (Ruta del Componente Práctico)
				$sSQL = 'SELECT core03rcp_orden FROM ' . $sTabla . ' LIMIT 0, 1';
				$bResultado = $objDB->ejecutasql($sSQL);
				if ($bResultado == false) {
					$sSQL = "ALTER TABLE " . $sTabla . " ADD core03rcp_orden int NULL DEFAULT 0";
					$bResultado = $objDB->ejecutasql($sSQL);
				}
				//Julio 22 de 2024 - Si acumula historial de otros planes de estudio (Planes de Transicion 11, Cambio de programa 12)
				$sSQL = 'SELECT core03acumula FROM ' . $sTabla . ' LIMIT 0, 1';
				$bResultado = $objDB->ejecutasql($sSQL);
				if ($bResultado == false) {
					$sSQL = "ALTER TABLE " . $sTabla . " ADD core03acumula int NULL DEFAULT 0";
					$bResultado = $objDB->ejecutasql($sSQL);
				}
				//Octubre 15 de 2024 - el SISSU_orden (Ruta del SISSU)
				$sSQL = 'SELECT core03sissu_orden FROM ' . $sTabla . ' LIMIT 0, 1';
				$bResultado = $objDB->ejecutasql($sSQL);
				if ($bResultado == false) {
					$sSQL = "ALTER TABLE " . $sTabla . " ADD core03sissu_orden int NULL DEFAULT 0";
					$bResultado = $objDB->ejecutasql($sSQL);
				}
			}
			if ($bIniciarContenedor) {
				$sSQL = "ALTER TABLE " . $sTabla . " ADD PRIMARY KEY(core03id)";
				$bResultado = $objDB->ejecutasql($sSQL);
				$sSQL = "ALTER TABLE " . $sTabla . " ADD UNIQUE INDEX core03plandeestudios_id(core03idcurso, core03idestprograma)";
				$bResultado = $objDB->ejecutasql($sSQL);
				$sSQL = "ALTER TABLE " . $sTabla . " ADD INDEX core03plandeestudios_padre(core03idestprograma)";
				$bResultado = $objDB->ejecutasql($sSQL);
				$sSQL = "ALTER TABLE " . $sTabla . " ADD INDEX core03plandeestudios_curso(core03idcurso)";
				$bResultado = $objDB->ejecutasql($sSQL);
				$sSQL = "ALTER TABLE " . $sTabla . " ADD INDEX core03plandeestudios_estado(core03estado)";
				$bResultado = $objDB->ejecutasql($sSQL);
				$sSQL = "ALTER TABLE " . $sTabla . " ADD INDEX core03plandeestudios_prematricula(core03premidperiodo)";
				$bResultado = $objDB->ejecutasql($sSQL);

				$sTabla = 'core04matricula_' . $iRes;
				$sSQL = "CREATE TABLE " . $sTabla . " (core04peraca int NOT NULL, core04tercero int NOT NULL, core04idcurso int NOT NULL, 
				core04id int NULL DEFAULT 0, core04idaula int NULL DEFAULT 0, core04idrol int NULL DEFAULT 0, 
				core04idnav int NULL DEFAULT 0, core04idgrupo int NULL DEFAULT 0, core04estadoengrupo int NULL DEFAULT 0, 
				core04fechamatricula int NULL DEFAULT 0, core04origenmatricula int NULL DEFAULT 0, core04idcead int NULL DEFAULT 0, 
				core04tienenota int NULL DEFAULT 0, core04idagenda int NULL DEFAULT 0, core04nota75 Decimal(15,2) NULL DEFAULT 0, core04puntosblearning int NULL DEFAULT -1, 
				core04fechanota75 int NULL DEFAULT 0, core04idusuarionota75 int NULL DEFAULT 0, core04nota25 Decimal(15,2) NULL DEFAULT 0, 
				core04fechanota25 int NULL DEFAULT 0, core04idusuarionota25 int NULL DEFAULT 0, core04notahabilita Decimal(15,2) NULL DEFAULT 0, 
				core04fechanotahabilita int NULL DEFAULT 0, core04idusuarionotahab int NULL DEFAULT 0, core04notasupletorio Decimal(15,2) NULL DEFAULT 0, 
				core04fechanotasupletorio int NULL DEFAULT 0, core04idusuarionotasup int NULL DEFAULT 0, core04notafinal Decimal(15,2) NULL DEFAULT 0, 
				core04estado int NULL DEFAULT 0, core04aplicoagenda int NULL DEFAULT 0, core04idprograma int NULL DEFAULT 0, 
				core04nuevo int NULL DEFAULT 0, core04cursoequivalente int NULL DEFAULT 0, core04idmoodle int NULL DEFAULT 0, 
				core04fechaultacceso int NULL DEFAULT 0, core04minultacceso int NULL DEFAULT 0, core04idtutor int NULL DEFAULT 0, 
				core04est_aprob Decimal(15,2) NULL DEFAULT 0, core04est_nivel int NULL DEFAULT 0, core04est_5presenta int NULL DEFAULT 0, core04est_70presenta int NULL DEFAULT 0, core04est_75presenta int NULL DEFAULT 0, 
				core04est_75cero int NULL DEFAULT 0, core04est_75noaprobado int NULL DEFAULT 0, core04est_5aprobado int NULL DEFAULT 0, core04est_70aprobado int NULL DEFAULT 0, core04est_75aprobado int NULL DEFAULT 0, 
				core04est_25presenta int NULL DEFAULT 0, core04est_25cero int NULL DEFAULT 0, core04est_25noaprobado int NULL DEFAULT 0, 
				core04est_25aprobado int NULL DEFAULT 0, core04est_100presenta int NULL DEFAULT 0, core04est_100cero int NULL DEFAULT 0, 
				core04est_100noaprobado int NULL DEFAULT 0, core04est_100aprobado int NULL DEFAULT 0, core04idevaldocente int NULL DEFAULT 0, 
				core04idregevaldoc int NULL DEFAULT 0, core04fechaexporta int NULL DEFAULT 0, core04minexporta int NULL DEFAULT 0, 
				core04calificado int NULL DEFAULT 0, core04fechaexp25 int NULL DEFAULT 0, core04minexp25 int NULL DEFAULT 0, 
				core04est_numactivm0 int NULL DEFAULT 0, core04est_numactivnoprem0 int NULL DEFAULT 0, core04est_numactivm1 int NULL DEFAULT 0, 
				core04est_numactivnoprem1 int NULL DEFAULT 0, core04est_numactivm2 int NULL DEFAULT 0, core04est_numactivnoprem2 int NULL DEFAULT 0, 
				core04idmatricula int NULL DEFAULT 0, core04edad int NULL DEFAULT 0, core04idplanestudio int NULL DEFAULT 0, 
				core04numcreditos int NULL DEFAULT 0, core04resultado int NULL DEFAULT 0, core04nummatriculaprograma int NULL DEFAULT 0, 
				core04numintento int NULL DEFAULT 0, core04estact_resul1 int NULL DEFAULT 0, core04estact_resul2 int NULL DEFAULT 0, 
				core04estact_resul3 int NULL DEFAULT 0, core04estact_resul4 int NULL DEFAULT 0, core04estact_resul5 int NULL DEFAULT 0, 
				core04estact_resul6 int NULL DEFAULT 0, core04estact_resul7 int NULL DEFAULT 0, core04estact_resul8 int NULL DEFAULT 0, 
				core04estact_resul9 int NULL DEFAULT 0, core04estact_resul10 int NULL DEFAULT 0, core04estact_puntaje1 int NULL DEFAULT 0, 
				core04estact_puntaje2 int NULL DEFAULT 0, core04estact_puntaje3 int NULL DEFAULT 0, core04estact_puntaje4 int NULL DEFAULT 0, 
				core04estact_puntaje5 int NULL DEFAULT 0, core04estact_puntaje6 int NULL DEFAULT 0, core04estact_puntaje7 int NULL DEFAULT 0, 
				core04estact_puntaje8 int NULL DEFAULT 0, core04estact_puntaje9 int NULL DEFAULT 0, core04estact_puntaje10 int NULL DEFAULT 0, 
				core04res75 Decimal(2,1) NULL DEFAULT 0, core04res25 Decimal(2,1) NULL DEFAULT 0, core04resdef Decimal(2,1) NULL DEFAULT 0, 
				core04idcupoblear int NULL DEFAULT 0, core04res75campus Decimal(2,1) NULL DEFAULT 0, core04idgrupoinicial int NULL DEFAULT 0)";
				$bResultado = $objDB->ejecutasql($sSQL);
				$sSQL = "ALTER TABLE " . $sTabla . " ADD PRIMARY KEY(core04id)";
				$bResultado = $objDB->ejecutasql($sSQL);
				$sSQL = "ALTER TABLE " . $sTabla . " ADD UNIQUE INDEX core04matricula_id(core04idcurso, core04tercero, core04peraca)";
				$bResultado = $objDB->ejecutasql($sSQL);
				$sSQL = "ALTER TABLE " . $sTabla . " ADD INDEX core04matricula_tutor(core04idtutor)";
				$bResultado = $objDB->ejecutasql($sSQL);
				$sSQL = "ALTER TABLE " . $sTabla . " ADD INDEX core04matricula_matricula(core04idmatricula)";
				$bResultado = $objDB->ejecutasql($sSQL);
				$sSQL = "ALTER TABLE " . $sTabla . " ADD INDEX core04matricula_tercero(core04tercero)";
				$bResultado = $objDB->ejecutasql($sSQL);
				$sSQL = "ALTER TABLE " . $sTabla . " ADD INDEX core04matricula_grupo(core04idgrupo)";
				$bResultado = $objDB->ejecutasql($sSQL);
				$sSQL = "ALTER TABLE " . $sTabla . " ADD INDEX core04matricula_periodo(core04peraca)";
				$bResultado = $objDB->ejecutasql($sSQL);
				$sSQL = "ALTER TABLE " . $sTabla . " ADD INDEX core04matricula_curso(core04idcurso)";
				$bResultado = $objDB->ejecutasql($sSQL);
				$sSQL = "ALTER TABLE " . $sTabla . " ADD INDEX core04matricula_centro(core04idcead)";
				$bResultado = $objDB->ejecutasql($sSQL);
				$sTabla = 'core05actividades_' . $iRes;
				$sSQL = "CREATE TABLE " . $sTabla . " (core05idmatricula int NOT NULL, core05idfase int NOT NULL, core05idunidad int NOT NULL, 
				core05idactividad int NOT NULL, core05id int NULL DEFAULT 0, core05peraca int NULL DEFAULT 0, 
				core05tercero int NULL DEFAULT 0, core05idcurso int NULL DEFAULT 0, core05idaula int NULL DEFAULT 0, 
				core05idgrupo int NULL DEFAULT 0, core05idnav int NULL DEFAULT 0, core05fechaapertura int NULL DEFAULT 0, 
				core05fechacierre int NULL DEFAULT 0, core05fecharetro int NULL DEFAULT 0, core05idtutor int NULL DEFAULT 0, 
				core05tipoactividad int NULL DEFAULT 0, core05puntaje75 int NULL DEFAULT 0, core05puntaje25 int NULL DEFAULT 0, 
				core05nota Decimal(15,2) NULL DEFAULT 0, core05fechanota int NULL DEFAULT 0, core05acumula75 int NULL DEFAULT 0, 
				core05acumula25 int NULL DEFAULT 0, core05estado int NULL DEFAULT 0, core05retroalimentacion Text NULL, 
				core05rezagado int NULL DEFAULT 0, core05calificado int NULL DEFAULT 0, core05idcupolab int NULL DEFAULT 0)";
				$bResultado = $objDB->ejecutasql($sSQL);
				$sSQL = "ALTER TABLE " . $sTabla . " ADD PRIMARY KEY(core05id)";
				$bResultado = $objDB->ejecutasql($sSQL);
				$sSQL = "ALTER TABLE " . $sTabla . " ADD UNIQUE INDEX core05actividades_id(core05idactividad, core05idunidad, core05idfase, core05idmatricula)";
				$bResultado = $objDB->ejecutasql($sSQL);
				$sSQL = "ALTER TABLE " . $sTabla . " ADD INDEX core05actividades_padre(core05idmatricula)";
				$bResultado = $objDB->ejecutasql($sSQL);
				$sSQL = "ALTER TABLE " . $sTabla . " ADD INDEX core05actividades_tercero(core05tercero)";
				$bResultado = $objDB->ejecutasql($sSQL);
				$sSQL = "ALTER TABLE " . $sTabla . " ADD INDEX core05actividades_curso(core05idcurso)";
				$bResultado = $objDB->ejecutasql($sSQL);
				$sSQL = "ALTER TABLE " . $sTabla . " ADD INDEX core05actividades_grupo(core05idgrupo)";
				$bResultado = $objDB->ejecutasql($sSQL);
				$sSQL = "ALTER TABLE " . $sTabla . " ADD INDEX core05actividades_estado(core05estado)";
				$bResultado = $objDB->ejecutasql($sSQL);
				$sSQL = "ALTER TABLE " . $sTabla . " ADD INDEX core05actividades_periodo(core05peraca)";
				$bResultado = $objDB->ejecutasql($sSQL);
				$sSQL = "ALTER TABLE " . $sTabla . " ADD INDEX core05actividades_actividad(core05idactividad)";
				$bResultado = $objDB->ejecutasql($sSQL);
			}
			// Marzo 30 de 2024 - Se crea la tabla de lineas de electividad.
			$sTabla = 'corg18lineaelec_' . $iRes;
			$bIniciarContenedor = !$objDB->bexistetabla($sTabla);
			if ($bIniciarContenedor) {
				$sSQL = "CREATE TABLE " . $sTabla . " (corg18idestprograma int NOT NULL, corg18idlineaelec int NOT NULL, corg18id int NOT NULL DEFAULT 0, corg18idtercero int NOT NULL DEFAULT 0, corg18idprograma int NOT NULL DEFAULT 0, corg18itipocurso int NOT NULL DEFAULT 0, corg18creditos int NOT NULL DEFAULT 0, corg18aprobados int NOT NULL DEFAULT 0)";
				$bResultado = $objDB->ejecutasql($sSQL);
				$sSQL = "ALTER TABLE " . $sTabla . " ADD PRIMARY KEY(corg18id)";
				$bResultado = $objDB->ejecutasql($sSQL);
				$sSQL = $objDB->sSQLCrearIndice($sTabla, 'corg18lineaelec_id', 'corg18idestprograma, corg18idlineaelec', true);
				$bResultado = $objDB->ejecutasql($sSQL);
				$sSQL = $objDB->sSQLCrearIndice($sTabla, 'corg18lineaelec_padre', 'corg18idestprograma');
				$bResultado = $objDB->ejecutasql($sSQL);
				$sSQL = $objDB->sSQLCrearIndice($sTabla, 'corg18lineaelec_tipo', 'corg18itipocurso');
				$bResultado = $objDB->ejecutasql($sSQL);
			}
			// Tablas del SAI
			$sTabla = 'saiu40baseconotifica_' . $iRes;
			$bIniciarContenedor = !$objDB->bexistetabla($sTabla);
			if ($bIniciarContenedor) {
				$sSQL = "CREATE TABLE " . $sTabla . " (saiu40idbasecon int NOT NULL, saiu40idtercero int NOT NULL, saiu40consec int NOT NULL, saiu40id int NULL DEFAULT 0, saiu40fecha int NULL DEFAULT 0, saiu40hora int NULL DEFAULT 0, saiu40min int NULL DEFAULT 0, saiu40vianotifica int NULL DEFAULT 0)";
				$bResultado = $objDB->ejecutasql($sSQL);
				$sSQL = "ALTER TABLE " . $sTabla . " ADD PRIMARY KEY(saiu40id)";
				$bResultado = $objDB->ejecutasql($sSQL);
				$sSQL = "ALTER TABLE " . $sTabla . " ADD UNIQUE INDEX saiu40baseconotifica_id(saiu40idbasecon, saiu40idtercero, saiu40consec)";
				$bResultado = $objDB->ejecutasql($sSQL);
				$sSQL = "ALTER TABLE " . $sTabla . " ADD INDEX saiu40baseconotifica_padre(saiu40idbasecon)";
				$bResultado = $objDB->ejecutasql($sSQL);
				$sSQL = "ALTER TABLE " . $sTabla . " ADD INDEX saiu40baseconotifica_tercero(saiu40idtercero)";
				$bResultado = $objDB->ejecutasql($sSQL);
			}
			$sTabla = 'saiu41docente_' . $iRes;
			$bIniciarContenedor = !$objDB->bexistetabla($sTabla);
			if ($bIniciarContenedor) {
				$sSQL = "CREATE TABLE " . $sTabla . " (saiu41idestudiante int NOT NULL, saiu41consec int NOT NULL, saiu41id int NULL DEFAULT 0, 
				saiu41tipocontacto int NULL DEFAULT 0, saiu41fecha int NULL DEFAULT 0, saiu41cerrada int NULL DEFAULT 0, 
				saiu41idperiodo int NULL DEFAULT 0, saiu41idcurso int NULL DEFAULT 0, saiu41idactividad int NULL DEFAULT 0, 
				saiu41idtutor int NULL DEFAULT 0, saiu41visiblealest int NULL DEFAULT 0, saiu41contacto_efectivo int NULL DEFAULT 0, 
				saiu41contacto_forma int NULL DEFAULT 0, saiu41contacto_observa Text NULL, saiu41seretira int NULL DEFAULT 0, 
				saiu41factorprincipaldesc int NULL DEFAULT 0, saiu41motivocontacto int NULL DEFAULT 0, saiu41acciones int NULL DEFAULT 0, 
				saiu41resultados int NULL DEFAULT 0, saiu41idestprog int NULL DEFAULT 0, saiu41idescuela int NULL DEFAULT 0, 
				saiu41idprograma int NULL DEFAULT 0, saiu41idzona int NULL DEFAULT 0, saiu41idcentro int NULL DEFAULT 0, 
				saiu41tipointeresado int NULL DEFAULT 1, saiu41subtipocontacto int NULL DEFAULT 0)";
				$bResultado = $objDB->ejecutasql($sSQL);
				$sSQL = "ALTER TABLE " . $sTabla . " ADD PRIMARY KEY(saiu41id)";
				$bResultado = $objDB->ejecutasql($sSQL);
				$sSQL = "ALTER TABLE " . $sTabla . " ADD UNIQUE INDEX saiu41docente_id(saiu41idestudiante, saiu41consec)";
				$bResultado = $objDB->ejecutasql($sSQL);
				$sSQL = "ALTER TABLE " . $sTabla . " ADD INDEX saiu41docente_periodo(saiu41idperiodo)";
				$bResultado = $objDB->ejecutasql($sSQL);
				$sSQL = "ALTER TABLE " . $sTabla . " ADD INDEX saiu41docente_curso(saiu41idcurso)";
				$bResultado = $objDB->ejecutasql($sSQL);
				$sSQL = "ALTER TABLE " . $sTabla . " ADD INDEX saiu41docente_tutor(saiu41idtutor)";
				$bResultado = $objDB->ejecutasql($sSQL);
			} else {
				//Agosto 23 de 2022 - Verificamos que exista el campos adicionales.
				$sSQL = 'SELECT saiu41tipointeresado FROM ' . $sTabla . ' LIMIT 0, 1';
				$bResultado = $objDB->ejecutasql($sSQL);
				if ($bResultado == false) {
					$sSQL = "ALTER TABLE " . $sTabla . " ADD saiu41tipointeresado int NULL DEFAULT 1, ADD saiu41subtipocontacto int NULL DEFAULT 0";
					$bResultado = $objDB->ejecutasql($sSQL);
				}
			}
			// Tablas de monitoreo
			$sTabla = 'moni13rapcursoest_' . $iRes;
			$bIniciarContenedor = !$objDB->bexistetabla($sTabla);
			if ($bIniciarContenedor) {
				$sSQL = "CREATE TABLE " . $sTabla . " (moni13idplan int NOT NULL, moni13idrap int NOT NULL, moni13idestudiante int NOT NULL, moni13idcurso int NOT NULL, moni13idperiodo int NOT NULL, moni13idact int NOT NULL, moni13id int NOT NULL DEFAULT 0, moni13puntajemax Decimal(15,2) NULL DEFAULT 0, moni13puntajeobtenido Decimal(15,2) NULL DEFAULT 0)";
				$bResultado = $objDB->ejecutasql($sSQL);
				$sSQL = "ALTER TABLE " . $sTabla . " ADD PRIMARY KEY(moni13id)";
				$bResultado = $objDB->ejecutasql($sSQL);
				$sSQL = $objDB->sSQLCrearIndice($sTabla, 'moni13rapcursoest_id', 'moni13idplan, moni13idrap, moni13idestudiante, moni13idcurso, moni13idperiodo, moni13idact', true);
				$bResultado = $objDB->ejecutasql($sSQL);
				$sSQL = $objDB->sSQLCrearIndice($sTabla, 'moni13rapcursoest_padre', 'moni13idplan');
				$bResultado = $objDB->ejecutasql($sSQL);
			}
			$sTabla = 'moni14totalrapest_' . $iRes;
			$bIniciarContenedor = !$objDB->bexistetabla($sTabla);
			if ($bIniciarContenedor) {
				$sSQL = "CREATE TABLE " . $sTabla . " (moni14idplan int NOT NULL, moni14idrap int NOT NULL, moni14idestudiante int NOT NULL, moni14idcurso int NOT NULL, moni14idperiodo int NOT NULL, moni14id int NOT NULL DEFAULT 0, moni14puntajemax Decimal(15,2) NULL DEFAULT 0, moni14puntajecalificado Decimal(15,2) NULL DEFAULT 0, moni14puntajeobtenido Decimal(15,2) NULL DEFAULT 0, moni14rappeso Decimal(15,2) NULL DEFAULT 0, moni14rappesoobtenido Decimal(15,2) NULL DEFAULT 0)";
				$bResultado = $objDB->ejecutasql($sSQL);
				$sSQL = "ALTER TABLE " . $sTabla . " ADD PRIMARY KEY(moni14id)";
				$bResultado = $objDB->ejecutasql($sSQL);
				$sSQL = $objDB->sSQLCrearIndice($sTabla, 'moni14totalrapest_id', 'moni14idplan, moni14idrap, moni14idestudiante, moni14idcurso, moni14idperiodo', true);
				$bResultado = $objDB->ejecutasql($sSQL);
				$sSQL = $objDB->sSQLCrearIndice($sTabla, 'moni14totalrapest_padre', 'moni14idplan');
				$bResultado = $objDB->ejecutasql($sSQL);
			}
		}
	} else {
		$sError = 'No se ha encontrado el tercero Ref ' . $idTercero . '';
	}
	return array($iRes, $sError);
}
// Funciones de contabilidad
function f1122_NombreFamilia($idFamilia, $objDB, $bHtml = true)
{
	$res = '{' . $idFamilia . '}';
	$sSQL = 'SELECT nico22nombre FROM nico22familia WHERE nico22id=' . $idFamilia;
	$tabla22 = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla22) > 0) {
		$fila22 = $objDB->sf($tabla22);
		if ($bHtml) {
			$res = cadena_notildes($fila22['nico22nombre']);
		} else {
			$res = utf8_decode($fila22['nico22nombre']);
		}
	}
	return $res;
}
// 
function f1527_EsLider($idTercero, $objDB, $bDebug = false)
{
	$bRes = false;
	$sDebug = '';
	$sSQL = 'SELECT bita27id FROM bita27equipotrabajo WHERE bita27idlider=' . $idTercero . '';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$bRes = true;
	}
	return array($bRes, $sDebug);
}
function f1708_TotalEstudiantes($idPeriodo, $idCurso, $objDB, $bDebug = false)
{
	$sDebug = '';
	$iMatricula = 0;
	$iAplazados = 0;
	$iCancelados = 0;
	$aAula = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
	$aAula[1] = 0;
	$sSQL = 'SELECT ofer08id, ofer08estadooferta, ofer08numestudiantes, ofer08numcancelaciones, ofer08numaplazamientos 
	FROM ofer08oferta 
	WHERE ofer08idper_aca=' . $idPeriodo . ' AND ofer08idcurso=' . $idCurso . ' AND ofer08cead=0';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$filaest = $objDB->sf($tabla);
		$id08 = $filaest['ofer08id'];
		if ($filaest['ofer08estadooferta'] == 1) {
			//Ver que matricula hay.
			$sSQL = 'SHOW TABLES LIKE "core04%"';
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . ' Total Periodo: Lista de contenedores: ' . $sSQL . '<br>';
			}
			$tablac = $objDB->ejecutasql($sSQL);
			while ($filac = $objDB->sf($tablac)) {
				$iContenedor = substr($filac[0], 16);
				//Estados que se quitan, 1 - No disponible, 2 Matricula externa, 9 Cancelado, 10 aplazado
				if ($iContenedor != 0) {
					$sSQL = 'SELECT TB.core04idaula, TB.core04estado, COUNT(1) AS Total 
					FROM core04matricula_' . $iContenedor . ' AS TB 
					WHERE TB.core04peraca=' . $idPeriodo . ' AND TB.core04idcurso=' . $idCurso . ' AND TB.core04estado<>1
					GROUP BY TB.core04idaula, TB.core04estado';
					$tabla = $objDB->ejecutasql($sSQL);
					while ($fila = $objDB->sf($tabla)) {
						$iMatricula = $iMatricula + $fila['Total'];
						switch ($fila['core04estado']) {
							case 9: //Cancelados
								$iCancelados = $iCancelados + $fila['Total'];
								break;
							case 10: //Aplazados
								$iAplazados = $iAplazados + $fila['Total'];
								break;
							default:
								$idAula = $fila['core04idaula'];
								if ($idAula == 0) {
									$idAula = 1;
								}
								$aAula[$idAula] = $aAula[$idAula] + $fila['Total'];
								break;
						}
					}
				}
			}
			//Termina de revisar los datos de matricula
		}
		//Ahora cada aula.
		$sSQL = 'SELECT unad48identificador, unad48id, unad48numestudiantes 
		FROM unad48cursoaula 
		WHERE unad48per_aca=' . $idPeriodo . ' AND unad48idcurso=' . $idCurso . '';
		$tablaa = $objDB->ejecutasql($sSQL);
		while ($filaa = $objDB->sf($tablaa)) {
			$iEstAula = -1;
			switch ($filaa['unad48identificador']) {
				case 'A':
					$iEstAula = $aAula[1];
					break;
				case 'B':
					$iEstAula = $aAula[2];
					break;
				case 'C':
					$iEstAula = $aAula[3];
					break;
				case 'D':
					$iEstAula = $aAula[4];
					break;
				case 'E':
					$iEstAula = $aAula[5];
					break;
				case 'F':
					$iEstAula = $aAula[6];
					break;
				case 'G':
					$iEstAula = $aAula[7];
					break;
				case 'I':
					$iEstAula = $aAula[8];
					break;
			}
			if ($iEstAula != -1) {
				if ($iEstAula != $filaa['unad48numestudiantes']) {
					$sSQL = 'UPDATE unad48cursoaula SET unad48numestudiantes=' . $iEstAula . ' WHERE unad48id=' . $filaa['unad48id'] . '';
					$result = $objDB->ejecutasql($sSQL);
				}
			}
		}
		$sSQL = 'SELECT ofer17numaula, ofer17numestudiantes FROM ofer17cargaxnav WHERE ofer17per_aca=' . $idPeriodo . ' AND ofer17curso=' . $idCurso . '';
		$tablaa = $objDB->ejecutasql($sSQL);
		while ($filaa = $objDB->sf($tablaa)) {
			$iEstAula = $aAula[$filaa['ofer17numaula']];
			if ($iEstAula != $filaa['ofer17numestudiantes']) {
				$sSQL = 'UPDATE ofer17cargaxnav SET ofer17numestudiantes=' . $iEstAula . ' WHERE ofer17per_aca=' . $idPeriodo . ' AND ofer17curso=' . $idCurso . ' AND ofer17numaula=' . $filaa['ofer17numaula'] . '';
				$result = $objDB->ejecutasql($sSQL);
			}
		}

		$bEntra = false;
		if ($iMatricula != $filaest['ofer08numestudiantes']) {
			$bEntra = true;
		}
		if ($iCancelados != $filaest['ofer08numcancelaciones']) {
			$bEntra = true;
		}
		if ($iAplazados != $filaest['ofer08numaplazamientos']) {
			$bEntra = true;
		}
		if ($bEntra) {
			$sSQL = 'UPDATE ofer08oferta SET ofer08numestudiantes=' . $iMatricula . ', ofer08numcancelaciones=' . $iCancelados . ', ofer08numaplazamientos=' . $iAplazados . ' WHERE ofer08id=' . $id08 . '';
			$result = $objDB->ejecutasql($sSQL);
		}
	}
	return array($iMatricula, $iAplazados, $iCancelados, $sDebug);
}
function f1708_TotalizarAsignacion($idPeriodo, $idEscuela, $objDB, $bDebug = false)
{
	$sError = '';
	$sDebug = '';
	$sSQL = 'SELECT ofer08id, ofer08idcurso, ofer08numestudiantes, ofer08cargath 
	FROM ofer08oferta 
	WHERE ofer08idper_aca=' . $idPeriodo . ' AND ofer08idescuela=' . $idEscuela . ' AND ofer08cead=0 AND ofer08estadooferta=1';
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' Listando cursos ' . $sSQL . ' <br>';
	}
	$tabla = $objDB->ejecutasql($sSQL);
	while ($fila = $objDB->sf($tabla)) {
		$iAsignados = 0;
		$sSQL = 'SELECT SUM(core20numestudiantes) AS Total 
		FROM core20asignacion
		WHERE core20idcurso=' . $fila['ofer08idcurso'] . ' AND core20idperaca=' . $idPeriodo . ' AND core20idtutor<>0';
		$tabla20 = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla20) > 0) {
			$fila20 = $objDB->sf($tabla20);
			$iAsignados = $fila20['Total'];
		}
		$sSQL = 'UPDATE ofer08oferta SET ofer08cargath=' . $iAsignados . ' WHERE ofer08id=' . $fila['ofer08id'] . '';
		$result = $objDB->ejecutasql($sSQL);
	}
	return array($sError, $sDebug);
}
function f2219_RegistrarTutor($idTercero, $objDB, $bDebug = false, $idZona = 0, $idCentro = 0)
{
	$sError = '';
	$sDebug = '';
	//Revisamos que el tutor exista.
	$sSQL = 'SELECT core19idtercero, core19idzona, core19idsede FROM core19tutores WHERE core19idtercero=' . $idTercero . '';
	$tabla19 = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla19) == 0) {
		//Lo insertamos porque no esta.
		$sSQL = 'INSERT INTO core19tutores (core19idtercero, core19id, core19activo, core19idzona, core19idsede, 
		core19formavincula) VALUES (' . $idTercero . ', ' . $idTercero . ', "S", ' . $idZona . ', ' . $idCentro . ', 0)';
		$tabla19 = $objDB->ejecutasql($sSQL);
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' Insertando tutor ' . $sSQL . ' <br>';
		}
	} else {
		$fila19 = $objDB->sf($tabla19);
		$sInfoCambia = '';
		if ($fila19['core19idzona'] != $idZona) {
			$sInfoCambia = 'core19idzona=' . $idZona . '';
		}
		if ($fila19['core19idsede'] != $idCentro) {
			if ($sInfoCambia != '') {
				$sInfoCambia = $sInfoCambia . ', ';
			}
			$sInfoCambia = $sInfoCambia . 'core19idsede=' . $idCentro . '';
		}
		if ($sInfoCambia != '') {
			$sSQL = 'UPDATE core19tutores SET ' . $sInfoCambia . ' WHERE core19idtercero=' . $idTercero . '';
			$tabla19 = $objDB->ejecutasql($sSQL);
		}
	}
	return array($sError, $sDebug);
}
function f2220_RegistrarAsignacion($idTercero, $idPeriodo, $idCurso, $iNumAsignados, $iNumGrupos, $objDB, $bDebug = false, $bSoloInsertar = false, $bDesdeCarga = false)
{
	$sError = '';
	$sDebug = '';
	$iHoy = fecha_DiaMod();
	if ($idTercero == 0) {
		$sError = 'No hay lugar a asignaci&oacute;n';
	}
	if ($sError == '') {
		if ($iNumAsignados == '') {
			$iNumAsignados = 0;
		}
		$sSQL = 'SELECT core20id, core20numestaplicados, core20numgrupos, core20fechacargaini 
		FROM core20asignacion 
		WHERE core20idtutor=' . $idTercero . ' AND core20idperaca=' . $idPeriodo . ' AND core20idcurso=' . $idCurso . '';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' Consultando el registro de asignacion: ' . $sSQL . ' <br>';
		}
		$tabla20 = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla20) == 0) {
			//Verificamos que exista.
			list($sError, $sDebugT) = f2219_RegistrarTutor($idTercero, $objDB, $bDebug);
			$sDebug = $sDebug . $sDebugT;
			//Lo insertamos porque no esta.
			$core20id = tabla_consecutivo('core20asignacion', 'core20id', '', $objDB);
			$sSQL = 'INSERT INTO core20asignacion (core20idtutor, core20idperaca, core20idcurso, core20id, core20numestudiantes, core20numestaplicados, core20numgrupos, core20fechaingregistro, core20fechaactualiza) 
			VALUES (' . $idTercero . ', ' . $idPeriodo . ', ' . $idCurso . ', ' . $core20id . ', 0, 
			' . $iNumAsignados . ', ' . $iNumGrupos . ', ' . $iHoy . ', ' . $iHoy . ')';
			$tabla20 = $objDB->ejecutasql($sSQL);
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . ' Insertando carga ' . $sSQL . ' <br>';
			}
		} else {
			if (!$bSoloInsertar) {
				//Vemos si la data se actualizo.
				$filabase = $objDB->sf($tabla20);
				$bModifica = false;
				$sInfoCambia = 'core20fechaactualiza=' . $iHoy . '';
				if ($filabase['core20numestaplicados'] != $iNumAsignados) {
					$sInfoCambia = 'core20numestaplicados=' . $iNumAsignados . ', ' . $sInfoCambia;
					$bModifica = true;
				}
				if ($filabase['core20numgrupos'] != $iNumGrupos) {
					$sInfoCambia = 'core20numgrupos=' . $iNumGrupos . ', ' . $sInfoCambia;
					$bModifica = true;
				}
				if ($bDesdeCarga) {
					if ($filabase['core20fechacargaini'] == 0) {
						$sInfoCambia = 'core20fechacargaini=' . $iHoy . ', ' . $sInfoCambia;
						$bModifica = true;
					}
				}
				if ($bModifica) {
					$sSQL = 'UPDATE core20asignacion SET ' . $sInfoCambia . ' WHERE core20id=' . $filabase['core20id'] . '';
					$tabla20 = $objDB->ejecutasql($sSQL);
					if ($bDebug) {
						$sDebug = $sDebug . fecha_microtiempo() . ' Actualizando la carga asignada al tutor ' . $sSQL . ' <br>';
					}
				}
			}
		}
	}
	return array($sError, $sDebug);
}
function f2200_ImportarTutores($idPeriodo, $objDB, $bDebug = false)
{
	list($sError, $sDebug) = f2200_ImportarTutoresV2($idPeriodo, 0, $objDB, $bDebug);
	return array($sError, $sDebug);
}
function f2200_ImportarTutores951($idPeriodo, $idCurso, $objDB, $bDebug = false)
{
	/*
	Este proceso revisa que haya datos en las tablas core19 y core20 que son las tablas de detallar informacion para los tutores.
	*/
	$sError = '';
	$sDebug = '';
	//Saber que contenedor de grupos tiene el periodo.
	$idContenedor = f146_Contenedor($idPeriodo, $objDB);
	if ($idContenedor == 0) {
		$sError = 'No se ha definido un contenedor para los grupos del periodo ' . $idPeriodo;
	}
	if ($sError == '') {
		/*
		SELECT TR.ins_estudiante, T1.cur_materia, T1.grupo, T1.cur_docente 
		FROM registro AS TR, cursos_periodos AS T1 
		WHERE TR.ano=611 AND TR.ins_novedad=79
		AND TR.ins_curso=T1.consecutivo AND T1.cur_edificio<>99
		*/
		$iHoy = fecha_DiaMod();
		$sCondiCurso = '';
		if ($idCurso != 0) {
			$sCondiCurso = ' AND core06idcurso=' . $idCurso . '';
		}
		$sSQL = 'SELECT core06idtutor, core06idcurso  
		FROM core06grupos_' . $idContenedor . '
		WHERE core06peraca=' . $idPeriodo . $sCondiCurso . ' AND core06idtutor<>0
		GROUP BY core06idtutor, core06idcurso
		ORDER BY core06idtutor';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' Listado de carga a verificar ' . $sSQL . ' <br>';
		}
		$tabla = $objDB->ejecutasql($sSQL);
		$idTutor = -1;
		while ($fila = $objDB->sf($tabla)) {
			if ($idTutor != $fila['core06idtutor']) {
				$idTutor = $fila['core06idtutor'];
			}
			if ($idTutor != 0) {
				//El tutor si existe... ahora a revisar que la carga este.
				list($sError, $sDebugA) = f2220_RegistrarAsignacion($idTutor, $idPeriodo, $fila['core06idcurso'], 0, 0, $objDB, $bDebug, true);
				$sDebug = $sDebug . $sDebugA;
			}
		}
		//Una vez alimentados los grupos, se debe actualizar la tabla de oferta.
		$objDBRyC = TraerDBRyC();
		if ($objDBRyC == NULL) {
			/*
			if (false) {
			//Armar la data del los grupos.
			$sSQL='SELECT ofer08id, ofer08idcurso FROM ofer08oferta WHERE ofer08idper_aca='.$idPeriodo.' AND ofer08idacomanamento=0';
			$tabla=$objDB->ejecutasql($sSQL);
			while ($fila=$objDB->sf($tabla)){
				$sSQL='SELECT core06iddirector FROM core06grupos_'.$idContenedor.' WHERE core06peraca='.$idPeriodo.' AND core06idcurso='.$fila['ofer08idcurso'].' AND core06iddirector>0 ORDER BY core06consec LIMIT 0, 1';
				$tabla06=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($tabla06)>0){
					//$fila06=$objDB->sf($tabla06);
					//$sSQL='UPDATE ofer08oferta SET ofer08idacomanamento='.$fila06['core06iddirector'].' WHERE ofer08id='.$fila['ofer08id'].'';
					//$result=$objDB->ejecutasql($sSQL);
					}else{
					//Es posible que el director venga en el grupo 0
					if (false) {
						//Se considera solo registro y control como origen del dato
					$sSQL='SELECT idnumber FROM sw_edu_enrollment_final WHERE cod_curso="'.$fila['ofer08idcurso'].'A_'.$idPeriodo.'" AND role=3 LIMIT 0, 1';
					$tabla06=$objDB->ejecutasql($sSQL);
					if ($objDB->nf($tabla06)>0){
						$fila06=$objDB->sf($tabla06);
						$idTercero=0;
						$sSQL='SELECT unad11id FROM unad11terceros WHERE unad11doc="'.$fila06['idnumber'].'"';
						$tabla11=$objDB->ejecutasql($sSQL);
						if ($objDB->nf($tabla11)>0){
							$fila11=$objDB->sf($tabla11);
							$idTercero=$fila11['unad11id'];
							}
						if ($idTercero!=0){
							$sSQL='UPDATE ofer08oferta SET ofer08idacomanamento='.$idTercero.' WHERE ofer08id='.$fila['ofer08id'].'';
							$result=$objDB->ejecutasql($sSQL);
							}
						}
						}
					}
				}
				}
			*/
		} else {
			//Armamos el acompa;amiento desde ryc
			$aDoc = array();
			$sSQL = 'SELECT documento FROM direccion_academica WHERE peraca=' . $idPeriodo . ' AND id_rol=3 AND codigo_curso=' . $idCurso . ' GROUP BY documento';
			$tablad = $objDBRyC->ejecutasql($sSQL);
			while ($filad = $objDBRyC->sf($tablad)) {
				$sDoc = $filad['documento'];
				$idDirector = 0;
				$sSQL = 'SELECT unad11id FROM unad11terceros WHERE unad11doc="' . $sDoc . '" AND unad11tipodoc="CC"';
				$tabla11 = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($tabla11) == 0) {
					unad11_importar_V2($sDoc, '', $objDB);
					$tabla11 = $objDB->ejecutasql($sSQL);
				}
				if ($objDB->nf($tabla11) > 0) {
					$fila11 = $objDB->sf($tabla11);
					$idDirector = $fila11['unad11id'];
				}
				$aDoc['CC' . $sDoc] = $idDirector;
			}
			$iNoOfertados = 0;
			$sSQL = 'SELECT codigo_curso, documento FROM direccion_academica WHERE peraca=' . $idPeriodo . ' AND id_rol=3 AND codigo_curso=' . $idCurso . '';
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . ' Trayendo datos de acompanamiento desde RYC: ' . $sSQL . '<br>';
			}
			$tablad = $objDBRyC->ejecutasql($sSQL);
			while ($filad = $objDBRyC->sf($tablad)) {
				$sDoc = $filad['documento'];
				$sCurso = $filad['codigo_curso'];
				$idDirector = $aDoc['CC' . $sDoc];
				//Actualizar la oferta de ser necesario.
				$sSQL = 'SELECT ofer08id, ofer08idacomanamento, ofer08idcurso FROM ofer08oferta WHERE ofer08idper_aca=' . $idPeriodo . ' AND ofer08idcurso=' . $sCurso . '';
				$tabla = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($tabla) > 0) {
					$fila = $objDB->sf($tabla);
					if ($fila['ofer08idacomanamento'] != $idDirector) {
						$sSQL = 'UPDATE ofer08oferta SET ofer08idacomanamento=' . $idDirector . ' WHERE ofer08id=' . $fila['ofer08id'] . '';
						$result = $objDB->ejecutasql($sSQL);
						if ($bDebug) {
							$sDebug = $sDebug . fecha_microtiempo() . ' Actualizando dato de acompanamiento [' . $fila['ofer08idcurso'] . ' - ' . $fila['ofer08idacomanamento'] . '] ' . $sSQL . '<br>';
						}
					}
				} else {
					//@@@@ Poseemos problemas un curso que no esta ofertado...
					$iNoOfertados++;
				}
			}
			if ($iNoOfertados > 0) {
				$sError = 'Por favor revise la integraci&oacute;n, se han encontrado ' . $iNoOfertados . ' cursos NO ofertados';
			}
		}
	}
	if ($sError == '') {
	}
	return array($sError, $sDebug);
}
function f2200_ImportarTutoresV2($idPeriodo, $idCurso, $objDB, $bDebug = false, $bForzar = false)
{
	/*
	Este proceso revisa que haya datos en las tablas core19 y core20 que son las tablas de detallar informacion para los tutores.
	// 15 de Abril de 2021 - Este proceso ahora se alimenta de la tabla cort02infoasignacion que es alimentada por Talento Humano.
	--- Octubre 10 de 2023 --- Se agrega a este proceso lo que existe en la cort07comppractico - que sale a ser hermana de la cort02
	*/
	$sError = '';
	$sDebug = '';
	$bAnterior = false;
	if ($idPeriodo < 952) {
		$bAnterior = true;
	} else {
		$bAnterior = f2206th_PeriodosExcluidos($idPeriodo);
	}
	if ($bAnterior) {
		//Se debe enrutar por la funcion anterior
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . 'Se actualiza tutores desde RCA <br>';
		}
		list($sError, $sDebug) = f2200_ImportarTutores951($idPeriodo, $idCurso, $objDB, $bDebug);
		return array($sError, $sDebug);
	}
	//Saber que contenedor de grupos tiene el periodo.
	if ($idCurso == 0) {
		$sError = 'No se permite importar tutores en forma masiva, por favor informar al administrador del sistema.';
	}
	if ($sError == '') {
		$idContenedor = f146_Contenedor($idPeriodo, $objDB);
		if ($idContenedor == 0) {
			$sError = 'No se ha definido un contenedor para los grupos del periodo ' . $idPeriodo;
		}
	}
	if ($sError == '') {
		$iHoy = fecha_DiaMod();
		//Armamos el acompa;amiento desde Talento Humano
		list($idDirector, $sDebugD) = f2205th_DirectorDesdeTH($idPeriodo, $idCurso, $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugD;
		$iNoOfertados = 0;
		$sNoOfertados = '';
		/*
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' Director de curso asignado: ' . $idDirector . '<br>';
		}
		*/
		if ($idDirector != 0) {
			//Actualizar la oferta de ser necesario.
			$sSQL = 'SELECT ofer08id, ofer08idacomanamento FROM ofer08oferta WHERE ofer08idper_aca=' . $idPeriodo . ' AND ofer08idcurso=' . $idCurso . '';
			$tabla = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla) > 0) {
				$fila = $objDB->sf($tabla);
				if ($fila['ofer08idacomanamento'] != $idDirector) {
					$sSQL = 'UPDATE ofer08oferta SET ofer08idacomanamento=' . $idDirector . ' WHERE ofer08id=' . $fila['ofer08id'] . '';
					$result = $objDB->ejecutasql($sSQL);
					if ($bDebug) {
						$sDebug = $sDebug . fecha_microtiempo() . ' Actualizando dato de acompanamiento [' . $idCurso . ' - ' . $fila['ofer08idacomanamento'] . '] ' . $sSQL . '<br>';
					}
				}
				// Reflejar el cambio en la tabla de compartimiento de datoss
				$sSQL = 'UPDATE cort05infodirectores SET cort05sii_fechaproceso=' . $iHoy . ' WHERE cort05idperiodo=' . $idPeriodo . ' AND cort05idcurso=' . $idCurso . ' AND cort05sii_fechaproceso=0 AND cort05tipoasignacion=1';
				$result = $objDB->ejecutasql($sSQL);
			} else {
				// Poseemos problemas, un curso que no esta ofertado...
				$iNoOfertados++;
				if ($sNoOfertados != '') {
					$sNoOfertados = $sNoOfertados . ', ';
				}
				$sNoOfertados = $sNoOfertados . $idCurso;
			}
		}
		for ($m = 1; $m <= 2; $m++) {
			//Ahora si traemos a los tutores.
			if ($m == 1) {
				$sSQL = 'SELECT cort02consec, cort02tipodoc, cort02numerodoc, cort02tipoproceso, cort02numestudiantes, 
				cort02codcentro, cort02sii_id, cort02sii_fechaproceso 
				FROM cort02infoasignacion 
				WHERE cort02idperiodo=' . $idPeriodo . ' AND cort02idcurso=' . $idCurso . '
				ORDER BY cort02consec';
			} else {
				$sSQL = 'SELECT cort07consec AS cort02consec, cort07tipodoc AS cort02tipodoc, cort07numerodoc AS cort02numerodoc, 
				cort07tipoproceso AS cort02tipoproceso, cort07numestudiantes AS cort02numestudiantes, 
				cort07codcentro AS cort02codcentro, cort07sii_id AS cort02sii_id, cort07sii_fechaproceso AS cort02sii_fechaproceso 
				FROM cort07comppractico 
				WHERE cort07idperiodo=' . $idPeriodo . ' AND cort07idcurso=' . $idCurso . '
				ORDER BY cort07consec';
			}
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . ' Trayendo datos de asignacion desde TH: ' . $sSQL . '<br>';
			}
			$tablad = $objDB->ejecutasql($sSQL);
			while ($filad = $objDB->sf($tablad)) {
				if ($bDebug) {
					$sDebug = $sDebug . fecha_microtiempo() . ' Consultando registro: "' . $filad['cort02numerodoc'] . '" - ID - "' . $filad['cort02sii_id'] . '"<br>';
				}
				$idTutor = 0;
				$sErrLinea = '';
				$sTipoDoc = '';
				$sDoc = '';
				$core20numestudiantes = $filad['cort02numestudiantes'];
				$iTipoProceso = $filad['cort02tipoproceso'];
				$bConsultarTutor = false;
				if ((int)$filad['cort02sii_id'] == 0) {
					$bConsultarTutor = true;
				} else {
					if ($bForzar) {
						$bConsultarTutor = true;
					} else {
						//El registro ya fue procesado es decir ya deberia existir.
						$sSQL = 'SELECT core20idtutor FROM core20asignacion WHERE core20id=' . $filad['cort02sii_id'] . '';
						if ($bDebug) {
							$sDebug = $sDebug . fecha_microtiempo() . ' Consultando Registro Procesado : ' . $sSQL . '<br>';
						}
						$tabla20 = $objDB->ejecutasql($sSQL);
						if ($objDB->nf($tabla20) > 0) {
							$fila20 = $objDB->sf($tabla20);
							if ($fila20['core20idtutor'] != 0) {
								$idTutor = $fila20['core20idtutor'];
							} else {
								$bConsultarTutor = true;
							}
						} else {
							$bConsultarTutor = true;
						}
					}
				}
				if ($bConsultarTutor) {
					$sTipoDoc = TH_EquivalenteTipoDoc($filad['cort02tipodoc']);
					$sDoc = trim($filad['cort02numerodoc']);
					$sSQL = 'SELECT unad11id, unad11estado FROM unad11terceros WHERE unad11doc="' . $sDoc . '" AND unad11tipodoc="' . $sTipoDoc . '"';
					if ($bDebug) {
						$sDebug = $sDebug . fecha_microtiempo() . ' Consultando Tutor: ' . $sSQL . '<br>';
					}
					$tabla11 = $objDB->ejecutasql($sSQL);
					if ($objDB->nf($tabla11) == 0) {
						if ($sTipoDoc != 'CC') {
							$sSQL = 'SELECT unad11id, unad11estado FROM unad11terceros WHERE unad11doc="' . $sDoc . '" AND unad11tipodoc="CC"';
							//if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consultando Tutor: '.$sSQL.'<br>';}
							$tabla11 = $objDB->ejecutasql($sSQL);
							if ($objDB->nf($tabla11) == 0) {
								unad11_importar_V2($sDoc, '', $objDB);
								$tabla11 = $objDB->ejecutasql($sSQL);
							}
						} else {
							unad11_importar_V2($sDoc, '', $objDB);
							$tabla11 = $objDB->ejecutasql($sSQL);
						}
					}
					if ($objDB->nf($tabla11) > 0) {
						$fila11 = $objDB->sf($tabla11);
						if ($fila11['unad11estado'] == 0) {
							$idTutor = $fila11['unad11id'];
						} else {
							// Junio 26 de 2023 - Se ajusta problema de tutor
							// Lo que esta sucediendo es que el tutor puede llegar con una CE y resulta que el usuario activo es una CC
							if ($sTipoDoc != 'CC') {
								$sSQL = 'SELECT unad11id, unad11estado FROM unad11terceros WHERE unad11doc="' . $sDoc . '" AND unad11tipodoc="CC"';
								$tabla11 = $objDB->ejecutasql($sSQL);
								if ($objDB->nf($tabla11) > 0) {
									$fila11 = $objDB->sf($tabla11);
									if ($fila11['unad11estado'] == 0) {
										$idTutor = $fila11['unad11id'];
									} else {
										$sErrLinea = 'El usuario CC ' . $sDoc . ' NO SE ENCUENTRA ACTIVO';
									}
								} else {
									$sErrLinea = 'No se ha encontrado el tutor ' . $sTipoDoc . '' . $sDoc . '';
								}
							} else {
								$sErrLinea = 'El usuario CC ' . $sDoc . ' NO SE ENCUENTRA ACTIVO';
							}
						}
					} else {
						$sErrLinea = 'No se ha encontrado el tutor ' . $sTipoDoc . '' . $sDoc . '';
					}
					switch ($iTipoProceso) {
						case 1: //Asignacion.
							$core20numestudiantes = $filad['cort02numestudiantes'];
							break;
						case 2: //Cambio de asignacion
						case 3: //Retiro de asignacion
							if ($iTipoProceso == 2) {
								$core20numestudiantes = $filad['cort02numestudiantes'];
							} else {
								$core20numestudiantes = 0;
							}
							$sSQL = 'SELECT core20id, core20numestudiantes 
							FROM core20asignacion 
							WHERE core20idtutor=' . $idTutor . ' AND core20idperaca=' . $idPeriodo . ' AND core20idcurso=' . $idCurso . '';
							if ($bDebug) {
								$sDebug = $sDebug . fecha_microtiempo() . ' Consultando el registro de asignacion: ' . $sSQL . ' <br>';
							}
							$tabla20 = $objDB->ejecutasql($sSQL);
							if ($objDB->nf($tabla20) > 0) {
								$filabase = $objDB->sf($tabla20);
								$core20id = $filabase['core20id'];
								$sInfoCambia = 'core20numestudiantes=' . $core20numestudiantes . '';
								$sSQL = 'UPDATE core20asignacion SET ' . $sInfoCambia . ' WHERE core20id=' . $core20id . '';
								$result = $objDB->ejecutasql($sSQL);
								if ($bDebug) {
									$sDebug = $sDebug . fecha_microtiempo() . ' Actualizando la carga asignada al tutor ' . $sSQL . ' <br>';
								}
								if ((int)$filad['cort02sii_id'] == 0) {
									if ($m == 1) {
										$sSQL = 'UPDATE cort02infoasignacion SET cort02sii_id=' . $core20id . ', cort02sii_fechaproceso=' . $iHoy . ' WHERE cort02consec=' . $filad['cort02consec'] . '';
									} else {
										$sSQL = 'UPDATE cort07comppractico SET cort07sii_id=' . $core20id . ', cort07sii_fechaproceso=' . $iHoy . ' WHERE cort07consec=' . $filad['cort02consec'] . '';
									}
									$result = $objDB->ejecutasql($sSQL);
									if ($bDebug) {
										$sDebug = $sDebug . fecha_microtiempo() . ' Actualizando modificaci&oacute;n de carga asignada al tutor ' . $sSQL . ' <br>';
									}
								}
							}
							$idTutor = 0;
							//Solo actualizamos la asignacion directamente y ya.
							break;
						case 4: //Cambio de centro, dejamos quieta la asignacion.
							$sSQL = 'SELECT core20id, core20numestudiantes 
							FROM core20asignacion 
							WHERE core20idtutor=' . $idTutor . ' AND core20idperaca=' . $idPeriodo . ' AND core20idcurso=' . $idCurso . '';
							if ($bDebug) {
								$sDebug = $sDebug . fecha_microtiempo() . ' Consultando el registro de asignacion: ' . $sSQL . ' <br>';
							}
							$tabla20 = $objDB->ejecutasql($sSQL);
							if ($objDB->nf($tabla20) > 0) {
								$filabase = $objDB->sf($tabla20);
								$core20id = $filabase['core20id'];
								$core20numestudiantes = $filabase['core20numestudiantes'];
							}
							break;
					}
				}
				if ($idTutor != 0) {
					if ($sErrLinea == '') {
						//if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Actualizando datos del tutor: '.$sSQL.'<br>';}
						//Ubicar el centro y la zona
						$sSQL = 'SELECT unad24id, unad24idzona FROM unad24sede WHERE unad24codigoryc=' . $filad['cort02codcentro'] . '';
						$tabla = $objDB->ejecutasql($sSQL);
						if ($objDB->nf($tabla) > 0) {
							$fila = $objDB->sf($tabla);
							$core20idzona = $fila['unad24idzona'];
							$core20idcentro = $fila['unad24id'];
						} else {
							//$idTutor=0;
							$core20idzona = 0;
							$core20idcentro = 0;
							if ($bDebug) {
								$sDebug = $sDebug . fecha_microtiempo() . ' <span class="rojo">No se ha podido definir el centro ' . $filad['cort02codcentro'] . ' para ' . $sTipoDoc . '' . $sDoc . '</span> <br>';
							}
							//$sErrLinea='No se podido definir el centro '.$filad['cort02codcentro'].' para '.$sTipoDoc.''.$sDoc.'';
						}
					}
				}
				if ($sErrLinea == '') {
					if ($idTutor != 0) {
						//Verificamos que exista.
						list($sErrLinea, $sDebugT) = f2219_RegistrarTutor($idTutor, $objDB, $bDebug, $core20idzona, $core20idcentro);
						$sDebug = $sDebug . $sDebugT;
					}
				}
				if ($sErrLinea == '') {
					//El proceso que sigue es como se hacia en f2220_RegistrarAsignacion pero con toda la data.
					$sSQL = 'SELECT core20id, core20numestudiantes, core20idzona, core20idcentro 
					FROM core20asignacion 
					WHERE core20idtutor=' . $idTutor . ' AND core20idperaca=' . $idPeriodo . ' AND core20idcurso=' . $idCurso . '';
					if ($bDebug) {
						$sDebug = $sDebug . fecha_microtiempo() . ' Consultando el registro de asignacion: ' . $sSQL . ' <br>';
					}
					$tabla20 = $objDB->ejecutasql($sSQL);
					if ($objDB->nf($tabla20) == 0) {
						//Lo insertamos porque no esta.
						$core20id = tabla_consecutivo('core20asignacion', 'core20id', '', $objDB);
						$sSQL = 'INSERT INTO core20asignacion (core20idtutor, core20idperaca, core20idcurso, core20id, core20numestudiantes, 
						core20numestaplicados, core20numgrupos, core20fechaingregistro, core20fechaactualiza, core20idzona, 
						core20idcentro) 
						VALUES (' . $idTutor . ', ' . $idPeriodo . ', ' . $idCurso . ', ' . $core20id . ', ' . $core20numestudiantes . ', 
						0, 0, ' . $iHoy . ', ' . $iHoy . ', ' . $core20idzona . ', 
						' . $core20idcentro . ')';
						$tabla20 = $objDB->ejecutasql($sSQL);
						if ($bDebug) {
							$sDebug = $sDebug . fecha_microtiempo() . ' Insertando carga ' . $sSQL . ' <br>';
						}
					} else {
						$filabase = $objDB->sf($tabla20);
						$core20id = $filabase['core20id'];
						$bModifica = false;
						$sInfoCambia = 'core20fechaactualiza=' . $iHoy . '';
						$sInfo19 = '';
						switch ($iTipoProceso) {
							case 1: //Asignacion.
								if ($filabase['core20numestudiantes'] != $core20numestudiantes) {
									$sInfoCambia = 'core20numestudiantes=' . $core20numestudiantes . ', ' . $sInfoCambia;
									$bModifica = true;
								}
								break;
						}
						if ($filabase['core20idzona'] != $core20idzona) {
							$sInfoCambia = 'core20idzona=' . $core20idzona . ', ' . $sInfoCambia;
							$bModifica = true;
						}
						if ($filabase['core20idcentro'] != $core20idcentro) {
							$sInfoCambia = 'core20idcentro=' . $core20idcentro . ', ' . $sInfoCambia;
							$bModifica = true;
						}
						if ($bModifica) {
							$sSQL = 'UPDATE core20asignacion SET ' . $sInfoCambia . ' WHERE core20id=' . $core20id . '';
							$result = $objDB->ejecutasql($sSQL);
							if ($bDebug) {
								$sDebug = $sDebug . fecha_microtiempo() . ' Actualizando la carga asignada al tutor ' . $sSQL . ' <br>';
							}
						}
					}
					//El dato ya esta procesado ahora actualizarlo en TH
					if ((int)$filad['cort02sii_id'] == 0) {
						if ($m == 1) {
							$sSQL = 'UPDATE cort02infoasignacion SET cort02sii_id=' . $core20id . ', cort02sii_fechaproceso=' . $iHoy . ' WHERE cort02consec=' . $filad['cort02consec'] . '';
						} else {
							$sSQL = 'UPDATE cort07comppractico SET cort07sii_id=' . $core20id . ', cort07sii_fechaproceso=' . $iHoy . ' WHERE cort07consec=' . $filad['cort02consec'] . '';
						}
						$result = $objDB->ejecutasql($sSQL);
						if ($bDebug) {
							$sDebug = $sDebug . fecha_microtiempo() . ' Actualizando la carga asignada al tutor ' . $sSQL . ' <br>';
						}
					}
				}
				if ($sErrLinea != '') {
					if ($sError != '') {
						$sError = $sError . '<br>';
					}
					$sError = $sError . $sErrLinea;
				}
			}
		}
		// --- Fin de cargar un tutor de la 02 0 LA 07
		if ($iNoOfertados > 0) {
			$sError = 'Por favor revise la integraci&oacute;n, se han encontrado ' . $iNoOfertados . ' cursos NO ofertados (' . $sNoOfertados . ')';
		}
	}
	if ($sError != '') {
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' <span class="rojo">NO SE PUEDE ACTUALIZAR </span> Error:' . $sError . ' <br>';
		}
	}
	return array($sError, $sDebug);
}
function f2200_IgualarPracticas($objDB, $bDebug = false)
{
	//los que quitaron la practica // AND T9.core09idtipopractica=0
	$sSQL = 'UPDATE core01estprograma AS TB, core09programa AS T9
	SET TB.core01estadopractica=-1, TB.core01idtipopractica=0
	WHERE TB.core01estadopractica=0 AND TB.core01idprograma=T9.core09id';
	$tabla = $objDB->ejecutasql($sSQL);
	//Actualizar los que tienen.
	$sSQL = 'UPDATE core01estprograma AS TB, core09programa AS T9
	SET TB.core01estadopractica=0, TB.core01idtipopractica=T9.core09idtipopractica
	WHERE TB.core01estadopractica IN (-1, 0) AND TB.core01idestado=0 AND TB.core01idprograma=T9.core09id AND T9.core09idtipopractica>0 AND TB.core01idtipopractica<>T9.core09idtipopractica';
	$tabla = $objDB->ejecutasql($sSQL);
}
//Esta funcion se usa para mantenimiento
function f2204_RevisarEstructura($objDB, $bDebug = false)
{
	$sError = '';
	$sDebug = '';
	$sSQL = 'SHOW TABLES LIKE "core04%"';
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' Total Periodo: Lista de contenedores: ' . $sSQL . '<br>';
	}
	$tablac = $objDB->ejecutasql($sSQL);
	while ($filac = $objDB->sf($tablac)) {
		$iContenedor = substr($filac[0], 16);
		if ($iContenedor != 0) {
			$sSQL = 'ALTER TABLE core04matricula_' . $iContenedor . ' ADD core04idgrupoinicial int NULL DEFAULT 0';
			$result = $objDB->ejecutasql($sSQL);
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . ' Creando campo Grupo Inicial: ' . $sSQL . '<br>';
			}
		}
	}
	return array($sError, $sDebug);
}
// ----
function f2205_ArmarAgendaCursoEstudiante($idPeriodo, $idTercero, $idCurso, $objDB, $idContTercero = 0, $bDebug = false)
{
	$sError = '';
	$sErrCurso = '';
	$sDebug = '';
	$sCampos05 = 'core05idmatricula, core05idfase, core05idunidad, core05idactividad, core05id, 
	core05peraca, core05tercero, core05idcurso, core05idaula, core05idgrupo, 
	core05idnav, core05fechaapertura, core05fechacierre, core05fecharetro, core05idtutor, 
	core05tipoactividad, core05puntaje75, core05puntaje25, core05nota, core05fechanota, 
	core05acumula75, core05acumula25, core05retroalimentacion, core05estado, core05rezagado';
	$sValores05 = '';
	if ($idContTercero == 0) {
		list($idContTercero, $sError) = f1011_BloqueTercero($idTercero, $objDB);
	}
	if ($sError == '') {
		$iHoy = fecha_DiaMod();
		$sTabla04 = 'core04matricula_' . $idContTercero;
		$sTabla05 = 'core05actividades_' . $idContTercero;
		$core05id = tabla_consecutivo($sTabla05, 'core05id', '', $objDB);
		$sSQL4 = 'SELECT TB.core04id, TB.core04idaula, TB.core04idagenda, TB.core04cursoequivalente, TB.core04idgrupo, 
		TB.core04idtutor, TB.core04idnav, TB.core04idmoodle, T40.unad40modocalifica, T40.unad40tipoexterno 
		FROM ' . $sTabla04 . ' AS TB, unad40curso AS T40 
		WHERE TB.core04tercero=' . $idTercero . ' AND TB.core04peraca=' . $idPeriodo . ' AND TB.core04idcurso=' . $idCurso . ' AND TB.core04aplicoagenda=0 AND TB.core04idcurso=T40.unad40id';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' Matricula del curso. ' . $sSQL4 . '<br>';
		}
		$tabla04 = $objDB->ejecutasql($sSQL4);
		while ($fila04 = $objDB->sf($tabla04)) {
			$sCodRevisa = $idCurso;
			if ($fila04['core04cursoequivalente'] != 0) {
				$sCodRevisa = $fila04['core04cursoequivalente'];
			}
			$bEsExterno = false;
			//$bTipoCertificacion=false;
			if ($fila04['unad40modocalifica'] == 9) {
				$bEsExterno = true;
				if ($fila04['unad40tipoexterno'] == 2) {
					//Las certificaciones no son externas...
					$bEsExterno = false;
					//$bTipoCertificacion=true;
				}
			}
			if ($bEsExterno) {
				//Es un curso externo, por tanto se maneja la agenda de otra forma.
				$ofer08idagenda = -98;
				$ofer08idnav = 0;
				$core04idmoodle = 0;
				$sSQL = 'UPDATE ' . $sTabla04 . ' SET core04idagenda=' . $ofer08idagenda . ', core04idnav=' . $ofer08idnav . ', core04idmoodle=' . $core04idmoodle . ' WHERE core04id=' . $fila04['core04id'] . '';
				$result = $objDB->ejecutasql($sSQL);
			} else {
				if ($fila04['core04idagenda'] == 0) {
					//Encontrar la agenda que le corresponda.
					$ofer08idagenda = 0;
					$ofer08idnav = 0;
					$core04idmoodle = 0;
					$sSQL = 'SELECT ofer08estadooferta, ofer08estadocampus, ofer08idagenda, ofer08idnav, ofer08idcursonav, ofer08idescuela FROM ofer08oferta WHERE ofer08idcurso="' . $sCodRevisa . '" AND ofer08idper_aca=' . $idPeriodo . ' AND ofer08cead=0';
					$tabla08 = $objDB->ejecutasql($sSQL);
					if ($objDB->nf($tabla08) > 0) {
						$fila = $objDB->sf($tabla08);
						if ($bDebug) {
							$sDebug = $sDebug . fecha_microtiempo() . ' Cargando oferta del curso ' . $sCodRevisa . '<br>';
						}
						if ($fila['ofer08estadooferta'] == 1) {
							switch ($fila['ofer08estadocampus']) {
								case 10: //Acreditado
								case 12: //Certificado
								case 13: //Validado
								case 16: //Excepción de Alistamiento.
									if ($fila['ofer08idagenda'] != 0) {
										$ofer08idagenda = $fila['ofer08idagenda'];
										$ofer08idnav = $fila['ofer08idnav'];
										$core04idmoodle = $fila['ofer08idcursonav'];
									} else {
										$sErrCurso = 'Curso Sin Agenda Asignada';
									}
									break;
								default:
									$sErrCurso = 'Estado oferta: En Certificaci&oacute;n';
									break;
							}
						} else {
							$sErrCurso = 'Estado oferta: Cancelado';
						}
					}
					if ($ofer08idagenda != 0) {
						$sSQL = 'UPDATE ' . $sTabla04 . ' SET core04idagenda=' . $ofer08idagenda . ', core04idnav=' . $ofer08idnav . ', core04idmoodle=' . $core04idmoodle . ' WHERE core04id=' . $fila04['core04id'] . '';
						$result = $objDB->ejecutasql($sSQL);
					}
				} else {
					$ofer08idagenda = $fila04['core04idagenda'];
					$ofer08idnav = $fila04['core04idnav'];
					$core04idmoodle = $fila04['core04idmoodle'];
				}
			}
			if ($sErrCurso == '') {
				$idGrupo = $fila04['core04idgrupo'];
				//Llega con id de agenda. tenemos que saber el aula.... para saber el aula debe tener grupo...
				if ($idGrupo == 0) {
					$sErrCurso = 'Sin grupo';
				}
			}
			if ($sErrCurso == '') {
				$bHayAgenda = true;
				if ($ofer08idagenda == -98) {
					$bHayAgenda = false;
				}
				//if ($bTipoCertificacion){$bHayAgenda=false;}
				if ($bHayAgenda) {
					$core04idaula = $fila04['core04idaula'];
					if ($core04idaula == 0) {
						//Puede que el aula este en 0 aún cuando el curso tenga grupo, 
						//@@ en ese caso toca buscar el grupo y ponerle al aula.
						$core04idaula = 1;
						$sSQL = 'UPDATE ' . $sTabla04 . ' SET core04idaula=' . $core04idaula . ' WHERE core04id=' . $fila04['core04id'] . '';
						$result = $objDB->ejecutasql($sSQL);
					}
					//Traer la agenda
					$sValores05 = '';
					$sSQL = 'SELECT TB.ofer18fase, TB.ofer18unidad, TB.ofer18idactividad, 
					CONCAT(SUBSTR(TB.ofer18fechainicio, 7, 4), SUBSTR(TB.ofer18fechainicio, 4, 2), SUBSTR(TB.ofer18fechainicio, 1, 2)) AS FechaIni, 
					CONCAT(SUBSTR(TB.ofer18fechacierrre, 7, 4), SUBSTR(TB.ofer18fechacierrre, 4, 2), SUBSTR(TB.ofer18fechacierrre, 1, 2)) AS FechaCierre, 
					CONCAT(SUBSTR(TB.ofer18fecharetro, 7, 4), SUBSTR(TB.ofer18fecharetro, 4, 2), SUBSTR(TB.ofer18fecharetro, 1, 2)) AS FechaRetro, 
					TB.ofer18peso, TB.ofer18detalle, T4.ofer04idtipoactividad
					FROM ofer18agendaoferta AS TB, ofer04cursoactividad AS T4 
					WHERE TB.ofer18curso=' . $sCodRevisa . ' AND TB.ofer18per_aca=' . $idPeriodo . ' AND TB.ofer18numaula=' . $core04idaula . ' AND TB.ofer18idactividad=T4.ofer04id';
					if ($bDebug) {
						$sDebug = $sDebug . fecha_microtiempo() . ' Consultando agenda a insertar ' . $sSQL . '<br>';
					}
					$tabla05 = $objDB->ejecutasql($sSQL);
					while ($fila05 = $objDB->sf($tabla05)) {
						$core05tipoactividad = $fila05['ofer04idtipoactividad'];
						if ($sValores05 != '') {
							$sValores05 = $sValores05 . ', ';
						}
						$sValores05 = $sValores05 . '(' . $fila04['core04id'] . ', ' . $fila05['ofer18fase'] . ', ' . $fila05['ofer18unidad'] . ', ' . $fila05['ofer18idactividad'] . ', ' . $core05id . ', 
						' . $idPeriodo . ', ' . $idTercero . ', ' . $idCurso . ', ' . $fila04['core04idaula'] . ', ' . $fila04['core04idgrupo'] . ', 
						' . $ofer08idnav . ', ' . $fila05['FechaIni'] . ', ' . $fila05['FechaCierre'] . ', ' . $fila05['FechaRetro'] . ', ' . $fila04['core04idtutor'] . ', 
						' . $core05tipoactividad . ', 0, 0, 0, 0, 
						0, 0, "", 0, 0)';
						$core05id++;
					}
					if ($sValores05 != '') {
						$sSQL = 'INSERT INTO ' . $sTabla05 . '(' . $sCampos05 . ') VALUES ' . $sValores05 . '';
						if ($bDebug) {
							$sDebug = $sDebug . fecha_microtiempo() . ' Insertando agenda <b>' . $sSQL . '</b><br>';
						}
						$result = $objDB->ejecutasql($sSQL);
						if ($result == false) {
							$sErrCurso = 'Falla al intentar guardar la agenda';
							if ($bDebug) {
								$sDebug = $sDebug . fecha_microtiempo() . ' Error insertando agenda <b>' . $sSQL . '</b><br>';
							}
						} else {
							//Actualizar la fila04
							$sSQL = 'UPDATE ' . $sTabla04 . ' SET core04aplicoagenda=' . $iHoy . ' WHERE core04id=' . $fila04['core04id'] . '';
							$result = $objDB->ejecutasql($sSQL);
						}
					}
					//Fin de si la agenda no es externa...
				} else {
					//Limpiamos la casa.
					$sSQL = 'DELETE FROM ' . $sTabla05 . ' WHERE core05idmatricula=' . $fila04['core04id'] . '';
					if ($bDebug) {
						$sDebug = $sDebug . fecha_microtiempo() . ' <b>NO SE USA AGENDA </b>' . $sSQL . '<br>';
					}
					$result = $objDB->ejecutasql($sSQL);
					//$sSQL='UPDATE '.$sTabla04.' SET core04aplicoagenda='.$iHoy.' WHERE core04id='.$fila04['core04id'].'';
					//$result=$objDB->ejecutasql($sSQL);
				}
			}
		}
	}
	return array($sError, $sErrCurso, $sDebug);
}
function f2205_ArreglarAgendaCursoEstudiante($idPeriodo, $idCurso, $objDB, $bDebug = false)
{
	$sError = '';
	$sErrCurso = '';
	$sDebug = '';
	$sSQL4 = '';
	$iActividadesProc = 0;
	set_time_limit(0);
	$sCampos05 = 'core05idmatricula, core05idfase, core05idunidad, core05idactividad, core05id, 
	core05peraca, core05tercero, core05idcurso, core05idaula, core05idgrupo, 
	core05idnav, core05fechaapertura, core05fechacierre, core05fecharetro, core05idtutor, 
	core05tipoactividad, core05puntaje75, core05puntaje25, core05nota, core05fechanota, 
	core05acumula75, core05acumula25, core05retroalimentacion, core05estado, core05rezagado';
	// Las agendas ya existen por lo que hay que traer la información de los estudiantes
	$iHoy = fecha_DiaMod();
	$sSQL = 'SHOW TABLES LIKE "core04%"';
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' Total Periodo: Lista de contenedores: ' . $sSQL . '<br>';
	}
	$tablac = $objDB->ejecutasql($sSQL);
	while ($filac = $objDB->sf($tablac)) {
		$idContTercero = 0;
		$sTabla04 = substr($filac[0], 16);
		$sSQL4 = 'SELECT TB.core04id, TB.core04tercero, TB.core04idaula, TB.core04idagenda, TB.core04cursoequivalente, 
		TB.core04idgrupo, TB.core04idtutor, TB.core04idnav, TB.core04idmoodle, T40.unad40modocalifica, 
		T40.unad40tipoexterno 
		FROM core04matricula_' . $sTabla04 . ' AS TB, unad40curso AS T40 
		WHERE TB.core04peraca=' . $idPeriodo . ' AND TB.core04idcurso=' . $idCurso . ' AND TB.core04idcurso=T40.unad40id
		ORDER BY TB.core04idaula';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' Matricula del curso: ' . $sSQL4 . ' - Tabla: ' . $sTabla04 . '<br>';
		}
		$tabla04 = $objDB->ejecutasql($sSQL4);
		if ($objDB->nf($tabla04) > 0) {
			while ($fila04 = $objDB->sf($tabla04)) {
				if ($idContTercero == 0) {
					list($idContTercero, $sError) = f1011_BloqueTercero($fila04['core04tercero'], $objDB);
				}
				$sTabla05 = 'core05actividades_' . $idContTercero;
				if ($bDebug) {
					$sDebug = $sDebug . fecha_microtiempo() . ' Contenedor del tercero: ' . $fila04['core04tercero'] . ' y la tabla core05: ' . $sTabla05 . '<br>';
				}
				$core05id = tabla_consecutivo($sTabla05, 'core05id', '', $objDB);
				$sCodRevisa = $idCurso;
				if ($fila04['core04cursoequivalente'] != 0) {
					$sCodRevisa = $fila04['core04cursoequivalente'];
				}
				$bEsExterno = false;
				if ($fila04['unad40modocalifica'] == 9) {
					$bEsExterno = true;
					if ($fila04['unad40tipoexterno'] == 2) {
						//Las certificaciones no son externas...
						$bEsExterno = false;
					}
				}
				if ($bEsExterno) {
					//Es un curso externo, por tanto se maneja la agenda de otra forma.
					$ofer08idagenda = -98;
					$ofer08idnav = 0;
					$core04idmoodle = 0;
					$sSQL = 'UPDATE core04matricula_' . $sTabla04 . ' SET core04idagenda=' . $ofer08idagenda . ', core04idnav=' . $ofer08idnav . ', core04idmoodle=' . $core04idmoodle . ' WHERE core04id=' . $fila04['core04id'] . '';
					if ($bDebug) {
						$sDebug = $sDebug . fecha_microtiempo() . ' Ajustando curso externo: ' . $sSQL . '<br>';
					}
					$result = $objDB->ejecutasql($sSQL);
				} else {
					if ($fila04['core04idagenda'] == 0) {
						//Encontrar la agenda que le corresponda.
						$ofer08idagenda = 0;
						$ofer08idnav = 0;
						$core04idmoodle = 0;
						$sSQL = 'SELECT ofer08estadooferta, ofer08estadocampus, ofer08idagenda, ofer08idnav, ofer08idcursonav, ofer08idescuela FROM ofer08oferta WHERE ofer08idcurso="' . $sCodRevisa . '" AND ofer08idper_aca=' . $idPeriodo . ' AND ofer08cead=0';
						if ($bDebug) {
							$sDebug = $sDebug . fecha_microtiempo() . ' Buscando en la oferta: ' . $sSQL . '<br>';
						}
						$tabla08 = $objDB->ejecutasql($sSQL);
						if ($objDB->nf($tabla08) > 0) {
							$fila = $objDB->sf($tabla08);
							if ($bDebug) {
								$sDebug = $sDebug . fecha_microtiempo() . ' Cargando oferta del curso: ' . $sCodRevisa . '<br>';
							}
							if ($fila['ofer08estadooferta'] == 1) {
								switch ($fila['ofer08estadocampus']) {
									case 10: //Acreditado
									case 12: //Certificado
									case 13: //Validado
									case 16: //Excepción de Alistamiento.
										if ($fila['ofer08idagenda'] != 0) {
											$ofer08idagenda = $fila['ofer08idagenda'];
											$ofer08idnav = $fila['ofer08idnav'];
											$core04idmoodle = $fila['ofer08idcursonav'];
										} else {
											$sErrCurso = 'Curso Sin Agenda Asignada';
										}
										break;
									default:
										$sErrCurso = 'Estado oferta: En Certificaci&oacute;n';
										break;
								}
							} else {
								$sErrCurso = 'Estado oferta: Cancelado';
							}
						}
						if ($ofer08idagenda != 0) {
							$sSQL = 'UPDATE core04matricula_' . $sTabla04 . ' SET core04idagenda=' . $ofer08idagenda . ', core04idnav=' . $ofer08idnav . ', core04idmoodle=' . $core04idmoodle . ' WHERE core04id=' . $fila04['core04id'] . '';
							if ($bDebug) {
								$sDebug = $sDebug . fecha_microtiempo() . ' Ajustando informacion del curso:  ' . $sSQL . '<br>';
							}
							$result = $objDB->ejecutasql($sSQL);
						}
					} else {
						$ofer08idagenda = $fila04['core04idagenda'];
						$ofer08idnav = $fila04['core04idnav'];
						$core04idmoodle = $fila04['core04idmoodle'];
					}
				}
				if ($bDebug) {
					$sDebug = $sDebug . fecha_microtiempo() . ' Grupo:  ' . $fila04['core04idgrupo'] . '<br>';
				}
				if ($sErrCurso == '') {
					$idGrupo = $fila04['core04idgrupo'];
					//Llega con id de agenda. tenemos que saber el aula.... para saber el aula debe tener grupo...
					if ($idGrupo == 0) {
						$sErrCurso = 'Agenda: ' . $fila04['core04id'] . ' - Sin grupo';
					}
				}
				if ($sErrCurso == '') {
					$bHayAgenda = true;
					if ($ofer08idagenda == -98) {
						$bHayAgenda = false;
					}
					if ($bHayAgenda) {
						$core04idaula = $fila04['core04idaula'];
						if ($core04idaula == 0) {
							//Puede que el aula este en 0 aún cuando el curso tenga grupo, 
							//@@ en ese caso toca buscar el grupo y ponerle al aula.
							$core04idaula = 1;
							$sSQL = 'UPDATE ' . $sTabla04 . ' SET core04idaula=' . $core04idaula . ' WHERE core04id=' . $fila04['core04id'] . '';
							if ($bDebug) {
								$sDebug = $sDebug . fecha_microtiempo() . ' Ajustando aula del curso: ' . $sSQL . '<br>';
							}
							$result = $objDB->ejecutasql($sSQL);
						}
						//Traer la agenda
						$sValores05 = '';
						$acore05actividades = array();
						$sSQL5 = 'SELECT core05idactividad 
						FROM ' . $sTabla05 . ' 
						WHERE core05peraca=' . $idPeriodo . ' AND core05idcurso=' . $idCurso . ' 
						AND core05tercero=' . $fila04['core04tercero'];
						if ($bDebug) {
							$sDebug = $sDebug . fecha_microtiempo() . ' Consultando actividades en la agenda del estudiante ' . $sSQL5 . '<br>';
						}
						$tabla5est = $objDB->ejecutasql($sSQL5);
						while ($fila5est = $objDB->sf($tabla5est)) {
							$acore05actividades[$fila5est['core05idactividad']] = $fila5est['core05idactividad'];
						}
						$sSQL = 'SELECT TB.ofer18fase, TB.ofer18unidad, TB.ofer18idactividad, 
						CONCAT(SUBSTR(TB.ofer18fechainicio, 7, 4), SUBSTR(TB.ofer18fechainicio, 4, 2), SUBSTR(TB.ofer18fechainicio, 1, 2)) AS FechaIni, 
						CONCAT(SUBSTR(TB.ofer18fechacierrre, 7, 4), SUBSTR(TB.ofer18fechacierrre, 4, 2), SUBSTR(TB.ofer18fechacierrre, 1, 2)) AS FechaCierre, 
						CONCAT(SUBSTR(TB.ofer18fecharetro, 7, 4), SUBSTR(TB.ofer18fecharetro, 4, 2), SUBSTR(TB.ofer18fecharetro, 1, 2)) AS FechaRetro, 
						TB.ofer18peso, TB.ofer18detalle, T4.ofer04idtipoactividad
						FROM ofer18agendaoferta AS TB, ofer04cursoactividad AS T4 
						WHERE TB.ofer18curso=' . $sCodRevisa . ' AND TB.ofer18per_aca=' . $idPeriodo . ' AND TB.ofer18numaula=' . $core04idaula . ' AND TB.ofer18idactividad=T4.ofer04id';
						if ($bDebug) {
							$sDebug = $sDebug . fecha_microtiempo() . ' Consultando agenda a insertar ' . $sSQL . '<br>';
						}
						$tabla05 = $objDB->ejecutasql($sSQL);
						while ($fila05 = $objDB->sf($tabla05)) {
							if (isset($acore05actividades[$fila05['ofer18idactividad']]) == 0) {
								$core05tipoactividad = $fila05['ofer04idtipoactividad'];
								if ($sValores05 != '') {
									$sValores05 = $sValores05 . ', ';
								}
								$sValores05 = $sValores05 . '(' . $fila04['core04id'] . ', ' . $fila05['ofer18fase'] . ', ' . $fila05['ofer18unidad'] . ', ' . $fila05['ofer18idactividad'] . ', ' . $core05id . ', 
								' . $idPeriodo . ', ' . $fila04['core04tercero'] . ', ' . $idCurso . ', ' . $fila04['core04idaula'] . ', ' . $fila04['core04idgrupo'] . ', 
								' . $ofer08idnav . ', ' . $fila05['FechaIni'] . ', ' . $fila05['FechaCierre'] . ', ' . $fila05['FechaRetro'] . ', ' . $fila04['core04idtutor'] . ', 
								' . $core05tipoactividad . ', 0, 0, 0, 0, 
								0, 0, "", 0, 0)';
								$core05id++;
								$iActividadesProc++;
							}
						}
						if ($sValores05 != '') {
							$sSQL = 'INSERT INTO ' . $sTabla05 . '(' . $sCampos05 . ') VALUES ' . $sValores05 . '';
							if ($bDebug) {
								$sDebug = $sDebug . fecha_microtiempo() . ' Insertando agenda <b>' . $sSQL . '</b><br>';
							}
							$result = $objDB->ejecutasql($sSQL);
							if ($result == false) {
								$sErrCurso = 'Falla al intentar guardar la agenda';
								if ($bDebug) {
									$sDebug = $sDebug . fecha_microtiempo() . ' Error insertando agenda <b>' . $sSQL . '</b><br>';
								}
							} else {
								//Actualizar la fila04
								$sSQL = 'UPDATE core04matricula_' . $sTabla04 . ' SET core04aplicoagenda=' . $iHoy . ' WHERE core04id=' . $fila04['core04id'] . '';
								$result = $objDB->ejecutasql($sSQL);
							}
						}
						//Fin de si la agenda no es externa...
					} else {
						//Limpiamos la casa.
						$sSQL = 'DELETE FROM ' . $sTabla05 . ' WHERE core05idmatricula=' . $fila04['core04id'] . '';
						if ($bDebug) {
							$sDebug = $sDebug . fecha_microtiempo() . ' <b>NO SE USA AGENDA </b>' . $sSQL . '<br>';
						}
						$result = $objDB->ejecutasql($sSQL);
						//$sSQL='UPDATE '.$sTabla04.' SET core04aplicoagenda='.$iHoy.' WHERE core04id='.$fila04['core04id'].'';
						//$result=$objDB->ejecutasql($sSQL);
					}
				}
				if ($sErrCurso != '') {
					if ($bDebug) {
						$sDebug = $sDebug . fecha_microtiempo() . ' Error del curso: ' . $sErrCurso . '<br>';
					}
					$sErrCurso = '';
				}
			}
		}
	}
	return array($sError, $iActividadesProc, $sDebug);
}
function f2205_ArreglarFechaAgendaCursoEstudiante($idPeriodo, $idCurso, $objDB, $bDebug = false)
{
	$sError = '';
	$sErrCurso = '';
	$sDebug = '';
	$sSQL4 = '';
	$iFechaInicio = 0;
	$iFechaCierre = 0;
	$iFechaRetro = 0;
	$aFechasAgenda = array();
	set_time_limit(0);
	$sSQL = 'SELECT TB.ofer18numaula, TB.ofer18idactividad, TB.ofer18fechainicio, TB.ofer18fechacierrre, TB.ofer18fecharetro 
	FROM ofer18agendaoferta AS TB 
	WHERE TB.ofer18per_aca=' . $idPeriodo . ' AND TB.ofer18curso=' . $idCurso;
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' Aula y fechas de la agenda: ' . $sSQL . '<br>';
	}
	$tabla18 = $objDB->ejecutasql($sSQL);
	while ($fila18 = $objDB->sf($tabla18)) {
		$aFechasAgenda[$fila18['ofer18numaula']][$fila18['ofer18idactividad']]['fechainicio'] = fecha_EnNumero($fila18['ofer18fechainicio']);
		$aFechasAgenda[$fila18['ofer18numaula']][$fila18['ofer18idactividad']]['fechacierre'] = fecha_EnNumero($fila18['ofer18fechacierrre']);
		$aFechasAgenda[$fila18['ofer18numaula']][$fila18['ofer18idactividad']]['fecharetro'] = fecha_EnNumero($fila18['ofer18fecharetro']);
	}
	// Las agendas ya existen por lo que hay que traer la información de los estudiantes
	$iHoy = fecha_DiaMod();
	$sSQL='SHOW TABLES LIKE "core04%"';
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' Total Periodo: Lista de contenedores: ' . $sSQL . '<br>';
	}
	$tablac = $objDB->ejecutasql($sSQL);
	while ($filac = $objDB->sf($tablac)) {
		$iContenedor = 0;
		$iContenedor = substr($filac[0], 16);
		$sTabla05 = 'core05actividades_' . $iContenedor;
		$sSQL5 = 'SELECT core05id, core05idaula, core05idactividad, core05fechaapertura, core05fechacierre, core05fecharetro 
		FROM ' . $sTabla05 . ' 
		WHERE core05peraca=' . $idPeriodo . ' AND core05idcurso=' . $idCurso . ' 
		ORDER BY  core05idaula, core05idactividad';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' Consultando actividades en la agenda del estudiante ' . $sSQL5 . '<br>';
		}
		$tabla05 = $objDB->ejecutasql($sSQL5);
		while ($fila05 = $objDB->sf($tabla05)) {
			$sActualiza = '';
			$bFechaActualiza = false;
			$iInicio = $aFechasAgenda[$fila05['core05idaula']][$fila05['core05idactividad']]['fechainicio'];
			$iCierre = $aFechasAgenda[$fila05['core05idaula']][$fila05['core05idactividad']]['fechacierre'];
			$iRetro = $aFechasAgenda[$fila05['core05idaula']][$fila05['core05idactividad']]['fecharetro'];
			if ($iInicio != $fila05['core05fechaapertura']) {
				if ($sActualiza != '') {
					$sActualiza = $sActualiza . ', ';
				}
				$bFechaActualiza = true;
				$sActualiza = $sActualiza . 'core05fechaapertura=' . $iInicio;
				$iFechaInicio++;
			}
			if ($iCierre != $fila05['core05fechacierre']) {
				if ($sActualiza != '') {
					$sActualiza = $sActualiza . ', ';
				}
				$bFechaActualiza = true;
				$sActualiza = $sActualiza . 'core05fechacierre=' . $iCierre;
				$iFechaCierre++;
			}
			if ($iRetro != $fila05['core05fecharetro']) {
				if ($sActualiza != '') {
					$sActualiza = $sActualiza . ', ';
				}
				$bFechaActualiza = true;
				$sActualiza = $sActualiza . 'core05fecharetro=' . $iRetro;
				$iFechaRetro++;
			}
			if ($bFechaActualiza) {
				$sSQL = 'UPDATE ' . $sTabla05 . ' SET ' . $sActualiza . ' WHERE core05peraca=' . $idPeriodo . ' AND core05idcurso=' . $idCurso . ' AND core05id=' . $fila05['core05id'];
				if ($bDebug) {
					$sDebug = $sDebug . fecha_microtiempo() . ' Actualizando fechas: ' . $sSQL . '<br>';
				}
				$result = $objDB->ejecutasql($sSQL);
				if ($result == false) {
					$sError = $sError . ' Falla al intentar agregar campos de anexo respuesta';
				}
			}
		}
	}
	return array($iFechaInicio, $iFechaCierre, $iFechaRetro, $sError, $sDebug);
}
function f2206_InfoGrupo($idPeriodo, $idGrupo, $objDB, $idContenedor = 0, $bDebug = false, $bReducido = false, $bConTutor = true)
{
	$sRes = '{' . $idGrupo . '}';
	$sDebug = '';
	if ($idContenedor == 0) {
		$idContenedor = f146_Contenedor($idPeriodo, $objDB);
	}
	if ($idContenedor != 0) {
		$sSQL = 'SELECT TB.core06consec, TB.core06idtutor, T11.unad11doc, T11.unad11razonsocial, T11.unad11correofuncionario, T11.unad11correoinstitucional 
		FROM core06grupos_' . $idContenedor . ' AS TB, unad11terceros AS T11 
		WHERE TB.core06id=' . $idGrupo . ' AND TB.core06idtutor=T11.unad11id';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);

			$sInfoTutor = '[Sin tutor asignado]';
			if ($fila['core06idtutor'] != 0) {
				if ($bReducido) {
					if ($bConTutor) {
						$sInfoTutor = ' <span title="' . formato_numero($fila['unad11doc']) . '">' . cadena_notildes($fila['unad11razonsocial']) . '</span>';
					} else {
						$sInfoTutor = '';
					}
				} else {
					$sMail = $fila['unad11correofuncionario'];
					$sInfoTutor = ' Tutor: <b><span title="' . formato_numero($fila['unad11doc']) . '">' . cadena_notildes($fila['unad11razonsocial']) . '</span> ' . $sMail . '</b>';
				}
			}
			if ($bReducido) {
				$sRes = '' . $fila['core06consec'] . ' - ' . $sInfoTutor;
			} else {
				$sRes = '<b>' . $fila['core06consec'] . '</b>' . $sInfoTutor;
			}
		} else {
			$sRes = '[<span class="rojo">No se ha encontrado el grupo Ref: ' . $idGrupo . '</span>]';
		}
	} else {
		$sRes = '[<span class="rojo">No se ha definido un contenedor de grupos en el periodo ' . $idPeriodo . '</span>]';
	}
	return array($sRes, $sDebug);
}
//Director desde TH
function f2205th_DirectorDesdeTH($idPeriodo, $idCurso, $objDB, $bDebug = false)
{
	$sError = '';
	$sDebug = '';
	$idDirector = 0;
	$bEntra = false;
	//Abril 15 de 2021 se revisa que este reportado por Talento Humano.
	$sSQL = 'SELECT cort05tipodoc, cort05numerodoc, cort05tipoproceso
	FROM cort05infodirectores
	WHERE cort05idperiodo=' . $idPeriodo . ' AND cort05idcurso=' . $idCurso . ' AND cort05tipoasignacion=1
	ORDER BY cort05conse DESC';
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' <b>Importar Directores</b> Datos de origen de TH: ' . $sSQL . ' <br>';
	}
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		while ($fila = $objDB->sf($tabla)) {
			if (!$bEntra) {
				if ($fila['cort05tipoproceso'] == 1) {
					$sTipoDoc = TH_EquivalenteTipoDoc($fila['cort05tipodoc']);
					$sDoc = $fila['cort05numerodoc'];
					$bEntra = true;
				}
			}
		}
	}
	if ($bEntra) {
		$sSQL = 'SELECT unad11id, unad11estado FROM unad11terceros WHERE unad11doc="' . $sDoc . '" AND unad11tipodoc="' . $sTipoDoc . '"';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' <b>Importar Directores</b> Consultando tercero a Importar: ' . $sSQL . ' <br>';
		}
		$tabla11 = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla11) == 0) {
			unad11_importar_V2($sDoc, '', $objDB);
			$tabla11 = $objDB->ejecutasql($sSQL);
		}
		if ($objDB->nf($tabla11) > 0) {
			$fila11 = $objDB->sf($tabla11);
			if ($fila11['unad11estado'] != 0) {
				if ($sTipoDoc != 'CC') {
					$sSQL = 'SELECT unad11id, unad11estado FROM unad11terceros WHERE unad11doc="' . $sDoc . '" AND unad11tipodoc="CC"';
					if ($bDebug) {
						$sDebug = $sDebug . fecha_microtiempo() . ' <b>Importar Directores</b> Consultando tercero a Importar [Por cedula]: ' . $sSQL . ' <br>';
					}
					$tabla11 = $objDB->ejecutasql($sSQL);
					if ($objDB->nf($tabla11) > 0) {
						$fila11 = $objDB->sf($tabla11);
						if ($fila11['unad11estado'] == 0) {
							$idDirector = $fila11['unad11id'];
						} else {
							if ($bDebug) {
								$sDebug = $sDebug . fecha_microtiempo() . ' <b>Importar Directores</b> El usuario ' . $sDoc . ' se encuentra inactivo [Importando tutores].<br>';
							}
						}
					} else {
						if ($bDebug) {
							$sDebug = $sDebug . fecha_microtiempo() . ' <b>Importar Directores</b> El usuario ' . $sTipoDoc . ' ' . $sDoc . ' se encuentra inactivo [Importando tutores].<br>';
						}
					}
				} else {
					if ($bDebug) {
						$sDebug = $sDebug . fecha_microtiempo() . ' <b>Importar Directores</b> El usuario ' . $sDoc . ' se encuentra inactivo [Importando tutores].<br>';
					}
				}
			} else {
				$idDirector = $fila11['unad11id'];
			}
		}
	}
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' <b>Importar Directores</b> Director desde TH: ' . $idDirector . ' <br>';
	}
	return array($idDirector, $sDebug);
}
function f2206th_PeriodosExcluidos($idPeriodo)
{
	$bExcluido = false;
	switch ($idPeriodo) {
		case 1111:
		case 1088:
		case 1087:
		case 1083:
		case 1081:
		case 1024:
		case 1023:
		case 1022:
		case 1019:
		case 1018:
		case 1017:
		case 1016:
		case 1015:
		case 1012:
		case 1011:
		case 991:
		case 981:
		case 971:
		case 964:
		case 963:
			$bExcluido = true;
			break;
	}
	return $bExcluido;
}
//Antigua funcion desde RCA
function f2206_TutorDirectorDesdeRyC($idPeriodo, $idCurso, $idGrupo, $objDB, $objDBRyC, $bDebug = false)
{
	$sDebug = '';
	$idTutor = 0;
	$idDirector = 0;
	//Abril 15 de 2021- La asignacion de tutores se hace en un proceso separado.
	$bAnteriores = false;
	if ($idPeriodo > 951) {
		$bAnteriores = f2206th_PeriodosExcluidos($idPeriodo);
	} else {
		$bAnteriores = true;
	}
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' <b>Leyendo Directores y Tutores ' . $idPeriodo . ' ' . $idCurso . '</b><br>';
	}
	if (!$bAnteriores) {
		list($idDirector, $sDebugT) = f2205th_DirectorDesdeTH($idPeriodo, $idCurso, $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugT;
		return array($idTutor, $idDirector, $sDebug);
		die();
	}
	$sSQL = 'SELECT cur_docente FROM cursos_periodos AS T1 WHERE T1.peraca=' . $idPeriodo . ' AND T1.cur_materia=' . $idCurso . ' AND T1.grupo=' . $idGrupo . ' AND T1.estado="A" AND T1.cur_edificio<>99';
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' Leyendo tutor desde RyC: ' . $sSQL . ' <br>';
	}
	$tablad = $objDBRyC->ejecutasql($sSQL);
	if ($objDBRyC->nf($tablad) > 0) {
		$filad = $objDBRyC->sf($tablad);
		$sDoc = $filad['cur_docente'];
		$bEntra = true;
		//Aqui se agregan las excepciones
		if ($sDoc == '0') {
			$bEntra = false;
		}
		if ($bEntra) {
			$sSQL = 'SELECT unad11id FROM unad11terceros WHERE unad11doc="' . $sDoc . '" AND unad11tipodoc="CC"';
			$tabla11 = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla11) == 0) {
				unad11_importar_V2($sDoc, '', $objDB);
				$tabla11 = $objDB->ejecutasql($sSQL);
			}
			if ($objDB->nf($tabla11) > 0) {
				$fila11 = $objDB->sf($tabla11);
				$idTutor = $fila11['unad11id'];
			}
		}
	}
	$idDirector = 0;
	$sSQL = 'SELECT documento FROM direccion_academica WHERE peraca=' . $idPeriodo . ' AND codigo_curso="' . $idCurso . '" AND id_rol=3';
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' Leyendo director desde RyC: ' . $sSQL . ' <br>';
	}
	$tablad = $objDBRyC->ejecutasql($sSQL);
	if ($objDBRyC->nf($tablad) > 0) {
		$filad = $objDBRyC->sf($tablad);
		$sDoc = $filad['documento'];
		$bEntra = true;
		//Aqui se agregan las excepciones
		if ($sDoc == '0') {
			$bEntra = false;
		}
		if ($bEntra) {
			$sSQL = 'SELECT unad11id FROM unad11terceros WHERE unad11doc="' . $sDoc . '" AND unad11tipodoc="CC"';
			$tabla11 = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla11) == 0) {
				unad11_importar_V2($sDoc, '', $objDB);
				$tabla11 = $objDB->ejecutasql($sSQL);
			}
			if ($objDB->nf($tabla11) > 0) {
				$fila11 = $objDB->sf($tabla11);
				$idDirector = $fila11['unad11id'];
			}
		}
	}
	if ($idTutor == 0) {
		$idTutor = $idDirector;
	}
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' Datos devueltos al leer RyC: ' . $idTutor . ', ' . $idDirector . ' <br>';
	}
	return array($idTutor, $idDirector, $sDebug);
}
function f2206_CrearGrupo($idPeriodo, $idCurso, $idGrupo, $params, $objDB, $objDBRyC, $idContPeriodo = 0, $bDebug = false)
{
	$sError = '';
	$sDebug = '';
	$core06grupoidforma = 0;
	$core06grupominest = 5;
	$core06grupomaxest = 5;
	$core06fechatopearmado = 0;
	$core06idtutor = 0;
	$core06iddirector = 0;
	$core06idestudiantelider = 0;
	$core06numinscritos = 0;
	$core06codigogrupo = '';
	$core06estado = 1;
	$core06idcead = 0;
	if ($idContPeriodo == 0) {
		$idContPeriodo = f146_Contenedor($idPeriodo, $objDB);
	}
	$idAula = 1;
	if ($idGrupo > 699) {
		$idAula = 2;
	}
	if ($idGrupo > 1399) {
		$idAula = 3;
	}
	if ($idGrupo > 2099) {
		$idAula = 4;
	}
	if ($idGrupo > 2799) {
		$idAula = 5;
	}
	$id06aula = $idAula;
	$core06idtutor = 0;
	$core06iddirector = 0;
	if ($idPeriodo > 951) {
		//A partir del periodo 952 va sin tutor y sin director.
	} else {
		if ($objDBRyC == NULL) {
			/*
			$sSQL='SELECT core96idtercero, core96role FROM core96tmp WHERE core96curso='.$idCurso.' AND core96peraca='.$idPeriodo.' AND core96grupo='.$idGrupo.' AND core96role IN (3, 4)';
			$tablab=$objDB->ejecutasql($sSQL);
			while($filab=$objDB->sf($tablab)){
				if ($filab['core96role']==4){
					$core06idtutor=$filab['core96idtercero'];
					}else{
					$core06iddirector=$filab['core96idtercero'];
					}
				}
			if ($core06idtutor==0){$core06idtutor=$core06iddirector;}
			*/
		} else {
			//Traer el tutor y el director de ryc
			list($core06idtutor, $core06iddirector, $sDebug) = f2206_TutorDirectorDesdeRyC($idPeriodo, $idCurso, $idGrupo, $objDB, $objDBRyC, $bDebug);
		}
	}
	$sTabla06 = 'core06grupos_' . $idContPeriodo;
	$id06 = tabla_consecutivo($sTabla06, 'core06id', '', $objDB);
	$sSQL = 'INSERT INTO ' . $sTabla06 . ' (core06peraca, core06idcurso, core06consec, core06id, core06idaula, 
	core06grupoidforma, core06grupominest, core06grupomaxest, core06fechatopearmado, core06idtutor, 
	core06iddirector, core06idestudiantelider, core06numinscritos, core06codigogrupo, core06estado, 
	core06idcead) 
	VALUES (' . $idPeriodo . ', ' . $idCurso . ', ' . $idGrupo . ', ' . $id06 . ', ' . $idAula . ', 
	' . $core06grupoidforma . ', ' . $core06grupominest . ', ' . $core06grupomaxest . ', ' . $core06fechatopearmado . ', ' . $core06idtutor . ', 
	' . $core06iddirector . ', ' . $core06idestudiantelider . ', ' . $core06numinscritos . ', "' . $core06codigogrupo . '", ' . $core06estado . ', 
	' . $core06idcead . ')';
	$result = $objDB->ejecutasql($sSQL);
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' Se crea el grupo ' . $sSQL . '<br>';
	}
	return array($sError, $sDebug);
}
function f2206_IgualarTutoresV2($idPeriodo, $idCurso, $objDB, $idContPeriodo = 0, $bDebug = false)
{
	$sError = '';
	$sDebug = '';
	if ((int)$idCurso == 0) {
		$sError = 'No se ha definido el curso a consultar';
	}
	if ($sError == '') {
		$iConfirma = numeros_validar($idCurso);
		if ($iConfirma != $idCurso) {
			$sError = 'Codigo de curso errado.';
		}
	}
	if ($sError == '') {
		if ($idContPeriodo == 0) {
			$idContPeriodo = f146_Contenedor($idPeriodo, $objDB);
		}
		if ($idContPeriodo == 0) {
			$sError = 'No se ha definido un contenedor para el periodo ' . $idPeriodo . '';
		}
	}
	if ($sError == '') {
		//Alistamos el listado de cursos de postgrado que deben tener una nota aprobatoria de 3.5, 
		//esto se debe compensar cuando se apliquen los planes de estudio...
		$sBasica = '-99';
		$sInformal = '-99';
		$sPregrados = '-99';
		$sIdsProgramas = '-99';
		/*
		$sSQL='SELECT TB.core09id, T1.core22grupo 
		FROM core09programa AS TB, core22nivelprograma AS T1
		WHERE TB.cara09nivelformacion=T1.core22id';
		$tabla=$objDB->ejecutasql($sSQL);
		while($fila=$objDB->sf($tabla)){
			switch($fila['core22grupo']){
				case 2: //Informal
				$sInformal=$sInformal.','.$fila['core09id'];
				break;
				case 3: //Profesional
				$sPregrados=$sPregrados.','.$fila['core09id'];
				break;
				case 4: //PostGradual
				$sIdsProgramas=$sIdsProgramas.','.$fila['core09id'];
				break;
				default: 
				$sBasica=$sBasica.','.$fila['core09id'];
				break;
				}
			}
		*/
		$iNivelForma = 3;
		$iNotaBase = 3;
		$sSQL = 'SELECT T1.core22grupo 
		FROM unad40curso AS TB, core22nivelprograma AS T1
		WHERE TB.unad40id=' . $idCurso . ' AND TB.unad40nivelformacion=T1.core22id';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			$iNivelForma = $fila['core22grupo'];
		}
		//Alistamos los condicionales que nos hagan falta.
		$sCondiCurso = '';
		$sCondi04 = '';
		$sCondi04b = '';
		if ($idCurso != 0) {
			$sCondiCurso = ' AND T6.core06idcurso=' . $idCurso . ' ';
			$sCondi04 = ' AND T4.core04idcurso=' . $idCurso . ' ';
			$sCondi04b = ' AND core04idcurso=' . $idCurso . ' ';
		}
		//Recorremos los cursos igualando tutores.
		$bPrimerDebug = $bDebug;
		$sSQL = 'SHOW TABLES LIKE "core04%"';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' Total Periodo: Lista de contenedores: ' . $sSQL . '<br>';
		}
		$tabla = $objDB->ejecutasql($sSQL);
		while ($fila = $objDB->sf($tabla)) {
			$iContenedor = substr($fila[0], 16);
			$bHayDatos = true;
			if ((int)$iContenedor != 0) {
			} else {
				$bHayDatos = false;
			}
			if ($bHayDatos) {
				// OR (T6.core06idtutor<>T4.core04idtutor)
				$sSQL = 'SELECT 1 FROM core06grupos_' . $idContPeriodo . ' AS T6, core04matricula_' . $iContenedor . ' AS T4
				WHERE T6.core06peraca=' . $idPeriodo . $sCondiCurso . ' AND T6.core06id=T4.core04idgrupo AND T4.core04peraca=' . $idPeriodo . $sCondi04 . '
				AND ((T6.core06idaula<>T4.core04idaula)) LIMIT 0, 1';
				$tablarev = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($tablarev) > 0) {
					//Hay grupos, por tanto igualar la tabla 6 con la 4
					$sSQL = 'UPDATE core06grupos_' . $idContPeriodo . ' AS T6, core04matricula_' . $iContenedor . ' AS T4
					SET T4.core04idaula=T6.core06idaula 
					WHERE T6.core06peraca=' . $idPeriodo . $sCondiCurso . ' AND T6.core06id=T4.core04idgrupo AND T4.core04peraca=' . $idPeriodo . $sCondi04 . '
					AND ((T6.core06idaula<>T4.core04idaula))';
					if ($bPrimerDebug) {
						$sDebug = $sDebug . fecha_microtiempo() . ' Actualizando aulas en contenedor ' . $iContenedor . ': ' . $sSQL . '<br>';
					}
					$result = $objDB->ejecutasql($sSQL);
				}
				$sSQL = 'SELECT 1 FROM core06grupos_' . $idContPeriodo . ' AS T6, core04matricula_' . $iContenedor . ' AS T4
				WHERE T6.core06peraca=' . $idPeriodo . $sCondiCurso . ' AND T6.core06id=T4.core04idgrupo AND T4.core04peraca=' . $idPeriodo . $sCondi04 . '
				AND ((T6.core06idtutor<>T4.core04idtutor)) LIMIT 0, 1';
				$tablarev = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($tablarev) > 0) {
					$sSQL = 'UPDATE core06grupos_' . $idContPeriodo . ' AS T6, core04matricula_' . $iContenedor . ' AS T4
					SET T4.core04idtutor=T6.core06idtutor 
					WHERE T6.core06peraca=' . $idPeriodo . $sCondiCurso . ' AND T6.core06id=T4.core04idgrupo AND T4.core04peraca=' . $idPeriodo . $sCondi04 . '
					AND ((T6.core06idtutor<>T4.core04idtutor))';
					if ($bPrimerDebug) {
						$sDebug = $sDebug . fecha_microtiempo() . ' Actualizando tutores en contenedor ' . $iContenedor . ': ' . $sSQL . '<br>';
					}
					$result = $objDB->ejecutasql($sSQL);
				}

				$sSQL = 'UPDATE core04matricula_' . $iContenedor . ' AS T4, core05actividades_' . $iContenedor . ' AS T5
				SET T5.core05idgrupo=T4.core04idgrupo, T5.core05idaula=T4.core04idaula, T5.core05idtutor=T4.core04idtutor
				WHERE T4.core04peraca=' . $idPeriodo . $sCondi04 . '
				AND T4.core04id=T5.core05idmatricula 
				AND ((T4.core04idgrupo<>T5.core05idgrupo) OR (T4.core04idaula<>T5.core05idaula) OR (T4.core04idtutor<>T5.core05idtutor))';
				if ($bPrimerDebug) {
					$sDebug = $sDebug . fecha_microtiempo() . ' Actualizando actividades en contenedor ' . $iContenedor . ': ' . $sSQL . '<br>';
				}
				$result = $objDB->ejecutasql($sSQL);
				//Ajustamos la nota aprobatoria para estadistica .
				//Marzo 17 de 2021 - Se estaba ajustando la nota por curso, pero ahora se ajusta según el programa.
				if ($iNivelForma == 4) {
					//El curso es de postgrado, si o si va con nota 3,5
					$sSQL = 'UPDATE core04matricula_' . $iContenedor . ' SET core04est_aprob=3.5, core04est_nivel=4 WHERE core04peraca=' . $idPeriodo . $sCondi04b . '';
					if ($bPrimerDebug) {
						$sDebug = $sDebug . fecha_microtiempo() . ' Actualizando el nivel estadistico en PostGrado ' . $iContenedor . ': ' . $sSQL . '<br>';
					}
					$result = $objDB->ejecutasql($sSQL);
				} else {
					$sSQL = 'UPDATE core04matricula_' . $iContenedor . ' AS TB, core09programa AS T9, core22nivelprograma AS T22 
					SET TB.core04est_aprob=3.5, TB.core04est_nivel=4 
					WHERE TB.core04peraca=' . $idPeriodo . $sCondi04b . ' AND TB.core04idprograma=T9.core09id 
					AND T9.cara09nivelformacion=T22.core22id AND T22.core22grupo=4 AND TB.core04est_nivel<>T22.core22grupo';
					if ($bPrimerDebug) {
						$sDebug = $sDebug . fecha_microtiempo() . ' Actualizando el nivel estadistico en PostGrado ' . $iContenedor . ': ' . $sSQL . '<br>';
					}
					$result = $objDB->ejecutasql($sSQL);
					$sSQL = 'UPDATE core04matricula_' . $iContenedor . ' AS TB, core09programa AS T9, core22nivelprograma AS T22 
					SET TB.core04est_aprob=3.0, TB.core04est_nivel=T22.core22grupo 
					WHERE TB.core04peraca=' . $idPeriodo . $sCondi04b . ' AND TB.core04idprograma=T9.core09id 
					AND T9.cara09nivelformacion=T22.core22id AND T22.core22grupo<>4 AND TB.core04est_nivel<>T22.core22grupo';
					if ($bPrimerDebug) {
						$sDebug = $sDebug . fecha_microtiempo() . ' Actualizando el nivel estadistico los demas niveles ' . $iContenedor . ': ' . $sSQL . '<br>';
					}
					$result = $objDB->ejecutasql($sSQL);
					//16 de dic de 2021 - Se cambia esta actualizacion
					/*
					$sSQL='UPDATE core04matricula_'.$iContenedor.' AS TB 
					SET TB.core04est_aprob=3.5, TB.core04est_nivel=4 
					WHERE TB.core04peraca='.$idPeriodo.$sCondi04b.' AND TB.core04idprograma IN ('.$sIdsProgramas.') 
					AND TB.core04est_nivel<>4 AND TB.core04est_aprob<>3.5';
					if ($bPrimerDebug){$sDebug=$sDebug.fecha_microtiempo().' Actualizando el nivel estadistico en PostGrado '.$iContenedor.': '.$sSQL.'<br>';}
					$result=$objDB->ejecutasql($sSQL);
					$sSQL='UPDATE core04matricula_'.$iContenedor.' AS TB 
					SET TB.core04est_aprob=3, TB.core04est_nivel=3 
					WHERE TB.core04peraca='.$idPeriodo.$sCondi04b.' AND TB.core04idprograma IN ('.$sPregrados.') 
					AND TB.core04est_nivel<>3 AND TB.core04est_aprob<>3';
					if ($bPrimerDebug){$sDebug=$sDebug.fecha_microtiempo().' Actualizando el nivel estadistico los PREGRADOS '.$iContenedor.': '.$sSQL.'<br>';}
					$result=$objDB->ejecutasql($sSQL);
					$sSQL='UPDATE core04matricula_'.$iContenedor.' AS TB 
					SET TB.core04est_aprob=3, TB.core04est_nivel=2 
					WHERE TB.core04peraca='.$idPeriodo.$sCondi04b.' AND TB.core04idprograma IN ('.$sInformal.') 
					AND TB.core04est_nivel<>3 AND TB.core04est_aprob<>2';
					if ($bPrimerDebug){$sDebug=$sDebug.fecha_microtiempo().' Actualizando el nivel estadistico NO FORMAL '.$iContenedor.': '.$sSQL.'<br>';}
					$result=$objDB->ejecutasql($sSQL);
					$sSQL='UPDATE core04matricula_'.$iContenedor.' AS TB 
					SET TB.core04est_aprob=3, TB.core04est_nivel=1 
					WHERE TB.core04peraca='.$idPeriodo.$sCondi04b.' AND TB.core04idprograma IN ('.$sBasica.') 
					AND TB.core04est_nivel<>3 AND TB.core04est_aprob<>1';
					if ($bPrimerDebug){$sDebug=$sDebug.fecha_microtiempo().' Actualizando el nivel estadistico NO FORMAL '.$iContenedor.': '.$sSQL.'<br>';}
					$result=$objDB->ejecutasql($sSQL);
					*/
				}
				/*
				$sSQL='UPDATE core04matricula_'.$iContenedor.' SET core04est_aprob=3.0, core04est_nivel=1 WHERE core04peraca='.$idPeriodo.$sCondi04b.' AND core04idprograma IN ('.$sBasica.')';
				if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Actualizando el nivel estadistico en basica '.$iContenedor.': '.$sSQL.'<br>';}
				$result=$objDB->ejecutasql($sSQL);
				$sSQL='UPDATE core04matricula_'.$iContenedor.' SET core04est_aprob=3.0, core04est_nivel=2 WHERE core04peraca='.$idPeriodo.$sCondi04b.' AND core04idprograma IN ('.$sInformal.')';
				if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Actualizando el nivel estadistico en informal '.$iContenedor.': '.$sSQL.'<br>';}
				$result=$objDB->ejecutasql($sSQL);
				$sSQL='UPDATE core04matricula_'.$iContenedor.' SET core04est_aprob=3.0, core04est_nivel=3 WHERE core04peraca='.$idPeriodo.$sCondi04b.' AND core04idprograma IN ('.$sPregrados.')';
				if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Actualizando el nivel estadistico en pregrado '.$iContenedor.': '.$sSQL.'<br>';}
				$result=$objDB->ejecutasql($sSQL);
				$sSQL='UPDATE core04matricula_'.$iContenedor.' SET core04est_aprob=3.5, core04est_nivel=4 WHERE core04peraca='.$idPeriodo.$sCondi04b.' AND core04idprograma IN ('.$sIdsProgramas.')';
				if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Actualizando el nivel estadistico en PostGrado '.$iContenedor.': '.$sSQL.'<br>';}
				$result=$objDB->ejecutasql($sSQL);
				*/
				$bPrimerDebug = false;
			}
		}
	}
	return array($sError, $sDebug);
}
function f2207_TotalMatricula($idPeriodo, $idPrograma, $idCead, $sCurso, $objDB, $bDebug = false)
{
	$sDebug = '';
	$sSQLadd1 = '';
	if ($idPrograma != '') {
		$sSQLadd1 = $sSQLadd1 . 'TB.core07idprograma=' . $idPrograma . ' AND ';
	}
	if ($idCead != '') {
		$sSQLadd1 = $sSQLadd1 . 'TB.core07idcead=' . $idCead . ' AND ';
	}
	if ($sCurso != '') {
		$sSQLadd1 = $sSQLadd1 . 'TB.core07idcurso=' . $sCurso . ' AND ';
	}
	$sSQL = 'SELECT SUM(TB.core07numestudiantes) AS Ant, SUM(TB.core07numnuevos) AS Nuevo 
	FROM core07matriculaest AS TB 
	WHERE ' . $sSQLadd1 . ' TB.core07idperaca=' . $idPeriodo . '';
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' Consulta Totales: ' . $sSQL . '<br>';
	}
	$tabla = $objDB->ejecutasql($sSQL);
	$fila = $objDB->sf($tabla);
	$iTotalAntiguos = $fila['Ant'];
	$iTotalNuevos = $fila['Nuevo'];
	return array($iTotalAntiguos, $iTotalNuevos, $sDebug);
}
function f2209_CodigoPrograma($idPrograma, $objDB)
{
	$sCodPrograma = $idPrograma;
	$sSQL = 'SELECT core09codigo FROM core09programa WHERE core09id=' . $idPrograma . '';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$sCodPrograma = $fila['core09codigo'];
	}
	return $sCodPrograma;
}
function f2209_ConsultaComboEscuelas($sWhere = '', $objDB = NULL)
{
	return 'SELECT TB.core12id AS id, TB.core12nombre AS nombre 
	FROM core12escuela AS TB 
	WHERE ' . $sWhere . ' TB.core12id>0 
	ORDER BY TB.core12sigla';
}
function f2209_ConsultaCombo($sWhere = '', $objDB = NULL)
{
	return 'SELECT TB.core09id AS id, CONCAT(TB.core09nombre, " [", TB.core09codigo, "] - ", T1.core12sigla) AS nombre 
	FROM core09programa AS TB, core12escuela AS T1 
	WHERE ' . $sWhere . ' TB.core09id>0 AND TB.core09idescuela=T1.core12id 
	ORDER BY TB.core09nombre';
}
// --- Rango de creditos que aplican para el programa en un semestre
function f2209_RangoCreditosXCiclo($core09id, $objDB, $bDebug = false)
{
	list($iMinimo, $iMaximo, $iRecomendado, $sError, $sDebug) = f2209_RangoCreditosXCicloV2($core09id, $objDB, $bDebug);
	return array($iMinimo, $iMaximo, $sError, $sDebug);
}
function f2209_RangoCreditosXCicloV2($core09id, $objDB, $bDebug = false)
{
	$iMinimo = 10;
	$iMaximo = 20;
	$iRecomendado = 15;
	$sError = '';
	$sDebug = '';
	if ((int)$core09id > 0) {
		$sSQL = 'SELECT TB.core09id, T1.core22grupo 
		FROM core09programa AS TB, core22nivelprograma AS T1
		WHERE TB.core09id=' . $core09id . ' AND TB.cara09nivelformacion=T1.core22id';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' <b>Rango de Creditos por ciclo</b> Nivel del programa: ' . $sSQL . ' <br>';
		}
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			switch ($fila['core22grupo']) {
				case 4: // PostGrado 
					//@@@@ --- PENDIENTE DEFINIR SI SE GUARDAN LOS VALORES O LOS QUEMAMOS POR CODIGO.
					$iMinimo = 6;
					$iMaximo = 12;
					$iRecomendado = 9;
					break;
			}
		}
	}
	return array($iMinimo, $iMaximo, $iRecomendado, $sError, $sDebug);
}
// --- 
function f2209_ProgramasLider($idTercero, $objDB, $bDebug = false)
{
	$sProgramas = '';
	$sDebug = '';
	$sSQL = 'SELECT TB.core09id 
	FROM core09programa AS TB
	WHERE TB.core09iddirector=' . $idTercero . '';
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' Consultando Lideres de programa: ' . $sSQL . '<br>';
	}
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$sProgramas = '-99';
	}
	while ($fila = $objDB->sf($tabla)) {
		$sProgramas = $sProgramas . ',' . $fila['core09id'];
	}
	return array($sProgramas, $sDebug);
}
function f2209_TituloPrograma($id09, $objDB)
{
	$sRes = '&nbsp;';
	if ((int)$id09 != 0) {
		$sRes = '{' . $id09 . '}';
		$sSQL = 'SELECT TB.core09codigo, TB.core09nombre, T1.core12sigla 
		FROM core09programa AS TB, core12escuela AS T1 
		WHERE TB.core09id=' . $id09 . ' AND TB.core09idescuela=T1.core12id';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			$sRes = '' . cadena_notildes($fila['core09nombre']) . ' [' . $fila['core09codigo'] . '] - ' . $fila['core12sigla'] . '';
		}
	}
	return $sRes;
}
function f2210_LlenarComboPlanes($idPrograma, $objDB, $objCombos, $bConDuracion = true, $bConEstado = false, $iAnchoCombo = 440)
{
	if ((int)$idPrograma != 0) {
		$objCombos->iAncho = $iAnchoCombo;
		$sSQL = 'SELECT core10id, core10consec, core10numregcalificado, core10fechaversion, core10fechavence, core10estado 
		FROM core10programaversion 
		WHERE core10idprograma=' . $idPrograma . ' AND core10estado IN ("S", "X") 
		ORDER BY core10consec DESC';
		$tabla = $objDB->ejecutasql($sSQL);
		while ($fila = $objDB->sf($tabla)) {
			$sDuracion = '';
			if ($bConDuracion) {
				$sDuracion = ' - Vigente desde ' . fecha_DesdeNumero($fila['core10fechaversion']) . ' hasta ' . fecha_DesdeNumero($fila['core10fechavence']) . '';
			}
			$objCombos->addItem($fila['core10id'], $fila['core10consec'] . ' - N&deg; Res ' . $fila['core10numregcalificado'] . $sDuracion . '');
		}
	}
	return $objCombos;
}
function f2210_LlenarComboPlanesPrograma($sIds, $objDB, $objCombos, $iAnchoCombo = 440, $bDebug = false)
{
	$sDebug = '';
	$sSQL = 'SELECT TB.core10id, TB.core10consec, TB.core10numregcalificado, TB.core10fechaversion, TB.core10fechavence, TB.core10estado, TB.core10idprograma, T9.core09codigo, T9.core09nombre 
	FROM core10programaversion AS TB, core09programa AS T9 
	WHERE TB.core10id IN (' . $sIds . ') AND TB.core10idprograma=T9.core09id
	ORDER BY T9.core09nombre, TB.core10consec DESC';
	$tabla = $objDB->ejecutasql($sSQL);
	$objCombos->iAncho = $iAnchoCombo;
	while ($fila = $objDB->sf($tabla)) {
		$objCombos->addItem($fila['core10id'], cadena_notildes($fila['core09nombre']) . ' [' . $fila['core09codigo'] . '] Ver: ' . $fila['core10consec'] . ' - N&deg; Res ' . $fila['core10numregcalificado'] . '');
	}
	return $objCombos;
}
function f2210_TituloPlan($id10, $objDB, $bConDuracion = true, $bConEstado = false)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$sRes = '&nbsp;';
	if ((int)$id10 > 0) {
		$sRes = '{' . $id10 . '}';
		$sSQL = 'SELECT core10consec, core10numregcalificado, core10fechaversion, core10fechavence, core10estado 
		FROM core10programaversion 
		WHERE core10id=' . $id10 . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			$sDuracion = '';
			if ($bConDuracion) {
				$sDuracion = ' - Vigente desde ' . fecha_DesdeNumero($fila['core10fechaversion']) . ' hasta ' . fecha_DesdeNumero($fila['core10fechavence']) . '';
			}
			$sRes = $fila['core10consec'] . ' - N&deg; Res ' . $fila['core10numregcalificado'] . $sDuracion . '';
		}
	} else {
		if ((int)$id10 != 0) {
			$sRes = '[' . $ETI['msg_na'] . ']';
		}
	}
	return $sRes;
}
function f2210_TituloPlanPrograma($id10, $objDB, $bConDuracion = false, $bConEstado = false)
{
	$sRes = '&nbsp;';
	if ((int)$id10 != 0) {
		$sRes = '{' . $id10 . '}';
		$sSQL = 'SELECT TB.core10id, TB.core10consec, TB.core10numregcalificado, TB.core10fechaversion, TB.core10fechavence, TB.core10estado, TB.core10idprograma, T9.core09codigo, T9.core09nombre 
		FROM core10programaversion AS TB, core09programa AS T9 
		WHERE TB.core10id=' . $id10 . ' AND TB.core10idprograma=T9.core09id';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			$sDuracion = '';
			if ($bConDuracion) {
				$sDuracion = ' - Vigente desde ' . fecha_DesdeNumero($fila['core10fechaversion']) . ' hasta ' . fecha_DesdeNumero($fila['core10fechavence']) . '';
			}
			$sRes = cadena_notildes($fila['core09nombre']) . ' [' . $fila['core09codigo'] . '] Ver: ' . $fila['core10consec'] . ' - N&deg; Res ' . $fila['core10numregcalificado'] . $sDuracion . '';
		}
	}
	return $sRes;
}
//Planes de estudio
function f2211_ParametrosElectivos($idPlan, $objDB)
{
	$bConElectivosGenerales = false;
	$bConElectivosEscuela = false;
	$idEscuela = 0;
	$idPrograma = 0;
	$sIds40 = '-99';
	$sIds40B = '';
	$sGruposNivel = '10,4,2'; //Por defecto el grupo de Tecnica, Tecnologia y profesional

	$sSQL = 'SELECT TB.core10idprograma, TB.core10numcredelecgenerales, TB.core10numcredelecescuela, 
	T9.core09idescuela, T9.cara09nivelformacion 
	FROM core10programaversion AS TB, core09programa AS T9 
	WHERE TB.core10id=' . $idPlan . ' AND TB.core10idprograma=T9.core09id ';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$idEscuela = $fila['core09idescuela'];
		$idPrograma = $fila['core10idprograma'];
		$cara09nivelformacion = $fila['cara09nivelformacion'];
		if ($fila['core10numcredelecgenerales'] > 0) {
			$bConElectivosGenerales = true;
		}
		if ($fila['core10numcredelecescuela'] > 0) {
			$bConElectivosEscuela = true;
		}
	}
	if ($bConElectivosEscuela || $bConElectivosGenerales) {
		//Primero listamos los que se descartan
		$sSQL = 'SELECT TB.unad40id 
		FROM core11plandeestudio AS T1, unad40curso AS TB
		WHERE T1.core11idversionprograma=' . $idPlan . ' AND T1.core11idcurso=TB.unad40id 
		AND T1.core11fechaaprobado>0 AND T1.core11idequivalente=0';
		$tabla = $objDB->ejecutasql($sSQL);
		while ($fila = $objDB->sf($tabla)) {
			$sIds40 = $sIds40 . ',' . $fila['unad40id'];
		}
		switch ($cara09nivelformacion) {
			case 3: //Especialización
			case 5: //Maestria
				$sGruposNivel = '3,5';
				break;
			case 6: //Doctorado
				$sGruposNivel = '6';
				break;
		}
	}
	$sIds40B = $sIds40;
	if ($bConElectivosEscuela && $bConElectivosGenerales) {
		$sSQL = 'SELECT T40.unad40id 
		FROM core28electivos AS TB, unad40curso AS T40 
		WHERE TB.core28idescuela=' . $idEscuela . ' AND TB.core28ofertado="S" AND TB.core28idcurso NOT IN (' . $sIds40 . ') 
		AND TB.core28idcurso=T40.unad40id AND T40.unad40nivelformacion IN (' . $sGruposNivel . ')';
		$tabla = $objDB->ejecutasql($sSQL);
		while ($fila = $objDB->sf($tabla)) {
			$sIds40B = $sIds40B . ',' . $fila['unad40id'];
		}
	}
	return array($bConElectivosEscuela, $idEscuela, $sGruposNivel, $sIds40, $bConElectivosGenerales, $idPrograma, $sIds40B);
}
function f2211_LlenarComboCursos($idPlan, $objDB, $objCombos, $iAnchoCombo = 600)
{
	$sDebug = '';
	if ((int)$idPlan != 0) {
		//Ver si lleva IBC o si lleva ELECTIVOS DISCIPLINARES.
		list($bConElectivosEscuela, $idEscuela, $sGruposNivel, $sIds40, $bConElectivosGenerales, $idPrograma, $sIds40B) = f2211_ParametrosElectivos($idPlan, $objDB);
		$sSQLUnion = '';
		if ($bConElectivosEscuela) {
			$sSQLUnion = ' UNION SELECT T40.unad40id, T40.unad40titulo, T40.unad40nombre, T40.unad40numcreditos, TB.core28nivelaplica AS core11nivelaplica
			FROM core28electivos AS TB, unad40curso AS T40 
			WHERE TB.core28idescuela=' . $idEscuela . ' AND TB.core28ofertado="S" AND TB.core28idcurso NOT IN (' . $sIds40 . ') 
			AND TB.core28idcurso=T40.unad40id AND T40.unad40nivelformacion IN (' . $sGruposNivel . ')';
		}
		//Ahora los electivos Generales.
		if ($bConElectivosGenerales) {
			$sSQLUnion = $sSQLUnion . ' UNION SELECT T40.unad40id, T40.unad40titulo, T40.unad40nombre, T40.unad40numcreditos, TB.core28nivelaplica AS core11nivelaplica 
			FROM core28electivos AS TB, unad40curso AS T40 
			WHERE TB.core28idescuela=0  AND TB.core28idprograma IN (0,' . $idPrograma . ') AND TB.core28ofertado="S" AND TB.core28idcurso NOT IN (' . $sIds40B . ') 
			AND TB.core28idcurso=T40.unad40id AND T40.unad40nivelformacion IN (' . $sGruposNivel . ')';
		}
		// 
		$objCombos->iAncho = $iAnchoCombo;
		$sSQL = 'SELECT TB.unad40id, TB.unad40titulo, TB.unad40nombre, TB.unad40numcreditos, T1.core11nivelaplica 
		FROM core11plandeestudio AS T1, unad40curso AS TB
		WHERE T1.core11idversionprograma=' . $idPlan . ' AND T1.core11idcurso=TB.unad40id 
		AND T1.core11fechaaprobado>0 AND T1.core11idequivalente=0 ' . $sSQLUnion . '
		ORDER BY core11nivelaplica, unad40nombre';
		//$sDebug = $sSQL;
		$tabla = $objDB->ejecutasql($sSQL);
		while ($fila = $objDB->sf($tabla)) {
			$objCombos->addItem($fila['unad40id'], $fila['unad40titulo'] . ' - ' . $fila['unad40nombre'] . ' [Cred ' . $fila['unad40numcreditos'] . ' Nivel ' . $fila['core11nivelaplica']);
		}
	}
	return array($objCombos, $sDebug);
}
//Resumen de creditos
function f2211_ResumenCreditos($core10id, $objDB)
{
	//Por los cambios en el dise;o se cambia el orden en que se devuelven los parametros.
	list($iNumCredBasicos, $iNumCredEspecificos, $iNumCredElectivoBComun, $iNumCredElectivoDComun, $iNumCredElectivosDEsp, $iNumCredElectivoComp, $iNumCredRequisitos) = f2211_ResumenCreditosV2($core10id, $objDB);
	return array($iNumCredBasicos, $iNumCredEspecificos, $iNumCredElectivosDEsp, $iNumCredRequisitos, $iNumCredElectivoComp, $iNumCredElectivoBComun, $iNumCredElectivoDComun);
}
function f2211_ResumenCreditosV2($core10id, $objDB, $bNivelDoctorado = false)
{
	$iNumCredBasicos = 0;
	$iNumCredEspecificos = 0;
	$iNumCredElectivoBComun = 0;
	$iNumCredElectivoDComun = 0;
	$iNumCredElectivosDEsp = 0;
	$iNumCredElectivoComp = 0;
	$iNumCredRequisitos = 0;
	$iLineasXEst = 0;
	$sInfoLineas = '';
	//Hay que tener en cuenta que el plan de estudio puede tener lineas de profundización
	$sCondiLineaProf = '';
	$bRevisaLineas = false;
	if (!$bNivelDoctorado) {
		$sSQL = 'SELECT core09codigo, core09nombre, cara09nivelformacion FROM core09programa WHERE core09id=' . $_REQUEST['core10idprograma'];
		$result = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($result) > 0) {
			$fila = $objDB->sf($result);
			if ($fila['cara09nivelformacion'] == 6) {
				$bNivelDoctorado = true;
			}
		}
	}
	$sCondiAprobados = ' AND core11fechaaprobado<>0';
	$sSQL = 'SELECT core10estado, core10numlineasprof 
	FROM core10programaversion 
	WHERE core10id=' . $core10id . '';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$iLineasXEst = $fila['core10numlineasprof'];
		if ($fila['core10estado'] != 'S') {
			$sCondiAprobados = '';
		}
	}
	// Si es doctorado se requiere ver las rutas de formación doctoral
	if ($bNivelDoctorado) {
		$bRevisaLineas = true;
		$sSQL = 'SELECT 1 FROM core21lineaprof WHERE core21idprograma=' . $_REQUEST['core10idprograma'] . '';
		$tabla = $objDB->ejecutasql($sSQL);
		$iPendientes = $objDB->nf($tabla);
	}
	if ($iLineasXEst > 0) {
		$bRevisaLineas = true;
		$iPendientes = $iLineasXEst;
	}
	if ($bRevisaLineas) {
		// Ahora sumar las lineas de profundizacion
		$sSQL = 'SELECT core11idlineaprof, SUM(core11numcreditos) AS Creditos 
		FROM core11plandeestudio 
		WHERE core11idversionprograma=' . $core10id . ' AND core11idlineaprof<>0
		GROUP BY core11idlineaprof
		ORDER BY SUM(core11numcreditos)';
		$tabla = $objDB->ejecutasql($sSQL);
		while ($fila = $objDB->sf($tabla)) {
			if ($iPendientes > 0) {
				$sCondiLineaProf = $sCondiLineaProf . ', ' . $fila['core11idlineaprof'] . '';
				$iPendientes--;
			}
		}
	}
	$sSQL = 'SELECT core11tiporegistro, SUM(core11numcreditos) AS Creditos, COUNT(core11id) AS Cursos 
	FROM core11plandeestudio 
	WHERE core11idversionprograma=' . $core10id . ' AND core11idlineaprof IN (0' . $sCondiLineaProf . ') ' . $sCondiAprobados . ' 
	GROUP BY core11tiporegistro';
	$tabla = $objDB->ejecutasql($sSQL);
	while ($fila = $objDB->sf($tabla)) {
		switch ($fila['core11tiporegistro']) {
			case 0:
				$iNumCredBasicos = $fila['Creditos'];
				break;
			case 1:
				$iNumCredEspecificos = $fila['Creditos'];
				break;
			case 5:
				$iNumCredElectivoBComun = $fila['Creditos'];
				break;
			case 6:
				$iNumCredElectivoDComun = $fila['Creditos'];
				break;
			case 2:
				$iNumCredElectivosDEsp = $fila['Creditos'];
				break;
			case 4:
				$iNumCredElectivoComp = $fila['Creditos'];
				break;
			case 3:
				$iNumCredRequisitos = $fila['Creditos'];
				break;
		}
	}
	if ($sCondiLineaProf != '') {
		$sSQL = 'SELECT core21consec, core21id, core21nombre
		FROM core21lineaprof
		WHERE core21id IN (-99' . $sCondiLineaProf . ')
		ORDER BY core21nombre ';
		$tabla = $objDB->ejecutasql($sSQL);
		while ($fila = $objDB->sf($tabla)) {
			if ($sInfoLineas != '') {
				$sInfoLineas = $sInfoLineas . ', ';
			}
			$sInfoLineas = $sInfoLineas . cadena_notildes($fila['core21nombre']);
		}
	}
	return array($iNumCredBasicos, $iNumCredEspecificos, $iNumCredElectivoBComun, $iNumCredElectivoDComun, $iNumCredElectivosDEsp, $iNumCredElectivoComp, $iNumCredRequisitos, $sInfoLineas);
}
function f2211_TituloCursoEnPlan($idCurso, $id10, $objDB, $bDebug = false)
{
	$sRes = '&nbsp;';
	$sDebug = '';
	if ((int)$id10 != 0) {
		$sRes = '{El curso ' . $idCurso . ' no esta asociado al plan de estudios ' . $id10 . '}';
		$sSQL = 'SELECT T2.unad40titulo, T2.unad40nombre, TB.core11nivelaplica, T3.core13nombre, TB.core11obligarorio, 
		TB.core11idequivalente, TB.core11homologable, TB.core11porsuficiencia, TB.core11numcreditos, TB.core11idequivalente 
		FROM core11plandeestudio AS TB, unad40curso AS T2, core13tiporegistroprog AS T3 
		WHERE TB.core11idversionprograma=' . $id10 . ' AND TB.core11idcurso=' . $idCurso . ' 
		AND TB.core11idcurso=T2.unad40id AND TB.core11tiporegistro=T3.core13id ';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			$sObligatorio = '';
			$sEquivalente = '';
			if ($fila['core11obligarorio'] != 0) {
				$sObligatorio = ' - OBLIGATORIO';
			}
			if ($fila['core11idequivalente'] != 0) {
				$sEquivalente = ' - EQUIVALENTE';
			}
			$sRes = $fila['unad40titulo'] . ' - ' . cadena_notildes($fila['unad40nombre']) . ' [Nivel ' . $fila['core11nivelaplica'] . ' - ' . $fila['core13nombre'] . $sObligatorio . ' - Cred. ' . $fila['core11numcreditos'] . $sEquivalente . ']';
		} else {
			//El curso puede ser un electivo IBC o EIC
			$idPrograma = 0;
			$sSQL = 'SELECT TB.core10idprograma, TB.core10numcredelecgenerales, TB.core10numcredelecescuela, 
			T9.core09idescuela, T9.cara09nivelformacion 
			FROM core10programaversion AS TB, core09programa AS T9 
			WHERE TB.core10id=' . $id10 . ' AND TB.core10idprograma=T9.core09id';
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . '<br><b>Consulta Cursos PEI</b>: Info base ' . $sSQL . '';
			}
			$tabla = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla) > 0) {
				$fila = $objDB->sf($tabla);
				$idEscuela = $fila['core09idescuela'];
				$idPrograma = $fila['core10idprograma'];
				$cara09nivelformacion = $fila['cara09nivelformacion'];
				if ($fila['core10numcredelecgenerales'] > 0) {
					$bConElectivosGenerales = true;
				}
				if ($fila['core10numcredelecescuela'] > 0) {
					$bConElectivosEscuela = true;
				}
			}
			if ($bConElectivosEscuela || $bConElectivosGenerales) {
				$sGruposNivel = '10,4,2'; //Por defecto el grupo de Tecnica, Tecnologia y profesional
				switch ($cara09nivelformacion) {
					case 3: //Especialización
					case 5: //Maestria
						$sGruposNivel = '3,5';
						break;
					case 6: //Doctorado
						$sGruposNivel = '6';
						break;
				}
				$sSQL = 'SELECT TB.core28idescuela, TB.core28idprograma, T40.unad40titulo, T40.unad40nombre, T40.unad40numcreditos, 
				T40.unad40semestre
				FROM core28electivos AS TB, unad40curso AS T40 
				WHERE ((TB.core28idescuela=' . $idEscuela . ') OR (TB.core28idescuela=0  AND TB.core28idprograma IN (0,' . $idPrograma . '))) 
				AND TB.core28ofertado="S" AND TB.core28idcurso=' . $idCurso . ' 
				AND TB.core28idcurso=T40.unad40id AND T40.unad40nivelformacion IN (' . $sGruposNivel . ')
				ORDER BY TB.core28idescuela DESC, TB.core28idprograma DESC';
				$tabla = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($tabla) > 0) {
					$fila = $objDB->sf($tabla);
					$sTipoE = 'EIC';
					if ($fila['core28idescuela'] == 0) {
						$sTipoE = 'IBC';
					}
					$sRes = $fila['unad40titulo'] . ' - ' . cadena_notildes($fila['unad40nombre']) . ' [' . $sTipoE . ' - Nivel: ' . $fila['unad40semestre'] . ' -  Cred: ' . $fila['unad40numcreditos'] . ']';
				}
			}
		}
	}
	return $sRes;
}
//Contacto de la escuela.
function f2212_Contacto($idEscuela, $idZona, $idCentro, $objDB, $iTipoContacto = 0, $bDebug = false)
{
	$sRes = '';
	$sDebug = '';
	//La idea es devolver el contacto de la escuela desde el directorio, entonces si tenemos centro va el centro si tenemos zona va por zona.
	if ((int)$idZona != 0) {
		//Viene una zona asi que tenemos que consultar el directorio.
		$sSQL = 'SELECT corf47correo 
		FROM corf47contactos 
		WHERE corf47idescuela=' . $idEscuela . ' AND corf47idzona=' . $idZona . ' AND corf47idcentro IN (0, ' . $idCentro . ') 
		AND corf47tipoconctacto=' . $iTipoContacto . ' AND corf47vigente=1 
		ORDER BY corf47idcentro DESC';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' Contacto ' . $sSQL . '<br>';
		}
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			$sRes = $fila['corf47correo'];
		}
	}
	//En caso de que nada, vamos a la escuela.
	if ($sRes == '') {
		$sSQL = 'SELECT core12correo FROM core12escuela WHERE core12id=' . $idEscuela . '';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' Contacto ' . $sSQL . '<br>';
		}
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			$sRes = $fila['core12correo'];
		}
	}
	return array($sRes, $sDebug);
}
//---
//Marzo 20 de 2021 - A veces necesitamos saber si la persona pertenece a una escuela en especificio.
//En esta función se devuelve a que escuela pertenece una persona, y se aplica solo para una escuela.
function f2212_EscuelaPerteneceV2($idTercero, $idRevisa, $objDB, $bDebug = false, $bIncluirLideres = true)
{
	$idEscuela = '';
	$idZona = '-1';
	$sDebug = '';
	$sCondi12 = '';
	$sCondi9 = '';
	$sCondi26 = '';
	if ((int)$idRevisa != 0) {
		$sCondi12 = 'TB.core12id=' . $idRevisa . ' AND ';
		$sCondi9 = 'TB.core09idescuela=' . $idRevisa . ' AND ';
		$sCondi26 = 'core26idescuela=' . $idRevisa . ' AND ';
	}
	$sSQL = 'SELECT TB.core12id 
	FROM core12escuela AS TB
	WHERE ' . $sCondi12 . '((TB.core12iddecano=' . $idTercero . ') OR (TB.core12idadministrador=' . $idTercero . '))';
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' Consultando Decanos y Secretarios: ' . $sSQL . '<br>';
	}
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$idEscuela = $fila['core12id'];
		$idZona = 0;
	} else {
		if ($bIncluirLideres) {
			//Puede ser lider de programa y entonces tambien le vamos a devolver la escuela.
			$sSQL = 'SELECT TB.core09idescuela 
			FROM core09programa AS TB
			WHERE ' . $sCondi9 . 'TB.core09iddirector=' . $idTercero . '';
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . ' Consultando Lideres de programa: ' . $sSQL . '<br>';
			}
			$tabla = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla) > 0) {
				$fila = $objDB->sf($tabla);
				$idEscuela = $fila['core09idescuela'];
				$idZona = 0;
			}
		}
		if ((int)$idEscuela == 0) {
			//Puede ser un zonal... entonces tambien pertenece.
			$sSQL = 'SELECT core26idescuela, core26idzona FROM core26espejos WHERE ' . $sCondi26 . 'core26idtercero=' . $idTercero . ' AND core26vigente="S"';
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . ' Consultando Espejos: ' . $sSQL . '<br>';
			}
			$tabla = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla) > 0) {
				$fila = $objDB->sf($tabla);
				$idEscuela = $fila['core26idescuela'];
				$idZona = $fila['core26idzona'];
			}
		}
	}
	return array($idEscuela, $idZona, $sDebug);
}
// Lista de zonas
function f2212_ListaCentros($idTercero, $objDB, $bDebug = false)
{
	$sIds = '-99';
	$sDebug = '';
	$sCondi26 = '';
	$sCondi12 = '';
	// Primero descartamos que sea un lider nacional.
	/*
	$sSQL = 'SELECT 1 
	FROM core26espejos 
	WHERE ' . $sCondi26 . 'core26idtercero=' . $idTercero . ' AND core26vigente="S" AND core26idzona=0';
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' Consultando Espejos de nivel nacional: ' . $sSQL . '<br>';
	}
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		// En caso de que no se devuelvan ids es porque tiene acceso a todo.
		$sIds = '';
	} else {
	}
	 */
	// OR (TB.unad23administrador=' . $idTercero . ')
	$sSQL = 'SELECT TB.unad24id 
	FROM unad24sede AS TB
	WHERE ' . $sCondi12 . '((TB.unad24director=' . $idTercero . '))';
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' Consultando Directores de centro: ' . $sSQL . '<br>';
	}
	$tabla = $objDB->ejecutasql($sSQL);
	while ($fila = $objDB->sf($tabla)) {
		$sIds = $sIds . ',' . $fila['unad24id'];
	}
	return array($sIds, $sDebug);
}
//Septiembre 2 de 2024 - Necesitamos tener el listado de escuelas a las que alguien pertenece
function f2212_ListaEscuelas($idTercero, $objDB, $bDebug = false)
{
	$sIds = '-99';
	$sDebug = '';
	$sCondi12 = '';
	$sCondi9 = '';
	$sCondi26 = '';
	// Primero descartamos que sea un lider nacional.
	$sSQL = 'SELECT 1 
	FROM core26espejos 
	WHERE ' . $sCondi26 . 'core26idtercero=' . $idTercero . ' AND core26vigente="S" AND core26idescuela=0';
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' Consultando Espejos de nivel nacional: ' . $sSQL . '<br>';
	}
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		// En caso de que no se devuelvan ids es porque tiene acceso a todo.
		$sIds = '';
	} else {
		$sSQL = 'SELECT TB.core12id 
		FROM core12escuela AS TB
		WHERE ' . $sCondi12 . '((TB.core12iddecano=' . $idTercero . ') OR (TB.core12idadministrador=' . $idTercero . '))';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' Consultando Decanos y Secretarios: ' . $sSQL . '<br>';
		}
		$tabla = $objDB->ejecutasql($sSQL);
		while ($fila = $objDB->sf($tabla)) {
			$sIds = $sIds . ',' . $fila['core12id'];
		}
		//Puede ser lider de programa y entonces tambien le vamos a devolver la escuela.
		$sSQL = 'SELECT TB.core09idescuela 
		FROM core09programa AS TB
		WHERE ' . $sCondi9 . 'TB.core09iddirector=' . $idTercero . '';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' Consultando Lideres de programa: ' . $sSQL . '<br>';
		}
		$tabla = $objDB->ejecutasql($sSQL);
		while ($fila = $objDB->sf($tabla)) {
			$sIds = $sIds . ',' . $fila['core09idescuela'];
		}
		//Puede ser un zonal... entonces tambien pertenece.
		$sSQL = 'SELECT core26idescuela, core26idzona 
		FROM core26espejos 
		WHERE ' . $sCondi26 . 'core26idtercero=' . $idTercero . ' AND core26vigente="S" AND core26idescuela<>0';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' Consultando Espejos: ' . $sSQL . '<br>';
		}
		$tabla = $objDB->ejecutasql($sSQL);
		while ($fila = $objDB->sf($tabla)) {
			$sIds = $sIds . ',' . $fila['core26idescuela'];
		}
	}
	return array($sIds, $sDebug);
}
// Lista de zonas
function f2212_ListaZonas($idTercero, $objDB, $bDebug = false)
{
	$sIds = '-99';
	$sDebug = '';
	$sCondi26 = '';
	$sCondi12 = '';
	$idPrimerZona = -1;
	// Primero descartamos que sea un lider nacional.
	$sSQL = 'SELECT 1 
	FROM core26espejos 
	WHERE ' . $sCondi26 . 'core26idtercero=' . $idTercero . ' AND core26vigente="S" AND core26idzona=0';
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' Consultando Espejos de nivel nacional: ' . $sSQL . '<br>';
	}
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		// En caso de que no se devuelvan ids es porque tiene acceso a todo.
		$sIds = '';
	} else {
		$sSQL = 'SELECT TB.unad23id 
		FROM unad23zona AS TB
		WHERE ' . $sCondi12 . '((TB.unad23director=' . $idTercero . ') OR (TB.unad23administrador=' . $idTercero . '))';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' Consultando Directores zonales y auxiliares: ' . $sSQL . '<br>';
		}
		$tabla = $objDB->ejecutasql($sSQL);
		while ($fila = $objDB->sf($tabla)) {
			$sIds = $sIds . ',' . $fila['unad23id'];
			if ($idPrimerZona == -1) {
				$idPrimerZona = $fila['unad23id'];
			}
		}
		//Puede ser un zonal... entonces tambien pertenece.
		$sSQL = 'SELECT core26idescuela, core26idzona 
		FROM core26espejos 
		WHERE ' . $sCondi26 . 'core26idtercero=' . $idTercero . ' AND core26vigente="S" AND core26idzona<>0';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' Consultando Espejos: ' . $sSQL . '<br>';
		}
		$tabla = $objDB->ejecutasql($sSQL);
		while ($fila = $objDB->sf($tabla)) {
			$sIds = $sIds . ',' . $fila['core26idzona'];
			if ($idPrimerZona == -1) {
				$idPrimerZona = $fila['core26idzona'];
			}
		}
	}
	return array($sIds, $sDebug, $idPrimerZona);
}
function f2300_EsConsejero($idTercero, $objDB, $bDebug = false)
{
	$bEsConsejero = false;
	$sIdCentro = '';
	$sDebug = '';
	$sSQL = 'SELECT cara01idcead FROM cara13consejeros WHERE cara13idconsejero=' . $idTercero . ' AND cara13activo="S" GROUP BY cara01idcead';
	$tabla = $objDB->ejecutasql($sSQL);
	while ($fila = $objDB->sf($tabla)) {
		$bEsConsejero = true;
		if ($sIdCentro != '') {
			$sIdCentro = $sIdCentro . ',';
		}
		$sIdCentro = $sIdCentro . $fila['cara01idcead'];
	}
	return array($bEsConsejero, $sIdCentro, $sDebug);
}
function f2300_ZonasTercero($idTercero, $objDB, $bDebug = false)
{
	$idPrimera = '';
	$sIdZona = '-99';
	$sDebug = '';
	$sSQL = 'SELECT cara21idzona FROM cara21lidereszona WHERE cara21idlider=' . $idTercero . ' AND cara21activo="S"';
	$tabla = $objDB->ejecutasql($sSQL);
	while ($fila = $objDB->sf($tabla)) {
		$sIdZona = $sIdZona . ',' . $fila['cara21idzona'];
		if ($idPrimera == '') {
			$idPrimera = $fila['cara21idzona'];
		}
	}
	return array($sIdZona, $idPrimera, $sDebug);
}
function f2402_TotalEstudiantesPeriodoCursoTutor($idTercero, $idPeriodo, $idCurso, $objDB, $bDebug = false)
{
	//Esto no es la tabla core20asignacion????
	$sError = '';
	$sDebug = '';
	$ceca02numest = 0;
	return array($ceca02numest, $sError, $sDebug);
}
function f2402_CargarActividades($idTercero, $idPeriodo, $idCurso, $objDB, $bForzar = false, $bDebug = false, $idContPeriodo = 0)
{
	//Agosto 31 de 2021 - Se revisa esta funcion porque no esta totalizando bien.
	$sError = '';
	$sDebug = '';
	$iNumRegistros = 0;
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' Iniciando revision de actividades para el tutor ' . $idTercero . ' Periodo ' . $idPeriodo . ' Curso ' . $idCurso . '<br>';
	}
	if ((int)$idPeriodo < 761) {
		return array($sError, $sDebug);
		die();
	}
	// Enero 8 de 2024 - Si se intenta crear actividades para el tercero 0, se ignoran.
	if ((int)$idTercero == 0) {
		return array($sError, $sDebug);
		die();
	}
	//Antes que nada saber si es un curso Externo.
	$sSQL = 'SELECT unad40modocalifica, unad40tipoexterno FROM unad40curso WHERE unad40id=' . $idCurso . '';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		if ($fila['unad40modocalifica'] == 9) {
			//Es un curso externo No se cargan actividades, solo se reportan.
			return array($sError, $sDebug);
			die();
		}
	}
	//Fin de verificar si es un curso externo.
	if ($idContPeriodo == 0) {
		$idContPeriodo = f146_Contenedor($idPeriodo, $objDB);
	}
	//Saber si la tabla existe.
	if ($idContPeriodo != 0) {
		$sNomTabla = 'ceca02actividadtutor_' . $idContPeriodo . '';
		$sSQL = 'SHOW TABLES LIKE "' . $sNomTabla . '"';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' Revisando que exista el contenedor ' . $sSQL . '<br>';
		}
		if ($objDB->nf($tabla) == 0) {
			$sSQL = 'CREATE TABLE ' . $sNomTabla . ' (ceca02idtutor int NOT NULL, ceca02idperaca int NOT NULL, ceca02idcurso int NOT NULL, 
			ceca02idactividad int NOT NULL, ceca02id int NULL DEFAULT 0, ceca02idestado int NULL DEFAULT 0, 
			ceca02fechainicio int NULL DEFAULT 0, ceca02fechacierrre int NULL DEFAULT 0, ceca02fecharetro int NULL DEFAULT 0, 
			ceca02peso int NULL DEFAULT 0, ceca02numgrupos int NULL DEFAULT 0, ceca02numest int NULL DEFAULT 0, 
			ceca02momentoest int NULL DEFAULT 0, ceca02idcierra int NULL DEFAULT 0, ceca02fechacierre int NULL DEFAULT 0, 
			ceca02mincierra int NULL DEFAULT 0, ceca02fechaimporta int NULL DEFAULT 0, ceca02estado0 int NULL DEFAULT 0, 
			ceca02estado1 int NULL DEFAULT 0, ceca02estado3 int NULL DEFAULT 0, ceca02estado5 int NULL DEFAULT 0, 
			ceca02estado7 int NULL DEFAULT 0, ceca02puntajeacum int NULL DEFAULT 0, ceca02numaprobados int NULL DEFAULT 0, 
			ceca02promedio Decimal(15,2) NULL DEFAULT 0, ceca02porcaproba Decimal(15,2) NULL DEFAULT 0)';
			$tabla = $objDB->ejecutasql($sSQL);
			$sSQL = 'ALTER TABLE ' . $sNomTabla . ' ADD PRIMARY KEY(ceca02id)';
			$tabla = $objDB->ejecutasql($sSQL);
			$sSQL = 'ALTER TABLE ' . $sNomTabla . ' ADD UNIQUE INDEX ceca02actividadtutor_id(ceca02idtutor, ceca02idperaca, ceca02idcurso, ceca02idactividad)';
			$tabla = $objDB->ejecutasql($sSQL);
			$sSQL = 'ALTER TABLE ' . $sNomTabla . ' ADD INDEX ceca02actividadtutor_tutor(ceca02idtutor)';
			$tabla = $objDB->ejecutasql($sSQL);
		}
	} else {
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' No se ha establecido un contenedor para el periodo ' . $idPeriodo . '<br>';
		}
	}
	//Ver si existen datos del tutor-curso
	$bEntra = false;
	if ($idContPeriodo != 0) {
		if ($bForzar) {
			$bEntra = true;
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . ' LIBDATOS - Se forza el proceso de actUalizacion de datos del tutor.<br>';
			}
		} else {
			if ($idCurso != '') {
				$sSQL = 'SELECT 1 FROM ' . $sNomTabla . ' WHERE ceca02idtutor=' . $idTercero . ' AND ceca02idperaca=' . $idPeriodo . ' AND ceca02idcurso=' . $idCurso . ' LIMIT 0, 1';
				if ($bDebug) {
					$sDebug = $sDebug . fecha_microtiempo() . ' Revisando si hay actividades para el tutor: ' . $sSQL . '<br>';
				}
				$tabla = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($tabla) == 0) {
					$bEntra = true;
				}
			} else {
				if ($bDebug) {
					$sDebug = $sDebug . fecha_microtiempo() . ' No se ha definido el curso a consultar.<br>';
				}
			}
		}
	}
	$iFechaArmaGrupos = 0;
	$bGestionarCargas = $bEntra;
	if ($bGestionarCargas) {
		//solo si es automatica
		$sSQL = 'SELECT ofer08id, ofer08estadooferta, ofer08grupoidforma, ofer08c2fechaarmagrupos 
		FROM ofer08oferta 
		WHERE ofer08idper_aca=' . $idPeriodo . ' AND ofer08idcurso=' . $idCurso . ' AND ofer08cead=0';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' Datos de la oferta: ' . $sSQL . '<br>';
		}
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			$iFechaArmaGrupos = $fila['ofer08c2fechaarmagrupos'];
			if ($fila['ofer08estadooferta'] != 1) {
				$sError = 'El curso no se encuentra ofertado.';
				$bEntra = false;
				$bGestionarCargas = false;
			}
			if ($fila['ofer08grupoidforma'] != 0) {
				$bGestionarCargas = false;
				if ($bDebug) {
					$sDebug = $sDebug . fecha_microtiempo() . ' <b>Asignaci&oacute;n de tutores</b> La distribuci&oacute;n es por tutor<br>';
				}
				//$sError='La asignaci&oacute;n de grupos no es automatica.';
			}
		}
	}
	if ($bGestionarCargas) {
		//A partir del periodo 952 se hace a la inversa, por tanto se revisa la asignación primero.
		$bAnterior = false;
		$iCupoALlenar = 0;
		$iCupoALiberar = 0;
		$idZonaTutor = 0;
		$idCentroTutor = 0;
		if ($idPeriodo < 952) {
			$bAnterior = true;
		} else {
			if ($idPeriodo < 1111) {
				$bAnterior = f2206th_PeriodosExcluidos($idPeriodo);
			}
		}
		if (!$bAnterior) {
			$iTolerancia = 2;
			//Revisar la asignación si tiene cupo disponible cargarle alumnos.
			$sSQL = 'SELECT core20numestudiantes, core20numestaplicados, core20idzona, core20idcentro 
			FROM core20asignacion  WHERE core20idtutor=' . $idTercero . ' AND core20idperaca=' . $idPeriodo . ' AND core20idcurso=' . $idCurso . '';
			$tabla = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla) > 0) {
				$fila = $objDB->sf($tabla);
				if ($fila['core20numestudiantes'] > $fila['core20numestaplicados']) {
					$iCupoALlenar = $fila['core20numestudiantes'] - $fila['core20numestaplicados'];
				} else {
					if ($fila['core20numestaplicados'] > ($fila['core20numestudiantes'] + $iTolerancia)) {
						$iCupoALiberar = $fila['core20numestaplicados'] - $fila['core20numestudiantes'];
					}
				}
				$idZonaTutor = $fila['core20idzona'];
				$idCentroTutor = $fila['core20idcentro'];
			}
		}
		if ($iCupoALlenar > 0) {
			$iPasos = 3;
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . ' <b>Asignaci&oacute;n de tutores</b> Cupo disponible: ' . $iCupoALlenar . '<br>';
			}
			for ($m = 1; $m <= $iPasos; $m++) {
				if ($iCupoALlenar > 0) {
					//Hacemos los barridos donde se le asignan grupos 1 de su mismo centro, 2 de su Zona, 3 lo que este disponible
					list($iAsignados, $iNumGrupos, $sDebugC, $sErrorC) = f2206_CambiarTutorGrupos($idPeriodo, $idCurso, $idContPeriodo, $m, $idTercero, $idCentroTutor, $idZonaTutor, $iCupoALlenar, 0, $objDB, $bDebug, $iFechaArmaGrupos);
					$sDebug = $sDebug . $sDebugC;
					if ($sErrorC != '') {
						if ($bDebug) {
							$sDebug = $sDebug . '<span class="rojo">' . $sErrorC . '</span><br>';
						}
					}
					$iCupoALlenar = $iCupoALlenar - $iAsignados;
				}
			}
		} else {
			if ($iCupoALiberar > 0) {
				//Le sobran cupos tratar de liberar.
				//Primero los que no tienen zona.
				for ($m = 3; $m >= 1; $m--) {
					if ($iCupoALiberar > 0) {
						list($iAsignados, $iNumGrupos, $sDebugC, $sErrorC) = f2206_CambiarTutorGrupos($idPeriodo, $idCurso, $idContPeriodo, $m, 0, $idCentroTutor, $idZonaTutor, $iCupoALiberar, $idTercero, $objDB, $bDebug, $iFechaArmaGrupos);
						$sDebug = $sDebug . $sDebugC;
						if ($sErrorC != '') {
							if ($bDebug) {
								$sDebug = $sDebug . '<span class="rojo">' . $sErrorC . '</span><br>';
							}
						}
						$iCupoALiberar = $iCupoALiberar - $iAsignados;
					}
				}
			}
		}
	}
	if ($bEntra) {
		//Diciembre 20 de 2021 - Solo si se forza se hace igualación de tutorees.
		if ($bForzar) {
			//Antes vamos a asegurarnos que las tablas 4 y 5 esté sincronizada con la tabla 6
			list($sError, $sDebugI) = f2206_IgualarTutoresV2($idPeriodo, $idCurso, $objDB, $idContPeriodo, $bDebug);
			$sDebug = $sDebug . $sDebugI;
		}
	}
	if ($bEntra) {
		//Verificar que no existan errores en la agenda base.
		$sSQL = 'SELECT TB.ofer18idactividad, COUNT(1) AS Reg 
		FROM ofer18agendaoferta AS TB 
		WHERE TB.ofer18curso=' . $idCurso . ' AND TB.ofer18per_aca=' . $idPeriodo . ' AND TB.ofer18numaula=1
		GROUP BY TB.ofer18idactividad
		HAVING COUNT(1)>1';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$bEntra = false;
			$sError = 'Se ha detectado un error de duplicidad de actividades en la agenda al momento del alistamiento, no es posible cargar actividades.';
			if ($bDebug) {
				$sIdsAct = '-99';
				while ($fila = $objDB->sf($tabla)) {
					$sIdsAct = $sIdsAct . ',' . $fila['ofer18idactividad'];
				}
				$sSQL = 'SELECT * 
				FROM ofer18agendaoferta AS TB 
				WHERE TB.ofer18curso=' . $idCurso . ' AND TB.ofer18per_aca=' . $idPeriodo . ' AND TB.ofer18numaula=1 AND TB.ofer18idactividad IN (' . $sIdsAct . ')';
				if ($bDebug) {
					$sDebug = $sDebug . fecha_microtiempo() . ' <span class="rojo">Actividades Duplicadas</span>: ' . $sSQL . '<br>';
				}
			}
		}
	}
	if ($bEntra) {
		//Alistar el id.
		$sTablaGrupos = 'core06grupos_' . $idContPeriodo;
		$ceca02id = tabla_consecutivo($sNomTabla, 'ceca02id', '', $objDB);
		$sCampos05 = 'INSERT INTO ' . $sNomTabla . ' (ceca02idtutor, ceca02idperaca, ceca02idcurso, ceca02idactividad, ceca02id, 
		ceca02idestado, ceca02fechainicio, ceca02fechacierrre, ceca02fecharetro, ceca02peso, 
		ceca02numgrupos, ceca02numest, ceca02momentoest) VALUES ';
		// Buscar la agenda de ese curso y duplicarsela al tutor.
		$ceca02numgrupos = 0;
		$ceca02numest = 0;
		$bPrimera = true;
		$sSQL5 = '';
		$sSQL = 'SELECT TB.ofer18idactividad, TB.ofer18fechainicio, TB.ofer18fechacierrre, TB.ofer18fecharetro, TB.ofer18peso, TB.ofer18unidad 
		FROM ofer18agendaoferta AS TB 
		WHERE TB.ofer18curso=' . $idCurso . ' AND TB.ofer18per_aca=' . $idPeriodo . ' AND TB.ofer18numaula=1';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' Consulta Actividades agenda: ' . $sSQL . '<br>';
		}
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) == 0) {
			$sError = 'NO SE HA ENCONTRADO UNA AGENDA PARA EL CURSO ' . $idCurso . '.';
		}
		while ($fila = $objDB->sf($tabla)) {
			$ceca02momentoest = 0;
			$sSQL = 'SELECT ofer03idmomento FROM ofer03cursounidad WHERE ofer03id=' . $fila['ofer18unidad'] . '';
			$tabla03 = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla03) > 0) {
				$fila03 = $objDB->sf($tabla03);
				$ceca02momentoest = $fila03['ofer03idmomento'];
			} else {
				//Octubre 15 de 2020 - Se devuelve un error para poder parar el processo.
				if ($fila['ofer18unidad'] == 0) {
					$sError = 'La actividad ' . $fila['ofer18idactividad'] . ' no esta asociada a una unidad. La agenda del curso debe ser corregida.';
				} else {
					$sError = 'La unidad ' . $fila['ofer18unidad'] . ' no tiene asociado un momento estadistico.';
				}
				//EL MOMENTO ESTA PERDIDO.
				if ($bDebug) {
					$sDebug = $sDebug . fecha_microtiempo() . ' <span class="rojo">Momento estadistico no encontrado. [Unidad ' . $fila['ofer18unidad'] . ']</span><br>';
				}
				return array($sError, $sDebug);
				die();
			}
			if ($bPrimera) {
				//Contar la cantidad de grupos .
				//Septiembre 31 de 2021, se corrige esta situacion.
				$ceca02numgrupos = 0;
				$ceca02numest = 0;
				$sIds06 = '-99';
				$sCondiAdd = '';
				if ($idPeriodo > Id_PeriodoInicioC2()) {
					//Totalizamos los grupos para no tener sorpresas.
					list($iActualizados, $sErrorG, $sDebugG) = f2406_TotalizarCurso($idPeriodo, $idCurso, $idContPeriodo, $objDB, $bDebug);
					/*
					$sSQL='SELECT core06id FROM '.$sTablaGrupos.' WHERE core06peraca='.$idPeriodo.' AND core06idcurso='.$idCurso.' AND core06idtutor='.$idTercero.' AND core06numinscritos=0';
					$tabla03=$objDB->ejecutasql($sSQL);
					while($fila03=$objDB->sf($tabla03)){
						//Totalizamos el grupo.
						list($iTotal, $sErrorT, $sDebugT)=f2406_TotalizarGrupo($idPeriodo, $fila03['core06id'], $idContPeriodo, $objDB, false);
						}
					$sCondiAdd=' AND core06numinscritos>0';
					*/
				}
				$sSQL = 'SELECT COUNT(1) AS NumGrupos, SUM(core06numinscritos) AS NumInscritos 
				FROM ' . $sTablaGrupos . ' 
				WHERE core06peraca=' . $idPeriodo . ' AND core06idcurso=' . $idCurso . ' AND core06idtutor=' . $idTercero . $sCondiAdd . '';
				if ($bDebug) {
					$sDebug = $sDebug . fecha_microtiempo() . ' Cantidad de grupos del profesor ' . $sSQL . '<br>';
				}
				$tabla03 = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($tabla03) > 0) {
					$fila03 = $objDB->sf($tabla03);
					$ceca02numgrupos = $fila03['NumGrupos'];
					$ceca02numest = $fila03['NumInscritos'];
				}
				// Ahora contamos los estudiantes.
				// AND TB.core05idactividad='.$fila['ofer18idactividad'].' 
				/*
				$sSQL='SHOW TABLES LIKE "core04%"';
				$sSQLBase='';
				if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Total Periodo: Lista de contenedores: '.$sSQL.'<br>';}
				$tablac=$objDB->ejecutasql($sSQL);
				while($filac=$objDB->sf($tablac)){
					$iContenedor=substr($filac[0], 16);
					if ((int)$iContenedor!=0){
						if ($sSQLBase!=''){$sSQLBase=$sSQLBase.' 
						UNION 
						';}
						$sSQLBase=$sSQLBase.'SELECT DISTINCT(TB.core04tercero) AS Total 
						FROM '.$filac[0].' AS TB, '.$sTablaGrupos.' AS T6 
						WHERE TB.core04idtutor='.$idTercero.' AND TB.core04idcurso='.$idCurso.' AND TB.core04peraca='.$idPeriodo.' AND TB.core04estado NOT IN (1, 9, 10) AND TB.core04idgrupo=T6.core06id';
						}
					}
				if ($sSQLBase!=''){
					if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta de total estudiantes :<br>'.$sSQLBase.'<br>
					--------------------<br>';}
					$tabla03=$objDB->ejecutasql($sSQLBase);
					$ceca02numest=$objDB->nf($tabla03);
					}
				*/
				//list($ceca02numest, $sErrorE, $sDebugE)=f2402_TotalEstudiantesPeriodoCursoTutor($idTercero, $idPeriodo, $idCurso, $objDB, $bDebug);
				$bPrimera = false;
			}
			//Termina de hacer el conteo
			$bInserta = true;
			if ($ceca02numgrupos == 0) {
				$bInserta = false;
				$bForzar = false;
				//La quitamos si existe.
				//16 Mar 2021 - Se quita esta opcion :  AND ceca02idestado IN (0, 1)
				$sSQL = 'DELETE FROM ' . $sNomTabla . ' WHERE ceca02idtutor=' . $idTercero . ' AND ceca02idperaca=' . $idPeriodo . ' AND ceca02idcurso=' . $idCurso . '';
				if ($bDebug) {
					$sDebug = $sDebug . fecha_microtiempo() . ' Retirando datos del profesor:' . $sSQL . '<br>';
				}
				$result = $objDB->ejecutasql($sSQL);
			}
			if ($bForzar) {
				//Si forza es posible que ya exista la data..
				$sSQL = 'SELECT ceca02id, ceca02numgrupos, ceca02numest, ceca02momentoest, ceca02peso 
				FROM ' . $sNomTabla . ' 
				WHERE ceca02idtutor=' . $idTercero . ' AND ceca02idperaca=' . $idPeriodo . ' AND ceca02idcurso=' . $idCurso . ' AND ceca02idactividad=' . $fila['ofer18idactividad'] . '';
				if ($bDebug) {
					$sDebug = $sDebug . fecha_microtiempo() . ' Verificando que la actividad exista:' . $sSQL . '<br>';
				}
				$tabla03 = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($tabla03) > 0) {
					$fila03 = $objDB->sf($tabla03);
					$bInserta = false;
					$iNumRegistros++;
					$sdatos = '';
					$scampo[1] = 'ceca02numgrupos';
					$scampo[2] = 'ceca02numest';
					$scampo[3] = 'ceca02momentoest';
					$scampo[4] = 'ceca02peso';
					$sdato[1] = $ceca02numgrupos;
					$sdato[2] = $ceca02numest;
					$sdato[3] = $ceca02momentoest;
					$sdato[4] = $fila['ofer18peso'];
					$numcmod = 4;
					for ($k = 1; $k <= $numcmod; $k++) {
						if ($fila03[$scampo[$k]] != $sdato[$k]) {
							if ($sdatos != '') {
								$sdatos = $sdatos . ', ';
							}
							$sdatos = $sdatos . $scampo[$k] . '="' . $sdato[$k] . '"';
						}
					}
					if ($sdatos != '') {
						$sSQL = 'UPDATE ' . $sNomTabla . ' SET ' . $sdatos . ' WHERE ceca02id=' . $fila03['ceca02id'] . '';
						if ($bDebug) {
							$sDebug = $sDebug . fecha_microtiempo() . ' Actualizando actividad ' . $sSQL . '<br>';
						}
						$result = $objDB->ejecutasql($sSQL);
					}
				}
			}
			if ($bInserta) {
				if ($ceca02numest != 0) {
					$ceca02fechainicio = fecha_EnNumero($fila['ofer18fechainicio']);
					$ceca02fechacierrre = fecha_EnNumero($fila['ofer18fechacierrre']);
					$ceca02fecharetro = fecha_EnNumero($fila['ofer18fecharetro']);
					if ($sSQL5 != '') {
						$sSQL5 = $sSQL5 . ',';
					}
					$sSQL5 = $sSQL5 . '(' . $idTercero . ', ' . $idPeriodo . ', ' . $idCurso . ', ' . $fila['ofer18idactividad'] . ', ' . $ceca02id . ', 0, ' . $ceca02fechainicio . ', ' . $ceca02fechacierrre . ', ' . $ceca02fecharetro . ', ' . $fila['ofer18peso'] . ', ' . $ceca02numgrupos . ', ' . $ceca02numest . ', ' . $ceca02momentoest . ')';
					$ceca02id++;
					$iNumRegistros++;
				}
			}
		}
		if ($sSQL5 != '') {
			$sSQL = $sCampos05 . $sSQL5;
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . ' Consulta Actividades del tutor: ' . $sSQL . '<br>';
			}
			$tabla = $objDB->ejecutasql($sSQL);
		} else {
		}
		if ($iNumRegistros == 0) {
			$sError = 'No se registran estudiantes a cargo para el tutor.';
		}
		//Actualizar la tabla de asignación.
		list($sError, $sDebugA) = f2220_RegistrarAsignacion($idTercero, $idPeriodo, $idCurso, $ceca02numest, $ceca02numgrupos, $objDB, $bDebug, false, true);
		$sDebug = $sDebug . $sDebugA;
		//Fin de la asignación.
	} else {
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' <b>No se actualizan las actividades</b><br>';
		}
	}
	return array($sError, $sDebug);
}
//Totalizamos un curso
function f2406_TotalizarGrupo($idPeriodo, $idGrupo, $idContenedor, $objDB, $bDebug = false)
{
	$sError = '';
	$sDebug = '';
	$iTotal = 0;
	if ($idContenedor == 0) {
		$idContenedor = f146_Contenedor($idPeriodo, $objDB);
	}
	if ($idContenedor != 0) {
		$sSQL = 'SHOW TABLES LIKE "core04%"';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' Total Periodo: Lista de contenedores: ' . $sSQL . '<br>';
		}
		$tablac = $objDB->ejecutasql($sSQL);
		while ($filac = $objDB->sf($tablac)) {
			$iContenedor = substr($filac[0], 16);
			//Estados que se quitan, 1 - No disponible, 2 Matricula externa, 9 Cancelado, 10 aplazado
			if ($iContenedor != 0) {
				$sSQL = 'SELECT 1 
				FROM core04matricula_' . $iContenedor . ' AS TB 
				WHERE TB.core04idgrupo=' . $idGrupo . ' AND TB.core04peraca=' . $idPeriodo . ' AND TB.core04estado NOT IN (1, 2, 9, 10)';
				$tabla = $objDB->ejecutasql($sSQL);
				$iTotal = $iTotal + $objDB->nf($tabla);
			}
		}
		//Actualizar el dato al grupo.
		$sSQL = 'UPDATE core06grupos_' . $idContenedor . ' SET core06numinscritos=' . $iTotal . ' WHERE core06id=' . $idGrupo . '';
		$tabla = $objDB->ejecutasql($sSQL);
	} else {
		$sError = 'No se ha definido un contenedor para el periodo ' . $idPeriodo . '';
	}
	return array($iTotal, $sError, $sDebug);
}
function f2491_OrigenNotaActividad($idPeriodo, $idCurso, $idActividad, $objDB, $bDebug = false)
{
	$iOrigenNota = 1;
	$sDebug = '';
	//Ubicar le origen de la nota
	$sSQL = 'SELECT TB.ofer18origennota 
	FROM ofer18agendaoferta AS TB 
	WHERE TB.ofer18per_aca=' . $idPeriodo . ' AND TB.ofer18curso=' . $idCurso . ' AND TB.ofer18numaula=1 AND TB.ofer18idactividad=' . $idActividad . '';
	$tabla18 = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla18) > 0) {
		$fila18 = $objDB->sf($tabla18);
		$iOrigenNota = $fila18['ofer18origennota'];
		if ($iOrigenNota == 0) {
			$iOrigenNota = 1;
		}
	}
	//Termina de ubicar la nota.
	return array($iOrigenNota, $sDebug);
}
function f3000_TablasInventario($idContenedor, $objDB, $bDebug = false)
{
	$sError = '';
	$sDebug = '';
	$sTabla = 'saiu23inventario_' . $idContenedor . '';
	$bIniciarContenedor = !$objDB->bexistetabla($sTabla);
	if ($bIniciarContenedor) {
		$sSQL = "CREATE TABLE " . $sTabla . " (saiu23idtercero int NOT NULL, saiu23modulo int NOT NULL, saiu23tabla int NOT NULL, saiu23idtabla int NOT NULL, saiu23fecha int NULL DEFAULT 0, saiu23idtipo int NULL DEFAULT 0, saiu23idtema int NULL DEFAULT 0, saiu23estado int NULL DEFAULT 0)";
		$bResultado = $objDB->ejecutasql($sSQL);
		if ($bResultado == false) {
			$sError = 'No ha sido posible iniciar la creaci&oacute;n del contenedor de invetario Ref: ' . $idContenedor . ', Por favor informe al administrador del sistema';
		}
		if ($sError == '') {
			$sSQL = "ALTER TABLE " . $sTabla . " ADD PRIMARY KEY(saiu23idtercero, saiu23modulo, saiu23tabla, saiu23idtabla)";
			$bResultado = $objDB->ejecutasql($sSQL);
			$sSQL = "ALTER TABLE " . $sTabla . " ADD INDEX saiu23inventario_fecha(saiu23fecha)";
			$bResultado = $objDB->ejecutasql($sSQL);
		}
	}
	return array($sError, $sDebug);
}
function f3000_AjustarTablas($objDB, $bDebug = false)
{
	$sDebug = '';
	$sSQL = 'SHOW TABLES LIKE "saiu05solicitud%"';
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' Total Periodo: Lista de contenedores: ' . $sSQL . '<br>';
	}
	$tablac = $objDB->ejecutasql($sSQL);
	while ($filac = $objDB->sf($tablac)) {
		$sTabla = $filac[0];
		$sSQL = 'SELECT saiu05idcategoria FROM ' . $sTabla . ' LIMIT 0, 1';
		$result = $objDB->ejecutasql($sSQL);
		if ($result == false) {
			$sSQL = 'ALTER TABLE ' . $sTabla . ' ADD saiu05idcategoria int NULL DEFAULT 0';
			$result = $objDB->ejecutasql($sSQL);
		}
		// Segunda etapa
		$sSQL = 'SELECT saiu05raddia FROM ' . $sTabla . ' LIMIT 0, 1';
		$result = $objDB->ejecutasql($sSQL);
		if ($result == false) {
			$sSQL = 'ALTER TABLE ' . $sTabla . ' ADD saiu05raddia int NULL DEFAULT 0, ADD saiu05radhora int NULL DEFAULT 0, ADD saiu05radmin int NULL DEFAULT 0, 
			ADD saiu05raddespcalend int NULL DEFAULT 0, ADD saiu05raddesphab int NULL DEFAULT 0';
			$result = $objDB->ejecutasql($sSQL);
		}
	}
	return array($sDebug);
}
function f3005_RevisarTabla($sContenedor, $objDB, $bDebug = false)
{
	$sError = '';
	$sDebug = '';
	$sTabla05 = 'saiu05solicitud_' . $sContenedor;
	$sSQL = "SELECT saiu05idarchivo FROM " . $sTabla05 . " LIMIT 0, 1;";
	$result = $objDB->ejecutasql($sSQL);
	if ($result == false) {
		$sSQL = "ALTER TABLE " . $sTabla05 . " 
		ADD saiu05idorigen INT NULL DEFAULT 0 AFTER saiu05respuesta,
		ADD saiu05idarchivo INT NULL DEFAULT 0 AFTER saiu05idorigen";
		$result = $objDB->ejecutasql($sSQL);
		if ($result == false) {
			$sError = $sError . ' Falla al intentar agregar campos de anexo respuesta';
		}
	}
	$sSQL = "SELECT saiu05evalconocimiento FROM " . $sTabla05 . " LIMIT 0, 1;";
	$result = $objDB->ejecutasql($sSQL);
	if ($result == false) {
		$sSQL = "ALTER TABLE " . $sTabla05 . " 
		ADD saiu05evalconocimiento INT NULL DEFAULT 0 AFTER saiu05evalsugerencias,
		ADD saiu05evalconocmotivo TEXT NULL AFTER saiu05evalconocimiento,
		ADD saiu05evalutilidad INT NULL DEFAULT 0 AFTER saiu05evalconocmotivo,
		ADD saiu05evalutilmotivo TEXT NULL AFTER saiu05evalutilidad";
		$result = $objDB->ejecutasql($sSQL);
		if ($result == false) {
			$sError = $sError . ' Falla al intentar agregar campos de encuesta';
		}
	}
	return array($sError, $sDebug);
}
function f3018_RevisarTabla($sContenedor, $objDB, $bDebug = false)
{
	$sError = '';
	$sDebug = '';
	$sTabla18 = 'saiu18telefonico_' . $sContenedor;
	$sSQL = "SELECT saiu18fecharespcaso FROM " . $sTabla18 . " LIMIT 0, 1;";
	$result = $objDB->ejecutasql($sSQL);
	if ($result == false) {
		$sSQL = "ALTER TABLE " . $sTabla18 . " 
		ADD saiu18fecharespcaso INT NULL DEFAULT 0 AFTER saiu18respuesta,
		ADD saiu18horarespcaso INT NULL DEFAULT 0 AFTER saiu18fecharespcaso,
		ADD saiu18minrespcaso INT NULL DEFAULT 0 AFTER saiu18horarespcaso,
		ADD saiu18idunidadcaso INT NULL DEFAULT 0 AFTER saiu18minrespcaso,
		ADD saiu18idequipocaso INT NULL DEFAULT 0 AFTER saiu18idunidadcaso,
		ADD saiu18idsupervisorcaso INT NULL DEFAULT 0 AFTER saiu18idequipocaso,
		ADD saiu18idresponsablecaso INT NULL DEFAULT 0 AFTER saiu18idsupervisorcaso,
		ADD saiu18numref VARCHAR(20) NULL AFTER saiu18idresponsablecaso,
		ADD saiu18evalacepta INT NULL DEFAULT 0 AFTER saiu18numref,
		ADD saiu18evalfecha INT NULL DEFAULT 0 AFTER saiu18evalacepta,
		ADD saiu18evalamabilidad INT NULL DEFAULT 0 AFTER saiu18evalfecha,
		ADD saiu18evalamabmotivo TEXT AFTER saiu18evalamabilidad,
		ADD saiu18evalrapidez INT NULL DEFAULT 0 AFTER saiu18evalamabmotivo,
		ADD saiu18evalrapidmotivo TEXT AFTER saiu18evalrapidez,
		ADD saiu18evalclaridad INT NULL DEFAULT 0 AFTER saiu18evalrapidmotivo,
		ADD saiu18evalcalridmotivo TEXT AFTER saiu18evalclaridad,
		ADD saiu18evalresolvio INT NULL DEFAULT 0 AFTER saiu18evalcalridmotivo,
		ADD saiu18evalsugerencias TEXT AFTER saiu18evalresolvio,
		ADD saiu18evalconocimiento INT NULL DEFAULT 0 AFTER saiu18evalsugerencias,
		ADD saiu18evalconocmotivo TEXT AFTER saiu18evalconocimiento,
		ADD saiu18evalutilidad INT NULL DEFAULT 0 AFTER saiu18evalconocmotivo,
		ADD saiu18evalutilmotivo TEXT AFTER saiu18evalutilidad,
		ADD saiu18idorigen INT NULL DEFAULT 0 AFTER saiu18detalle,
		ADD saiu18idarchivo INT NULL DEFAULT 0 AFTER saiu18idorigen,
		ADD saiu18idorigenrta INT NULL DEFAULT 0 AFTER saiu18respuesta,
		ADD saiu18idarchivorta INT NULL DEFAULT 0 AFTER saiu18idorigenrta,
		ADD saiu18fechafin INT NULL DEFAULT 0 AFTER saiu18idarchivo";
		$result = $objDB->ejecutasql($sSQL);
		if ($result == false) {
			$sError = $sError . ' Falla al intentar agregar campos de encuesta';
		}
	}
	return array($sError, $sDebug);
}
function f3019_RevisarTabla($sContenedor, $objDB, $bDebug = false)
{
	$sError = '';
	$sDebug = '';
	$sTabla19 = 'saiu19chat_' . $sContenedor;
	$sSQL = "SELECT saiu19fecharespcaso FROM " . $sTabla19 . " LIMIT 0, 1;";
	$result = $objDB->ejecutasql($sSQL);
	if ($result == false) {
		$sSQL = "ALTER TABLE " . $sTabla19 . " 
		ADD saiu19respuesta TEXT AFTER saiu19numsesionchat,
		ADD saiu19fecharespcaso INT NULL DEFAULT 0 AFTER saiu19respuesta,
		ADD saiu19horarespcaso INT NULL DEFAULT 0 AFTER saiu19fecharespcaso,
		ADD saiu19minrespcaso INT NULL DEFAULT 0 AFTER saiu19horarespcaso,
		ADD saiu19idunidadcaso INT NULL DEFAULT 0 AFTER saiu19minrespcaso,
		ADD saiu19idequipocaso INT NULL DEFAULT 0 AFTER saiu19idunidadcaso,
		ADD saiu19idsupervisorcaso INT NULL DEFAULT 0 AFTER saiu19idequipocaso,
		ADD saiu19idresponsablecaso INT NULL DEFAULT 0 AFTER saiu19idsupervisorcaso,
		ADD saiu19numref VARCHAR(20) NULL AFTER saiu19idresponsablecaso,
		ADD saiu19evalacepta INT NULL DEFAULT 0 AFTER saiu19numref,
		ADD saiu19evalfecha INT NULL DEFAULT 0 AFTER saiu19evalacepta,
		ADD saiu19evalamabilidad INT NULL DEFAULT 0 AFTER saiu19evalfecha,
		ADD saiu19evalamabmotivo TEXT AFTER saiu19evalamabilidad,
		ADD saiu19evalrapidez INT NULL DEFAULT 0 AFTER saiu19evalamabmotivo,
		ADD saiu19evalrapidmotivo TEXT AFTER saiu19evalrapidez,
		ADD saiu19evalclaridad INT NULL DEFAULT 0 AFTER saiu19evalrapidmotivo,
		ADD saiu19evalcalridmotivo TEXT AFTER saiu19evalclaridad,
		ADD saiu19evalresolvio INT NULL DEFAULT 0 AFTER saiu19evalcalridmotivo,
		ADD saiu19evalsugerencias TEXT AFTER saiu19evalresolvio,
		ADD saiu19evalconocimiento INT NULL DEFAULT 0 AFTER saiu19evalsugerencias,
		ADD saiu19evalconocmotivo TEXT AFTER saiu19evalconocimiento,
		ADD saiu19evalutilidad INT NULL DEFAULT 0 AFTER saiu19evalconocmotivo,
		ADD saiu19evalutilmotivo TEXT AFTER saiu19evalutilidad,
		ADD saiu19idorigen INT NULL DEFAULT 0 AFTER saiu19detalle,
		ADD saiu19idarchivo INT NULL DEFAULT 0 AFTER saiu19idorigen,
		ADD saiu19idorigenrta INT NULL DEFAULT 0 AFTER saiu19respuesta,
		ADD saiu19idarchivorta INT NULL DEFAULT 0 AFTER saiu19idorigenrta,
		ADD saiu19fechafin INT NULL DEFAULT 0 AFTER saiu19idarchivo";
		$result = $objDB->ejecutasql($sSQL);
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' Consulta f3019_RevisarTabla: ' . $sSQL . '<br>';
		}
		if ($result == false) {
			$sError = $sError . ' Falla al intentar agregar campos de encuesta';
		}
	}
	return array($sError, $sDebug);
}
function f3020_RevisarTabla($sContenedor, $objDB, $bDebug = false)
{
	$sError = '';
	$sDebug = '';
	$sTabla20 = 'saiu20correo_' . $sContenedor;
	$sSQL = "SELECT saiu20fecharespcaso FROM " . $sTabla20 . " LIMIT 0, 1;";
	$result = $objDB->ejecutasql($sSQL);
	if ($result == false) {
		$sSQL = "ALTER TABLE " . $sTabla20 . " 
		ADD saiu20idcorreootro varchar(50) NULL AFTER saiu20idcorreo,
		ADD saiu20fecharespcaso INT NULL DEFAULT 0 AFTER saiu20respuesta,
		ADD saiu20horarespcaso INT NULL DEFAULT 0 AFTER saiu20fecharespcaso,
		ADD saiu20minrespcaso INT NULL DEFAULT 0 AFTER saiu20horarespcaso,
		ADD saiu20idunidadcaso INT NULL DEFAULT 0 AFTER saiu20minrespcaso,
		ADD saiu20idequipocaso INT NULL DEFAULT 0 AFTER saiu20idunidadcaso,
		ADD saiu20idsupervisorcaso INT NULL DEFAULT 0 AFTER saiu20idequipocaso,
		ADD saiu20idresponsablecaso INT NULL DEFAULT 0 AFTER saiu20idsupervisorcaso,
		ADD saiu20numref VARCHAR(20) NULL AFTER saiu20idresponsablecaso,
		ADD saiu20evalacepta INT NULL DEFAULT 0 AFTER saiu20numref,
		ADD saiu20evalfecha INT NULL DEFAULT 0 AFTER saiu20evalacepta,
		ADD saiu20evalamabilidad INT NULL DEFAULT 0 AFTER saiu20evalfecha,
		ADD saiu20evalamabmotivo TEXT AFTER saiu20evalamabilidad,
		ADD saiu20evalrapidez INT NULL DEFAULT 0 AFTER saiu20evalamabmotivo,
		ADD saiu20evalrapidmotivo TEXT AFTER saiu20evalrapidez,
		ADD saiu20evalclaridad INT NULL DEFAULT 0 AFTER saiu20evalrapidmotivo,
		ADD saiu20evalcalridmotivo TEXT AFTER saiu20evalclaridad,
		ADD saiu20evalresolvio INT NULL DEFAULT 0 AFTER saiu20evalcalridmotivo,
		ADD saiu20evalsugerencias TEXT AFTER saiu20evalresolvio,
		ADD saiu20evalconocimiento INT NULL DEFAULT 0 AFTER saiu20evalsugerencias,
		ADD saiu20evalconocmotivo TEXT AFTER saiu20evalconocimiento,
		ADD saiu20evalutilidad INT NULL DEFAULT 0 AFTER saiu20evalconocmotivo,
		ADD saiu20evalutilmotivo TEXT AFTER saiu20evalutilidad,
		ADD saiu20idorigen INT NULL DEFAULT 0 AFTER saiu20detalle,
		ADD saiu20idarchivo INT NULL DEFAULT 0 AFTER saiu20idorigen,
		ADD saiu20idorigenrta INT NULL DEFAULT 0 AFTER saiu20respuesta,
		ADD saiu20idarchivorta INT NULL DEFAULT 0 AFTER saiu20idorigenrta,
		ADD saiu20fechafin INT NULL DEFAULT 0 AFTER saiu20idarchivo";
		$result = $objDB->ejecutasql($sSQL);
		if ($result == false) {
			$sError = $sError . ' Falla al intentar agregar campos de encuesta';
		}
	}
	return array($sError, $sDebug);
}
function f3021_RevisarTabla($sContenedor, $objDB, $bDebug = false)
{
	$sError = '';
	$sDebug = '';
	$sTabla21 = 'saiu21directa_' . $sContenedor;
	$sSQL = "SELECT saiu21fecharespcaso FROM " . $sTabla21 . " LIMIT 0, 1;";
	$result = $objDB->ejecutasql($sSQL);
	if ($result == false) {
		$sSQL = "ALTER TABLE " . $sTabla21 . " 
		ADD saiu21fecharespcaso INT NULL DEFAULT 0 AFTER saiu21respuesta,
		ADD saiu21horarespcaso INT NULL DEFAULT 0 AFTER saiu21fecharespcaso,
		ADD saiu21minrespcaso INT NULL DEFAULT 0 AFTER saiu21horarespcaso,
		ADD saiu21idunidadcaso INT NULL DEFAULT 0 AFTER saiu21minrespcaso,
		ADD saiu21idequipocaso INT NULL DEFAULT 0 AFTER saiu21idunidadcaso,
		ADD saiu21idsupervisorcaso INT NULL DEFAULT 0 AFTER saiu21idequipocaso,
		ADD saiu21idresponsablecaso INT NULL DEFAULT 0 AFTER saiu21idsupervisorcaso,
		ADD saiu21numref VARCHAR(21) NULL AFTER saiu21idresponsablecaso,
		ADD saiu21evalacepta INT NULL DEFAULT 0 AFTER saiu21numref,
		ADD saiu21evalfecha INT NULL DEFAULT 0 AFTER saiu21evalacepta,
		ADD saiu21evalamabilidad INT NULL DEFAULT 0 AFTER saiu21evalfecha,
		ADD saiu21evalamabmotivo TEXT AFTER saiu21evalamabilidad,
		ADD saiu21evalrapidez INT NULL DEFAULT 0 AFTER saiu21evalamabmotivo,
		ADD saiu21evalrapidmotivo TEXT AFTER saiu21evalrapidez,
		ADD saiu21evalclaridad INT NULL DEFAULT 0 AFTER saiu21evalrapidmotivo,
		ADD saiu21evalcalridmotivo TEXT AFTER saiu21evalclaridad,
		ADD saiu21evalresolvio INT NULL DEFAULT 0 AFTER saiu21evalcalridmotivo,
		ADD saiu21evalsugerencias TEXT AFTER saiu21evalresolvio,
		ADD saiu21evalconocimiento INT NULL DEFAULT 0 AFTER saiu21evalsugerencias,
		ADD saiu21evalconocmotivo TEXT AFTER saiu21evalconocimiento,
		ADD saiu21evalutilidad INT NULL DEFAULT 0 AFTER saiu21evalconocmotivo,
		ADD saiu21evalutilmotivo TEXT AFTER saiu21evalutilidad,
		ADD saiu21idorigen INT NULL DEFAULT 0 AFTER saiu21detalle,
		ADD saiu21idarchivo INT NULL DEFAULT 0 AFTER saiu21idorigen,
		ADD saiu21idorigenrta INT NULL DEFAULT 0 AFTER saiu21respuesta,
		ADD saiu21idarchivorta INT NULL DEFAULT 0 AFTER saiu21idorigenrta,
		ADD saiu21fechafin INT NULL DEFAULT 0 AFTER saiu21idarchivo";
		$result = $objDB->ejecutasql($sSQL);
		if ($result == false) {
			$sError = $sError . ' Falla al intentar agregar campos de encuesta';
		}
	}
	return array($sError, $sDebug);
}
function f3073_RevisarTabla($sContenedor, $objDB, $bDebug = false)
{
	$sError = '';
	$sDebug = '';
	$sTabla73 = 'saiu73solusuario_' . $sContenedor;
	$sSQL = "SELECT saiu73idtelefono FROM " . $sTabla73 . " LIMIT 0, 1;";
	$result = $objDB->ejecutasql($sSQL);
	if ($result == false) {
		$sSQL = "ALTER TABLE " . $sTabla73 . " 
		ADD saiu73idcanal INT NULL DEFAULT 3073,
		ADD saiu73idtelefono INT NULL DEFAULT 0,
		ADD saiu73numtelefono VARCHAR(20) NULL,
		ADD saiu73numorigen VARCHAR(20) NULL,
		ADD saiu73idchat INT NULL DEFAULT 0,
		ADD saiu73numsesionchat VARCHAR(20) NULL,
		ADD saiu73idcorreo INT NULL DEFAULT 0,
		ADD saiu73idcorreootro VARCHAR(50) NULL,
		ADD saiu73correoorigen VARCHAR(50) NULL,
		ADD saiu73evalacepta INT NULL DEFAULT 0,
		ADD saiu73evalfecha INT NULL DEFAULT 0,
		ADD saiu73evalamabilidad INT NULL DEFAULT 0,
		ADD saiu73evalrapidez INT NULL DEFAULT 0,
		ADD saiu73evalclaridad INT NULL DEFAULT 0,
		ADD saiu73evalresolvio INT NULL DEFAULT 0,
		ADD saiu73evalconocimiento INT NULL DEFAULT 0,
		ADD saiu73evalutilidad INT NULL DEFAULT 0,
		ADD saiu73evalsugerencias TEXT";
		$result = $objDB->ejecutasql($sSQL);
		if ($result == false) {
			$sError = $sError . ' Falla al intentar agregar campos a la tabla';
		}
	}
	$sSQL = "SELECT saiu73fecharad FROM " . $sTabla73 . " LIMIT 0, 1;";
	$result = $objDB->ejecutasql($sSQL);
	if ($result == false) {
		$sSQL = "ALTER TABLE " . $sTabla73 . " ADD saiu73fecharad INT NULL DEFAULT 0";
		$result = $objDB->ejecutasql($sSQL);
		if ($result == false) {
			$sError = $sError . ' Falla al intentar agregar campo saiu73fecharad a la tabla';
		}
	}
	return array($sError, $sDebug);
}
function f3000_TablasMes($iAgno, $iMes, $objDB, $bDebug = false)
{
	$sError = '';
	$sDebug = '';
	$sTabla = 'saiu05solicitud_' . $iAgno . $iMes;
	$bIniciarContenedor = !$objDB->bexistetabla($sTabla);
	if ($bIniciarContenedor) {
		$sSQL = "CREATE TABLE " . $sTabla . " (saiu05agno int NOT NULL, saiu05mes int NOT NULL, saiu05tiporadicado int NOT NULL, 
		saiu05consec int NOT NULL, saiu05id int NULL DEFAULT 0, saiu05origenagno int NULL DEFAULT 0, 
		saiu05origenmes int NULL DEFAULT 0, saiu05origenid int NULL DEFAULT 0, saiu05dia int NULL DEFAULT 0, 
		saiu05hora int NULL DEFAULT 0, saiu05minuto int NULL DEFAULT 0, saiu05estado int NULL DEFAULT 0, 
		saiu05idmedio int NULL DEFAULT 0, saiu05idtemaorigen int NULL DEFAULT 0, saiu05idtemafin int NULL DEFAULT 0, 
		saiu05idtiposolorigen int NULL DEFAULT 0, saiu05idtiposolfin int NULL DEFAULT 0, saiu05idsolicitante int NULL DEFAULT 0, 
		saiu05idinteresado int NULL DEFAULT 0, saiu05tipointeresado int NULL DEFAULT 0, saiu05rptaforma int NULL DEFAULT 0, 
		saiu05rptacorreo varchar(50) NULL, saiu05rptadireccion varchar(100) NULL, saiu05costogenera int NULL DEFAULT 0, 
		saiu05costovalor Decimal(15,2) NULL DEFAULT 0, saiu05costorefpago varchar(50) NULL, saiu05prioridad int NULL DEFAULT 0, 
		saiu05idzona int NULL DEFAULT 0, saiu05cead int NULL DEFAULT 0, saiu05numref varchar(20) NULL, 
		saiu05detalle Text NULL, saiu05infocomplemento Text NULL, saiu05idunidadresp int NULL DEFAULT 0, 
		saiu05idequiporesp int NULL DEFAULT 0, saiu05idsupervisor int NULL DEFAULT 0, saiu05idresponsable int NULL DEFAULT 0, 
		saiu05idescuela int NULL DEFAULT 0, saiu05idprograma int NULL DEFAULT 0, saiu05idperiodo int NULL DEFAULT 0, 
		saiu05idcurso int NULL DEFAULT 0, saiu05idgrupo int NULL DEFAULT 0, saiu05tiemprespdias int NULL DEFAULT 0, 
		saiu05tiempresphoras int NULL DEFAULT 0, saiu05fecharespprob int NULL DEFAULT 0, saiu05respuesta Text NULL, 
		saiu05idorigen int NULL DEFAULT 0, saiu05idarchivo int NULL DEFAULT 0,
		saiu05fecharespdef int NULL DEFAULT 0, saiu05horarespdef int NULL DEFAULT 0, saiu05minrespdef int NULL DEFAULT 0, 
		saiu05diasproc int NULL DEFAULT 0, saiu05minproc int NULL DEFAULT 0, saiu05diashabproc int NULL DEFAULT 0, 
		saiu05minhabproc int NULL DEFAULT 0, saiu05idmoduloproc int NULL DEFAULT 0, saiu05identificadormod int NULL DEFAULT 0, 
		saiu05numradicado int NULL DEFAULT 0, saiu05evalacepta int NULL DEFAULT 0, saiu05evalfecha int NULL DEFAULT 0, 
		saiu05evalamabilidad int NULL DEFAULT 0, saiu05evalamabmotivo Text NULL, saiu05evalrapidez int NULL DEFAULT 0, 
		saiu05evalrapidmotivo Text NULL, saiu05evalclaridad int NULL DEFAULT 0, saiu05evalcalridmotivo Text NULL, 
		saiu05evalresolvio int NULL DEFAULT 0, saiu05evalsugerencias Text NULL, saiu05evalconocimiento int NULL DEFAULT 0, 
		saiu05evalconocmotivo Text NULL, saiu05evalutilidad int NULL DEFAULT 0, saiu05evalutilmotivo Text NULL, saiu05idcategoria int NULL DEFAULT 0, 
		saiu05raddia int NULL DEFAULT 0, saiu05radhora int NULL DEFAULT 0, saiu05radmin int NULL DEFAULT 0, 
		saiu05raddespcalend int NULL DEFAULT 0, saiu05raddesphab int NULL DEFAULT 0)";
		$bResultado = $objDB->ejecutasql($sSQL);
		if ($bResultado == false) {
			$sError = 'No ha sido posible iniciar la creaci&oacute;n de lso contenedores para ' . $iAgno . $iMes . ', Por favor informe al administrador del sistema';
		} else {
			list($sError, $sDebugT) = f3005_RevisarTabla($iAgno . $iMes, $objDB, $bDebug);
		}
		if ($sError == '') {
			$sSQL = "ALTER TABLE " . $sTabla . " ADD PRIMARY KEY(saiu05id)";
			$bResultado = $objDB->ejecutasql($sSQL);
			$sSQL = "ALTER TABLE " . $sTabla . " ADD UNIQUE INDEX saiu05solicitud_id(saiu05agno, saiu05mes, saiu05tiporadicado, saiu05consec)";
			$bResultado = $objDB->ejecutasql($sSQL);
			$sSQL = "ALTER TABLE " . $sTabla . " ADD INDEX saiu05solicitud_solicitante(saiu05idsolicitante)";
			$bResultado = $objDB->ejecutasql($sSQL);
			$sSQL = "ALTER TABLE " . $sTabla . " ADD INDEX saiu05solicitud_interesado(saiu05idinteresado)";
			$bResultado = $objDB->ejecutasql($sSQL);
			$sSQL = "ALTER TABLE " . $sTabla . " ADD INDEX saiu05solicitud_responsable(saiu05idresponsable)";
			$bResultado = $objDB->ejecutasql($sSQL);
			$sSQL = "ALTER TABLE " . $sTabla . " ADD INDEX saiu05solicitud_estado(saiu05estado)";
			$bResultado = $objDB->ejecutasql($sSQL);
			$sSQL = "ALTER TABLE " . $sTabla . " ADD INDEX saiu05solicitud_unidad(saiu05idunidadresp)";
			$bResultado = $objDB->ejecutasql($sSQL);
			$sSQL = "ALTER TABLE " . $sTabla . " ADD INDEX saiu05solicitud_equipo(saiu05idequiporesp)";
			$bResultado = $objDB->ejecutasql($sSQL);
			$sSQL = "ALTER TABLE " . $sTabla . " ADD INDEX saiu05solicitud_supervisor(saiu05idsupervisor)";
			$bResultado = $objDB->ejecutasql($sSQL);
			$sSQL = "ALTER TABLE " . $sTabla . " ADD INDEX saiu05solicitud_radicado(saiu05numradicado)";
			$bResultado = $objDB->ejecutasql($sSQL);
			$sSQL = "ALTER TABLE " . $sTabla . " ADD INDEX saiu05solicitud_referencia(saiu05numref)";
			$bResultado = $objDB->ejecutasql($sSQL);

			$sTabla = 'saiu06solanotacion_' . $iAgno . $iMes;
			$sSQL = "CREATE TABLE " . $sTabla . " (saiu06idsolicitud int NOT NULL, saiu06consec int NOT NULL, saiu06id int NULL DEFAULT 0, saiu06anotacion Text NULL, saiu06visible varchar(1) NULL, saiu06descartada varchar(1) NULL, saiu06idorigen int NULL DEFAULT 0, saiu06idarchivo int NULL DEFAULT 0, saiu06idusuario int NULL DEFAULT 0, saiu06fecha int NULL DEFAULT 0, saiu06hora int NULL DEFAULT 0, saiu06minuto int NULL DEFAULT 0)";
			$bResultado = $objDB->ejecutasql($sSQL);
			$sSQL = "ALTER TABLE " . $sTabla . " ADD PRIMARY KEY(saiu06id)";
			$bResultado = $objDB->ejecutasql($sSQL);
			$sSQL = "ALTER TABLE " . $sTabla . " ADD UNIQUE INDEX saiu06solanotacion_id(saiu06idsolicitud, saiu06consec)";
			$bResultado = $objDB->ejecutasql($sSQL);
			$sSQL = "ALTER TABLE " . $sTabla . " ADD INDEX saiu06solanotacion_padre(saiu06idsolicitud)";
			$bResultado = $objDB->ejecutasql($sSQL);
			$sTabla = 'saiu07anexos_' . $iAgno . $iMes;
			$sSQL = "CREATE TABLE " . $sTabla . " (saiu07idsolicitud int NOT NULL, saiu07consec int NOT NULL, saiu07id int NULL DEFAULT 0, saiu07idtipoanexo int NULL DEFAULT 0, saiu07detalle varchar(100) NULL, saiu07idorigen int NULL DEFAULT 0, saiu07idarchivo int NULL DEFAULT 0, saiu07idusuario int NULL DEFAULT 0, saiu07fecha int NULL DEFAULT 0, saiu07hora int NULL DEFAULT 0, saiu07minuto int NULL DEFAULT 0, saiu07estado int NULL DEFAULT 0, saiu07idvalidad int NULL DEFAULT 0, saiu07fechavalida int NULL DEFAULT 0, saiu07horavalida int NULL DEFAULT 0, saiu07minvalida int NULL DEFAULT 0)";
			$bResultado = $objDB->ejecutasql($sSQL);
			$sSQL = "ALTER TABLE " . $sTabla . " ADD PRIMARY KEY(saiu07id)";
			$bResultado = $objDB->ejecutasql($sSQL);
			$sSQL = "ALTER TABLE " . $sTabla . " ADD UNIQUE INDEX saiu07anexos_id(saiu07idsolicitud, saiu07consec)";
			$bResultado = $objDB->ejecutasql($sSQL);
			$sSQL = "ALTER TABLE " . $sTabla . " ADD INDEX saiu07anexos_padre(saiu07idsolicitud)";
			$bResultado = $objDB->ejecutasql($sSQL);
			$sTabla = 'saiu08solinteresados_' . $iAgno . $iMes;
			$sSQL = "CREATE TABLE " . $sTabla . " (saiu08idsolicitud int NOT NULL, saiu08idinteresado int NOT NULL, saiu08id int NULL DEFAULT 0, saiu08detalle varchar(250) NULL)";
			$bResultado = $objDB->ejecutasql($sSQL);
			$sSQL = "ALTER TABLE " . $sTabla . " ADD PRIMARY KEY(saiu08id)";
			$bResultado = $objDB->ejecutasql($sSQL);
			$sSQL = "ALTER TABLE " . $sTabla . " ADD UNIQUE INDEX saiu08solinteresados_id(saiu08idsolicitud, saiu08idinteresado)";
			$bResultado = $objDB->ejecutasql($sSQL);
			$sSQL = "ALTER TABLE " . $sTabla . " ADD INDEX saiu08solinteresados_padre(saiu08idsolicitud)";
			$bResultado = $objDB->ejecutasql($sSQL);
			$sTabla = 'saiu09cambioestado_' . $iAgno . $iMes;
			$sSQL = "CREATE TABLE " . $sTabla . " (saiu09idsolicitud int NOT NULL, saiu09consec int NOT NULL, saiu09id int NULL DEFAULT 0, saiu09idestadoorigen int NULL DEFAULT 0, saiu09idestadofin int NULL DEFAULT 0, saiu09idusuario int NULL DEFAULT 0, saiu09fecha int NULL DEFAULT 0, saiu09hora int NULL DEFAULT 0, saiu09minuto int NULL DEFAULT 0)";
			$bResultado = $objDB->ejecutasql($sSQL);
			$sSQL = "ALTER TABLE " . $sTabla . " ADD PRIMARY KEY(saiu09id)";
			$bResultado = $objDB->ejecutasql($sSQL);
			$sSQL = "ALTER TABLE " . $sTabla . " ADD UNIQUE INDEX saiu09cambioestado_id(saiu09idsolicitud, saiu09consec)";
			$bResultado = $objDB->ejecutasql($sSQL);
			$sSQL = "ALTER TABLE " . $sTabla . " ADD INDEX saiu09cambioestado_padre(saiu09idsolicitud)";
			$bResultado = $objDB->ejecutasql($sSQL);
			$sTabla = 'saiu10cambioresponsable_' . $iAgno . $iMes;
			$sSQL = "CREATE TABLE " . $sTabla . " (saiu10idsolicitud int NOT NULL, saiu10consec int NOT NULL, saiu10id int NULL DEFAULT 0, saiu10idresporigen int NULL DEFAULT 0, saiu10idrespfin int NULL DEFAULT 0, saiu10idusuario int NULL DEFAULT 0, saiu10fecha int NULL DEFAULT 0, saiu10hora int NULL DEFAULT 0, saiu10minuto int NULL DEFAULT 0)";
			$bResultado = $objDB->ejecutasql($sSQL);
			$sSQL = "ALTER TABLE " . $sTabla . " ADD PRIMARY KEY(saiu10id)";
			$bResultado = $objDB->ejecutasql($sSQL);
			$sSQL = "ALTER TABLE " . $sTabla . " ADD UNIQUE INDEX saiu10cambioresponsable_id(saiu10idsolicitud, saiu10consec)";
			$bResultado = $objDB->ejecutasql($sSQL);
			$sSQL = "ALTER TABLE " . $sTabla . " ADD INDEX saiu10cambioresponsable_padre(saiu10idsolicitud)";
			$bResultado = $objDB->ejecutasql($sSQL);
		}
	}
	if ($sError == '') {
		$sTabla = 'saiu18telefonico_' . $iAgno;
		$bIniciarContenedor = !$objDB->bexistetabla($sTabla);
		if ($bIniciarContenedor) {
			$sSQL = "CREATE TABLE " . $sTabla . " (saiu18agno int NOT NULL, saiu18mes int NOT NULL, saiu18tiporadicado int NOT NULL, 
			saiu18consec int NOT NULL, saiu18id int NULL DEFAULT 0, saiu18dia int NULL DEFAULT 0, saiu18hora int NULL DEFAULT 0, 
			saiu18minuto int NULL DEFAULT 0, saiu18estado int NULL DEFAULT 0, saiu18idtelefono int NULL DEFAULT 0, 
			saiu18numtelefono varchar(20) NULL, saiu18idsolicitante int NULL DEFAULT 0, saiu18tipointeresado int NULL DEFAULT 0, 
			saiu18clasesolicitud int NULL DEFAULT 0, saiu18tiposolicitud int NULL DEFAULT 0, saiu18temasolicitud int NULL DEFAULT 0, 
			saiu18idzona int NULL DEFAULT 0, saiu18idcentro int NULL DEFAULT 0, saiu18codpais varchar(3) NULL, 
			saiu18coddepto varchar(5) NULL, saiu18codciudad varchar(8) NULL, saiu18idescuela int NULL DEFAULT 0, 
			saiu18idprograma int NULL DEFAULT 0, saiu18idperiodo int NULL DEFAULT 0, saiu18numorigen varchar(20) NULL, 
			saiu18idpqrs int NULL DEFAULT 0, saiu18detalle Text NULL, saiu18horafin int NULL DEFAULT 0, 
			saiu18minutofin int NULL DEFAULT 0, saiu18paramercadeo int NULL DEFAULT 0, saiu18idresponsable int NULL DEFAULT 0, 
			saiu18tiemprespdias int NULL DEFAULT 0, saiu18tiempresphoras int NULL DEFAULT 0, saiu18tiemprespminutos int NULL DEFAULT 0, 
			saiu18solucion int NULL DEFAULT 0, saiu18idcaso int NULL DEFAULT 0, saiu18respuesta Text NULL)";
			$bResultado = $objDB->ejecutasql($sSQL);
			if ($bResultado == false) {
				$sError = 'No ha sido posible iniciar la creaci&oacute;n de los contenedores para ' . $sTabla . ', Por favor informe al administrador del sistema';
			} else {
				list($sError, $sDebugT) = f3018_RevisarTabla($iAgno, $objDB, $bDebug);
			}
			if ($sError == '') {
				$sSQL = "ALTER TABLE " . $sTabla . " ADD PRIMARY KEY(saiu18id)";
				$bResultado = $objDB->ejecutasql($sSQL);
				$sSQL = "ALTER TABLE " . $sTabla . " ADD UNIQUE INDEX saiu18telefonico_id(saiu18agno, saiu18mes, saiu18tiporadicado, saiu18consec)";
				$bResultado = $objDB->ejecutasql($sSQL);
				//Indice por solicitante
				$sSQL = "ALTER TABLE " . $sTabla . " ADD INDEX saiu18telefonico_solicitante(saiu18idsolicitante)";
				$bResultado = $objDB->ejecutasql($sSQL);
				//Indice por tipo de solicitud.
				$sSQL = "ALTER TABLE " . $sTabla . " ADD INDEX saiu18telefonico_tema(saiu18temasolicitud)";
				$bResultado = $objDB->ejecutasql($sSQL);
			}
		}
	}
	if ($sError == '') {
		$sTabla = 'saiu19chat_' . $iAgno;
		$bIniciarContenedor = !$objDB->bexistetabla($sTabla);
		if ($bIniciarContenedor) {
			$sSQL = "CREATE TABLE " . $sTabla . " (saiu19agno int NOT NULL, saiu19mes int NOT NULL, saiu19tiporadicado int NOT NULL, 
			saiu19consec int NOT NULL, saiu19id int NULL DEFAULT 0, saiu19dia int NULL DEFAULT 0, saiu19hora int NULL DEFAULT 0, 
			saiu19minuto int NULL DEFAULT 0, saiu19estado int NULL DEFAULT 0, saiu19idchat int NULL DEFAULT 0, 
			saiu19idsolicitante int NULL DEFAULT 0, saiu19tipointeresado int NULL DEFAULT 0, saiu19clasesolicitud int NULL DEFAULT 0, 
			saiu19tiposolicitud int NULL DEFAULT 0, saiu19temasolicitud int NULL DEFAULT 0, saiu19idzona int NULL DEFAULT 0, 
			saiu19idcentro int NULL DEFAULT 0, saiu19codpais varchar(3) NULL, saiu19coddepto varchar(5) NULL, saiu19codciudad varchar(8) NULL, 
			saiu19idescuela int NULL DEFAULT 0, saiu19idprograma int NULL DEFAULT 0, saiu19idperiodo int NULL DEFAULT 0, 
			saiu19numorigen varchar(20) NULL, saiu19idpqrs int NULL DEFAULT 0, saiu19detalle Text NULL, saiu19horafin int NULL DEFAULT 0, 
			saiu19minutofin int NULL DEFAULT 0, saiu19paramercadeo int NULL DEFAULT 0, saiu19idresponsable int NULL DEFAULT 0, 
			saiu19tiemprespdias int NULL DEFAULT 0, saiu19tiempresphoras int NULL DEFAULT 0, saiu19tiemprespminutos int NULL DEFAULT 0, 
			saiu19solucion int NULL DEFAULT 0, saiu19idcaso int NULL DEFAULT 0, saiu19numsesionchat varchar(20) NULL)";
			$bResultado = $objDB->ejecutasql($sSQL);
			if ($bResultado == false) {
				$sError = 'No ha sido posible iniciar la creaci&oacute;n de los contenedores para ' . $sTabla . ', Por favor informe al administrador del sistema';
			} else {
				list($sError, $sDebugT) = f3019_RevisarTabla($iAgno, $objDB, $bDebug);
			}
			if ($sError == '') {
				$sSQL = "ALTER TABLE " . $sTabla . " ADD PRIMARY KEY(saiu19id)";
				$bResultado = $objDB->ejecutasql($sSQL);
				$sSQL = "ALTER TABLE " . $sTabla . " ADD UNIQUE INDEX saiu19chat_id(saiu19agno, saiu19mes, saiu19tiporadicado, saiu19consec)";
				$bResultado = $objDB->ejecutasql($sSQL);
				//Indice por solicitante
				$sSQL = "ALTER TABLE " . $sTabla . " ADD INDEX saiu19chat_solicitante(saiu19idsolicitante)";
				$bResultado = $objDB->ejecutasql($sSQL);
				//Indice por tipo de solicitud.
				$sSQL = "ALTER TABLE " . $sTabla . " ADD INDEX saiu19chat_tema(saiu19temasolicitud)";
				$bResultado = $objDB->ejecutasql($sSQL);
			}
		}
	}
	if ($sError == '') {
		$sTabla = 'saiu20correo_' . $iAgno;
		$bIniciarContenedor = !$objDB->bexistetabla($sTabla);
		if ($bIniciarContenedor) {
			$sSQL = "CREATE TABLE " . $sTabla . " (saiu20agno int NOT NULL, saiu20mes int NOT NULL, saiu20tiporadicado int NOT NULL, saiu20consec int NOT NULL, saiu20id int NULL DEFAULT 0, 
			saiu20origenagno int NULL, saiu20origenmes int NULL, saiu20origenid int NULL, saiu20dia int NULL DEFAULT 0, saiu20hora int NULL DEFAULT 0, saiu20minuto int NULL DEFAULT 0, 
			saiu20estado int NULL DEFAULT 0, saiu20idcorreo int NULL DEFAULT 0, saiu20numcorreo varchar(50) NULL, saiu20idsolicitante int NULL DEFAULT 0, saiu20tipointeresado int NULL DEFAULT 0, 
			saiu20clasesolicitud int NULL DEFAULT 0, saiu20tiposolicitud int NULL DEFAULT 0, saiu20temasolicitud int NULL DEFAULT 0, saiu20idzona int NULL DEFAULT 0, saiu20idcentro int NULL DEFAULT 0, 
			saiu20codpais varchar(3) NULL, saiu20coddepto varchar(5) NULL, saiu20codciudad varchar(8) NULL, saiu20idescuela int NULL DEFAULT 0, saiu20idprograma int NULL DEFAULT 0, 
			saiu20idperiodo int NULL DEFAULT 0, saiu20numorigen varchar(20) NULL, saiu20idpqrs int NULL DEFAULT 0, saiu20detalle Text NULL, saiu20horafin int NULL DEFAULT 0, saiu20minutofin int NULL DEFAULT 0, 
			saiu20paramercadeo int NULL DEFAULT 0, saiu20idresponsable int NULL DEFAULT 0, saiu20tiemprespdias int NULL DEFAULT 0, saiu20tiempresphoras int NULL DEFAULT 0, saiu20tiemprespminutos int NULL DEFAULT 0, 
			saiu20solucion int NULL DEFAULT 0, saiu20idcaso int NULL DEFAULT 0, saiu20respuesta Text NULL, saiu20correoorigen varchar(50) NULL)";
			$bResultado = $objDB->ejecutasql($sSQL);
			if ($bResultado == false) {
				$sError = 'No ha sido posible iniciar la creaci&oacute;n de los contenedores para ' . $sTabla . ', Por favor informe al administrador del sistema';
			} else {
				list($sError, $sDebugT) = f3020_RevisarTabla($iAgno, $objDB, $bDebug);
			}
			if ($sError == '') {
				$sSQL = "ALTER TABLE " . $sTabla . " ADD PRIMARY KEY(saiu20id)";
				$bResultado = $objDB->ejecutasql($sSQL);
				$sSQL = "ALTER TABLE " . $sTabla . " ADD UNIQUE INDEX saiu20correo_id(saiu20agno, saiu20mes, saiu20tiporadicado, saiu20consec)";
				$bResultado = $objDB->ejecutasql($sSQL);
				//Indice por solicitante
				$sSQL = "ALTER TABLE " . $sTabla . " ADD INDEX saiu20correo_solicitante(saiu20idsolicitante)";
				$bResultado = $objDB->ejecutasql($sSQL);
				//Indice por tipo de solicitud.
				$sSQL = "ALTER TABLE " . $sTabla . " ADD INDEX saiu20correo_tema(saiu20temasolicitud)";
				$bResultado = $objDB->ejecutasql($sSQL);
			}
		}
	}
	if ($sError == '') {
		$sTabla = 'saiu21directa_' . $iAgno;
		$bIniciarContenedor = !$objDB->bexistetabla($sTabla);
		if ($bIniciarContenedor) {
			$sSQL = "CREATE TABLE " . $sTabla . " (saiu21agno int NOT NULL, saiu21mes int NOT NULL, saiu21tiporadicado int NOT NULL, saiu21consec int NOT NULL, saiu21id int NULL DEFAULT 0, 
			saiu21origenagno int NULL DEFAULT 0, saiu21origenmes int NULL DEFAULT 0, saiu21origenid int NULL DEFAULT 0, saiu21dia int NULL DEFAULT 0, saiu21hora int NULL DEFAULT 0, saiu21minuto int NULL DEFAULT 0, 
			saiu21estado int NULL DEFAULT 0, saiu21idsolicitante int NULL DEFAULT 0, saiu21tipointeresado int NULL DEFAULT 0, saiu21clasesolicitud int NULL DEFAULT 0, saiu21tiposolicitud int NULL DEFAULT 0, 
			saiu21temasolicitud int NULL DEFAULT 0, saiu21idzona int NULL DEFAULT 0, saiu21idcentro int NULL DEFAULT 0, saiu21codpais varchar(3) NULL, saiu21coddepto varchar(5) NULL, saiu21codciudad varchar(8) NULL, 
			saiu21idescuela int NULL DEFAULT 0, saiu21idprograma int NULL DEFAULT 0, saiu21idperiodo int NULL DEFAULT 0, saiu21idpqrs int NULL DEFAULT 0, saiu21detalle Text NULL, saiu21horafin int NULL DEFAULT 0, 
			saiu21minutofin int NULL DEFAULT 0, saiu21paramercadeo int NULL DEFAULT 0, saiu21idresponsable int NULL DEFAULT 0, saiu21tiemprespdias int NULL DEFAULT 0, saiu21tiempresphoras int NULL DEFAULT 0, 
			saiu21tiemprespminutos int NULL DEFAULT 0, saiu21solucion int NULL DEFAULT 0, saiu21idcaso int NULL DEFAULT 0, saiu21respuesta Text NULL)";
			$bResultado = $objDB->ejecutasql($sSQL);
			if ($bResultado == false) {
				$sError = 'No ha sido posible iniciar la creaci&oacute;n de los contenedores para ' . $sTabla . ', Por favor informe al administrador del sistema';
			} else {
				list($sError, $sDebugT) = f3021_RevisarTabla($iAgno, $objDB, $bDebug);
			}
			if ($sError == '') {
				$sSQL = "ALTER TABLE " . $sTabla . " ADD PRIMARY KEY(saiu21id)";
				$bResultado = $objDB->ejecutasql($sSQL);
				$sSQL = "ALTER TABLE " . $sTabla . " ADD UNIQUE INDEX saiu21directa_id(saiu21agno, saiu21mes, saiu21tiporadicado, saiu21consec)";
				$bResultado = $objDB->ejecutasql($sSQL);
				//Indice por solicitante
				$sSQL = "ALTER TABLE " . $sTabla . " ADD INDEX saiu21directa_solicitante(saiu21idsolicitante)";
				$bResultado = $objDB->ejecutasql($sSQL);
				//Indice por tipo de solicitud.
				$sSQL = "ALTER TABLE " . $sTabla . " ADD INDEX saiu21directa_tema(saiu21temasolicitud)";
				$bResultado = $objDB->ejecutasql($sSQL);
			}
		}
	}
	if ($sError == '') {
		$sTabla = 'saiu73solusuario_' . $iAgno;
		$bIniciarContenedor = !$objDB->bexistetabla($sTabla);
		if ($bIniciarContenedor) {
			$sSQL = "CREATE TABLE " . $sTabla . " (saiu73agno int NOT NULL, saiu73mes int NOT NULL, saiu73tiporadicado int NOT NULL, saiu73consec int NOT NULL, saiu73id int NULL DEFAULT 0, 
			saiu73origenagno int NULL DEFAULT 0, saiu73origenmes int NULL DEFAULT 0, saiu73origenid int NULL DEFAULT 0, saiu73dia int NULL DEFAULT 0, saiu73hora int NULL DEFAULT 0, saiu73minuto int NULL DEFAULT 0, 
			saiu73estado int NULL DEFAULT 0, saiu73idsolicitante int NULL DEFAULT 0, saiu73tipointeresado int NULL DEFAULT 0, saiu73clasesolicitud int NULL DEFAULT 0, saiu73tiposolicitud int NULL DEFAULT 0, 
			saiu73temasolicitud int NULL DEFAULT 0, saiu73idzona int NULL DEFAULT 0, saiu73idcentro int NULL DEFAULT 0, saiu73codpais varchar(3) NULL, saiu73coddepto varchar(5) NULL, saiu73codciudad varchar(8) NULL, 
			saiu73idescuela int NULL DEFAULT 0, saiu73idprograma int NULL DEFAULT 0, saiu73idperiodo int NULL DEFAULT 0, saiu73idpqrs int NULL DEFAULT 0, saiu73detalle Text NULL, saiu73horafin int NULL DEFAULT 0, 
			saiu73minutofin int NULL DEFAULT 0, saiu73paramercadeo int NULL DEFAULT 0, saiu73idresponsable int NULL DEFAULT 0, saiu73tiemprespdias int NULL DEFAULT 0, saiu73tiempresphoras int NULL DEFAULT 0, 
			saiu73tiemprespminutos int NULL DEFAULT 0, saiu73solucion int NULL DEFAULT 0, saiu73idcaso int NULL DEFAULT 0, saiu73respuesta Text NULL, saiu73fecharespcaso INT NULL DEFAULT 0, 
			saiu73horarespcaso INT NULL DEFAULT 0, saiu73minrespcaso INT NULL DEFAULT 0, saiu73idunidadcaso INT NULL DEFAULT 0, saiu73idequipocaso INT NULL DEFAULT 0, saiu73idsupervisorcaso INT NULL DEFAULT 0, 
			saiu73idresponsablecaso INT NULL DEFAULT 0, saiu73numref VARCHAR(37) NULL, saiu73idorigen INT NULL DEFAULT 0, saiu73idarchivo INT NULL DEFAULT 0, saiu73idorigenrta INT NULL DEFAULT 0, 
			saiu73idarchivorta INT NULL DEFAULT 0, saiu73fechafin INT NULL DEFAULT 0)";
			$bResultado = $objDB->ejecutasql($sSQL);
			if ($bResultado == false) {
				$sError = 'No ha sido posible iniciar la creaci&oacute;n de los contenedores para ' . $sTabla . ', Por favor informe al administrador del sistema';
			} else {
				list($sError, $sDebugT) = f3073_RevisarTabla($iAgno, $objDB, $bDebug);
			}
			if ($sError == '') {
				$sSQL = "ALTER TABLE " . $sTabla . " ADD PRIMARY KEY(saiu73id)";
				$bResultado = $objDB->ejecutasql($sSQL);
				$sSQL = "ALTER TABLE " . $sTabla . " ADD UNIQUE INDEX saiu73directa_id(saiu73agno, saiu73mes, saiu73tiporadicado, saiu73consec)";
				$bResultado = $objDB->ejecutasql($sSQL);
				//Indice por solicitante
				$sSQL = "ALTER TABLE " . $sTabla . " ADD INDEX saiu73directa_solicitante(saiu73idsolicitante)";
				$bResultado = $objDB->ejecutasql($sSQL);
				//Indice por tipo de solicitud.
				$sSQL = "ALTER TABLE " . $sTabla . " ADD INDEX saiu73directa_tema(saiu73temasolicitud)";
				$bResultado = $objDB->ejecutasql($sSQL);
			}
		}
	}
	if ($sError == '') {
		$sTabla = 'saiu28mesaayuda_' . $iAgno;
		$bIniciarContenedor = !$objDB->bexistetabla($sTabla);
		if ($bIniciarContenedor) {
			$sSQL = "CREATE TABLE " . $sTabla . " (saiu28agno int NOT NULL, saiu28mes int NOT NULL, saiu28tiporadicado int NOT NULL, 
			saiu28consec int NOT NULL, saiu28id int NULL DEFAULT 0, saiu28dia int NULL DEFAULT 0, 
			saiu28hora int NULL DEFAULT 0, saiu28minuto int NULL DEFAULT 0, saiu28estado int NULL DEFAULT 0, 
			saiu28idsolicitante int NULL DEFAULT 0, saiu28tipointeresado int NULL DEFAULT 0, saiu28clasesolicitud int NULL DEFAULT 0, 
			saiu28tiposolicitud int NULL DEFAULT 0, saiu28temasolicitud int NULL DEFAULT 0, saiu28idzona int NULL DEFAULT 0, 
			saiu28idcentro int NULL DEFAULT 0, saiu28codpais varchar(3) NULL, saiu28coddepto varchar(5) NULL, 
			saiu28codciudad varchar(8) NULL, saiu28idescuela int NULL DEFAULT 0, saiu28idprograma int NULL DEFAULT 0, 
			saiu28idperiodo int NULL DEFAULT 0, saiu28idpqrs int NULL DEFAULT 0, saiu28detalle Text NULL, 
			saiu28horafin int NULL DEFAULT 0, saiu28minutofin int NULL DEFAULT 0, saiu28idresponsable int NULL DEFAULT 0, 
			saiu28tiemprespdias int NULL DEFAULT 0, saiu28tiempresphoras int NULL DEFAULT 0, saiu28tiemprespminutos int NULL DEFAULT 0, 
			saiu28solucion int NULL DEFAULT 0, saiu28numetapas int NULL DEFAULT 0, saiu28idunidadresp1 int NULL DEFAULT 0, 
			saiu28idequiporesp1 int NULL DEFAULT 0, saiu28idliderrespon1 int NULL DEFAULT 0, saiu28tiemprespdias1 int NULL DEFAULT 0, 
			saiu28tiempresphoras1 int NULL DEFAULT 0, saiu28centrotarea1 int NULL DEFAULT 0, saiu28tiempousado1 int NULL DEFAULT 0, 
			saiu28tiempocalusado1 int NULL DEFAULT 0, saiu28idunidadresp2 int NULL DEFAULT 0, saiu28idequiporesp2 int NULL DEFAULT 0, 
			saiu28idliderrespon2 int NULL DEFAULT 0, saiu28tiemprespdias2 int NULL DEFAULT 0, saiu28tiempresphoras2 int NULL DEFAULT 0, 
			saiu28centrotarea2 int NULL DEFAULT 0, saiu28tiempousado2 int NULL DEFAULT 0, saiu28tiempocalusado2 int NULL DEFAULT 0, 
			saiu28idunidadresp3 int NULL DEFAULT 0, saiu28idequiporesp3 int NULL DEFAULT 0, saiu28idliderrespon3 int NULL DEFAULT 0, 
			saiu28tiemprespdias3 int NULL DEFAULT 0, saiu28tiempresphoras3 int NULL DEFAULT 0, saiu28centrotarea3 int NULL DEFAULT 0, 
			saiu28tiempousado3 int NULL DEFAULT 0, saiu28tiempocalusado3 int NULL DEFAULT 0, saiu28idsupervisor int NULL DEFAULT 0, 
			saiu28moduloasociado int NULL DEFAULT 0, saiu28etapaactual int NULL DEFAULT 0, saiu28fechalimite int NULL DEFAULT 0, 
			saiu28horalimite int NULL DEFAULT 0, saiu28minlimite int NULL DEFAULT 0)";
			$bResultado = $objDB->ejecutasql($sSQL);
			$sSQL = "ALTER TABLE " . $sTabla . " ADD PRIMARY KEY(saiu28id)";
			$bResultado = $objDB->ejecutasql($sSQL);
			$sSQL = "ALTER TABLE " . $sTabla . " ADD UNIQUE INDEX saiu28mesaayuda_id(saiu28agno, saiu28mes, saiu28tiporadicado, saiu28consec)";
			$bResultado = $objDB->ejecutasql($sSQL);
			//Indice por solicitante
			$sSQL = "ALTER TABLE " . $sTabla . " ADD INDEX saiu28mesaayuda_solicitante(saiu28idsolicitante)";
			$bResultado = $objDB->ejecutasql($sSQL);
			//Indice por tipo de solicitud.
			$sSQL = "ALTER TABLE " . $sTabla . " ADD INDEX saiu28mesaayuda_tema(saiu28temasolicitud)";
			$bResultado = $objDB->ejecutasql($sSQL);
			//Indice por responsable
			$sSQL = "ALTER TABLE " . $sTabla . " ADD INDEX saiu28mesaayuda_responsable(saiu28idresponsable)";
			$bResultado = $objDB->ejecutasql($sSQL);
			//Indice de estado
			$sSQL = "ALTER TABLE " . $sTabla . " ADD INDEX saiu28mesaayuda_estado(saiu28estado)";
			$bResultado = $objDB->ejecutasql($sSQL);
		}
	}
	if ($sError == '') {
		$sTabla = 'saiu29anexos_' . $iAgno;
		$bIniciarContenedor = !$objDB->bexistetabla($sTabla);
		if ($bIniciarContenedor) {
			$sSQL = "CREATE TABLE " . $sTabla . " (saiu29idsolicitud int NOT NULL, saiu29idanexo int NOT NULL, saiu29consec int NOT NULL, saiu29id int NULL DEFAULT 0, saiu29idorigen int NULL DEFAULT 0, saiu29idarchivo int NULL DEFAULT 0, saiu29detalle Text NULL)";
			$bResultado = $objDB->ejecutasql($sSQL);
			$sSQL = "ALTER TABLE " . $sTabla . " ADD PRIMARY KEY(saiu29id)";
			$bResultado = $objDB->ejecutasql($sSQL);
			$sSQL = "ALTER TABLE " . $sTabla . " ADD UNIQUE INDEX saiu29anexos_id(saiu29idsolicitud, saiu29idanexo, saiu29consec)";
			$bResultado = $objDB->ejecutasql($sSQL);
			$sSQL = "ALTER TABLE " . $sTabla . " ADD INDEX saiu29anexos_padre(saiu29idsolicitud)";
			$bResultado = $objDB->ejecutasql($sSQL);
			$sTabla = 'saiu30anotaciones_' . $iAgno;
			$sSQL = "CREATE TABLE " . $sTabla . " (saiu30idsolicitud int NOT NULL, saiu30consec int NOT NULL, saiu30id int NULL DEFAULT 0, saiu30visiblealinteresado int NULL DEFAULT 0, saiu30anotacion Text NULL, saiu30idusuario int NULL DEFAULT 0, saiu30fecha int NULL DEFAULT 0, saiu30hora int NULL DEFAULT 0, saiu30minuto int NULL DEFAULT 0)";
			$bResultado = $objDB->ejecutasql($sSQL);
			$sSQL = "ALTER TABLE " . $sTabla . " ADD PRIMARY KEY(saiu30id)";
			$bResultado = $objDB->ejecutasql($sSQL);
			$sSQL = "ALTER TABLE " . $sTabla . " ADD UNIQUE INDEX saiu30anotaciones_id(saiu30idsolicitud, saiu30consec)";
			$bResultado = $objDB->ejecutasql($sSQL);
			$sSQL = "ALTER TABLE " . $sTabla . " ADD INDEX saiu30anotaciones_padre(saiu30idsolicitud)";
			$bResultado = $objDB->ejecutasql($sSQL);
			$sTabla = 'saiu39cambioestmesa_' . $iAgno;
			$sSQL = "CREATE TABLE " . $sTabla . " (saiu39idsolicitud int NOT NULL, saiu39consec int NOT NULL, saiu39id int NULL DEFAULT 0, saiu39idetapa int NULL DEFAULT 0, saiu39idresponsable int NULL DEFAULT 0, saiu39idestadorigen int NULL DEFAULT 0, saiu39idestadofin int NULL DEFAULT 0, saiu39detalle Text NULL, saiu39usuario int NULL DEFAULT 0, saiu39fecha int NULL DEFAULT 0, saiu39hora int NULL DEFAULT 0, saiu39minuto int NULL DEFAULT 0, saiu39correterminos int NULL DEFAULT 0, saiu39tiempousado int NULL DEFAULT 0, saiu39tiempocalusado int NULL DEFAULT 0)";
			$bResultado = $objDB->ejecutasql($sSQL);
			$sSQL = "ALTER TABLE " . $sTabla . " ADD PRIMARY KEY(saiu39id)";
			$bResultado = $objDB->ejecutasql($sSQL);
			$sSQL = "ALTER TABLE " . $sTabla . " ADD UNIQUE INDEX saiu39cambioestmesa_id(saiu39idsolicitud, saiu39consec)";
			$bResultado = $objDB->ejecutasql($sSQL);
			$sSQL = "ALTER TABLE " . $sTabla . " ADD INDEX saiu39cambioestmesa_padre(saiu39idsolicitud)";
			$bResultado = $objDB->ejecutasql($sSQL);
		}
	}
	if ($sError == '') {
		$sTabla = 'saiu47tramites_' . $iAgno;
		$bIniciarContenedor = !$objDB->bexistetabla($sTabla);
		if ($bIniciarContenedor) {
			$sSQL = "CREATE TABLE " . $sTabla . " (saiu47agno int NOT NULL, saiu47mes int NOT NULL, saiu47tiporadicado int NOT NULL, 
			saiu47tipotramite int NOT NULL, saiu47consec int NOT NULL, saiu47id int NULL DEFAULT 0, saiu47origenagno int NULL DEFAULT 0, 
			saiu47origenmes int NULL DEFAULT 0, saiu47origenid int NULL DEFAULT 0, saiu47dia int NULL DEFAULT 0, 
			saiu47hora int NULL DEFAULT 0, saiu47minuto int NULL DEFAULT 0, saiu47idsolicitante int NULL DEFAULT 0, 
			saiu47idperiodo int NULL DEFAULT 0, saiu47idescuela int NULL DEFAULT 0, saiu47idprograma int NULL DEFAULT 0, 
			saiu47idzona int NULL DEFAULT 0, saiu47idcentro int NULL DEFAULT 0, saiu47estado int NULL DEFAULT 0, 
			saiu47t1idmotivo int NULL DEFAULT 0, saiu47t1vrsolicitado Decimal(15,2) NULL DEFAULT 0, saiu47t1vraprobado Decimal(15,2) NULL DEFAULT 0, 
			saiu47t1vrsaldoafavor Decimal(15,2) NULL DEFAULT 0, saiu47t1vrdevolucion Decimal(15,2) NULL DEFAULT 0, 
			saiu47idbenefdevol int NULL DEFAULT 0, saiu47idaprueba int NULL DEFAULT 0, saiu47fechaaprueba int NULL DEFAULT 0, 
			saiu47horaaprueba int NULL DEFAULT 0, saiu47minutoaprueba int NULL DEFAULT 0, saiu47detalle Text NULL, 
			saiu47idunidad int NULL DEFAULT 0, saiu47idgrupotrabajo int NULL DEFAULT 0, saiu47idresponsable int NULL DEFAULT 0, 
			saiu47t707fecha int NULL DEFAULT 0, saiu47t707formarecaudo int NULL DEFAULT 0, saiu47t707identidadconv int NULL DEFAULT 0, 
			saiu47t707idbanco int NULL DEFAULT 0, saiu47t707idcuenta int NULL DEFAULT 0)";
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . ' <b>TABLA</b> ' . $sTabla . ': ' . $sSQL . '<br>';
			}
			$bResultado = $objDB->ejecutasql($sSQL);
			$sSQL = "ALTER TABLE " . $sTabla . " ADD PRIMARY KEY(saiu47id)";
			$bResultado = $objDB->ejecutasql($sSQL);
			$sSQL = "ALTER TABLE " . $sTabla . " ADD UNIQUE INDEX saiu47tramites_id(saiu47agno, saiu47mes, saiu47tiporadicado, saiu47tipotramite, saiu47consec)";
			$bResultado = $objDB->ejecutasql($sSQL);
			$sSQL = "ALTER TABLE " . $sTabla . " ADD INDEX saiu47tramites_tercero(saiu47idsolicitante)";
			$bResultado = $objDB->ejecutasql($sSQL);
			$sSQL = "ALTER TABLE " . $sTabla . " ADD INDEX saiu47tramites_estado(saiu47estado)";
			$bResultado = $objDB->ejecutasql($sSQL);
		} else {
			$sSQL = 'SELECT saiu47t707fecha FROM ' . $sTabla . ' LIMIT 0, 1';
			$bResultado = $objDB->ejecutasql($sSQL);
			if ($bResultado == false) {
				$sSQL = 'ALTER TABLE ' . $sTabla . ' ADD saiu47t707fecha int NULL DEFAULT 0';
				$bResultado = $objDB->ejecutasql($sSQL);
			}
			//Julio 11 de 2022 Se agregan los campos para cuentas bancarias.
			$sSQL = 'SELECT saiu47t707formarecaudo FROM ' . $sTabla . ' LIMIT 0, 1';
			$bResultado = $objDB->ejecutasql($sSQL);
			if ($bResultado == false) {
				$sSQL = 'ALTER TABLE ' . $sTabla . ' ADD saiu47t707formarecaudo int NULL DEFAULT 0, ADD saiu47t707identidadconv int NULL DEFAULT 0, 
				ADD saiu47t707idbanco int NULL DEFAULT 0, ADD saiu47t707idcuenta int NULL DEFAULT 0';
				$bResultado = $objDB->ejecutasql($sSQL);
			}
			//Febrero 27 de 2025 - Se agregan los campos para hacer metricas de duración del proceso.
			$sSQL = 'SELECT saiu47prob_abandono FROM ' . $sTabla . ' LIMIT 0, 1';
			$bResultado = $objDB->ejecutasql($sSQL);
			if ($bResultado == false) {
				$sSQL = 'ALTER TABLE ' . $sTabla . ' ADD saiu47prob_abandono int NOT NULL DEFAULT 0, ADD saiu47tem_radicado int NOT NULL DEFAULT 0, 
				ADD saiu47tem_est4 int NOT NULL DEFAULT 0, ADD saiu47tem_est6 int NOT NULL DEFAULT 0, ADD saiu47tem_est31 int NOT NULL DEFAULT 0, 
				ADD saiu47tem_est21 int NOT NULL DEFAULT 0, ADD saiu47tem_est7 int NOT NULL DEFAULT 0, ADD saiu47tem_est36 int NOT NULL DEFAULT 0, 
				ADD saiu47tem_est8 int NOT NULL DEFAULT 0, ADD saiu47tem_est46 int NOT NULL DEFAULT 0, ADD saiu47tem_est11 int NOT NULL DEFAULT 0, 
				ADD saiu47tem_est12 int NOT NULL DEFAULT 0, ADD saiu47tem_est10 int NOT NULL DEFAULT 0, ADD saiu47tem_total int NOT NULL DEFAULT 0';
				$bResultado = $objDB->ejecutasql($sSQL);
			}
			// Julio 21 de 2025 - Se agrega la fecha de ultimo estado
			$sSQL = 'SELECT saiu47fechaultestado FROM ' . $sTabla . ' LIMIT 0, 1';
			$bResultado = $objDB->ejecutasql($sSQL);
			if ($bResultado == false) {
				$sSQL = 'ALTER TABLE ' . $sTabla . ' ADD saiu47fechaultestado int NOT NULL DEFAULT 0';
				$bResultado = $objDB->ejecutasql($sSQL);
			}
		}
	}
	if ($sError == '') {
		$sTabla = 'saiu59tramiteanexo_' . $iAgno;
		$bIniciarContenedor = !$objDB->bexistetabla($sTabla);
		if ($bIniciarContenedor) {
			$sSQL = "CREATE TABLE " . $sTabla . " (saiu59idtramite int NOT NULL, saiu59consec int NOT NULL, saiu59id int NULL DEFAULT 0, saiu59idtipodoc int NULL DEFAULT 0, saiu59opcional int NULL DEFAULT 0, saiu59idestado int NULL DEFAULT 0, saiu59idorigen int NULL DEFAULT 0, saiu59idarchivo int NULL DEFAULT 0, saiu59idusuario int NULL DEFAULT 0, saiu59fecha int NULL DEFAULT 0, saiu59idrevisa int NULL DEFAULT 0, saiu59fecharevisa int NULL DEFAULT 0)";
			$bResultado = $objDB->ejecutasql($sSQL);
			$sSQL = "ALTER TABLE " . $sTabla . " ADD PRIMARY KEY(saiu59id)";
			$bResultado = $objDB->ejecutasql($sSQL);
			$sSQL = "ALTER TABLE " . $sTabla . " ADD UNIQUE INDEX saiu59tramiteanexo_id(saiu59idtramite, saiu59consec)";
			$bResultado = $objDB->ejecutasql($sSQL);
			$sSQL = "ALTER TABLE " . $sTabla . " ADD INDEX saiu59tramiteanexo_padre(saiu59idtramite)";
			$bResultado = $objDB->ejecutasql($sSQL);
		}
	}
	if ($sError == '') {
		$sTabla = 'saiu48anotaciones_' . $iAgno;
		$bIniciarContenedor = !$objDB->bexistetabla($sTabla);
		if ($bIniciarContenedor) {
			$sSQL = "CREATE TABLE " . $sTabla . " (saiu48idtramite int NOT NULL, saiu48consec int NOT NULL, saiu48id int NULL DEFAULT 0, saiu48visiblealinteresado int NULL DEFAULT 0, saiu48anotacion Text NULL, saiu48idusuario int NULL DEFAULT 0, saiu48fecha int NULL DEFAULT 0, saiu48hora int NULL DEFAULT 0, saiu48minuto int NULL DEFAULT 0)";
			$bResultado = $objDB->ejecutasql($sSQL);
			$sSQL = "ALTER TABLE " . $sTabla . " ADD PRIMARY KEY(saiu48id)";
			$bResultado = $objDB->ejecutasql($sSQL);
			$sSQL = "ALTER TABLE " . $sTabla . " ADD UNIQUE INDEX saiu48anotaciones_id(saiu48idtramite, saiu48consec)";
			$bResultado = $objDB->ejecutasql($sSQL);
			$sSQL = "ALTER TABLE " . $sTabla . " ADD INDEX saiu48anotaciones_padre(saiu48idtramite)";
			$bResultado = $objDB->ejecutasql($sSQL);
		}
	}
	if ($sError == '') {
		$sTabla = 'saiu49cambioesttra_' . $iAgno;
		$bIniciarContenedor = !$objDB->bexistetabla($sTabla);
		if ($bIniciarContenedor) {
			$sSQL = "CREATE TABLE " . $sTabla . " (saiu49idtramite int NOT NULL, saiu49consec int NOT NULL, saiu49id int NULL DEFAULT 0, saiu49idresponsable int NULL DEFAULT 0, saiu49idestadorigen int NULL DEFAULT 0, saiu49idestadofin int NULL DEFAULT 0, saiu49detalle Text NULL, saiu49usuario int NULL DEFAULT 0, saiu49fecha int NULL DEFAULT 0, saiu49hora int NULL DEFAULT 0, saiu49minuto int NULL DEFAULT 0, saiu49correterminos int NULL DEFAULT 0, saiu49tiempousado int NULL DEFAULT 0, saiu49tiempocalusado int NULL DEFAULT 0)";
			$bResultado = $objDB->ejecutasql($sSQL);
			$sSQL = "ALTER TABLE " . $sTabla . " ADD PRIMARY KEY(saiu49id)";
			$bResultado = $objDB->ejecutasql($sSQL);
			$sSQL = "ALTER TABLE " . $sTabla . " ADD UNIQUE INDEX saiu49cambioesttra_id(saiu49idtramite, saiu49consec)";
			$bResultado = $objDB->ejecutasql($sSQL);
			$sSQL = "ALTER TABLE " . $sTabla . " ADD INDEX saiu49cambioesttra_padre(saiu49idtramite)";
			$bResultado = $objDB->ejecutasql($sSQL);
		}
	}
	if ($sError == '') {
		/*
		$sTabla='saiu15historico_'.$iAgno;
		$bIniciarContenedor=!$objDB->bexistetabla($sTabla);
		if ($bIniciarContenedor){
			$sSQL="CREATE TABLE ".$sTabla." (saiu15idinteresado int NOT NULL, saiu15agno int NOT NULL, saiu15mes int NOT NULL, saiu15tiporadicado int NOT NULL, saiu15id int NULL DEFAULT 0, saiu15numsolicitudes int NULL DEFAULT 0, saiu15numsupervisiones int NULL DEFAULT 0)";
			$bResultado=$objDB->ejecutasql($sSQL);
			$sSQL="ALTER TABLE ".$sTabla." ADD PRIMARY KEY(saiu15id)";
			$bResultado=$objDB->ejecutasql($sSQL);
			$sSQL="ALTER TABLE ".$sTabla." ADD UNIQUE INDEX saiu15historico_id(saiu15idinteresado, saiu15agno, saiu15mes, saiu15tiporadicado)";
			$bResultado=$objDB->ejecutasql($sSQL);
			}
		*/
	}
	if ($sError == '') {
		$sTabla = 'saiu76anotaciones_' . $iAgno;
		$bIniciarContenedor = !$objDB->bexistetabla($sTabla);
		if ($bIniciarContenedor) {
			$sSQL = "CREATE TABLE " . $sTabla . " (saiu76idsolicitud int NOT NULL, saiu76consec int NOT NULL, saiu76id int NULL DEFAULT 0, saiu76visible int NULL DEFAULT 0, saiu76anotacion Text NULL, saiu76idorigen int NULL DEFAULT 0, saiu76idarchivo int NULL DEFAULT 0, saiu76idusuario int NULL DEFAULT 0, saiu76fecha int NULL DEFAULT 0, saiu76hora int NULL DEFAULT 0, saiu76minuto int NULL DEFAULT 0)";
			$bResultado = $objDB->ejecutasql($sSQL);
			$sSQL = "ALTER TABLE " . $sTabla . " ADD PRIMARY KEY(saiu76id)";
			$bResultado = $objDB->ejecutasql($sSQL);
			$sSQL = "ALTER TABLE " . $sTabla . " ADD UNIQUE INDEX saiu76anotaciones_id(saiu76idsolicitud, saiu76consec)";
			$bResultado = $objDB->ejecutasql($sSQL);
			$sSQL = "ALTER TABLE " . $sTabla . " ADD INDEX saiu76anotaciones_padre(saiu76idsolicitud)";
			$bResultado = $objDB->ejecutasql($sSQL);
		}
	}
	return array($sError, $sDebug);
}
// -- Cargos de una persona, a partir de una fecha
function f3254_ConsultaComboCargo($idTercero, $iFecha, $sWhere = '')
{
	if ($iFecha > 0) {
		$sWhere = $sWhere . ' TB.gthu54ifechaingreso<=' . $iFecha . ' AND 
		(TB.gthu54ifechafin=0 OR TB.gthu54ifechafin>=' . $iFecha . ') AND ';
	}
	$sSQL = 'SELECT TB.gthu54idcargo AS id, CONCAT(T1.gthv01nombre, " - Desde ", TB.gthu54fechaini, CASE TB.gthu54ifechafin WHEN 0 THEN "" ELSE CONCAT(" Hasta ", TB.gthu54fechafin) END) AS nombre 
	FROM gthu54hvlaboral AS TB, gthv01cargo AS T1
	WHERE TB.gthu54idtercero=' . $idTercero . ' AND TB.gthu54funcionario=1 AND ' . $sWhere . ' TB.gthu54idcargo=T1.gthv01id 
	ORDER BY TB.gthu54ifechaingreso DESC';
	return $sSQL;
}
//Consulta de monedas
function f4614_ConsultaCombo($sWhere = '', $objDB = NULL)
{
	$sSQL = 'SELECT gafi14id AS id, COCANT(gafi14nombre, " [", gafi14sigla, "]") AS nombre 
	FROM gafi14monedas 
	WHERE gafi14id>0 AND gafi14activa=1 
	ORDER BY gafi14orden';
	return $sSQL;
}
//Nota de novedad
function f12206_InfoNovedadCurso($idPeriodo, $idCurso, $idTercero, $objDB, $bDebug = false, $iModelo = 0)
{
	$sInfoNovedad = '';
	$iFechaSol = '';
	$sDebug = '';
	$sSQL = 'SELECT TB.corf06estado, TB.corf06fecha
	FROM corf06novedad AS TB, corf07novedadcurso AS T7
	WHERE TB.corf06idestudiante=' . $idTercero . ' AND TB.corf06idperiodo=' . $idPeriodo . ' AND TB.corf06tiponov=7 AND TB.corf06id=T7.corf07idnovedad AND T7.corf07idcurso=' . $idCurso . '';
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' Revisando solicitud aplaza: ' . $sSQL . '<br>';
	}
	$tabla6 = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla6) > 0) {
		$fila6 = $objDB->sf($tabla6);
		$iFechaSol = $fila6['corf06fecha'];
		switch ($fila6['corf06estado']) {
			case 0: //Borrador', '
				$sInfoNovedad = 'Solicitud de aplazamiento en proceso';
				if ($iModelo == 1) {
					$sInfoNovedad = '<span class="rojo">' . $sInfoNovedad . '</span> Su solicitud no estar&aacute; en firme hasta tanto no sea completada, para completarla acceda al <a href="saiacad.php" target="_blank"><b>SAI</b></a>.';
				}
				break;
			case 1: //Solicitada', '
				$sInfoNovedad = 'Solicitado en aplazamiento';
				if ($iModelo == 1) {
					$sInfoNovedad = '<b>' . $sInfoNovedad . '</b> Fecha solicitud: ' . fecha_desdenumero($iFechaSol) . '';
				}
				break;
			case 2: //Devuelta', '
				$sInfoNovedad = 'Solicitud devuelta';
				if ($iModelo == 1) {
					$sInfoNovedad = '<span class="rojo">' . $sInfoNovedad . '</span>';
				}
				break;
			case 3: //En estudio por la escuela', '
				$sInfoNovedad = 'Solicitud en estudio por la escuela';
				if ($iModelo == 1) {
					$sInfoNovedad = '<b>' . $sInfoNovedad . '</b> Fecha solicitud: ' . fecha_desdenumero($iFechaSol) . '';
				}
				break;
			case 4: //En reposici&oacute;n
				$sInfoNovedad = 'En reposici&oacute;n';
				if ($iModelo == 1) {
					$sInfoNovedad = '<b>' . $sInfoNovedad . '</b>';
				}
				break;
		}
	}
	return array($sInfoNovedad, $iFechaSol, $sDebug);
}
//Token para los sistemas rest
function Rest_Token()
{
	return md5(fecha_DiaMod() . 'UNAD');
}
//Integración con talento humano.
function TH_EquivalenteTipoDoc($sInfoIngresa)
{
	$sRes = 'XX';
	switch ($sInfoIngresa) {
		case 1:
			$sRes = 'CC';
			break;
		case 2:
			$sRes = 'CE';
			break;
		case 3:
			//@@@@ -- No se ha aplicado las convenciones.
			//$sRes='CE';
			break;
		case 4:
			//$sRes='CE';
			break;
		case 5:
			//$sRes='CE';
			break;
	}
	return $sRes;
}
function TraerDBCampus($bDebug = false)
{
	$objCampus = NULL;
	$sDebug = '';
	$sDirBase = __DIR__ . '/';
	require $sDirBase . 'app.php';
	if (isset($APP->dbhostcampus) != 0) {
		$objCampus = new clsdbadmin($APP->dbhostcampus, $APP->dbusercampus, $APP->dbpasscampus, $APP->dbnamecampus);
		if ($APP->dbpuertocampus != '') {
			$objCampus->dbPuerto = $APP->dbpuertocampus;
		}
		if ($bDebug) {
			if (!$objCampus->Conectar()) {
				$sDebug = $sDebug . '' . fecha_microtiempo() . ' Error al intentar conectar con la base de datos <b>' . $objCampus->serror . '</b><br>';
			}
		}
	} else {
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' No existen parametros de conexion con Grados [' . $sDirBase . 'app.php]<br>';
		}
	}
	return array($objCampus, $sDebug);
}
function TraerDBCentral($bDebug = false)
{
	$objDBCentral = NULL;
	$sDebug = '';
	$sDirBase = __DIR__ . '/';
	require $sDirBase . 'app.php';
	if (isset($APP->dbhostcentral) != 0) {
		$objDBCentral = new clsdbadmin($APP->dbhostcentral, $APP->dbusercentral, $APP->dbpasscentral, $APP->dbnamecentral);
		if ($APP->dbpuertocentral != '') {
			$objDBCentral->dbPuerto = $APP->dbpuertocentral;
		}
		if ($bDebug) {
			if (!$objDBCentral->Conectar()) {
				$sDebug = $sDebug . '' . fecha_microtiempo() . ' Error al intentar conectar con la base de datos <b>' . $objCampus->serror . '</b><br>';
			}
		}
	} else {
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' No existen parametros de conexion con el centralizador [' . $sDirBase . 'app.php]<br>';
		}
	}
	return array($objDBCentral, $sDebug);
}
function TraerDBRyC()
{
	list($objRyC, $sDebug) = TraerDBRyCV2();
	return $objRyC;
}
function TraerDBRyCV2($bDebug = false)
{
	$objRyC = NULL;
	$sDebug = '';
	$sDirBase = './';
	$sRutaIni = $sDirBase . 'app.php';
	if (!file_exists($sRutaIni)) {
		$sDirBase = __DIR__ . '/';
		$sRutaIni = $sDirBase . 'app.php';
	}
	require $sRutaIni;
	if (isset($APP->dbhostryc) != 0) {
		$objRyC = new clsdbadmin($APP->dbhostryc, $APP->dbuserryc, $APP->dbpassryc, $APP->dbnameryc);
		if ($APP->dbpuertoryc != '') {
			$objRyC->dbPuerto = $APP->dbpuertoryc;
		}
		if (!$objRyC->Conectar()) {
			if ($bDebug) {
				$sDebug = $sDebug . '' . fecha_microtiempo() . ' Error al intentar conectar con la base de datos <b>' . $objRyC->serror . ' - [' . $APP->dbhostryc . ' - ' . $APP->dbuserryc . ']</b><br>[' . $sRutaIni . ']<br>';
			}
			$objRyC = NULL;
		} else {
			if ($bDebug) {
				$sDebug = $sDebug . '' . fecha_microtiempo() . ' Conectado con la base de datos ' . $APP->dbnameryc . ' en ' . $APP->dbhostryc . ' [' . $sRutaIni . ']<br>';
			}
		}
	} else {
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' No existen parametros de conexion con RyC [' . $sDirBase . 'app.php]<br>';
		}
	}
	return array($objRyC, $sDebug);
}
function TraerDBRyCPruebas($bDebug = false)
{
	$objRyC = NULL;
	$sDebug = '';
	$sRutaIni = 'app.php';
	if (!file_exists($sRutaIni)) {
		$sDirBase = __DIR__ . '/';
		$sRutaIni = $sDirBase . 'app.php';
	}
	require $sRutaIni;
	if (isset($APP->dbhostryc) != 0) {
		$objRyC = new clsdbadmin($APP->dbhostryc_p, $APP->dbuserryc_p, $APP->dbpassryc_p, $APP->dbnameryc_p);
		if ($APP->dbpuertoryc_p != '') {
			$objRyC->dbPuerto = $APP->dbpuertoryc_p;
		}
		if (!$objRyC->Conectar()) {
			if ($bDebug) {
				$sDebug = $sDebug . '' . fecha_microtiempo() . ' Error al intentar conectar con la base de datos <b>' . $objRyC->serror . ' - [' . $APP->dbhostryc_p . ' - ' . $APP->dbuserryc_p . ']</b><br>[' . $sRutaIni . ']<br>';
			}
			$objRyC = NULL;
		} else {
			if ($bDebug) {
				$sDebug = $sDebug . '' . fecha_microtiempo() . ' Conectado con la base de datos ' . $APP->dbnameryc_p . ' [' . $sRutaIni . ']<br>';
			}
		}
	} else {
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' No existen parametros de conexion con RyC [' . $sDirBase . 'app.php]<br>';
		}
	}
	return array($objRyC, $sDebug);
}
function TraerDBGrados($bDebug = false)
{
	$objGrados = NULL;
	$sDebug = '';
	$sDirBase = __DIR__ . '/';
	require $sDirBase . 'app.php';
	if (isset($APP->dbhostgrados) != 0) {
		$objGrados = new clsdbadmin($APP->dbhostgrados, $APP->dbusergrados, $APP->dbpassgrados, $APP->dbnamegrados);
		if ($APP->dbpuertogrados != '') {
			$objGrados->dbPuerto = $APP->dbpuertogrados;
		}
		if ($bDebug) {
			if (!$objGrados->Conectar()) {
				$sDebug = $sDebug . '' . fecha_microtiempo() . ' Error al intentar conectar con la base de datos <b>' . $objGrados->serror . '</b><br>';
			}
		}
	} else {
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' No existen parametros de conexion con Grados [' . $sDirBase . 'app.php]<br>';
		}
	}
	return array($objGrados, $sDebug);
}
function TraerDBNotas($bDebug = false)
{
	$objNotas = NULL;
	$sDebug = '';
	$sDirBase = __DIR__ . '/';
	require $sDirBase . 'app.php';
	if (isset($APP->dbhostnotas) != 0) {
		$objNotas = new clsdbadmin($APP->dbhostnotas, $APP->dbusernotas, $APP->dbpassnotas, $APP->dbnamenotas);
		if ($APP->dbpuertonotas != '') {
			$objNotas->dbPuerto = $APP->dbpuertonotas;
		}
		if ($bDebug) {
			if (!$objNotas->Conectar()) {
				$sDebug = $sDebug . '' . fecha_microtiempo() . ' Error al intentar conectar con la base de datos <b>' . $objNotas->serror . '</b><br>';
			}
		}
	} else {
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' No existen parametros de conexion con Grados [' . $sDirBase . 'app.php]<br>';
		}
	}
	return array($objNotas, $sDebug);
}
//Florida.
function TraerDBFL($bDebug = false)
{
	$objFL = NULL;
	$sDebug = '';
	$sDirBase = __DIR__ . '/';
	require $sDirBase . 'app.php';
	if (isset($APP->dbhostfl) != 0) {
		$sModelo = 'M';
		if (isset($APP->dbmodelofl) != 0) {
			if ($APP->dbmodelofl == 'P') {
				$sModelo = 'P';
			}
		}
		$objFL = new clsdbadmin($APP->dbhostfl, $APP->dbuserfl, $APP->dbpassfl, $APP->dbnamefl, $sModelo);
		if ($APP->dbpuertofl != '') {
			$objFL->dbPuerto = $APP->dbpuertofl;
		}
		if ($bDebug) {
			if (!$objFL->Conectar()) {
				$sDebug = $sDebug . '' . fecha_microtiempo() . ' Error al intentar conectar con la base de datos <b>' . $objFL->serror . '</b><br>';
			}
		}
	} else {
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' No existen parametros de conexion con Grados [' . $sDirBase . 'app.php]<br>';
		}
	}
	return array($objFL, $sDebug);
}
function Traer_Entidad()
{
	$sDirBase = __DIR__ . '/';
	$idEntidad = 0;
	if (file_exists($sDirBase . 'app.php')) {
		require $sDirBase . 'app.php';
		if (isset($APP->entidad) != 0) {
			switch ($APP->entidad) {
				case 1: // Unad Florida
				case 2: // Unad Union Europea
					$idEntidad = $APP->entidad;
					break;
			}
		}
	}
	return $idEntidad;
}

// ----------------- Funciones de ajuste
function f1024_Opcion($iVr, $sParametro, $objDB)
{
	return $iVr;
}
