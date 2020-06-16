<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2018 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión
--- 2301consejero.php
---
--- Cambios 16 de junio de 2020
--- 1. Se adiciona la versión 2 de las preguntas de discapacidades y talento
--- Omar Augusto Bautista Mora - UNAD - 2020
--- omar.bautista@unad.edu.co
*/
function f2301_db_GuardarDiscapacidad($DATA, $objDB, $bDebug=false){
	$iCodModulo=2301;
	$bAudita[3]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2301=$APP->rutacomun.'lg/lg_2301_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2301)){$mensajes_2301=$APP->rutacomun.'lg/lg_2301_es.php';}
	require $mensajes_todas;
	require $mensajes_2301;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	$bCerrando=false;
	$sErrorCerrando='';
	// -- Se inicia validando todas las posibles entradas de usuario.
	if($DATA['cara01discversion']==0){
		$DATA['cara01discsensorial'] = htmlspecialchars(trim($DATA['cara01discsensorial']));
		$DATA['cara01discfisica'] = htmlspecialchars(trim($DATA['cara01discfisica']));
		$DATA['cara01disccognitiva'] = htmlspecialchars(trim($DATA['cara01disccognitiva']));
		}
	if($DATA['cara01discversion']==1){
		$DATA['cara01discv2sensorial'] = htmlspecialchars(trim($DATA['cara01discv2sensorial']));
		$DATA['cara02discv2intelectura'] = htmlspecialchars(trim($DATA['cara02discv2intelectura']));
		$DATA['cara02discv2fisica'] = htmlspecialchars(trim($DATA['cara02discv2fisica']));
		$DATA['cara02discv2psico'] = htmlspecialchars(trim($DATA['cara02discv2psico']));
		$DATA['cara02discv2sistemica'] = htmlspecialchars(trim($DATA['cara02discv2sistemica']));
		$DATA['cara02discv2multiple'] = htmlspecialchars(trim($DATA['cara02discv2multiple']));
		$DATA['cara02talentoexcepcional'] = htmlspecialchars(trim($DATA['cara02talentoexcepcional']));
		}
	// -- Se inicializan las variables que puedan pasar vacias {Especialmente n�meros}.
	// -- Seccion para validar los posibles causales de error.
	//Primero hacer un caso de revision de los encabezados.
	$aFicha=array('', 'cara01fichaper', 'cara01fichafam', 'cara01fichaaca', 'cara01fichalab', 'cara01fichabien', 'cara01fichapsico', 'cara01fichadigital', 'cara01fichalectura', 'cara01ficharazona', 'cara01fichaingles', 'cara01fichabiolog', 'cara01fichafisica', 'cara01fichaquimica');
	//Fin de revisar los casos de revision de encabezados
	$sSepara=', ';
	if($DATA['cara01discversion']==0){
		if ($DATA['cara01disccognitiva']==''){$sError=$ERR['cara01disccognitiva'].$sSepara.$sError;}
		if ($DATA['cara01discfisica']==''){$sError=$ERR['cara01discfisica'].$sSepara.$sError;}
		if ($DATA['cara01discsensorial']==''){$sError=$ERR['cara01discsensorial'].$sSepara.$sError;}
		}
	if($DATA['cara01discversion']==1){
		if ($DATA['cara01discv2sensorial']==''){$sError=$ERR['cara01discv2sensorial'].$sSepara.$sError;}
		if ($DATA['cara02discv2intelectura']==''){$sError=$ERR['cara02discv2intelectura'].$sSepara.$sError;}
		if ($DATA['cara02discv2fisica']==''){$sError=$ERR['cara02discv2fisica'].$sSepara.$sError;}
		if ($DATA['cara02discv2psico']==''){$sError=$ERR['cara02discv2psico'].$sSepara.$sError;}
		if ($DATA['cara02discv2sistemica']==''){$sError=$ERR['cara02discv2sistemica'].$sSepara.$sError;}
		if ($DATA['cara02discv2multiple']==''){$sError=$ERR['cara02discv2multiple'].$sSepara.$sError;}
		if ($DATA['cara02talentoexcepcional']==''){$sError=$ERR['cara02talentoexcepcional'].$sSepara.$sError;}
		}
	//Valiaciones de campos obligatorios en todo guardar.
	if ($DATA['cara01idtercero']==0){$sError=$ERR['cara01idtercero'];}
	if ($DATA['cara01idperaca']==''){$sError=$ERR['cara01idperaca'];}
	// -- Se verifican los valores de campos de otras tablas.
	if ($sError==''){$sError=tabla_terceros_existe($DATA['cara01idtercero_td'], $DATA['cara01idtercero_doc'], $objDB, 'El tercero Tercero ');}
	if ($sError==''){
		list($sError, $sInfo)=tercero_Bloqueado($DATA['cara01idtercero'], $objDB);
		if ($sInfo!=''){$sError=$sError.'<br>'.sInfo;}
		}
	if ($sError==''){
		if ($DATA['paso']==10){
			$sError=$ERR['2'];
			}else{
			if (!seg_revisa_permiso($iCodModulo, 14, $objDB)){$sError=$ERR['3'];}
			}
		}
	if ($sError==''){
		$bpasa=false;
		if ($DATA['paso']==10){}else{
			if($DATA['cara01discversion']==0) {
				$scampo[1] = 'cara01discsensorial';
				$scampo[2] = 'cara01discfisica';
				$scampo[3] = 'cara01disccognitiva';
				$scampo[4] = 'cara01perayuda';
				$scampo[5] = 'cara01discsensorialotra';
				$scampo[6] = 'cara01discfisicaotra';
				$scampo[7] = 'cara01disccognitivaotra';
				$scampo[8] = 'cara01perotraayuda';
				$scampo[9]='cara01idconfirmadisc';
				$scampo[10]='cara01fechaconfirmadisc';
				}
			if($DATA['cara01discversion']==1){
				$scampo[1] = 'cara01discv2sensorial';
				$scampo[2] = 'cara02discv2intelectura';
				$scampo[3] = 'cara02discv2fisica';
				$scampo[4] = 'cara02discv2psico';
				$scampo[5] = 'cara02discv2sistemica';
				$scampo[6] = 'cara02discv2multiple';
				$scampo[7] = 'cara02talentoexcepcional';
				$scampo[8] = 'cara01perayuda';
				$scampo[9] = 'cara02discv2sistemicaotro';
				$scampo[10] = 'cara02discv2multipleotro';
				$scampo[11]='cara01idconfirmadisc';
				$scampo[12]='cara01fechaconfirmadisc';
				}
			if($DATA['cara01discversion']==0) {
				$sdato[1] = $DATA['cara01discsensorial'];
				$sdato[2] = $DATA['cara01discfisica'];
				$sdato[3] = $DATA['cara01disccognitiva'];
				$sdato[4] = $DATA['cara01perayuda'];
				$sdato[5] = $DATA['cara01discsensorialotra'];
				$sdato[6] = $DATA['cara01discfisicaotra'];
				$sdato[7] = $DATA['cara01disccognitivaotra'];
				$sdato[8] = $DATA['cara01perotraayuda'];
				$sdato[9]=$_SESSION['unad_id_tercero'];
				$sdato[10]=fecha_DiaMod();
				$numcmod=10;
				}
			if($DATA['cara01discversion']==1){
				$sdato[1] = $DATA['cara01discv2sensorial'];
				$sdato[2] = $DATA['cara02discv2intelectura'];
				$sdato[3] = $DATA['cara02discv2fisica'];
				$sdato[4] = $DATA['cara02discv2psico'];
				$sdato[5] = $DATA['cara02discv2sistemica'];
				$sdato[6] = $DATA['cara02discv2multiple'];
				$sdato[7] = $DATA['cara02talentoexcepcional'];
				$sdato[8] = $DATA['cara01perayuda'];
				$sdato[9] = $DATA['cara02discv2sistemicaotro'];
				$sdato[10] = $DATA['cara02discv2multipleotro'];
				$sdato[11]=$_SESSION['unad_id_tercero'];
				$sdato[12]=fecha_DiaMod();
				$numcmod=12;
				}
			$sWhere='cara01id='.$DATA['cara01id'].'';
			$sSQL='SELECT * FROM cara01encuesta WHERE '.$sWhere;
			$sdatos='';
			$bPrimera=true;
			$result=$objDB->ejecutasql($sSQL);
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
					$sSQL='UPDATE cara01encuesta SET '.utf8_encode($sdatos).' WHERE '.$sWhere.';';
					}else{
					$sdetalle=$sdatos.'['.$sWhere.']';
					$sSQL='UPDATE cara01encuesta SET '.$sdatos.' WHERE '.$sWhere.';';
					}
				$idaccion=3;
				}
			}
		if ($bpasa){
			$result=$objDB->ejecutasql($sSQL);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' [2301] ..<!-- '.$sSQL.' -->';
				if ($idaccion==2){$DATA['cara01id']='';}
				$DATA['paso']=$DATA['paso']-10;
				$bCerrando=false;
				}else{
				if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Guardar 2301 '.$sSQL.'<br>';}
				if ($bAudita[$idaccion]){seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], $idaccion, $DATA['cara01id'], $sdetalle, $objDB);}
				$DATA['paso']=2;
				//Pasar la necesidad especial al tercero.
				$sNecesidad='';
				if($DATA['cara01discversion']==0) {
					if ($DATA['cara01discsensorial']!='N'){
						$sNecesidad=trim('Discapacidad sensorial '.$acara01discsensorial[$DATA['cara01discsensorial']].' '.$DATA['cara01discsensorialotra']);
						}
					if ($DATA['cara01discfisica']!='N'){
						if ($sNecesidad!=''){$sNecesidad=$sNecesidad.' - ';}
						$sNecesidad=$sNecesidad.trim('Discapacidad fisica '.$acara01discfisica[$DATA['cara01discfisica']].' '.$DATA['cara01discfisicaotra']);
						}
					if ($DATA['cara01disccognitiva']!='N'){
						if ($sNecesidad!=''){$sNecesidad=$sNecesidad.' - ';}
						$sNecesidad=$sNecesidad.trim('Discapacidad cognitiva '.$acara01disccognitiva[$DATA['cara01disccognitiva']].' '.$DATA['cara01disccognitivaotra']);
						}
					}
				if($DATA['cara01discversion']==1) {
					$acara37discapacidades=array();
					$acara02talentoexcepcional=array();
					$acara01discv2=array($DATA['cara01discv2sensorial'],$DATA['cara02discv2intelectura'],$DATA['cara02discv2fisica'],$DATA['cara02discv2psico']);
					foreach($acara01discv2 as $i_id){
						if (isset($acara37discapacidades[$i_id])==0){
							$sSQL='SELECT cara37nombre FROM cara37discapacidades WHERE cara37id='.$i_id.'';
							$tablae=$objDB->ejecutasql($sSQL);
							if ($objDB->nf($tablae)>0){
								$filae=$objDB->sf($tablae);
								$acara37discapacidades[$i_id]=$filae['cara37nombre'];
								}else{
								$acara37discapacidades[$i_id]='';
								}
							}
						}
					$i_cara02talentoexcepcional=$DATA['cara02talentoexcepcional'];
					if (isset($acara02talentoexcepcional[$i_cara02talentoexcepcional])==0){
						$sSQL='SELECT cara38nombre FROM cara38talentos WHERE cara38id='.$i_cara02talentoexcepcional.'';
						$tablae=$objDB->ejecutasql($sSQL);
						if ($objDB->nf($tablae)>0){
							$filae=$objDB->sf($tablae);
							$acara02talentoexcepcional[$i_cara02talentoexcepcional]=$filae['cara38nombre'];
							}else{
							$acara02talentoexcepcional[$i_cara02talentoexcepcional]='';
							}
						}
					if ($DATA['cara01discv2sensorial']!=''){
						$sNecesidad=$sNecesidad.trim('Discapacidad sensorial '.$acara37discapacidades[$DATA['cara01discv2sensorial']]);
						}
					if ($DATA['cara02discv2intelectura']!=''){
						if ($sNecesidad!=''){$sNecesidad=$sNecesidad.' - ';}
						$sNecesidad=$sNecesidad.trim('Discapacidad Intelectual '.$acara37discapacidades[$DATA['cara02discv2intelectura']]);
						}
					if ($DATA['cara02discv2fisica']!=''){
						if ($sNecesidad!=''){$sNecesidad=$sNecesidad.' - ';}
						$sNecesidad=$sNecesidad.trim('Discapacidad fisica '.$acara37discapacidades[$DATA['cara02discv2fisica']]);
						}
					if ($DATA['cara02discv2psico']!=''){
						if ($sNecesidad!=''){$sNecesidad=$sNecesidad.' - ';}
						$sNecesidad=$sNecesidad.trim('Discapacidad psicosocial '.$acara37discapacidades[$DATA['cara02discv2psico']]);
						}
					if ($DATA['cara02discv2sistemica']!='' && $DATA['cara02discv2sistemica']!=0){
						if ($sNecesidad!=''){$sNecesidad=$sNecesidad.' - ';}
						$sNecesidad=$sNecesidad.trim('Discapacidad sistemica '.$acara02discv2sistemica[$DATA['cara02discv2sistemica']].' '.$DATA['cara02discv2sistemicaotro']);
						}
					if ($DATA['cara02discv2multiple']!='' && $DATA['cara02discv2multiple']!=0){
						if ($sNecesidad!=''){$sNecesidad=$sNecesidad.' - ';}
						$sNecesidad=$sNecesidad.trim('Discapacidad multiple '.$acara02discv2multiple[$DATA['cara02discv2multiple']].' '.$DATA['cara02discv2multipleotro']);
						}
					if ($DATA['cara02talentoexcepcional']!=''){
						if ($sNecesidad!=''){$sNecesidad=$sNecesidad.' - ';}
						$sNecesidad=$sNecesidad.trim('Talento excepcional '.$acara02talentoexcepcional[$DATA['cara02talentoexcepcional']]);
						}
					}
				$bEntra=true;
				if ($DATA['cara01perayuda']==0){$bEntra=false;}
				if ($DATA['cara01perayuda']==-1){
					$bEntra=false;
					if ($sNecesidad!=''){$sNecesidad=$sNecesidad.' - ';}
					$sNecesidad=$sNecesidad.trim('Necesidad especial: '.$DATA['cara01perotraayuda']);
					}
				if ($bEntra){
					$sOtra='{'.$DATA['cara01perayuda'].'}';
					$sSQL='SELECT cara14nombre FROM cara14ayudaajuste WHERE cara14id='.$DATA['cara01perayuda'].'';
					$tablae=$objDB->ejecutasql($sSQL);
					if ($objDB->nf($tablae)>0){
						$filae=$objDB->sf($tablae);
						$sOtra=$filae['cara14nombre'];
						}
					if ($sNecesidad!=''){$sNecesidad=$sNecesidad.' - ';}
					$sNecesidad=$sNecesidad.'Necesidad especial: '.$sOtra;
					}
				$sSQL='UPDATE unad11terceros SET unad11necesidadesp="'.$sNecesidad.'" WHERE unad11id='.$DATA['cara01idtercero'].'';
				$result=$objDB->ejecutasql($sSQL);
				if($DATA['cara01discversion']==0) {
					$DATA['cara01idconfirmadisc'] = $sdato[9];
					$DATA['cara01fechaconfirmadisc'] = $sdato[10];
					}
				if($DATA['cara01discversion']==1) {
					$DATA['cara01idconfirmadisc'] = $sdato[11];
					$DATA['cara01fechaconfirmadisc'] = $sdato[12];
					}
				}
			}else{
			$DATA['paso']=2;
			}
		}else{
		$DATA['paso']=$DATA['paso']-10;
		$bCerrando=false;
		}
	$sInfoCierre='';
	return array($DATA, $sError, $iTipoError, $bCerrando, $sErrorCerrando, $sDebug);
	}
?>
