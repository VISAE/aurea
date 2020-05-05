<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2019 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
*/
/** Archivo librai.php.
* @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
* @date lunes, 24 de junio de 2019
*/
class cls2203_rpdei{
var $iEstado=0;
var $iFormaNota=0;
var $iNivel=0;
var $iNota75=0;
var $iNota25=0;
var $iNumCreAprobados=0;
var $iNumCreditos=0;
var $iTipoCurso=-1;
var $iObligatorio=0;
var $sCodCurso='';
var $sCodEquivalente='';
var $sNomCurso='';
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
var $iPeso=0;
function __construct($sCodCurso, $sNomCurso, $iTipoCurso, $iNumCreditos){
	$this->sCodCurso=$sCodCurso;
	$this->sCodEquivalente=$sCodCurso;
	$this->sNomCurso=$sNomCurso;
	$this->iNumCreditos=$iNumCreditos;
	$this->iTipoCurso=$iTipoCurso;
	switch($iTipoCurso){
		case 0:
		case 1:
		case 2:
		case 4:
		case 5:
		case 6:
		$this->iPeso=$iNumCreditos;
		break;
		case 3:
		$this->iPeso=1;
		break;
		default:
		$this->iPeso=0;
		break;
		}
	}}

class unad_rai{
var $sIdioma='es';

var $idEstudiantePrograma=0;

var $iCampoCredBasicos=0;
var $iCampoCredEspecificos=0;
var $iCampoCredElectivosBComun=0;
var $iCampoCredElectivosDComun=0;
var $iCampoCredElectivosDEsp=0;
var $iCampoCredElectivosDComp=0;

var $iJuegoCampos=0;

var $iNumCredBasicos=0;
var $iNumCredEspecificos=0;
var $iNumCredElectivosBComun=0;
var $iNumCredElectivosDComun=0;
var $iNumCredElectivosDEsp=0;
var $iNumCredElectivosDComp=0;
var $iNumCredRequisitos=0;

var $iNumAprobBasicos=0;
var $iNumAprobEspecificos=0;
var $iNumAprobElectivosBComun=0;
var $iNumAprobElectivosDComun=0;
var $iNumAprobElectivosDEsp=0;
var $iNumAprobElectivosDComp=0;
var $iNumAprobRequisitos=0;

var $iDisponiblesElectivosBComun=0;
var $iDisponiblesElectivosDComun=0;
var $iDisponiblesRequisitos=0;

var $idPrograma=0;
var $idPlanEstudios=0;
var $idTercero=0;
var $idVisual=0;
var $sCodPlanEstudio='';
var $sError='';
var $sNomPrograma='';
//Variables para el detalle.
var $aAprobados=array();
var $aFilas=array();
var $aNecesarios=array();
var $aRegistros=array();
var $aTipoCredito=array();
var $idContenedor=0;
var $idPlan=0;
var $idPlanOrigen=0;
var $iNecesarios=0;
var $iRegistros=0;
var $iPesoTotal=0;

function limpiar(){
	$this->idEstudiantePrograma=0;
	$this->sNomPrograma='';
	$this->idPlanEstudios=0;
	$this->sCodPlanEstudio='';
	$this->sError='';
	$this->iCampoCredBasicos=0;
	$this->iCampoCredEspecificos=0;
	$this->iCampoCredElectivosBComun=0;
	$this->iCampoCredElectivosDComun=0;
	$this->iCampoCredElectivosDEsp=0;
	$this->iCampoCredElectivosDComp=0;
	$this->iNumCredBasicos=0;
	$this->iNumCredEspecificos=0;
	$this->iNumCredElectivosBComun=0;
	$this->iNumCredElectivosDComun=0;
	$this->iNumCredElectivosDEsp=0;
	$this->iNumCredElectivosDComp=0;
	$this->iNumCredRequisitos=0;
	$this->iNumAprobBasicos=0;
	$this->iNumAprobEspecificos=0;
	$this->iNumAprobElectivosBComun=0;
	$this->iNumAprobElectivosDComun=0;
	$this->iNumAprobElectivosDEsp=0;
	$this->iNumAprobElectivosDComp=0;
	$this->iNumAprobRequisitos=0;
	$this->iDisponiblesElectivosBComun=0;
	$this->iDisponiblesElectivosDComun=0;
	$this->iDisponiblesRequisitos=0;
	$this->LimpiarDetalle();
	}
function LimpiarDetalle(){
	$this->iNecesarios=0;
	$this->iRegistros=0;
	$this->iPesoTotal=0;
	$this->aNecesarios=array();
	$this->aRegistros=array();
	for ($k=0;$k<10;$k++){
		$this->aNecesarios[$k]=0;
		$this->aAprobados[$k]=0;
		$this->aFilas[$k]=0;
		$this->aTipoCredito[$k]='{'.$k.'}';
		}
	}
function html_encabezado(){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$this->sIdioma.'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2200=$APP->rutacomun.'lg/lg_2200_'.$this->sIdioma.'.php';
	if (!file_exists($mensajes_2200)){$mensajes_2200=$APP->rutacomun.'lg/lg_2200_es.php';}
	require $mensajes_todas;
	require $mensajes_2200;
	$sDebug='';
	$sCampoBasicos='';
	$sCompBasicos='';
	$sCampoEspecificos='';
	$sCampoElectivoComun='';
	$sCampoElectivoDComun='';
	$sCampoElectivoDEspecifico='';
	$sCampoElectivoDComp='';
	$sCampoTotalElectivos='';

	$sAprobadoBasicos='';
	$sAprobadoEspecificos='';
	$sAprobadoElectivoComun='';
	$sAprobadoElectivoDComun='';
	$sAprobadoElectivoDEsp='';
	$sAprobadoElectivoDComp='';
	$sAprobadoRequisitoGrado='';
	
	$sDispElectivoComun='';
	$sDispElectivoDComun='';
	$sDispRequisitoGrado='';
	
	$sAvance='';
	$iTotalElectivos=$this->iCampoCredElectivosBComun+$this->iCampoCredElectivosDComun+$this->iCampoCredElectivosDEsp+$this->iCampoCredElectivosDComp;
	switch($this->iJuegoCampos){
		case 1:// Vista version del programa
		$sCampoBasicos='<input id="core10numcredbasicos" name="core10numcredbasicos" type="text" value="'.$this->iCampoCredBasicos.'" class="cuatro" maxlength="10" placeholder="0"/>';
		$sCompBasicos='&nbsp;/ ';
		$sCampoEspecificos='<input id="core10numcredespecificos" name="core10numcredespecificos" type="text" value="'.$this->iCampoCredEspecificos.'" class="cuatro" maxlength="10" placeholder="0"/>';
		$sCampoElectivoComun='<input id="core10numcredelecgenerales" name="core10numcredelecgenerales" type="text" value="'.$this->iCampoCredElectivosBComun.'" class="cuatro" maxlength="10" placeholder="0"/>';
		$sCampoElectivoDComun='<input id="core10numcredelecescuela" name="core10numcredelecescuela" type="text" value="'.$this->iCampoCredElectivosDComun.'" class="cuatro" maxlength="10" placeholder="0"/>';
		$sCampoElectivoDEspecifico='<input id="core10numcredelecprograma" name="core10numcredelecprograma" type="text" value="'.$this->iCampoCredElectivosDEsp.'" class="cuatro" maxlength="10" placeholder="0"/>';
		$sCampoElectivoDComp='<input id="core10numcredeleccomplem" name="core10numcredeleccomplem" type="text" value="'.$this->iCampoCredElectivosDComp.'" class="cuatro" maxlength="10" placeholder="0"/>';
		$sCampoTotalElectivos='<input id="core10numcredelectivos" name="core10numcredelectivos" type="hidden" value="'.$iTotalElectivos.'"/>';
		break;
		case 2: //Plan de estudios individual
		$iAprobados=$this->iNumAprobBasicos+$this->iNumAprobEspecificos+$this->iNumAprobElectivosBComun+$this->iNumAprobElectivosDComun+$this->iNumAprobElectivosDEsp+$this->iNumAprobElectivosDComp;
		//+$this->iNumAprobRequisitos
		$iRequeridos=$this->iCampoCredBasicos+$this->iCampoCredEspecificos+$this->iCampoCredElectivosBComun+$this->iCampoCredElectivosDComun+$this->iCampoCredElectivosDEsp+$this->iCampoCredElectivosDComp;
		//+$this->iNumCredRequisitos
		if ($this->iCampoCredBasicos>0){$sAprobadoBasicos=''.$this->iNumAprobBasicos.' de ';}
		if ($this->iCampoCredEspecificos>0){$sAprobadoEspecificos=''.$this->iNumAprobEspecificos.' de ';}
		if ($this->iCampoCredElectivosBComun>0){$sAprobadoElectivoComun=''.$this->iNumAprobElectivosBComun.' de ';}
		if ($this->iCampoCredElectivosDComun>0){$sAprobadoElectivoDComun=''.$this->iNumAprobElectivosDComun.' de ';}
		if ($this->iCampoCredElectivosDEsp>0){$sAprobadoElectivoDEsp=''.$this->iNumAprobElectivosDEsp.' de ';}
		if ($this->iCampoCredElectivosDComp>0){$sAprobadoElectivoDComp=''.$this->iNumAprobElectivosDComp.' de ';}
		if ($this->iNumCredRequisitos>0){$sAprobadoRequisitoGrado=''.$this->iNumAprobRequisitos.' de ';}

		$sDispElectivoComun='&nbsp;/ '.$this->iDisponiblesElectivosBComun;
		$sDispElectivoDComun='&nbsp;/ '.$this->iDisponiblesElectivosDComun;
		$sDispRequisitoGrado='&nbsp;/ '.$this->iDisponiblesRequisitos;

		$sCampoBasicos='<input id="core01numcredbasicos" name="core01numcredbasicos" type="hidden" value="'.$this->iCampoCredBasicos.'"/><b>'.$this->iCampoCredBasicos.'</b>';
		$sCompBasicos='&nbsp;/ ';
		$sCampoEspecificos='<input id="core01numcredespecificos" name="core01numcredespecificos" type="hidden" value="'.$this->iCampoCredEspecificos.'"/><b>'.$this->iCampoCredEspecificos.'</b>';
		$sCampoElectivoComun='<input id="core01numcredelecgenerales" name="core01numcredelecgenerales" type="hidden" value="'.$this->iCampoCredElectivosBComun.'"/><b>'.$this->iCampoCredElectivosBComun.'</b>';
		$sCampoElectivoDComun='<input id="core01numcredelecescuela" name="core01numcredelecescuela" type="hidden" value="'.$this->iCampoCredElectivosDComun.'"/><b>'.$this->iCampoCredElectivosDComun.'</b>';
		$sCampoElectivoDEspecifico='<input id="core01numcredelecprograma" name="core01numcredelecprograma" type="hidden" value="'.$this->iCampoCredElectivosDEsp.'"/><b>'.$this->iCampoCredElectivosDEsp.'</b>';
		$sCampoElectivoDComp='<input id="core01numcredeleccomplem" name="core01numcredeleccomplem" type="hidden" value="'.$this->iCampoCredElectivosDComp.'"/><b>'.$this->iCampoCredElectivosDComp.'</b>';
		$sCampoTotalElectivos='<input id="core01numcredelectivos" name="core01numcredelectivos" type="hidden" value="'.$iTotalElectivos.'"/>';
		$iAvance=0;
		if ($iRequeridos>0){
			$iAvance=($iAprobados/$iRequeridos)*100;
			}
		$sAvance='<tr>
<td colspan="13" align="center" class="fondoazul">'.$ETI['msg_avance'].': <b>'.formato_numero($iAvance, 2).' %</b></td>
<td colspan="1"></td>
</tr>';
		break;
		default:
		break;
		}
	$sHTML='<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">
<tr class="fondoazul">
<td colspan="14" align="center"><b>'.$ETI['msg_creditos'].'</b></td>
</tr>
<tr align="center">
<td rowspan="2" colspan="2" class="fondoazul">'.$ETI['msg_credbasicos'].'</td>
<td rowspan="2" colspan="2" class="fondoazul">'.$ETI['msg_credespecificos'].'</td>
<td rowspan="2" bgcolor="#FFFFFF">&nbsp;</td>
<td rowspan="2" class="fondoazul">'.$ETI['msg_electivobas'].'</td>
<td colspan="5">'.$ETI['msg_electivodisc'].'</td>
<td rowspan="2" align="center" class="fondoazul">'.$ETI['msg_total'].'</td>
<td rowspan="2" bgcolor="#FFFFFF">&nbsp;</td>
<td rowspan="2" class="fondoazul">'.$ETI['msg_credrequisitos'].'</td>
</tr>
<tr align="center">
<td align="center">'.$ETI['msg_credelecescuela'].'</td>
<td align="center" colspan="2">'.$ETI['msg_credelecprograma'].'</td>
<td align="center" colspan="2">'.$ETI['msg_credeleccomplem'].'</td>
</tr>
<tr>
<td align="right">'.$sAprobadoBasicos.$sCampoBasicos.'</td>
<td><div id="lbl_basicos">'.$sCompBasicos.$this->iNumCredBasicos.'</div></td>
<td align="right">'.$sAprobadoEspecificos.$sCampoEspecificos.'</td>
<td><div id="lbl_especificos">'.$sCompBasicos.$this->iNumCredEspecificos.'</div></td>
<td class="fondoazul"></td>
<td align="center">'.$sAprobadoElectivoComun.$sCampoElectivoComun.$sDispElectivoComun.'</td>
<td align="center">'.$sAprobadoElectivoDComun.$sCampoElectivoDComun.$sDispElectivoDComun.'</td>
<td align="right">'.$sAprobadoElectivoDEsp.$sCampoElectivoDEspecifico.'</td>
<td><div id="lbl_electivos">'.$sCompBasicos.$this->iNumCredElectivosDEsp.'</div></td>
<td align="right">'.$sAprobadoElectivoDComp.$sCampoElectivoDComp.'</td>
<td><div id="lbl_electivosComp">'.$sCompBasicos.$this->iNumCredElectivosDComp.'</div></td>
<td align="center"><div id="lbl_electivost">'.$sCampoTotalElectivos.'<b>'.$iTotalElectivos.'</b></div></td>
<td class="fondoazul"></td>
<td align="center"><div id="lbl_requisitos">'.$sAprobadoRequisitoGrado.''.$this->iNumCredRequisitos.$sDispRequisitoGrado.'</div></td>
</tr>'.$sAvance.'
</table>';
	return array($sHTML, $sDebug);
	}
function cargar($objDB, $bDebug=false){
	$sError='';
	$sDebug='';
	$this->limpiar();
	$core01id=0;
	$bConDetalle=false;
	list($idContenedor, $sErrorCont)=f1011_BloqueTercero($this->idTercero, $objDB);
	$this->idContenedor=$idContenedor;
	$sSQL='SELECT TB.core01id, TB.core01idprograma, T9.core09nombre, TB.core01idplandeestudios, TB.core01idrevision, TB.core01peracainicial, 
TB.core01numcredbasicos, TB.core01numcredespecificos, TB.core01numcredelecgenerales, TB.core01numcredelecescuela, TB.core01numcredelecprograma, TB.core01numcredeleccomplem, 
TB.core01numcredbasicosaprob, TB.core01numcredespecificosaprob, TB.core01numcredelecgeneralesaprob, TB.core01numcredelecescuelaaprob, TB.core01numcredelecprogramaaaprob, TB.core01numcredeleccomplemaprob, TB.core01numcredelectivosaprob 
FROM core01estprograma AS TB, core09programa AS T9 
WHERE TB.core01idtercero='.$this->idTercero.' AND TB.core01idprograma='.$this->idPrograma.' AND TB.core01idprograma=T9.core09id';
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$core01id=$fila['core01id'];
		$this->idEstudiantePrograma=$fila['core01id'];
		$this->sNomPrograma=$fila['core09nombre'];
		$this->idPlanEstudios=$fila['core01idplandeestudios'];

		$this->iCampoCredBasicos=$fila['core01numcredbasicos'];
		$this->iCampoCredEspecificos=$fila['core01numcredespecificos'];
		$this->iCampoCredElectivosBComun=$fila['core01numcredelecgenerales'];
		$this->iCampoCredElectivosDComun=$fila['core01numcredelecescuela'];
		$this->iCampoCredElectivosDEsp=$fila['core01numcredelecprograma'];
		$this->iCampoCredElectivosDComp=$fila['core01numcredeleccomplem'];

		$this->iNumAprobBasicos=$fila['core01numcredbasicosaprob'];
		$this->iNumAprobEspecificos=$fila['core01numcredespecificosaprob'];
		$this->iNumAprobElectivosBComun=$fila['core01numcredelecgeneralesaprob'];
		$this->iNumAprobElectivosDComun=$fila['core01numcredelecescuelaaprob'];
		$this->iNumAprobElectivosDEsp=$fila['core01numcredelecprogramaaaprob'];
		$this->iNumAprobElectivosDComp=$fila['core01numcredeleccomplemaprob'];
		if ($fila['core01idrevision']!=0){
			$bConDetalle=true;
			}else{
			if ($fila['core01peracainicial']>611){
				$bConDetalle=true;
				}
			}

		$sSQL='SELECT TB.core03itipocurso, SUM(TB.core03numcreditos) AS Creditos, COUNT(TB.core03id) AS Cant 
FROM core03plandeestudios_'.$idContenedor.' AS TB 
WHERE TB.core03idestprograma='.$core01id.' 
GROUP BY TB.core03itipocurso';
		$tabla=$objDB->ejecutasql($sSQL);
		while($fila=$objDB->sf($tabla)){
			switch($fila['core03itipocurso']){
				case 0:
				$this->iNumCredBasicos=$fila['Creditos'];
				break;
				case 1:
				$this->iNumCredEspecificos=$fila['Creditos'];
				break;
				case 2:
				$this->iNumCredElectivosDEsp=$fila['Creditos'];
				break;
				case 3:
				$this->iNumCredRequisitos=$fila['Creditos'];
				break;
				case 4:
				$this->iNumCredElectivosDComp=$fila['Creditos'];
				break;
				case 5:
				$this->iNumCredElectivosBComun=$fila['Creditos'];
				break;
				case 6:
				$this->iNumCredElectivosDComun=$fila['Creditos'];
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
		//$this->iNumAprobRequisitos=$fila[''];
		/*
		$this->iDisponiblesElectivosBComun=$fila[''];
		$this->iDisponiblesElectivosDComun=$fila[''];
		$this->iDisponiblesRequisitos=$fila[''];
		*/
		if ($bConDetalle){
			list($sErrorD, $sDebugD)=$this->CargarCuerpo($this->idEstudiantePrograma, $objDB, $bDebug);
			}
		}else{
		$this->sError='Error interno: No se ha encontrado el registro de programa Ref '.$this->idPrograma.'';
		}
	if ($this->idPlanEstudios!=0){
		$sSQL='SELECT core10consec FROM core10programaversion WHERE core10id='.$this->idPlanEstudios.'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$fila=$objDB->sf($tabla);
			$this->sCodPlanEstudio=$fila['core10consec'];
			}
		}else{
		require './app.php';
		//$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$this->sIdioma.'.php';
		//if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
		$mensajes_2200=$APP->rutacomun.'lg/lg_2200_'.$this->sIdioma.'.php';
		if (!file_exists($mensajes_2200)){$mensajes_2200=$APP->rutacomun.'lg/lg_2200_es.php';}
		//require $mensajes_todas;
		require $mensajes_2200;
		$this->sCodPlanEstudio='{'.$ETI['msg_peienrevision'].'}';
		}
	return array($sError, $sDebug);
	}
function CargarCuerpo($core01id, $objDB, $bDebug=false){
	$sError='';
	$sDebug='';
	$this->LimpiarDetalle();
	$sSQL='SELECT core01idplandeestudios, core01gradoestado FROM core01estprograma WHERE core01id='.$core01id.'';
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$this->idPlanOrigen=$fila['core01idplandeestudios'];
		}
	if ($this->idPlanOrigen<1){
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().'No se ha asignado un plan de estudios.<br>';}
		return array($sError, $sDebug);
		die();
		}
	$sSQL='SELECT core13id, core13nombre FROM core13tiporegistroprog';
	$tabla=$objDB->ejecutasql($sSQL);
	while($fila=$objDB->sf($tabla)){
		$this->aTipoCredito[$fila['core13id']]=$fila['core13nombre'];
		}
	$sSQL='SELECT core01numcredbasicos, core01numcredespecificos, core01numcredelecgenerales, core01numcredelecescuela, core01numcredelecprograma, core01numcredeleccomplem 
FROM core01estprograma 
WHERE core01id='.$core01id.'';
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$this->aNecesarios[0]=$fila['core01numcredbasicos'];
		$this->aNecesarios[1]=$fila['core01numcredespecificos'];
		$this->aNecesarios[5]=$fila['core01numcredelecgenerales'];
		$this->aNecesarios[6]=$fila['core01numcredelecescuela'];
		$this->aNecesarios[2]=$fila['core01numcredelecprograma'];
		$this->aNecesarios[4]=$fila['core01numcredeleccomplem'];
		}
	for ($k=0;$k<10;$k++){
		$this->iNecesarios=$this->iNecesarios+$this->aNecesarios[$k];
		}
	$sSQLadd='';
	$sSQLadd1='';
	$sSQL='SELECT TB.core03idcurso, TB.core03id, T4.unad40consec, T4.unad40nombre, TB.core03obligatorio, TB.core03numcreditos, TB.core03nivelcurso, TB.core03nota75, TB.core03fechanota75, TB.core03nota25, TB.core03fechanota25, TB.core03notahomologa, TB.core03fechanotahomologa, TB.core03detallehomologa, TB.core03fechainclusion, TB.core03notafinal, TB.core03formanota, TB.core03estado, TB.core03idtercero, TB.core03itipocurso, TB.core03peracaaprueba, TB.core03idusuarionota75, TB.core03idusuarionota25, TB.core03idusuarionotahomo 
FROM core03plandeestudios_'.$this->idContenedor.' AS TB, unad40curso AS T4 
WHERE '.$sSQLadd1.' TB.core03idestprograma='.$core01id.' AND TB.core03idcurso=T4.unad40id '.$sSQLadd.'
ORDER BY TB.core03itipocurso, TB.core03nivelcurso, TB.core03idcurso';
	if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Carga de datos: '.$sSQL.'<br>';}
	$tabla=$objDB->ejecutasql($sSQL);
	while($fila=$objDB->sf($tabla)){
		$objCurso=new cls2203_rpdei($fila['core03idcurso'], $fila['unad40nombre'],$fila['core03itipocurso'],$fila['core03numcreditos']);
		$objCurso->iObligatorio=$fila['core03obligatorio'];
		$objCurso->iNivel=$fila['core03nivelcurso'];
		$objCurso->iNota75=$fila['core03nota75'];
		$objCurso->iNota25=$fila['core03nota25'];
		$objCurso->iFormaNota=$fila['core03formanota'];
		$objCurso->iEstado=$fila['core03estado'];
		$this->iPesoTotal=$this->iPesoTotal+$objCurso->iPeso;
		$this->iRegistros++;
		$this->aRegistros[$this->iRegistros]=$objCurso;
		}
	return array($sError, $sDebug);
	}
function htmlCuerpo(){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2203=$APP->rutacomun.'lg/lg_2203_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2203)){$mensajes_2203=$APP->rutacomun.'lg/lg_2203_es.php';}
	require $mensajes_todas;
	require $mensajes_2203;
	if ($this->idPlanOrigen<1){
		return '<span class="rojo">'.$ETI['msg_noplan'].'</span>';
		die();
		}
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
<td><b>'.$ETI['core03fechanota75'].'</b></td>
<td><b>'.$ETI['core03fechanota25'].'</b></td>
<td align="right">
'.html_paginador('paginaf2203', $registros, $lineastabla, $pagina, 'paginarf2203()').'
'.html_lpp('lppf2203', $lineastabla, 'paginarf2203()').'
</td>
*/
	$sFilaEncabezado='<tr class="fondoazul">
<td colspan="2"><b>'.$ETI['core03idcurso'].'</b></td>
<td></td>
<td><b>'.$ETI['core03numcreditos'].'</b></td>
<td><b>'.$ETI['core03nivelcurso'].'</b></td>
<td><b>'.$ETI['core03notafinal'].'</b></td>
<td><b>'.$ETI['core03estado'].'</b></td>
</tr>';
	/*
<td><b>'.$ETI['core03nota75'].'</b></td>
<td><b>'.$ETI['core03nota25'].'</b></td>
<td><b>'.$ETI['core03notahomologa'].'</b></td>
<td><b>'.$ETI['core03formanota'].'</b></td>
<td><b>'.$ETI['msg_fechanota'].'</b></td>
	*/
	//<b>'.$ETI['core03obligatorio'].'</b>
	$iCols=7;
	$sVerde=' bgcolor="#006600"';
	$aEstado=array('Disponible', 'Pendiente de prerequisito', '', '', '', 'Homologado', '', 'Calificado', '', 'Excludio');
	$sRes='<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">';
	for ($k=0;$k<10;$k++){
		$bPasa=false;
		$iAvance=10;
		if ($k==0){
			if ($this->iNecesarios==0){$bPasa=true;}
			}
		if ($this->aNecesarios[$k]!=0){
			$bPasa=true;
			$iAvance=(int)($this->aAprobados[$k]/$this->aNecesarios[$k]);
			if ($iAvance>100){$iAvance=100;}
			}
		if ($bPasa){
			$sRes=$sRes.'<tr class="fondoazul">
<td colspan="'.$iCols.'" align="center"><b>'.cadena_notildes($this->aTipoCredito[$k]).'</b></td>
</tr>'.$sFilaEncabezado;
			//Mostrar el contenido del componente.
			for ($j=1;$j<=$this->iRegistros;$j++){
				$objCurso=$this->aRegistros[$j];
				if ($objCurso->iTipoCurso==$k){
					$sPref='';
					$sSuf='';
					if ($objCurso->iNumCreAprobados>0){
						$sPref='<b>';
						$sSuf='</b>';
						}
					$sObligatorio='';
					if ($objCurso->iObligatorio!=0){
						$sObligatorio='Obligatorio';
						}
					$sNotaFin='[Pendiente]';
					$sRes=$sRes.'<tr>
<td>'.$sPref.$objCurso->sCodCurso.$sSuf.'</td>
<td>'.$sPref.cadena_notildes($objCurso->sNomCurso).$sSuf.'</td>
<td>'.$sPref.$sObligatorio.$sSuf.'</td>
<td align="center">'.$sPref.$objCurso->iNumCreAprobados.' / '.$objCurso->iNumCreditos.$sSuf.'</td>
<td>'.$sPref.$objCurso->iNivel.$sSuf.'</td>
<td>'.$sPref.$sNotaFin.$sSuf.'</td>
<td>'.$sPref.$aEstado[$objCurso->iEstado].$sSuf.'</td>
</tr>';
					}
				}
			//Muestra una tabla de progreso del componente.
			if ($iAvance>0){
				$sLinea2='';
				if ($iAvance<100){
					$sLinea2='<td width="'.(100-$iAvance).'%" align="center">'.(100-$iAvance).' %</td>';
					}
				$sRes=$sRes.'<tr>
<td colspan="'.$iCols.'"><table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">
<tr>
<td width="'.$iAvance.'%"'.$sVerde.' align="center"><span style="color:#FFFFFF">'.$iAvance.' %</span></td>'.$sLinea2.'
</tr>
</table></td>
</tr>';
				}
			}
		}
	$sRes=$sRes.'</table>';
	return $sRes;
	}
function sVisual1(){
	$sHTML=''; 
	$sJS='';
	$sDebug='';
	$sError='';
	$iTipoError=0;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$this->sIdioma.'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2200=$APP->rutacomun.'lg/lg_2200_'.$this->sIdioma.'.php';
	if (!file_exists($mensajes_2200)){$mensajes_2200=$APP->rutacomun.'lg/lg_2200_es.php';}
	require $mensajes_todas;
	require $mensajes_2200;
	$sHTML='<div class="GrupoCampos">
'.$ETI['core01idprograma'].': <b>'.cadena_notildes($this->sNomPrograma).'</b>
<div class="salto1px"></div>
'.$ETI['core01idplandeestudios'].': <b>'.cadena_notildes($this->sCodPlanEstudio).'</b>
<div class="salto1px"></div>
</div>';
	return array($sHTML, $sJS, $sError, $iTipoError, $sDebug);
	}
function html(){
	$sHTML='';
	$sJS='';
	$sDebug='';
	$sError=$this->sError;
	$iTipoError=0;
	if ($sError==''){
		switch ($this->idVisual){
			case 1:
			break;
			default:
			list($sHTML, $sJS, $sError, $iTipoError, $sDebug)=$this->sVisual1();
			break;
			}
		list($sHTMLe, $sDebugE)=$this->html_encabezado();
		$sHTML=$sHTML.$sHTMLe;
		$sDebug=$sDebug.$sDebugE;
		$sHTML=$sHTML.$this->htmlCuerpo();
		}
	return array($sHTML, $sJS, $sError, $iTipoError, $sDebug);
	}
function __construct($idTercero, $idPrograma, $idVisual=0){
	$this->idTercero=$idTercero;
	$this->idPrograma=$idPrograma;
	$this->idVisual=$idVisual;
	}
}
?>