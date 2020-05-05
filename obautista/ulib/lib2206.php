<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2019 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.23.1 viernes, 12 de abril de 2019
--- 2206 Grupos de estudiantes
*/
function f2206_db_Guardar($valores, $objDB, $bDebug=false){
	$iCodModulo=2206;
	$bAudita[2]=false;
	$bAudita[3]=false;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2206='lg/lg_2206_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2206)){$mensajes_2206='lg/lg_2206_es.php';}
	require $mensajes_todas;
	require $mensajes_2206;
	$sError='';
	$sDebug='';
	$binserta=false;
	$iAccion=3;
	if(!is_array($valores)){$valores=json_decode(str_replace('\"','"',$valores),true);}
	$core06peraca=numeros_validar($valores[1]);
	$core06idcurso=numeros_validar($valores[2]);
	$core06consec=numeros_validar($valores[3]);
	$core06id=numeros_validar($valores[4], true);
	$core06idaula=numeros_validar($valores[5]);
	$core06grupoidforma=numeros_validar($valores[6]);
	$core06grupominest=numeros_validar($valores[7]);
	$core06grupomaxest=numeros_validar($valores[8]);
	$core06fechatopearmado=numeros_validar($valores[9]);
	$core06idtutor=numeros_validar($valores[10]);
	$core06iddirector=numeros_validar($valores[11]);
	$core06idestudiantelider=numeros_validar($valores[12]);
	$core06numinscritos=numeros_validar($valores[13]);
	$core06codigogrupo=htmlspecialchars(trim($valores[14]));
	$core06estado=numeros_validar($valores[15]);
	$core06idcead=numeros_validar($valores[16]);
	//if ($core06idaula==''){$core06idaula=0;}
	//if ($core06grupoidforma==''){$core06grupoidforma=0;}
	//if ($core06grupominest==''){$core06grupominest=0;}
	//if ($core06grupomaxest==''){$core06grupomaxest=0;}
	//if ($core06fechatopearmado==''){$core06fechatopearmado=0;}
	//if ($core06numinscritos==''){$core06numinscritos=0;}
	//if ($core06estado==''){$core06estado=0;}
	//if ($core06idcead==''){$core06idcead=0;}
	$sSepara=', ';
	if ($core06idcead==''){$sError=$ERR['core06idcead'].$sSepara.$sError;}
	if ($core06estado==''){$sError=$ERR['core06estado'].$sSepara.$sError;}
	if ($core06codigogrupo==''){$sError=$ERR['core06codigogrupo'].$sSepara.$sError;}
	if ($core06numinscritos==''){$sError=$ERR['core06numinscritos'].$sSepara.$sError;}
	if ($core06idestudiantelider==0){$sError=$ERR['core06idestudiantelider'].$sSepara.$sError;}
	if ($core06iddirector==0){$sError=$ERR['core06iddirector'].$sSepara.$sError;}
	if ($core06idtutor==0){$sError=$ERR['core06idtutor'].$sSepara.$sError;}
	if ($core06fechatopearmado==''){$sError=$ERR['core06fechatopearmado'].$sSepara.$sError;}
	if ($core06grupomaxest==''){$sError=$ERR['core06grupomaxest'].$sSepara.$sError;}
	if ($core06grupominest==''){$sError=$ERR['core06grupominest'].$sSepara.$sError;}
	if ($core06grupoidforma==''){$sError=$ERR['core06grupoidforma'].$sSepara.$sError;}
	if ($core06idaula==''){$sError=$ERR['core06idaula'].$sSepara.$sError;}
	//if ($core06id==''){$sError=$ERR['core06id'].$sSepara.$sError;}//CONSECUTIVO
	//if ($core06consec==''){$sError=$ERR['core06consec'].$sSepara.$sError;}//CONSECUTIVO
	if ($core06idcurso==''){$sError=$ERR['core06idcurso'].$sSepara.$sError;}
	if ($core06peraca==''){$sError=$ERR['core06peraca'].$sSepara.$sError;}
	if ($sError==''){
		list($sError, $sInfo)=tercero_Bloqueado($core06idestudiantelider, $objDB);
		if ($sInfo!=''){$sError=$sError.'<br>'.sInfo;}
		}
	if ($sError==''){
		list($sError, $sInfo)=tercero_Bloqueado($core06iddirector, $objDB);
		if ($sInfo!=''){$sError=$sError.'<br>'.sInfo;}
		}
	if ($sError==''){
		list($sError, $sInfo)=tercero_Bloqueado($core06idtutor, $objDB);
		if ($sInfo!=''){$sError=$sError.'<br>'.sInfo;}
		}
	if ($sError==''){
		if ((int)$core06id==0){
			if ((int)$core06consec==0){
				$core06consec=tabla_consecutivo('core06grupos', 'core06consec', 'core06peraca='.$core06peraca.' AND core06idcurso='.$core06idcurso.'', $objDB);
				if ($core06consec==-1){$sError=$objDB->serror;}
				}else{
				if (!seg_revisa_permiso($iCodModulo, 8, $objDB)){$sError=$ERR['8'];}
				}
			if ($sError==''){
				$sSQL='SELECT core06peraca FROM core06grupos WHERE core06peraca='.$core06peraca.' AND core06idcurso='.$core06idcurso.' AND core06consec='.$core06consec.'';
				$result=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($result)!=0){
					$sError=$ERR['existe'];
					}else{
					if (!seg_revisa_permiso($iCodModulo, 2, $objDB)){$sError=$ERR['2'];}
					}
				}
			if ($sError==''){
				$core06id=tabla_consecutivo('core06grupos', 'core06id', '', $objDB);
				if ($core06id==-1){$sError=$objDB->serror;}
				$binserta=true;
				$iAccion=2;
				}
			}else{
			if (!seg_revisa_permiso($iCodModulo, 3, $objDB)){$sError=$ERR['3'];}
			}
		}
	if ($sError==''){
		if ($binserta){
			}
		}
	if ($sError==''){
		if ($binserta){
			$scampos='core06peraca, core06idcurso, core06consec, core06id, core06idaula, 
core06grupoidforma, core06grupominest, core06grupomaxest, core06fechatopearmado, core06idtutor, 
core06iddirector, core06idestudiantelider, core06numinscritos, core06codigogrupo, core06estado, 
core06idcead';
			$svalores=''.$core06peraca.', '.$core06idcurso.', '.$core06consec.', '.$core06id.', '.$core06idaula.', 
'.$core06grupoidforma.', '.$core06grupominest.', '.$core06grupomaxest.', '.$core06fechatopearmado.', "'.$core06idtutor.'", 
"'.$core06iddirector.'", "'.$core06idestudiantelider.'", '.$core06numinscritos.', "'.$core06codigogrupo.'", '.$core06estado.', 
'.$core06idcead.'';
			if ($APP->utf8==1){
				$sSQL='INSERT INTO core06grupos ('.$scampos.') VALUES ('.utf8_encode($svalores).');';
				}else{
				$sSQL='INSERT INTO core06grupos ('.$scampos.') VALUES ('.$svalores.');';
				}
			$result=$objDB->ejecutasql($sSQL);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' {Grupos de estudiantes}.<!-- '.$sSQL.' -->';
				}else{
				if ($bAudita[2]){
					seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 2, $core06id, $sSQL, $objDB);
					}
				}
			}else{
			$scampo2206[1]='core06idaula';
			$scampo2206[2]='core06grupoidforma';
			$scampo2206[3]='core06grupominest';
			$scampo2206[4]='core06grupomaxest';
			$scampo2206[5]='core06fechatopearmado';
			$scampo2206[6]='core06idtutor';
			$scampo2206[7]='core06iddirector';
			$scampo2206[8]='core06idestudiantelider';
			$scampo2206[9]='core06numinscritos';
			$scampo2206[10]='core06codigogrupo';
			$scampo2206[11]='core06estado';
			$scampo2206[12]='core06idcead';
			$svr2206[1]=$core06idaula;
			$svr2206[2]=$core06grupoidforma;
			$svr2206[3]=$core06grupominest;
			$svr2206[4]=$core06grupomaxest;
			$svr2206[5]=$core06fechatopearmado;
			$svr2206[6]=$core06idtutor;
			$svr2206[7]=$core06iddirector;
			$svr2206[8]=$core06idestudiantelider;
			$svr2206[9]=$core06numinscritos;
			$svr2206[10]=$core06codigogrupo;
			$svr2206[11]=$core06estado;
			$svr2206[12]=$core06idcead;
			$inumcampos=12;
			$sWhere='core06id='.$core06id.'';
			//$sWhere='core06peraca='.$core06peraca.' AND core06idcurso='.$core06idcurso.' AND core06consec='.$core06consec.'';
			$sSQL='SELECT * FROM core06grupos WHERE '.$sWhere;
			$sdatos='';
			$bpasa=false;
			$result=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($result)>0){
				$filaorigen=$objDB->sf($result);
				for ($k=1;$k<=$inumcampos;$k++){
					if ($filaorigen[$scampo2206[$k]]!=$svr2206[$k]){
						if ($sdatos!=''){$sdatos=$sdatos.', ';}
						$sdatos=$sdatos.$scampo2206[$k].'="'.$svr2206[$k].'"';
						$bpasa=true;
						}
					}
				}
			if ($bpasa){
				if ($APP->utf8==1){
					$sSQL='UPDATE core06grupos SET '.utf8_encode($sdatos).' WHERE '.$sWhere.';';
					}else{
					$sSQL='UPDATE core06grupos SET '.$sdatos.' WHERE '.$sWhere.';';
					}
				$result=$objDB->ejecutasql($sSQL);
				if ($result==false){
					$sError=$ERR['falla_guardar'].' {Grupos de estudiantes}. <!-- '.$sSQL.' -->';
					}else{
					if ($bAudita[3]){
						seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 3, $core06id, $sSQL, $objDB);
						}
					}
				}
			}
		}
	return array($sError, $iAccion, $core06id, $sDebug);
	}
function f2206_db_Eliminar($aParametros, $objDB, $bDebug=false){
	$iCodModulo=2206;
	$bAudita[4]=false;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2206='lg/lg_2206_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2206)){$mensajes_2206='lg/lg_2206_es.php';}
	require $mensajes_todas;
	require $mensajes_2206;
	$sError='';
	$sDebug='';
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$core06peraca=numeros_validar($aParametros[1]);
	$core06idcurso=numeros_validar($aParametros[2]);
	$core06consec=numeros_validar($aParametros[3]);
	$core06id=numeros_validar($aParametros[4]);
	if ($sError==''){
		if (!seg_revisa_permiso($iCodModulo, 4, $objDB)){
			$sError=$ERR['4'];
			}
		}
	if ($sError==''){
		$sSQL='SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=2206';
		$tablaor=$objDB->ejecutasql($sSQL);
		while ($filaor=$objDB->sf($tablaor)){
			$sSQL='SELECT '.$filaor['campoid'].' FROM '.$filaor['tabla'].' WHERE '.$filaor['camporev'].'='.$core06id.' LIMIT 0, 1';
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
		$sWhere='core06id='.$core06id.'';
		//$sWhere='core06peraca='.$core06peraca.' AND core06idcurso='.$core06idcurso.' AND core06consec='.$core06consec.'';
		$sSQL='DELETE FROM core06grupos WHERE '.$sWhere.';';
		$result=$objDB->ejecutasql($sSQL);
		if ($result==false){
			$sError=$ERR['falla_eliminar'].' {2206 Grupos de estudiantes}.<!-- '.$sSQL.' -->';
			}else{
			if ($bAudita[4]){
				seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $core06id, $sSQL, $objDB);
				}
			}
		}
	return array($sError, $sDebug);
	}
function f2206_TablaDetalleV2($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2206='lg/lg_2206_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2206)){$mensajes_2206='lg/lg_2206_es.php';}
	require $mensajes_todas;
	require $mensajes_2206;
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	if (isset($aParametros[0])==0){$aParametros[0]=-1;}
	if (isset($aParametros[101])==0){$aParametros[101]=1;}
	if (isset($aParametros[102])==0){$aParametros[102]=20;}
	//if (isset($aParametros[103])==0){$aParametros[103]='';}
	//$aParametros[103]=numeros_validar($aParametros[103]);
	$aParametros[0]=numeros_validar($aParametros[0]);
	if ($aParametros[0]==''){$aParametros[0]=-1;}
	$sDebug='';
	$ofer08id=$aParametros[0];
	$pagina=$aParametros[101];
	$lineastabla=$aParametros[102];
	$babierta=true;
	$idPeraca=0;
	$idCurso=0;
	$idContenedor=0;
	$sSQL='SELECT ofer08idper_aca, ofer08idcurso FROM ofer08oferta WHERE ofer08id='.$ofer08id;
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$idPeraca=$fila['ofer08idper_aca'];
		$idCurso=$fila['ofer08idcurso'];
		$idContenedor=f146_Contenedor($idPeraca, $objDB);
		}
	$sSQLadd='';
	$sSQLadd1='';
	$sLeyenda='';
	$bConError=false;
	if ($idPeraca==0){
		$sLeyenda='<div class="salto1px"></div>
<div class="GrupoCamposAyuda">
<b>No se ha definido un periodo acad&eacute;mico para la oferta</b>
<div class="salto1px"></div>
</div>';
		$bConError=true;
		}
	if ($idContenedor==0){
		$sLeyenda='<div class="salto1px"></div>
<div class="GrupoCamposAyuda">
<b>No se ha definido un contenedor para los grupos del periodo '.$idPeraca.'</b>
<div class="salto1px"></div>
</div>';
		$bConError=true;
		}
	if ($bConError){
		$res=$sLeyenda.'<input id="paginaf2206" name="paginaf2206" type="hidden" value="'.$pagina.'"/><input id="lppf2206" name="lppf2206" type="hidden" value="'.$lineastabla.'"/>';
		return array(utf8_encode($res), $sDebug);
		die();
		}
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
	$sTitulos='Peraca, Curso, Consec, Id, Aula, Grupoidforma, Grupominest, Grupomaxest, Fechatopearmado, Tutor, Director, Estudiantelider, Numinscritos, Codigogrupo, Estado, Cead';
	$sSQL='SELECT TB.core06peraca, TB.core06idcurso, TB.core06consec, TB.core06id, TB.core06idaula, TB.core06grupoidforma, TB.core06grupominest, TB.core06grupomaxest, TB.core06fechatopearmado, T10.unad11razonsocial AS C10_nombre, TB.core06numinscritos, TB.core06codigogrupo, TB.core06estado, TB.core06idtutor, T10.unad11tipodoc AS C10_td, T10.unad11doc AS C10_doc, TB.core06iddirector, TB.core06idestudiantelider, TB.core06idcead 
FROM core06grupos_'.$idContenedor.' AS TB, unad11terceros AS T10 
WHERE '.$sSQLadd1.' TB.core06idcurso='.$idCurso.' AND TB.core06peraca='.$idPeraca.' AND TB.core06idtutor=T10.unad11id '.$sSQLadd.'
ORDER BY TB.core06consec';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_2206" name="consulta_2206" type="hidden" value="'.$sSQLlista.'"/>
<input id="titulos_2206" name="titulos_2206" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objDB->ejecutasql($sSQL);
	if ($tabladetalle==false){
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 2206: '.$sSQL.'<br>';}
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		//$sLeyenda=$sSQL;
		}else{
		$registros=$objDB->nf($tabladetalle);
		if ($registros==0){
			return array(utf8_encode($sErrConsulta.'<input id="paginaf2206" name="paginaf2206" type="hidden" value="'.$pagina.'"/><input id="lppf2206" name="lppf2206" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
			}
		if ((($registros-1)/$lineastabla)<($pagina-1)){$pagina=(int)(($registros-1)/$lineastabla)+1;}
		if ($registros>$lineastabla){
			$rbase=($pagina-1)*$lineastabla;
			$limite=' LIMIT '.$rbase.', '.$lineastabla;
			$tabladetalle=$objDB->ejecutasql($sSQL.$limite);
			}
		}
	$res=$sErrConsulta.$sLeyenda.'<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">
<tr class="fondoazul">
<td><b>'.$ETI['core06consec'].'</b></td>
<td><b>'.$ETI['core06idaula'].'</b></td>
<td><b>'.$ETI['core06grupoidforma'].'</b></td>
<td><b>'.$ETI['core06grupominest'].'</b></td>
<td><b>'.$ETI['core06grupomaxest'].'</b></td>
<td><b>'.$ETI['core06fechatopearmado'].'</b></td>
<td colspan="2"><b>'.$ETI['core06idtutor'].'</b></td>
<td><b>'.$ETI['core06numinscritos'].'</b></td>
<td align="right">
'.html_paginador('paginaf2206', $registros, $lineastabla, $pagina, 'paginarf2206()').'
'.html_lpp('lppf2206', $lineastabla, 'paginarf2206()').'
</td>
</tr>';
	$tlinea=1;
	$idTutor=-1;
	while($filadet=$objDB->sf($tabladetalle)){
		if ($idTutor!=$filadet['core06idtutor']){
			}
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
		$et_core06consec=$sPrefijo.$filadet['core06consec'].$sSufijo;
		$et_core06idaula=$sPrefijo.$filadet['core06idaula'].$sSufijo;
		$et_core06grupoidforma=$sPrefijo.$filadet['core06grupoidforma'].$sSufijo;
		$et_core06grupominest=$sPrefijo.$filadet['core06grupominest'].$sSufijo;
		$et_core06grupomaxest=$sPrefijo.$filadet['core06grupomaxest'].$sSufijo;
		$et_core06fechatopearmado=$sPrefijo.$filadet['core06fechatopearmado'].$sSufijo;
		$et_core06idtutor=$sPrefijo.$filadet['core06idtutor'].$sSufijo;
		$et_core06iddirector=$sPrefijo.$filadet['core06iddirector'].$sSufijo;
		$et_core06idestudiantelider=$sPrefijo.$filadet['core06idestudiantelider'].$sSufijo;
		$et_core06numinscritos=$sPrefijo.$filadet['core06numinscritos'].$sSufijo;
		$et_core06codigogrupo=$sPrefijo.cadena_notildes($filadet['core06codigogrupo']).$sSufijo;
		//$et_core06estado=$sPrefijo.$filadet['core06estado'].$sSufijo;
		//$et_core06idcead=$sPrefijo.cadena_notildes($filadet['unad24nombre']).$sSufijo;
		if ($babierta){
			//$sLink='<a href="javascript:cargaridf2206('.$filadet['core06id'].')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$res=$res.'<tr'.$sClass.'>
<td>'.$et_core06consec.'</td>
<td>'.$et_core06idaula.'</td>
<td>'.$et_core06grupoidforma.'</td>
<td>'.$et_core06grupominest.'</td>
<td>'.$et_core06grupomaxest.'</td>
<td>'.$et_core06fechatopearmado.'</td>
<td>'.$sPrefijo.$filadet['C10_td'].' '.$filadet['C10_doc'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['C10_nombre']).$sSufijo.'</td>
<td>'.$et_core06numinscritos.'</td>
<td>'.$sLink.'</td>
</tr>';
		}
	$res=$res.'</table>';
	$objDB->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
	}
function f2206_Clonar($core06idcurso, $core06idcursoPadre, $objDB){
	$sError='';
	$core06consec=tabla_consecutivo('core06grupos', 'core06consec', 'core06idcurso='.$core06idcurso.'', $objDB);
	if ($core06consec==-1){$sError=$objDB->serror;}
	$core06id=tabla_consecutivo('core06grupos', 'core06id', '', $objDB);
	if ($core06id==-1){$sError=$objDB->serror;}
	if ($sError==''){
		$sCampos2206='core06peraca, core06idcurso, core06consec, core06id, core06idaula, core06grupoidforma, core06grupominest, core06grupomaxest, core06fechatopearmado, core06idtutor, core06iddirector, core06idestudiantelider, core06numinscritos, core06codigogrupo, core06estado, core06idcead';
		$sValores2206='';
		$sSQL='SELECT * FROM core06grupos WHERE core06idcurso='.$core06idcursoPadre.'';
		$tabla=$objDB->ejecutasql($sSQL);
		while($fila=$objDB->sf($tabla)){
			if ($sValores2206!=''){$sValores2206=$sValores2206.', ';}
			$sValores2206=$sValores2206.'('.$fila['core06peraca'].', '.$core06idcurso.', '.$core06consec.', '.$core06id.', '.$fila['core06idaula'].', '.$fila['core06grupoidforma'].', '.$fila['core06grupominest'].', '.$fila['core06grupomaxest'].', '.$fila['core06fechatopearmado'].', '.$fila['core06idtutor'].', '.$fila['core06iddirector'].', '.$fila['core06idestudiantelider'].', '.$fila['core06numinscritos'].', "'.$fila['core06codigogrupo'].'", '.$fila['core06estado'].', '.$fila['core06idcead'].')';
			$core06consec++;
			$core06id++;
			}
		if ($sValores2206!=''){
			$sSQL='INSERT INTO core06grupos('.$sCampos2206.') VALUES '.$sValores2206.'';
			$result=$objDB->ejecutasql($sSQL);
			}
		}
	return $sError;
	}
// -- 2206 Grupos de estudiantes XAJAX 
function f2206_Guardar($valores, $aParametros){
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
		list($sError, $iAccion, $core06id, $sDebugGuardar)=f2206_db_Guardar($valores, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugGuardar;
		}
	$objResponse=new xajaxResponse();
	if ($sError==''){
		list($sdetalle, $sDebugTabla)=f2206_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugTabla;
		$objResponse->assign('div_f2206detalle', 'innerHTML', $sdetalle);
		//if ($iAccion==2){
			//$objResponse->call('cargaridf2206('.$core06id.')');
			//}else{
			$objResponse->call('limpiaf2206');
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
function f2206_Traer($aParametros){
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
		$core06peraca=numeros_validar($aParametros[1]);
		$core06idcurso=numeros_validar($aParametros[2]);
		$core06consec=numeros_validar($aParametros[3]);
		if (($core06peraca!='')&&($core06idcurso!='')&&($core06consec!='')){$besta=true;}
		}else{
		$core06id=$aParametros[103];
		if ((int)$core06id!=0){$besta=true;}
		}
	if ($besta){
		$besta=false;
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		$bHayDb=true;
		$sSQLcondi='';
		if ($paso==1){
			$sSQLcondi=$sSQLcondi.'core06peraca='.$core06peraca.' AND core06idcurso='.$core06idcurso.' AND core06consec='.$core06consec.'';
			}else{
			$sSQLcondi=$sSQLcondi.'core06id='.$core06id.'';
			}
		$sSQL='SELECT * FROM core06grupos WHERE '.$sSQLcondi;
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
		$core06idtutor_id=(int)$fila['core06idtutor'];
		$core06idtutor_td=$APP->tipo_doc;
		$core06idtutor_doc='';
		$core06idtutor_nombre='';
		if ($core06idtutor_id!=0){
			list($core06idtutor_nombre, $core06idtutor_id, $core06idtutor_td, $core06idtutor_doc)=html_tercero($core06idtutor_td, $core06idtutor_doc, $core06idtutor_id, 0, $objDB);
			}
		$core06iddirector_id=(int)$fila['core06iddirector'];
		$core06iddirector_td=$APP->tipo_doc;
		$core06iddirector_doc='';
		$core06iddirector_nombre='';
		if ($core06iddirector_id!=0){
			list($core06iddirector_nombre, $core06iddirector_id, $core06iddirector_td, $core06iddirector_doc)=html_tercero($core06iddirector_td, $core06iddirector_doc, $core06iddirector_id, 0, $objDB);
			}
		$core06idestudiantelider_id=(int)$fila['core06idestudiantelider'];
		$core06idestudiantelider_td=$APP->tipo_doc;
		$core06idestudiantelider_doc='';
		$core06idestudiantelider_nombre='';
		if ($core06idestudiantelider_id!=0){
			list($core06idestudiantelider_nombre, $core06idestudiantelider_id, $core06idestudiantelider_td, $core06idestudiantelider_doc)=html_tercero($core06idestudiantelider_td, $core06idestudiantelider_doc, $core06idestudiantelider_id, 0, $objDB);
			}
		$core06peraca_nombre='';
		$html_core06peraca=html_oculto('core06peraca', $fila['core06peraca'], $core06peraca_nombre);
		$objResponse->assign('div_core06peraca', 'innerHTML', $html_core06peraca);
		$core06consec_nombre='';
		$html_core06consec=html_oculto('core06consec', $fila['core06consec'], $core06consec_nombre);
		$objResponse->assign('div_core06consec', 'innerHTML', $html_core06consec);
		$core06id_nombre='';
		$html_core06id=html_oculto('core06id', $fila['core06id'], $core06id_nombre);
		$objResponse->assign('div_core06id', 'innerHTML', $html_core06id);
		$objResponse->assign('core06idaula', 'value', $fila['core06idaula']);
		$objResponse->assign('core06grupoidforma', 'value', $fila['core06grupoidforma']);
		$objResponse->assign('core06grupominest', 'value', $fila['core06grupominest']);
		$objResponse->assign('core06grupomaxest', 'value', $fila['core06grupomaxest']);
		$objResponse->assign('core06fechatopearmado', 'value', $fila['core06fechatopearmado']);
		$objResponse->assign('core06idtutor', 'value', $fila['core06idtutor']);
		$objResponse->assign('core06idtutor_td', 'value', $core06idtutor_td);
		$objResponse->assign('core06idtutor_doc', 'value', $core06idtutor_doc);
		$objResponse->assign('div_core06idtutor', 'innerHTML', $core06idtutor_nombre);
		$objResponse->assign('core06iddirector', 'value', $fila['core06iddirector']);
		$objResponse->assign('core06iddirector_td', 'value', $core06iddirector_td);
		$objResponse->assign('core06iddirector_doc', 'value', $core06iddirector_doc);
		$objResponse->assign('div_core06iddirector', 'innerHTML', $core06iddirector_nombre);
		$objResponse->assign('core06idestudiantelider', 'value', $fila['core06idestudiantelider']);
		$objResponse->assign('core06idestudiantelider_td', 'value', $core06idestudiantelider_td);
		$objResponse->assign('core06idestudiantelider_doc', 'value', $core06idestudiantelider_doc);
		$objResponse->assign('div_core06idestudiantelider', 'innerHTML', $core06idestudiantelider_nombre);
		$objResponse->assign('core06numinscritos', 'value', $fila['core06numinscritos']);
		$objResponse->assign('core06codigogrupo', 'value', $fila['core06codigogrupo']);
		$objResponse->assign('core06estado', 'value', $fila['core06estado']);
		$objResponse->assign('core06idcead', 'value', $fila['core06idcead']);
		$objResponse->call("MensajeAlarmaV2('', 0)");
		$objResponse->call("verboton('belimina2206','block')");
		}else{
		if ($paso==1){
			$objResponse->assign('core06peraca', 'value', $core06peraca);
			$objResponse->assign('core06consec', 'value', $core06consec);
			}else{
			$objResponse->call('MensajeAlarmaV2("No se encontro el registro de referencia:'.$core06id.'", 0)');
			}
		}
	if ($bHayDb){
		$objDB->CerrarConexion();
		}
	return $objResponse;
	}
function f2206_Eliminar($aParametros){
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
	list($sError, $sDebugElimina)=f2206_db_Eliminar($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugElimina;
	$objResponse=new xajaxResponse();
	if ($sError==''){
		list($sDetalle, $sDebugTabla)=f2206_TablaDetalleV2($aParametros, $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugTabla;
		$objResponse->assign('div_f2206detalle', 'innerHTML', $sDetalle);
		$objResponse->call('limpiaf2206');
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
function f2206_HtmlTabla($aParametros){
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
	list($sDetalle, $sDebugTabla)=f2206_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f2206detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f2206_PintarLlaves($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	//if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	if (isset($APP->piel)==0){$APP->piel=1;}
	$iPiel=$APP->piel;
	$html_core06peraca='<input id="core06peraca" name="core06peraca" type="text" value="" onchange="revisaf2206()" class="cuatro"/>';
	$html_core06consec='<input id="core06consec" name="core06consec" type="text" value="" onchange="revisaf2206()" class="cuatro"/>';
	$html_core06id='<input id="core06id" name="core06id" type="hidden" value=""/>';
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_core06peraca','innerHTML', $html_core06peraca);
	$objResponse->assign('div_core06consec','innerHTML', $html_core06consec);
	$objResponse->assign('div_core06id','innerHTML', $html_core06id);
	return $objResponse;
	}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------
?>