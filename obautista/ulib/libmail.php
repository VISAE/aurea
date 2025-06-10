<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2016 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.12.0 martes, 19 de enero de 2016
--- Modelo Versión 2.22.4 miércoles, 31 de octubre de 2018
--- la ideas es centralizar todas las funciones de envio de mail en una libreria...
*/

class clsMail_Unad
{
	var $idSMTP = -1;
	var $sOrigen = '';
	var $sNombreOrigen = '';
	var $iAdjuntos = 0;
	var $iDestinatarios = 0;
	var $correos = array();
	var $nombres = array();
	var $tipoenvio = array();
	var $adjuntos = array();
	var $sCuerpo = '';
	var $sAsunto = '';
	var $bHTML = true;
	var $objMAIL = NULL;

	var $objdb;
	var $fila17;
	function addAdjunto($sRuta)
	{
		$this->iAdjuntos++;
		$this->adjuntos[$this->iAdjuntos] = $sRuta;
	}
	function addCorreo($sDirCorreo, $sNombreDestino = '', $sTipoEnvio = '')
	{
		$bPasa = false;
		$sDirCorreo = trim($sDirCorreo);
		if (correo_VerificarDireccion($sDirCorreo)) {
			$this->iDestinatarios++;
			$this->correos[$this->iDestinatarios] = $sDirCorreo;
			$this->nombres[$this->iDestinatarios] = $sNombreDestino;
			switch ($sTipoEnvio) {
				case 'P':
				case 'C':
				case 'O':
				case 'p':
				case 'c':
				case 'o':
					$sTipoEnvio = strtoupper($sTipoEnvio);
					break;
				default:
					$sTipoEnvio = '';
					break;
			}
			if ($sTipoEnvio == '') {
				if ($this->iDestinatarios == 1) {
					$this->tipoenvio[$this->iDestinatarios] = 'P';
				} else {
					$this->tipoenvio[$this->iDestinatarios] = 'C';
				}
			} else {
				$this->tipoenvio[$this->iDestinatarios] = $sTipoEnvio;
			}
			$bPasa = true;
		}
		return $bPasa;
	}
	//Es decir que se lo envia el sistema a la entidad..
	function autoMensaje()
	{
		$sError = '';
		//Cuando nos mandamos una notificacion...
		$this->addCorreo('soporte.campus@unad.edu.co');
		return $sError;
	}
	function Enviar($bDebug = false)
	{
		list($sError, $sDebug) = $this->EnviarV2();
		return $sError;
	}
	function EnviarV2($bDebug = false)
	{
		$sPref = 'unad69';
		$sError = '';
		$sDebug = '';
		if ($this->iDestinatarios == 0) {
			$sError = 'No se han agregado destinatarios.';
		}
		//Ubicar el SMTP de origen.
		if ($sError == '') {
			if ($this->idSMTP == -1) {
				$this->TraerSMTP(1);
				if ($this->idSMTP == -1) {
					$sError = 'No se ha podido determinar un correo para notificaciones.';
				}
			}
		}
		$sNumSmtp = '';
		if ($sError == '') {
			$fila17 = $this->fila17;
			//Enviar el correo.
			if (!class_exists('PHPMailer')) {
				require './app.php';
				require $APP->rutacomun . 'PHPmailer/class.phpmailer.php';
				require $APP->rutacomun . 'PHPmailer/class.smtp.php';
			}
			$sNumSmtp = $fila17[$sPref . 'consec'];
			$mail = new PHPMailer();
			$mail->IsSMTP();
			$mail->Username = $fila17[$sPref . 'usuariomail'];
			$mail->From = $fila17[$sPref . 'usuariomail'];
			$mail->FromName = $fila17[$sPref . 'titulo'];
			if ($bDebug) {
				$sDebug = $sDebug = ' <b>ENVIANDO CORREO</b>: Origen Username:' . $fila17[$sPref . 'usuariomail'] . '<br>';
			}
			if ($fila17[$sPref . 'autenticacion'] != '') {
				$mail->SMTPAuth = true;
				$mail->SMTPSecure = strtolower($fila17[$sPref . 'autenticacion']);
				$sClave = $fila17[$sPref . 'pwdmail'];
				$mail->Password = $sClave;
			} else {
				$mail->SMTPAuth = false;
				if ($this->sOrigen == '') {
				} else {
					$mail->Username = $this->sOrigen;
					$mail->From = $this->sOrigen;
					$mail->FromName = $this->sNombreOrigen;
				}
			}
			$mail->Port = $fila17[$sPref . 'puertomail'];
			$mail->Host = $fila17[$sPref . 'servidorsmtp'];
			$mail->Subject = utf8_decode($this->sAsunto);
			for ($k = 1; $k <= $this->iDestinatarios; $k++) {
				switch ($this->tipoenvio[$k]) {
					case 'C':
						$mail->AddCC($this->correos[$k], $this->nombres[$k]);
						break;
					case 'O':
						$mail->AddBCC($this->correos[$k], $this->nombres[$k]);
						break;
					default:
						$mail->AddAddress($this->correos[$k], $this->nombres[$k]);
						break;
				}
			}
			$mail->WordWrap = 50;
			if ($this->bHTML) {
				$mail->IsHTML(true);
				$mail->Body = '<html><body>' . $this->sCuerpo . '</body></html>';
			} else {
				$mail->Body = $this->sCuerpo;
			}
			$mail->CharSet = 'UTF-8';
			for ($k = 1; $k <= $this->iAdjuntos; $k++) {
				$mail->addAttachment($this->adjuntos[$k]);
			}
			try {
				if ($mail->Send()) {
				} else {
					$sError = 'Error enviando el mensaje de correo, por favor comun&iacute;quese con el administrador del sistema [Cuenta en uso: ' . $sNumSmtp . '].';
					if ($bDebug) {
						$sError = $sError . '<br>Error: ' . $mail->ErrorInfo;
					}
				}
			} catch (Exception $e) {
				$sError = 'Error al intentar enviar el mensaje de correo, por favor comuniquese con el administrador del sistema [SMTP en uso: ' . $sNumSmtp . '].';
				if ($bDebug) {
					$sError = $sError . '<br>Error: ' . $e->getMessage();
				}
			}
			unset($mail);
			//Termina de enviar el correo.
		}
		return array($sError, $sDebug, $sNumSmtp);
	}
	function NuevoMensaje()
	{
		//Alista la libreria para que se vuelva a enviar el mensaje pero a otro destinatario.
		$this->iDestinatarios = 0;
		$this->correos = array();
		$this->nombres = array();
		if (is_null($this->objMAIL)) {
		} else {
			$this->objMAIL->clearAllRecipients();
			$this->objMAIL->clearAttachments();
		}
	}
	function RecurrenteIniciar($bDebug = false)
	{
		$sPref = 'unad69';
		$sError = '';
		//Ubicar el SMTP de origen.
		if ($sError == '') {
			if ($this->idSMTP == -1) {
				$this->TraerSMTP(1);
				if ($this->idSMTP == -1) {
					$sError = 'No se ha podido determinar un correo para notificaciones.';
				}
			}
		}
		if ($sError == '') {
			$fila17 = $this->fila17;
			//Enviar el correo.
			if (!class_exists('PHPMailer')) {
				$sDirBase = __DIR__ . '/';
				require $sDirBase . 'app.php';
				require $APP->rutacomun . 'PHPmailer/class.phpmailer.php';
				require $APP->rutacomun . 'PHPmailer/class.smtp.php';
			}
			$mail = new PHPMailer();
			$mail->IsSMTP();
			$mail->Username = $fila17[$sPref . 'usuariomail'];
			$mail->From = $fila17[$sPref . 'usuariomail'];
			$mail->FromName = $fila17[$sPref . 'titulo'];
			if ($fila17[$sPref . 'autenticacion'] != '') {
				$mail->SMTPAuth = true;
				$mail->SMTPSecure = strtolower($fila17[$sPref . 'autenticacion']);
				$mail->Password = $fila17[$sPref . 'pwdmail'];
			} else {
				$mail->SMTPAuth = false;
				if ($this->sOrigen == '') {
				} else {
					$mail->Username = $this->sOrigen;
					$mail->From = $this->sOrigen;
					$mail->FromName = $this->sNombreOrigen;
				}
			}
			$mail->Port = $fila17[$sPref . 'puertomail'];
			$mail->Host = $fila17[$sPref . 'servidorsmtp'];
			$this->objMAIL = $mail;
		}
		return $sError;
	}
	function RecurrenteEnviar($bDebug = false)
	{
		$sError = '';
		if ($this->iDestinatarios == 0) {
			$sError = 'No se han agregado destinatarios.';
		}
		if ($sError == '') {
			$mail = $this->objMAIL;
			$mail->Subject = utf8_decode($this->sAsunto);
			for ($k = 1; $k <= $this->iDestinatarios; $k++) {
				switch ($this->tipoenvio[$k]) {
					case 'C':
						$mail->AddCC($this->correos[$k], $this->nombres[$k]);
						break;
					case 'O':
						$mail->AddBCC($this->correos[$k], $this->nombres[$k]);
						break;
					default:
						$mail->AddAddress($this->correos[$k], $this->nombres[$k]);
						break;
				}
			}
			$mail->WordWrap = 50;
			if ($this->bHTML) {
				$mail->IsHTML(true);
				$mail->Body = '<html><body>' . $this->sCuerpo . '</body></html>';
			} else {
				$mail->Body = $this->sCuerpo;
			}
			$mail->CharSet = 'UTF-8';
			try {
				if ($mail->Send()) {
				} else {
					$sError = 'Error enviando el mensaje de correo, por favor comuniquese con el administrador del sistema.';
					//('.$mail->ErrorInfo.')
					if ($bDebug) {
						$sError = $sError . '<br>Error: ' . $mail->ErrorInfo;
					}
				}
			} catch (Exception $e) {
				$sError = 'Error al intentar enviar el mensaje de correo, por favor comuniquese con el administrador del sistema.';
				//<!-- '.$e->getMessage().' -->
			}
			//Termina de enviar el correo.
		}
		return $sError;
	}
	function RecurrenteCerrar()
	{
		unset($this->objMAIL);
	}
	function sListaCorreos($bCompletos = true)
	{
		$sRes = '';
		$iIni = 1;
		if (!$bCompletos) {
			$iIni = 2;
		}
		for ($k = $iIni; $k <= $this->iDestinatarios; $k++) {
			if ($sRes != '') {
				$sRes = $sRes . ', ';
			}
			$sRes = $sRes . $this->correos[$k];
		}
		return $sRes;
	}
	function TraerSMTP($idSMTP)
	{
		$objdb = $this->objdb;
		if ($idSMTP > -1) {
			$sSQL = 'SELECT * FROM unad69smtp WHERE unad69id=' . $idSMTP;
			$tabla = $objdb->ejecutasql($sSQL);
			if ($objdb->nf($tabla)) {
				$this->idSMTP = $idSMTP;
				$this->fila17 = $objdb->sf($tabla);
			}
		}
	}
	function __construct($objdb)
	{
		$this->objdb = $objdb;
	}
}
//Fin de la clase clsmail.
function fgmail_limpiar_texto($str)
{
	$subject = '';
	$subject_array = imap_mime_header_decode($str);
	foreach ($subject_array as $obj) {
		$subject = $subject . cadena_codificar(rtrim($obj->text, "t"));
	}
	return $subject;
}
function fgmail_leer($email, $password, $fecha, $bConCuerpo = false, $bDescartarPropios = false)
{
	$mailbox = "{imap.gmail.com:993/imap/ssl/novalidate-cert}INBOX";
	$aRes = array();
	$iCorreos = 0;
	$sError = '';
	try {
		$inbox = imap_open($mailbox, $email, $password);
	} catch (Exception $e) {
		$sError = 'Falló la conexión: ' . $e->getMessage();
	}
	if ($sError == '') {
		$emails = imap_search($inbox, 'SINCE "' . $fecha . '"');
		if ($emails) {
			foreach ($emails as $email_number) {
				$overview = imap_fetch_overview($inbox, $email_number);
				foreach ($overview as $over) {
					$fromaddress = '';
					$cabeceras = imap_header($inbox, $email_number);
					$from = $cabeceras->from;
					foreach ($from as $id => $object) {
						//$fromname = $object->personal;
						$fromaddress = $object->mailbox . "@" . $object->host;
					}
					$bCargaCorreo = true;
					if ($bDescartarPropios) {
						if ($fromaddress == $email) {
							$bCargaCorreo = false;
						}
					}
					if ($bCargaCorreo) {
						if ($bConCuerpo) {
							//$sLectura=imap_body($inbox, $email_number);
							$cuerpo = fgmail_traer_cuerpo($inbox, $email_number);
							/*
							$sLectura=imap_fetchbody($inbox, $email_number, 1.1);
							if ($sLectura==''){
								$sLectura=imap_fetchbody($inbox, $email_number, 1.2);
								}
							if ($sLectura==''){
								$sLectura=imap_fetchbody($inbox, $email_number, 1);
								}
							
							if ($sLectura!=''){
								$cuerpo=''.imap_qprint($sLectura);
								}
							*/
						} else {
							$cuerpo = '';
						}
						$iCorreos++;
						if (isset($over->subject) == 0) {
							$asunto = '{Sin asunto}';
						} else {
							$asunto = fgmail_limpiar_texto($over->subject);
						}
						$remitente = '';
						$destinatario = '';
						$message_id = $email_number;
						$uid = 0;
						if (isset($over->from)) { //quien lo envió
							$remitente = fgmail_limpiar_texto($over->from);
							$remitente = utf8_decode($remitente);
							//$remitente=utf8_decode($over->from);
						}
						if (isset($over->to)) { //destinatario 
							$destinatario = fgmail_limpiar_texto($over->to);
							$destinatario = utf8_decode($destinatario);
						}
						/*
						if (isset($over->message_id)) { // -  - ID de mensaje
							$message_id = fgmail_limpiar_texto($over->message_id);
							$message_id = utf8_decode($message_id);
							}
						*/
						if (isset($over->uid)) { //  - tamaño en bytes
							$uid = fgmail_limpiar_texto($over->uid);
							$uid = utf8_decode($uid);
						}
						//Ahora centralizando
						$aRes[$iCorreos]['asunto'] = utf8_decode($asunto);
						$aRes[$iCorreos]['origen'] = $fromaddress;
						$aRes[$iCorreos]['remitente'] = $remitente;
						$aRes[$iCorreos]['destinatario'] = $destinatario;
						$aRes[$iCorreos]['cuerpo'] = $cuerpo;
						$aRes[$iCorreos]['msg_id'] = $message_id;
						$aRes[$iCorreos]['uid'] = $uid;
						$aRes[$iCorreos]['over'] = $fromaddress;
					}
				}
			}
		}
		imap_close($inbox);
	}
	return array($sError, $iCorreos, $aRes);
}
function fgmail_traer_cuerpo($inbox, $number)
{
	$cuerpo = '---NO ENCONTRADO---';
	$sLectura = imap_fetchbody($inbox, $number, 1.1);
	if ($sLectura == '') {
		$sLectura = imap_fetchbody($inbox, $number, 1.2);
	}
	if ($sLectura == '') {
		$sLectura = imap_fetchbody($inbox, $number, 1);
	}
	if ($sLectura != '') {
		$cuerpo = '' . imap_qprint($sLectura);
	}
	return $cuerpo;
}
function fgmail_traer_cuerpoV2($inbox, $number)
{
	$info = imap_fetchstructure($inbox, $number, 0);
	if ($info->encoding == 3) {
		$message = base64_decode(imap_fetchbody($inbox, $number, 1));
	} else {
		if ($info->encoding == 4) {
			$message = imap_qprint(imap_fetchbody($inbox, $number, 1));
		} else {
			$message = imap_fetchbody($inbox, $number, 1);
		}
	}
	//$message = imap_fetchbody($inbox, $number, 2);
	return decode_qprint($message);
}
function fgmail_leerCorreo($email, $password, $fecha, $iNumeroCorreo)
{
	$mailbox = "{imap.gmail.com:993/imap/ssl/novalidate-cert}INBOX";
	$aRes = array();
	$cuerpo = '--NO ENCONTRADO--';
	$sError = '';
	try {
		$inbox = imap_open($mailbox, $email, $password);
	} catch (Exception $e) {
		$sError = 'Falló la conexión: ' . $e->getMessage();
	}
	if ($sError == '') {
		$cuerpo = fgmail_traer_cuerpo($inbox, $iNumeroCorreo);
		//$sLectura=imap_fetchbody($inbox, $iNumeroCorreo, 1.1);
		/*
        $emails = imap_search($inbox, 'SINCE "' . $fecha . '"');
        if ($emails){
            foreach ($emails as $email_number) {
				if ($email_number==$iNumeroCorreo){
					$cuerpo='Respuesta '.$email_number.':'.imap_fetchbody($inbox, $email_number, 1.2);
					}
				}
			}
		*/
		imap_close($inbox);
	}
	return array($sError, $cuerpo);
}
