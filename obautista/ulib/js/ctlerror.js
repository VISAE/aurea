// JavaScript Document
// Gestion generica de resaltado para controles requeridos.
(function (window, document) {
	'use strict';

	const aCtlErrorConfigurados = [];

	function ctlerror_NormalizarItem(item) {
		let config = item;
		let sTipo = 'texto';
		if (typeof item === 'string') {
			config = { campo: item };
		}
		config = config || {};
		sTipo = (config.tipo || 'texto').toLowerCase();
		if (sTipo === 'fecha') {
			sTipo = 'fecha_numero';
		}
		return {
			campo: config.campo || config.nombre || '',
			tipo: sTipo,
			idLabel: config.idLabel || '',
			idDiv: config.idDiv || '',
			idDestino: config.idDestino || '',
			aplicarEn: (config.aplicarEn || 'control').toLowerCase(),
			eventos: config.eventos || [],
			mostrarMensajeLabel: (typeof config.mostrarMensajeLabel === 'boolean') ? config.mostrarMensajeLabel : true,
			textoMensajeLabel: config.textoMensajeLabel || ''
		};
	}

	function ctlerror_BuscarConfig(campo) {
		for (let i = 0; i < aCtlErrorConfigurados.length; i++) {
			if (aCtlErrorConfigurados[i].campo === campo) {
				return aCtlErrorConfigurados[i];
			}
		}
		return ctlerror_NormalizarItem(campo);
	}

	function ctlerror_ObtenerControl(config) {
		if (config.campo === '') {
			return null;
		}
		return document.getElementById(config.campo);
	}

	function ctlerror_ObtenerLabel(config, control) {
		let idLabel = config.idLabel;
		if (idLabel === '') {
			idLabel = 'label_' + config.campo;
		}
		let label = document.getElementById(idLabel);
		if ((label === null) && (control !== null) && (typeof control.closest === 'function')) {
			label = control.closest('label');
		}
		return label;
	}

	function ctlerror_ObtenerDiv(config) {
		let idDiv = config.idDiv;
		if (idDiv === '') {
			idDiv = 'div_' + config.campo;
		}
		return document.getElementById(idDiv);
	}

	function ctlerror_ObtenerDestino(config, control) {
		let destino = null;
		if (config.idDestino !== '') {
			destino = document.getElementById(config.idDestino);
			if (destino !== null) {
				return destino;
			}
		}
		switch (config.aplicarEn) {
			case 'div':
				return ctlerror_ObtenerDiv(config);
			case 'label':
				return ctlerror_ObtenerLabel(config, control);
			case 'control':
			default:
				return control;
		}
	}

	function ctlerror_ControlVisible(control) {
		if (control === null) {
			return false;
		}
		if ((typeof control.type !== 'undefined') && (control.type.toLowerCase() === 'hidden')) {
			return false;
		}
		return true;
	}

	function ctlerror_EsTipoFecha(config) {
		switch (config.tipo) {
			case 'fecha_numero':
			case 'fecha_texto':
				return true;
			default:
				return false;
		}
	}

	function ctlerror_UsaControlOculto(config) {
		return ctlerror_EsTipoFecha(config);
	}

	function ctlerror_ObtenerControlesEvento(config, control) {
		const controles = [];
		if (ctlerror_EsTipoFecha(config)) {
			['dia', 'mes', 'agno'].forEach(function (sufijo) {
				const obj = document.getElementById(config.campo + '_' + sufijo);
				if (obj !== null) {
					controles.push(obj);
				}
			});
		}
		if ((controles.length === 0) && (control !== null)) {
			controles.push(control);
		}
		return controles;
	}

	function ctlerror_ObtenerParteFecha(campo, sufijo) {
		return document.getElementById(campo + '_' + sufijo);
	}

	function ctlerror_ValorTexto(control) {
		if (control === null) {
			return '';
		}
		return String(control.value).trim();
	}

	function ctlerror_ValorNumero(control) {
		const valor = parseInt(ctlerror_ValorTexto(control), 10);
		if (isNaN(valor)) {
			return 0;
		}
		return valor;
	}

	function ctlerror_FechaTextoVacia(config) {
		const dia = ctlerror_ValorTexto(ctlerror_ObtenerParteFecha(config.campo, 'dia'));
		const mes = ctlerror_ValorTexto(ctlerror_ObtenerParteFecha(config.campo, 'mes'));
		const agno = ctlerror_ValorTexto(ctlerror_ObtenerParteFecha(config.campo, 'agno'));
		return (dia === '') || (dia === '0') || (dia === '00') ||
			(mes === '') || (mes === '0') || (mes === '00') ||
			(agno === '') || (agno === '0') || (agno === '0000');
	}

	function ctlerror_FechaNumeroVacia(config) {
		const dia = ctlerror_ValorNumero(ctlerror_ObtenerParteFecha(config.campo, 'dia'));
		const mes = ctlerror_ValorNumero(ctlerror_ObtenerParteFecha(config.campo, 'mes'));
		const agno = ctlerror_ValorNumero(ctlerror_ObtenerParteFecha(config.campo, 'agno'));
		return (dia === 0) || (mes === 0) || (agno === 0);
	}

	function ctlerror_EstaVacio(control, config) {
		switch (config.tipo) {
			case 'fecha_numero':
				return ctlerror_FechaNumeroVacia(config);
			case 'fecha_texto':
				return ctlerror_FechaTextoVacia(config);
			case 'texto':
			default:
				return control.value.replace(/\s+/g, ' ').trim() === '';
		}
	}

	function ctlerror_AplicarEstadoLabel(config, control, bError) {
		const label = ctlerror_ObtenerLabel(config, control);
		if ((label === null) || !config.mostrarMensajeLabel) {
			return;
		}
		if (bError) {
			label.classList.add('campoRequeridoLabel');
			label.setAttribute('data-ctlerror-msg', config.textoMensajeLabel);
		} else {
			label.classList.remove('campoRequeridoLabel');
			label.removeAttribute('data-ctlerror-msg');
		}
	}

	function ctlerror_LimpiarEstado(config) {
		const control = ctlerror_ObtenerControl(config);
		const destino = ctlerror_ObtenerDestino(config, control);
		const label = ctlerror_ObtenerLabel(config, control);
		if (destino !== null) {
			destino.classList.remove('campoRequerido');
		}
		if (label !== null) {
			label.classList.remove('campoRequeridoLabel');
			label.removeAttribute('data-ctlerror-msg');
		}
		if (control !== null) {
			control.removeAttribute('aria-invalid');
			if (typeof control.dataset !== 'undefined') {
				delete control.dataset.ctlerrorVinculado;
			}
		}
		return true;
	}

	function ctlerror_AplicarEstado(config) {
		const control = ctlerror_ObtenerControl(config);
		const destino = ctlerror_ObtenerDestino(config, control);
		let bError = false;
		if ((control === null) || (destino === null)) {
			return false;
		}
		if (!ctlerror_UsaControlOculto(config) && !ctlerror_ControlVisible(control)) {
			return false;
		}
		bError = ctlerror_EstaVacio(control, config);
		destino.classList.toggle('campoRequerido', bError);
		ctlerror_AplicarEstadoLabel(config, control, bError);
		if (bError) {
			control.setAttribute('aria-invalid', 'true');
		} else {
			control.removeAttribute('aria-invalid');
		}
		return true;
	}

	function ctlerror_EsEvaluable(config) {
		const control = ctlerror_ObtenerControl(config);
		const destino = ctlerror_ObtenerDestino(config, control);
		if ((control === null) || (destino === null)) {
			return false;
		}
		if (!ctlerror_UsaControlOculto(config) && !ctlerror_ControlVisible(control)) {
			return false;
		}
		return true;
	}

	function ctlerror_ResolverEventos(config) {
		if (Array.isArray(config.eventos) && (config.eventos.length > 0)) {
			return config.eventos;
		}
		if (ctlerror_EsTipoFecha(config)) {
			return ['change'];
		}
		switch (config.tipo) {
			case 'texto':
			default:
				return ['change', 'input'];
		}
	}

	function ctlerror_Vincular(config, validarAlIniciar) {
		const control = ctlerror_ObtenerControl(config);
		if (control === null) {
			return false;
		}
		if (!ctlerror_UsaControlOculto(config) && !ctlerror_ControlVisible(control)) {
			return false;
		}
		if (control.dataset.ctlerrorVinculado === '1') {
			if (validarAlIniciar) {
				ctlerror_AplicarEstado(config);
			}
			return true;
		}
		const eventos = ctlerror_ResolverEventos(config);
		const controlesEvento = ctlerror_ObtenerControlesEvento(config, control);
		for (let k = 0; k < controlesEvento.length; k++) {
			for (let i = 0; i < eventos.length; i++) {
				controlesEvento[k].addEventListener(eventos[i], function () {
					ctlerror_AplicarEstado(config);
				});
			}
		}
		control.dataset.ctlerrorVinculado = '1';
		if (validarAlIniciar) {
			ctlerror_AplicarEstado(config);
		}
		return true;
	}

	function ctlerror_NormalizarOpciones(opciones) {
		if (typeof opciones === 'boolean') {
			return {
				validarAlIniciar: opciones,
				textoMensajeLabel: 'Campo requerido'
			};
		}
		opciones = opciones || {};
		return {
			validarAlIniciar: (typeof opciones.validarAlIniciar === 'boolean') ? opciones.validarAlIniciar : true,
			textoMensajeLabel: opciones.textoMensajeLabel || 'Campo requerido'
		};
	}

	function ctlerror_iniciar(items, opciones) {
		if (!Array.isArray(items)) {
			return false;
		}
		const configGlobal = ctlerror_NormalizarOpciones(opciones);
		for (let i = 0; i < items.length; i++) {
			const config = ctlerror_NormalizarItem(items[i]);
			if (config.campo === '') {
				continue;
			}
			if (config.textoMensajeLabel === '') {
				config.textoMensajeLabel = configGlobal.textoMensajeLabel;
			}
			aCtlErrorConfigurados.push(config);
			ctlerror_Vincular(config, configGlobal.validarAlIniciar);
		}
		return true;
	}

	function ctlerror_validar(campo) {
		const config = ctlerror_BuscarConfig(campo);
		return ctlerror_AplicarEstado(config);
	}

	function ctlerror_limpiar(campo) {
		const config = ctlerror_BuscarConfig(campo);
		return ctlerror_LimpiarEstado(config);
	}

	function ctlerror_refrescar(campo, validarAlIniciar) {
		const config = ctlerror_BuscarConfig(campo);
		ctlerror_LimpiarEstado(config);
		return ctlerror_Vincular(config, !!validarAlIniciar);
	}

	function ctlerror_resumen(campos) {
		const aCampos = [];
		const aProcesados = {};
		const aFaltantes = [];
		let iTotal = 0;
		let iPendientes = 0;
		if (Array.isArray(campos) && (campos.length > 0)) {
			for (let i = 0; i < campos.length; i++) {
				aCampos.push(ctlerror_BuscarConfig(campos[i]));
			}
		} else {
			for (let i = 0; i < aCtlErrorConfigurados.length; i++) {
				aCampos.push(aCtlErrorConfigurados[i]);
			}
		}
		for (let i = 0; i < aCampos.length; i++) {
			const config = ctlerror_NormalizarItem(aCampos[i]);
			const sLlave = config.campo + '|' + config.tipo + '|' + config.aplicarEn;
			const control = ctlerror_ObtenerControl(config);
			if (aProcesados[sLlave]) {
				continue;
			}
			aProcesados[sLlave] = true;
			if (!ctlerror_EsEvaluable(config)) {
				continue;
			}
			iTotal++;
			ctlerror_AplicarEstado(config);
			if (ctlerror_EstaVacio(control, config)) {
				iPendientes++;
				aFaltantes.push(config.campo);
			}
		}
		return {
			total: iTotal,
			pendientes: iPendientes,
			ok: (iPendientes === 0),
			campos: aFaltantes
		};
	}

	function ctlerror_pendientes(campos) {
		return ctlerror_resumen(campos).pendientes;
	}

	window.ctlerror_iniciar = ctlerror_iniciar;
	window.ctlerror_validar = ctlerror_validar;
	window.ctlerror_limpiar = ctlerror_limpiar;
	window.ctlerror_refrescar = ctlerror_refrescar;
	window.ctlerror_resumen = ctlerror_resumen;
	window.ctlerror_pendientes = ctlerror_pendientes;
})(window, document);
