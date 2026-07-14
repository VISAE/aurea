<?php
/*
--- � Angel Mauro Avellaneda Barreto - UNAD - 2018 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versi�n 2.22.4 s�bado, 27 de octubre de 2018
--- Procesos masivos...
*/
function f1200_ArmarEstructura($iMes, $objDB, $bDebug = false)
{
	$sError = '';
	$sDebug = '';
	$sTabla = 'masi01alerta_' . $iMes;
	if ($objDB->bexistetabla($sTabla)) {
	} else {
		$sSQL = 'CREATE TABLE ' . $sTabla . ' (masi01idtercero int NOT NULL, masi01fecha int NOT NULL, masi01correo varchar(50) NULL, masi01idsmpt int NULL DEFAULT 0, masi01minutoenvio int NULL DEFAULT 0)';
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

			$sTabla = 'masi02mensaje_' . $iMes;
			$sSQL = 'CREATE TABLE ' . $sTabla . ' (masi02idtercero int NOT NULL, masi02fecha int NOT NULL, masi02idproceso int NOT NULL, masi02idref int NOT NULL, masi02mensaje Text NULL)';
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
	//Termina de notificar cupos de laboratorio.
	return array($sError, $sDebug);
}
function f1200_ArmarEstructuraMasivos($iMes, $objDB, $bDebug = false)
{
	$sError = '';
	$sDebug = '';
	$sTabla = 'masi05mensajes_' . $iMes;
	$bExiste = $objDB->bexistetabla($sTabla);
	if (!$bExiste) {
		$sSQL = 'CREATE TABLE ' . $sTabla . ' (masi05idproceso int NULL DEFAULT 0, masi05consec int NULL DEFAULT 0, masi05id int NULL DEFAULT 0, 
		masi05estado int NULL DEFAULT 0, masi05asunto varchar(250) NULL, masi05cuerpo Text NULL, 
		masi05admiterpta int NULL DEFAULT 0, masi05correorpta varchar(200) NULL, masi05firma int NULL DEFAULT 0, 
		masi05idusuario int NULL DEFAULT 0, masi05fecha int NULL DEFAULT 0, masi05hora int NULL DEFAULT 0, 
		masi05min int NULL DEFAULT 0, masi05unidadfunc int NULL DEFAULT 0, masi05zona int NULL DEFAULT 0, 
		masi05centro int NULL DEFAULT 0, masi05escuela int NULL DEFAULT 0, masi05programa int NULL DEFAULT 0, 
		masi05idperiodo int NULL DEFAULT 0, masi05curso int NULL DEFAULT 0, masi05docente int NULL DEFAULT 0, 
		masi05total_usuarios int NULL DEFAULT 0, masi05total_envios int NULL DEFAULT 0, masi05tiponotifica int NULL DEFAULT 0, 
		masi05periodicidad int NULL DEFAULT 0, masi05idrelacion int NULL DEFAULT 0, masi05idrelacion2 int NULL DEFAULT 0, 
		masi05idrelacion3 int NULL DEFAULT 0)';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' Ejecutando: ' . $sSQL . ' <br>';
		}
		$result = $objDB->ejecutasql($sSQL);
		if ($result == false) {
			$sError = 'No ha sido posible iniciar la creaci&oacute;n del contenedor para ' . $sTabla . ', Por favor informe al administrador del sistema<br>' . $objDB->serror . '';
		} else {
			$sSQL = "ALTER TABLE " . $sTabla . " ADD PRIMARY KEY(masi05id)";
			$bResultado = $objDB->ejecutasql($sSQL);
			$sSQL = "ALTER TABLE " . $sTabla . " ADD UNIQUE INDEX masi05mensajes_id(masi05idproceso, masi05consec)";
			$bResultado = $objDB->ejecutasql($sSQL);
			$sSQL = "ALTER TABLE " . $sTabla . " ADD INDEX masi05mensajes_estado(masi05estado)";
			$bResultado = $objDB->ejecutasql($sSQL);
			$sSQL = "ALTER TABLE " . $sTabla . " ADD INDEX masi05mensajes_usuario(masi05idusuario)";
			$bResultado = $objDB->ejecutasql($sSQL);
			$sSQL = "ALTER TABLE " . $sTabla . " ADD INDEX masi05mensajes_unidadfunc(masi05unidadfunc)";
			$bResultado = $objDB->ejecutasql($sSQL);
			$sSQL = "ALTER TABLE " . $sTabla . " ADD INDEX masi05mensajes_tiponotifica(masi05tiponotifica)";
			$bResultado = $objDB->ejecutasql($sSQL);

			$sTabla = 'masi06mensajepoblacion_' . $iMes;
			$sSQL = "CREATE TABLE " . $sTabla . " (masi06idmensaje int NOT NULL, masi06consec int NOT NULL, masi06id int NULL DEFAULT 0, masi06zona int NULL DEFAULT 0, masi06centro int NULL DEFAULT 0, 
			masi06escuela int NULL DEFAULT 0, masi06nivelforma int NULL DEFAULT 0, masi06programa int NULL DEFAULT 0, masi06est_condicion int NULL DEFAULT 0, masi06sexo int NULL DEFAULT 0, 
			masi06idperiodo int NULL DEFAULT 0, masi06curso int NULL DEFAULT 0, masi06docente int NULL DEFAULT 0, masi06unidadfunc int NULL DEFAULT 0, masi06agnogrado int NULL DEFAULT 0)";
			$bResultado = $objDB->ejecutasql($sSQL);
			$sSQL = "ALTER TABLE " . $sTabla . " ADD PRIMARY KEY(masi06id)";
			$bResultado = $objDB->ejecutasql($sSQL);
			$sSQL = "ALTER TABLE " . $sTabla . " ADD UNIQUE INDEX masi06mensajepoblacion_id(masi06idmensaje, masi06consec)";
			$bResultado = $objDB->ejecutasql($sSQL);
			$sSQL = "ALTER TABLE " . $sTabla . " ADD INDEX masi06mensajepoblacion_padre(masi06idmensaje)";
			$bResultado = $objDB->ejecutasql($sSQL);

			$sTabla = 'masi07mensajeanexo_' . $iMes;
			$sSQL = "CREATE TABLE " . $sTabla . " (masi07idmensaje int NOT NULL, masi07consec int NOT NULL, masi07id int NULL DEFAULT 0, masi07titulo varchar(200), masi07idorigen int NULL DEFAULT 0, masi07idarchivo int NULL DEFAULT 0)";
			$bResultado = $objDB->ejecutasql($sSQL);
			$sSQL = "ALTER TABLE " . $sTabla . " ADD PRIMARY KEY(masi07id)";
			$bResultado = $objDB->ejecutasql($sSQL);
			$sSQL = "ALTER TABLE " . $sTabla . " ADD UNIQUE INDEX masi07mensajeanexo_id(masi07idmensaje, masi07consec)";
			$bResultado = $objDB->ejecutasql($sSQL);
			$sSQL = "ALTER TABLE " . $sTabla . " ADD INDEX masi07mensajeanexo_padre(masi07idmensaje)";
			$bResultado = $objDB->ejecutasql($sSQL);

			$sTabla = 'masi08mensajedest_' . $iMes;
			$sSQL = "CREATE TABLE " . $sTabla . " (masi08idmensaje int NOT NULL, masi08idtercero int NOT NULL, masi08idfecha int NOT NULL, masi08id int NULL DEFAULT 0, masi08idpoblacion int NULL DEFAULT 0, 
			masi08fechaenvio int NULL DEFAULT 0, masi08horaenvio int NULL DEFAULT 0, masi08minenvio int NULL DEFAULT 0, masi08idsmtp int NULL DEFAULT 0)";
			$bResultado = $objDB->ejecutasql($sSQL);
			$sSQL = "ALTER TABLE " . $sTabla . " ADD PRIMARY KEY(masi08id)";
			$bResultado = $objDB->ejecutasql($sSQL);
			$sSQL = "ALTER TABLE " . $sTabla . " ADD UNIQUE INDEX masi08mensajedest_id(masi08idmensaje, masi08idtercero, masi08idfecha)";
			$bResultado = $objDB->ejecutasql($sSQL);
			$sSQL = "ALTER TABLE " . $sTabla . " ADD INDEX masi08mensajedest_padre(masi08idmensaje)";
			$bResultado = $objDB->ejecutasql($sSQL);
		}
	}
	return array($sError, $sDebug);
}