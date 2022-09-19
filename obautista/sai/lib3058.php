<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2021 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.25.10c miércoles, 14 de abril de 2021
--- 3058 saiu58lectura
*/
/** Archivo lib3058.php.
* Libreria 3058 saiu58lectura.
* @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
* @date miércoles, 14 de abril de 2021
*/
function f3058_Busquedas($aParametros){
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3058='lg/lg_3058_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3058)){$mensajes_3058='lg/lg_3058_es.php';}
	require $mensajes_todas;
	require $mensajes_3058;
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
	$sTitulo='<h2>'.$ETI['titulo_3058'].' - '.$sTitulo.'</h2>';
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97titulo', 'innerHTML', $sTitulo);
	$objResponse->assign('div_97params', 'innerHTML', $sParams);
	$objResponse->assign('div_97tabla', 'innerHTML', $sTabla);
	$objResponse->setFunction('paginarbusqueda','',$sJavaBusqueda);
	$objResponse->call('expandesector(97)');
	return $objResponse;
	}
function f3058_HtmlBusqueda($aParametros){
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
function f3058_TablaDetalleV2($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3058='lg/lg_3058_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3058)){$mensajes_3058='lg/lg_3058_es.php';}
	require $mensajes_todas;
	require $mensajes_3058;
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	if (isset($aParametros[100])==0){$aParametros[100]=$_SESSION['unad_id_tercero'];}
	if (isset($aParametros[101])==0){$aParametros[101]=1;}
	if (isset($aParametros[102])==0){$aParametros[102]=20;}
	if (isset($aParametros[103])==0){$aParametros[103]='';}
	if (isset($aParametros[104])==0){$aParametros[104]='';}
	$idTercero=$aParametros[100];
	$sDebug='';
	$pagina=$aParametros[101];
	$lineastabla=$aParametros[102];
	$saiu58idcorreo=$aParametros[103];
	$saiu58fecha=$aParametros[104];
	$bAbierta=true;
	//$sSQL='SELECT Campo FROM Tabla WHERE Id='.$sValorId;
	//$tabla=$objDB->ejecutasql($sSQL);
	//if ($objDB->nf($tabla)>0){
		//$fila=$objDB->sf($tabla);
		//if ($fila['Campo']!='S'){$bAbierta=true;}
		//}
	$sLeyenda='';
	$sBotones='<input id="paginaf3058" name="paginaf3058" type="hidden" value="'.$pagina.'"/>
	<input id="lppf3058" name="lppf3058" type="hidden" value="'.$lineastabla.'"/>';
	if ((int)$saiu58idcorreo==0){
		$sLeyenda='No ha seleccionado un correo a leer.';
		}else{
		if (!fecha_esvalida($saiu58fecha)){$sLeyenda='Fecha incorrecta.';}
		}
	if ($sLeyenda==''){
		$sSQL='SELECT saiu57usuariomail, saiu57pwdmail FROM saiu57correos WHERE saiu57id='.$saiu58idcorreo.'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$fila=$objDB->sf($tabla);
			$sFechaRevisa=fecha_mmddaaaa($saiu58fecha);
			if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' <b>Inicia lectura del buzon</b><br>';}
			//Lee el correo descartando los propios.
			$bConCuerpo=true;
			list($sError, $iCorreos, $aRes)=fgmail_leer($fila['saiu57usuariomail'], $fila['saiu57pwdmail'], $sFechaRevisa, $bConCuerpo, true);
			if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' <b>Termina lectura del buzon</b><br>';}
			if ($sError==''){
				if ($iCorreos==0){
					$sLeyenda='No hay mensajes de correo en el buzon de entrada en la fecha '.$saiu58fecha;
					}
				}else{
				$sLeyenda='Error al intentar leer el correo: '.$sError;
				}
			if (!is_object($objDB)){}
			if (true){
				//La lectura de los correos se pudo haber demorado y hay que reconectar la base de datos.
				$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
				if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
				}
			}
		}
	if ($sLeyenda!=''){
		$sLeyenda='<div class="salto1px"></div>
		<div class="GrupoCamposAyuda">
		'.$sLeyenda.'
		<div class="salto1px"></div>
		</div>';
		return array($sLeyenda.$sBotones, $sDebug);
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
	$sErrConsulta='';
	$res=$sErrConsulta.$sLeyenda.$sBotones;
	$res=$res.'<div class="table-responsive">
	<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">
	<thead class="fondoazul"><tr>
	<td><b>'.$ETI['saiu58idcorreo'].'</b></td>
	<td><b>'.$ETI['msg_usuario'].'</b></td>
	<td><b>'.$ETI['msg_titulo'].'</b></td>
	<td align="center"><b>'.$iCorreos.'</b></td>
	</tr></thead>';
	$tlinea=1;
	for ($k=1;$k<=$iCorreos;$k++){
		$sPrefijo='';
		$sSufijo='';
		$sClass=' class="resaltetabla"';
		$sLink='';
		if(($tlinea%2)!=0){$sClass='';}
		$tlinea++;
		$et_saiu58fecha='';
		$idTercero=0;
		$iNumTerceros=0;
		//Con el correo de origen traer el tercero.
		$sCorreo=$aRes[$k]['origen'];
		if ($sCorreo!=''){
			for ($l=1;$l<=4;$l++){
				if ($iNumTerceros==0){
					switch($l){
						case 1:
						$sCondi='unad11correonotifica="'.$sCorreo.'"';
						break;
						case 2:
						$sCondi='unad11correofuncionario="'.$sCorreo.'"';
						break;
						case 3:
						$sCondi='unad11correo="'.$sCorreo.'"';
						break;
						case 4:
						$sCondi='unad11correoinstitucional="'.$sCorreo.'"';
						break;
						}
					$sSQL='SELECT unad11id, unad11razonsocial FROM unad11terceros WHERE '.$sCondi;
					$tabla=$objDB->ejecutasql($sSQL);
					$iNumTerceros=$objDB->nf($tabla);
					if ($iNumTerceros>0){
						$fila=$objDB->sf($tabla);
						$et_saiu58fecha=cadena_notildes($fila['unad11razonsocial']).'<br>['.$sCorreo.']';
						$idTercero=$fila['unad11id'];
						}
					}
				}
			}
		if ($idTercero==0){
			$et_saiu58fecha='['.$sCorreo.']';
			$sPrefijo='<span class="rojo">';
			$sSufijo='</span>';
			}else{
			if ($iNumTerceros==1){
				$sPrefijo='<b>';
				$sSufijo='</b>';
				}
			}
		$idCorreo=$aRes[$k]['msg_id'];
		if ($bAbierta){
			$sLink='<a href="javascript:vercontenido('.$idCorreo.')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
			}
		$res=$res.'<tr'.$sClass.'>
		<td>'.$sPrefijo.$aRes[$k]['uid'].$sSufijo.'</td>
		<td>'.$sPrefijo.$et_saiu58fecha.$sSufijo.'</td>
		<td>'.cadena_notildes($aRes[$k]['asunto']).'
		<input id="msg_titulo_'.$idCorreo.'" name="msg_titulo_'.$idCorreo.'" type="hidden" value="'.cadena_notildes($aRes[$k]['asunto']).'" />
		<input id="msg_cuerpo_'.$idCorreo.'" name="msg_cuerpo_'.$idCorreo.'" type="hidden" value="'.cadena_notildes(htmlspecialchars($aRes[$k]['cuerpo'])).'" />
		</td>
		<td>'.$sLink.'</td>
		</tr>';
		/*
		<tr'.$sClass.'>
		<td></td>
		<td colspan="2">'.cadena_notildes($aRes[$k]['cuerpo']).'</td>
		</tr>';
		*/
		}
	$res=$res.'</table>
	<div class="salto5px"></div>
	</div>';
	return array(utf8_encode($res), $sDebug);
	}
function f3058_HtmlTabla($aParametros){
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
	list($sDetalle, $sDebugTabla)=f3058_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f3058detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f3058_VerCorreo($aParametros){
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
	$saiu58idcorreo=$aParametros[0];
	$saiu58fecha=$aParametros[1];
	$idCorreo=$aParametros[2];
	$sDetalle='{'.$idCorreo.'}';
	$sSQL='SELECT saiu57usuariomail, saiu57pwdmail FROM saiu57correos WHERE saiu57id='.$saiu58idcorreo.'';
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$sFechaRevisa=fecha_mmddaaaa($saiu58fecha);
		list($sError, $sDetalle)=fgmail_leerCorreo($fila['saiu57usuariomail'], $fila['saiu57pwdmail'], $sFechaRevisa, $idCorreo);
		if ($sError!=''){
			$sDetalle='Error al leer:'.$sError;
			}
		}
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f3058cuerpo', 'innerHTML', '');
	$objResponse->assign('txtCuerpo', 'value', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
?>