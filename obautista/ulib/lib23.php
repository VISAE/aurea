<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2019 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
*/
function f2301_InfoEncuesta($idEncuesta, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_2301=$APP->rutacomun.'lg/lg_2301_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2301)){$mensajes_2301=$APP->rutacomun.'lg/lg_2301_es.php';}
	require $mensajes_2301;
	$sError='';
	$sDebug='';
	$aData=array();
	$sSQL='SELECT * FROM cara01encuesta WHERE cara01id='.$idEncuesta.'';
	if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Datos de la encuesta: '.$sSQL.'<br>';}
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$aData['cara01idperaca']=$fila['cara01idperaca'];
		$aData['cara01completa']=$fila['cara01completa'];
		$aData['cara01fechaencuesta']=$fila['cara01fechaencuesta'];
		$aData['cara01agnos']=$fila['cara01agnos'];
		$aData['cara01tipocaracterizacion']=$fila['cara01tipocaracterizacion'];
		$aData['cara01idperiodoacompana']=$fila['cara01idperiodoacompana'];
		$aData['cara01fechacierreacom']=$fila['cara01fechacierreacom'];
		//Estos valores son la base para calcular el nivel por competencias...
		$aData['cara01psico_puntaje']=$fila['cara01psico_puntaje'];
		$aData['cara01niveldigital']=$fila['cara01niveldigital'];
		$aData['cara01nivellectura']=$fila['cara01nivellectura'];
		$aData['cara01nivelrazona']=$fila['cara01nivelrazona'];
		$aData['cara01nivelingles']=$fila['cara01nivelingles'];
		$aData['cara01nivelbiolog']=$fila['cara01nivelbiolog'];
		$aData['cara01nivelfisica']=$fila['cara01nivelfisica'];
		$aData['cara01nivelquimica']=$fila['cara01nivelquimica'];
		$aData['cara01factorprincipaldesc']=$fila['cara01factorprincipaldesc'];
		$aData['cara01idcursocatedra']=$fila['cara01idcursocatedra'];
		$aData['cara01factorprincpermanencia']=$fila['cara01factorprincpermanencia'];
		//cara01factorprincpermanencia
		//Ahora los riesgos que son tipo texto..
		$r1='';
		$r2='';
		$r3='';
		$r4='';
		$iPuntajeSD=0;
		$iPuntajePsico=0;
		$iPuntajeAca=0;
		$iPuntajeEco=0;
		//Variables de igualacion, las validaciones tienen la misma estructura.
		$aCampo=array('cara01zonares', 'cara01fam_dependeecon', 'cara01inpecrecluso', 'cara01indigenas', 'cara01discsensorial', 'cara01discfisica', 'cara01disccognitiva');
		$aValor=array('R', 'S', 'S', 0, 'N', 'N', 'N');
		$aEti=array('msg_r1zona', 'msg_r1depende', 'msg_r1recluso', 'msg_r1indigena', 'msg_r1discsen', 'msg_r1discfis', 'msg_r1disccog');
		$aSuma=array(5, 3, 2, 5, 3, 3, 4);
		$aTipo=array(0,0,0,1,1,1,1);
		$iCampos=7;
		for ($k=0;$k<$iCampos;$k++){
			$bEsta=false;
			if ($aTipo[$k]==0){
				if ($fila[$aCampo[$k]]==$aValor[$k]){$bEsta=true;}
				}else{
				if ($fila[$aCampo[$k]]!=$aValor[$k]){$bEsta=true;}
				}
			if ($bEsta){
				$r1=$r1.$ETI[$aEti[$k]].' ('.$aSuma[$k].'), ';
				$iPuntajeSD=$iPuntajeSD+$aSuma[$k];
				}
			}
		if ($fila['cara01victimadesplazado']=='S'){
			if ($fila['cara01idconfirmadesp']!=0){
				$r1=$r1.$ETI['msg_r1desplazado'].', ';
				$iPuntajeSD=$iPuntajeSD+3;
				}else{
				$r1=$r1.'('.$ETI['msg_r1desplazado'].'), ';
				}
			}
		if ($fila['cara01victimaacr']=='S'){
			if ($fila['cara01idconfirmacr']!=0){
				$r1=$r1.$ETI['msg_r1acr'].', ';
				$iPuntajeSD=$iPuntajeSD+3;
				}else{
				$r1=$r1.'('.$ETI['msg_r1acr'].'), ';
				}
			}
		//Ahora vamos por los psilogicos // Aunque... ya estan en cara01psico_puntaje
		$aCampo=array('cara01acad_tiemposinest', 'cara01acad_primeraopc');
		$aValor=array(6, 'N');
		$aEti=array('msg_r3tiemponoest', 'msg_r3otroprog');
		$aSuma=array(5, 5);
		$aTipo=array(0,0);
		$iCampos=0;
		for ($k=0;$k<$iCampos;$k++){
			$bEsta=false;
			if ($aTipo[$k]==0){
				if ($fila[$aCampo[$k]]==$aValor[$k]){$bEsta=true;}
				}else{
				if ($fila[$aCampo[$k]]!=$aValor[$k]){$bEsta=true;}
				}
			if ($bEsta){
				$r2=$r2.$ETI[$aEti[$k]].' ('.$aSuma[$k].'), ';
				$iPuntajePsico=$iPuntajePsico+$aSuma[$k];
				}
			}
		//Ahora los academicos (El internet y la electricidad se validan doble vez)
		$aCampo=array('cara01acad_tiemposinest', 'cara01acad_primeraopc', 'cara01campus_energia', 'cara01campus_energia', 'cara01campus_internetreside', 'cara01campus_internetreside', 'cara01campus_ofimatica', 'cara01campus_usocorreo');
		$aValor=array(6, 'N', 2, 3, 2, 3, 'N', 4);
		$aEti=array('msg_r3tiemponoest', 'msg_r3otroprog', 'msg_r3energia', 'msg_r3energia', 'msg_r3internet', 'msg_r3internet', 'msg_r3ofimatica', 'msg_r3nocorreo');
		$aSuma=array(5, 5, 5, 5, 5, 5, 3, 3);
		$aTipo=array(0, 0, 0, 0, 0, 0, 0, 0);
		$iCampos=8;
		for ($k=0;$k<$iCampos;$k++){
			$bEsta=false;
			if ($aTipo[$k]==0){
				if ($fila[$aCampo[$k]]==$aValor[$k]){$bEsta=true;}
				}else{
				if ($fila[$aCampo[$k]]!=$aValor[$k]){$bEsta=true;}
				}
			if ($bEsta){
				$r3=$r3.$ETI[$aEti[$k]].' ('.$aSuma[$k].'), ';
				$iPuntajeAca=$iPuntajeAca+$aSuma[$k];
				}
			}
		if (($fila['cara01campus_compescrito']=='N')&&($fila['cara01campus_portatil']=='N')){
			if (($fila['cara01campus_tableta']=='N')&&($fila['cara01campus_telefono']=='N')){
				$r3=$r3.$ETI['msg_r3sinequipo'].', ';
				$iPuntajeAca=$iPuntajeAca+5;
				}else{
				$r3=$r3.$ETI['msg_r3sincomputador'].', ';
				$iPuntajeAca=$iPuntajeAca+4;
				}
			}
		//Los economicos
		$aCampo=array('cara01lab_situacion', 'cara01lab_rangoingreso', 'cara01lab_tiempoacadem');
		$aValor=array(4, 1, 1);
		$aEti=array('msg_r4desempleado', 'msg_r4menos1smm', 'msg_r4pocotiempo');
		$aSuma=array(3, 3, 3);
		$aTipo=array(0, 0, 0);
		$iCampos=3;
		for ($k=0;$k<$iCampos;$k++){
			$bEsta=false;
			if ($aTipo[$k]==0){
				if ($fila[$aCampo[$k]]==$aValor[$k]){$bEsta=true;}
				}else{
				if ($fila[$aCampo[$k]]!=$aValor[$k]){$bEsta=true;}
				}
			if ($bEsta){
				$r4=$r4.$ETI[$aEti[$k]].' ('.$aSuma[$k].'), ';
				$iPuntajeEco=$iPuntajeEco+$aSuma[$k];
				}
			}
		//Ahora a calcular los factores.
		if ($iPuntajeSD>15){
			$sCpto='<h3>'.$ariesgo[3];
			$sCierre='</h3>';
			}else{
			if ($iPuntajeSD>4){
				$sCpto='<b>'.$ariesgo[2];
				$sCierre='</b>';
				}else{
				$sCpto=''.$ariesgo[1];
				$sCierre='';
				}
			}
		$r1=$sCpto.' ['.$r1.']'.$sCierre;
		if ($iPuntajePsico>15){
			$sCpto='<h3>'.$ariesgo[3];
			$sCierre='</h3>';
			}else{
			if ($iPuntajePsico>4){
				$sCpto='<b>'.$ariesgo[2];
				$sCierre='</b>';
				}else{
				$sCpto=''.$ariesgo[1];
				$sCierre='';
				}
			}
		$r2=$sCpto.' ['.$r2.']'.$sCierre;
		if ($iPuntajeAca>12){
			$sCpto='<h3>'.$ariesgo[3];
			$sCierre='</h3>';
			}else{
			if ($iPuntajeAca>4){
				$sCpto='<b>'.$ariesgo[2];
				$sCierre='</b>';
				}else{
				$sCpto=''.$ariesgo[1];
				$sCierre='';
				}
			}
		$r3=$sCpto.' ['.$r3.']'.$sCierre;
		if ($iPuntajeEco>5){
			$sCpto='<h3>'.$ariesgo[3];
			$sCierre='</h3>';
			}else{
			if ($iPuntajeEco>3){
				$sCpto='<b>'.$ariesgo[2];
				$sCierre='</b>';
				}else{
				$sCpto=''.$ariesgo[1];
				$sCierre='';
				}
			}
		$r4=$sCpto.' ['.$r4.']'.$sCierre;
		//Ahora guardamos en la base de datos para cuando nos los pidan.
		$bEntra=false;
		if ($fila['cara01factordescper']!=$iPuntajeSD){$bEntra=true;}
		if ($fila['cara01factordescpsico']!=$iPuntajePsico){$bEntra=true;}
		if ($fila['cara01factordescacad']!=$iPuntajeAca){$bEntra=true;}
		if ($fila['cara01factordesc']!=$iPuntajeEco){$bEntra=true;}
		if ($bEntra){
			//, cara01factordescpsico='.$iPuntajePsico.'
			$sSQL='UPDATE cara01encuesta SET cara01factordescper='.$iPuntajeSD.', cara01factordescacad='.$iPuntajeAca.', cara01factordesc='.$iPuntajeEco.' WHERE cara01id='.$idEncuesta.'';
			$result=$objDB->ejecutasql($sSQL);
			}
		//Fin del guardado
		$aData['r1']=$r1;
		$aData['r2']=$r2;
		$aData['r3']=$r3;
		$aData['r4']=$r4;
		}else{
		$sError='No se ha encontrado el registro de encuesta '.$idEncuesta.' '.$sSQL;
		}
	return array($sError, $aData, $sDebug);
	}
?>
