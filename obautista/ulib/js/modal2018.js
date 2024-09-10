function ModalMensaje(sHTML) {
	let objCuerpo = document.getElementById('ModalCuerpoAdv');
	jQuery.noConflict();
	jQuery('#ModalAdvertencia').modal();
	// Cargo el mensaje
	objCuerpo.innerHTML = sHTML;
}
function ModalConfirm(sHTML) {
	let objCuerpo = document.getElementById('ModalCuerpoCon');
	jQuery.noConflict();
	jQuery('#ModalConfirm').modal('show');
	// Cargo el mensaje
	objCuerpo.innerHTML = sHTML;
	// Focus
	setTimeout(function () {
		jQuery("#modal-btn-si").focus();
	}, 500);
}
function ModalConfirmV2(sHTML, callback) {
	let objCuerpo = document.getElementById('ModalCuerpoCon');
	let btnSi = document.getElementById('modal-btn-si');
	jQuery.noConflict();
	jQuery('#ModalConfirm').modal('show');
	btnSi.onclick = callback;
	// Cargo el mensaje
	objCuerpo.innerHTML = sHTML;
	// Focus
	setTimeout(function () {
		jQuery("#modal-btn-si").focus();
	}, 500);
}