<?php
/*
--- © Cristhiam Dario Silva Chavez - UNAD - 2019 ---
--- cristhiam.silva@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.22.8 jueves, 28 de marzo de 2019
--- 2203 Plan de estudios
*/
class cls2203_registro{
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
	}
}
class cls2203_plan{
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
function Iniciar(){
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
function CargarDatos($core01id, $objDB, $bDebug=false){
	$sError='';
	$sDebug='';
	$this->Iniciar();
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
		$objCurso=new cls2203_registro($fila['core03idcurso'], $fila['unad40nombre'],$fila['core03itipocurso'],$fila['core03numcreditos']);
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
function html(){
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
function __construct($idPlan){
	$this->idPlan=$idPlan;
	}
}
function f2203_db_Guardar($valores, $objDB, $bDebug=false){
	$iCodModulo=2203;
	$bAudita[2]=false;
	$bAudita[3]=false;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2203='lg/lg_2203_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2203)){$mensajes_2203='lg/lg_2203_es.php';}
	require $mensajes_todas;
	require $mensajes_2203;
	$sError='';
	$sDebug='';
	$binserta=false;
	$iAccion=3;
	if(!is_array($valores)){$valores=json_decode(str_replace('\"','"',$valores),true);}
	$core03idestprograma=numeros_validar($valores[1]);
	$core03idcurso=numeros_validar($valores[2]);
	$core03id=numeros_validar($valores[3], true);
	$core03itipocurso=numeros_validar($valores[6]);
	$core03obligatorio=numeros_validar($valores[7]);
	$core03numcreditos=numeros_validar($valores[8]);
	$core03nivelcurso=numeros_validar($valores[9]);
	$core03peracaaprueba=numeros_validar($valores[10]);
	$core03nota75=numeros_validar($valores[11], true);
	$core03fechanota75=numeros_validar($valores[12]);
	$core03idusuarionota75=numeros_validar($valores[13]);
	$core03nota25=numeros_validar($valores[14], true);
	$core03fechanota25=numeros_validar($valores[15]);
	$core03idusuarionota25=numeros_validar($valores[16]);
	$core03notahomologa=numeros_validar($valores[17], true);
	$core03fechanotahomologa=numeros_validar($valores[18]);
	$core03idusuarionotahomo=numeros_validar($valores[19]);
	$core03detallehomologa=htmlspecialchars(trim($valores[20]));
	$core03fechainclusion=numeros_validar($valores[21]);
	$core03notafinal=numeros_validar($valores[22], true);
	$core03formanota=numeros_validar($valores[23]);
	$core03estado=numeros_validar($valores[24]);
	//if ($core03idprograma==''){$core03idprograma=0;}
	//if ($core03itipocurso==''){$core03itipocurso=0;}
	//if ($core03obligatorio==''){$core03obligatorio=0;}
	//if ($core03numcreditos==''){$core03numcreditos=0;}
	//if ($core03nivelcurso==''){$core03nivelcurso=0;}
	//if ($core03peracaaprueba==''){$core03peracaaprueba=0;}
	//if ($core03nota75==''){$core03nota75=0;}
	//if ($core03fechanota75==''){$core03fechanota75=0;}
	//if ($core03nota25==''){$core03nota25=0;}
	//if ($core03fechanota25==''){$core03fechanota25=0;}
	//if ($core03notahomologa==''){$core03notahomologa=0;}
	//if ($core03fechanotahomologa==''){$core03fechanotahomologa=0;}
	//if ($core03fechainclusion==''){$core03fechainclusion=0;}
	//if ($core03notafinal==''){$core03notafinal=0;}
	//if ($core03formanota==''){$core03formanota=0;}
	//if ($core03estado==''){$core03estado=0;}
	$sSepara=', ';
	if ($core03estado==''){$sError=$ERR['core03estado'].$sSepara.$sError;}
	if ($core03formanota==''){$sError=$ERR['core03formanota'].$sSepara.$sError;}
	if ($core03notafinal==''){$sError=$ERR['core03notafinal'].$sSepara.$sError;}
	if ($core03fechainclusion==''){$sError=$ERR['core03fechainclusion'].$sSepara.$sError;}
	if ($core03detallehomologa==''){$sError=$ERR['core03detallehomologa'].$sSepara.$sError;}
	if ($core03idusuarionotahomo==0){$sError=$ERR['core03idusuarionotahomo'].$sSepara.$sError;}
	if ($core03fechanotahomologa==''){$sError=$ERR['core03fechanotahomologa'].$sSepara.$sError;}
	if ($core03notahomologa==''){$sError=$ERR['core03notahomologa'].$sSepara.$sError;}
	if ($core03idusuarionota25==0){$sError=$ERR['core03idusuarionota25'].$sSepara.$sError;}
	if ($core03fechanota25==''){$sError=$ERR['core03fechanota25'].$sSepara.$sError;}
	if ($core03nota25==''){$sError=$ERR['core03nota25'].$sSepara.$sError;}
	if ($core03idusuarionota75==0){$sError=$ERR['core03idusuarionota75'].$sSepara.$sError;}
	if ($core03fechanota75==''){$sError=$ERR['core03fechanota75'].$sSepara.$sError;}
	if ($core03nota75==''){$sError=$ERR['core03nota75'].$sSepara.$sError;}
	if ($core03peracaaprueba==''){$sError=$ERR['core03peracaaprueba'].$sSepara.$sError;}
	if ($core03nivelcurso==''){$sError=$ERR['core03nivelcurso'].$sSepara.$sError;}
	if ($core03numcreditos==''){$sError=$ERR['core03numcreditos'].$sSepara.$sError;}
	if ($core03obligatorio==''){$sError=$ERR['core03obligatorio'].$sSepara.$sError;}
	if ($core03itipocurso==''){$sError=$ERR['core03itipocurso'].$sSepara.$sError;}
	if ($core03idtercero==0){$sError=$ERR['core03idtercero'].$sSepara.$sError;}
	//if ($core03id==''){$sError=$ERR['core03id'].$sSepara.$sError;}//CONSECUTIVO
	if ($core03idcurso==0){$sError=$ERR['core03idcurso'].$sSepara.$sError;}
	if ($core03idestprograma==''){$sError=$ERR['core03idestprograma'].$sSepara.$sError;}
	if ($sError==''){
		list($sError, $sInfo)=tercero_Bloqueado($core03idusuarionotahomo, $objDB);
		if ($sInfo!=''){$sError=$sError.'<br>'.sInfo;}
		}
	if ($sError==''){
		list($sError, $sInfo)=tercero_Bloqueado($core03idusuarionota25, $objDB);
		if ($sInfo!=''){$sError=$sError.'<br>'.sInfo;}
		}
	if ($sError==''){
		list($sError, $sInfo)=tercero_Bloqueado($core03idusuarionota75, $objDB);
		if ($sInfo!=''){$sError=$sError.'<br>'.sInfo;}
		}
	if ($sError==''){
		list($sError, $sInfo)=tercero_Bloqueado($core03idtercero, $objDB);
		if ($sInfo!=''){$sError=$sError.'<br>'.sInfo;}
		}
	if ($sError==''){
		$sSQL='SELECT  FROM  WHERE ="'.$core03idcurso.'"';
		$result=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($result)==0){$sError='No se encuentra el Curso {ref '.$core03idcurso.'}';}
		}
	if ($sError==''){
		if ((int)$core03id==0){
			if ($sError==''){
				$sSQL='SELECT core03idestprograma FROM core03plandeestudios WHERE core03idestprograma='.$core03idestprograma.' AND core03idcurso='.$core03idcurso.'';
				$result=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($result)!=0){
					$sError=$ERR['existe'];
					}else{
					if (!seg_revisa_permiso($iCodModulo, 2, $objDB)){$sError=$ERR['2'];}
					}
				}
			if ($sError==''){
				$core03id=tabla_consecutivo('core03plandeestudios', 'core03id', '', $objDB);
				if ($core03id==-1){$sError=$objDB->serror;}
				$binserta=true;
				$iAccion=2;
				}
			}else{
			if (!seg_revisa_permiso($iCodModulo, 3, $objDB)){$sError=$ERR['3'];}
			}
		}
	if ($sError==''){
		if ($binserta){
			$core03idtercero='';
			$core03idprograma=0;
			$core03peracaaprueba=0;
			$core03estado=0;
			//$sSQL='SELECT Campo FROM Tabla WHERE Id='.$sValorId;
			//$tabla=$objDB->ejecutasql($sSQL);
			//if ($objDB->nf($tabla)>0){
				//$fila=$objDB->sf($tabla);
				//$sCampo=$fila['sCampo'];
				//}
			$sError='INFORMACION PARA EL ADMINISTRADOR DEL SISTEMA: No ha definido valores para los campos ocultos.';
			}
		}
	if ($sError==''){
		//Si el campo core03detallehomologa permite html quite la linea htmlspecialchars para el campo y habilite la siguiente linea:
		//$core03detallehomologa=str_replace('"', '\"', $core03detallehomologa);
		$core03detallehomologa=str_replace('"', '\"', $core03detallehomologa);
		if ($binserta){
			$scampos='core03idestprograma, core03idcurso, core03id, core03idtercero, core03idprograma, 
core03itipocurso, core03obligatorio, core03numcreditos, core03nivelcurso, core03peracaaprueba, 
core03nota75, core03fechanota75, core03idusuarionota75, core03nota25, core03fechanota25, 
core03idusuarionota25, core03notahomologa, core03fechanotahomologa, core03idusuarionotahomo, core03detallehomologa, 
core03fechainclusion, core03notafinal, core03formanota, core03estado';
			$svalores=''.$core03idestprograma.', '.$core03idcurso.', '.$core03id.', "'.$core03idtercero.'", '.$core03idprograma.', 
'.$core03itipocurso.', '.$core03obligatorio.', '.$core03numcreditos.', '.$core03nivelcurso.', '.$core03peracaaprueba.', 
'.$core03nota75.', '.$core03fechanota75.', "'.$core03idusuarionota75.'", '.$core03nota25.', '.$core03fechanota25.', 
"'.$core03idusuarionota25.'", '.$core03notahomologa.', '.$core03fechanotahomologa.', "'.$core03idusuarionotahomo.'", "'.$core03detallehomologa.'", 
'.$core03fechainclusion.', '.$core03notafinal.', '.$core03formanota.', '.$core03estado.'';
			if ($APP->utf8==1){
				$sSQL='INSERT INTO core03plandeestudios ('.$scampos.') VALUES ('.utf8_encode($svalores).');';
				}else{
				$sSQL='INSERT INTO core03plandeestudios ('.$scampos.') VALUES ('.$svalores.');';
				}
			$result=$objDB->ejecutasql($sSQL);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' {Plan de estudios}.<!-- '.$sSQL.' -->';
				}else{
				if ($bAudita[2]){
					seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 2, $core03id, $sSQL, $objDB);
					}
				}
			}else{
			$scampo2203[1]='core03itipocurso';
			$scampo2203[2]='core03obligatorio';
			$scampo2203[3]='core03numcreditos';
			$scampo2203[4]='core03nivelcurso';
			$scampo2203[5]='core03nota75';
			$scampo2203[6]='core03fechanota75';
			$scampo2203[7]='core03idusuarionota75';
			$scampo2203[8]='core03nota25';
			$scampo2203[9]='core03fechanota25';
			$scampo2203[10]='core03idusuarionota25';
			$scampo2203[11]='core03notahomologa';
			$scampo2203[12]='core03fechanotahomologa';
			$scampo2203[13]='core03idusuarionotahomo';
			$scampo2203[14]='core03detallehomologa';
			$scampo2203[15]='core03fechainclusion';
			$scampo2203[16]='core03notafinal';
			$scampo2203[17]='core03formanota';
			$svr2203[1]=$core03itipocurso;
			$svr2203[2]=$core03obligatorio;
			$svr2203[3]=$core03numcreditos;
			$svr2203[4]=$core03nivelcurso;
			$svr2203[5]=$core03nota75;
			$svr2203[6]=$core03fechanota75;
			$svr2203[7]=$core03idusuarionota75;
			$svr2203[8]=$core03nota25;
			$svr2203[9]=$core03fechanota25;
			$svr2203[10]=$core03idusuarionota25;
			$svr2203[11]=$core03notahomologa;
			$svr2203[12]=$core03fechanotahomologa;
			$svr2203[13]=$core03idusuarionotahomo;
			$svr2203[14]=$core03detallehomologa;
			$svr2203[15]=$core03fechainclusion;
			$svr2203[16]=$core03notafinal;
			$svr2203[17]=$core03formanota;
			$inumcampos=17;
			$sWhere='core03id='.$core03id.'';
			//$sWhere='core03idestprograma='.$core03idestprograma.' AND core03idcurso='.$core03idcurso.'';
			$sSQL='SELECT * FROM core03plandeestudios WHERE '.$sWhere;
			$sdatos='';
			$bpasa=false;
			$result=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($result)>0){
				$filaorigen=$objDB->sf($result);
				for ($k=1;$k<=$inumcampos;$k++){
					if ($filaorigen[$scampo2203[$k]]!=$svr2203[$k]){
						if ($sdatos!=''){$sdatos=$sdatos.', ';}
						$sdatos=$sdatos.$scampo2203[$k].'="'.$svr2203[$k].'"';
						$bpasa=true;
						}
					}
				}
			if ($bpasa){
				if ($APP->utf8==1){
					$sSQL='UPDATE core03plandeestudios SET '.utf8_encode($sdatos).' WHERE '.$sWhere.';';
					}else{
					$sSQL='UPDATE core03plandeestudios SET '.$sdatos.' WHERE '.$sWhere.';';
					}
				$result=$objDB->ejecutasql($sSQL);
				if ($result==false){
					$sError=$ERR['falla_guardar'].' {Plan de estudios}. <!-- '.$sSQL.' -->';
					}else{
					if ($bAudita[3]){
						seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 3, $core03id, $sSQL, $objDB);
						}
					}
				}
			}
		}
	return array($sError, $iAccion, $core03id, $sDebug);
	}
function f2203_db_Eliminar($aParametros, $objDB, $bDebug=false){
	$iCodModulo=2203;
	$bAudita[4]=false;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2203='lg/lg_2203_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2203)){$mensajes_2203='lg/lg_2203_es.php';}
	require $mensajes_todas;
	require $mensajes_2203;
	$sError='';
	$sDebug='';
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$core03idestprograma=numeros_validar($aParametros[1]);
	$core03idcurso=numeros_validar($aParametros[2]);
	$core03id=numeros_validar($aParametros[3]);
	if ($sError==''){
		if (!seg_revisa_permiso($iCodModulo, 4, $objDB)){
			$sError=$ERR['4'];
			}
		}
	if ($sError==''){
		$sSQL='SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=2203';
		$tablaor=$objDB->ejecutasql($sSQL);
		while ($filaor=$objDB->sf($tablaor)){
			$sSQL='SELECT '.$filaor['campoid'].' FROM '.$filaor['tabla'].' WHERE '.$filaor['camporev'].'='.$core03id.' LIMIT 0, 1';
			$tabla=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla)>0){
				$sError=$filaor['mensaje'];
				if ($filaor['etiqueta']!=''){
					if (isset($ERR[$filaor['etiqueta']])!=0){$sError=$ERR[$filaor['etiqueta']];}
					}
				break;
				}
			}
		}
	if ($sError==''){
		//acciones previas
		$sWhere='core03id='.$core03id.'';
		//$sWhere='core03idestprograma='.$core03idestprograma.' AND core03idcurso='.$core03idcurso.'';
		$sSQL='DELETE FROM core03plandeestudios WHERE '.$sWhere.';';
		$result=$objDB->ejecutasql($sSQL);
		if ($result==false){
			$sError=$ERR['falla_eliminar'].' {2203 Plan de estudios}.<!-- '.$sSQL.' -->';
			}else{
			if ($bAudita[4]){
				seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $core03id, $sSQL, $objDB);
				}
			}
		}
	return array($sError, $sDebug);
	}
function f2203_TablaDetalleV2($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2203='lg/lg_2203_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2203)){$mensajes_2203='lg/lg_2203_es.php';}
	require $mensajes_todas;
	require $mensajes_2203;
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	if (isset($aParametros[0])==0){$aParametros[0]=-1;}
	if (isset($aParametros[100])==0){$aParametros[100]=0;}
	if (isset($aParametros[101])==0){$aParametros[101]=1;}
	if (isset($aParametros[102])==0){$aParametros[102]=20;}
	//if (isset($aParametros[103])==0){$aParametros[103]='';}
	$idContenedor=numeros_validar($aParametros[100]);
	if ($idContenedor==''){$idContenedor=0;}
	$aParametros[0]=numeros_validar($aParametros[0]);
	if ($aParametros[0]==''){$aParametros[0]=-1;}
	$sDebug='';
	$core01id=$aParametros[0];
	$pagina=$aParametros[101];
	$lineastabla=$aParametros[102];
	$babierta=false;
	//$sSQL='SELECT Campo FROM core01estprograma WHERE core01id='.$core01id;
	//$tabla=$objDB->ejecutasql($sSQL);
	//if ($objDB->nf($tabla)>0){
		//$fila=$objDB->sf($tabla);
		//if ($fila['Campo']!='S'){$babierta=true;}
		//}
	$sSQLadd='';
	$sSQLadd1='';
	$sLeyenda='';
	if (false){
		$sLeyenda='<div class="salto1px"></div>
<div class="GrupoCamposAyuda">
<b>Importante:</b> Mensaje al usuario
<div class="salto1px"></div>
</div>';
		}
	$objPlan=new cls2203_plan($core01id);
	$objPlan->idContenedor=$idContenedor;
	list($sError, $sDebugP)=$objPlan->CargarDatos($core01id, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugP;
	$res=$objPlan->html();
	return array(utf8_encode($res), $sDebug);
	//if ((int)$aParametros[103]!=-1){$sSQLadd=$sSQLadd.' AND TB.campo='.$aParametros[103];}
	//if ($aParametros[103]!=''){$sSQLadd=$sSQLadd.' AND TB.campo2 LIKE "%'.$aParametros[103].'%"';}
	/*
	if ($aParametros[104]!=''){
		$sBase=trim(strtoupper($aParametros[104]));
		$aNoms=explode(' ', $sBase);
		for ($k=1;$k<=count($aNoms);$k++){
			$sCadena=$aNoms[$k-1];
			if ($sCadena!=''){
				$sSQLadd=$sSQLadd.' AND T6.unad11razonsocial LIKE "%'.$sCadena.'%"';
				//$sSQLadd1=$sSQLadd1.'T1.unad11razonsocial LIKE "%'.$sCadena.'%" AND ';
				}
			}
		}
	*/
	$sTitulos='Estprograma, Curso, Id, Tercero, Programa, Itipocurso, Obligatorio, Numcreditos, Nivelcurso, Peracaaprueba, Nota75, Fechanota75, Usuarionota75, Nota25, Fechanota25, Usuarionota25, Notahomologa, Fechanotahomologa, Usuarionotahomo, Detallehomologa, Fechainclusion, Notafinal, Formanota, Estado';
	$sSQL='SELECT TB.core03idcurso, TB.core03id, T4.unad40consec, T4.unad40nombre, TB.core03obligatorio, TB.core03numcreditos, TB.core03nivelcurso, TB.core03nota75, TB.core03fechanota75, TB.core03nota25, TB.core03fechanota25, TB.core03notahomologa, TB.core03fechanotahomologa, TB.core03detallehomologa, TB.core03fechainclusion, TB.core03notafinal, TB.core03formanota, TB.core03estado, TB.core03idtercero, TB.core03itipocurso, TB.core03peracaaprueba, TB.core03idusuarionota75, TB.core03idusuarionota25, TB.core03idusuarionotahomo 
FROM core03plandeestudios_'.$idContenedor.' AS TB, unad40curso AS T4 
WHERE '.$sSQLadd1.' TB.core03idestprograma='.$core01id.' AND TB.core03idcurso=T4.unad40id '.$sSQLadd.'
ORDER BY TB.core03itipocurso, TB.core03nivelcurso, TB.core03idcurso';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_2203" name="consulta_2203" type="hidden" value="'.$sSQLlista.'"/>
<input id="titulos_2203" name="titulos_2203" type="hidden" value="'.$sTitulos.'"/>';
	if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 2203: '.$sSQL.'<br>';}
	$tabladetalle=$objDB->ejecutasql($sSQL);
	if ($tabladetalle==false){
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		//$sLeyenda=$sSQL;
		}else{
		$registros=$objDB->nf($tabladetalle);
		}
	while($filadet=$objDB->sf($tabladetalle)){
		}
	$res=$sErrConsulta.$sLeyenda.'<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">
<tr class="fondoazul">
<td colspan="2"><b>'.$ETI['core03idcurso'].'</b></td>
<td><b>'.$ETI['core03obligatorio'].'</b></td>
<td><b>'.$ETI['core03numcreditos'].'</b></td>
<td><b>'.$ETI['core03nivelcurso'].'</b></td>
<td><b>'.$ETI['core03nota75'].'</b></td>
<td><b>'.$ETI['core03fechanota75'].'</b></td>
<td><b>'.$ETI['core03nota25'].'</b></td>
<td><b>'.$ETI['core03fechanota25'].'</b></td>
<td><b>'.$ETI['core03notahomologa'].'</b></td>
<td><b>'.$ETI['core03fechainclusion'].'</b></td>
<td><b>'.$ETI['core03notafinal'].'</b></td>
<td><b>'.$ETI['core03formanota'].'</b></td>
<td><b>'.$ETI['core03estado'].'</b></td>
<td align="right">
'.html_paginador('paginaf2203', $registros, $lineastabla, $pagina, 'paginarf2203()').'
'.html_lpp('lppf2203', $lineastabla, 'paginarf2203()').'
</td>
</tr>';
	$tlinea=1;
	while($filadet=$objDB->sf($tabladetalle)){
		$sPrefijo='';
		$sSufijo='';
		$sClass='';
		$sLink='';
		if (false){
			$sPrefijo='<b>';
			$sSufijo='</b>';
			}
		if(($tlinea%2)==0){$sClass=' class="resaltetabla"';}
		$tlinea++;
		$et_core03idcurso=$sPrefijo.cadena_notildes($filadet['unad40nombre']).$sSufijo;
		$et_core03idtercero=$sPrefijo.$filadet['core03idtercero'].$sSufijo;
		$et_core03obligatorio=$sPrefijo.$filadet['core03obligatorio'].$sSufijo;
		$et_core03numcreditos=$sPrefijo.$filadet['core03numcreditos'].$sSufijo;
		$et_core03nivelcurso=$sPrefijo.$filadet['core03nivelcurso'].$sSufijo;
		$et_core03peracaaprueba=$sPrefijo.cadena_notildes($filadet['exte02nombre']).$sSufijo;
		$et_core03nota75=$sPrefijo.formato_moneda($filadet['core03nota75']).$sSufijo;
		$et_core03fechanota75=$sPrefijo.$filadet['core03fechanota75'].$sSufijo;
		$et_core03idusuarionota75=$sPrefijo.$filadet['core03idusuarionota75'].$sSufijo;
		$et_core03nota25=$sPrefijo.formato_moneda($filadet['core03nota25']).$sSufijo;
		$et_core03fechanota25=$sPrefijo.$filadet['core03fechanota25'].$sSufijo;
		$et_core03idusuarionota25=$sPrefijo.$filadet['core03idusuarionota25'].$sSufijo;
		$et_core03notahomologa=$sPrefijo.formato_moneda($filadet['core03notahomologa']).$sSufijo;
		$et_core03fechanotahomologa=$sPrefijo.$filadet['core03fechanotahomologa'].$sSufijo;
		$et_core03idusuarionotahomo=$sPrefijo.$filadet['core03idusuarionotahomo'].$sSufijo;
		$et_core03detallehomologa=$sPrefijo.cadena_notildes($filadet['core03detallehomologa']).$sSufijo;
		$et_core03fechainclusion=$sPrefijo.$filadet['core03fechainclusion'].$sSufijo;
		$et_core03notafinal=$sPrefijo.formato_moneda($filadet['core03notafinal']).$sSufijo;
		$et_core03formanota=$sPrefijo.$filadet['core03formanota'].$sSufijo;
		$et_core03estado=$sPrefijo.$filadet['core03estado'].$sSufijo;
		if ($babierta){
			$sLink='<a href="javascript:cargaridf2203('.$filadet['core03id'].')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$res=$res.'<tr'.$sClass.'>
<td>'.$sPrefijo.$filadet['core03idcurso'].$sSufijo.'</td>
<td>'.$et_core03idcurso.'</td>
<td>'.$et_core03obligatorio.'</td>
<td>'.$et_core03numcreditos.'</td>
<td>'.$et_core03nivelcurso.'</td>
<td>'.$et_core03peracaaprueba.'</td>
<td align="right">'.$et_core03nota75.'</td>
<td align="right">'.$et_core03nota25.'</td>
<td align="right">'.$et_core03notahomologa.'</td>
<td>'.$et_core03fechainclusion.'</td>
<td align="right">'.$et_core03notafinal.'</td>
<td>'.$et_core03formanota.'</td>
<td>'.$et_core03estado.'</td>
<td>'.$sLink.'</td>
</tr>';
		}
	$res=$res.'</table>';
	$objDB->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
	}
function f2203_Clonar($core03idestprograma, $core03idestprogramaPadre, $objDB){
	$sError='';
	$core03id=tabla_consecutivo('core03plandeestudios', 'core03id', '', $objDB);
	if ($core03id==-1){$sError=$objDB->serror;}
	if ($sError==''){
		$sCampos2203='core03idestprograma, core03idcurso, core03id, core03idtercero, core03idprograma, core03itipocurso, core03obligatorio, core03numcreditos, core03nivelcurso, core03peracaaprueba, core03nota75, core03fechanota75, core03idusuarionota75, core03nota25, core03fechanota25, core03idusuarionota25, core03notahomologa, core03fechanotahomologa, core03idusuarionotahomo, core03detallehomologa, core03fechainclusion, core03notafinal, core03formanota, core03estado';
		$sValores2203='';
		$sSQL='SELECT * FROM core03plandeestudios WHERE core03idestprograma='.$core03idestprogramaPadre.'';
		$tabla=$objDB->ejecutasql($sSQL);
		while($fila=$objDB->sf($tabla)){
			if ($sValores2203!=''){$sValores2203=$sValores2203.', ';}
			$sValores2203=$sValores2203.'('.$core03idestprograma.', '.$fila['core03idcurso'].', '.$core03id.', '.$fila['core03idtercero'].', '.$fila['core03idprograma'].', '.$fila['core03itipocurso'].', '.$fila['core03obligatorio'].', '.$fila['core03numcreditos'].', '.$fila['core03nivelcurso'].', '.$fila['core03peracaaprueba'].', "'.$fila['core03nota75'].'", '.$fila['core03fechanota75'].', '.$fila['core03idusuarionota75'].', "'.$fila['core03nota25'].'", '.$fila['core03fechanota25'].', '.$fila['core03idusuarionota25'].', "'.$fila['core03notahomologa'].'", '.$fila['core03fechanotahomologa'].', '.$fila['core03idusuarionotahomo'].', "'.$fila['core03detallehomologa'].'", '.$fila['core03fechainclusion'].', "'.$fila['core03notafinal'].'", '.$fila['core03formanota'].', '.$fila['core03estado'].')';
			$core03id++;
			}
		if ($sValores2203!=''){
			$sSQL='INSERT INTO core03plandeestudios('.$sCampos2203.') VALUES '.$sValores2203.'';
			$result=$objDB->ejecutasql($sSQL);
			}
		}
	return $sError;
	}
// -- 2203 Plan de estudios XAJAX 
function f2203_Busqueda_db_core03idcurso($sCodigo, $objDB, $bDebug=false){
	$sRespuesta='';
	$sDebug='';
	$id=0;
	$sCodigo=htmlspecialchars(trim($sCodigo));
	if ($sCodigo!=''){
		$sSQL='SELECT , ,  FROM  WHERE ="'.$sCodigo.'"';
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta Busqueda: '.$sSQL.'<br>';}
		$res=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($res)!=0){
			$fila=$objDB->sf($res);
			$sRespuesta='<b>'.$fila[''].' '.cadena_notildes($fila['']).'</b>';
			$id=$fila[''];
			}
		if ($sRespuesta==''){
			$sRespuesta='<span class="rojo">{'.$sCodigo.' No encontrado}</span>';
			}
		}
	return array($id, utf8_encode($sRespuesta), $sDebug);
	}
function f2203_Busqueda_core03idcurso($aParametros){
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$_SESSION['u_ultimominuto']=iminutoavance();
	$sRespuesta='';
	$sDebug='';
	$scodigo=$aParametros[0];
	$bxajax=true;
	$bDebug=false;
	if (isset($aParametros[3])!=0){if ($aParametros[3]==1){$bxajax=false;}}
	if (isset($aParametros[9])!=0){if ($aParametros[9]==1){$bDebug=true;}}
	$id=0;
	if ($scodigo!=''){
		require './app.php';
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		list($id, $sRespuesta, $sDebugCon)=f2203_Busqueda_db_core03idcurso($scodigo, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugCon;
		$objDB->CerrarConexion();
		}
	$objid=$aParametros[1];
	$sdiv=$aParametros[2];
	$objResponse=new xajaxResponse();
	$objResponse->assign($sdiv, 'innerHTML', $sRespuesta);
	$objResponse->assign($objid, 'value', $id);
	if ((int)$id>0){
		$objResponse->call('revisaf2203');
		}
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f2203_Guardar($valores, $aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$sError='';
	$bDebug=false;
	$sDebug='';
	$bHayDb=false;
	$opts=$aParametros;
	if(!is_array($opts)){$opts=json_decode(str_replace('\"','"',$opts),true);}
	if (isset($opts[99])!=0){if ($opts[99]==1){$bDebug=true;}}
	//if(!is_array($valores)){$datos=json_decode(str_replace('\"','"',$valores),true);}
	//if (isset($datos[0])==0){$datos[0]='';}
	//if ($datos[0]==''){$sError=$ERR[''];}
	if ($sError==''){
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		$bHayDb=true;
		list($sError, $iAccion, $core03id, $sDebugGuardar)=f2203_db_Guardar($valores, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugGuardar;
		}
	$objResponse=new xajaxResponse();
	if ($sError==''){
		list($sdetalle, $sDebugTabla)=f2203_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugTabla;
		$objResponse->assign('div_f2203detalle', 'innerHTML', $sdetalle);
		//if ($iAccion==2){
			//$objResponse->call('cargaridf2203('.$core03id.')');
			//}else{
			$objResponse->call('limpiaf2203');
			//}
		$objResponse->call("MensajeAlarmaV2('".$ETI['msg_itemguardado']."', 1)");
		}else{
		$objResponse->call("MensajeAlarmaV2('".$sError."', 0)");
		}
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	if ($bHayDb){
		$objDB->CerrarConexion();
		}
	return $objResponse;
	}
function f2203_Traer($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$sError='';
	$bHayDb=false;
	$besta=false;
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$paso=$aParametros[0];
	if ($paso==1){
		$core03idestprograma=numeros_validar($aParametros[1]);
		$core03idcurso=numeros_validar($aParametros[2]);
		if (($core03idestprograma!='')&&($core03idcurso!='')){$besta=true;}
		}else{
		$core03id=$aParametros[103];
		if ((int)$core03id!=0){$besta=true;}
		}
	if ($besta){
		$besta=false;
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		$bHayDb=true;
		$sSQLcondi='';
		if ($paso==1){
			$sSQLcondi=$sSQLcondi.'core03idestprograma='.$core03idestprograma.' AND core03idcurso='.$core03idcurso.'';
			}else{
			$sSQLcondi=$sSQLcondi.'core03id='.$core03id.'';
			}
		$sSQL='SELECT * FROM core03plandeestudios WHERE '.$sSQLcondi;
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$fila=$objDB->sf($tabla);
			$besta=true;
			}
		}
	$objResponse=new xajaxResponse();
	if ($besta){
		if (isset($APP->piel)==0){$APP->piel=1;}
		$iPiel=$APP->piel;
		$core03idcurso_nombre='';
		$core03idcurso_cod='';
		if ((int)$fila['core03idcurso']!=0){
			$sSQL='SELECT ,  FROM  WHERE ='.$fila['core03idcurso'].'';
			$res=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($res)!=0){
				$filaDetalle=$objDB->sf($res);
				$core03idcurso_nombre='<b>'.cadena_notildes($filaDetalle['']).'</b>';
				$core03idcurso_cod=$filaDetalle[''];
				}
			if ($core03idcurso_nombre==''){
				$core03idcurso_nombre='<font class="rojo">{Ref : '.$fila['core03idcurso'].' No encontrado}</font>';
				}
			}
		$core03idtercero_id=(int)$fila['core03idtercero'];
		$core03idtercero_td=$APP->tipo_doc;
		$core03idtercero_doc='';
		$core03idtercero_nombre='';
		if ($core03idtercero_id!=0){
			list($core03idtercero_nombre, $core03idtercero_id, $core03idtercero_td, $core03idtercero_doc)=html_tercero($core03idtercero_td, $core03idtercero_doc, $core03idtercero_id, 0, $objDB);
			}
		$core03idusuarionota75_id=(int)$fila['core03idusuarionota75'];
		$core03idusuarionota75_td=$APP->tipo_doc;
		$core03idusuarionota75_doc='';
		$core03idusuarionota75_nombre='';
		if ($core03idusuarionota75_id!=0){
			list($core03idusuarionota75_nombre, $core03idusuarionota75_id, $core03idusuarionota75_td, $core03idusuarionota75_doc)=html_tercero($core03idusuarionota75_td, $core03idusuarionota75_doc, $core03idusuarionota75_id, 0, $objDB);
			}
		$core03idusuarionota25_id=(int)$fila['core03idusuarionota25'];
		$core03idusuarionota25_td=$APP->tipo_doc;
		$core03idusuarionota25_doc='';
		$core03idusuarionota25_nombre='';
		if ($core03idusuarionota25_id!=0){
			list($core03idusuarionota25_nombre, $core03idusuarionota25_id, $core03idusuarionota25_td, $core03idusuarionota25_doc)=html_tercero($core03idusuarionota25_td, $core03idusuarionota25_doc, $core03idusuarionota25_id, 0, $objDB);
			}
		$core03idusuarionotahomo_id=(int)$fila['core03idusuarionotahomo'];
		$core03idusuarionotahomo_td=$APP->tipo_doc;
		$core03idusuarionotahomo_doc='';
		$core03idusuarionotahomo_nombre='';
		if ($core03idusuarionotahomo_id!=0){
			list($core03idusuarionotahomo_nombre, $core03idusuarionotahomo_id, $core03idusuarionotahomo_td, $core03idusuarionotahomo_doc)=html_tercero($core03idusuarionotahomo_td, $core03idusuarionotahomo_doc, $core03idusuarionotahomo_id, 0, $objDB);
			}
		$html_core03idcurso_cod=html_oculto('core03idcurso_cod', $core03idcurso_cod);
		$objResponse->assign('core03idcurso', 'value', $fila['core03idcurso']);
		$objResponse->assign('div_core03idcurso_cod', 'innerHTML', $html_core03idcurso_cod);
		$objResponse->call("verboton('bcore03idcurso','none')");
		$objResponse->assign('div_core03idcurso', 'innerHTML', $core03idcurso_nombre);
		$core03id_nombre='';
		$html_core03id=html_oculto('core03id', $fila['core03id'], $core03id_nombre);
		$objResponse->assign('div_core03id', 'innerHTML', $html_core03id);
		$objResponse->assign('core03idtercero', 'value', $fila['core03idtercero']);
		$objResponse->assign('core03idtercero_td', 'value', $core03idtercero_td);
		$objResponse->assign('core03idtercero_doc', 'value', $core03idtercero_doc);
		$objResponse->assign('div_core03idtercero', 'innerHTML', $core03idtercero_nombre);
		$objResponse->assign('core03idprograma', 'value', $fila['core03idprograma']);
		$objResponse->assign('core03itipocurso', 'value', $fila['core03itipocurso']);
		$objResponse->assign('core03obligatorio', 'value', $fila['core03obligatorio']);
		$objResponse->assign('core03numcreditos', 'value', $fila['core03numcreditos']);
		$objResponse->assign('core03nivelcurso', 'value', $fila['core03nivelcurso']);
		list($core03peracaaprueba_nombre, $serror_det)=tabla_campoxid('exte02per_aca','exte02nombre','exte02id', $fila['core03peracaaprueba'],'{'.$ETI['msg_sindato'].'}', $objDB);
		$html_core03peracaaprueba=html_oculto('core03peracaaprueba', $fila['core03peracaaprueba'], $core03peracaaprueba_nombre);
		$objResponse->assign('div_core03peracaaprueba', 'innerHTML', $html_core03peracaaprueba);
		$objResponse->assign('core03nota75', 'value', $fila['core03nota75']);
		$objResponse->assign('core03fechanota75', 'value', $fila['core03fechanota75']);
		$objResponse->assign('core03idusuarionota75', 'value', $fila['core03idusuarionota75']);
		$objResponse->assign('core03idusuarionota75_td', 'value', $core03idusuarionota75_td);
		$objResponse->assign('core03idusuarionota75_doc', 'value', $core03idusuarionota75_doc);
		$objResponse->assign('div_core03idusuarionota75', 'innerHTML', $core03idusuarionota75_nombre);
		$objResponse->assign('core03nota25', 'value', $fila['core03nota25']);
		$objResponse->assign('core03fechanota25', 'value', $fila['core03fechanota25']);
		$objResponse->assign('core03idusuarionota25', 'value', $fila['core03idusuarionota25']);
		$objResponse->assign('core03idusuarionota25_td', 'value', $core03idusuarionota25_td);
		$objResponse->assign('core03idusuarionota25_doc', 'value', $core03idusuarionota25_doc);
		$objResponse->assign('div_core03idusuarionota25', 'innerHTML', $core03idusuarionota25_nombre);
		$objResponse->assign('core03notahomologa', 'value', $fila['core03notahomologa']);
		$objResponse->assign('core03fechanotahomologa', 'value', $fila['core03fechanotahomologa']);
		$objResponse->assign('core03idusuarionotahomo', 'value', $fila['core03idusuarionotahomo']);
		$objResponse->assign('core03idusuarionotahomo_td', 'value', $core03idusuarionotahomo_td);
		$objResponse->assign('core03idusuarionotahomo_doc', 'value', $core03idusuarionotahomo_doc);
		$objResponse->assign('div_core03idusuarionotahomo', 'innerHTML', $core03idusuarionotahomo_nombre);
		$objResponse->assign('core03detallehomologa', 'value', $fila['core03detallehomologa']);
		$objResponse->assign('core03fechainclusion', 'value', $fila['core03fechainclusion']);
		$objResponse->assign('core03notafinal', 'value', $fila['core03notafinal']);
		$objResponse->assign('core03formanota', 'value', $fila['core03formanota']);
		list($core03estado_nombre, $serror_det)=tabla_campoxid('','','', $fila['core03estado'],'{'.$ETI['msg_sindato'].'}', $objDB);
		$html_core03estado=html_oculto('core03estado', $fila['core03estado'], $core03estado_nombre);
		$objResponse->assign('div_core03estado', 'innerHTML', $html_core03estado);
		$objResponse->call("MensajeAlarmaV2('', 0)");
		$objResponse->call("verboton('belimina2203','block')");
		}else{
		if ($paso==1){
			$objResponse->assign('core03idcurso', 'value', $core03idcurso);
			}else{
			$objResponse->call('MensajeAlarmaV2("No se encontro el registro de referencia:'.$core03id.'", 0)');
			}
		}
	if ($bHayDb){
		$objDB->CerrarConexion();
		}
	return $objResponse;
	}
function f2203_Eliminar($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$sError='';
	$iTipoError=0;
	$bDebug=false;
	$sDebug='';
	$opts=$aParametros;
	if(!is_array($opts)){$opts=json_decode(str_replace('\"','"',$opts),true);}
	if (isset($opts[99])!=0){if ($opts[99]==1){$bDebug=true;}}
	//if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	list($sError, $sDebugElimina)=f2203_db_Eliminar($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugElimina;
	$objResponse=new xajaxResponse();
	if ($sError==''){
		list($sDetalle, $sDebugTabla)=f2203_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugTabla;
		$objResponse->assign('div_f2203detalle', 'innerHTML', $sDetalle);
		$objResponse->call('limpiaf2203');
		$sError=$ETI['msg_itemeliminado'];
		$iTipoError=1;
		}
	$objResponse->call("MensajeAlarmaV2('".$sError."', ".$iTipoError.")");
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	$objDB->CerrarConexion();
	return $objResponse;
	}
function f2203_HtmlTabla($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	$sError='';
	$bDebug=false;
	$sDebug='';
	$opts=$aParametros;
	if(!is_array($opts)){$opts=json_decode(str_replace('\"','"',$opts),true);}
	if (isset($opts[99])!=0){if ($opts[99]==1){$bDebug=true;}}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	list($sDetalle, $sDebugTabla)=f2203_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f2203detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f2203_PintarLlaves($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	//if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	if (isset($APP->piel)==0){$APP->piel=1;}
	$iPiel=$APP->piel;
	$objCombos=new clsHtmlCombos('n');
	$html_core03idcurso_cod='<input id="core03idcurso_cod" name="core03idcurso_cod" type="text" value="" onchange="cod_core03idcurso()" class="veinte"/>';
	$html_core03id='<input id="core03id" name="core03id" type="hidden" value=""/>';
	$html_core03peracaaprueba=f2203_HTMLComboV2_core03peracaaprueba($objDB, $objCombos, 0);
	$html_core03estado=f2203_HTMLComboV2_core03estado($objDB, $objCombos, 0);
	$objResponse=new xajaxResponse();
	$objResponse->assign('core03idcurso','value', '0');
	$objResponse->assign('div_core03idcurso_cod','innerHTML', $html_core03idcurso_cod);
	$objResponse->assign('div_core03idcurso','innerHTML', '');
	$objResponse->call("verboton('bcore03idcurso','block')");
	$objResponse->assign('div_core03id','innerHTML', $html_core03id);
	$objResponse->assign('div_core03peracaaprueba','innerHTML', $html_core03peracaaprueba);
	$objResponse->assign('div_core03estado','innerHTML', $html_core03estado);
	return $objResponse;
	}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------
?>