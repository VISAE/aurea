<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2018 - 2019 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.22.3 lunes, 27 de agosto de 2018
--- Modelo Versión 2.22.6d lunes, 21 de enero de 2019
--- Modelo Versión 2.24.1 viernes, 28 de febrero de 2020
--- 2300 cara00config
*/
/** Archivo lib2307.php.
* Libreria 2307 cara00panel.
* @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
* @date viernes, 28 de febrero de 2020
*/
function f2307_HTMLComboV2_cara00zona($objDB, $objCombos, $valor){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('cara00zona', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	//$objCombos->iAncho=450;
	$objCombos->sAccion='carga_combo_cara00idconsejero()';
	$sSQL='SELECT unad23id AS id, unad23nombre AS nombre FROM unad23zona';
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f2307_HTMLComboV2_cara00idconsejero($objDB, $objCombos, $valor, $vrcara00zona){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('cara00idconsejero', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	//$objCombos->iAncho=450;
	$objCombos->sAccion='paginarf2307();';
	$sCondi='';
	if ((int)$vrcara00zona!=0){
		if ($vrcara00zona==-1){
			$sCondi='TB.cara01idzona=0 AND ';
			}else{
			$objCombos->addItem('-1', '{Todos}');
			$sCondi='TB.cara01idzona='.$vrcara00zona.' AND ';
			}
		}
	$sSQL='SELECT TB.cara13idconsejero AS id, CONCAT(T11.unad11doc, " ", T11.unad11razonsocial) AS nombre 
FROM cara13consejeros AS TB, unad11terceros AS T11 
WHERE '.$sCondi.' TB.cara13idconsejero=T11.unad11id
GROUP BY TB.cara13idconsejero, CONCAT(T11.unad11doc, " ", T11.unad11razonsocial)
ORDER BY T11.unad11razonsocial';
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f2307_Combocara00idconsejero($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$bDebug=false;
	$objCombos=new clsHtmlCombos('');
	$html_cara00idconsejero=f2307_HTMLComboV2_cara00idconsejero($objDB, $objCombos, '', $aParametros[0]);
	list($sDetalle, $sDebugTabla)=f2307_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_cara00idconsejero', 'innerHTML', $html_cara00idconsejero);
	$objResponse->assign('div_f2307detalle', 'innerHTML', $sDetalle);
	$objResponse->call('$("#cara00idconsejero").chosen()');
	return $objResponse;
	}
function f2300_ExisteDato($datos){
	if(!is_array($datos)){$datos=json_decode(str_replace('\"','"',$datos),true);}
	$_SESSION['u_ultimominuto']=iminutoavance();
	$bHayLlave=true;
	$cara00id=numeros_validar($datos[1]);
	if ($cara00id==''){$bHayLlave=false;}
	if ($bHayLlave){
		require './app.php';
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		$sSQL='SELECT cara00id FROM cara00config WHERE cara00id='.$cara00id.'';
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
function f2300_Busquedas($aParametros){
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2300='lg/lg_2300_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2300)){$mensajes_2300='lg/lg_2300_es.php';}
	require $mensajes_todas;
	require $mensajes_2300;
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
	$sTitulo='<h2>'.$ETI['titulo_2300'].' - '.$sTitulo.'</h2>';
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97titulo', 'innerHTML', $sTitulo);
	$objResponse->assign('div_97params', 'innerHTML', $sParams);
	$objResponse->assign('div_97tabla', 'innerHTML', $sTabla);
	$objResponse->setFunction('paginarbusqueda','',$sJavaBusqueda);
	$objResponse->call('expandesector(97)');
	return $objResponse;
	}
function f2300_HtmlBusqueda($aParametros){
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
function f2300_TablaDetalleV2($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2300='lg/lg_2300_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2300)){$mensajes_2300='lg/lg_2300_es.php';}
	require $mensajes_todas;
	require $mensajes_2300;
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	if (isset($aParametros[101])==0){$aParametros[101]=1;}
	if (isset($aParametros[102])==0){$aParametros[102]=20;}
	//if (isset($aParametros[103])==0){$aParametros[103]='';}
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
	$sTitulos='Id, Perfilconsejero';
	$sSQL='SELECT TB.cara00id, T2.unad05nombre, TB.cara00idperfilconsejero 
FROM cara00config AS TB, unad05perfiles AS T2 
WHERE '.$sSQLadd1.' TB.cara00idperfilconsejero=T2.unad05id '.$sSQLadd.'
ORDER BY TB.cara00id';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_2300" name="consulta_2300" type="hidden" value="'.$sSQLlista.'"/>
<input id="titulos_2300" name="titulos_2300" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objDB->ejecutasql($sSQL);
	if ($tabladetalle==false){
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 2300: '.$sSQL.'<br>';}
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		//$sLeyenda=$sSQL;
		}else{
		$registros=$objDB->nf($tabladetalle);
		if ($registros==0){
			//return array(cadena_codificar($sErrConsulta.'<input id="paginaf2300" name="paginaf2300" type="hidden" value="'.$pagina.'"/><input id="lppf2300" name="lppf2300" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
<td><b>'.$ETI['cara00id'].'</b></td>
<td><b>'.$ETI['cara00idperfilconsejero'].'</b></td>
<td align="right">
'.html_paginador('paginaf2300', $registros, $lineastabla, $pagina, 'paginarf2300()').'
'.html_lpp('lppf2300', $lineastabla, 'paginarf2300()').'
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
		if ($babierta){
			$sLink='<a href="javascript:cargadato('."'".$filadet['cara00id']."'".')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$res=$res.'<tr'.$sClass.'>
<td>'.$sPrefijo.$filadet['cara00id'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['unad05nombre']).$sSufijo.'</td>
<td>'.$sLink.'</td>
</tr>';
		}
	$res=$res.'</table>';
	$objDB->liberar($tabladetalle);
	return array(cadena_codificar($res), $sDebug);
	}
function f2300_HtmlTabla($aParametros){
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
	list($sDetalle, $sDebugTabla)=f2300_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f2300detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f2300_db_CargarPadre($DATA, $objDB, $bDebug=false){
	$sError='';
	$iTipoError=0;
	$sDebug='';
	require './app.php';
	$sSQL='SELECT * FROM cara00config WHERE cara00id='.$DATA['cara00id'].'';
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$DATA['cara00id']=$fila['cara00id'];
		$DATA['cara00idperfilconsejero']=$fila['cara00idperfilconsejero'];
		$DATA['cara00idperfillider']=$fila['cara00idperfillider'];
		$bcargo=true;
		$DATA['paso']=2;
		$DATA['boculta2300']=0;
		$bLimpiaHijos=true;
		}else{
		$DATA['paso']=0;
		}
	return array($DATA, $sError, $iTipoError, $sDebug);
	}
function f2300_db_GuardarV2($DATA, $objDB, $bDebug=false){
	$iCodModulo=2300;
	$bAudita[2]=true;
	$bAudita[3]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2300='lg/lg_2300_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2300)){$mensajes_2300='lg/lg_2300_es.php';}
	require $mensajes_todas;
	require $mensajes_2300;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	// -- Se inicia validando todas las posibles entradas de usuario.
	/*
	if (isset($DATA['cara00id'])==0){$DATA['cara00id']='';}
	if (isset($DATA['cara00idperfilconsejero'])==0){$DATA['cara00idperfilconsejero']='';}
	if (isset($DATA['cara00idperfillider'])==0){$DATA['cara00idperfillider']='';}
	*/
	$DATA['cara00id']=numeros_validar($DATA['cara00id']);
	$DATA['cara00idperfilconsejero']=numeros_validar($DATA['cara00idperfilconsejero']);
	$DATA['cara00idperfillider']=numeros_validar($DATA['cara00idperfillider']);
	// -- Se inicializan las variables que puedan pasar vacias {Especialmente números}.
	//if ($DATA['cara00idperfilconsejero']==''){$DATA['cara00idperfilconsejero']=0;}
	//if ($DATA['cara00idperfillider']==''){$DATA['cara00idperfillider']=0;}
	// -- Seccion para validar los posibles causales de error.
	$sSepara=', ';
	if (true){
		if ($DATA['cara00idperfillider']==''){$sError=$ERR['cara00idperfillider'].$sSepara.$sError;}
		if ($DATA['cara00idperfilconsejero']==''){$sError=$ERR['cara00idperfilconsejero'].$sSepara.$sError;}
		//Fin de las valiaciones NO LLAVE.
		}
	//Valiaciones de campos obligatorios en todo guardar.
	if ($DATA['cara00id']==''){$sError=$ERR['cara00id'];}
	// -- Se verifican los valores de campos de otras tablas.
	if ($sError==''){
		if ($DATA['paso']==10){
			$sSQL='SELECT cara00id FROM cara00config WHERE cara00id='.$DATA['cara00id'].'';
			$result=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($result)!=0){
				$sError=$ERR['existe'];
				}else{
				if (!seg_revisa_permiso($iCodModulo, 2, $objDB)){$sError=$ERR['2'];}
				}
			}else{
			if (!seg_revisa_permiso($iCodModulo, 3, $objDB)){$sError=$ERR['3'];}
			}
		}
	if ($sError==''){
		if ($DATA['paso']==10){
			//Preparar el Id, Si no lo hay se quita la comprobación.
			}
		}
	if ($sError==''){
		$bpasa=false;
		if ($DATA['paso']==10){
			$sCampos2300='cara00id, cara00idperfilconsejero, cara00idperfillider';
			$sValores2300=''.$DATA['cara00id'].', '.$DATA['cara00idperfilconsejero'].', '.$DATA['cara00idperfillider'].'';
			if ($APP->utf8==1){
				$sSQL='INSERT INTO cara00config ('.$sCampos2300.') VALUES ('.cadena_codificar($sValores2300).');';
				$sdetalle=$sCampos2300.'['.cadena_codificar($sValores2300).']';
				}else{
				$sSQL='INSERT INTO cara00config ('.$sCampos2300.') VALUES ('.$sValores2300.');';
				$sdetalle=$sCampos2300.'['.$sValores2300.']';
				}
			$idaccion=2;
			$bpasa=true;
			}else{
			$scampo[1]='cara00idperfilconsejero';
			$scampo[2]='cara00idperfillider';
			$sdato[1]=$DATA['cara00idperfilconsejero'];
			$sdato[2]=$DATA['cara00idperfillider'];
			$numcmod=2;
			$sWhere='cara00id='.$DATA['cara00id'].'';
			$sSQL='SELECT * FROM cara00config WHERE '.$sWhere;
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
					$sdetalle=cadena_codificar($sdatos).'['.$sWhere.']';
					$sSQL='UPDATE cara00config SET '.cadena_codificar($sdatos).' WHERE '.$sWhere.';';
					}else{
					$sdetalle=$sdatos.'['.$sWhere.']';
					$sSQL='UPDATE cara00config SET '.$sdatos.' WHERE '.$sWhere.';';
					}
				$idaccion=3;
				}
			}
		if ($bpasa){
			$result=$objDB->ejecutasql($sSQL);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' [2300] ..<!-- '.$sSQL.' -->';
				$DATA['paso']=$DATA['paso']-10;
				}else{
				if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Guardar 2300 '.$sSQL.'<br>';}
				if ($bAudita[$idaccion]){seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], $idaccion, 0, $sdetalle, $objDB);}
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
function f2300_db_Eliminar($nada, $objDB, $bDebug=false){
	$iCodModulo=2300;
	$bAudita[4]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2300='lg/lg_2300_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2300)){$mensajes_2300='lg/lg_2300_es.php';}
	require $mensajes_todas;
	require $mensajes_2300;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	// Traer los datos para hacer las validaciones.
	if ($sError==''){
		$sSQL='SELECT * FROM cara00config WHERE ='.$nada.'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$filabase=$objDB->sf($tabla);
			}else{
			$sError='No se encuentra el registro solicitado {Ref: '.$nada.'}';
			}
		}
	if ($sError==''){
		if (!seg_revisa_permiso($iCodModulo, 4, $objDB)){
			$sError=$ERR['4'];
			}
		}
/*
	if ($sError==''){
		$sSQL='SELECT * FROM tablaexterna WHERE idexterno='.$_REQUEST['CampoRevisa'].' LIMIT 0, 1';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$sError=$ERR['p1'];//Incluya la explicacion al error en el archivo de idioma
			}
		}
*/
	if ($sError==''){
		$sWhere='cara00id='.$filabase['cara00id'].'';
		$sSQL='DELETE FROM cara00config WHERE '.$sWhere.';';
		$result=$objDB->ejecutasql($sSQL);
		if ($result==false){
			$sError=$ERR['falla_eliminar'].' .. <!-- '.$sSQL.' -->';
			}else{
			if ($bAudita[4]){seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, 0, $sWhere, $objDB);}
			}
		}
	return array($sError, $iTipoError, $sDebug);
	}
function f2300_TituloBusqueda(){
	return 'Busqueda de Opciones del sistema';
	}
function f2300_ParametrosBusqueda(){
	$sParams='<label class="Label90">Nombre</label><label><input id="b2300nombre" name="b2300nombre" type="text" value="" onchange="paginarbusqueda()" /></label>';
	return $sParams;
	}
function f2300_JavaScriptBusqueda($iModuloBusca){
	$sRes='var sCampo=window.document.frmedita.scampobusca.value;
var params=new Array();
params[100]=sCampo;
params[101]=window.document.frmedita.paginabusqueda.value;
params[102]=window.document.frmedita.lppfbusqueda.value;
params[103]=window.document.frmedita.b2300nombre.value;
xajax_f'.$iModuloBusca.'_HtmlBusqueda(params);';
	return $sRes;
	}
function f2300_TablaDetalleBusquedas($aParametros, $objDB){
	$res='';
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2300='lg/lg_2300_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2300)){$mensajes_2300='lg/lg_2300_es.php';}
	require $mensajes_todas;
	require $mensajes_2300;
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
				$sSQLadd=$sSQLadd.' AND T6.unad11razonsocial LIKE "%'.$sCadena.'%"';
				//$sSQLadd1=$sSQLadd1.'T1.unad11razonsocial LIKE "%'.$sCadena.'%" AND ';
				}
			}
		}
	*/
	$sTitulos='Id, Perfilconsejero';
	$sSQL='SELECT TB.cara00id, T2.unad05nombre, TB.cara00idperfilconsejero 
FROM cara00config AS TB, unad05perfiles AS T2 
WHERE '.$sSQLadd1.' TB.cara00idperfilconsejero=T2.unad05id '.$sSQLadd.'
ORDER BY TB.cara00id';
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
			//return array(cadena_codificar($sErrConsulta.'<input id="paginaf2300" name="paginaf2300" type="hidden" value="'.$pagina.'"/><input id="lppf2300" name="lppf2300" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
<td><b>'.$ETI['cara00id'].'</b></td>
<td><b>'.$ETI['cara00idperfilconsejero'].'</b></td>
<td align="right">
'.html_paginador('paginabusqueda', $registros, $lineastabla, $pagina, 'paginarbusqueda()').'
'.html_lpp('lppfbusqueda', $lineastabla, 'paginarbusqueda()').'
</td>
</tr>';
	$tlinea=1;
	while($filadet=$objDB->sf($tabladetalle)){
		$sPrefijo='<a href="javascript:Devuelve(\''.$filadet[''].'\');">';
		$sSufijo='</a>';
		$tlinea++;
		$res=$res.'<tr onmouseover="cambia_color_over(this);" onmouseout="cambia_color_out(this);">
<td>'.$sPrefijo.$filadet['cara00id'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['unad05nombre']).$sSufijo.'</td>
<td></td>
</tr>';
		}
	$res=$res.'</table>';
	$objDB->liberar($tabladetalle);
	return cadena_codificar($res);
	}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------
function f2300_ProcesarEdades($objDB, $bDebug=false){
	$sError='';
	$sDebug='';
	$bContinua=false;
	$iProcesados=0;
	require './app.php';
	$bHayDBRyC=false;
	list($objDBRyC, $sDebug)=TraerDBRyCV2();
	if ($objDBRyC->Conectar()){
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Conectado con la db de Registro y control<br>';}
		$bHayDBRyC=true;
		}else{
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' <b>Procesar Edades</b> Falla al conectar con RCONT: '.$objDBRyC->serror.'<br>';}
		//$sError=' Error al intentar conectar con la base de datos de RyC <b>'.$objDBRyC->serror.'</b>';
		}
	if ($sError==''){
		//Primero quitamos las edades que no tienen sentido.
		$sSQL='UPDATE cara01encuesta SET cara01agnos=0 WHERE cara01agnos>80';
		$tabla=$objDB->ejecutasql($sSQL);
		//ahora las reconstruimos.
		$sSQL='SELECT TB.cara01id, TB.cara01idtercero, T11.unad11doc, TB.cara01agnos, T11.unad11fechanace, TB.cara01fechaencuesta 
		FROM cara01encuesta AS TB, unad11terceros AS T11 
		WHERE TB.cara01agnos=0 AND TB.cara01fechaencuesta>0 AND TB.cara01idtercero=T11.unad11id';
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' <b>Procesar Edades</b> Datos a revisar '.$sSQL.'<br>';}
		$tabla=$objDB->ejecutasql($sSQL);
		while($fila=$objDB->sf($tabla)){
			$cara01agnos=0;
			$sFechaEncuesta=fecha_desdenumero($fila['cara01fechaencuesta']);
			if (fecha_esvalida($fila['unad11fechanace'])){
				list($cara01agnos, $iMedida)=fecha_edad($fila['unad11fechanace'], $sFechaEncuesta);
				if ($iMedida!=1){$cara01agnos=0;}
				}else{
				if ($bHayDBRyC){
					//Traer el dato desde registro y control.
					$sSQL='SELECT YEAR(fechan) AS Agno, MONTH(fechan) AS Mes, DAY(fechan) AS Dia FROM datos_est WHERE id='.$fila['unad11doc'].'';
					$tablaRyC=$objDBRyC->ejecutasql($sSQL);
					if ($objDBRyC->nf($tablaRyC)>0){
						$filaRyC=$objDBRyC->sf($tablaRyC);
						$sFechaNace=fecha_armar($filaRyC['Dia'], $filaRyC['Mes'], $filaRyC['Agno']);
						list($cara01agnos, $iMedida)=fecha_edad($sFechaNace, $sFechaEncuesta);
						if ($iMedida!=1){$cara01agnos=0;}
						//Actualizamos los datos en aurea
						$sSQL='UPDATE unad11terceros SET unad11fechanace="'.$sFechaNace.'" WHERE unad11id='.$fila['cara01idtercero'].'';
						if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Actualizando fecha de nacimiento: '.$sSQL.'<br>';}
						$result=$objDB->ejecutasql($sSQL);
						}
					}
				}
			if ($cara01agnos!=0){
				//Actualizamos la edad...
				$sSQL='UPDATE cara01encuesta SET cara01agnos='.$cara01agnos.' WHERE cara01id='.$fila['cara01id'].'';
				if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Actualizando encuesta: '.$sSQL.'<br>';}
				$result=$objDB->ejecutasql($sSQL);
				$iProcesados++;
				}
			}
		}
	if ($bHayDBRyC){
		$objDBRyC->CerrarConexion();
		}
	return array($sError, $sDebug, $bContinua, $iProcesados);
	}
function f2307_TablaDetalleV2($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2300='lg/lg_2300_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2300)){$mensajes_2300='lg/lg_2300_es.php';}
	require $mensajes_todas;
	require $mensajes_2300;
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	if (isset($aParametros[100])==0){$aParametros[100]=$_SESSION['unad_id_tercero'];}
	if (isset($aParametros[101])==0){$aParametros[101]=1;}
	if (isset($aParametros[102])==0){$aParametros[102]=20;}
	if (isset($aParametros[103])==0){$aParametros[103]='';}
	if (isset($aParametros[104])==0){$aParametros[104]='';}
	if (isset($aParametros[105])==0){$aParametros[105]='';}
	//$aParametros[103]=numeros_validar($aParametros[103]);
	$sDebug='';
	$idTercero=$aParametros[100];
	$pagina=$aParametros[101];
	$lineastabla=$aParametros[102];
	$idConsejero=numeros_validar($aParametros[103]);
	$idZona=numeros_validar($aParametros[104]);
	$idPeriodo=numeros_validar($aParametros[105]);
	$babierta=true;
	//$sSQL='SELECT Campo FROM Tabla WHERE Id='.$sValorId;
	//$tabla=$objDB->ejecutasql($sSQL);
	//if ($objDB->nf($tabla)>0){
		//$fila=$objDB->sf($tabla);
		//if ($fila['Campo']!='S'){$babierta=true;}
		//}
	$sLeyenda='';
	if ((int)$idConsejero==0){$sLeyenda='No ha seleccionado un consejero a consultar';}
	if ($sLeyenda!=''){
		$sLeyenda='<div class="salto1px"></div>
<div class="GrupoCamposAyuda">
'.$sLeyenda.'
<div class="salto1px"></div>
</div>';
		return array(cadena_codificar($sLeyenda.'<input id="paginaf2307" name="paginaf2307" type="hidden" value="'.$pagina.'"/><input id="lppf2307" name="lppf2307" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
		die();
		}
	$sSQLadd='';
	$sSQLadd1='';
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
	$sIdsCursosCatedra='-99';
	$sSQL='SELECT unad40id FROM unad40curso WHERE unad40catedraunadista=1';
	$tabla1=$objDB->ejecutasql($sSQL);
	while($fila1=$objDB->sf($tabla1)){
		$sIdsCursosCatedra=$sIdsCursosCatedra.','.$fila1['unad40id'];
		}
	$sBotones='<input id="paginaf2300" name="paginaf2300" type="hidden" value="'.$pagina.'"/><input id="lppf2300" name="lppf2300" type="hidden" value="'.$lineastabla.'"/>';
	$sTitulos='Id, Perfilconsejero';
	$sCondi='';
	if ($idZona!=''){
		if ($idZona==-1){
			$sCondi='TB.cara01idzona=0 AND ';
			}else{
			$sCondi='TB.cara01idzona='.$idZona.' AND ';
			}
		}
	$bXPeriodo=true;
	if ($idPeriodo!=''){
		$bXPeriodo=false;
		$sCondi='TB.cara13peraca='.$idPeriodo.' AND '.$sCondi;
		}
	$bMostrarConsejero=false;
	if ($idConsejero=='-1'){
		$bMostrarConsejero=$bXPeriodo;
		}else{
		$sCondi='TB.cara13idconsejero='.$idConsejero.' AND '.$sCondi;
		}
	$sSQL='SELECT TB.cara13idconsejero, TB.cara13peraca, T2.exte02nombre, TB.cara01cargaasignada, TB.cara01cargafinal 
	FROM cara13consejeros AS TB, exte02per_aca AS T2 
	WHERE '.$sCondi.'TB.cara13peraca=T2.exte02id 
	ORDER BY TB.cara13idconsejero, TB.cara13peraca DESC';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_2300" name="consulta_2300" type="hidden" value="'.$sSQLlista.'"/>
	<input id="titulos_2300" name="titulos_2300" type="hidden" value="'.$sTitulos.'"/>';
	if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 2300: '.$sSQL.'<br>';}
	$tabladetalle=$objDB->ejecutasql($sSQL);
	if ($tabladetalle==false){
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		//$sLeyenda=$sSQL;
		}else{
		$registros=$objDB->nf($tabladetalle);
		if ($registros==0){
			$sTabla='<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">
			<tr class="fondoazul">
			<td align="center"><b>'.'No se registra asignaci&oacute;n de estudiantes para acompa&ntilde;amiento'.'</b></td>
			</tr>
			</table>';
			return array(cadena_codificar($sErrConsulta.$sBotones).$sTabla, $sDebug);
			}
		/*
		if ((($registros-1)/$lineastabla)<($pagina-1)){$pagina=(int)(($registros-1)/$lineastabla)+1;}
		if ($registros>$lineastabla){
			$rbase=($pagina-1)*$lineastabla;
			$limite=' LIMIT '.$rbase.', '.$lineastabla;
			$tabladetalle=$objDB->ejecutasql($sSQL.$limite);
			}
		*/
		}
	if ($bXPeriodo){
		$sTitulo1='Periodo';
		}else{
		$sTitulo1='Consejero';
		}
	$res=$sErrConsulta.$sLeyenda.$sBotones.'<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">
<tr class="fondoazul">
<td colspan="3" align="center"><b>'.'Estudiantes asignados'.'</b></td>
<td colspan="3" align="center"><b>'.'Acompa&ntilde;amientos'.'</b> [Abierto -] <b>Cerrados</b> / Estudiantes</td>
</tr>
<tr class="fondoazul">
<td><b>'.$sTitulo1.'</b></td>
<td align="center" title="Estudiantes que fueron asignados para hacerles acompa&ntilde;miento."><b>'.'Aplicados'.'</b></td>
<td align="center" title="Estudiantes que le han sido asignados en cursos de catedra unadista."><b>'.'Est. Catedra'.'</b></td>
<td align="center"><b>'.'Momento Inicial'.'</b></td>
<td align="center"><b>'.'Momento Intermedio'.'</b></td>
<td align="center"><b>'.'Momento Final'.'</b></td>
</tr>';
	$tlinea=1;
	$idMuestra=-99;
	$idPeriodo=-99;
	$idContenedor=0;
	while($filadet=$objDB->sf($tabladetalle)){
		if ($bMostrarConsejero){
			if ($idMuestra!=$filadet['cara13idconsejero']){
				$idMuestra=$filadet['cara13idconsejero'];
				$sNomConsejero='{'.$idMuestra.'}';
				$sSQL='SELECT unad11razonsocial FROM unad11terceros WHERE unad11id='.$idMuestra.'';
				$tabla11=$objDB->ejecutasql($sSQL);
				if ($objDB->nf($tabla11)>0){
					$fila11=$objDB->sf($tabla11);
					$sNomConsejero='Consejero: <b>'.cadena_notildes($fila11['unad11razonsocial']).'</b>';
					}
				$res=$res.'<tr class="fondoazul">
<td colspan="6" align="center">'.$sNomConsejero.'</td>
</tr>';
				}
			}
		$idConsejero=$filadet['cara13idconsejero'];
		if ($idPeriodo!=$filadet['cara13peraca']){
			$idPeriodo=$filadet['cara13peraca'];
			/*
			if ($idPeriodo>760){
				$idContenedor=f146_Contenedor($idPeriodo, $objDB);
				}else{
				$idContenedor=0;
				}
			*/
			}
		$sPrefijo='';
		$sSufijo='';
		$sClass='';
		$sLink='';
		$sRevisa='';
		if (false){
			$sPrefijo='<b>';
			$sSufijo='</b>';
			}
		if(($tlinea%2)==0){$sClass=' class="resaltetabla"';}
		$tlinea++;
		$sIds='-99';
		$iEstudiantesCatedra=0;
		$sInfoCatedra='{No Registra}';
		//if ($idContenedor>0){}
		$sSQL='SELECT core20idcurso, core20numestudiantes, core20numestaplicados 
FROM core20asignacion
WHERE core20idtutor='.$idConsejero.' AND core20idperaca='.$idPeriodo.' AND core20idcurso IN ('.$sIdsCursosCatedra.')';
		$tabla1=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla1)>0){
			$sInfoCatedra='';
			while($fila1=$objDB->sf($tabla1)){
				$sInfoCatedra=$sInfoCatedra.' '.$fila1['core20idcurso'].' [ '.$fila1['core20numestaplicados'].' / '.$fila1['core20numestudiantes'].' ]';
				}
			}
		$iM1='';
		$iM2='';
		$iM3='';
		$aCont=array();
		for ($k=11;$k<=33;$k++){
			$aCont[$k]=0;
			}
		$sDetalle='';
		$sSQL='SELECT TB.cara01id FROM cara01encuesta AS TB WHERE TB.cara01idconsejero='.$idConsejero.' AND (TB.cara01idperiodoacompana='.$filadet['cara13peraca'].' OR TB.cara01idperaca='.$filadet['cara13peraca'].')';
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta ENCUESTAS: '.$sSQL.'<br>';}
		$tabla1=$objDB->ejecutasql($sSQL);
		//$iAplicados=$objDB->nf($tabla1);
		while($fila1=$objDB->sf($tabla1)){
			$sIds=$sIds.','.$fila1['cara01id'];
			}
		if ($sIds!='-99'){
			//Numero de estudiantes por momento
			$sSQL='SELECT cara23idtipo, COUNT(DISTINCT(cara23idtercero)) AS Estudiantes
FROM cara23acompanamento
WHERE cara23idencuesta IN ('.$sIds.')
GROUP BY cara23idtipo';
if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta ESTUDIANTES: '.$sSQL.'<br>';}
			//$sRevisa=$sSQL;
			$tabla1=$objDB->ejecutasql($sSQL);
			while($fila1=$objDB->sf($tabla1)){
				switch($fila1['cara23idtipo']){
					case 1:
					$aCont[11]=$fila1['Estudiantes'];
					break;
					case 2:
					$aCont[21]=$fila1['Estudiantes'];
					break;
					case 3:
					$aCont[31]=$fila1['Estudiantes'];
					break;
					}
				}
			//Numero de eventos
			$sSQL='SELECT cara23idtipo, cara23estado, COUNT(1) AS Eventos
FROM cara23acompanamento
WHERE cara23idencuesta IN ('.$sIds.')
GROUP BY cara23idtipo, cara23estado';
if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta EVENTOS: '.$sSQL.'<br>';}
			//$iM3=$sSQL;
			$tabla1=$objDB->ejecutasql($sSQL);
			while($fila1=$objDB->sf($tabla1)){
				switch($fila1['cara23idtipo']){
					case 1:
					if ($fila1['cara23estado']==7){
						$aCont[12]=$fila1['Eventos'];
						}else{
						$aCont[13]=$fila1['Eventos'];
						}
					break;
					case 2:
					if ($fila1['cara23estado']==7){
						$aCont[22]=$fila1['Eventos'];
						}else{
						$aCont[23]=$fila1['Eventos'];
						}
					break;
					case 3:
					if ($fila1['cara23estado']==7){
						$aCont[32]=$fila1['Eventos'];
						}else{
						$aCont[33]=$fila1['Eventos'];
						}
					break;
					}
				}
			if ($aCont[11]>0){
				$iM1='<b>'.$aCont[12].'</b> / '.$aCont[11];
				if ($aCont[13]>0){
					$iM1=$aCont[13].' - '.$iM1;
					}
				}
			if ($aCont[21]>0){
				$iM2='<b>'.$aCont[22].'</b> / '.$aCont[21];
				if ($aCont[23]>0){
					$iM2=$aCont[23].' - '.$iM2;
					}
				}
			if ($aCont[31]>0){
				$iM3='<b>'.$aCont[32].'</b> / '.$aCont[31];
				if ($aCont[33]>0){
					$iM3=$aCont[33].' - '.$iM3;
					}
				}
			}
		//cara01cargaasignada, TB.cara01cargafinal
		if ($bXPeriodo){
			$sDatoCol1=cadena_notildes($filadet['exte02nombre']);
			}else{
			$idMuestra=$filadet['cara13idconsejero'];
			$sDatoCol1='{'.$idMuestra.'}';
			$sSQL='SELECT unad11razonsocial FROM unad11terceros WHERE unad11id='.$idMuestra.'';
			$tabla11=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla11)>0){
				$fila11=$objDB->sf($tabla11);
				$sDatoCol1=''.cadena_notildes($fila11['unad11razonsocial']).'';
				}
			}
		$res=$res.'<tr'.$sClass.'>
<td>'.$sPrefijo.$sDatoCol1.$sSufijo.'</td>
<td align="center">'.$sPrefijo.$filadet['cara01cargafinal'].' / '.$filadet['cara01cargaasignada'].$sSufijo.'</td>
<td align="center">'.$sPrefijo.$sInfoCatedra.$sSufijo.'</td>
<td align="center">'.$sPrefijo.$iM1.$sSufijo.'</td>
<td align="center">'.$sPrefijo.$iM2.$sSufijo.'</td>
<td align="center">'.$sPrefijo.$iM3.$sSufijo.'</td>
</tr>';
//<tr><td colspan="6">'.$sRevisa.'</td></tr>
		}
	$res=$res.'</table>';
	$objDB->liberar($tabladetalle);
	return array(cadena_codificar($res), $sDebug);
	}
function f2307_HtmlTabla($aParametros){
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
	list($sDetalle, $sDebugTabla)=f2307_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f2307detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
?>