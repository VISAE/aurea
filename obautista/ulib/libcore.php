<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2019 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.24.1 miércoles, 5 de febrero de 2020
*/
function f2216_FaltantesMatricula($idPeraca, $idCurso, $objDB, $bDebug=false){
	$sError='';
	$sFaltantes='';
	$sDebug='';
	$sDirBase=__DIR__.'/';
	list($objDBRyC, $sDebugR)=TraerDBRyCV2($bDebug);
	if ($objDBRyC==NULL){
		$sDebug=$sDebug.$sDebugR;
		$sError='No ha sido posible conectarse con RyC';
		}
	if ($sError==''){
		$sDocExistentes='-99';
		$sSQL='SELECT T11.unad11doc FROM core16actamatricula AS TB, unad11terceros AS T11 WHERE TB.core16peraca='.$idPeraca.' AND (TB.core16parametros="'.$idCurso.'" OR TB.core16parametros LIKE "'.$idCurso.'@%" OR TB.core16parametros LIKE "%@'.$idCurso.'@%" OR TB.core16parametros LIKE "%@'.$idCurso.'") AND TB.core16tercero=T11.unad11id';
		$tabla=$objDB->ejecutasql($sSQL);
		while($fila=$objDB->sf($tabla)){
			$sDocExistentes=$sDocExistentes.','.$fila['unad11doc'];
			}
		$sSQL='SELECT TR.ins_estudiante 
FROM registro AS TR, cursos_periodos AS T1 
WHERE TR.ano='.$idPeraca.' AND TR.ins_novedad=79 AND TR.estado="A" AND TR.ins_estudiante NOT IN ('.$sDocExistentes.')
AND TR.ins_curso=T1.consecutivo AND T1.cur_materia='.$idCurso.' AND T1.cur_edificio<>99';
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' alumnos pendientes de matricula RyC: '.$sSQL.'<br>';}
		$tabla=$objDBRyC->ejecutasql($sSQL);
		while($fila=$objDBRyC->sf($tabla)){
			$sFaltantes=$sFaltantes.$fila['ins_estudiante'].', ';
			}
		}
	return array($sError, $sFaltantes, $sDebug);
	}
function f2216_SobrantesMatricula($idPeraca, $idCurso, $objDB, $bDebug=false){
	$sError='';
	$sSobrantes='';
	$sDebug='';
	$sDirBase=__DIR__.'/';
	list($objDBRyC, $sDebugR)=TraerDBRyCV2($bDebug);
	if ($objDBRyC==NULL){
		$sDebug=$sDebug.$sDebugR;
		$sError='No ha sido posible conectarse con RyC';
		}
	if ($sError==''){
		$sDocExistentes='-99';
		$sSQL='SELECT TR.ins_estudiante 
FROM registro AS TR, cursos_periodos AS T1 
WHERE TR.ano='.$idPeraca.' AND TR.ins_novedad=79 AND TR.estado="A"
AND TR.ins_curso=T1.consecutivo AND T1.cur_materia='.$idCurso.' AND T1.cur_edificio<>99';
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Alumnos existentes en RyC: '.$sSQL.'<br>';}
		$tabla=$objDBRyC->ejecutasql($sSQL);
		while($fila=$objDBRyC->sf($tabla)){
			$sDocExistentes=$sDocExistentes.','.$fila['ins_estudiante'];
			}
		$sSQL='SELECT T11.unad11doc 
FROM core16actamatricula AS TB, unad11terceros AS T11 
WHERE TB.core16peraca='.$idPeraca.' AND (TB.core16parametros="'.$idCurso.'" OR TB.core16parametros LIKE "'.$idCurso.'@%" OR TB.core16parametros LIKE "%@'.$idCurso.'@%" OR TB.core16parametros LIKE "%@'.$idCurso.'") AND TB.core16tercero=T11.unad11id AND T11.unad11doc NOT IN ('.$sDocExistentes.')';
		// AND TR.ins_estudiante NOT IN ('.$sDocExistentes.')
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Alumnos sobrantes en CORE: '.$sSQL.'<br>';}
		$tabla=$objDB->ejecutasql($sSQL);
		while($fila=$objDB->sf($tabla)){
			$sSobrantes=$sSobrantes.$fila['unad11doc'].', ';
			}
		}
	return array($sError, $sSobrantes, $sDebug);
	}
function f2216_NoProcesadosMatriculaCurso($idPeraca, $idCurso, $objDB, $bDebug=false){
	$sError='';
	$sNoProcesados='';
	$sDebug='';
	$sDirBase=__DIR__.'/';
	if ($sError==''){
		$tMat=0;
		$sDocExistentes='-99';
		$sSQL='SHOW TABLES LIKE "core04%"';
		$sSQLBase='';
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Total Periodo: Lista de contenedores: '.$sSQL.'<br>';}
		$tablac=$objDB->ejecutasql($sSQL);
		while($filac=$objDB->sf($tablac)){
			$iContenedor=substr($filac[0], 16);
			$iMatriculados=0;
			$sSQL='SELECT core04tercero FROM core04matricula_'.$iContenedor.' WHERE core04idcurso='.$idCurso.' AND core04peraca='.$idPeraca.'';
			if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Alumnos matriculados en el curso: '.$sSQL.'<br>';}
			$tabla=$objDB->ejecutasql($sSQL);
			$iMatriculados=$objDB->nf($tabla);
			$tMat=$tMat+$iMatriculados;
			while($fila=$objDB->sf($tabla)){
				$sDocExistentes=$sDocExistentes.','.$fila['core04tercero'];
				}
			}
		$sSQL='SELECT T11.unad11doc 
FROM core16actamatricula AS TB, unad11terceros AS T11 
WHERE TB.core16peraca='.$idPeraca.' AND (TB.core16parametros="'.$idCurso.'" OR TB.core16parametros LIKE "'.$idCurso.'@%" OR TB.core16parametros LIKE "%@'.$idCurso.'@%" OR TB.core16parametros LIKE "%@'.$idCurso.'") AND TB.core16tercero NOT IN ('.$sDocExistentes.') AND TB.core16tercero=T11.unad11id';
		// AND TR.ins_estudiante NOT IN ('.$sDocExistentes.')
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Alumnos NO procesados en CORE: '.$sSQL.'<br>';}
		$tabla=$objDB->ejecutasql($sSQL);
		while($fila=$objDB->sf($tabla)){
			$sNoProcesados=$sNoProcesados.$fila['unad11doc'].', ';
			}
		}
	return array($sError, $sNoProcesados, $sDebug);
	}
function f2216_DistribucionMatriculaAula($idPeraca, $idCurso, $objDB, $bDebug=false){
	$sError='';
	$sInfo='';
	$sDebug='';
	$sDirBase=__DIR__.'/';
	if ($sError==''){
		$tMat=0;
		$aAula=array();
		$aNomAula=array();
		$iAulas=0;
		$sDocExistentes='-99';
		$sSQL='SHOW TABLES LIKE "core04%"';
		//if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Total Periodo: Lista de contenedores: '.$sSQL.'<br>';}
		$tablac=$objDB->ejecutasql($sSQL);
		$sSQL='';
		while($filac=$objDB->sf($tablac)){
			$iContenedor=substr($filac[0], 16);
			if ($sSQL!=''){$sSQL=$sSQL.'
UNION 
';}
			$sSQL=$sSQL.'SELECT core04idaula, COUNT(core04tercero) AS Total FROM core04matricula_'.$iContenedor.' WHERE core04idcurso='.$idCurso.' AND core04peraca='.$idPeraca.' GROUP BY core04idaula ';
			//if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Alumnos matriculados en el curso: '.$sSQL.'<br>';}
			}
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Distribucion por AULA: '.$sSQL.'<br>';}
		$tabla=$objDB->ejecutasql($sSQL);
		while($fila=$objDB->sf($tabla)){
			if (isset($aAula['a'.$fila['core04idaula']])==0){
				$aNomAula[$iAulas]='a'.$fila['core04idaula'];
				$aAula['a'.$fila['core04idaula']]['total']=0;
				$aAula['a'.$fila['core04idaula']]['nombre']='Aula '.$fila['core04idaula'];
				$iAulas++;
				}
			$aAula['a'.$fila['core04idaula']]['total']=$aAula['a'.$fila['core04idaula']]['total']+$fila['Total'];
			}
		for ($k=0;$k<$iAulas;$k++){
			$sNomAula=$aNomAula[$k];
			$sInfo=$sInfo.''.$aAula[$sNomAula]['nombre'].': '.$aAula[$sNomAula]['total'].', ';
			}
		}
	return array($sError, $sInfo, $sDebug);
	}

function f2216_MatricularCurso($idPeraca, $idCurso, $objDB, $bDebug=false){
	$sError='';
	$sDebug='';
	$iProcesados=0;
	$sDirBase=__DIR__.'/';
	list($objDBRyC, $sDebugR)=TraerDBRyCV2($bDebug);
	if ($objDBRyC==NULL){
		$sDebug=$sDebug.$sDebugR;
		$sError='No ha sido posible conectarse con RyC';
		}
	if ($sError==''){
		$sDocExistentes='-99';
		$sSQL='SELECT T11.unad11doc FROM core16actamatricula AS TB, unad11terceros AS T11 WHERE TB.core16peraca='.$idPeraca.' AND (TB.core16parametros="'.$idCurso.'" OR TB.core16parametros LIKE "'.$idCurso.'@%" OR TB.core16parametros LIKE "%@'.$idCurso.'@%" OR TB.core16parametros LIKE "%@'.$idCurso.'") AND TB.core16tercero=T11.unad11id';
		$tabla=$objDB->ejecutasql($sSQL);
		while($fila=$objDB->sf($tabla)){
			$sDocExistentes=$sDocExistentes.','.$fila['unad11doc'];
			}
		$sSQL='SELECT TR.ins_estudiante 
FROM registro AS TR, cursos_periodos AS T1 
WHERE TR.ano='.$idPeraca.' AND TR.ins_novedad=79 AND TR.ins_estudiante NOT IN ('.$sDocExistentes.')
AND TR.ins_curso=T1.consecutivo AND T1.cur_materia='.$idCurso.' AND T1.cur_edificio<>99';
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' alumnos pendientes de matricula RyC: '.$sSQL.'<br>';}
		$tabla=$objDBRyC->ejecutasql($sSQL);
		//include $sDirBase.'../core/ApiEdunat.php';
		//$edunat = new ApiEdunat();
		while($fila=$objDBRyC->sf($tabla)){
			//$resultado = $edunat->getDataIndividual(base64_encode($idPeraca), base64_encode($fila['ins_estudiante']));
			//$iProcesados++;
			$bAplazaSemestre=false;
			$sSQL='SELECT cod_curso, tipo, c_programas, codigocead, fecha_Matricula, t_novedad 
FROM WSCaracterizacionTEMP
WHERE codigo='.$fila['ins_estudiante'].' AND periodo='.$idPeraca.'';
			$tablav=$objDBRyC->ejecutasql($sSQL);
			if ($objDBRyC->nf($tablav)>0){
				$filav=$objDBRyC->sf($tablav);
				$aParametros=array();
				$aParametros['peraca']=$idPeraca;
				$aParametros['documento']=$fila['ins_estudiante'];
				$aParametros['programa']=$filav['c_programas'];
				$aParametros['cead']=$filav['codigocead'];
				$aParametros['nuevo']=$filav['tipo'];
				$sAplazados='';
				$sCancelados='';
				$sCursos='';
				switch($filav['t_novedad']){
					case 'A': //Activo
					$sCursos=$filav['cod_curso'];
					break;
					case 'C': //Cancelacion de materia
					$sCancelados=$filav['cod_curso'];
					break;
					case 'D': //Aplazamiento de materia
					case 'F': //Aplazamiento de semestre.
					$sAplazados=$filav['cod_curso'];
					break;
					default:
					break;
					}
				while($filav=$objDBRyC->sf($tablav)){
					//$sCursos=$sCursos.'@'.$filav['cod_curso'];
					switch($filav['t_novedad']){
						case 'A': //Activo
						if ($sCursos!=''){$sCursos=$sCursos.'@';}
						$sCursos=$sCursos.$filav['cod_curso'];
						break;
						case 'C': //Cancelacion de materia
						if ($sCancelados!=''){$sCancelados=$sCancelados.'@';}
						$sCancelados=$sCancelados.$filav['cod_curso'];
						break;
						case 'D': //Aplazamiento de materia
						case 'F': //Aplazamiento de semestre.
						if ($sAplazados!=''){$sAplazados=$sAplazados.'@';}
						$sAplazados=$sAplazados.$filav['cod_curso'];
						break;
						default:
						break;
						}
					}
				$aParametros['aplazados']=$sAplazados;
				$aParametros['cancelados']=$sCancelados;
				$aParametros['cursos']=$sCursos;
				list($sErrorR, $sDebugR)=f2216_RegistrarMatricula($aParametros, $objDB, $bDebug);
				if ($sErrorR!=''){
					$sError=$sError.'Error al intentar registrar la matricula de '.$fila['ins_estudiante'].': '.$sErrorR.'<br>';
					}
				$sDebug=$sDebug.$sDebugR;
				$iProcesados++;
				}else{
				$sError=$sError.'No se encuentran datos de matricula de '.$fila['ins_estudiante'].'<br>';
				}
			}
		}
	return array($sError, $iProcesados, $sDebug);
	}
function f2216_MatricularPeriodo($idPeraca, $objDB, $bDebug=false){
	$sError='';
	$sDebug='';
	$iProcesados=0;
	$bHayDBRyC=false;
	$sDirBase=__DIR__.'/';
	list($objDBRyC, $sDebugR)=TraerDBRyCV2($bDebug);
	if ($objDBRyC==NULL){
		$sDebug=$sDebug.$sDebugR;
		$sError='No ha sido posible conectarse con RyC';
		}else{
		$bHayDBRyC=true;
		}
	if ($sError==''){
		$sDocExistentes='-99';
		$sSQL='SELECT T11.unad11doc FROM core16actamatricula AS TB, unad11terceros AS T11 WHERE TB.core16peraca='.$idPeraca.' AND TB.core16tercero=T11.unad11id';
		$tabla=$objDB->ejecutasql($sSQL);
		while($fila=$objDB->sf($tabla)){
			$sDocExistentes=$sDocExistentes.','.$fila['unad11doc'];
			}
		$sSQL='SELECT TR.ins_estudiante 
FROM registro AS TR, cursos_periodos AS T1 
WHERE TR.ano='.$idPeraca.' AND TR.ins_novedad=79 AND TR.ins_estudiante NOT IN ('.$sDocExistentes.')
AND TR.ins_curso=T1.consecutivo AND T1.cur_edificio<>99 
GROUP BY TR.ins_estudiante';
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Alumnos pendientes de matricula RyC: '.$sSQL.'<br>';}
		$tabla=$objDBRyC->ejecutasql($sSQL);
	//Ya no se trae por web service se trae por consulta...
		$bPorVista=false;
		if ($idPeraca==761){$bPorVista=true;}
		if ($idPeraca==762){$bPorVista=true;}
		if ($bPorVista){
		while($fila=$objDBRyC->sf($tabla)){
			$sSQL='SELECT cod_curso, tipo, c_programas, codigocead, fecha_Matricula, t_novedad
FROM WSCaracterizacionTEMP
WHERE codigo='.$fila['ins_estudiante'].' AND periodo='.$idPeraca.'';
			if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Revisando alumno: '.$sSQL.'<br>';}
			$tablav=$objDBRyC->ejecutasql($sSQL);
			if ($objDBRyC->nf($tablav)>0){
				$filav=$objDBRyC->sf($tablav);
				$aParametros=array();
				$aParametros['peraca']=$idPeraca;
				$aParametros['documento']=$fila['ins_estudiante'];
				$aParametros['programa']=$filav['c_programas'];
				$aParametros['cead']=$filav['codigocead'];
				$aParametros['nuevo']=$filav['tipo'];
				$sAplazados='';
				$sCancelados='';
				$sCursos='';
				switch($filav['t_novedad']){
					case 'A': //Activo
					$sCursos=$filav['cod_curso'];
					break;
					case 'C': //Cancelacion de materia
					$sCancelados=$filav['cod_curso'];
					break;
					case 'D': //Aplazamiento de materia
					case 'F': //Aplazamiento de semestre.
					$sAplazados=$filav['cod_curso'];
					break;
					default:
					break;
					}
				while($filav=$objDBRyC->sf($tablav)){
					//$sCursos=$sCursos.'@'.$filav['cod_curso'];
					switch($filav['t_novedad']){
						case 'A': //Activo
						if ($sCursos!=''){$sCursos=$sCursos.'@';}
						$sCursos=$sCursos.$filav['cod_curso'];
						break;
						case 'C': //Cancelacion de materia
						if ($sCancelados!=''){$sCancelados=$sCancelados.'@';}
						$sCancelados=$sCancelados.$filav['cod_curso'];
						break;
						case 'D': //Aplazamiento de materia
						case 'F': //Aplazamiento de semestre.
						if ($sAplazados!=''){$sAplazados=$sAplazados.'@';}
						$sAplazados=$sAplazados.$filav['cod_curso'];
						break;
						default:
						break;
						}
					}
				$aParametros['aplazados']=$sAplazados;
				$aParametros['cancelados']=$sCancelados;
				$aParametros['cursos']=$sCursos;
				list($sErrorR, $sDebugR)=f2216_RegistrarMatricula($aParametros, $objDB, $bDebug);
				if ($sErrorR!=''){
					$sError=$sError.'Error al intentar registrar la matricula de '.$fila['ins_estudiante'].': '.$sErrorR.'<br>';
					}
				$sDebug=$sDebug.$sDebugR;
				$iProcesados++;
				}else{
				$sError=$sError.'No se encuentran datos de matricula de '.$fila['ins_estudiante'].'<br>';
				}
			}
			}else{
			//El metodo por webservice.
			include $sDirBase.'../core/ApiEdunat.php';
			$edunat=new ApiEdunat();
			while($fila=$objDBRyC->sf($tabla)){
				$resultado = $edunat->getDataIndividual(base64_encode($idPeraca), base64_encode($fila['ins_estudiante']));
				$iProcesados++;
				}
			}
		}
	if ($bHayDBRyC){
		$objDBRyC->CerrarConexion();
		}
	return array($sError, $iProcesados, $sDebug);
	}
function f2216_TraerMatricula($idPeraca, $sDocumento, $objDB, $bDebug=false){
	$sError='';
	$sDebug='';
	$bPorVista=false;
	if ($idPeraca==761){$bPorVista=true;}
	if ($bPorVista){
	list($objDBRyC, $sDebugR)=TraerDBRyCV2($bDebug);
	if ($objDBRyC==NULL){
		$sDebug=$sDebug.$sDebugR;
		$sError='No ha sido posible conectarse con RyC';
		}else{
		$bHayDBRyC=true;
		}
	if ($sError==''){
		$sSQL='SELECT cod_curso, tipo, c_programas, codigocead, fecha_Matricula, t_novedad
FROM WSCaracterizacionTEMP
WHERE codigo='.$sDocumento.' AND periodo='.$idPeraca.'';
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consultando datos de matricula en RyC: '.$sSQL.'<br>';}
		$tablav=$objDBRyC->ejecutasql($sSQL);
		if ($objDBRyC->nf($tablav)>0){
			$filav=$objDBRyC->sf($tablav);
			$aParametros=array();
			$aParametros['peraca']=$idPeraca;
			$aParametros['documento']=$sDocumento;
			$aParametros['programa']=$filav['c_programas'];
			$aParametros['cead']=$filav['codigocead'];
			$aParametros['nuevo']=$filav['tipo'];
				$sAplazados='';
				$sCancelados='';
				$sCursos='';
				switch($filav['t_novedad']){
					case 'A': //Activo
					$sCursos=$filav['cod_curso'];
					break;
					case 'C': //Cancelacion de materia
					$sCancelados=$filav['cod_curso'];
					break;
					case 'D': //Aplazamiento de materia
					case 'F': //Aplazamiento de semestre.
					$sAplazados=$filav['cod_curso'];
					break;
					default:
					break;
					}
				while($filav=$objDBRyC->sf($tablav)){
					//$sCursos=$sCursos.'@'.$filav['cod_curso'];
					switch($filav['t_novedad']){
						case 'A': //Activo
						if ($sCursos!=''){$sCursos=$sCursos.'@';}
						$sCursos=$sCursos.$filav['cod_curso'];
						break;
						case 'C': //Cancelacion de materia
						if ($sCancelados!=''){$sCancelados=$sCancelados.'@';}
						$sCancelados=$sCancelados.$filav['cod_curso'];
						break;
						case 'D': //Aplazamiento de materia
						case 'F': //Aplazamiento de semestre.
						if ($sAplazados!=''){$sAplazados=$sAplazados.'@';}
						$sAplazados=$sAplazados.$filav['cod_curso'];
						break;
						default:
						break;
						}
					}
				$aParametros['aplazados']=$sAplazados;
				$aParametros['cancelados']=$sCancelados;
				$aParametros['cursos']=$sCursos;
			list($sError, $sDebugM)=f2216_RegistrarMatricula($aParametros, $objDB, $bDebug);
			$sDebug=$sDebug.$sDebugM;
			}else{
			$sError='No se encuentran datos de matricula para el documento '.$sDocumento.' en el periodo '.$idPeraca.'';
			}
		}
	if ($bHayDBRyC){
		$objDBRyC->CerrarConexion();
		}
		}else{
		//El metodo por webservice.
		$sDirBase=__DIR__.'/';
		include $sDirBase.'../core/ApiEdunat.php';
		$edunat = new ApiEdunat();
		$resultado = $edunat->getDataIndividual(base64_encode($idPeraca), base64_encode($sDocumento));
		$sError=json_encode($resultado);
		}
	return array($sError, $sDebug);
	}
function f2240_ActualizarDirectorEnGrupos($idPeraca, $idCurso, $objDB, $bDebug=false){
	$sError='';
	$sDebug='';
	$idAcompana=0;
	if ($sError==''){
		$idContPeraca=f146_Contenedor($idPeraca, $objDB);
		}
	return array($sError, $sDebug);
	}
function f2202_GenerarPEI($idEstudiante, $idPrograma, $objDB, $bDebug=false){
	$sError='';
	$sDebug='';
	$core01id=0;
	$idPlanEstudios=0;
	$iFechaInicial=0;
	$sSQL='SELECT TB.core01id, TB.core01fechainicio, TB.core01idplandeestudios 
FROM core01estprograma AS TB 
WHERE TB.core01idtercero='.$idEstudiante.' AND TB.core01idprograma='.$idPrograma.'';
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$core01id=$fila['core01id'];
		$iFechaInicial=$fila['core01fechainicio'];
		$idPlanEstudios=$fila['core01idplandeestudios'];
		}
	if ($idPlanEstudios==0){$sError='No se ha seleccionado un plan de estudios';}
	if ($sError==''){
		list($idContTercero, $sError)=f1011_BloqueTercero($idEstudiante, $objDB);
		//Mirar que no tenga ya un plan cargado.
		$sSQL='SELECT 1 FROM core03plandeestudios_'.$idContTercero.' WHERE core03idestprograma='.$core01id.'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$sError='Ya se ha incorporado un plan de estudios.';
			}
		}
	if ($sError==''){
		//, core01numcredbasicos, core01numcredespecificos, core01numcredelecgenerales, core01numcredelecescuela, core01numcredelecprograma, core01numcredeleccomplem, core01numcredelectivos
		//Actualizar el encabezado de estudiante.
		$sSQL='SELECT core10numcredbasicos, core10numcredespecificos, core10numcredelecgenerales, core10numcredelecescuela, core10numcredelecprograma, core10numcredeleccomplem FROM core10programaversion WHERE core10id='.$idPlanEstudios.'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$fila=$objDB->sf($tabla);
			$sSQL='UPDATE core01estprograma SET core01numcredbasicos='.$fila['core10numcredbasicos'].', core01numcredespecificos='.$fila['core10numcredespecificos'].', core01numcredelecgenerales='.$fila['core10numcredelecgenerales'].', core01numcredelecescuela='.$fila['core10numcredelecescuela'].', core01numcredelecprograma='.$fila['core10numcredelecprograma'].', core01numcredeleccomplem='.$fila['core10numcredeleccomplem'].', core01numcredelectivos='.($fila['core10numcredelecgenerales']+$fila['core10numcredelecescuela']+$fila['core10numcredelecprograma']+$fila['core10numcredeleccomplem']).' WHERE core01id='.$core01id.'';
			$result=$objDB->ejecutasql($sSQL);
			}else{
			$sError='No se ha encontrado el plan de estudios [Ref '.$idPlanEstudios.']';
			}
		}
	if ($sError==''){
		//Ahora si armar la tabla detalle.
		$core03id=tabla_consecutivo('core03plandeestudios_'.$idContTercero.'', 'core03id', '', $objDB);
		$sCampos03='INSERT INTO core03plandeestudios_'.$idContTercero.' (core03idestprograma, core03idcurso, core03id, core03idtercero, core03idprograma, 
core03idequivalente, core03itipocurso, core03obligatorio, core03homologable, core03habilitable, 
core03porsuficiencia, core03idprerequisito, core03idprerequisito2, core03idprerequisito3, core03idcorequisito, 
core03numcreditos, core03nivelcurso, core03peracaaprueba, core03nota75, core03fechanota75, 
core03idusuarionota75, core03nota25, core03fechanota25, core03idusuarionota25, core03notahomologa, 
core03fechanotahomologa, core03idusuarionotahomo, core03detallehomologa, core03idequivalencia, core03idmatricula, 
core03fechainclusion, core03notafinal, core03formanota, core03estado) VALUES ';
		$sValores03='';
		$sSQL='SELECT core11idcurso, core11tiporegistro, core11obligarorio, core11homologable, core11habilitable, core11porsuficiencia, core11idprerequisito, core11idprerequisito2, core11idprerequisito3, core11idcorrequisito, core11numcreditos, core11nivelaplica 
FROM core11plandeestudio 
WHERE core11idversionprograma='.$idPlanEstudios.'';
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Lectura del plan de estudios: '.$sSQL.'<br>';}
		$tabla=$objDB->ejecutasql($sSQL);
		while($fila=$objDB->sf($tabla)){
			if ($sValores03!=''){$sValores03=$sValores03.', ';}
			$core03formanota=0;
			$core03estado=0;
			if ($fila['core11idprerequisito']!=0){$core03estado=1;}
			if ($fila['core11idprerequisito2']!=0){$core03estado=1;}
			if ($fila['core11idprerequisito3']!=0){$core03estado=1;}
			$sValores03=$sValores03.'('.$core01id.', '.$fila['core11idcurso'].', '.$core03id.', '.$idEstudiante.', '.$idPrograma.', 
'.$fila['core11idcurso'].', '.$fila['core11tiporegistro'].', '.$fila['core11obligarorio'].', "'.$fila['core11homologable'].'", "'.$fila['core11habilitable'].'", 
"'.$fila['core11porsuficiencia'].'", '.$fila['core11idprerequisito'].', '.$fila['core11idprerequisito2'].', '.$fila['core11idprerequisito3'].', '.$fila['core11idcorrequisito'].', 
'.$fila['core11numcreditos'].', '.$fila['core11nivelaplica'].', 0, 0, 0, 
0, 0, 0, 0, 0, 
0, 0, "", 0, 0, 
'.$iFechaInicial.', 0, '.$core03formanota.', '.$core03estado.')';
			$core03id++;
			}
		if ($sValores03!=''){
			$sSQL=$sCampos03.$sValores03;
			$result=$objDB->ejecutasql($sSQL);
			if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Insersion del plan de estudios: '.$sSQL.'<br>';}
			}
		}
	return array($sError, $sDebug);
	}
?>