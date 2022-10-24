<?php
if (file_exists('./err_control.php')){require './err_control.php';}
require './app.php';
require $APP->rutacomun.'unad_todas.php';
require $APP->rutacomun.'libs/clsdbadmin.php';
require $APP->rutacomun.'unad_librerias.php';
require $APP->rutacomun.'libdatos.php';
require $APP->rutacomun.'libhtml.php';
require $APP->rutacomun.'xajax/xajax_core/xajax.inc.php';
require $APP->rutacomun.'unad_xajax.php';
// -- Se cargan los archivos de idioma
if (isset($_SESSION['unad_idioma']) == 0) {
  $_SESSION['unad_idioma'] = 'es';
}
$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_' . $_SESSION['unad_idioma'] . '.php';
if (!file_exists($mensajes_todas)) {
	$mensajes_todas = $APP->rutacomun . 'lg/lg_todas_es.php';
}
$mensajes_273 = 'lg/lg_273_' . $_SESSION['unad_idioma'] . '.php';
if (!file_exists($mensajes_273)) {
	$mensajes_273 = 'lg/lg_273_es.php';
}
require $mensajes_todas;
require $mensajes_273;
$xajax = NULL;
$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
//PROCESOS DE LA PAGINA
require 'lib273.php';
$xajax = new xajax();
$xajax->configure('javascript URI', $APP->rutacomun . 'xajax/');
$xajax->register(XAJAX_FUNCTION, 'f273_BuscaCodigo');
$xajax->register(XAJAX_FUNCTION, 'f273_EnviaEncuesta');
$xajax->processRequest();
$xajax->printJavascript($APP->rutacomun . 'xajax/');

$bDebug = false;
$sError = '';
$html_encuesta = '';

if (isset($_REQUEST['aure73tipodoc']) == 0) {
	$_REQUEST['aure73tipodoc'] = '';
}
if (isset($_REQUEST['aure73doc']) == 0) {
	$_REQUEST['aure73doc'] = '';
}
if (isset($_REQUEST['aure73codigo']) == 0) {
	$_REQUEST['aure73codigo'] = '';
}
if (isset($_REQUEST['paso']) == 0) {
    $_REQUEST['paso'] = 0;
}

$_REQUEST['aure73tipodoc'] = cadena_Validar($_REQUEST['aure73tipodoc']);
$_REQUEST['aure73doc'] = cadena_Validar($_REQUEST['aure73doc']);
$_REQUEST['aure73codigo'] = cadena_Validar($_REQUEST['aure73codigo']);

if ($_REQUEST['paso'] == 0) {
	if (isset($_GET['u'])!=0){
        //Esta recibiendo una peticion de recuperacion.
        $sURL=url_decode_simple($_GET['u']);
        if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Dato de llegada: '.$sURL.' <br>';}
        $aURL=explode('|', $sURL);
		if (count($aURL)<3){
			$sError = 'Codigo incorrecto.';
		} else {
			$iMes = $aURL[0];
			$idEncuesta = $aURL[1];
      $html_encuesta = f273_HTMLForm_Encuesta($iMes, $idEncuesta, $objDB);
		}
  }
  if (isset($_GET['n'])!=0){
        //Esta recibiendo una peticion de recuperacion.
        $sURL=url_decode_simple($_GET['n']);
        if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Dato de llegada: '.$sURL.' <br>';}
        $aURL=explode('|', $sURL);
		if (count($aURL)<3){
			$sError = 'Codigo incorrecto.';
		} else {
			$iMes = $aURL[0];
			$idEncuesta = $aURL[1];
      $html_encuesta = f273_HTMLNoRespondeEncuesta($iMes, $idEncuesta, $objDB);
		}
  }
}
//Crear los controles que requieran llamado a base de datos
$objCombos = new clsHtmlCombos();
$objCombos->nuevo('aure73tipodoc', $_REQUEST['aure73tipodoc'], true, '{' . $ETI['msg_seleccione'] . '}');
//$objCombos->addArreglo($aaure73tipodoc, $iaure73tipodoc);
$html_aure73tipodoc = $objCombos->html('', $objDB);

?>


<!doctype html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">

    <meta name="description" content="Plataforma Aurea, Universidad Abierta y a Distancia UNAD de Colombia">
    <meta name="author" content="Universidad Abierta y a Distancia UNAD de Colombia">

    <link rel="icon" type="image/svg+xml" href="../img/favicon/favicon.svg">
    <link rel="icon" type="image/png" href="../img/favicon/favicon.png">

    <title>Plataforma Aurea, Universidad Abierta y a Distancia UNAD de Colombia</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Serif&family=Open+Sans:wght@400;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css" media="all" />
    <link rel="stylesheet" href="../css/font-awesome.min.css" media="all" />

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>

    <!--
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script>window.jQuery || document.write('<script src="assets/js/vendor/jquery-slim.min.js"><\/script>')</script>
    -->
    <script>
      function enviacodigo() {
        let $aParametros = new Array();
        $aParametros[100] = window.document.frmcodigo.aure73tipodoc.value;
        $aParametros[101] = window.document.frmcodigo.aure73doc.value;
        $aParametros[102] = window.document.frmcodigo.aure73codigo.value;
        xajax_f273_BuscaCodigo($aParametros);
      }
      function enviaencuesta() {
        let $aParametros = new Array();
        $aParametros[100] = window.document.frmencuesta.iMes.value;
        $aParametros[101] = window.document.frmencuesta.aure73id.value;
        $aParametros[102] = window.document.frmencuesta.aure73t1_p1.value;
        $aParametros[103] = window.document.frmencuesta.aure73t1_p2.value;
        $aParametros[104] = window.document.frmencuesta.aure73t1_p3.value;
        $aParametros[105] = window.document.frmencuesta.aure73t1_p4.value;
        xajax_f273_EnviaEncuesta($aParametros);
      }
      function muestramensajes(tipo, texto) {
        htmlToast.className = htmlToast.className.replace(/bg-\w+/g, 'bg-'+tipo);
        document.getElementsByClassName('toast-body')[0].innerHTML = texto;
        objToast.show();
      }
      function muestracomplemento(elemento, valor) {
        sDisplay = 'none';
        if (valor > 0) {
          sDisplay = 'block';
        } 
        document.getElementById('p_'+elemento).style.display = sDisplay;
      }
    </script>
</head>
<body>

<form id="frmedita" name="frmedita" method="post" action="" autocomplete="off">
<input id="bNoAutocompletar" name="bNoAutocompletar" type="password" value="" style="display:none;"/>
<input id="paso" name="paso" type="hidden" value="<?php echo $_REQUEST['paso']; ?>" />
</form>
<?php
echo $sError;
?>


  <!-- HEADER -->
  <header class="header">
    <div class="container">
      <a href="/">
        <img src="../img/unad.svg" alt="Logo Universidad Abierta y a Distancia UNAD de Colombia">
      </a>
      <div class="header__title">
        <h1>Portal de Servicios</h1>
      </div>
    </div>
  </header>
  <!-- end HEADER -->


  <!-- MAIN -->
  <main class="main">
    <!-- slider images  -->
    <section class="images">
      <div class="logo">
        <img src="../img/plataforma-aurea.png" alt="Plataforma Aurea" />
      </div>
      <span></span>
      <div class="preload">
        <img src="../img/slide/001.jpg" />
        <img src="../img/slide/002.jpg" />
        <img src="../img/slide/003.jpg" />
        <img src="../img/slide/004.jpg" />
      </div>
      <div class="slide">
        <div style="background-image:url(../img/slide/001.jpg)"></div>
        <div style="background-image:url(../img/slide/002.jpg)"></div>
        <div style="background-image:url(../img/slide/003.jpg)"></div>
        <div style="background-image:url(../img/slide/004.jpg)"></div>
      </div>  
    </section>
    <!-- end slider images  -->

    <!-- dates area -->
    <section class="dates">
      <!-- menu -->
      <nav class="navbar navbar-expand-xl navbar-dark">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link" href="../index.html">
              <i class="fa fa-home"></i>
              Inicio
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../repositorio-manuales.html">
              <i class="fa fa-file"></i>
              Repositorio de Manuales de usuario
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../paz-y-salvo.html">
              <i class="fa fa-check"></i>
              Verificacion de Paz y salvo
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" href="index.php">
              <i class="fa fa-list"></i>
              Encuesta
            </a>
          </li>
        </ul>
      </nav>
      <!-- end menu -->

      <hr />

      <article class="article">
        <h2>
          <span><i class="fa fa-list"></i> <?php echo $ETI['titulo']; ?></span>
        </h2>
        <div class="article__area row">
          <!-- form area -->
          <div class="col-12">

            <?php
            $sStyleFormCodigo = 'style="display:none;"';
            $sStyleFormEncuesta = 'style="display:none;"';
            if ($sError == '') {
              if (isset($idEncuesta)) {
                $sStyleFormEncuesta = 'style="display:block;"';
              } else {
                $sStyleFormCodigo = 'style="display:block;"';
              }
            } else {
            ?>
            <div class="alert alert-danger" role="alert"><strong>No se ha encontrado la encuesta</strong></div>
            <?php
            }
            ?>
            <div id="div_aure73formcodigo" <?php echo $sStyleFormCodigo; ?>>
              <form id="frmcodigo" name="frmcodigo" method="post" action="" autocomplete="off">
              <div class="form-group row">
                  <div class="col-sm-3">
                    <label for="aure73tipodoc"><?php echo $ETI['aure73tipodoc']; ?></label>
                    <?php
                    echo html_tipodocV2('aure73tipodoc',$_REQUEST['aure73tipodoc']);
                    ?>
                  </div>
                  <div class="col-sm-9">
                    <label for="aure73doc" class="text-center"><?php echo $ETI['ing_campo'] . $ETI['aure73doc']; ?></label>
                    <input id="aure73doc" name="aure73doc" class="form-control form-control-lg text-center" type="text" value="<?php echo $_REQUEST['aure73doc']; ?>" maxlength="20" placeholder="<?php echo $ETI['digite']; ?>" />
                    <!-- <input type="text" class="form-control form-control-lg text-center" id="aure73doc" placeholder="Digítalo aquí"> -->
                  </div>
              </div>
              <div class="form-group row">
                <div class="col-sm-12">
                  <label for="aure73codigo" class="text-center"><?php echo $ETI['ing_campo'] . $ETI['aure73codigo']; ?></label>
                  <input id="aure73codigo" name="aure73codigo" class="form-control form-control-lg text-center" type="text" value="<?php echo $_REQUEST['aure73codigo']; ?>" maxlength="20" placeholder="<?php echo $ETI['digite']; ?>" />
                  <!-- <input type="text" class="form-control form-control-lg text-center" id="aure73codigo" placeholder="Digítalo aquí"> -->
                </div>
              </div>
              <input type="button" id="cmdEnviaCodigo" name="cmdEnviaCodigo" class="btn btn-aurea px-4 float-right" title="<?php echo $ETI['bt_enviar']; ?>" value="<?php echo $ETI['bt_enviar']; ?>" onclick="enviacodigo()">
              </form>
            </div>
            <?php
            ?>
            <hr>
            <div id="div_aure73formencuesta" <?php echo $sStyleFormEncuesta; ?>>
              <?php echo $html_encuesta; ?>
            </div>
          </div>
          <!-- end form area -->
        </div>
        <!-- toast -->
        <div style="position: fixed; top: 80%; right: 0; padding:5px;">
          <div id="liveToast" class="toast align-items-center text-white bg-primary border-0" role="alert" aria-live="assertive" aria-atomic="true" data-delay="5000">
            <div class="d-flex">
              <div class="toast-body">
              </div>
              <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
          </div>
        </div>
        <!-- end toast -->
      </article>
    </section>
    <!-- end dates area -->    
    <!-- footer -->
    <footer class="footer">
      <p>Universidad Nacional Abierta y a Distancia UNAD de Colombia - © Copyright UNAD 2022</p>
      <!-- <strong>Línea anticorrupción: </strong> <a href="tel:6013443700" target="_blank" title="Línea anticorrupción: 601-3443700 ext. 1544">601-3443700 ext. 1544</a><br> En Bogotá D.C. (Colombia) <strong>Teléfono: </strong><a href="tel:6013759500" target="_blank" title="Teléfono: 601-375 9500">601-375 9500 </a> <strong> - Línea gratuita nacional:</strong> <a href="tel:018000115223" target="_blank" title="Línea gratuita nacional: 01 8000 115223 "> 01 8000 115223</a><br> Institución de Educación Superior sujeta a inspección y vigilancia por el Ministerio de Educación Nacional <br> <strong>Universidad Nacional Abierta y a Distancia UNAD de Colombia - © Copyright UNAD 2022</strong> -->
    </footer>
    <!-- end footer -->
</main>
<!-- end MAIN -->
<script>
  let htmlToast = document.getElementById('liveToast');
  let objToast = new bootstrap.Toast(htmlToast);
  $(document).ready(() => {
    $('select').addClass("form-control");    
  });
</script>
</body>
</html>
