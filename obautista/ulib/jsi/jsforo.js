// JavaScript Document
function foro_actualizar(idProceso, idRef, idUsuario){
	var objCom=document.getElementById('nota_'+idProceso+'_0');
	var params=new Array();
	params[1]=idProceso;
	params[2]=idRef;
	params[4]=objCom.value.trim();
	params[5]=idUsuario;
	xajax_foro_actualizar(params);
	}
function foro_comentar(idProceso, idRef, idPadre, idUsuario){
	var objCom=document.getElementById('nota_'+idProceso+'_'+idPadre);
	if (objCom.value.trim()!=''){
		document.getElementById('bguarda_'+idProceso+'_'+idPadre).style.visible='none';
		var params=new Array();
		params[1]=idProceso;
		params[2]=idRef;
		params[3]=idPadre;
		params[4]=objCom.value.trim();
		params[5]=idUsuario;
		xajax_foro_comentar(params);
		}
	}