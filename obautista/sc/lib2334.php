<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2019 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.24.0 Thursday, November 21, 2019
--- 2334 core16actamatricula
*/
/** Archivo lib2334.php.
* Libreria 2334 core16actamatricula.
* @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
* @date Thursday, November 21, 2019
*/
function f2334_HTMLComboV2_core16peraca($objDB, $objCombos, $valor){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('core16peraca', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	$objCombos->sAccion='RevisaLlave();';
	$sSQL='SELECT exte02id AS id, exte02nombre AS nombre FROM exte02per_aca';
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f2334_HTMLComboV2_core16idprograma($objDB, $objCombos, $valor){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('core16idprograma', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	$objCombos->sAccion='RevisaLlave();';
	$sSQL='SELECT core09id AS id, core09nombre AS nombre FROM core09programa';
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f2334_ExisteDato($datos){
	if(!is_array($datos)){$datos=json_decode(str_replace('\"','"',$datos),true);}
	$_SESSION['u_ultimominuto']=iminutoavance();
	$bHayLlave=true;
	$core16peraca=numeros_validar($datos[1]);
	if ($core16peraca==''){$bHayLlave=false;}
	$core16tercero=numeros_validar($datos[2]);
	if ($core16tercero==''){$bHayLlave=false;}
	$core16idprograma=numeros_validar($datos[3]);
	if ($core16idprograma==''){$bHayLlave=false;}
	if ($bHayLlave){
		require './app.php';
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		$sSQL='SELECT core16idprograma FROM core16actamatricula WHERE core16peraca='.$core16peraca.' AND core16tercero='.$core16tercero.' AND core16idprograma='.$core16idprograma.'';
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
function f2334_Busquedas($aParametros){
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2334='lg/lg_2334_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2334)){$mensajes_2334='lg/lg_2334_es.php';}
	require $mensajes_todas;
	require $mensajes_2334;
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
		case 'core16tercero':
		require $APP->rutacomun.'lib111.php';
		$sTabla=f111_TablaDetalleBusquedas($aParametrosB, $objDB);
		$sTitulo='Busqueda de terceros';
		$sParams=f111_ParametrosBusqueda();
		$sJavaBusqueda=f111_JavaScriptBusqueda(2334);
		break;
		case 'core16idconsejero':
		require $APP->rutacomun.'lib111.php';
		$sTabla=f111_TablaDetalleBusquedas($aParametrosB, $objDB);
		$sTitulo='Busqueda de terceros';
		$sParams=f111_ParametrosBusqueda();
		$sJavaBusqueda=f111_JavaScriptBusqueda(2334);
		break;
		}
	$sTitulo='<h2>'.$ETI['titulo_2334'].' - '.$sTitulo.'</h2>';
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97titulo', 'innerHTML', $sTitulo);
	$objResponse->assign('div_97params', 'innerHTML', $sParams);
	$objResponse->assign('div_97tabla', 'innerHTML', $sTabla);
	$objResponse->setFunction('paginarbusqueda','',$sJavaBusqueda);
	$objResponse->call('expandesector(97)');
	return $objResponse;
	}
function f2334_HtmlBusqueda($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$sError='';
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$sDetalle='';
	switch($aParametros[100]){
		case 'core16tercero':
		require $APP->rutacomun.'lib111.php';
		$sDetalle=f111_TablaDetalleBusquedas($aParametros, $objDB);
		break;
		case 'core16idconsejero':
		require $APP->rutacomun.'lib111.php';
		$sDetalle=f111_TablaDetalleBusquedas($aParametros, $objDB);
		break;
		}
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97tabla', 'innerHTML', $sDetalle);
	return $objResponse;
	}
function f2334_TablaDetalleV2($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2216=$APP->rutacomun.'lg/lg_2216_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2216)){$mensajes_2216=$APP->rutacomun.'lg/lg_2216_es.php';}
	require $mensajes_todas;
	require $mensajes_2216;
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	if (isset($aParametros[101])==0){$aParametros[101]=1;}
	if (isset($aParametros[102])==0){$aParametros[102]=20;}
	if (isset($aParametros[103])==0){$aParametros[103]='';}
	if (isset($aParametros[104])==0){$aParametros[104]='';}
	if (isset($aParametros[105])==0){$aParametros[105]='';}
	if (isset($aParametros[106])==0){$aParametros[106]='';}
	if (isset($aParametros[107])==0){$aParametros[107]='';}
	if (isset($aParametros[108])==0){$aParametros[108]='';}
	if (isset($aParametros[109])==0){$aParametros[109]='';}
	if (isset($aParametros[110])==0){$aParametros[110]='';}
	if (isset($aParametros[111])==0){$aParametros[111]='';}
	if (isset($aParametros[112])==0){$aParametros[112]='';}
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
	if ($sLeyenda!=''){
		$sLeyenda='<div class="salto1px"></div>
<div class="GrupoCamposAyuda">
'.$sLeyenda.'
<div class="salto1px"></div>
</div>';
		return array($sLeyenda.'<input id="paginaf2334" name="paginaf2334" type="hidden" value="'.$pagina.'"/><input id="lppf2334" name="lppf2334" type="hidden" value="'.$lineastabla.'"/>', $sDebug);
		die();
		}
	$sSQLadd='';
	$sSQLadd1='';
	//if ((int)$aParametros[103]!=-1){$sSQLadd=$sSQLadd.' AND TB.campo='.$aParametros[103];}
	if ($aParametros[106]!=''){$sSQLadd1=$sSQLadd1.'TB.core16peraca='.$aParametros[106].' AND ';}

	if ($aParametros[112]!=''){
		$sSQLadd1=$sSQLadd1.'TB.core16idprograma='.$aParametros[112].' AND ';
		}else{
		if ($aParametros[111]!=''){$sSQLadd1=$sSQLadd1.'TB.core16idescuela='.$aParametros[111].' AND ';}
		}
	if ($aParametros[110]!=''){
		$sSQLadd1=$sSQLadd1.'TB.core16idcead='.$aParametros[110].' AND ';
		}else{
		if ($aParametros[109]!=''){$sSQLadd1=$sSQLadd1.'TB.core16idzona='.$aParametros[109].' AND ';}
		}

	if ($aParametros[107]!=''){$sSQLadd1=$sSQLadd1.'TB.core16parametros LIKE "%'.$aParametros[107].'%" AND ';}
	switch($aParametros[104]){
		case 1: // Estudiantes nuevos
		$sSQLadd1=$sSQLadd1.'TB.core16nuevo=1 AND ';
		break;
		case 2: // Estudiantes antiguso
		$sSQLadd1=$sSQLadd1.'TB.core16nuevo=0 AND ';
		break;
		case 3: // Nuevos sin consejero.
		$sSQLadd1=$sSQLadd1.'TB.core16nuevo=1 AND TB.core16idconsejero=0 AND ';
		break;
		case 4: // Nuevos sin encuesta.
		$sSQLadd1=$sSQLadd1.'TB.core16nuevo=1 AND TB.core16idcaracterizacion=0 AND ';
		break;
		}
	if ($aParametros[105]!=''){$sSQLadd=$sSQLadd.' AND T2.unad11doc LIKE "%'.$aParametros[105].'%"';}
	if ($aParametros[103]!=''){
		$sBase=trim(strtoupper($aParametros[103]));
		$aNoms=explode(' ', $sBase);
		for ($k=1;$k<=count($aNoms);$k++){
			$sCadena=$aNoms[$k-1];
			if ($sCadena!=''){
				$sSQLadd=$sSQLadd.' AND T2.unad11razonsocial LIKE "%'.$sCadena.'%"';
				}
			}
		}
	$sTablaConvenio='';
	if ($aParametros[108]!=''){
		$sTablaConvenio=', core51convenioest AS T51';
		$sSQLadd1=$sSQLadd1.'TB.core16tercero=T51.core51idtercero AND T51.core51idconvenio='.$aParametros[108].' AND T51.core51activo="S" AND ';
		}
	$sTitulos='Documento, Razon social, Programa, Nuevo, N cursos,Fecha Ultimo Acceso Campus';
	$sSQL='SELECT T2.unad11doc AS C2_doc, T2.unad11razonsocial AS C2_nombre, T9.core09nombre, TB.core16nuevo, 
TB.core16id, TB.core16idcead, TB.core16parametros, TB.core16fecharecibido, TB.core16minrecibido, TB.core16numaprobados, TB.core16promedio, TB.core16origen, TB.core16proccarac, TB.core16procagenda, TB.core16peraca, TB.core16tercero, T2.unad11tipodoc AS C2_td, TB.core16idprograma, TB.core16idescuela, TB.core16idzona, TB.core16idconsejero, TB.core16idcaracterizacion 
FROM core16actamatricula AS TB'.$sTablaConvenio.', unad11terceros AS T2, core09programa AS T9 
WHERE '.$sSQLadd1.' TB.core16tercero=T2.unad11id AND TB.core16idprograma=T9.core09id'.$sSQLadd.'
ORDER BY TB.core16peraca DESC, TB.core16idcead, TB.core16idprograma, TB.core16tercero';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_2334" name="consulta_2334" type="hidden" value="'.$sSQLlista.'"/>
<input id="titulos_2334" name="titulos_2334" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objDB->ejecutasql($sSQL);
	if ($tabladetalle==false){
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 2334: '.$sSQL.'<br>';}
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		//$sLeyenda=$sSQL;
		}else{
		$registros=$objDB->nf($tabladetalle);
		if ($registros==0){
			//return array(utf8_encode($sErrConsulta.'<input id="paginaf2334" name="paginaf2334" type="hidden" value="'.$pagina.'"/><input id="lppf2334" name="lppf2334" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
<td colspan="2"><b>'.$ETI['core16tercero'].'</b></td>
<td><b>'.$ETI['core16nuevo'].'</b></td>
<td><b>'.$ETI['core16idcaracterizacion'].'</b></td>
<td><b>'.$ETI['core16idconsejero'].'</b></td>
<td align="right" colspan="2">
'.html_paginador('paginaf2334', $registros, $lineastabla, $pagina, 'paginarf2334()').'
'.html_lpp('lppf2334', $lineastabla, 'paginarf2334()').'
</td>
</tr>';
	$tlinea=1;
	$idPeraca=-1;
	$idPrograma=-1;
	$idCead=-1;
	while($filadet=$objDB->sf($tabladetalle)){
		if ($idPeraca!=$filadet['core16peraca']){
			$idPeraca=$filadet['core16peraca'];
			$idPrograma=-1;
			$idCead=-1;
			$sNomPeraca='{'.$filadet['core16peraca'].'}';
			$sSQL='SELECT exte02nombre FROM exte02per_aca WHERE exte02id='.$filadet['core16peraca'].'';
			$tabla=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla)>0){
				$fila=$objDB->sf($tabla);
				$sNomPeraca=cadena_notildes($fila['exte02nombre']);
				}
			$res=$res.'<tr class="fondoazul">
<td colspan="7">'.$ETI['core16peraca'].' <b>'.$sNomPeraca.'</b></td>
</tr>';
			}
		if ($idPrograma!=$filadet['core16idprograma']){
			$idPrograma=$filadet['core16idprograma'];
			$idCead=-1;
			$sNomPrograma='{'.$filadet['core16idprograma'].'}';
			$sSQL='SELECT core09nombre FROM core09programa WHERE core09id='.$filadet['core16idprograma'].'';
			$tabla=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla)>0){
				$fila=$objDB->sf($tabla);
				$sNomPrograma=cadena_notildes($fila['core09nombre']);
				}
			$res=$res.'<tr class="fondoazul">
<td colspan="7">'.$ETI['core16idprograma'].' <b>'.$sNomPrograma.'</b></td>
</tr>';
			}
		if ($idCead!=$filadet['core16idcead']){
			$idCead=$filadet['core16idcead'];
			$sNomCEAD='{'.$filadet['core16idcead'].'}';
			$sSQL='SELECT unad24nombre FROM unad24sede WHERE unad24id='.$filadet['core16idcead'].'';
			$tabla=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla)>0){
				$fila=$objDB->sf($tabla);
				$sNomCEAD=cadena_notildes($fila['unad24nombre']);
				}
			$res=$res.'<tr class="fondoazul">
<td colspan="7">'.$ETI['core16idcead'].' <b>'.$sNomCEAD.'</b></td>
</tr>';
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
		$et_core16nuevo=$ETI['no'];
		if ($filadet['core16nuevo']==1){
			$et_core16nuevo=$ETI['si'];
			}
		$et_core16idcaracterizacion='[No Registra]';
		if ($filadet['core16idcaracterizacion']!=0){
			$et_core16idcaracterizacion='{'.$filadet['core16idcaracterizacion'].'}';
			$sSQL='SELECT cara01idperaca, cara01fechaencuesta, cara01completa FROM cara01encuesta WHERE cara01id='.$filadet['core16idcaracterizacion'].'';
			$tabla=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla)>0){
				$fila=$objDB->sf($tabla);
				$sAdd='Cerrada';
				if ($fila['cara01completa']!='S'){
					$sAdd='Abierta';
					$sPrefijo='<span class="rojo">';
					$sSufijo='</span>';
					}
				$et_core16idcaracterizacion=$fila['cara01idperaca'].' - '.fecha_desdenumero($fila['cara01fechaencuesta']).' - '.$sAdd;
				}
			}
		$et_core16idconsejero='['.$ETI['msg_ninguno'].']';
		if ($filadet['core16idconsejero']!=0){
			$et_core16idconsejero='{'.$filadet['core16idconsejero'].'}';
			$sSQL='SELECT unad11razonsocial FROM unad11terceros WHERE unad11id='.$filadet['core16idconsejero'].'';
			$tabla=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla)>0){
				$fila=$objDB->sf($tabla);
				$et_core16idconsejero=cadena_notildes($fila['unad11razonsocial']);
				}
			}
		if ($babierta){
			$sLink='<a href="javascript:cargaridf2334('.$filadet['core16id'].')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$res=$res.'<tr'.$sClass.'>
<td>'.$sPrefijo.$filadet['C2_td'].' '.$filadet['C2_doc'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['C2_nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.$et_core16nuevo.$sSufijo.'</td>
<td>'.$sPrefijo.$et_core16idcaracterizacion.$sSufijo.'</td>
<td colspan="2">'.$sPrefijo.$et_core16idconsejero.$sSufijo.'</td>
<td>'.$sLink.'</td>
</tr>';
		}
	$res=$res.'</table>';
	$objDB->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
	}
function f2334_HtmlTabla($aParametros){
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
	list($sDetalle, $sDebugTabla)=f2334_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f2334detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f2334_db_CargarPadre($DATA, $objDB, $bDebug=false){
	$sError='';
	$iTipoError=0;
	$sDebug='';
	require './app.php';
	$DATA['core16tercero_td']=$APP->tipo_doc;
	$DATA['core16tercero_doc']='';
	$DATA['core16idconsejero_td']=$APP->tipo_doc;
	$DATA['core16idconsejero_doc']='';
	if ($DATA['paso']==1){
		$sSQLcondi='core16peraca='.$DATA['core16peraca'].' AND core16tercero="'.$DATA['core16tercero'].'" AND core16idprograma='.$DATA['core16idprograma'].'';
		}else{
		$sSQLcondi='core16id='.$DATA['core16id'].'';
		}
	$sSQL='SELECT * FROM core16actamatricula WHERE '.$sSQLcondi;
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$DATA['core16peraca']=$fila['core16peraca'];
		$DATA['core16tercero']=$fila['core16tercero'];
		$DATA['core16idprograma']=$fila['core16idprograma'];
		$DATA['core16id']=$fila['core16id'];
		$DATA['core16idcead']=$fila['core16idcead'];
		$DATA['core16idescuela']=$fila['core16idescuela'];
		$DATA['core16idzona']=$fila['core16idzona'];
		$DATA['core16fecharecibido']=$fila['core16fecharecibido'];
		$DATA['core16nuevo']=$fila['core16nuevo'];
		$DATA['core16idconsejero']=$fila['core16idconsejero'];
		$DATA['core16estado']=$fila['core16estado'];
		$DATA['core16idcaracterizacion']=$fila['core16idcaracterizacion'];
		$bcargo=true;
		$DATA['paso']=2;
		$DATA['boculta2334']=0;
		$bLimpiaHijos=true;
		}else{
		$DATA['paso']=0;
		}
	return array($DATA, $sError, $iTipoError, $sDebug);
	}
function f2334_db_GuardarV2($DATA, $objDB, $bDebug=false){
	$iCodModulo=2334;
	$bAudita[2]=true;
	$bAudita[3]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2216=$APP->rutacomun.'lg/lg_2216_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2216)){$mensajes_2216=$APP->rutacomun.'lg/lg_2216_es.php';}
	require $mensajes_todas;
	require $mensajes_2216;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	// -- Se inicia validando todas las posibles entradas de usuario.
	/*
	if (isset($DATA['core16peraca'])==0){$DATA['core16peraca']='';}
	if (isset($DATA['core16tercero'])==0){$DATA['core16tercero']='';}
	if (isset($DATA['core16idprograma'])==0){$DATA['core16idprograma']='';}
	if (isset($DATA['core16id'])==0){$DATA['core16id']='';}
	if (isset($DATA['core16fecharecibido'])==0){$DATA['core16fecharecibido']='';}
	if (isset($DATA['core16idconsejero'])==0){$DATA['core16idconsejero']='';}
	*/
	$DATA['core16peraca']=numeros_validar($DATA['core16peraca']);
	$DATA['core16idprograma']=numeros_validar($DATA['core16idprograma']);
	// -- Se inicializan las variables que puedan pasar vacias {Especialmente números}.
	if ($DATA['core16idcead']==''){$DATA['core16idcead']=0;}
	if ($DATA['core16idescuela']==''){$DATA['core16idescuela']=0;}
	if ($DATA['core16idzona']==''){$DATA['core16idzona']=0;}
	if ($DATA['core16nuevo']==''){$DATA['core16nuevo']=0;}
	if ($DATA['core16estado']==''){$DATA['core16estado']=0;}
	if ($DATA['core16idcaracterizacion']==''){$DATA['core16idcaracterizacion']=0;}
	// -- Seccion para validar los posibles causales de error.
	$sSepara=', ';
	if (true){
		if ($DATA['core16idconsejero']==0){$sError=$ERR['core16idconsejero'].$sSepara.$sError;}
		if ($DATA['core16fecharecibido']==0){
			//$DATA['core16fecharecibido']=fecha_DiaMod();
			$sError=$ERR['core16fecharecibido'].$sSepara.$sError;
			}
		//Fin de las valiaciones NO LLAVE.
		}
	//Valiaciones de campos obligatorios en todo guardar.
	if ($DATA['core16idprograma']==''){$sError=$ERR['core16idprograma'];}
	if ($DATA['core16tercero']==0){$sError=$ERR['core16tercero'];}
	if ($DATA['core16peraca']==''){$sError=$ERR['core16peraca'];}
	// -- Se verifican los valores de campos de otras tablas.
	if ($DATA['core16idconsejero_doc']!=''){
		if ($sError==''){$sError=tabla_terceros_existe($DATA['core16idconsejero_td'], $DATA['core16idconsejero_doc'], $objDB, 'El tercero Consejero ');}
		if ($sError==''){
			list($sError, $sInfo)=tercero_Bloqueado($DATA['core16idconsejero'], $objDB);
			if ($sInfo!=''){$sError=$sError.'<br>'.sInfo;}
			}
		}
	if ($DATA['core16tercero_doc']!=''){
		if ($sError==''){$sError=tabla_terceros_existe($DATA['core16tercero_td'], $DATA['core16tercero_doc'], $objDB, 'El tercero Tercero ');}
		if ($sError==''){
			list($sError, $sInfo)=tercero_Bloqueado($DATA['core16tercero'], $objDB);
			if ($sInfo!=''){$sError=$sError.'<br>'.sInfo;}
			}
		}
	if ($sError==''){
		if ($DATA['paso']==10){
			$sSQL='SELECT 1 FROM core16actamatricula WHERE core16peraca='.$DATA['core16peraca'].' AND core16tercero="'.$DATA['core16tercero'].'" AND core16idprograma='.$DATA['core16idprograma'].'';
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
			$DATA['core16id']=tabla_consecutivo('core16actamatricula','core16id', '', $objDB);
			if ($DATA['core16id']==-1){$sError=$objDB->serror;}
			}
		}
	if ($sError==''){
		$bpasa=false;
		if ($DATA['paso']==10){
			}else{
			$scampo[1]='core16idcaracterizacion';
			$scampo[2]='core16idconsejero';
			$sdato[1]=$DATA['core16idcaracterizacion'];
			$sdato[2]=$DATA['core16idconsejero'];
			$numcmod=2;
			$sWhere='core16id='.$DATA['core16id'].'';
			$sSQL='SELECT * FROM core16actamatricula WHERE '.$sWhere;
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
					$sSQL='UPDATE core16actamatricula SET '.utf8_encode($sdatos).' WHERE '.$sWhere.';';
					}else{
					$sdetalle=$sdatos.'['.$sWhere.']';
					$sSQL='UPDATE core16actamatricula SET '.$sdatos.' WHERE '.$sWhere.';';
					}
				$idaccion=3;
				}
			}
		if ($bpasa){
			$result=$objDB->ejecutasql($sSQL);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' [2334] ..<!-- '.$sSQL.' -->';
				if ($idaccion==2){
					$DATA['core16id']='';
					$bQuitarCodigo=true;
					$DATA['paso']=0;
					}else{
					$DATA['paso']=2;
					}
				}else{
				if ($DATA['core16idcaracterizacion']!=0){
					$sSQL='UPDATE cara01encuesta SET cara01idconsejero='.$DATA['core16idconsejero'].', cara01idperiodoacompana='.$DATA['core16peraca'].' WHERE cara01id='.$DATA['core16idcaracterizacion'].'';
					$result=$objDB->ejecutasql($sSQL);
					}
				if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Guardar 2334 '.$sSQL.'<br>';}
				if ($bAudita[$idaccion]){seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], $idaccion, $DATA['core16id'], $sdetalle, $objDB);}
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
function f2334_db_Eliminar($core16id, $objDB, $bDebug=false){
	$sError='';
	$iTipoError=0;
	$sDebug='';
	return array($sError, $iTipoError, $sDebug);
	}
function f2334_TituloBusqueda(){
	return 'Busqueda de Asignacion de consejeros';
	}
function f2334_ParametrosBusqueda(){
	$sParams='<label class="Label90">Nombre</label><label><input id="b2334nombre" name="b2334nombre" type="text" value="" onchange="paginarbusqueda()" /></label>';
	return $sParams;
	}
function f2334_JavaScriptBusqueda($iModuloBusca){
	$sRes='var sCampo=window.document.frmedita.scampobusca.value;
var params=new Array();
params[100]=sCampo;
params[101]=window.document.frmedita.paginabusqueda.value;
params[102]=window.document.frmedita.lppfbusqueda.value;
params[103]=window.document.frmedita.b2334nombre.value;
xajax_f'.$iModuloBusca.'_HtmlBusqueda(params);';
	return $sRes;
	}
function f2334_TablaDetalleBusquedas($aParametros, $objDB){
	$res='';
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2334='lg/lg_2334_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2334)){$mensajes_2334='lg/lg_2334_es.php';}
	require $mensajes_todas;
	require $mensajes_2334;
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
	if ($sLeyenda!=''){
		$sLeyenda='<div class="salto1px"></div>
<div class="GrupoCamposAyuda">
'.$sLeyenda.'
<div class="salto1px"></div>
</div>';
		return array($sLeyenda.'<input id="paginaf2334" name="paginaf2334" type="hidden" value="'.$pagina.'"/><input id="lppf2334" name="lppf2334" type="hidden" value="'.$lineastabla.'"/>', $sDebug);
		die();
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
	$sTitulos='Peraca, Tercero, Programa, Id, Cead, Escuela, Zona, Fecharecibido, Nuevo, Consejero, Estado, Caracterizacion';
	$sSQL='SELECT T1.exte02nombre, T2.unad11razonsocial AS C2_nombre, T3.core09nombre, TB.core16id, T5.unad24nombre, T6.core12nombre, T7.unad23nombre, TB.core16fecharecibido, TB.core16nuevo, T10.unad11razonsocial AS C10_nombre, T11.core30nombre, TB.core16idcaracterizacion, TB.core16peraca, TB.core16tercero, T2.unad11tipodoc AS C2_td, T2.unad11doc AS C2_doc, TB.core16idprograma, TB.core16idcead, TB.core16idescuela, TB.core16idzona, TB.core16idconsejero, T10.unad11tipodoc AS C10_td, T10.unad11doc AS C10_doc, TB.core16estado 
FROM core16actamatricula AS TB, exte02per_aca AS T1, unad11terceros AS T2, core09programa AS T3, unad24sede AS T5, core12escuela AS T6, unad23zona AS T7, unad11terceros AS T10, core30estadomatricula AS T11 
WHERE '.$sSQLadd1.' TB.core16peraca=T1.exte02id AND TB.core16tercero=T2.unad11id AND TB.core16idprograma=T3.core09id AND TB.core16idcead=T5.unad24id AND TB.core16idescuela=T6.core12id AND TB.core16idzona=T7.unad23id AND TB.core16idconsejero=T10.unad11id AND TB.core16estado=T11.core30id '.$sSQLadd.'
ORDER BY TB.core16peraca, TB.core16tercero, TB.core16idprograma';
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
			//return array(utf8_encode($sErrConsulta.'<input id="paginaf2334" name="paginaf2334" type="hidden" value="'.$pagina.'"/><input id="lppf2334" name="lppf2334" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
<td><b>'.$ETI['core16peraca'].'</b></td>
<td colspan="2"><b>'.$ETI['core16tercero'].'</b></td>
<td><b>'.$ETI['core16idprograma'].'</b></td>
<td><b>'.$ETI['core16idcead'].'</b></td>
<td><b>'.$ETI['core16idescuela'].'</b></td>
<td><b>'.$ETI['core16idzona'].'</b></td>
<td><b>'.$ETI['core16fecharecibido'].'</b></td>
<td><b>'.$ETI['core16nuevo'].'</b></td>
<td colspan="2"><b>'.$ETI['core16idconsejero'].'</b></td>
<td><b>'.$ETI['core16estado'].'</b></td>
<td><b>'.$ETI['core16idcaracterizacion'].'</b></td>
<td align="right">
'.html_paginador('paginabusqueda', $registros, $lineastabla, $pagina, 'paginarbusqueda()').'
'.html_lpp('lppfbusqueda', $lineastabla, 'paginarbusqueda()').'
</td>
</tr>';
	$tlinea=1;
	while($filadet=$objDB->sf($tabladetalle)){
		$sPrefijo='<a href="javascript:Devuelve(\''.$filadet['core16id'].'\');">';
		$sSufijo='</a>';
		$tlinea++;
		$et_core16fecharecibido='';
		if ($filadet['core16fecharecibido']!=0){$et_core16fecharecibido=fecha_desdenumero($filadet['core16fecharecibido']);}
		$res=$res.'<tr onmouseover="cambia_color_over(this);" onmouseout="cambia_color_out(this);">
<td>'.$sPrefijo.cadena_notildes($filadet['exte02nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['C2_td'].' '.$filadet['C2_doc'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['C2_nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['core09nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['unad24nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['core12nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['unad23nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.$et_core16fecharecibido.$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['core16nuevo'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['C10_td'].' '.$filadet['C10_doc'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['C10_nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['core30nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['core16idcaracterizacion'].$sSufijo.'</td>
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
function f2334_UbicarCaracterizacion($id16, $objDB, $bDebug=false){
	//Febrero 28 de 2020 - Se agrega la asignación de consejero, el cual se toma de la tabla de matricula.
	$sError='';
	$sDebug='';
	$idConsejero=0;
	$idEncuesta=0;
	$bCambiaConsejero=false;
	$sSQL='SELECT TB.core16tercero, TB.core16idprograma, TB.core16idconsejero, TB.core16peraca, TB.core16idcaracterizacion, T11.unad11idtablero 
FROM core16actamatricula AS TB, unad11terceros AS T11 
WHERE TB.core16id='.$id16.' AND TB.core16tercero=T11.unad11id';
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$idTercero=$fila['core16tercero'];
		$idPrograma=$fila['core16idprograma'];
		$idPeraca=$fila['core16peraca'];
		$idConsejero=$fila['core16idconsejero'];
		$idConsejero2=0;
		$idEncuesta=$fila['core16idcaracterizacion'];
		$iContenedor=$fila['unad11idtablero'];
		if ($idConsejero==0){
			$sSQL='SELECT TB.core04idcurso, TB.core04idtutor 
FROM core04matricula_'.$iContenedor.' AS TB, unad40curso AS T4 
WHERE TB.core04peraca='.$idPeraca.' AND TB.core04tercero='.$idTercero.' 
AND TB.core04idcurso=T4.unad40id AND T4.unad40nombre LIKE "%CATEDRA%"';
			if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consultando el tutor de Catedra: '.$sSQL.'<br>';}
			$tabla4=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla4)>0){
				$fila4=$objDB->sf($tabla4);
				$idConsejero2=$fila4['core04idtutor'];
				}
			}
		if ($idEncuesta==0){}
		if (true){
			//Buscar la caracterizacion.
			$sSQL='SELECT cara01id, cara01idconsejero, cara01idperaca 
FROM cara01encuesta 
WHERE cara01idtercero='.$idTercero.' AND cara01idprograma='.$idPrograma.' 
ORDER BY cara01id DESC';
			if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Verificando encuestas: '.$sSQL.'<br>';}
			$tabla=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla)>0){
				$sAddConsejero='';
				$fila=$objDB->sf($tabla);
				$idEncuesta=$fila['cara01id'];
				if ($idConsejero==0){
					if ($fila['cara01idconsejero']>0){
						$bAsignar=false;
						if ($fila['cara01idperaca']==$idPeraca){$bAsignar=true;}
						if ($bAsignar){
							$idConsejero=$fila['cara01idconsejero'];
							$sAddConsejero=', core16idconsejero='.$idConsejero.'';
							}
						}else{
						if ($idConsejero2!=0){
							$idConsejero=$idConsejero2;
							$sAddConsejero=', core16idconsejero='.$idConsejero.'';
							$sSQL='UPDATE cara01encuesta SET cara01idconsejero='.$idConsejero.', cara01idperiodoacompana='.$idPeraca.' WHERE cara01id='.$fila['cara01id'].'';
							$result=$objDB->ejecutasql($sSQL);
							}
						}
					}else{
					$bAsignar=false;
					if ($fila['cara01idconsejero']==0){$bAsignar=true;}
					if ($bAsignar){
						$sSQL='UPDATE cara01encuesta SET cara01idconsejero='.$idConsejero.', cara01idperiodoacompana='.$idPeraca.' WHERE cara01id='.$fila['cara01id'].'';
						$result=$objDB->ejecutasql($sSQL);
						}
					}
				$sSQL='UPDATE core16actamatricula SET core16idcaracterizacion='.$idEncuesta.$sAddConsejero.'  WHERE core16id='.$id16.'';
				if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Actualizando la matricula: '.$sSQL.'<br>';}
				$result=$objDB->ejecutasql($sSQL);
				}
			}
		}else{
		$sError='No se ha encontrado el registro '.$id16.'';
		}
	return array($idEncuesta, $idConsejero, $sError, $sDebug);
	}
?>