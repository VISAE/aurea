<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2014 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
*/
//error_reporting(E_ALL);
//ini_set("display_errors", 1);
require_once '../config.php';
require 'app.php';
require $APP->rutacomun.'unad_todas.php';
require $APP->rutacomun.'libs/clsdbadmin.php';
require $APP->rutacomun.'unad_librerias.php';
require $APP->rutacomun.'fpdf/fpdf.php';
function pdfReporte($iReporte, $PARAMS, $iFormato, $objdb){
	class clsPDF extends FPDF{
		var $iFormato=0;
		var $iAnchoLibre=186;
		var $iAnchoTotal=216;
		var $iReporte=0;
		var $iFirmaReporte='http://www.unad.edu.co';
		var $filaent=NULL;
		var $filaentorno=NULL;
		var $sError;
		//var $smes=array('','Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre');
		var $xPrevia=0;
		var $yPrevia=0;
		//se Crean porque no permite en modo seguro tenerlas en forma implicita
		var $HREF='';
		var $I='';
		var $U='';
		function AddError($sdetalle){
			if ($this->sError!=''){$this->sError=$this->sError.'<br>';}
			$this->sError=$this->sError.$sdetalle;
			}
		function AddIndice($sTitulo, $iPagina, $iNivel){
			$this->indCantidad++;
			$sNumeracion='';
			if ($this->bNumerarTitulos){
				switch($iNivel){
					case 1:
					$this->iNumTitulo1++;
					$this->iNumTitulo2=0;
					$this->iNumTitulo3=0;
					$sNumeracion=$this->iNumTitulo1.$this->sNumSepara;
					break;
					case 2:
					$this->iNumTitulo2++;
					$this->iNumTitulo3=0;
					$sNumeracion=$this->iNumTitulo1.$this->sNumMarca.$this->iNumTitulo2.$this->sNumSepara;
					break;
					case 3:
					$this->iNumTitulo3++;
					$sNumeracion=$this->iNumTitulo1.$this->sNumMarca.$this->iNumTitulo2.$this->sNumMarca.$this->iNumTitulo3.$this->sNumSepara;
					break;
					}
				}
			$this->indTitulo[$this->indCantidad]=$sNumeracion.$sTitulo;
			$this->indPag[$this->indCantidad]=$iPagina;
			$this->indNivel[$this->indCantidad]=$iNivel;
			return $sNumeracion;
			}
		// Intérprete de HTML
		function WriteHTML($html){
			$html = str_replace("\n",' ',$html);
			$a = preg_split('/<(.*)>/U',$html,-1,PREG_SPLIT_DELIM_CAPTURE);
			foreach($a as $i=>$e){
				if($i%2==0){
					// Text
					if($this->HREF){
						$this->PutLink($this->HREF,$e);
						}else{
						$data=str_replace("[[","<",$e);
						$data=str_replace("]]",">",$data);
						$this->Write(5,$data);
						}
					}else{
					// Etiqueta
					if($e[0]=='/'){
						$this->CloseTag(strtoupper(substr($e,1)));
						}else{
						// Extraer atributos
						$a2 = explode(' ',$e);
						$tag = strtoupper(array_shift($a2));
						$attr = array();
						foreach($a2 as $v){
						if(preg_match('/([^=]*)=["\']?([^"\']*)/',$v,$a3))
						$attr[strtoupper($a3[1])] = $a3[2];
						}
					$this->OpenTag($tag,$attr);
					}
				}
			}
		}
		function OpenTag($tag, $attr){
		// Etiqueta de apertura
		if($tag=='B' || $tag=='I' || $tag=='U')
		$this->SetStyle($tag,true);
		if($tag=='A')
		$this->HREF = $attr['HREF'];
		if($tag=='BR')
		$this->Ln(5);
		}
		function CloseTag($tag){
		// Etiqueta de cierre
		if($tag=='B' || $tag=='I' || $tag=='U')
		$this->SetStyle($tag,false);
		if($tag=='A')
		$this->HREF = '';
		}
		function SetStyle($tag, $enable){
		// Modificar estilo y escoger la fuente correspondiente
		$this->$tag += ($enable ? 1 : -1);
		$style = '';
		foreach(array('B', 'I', 'U') as $s){
			if($this->$s>0)
			$style .= $s;
			}
		$this->SetFont('',$style);
		}
		function PutLink($URL, $txt){
		// Escribir un hiper-enlace
		$this->SetTextColor(0,0,255);
		$this->SetStyle('U',true);
		$this->Write(5,$txt,$URL);
		$this->SetStyle('U',false);
		$this->SetTextColor(0);
		}
		//Titulo de la entidad
		function TituloEntidad(){
			$this->SetFont('Arial','B',14);
			$sMensaje=$this->filaent['sys24nombre'];
			$this->Cell($this->iAnchoLibre,5,utf8_decode($sMensaje),0,0,'C');
			$this->Ln();
			if ($this->filaent['sys11doc']!=''){
				$sMensaje=$this->filaent['sys11tipodoc'].' '.$this->filaent['sys11doc'];
				switch ($this->filaent['sys24regimen']){
					case 'COC':
					$sMensaje=$sMensaje.' Régimen Común';
					break;
					case 'COS':
					$sMensaje=$sMensaje.' Régimen Simplificado';
					break;
					}
				$this->Cell($this->iAnchoLibre,5,utf8_decode($sMensaje),0,0,'C');
				$this->Ln();
				$this->SetFont('Arial','B',12);
				$sMensaje=$this->filaent['sys11direccion'].' '.$this->filaent['sys11telefono'];
				$this->Cell($this->iAnchoLibre,5,utf8_decode($sMensaje),0,0,'C');
				$this->Ln();
				}
			//Este es un espacio separador.
			$this->Cell(5,3,'');
			$this->Ln();
			}
		//Encabezado
		function Header(){
			//Aqui va el encabezado
			$iConFondo=0;
			if (file_exists('pcfg.php')){include 'pcfg.php';}
			if (isset($rpt[$this->iReporte]['fondo'])!=0){
				$sRuta=$rpt[$this->iReporte]['fondo'];
				if (file_exists($sRuta)){
					$this->Image($sRuta, 0, 0, $this->iAnchoTotal);
					$iConFondo=1;
					}
				}
			if ($iConFondo==0){
				$this->TituloEntidad();
				}
			if ($this->iReporte==2){
				}
			}
		//Pie de página
		function Footer(){
			switch($this->iFormato){
				case 1:
				$iPagina=$this->PageNo();
				$this->SetY(-8);
				$this->SetFont('Arial','',11);
				if(($iPagina%2)==0){
					//Margen par
					$this->Cell(0,5,''.$this->PageNo().'',0,0,'L');
					//$this->AddPage();
					}else{
					//Margen impar
					$this->Cell(0,5,''.$this->PageNo().'',0,0,'R');
					}
				break;
				default:
				$this->SetRightMargin(5);
				$this->SetY(-8);
				$this->SetFont('Arial','I',8);
				$this->Cell(0,5,'Página '.$this->PageNo().' de {nb}',0,0,'R');
				$this->SetY(-4);
				$this->SetFont('Arial','',7);
				$this->Cell(0,3,$this->iFirmaReporte,0,0,'R');
				$this->SetRightMargin(15);
				}
			}
		function PaginaIndice(){
			if ($this->iFormato==1){
				$iPagina=$this->PageNo();
				if(($iPagina%2)==0){$this->AddPage();}
				}
			$sNumera=$this->AddIndice('Indice', $this->PageNo(), 1);
			$this->SetFont('Arial','B',14);
			$this->Cell($this->iAnchoLibre,5,'I N D I C E',0,0,'C');
			$this->Ln();
			$this->SetFont('Arial','',12);
			for ($k=1;$k<=$this->indCantidad;$k++){
				$iAnchoVineta=1;
				switch($this->indNivel[$k]){
					case 1:
					$this->Ln();
					break;
					case 2:
					$iAnchoVineta=5;
					break;
					case 3:
					$iAnchoVineta=10;
					}
				if ($this->bNumerarTitulos){
					$iAnchoVineta=1;
					}
				//El problema es que el multicel puede saltar a una nueva pagina, por tanto hay que pintarlo primero.
				$pagIni=$this->PageNo();
				$mx=$this->GetX();
				$my=$this->GetY();
				$this->Cell($iAnchoVineta,5,''); //Marco la viñeta.
				$this->MultiCell($this->iAnchoLibre-($iAnchoVineta+10),5,utf8_decode($this->indTitulo[$k]));//,0,0,'C'
				//Si estoy en la misma pagina, Vuelvo al origen.
				if ($pagIni==$this->PageNo()){
					$this->SetXY($mx,$my);
					}else{
					$iTop=20;
					//if ($this->iFormato==1){$iTop=25;}
					$this->SetY($iTop);
					}
				$this->Cell($this->iAnchoLibre-10,5,''); //Corro el carro al final de la linea.
				$this->Cell(10,5,$this->indPag[$k],0,0,'R');
				$this->Ln();
				//$this->SetXY($mx+$iAnchoVineta,$my);
				}
			}
		//Funciones del reporte.
		function ArmarReporte1($PARAMS, $objdb){
			$this->SetTextColor(0,0,0);
			$this->SetFillColor(0,0,0);
			$this->SetDrawColor(0,0,0);
			}
		}
	$objpdf=NULL;
	$sError='';
	if ($objdb==NULL){
		$sError='No se ha definido un origen de datos';
		}
	if ($sError==''){
		//Cargar los parametros previos.
		$filaent=NULL;
		$sql='';
		/*
		$tabla=$objdb->ejecutasql($sql);
		if ($objdb->nf($tabla)>0){
			$filaent=$objdb->sf($tabla);
			}else{
			$sError='No ha sido posible acceder a los datos';
			}
		*/
		}
	// -- Validaciones de los parametros del reporte
	if ($sError==''){
		$filaentorno=NULL;
		switch ($iReporte){
			case 1:
			//if (isset($PARAMS['idtercero'])==0){$PARAMS['idtercero']='';}
			//if ((int)$PARAMS['idtercero']==0){$sError='No se ha ingresado un tercero';}
			break;
			}
		}
	// -- Empezamos la generacion del reporte
	if ($sError==''){
		switch ($iFormato){
			case 1:
			$objpdf=new clsPDF('P','mm',array(170,240));
			$objpdf->SetTopMargin(25);
			$objpdf->SetLeftMargin(20);
			$objpdf->SetRightMargin(20);
			$objpdf->iAnchoLibre=130;
			$objpdf->iAnchoTotal=170;
			break;
			default:
			switch ($iReporte){
				case 999: //Ejemplo de apaisado
				$objpdf=new clsPDF('L','mm','Letter');
				$objpdf->SetTopMargin(10);
				$objpdf->SetLeftMargin(15);
				$objpdf->SetRightMargin(15);
				$objpdf->iAnchoLibre=267;
				$objpdf->iAnchoTotal=297;
				break;
				default:
				$objpdf=new clsPDF('P','mm','Letter');
				$objpdf->SetTopMargin(10);
				$objpdf->SetLeftMargin(15);
				$objpdf->SetRightMargin(15);
				}
			}
		//Iniciar la generacion del reporte
		//if (trim($filaent['sys24firmareportes'])!=''){
		//	$objpdf->iFirmaReporte=trim($filaent['sys24firmareportes']);
		//	}
		$objpdf->AliasNbPages();
		$objpdf->iFormato=$iFormato;
		$objpdf->iReporte=$iReporte;
		$objpdf->filaent=$filaent;
		$objpdf->filaentorno=$filaentorno;
		$objpdf->AddPage();
		switch ($iReporte){
			case 1:
			$objpdf->ArmarReporte1($PARAMS, $objdb);
			break;
			}
		$sError=$objpdf->sError;
		}
	return array($objpdf, $sError);
	}
//Empezar revisando que haya una sesion.
if ($_SESSION['unad_id_tercero']==0){
	echo 'Su sesi&oacute;n ha caducado, por favor vuelva a ingresar al sistema.';
	die();
	}
$sError='';
$iReporte=0;
$bEntra=false;
if (isset($_REQUEST['r'])!=0){$iReporte=numeros_validar($_REQUEST['r']);}
if ((int)$iReporte!=0){$bEntra=true;}
if ($bEntra){
	$iFormato=0;
	if (isset($_REQUEST['f'])!=0){if ($_REQUEST['f']==1){$iFormato=1;}}
	//if (isset($_REQUEST['variable'])==0){$_REQUEST['variable']=0;}
	$objdb=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
	if ($APP->dbpuerto!=''){$objdb->dbPuerto=$APP->dbpuerto;}
	$bEntra=false;
	$sTituloRpt='Reporte';
	if ($iReporte==1){$sTituloRpt='TituloReporte';}
	switch ($iReporte){
		case 1:
		list($pdf, $sError)=pdfReporte($iReporte, $_REQUEST, $iFormato, $objdb);
		$bEntra=true;
		}
	if ($sError==''){if (!$bEntra){$sError='No se ha encontrado el reporte solicitado {'.$iReporte.'}';}}
	if ($sError==''){$sError=$pdf->sError;}
	if ($sError==''){
		$pdf->Output($sTituloRpt.'.pdf','D');
		}else{
		echo $sError;
		}
	}
?>