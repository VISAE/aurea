<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2018 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.22.2 lunes, 16 de julio de 2018
--- 2308 cara08pregunta
*/
function f2308_ExisteDato($datos){
	if(!is_array($datos)){$datos=json_decode(str_replace('\"','"',$datos),true);}
	$_SESSION['u_ultimominuto']=iminutoavance();
	$bHayLlave=true;
	$cara08idbloque=numeros_validar($datos[1]);
	if ($cara08idbloque==''){$bHayLlave=false;}
	$cara08consec=numeros_validar($datos[2]);
	if ($cara08consec==''){$bHayLlave=false;}
	if ($bHayLlave){
		require './app.php';
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		$sSQL='SELECT cara08consec FROM cara08pregunta WHERE cara08idbloque='.$cara08idbloque.' AND cara08consec='.$cara08consec.'';
		$res=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($res)==0){$bHayLlave=false;}
		$objDB->CerrarConexion();
		if ($bHayLlave){
			$objResponse=new xajaxResponse();
			$objResponse->call('cambiapaginaV2');
			return $objResponse;
			}
		}
	}
function f2308_Busquedas($aParametros){
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2308='lg/lg_2308_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2308)){$mensajes_2308='lg/lg_2308_es.php';}
	require $mensajes_todas;
	require $mensajes_2308;
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$sCampo=$aParametros[1];
	$sTitulo=' {'.$sCampo.'}';
	if (isset($aParametros[2])==0){$aParametros[2]=0;}
	if (isset($aParametros[3])==0){$aParametros[3]=0;}
	$sParams='';
	$sTabla='';
	$sJavaBusqueda='';
	$aParametrosB=array();
	$aParametrosB[101]=1;
	$aParametrosB[102]=20;
	switch($sCampo){
		}
	$sTitulo='<h2>'.$ETI['titulo_2308'].' - '.$sTitulo.'</h2>';
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97titulo', 'innerHTML', $sTitulo);
	$objResponse->assign('div_97params', 'innerHTML', $sParams);
	$objResponse->assign('div_97tabla', 'innerHTML', $sTabla);
	$objResponse->setFunction('paginarbusqueda','',$sJavaBusqueda);
	$objResponse->call('expandesector(97)');
	return $objResponse;
	}
function f2308_HtmlBusqueda($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$sError='';
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$sDetalle='';
	switch($aParametros[100]){
		}
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97tabla', 'innerHTML', $sDetalle);
	return $objResponse;
	}
function f2308_TablaDetalleV2($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2308='lg/lg_2308_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2308)){$mensajes_2308='lg/lg_2308_es.php';}
	require $mensajes_todas;
	require $mensajes_2308;
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	if (isset($aParametros[101])==0){$aParametros[101]=1;}
	if (isset($aParametros[102])==0){$aParametros[102]=20;}
	if (isset($aParametros[103])==0){$aParametros[103]='-1';}
	if (isset($aParametros[104])==0){$aParametros[104]='';}
	if (isset($aParametros[105])==0){$aParametros[105]='';}
	//$aParametros[103]=numeros_validar($aParametros[103]);
	$sDebug='';
	$pagina=$aParametros[101];
	$lineastabla=$aParametros[102];
	$babierta=true;
	//$sSQL='SELECT Campo FROM Tabla WHERE Id='.$sValorId;
	//$tabla=$objDB->ejecutasql($sSQL);
	//if ($objDB->nf($tabla)>0){
		//$fila=$objDB->sf($tabla);
		//if ($fila['Campo']!='S'){$babierta=true;}
		//}
	$sLeyenda='';
	if (false){
		$sLeyenda='<div class="salto1px"></div>
<div class="GrupoCamposAyuda">
<b>Importante:</b> Mensaje al usuario
<div class="salto1px"></div>
</div>';
		}
	$sSQLadd='';
	$sSQLadd1='';
	//if ((int)$aParametros[103]!=-1){$sSQLadd=$sSQLadd.' AND TB.campo='.$aParametros[103];}
	if ($aParametros[104]!=''){$sSQLadd=$sSQLadd.' AND TB.cara08titulo LIKE "%'.$aParametros[104].'%"';}
	if ($aParametros[105]!=''){
		$sBase=trim(strtoupper($aParametros[105]));
		$aNoms=explode(' ', $sBase);
		for ($k=1;$k<=count($aNoms);$k++){
			$sCadena=$aNoms[$k-1];
			if ($sCadena!=''){
				$sSQLadd=$sSQLadd.' AND TB.cara08cuerpo LIKE "%'.$sCadena.'%"';
				//$sSQLadd1=$sSQLadd1.'T1.sys11razonsocial LIKE "%'.$sCadena.'%" AND ';
				}
			}
		}
	$sTitulos='Bloque, Consec, Id, Activa, Grupo, Titulo, Cuerpo, Tipopreg, Usosiniciales, Usostotales';
	$sSQL='SELECT TB.cara08idbloque, TB.cara08consec, TB.cara08id, TB.cara08activa, TB.cara08titulo, TB.cara08tipopreg, TB.cara08usosiniciales, TB.cara08usostotales, TB.cara08idgrupo 
FROM cara08pregunta AS TB 
WHERE '.$sSQLadd1.'TB.cara08idbloque='.$aParametros[103].' '.$sSQLadd.'
ORDER BY TB.cara08idbloque, TB.cara08consec';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_2308" name="consulta_2308" type="hidden" value="'.$sSQLlista.'"/>
<input id="titulos_2308" name="titulos_2308" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objDB->ejecutasql($sSQL);
	if ($tabladetalle==false){
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 2308: '.$sSQL.'<br>';}
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		//$sLeyenda=$sSQL;
		}else{
		$registros=$objDB->nf($tabladetalle);
		if ($registros==0){
			//return array(utf8_encode($sErrConsulta.'<input id="paginaf2308" name="paginaf2308" type="hidden" value="'.$pagina.'"/><input id="lppf2308" name="lppf2308" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
<td><b>'.$ETI['cara08consec'].'</b></td>
<td><b>'.$ETI['cara08activa'].'</b></td>
<td><b>'.$ETI['cara08titulo'].'</b></td>
<td colspan="2"><b>'.$ETI['msg_usos'].'</b></td>
<td colspan="2" align="right">
'.html_paginador('paginaf2308', $registros, $lineastabla, $pagina, 'paginarf2308()').'
'.html_lpp('lppf2308', $lineastabla, 'paginarf2308()').'
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
		$et_cara08activa=$ETI['no'];
		if ($filadet['cara08activa']=='S'){$et_cara08activa=$ETI['si'];}
		$sVisor='<a href="javascript:verpregunta('.$filadet['cara08id'].')" class="lnkresalte">'.$ETI['lnk_visor'].'</a>';
		if ($babierta){
			$sLink='<a href="javascript:cargaridf2308('.$filadet['cara08id'].')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$res=$res.'<tr'.$sClass.'>
<td>'.$sPrefijo.$filadet['cara08consec'].$sSufijo.'</td>
<td>'.$sPrefijo.$et_cara08activa.$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['cara08titulo']).$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['cara08usosiniciales'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['cara08usostotales'].$sSufijo.'</td>
<td>'.$sVisor.'</td>
<td>'.$sLink.'</td>
</tr>';
		}
	$res=$res.'</table>';
	$objDB->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
	}
function f2308_HtmlTabla($aParametros){
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
	list($sDetalle, $sDebugTabla)=f2308_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f2308detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f2308_db_CargarPadre($DATA, $objDB, $bDebug=false){
	$sError='';
	$iTipoError=0;
	$sDebug='';
	require './app.php';
	if ($DATA['paso']==1){
		$sSQLcondi='cara08idbloque='.$DATA['cara08idbloque'].' AND cara08consec='.$DATA['cara08consec'].'';
		}else{
		$sSQLcondi='cara08id='.$DATA['cara08id'].'';
		}
	$sSQL='SELECT * FROM cara08pregunta WHERE '.$sSQLcondi;
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$DATA['cara08idbloque']=$fila['cara08idbloque'];
		$DATA['cara08consec']=$fila['cara08consec'];
		$DATA['cara08id']=$fila['cara08id'];
		$DATA['cara08activa']=$fila['cara08activa'];
		$DATA['cara08idgrupo']=$fila['cara08idgrupo'];
		$DATA['cara08titulo']=$fila['cara08titulo'];
		$DATA['cara08cuerpo']=$fila['cara08cuerpo'];
		$DATA['cara08tipopreg']=$fila['cara08tipopreg'];
		$DATA['cara08usosiniciales']=$fila['cara08usosiniciales'];
		$DATA['cara08usostotales']=$fila['cara08usostotales'];
		$DATA['cara08nivelpregunta']=$fila['cara08nivelpregunta'];
		$bcargo=true;
		$DATA['paso']=2;
		$DATA['boculta2308']=0;
		$bLimpiaHijos=true;
		}else{
		$DATA['paso']=0;
		}
	return array($DATA, $sError, $iTipoError, $sDebug);
	}
function f2308_db_GuardarV2($DATA, $objDB, $bDebug=false){
	$iCodModulo=2308;
	$bAudita[2]=true;
	$bAudita[3]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2308='lg/lg_2308_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2308)){$mensajes_2308='lg/lg_2308_es.php';}
	require $mensajes_todas;
	require $mensajes_2308;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	// -- Se inicia validando todas las posibles entradas de usuario.
	/*
	if (isset($DATA['cara08idbloque'])==0){$DATA['cara08idbloque']='';}
	if (isset($DATA['cara08consec'])==0){$DATA['cara08consec']='';}
	if (isset($DATA['cara08id'])==0){$DATA['cara08id']='';}
	if (isset($DATA['cara08activa'])==0){$DATA['cara08activa']='';}
	if (isset($DATA['cara08idgrupo'])==0){$DATA['cara08idgrupo']='';}
	if (isset($DATA['cara08titulo'])==0){$DATA['cara08titulo']='';}
	if (isset($DATA['cara08cuerpo'])==0){$DATA['cara08cuerpo']='';}
	if (isset($DATA['cara08tipopreg'])==0){$DATA['cara08tipopreg']='';}
	if (isset($DATA['cara08usosiniciales'])==0){$DATA['cara08usosiniciales']='';}
	if (isset($DATA['cara08usostotales'])==0){$DATA['cara08usostotales']='';}
	if (isset($DATA['cara08nivelpregunta'])==0){$DATA['cara08nivelpregunta']='';}
	*/
	$DATA['cara08idbloque']=numeros_validar($DATA['cara08idbloque']);
	$DATA['cara08consec']=numeros_validar($DATA['cara08consec']);
	$DATA['cara08activa']=htmlspecialchars(trim($DATA['cara08activa']));
	$DATA['cara08idgrupo']=numeros_validar($DATA['cara08idgrupo']);
	$DATA['cara08titulo']=htmlspecialchars(trim($DATA['cara08titulo']));
	$DATA['cara08cuerpo']=trim($DATA['cara08cuerpo']);
	$DATA['cara08tipopreg']=numeros_validar($DATA['cara08tipopreg']);
	$DATA['cara08usosiniciales']=numeros_validar($DATA['cara08usosiniciales']);
	$DATA['cara08usostotales']=numeros_validar($DATA['cara08usostotales']);
	$DATA['cara08nivelpregunta']=numeros_validar($DATA['cara08nivelpregunta']);
	// -- Se inicializan las variables que puedan pasar vacias {Especialmente números}.
	//if ($DATA['cara08idgrupo']==''){$DATA['cara08idgrupo']=0;}
	//if ($DATA['cara08tipopreg']==''){$DATA['cara08tipopreg']=0;}
	if ($DATA['cara08usosiniciales']==''){$DATA['cara08usosiniciales']=0;}
	if ($DATA['cara08usostotales']==''){$DATA['cara08usostotales']=0;}
	// -- Seccion para validar los posibles causales de error.
	$sSepara=', ';
	if (true){
		if ($DATA['cara08nivelpregunta']==''){$sError=$ERR['cara08nivelpregunta'].$sSepara.$sError;}
		//if ($DATA['cara08usostotales']==''){$sError=$ERR['cara08usostotales'].$sSepara.$sError;}
		//if ($DATA['cara08usosiniciales']==''){$sError=$ERR['cara08usosiniciales'].$sSepara.$sError;}
		if ($DATA['cara08tipopreg']==''){$sError=$ERR['cara08tipopreg'].$sSepara.$sError;}
		//if ($DATA['cara08cuerpo']==''){$sError=$ERR['cara08cuerpo'].$sSepara.$sError;}
		if ($DATA['cara08titulo']==''){$sError=$ERR['cara08titulo'].$sSepara.$sError;}
		if ($DATA['cara08idgrupo']==''){$sError=$ERR['cara08idgrupo'].$sSepara.$sError;}
		if ($DATA['cara08activa']==''){$sError=$ERR['cara08activa'].$sSepara.$sError;}
		//Fin de las valiaciones NO LLAVE.
		}
	//Valiaciones de campos obligatorios en todo guardar.
	if ($DATA['cara08idbloque']==''){$sError=$ERR['cara08idbloque'];}
	// -- Se verifican los valores de campos de otras tablas.
	if ($sError==''){
		if ($DATA['paso']==10){
			if ($DATA['cara08consec']==''){
				$DATA['cara08consec']=tabla_consecutivo('cara08pregunta', 'cara08consec', 'cara08idbloque='.$DATA['cara08idbloque'].'', $objDB);
				if ($DATA['cara08consec']==-1){$sError=$objDB->serror;}
				}else{
				if (!seg_revisa_permiso($iCodModulo, 8, $objDB)){
					$sError=$ERR['8'];
					$DATA['cara08consec']='';
					}
				}
			if ($sError==''){
				$sSQL='SELECT cara08idbloque FROM cara08pregunta WHERE cara08idbloque='.$DATA['cara08idbloque'].' AND cara08consec='.$DATA['cara08consec'].'';
				$result=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($result)!=0){
					$sError=$ERR['existe'];
					}else{
					if (!seg_revisa_permiso($iCodModulo, 2, $objDB)){$sError=$ERR['2'];}
					}
				}
			}else{
			if (!seg_revisa_permiso($iCodModulo, 3, $objDB)){$sError=$ERR['3'];}
			}
		}
	if ($sError==''){
		if ($DATA['paso']==10){
			//Preparar el Id, Si no lo hay se quita la comprobación.
			$DATA['cara08id']=tabla_consecutivo('cara08pregunta','cara08id', '', $objDB);
			if ($DATA['cara08id']==-1){$sError=$objDB->serror;}
			}
		}
	if ($sError==''){
		$DATA['cara08usostotales']=0;
		if (get_magic_quotes_gpc()==1){$DATA['cara08cuerpo']=stripslashes($DATA['cara08cuerpo']);}
		//Si el campo cara08cuerpo permite html quite la linea htmlspecialchars para el campo y habilite la siguiente linea:
		$cara08cuerpo=addslashes($DATA['cara08cuerpo']);
		$cara08cuerpo=str_replace('"', '\"', $DATA['cara08cuerpo']);
		$bpasa=false;
		if ($DATA['paso']==10){
			$sCampos2308='cara08idbloque, cara08consec, cara08id, cara08activa, cara08idgrupo, cara08titulo, cara08cuerpo, cara08tipopreg, cara08usosiniciales, cara08usostotales, 
cara08nivelpregunta';
			$sValores2308=''.$DATA['cara08idbloque'].', '.$DATA['cara08consec'].', '.$DATA['cara08id'].', "'.$DATA['cara08activa'].'", '.$DATA['cara08idgrupo'].', "'.$DATA['cara08titulo'].'", "'.$cara08cuerpo.'", '.$DATA['cara08tipopreg'].', '.$DATA['cara08usosiniciales'].', '.$DATA['cara08usostotales'].', 
'.$DATA['cara08nivelpregunta'].'';
			if ($APP->utf8==1){
				$sSQL='INSERT INTO cara08pregunta ('.$sCampos2308.') VALUES ('.utf8_encode($sValores2308).');';
				$sdetalle=$sCampos2308.'['.utf8_encode($sValores2308).']';
				}else{
				$sSQL='INSERT INTO cara08pregunta ('.$sCampos2308.') VALUES ('.$sValores2308.');';
				$sdetalle=$sCampos2308.'['.$sValores2308.']';
				}
			$idaccion=2;
			$bpasa=true;
			}else{
			$scampo[1]='cara08activa';
			$scampo[2]='cara08idgrupo';
			$scampo[3]='cara08titulo';
			$scampo[4]='cara08cuerpo';
			$scampo[5]='cara08tipopreg';
			$scampo[6]='cara08usosiniciales';
			$scampo[7]='cara08nivelpregunta';
			$sdato[1]=$DATA['cara08activa'];
			$sdato[2]=$DATA['cara08idgrupo'];
			$sdato[3]=$DATA['cara08titulo'];
			$sdato[4]=$cara08cuerpo;
			$sdato[5]=$DATA['cara08tipopreg'];
			$sdato[6]=$DATA['cara08usosiniciales'];
			$sdato[7]=$DATA['cara08nivelpregunta'];
			$numcmod=7;
			$sWhere='cara08id='.$DATA['cara08id'].'';
			$sSQL='SELECT * FROM cara08pregunta WHERE '.$sWhere;
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
					$sSQL='UPDATE cara08pregunta SET '.utf8_encode($sdatos).' WHERE '.$sWhere.';';
					}else{
					$sdetalle=$sdatos.'['.$sWhere.']';
					$sSQL='UPDATE cara08pregunta SET '.$sdatos.' WHERE '.$sWhere.';';
					}
				$idaccion=3;
				}
			}
		if ($bpasa){
			$result=$objDB->ejecutasql($sSQL);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' [2308] ..<!-- '.$sSQL.' -->';
				if ($idaccion==2){$DATA['cara08id']='';}
				$DATA['paso']=$DATA['paso']-10;
				}else{
				if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Guardar 2308 '.$sSQL.'<br>';}
				if ($bAudita[$idaccion]){
					list($res, $sDebugA)=seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], $idaccion, $DATA['cara08id'], $sdetalle, $objDB, $bDebug);
					$sDebug=$sDebug.$sDebugA;
					}
				$DATA['paso']=2;
				}
			}else{
			$DATA['paso']=2;
			}
		}else{
		$DATA['paso']=$DATA['paso']-10;
		}
	//if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' InfoDepura<br>';}
	return array($DATA, $sError, $iTipoError, $sDebug);
	}
function f2308_db_Eliminar($cara08id, $objDB, $bDebug=false){
	$iCodModulo=2308;
	$bAudita[4]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2308='lg/lg_2308_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2308)){$mensajes_2308='lg/lg_2308_es.php';}
	require $mensajes_todas;
	require $mensajes_2308;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	$cara08id=numeros_validar($cara08id);
	// Traer los datos para hacer las validaciones.
	if ($sError==''){
		$sSQL='SELECT * FROM cara08pregunta WHERE cara08id='.$cara08id.'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$filabase=$objDB->sf($tabla);
			}else{
			$sError='No se encuentra el registro solicitado {Ref: '.$cara08id.'}';
			}
		}
	if ($sError==''){
		$sSQL='SELECT cara09idpregunta FROM cara09pregrpta WHERE cara09idpregunta='.$filabase['cara08id'].'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$sError='Existen Respuestas creados, no es posible eliminar';
			}
		}
	if ($sError==''){
		if (!seg_revisa_permiso($iCodModulo, 4, $objDB)){
			$sError=$ERR['4'];
			}
		}
	if ($sError==''){
		$sSQL='SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=2308';
		$tablaor=$objDB->ejecutasql($sSQL);
		while ($filaor=$objDB->sf($tablaor)){
			$sSQL='SELECT '.$filaor['campoid'].' FROM '.$filaor['tabla'].' WHERE '.$filaor['camporev'].'='.$_REQUEST['cara08id'].' LIMIT 0, 1';
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
		//$sSQL='DELETE FROM cara09pregrpta WHERE cara09idpregunta='.$filabase['cara08id'].'';
		//$tabla=$objDB->ejecutasql($sSQL);
		$sWhere='cara08id='.$cara08id.'';
		//$sWhere='cara08consec='.$filabase['cara08consec'].' AND cara08idbloque='.$filabase['cara08idbloque'].'';
		$sSQL='DELETE FROM cara08pregunta WHERE '.$sWhere.';';
		$result=$objDB->ejecutasql($sSQL);
		if ($result==false){
			$sError=$ERR['falla_eliminar'].' .. <!-- '.$sSQL.' -->';
			}else{
			if ($bAudita[4]){seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $cara08id, $sWhere, $objDB);}
			}
		}
	return array($sError, $iTipoError, $sDebug);
	}
function f2308_TituloBusqueda(){
	return 'Busqueda de Preguntas Competencias digitales';
	}
function f2308_ParametrosBusqueda(){
	$sParams='<label class="Label90">Nombre</label><label><input id="b2308nombre" name="b2308nombre" type="text" value="" onchange="paginarbusqueda()" /></label>';
	return $sParams;
	}
function f2308_JavaScriptBusqueda($iModuloBusca){
	$sRes='var sCampo=window.document.frmedita.scampobusca.value;
var aParametros=new Array();
aParametros[100]=sCampo;
aParametros[101]=window.document.frmedita.paginabusqueda.value;
aParametros[102]=window.document.frmedita.lppfbusqueda.value;
aParametros[103]=window.document.frmedita.b2308nombre.value;
xajax_f'.$iModuloBusca.'_HtmlBusqueda(aParametros);';
	return $sRes;
	}
function f2308_TablaDetalleBusquedas($aParametros, $objDB){
	$res='';
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2308='lg/lg_2308_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2308)){$mensajes_2308='lg/lg_2308_es.php';}
	require $mensajes_todas;
	require $mensajes_2308;
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	if (isset($aParametros[101])==0){$aParametros[101]=1;}
	if (isset($aParametros[102])==0){$aParametros[102]=20;}
	if (isset($aParametros[103])==0){$aParametros[103]='';}
	//$aParametros[103]=numeros_validar($aParametros[103]);
	$pagina=$aParametros[101];
	$lineastabla=$aParametros[102];
	$babierta=true;
	//$sSQL='SELECT Campo FROM Tabla WHERE Id='.$sValorId;
	//$tabla=$objDB->ejecutasql($sSQL);
	//if ($objDB->nf($tabla)>0){
		//$fila=$objDB->sf($tabla);
		//if ($fila['Campo']!='S'){$babierta=true;}
		//}
	$sLeyenda='';
	if (false){
		$sLeyenda='<div class="salto1px"></div>
<div class="GrupoCamposAyuda">
<b>Importante:</b> Mensaje al usuario
<div class="salto1px"></div>
</div>';
		}
	$sSQLadd='';
	$sSQLadd1='';
	//if ($aParametros[103]!=''){$sSQLadd1=$sSQLadd1.'TB.campo2 LIKE "%'.$aParametros[103].'%" AND ';}
	//if ($aParametros[103]!=''){$sSQLadd=$sSQLadd.' AND TB.campo2 LIKE "%'.$aParametros[103].'%"';}
	/*
	if ($aParametros[104]!=''){
		$sBase=trim(strtoupper($aParametros[104]));
		$aNoms=explode(' ', $sBase);
		for ($k=1;$k<=count($aNoms);$k++){
			$sCadena=$aNoms[$k-1];
			if ($sCadena!=''){
				$sSQLadd=$sSQLadd.' AND T6.sys11razonsocial LIKE "%'.$sCadena.'%"';
				//$sSQLadd1=$sSQLadd1.'T1.sys11razonsocial LIKE "%'.$sCadena.'%" AND ';
				}
			}
		}
	*/
	$sTitulos='Bloque, Consec, Id, Activa, Grupo, Titulo, Cuerpo, Tipopreg, Usosiniciales, Usostotales, Nivelpregunta';
	$sSQL='SELECT TB.cara08idbloque, TB.cara08consec, TB.cara08id, TB.cara08activa, T5.cara06nombre, TB.cara08titulo, TB.cara08cuerpo, TB.cara08tipopreg, TB.cara08usosiniciales, TB.cara08usostotales, TB.cara08nivelpregunta, TB.cara08idgrupo 
FROM cara08pregunta AS TB, cara06grupopreg AS T5 
WHERE '.$sSQLadd1.' TB.cara08idgrupo=T5.cara06id '.$sSQLadd.'
ORDER BY TB.cara08idbloque, TB.cara08consec';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_busqueda" name="consulta_busqueda" type="hidden" value="'.$sSQLlista.'"/>
<input id="titulos_busqueda" name="titulos_busqueda" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objDB->ejecutasql($sSQL);
	if ($tabladetalle==false){
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		//$sLeyenda=$sSQL;
		}else{
		$registros=$objDB->nf($tabladetalle);
		if ($registros==0){
			//return array(utf8_encode($sErrConsulta.'<input id="paginaf2308" name="paginaf2308" type="hidden" value="'.$pagina.'"/><input id="lppf2308" name="lppf2308" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
<td><b>'.$ETI['cara08idbloque'].'</b></td>
<td><b>'.$ETI['cara08consec'].'</b></td>
<td><b>'.$ETI['cara08activa'].'</b></td>
<td><b>'.$ETI['cara08idgrupo'].'</b></td>
<td><b>'.$ETI['cara08titulo'].'</b></td>
<td><b>'.$ETI['cara08cuerpo'].'</b></td>
<td><b>'.$ETI['cara08tipopreg'].'</b></td>
<td><b>'.$ETI['cara08usosiniciales'].'</b></td>
<td><b>'.$ETI['cara08usostotales'].'</b></td>
<td align="right">
'.html_paginador('paginabusqueda', $registros, $lineastabla, $pagina, 'paginarbusqueda()').'
'.html_lpp('lppfbusqueda', $lineastabla, 'paginarbusqueda()').'
</td>
</tr>';
	$tlinea=1;
	while($filadet=$objDB->sf($tabladetalle)){
		$sPrefijo='<a href="javascript:Devuelve(\''.$filadet['cara08id'].'\');">';
		$sSufijo='</a>';
		$tlinea++;
		$et_cara08activa=$ETI['no'];
		if ($filadet['cara08activa']=='S'){$et_cara08activa=$ETI['si'];}
		$res=$res.'<tr onmouseover="cambia_color_over(this);" onmouseout="cambia_color_out(this);">
<td>'.$sPrefijo.$filadet['cara08idbloque'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['cara08consec'].$sSufijo.'</td>
<td>'.$sPrefijo.$et_cara08activa.$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['cara06nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['cara08titulo']).$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['cara08cuerpo'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['cara08tipopreg'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['cara08usosiniciales'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['cara08usostotales'].$sSufijo.'</td>
<td></td>
</tr>';
		}
	$res=$res.'</table>';
	$objDB->liberar($tabladetalle);
	return utf8_encode($res);
	}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------
?>