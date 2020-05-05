<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2016 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Version 2.15.7 jueves, 01 de septiembre de 2016
--- unad80foro Foro
*/
class clsT1580{
var $iCodModulo=1580;
var $bAuditaInsertar=true;
var $bAuditaModificar=true;
var $bAuditaEliminar=true;
var $unad80idproceso=0;
var $unad80idref=0;
var $unad80idpadre=0;
var $unad80consec='';
var $unad80id='';
var $unad80mensaje='';
var $unad80ifecha=0;
var $unad80hora=0;
var $unad80minuto=0;
var $unad80usuario=0;
function dato_unad80usuario($unad80usuario, $unad80usuario_td, $unad80usuario_doc, $objdb, $sPrevio='El tercero Usuario '){
	$sError='';
	if ($sError==''){$sError=tabla_terceros_existe($unad80usuario_td, $unad80usuario_doc, $objdb, $sPrevio);}
	if ($sError==''){
		list($sError, $sInfo)=tercero_Bloqueado($unad80usuario, $objdb);
		if ($sInfo!=''){$sError=$sError.'<br>'.sInfo;}
		}
	if ($sError==''){
		$this->unad80usuario=$unad80usuario;
		}
	}
function guardar($objdb, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_1580=$APP->rutacomun.'lg/lg_1580_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1580)){$mensajes_1580=$APP->rutacomun.'lg/lg_1580_es.php';}
	require $mensajes_todas;
	require $mensajes_1580;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	
	// -- Seccion para validar los posibles causales de error.
	if ($this->unad80usuario==0){$sError=$ERR['unad80usuario'];}
	//if ($this->unad80mensaje==''){$sError=$ERR['unad80mensaje'];}
	if ($this->unad80idpadre==''){$sError=$ERR['unad80idpadre'];}
	if ($this->unad80idref==''){$sError=$ERR['unad80idref'];}
	if ($this->unad80idproceso==''){$sError=$ERR['unad80idproceso'];}
	$idAccion=3;
	if ($sError==''){
		if ($this->unad80id==''){
			$idAccion=2;
			if ($this->unad80consec==''){
				$this->unad80consec=tabla_consecutivo('unad80foro', 'unad80consec', 'unad80idproceso='.$this->unad80idproceso.' AND unad80idref='.$this->unad80idref.' AND unad80idpadre='.$this->unad80idpadre.'', $objdb);
				if ($this->unad80consec==-1){$sError=$objdb->serror;}
				}else{
				if (!seg_revisa_permiso($this->iCodModulo, 8, $objdb)){
					$sError=$ERR['8'];
					$this->unad80consec='';
					}
				}
			if ($sError==''){
				$sql='SELECT unad80idproceso FROM unad80foro WHERE unad80idproceso='.$this->unad80idproceso.' AND unad80idref='.$this->unad80idref.' AND unad80idpadre='.$this->unad80idpadre.' AND unad80consec='.$this->unad80consec.'';
				$result=$objdb->ejecutasql($sql);
				if ($objdb->nf($result)!=0){
					$sError=$ERR['existe'];
					}else{
					if (!seg_revisa_permiso($this->iCodModulo, 2, $objdb)){$sError=$ERR['2'];}
					}
				}
			}else{
			if (!seg_revisa_permiso($this->iCodModulo, 3, $objdb)){$sError=$ERR['3'];}
			}
		}
	if ($sError==''){
		if ($idAccion==2){
			//Preparar el Id, Si no lo hay se quita la comprobación.
			$this->unad80id=tabla_consecutivo('unad80foro','unad80id', '', $objdb);
			if ($this->unad80id==-1){$sError=$objdb->serror;}
			}
		}
	if ($sError==''){
		if (get_magic_quotes_gpc()==1){$this->unad80mensaje=stripslashes($this->unad80mensaje);}
		//Si el campo unad80mensaje permite html quite la linea htmlspecialchars para el campo y habilite la siguiente linea:
		//$unad80mensaje=addslashes($this->unad80mensaje);
		$unad80mensaje=str_replace('&quot;', '\"', $this->unad80mensaje);
		$bpasa=false;
		if ($idAccion==2){
			$this->unad80ifecha=0;
			$this->unad80hora=0;
			$this->unad80minuto=0;
			//$this->unad80usuario=0; //$_SESSION['u_idtercero'];
			$sCampos1580='unad80idproceso, unad80idref, unad80idpadre, unad80consec, unad80id, unad80mensaje, unad80ifecha, unad80hora, unad80minuto, unad80usuario';
			$sValores1580=''.$this->unad80idproceso.', '.$this->unad80idref.', '.$this->unad80idpadre.', '.$this->unad80consec.', '.$this->unad80id.', "'.$unad80mensaje.'", '.$this->unad80ifecha.', '.$this->unad80hora.', '.$this->unad80minuto.', '.$this->unad80usuario.'';
			if ($APP->utf8==1){
				$sql='INSERT INTO unad80foro ('.$sCampos1580.') VALUES ('.utf8_encode($sValores1580).');';
				$sdetalle=$sCampos1580.'['.utf8_encode($sValores1580).']';
				}else{
				$sql='INSERT INTO unad80foro ('.$sCampos1580.') VALUES ('.$sValores1580.');';
				$sdetalle=$sCampos1580.'['.$sValores1580.']';
				}
			$bpasa=true;
			}else{
			$scampo[1]='unad80mensaje';
			$sdato[1]=$unad80mensaje;
			$numcmod=1;
			$sWhere='unad80id='.$this->unad80id.'';
			$sql='SELECT * FROM unad80foro WHERE '.$sWhere;
			$sdatos='';
			$result=$objdb->ejecutasql($sql);
			if ($objdb->nf($result)>0){
				$filabase=$objdb->sf($result);
				$bsepara=false;
				for ($k=1;$k<=$numcmod;$k++){
					if ($filabase[$scampo[$k]]!=$sdato[$k]){
						if ($sdatos!=''){$sdatos=$sdatos.', ';}
						$sdatos=$sdatos.$scampo[$k].'="'.$sdato[$k].'"';
						$bpasa=true;
						}
					}
				}
			if ($bpasa){
				if ($APP->utf8==1){
					$sdetalle=utf8_encode($sdatos).'['.$sWhere.']';
					$sql='UPDATE unad80foro SET '.utf8_encode($sdatos).' WHERE '.$sWhere.';';
					}else{
					$sdetalle=$sdatos.'['.$sWhere.']';
					$sql='UPDATE unad80foro SET '.$sdatos.' WHERE '.$sWhere.';';
					}
				}
			}
		if ($bpasa){
			$result=$objdb->ejecutasql($sql);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' ..<!-- '.$sql.' -->';
				if ($idAccion==2){$this->unad80id='';}
				}else{
				$iTipoError=1;
				if ($idAccion==2){
					$bAudita=$this->bAuditaInsertar;
					$sError=$ETI['itemguardado'];
					}else{
					$bAudita=$this->bAuditaModificar;
					$sError=$ETI['itemmodificado'];
					}
				if ($bAudita){seg_auditar($this->iCodModulo, $_SESSION['unad_id_tercero'], $idAccion, $this->unad80id, $sdetalle, $objdb);}
				}
			}
		}
	if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Esto es un debug<br>';}
	return array($sError, $iTipoError, $idAccion, $sDebug);
	}
function nuevo(){
	$this->unad80idproceso=0;
	$this->unad80idref=0;
	$this->unad80idpadre=0;
	$this->unad80consec='';
	$this->unad80id='';
	$this->unad80mensaje='';
	$this->unad80ifecha=0;
	$this->unad80hora=0;
	$this->unad80minuto=0;
	$this->unad80usuario=0;
	}
function traerxid($unad80id, $objdb){
	$sqlcondi='unad80id='.$unad80id.'';
	$sql='SELECT * FROM unad80foro WHERE '.$sqlcondi;
	$tabla=$objdb->ejecutasql($sql);
	if ($objdb->nf($tabla)>0){
		$fila=$objdb->sf($tabla);
		$this->unad80idproceso=$fila['unad80idproceso'];
		$this->unad80idref=$fila['unad80idref'];
		$this->unad80idpadre=$fila['unad80idpadre'];
		$this->unad80consec=$fila['unad80consec'];
		$this->unad80id=$fila['unad80id'];
		$this->unad80mensaje=$fila['unad80mensaje'];
		$this->unad80ifecha=$fila['unad80ifecha'];
		$this->unad80hora=$fila['unad80hora'];
		$this->unad80minuto=$fila['unad80minuto'];
		$this->unad80usuario=$fila['unad80usuario'];
		}
	}
function __construct(){
	$this->nuevo();
	}
//Fin de la clase
}
?>