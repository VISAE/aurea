<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2018 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.22.3 jueves, 23 de agosto de 2018
--- 2357 cara57rptavance
*/
function f2357_Busquedas($aParametros){
	require './app.php';
	$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
	$objDB->xajax();
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2357='lg/lg_2357_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2357)){$mensajes_2357='lg/lg_2357_es.php';}
	require $mensajes_todas;
	require $mensajes_2357;
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
	$sTitulo='<h2>'.$ETI['titulo_2357'].' - '.$sTitulo.'</h2>';
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_97titulo', 'innerHTML', $sTitulo);
	$objResponse->assign('div_97params', 'innerHTML', $sParams);
	$objResponse->assign('div_97tabla', 'innerHTML', $sTabla);
	$objResponse->setFunction('paginarbusqueda','',$sJavaBusqueda);
	$objResponse->call('expandesector(97)');
	return $objResponse;
	}
function f2357_HtmlBusqueda($aParametros){
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
class clsLinea{
	var $idEscuela=0;
	var $sNomEscuela='';
	var $iMatNuevos=0;
	var $iMatAntiguos=0;
	var $iEncNuevos=0;
	var $iEncAntiguos=0;
	var $iCompletasNuevos=0;
	var $iCompletasAntiguos=0;
	function __construct($id, $sNombre){
		$this->idEscuela=$id;
		$this->sNomEscuela=$sNombre;
		//$this->iEncAntiguos=$id;
		}
	}
function f2357_CargarData($idPeraca, $objDB, $bDebug=false){
	$sDebug='';
	$aEsc=array();
	$iNumEsc=0;
	$sSQL='SELECT core12id, core12nombre 
FROM core12escuela AS TB 
ORDER BY core12nombre';
	$tabla=$objDB->ejecutasql($sSQL);
	while($fila=$objDB->sf($tabla)){
		$objLin=new clsLinea($fila['core12id'], cadena_notildes($fila['core12nombre']));
		$iNumEsc++;
		$aEsc[$iNumEsc]=$objLin;
		}
	$sSQL='SELECT core16idescuela, core16nuevo, COUNT(core16id) AS Total FROM core16actamatricula WHERE core16peraca='.$idPeraca.' GROUP BY core16idescuela, core16nuevo';
	$tabla=$objDB->ejecutasql($sSQL);
	while($fila=$objDB->sf($tabla)){
		for ($k=1;$k<=$iNumEsc;$k++){
			$objLin=$aEsc[$k];
			if ($objLin->idEscuela==$fila['core16idescuela']){
				if ($fila['core16nuevo']==0){
					$objLin->iMatAntiguos=$fila['Total'];
					}else{
					$objLin->iMatNuevos=$fila['Total'];
					}
				$k=$iNumEsc+1;
				}
			}
		}
	$sSQL='SELECT cara01idescuela, cara01tipocaracterizacion, cara01completa, COUNT(cara01id) AS Total FROM cara01encuesta WHERE cara01idperaca='.$idPeraca.' GROUP BY cara01idescuela, cara01tipocaracterizacion, cara01completa';
	$tabla=$objDB->ejecutasql($sSQL);
	while($fila=$objDB->sf($tabla)){
		for ($k=1;$k<=$iNumEsc;$k++){
			$objLin=$aEsc[$k];
			if ($objLin->idEscuela==$fila['cara01idescuela']){
				if ($fila['cara01tipocaracterizacion']==3){
					$objLin->iEncAntiguos=$objLin->iEncAntiguos+$fila['Total'];
					if ($fila['cara01completa']=='S'){
						$objLin->iCompletasAntiguos=$objLin->iCompletasAntiguos+$fila['Total'];
						}
					}else{
					$objLin->iEncNuevos=$objLin->iEncNuevos+$fila['Total'];
					if ($fila['cara01completa']=='S'){
						$objLin->iCompletasNuevos=$objLin->iCompletasNuevos+$fila['Total'];
						}
					}
				$k=$iNumEsc+1;
				}
			}
		}
	$objDB->liberar($tabla);
	return array($aEsc, $sDebug);
	}
function f2357_TablaDetalleV2($aParametros, $objDB, $bDebug=false){
	require './app.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2357='lg/lg_2357_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2357)){$mensajes_2357='lg/lg_2357_es.php';}
	require $mensajes_todas;
	require $mensajes_2357;
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	if (isset($aParametros[101])==0){$aParametros[101]=1;}
	if (isset($aParametros[102])==0){$aParametros[102]=20;}
	if (isset($aParametros[103])==0){$aParametros[103]='';}
	$aParametros[103]=numeros_validar($aParametros[103]);
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
	if ($aParametros[103]==''){
		$sLeyenda='<div class="salto1px"></div>
<div class="GrupoCamposAyuda">
<b>Debe seleccionar un periodo para ejecutar la consulta</b>
<div class="salto1px"></div>
</div>';
		return array(utf8_encode($sLeyenda.'<input id="paginaf2357" name="paginaf2357" type="hidden" value="'.$pagina.'"/><input id="lppf2357" name="lppf2357" type="hidden" value="'.$lineastabla.'"/>'), $sDebug);
		}
	$idPeraca=$aParametros[103];
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
				$sSQLadd=$sSQLadd.' AND T6.sys11razonsocial LIKE "%'.$sCadena.'%"';
				//$sSQLadd1=$sSQLadd1.'T1.sys11razonsocial LIKE "%'.$sCadena.'%" AND ';
				}
			}
		}
	*/
	$iTotalRyC=-1;
	list($objDBRyC, $sDebugR)=TraerDBRyCV2($bDebug);
	if ($objDBRyC==NULL){
		}else{
		$sSQL='SELECT TR.ins_estudiante 
FROM registro AS TR, cursos_periodos AS T1 
WHERE TR.ano='.$idPeraca.' AND TR.ins_novedad=79 AND TR.estado="A"
AND TR.ins_curso=T1.consecutivo AND T1.cur_edificio<>99 
GROUP BY TR.ins_estudiante';
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' alumnos en matricula RyC: '.$sSQL.'<br>';}
		$tabla=$objDBRyC->ejecutasql($sSQL);
		$iTotalRyC=$objDBRyC->nf($tabla);
		}
	
	list($aEsc, $sDebugD)=f2357_CargarData($aParametros[103], $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugD;
	$iNumEsc=count($aEsc);
	$sTitulos='Peraca';
	$sSQLlista='';
	$sErrConsulta='<input id="consulta_2357" name="consulta_2357" type="hidden" value="'.$sSQLlista.'"/>
<input id="titulos_2357" name="titulos_2357" type="hidden" value="'.$sTitulos.'"/>';
	$res=$sErrConsulta.$sLeyenda.'<input id="paginaf2357" name="paginaf2357" type="hidden" value="'.$pagina.'"/><input id="lppf2357" name="lppf2357" type="hidden" value="'.$lineastabla.'"/>
<table border="0" align="center" cellpadding="0" cellspacing="2" class="tablaapp">
<tr class="fondoazul">
<td rowspan="2"><b>'.$ETI['msg_escuela'].'</b></td>
<td colspan="4" align="center"><b>'.$ETI['msg_antiguos'].'</b></td>
<td colspan="4" align="center"><b>'.$ETI['msg_nuevos'].'</b></td>
</tr>
<tr class="fondoazul">
<td align="center"><b>'.$ETI['msg_matricula'].'</b></td>
<td align="center"><b>'.$ETI['msg_encuestas'].'</b></td>
<td align="center"><b>'.$ETI['msg_completas'].'</b></td>
<td align="center"><b>'.$ETI['msg_avance'].'</b></td>
<td align="center"><b>'.$ETI['msg_matricula'].'</b></td>
<td align="center"><b>'.$ETI['msg_encuestas'].'</b></td>
<td align="center"><b>'.$ETI['msg_completas'].'</b></td>
<td align="center"><b>'.$ETI['msg_avance'].'</b></td>
</tr>';
	$tlinea=1;
	$iMatNuevos=0;
	$iMatAntiguos=0;
	$iEncNuevos=0;
	$iEncAntiguos=0;
	$iCompletasNuevos=0;
	$iCompletasAntiguos=0;
	for ($k=1;$k<=$iNumEsc;$k++){
		$objLin=$aEsc[$k];
		$bEntra=false;
		if (($objLin->iMatAntiguos+$objLin->iMatNuevos+$objLin->iEncAntiguos+$objLin->iEncNuevos)>0){$bEntra=true;}
		if ($bEntra){
			$iMatNuevos=$iMatNuevos+$objLin->iMatNuevos;
			$iMatAntiguos=$iMatAntiguos+$objLin->iMatAntiguos;
			$iEncNuevos=$iEncNuevos+$objLin->iEncNuevos;
			$iEncAntiguos=$iEncAntiguos+$objLin->iEncAntiguos;
			$iCompletasNuevos=$iCompletasNuevos+$objLin->iCompletasNuevos;
			$iCompletasAntiguos=$iCompletasAntiguos+$objLin->iCompletasAntiguos;
		$sPrefijo='';
		$sSufijo='';
		$sClass='';
		$sLink='';
		if (false){
			$sPrefijo='<b>';
			$sSufijo='</b>';
			}
		$et_avanceA='';
		$et_avanceN='';
		if ($objLin->iEncAntiguos>0){
			$et_avanceA=formato_numero($objLin->iCompletasAntiguos/$objLin->iEncAntiguos*100, 2).' %';
			}
		if ($objLin->iEncNuevos>0){
			$et_avanceN=formato_numero($objLin->iCompletasNuevos/$objLin->iEncNuevos*100, 2).' %';
			}
		if(($tlinea%2)==0){$sClass=' class="resaltetabla"';}
		$tlinea++;
		$res=$res.'<tr'.$sClass.'>
<td>'.$sPrefijo.$objLin->sNomEscuela.$sSufijo.'</td>
<td align="right">'.$sPrefijo.formato_numero($objLin->iMatAntiguos).$sSufijo.'</td>
<td align="right">'.$sPrefijo.formato_numero($objLin->iEncAntiguos).$sSufijo.'</td>
<td align="right">'.$sPrefijo.formato_numero($objLin->iCompletasAntiguos).$sSufijo.'</td>
<td align="right">'.$sPrefijo.$et_avanceA.$sSufijo.'</td>
<td align="right">'.$sPrefijo.formato_numero($objLin->iMatNuevos).$sSufijo.'</td>
<td align="right">'.$sPrefijo.formato_numero($objLin->iEncNuevos).$sSufijo.'</td>
<td align="right">'.$sPrefijo.formato_numero($objLin->iCompletasNuevos).$sSufijo.'</td>
<td align="right">'.$sPrefijo.$et_avanceN.$sSufijo.'</td>
</tr>';
		}
		}
	$sPrefijo='';
	$sSufijo='';
	$et_avanceA='';
	$et_avanceN='';
	$et_avanceT='';
	if ($iEncAntiguos>0){
		$et_avanceA=formato_numero($iCompletasAntiguos/$iEncAntiguos*100, 2).' %';
		}
	if ($iEncNuevos>0){
		$et_avanceN=formato_numero($iCompletasNuevos/$iEncNuevos*100, 2).' %';
		}
	if (($iEncAntiguos+$iEncNuevos)>0){
		$iBaseAnt=0;
		if ($iEncAntiguos>0){$iBaseAnt=$iEncAntiguos;}
		$et_avanceT=formato_numero(($iCompletasNuevos+$iCompletasAntiguos)/($iEncNuevos+$iBaseAnt)*100, 2).' %';
		}
	$sLinkMatricula=$ETI['msg_estudiantes'].' '.formato_numero($iTotalRyC);
	if ($iTotalRyC>0){
		if ($iTotalRyC>($iMatNuevos+$iMatAntiguos)){
			$sLinkMatricula=$sLinkMatricula.' <a href="javascript:procesarmatricula()" class="lnkresalte">'.$ETI['lnk_procesamatricula'].'</a>';
			}
		}
	$res=$res.'<tr class="fondoazul">
<td align="right">'.$sPrefijo.'TOTAL'.$sSufijo.'</td>
<td align="right">'.$sPrefijo.formato_numero($iMatAntiguos).$sSufijo.'</td>
<td align="right">'.$sPrefijo.formato_numero($iEncAntiguos).$sSufijo.'</td>
<td align="right">'.$sPrefijo.formato_numero($iCompletasAntiguos).$sSufijo.'</td>
<td align="right">'.$sPrefijo.$et_avanceA.$sSufijo.'</td>
<td align="right">'.$sPrefijo.formato_numero($iMatNuevos).$sSufijo.'</td>
<td align="right">'.$sPrefijo.formato_numero($iEncNuevos).$sSufijo.'</td>
<td align="right">'.$sPrefijo.formato_numero($iCompletasNuevos).$sSufijo.'</td>
<td align="right">'.$sPrefijo.$et_avanceN.$sSufijo.'</td>
</tr>
<tr class="fondoazul">
<td align="right" colspan="2">'.$sPrefijo.$sLinkMatricula.$sSufijo.'</td>
<td align="right" colspan="3">'.$sPrefijo.'GRAN TOTAL'.$sSufijo.'</td>
<td align="right">'.$sPrefijo.formato_numero($iMatNuevos+$iMatAntiguos).$sSufijo.'</td>
<td align="right">'.$sPrefijo.formato_numero($iEncNuevos+$iEncAntiguos).$sSufijo.'</td>
<td align="right">'.$sPrefijo.formato_numero($iCompletasNuevos+$iCompletasAntiguos).$sSufijo.'</td>
<td align="right">'.$sPrefijo.$et_avanceT.$sSufijo.'</td>
</tr>';
	$res=$res.'</table>';
	return array(utf8_encode($res), $sDebug);
	}
function f2357_HtmlTabla($aParametros){
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
	list($sDetalle, $sDebugTabla)=f2357_TablaDetalleV2($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->assign('div_f2357detalle', 'innerHTML', $sDetalle);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
function f2357_CargarDataDashboard($idPeraca, $objDB, $objGraficos, $bDebug=false){	
	$sDebug='';
	$aEscuela=array();
	$aPrograma=array();
	$aZona=array();
	$aSede=array();
	$aMatriculados=array("aZonas"=>array(),"aSedes"=>array(),"aEscuelas"=>array(),"aProgramas"=>array());
    $sSQL='SELECT core12id, core12nombre, core12sigla 
	FROM core12escuela AS TB 
	WHERE core12tieneestudiantes="S"';
	$tabla=$objDB->ejecutasql($sSQL);
	while($fila=$objDB->sf($tabla)){
		$aEscuela[$fila['core12id']]=utf8_encode($fila['core12sigla']);
		}
    $sSQL='SELECT core09id, core09nombre 
	FROM core09programa AS TB 
	WHERE core09activo="S"';
	$tabla=$objDB->ejecutasql($sSQL);
	while($fila=$objDB->sf($tabla)){
		$aPrograma[$fila['core09id']]=utf8_encode($fila['core09nombre']);
		}
    $sSQL='SELECT unad23id, unad23nombre, unad23sigla 
	FROM unad23zona AS TB 
	WHERE unad23conestudiantes="S"';
	$tabla=$objDB->ejecutasql($sSQL);
	while($fila=$objDB->sf($tabla)){
		$aZona[$fila['unad23id']]=utf8_encode($fila['unad23sigla']);
		}
    $sSQL='SELECT unad24id, unad24nombre, unad24activa 
	FROM unad24sede AS TB 
	WHERE unad24activa="S"';
	$tabla=$objDB->ejecutasql($sSQL);
	while($fila=$objDB->sf($tabla)){
		$aSede[$fila['unad24id']]=utf8_encode($fila['unad24nombre']);
		}
	$aContenidoMapa = array('ZCAR' => '', 'ZOCC' => '', 'ZCORI' => '', 'ZCBOY' => '', 'ZCBOG' => '', 'ZSUR' => '', 'ZCSUR' => '', 'ZAO' => '');
	$aTotalesMapa = array('ZCAR' => array('antiguos'=>0, 'nuevos'=>0, 'total'=>0), 'ZOCC' => array('antiguos'=>0, 'nuevos'=>0, 'total'=>0), 'ZCORI' => array('antiguos'=>0, 'nuevos'=>0, 'total'=>0), 'ZCBOY' => array('antiguos'=>0, 'nuevos'=>0, 'total'=>0), 'ZCBOG' => array('antiguos'=>0, 'nuevos'=>0, 'total'=>0), 'ZSUR' => array('antiguos'=>0, 'nuevos'=>0, 'total'=>0), 'ZCSUR' => array('antiguos'=>0, 'nuevos'=>0, 'total'=>0), 'ZAO' => array('antiguos'=>0, 'nuevos'=>0, 'total'=>0));
	$aConfiguracion=array(
		"aZonas"=>array(
			'aTitulos' => array('Antiguos','Nuevos'),
			'sTipo' => 'bar',
			'colorFondo' => array(),
			'colorLinea' => array(),
			'grupoApilado' => array(0,1)
		),
		"aSedes"=>array(
			'aTitulos' => array('Antiguos','Nuevos'),
			'sTipo' => 'bar',
			'colorFondo' => array(),
			'colorLinea' => array(),
			'grupoApilado' => array(0,1)
		),
		"aEscuelas"=>array(
			'aTitulos' => array('Antiguos','Nuevos'),
			'sTipo' => 'bar',
			'colorFondo' => array(),
			'colorLinea' => array(),
			'grupoApilado' => array(0,1)
		),
		"aProgramas"=>array(
			'aTitulos' => array('Antiguos','Nuevos'),
			'sTipo' => 'horizontalBar',
			'colorFondo' => array(),
			'colorLinea' => array(),
			'grupoApilado' => array(0,1)
		)
	);
	$sSQL='SELECT core16idzona, core16idcead, core16idescuela, core16idprograma, core16nuevo FROM core16actamatricula WHERE core16peraca='.$idPeraca.'';
	$tabla=$objDB->ejecutasql($sSQL);
    while ($fila = $objDB->sf($tabla)) {
        if (isset($aZona[$fila['core16idzona']])) {
            if (isset($aMatriculados['aZonas'][$fila['core16idzona']]) == 0) {
                $aMatriculados['aZonas'][$fila['core16idzona']] = array(
					'nombre' => $aZona[$fila['core16idzona']], 
					'cantidad' => array('grupo0' => 0, 'grupo1' => 0), 
					'total' => 0
				);
				if (isset($objGraficos->aPaletaZonas[$fila['core16idzona']])) {
					$aConfiguracion['aZonas']['colorFondo'][] = $objGraficos->aPaletaZonas[$fila['core16idzona']][4];
					$aConfiguracion['aZonas']['colorLinea'][] = $objGraficos->aPaletaZonas[$fila['core16idzona']][0];
				} else {
					$aConfiguracion['aZonas']['colorFondo'][] = $objGraficos->aPaletaUNAD['Azul'][4];
					$aConfiguracion['aZonas']['colorLinea'][] = $objGraficos->aPaletaUNAD['Azul'][0];
				}
				$aTotalesMapa[$aZona[$fila['core16idzona']]] = array('antiguos'=>0, 'nuevos'=>0, 'total'=>0, 'sedes'=>array());
            }
            if ($fila['core16nuevo'] == 0) { // Antiguos
                $aMatriculados['aZonas'][$fila['core16idzona']]['cantidad']['grupo0']++;
				$aTotalesMapa[$aZona[$fila['core16idzona']]]['antiguos']++;
            } else {
                $aMatriculados['aZonas'][$fila['core16idzona']]['cantidad']['grupo1']++;
				$aTotalesMapa[$aZona[$fila['core16idzona']]]['nuevos']++;
            }
            $aMatriculados['aZonas'][$fila['core16idzona']]['total']++;
			$aTotalesMapa[$aZona[$fila['core16idzona']]]['total']++;
        }
        if (isset($aSede[$fila['core16idcead']])) {
            if (isset($aMatriculados['aSedes'][$fila['core16idcead']]) == 0) {
                $aMatriculados['aSedes'][$fila['core16idcead']] = array(
					'nombre' => $aSede[$fila['core16idcead']], 
					'cantidad' => array('grupo0' => 0, 'grupo1' => 0), 
					'total' => 0
				);
				if (isset($objGraficos->aPaletaZonas[$fila['core16idzona']])) {
					$aConfiguracion['aSedes']['colorFondo'][] = $objGraficos->aPaletaZonas[$fila['core16idzona']][4];
					$aConfiguracion['aSedes']['colorLinea'][] = $objGraficos->aPaletaZonas[$fila['core16idzona']][0];
				} else {
					$aConfiguracion['aSedes']['colorFondo'][] = $objGraficos->aPaletaUNAD['Azul'][4];
					$aConfiguracion['aSedes']['colorLinea'][] = $objGraficos->aPaletaUNAD['Azul'][0];
				}
				$aTotalesMapa[$aZona[$fila['core16idzona']]]['sedes'][$aSede[$fila['core16idcead']]] = array('antiguos'=>0, 'nuevos'=>0, 'total'=>0);
            }
            if ($fila['core16nuevo'] == 0) { // Antiguos
                $aMatriculados['aSedes'][$fila['core16idcead']]['cantidad']['grupo0']++;
				$aTotalesMapa[$aZona[$fila['core16idzona']]]['sedes'][$aSede[$fila['core16idcead']]]['antiguos']++;
            } else {
                $aMatriculados['aSedes'][$fila['core16idcead']]['cantidad']['grupo1']++;
				$aTotalesMapa[$aZona[$fila['core16idzona']]]['sedes'][$aSede[$fila['core16idcead']]]['nuevos']++;
            }
            $aMatriculados['aSedes'][$fila['core16idcead']]['total']++;
			$aTotalesMapa[$aZona[$fila['core16idzona']]]['sedes'][$aSede[$fila['core16idcead']]]['total']++;
        }
        if (isset($aEscuela[$fila['core16idescuela']])) {
            if (isset($aMatriculados['aEscuelas'][$fila['core16idescuela']]) == 0) {
                $aMatriculados['aEscuelas'][$fila['core16idescuela']] = array(
					'nombre' => $aEscuela[$fila['core16idescuela']], 
					'cantidad' => array('grupo0' => 0, 'grupo1' => 0), 
					'total' => 0
				);
				if (isset($objGraficos->aPaletaEscuelas[$fila['core16idescuela']])) {
					$aConfiguracion['aEscuelas']['colorFondo'][] = $objGraficos->aPaletaEscuelas[$fila['core16idescuela']][4];
					$aConfiguracion['aEscuelas']['colorLinea'][] = $objGraficos->aPaletaEscuelas[$fila['core16idescuela']][0];
				} else {
					$aConfiguracion['aEscuelas']['colorFondo'][] = $objGraficos->aPaletaUNAD['Azul'][4];
					$aConfiguracion['aEscuelas']['colorLinea'][] = $objGraficos->aPaletaUNAD['Azul'][0];
				}
            }
            if ($fila['core16nuevo'] == 0) { // Antiguos
                $aMatriculados['aEscuelas'][$fila['core16idescuela']]['cantidad']['grupo0']++;
            } else {
                $aMatriculados['aEscuelas'][$fila['core16idescuela']]['cantidad']['grupo1']++;
            }
            $aMatriculados['aEscuelas'][$fila['core16idescuela']]['total']++;
        }
        if (isset($aPrograma[$fila['core16idprograma']])) {
            if (isset($aMatriculados['aProgramas'][$fila['core16idprograma']]) == 0) {
                $aMatriculados['aProgramas'][$fila['core16idprograma']] = array(
					'nombre' => $aPrograma[$fila['core16idprograma']], 
					'cantidad' => array('grupo0' => 0, 'grupo1' => 0), 
					'total' => 0
				);
				if (isset($objGraficos->aPaletaEscuelas[$fila['core16idescuela']])) {
					$aConfiguracion['aProgramas']['colorFondo'][] = $objGraficos->aPaletaEscuelas[$fila['core16idescuela']][4];
					$aConfiguracion['aProgramas']['colorLinea'][] = $objGraficos->aPaletaEscuelas[$fila['core16idescuela']][0];
				} else {
					$aConfiguracion['aProgramas']['colorFondo'][] = $objGraficos->aPaletaUNAD['Azul'][4];
					$aConfiguracion['aProgramas']['colorLinea'][] = $objGraficos->aPaletaUNAD['Azul'][0];
				}
            }
            if ($fila['core16nuevo'] == 0) { // Antiguos
                $aMatriculados['aProgramas'][$fila['core16idprograma']]['cantidad']['grupo0']++;
            } else {
                $aMatriculados['aProgramas'][$fila['core16idprograma']]['cantidad']['grupo1']++;
            }
            $aMatriculados['aProgramas'][$fila['core16idprograma']]['total']++;
        }
    }
    /*$sSQL='SELECT cara01idzona, cara01idcead, cara01idescuela, cara01idprograma FROM cara01encuesta WHERE cara01idperaca='.$idPeraca.'';
	$tabla=$objDB->ejecutasql($sSQL);
	while($fila=$objDB->sf($tabla)){

        }*/
	$objDB->liberar($tabla);
	return array($aMatriculados, $aConfiguracion, $aTotalesMapa, $sDebug);
	}
function f2357_DashboardDetalle($aParametros, $objDB, $bDebug=false){
	require './app.php';
	require $APP->rutacomun.'libgraficos.php';
	$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
	$mensajes_2357='lg/lg_2357_'.$_SESSION['unad_idioma'].'.php';
	if (!file_exists($mensajes_2357)){$mensajes_2357='lg/lg_2357_es.php';}
	require $mensajes_todas;
	require $mensajes_2357;
	if(!is_array($aParametros)){$aParametros=json_decode(str_replace('\"','"',$aParametros),true);}
	if (isset($aParametros[101])==0){$aParametros[101]=1;}
	if (isset($aParametros[102])==0){$aParametros[102]=20;}
	if (isset($aParametros[103])==0){$aParametros[103]='';}
	$aParametros[103]=numeros_validar($aParametros[103]);
	$sDebug='';
	$pagina=$aParametros[101];
	$lineastabla=$aParametros[102];
	$aGraficos=array();
	$babierta=true;
	$sLeyenda='';
    $objGraficos=new ClsGraficos('canvas_f2357grafico','map', $ETI['cara57mapa']);
	if ($aParametros[103]==''){
		$sLeyenda='<div class="salto1px"></div>
<div class="GrupoCamposAyuda">
<b>Debe seleccionar un periodo para ejecutar la consulta</b>
<div class="salto1px"></div>
</div>';
		return array(utf8_encode($sLeyenda), $sDebug);
		}
	$idPeraca=$aParametros[103];
	$sSQLadd='';
	$sSQLadd1='';

	list($aMatriculados, $aConfiguracion, $aTotalesMapa, $sDebugD)=f2357_CargarDataDashboard($aParametros[103], $objDB, $objGraficos, $bDebug);
	list($sHTMLMapa, $sScriptMapa, $sDebugG) = f2357_HtmlGrafico($aTotalesMapa, $objGraficos, $bDebug);
	$sDebug=$sDebug.$sDebugD.$sDebugG;
	foreach ($aMatriculados as $sGrafico=>$aValores) {
        $sHTML='<canvas id="'.$sGrafico.'" class="lienzo" width="400" height="365"></canvas>';
        $aDatos = array_column($aValores,'total');
        array_multisort($aDatos, SORT_DESC, $aValores, $aConfiguracion[$sGrafico]['colorFondo'], $aConfiguracion[$sGrafico]['colorLinea']);
        $aEtiquetas = array_column($aValores,'nombre');
        $aDatos = array_column($aValores,'cantidad');
        $aGraficos[$sGrafico] = array('sHTML'=>$sHTML, 'aEtiquetas'=>$aEtiquetas, 'aDatos'=>$aDatos, 'aConfiguracion'=>$aConfiguracion[$sGrafico]);
        }
	return array($aGraficos, $sHTMLMapa, $sScriptMapa, $sDebug);
	}
function f2357_HtmlDashboard($aParametros){
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
	list($aDetalle, $sHTMLMapa, $sScriptMapa, $sDebugTabla)=f2357_DashboardDetalle($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	$objDB->CerrarConexion();
	$objResponse=new xajaxResponse();
	$objResponse->call('pintarGraficosf2357('.json_encode($aDetalle).')');
	//$objResponse->assign('div_f2357detalle', 'innerHTML', $sDetalle);
	$objResponse->assign('div_f2357grafico', 'innerHTML', $sHTMLMapa);
	$objResponse->assign('div_f2357dashboard', 'style.display', 'block');
    $objResponse->script($sScriptMapa);
	if ($bDebug){
		$objResponse->assign('div_debug', 'innerHTML', $sDebug);
		}
	return $objResponse;
	}
//
function f2357_HtmlGrafico($aParametros, $objGraficos, $bDebug=false){
    $_SESSION['u_ultimominuto']=iminutoavance();
    $sError='';
    $bDebug=false;
    $sDebug='';
    $opts=$aParametros;
    if(!is_array($opts)){$opts=json_decode(str_replace('\"','"',$opts),true);}
    if (isset($opts[99])!=0){if ($opts[99]==1){$bDebug=true;}}
    require './app.php';
    $sDetalle=$objGraficos->construyeLienzo(400,500);
	$aTitulos = array('ZCAR' => 'Zona Caribe', 'ZOCC' => 'Zona Occidente', 'ZCORI' => 'Zona Centro Oriente', 'ZCBOY' => 'Zona Centro Boyacá', 'ZCBOG' => 'Zona Centro Bogotá Cundinamarca', 'ZSUR' => 'Zona Sur', 'ZCSUR' => 'Zona Centro Sur', 'ZAO' => 'Zona Amazonas Orinoquia');
	$aContenidoMapa = array('ZCAR' => '', 'ZOCC' => '', 'ZCORI' => '', 'ZCBOY' => '', 'ZCBOG' => '', 'ZSUR' => '', 'ZCSUR' => '', 'ZAO' => '');
	foreach($aParametros as $sClave => $aValor) {		
		$sEncabezadoTabla = '<h6>' . $sClave . ' - ' . $aValor['total'] . ' Matriculados</h6>
		<table style=\"font-size:12px;\">
			<thead>
				<tr><th rowspan=\"2\">Centro</th><th colspan=\"3\" style=\"text-align: center;\">Matriculados</th></tr>
				<tr><th>Antiguos</th><th>Nuevos</th><th>Total</th></tr>
			</thead>
		<tbody>';		
		$sTotales = '<tr>
			<th>Total:</th>
			<th style=\"text-align: right; border-top: 1px solid black;\">' . $aValor['antiguos'] . '</th>
			<th style=\"text-align: right; border-top: 1px solid black;\">' . $aValor['nuevos'] . '</th>
			<th style=\"text-align: right; border-top: 1px solid black; border-left: 1px solid black;\">' . $aValor['total'] . '</th>
		</tr>';
		if (isset($aValor['sedes'])) {
			foreach($aValor['sedes'] as $sClave1 => $aValor1) {
				$aContenidoMapa[$sClave] .= '<tr>
					<td>' . $sClave1 . '</td>
					<td style=\"text-align: right;\">' . $aValor1['antiguos'] . '</td>
					<td style=\"text-align: right;\">' . $aValor1['nuevos'] . '</td>
					<td style=\"text-align: right; font-weight: bold;\">' . $aValor1['total'] . '</td>
				</tr>';				
			}		
		}
		$aContenidoMapa[$sClave] = preg_replace("/\r|\n|\t/", "", $sEncabezadoTabla . $aContenidoMapa[$sClave] . $sTotales . '</tbody></table>');
	}
    $sDetalleScript=$objGraficos->construyeScript($aTitulos,$aContenidoMapa);
    return array($sDetalle, $sDetalleScript, $sDebug);
    }
?>