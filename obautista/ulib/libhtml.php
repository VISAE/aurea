<?php
/*
--- © Angel Mauro Avellaneda Barreto - Ideas - 2016 ---
--- mauro@avellaneda.co - http://www.ideasw.com
--- Modelo Versión 2.13.3 miércoles, 13 de julio de 2016
*/
class clsHtmlCombos
{
	const VERSION_MAYOR = 1;
	const VERSION_MENOR = 1;
	const VERSION_CORRECCION = 0;
	var $bConVacio = true;
	var $bConDebug = false;
	var $aItem = array();
	var $iItems = 0;
	// Los origenes es de donde tomamos los datos 0= manual, 1=sql  la consulta debe ser enviada.
	var $iOrigen = 0;
	var $iAncho = 0;
	var $sAccion = '';
	var $sClaseCombo = '';
	var $sEtiVacio = '';
	var $sNombre = '';
	var $sVrVacio = '';
	var $sValorCombo = '';
	var $sEstilos = '';
	function addArreglo($aDatos, $iCantidad, $sEstilo = '')
	{
		for ($k = 1; $k <= $iCantidad; $k++) {
			$bAdiciona = true;
			if (isset($aDatos[$k]) == 0) {
				$aDatos[$k] = '';
			}
			if ($aDatos[$k] == '') {
				$bAdiciona = false;
			}
			if ($bAdiciona) {
				$this->iItems++;
				$i = $this->iItems;
				$this->aItem[$i]['v'] = $k;
				$this->aItem[$i]['e'] = cadena_notildes($aDatos[$k]);
				$this->aItem[$i]['c'] = $sEstilo;
			}
		}
	}
	function addItem($sValor, $sEtiqueta, $sEstilo = '')
	{
		$this->iItems++;
		$i = $this->iItems;
		$this->aItem[$i]['v'] = $sValor;
		$this->aItem[$i]['e'] = $sEtiqueta;
		$this->aItem[$i]['c'] = $sEstilo;
	}
	function comboSistema($idModulo, $iConsec, $objDB, $sAccion = '')
	{
		$this->sAccion = $sAccion;
		$sSQL = 'SELECT unad22codopcion, unad22nombre FROM unad22combos WHERE unad22idmodulo=' . $idModulo . ' AND unad22consec=' . $iConsec . ' AND unad22activa="S" ORDER BY unad22orden, unad22nombre';
		$tablac = $objDB->ejecutasql($sSQL);
		while ($fila = $objDB->sf($tablac)) {
			$this->addItem($fila['unad22codopcion'], $fila['unad22nombre']);
		}
		return $this->html('');
	}
	function comun($idTabla, $objDB)
	{
		$sSQL = '';
		switch ($idTabla) {
			case 145:
				$sSQL = 'SELECT unad45codigo AS id, CONCAT(unad45titulo, " - ", unad45nombre) AS nombre 
				FROM unad45tipodoc WHERE unad45id>=0 
				ORDER BY unad45activo DESC, unad45orden, unad45titulo';
				break;
			case 1101:
				$sSQL = 'SELECT unad24id AS id, CONCAT(CASE unad24activa WHEN "S" THEN "" ELSE "[INACTIVA] " END, unad24nombre) AS nombre 
				FROM unad24sede 
				WHERE unad24id>0
				ORDER BY unad24activa DESC, unad24nombre';
				break;
			case 1102:
				$sSQL = 'SELECT unad10codigo AS id, unad10codigo AS nombre 
				FROM unad10vigencia 
				WHERE unad10financiera=1
				ORDER BY unad10codigo DESC';
				break;
		}
		if ($sSQL != '') {
			$tablac = $objDB->ejecutasql($sSQL);
			while ($fila = $objDB->sf($tablac)) {
				$this->addItem($fila['id'], $fila['nombre']);
			}
		}
	}
	function debug($bDebug = true)
	{
		$this->bConDebug = $bDebug;
	}
	function html($sConsulta = '', $objDB = NULL, $iComun = 0)
	{
		if ($iComun != 0) {
			$this->comun($iComun, $objDB);
			$sConsulta = '';
		}
		$sDebug = '';
		$sRes = '';
		$sAccion = '';
		$sEstilos = '';
		$sClaseC = '';
		if ($this->sAccion != '') {
			$sAccion = ' onChange="' . $this->sAccion . '"';
		}
		if ($this->sClaseCombo != '') {
			$sClaseC = ' class="' . $this->sClaseCombo . '"';
		}
		$sAncho = '';
		if ($this->iAncho != 0) {
			$sAncho = 'width:' . $this->iAncho . 'px;';
			if ($this->sEstilos == '') {
				$sEstilos = ' style="' . $sAncho . '"';
			}
		}
		if ($this->sEstilos != '') {
			$sEstilos = ' style="' . $sAncho . $this->sEstilos . '"';
		}
		$sRes = '<select id="' . $this->sNombre . '" name="' . $this->sNombre . '"' . $sAccion . $sClaseC . $sEstilos . '>';
		if ($this->bConVacio) {
			$sEstilo = '';
			if ($this->sVrVacio === '') {
				$sEstilo = ' style="color:#FF0000"';
			}
			$sRes = $sRes . '<option value="' . $this->sVrVacio . '"' . $sEstilo . '>' . $this->sEtiVacio . '</option>';
		}
		for ($k = 1; $k <= $this->iItems; $k++) {
			$sSel = '';
			$sEstilo = '';
			if ($this->aItem[$k]['v'] == $this->sValorCombo) {
				$sSel = ' selected';
			}
			if ($this->aItem[$k]['c'] != '') {
				$sEstilo = ' style="' . $this->aItem[$k]['c'] . '"';
			}
			$sRes = $sRes . '<option value="' . $this->aItem[$k]['v'] . '"' . $sSel . $sEstilo . '>' . cadena_notildes($this->aItem[$k]['e']) . '</option>';
		}
		if ($sConsulta != '') {
			$sEstilo = '';
			$tablac = $objDB->ejecutasql($sConsulta);
			if ($this->bConDebug) {
				if ($tablac == false) {
					$sDebug = $sConsulta;
				}
			}
			while ($fila = $objDB->sf($tablac)) {
				$sSel = '';
				if ($fila['id'] == $this->sValorCombo) {
					$sSel = ' selected';
				}
				$sNombreOpcion = cadena_notildes($fila['nombre']);
				//$sNombreOpcion = cadena_LimpiarTildes($sNombreOpcion, ' ');
				//$sRes = $sRes . '<option value="' . $fila['id'] . '"' . $sSel . $sEstilo . '>' . cadena_notildes($fila['nombre']) . '</option>';
				$sRes = $sRes . '<option value="' . $fila['id'] . '"' . $sSel . $sEstilo . '>' . $sNombreOpcion . '</option>';
			}
		}
		$sRes = $sRes . '</select>' . $sDebug;
		return cadena_codificar($sRes);
	}
	function meses($sEstilo = '')
	{
		$sMeses = array('', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
		for ($k = 1; $k <= 12; $k++) {
			$this->addItem($k, $sMeses[$k], $sEstilo);
		}
	}
	function nuevo($sNombre, $sValorCombo = '', $bConVacio = true, $sEtiVacio = '{Seleccione Uno}', $sVrVacio = '')
	{
		$this->bConVacio = $bConVacio;
		$this->aItem = array();
		$this->iAncho = 0;
		$this->iItems = 0;
		$this->sAccion = '';
		$this->sClaseCombo = '';
		$this->sEtiVacio = $sEtiVacio;
		$this->sNombre = $sNombre;
		$this->sVrVacio = $sVrVacio;
		$this->sValorCombo = $sValorCombo;
	}
	function numeros($iNumIni, $iNumFin, $iOrden = 0, $sEstilo = '')
	{
		if ($iOrden == 0) {
			for ($k = $iNumIni; $k <= $iNumFin; $k++) {
				$this->addItem($k, $k, $sEstilo);
			}
		} else {
			for ($k = $iNumFin; $k >= $iNumIni; $k--) {
				$this->addItem($k, $k, $sEstilo);
			}
		}
	}
	function sino($sEtiquetaSi = 'Si', $sEtiquetaNo = 'No', $sValorSi = 'S', $sValorNo = 'N', $sEstiloSi = '', $sEstiloNo = '')
	{
		$this->addItem($sValorSi, $sEtiquetaSi, $sEstiloSi);
		$this->addItem($sValorNo, $sEtiquetaNo, $sEstiloNo);
	}
	function __construct($sNombre = '', $sValorCombo = '', $bConVacio = true, $sEtiVacio = '{Seleccione Uno}', $sVrVacio = '')
	{
		$this->nuevo($sNombre, $sValorCombo, $bConVacio, $sEtiVacio);
	}
}
class clsHtmlCuerpoItem
{
	var $iTipo = 0;
	/*
	-- Tipos Aceptados: 0 HTML puro. 1 - Etiqueta. 100 - Salto de linea; 
	-- 1000 Un bloque HTML (Es decir un grupo campos.)
	*/
	var $bNegrilla = false;
	var $bAlerta = false;
	var $sCuerpo = '';
	var $sEstilo = '';
	var $objBloque = NULL;
	function __construct($iTipo, $sCuerpo = '')
	{
		$this->iTipo = $iTipo;
		$this->sCuerpo = $sCuerpo;
	}
}
class clsHtmlCuerpo
{
	var $iEstilo = 1;
	var $aItems = array();
	var $iItems = 0;
	function addBloque($objBloque, $sEstilo = '')
	{
		$objItem = new clsHtmlCuerpoItem(1000);
		$objItem->objBloque = $objBloque;
		$objItem->sEstilo = $sEstilo;
		$this->iItems++;
		$this->aItems[$this->iItems] = $objItem;
		return $this->iItems;
	}
	function addEtiqueta($sContenido, $sEstilo = '', $bNegrilla = false)
	{
		$objItem = new clsHtmlCuerpoItem(1, $sContenido);
		$objItem->sEstilo = $sEstilo;
		$objItem->bNegrilla = $bNegrilla;
		$this->iItems++;
		$this->aItems[$this->iItems] = $objItem;
		return $this->iItems;
	}
	function addHTML($sCuerpo)
	{
		$objItem = new clsHtmlCuerpoItem(0);
		$objItem->sCuerpo = $sCuerpo;
		$this->iItems++;
		$this->aItems[$this->iItems] = $objItem;
		return $this->iItems;
	}
	function addSalto()
	{
		$objItem = new clsHtmlCuerpoItem(100);
		$this->iItems++;
		$this->aItems[$this->iItems] = $objItem;
		return $this->iItems;
	}
	function armarBoton($sNombre, $sAccion = '', $sTitulo = '', $sClase = '', $sDescripcion = '')
	{
		$sRes = '';
		switch ($this->iEstilo) {
			case 2:
				break;
			default:
				$hTitulo = '';
				if ($sTitulo != '') {
					$hTitulo = ' value="' . $sTitulo . '"';
				}
				$hClase = '';
				switch ($sClase) {
					case 'proceso':
						$hClase = ' class="BotonAzul"';
						break;
				}
				$hAccion = '';
				if ($sAccion != '') {
					$hAccion = ' onclick="' . $sAccion . '"';
				}
				$hDesc = '';
				if ($sDescripcion != '') {
					$hDesc = ' title="' . $sDescripcion . '"';
				}
				$sRes = '<input id="' . $sNombre . '" name="' . $sNombre . '" type="button"' . $hTitulo . $hClase . $hAccion . $hDesc . '/>';
				break;
		}
		return $sRes;
	}
	function html()
	{
		$sRes = '';
		for ($k = 1; $k <= $this->iItems; $k++) {
			$objItem = $this->aItems[$k];
			switch ($objItem->iTipo) {
				case 0: //HTML puro.
					$sRes = $sRes . $objItem->sCuerpo;
					break;
				case 1: //Etiqueta.
					switch ($this->iEstilo) {
						case 2:
							break;
						default:
							$sComp = '';
							switch ($objItem->sEstilo) {
								case '30':
									$sComp = ' class="Label30"';
									break;
								case '60':
									$sComp = ' class="Label60"';
									break;
								case '90':
									$sComp = ' class="Label90"';
									break;
								case '130':
									$sComp = ' class="Label130"';
									break;
								case '160':
									$sComp = ' class="Label160"';
									break;
								case '200':
									$sComp = ' class="Label200"';
									break;
								case 'AreaS':
									$sComp = ' class="txtAreaS"';
									break;
								case 'L':
									$sComp = ' class="L"';
									break;
							}
							$sPrev = '';
							$sPost = '';
							if ($objItem->bNegrilla) {
								$sPrev = '<b>';
								$sPost = '</b>';
							}
							if ($objItem->bAlerta) {
								$sPrev = '<span class="rojo">' . $sPrev;
								$sPost = $sPost . '</span>';
							}
							$sRes = $sRes . '<label' . $sComp . '>' . $sPrev . $objItem->sCuerpo . $sPost . '</label>';
							break;
					}
					break;
				case 100: //Salto de linea.
					switch ($this->iEstilo) {
						case 2:
							break;
						default:
							$sRes = $sRes . '<div class="salto1px"></div>';
							break;
					}
					break;
				case 1000: //Grupo campos.
					$sPrev = '<div class="GrupoCampos">' . $this->iEstilo;
					$sPost = '<div class="salto1px"></div></div>';
					switch ($objItem->sEstilo) {
						case 450:
							$sPrev = '<div class="GrupoCampos450">';
							break;
						default:
							break;
					}
					$sRes = $sRes . $sPrev . $objItem->objBloque->html() . $sPost;
					break;
			}
			//Termina de recorrer cada item.
		}
		return $sRes;
	}
	function __construct($iEstilo = 1)
	{
		$this->iEstilo = $iEstilo;
	}
}
class clsHtmlFecha
{
	var $sNombre = '';
	var $sValor = '';
	function __construct($sNombre, $sValor)
	{
		$this->sNombre = $sNombre;
		$this->sValor = $sValor;
	}
}
class clsHtmlForma
{
	var $iPiel = 0;
	var $sAddEstiloTitulo = '';
	var $sAddEstiloBoton = '';
	var $aBotones = array();
	var $iBotones = 0;
	function addBoton($sNombre, $sClase, $sAccion, $sTitulo)
	{
		$this->iBotones++;
		$k = $this->iBotones;
		$this->aBotones[$k]['nombre'] = $sNombre;
		$this->aBotones[$k]['clase'] = $sClase;
		$this->aBotones[$k]['accion'] = $sAccion;
		$this->aBotones[$k]['titulo'] = $sTitulo;
	}
	/**
	 * Html de un boton.
	 *
	 * Esta función permite crear un boton con o sin icono dependiendo
	 * de la piel seleccionada.
	 *
	 * @param string $sNombre Nombre del boton
	 * @param string $sClase Clase del boton - Define que icono usar
	 * @param string $sAccion Acción que debe ejecutar el boton
	 * @param string $sTitulo Titulo del boton
	 * @param int $iLabel (Opcional) Valor para encapsular el boton en un label (Por defecto: 0)
	 * @param string $sAdicional (Opcional) Estilos adicionales al boton (Por defecto: '')
	 * @param string $sTexto (Opcional) Texto del boton (Por defecto: '')
	 * @param boolean $bIcono (Opcional) Mostrar icono (Por defecto: true)
	 * @return string HTML generado para el botón
	 */
	function htmlBotonSolo($sNombre, $sClase, $sAccion, $sTitulo, $iLabel = 0, $sAdicional = '', $sTexto = '', $bIcono = true)
	{
		$res = '';
		$sImgNombre = '';
		$sAddB = '';
		$sImg = '';
		$sTituloBtn = '';
		if ($sAdicional != '') {
			$sAddB = ' ' . $sAdicional;
		}
		switch ($this->iPiel) {
			case 2:
				if ($this->sAddEstiloBoton != '') {
					$sAddB = '' . $this->sAddEstiloBoton . '';
				}
				$bBotonMini = false;
				$sClaseFin = 'btn-tertiary';
				$sClaseMini = ' btMini';
				$aClase = explode(' ', $sClase);
				if (count($aClase) > 1) {
					$sClase = $aClase[0]; // Clase real
					$sColor = $aClase[1]; // Color
					switch ($sColor) {
						case 'az':
						case 'blue':
						case 'azul':
							$sClaseFin = 'btn-primary';
							break;
						case 'ama':
						case 'ye':
						case 'yellow':
						case 'amarillo':
							$sClaseFin = 'btn-tertiary';
							break;
						case 're':
						case 'red':
						case 'rojo':
							$sClaseFin = 'btn-red';
							break;
						case 'verde':
						case 've':
						case 'green':
							$sClaseFin = 'btn-success';
							break;
						case 'blanco':
						case 'bl':
						case 'white':
							$sClaseFin = 'btn-container';
							break;
						case 'gray':
						case 'gr':
						case 'gris':
							$sClaseFin = 'btn-secondary';
							break;
					}
				}
				if ($sAdicional != '') {
					$sAdicional = str_replace('style', '', $sAdicional);
					$sAdicional = str_replace('"', '', $sAdicional);
					$sAdicional = str_replace('=', '', $sAdicional);
					$sAdicional = str_replace('block', 'inline-block', $sAdicional);
					$sAddB = $sAdicional;
				}
				if ($sTexto == '') {
					$sTexto = $sTitulo;
				}
				switch ($sClase) {
					case '':
					case 'botonL':
					case 'botonM':
					case 'botonS':
						break;
					case 'botonActualizar':
						$sImg = '<i class="iSync"></i>';
						break;
					case 'botonAnexar':
						$bBotonMini = true;
						$sImg = '<i class="iAttach"></i>';
						break;
					case 'btSoloAnexar':
					case 'btAnexarS':
						$sImg = '<i class="iAttach"></i>';
						break;
					case 'btMiniAprobar':
						$bBotonMini = true;
						$sImg = '<i class="iCheck"></i>';
						break;
					case 'botonAprobado':
					case 'btUpAprobado':
					case 'btUpVisto':
						$sImg = '<i class="iCheck"></i>';
						break;
					case 'BotonAzul':
					case 'botonAzul':
						$sClaseFin = 'btn-primary';
						break;
					case 'botonBuscar':
					case 'botonSearch':
						$sImg = '<i class="iSearch"></i>';
						break;
					case 'botonMoney':
						$sImg = '<i class="iHelp"></i>';
						break;
					case 'botonPrint':
						$sImg = '<i class="iPrint"></i>';
						break;
					case 'botonProceso':
						$sImg = '<i class="iSettings"></i>';
						break;
					case 'botonVerde':
						$sClaseFin = 'btn-success';
						break;
					case 'btBorrarS':
						$sImg = '<i class="iDelete"></i>';
						break;
					case 'btActualizaS':
						$sImg = '<i class="iSync"></i>';
						break;
					case 'btMiniAnexar':
						$bBotonMini = true;
						$sImg = '<i class="iAttach"></i>';
						break;
					case 'btMiniAprobar':
						$bBotonMini = true;
						$sImg = '<i class="iCheck"></i>';
						break;
					case 'btMiniActualizar':
						$bBotonMini = true;
						$sImg = '<i class="iSync"></i>';
						break;
					case 'btMas':
						$sImg = '<i class="iAdd"></i>';
						break;
					case 'btAgregarS':
						$bBotonMini = true;
						$sImg = '<i class="iAdd"></i>';
						break;
					case 'btEnviarExcel':
						$sImg = '<i class="iExcel"></i>';
						break;
					case 'btEnviarPDF':
						$sImg = '<i class="iPdf"></i>';
						break;
					case 'btGuardarS':
						$sImg = '<i class="iSaveFill"></i>';
						break;
					case 'btMiniGuardar':
						$bBotonMini = true;
						$sImg = '<i class="icon-save-fill"></i>';
						break;
					case 'btMiniAyuda':
						$bBotonMini = true;
						$sImg = '<i class="iHelp"></i>';
						break;
					case 'btSupAyuda':
						$sImg = '<i class="iHelp"></i>';
						break;
					case 'btMiniBalancear':
						$bBotonMini = true;
						$sImg = '<i class="iHelp"></i>';
						break;
					case 'btMiniBuscar':
						$bBotonMini = true;
						$sImg = '<i class="iSearch"></i>';
						break;
					case 'btMiniHoy':
						$bBotonMini = true;
						$sImg = '<i class="iHoy"></i>';
						break;
					case 'btMiniClonar':
					case 'btMiniProceso':
						$bBotonMini = true;
						$sImg = '<i class="iSettings"></i>';
						break;
					case 'btMiniBorrar':
					case 'btMiniEliminar':
						$bBotonMini = true;
						$sImg = '<i class="icon-delete"></i>';
						break;
					case 'btUpEliminar':
						$sImg = '<i class="icon-delete"></i>';
						break;
					case 'btMiniExcel':
						$bBotonMini = true;
						$sImg = '<i class="iExcel"></i>';
						break;
					case 'btMiniExpande':
						$bBotonMini = true;
						$sImg = '<i id="i_expande' . $sNombre . '"  class="iExpandLess"></i>';
						$sNombre = 'btexpande' . $sNombre;
						break;
					case 'btMiniLimpiar':
						$bBotonMini = true;
						$sImg = '<i class="icon-copy"></i>';
						break;
					case 'btUpLimpiar':
						$sImg = '<i class="icon-copy"></i>';
						break;
					case 'btMiniMail':
						$bBotonMini = true;
						$sImg = '<i class="iEmail"></i>';
						break;
					case 'btMiniMas':
						$bBotonMini = true;
						$sImg = '<i class="iAdd"></i>';
						break;
					case 'btMiniMenos':
						$bBotonMini = true;
						$sImg = '<i class="iRemove"></i>';
						break;
					case 'btMiniNotificar':
						$bBotonMini = true;
						$sImg = '<i class="icon-send"></i>';
						break;
					case 'btMiniPersona':
						$bBotonMini = true;
						$sImg = '<i class="iPerson"></i>';
						break;
					case 'btPersonaAprobar':
						$sImg = '<i class="iPersonAdd"></i>';
						break;
					case 'btPersonaRechazar':
						$sImg = '<i class="iPersonCancel"></i>';
						break;
					case 'btPersonaLista':
						$sImg = '<i class="iPersonList"></i>';
						break;
					case 'btMiniRecoge':
						$bBotonMini = true;
						$sImg = '<i id="i_expande' . $sNombre . '"  class="iExpand"></i>';
						$sNombre = 'btexpande' . $sNombre;
						break;
					case 'btMiniTxt':
						$bBotonMini = true;
						$sImg = '<i class="iText"></i>';
						break;
					case 'btMiniZip':
						$bBotonMini = true;
						$sImg = '<i class="iZip"></i>';
						break;
					case 'btSubirS':
						$sImg = '<i class="iUpload"></i>';
						break;
					case 'btSupAnular':
						$sImg = '<i class="iNull"></i>';
						break;
					case 'btSupArchivar':
					case 'btSupResponder':
						$sImg = '<i class="iHelp"></i>';
						break;
					case 'btSupAnotaciones':
					case 'btSupAbrir':
						$sImg = '<i class="iOpen"></i>';
						break;
					case 'btSupClonar':
					case 'btSupVolver':
						$sImg = '<i class="iArrowBack"></i>';
						break;
					case 'btSupDenegado':
						$sImg = '<i class="iHelp"></i>';
						break;
					case 'btSupDocumento':
						$sImg = '<i class="iCopy"></i>';
						break;
					case 'btSupGuardar':
						$sImg = '<i class="iSaveFill"></i>';
						break;
					case 'btSupReasignar':
						$sImg = '<i class="iExcel"></i>';
						break;
					case 'btUpCerrar':
					case 'btSupCerrar':
						$sImg = '<i class="iClosed"></i>';
						break;
					case 'btUpDenegado':
						$sImg = '<i class="iHelp"></i>';
						break;
					case 'botonTerminar':
					case 'btUpPublicar':
						$sImg = '<i class="iTask"></i>';
						break;
					case 'botonCancelar':
					case 'btUpCancelar':
						$sImg = '<i class="iCancel"></i>'; // Hojita con una X
						break;
					case 'botonContinuar':
					case 'btUpContinuar':
						$sImg = '<i class="iArrowRight"></i>';
						break;
					case 'btUpGuardar':
						$sImg = '<i class="iSaveFill"></i>';
						break;
					case 'btUpMail':
						$sImg = '<i class="iHelp"></i>';
						break;
					case 'btSupDenegado':
						$sImg = '<i class="iHelp"></i>';
						break;
					case 'btSupDocumento':
						$sImg = '<i class="iCopy"></i>';
						break;
					case 'btSupReasignar':
						$sImg = '<i class="iExcel"></i>';
						break;
					case 'btUpDenegado':
						$sImg = '<i class="iHelp"></i>';
						break;
					case 'btUpRadicar':
						$sImg = '<i class="iUpload"></i>';
						break;
					case 'btUpVolver':
						$sImg = '<i class="iArrowBack"></i>';
						break;
					default:
						$bIcono = false;
						break;
				}
				if ($bBotonMini) {
					$sTexto = '';
					$sClaseFin = $sClaseFin . $sClaseMini;
				} else {
					$sAddB = $sAddB . 'padding-right: 1rem;';
				}
				if (!$bIcono) {
					$sImg = '';
					$sAddB = '';
				}
				$res = '<button id="' . $sNombre . '" name="' . $sNombre . '" type="button" class="' . $sClaseFin . '" onclick="' . $sAccion . '" title="' . $sTitulo . '" style="' . $sAddB . '">'  . $sImg . $sTexto . '</button>';
				break;
			case 0:
				$sClaseFin = $sClase;
				switch ($sClase) {
					case 'botonProceso':
						if ($iLabel) {
							$sAddB = ' style="width: ' . $iLabel . 'px !important"';
						}
					case 'btSoloReasignar':
						$sClaseFin = 'BotonAzul';
						break;
				}
				$res = '<input id="' . $sNombre . '" name="' . $sNombre . '" type="button" value="' . $sTitulo . '" class="' . $sClaseFin . '" onclick="' . $sAccion . '" title="' . $sTitulo . '"/>';
				break;
			default:
				$bEntra = true;
				$bLargo = false;
				$sImgLnk = '../ulib/img/btUpAyuda.jpg';
				$sClaseFin = $sClase;
				$sAdd = '';
				switch ($sClase) {
					case 'botonProceso':
						$res = '<input id="' . $sNombre . '" name="' . $sNombre . '" type="button" value="' . $sTitulo . '" class="image" data-icono="../ulib/img/pinon.png" onclick="' . $sAccion . '" title="' . $sTitulo . '"' . $sAddB . '/>';
						//$res='<a id="'.$sNombre.'" name="'.$sNombre.'" href="'.$sAccion.'" class="image" data-icono="pinon.png">'.$sTitulo.'</a>';
						$bEntra = false;
						break;
					case 'btEnviarExcel':
					case 'btMiniExcel':
						$sImgLnk = '../ulib/img/btSupExcel.jpg';
						break;
					case 'btMiniAnexar':
						$sImgLnk = '../ulib/img/btMiniAnexar.jpg';
						break;
					case 'btEnviarPDF':
						$sImgLnk = '../ulib/img/pdf.png';
						break;
					case 'btGuardarHoja':
						$sImgLnk = '../ulib/img/hoja-guardar.png';
						break;
					case 'btGuardarS';
					case 'btMiniGuardar':
						$sImgLnk = '../ulib/img/guardar18.png';
						break;
					case 'btMiniActualizar':
						$sImgLnk = '../ulib/img/btMiniActualizar.jpg';
						break;
					case 'btMiniBuscar':
						$sImgLnk = '../ulib/img/lupa18.png';
						break;
					case 'btMiniEliminar':
						$sImgLnk = '../ulib/img/x18.png';
						break;
					case 'btMiniHoy':
						$sImgLnk = '../ulib/img/h18.png';
						break;
					case 'btMiniLimpiar':
						$sImgLnk = '../ulib/img/hoja18.png';
						break;
					case 'btMiniPersona':
						$sImgLnk = '../ulib/img/persona18.png';
						break;
					case 'btSupCerrar':
						$sImgLnk = '../ulib/img/hoja-check.png';
						break;
					case 'btSupVolver':
						$sImgLnk = '../ulib/img/btSupVolver.jpg';
						break;
					case 'btUpEliminar':
						$sImgLnk = '../ulib/img/x.png';
						break;
					case 'btUpGuardar':
					case 'btSoloGuardar':
						$sImgLnk = '../ulib/img/guardar.png';
						break;
					case 'btUpLimpiar':
						$sImgLnk = '../ulib/img/hoja.png';
						$sClaseFin = 'btUpLimpiar';
						break;
					case 'btSupAyuda':
						$sImgLnk = '../ulib/img/btUpAyuda.jpg';
						$sClaseFin = 'btUpAyuda';
						break;
					case 'btMiniAyuda':
						$sImgLnk = '../ulib/img/btUpAyuda.png';
						break;
					default:
						$sClaseFin = 'BotonAzul';
						break;
				}
				if ($bLargo) {
					$res = '<input id="' . $sNombre . '" name="' . $sNombre . '" type="button" value="' . $sTitulo . '" class="image" onclick="' . $sAccion . '" title="' . $sTitulo . '" data-icono="' . $sImgNombre . '" style="background-image: url(\'' . $sImgLnk . '\'); ' . $sAddB . '"/>';
				} else {
					$res = '<input id="' . $sNombre . '" name="' . $sNombre . '" type="button" value="' . $sTitulo . '"  onClick="' . $sAccion . '" class="' . $sClaseFin . '" title="' . $sTitulo . '"' . $sAddB . '/>';
				}
				break;
		}
		switch ($iLabel) {
			case 30:
				$res = '<label class="Label30">' . $res . '</label>';
				break;
			case 60:
				$res = '<label class="Label60">' . $res . '</label>';
				break;
			case 90:
				$res = '<label class="Label90">' . $res . '</label>';
				break;
			case 130:
				$res = '<label class="Label130">' . $res . '</label>';
				break;
			case 160:
				$res = '<label class="Label160">' . $res . '</label>';
				break;
			case 200:
				$res = '<label class="Label200">' . $res . '</label>';
				break;
			case 220:
				$res = '<label class="Label220">' . $res . '</label>';
				break;
			case 250:
				$res = '<label class="Label250">' . $res . '</label>';
				break;
			case 300:
				$res = '<label class="Label300">' . $res . '</label>';
				break;
		}
		return $res;
	}
	function htmlInicioMarco($sTitulo = '', $sId = '')
	{
		$res = '';
		$sHtmlTitulo = '';
		switch ($this->iPiel) {
			case 2:
				if ($sTitulo) {
					$sHtmlTitulo = '<div class="areatitulo"><h3>' . $sTitulo . '</h3></div>';
				}
				$res = '<div class="areaform">' . $sHtmlTitulo;
				$res = $res . '<div class="areatrabajo">';
				if ($sId != '') {
					$res .= '<div id="' . $sId . '"></div><hr>';
				}
				if ($this->iBotones) {
					$res .= '<div class="breadcrumb__buttons">';
					for ($k = 1; $k <= $this->iBotones; $k++) {
						$sBoton = $this->htmlBotonSolo($this->aBotones[$k]['nombre'], $this->aBotones[$k]['clase'], $this->aBotones[$k]['accion'], $this->aBotones[$k]['titulo']);
						$res = $res . $sBoton;
					}
					$res .= '</div><hr>';
				}
				break;
			default:
				if ($sTitulo) {
					$sHtmlTitulo = '<div class="areatitulo"><h3>' . $sTitulo . '</h3></div>';
				}
				$res = '<div class="areaform">' . $sHtmlTitulo;
				$res = $res . '<div class="areatrabajo">';
				break;
		}
		return $res;
	}
	function htmlInicioMarcoSimple()
	{
		$res = '';
		switch ($this->iPiel) {
			case 2:
			case 0:
				$res = '<div id="cargaForm">';
				break;
			default:
				$res = '';
				break;
		}
		return $res;
	}
	function htmlFinMarco()
	{
		$res = '';
		switch ($this->iPiel) {
			default:
				$res = '</div></div>';
				break;
		}
		return $res;
	}
	function htmlFinMarcoSimple()
	{
		$res = '';
		switch ($this->iPiel) {
			case 2:
			case 0:
				$res = '</div>';
				break;
			default:
				$res = '';
				break;
		}
		return $res;
	}
	function htmlEspere($sMsgEspere = 'Este proceso puede tomar algunos momentos, por favor espere...', $bSpinner = true)
	{
		require './app.php';
		$iPiel = iDefinirPiel($APP, 2);
		$sRes = '';
		$sSpinner = '';
		$sStyle = '';
		if ($bSpinner) {
			$sSpinner = '<div class="spinner"></div>';
		}
		$sClass = 'MarquesinaMedia';
		if ($iPiel == 2) {
			$sClass = 'MarquesinaMedia flex gap-2';
		}
		$sRes = $sRes . '<div class="' . $sClass . '">';
		$sRes = $sRes . $sSpinner;
		$sRes = $sRes . '<div ' . $sStyle . '>' . $sMsgEspere . '</div>';
		$sRes = $sRes . '</div>';
		return $sRes;
	}
	function htmlExpande($sCodigo, $iValor, $sTituloMostrar = 'Mostrar', $sTituloOcultar = 'Ocultar')
	{
		$res = '<input id="boculta' . $sCodigo . '" name="boculta' . $sCodigo . '" type="hidden" value="' . $iValor . '" />';
		$sEstado1 = 'none';
		$sEstado2 = 'block';
		$sTitulo = $sTituloOcultar;
		$sClassBtn = 'btMiniExpande';
		if ($iValor != 0) {
			$sEstado1 = 'block';
			$sEstado2 = 'none';
			$sTitulo = $sTituloMostrar;
			$sClassBtn = 'btMiniRecoge';
		}
		switch ($this->iPiel) {
			case 2:
				$res = $res . $this->htmlBotonSolo($sCodigo, $sClassBtn, 'expandepanel(' . $sCodigo . ',\'' . $sEstado1 . '\',0);', $sTitulo);
				break;
			default:
				$res = $res . '<label class="Label30">';
				$res = $res . '<input id="btexpande' . $sCodigo . '" name="btexpande' . $sCodigo . '" type="button" value="Mostrar" class="btMiniExpandir" onclick="expandepanel(' . $sCodigo . ',\'block\',0);" title="' . $sTituloMostrar . '" style="display:' . $sEstado1 . ';"/>';
				$res = $res . '</label>';
				$res = $res . '<label class="Label30">';
				$res = $res . '<input id="btrecoge' . $sCodigo . '" name="btrecoge' . $sCodigo . '" type="button" value="Ocultar" class="btMiniRecoger" onclick="expandepanel(' . $sCodigo . ',\'none\',1);" title="' . $sTituloOcultar . '" style="display:' . $sEstado2 . ';"/>';
				$res = $res . '</label>';
				break;
		}
		return $res;
	}
	function htmlRetomaSesion($sUsuario, $sMsgContrasena = 'Contrase&ntilde;a', $sMsgIngreseContrasegna = 'Ingrese la contrase&ntilde;a')
	{
		$res = '<div class="salto1px"></div><a href="../">Ir a Inicio</a>';
		return $res;
	}
	function htmlTitulo($sTitulo, $iCodModulo, $sId = '')
	{
		require './app.php';
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
		if (!file_exists($mensajes_todas)) {
			$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
		}
		require $mensajes_todas;
		$res = '' . $sTitulo . '';
		$sAddE = '';
		$sAddId = '';
		if ($this->sAddEstiloTitulo != '') {
			$sAddE = ' style="' . $this->sAddEstiloTitulo . '"';
		}
		if ($sId != '') {
			$sAddId = ' id="' . $sId . '"';
		}
		switch ($this->iPiel) {
			default:
				$res = '<div class="titulos">
		<div class="titulosD">';
				for ($k = 1; $k <= $this->iBotones; $k++) {
					$sBoton = $this->htmlBotonSolo($this->aBotones[$k]['nombre'], $this->aBotones[$k]['clase'], $this->aBotones[$k]['accion'], $this->aBotones[$k]['titulo']);
					$res = $res . $sBoton;
				}
				$sComp1 = '';
				$sComp2 = '';
				if ($sAddE == '') {
					$sComp1 = '<h2>';
					$sComp2 = '</h2>';
				}
				$res = $res . '</div>
		<div class="titulosI"' . $sAddE . $sAddId . '>' . $sComp1 . $sTitulo . $sComp2 . '</div>
		</div>';
				break;
		}
		return $res;
	}
	function __construct($iPiel)
	{
		$this->iPiel = $iPiel;
	}
}
class clsHTMLGrafica
{
	var $sTitulo = '';
	var $sTipo = 'bar';
	var $sIdCanvas = '';
	function sJS($aDatos, $aEtiquetas)
	{
		if (!is_array($aDatos)) {
			return '';
		}
		if (!is_array($aEtiquetas)) {
			return '';
		}
		$iCantidad = count($aDatos);
		$sListaVal = $aDatos[0];
		if ($sListaVal == '') {
			$sListaVal = 0;
		}
		$sListaEti = "'" . $aEtiquetas[0] . "'";
		for ($k = 1; $k < $iCantidad; $k++) {
			$sVal = 0;
			if (is_numeric($aDatos[$k])) {
				$sVal = $aDatos[$k];
			}
			$sListaVal = $sListaVal . ', ' . $sVal;
			$sEtiqueta = '';
			if (isset($aEtiquetas[$k]) != 0) {
				$sEtiqueta = cadena_NoTildesJS($aEtiquetas[$k]);
			}
			$sListaEti = $sListaEti . ", '" . $sEtiqueta . "'";
		}
		$sJS = "
	function grafica_" . $this->sIdCanvas . "(){
	var ctx=document.getElementById('" . $this->sIdCanvas . "').getContext('2d');
	var myChart=new Chart(ctx, {
	type:'" . $this->sTipo . "',
	data:{
	labels:[" . $sListaEti . "],
	datasets:[{
	label:'" . $this->sTitulo . "',
	data:[" . $sListaVal . "],
	backgroundColor: [
	'rgba(255, 99, 132, 0.2)',
	'rgba(54, 162, 235, 0.2)',
	'rgba(255, 206, 86, 0.2)',
	'rgba(75, 192, 192, 0.2)',
	'rgba(153, 102, 255, 0.2)',
	'rgba(255, 159, 64, 0.2)'
	],
	borderColor:[
	'rgba(255, 99, 132, 1)',
	'rgba(54, 162, 235, 1)',
	'rgba(255, 206, 86, 1)',
	'rgba(75, 192, 192, 1)',
	'rgba(153, 102, 255, 1)',
	'rgba(255, 159, 64, 1)'
	],
	borderWidth:1
	}]
	},
	options:{scales:{yAxes:[{ticks:{beginAtZero:true}}]}}
	});
		}
	";
		return $sJS;
	}
	function __construct($sTitulo, $sIdCanvas, $sTipo = 'bar')
	{
		$this->sTitulo = $sTitulo;
		$this->sIdCanvas = $sIdCanvas;
		switch ($sTipo) {
			case 'horizontalBar':
				$this->sTipo = 'horizontalBar';
				break;
			case 'pie':
				$this->sTipo = 'pie';
				break;
			case 'line':
				$this->sTipo = 'line';
				break;
		}
	}
}
class clsHtmlMenu
{
	var $iPiel = 1;
	var $iSuperior = 0;
	var $aMenu = array();
	var $iMenu = 0;
	function addItem($sTitulo, $sDestino)
	{
		$this->iMenu++;
		$k = $this->iMenu;
		$this->aMenu[$k]['titulo'] = $sTitulo;
		$this->aMenu[$k]['destino'] = $sDestino;
	}
	function htmlMenu()
	{
		$sPrimera = '';
		$sClaseMSup = '';
		$sPrevSup = '';
		$sPostSup = '';
		if ($this->iPiel == 0) {
			$sPrimera = '<li class="ini"></li>';
			if ($this->iSuperior == 1) {
				$sClaseMSup = ' class="ppal"';
			}
			$sPrevSup = '<span>';
			$sPostSup = '</span>';
		}
		$res = '';
		for ($k = 1; $k <= $this->iMenu; $k++) {
			$etiqueta = $this->aMenu[$k]['titulo'];
			$sDestino = $this->aMenu[$k]['destino'];
			$res = $res . '<li><a href="' . $sDestino . '"' . $sClaseMSup . '>' . $sPrevSup . '' . $etiqueta . '' . $sPostSup . '</a></li>';
		}
		return $res;
	}
	function __construct($iPiel = 1, $iSuperior = 0)
	{
		$this->iPiel = $iPiel;
		$this->iSuperior = $iSuperior;
	}
}
class clsHtmlTercero
{
	var $bConCorreo = false;
	var $bConDireccion = false;
	var $bConGrupoCampos = true;
	var $bConTelefono = false;
	var $bExiste = false;
	var $bOculto = false;
	var $bSoloDatos = false;
	var $idTercero = 0;
	var $iForma = 0;
	var $iMarcador = 0;
	var $iPiel = 0;
	var $sDoc = '';
	var $sCorreo = '';
	var $sCorreoInstitucional = '';
	var $sDireccion = '';
	var $sNombreCampo = 'unad11id';
	var $sRazonSocial = '&nbsp;';
	var $sTelefono = '';
	var $sTipoDoc = 'CC';
	var $sTituloCampo = 'Tercero';
	// datos constantes que se toman de las librerias de idioma o del app.php
	var $STIPODOC = 'CC';
	var $ETI_INGDOC = '';
	var $ETI_CORREO = '';
	var $ETI_DIRECCION = '';
	var $ETI_TELEFONO = '';
	function Cargar($objDB)
	{
		$this->Traer('', '', $this->idTercero, $objDB);
	}
	function html()
	{
		$sRes = '';
		$sGC = '';
		$sGCc = '';
		if ($this->bConGrupoCampos) {
			$sGC = '<div class="GrupoCampos450">';
			$sGCc = '<div class="salto1px"></div>
		</div>';
		}
		$sRes = $this->HtmlTitulo();
		$sRes = $sRes . html_salto();
		if ($this->bSoloDatos) {
			$sRes = $sRes . $this->HtmlDatos();
		} else {
			$sRes = $sRes . $this->HtmlCuerpo();
		}
		return $sGC . $sRes . $sGCc;
	}
	function Limpiar()
	{
		$this->sDoc = '';
		$this->sCorreo = '';
		$this->sCorreoInstitucional = '';
		$this->sDireccion = '';
		$this->sRazonSocial = '&nbsp;';
		$this->sTelefono = '';
		$this->sTipoDoc = $this->STIPODOC;
		$this->bExiste = false;
	}
	function HtmlCuerpo()
	{
		$sPref = '<b>';
		$sSuf = '</b>';
		if (!$this->bExiste) {
			$sPref = '';
			$sSuf = '';
		}
		$sRes = '<input id="' . $this->sNombreCampo . '" name="' . $this->sNombreCampo . '" type="hidden" value="' . $this->idTercero . '"/>';
		$sRes = $sRes . '<div id="div_' . $this->sNombreCampo . '_llaves">' . html_DivTerceroV3($this->sNombreCampo, $this->sTipoDoc, $this->sDoc, $this->bOculto, $this->iPiel, $this->iMarcador, $this->ETI_INGDOC) . '</div>';
		$sRes = $sRes . html_salto();
		$sRes = $sRes . '<div id="div_' . $this->sNombreCampo . '" class="L">' . $sPref . cadena_notildes($this->sRazonSocial) . $sSuf . '</div>';
		if ($this->bConDireccion) {
			$sRes = $sRes . html_salto() . $this->HtmlDireccion();
		}
		if ($this->bConTelefono) {
			$sRes = $sRes . html_salto() . $this->HtmlTelefono();
		}
		if ($this->bConCorreo) {
			$sRes = $sRes . html_salto() . $this->HtmlCorreo();
		}
		return $sRes;
	}
	function HtmlCorreo()
	{
		$sRes = '<label class="L">' . $this->ETI_CORREO . ' <b>' . $this->sCorreoInstitucional . '</b></label>';
		return $sRes;
	}
	function HtmlDireccion()
	{
		$sRes = '<label class="L">' . $this->ETI_DIRECCION . ' <b>' . $this->sDireccion . '</b></label>';
		return $sRes;
	}
	function HtmlTelefono()
	{
		$sRes = '<label class="L">' . $this->ETI_TELEFONO . ' <b>' . $this->sTelefono . '</b></label>';
		return $sRes;
	}
	function HtmlDatos()
	{
		$sRes = '<label class="Label350"><b>' . $this->sTipoDoc . ' ' . $this->sDoc . '</b></label>';
		$sRes = $sRes . html_salto();
		$sRes = $sRes . '<label class="L"><b>' . cadena_notildes($this->sRazonSocial) . '</b></label>';
		if ($this->bConDireccion) {
			$sRes = $sRes . html_salto() . $this->HtmlDireccion();
		}
		if ($this->bConTelefono) {
			$sRes = $sRes . html_salto() . $this->HtmlTelefono();
		}
		if ($this->bConCorreo) {
			$sRes = $sRes . html_salto() . $this->HtmlCorreo();
		}
		return $sRes;
	}
	function HtmlTitulo()
	{
		$sRes = '<label class="TituloGrupo">' . $this->sTituloCampo . '</label>';
		return $sRes;
	}
	function Traer($sTipoDoc, $sDoc, $id, $objDB)
	{
		$this->bExiste = false;
		$this->Limpiar();
		$this->idTercero = 0;
		$sError = '';
		$sCondi = 'unad11id=' . $id . '';
		if ($sDoc != '') {
			$sVerifica = htmlspecialchars($sTipoDoc . $sDoc);
			if ($sVerifica != $sTipoDoc . $sDoc) {
				$sError = 'Quieto veneno... que estan intentando hacer...';
			}
			if ($sError == '') {
				$sCondi = 'unad11tipodoc="' . $sTipoDoc . '" AND unad11doc="' . $sDoc . '"';
			}
		}
		if ($sError == '') {
			$sSQL = 'SELECT unad11id, unad11tipodoc, unad11doc, unad11razonsocial, unad11telefono, unad11direccion, 
			unad11correo, unad11correonotifica, unad11correoinstitucional, unad11correofuncionario 
			FROM unad11terceros WHERE ' . $sCondi;
			$tabla = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla) > 0) {
				$fila = $objDB->sf($tabla);
				$this->idTercero = $fila['unad11id'];
				$this->sDoc = $fila['unad11doc'];
				$this->sCorreo = $fila['unad11correo'];
				$this->sCorreoInstitucional = $fila['unad11correoinstitucional'];
				if (correo_VerificarDireccion($fila['unad11correofuncionario'])) {
					$this->sCorreoInstitucional = $fila['unad11correofuncionario'];
				}
				$this->sDireccion = $fila['unad11direccion'];
				$this->sRazonSocial = $fila['unad11razonsocial'];
				$this->sTelefono = $fila['unad11telefono'];
				$this->sTipoDoc = $fila['unad11tipodoc'];
				$this->bExiste = true;
			} else {
				$this->sRazonSocial = '{' . 'No encontrado' . '}';
			}
		}
		return array($this->sTipoDoc, $this->sDoc, $this->idTercero);
	}
	function __construct($idTercero = 0, $sNombreCampo = 'unad11id', $sTituloCampo = 'Tercero', $iMarcador = 0)
	{
		require './app.php';
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
		if (!file_exists($mensajes_todas)) {
			$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
		}
		$mensajes_111 = $APP->rutacomun . 'lg/lg_111_' . $_SESSION['unad_idioma'] . '.php';
		if (!file_exists($mensajes_111)) {
			$mensajes_111 = $APP->rutacomun . 'lg/lg_111_es.php';
		}
		require $mensajes_todas;
		require $mensajes_111;

		$this->STIPODOC = $APP->tipo_doc;
		$this->ETI_INGDOC = $ETI['ing_doc'];
		$this->ETI_CORREO = $ETI['unad11correo'];
		$this->ETI_DIRECCION = $ETI['unad11direccion'];
		$this->ETI_TELEFONO = $ETI['unad11telefono'];

		$this->Limpiar();
		$this->idTercero = $idTercero;
		$this->iMarcador = $iMarcador;
		$this->sNombreCampo = $sNombreCampo;
		$this->sTituloCampo = $sTituloCampo;
	}
}
function html_BotonAyudaV2($sNombreCampo, $sTituloCampo = 'Informaci&oacute;n relevante')
{
	return html_BotonAyuda($sNombreCampo);
}
function html_BotonAyuda($sNombreCampo, $sTituloCampo = 'Informaci&oacute;n relevante')
{
	$sRes = '<label class="Label30">';
	$sRes = $sRes . '<input id="cmdAyuda_' . $sNombreCampo . '" name="cmdAyuda_' . $sNombreCampo . '" type="button" class="btMiniAyuda" onclick="AyudaLocal(\'' . $sNombreCampo . '\');" title="' . $sTituloCampo . '" />';
	$sRes = $sRes . '</label>';
	return $sRes;
}
function html_BotonVerde($sNombreCampo, $sValor, $sAccion = '', $sEtiqueta = '')
{
	$sIdBt = '';
	$sEtiquetaBt = '';
	$sAccionBt = '';
	if ($sEtiqueta != '') {
		$sEtiquetaBt = ' title="' . $sEtiqueta . '"';
	}
	if ($sAccion != '') {
		$sAccionBt = ' onclick="' . $sAccion . '"';
	}
	if ($sNombreCampo == '') {
		$sIdBt = ' id="' . $sNombreCampo . '" name="' . $sNombreCampo . '"';
	}
	$res = '<div><button' . $sIdBt . ' type="button"' . $sAccionBt . $sEtiquetaBt . '><i class="fa fa-check-square"></i> ' . $sValor . '</button></div>';
	return $res;
}
function html_BotonRojo($sNombreCampo, $sValor, $sAccion = '', $sEtiqueta = '')
{
	$sIdBt = '';
	$sEtiquetaBt = '';
	$sAccionBt = '';
	if ($sEtiqueta != '') {
		$sEtiquetaBt = ' title="' . $sEtiqueta . '"';
	}
	if ($sAccion != '') {
		$sAccionBt = ' onclick="' . $sAccion . '"';
	}
	if ($sNombreCampo != '') {
		$sIdBt = ' id="' . $sNombreCampo . '" name="' . $sNombreCampo . '"';
	}
	$res = '<div><button' . $sIdBt . ' type="button" class="__error"' . $sAccionBt . $sEtiquetaBt . '><i class="fa fa-times-circle"></i> ' . $sValor . '</button></div>';
	return $res;
}
// Una caja, es decir, un grupo campos
function html_Caja($sTitulo, $sCuerpo, $iAncho = 450, $iPiel = 1, $aDatos = NULL, $iFilas = 0, $iColumnas = 0)
{
	$sRes = '';
	if ($sTitulo != '') {
		$sRes = '<label class="TituloGrupo">' . $sTitulo . '</label>' . html_salto();
	}
	$sRes = $sRes . $sCuerpo;
	switch ($iAncho) {
		case 0:
			$sRes = '<div class="GrupoCampos">' . $sRes . html_salto() . '</div>';
			break;
		case 520:
			$sRes = '<div class="GrupoCampos520">' . $sRes . html_salto() . '</div>';
			break;
		default:
			$sRes = '<div class="GrupoCampos450">' . $sRes . html_salto() . '</div>';
			break;
	}
	return $sRes;
}
// Caja de informacion importante
function html_CajaInfoImportante($sTitulo, $sCuerpo) {
	$sRes = '<div class="container bg--tertiary max-w-100per grid gap-1" style="border: none; padding: 2rem;">';
	if ($sTitulo != '') {
		$sRes = $sRes . '<h2 class="subtitle text-on-tertiary" style="border-color: var(--sys-on-tertiary); padding-bottom: 1rem;"><b>' . $sTitulo . '</b></h2>';
	}
	$sRes = $sRes . '<div class="container--lower grid gap-1">';
	$sRes = $sRes . $sCuerpo;
	$sRes = $sRes . '</div></div>';
	return $sRes;
}
//El div alarma
function html_DivAlarmaV2($sError, $iTipoError, $bDebug = false)
{
	$sClase = '';
	$iMomento = 0;
	if ($bDebug) {
		$sError = $sError . ' -- ' . $iTipoError . '';
	}
	if ($iTipoError == '') {
		$iTipoError = 0;
	}
	if ($sError != '') {
		$sClase = ' class="alarma_roja"';
		$bPasa = false;
		if ($iTipoError == 1) {
			$bPasa = true;
			$iMomento = 1;
		}
		if ($iTipoError === 'verde') {
			$bPasa = true;
			$iMomento = 2;
		}
		if ($bPasa) {
			$sClase = ' class="alarma_verde"';
			$iTipoError = 1;
		}
		$bPasa = false;
		if ($iTipoError == 2) {
			$bPasa = true;
			$iMomento = 3;
		}
		if ($iTipoError === 'azul') {
			$bPasa = true;
			$iMomento = 4;
		}
		if ($bPasa) {
			$sClase = ' class="alarma_azul"';
			$iTipoError = 2;
		}
		if (strlen($sError) > 1000) {
			$sError = '<div class="divScroll200">' . $sError . '</div>';
		}
	}
	if ($bDebug) {
		$sError = $sError . ' -- ' . $iMomento . '';
	}
	$sRes = '<div id="div_alarma"' . $sClase . '>' . $sError . '</div>';
	return $sRes;
}
function html_DivAyudaLocal($sNombreCampo)
{
	$sRes = '<div class="salto1px"></div>';
	$sRes = $sRes . '<div id="div_ayuda_' . $sNombreCampo . '" style="display:none" class="GrupoCamposAyuda"></div>';
	$sRes = $sRes . '<div class="salto1px"></div>';
	return $sRes;
}
function html_DivTerceroV2($sNombreCampo, $sTipoDoc, $sDoc, $bOculto, $idAccion = 0, $sPlaceHolder = '', $iBotones = 3)
{
	return html_DivTerceroV3($sNombreCampo, $sTipoDoc, $sDoc, $bOculto, 0, $idAccion, $sPlaceHolder, $iBotones);
}
function html_DivTerceroV4($sNombreCampo, $sTipoDoc, $sDoc, $bOculto, $iPiel, $objDB, $idAccion, $sPlaceHolder)
{
	return html_DivTerceroV3($sNombreCampo, $sTipoDoc, $sDoc, $bOculto, 0, $idAccion, $sPlaceHolder, 3);
}
function html_DivTerceroV3($sNombreCampo, $sTipoDoc, $sDoc, $bOculto, $iPiel, $idAccion = 0, $sPlaceHolder = '', $iBotones = 3)
{
	$sRes = '';
	if ($bOculto) {
		$sRes = '' . html_oculto($sNombreCampo . '_td', $sTipoDoc) . ' ' . html_oculto($sNombreCampo . '_doc', $sDoc) . '';
	} else {
		$sAdd = '';
		if ($sPlaceHolder != '') {
			$sAdd = ' placeholder="' . $sPlaceHolder . '"';
		}
		$sRes = html_tipodocV2($sNombreCampo . '_td', $sTipoDoc, "ter_muestra('" . $sNombreCampo . "', " . $idAccion . ")", false);
		$sRes = $sRes . '<input id="' . $sNombreCampo . '_doc" name="' . $sNombreCampo . '_doc" type="text" value="' . $sDoc . '" onchange="ter_muestra(\'' . $sNombreCampo . '\',' . $idAccion . ')" maxlength="20" onclick="revfoco(this);"' . $sAdd . '/>';
		$bConbuscar = false;
		$bConCrear = false;
		switch ($iBotones) {
			case 1:
				$bConbuscar = true;
				break;
			case 3:
				$bConbuscar = true;
				break;
		}
		if ($bConbuscar) {
			$sRes = $sRes . '</label>';
			$sRes = $sRes . '<label class="Label30">';
			$sRes = $sRes . '<input type="button" name="b' . $sNombreCampo . '" value="Buscar" class="btMiniBuscar" onclick="buscarV2016(\'' . $sNombreCampo . '\')" title="Buscar Tercero"/>';
		}
		if ($bConCrear) {
			$sRes = $sRes . '</label>';
			$sRes = $sRes . '<label class="Label30">';
			$sRes = $sRes . '<input type="button" name="c' . $sNombreCampo . '" value="Crear" class="btMiniPersona" onclick="ter_crea(\'' . $sNombreCampo . '\',' . $idAccion . ')" title="Crear Tercero"/>';
		}
	}
	return '<label class="Label350">' . $sRes . '</label>';
}
// Versión 2023 - NO LLEVA PIEL
function html_DivTerceroV7($sNombreCampo, $sTipoDoc, $sDoc, $bOculto, $idAccion = 0, $sPlaceHolder = '', $iBotones = 3)
{
	$sRes = '';
	if ($bOculto) {
		$sRes = '' . html_oculto($sNombreCampo . '_td', $sTipoDoc) . ' ' . html_oculto($sNombreCampo . '_doc', $sDoc) . '';
	} else {
		$sAdd = '';
		if ($sPlaceHolder != '') {
			$sAdd = ' placeholder="' . $sPlaceHolder . '"';
		}
		$sRes = '' . html_tipodocV2($sNombreCampo . '_td', $sTipoDoc, "ter_muestra('" . $sNombreCampo . "', " . $idAccion . ")", false) . '';
		$sRes = $sRes . '<input id="' . $sNombreCampo . '_doc" name="' . $sNombreCampo . '_doc" type="text" value="' . $sDoc . '" onchange="ter_muestra(\'' . $sNombreCampo . '\',' . $idAccion . ')" maxlength="20" onclick="revfoco(this);"' . $sAdd . '/>';
		$bConbuscar = false;
		$bConCrear = false;
		switch ($iBotones) {
			case 1:
				$bConbuscar = true;
				break;
			case 3:
				$bConbuscar = true;
				break;
		}
		if ($bConbuscar) {
			$sRes = $sRes . '<input type="button" name="b' . $sNombreCampo . '" value="Buscar" class="btMiniBuscar" onclick="buscarV2016(\'' . $sNombreCampo . '\')" title="Buscar Tercero"/>';
		}
		if ($bConCrear) {
			$sRes = $sRes . '<input type="button" name="c' . $sNombreCampo . '" value="Crear" class="btMiniPersona" onclick="ter_crea(\'' . $sNombreCampo . '\',' . $idAccion . ')" title="Crear Tercero"/>';
		}
	}
	return '<div>' . $sRes . '</div>';
}
//En este el tipo doc se toma de la tabla unad45tipodoc
function html_Combo145($sNombreCampo, $sTipoDoc, $objDB, $objCombos, $idAccion = 0, $iEspecial = 0)
{
	$sRes = '';
	$sCondi = 'unad45id>=0';
	switch ($iEspecial) {
		case 16: // Proveedores
			$sCondi = 'unad45aplicaproveed>0';
			break;
	}
	$objCombos->nuevo($sNombreCampo . '_td', $sTipoDoc, false);
	if ($idAccion >= 0) {
		$objCombos->sAccion = "ter_muestra('" . $sNombreCampo . "', " . $idAccion . ")";
	}
	$objCombos->iAncho = 60;
	$sSQL = 'SELECT unad45codigo AS id, CONCAT(unad45titulo, " - ", unad45nombre) AS nombre 
	FROM unad45tipodoc WHERE ' . $sCondi . ' 
	ORDER BY unad45activo DESC, unad45orden, unad45titulo';
	$sRes = $objCombos->html($sSQL, $objDB);
	return $sRes;
}
function html_DivTerceroV8($sNombreCampo, $sTipoDoc, $sDoc, $bOculto, $objDB, $objCombos, $idAccion = 0, $sPlaceHolder = '', $iBotones = 1, $iEspecial = 0)
{
	require './app.php';
	$iPiel = iDefinirPiel($APP, 2);
	$sRes = '';
	if ($bOculto) {
		$sRes = '' . html_oculto($sNombreCampo . '_td', $sTipoDoc) . '&nbsp;' . html_oculto($sNombreCampo . '_doc', $sDoc) . '';
	} else {
		$sAdd = '';
		if ($sPlaceHolder != '') {
			$sAdd = ' placeholder="' . $sPlaceHolder . '"';
		}
		$sClassTipoDoc = 'Label60';
		if ($iPiel == 2) {
			$sClassTipoDoc = 'w-8';
		}
		// TipoDoc
		$sRes = '<label class="' . $sClassTipoDoc . '">' . html_Combo145($sNombreCampo, $sTipoDoc, $objDB, $objCombos, $idAccion, $iEspecial) . '</label>';
		// Documento
		$sClassDocumento = 'Label220';
		if ($iPiel == 2) {
			$sClassDocumento = 'w-35';
		}
		$sRes = $sRes . '<label class="' . $sClassDocumento . '">';
		$sRes = $sRes . '<input id="' . $sNombreCampo . '_doc" name="' . $sNombreCampo . '_doc" type="text" value="' . $sDoc . '" onchange="ter_muestra(\'' . $sNombreCampo . '\',' . $idAccion . ')" maxlength="20" class="' . $sClassDocumento . '" onclick="revfoco(this);"' . $sAdd . '/>';
		$sRes = $sRes . '</label>';
		$bConbuscar = false;
		$bConCrear = false;
		switch ($iBotones) {
			case 1:
				$bConbuscar = true;
				break;
			case 3:
				$bConbuscar = true;
				break;
		}
		if ($bConbuscar || $bConCrear) {
			switch ($iPiel) {
				case 2:
					$objForma = new clsHtmlForma($iPiel);
					$sRes .= '<div class="flex" style="gap: 1rem">';
					if ($bConbuscar) {
						$sRes .= $objForma->htmlBotonSolo('b' . $sNombreCampo, 'btMiniBuscar', 'buscarV2016(\'' . $sNombreCampo . '\')', 'Buscar Tercero');
					}
					if ($bConCrear) {
						$sRes .= $objForma->htmlBotonSolo('c' . $sNombreCampo, 'btMiniPersona', 'ter_crea(\'' . $sNombreCampo . '\',' . $idAccion . ')', 'Crear Tercero');
					}
					$sRes .= '</div>';
					break;
				default:
					if ($bConbuscar) {
						$sRes = $sRes . '<label class="Label30">';
						$sRes = $sRes . '<input type="button" name="b' . $sNombreCampo . '" value="Buscar" class="btMiniBuscar" onclick="buscarV2016(\'' . $sNombreCampo . '\')" title="Buscar Tercero"/>';
						$sRes = $sRes . '</label>';
					}
					if ($bConCrear) {
						$sRes = $sRes . '<label class="Label30">';
						$sRes = $sRes . '<input type="button" name="c' . $sNombreCampo . '" value="Crear" class="btMiniPersona" onclick="ter_crea(\'' . $sNombreCampo . '\',' . $idAccion . ')" title="Crear Tercero"/>';
						$sRes = $sRes . '</label>';
					}
					break;
			}
		}
	}
	$sClassFin = '';
	if ($iPiel == 2) {
		$sClassFin = ' class="flex"';
	}
	return '<div' . $sClassFin . '>' . $sRes . '</div>';
}
//Mensaje de espere.
function htmlEspereV2($sMsgEspere = 'Procesando datos, por favor espere...', $bSpinner = true)
{
	$sSpinner = '';
	$sStyle = '';
	$sStyle2 = '';
	if ($bSpinner) {
		$sSpinner = '<div class="spinner"></div>';
		$sStyle = 'style="padding-top: 8px; padding-left: 5px;"';
		$sStyle2 = 'style="height: 45px; padding-top: 4px; padding-left: 5px;"';
	}
	$res = '<div class="GrupoCamposAyuda" ' . $sStyle2 . '>';
	$res = $res . '<div class="MarquesinaMedia">' . $sSpinner;
	$res = $res . '<div ' . $sStyle . '>' . $sMsgEspere . '</div>';
	$res = $res . '</div>';
	$res = $res . '</div>';
	return $res;
}
//Mostrar la fecha desde un numero
function html_FechaEnNumero($nomcampo, $valor = 0, $bvacio = false, $accion = '', $iagnoini = 0, $iagnofin = 0, $idiafijo = 0, $imesfijo = 0)
{
	if (!$bvacio) {
		if ((int)$valor == 0) {
			$valor = fecha_DiaMod();
		}
	}
	list($_dia, $_mes, $_agno) = fecha_DividirNumero($valor);
	if ($iagnoini == 0) {
		$iagnoini = 2000;
	}
	if ($iagnofin == 0) {
		if ($_agno == 0) {
			$iagnofin = date('Y') + 5;
		} else {
			$iagnofin = $_agno + 5;
		}
	}
	$res = '';
	if ($idiafijo == 0) {
		$res = html_ComboDia($nomcampo . '_dia', $_dia, $bvacio, 'fecha_AjustaNum(\'' . $nomcampo . "','" . $accion . '\');');
	} else {
		$svr = $idiafijo;
		if ($idiafijo < 10) {
			$svr = '0' . $svr;
		}
		$res = $res . '<input id="' . $nomcampo . '_dia" name="' . $nomcampo . '_dia" type="hidden" value="' . $svr . '"/>&nbsp;<b>' . $svr . '/</b>';
	}
	$res = $res . ' ' . html_ComboMes($nomcampo . '_mes', $_mes, $bvacio, 'fecha_AjustaNum(' . "'" . $nomcampo . "','" . $accion . "'" . ')') . ' ';
	if ($iagnofin < $iagnoini) {
		$iagnofin = $iagnoini;
	}
	$bconagno = true;
	if ($iagnofin == $iagnoini) {
		$bconagno = false;
	}
	if ($bconagno) {
		$res = $res . '<select id="' . $nomcampo . '_agno" name="' . $nomcampo . '_agno" onchange="fecha_AjustaNum(' . "'" . $nomcampo . "','" . $accion . "'" . ')" class="cbo_agno">';
		if ($bvacio) {
			$res = $res . '<option value="0"></option>';
		}
		for ($size = $iagnofin; $size >= $iagnoini; $size--) {
			$ssel = '';
			if ($size == $_agno) {
				$ssel = ' selected';
			}
			$res = $res . '<option' . $ssel . ' value="' . $size . '">' . $size . '</option>';
		}
		$res = $res . '</select>';
	} else {
		$res = $res . '<input id="' . $nomcampo . '_agno" name="' . $nomcampo . '_agno" type="hidden" value="' . $iagnoini . '"/>&nbsp;<b>/' . $iagnoini . '</b>';
	}
	if (trim($valor) == '') {
		$valor = '0';
	}
	$res = $res . '<input id="' . $nomcampo . '" name="' . $nomcampo . '" type="hidden" value="' . $valor . '"/>';
	return $res;
}

function html_NoPermiso($iCodModulo, $sTituloModulo, $iPiel = 0)
{
	require './app.php';
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
	if (!file_exists($mensajes_todas)) {
		$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
	}
	require $mensajes_todas;
	$sCambiaSesion = $ETI['msg_nopermiso'];
	if ($_SESSION['unad_id_tercero'] == 0) {
		$sCambiaSesion = '' . $ETI['msg_nosesion'];
	}
	switch ($iPiel) {
		default:
			$rRes = '<div id="interna">
		<form id="frmedita" name="frmedita" method="post" action="">
		<div id="titulacion">
		<div id="titulacionD">
		<input id="cmdAyuda" name="cmdAyuda" type="button" class="btUpAyuda" onclick="muestraayuda(' . $iCodModulo . ');" title="' . $ETI['bt_ayuda'] . '" value="' . $ETI['bt_ayuda'] . '"/>
		</div>
		<div id="titulacionI">
		<h2>' . $sTituloModulo . '</h2>
		</div>
		</div>
		<div id="cargaForm">
		<div id="area">
		<div class="MarquesinaMedia">
		' . $sCambiaSesion . '
		</div>
		</div>
		</div>
		</form>
		</div>';
			break;
	}
	return $rRes;
}
function html_LnkArchivoV2($origen, $id, $titulo = 'Descargar', $sRaiz = 'verarchivo.php', $sClase = 'class="lnkresalte"')
{
	$res = '&nbsp;';
	if ($id != 0) {
		$res = '<a href="' . $sRaiz . '?u=' . url_encode($origen . '|' . $id) . '" target="_blank" ' . $sClase . '>' . $titulo . '</a>';
	}
	return $res;
}
function html_menuCampus($idsistema, $objDB, $iPiel = 0, $bDebug = false, $idTercero = 0, $bSalto = false)
{
	$sDebug = '';
	require './app.php';
	$idEntidad = Traer_Entidad();
	$_SESSION['u_ultimominuto'] = iminutoavance();
	if ($idTercero == 0) {
		$idTercero = $_SESSION['unad_id_tercero'];
	}
	$sDebug = sesion_actualizar_v2($objDB, $bDebug);
	$sHTML = '';
	$sClaseLinkBase = '';
	$sClaseLinkItem = '';
	$sClaseLiBase = '';
	$sClaseLiItem = '';
	$sInicioBloque = '';
	$sFinBloque = '';
	$sInicioItem = '';
	$sFinItem = '';
	$et_inicio = 'Inicio';
	$et_accesit = 'ACCESIT';
	$et_clave = 'Contrase&ntilde;a';
	$et_miperfil = 'Mi perfil';
	$et_salir = 'Salir';
	$et_miscursos = 'Mis cursos';
	$et_misinscripciones = 'Mis inscripciones';
	$et_misprogramas = 'Mis programas';
	$et_proveedores = 'Proveedores';
	$et_vida = 'Vida acad&eacute;mica';
	$et_sai = 'Sistema de Atenci&oacute;n Integral';
	$et_cipas = 'CIPAS';
	$et_servicios = 'Servicios';
	$et_mooc = 'Cursos MOOC';
	$et_admisiones = 'Admisiones';
	$et_oferabierta = 'Cursos Abiertos';
	$et_contacto = 'Datos de contacto';
	$et_emprendimiento = 'Emprendimiento';
	$et_soporte = 'Soporte';
	$et_banner = 'Banner';
	$et_erp = 'SIGAF';
	$et_gestion = 'Gesti&oacute;n';
	$et_modulos = 'Acad&eacute;mico';
	$et_ayuda = 'Ayuda';
	$et_manuales = 'Manuales';
	$et_acerca = 'Acerca de...';
	switch ($_SESSION['unad_idioma']) {
		case 'en':
			$et_ayuda = 'Help';
			$et_acerca = 'About...';
			$et_miperfil = 'My profile';
			$et_manuales = 'Manuals';
			$et_salir = 'Exit';
			break;
		case 'pt':
			$et_ayuda = 'Ajuda';
			$et_acerca = 'Sobre...';
			$et_miperfil = 'Meu perfil';
			$et_manuales = 'Manuais';
			$et_salir = 'Sair';
			break;
	}
	$sSalto = '';
	if ($bSalto) {
		$sSalto = ' target="_blank"';
	}
	if ($iPiel == 0) {
		$sHTML = '<style> ';
		$sHTML = $sHTML . '#menu-personalizado{height: 20px;display: grid;align-items: center;justify-content: center;} ';
		$sHTML = $sHTML . '#menu-personalizado ul#navmenu-h{height: unset;} ';
		$sHTML = $sHTML . 'ul#navmenu-h ul {z-index: 100;} ';
		$sHTML = $sHTML . '#menu-personalizado li:hover > #menu-personalizado ul#navmenu-h ul{border: 1px solid;} ';
		$sHTML = $sHTML . 'ul#navmenu-h a:hover, ul#navmenu-h li:hover a, ul#navmenu-h li.iehover a {color: #000;background: #ffffff !important;} ';
		$sHTML = $sHTML . 'ul#navmenu-h a:hover{background-color: red !important;text-decoration: underline;} ';
		$sHTML = $sHTML . '</style> ';
		$sHTML = $sHTML . '<div id="menu-personalizado" class="menuapp"><ul id="navmenu-h" style="position: relative;!important">';
		$sClaseLinkBase = ' class="ppal"';
		$sInicioBloque = '<ul style="text-align:left;"><li class="ini"></li>';
		$sFinBloque = '</ul>';
		$sInicioItem = '<li>';
		$sFinItem = '</li>';
	}
	if ($iPiel == 1) {
		$sHTML = '<ul class="nav nav-tabs">';
		$sClaseLinkBase = ' class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"';
		$sClaseLinkItem = ' class="dropdown-item"';
		$sClaseLiBase = ' class="nav-item dropdown"';
		$sInicioBloque = '<div class="dropdown-menu">';
		$sFinBloque = '</div>';
	}
	$sHTML = $sHTML . '<li' . $sClaseLiBase . '><a href="#"' . $sClaseLinkBase . '><span>' . $et_inicio . '</span></a>' . $sInicioBloque;
	$sHTML = $sHTML . $sInicioItem . '<a href="accesit.php"' . $sClaseLinkItem . '><span>' . $et_accesit . '</span></a>' . $sFinItem;
	$bEspeciales = false;
	$bRevisaEspeciales = false;
	if ((int)$idTercero > 0) {
		switch ($idEntidad) {
			case 0: // Colombia
				$bRevisaEspeciales = true;
				break;
		}
		if ($bRevisaEspeciales) {
			if (function_exists('f107_PerfilPertenece')) {
				if (f107_PerfilPertenece($idTercero, 1, $objDB)) {
					$bEspeciales = true;
				} else {
				}
			}
		}
		$sHTML = $sHTML . $sInicioItem . '<a href="miperfil.php"' . $sClaseLinkItem . $sSalto . '><span>' . $et_miperfil . '</span></a>' . $sFinItem;
		$sHTML = $sHTML . $sInicioItem . '<a href="contrasegna.php"' . $sClaseLinkItem . $sSalto . '><span>' . $et_clave . '</span></a>' . $sFinItem;
		$sHTML = $sHTML . $sInicioItem . '<a href="contacto.php"' . $sClaseLinkItem . $sSalto . '><span>' . $et_contacto . '</span></a>' . $sFinItem;
		$sHTML = $sHTML . $sInicioItem . '<a href="salir.php"' . $sClaseLinkItem . '><span>' . $et_salir . '</span></a>' . $sFinItem;
		$sHTML = $sHTML . $sFinBloque . '';
		$sHTML = $sHTML . '<li' . $sClaseLiBase . '><a href="#"' . $sClaseLinkBase . '><span>' . $et_vida . '</span></a>' . $sInicioBloque;
		$sHTML = $sHTML . $sInicioItem . '<a href="miscursos.php"' . $sClaseLinkItem . $sSalto . '><span>' . $et_miscursos . '</span></a>' . $sFinItem;
		$sHTML = $sHTML . $sInicioItem . '<a href="misinscripciones.php"' . $sClaseLinkItem . $sSalto . '><span>' . $et_misinscripciones . '</span></a>' . $sFinItem;
		if ($idEntidad == 0) {
			$sHTML = $sHTML . $sInicioItem . '<a href="cipas.php"' . $sClaseLinkItem . $sSalto . '><span>' . $et_cipas . '</span></a>' . $sFinItem;
			$sHTML = $sHTML . $sInicioItem . '<a href="sai.php"' . $sClaseLinkItem . $sSalto . '><span>' . $et_sai . '</span></a>' . $sFinItem;
			if ($bEspeciales) {
				$sHTML = $sHTML . $sInicioItem . '<a href="misprogramas.php"' . $sClaseLinkItem . $sSalto . '><span>' . $et_misprogramas . '</span></a>' . $sFinItem;
			}
		}
		$sHTML = $sHTML . $sFinBloque . '';
		$sHTML = $sHTML . '<li' . $sClaseLiBase . '><a href="#"' . $sClaseLinkBase . '><span>' . $et_servicios . '</span></a>' . $sInicioBloque;
		$sHTML = $sHTML . $sInicioItem . '<a href="admisiones.php"' . $sClaseLinkItem . $sSalto . '><span>' . $et_admisiones . '</span></a>' . $sFinItem;
		$sHTML = $sHTML . $sInicioItem . '<a href="mooc.php"' . $sClaseLinkItem . $sSalto . '><span>' . $et_mooc . '</span></a>' . $sFinItem;
		if ($idEntidad == 0) {
			$sHTML = $sHTML . $sInicioItem . '<a href="ofertaabierta.php"' . $sClaseLinkItem . $sSalto . '><span>' . $et_oferabierta . '</span></a>' . $sFinItem;
			$sHTML = $sHTML . $sInicioItem . '<a href="emprendedor.php"' . $sClaseLinkItem . $sSalto . '><span>' . $et_emprendimiento . '</span></a>' . $sFinItem;
			if ($bEspeciales) {
				$sHTML = $sHTML . $sInicioItem . '<a href="https://erp.unad.edu.co/proveedor/" target="_blank"' . $sClaseLinkItem . $sSalto . '><span>' . $et_proveedores . '</span></a>' . $sFinItem;
			}
		}
	}
	$sHTML = $sHTML . $sFinBloque . '';
	//Ver si tiene funciones administrativas.
	$bConSoporte = false;
	$bInternos = false;
	if ((int)$idTercero > 0) {
		if (is_object($objDB)) {
			$bInternos = true;
			list($bConSoporte, $sDebugP) = seg_revisa_permisoV3(202, 1, $idTercero, $objDB);
		}
	}
	if ($bInternos) {
		$sSQL = 'SELECT unad88appsestado FROM unad88opciones WHERE unad88id=1';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			if ($fila['unad88appsestado'] == 9) {
				$bInternos = false;
			}
		} else {
			$bInternos = false;
		}
	}
	if ($bInternos) {
		//Acceso a los modulos en los que tiene permiso.
		$bConModulos = false;
		$bConERP = false;
		$bConGestion = false;
		$sPerfiles = '-99';
		$sSQL = 'SELECT unad07idperfil FROM unad07usuarios WHERE unad07idtercero=' . $idTercero . ' AND unad07vigente="S"';
		$tabla = $objDB->ejecutasql($sSQL);
		while ($fila = $objDB->sf($tabla)) {
			$sPerfiles = $sPerfiles . ',' . $fila['unad07idperfil'];
		}
		$sSistema = '-99';
		//, unad01orden
		if ($sPerfiles != '-99') {
			$sSQL = 'SELECT T1.unad02idsistema, TS.unad01orden 
			FROM unad06perfilmodpermiso AS TB, unad02modulos AS T1, unad01sistema AS TS 
			WHERE TB.unad06idperfil IN (' . $sPerfiles . ') AND TB.unad06idpermiso=1 AND TB.unad06vigente="S" 
			AND TB.unad06idmodulo=T1.unad02id AND T1.unad02idsistema NOT IN (99)
			AND T1.unad02idsistema=TS.unad01id 
			GROUP BY T1.unad02idsistema, TS.unad01orden';
			$tabla = $objDB->ejecutasql($sSQL);
			while ($fila = $objDB->sf($tabla)) {
				if ($fila['unad01orden'] < 70) {
					$bConModulos = true;
				} else {
					if ($fila['unad01orden'] < 100) {
						$bConERP = true;
					} else {
						$bConGestion = true;
					}
				}
				$sSistema = $sSistema . ',' . $fila['unad02idsistema'];
			}
		}
		$sRutaBase = 'https://aurea2.unad.edu.co/';
		switch ($idEntidad) {
			case 0: // Colombia
				break;
			default:
				$sRutaBase = './';
				break;
		}
		$sSalto = ' target="_blank"';
		if ($bConModulos) {
			//Vemos los temas asociados a modulos academicos y eventos.
			$sSQL = 'SELECT unad01nombre, unad01descripcion, unad01ruta 
			FROM unad01sistema 
			WHERE unad01id IN (' . $sSistema . ') AND unad01publico="S" AND unad01instalado="S" AND unad01orden<70 
			ORDER BY unad01orden, unad01nombre';
			$tabla = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla) > 0) {
				$sHTML = $sHTML . '<li' . $sClaseLiBase . '><a href="#"' . $sClaseLinkBase . '><span>' . $et_modulos . '</span></a>' . $sInicioBloque;
			}
			while ($fila = $objDB->sf($tabla)) {
				$sRutaDef = $sRutaBase . cadena_Reemplazar($fila['unad01ruta'], '../', '');
				$sHTML = $sHTML . $sInicioItem . '<a href="' . $sRutaDef . '"' . $sClaseLinkItem . ' title="' . cadena_notildes($fila['unad01descripcion']) . '" target="_blank"><span>' . strtoupper($fila['unad01nombre']) . '</span></a>' . $sFinItem;
			}
			$sHTML = $sHTML . $sFinBloque . '</li>';
		}
		if ($bConERP) {
			//ERP
			$sSQL = 'SELECT unad01nombre, unad01descripcion, unad01ruta 
			FROM unad01sistema 
			WHERE unad01id IN (' . $sSistema . ') AND unad01publico="S" AND unad01instalado="S" AND unad01orden>69 AND unad01orden<100  
			ORDER BY unad01orden, unad01nombre';
			$tabla = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla) > 0) {
				$bConERP = true;
				$sHTML = $sHTML . '<li' . $sClaseLiBase . '><a href="#"' . $sClaseLinkBase . '><span>' . $et_erp . '</span></a>' . $sInicioBloque;
			}
			while ($fila = $objDB->sf($tabla)) {
				$sRutaDef = $sRutaBase . cadena_Reemplazar($fila['unad01ruta'], '../', '');
				$sHTML = $sHTML . $sInicioItem . '<a href="' . $sRutaDef . '"' . $sClaseLinkItem . ' title="' . cadena_notildes($fila['unad01descripcion']) . '" target="_blank"><span>' . strtoupper($fila['unad01nombre']) . '</span></a>' . $sFinItem;
			}
			$sHTML = $sHTML . $sFinBloque . '</li>';
		}
		if ($bConGestion) {
			//Modulos de gestion.
			$sSQL = 'SELECT unad01nombre, unad01descripcion, unad01ruta 
			FROM unad01sistema 
			WHERE unad01id IN (' . $sSistema . ') AND unad01publico="S" AND unad01instalado="S" AND unad01orden>99  
			ORDER BY unad01orden, unad01nombre';
			$tabla = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla) > 0) {
				$bConGestion = true;
				$sHTML = $sHTML . '<li' . $sClaseLiBase . '><a href="#"' . $sClaseLinkBase . '><span>' . $et_gestion . '</span></a>' . $sInicioBloque;
			}
			while ($fila = $objDB->sf($tabla)) {
				$sRutaDef = $sRutaBase . cadena_Reemplazar($fila['unad01ruta'], '../', '');
				$sHTML = $sHTML . $sInicioItem . '<a href="' . $sRutaDef . '"' . $sClaseLinkItem . ' title="' . cadena_notildes($fila['unad01descripcion']) . '" target="_blank"><span>' . strtoupper($fila['unad01nombre']) . '</span></a>' . $sFinItem;
			}
			$sHTML = $sHTML . $sFinBloque . '</li>';
		}
	}
	//Termina las funciones administrativas
	$sHTML = $sHTML . '<li' . $sClaseLiBase . '><a href="#"' . $sClaseLinkBase . '><span>' . $et_ayuda . '</span></a>' . $sInicioBloque;
	if ($bConSoporte) {
		$sHTML = $sHTML . $sInicioItem . '<a href="adminbanner.php"' . $sClaseLinkItem . $sSalto . '><span>' . $et_banner . '</span></a>' . $sFinItem;
	}
	if ((int)$idTercero > 0) {
		$sHTML = $sHTML . $sInicioItem . '<a href="unadayudas.php"' . $sClaseLinkItem . '><span>' . $et_manuales . '</span></a>' . $sFinItem;
	}
	$sHTML = $sHTML . $sInicioItem . '<a href="acercade.php"' . $sClaseLinkItem . '><span>' . $et_acerca . '</span></a>' . $sFinItem;
	$sHTML = $sHTML . $sFinBloque . '</li>';
	if ($iPiel == 0) {
		$sHTML = $sHTML . '</ul></div>';
	}
	if ($iPiel == 1) {
		$sHTML = $sHTML . '</ul>';
	}
	return array($sHTML, $sDebug);
}
function html_menuCampusV2($objDB, $iPiel = 2, $bDebug = false, $idTercero = 0, $bSalto = false)
{
	require './app.php';
	$sDebug = '';
	$idEntidad = Traer_Entidad();
	$_SESSION['u_ultimominuto'] = iminutoavance();
	if ($idTercero == 0) {
		$idTercero = $_SESSION['unad_id_tercero'];
	}
	$sDebug = sesion_actualizar_v2($objDB, $bDebug);
	$sHTML = '';
	$sClaseLinkBase = '';
	$sClaseLinkItem = '';
	$sClaseLiBase = '';
	$sClaseLiItem = '';
	$sInicioBloque = '';
	$sFinBloque = '';
	$sInicioItem = '';
	$sFinItem = '';
	$et_inicio = 'Inicio';
	$et_accesit = 'ACCESIT';
	$et_clave = 'Contrase&ntilde;a';
	$et_miperfil = 'Mi perfil';
	$et_inisesion = 'Iniciar Sesi&oacute;n';
	$et_salir = 'Salir';
	$et_miscursos = 'Mis cursos';
	$et_misinscripciones = 'Mis inscripciones';
	$et_misprogramas = 'Mis programas';
	$et_proveedores = 'Proveedores';
	$et_vida = 'Vida acad&eacute;mica';
	$et_sai = 'Sistema de Atenci&oacute;n Integral';
	$et_cipas = 'CIPAS';
	$et_servicios = 'Servicios';
	$et_mooc = 'Cursos MOOC';
	$et_admisiones = 'Admisiones';
	$et_oferabierta = 'Cursos Abiertos';
	$et_contacto = 'Datos de contacto';
	$et_emprendimiento = 'Emprendimiento';
	$et_soporte = 'Soporte';
	$et_banner = 'Banner';
	$et_erp = 'SIGAF';
	$et_gestion = 'Gesti&oacute;n';
	$et_modulos = 'Acad&eacute;mico';
	$et_ayuda = 'Ayuda';
	$et_manuales = 'Manuales';
	$et_acerca = 'Acerca de...';
	switch ($_SESSION['unad_idioma']) {
		case 'en':
			$et_ayuda = 'Help';
			$et_acerca = 'About...';
			$et_miperfil = 'My profile';
			$et_manuales = 'Manuals';
			$et_salir = 'Exit';
			break;
		case 'pt':
			$et_ayuda = 'Ajuda';
			$et_acerca = 'Sobre...';
			$et_miperfil = 'Meu perfil';
			$et_manuales = 'Manuais';
			$et_salir = 'Sair';
			break;
	}

	$bInternos = false;
	$bConModulos = false;
	$bConERP = false;
	$bConGestion = false;
	$sHTML = '';
	$sClaseLinkBase = '';
	$sClaseLinkItem = '';
	$sClaseLiBase = '';
	$sClaseLiItem = '';
	$sInicioBloque = '<div class="option-drop"><ul>';
	$sFinBloque = '</ul></div>';
	$sInicioItem = '';
	$sFinItem = '';
	$sHTML = $sHTML . '<li class="options__item">';
	$sHTML = $sHTML . '<a class="option">' . $et_inicio . '<i class="iNavigateNext"></i></a>';
	$sHTML = $sHTML . $sInicioBloque;
	if ((int)$idTercero > 0) {
		$bEspeciales = false;
		$bConSoporte = false;
		if (function_exists('f107_PerfilPertenece')) {
			if (f107_PerfilPertenece($idTercero, 1, $objDB)) {
				$bEspeciales = true;
			} else {
			}
		}
		if ((int)$idTercero > 0) {
			if (is_object($objDB)) {
				$bInternos = true;
				list($bConSoporte, $sDebugP) = seg_revisa_permisoV3(202, 1, $idTercero, $objDB);
			}
		}
		$sHTML = $sHTML . '<li><a href="accesit.php" title="' . $et_accesit . '">' . $et_accesit . '</a></li>';
		$sHTML = $sHTML . '<li><a href="miperfil.php" title="' . $et_miperfil . '">' . $et_miperfil . '</a></li>';
		$sHTML = $sHTML . '<li><a href="contrasegna.php" title="' . $et_clave . '">' . $et_clave . '</a></li>';
		$sHTML = $sHTML . '<li><a href="contacto.php" title="' . $et_contacto . '">' . $et_contacto . '</a></li>';
		if ($bConSoporte) {
			$sHTML = $sHTML . '<li><a href="adminbanner.php" title="' . $et_banner . '">' . $et_banner . '</a></li>';
		}
		$sHTML = $sHTML . $sFinBloque;
		$sHTML = $sHTML . '</li><li class="options__item">';
		$sHTML = $sHTML . '<a class="option">' . $et_vida . '<i class="iNavigateNext"></i></a>';
		$sHTML = $sHTML . $sInicioBloque;
		$sHTML = $sHTML . '<li><a href="miscursos.php" title="' . $et_miscursos . '">' . $et_miscursos . '</a></li>';
		if ($idEntidad == 0) {
			$sHTML = $sHTML . '<li><a href="cipas.php" title="' . $et_cipas . '">' . $et_cipas . '</a></li>';
			$sHTML = $sHTML . '<li><a href="sai.php" title="' . $et_sai . '">' . $et_sai . '</a></li>';
			if ($bEspeciales) {
				$sHTML = $sHTML . '<li><a href="misprogramas.php" title="' . $et_misprogramas . '">' . $et_misprogramas . '</a></li>';
			}
		}
		$sHTML = $sHTML . $sFinBloque;
		$sHTML = $sHTML . '</li><li class="options__item">';
		$sHTML = $sHTML . '<a class="option">' . $et_servicios . '<i class="iNavigateNext"></i></a>';
		$sHTML = $sHTML . $sInicioBloque;
		$sHTML = $sHTML . '<li><a href="admisiones.php" title="' . $et_admisiones . '">' . $et_admisiones . '</a></li>';
		$sHTML = $sHTML . '<li><a href="mooc.php" title="' . $et_mooc . '">' . $et_mooc . '</a></li>';
		if ($idEntidad == 0) {
			$sHTML = $sHTML . '<li><a href="ofertaabierta.php" title="' . $et_oferabierta . '">' . $et_oferabierta . '</a></li>';
			$sHTML = $sHTML . '<li><a href="emprendedor.php" title="' . $et_emprendimiento . '">' . $et_emprendimiento . '</a></li>';
			if ($bEspeciales) {
				$sHTML = $sHTML . '<li><a href="proveedores.php" title="' . $et_proveedores . '">' . $et_proveedores . '</a></li>';
			}
		}
	} else {
		$sHTML = $sHTML . '<li><a href="./" title="' . $et_inisesion . '">' . $et_inisesion . '</a></li>';
	}
	$sHTML = $sHTML . $sFinBloque;
	//Ver si tiene funciones administrativas.
	if ($bInternos) {
		$sSQL = 'SELECT unad88appsestado FROM unad88opciones WHERE unad88id=1';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$fila = $objDB->sf($tabla);
			if ($fila['unad88appsestado'] == 9) {
				$bInternos = false;
			}
		} else {
			$bInternos = false;
		}
	}
	if ($bInternos) {
		//Acceso a los modulos en los que tiene permiso.
		$sPerfiles = '-99';
		$sSQL = 'SELECT unad07idperfil FROM unad07usuarios WHERE unad07idtercero=' . $idTercero . ' AND unad07vigente="S"';
		$tabla = $objDB->ejecutasql($sSQL);
		while ($fila = $objDB->sf($tabla)) {
			$sPerfiles = $sPerfiles . ',' . $fila['unad07idperfil'];
		}
		$sSistema = '-99';
		//, unad01orden
		if ($sPerfiles != '-99') {
			$sSQL = 'SELECT T1.unad02idsistema, TS.unad01orden 
			FROM unad06perfilmodpermiso AS TB, unad02modulos AS T1, unad01sistema AS TS 
			WHERE TB.unad06idperfil IN (' . $sPerfiles . ') AND TB.unad06idpermiso=1 AND TB.unad06vigente="S" 
			AND TB.unad06idmodulo=T1.unad02id AND T1.unad02idsistema NOT IN (99)
			AND T1.unad02idsistema=TS.unad01id 
			GROUP BY T1.unad02idsistema, TS.unad01orden';
			$tabla = $objDB->ejecutasql($sSQL);
			while ($fila = $objDB->sf($tabla)) {
				if ($fila['unad01orden'] < 70) {
					$bConModulos = true;
				} else {
					if ($fila['unad01orden'] < 100) {
						$bConERP = true;
					} else {
						$bConGestion = true;
					}
				}
				$sSistema = $sSistema . ',' . $fila['unad02idsistema'];
			}
		}
		$sRutaBase = 'https://aurea2.unad.edu.co/';
		switch ($idEntidad) {
			case 0: // Colombia
				break;
			default:
				$sRutaBase = './';
				break;
		}
		$sSalto = ' target="_blank"';
		if ($bConModulos) {
			//Vemos los temas asociados a modulos academicos y eventos.
			$sSQL = 'SELECT unad01nombre, unad01descripcion, unad01ruta 
			FROM unad01sistema 
			WHERE unad01id IN (' . $sSistema . ') AND unad01publico="S" AND unad01instalado="S" AND unad01orden<70 
			ORDER BY unad01orden, unad01nombre';
			$tabla = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla) > 0) {
				$sHTML = $sHTML . '</li><li class="options__item">';
				$sHTML = $sHTML . '<a class="option">' . $et_modulos . '<i class="iNavigateNext"></i></a>';
				$sHTML = $sHTML . $sInicioBloque;
				while ($fila = $objDB->sf($tabla)) {
					$sRutaDef = $sRutaBase . cadena_Reemplazar($fila['unad01ruta'], '../', '');
					$sHTML = $sHTML . '<li><a href="' . $sRutaDef . '" title="' . cadena_notildes($fila['unad01descripcion']) . '" target="_blank">' . strtoupper($fila['unad01nombre']) . '</a></li>';
				}
				$sHTML = $sHTML . $sFinBloque;
			}
		}
		if ($bConERP) {
			//ERP
			$sSQL = 'SELECT unad01nombre, unad01descripcion, unad01ruta 
			FROM unad01sistema 
			WHERE unad01id IN (' . $sSistema . ') AND unad01publico="S" AND unad01instalado="S" AND unad01orden>69 AND unad01orden<100  
			ORDER BY unad01orden, unad01nombre';
			$tabla = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla) > 0) {
				$sHTML = $sHTML . '</li><li class="options__item">';
				$sHTML = $sHTML . '<a class="option">' . $et_erp . '<i class="iNavigateNext"></i></a>';
				$sHTML = $sHTML . $sInicioBloque;
				while ($fila = $objDB->sf($tabla)) {
					$sRutaDef = $sRutaBase . cadena_Reemplazar($fila['unad01ruta'], '../', '');
					$sHTML = $sHTML . '<li><a href="' . $sRutaDef . '" title="' . cadena_notildes($fila['unad01descripcion']) . '" target="_blank">' . strtoupper($fila['unad01nombre']) . '</a></li>';
				}
				$sHTML = $sHTML . $sFinBloque;
			}
		}
		if ($bConGestion) {
			//Modulos de gestion.
			$sSQL = 'SELECT unad01nombre, unad01descripcion, unad01ruta 
			FROM unad01sistema 
			WHERE unad01id IN (' . $sSistema . ') AND unad01publico="S" AND unad01instalado="S" AND unad01orden>99  
			ORDER BY unad01orden, unad01nombre';
			$tabla = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tabla) > 0) {
				$sHTML = $sHTML . '</li><li class="options__item">';
				$sHTML = $sHTML . '<a class="option">' . $et_gestion . '<i class="iNavigateNext"></i></a>';
				$sHTML = $sHTML . $sInicioBloque;
				while ($fila = $objDB->sf($tabla)) {
					$sRutaDef = $sRutaBase . cadena_Reemplazar($fila['unad01ruta'], '../', '');
					$sHTML = $sHTML . '<li><a href="' . $sRutaDef . '" title="' . cadena_notildes($fila['unad01descripcion']) . '" target="_blank">' . strtoupper($fila['unad01nombre']) . '</a></li>';
				}
				$sHTML = $sHTML . $sFinBloque;
			}
		}
	}
	//Termina las funciones administrativas
	$sHTML = $sHTML . '</li>';
	return array($sHTML, $sDebug);
}
// --- Menu de proveedores que es para el portal de proveedores
function html_MenuProveedores($objDB, $iPiel = 2, $bDebug = false, $idTercero = 0, $bSalto = false)
{
	require './app.php';
	$sDebug = '';
	$idEntidad = Traer_Entidad($APP);
	$_SESSION['u_ultimominuto'] = iminutoavance();
	if ($idTercero == 0) {
		$idTercero = $_SESSION['unad_id_tercero'];
	}
	$sDebug = sesion_actualizar_v2($objDB, $bDebug);
	$et_ini = 'Inicio';
	$et_panel = 'Panel';
	$et_miperfil = 'Mi perfil';
	$et_clave = 'Contrase&ntilde;a';
	$et_contacto = 'Datos de contacto';
	$et_inisesion = 'Iniciar Sesi&oacute;n';
	$et_proveedor = 'Proveedor';
	$et_regproveeedor = 'Registro de proveedor';
	$et_cotizaciones = 'Cotizaciones';
	switch ($_SESSION['unad_idioma']) {
		case 'en':
			$et_ini = 'Home';
			$et_panel = 'Panel';
			$et_dp = 'Personal Information';
			$et_pwd = 'Password';
			$et_inisesion = 'Login';
			$et_ayuda = 'Help';
			$et_acerca = 'About...';
			$et_miperfil = 'My profile';
			$et_salir = 'Exit';
			break;
		case 'pt':
			$et_ini = 'Home';
			$et_panel = 'Painel';
			$et_chat = 'Bate Papo';
			$et_dp = 'Dados Pessoais';
			$et_pwd = 'Sehna';
			$et_inisesion = 'Login';
			$et_ayuda = 'Ajuda';
			$et_acerca = 'Sobre...';
			$et_miperfil = 'Meu perfil';
			$et_salir = 'Sair';
			break;
	}
	$sHTML = '';
	$sClaseLinkBase = '';
	$sClaseLinkItem = '';
	$sClaseLiBase = '';
	$sClaseLiItem = '';
	$sInicioBloque = '<div class="option-drop"><ul>';
	$sFinBloque = '</ul></div>';
	$sInicioItem = '';
	$sFinItem = '';
	$sHTML = $sHTML . '<li class="options__item">';
	if (($idTercero != 0)) {
		$sHTML = $sHTML . '<a class="option">' . $et_ini . '<i class="iNavigateNext"></i></a>';
		$sHTML = $sHTML . $sInicioBloque;
		$sHTML = $sHTML . '<li><a href="./">' . $et_panel . '</a></li>';
		$sHTML = $sHTML . '<li><a href="miperfil.php">' . $et_miperfil . '</a></li>';
		$sHTML = $sHTML . '<li><a href="contrasegna.php">' . $et_clave . '</a></li>';
		$sHTML = $sHTML . '<li><a href="contacto.php">' . $et_contacto . '</a></li>';
		$sHTML = $sHTML . $sFinBloque;
		$sHTML = $sHTML . '</li><li class="options__item">';
		$sHTML = $sHTML . '<a class="option">' . $et_proveedor . '<i class="iNavigateNext"></i></a>';
		$sHTML = $sHTML . $sInicioBloque;
		$sHTML = $sHTML . '<li><a href="proveedor.php" title="' . $et_regproveeedor . '">' . $et_regproveeedor . '</a></li>';
		$sHTML = $sHTML . '<li><a href="cotizacion.php" title="' . $et_cotizaciones . '">' . $et_cotizaciones . '</a></li>';
		$sHTML = $sHTML . $sFinBloque;
	} else {
		//no hay tercero
		$sRutaLogin = '../';
		if ($idEntidad == 1) {
			$sRutaLogin = 'https://aurea.unad.us/campus/';
		}
		$sHTML = $sHTML . '<a class="option" href="' . $sRutaLogin . '">' . $et_inisesion . '<i class="iNavigateNext"></i></a>';
	}
	//Acceso a los modulos en los que tiene permiso.
	$sHTML = $sHTML . '</li>';
	return array($sHTML, $sDebug);
}
// --- Termina el menu de proveedores
function html_notaV3($nota, $bocultacero = true, $iVrAprueba = 3, $iVrMaximo = 5, $iDecimales = 1)
{
	$res = '';
	if ($nota <= 0) {
		if (!$bocultacero) {
			$res = '<font class="rojo">' . formato_numero(0, $iDecimales) . '</font>';
		}
	} else {
		$sMuestra = formato_numero($nota, $iDecimales);
		if ($nota < $iVrAprueba) {
			$res = '<font class="rojo">' . $sMuestra . '</font>';
		} else {
			if ($nota <= $iVrMaximo) {
				$res = '<font class="verde">' . $sMuestra . '</font>';
			}
		}
	}
	return $res;
}
function html_salto()
{
	return '<div class="salto1px"></div>';
}
// Menus..
function html_MenuGrupo2023($grupo, $idsistema, $iPiel, $objDB, $completo = true, $bDebug = false, $idTercero = 0)
{
	$bentra = false;
	$sDebug = '';
	$sadd = '';
	$res = '';
	if (($idsistema == 10) && ($grupo == 99)) {
		$sadd = '99,';
	}
	if ($idTercero == 0) {
		$idTercero = $_SESSION['unad_id_tercero'];
	}
	$sId09 = '-99';
	$sSQL = 'SELECT T6.unad06idmodulo 
	FROM unad07usuarios as T7, unad06perfilmodpermiso AS T6, unad09modulomenu AS T9, unad02modulos AS T2 
	WHERE T7.unad07idtercero=' . $idTercero . ' AND T7.unad07vigente="S" 
	AND T7.unad07idperfil=T6.unad06idperfil 
	AND T6.unad06vigente="S" AND T6.unad06idpermiso=1 
	AND T6.unad06idmodulo=T9.unad09idmodulo AND T9.unad09grupo=' . $grupo . ' 
	AND T6.unad06idmodulo=T2.unad02id AND T2.unad02idsistema IN (0,' . $sadd . $idsistema . ') 
	GROUP BY T6.unad06idmodulo ';
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' Consulta id modulos grupo ' . $grupo . ' App ' . $idsistema . ' ' . $sSQL . '<br>';
	}
	$resultm = $objDB->ejecutasql($sSQL);
	while ($filam = $objDB->sf($resultm)) {
		$sId09 = $sId09 . ',' . $filam['unad06idmodulo'];
	}
	$sSQL = 'SELECT T9.unad09nombre, T9.unad09pagina, T9.unad09nombre_en, T9.unad09nombre_pt 
	FROM unad09modulomenu AS T9 
	WHERE T9.unad09idmodulo IN (' . $sId09 . ') 
	ORDER BY T9.unad09orden';
	if ($bDebug) {
		$sDebug = $sDebug . fecha_microtiempo() . ' Modulos a mostrar grupo ' . $grupo . ' App ' . $idsistema . ' ' . $sSQL . '<br>';
	}
	$resultm = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($resultm) > 0) {
		$bentra = true;
	}
	if ($bentra) {
		$slin1 = '';
		$slin2 = '';
		$sInicioBloque = '';
		$sFinBloque = '';
		if ($completo) {
			$res = $res . $sInicioBloque;
		}
		while ($filamenu = $objDB->sf($resultm)) {
			if (trim($filamenu['unad09pagina']) != '') {
				if ($filamenu['unad09nombre'] == '-') {
					//$res = $res . $slin1 . '<hr>' . $slin2;
					$res = $res . '<li>---</li>';
				} else {
					$eti = cadena_notildes($filamenu['unad09nombre']);
					switch ($_SESSION['unad_idioma']) {
						case 'en':
							if (trim($filamenu['unad09nombre_en']) != '') {
								$eti = $filamenu['unad09nombre_en'];
							}
							break;
						case 'pt':
							if (trim($filamenu['unad09nombre_pt']) != '') {
								$eti = cadena_notildes($filamenu['unad09nombre_pt']);
							}
							break;
					}
					$res = $res . '<li><a href="' . trim($filamenu['unad09pagina']) . '">' . $eti . '</a></li>';
				}
			}
		}
		if ($completo) {
			$res = $res . $sFinBloque;
		}
	}
	return array($res, $sDebug);
}
function html_Menu2023($idsistema, $objDB, $iPiel = 2, $bDebug = false, $idTercero = 0)
{
	require './app.php';
	$sDebug = '';
	$idEntidad = Traer_Entidad($APP);
	$_SESSION['u_ultimominuto'] = iminutoavance();
	if ($idTercero == 0) {
		$idTercero = $_SESSION['unad_id_tercero'];
	}
	$sDebug = sesion_actualizar_v2($objDB, $bDebug);
	$et_ini = 'Inicio';
	$et_entorno = 'Entorno de trabajo';
	$et_panel = 'Panel';
	$et_campus = 'Campus Virtual';
	$et_chat = 'Chat';
	$et_dp = 'Datos Personales';
	$et_pwd = 'Contrase&ntilde;a';
	$et_inisesion = 'Iniciar Sesi&oacute;n';
	$et_ayuda = 'Ayuda';
	$et_acerca = 'Acerca de...';
	$et_erp = 'SIGAF';
	$et_gestion = 'Gesti&oacute;n';
	$et_manuales = 'Manuales';
	$et_miperfil = 'Mi perfil';
	// (M&oacute;dulos)
	$et_modulos = 'Acad&eacute;mico';
	$et_salir = 'Salir';
	switch ($_SESSION['unad_idioma']) {
		case 'en':
			$et_ini = 'Home';
			$et_panel = 'Panel';
			$et_dp = 'Personal Information';
			$et_pwd = 'Password';
			$et_inisesion = 'Login';
			$et_ayuda = 'Help';
			$et_acerca = 'About...';
			$et_miperfil = 'My profile';
			$et_salir = 'Exit';
			break;
		case 'pt':
			$et_ini = 'Home';
			$et_panel = 'Painel';
			$et_chat = 'Bate Papo';
			$et_dp = 'Dados Pessoais';
			$et_pwd = 'Sehna';
			$et_inisesion = 'Login';
			$et_ayuda = 'Ajuda';
			$et_acerca = 'Sobre...';
			$et_miperfil = 'Meu perfil';
			$et_salir = 'Sair';
			break;
	}
	$sHTML = '';
	$sClaseLinkBase = '';
	$sClaseLinkItem = '';
	$sClaseLiBase = '';
	$sClaseLiItem = '';
	$sInicioBloque = '<div class="option-drop"><ul>';
	$sFinBloque = '</ul></div>';
	$sInicioItem = '';
	$sFinItem = '';
	$sHTML = $sHTML . '<li class="options__item">';
	if (($idTercero != 0)) {
		$sHTML = $sHTML . '<a class="option" href="index.php">' . $et_ini . '<i class="iNavigateNext"></i></a>';
		$sHTML = $sHTML . $sInicioBloque;
		$bEntraGrupoCero = false;
		if ($idsistema > 0) {
			if ($idsistema != 51) {
				$bEntraGrupoCero = true;
			}
		}
		if ($bEntraGrupoCero) {
			$sgrupo = '';
			list($sgrupo, $sDebugG) = html_MenuGrupo2023(0, $idsistema, $iPiel, $objDB, false, $bDebug, $idTercero);
			$sDebug = $sDebug . $sDebugG;
			$sHTML = $sHTML . $sgrupo;
		}
		$sHTML = $sHTML . '<li><a href="miperfil.php">' . $et_miperfil . '</a></li>';
		$sHTML = $sHTML . '<li><a href="unadentorno.php">' . $et_entorno . '</a></li>';
		$sHTML = $sHTML . $sFinBloque;
		//traer los encabezados que estan disponible para ese sistema.
		$sIds = '-99';
		$sSQL = 'SELECT T9.unad09grupo 
		FROM unad07usuarios AS T7, unad06perfilmodpermiso AS T6, unad02modulos AS T2, unad09modulomenu AS T9 
		WHERE T7.unad07idtercero=' . $idTercero . ' AND T7.unad07vigente="S" 
		AND T7.unad07idperfil=T6.unad06idperfil AND T6.unad06idpermiso=1 AND T6.unad06vigente="S" 
		AND T6.unad06idmodulo=T2.unad02id AND T2.unad02idsistema IN (0,' . $idsistema . ') 
		AND T6.unad06idmodulo=T9.unad09idmodulo AND T9.unad09grupo>0 AND T9.unad09grupo NOT IN (99) 
		GROUP BY T9.unad09grupo ';
		if ($bDebug) {
			$sDebug = $sDebug . fecha_microtiempo() . ' Grupos del menu: ' . $sSQL . '<br>';
		}
		$tabla = $objDB->ejecutasql($sSQL);
		while ($fila = $objDB->sf($tabla)) {
			$sIds = $sIds . ',' . $fila['unad09grupo'];
		}
		if ($sIds != '-99') {
			$sSQL = 'SELECT unad08nombre, unad08pagina, unad08id, unad08nombre_en, unad08nombre_pt
			FROM unad08grupomenu 
			WHERE unad08id IN (' . $sIds . ')
			ORDER BY unad08id';
			$result = $objDB->ejecutasql($sSQL);
			if ($result == false) {
				echo '...<!-- ' . $sSQL . '<br>' . $objDB->serror . ' -->';
			}
			while ($row = $objDB->sf($result)) {
				if (trim($row['unad08pagina']) != '') {
					$eti = cadena_notildes($row['unad08nombre']);
					switch ($_SESSION['unad_idioma']) {
						case 'en':
							if (trim($row['unad08nombre_en']) != '') {
								$eti = $row['unad08nombre_en'];
							}
							break;
						case 'pt':
							if (trim($row['unad08nombre_pt']) != '') {
								$eti = cadena_notildes($row['unad08nombre_pt']);
							}
							break;
					}
					$sHTML = $sHTML . '</li><li class="options__item">';
					$sHTML = $sHTML . '<a class="option" href="' . trim($row['unad08pagina']) . '">' . $eti . '<i class="iNavigateNext"></i></a>';
					list($sHTMLgrupo, $sDebugG) = html_MenuGrupo2023($row['unad08id'], $idsistema, $iPiel, $objDB, true, $bDebug, $idTercero);
					$sDebug = $sDebug . $sDebugG;
					if ($sHTMLgrupo != '') {
						$sHTML = $sHTML . $sInicioBloque . $sHTMLgrupo . $sFinBloque;
					}
				}
			}
		}
	} else {
		//no hay tercero
		$sRutaLogin = 'https://campus.unad.edu.co';
		$sRutaLocal = '../login/';
		switch ($idEntidad) {
			case 1:
				$sRutaLocal = 'https://unad.us/campus/';
				break;
			case 2:
				$sRutaLocal = 'https://campus.unad-ue.es/';
				break;
		}
		$sHTML = $sHTML . '<a class="option" href="./">' . $et_ini . '<i class="iNavigateNext"></i></a>';
		$sHTML = $sHTML . $sInicioBloque;
		if ($idEntidad == 0) {
			$sHTML = $sHTML . '<li><a href="' . $sRutaLogin . '">' . $et_campus . '</a></li>';
		}
		$sHTML = $sHTML . '<li><a href="' . $sRutaLocal . '">' . $et_inisesion . '</a></li>';
		$sHTML = $sHTML . $sFinBloque;
	}
	//Acceso a los modulos en los que tiene permiso.
	$bConModulos = false;
	$bConERP = false;
	$bConGestion = false;
	$sPerfiles = '-99';
	$sSQL = 'SELECT unad07idperfil FROM unad07usuarios WHERE unad07idtercero=' . $idTercero . ' AND unad07vigente="S"';
	$tabla = $objDB->ejecutasql($sSQL);
	while ($fila = $objDB->sf($tabla)) {
		$sPerfiles = $sPerfiles . ',' . $fila['unad07idperfil'];
	}
	$sSistema = '-99';
	//, unad01orden
	$sSQL = 'SELECT T1.unad02idsistema, TS.unad01orden 
	FROM unad06perfilmodpermiso AS TB, unad02modulos AS T1, unad01sistema AS TS 
	WHERE TB.unad06idperfil IN (' . $sPerfiles . ') AND TB.unad06idpermiso=1 AND TB.unad06vigente="S" AND TB.unad06idmodulo=T1.unad02id AND T1.unad02idsistema NOT IN (99, ' . $idsistema . ')
	AND T1.unad02idsistema=TS.unad01id 
	GROUP BY T1.unad02idsistema, TS.unad01orden';
	$tabla = $objDB->ejecutasql($sSQL);
	while ($fila = $objDB->sf($tabla)) {
		if ($fila['unad01orden'] < 70) {
			$bConModulos = true;
		} else {
			if ($fila['unad01orden'] < 100) {
				$bConERP = true;
			} else {
				$bConGestion = true;
			}
		}
		$sSistema = $sSistema . ',' . $fila['unad02idsistema'];
	}
	if ($bConModulos) {
		//Vemos los temas asociados a modulos academicos y eventos.
		$sSQL = 'SELECT unad01nombre, unad01descripcion, unad01ruta 
		FROM unad01sistema 
		WHERE unad01id IN (' . $sSistema . ') AND unad01publico="S" AND unad01instalado="S" AND unad01orden<70 
		ORDER BY unad01orden, unad01nombre';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$sHTML = $sHTML . '</li><li class="options__item">';
			$sHTML = $sHTML . '<a class="option">' . $et_modulos . '<i class="iNavigateNext"></i></a>';
			$sHTML = $sHTML . $sInicioBloque;
			while ($fila = $objDB->sf($tabla)) {
				$sHTML = $sHTML . '<li><a href="' . $fila['unad01ruta'] . '" title="' . cadena_notildes($fila['unad01descripcion']) . '" target="_blank">' . strtoupper($fila['unad01nombre']) . '</a></li>';
			}
			$sHTML = $sHTML . $sFinBloque;
		}
	}
	if ($bConERP) {
		//ERP
		$sSQL = 'SELECT unad01nombre, unad01descripcion, unad01ruta 
		FROM unad01sistema 
		WHERE unad01id IN (' . $sSistema . ') AND unad01publico="S" AND unad01instalado="S" AND unad01orden>69 AND unad01orden<100  
		ORDER BY unad01orden, unad01nombre';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$bConERP = true;
			$sHTML = $sHTML . '</li><li class="options__item">';
			$sHTML = $sHTML . '<a class="option">' . $et_erp . '<i class="iNavigateNext"></i></a>';
			$sHTML = $sHTML . $sInicioBloque;
			while ($fila = $objDB->sf($tabla)) {
				$sHTML = $sHTML . '<li><a href="' . $fila['unad01ruta'] . '" title="' . cadena_notildes($fila['unad01descripcion']) . '" target="_blank">' . strtoupper($fila['unad01nombre']) . '</a></li>';
			}
			$sHTML = $sHTML . $sFinBloque;
		}
	}
	if ($bConGestion) {
		//Modulos de gestion.
		$sSQL = 'SELECT unad01nombre, unad01descripcion, unad01ruta 
		FROM unad01sistema 
		WHERE unad01id IN (' . $sSistema . ') AND unad01publico="S" AND unad01instalado="S" AND unad01orden>99  
		ORDER BY unad01orden, unad01nombre';
		$tabla = $objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla) > 0) {
			$bConGestion = true;
			$sHTML = $sHTML . '</li><li class="options__item">';
			$sHTML = $sHTML . '<a class="option">' . $et_gestion . '<i class="iNavigateNext"></i></a>';
			$sHTML = $sHTML . $sInicioBloque;
			while ($fila = $objDB->sf($tabla)) {
				$sHTML = $sHTML . '<li><a href="' . $fila['unad01ruta'] . '" title="' . cadena_notildes($fila['unad01descripcion']) . '" target="_blank">' . strtoupper($fila['unad01nombre']) . '</a></li>';
			}
			$sHTML = $sHTML . $sFinBloque;
		}
	}
	$sHTML = $sHTML . '</li>';
	return array($sHTML, $sDebug);
}

function f101_PanelSistema($idSistema, $objDB, $bDebug = false)
{
	$sClase1 = '';
	$sRes = '';
	$sDebug = '';
	$bHayContenido = false;
	$unad01correosoporte = '';
	$unad01correocapacita = '';
	$iNumProcesos = 0;
	$sSQL = 'SELECT unad01correosoporte, unad01correocapacita FROM unad01sistema WHERE unad01id=' . $idSistema . '';
	$tabla = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla) > 0) {
		$fila = $objDB->sf($tabla);
		$unad01correosoporte = $fila['unad01correosoporte'];
		$unad01correocapacita = $fila['unad01correocapacita'];
		if ($unad01correosoporte != '') {
			$bHayContenido = true;
		} else {
			if ($unad01correocapacita != '') {
				$bHayContenido = true;
			}
		}
		// Ahora alistamos los procesos
		$sSQL = 'SELECT aurf52titulo, aurf52descripcion, aurf52linkmanual 
		FROM aurf52proceso 
		WHERE aurf52idsistema=' . $idSistema . ' AND aurf52publicado=1
		ORDER BY aurf52orden';
		$tabla52 = $objDB->ejecutasql($sSQL);
		$iNumProcesos = $objDB->nf($tabla52);
		if ($iNumProcesos > 0) {
			$bHayContenido = true;
		}
	}
	if ($bHayContenido) {
		$sClase1 = ' class="section-aside"';
		$sAyudas = '<a href="./unadayudas.php" class="module-contact" title="Ayudas y manuales"><p>Ayudas y Manuales</p></a>';
		$sResM1 = '';
		$sResM2 = '';
		if ($unad01correosoporte != '') {
			$sResM1 = '<a href="mailto:' . $unad01correosoporte . '" class="module-contact" title="Enviar correo">';
			$sResM1 = $sResM1 . '<p>Para soporte:</p><b>' . $unad01correosoporte . '</b></a>';
		}
		if ($unad01correocapacita != '') {
			$sResM2 = '<a href="mailto:' . $unad01correocapacita . '" class="module-contact" title="Enviar correo">';
			$sResM2 = $sResM2 . '<p>Para capacitaciones:</p><b>' . $unad01correocapacita . '</b></a>';
		}
		$sProcesos = '';
		if ($iNumProcesos > 0) {
			$sProcesos = '<div class="module-processes">';
			$sProcesos = $sProcesos . '<b>Procesos que encontrar&aacute; en este m&oacute;dulo:</b>';
			$sProcesos = $sProcesos . '<div>';
			while ($fila = $objDB->sf($tabla52)) {
				$sProcesos = $sProcesos . '<details>';
				$sProcesos = $sProcesos . '<summary>' . cadena_notildes($fila['aurf52titulo']) . '</summary>';
				$sProcesos = $sProcesos . '<div>';
				$sProcesos = $sProcesos . '' . cadena_notildes($fila['aurf52descripcion']) . '';
				if ($fila['aurf52linkmanual'] != '') {
					$sProcesos = $sProcesos . html_salto() . 'Para mayores detalles, por favor consulte el siguiente <a href="./verarchivo.php?u=' . $fila['aurf52linkmanual'] . '" target="_blank" class="lnkresalto" title="Manual asociado al proceso">manual</a>';
				}
				$sProcesos = $sProcesos . '</div>';
				$sProcesos = $sProcesos . '</details>';
			}
			$sProcesos = $sProcesos . '</div></div>';
		}
		$sRes = '<aside>';
		$sRes = $sRes . '<div class="header-aside">';
		$sRes = $sRes . '<i class="iInfo"></i>';
		$sRes = $sRes . '<b>Importante</b>';
		$sRes = $sRes . '</div>';
		$sRes = $sRes . '<div class="body-aside">' . $sAyudas . $sResM1 . $sResM2 . $sProcesos . '</div>';
		$sRes = $sRes . '</aside>';
	}
	return array($sClase1, $sRes, $sDebug);
}

// STAGE - ETAPAS
function html_EtapaInicio($sTitulo, $iEstado = 0, $bPrimero = false, $sClase = '')
{
	$sEstadoEtapa = '';
	$sEstadoAcordeon = '';
	switch ($iEstado) {
		case '0':
			$sEstadoEtapa = ' available';
			break;
		case '1':
			$sEstadoEtapa = ' completed';
			break;
		default:
			$sEstadoAcordeon = ' closed';
			break;
	}
	$sRes = '';
	if ($bPrimero) {
		$sClaseProyecto = '';
		switch ($sClase) {
			case '':
				break;
			default:
				$sClaseProyecto = ' stages-3';
				break;
		}
		$sRes = '<div class="stages-project' . $sClaseProyecto . '">';
	}
	$sRes = $sRes . '<div class="stage' . $sEstadoEtapa . '">
<details class="accordion"' . $sEstadoAcordeon . '>
<summary class="accordion-title">' . $sTitulo . '</summary>
<div class="accordion-content">';
	return $sRes;
}
function html_EtapaItem($sTitulo, $sCuerpo, $sClase)
{
	$sClaseItem = 'cola';
	switch ($sClase) {
		case 'disponible':
		case 'proceso':
		case 'devuelta':
		case 'radicada':
		case 'completo':
		case 'descartada':
			$sClaseItem = $sClase;
			break;
		case 1: // Devuelto
			$sClaseItem = 'devuelta';
			break;
		case 3: // Radicado
			$sClaseItem = 'radicada';
			break;
		case 7: // Aprobado
			$sClaseItem = 'completo';
			break;
	}
	$sRes = '<div class="item ' . $sClaseItem . '">';
	$sRes = $sRes . '<div class="item-body">';
	if ($sTitulo != '') {
	}
	if (true) {
		$sRes = $sRes . '<b class="item-title">' . $sTitulo . '</b>';
	} else {
		$sRes = $sRes . '<b class="item-title" style="display:none;"></b>';
	}
	$sRes = $sRes . '<div class="item-content">' . $sCuerpo . '</div>';
	$sRes = $sRes . '</div>';
	$sRes = $sRes . '</div>';
	return $sRes;
}
function html_EtapaFinal($bUltimo = false)
{
	$sRes = '</div></details></div>';
	if ($bUltimo) {
		$sRes = $sRes . '</div>';
	}
	return $sRes;
}

function html_GrupoBotones($iCodigo, $iClase, $iSel = 0)
{
	$sClase = '';
	if ($iSel == 1) {
		$sClase = ' class="button-checked"';
	}
	$sRes = '<div><button id="button-' . $iClase . '" onclick="javascript:marca_boton(' . $iCodigo . ')"' . $sClase . '>' . $iCodigo . '</button></div>';
	return $sRes;
}

function html_Tercero1l($id, $objDB, $bHtml = false)
{
	$sHTML = '';
	$sCondi = '';
	$sSQL = 'SELECT unad11razonsocial, unad11direccion, unad11telefono, unad11id, unad11tipodoc, unad11doc, unad11bloqueado 
	FROM unad11terceros 
	WHERE unad11id=' . $id . '';
	$tablater = $objDB->ejecutasql($sSQL);
	if ($objDB->nf($tablater) > 0) {
		$filater = $objDB->sf($tablater);
		$sPrev = '';
		$sPost = '';
		if ($bHtml) {
			$sPrev = '<b>';
			$sPost = '</b>';
		}
		$sHTML = $sPrev . $filater['unad11tipodoc'] . $filater['unad11doc'] . ' ' . cadena_notildes($filater['unad11razonsocial']) . $sPost;
		/*
		if ($filater['unad11bloqueado'] == 'S') {
			//Esta bloqueado informar la causal..
			$bSimple = true;
			$sSQL = 'SELECT sys75fechabloqueo, sys75motivo FROM sys75tercerobloqueo WHERE sys75idtercero=' . $id . ' AND sys75fechalibera="00/00/0000" ORDER BY sys75consec DESC';
			$tablabloq = $objDB->ejecutasql($sSQL);
			if ($objDB->nf($tablabloq) > 0) {
				$filabloq = $objDB->sf($tablabloq);
				$sHTML = $sHTML . '<br><span class="rojo"><b>Bloqueado desde ' . $filabloq['sys75fechabloqueo'] . '</b>: ' . cadena_notildes($filabloq['sys75motivo']) . '</span>';
			}
		}
		*/
	}
	return $sHTML;
}
function htmlAlertas($sColor, $sTexto)
{
	$sHTML = '';
	$sTipo = '';
	switch ($sColor) {
		case 'verde':
			$sTipo = 'success';
			break;
		case 'naranja':
			$sTipo = 'warning';
			break;
		case 'rojo':
			$sTipo = 'danger';
			break;
		default:
			$sTipo = 'info';
	}
	$sHTML = $sHTML . '<div class="alert alert-' . $sTipo . '" role="alert"><strong>' . $sTexto . '</strong></div>';
	return $sHTML;
}

function html_Alerta($sTexto, $sColor = '', $bConIcono = true)
{
	$sRes = '';
	$sTipo = '';
	$sIcono = '';
	switch ($sColor) {
		case 'verde':
			$sTipo = 'success';
			$sIcono = '';
			break;
		case 'naranja':
			$sTipo = 'warning';
			$sIcono = '';
			break;
		case 'rojo':
			$sTipo = 'danger';
			$sIcono = '';
			break;
		default:
			$sTipo = 'info';
			$sIcono = 'icon-info';
	}
	$sRes = '<div class="alert alert-' . $sTipo . '">';
	if ($bConIcono) {
		$sRes = $sRes . '<i class="' . $sIcono . '"></i>';
	}
	$sRes = $sRes . '<p>' . $sTexto . '</p>';
	$sRes = $sRes . '</div>';
	return $sRes;
}

