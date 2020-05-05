<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2018 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.21.0 martes, 01 de mayo de 2018
--- 2138 unad11terceros
*/
function f2138_HTMLComboV2_unad11tipodoc($objdb, $objCombos, $valor){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('unad11tipodoc', $valor, false, '{'.$ETI['msg_seleccione'].'}');
	$objCombos->sAccion='RevisaLlave();';
	$res=$objCombos->html('SELECT  AS id,  AS nombre FROM ', $objdb);
	return $res;
	}
function f2138_ExisteDato($datos){
	if(!is_array($datos)){$datos=json_decode(str_replace('\"','"',$datos),true);}
	$_SESSION['u_ultimominuto']=iminutoavance();
	$bHayLlave=true;
	$unad11tipodoc=htmlspecialchars($datos[1]);
	if ($unad11tipodoc==''){$bHayLlave=false;}
	$unad11doc=htmlspecialchars($datos[2]);
	if ($unad11doc==''){$bHayLlave=false;}
	if ($bHayLlave){
		require './app.php';
		$objdb=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objdb->dbPuerto=$APP->dbpuerto;}
		$objdb->xajax();
		$sql='SELECT unad11doc FROM unad11terceros WHERE unad11tipodoc="'.$unad11tipodoc.'" AND unad11doc="'.$unad11doc.'"';
		$res=$objdb->ejecutasql($sql);
		if ($objdb->nf($res)==0){$bHayLlave=false;}
		$objdb->CerrarConexion();
		if ($bHayLlave){
			$objResponse=new xajaxResponse();
			$objResponse->call('cambiapaginaV2');
			return $objResponse;
			}
		}
	}
function f2138_Busquedas($params){
	require './app.php';
	$objdb=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objdb->dbPuerto=$APP->dbpuerto;}
	$objdb->xajax();
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2138=$APP->rutacomun.'lg/lg_111_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2138)){$mensajes_2138=$APP->rutacomun.'lg/lg_111_es.php';}
	require $mensajes_todas;
	require $mensajes_2138;
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	$sCampo=$params[1];
	$sTitulo=' {'.$sCampo.'}';
	if (isset($params[2])==0){$params[2]=0;}
	if (isset($params[3])==0){$params[3]=0;}
	$sParams='';
	$sTabla='';
	$sJavaBusqueda='';
	$paramsb=array();
	$paramsb[101]=1;
	$paramsb[102]=20;
	switch($sCampo){
		}
	$sTitulo='<h2>'.$ETI['titulo_2138'].' - '.$sTitulo.'</h2>';
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97titulo', 'innerHTML', $sTitulo);
	$objResponse->assign('div_97params', 'innerHTML', $sParams);
	$objResponse->assign('div_97tabla', 'innerHTML', $sTabla);
	$objResponse->setFunction('paginarbusqueda','',$sJavaBusqueda);
	$objResponse->call('expandesector(97)');
	return $objResponse;
	}
function f2138_HtmlBusqueda($params){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	$sError='';
	require './app.php';
	$objdb=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objdb->dbPuerto=$APP->dbpuerto;}
	$objdb->xajax();
	$sDetalle='';
	switch($params[100]){
		}
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97tabla', 'innerHTML', $sDetalle);
	return $objResponse;
	}
function f2138_TablaDetalleV2($params, $objdb, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2138=$APP->rutacomun.'lg/lg_111_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2138)){$mensajes_2138=$APP->rutacomun.'lg/lg_111_es.php';}
	require $mensajes_todas;
	require $mensajes_2138;
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	if (isset($params[101])==0){$params[101]=1;}
	if (isset($params[102])==0){$params[102]=20;}
	//if (isset($params[103])==0){$params[103]='';}
	//$params[103]=numeros_validar($params[103]);
	$sDebug='';
	$pagina=$params[101];
	$lineastabla=$params[102];
	$babierta=true;
	//$sql='SELECT Campo FROM Tabla WHERE Id='.$sValorId;
	//$tabla=$objdb->ejecutasql($sql);
	//if ($objdb->nf($tabla)>0){
		//$fila=$objdb->sf($tabla);
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
	$sqladd='1';
	$sqladd1='';
	//if ((int)$params[103]!=-1){$sqladd=$sqladd.' AND TB.campo='.$params[103];}
	//if ($params[103]!=''){$sqladd=$sqladd.' AND TB.campo2 LIKE "%'.$params[103].'%"';}
	/*
	if ($params[104]!=''){
		$sBase=trim(strtoupper($params[104]));
		$aNoms=explode(' ', $sBase);
		for ($k=1;$k<=count($aNoms);$k++){
			$sCadena=$aNoms[$k-1];
			if ($sCadena!=''){
				$sqladd=$sqladd.' AND T6.sys11razonsocial LIKE "%'.$sCadena.'%"';
				//$sqladd1=$sqladd1.'T1.sys11razonsocial LIKE "%'.$sCadena.'%" AND ';
				}
			}
		}
	*/
	$sTitulos='Tipodoc, Doc, Id, Usuario, Nombre1, Nombre2, Apellido1, Apellido2, Genero, Fechanace, Direccion, Telefono, Correo';
	$sql='SELECT TB.unad11tipodoc, TB.unad11doc, TB.unad11id, TB.unad11usuario, TB.unad11nombre1, TB.unad11nombre2, TB.unad11apellido1, TB.unad11apellido2, TB.unad11genero, TB.unad11fechanace, TB.unad11direccion, TB.unad11telefono, TB.unad11correo 
FROM unad11terceros AS TB 
WHERE '.$sqladd1.'  '.$sqladd.'
ORDER BY TB.unad11tipodoc, TB.unad11doc';
	$sqllista=str_replace("'","|",$sql);
	$sqllista=str_replace('"',"|",$sqllista);
	$sErrConsulta='<input id="consulta_2138" name="consulta_2138" type="hidden" value="'.$sqllista.'"/>
<input id="titulos_2138" name="titulos_2138" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objdb->ejecutasql($sql);
	if ($tabladetalle==false){
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 2138: '.$sql.'<br>';}
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sql.' '.$objdb->serror.'"/>';
		//$sLeyenda=$sql;
		}else{
		$registros=$objdb->nf($tabladetalle);
		if ($registros==0){
			//return array(utf8_encode($sErrConsulta.'<input id="paginaf2138" name="paginaf2138" type="hidden" value="'.$pagina.'"/><input id="lppf2138" name="lppf2138" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
			}
		if ((($registros-1)/$lineastabla)<($pagina-1)){$pagina=(int)(($registros-1)/$lineastabla)+1;}
		if ($registros>$lineastabla){
			$rbase=($pagina-1)*$lineastabla;
			$limite=' LIMIT '.$rbase.', '.$lineastabla;
			$tabladetalle=$objdb->ejecutasql($sql.$limite);
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
<td><b>'.$ETI['unad11direccion'].'</b></td>
<td><b>'.$ETI['unad11telefono'].'</b></td>
<td><b>'.$ETI['unad11correo'].'</b></td>
<td align="right">
'.html_paginador('paginaf2138', $registros, $lineastabla, $pagina, 'paginarf2138()').'
'.html_lpp('lppf2138', $lineastabla, 'paginarf2138()').'
</td>
</tr>';
	$tlinea=1;
	while($filadet=$objdb->sf($tabladetalle)){
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
		$et_unad11genero=$ETI['no'];
		if ($filadet['unad11genero']=='S'){$et_unad11genero=$ETI['si'];}
		$et_unad11fechanace='';
		if ($filadet['unad11fechanace']!='00/00/0000'){$et_unad11fechanace=$filadet['unad11fechanace'];}
		if ($babierta){
			$sLink='<a href="javascript:cargaridf2138('.$filadet['unad11id'].')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$res=$res.'<tr'.$sClass.'>
<td>'.$sPrefijo.cadena_notildes($filadet['unad11tipodoc']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['unad11doc']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['unad11usuario']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['unad11nombre1']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['unad11nombre2']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['unad11apellido1']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['unad11apellido2']).$sSufijo.'</td>
<td>'.$sPrefijo.$et_unad11genero.$sSufijo.'</td>
<td>'.$sPrefijo.$et_unad11fechanace.$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['unad11direccion']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['unad11telefono']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['unad11correo']).$sSufijo.'</td>
<td>'.$sLink.'</td>
</tr>';
		}
	$res=$res.'</table>';
	$objdb->liberar($tabladetalle);
	return array(utf8_encode($res), $sDebug);
	}
function f2138_HtmlTabla($params){
	$_SESSION['u_ultimominuto']=iminutoavance();
	$sError='';
	$bDebug=false;
	$sDebug='';
	$opts=$params;
	if(!is_array($opts)){$opts=json_decode(str_replace('\"','"',$opts),true);}
	if (isset($opts[99])!=0){if ($opts[99]==1){$bDebug=true;}}
	require './app.php';
	$objdb=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objdb->dbPuerto=$APP->dbpuerto;}
	$objdb->xajax();
	list($sDetalle, $sDebugTabla)=f2138_TablaDetalleV2($params, $objdb, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objdb->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f2138detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f2138_db_CargarPadre($DATA, $objdb, $bDebug=false){
	$sError='';
	$iTipoError=0;
	$sDebug='';
	require './app.php';
	if ($DATA['paso']==1){
		$sqlcondi='unad11tipodoc="'.$DATA['unad11tipodoc'].'" AND unad11doc="'.$DATA['unad11doc'].'"';
		}else{
		$sqlcondi='unad11id='.$DATA['unad11id'].'';
		}
	$sql='SELECT * FROM unad11terceros WHERE '.$sqlcondi;
	$tabla=$objdb->ejecutasql($sql);
	if ($objdb->nf($tabla)>0){
		$fila=$objdb->sf($tabla);
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
		$DATA['unad11direccion']=$fila['unad11direccion'];
		$DATA['unad11telefono']=$fila['unad11telefono'];
		$DATA['unad11correo']=$fila['unad11correo'];
		$bcargo=true;
		$DATA['paso']=2;
		$DATA['boculta2138']=0;
		$bLimpiaHijos=true;
		}else{
		$DATA['paso']=0;
		}
	return array($DATA, $sError, $iTipoError, $sDebug);
	}
function f2138_db_GuardarV2($DATA, $objdb, $bDebug=false){
	$icodmodulo=2138;
	$bAudita[2]=true;
	$bAudita[3]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2138=$APP->rutacomun.'lg/lg_111_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2138)){$mensajes_2138=$APP->rutacomun.'lg/lg_111_es.php';}
	require $mensajes_todas;
	require $mensajes_2138;
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
	$DATA['unad11direccion']=htmlspecialchars(trim($DATA['unad11direccion']));
	$DATA['unad11telefono']=htmlspecialchars(trim($DATA['unad11telefono']));
	$DATA['unad11correo']=htmlspecialchars(trim($DATA['unad11correo']));
	// -- Se inicializan las variables que puedan pasar vacias {Especialmente números}.
	// -- Seccion para validar los posibles causales de error.
	$sSepara=', ';
	if (true){
		if ($DATA['unad11correo']==''){$sError=$ERR['unad11correo'].$sSepara.$sError;}
		//if ($DATA['unad11telefono']==''){$sError=$ERR['unad11telefono'].$sSepara.$sError;}
		//if ($DATA['unad11direccion']==''){$sError=$ERR['unad11direccion'].$sSepara.$sError;}
		if (!fecha_esvalida($DATA['unad11fechanace'])){
			$DATA['unad11fechanace']='00/00/0000';
			//$sError=$ERR['unad11fechanace'].$sSepara.$sError;
			}
		//if ($DATA['unad11genero']==''){$sError=$ERR['unad11genero'].$sSepara.$sError;}
		//if ($DATA['unad11apellido2']==''){$sError=$ERR['unad11apellido2'].$sSepara.$sError;}
		if ($DATA['unad11apellido1']==''){$sError=$ERR['unad11apellido1'].$sSepara.$sError;}
		//if ($DATA['unad11nombre2']==''){$sError=$ERR['unad11nombre2'].$sSepara.$sError;}
		if ($DATA['unad11nombre1']==''){$sError=$ERR['unad11nombre1'].$sSepara.$sError;}
		//Fin de las valiaciones NO LLAVE.
		}
	//Valiaciones de campos obligatorios en todo guardar.
	if ($DATA['unad11doc']==''){$sError=$ERR['unad11doc'];}
	if ($DATA['unad11tipodoc']==''){$sError=$ERR['unad11tipodoc'];}
	// -- Se verifican los valores de campos de otras tablas.
	if ($sError==''){
		if ($DATA['paso']==10){
			$sql='SELECT unad11tipodoc FROM unad11terceros WHERE unad11tipodoc="'.$DATA['unad11tipodoc'].'" AND unad11doc="'.$DATA['unad11doc'].'"';
			$result=$objdb->ejecutasql($sql);
			if ($objdb->nf($result)!=0){
				$sError=$ERR['existe'];
				}else{
				if (!seg_revisa_permiso($icodmodulo, 2, $objdb)){$sError=$ERR['2'];}
				}
			}else{
			if (!seg_revisa_permiso($icodmodulo, 3, $objdb)){$sError=$ERR['3'];}
			}
		}
	if ($sError==''){
		if ($DATA['paso']==10){
			//Preparar el Id, Si no lo hay se quita la comprobación.
			$DATA['unad11id']=tabla_consecutivo('unad11terceros','unad11id', '', $objdb);
			if ($DATA['unad11id']==-1){$sError=$objdb->serror;}
			}
		}
	if ($sError==''){
		$bpasa=false;
		if ($DATA['paso']==10){
			$DATA['unad11usuario']='';
			$sCampos2138='unad11tipodoc, unad11doc, unad11id, unad11usuario, unad11nombre1, unad11nombre2, unad11apellido1, unad11apellido2, unad11genero, unad11fechanace, 
unad11direccion, unad11telefono, unad11correo';
			$sValores2138='"'.$DATA['unad11tipodoc'].'", "'.$DATA['unad11doc'].'", '.$DATA['unad11id'].', "'.$DATA['unad11usuario'].'", "'.$DATA['unad11nombre1'].'", "'.$DATA['unad11nombre2'].'", "'.$DATA['unad11apellido1'].'", "'.$DATA['unad11apellido2'].'", "'.$DATA['unad11genero'].'", "'.$DATA['unad11fechanace'].'", 
"'.$DATA['unad11direccion'].'", "'.$DATA['unad11telefono'].'", "'.$DATA['unad11correo'].'"';
			if ($APP->utf8==1){
				$sql='INSERT INTO unad11terceros ('.$sCampos2138.') VALUES ('.utf8_encode($sValores2138).');';
				$sdetalle=$sCampos2138.'['.utf8_encode($sValores2138).']';
				}else{
				$sql='INSERT INTO unad11terceros ('.$sCampos2138.') VALUES ('.$sValores2138.');';
				$sdetalle=$sCampos2138.'['.$sValores2138.']';
				}
			$idaccion=2;
			$bpasa=true;
			}else{
			$unad11razonsocial=trim(trim($DATA['unad11nombre1']).' '.trim($DATA['unad11nombre2']).' '.trim($DATA['unad11apellido1']).' '.trim($DATA['unad11apellido2']));
			$scampo[1]='unad11nombre1';
			$scampo[2]='unad11nombre2';
			$scampo[3]='unad11apellido1';
			$scampo[4]='unad11apellido2';
			$scampo[5]='unad11genero';
			$scampo[6]='unad11fechanace';
			$scampo[7]='unad11direccion';
			$scampo[8]='unad11telefono';
			$scampo[9]='unad11correo';
			$scampo[10]='unad11razonsocial';
			$sdato[1]=$DATA['unad11nombre1'];
			$sdato[2]=$DATA['unad11nombre2'];
			$sdato[3]=$DATA['unad11apellido1'];
			$sdato[4]=$DATA['unad11apellido2'];
			$sdato[5]=$DATA['unad11genero'];
			$sdato[6]=$DATA['unad11fechanace'];
			$sdato[7]=$DATA['unad11direccion'];
			$sdato[8]=$DATA['unad11telefono'];
			$sdato[9]=$DATA['unad11correo'];
			$sdato[10]=$unad11razonsocial;
			$numcmod=10;
			$sWhere='unad11id='.$DATA['unad11id'].'';
			$sql='SELECT * FROM unad11terceros WHERE '.$sWhere;
			$sdatos='';
			$bPrimera=true;
			$result=$objdb->ejecutasql($sql);
			if ($objdb->nf($result)>0){
				$filabase=$objdb->sf($result);
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
					$sql='UPDATE unad11terceros SET '.utf8_encode($sdatos).' WHERE '.$sWhere.';';
					}else{
					$sdetalle=$sdatos.'['.$sWhere.']';
					$sql='UPDATE unad11terceros SET '.$sdatos.' WHERE '.$sWhere.';';
					}
				$idaccion=3;
				}
			}
		if ($bpasa){
			$result=$objdb->ejecutasql($sql);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' [2138] ..<!-- '.$sql.' -->';
				if ($idaccion==2){$DATA['unad11id']='';}
				$DATA['paso']=$DATA['paso']-10;
				}else{
				if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Guardar 2138 '.$sql.'<br>';}
				if ($bAudita[$idaccion]){seg_auditar(111, $_SESSION['unad_id_tercero'], $idaccion, $DATA['unad11id'], $sdetalle, $objdb);}
				$DATA['paso']=2;
				list($sErrorM, $sDebugM)=AUREA_ActualizarPerfilMoodle($DATA['unad11id'], $objdb, $bDebug);
				$sDebug=$sDebug.$sDebugM;
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
function f2138_db_Eliminar($unad11id, $objdb, $bDebug=false){
	$icodmodulo=2138;
	$bAudita[2]=true;
	$bAudita[3]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2138=$APP->rutacomun.'lg/lg_111_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2138)){$mensajes_2138=$APP->rutacomun.'lg/lg_111_es.php';}
	require $mensajes_todas;
	require $mensajes_2138;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	$unad11id=numeros_validar($unad11id);
	// Traer los datos para hacer las validaciones.
	if ($sError==''){
		$sql='SELECT * FROM unad11terceros WHERE unad11id='.$unad11id.'';
		$tabla=$objdb->ejecutasql($sql);
		if ($objdb->nf($tabla)>0){
			$filabase=$objdb->sf($tabla);
			}else{
			$sError='No se encuentra el registro solicitado {Ref: '.$unad11id.'}';
			}
		}
	if ($sError==''){
		if (!seg_revisa_permiso($icodmodulo, 4, $objdb)){
			$sError=$ERR['4'];
			}
		}
	if ($sError==''){
		$sql='SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=2138';
		$tablaor=$objdb->ejecutasql($sql);
		while ($filaor=$objdb->sf($tablaor)){
			$sql='SELECT '.$filaor['campoid'].' FROM '.$filaor['tabla'].' WHERE '.$filaor['camporev'].'='.$_REQUEST['unad11id'].' LIMIT 0, 1';
			$tabla=$objdb->ejecutasql($sql);
			if ($objdb->nf($tabla)>0){
				$sError=$filaor['mensaje'];
				if ($filaor['etiqueta']!=''){
					if (isset($ERR[$filaor['etiqueta']])!=0){$sError=$ERR[$filaor['etiqueta']];}
					}
				break;
				}
			}
		}
	if ($sError==''){
		$sWhere='unad11id='.$unad11id.'';
		//$sWhere='unad11doc="'.$filabase['unad11doc'].'" AND unad11tipodoc="'.$filabase['unad11tipodoc'].'"';
		$sql='DELETE FROM unad11terceros WHERE '.$sWhere.';';
		$result=$objdb->ejecutasql($sql);
		if ($result==false){
			$sError=$ERR['falla_eliminar'].' .. <!-- '.$sql.' -->';
			}else{
			if ($bAudita[4]){seg_auditar($icodmodulo, $_SESSION['unad_id_tercero'], 4, $unad11id, $sWhere, $objdb);}
			}
		}
	return array($sError, $iTipoError, $sDebug);
	}
function f2138_TituloBusqueda(){
	return 'Busqueda de Documentos';
	}
function f2138_ParametrosBusqueda(){
	$sParams='<label class="Label90">Nombre</label><label><input id="b2138nombre" name="b2138nombre" type="text" value="" onchange="paginarbusqueda()" /></label>';
	return $sParams;
	}
function f2138_JavaScriptBusqueda($iModuloBusca){
	$sRes='var sCampo=window.document.frmedita.scampobusca.value;
var params=new Array();
params[100]=sCampo;
params[101]=window.document.frmedita.paginabusqueda.value;
params[102]=window.document.frmedita.lppfbusqueda.value;
params[103]=window.document.frmedita.b2138nombre.value;
xajax_f'.$iModuloBusca.'_HtmlBusqueda(params);';
	return $sRes;
	}
function f2138_TablaDetalleBusquedas($params, $objdb){
	$res='';
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2138=$APP->rutacomun.'lg/lg_111_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2138)){$mensajes_2138=$APP->rutacomun.'lg/lg_111_es.php';}
	require $mensajes_todas;
	require $mensajes_2138;
	if(!is_array($params)){$params=json_decode(str_replace('\"','"',$params),true);}
	if (isset($params[101])==0){$params[101]=1;}
	if (isset($params[102])==0){$params[102]=20;}
	if (isset($params[103])==0){$params[103]='';}
	//$params[103]=numeros_validar($params[103]);
	$pagina=$params[101];
	$lineastabla=$params[102];
	$babierta=true;
	//$sql='SELECT Campo FROM Tabla WHERE Id='.$sValorId;
	//$tabla=$objdb->ejecutasql($sql);
	//if ($objdb->nf($tabla)>0){
		//$fila=$objdb->sf($tabla);
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
	$sqladd='1';
	$sqladd1='';
	//if ($params[103]!=''){$sqladd1=$sqladd1.'TB.campo2 LIKE "%'.$params[103].'%" AND ';}
	//if ($params[103]!=''){$sqladd=$sqladd.' AND TB.campo2 LIKE "%'.$params[103].'%"';}
	/*
	if ($params[104]!=''){
		$sBase=trim(strtoupper($params[104]));
		$aNoms=explode(' ', $sBase);
		for ($k=1;$k<=count($aNoms);$k++){
			$sCadena=$aNoms[$k-1];
			if ($sCadena!=''){
				$sqladd=$sqladd.' AND T6.sys11razonsocial LIKE "%'.$sCadena.'%"';
				//$sqladd1=$sqladd1.'T1.sys11razonsocial LIKE "%'.$sCadena.'%" AND ';
				}
			}
		}
	*/
	$sTitulos='Tipodoc, Doc, Id, Usuario, Nombre1, Nombre2, Apellido1, Apellido2, Genero, Fechanace, Direccion, Telefono, Correo';
	$sql='SELECT TB.unad11tipodoc, TB.unad11doc, TB.unad11id, TB.unad11usuario, TB.unad11nombre1, TB.unad11nombre2, TB.unad11apellido1, TB.unad11apellido2, TB.unad11genero, TB.unad11fechanace, TB.unad11direccion, TB.unad11telefono, TB.unad11correo 
FROM unad11terceros AS TB 
WHERE '.$sqladd1.'  '.$sqladd.'
ORDER BY TB.unad11tipodoc, TB.unad11doc';
	$sqllista=str_replace("'","|",$sql);
	$sqllista=str_replace('"',"|",$sqllista);
	$sErrConsulta='<input id="consulta_busqueda" name="consulta_busqueda" type="hidden" value="'.$sqllista.'"/>
<input id="titulos_busqueda" name="titulos_busqueda" type="hidden" value="'.$sTitulos.'"/>';
	$tabladetalle=$objdb->ejecutasql($sql);
	if ($tabladetalle==false){
		$registros=0;
		$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sql.' '.$objdb->serror.'"/>';
		//$sLeyenda=$sql;
		}else{
		$registros=$objdb->nf($tabladetalle);
		if ($registros==0){
			//return array(utf8_encode($sErrConsulta.'<input id="paginaf2138" name="paginaf2138" type="hidden" value="'.$pagina.'"/><input id="lppf2138" name="lppf2138" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
			}
		if ((($registros-1)/$lineastabla)<($pagina-1)){$pagina=(int)(($registros-1)/$lineastabla)+1;}
		if ($registros>$lineastabla){
			$rbase=($pagina-1)*$lineastabla;
			$limite=' LIMIT '.$rbase.', '.$lineastabla;
			$tabladetalle=$objdb->ejecutasql($sql.$limite);
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
<td><b>'.$ETI['unad11direccion'].'</b></td>
<td><b>'.$ETI['unad11telefono'].'</b></td>
<td><b>'.$ETI['unad11correo'].'</b></td>
<td align="right">
'.html_paginador('paginabusqueda', $registros, $lineastabla, $pagina, 'paginarbusqueda()').'
'.html_lpp('lppfbusqueda', $lineastabla, 'paginarbusqueda()').'
</td>
</tr>';
	$tlinea=1;
	while($filadet=$objdb->sf($tabladetalle)){
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
<td>'.$sPrefijo.cadena_notildes($filadet['unad11direccion']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['unad11telefono']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['unad11correo']).$sSufijo.'</td>
<td></td>
</tr>';
		}
	$res=$res.'</table>';
	$objdb->liberar($tabladetalle);
	return utf8_encode($res);
	}
// -----------------------------------
// ---- Funciones personalizadas  ----
// -----------------------------------
?>