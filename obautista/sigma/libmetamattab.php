<?php
class cls3655Registro
{
	var $plan44idescuela = 0;
	var $plan44idprograma = 0;
	var $plan44idciclo = 0;
	var $plan44idbloque = 0;
	var $plan44idzona = 0;
	var $plan44idcentro = 0;
	var $iNuevos = 0;
	var $iAntiguos = 0;
	var $iMatNuevos = 0;
	var $iMatAntiguos = 0;
}
class cls3655Tablero
{
	var $bvigencia = '';
	var $bidciclo = '';
	var $bidbloque = '';
	var $bidnivelforma = '';
	var $bidprograma = '';
	var $bidzona = '';
	var $bidcentro = '';
	var $bidescuela = '';


    var $aEscuela = array();
	var $iNumEscuela = 0;
	var $aPrograma = array();
	var $iNumProgramas = 0;
	var $aZonas = array();
	var $iNumZonas = 0;
	var $aDatos = array();
	var $iNumDatos = 0;
	function TraerDatos($objDB, $bDebug = false)
	{
		$sError = '';
		$sDebug = '';

		$sIds43 = '-99';
		if ((int)$this->bvigencia == 0) {
			$sError = 'No se ha seleccionado la vigencia.';
			return array($sError, $sDebug);
		}

		$sCondi = '';
		if ((int)$this->bidescuela != 0) {
			$sCondi = ' AND plan43idescuela=' . $this->bidescuela . '';
		}

		$sSQL = 'SELECT plan43id FROM plan43metaversion WHERE plan43idvigencia=' . $this->bvigencia . ' AND plan43estado IN (3,4,5,7) ' . $sCondi;
		$tabla = $objDB->ejecutasql($sSQL);
		while ($fila = $objDB->sf($tabla)) {
			$sIds43 = $sIds43 . ',' . $fila['plan43id'];
		}

		$sSQLadd = '';
		$sSQLadd1 = '';

		if ((int)$this->bidbloque != 0) {
			$sSQLadd1 = 'TB.plan44idbloque=' . $this->bidbloque . ' AND ';
		} else {
			if ((int)$this->bidciclo != 0) {
				$sSQLadd1 = 'TB.plan44idciclo=' . $this->bidciclo . ' AND ';
			}
		}
		$bHayEscuela = false;
		$bHayPrograma = false;
		if ((int)$this->bidprograma != 0) {
			$bHayPrograma = true;
			$sSQLadd1 = $sSQLadd1 . 'TB.plan44idprograma=' . $this->bidciclo . ' AND ';
		} else {
			if ((int)$this->bidescuela != 0) {
				$bHayEscuela = true;
				$sSQLadd1 = $sSQLadd1 . 'TB.plan44idescuela=' . $this->bidescuela . ' AND ';
			}
		}
		if (!$bHayPrograma) {
			switch ($this->bidnivelforma) {
				case '':
					break;
				default:
					$sIds9 = '-99';
					$sCondi = '';
					if ($bHayEscuela) {
						$sCondi = ' AND core09idescuela=' . $this->bidescuela . '';
					}
					$sSQL = 'SELECT core09idsnies 
					FROM core09programa
					WHERE cara09nivelformacion=' . $this->bidnivelforma . $sCondi . ' AND core09idsnies>0';
					$tabla = $objDB->ejecutasql($sSQL);
					while ($fila = $objDB->sf($tabla)) {
						$sIds9 = $sIds9 . ',' . $fila['core09idsnies'];
					}
					$sSQLadd1 = $sSQLadd1 . 'TB.plan44idprograma IN (' . $sIds9 . ') AND ';
					break;
			}
		}
		if ((int)$this->bidcentro != 0) {
			$sSQLadd1 = $sSQLadd1 . 'TB.plan44idcentro=' . $this->bidcentro . ' AND ';
		} else {
			if ((int)$this->bidzona != 0) {
				$sSQLadd1 = $sSQLadd1 . 'TB.plan44idzona=' . $this->bidzona . ' AND ';
			}
		}
		$this->iNumDatos = 0;
		if ($sError == '') {
			// Cargar un totalizado por escuela
			$sSQL = 'SELECT plan44idescuela, plan44idprograma, plan44idzona, plan44idcentro, plan44idciclo, plan44idbloque, 
			SUM(plan44proy_nuevos_conc) AS Nuevos, SUM(plan44proy_ant_conc) AS Antiguos, 
			SUM(plan44ejec_nuevos) AS EjeNuevos, SUM(plan44ejec_ant) AS EjeAntiguos
			FROM plan44metamat AS TB 
			WHERE ' . $sSQLadd1 . ' plan44tiporegistro=3 AND plan44idversion IN (' . $sIds43 . ') AND plan44estado IN (' . f3644_EstadosAcumulan() . ') 
			AND plan44idbloque<>0 AND (plan44proy_nuevos_conc+plan44proy_ant_conc)>0 ' . $sSQLadd . '
			GROUP BY plan44idescuela, plan44idprograma, plan44idzona, plan44idcentro, plan44idciclo, plan44idbloque';
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . ' <b>Tablero de metas</b>: Totalizado base: ' . $sSQL . '<br>';
			}
			$tabla44 = $objDB->ejecutasql($sSQL);
			while ($fila44 = $objDB->sf($tabla44)) {
				$objReg = new cls3655Registro();
				$objReg->plan44idescuela = $fila44['plan44idescuela'];
				$objReg->plan44idprograma = $fila44['plan44idprograma'];
				$objReg->plan44idzona = $fila44['plan44idzona'];
				$objReg->plan44idcentro = $fila44['plan44idcentro'];
				$objReg->plan44idciclo = $fila44['plan44idciclo'];
				$objReg->plan44idbloque = $fila44['plan44idbloque'];
				$objReg->iNuevos = $fila44['Nuevos'];
				$objReg->iAntiguos = $fila44['Antiguos'];
				$objReg->iMatNuevos = $fila44['EjeNuevos'];
				$objReg->iMatAntiguos = $fila44['EjeAntiguos'];
				$this->aDatos[$this->iNumDatos] = $objReg;
				$this->iNumDatos++;
			}
		}
		return array($sError, $sDebug);
	}
	function TotalEscuela($plan44idescuela, $plan44idciclo, $plan44idbloque) {
		$iNuevos = 0;
		$iAntiguos = 0;
		for ($k = 0; $k < $this->iNumEscuela; $k++) {
			$bEntra = false;
			$objReg = $this->aEscuela[$k];
			//Primer filtro solo el programa
			if ($objReg->plan44idescuela == $plan44idescuela) {
				$bEntra = true;
			}
			if ($bEntra) {
				if ($plan44idciclo == 0) {
					// Todos
				} else {
					if ($plan44idciclo == $objReg->plan44idciclo) {
						if ($plan44idbloque == 0) {
							//Cualquier bloque
						} else {
							//Solo el bloque
							if ($plan44idbloque != $objReg->plan44idbloque) {
								$bEntra = false;
							}
						}
					} else {
						$bEntra = false; // No es del ciclo
					}
				}
			}
			if ($bEntra) {
				$iNuevos = $iNuevos + $objReg->iNuevos;
				$iAntiguos = $iAntiguos + $objReg->iAntiguos;
			}
		}
		return array($iNuevos, $iAntiguos);
	}
	function TotalPrograma($plan44idprograma, $plan44idciclo, $plan44idbloque) {
		$iNuevos = 0;
		$iAntiguos = 0;
		for ($k = 0; $k < $this->iNumProgramas; $k++) {
			$bEntra = false;
			$objReg = $this->aPrograma[$k];
			//Primer filtro solo el programa
			if ($objReg->plan44idprograma == $plan44idprograma) {
				$bEntra = true;
			}
			if ($bEntra) {
				if ($plan44idciclo == 0) {
					// Todos
				} else {
					if ($plan44idciclo == $objReg->plan44idciclo) {
						if ($plan44idbloque == 0) {
							//Cualquier bloque
						} else {
							//Solo el bloque
							if ($plan44idbloque != $objReg->plan44idbloque) {
								$bEntra = false;
							}
						}
					} else {
						$bEntra = false; // No es del ciclo
					}
				}
			}
			if ($bEntra) {
				$iNuevos = $iNuevos + $objReg->iNuevos;
				$iAntiguos = $iAntiguos + $objReg->iAntiguos;
			}
		}
		return array($iNuevos, $iAntiguos);
	}
	function TotalZonaPrograma($plan44idzona, $plan44idprograma, $plan44idciclo, $plan44idbloque) {
		$iNuevos = 0;
		$iAntiguos = 0;
		for ($k = 0; $k < $this->iNumZonas; $k++) {
			$bEntra = false;
			$objReg = $this->aZonas[$k];
			//Primer filtro solo el programa
			if ($objReg->plan44idprograma == $plan44idprograma) {
				if ($objReg->plan44idzona == $plan44idzona) {
					$bEntra = true;
				}
			}
			if ($bEntra) {
				if ($plan44idciclo == 0) {
					// Todos
				} else {
					if ($plan44idciclo == $objReg->plan44idciclo) {
						if ($plan44idbloque == 0) {
							//Cualquier bloque
						} else {
							//Solo el bloque
							if ($plan44idbloque != $objReg->plan44idbloque) {
								$bEntra = false;
							}
						}
					} else {
						$bEntra = false; // No es del ciclo
					}
				}
			}
			if ($bEntra) {
				$iNuevos = $iNuevos + $objReg->iNuevos;
				$iAntiguos = $iAntiguos + $objReg->iAntiguos;
			}
		}
		return array($iNuevos, $iAntiguos);
	}
}

function f3644_EstadosAcumulan()
{
	return '4, 5, 7';
}
