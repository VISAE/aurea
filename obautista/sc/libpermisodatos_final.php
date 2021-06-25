<?php
class clsUNADPermisosZonaEscuela__ {
var $idModulo = 0;
var $idTercero = 0;
var $bPermisoTodos = false;
var $bPermisoZonas = false;
var $bPermisoCentros = false;
var $bPermisoEscuelas = false;
var $bPermisoProgramas = false;
var $sIdEscuela = '-99';
var $sIdPrograma = '-99';
var $sIdZona = '-99';
var $sIdCentro = '-99';
function bPermisoTodos($objDB, $bDebug){
    $sDebug='';
    list($this->bPermisoTodos, $sDebug)=seg_revisa_permisoV3($this->idModulo, 12, $this->idTercero, $objDB, $bDebug);
    return array($this->bPermisoTodos, $sDebug);
    }
function bPermisoEscuelas($objDB, $bDebug){
    $sDebug='';
    $this->bPermisoEscuelas=false;
    if($this->bPermisoTodos) {
        $this->bPermisoEscuelas = true;
        } else {
        list($this->bPermisoEscuelas, $sDebug) = seg_revisa_permisoV3($this->idModulo, 1701, $this->idTercero, $objDB, $bDebug);
        }
    return array($this->bPermisoEscuelas, $sDebug);
    }
function sListaEscuelas($objDB, $bDebug){
    $sDebug='';
    if ($this->bPermisoEscuelas) {
        $sSQL='SELECT TB.core12id 
FROM core12escuela AS TB 
WHERE TB.core12tieneestudiantes="S" ';
        if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consultando todas las Escuelas: '.$sSQL.'<br>';}
        $tabla=$objDB->ejecutasql($sSQL);
        while($fila=$objDB->sf($tabla)){
            $this->sIdEscuela=$this->sIdEscuela.','.$fila['core12id'];
            }
        } else {
        $sSQL='SELECT TB.core12id 
FROM core12escuela AS TB
WHERE ((TB.core12iddecano='.$this->idTercero.') OR (TB.core12idadministrador='.$this->idTercero.'))';
	    if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consultando Escuelas de Decanos y Secretarios: '.$sSQL.'<br>';}
	    $tabla=$objDB->ejecutasql($sSQL);
        if ($objDB->nf($tabla)>0){
            $fila=$objDB->sf($tabla);
            $this->sIdEscuela=$this->sIdEscuela.','.$fila['core12id'];
            }else{
            $sSQL='SELECT TB.core09idescuela 
FROM core09programa AS TB
WHERE TB.core09iddirector='.$this->idTercero.'';
            if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consultando Escuelas de Lideres de programa: '.$sSQL.'<br>';}
            $tabla=$objDB->ejecutasql($sSQL);
            if ($objDB->nf($tabla)>0){
                $fila=$objDB->sf($tabla);
                $this->sIdEscuela=$this->sIdEscuela.','.$fila['core09idescuela'];
                }
            }
        }
    return array($this->sIdEscuela, $sDebug);
    }
function bPermisoProgramas($objDB, $bDebug){
    $sDebug='';
    $this->bPermisoProgramas=false;
    if($this->bPermisoTodos) {
        $this->bPermisoProgramas=true;
        } else {
        list($this->bPermisoProgramas, $sDebug) = seg_revisa_permisoV3($this->idModulo, 1709, $this->idTercero, $objDB, $bDebug);
        }
    return array($this->bPermisoProgramas, $sDebug);
    }
function sListaProgramas($objDB, $bDebug){
    $sDebug='';
    if($this->bPermisoProgramas){
        $sSQL='SELECT TB.core09id 
FROM core09programa AS TB 
WHERE TB.core09activo="S" ';
        if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consultando todos los programas: '.$sSQL.'<br>';}
        $tabla=$objDB->ejecutasql($sSQL);
        while($fila=$objDB->sf($tabla)){
            $this->sIdPrograma=$this->sIdPrograma.','.$fila['core09id'];
		    }
        } else {
        $sSQL='SELECT TB.core09id 
FROM core09programa AS TB
WHERE TB.core09iddirector='.$this->idTercero.'';
        if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consultando programas de Lideres de programa: '.$sSQL.'<br>';}
        $tabla=$objDB->ejecutasql($sSQL);
        while($fila=$objDB->sf($tabla)){
            $this->sIdPrograma=$this->sIdPrograma.','.$fila['core09id'];
		    }
        }
    return array($this->sIdPrograma, $sDebug);
    }
function bPermisoZonas($objDB, $bDebug) {
    $sDebug='';
    $this->bPermisoZonas = false;
    if($this->bPermisoTodos) {
        $this->bPermisoZonas = true;
        } else {
        list($this->bPermisoZonas, $sDebug) = seg_revisa_permisoV3($this->idModulo, 1710, $this->idTercero, $objDB, $bDebug);
        }
    return array($this->bPermisoZonas, $sDebug);
    }
function sListaZonas($objDB, $bDebug) {
    $sDebug='';
    if($this->bPermisoZonas) {
        $sSQL='SELECT TB.unad23id 
FROM unad23zona AS TB 
WHERE unad23conestudiantes="S" ';
        if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consultando Zonas de Lideres: '.$sSQL.'<br>';}
        $tabla=$objDB->ejecutasql($sSQL);
        while($fila=$objDB->sf($tabla)){
            $this->sIdZona=$this->sIdZona.','.$fila['unad23id'];
            }
        } else {
        $sSQL='SELECT cara21idzona 
FROM cara21lidereszona 
WHERE cara21idlider='.$this->idTercero.' AND cara21activo="S"';
        if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consultando Zonas de Lideres: '.$sSQL.'<br>';}
        $tabla=$objDB->ejecutasql($sSQL);
        while($fila=$objDB->sf($tabla)){
            $this->sIdZona=$this->sIdZona.','.$fila['cara21idzona'];
            }
        }
    return array($this->sIdZona, $sDebug);
    }
function bPermisoCentros($objDB, $bDebug) {
    $sDebug='';
    $this->bPermisoCentros=false;
    if($this->bPermisoTodos) {
        $this->bPermisoCentros=true;
        } else {
        list($this->bPermisoCentros, $sDebug) = seg_revisa_permisoV3($this->idModulo, 1708, $this->idTercero, $objDB, $bDebug);
        }
    return array($this->bPermisoCentros, $sDebug);
    }
function sListaCentros($objDB, $bDebug) {
    $sDebug='';
    if($this->bPermisoCentros){
        $sSQL='SELECT TB.unad24id 
FROM unad24sede AS TB 
WHERE unad24activa="S" ';
        if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consultando Zonas de Lideres: '.$sSQL.'<br>';}
        $tabla=$objDB->ejecutasql($sSQL);
        while($fila=$objDB->sf($tabla)){
            $this->sIdCentro=$this->sIdCentro.','.$fila['unad24id'];
            }
        } else {

        }
    return array($this->sIdCentro, $sDebug);
    }
function cargarPermisos($objDB,$bDebug=false) {
    $this->bPermisoTodos($objDB,$bDebug);
    $this->bPermisoEscuelas($objDB,$bDebug);
    $this->sListaEscuelas($objDB,$bDebug);
    $this->bPermisoProgramas($objDB,$bDebug);
    $this->sListaProgramas($objDB,$bDebug);
    $this->bPermisoZonas($objDB,$bDebug);
    $this->sListaZonas($objDB,$bDebug);
    $this->bPermisoCentros($objDB,$bDebug);
    $this->sListaCentros($objDB,$bDebug);
    }
function __construct($idModulo, $idTercero){
    $this->idModulo = $idModulo;
    $this->idTercero = $idTercero;
    }
function __destruct(){
    }
}