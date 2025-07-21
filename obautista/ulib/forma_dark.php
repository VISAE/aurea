<?php
function encabezado($xajax = null, $sTitulo = '')
{
	require './app.php';
	echo '<!doctype html>
	<html lang="en">
	<head>
		<title>' . $sTitulo . '</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<script src="https://kit.fontawesome.com/994bca674e.js" crossorigin="anonymous"></script>
		<link rel="stylesheet" href="' . $APP->rutacomun . 'css3/bootstrap.min.css" type="text/css"/>
		<link rel="stylesheet" href="' . $APP->rutacomun . 'css3/style.css" type="text/css" />
		';
	if ($xajax != NULL) {
		$xajax->printJavascript();
	}
	echo '</head>
	<body>';
}
function cuerpo()
{
	echo '<div class="container m-4">';
}

function piedepagina()
{
	echo '
</div>
<script>
if ( window.history.replaceState ) {
	window.history.replaceState( null, null, window.location.href );
}
</script>
</body>

</html>';
}
