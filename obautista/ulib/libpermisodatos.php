<?php
/*
--- © Omar Augusto Bautista - UNAD - 2020 ---
--- omar.bautista@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.25.4 viernes, 10 de julio de 2020
--- libpermisodatos.php -- Gestiona los permisos sobre datos que se puedan requerir de un usuario.
*/
class clsUNADPermisosZonaEscuela {
var $idModulo = 0;
var $idTercero = 0;
var $bPermisoTodos = false;
var $bPermisoZonas = false;
var $bPermisoCentros = false;
var $bPermisoEscuelas = false;
var $bPermisoProgramas = false;
var $bEsConsejero = false;
var $bEsLiderZonalConsejeria = false;
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
    if($this->bEsConsejero){ $this->bPermisoEscuelas=true; }
    if ($this->bPermisoEscuelas) {
        $sSQL='SELECT TB.core12id 
FROM core12escuela AS TB 
WHERE TB.core12tieneestudiantes="S" ';
        if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consultando todas las Escuelas: '.$sSQL.'<br>';}
        $tabla=$objDB->ejecutasql($sSQL);
        while($fila=$objDB->sf($tabla)){
            $this->sIdEscuela=$this->sIdEscuela.','.$fila['core12id'];
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
    $sSQL='SELECT TB.core09id 
FROM core09programa AS TB
WHERE TB.core09iddirector='.$this->idTercero.'';
    if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consultando programas de Lideres de programa: '.$sSQL.'<br>';}
    $tabla=$objDB->ejecutasql($sSQL);
    while($fila=$objDB->sf($tabla)){
        $this->sIdPrograma=$this->sIdPrograma.','.$fila['core09id'];
        }
    if($this->bPermisoProgramas || $this->bPermisoEscuelas){
        $sSQL='SELECT TB.core09id 
FROM core09programa AS TB 
WHERE TB.core09activo="S" ';
        if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consultando todos los programas: '.$sSQL.'<br>';}
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
    $sSQL='SELECT cara21idzona 
FROM cara21lidereszona 
WHERE cara21idlider='.$this->idTercero.' AND cara21activo="S"';
    if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consultando Zonas de Lideres de Consejeria: '.$sSQL.'<br>';}
    $tabla=$objDB->ejecutasql($sSQL);
    while($fila=$objDB->sf($tabla)){
        $this->bEsLiderZonalConsejeria=true;
        $this->sIdZona=$this->sIdZona.','.$fila['cara21idzona'];
        }
    $sSQL='SELECT cara01idzona FROM cara13consejeros WHERE cara13idconsejero='.$this->idTercero.' AND cara13activo="S"';
    if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consultando Zonas de Consejeros: '.$sSQL.'<br>';}
    $tabla=$objDB->ejecutasql($sSQL);
    while($fila=$objDB->sf($tabla)){
        $this->bEsConsejero=true;
        $this->bPermisoZonas=true;
        $this->sIdZona=$this->sIdZona.','.$fila['cara01idzona'];
        }
    if($this->bPermisoZonas) {
        $sSQL='SELECT TB.unad23id 
FROM unad23zona AS TB 
WHERE unad23conestudiantes="S" ';
        if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consultando todas las Zonas: '.$sSQL.'<br>';}
        $tabla=$objDB->ejecutasql($sSQL);
        while($fila=$objDB->sf($tabla)){
            $this->sIdZona=$this->sIdZona.','.$fila['unad23id'];
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
    if($this->bPermisoCentros || $this->bPermisoZonas){
        $sSQL='SELECT TB.unad24id 
FROM unad24sede AS TB 
WHERE unad24activa="S" ';
        if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consultando todos los Centros: '.$sSQL.'<br>';}
        $tabla=$objDB->ejecutasql($sSQL);
        while($fila=$objDB->sf($tabla)){
            $this->sIdCentro=$this->sIdCentro.','.$fila['unad24id'];
            }
        }
    return array($this->sIdCentro, $sDebug);
    }
function cargarPermisos($objDB,$bDebug=false) {
    $sDebug='';
    $sError='';
    list($sId, $sDebugR)=$this->bPermisoTodos($objDB,$bDebug);
    $sDebug = $sDebug . $sDebugR;
    list($sId, $sDebugR)=$this->bPermisoZonas($objDB,$bDebug);
    $sDebug = $sDebug . $sDebugR;
    list($sId, $sDebugR)=$this->sListaZonas($objDB,$bDebug);
    $sDebug = $sDebug . $sDebugR;
    list($sId, $sDebugR)=$this->bPermisoCentros($objDB,$bDebug);
    $sDebug = $sDebug . $sDebugR;
    list($sId, $sDebugR)=$this->sListaCentros($objDB,$bDebug);
    $sDebug = $sDebug . $sDebugR;
    list($sId, $sDebugR)=$this->bPermisoEscuelas($objDB,$bDebug);
    $sDebug = $sDebug . $sDebugR;
    list($sId, $sDebugR)=$this->sListaEscuelas($objDB,$bDebug);
    $sDebug = $sDebug . $sDebugR;
    list($sId, $sDebugR)=$this->bPermisoProgramas($objDB,$bDebug);
    $sDebug = $sDebug . $sDebugR;
    list($sId, $sDebugR)=$this->sListaProgramas($objDB,$bDebug);
    $sDebug = $sDebug . $sDebugR;
    return array($sDebug, $sError);
    }
function __construct($idModulo, $idTercero){
    $this->idModulo = $idModulo;
    $this->idTercero = $idTercero;
    }
function __destruct(){
    }
}