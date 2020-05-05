<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2016 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Marzo 7 de 2016 Se inicial.
*/
class clsUNADCalendario{
var $DATA=array();
var $MESES=array();
var $bMostrarMesCompleto=false;
var $bMostrarTituloDias=true;
var $bMinimoMesActual=false;
var $iAgnoIni=0;
var $iAgnoFin=0;
var $iColumnas=2;
var $iDias=0;
var $iMesIni=13;
var $iMesFin=0;
var $iOrden=0;
function AddFecha($sFecha, $sRotulo, $sColorFondo=''){
	$res='';
	if (fecha_esvalida($sFecha)){
		$iFecha=fecha_EnNumero($sFecha);
		if (isset($this->DATA[$iFecha]['rotulo'])==0){
			$this->iDias++;
			$this->DATA[$iFecha]['rotulo']=$sRotulo;
			//$this->DATA[$iFecha]['diasemana']=fecha_diasemana($sFecha);
			$iMes=substr($sFecha,3,2);
			$iAgno=substr($sFecha,6,4);
			$this->DATA[$iFecha]['mes']=$iMes;
			$this->DATA[$iFecha]['agno']=$iAgno;
			$this->DATA[$iFecha]['colorfondo']=$sColorFondo;
			$iMesAgno=$iMes+($iAgno*100);
			if (isset($this->MESES[$iMesAgno]['dias'])==0){
				$this->MESES[$iMesAgno]['dias']=1;
				}else{
				$this->MESES[$iMesAgno]['dias']++;
				}
			if ($this->iAgnoIni==0){
				$this->iMesIni=$iMes;
				$this->iAgnoIni=$iAgno;
				$this->iMesFin=$iMes;
				$this->iAgnoFin=$iAgno;
				}
			if (($this->iMesIni+($this->iAgnoIni*100))>$iMesAgno){
				$this->iMesIni=$iMes;
				$this->iAgnoIni=$iAgno;
				}
			if (($this->iMesFin+($this->iAgnoFin*100))<$iMesAgno){
				$this->iMesFin=$iMes;
				$this->iAgnoFin=$iAgno;
				}
			}
		}else{
		$res='Fecha Incorrecta';
		}
	return $res;
	}
function HTMLTablaTituloMes($iMes, $iAgno, $sIdioma='es'){
	$iCols=7;
	if ($this->iColumnas>1){$iCols=7*$this->iColumnas;}
	$res='<tr class="fondoazul">
<td colspan="'.$iCols.'" align="center"><b>'.fecha_mes_nombre($iMes).' - '.$iAgno.'</b></td>
</tr>';
	return $res;
	}
function HTMLTablaTitulosDias($sIdioma='es'){
	$sEspacios='';
	if ($this->iColumnas>1){$sEspacios=' colspan="'.$this->iColumnas.'"';}
	$res='<tr class="fondoazul">
<td align="center"'.$sEspacios.'>Lunes</td>
<td align="center"'.$sEspacios.'>Martes</td>
<td align="center"'.$sEspacios.'>Miercoles</td>
<td align="center"'.$sEspacios.'>Jueves</td>
<td align="center"'.$sEspacios.'>Viernes</td>
<td align="center"'.$sEspacios.'>Sabado</td>
<td align="center"'.$sEspacios.'>Domingo</td>
</tr>';
	return $res;
	}
function HTMLTabla($sIdioma='es'){
	$sAlineaDia=' align="center"';
	$iDias=$this->iDias;
	$DATA=$this->DATA;
	$res='';
	if ($iDias==0){
		if ($this->bMinimoMesActual){
			$this->iColumnas=1;
			$iMes=fecha_mes();
			$iAgno=fecha_agno();
			$res=$res.$this->HTMLTablaTituloMes($iMes, $iAgno, $sIdioma);
			if ($this->bMostrarTituloDias){
				$res=$res.$this->HTMLTablaTitulosDias($sIdioma);
				}
			if ($this->bMostrarMesCompleto){
				$iDiaSem=date('w', mktime(0, 0, 0, $iMes, 1, $iAgno));
				//Ver cuando arranca la primer semana del mes...
				$res=$res.'<tr>';
				if ($iDiaSem>1){
					$res=$res.'<td colspan="'.($iDiaSem-1).'"></td>';
					}
				$iDiaSem--;
				$iNumDiasMes=fecha_mesNumDias($iMes, $iAgno);
				for ($m=1;$m<=$iNumDiasMes;$m++){
					$iDiaSem++;
					if ($iDiaSem>6){$iDiaSem=0;}
					if ($iDiaSem==1){$res=$res.'<tr></tr>';}
					$res=$res.'<td'.$sAlineaDia.'>'.$m.'</td>';
					}
				$iDiaSem++;
				if ($iDiaSem<>7){
					if ($iDiaSem<>1){
						$res=$res.'<td colspan="'.(8-$iDiaSem).'"></td>';
						}
					}
				$res=$res.'</tr>';
				}
			}
		//Termina cuando no hay datos.
		}else{
		$iMesAgno=0;
		$iMesAgnoIni=$this->iMesIni+($this->iAgnoIni*100);
		$iMesAgnoFin=$this->iMesFin+($this->iAgnoFin*100);
		for ($k=$iMesAgnoIni;$k<=$iMesAgnoFin;$k++){
			$bPasa=true;
			if (isset($this->MESES[$k]['dias'])==0){$bPasa=false;}
			if ($bPasa){
				$iAgno=round($k/100,0);
				$iMes=$k-($iAgno*100);
				$sMes=$this->HTMLTablaTituloMes($iMes, $iAgno, $sIdioma);
				if ($this->bMostrarTituloDias){
					$sMes=$sMes.$this->HTMLTablaTitulosDias($sIdioma);
					}
				$bTr=true;
				$iDiaSem=date('w', mktime(0, 0, 0, $iMes, 1, $iAgno));
				//Ver cuando arranca la primer semana del mes...
				$sMes=$sMes.'<tr>';
				$iMultiplicadorColumnas=1;
				if ($this->iColumnas==2){$iMultiplicadorColumnas=2;}
				if ($iDiaSem>1){
					$iCols=($iDiaSem-1)*$iMultiplicadorColumnas;
					$sMes=$sMes.'<td colspan="'.$iCols.'"></td>';
					}
				$iDiaSem--;
				$iNumDiasMes=fecha_mesNumDias($iMes, $iAgno);;
				for ($m=1;$m<=$iNumDiasMes;$m++){
					$sClaseDia=' class="resaltetabla"';
					$iDiaSem++;
					if ($iDiaSem>6){$iDiaSem=0;}
					if ($iDiaSem==1){$sMes=$sMes.'<tr></tr>';}
					$iFecha=($k*100)+$m;
					switch($this->iColumnas){
						case 2:
						if (isset($this->DATA[$iFecha]['rotulo'])==0){
							$sClaseDia='';
							$sRotuloDia='<span class="rojo">'.$m.'</span>';
							$sRotulo='';
							}else{
							$sRotuloDia=$m;
							$sRotulo=$this->DATA[$iFecha]['rotulo'];
							$sColorFondo=$this->DATA[$iFecha]['colorfondo'];
							if ($sColorFondo!=''){
								$sClaseDia=' bgcolor="#'.$sColorFondo.'"';
								}
							}
						$sMes=$sMes.'<td'.$sAlineaDia.$sClaseDia.'>'.$sRotuloDia.'</td><td'.$sAlineaDia.$sClaseDia.'>'.$sRotulo.'</td>';
						break;
						default:
						if (isset($this->DATA[$iFecha]['rotulo'])==0){
							$sClaseDia='';
							$sRotulo='<span class="rojo">'.$m.'</span>';
							}else{
							$sRotulo=$m.' '.$this->DATA[$iFecha]['rotulo'];
							}
						$sMes=$sMes.'<td'.$sAlineaDia.$sClaseDia.'>'.$sRotulo.'</td>';
						break;
						}
					}
				if($bTr){
					$iDiaSem++;
					if ($iDiaSem<>7){
						if ($iDiaSem<>1){
							$iCols=(8-$iDiaSem)*$iMultiplicadorColumnas;
							$sMes=$sMes.'<td colspan="'.$iCols.'"></td>';
							}
						}
					$sMes=$sMes.'</tr>';
					}
				//Termina el recorrido por un mes.
				if ($this->iOrden==1){
					$res=$sMes.$res;
					}else{
					$res=$res.$sMes;
					}
				}
			}
		//Termina que hay dias.
		}
	$res='<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">'.$res.'</table>';
	return $res;
	}
function __construct(){
	}
function __destruct(){
	}
}
?>