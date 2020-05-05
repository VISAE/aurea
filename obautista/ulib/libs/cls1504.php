<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2018 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Version 2.22.3 jueves, 2 de agosto de 2018
--- bita04bitacora Bitacoras
*/
class clsT1504{
var $iCodModulo=1504;
var $bAuditaInsertar=true;
var $bAuditaModificar=true;
var $bAuditaEliminar=true;
var $bita04consec='';
var $bita04id='';
var $bita04estado=0;
var $bita04idtiposolicitud='';
var $bita04idsolicita=0;
var $bita04fecha='00/00/0000';
var $bita04hora=0;
var $bita04minuto=0;
var $bita04cead=0;
var $bita04ticketsau='';
var $bita04prioridad='';
var $bita04detalle='';
var $bita04idatiende=0;
var $bita04fechaprobable='00/00/0000';
var $bita04fecharesuelve='00/00/0000';
var $bita04idtema='';
var $bita04idtiposolicitante='';
var $bita04orden=0; //0 para las pendiente, 7 para las resueltas y 9 para las anuladas.
var $bita04idelabora=0;
function dato_bita04idelabora($bita04idelabora, $bita04idelabora_td, $bita04idelabora_doc, $objDB, $sPrevio='El tercero Elabora '){
	$sError='';
	if ($sError==''){$sError=tabla_terceros_existe($bita04idelabora_td, $bita04idelabora_doc, $objDB, $sPrevio);}
	if ($sError==''){
		list($sError, $sInfo)=tercero_Bloqueado($bita04idelabora, $objDB);
		if ($sInfo!=''){$sError=$sError.'<br>'.sInfo;}
		}
	if ($sError==''){
		$this->bita04idelabora=$bita04idelabora;
		}
	}
function dato_bita04idatiende($bita04idatiende, $bita04idatiende_td, $bita04idatiende_doc, $objDB, $sPrevio='El tercero Atiende '){
	$sError='';
	if ($sError==''){$sError=tabla_terceros_existe($bita04idatiende_td, $bita04idatiende_doc, $objDB, $sPrevio);}
	if ($sError==''){
		list($sError, $sInfo)=tercero_Bloqueado($bita04idatiende, $objDB);
		if ($sInfo!=''){$sError=$sError.'<br>'.sInfo;}
		}
	if ($sError==''){
		$this->bita04idatiende=$bita04idatiende;
		}
	}
function dato_bita04idsolicita($bita04idsolicita, $bita04idsolicita_td, $bita04idsolicita_doc, $objDB, $sPrevio='El tercero Solicita '){
	$sError='';
	if ($sError==''){$sError=tabla_terceros_existe($bita04idsolicita_td, $bita04idsolicita_doc, $objDB, $sPrevio);}
	if ($sError==''){
		list($sError, $sInfo)=tercero_Bloqueado($bita04idsolicita, $objDB);
		if ($sInfo!=''){$sError=$sError.'<br>'.sInfo;}
		}
	if ($sError==''){
		$this->bita04idsolicita=$bita04idsolicita;
		}
	}
function guardar($objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_1504=$APP->rutacomun.'lg/lg_1504_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_1504)){$mensajes_1504=$APP->rutacomun.'lg/lg_1504_es.php';}
	require $mensajes_todas;
	require $mensajes_1504;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	
	/* -- Seccion para validar los posibles causales de error. */
	if ($this->bita04idelabora==0){$sError=$ERR['bita04idelabora'];}
	//if ($this->bita04orden==''){$sError=$ERR['bita04orden'];}
	if ($this->bita04idtiposolicitante==''){$sError=$ERR['bita04idtiposolicitante'];}
	if ($this->bita04idtema==''){$sError=$ERR['bita04idtema'];}
	if (!fecha_esvalida($this->bita04fecharesuelve)){
		$this->bita04fecharesuelve='00/00/0000';
		//$sError=$ERR['bita04fecharesuelve'];
		}
	if (!fecha_esvalida($this->bita04fechaprobable)){
		$this->bita04fechaprobable='00/00/0000';
		//$sError=$ERR['bita04fechaprobable'];
		}
	if ($this->bita04idatiende==0){$sError=$ERR['bita04idatiende'];}
	//if ($this->bita04detalle==''){$sError=$ERR['bita04detalle'];}
	if ($this->bita04prioridad==''){$sError=$ERR['bita04prioridad'];}
	//if ($this->bita04ticketsau==''){$sError=$ERR['bita04ticketsau'];}
	if ($this->bita04cead==''){
		$this->bita04cead=0;
		//$sError=$ERR['bita04cead'];
		}
	if ($this->bita04minuto==''){$sError=$ERR['bita04minuto'];}
	if ($this->bita04hora==''){$sError=$ERR['bita04hora'];}
	if (!fecha_esvalida($this->bita04fecha)){
		//$this->bita04fecha='00/00/0000';
		$sError=$ERR['bita04fecha'];
		}
	if ($this->bita04idsolicita==0){$sError=$ERR['bita04idsolicita'];}
	if ($this->bita04idtiposolicitud==''){$sError=$ERR['bita04idtiposolicitud'];}
	$idAccion=3;
	if ($sError==''){
		if ($this->bita04id==''){
			$idAccion=2;
			if ($this->bita04consec==''){
				$this->bita04consec=tabla_consecutivo('bita04bitacora', 'bita04consec', '', $objDB);
				if ($this->bita04consec==-1){$sError=$objDB->serror;}
				}else{
				if (!seg_revisa_permiso($this->iCodModulo, 8, $objDB)){
					$sError=$ERR['8'];
					$this->bita04consec='';
					}
				}
			if ($sError==''){
				$sSQL='SELECT bita04consec FROM bita04bitacora WHERE bita04consec='.$this->bita04consec.'';
				$result=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($result)!=0){
					$sError=$ERR['existe'];
					}else{
					if (!seg_revisa_permiso($this->iCodModulo, 2, $objDB)){$sError=$ERR['2'];}
					}
				}
			}else{
			if (!seg_revisa_permiso($this->iCodModulo, 3, $objDB)){$sError=$ERR['3'];}
			}
		}
	if ($sError==''){
		if ($idAccion==2){
			/* Preparar el Id, Si no lo hay se quita la comprobación. */
			$this->bita04id=tabla_consecutivo('bita04bitacora','bita04id', '', $objDB);
			if ($this->bita04id==-1){$sError=$objDB->serror;}
			}
		}
	if ($sError==''){
		//if ($this->bita04orden==''){$this->bita04orden=$this->bita04consec;}
		if (get_magic_quotes_gpc()==1){$this->bita04detalle=stripslashes($this->bita04detalle);}
		/* Si el campo bita04detalle permite html quite la linea htmlspecialchars para el campo y habilite la siguiente linea: */
		//$bita04detalle=addslashes($this->bita04detalle);
		$bita04detalle=str_replace('"', '\"', $this->bita04detalle);
		$bpasa=false;
		if ($idAccion==2){
			//$this->bita04estado=0;
			$sCampos1504='bita04consec, bita04id, bita04estado, bita04idtiposolicitud, bita04idsolicita, bita04fecha, bita04hora, bita04minuto, bita04cead, bita04ticketsau, 
bita04prioridad, bita04detalle, bita04idatiende, bita04fechaprobable, bita04fecharesuelve, bita04idtema, bita04idtiposolicitante, bita04orden, bita04idelabora';
			$sValores1504=''.$this->bita04consec.', '.$this->bita04id.', '.$this->bita04estado.', '.$this->bita04idtiposolicitud.', '.$this->bita04idsolicita.', "'.$this->bita04fecha.'", '.$this->bita04hora.', '.$this->bita04minuto.', '.$this->bita04cead.', "'.$this->bita04ticketsau.'", 
'.$this->bita04prioridad.', "'.$bita04detalle.'", '.$this->bita04idatiende.', "'.$this->bita04fechaprobable.'", "'.$this->bita04fecharesuelve.'", '.$this->bita04idtema.', '.$this->bita04idtiposolicitante.', '.$this->bita04orden.', '.$this->bita04idelabora.'';
			if ($APP->utf8==1){
				$sSQL='INSERT INTO bita04bitacora ('.$sCampos1504.') VALUES ('.utf8_encode($sValores1504).');';
				$sdetalle=$sCampos1504.'['.utf8_encode($sValores1504).']';
				}else{
				$sSQL='INSERT INTO bita04bitacora ('.$sCampos1504.') VALUES ('.$sValores1504.');';
				$sdetalle=$sCampos1504.'['.$sValores1504.']';
				}
			$bpasa=true;
			}else{
			$scampo[1]='bita04idsolicita';
			$scampo[2]='bita04fecha';
			$scampo[3]='bita04hora';
			$scampo[4]='bita04minuto';
			$scampo[5]='bita04cead';
			$scampo[6]='bita04ticketsau';
			$scampo[7]='bita04prioridad';
			$scampo[8]='bita04detalle';
			$scampo[9]='bita04idatiende';
			$scampo[10]='bita04fechaprobable';
			$scampo[11]='bita04fecharesuelve';
			$scampo[12]='bita04idtema';
			$scampo[13]='bita04idtiposolicitante';
			$scampo[14]='bita04orden';
			$scampo[15]='bita04idelabora';
			$sdato[1]=$this->bita04idsolicita;
			$sdato[2]=$this->bita04fecha;
			$sdato[3]=$this->bita04hora;
			$sdato[4]=$this->bita04minuto;
			$sdato[5]=$this->bita04cead;
			$sdato[6]=$this->bita04ticketsau;
			$sdato[7]=$this->bita04prioridad;
			$sdato[8]=$bita04detalle;
			$sdato[9]=$this->bita04idatiende;
			$sdato[10]=$this->bita04fechaprobable;
			$sdato[11]=$this->bita04fecharesuelve;
			$sdato[12]=$this->bita04idtema;
			$sdato[13]=$this->bita04idtiposolicitante;
			$sdato[14]=$this->bita04orden;
			$sdato[15]=$this->bita04idelabora;
			$numcmod=15;
			$sWhere='bita04id='.$this->bita04id.'';
			$sSQL='SELECT * FROM bita04bitacora WHERE '.$sWhere;
			$sdatos='';
			$bPrimera=true;
			$result=$objDB->ejecutasql($sSQL);
			if ($result==false){$sDebug=$sDebug.fecha_microtiempo().' FALLA LECTURA DE DATOS [1504]: '.$sSQL.'<br>';}
			if ($objDB->nf($result)>0){
				$filabase=$objDB->sf($result);
				if ($bDebug&&$bPrimera){
					for ($k=1;$k<=$numcmod;$k++){
						if (isset($filabase[$scampo[$k]])==0){
							$sDebug=$sDebug.fecha_microtiempo().' FALLA CODIGO: Falta el campo '.$k.' '.$scampo[$k].'<br>';
							}
						}
					$bPrimera=false;
					}
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
					$sSQL='UPDATE bita04bitacora SET '.utf8_encode($sdatos).' WHERE '.$sWhere.';';
					}else{
					$sdetalle=$sdatos.'['.$sWhere.']';
					$sSQL='UPDATE bita04bitacora SET '.$sdatos.' WHERE '.$sWhere.';';
					}
				}
			}
		if ($bpasa){
			$result=$objDB->ejecutasql($sSQL);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' [1504] ..<!-- '.$sSQL.' -->';
				if ($idAccion==2){$this->bita04id='';}
				}else{
				$iTipoError=1;
				if ($idAccion==2){
					$bAudita=$this->bAuditaInsertar;
					$sError=$ETI['msg_itemguardado'];
					}else{
					$bAudita=$this->bAuditaModificar;
					$sError=$ETI['msg_itemmodificado'];
					}
				if ($bAudita){seg_auditar($this->iCodModulo, $_SESSION['unad_id_tercero'], $idAccion, $this->bita04id, $sdetalle, $objDB);}
				}
			}
		}
	if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Esto es un debug<br>';}
	return array($sError, $iTipoError, $idAccion, $sDebug);
	}
function nuevo($iTipo=0){
	$sHoy=fecha_hoy();
	$this->bita04consec='';
	$this->bita04id='';
	$this->bita04estado=0;
	$this->bita04idtiposolicitud=0;
	$this->bita04idsolicita=0;
	$this->bita04fecha=$sHoy;
	$this->bita04hora=fecha_hora();
	$this->bita04minuto=fecha_minuto();
	$this->bita04cead=0;
	$this->bita04ticketsau='';
	$this->bita04prioridad=5;
	$this->bita04detalle='';
	$this->bita04idatiende=$_SESSION['unad_id_tercero'];
	$this->bita04fechaprobable='00/00/0000';
	$this->bita04fecharesuelve='00/00/0000';
	$this->bita04idtema=0;
	$this->bita04idtiposolicitante=0;
	$this->bita04orden=0;
	$this->bita04idelabora=$_SESSION['unad_id_tercero'];
	if ($iTipo==4){
		$this->bita04estado=7;
		$this->bita04idtiposolicitud=1;
		$this->bita04idsolicita=0;
		$this->bita04detalle='Se envia correo recuperacion';
		$this->bita04idtema=5;
		$this->bita04idtiposolicitante=1;
		$this->bita04fecharesuelve=$sHoy;
		$this->bita04orden=7;
		}
	if ($iTipo==1701){
		//Respaldo de cursos
		$this->bita04estado=7;
		$this->bita04idtiposolicitud=7;
		$this->bita04idsolicita=$_SESSION['unad_id_tercero'];
		$this->bita04detalle='Copia de curso';
		$this->bita04idtema=32;
		$this->bita04idtiposolicitante=2;
		$this->bita04fecharesuelve=$sHoy;
		$this->bita04orden=7;
		}
	if ($iTipo==1702){
		//Migrar cursos
		$this->bita04estado=7;
		$this->bita04idtiposolicitud=7;
		$this->bita04idsolicita=$_SESSION['unad_id_tercero'];
		$this->bita04detalle='Restauracion';
		$this->bita04idtema=31;
		$this->bita04idtiposolicitante=2;
		$this->bita04fecharesuelve=$sHoy;
		$this->bita04orden=7;
		}
	if ($iTipo==1703){
		//Tablas de calificaciones.
		$this->bita04estado=7;
		$this->bita04idtiposolicitud=7;
		$this->bita04idsolicita=$_SESSION['unad_id_tercero'];
		$this->bita04detalle='Revision tabla de calificaciones';
		$this->bita04idtema=33;
		$this->bita04idtiposolicitante=2;
		$this->bita04fecharesuelve=$sHoy;
		$this->bita04orden=7;
		}
	}
function traerxid($bita04id, $objDB){
	$sSQLcondi='bita04id='.$bita04id.'';
	$sSQL='SELECT * FROM bita04bitacora WHERE '.$sSQLcondi;
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$this->bita04consec=$fila['bita04consec'];
		$this->bita04id=$fila['bita04id'];
		$this->bita04estado=$fila['bita04estado'];
		$this->bita04idtiposolicitud=$fila['bita04idtiposolicitud'];
		$this->bita04idsolicita=$fila['bita04idsolicita'];
		$this->bita04fecha=$fila['bita04fecha'];
		$this->bita04hora=$fila['bita04hora'];
		$this->bita04minuto=$fila['bita04minuto'];
		$this->bita04cead=$fila['bita04cead'];
		$this->bita04ticketsau=$fila['bita04ticketsau'];
		$this->bita04prioridad=$fila['bita04prioridad'];
		$this->bita04detalle=$fila['bita04detalle'];
		$this->bita04idatiende=$fila['bita04idatiende'];
		$this->bita04fechaprobable=$fila['bita04fechaprobable'];
		$this->bita04fecharesuelve=$fila['bita04fecharesuelve'];
		$this->bita04idtema=$fila['bita04idtema'];
		$this->bita04idtiposolicitante=$fila['bita04idtiposolicitante'];
		$this->bita04orden=$fila['bita04orden'];
		$this->bita04idelabora=$fila['bita04idelabora'];
		}
	}
function __construct(){
	$this->nuevo();
	}
/* Fin de la clase */
}
?>