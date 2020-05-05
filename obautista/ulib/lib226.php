<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2020 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.24.1 viernes, 7 de febrero de 2020
--- Modelo Versión 2.24.1 martes, 18 de febrero de 2020
--- 226 unae26unidadesfun
*/
/** Archivo lib226.php.
* Libreria 226 unae26unidadesfun.
* @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
* @date viernes, 7 de febrero de 2020
*/
function f226_ExisteDato($datos){
	if(!is_array($datos)){$datos=json_decode(str_replace('\"','"',$datos),true);}
	$_SESSION['u_ultimominuto']=iminutoavance();
	$bHayLlave=true;
	$unae26consec=numeros_validar($datos[1]);
	if ($unae26consec==''){$bHayLlave=false;}
	$unae26idzona=numeros_validar($datos[2]);
	if ($unae26idzona==''){$bHayLlave=false;}
	if ($bHayLlave){
		require './app.php';
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		$sSQL='SELECT unae26idzona FROM unae26unidadesfun WHERE unae26consec='.$unae26consec.' AND unae26idzona='.$unae26idzona.'';
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
function f226_Busquedas($aParametros){
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_226=$APP->rutacomun.'lg/lg_226_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_226)){$mensajes_226=$APP->rutacomun.'lg/lg_226_es.php';}
	require $mensajes_todas;
	require $mensajes_226;
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
		case 'unae26idresponsable':
		require $APP->rutacomun.'lib111.php';
		$sTabla=f111_TablaDetalleBusquedas($aParametrosB, $objDB);
		$sTitulo='Busqueda de terceros';
		$sParams=f111_ParametrosBusqueda();
		$sJavaBusqueda=f111_JavaScriptBusqueda(226);
		break;
		case 'unae27idresponsable':
		require $APP->rutacomun.'lib111.php';
		$sTabla=f111_TablaDetalleBusquedas($aParametrosB, $objDB);
		$sTitulo='Busqueda de terceros';
		$sParams=f111_ParametrosBusqueda();
		$sJavaBusqueda=f111_JavaScriptBusqueda(226);
		break;
		}
	$sTitulo='<h2>'.$ETI['titulo_226'].' - '.$sTitulo.'</h2>';
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97titulo', 'innerHTML', $sTitulo);
	$objResponse->assign('div_97params', 'innerHTML', $sParams);
	$objResponse->assign('div_97tabla', 'innerHTML', $sTabla);
	$objResponse->setFunction('paginarbusqueda','',$sJavaBusqueda);
	$objResponse->call('expandesector(97)');
	return $objResponse;
	}
function f226_HtmlBusqueda($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$sError='';
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$sDetalle='';
	switch($aParametros[100]){
		case 'unae26idresponsable':
		require $APP->rutacomun.'lib111.php';
		$sDetalle=f111_TablaDetalleBusquedas($aParametros, $objDB);
		break;
		case 'unae27idresponsable':
		require $APP->rutacomun.'lib111.php';
		$sDetalle=f111_TablaDetalleBusquedas($aParametros, $objDB);
		break;
		}
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97tabla', 'innerHTML', $sDetalle);
	return $objResponse;
	}
function f226_TablaDetalleV2($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_226=$APP->rutacomun.'lg/lg_226_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_226)){$mensajes_226=$APP->rutacomun.'lg/lg_226_es.php';}
	require $mensajes_todas;
	require $mensajes_226;
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	if (isset($aParametros[100])==0){$aParametros[100]=$_SESSION['unad_id_tercero'];}
	if (isset($aParametros[101])==0){$aParametros[101]=1;}
	if (isset($aParametros[102])==0){$aParametros[102]=20;}
	//if (isset($aParametros[103])==0){$aParametros[103]='';}
	//if (isset($aParametros[104])==0){$aParametros[104]='';}
	//$aParametros[103]=numeros_validar($aParametros[103]);
	$idTercero=$aParametros[100];
	$sDebug='';
	$pagina=$aParametros[101];
	$lineastabla=$aParametros[102];
	$bAbierta=true;
	//$sSQL='SELECT Campo FROM Tabla WHERE Id='.$sValorId;
	//$tabla=$objDB->ejecutasql($sSQL);
	//if ($objDB->nf($tabla)>0){
		//$fila=$objDB->sf($tabla);
		//if ($fila['Campo']!='S'){$bAbierta=true;}
		//}
	$sLeyenda='';
	if ($sLeyenda!=''){
		$sLeyenda='<div class="salto1px"></div>
<div class="GrupoCamposAyuda">
'.$sLeyenda.'
<div class="salto1px"></div>
</div>';
		return array($sLeyenda.'<input id="paginaf226" name="paginaf226" type="hidden" value="'.$pagina.'"/><input id="lppf226" name="lppf226" type="hidden" value="'.$lineastabla.'"/>', $sDebug);
		die();
		}
/*
	$aEstado=array();
	$sSQL='SELECT id, nombre FROM tabla';
	$tabla=$objDB->ejecutasql($sSQL);
	while($fila=$objDB->sf($tabla)){
		$aEstado[$fila['id']]=cadena_notildes($fila['nombre']);
		}
*/
	$sSQLadd='';
	$sSQLadd1='';
	//if ((int)$aParametros[103]!=-1){$sSQLadd=$sSQLadd.' AND TB.campo='.$aParametros[103];}
	//if ($aParametros[103]!=''){$sSQLadd=$sSQLadd.' AND TB.campo2 LIKE "%'.$aParametros[103].'%"';}
	/*
	if ($aParametros[103]!=''){
		$sBase=trim(strtoupper($aParametros[103]));
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
	$sTitulos='Consec, Zona, Id, Activa, Nombre, Fractal, Unidadpadre, Responsable, Tituloresponsable';
	$sSQL='SELECT TB.unae26consec, TB.unae26idzona, TB.unae26id, TB.unae26activa, TB.unae26nombre, TB.unae26fractal, T8.unad11razonsocial AS C8_nombre, TB.unae26tituloresponsable, TB.unae26nivel, TB.unae26orden, TB.unae26lugar, TB.unae26prefijo, TB.unae26unidadpadre, TB.unae26idresponsable, T8.unad11tipodoc AS C8_td, T8.unad11doc AS C8_doc 
FROM unae26unidadesfun AS TB,  unad11terceros AS T8 
WHERE TB.unae26idzona=0 AND '.$sSQLadd1.' TB.unae26id>0 AND TB.unae26idresponsable=T8.unad11id '.$sSQLadd.'
ORDER BY TB.unae26activa DESC, TB.unae26lugar, TB.unae26orden, TB.unae26nombre';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_226" name="consulta_226" type="hidden" value="'.$sSQLlista.'"/>
<input id="titulos_226" name="titulos_226" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objDB->ejecutasql($sSQL);
	if ($tabladetalle==false){
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 226: '.$sSQL.'<br>';}
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		//$sLeyenda=$sSQL;
		}else{
		$registros=$objDB->nf($tabladetalle);
		if ($registros==0){
			//return array(utf8_encode($sErrConsulta.'<input id="paginaf226" name="paginaf226" type="hidden" value="'.$pagina.'"/><input id="lppf226" name="lppf226" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
<td><b>'.$ETI['unae26consec'].'</b></td>
<td><b>'.$ETI['unae26nombre'].'</b></td>
<td><b>'.$ETI['unae26activa'].'</b></td>
<td><b>'.$ETI['unae26fractal'].'</b></td>
<td><b>'.$ETI['unae26idresponsable'].'</b></td>
<td><b>'.$ETI['unae26orden'].'</b></td>
<td align="right">
'.html_paginador('paginaf226', $registros, $lineastabla, $pagina, 'paginarf226()').'
'.html_lpp('lppf226', $lineastabla, 'paginarf226()').'
</td>
</tr>';
	$tlinea=1;
	while($filadet=$objDB->sf($tabladetalle)){
		$sPrefijo='';
		$sSufijo='';
		$sClass='';
		$sLink='';
		if ($filadet['unae26activa']!='S'){
			$sPrefijo='<span class="rojo">';
			$sSufijo='</span>';
			}
		if(($tlinea%2)==0){$sClass=' class="resaltetabla"';}
		$tlinea++;
		/*
		$et_unae26unidadpadre='Sin padre';
		if ($filadet['unae26unidadpadre']!=0){
			$sSQL1='SELECT unae26nombre FROM unae26unidadesfun WHERE unae26id='.$filadet['unae26unidadpadre'];
			$tablanombre=$objDB->ejecutasql($sSQL1);
			if ($objDB->nf($tablanombre)>0){
				$filanom=$objDB->sf($tablanombre);
				$et_unae26unidadpadre=$filanom['unae26nombre'];
			}
		*/
		$et_unae26activa=$sPrefijo.$ETI['si'].$sSufijo;
		if ($filadet['unae26activa']!='S'){
			$et_unae26activa=$sPrefijo.$ETI['no'].$sSufijo;
			}
		$et_unae26fractal=$sPrefijo.$ETI['no'].$sSufijo;
		if ($filadet['unae26fractal']=='S'){$et_unae26fractal=$sPrefijo.$ETI['si'].$sSufijo;}
		//$sTD='';
		$sDoc='';
		if ($filadet['unae26idresponsable']!=0){
			//$sTD=$filadet['C8_td'].' '.$filadet['C8_doc'];
			$sDoc=cadena_notildes($filadet['C8_nombre']);
			}
		//unae26nivel, TB.unae26orden
		$et_unae26orden=$filadet['unae26nivel'].' - '.$filadet['unae26orden'];
		if ($bAbierta){
			$sLink='<a href="javascript:cargaridf226('.$filadet['unae26id'].')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$res=$res.'<tr'.$sClass.'>
<td>'.$sPrefijo.$filadet['unae26consec'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['unae26prefijo'].' '.cadena_notildes($filadet['unae26nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.$et_unae26activa.$sSufijo.'</td>
<td>'.$sPrefijo.$et_unae26fractal.$sSufijo.'</td>
<td>'.$sPrefijo.$sDoc.$sSufijo.'</td>
<td>'.$sPrefijo.$et_unae26orden.$sSufijo.'</td>
<td>'.$sLink.'</td>
</tr>';
		}
	$res=$res.'</table>';
	$objDB->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
	}
function f226_HtmlTabla($aParametros){
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
	list($sDetalle, $sDebugTabla)=f226_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f226detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f226_db_CargarPadre($DATA, $objDB, $bDebug=false){
	$sError='';
	$iTipoError=0;
	$sDebug='';
	require './app.php';
	$DATA['unae26idresponsable_td']=$APP->tipo_doc;
	$DATA['unae26idresponsable_doc']='';
	if ($DATA['paso']==1){
		$sSQLcondi='unae26consec='.$DATA['unae26consec'].' AND unae26idzona='.$DATA['unae26idzona'].'';
		}else{
		$sSQLcondi='unae26id='.$DATA['unae26id'].'';
		}
	$sSQL='SELECT * FROM unae26unidadesfun WHERE '.$sSQLcondi;
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$DATA['unae26consec']=$fila['unae26consec'];
		$DATA['unae26idzona']=$fila['unae26idzona'];
		$DATA['unae26id']=$fila['unae26id'];
		$DATA['unae26activa']=$fila['unae26activa'];
		$DATA['unae26nombre']=$fila['unae26nombre'];
		$DATA['unae26fractal']=$fila['unae26fractal'];
		$DATA['unae26unidadpadre']=$fila['unae26unidadpadre'];
		$DATA['unae26idresponsable']=$fila['unae26idresponsable'];
		$DATA['unae26tituloresponsable']=$fila['unae26tituloresponsable'];
		$DATA['unae26nivel']=$fila['unae26nivel'];
		$DATA['unae26orden']=$fila['unae26orden'];
		$DATA['unae26lugar']=$fila['unae26lugar'];
		$DATA['unae26prefijo']=$fila['unae26prefijo'];
		$bcargo=true;
		$DATA['paso']=2;
		$DATA['boculta226']=0;
		$bLimpiaHijos=true;
		}else{
		$DATA['paso']=0;
		}
	return array($DATA, $sError, $iTipoError, $sDebug);
	}
function f226_db_GuardarV2($DATA, $objDB, $bDebug=false){
	$iCodModulo=226;
	$bAudita[2]=true;
	$bAudita[3]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_226=$APP->rutacomun.'lg/lg_226_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_226)){$mensajes_226=$APP->rutacomun.'lg/lg_226_es.php';}
	require $mensajes_todas;
	require $mensajes_226;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	// -- Se inicia validando todas las posibles entradas de usuario.
	/*
	if (isset($DATA['unae26consec'])==0){$DATA['unae26consec']='';}
	if (isset($DATA['unae26id'])==0){$DATA['unae26id']='';}
	if (isset($DATA['unae26activa'])==0){$DATA['unae26activa']='';}
	if (isset($DATA['unae26nombre'])==0){$DATA['unae26nombre']='';}
	if (isset($DATA['unae26fractal'])==0){$DATA['unae26fractal']='';}
	if (isset($DATA['unae26unidadpadre'])==0){$DATA['unae26unidadpadre']='';}
	if (isset($DATA['unae26idresponsable'])==0){$DATA['unae26idresponsable']='';}
	if (isset($DATA['unae26tituloresponsable'])==0){$DATA['unae26tituloresponsable']='';}
	if (isset($DATA['unae26orden'])==0){$DATA['unae26orden']='';}
	*/
	$DATA['unae26consec']=numeros_validar($DATA['unae26consec']);
	$DATA['unae26activa']=htmlspecialchars(trim($DATA['unae26activa']));
	$DATA['unae26nombre']=htmlspecialchars(trim($DATA['unae26nombre']));
	$DATA['unae26fractal']=htmlspecialchars(trim($DATA['unae26fractal']));
	$DATA['unae26unidadpadre']=numeros_validar($DATA['unae26unidadpadre']);
	$DATA['unae26tituloresponsable']=htmlspecialchars(trim($DATA['unae26tituloresponsable']));
	$DATA['unae26orden']=numeros_validar($DATA['unae26orden']);
	$DATA['unae26prefijo']=htmlspecialchars(trim($DATA['unae26prefijo']));
	// -- Se inicializan las variables que puedan pasar vacias {Especialmente números}.
	//if ($DATA['unae26unidadpadre']==''){$DATA['unae26unidadpadre']=0;}
	if ($DATA['unae26idzona']==''){$DATA['unae26idzona']=0;}// yo
	// -- Seccion para validar los posibles causales de error.
	$sSepara=', ';
	if (true){
		//if ($DATA['unae26orden']==''){$sError=$ERR['unae26orden'].$sSepara.$sError;}
		//if ($DATA['unae26tituloresponsable']==''){$sError=$ERR['unae26tituloresponsable'].$sSepara.$sError;}
		//if ($DATA['unae26idresponsable']==0){$sError=$ERR['unae26idresponsable'].$sSepara.$sError;}
		if ($DATA['unae26unidadpadre']==''){$sError=$ERR['unae26unidadpadre'].$sSepara.$sError;}
		if ($DATA['unae26fractal']==''){$sError=$ERR['unae26fractal'].$sSepara.$sError;}
		if ($DATA['unae26nombre']==''){$sError=$ERR['unae26nombre'].$sSepara.$sError;}
		if ($DATA['unae26activa']==''){$sError=$ERR['unae26activa'].$sSepara.$sError;}
		//Fin de las valiaciones NO LLAVE.
		}
	//Valiaciones de campos obligatorios en todo guardar.
	// -- Se verifican los valores de campos de otras tablas.
	if ($DATA['unae26idresponsable_doc']!=''){
		if ($sError==''){$sError=tabla_terceros_existe($DATA['unae26idresponsable_td'], $DATA['unae26idresponsable_doc'], $objDB, 'El tercero Responsable ');}
		if ($sError==''){
			list($sError, $sInfo)=tercero_Bloqueado($DATA['unae26idresponsable'], $objDB);
			if ($sInfo!=''){$sError=$sError.'<br>'.sInfo;}
			}
		}
	if ($sError==''){
		if ($DATA['paso']==10){
			if ($DATA['unae26consec']==''){
				$DATA['unae26consec']=tabla_consecutivo('unae26unidadesfun', 'unae26consec', 'unae26idzona='.$DATA['unae26idzona'].'', $objDB);
				if ($DATA['unae26consec']==-1){$sError=$objDB->serror;}
				}else{
				if (!seg_revisa_permiso($iCodModulo, 8, $objDB)){
					$sError=$ERR['8'];
					$DATA['unae26consec']='';
					}
				}
			if ($sError==''){
				$sSQL='SELECT 1 FROM unae26unidadesfun WHERE unae26consec='.$DATA['unae26consec'].' AND unae26idzona='.$DATA['unae26idzona'].'';
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
	$bQuitarCodigo=false;
	if ($sError==''){
		if ($DATA['paso']==10){
			//Preparar el Id, Si no lo hay se quita la comprobación.
			$DATA['unae26id']=tabla_consecutivo('unae26unidadesfun','unae26id', '', $objDB);
			if ($DATA['unae26id']==-1){$sError=$objDB->serror;}
			}
		}
	if ($sError==''){
		if ($DATA['unae26orden']==''){$DATA['unae26orden']=$DATA['unae26consec'];}
		$factor=0;
		$iBase=0;
		if ($DATA['unae26unidadpadre']==0){
			$DATA['unae26nivel']=1;
			$factor=100000000;
			$DATA['unae26prefijo']='';
			}else{
			$iBase=100000000;
			$factor=1000000;
			$iNivel=2;
			$sPrefijo='--';
			$sSQL='SELECT unae26nivel, unae26lugar FROM unae26unidadesfun WHERE unae26id='.$DATA['unae26unidadpadre'].'';
			$result=$objDB->ejecutasql($sSQL);
			if ($objDB->nf($result)!=0){
				$fila=$objDB->sf($result);
				$iNivel=$fila['unae26nivel']+1;
				$iBase=$fila['unae26lugar'];
				}
			$iLlena=$iNivel-2;
			for($j=1;$j<=$iLlena;$j++){
				$sPrefijo=$sPrefijo.'--';
				if ($factor>1){
					$factor=$factor/100;
					}
				}
			$DATA['unae26nivel']=$iNivel;
			$DATA['unae26prefijo']=$sPrefijo;
			}
		$DATA['unae26lugar']=$iBase+($DATA['unae26orden']*$factor);
		$bpasa=false;
		if ($DATA['paso']==10){
			$sCampos226='unae26consec, unae26idzona, unae26id, unae26activa, unae26nombre, 
unae26fractal, unae26unidadpadre, unae26idresponsable, unae26tituloresponsable, unae26nivel, 
unae26orden, unae26lugar, unae26prefijo';
			$sValores226=''.$DATA['unae26consec'].', '.$DATA['unae26idzona'].', '.$DATA['unae26id'].', "'.$DATA['unae26activa'].'", "'.$DATA['unae26nombre'].'", 
"'.$DATA['unae26fractal'].'", '.$DATA['unae26unidadpadre'].', '.$DATA['unae26idresponsable'].', "'.$DATA['unae26tituloresponsable'].'", '.$DATA['unae26nivel'].', 
'.$DATA['unae26orden'].', '.$DATA['unae26lugar'].', "'.$DATA['unae26prefijo'].'"';
			if ($APP->utf8==1){
				$sSQL='INSERT INTO unae26unidadesfun ('.$sCampos226.') VALUES ('.utf8_encode($sValores226).');';
				$sdetalle=$sCampos226.'['.utf8_encode($sValores226).']';
				}else{
				$sSQL='INSERT INTO unae26unidadesfun ('.$sCampos226.') VALUES ('.$sValores226.');';
				$sdetalle=$sCampos226.'['.$sValores226.']';
				}
			$idAccion=2;
			$bpasa=true;
			}else{
			$scampo[1]='unae26activa';
			$scampo[2]='unae26nombre';
			$scampo[3]='unae26fractal';
			$scampo[4]='unae26unidadpadre';
			$scampo[5]='unae26idresponsable';
			$scampo[6]='unae26tituloresponsable';
			$scampo[7]='unae26orden';
			$scampo[8]='unae26nivel';
			$scampo[9]='unae26lugar';
			$scampo[10]='unae26prefijo';
			$sdato[1]=$DATA['unae26activa'];
			$sdato[2]=$DATA['unae26nombre'];
			$sdato[3]=$DATA['unae26fractal'];
			$sdato[4]=$DATA['unae26unidadpadre'];
			$sdato[5]=$DATA['unae26idresponsable'];
			$sdato[6]=$DATA['unae26tituloresponsable'];
			$sdato[7]=$DATA['unae26orden'];
			$sdato[8]=$DATA['unae26nivel'];
			$sdato[9]=$DATA['unae26lugar'];
			$sdato[10]=$DATA['unae26prefijo'];
			$numcmod=10;
			//unae26nivel, unae26lugar, unae26prefijo
			$sWhere='unae26id='.$DATA['unae26id'].'';
			$sSQL='SELECT * FROM unae26unidadesfun WHERE '.$sWhere;
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
					$sSQL='UPDATE unae26unidadesfun SET '.utf8_encode($sdatos).' WHERE '.$sWhere.';';
					}else{
					$sdetalle=$sdatos.'['.$sWhere.']';
					$sSQL='UPDATE unae26unidadesfun SET '.$sdatos.' WHERE '.$sWhere.';';
					}
				$idAccion=3;
				}
			}
		if ($bpasa){
			$result=$objDB->ejecutasql($sSQL);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' [226] ..<!-- '.$sSQL.' -->';
				if ($idAccion==2){
					$DATA['unae26id']='';
					$bQuitarCodigo=true;
					$DATA['paso']=0;
					}else{
					$DATA['paso']=2;
					}
				}else{
				if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Guardar 226 '.$sSQL.'<br>';}
				if ($bAudita[$idAccion]){seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], $idAccion, $DATA['unae26id'], $sdetalle, $objDB);}
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
function f226_db_Eliminar($unae26id, $objDB, $bDebug=false){
	$iCodModulo=226;
	$bAudita[4]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_226=$APP->rutacomun.'lg/lg_226_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_226)){$mensajes_226=$APP->rutacomun.'lg/lg_226_es.php';}
	require $mensajes_todas;
	require $mensajes_226;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	$unae26id=numeros_validar($unae26id);
	// Traer los datos para hacer las validaciones.
	if ($sError==''){
		$sSQL='SELECT * FROM unae26unidadesfun WHERE unae26id='.$unae26id.'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$filabase=$objDB->sf($tabla);
			}else{
			$sError='No se encuentra el registro solicitado {Ref: '.$unae26id.'}';
			}
		}
	if ($sError==''){
		$sSQL='SELECT unae26unidadpadre FROM unae26unidadesfun WHERE unae26unidadpadre='.$filabase['unae26id'].'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$sError='Existen Fractales creados, no es posible eliminar';
			}
		}
	if ($sError==''){
		if (!seg_revisa_permiso($iCodModulo, 4, $objDB)){
			$sError=$ERR['4'];
			}
		}
	if ($sError==''){
		$sSQL='SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=226';
		$tablaor=$objDB->ejecutasql($sSQL);
		while ($filaor=$objDB->sf($tablaor)){
			$sSQL='SELECT '.$filaor['campoid'].' FROM '.$filaor['tabla'].' WHERE '.$filaor['camporev'].'='.$_REQUEST['unae26id'].' LIMIT 0, 1';
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
		//$sSQL='DELETE FROM unae26unidadesfun WHERE unae27consec='.$filabase['unae26consec'].'';
		//$tabla=$objDB->ejecutasql($sSQL);
		$sWhere='unae26id='.$unae26id.'';
		//$sWhere='unae26idzona='.$filabase['unae26idzona'].' AND unae26consec='.$filabase['unae26consec'].'';
		$sSQL='DELETE FROM unae26unidadesfun WHERE '.$sWhere.';';
		$result=$objDB->ejecutasql($sSQL);
		if ($result==false){
			$sError=$ERR['falla_eliminar'].' .. <!-- '.$sSQL.' -->';
			}else{
			if ($bAudita[4]){seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $unae26id, $sWhere, $objDB);}
			}
		}
	return array($sError, $iTipoError, $sDebug);
	}
function f226_TituloBusqueda(){
	return 'Busqueda de Unidades funcionales';
	}
function f226_ParametrosBusqueda(){
	$sParams='<label class="Label90">Nombre</label><label><input id="b226nombre" name="b226nombre" type="text" value="" onchange="paginarbusqueda()" /></label>';
	return $sParams;
	}
function f226_JavaScriptBusqueda($iModuloBusca){
	$sRes='var sCampo=window.document.frmedita.scampobusca.value;
var params=new Array();
params[100]=sCampo;
params[101]=window.document.frmedita.paginabusqueda.value;
params[102]=window.document.frmedita.lppfbusqueda.value;
params[103]=window.document.frmedita.b226nombre.value;
xajax_f'.$iModuloBusca.'_HtmlBusqueda(params);';
	return $sRes;
	}
function f226_TablaDetalleBusquedas($aParametros, $objDB){
	$res='';
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_226=$APP->rutacomun.'lg/lg_226_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_226)){$mensajes_226=$APP->rutacomun.'lg/lg_226_es.php';}
	require $mensajes_todas;
	require $mensajes_226;
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	if (isset($aParametros[100])==0){$aParametros[100]=$_SESSION['unad_id_tercero'];}
	if (isset($aParametros[101])==0){$aParametros[101]=1;}
	if (isset($aParametros[102])==0){$aParametros[102]=20;}
	if (isset($aParametros[103])==0){$aParametros[103]='';}
	if (isset($aParametros[104])==0){$aParametros[104]='';}
	//$aParametros[103]=numeros_validar($aParametros[103]);
	$idTercero=$aParametros[100];
	$pagina=$aParametros[101];
	$lineastabla=$aParametros[102];
	$bAbierta=true;
	//$sSQL='SELECT Campo FROM Tabla WHERE Id='.$sValorId;
	//$tabla=$objDB->ejecutasql($sSQL);
	//if ($objDB->nf($tabla)>0){
		//$fila=$objDB->sf($tabla);
		//if ($fila['Campo']!='S'){$bAbierta=true;}
		//}
	$sLeyenda='';
	if ($sLeyenda!=''){
		$sLeyenda='<div class="salto1px"></div>
<div class="GrupoCamposAyuda">
'.$sLeyenda.'
<div class="salto1px"></div>
</div>';
		return array($sLeyenda.'<input id="paginaf226" name="paginaf226" type="hidden" value="'.$pagina.'"/><input id="lppf226" name="lppf226" type="hidden" value="'.$lineastabla.'"/>', $sDebug);
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
	$sTitulos='Consec, Zona, Id, Activa, Nombre, Fractal, Unidadpadre, Responsable, Tituloresponsable';
	$sSQL='SELECT TB.unae26consec, TB.unae26idzona, TB.unae26id, TB.unae26activa, TB.unae26nombre, TB.unae26fractal, T7.unae26nombre, T8.unad11razonsocial AS C8_nombre, TB.unae26tituloresponsable, TB.unae26unidadpadre, TB.unae26idresponsable, T8.unad11tipodoc AS C8_td, T8.unad11doc AS C8_doc 
FROM unae26unidadesfun AS TB, unae26unidadesfun AS T7, unad11terceros AS T8 
WHERE '.$sSQLadd1.' TB.unae26unidadpadre=T7.unae26id AND TB.unae26idresponsable=T8.unad11id '.$sSQLadd.'
ORDER BY TB.unae26consec, TB.unae26idzona';
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
			//return array(utf8_encode($sErrConsulta.'<input id="paginaf226" name="paginaf226" type="hidden" value="'.$pagina.'"/><input id="lppf226" name="lppf226" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
<td><b>'.$ETI['unae26consec'].'</b></td>
<td><b>'.$ETI['unae26idzona'].'</b></td>
<td><b>'.$ETI['unae26activa'].'</b></td>
<td><b>'.$ETI['unae26nombre'].'</b></td>
<td><b>'.$ETI['unae26fractal'].'</b></td>
<td><b>'.$ETI['unae26unidadpadre'].'</b></td>
<td colspan="2"><b>'.$ETI['unae26idresponsable'].'</b></td>
<td><b>'.$ETI['unae26tituloresponsable'].'</b></td>
<td align="right">
'.html_paginador('paginabusqueda', $registros, $lineastabla, $pagina, 'paginarbusqueda()').'
'.html_lpp('lppfbusqueda', $lineastabla, 'paginarbusqueda()').'
</td>
</tr>';
	$tlinea=1;
	while($filadet=$objDB->sf($tabladetalle)){
		$sPrefijo='<a href="javascript:Devuelve(\''.$filadet['unae26id'].'\');">';
		$sSufijo='</a>';
		$tlinea++;
		$et_unae26activa=$sPrefijo.$ETI['no'].$sSufijo;
		if ($filadet['unae26activa']=='S'){$et_unae26activa=$sPrefijo.$ETI['si'].$sSufijo;}
		$res=$res.'<tr onmouseover="cambia_color_over(this);" onmouseout="cambia_color_out(this);">
<td>'.$sPrefijo.$filadet['unae26consec'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['unae26idzona'].$sSufijo.'</td>
<td>'.$sPrefijo.$et_unae26activa.$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['unae26nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['unae26fractal'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['unae26nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['C8_td'].' '.$filadet['C8_doc'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['C8_nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['unae26tituloresponsable']).$sSufijo.'</td>
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