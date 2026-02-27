<?php
/*
--- © Omar Augusto Bautista - UNAD - 2026 ---
--- omar.bautista@unad.edu.co - http://www.unad.edu.co
--- Modelo Version 3.1.5 viernes, 27 de febrero de 2026
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
	var $sTituloReporte = 'Tipo de convocatoria';
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
	var $encryption_key;
	var $padding;
	
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
			$this->Ln();
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
		$this->Cell($this->iAnchoLibre + 10, 3, cadena_decodificar('Sistema Integrado de Información 4.0'), 0, 0, 'R');
		$this->Ln();
		$this->Cell($this->iAnchoLibre + 10, 3, 'Plataforma AUREA', 0, 0, 'R');
		$this->Ln();
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
		$this->Ln();
		//p_FuenteNormalV2($this);
		if ($this->sDetalleTitulo != '') {
			$this->Cell($this->iAnchoLibre, 5, $this->sDetalleTitulo, 0, 0, 'C');
			$this->Ln();
		}
		if ($this->sDetalleHoja != '') {
			//$this->Cell(120, 5, 'Detalle: ', 0, 0, 'R');
			//p_FuenteNormalV2($this, 'B');
			$this->Cell($this->iAnchoLibre, 5, $this->sDetalleHoja, 0, 0, 'C');
			$this->Ln();
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
			$this->Cell(0, 5, cadena_decodificar($sEtiqueta) . $this->PageNo() . ' de {nb}', 0, 0, 'R');
		}
		$sEtiqueta = '';
		if ($this->sNumCopia != '') {
			$sEtiqueta = 'Copia ' . $this->sNumCopia . ' - ';
		}
		if ($this->bConFechaImprime) {
			$sEtFImp = 'Fecha de impresión ';
			$sEtiqueta = $sEtiqueta . cadena_decodificar($sEtFImp) . formato_fechalarga(fecha_hoy(), true) . ' ' . html_TablaHoraMin(fecha_hora(), fecha_minuto());
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
	function ArmarReporte2934($PARAMS, $objDB)
	{
		$this->SetTextColor(0, 0, 0);
		$this->SetFillColor(0, 0, 0);
		$this->SetDrawColor(0, 0, 0);
		p_FuenteNormalV2($this);
		//$iPuntoX = $this->GetX();
		//$sTitulo = 'Titulo 1';
		//$sNumera=p_AddIndice($sTitulo, $this->PageNo(), 1, $this);
		$sSQL = 'SELECT * FROM visa34convtipo WHERE visa34id=' . $PARAMS['id2934'] . '';
		if ($this->bDebug) {
			p_AddDebug('Consulta para el reporte ' . $sSQL, $this);
		}
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			$this->Cell($this->iAnchoLibre, 5, 'Consec: ' . $fila['visa34consec']);
			$this->Ln();
			$this->Cell($this->iAnchoLibre, 5, 'Nombre: ' . $fila['visa34nombre']);
			$this->Ln();
			$svisa34rolestudiante = $fila['visa34rolestudiante'];
			$sSQL = 'SELECT  FROM  WHERE =' . $fila['visa34rolestudiante'];
			$tablat = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablat) > 0) {
				$filat = $objDB->sf($tablat);
				$svisa34rolestudiante = $filat[''];
				if ($this->bCodificar) {
					$svisa34rolestudiante = cadena_codificar($filat['']);
				}
			}
			$this->Cell($this->iAnchoLibre, 5, 'Rolestudiante: ' . cadena_codificar($svisa34rolestudiante));
			$this->Ln();
			$svisa34roladministrativo = $fila['visa34roladministrativo'];
			$sSQL = 'SELECT  FROM  WHERE =' . $fila['visa34roladministrativo'];
			$tablat = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablat) > 0) {
				$filat = $objDB->sf($tablat);
				$svisa34roladministrativo = $filat[''];
				if ($this->bCodificar) {
					$svisa34roladministrativo = cadena_codificar($filat['']);
				}
			}
			$this->Cell($this->iAnchoLibre, 5, 'Roladministrativo: ' . cadena_codificar($svisa34roladministrativo));
			$this->Ln();
			$svisa34rolacademico = $fila['visa34rolacademico'];
			$sSQL = 'SELECT  FROM  WHERE =' . $fila['visa34rolacademico'];
			$tablat = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablat) > 0) {
				$filat = $objDB->sf($tablat);
				$svisa34rolacademico = $filat[''];
				if ($this->bCodificar) {
					$svisa34rolacademico = cadena_codificar($filat['']);
				}
			}
			$this->Cell($this->iAnchoLibre, 5, 'Rolacademico: ' . cadena_codificar($svisa34rolacademico));
			$this->Ln();
			$svisa34rolaspirante = $fila['visa34rolaspirante'];
			$sSQL = 'SELECT  FROM  WHERE =' . $fila['visa34rolaspirante'];
			$tablat = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablat) > 0) {
				$filat = $objDB->sf($tablat);
				$svisa34rolaspirante = $filat[''];
				if ($this->bCodificar) {
					$svisa34rolaspirante = cadena_codificar($filat['']);
				}
			}
			$this->Cell($this->iAnchoLibre, 5, 'Rolaspirante: ' . cadena_codificar($svisa34rolaspirante));
			$this->Ln();
			$svisa34rolegresado = $fila['visa34rolegresado'];
			$sSQL = 'SELECT  FROM  WHERE =' . $fila['visa34rolegresado'];
			$tablat = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablat) > 0) {
				$filat = $objDB->sf($tablat);
				$svisa34rolegresado = $filat[''];
				if ($this->bCodificar) {
					$svisa34rolegresado = cadena_codificar($filat['']);
				}
			}
			$this->Cell($this->iAnchoLibre, 5, 'Rolegresado: ' . cadena_codificar($svisa34rolegresado));
			$this->Ln();
			$svisa34rolexterno = $fila['visa34rolexterno'];
			$sSQL = 'SELECT  FROM  WHERE =' . $fila['visa34rolexterno'];
			$tablat = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablat) > 0) {
				$filat = $objDB->sf($tablat);
				$svisa34rolexterno = $filat[''];
				if ($this->bCodificar) {
					$svisa34rolexterno = cadena_codificar($filat['']);
				}
			}
			$this->Cell($this->iAnchoLibre, 5, 'Rolexterno: ' . cadena_codificar($svisa34rolexterno));
			$this->Ln();
			$svisa34grupotipologia = $fila['visa34grupotipologia'];
			$sSQL = 'SELECT visa46nombre FROM visa46grupotipologia WHERE visa46id=' . $fila['visa34grupotipologia'];
			$tablat = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablat) > 0) {
				$filat = $objDB->sf($tablat);
				$svisa34grupotipologia = $filat['visa46nombre'];
				if ($this->bCodificar) {
					$svisa34grupotipologia = cadena_codificar($filat['visa46nombre']);
				}
			}
			$this->Cell($this->iAnchoLibre, 5, 'Grupotipologia: ' . cadena_codificar($svisa34grupotipologia));
			$this->Ln();
			$svisa34activo = $fila['visa34activo'];
			$sSQL = 'SELECT  FROM  WHERE =' . $fila['visa34activo'];
			$tablat = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablat) > 0) {
				$filat = $objDB->sf($tablat);
				$svisa34activo = $filat[''];
				if ($this->bCodificar) {
					$svisa34activo = cadena_codificar($filat['']);
				}
			}
			$this->Cell($this->iAnchoLibre, 5, 'Activo: ' . cadena_codificar($svisa34activo));
			$this->Ln();
			$svisa34aplicazona = $fila['visa34aplicazona'];
			$sSQL = 'SELECT  FROM  WHERE =' . $fila['visa34aplicazona'];
			$tablat = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablat) > 0) {
				$filat = $objDB->sf($tablat);
				$svisa34aplicazona = $filat[''];
				if ($this->bCodificar) {
					$svisa34aplicazona = cadena_codificar($filat['']);
				}
			}
			$this->Cell($this->iAnchoLibre, 5, 'Aplicazona: ' . cadena_codificar($svisa34aplicazona));
			$this->Ln();
			$svisa34aplicacentro = $fila['visa34aplicacentro'];
			$sSQL = 'SELECT  FROM  WHERE =' . $fila['visa34aplicacentro'];
			$tablat = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablat) > 0) {
				$filat = $objDB->sf($tablat);
				$svisa34aplicacentro = $filat[''];
				if ($this->bCodificar) {
					$svisa34aplicacentro = cadena_codificar($filat['']);
				}
			}
			$this->Cell($this->iAnchoLibre, 5, 'Aplicacentro: ' . cadena_codificar($svisa34aplicacentro));
			$this->Ln();
			$svisa34aplicaescuela = $fila['visa34aplicaescuela'];
			$sSQL = 'SELECT  FROM  WHERE =' . $fila['visa34aplicaescuela'];
			$tablat = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablat) > 0) {
				$filat = $objDB->sf($tablat);
				$svisa34aplicaescuela = $filat[''];
				if ($this->bCodificar) {
					$svisa34aplicaescuela = cadena_codificar($filat['']);
				}
			}
			$this->Cell($this->iAnchoLibre, 5, 'Aplicaescuela: ' . cadena_codificar($svisa34aplicaescuela));
			$this->Ln();
			$svisa34aplicaprograma = $fila['visa34aplicaprograma'];
			$sSQL = 'SELECT  FROM  WHERE =' . $fila['visa34aplicaprograma'];
			$tablat = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablat) > 0) {
				$filat = $objDB->sf($tablat);
				$svisa34aplicaprograma = $filat[''];
				if ($this->bCodificar) {
					$svisa34aplicaprograma = cadena_codificar($filat['']);
				}
			}
			$this->Cell($this->iAnchoLibre, 5, 'Aplicaprograma: ' . cadena_codificar($svisa34aplicaprograma));
			$this->Ln();
			$this->Cell($this->iAnchoLibre, 5, 'Nombre: ' . $fila['bnombre']);
			$this->Ln();
			$sTitulo = 'Configura Anexos';
			//$sNumera=p_AddIndice($sTitulo, $this->PageNo(), 1, $this);
			p_TituloBloque($sTitulo, $this);
			p_FuenteNormalV2($this);
			$sSQL = 'SELECT * FROM visa42convanexo WHERE visa42idtipo=' . $fila['visa34id'] . '';
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
				$c5 = 20;
				$c6 = 20;
				$c7 = 20;
				$c8 = 20;
				$c9 = 20;
				$this->Cell($c2, 5, 'Consec');
				$this->Cell($c4, 5, 'Titulo');
				$this->Cell($c5, 5, 'Descripcion');
				$this->Cell($c6, 5, 'Activo');
				$this->Cell($c7, 5, 'Orden');
				$this->Cell($c8, 5, 'Obligatorio');
				$this->Cell($c9, 5, 'Tipodocumento');
				$this->Ln();
			}
			while ($fila1 = $objDB->sf($tabla1)) {
				$this->Cell($c1, 5, $fila1['visa42consec']);
				$this->Cell($c2, 5, $fila1['visa42titulo']);
				$this->Cell($c3, 5, $fila1['visa42descripcion']);
				$svisa42activo = $fila1['visa42activo'];
				$sSQL = 'SELECT  FROM  WHERE =' . $fila1['visa42activo'];
				$tablat = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($tablat) > 0) {
					$filat = $objDB->sf($tablat);
					$svisa42activo = $filat[''];
					if ($this->bCodificar) {
						$svisa42activo = cadena_codificar($filat['']);
					}
				}
				$this->Cell($c4, 5, cadena_codificar($svisa42activo));
				$this->Cell($c5, 5, $fila1['visa42orden']);
				$svisa42obligatorio = $fila1['visa42obligatorio'];
				$sSQL = 'SELECT  FROM  WHERE =' . $fila1['visa42obligatorio'];
				$tablat = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($tablat) > 0) {
					$filat = $objDB->sf($tablat);
					$svisa42obligatorio = $filat[''];
					if ($this->bCodificar) {
						$svisa42obligatorio = cadena_codificar($filat['']);
					}
				}
				$this->Cell($c6, 5, cadena_codificar($svisa42obligatorio));
				$svisa42tipodocumento = $fila1['visa42tipodocumento'];
				$sSQL = 'SELECT gedo02nombre FROM gedo02tipodoc WHERE gedo02id=' . $fila1['visa42tipodocumento'];
				$tablat = $objDB->ejecutasql($sSQL);
				if ($objDB->nf($tablat) > 0) {
					$filat = $objDB->sf($tablat);
					$svisa42tipodocumento = $filat['gedo02nombre'];
					if ($this->bCodificar) {
						$svisa42tipodocumento = cadena_codificar($filat['gedo02nombre']);
					}
				}
				$this->Cell($c7, 5, cadena_codificar($svisa42tipodocumento));
				$this->Ln();
			}
			p_Separador($this);
		}
		// Fin de ArmarReporte2934
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
		$objpdf->sTituloReporte = 'Tipo de convocatoria';
		$sDetalle = '';
		/*
		if ($PARAMS['v7'] != '') {
			$sDetalle = 'Cuenta inicial: ' . $PARAMS['v7'];
		}
		*/
		$objpdf->sDetalleTitulo = $sDetalle;
		//$objpdf->sRefRpt = $PARAMS['id2934'];
		$objpdf->AliasNbPages();
		$objpdf->bCodificar = $bCodificarUTF8;
		$objpdf->iFormato = $iFormato;
		$objpdf->iReporte = $iReporte;
		$objpdf->filaent = NULL;
		$objpdf->filaentorno = $filaentorno;
		$objpdf->AddPage();
		$objpdf->ArmarReporte2934($PARAMS, $objDB);
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
$bDebug = false;
$sError = '';
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
if (!$bEntra) {
	$sError = 'No se han definido los parametros del reporte.';
}
if ($sError == '') {
	$iCodModulo = 2934;
	$objDB = new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto != '') {
		$objDB->dbPuerto = $APP->dbpuerto;
	}
	//Validar permisos.
	list($bEntra, $sDebugP) = seg_revisa_permisoV3($iCodModulo, 5, $idTercero, $objDB);
	if (!$bEntra) {
		$sError = 'No cuenta con permisos para este reporte.';
	}
}
if ($sError == '') {
	$iFormato94 = 0;
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
	$sTituloRpt = 'Tipo de convocatoria';
	$bReporteControlado = false;
	$sNumCopiaReporte = '';
}
/*
if ($sError == '') {
	// Definir si un reporte es controlado
	$idRef = $_REQUEST['id2934'];
	require $APP->rutacomun . 'lib293.php';
	$bCopiaBloqueada = true;
	$bRequierePermiso = true;
	// Definir cuando una copia NO esta bloqueada.
	if (f293_NumCopias($iCodModulo, $idRef, $objDB) == 0) {
		$bCopiaBloqueada = false;
		$bRequierePermiso = false;
	} else {
		$iPermisoCopia = 9;
	}
	if ($bRequierePermiso) {
		list($bDevuelve, $sDebugP) = seg_revisa_permisoV3($iCodModulo, $iPermisoCopia, $idTercero, $objDB);
		if ($bDevuelve) {
			$bCopiaBloqueada = false;
		}
	}
	if ($bCopiaBloqueada) {
		$sError = 'No tiene permiso para imprimir copia de este reporte [Modulo ' . $iModPermiso . ' Permiso ' . $iPermisoCopia . ']';
	}
}
if ($sError == '') {
	// Hacer el registro de la copia.
	list($sNumCopiaReporte, $sError) = f293_RegistrarCopia($iCodModulo, $idRef, $objDB);
}
*/
if ($sError == '') {
	list($pdf, $sError) = pdfReporteV2($iCodModulo, $_REQUEST, $iFormato, $sNumCopiaReporte, $bCodificarUTF8, $objDB, $bDebug);
	if ($sError == '') {
		$sError = $pdf->sError;
	}
}
if ($sError == '') {
	$sNombreArchivo = cadena_Reemplazar($sTituloRpt . '_' . $pdf->sRefRpt, ' ', '_');
	$pdf->Output($sNombreArchivo . '.pdf', 'D');
} else {
	echo $sError;
}

