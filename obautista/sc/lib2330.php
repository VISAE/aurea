<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2020 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.25.0 viernes, 22 de mayo de 2020
--- 2330 unad11terceros
*/
/** Archivo lib2330.php.
* Libreria 2330 unad11terceros.
* @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
* @date viernes, 22 de mayo de 2020
*/
function f2330_HTMLComboV2_unad11tipodoc($objDB, $objCombos, $valor){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('unad11tipodoc', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	//$objCombos->iAncho=450;
	$objCombos->sAccion='RevisaLlave();';
	$sSQL='SELECT  AS id,  AS nombre FROM ';
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f2330_ExisteDato($datos){
	if(!is_array($datos)){$datos=json_decode(str_replace('\"','"',$datos),true);}
	$_SESSION['u_ultimominuto']=iminutoavance();
	$bHayLlave=true;
	$unad11tipodoc=htmlspecialchars($datos[1]);
	if ($unad11tipodoc==''){$bHayLlave=false;}
	$unad11doc=htmlspecialchars($datos[2]);
	if ($unad11doc==''){$bHayLlave=false;}
	if ($bHayLlave){
		require './app.php';
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		$sSQL='SELECT unad11doc FROM unad11terceros WHERE unad11tipodoc="'.$unad11tipodoc.'" AND unad11doc="'.$unad11doc.'"';
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
function f2330_Busquedas($aParametros){
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2330='lg/lg_2330_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2330)){$mensajes_2330='lg/lg_2330_es.php';}
	require $mensajes_todas;
	require $mensajes_2330;
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
		case 'cara39idautoriza':
		require $APP->rutacomun.'lib111.php';
		$sTabla=f111_TablaDetalleBusquedas($aParametrosB, $objDB);
		$sTitulo='Busqueda de terceros';
		$sParams=f111_ParametrosBusqueda();
		$sJavaBusqueda=f111_JavaScriptBusqueda(2330);
		break;
		}
	$sTitulo='<h2>'.$ETI['titulo_2330'].' - '.$sTitulo.'</h2>';
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97titulo', 'innerHTML', $sTitulo);
	$objResponse->assign('div_97params', 'innerHTML', $sParams);
	$objResponse->assign('div_97tabla', 'innerHTML', $sTabla);
	$objResponse->setFunction('paginarbusqueda','',$sJavaBusqueda);
	$objResponse->call('expandesector(97)');
	return $objResponse;
	}
function f2330_HtmlBusqueda($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$sError='';
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$sDetalle='';
	switch($aParametros[100]){
		case 'cara39idautoriza':
		require $APP->rutacomun.'lib111.php';
		$sDetalle=f111_TablaDetalleBusquedas($aParametros, $objDB);
		break;
		}
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97tabla', 'innerHTML', $sDetalle);
	return $objResponse;
	}
function f2330_TablaDetalleV2($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2330='lg/lg_2330_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2330)){$mensajes_2330='lg/lg_2330_es.php';}
	$mensajes_111=$APP->rutacomun.'lg/lg_111_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_111)){$mensajes_111=$APP->rutacomun.'lg/lg_111_es.php';}
	require $mensajes_todas;
	require $mensajes_2330;
	require $mensajes_111;
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	if (isset($aParametros[100])==0){$aParametros[100]=$_SESSION['unad_id_tercero'];}
	if (isset($aParametros[101])==0){$aParametros[101]=1;}
	if (isset($aParametros[102])==0){$aParametros[102]=20;}
	if (isset($aParametros[103])==0){$aParametros[103]='';}
	if (isset($aParametros[104])==0){$aParametros[104]='';}
	if (isset($aParametros[105])==0){$aParametros[105]='';}
	if (isset($aParametros[106])==0){$aParametros[106]='';}
	//$aParametros[103]=numeros_validar($aParametros[103]);
	$idTercero=$aParametros[100];
	$sDebug='';
	$pagina=$aParametros[101];
	$lineastabla=$aParametros[102];
	$bListar=$aParametros[106];
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
		return array($sLeyenda.'<input id="paginaf2330" name="paginaf2330" type="hidden" value="'.$pagina.'"/><input id="lppf2330" name="lppf2330" type="hidden" value="'.$lineastabla.'"/>', $sDebug);
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
	$sSQLadd1=' WHERE unad11id>0';
	//if ((int)$aParametros[103]!=-1){$sSQLadd=$sSQLadd.' AND TB.campo='.$aParametros[103];}
	//if ($aParametros[103]!=''){$sSQLadd=$sSQLadd.' AND TB.campo2 LIKE "%'.$aParametros[103].'%"';}
	if ($aParametros[103]!=''){
		$sSQLadd=$sSQLadd.' AND TB.unad11doc LIKE "%'.$aParametros[103].'%"';
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Intentano importar el documento: '.$aParametros[103].'<br>';}
		list($sErrorI, $sDebugI)=unad11_importar_V2($aParametros[103], '', $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugI;
		}
	switch($bListar){
		case 'S': //Permitidos
		$sSQLadd=$sSQLadd.' AND TB.unad11accesomovil<>0';
		break;
		case 'N': // No Permitidos
		$sSQLadd=$sSQLadd.' AND TB.unad11accesomovil=0';
		break;
		}
	if ($aParametros[104]!=''){
		$sBase=trim(strtoupper($aParametros[104]));
		$aNoms=explode(' ', $sBase);
		for ($k=1;$k<=count($aNoms);$k++){
			$sCadena=$aNoms[$k-1];
			if ($sCadena!=''){
				$sSQLadd=$sSQLadd.' AND TB.unad11razonsocial LIKE "%'.$sCadena.'%"';
				//$sSQLadd1=$sSQLadd1.'T1.unad11razonsocial LIKE "%'.$sCadena.'%" AND ';
				}
			}
		}
	//if ($sSQLadd1!=''){$sSQLadd1=' WHERE '.$sSQLadd1;}
	$sTitulos='Tipodoc, Documento, Razonsocial, Fecha nacimiento, Genero, Acceso movil';
	$registros=0;
	$bGigante=true; //En caso de que la tabla sea muy grande pasarlo a true
	$sLimite='';
	if ($bGigante){
		$sSQL='SELECT COUNT(1) AS Total 
FROM unad11terceros AS TB 
'.$sSQLadd1.$sSQLadd.'';
		$tabladetalle=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabladetalle)>0){
			$fila=$objDB->sf($tabladetalle);
			$registros=$fila['Total'];
			}
		if ((($registros-1)/$lineastabla)<($pagina-1)){$pagina=(int)(($registros-1)/$lineastabla)+1;}
		if ($registros>$lineastabla){
			$rbase=($pagina-1)*$lineastabla;
			$sLimite=' LIMIT '.$rbase.', '.$lineastabla;
			}
		}
	$sSQL='SELECT TB.unad11tipodoc, TB.unad11doc, TB.unad11razonsocial, TB.unad11fechanace, TB.unad11genero, TB.unad11accesomovil, TB.unad11id 
FROM unad11terceros AS TB 
'.$sSQLadd1.$sSQLadd.'
ORDER BY TB.unad11doc';
	$sSQLlista=str_replace("'","|",$sSQL);
	$sSQLlista=str_replace('"',"|",$sSQLlista);
	$sErrConsulta='<input id="consulta_2330" name="consulta_2330" type="hidden" value="'.$sSQLlista.'"/>
<input id="titulos_2330" name="titulos_2330" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objDB->ejecutasql($sSQL.$sLimite);
	if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 2330: '.$sSQL.$sLimite.'<br>';}
	if ($tabladetalle==false){
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
		//$sLeyenda=$sSQL;
		}else{
		if (!$bGigante){
			$registros=$objDB->nf($tabladetalle);
			if ($registros==0){
				//return array(cadena_codificar($sErrConsulta.'<input id="paginaf2330" name="paginaf2330" type="hidden" value="'.$pagina.'"/><input id="lppf2330" name="lppf2330" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
				}
			if ((($registros-1)/$lineastabla)<($pagina-1)){$pagina=(int)(($registros-1)/$lineastabla)+1;}
			if ($registros>$lineastabla){
				$rbase=($pagina-1)*$lineastabla;
				$sLimite=' LIMIT '.$rbase.', '.$lineastabla;
				$tabladetalle=$objDB->ejecutasql($sSQL.$sLimite);
				}
			}
		}
	$res=$sErrConsulta.$sLeyenda.'<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">
<tr class="fondoazul">
<td colspan="2"><b>'.$ETI['unad11doc'].'</b></td>
<td><b>'.$ETI['unad11razonsocial'].'</b></td>
<td><b>'.$ETI['unad11fechanace'].'</b></td>
<td><b>'.$ETI['unad11genero'].'</b></td>
<td><b>'.$ETI['unad11accesomovil'].'</b></td>
<td align="right">
'.html_paginador('paginaf2330', $registros, $lineastabla, $pagina, 'paginarf2330()').'
'.html_lpp('lppf2330', $lineastabla, 'paginarf2330()').'
</td>
</tr>';
	$tlinea=1;
	while($filadet=$objDB->sf($tabladetalle)){
		$sPrefijo='';
		$sSufijo='';
		$sClass='';
		$sLink='';
		$et_unad11accesomovil=$sPrefijo.$ETI['no'].$sSufijo;
		if ($filadet['unad11accesomovil']!=0){
			$sPrefijo='<b>';
			$sSufijo='</b>';
			$et_unad11accesomovil=$sPrefijo.$ETI['si'].$sSufijo;
			}
		if(($tlinea%2)==0){$sClass=' class="resaltetabla"';}
		$tlinea++;
		/*
		$et_unad11genero=$sPrefijo.$ETI['no'].$sSufijo;
		if ($filadet['unad11genero']=='S'){$et_unad11genero=$sPrefijo.$ETI['si'].$sSufijo;}
		*/
		$et_unad11fechanace='';
		if ($filadet['unad11fechanace']!='00/00/0000'){$et_unad11fechanace=$filadet['unad11fechanace'];}
		/*
		$et_unad11ecivil=$sPrefijo.$ETI['no'].$sSufijo;
		if ($filadet['unad11ecivil']=='S'){$et_unad11ecivil=$sPrefijo.$ETI['si'].$sSufijo;}
		*/
		if ($bAbierta){
			$sLink='<a href="javascript:cargaridf2330('.$filadet['unad11id'].')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$res=$res.'<tr'.$sClass.'>
<td>'.$sPrefijo.$filadet['unad11tipodoc'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['unad11doc'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['unad11razonsocial']).$sSufijo.'</td>
<td>'.$sPrefijo.$et_unad11fechanace.$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['unad11genero'].$sSufijo.'</td>
<td>'.$sPrefijo.$et_unad11accesomovil.$sSufijo.'</td>
<td>'.$sLink.'</td>
</tr>';
		}
	$res=$res.'</table>';
	$objDB->liberar($tabladetalle);
	return array(cadena_codificar($res), $sDebug);
	}
function f2330_HtmlTabla($aParametros){
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
	list($sDetalle, $sDebugTabla)=f2330_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f2330detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f2330_db_CargarPadre($DATA, $objDB, $bDebug=false){
	$sError='';
	$iTipoError=0;
	$sDebug='';
	require './app.php';
	if ($DATA['paso']==1){
		$sSQLcondi='unad11tipodoc="'.$DATA['unad11tipodoc'].'" AND unad11doc="'.$DATA['unad11doc'].'"';
		}else{
		$sSQLcondi='unad11id='.$DATA['unad11id'].'';
		}
	$sSQL='SELECT * FROM unad11terceros WHERE '.$sSQLcondi;
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$DATA['unad11tipodoc']=$fila['unad11tipodoc'];
		$DATA['unad11doc']=$fila['unad11doc'];
		$DATA['unad11id']=$fila['unad11id'];
		$DATA['unad11usuario']=$fila['unad11usuario'];
		$DATA['unad11nombre1']=$fila['unad11nombre1'];
		$DATA['unad11nombre2']=$fila['unad11nombre2'];
		$DATA['unad11apellido1']=$fila['unad11apellido1'];
		$DATA['unad11apellido2']=$fila['unad11apellido2'];
		$DATA['unad11genero']=$fila['unad11genero'];
		$DATA['unad11fechanace']=$fila['unad11fechanace'];
		$DATA['unad11rh']=$fila['unad11rh'];
		$DATA['unad11ecivil']=$fila['unad11ecivil'];
		$DATA['unad11razonsocial']=$fila['unad11razonsocial'];
		$DATA['unad11direccion']=$fila['unad11direccion'];
		$DATA['unad11telefono']=$fila['unad11telefono'];
		$DATA['unad11correo']=$fila['unad11correo'];
		$DATA['unad11accesomovil']=$fila['unad11accesomovil'];
		$bcargo=true;
		$DATA['paso']=2;
		$DATA['boculta2330']=0;
		$bLimpiaHijos=true;
		}else{
		$DATA['paso']=0;
		}
	return array($DATA, $sError, $iTipoError, $sDebug);
	}
function f2330_db_GuardarV2($DATA, $objDB, $bDebug=false){
	$iCodModulo=2330;
	$bAudita[2]=true;
	$bAudita[3]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2330='lg/lg_2330_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2330)){$mensajes_2330='lg/lg_2330_es.php';}
	require $mensajes_todas;
	require $mensajes_2330;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	// -- Se inicia validando todas las posibles entradas de usuario.
	/*
	if (isset($DATA['unad11tipodoc'])==0){$DATA['unad11tipodoc']='';}
	if (isset($DATA['unad11doc'])==0){$DATA['unad11doc']='';}
	if (isset($DATA['unad11id'])==0){$DATA['unad11id']='';}
	if (isset($DATA['unad11nombre1'])==0){$DATA['unad11nombre1']='';}
	if (isset($DATA['unad11nombre2'])==0){$DATA['unad11nombre2']='';}
	if (isset($DATA['unad11apellido1'])==0){$DATA['unad11apellido1']='';}
	if (isset($DATA['unad11apellido2'])==0){$DATA['unad11apellido2']='';}
	if (isset($DATA['unad11genero'])==0){$DATA['unad11genero']='';}
	if (isset($DATA['unad11fechanace'])==0){$DATA['unad11fechanace']='';}
	if (isset($DATA['unad11rh'])==0){$DATA['unad11rh']='';}
	if (isset($DATA['unad11ecivil'])==0){$DATA['unad11ecivil']='';}
	if (isset($DATA['unad11razonsocial'])==0){$DATA['unad11razonsocial']='';}
	if (isset($DATA['unad11direccion'])==0){$DATA['unad11direccion']='';}
	if (isset($DATA['unad11telefono'])==0){$DATA['unad11telefono']='';}
	if (isset($DATA['unad11correo'])==0){$DATA['unad11correo']='';}
	*/
	$DATA['unad11tipodoc']=htmlspecialchars(trim($DATA['unad11tipodoc']));
	$DATA['unad11doc']=htmlspecialchars(trim($DATA['unad11doc']));
	$DATA['unad11usuario']=htmlspecialchars(trim($DATA['unad11usuario']));
	$DATA['unad11nombre1']=htmlspecialchars(trim($DATA['unad11nombre1']));
	$DATA['unad11nombre2']=htmlspecialchars(trim($DATA['unad11nombre2']));
	$DATA['unad11apellido1']=htmlspecialchars(trim($DATA['unad11apellido1']));
	$DATA['unad11apellido2']=htmlspecialchars(trim($DATA['unad11apellido2']));
	$DATA['unad11genero']=htmlspecialchars(trim($DATA['unad11genero']));
	$DATA['unad11rh']=htmlspecialchars(trim($DATA['unad11rh']));
	$DATA['unad11ecivil']=htmlspecialchars(trim($DATA['unad11ecivil']));
	$DATA['unad11razonsocial']=htmlspecialchars(trim($DATA['unad11razonsocial']));
	$DATA['unad11direccion']=htmlspecialchars(trim($DATA['unad11direccion']));
	$DATA['unad11telefono']=htmlspecialchars(trim($DATA['unad11telefono']));
	$DATA['unad11correo']=htmlspecialchars(trim($DATA['unad11correo']));
	// -- Se inicializan las variables que puedan pasar vacias {Especialmente números}.
	if ($DATA['unad11accesomovil']==''){$DATA['unad11accesomovil']=0;}
	// -- Seccion para validar los posibles causales de error.
	$sSepara=', ';
	if (true){
		if ($DATA['unad11correo']==''){$sError=$ERR['unad11correo'].$sSepara.$sError;}
		if ($DATA['unad11telefono']==''){$sError=$ERR['unad11telefono'].$sSepara.$sError;}
		if ($DATA['unad11direccion']==''){$sError=$ERR['unad11direccion'].$sSepara.$sError;}
		if ($DATA['unad11razonsocial']==''){$sError=$ERR['unad11razonsocial'].$sSepara.$sError;}
		if ($DATA['unad11ecivil']==''){$sError=$ERR['unad11ecivil'].$sSepara.$sError;}
		if ($DATA['unad11rh']==''){$sError=$ERR['unad11rh'].$sSepara.$sError;}
		if (!fecha_esvalida($DATA['unad11fechanace'])){
			//$DATA['unad11fechanace']='00/00/0000';
			$sError=$ERR['unad11fechanace'].$sSepara.$sError;
			}
		if ($DATA['unad11genero']==''){$sError=$ERR['unad11genero'].$sSepara.$sError;}
		if ($DATA['unad11apellido2']==''){$sError=$ERR['unad11apellido2'].$sSepara.$sError;}
		if ($DATA['unad11apellido1']==''){$sError=$ERR['unad11apellido1'].$sSepara.$sError;}
		if ($DATA['unad11nombre2']==''){$sError=$ERR['unad11nombre2'].$sSepara.$sError;}
		if ($DATA['unad11nombre1']==''){$sError=$ERR['unad11nombre1'].$sSepara.$sError;}
		//Fin de las valiaciones NO LLAVE.
		}
	//Valiaciones de campos obligatorios en todo guardar.
	if ($DATA['unad11doc']==''){$sError=$ERR['unad11doc'];}
	if ($DATA['unad11tipodoc']==''){$sError=$ERR['unad11tipodoc'];}
	// -- Se verifican los valores de campos de otras tablas.
	$bQuitarCodigo=false;
	$sCampoCodigo='';
	if ($sError==''){
		if ($DATA['paso']==10){
			$sSQL='SELECT 1 FROM unad11terceros WHERE unad11tipodoc="'.$DATA['unad11tipodoc'].'" AND unad11doc="'.$DATA['unad11doc'].'"';
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
			$DATA['unad11id']=tabla_consecutivo('unad11terceros','unad11id', '', $objDB);
			if ($DATA['unad11id']==-1){$sError=$objDB->serror;}
			}
		}
	if ($sError==''){
		$bpasa=false;
		if ($DATA['paso']==10){
			}else{
			$scampo[1]='unad11nombre1';
			$scampo[2]='unad11nombre2';
			$scampo[3]='unad11apellido1';
			$scampo[4]='unad11apellido2';
			$scampo[5]='unad11genero';
			$scampo[6]='unad11fechanace';
			$scampo[7]='unad11rh';
			$scampo[8]='unad11ecivil';
			$scampo[9]='unad11razonsocial';
			$scampo[10]='unad11direccion';
			$scampo[11]='unad11telefono';
			$scampo[12]='unad11correo';
			$sdato[1]=$DATA['unad11nombre1'];
			$sdato[2]=$DATA['unad11nombre2'];
			$sdato[3]=$DATA['unad11apellido1'];
			$sdato[4]=$DATA['unad11apellido2'];
			$sdato[5]=$DATA['unad11genero'];
			$sdato[6]=$DATA['unad11fechanace'];
			$sdato[7]=$DATA['unad11rh'];
			$sdato[8]=$DATA['unad11ecivil'];
			$sdato[9]=$DATA['unad11razonsocial'];
			$sdato[10]=$DATA['unad11direccion'];
			$sdato[11]=$DATA['unad11telefono'];
			$sdato[12]=$DATA['unad11correo'];
			$numcmod=12;
			$sWhere='unad11id='.$DATA['unad11id'].'';
			$sSQL='SELECT * FROM unad11terceros WHERE '.$sWhere;
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
					$sSQL='UPDATE unad11terceros SET '.cadena_codificar($sdatos).' WHERE '.$sWhere.';';
					}else{
					$sdetalle=$sdatos.'['.$sWhere.']';
					$sSQL='UPDATE unad11terceros SET '.$sdatos.' WHERE '.$sWhere.';';
					}
				$idAccion=3;
				}
			}
		if ($bpasa){
			$result=$objDB->ejecutasql($sSQL);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' [2330] ..<!-- '.$sSQL.' -->';
				if ($idAccion==2){
					$DATA['unad11id']='';
					$bQuitarCodigo=true;
					$DATA['paso']=0;
					}else{
					$DATA['paso']=2;
					}
				}else{
				if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Guardar 111 '.$sSQL.'<br>';}
				if ($bAudita[$idAccion]){seg_auditar(111, $_SESSION['unad_id_tercero'], $idAccion, $DATA['unad11id'], $sdetalle, $objDB);}
				$DATA['paso']=2;
				}
			}else{
			$DATA['paso']=2;
			}
		}else{
		if ($DATA['paso']==10){
			$DATA['paso']=0;
			}else{
			$DATA['paso']=2;
			}
		if ($bQuitarCodigo){
			if ($sCampoCodigo!=''){$DATA[$sCampoCodigo]='';}
			}
		}
	//if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' InfoDepura<br>';}
	return array($DATA, $sError, $iTipoError, $sDebug);
	}
function f2330_db_Eliminar($unad11id, $objDB, $bDebug=false){
	$iCodModulo=2330;
	$bAudita[4]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2330='lg/lg_2330_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2330)){$mensajes_2330='lg/lg_2330_es.php';}
	require $mensajes_todas;
	require $mensajes_2330;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	$unad11id=numeros_validar($unad11id);
	// Traer los datos para hacer las validaciones.
	if ($sError==''){
		$sSQL='SELECT * FROM unad11terceros WHERE unad11id='.$unad11id.'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$filabase=$objDB->sf($tabla);
			}else{
			$sError='No se encuentra el registro solicitado {Ref: '.$unad11id.'}';
			}
		}
	if ($sError==''){
		$sSQL='SELECT 1 FROM cara39autorizacionmovil WHERE cara39idtercero='.$filabase['unad11id'].'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$sError='Existen Autorizaciones creados, no es posible eliminar';
			}
		}
	if ($sError==''){
		if (!seg_revisa_permiso($iCodModulo, 4, $objDB)){
			$sError=$ERR['4'];
			}
		}
	if ($sError==''){
		$sSQL='SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=2330';
		$tablaor=$objDB->ejecutasql($sSQL);
		while ($filaor=$objDB->sf($tablaor)){
			$sSQL='SELECT '.$filaor['campoid'].' FROM '.$filaor['tabla'].' WHERE '.$filaor['camporev'].'='.$_REQUEST['unad11id'].' LIMIT 0, 1';
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
		//$sSQL='DELETE FROM cara39autorizacionmovil WHERE cara39idtercero='.$filabase['unad11id'].'';
		//$tabla=$objDB->ejecutasql($sSQL);
		$sWhere='unad11id='.$unad11id.'';
		//$sWhere='unad11doc="'.$filabase['unad11doc'].'" AND unad11tipodoc="'.$filabase['unad11tipodoc'].'"';
		$sSQL='DELETE FROM unad11terceros WHERE '.$sWhere.';';
		$result=$objDB->ejecutasql($sSQL);
		if ($result==false){
			$sError=$ERR['falla_eliminar'].' .. <!-- '.$sSQL.' -->';
			}else{
			if ($bAudita[4]){seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $unad11id, $sWhere, $objDB);}
			}
		}
	return array($sError, $iTipoError, $sDebug);
	}
function f2330_TituloBusqueda(){
	return 'Busqueda de Acceso a moviles';
	}
function f2330_ParametrosBusqueda(){
	$sParams='<label class="Label90">Nombre</label><label><input id="b2330nombre" name="b2330nombre" type="text" value="" onchange="paginarbusqueda()" /></label>';
	return $sParams;
	}
function f2330_JavaScriptBusqueda($iModuloBusca){
	$sRes='var sCampo=window.document.frmedita.scampobusca.value;
var params=new Array();
params[100]=sCampo;
params[101]=window.document.frmedita.paginabusqueda.value;
params[102]=window.document.frmedita.lppfbusqueda.value;
params[103]=window.document.frmedita.b2330nombre.value;
xajax_f'.$iModuloBusca.'_HtmlBusqueda(params);';
	return $sRes;
	}
function f2330_TablaDetalleBusquedas($aParametros, $objDB){
	$res='';
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2330='lg/lg_2330_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2330)){$mensajes_2330='lg/lg_2330_es.php';}
	require $mensajes_todas;
	require $mensajes_2330;
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
		return array($sLeyenda.'<input id="paginaf2330" name="paginaf2330" type="hidden" value="'.$pagina.'"/><input id="lppf2330" name="lppf2330" type="hidden" value="'.$lineastabla.'"/>', $sDebug);
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
	$sTitulos='Tipodoc, Doc, Id, Usuario, Nombre1, Nombre2, Apellido1, Apellido2, Genero, Fechanace, Rh, Ecivil, Razonsocial, Direccion, Telefono, Correo, Accesomovil';
	$sSQL='SELECT TB.unad11tipodoc, TB.unad11doc, TB.unad11id, TB.unad11usuario, TB.unad11nombre1, TB.unad11nombre2, TB.unad11apellido1, TB.unad11apellido2, TB.unad11genero, TB.unad11fechanace, TB.unad11rh, T12.unad21nombre, TB.unad11razonsocial, TB.unad11direccion, TB.unad11telefono, TB.unad11correo, TB.unad11accesomovil, TB.unad11ecivil 
FROM unad11terceros AS TB, unad21estadocivil AS T12 
WHERE '.$sSQLadd1.' TB.unad11ecivil=T12.unad21codigo '.$sSQLadd.'
ORDER BY TB.unad11tipodoc, TB.unad11doc';
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
			//return array(cadena_codificar($sErrConsulta.'<input id="paginaf2330" name="paginaf2330" type="hidden" value="'.$pagina.'"/><input id="lppf2330" name="lppf2330" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
<td><b>'.$ETI['unad11tipodoc'].'</b></td>
<td><b>'.$ETI['unad11doc'].'</b></td>
<td><b>'.$ETI['unad11usuario'].'</b></td>
<td><b>'.$ETI['unad11nombre1'].'</b></td>
<td><b>'.$ETI['unad11nombre2'].'</b></td>
<td><b>'.$ETI['unad11apellido1'].'</b></td>
<td><b>'.$ETI['unad11apellido2'].'</b></td>
<td><b>'.$ETI['unad11genero'].'</b></td>
<td><b>'.$ETI['unad11fechanace'].'</b></td>
<td><b>'.$ETI['unad11rh'].'</b></td>
<td><b>'.$ETI['unad11ecivil'].'</b></td>
<td><b>'.$ETI['unad11razonsocial'].'</b></td>
<td><b>'.$ETI['unad11direccion'].'</b></td>
<td><b>'.$ETI['unad11telefono'].'</b></td>
<td><b>'.$ETI['unad11correo'].'</b></td>
<td><b>'.$ETI['unad11accesomovil'].'</b></td>
<td align="right">
'.html_paginador('paginabusqueda', $registros, $lineastabla, $pagina, 'paginarbusqueda()').'
'.html_lpp('lppfbusqueda', $lineastabla, 'paginarbusqueda()').'
</td>
</tr>';
	$tlinea=1;
	while($filadet=$objDB->sf($tabladetalle)){
		$sPrefijo='<a href="javascript:Devuelve(\''.$filadet['unad11id'].'\');">';
		$sSufijo='</a>';
		$tlinea++;
		$et_unad11fechanace='';
		if ($filadet['unad11fechanace']!='00/00/0000'){$et_unad11fechanace=$filadet['unad11fechanace'];}
		$res=$res.'<tr onmouseover="cambia_color_over(this);" onmouseout="cambia_color_out(this);">
<td>'.$sPrefijo.$filadet['unad11tipodoc'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['unad11doc']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['unad11usuario']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['unad11nombre1']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['unad11nombre2']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['unad11apellido1']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['unad11apellido2']).$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['unad11genero'].$sSufijo.'</td>
<td>'.$sPrefijo.$et_unad11fechanace.$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['unad11rh'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['unad11ecivil'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['unad11razonsocial']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['unad11direccion']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['unad11telefono']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['unad11correo']).$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['unad11accesomovil'].$sSufijo.'</td>
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
?>