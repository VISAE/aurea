<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2023 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Version 2.29.2 lunes, 13 de febrero de 2023
*/
//error_reporting(E_ALL);
//ini_set("display_errors", 1);
if (file_exists('./err_control.php')) {
	require './err_control.php';
}
if (!file_exists('./app.php')) {
	echo '<b>Error N 1 de instalaci&oacute;n</b><br>No se ha establecido un archivo de configuraci&oacute;n, por favor comuniquese con el administrador del sistema . ';
	die();
}
mb_internal_encoding('UTF-8');
require './app.php';
require $APP->rutacomun . 'unad_todas.php';
require $APP->rutacomun . 'libs/clsdbadmin.php';
require $APP->rutacomun . 'unad_librerias.php';
require $APP->rutacomun . 'fpdf/fpdf.php';
require $APP->rutacomun . 'libp.php';
class clsPDF extends FPDF
{
	var $aDebug = array();
	var $bCodificar = false;
	var $bConPagina = true;
	var $bConFechaImprime = true;
	var $bDebug = false;
	var $iAnchoFondo = 0;
	var $iAnchoLibre = 186;
	var $iAnchoTotal = 216;
	var $iAltoTotal = 279;
	var $iBordeEncabezado = 10;
	var $iBordeSuperior = 25;
	var $iBordeInferior = 10;
	var $iBordeIzquierda = 15;
	var $iBordeDerecha = 15;
	var $iDebug = 0;
	var $iFormato = 0;
	var $iFuenteTamGrande = 14;
	var $iFuenteTamNormal = 12;
	var $iFuenteTamMedia = 11;
	var $iFuenteTamPequena = 10;
	var $iFuenteTamMini = 8;
	var $iReporte = 0;
	var $iSector = 0;
	var $filaent = NULL;
	var $filaentorno = NULL;
	var $sDetalleTitulo = '';
	var $sDetalleHoja = '';
	var $sError = '';
	var $sFirmaReporte = 'http://www.unad.edu.co';
	var $sFondo = '';
	var $sFuenteFamilia = 'Arial';
	var $sFuenteFamilia2 = 'Courier';
	var $sRefRpt = '';
	var $sNumCopia = '';
	var $sTituloReporte = 'Bitacora de desarrollo';
	//var $smes = array('', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
	var $xPrevia = 0;
	var $yPrevia = 0;
	//se Crean porque no permite en modo seguro tenerlas en forma implicita
	var $HREF = '';
	var $B = '';
	var $I = '';
	var $U = '';
	//Armado del indice
	var $bNumerarTitulos = true;
	var $iNumTitulo1 = 0;
	var $iNumTitulo2 = 0;
	var $iNumTitulo3 = 0;
	var $sNumSepara = ' ';
	// -- Funciones para encriptar
	var $encrypted = false;
	var $Uvalue;
	var $Ovalue;
	var $Pvalue;
	var $enc_obj_id;
	function SetProtection($permissions = array(), $user_pass = '', $owner_pass = null)
	{
		$options = array('print' => 4, 'modify' => 8, 'copy' => 16, 'annot-forms' => 32);
		$protection = 192;
		foreach ($permissions as $permission) {
			if (!isset($options[$permission])) {
				$this->Error('Incorrect permission: ' . $permission);
			}
			$protection += $options[$permission];
		}
		if ($owner_pass === null) {
			$owner_pass = uniqid(rand());
		}
		$this->encrypted = true;
		$this->padding = "\x28\xBF\x4E\x5E\x4E\x75\x8A\x41\x64\x00\x4E\x56\xFF\xFA\x01\x08\x2E\x2E\x00\xB6\xD0\x68\x3E\x80\x2F\x0C\xA9\xFE\x64\x53\x69\x7A";
		$this->_generateencryptionkey($user_pass, $owner_pass, $protection);
	}
	function _putstream($s)
	{
		if ($this->encrypted) {
			$s = RC4($this->_objectkey($this->n), $s);
		}
		parent::_putstream($s);
	}
	function _textstring($s)
	{
		if ($this->encrypted) {
			$s = RC4($this->_objectkey($this->n), $s);
		}
		return parent::_textstring($s);
	}
	function _objectkey($n)
	{
		return substr($this->_md5_16($this->encryption_key . pack('VXxx', $n)), 0, 10);
	}
	function _putresources()
	{
		parent::_putresources();
		if ($this->encrypted) {
			$this->_newobj();
			$this->enc_obj_id = $this->n;
			$this->_out('<<');
			$this->_putencryption();
			$this->_out('>>');
			$this->_out('endobj');
		}
	}
	function _putencryption()
	{
		$this->_out('/Filter /Standard');
		$this->_out('/V 1');
		$this->_out('/R 2');
		$this->_out('/O (' . $this->_escape($this->Ovalue) . ')');
		$this->_out('/U (' . $this->_escape($this->Uvalue) . ')');
		$this->_out('/P ' . $this->Pvalue);
	}
	function _puttrailer()
	{
		parent::_puttrailer();
		if ($this->encrypted) {
			$this->_out('/Encrypt ' . $this->enc_obj_id . ' 0 R');
			$this->_out('/ID [()()]');
		}
	}
	function _md5_16($string)
	{
		return pack('H*', md5($string));
	}
	function _Ovalue($user_pass, $owner_pass)
	{
		$tmp = $this->_md5_16($owner_pass);
		$owner_RC4_key = substr($tmp, 0, 5);
		return RC4($owner_RC4_key, $user_pass);
	}
	function _Uvalue()
	{
		return RC4($this->encryption_key, $this->padding);
	}
	function _generateencryptionkey($user_pass, $owner_pass, $protection)
	{
		$user_pass = substr($user_pass . $this->padding, 0, 32);
		$owner_pass = substr($owner_pass . $this->padding, 0, 32);
		$this->Ovalue = $this->_Ovalue($user_pass, $owner_pass);
		$tmp = $this->_md5_16($user_pass . $this->Ovalue . chr($protection) . "\xFF\xFF\xFF");
		$this->encryption_key = substr($tmp, 0, 5);
		$this->Uvalue = $this->_Uvalue();
		$this->Pvalue = - (($protection ^ 255) + 1);
	}
	// -- Fin de poner encriptacion.
	//Encabezado
	function Header()
	{
		//Aqui va el encabezado
		if ($this->iSector == 98) {
			p_FuenteGrandeV2($this, 'B');
			$this->Cell($this->iAnchoLibre, 5, cadena_codificar('Información de depuración'), 0, 0, 'C');
			$this->ln();
			return;
		}
		$iConFondo = 0;
		if ($this->sFondo != '') {
			if (file_exists($this->sFondo)) {
				$this->Image($this->sFondo, 0, 0, $this->iAnchoFondo);
				$iConFondo = 1;
			}
		}
		$this->SetY(5);
		p_FuentePequenaV2($this);
		$this->SetTextColor(0, 0, 130);
		$this->Cell($this->iAnchoLibre + 10, 3, cadena_codificar('Sistema Integrado de Información 4.0'), 0, 0, 'R');
		$this->ln();
		$this->Cell($this->iAnchoLibre + 10, 3, 'Plataforma AUREA', 0, 0, 'R');
		$this->ln();
		$this->SetTextColor(0, 0, 0);
		$yPos = $this->GetY();
		if ($yPos > $this->iBordeEncabezado) {
			$this->SetY($this->iBordeEncabezado);
		}
		if ($iConFondo == 0) {
			p_TituloEntidad($this, false);
		} else {
			p_FuenteGrandeV2($this, 'B');
		}
		//Ubique aqui los componentes adicionales del encabezado
		//$this->SetFont('Arial', 'B', 14);
		$this->Cell($this->iAnchoLibre, 5, $this->sTituloReporte . ' ' . $this->sRefRpt, 0, 0, 'C');
		$this->ln();
		//p_FuenteNormalV2($this);
		if ($this->sDetalleTitulo != '') {
			$this->Cell($this->iAnchoLibre, 5, $this->sDetalleTitulo, 0, 0, 'C');
			$this->ln();
		}
		if ($this->sDetalleHoja != '') {
			//$this->Cell(120, 5, 'Detalle: ', 0, 0, 'R');
			//p_FuenteNormalV2($this, 'B');
			$this->Cell($this->iAnchoLibre, 5, $this->sDetalleHoja, 0, 0, 'C');
			$this->ln();
		}
		$yPos = $this->GetY();
		if ($yPos < $this->iBordeSuperior) {
			$this->SetY($this->iBordeSuperior);
		}
	}
	//Pie de página
	function Footer()
	{
		$bModMargen = false;
		if ($this->bConPagina) {
			$bModMargen = true;
		}
		if ($this->bConFechaImprime) {
			$bModMargen = true;
		}
		if (trim($this->sFirmaReporte) != '') {
			$bModMargen = true;
		}
		if ($bModMargen) {
			$this->SetRightMargin(5);
		}
		if ($this->bConPagina) {
			$this->SetY(-8);
			$this->SetFont('Arial', 'I', 8);
			$sEtiqueta = 'Página ';
			$this->Cell(0, 5, cadena_codificar($sEtiqueta) . $this->PageNo() . ' de {nb}', 0, 0, 'R');
		}
		$sEtiqueta = '';
		if ($this->sNumCopia != '') {
			$sEtiqueta = 'Copia ' . $this->sNumCopia . ' - ';
		}
		if ($this->bConFechaImprime) {
			$sEtFImp = 'Fecha de impresión ';
			$sEtiqueta = $sEtiqueta . cadena_codificar($sEtFImp) . formato_fechalarga(fecha_hoy(), true) . ' ' . html_TablaHoraMin(fecha_hora(), fecha_minuto());
		}
		if ($sEtiqueta != '') {
			$this->SetY(-8);
			$this->SetFont('Arial', 'I', 8);
			$this->Cell(0, 5, $sEtiqueta);
		}
		if (trim($this->sFirmaReporte) != '') {
			$this->SetY(-4);
			$this->SetFont('Arial', '', 7);
			$this->Cell(0, 3, $this->sFirmaReporte, 0, 0, 'R');
		}
		if ($bModMargen) {
			$this->SetRightMargin($this->iBordeDerecha);
		}
	}
	//Funciones del reporte.
	function ArmarReporte251($PARAMS, $objDB)
	{
		$this->SetTextColor(0, 0, 0);
		$this->SetFillColor(0, 0, 0);
		$this->SetDrawColor(0, 0, 0);
		p_FuenteNormalV2($this);
		//$iPuntoX = $this->GetX();
		//$sTitulo = 'Titulo 1';
		//$sNumera=p_AddIndice($sTitulo, $this->PageNo(), 1, $this);
		$sSQL = 'SELECT * FROM aure51bitacora WHERE aure51id=' . $PARAMS['id251'] . '';
		if ($this->bDebug) {
			p_AddDebug('Consulta para el reporte ' . $sSQL, $this);
		}
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			$saure51idproyecto = $fila['aure51idproyecto'];
			$sSQL = 'SELECT bita09titulo FROM bita09proyecto WHERE bita09id=' . $fila['aure51idproyecto'];
			$tablat = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablat) > 0) {
				$filat = $objDB->sf($tablat);
				$saure51idproyecto = $filat['bita09titulo'];
				if ($this->bCodificar) {
					$saure51idproyecto = cadena_codificar($filat['bita09titulo']);
				}
			}
			$this->Cell($this->iAnchoLibre, 5, 'Proyecto: ' . cadena_codificar($saure51idproyecto));
			$this->ln();
			$this->Cell($this->iAnchoLibre, 5, 'Consec: ' . $fila['aure51consec']);
			$this->ln();
			$this->Cell($this->iAnchoLibre, 5, 'Padre: ' . $fila['aure51idpadre']);
			$this->ln();
			$this->Cell($this->iAnchoLibre, 5, 'Orden: ' . $fila['aure51orden']);
			$this->ln();
			$this->Cell($this->iAnchoLibre, 5, 'Estado: ' . $fila['aure51estado']);
			$this->ln();
			$this->Cell($this->iAnchoLibre, 5, 'Fecha: ' . $fila['aure51fecha']);
			$this->ln();
			$this->Cell($this->iAnchoLibre, 5, 'Horaini: ' . $fila['aure51horaini']);
			$this->ln();
			$this->Cell($this->iAnchoLibre, 5, 'Minini: ' . $fila['aure51minini']);
			$this->ln();
			$this->Cell($this->iAnchoLibre, 5, 'Horafin: ' . $fila['aure51horafin']);
			$this->ln();
			$this->Cell($this->iAnchoLibre, 5, 'Minfin: ' . $fila['aure51minfin']);
			$this->ln();
			$saure51idsistema = $fila['aure51idsistema'];
			$sSQL = 'SELECT unad01nombre FROM unad01sistema WHERE unad01id=' . $fila['aure51idsistema'];
			$tablat = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablat) > 0) {
				$filat = $objDB->sf($tablat);
				$saure51idsistema = $filat['unad01nombre'];
				if ($this->bCodificar) {
					$saure51idsistema = cadena_codificar($filat['unad01nombre']);
				}
			}
			$this->Cell($this->iAnchoLibre, 5, 'Sistema: ' . cadena_codificar($saure51idsistema));
			$this->ln();
			$this->Cell($this->iAnchoLibre, 5, 'Actividad: ' . $fila['aure51actividad']);
			$this->ln();
			$this->Cell($this->iAnchoLibre, 5, 'Lugar: ' . $fila['aure51lugar']);
			$this->ln();
			$this->Cell($this->iAnchoLibre, 5, 'Detalleactiv: ' . $fila['aure51detalleactiv']);
			$this->ln();
			$this->Cell($this->iAnchoLibre, 5, 'Objetivo: ' . $fila['aure51objetivo']);
			$this->ln();
			$this->Cell($this->iAnchoLibre, 5, 'Resultado: ' . $fila['aure51resultado']);
			$this->ln();
			$et_aure51idresponsable = p_DatosTercero($fila['aure51idresponsable'], $objDB);
			if ($this->bCodificar) {
				$et_aure51idresponsable = cadena_codificar($et_aure51idresponsable);
			}
			$this->Cell($this->iAnchoLibre, 5, 'Responsable: ' . cadena_codificar($et_aure51idresponsable));
			$this->ln();
			$saure51tiporesultado = $fila['aure51tiporesultado'];
			$sSQL = 'SELECT aure59nombre FROM aure59tiporesult WHERE aure59id=' . $fila['aure51tiporesultado'];
			$tablat = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablat) > 0) {
				$filat = $objDB->sf($tablat);
				$saure51tiporesultado = $filat['aure59nombre'];
				if ($this->bCodificar) {
					$saure51tiporesultado = cadena_codificar($filat['aure59nombre']);
				}
			}
			$this->Cell($this->iAnchoLibre, 5, 'Tiporesultado: ' . cadena_codificar($saure51tiporesultado));
			$this->ln();
			$sTitulo = 'Participantes';
			//$sNumera=p_AddIndice($sTitulo, $this->PageNo(), 1, $this);
			p_TituloBloque($sTitulo, $this);
			p_FuenteNormalV2($this);
			$sSQL = 'SELECT * FROM aure52bitaparticipa WHERE aure52idbitacora=' . $fila['aure51id'] . '';
			if ($this->bDebug) {
				p_AddDebug('Consulta hija ' . $sSQL, $this);
			}
			$tabla1 = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla1) > 0) {
				//Encabezados.
				$c1 = 20;
				$c2 = 20;
				$c3 = 20;
				$c4 = 20;
				$this->Cell($c2, 5, 'Tercero');
				$this->Cell($c4, 5, 'Activo');
				$this->ln();
			}
			while ($fila1 = $objDB->sf($tabla1)) {
				$et_aure52idtercero = p_DatosTercero($fila1['aure52idtercero'], $objDB);
				if ($this->bCodificar) {
					$et_aure52idtercero = cadena_codificar($et_aure52idtercero);
				}
				$this->Cell($c1, 5, cadena_codificar($et_aure52idtercero));
				$saure52activo = $fila1['aure52activo'];
				$sSQL = 'SELECT  FROM  WHERE =' . $fila1['aure52activo'];
				$tablat = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($tablat) > 0) {
					$filat = $objDB->sf($tablat);
					$saure52activo = $filat[''];
					if ($this->bCodificar) {
						$saure52activo = cadena_codificar($filat['']);
					}
				}
				$this->Cell($c2, 5, cadena_codificar($saure52activo));
				$this->ln();
			}
			p_Separador($this);
			$sTitulo = 'Anexos';
			//$sNumera=p_AddIndice($sTitulo, $this->PageNo(), 1, $this);
			p_TituloBloque($sTitulo, $this);
			p_FuenteNormalV2($this);
			$sSQL = 'SELECT * FROM aure58anexos WHERE aure58idbitacora=' . $fila['aure51id'] . '';
			if ($this->bDebug) {
				p_AddDebug('Consulta hija ' . $sSQL, $this);
			}
			$tabla2 = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla2) > 0) {
				//Encabezados.
				$c1 = 20;
				$c2 = 20;
				$c3 = 20;
				$c4 = 20;
				$c5 = 20;
				$c6 = 20;
				$c7 = 20;
				$c8 = 20;
				$this->Cell($c2, 5, 'Consec');
				$this->Cell($c4, 5, 'Titulo');
				$this->Cell($c5, 5, 'Origen');
				$this->Cell($c6, 5, 'Archivo');
				$this->Cell($c7, 5, 'Usuario');
				$this->Cell($c8, 5, 'Fecha');
				$this->ln();
			}
			while ($fila2 = $objDB->sf($tabla2)) {
				$this->Cell($c1, 5, $fila2['aure58consec']);
				$this->Cell($c2, 5, $fila2['aure58titulo']);
				$this->Cell($c3, 5, $fila2['aure58idorigen']);
				$this->Cell($c4, 5, $fila2['aure58idarchivo']);
				$et_aure58idusuario = p_DatosTercero($fila2['aure58idusuario'], $objDB);
				if ($this->bCodificar) {
					$et_aure58idusuario = cadena_codificar($et_aure58idusuario);
				}
				$this->Cell($c5, 5, cadena_codificar($et_aure58idusuario));
				$this->Cell($c6, 5, $fila2['aure58fecha']);
				$this->ln();
			}
			p_Separador($this);
			$sTitulo = 'Riesgos';
			//$sNumera=p_AddIndice($sTitulo, $this->PageNo(), 1, $this);
			p_TituloBloque($sTitulo, $this);
			p_FuenteNormalV2($this);
			$sSQL = 'SELECT * FROM aure57riesgobitacora WHERE aure57idbitacora=' . $fila['aure51id'] . '';
			if ($this->bDebug) {
				p_AddDebug('Consulta hija ' . $sSQL, $this);
			}
			$tabla3 = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla3) > 0) {
				//Encabezados.
				$c1 = 20;
				$c2 = 20;
				$c3 = 20;
				$c4 = 20;
				$this->Cell($c2, 5, 'Riesgo');
				$this->Cell($c4, 5, 'Nivelriesgo');
				$this->ln();
			}
			while ($fila3 = $objDB->sf($tabla3)) {
				$this->Cell($c1, 5, $fila3['aure57idriesgo']);
				$saure57nivelriesgo = $fila3['aure57nivelriesgo'];
				$sSQL = 'SELECT  FROM  WHERE =' . $fila3['aure57nivelriesgo'];
				$tablat = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($tablat) > 0) {
					$filat = $objDB->sf($tablat);
					$saure57nivelriesgo = $filat[''];
					if ($this->bCodificar) {
						$saure57nivelriesgo = cadena_codificar($filat['']);
					}
				}
				$this->Cell($c2, 5, cadena_codificar($saure57nivelriesgo));
				$this->ln();
			}
			p_Separador($this);
			$sTitulo = 'Historia de usuario';
			//$sNumera=p_AddIndice($sTitulo, $this->PageNo(), 1, $this);
			p_TituloBloque($sTitulo, $this);
			p_FuenteNormalV2($this);
			$sSQL = 'SELECT * FROM aure80historiaus WHERE aure80idbitacora=' . $fila['aure51id'] . '';
			if ($this->bDebug) {
				p_AddDebug('Consulta hija ' . $sSQL, $this);
			}
			$tabla4 = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla4) > 0) {
				//Encabezados.
				$c1 = 20;
				$c2 = 20;
				$c3 = 20;
				$c4 = 20;
				$c5 = 20;
				$c6 = 20;
				$c7 = 20;
				$c8 = 20;
				$c9 = 20;
				$c10 = 20;
				$this->Cell($c2, 5, 'Consec');
				$this->Cell($c4, 5, 'Momento');
				$this->Cell($c5, 5, 'Infousuario');
				$this->Cell($c6, 5, 'Prioridad');
				$this->Cell($c7, 5, 'Semanaest');
				$this->Cell($c8, 5, 'Diasest');
				$this->Cell($c9, 5, 'Iteracionasig');
				$this->Cell($c10, 5, 'Infotecnica');
				$this->ln();
			}
			while ($fila4 = $objDB->sf($tabla4)) {
				$this->Cell($c1, 5, $fila4['aure80consec']);
				$saure80momento = $fila4['aure80momento'];
				$sSQL = 'SELECT aure53nombre FROM aure53hmomento WHERE aure53id=' . $fila4['aure80momento'];
				$tablat = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($tablat) > 0) {
					$filat = $objDB->sf($tablat);
					$saure80momento = $filat['aure53nombre'];
					if ($this->bCodificar) {
						$saure80momento = cadena_codificar($filat['aure53nombre']);
					}
				}
				$this->Cell($c2, 5, cadena_codificar($saure80momento));
				$this->Cell($c3, 5, $fila4['aure80infousuario']);
				$saure80prioridad = $fila4['aure80prioridad'];
				$sSQL = 'SELECT  FROM  WHERE =' . $fila4['aure80prioridad'];
				$tablat = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($tablat) > 0) {
					$filat = $objDB->sf($tablat);
					$saure80prioridad = $filat[''];
					if ($this->bCodificar) {
						$saure80prioridad = cadena_codificar($filat['']);
					}
				}
				$this->Cell($c4, 5, cadena_codificar($saure80prioridad));
				$this->Cell($c5, 5, $fila4['aure80semanaest']);
				$this->Cell($c6, 5, $fila4['aure80diasest']);
				$this->Cell($c7, 5, $fila4['aure80iteracionasig']);
				$this->Cell($c8, 5, $fila4['aure80infotecnica']);
				$this->ln();
			}
			p_Separador($this);
			$sTitulo = 'Tareas de ingenieria';
			//$sNumera=p_AddIndice($sTitulo, $this->PageNo(), 1, $this);
			p_TituloBloque($sTitulo, $this);
			p_FuenteNormalV2($this);
			$sSQL = 'SELECT * FROM aure81tareaing WHERE aure81idbitacora=' . $fila['aure51id'] . '';
			if ($this->bDebug) {
				p_AddDebug('Consulta hija ' . $sSQL, $this);
			}
			$tabla5 = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla5) > 0) {
				//Encabezados.
				$c1 = 20;
				$c2 = 20;
				$c3 = 20;
				$c4 = 20;
				$c5 = 20;
				$c6 = 20;
				$c7 = 20;
				$c8 = 20;
				$c9 = 20;
				$c10 = 20;
				$this->Cell($c2, 5, 'Consec');
				$this->Cell($c4, 5, 'Bithistoria');
				$this->Cell($c5, 5, 'Tipotarea');
				$this->Cell($c6, 5, 'Semanasest');
				$this->Cell($c7, 5, 'Diasest');
				$this->Cell($c8, 5, 'Fechainicio');
				$this->Cell($c9, 5, 'Avance');
				$this->Cell($c10, 5, 'Fechafinal');
				$this->ln();
			}
			while ($fila5 = $objDB->sf($tabla5)) {
				$this->Cell($c1, 5, $fila5['aure81consec']);
				$saure81idbithistoria = $fila5['aure81idbithistoria'];
				$sSQL = 'SELECT aure80consec FROM aure80historiaus WHERE aure80id=' . $fila5['aure81idbithistoria'];
				$tablat = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($tablat) > 0) {
					$filat = $objDB->sf($tablat);
					$saure81idbithistoria = $filat['aure80consec'];
					if ($this->bCodificar) {
						$saure81idbithistoria = cadena_codificar($filat['aure80consec']);
					}
				}
				$this->Cell($c2, 5, cadena_codificar($saure81idbithistoria));
				$saure81idtipotarea = $fila5['aure81idtipotarea'];
				$sSQL = 'SELECT aure54nombre FROM aure54tipotarea WHERE aure54id=' . $fila5['aure81idtipotarea'];
				$tablat = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($tablat) > 0) {
					$filat = $objDB->sf($tablat);
					$saure81idtipotarea = $filat['aure54nombre'];
					if ($this->bCodificar) {
						$saure81idtipotarea = cadena_codificar($filat['aure54nombre']);
					}
				}
				$this->Cell($c3, 5, cadena_codificar($saure81idtipotarea));
				$this->Cell($c4, 5, $fila5['aure81semanasest']);
				$this->Cell($c5, 5, $fila5['aure81diasest']);
				$this->Cell($c6, 5, $fila5['aure81fechainicio']);
				$saure81avance = $fila5['aure81avance'];
				$sSQL = 'SELECT  FROM  WHERE =' . $fila5['aure81avance'];
				$tablat = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($tablat) > 0) {
					$filat = $objDB->sf($tablat);
					$saure81avance = $filat[''];
					if ($this->bCodificar) {
						$saure81avance = cadena_codificar($filat['']);
					}
				}
				$this->Cell($c7, 5, cadena_codificar($saure81avance));
				$this->Cell($c8, 5, $fila5['aure81fechafinal']);
				$this->ln();
			}
			p_Separador($this);
			$sTitulo = 'Pruebas de aceptacion';
			//$sNumera=p_AddIndice($sTitulo, $this->PageNo(), 1, $this);
			p_TituloBloque($sTitulo, $this);
			p_FuenteNormalV2($this);
			$sSQL = 'SELECT * FROM aure82pruebaac WHERE aure82idbitacora=' . $fila['aure51id'] . '';
			if ($this->bDebug) {
				p_AddDebug('Consulta hija ' . $sSQL, $this);
			}
			$tabla6 = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla6) > 0) {
				//Encabezados.
				$c1 = 20;
				$c2 = 20;
				$c3 = 20;
				$c4 = 20;
				$c5 = 20;
				$c6 = 20;
				$c7 = 20;
				$c8 = 20;
				$c9 = 20;
				$c10 = 20;
				$this->Cell($c2, 5, 'Consec');
				$this->Cell($c4, 5, 'Condiciones');
				$this->Cell($c5, 5, 'Pasos');
				$this->Cell($c6, 5, 'Asignaperfil');
				$this->Cell($c7, 5, 'Manuales');
				$this->Cell($c8, 5, 'Capacitacion');
				$this->Cell($c9, 5, 'Evaluacion');
				$this->Cell($c10, 5, 'Resultadoesp');
				$this->ln();
			}
			while ($fila6 = $objDB->sf($tabla6)) {
				$this->Cell($c1, 5, $fila6['aure82consec']);
				$this->Cell($c2, 5, $fila6['aure82condiciones']);
				$this->Cell($c3, 5, $fila6['aure82pasos']);
				$saure82asignaperfil = $fila6['aure82asignaperfil'];
				$sSQL = 'SELECT  FROM  WHERE =' . $fila6['aure82asignaperfil'];
				$tablat = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($tablat) > 0) {
					$filat = $objDB->sf($tablat);
					$saure82asignaperfil = $filat[''];
					if ($this->bCodificar) {
						$saure82asignaperfil = cadena_codificar($filat['']);
					}
				}
				$this->Cell($c4, 5, cadena_codificar($saure82asignaperfil));
				$saure82manuales = $fila6['aure82manuales'];
				$sSQL = 'SELECT  FROM  WHERE =' . $fila6['aure82manuales'];
				$tablat = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($tablat) > 0) {
					$filat = $objDB->sf($tablat);
					$saure82manuales = $filat[''];
					if ($this->bCodificar) {
						$saure82manuales = cadena_codificar($filat['']);
					}
				}
				$this->Cell($c5, 5, cadena_codificar($saure82manuales));
				$saure82capacitacion = $fila6['aure82capacitacion'];
				$sSQL = 'SELECT  FROM  WHERE =' . $fila6['aure82capacitacion'];
				$tablat = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($tablat) > 0) {
					$filat = $objDB->sf($tablat);
					$saure82capacitacion = $filat[''];
					if ($this->bCodificar) {
						$saure82capacitacion = cadena_codificar($filat['']);
					}
				}
				$this->Cell($c6, 5, cadena_codificar($saure82capacitacion));
				$saure82evaluacion = $fila6['aure82evaluacion'];
				$sSQL = 'SELECT  FROM  WHERE =' . $fila6['aure82evaluacion'];
				$tablat = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($tablat) > 0) {
					$filat = $objDB->sf($tablat);
					$saure82evaluacion = $filat[''];
					if ($this->bCodificar) {
						$saure82evaluacion = cadena_codificar($filat['']);
					}
				}
				$this->Cell($c7, 5, cadena_codificar($saure82evaluacion));
				$this->Cell($c8, 5, $fila6['aure82resultadoesp']);
				$this->ln();
			}
			p_Separador($this);
			$sTitulo = 'Tarjetas CRC';
			//$sNumera=p_AddIndice($sTitulo, $this->PageNo(), 1, $this);
			p_TituloBloque($sTitulo, $this);
			p_FuenteNormalV2($this);
			$sSQL = 'SELECT * FROM aure83tarjetacrc WHERE aure83idbitacora=' . $fila['aure51id'] . '';
			if ($this->bDebug) {
				p_AddDebug('Consulta hija ' . $sSQL, $this);
			}
			$tabla7 = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla7) > 0) {
				//Encabezados.
				$c1 = 20;
				$c2 = 20;
				$c3 = 20;
				$c4 = 20;
				$c5 = 20;
				$c6 = 20;
				$c7 = 20;
				$c8 = 20;
				$c9 = 20;
				$c10 = 20;
				$this->Cell($c2, 5, 'Consec');
				$this->Cell($c4, 5, 'Bithistoria');
				$this->Cell($c5, 5, 'Tarea');
				$this->Cell($c6, 5, 'Vigente');
				$this->Cell($c7, 5, 'Nombreclase');
				$this->Cell($c8, 5, 'Responsabilidades');
				$this->Cell($c9, 5, 'Colaboradores');
				$this->Cell($c10, 5, 'Tabla');
				$this->ln();
			}
			while ($fila7 = $objDB->sf($tabla7)) {
				$this->Cell($c1, 5, $fila7['aure83consec']);
				$saure83idbithistoria = $fila7['aure83idbithistoria'];
				$sSQL = 'SELECT aure80consec FROM aure80historiaus WHERE aure80id=' . $fila7['aure83idbithistoria'];
				$tablat = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($tablat) > 0) {
					$filat = $objDB->sf($tablat);
					$saure83idbithistoria = $filat['aure80consec'];
					if ($this->bCodificar) {
						$saure83idbithistoria = cadena_codificar($filat['aure80consec']);
					}
				}
				$this->Cell($c2, 5, cadena_codificar($saure83idbithistoria));
				$saure83idtarea = $fila7['aure83idtarea'];
				$sSQL = 'SELECT aure81consec FROM aure81tareaing WHERE aure81id=' . $fila7['aure83idtarea'];
				$tablat = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($tablat) > 0) {
					$filat = $objDB->sf($tablat);
					$saure83idtarea = $filat['aure81consec'];
					if ($this->bCodificar) {
						$saure83idtarea = cadena_codificar($filat['aure81consec']);
					}
				}
				$this->Cell($c3, 5, cadena_codificar($saure83idtarea));
				$saure83vigente = $fila7['aure83vigente'];
				$sSQL = 'SELECT  FROM  WHERE =' . $fila7['aure83vigente'];
				$tablat = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($tablat) > 0) {
					$filat = $objDB->sf($tablat);
					$saure83vigente = $filat[''];
					if ($this->bCodificar) {
						$saure83vigente = cadena_codificar($filat['']);
					}
				}
				$this->Cell($c4, 5, cadena_codificar($saure83vigente));
				$this->Cell($c5, 5, $fila7['aure83nombreclase']);
				$this->Cell($c6, 5, $fila7['aure83responsabilidades']);
				$this->Cell($c7, 5, $fila7['aure83colaboradores']);
				$saure83idtabla = $fila7['aure83idtabla'];
				$sSQL = 'SELECT  FROM  WHERE =' . $fila7['aure83idtabla'];
				$tablat = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($tablat) > 0) {
					$filat = $objDB->sf($tablat);
					$saure83idtabla = $filat[''];
					if ($this->bCodificar) {
						$saure83idtabla = cadena_codificar($filat['']);
					}
				}
				$this->Cell($c8, 5, cadena_codificar($saure83idtabla));
				$this->ln();
			}
			p_Separador($this);
		}
		// Fin de ArmarReporte251
	}
	// Fin de clsPDF
}
function pdfReporteV2($iReporte, $PARAMS, $iFormato, $sNumCopiaReporte, $bCodificarUTF8, $objDB, $bDebug = false)
{
	$objpdf = NULL;
	$sError = '';
	require './app.php';
	if ($objDB == NULL) {
		$sError = 'No se ha definido un origen de datos';
	}
	if ($sError == '') {
		//Cargar los parametros previos.
	}
	// -- Validaciones de los parametros del reporte
	if ($sError == '') {
		$filaentorno = NULL;
		$sSQL = ''; //Aqui debe ubicar la consulta de entorno del reporte.
		/*
		if (isset($PARAMS['idtercero']) == 0) {
			$PARAMS['idtercero'] = '';
		}
		if ((int)$PARAMS['idtercero'] == 0) {
			$sError = 'No se ha ingresado un tercero';
		}
		*/
		if ($sSQL != '') {
			$tablaent = $objDB->ejecutasql($sSQL);
			if ($tablaent == false) {
				$sError = 'No fue posible cargar los datos del reporte [Entorno]<!-- ' . $sSQL . ' -->';
			} else {
				$filaentorno = $objDB->sf($tablaent);
			}
		}
	}
	// -- Empezamos la generacion del reporte
	if ($sError == '') {
		$iAncho = 216;
		$iAlto = 279;
		$TP = "Letter";
		$sPagina = '$TP="Letter";';
		$Posicion = 'P';
		$bConCFG = true;
		$iSup = 35;
		$rpt[$iReporte]['bordeencabezado'] = 20;
		$rpt[$iReporte]['bordesup'] = 25;
		$rpt[$iReporte]['bordeinf'] = 10;
		$rpt[$iReporte]['borde_izquierda'] = 15;
		$rpt[$iReporte]['borde_derecha'] = 15;
		$rpt[$iReporte]['fechaimpreso'] = 1;
		//$rpt[$iReporte]['fondo'] = $APP->rutacomun . 'imagenes/membrete.jpg';
		$rpt[$iReporte]['fondo'] = $APP->rutacomun . 'imagenes/membrete_borrador.png';
		$rpt[$iReporte]['pagina_formato'] = 0;
		$rpt[$iReporte]['pagina_orientacion'] = 0;
		if ($rpt[$iReporte]['pagina_orientacion'] == 1) {
			$Posicion = 'L';
			$iTemp = $iAncho;
			$iAncho = $iAlto;
			$iAlto = $iTemp;
		}
		$objpdf = new clsPDF($Posicion, 'mm', $TP);
		$objpdf->bDebug = $bDebug;
		$objpdf->iBordeEncabezado = $rpt[$iReporte]['bordeencabezado'];
		$objpdf->iBordeInferior = $rpt[$iReporte]['bordeinf'];
		$objpdf->iBordeIzquierda = $rpt[$iReporte]['borde_izquierda'];
		$objpdf->iBordeDerecha = $rpt[$iReporte]['borde_derecha'];
		if ($rpt[$iReporte]['fechaimpreso'] == 0) {
			$objpdf->bConFechaImprime = false;
		}
		if ($iSup > 0) {
			$objpdf->iBordeSuperior = $iSup;
		}
		$objpdf->iAnchoLibre = $iAncho - ($objpdf->iBordeIzquierda + $objpdf->iBordeDerecha);
		$objpdf->iAnchoTotal = $iAncho;
		$objpdf->iAltoTotal = $iAlto;
		p_AddFondo($rpt[$iReporte]['fondo'], $objpdf);
		$objpdf->SetTopMargin($objpdf->iBordeSuperior);
		$objpdf->SetLeftMargin($objpdf->iBordeIzquierda);
		$objpdf->SetRightMargin($objpdf->iBordeDerecha);
		$objpdf->SetAutoPageBreak(true, $objpdf->iBordeInferior);
		$sClave = '';
		if (isset($PARAMS['clave']) != 0) {
			$sClave = trim($PARAMS['clave']);
		}
		if ($sClave != '') {
			$objpdf->SetProtection(array(), $sClave);
		}
		$objpdf->sNumCopia = $sNumCopiaReporte;
		//Iniciar la generacion del reporte
		$objpdf->sTituloReporte = 'Bitacora de desarrollo';
		$sDetalle = '';
		/*
		if ($PARAMS['v7'] != '') {
			$sDetalle = 'Cuenta inicial: ' . $PARAMS['v7'];
		}
		*/
		$objpdf->sDetalleTitulo = $sDetalle;
		//$objpdf->sRefRpt = $PARAMS['id251'];
		$objpdf->AliasNbPages();
		$objpdf->bCodificar = $bCodificarUTF8;
		$objpdf->iFormato = $iFormato;
		$objpdf->iReporte = $iReporte;
		$objpdf->filaent = NULL;
		$objpdf->filaentorno = $filaentorno;
		$objpdf->AddPage();
		$objpdf->ArmarReporte251($PARAMS, $objDB);
		//$objpdf->AddPage();
		//p_PaginaIndice($objpdf);
		if ($bDebug) {
			$objpdf->iSector = 98;
			$objpdf->AddPage();
			p_PaginaDebug($objpdf);
		}
		$sError = $objpdf->sError;
	}
	return array($objpdf, $sError);
}
$bEntra = true;
//Empezar revisando que haya una sesion.
if ($_SESSION['unad_id_tercero'] == 0) {
	$bEntra = false;
} else {
	$idTercero = numeros_validar($_SESSION['unad_id_tercero']);
	if ($idTercero != $_SESSION['unad_id_tercero']) {
		$bEntra = false;
	}
}
//Validar las variables.
if ($bEntra) {
	/*
	if (isset($_REQUEST['variable']) == 0) {
		$_REQUEST['variable'] = 0;
	}
	*/
}
if ($bEntra) {
	if (isset($_REQUEST['v3']) != 0) {
		$iVr = numeros_validar($_REQUEST['v3']);
		if ($iVr != $_REQUEST['v3']) {
			$bEntra = false;
		}
	} else {
		$bEntra = false;
	}
}
if ($bEntra) {
	if (isset($_REQUEST['v4']) != 0) {
		$iVr = numeros_validar($_REQUEST['v4']);
		if ($iVr != $_REQUEST['v4']) {
			$bEntra = false;
		}
	} else {
		$bEntra = false;
	}
}
if ($bEntra) {
	$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto != '') {
		$objDB->dbPuerto = $APP->dbpuerto;
	}
	//Validar permisos.
	list($bEntra, $sDebugP) = seg_revisa_permisoV3(251, 5, $idTercero, $objDB);
}
if ($bEntra) {
	$sError = '';
	$iReporte = 0;
	$iFormato94 = 0;
	$bDebug = false;
	if (isset($_REQUEST['rdebug']) != 0) {
		if ($_REQUEST['rdebug'] == 1) {
			$bDebug = true;
		}
	}
	if (isset($_REQUEST['iformato94']) != 0) {
		$iFormato94 = $_REQUEST['iformato94'];
	}
	$_SESSION['u_ultimominuto'] = iminutoavance();
	$iFormato = 0;
	if (isset($_REQUEST['f']) != 0) {
		if ($_REQUEST['f'] == 1) {
			$iFormato = 1;
		}
	}
	$bCodificarUTF8 = false;
	if ($APP->utf8 == 1) {
		$bCodificarUTF8 = true;
	}
	$bEntra = false;
	$sTituloRpt = 'Bitacora de desarrollo';
	$bReporteControlado = false;
	$sNumCopiaReporte = '';
	// Definir si un reporte es controlado
	//$idRef = $PARAMS['id251'];
	list($pdf, $sError) = pdfReporteV2($iReporte, $_REQUEST, $iFormato, $sNumCopiaReporte, $bCodificarUTF8, $objDB, $bDebug);
	if ($sError == '') {
		$sError = $pdf->sError;
	}
	if ($sError == '') {
		$sNombreArchivo = cadena_Reemplazar($sTituloRpt . '_' . $pdf->sRefRpt, ' ', '_');
		$pdf->Output($sNombreArchivo . '.pdf', 'D');
	} else {
		echo $sError;
	}
} else {
	header('Location:./');
	die();
}
?>