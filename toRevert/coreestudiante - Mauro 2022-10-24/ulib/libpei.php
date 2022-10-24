<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2019 - 2020 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Version 2.25.0 sábado, 20 de junio de 2020
*/

/** Archivo librai.php.
 * @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
 * @date lunes, 24 de junio de 2019
 */
class cls2203_rpdei
{
	var $id03 = 0;
	var $idPeriodoPrem = 0;
	var $iEstado = 0;
	var $iFormaNota = 0;
	var $iNivel = 0;
	var $iNota75 = 0;
	var $iNota25 = 0;
	var $iNumCreAprobados = 0;
	var $iNumCreditos = 0;
	var $iTipoCurso = -1;
	var $iTieneEquivalentes = 0;
	var $iObligatorio = 0;
	var $sCodCurso = '';
	var $sCodEquivalente = '';
	var $sNomCurso = '';
	var $sPrerequisitos = '';
	var $sTitulo = '';
	/*
0	Básico	1	1
1	Específico	1	2
5	Electivo Básico Común	0	3
6	Electivo Disciplinar Común	0	4
2	Electivo Disciplinar Específico	1	5
4	Electivo Disciplinar Complementario	1	6
3	Requisito de grado	1	7
7	Curso Libre	0	8
8	No Categorizado	0	9
9   Opcion de grado.
*/
	var $iPeso = 0;
	function __construct($sCodCurso, $sTitulo, $sNomCurso, $iTipoCurso, $iNumCreditos, $id03, $idPeriodoPrem, $iTieneEquivalentes)
	{
		$this->sCodCurso = $sCodCurso;
		$this->sTitulo = $sTitulo;
		$this->sCodEquivalente = $sCodCurso;
		$this->sNomCurso = $sNomCurso;
		$this->iNumCreditos = $iNumCreditos;
		$this->iTipoCurso = $iTipoCurso;
		$this->id03 = $id03;
		$this->idPeriodoPrem = $idPeriodoPrem;
		$this->iTieneEquivalentes = $iTieneEquivalentes;
		switch ($iTipoCurso) {
			case 0:
			case 1:
			case 2:
			case 4:
			case 5:
			case 6:
				$this->iPeso = $iNumCreditos;
				break;
			case 3:
				$this->iPeso = 1;
				break;
			default:
				$this->iPeso = 0;
				break;
		}
	}
}

class unad_pei
{
	var $sIdioma = 'es';

	var $idEstudiantePrograma = 0;

	var $iCampoCredBasicos = 0;
	var $iCampoCredEspecificos = 0;
	var $iCampoCredElectivosBComun = 0;
	var $iCampoCredElectivosDComun = 0;
	var $iCampoCredElectivosDEsp = 0;
	var $iCampoCredElectivosDComp = 0;

	var $iJuegoCampos = 0;

	var $iNumCredBasicos = 0;
	var $iNumCredEspecificos = 0;
	var $iNumCredElectivosBComun = 0;
	var $iNumCredElectivosDComun = 0;
	var $iNumCredElectivosDEsp = 0;
	var $iNumCredElectivosDComp = 0;
	var $iNumCredRequisitos = 0;

	var $iNumAprobBasicos = 0;
	var $iNumAprobEspecificos = 0;
	var $iNumAprobElectivosBComun = 0;
	var $iNumAprobElectivosDComun = 0;
	var $iNumAprobElectivosDEsp = 0;
	var $iNumAprobElectivosDComp = 0;
	var $iNumAprobRequisitos = 0;

	var $iDisponiblesElectivosBComun = 0;
	var $iDisponiblesElectivosDComun = 0;
	var $iDisponiblesRequisitos = 0;

	var $idOpcionGrado = 0;
	var $idProgramaOpcionGrado = 0;
	var $idPrograma = 0;
	var $idPlanEstudios = 0;
	var $idLineaProf = 0;
	var $idTercero = 0;
	var $idVisual = 0; //0, listado de cursos por tipo de curso, 1 Cuadricula...
	var $sCodPlanEstudio = '';
	var $sCodLineaProf = '';
	var $sError = '';
	var $sNomPrograma = '';
	//Variables para el detalle.
	var $aAprobados = array();
	var $aFilas = array();
	var $aNecesarios = array();
	var $aRegistros = array();
	var $aTipoCredito = array();
	var $aNomCredito = array();
	var $aUsoCredito = array();
	var $aIdCredito = array();
	var $aComunes = array();
	var $aComunEscuela = array();
	var $iTipoCreditos = 0;
	var $idContenedor = 0;
	var $idPlan = 0;
	var $idPlanOrigen = 0;
	var $iNecesarios = 0;
	var $iRegistros = 0;
	var $iPesoTotal = 0;
	var $aNiveles = array();
	var $aIdNivel = array();
	var $iNiveles = 0;
	var $aEstados = array();
	var $aTonosEstados = array();
	//Variabless para visualizar.
	var $bConImprimir = false;
	var $bGestionPrematricula = false;

	function limpiar()
	{
		$this->idEstudiantePrograma = 0;
		$this->sNomPrograma = '';
		$this->idOpcionGrado = 0;
		$this->idProgramaOpcionGrado = 0;
		$this->idPlanEstudios = 0;
		$this->idLineaProf = 0;
		$this->sCodPlanEstudio = '';
		$this->sCodLineaProf = '';
		$this->sError = '';
		$this->iCampoCredBasicos = 0;
		$this->iCampoCredEspecificos = 0;
		$this->iCampoCredElectivosBComun = 0;
		$this->iCampoCredElectivosDComun = 0;
		$this->iCampoCredElectivosDEsp = 0;
		$this->iCampoCredElectivosDComp = 0;
		$this->iNumCredBasicos = 0;
		$this->iNumCredEspecificos = 0;
		$this->iNumCredElectivosBComun = 0;
		$this->iNumCredElectivosDComun = 0;
		$this->iNumCredElectivosDEsp = 0;
		$this->iNumCredElectivosDComp = 0;
		$this->iNumCredRequisitos = 0;
		$this->iNumAprobBasicos = 0;
		$this->iNumAprobEspecificos = 0;
		$this->iNumAprobElectivosBComun = 0;
		$this->iNumAprobElectivosDComun = 0;
		$this->iNumAprobElectivosDEsp = 0;
		$this->iNumAprobElectivosDComp = 0;
		$this->iDisponiblesElectivosBComun = 0;
		$this->iDisponiblesElectivosDComun = 0;
		$this->iDisponiblesRequisitos = 0;
		$this->aComunes = array();
		$this->aComunEscuela = array();
		$this->LimpiarDetalle();
	}
	function LimpiarDetalle()
	{
		$this->iNecesarios = 0;
		$this->iRegistros = 0;
		$this->iPesoTotal = 0;
		$this->aNecesarios = array();
		$this->aRegistros = array();
		$this->iNumAprobRequisitos = 0;
		for ($k = 0; $k < 10; $k++) {
			$this->aNecesarios[$k] = 0;
			$this->aAprobados[$k] = 0;
			$this->aFilas[$k] = 0;
			$this->aTipoCredito[$k] = '{' . $k . '}';
		}
	}
	function html_cuadr_avancextipocredito($iNecesarios, $iAprobados)
	{
		$sRes = '';
		if ($iNecesarios > 0) {
			if ($iAprobados > 0) {
				if ($iAprobados < $iNecesarios) {
					$sRes = 'Aprobados <b>' . $iAprobados . '</b> de ' . $iNecesarios . ' cr&eacute;ditos';
				} else {
					$sRes = '<b>Completado</b> [' . $iNecesarios . ' cr&eacute;ditos]';
				}
			} else {
				$sRes = 'Necesita <b>' . $iNecesarios . ' cr&eacute;ditos</b>';
			}
		}
		return $sRes;
	}
	function html_ComboPrematricula($idCurso, $sPeriodos, $objDB, $bDebug = false)
	{
		$sRes = '';
	}
	function html_cuadricula($objDB, $bDebug = false)
	{
		require './app.php';
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $this->sIdioma . '.php';
		if (!file_exists($mensajes_todas)) {
			$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
		}
		$mensajes_2200 = $APP->rutacomun . 'lg/lg_2200_' . $this->sIdioma . '.php';
		if (!file_exists($mensajes_2200)) {
			$mensajes_2200 = $APP->rutacomun . 'lg/lg_2200_es.php';
		}
		require $mensajes_todas;
		require $mensajes_2200;
		$sDebug = '';
		$iCampos = 0;
		$sAvance = '';
		$iTotalElectivos = $this->iCampoCredElectivosBComun + $this->iCampoCredElectivosDComun + $this->iCampoCredElectivosDEsp + $this->iCampoCredElectivosDComp;
		$iAprobados = $this->iNumAprobBasicos + $this->iNumAprobEspecificos + $this->iNumAprobElectivosBComun + $this->iNumAprobElectivosDComun + $this->iNumAprobElectivosDEsp + $this->iNumAprobElectivosDComp;
		$iRequeridos = $this->iCampoCredBasicos + $this->iCampoCredEspecificos + $this->iCampoCredElectivosBComun + $this->iCampoCredElectivosDComun + $this->iCampoCredElectivosDEsp + $this->iCampoCredElectivosDComp;
		$iAvance = 0;
		$sInfoAvance = ' [Sin avance]';
		$iMaximoMatricula = 1000;
		if ($iRequeridos > 0) {
			$iAvance = ($iAprobados / $iRequeridos) * 100;
			$sInfoAvance = '<b>' . formato_numero($iAvance, 2) . ' %</b> [<b>' . $iAprobados . '</b> de ' . $iRequeridos . ' cr&eacute;ditos]';
		}
		$sCuerpo = '';
		$sFila2 = '';
		$iNumCajas = 0;
		//Alistamos los periodos en que vamos a consultar la matricula.
		$iHoy = fecha_DiaMod();
		$sIds02 = '-99';
		if ($iMaximoMatricula > 0) {
			$sSQL = 'SELECT exte02id FROM exte02per_aca WHERE (exte02fechafinmatricula>0 AND exte02fechafinmatricula>=' . $iHoy . ')';
			$tabla = $objDB->ejecutasql($sSQL);
			while ($fila = $objDB->sf($tabla)) {
				$sIds02 = $sIds02 . ',' . $fila['exte02id'];
			}
		}
		if ($sIds02 != '-99') {
			//Ya vimos que hay periodos activos para oferta, ahora, cuantos de esos estan para esta versión del programa.
			$sSQL = 'SELECT TB.ofer53peraca 
			FROM ofer53preoferta AS TB, ofer54preofertaprograma AS T1 
			WHERE TB.ofer53peraca IN (' . $sIds02 . ') AND ofer53cerrado="S" AND TB.ofer53id=T1.ofer54idpreoferta AND T1.ofer54idversionprog=' . $this->idPlanEstudios . '';
			$sIds02 = '-99';
			$tabla = $objDB->ejecutasql($sSQL);
			while ($fila = $objDB->sf($tabla)) {
				$sIds02 = $sIds02 . ',' . $fila['ofer53peraca'];
			}
		}
		if ($sIds02 == '-99') {
			$iMaximoMatricula = 0;
		} else {
			$objCombos = new clsHtmlCombos();
		}
		//Fin de la consulta de periodos para matricula.
		for ($k = 1; $k <= $this->iTipoCreditos; $k++) {
			$sCaja = '';
			$idTipoCred = $this->aIdCredito[$k];
			if ($this->aUsoCredito[$idTipoCred] != 0) {
				if ($iCampos == 0) {
					//$sCaja='<td rowspan="3"></td>';
					$iCampos++;
				}
				$sCaja = $sCaja . '<td align="center">' . $this->aNomCredito[$idTipoCred] . '</td>';
				$sAvanceParcial = '';
				switch ($idTipoCred) {
					case 0:	//Básico
						$sAvanceParcial = $this->html_cuadr_avancextipocredito($this->iCampoCredBasicos, $this->iNumAprobBasicos);
						break;
					case 1:	//Especí­fico
						$sAvanceParcial = $this->html_cuadr_avancextipocredito($this->iCampoCredEspecificos, $this->iNumAprobEspecificos);
						break;
					case 5:	//Electivo Básico Común
						break;
					case 6:	//Electivo Disciplinar Común
						break;
					case 2:	//Electivo Disciplinar Especí­fico
						$sAvanceParcial = $this->html_cuadr_avancextipocredito($this->iCampoCredElectivosDEsp, $this->iNumAprobElectivosDEsp);
						break;
					case 4:	//Electivo Disciplinar Complementario
						$sAvanceParcial = $this->html_cuadr_avancextipocredito($this->iCampoCredElectivosDComp, $this->iNumAprobElectivosDComp);
						break;
					case 3:	//Requisito de grado
						break;
					case 7:	//Curso Libre
						break;
					case 8:	//No Categorizado
						break;
					case 9:	//Opcion de grado
						break;
				}
				$sFila2 = $sFila2 . '<td align="center" style="min-width:300px">' . $sAvanceParcial . '</td>';
				$iCampos++;
			}
			$sCuerpo = $sCuerpo . $sCaja;
			$iNumCajas++;
		}
		if ($iCampos == 0) {
			$iCampos = 1;
		}
		if ($sCuerpo != '') {
			$sCuerpo = '<thead class="fondoazul">
			<tr>
			<td rowspan="3"></td>
			<td colspan="' . $iNumCajas . '" align="center"><b>' . $ETI['msg_componenteforma'] . '</b></td>
			</tr>
			<tr class="fondoazul">' . $sCuerpo . '</tr>
			<tr class="fondoazul">' . $sFila2 . '</tr>
			</thead>';
			//Ahora cargar cada nivel...
			/*
			$this->aIdNivel=array();
			$this->iNiveles=0;
			*/
			$tlinea = 1;
			$sAnchoCol1 = ' style="min-width:20px"';
			for ($j = 1; $j <= $this->iNiveles; $j++) {
				$sNivel = '';
				$idNivel = $this->aIdNivel[$j];
				//'.$ETI['msg_periodo'].' 
				$sNivel = '<td' . $sAnchoCol1 . '><b>' . $idNivel . '</b></td>';
				$sAnchoCol1 = '';
				$sAnchoCuerpo = '';
				//Recorrer por cada tipo de credito.
				for ($k = 1; $k <= $this->iTipoCreditos; $k++) {
					$sCaja = '';
					$idTipoCred = $this->aIdCredito[$k];
					if ($this->aUsoCredito[$idTipoCred] != 0) {
						$sCursosNivel = '';
						//$sCursosNivel=''.$idNivel.'-'.$idTipoCred.'<br>';
						for ($l = 1; $l <= $this->iRegistros; $l++) {
							$objCurso = $this->aRegistros[$l];
							if ($objCurso->iTipoCurso == $idTipoCred) {
								if ($objCurso->iNivel == $idNivel) {
									$sInfoEstado = '';
									//'.$objCurso->iNumCreAprobados.' / 
									$sTono = ' bgcolor="#FF0000"';
									$sColorCreditos = ' style="color:#FFFFFF"';
									if ($objCurso->iNumCreAprobados > 0) {
										$sTono = ' bgcolor="#006600"';
									}
									//'.$this->aTonosEstados[$objCurso->iEstado].'
									if ($sCursosNivel != '') {
										$sCursosNivel = $sCursosNivel . '<hr>';
									}
									$sLineaPreMatricula = '';
									$bConPrematricula = false;
									switch ($objCurso->iEstado) {
										case 0: //Disponible para matricula.
											if ($iMaximoMatricula > $objCurso->iNumCreditos) {
												$bConPrematricula = true;
											}
											break;
										case 1: // Pendiente de prerequisito
											if ($iMaximoMatricula > $objCurso->iNumCreditos) {
												$bConPrematricula = true;
											}
											if ($objCurso->sPrerequisitos != '') {
												$sInfoEstado = ' [' . $objCurso->sPrerequisitos . ']';
											}
											break;
										case 9: //Excluido
											$sTono = '';
											$sColorCreditos = '';
											break;
									}
									if ($bConPrematricula) {
										$iPreOfertaDisponible = 0;
										$sObjetoMatricula = 'No hay oferta disponible a&uacute;n.';
										$sIds02c = '-99';
										//Saber si el curso fue ofertado para estos periodos.
										$sSQL = 'SELECT ofer55periodo FROM ofer55preofertacurso WHERE ofer55idcurso=' . $objCurso->sCodCurso . ' AND ofer55periodo IN (' . $sIds02 . ') AND ofer55idversionprog=' . $this->idPlanEstudios . ' AND (ofer55proyestnuevos+ofer55proyestantiguos)>0';
										if ($bDebug) {
											$sDebug = $sDebug . fecha_microtiempo() . ' Prematricula Solicitud: ' . $sSQL . '<br>';
										}
										$tabla55 = $objDB->ejecutasql($sSQL);
										while ($fila55 = $objDB->sf($tabla55)) {
											$sIds02c = $sIds02c . ',' . $fila55['ofer55periodo'];
										}
										if ($sIds02c != '-99') {
											$sSQL = 'SELECT TB.ofer64idperiodo, T2.exte02nombre, TB.ofer64estado, TB.ofer64preaprobado 
											FROM ofer64preofanalisis AS TB, exte02per_aca AS T2 
											WHERE TB.ofer64idcurso=' . $objCurso->sCodCurso . ' AND TB.ofer64idperiodo IN (' . $sIds02c . ') AND TB.ofer64estado IN (0,1,2,3,5) AND TB.ofer64idperiodo=T2.exte02id
											ORDER BY T2.exte02fechafinmatricula';
											if ($bDebug) {
												$sDebug = $sDebug . fecha_microtiempo() . ' Prematricula Analisis: ' . $sSQL . '<br>';
											}
											$tabla = $objDB->ejecutasql($sSQL);
											$iPreOfertaDisponible = $objDB->nf($tabla);
										}
										if ($iPreOfertaDisponible > 0) {
											$sDivPrematricula = '';
											$objCombos->nuevo('core03premidperiodo_' . $objCurso->id03, $objCurso->idPeriodoPrem, true, '' . $ETI['msg_aunno'] . '', 0);
											$objCombos->iAncho = 250;
											if ($this->bGestionPrematricula){
												$objCombos->sAccion = 'prematricular(' . $this->idContenedor . ', ' . $objCurso->id03 . ', this.value)';
												$sDivPrematricula = html_salto().'<div id="div_fila' . $objCurso->id03 . '"></div>';
											}
											while ($fila = $objDB->sf($tabla)) {
												$sInfoOpcional = '';
												switch ($fila['ofer64estado']) {
													case 0: // Solicitada
													case 1: // En estudio
														$sInfoOpcional = ' [PROYECTADA]';
														break;
													case 2: // Preoferta aprobada
														if ($fila['ofer64preaprobado'] == 0) {
															$sInfoOpcional = ' [PROYECTADA]';
														}
														break;
												}
												$objCombos->addItem($fila['ofer64idperiodo'], cadena_notildes($fila['exte02nombre']) . $sInfoOpcional);
											}
											$sObjetoMatricula = 'Matricular en: ' . $objCombos->html('', $objDB) . $sDivPrematricula;
										}
										if ($objCurso->iTieneEquivalentes != 0) {
											//Tenemos que mostrar los equivalentes.
											if ($iPreOfertaDisponible == 0) {
												$sObjetoMatricula = 'Este curso se homologa internamente con:';
											} else {
												$sObjetoMatricula = $sObjetoMatricula . '<br>Tambien es homologable con:';
											}
											$sSQL = 'SELECT TB.core03idcurso, T4.unad40titulo, T4.unad40nombre 
											FROM core03plandeestudios_' . $this->idContenedor . ' AS TB, unad40curso AS T4 
											WHERE TB.core03idestprograma=' . $this->idEstudiantePrograma . ' AND ((TB.core03idcursoreemp1=' . $objCurso->sCodCurso . ') OR (TB.core03idcursoreemp2=' . $objCurso->sCodCurso . ')) AND TB.core03idcurso=T4.unad40id ';
											$tablae = $objDB->ejecutasql($sSQL);
											while ($filae = $objDB->sf($tablae)) {
												$sObjetoMatricula = $sObjetoMatricula . '<br><b>' . $filae['unad40titulo'] . '</b> ' . cadena_notildes($filae['unad40nombre']) . '';
											}
										}
										$sLineaPreMatricula = '<tr>
										<td colspan="3">' . $sObjetoMatricula . '</td>
										</tr>';
										//Reducir el maximo de matricula;
										$iMaximoMatricula = $iMaximoMatricula - $objCurso->iNumCreditos;
									}
									$sCursosNivel = $sCursosNivel . '<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">
									<tr>
									<td align="center" style="color:#' . $this->aTonosEstados[$objCurso->iEstado] . '"><b>' . $objCurso->sCodCurso . '</b></td>
									<td style="color:#' . $this->aTonosEstados[$objCurso->iEstado] . '">' . $this->aEstados[$objCurso->iEstado] . $sInfoEstado . '</td>
									<td align="center"' . $sColorCreditos . $sTono . ' width="40px">&nbsp;<b>' . $objCurso->iNumCreditos . '</b></td>
									</tr>
									<tr>
									<td colspan="3">' . cadena_notildes($objCurso->sNomCurso) . '</td>
									</tr>' . $sLineaPreMatricula . '
									</table>';
								}
							}
						}
						$sNivel = $sNivel . '<td align="center" valign="top"' . $sAnchoCuerpo . '>' . $sCursosNivel . '</td>';
					}
					$sAnchoCuerpo = '';
				}
				$sClass = '';
				//if(($tlinea%2)==0){$sClass=' class="resaltetabla"';}
				if (($tlinea % 2) == 0) {
					$sClass = ' bgcolor="#FFEEBB"';
				}
				$tlinea++;
				$sCuerpo = $sCuerpo . '<tr' . $sClass . '>' . $sNivel . '</tr>';
			}
		}

		$sAvance = '<tr>
		<td colspan="' . $iCampos . '" align="center" class="fondoazul">' . $ETI['msg_avance'] . ': ' . $sInfoAvance . '</td>
		</tr>';
		$sHTML = '<div class="table-responsive">
		<table border="1" align="center" cellpadding="0" cellspacing="2" class="tablaapp">
		' . $sCuerpo . $sAvance . '
		</table>
		</div>';
		return array($sHTML, $sDebug);
	}
	function html_encabezado($bDebug = false)
	{
		require './app.php';
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $this->sIdioma . '.php';
		if (!file_exists($mensajes_todas)) {
			$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
		}
		$mensajes_2200 = $APP->rutacomun . 'lg/lg_2200_' . $this->sIdioma . '.php';
		if (!file_exists($mensajes_2200)) {
			$mensajes_2200 = $APP->rutacomun . 'lg/lg_2200_es.php';
		}
		require $mensajes_todas;
		require $mensajes_2200;
		$sDebug = '';
		$sCampoBasicos = '';
		$sCompBasicos = '';
		$sCampoEspecificos = '';
		$sCampoObligatorios = '';
		$sCampoElectivoComun = '';
		$sCampoElectivoDComun = '';
		$sCampoElectivoDEspecifico = '';
		$sCampoElectivoDComp = '';
		$sCampoTotalElectivos = '';

		$sAprobadoBasicos = '';
		$sAprobadoEspecificos = '';
		$sAprobadoElectivoComun = '';
		$sAprobadoElectivoDComun = '';
		$sAprobadoElectivoDEsp = '';
		$sAprobadoElectivoDComp = '';
		$sAprobadoRequisitoGrado = '';

		$sDispElectivoComun = '';
		$sDispElectivoDComun = '';
		$sDispRequisitoGrado = '';

		$sAvance = '';
		$sInfoAvance = '';
		$sInfoTotales = '';
		$iTotalObligatorios = (int)$this->iCampoCredBasicos + (int)$this->iCampoCredEspecificos;
		$iTotalElectivos = $this->iCampoCredElectivosBComun + $this->iCampoCredElectivosDComun + $this->iCampoCredElectivosDEsp + $this->iCampoCredElectivosDComp;
		$iRequeridos = $iTotalObligatorios + $iTotalElectivos;
		switch ($this->iJuegoCampos) {
			case 1: // Vista version del programa
				$sCampoBasicos = '<input id="core10numcredbasicos" name="core10numcredbasicos" type="text" value="' . $this->iCampoCredBasicos . '" class="cuatro" maxlength="10" placeholder="0"/>';
				$sCompBasicos = '&nbsp;/ ';
				$sCampoEspecificos = '<input id="core10numcredespecificos" name="core10numcredespecificos" type="text" value="' . $this->iCampoCredEspecificos . '" class="cuatro" maxlength="10" placeholder="0"/>';
				$sCampoElectivoComun = '<input id="core10numcredelecgenerales" name="core10numcredelecgenerales" type="text" value="' . $this->iCampoCredElectivosBComun . '" class="cuatro" maxlength="10" placeholder="0"/>';
				$sCampoElectivoDComun = '<input id="core10numcredelecescuela" name="core10numcredelecescuela" type="text" value="' . $this->iCampoCredElectivosDComun . '" class="cuatro" maxlength="10" placeholder="0"/>';
				$sCampoElectivoDEspecifico = '<input id="core10numcredelecprograma" name="core10numcredelecprograma" type="text" value="' . $this->iCampoCredElectivosDEsp . '" class="cuatro" maxlength="10" placeholder="0"/>';
				$sCampoElectivoDComp = '<input id="core10numcredeleccomplem" name="core10numcredeleccomplem" type="text" value="' . $this->iCampoCredElectivosDComp . '" class="cuatro" maxlength="10" placeholder="0"/>';
				$sCampoTotalElectivos = '<input id="core10numcredelectivos" name="core10numcredelectivos" type="hidden" value="' . $iTotalElectivos . '"/>';
				$sInfoTotales = ': <b>' . $iRequeridos . '</b>';
				break;
			case 2: //Plan de estudios individual
				$iAprobados = $this->iNumAprobBasicos + $this->iNumAprobEspecificos + $this->iNumAprobElectivosBComun + $this->iNumAprobElectivosDComun + $this->iNumAprobElectivosDEsp + $this->iNumAprobElectivosDComp;
				if ($this->iCampoCredBasicos > 0) {
					$sAprobadoBasicos = '' . $this->iNumAprobBasicos . ' de ';
				}
				if ($this->iCampoCredEspecificos > 0) {
					$sAprobadoEspecificos = '' . $this->iNumAprobEspecificos . ' de ';
				}
				if ($this->iCampoCredElectivosBComun > 0) {
					$sAprobadoElectivoComun = '' . $this->iNumAprobElectivosBComun . ' de ';
				}
				if ($this->iCampoCredElectivosDComun > 0) {
					$sAprobadoElectivoDComun = '' . $this->iNumAprobElectivosDComun . ' de ';
				}
				if ($this->iCampoCredElectivosDEsp > 0) {
					$sAprobadoElectivoDEsp = '' . $this->iNumAprobElectivosDEsp . ' de ';
				}
				if ($this->iCampoCredElectivosDComp > 0) {
					$sAprobadoElectivoDComp = '' . $this->iNumAprobElectivosDComp . ' de ';
				}
				if ($this->iNumCredRequisitos > 0) {
					$sAprobadoRequisitoGrado = '' . $this->iNumAprobRequisitos . ' de ';
				}

				$sDispElectivoComun = '&nbsp;/ ' . $this->iDisponiblesElectivosBComun;
				$sDispElectivoDComun = '&nbsp;/ ' . $this->iDisponiblesElectivosDComun;
				$sDispRequisitoGrado = '&nbsp;/ ' . $this->iDisponiblesRequisitos;

				$sCampoBasicos = '<input id="core01numcredbasicos" name="core01numcredbasicos" type="hidden" value="' . $this->iCampoCredBasicos . '"/><b>' . $this->iCampoCredBasicos . '</b>';
				$sCompBasicos = '&nbsp;/ ';
				$sCampoEspecificos = '<input id="core01numcredespecificos" name="core01numcredespecificos" type="hidden" value="' . $this->iCampoCredEspecificos . '"/><b>' . $this->iCampoCredEspecificos . '</b>';
				$sCampoElectivoComun = '<input id="core01numcredelecgenerales" name="core01numcredelecgenerales" type="hidden" value="' . $this->iCampoCredElectivosBComun . '"/><b>' . $this->iCampoCredElectivosBComun . '</b>';
				$sCampoElectivoDComun = '<input id="core01numcredelecescuela" name="core01numcredelecescuela" type="hidden" value="' . $this->iCampoCredElectivosDComun . '"/><b>' . $this->iCampoCredElectivosDComun . '</b>';
				$sCampoElectivoDEspecifico = '<input id="core01numcredelecprograma" name="core01numcredelecprograma" type="hidden" value="' . $this->iCampoCredElectivosDEsp . '"/><b>' . $this->iCampoCredElectivosDEsp . '</b>';
				$sCampoElectivoDComp = '<input id="core01numcredeleccomplem" name="core01numcredeleccomplem" type="hidden" value="' . $this->iCampoCredElectivosDComp . '"/><b>' . $this->iCampoCredElectivosDComp . '</b>';
				$sCampoTotalElectivos = '<input id="core01numcredelectivos" name="core01numcredelectivos" type="hidden" value="' . $iTotalElectivos . '"/>';
				$iAvance = 0;
				if ($iRequeridos > 0) {
					$iAvance = ($iAprobados / $iRequeridos) * 100;
					$sInfoAvance = '<b>' . formato_numero($iAvance, 2) . ' %</b>';
				}
				$sAvance = '<tr>
				<td colspan="13" align="center" class="fondoazul">' . $ETI['msg_avance'] . ': ' . $sInfoAvance . '</td>
				<td></td>
				<td align="center"><b>' . $iAprobados . '</b> / ' . $iRequeridos . '</td>
				</tr>';
				// colspan="1"
				break;
			default:
				break;
		}
		$sHTML = '<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">
		<tr>
		<td colspan="13" align="center" class="fondoazul"><b>' . $ETI['msg_creditos'] . '</b></td>
		<td colspan="2"></td>
		</tr>
		<tr align="center">
		<td colspan="5" class="fondoazul">' . $ETI['msg_credobligatorios'] . '</td>
		<td rowspan="2" bgcolor="#FFFFFF">&nbsp;</td>
		<td rowspan="2" class="fondoazul">' . $ETI['msg_electivobas'] . '</td>
		<td colspan="5">' . $ETI['msg_electivodisc'] . '</td>
		<td rowspan="2" align="center" class="fondoazul">' . $ETI['msg_total'] . '</td>
		<td rowspan="2" bgcolor="#FFFFFF">&nbsp;</td>
		<td class="fondoazul">' . $ETI['msg_credrequisitos'] . '</td>
		</tr>
		<tr align="center">
		<td colspan="2" class="fondoazul">' . $ETI['msg_credbasicos'] . '</td>
		<td colspan="2" class="fondoazul">' . $ETI['msg_credespecificos'] . '</td>
		<td class="fondoazul">' . $ETI['msg_totalobligatorios'] . '</td>
		<td align="center">' . $ETI['msg_credelecescuela'] . '</td>
		<td align="center" colspan="2">' . $ETI['msg_credelecprograma'] . '</td>
		<td align="center" colspan="2" title="' . $ETI['msg_credeleccomplem_total'] . '">' . $ETI['msg_credeleccomplem'] . '</td>
		<td align="center"><div id="lbl_requisitos">' . $sAprobadoRequisitoGrado . '' . $this->iNumCredRequisitos . $sDispRequisitoGrado . '</div></td>
		</tr>
		<tr>
		<td align="right">' . $sAprobadoBasicos . $sCampoBasicos . '</td>
		<td><div id="lbl_basicos">' . $sCompBasicos . $this->iNumCredBasicos . '</div></td>
		<td align="right">' . $sAprobadoEspecificos . $sCampoEspecificos . '</td>
		<td><div id="lbl_especificos">' . $sCompBasicos . $this->iNumCredEspecificos . '</div></td>
		<td align="center"><b>' . $iTotalObligatorios . '</b></td>
		<td class="fondoazul"></td>
		<td align="center">' . $sAprobadoElectivoComun . $sCampoElectivoComun . $sDispElectivoComun . '</td>
		<td align="center">' . $sAprobadoElectivoDComun . $sCampoElectivoDComun . $sDispElectivoDComun . '</td>
		<td align="right">' . $sAprobadoElectivoDEsp . $sCampoElectivoDEspecifico . '</td>
		<td><div id="lbl_electivos">' . $sCompBasicos . $this->iNumCredElectivosDEsp . '</div></td>
		<td align="right">' . $sAprobadoElectivoDComp . $sCampoElectivoDComp . '</td>
		<td><div id="lbl_electivosComp">' . $sCompBasicos . $this->iNumCredElectivosDComp . '</div></td>
		<td align="center"><div id="lbl_electivost">' . $sCampoTotalElectivos . '<b>' . $iTotalElectivos . '</b></div></td>
		<td></td>
		<td class="fondoazul" align="center">' . $ETI['msg_credtotales'] . $sInfoTotales . '</td>
		</tr>' . $sAvance . '
		</table>';
		return array($sHTML, $sDebug);
	}
	function cargarV2($aParametros, $objDB, $bConDetalle = false, $bDebug = false)
	{
		$sError = '';
		$sDebug = '';
		$this->limpiar();
		$core01id = 0;
		$this->aNomCredito = array();
		$this->aUsoCredito = array();
		$this->aIdCredito = array();
		$this->iTipoCreditos = 0;
		$this->aNiveles = array();
		$this->aIdNivel = array();
		$this->aEstados = array();
		$this->aTonosEstados = array();
		$this->aComunes = array();
		$this->aComunEscuela = array();
		$this->iNiveles = 0;
		$sSQL = 'SELECT core35id, core35nombre, core35tono FROM core35estadoenpde';
		$tabla = $objDB->ejecutasql($sSQL);
		while ($fila = $objDB->sf($tabla)) {
			$this->aEstados[$fila['core35id']] = cadena_notildes($fila['core35nombre']);
			$this->aTonosEstados[$fila['core35id']] = $fila['core35tono'];
		}
		$sSQL = 'SELECT core13id, core13nombre FROM core13tiporegistroprog ORDER BY core13orden';
		$tabla = $objDB->ejecutasql($sSQL);
		while ($fila = $objDB->sf($tabla)) {
			$this->iTipoCreditos++;
			$this->aIdCredito[$this->iTipoCreditos] = $fila['core13id'];
			$this->aNomCredito[$fila['core13id']] = cadena_notildes($fila['core13nombre']);
			$this->aUsoCredito[$fila['core13id']] = 0;
		}
		if ($this->idContenedor == 0) {
			list($idContenedor, $sErrorCont) = f1011_BloqueTercero($this->idTercero, $objDB);
			$this->idContenedor = $idContenedor;
		}
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' <b>PEI</b> Tercero ' . $this->idTercero . ' Contenedor ' . $this->idContenedor . '<br>';
		}
		$sSQL = 'SELECT TB.core01id, TB.core01idprograma, T9.core09nombre, TB.core01idplandeestudios, TB.core01idrevision, 
		TB.core01peracainicial, TB.core01numcredbasicos, TB.core01numcredespecificos, TB.core01numcredelecgenerales, 
		TB.core01numcredelecescuela, TB.core01numcredelecprograma, TB.core01numcredeleccomplem, TB.core01numcredbasicosaprob, 
		TB.core01numcredespecificosaprob, TB.core01numcredelecgeneralesaprob, TB.core01numcredelecescuelaaprob, 
		TB.core01numcredelecprogramaaaprob, TB.core01numcredeleccomplemaprob, TB.core01numcredelectivosaprob, TB.core01idlineaprof, 
		TB.core01gradoidopcion 
		FROM core01estprograma AS TB, core09programa AS T9 
		WHERE TB.core01idtercero=' . $this->idTercero . ' AND TB.core01idprograma=' . $this->idPrograma . ' AND TB.core01idprograma=T9.core09id';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' <b>PEI</b> Consultando registro del estudiante: ' . $sSQL . '<br>';
		}
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			$core01id = $fila['core01id'];
			$this->idEstudiantePrograma = $fila['core01id'];
			$this->sNomPrograma = $fila['core09nombre'];
			$this->idOpcionGrado = $fila['core01gradoidopcion'];
			$this->idPlanEstudios = $fila['core01idplandeestudios'];
			$this->idLineaProf = $fila['core01idlineaprof'];

			$this->iCampoCredBasicos = $fila['core01numcredbasicos'];
			$this->iCampoCredEspecificos = $fila['core01numcredespecificos'];
			$this->iCampoCredElectivosBComun = $fila['core01numcredelecgenerales'];
			$this->iCampoCredElectivosDComun = $fila['core01numcredelecescuela'];
			$this->iCampoCredElectivosDEsp = $fila['core01numcredelecprograma'];
			$this->iCampoCredElectivosDComp = $fila['core01numcredeleccomplem'];

			$this->iNumAprobBasicos = $fila['core01numcredbasicosaprob'];
			$this->iNumAprobEspecificos = $fila['core01numcredespecificosaprob'];
			$this->iNumAprobElectivosBComun = $fila['core01numcredelecgeneralesaprob'];
			$this->iNumAprobElectivosDComun = $fila['core01numcredelecescuelaaprob'];
			$this->iNumAprobElectivosDEsp = $fila['core01numcredelecprogramaaaprob'];
			$this->iNumAprobElectivosDComp = $fila['core01numcredeleccomplemaprob'];
			if ($fila['core01idrevision'] != 0) {
				$bConDetalle = true;
			} else {
				if ($fila['core01peracainicial'] > 611) {
					$bConDetalle = true;
				}
			}
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . ' Id del plan de estudios ' . $this->idPlanEstudios . '<b></b>.<br>';
			}
			if (!$bConDetalle) {
				if ($bDebug) {
					if ($this->idPlanEstudios != 0) {
						$bConDetalle = true;
						if ($bDebug) {
							$sDebug = $sDebug . fecha_microtiempo() . ' <b>Mostramos el plan de estudios por pedido del debug</b>.<br>';
						}
					}
				}
			}
			//Total de cursos disponibles para el usuario.
			$sTiposExcluidos = '9, 10, 11, 12';
			switch($this->idOpcionGrado) {
				case 4: // Diplomado de profundización
					$sTiposExcluidos = '9, 11, 12';
					break;
			}
			$sSQL = 'SELECT TB.core03itipocurso, SUM(TB.core03numcreditos) AS Creditos, COUNT(TB.core03id) AS Cant 
			FROM core03plandeestudios_' . $idContenedor . ' AS TB 
			WHERE TB.core03idestprograma=' . $core01id . ' AND TB.core03itipocurso NOT IN (' . $sTiposExcluidos . ') AND TB.core03idequivalente=0 
			GROUP BY TB.core03itipocurso';
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . ' <b>PEI</b> Distribucion por tipo de curso: ' . $sSQL . '<br>';
			}
			$tabla = $objDB->ejecutasql($sSQL);
			while ($fila = $objDB->sf($tabla)) {
				$this->aUsoCredito[$fila['core03itipocurso']] = 1;
				switch ($fila['core03itipocurso']) {
					case 0:
						$this->iNumCredBasicos = $fila['Creditos'];
						break;
					case 1:
						$this->iNumCredEspecificos = $fila['Creditos'];
						break;
					case 2:
						$this->iNumCredElectivosDEsp = $fila['Creditos'];
						break;
					case 3:
						$this->iNumCredRequisitos = $fila['Creditos'];
						$this->iDisponiblesRequisitos = $fila['Creditos'];
						break;
					case 4:
						$this->iNumCredElectivosDComp = $fila['Creditos'];
						break;
					case 5:
						//$this->iNumCredElectivosBComun=$fila['Creditos'];
						$this->iDisponiblesElectivosBComun = $fila['Creditos'];
						break;
					case 6:
						//$this->iNumCredElectivosDComun=$fila['Creditos'];
						$this->iDisponiblesElectivosDComun = $fila['Creditos'];
						break;
				}
			}
			/*
			$this->iNumCredEspecificos=$fila['core01numcredespecificos'];
			$this->iNumCredElectivosBComun=$fila['core01numcredelecgenerales'];
			$this->iNumCredElectivosDComun=$fila['core01numcredelecescuela'];
			$this->iNumCredElectivosDEsp=$fila['core01numcredelecprograma'];
			$this->iNumCredElectivosDComp=$fila['core01numcredeleccomplem'];
			//$this->iNumCredRequisitos=$fila[''];
			*/
			if ($bConDetalle) {
				list($sErrorD, $sDebugD) = $this->CargarCuerpoV2($this->idEstudiantePrograma, $aParametros, $objDB, $bDebug);
				$sDebug = $sDebug . $sDebugD;
			}else {
				//Junio 30 de 2022 - Los requisitos de grados no se calculan bien, por tanto vamos a sumar cuantos se han aprobado.
				$sSQL = 'SELECT TB.core03numcreditos 
				FROM core03plandeestudios_' . $this->idContenedor . ' AS TB, unad40curso AS T4 
				WHERE TB.core03idestprograma=' . $this->idEstudiantePrograma . '  AND TB.core03idequivalente=0 AND TB.core03itipocurso=3 AND TB.core03estado=8 AND TB.core03idcurso=T4.unad40id';
				$tabla = $objDB->ejecutasql($sSQL);
				while($fila = $objDB->sf($tabla)){
					$this->iNumAprobRequisitos = $this->iNumAprobRequisitos + $fila['core03numcreditos'];
				}
			}
		} else {
			$this->sError = 'Error interno: No se ha encontrado el registro de programa Ref ' . $this->idPrograma . '';
		}
		if ($this->idPlanEstudios != 0) {
			$sSQL = 'SELECT core10consec, core10numregcalificado FROM core10programaversion WHERE core10id=' . $this->idPlanEstudios . '';
			$tabla = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla) > 0) {
				$fila = $objDB->sf($tabla);
				$this->sCodPlanEstudio = $fila['core10consec'] . ' - N&deg; Res ' . $fila['core10numregcalificado'];
			}
			switch ($this->idLineaProf) {
				case -1:
					$ETI['msg_pendiente'] = 'Pendiente';
					$this->sCodLineaProf = '[' . $ETI['msg_pendiente'] . ']';
					break;
				case 0:
					$this->sCodLineaProf = '';
					break;
				default:
					$sSQL = 'SELECT core21consec, core21nombre FROM core21lineaprof WHERE core21id=' . $this->idLineaProf . '';
					$tabla = $objDB->ejecutasql($sSQL);
					if ($objDB->nf($tabla) > 0) {
						$fila = $objDB->sf($tabla);
						$this->sCodLineaProf = $fila['core21consec'] . ' - ' . cadena_notildes($fila['core21nombre']);
					}
					break;
			}
		} else {
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . ' <b>No se encontro un plan de estudios</b><br>';
			}
			require './app.php';
			//$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$this->sIdioma.'.php';
			//if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
			$mensajes_2200 = $APP->rutacomun . 'lg/lg_2200_' . $this->sIdioma . '.php';
			if (!file_exists($mensajes_2200)) {
				$mensajes_2200 = $APP->rutacomun . 'lg/lg_2200_es.php';
			}
			//require $mensajes_todas;
			require $mensajes_2200;
			$this->sCodPlanEstudio = '{' . $ETI['msg_peienrevision'] . '}';
		}
		return array($sError, $sDebug);
	}
	function CargarCuerpoV2($core01id, $aParametros, $objDB, $bDebug = false)
	{
		$sError = '';
		$sDebug = '';
		$this->LimpiarDetalle();
		$sSQL = 'SELECT core01idplandeestudios, core01gradoestado, core01idtercero, core01idestado 
		FROM core01estprograma WHERE core01id=' . $core01id . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			$idEstadoPlan = $fila['core01idestado'];
			$this->idPlanOrigen = $fila['core01idplandeestudios'];
			if ($this->idTercero == 0) {
				$this->idTercero = $fila['core01idtercero'];
				$this->idContenedor = 0;
			}
			//if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' <b>PEI</b> Tercero '.$this->idTercero.' Contenedor '.$this->idContenedor.'<br>';}
		}
		if ($this->idPlanOrigen < 1) {
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . '<span class="rojo">No se ha asignado un plan de estudios</span>.<br>';
			}
			return array($sError, $sDebug);
			die();
		}
		$sSQL = 'SELECT core13id, core13nombre FROM core13tiporegistroprog';
		$tabla = $objDB->ejecutasql($sSQL);
		while ($fila = $objDB->sf($tabla)) {
			$this->aTipoCredito[$fila['core13id']] = $fila['core13nombre'];
		}
		$sSQL = 'SELECT core01numcredbasicos, core01numcredespecificos, core01numcredelecgenerales, core01numcredelecescuela, core01numcredelecprograma, core01numcredeleccomplem 
		FROM core01estprograma 
		WHERE core01id=' . $core01id . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			$this->aNecesarios[0] = $fila['core01numcredbasicos'];
			$this->aNecesarios[1] = $fila['core01numcredespecificos'];
			$this->aNecesarios[5] = $fila['core01numcredelecgenerales'];
			$this->aNecesarios[6] = $fila['core01numcredelecescuela'];
			$this->aNecesarios[2] = $fila['core01numcredelecprograma'];
			$this->aNecesarios[4] = $fila['core01numcredeleccomplem'];
		}
		for ($k = 0; $k < 10; $k++) {
			$this->iNecesarios = $this->iNecesarios + $this->aNecesarios[$k];
		}
		//El resumen por niveles...
		/*
		$this->aNiveles=array();
		$this->aIdNivel=array();
		$this->iNiveles=0;
		*/
		if ($this->idContenedor == 0) {
			list($idContenedor, $sErrorCont) = f1011_BloqueTercero($this->idTercero, $objDB);
			$this->idContenedor = $idContenedor;
		}
		$sSQL = 'SELECT TB.core03nivelcurso 
		FROM core03plandeestudios_' . $this->idContenedor . ' AS TB 
		WHERE TB.core03idestprograma=' . $core01id . '  
		GROUP BY TB.core03nivelcurso
		ORDER BY TB.core03nivelcurso';
		$tabla = $objDB->ejecutasql($sSQL);
		while ($fila = $objDB->sf($tabla)) {
			$this->iNiveles++;
			$this->aIdNivel[$this->iNiveles] = $fila['core03nivelcurso'];
			//$this->aNomCredito[$fila['core13id']]=cadena_notildes($fila['core13nombre']);
		}

		if (isset($aParametros[101]) == 0) {
			$aParametros[101] = 1;
		}
		if (isset($aParametros[102]) == 0) {
			$aParametros[102] = 200;
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
		$sSQLadd = '';
		$sSQLadd1 = '';

		//if ($aParametros[104]!=''){$sSQLadd1=$sSQLadd1.'TB.core11componeteconoce='.$aParametros[104].' AND ';}
		if ($aParametros[106] != '') {
			$sSQLadd1 = $sSQLadd1 . 'TB.core03itipocurso=' . $aParametros[106] . ' AND ';
		}
		//Codigo del curso
		if ($aParametros[105] != '') {
			$sSQLadd = $sSQLadd . ' AND T4.unad40titulo LIKE "%' . $aParametros[105] . '%"';
		}
		if ($aParametros[103] != '') {
			$sBase = trim(strtoupper($aParametros[103]));
			$aNoms = explode(' ', $sBase);
			for ($k = 1; $k <= count($aNoms); $k++) {
				$sCadena = $aNoms[$k - 1];
				if ($sCadena != '') {
					$sSQLadd = $sSQLadd . ' AND T4.unad40nombre LIKE "%' . $sCadena . '%"';
					//$sSQLadd1=$sSQLadd1.'T1.unad11razonsocial LIKE "%'.$sCadena.'%" AND ';
				}
			}
		}

		//Los cursos que se muestran son solo los originales del programa, las equivalencias no van...
		$sSQL = 'SELECT TB.core03idcurso, TB.core03id, T4.unad40titulo, T4.unad40nombre, TB.core03obligatorio, 
		TB.core03numcreditos, TB.core03nivelcurso, TB.core03nota75, TB.core03nota25, TB.core03formanota, 
		TB.core03estado, TB.core03itipocurso, TB.core03premidperiodo, TB.core03idprerequisito, TB.core03idprerequisito2, 
		TB.core03idprerequisito3, TB.core03tieneequivalente 
		FROM core03plandeestudios_' . $this->idContenedor . ' AS TB, unad40curso AS T4 
		WHERE TB.core03idestprograma=' . $core01id . ' ' . $sSQLadd1 . ' AND TB.core03idequivalente=0 AND TB.core03idcurso=T4.unad40id ' . $sSQLadd . '
		ORDER BY TB.core03itipocurso, TB.core03nivelcurso, TB.core03idcurso';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' <b>PEI</b> Carga de datos: <br>' . $sSQL . '<br>----------------<br>';
		}
		$tabla = $objDB->ejecutasql($sSQL);
		while ($fila = $objDB->sf($tabla)) {
			$objCurso = new cls2203_rpdei($fila['core03idcurso'], $fila['unad40titulo'], $fila['unad40nombre'], $fila['core03itipocurso'], $fila['core03numcreditos'], $fila['core03id'], $fila['core03premidperiodo'], $fila['core03tieneequivalente']);
			$objCurso->iObligatorio = $fila['core03obligatorio'];
			$objCurso->iNivel = $fila['core03nivelcurso'];
			$objCurso->iNota75 = $fila['core03nota75'];
			$objCurso->iNota25 = $fila['core03nota25'];
			$objCurso->iFormaNota = $fila['core03formanota'];
			$objCurso->iEstado = $fila['core03estado'];
			switch ($objCurso->iEstado) {
				case 1: // Pendiente de prerequisito
					$sInfoPrerequisito = '';
					if ($fila['core03idprerequisito'] != 0) {
						$sInfoPrerequisito = $fila['core03idprerequisito'];
					}
					if ($fila['core03idprerequisito2'] != 0) {
						$sInfoPrerequisito = $sInfoPrerequisito . ', ' . $fila['core03idprerequisito2'];
					}
					if ($fila['core03idprerequisito3'] != 0) {
						$sInfoPrerequisito = $sInfoPrerequisito . ', ' . $fila['core03idprerequisito3'];
					}
					$objCurso->sPrerequisitos = $sInfoPrerequisito;
					break;
				case 5: //Homologado
				case 7: //Aprobado
				case 8: //Requisito cumplido
				case 10: // Plan de transicion
				case 11: // Aprobado por convenio.
				case 15: // Aprobado por suficiencia
				case 17: // Ciclo Aprobado En Otra Institución
				case 25: // Homologado por suficiencia.
					//Contrastar contra f2201_PEIEstadosAprobado() -- 5, 7,8,9,10,11,15,17,25
					$objCurso->iNumCreAprobados = $fila['core03numcreditos'];
					//Si es un requisito de grado va como requisito
					switch ($fila['core03itipocurso']) {
						case 3: //Requisito de grado
							$this->iNumAprobRequisitos = $this->iNumAprobRequisitos + $fila['core03numcreditos'];
							break;
					}
					break;
				case 9: //No requerido
					break;
			}
			$this->iPesoTotal = $this->iPesoTotal + $objCurso->iPeso;
			$this->iRegistros++;
			$this->aRegistros[$this->iRegistros] = $objCurso;
			//Ver que los tipo requisitio de grado se incluyan
			$bPuedeSerComun = true;
			switch ($fila['core03itipocurso']) {
				case 3: //Requisito de grado
					$this->aNecesarios[3] = $this->aNecesarios[3] + $fila['core03numcreditos'];
					break;
			}
		}
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' <b>PEI</b> Carga de datos: Total registros' . $this->iRegistros . '<br>';
		}
		return array($sError, $sDebug);
	}
	function htmlCuerpoV3($aParametros, $bDebug = false)
	{
		require './app.php';
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
		if (!file_exists($mensajes_todas)) {
			$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
		}
		$mensajes_2203 = $APP->rutacomun . 'lg/lg_2203_' . $_SESSION['unad_idioma'] . '.php';
		if (!file_exists($mensajes_2203)) {
			$mensajes_2203 = $APP->rutacomun . 'lg/lg_2203_es.php';
		}
		require $mensajes_todas;
		require $mensajes_2203;
		if ($this->idPlanOrigen < 1) {
			return '<span class="rojo">' . $ETI['msg_noplan'] . '</span>';
			die();
		}
		if (isset($aParametros[101]) == 0) {
			$aParametros[101] = 1;
		}
		if (isset($aParametros[102]) == 0) {
			$aParametros[102] = 200;
		}
		$pagina = $aParametros[101];
		$lineastabla = $aParametros[102];
		$sFilaEncabezado = '<tr class="fondoazul">
		<td colspan="2"><b>' . $ETI['core03idcurso'] . '</b></td>
		<td></td>
		<td><b>' . $ETI['core03numcreditos'] . '</b></td>
		<td><b>' . $ETI['core03nivelcurso'] . '</b></td>
		<td><b>' . $ETI['core03notafinal'] . '</b></td>
		<td><b>' . $ETI['core03estado'] . '</b></td>
		<td align="right">
		' . html_paginador('paginaf2203', $registros, $lineastabla, $pagina, 'paginarf2203()') . '
		' . html_lpp('lppf2203', $lineastabla, 'paginarf2203()', 200) . '
		</td>
		</tr>';
		$iCols = 7;
		$sVerde = ' bgcolor="#006600"';
		$aEstado = array();
		$sSQL = 'SELECT core35id, core35nombre, core35tono FROM core35estadoenpde';
		$tabla = $objDB->ejecutasql($sSQL);
		while ($fila = $objDB->sf($tabla)) {
			$aEstado[$fila['core35id']]['nombre'] = cadena_notildes($fila['core35nombre']);
			$aEstado[$fila['core35id']]['tono'] = $fila['core35tono'];
		}
		//$aEstado=array('Disponible', 'Pendiente de prerequisito', '', '', '', 'Homologado', '', 'Calificado', '', 'Excludio');
		$sRes = '<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">';
		for ($k = 0; $k < 10; $k++) {
			$bPasa = false;
			$iAvance = 0;
			if ($k == 0) {
				if ($this->iNecesarios == 0) {
					$bPasa = true;
				}
			}
			if ($this->aNecesarios[$k] != 0) {
				$bPasa = true;
				$iAvance = (int)($this->aAprobados[$k] / $this->aNecesarios[$k]);
				if ($iAvance > 100) {
					$iAvance = 100;
				}
			}
			//if ($k==3){$bPasa=true;}
			if ($bPasa) {
				$sRes = $sRes . '<tr class="fondoazul">
				<td colspan="' . $iCols . '" align="center"><b>' . cadena_notildes($this->aTipoCredito[$k]) . '</b></td>
				</tr>' . $sFilaEncabezado;
				if ($bDebug) {
					$sDebug = $sDebug . fecha_microtiempo() . ' <b>PEI CUERPO</b>Total registros' . $this->iRegistros . '<br>';
				}
				//Mostrar el contenido del componente.
				for ($j = 1; $j <= $this->iRegistros; $j++) {
					$objCurso = $this->aRegistros[$j];
					if ($objCurso->iTipoCurso == $k) {
						$sPref = '';
						$sSuf = '';
						if ($objCurso->iNumCreAprobados > 0) {
							$sPref = '<b>';
							$sSuf = '</b>';
						}
						$sObligatorio = '';
						if ($objCurso->iObligatorio != 0) {
							$sObligatorio = 'Obligatorio';
						}
						$sNotaFin = '[Pendiente]';
						$sInfoCreditos = $objCurso->iNumCreAprobados . ' / ' . $objCurso->iNumCreditos;
						$sPref = '<span style="color:#' . $aEstado[$objCurso->iEstado]['tono'] . '">' . $sPref;
						$sSuf = $sSuf . '</span>';
						$sBotonComun = 'ttt';
						$sAnchoColumna = ' colspan="2"';
						/*
						if (isset($this->aComunEscuela[$objCurso->sCodCurso])!=0){
							$sBotonComun='</td><td>Aqui';
							$sAnchoColumna='';
							}
						*/
						$sRes = $sRes . '<tr>
						<td>' . $sPref . $objCurso->sCodCurso . $sSuf . '</td>
						<td>' . $sPref . cadena_notildes($objCurso->sNomCurso) . $sSuf . '</td>
						<td>' . $sPref . $sObligatorio . $sSuf . '</td>
						<td align="center">' . $sPref . $sInfoCreditos . $sSuf . '</td>
						<td>' . $sPref . $objCurso->iNivel . $sSuf . '</td>
						<td>' . $sPref . $sNotaFin . $sSuf . '</td>
						<td' . $sAnchoColumna . '>' . $sPref . $aEstado[$objCurso->iEstado]['nombre'] . $sSuf . $sBotonComun . '</td>
						</tr>';
					}
				}
				//Muestra una tabla de progreso del componente.
				if ($iAvance > 0) {
					$sLinea2 = '';
					if ($iAvance < 100) {
						$sLinea2 = '<td width="' . (100 - $iAvance) . '%" align="center">' . (100 - $iAvance) . ' %</td>';
					}
					$sRes = $sRes . '<tr>
				<td colspan="' . $iCols . '"><table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">
				<tr>
				<td width="' . $iAvance . '%"' . $sVerde . ' align="center"><span style="color:#FFFFFF">' . $iAvance . ' %</span></td>' . $sLinea2 . '
				</tr>
				</table></td>
				</tr>';
				}
			}
		}
		$sRes = $sRes . '</table>';
		return array($sRes, $sDebug);
	}
	function sVisual1()
	{
		$sHTML = '';
		$sJS = '';
		$sDebug = '';
		$sError = '';
		$iTipoError = 0;
		require './app.php';
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $this->sIdioma . '.php';
		if (!file_exists($mensajes_todas)) {
			$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
		}
		$mensajes_2200 = $APP->rutacomun . 'lg/lg_2200_' . $this->sIdioma . '.php';
		if (!file_exists($mensajes_2200)) {
			$mensajes_2200 = $APP->rutacomun . 'lg/lg_2200_es.php';
		}
		require $mensajes_todas;
		require $mensajes_2200;
		$sInfoLinea = '';
		if ($this->idLineaProf != 0) {
			$sInfoLinea = html_salto() . '' . $ETI['core01idlineaprof'] . ': <b>' . $this->sCodLineaProf . '</b>';
		}
		$sHTML = '<div class="GrupoCampos">
		' . $ETI['core01idprograma'] . ': <b>' . cadena_notildes($this->sNomPrograma) . '</b>' . html_salto() . '
		' . $ETI['core01idplandeestudios'] . ': <b>' . cadena_notildes($this->sCodPlanEstudio) . '</b>' . $sInfoLinea . html_salto() . '
		</div>';
		return array($sHTML, $sJS, $sError, $iTipoError, $sDebug);
	}
	function htmlV2($aParametros, $objDB, $bDebug = false, $bConEncabezados = false)
	{
		$sHTML = '';
		$sJS = '';
		$sDebug = '';
		$sError = $this->sError;
		$iTipoError = 0;
		if ($sError == '') {
			if ($bConEncabezados) {
				list($sHTML, $sJS, $sError, $iTipoError, $sDebug) = $this->sVisual1();
			}
			switch ($this->idVisual) {
				case 1: //Cuadricula...
					list($sHTMLe, $sDebugE) = $this->html_cuadricula($objDB, $bDebug);
					$sHTML = $sHTML . $sHTMLe;
					$sDebug = $sDebug . $sDebugE;
					break;
				default:
					list($sHTMLe, $sDebugE) = $this->html_encabezado($bDebug);
					$sHTML = $sHTML . $sHTMLe;
					$sDebug = $sDebug . $sDebugE;
					list($sHTMLc, $sDebugC) = $this->htmlCuerpoV3($aParametros, $bDebug);
					$sHTML = $sHTML . $sHTMLc;
					$sDebug = $sDebug . $sDebugC;
					break;
			}
		}
		return array($sHTML, $sJS, $sError, $iTipoError, $sDebug);
	}
	function __construct($idTercero, $idPrograma, $idVisual = 0)
	{
		$this->idTercero = $idTercero;
		$this->idPrograma = $idPrograma;
		$this->idVisual = $idVisual;
	}
}
