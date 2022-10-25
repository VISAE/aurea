<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2019 - 2020 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.24.1 miércoles, 5 de febrero de 2020
*/
function f102_GestionaGrupos($idPeriodo, $objDB)
{
	$bRes = false;
	if ($idPeriodo > 952) {
		$bRes = true;
		if ($idPeriodo < 1122) {
			switch ($idPeriodo) {
				case 957:
				case 958:
				case 963:
				case 965:
				case 972:
				case 979:
				case 981:
				case 991:
				case 992:
				case 995:
				case 996:
				case 997:
				case 998:
				case 999:
				case 1018:
				case 1019:
				case 1022:
				case 1023:
				case 1024:
				case 1088:
				case 1090:
				case 1092:
				case 1111:
				case 1113:
				case 1114:
				case 1115:
				case 1116:
				case 1118:
				case 1120:
				case 1121:
					$bRes = false;
					break;
			}
		}
	}
	return $bRes;
}
function f140_EsExtraCurricular($idCurso, $objDB, $bDebug = false)
{
	$bRes = false;
	$sDebug = '';
	$sSQL = 'SELECT TB.unad40nivelformacion, T22.core22extracurricular  
	FROM unad40curso AS TB, core22nivelprograma AS T22 
	WHERE TB.unad40id=' . $idCurso . ' AND TB.unad40nivelformacion=T22.core22id';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		if ($fila['core22extracurricular'] == 1) {
			$bRes = true;
		}
	}
	return array($bRes, $sDebug);
}
function f140_Titulo($idCurso, $objDB, $bDebug = false)
{
	//similar a f140_EtiquetaCurso($idCurso, $objDB)
	$sEtiqueta = '{' . $idCurso . '}';
	$sNombre = '';
	$sSQL = 'SELECT unad40titulo, unad40nombre FROM unad40curso WHERE unad40id=' . $idCurso . '';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$sEtiqueta = $fila['unad40titulo'];
		$sNombre = cadena_notildes($fila['unad40nombre']);
	}
	return array($sEtiqueta, $sNombre);
}
function f1708_OfertaDirector($idDirector, $objDB, $bDebug = false)
{
	$sIds = '-99';
	$sDebug = '';
	$sSQL = 'SELECT TB.ofer08id FROM TB.ofer08oferta WHERE TB.ofer08cead=0 AND TB.ofer08estadooferta=1 AND TB.ofer08idacomanamento=' . $idDirector . '';
	$tabla = $objDB->ejecutasql($sSQL);
	while ($fila = $objDB->sf($tabla)) {
		$sIds = $sIds . ',' . $fila['corf12idoferta'];
	}
	$sSQL = 'SELECT corf12idoferta FROM corf12directores WHERE corf12idtercero=' . $idDirector . ' AND corf12activo=1';
	$tabla = $objDB->ejecutasql($sSQL);
	while ($fila = $objDB->sf($tabla)) {
		$sIds = $sIds . ',' . $fila['corf12idoferta'];
	}
	return array($sIds, $sDebug);
}
function f2200_ActualizarInfoMatricula($idPrograma, $id01, $objDB, $bDebug = false)
{
}
function f2200_CicloActual($objDB, $bDebug = false)
{
	$iHoy = fecha_DiaMod();
	$idCicloMax = 0;
	$sDebug = '';
	$sSQL = 'SELECT unae17id FROM unae17cicloacadem WHERE unae17fechaini<=' . $iHoy . ' AND unae17fechafin>=' . $iHoy . '';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) == 0) {
		$sSQL = 'SELECT unae17id FROM unae17cicloacadem ORDER BY unae17fechafin DESC LIMIT 0, 1';
		$tabla = $objDB->ejecutasql($sSQL);
	}
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$idCicloMax = $fila['unae17id'];
	}
	return array($idCicloMax, $sDebug);
}
function f2200_RolConfiguracionAlistamiento($idRolAlistamiento, $iEstadoCampus, $ofer08obligaacreditar, $objDB, $bDebug = false)
{
	$sRol = 3;
	if ($ofer08obligaacreditar == 'S') {
		$sRol = 17;
	}
	$sDebug = '';
	//El rol debe tomar segun el avance y la configuracion ofer10. //$iEstadoCampus
	$sSQL = 'SELECT * FROM ofer10rol WHERE ofer10id=' . $idRolAlistamiento . '';
	if ($bDebug) {
		$sDebug = fecha_microtiempo() . ' Estado Campus=' . $iEstadoCampus . ', Consultando configuracion: ' . $sSQL;
	}
	$tabla10 = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla10) > 0) {
		$fila10 = $objDB->sf($tabla10);
		$sCampo = '';
		switch ($ofer08obligaacreditar) {
			case 'S':
				switch ($iEstadoCampus) {
					case 1:
						$sCampo = 'ofer10perfilest01';
						break;
					case 2:
						$sCampo = 'ofer10perfilest02';
						break; //En alistamiento
					case 4:
						$sCampo = 'ofer10perfilest04';
						break;
					case 7:
						$sCampo = 'ofer10perfilest07';
						break;
					case 8:
						$sCampo = 'ofer10perfilest08';
						break;
					case 9:
						$sCampo = 'ofer10perfilest09';
						break; //Ajustes finales
					case 10:
						$sCampo = 'ofer10perfilest10';
						break;
					case 11:
						$sCampo = 'ofer10perfilest11';
						break;
					case 12:
						$sCampo = 'ofer10perfilest12';
						break;
					case 15:
						$sCampo = 'ofer10perfilest15';
						break;
					case 20: //Para alistar.
					case 19:
						$sCampo = 'ofer10perfilest19';
						break;
					case 16:
						$sCampo = 'ofer10perfilest16';
						break; //Excepcion de alistamiento
				}
				break;
			case 'N':
				switch ($iEstadoCampus) {
					case 1:
						$sCampo = 'ofer10perfilestcer01';
						break;
					case 2:
						$sCampo = 'ofer10perfilestcer02';
						break; //En alistamiento
					case 4:
						$sCampo = 'ofer10perfilestcer04';
						break;
					case 9:
						$sCampo = 'ofer10perfilestcer09';
						break; //Ajustes finales
					case 15:
						$sCampo = 'ofer10perfilestcer15';
						break;
					case 20: //Para alistar.
					case 19:
						$sCampo = 'ofer10perfilestcer19';
						break;
					case 16:
						$sCampo = 'ofer10perfilestcer16';
						break; //Excepcion de alistamiento
				}
				break;
			case 'E':
				switch ($iEstadoCampus) {
					case 1:
						$sCampo = 'ofer10perfilexcep01';
						break;
					case 2:
						$sCampo = 'ofer10perfilexcep02';
						break; //En alistamiento
					case 4:
						$sCampo = 'ofer10perfilexcep04';
						break;
						//case 7:$sCampo='ofer10perfilest07';break;
						//case 8:$sCampo='ofer10perfilest08';break;
					case 9:
						$sCampo = 'ofer10perfilexcep09';
						break; //Ajustes finales
						//case 10:$sCampo='ofer10perfilest10';break;
					case 11:
						$sCampo = 'ofer10perfilexcep11';
						break;
					case 12:
						$sCampo = 'ofer10perfilexcep12';
						break;
					case 15:
						$sCampo = 'ofer10perfilexcep15';
						break;
					case 20: //Para alistar.
					case 19:
						$sCampo = 'ofer10perfilexcep19';
						break;
					case 16:
						$sCampo = 'ofer10perfilexcep16';
						break; //Excepcion de alistamiento
				}
				break;
		}
		if ($sCampo != '') {
			if (isset($fila10[$sCampo]) == 0){
				if ($bDebug) {
					$sDebug = $sDebug . '<br>' . fecha_microtiempo() . ' <span class="rojo">No existe el campo</span> ' . $sCampo . '';
				}
				$sCampo = '';
			}
		}
		if ($sCampo != '') {
			if ($bDebug) {
				$sDebug = $sDebug . '<br>' . fecha_microtiempo() . ' Rol disponible =' . $fila10[$sCampo] . '';
			}
			if ((int)$fila10[$sCampo] != 0) {
				$sRol = $fila10[$sCampo];
			}
		} else {
			if ($bDebug) {
				$sDebug = $sDebug . '<br>' . fecha_microtiempo() . ' NO se considera un rol para el estado del curso.';
			}
		}
	}
	return array($sRol, $sDebug);
}
function f2200_RolUsuario($idTercero, $idCurso, $idPeriodo, $idAula, $objDB, $bDebug = false)
{
	$aAulas = array('A', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', '', '0', '', '', '', '', '5', '', '', '', 'R', 'S', '', '', 'V');
	$sRol = '';
	$sGrupo = '';
	$iAulas = 1;
	$aAulas[1] = 1;
	$aTitulos[1] = $idCurso . 'A_' . $idPeriodo;
	$aTitulos[1] = 'Aula A';
	$sError = '';
	$sDebug = '';
	$sDebug2 = '';
	$bResuelto = false;
	$bEnProduccion = false;
	$bEnAcreditacion = false;
	$bEnValidacion = false;
	$bEnCasosEspeciales = false;
	$bTodasLasAulas = false;
	$bTutorDirecto = false;
	$bIncluirGrupos = false;
	$ofer08c2fechaarmagrupos = 0;
	$idEscuela = 0;
	$idPrograma = 0;
	if ($bDebug) {
		$sDebug = fecha_microtiempo() . ' Analizando datos de acceso...<br>';
	}
	//Primero ver que la oferta exista.
	$sSQL = 'SELECT TB.ofer08id, TB.ofer08estadooferta, TB.ofer08estadocampus, TB.ofer08obligaacreditar, TB.ofer08idescuela, 
	TB.ofer08idprograma, TB.ofer08aliasmoodle, TB.ofer08iddirector, TB.ofer08idacomanamento, T4.unad40titulo, 
	TB.ofer08c2fechaarmagrupos 
	FROM ofer08oferta AS TB, unad40curso AS T4
	WHERE TB.ofer08idper_aca=' . $idPeriodo . ' AND TB.ofer08idcurso=' . $idCurso . ' AND TB.ofer08cead=0 AND TB.ofer08idcurso=T4.unad40id';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$idOferta = $fila['ofer08id'];
		$iEstadoOferta = $fila['ofer08estadooferta'];
		$iEstadoCampus = $fila['ofer08estadocampus'];
		$idEscuela = $fila['ofer08idescuela'];
		$idPrograma = $fila['ofer08idprograma'];
		$sMarcaCurso = $fila['ofer08aliasmoodle'];
		$sCodCurso = trim($fila['unad40titulo']);
		$idAlista = $fila['ofer08iddirector'];
		$idAcompanamento = $fila['ofer08idacomanamento'];
		$ofer08obligaacreditar = $fila['ofer08obligaacreditar'];
		$ofer08c2fechaarmagrupos = $fila['ofer08c2fechaarmagrupos'];
		switch ($iEstadoOferta) {
			case 1:
				break;
			default:
				$sError = 'La oferta de este curso no esta disponible';
				break;
		}
		switch ($iEstadoCampus) {
			case 10: //Acreditado
			case 12: //Certificado
			case 13: //Validado
			case 16: //Excepción Alistamiento.
				if ($iEstadoOferta == 1) {
					$bEnProduccion = true;
				}
				break;
			default:
				switch ($ofer08obligaacreditar) {
					case 'S':
						$bEnAcreditacion = true;
						break;
					case 'N':
						//Si tiene la excepcion de certificacion es una validacion.
						$sSQL = 'SELECT ofer67estadorespuesta FROM ofer67ofertanovedad WHERE ofer67idcurso=' . $idCurso . ' AND ofer67idperiodo=' . $idPeriodo . ' AND ofer67tiponovedad=9 ORDER BY ofer67consec DESC';
						$tabla = $objDB->ejecutasql($sSQL);
						if ($objDB->nf($tabla) > 0) {
							$fila = $objDB->sf($tabla);
							if ($fila['ofer67estadorespuesta'] == 1) {
								$bEnValidacion = true;
							}
						}
						break;
					default:
						$bEnCasosEspeciales = true;
						break;
				}
				break;
		}
	} else {
		$sError = 'No se ha encontrado la oferta del curso';
		$bResuelto = true;
	}
	//Estudiante...
	if (!$bResuelto) {
		$iHoy = fecha_DiaMod();
		//Saber si es un estudiante.
		list($idContTercero, $sError) = f1011_BloqueTercero($idTercero, $objDB);
		$sSQL = 'SELECT core04estado, core04idaula, core04idgrupo, T2.exte02fechalimgrupos, core04id 
		FROM core04matricula_' . $idContTercero . ' AS TB, exte02per_aca AS T2 
		WHERE core04tercero=' . $idTercero . ' AND core04peraca=' . $idPeriodo . ' AND core04idcurso=' . $idCurso . ' 
		AND TB.core04peraca=T2.exte02id ';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			switch ($fila['core04estado']) {
				case 1: //No disponible {Error de matricula, por tanto no se hace nada.}
					break;
				case 9: //Cancelado
				case 10: //Aplazado
					$sError = 'Curso no disponible por aplazamiento o cancelaci&oacute;n';
					if ($bDebug) {
						$sDebug = 'Alumno con cancelacion o aplazamiento';
					}
					$bResuelto = true;
					break;
				case 13:
					$sError = 'Curso no disponible [Error 2204.5]';
					if ($bDebug) {
						$sDebug = 'Alumno con retiro forzoso';
					}
					$bResuelto = true;
					break;
				default:
					$sRol = 5;
					$sGrupo = 'PRELIMINAR';
					$bBuscarGrupo = false;
					if (!f102_GestionaGrupos($idPeriodo, $objDB)) {
						$bBuscarGrupo = true;
					} else {
						if ($ofer08c2fechaarmagrupos <> 0) {
							$bBuscarGrupo = true;
						} else {
							if ($bDebug) {
								$sDebug = 'Acceso como estudiante [PRELIMINAR]';
							}
						}
					}
					if ($bBuscarGrupo) {
						$bSeGestionaGrupos = f102_GestionaGrupos($idPeriodo, $objDB);
						if ($fila['core04idgrupo'] == 0) {
							//No tiene grupo asignado, entonces es un invitado hasta tener grupo.
							if (!$bSeGestionaGrupos) {
								$sRol = 9;
								$sGrupo = 0;
								if ($bDebug) {
									$sDebug = 'Acceso como estudiante invitado [Sin grupo]';
								}
							} else {
								if ($bDebug) {
									$sDebug = 'Acceso como estudiante [PRELIMINAR]';
								}
							}
						} else {
							$sGrupo = 0;
							$bUbicarGrupo = false;
							if ($bSeGestionaGrupos) {
								if ($ofer08c2fechaarmagrupos <> 0) {
									$bUbicarGrupo = true;
								} else {
									if ($bDebug) {
										$sDebug = 'Acceso como estudiante [PRELIMINAR]';
									}
								}
							} else {
								$bUbicarGrupo = true;
							}
							if ($bUbicarGrupo) {
								$idContPeraca = f146_Contenedor($idPeriodo, $objDB);
								if ($idContPeraca == 0) {
									if (!$bSeGestionaGrupos) {
										if ($bDebug) {
											$sDebug = 'Acceso como estudiante invitado [No se ha definido el contendor de grupos para el periodo]';
										}
									} else {
										$sDebug = 'Acceso como estudiante [PRELIMINAR]';
									}
								} else {
									//Toca sacar el grupo de la tabla 06
									$sSQL = 'SELECT core06consec, core06idaula FROM core06grupos_' . $idContPeraca . ' WHERE core06id=' . $fila['core04idgrupo'] . '';
									$tabla06 = $objDB->ejecutasql($sSQL);
									if ($objDB->nf($tabla06) > 0) {
										$fila06 = $objDB->sf($tabla06);
										$sGrupo = $sCodCurso . '_' . $fila06['core06consec'];
										$aAulas[1] = $fila06['core06idaula'];
										$aTitulos[1] = 'Aula ' . $aAulas[$fila06['core06idaula']];
										if ($bDebug) {
											$sDebug = 'Acceso como estudiante grupo ' . $sGrupo . ' - Aula ' . $fila06['core06idaula'] . '';
											if ($bSeGestionaGrupos) {
												$sDebug = $sDebug . ' - Gestiona grupos';
											}
										}
									} else {
										//26 Ago 2021 - liberar esa asignación porque a todas luces ha habido un error.
										$sSQL = 'UPDATE core04matricula_' . $idContTercero . ' SET core04idgrupo=0 WHERE core04id=' . $fila['core04id'] . '';
										$result = $objDB->ejecutasql($sSQL);
										if ($bDebug) {
											$sDebug = 'Acceso como estudiante invitado [No se encuentra el grupo Ref: ' . $fila['core04idgrupo'] . ']';
										}
										if ($idPeriodo > 951) {
											// AND core16idprograma=
											$sSQL = 'UPDATE core16actamatricula SET core16procagenda=0 WHERE core16peraca=' . $idPeriodo . ' AND core16tercero=' . $idTercero . '';
											$result = $objDB->ejecutasql($sSQL);
										}
									}
								}
							}
						}
						//Fin de si busca el grupo
					}
					$bResuelto = true;
					break;
			}
		} else {
			if ($bDebug) {
				$sDebug = fecha_microtiempo() . ' No hay registro de estudiante<br>';
			}
		}
	}
	//Director
	if (!$bResuelto) {
		$idContenedor = f146_Contenedor($idPeriodo, $objDB);
		//Saber si es el director acompañamiento
		$bEsDirector = false;
		$bRevisaAdicionales = false;
		if ($idAcompanamento == $idTercero) {
			if ($bEnProduccion) {
				$bEsDirector = true;
			} else {
				//$bRevisaAdicionales=true;
				$bPasa = false;
				if ($bEnAcreditacion) {
					$bPasa = true;
				}
				if ($bEnCasosEspeciales) {
					$bPasa = true;
				}
				if ($bEnValidacion) {
					$bPasa = true;
				}
				if ($bPasa) {
					$sSQL = 'SELECT 1 
					FROM ofer38matricula AS T38
					WHERE T38.ofer38idoferta=' . $idOferta . ' AND T38.ofer38idtercero=' . $idTercero . ' AND T38.ofer38activo="S" AND T38.ofer38idrol=17 
					LIMIT 0,1';
					$tabla = $objDB->ejecutasql($sSQL);
					if ($objDB->nf($tabla) > 0) {
						$sRol = 17;
						$sGrupo = '0';
						$bResuelto = true;
						if ($bDebug) {
							$sDebug = 'Acceso como Dise&ntilde;ador [Rol 17 Grupo ' . $sGrupo . '].';
						}
					}
				} else {
					$bEsDirector = true;
				}
			}
		} else {
			//Saber si es un director suplente.
			$sSQL = 'SELECT 1 FROM corf12directores WHERE corf12idoferta=' . $idOferta . ' AND corf12idtercero=' . $idTercero . ' AND corf12activo=1';
			$tabla = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla) > 0) {
				$bEsDirector = true;
			} else {
				$bRevisaAdicionales = true;
			}
		}
		if ($bRevisaAdicionales) {
			$sSQL = 'SELECT 1 
			FROM ofer38matricula AS T38
			WHERE T38.ofer38idoferta=' . $idOferta . ' AND T38.ofer38idtercero=' . $idTercero . ' AND T38.ofer38activo="S" AND T38.ofer38idrol=3 
			LIMIT 0,1';
			$tabla = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla) > 0) {
				$bEsDirector = true;
			}
		}
		if ($bEsDirector) {
			$sRol = 3;
			$sNombreProceso = 'Producci&oacute;n';
			//El rol podria cambiar...
			if (!$bEnProduccion) {
				$sNombreProceso = 'Certificaci&oacute;n';
				if ($bEnAcreditacion) {
					$sNombreProceso = 'Acreditaci&oacute;n';
				}
				if ($bEnCasosEspeciales) {
					$sNombreProceso = 'Casos Especiales';
				}
				if ($bEnValidacion) {
					$sNombreProceso = 'Validaci&oacute;n';
				}
				//Aqui en lugar de aceptar el rol quemado debemos buscarle el rol de la configuracion.
				list($sRol, $sDebugC) = f2200_RolConfiguracionAlistamiento(2, $iEstadoCampus, $ofer08obligaacreditar, $objDB, $bDebug);
				if ($sDebugC != '') {
					$sDebug = $sDebug . '<br>' . $sDebugC;
				}
			}
			//Fin de si el rol cambia..
			$sGrupo = '0';
			$bResuelto = true;
			$bIncluirGrupos = true;
			$sCondiAula = '';
			if ($idAula == '') {
				$bTodasLasAulas = true;
			} else {
				$sCondiAula = ' AND TB.core06idaula=' . $idAula . ' ';
			}
			//Ver si es tutor en en grupos.
			$sSQL = 'SELECT TB.core06consec FROM core06grupos_' . $idContenedor . ' AS TB WHERE TB.core06peraca=' . $idPeriodo . ' AND TB.core06idcurso=' . $idCurso . ' AND TB.core06idtutor=' . $idTercero . $sCondiAula . ' ORDER BY TB.core06consec';
			$tabla = $objDB->ejecutasql($sSQL);
			while ($fila = $objDB->sf($tabla)) {
				//if ($sGrupo!=''){$sGrupo=$sGrupo.'@';}
				$sGrupo = $sGrupo . '@' . $sCodCurso . '_' . $fila['core06consec'];
			}
			//Ver los grupos donde es tutor manual.
			if ($idAula == '') {
				$sSQL = 'SELECT T41.ofer41codigo 
				FROM ofer38matricula AS T38, ofer41grupos AS T41 
				WHERE T38.ofer38idoferta=' . $idOferta . ' AND T38.ofer38idtercero=' . $idTercero . ' AND T38.ofer38activo="S" AND T38.ofer38grupo=T41.ofer41id AND T41.ofer41codigo NOT IN (0)
				GROUP BY T41.ofer41codigo';
				$tabla = $objDB->ejecutasql($sSQL);
				while ($fila = $objDB->sf($tabla)) {
					//if ($sGrupo!=''){$sGrupo=$sGrupo.'@';}
					$sGrupo = $sGrupo . '@' . $fila['ofer41codigo'];
				}
			}
			//Terminado el tema de los grupos
			if ($bDebug) {
				$sDebug = 'Acceso como Director de curso [' . $sNombreProceso . '] [Rol ' . $sRol . ' Grupo ' . $sGrupo . '].';
			}
		} else {
			if ($idAlista == $idTercero) {
				if (!$bEnProduccion) {
					//Toca tomar la configuracion de la ofer10
					$sGrupo = 0;
					$bResuelto = true;
					$sNombreProceso = 'Certificaci&oacute;n';
					if ($bEnAcreditacion) {
						$sNombreProceso = 'Acreditaci&oacute;n';
					}
					if ($bEnCasosEspeciales) {
						$sNombreProceso = 'Casos Especiales';
					}
					if ($bEnValidacion) {
						$sNombreProceso = 'Validaci&oacute;n';
					}
					list($sRol, $sDebugC) = f2200_RolConfiguracionAlistamiento(2, $iEstadoCampus, $ofer08obligaacreditar, $objDB, $bDebug);
					if ($bDebug) {
						$sDebug = 'Acceso como Director de curso alistamiento [' . $sNombreProceso . '] [Rol <b>' . $sRol . '</b> Grupo ' . $sGrupo . ']';
					}
					if ($sDebugC != '') {
						$sDebug = $sDebug . '<br>' . $sDebugC;
					}
				} else {
					//Octubre 16 de 2020...
					//Revisar si es una excepcion de alistamiento, en ese caso pasa
					switch ($iEstadoCampus) {
						case 16: //Excepcion de alistamiento
							$sRol = 17;
							$sGrupo = 0;
							$bResuelto = true;
							///------------ Ver como mejorar.. --------------------
							$sCondiAula = '';
							if ($idAula == '') {
								$bTodasLasAulas = true;
							} else {
								$sCondiAula = ' AND TB.core06idaula=' . $idAula . ' ';
							}
							//Ver si es tutor en en grupos.
							$sSQL = 'SELECT TB.core06consec FROM core06grupos_' . $idContenedor . ' AS TB WHERE TB.core06peraca=' . $idPeriodo . ' AND TB.core06idcurso=' . $idCurso . ' AND TB.core06idtutor=' . $idTercero . $sCondiAula . ' ORDER BY TB.core06consec';
							$tabla = $objDB->ejecutasql($sSQL);
							while ($fila = $objDB->sf($tabla)) {
								//if ($sGrupo!=''){$sGrupo=$sGrupo.'@';}
								$sGrupo = $sGrupo . '@' . $sCodCurso . '_' . $fila['core06consec'];
							}
							//Ver los grupos donde es tutor manual.
							if ($idAula == '') {
								$sSQL = 'SELECT T41.ofer41codigo 
							FROM ofer38matricula AS T38, ofer41grupos AS T41 
							WHERE T38.ofer38idoferta=' . $idOferta . ' AND T38.ofer38idtercero=' . $idTercero . ' AND T38.ofer38activo="S" AND T38.ofer38grupo=T41.ofer41id AND T41.ofer41codigo NOT IN (0)
							GROUP BY T41.ofer41codigo';
								$tabla = $objDB->ejecutasql($sSQL);
								while ($fila = $objDB->sf($tabla)) {
									//if ($sGrupo!=''){$sGrupo=$sGrupo.'@';}
									$sGrupo = $sGrupo . '@' . $fila['ofer41codigo'];
								}
							}
							//--------------------------- final... ------- 
							if ($bDebug) {
								$sDebug = 'Acceso como Director de curso Excepci&oacute;n de alistamiento [Rol ' . $sRol . ' Grupo ' . $sGrupo . ']';
							}
							break;
					}
				}
			}
		}
	}
	//Tutor con grupos
	if (!$bResuelto) {
		//Saber si es un tutor
		$sIds48 = '-99';
		$sCondiAula = '';
		if ($idAula == '') {
		} else {
			$sCondiAula = ' AND TB.core06idaula=' . $idAula . ' ';
		}
		$sSQL = 'SELECT TB.core06consec, TB.core06idaula FROM core06grupos_' . $idContenedor . ' AS TB WHERE TB.core06peraca=' . $idPeriodo . ' AND TB.core06idcurso=' . $idCurso . ' AND TB.core06idtutor=' . $idTercero . $sCondiAula . ' ORDER BY TB.core06consec';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$sRol = 4;
			$bResuelto = true;
			$sDebug = '';
			//if ($bDebug){$sDebug=fecha_microtiempo().' Aulas del tutor '.$sSQL.'<br>';}
			while ($fila = $objDB->sf($tabla)) {
				if ($sGrupo != '') {
					$sGrupo = $sGrupo . '@';
				}
				$sGrupo = $sGrupo . $sCodCurso . '_' . $fila['core06consec'];
				$sIds48 = $sIds48 . ',' . $fila['core06idaula'];
			}
			// Grupos en matricula manual
			$sSQL = 'SELECT 1  
			FROM ofer38matricula AS T38, unad58rolmoodle AS T58 
			WHERE T38.ofer38idoferta=' . $idOferta . ' AND T38.ofer38idtercero=' . $idTercero . ' AND T38.ofer38activo="S" AND T38.ofer38idrol=T58.unad58id
			LIMIT 0, 1';
			$tabla38 = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla38) > 0) {
				$sIds48 = $sIds48 . ',1';
			}
			$bTutorDirecto = true;
			//Consolidar las aulas.
			$iAulas = 0;
			$sSQL = 'SELECT unad48consec, unad48aliasmoodle, unad48identificador FROM unad48cursoaula WHERE unad48idcurso=' . $idCurso . ' AND unad48per_aca=' . $idPeriodo . ' AND unad48consec IN (' . $sIds48 . ')';
			$tabla = $objDB->ejecutasql($sSQL);
			while ($fila = $objDB->sf($tabla)) {
				$iAulas++;
				$aAulas[$iAulas] = $fila['unad48consec'];
				switch ($fila['unad48identificador']) {
					case 'H':
						$aTitulos[$iAulas] = 'Habilitaciones';
						break;
					case 'S':
						$aTitulos[$iAulas] = 'Supletorios';
						break;
					case 'V':
						$aTitulos[$iAulas] = 'Validaciones';
						break;
					default:
						$aTitulos[$iAulas] = 'Aula ' . $fila['unad48identificador'];
						break;
				}
			}
			//Terminar en grupos manual.
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . ' Acceso como tutor [Num Aulas ' . $iAulas . '] grupos ' . $sGrupo . '<br>';
			}
		}
	}
	//Tutor sin grupos
	if (!$bResuelto) {
		$sSQL = 'SELECT 1 FROM core20asignacion AS TB WHERE TB.core20idperaca=' . $idPeriodo . ' AND TB.core20idcurso=' . $idCurso . ' AND TB.core20idtutor=' . $idTercero . ' ';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$sRol = 4;
			$iAulas = 1;
			$bResuelto = true;
			if ($bDebug) {
				$sDebug = fecha_microtiempo() . ' Acceso como tutor sin grupos [Rol 4]';
			}
		}
	}
	//E-Monitores
	if (!$bResuelto) {
		$sSQL = 'SELECT 1 FROM plab33emoncurso WHERE plab33idperiodo=' . $idPeriodo . ' AND plab33idcurso=' . $idCurso . ' AND plab33idmonitor=' . $idTercero . ' AND plab33activo=1';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$sRol = 18;
			$sGrupo = 0;
			$bResuelto = true;
			$bTodasLasAulas = $bEnProduccion;
			if ($bDebug) {
				$sDebug = fecha_microtiempo() . ' Acceso como E-monitor [Rol 18]';
			}
		}
	}
	//Lider de programa.
	if (!$bResuelto) {
		//Saber si es un lider de programa
		$sSQL = 'SELECT core09iddirector FROM core09programa WHERE core09id=' . $idPrograma . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			if ($fila['core09iddirector'] == $idTercero) {
				$sRol = 21;
				$sGrupo = 0;
				$bResuelto = true;
				$bTodasLasAulas = true;
				if ($bDebug) {
					$sDebug = 'Acceso como Lider de programa [en producci&oacute;n]';
				}
				if (!$bEnProduccion) {
					$bTodasLasAulas = false;
					if ($bDebug) {
						$sDebug = 'Acceso como Lider de programa [Alistamiento]';
					}
					if ($bEnAcreditacion) {
						$sRol = 22;
						if ($bDebug) {
							$sDebug = 'Acceso como Lider de programa [Alistamiento]';
						}
					}
				}
				//Tener en cuenta que pueden asignarle rol de tutor por matricula directa.
				//Termina si esta listo...
			}
		}
	}
	//Secretario o Decano
	if (!$bResuelto) {
		//Saber si es secretario o decano 
		$sSQL = 'SELECT core12iddecano, core12idadministrador FROM core12escuela WHERE core12id=' . $idEscuela . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			if ($fila['core12idadministrador'] == $idTercero) {
				$sRol = 20;
				$sGrupo = 0;
				$bResuelto = true;
				$bTodasLasAulas = $bEnProduccion;
				$bTutorDirecto = true;
				if ($bDebug) {
					$sDebug = 'Acceso como Secretario Acad&eacute;mico';
				}
			}
			if ($fila['core12iddecano'] == $idTercero) {
				$sRol = 19;
				$sGrupo = 0;
				$bResuelto = true;
				$bTodasLasAulas = $bEnProduccion;
				if ($bDebug) {
					$sDebug = 'Acceso como Decano';
				}
			}
		}
	}
	//Asignación por grupo.
	if (!$bResuelto) {
		//Si hace parte de grupos de trabajo que tengan perfil
		$sId27 = '-99';
		$sId28 = '-99';
		$sSQL = 'SELECT ofer73idequipo FROM ofer73matriculaequipo WHERE ofer73activa=1 GROUP BY ofer73idequipo';
		$tabla = $objDB->ejecutasql($sSQL);
		while ($fila = $objDB->sf($tabla)) {
			$sId27 = $sId27 . ',' . $fila['ofer73idequipo'];
		}
		//Ahora con los equipos autorizados ver si es miembro de algun equipo de estos.
		if ($sId27 != '-99') {
			$sSQL = 'SELECT bita28idequipotrab FROM bita28eqipoparte WHERE bita28idtercero=' . $idTercero . ' AND bita28idequipotrab IN (' . $sId27 . ') AND bita28activo="S"';
			//if ($bDebug){$sDebug='Verificando pertinencia a equipos de trabajo: '.$sSQL.'<br>';}
			$tabla = $objDB->ejecutasql($sSQL);
			while ($fila = $objDB->sf($tabla)) {
				$sId28 = $sId28 . ',' . $fila['bita28idequipotrab'];
			}
		}
		if ($sId28 != '-99') {
			//Ahora con los equipos de trabajo a los que esta asociado, vemos que perfiles tiene y en que prevalencia.
			$sSQL = 'SELECT TB.ofer73idrol, TB.ofer73idequipo 
			FROM ofer73matriculaequipo AS TB, unad58rolmoodle AS T58 
			WHERE TB.ofer73idequipo IN (' . $sId28 . ') AND TB.ofer73idrol=T58.unad58id 
			ORDER BY T58.unad58prioridad DESC, T58.unad58orden';
			$tabla = $objDB->ejecutasql($sSQL);
			$tabla = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla) > 0) {
				$fila = $objDB->sf($tabla);
				$sRol = $fila['ofer73idrol'];
				$sGrupo = 0;
				$bResuelto = true;
				$bTodasLasAulas = $bEnProduccion;
				if ($bDebug) {
					$sDebug = 'Acceso Por Asiganci&oacute;n de Permisos Por Equipo de Trabajo Ref [' . $fila['ofer73idequipo'] . '] Rol ' . $sRol . '';
				}
			}
		}
	}
	//Matricula directa
	if (!$bResuelto) {
		//ahora si, matricula manual, solo el rol mas prevalente
		$sIds = '-99';
		$sSQL = 'SELECT T38.ofer38idrol 
		FROM ofer38matricula AS T38, unad58rolmoodle AS T58 
		WHERE T38.ofer38idoferta=' . $idOferta . ' AND T38.ofer38idtercero=' . $idTercero . ' AND T38.ofer38activo="S" AND T38.ofer38idrol=T58.unad58id
		GROUP BY T38.ofer38idrol
		ORDER BY T58.unad58prioridad DESC, T58.unad58orden';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			$sRol = $fila['ofer38idrol'];
			switch ($sRol) {
				case 3:  //Director de curso
				case 17: //diseñador
					$bTutorDirecto = true;
					break;
			}
			$bResuelto = true;
			//Ahora saber a que grupos pertenece...
			$sSQL = 'SELECT T41.ofer41codigo 
			FROM ofer38matricula AS T38, ofer41grupos AS T41 
			WHERE T38.ofer38idoferta=' . $idOferta . ' AND T38.ofer38idtercero=' . $idTercero . ' AND T38.ofer38activo="S" AND ofer38idrol=' . $sRol . ' AND T38.ofer38grupo=T41.ofer41id
			GROUP BY T41.ofer41codigo';
			$tabla = $objDB->ejecutasql($sSQL);
			while ($fila = $objDB->sf($tabla)) {
				if ($sGrupo != '') {
					$sGrupo = $sGrupo . '@';
				}
				$sGrupo = $sGrupo . $fila['ofer41codigo'];
			}
			if ($bDebug) {
				$sDebug = fecha_microtiempo() . 'Acceso por matricula directa Rol: ' . $sRol . ' Grupos: ' . $sGrupo . ' [Rol: ' . $sRol . ']';
			}
		}
	}

	if ($bTutorDirecto) {
		// AND ofer38idrol=4
		//Ver los grupos donde es tutor manual.
		$sGruposMD = '';
		$sSQL = 'SELECT T41.ofer41codigo 
		FROM ofer38matricula AS T38, ofer41grupos AS T41 
		WHERE T38.ofer38idoferta=' . $idOferta . ' AND T38.ofer38idtercero=' . $idTercero . ' AND T38.ofer38activo="S" AND T38.ofer38grupo=T41.ofer41id AND T41.ofer41codigo NOT IN (0)
		GROUP BY T41.ofer41codigo';
		$tabla = $objDB->ejecutasql($sSQL);
		while ($fila = $objDB->sf($tabla)) {
			//if ($sGrupo!=''){$sGrupo=$sGrupo.'@';}
			$sGrupo = $sGrupo . '@' . $fila['ofer41codigo'];
			$sGruposMD = $sGruposMD . '@' . $fila['ofer41codigo'];
		}
		if ($bDebug) {
			if ($sGruposMD != '') {
				$sDebug = $sDebug . '<br>' . fecha_microtiempo() . ' Acceso a grupos por Matricula directa: ' . $sGruposMD . '<br>';
			}
		}
	}
	if (!$bResuelto) {
		if ($bEnProduccion) {
			//Invitado porque no hay de otra.
			$sRol = 9;
			$sGrupo = 0;
			$bTodasLasAulas = true;
			if ($bDebug) {
				$sDebug = fecha_microtiempo() . ' Acceso como invitado<br>';
			}
		} else {
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . ' Curso no disponible para invitados<br>';
			}
		}
	}
	if ($bTodasLasAulas) {
		//Todas las aulas que esten a ese curso.
		$iAulas = 0;
		$aAulas[1] = 1;
		$aTitulos[1] = 'Aula A';
		$sSQL = 'SELECT unad48consec, unad48aliasmoodle, unad48identificador FROM unad48cursoaula WHERE unad48idcurso=' . $idCurso . ' AND unad48per_aca=' . $idPeriodo . '';
		$tabla = $objDB->ejecutasql($sSQL);
		while ($fila = $objDB->sf($tabla)) {
			$iAulas++;
			$aAulas[$iAulas] = $fila['unad48consec'];
			switch ($fila['unad48identificador']) {
				case 'H':
					$aTitulos[$iAulas] = 'Habilitaciones';
					break;
				case 'S':
					$aTitulos[$iAulas] = 'Supletorios';
					break;
				case 'V':
					$aTitulos[$iAulas] = 'Validaciones';
					break;
				default:
					$aTitulos[$iAulas] = 'Aula ' . $fila['unad48identificador'];
					break;
			}
		}
	}
	if ($iAulas == 0) {
		$iAulas = 1;
		$aAulas[1] = 1;
		$aTitulos[1] = 'Aula A';
	}
	/*
	//Junio 1 de 2021 - Se retira esta condicion que estaba causando problema con tutores por matricula directa.
	if ($bResuelto){
		switch($sRol){
			case 4:
			if ($sGrupo==='0'){
				$sGrupo='';
				}
			if ($sGrupo===0){
				$sGrupo='';
				}
			break;
			}
		}
	*/
	return array($sRol, $sGrupo, $iAulas, $aAulas, $aTitulos, $sError, $sDebug);
}
//Grupos de estados -- Esto se hace para evitar que existan comparaciones diferentes.
function f2201_PEIEstadosAprobado()
{
	$sLista = '5,7,8,10,11,15,17,25';
	//El 9 son los NO REQUERIDOS.
	return $sLista;
}
//Armar el PEI
function f2201_ActualizarNotas($core01id, $objDB, $bDebug = false)
{
	$sError = '';
	$sDebug = '';
	$core01idestado = 0;
	//Octubre 15 de 2021 - Se ajusta para marcar los cursos matriculados (estado 6)
	$sSQL = 'SELECT TB.core01idtercero, T11.unad11doc, TB.core01idprograma, TB.core01peracainicial, 
	TB.core01idtipopractica, TB.core01estadopractica, TB.core01idplandeestudios, TB.core01idestado 
	FROM core01estprograma AS TB, unad11terceros AS T11 
	WHERE TB.core01id=' . $core01id . ' AND TB.core01idtercero=T11.unad11id';
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' <b>Se inicia el historico</b>: ' . $sSQL . '<br>';
	}
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$core01idtercero = $fila['core01idtercero'];
		$core01idplandeestudios = $fila['core01idplandeestudios'];
		$unad11doc = $fila['unad11doc'];
		$core01idprograma = $fila['core01idprograma'];
		$core01peracainicial = $fila['core01peracainicial'];
		$core01idtipopractica = $fila['core01idtipopractica'];
		$core01estadopractica = $fila['core01estadopractica'];
		$core01idestado = $fila['core01idestado'];
	} else {
		$sError = 'No se ha encontrado el plan de estudidos solicitado.';
	}
	if ($sError == '') {
		list($idContTercero, $sError) = f1011_BloqueTercero($core01idtercero, $objDB);
		$iPeriodoBase = 0;
		$iFechaMatricula = 0;
	}
	if ($sError == '') {
		if ($core01idprograma == 450) {
			$sError = 'No se permite importar el programa';
		}
	}
	if ($sError == '') {
		$iPeriodoBase = 0;
		$sIdsPrograma = $core01idprograma;
	}
	if ($sError == '') {
		switch($core01idestado) {
			case -2: //Es aspirante
			case -1: // Es admitido.
				$sSQL = 'SELECT 1 FROM core16actamatricula WHERE core16tercero=' . $core01idtercero . ' AND core16idprograma=' . $core01idprograma . '';
				$result = $objDB->ejecutasql($sSQL);
				if ($bDebug) {
					$sDebug = $sDebug . fecha_microtiempo() . ' <b>Verificando si tiene matricula en el programa.</b>: ' . $sSQL . '<br>';
				}
				$tabla16 = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($tabla16) > 0) {
					$core01idestado = 0;
					$sSQL = 'UPDATE core01estprograma SET core01idestado=' . $core01idestado . ' WHERE core01id=' . $core01id . '';
					$result = $objDB->ejecutasql($sSQL);
					if ($bDebug) {
						$sDebug = $sDebug . fecha_microtiempo() . ' <b>Ajustando el estado del PEI</b>: ' . $sSQL . '<br>';
					}
				}
				break;
		}
		/*
		//Ahora que ya importo vamos a procesar todo lo que no este procesado
		$sSQL='SELECT core16id FROM core16actamatricula WHERE core16tercero='.$core01idtercero.' AND core16peraca>760 AND core16idprograma='.$core01idprograma.'';
		$tabla16=$objDB->ejecutasql($sSQL);
		while($fila16=$objDB->sf($tabla16)){
			//Ahora que le monte los promedios
			list($core16aplicada, $sError, $sDebugP)=f2216_AplicarPromedios($fila16['core16id'], $objDB, $bDebug);
			$sDebug=$sDebug.$sDebugP;
			}
		*/
	}
	if ($sError == '') {
		//Mayo 3 de 2022 vamos a considerar las homologaciones en este punto
		$sSQL = 'SELECT core71id 
		FROM core71homolsolicitud 
		WHERE core71idestudiante=' . $core01idtercero . ' AND core71idplanest=' . $core01idplandeestudios . ' AND core71estado=16';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' <b>Cursos Homologados</b>: ' . $sSQL . '<br>';
		}
		$tabla71 = $objDB->ejecutasql($sSQL);
		while ($fila71 = $objDB->sf($tabla71)) {
			list($sErrorH, $sDebugH) = f2203_AplicarHomologacion($fila71['core71id'], $objDB, $bDebug, $idContTercero);
			$sDebug = $sDebug . $sDebugH;
		}
	}
	if ($sError == '') {
		//Ahora si a alimentar los planes de estudio...
		//A nivel estrategia lo mejor es hacerlo curso a curso y que luego un proceso invoque todo el listado.
		//El proceso se deja en la libcore.php
		// Julio 27 de 2022 - Se incluye el estado 2 que corresponde a los cursos externos.
		$sIds40 = '-99';
		$sSQL = 'SELECT TB.core04id, TB.core04idcurso
		FROM core04matricula_' . $idContTercero . ' AS TB, ofer08oferta AS T8
		WHERE TB.core04tercero=' . $core01idtercero . ' AND TB.core04peraca>760 AND TB.core04estado IN (2, 7, 8) AND core04resdef>=core04est_aprob
		AND TB.core04peraca=T8.ofer08idper_aca AND TB.core04idcurso=T8.ofer08idcurso AND T8.ofer08cead=0 AND T8.ofer08c2fechacierre<>0';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' <b>Cursos aprobados</b> desde 2020: ' . $sSQL . '<br>';
		}
		$tabla = $objDB->ejecutasql($sSQL);
		while ($fila = $objDB->sf($tabla)) {
			$sIds40 = $sIds40 . ',' . $fila['core04idcurso'];
		}
		$sSQL = 'SELECT TB.core04id, TB.core04idcurso
		FROM core04matricula_' . $idContTercero . ' AS TB
		WHERE TB.core04tercero=' . $core01idtercero . ' AND TB.core04peraca<761 AND TB.core04estado IN (7, 8) AND core04resdef>=core04est_aprob';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' <b>Cursos aprobados</b> previos: ' . $sSQL . '<br>';
		}
		$tabla = $objDB->ejecutasql($sSQL);
		while ($fila = $objDB->sf($tabla)) {
			$sIds40 = $sIds40 . ',' . $fila['core04idcurso'];
		}
		//Ahora si consultamos cuales de estos estaran pendientes de ser registrados en el plan de estudios.
		/*
		Estados que pueden tener los cursos.
		0	Disponible	0	FF6600
		1	Pendiente de Prerequisito	1	FF0000
		2	Solicitado en Homologación	2	FF6600
		3	En Estudio de Homologación	3	FF6600
		4	Homologación Aprobada	4	FF6600
		5	Homologado	5	000033
		6	Matriculado	1	FF6600
		7	Aprobado	7	000033
		8	Requisto Cumplido	8	000033
		9	Excludio	9	000033
		12	Solicitado en Suficiencia	2	FF6600
		13	En Estudio de Suficiencia	3	FF6600
		14	Suficiencia Aprobada	4	FF6600
		15	Aprobado por suficiencia	5	000033
		15  Ciclo Aprobado en otra institucion 
		25 Homologado por Equivalencia
		*/
		$sSQL = 'SELECT TB.core03id, TB.core03estado 
		FROM core03plandeestudios_' . $idContTercero . ' AS TB
		WHERE TB.core03idestprograma=' . $core01id . ' AND ((TB.core03tieneequivalente=0 AND TB.core03idcurso IN (' . $sIds40 . ')) OR (TB.core03tieneequivalente<>0)) AND TB.core03estado IN (0,1,6)';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' <b>Plan de estudio a ser construido</b>: ' . $sSQL . '<br>';
		}
		$tabla = $objDB->ejecutasql($sSQL);
		while ($fila = $objDB->sf($tabla)) {
			list($sErrorL, $sDebugL) = f2203_ArmarFilaPlan($idContTercero, $fila['core03id'], $objDB, $bDebug);
			$sDebug = $sDebug . $sDebugL;
		}
		//Hasta aqui el proceso registra, ahora marcar los que estan matriculados (solo se considera matriculado.)
		$sIds40Mat = '-99';
		$sSQL = 'SELECT TB.core04id, TB.core04idcurso
		FROM core04matricula_' . $idContTercero . ' AS TB, ofer08oferta AS T8
		WHERE TB.core04tercero=' . $core01idtercero . ' AND TB.core04peraca>760 AND TB.core04estado IN (0, 2, 5, 7, 8)
		AND TB.core04peraca=T8.ofer08idper_aca AND TB.core04idcurso=T8.ofer08idcurso AND T8.ofer08cead=0 AND T8.ofer08c2fechacierre=0';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' <b>Cursos matriculados</b> desde 2020: ' . $sSQL . '<br>';
		}
		$tabla = $objDB->ejecutasql($sSQL);
		while ($fila = $objDB->sf($tabla)) {
			$sIds40Mat = $sIds40Mat . ',' . $fila['core04idcurso'];
		}
		//Marcar los cursos como matriculados.
		$sSQL = 'UPDATE core03plandeestudios_' . $idContTercero . ' SET core03estado=6 
		WHERE core03idestprograma=' . $core01id . ' AND core03estado IN (0, 1) AND core03idcurso IN (' . $sIds40Mat . ')';
		$result = $objDB->ejecutasql($sSQL);
		//Ahora Quitar la marca de matriculados a todos que ya no la tengan
		$sSQL = 'UPDATE core03plandeestudios_' . $idContTercero . ' SET core03estado=0 
		WHERE core03idestprograma=' . $core01id . ' AND core03estado=6 AND core03idcurso NOT IN (' . $sIds40Mat . ')';
		$result = $objDB->ejecutasql($sSQL);
		//Informar que fue procesado
		$sSQL = 'SELECT 1 FROM core03plandeestudios_' . $idContTercero . ' WHERE core03idestprograma=' . $core01id . ' AND core03estado IN (' . f2201_PEIEstadosAprobado() . ')';
		$tabla3 = $objDB->ejecutasql($sSQL);
		$iNumAprobados = $objDB->nf($tabla3);
		//, core01numcursosaprob='.$iNumAprobados.'
		$sSQL = 'UPDATE core01estprograma SET core01procesar=0, core01numcursosaprob=' . $iNumAprobados . ' WHERE core01id=' . $core01id . '';
		$result = $objDB->ejecutasql($sSQL);
		//Totalizar el plan.
		list($core01avanceplanest, $sErrorP, $sDebugP) = f2203_TotalizarPlanV2($core01id, $objDB, $bDebug, $idContTercero);
		$sDebug = $sDebug . $sDebugP;
		if ($core01idtipopractica > 0) {
			if ($core01estadopractica == 0) {
				//Ver si ya es candidato para la practica.
				$sSQL = 'SELECT oalb41porcplanestudios, olab41nivelrequerido FROM olab41tipopractica WHERE olab41id=' . $core01idtipopractica . '';
				if ($bDebug) {
					$sDebug = $sDebug . fecha_microtiempo() . 'Consultando el tipo de practica: ' . $sSQL . '.<br>';
				}
				$tabla = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($tabla) > 0) {
					$fila = $objDB->sf($tabla);
					if ($fila['oalb41porcplanestudios'] > 0) {
						if ($core01avanceplanest >= $fila['oalb41porcplanestudios']) {
							$sSQL = 'UPDATE core01estprograma SET core01estadopractica=1 WHERE core01id=' . $core01id . '';
							if ($bDebug) {
								$sDebug = $sDebug . fecha_microtiempo() . ' Activando como candidato a practica: ' . $sSQL . '.<br>';
							}
							$result = $objDB->ejecutasql($sSQL);
						}
					}
				}
			}
		}
		//list($sDebugG)=f2201_ActivarParaTrabajoGrado($core01id, $objDB, $bDebug);
		//$sDebug=$sDebug.$sDebugG;
	}
	return array($sError, $sDebug, $core01idestado);
}
//Activar la opcion de que pueda hacer una propuesta de grado.
function f2201_ActivarParaTrabajoGrado($id01, $objDB, $bDebug = false)
{
	//El id01 puede venir vacio, que es cuando se corre el cron
	$sDebug = '';
	$sCondi = '';
	//Octubre 15 de 2021 -- Esta tarea se lleva ahora a la corepei en forma manual, asi que ya no se gestiona mas por este camino.
	/*
	if ((int)$id01!=0){
		$sCondi='TB.core01id='.$id01.' AND ';
		}
	$sSQL='UPDATE core01estprograma AS TB, core09programa AS T9, core22nivelprograma AS T22
	SET TB.core01gradoestado=1
	WHERE '.$sCondi.' TB.core01gradoestado=0 AND TB.core01idestado IN (0,1,2,3,7) AND TB.core01avanceplanest>=75 
	AND TB.core01idprograma=T9.core09id AND T9.core09ofrecetitulo<>0 
	AND T9.cara09nivelformacion=T22.core22id AND T22.core22grupo=3';
	if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' <b>Activando Trabajo de grado Pregrado</b>: '.$sSQL.'<br>';}
	$result=$objDB->ejecutasql($sSQL);
	$sSQL='UPDATE core01estprograma AS TB, core09programa AS T9, core22nivelprograma AS T22
	SET TB.core01gradoestado=1
	WHERE '.$sCondi.' TB.core01gradoestado=0 AND TB.core01idestado IN (0,1,2,3,7) AND TB.core01avanceplanest>=40 
	AND TB.core01idprograma=T9.core09id AND T9.core09ofrecetitulo<>0 
	AND T9.cara09nivelformacion=T22.core22id AND T22.core22grupo=4';
	if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' <b>Activando Trabajo de grado PostGrado</b>: '.$sSQL.'<br>';}
	$result=$objDB->ejecutasql($sSQL);
	*/
	return array($sDebug);
}
//Ajustar si hay cambio de ciclo previo
function f2201_AjustarCicloPrevio($core01id, $objDB, $bDebug = false)
{
	$sError = '';
	$sDebug = '';
	//basicamente los cursos base se marcan con estado 17 plan de estudios y se actualiza el plan de estudios.
	$sSQL = 'SELECT TB.core01idtercero, T11.unad11idtablero, TB.core01idprograma, TB.core01ciclobase 
	FROM core01estprograma AS TB, unad11terceros AS T11 
	WHERE TB.core01id=' . $core01id . ' AND TB.core01idtercero=T11.unad11id';
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' <b>Se inicia el historico</b>: ' . $sSQL . '<br>';
	}
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$unad11idtablero = $fila['unad11idtablero'];
		$core01ciclobase = $fila['core01ciclobase'];
		if ($core01ciclobase == 0) {
			$sError = 'No es necesario ajustar el plan de estudios.';
		}
	} else {
		$sError = 'No se ha encontrado el registro del estudiante.';
	}
	if ($sError == '') {
		//Vamos directo a la core03
		$sSQL = 'UPDATE core03plandeestudios_' . $unad11idtablero . ' SET core03estado=17 
		WHERE core03idestprograma=' . $core01id . ' AND core03nivelcurso<=' . $core01ciclobase . ' AND core03estado IN (0,1)';
		$result = $objDB->ejecutasql($sSQL);
	}
	if ($sError == '') {
		list($sError, $sDebugI) = f2201_ActualizarNotas($core01id, $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugI;
	}
	return array($sError, $sDebug);
}
// Ver el avance en numero de creditos.
function f2201_AvanceEnCreditos($core01id, $objDB, $bDebug = false)
{
	$sDebug = '';
	$sError = '';
	$iAvance = 0;
	$sSQL = 'SELECT SUM(core01numcredbasicosaprob+core01numcredespecificosaprob+ core01numcredelecgeneralesaprob+
	core01numcredelecescuelaaprob+ core01numcredelecprogramaaaprob+ core01numcredeleccomplemaprob) AS Total 
	FROM core01estprograma WHERE core01id=' . $core01id . '';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$iAvance = $fila['Total'];
	}
	return array($iAvance, $sDebug);
}
//Ver si tiene excepciones al plan de estudio.
function f2201_Excepciones($core01id, $objDB, $bDebug = false)
{
	$bTiene = false;
	$sIds = '-99';
	$sDebug = '';
	$sError = '';
	$sSQL = 'SELECT TB.core01idplandeestudios, TB.core01idtercero, T11.unad11idtablero
	FROM core01estprograma AS TB, unad11terceros AS T11 
	WHERE TB.core01id=' . $core01id . ' AND TB.core01idtercero=T11.unad11id';
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' <b>Exepciones: </b> Registro PEI ' . $sSQL . '<br>';
	}
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$idPlan = $fila['core01idplandeestudios'];
		$idTercero = $fila['core01idtercero'];
		$idContenedor = $fila['unad11idtablero'];
	} else {
		$sError = 'No encontrado';
	}
	if ($sError == '') {
		//Ahora ver si el plan tiene cursos excepcion
		$sIds14 = '-99';
		$sSQL = 'SELECT corf14idcurso FROM corf14cursoexcepcion WHERE corf14idplanest=' . $idPlan . '';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' <b>Exepciones: </b>: ' . $sSQL . '<br>';
		}
		$tabla = $objDB->ejecutasql($sSQL);
		while ($fila = $objDB->sf($tabla)) {
			$sIds14 = $sIds14 . ',' . $fila['corf14idcurso'];
		}
		if ($sIds14 == '-99') {
			$sError = 'Sin excepciones';
		}
	}
	if ($sError == '') {
		//Ahora buscar los sobrantes, primero lo que esta en el plan.
		$sIds40 = '-99';
		$sSQL = 'SELECT TB.core03idcurso 
		FROM core03plandeestudios_' . $idContenedor . ' AS TB 
		WHERE TB.core03idestprograma=' . $core01id . ' AND TB.core03idcurso IN (' . $sIds14 . ') AND TB.core03idequivalente=0 AND TB.core03estado<>9';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' <b>Exepciones: </b> Existentes ' . $sSQL . '<br>';
		}
		$tabla = $objDB->ejecutasql($sSQL);
		while ($fila = $objDB->sf($tabla)) {
			$sIds40 = $sIds40 . ',' . $fila['core03idcurso'];
		}
		//Ahora si los sobrantes.
		$sSQL = 'SELECT TB.core04idcurso 
		FROM core04matricula_' . $idContenedor . ' AS TB 
		WHERE TB.core04tercero=' . $idTercero . ' AND TB.core04idcurso IN (' . $sIds14 . ') AND TB.core04idcurso NOT IN (' . $sIds40 . ') AND TB.core04estado IN (7,8) 
		AND TB.core04resdef>=TB.core04est_aprob';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' <b>Exepciones: </b>Resultado ' . $sSQL . '<br>';
		}
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$bTiene = true;
		}
		while ($fila = $objDB->sf($tabla)) {
			$sIds = $sIds . ',' . $fila['core04idcurso'];
		}
	}
	return array($bTiene, $sIds, $sDebug);
}
//Actualizar la fecha de ultima matricula.
function f2201_FechaUltimaMatricula($id01, $objDB, $bDebug = false)
{
	$iFecha = 0;
	$sDebug = '';
	$bSoloHistorico = false;
	$sSQL = 'SELECT core01id, core01idtercero, core01idestado, core01fechaultmatricula, core01idprograma, core01idplandeestudios, core01procesar 
	FROM core01estprograma WHERE core01id=' . $id01 . '';
	$tabla01 = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla01) > 0) {
		$fila01 = $objDB->sf($tabla01);
		$idTercero = $fila01['core01idtercero'];
		$idPrograma = $fila01['core01idprograma'];
		$core16idplanestudio = $fila01['core01idplandeestudios'];
		$sSQL = 'SELECT TB.core16id, TB.core16peraca, TB.core16fechamatricula, T11.unad11doc 
		FROM core16actamatricula AS TB, unad11terceros AS T11 
		WHERE TB.core16tercero=' . $idTercero . ' AND TB.core16idprograma=' . $idPrograma . ' AND TB.core16idplanestudio IN (0, ' . $core16idplanestudio . ') 
		AND TB.core16tercero=T11.unad11id
		ORDER BY TB.core16peraca DESC LIMIT 0,1';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' Matriculas Hechas ' . $sSQL . '<br>';
		}
		$tabla16 = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla16) > 0) {
			$fila16 = $objDB->sf($tabla16);
			$iFecha = $fila16['core16fechamatricula'];
			if ($iFecha == 0) {
				//Traemos la fecha de RCONT - En su momento no se importó.
				$bTraeRyC = false;
				list($objDBRyC, $sDebugC) = TraerDBRyCV2($bDebug);
				$sDebug = $sDebug . $sDebugC;
				if ($objDBRyC == NULL) {
				} else {
					$bTraeRyC = true;
				}
				if ($bTraeRyC) {
					$sSQL = 'SELECT fecha_Matricula
					FROM WSCaracterizacionTEMP
					WHERE codigo=' . $fila16['unad11doc'] . ' AND periodo=' . $fila16['core16peraca'] . '
					ORDER BY fecha_Matricula, t_novedad';
					$tablar = $objDBRyC->ejecutasql($sSQL);
					if ($objDBRyC->nf($tablar) > 0) {
						$filar = $objDBRyC->sf($tablar);
						$iFecha = $filar['fecha_Matricula'];
						$sSQL = 'UPDATE core16actamatricula SET core16fechamatricula=' . $iFecha . ' WHERE core16id=' . $fila16['core16id'] . '';
						$result = $objDB->ejecutasql($sSQL);
					}
					$objDBRyC->CerrarConexion();
				}
			}
			if ($fila01['core01fechaultmatricula'] != $iFecha) {
				//Febrero 21 de 2022 - Se manda a procesar el plan de estudios si cambia la fecha de ultima matricula.
				$idEntidad = Traer_Entidad();
				$iPeriodoSII = 761;
				if ($idEntidad == 1) {
					$iPeriodoSII = 1;
				}
				$iHoy = fecha_DiaMod();
				$iManhana = fecha_NumSumarDias($iHoy, 1);
				$sAdicional = ', core01procesar=' . $iManhana . '';
				if ($fila16['core16peraca'] < $iPeriodoSII) {
					$sAdicional = '';
				} else {
					if ($fila01['core01procesar'] <> 0) {
						$sAdicional = '';
					}
				}
				$sSQL = 'UPDATE core01estprograma SET core01fechaultmatricula=' . $iFecha . $sAdicional . ' WHERE core01id=' . $id01 . '';
				if ($bDebug) {
					$sDebug = $sDebug . fecha_microtiempo() . ' Actualizando Ultima Matricula ' . $sSQL . '<br>';
				}
				$result = $objDB->ejecutasql($sSQL);
			}
			//Ahora verificamos el estado, basicamente si es congruente con el estado actual.
			switch ($fila01['core01idestado']) {
				case 9: //Retirado -- 4 de Noviembre de 2021 - Se fue y se queda ido a menos que vuelva a matricular.
					$iFecha2 = 0; //La fecha en que dijo que se retiraba.
					$sSQL = 'SELECT core22fecha FROM core22gradohistorialest WHERE core22idestprograma=' . $fila01['core01id'] . ' AND core22idestadodestino=9 ORDER BY core22fecha DESC';
					$tabla22 = $objDB->ejecutasql($sSQL);
					if ($objDB->nf($tabla22) > 0) {
						$fila22 = $objDB->sf($tabla22);
						$iFecha2 = $fila22['core22fecha'];
					}
					if ($iFecha > $iFecha2) {
						$iFechaReg = $iFecha;
						$iEstadoFin = 0; //Por defecto esta activo.
						$core22anotacion = 'Se activa por registro de matricula';
					}
					break;
				case 2: //Inactivo
				case 3: //Sin matricula por 2 años o mas.
					$core22anotacion = 'Se activa por registro de matricula';
				case 0: //Activo
					$iFechaReg = $iFecha;
					$iFecha2 = fecha_ArmarNumero(fecha_dia(), fecha_mes(), fecha_agno() - 1);
					$iEstadoFin = 0; //Por defecto esta activo.
					if ($iFecha < $iFecha2) {
						$core22anotacion = 'No se registra matricula';
						$iFechaReg = fecha_NumSumarDias($iFecha, 366);
						$iEstadoFin = 2; //Esa matricula fue hace mas de un año...
						$iFecha3 = fecha_ArmarNumero(fecha_dia(), fecha_mes(), fecha_agno() - 2);
						if ($iFecha < $iFecha3) {
							$iFechaReg = fecha_NumSumarDias($iFecha, 761);
							$iEstadoFin = 3; //Ya pasaron 2 años...
						}
					}
					if ($fila01['core01idestado'] != $iEstadoFin) {
						list($sErrorM, $sDebugM) = f2222_CambiaEstado($fila01['core01id'], $fila01['core01idestado'], $iEstadoFin, 0, $core22anotacion, $objDB, $iFechaReg, $bDebug);
					}
					break;
			}
		}
	}
	return array($iFecha, $sDebug);
}
function f2201_ImportarHistorico($core01id, $objDB, $objDBRyC = NULL, $bDebug = false)
{
	$sError = '';
	$sDebug = '';
	$bTraeRyC = false;
	$sSQL = 'SELECT TB.core01idtercero, T11.unad11doc, TB.core01idprograma, TB.core01peracainicial, TB.core01idestado, 
	TB.core01idrevision, TB.core01fechaimportacion, TB.core01idimporta, TB.core01idescuela, TB.core01idplandeestudios, 
	TB.core01idzona, TB.core011idcead 
	FROM core01estprograma AS TB, unad11terceros AS T11 
	WHERE TB.core01id=' . $core01id . ' AND TB.core01idtercero=T11.unad11id';
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' <b>Se inicia el historico</b> Cargando registro del estudiante: ' . $sSQL . '<br>';
	}
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$core01idtercero = $fila['core01idtercero'];
		$unad11doc = $fila['unad11doc'];
		$core01idplandeestudios = $fila['core01idplandeestudios'];
		$core01idescuela = $fila['core01idescuela'];
		$core01idprograma = $fila['core01idprograma'];
		$core01peracainicial = $fila['core01peracainicial'];
		$core01idestado = $fila['core01idestado'];
		$core01idrevision = $fila['core01idrevision'];
		$core01fechaimportacion = $fila['core01fechaimportacion'];
		$core01idimporta = $fila['core01idimporta'];
		$core01idzona = $fila['core01idzona'];
		$core011idcead = $fila['core011idcead'];
	} else {
		$sError = 'No se ha encontrado el plan de estudios Ref: ' . $core01id . '';
	}
	if ($sError == '') {
		if ($objDBRyC == NULL) {
			list($objDBRyC, $sDebugC) = TraerDBRyCV2($bDebug);
			$sDebug = $sDebug . $sDebugC;
			if ($objDBRyC == NULL) {
				$sError = 'No ha sido posible establecer conexi&oacute;n con EDUNAT.';
			} else {
				$bTraeRyC = true;
			}
		}
	}
	if ($sError == '') {
		list($idContTercero, $sError) = f1011_BloqueTercero($core01idtercero, $objDB);
		$iPeriodoBase = 0;
		$iFechaMatricula = 0;
	}
	if ($sError == '') {
		if ($core01idprograma == 450) {
			$sError = 'No se permite importar el programa';
		}
	}
	if ($sError == '') {
		$iPeriodoBase = 0;
		$sIdsPrograma = $core01idprograma;
		switch ($core01idprograma) {
			case 440:
				$sIdsPrograma = '1,2,3,4,5,6';
				break;
		}
	}
	if ($sError == '') {
		//Ahora comprobamos que no tengamos pequeños defectos en las importaciones previas.
		$sSQL = 'SELECT core16id, core16peraca 
		FROM core16actamatricula 
		WHERE core16tercero=' . $core01idtercero . ' AND core16peraca>760 AND core16idprograma=' . $core01idprograma . ' AND core16fechamatricula=0';
		$tabla16 = $objDB->ejecutasql($sSQL);
		while ($fila16 = $objDB->sf($tabla16)) {
			$sSQL = 'SELECT fecha_Matricula 
			FROM WSCaracterizacionTEMP
			WHERE codigo=' . $unad11doc . ' AND c_programas IN (' . $sIdsPrograma . ') AND periodo=' . $fila16['core16peraca'] . '';
			$tablar = $objDBRyC->ejecutasql($sSQL);
			if ($objDBRyC->nf($tablar) > 0) {
				$filar = $objDBRyC->sf($tablar);
				$iFechaMatricula = $filar['fecha_Matricula'];
				$sSQL = 'UPDATE core16actamatricula SET core16fechamatricula=' . $iFechaMatricula . ' WHERE core16id=' . $fila16['core16id'] . '';
				$result = $objDB->ejecutasql($sSQL);
			}
		}
	}
	if ($sError == '') {
		$sSQL = 'SELECT periodo, fecha_Matricula 
		FROM WSCaracterizacionTEMP
		WHERE codigo=' . $unad11doc . ' AND c_programas IN (' . $sIdsPrograma . ') AND periodo<761
		GROUP BY periodo, fecha_Matricula
		ORDER BY periodo';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' <b>Matricula registrada</b>: ' . $sSQL . '<br>';
		}
		$tabla16 = $objDBRyC->ejecutasql($sSQL);
		while ($fila16 = $objDBRyC->sf($tabla16)) {
			if ($iPeriodoBase == 0) {
				$iPeriodoBase = $fila16['periodo'];
				$iFechaMatricula = $fila16['fecha_Matricula'];
			}
			//Vamos a importar cada periodo, si ya existe no será problema.
			list($sErrorM, $sDebugS) = f2216_TraerMatricula($fila16['periodo'], $unad11doc, $objDB, $bDebug, $core01idprograma);
			$sDebug = $sDebug . $sDebugS;
		}
		if ($iPeriodoBase == 0) {
			$sSQL = 'SELECT periodo, fecha_Matricula 
			FROM WSCaracterizacionTEMP
			WHERE codigo=' . $unad11doc . ' AND c_programas IN (' . $sIdsPrograma . ')
			GROUP BY periodo, fecha_Matricula
			ORDER BY periodo LIMIT 0, 1';
			$tabla16 = $objDBRyC->ejecutasql($sSQL);
			while ($fila16 = $objDBRyC->sf($tabla16)) {
				$iPeriodoBase = $fila16['periodo'];
				$iFechaMatricula = $fila16['fecha_Matricula'];
			}
		}
		if ($iPeriodoBase != 0) {
			$sCambia = '';
			if ($core01peracainicial == 0) {
				$core01peracainicial = 951;
			}
			if ($core01peracainicial > $iPeriodoBase) {
				if ($sCambia != '') {
					$sCambia = $sCambia . ', ';
				}
				$sCambia = 'core01peracainicial=' . $iPeriodoBase . ', core01fechainicio=' . $iFechaMatricula . '';
			} else {
				if ($core01peracainicial == 87) {
					if ($sCambia != '') {
						$sCambia = $sCambia . ', ';
					}
					$sCambia = 'core01peracainicial=' . $iPeriodoBase . ', core01fechainicio=' . $iFechaMatricula . '';
				}
			}
			if ($core01idestado == 10) {
				if ($core01idrevision == 0) {
					if ($sCambia != '') {
						$sCambia = $sCambia . ', ';
					}
					$sCambia = $sCambia . 'core01idrevision=356770';
				}
			}
			if ($core01fechaimportacion == 0) {
				if ($sCambia != '') {
					$sCambia = $sCambia . ', ';
				}
				if (isset($_SESSION['unad_id_tercero']) == 0) {
					$_SESSION['unad_id_tercero'] = 0;
				}
				$core01idimporta = $_SESSION['unad_id_tercero'];
				if ((int)$core01idimporta == 0) {
					$core01idimporta = 356770;
				}
				$sCambia = $sCambia . 'core01fechaimportacion=' . fecha_DiaMod() . ', core01idimporta=' . $core01idimporta . '';
			}
			if ($sCambia != '') {
				$sSQL = 'UPDATE core01estprograma SET ' . $sCambia . ' WHERE core01id=' . $core01id . '';
				$result = $objDB->ejecutasql($sSQL);
				if ($bDebug) {
					$sDebug = $sDebug . fecha_microtiempo() . ' <b>Ajustando los parametros inciales</b>: ' . $sSQL . '<br>';
				}
			}
		}
	} else {
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' <b>NO SE IMPORTA NADA</b>: ' . $sError . '<br>';
		}
	}
	//Ahora si llega a estar en estado de admitido o candidato ver que tenga actas de matricula para pasarlo a activo.
	if ($sError == '') {
		switch($core01idestado) {
			case -2: //Es aspirante
			case -1: // Es admitido.
				$sSQL = 'SELECT 1 FROM core16actamatricula WHERE core16tercero=' . $core01idtercero . ' AND core16idprograma=' . $core01idprograma . '';
				$result = $objDB->ejecutasql($sSQL);
				if ($bDebug) {
					$sDebug = $sDebug . fecha_microtiempo() . ' <b>Verificando si tiene matricula en el programa.</b>: ' . $sSQL . '<br>';
				}
				$tabla16 = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($tabla16) > 0) {
					$core01idestado = 0;
					$sSQL = 'UPDATE core01estprograma SET core01idestado=' . $core01idestado . ' WHERE core01id=' . $core01id . '';
					$result = $objDB->ejecutasql($sSQL);
					if ($bDebug) {
						$sDebug = $sDebug . fecha_microtiempo() . ' <b>Ajustando el estado del PEI</b>: ' . $sSQL . '<br>';
					}
				}
				break;
		}
	} else {
		$sDebug = $sDebug . 'Error : ' . $sError . '<br>';
	}
	if ($sError == '') {
		list($sError, $sDebugH) = f2201_ImportarHomologaciones($core01id, $objDB, $objDBRyC, $bDebug);
		$sDebug = $sDebug . $sDebugH;
	}
	if ($sError == '') {
		$objNoConformidad = new clsT203(2216);
		//Ahora que ya importo vamos a procesar todo lo que no este procesado
		$sSQL = 'SELECT core16id FROM core16actamatricula WHERE core16tercero=' . $core01idtercero . ' AND core16peraca<761 AND core16idprograma=' . $core01idprograma . '';
		$tabla16 = $objDB->ejecutasql($sSQL);
		while ($fila16 = $objDB->sf($tabla16)) {
			list($core16procesado, $core16proccarac, $core16procagenda, $core16numcursos, $sErrorp, $sDebugP) = f2216_Procesar($fila16['core16id'], $objDB, $bDebug, 0);
			$sDebug = $sDebug . $sDebugP;
			//Ahora la matricula
			list($core16procesado, $core16numcursos, $sErrorP, $sDebugP) = f2216_ProcesarMatricula($fila16['core16id'], $objDB, $objNoConformidad, $bDebug);
			$sDebug = $sDebug . $sDebugP;
			//Ahora la agenda
			list($core16procagenda, $sErrorP, $sDebugP) = f2216_ProcesarAgenda($fila16['core16id'], $objDB, $bDebug, $objDBRyC, true);
			$sDebug = $sDebug . $sDebugP;
			//Ahora que le monte los promedios
			list($core16aplicada, $sError, $sDebugP) = f2216_AplicarPromedios($fila16['core16id'], $objDB, $bDebug);
			$sDebug = $sDebug . $sDebugP;
		}
	}
	if ($sError == '') {
		//Ahora si a alimentar los planes de estudio...
		//A nivel estrategia lo mejor es hacerlo curso a curso y que luego un proceso invoque todo el listado.
		//El proceso se deja en la libcore.php
		$sIds40 = '-99';
		$sSQL = 'SELECT TB.core04id, TB.core04idcurso
		FROM core04matricula_' . $idContTercero . ' AS TB
		WHERE TB.core04tercero=' . $core01idtercero . ' AND TB.core04peraca<761  AND TB.core04estado=8 AND core04resdef>=core04est_aprob';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' <b>Cursos aprobados</b>: ' . $sSQL . '<br>';
		}
		$tabla = $objDB->ejecutasql($sSQL);
		while ($fila = $objDB->sf($tabla)) {
			$sIds40 = $sIds40 . ',' . $fila['core04idcurso'];
		}
		//Ahora si consultamos cuales de estos estaran pendientes de ser registrados en el plan de estudios.
		/*
		Estados que pueden tener los cursos.
		0	Disponible	0	FF6600
		1	Pendiente de Prerequisito	1	FF0000
		2	Solicitado en Homologación	2	FF6600
		3	En Estudio de Homologación	3	FF6600
		4	Homologación Aprobada	4	FF6600
		5	Homologado	5	000033
		6	Matriculado	1	FF6600
		7	Aprobado	7	000033
		8	Requisto Cumplido	8	000033
		9	Excludio	9	000033
		12	Solicitado en Suficiencia	2	FF6600
		13	En Estudio de Suficiencia	3	FF6600
		14	Suficiencia Aprobada	4	FF6600
		15	Aprobado por suficiencia	5	000033
		*/
		$sSQL = 'SELECT TB.core03id, TB.core03estado 
		FROM core03plandeestudios_' . $idContTercero . ' AS TB
		WHERE TB.core03idestprograma=' . $core01id . ' AND TB.core03idcurso IN (' . $sIds40 . ') AND TB.core03estado IN (0,1,6)';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' <b>Plan de estudio a ser construido</b>: ' . $sSQL . '<br>';
		}
		$tabla = $objDB->ejecutasql($sSQL);
		while ($fila = $objDB->sf($tabla)) {
			list($sErrorL, $sDebugL) = f2203_ArmarFilaPlan($idContTercero, $fila['core03id'], $objDB, $bDebug);
		}
		//Totalizar el plan.
		list($core01avanceplanest, $sErrorP, $sDebugP) = f2203_TotalizarPlanV2($core01id, $objDB, $bDebug, $idContTercero);
		$sDebug = $sDebug . $sDebugP;
		list($sDebugG) = f2201_ActivarParaTrabajoGrado($core01id, $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugG;
	}
	if ($bTraeRyC) {
		if ($objDBRyC != NULL) {
			$objDBRyC->CerrarConexion();
		}
	}
	return array($sError, $sDebug);
}
function f2201_ImportarHomologaciones($core01id, $objDB, $objDBRyC = NULL, $bDebug = false)
{
	$sError = '';
	$sDebug = '';
	// Datos previos
	$bTraeRyC = false;
	$sSQL = 'SELECT TB.core01idtercero, T11.unad11doc, TB.core01idprograma, 
	TB.core01idescuela, TB.core01idplandeestudios, TB.core01idzona, TB.core011idcead 
	FROM core01estprograma AS TB, unad11terceros AS T11 
	WHERE TB.core01id=' . $core01id . ' AND TB.core01idtercero=T11.unad11id';
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' <b>Se inicia el historico</b> Cargando registro del estudiante: ' . $sSQL . '<br>';
	}
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$core01idtercero = $fila['core01idtercero'];
		$unad11doc = $fila['unad11doc'];
		$core01idplandeestudios = $fila['core01idplandeestudios'];
		$core01idescuela = $fila['core01idescuela'];
		$core01idprograma = $fila['core01idprograma'];
		$core01idzona = $fila['core01idzona'];
		$core011idcead = $fila['core011idcead'];
	} else {
		$sError = 'No se ha encontrado el plan de estudios Ref: ' . $core01id . '';
	}
	if ($sError == '') {
		if ($objDBRyC == NULL) {
			list($objDBRyC, $sDebugC) = TraerDBRyCV2($bDebug);
			$sDebug = $sDebug . $sDebugC;
			if ($objDBRyC == NULL) {
				$sError = 'No ha sido posible establecer conexi&oacute;n con EDUNAT.';
			} else {
				$bTraeRyC = true;
			}
		}
	}
	if ($sError == '') {
		// Julio 25 de 2022 - Ahora vamos a importar las homologaciones...
		$sSQL = 'SELECT YEAR(fecha_cargue) AS Agno, MONTH(fecha_cargue) AS Mes, DAY(fecha_cargue) AS Dia
		FROM homologaciones
		WHERE codigo_estudiante=' . $unad11doc . '
		GROUP BY YEAR(fecha_cargue), MONTH(fecha_cargue), DAY(fecha_cargue)';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' <b>IMPORTANDO HOMOLOGACIONES</b> Revisando historico (RCONT): ' . $sSQL . '<br>';
		}
		$tabla71b = $objDBRyC->ejecutasql($sSQL);
		while ($fila71b = $objDBRyC->sf($tabla71b)) {
			$iAgnoH = $fila71b['Agno'];
			$iMesH = $fila71b['Mes'];
			$iDiaH = $fila71b['Dia'];
			$iDiaImporta = ($iAgnoH * 10000) + ($iMesH * 100) + $iDiaH;
			//Verificar que no este importado ya.
			$bImportarHomol = false;
			if ($iAgnoH < 3000) {
				$sSQL = 'SELECT core71id 
				FROM core71homolsolicitud 
				WHERE core71idclasehomol=1 AND core71idtipohomol=-2 AND core71idestudiante=' . $core01idtercero . ' AND core71fechaaplicado=' . $iDiaImporta . '';
					if ($bDebug) {
						$sDebug = $sDebug . fecha_microtiempo() . ' <b>IMPORTANDO HOMOLOGACIONES</b> Verificando registro: ' . $sSQL . '<br>';
					}
				$tabla71 = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($tabla71) == 0) {
					$bImportarHomol = true;
				}
			}
			if ($bImportarHomol) {
				// Preparar los datos
				$core71consec = tabla_consecutivo('core71homolsolicitud', 'core71consec', 'core71agno=' . $iAgnoH . ' AND core71idclasehomol=1 AND core71idescuela=' . $core01idescuela . '', $objDB);
				$core71id = tabla_consecutivo('core71homolsolicitud', 'core71id', '', $objDB);
				$core73id = tabla_consecutivo('core73homolcurso', 'core73id', '', $objDB);
				$core71estado = 16;
				$core71detalle = 'Importado de RCONT';
				$core71extidies = 0;
				$core71extidprogramaies = 0;
				$core71extsnies = '';
				$core71numrecibopagoest = '';
				$core71idresponsableestudio = 356770;
				$core71costohomologaciones = 0;
				$core71vrpagado = 0;
				$cire71idacuerdoescuela = 0;
				$core71numdias = 0;
				$sSQL = 'SELECT codigo_curso, nota
				FROM homologaciones
				WHERE codigo_estudiante=' . $unad11doc . ' AND YEAR(fecha_cargue)=' . $iAgnoH . ' AND MONTH(fecha_cargue)=' . $iMesH . ' AND DAY(fecha_cargue)=' . $iDiaH . '';
				if ($bDebug) {
					$sDebug = $sDebug . fecha_microtiempo() . ' <b>IMPORTANDO HOMOLOGACIONES</b> Curso homologados (RCONT): ' . $sSQL . '<br>';
				}
				$tabla73b = $objDBRyC->ejecutasql($sSQL);
				$core71numcursosestudio = $objDBRyC->nf($tabla73b);
				$core71numcursoshomologa = $core71numcursosestudio;
				// Ahora si insertar.
				$sCampos2271 = 'core71agno, core71idclasehomol, core71idescuela, core71consec, core71id, 
				core71idtipohomol, core71idestudiante, core71idprograma, core71idplanest, core71estado, 
				core71fechasolicitud, core71detalle, core71extidies, core71extidprogramaies, core71extsnies, 
				core71numrecibopagoest, core71fecharadicado, core71numcursosestudio, core71numcursoshomologa, core71idresponsableestudio, 
				core71fechafinestudio, core71costohomologaciones, core71vrpagado, core71fechaaplicado, cire71idacuerdoescuela, 
				core71idzona, core71idcentro, core71idestprog, core71idsolicita, core71idcierra, 
				core71numdias';
				$sCampos2273 = 'core73idsolicitudhomol, core73idcurso, core73id, core73idestadoestudio, core73idresponsable, 
				core73fechainicio, core73fechafin, core73iddoccontenidos, core73idorigendoc, core73idarchivodoc, 
				core73costohomol, core73notahomol, core73detalle, core73formacalificacion, core73notaorigen, 
				core73idcursoorigen, core73idcursoorigen2';
				$sValores2271 = '' . $iAgnoH . ', 1, ' . $core01idescuela . ', ' . $core71consec . ', ' . $core71id . ', 
				-2, ' . $core01idtercero . ', ' . $core01idprograma . ', ' . $core01idplandeestudios . ', ' . $core71estado . ', 
				' . $iDiaImporta . ', "' . $core71detalle . '", ' . $core71extidies . ', ' . $core71extidprogramaies . ', "' . $core71extsnies . '", 
				"' . $core71numrecibopagoest . '", ' . $iDiaImporta . ', ' . $core71numcursosestudio . ', ' . $core71numcursoshomologa . ', ' . $core71idresponsableestudio . ', 
				' . $iDiaImporta . ', ' . $core71costohomologaciones . ', ' . $core71vrpagado . ', ' . $iDiaImporta . ', ' . $cire71idacuerdoescuela . ', 
				' . $core01idzona . ', ' . $core011idcead . ', ' . $core01id . ', ' . $core01idtercero . ', ' . $core71idresponsableestudio . ', 
				' . $core71numdias . '';
				$sSQL = 'INSERT INTO core71homolsolicitud (' . $sCampos2271 . ') VALUES (' . $sValores2271 . ');';
				if ($bDebug) {
					$sDebug = $sDebug . fecha_microtiempo() . ' <b>IMPORTANDO HOMOLOGACIONES</b> Insertando Encabezado: ' . $sSQL . '<br>';
				}
				$result = $objDB->ejecutasql($sSQL);
				// Ahora si insertar.
				while ($fila73b = $objDBRyC->sf($tabla73b)) {
					$core73idcurso = $fila73b['codigo_curso'];
					$core73notahomol = $fila73b['nota'];
					$core73idestadoestudio = 7;
					$core73iddoccontenidos = 0;
					$core73idorigendoc = 0;
					$core73idarchivodoc = 0;
					$core73costohomol = 0;
					$core73detalle = '';
					$core73formacalificacion = 5;
					$core73notaorigen = 0;
					$core73idcursoorigen = 0;
					$core73idcursoorigen2 = 0;
					$sValores2273 = '' . $core71id . ', ' . $core73idcurso . ', ' . $core73id . ', ' . $core73idestadoestudio . ', ' . $core71idresponsableestudio . ', 
					' . $iDiaImporta . ', ' . $iDiaImporta . ', ' . $core73iddoccontenidos . ', ' . $core73idorigendoc . ', ' . $core73idarchivodoc . ', 
					' . $core73costohomol . ', ' . $core73notahomol . ', "' . $core73detalle . '", ' . $core73formacalificacion . ', ' . $core73notaorigen . ', 
					' . $core73idcursoorigen . ', ' . $core73idcursoorigen2 . '';
					$sSQL = 'INSERT INTO core73homolcurso (' . $sCampos2273 . ') VALUES (' . $sValores2273 . ');';
					if ($bDebug) {
						$sDebug = $sDebug . fecha_microtiempo() . ' <b>IMPORTANDO HOMOLOGACIONES</b> Insertando curso: ' . $sSQL . '<br>';
					}
					$result = $objDB->ejecutasql($sSQL);
					$core73id++;
				}
			}
		}
		//Terminar las homologaciones
	}
	if ($bTraeRyC) {
		if ($objDBRyC != NULL) {
			$objDBRyC->CerrarConexion();
		}
	}
	return array($sError, $sDebug);
}
function f2201_Inactivar($objDB, $bDebug = false)
{
	$sError = '';
	$sDebug = '';
	$iHoy = fecha_DiaMod();
	$iFechaBase = fecha_ArmarNumero(fecha_dia(), fecha_mes(), fecha_agno() - 2);
	// TB.core01idtercero, T11.unad11doc, TB.core01idprograma, TB.core01fechaultmatricula
	//, unad11terceros AS T11   AND TB.core01idtercero=T11.unad11id
	$sSQL = 'SELECT TB.core01id, TB.core01idestado 
	FROM core01estprograma AS TB
	WHERE TB.core01idestado IN (0,2) AND TB.core01fechaultmatricula<' . $iFechaBase . '
	LIMIT 0, 2000';
	$tabla = $objDB->ejecutasql($sSQL);
	$iTotalFase1 = $objDB->nf($tabla);
	while ($fila = $objDB->sf($tabla)) {
		//Basicamente confirmamos la fecha de la ultima matricula antes de hacer el cambio de estado.
		list($iFecha, $sDebug) = f2201_FechaUltimaMatricula($fila['core01id'], $objDB, $bDebug);
		if ($iFecha < $iFechaBase) {
			//Aplicamos el cambio de estado
			if ($iFecha == 0) {
				$iFechaReg = $iHoy;
			} else {
				$iFechaReg = fecha_NumSumarDias($iFecha, 731);
			}
			$core22anotacion = 'No se ha detectado matricula.';
			list($sErrorM, $sDebugM) = f2222_CambiaEstado($fila['core01id'], $fila['core01idestado'], 3, 0, $core22anotacion, $objDB, $iFechaReg, $bDebug);
		}
	}
	if ($iTotalFase1 < 2000) {
		$iFechaBase = fecha_ArmarNumero(fecha_dia(), fecha_mes(), fecha_agno() - 1);
		$sSQL = 'SELECT TB.core01id, TB.core01idestado 
		FROM core01estprograma AS TB
		WHERE TB.core01idestado=0 AND TB.core01fechaultmatricula<' . $iFechaBase . '
		LIMIT 0, 2000';
		$tabla = $objDB->ejecutasql($sSQL);
		while ($fila = $objDB->sf($tabla)) {
			//Basicamente confirmamos la fecha de la ultima matricula antes de hacer el cambio de estado.
			list($iFecha, $sDebug) = f2201_FechaUltimaMatricula($fila['core01id'], $objDB, $bDebug);
			if ($iFecha < $iFechaBase) {
				//Aplicamos el cambio de estado
				if ($iFecha == 0) {
					$iFechaReg = $iHoy;
				} else {
					$iFechaReg = fecha_NumSumarDias($iFecha, 366);
				}
				$core22anotacion = 'No se ha detectado matricula.';
				list($sErrorM, $sDebugM) = f2222_CambiaEstado($fila['core01id'], $fila['core01idestado'], 2, 0, $core22anotacion, $objDB, $iFechaReg, $bDebug);
			}
		}
	}
	return array($sError, $sDebug);
}
function f2201_IniciarEstudiante($idEstudiante, $idPrograma, $idPlan, $objDB, $bDebug = false, $bCentro = 0)
{
	$sError = '';
	$sDebug = '';
	$core01id = 0;
	//core01idplandeestudios
	switch ($idPrograma) {
		case 450:
			$sError = 'No se permite alumnos en el programa cursos libres.';
			break;
		case 1:
		case 2:
		case 3:
		case 4:
		case 5:
		case 6:
			$idPrograma = 440;
			break;
	}
	if ($sError == '') {
		$sSQL = 'SELECT core01id FROM core01estprograma WHERE core01idtercero=' . $idEstudiante . ' AND core01idprograma=' . $idPrograma . '';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' Registro de estudiante ' . $sSQL . '<br>';
		}
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) == 0) {
			//No esta el registro, agregarlo.
			$core01idplandeestudios = 0;
			$core01numcredbasicos = 0;
			$core01numcredespecificos = 0;
			$core01numcredelectivos = 0;
			$core01idestado = 0;
			$core01numcredbasicosaprob = 0;
			$core01numcredespecificosaprob = 0;
			$core01numcredelectivosaprob = 0;
			$core01notaminima = 3;
			$core01notamaxima = 5;
			$core01fechafinaliza = 0;
			$core01peracafinal = 0;
			$core16peraca = 87;
			$core01idzona = 0;
			$core011idcead = 0;
			$core01fechainicio = 0;
			$core01fechaultmatricula = 0;
			//Miramos la version del programa.
			if ($idPlan == 0) {
				$sSQL = 'SELECT TB.core10id, TB.core10numcredbasicos, TB.core10numcredespecificos, TB.core10numcredelectivos, T9.core09idescuela 
				FROM core10programaversion AS TB, core09programa AS T9 
				WHERE TB.core10idprograma=' . $idPrograma . ' AND TB.core10estado="S" AND TB.core10idprograma=T9.core09id 
				ORDER BY TB.core10fechavence DESC';
			} else {
				$sSQL = 'SELECT core10id, core10numcredbasicos, core10numcredespecificos, core10numcredelectivos FROM core10programaversion WHERE core10id=' . $idPlan . '';
				$sSQL = 'SELECT TB.core10id, TB.core10numcredbasicos, TB.core10numcredespecificos, TB.core10numcredelectivos, T9.core09idescuela 
				FROM core10programaversion AS TB, core09programa AS T9 
				WHERE TB.core10id=' . $idPlan . ' AND TB.core10estado="S" AND TB.core10idprograma=T9.core09id 
				ORDER BY TB.core10fechavence DESC';
			}
			$tabla = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla) > 0) {
				$fila = $objDB->sf($tabla);
				$core01idplandeestudios = $fila['core10id'];
				$core01numcredbasicos = $fila['core10numcredbasicos'];
				$core01numcredespecificos = $fila['core10numcredespecificos'];
				$core01numcredelectivos = $fila['core10numcredelectivos'];
				$core01idescuela = $fila['core09idescuela'];
			} else {
				//No se encontro el plan de estudios... grave la cosa...
				$sSQL = 'SELECT T9.core09idescuela 
				FROM core09programa AS T9 
				WHERE T9.core09id=' . $idPrograma . '';
				$tabla = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($tabla) > 0) {
					$fila = $objDB->sf($tabla);
					$core01idescuela = $fila['core09idescuela'];
				}
			}
			//Ahora la información de matricula
			$bPrimerMatricula = true;
			$sSQL = 'SELECT core16peraca, core16fechamatricula, core16fecharecibido, core16id, core16idcead, core16idzona  
			FROM core16actamatricula 
			WHERE core16tercero=' . $idEstudiante . ' AND core16idprograma=' . $idPrograma . '
			ORDER BY core16peraca';
			$tabla = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla) > 0) {
				$fila = $objDB->sf($tabla);
				if ($bPrimerMatricula) {
					$core16peraca = $fila['core16peraca'];
					$core01idzona = $fila['core16idzona'];
					$core011idcead = $fila['core16idcead'];
					if ($fila['core16fechamatricula'] != 0) {
						$core01fechainicio = $fila['core16fechamatricula'];
					} else {
						$core01fechainicio = $fila['core16fecharecibido'];
					}
					$bPrimerMatricula = false;
				}
				if ($fila['core16fechamatricula'] != 0) {
					$core01fechaultmatricula = $fila['core16fechamatricula'];
				} else {
					$core01fechaultmatricula = $fila['core16fecharecibido'];
				}
			} else {
				$core01idestado = -1;
				$core01fechainicio = fecha_DiaMod();
				//No hay matricula... podria venir un centro marcado...
				if ($bCentro != 0) {
					$sSQL = 'SELECT unad24id, unad24idzona FROM unad24sede WHERE unad24id=' . $bCentro . '';
					$tabla = $objDB->ejecutasql($sSQL);
					if ($objDB->nf($tabla) > 0) {
						$fila = $objDB->sf($tabla);
						$core01idzona = $fila['unad24idzona'];
						$core011idcead = $fila['unad24id'];
					}
				}
			}
			$sCampos2202 = 'core01idtercero, core01idprograma, core01id, core01idescuela, core01idzona, 
			core011idcead, core01fechainicio, core01peracainicial, core01fechaultmatricula, core01idplandeestudios, 
			core01numcredbasicos, core01numcredespecificos, core01numcredelectivos, core01idestado, core01numcredbasicosaprob, 
			core01numcredespecificosaprob, core01numcredelectivosaprob, core01notaminima, core01notamaxima, core01fechafinaliza, 
			core01peracafinal';
			$core01id = tabla_consecutivo('core01estprograma', 'core01id', '', $objDB);
			$sValores2202 = '' . $idEstudiante . ', ' . $idPrograma . ', ' . $core01id . ', ' . $core01idescuela . ', ' . $core01idzona . ', 
			' . $core011idcead . ', ' . $core01fechainicio . ', ' . $core16peraca . ', ' . $core01fechaultmatricula . ', ' . $core01idplandeestudios . ', 
			' . $core01numcredbasicos . ', ' . $core01numcredespecificos . ', ' . $core01numcredelectivos . ', ' . $core01idestado . ', ' . $core01numcredbasicosaprob . ', 
			' . $core01numcredespecificosaprob . ', ' . $core01numcredelectivosaprob . ', ' . $core01notaminima . ', ' . $core01notamaxima . ', ' . $core01fechafinaliza . ', 
			' . $core01peracafinal . '';
			$sSQL = 'INSERT INTO core01estprograma (' . $sCampos2202 . ') VALUES (' . $sValores2202 . ');';
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . ' Insertando el estudiante ' . $sSQL . '<br>';
			}
			$result = $objDB->ejecutasql($sSQL);
		} else {
			$fila = $objDB->sf($tabla);
			$core01id = $fila['core01id'];
		}
	}
	return array($core01id, $sError, $sDebug);
}
function f2201_PlanesEstudioXEstudiante($idEstudiante, $objDB, $bDebug = false)
{
	$sIds = '-99';
	$sDebug = '';
	//Noviembre 3 de 2021 - Se agrega el estado -1 admitido por el tema de la homologaciones.
	$sSQL = 'SELECT core01idplandeestudios 
	FROM core01estprograma 
	WHERE core01idtercero=' . $idEstudiante . ' AND core01idestado IN (-1, 0, 2, 3, 5) AND core01idplandeestudios>0';
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' <br>';
	}
	$tabla = $objDB->ejecutasql($sSQL);
	while ($fila = $objDB->sf($tabla)) {
		$sIds = $sIds . ',' . $fila['core01idplandeestudios'];
	}
	return array($sIds, $sDebug);
}
//Prueba de estado
function f2201_PruebaEstado($core01id, $objDB, $bDebug = true)
{
	$sRes = '';
	$sDebug = '';
	$sSQL = 'SELECT core01idpruebaestado FROM core01estprograma WHERE core01id=' . $core01id . '';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$core01idpruebaestado = $fila['core01idpruebaestado'];
		if ($core01idpruebaestado > 0) {
			$sSQL = 'SELECT TB.corf48numreg, TB.corf48puntaje, T49.corf49nombre 
			FROM corf48pruebasaber AS TB, corf49tipoprueba AS T49 
			WHERE TB.corf48id=' . $core01idpruebaestado . ' AND TB.corf48idtipoprueba=T49.corf49id';
			$tabla = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla) > 0) {
				$fila = $objDB->sf($tabla);
				$sRes = '<b>' . $fila['corf49nombre'] . '</b> N&deg; de registro <b>' . $fila['corf48numreg'] . '</b> Puntaje <b>' . $fila['corf48puntaje'] . '</b>';
			}
		}
	}
	return array($sRes, $sDebug);
}
//Revisar el estado de la practica
function f2201_RevisarHistoricoPractica($id01, $objDB, $bDebug = false)
{
	$sError = '';
	$sDebug = '';
	$sSQL = 'SELECT TB.core01idtercero, TB.core01idprograma, TB.core01idtipopractica, TB.core01estadopractica, T11.unad11idtablero, TB.core01idplandeestudios 
	FROM core01estprograma AS TB, unad11terceros AS T11 
	WHERE TB.core01id=' . $id01 . ' AND TB.core01idtercero=T11.unad11id ';
	$tabla01 = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla01) > 0) {
		$fila01 = $objDB->sf($tabla01);
		$core01idtercero = $fila01['core01idtercero'];
		$core01idprograma = $fila01['core01idprograma'];
		$olab45idtipopractica = $fila01['core01idtipopractica'];
		if ($olab45idtipopractica < 1) {
			$sError = 'El estudiante no necesita hacer practicas.';
		} else {
			switch ($fila01['core01estadopractica']) {
				case 7:
					$sError = 'La practica ya se encuentra ejecutada.';
					break;
			}
		}
	} else {
		$sError = 'No ha sido encontrado el registro del estudiante [Ref: ' . $id01 . ']';
	}
	if ($sError == '') {
		$olab45idaprueba = 0;
		$olab45fechainicio = 0;
		$olab45fechafin = 0;
		$olab45idtutor = 0;
		$olab45idmonitor = 0;
		$olab45idgrupo = 0;
		$olab45idcupopractica = 0;
		$olab45idcalifica = 0;
		$olab45aprobada = 'S';
		$olab45idescenario = 0;
		$bTienePendientes = false;
		$iNumCursos = 0;
		$sSQL = 'SELECT TB.core03idcurso, TB.core03peracaaprueba, TB.core03notafinal, TB.core03estado, TB.core03fechanota75   
		FROM core03plandeestudios_1 AS TB, unad40curso AS T40
		WHERE TB.core03idestprograma=' . $id01 . ' AND TB.core03estado<>9 AND TB.core03idcurso=T40.unad40id AND T40.unad40modocalifica=3';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($fila = $objDB->sf($tabla)) {
			$iNumCursos++;
			switch ($fila['core03estado']) {
				case 7: //Aprobado.
					//Lo agregamos para tener el registro
					$olab45fechaaprueba = $fila['core03fechanota75'];
					$olab45idperiodo = $fila['core03peracaaprueba'];
					$olab45idcurso = $fila['core03idcurso'];
					$olab45nota = round($fila['core03idcurso'] / 100, 1);
					$olab45fechanota = $fila['core03fechanota75'];
					$sSQL = 'SELECT TB.olab45id 
				FROM olab45practica AS TB 
				WHERE TB.olab45idestprograma=' . $id01 . ' AND TB.olab45idperiodo=' . $fila['core03peracaaprueba'] . ' AND TB.olab45idcurso=' . $fila['core03idcurso'] . '';
					$tabla45 = $objDB->ejecutasql($sSQL);
					if ($objDB->nf($tabla45) == 0) {
						//Agregar el registro
						$olab45numpractica = tabla_consecutivo('olab45practica', 'olab45numpractica', 'olab45idestprograma=' . $id01 . '', $objDB);
						$olab45id = tabla_consecutivo('olab45practica', 'olab45id', '', $objDB);
						$sCampos2145 = 'olab45idestprograma, olab45numpractica, olab45id, olab45idtipopractica, olab45fechaaprueba, 
					olab45idaprueba, olab45fechainicio, olab45fechafin, olab45idperiodo, olab45idcurso, 
					olab45idtutor, olab45idmonitor, olab45idgrupo, olab45idcupopractica, olab45nota, 
					olab45fechanota, olab45idcalifica, olab45aprobada, olab45idestudiante, olab45idprograma, 
					olab45idescenario';
						$sValores2145 = '' . $id01 . ', ' . $olab45numpractica . ', ' . $olab45id . ', ' . $olab45idtipopractica . ', "' . $olab45fechaaprueba . '", 
					"' . $olab45idaprueba . '", "' . $olab45fechainicio . '", "' . $olab45fechafin . '", ' . $olab45idperiodo . ', ' . $olab45idcurso . ', 
					"' . $olab45idtutor . '", "' . $olab45idmonitor . '", ' . $olab45idgrupo . ', ' . $olab45idcupopractica . ', ' . $olab45nota . ', 
					' . $olab45fechanota . ', "' . $olab45idcalifica . '", "' . $olab45aprobada . '", ' . $core01idtercero . ', ' . $core01idprograma . ', 
					' . $olab45idescenario . '';
						$sSQL = 'INSERT INTO olab45practica (' . $sCampos2145 . ') VALUES (' . $sValores2145 . ');';
						$result = $objDB->ejecutasql($sSQL);
					} else {
						//Actualizar de ser necesario.
						$fila45 = $objDB->sf($tabla45);
						$sCambia = '';
						if ($sCambia != '') {
						}
					}
					break;
				default:
					$bTienePendientes = true;
					break;
			}
		}
		if ($iNumCursos > 0) {
			if (!$bTienePendientes) {
				$sSQL = 'UPDATE core01estprograma SET core01estadopractica=7 WHERE core01id=' . $id01 . '';
				$result = $objDB->ejecutasql($sSQL);
			}
		}
	}
	return array($sError, $sDebug);
}
function f2201_AnalizarContinuidad($id01, $objDB, $bDebug = false)
{
	$sError = '';
	$sDebug = '';
	$aCiclos = array();
	$aCursos = array();
	$aMatriculas = array();
	$aAplazados = array();
	$aAprobados = array();
	$iNumMatriculas = 0;
	$iNumCiclos = 0;
	$core01oportuno = -1;
	$core01sem_total = 0;
	$core01oportunidad = 0;
	$iSemestresPasados = 0;
	$idCicloMin = 0;
	list($idCicloMax, $sDebugC) = f2200_CicloActual($objDB, $bDebug);
	$iResultado = 0;
	for ($k = 0; $k <= $idCicloMax; $k++) {
		$aCiclos[$k] = 0;
		$aMatriculas[$k] = 0;
		$aCursos[$k] = 0;
		$aAplazados[$k] = 0;
		$aAprobados[$k] = 0;
	}
	/*
	core01contmat1, core01contmat2, core01contmat3, core01contmat4, core01contmat5, 
	core01contmat6, core01contmat7, core01contmat8, core01contmat9, core01contmat10, 
	core01contmat11, core01contmat12, core01contmat13, core01contmat14, core01contmat15, 
	core01contmat16, core01contmat17, core01contmat18, core01contmat19, core01contmat20, 
	core01contaprob1, core01contaprob2, core01contaprob3, core01contaprob4, core01contaprob5, 
	core01contaprob6, core01contaprob7, core01contaprob8, core01contaprob9, core01contaprob10, 
	core01contaprob11, core01contaprob12, core01contaprob13, core01contaprob14, core01contaprob15, 
	core01contaprob16, core01contaprob17, core01contaprob18, core01contaprob19, core01contaprob20
	*/
	$sSQL = 'SELECT TB.core01idtercero, TB.core01idprograma, TB.core01idplandeestudios, 
	TB.core01contestado, TB.core01contmatriculas, TB.core01idestado, 
	TB.core01contciclo1, TB.core01contciclo2, TB.core01contciclo3, TB.core01contciclo4, TB.core01contciclo5, 
	TB.core01contciclo6, TB.core01contciclo7, TB.core01contciclo8, TB.core01contciclo9, TB.core01contciclo10, 
	TB.core01contciclo11, TB.core01contciclo12, TB.core01contciclo13, TB.core01contciclo14, TB.core01contciclo15, 
	TB.core01contciclo16, TB.core01contciclo17, TB.core01contciclo18, TB.core01contciclo19, TB.core01contciclo20, 
	TB.core01oportuno, TB.core01sem_proyectados, TB.core01semestrerelativo, TB.core01sem_total, TB.core01oportunidad, 
	T9.core09aplicacontinuidad, TB.core01fechafinaliza, TB.core01gradofecha, T9.core09semestres 
	FROM core01estprograma AS TB, core09programa AS T9 
	WHERE TB.core01id=' . $id01 . ' AND TB.core01idprograma=T9.core09id';
	$tabla1 = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla1) > 0) {
		$fila1 = $objDB->sf($tabla1);
		$idTercero = $fila1['core01idtercero'];
		$idPrograma = $fila1['core01idprograma'];
		$idPlan = $fila1['core01idplandeestudios'];
		$core01sem_proyectados = $fila1['core01sem_proyectados'];
		$core01semestrerelativo = $fila1['core01semestrerelativo'];
		$bAplicaCont = false;
		$iCicloTermina = 0;
		$iEstadoTermina = 80;
		$iCicloGrado = 0;
		$iCicloCambia = 0;
		if ($fila1['core09aplicacontinuidad'] == 1) {
			switch ($fila1['core01idestado']) {
				case -2: //Candidato
				case -1: //Admitido
				case 98: //Candidato Desinteresado
				case 99: //Inadmitido
					break;
				case 10: //Graduado
					//Calcular cuando se graduó
					if ($fila1['core01gradofecha'] != 0) {
						list($iCicloGrado, $sDebugC) = f217_CohorteAFecha($fila1['core01gradofecha'], $objDB);
					}
				case 7: //Graduando
					$bAplicaCont = true;
					//Calcular en que ciclo termino...
					if ($fila1['core01fechafinaliza'] != 0) {
						list($iCicloTermina, $sDebugC) = f217_CohorteAFecha($fila1['core01fechafinaliza'], $objDB);
					}
					break;
				case 11: //Hizo Transicion
				case 12: //Cambio de programa
					$bAplicaCont = true;
					if ($fila1['core01fechafinaliza'] != 0) {
						list($iCicloTermina, $sDebugC) = f217_CohorteAFecha($fila1['core01fechafinaliza'], $objDB);
						//$iCicloTermina--;
						$iEstadoTermina = 60;
					}
					break;
				default:
					$bAplicaCont = true;
					break;
			}
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . ' <b>Continuidad</b> Datos de inicio: Ciclo Termina ' . $iCicloTermina . ', Estado Termina ' . $iEstadoTermina . '<br>';
			}
		}
		if ($bAplicaCont) {
			$core01sem_total = 1;
			//Traer los datos de matricula....
			//4 de Noviembre de 2021 - Vamos a ignorar las matriculas aplazadas y canceladas.
			//26 de Noviembre de 2021 - No se pueden ignorar los aplazmientos ni cancelaciones (hacen parte del ciclo inicial)
			// AND TB.core16estado NOT IN (8, 9)
			$sSQL = 'SELECT T2.exte02idciclo, T17.unae17orden, SUM(TB.core16numcursos+TB.cara16numcursosext+TB.cara16numcursospost) AS Cursos, 
			SUM(TB.core16numaprobados) AS Aprobados, COUNT(1) AS Matriculas, SUM(TB.cara16numaplazados) AS Aplazados, SUM(TB.cara16numcancelados) AS Cancelados
			FROM core16actamatricula AS TB, exte02per_aca AS T2, unae17cicloacadem AS T17
			WHERE TB.core16tercero=' . $idTercero . ' AND TB.core16idprograma=' . $idPrograma . ' AND TB.core16estado NOT IN (99)
			AND TB.core16peraca=T2.exte02id AND T2.exte02idciclo=T17.unae17id
			GROUP BY T2.exte02idciclo, T17.unae17orden
			ORDER BY T17.unae17orden';
			/*
			//Abril 12 de 2021
			//Considerar los planes de estudio ha dejado varios datos incongruentes, entonces de momento es mejor ignorarlos
			$sSQL='SELECT T2.exte02idciclo, T17.unae17orden, SUM(TB.core16numcursos) AS Cursos, 
			SUM(TB.core16numaprobados) AS Aprobados, COUNT(1) AS Matriculas, SUM(TB.cara16numaplazados) AS Aplazados
			FROM core16actamatricula AS TB, exte02per_aca AS T2, unae17cicloacadem AS T17
			WHERE TB.core16tercero='.$idTercero.' AND TB.core16idprograma='.$idPrograma.' AND TB.core16idplanestudio IN (0,'.$idPlan.') 
			AND TB.core16peraca=T2.exte02id AND T2.exte02idciclo=T17.unae17id
			GROUP BY T2.exte02idciclo, T17.unae17orden
			ORDER BY T17.unae17orden';
			*/
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . ' <b>Continuidad</b> Datos de matricula ' . $sSQL . '<br>';
			}
			$tabla = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla) == 0) {
				//AND TB.core16estado NOT IN (8, 9)
				$sSQL = 'SELECT T2.exte02idciclo, T17.unae17orden, SUM(TB.core16numcursos+TB.cara16numcursosext+TB.cara16numcursospost) AS Cursos, 
				SUM(TB.core16numaprobados) AS Aprobados, COUNT(1) AS Matriculas, SUM(TB.cara16numaplazados) AS Aplazados, SUM(TB.cara16numcancelados) AS Cancelados
				FROM core16actamatricula AS TB, exte02per_aca AS T2, unae17cicloacadem AS T17
				WHERE TB.core16tercero=' . $idTercero . ' AND TB.core16idprograma=' . $idPrograma . ' AND TB.core16estado NOT IN (99)   
				AND TB.core16peraca=T2.exte02id AND T2.exte02idciclo=T17.unae17id
				GROUP BY T2.exte02idciclo, T17.unae17orden
				ORDER BY T17.unae17orden';
				if ($bDebug) {
					$sDebug = $sDebug . fecha_microtiempo() . ' <b>Continuidad</b> Datos de matricula [Sin plan de estudios] ' . $sSQL . '<br>';
				}
				$tabla = $objDB->ejecutasql($sSQL);
			}
			while ($fila = $objDB->sf($tabla)) {
				$idCiclo = $fila['exte02idciclo'];
				if ($idCicloMin == 0) {
					$idCicloMin = $idCiclo;
				}
				$iNumMatriculas = $iNumMatriculas + $fila['Matriculas'];
				$aCiclos[$idCiclo] = $idCiclo;
				$bNumeroCursos = $fila['Cursos'];
				$aCursos[$idCiclo] = $bNumeroCursos;
				if ($bNumeroCursos == 0) {
					$aMatriculas[$idCiclo] = 0;
					if ($idCiclo < 55) {
						//Antes de 2008 I por tanto los codigos de los cursos no son importables
						$aMatriculas[$idCiclo] = 1;
					} else {
						$aMatriculas[$idCiclo] = $fila['Aprobados'];
					}
				} else {
					$aMatriculas[$idCiclo] = $fila['Cursos'];
				}
				$aAplazados[$idCiclo] = $fila['Aplazados'];
				$aAprobados[$idCiclo] = $fila['Aprobados'];
			}
			$sData = 'core01contmatriculas=' . $idCicloMin . '';
			$idEstadoCont = 10;
			$iNumAusencias = 0;
			$iAusenciaAnterior = 0;
			$iNumReingresos = 0;
			$iNumIntermitencias = 0;
			if ($idCicloMin == 0) {
				$idCicloMin = $idCicloMax;
			}
			//26 de Noviembre de 2021 - Puede ser que el estado inicial sea aplazado o cancelado, no necesariamente en continuidad.
			if ($idCicloMin >= 55) {
				if ($aCursos[$idCicloMin] == 0) {
					//Aplazo o cancelo el primer semestre.
					$idEstadoCont = 21;
					$iNumAusencias++;
					$iAusenciaAnterior = 1;
				}
			}
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . ' <b>Continuidad</b> Ciclo base ' . $idCicloMin . ' Ciclo tope ' . $idCicloMax . '<br>';
			}
			for ($k = 1; $k < 21; $k++) {
				$iCicloAnaliza = $idCicloMin + $k;
				$bCalcularCont = true;
				$bAgregaRegistro = true;
				if ($iCicloCambia > 0) {
					if ($iCicloAnaliza > $iCicloCambia) {
						$bCalcularCont = false;
						$idEstadoCont = 60;
					}
				}
				if ($iCicloTermina > 0) {
					if ($iCicloAnaliza > $iCicloTermina) {
						$bCalcularCont = false;
						$idEstadoCont = $iEstadoTermina;
					}
				}
				if ($iCicloGrado > 0) {
					if ($iCicloAnaliza >= $iCicloGrado) {
						$bCalcularCont = false;
						$idEstadoCont = 90;
					}
				}
				if ($bCalcularCont) {
					if ($iCicloAnaliza > $idCicloMax) {
						if ($sData != '') {
							$sData = $sData . ', ';
						}
						$sData = $sData . 'core01contciclo' . $k . '=0';
						$bAgregaRegistro = false;
					} else {
						$core01sem_total++;
						$bConMatriculaActiva = false;
						if ($idCiclo < 55) {
							if ($aMatriculas[$iCicloAnaliza] > 0) {
								$bConMatriculaActiva = true;
							}
						} else {
							if ($aCursos[$iCicloAnaliza] > 0) {
								$bConMatriculaActiva = true;
							}
						}
						if ($bConMatriculaActiva) {
							//Hubo matricula permanece
							switch ($idEstadoCont) {
								case 10: //Viene en continuidad. Permanece
									break;
								case 20: //Estuvo ausente
								case 21: //Estuvo aplazado
									$idEstadoCont = 10;
									if ($iAusenciaAnterior > 1) {
										$iNumIntermitencias++;
									}
									if ($iNumReingresos > 0) {
										$idEstadoCont = 40 + $iNumReingresos;
									} else {
										if ($iNumIntermitencias > 0) {
											$idEstadoCont = 50 + $iNumIntermitencias;
										}
									}
									break;
								case 30: //Viene como desertor y matricula
									$iNumReingresos++;
									$idEstadoCont = 40 + $iNumReingresos;
									break;
							}
							$iAusenciaAnterior = 0; //Le indicamos al siguiente ciclo que hubo matricula.
						} else {
							if ($aAplazados[$iCicloAnaliza] > 0) {
								$idEstadoCont = 21;
							} else {
								//No hay matricula compadres.
								switch ($idEstadoCont) {
									case 10: //Viene en continuidad. la pierde
										$idEstadoCont = 20;
										$iNumAusencias++;
										$iAusenciaAnterior = 1;
										break;
									case 21: //Viene aplazado, pierde la continuidad (y lo castiganos)
										if ($iAusenciaAnterior > 1) {
											$idEstadoCont = 30;
										} else {
											$idEstadoCont = 20;
											$iNumAusencias++;
											$iAusenciaAnterior++;
										}
										break;
									case 20: //Ausente anterior
										if ($iAusenciaAnterior > 1) {
											$iAusenciaAnterior = 0;
											//Se abrio el hueco debe saltar
											$idEstadoCont = 30;
										} else {
											$iAusenciaAnterior++;
										}
										break;
									case 30: //Viene commo desertor y no matricula...
										break;
									case 41: //Reingresaron y no matriculan
									case 42:
									case 43:
									case 44:
									case 45:
									case 51: //Intermitentes y no matricularon
									case 52:
									case 53:
									case 54:
									case 55:
										$idEstadoCont = 20;
										$iNumAusencias++;
										$iAusenciaAnterior++;
										break;
								}
							}
						}
					}
				}else{
					//Puede estar terminando pero sin grado, por tanto hay que sumar el semestre.
					if ($iCicloTermina > 0) {
						if ($iCicloAnaliza > $iCicloTermina) {
							if ($iCicloGrado > 0) {
								if ($iCicloAnaliza < $iCicloGrado) {
									$core01sem_total++;
								}
							} else {
								if ($iCicloAnaliza <= $idCicloMax) {
									$core01sem_total++;
								}
							}
						}
					}
				}
				if ($bAgregaRegistro) {
					switch ($idEstadoCont) {
						case 60: //Cambio de programa
						case 90: //Graduado.
							break;
						default:
							$iSemestresPasados++;
							//$core01sem_total++;
							break;
					}
					if ($sData != '') {
						$sData = $sData . ', ';
					}
					//, core01contmat'.$k.'=0, core01contaprob'.$k.'=0
					$sData = $sData . 'core01contciclo' . $k . '=' . $idEstadoCont . '';
				}
			}
			//Ahora calcular la oportunidad final
			if ($core01sem_proyectados > 0){
				//La duracion total debe ser menor a los semestres proyectados mas 2
				if ($core01semestrerelativo < ($core01sem_total-2)){
					$core01oportuno = 0;
					$core01oportunidad = $core01sem_total - ($core01semestrerelativo+2);
				} else {
					$core01oportuno = 1;
					$core01oportunidad = 0;
					if ($core01semestrerelativo > $core01sem_total) {
						$core01oportunidad = $core01sem_total - $core01semestrerelativo;
					}
				}
			}
		} else {
			//No aplica..
			$sData = 'core01contmatriculas=0';
			$idEstadoCont = 100;
			for ($k = 1; $k < 21; $k++) {
				if ($sData != '') {
					$sData = $sData . ', ';
				}
				$sData = $sData . 'core01contciclo' . $k . '=' . $idEstadoCont . '';
			}
		}
		if ($sData != '') {
			$sData = $sData . ', ';
		}
		if ($idEstadoCont == 0) {
			if ($idCicloMin == $idCicloMax) {
				$idEstadoCont = 10;
			}
		}
		$sData = $sData . 'core01contestado=' . $idEstadoCont . '';
		//los datos de la oportunidad
		if ($fila1['core01oportuno'] != $core01oportuno) {
			if ($sData != '') {
				$sData = $sData . ', ';
			}
			$sData = $sData . 'core01oportuno=' . $core01oportuno . '';
		}
		if ($fila1['core01sem_total'] != $core01sem_total) {
			if ($sData != '') {
				$sData = $sData . ', ';
			}
			$sData = $sData . 'core01sem_total=' . $core01sem_total . '';
		}
		if ($fila1['core01oportunidad'] != $core01oportunidad) {
			if ($sData != '') {
				$sData = $sData . ', ';
			}
			$sData = $sData . 'core01oportunidad=' . $core01oportunidad . '';
		}
		//Ahora guardar lo que cambie.
		$iResultado = $idEstadoCont;
		if ($sData != '') {
			$sSQL = 'UPDATE core01estprograma SET ' . $sData . ' WHERE core01id=' . $id01 . '';
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . ' <b>Aplicando continuidad</b> ' . $sSQL . '<br>';
			}
			$result = $objDB->ejecutasql($sSQL);
		}
	} else {
		$sError = 'No se ha encontrado el registro ref: ' . $id01 . '';
	}
	return array($sError, $sDebug, $iResultado, $iNumMatriculas);
}
function f2201_TablaContinuidad($id01, $objDB, $bDebug = false)
{
	$sRes = '';
	$bTermina = false;
	$core09semestres = 0;
	$core01oportuno = -1;
	$iSemestresPasados = 0;
	$bGraduado = false;
	//core01idtercero, core01idprograma, core01idplandeestudios,  , core01contmatriculas, core01idestado,
	/* 
	, 
	core01contaprob1, core01contaprob2, core01contaprob3, core01contaprob4, core01contaprob5, 
	core01contaprob6, core01contaprob7, core01contaprob8, core01contaprob9, core01contaprob10, 
	core01contaprob11, core01contaprob12, core01contaprob13, core01contaprob14, core01contaprob15, 
	core01contaprob16, core01contaprob17, core01contaprob18, core01contaprob19, core01contaprob20
	*/
	$sSQL = 'SELECT core01contmatriculas, core01contestado, core01contciclo1, core01contciclo2, core01contciclo3, core01contciclo4, core01contciclo5, 
	core01contciclo6, core01contciclo7, core01contciclo8, core01contciclo9, core01contciclo10, 
	core01contciclo11, core01contciclo12, core01contciclo13, core01contciclo14, core01contciclo15, 
	core01contciclo16, core01contciclo17, core01contciclo18, core01contciclo19, core01contciclo20, 
	core01sem_proyectados, core01oportuno, core01oportunidad
	FROM core01estprograma AS TB 
	WHERE TB.core01id=' . $id01 . '';
	$tabla1 = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla1) > 0) {
		$fila = $objDB->sf($tabla1);
		$core09semestres = $fila['core01sem_proyectados'];
		if ($fila['core01contmatriculas'] != 0) {
			$iCeldas = 20;
			for ($k = 20; $k > 0; $k--) {
				if ($fila['core01contciclo' . $k] == 0) {
					$iCeldas--;
				} else {
					$k = 0;
				}
			}
			if ($iCeldas > 16) {
				$iCeldas = 20;
			} else {
				if ($iCeldas > 12) {
					$iCeldas = 16;
				} else {
					if ($iCeldas > 8) {
						$iCeldas = 12;
					} else {
						if ($iCeldas > 4) {
							$iCeldas = 8;
						} else {
							$iCeldas = 4;
						}
					}
				}
			}
			$aEstado = array();
			$aTonos = array();
			$sSQL = 'SELECT core36id, core36nombre, core36tono FROM core36estadocont';
			$tablae = $objDB->ejecutasql($sSQL);
			while ($filae = $objDB->sf($tablae)) {
				$aEstado[$filae['core36id']] = cadena_notildes($filae['core36nombre']);
				$aTonos[$filae['core36id']] = $filae['core36tono'];
			}
			$idCicloInicial = $fila['core01contmatriculas'];
			$sSQL = 'SELECT unae17id, unae17nombre FROM unae17cicloacadem WHERE unae17id>=' . $idCicloInicial . ' AND unae17id<=' . ($idCicloInicial + $iCeldas) . ' ORDER BY unae17id';
			$aCiclo = array();
			$tabla = $objDB->ejecutasql($sSQL);
			$iLinea = 0;
			while ($filac = $objDB->sf($tabla)) {
				$aCiclo[$iLinea] = $filac['unae17nombre'];
				$iLinea++;
			}
			$sRes = '<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">';
			$sRes = $sRes . '<tr><td colspan="2" width="25%"></td><td colspan="2" width="25%"></td><td colspan="2" width="25%"></td><td colspan="2" width="25%"></td></tr>';
			$sFila = '';
			$iProcesos = 0;
			$iCicloFinal = 0;
			for ($k = 1; $k <= $iCeldas; $k++) {
				$idEstadoCiclo = $fila['core01contciclo' . $k];
				$bPintaCiclo = true;
				if ($idEstadoCiclo == 0) {
					$bPintaCiclo = false;
				}
				if ($idEstadoCiclo == 80) {
					//Graduando...
					if ($iCicloFinal == 0) {
						list($iCicloFinal, $sDebugC) = f2200_CicloActual($objDB);
					}
					if ($iCicloFinal < ($idCicloInicial + $k)) {
						$bTermina = true;
					}
				}
				if ($bTermina) {
					$bPintaCiclo = false;
				}
				if (!$bPintaCiclo) {
					$sFila = '' . $sFila . '<td colspan="2"></td>';
				} else {
					$sTono = ' style="color:#FFFFFF" bgcolor="#' . $aTonos[$idEstadoCiclo] . '"';
					$sNomCiclo = '{' . $k . '}';
					if (isset($aCiclo[$k]) != 0) {
						$sNomCiclo = $aCiclo[$k];
					}
					$sFila = '' . $sFila . '<td align="center"' . $sTono . '>' . $sNomCiclo . '</td><td align="center"' . $sTono . '>' . $aEstado[$idEstadoCiclo] . '</td>';
					switch ($idEstadoCiclo) {
						case 60: //Cambio de programa
						case 90: //Graduado
							$bTermina = true;
							break;
						default:
							$iSemestresPasados++;
							break;
					}
				}
				$iProcesos++;
				if ($iProcesos > 3) {
					if ($bTermina) {
						$k = 21;
					} else {
						$sRes = $sRes . '<tr>' . $sFila . '</tr>';
						$sFila = '';
						$iProcesos = 0;
					}
				}
			}
			$sRes = $sRes . '<tr>' . $sFila . '</tr>';
			if ($core09semestres > 0) {
				$sInfoOportuno = '';
				switch ($fila['core01contestado']) {
					case 60: //Cambio de programa no lleva graduacion oportuna
						break;						
					case 90: //Graduado
						if ($fila['core01sem_proyectados'] > 0) {
							if ($fila['core01oportunidad'] > 0) {
								$sInfoOportuno = '<span class="rojo">Grado Tard&iacute;o [+' . $fila['core01oportunidad'] . ' semestres]</span>';
							} else {
								$sInfoOportuno = '<span class="verde">Grado Oportuno</span>';
							}
						}
						break;
					default:
						$sInfoOportuno = 'Pendiente an&aacute;lisis de oportunidad.';
						if ($fila['core01sem_proyectados'] > 0) {
							if ($fila['core01oportuno'] != -1){
								if ($fila['core01oportunidad'] > 0) {
									$sInfoOportuno = '<span class="rojo">Estudiante Tard&iacute;o [+' . $fila['core01oportunidad'] . ' semestres]</span>';
								} else {
									$sInfoOportuno = '<span class="verde">Estudiante en tiempo oportuno</span>';
								}
							}
						}
						break;
				}
				if ($sInfoOportuno != '') {
					$sRes = $sRes . '<tr><td colspan="8" align="center">' . $sInfoOportuno . '</td></tr>';
				}
			}
			$sRes = $sRes . '</table>';
		}
	}
	return $sRes;
}
function f2201_TablaContinuidadV0($id01, $objDB, $bDebug = false)
{
	$sRes = '';
	//core01idtercero, core01idprograma, core01idplandeestudios,  , core01contmatriculas, core01idestado,
	/* 
	, 
	core01contaprob1, core01contaprob2, core01contaprob3, core01contaprob4, core01contaprob5, 
	core01contaprob6, core01contaprob7, core01contaprob8, core01contaprob9, core01contaprob10, 
	core01contaprob11, core01contaprob12, core01contaprob13, core01contaprob14, core01contaprob15, 
	core01contaprob16, core01contaprob17, core01contaprob18, core01contaprob19, core01contaprob20
	*/
	$sSQL = 'SELECT core01contestado, core01contciclo1, core01contciclo2, core01contciclo3, core01contciclo4, core01contciclo5, 
	core01contciclo6, core01contciclo7, core01contciclo8, core01contciclo9, core01contciclo10, 
	core01contciclo11, core01contciclo12, core01contciclo13, core01contciclo14, core01contciclo15, 
	core01contciclo16, core01contciclo17, core01contciclo18, core01contciclo19, core01contciclo20, 
	core01contmat1, core01contmat2, core01contmat3, core01contmat4, core01contmat5, 
	core01contmat6, core01contmat7, core01contmat8, core01contmat9, core01contmat10, 
	core01contmat11, core01contmat12, core01contmat13, core01contmat14, core01contmat15, 
	core01contmat16, core01contmat17, core01contmat18, core01contmat19, core01contmat20
	FROM core01estprograma WHERE core01id=' . $id01 . '';
	$tabla1 = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla1) > 0) {
		$fila = $objDB->sf($tabla1);
		if ($fila['core01contestado'] != 0) {
			$iCeldas = 20;
			for ($k = 20; $k > 0; $k--) {
				if ($fila['core01contciclo' . $k] == 0) {
					$iCeldas--;
				} else {
					$k = 0;
				}
			}
			if ($iCeldas > 16) {
				$iCeldas = 20;
			} else {
				if ($iCeldas > 12) {
					$iCeldas = 16;
				} else {
					if ($iCeldas > 8) {
						$iCeldas = 8;
					} else {
						if ($iCeldas > 4) {
							$iCeldas = 8;
						} else {
							$iCeldas = 4;
						}
					}
				}
			}
			$idCicloInicial = $fila['core01contciclo1'];
			$sSQL = 'SELECT unae17id, unae17nombre FROM unae17cicloacadem WHERE unae17id>=' . $idCicloInicial . ' AND unae17id<=' . ($idCicloInicial + $iCeldas) . '';
			$aCiclo = array();
			$tabla = $objDB->ejecutasql($sSQL);
			while ($filac = $objDB->sf($tabla)) {
				$aCiclo[$filac['unae17id']] = $filac['unae17nombre'];
			}
			$sRes = '<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">';
			$sRes = $sRes . '<tr><td colspan="2" width="25%"></td><td colspan="2" width="25%"></td><td colspan="2" width="25%"></td><td colspan="2" width="25%"></td></tr>';
			$sFila = '';
			$iProcesos = 0;
			for ($k = $iCeldas; $k > 0; $k--) {
				$idCiclo = $fila['core01contciclo' . $k];
				if ($idCiclo == 0) {
					$sFila = '<td colspan="2"></td>' . $sFila;
				} else {
					$sNomCiclo = '{' . $idCiclo . '}';
					if (isset($aCiclo[$idCiclo]) != 0) {
						$sNomCiclo = $aCiclo[$idCiclo];
					}
					$sFila = '<td>' . $sNomCiclo . '</td><td>' . $fila['core01contaprob' . $k] . ' / ' . $fila['core01contmat' . $k] . '</td>' . $sFila;
				}
				$iProcesos++;
				if ($iProcesos > 3) {
					$sRes = $sRes . '<tr>' . $sFila . '</tr>';
					$sFila = '';
					$iProcesos = 0;
				}
			}
			$sRes = $sRes . '<tr>' . $sFila . '</tr>';
			$sRes = $sRes . '</table>';
		}
	}
	return $sRes;
}
function f2202_GenerarPEI($idEstudiante, $idPrograma, $idPlanEstudios, $objDB, $bDebug = false)
{
	$sError = '';
	$sDebug = '';
	$core01id = 0;
	$iFechaInicial = 0;
	$sLineasProf = '';
	$idOpcionGrado = 0;
	$core01idgradopostgrado = 0;
	if ($idPlanEstudios == 0) {
		$sError = 'No se ha seleccionado un plan de estudios';
	}
	if ($sError == '') {
		$sSQL = 'SELECT TB.core01id, TB.core01fechainicio, TB.core01idestado, TB.core01idlineaprof, TB.core01idlineaprof2, 
		TB.core01idescuela, TB.core01gradoidopcion, TB.core01idgradopostgrado 
		FROM core01estprograma AS TB 
		WHERE TB.core01idtercero=' . $idEstudiante . ' AND TB.core01idprograma=' . $idPrograma . ' AND TB.core01idplandeestudios=' . $idPlanEstudios . '';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' <b>ACTUALIZAR PEI</b> Leyendo encabezado: ' . $sSQL . '<br>';
		}
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			$core01id = $fila['core01id'];
			$iFechaInicial = $fila['core01fechainicio'];
			$idEscuela = $fila['core01idescuela'];
			$idOpcionGrado = $fila['core01gradoidopcion'];
			$core01idgradopostgrado = $fila['core01idgradopostgrado'];
			$idLineaProf = $fila['core01idlineaprof'];
			if ($fila['core01idlineaprof'] > 0) {
				$sLineasProf = ',' . $fila['core01idlineaprof'];
				if ($fila['core01idlineaprof2'] > 0) {
					$sLineasProf = $sLineasProf . ',' . $fila['core01idlineaprof2'];
				}
			}
			switch ($fila['core01idestado']) {
				case -1: // Admitido (Es necesario para los estudios de homologacion.)
				case 0: // Activo...
				case 2: //Inactivo
				case 3: // Sin matricula por mas de 2 años.
					break;
				default:
					$sError = 'El plan de estudios no permite actualizaci&oacute;n.';
					break;
			}
		} else {
			$sError = 'No se encuentra un plan de estudios con los parametros asignados.';
		}
	}
	if ($sError == '') {
		list($idContTercero, $sError) = f1011_BloqueTercero($idEstudiante, $objDB);
		/*
		//Mirar que no tenga ya un plan cargado.
		$sSQL='SELECT 1 FROM core03plandeestudios_'.$idContTercero.' WHERE core03idestprograma='.$core01id.'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$sError='Ya se ha incorporado un plan de estudios.';
			}
		*/
	}
	if ($sError == '') {
		//, core01numcredbasicos, core01numcredespecificos, core01numcredelecgenerales, core01numcredelecescuela, core01numcredelecprograma, core01numcredeleccomplem, core01numcredelectivos
		$sCambia01 = '';
		if ($idLineaProf == -1) {
			//Saber si el programa tiene cargadas lineas de profundizacion.
			$sSQL = 'SELECT core11idlineaprof 
			FROM core11plandeestudio
			WHERE core11idversionprograma=' . $idPlanEstudios . ' AND core11fechaaprobado<>0 AND core11idlineaprof<>0
			GROUP BY core11idlineaprof';
			$tabla = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla) > 0) {
				$sCambia01 = 'core01idlineaprof=0, ';
			}
		}
		//Actualizar el encabezado de estudiante.
		$iNumLineas = 1;
		$sSQL = 'SELECT core10numcredbasicos, core10numcredespecificos, core10numcredelecgenerales, 
		core10numcredelecescuela, core10numcredelecprograma, core10numcredeleccomplem, core10numlineasprof 
		FROM core10programaversion 
		WHERE core10id=' . $idPlanEstudios . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			$bConElectivosGenerales = false;
			$bConElectivosEscuela = false;
			if ($fila['core10numcredelecgenerales'] > 0) {
				$bConElectivosGenerales = true;
			}
			if ($fila['core10numcredelecescuela'] > 0) {
				$bConElectivosEscuela = true;
			}
			$iNumLineas = $fila['core10numlineasprof'];
			$iHoy = fecha_DiaMod();
			$iTotalElectivos = $fila['core10numcredelecgenerales'] + $fila['core10numcredelecescuela'] + $fila['core10numcredelecprograma'] + $fila['core10numcredeleccomplem'];
			$sSQL = 'UPDATE core01estprograma SET ' . $sCambia01 . 'core01numcredbasicos=' . $fila['core10numcredbasicos'] . ', 
			core01numcredespecificos=' . $fila['core10numcredespecificos'] . ', core01numcredelecgenerales=' . $fila['core10numcredelecgenerales'] . ', 
			core01numcredelecescuela=' . $fila['core10numcredelecescuela'] . ', core01numcredelecprograma=' . $fila['core10numcredelecprograma'] . ', 
			core01numcredeleccomplem=' . $fila['core10numcredeleccomplem'] . ', core01numcredelectivos=' . $iTotalElectivos . ' 
			WHERE core01id=' . $core01id . '';
			$result = $objDB->ejecutasql($sSQL);
		} else {
			$sError = 'No se ha encontrado el plan de estudios [Ref ' . $idPlanEstudios . ']';
		}
	}
	if ($sError == '') {
		//Descartamos todos los cursos que esten en espera.
		$sSQL = 'UPDATE core03plandeestudios_' . $idContTercero . ' SET core03estado=-1 
		WHERE core03idestprograma=' . $core01id . ' AND core03estado IN (0, 1, 6, 9)';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' <b>ACTUALIZAR PEI</b> Inactivando cursos: ' . $sSQL . '<br>';
		}
		$result = $objDB->ejecutasql($sSQL);
		//Ahora si armar la tabla detalle.
		//core03notahomologa, core03fechanotahomologa, core03idusuarionotahomo, core03detallehomologa, 
		$core03id = tabla_consecutivo('core03plandeestudios_' . $idContTercero . '', 'core03id', '', $objDB);
		$sCampos03 = 'INSERT INTO core03plandeestudios_' . $idContTercero . ' (core03idestprograma, core03idcurso, core03id, core03idtercero, core03idprograma, 
		core03itipocurso, core03obligatorio, core03homologable, core03habilitable, 
		core03porsuficiencia, core03idprerequisito, core03idprerequisito2, core03idprerequisito3, core03idcorequisito, 
		core03numcreditos, core03nivelcurso, core03peracaaprueba, core03nota75, core03fechanota75, 
		core03idusuarionota75, core03nota25, core03fechanota25, core03idusuarionota25, core03idequivalencia, core03idmatricula, 
		core03fechainclusion, core03notafinal, core03formanota, core03estado, core03idequivalente, 
		core03idcursoreemp1, core03idcursoreemp2, core03tieneequivalente) VALUES ';
		$sValores03 = '';
		//Se carga la base que esta aprobada.
		$sTipoRegistro = '';
		switch($idOpcionGrado) {
			case 4: // Diplomado de profundización
				$sTipoRegistro = ' OR (core11tiporegistro=10)';
				break;
			case 5: // Creditos de postgrado
				$sIds40 = '-99';
				if ($core01idgradopostgrado != 0){
					$sSQL = 'SELECT TB.core11idcurso 
					FROM core11plandeestudio AS TB, core10programaversion AS T10 
					WHERE TB.core11idversionprograma=T10.core10id AND T10.core10idprograma=' . $core01idgradopostgrado . ' AND T10.core10estado="S"';
					$tablaPrev = $objDB->ejecutasql($sSQL);
					while ($filaPrev = $objDB->sf($tablaPrev)) {
						$sIds40 = $sIds40. ','.$filaPrev['core11idcurso'];
					}
				}
				$sTipoRegistro = ' OR ((core11tiporegistro IN (9,12)) AND (core11idcurso IN (' . $sIds40 . ')))';
				break;
		}
		$sIds40 = '-99';
		$sSQL = 'SELECT core11idcurso, core11tiporegistro, core11obligarorio, core11homologable, core11habilitable, 
		core11porsuficiencia, core11idprerequisito, core11idprerequisito2, core11idprerequisito3, core11idcorrequisito, 
		core11numcreditos, core11nivelaplica, core11idequivalente, core11idcursoreemp1, core11idcursoreemp2, 
		core11tieneequivalente 
		FROM core11plandeestudio 
		WHERE core11idversionprograma=' . $idPlanEstudios . ' AND core11fechaaprobado<>0 AND core11idlineaprof IN (0' . $sLineasProf . ') 
		AND ((core11tiporegistro<7)'.$sTipoRegistro.')';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' <b>ACTUALIZAR PEI</b> Lectura del plan de estudios: ' . $sSQL . '<br>';
		}
		$tabla = $objDB->ejecutasql($sSQL);
		while ($fila = $objDB->sf($tabla)) {
			$sIds40 = $sIds40 . ',' . $fila['core11idcurso'];
			if ($sValores03 != '') {
				$sValores03 = $sValores03 . ', ';
			}
			$core03formanota = 0;
			$core03estado = 0;
			if ($fila['core11idprerequisito'] != 0) {
				$core03estado = 1;
			}
			if ($fila['core11idprerequisito2'] != 0) {
				$core03estado = 1;
			}
			if ($fila['core11idprerequisito3'] != 0) {
				$core03estado = 1;
			}
			$core03idequivalente = $fila['core11idequivalente'];
			$core03idcursoreemp1 = $fila['core11idcursoreemp1'];
			$core03idcursoreemp2 = $fila['core11idcursoreemp2'];
			$core03tieneequivalente = $fila['core11tieneequivalente'];
			$sSQL = 'SELECT core03id FROM core03plandeestudios_' . $idContTercero . ' WHERE core03idestprograma=' . $core01id . ' AND core03idcurso= ' . $fila['core11idcurso'] . '';
			$tablar = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablar) == 0) {
				$sSQL = $sCampos03 . '(' . $core01id . ', ' . $fila['core11idcurso'] . ', ' . $core03id . ', ' . $idEstudiante . ', ' . $idPrograma . ', 
				' . $fila['core11tiporegistro'] . ', ' . $fila['core11obligarorio'] . ', "' . $fila['core11homologable'] . '", "' . $fila['core11habilitable'] . '", 
				"' . $fila['core11porsuficiencia'] . '", ' . $fila['core11idprerequisito'] . ', ' . $fila['core11idprerequisito2'] . ', ' . $fila['core11idprerequisito3'] . ', ' . $fila['core11idcorrequisito'] . ', 
				' . $fila['core11numcreditos'] . ', ' . $fila['core11nivelaplica'] . ', 0, 0, 0, 
				0, 0, 0, 0, 0, 0, 
				' . $iFechaInicial . ', 0, ' . $core03formanota . ', ' . $core03estado . ', ' . $core03idequivalente . ', 
				' . $core03idcursoreemp1 . ', ' . $core03idcursoreemp2 . ', ' . $core03tieneequivalente . ')';
				$core03id++;
			} else {
				$filar = $objDB->sf($tablar);
				$sSQL = 'UPDATE core03plandeestudios_' . $idContTercero . ' SET  
				core03itipocurso=' . $fila['core11tiporegistro'] . ', core03obligatorio=' . $fila['core11obligarorio'] . ', 
				core03homologable="' . $fila['core11homologable'] . '", core03habilitable="' . $fila['core11habilitable'] . '", 
				core03porsuficiencia="' . $fila['core11porsuficiencia'] . '", core03idprerequisito=' . $fila['core11idprerequisito'] . ', 
				core03idprerequisito2=' . $fila['core11idprerequisito2'] . ', core03idprerequisito3=' . $fila['core11idprerequisito3'] . ', 
				core03idcorequisito=' . $fila['core11idcorrequisito'] . ', core03numcreditos=' . $fila['core11numcreditos'] . ', 
				core03nivelcurso=' . $fila['core11nivelaplica'] . ', core03formanota=' . $core03formanota . ', 
				core03estado=' . $core03estado . ', core03idequivalente=' . $core03idequivalente . ', 
				core03idcursoreemp1=' . $core03idcursoreemp1 . ', core03idcursoreemp2=' . $core03idcursoreemp2 . ', 
				core03tieneequivalente=' . $core03tieneequivalente . ' WHERE core03id=' . $filar['core03id'] . '';
			}
			$result = $objDB->ejecutasql($sSQL);
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . ' <b>ACTUALIZAR PEI</b> Activando curso: ' . $sSQL . '<br>';
			}
		}
		//Ahora Cargamos los electivos de escuela.
		if ($bConElectivosEscuela) {
			$sSQL = 'SELECT TB.core28idcurso, T40.unad40homologable, T40.unad40habilitable, T40.unad40porsuficiencia, T40.unad40numcreditos
			FROM core28electivos AS TB, unad40curso AS T40 
			WHERE TB.core28idescuela=' . $idEscuela . ' AND TB.core28ofertado="S" AND TB.core28idcurso NOT IN (' . $sIds40 . ') 
			AND TB.core28idcurso=T40.unad40id';
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . ' <b>ACTUALIZAR PEI</b> Lectura de electivos disciplinares comunes: ' . $sSQL . '<br>';
			}
			$tabla = $objDB->ejecutasql($sSQL);
			while ($fila = $objDB->sf($tabla)) {
				$sIds40 = $sIds40 . ',' . $fila['core28idcurso'];
				if ($sValores03 != '') {
					$sValores03 = $sValores03 . ', ';
				}
				$core03formanota = 0;
				$core03estado = 0;
				$core03idequivalente = 0;
				$core03idcursoreemp1 = 0;
				$core03idcursoreemp2 = 0;
				$core03tieneequivalente = 0;
				$core11tiporegistro = 6;
				$core11obligarorio = 0;
				$core11nivelaplica = 2;
				$sSQL = 'SELECT core03id FROM core03plandeestudios_' . $idContTercero . ' WHERE core03idestprograma=' . $core01id . ' AND core03idcurso= ' . $fila['core28idcurso'] . '';
				$tablar = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($tablar) == 0) {
					$sSQL = $sCampos03 . '(' . $core01id . ', ' . $fila['core28idcurso'] . ', ' . $core03id . ', ' . $idEstudiante . ', ' . $idPrograma . ', 
					' . $core11tiporegistro . ', ' . $core11obligarorio . ', "' . $fila['unad40homologable'] . '", "' . $fila['unad40habilitable'] . '", 
					"' . $fila['unad40porsuficiencia'] . '", 0, 0, 0, 0, 
					' . $fila['unad40numcreditos'] . ', ' . $core11nivelaplica . ', 0, 0, 0, 
					0, 0, 0, 0, 0, 0, 
					' . $iFechaInicial . ', 0, ' . $core03formanota . ', ' . $core03estado . ', ' . $core03idequivalente . ', 
					' . $core03idcursoreemp1 . ', ' . $core03idcursoreemp2 . ', ' . $core03tieneequivalente . ')';
					$core03id++;
				} else {
					$filar = $objDB->sf($tablar);
					$sSQL = 'UPDATE core03plandeestudios_' . $idContTercero . ' SET  
					core03itipocurso=' . $core11tiporegistro . ', core03obligatorio=0, 
					core03homologable="' . $fila['unad40homologable'] . '", core03habilitable="' . $fila['unad40habilitable'] . '", 
					core03porsuficiencia="' . $fila['unad40porsuficiencia'] . '", core03idprerequisito=0, 
					core03idprerequisito2=0, core03idprerequisito3=0, 
					core03idcorequisito=0, core03numcreditos=' . $fila['unad40numcreditos'] . ', 
					core03nivelcurso=' . $core11nivelaplica . ', core03formanota=' . $core03formanota . ', 
					core03estado=' . $core03estado . ', core03idequivalente=' . $core03idequivalente . ', 
					core03idcursoreemp1=' . $core03idcursoreemp1 . ', core03idcursoreemp2=' . $core03idcursoreemp2 . ', 
					core03tieneequivalente=' . $core03tieneequivalente . ' WHERE core03id=' . $filar['core03id'] . '';
				}
				$result = $objDB->ejecutasql($sSQL);
				if ($bDebug) {
					$sDebug = $sDebug . fecha_microtiempo() . ' <b>ACTUALIZAR PEI</b> Activando curso Electivo disciplinar comun: ' . $sSQL . '<br>';
				}
			}
		}
		//Ahora los electivos Generales.
		if ($bConElectivosGenerales) {
			$sSQL = 'SELECT TB.core28idcurso, T40.unad40homologable, T40.unad40habilitable, T40.unad40porsuficiencia, T40.unad40numcreditos
			FROM core28electivos AS TB, unad40curso AS T40 
			WHERE TB.core28idescuela=0 AND TB.core28ofertado="S" AND TB.core28idcurso NOT IN (' . $sIds40 . ') 
			AND TB.core28idcurso=T40.unad40id';
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . ' <b>ACTUALIZAR PEI</b> Lectura electivos comunes: ' . $sSQL . '<br>';
			}
			$tabla = $objDB->ejecutasql($sSQL);
			while ($fila = $objDB->sf($tabla)) {
				$sIds40 = $sIds40 . ',' . $fila['core28idcurso'];
				if ($sValores03 != '') {
					$sValores03 = $sValores03 . ', ';
				}
				$core03formanota = 0;
				$core03estado = 0;
				$core03idequivalente = 0;
				$core03idcursoreemp1 = 0;
				$core03idcursoreemp2 = 0;
				$core03tieneequivalente = 0;
				$core11tiporegistro = 5;
				$core11obligarorio = 0;
				$core11nivelaplica = 3;
				$sSQL = 'SELECT core03id FROM core03plandeestudios_' . $idContTercero . ' WHERE core03idestprograma=' . $core01id . ' AND core03idcurso= ' . $fila['core28idcurso'] . '';
				$tablar = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($tablar) == 0) {
					$sSQL = $sCampos03 . '(' . $core01id . ', ' . $fila['core28idcurso'] . ', ' . $core03id . ', ' . $idEstudiante . ', ' . $idPrograma . ', 
					' . $core11tiporegistro . ', ' . $core11obligarorio . ', "' . $fila['unad40homologable'] . '", "' . $fila['unad40habilitable'] . '", 
					"' . $fila['unad40porsuficiencia'] . '", 0, 0, 0, 0, 
					' . $fila['unad40numcreditos'] . ', ' . $core11nivelaplica . ', 0, 0, 0, 
					0, 0, 0, 0, 0, 0, 
					' . $iFechaInicial . ', 0, ' . $core03formanota . ', ' . $core03estado . ', ' . $core03idequivalente . ', 
					' . $core03idcursoreemp1 . ', ' . $core03idcursoreemp2 . ', ' . $core03tieneequivalente . ')';
					$core03id++;
				} else {
					$filar = $objDB->sf($tablar);
					$sSQL = 'UPDATE core03plandeestudios_' . $idContTercero . ' SET  
					core03itipocurso=' . $core11tiporegistro . ', core03obligatorio=0, 
					core03homologable="' . $fila['unad40homologable'] . '", core03habilitable="' . $fila['unad40habilitable'] . '", 
					core03porsuficiencia="' . $fila['unad40porsuficiencia'] . '", core03idprerequisito=0, 
					core03idprerequisito2=0, core03idprerequisito3=0, 
					core03idcorequisito=0, core03numcreditos=' . $fila['unad40numcreditos'] . ', 
					core03nivelcurso=' . $core11nivelaplica . ', core03formanota=' . $core03formanota . ', 
					core03estado=' . $core03estado . ', core03idequivalente=' . $core03idequivalente . ', 
					core03idcursoreemp1=' . $core03idcursoreemp1 . ', core03idcursoreemp2=' . $core03idcursoreemp2 . ', 
					core03tieneequivalente=' . $core03tieneequivalente . ' WHERE core03id=' . $filar['core03id'] . '';
				}
				$result = $objDB->ejecutasql($sSQL);
				if ($bDebug) {
					$sDebug = $sDebug . fecha_microtiempo() . ' <b>ACTUALIZAR PEI</b> Activando curso Electivos comunes: ' . $sSQL . '<br>';
				}
			}
		}
		//Ahora si quitar lo que sobre.
		$sSQL = 'DELETE FROM core03plandeestudios_' . $idContTercero . ' WHERE core03idestprograma=' . $core01id . ' AND core03estado=-1';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' <b>ACTUALIZAR PEI</b> Retirando cursos sobrantes: ' . $sSQL . '<br>';
		}
		$result = $objDB->ejecutasql($sSQL);
		//Totalizamos el numero de cursos
		$sSQL = 'SELECT 1 FROM core03plandeestudios_' . $idContTercero . ' WHERE core03idestprograma=' . $core01id . '';
		$tabla3 = $objDB->ejecutasql($sSQL);
		$iNumCursos = $objDB->nf($tabla3);
		//$sSQL='SELECT 1 FROM core03plandeestudios_'.$idContTercero.' WHERE core03idestprograma='.$core01id.' AND core03estado IN ('.f2201_PEIEstadosAprobado().')';
		//$tabla3=$objDB->ejecutasql($sSQL);
		//$iNumAprobados=$objDB->nf($tabla3);
		//, core01numcursosaprob='.$iNumAprobados.'
		$sSQL = 'UPDATE core01estprograma SET core01fechapei=' . $iHoy . ', core01procesar=' . $iHoy . ', core01numcursos=' . $iNumCursos . ' WHERE core01id=' . $core01id . '';
		$result = $objDB->ejecutasql($sSQL);
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' <b>ACTUALIZAR PEI</b> Totalizando PEI: ' . $sSQL . '<br>';
		}
	}
	return array($sError, $sDebug);
}
//Agregar un curso al plan de estudios, esto aplica para diplomados y para creditos de postgrado
function f2203_AddCurso($core01id, $idcurso, $objDB, $bDebug)
{
	$sError = '';
	$sDebug = '';
	$sSQL = 'SELECT TB.core01idplandeestudios, TB.core01idtercero, T11.unad11idtablero, TB.core01idprograma, TB.core01fechainicio
	FROM core01estprograma AS TB, unad11terceros AS T11 
	WHERE TB.core01id=' . $core01id . ' AND TB.core01idtercero=T11.unad11id';
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' <b>PEI: </b> Registro PEI ' . $sSQL . '<br>';
	}
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$idPrograma = $fila['core01idprograma'];
		$idPlan = $fila['core01idplandeestudios'];
		$idTercero = $fila['core01idtercero'];
		$idContenedor = $fila['unad11idtablero'];
		$iFechaInicial = $fila['core01fechainicio'];
	} else {
		$sError = 'Registro de estuidante NO encontrado';
	}
	if ($sError == '') {
		//Ahora alistar el curso, Primero lo buscamos en el plan de estudios.
		$sSQL = 'SELECT core11tiporegistro AS corf14tipocredito, core11nivelaplica AS corf14nivel, core11numcreditos AS unad40numcreditos
		FROM core11plandeestudio 
		WHERE core11idversionprograma=' . $idPlan . ' AND core11idcurso=' . $idcurso . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila14 = $objDB->sf($tabla);
		} else {
			$sSQL = 'SELECT TB.corf14tipocredito, TB.corf14nivel, T40.unad40numcreditos 
			FROM corf14cursoexcepcion AS TB, unad40curso AS T40 
			WHERE TB.corf14idplanest=' . $idPlan . ' AND TB.corf14idcurso=' . $idcurso . ' AND TB.corf14idcurso=T40.unad40id';
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . ' <b>Exepciones: </b>: ' . $sSQL . '<br>';
			}
			$tabla = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla) > 0) {
				$fila14 = $objDB->sf($tabla);
			} else {
				$sError = 'No se ha encontrado el curso solicitado [' . $idcurso . '].';
			}
		}
	}
	if ($sError == '') {
		$core03formanota = 0;
		$core03estado = 0;
		$sSQL = 'SELECT core03id, core03idequivalente 
		FROM core03plandeestudios_' . $idContenedor . ' WHERE core03idestprograma=' . $core01id . ' 
		AND core03idcurso=' . $idcurso . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) == 0) {
			//Ahora si insertarlo en el PEI
			$core03id = tabla_consecutivo('core03plandeestudios_' . $idContenedor . '', 'core03id', '', $objDB);
			//, core03notahomologa, core03fechanotahomologa, core03idusuarionotahomo, core03detallehomologa, 
			$sCampos03 = 'INSERT INTO core03plandeestudios_' . $idContenedor . ' (core03idestprograma, core03idcurso, core03id, core03idtercero, core03idprograma, 
			core03itipocurso, core03obligatorio, core03homologable, core03habilitable, 
			core03porsuficiencia, core03idprerequisito, core03idprerequisito2, core03idprerequisito3, core03idcorequisito, 
			core03numcreditos, core03nivelcurso, core03peracaaprueba, core03nota75, core03fechanota75, 
			core03idusuarionota75, core03nota25, core03fechanota25, core03idusuarionota25, 
			core03idequivalencia, core03idmatricula, 
			core03fechainclusion, core03notafinal, core03formanota, core03estado, core03idequivalente, 
			core03idcursoreemp1, core03idcursoreemp2, core03tieneequivalente) VALUES ';
			$sValores03 = '(' . $core01id . ', ' . $idcurso . ', ' . $core03id . ', ' . $idTercero . ', ' . $idPrograma . ', 
			' . $fila14['corf14tipocredito'] . ', 0, "N", "N", 
			"N", 0, 0, 0, 0, 
			' . $fila14['unad40numcreditos'] . ', ' . $fila14['corf14nivel'] . ', 0, 0, 0, 
			0, 0, 0, 0, 
			0, 0, 
			' . $iFechaInicial . ', 0, ' . $core03formanota . ', ' . $core03estado . ', 0, 
			0, 0, 0)';
			$result = $objDB->ejecutasql($sCampos03 . $sValores03);
			if ($result == false) {
				$sError = 'Falla al intentar agregar el curso al PEI.<br>' . $objDB->serror . '';
			}
		} else {
			//Es posible que ya exista como equivalente y que deba ser transformado.
			$fila = $objDB->sf($tabla);
			$core03id = $fila['core03id'];
			if ($fila['core03idequivalente'] != 0) {
				$sSQL = 'UPDATE core03plandeestudios_' . $idContenedor . ' SET core03itipocurso=' . $fila14['corf14tipocredito'] . ', 
				core03numcreditos=' . $fila14['unad40numcreditos'] . ', core03nivelcurso=' . $fila14['corf14nivel'] . ', 
				core03formanota=' . $core03formanota . ', core03estado=' . $core03estado . ', core03idequivalente=0,
				core03idcursoreemp1=0, core03idcursoreemp2=0, core03tieneequivalente=0 
				WHERE core03id=' . $core03id . '';
				$result = $objDB->ejecutasql($sSQL);
				if ($result == false) {
					$sError = 'Falla al intentar actualizar el curso en el PEI.<br>' . $objDB->serror . '';
				}
			} else {
				$sError = 'El curso ya hace parte del plan de estudios, no se puede agregar la excepci&oacute;n.';
			}
		}
	}
	if ($sError == '') {
		//Ahora importarle la nota del curso.
		list($sErrorL, $sDebugL) = f2203_ArmarFilaPlan($idContenedor, $core03id, $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugL;
		list($core01avanceplanest, $sErrorP, $sDebugP) = f2203_TotalizarPlanV2($core01id, $objDB, $bDebug, $idContenedor);
		$sDebug = $sDebug . $sDebugP;
		list($sDebugG) = f2201_ActivarParaTrabajoGrado($core01id, $objDB, $bDebug);
		$sDebug = $sDebug . $sDebugG;
	}
	return array($sError, $sDebug);
}
// Aplicar una homologacion, esto se da cuando las homologaciones cambian de estado.
function f2203_AplicarHomologacion($id71, $objDB, $bDebug = false, $idContTercero = 0)
{
	$sError = '';
	$sDebug = '';
	/* --- Estados a los que puede llegar un curso y que se pueden gestionar en esta funcion.
	2	Solicitado en Homologación
	3	En Estudio de Homologación
	4	Homologación Aprobada
	5	Homologado
	10	Plan de Transición
	11	Aprobado Por Convenio
	12	Solicitado en Suficiencia
	13	En Estudio de Suficiencia
	14	Suficiencia Aprobada
	15	Aprobado por suficiencia
	25	Homologado por Equivalencia
	*/
	//Primero ubicamos el tipo de homologacion.
	$iEstadoFin = -1;
	$iEstadoFin2 = -1;
	$sSQL = 'SELECT core71idclasehomol, core71estado, core71idestudiante, core71idestprog 
	FROM core71homolsolicitud 
	WHERE core71id=' . $id71 . '';
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' <b>Aplicando Homologacion </b>: ' . $sSQL . '<br>';
	}
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$core71idclasehomol = $fila['core71idclasehomol'];
		$core71estado = $fila['core71estado'];
		$idTercero = $fila['core71idestudiante'];
		$core71idestprog = $fila['core71idestprog'];
		switch ($core71idclasehomol) {
			case 1: // Externa
				switch ($core71estado) {
					case 14: // aprobada
					case 16: // Aplicada
						$iEstadoFin = 5; // Homologado
						break;
					}
				break;
			case 3: // Convenio
				switch ($core71estado) {
					case 1: //La devolvieron, dejar todo como estaba.
						$iEstadoFin = 0;
						$iEstadoFin2 = 1; //En caso que tenga prerequisitos y no esten aprobados.
						break;
					case 9: // Pasa a verificar estudio.
						$iEstadoFin = 2;
						$iEstadoFin2 = 2;
						break;
					case 16: //	Aplicada
						$iEstadoFin = 5; // Homologado, puede ser 11.
						$iEstadoFin2 = 11; //En caso de la nota sea por convenio.
						break;
				}
				break;
			case 11: // Cambio de programa
				switch ($core71estado) {
					case 16: // Aplicada
						$iEstadoFin = 10;
						$iEstadoFin2 = 10;
				}
				break;
		}
		if ($iEstadoFin == -1) {
			$sError = 'No ha sido posible determinar el proceso para el tipo de homologaci&oacute;n [Clase ' . $core71idclasehomol . ' - Estado ' . $core71estado . ']';
		}
	} else {
		$sError = 'No se ha encontrado la homologaci&oacute;n Ref: ' . $id71 . '';
	}
	if ($sError == '') {
		//Verificamos que el estado del estudiante permita la transaccion
		$sSQL = 'SELECT core01idestado FROM core01estprograma WHERE core01id=' . $core71idestprog . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			switch ($fila['core01idestado']) {
				case 7: //Graduando
					$sError = 'El estudiante ya tiene completo su plan de estudios';
					break;
				case 10: // Graduado
					$sError = 'El estudiante ya se encuentra graduado';
					break;
				case -1: //Admitido
					$sSQL = 'UPDATE core01estprograma SET core01idestado=0 WHERE core01id=' . $core71idestprog . '';
					$result = $objDB->ejecutasql($sSQL);
					break;
			}
		} else {
			$sError = 'No ha sido posible determinar el registro de estudiante Ref: ' . $core71idestprog . '';
		}
	}
	if ($sError == '') {
		if ($idContTercero == 0) {
			list($idContTercero, $sError) = f1011_BloqueTercero($idTercero, $objDB);
		}
		if ($idContTercero == 0) {
			$sError = 'No ha sido posible ubicar el contenedor para el tercero';
		}
	}
	if ($sError == '') {
		//Primero ubicamos el listado de cursos contenidos en la solicitud.
		$aCursos = array();
		$aIndice = array();
		$iCursos = 0;
		$sIds40 = '-99';
		$sIds73 = '0';
		//Retiramos los negados
		$sSQL = 'SELECT core73id, core73idcurso, core73idestadoestudio, core73formacalificacion, core73notaorigen, core73notahomol, 
		core73idcursoorigen 
		FROM core73homolcurso 
		WHERE core73idsolicitudhomol=' . $id71 . ' AND core73idestadoestudio NOT IN (9)';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' Cursos incluidos en el estudio: ' . $sSQL . '<br>';
		}
		$tabla = $objDB->ejecutasql($sSQL);
		while ($fila = $objDB->sf($tabla)) {
			$idCurso = $fila['core73idcurso'];
			$sIds40 = $sIds40 . ',' . $idCurso;
			$sIds73 = $sIds73 . ',' . $fila['core73id'];
			$aIndice[$iCursos] = $idCurso;
			$aCursos[$idCurso]['id'] = $fila['core73id'];
			$aCursos[$idCurso]['estado'] = $fila['core73idestadoestudio'];
			$aCursos[$idCurso]['formacal'] = $fila['core73formacalificacion'];
			$aCursos[$idCurso]['origen'] = $fila['core73notaorigen'];
			$aCursos[$idCurso]['nota'] = $fila['core73notahomol'];
			$aCursos[$idCurso]['cursoorigen'] = $fila['core73idcursoorigen'];
			$iCursos++;
		}
		//Ahora si cargamos esos registors del PEI
		//, core03formanota, core03estado, core03idhomolcurso, core03notafinal
		$sSQL = 'SELECT core03id, core03idcurso, core03idprerequisito 
		FROM core03plandeestudios_' . $idContTercero . ' 
		WHERE core03idestprograma=' . $core71idestprog . ' AND core03idcurso IN (' . $sIds40 . ') AND core03idhomolcurso IN (' . $sIds73 . ')';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' Revisando PEI: ' . $sSQL . '<br>';
		}
		$tabla = $objDB->ejecutasql($sSQL);
		while ($fila = $objDB->sf($tabla)) {
			$idCurso = $fila['core03idcurso'];
			$core03formanota = 0; //0 pendiente, 1 Matricula directa, 2 Homologacion, 3 Convenio, 4 Validacion (suficiencia)
			$core03estado = $iEstadoFin;
			$core03notafinal = 0;
			$core03idequivalencia = 0;
			if ($iEstadoFin == 0) {
				$core03idhomolcurso = 0;
			} else {
				//La homologacion esta en proceso hay que marcar el registro.
				$core03idhomolcurso = $aCursos[$idCurso]['id'];
				switch ($core71idclasehomol) {
					case 3: //Convenio
					case 4: //Suficieencias
						$core03formanota = $core71idclasehomol;
						if ($core71estado == 16) {
							if ($aCursos[$idCurso]['estado'] == 7) {
								if ($aCursos[$idCurso]['formacal'] == 6) {
									$core03estado = $iEstadoFin2;
								} else {
									$core03notafinal = $aCursos[$idCurso]['nota'] * 100;
								}
							} else {
								$core03estado = 0;
								$core03formanota = 0;
							}
						}
						break;
					default:
						$core03formanota = 2;
						if ($aCursos[$idCurso]['estado'] == 7) {
							$core03notafinal = $aCursos[$idCurso]['nota'] * 100;
							$core03idequivalencia = $aCursos[$idCurso]['cursoorigen'];
						}
						break;
				}
			}
			//Revisar el tema de prerequisito.
			if ($core03estado == 0) {
				if ($fila['core03idprerequisito'] != 0) {
					//Revisar que el prerequisito no este aprobado.
					$sSQL = 'SELECT 1 
					FROM core03plandeestudios_' . $idContTercero . ' 
					WHERE core03idestprograma=' . $core71idestprog . ' AND core03idcurso=' . $fila['core03idprerequisito'] . ' AND core03estado IN (' . f2201_PEIEstadosAprobado() . ')';
					$tablarev = $objDB->ejecutasql($sSQL);
					if ($objDB->nf($tablarev) == 0) {
						$core03estado = $iEstadoFin2;
					}
				}
			}
			//Registrar el cambio.
			$sSQL = 'UPDATE core03plandeestudios_' . $idContTercero . ' SET core03formanota=' . $core03formanota . ', core03notafinal=' . $core03notafinal . ', 
			core03estado=' . $core03estado . ', core03idhomolcurso=' . $core03idhomolcurso . ', core03idequivalencia=' . $core03idequivalencia . ' 
			WHERE core03id=' . $fila['core03id'] . '';
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . ' Actualizando curso: ' . $sSQL . '<br>';
			}
			$result = $objDB->ejecutasql($sSQL);
			//Ver si este curso es prerequisito para liberar elcurso posterior
			list($sErrorP, $sDebugP) = f2203_LiberarPrerequisito($core71idestprog, $idCurso, $objDB, $idContTercero, $bDebug);
			$sDebug = $sDebug . $sDebugP;
		}
	}
	if ($sError == '') {
		if ($core71estado == 16) {
			//Totalizar el plan de estudios.
			list($core01avanceplanest, $sErrorP, $sDebugP) = f2203_TotalizarPlanV2($core71idestprog, $objDB, $bDebug, $idContTercero);
			$sDebug = $sDebug . $sDebugP;
		}
	}
	return array($sError, $sDebug);
}
// Ver si el curso fue aprobado o esta matriculado.
function f2203_ArmarFilaPlan($idContTercero, $id03, $objDB, $bDebug = false)
{
	$sError = '';
	$sDebug = '';
	$bTieneEquivalente = false;
	$bActualiza = false;
	//Miramos que el registro exista y que este pendiente de ser procesado.
	$sSQL = 'SELECT TB.core03idestprograma, TB.core03estado, TB.core03idcurso, TB.core03idtercero, TB.core03idprograma, 
	TB.core03itipocurso, TB.core03tieneequivalente 
	FROM core03plandeestudios_' . $idContTercero . ' AS TB
	WHERE TB.core03id=' . $id03 . '';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila03 = $objDB->sf($tabla);
		switch ($fila03['core03estado']) {
			case 0: // Disponible
			case 1: // Pendiente de prerequisito
			case 6: // Matriculado
				$idPlan = $fila03['core03idestprograma'];
				$idTercero = $fila03['core03idtercero'];
				$idCurso = $fila03['core03idcurso'];
				$idPrograma = $fila03['core03idprograma'];
				$idTipoRegistro = $fila03['core03itipocurso'];
				if ($fila03['core03tieneequivalente'] != 0) {
					$bTieneEquivalente = true;
					//Resolver primero los equivalentes.
					$sSQL = 'SELECT TB.core03id 
					FROM core03plandeestudios_' . $idContTercero . ' AS TB 
					WHERE TB.core03idestprograma=' . $idPlan . ' AND TB.core03estado IN (0, 1, 6) AND TB.core03idequivalente<>0 
					AND (TB.core03idcursoreemp1=' . $idCurso . ' OR TB.core03idcursoreemp2=' . $idCurso . ')';
					$tablae = $objDB->ejecutasql($sSQL);
					while ($filae = $objDB->sf($tablae)) {
						list($sErrorE, $sDebugE) = f2203_ArmarFilaPlan($idContTercero, $filae['core03id'], $objDB, $bDebug);
						$sDebug = $sDebug . $sDebugE;
					}
				}
				break;
			default:
				$sError = 'El curso se encuentra bloqueado para ser procesado.';
				break;
		}
	} else {
		$sError = 'No se ha encontrado el registro del plan de estudios.';
	}
	if ($sError == '') {
		//Pendiente traer la nota minima segun se haya definido en el Plan tabla core11
		$dRes = f2211_NotaAprobatoria($idCurso, $idPrograma, 0, $objDB);
		$iNotaMinima = ($dRes * 100) - 1;
		//TB.core03idcurso, TB.core03idtercero, TB.core03idprograma
		//Buscar el curso en la 04 para ver si fue aprobado.
		// Julio 27 de 2022 se agregan los cursos externos (estado 2) a ser prosesados.
		$sSQL = 'SELECT TB.core04id, TB.core04peraca, TB.core04nota75, TB.core04fechanota75, TB.core04idusuarionota75, 
		TB.core04nota25, TB.core04fechanota25, TB.core04idusuarionota25, TB.core04idmatricula, TB.core04notafinal, TB.core04resdef
		FROM core04matricula_' . $idContTercero . ' AS TB, ofer08oferta AS T8
		WHERE TB.core04tercero=' . $idTercero . ' AND TB.core04idcurso=' . $idCurso . ' AND TB.core04peraca>760 AND TB.core04estado IN (2,7,8) 
		AND core04resdef>=core04est_aprob AND TB.core04peraca=T8.ofer08idper_aca AND TB.core04idcurso=T8.ofer08idcurso AND T8.ofer08cead=0 AND T8.ofer08c2fechacierre<>0
		ORDER BY TB.core04peraca DESC';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) == 0) {
			//Por si no cruza con la oferta.
			$sSQL = 'SELECT TB.core04id, TB.core04peraca, TB.core04nota75, TB.core04fechanota75, TB.core04idusuarionota75, 
			TB.core04nota25, TB.core04fechanota25, TB.core04idusuarionota25, TB.core04idmatricula, TB.core04notafinal, TB.core04resdef
			FROM core04matricula_' . $idContTercero . ' AS TB
			WHERE TB.core04tercero=' . $idTercero . ' AND TB.core04idcurso=' . $idCurso . ' AND TB.core04peraca<761 AND TB.core04estado=8 AND core04resdef>=core04est_aprob
			ORDER BY TB.core04peraca DESC';
			$tabla = $objDB->ejecutasql($sSQL);
		}
		if ($objDB->nf($tabla) > 0) {
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . ' <b>PEI - Registrando nota</b>: ' . $sSQL . '<br>';
			}
			$fila = $objDB->sf($tabla);
			//Actualizar el registro..
			$core03nota25 = $fila['core04nota25'];
			$core03fechanota25 = $fila['core04fechanota25'];
			$core03idusuarionota25 = $fila['core04idusuarionota25'];
			$core03idmatricula = $fila['core04idmatricula'];
			$core03formanota = 1; //0 pendiente, 1 Matricula directa, 2 Homologacion, 3 Validacion (suficiencia)
			$core03estado = 7; //7	Aprobado 
			switch ($idTipoRegistro) {
				case 3: //	Requisito de grado	
					$core03estado = 8; // Requisto Cumplido
					break;
			}
			$iNotaFin = $fila['core04resdef'] * 100;
			$bTieneEquivalente = false;
			$sSQL = 'UPDATE core03plandeestudios_' . $idContTercero . ' 
			SET core03peracaaprueba=' . $fila['core04peraca'] . ', core03nota75=' . $fila['core04nota75'] . ', 
			core03fechanota75=' . $fila['core04fechanota75'] . ', core03idusuarionota75=' . $fila['core04idusuarionota75'] . ', 
			core03nota25=' . $core03nota25 . ', core03fechanota25=' . $core03fechanota25 . ', core03idusuarionota25=' . $core03idusuarionota25 . ', 
			core03idmatricula=' . $core03idmatricula . ', core03notafinal=' . $iNotaFin . ', core03formanota=' . $core03formanota . ', 
			core03estado=' . $core03estado . ' 
			WHERE core03id=' . $id03 . '';
			$result = $objDB->ejecutasql($sSQL);
			$bActualiza = true;
		}
		if ($bTieneEquivalente) {
			//El alumno no lo ha visto pero pudo haber visto un equivalente.
			$sSQL = 'SELECT TB.core03id, TB.core03idcurso, TB.core03peracaaprueba, TB.core03nota75, TB.core03fechanota75, 
			TB.core03idusuarionota75, TB.core03nota25, TB.core03fechanota25, TB.core03idusuarionota25, TB.core03idmatricula, 
			TB.core03notafinal, TB.core03formanota 
			FROM core03plandeestudios_' . $idContTercero . ' AS TB 
			WHERE TB.core03idestprograma=' . $idPlan . ' AND TB.core03estado IN (7, 8) AND TB.core03idequivalente<>0 
			AND (TB.core03idcursoreemp1=' . $idCurso . ' OR TB.core03idcursoreemp2=' . $idCurso . ')';
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . ' Considerando equivalentes: ' . $sSQL . '<br>';
			}
			$tablae = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablae) > 0) {
				$filae = $objDB->sf($tablae);
				$core03estado = 25;
				$sSQL = 'UPDATE core03plandeestudios_' . $idContTercero . ' 
				SET core03peracaaprueba=' . $filae['core03peracaaprueba'] . ', core03nota75=' . $filae['core03nota75'] . ', 
				core03fechanota75=' . $filae['core03fechanota75'] . ', core03idusuarionota75=' . $filae['core03idusuarionota75'] . ', 
				core03nota25=' . $filae['core03nota25'] . ', core03fechanota25=' . $filae['core03fechanota25'] . ', 
				core03idusuarionota25=' . $filae['core03idusuarionota25'] . ', core03idmatricula=' . $filae['core03idmatricula'] . ', 
				core03notafinal=' . $filae['core03notafinal'] . ', core03formanota=' . $filae['core03formanota'] . ', 
				core03estado=' . $core03estado . ', core03idprerequisito3=' . $filae['core03idcurso'] . ' 
				WHERE core03id=' . $id03 . '';
				$result = $objDB->ejecutasql($sSQL);
				$bActualiza = true;
			}
		}
		if ($bActualiza) {
			//Resulta que este puede ser un prerequisito y debe habilitar el curso siguiente.
			list($sErrorP, $sDebugP) = f2203_LiberarPrerequisito($idPlan, $idCurso, $objDB, $idContTercero, $bDebug);
			$sDebug = $sDebug . $sDebugP;
		}
	}
	return array($sError, $sDebug);
}
//Cursos Sobrantes
function f2203_CursosSobrantes($core01id, $objDB, $bDebug = false)
{
	$sIds = '-99';
	$sError = '';
	$sDebug = '';
	$sSQL = 'SELECT TB.core01idplandeestudios, TB.core01idtercero, T11.unad11idtablero, TB.core01idprograma
	FROM core01estprograma AS TB, unad11terceros AS T11 
	WHERE TB.core01id=' . $core01id . ' AND TB.core01idtercero=T11.unad11id';
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' <b>PEI: </b> Registro PEI ' . $sSQL . '<br>';
	}
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$idPrograma = $fila['core01idprograma'];
		$idPlan = $fila['core01idplandeestudios'];
		$idTercero = $fila['core01idtercero'];
		$idContenedor = $fila['unad11idtablero'];
	} else {
		$sError = 'Registro de estuidante NO encontrado';
	}
	if ($sError == '') {
		$sExcluidos = '-99';
		$sSQL = 'SELECT core03idcurso, core03estado, core03idequivalente, core03idprerequisito3 
		FROM core03plandeestudios_' . $idContenedor . ' 
		WHERE core03idestprograma=' . $core01id . '';
		$tabla = $objDB->ejecutasql($sSQL);
		while ($fila = $objDB->sf($tabla)) {
			$sExcluidos = $sExcluidos . ',' . $fila['core03idcurso'];
			switch ($fila['core03estado']) {
				case 10: // Transicion
					$sExcluidos = $sExcluidos . ',' . $fila['core03idequivalente'];
					break;
				case 25: // Homologado por equivalencia
					$sExcluidos = $sExcluidos . ',' . $fila['core03idprerequisito3'];
					break;
			}
		}
		$sSQL = 'SELECT core04idcurso FROM core04matricula_' . $idContenedor . ' WHERE core04tercero=' . $idTercero . ' AND core04resultado=7 AND core04idcurso NOT IN (' . $sExcluidos . ')';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' <b>PEI: </b> Cursos aprobados NO REQUERIDOS ' . $sSQL . '<br>';
		}
		$tabla = $objDB->ejecutasql($sSQL);
		while ($fila = $objDB->sf($tabla)) {
			$sIds = $sIds . ',' . $fila['core04idcurso'];
		}
	}
	return array($sIds, $sError, $sDebug);
}
//Liberar cursos donde sea prerequisito.
function f2203_LiberarPrerequisito($core01id, $idCurso, $objDB, $idContTercero = 0, $bDebug = false)
{
	$sError = '';
	$sDebug = '';
	//@@@ esto se debe mejor de cara al multiple perequisito, por ahora dejamos en uno solo
	$sSQL = 'UPDATE core03plandeestudios_' . $idContTercero . ' 
	SET core03estado=0 
	WHERE core03idestprograma=' . $core01id . ' AND core03estado=1 AND core03idprerequisito=' . $idCurso . ' AND core03idprerequisito2=0 AND core03idprerequisito3=0';
	$result = $objDB->ejecutasql($sSQL);
	return array($sError, $sDebug);
}
//Numero de estudiantes pendientes de ver el curso
function f2203_Pendientes($idCurso, $idPlan, $objDB, $iPeriodoPremat = 0, $bIncluirDesertores = false, $bDebug = false)
{
	$sDebug = '';
	$iTotal = 0;
	$iPremPeriodo = 0;
	$sEstadosDeserta = '';
	if ($bIncluirDesertores) {
		$sEstadosDeserta = ',3,5,6';
	}
	$sSQLadd = '';
	if ((int)$idPlan != 0) {
		$sSQLadd = ' AND T1.core01idplandeestudios=' . $idPlan . '';
	}
	$sCampos = '';
	$sAgrupa = '';
	if ($iPeriodoPremat != 0) {
		$sCampos = 'core03premidperiodo, ';
		$sAgrupa = ' GROUP BY core03premidperiodo';
	}
	$sSQL = 'SHOW TABLES LIKE "core04%"';
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' Total Periodo: Lista de contenedores: ' . $sSQL . '<br>';
	}
	$tablac = $objDB->ejecutasql($sSQL);
	while ($filac = $objDB->sf($tablac)) {
		$iContenedor = substr($filac[0], 16);
		if ($iContenedor != 0) {
			//core03premidperiodo
			$sSQL = 'SELECT ' . $sCampos . ' COUNT(1) AS Total
			FROM core03plandeestudios_' . $iContenedor . ' AS TB, core01estprograma AS T1, core09programa AS T9 
			WHERE TB.core03idcurso=' . $idCurso . ' AND TB.core03estado IN (0,1) 
			AND TB.core03idestprograma=T1.core01id AND T1.core01idestado IN (0,1,2' . $sEstadosDeserta . ') ' . $sSQLadd . ' 
			AND T1.core01idprograma=T9.core09id AND T9.core09aplicacontinuidad=1 ' . $sAgrupa . '';
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . ' Consultando: ' . $sSQL . '<br>';
			}
			$tabla = $objDB->ejecutasql($sSQL);
			while ($fila = $objDB->sf($tabla)) {
				$iTotal = $iTotal + $fila['Total'];
				if ($iPeriodoPremat != 0) {
					if ($iPeriodoPremat == $fila['core03premidperiodo']) {
						$iPremPeriodo = $iPremPeriodo + $fila['Total'];
					}
				}
			}
		}
	}
	return array($iTotal, $iPremPeriodo, $sDebug);
}
//Purgar el plan de estudio aplica para cuando se hace transicion o cambio de programa, basicamente se quita todo lo que sobre.
function f2203_PurgarPlan($core01id, $objDB, $bDebug = false)
{
	$sError = '';
	$sDebug = '';
	$sSQL = 'SELECT TB.core01idestado, TB.core01fechafinaliza, TB.core01peracafinal, T11.unad11idtablero
	FROM core01estprograma AS TB, unad11terceros AS T11 
	WHERE TB.core01id=' . $core01id . ' AND TB.core01idtercero=T11.unad11id';
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' <b>PEI: </b> Registro PEI ' . $sSQL . '<br>';
	}
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$idContenedor = $fila['unad11idtablero'];
		switch ($fila['core01idestado']) {
			case 11: // Transicion
			case 12: // Cambio de programa
				break;
			default:
				$sError = 'No se permite purgar el plan de estudios.';
				break;
		}
	} else {
		$sError = 'Registro de estuidante NO encontrado';
	}
	if ($sError == '') {
		//Retiramos todo lo que NO este aprobado.
		$sSQL = 'DELETE FROM core03plandeestudios_' . $idContenedor . ' WHERE core03idestprograma=' . $core01id . ' AND core03estado NOT IN (' . f2201_PEIEstadosAprobado() . ')';
		$result = $objDB->ejecutasql($sSQL);
		//Totalizar el plan de estudios.
		list($core01avanceplanest, $sErrorP, $sDebugP) = f2203_TotalizarPlanV2($core01id, $objDB, $bDebug, $idContenedor);
		$sDebug = $sDebug . $sDebugP;
		//Ahora ajustar los valores de terminacion
		$iFechaTermina = 0;
		$idPeriodoFin = 0;
		$sSQL = 'SELECT core03fechanota25, core03peracaaprueba FROM core03plandeestudios_' . $idContenedor . ' 
		WHERE core03idestprograma=' . $core01id . ' AND core03estado IN (5, 7, 8, 15) 
		ORDER BY core03fechanota25 DESC LIMIT 0,1';
		$tabla3 = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla3) > 0) {
			$fila3 = $objDB->sf($tabla3);
			$iFechaTermina = $fila3['core03fechanota25'];
			$idPeriodoFin = $fila3['core03peracaaprueba'];
		} else {
			$iFechaTermina = fecha_DiaMod();
		}
		$sSQL = 'UPDATE core01estprograma SET core01fechafinaliza=' . $iFechaTermina . ', 
		core01peracafinal=' . $idPeriodoFin . ' WHERE core01id=' . $core01id . '';
		$result = $objDB->ejecutasql($sSQL);
		list($sErrorC, $sDebugC, $iResultado, $iNumMatriculas) = f2201_AnalizarContinuidad($core01id, $objDB, $bDebug);
	}
	return array($sError, $sDebug);
}
// Calcular el avance del PEI
function f2203_TotalizarPlanV2($id01, $objDB, $bDebug = false, $idContTercero = 0, $bForzar = false)
{
	$sError = '';
	$sDebug = '';
	$core01cantmatriculas = 0;
	$core01sem_proyectados = 0;
	$core01semestrerelativo = 0;
	$core01avanceplanest = 0;
	$core01homol_est = 0;
	$core01homol_aprob = 0;
	$core01homol_total = 0;
	$sSQL = 'SELECT TB.core01idtercero, TB.core01idprograma, TB.core01idplandeestudios, TB.core01numcredbasicos, TB.core01numcredespecificos, 
	TB.core01numcredelecgenerales, TB.core01numcredelecescuela, TB.core01numcredelecprograma, TB.core01numcredeleccomplem, 
	TB.core01numcredelectivos, TB.core01idestado, TB.core01idrevision, T9.core09ofrecetitulo, T9.core09semestres, T9.core09aplicacontinuidad 
	FROM core01estprograma AS TB, core09programa AS T9 
	WHERE TB.core01id=' . $id01 . ' AND TB.core01idprograma=T9.core09id';
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' <b>Totalizar PEI</b>: Datos base ' . $sSQL . '<br>';
	}
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$idTercero = $fila['core01idtercero'];
		$core01idplandeestudios = $fila['core01idplandeestudios'];
		$core01idrevision = $fila['core01idrevision'];
		$iTotalCreditos = $fila['core01numcredbasicos'] + $fila['core01numcredespecificos'] + $fila['core01numcredelecgenerales'];
		$iTotalCreditos = $iTotalCreditos + $fila['core01numcredelecescuela'] + $fila['core01numcredelecprograma'] + $fila['core01numcredeleccomplem'];
		$core09ofrecetitulo = $fila['core09ofrecetitulo'];
		if ($fila['core09aplicacontinuidad'] == 1){
			$core01sem_proyectados = $fila['core09semestres'];
		}
		switch ($fila['core01idestado']) {
			case -1: // Admitido (Se permite)
			case 0: // Activo
			case 2: // Inactivo
			case 3: // Sin matricula por 2 años
				break;
			case -2: // Aspirante no hay forma.
			case 98: // Desinteresado
			case 99: // Inadmitido
				$sError = 'No es posible totalizar el plan de estudios.';
				break;
			default:
				if (!$bForzar) {
					$sError = 'No es posible totalizar el plan de estudios.';
				}
				break;
		}
	} else {
		$sError = 'No ha sido posible encontrar el plan Ref: ' . $id01 . '';
	}
	if ($sError == '') {
		if ($idContTercero == 0) {
			list($idContTercero, $sError) = f1011_BloqueTercero($idTercero, $objDB);
		}
		if ($idContTercero == 0) {
			$sError = 'No ha sido posible ubicar el contenedor para el tercero';
		}
	}
	if ($sError == '') {
		$bTermina = false;
		$bRevisarGrado = false;
		if ($core01idplandeestudios != 0) {
			//Primero vamos a ver la cantidad de matriculas.
			$sSQL = 'SELECT 1 FROM core16actamatricula WHERE core16tercero=' . $idTercero . ' AND core16idplanestudio=' . $core01idplandeestudios . '';
			$tabla16 = $objDB->ejecutasql($sSQL);
			$core01cantmatriculas = $objDB->nf($tabla16);
		}
		//Ahora miramos la aprobación por tipo de registro.
		$aTipoCurso = array();
		for ($k = 0; $k <= 12; $k++) {
			$aTipoCurso[$k] = 0;
		}
		$sSQL = 'SELECT TB.core03itipocurso, SUM(TB.core03numcreditos) AS Creditos 
		FROM core03plandeestudios_' . $idContTercero . ' AS TB
		WHERE TB.core03idestprograma=' . $id01 . ' AND TB.core03idequivalente=0 AND TB.core03estado IN (' . f2201_PEIEstadosAprobado() . ')
		GROUP BY TB.core03itipocurso';
		$tabla3 = $objDB->ejecutasql($sSQL);
		while ($fila3 = $objDB->sf($tabla3)) {
			$aTipoCurso[$fila3['core03itipocurso']] = $fila3['Creditos'];
		}
		$sTiposAExcluir = '-99';
		$core01numcredbasicosaprob = $aTipoCurso[0];
		if ($core01numcredbasicosaprob >= $fila['core01numcredbasicos']) {
			$core01numcredbasicosaprob = $fila['core01numcredbasicos'];
			$sTiposAExcluir = $sTiposAExcluir . ', 0';
		}
		$core01numcredespecificosaprob = $aTipoCurso[1];
		if ($core01numcredespecificosaprob >= $fila['core01numcredespecificos']) {
			$core01numcredespecificosaprob = $fila['core01numcredespecificos'];
			$sTiposAExcluir = $sTiposAExcluir . ', 1';
		}
		$core01numcredelecgeneralesaprob = $aTipoCurso[5];
		if ($core01numcredelecgeneralesaprob >= $fila['core01numcredelecgenerales']) {
			$core01numcredelecgeneralesaprob = $fila['core01numcredelecgenerales'];
			$sTiposAExcluir = $sTiposAExcluir . ', 5';
		}
		$core01numcredelecescuelaaprob = $aTipoCurso[6];
		if ($core01numcredelecescuelaaprob >= $fila['core01numcredelecescuela']) {
			$core01numcredelecescuelaaprob = $fila['core01numcredelecescuela'];
			$sTiposAExcluir = $sTiposAExcluir . ', 6';
		}
		$core01numcredelecprogramaaaprob = $aTipoCurso[2];
		if ($core01numcredelecprogramaaaprob >= $fila['core01numcredelecprograma']) {
			$core01numcredelecprogramaaaprob = $fila['core01numcredelecprograma'];
			$sTiposAExcluir = $sTiposAExcluir . ', 2';
		}
		$core01numcredeleccomplemaprob = $aTipoCurso[4];
		if ($core01numcredeleccomplemaprob >= $fila['core01numcredeleccomplem']) {
			$core01numcredeleccomplemaprob = $fila['core01numcredeleccomplem'];
			$sTiposAExcluir = $sTiposAExcluir . ', 4';
		}
		$core01numcredelectivosaprob = $core01numcredelecgeneralesaprob + $core01numcredelecescuelaaprob + $core01numcredelecprogramaaaprob + $core01numcredeleccomplemaprob;
		$iTotalAprobado = $core01numcredbasicosaprob + $core01numcredespecificosaprob + $core01numcredelectivosaprob;
		$core01avanceplanest = 0;
		if ($iTotalAprobado > 0) {
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . ' <b>AVANCE PEI</b> ' . $iTotalAprobado . ' / ' . $iTotalCreditos . '<br>';
			}
			if ($iTotalAprobado >= $iTotalCreditos) {
				$core01avanceplanest = 100;
				switch ($fila['core01idestado']) {
					case 7: // Egresando
						$bRevisarGrado = true;
						break;
					case 10: // Graduado
						break;
					default:
						if ($core09ofrecetitulo == 1) {
							$bTermina = true;
						}
						break;
				}
			} else {
				$core01avanceplanest = round(($iTotalAprobado / $iTotalCreditos) * 100, 2);
			}
		}
		//Ahora los cursos en homologaciones
		$sSQL = 'SELECT TB.core03estado, SUM(TB.core03numcreditos) AS Creditos 
		FROM core03plandeestudios_' . $idContTercero . ' AS TB
		WHERE TB.core03idestprograma=' . $id01 . ' AND TB.core03idequivalente=0 AND TB.core03estado IN (2,3,4,5,11,12,13,14,15)
		GROUP BY TB.core03estado';
		$tabla3 = $objDB->ejecutasql($sSQL);
		while ($fila3 = $objDB->sf($tabla3)) {
			switch ($fila3['core03estado']) {
				case 2: //Solicitado en Homologación
				case 3: //En Estudio de Homologación
				case 4: //Homologación Aprobada
				case 12: //Solicitado en Suficiencia
				case 13: //En Estudio de Suficiencia
				case 14: //Suficiencia Aprobada
					$core01homol_est = $core01homol_est + $fila3['Creditos'];
					break;
				case 5: //Homologado
				case 11: //Aprobado Por Convenio
				case 15: //Aprobado por suficiencia
					$core01homol_aprob = $core01homol_aprob + $fila3['Creditos'];
					break;
			}
		}
		$core01homol_total = $core01homol_est + $core01homol_aprob;
		//Fin del proceso de consultar homologaciones
		//Ahora vamos a calcular el semestre relativo
		if ($core01sem_proyectados > 0) {
			$iCreditosSem = round($iTotalCreditos / $core01sem_proyectados, 2);
			if ($iCreditosSem > 0) {
				$iAvancePropio = $iTotalAprobado;
				$iPorHomologacion = $core01homol_aprob;
				while ($iPorHomologacion > 0){
					$iPorHomologacion = $iPorHomologacion - $iCreditosSem;
					if ($iPorHomologacion >= 0){
						$core01sem_proyectados--;
						$iAvancePropio = $iAvancePropio - $iCreditosSem;
					}
				}
				//Ahora si colocarle el semestre relativo.
				$core01semestrerelativo = 1;
				while ($iAvancePropio > $iCreditosSem){
					$core01semestrerelativo++;
					$iAvancePropio = $iAvancePropio - $iCreditosSem;
				}
			}
		}
		if ($core01semestrerelativo > $core01sem_proyectados){
			$core01semestrerelativo = $core01sem_proyectados;
		}
		//Ahora si guardamos los datos
		$sSQL = 'UPDATE core01estprograma SET core01numcredbasicosaprob=' . $core01numcredbasicosaprob . ', 
		core01numcredespecificosaprob=' . $core01numcredespecificosaprob . ', core01numcredelecgeneralesaprob=' . $core01numcredelecgeneralesaprob . ', 
		core01numcredelecescuelaaprob=' . $core01numcredelecescuelaaprob . ', core01numcredelecprogramaaaprob=' . $core01numcredelecprogramaaaprob . ', 
		core01numcredeleccomplemaprob=' . $core01numcredeleccomplemaprob . ', core01numcredelectivosaprob=' . $core01numcredelectivosaprob . ', 
		core01avanceplanest=' . $core01avanceplanest . ', core01homol_est=' . $core01numcredelectivosaprob . ', 
		core01homol_aprob=' . $core01homol_aprob . ', core01homol_total=' . $core01homol_total . ', core01cantmatriculas=' . $core01cantmatriculas . ', 
		core01sem_proyectados=' . $core01sem_proyectados . ', core01semestrerelativo=' . $core01semestrerelativo . ', core01avancecreditos=' . $iTotalAprobado . ' 
		WHERE core01id=' . $id01 . '';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' <b>Totalizar PEI</b>: ' . $iTotalAprobado . ' / ' . $iTotalCreditos . ' ' . $sSQL . '<br>';
		}
		$result = $objDB->ejecutasql($sSQL);
		if ($core09ofrecetitulo == 1) {
			if ($sTiposAExcluir != '-99') {
				//Exluimos lo que ya no se necesita.
				$sSQL = 'UPDATE core03plandeestudios_' . $idContTercero . '
				SET core03estado=9 
				WHERE core03idestprograma=' . $id01 . ' AND core03estado IN (0, 1) AND core03itipocurso IN (' . $sTiposAExcluir . ')';
				if ($bDebug) {
					$sDebug = $sDebug . fecha_microtiempo() . ' <b>PEI - Retirando Sobrantes</b>: ' . $sSQL . '<br>';
				}
				$result = $objDB->ejecutasql($sSQL);
			}
		}
		if ($bTermina) {
			//Cambiar de estado el plan de estudios.
			$iFechaTermina = 0;
			$idPeriodoFin = 0;
			$sSQL = 'SELECT core03fechanota25, core03peracaaprueba 
			FROM core03plandeestudios_' . $idContTercero . ' 
			WHERE core03idestprograma=' . $id01 . ' AND core03estado IN (5, 7, 8, 15) 
			ORDER BY core03fechanota25 DESC LIMIT 0,1';
			$tabla3 = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla3) > 0) {
				$fila3 = $objDB->sf($tabla3);
				$iFechaTermina = $fila3['core03fechanota25'];
				$idPeriodoFin = $fila3['core03peracaaprueba'];
			} else {
				$iFechaTermina = fecha_DiaMod();
			}
			$sAdd = '';
			if ($core01idrevision < 1) {
				$sAdd = ', core01idrevision=' . $_SESSION['unad_id_tercero'] . '';
			}
			$sSQL = 'UPDATE core01estprograma SET core01fechafinaliza=' . $iFechaTermina . ', 
			core01peracafinal=' . $idPeriodoFin . $sAdd . ' WHERE core01id=' . $id01 . '';
			$result = $objDB->ejecutasql($sSQL);
			list($sErrorE, $sDebugE) = f2222_CambiaEstado($id01, $fila['core01idestado'], 7, 0, '', $objDB, $iFechaTermina, $bDebug);
			$bRevisarGrado = true;
		}
		if ($bRevisarGrado) {
			list($sErrorE, $sDebugE) = f2203_VerificarGrado($id01, $objDB, $bDebug);
			list($sErrorC, $sDebugC, $iResultado, $iNumMatriculas) = f2201_AnalizarContinuidad($id01, $objDB, $bDebug);
		}
	}
	return array($core01avanceplanest, $sError, $sDebug);
}
//Obtener el id del plan de estudios.
function f2203_UbicarPlanEstudiante($idTercero, $idPrograma, $objDB, $bDebug = false, $idContenedor = 0)
{
	$idPlan = 0;
	$sDebug = '';
	//@@@@ Esto hay que mejorarlo pero por el momento lo vamos a dejar sencillo
	$sSQL = 'SELECT core01idplandeestudios FROM core01estprograma WHERE core01idtercero=' . $idTercero . ' AND core01idprograma=' . $idPrograma . '';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) == 1) {
		$fila = $objDB->sf($tabla);
		$idPlan = $fila['core01idplandeestudios'];
	}
	return array($idPlan, $sDebug);
}
//Ver si esta graduado
function f2203_VerificarGrado($id01, $objDB, $bDebug)
{
	$bGraduado = false;
	$sError = '';
	$sDebug = '';
	$bConDBGrados = false;
	$sSQL = 'SELECT TB.core01idestado, TB.core01idtercero, TB.core01idprograma, T11.unad11doc, T9.core09codgrados, 
	TB.core01idtipopractica, core01estadopractica 
	FROM core01estprograma AS TB, unad11terceros AS T11, core09programa AS T9 
	WHERE TB.core01id=' . $id01 . ' AND TB.core01idtercero=T11.unad11id AND TB.core01idprograma=T9.core09id';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		if ($fila['core01idestado'] == 7) {
			//Es graduando, puede ser que ya se graduó.
			if ($fila['core09codgrados'] == 0) {
				$sError = 'No se ha definido el codigo del programa para grados';
			}
		} else {
			$sError = 'El registro no esta pendiente de revisi&oacute;n de grado.';
		}
	} else {
		$sError = 'No se ha encontrado el registro';
	}
	if ($sError == '') {
		list($objDBGrados, $sDebugD) = TraerDBGrados();
		if ($objDBGrados == NULL) {
			$sError = 'No ha sido posible conectarse a la base de datos de Grados.';
		} else {
			$bConDBGrados = true;
		}
	}
	if ($sError == '') {
		$sInfoGrado = '';
		$iFechaGrado = 0;
		$sSQL = 'SELECT codigocead, dia, mes, ano, acta, libro_actas, reg_diploma, libro, folio, snies, nombre_titulo, titulo_grado 
		FROM graduados 
		WHERE documento=' . $fila['unad11doc'] . ' AND codigoprograma=' . $fila['core09codgrados'] . '';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' Consultando Grados: ' . $sSQL . '<br>';
		}
		$tablaG = $objDBGrados->ejecutasql($sSQL);
		if ($objDBGrados->nf($tablaG) == 0) {
			$sError = 'No se encuentra registro de grado';
		} else {
			$filaG = $objDBGrados->sf($tablaG);
			$sInfoGrado = $filaG['titulo_grado'];
			$iMes = f117_NumMes($filaG['mes']);
			if ($iMes == 0) {
				$sError = 'No ha sido posible interpretar la fecha de grado {' . $filaG['mes'] . '}';
			}
			$iFechaGrado = $filaG['dia'] + ($iMes * 100) + ($filaG['ano'] * 10000);
			$bGraduado = true;
		}
	}
	if ($sError == '') {
		if ($bGraduado) {
			$sAdicional = '';
			if ($fila['core01idtipopractica'] > 0) {
				if ($fila['core01estadopractica'] <> 7) {
					$sAdicional = ', core01estadopractica=7';
				}
			}
			$sSQL = 'UPDATE core01estprograma SET core01gradofecha=' . $iFechaGrado . ', core01gradonumacta=' . (int)$filaG['acta'] . ', 
			core01gradonumlibroactas=' . (int)$filaG['libro_actas'] . ', core01gradonumfolio=' . (int)$filaG['folio'] . ', 
			core01gradonumdiploma=' . (int)$filaG['reg_diploma'] . ', core01gradoestado=21, 
			core01gradonumlibrodiplomas=' . (int)$filaG['libro'] . $sAdicional . ' WHERE core01id=' . $id01 . '';
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . ' Actualizando Grado: ' . $sSQL . '<br>';
			}
			$result = $objDB->ejecutasql($sSQL);
			list($sError, $sDebugE) = f2222_CambiaEstado($id01, $fila['core01idestado'], 10, 0, $sInfoGrado, $objDB, $iFechaGrado, $bDebug);
			$sDebug = $sDebug . $sDebugE;
		}
	}
	if ($bConDBGrados) {
		$objDBGrados->CerrarConexion();
	}
	return array($sError, $sDebug);
}
//
function f2204_IgualarConGrupos($idPeriodo, $objDB, $bDebug = false, $idContPeriodo = 0, $bCurso = '')
{
	$sError = '';
	$sDebug = '';
	$iProcesados = 0;
	$iNavs = 0;
	if ($idContPeriodo == 0) {
		$idContPeriodo = f146_Contenedor($idPeriodo, $objDB);
		if ((int)$idContPeriodo == 0) {
			$sError = 'No se ha podido encontrar el contenedor del periodo';
		}
	}
	if ($sError == '') {
		$sCondi04 = '';
		if ($bCurso != '') {
			$sCondi04 = ' AND TB.core04idcurso=' . $bCurso . '';
		}
		$sSQL = 'SHOW TABLES LIKE "core04%"';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' Total Periodo: Lista de contenedores: ' . $sSQL . '<br>';
		}
		$tablac = $objDB->ejecutasql($sSQL);
		while ($filac = $objDB->sf($tablac)) {
			$iContenedor = substr($filac[0], 16);
			if ((int)$iContenedor != 0) {
				//REVISAR LOS NAV
				$sSQL = 'UPDATE core04matricula_' . $iContenedor . ' AS TB, ofer08oferta AS T6
				SET TB.core04idnav=T6.ofer08idnav
				WHERE TB.core04peraca=' . $idPeriodo . $sCondi04 . ' AND TB.core04estado=0 AND TB.core04idgrupo>0
				AND TB.core04peraca=T6.ofer08idper_aca AND TB.core04idcurso=T6.ofer08idcurso AND T6.ofer08cead=0
				AND TB.core04idnav<>T6.ofer08idnav';
				if ($bDebug) {
					$sDebug = $sDebug . fecha_microtiempo() . ' Revisando NAVs: ' . $sSQL . '<br>';
				}
				$result = $objDB->ejecutasql($sSQL);
				$iNavs = $iNavs + $objDB->iFilasAfectadas;
				//Ahora revisar los navs de cursos externos
				$sSQL = 'UPDATE core04matricula_' . $iContenedor . ' AS TB, ofer08oferta AS T6
				SET TB.core04idnav=T6.ofer08idnav
				WHERE TB.core04peraca=' . $idPeriodo . $sCondi04 . ' AND TB.core04estado=2 
				AND TB.core04peraca=T6.ofer08idper_aca AND TB.core04idcurso=T6.ofer08idcurso AND T6.ofer08cead=0
				AND TB.core04idnav<>T6.ofer08idnav';
				if ($bDebug) {
					$sDebug = $sDebug . fecha_microtiempo() . ' Revisando NAVs de cursos externos: ' . $sSQL . '<br>';
				}
				$result = $objDB->ejecutasql($sSQL);
				$iNavs = $iNavs + $objDB->iFilasAfectadas;
				//Termina de revisar los NAVS
				// Ahora revisar los grupos
				$sSQL = 'UPDATE core04matricula_' . $iContenedor . ' AS TB, core06grupos_' . $idContPeriodo . ' AS T6
				SET TB.core04idaula=T6.core06idaula
				WHERE TB.core04peraca=' . $idPeriodo . $sCondi04 . ' 
				AND TB.core04idgrupo=T6.core06id AND TB.core04idaula<>T6.core06idaula';
				if ($bDebug) {
					$sDebug = $sDebug . fecha_microtiempo() . ' Revisando Aulas: ' . $sSQL . '<br>';
				}
				$result = $objDB->ejecutasql($sSQL);
				$iProcesados = $iProcesados + $objDB->iFilasAfectadas;
				//Por si las dudas revisar los tutores
				$sSQL = 'UPDATE core04matricula_' . $iContenedor . ' AS TB, core06grupos_' . $idContPeriodo . ' AS T6
				SET TB.core04idtutor=T6.core06idtutor
				WHERE TB.core04peraca=' . $idPeriodo . $sCondi04 . ' 
				AND TB.core04idgrupo=T6.core06id AND TB.core04idtutor<>T6.core06idtutor';
				if ($bDebug) {
					$sDebug = $sDebug . fecha_microtiempo() . ' Revisando Aulas: ' . $sSQL . '<br>';
				}
				$result = $objDB->ejecutasql($sSQL);
				$iProcesados = $iProcesados + $objDB->iFilasAfectadas;
				// Terminamos...
			}
		}
	}
	return array($iProcesados, $sError, $sDebug, $iNavs);
}
//Total de matricula...
function f2204_MatriculaXCentro($idPeriodo, $idCurso, $idZona, $idCentro, $objDB, $bDebug = false)
{
	$sDebug = '';
	$iTotal = 0;
	$iZona = 0;
	$iCentro = 0;
	$iTotalN = 0;
	$iZonaN = 0;
	$iCentroN = 0;
	$sCampos = '';
	$sAgrupar = '';
	$bConZona = false;
	$bConCentro = false;
	if ((int)$idCentro != 0) {
		$sCampos = 'core07idcead, core07idzona, ';
		$sAgrupar = ' GROUP BY core07idcead, core07idzona';
		$bConZona = true;
		$bConCentro = true;
	} else {
		if ((int)$idZona != 0) {
			$sCampos = 'core07idzona, ';
			$sAgrupar = ' GROUP BY core07idzona';
			$bConZona = true;
		}
	}
	$sSQL = 'SELECT ' . $sCampos . 'SUM(core07numnuevos) As Nuevos, SUM(core07numestudiantes) As Antiguos 
	FROM core07matriculaest
	WHERE core07idperaca=' . $idPeriodo . ' AND core07idcurso=' . $idCurso . $sAgrupar . '';
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' <b>Total Matricula</b>: ' . $sSQL . '';
	}
	$tabla = $objDB->ejecutasql($sSQL);
	while ($fila = $objDB->sf($tabla)) {
		$iNuevos = $fila['Nuevos'];
		$iAntiguos = $fila['Antiguos'];
		$iTotalN = $iTotalN + $iNuevos;
		$iTotal = $iTotal + $iAntiguos;
		if ($bConZona) {
			if ($fila['core07idzona'] == $idZona) {
				$iZonaN = $iZonaN + $iNuevos;
				$iZona = $iZona + $iAntiguos;
			}
		}
		if ($bConCentro) {
			if ($fila['core07idcead'] == $idCentro) {
				$iCentroN = $iCentroN + $iNuevos;
				$iCentro = $iCentro + $iAntiguos;
			}
		}
	}
	return array($iTotalN, $iZonaN, $iCentroN, $iTotal, $iZona, $iCentro, $sDebug);
}
//
function f2206_ActualizarTutor($idPeriodo, $idTutor, $idCurso, $idContenedor, $objDB, $bDebug = false)
{
	$sError = '';
	$sDebug = '';
	$bAnteriores = false;
	if ($idPeriodo > 951) {
		//$bAnteriores=f2206th_PeriodosExcluidos($idPeriodo);
	} else {
		$bAnteriores = true;
	}
	if ($bAnteriores) {
	}
	if ($sError == '') {
		list($sErrorA, $sDebugA) = f2402_CargarActividades($idTutor, $idPeriodo, $idCurso, $objDB, true, $bDebug);
		$sDebug = $sDebug . $sDebugA;
	}
	return array($sError, $sDebug);
}
//Purgar los grupos en 0
function f2206_PurgarGrupos($idPeriodo, $idCurso, $objDB, $bDebug = false, $idContenedor = 0)
{
	$sError = '';
	$sDebug = '';
	if ($idContenedor == 0) {
		$idContenedor = f146_Contenedor($idPeriodo, $objDB);
		if ($idContenedor == 0) {
			$sError = 'No se ha podido encontrar contenedor para el periodo ' . $idPeriodo . '';
		}
	}
	if ($sError == '') {
		//Primero vemos los cursos que no tengan estudiantes.
		$sAdd = '';
		$sAdd08 = '';
		if ($idCurso != 0) {
			$sAdd = ' AND core06idcurso=' . $idCurso . '';
			$sAdd08 = ' AND core07idcurso=' . $idCurso . '';
		}
		$sIds40 = '-99';
		$sSQL = 'SELECT core07idcurso
		FROM core07matriculaest
		WHERE core07idperaca=' . $idPeriodo . '
		GROUP BY core07idcurso
		HAVING SUM(core07numestudiantes+core07numnuevos)=0' . $sAdd08;
		$tabla = $objDB->ejecutasql($sSQL);
		while ($fila = $objDB->sf($tabla)) {
			$sIds40 = $sIds40 . ',' . $fila['core07idcurso'];
		}
		//Ahora si borramos todo lo que quede en 0
		$sTablaGrupos = 'core06grupos_' . $idContenedor;
		$sSQL = 'SELECT core06id 
		FROM ' . $sTablaGrupos . ' 
		WHERE core06peraca=' . $idPeriodo . ' AND ((core06numinscritos=0) OR (core06idcurso IN (' . $sIds40 . '))) ';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' Cantidad de grupos en 0: ' . $sSQL . '<br>';
		}
		$tabla06 = $objDB->ejecutasql($sSQL);
		while ($fila06 = $objDB->sf($tabla06)) {
			$bElimina = true;
			list($iTotal, $sErrorT, $sDebugT)=f2406_TotalizarGrupo($idPeriodo, $fila06['core06id'], $idContenedor, $objDB);
			if ($iTotal != 0){
				$bElimina = false;
				$sSQL = 'UPDATE ' . $sTablaGrupos . ' SET core06numinscritos=' . $iTotal . ' WHERE core06id=' . $fila06['core06id'] . '';
				$result = $objDB->ejecutasql($sSQL);
			}
			if ($bElimina) {
				$sSQL = 'DELETE FROM ' . $sTablaGrupos . ' WHERE core06id=' . $fila06['core06id'] . '';
				$result = $objDB->ejecutasql($sSQL);
			}
		}
	}
	return array($sError, $sDebug);
}
function f2206_TotalizarTutor($idPeriodo, $idCurso, $idTutor, $objDB, $bDebug = false, $idContenedor = 0)
{
	$sError = '';
	$sDebug = '';
	if ($idContenedor == 0) {
		$idContenedor = f146_Contenedor($idPeriodo, $objDB);
		if ($idContenedor == 0) {
			$sError = 'No se ha podido encontrar contenedor para el periodo ' . $idPeriodo . '';
		}
	}
	$ceca02numgrupos = 0;
	$ceca02numest = 0;
	if ($sError == '') {
		$sTablaGrupos = 'core06grupos_' . $idContenedor;
		$sNomTabla = 'ceca02actividadtutor_' . $idContenedor . '';
		$bTotaliza = false;
		$sCondiAdd = '';
		$sSQL = 'SELECT COUNT(1) AS NumGrupos, SUM(core06numinscritos) AS NumInscritos 
		FROM ' . $sTablaGrupos . ' 
		WHERE core06peraca=' . $idPeriodo . ' AND core06idcurso=' . $idCurso . ' AND core06idtutor=' . $idTutor . $sCondiAdd . '';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' Cantidad de grupos del profesor ' . $sSQL . '<br>';
		}
		$tabla03 = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla03) > 0) {
			$fila03 = $objDB->sf($tabla03);
			$ceca02numgrupos = $fila03['NumGrupos'];
			$ceca02numest = $fila03['NumInscritos'];
		}
		//Verificar que sea necesaria la actualizacion
		$sSQL = 'SELECT ceca02numgrupos, ceca02numest FROM ' . $sNomTabla . ' WHERE ceca02idtutor=' . $idTutor . ' AND ceca02idperaca=' . $idPeriodo . ' AND ceca02idcurso=' . $idCurso . '';
		$tabla03 = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla03) == 0) {
			if ($ceca02numest > 0) {
				list($sErrorA, $sDebugA) = f2402_CargarActividades($idTutor, $idPeriodo, $idCurso, $objDB, true, $bDebug);
				$sDebug = $sDebug . $sDebugA;
			}
		} else {
			$fila03 = $objDB->sf($tabla03);
			if ($fila03['ceca02numgrupos'] != $ceca02numgrupos) {
				$bTotaliza = true;
			}
			if ($fila03['ceca02numest'] != $ceca02numest) {
				$bTotaliza = true;
			}
		}
		if ($bTotaliza) {
			$sSQL = 'UPDATE ' . $sNomTabla . ' SET ceca02numgrupos=' . $ceca02numgrupos . ', ceca02numest=' . $ceca02numest . '
			WHERE ceca02idtutor=' . $idTutor . ' AND ceca02idperaca=' . $idPeriodo . ' AND ceca02idcurso=' . $idCurso . '';
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . ' <b>Actualizando Carga del tutor</b>: ' . $sSQL . '<br>';
			}
			$result = $objDB->ejecutasql($sSQL);
		}
	}
	return array($sError, $sDebug);
}
//Informacion del grupo.
function f2206_TituloGrupo($idPeriodo, $id06, $objDB, $bDebug = false, $idContenedor = 0)
{
	$sError = '';
	$sDebug = '';
	$sTituloGrupo = '';
	$idTutor = 0;
	$sNomTutor = '';
	if ($idContenedor == 0) {
		$idContenedor = f146_Contenedor($idPeriodo, $objDB);
		if ($idContenedor == 0) {
			$sError = 'No se ha podido encontrar contenedor para el periodo ' . $idPeriodo . '';
		}
	}
	if ($sError == '') {
		$sTablaGrupos = 'core06grupos_' . $idContenedor;
		$sSQL = 'SELECT TB.core06idtutor, TB.core06consec, T11.unad11razonsocial 
		FROM core06grupos_' . $idContenedor . ' AS TB, unad11terceros AS T11
		WHERE TB.core06id=' . $id06 . ' AND TB.core06idtutor=T11.unad11id';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			$sTituloGrupo = $fila['core06consec'];
			$idTutor = $fila['core06idtutor'];
			if ($idTutor != 0) {
				$sNomTutor = $fila['unad11razonsocial'];
			}
		}
	}
	return array($sTituloGrupo, $idTutor, $sNomTutor, $sError, $sDebug);
}
//Nota aprobatoria de un curso
function f2211_NotaAprobatoria($idCurso, $idPrograma, $idVersion, $objDB)
{
	$dRes = 3;
	return $dRes;
}
function f2216_EsEstudiante($idTercero, $idCurso, $idPeriodo, $objDB, $bDebug = false)
{
	$sError = '';
	$sDebug = '';
	list($idContTercero, $sError) = f1011_BloqueTercero($idTercero, $objDB);
	$sSQL = 'SELECT core04estado, core04idaula, core04idgrupo FROM core04matricula_' . $idContTercero . ' WHERE core04tercero=' . $idTercero . ' AND core04peraca=' . $idPeriodo . ' AND core04idcurso=' . $idCurso . '';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		switch ($fila['core04estado']) {
			case 1: //No disponible {Error de matricula, por tanto no se hace nada.}
				$sError = 'No se registra matricula';
				break;
			case 9: //Cancelado
				$sError = 'Curso no disponible por cancelaci&oacute;n';
				break;
			case 10: //Aplazado
				$sError = 'Curso no disponible por aplazamiento';
				break;
			case 13:
				$sError = 'Curso no disponible [Error 2204.5]';
				break;
			default:
				break;
		}
	} else {
		$sError = 'No se registra matricula en el curso';
	}
	return array($sError, $sDebug);
}
function f2216_FaltantesMatricula($idPeriodo, $idCurso, $objDB, $bDebug = false)
{
	$sError = '';
	$sFaltantes = '';
	$sDebug = '';
	$sDirBase = __DIR__ . '/';
	list($objDBRyC, $sDebugR) = TraerDBRyCV2($bDebug);
	if ($objDBRyC == NULL) {
		$sDebug = $sDebug . $sDebugR;
		$sError = 'No ha sido posible conectarse con RyC';
	}
	if ($sError == '') {
		$sDocExistentes = '-99';
		$sSQL = 'SELECT T11.unad11doc FROM core16actamatricula AS TB, unad11terceros AS T11 WHERE TB.core16peraca=' . $idPeriodo . ' AND (TB.core16parametros="' . $idCurso . '" OR TB.core16parametros LIKE "' . $idCurso . '@%" OR TB.core16parametros LIKE "%@' . $idCurso . '@%" OR TB.core16parametros LIKE "%@' . $idCurso . '") AND TB.core16tercero=T11.unad11id';
		$tabla = $objDB->ejecutasql($sSQL);
		while ($fila = $objDB->sf($tabla)) {
			$sDocExistentes = $sDocExistentes . ',' . $fila['unad11doc'];
		}
		$sSQL = 'SELECT TR.ins_estudiante 
		FROM registro AS TR, cursos_periodos AS T1 
		WHERE TR.ano=' . $idPeriodo . ' AND TR.ins_novedad=79 AND TR.estado="A" AND TR.ins_estudiante NOT IN (' . $sDocExistentes . ')
		AND TR.ins_curso=T1.consecutivo AND T1.cur_materia=' . $idCurso . ' AND T1.cur_edificio<>99';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' alumnos pendientes de matricula RyC: ' . $sSQL . '<br>';
		}
		$tabla = $objDBRyC->ejecutasql($sSQL);
		while ($fila = $objDBRyC->sf($tabla)) {
			$sFaltantes = $sFaltantes . $fila['ins_estudiante'] . ', ';
		}
	}
	return array($sError, $sFaltantes, $sDebug);
}
function f2216_SobrantesMatricula($idPeriodo, $idCurso, $objDB, $bDebug = false)
{
	$sError = '';
	$sSobrantes = '';
	$sDebug = '';
	$sDirBase = __DIR__ . '/';
	list($objDBRyC, $sDebugR) = TraerDBRyCV2($bDebug);
	if ($objDBRyC == NULL) {
		$sDebug = $sDebug . $sDebugR;
		$sError = 'No ha sido posible conectarse con RyC';
	}
	if ($sError == '') {
		$sDocExistentes = '-99';
		$sSQL = 'SELECT TR.ins_estudiante 
		FROM registro AS TR, cursos_periodos AS T1 
		WHERE TR.ano=' . $idPeriodo . ' AND TR.ins_novedad=79 AND TR.estado="A"
		AND TR.ins_curso=T1.consecutivo AND T1.cur_materia=' . $idCurso . ' AND T1.cur_edificio<>99';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' Alumnos existentes en RyC: ' . $sSQL . '<br>';
		}
		$tabla = $objDBRyC->ejecutasql($sSQL);
		while ($fila = $objDBRyC->sf($tabla)) {
			$sDocExistentes = $sDocExistentes . ',' . $fila['ins_estudiante'];
		}
		$sSQL = 'SELECT T11.unad11doc 
		FROM core16actamatricula AS TB, unad11terceros AS T11 
		WHERE TB.core16peraca=' . $idPeriodo . ' AND (TB.core16parametros="' . $idCurso . '" OR TB.core16parametros LIKE "' . $idCurso . '@%" OR TB.core16parametros LIKE "%@' . $idCurso . '@%" OR TB.core16parametros LIKE "%@' . $idCurso . '") AND TB.core16tercero=T11.unad11id AND T11.unad11doc NOT IN (' . $sDocExistentes . ')';
		// AND TR.ins_estudiante NOT IN ('.$sDocExistentes.')
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' Alumnos sobrantes en CORE: ' . $sSQL . '<br>';
		}
		$tabla = $objDB->ejecutasql($sSQL);
		while ($fila = $objDB->sf($tabla)) {
			$sSobrantes = $sSobrantes . $fila['unad11doc'] . ', ';
		}
	}
	return array($sError, $sSobrantes, $sDebug);
}
function f2216_NoProcesadosMatriculaCurso($idPeriodo, $idCurso, $objDB, $bDebug = false)
{
	$sError = '';
	$sNoProcesados = '';
	$sDebug = '';
	$sDirBase = __DIR__ . '/';
	if ($sError == '') {
		$tMat = 0;
		$sDocExistentes = '-99';
		$sSQL = 'SHOW TABLES LIKE "core04%"';
		$sSQLBase = '';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' Total Periodo: Lista de contenedores: ' . $sSQL . '<br>';
		}
		$tablac = $objDB->ejecutasql($sSQL);
		while ($filac = $objDB->sf($tablac)) {
			$iContenedor = substr($filac[0], 16);
			$iMatriculados = 0;
			$sSQL = 'SELECT core04tercero FROM core04matricula_' . $iContenedor . ' WHERE core04idcurso=' . $idCurso . ' AND core04peraca=' . $idPeriodo . '';
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . ' Alumnos matriculados en el curso: ' . $sSQL . '<br>';
			}
			$tabla = $objDB->ejecutasql($sSQL);
			$iMatriculados = $objDB->nf($tabla);
			$tMat = $tMat + $iMatriculados;
			while ($fila = $objDB->sf($tabla)) {
				$sDocExistentes = $sDocExistentes . ',' . $fila['core04tercero'];
			}
		}
		$sSQL = 'SELECT T11.unad11doc 
		FROM core16actamatricula AS TB, unad11terceros AS T11 
		WHERE TB.core16peraca=' . $idPeriodo . ' AND (TB.core16parametros="' . $idCurso . '" OR TB.core16parametros LIKE "' . $idCurso . '@%" OR TB.core16parametros LIKE "%@' . $idCurso . '@%" OR TB.core16parametros LIKE "%@' . $idCurso . '") AND TB.core16tercero NOT IN (' . $sDocExistentes . ') AND TB.core16tercero=T11.unad11id';
		// AND TR.ins_estudiante NOT IN ('.$sDocExistentes.')
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' Alumnos NO procesados en CORE: ' . $sSQL . '<br>';
		}
		$tabla = $objDB->ejecutasql($sSQL);
		while ($fila = $objDB->sf($tabla)) {
			$sNoProcesados = $sNoProcesados . $fila['unad11doc'] . ', ';
		}
	}
	return array($sError, $sNoProcesados, $sDebug);
}
function f2216_DistribucionMatriculaAula($idPeriodo, $idCurso, $objDB, $bDebug = false)
{
	$sError = '';
	$sInfo = '';
	$sDebug = '';
	$sDirBase = __DIR__ . '/';
	if ($sError == '') {
		$tMat = 0;
		$aTituloAula = array('[Sin Aula]', 'A', 'B', 'C', 'D', 'E', 'F', 'G');
		$aAula = array();
		$aNomAula = array();
		$iAulas = 0;
		$sDocExistentes = '-99';
		$sSQL = 'SHOW TABLES LIKE "core04%"';
		//if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Total Periodo: Lista de contenedores: '.$sSQL.'<br>';}
		$tablac = $objDB->ejecutasql($sSQL);
		$sSQL = '';
		while ($filac = $objDB->sf($tablac)) {
			$iContenedor = substr($filac[0], 16);
			if ($iContenedor != 0) {
				if ($sSQL != '') {
					$sSQL = $sSQL . ' UNION ';
				}
				$sSQL = $sSQL . 'SELECT ' . $iContenedor . ' AS Cont, core04idaula, COUNT(core04tercero) AS Total FROM core04matricula_' . $iContenedor . ' WHERE core04idcurso=' . $idCurso . ' AND core04peraca=' . $idPeriodo . ' GROUP BY core04idaula ';
				//if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Alumnos matriculados en el curso: '.$sSQL.'<br>';}
			}
		}
		$sSQL = $sSQL . 'ORDER BY core04idaula';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' Distribucion por AULA: ' . $sSQL . '<br>';
		}
		$tabla = $objDB->ejecutasql($sSQL);
		while ($fila = $objDB->sf($tabla)) {
			if (isset($aAula['a' . $fila['core04idaula']]) == 0) {
				$aNomAula[$iAulas] = 'a' . $fila['core04idaula'];
				$aAula['a' . $fila['core04idaula']]['total'] = 0;
				$aAula['a' . $fila['core04idaula']]['nombre'] = 'Aula ' . $aTituloAula[$fila['core04idaula']];
				$iAulas++;
			}
			$aAula['a' . $fila['core04idaula']]['total'] = $aAula['a' . $fila['core04idaula']]['total'] + $fila['Total'];
		}
		for ($k = 0; $k < $iAulas; $k++) {
			$sNomAula = $aNomAula[$k];
			$sInfo = $sInfo . '' . $aAula[$sNomAula]['nombre'] . ': ' . formato_numero($aAula[$sNomAula]['total']) . ', ';
		}
	}
	return array($sError, $sInfo, $sDebug);
}

function f2216_MatricularCurso($idPeriodo, $idCurso, $objDB, $bDebug = false)
{
	$sError = '';
	$sDebug = '';
	$iProcesados = 0;
	$sDirBase = __DIR__ . '/';
	list($objDBRyC, $sDebugR) = TraerDBRyCV2($bDebug);
	if ($objDBRyC == NULL) {
		$sDebug = $sDebug . $sDebugR;
		$sError = 'No ha sido posible conectarse con RyC';
	}
	if ($sError == '') {
		$objNoConformidad = new clsT203(2216);
		$sDocExistentes = '-99';
		$sSQL = 'SELECT T11.unad11doc FROM core16actamatricula AS TB, unad11terceros AS T11 WHERE TB.core16peraca=' . $idPeriodo . ' AND (TB.core16parametros="' . $idCurso . '" OR TB.core16parametros LIKE "' . $idCurso . '@%" OR TB.core16parametros LIKE "%@' . $idCurso . '@%" OR TB.core16parametros LIKE "%@' . $idCurso . '") AND TB.core16tercero=T11.unad11id';
		$tabla = $objDB->ejecutasql($sSQL);
		while ($fila = $objDB->sf($tabla)) {
			$sDocExistentes = $sDocExistentes . ',' . $fila['unad11doc'];
		}
		$sSQL = 'SELECT TR.ins_estudiante 
		FROM registro AS TR, cursos_periodos AS T1 
		WHERE TR.ano=' . $idPeriodo . ' AND TR.ins_novedad=79 AND TR.ins_estudiante NOT IN (' . $sDocExistentes . ')
		AND TR.ins_curso=T1.consecutivo AND T1.cur_materia=' . $idCurso . ' AND T1.cur_edificio<>99';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' alumnos pendientes de matricula RyC: ' . $sSQL . '<br>';
		}
		$tabla = $objDBRyC->ejecutasql($sSQL);
		if (false) {
			//include $sDirBase.'../core/ApiEdunat.php';
			//$edunat = new ApiEdunat();
			//Noviembre 9 de 2020 - Se manda a una sola funcion que procese completamente a todos los usuarios.
			/*
			while($fila=$objDBRyC->sf($tabla)){
				//$resultado = $edunat->getDataIndividual(base64_encode($idPeriodo), base64_encode($fila['ins_estudiante']));
				//$iProcesados++;
				$bAplazaSemestre=false;
				$sSQL='SELECT cod_curso, tipo, c_programas, codigocead, fecha_Matricula, t_novedad 
				FROM WSCaracterizacion___TEMP
				WHERE codigo='.$fila['ins_estudiante'].' AND periodo='.$idPeriodo.'';
				$tablav=$objDBRyC->ejecutasql($sSQL);
				if ($objDBRyC->nf($tablav)>0){
					$filav=$objDBRyC->sf($tablav);
					$aParametros=array();
					$aParametros['peraca']=$idPeriodo;
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
			*/
		}
		//---------
		while ($fila = $objDBRyC->sf($tabla)) {
			list($sErrorM, $sDebugS) = f2216_TraerMatricula($idPeriodo, $fila['ins_estudiante'], $objDB, $bDebug);
			$sError = $sError . $sErrorM;
			$sDebug = $sDebug . $sDebugS;
			//Como es curso a curso vamos a incluir aqui el llamado a reconstruir la matricula y la agenda.
			$sSQL = 'SELECT unad11id FROM unad11terceros WHERE unad11doc="' . $fila['ins_estudiante'] . '"';
			$tabla11 = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla11) == 1) {
				$fila11 = $objDB->sf($tabla11);
				$idTercero = $fila11['unad11id'];
				$sSQL = 'SELECT core16id FROM core16actamatricula WHERE core16peraca=' . $idPeriodo . ' AND core16tercero=' . $idTercero . '';
				$tabla16 = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($tabla16) == 1) {
					$fila16 = $objDB->sf($tabla16);
					list($core16procesado, $core16numcursos, $sErrorM, $sDebugP) = f2216_ProcesarMatricula($fila16['core16id'], $objDB, $objNoConformidad, $bDebug);
					$sDebug = $sDebug . $sDebugP;
					list($core16procagenda, $sErrorA, $sDebugP) = f2216_ProcesarAgenda($fila16['core16id'], $objDB, $bDebug, $objDBRyC, true);
					$sDebug = $sDebug . $sDebugP;
				}
			}
			//Termina de procesar todo...
			$iProcesados++;
		}
		// ----- Final
	}
	return array($sError, $iProcesados, $sDebug);
}
function f2216_MatricularPeriodo($idPeriodo, $objDB, $bDebug = false, $iTotal = 0)
{
	$sError = '';
	$sDebug = '';
	$iProcesados = 0;
	$bHayDBRyC = false;
	$sDirBase = __DIR__ . '/';
	list($objDBRyC, $sDebugR) = TraerDBRyCV2($bDebug);
	if ($objDBRyC == NULL) {
		$sDebug = $sDebug . $sDebugR;
		$sError = 'No ha sido posible conectarse con RyC';
	} else {
		$bHayDBRyC = true;
	}
	if ($sError == '') {
		$sDocExistentes = '-99';
		if ($iTotal == 0) {
			$sSQL = 'SELECT T11.unad11doc 
			FROM core16actamatricula AS TB, unad11terceros AS T11 
			WHERE TB.core16peraca=' . $idPeriodo . ' AND TB.core16tipomatricula IN (0, 1) AND TB.core16tercero=T11.unad11id AND T11.unad11tipodoc="CC"';
			$tabla = $objDB->ejecutasql($sSQL);
			while ($fila = $objDB->sf($tabla)) {
				$sVerifica = '_' . numeros_validar($fila['unad11doc']);
				if ($sVerifica != '_' . $fila['unad11doc']) {
					$sDocExistentes = $sDocExistentes . ',"' . $fila['unad11doc'] . '"';
				} else {
					$sDocExistentes = $sDocExistentes . ',' . $fila['unad11doc'];
				}
			}
		}
		// AND T1.cur_edificio<>99
		$sSQL = 'SELECT TR.ins_estudiante 
		FROM registro AS TR, cursos_periodos AS T1 
		WHERE TR.ano=' . $idPeriodo . ' AND TR.ins_estudiante NOT IN (' . $sDocExistentes . ')
		AND TR.ins_curso=T1.consecutivo 
		GROUP BY TR.ins_estudiante';
		if ($iTotal == 0) {
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . ' Alumnos pendientes de matricula RyC: ' . $sSQL . '<br>';
			}
		} else {
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . ' Se forza toda la matricula que viene de RyC: ' . $sSQL . '<br>';
			}
		}
		$tabla = $objDBRyC->ejecutasql($sSQL);
		//Ya no se trae por web service se trae por consulta...
		$bPorVista = true;
		while ($fila = $objDBRyC->sf($tabla)) {
			list($sErrorE, $sDebugE) = f2216_TraerMatricula($idPeriodo, $fila['ins_estudiante'], $objDB, $bDebug, 0, $objDBRyC);
			$sDebug = $sDebug . $sDebugE;
			if ($sErrorE != '') {
				if ($sError != '') {
					$sError = $sError . '<br>';
				}
				$sError = $sError . 'Documento ' . $fila['ins_estudiante'] . ': ' . $sErrorE;
			} else {
				$iProcesados++;
			}
		}
	}
	if ($bHayDBRyC) {
		$objDBRyC->CerrarConexion();
	}
	return array($sError, $iProcesados, $sDebug);
}
function f2216_PendientesXGrupo($idPeriodo, $idCurso, $objDB, $bDebug = false)
{
	$iPendienteXGrupo = 0;
	$iPendienteXTutor = 0;
	$sDebug = '';
	$sError = '';
	if ($sError == '') {
		$sSQL = 'SHOW TABLES LIKE "core04%"';
		$sCondiTutor = '';
		//if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Total Periodo: Lista de contenedores: '.$sSQL.'<br>';}
		$tablac = $objDB->ejecutasql($sSQL);
		while ($filac = $objDB->sf($tablac)) {
			$idContenedor = substr($filac[0], 16);
			if ((int)$idContenedor != 0) {
				//Cargar la matricula.
				$sSQL = 'SELECT TB.core04idgrupo, TB.core04idtutor, COUNT(1) AS NumEstudiantes
				FROM core04matricula_' . $idContenedor . ' AS TB 
				WHERE TB.core04idcurso=' . $idCurso . ' AND TB.core04peraca=' . $idPeriodo . ' AND (TB.core04idgrupo=0 OR TB.core04idtutor=0) 
				AND TB.core04estado NOT IN (1, 2, 9, 10)
				GROUP BY TB.core04idgrupo, TB.core04idtutor';
				if ($bDebug) {
					$sDebug = $sDebug . fecha_microtiempo() . ' Estudiantes sin grupo: ' . $sSQL . '<br>';
				}
				$tabla04 = $objDB->ejecutasql($sSQL);
				while ($fila04 = $objDB->sf($tabla04)) {
					if ($fila04['core04idgrupo'] == 0) {
						$iPendienteXGrupo = $iPendienteXGrupo + $fila04['NumEstudiantes'];
					} else {
						$iPendienteXTutor = $iPendienteXTutor + $fila04['NumEstudiantes'];
					}
				}
			}
		}
	}
	return array($iPendienteXGrupo, $iPendienteXTutor, $sDebug);
}
///
function f2216_TotalMatricula($idPeriodo, $idCurso, $objDB, $idTutor = '', $bDebug = false)
{
	list($iMatricula, $iExternos, $iAplazados, $iCancelados, $iAsignados, $sError, $sDebug) = f2216_TotalMatriculaV2($idPeriodo, $idCurso, $objDB, $idTutor, $bDebug);
	return array($iMatricula, $iExternos, $iAplazados, $iCancelados, $sError, $sDebug);
}
function f2216_TotalMatriculaV2($idPeriodo, $idCurso, $objDB, $idTutor = '', $bDebug = false)
{
	$iMatricula = 0;
	$iExternos = 0;
	$iAplazados = 0;
	$iCancelados = 0;
	$iAsignados = 0;
	$iSaldos = 0;
	$sError = '';
	$sDebug = '';
	if ($sError == '') {
		$sSQL = 'SHOW TABLES LIKE "core04%"';
		$sCondiTutor = '';
		if ($idTutor != '') {
			$sCondiTutor = ' AND TB.core04idtutor=' . $idTutor . '';
		}
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' Total Periodo: Lista de contenedores: ' . $sSQL . '<br>';
		}
		$tablac = $objDB->ejecutasql($sSQL);
		while ($filac = $objDB->sf($tablac)) {
			$idContenedor = substr($filac[0], 16);
			//Cargar la matricula.
			$sSQL = 'SELECT TB.core04estado, COUNT(1) AS NumEstudiantes
			FROM core04matricula_' . $idContenedor . ' AS TB 
			WHERE TB.core04idcurso=' . $idCurso . ' AND TB.core04peraca=' . $idPeriodo . $sCondiTutor . ' AND TB.core04estado<>1
			GROUP BY TB.core04estado';
			$tabla04 = $objDB->ejecutasql($sSQL);
			while ($fila04 = $objDB->sf($tabla04)) {
				switch ($fila04['core04estado']) {
					case 2: //Externos, juegan en otro lado..
						$iExternos = $iExternos + $fila04['NumEstudiantes'];
						break;
					case 9: //Cancelado
						$iCancelados = $iCancelados + $fila04['NumEstudiantes'];
						break;
					case 10: //Aplazado
						$iAplazados = $iAplazados + $fila04['NumEstudiantes'];
						break;
					default:
						$iMatricula = $iMatricula + $fila04['NumEstudiantes'];
						break;
				}
			}
		}
		if ($idTutor == '') {
			$sSQL = 'SELECT SUM(core20numestudiantes) AS Total 
			FROM core20asignacion
			WHERE core20idcurso=' . $idCurso . ' AND core20idperaca=' . $idPeriodo . '';
			$tabla = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla) > 0) {
				$fila04 = $objDB->sf($tabla);
				$iAsignados = $fila04['Total'];
			}
		}
	}
	return array($iMatricula, $iExternos, $iAplazados, $iCancelados, $iAsignados, $iSaldos, $sError, $sDebug);
}
//
function f2216_TraerMatricula($idPeriodo, $sDocumento, $objDB, $bDebug = false, $idPrograma = 0, $objDBRyC = NULL)
{
	$sError = '';
	$sDebug = '';
	$bPorVista = true;
	$bHayDBRyC = false;
	//Noviembre 18 de 2020 - Todo lo que sea periodo 760 e inferior se trae, pero se maneja como importado.
	//Todo lo de periodos inferiores a 179 se manejaba al 60 - 40
	//if ($idPeriodo==761){$bPorVista=true;}
	//Octubre 6 de 2022 - Se agrega el estado 99 para las actas que no tiene respaldo en RCONT para core16tipomatricula=0.
	if ($objDBRyC == NULL) {
		list($objDBRyC, $sDebugR) = TraerDBRyCV2($bDebug);
		if ($objDBRyC == NULL) {
			$sDebug = $sDebug . $sDebugR;
			$sError = 'No ha sido posible conectarse con RCONT';
		} else {
			$bHayDBRyC = true;
		}
	}
	if ($sError == '') {
		$sCondiMatricula = '';
		$sCodPrograma = 0;
		if ($idPrograma == 450) {
			$idPrograma = 0;
		}
		if ($idPrograma != 0){
			$sSQL = 'SELECT core09codigo FROM core09programa WHERE core09id=' . $idPrograma . '';
			$tabla09 = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla09) > 0) {
				$fila09 = $objDB->sf($tabla09);
				$sCodPrograma = $fila09['core09codigo'];
				$sCondiMatricula = ' AND c_programas=' . $sCodPrograma . '';
			} else {
				$sError = 'No se ha encontrado el programa Ref: ' . $idPrograma . '';
			}
		}
	}
	if ($sError == ''){
		$aCursos = array();
		$sSQL = 'SELECT cod_curso, tipo, c_programas, codigocead, fecha_Matricula, t_novedad
		FROM WSCaracterizacionTEMP
		WHERE codigo=' . $sDocumento . ' AND periodo=' . $idPeriodo . $sCondiMatricula . '
		ORDER BY t_novedad';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' Consultando datos de matricula en RCONT: ' . $sSQL . '<br>';
		}
		$tablav = $objDBRyC->ejecutasql($sSQL);
		if ($objDBRyC->nf($tablav) > 0) {
			$filav = $objDBRyC->sf($tablav);
			$aParametros = array();
			$aParametros['peraca'] = $idPeriodo;
			$aParametros['documento'] = $sDocumento;
			$aParametros['cead'] = $filav['codigocead'];
			$aParametros['nuevo'] = $filav['tipo'];
			$aParametros['fechamat'] = $filav['fecha_Matricula'];
			$bDirecto = false;
			if ($idPrograma != 0) {
				if ($idPeriodo > 760) {
					$bDirecto = true;
				}
			}
			if ($bDirecto) {
				$aParametros['programa'] = $sCodPrograma;
				if ($bDebug) {
					$sDebug = $sDebug . fecha_microtiempo() . ' Registra al estudiante en el programa ' . $sCodPrograma . '<br>';
				}
			} else {
				$aParametros['programa'] = $filav['c_programas'];
				//Es posible que el estudiante se reclasifique...
				$sSQL = 'SELECT core09reclasifica FROM core09programa WHERE core09id=' . $filav['c_programas'] . ' AND core09reclasifica>0';
				$tabla09 = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($tabla09) > 0) {
					$fila09 = $objDB->sf($tabla09);
					$aParametros['programa'] = $fila09['core09reclasifica'];
					if ($bDebug) {
						$sDebug = $sDebug . fecha_microtiempo() . ' Reclasifica al alumno en el programa ' . $aParametros['programa'] . '<br>';
					}
				}
			}
			$sAplazados = '';
			$sCancelados = '';
			$sCursos = '';
			$aCursos[$filav['cod_curso']] = 1;
			switch ($filav['t_novedad']) {
				case 'A': //Activo
					$sCursos = $filav['cod_curso'];
					break;
				case 'B': //Cancelacion de semestre
				case 'C': //Cancelacion de materia
					$sCancelados = $filav['cod_curso'];
					break;
				case 'D': //Aplazamiento de materia
				case 'F': //Aplazamiento de semestre.
					$sAplazados = $filav['cod_curso'];
					break;
				default:
					break;
			}
			while ($filav = $objDBRyC->sf($tablav)) {
				//$sCursos=$sCursos.'@'.$filav['cod_curso'];
				switch ($filav['t_novedad']) {
					case 'A': //Activo
						if ($sCursos != '') {
							$sCursos = $sCursos . '@';
						}
						$sCursos = $sCursos . $filav['cod_curso'];
						$aCursos[$filav['cod_curso']] = 1;
						break;
					case 'B': //Cancelacion de semestre
					case 'C': //Cancelacion de materia
						if (isset($aCursos[$filav['cod_curso']]) == 0) {
							$aCursos[$filav['cod_curso']] = 1;
							if ($sCancelados != '') {
								$sCancelados = $sCancelados . '@';
							}
							$sCancelados = $sCancelados . $filav['cod_curso'];
						}
						break;
					case 'D': //Aplazamiento de materia
					case 'F': //Aplazamiento de semestre.
						if (isset($aCursos[$filav['cod_curso']]) == 0) {
							$aCursos[$filav['cod_curso']] = 1;
							if ($sAplazados != '') {
								$sAplazados = $sAplazados . '@';
							}
							$sAplazados = $sAplazados . $filav['cod_curso'];
						}
						break;
					default:
						break;
				}
			}
			$aParametros['aplazados'] = $sAplazados;
			$aParametros['cancelados'] = $sCancelados;
			$aParametros['cursos'] = $sCursos;
			list($sError, $sDebugM) = f2216_RegistrarMatricula($aParametros, $objDB, $bDebug);
			$sDebug = $sDebug . $sDebugM;
		} else {
			$sError = 'No se encuentran datos de matricula para el documento ' . $sDocumento . ' en el periodo ' . $idPeriodo . '';
			if ($idPrograma != 0) {
				$sError = $sError . ' programa ' . $sCodPrograma . '';
			}
			//4 de Noviembre de 2021 - Se ajusta la matricula a cancelación en caso de que exista.
			//6 de Octubre de 2022 - Se pasa a matricula ANULADA - para efectos de estaadistica.
			$idTercero = 0;
			$sSQL = 'SELECT unad11id FROM unad11terceros WHERE unad11tipodoc="CC" AND unad11doc="' . $sDocumento . '"';
			$tabla = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla) > 0) {
				$fila = $objDB->sf($tabla);
				$idTercero = $fila['unad11id'];
			}
			if ($idTercero != 0) {
				if ($idPrograma != 0) {
					$sSQL = 'SELECT core16id, core16estado 
					FROM core16actamatricula 
					WHERE core16peraca=' . $idPeriodo . ' AND core16tercero=' . $idTercero . ' AND core16idprograma=' . $idPrograma . ' 
					AND core16estado<>99 AND core16tipomatricula=0';
					if ($bDebug) {
						$sDebug = $sDebug . fecha_microtiempo() . ' Verificando si existen matriculas: ' . $sSQL . '<br>';
					}
					$tabla16 = $objDB->ejecutasql($sSQL);
					if ($objDB->nf($tabla16) > 0) {
						$fila16 = $objDB->sf($tabla16);
						$iHoy = fecha_DiaMod();
						$sSQL = 'UPDATE core16actamatricula SET core16estado=99, core16procesado=' . $iHoy . ', core16aplicada=' . $iHoy . ', core16parametros="", core16cancelados="" WHERE core16id=' . $fila16['core16id'] . '';
						if ($bDebug) {
							$sDebug = $sDebug . fecha_microtiempo() . ' ANULANDO matricula del estudiante: ' . $sSQL . '<br>';
						}
						$result = $objDB->ejecutasql($sSQL);
					}
				} else {
					$sSQL = 'SELECT core16id, core16parametros, core16cancelados 
					FROM core16actamatricula 
					WHERE core16peraca=' . $idPeriodo . ' AND core16tercero=' . $idTercero . '
					AND core16estado<>99 AND core16tipomatricula=0';
					if ($bDebug) {
						$sDebug = $sDebug . fecha_microtiempo() . ' Verificando si existen matriculas: ' . $sSQL . '<br>';
					}
					$tabla16 = $objDB->ejecutasql($sSQL);
					while($fila16 = $objDB->sf($tabla16)) {
						$sCancelados = '';
						$bCambia = false;
						if ($fila16['core16cancelados'] != '') {
							$sCancelados = $fila16['core16cancelados'];
						}
						if ($fila16['core16parametros'] != '') {
							$bCambia = true;
							if ($sCancelados != '') {
								$sCancelados = $sCancelados . '@';
							}
							$sCancelados = $sCancelados . $fila16['core16parametros'];
						}
						if ($fila16['core16cancelados'] != $sCancelados) {
							$bCambia = true;
						}
						if ($bCambia) {
							$sSQL = 'UPDATE core16actamatricula SET core16procesado=0, core16aplicada=0, core16parametros="", core16cancelados="' . $sCancelados . '" WHERE core16id=' . $fila16['core16id'] . '';
							if ($bDebug) {
								$sDebug = $sDebug . fecha_microtiempo() . ' Cancelando matriculas del estudiante: ' . $sSQL . '<br>';
							}
							$result = $objDB->ejecutasql($sSQL);
						}
					}
					// Termina si no se pidio verificar el programa y se cancelaron las matriculas.
				}
			} else {
				if ($bDebug) {
					$sDebug = $sDebug . fecha_microtiempo() . ' No se encuentran datos del tercero: ' . $sSQL . '<br>';
				}
			}
		}
	}
	if ($bHayDBRyC) {
		$objDBRyC->CerrarConexion();
	}
	return array($sError, $sDebug);
}
//Ubicar el nivel dentro del programa..
function f2216_UbicarNivel($id16, $objDB, $bDebug = false)
{
	$iNivel = 0;
	$sDebug = '';
	$sError = '';
	//Averiguamos el programa, si es por semestres vemos de nivel son los cursos, si no va por avance del plan de estudios
	$sSQL = 'SELECT TB.core16idprograma, TB.core16idplanestudio, TB.core16peraca, TB.core16nivelprograma, 
	T9.core09semestres, T9.core09aplicacontinuidad, T9.core09formapromocion, T9.core09semestres, 
	TB.core16tercero, T11.unad11idtablero
	FROM core16actamatricula AS TB, core09programa AS T9, unad11terceros AS T11 
	WHERE TB.core16id=' . $id16 . ' AND TB.core16idprograma=T9.core09id AND TB.core16tercero=T11.unad11id ';
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' <b>UBICAR NIVEL</b> Info base: ' . $sSQL . '<br>';
	}
	$tabla16 = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla16) > 0) {
		$fila16 = $objDB->sf($tabla16);
		if ($fila16['core16idplanestudio'] == 0) {
			$sError = 'No se registra plan de estudios en la matricula';
		}
		$iSemestres = $fila16['core09semestres'];
		if ($iSemestres > 20) {
			$iSemestres = 20;
		}
		if ($iSemestres == 0) {
			$sError = 'No se ha definido la duraci&oacute;n del programa acad&eacute;mico.';
		}
	} else {
		$sError = 'No se ha encontrado el registro [Ref ' . $id16 . ']';
	}
	if ($sError == '') {
		if ($fila16['core09formapromocion'] == 1) {
			//Ver de que nivel son los cursos que matriculo.
			//$aCursos=array();
			//20 espacios por si las moscas.
			$aNivel = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
			$sIds40 = '-99';
			$sTabla = 'core04matricula_' . $fila16['unad11idtablero'];
			$sSQL = 'SELECT TB.core04idcurso, TB.core04resdef 
			FROM ' . $sTabla . ' AS TB 
			WHERE TB.core04tercero=' . $fila16['core16tercero'] . ' AND TB.core04peraca=' . $fila16['core16peraca'] . ' AND core04estado NOT IN (1, 9, 10)';
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . ' <b>UBICAR NIVEL</b> Cursos matriculados: ' . $sSQL . '<br>';
			}
			$tabla = $objDB->ejecutasql($sSQL);
			while ($fila = $objDB->sf($tabla)) {
				$sIds40 = $sIds40 . ',' . $fila['core04idcurso'];
				//$aCursos[$fila['core04idcurso']]=$fila['core04resdef'];
			}
			$sSQL = 'SELECT TB.core11idcurso, TB.core11nivelaplica
			FROM core11plandeestudio AS TB 
			WHERE TB.core11idversionprograma=' . $fila16['core16idplanestudio'] . ' AND TB.core11idcurso IN (' . $sIds40 . ')';
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . ' <b>UBICAR NIVEL</b> Plan de estudio: ' . $sSQL . '<br>';
			}
			$tabla = $objDB->ejecutasql($sSQL);
			while ($fila = $objDB->sf($tabla)) {
				if ($fila['core11nivelaplica'] < 21) {
					$aNivel[$fila['core11nivelaplica']] = $aNivel[$fila['core11nivelaplica']] + 1;
				}
			}
			//Ahora si ver cual es el nivel inferior.
			for ($k = 1; $k < 21; $k++) {
				if ($aNivel[$k] != 0) {
					$iNivel = $k;
					$k = 21;
				}
			}
		}
	}
	if ($sError == '') {
		if ($iNivel != $fila16['core16nivelprograma']) {
			$sSQL = 'UPDATE core16actamatricula SET core16nivelprograma=' . $iNivel . ' WHERE core16id=' . $id16 . '';
			$result = $objDB->ejecutasql($sSQL);
		}
	}
	return array($iNivel, $sError, $sDebug);
}
//Cambios de estado.
function f2222_CambiaEstado($id01, $core22idestadoorigen, $core22idestadodestino, $core22idtercero, $core22anotacion, $objDB, $iFechaReg = 0, $bDebug = false)
{
	$sError = '';
	$sDebug = '';
	$sSQL = 'SELECT core01idestado, core01peracainicial, core01avanceplanest FROM core01estprograma WHERE core01id=' . $id01 . '';
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . '<b>Cambio de estado</b> Consultando estudiante ' . $sSQL . '<br>';
	}
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila1 = $objDB->sf($tabla);
		if ($fila1['core01idestado'] != $core22idestadoorigen) {
			$sError = 'No ha sido posible comprobar la validez del cambio de estado para el estudiante.';
		}
	} else {
		$sError = 'No se ha encontrado el registro de estudiante Ref ' . $id01 . '';
	}
	if ($sError == '') {
		if ($core22idestadoorigen == $core22idestadodestino) {
			$sError = 'No se detecta un cambio de estado.';
		}
	}
	$bRetirarPosteriores = false;
	if ($sError == '') {
		switch ($core22idestadodestino) {
			case 7: // Graduando
				$bRetirarPosteriores = true;
				break;
			case 9: // Se retira, si lleva mas de 2 años es desercion.
				break;
			case 10: // Graduado
			case 11: // Transicion
			case 12: // Cambio de programa
				$bRetirarPosteriores = true;
				break;
		}
	}
	if ($bRetirarPosteriores) {
		$sSQL = 'SELECT core22id, core22idestadoorigen FROM core22gradohistorialest 
		WHERE core22idestprograma=' . $id01 . ' AND core22fecha>' . $iFechaReg . '
		ORDER BY core22fecha DESC';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$sIds = '-99';
			$bPrimera = true;
			while ($fila = $objDB->sf($tabla)) {
				if ($bPrimera) {
					$core22idestadoorigen = $fila['core22idestadoorigen'];
					$bPrimera = false;
				}
				$sIds = $sIds . ',' . $fila['core22id'];
			}
			$sSQL = 'DELETE FROM core22gradohistorialest WHERE core22id IN (' . $sIds . ')';
			$result = $objDB->ejecutasql($sSQL);
		}
	}
	if ($sError == '') {
		$core22anotacion = cadena_reemplazar($core22anotacion, '"', '');
		$core22consec = tabla_consecutivo('core22gradohistorialest', 'core22consec', 'core22idestprograma=' . $id01 . '', $objDB);
		$core22id = tabla_consecutivo('core22gradohistorialest', 'core22id', '', $objDB);
		if ($iFechaReg == 0) {
			$iFechaReg = fecha_DiaMod();
		}
		$scampos = 'core22idestprograma, core22consec, core22id, core22idestadoorigen, core22idestadodestino, core22idtercero, 
		core22fecha, core22anotacion';
		$svalores = '' . $id01 . ', ' . $core22consec . ', ' . $core22id . ', ' . $core22idestadoorigen . ', ' . $core22idestadodestino . ', ' . $core22idtercero . ', 
		' . $iFechaReg . ', "' . $core22anotacion . '"';
		$sSQL = 'INSERT INTO core22gradohistorialest (' . $scampos . ') VALUES (' . $svalores . ');';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . '<b>Cambio de estado</b> Insertando historial ' . $sSQL . '<br>';
		}
		$result = $objDB->ejecutasql($sSQL);
		if ($result == false) {
			$sError = 'Error al intentar registrar el cambio de estado, si el problema persiste informe al administrador del sistema.<!-- ' . $sSQL . ' -->';
		} else {
			$sSQL = 'UPDATE core01estprograma SET core01idestado=' . $core22idestadodestino . ' WHERE core01id=' . $id01 . '';
			$result = $objDB->ejecutasql($sSQL);
		}
	}
	return array($sError, $sDebug);
}
//Cerrar las convocatorias a prueba de estado..
function f2230_CerrarConvocatorias($objDB, $bDebug = false)
{
	$sError = '';
	$sDebug = '';
	$iHoy = fecha_DiaMod();
	$sSQL = 'UPDATE corf29convpruebaest SET corf29estado=7 WHERE corf29fechacierreinsc<' . $iHoy . ' AND corf29estado=5';
	$result = $objDB->ejecutasql($sSQL);
	return array($sError, $sDebug);
}
//
function f2240_ActualizarDirectorEnGrupos($idPeriodo, $idCurso, $objDB, $bDebug = false)
{
	$sError = '';
	$sDebug = '';
	$idAcompana = 0;
	if ($idPeriodo < 761) {
		$sError = 'Este proceso no se encuentra disponible para el periodo ' . $idPeriodo . '.';
	}
	if ($sError == '') {
		$sSQL = 'SELECT ofer08idacomanamento, ofer08c2fechacierre FROM ofer08oferta WHERE ofer08idper_aca=' . $idPeriodo . ' AND ofer08idcurso=' . $idCurso . ' AND ofer08cead=0';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			$idAcompana = $fila['ofer08idacomanamento'];
			if ($fila['ofer08c2fechacierre'] != 0) {
				$sError = 'El curso ya tiene cierre acad&eacute;mico.';
			}
		} else {
			$sError = 'No se ha encontrado la oferta del curso ' . $idCurso . ' en el periodo ' . $idPeriodo . '';
		}
	}
	if ($sError == '') {
		$idContPeriodo = f146_Contenedor($idPeriodo, $objDB);
		$sSQL = 'UPDATE core06grupos_' . $idContPeriodo . ' SET core06iddirector=' . $idAcompana . ' WHERE core06peraca=' . $idPeriodo . ' AND core06idcurso=' . $idCurso . '';
		$result = $objDB->ejecutasql($sSQL);
	}
	return array($sError, $sDebug);
}
function f2247_AccesoAdmin($idTercero, $objDB, $bDebug = false, $idPeriodo = 0, $idCurso = 0)
{
	$sRes = '-99';
	$sDebug = '';
	$sId38 = '-99';
	//Quitamos los espacios de acompañamiento academico.
	list($sIds08, $sDebug) = f2247_EspaciosTercero($idTercero, $objDB, $bDebug, $idPeriodo);
	//Debemos quitar los espacios donde es estudiante.
	if ($sIds08 == '') {
		$sIds08 = '-99';
	}
	//Ver si tiene un acceso global.
	//Es parte de un grupo y puede tener acceso global.
	$sTablas38 = '';
	$sCondi38 = '';
	$sCondi20 = '';
	$sCondi08 = '';
	$sCondi12 = '';
	$sCondi33 = '';
	if ($idPeriodo != 0) {
		$sTablas38 = ', ofer08oferta AS T8 ';
		$sCondi38 = ' AND TB.ofer38idoferta=T8.ofer08id AND T8.ofer08idper_aca=' . $idPeriodo . ' ';
		$sCondi20 = ' AND TB.core20idperaca=' . $idPeriodo . ' ';
		$sCondi08 = ' AND T8.ofer08idper_aca=' . $idPeriodo . ' ';
		$sCondi12 = '';
		$sCondi33 = ' AND TB.plab33idperiodo=' . $idPeriodo . '';
	}
	$sSQL = 'SELECT TB.ofer38idoferta 
	FROM ofer38matricula AS TB ' . $sTablas38 . ' 
	WHERE TB.ofer38idtercero=' . $idTercero . ' AND TB.ofer38idoferta NOT IN (' . $sIds08 . ') AND TB.ofer38activo="S" ' . $sCondi38 . ' 
	GROUP BY TB.ofer38idoferta';
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' <b>ESPACIOS ADMINISTRATIVOS</b> Acceso global: ' . $sSQL . '<br>';
	}
	$tabla = $objDB->ejecutasql($sSQL);
	while ($fila = $objDB->sf($tabla)) {
		$sId38 = $sId38 . ',' . $fila['ofer38idoferta'];
	}
	//Ya se cargo las ofertas a donde tiene matricula directa, 
	//Donde es E-Monitor
	$sSQL = 'SELECT T8.ofer08id 
	FROM plab33emoncurso AS TB, ofer08oferta AS T8 
	WHERE TB.plab33idmonitor=' . $idTercero . ' AND plab33activo=1 ' . $sCondi33 . ' 
	AND TB.plab33idperiodo=T8.ofer08idper_aca AND TB.plab33idcurso=T8.ofer08idcurso AND T8.ofer08cead=0 AND T8.ofer08estadooferta=1';
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' <b>ESPACIOS ADMINISTRATIVOS</b>: E-Monitor: ' . $sSQL . '<br>';
	}
	$tabla = $objDB->ejecutasql($sSQL);
	while ($fila = $objDB->sf($tabla)) {
		$sId38 = $sId38 . ',' . $fila['ofer08id'];
	}
	//ahora las ofertas donde es tutor.
	$sSQL = 'SELECT T8.ofer08id
	FROM core20asignacion AS TB, exte02per_aca AS T2, ofer08oferta AS T8 
	WHERE TB.core20idtutor=' . $idTercero . $sCondi20 . ' AND ((TB.core20idperaca<951) OR (TB.core20idperaca>950 AND (TB.core20numestudiantes+TB.core20numestaplicados)>0)) 
	AND TB.core20idperaca=T2.exte02id AND T2.exte02vigente="S" 
	AND TB.core20idperaca=T8.ofer08idper_aca AND TB.core20idcurso=T8.ofer08idcurso AND T8.ofer08cead=0 AND T8.ofer08estadooferta=1';
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' <b>ESPACIOS ADMINISTRATIVOS</b>: Tutor: ' . $sSQL . '<br>';
	}
	$tabla = $objDB->ejecutasql($sSQL);
	while ($fila = $objDB->sf($tabla)) {
		$sId38 = $sId38 . ',' . $fila['ofer08id'];
	}
	//Ahora donde es director de curso.
	$sSQL = 'SELECT T8.ofer08id
	FROM ofer08oferta AS T8, exte02per_aca AS T2 
	WHERE T8.ofer08idacomanamento=' . $idTercero . $sCondi08 . ' AND T8.ofer08estadooferta=1 AND T8.ofer08idper_aca=T2.exte02id AND T2.exte02vigente="S"';
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' <b>ESPACIOS ADMINISTRATIVOS</b>: Director de curso: ' . $sSQL . '<br>';
	}
	$tabla = $objDB->ejecutasql($sSQL);
	while ($fila = $objDB->sf($tabla)) {
		$sId38 = $sId38 . ',' . $fila['ofer08id'];
	}
	//Ahora donde es director de curso suplente.
	$sSQL = 'SELECT T8.ofer08id
	FROM corf12directores AS TB, ofer08oferta AS T8, exte02per_aca AS T2 
	WHERE TB.corf12idtercero=' . $idTercero . ' AND TB.corf12activo=1 
	AND TB.corf12idoferta=T8.ofer08id AND T8.ofer08estadooferta=1 ' . $sCondi08 . ' 
	AND T8.ofer08idper_aca=T2.exte02id AND T2.exte02vigente="S"';
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' <b>ESPACIOS ADMINISTRATIVOS</b>: Director de curso suplente: ' . $sSQL . '<br>';
	}
	$tabla = $objDB->ejecutasql($sSQL);
	while ($fila = $objDB->sf($tabla)) {
		$sId38 = $sId38 . ',' . $fila['ofer08id'];
	}
	//Ahora lo que hace como secretario academico.
	$sIds12 = '-99';
	$sSQL = 'SELECT TB.core12id 
	FROM core12escuela AS TB
	WHERE ((TB.core12iddecano=' . $idTercero . ') OR (TB.core12idadministrador=' . $idTercero . '))';
	$tabla = $objDB->ejecutasql($sSQL);
	while ($fila = $objDB->sf($tabla)) {
		$sIds12 = $sIds12 . ',' . $fila['core12id'];
	}
	if ($sIds12 != '-99') {
		$sSQL = 'SELECT T8.ofer08id
		FROM ofer08oferta AS T8, exte02per_aca AS T2 
		WHERE T8.ofer08idescuela IN (' . $sIds12 . ') AND T8.ofer08estadooferta=1 ' . $sCondi08 . ' 
		AND T8.ofer08idper_aca=T2.exte02id AND T2.exte02vigente="S"';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' <b>ESPACIOS ADMINISTRATIVOS</b>: Secretario academico o decano: ' . $sSQL . '<br>';
		}
		$tabla = $objDB->ejecutasql($sSQL);
		while ($fila = $objDB->sf($tabla)) {
			$sId38 = $sId38 . ',' . $fila['ofer08id'];
		}
	}
	//Ahora lo que hace como lider de programa
	$sIds09 = '-99';
	$sSQL = 'SELECT TB.core09id 
	FROM core09programa AS TB
	WHERE TB.core09iddirector=' . $idTercero . '';
	$tabla = $objDB->ejecutasql($sSQL);
	while ($fila = $objDB->sf($tabla)) {
		$sIds09 = $sIds09 . ',' . $fila['core09id'];
	}
	if ($sIds09 != '-99') {
		$sSQL = 'SELECT T8.ofer08id
		FROM ofer08oferta AS T8, exte02per_aca AS T2 
		WHERE T8.ofer08idprograma IN (' . $sIds09 . ') AND T8.ofer08estadooferta=1 ' . $sCondi08 . ' 
		AND T8.ofer08idper_aca=T2.exte02id AND T2.exte02vigente="S"';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' <b>ESPACIOS ADMINISTRATIVOS</b>: Lider de programa: ' . $sSQL . '<br>';
		}
		$tabla = $objDB->ejecutasql($sSQL);
		while ($fila = $objDB->sf($tabla)) {
			$sId38 = $sId38 . ',' . $fila['ofer08id'];
		}
	}
	//Ahora si verificar y lo que quede es.
	if ($sId38 != '-99') {
		$sSQL = 'SELECT TB.ofer08id 
		FROM ofer08oferta AS TB, exte02per_aca AS T2, unad39nav AS T5 
		WHERE TB.ofer08id IN (' . $sId38 . ') AND TB.ofer08idper_aca=T2.exte02id AND T2.exte02vigente="S"
		AND TB.ofer08idnav=T5.unad39id AND T5.unad39activo="S"';
		$tabla = $objDB->ejecutasql($sSQL);
		while ($fila = $objDB->sf($tabla)) {
			$sRes = $sRes . ',' . $fila['ofer08id'];
		}
	}
	if ($sRes == '-99') {
		$sRes = '';
	}
	return array($sRes, $sDebug);
}
function f2247_EspaciosTercero($idTercero, $objDB, $bDebug = false, $idPeriodo = 0)
{
	$sRes = '-99';
	$sDebug = '';
	//Vamos a devolver los id de oferta donde esta matriculado siempre que estos sean espacios de acompañamiento.
	$sId38 = '-99';
	$sTablas38 = '';
	$sCondi38 = '';
	if ($idPeriodo != 0) {
		$sTablas38 = ', ofer08oferta AS T8';
		$sCondi38 = ' AND TB.ofer38idoferta=T8.ofer08id AND T8.ofer08idper_aca=' . $idPeriodo . '';
	}
	$sSQL = 'SELECT TB.ofer38idoferta 
	FROM ofer38matricula AS TB ' . $sTablas38 . ' 
	WHERE TB.ofer38idtercero=' . $idTercero . ' AND TB.ofer38idrol IN (5,6) AND TB.ofer38activo="S" ' . $sCondi38 . '
	GROUP BY TB.ofer38idoferta';
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' <b>Espacios de Acompanamento Academico</b> Matricula directa: ' . $sSQL . '<br>';
	}
	$tabla = $objDB->ejecutasql($sSQL);
	while ($fila = $objDB->sf($tabla)) {
		$sId38 = $sId38 . ',' . $fila['ofer38idoferta'];
	}
	if ($sId38 != '-99') {
		//Se reporta la oferta de uso 5 - Entorno de Acompañamiento Académico y Espacios COVID19
		//28 de Oct de 2021 - Se agrega parametro adicional para controlar que el nav este activo.
		$sSQL = 'SELECT TB.ofer08id 
		FROM ofer08oferta AS TB, exte02per_aca AS T2, unad40curso AS T4, unad39nav AS T5 
		WHERE TB.ofer08id IN (' . $sId38 . ') AND TB.ofer08estadooferta=1 AND TB.ofer08idper_aca=T2.exte02id AND T2.exte02vigente="S" 
		AND TB.ofer08idcurso=T4.unad40id AND T4.unad40tipouso IN (5, 11)
		AND TB.ofer08idnav=T5.unad39id AND T5.unad39activo="S"';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' <b>Espacios de Acompanamento Academico</b>: ' . $sSQL . '<br>';
		}
		$tabla = $objDB->ejecutasql($sSQL);
		while ($fila = $objDB->sf($tabla)) {
			$sRes = $sRes . ',' . $fila['ofer08id'];
		}
	}
	if ($sRes == '-99') {
		$sRes = '';
	}
	return array($sRes, $sDebug);
}
function f2271_CambiaEstado($core71id, $core76idestadofin, $core76detalle, $objDB, $bDebug)
{
	$sError = '';
	$sDebug = '';
	$core76diasterminos = -1;
	require './app.php';
	$sSQL = 'SELECT core71estado, core71idclasehomol, core71idestudiante, core71idresponsableestudio FROM core71homolsolicitud WHERE core71id=' . $core71id . '';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$core71estado = $fila['core71estado'];
		if ($core71estado == $core76idestadofin) {
			$sError = 'La solicitud ya ha cambiado de estado.';
		}
	} else {
		$sError = 'No se encuentra la solicitud Ref: ' . $core71id . '';
	}
	if ($sError == '') {
		switch ($core71estado) {
			case 1: //En solicitud. - Debe haber al menos un curso solicitado.
				$sSQL = 'SELECT 1 FROM core73homolcurso WHERE core73idsolicitudhomol=' . $core71id . '';
				$tabla = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($tabla) == 0) {
					$sError = 'No se han agregado cursos a la solicitud.';
				}
				break;
		}
	}
	if ($sError == '') {
		$core76idusuario = $_SESSION['unad_id_tercero'];
		$core76detalle = htmlspecialchars($core76detalle);
		$core76fecha = fecha_DiaMod();
		$sCampos2271 = 'core76idsolicitudhomol, core76consec, core76id, core76idestadoorigen, core76idestadofin, 
		core76idusuario, core76detalle, core76fecha, core76diasterminos';
		$core76consec = tabla_consecutivo('core76homolcambioest', 'core76consec', 'core76idsolicitudhomol=' . $core71id . '', $objDB);
		$core76id = tabla_consecutivo('core76homolcambioest', 'core76id', '', $objDB);
		$sValores2271 = '' . $core71id . ', ' . $core76consec . ', ' . $core76id . ', ' . $core71estado . ', ' . $core76idestadofin . ', 
		' . $core76idusuario . ', "' . $core76detalle . '", ' . $core76fecha . ', ' . $core76diasterminos . '';
		if ($APP->utf8 == 1) {
			$sSQL = 'INSERT INTO core76homolcambioest (' . $sCampos2271 . ') VALUES (' . utf8_encode($sValores2271) . ');';
		} else {
			$sSQL = 'INSERT INTO core76homolcambioest (' . $sCampos2271 . ') VALUES (' . $sValores2271 . ');';
		}
		$result = $objDB->ejecutasql($sSQL);
		if ($result == false) {
			$sError = 'Falla al intentar registrar el cambio de estado. <!-- ' . $sSQL . ' -->';
		} else {
			$sSQL = 'UPDATE core71homolsolicitud SET core71estado=' . $core76idestadofin . ' WHERE core71id=' . $core71id . '';
			$result = $objDB->ejecutasql($sSQL);
		}
	}
	return array($sError, $sDebug);
}
function f2271_NotificarSolicitud($core71id, $objDB, $bDebug)
{
	$sError = '';
	$sDebug = '';
	$sSQL = 'SELECT core71estado, core71idclasehomol, core71idestudiante, core71idresponsableestudio FROM core71homolsolicitud WHERE core71id=' . $core71id . '';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
	}
	return array($sError, $sDebug);
}
//Soporte de la aplicación
function soporte_core()
{
	return 'soporte.campus@unad.edu.co';
}
