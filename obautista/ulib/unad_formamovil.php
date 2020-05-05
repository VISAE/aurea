<?php
function forma_cabeceraV2($CFG, $SITE, $XAJAX=NULL, $modnombre='', $sLinks=''){
	$sTipo='misc';
	$aBloque=explode('@',$sLinks);
	switch(count($aBloque)){
		case 1:
		$aLinea=explode('|', $aBloque[0]);
		$sName=$aLinea[0];
		$sLink=$aLinea[1];
		$navigation=build_navigation(array(array('name'=>$sName, 'link'=>$sLink, 'type'=>$sTipo)));
		break;
		case 3:
		$aLinea=explode('|', $aBloque[0]);
		$sName1=$aLinea[0];
		$sLink1=$aLinea[1];
		$aLinea=explode('|', $aBloque[1]);
		$sName2=$aLinea[0];
		$sLink2=$aLinea[1];
		$aLinea=explode('|', $aBloque[2]);
		$sName3=$aLinea[0];
		$sLink3=$aLinea[1];
		//echo '---'.$sName1.'|'.$sLink1.'@'.$sName2.'|'.$sLink2.'@'.$sName3.'|'.$sLink3.'---';
		$navigation=build_navigation(array(array('name'=>$sName1, 'link'=>$sLink1, 'type'=>$sTipo),array('name'=>$sName2, 'link'=>$sLink2, 'type'=>$sTipo),array('name'=>$sName3, 'link'=>$sLink3, 'type'=>$sTipo)));
		break;
		default:
		$sName='';
		$sLink='';
		$navigation=build_navigation(array(array('name'=>$sName, 'link'=>$sLink, 'type'=>$sTipo)));
		break;
		}
	forma_cabecera($CFG, $SITE, $modnombre, $navigation, $XAJAX);
	}
function forma_cabecera($CFG, $SITE, $modulo_nombre, $modulo_sigla, $XAJAX=NULL){
	if (empty($CFG->langmenu)) {
		$langmenu = '';
		}else{
		$currlang = current_language();
		$langs = get_list_of_languages();
		$langlabel = get_accesshide(get_string('language'));
		$langmenu = popup_form($CFG->wwwroot .'/index.php?lang=', $langs, 'chooselang', $currlang, '', '', '', true, 'self', $langlabel);
		}
	print_header($SITE->fullname.' - '.$modulo_nombre, $modulo_nombre, $modulo_sigla,'','',true, $langmenu);
	if ($XAJAX!=NULL){$XAJAX->printJavascript();}
	echo '
<script language="JavaScript" type="text/javascript" charset="UTF-8">
<!--
function muestraayuda(app, modulo){
	window.document.frmayuda.app.value=app;
	window.document.frmayuda.com.value=modulo;
	window.document.frmayuda.nota.value=0;
	window.document.frmayuda.submit();
	}
function ayudanota(app, nota){
	window.document.frmayuda.app.value=app;
	window.document.frmayuda.com.value=0;
	window.document.frmayuda.nota.value=nota;
	window.document.frmayuda.submit();
	}
-->
</script>
<form id="frmayuda" name="frmayuda" action="http://datateca.unad.edu.co/ayuda/" method="post" target="_blank">
<input id="app" name="app" type="hidden" value="0" />
<input id="com" name="com" type="hidden" value="0" />
<input id="nota" name="nota" type="hidden" value="0" />
</form>
';
	}
function forma_mitad(){
	echo '
<hr>
<div class="salto1px"></div>
<div class="cuerpo">
';
	}
function forma_piedepagina(){
	echo '
</div>
<div class="salto1px"></div>
<div id="div_tiempo" style="width:150px;" class="ir_derecha"></div>
<div class="salto1px"></div>';
	print_footer('home');
	}
?>