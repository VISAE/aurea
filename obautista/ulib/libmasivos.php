<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2018 - 2026 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.22.4 sábado, 27 de octubre de 2018
--- Modelo Versión 3.1.5b sábado, 21 de marzo de 2026 - Se inicia la gestion de mensajes masivos
--- Procesos masivos...
*/
function f1200_ArmarEstructura($iMes, $objDB, $bDebug = false)
{
	$sError = '';
	$sDebug = '';
	// Estas primeras 2 tablas son para procesos masivmos del OIL
	if ($bDebug) {
		$sDebug = $sDebug . log_debug('Iniciando verificacion de tablas');
	}
	$sTabla = 'masi01alerta_' . $iMes;
	if (!$objDB->bexistetabla($sTabla)) {
		$sSQL = 'CREATE TABLE ' . $sTabla . ' (masi01idtercero int NOT NULL, masi01fecha int NOT NULL, masi01correo varchar(50) NULL, masi01idsmpt int NULL DEFAULT 0, masi01minutoenvio int NULL DEFAULT 0) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' Ejecutando: ' . $sSQL . ' <br>';
		}
		$result = $objDB->ejecutasql($sSQL);
		if ($result == false) {
			$sError = 'No fue posible crear las tabla para manejo de masivos. ' . $objDB->serror . '';
		} else {
			$sSQL = 'ALTER TABLE ' . $sTabla . ' ADD PRIMARY KEY(masi01idtercero, masi01fecha)';
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . ' Ejecutando: ' . $sSQL . ' <br>';
			}
			$result = $objDB->ejecutasql($sSQL);
		}
	}
	if ($sError == '') {
		$sTabla = 'masi02mensaje_' . $iMes;
		if (!$objDB->bexistetabla($sTabla)) {
			$sSQL = 'CREATE TABLE ' . $sTabla . ' (masi02idtercero int NOT NULL, masi02fecha int NOT NULL, masi02idproceso int NOT NULL, masi02idref int NOT NULL, masi02mensaje Text NULL) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci';
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . ' Ejecutando: ' . $sSQL . ' <br>';
			}
			$result = $objDB->ejecutasql($sSQL);
			$sSQL = 'ALTER TABLE ' . $sTabla . ' ADD PRIMARY KEY(masi02idtercero, masi02fecha, masi02idproceso, masi02idref)';
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . ' Ejecutando: ' . $sSQL . ' <br>';
			}
			$result = $objDB->ejecutasql($sSQL);
		}
	}
	//21 de marzo de 2026 - Tablas de correo masivo.
	if ($sError == '') {
		$sTabla = 'masi05mensajes_' . $iMes;
		if ($bDebug) {
			$sDebug = $sDebug . log_debug('Iniciando verificacion de la tabla ' . $sTabla);
		}
		if (!$objDB->bexistetabla($sTabla)) {
			$sSQL = 'CREATE TABLE ' . $sTabla . ' (masi05idproceso int NOT NULL, masi05consec int NOT NULL, masi05id int NOT NULL DEFAULT 0, masi05estado int NOT NULL DEFAULT 0, masi05asunto varchar(250) NULL, masi05cuerpo Text NULL, masi05admiterpta int NOT NULL DEFAULT 0, masi05correorpta varchar(200) NULL, masi05firma int NOT NULL DEFAULT 0, masi05idusuario int NOT NULL DEFAULT 0, masi05fecha int NOT NULL DEFAULT 0, masi05hora int NOT NULL DEFAULT 0, masi05min int NOT NULL DEFAULT 0, masi05unidadfunc int NOT NULL DEFAULT 0, masi05zona int NOT NULL DEFAULT 0, masi05centro int NOT NULL DEFAULT 0, masi05escuela int NOT NULL DEFAULT 0, masi05programa int NOT NULL DEFAULT 0, masi05idperiodo int NOT NULL DEFAULT 0, masi05curso int NOT NULL DEFAULT 0, masi05docente int NOT NULL DEFAULT 0, masi05total_usuarios int NOT NULL DEFAULT 0, masi05total_envios int NOT NULL DEFAULT 0, masi05tiponotifica int NOT NULL DEFAULT 0, masi05periodicidad int NOT NULL DEFAULT 0, masi05idrelacion int NOT NULL DEFAULT 0, masi05idrelacion2 int NOT NULL DEFAULT 0, masi05idrelacion3 int NOT NULL DEFAULT 0) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci';
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . ' Ejecutando: ' . $sSQL . ' <br>';
			}
			$result = $objDB->ejecutasql($sSQL);
			if ($result == false) {
				$sError = 'No fue posible crear las tabla ' . $sTabla . '. ' . $objDB->serror . '';
			}
			$sSQL="ALTER TABLE ' . $sTabla . ' ADD PRIMARY KEY(masi05id)";
			$result = $objDB->ejecutasql($sSQL);

			$sSQL=$objDB->sSQLCrearIndice($sTabla, 'masi05mensajes_id', 'masi05idproceso, masi05consec', true);
			$result = $objDB->ejecutasql($sSQL);

		}
	}
	if ($sError == '') {
		$sTabla = 'masi06mensajepoblacion_' . $iMes;
		if ($bDebug) {
			$sDebug = $sDebug . log_debug('Iniciando verificacion de la tabla ' . $sTabla);
		}
		if (!$objDB->bexistetabla($sTabla)) {
			$sSQL='CREATE TABLE ' . $sTabla . ' (masi06idmensaje int NOT NULL, masi06consec int NOT NULL, masi06id int NOT NULL DEFAULT 0, masi06zona int NOT NULL DEFAULT 0, masi06centro int NOT NULL DEFAULT 0, masi06escuela int NOT NULL DEFAULT 0, masi06nivelforma int NOT NULL DEFAULT 0, masi06programa int NOT NULL DEFAULT 0, masi06est_condicion int NOT NULL DEFAULT 0, masi06sexo int NOT NULL DEFAULT 0, masi06idperiodo int NOT NULL DEFAULT 0, masi06curso int NOT NULL DEFAULT 0) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci';
			$result = $objDB->ejecutasql($sSQL);
			if ($result == false) {
				$sError = 'No fue posible crear las tabla ' . $sTabla . '. ' . $objDB->serror . '';
			}
			$sSQL='ALTER TABLE ' . $sTabla . ' ADD PRIMARY KEY(masi06id)';
			$result = $objDB->ejecutasql($sSQL);
			$sSQL=$objDB->sSQLCrearIndice('' . $sTabla . '', 'masi06mensajepoblacion_id', 'masi06idmensaje, masi06consec', true);
			$result = $objDB->ejecutasql($sSQL);
			$sSQL=$objDB->sSQLCrearIndice('' . $sTabla . '', 'masi06mensajepoblacion_padre', 'masi06idmensaje');
			$result = $objDB->ejecutasql($sSQL);
		}
	}
	if ($sError == '') {
		$sTabla = 'masi07mensajeanexo_' . $iMes;
		if ($bDebug) {
			$sDebug = $sDebug . log_debug('Iniciando verificacion de la tabla ' . $sTabla);
		}
		if (!$objDB->bexistetabla($sTabla)) {
			$sSQL='CREATE TABLE ' . $sTabla . ' (masi07idmensaje int NOT NULL, masi07consec int NOT NULL, masi07id int NOT NULL DEFAULT 0, masi07titulo varchar(200) NULL, masi07idorigen int NOT NULL DEFAULT 0, masi07idarchivo int NOT NULL DEFAULT 0) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci';
			$result = $objDB->ejecutasql($sSQL);
			if ($result == false) {
				$sError = 'No fue posible crear las tabla ' . $sTabla . '. ' . $objDB->serror . '';
			}
			$sSQL='ALTER TABLE ' . $sTabla . ' ADD PRIMARY KEY(masi07id)';
			$result = $objDB->ejecutasql($sSQL);
			$sSQL=$objDB->sSQLCrearIndice('' . $sTabla . '', 'masi07mensajeanexo_id', 'masi07idmensaje, masi07consec', true);
			$result = $objDB->ejecutasql($sSQL);
			$sSQL=$objDB->sSQLCrearIndice('' . $sTabla . '', 'masi07mensajeanexo_padre', 'masi07idmensaje');
			$result = $objDB->ejecutasql($sSQL);
		}
	}
	if ($sError == '') {
		$sTabla = 'masi08mensajedest_' . $iMes;
		if ($bDebug) {
			$sDebug = $sDebug . log_debug('Iniciando verificacion de la tabla ' . $sTabla);
		}
		if (!$objDB->bexistetabla($sTabla)) {
			$sSQL='CREATE TABLE ' . $sTabla . ' (masi08idmensaje int NOT NULL, masi08idtercero int NOT NULL, masi08idfecha int NOT NULL, masi08id int NOT NULL DEFAULT 0, masi08idpoblacion int NOT NULL DEFAULT 0, masi08fechaenvio int NOT NULL DEFAULT 0, masi08horaenvio int NOT NULL DEFAULT 0, masi08minenvio int NOT NULL DEFAULT 0, masi08idsmtp int NOT NULL DEFAULT 0) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci';
			$result = $objDB->ejecutasql($sSQL);
			if ($result == false) {
				$sError = 'No fue posible crear las tabla ' . $sTabla . '. ' . $objDB->serror . '';
			}
			$sSQL='ALTER TABLE ' . $sTabla . ' ADD PRIMARY KEY(masi08id)';
			$result = $objDB->ejecutasql($sSQL);
			$sSQL=$objDB->sSQLCrearIndice('' . $sTabla . '', 'masi08mensajedest_id', 'masi08idmensaje, masi08idtercero, masi08idfecha', true);
			$result = $objDB->ejecutasql($sSQL);
			$sSQL=$objDB->sSQLCrearIndice('' . $sTabla . '', 'masi08mensajedest_padre', 'masi08idmensaje');
			$result = $objDB->ejecutasql($sSQL);
		}
	}
	return array($sError, $sDebug);
}
function f1200_CargarMensajes($iMes, $iDia, $objDB, $bDebug = false)
{
	$sError = '';
	$sDebug = '';
	$sTabla1 = 'masi01alerta_' . $iMes;
	$sTabla2 = 'masi02mensaje_' . $iMes;
	$sInsert2 = 'INSERT INTO masi02mensaje_' . $iMes . ' (masi02idtercero, masi02fecha, masi02idproceso, masi02idref, masi02mensaje) ';
	//Saber quienes se les puede notificar.
	$sIds11 = '-99';
	$sSQL = 'SELECT unad11id FROM unad11terceros WHERE unad11aceptanotificacion="S" AND unad11fechaconfmail<>0';
	$tabla = $objDB->ejecutasql($sSQL);
	while ($fila = $objDB->sf($tabla)) {
		$sIds11 = $sIds11 . ',' . $fila['unad11id'];
	}
	//Notificar cupos de laboratorio...
	$sSQL = 'SELECT olab07recestudiantes, olab07recestorigen FROM olab07config WHERE olab07id=1';
	$result = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($result) == 0) {
		$sError = 'No se han definido las opciones de configuracion.';
	}
	if ($sError == '') {
		$configLab = $objDB->sf($result);
		if ($configLab['olab07recestudiantes'] == 'S') {
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . ' Cargando notificaciones de laboratorio (Para estudiantes)<br>';
			}
			//Alistar lo que debo hacer.
			$sIds = '-99';
			$sSQL = 'SELECT olab08id FROM olab08oferta WHERE olab08idresponsable<>0 AND olab08cerrado="S" AND olab08realizado<>"S"';
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . ' Listado de actividades vigentes: ' . $sSQL . ' <br>';
			}
			$tabla = $objDB->ejecutasql($sSQL);
			while ($fila = $objDB->sf($tabla)) {
				$sIds = $sIds . ',' . $fila['olab08id'];
			}
			$sManana = fecha_sumardias(fecha_hoy(), 1);
			$sNomManana = formato_fechalarga($sManana, true);
			//Ahora cargar las jornadas que va a haber ma;ana.
			$sSQL = 'SELECT TB.olab10idoferta, TB.olab10hora, TB.olab10minuto, TB.olab10horafinal, TB.olab10minutofinal, T8.olab08idcurso, T8.olab08numgrupo, T8.olab08idlaboratorio , TL.olab01nombre, T8.olab08idtipooferta
			FROM olab10horarios AS TB, olab08oferta AS T8, olab01laboratorios AS TL 
			WHERE TB.olab10fecha="' . $sManana . '" AND TB.olab10retirado="N" AND TB.olab10idoferta IN (' . $sIds . ') AND TB.olab10idoferta=T8.olab08id AND T8.olab08idlaboratorio=TL.olab01id 
			ORDER BY TB.olab10hora, TB.olab10minuto';
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . ' Consulta de recordatorios: ' . $sSQL . ' <br>';
			}
			$tabla = $objDB->ejecutasql($sSQL);
			while ($fila = $objDB->sf($tabla)) {
				//En cada jornada insertar los recordatorios.
				//$sInsert2..
				//cadena_notildes
				$sActividad = 'Laboratorio';
				if ($fila['olab08idtipooferta'] == 1) {
					$sActividad = 'Salida de campo';
				}
				$sSQL = $sInsert2 . 'SELECT TB.olab11idestudiante, (' . $iDia . ') AS idDia, (2111) AS idProceso, TB.olab11id, "' . $sActividad . ' Curso ' . $fila['olab08idcurso'] . ' el ' . $sNomManana . ' de ' . formato_horaminuto($fila['olab10hora'], $fila['olab10minuto']) . ' a ' . formato_horaminuto($fila['olab10horafinal'], $fila['olab10minutofinal']) . ' <br>Lugar ' . cadena_Reemplazar($fila['olab01nombre'], '"', '-') . '" AS Alerta
				FROM olab11cupos AS TB, unad11terceros AS T11 
				WHERE olab11idoferta=' . $fila['olab10idoferta'] . ' AND TB.olab11idestudiante=T11.unad11id AND T11.unad11aceptanotificacion<>"N" AND T11.unad11fechaconfmail<>0';
				if ($bDebug) {
					$sDebug = $sDebug . fecha_microtiempo() . ' Datos a insertar en los recordatorios: ' . $sSQL . ' <br>';
				}
				$result = $objDB->ejecutasql($sSQL);
			}
			//ARMAR LOS ENCABEZADOS...
			$sSQL = 'INSERT INTO masi01alerta_' . $iMes . ' (masi01idtercero, masi01fecha, masi01correo)
			SELECT T2.masi02idtercero, (' . $iDia . ') AS idDia, T11.unad11correonotifica
			FROM masi02mensaje_' . $iMes . ' AS T2, unad11terceros AS T11 
			WHERE T2.masi02fecha=' . $iDia . ' AND T2.masi02idtercero=T11.unad11id
			GROUP BY T2.masi02idtercero, T11.unad11correonotifica';
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . ' Armando encabezados: ' . $sSQL . ' <br>';
			}
			$result = $objDB->ejecutasql($sSQL);
		} else {
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . ' La configuracion tiene apagada las notificaciones de laboratorio para estudiantes <br>';
			}
		}
	}
	//Termina de notificar cupos de laboratorio.
	return array($sError, $sDebug);
}
function f1200_RevisarTablaPoblacion($iMes, $objDB, $bDebug = false)
{
	$sError = '';
	$sDebug = '';
	$sTabla = 'masi06mensajepoblacion_' . $iMes;
	$sSQL = "SELECT masi06docente FROM " . $sTabla . " LIMIT 0, 1;";
	$result = $objDB->ejecutasql($sSQL);
	if ($result == false) {
		$sSQL = "ALTER TABLE " . $sTabla . " 
		ADD masi06docente INT NULL DEFAULT 0 AFTER masi06curso,
		ADD masi06unidadfunc INT NULL DEFAULT 0 AFTER masi06docente,
		ADD masi06agnogrado INT NULL DEFAULT 0 AFTER masi06unidadfunc";
		$result = $objDB->ejecutasql($sSQL);
		if ($result == false) {
			$sError = $sError . ' Falla al intentar agregar campos de poblacion';
		}
	}
	return array($sError, $sDebug);
}
