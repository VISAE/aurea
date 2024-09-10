//
const modal2023 = document.getElementById('div_modal');
const btnModalClose1 = document.querySelectorAll(".modal-close");

function cerrarModal() {
	modal2023.style.display = 'none';
}

// Cerrar Modal Cursos
if (btnModalClose1) {
	btnModalClose1.forEach(element => {
		element.addEventListener('click', () => {
			modal2023.style.display = "none";
		});
	});
}

function abrirModal() {
	// Abre
	modal2023.style.display = 'grid';
}

function ModalMensaje(sHTML) {
	ModalMensajeV2(sHTML);
}

function ModalConfirmV2(sHTML, callback) {
	ModalMensajeV2(sHTML, callback);
}

function ModalMensajeV2(texto, callback = null, { tituloModal = '', botonAceptar = '', botonCancelar = '' } = {}) {
	const modalBody = document.getElementById('modal__body');
	const btnCancelar = document.getElementById('boton__cancelar');
	// Cargo el mensaje
	modalBody.innerHTML = texto;
	// Verificar si debo usar o no callback
	let estadoCancelar = 'none';
	if (typeof callback === 'function') {
		// Aca incruste el callback
		const btnAceptar = document.getElementById('boton__aceptar');
		btnAceptar.onclick = callback;
		// Mostrar el boton cancelar
		estadoCancelar = 'flex';
	}
	// Definir el estado del boton cancelar
	btnCancelar.style.display = estadoCancelar;
	// Modificar el titulo del modal
	if (tituloModal) {
		const modalTitle = document.getElementById('modal__title');
		modalTitle.innerHTML = tituloModal;
	}
	// Cambiar texto del boton aceptar
	if (botonAceptar) {
		const btnAceptarTitulo = document.getElementById('boton__aceptar__title');
		btnAceptarTitulo.innerHTML = botonAceptar;
	}
	if (botonCancelar) {
		const btnCancelarTitulo = document.getElementById('boton__cancelar__title');
		btnCancelarTitulo.innerHTML = botonCancelar;
	}
	//
	abrirModal();
}