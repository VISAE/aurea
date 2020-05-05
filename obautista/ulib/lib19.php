<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2016 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
*/
function f19_IniciarEncuesta($idEncuesta, $idPeraca, $idCurso, $idTercero, $idBloqueo, $objdb){
	list($bResult, $sError, $sDebug)=f19_IniciarEncuestaV2($idEncuesta, $idPeraca, $idCurso, $idTercero, $idBloqueo, $objdb);
	return $bResult;
	}
function f19_IniciarEncuestaV2($idEncuesta, $idPeraca, $idCurso, $idTercero, $idBloqueo, $objdb, $bDebug=false){
	$bResult=false;
	$sDebug='';
	$sError='';
	if ((int)$idTercero==0){
		$sError='No se ha definido un tercero a insertar en la encuesta';
		}
	if ($idCurso==''){
		$sError='El dato curso viene vacio, debe ser numerico.';
		}
	if ($sError==''){
		$even21pais='';
		$even21depto='';
		$even21ciudad='';
		$even21fechanace='';
		$even21agnos=0;
		$even21perfil=0;
		$even21idzona=0;
		$even21idcead=0;
		$even21idprograma=0;
		$sObligatoria='S';
		$sql='SELECT * FROM even21encuestaaplica WHERE even21idtercero='.$idTercero.' ORDER BY even21terminada DESC, even21id DESC LIMIT 0,1';
		$tabla=$objdb->ejecutasql($sql);
		if ($objdb->nf($tabla)>0){
			$fila=$objdb->sf($tabla);
			$even21pais=$fila['even21pais'];
			$even21depto=$fila['even21depto'];
			$even21ciudad=$fila['even21ciudad'];
			$even21fechanace=$fila['even21fechanace'];
			$even21agnos=$fila['even21agnos'];
			$even21perfil=$fila['even21perfil'];
			$even21idzona=$fila['even21idzona'];
			$even21idcead=$fila['even21idcead'];
			$even21idprograma=$fila['even21idprograma'];
			}else{
			$even21pais='057';
			}
		//Ver que no este antes de insertarla.
		$sql='SELECT even21id FROM even21encuestaaplica WHERE even21idencuesta='.$idEncuesta.' AND even21idtercero='.$idTercero.' AND even21idperaca='.$idPeraca.' AND even21idcurso='.$idCurso.' AND even21idbloquedo='.$idBloqueo.'';
		$tabla=$objdb->ejecutasql($sql);
		if ($objdb->nf($tabla)==0){
			$even21id=tabla_consecutivo('even21encuestaaplica', 'even21id', '', $objdb);
			$sql21='INSERT INTO even21encuestaaplica (even21idencuesta, even21idtercero, even21idperaca, even21idcurso, even21idbloquedo, even21id, even21fechapresenta, even21terminada, even21pais, even21depto, even21ciudad, even21fechanace, even21agnos, even21perfil, even21idzona, even21idcead, even21idprograma, even21obligatoria) VALUES ('.$idEncuesta.', '.$idTercero.', '.$idPeraca.', '.$idCurso.', '.$idBloqueo.', '.$even21id.', "00/00/0000", "N", "'.$even21pais.'", "'.$even21depto.'", "'.$even21ciudad.'", "'.$even21fechanace.'", '.$even21agnos.', '.$even21perfil.', '.$even21idzona.', '.$even21idcead.', '.$even21idprograma.', "'.$sObligatoria.'")';
			$tabla=$objdb->ejecutasql($sql21);
			//f1926_ArmarTabla22($idEncuesta, $even21id, $objdb);
			if ($tabla==false){
				if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Falla al insertar encuesta '.$sql21.'<br>';}
				}else{
				$bResult=true;
				}
			}
		}else{
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' '.$sError.'<br>';}
		}
	return array($bResult, $sError, $sDebug);
	}
function f19_ProcesoCurso($idPeraca, $idCurso, $objdb, $bDebug=false){
	$bConLaboratorio=false;
	$bConSalida=false;
	$sDebug='';
	$sql='SELECT ofer08incluyelaboratorio, ofer08incluyesalida FROM ofer08oferta WHERE ofer08idcurso='.$idCurso.' AND ofer08idper_aca='.$idPeraca.' AND ofer08cead=0';
	$tabla=$objdb->ejecutasql($sql);
	if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' PROCESOS QUE APLICAN AL CURSO '.$idCurso.' '.$sql.' <br>';}
	if ($objdb->nf($tabla)>0){
		$fila=$objdb->sf($tabla);
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' RESPUESTA ['.$fila['ofer08incluyesalida'].'] <br>';}
		if ($fila['ofer08incluyelaboratorio']=='S'){$bConLaboratorio=true;}
		if ($fila['ofer08incluyesalida']=='S'){
			if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' APLICAN SALIDA DE CAMPO <br>';}
			$bConSalida=true;
			}
		}else{
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' No se han encontrado registros de oferta...'.$objdb->serror.'<br>';}
		}
	return array($bConLaboratorio, $bConSalida, $sDebug);
	}
function f19_AplicarEncuestaAlumnoLaboratorioV2($idPeraca, $idCurso, $idTercero, $idProceso, $objdb, $bDebug=false, $idEncuesta=0){
/* 
sábado, 28 de octubre de 2017 - Se agregan diferentes procesos, entonces puede ser laboratorio o salida de campo.
Martes, 16 de enero de 2018 - Se cambia el encabezado debido a que se debe especificar explicitamente el proceso, para evitar problemas con los cursos que tienen salidas y laboratorio.
*/
	//Primero ver que no tenga una.
	$bIncluye=false;
	$sDebug='';
	/*
	$idProceso=1701;
	list($bConLaboratorio, $bConSalida, $sDebugP)=f19_ProcesoCurso($idPeraca, $idCurso, $objdb, $bDebug);
	$sDebug=$sDebug.$sDebugP;
	if (!$bConLaboratorio){
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Aplicaci&oacute;n de Encuestas Laboratorios y Salidas: No es laboratorio<br>';}
		if ($bConSalida){
			$idProceso=1704;
			if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Aplicaci&oacute;n de Encuestas Laboratorios y Salidas: Es salida<br>';}
			}
		}
	*/
	$sError='';
	if ($idEncuesta==0){
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Aplicaci&oacute;n de Encuestas Laboratorios y Salidas: Proceso '.$idProceso.'<br>';}
		}
	$sql='SELECT TB.even21id FROM even21encuestaaplica AS TB, even16encuesta AS T16 WHERE TB.even21idtercero='.$idTercero.' AND TB.even21idperaca='.$idPeraca.' AND TB.even21idcurso='.$idCurso.' AND TB.even21idencuesta=T16.even16id AND T16.even16idproceso IN (1701, 1704)';
	$result=$objdb->ejecutasql($sql);
	if ($objdb->nf($result)>0){$sError='Ya se ha aplicado una encuesta para el curso.';}
	if ($sError==''){
		if ($idEncuesta==0){
			if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Aplicaci&oacute;n de Encuestas Laboratorios y Salidas: Iniciando carga de encuesta<br>';}
			//echo 'Iniciando carga de la encuesta. <br>';
			$sHoy=fecha_hoy();
			$sIds='-99';
			//Ver si ha una encuesta especifica para el proceso.
			$sql='SELECT even16id FROM even16encuesta WHERE even16idproceso='.$idProceso.' AND even16publicada="S"';
			$tabla=$objdb->ejecutasql($sql);
			while ($fila=$objdb->sf($tabla)){
				$sIds=$sIds.','.$fila['even16id'];
				}
			if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Aplicaci&oacute;n de Encuestas Laboratorios y Salidas: Revisando encuestas del proceso '.$idProceso.' '.$sql.'<br>';}
			$sql='SELECT even24idencuesta FROM even24encuestaperaca WHERE even24idencuesta IN ('.$sIds.') AND even24idperaca='.$idPeraca.' AND STR_TO_DATE(even24fechainicial, "%d/%m/%Y")<=STR_TO_DATE("'.$sHoy.'", "%d/%m/%Y") AND STR_TO_DATE(even24fechafinal, "%d/%m/%Y")>=STR_TO_DATE("'.$sHoy.'", "%d/%m/%Y") ORDER BY even24idperaca DESC';
			//echo $sql.'<br>';
			$tabla=$objdb->ejecutasql($sql);
			if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Aplicaci&oacute;n de Encuestas Laboratorios y Salidas: Revisando encuestas para el periodo '.$sql.'<br>';}
			if ($objdb->nf($tabla)>0){
				$fila=$objdb->sf($tabla);
				$idEncuesta=$fila['even24idencuesta'];
				}
			}
		if ($idEncuesta!=0){
			if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Aplicaci&oacute;n de Encuestas Laboratorios y Salidas: Se intenta insertar la encuesta '.$idEncuesta.' Periodo '.$idPeraca.' Curso '.$idCurso.' Tercero [Id] '.$idTercero.'<br>';}
			list($bIncluye, $sErrorE, $sDebugE)=f19_IniciarEncuestaV2($idEncuesta, $idPeraca, $idCurso, $idTercero, 0, $objdb, $bDebug);
			$sDebug=$sDebug.$sDebugE;
			}
		}
	return array($bIncluye, $sDebug);
	}
function f19_AplicarEncuestaProfesorLaboratorio($idPeraca, $idCurso, $idTercero, $objdb){
	//Primero ver que no tenga una.
	$bIncluye=false;
	$idProceso=1702;
	list($bConLaboratorio, $bConSalida)=f19_ProcesoCurso($idPeraca, $idCurso, $objdb);
	if (!$bConLaboratorio){
		if ($bConSalida){$idProceso=1705;}
		}
	$sError='';
	$idEncuesta=0;
	$sql='SELECT TB.even21id FROM even21encuestaaplica AS TB, even16encuesta AS T16 WHERE TB.even21idtercero='.$idTercero.' AND TB.even21idperaca='.$idPeraca.' AND TB.even21idcurso='.$idCurso.' AND TB.even21idencuesta=T16.even16id AND T16.even16idproceso IN (1702, 1705)';
	$result=$objdb->ejecutasql($sql);
	if ($objdb->nf($result)>0){$sError='Ya se ha aplicado una encuesta para el curso.';}
	if ($sError==''){
		//echo 'Iniciando carga de la encuesta. <br>';
		$sHoy=fecha_hoy();
		$sIds='-99';
		//Ver si ha una encuesta para profesores
		$sql='SELECT even16id FROM even16encuesta WHERE even16idproceso='.$idProceso.' AND even16publicada="S"';
		$tabla=$objdb->ejecutasql($sql);
		while ($fila=$objdb->sf($tabla)){
			$sIds=$sIds.','.$fila['even16id'];
			}
		$sql='SELECT even24idencuesta FROM even24encuestaperaca WHERE even24idencuesta IN ('.$sIds.') AND even24idperaca='.$idPeraca.' AND STR_TO_DATE(even24fechainicial, "%d/%m/%Y")<=STR_TO_DATE("'.$sHoy.'", "%d/%m/%Y") AND STR_TO_DATE(even24fechafinal, "%d/%m/%Y")>=STR_TO_DATE("'.$sHoy.'", "%d/%m/%Y") ORDER BY even24idperaca DESC';
		//echo $sql.'<br>';
		$tabla=$objdb->ejecutasql($sql);
		if ($objdb->nf($tabla)>0){
			$fila=$objdb->sf($tabla);
			$idEncuesta=$fila['even24idencuesta'];
			//echo 'Se intenta insertar la encuesta '.$idEncuesta.'<br>';
			$bIncluye=f19_IniciarEncuesta($idEncuesta, $idPeraca, $idCurso, $idTercero, 0, $objdb);
			}
		}
	return $bIncluye;
	}
?>