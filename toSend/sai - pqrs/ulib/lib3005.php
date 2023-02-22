<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2020 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.24.1 viernes, 21 de febrero de 2020
--- 3005 saiu05solicitud
*/
/** Archivo lib3005.php.
* Libreria 3005 saiu05solicitud.
* @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
* @date viernes, 21 de febrero de 2020
*/
function f3005_HTMLComboV2_saiu05tiporadicado($objDB, $objCombos, $valor){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('saiu05tiporadicado', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	//$objCombos->iAncho=450;
	$objCombos->sAccion='RevisaLlave();';
	$sSQL='SELECT saiu16id AS id, saiu16nombre AS nombre FROM saiu16tiporadicado';
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f3005_HTMLComboV2_saiu05idtemaorigen($objDB, $objCombos, $valor, $vrsaiu05idtiposolorigen){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('saiu05idtemaorigen', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	$sSQL='';
	if ((int)$vrsaiu05idtiposolorigen!=0){
		$objCombos->iAncho=450;
		$sCondi='saiu03tiposol="'.$vrsaiu05idtiposolorigen.'"';
		if ($sCondi!=''){$sCondi=' WHERE '.$sCondi;}
		$sSQL='SELECT saiu03id AS id, saiu03titulo AS nombre FROM saiu03temasol'.$sCondi;
		}
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f3005_HTMLComboV2_saiu05idcentro($objDB, $objCombos, $valor, $vrsaiu18idzona){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$sCondi='unad24idzona="'.$vrsaiu18idzona.'"';
	$objCombos->nuevo('saiu05idcentro', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	//$objCombos->iAncho=450;
	$sSQL='SELECT unad24id AS id, unad24nombre AS nombre FROM unad24sede WHERE '.$sCondi.' AND unad24id>0';
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f3005_HTMLComboV2_saiu05idprograma($objDB, $objCombos, $valor, $vrsaiu05idescuela){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('saiu05idprograma', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	$objCombos->addItem('0', $ETI['msg_na']);
	//$objCombos->iAncho=450;
	$sCondiEscuela=' AND TB.core09idescuela="'.$vrsaiu05idescuela.'"';
	$sTabla2='';
	$sCampos2='';
	if ($vrsaiu05idescuela==''){
		$sCondiEscuela=' AND TB.core09idescuela=T12.core12id';
		$sTabla2=', core12escuela AS T12';
		$sCampos2=', " [", T12.core12sigla, "]"';
		}
	$sSQL='SELECT TB.core09id AS id, CONCAT(TB.core09nombre, " [", TB.core09codigo, "]"'.$sCampos2.') AS nombre FROM core09programa AS TB'.$sTabla2.' WHERE TB.core09id>0'.$sCondiEscuela;
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}	
function f3005_HTMLComboV2_saiu05coddepto($objDB, $objCombos, $valor, $vrsaiu05codpais){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	//@@ Se debe arreglar la condicion..
	$sCondi='unad19codpais="'.$vrsaiu05codpais.'"';
	if ($sCondi!=''){$sCondi=' WHERE '.$sCondi;}
	$objCombos->nuevo('saiu05coddepto', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	//$objCombos->iAncho=450;
	$objCombos->sAccion='carga_combo_saiu05codciudad()';
	$sSQL='SELECT unad19codigo AS id, unad19nombre AS nombre FROM unad19depto'.$sCondi;
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f3005_HTMLComboV2_saiu05codciudad($objDB, $objCombos, $valor, $vrsaiu05coddepto){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	//@@ Se debe arreglar la condicion..
	$sCondi='unad20coddepto="'.$vrsaiu05coddepto.'"';
	if ($sCondi!=''){$sCondi=' WHERE '.$sCondi;}
	$objCombos->nuevo('saiu18codciudad', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	//$objCombos->iAncho=450;
	$sSQL='SELECT unad20codigo AS id, unad20nombre AS nombre FROM unad20ciudad'.$sCondi;
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f3005_HTMLComboV2_saiu05costogenera($objDB, $objCombos, $valor){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	require $mensajes_todas;
	$objCombos->nuevo('saiu05costogenera', $valor, true, '{'.$ETI['msg_seleccione'].'}');
	//$objCombos->iAncho=450;
	$sSQL='SELECT  AS id,  AS nombre FROM ';
	$res=$objCombos->html($sSQL, $objDB);
	return $res;
	}
function f3005_Combosaiu05idtemaorigen($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$objCombos=new clsHtmlCombos('n');
	$html_saiu05idtemaorigen=f3005_HTMLComboV2_saiu05idtemaorigen($objDB, $objCombos, '', $aParametros[0]);
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_saiu05idtemaorigen', 'innerHTML', $html_saiu05idtemaorigen);
	$objResponse->call('jQuery("#saiu05idtemaorigen").chosen({no_results_text: "No existen coincidencias: ",width: "100%"})');
	return $objResponse;
	}
function f3005_ExisteDato($datos){
	if(!is_array($datos)){$datos=json_decode(str_replace('\"','"',$datos),true);}
	$_SESSION['u_ultimominuto']=iminutoavance();
	$bHayLlave=true;
	$saiu05agno=numeros_validar($datos[1]);
	if ($saiu05agno==''){$bHayLlave=false;}
	$saiu05mes=numeros_validar($datos[2]);
	if ($saiu05mes==''){$bHayLlave=false;}
	$saiu05tiporadicado=numeros_validar($datos[3]);
	if ($saiu05tiporadicado==''){$bHayLlave=false;}
	$saiu05consec=numeros_validar($datos[4]);
	if ($saiu05consec==''){$bHayLlave=false;}
	if ($bHayLlave){
		require './app.php';
		$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
		if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
		$objDB->xajax();
		$sSQL='SELECT saiu05consec FROM saiu05solicitud WHERE saiu05agno='.$saiu05agno.' AND saiu05mes='.$saiu05mes.' AND saiu05tiporadicado='.$saiu05tiporadicado.' AND saiu05consec='.$saiu05consec.'';
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
function f3005_Busquedas($aParametros){
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3005=$APP->rutacomun.'lg/lg_3005_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3005)){$mensajes_3005=$APP->rutacomun.'lg/lg_3005_es.php';}
	require $mensajes_todas;
	require $mensajes_3005;
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
		case 'saiu05idsolicitante':
		require $APP->rutacomun.'lib111.php';
		$sTabla=f111_TablaDetalleBusquedas($aParametrosB, $objDB);
		$sTitulo='Busqueda de terceros';
		$sParams=f111_ParametrosBusqueda();
		$sJavaBusqueda=f111_JavaScriptBusqueda(3005);
		break;
		case 'saiu05idinteresado':
		require $APP->rutacomun.'lib111.php';
		$sTabla=f111_TablaDetalleBusquedas($aParametrosB, $objDB);
		$sTitulo='Busqueda de terceros';
		$sParams=f111_ParametrosBusqueda();
		$sJavaBusqueda=f111_JavaScriptBusqueda(3005);
		break;
		case 'saiu05idresponsable':
		require $APP->rutacomun.'lib111.php';
		$sTabla=f111_TablaDetalleBusquedas($aParametrosB, $objDB);
		$sTitulo='Busqueda de terceros';
		$sParams=f111_ParametrosBusqueda();
		$sJavaBusqueda=f111_JavaScriptBusqueda(3005);
		break;
		case 'saiu06idusuario':
		require $APP->rutacomun.'lib111.php';
		$sTabla=f111_TablaDetalleBusquedas($aParametrosB, $objDB);
		$sTitulo='Busqueda de terceros';
		$sParams=f111_ParametrosBusqueda();
		$sJavaBusqueda=f111_JavaScriptBusqueda(3005);
		break;
		case 'saiu07idusuario':
		require $APP->rutacomun.'lib111.php';
		$sTabla=f111_TablaDetalleBusquedas($aParametrosB, $objDB);
		$sTitulo='Busqueda de terceros';
		$sParams=f111_ParametrosBusqueda();
		$sJavaBusqueda=f111_JavaScriptBusqueda(3005);
		break;
		case 'saiu07idvalidad':
		require $APP->rutacomun.'lib111.php';
		$sTabla=f111_TablaDetalleBusquedas($aParametrosB, $objDB);
		$sTitulo='Busqueda de terceros';
		$sParams=f111_ParametrosBusqueda();
		$sJavaBusqueda=f111_JavaScriptBusqueda(3005);
		break;
		}
	$sTitulo='<h2>'.$ETI['titulo_3005'].' - '.$sTitulo.'</h2>';
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97titulo', 'innerHTML', $sTitulo);
	$objResponse->assign('div_97params', 'innerHTML', $sParams);
	$objResponse->assign('div_97tabla', 'innerHTML', $sTabla);
	$objResponse->setFunction('paginarbusqueda','',$sJavaBusqueda);
	$objResponse->call('expandesector(97)');
	return $objResponse;
	}
function f3005_HtmlBusqueda($aParametros){
	$_SESSION['u_ultimominuto']=iminutoavance();
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	$sError='';
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$sDetalle='';
	switch($aParametros[100]){
		case 'saiu05idsolicitante':
		require $APP->rutacomun.'lib111.php';
		$sDetalle=f111_TablaDetalleBusquedas($aParametros, $objDB);
		break;
		case 'saiu05idinteresado':
		require $APP->rutacomun.'lib111.php';
		$sDetalle=f111_TablaDetalleBusquedas($aParametros, $objDB);
		break;
		case 'saiu05idresponsable':
		require $APP->rutacomun.'lib111.php';
		$sDetalle=f111_TablaDetalleBusquedas($aParametros, $objDB);
		break;
		case 'saiu06idusuario':
		require $APP->rutacomun.'lib111.php';
		$sDetalle=f111_TablaDetalleBusquedas($aParametros, $objDB);
		break;
		case 'saiu07idusuario':
		require $APP->rutacomun.'lib111.php';
		$sDetalle=f111_TablaDetalleBusquedas($aParametros, $objDB);
		break;
		case 'saiu07idvalidad':
		require $APP->rutacomun.'lib111.php';
		$sDetalle=f111_TablaDetalleBusquedas($aParametros, $objDB);
		break;
		}
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97tabla', 'innerHTML', $sDetalle);
	return $objResponse;
	}
function f3005_TablaDetalleV2($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3005=$APP->rutacomun.'lg/lg_3005_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3005)){$mensajes_3005=$APP->rutacomun.'lg/lg_3005_es.php';}
	require $mensajes_todas;
	require $mensajes_3005;
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	if (isset($aParametros[100])==0){$aParametros[100]=$_SESSION['unad_id_tercero'];}
	if (isset($aParametros[101])==0){$aParametros[101]=1;}
	if (isset($aParametros[102])==0){$aParametros[102]=20;}
	if (isset($aParametros[103])==0){$aParametros[103]='';}
	if (isset($aParametros[104])==0){$aParametros[104]='';}
	//$aParametros[103]=numeros_validar($aParametros[103]);
	$idTercero=$aParametros[100];
	$sDebug='';
	$pagina=$aParametros[101];
	$lineastabla=$aParametros[102];
	$sNombre=$aParametros[103];
	$iAgno=$aParametros[104];
	$bAbierta=true;
	//$sSQL='SELECT Campo FROM Tabla WHERE Id='.$sValorId;
	//$tabla=$objDB->ejecutasql($sSQL);
	//if ($objDB->nf($tabla)>0){
		//$fila=$objDB->sf($tabla);
		//if ($fila['Campo']!='S'){$bAbierta=true;}
		//}
	$sLeyenda='';
	if ($iAgno==''){$sLeyenda='No ha seleccionado un a&ntilde;o a consultar';}
	if ($sLeyenda!=''){
		$sLeyenda='<div class="salto1px"></div>
		<div class="GrupoCamposAyuda">
		'.$sLeyenda.'
		<div class="salto1px"></div>
		</div>';
		return array($sLeyenda.'<input id="paginaf3005" name="paginaf3005" type="hidden" value="'.$pagina.'"/><input id="lppf3005" name="lppf3005" type="hidden" value="'.$lineastabla.'"/>', $sDebug);
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
	/*
	$sTitulos='Agno, Mes, Tiporadicado, Consec, Id, Origenagno, Origenmes, Origenid, Dia, Hora, Minuto, Estado, Medio, Tiposolorigen, Temaorigen, Temafin, Tiposolfin, Solicitante, Interesado, Tipointeresado, Rptaforma, Rptacorreo, Rptadireccion, Costogenera, Costovalor, Costorefpago, Prioridad, Zona, Cead, Numref, Detalle, Infocomplemento, Responsable, Escuela, Programa, Periodo, Curso, Grupo, Tiemprespdias, Tiempresphoras, Fecharespprob, Respuesta, Moduloproc, Entificadormod, Numradicado';
	$sSQL='SELECT TB.saiu05agno, TB.saiu05mes, T3.saiu16nombre, TB.saiu05consec, TB.saiu05id, TB.saiu05origenagno, TB.saiu05origenmes, TB.saiu05origenid, TB.saiu05dia, TB.saiu05hora, TB.saiu05minuto, T12.saiu11nombre, T13.bita01nombre, T14.saiu02titulo, T15.saiu03titulo, T16.saiu03titulo, T17.saiu02titulo, T18.unad11razonsocial AS C18_nombre, T19.unad11razonsocial AS C19_nombre, T20.bita07nombre, T21.saiu12nombre, TB.saiu05rptacorreo, TB.saiu05rptadireccion, TB.saiu05costogenera, TB.saiu05costovalor, TB.saiu05costorefpago, T27.bita03nombre, T28.unad23nombre, T29.unad24nombre, TB.saiu05numref, TB.saiu05detalle, TB.saiu05infocomplemento, T33.unad11razonsocial AS C33_nombre, T34.core12nombre, T35.core09nombre, T36.exte02nombre, T37.unad40nombre, T38.core06consec, TB.saiu05tiemprespdias, TB.saiu05tiempresphoras, TB.saiu05fecharespprob, TB.saiu05respuesta, TB.saiu05idmoduloproc, TB.saiu05identificadormod, TB.saiu05numradicado, TB.saiu05tiporadicado, TB.saiu05estado, TB.saiu05idmedio, TB.saiu05idtiposolorigen, TB.saiu05idtemaorigen, TB.saiu05idtemafin, TB.saiu05idtiposolfin, TB.saiu05idsolicitante, T18.unad11tipodoc AS C18_td, T18.unad11doc AS C18_doc, TB.saiu05idinteresado, T19.unad11tipodoc AS C19_td, T19.unad11doc AS C19_doc, TB.saiu05tipointeresado, TB.saiu05rptaforma, TB.saiu05prioridad, TB.saiu05idzona, TB.saiu05cead, TB.saiu05idresponsable, T33.unad11tipodoc AS C33_td, T33.unad11doc AS C33_doc, TB.saiu05idescuela, TB.saiu05idprograma, TB.saiu05idperiodo, TB.saiu05idcurso, TB.saiu05idgrupo 
	FROM saiu05solicitud AS TB, saiu16tiporadicado AS T3, saiu11estadosol AS T12, bita01tiposolicitud AS T13, saiu02tiposol AS T14, saiu03temasol AS T15, saiu03temasol AS T16, saiu02tiposol AS T17, unad11terceros AS T18, unad11terceros AS T19, bita07tiposolicitante AS T20, saiu12formarespuesta AS T21, bita03prioridad AS T27, unad23zona AS T28, unad24sede AS T29, unad11terceros AS T33, core12escuela AS T34, core09programa AS T35, exte02per_aca AS T36, unad40curso AS T37, core06grupos AS T38 
	WHERE '.$sSQLadd1.' TB.saiu05tiporadicado=T3.saiu16id AND TB.saiu05estado=T12.saiu11id AND TB.saiu05idmedio=T13.bita01id AND TB.saiu05idtiposolorigen=T14.saiu02id AND TB.saiu05idtemaorigen=T15.saiu03id AND TB.saiu05idtemafin=T16.saiu03id AND TB.saiu05idtiposolfin=T17.saiu02id AND TB.saiu05idsolicitante=T18.unad11id AND TB.saiu05idinteresado=T19.unad11id AND TB.saiu05tipointeresado=T20.bita07id AND TB.saiu05rptaforma=T21.saiu12id AND TB.saiu05prioridad=T27.bita03id AND TB.saiu05idzona=T28.unad23id AND TB.saiu05cead=T29.unad24id AND TB.saiu05idresponsable=T33.unad11id AND TB.saiu05idescuela=T34.core12id AND TB.saiu05idprograma=T35.core09id AND TB.saiu05idperiodo=T36.exte02id AND TB.saiu05idcurso=T37.unad40id AND TB.saiu05idgrupo=T38.core06id '.$sSQLadd.'
	ORDER BY TB.saiu05agno, TB.saiu05mes, TB.saiu05tiporadicado, TB.saiu05consec';
	*/
	//Las solicitudes no estan en una tabla en contenedores...
	$aTablas=array();
	$iTablas=0;
	$iNumSolicitudes=0;
	$sSQL='SELECT saiu15agno, saiu15mes, SUM(saiu15numsolicitudes) AS Solicitudes 
	FROM saiu15historico 
	WHERE saiu15agno='.$iAgno.' AND saiu15tiporadicado=1
	GROUP BY saiu15agno, saiu15mes';
	if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Historico: '.$sSQL.'<br>';}
	$tabla15=$objDB->ejecutasql($sSQL);
	while($fila15=$objDB->sf($tabla15)){
		$iNumSolicitudes=$iNumSolicitudes+$fila15['Solicitudes'];
		if ($fila15['saiu15mes']<10){
			$sContenedor=$fila15['saiu15agno'].'0'.$fila15['saiu15mes'];
			}else{
			$sContenedor=$fila15['saiu15agno'].$fila15['saiu15mes'];
			}
		$iTablas++;
		$aTablas[$iTablas]=$sContenedor;
	}
	$registros=$iNumSolicitudes;
	$limite='';
	if ((($registros-1)/$lineastabla)<($pagina-1)){$pagina=(int)(($registros-1)/$lineastabla)+1;}
	if ($registros>$lineastabla){
		$rbase=($pagina-1)*$lineastabla;
		$limite=' LIMIT '.$rbase.', '.$lineastabla;
	}
	$sTitulos='Agno, Mes, Dia, Consecutivo, Estado, Hora, Minuto';
	$sSQL='';
	$sErrConsulta='';
	for ($k=1;$k<=$iTablas;$k++){
		if ($k!=1){$sSQL=$sSQL.' UNION ';}
		$sContenedor=$aTablas[$k];
		$sSQL=$sSQL.'SELECT TB.saiu05agno, TB.saiu05mes, TB.saiu05dia, TB.saiu05consec, T12.saiu11nombre, TB.saiu05hora, TB.saiu05minuto, TB.saiu05id, TB.saiu05estado
		FROM saiu05solicitud_'.$sContenedor.' AS TB, saiu11estadosol AS T12 
		WHERE TB.saiu05tiporadicado=1 AND TB.saiu05estado=T12.saiu11id';
	}
	if ($sSQL != '') {
		$sSQL=$sSQL.' ORDER BY saiu05agno DESC, saiu05mes DESC, saiu05consec DESC'.$limite;
		$sSQLlista=str_replace("'","|",$sSQL);
		$sSQLlista=str_replace('"',"|",$sSQLlista);
		$sErrConsulta='<input id="consulta_3005" name="consulta_3005" type="hidden" value="'.$sSQLlista.'"/>
		<input id="titulos_3005" name="titulos_3005" type="hidden" value="'.$sTitulos.'"/>';
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Consulta 3005: '.$sSQL.'<br>';}
		$tabladetalle=$objDB->ejecutasql($sSQL);
		if ($tabladetalle==false){
			$registros=0;
			$sErrConsulta=$sErrConsulta.'..<input id="err" name="err" type="hidden" value="'.$sSQL.' '.$objDB->serror.'"/>';
			//$sLeyenda=$sSQL;
		}
	}
	$res=$sErrConsulta.$sLeyenda.'<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">
	<tr class="fondoazul">
	<td><b>'.$ETI['msg_numsolicitud'].'</b></td>
	<td colspan="2"><b>'.$ETI['saiu05dia'].'</b></td>
	<td><b>'.$ETI['saiu05estado'].'</b></td>
	<td align="right">
	'.html_paginador('paginaf3005', $registros, $lineastabla, $pagina, 'paginarf3005()').'
	'.html_lpp('lppf3005', $lineastabla, 'paginarf3005()').'
	</td>
	</tr>';
	if ($sSQL != '') {
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
			$et_NumSol=f3000_NumSolicitud($filadet['saiu05agno'], $filadet['saiu05mes'], $filadet['saiu05consec']);
			$et_saiu05dia='';
			$et_saiu05dia=fecha_armar($filadet['saiu05dia'], $filadet['saiu05mes'], $filadet['saiu05agno']);
			$et_saiu05hora=html_TablaHoraMin($filadet['saiu05hora'], $filadet['saiu05minuto']);
			//$et_saiu05fecharespprob='';
			//if ($filadet['saiu05fecharespprob']!='00/00/0000'){$et_saiu05fecharespprob=$filadet['saiu05fecharespprob'];}
			if ($bAbierta){
				$sLink='<a href="javascript:cargaridf3005('.$filadet['saiu05agno'].', '.$filadet['saiu05mes'].', '.$filadet['saiu05id'].')" class="lnkresalte">'.$ETI['lnk_cargar'].'</a>';
				}
			$res=$res.'<tr'.$sClass.'>
			<td>'.$sPrefijo.$et_NumSol.$sSufijo.'</td>
			<td>'.$sPrefijo.$et_saiu05dia.$sSufijo.'</td>
			<td>'.$sPrefijo.$et_saiu05hora.$sSufijo.'</td>
			<td>'.$sPrefijo.cadena_notildes($filadet['saiu11nombre']).$sSufijo.'</td>
			<td>'.$sLink.'</td>
			</tr>';
		}
		$objDB->liberar($tabladetalle);
	}
	$res=$res.'</table>';
	return array(utf8_encode($res), $sDebug);
}
function f3005_HtmlTabla($aParametros){
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
	list($sDetalle, $sDebugTabla)=f3005_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f3005detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f3005_db_CargarPadre($DATA, $objDB, $bDebug=false){
	$sError='';
	$iTipoError=0;
	$sDebug='';
	require './app.php';
	$DATA['saiu05idsolicitante_td']=$APP->tipo_doc;
	$DATA['saiu05idsolicitante_doc']='';
	$DATA['saiu05idinteresado_td']=$APP->tipo_doc;
	$DATA['saiu05idinteresado_doc']='';
	$DATA['saiu05idresponsable_td']=$APP->tipo_doc;
	$DATA['saiu05idresponsable_doc']='';
	if ($DATA['paso']==1){
		$sSQLcondi='saiu05agno='.$DATA['saiu05agno'].' AND saiu05mes='.$DATA['saiu05mes'].' AND saiu05tiporadicado='.$DATA['saiu05tiporadicado'].' AND saiu05consec='.$DATA['saiu05consec'].'';
		}else{
		$sSQLcondi='saiu05id='.$DATA['saiu05id'].'';
		}
	$sSQL='SELECT * FROM saiu05solicitud WHERE '.$sSQLcondi;
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$DATA['saiu05agno']=$fila['saiu05agno'];
		$DATA['saiu05mes']=$fila['saiu05mes'];
		$DATA['saiu05tiporadicado']=$fila['saiu05tiporadicado'];
		$DATA['saiu05consec']=$fila['saiu05consec'];
		$DATA['saiu05id']=$fila['saiu05id'];
		$DATA['saiu05origenagno']=$fila['saiu05origenagno'];
		$DATA['saiu05origenmes']=$fila['saiu05origenmes'];
		$DATA['saiu05origenid']=$fila['saiu05origenid'];
		$DATA['saiu05dia']=$fila['saiu05dia'];
		$DATA['saiu05hora']=$fila['saiu05hora'];
		$DATA['saiu05minuto']=$fila['saiu05minuto'];
		$DATA['saiu05estado']=$fila['saiu05estado'];
		$DATA['saiu05idmedio']=$fila['saiu05idmedio'];
		$DATA['saiu05idtiposolorigen']=$fila['saiu05idtiposolorigen'];
		$DATA['saiu05idtemaorigen']=$fila['saiu05idtemaorigen'];
		$DATA['saiu05idtemafin']=$fila['saiu05idtemafin'];
		$DATA['saiu05idtiposolfin']=$fila['saiu05idtiposolfin'];
		$DATA['saiu05idsolicitante']=$fila['saiu05idsolicitante'];
		$DATA['saiu05idinteresado']=$fila['saiu05idinteresado'];
		$DATA['saiu05tipointeresado']=$fila['saiu05tipointeresado'];
		$DATA['saiu05rptaforma']=$fila['saiu05rptaforma'];
		$DATA['saiu05rptacorreo']=$fila['saiu05rptacorreo'];
		$DATA['saiu05rptadireccion']=$fila['saiu05rptadireccion'];
		$DATA['saiu05costogenera']=$fila['saiu05costogenera'];
		$DATA['saiu05costovalor']=$fila['saiu05costovalor'];
		$DATA['saiu05costorefpago']=$fila['saiu05costorefpago'];
		$DATA['saiu05prioridad']=$fila['saiu05prioridad'];
		$DATA['saiu05idzona']=$fila['saiu05idzona'];
		$DATA['saiu05cead']=$fila['saiu05cead'];
		$DATA['saiu05numref']=$fila['saiu05numref'];
		$DATA['saiu05detalle']=$fila['saiu05detalle'];
		$DATA['saiu05infocomplemento']=$fila['saiu05infocomplemento'];
		$DATA['saiu05idresponsable']=$fila['saiu05idresponsable'];
		$DATA['saiu05idescuela']=$fila['saiu05idescuela'];
		$DATA['saiu05idprograma']=$fila['saiu05idprograma'];
		$DATA['saiu05idperiodo']=$fila['saiu05idperiodo'];
		$DATA['saiu05idcurso']=$fila['saiu05idcurso'];
		$DATA['saiu05idgrupo']=$fila['saiu05idgrupo'];
		$DATA['saiu05tiemprespdias']=$fila['saiu05tiemprespdias'];
		$DATA['saiu05tiempresphoras']=$fila['saiu05tiempresphoras'];
		$DATA['saiu05fecharespprob']=$fila['saiu05fecharespprob'];
		$DATA['saiu05respuesta']=$fila['saiu05respuesta'];
		$DATA['saiu05idmoduloproc']=$fila['saiu05idmoduloproc'];
		$DATA['saiu05identificadormod']=$fila['saiu05identificadormod'];
		$DATA['saiu05numradicado']=$fila['saiu05numradicado'];
		$bcargo=true;
		$DATA['paso']=2;
		$DATA['boculta3005']=0;
		$bLimpiaHijos=true;
		}else{
		$DATA['paso']=0;
		}
	return array($DATA, $sError, $iTipoError, $sDebug);
	}
function f3005_db_GuardarV2($DATA, $objDB, $bDebug=false){
	$iCodModulo=3005;
	$bAudita[2]=true;
	$bAudita[3]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3005=$APP->rutacomun.'lg/lg_3005_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3005)){$mensajes_3005=$APP->rutacomun.'lg/lg_3005_es.php';}
	require $mensajes_todas;
	require $mensajes_3005;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	// -- Se inicia validando todas las posibles entradas de usuario.
	/*
	if (isset($DATA['saiu05consec'])==0){$DATA['saiu05consec']='';}
	if (isset($DATA['saiu05id'])==0){$DATA['saiu05id']='';}
	if (isset($DATA['saiu05dia'])==0){$DATA['saiu05dia']='';}
	if (isset($DATA['saiu05idtiposolorigen'])==0){$DATA['saiu05idtiposolorigen']='';}
	if (isset($DATA['saiu05idtemaorigen'])==0){$DATA['saiu05idtemaorigen']='';}
	if (isset($DATA['saiu05idinteresado'])==0){$DATA['saiu05idinteresado']='';}
	if (isset($DATA['saiu05tipointeresado'])==0){$DATA['saiu05tipointeresado']='';}
	if (isset($DATA['saiu05rptaforma'])==0){$DATA['saiu05rptaforma']='';}
	if (isset($DATA['saiu05rptacorreo'])==0){$DATA['saiu05rptacorreo']='';}
	if (isset($DATA['saiu05rptadireccion'])==0){$DATA['saiu05rptadireccion']='';}
	if (isset($DATA['saiu05costogenera'])==0){$DATA['saiu05costogenera']='';}
	if (isset($DATA['saiu05costovalor'])==0){$DATA['saiu05costovalor']='';}
	if (isset($DATA['saiu05detalle'])==0){$DATA['saiu05detalle']='';}
	if (isset($DATA['saiu05idresponsable'])==0){$DATA['saiu05idresponsable']='';}
	if (isset($DATA['saiu05idmoduloproc'])==0){$DATA['saiu05idmoduloproc']='';}
	if (isset($DATA['saiu05identificadormod'])==0){$DATA['saiu05identificadormod']='';}
	if (isset($DATA['saiu05numradicado'])==0){$DATA['saiu05numradicado']='';}
	*/
	$DATA['saiu05consec']=numeros_validar($DATA['saiu05consec']);
	$DATA['saiu05hora']=numeros_validar($DATA['saiu05hora']);
	$DATA['saiu05minuto']=numeros_validar($DATA['saiu05minuto']);
	$DATA['saiu05idtiposolorigen']=numeros_validar($DATA['saiu05idtiposolorigen']);
	$DATA['saiu05idtemaorigen']=numeros_validar($DATA['saiu05idtemaorigen']);
	$DATA['saiu05tipointeresado']=numeros_validar($DATA['saiu05tipointeresado']);
	$DATA['saiu05rptaforma']=numeros_validar($DATA['saiu05rptaforma']);
	$DATA['saiu05rptacorreo']=htmlspecialchars(trim($DATA['saiu05rptacorreo']));
	$DATA['saiu05rptadireccion']=htmlspecialchars(trim($DATA['saiu05rptadireccion']));
	$DATA['saiu05costogenera']=numeros_validar($DATA['saiu05costogenera']);
	$DATA['saiu05costovalor']=numeros_validar($DATA['saiu05costovalor'],true);
	$DATA['saiu05costorefpago']=htmlspecialchars(trim($DATA['saiu05costorefpago']));
	$DATA['saiu05numref']=htmlspecialchars(trim($DATA['saiu05numref']));
	$DATA['saiu05detalle']=htmlspecialchars(trim($DATA['saiu05detalle']));
	$DATA['saiu05infocomplemento']=htmlspecialchars(trim($DATA['saiu05infocomplemento']));
	$DATA['saiu05respuesta']=htmlspecialchars(trim($DATA['saiu05respuesta']));
	$DATA['saiu05idmoduloproc']=numeros_validar($DATA['saiu05idmoduloproc']);
	$DATA['saiu05identificadormod']=numeros_validar($DATA['saiu05identificadormod']);
	$DATA['saiu05numradicado']=numeros_validar($DATA['saiu05numradicado']);
	$DATA['saiu05idcategoria']=numeros_validar($DATA['saiu05idcategoria']);
	// -- Se inicializan las variables que puedan pasar vacias {Especialmente números}.
	if ($DATA['saiu05origenid']==''){$DATA['saiu05origenid']=0;}
	//if ($DATA['saiu05hora']==''){$DATA['saiu05hora']=0;}
	//if ($DATA['saiu05minuto']==''){$DATA['saiu05minuto']=0;}
	if ($DATA['saiu05estado']==''){$DATA['saiu05estado']=0;}
	if ($DATA['saiu05idmedio']==''){$DATA['saiu05idmedio']=0;}
	//if ($DATA['saiu05idtiposolorigen']==''){$DATA['saiu05idtiposolorigen']=0;}
	//if ($DATA['saiu05idtemaorigen']==''){$DATA['saiu05idtemaorigen']=0;}
	//if ($DATA['saiu05tipointeresado']==''){$DATA['saiu05tipointeresado']=0;}
	//if ($DATA['saiu05rptaforma']==''){$DATA['saiu05rptaforma']=0;}
	//if ($DATA['saiu05costogenera']==''){$DATA['saiu05costogenera']=0;}
	//if ($DATA['saiu05costovalor']==''){$DATA['saiu05costovalor']=0;}
	if ($DATA['saiu05idmoduloproc']==''){$DATA['saiu05idmoduloproc']=0;}
	if ($DATA['saiu05identificadormod']==''){$DATA['saiu05identificadormod']=0;}
	//if ($DATA['saiu05numradicado']==''){$DATA['saiu05numradicado']=0;}
	// -- Seccion para validar los posibles causales de error.
	$sSepara=', ';
	if (true){
		/*
		if ($DATA['saiu05numradicado']==''){$sError=$ERR['saiu05numradicado'].$sSepara.$sError;}
		if ($DATA['saiu05identificadormod']==''){$sError=$ERR['saiu05identificadormod'].$sSepara.$sError;}
		if ($DATA['saiu05idmoduloproc']==''){$sError=$ERR['saiu05idmoduloproc'].$sSepara.$sError;}
		//if (!fecha_esvalida($DATA['saiu05fecharespprob'])){
			//$DATA['saiu05fecharespprob']='00/00/0000';
			//$sError=$ERR['saiu05fecharespprob'].$sSepara.$sError;
			//}
		if ($DATA['saiu05idresponsable']==0){$sError=$ERR['saiu05idresponsable'].$sSepara.$sError;}
		if ($DATA['saiu05costovalor']==''){$sError=$ERR['saiu05costovalor'].$sSepara.$sError;}
		if ($DATA['saiu05costogenera']==''){$sError=$ERR['saiu05costogenera'].$sSepara.$sError;}
		if ($DATA['saiu05rptaforma']==''){$sError=$ERR['saiu05rptaforma'].$sSepara.$sError;}
		if ($DATA['saiu05tipointeresado']==''){$sError=$ERR['saiu05tipointeresado'].$sSepara.$sError;}
		if ($DATA['saiu05idinteresado']==0){$sError=$ERR['saiu05idinteresado'].$sSepara.$sError;}
		if ($DATA['saiu05idsolicitante']==0){$sError=$ERR['saiu05idsolicitante'].$sSepara.$sError;}
		if ($DATA['saiu05dia']==0){
			//$DATA['saiu05dia']=fecha_DiaMod();
			$sError=$ERR['saiu05dia'].$sSepara.$sError;
			}
		*/
		if ($DATA['saiu05detalle']==''){$sError=$ERR['saiu05detalle'].$sSepara.$sError;}
		if ($DATA['saiu05idtiposolorigen']==''){
			$sError=$ERR['saiu05idtiposolorigen_2'].$sSepara.$sError;
			}else{
			if ($DATA['saiu05idtemaorigen']==''){$sError=$ERR['saiu05idtemaorigen_2'].$sSepara.$sError;}
			}
		if ($DATA['saiu05rptaforma']==1){
			if (correo_VerificarDireccion($DATA['saiu05rptacorreo'])){
				}else{
				$sError=$ERR['saiu05rptacorreo'].$sSepara.$sError;
				}
			}
		if ($DATA['saiu05rptaforma']==2){
			if ($DATA['saiu05rptadireccion']==''){$sError=$ERR['saiu05rptadireccion'].$sSepara.$sError;}
			}
		if ($DATA['saiu05idmedio']==''){$sError=$ERR['saiu05idmedio'].$sSepara.$sError;}
		if ($DATA['saiu05idcategoria']==''){$sError=$ERR['saiu05idcategoria'].$sSepara.$sError;}
		//Fin de las valiaciones NO LLAVE.
		}
	//Valiaciones de campos obligatorios en todo guardar.
	// -- Se verifican los valores de campos de otras tablas.
	if ($DATA['saiu05idresponsable_doc']!=''){
		if ($sError==''){$sError=tabla_terceros_existe($DATA['saiu05idresponsable_td'], $DATA['saiu05idresponsable_doc'], $objDB, 'El tercero Responsable ');}
		if ($sError==''){
			list($sError, $sInfo)=tercero_Bloqueado($DATA['saiu05idresponsable'], $objDB);
			if ($sInfo!=''){$sError=$sError.'<br>'.$sInfo;}
			}
		}
	if ($DATA['saiu05idinteresado_doc']!=''){
		if ($sError==''){$sError=tabla_terceros_existe($DATA['saiu05idinteresado_td'], $DATA['saiu05idinteresado_doc'], $objDB, 'El tercero Interesado ');}
		if ($sError==''){
			list($sError, $sInfo)=tercero_Bloqueado($DATA['saiu05idinteresado'], $objDB);
			if ($sInfo!=''){$sError=$sError.'<br>'.$sInfo;}
			}
		}
	if ($DATA['saiu05idsolicitante_doc']!=''){
		if ($sError==''){$sError=tabla_terceros_existe($DATA['saiu05idsolicitante_td'], $DATA['saiu05idsolicitante_doc'], $objDB, 'El tercero Solicitante ');}
		if ($sError==''){
			list($sError, $sInfo)=tercero_Bloqueado($DATA['saiu05idsolicitante'], $objDB);
			if ($sInfo!=''){$sError=$sError.'<br>'.$sInfo;}
			}
		}
	if ($sError==''){
		if(strlen($DATA['saiu05mes'])==2){
			$sTabla05='saiu05solicitud_'.$DATA['saiu05agno'].$DATA['saiu05mes'];
			}else{
			$sTabla05='saiu05solicitud_'.$DATA['saiu05agno'].'0'.$DATA['saiu05mes'];
			}
		if ($DATA['paso']==10){
			//El codigo no es posible que sea puesto por nadie.
			//$bQuitarCodigo=true;
			$DATA['saiu05consec']=tabla_consecutivo($sTabla05, 'saiu05consec', 'saiu05agno='.$DATA['saiu05agno'].' AND saiu05mes='.$DATA['saiu05mes'].' AND saiu05tiporadicado='.$DATA['saiu05tiporadicado'].'', $objDB);
				if ($DATA['saiu05consec']==-1){$sError=$objDB->serror;}
			if ($sError==''){
				$sSQL='SELECT 1 FROM '.$sTabla05.' WHERE saiu05agno='.$DATA['saiu05agno'].' AND saiu05mes='.$DATA['saiu05mes'].' AND saiu05tiporadicado='.$DATA['saiu05tiporadicado'].' AND saiu05consec='.$DATA['saiu05consec'].'';
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
			$DATA['saiu05id']=tabla_consecutivo($sTabla05,'saiu05id', '', $objDB);
			if ($DATA['saiu05id']==-1){$sError=$objDB->serror;}
			}
		}
	$bCalularTotales=false;
	if ($sError==''){
		if (get_magic_quotes_gpc()==1){$DATA['saiu05detalle']=stripslashes($DATA['saiu05detalle']);}
		//Si el campo saiu05detalle permite html quite la linea htmlspecialchars para el campo y habilite la siguiente linea:
		//$saiu05detalle=addslashes($DATA['saiu05detalle']);
		$saiu05detalle=str_replace('"', '\"', $DATA['saiu05detalle']);
		if (get_magic_quotes_gpc()==1){$DATA['saiu05infocomplemento']=stripslashes($DATA['saiu05infocomplemento']);}
		//Si el campo saiu05infocomplemento permite html quite la linea htmlspecialchars para el campo y habilite la siguiente linea:
		//$saiu05infocomplemento=addslashes($DATA['saiu05infocomplemento']);
		$saiu05infocomplemento=str_replace('"', '\"', $DATA['saiu05infocomplemento']);
		if (get_magic_quotes_gpc()==1){$DATA['saiu05respuesta']=stripslashes($DATA['saiu05respuesta']);}
		//Si el campo saiu05respuesta permite html quite la linea htmlspecialchars para el campo y habilite la siguiente linea:
		//$saiu05respuesta=addslashes($DATA['saiu05respuesta']);
		$saiu05respuesta=str_replace('"', '\"', $DATA['saiu05respuesta']);
		$idunidad = 0;
		$idgrupotrabajo = 0;
		$idresponsable = 0;
		$sSQL = 'SELECT saiu03idunidadresp1, saiu03idequiporesp1, saiu03idliderrespon1
		FROM saiu03temasol
		WHERE saiu03id = ' . $DATA['saiu05idtemaorigen'] . '';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' Consulta responsable solicitud ' . $sSQL . '<br>';
		}
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			$idunidad = $fila['saiu03idunidadresp1'];
			$idgrupotrabajo = $fila['saiu03idequiporesp1'];
			$idresponsable = $fila['saiu03idliderrespon1'];
			if ($idgrupotrabajo > 0) {
				$sSQL = 'SELECT bita28idtercero
				FROM bita28eqipoparte
				WHERE bita28idequipotrab = ' . $idgrupotrabajo . ' AND bita28activo = "S"' . '';
				$tabla = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($tabla) > 0) {
					$aEquipo = array();
					$sEquipo = '';
					while ($fila = $objDB->sf($tabla)) {
						$aEquipo[] = $fila['bita28idtercero'];
					}
					$sEquipo = implode(',',$aEquipo);
					$sSQL = 'SELECT saiu05idresponsable, COUNT(saiu05id) AS asignaciones
					FROM ' . $sTabla05 . '
					WHERE saiu05idresponsable IN (' . $sEquipo . ')
					GROUP BY saiu05idresponsable
					ORDER BY asignaciones';
					$tabla = $objDB->ejecutasql($sSQL);
					$iResponsables = $objDB->nf($tabla);
					if ($iResponsables >= count($aEquipo)) {
						$fila = $objDB->sf($tabla);
						$idresponsable = $fila['saiu05idresponsable'];
					} else {
						$aResponsables = array();
						while($fila = $objDB->sf($tabla)) {
							$aResponsables[] = $fila['saiu05idresponsable'];
						}
						$aSinAsignar = array_values(array_diff($aEquipo, $aResponsables));
						$idresponsable = $aSinAsignar[0];
					}
				}
			}
		}
		$bpasa=false;
		if ($DATA['paso']==10){
			$DATA['saiu05agno']=fecha_agno();
			$DATA['saiu05mes']=fecha_mes();
			$DATA['saiu05dia']=fecha_dia();
			$DATA['saiu05origenagno']=0;
			$DATA['saiu05origenmes']=0;
			$DATA['saiu05origenid']=0;
			$DATA['saiu05hora']=fecha_hora();
			$DATA['saiu05minuto']=fecha_minuto();
			$DATA['saiu05estado']=0; //Solicitado
			//$DATA['saiu05idmedio']=0;
			$DATA['saiu05idtemafin']=0;
			$DATA['saiu05idtiposolfin']=0;
			//$DATA['saiu05idsolicitante']=0; //$_SESSION['u_idtercero'];
			$DATA['saiu05costogenera']=0;
			$DATA['saiu05costorefpago']='';
			$DATA['saiu05prioridad']=0;
			$DATA['saiu05idzona']=0;
			$DATA['saiu05cead']=0;
			$DATA['saiu05numref']=$DATA['saiu05agno'].$DATA['saiu05mes'].'-'.$DATA['saiu05id'].'-'.strtoupper(substr(md5($DATA['saiu05id']),0,5));
			$DATA['saiu05idescuela']=0;
			$DATA['saiu05idprograma']=0;
			$DATA['saiu05idperiodo']=0;
			$DATA['saiu05idcurso']=0;
			$DATA['saiu05idgrupo']=0;
			$DATA['saiu05tiemprespdias']=0;
			$DATA['saiu05tiempresphoras']=0;
			$DATA['saiu05fecharespprob']=0; //fecha_hoy();
			$DATA['saiu05numradicado']=0;
			$DATA['saiu05idcategoria']=0;
			$DATA['saiu05idunidadresp']=$idunidad;
			$DATA['saiu05idequiporesp']=$idgrupotrabajo;
			$DATA['saiu05idresponsable']=$idresponsable;
			$sCampos3005='saiu05agno, saiu05mes, saiu05tiporadicado, saiu05consec, saiu05id, 
saiu05origenagno, saiu05origenmes, saiu05origenid, saiu05dia, saiu05hora, 
saiu05minuto, saiu05estado, saiu05idmedio, saiu05idtiposolorigen, saiu05idtemaorigen, 
saiu05idtemafin, saiu05idtiposolfin, saiu05idsolicitante, saiu05idinteresado, saiu05tipointeresado, 
saiu05rptaforma, saiu05rptacorreo, saiu05rptadireccion, saiu05costogenera, saiu05costovalor, 
saiu05costorefpago, saiu05prioridad, saiu05idzona, saiu05cead, saiu05numref, 
saiu05detalle, saiu05infocomplemento, saiu05idunidadresp, saiu05idequiporesp, saiu05idresponsable, saiu05idescuela, saiu05idprograma, 
saiu05idperiodo, saiu05idcurso, saiu05idgrupo, saiu05tiemprespdias, saiu05tiempresphoras, 
saiu05fecharespprob, saiu05respuesta, saiu05idmoduloproc, saiu05identificadormod, saiu05numradicado, saiu05idcategoria';
			$sValores3005=''.$DATA['saiu05agno'].', '.$DATA['saiu05mes'].', '.$DATA['saiu05tiporadicado'].', '.$DATA['saiu05consec'].', '.$DATA['saiu05id'].', 
'.$DATA['saiu05origenagno'].', '.$DATA['saiu05origenmes'].', '.$DATA['saiu05origenid'].', '.$DATA['saiu05dia'].', '.$DATA['saiu05hora'].', 
'.$DATA['saiu05minuto'].', '.$DATA['saiu05estado'].', '.$DATA['saiu05idmedio'].', '.$DATA['saiu05idtiposolorigen'].', '.$DATA['saiu05idtemaorigen'].', 
'.$DATA['saiu05idtemafin'].', '.$DATA['saiu05idtiposolfin'].', '.$DATA['saiu05idsolicitante'].', '.$DATA['saiu05idinteresado'].', '.$DATA['saiu05tipointeresado'].', 
'.$DATA['saiu05rptaforma'].', "'.$DATA['saiu05rptacorreo'].'", "'.$DATA['saiu05rptadireccion'].'", '.$DATA['saiu05costogenera'].', '.$DATA['saiu05costovalor'].', 
"'.$DATA['saiu05costorefpago'].'", '.$DATA['saiu05prioridad'].', '.$DATA['saiu05idzona'].', '.$DATA['saiu05cead'].', "'.$DATA['saiu05numref'].'", 
"'.$saiu05detalle.'", "'.$saiu05infocomplemento.'", '.$DATA['saiu05idunidadresp'].', '.$DATA['saiu05idequiporesp'].', '.$DATA['saiu05idresponsable'].', '.$DATA['saiu05idescuela'].', '.$DATA['saiu05idprograma'].', 
'.$DATA['saiu05idperiodo'].', '.$DATA['saiu05idcurso'].', '.$DATA['saiu05idgrupo'].', '.$DATA['saiu05tiemprespdias'].', '.$DATA['saiu05tiempresphoras'].', 
"'.$DATA['saiu05fecharespprob'].'", "'.$saiu05respuesta.'", '.$DATA['saiu05idmoduloproc'].', '.$DATA['saiu05identificadormod'].', '.$DATA['saiu05numradicado'].', '.$DATA['saiu05idcategoria'].'';
			if ($APP->utf8==1){
				$sSQL='INSERT INTO '.$sTabla05.' ('.$sCampos3005.') VALUES ('.utf8_encode($sValores3005).');';
				$sdetalle=$sCampos3005.'['.utf8_encode($sValores3005).']';
				}else{
				$sSQL='INSERT INTO '.$sTabla05.' ('.$sCampos3005.') VALUES ('.$sValores3005.');';
				$sdetalle=$sCampos3005.'['.$sValores3005.']';
				}
			$idAccion=2;
			$bpasa=true;
			}else{
			$scampo[1]='saiu05dia';
			$scampo[2]='saiu05idcategoria';
			$scampo[3]='saiu05idtiposolorigen';
			$scampo[4]='saiu05idtemaorigen';
			$scampo[5]='saiu05tipointeresado';
			$scampo[6]='saiu05rptaforma';
			$scampo[7]='saiu05rptacorreo';
			$scampo[8]='saiu05rptadireccion';
			$scampo[9]='saiu05detalle';
			$scampo[10]='saiu05idunidadresp';
			$scampo[11]='saiu05idequiporesp';
			$scampo[12]='saiu05idresponsable';
			$sdato[1]=$DATA['saiu05dia'];
			$sdato[2]=$DATA['saiu05idcategoria'];
			$sdato[3]=$DATA['saiu05idtiposolorigen'];
			$sdato[4]=$DATA['saiu05idtemaorigen'];
			$sdato[5]=$DATA['saiu05tipointeresado'];
			$sdato[6]=$DATA['saiu05rptaforma'];
			$sdato[7]=$DATA['saiu05rptacorreo'];
			$sdato[8]=$DATA['saiu05rptadireccion'];
			$sdato[9]=$saiu05detalle;
			$sdato[10]=$idunidad;
			$sdato[11]=$idgrupotrabajo;
			$sdato[12]=$idresponsable;
			$numcmod=12;
			$sWhere='saiu05id='.$DATA['saiu05id'].'';
			$sSQL='SELECT * FROM '.$sTabla05.' WHERE '.$sWhere;
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
					$sSQL='UPDATE '.$sTabla05.' SET '.utf8_encode($sdatos).' WHERE '.$sWhere.';';
					}else{
					$sdetalle=$sdatos.'['.$sWhere.']';
					$sSQL='UPDATE '.$sTabla05.' SET '.$sdatos.' WHERE '.$sWhere.';';
					}
				$idAccion=3;
				}
			}
		if ($bpasa){
			$result=$objDB->ejecutasql($sSQL);
			if ($result==false){
				$sError=$ERR['falla_guardar'].' [3005] ..<!-- '.$sSQL.' -->';
				if ($idAccion==2){
					$DATA['saiu05id']='';
					$bQuitarCodigo=true;
					$DATA['paso']=0;
					}else{
					$DATA['paso']=2;
					}
				}else{
				list($sErrorC, $sDebugC) = f3005_CargarDocumentos($DATA['saiu05agno'], $DATA['saiu05mes'], $DATA['saiu05id'], $objDB, $bDebug);
				if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Guardar 3005 '.$sSQL.'<br>';}
				if ($bAudita[$idAccion]){seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], $idAccion, $DATA['saiu05id'], $sdetalle, $objDB);}
				$DATA['paso']=2;
				$bCalularTotales=true;
				}
			}else{
			$DATA['paso']=2;
			}
		}else{
		$DATA['paso']=$DATA['paso']-10;
		}
	if ($bQuitarCodigo){
		$DATA['saiu05consec']='';
		}
	if ($bCalularTotales){
		list($sErrorT, $sDebugT)=f3000_CalcularTotales($DATA['saiu05idsolicitante'], $DATA['saiu05agno'], (int)$DATA['saiu05mes'], $objDB, $bDebug);
		$sDebug=$sDebug.$sDebugT;
		}
	//if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' InfoDepura<br>';}
	return array($DATA, $sError, $iTipoError, $sDebug);
	}
function f3005_db_Eliminar($iAgno, $iMes, $saiu05id, $objDB, $bDebug=false){
	$iCodModulo=3005;
	$bAudita[4]=true;
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3005=$APP->rutacomun.'lg/lg_3005_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3005)){$mensajes_3005=$APP->rutacomun.'lg/lg_3005_es.php';}
	require $mensajes_todas;
	require $mensajes_3005;
	$sError='';
	$iTipoError=0;
	$sDebug='';
	$saiu05id=numeros_validar($saiu05id);
	// Traer los datos para hacer las validaciones.
	if ($sError==''){
		$sTabla5='saiu05solicitud'.f3000_Contenedor($iAgno, $iMes);
		$sTabla6='saiu06solanotacion'.f3000_Contenedor($iAgno, $iMes);
		$sTabla7='saiu07anexos'.f3000_Contenedor($iAgno, $iMes);
		$sSQL='SELECT * FROM '.$sTabla5.' WHERE saiu05id='.$saiu05id.'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$filabase=$objDB->sf($tabla);
			}else{
			$sError='No se encuentra el registro solicitado {Ref: '.$saiu05id.'}';
			}
		}
	if ($sError==''){
		$sSQL='SELECT saiu06idsolicitud FROM '.$sTabla6.' WHERE saiu06idsolicitud='.$filabase['saiu05id'].'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$sError='Existen Anotaciones creadas, no es posible eliminar';
			}
		}
	if ($sError==''){
		$sSQL='SELECT saiu07idsolicitud FROM '.$sTabla7.' WHERE saiu07idsolicitud='.$filabase['saiu05id'].'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$sError='Existen Anexos creados, no es posible eliminar';
			}
		}
	if ($sError==''){
		if (!seg_revisa_permiso($iCodModulo, 4, $objDB)){
			$sError=$ERR['4'];
			}
		}
	if ($sError==''){
		$sSQL='SELECT unad70origennomtabla AS tabla, unad70origenidtabla AS campoid, unad70origencamporev AS camporev, unad70mensaje AS mensaje, unad70etiqueta AS etiqueta FROM unad70bloqueoelimina WHERE unad70idtabla=3005';
		$tablaor=$objDB->ejecutasql($sSQL);
		while ($filaor=$objDB->sf($tablaor)){
			$sSQL='SELECT '.$filaor['campoid'].' FROM '.$filaor['tabla'].' WHERE '.$filaor['camporev'].'='.$_REQUEST['saiu05id'].' LIMIT 0, 1';
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
		//$sSQL='DELETE FROM saiu06solanotacion WHERE saiu06idsolicitud='.$filabase['saiu05id'].'';
		//$tabla=$objDB->ejecutasql($sSQL);
		//$sSQL='DELETE FROM saiu07anexos WHERE saiu07idsolicitud='.$filabase['saiu05id'].'';
		//$tabla=$objDB->ejecutasql($sSQL);
		$sWhere='saiu05id='.$saiu05id.'';
		//$sWhere='saiu05consec='.$filabase['saiu05consec'].' AND saiu05tiporadicado='.$filabase['saiu05tiporadicado'].' AND saiu05mes='.$filabase['saiu05mes'].' AND saiu05agno='.$filabase['saiu05agno'].'';
		$sSQL='DELETE FROM '.$sTabla5.' WHERE '.$sWhere.';';
		$result=$objDB->ejecutasql($sSQL);
		if ($result==false){
			$sError=$ERR['falla_eliminar'].' .. <!-- '.$sSQL.' -->';
			}else{
			if ($bAudita[4]){seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 4, $saiu05id, $sTabla5.'-'.$sWhere, $objDB);}
			list($sErrorT, $sDebugT)=f3000_CalcularTotales($filabase['saiu05idsolicitante'], $filabase['saiu05agno'], $filabase['saiu05mes'], $objDB, $bDebug);
			$sDebug=$sDebug.$sDebugT;
			}
		}
	return array($sError, $iTipoError, $sDebug);
	}
function f3005_TituloBusqueda(){
	return 'Busqueda de Solicitudes';
	}
function f3005_ParametrosBusqueda(){
	$sParams='<label class="Label90">Nombre</label><label><input id="b3005nombre" name="b3005nombre" type="text" value="" onchange="paginarbusqueda()" /></label>';
	return $sParams;
	}
function f3005_JavaScriptBusqueda($iModuloBusca){
	$sRes='var sCampo=window.document.frmedita.scampobusca.value;
var params=new Array();
params[100]=sCampo;
params[101]=window.document.frmedita.paginabusqueda.value;
params[102]=window.document.frmedita.lppfbusqueda.value;
params[103]=window.document.frmedita.b3005nombre.value;
xajax_f'.$iModuloBusca.'_HtmlBusqueda(params);';
	return $sRes;
	}
function f3005_TablaDetalleBusquedas($aParametros, $objDB){
	$res='';
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_3005=$APP->rutacomun.'lg/lg_3005_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_3005)){$mensajes_3005=$APP->rutacomun.'lg/lg_3005_es.php';}
	require $mensajes_todas;
	require $mensajes_3005;
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
		return array($sLeyenda.'<input id="paginaf3005" name="paginaf3005" type="hidden" value="'.$pagina.'"/><input id="lppf3005" name="lppf3005" type="hidden" value="'.$lineastabla.'"/>', $sDebug);
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
	$sTitulos='Agno, Mes, Tiporadicado, Consec, Id, Origenagno, Origenmes, Origenid, Dia, Hora, Minuto, Estado, Medio, Tiposolorigen, Temaorigen, Temafin, Tiposolfin, Solicitante, Interesado, Tipointeresado, Rptaforma, Rptacorreo, Rptadireccion, Costogenera, Costovalor, Costorefpago, Prioridad, Zona, Cead, Numref, Detalle, Infocomplemento, Responsable, Escuela, Programa, Periodo, Curso, Grupo, Tiemprespdias, Tiempresphoras, Fecharespprob, Respuesta, Moduloproc, Entificadormod, Numradicado';
	$sSQL='SELECT TB.saiu05agno, TB.saiu05mes, T3.saiu16nombre, TB.saiu05consec, TB.saiu05id, TB.saiu05origenagno, TB.saiu05origenmes, TB.saiu05origenid, TB.saiu05dia, TB.saiu05hora, TB.saiu05minuto, T12.saiu11nombre, T13.bita01nombre, T14.saiu02titulo, T15.saiu03titulo, T16.saiu03titulo, T17.saiu02titulo, T18.unad11razonsocial AS C18_nombre, T19.unad11razonsocial AS C19_nombre, T20.bita07nombre, T21.saiu12nombre, TB.saiu05rptacorreo, TB.saiu05rptadireccion, TB.saiu05costogenera, TB.saiu05costovalor, TB.saiu05costorefpago, T27.bita03nombre, T28.unad23nombre, T29.unad24nombre, TB.saiu05numref, TB.saiu05detalle, TB.saiu05infocomplemento, T33.unad11razonsocial AS C33_nombre, T34.core12nombre, T35.core09nombre, T36.exte02nombre, T37.unad40nombre, T38.core06consec, TB.saiu05tiemprespdias, TB.saiu05tiempresphoras, TB.saiu05fecharespprob, TB.saiu05respuesta, TB.saiu05idmoduloproc, TB.saiu05identificadormod, TB.saiu05numradicado, TB.saiu05tiporadicado, TB.saiu05estado, TB.saiu05idmedio, TB.saiu05idtiposolorigen, TB.saiu05idtemaorigen, TB.saiu05idtemafin, TB.saiu05idtiposolfin, TB.saiu05idsolicitante, T18.unad11tipodoc AS C18_td, T18.unad11doc AS C18_doc, TB.saiu05idinteresado, T19.unad11tipodoc AS C19_td, T19.unad11doc AS C19_doc, TB.saiu05tipointeresado, TB.saiu05rptaforma, TB.saiu05prioridad, TB.saiu05idzona, TB.saiu05cead, TB.saiu05idresponsable, T33.unad11tipodoc AS C33_td, T33.unad11doc AS C33_doc, TB.saiu05idescuela, TB.saiu05idprograma, TB.saiu05idperiodo, TB.saiu05idcurso, TB.saiu05idgrupo 
FROM saiu05solicitud AS TB, saiu16tiporadicado AS T3, saiu11estadosol AS T12, bita01tiposolicitud AS T13, saiu02tiposol AS T14, saiu03temasol AS T15, saiu03temasol AS T16, saiu02tiposol AS T17, unad11terceros AS T18, unad11terceros AS T19, bita07tiposolicitante AS T20, saiu12formarespuesta AS T21, bita03prioridad AS T27, unad23zona AS T28, unad24sede AS T29, unad11terceros AS T33, core12escuela AS T34, core09programa AS T35, exte02per_aca AS T36, unad40curso AS T37, core06grupos AS T38 
WHERE '.$sSQLadd1.' TB.saiu05tiporadicado=T3.saiu16id AND TB.saiu05estado=T12.saiu11id AND TB.saiu05idmedio=T13.bita01id AND TB.saiu05idtiposolorigen=T14.saiu02id AND TB.saiu05idtemaorigen=T15.saiu03id AND TB.saiu05idtemafin=T16.saiu03id AND TB.saiu05idtiposolfin=T17.saiu02id AND TB.saiu05idsolicitante=T18.unad11id AND TB.saiu05idinteresado=T19.unad11id AND TB.saiu05tipointeresado=T20.bita07id AND TB.saiu05rptaforma=T21.saiu12id AND TB.saiu05prioridad=T27.bita03id AND TB.saiu05idzona=T28.unad23id AND TB.saiu05cead=T29.unad24id AND TB.saiu05idresponsable=T33.unad11id AND TB.saiu05idescuela=T34.core12id AND TB.saiu05idprograma=T35.core09id AND TB.saiu05idperiodo=T36.exte02id AND TB.saiu05idcurso=T37.unad40id AND TB.saiu05idgrupo=T38.core06id '.$sSQLadd.'
ORDER BY TB.saiu05agno, TB.saiu05mes, TB.saiu05tiporadicado, TB.saiu05consec';
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
			//return array(utf8_encode($sErrConsulta.'<input id="paginaf3005" name="paginaf3005" type="hidden" value="'.$pagina.'"/><input id="lppf3005" name="lppf3005" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
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
<td><b>'.$ETI['saiu05agno'].'</b></td>
<td><b>'.$ETI['saiu05mes'].'</b></td>
<td><b>'.$ETI['saiu05tiporadicado'].'</b></td>
<td><b>'.$ETI['saiu05consec'].'</b></td>
<td><b>'.$ETI['saiu05origenagno'].'</b></td>
<td><b>'.$ETI['saiu05origenmes'].'</b></td>
<td><b>'.$ETI['saiu05origenid'].'</b></td>
<td><b>'.$ETI['saiu05dia'].'</b></td>
<td><b>'.$ETI['saiu05hora'].'</b></td>
<td><b>'.$ETI['saiu05estado'].'</b></td>
<td><b>'.$ETI['saiu05idmedio'].'</b></td>
<td><b>'.$ETI['saiu05idtiposolorigen'].'</b></td>
<td><b>'.$ETI['saiu05idtemaorigen'].'</b></td>
<td><b>'.$ETI['saiu05idtemafin'].'</b></td>
<td><b>'.$ETI['saiu05idtiposolfin'].'</b></td>
<td colspan="2"><b>'.$ETI['saiu05idsolicitante'].'</b></td>
<td colspan="2"><b>'.$ETI['saiu05idinteresado'].'</b></td>
<td><b>'.$ETI['saiu05tipointeresado'].'</b></td>
<td><b>'.$ETI['saiu05rptaforma'].'</b></td>
<td><b>'.$ETI['saiu05rptacorreo'].'</b></td>
<td><b>'.$ETI['saiu05rptadireccion'].'</b></td>
<td><b>'.$ETI['saiu05costogenera'].'</b></td>
<td><b>'.$ETI['saiu05costovalor'].'</b></td>
<td><b>'.$ETI['saiu05costorefpago'].'</b></td>
<td><b>'.$ETI['saiu05prioridad'].'</b></td>
<td><b>'.$ETI['saiu05idzona'].'</b></td>
<td><b>'.$ETI['saiu05cead'].'</b></td>
<td><b>'.$ETI['saiu05numref'].'</b></td>
<td><b>'.$ETI['saiu05detalle'].'</b></td>
<td><b>'.$ETI['saiu05infocomplemento'].'</b></td>
<td colspan="2"><b>'.$ETI['saiu05idresponsable'].'</b></td>
<td><b>'.$ETI['saiu05idescuela'].'</b></td>
<td><b>'.$ETI['saiu05idprograma'].'</b></td>
<td><b>'.$ETI['saiu05idperiodo'].'</b></td>
<td><b>'.$ETI['saiu05idcurso'].'</b></td>
<td><b>'.$ETI['saiu05idgrupo'].'</b></td>
<td><b>'.$ETI['saiu05tiemprespdias'].'</b></td>
<td><b>'.$ETI['saiu05tiempresphoras'].'</b></td>
<td><b>'.$ETI['saiu05fecharespprob'].'</b></td>
<td><b>'.$ETI['saiu05respuesta'].'</b></td>
<td><b>'.$ETI['saiu05idmoduloproc'].'</b></td>
<td><b>'.$ETI['saiu05identificadormod'].'</b></td>
<td><b>'.$ETI['saiu05numradicado'].'</b></td>
<td align="right">
'.html_paginador('paginabusqueda', $registros, $lineastabla, $pagina, 'paginarbusqueda()').'
'.html_lpp('lppfbusqueda', $lineastabla, 'paginarbusqueda()').'
</td>
</tr>';
	$tlinea=1;
	while($filadet=$objDB->sf($tabladetalle)){
		$sPrefijo='<a href="javascript:Devuelve(\''.$filadet['saiu05id'].'\');">';
		$sSufijo='</a>';
		$tlinea++;
		$et_saiu05dia='';
		if ($filadet['saiu05dia']!=0){$et_saiu05dia=fecha_desdenumero($filadet['saiu05dia']);}
		$et_saiu05hora=html_TablaHoraMin($filadet['saiu05hora'], $filadet['saiu05minuto']);
		$et_saiu05fecharespprob='';
		if ($filadet['saiu05fecharespprob']!='00/00/0000'){$et_saiu05fecharespprob=$filadet['saiu05fecharespprob'];}
		$res=$res.'<tr onmouseover="cambia_color_over(this);" onmouseout="cambia_color_out(this);">
<td>'.$sPrefijo.$filadet['saiu05agno'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu05mes'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['saiu16nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu05consec'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu05origenagno'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu05origenmes'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu05origenid'].$sSufijo.'</td>
<td>'.$sPrefijo.$et_saiu05dia.$sSufijo.'</td>
<td>'.$sPrefijo.$et_saiu05hora.$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['saiu11nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['bita01nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['saiu02titulo']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['saiu03titulo']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['saiu03titulo']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['saiu02titulo']).$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['C18_td'].' '.$filadet['C18_doc'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['C18_nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['C19_td'].' '.$filadet['C19_doc'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['C19_nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['bita07nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['saiu12nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['saiu05rptacorreo']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['saiu05rptadireccion']).$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu05costogenera'].$sSufijo.'</td>
<td align="right">'.$sPrefijo.formato_moneda($filadet['saiu05costovalor']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['saiu05costorefpago']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['bita03nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['unad23nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['unad24nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['saiu05numref']).$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu05detalle'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu05infocomplemento'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['C33_td'].' '.$filadet['C33_doc'].$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['C33_nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['core12nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['core09nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['exte02nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['unad40nombre']).$sSufijo.'</td>
<td>'.$sPrefijo.cadena_notildes($filadet['core06consec']).$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu05tiemprespdias'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu05tiempresphoras'].$sSufijo.'</td>
<td>'.$sPrefijo.$et_saiu05fecharespprob.$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu05respuesta'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu05idmoduloproc'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu05identificadormod'].$sSufijo.'</td>
<td>'.$sPrefijo.$filadet['saiu05numradicado'].$sSufijo.'</td>
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
function f3005_CargarDocumentos($iAgno, $iMes, $saiu05id, $objDB, $bDebug = false, $bForzar = false)
{
	$sError = '';
	$sDebug = '';
	$sTabla05 = 'saiu05solicitud_' . $iAgno . $iMes;
	$sTabla07 = 'saiu07anexos_' . $iAgno . $iMes;
	if (!$objDB->bexistetabla($sTabla05)) {
		$sError = 'No ha sido posible acceder al contenedor de datos';
	} else {
	}
	if ($sError == '') {
		$sSQL = 'SELECT saiu07id, saiu07idarchivo FROM ' . $sTabla07 . ' WHERE saiu07idsolicitud=' . $saiu05id . ';';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$sError = 'Ya existen documentos cargados.';
			$sWhere = '';
			while ($fila = $objDB->sf($tabla)) {
				if ($fila['saiu07idarchivo'] != 0) {
					if ($sWhere==''){
						$sWhere=$sWhere.'WHERE saiu07id IN (';
					} else {
						$sWhere=$sWhere.', ';
					}
					$sWhere=$sWhere.$fila['saiu07id'];
				}
			}
			if ($sWhere != '') {
				$sWhere=$sWhere.')';
				$sSQL = 'UPDATE ' . $sTabla07 . ' SET saiu07estado=1 ' . $sWhere . ';';
				$result = $objDB->ejecutasql($sSQL);
				if ($result==false){
					$sError=$sError . '<br>Falla cambio de estado anexos ';
				}
			}
		}
		$sDebug = $sDebug . fecha_microtiempo() . ' documentos cargados: ' . $sSQL . '<br>';
	}
	if ($sError == '') {
		$sSQL = 'SELECT saiu05idsolicitante, saiu05idtemaorigen FROM ' . $sTabla05 . ' WHERE saiu05id=' . $saiu05id . '';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			$saiu04idtema = $fila['saiu05idtemaorigen'];
			$saiu07idusuario = $fila['saiu05idsolicitante'];
		} else {
			$sError = 'No se ha encontrado el registro solicitado [Ref ' . $saiu05id . ' A&ntilde;o ' . $iAgno . '-' . $iMes . ']';
		}
		$sDebug = $sDebug . fecha_microtiempo() . ' id solicitante: ' . $sSQL . '<br>';
	}
	if ($sError == '') {
		$sCampos3007 = '  saiu07idsolicitud, saiu07consec, saiu07id, saiu07idtipoanexo, saiu07detalle, 
		saiu07idorigen, saiu07idarchivo, saiu07idusuario, saiu07fecha, saiu07hora, saiu07minuto, 
		saiu07estado, saiu07idvalidad, saiu07fechavalida, saiu07horavalida, saiu07minvalida';
		$saiu07consec = tabla_consecutivo($sTabla07, 'saiu07consec', 'saiu07idsolicitud=' . $saiu05id . '', $objDB);
		$saiu07id = tabla_consecutivo($sTabla07, 'saiu07id', '', $objDB);
		$sSQL = 'SELECT saiu04id, saiu04obligatorio 
		FROM saiu04temaanexo 
		WHERE saiu04idtema=' . $saiu04idtema . ' AND saiu04activo="S" 
		ORDER BY saiu04orden';
		$tabla = $objDB->ejecutasql($sSQL);
		while ($fila = $objDB->sf($tabla)) {
			$sValores3007 = '' . $saiu05id . ', ' . $saiu07consec . ', ' . $saiu07id . ', ' . $fila['saiu04id'] . ', "", 
			0, 0, ' . $saiu07idusuario . ', 0, 0, 0,
			0, 0, 0, 0, 0';
			$sSQL = 'INSERT INTO ' . $sTabla07 . ' (' . $sCampos3007 . ') VALUES (' . $sValores3007 . ');';
			$result = $objDB->ejecutasql($sSQL);
			if ($bDebug) {
				$sDebug = $sDebug . fecha_microtiempo() . ' Insertando: ' . $sSQL . '<br>';
			}
			$saiu07consec++;
			$saiu07id++;
		}
	}
	return array($sError, $sDebug);
}
//
?>