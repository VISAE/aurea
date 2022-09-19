<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2020 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.25.3 martes, 14 de julio de 2020
--- Libreria para calculos de tiempo
*/
function Tiempo_HTML($iDias, $iHoras, $iMinutos){
	$sRes='';
	if ($iDias>0){
		$sRes=formato_numero($iDias).' D&Iacute;as ';
		}
	if ($iHoras>0){
		$sRes=$sRes.formato_numero($iHoras).' Horas ';
		}
	if ($iMinutos>0){
		$sRes=$sRes.formato_numero($iMinutos).' Minutos';
		}
	return $sRes;
	}
function Tiempo_MinutosCalendario($iDiaInicial, $iHoraInicial, $iMinutoInicial, $iDiaFinal, $iHoraFinal, $iMinutoFinal, $bInclusive=true){
	$iDias=0;
	$iHoras=0;
	$iMinutos=0;
	$iMinDiaIni=($iHoraInicial*60)+$iMinutoInicial;
	$iMinDiaFin=($iHoraFinal*60)+$iMinutoFinal;
	if ($iDiaInicial==$iDiaFinal){
		$iLapsoMinutos=$iMinDiaFin-$iMinDiaIni+1;
		if ($iLapsoMinutos<1){
			//Nos estan dando una hora inferior
			$iLapsoMinutos=(24*60)-$iMinDiaIni;
			}
		$iHoras=floor($iLapsoMinutos/60);
		$iMinutos=$iLapsoMinutos-($iHoras*60);
		}else{
		//El día es Diferente
		if ($iDiaInicial<$iDiaFinal){
			//Cantidad de dias enteros que pasaron, no se debe contar ni el día inicial ni el final.
			$iDias=fecha_DiasEntreFechasDesdeNumero($iDiaInicial, $iDiaFinal)-1;
			//Ahora la cola de los minutos del día Inicial y el sobrante de los minutos del día Final.
			$iColaMinutos=(24*60)-$iMinDiaIni;
			$iMinutosAdicionales=$iColaMinutos+$iMinDiaFin;
			$iHoras=floor($iMinutosAdicionales/60);
			$iMinutos=$iMinutosAdicionales-($iHoras*60);
			if ($iHoras>23){
				$iHoras=$iHoras-24;
				$iDias--;
				}
			}
		}
	return array($iDias, $iHoras, $iMinutos);
	}
?>
