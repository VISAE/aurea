<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2014 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Version 2.5.7 Domingo, 14 de septiembre de 2014
--- Septiembre 20 de 2014 se incorpora la hora del registro.
*/
class unad_debug{
//Ruta del archivo que nos mostrará el debug
var $sRuta='';
var $bLimpia=true;
//Si la depuracion esta activa o no.
var $bActiva=true;
function Registrar($sValor){
	if ($this->bActiva){
		$lnkDeb=fopen($this->sRuta,'a');
		fwrite($lnkDeb, date('d/m/Y H:m:s').' '.$sValor.'<br>');
		fclose($lnkDeb);
		}
	}
function Registrar2($sRuta, $sValor){
	if ($this->bActiva){
		$lnkDeb=fopen($sRuta,'w');
		fwrite($lnkDeb, $sValor);
		fclose($lnkDeb);
		}
	}
function Reiniciar($sMotivo=''){
	$this->bLimpia=true;
	if ($this->bActiva){
		if ($sMotivo!=''){$sMotivo=$sMotivo.', ';}
		$lnkDeb=fopen($this->sRuta,'w');
		fwrite($lnkDeb, $sMotivo.'Fecha: '.date('d/m/Y H:i:s').'<br>');
		fclose($lnkDeb);
		}
	}
function __construct($sTitulo='', $bActiva=true, $sRutaBase=''){
	if ($sRutaBase!=''){
		if (!file_exists($sRutaBase)){$sRutaBase='';}
		}
	if ($sRutaBase==''){
		$sRutaBase='debug.htm';
		}
	$this->sRuta=$sRutaBase;
	$this->bActiva=$bActiva;
	if ($bActiva){
		$this->Reiniciar($sTitulo);
		}
	}
}
?>