// OBTENER NOMBRE DEL ARCHIVO JS
const scripts = document.getElementsByTagName('script');
const JS_ORIGEN = scripts[scripts.length - 1].src.split('/').pop().replace('.js', '') + '_';
// FUNCION PARA CERRAR ELEMENTOS AL HACER CLIC FUERA DE ELLOS
/// sTrigger: Elemento que activa el evento (ej. botón)
/// sElement: Elemento que se cierra (ej. menú desplegable)
/// bIsExpanded: Indica si el elemento está expandido
/// fParameters: Función a ejecutar al hacer clic fuera del elemento
function closeOnClickOutside({ target, sTrigger, sElement, bIsExpanded, fParameters }) {
	// Si no se está mostrando el elemento, no hacemos nada
	if (!bIsExpanded) return;
	// Verifica si el clic fue fuera del elemento y del disparador
	if (target !== sElement && !sElement.contains(target) && target !== sTrigger) {
		fParameters();
		document.removeEventListener("click", closeOnClickOutside);
	}
}
// Elemento que activa el selector de idiomas
const LANGUAGE_SELECTOR = document.querySelector('.language-selector__button');
// Contenedor del selector de idiomas
const LANGUAGE_SELECTOR_DROPDOWN = document.querySelector('.language-selector__dropdown');
// Valirdar si el selector de idiomas está expandido
let isLanguageSelectorExpanded = false;
// Variable para almacenar el idioma de sesión
const currentLangCampus = document.getElementById('lang-config')?.dataset?.lang;


// FUNCION PARA ABRIR EL SELECTOR DE IDIOMAS
function openLanguageSelector() {
	isLanguageSelectorExpanded = true;
	LANGUAGE_SELECTOR.setAttribute("aria-expanded", "true");
	LANGUAGE_SELECTOR_DROPDOWN.classList.remove("language-selector__dropdown--hidden");
}
// FUNCION PARA CERRAR EL SELECTOR DE IDIOMAS
function closeLanguageSelector() {
	isLanguageSelectorExpanded = false;
	LANGUAGE_SELECTOR.setAttribute("aria-expanded", "false");
	LANGUAGE_SELECTOR_DROPDOWN.classList.add("language-selector__dropdown--hidden");
}

// FUnCIÓN PARA CAMBIAR EL IDIOMA
function changeLanguage(idioma = "es") {
	xajax_iDefinirIdioma(idioma);
	setTimeout(() => {
		location.reload();
	}, 500);
}

// FUNCION PARA INICIALIZAR EL SELECTOR DE IDIOMAS
function initLanguageSelector() {
	// Opener language selector
	if (LANGUAGE_SELECTOR && LANGUAGE_SELECTOR_DROPDOWN) {
		closeLanguageSelector();
	}
	// Comprobar si el idioma ya está almacenado
	if (currentLangCampus) {
		// Si existe, establecer el atributo aria-checked en el elemento correspondiente
		const languageItem = LANGUAGE_SELECTOR_DROPDOWN.querySelector(`#language-selector-${currentLangCampus}`);
		if (languageItem) {
			languageItem.setAttribute("aria-checked", "true");
		}
	} else {
		// Marcar como seleccionado
		const languageItem = LANGUAGE_SELECTOR_DROPDOWN.querySelector(`#language-selector-${currentLangCampus}`);
		if (languageItem) {
			languageItem.setAttribute("aria-checked", "true");
		}
	}
}

// FUNCION PARA TOGGLE DEL SELECTOR DE IDIOMAS
function toggleLanguageSelector() {
	// Comprobar si el selector está abierto o cerrado, Si está cerrado, abrirlo; si está abierto, cerrarlo
	if (!isLanguageSelectorExpanded) {
		openLanguageSelector();
		// Cerrar el selector al hacer clic por fuera de él
		document.addEventListener("click", (event) => {
			// Probando Ando
			// const initialTarget = event.target.parentElement ? event.target.parentElement : event.target;
			const initialTarget = event.target;
			closeOnClickOutside({
				target: initialTarget,
				sTrigger: LANGUAGE_SELECTOR,
				sElement: LANGUAGE_SELECTOR_DROPDOWN,
				bIsExpanded: true,
				fParameters: () => {
					closeLanguageSelector();
				}
			});
		});
	} else {
		closeLanguageSelector();
	}
}

if (LANGUAGE_SELECTOR_DROPDOWN) {
	// Idimas disponibles
	const LANGUAGE_SELECTOR_ES = LANGUAGE_SELECTOR_DROPDOWN.querySelector('#language-selector-es');
	const LANGUAGE_SELECTOR_EN = LANGUAGE_SELECTOR_DROPDOWN.querySelector('#language-selector-en');

	// Añadir evento de click a cada idioma
	if (LANGUAGE_SELECTOR_ES && LANGUAGE_SELECTOR_EN) {
		LANGUAGE_SELECTOR_ES.addEventListener('click', () => {
			LANGUAGE_SELECTOR_ES.setAttribute("aria-checked", "true");
			LANGUAGE_SELECTOR_EN.setAttribute("aria-checked", "false");
			closeLanguageSelector();
			changeLanguage("es");
		});
		LANGUAGE_SELECTOR_EN.addEventListener('click', () => {
			LANGUAGE_SELECTOR_EN.setAttribute("aria-checked", "true");
			LANGUAGE_SELECTOR_ES.setAttribute("aria-checked", "false");
			closeLanguageSelector();
			changeLanguage("en");
		});
	}
}

initLanguageSelector();
// CLASES HTML

const BOTON_MAS = document.getElementById("boton-mas");
const BOTON_MENOS = document.getElementById("boton-menos");
const BOTON_TEMA_CLARO = document.getElementById("boton-claro");
const BOTON_TEMA_OSCURO = document.getElementById("boton-oscuro");
const BOTON_FILTRO_CONTRASTE = document.getElementById("boton-contraste");
const BOTON_FILTRO_SATURACION = document.getElementById("boton-saturacion");
const BOTON_CURSOR = document.getElementById("boton-cursor");
const BOTON_LECTURA = document.getElementById("boton-lectura");
const BOTON_REINICIO = document.getElementById("boton-reinicio");
const ABRIR_WIDGET = document.querySelectorAll(".open-widget-access");
const CERRAR_WIDGET = document.querySelectorAll(".close-widget-access");
const WIDGET_ACCESS = document.getElementById("widget-access");

// *Elemento raiz del documento, en este caso
const HTML_POSITION = document.documentElement;

// CLASES PARA EL BODY
const TEMA_CLARO = "light-theme";
const TEMA_OSCURO = "dark-theme";
const FILTRO_CONTRASTE = "contrast-filter";
const FILTRO_SATURACION_1 = "saturation-filter-1";
const FILTRO_SATURACION_2 = "saturation-filter-2";
const FILTRO_SATURACION_3 = "saturation-filter-3";
const OPTION_CURSOR = "cursor";
const OPTION_LECTURA = "lectura";

// CLASES PARA LOS BOTONES DEL PANEL DE ACCESIBILIDAD
const ESTADO_ACTIVE = "active";
const ESTADO_CONTRASTE_1 = "contrast-1";
const ESTADO_SATURACION_1 = "saturation-1";
const ESTADO_SATURACION_2 = "saturation-2";
const ESTADO_SATURACION_3 = "saturation-3";

// VALORES PARA DEFINIR EL TAMAÑO DE FONT SIZE EN BODY
const VALOR_UNIDAD = "px";
const VALOR_MAX = 10.5;
const VALOR_BASE = 8;
const VALOR_MIN = 5.5;
const VALOR_PASO = 0.5;

// VARIABLES LOCALES
let fuenteGuardada = window.localStorage.getItem(JS_ORIGEN + "fuenteGuardada");
let temaGuardado = window.localStorage.getItem(JS_ORIGEN + "temaGuardado");
let filtroGuardado = window.localStorage.getItem(JS_ORIGEN + "filtroGuardado");
let cursorGuardado = window.localStorage.getItem(JS_ORIGEN + "cursorGuardado");
let lecturaGuardada = window.localStorage.getItem(JS_ORIGEN + "lecturaGuardada");
// FUNCIONES

//* Cambiar el icono del panel de accesibilidad, en caso de que hayan funciones activas
function estadoWidget() {
	if (WIDGET_ACCESS) {
		activeElements = WIDGET_ACCESS.querySelectorAll(".widget__content .active");
		if (activeElements.length === 1 && activeElements[0].id === "boton-claro" && document.documentElement.style.fontSize == VALOR_BASE + VALOR_UNIDAD) {
			WIDGET_ACCESS.classList.remove(ESTADO_ACTIVE);
		} else {
			WIDGET_ACCESS.classList.add(ESTADO_ACTIVE);
		}
	}
}
//* Aumentar o disminuir la fuente raiz (De este valor dependen la unidad rem)
function cambiarFuente(parametro) {
	let dNuevaFuente = parametro === "aumentar"
		? Math.min(VALOR_MAX, parseFloat(fuenteGuardada) + VALOR_PASO)
		: Math.max(VALOR_MIN, parseFloat(fuenteGuardada) - VALOR_PASO);

	if (dNuevaFuente !== parseFloat(fuenteGuardada)) {
		fuenteGuardada = dNuevaFuente;
		document.documentElement.style.fontSize = fuenteGuardada + VALOR_UNIDAD;
		window.localStorage.setItem(JS_ORIGEN + "fuenteGuardada", fuenteGuardada);
	}
}
//* Activar el tema claro
function temaClaro() {
	reiniciarTemasActives();
	reiniciarTemas();
	document.body.classList.add(TEMA_CLARO);
	window.localStorage.setItem(JS_ORIGEN + "temaGuardado", TEMA_CLARO);
	if (BOTON_TEMA_CLARO.classList.contains(ESTADO_ACTIVE)) {
		BOTON_TEMA_CLARO.classList.remove(ESTADO_ACTIVE);
		BOTON_TEMA_CLARO.firstElementChild.style.display = "none";
	} else {
		BOTON_TEMA_CLARO.classList.add(ESTADO_ACTIVE);
		BOTON_TEMA_CLARO.firstElementChild.style.display = "grid";
	}
}
//* Activar el tema oscuro
function temaOscuro() {
	reiniciarTemasActives();
	reiniciarTemas();
	document.body.classList.add(TEMA_OSCURO);
	window.localStorage.setItem(JS_ORIGEN + "temaGuardado", TEMA_OSCURO);
	if (BOTON_TEMA_OSCURO.classList.contains(ESTADO_ACTIVE)) {
		BOTON_TEMA_OSCURO.classList.remove(ESTADO_ACTIVE);
		BOTON_TEMA_OSCURO.firstElementChild.style.display = "none";
	} else {
		BOTON_TEMA_OSCURO.classList.add(ESTADO_ACTIVE);
		BOTON_TEMA_OSCURO.firstElementChild.style.display = "grid";
	}
}
//* Activar filtro contraste
function filtroContraste() {
	if (document.body.classList.contains(FILTRO_CONTRASTE)) {
		document.body.classList.remove(FILTRO_CONTRASTE);
		BOTON_FILTRO_CONTRASTE.classList.remove(ESTADO_ACTIVE, ESTADO_CONTRASTE_1);
		window.localStorage.setItem(JS_ORIGEN + "filtroGuardado", "");
	} else {
		document.body.classList.remove(TEMA_CLARO);
		document.body.classList.add(FILTRO_CONTRASTE);
		BOTON_FILTRO_CONTRASTE.classList.add(ESTADO_CONTRASTE_1);
		window.localStorage.setItem(JS_ORIGEN + "filtroGuardado", FILTRO_CONTRASTE);
	}
	document.body.classList.remove(FILTRO_SATURACION_1, FILTRO_SATURACION_2, FILTRO_SATURACION_3);
	BOTON_FILTRO_SATURACION.classList.remove(ESTADO_ACTIVE, ESTADO_SATURACION_1, ESTADO_SATURACION_2, ESTADO_SATURACION_3);
}

//* Aplicar filtros de saturacion (3)
function filtroSaturacion() {
	switch (true) {
		case document.body.classList.contains(FILTRO_SATURACION_1):
			document.body.classList.remove(FILTRO_SATURACION_1);
			document.body.classList.add(FILTRO_SATURACION_2);
			window.localStorage.setItem(JS_ORIGEN + "filtroGuardado", FILTRO_SATURACION_2);
			BOTON_FILTRO_SATURACION.classList.add(ESTADO_SATURACION_2)
			BOTON_FILTRO_SATURACION.classList.remove(ESTADO_SATURACION_1);
			break;
		case document.body.classList.contains(FILTRO_SATURACION_2):
			document.body.classList.remove(FILTRO_SATURACION_2);
			document.body.classList.add(FILTRO_SATURACION_3);
			window.localStorage.setItem(JS_ORIGEN + "filtroGuardado", FILTRO_SATURACION_3);
			BOTON_FILTRO_SATURACION.classList.add(ESTADO_SATURACION_3)
			BOTON_FILTRO_SATURACION.classList.remove(ESTADO_SATURACION_2);
			break;
		case document.body.classList.contains(FILTRO_SATURACION_3):
			document.body.classList.remove(FILTRO_SATURACION_3);
			window.localStorage.setItem(JS_ORIGEN + "filtroGuardado", "");
			BOTON_FILTRO_SATURACION.classList.remove(ESTADO_ACTIVE, ESTADO_SATURACION_3);
			break;
		default:
			document.body.classList.add(FILTRO_SATURACION_1);
			document.body.classList.remove(FILTRO_CONTRASTE, FILTRO_SATURACION_2, FILTRO_SATURACION_3);
			window.localStorage.setItem(JS_ORIGEN + "filtroGuardado", FILTRO_SATURACION_1);
			BOTON_FILTRO_SATURACION.classList.add(ESTADO_ACTIVE, ESTADO_SATURACION_1);
			BOTON_FILTRO_SATURACION.classList.remove(ESTADO_SATURACION_3);
			break;
	}
	BOTON_FILTRO_CONTRASTE.classList.remove(ESTADO_ACTIVE, ESTADO_CONTRASTE_1);
}
//* Activar el gran cursor (Un mouse grande)
function granCursor() {
	BOTON_CURSOR.classList.toggle(ESTADO_ACTIVE);
	if (BOTON_CURSOR.classList.contains(ESTADO_ACTIVE)) {
		BOTON_CURSOR.firstElementChild.style.display = "grid";
		window.localStorage.setItem(JS_ORIGEN + "cursorGuardado", OPTION_CURSOR);
	} else {
		BOTON_CURSOR.firstElementChild.style.display = "none";
		window.localStorage.setItem(JS_ORIGEN + "cursorGuardado", "");
	}

	if (document.body.classList.contains(OPTION_CURSOR)) {
		document.body.classList.remove(OPTION_CURSOR);
	} else {
		document.body.classList.add(OPTION_CURSOR);
	}
}
let mousePosition = { X: 0, Y: 0 };
//* Activar la guia de lectura (Una pantalla que me enfoca por donde va el mouse)
function guiaLectura() {
	BOTON_LECTURA.classList.toggle(ESTADO_ACTIVE);
	if (BOTON_LECTURA.classList.contains(ESTADO_ACTIVE)) {
		BOTON_LECTURA.firstElementChild.style.display = "grid";
		window.localStorage.setItem(JS_ORIGEN + "lecturaGuardada", OPTION_LECTURA);
	} else {
		BOTON_LECTURA.firstElementChild.style.display = "none";
		window.localStorage.setItem(JS_ORIGEN + "lecturaGuardada", "");
	}
	if (document.body.classList.contains(OPTION_LECTURA)) {
		document.body.classList.remove(OPTION_LECTURA);
		HTML_POSITION.removeEventListener("mousemove", locationHTML_POSITION);
		document.removeEventListener('mousemove', getMousePosition);
	} else {
		document.body.classList.add(OPTION_LECTURA);
		HTML_POSITION.addEventListener("mousemove", locationHTML_POSITION);
		document.addEventListener('mousemove', getMousePosition);
	}
}
function getMousePosition(e) {
	mousePosition = { X: e.clientX, Y: e.clientY };
}
//* Esto me da coordenadas de la posicion del mouse (Con esto trabaja guiaLectura)
function locationHTML_POSITION() {
	const totalX = screen.height;
	const gap = (24 * totalX) / 100;
	const gap1 = (10 * gap) / 100;
	const gap2 = (90 * gap) / 100;
	const firstLine = (mousePosition.Y - gap1) + VALOR_UNIDAD;
	const secondLine = (screen.height - mousePosition.Y - gap2) + VALOR_UNIDAD;
	HTML_POSITION.style.setProperty("--y", firstLine);
	HTML_POSITION.style.setProperty("--y-down", secondLine);
}
let lastDevicePixelRatio = window.devicePixelRatio;
function onZoomChange() {
	const currentDevicePixelRatio = window.devicePixelRatio;
	if (currentDevicePixelRatio !== lastDevicePixelRatio) {
		lastDevicePixelRatio = currentDevicePixelRatio;
		locationHTML_POSITION();
	}
}
//* Reiniciar funcionalidades del panel de accesibilidad
function widgetReinicio() {
	document.body.className = TEMA_CLARO;
	window.localStorage.setItem(JS_ORIGEN + "temaGuardado", TEMA_CLARO);
	window.localStorage.setItem(JS_ORIGEN + "filtroGuardado", "");
	window.localStorage.setItem(JS_ORIGEN + "cursorGuardado", "");
	window.localStorage.setItem(JS_ORIGEN + "lecturaGuardada", "");
	window.localStorage.setItem(JS_ORIGEN + "menuGuardado", "");
	document.documentElement.style.fontSize = VALOR_BASE + VALOR_UNIDAD;
	window.localStorage.setItem(JS_ORIGEN + "fuenteGuardada", VALOR_BASE);
	fuenteGuardada = VALOR_BASE;
	reiniciarTemasActives();
	BOTON_FILTRO_CONTRASTE.className = "btn";
	BOTON_FILTRO_SATURACION.className = "btn";
	BOTON_CURSOR.classList.remove(ESTADO_ACTIVE);
	BOTON_LECTURA.classList.remove(ESTADO_ACTIVE);
	if (BOTON_CURSOR.classList == ESTADO_ACTIVE) {
		BOTON_CURSOR.firstElementChild.style.display = "grid";
	} else {
		BOTON_CURSOR.firstElementChild.style.display = "none";
	}
	if (BOTON_LECTURA.classList == ESTADO_ACTIVE) {
		BOTON_LECTURA.firstElementChild.style.display = "grid";
	} else {
		BOTON_LECTURA.firstElementChild.style.display = "none";
	}
	BOTON_TEMA_CLARO.classList.add(ESTADO_ACTIVE);
	BOTON_TEMA_CLARO.firstElementChild.style.display = "grid";
	// Reiniciar los eventos
	document.removeEventListener('mousemove', getMousePosition);
}
//* Remueve las clases light-theme y dark-theme del body
function reiniciarTemas() {
	document.body.classList.remove(TEMA_CLARO);
	document.body.classList.remove(TEMA_OSCURO);
}
//* Reiniciar los estilos de funcionalidades activas en el panel de accesibilidad
function reiniciarTemasActives() {
	BOTON_TEMA_CLARO.classList.remove(ESTADO_ACTIVE);
	BOTON_TEMA_OSCURO.classList.remove(ESTADO_ACTIVE);
	BOTON_TEMA_CLARO.firstElementChild.style.display = "none";
	BOTON_TEMA_OSCURO.firstElementChild.style.display = "none";
}
// SI EXISTE, HAGALE
if (ABRIR_WIDGET.length) {
	ABRIR_WIDGET.forEach(btn => {
		btn.addEventListener("click", (event) => {
			event.stopPropagation();
			WIDGET_ACCESS.classList.toggle("open");
		});
	});
}
// *Cerrar Widget de Accesibilidad
if (CERRAR_WIDGET.length) {
	CERRAR_WIDGET.forEach(btn => {
		btn.addEventListener("click", () => {
			WIDGET_ACCESS.classList.remove("open");
			estadoWidget();
		});
	});
}
// *Cerrar con click por fuera, Widget de Accesibilidad
if (WIDGET_ACCESS) {
	document.addEventListener('click', function (event) {
		if (WIDGET_ACCESS.classList.contains("open")) {
			if (event.target !== WIDGET_ACCESS && !WIDGET_ACCESS.contains(event.target) && event.target !== ABRIR_WIDGET.length) {
				WIDGET_ACCESS.classList.remove("open");
				if (BUTTON_MENU_ANCHOR && !BUTTON_MENU_ANCHOR.contains(event.target)) {
					WIDGET_ACCESS.classList.remove("open");
				}
			}
			estadoWidget();
		}
	});
}
// *Aumentar fuenteGuardada
if (BOTON_MAS) {
	BOTON_MAS.addEventListener("click", () => {
		cambiarFuente("aumentar");
	});
}
// *Disminuir fuenteGuardada
if (BOTON_MENOS) {
	BOTON_MENOS.addEventListener("click", () => {
		cambiarFuente("disminuir");
	});
}
// *Activar Tema CLARO
if (BOTON_TEMA_CLARO) {
	BOTON_TEMA_CLARO.addEventListener("click", () => {
		temaClaro();
	});
}
// *Activar Tema OSCURO
if (BOTON_TEMA_OSCURO) {
	BOTON_TEMA_OSCURO.addEventListener("click", () => {
		temaOscuro();
	});
}
// *Activar Tema CONTRASTE
if (BOTON_FILTRO_CONTRASTE) {
	BOTON_FILTRO_CONTRASTE.addEventListener("click", () => {
		filtroContraste();
	});
}
// *Activar Filtros de Saturación
if (BOTON_FILTRO_SATURACION) {
	BOTON_FILTRO_SATURACION.addEventListener("click", () => {
		filtroSaturacion();
	});
}
// *Activar Gran Cursor
if (BOTON_CURSOR) {
	BOTON_CURSOR.addEventListener("click", () => {
		granCursor();
	});
}
// *Activar Lectura
if (BOTON_LECTURA) {
	BOTON_LECTURA.addEventListener("click", () => {
		guiaLectura();
	});
	// Tenga en cuenta el zoom
	window.addEventListener("resize", onZoomChange);
}
// *Reiniciar TEMA y FONT SIZE
if (BOTON_REINICIO) {
	BOTON_REINICIO.addEventListener("click", () => {
		widgetReinicio();
	});
}
///////////////////////////////////////   VALORES INICIALES  \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
////////////////////////// Establece el estado inicial de algunos elementos \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\

// *Establece el tema (CLARO, OSCURO o CONTRASTE).

var clasesInit = "";

if (temaGuardado) {
	clasesInit += temaGuardado + " ";
	document.body.className = clasesInit;
	switch (temaGuardado) {
		case TEMA_CLARO:
			BOTON_TEMA_CLARO.classList.add("active");
			BOTON_TEMA_CLARO.firstElementChild.style.display = "grid";
			break;
		case TEMA_OSCURO:
			BOTON_TEMA_OSCURO.classList.add("active");
			BOTON_TEMA_OSCURO.firstElementChild.style.display = "grid";
			break;
	}
} else {
	document.body.className = TEMA_CLARO;
}
if (filtroGuardado) {
	clasesInit += filtroGuardado + " ";
	document.body.className = clasesInit;
	switch (filtroGuardado) {
		case "contrast-filter":
			BOTON_FILTRO_CONTRASTE.classList.add("active", "contrast-1");
			break;
		case "saturation-filter-1":
			BOTON_FILTRO_SATURACION.classList.add("active", "saturation-1")
			break;
		case "saturation-filter-2":
			BOTON_FILTRO_SATURACION.classList.add("active", "saturation-2");
			break;
		case "saturation-filter-3":
			BOTON_FILTRO_SATURACION.classList.add("active", "saturation-3");
			break;
	}
}
// *Establece el valor de FONT SIZE para su uso en AUMENTAR o DISMINUIR fuenteGuardada
if (fuenteGuardada) {
	document.documentElement.style.fontSize = fuenteGuardada + "px";
} else {
	document.documentElement.style.fontSize = "8px";
	fuenteGuardada = 8;
}
if (cursorGuardado) {
	clasesInit += cursorGuardado + " ";
	document.body.className = clasesInit;
	BOTON_CURSOR.classList.add("active");
	BOTON_CURSOR.firstElementChild.style.display = "grid";
}
if (lecturaGuardada) {
	clasesInit += lecturaGuardada + " ";
	document.body.className = clasesInit;
	BOTON_LECTURA.classList.add("active");
	BOTON_LECTURA.firstElementChild.style.display = "grid";
	HTML_POSITION.addEventListener("mousemove", locationHTML_POSITION);
	document.addEventListener("mousemove", getMousePosition);
}
estadoWidget();
////////////////////////////////////////   NAVBAR   \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
///////////////// Con esto CAMBIO el TIPO DE MENU DE DESKTOP O MOBILEL \\\\\\\\\\\\\\\\\

const MENU__TOGGLE = document.querySelector(".menu-toggle");
const BUTTON_MENU_ANCHOR = document.querySelector(".menu-anchor");
const sidebar = document.querySelector("nav .sidebar");
const menu = document.querySelector("nav");
const menuMobile = document.querySelector('.menu-mobile');
const optionsMenuAnchor = document.querySelectorAll(".sidebar__content a");
const optionsMenu = document.querySelectorAll(".submenu__content a");
const optionsMainMenu = document.querySelectorAll("nav .nav__content .nav__main .menu-item");
const optionsSidebarMenu = document.querySelectorAll(".sidebar__content .menu-item");
const submenusAll = document.querySelectorAll(".submenu > .submenu__content");

let menuGuardado = window.localStorage.getItem(JS_ORIGEN + "menuGuardado");

// abrir menus flotantes
// Elemento que activa el selector de idiomas
const NAV_USER = document.querySelector('.nav__user__button');
// Contenedor del selector de idiomas
const NAV_USER_DROPDOWN = document.querySelector('.nav__user__dropdown');
let isNavUserExpanded = false;

// Anclar y desanclar menu
function anchorMenu() {
	BUTTON_MENU_ANCHOR.addEventListener("click", function () {
		if (sidebar.classList.contains("anchor-menu")) {
			sidebar.classList.remove("anchor-menu");
			sidebar.classList.add("no-anchor-menu");
			menuGuardado = "no-anchor-menu";
		} else {
			sidebar.classList.remove("no-anchor-menu");
			sidebar.classList.add("anchor-menu");
			menuGuardado = "anchor-menu";
		}

		window.localStorage.setItem(JS_ORIGEN + "menuGuardado", menuGuardado);
	});
}
// Abrir y Cerrar Menu Mobile
function openMenuMobile() {
	menu.classList.add("menu-mobile");
	MENU__TOGGLE.querySelector("i").className = "icon-closed";

	if (!menu.classList.contains('menu-is-open')) {
		menu.classList.add('menu-is-open');
	}
	closeWhitoutTarget();
}
function closeMenuMobile() {
	menu.classList.remove("menu-mobile", "menu-is-open");
	MENU__TOGGLE.querySelector("i").className = "icon-menu";
	closeSubmenusAll();
}
function closeSubmenusAll() {
	submenusAll.forEach(submenu => {
		submenu.classList.remove('active');
		submenu.setAttribute('aria-hidden', 'true');
	});
}

// Opener menu mobile
if (MENU__TOGGLE) {
	MENU__TOGGLE.addEventListener("click", function () {
		if (menu.classList.contains("menu-mobile")) {
			closeMenuMobile();
		} else {
			openMenuMobile();
		}
	});
}
// *Activar MENU ANCLADO
if (BUTTON_MENU_ANCHOR) {
	anchorMenu();
}
function showSubmenu(item, submenu) {
	const isOpen = submenu.classList.contains('active');
	submenu.classList.toggle('active');
	submenu.setAttribute('aria-hidden', isOpen ? 'true' : 'false');
	item.setAttribute('aria-expanded', isOpen ? 'false' : 'true');
}
//Ocultar menu al hacer click en una opcion principal
if (optionsMenuAnchor) {
	optionsMenuAnchor.forEach(function (option) {
		option.addEventListener('click', () => {            //Ocultar menu en Responsive
			if (menu.classList.contains("menu-mobile")) {
				closeMenuMobile();
			}
		});
	})
}
//Ocultar menu al hacer click en una opción de un submenu
if (optionsMenu) {
	optionsMenu.forEach(function (option) {
		option.addEventListener('click', () => {
			//Ocultar menu en Desktop
			option.closest(".submenu").classList.add('no-hover');
			setTimeout(() => {
				option.closest(".submenu").classList.remove('no-hover');
			}, 1000);

			//Ocultar menu en Responsive
			if (menu.classList.contains("menu-mobile")) {
				closeMenuMobile();
			}
		});
	})
}
// Ocultar menu mobile al hacer click fuera del menu  
function closeWhitoutTarget() {
	function handleClickOutside(event) {
		if (!menu.contains(event.target) && !MENU__TOGGLE.contains(event.target) && !WIDGET_ACCESS.contains(event.target)) {
			closeMenuMobile();
			document.removeEventListener("click", handleClickOutside);
		}
	}

	setTimeout(() => {
		document.addEventListener("click", handleClickOutside);
	}, 0);
}

// FUNCION PARA ABRIR EL MENU DE USUARIO
function openNavUser() {
	isNavUserExpanded = true;
	NAV_USER.setAttribute("aria-expanded", "true");
	NAV_USER_DROPDOWN.classList.remove("nav__user__dropdown--hidden");
	// CERRAR CON CLICK POR FUERA DEL MENU DE USUARIO
	document.addEventListener("click", function (event) {
		if (!NAV_USER.contains(event.target) && !NAV_USER_DROPDOWN.contains(event.target)) {
			closeNavUser();
		}
	});
}
// FUNCION PARA CERRAR EL MENU DE USUARIO
function closeNavUser() {
	isNavUserExpanded = false;
	NAV_USER.setAttribute("aria-expanded", "false");
	NAV_USER_DROPDOWN.classList.add("nav__user__dropdown--hidden");
}
// FUNCION PARA TOGGLE DEL MENU DE USUARIO
function toggleNavUser() {
	// Comprobar si el selector está abierto o cerrado, Si está cerrado, abrirlo; si está abierto, cerrarlo
	if (!isNavUserExpanded) {
		openNavUser();
	} else {
		closeNavUser();
	}
}
// FUNCION PARA INICIALIZAR EL MENU DE USUARIO
function initNavUser() {
	// Opener language selector
	if (NAV_USER && NAV_USER_DROPDOWN) {
		closeNavUser();
	}
}

window.addEventListener("resize", function () {
	if (menu) {
		if (menu.classList.contains("menu-mobile")) {
			// Cerrar menu
			closeMenuMobile();
			// Cerrar WIDGET si está abierto
			WIDGET_ACCESS.classList.remove("open");
			estadoWidget();
		}
	}
});
// Accesibilidad recorrer menu
optionsSidebarMenu.forEach(optionsSidebarMenu => {
	const item = optionsSidebarMenu.querySelector(".item");
	const submenu = optionsSidebarMenu.querySelector(".submenu > .submenu__content");

	if (!item || !submenu) return;

	// KEYDOWN: siempre activo
	item.addEventListener("keydown", event => {
		if (event.key === "Enter") {
			event.preventDefault();
			showSubmenu(item, submenu);
		}
	});

	// CLICK: solo si el menú está abierto
	item.addEventListener("click", event => {
		if (!menu.classList.contains("menu-is-open")) return;
		event.preventDefault();
		showSubmenu(item, submenu);
	});
});

initNavUser();
///////////////////////////////////////   VALORES INICIALES  \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
////////////////////////// Establece el estado inicial de algunos elementos \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\

// Establece SI el menu es ANCLADO.
if (sidebar) {
	if (menuGuardado) {
		sidebar.classList.add(menuGuardado);
	} else {
		sidebar.classList.add("anchor-menu");
	}
}
const modalBg = document.querySelector('dialog.modal-bg');
const btnModalClose = document.querySelectorAll(".modal-close");
const focusables = document.querySelectorAll('nav, section#widget-access, main, aside, footer');
// Funcion para abrir el modal
function openModal() {
	inertElements();
	modalBg.setAttribute('open', '');
	modalBg.setAttribute('aria-modal', 'true');
	modalBg.focus();
}
// Funcion para cerrar el modal
function closeModal() {
	inertElements();
	modalBg.removeAttribute('open');
	modalBg.setAttribute('aria-modal', 'false');
}
// Funcionar para quitar tabIndex en elementos que no sean el modal
function inertElements() {
	focusables.forEach(element => {
		if (element.hasAttribute('inert')) {
			element.removeAttribute('inert');
		} else {
			element.setAttribute('inert', '');
		}
	});
}
// Cerrar Modal
if (btnModalClose) {
	btnModalClose.forEach(element => {
		element.addEventListener('click', () => {
			closeModal();
		});
	});
}



// // Cerrar Alertas
// const btnAlertClose = document.querySelectorAll(".alert__close");

// // Funcion para cerrar el modal
// function closeAlert(element) {
// 	const alert = element.closest('.alert');
// 	if(alert){
// 		alert.style.transform = 'translateX(calc(100% + 4rem))';
// 		setTimeout(() => {
// 			alert.remove();
// 		}, 200); // mismo tiempo que el transition en CSS
// 	}
// }

// // Cerrar Alerta
// if (btnAlertClose) {
// 	btnAlertClose.forEach(element => {
// 		element.addEventListener('click', () => {
// 			closeAlert(element);
// 		});
// 	});
// }
let slideIndex = 1;
let slides = document.querySelectorAll(".slider__images img");

function plusSlides(n) {
	showSlides(slideIndex += n);
}

function currentSlides(n) {
	showSlides(slideIndex = n);
}

function createDots() {
	let dotsContainer = document.querySelector(".slider__dots");
	for (let j = 0; j < slides.length; j++) {
		let dot = document.createElement("span");
		dot.classList.add("dot");
		dot.setAttribute("onclick", "currentSlides(" + (j + 1) + ")");
		dotsContainer.appendChild(dot);
	}
}

function showSlides(n) {
	let i;
	let dots = document.getElementsByClassName("dot");
	if (n > slides.length) {
		slideIndex = 1;
	}
	if (n < 1) {
		slideIndex = slides.length;
	}
	for (i = 0; i < slides.length; i++) {
		slides[i].style.display = "none";
	}
	for (i = 0; i < dots.length; i++) {
		dots[i].className = dots[i].className.replace(" active", "");
	}
	slides[slideIndex - 1].style.display = "block";
	dots[slideIndex - 1].className += " active";
}

const slider = document.querySelector(".slider");

if (slider) {
	createDots();
	showSlides(slideIndex);
	setInterval(function () { plusSlides(1) }, 5000);
}
//// Creación de combobox personalizado a partir de un select HTML

// Funcion que convierte un select en un combobox personalizado
function FromSelectToCombobox(select) {
	// Ocultar select origin
	select.hidden = true;

	// Obterner clase del select para aplicarla al combobox
	const classList = select.classList;
	// Obtener textos personalizados desde data-attributes o usar valores por defecto
	const {
		text = 'Select an option',
		ariaLabel = '',
		ariaLabelOpen = 'Search box and list',
		ariaLabelInput = 'Search options',
		placeholder = 'Search…',
		emptyText = 'No results found'
	} = select.dataset;

	// Crear estructura combobox
	const combobox = document.createElement('div');
	combobox.className = classList.value;
	combobox.dataset.combobox = '';
	combobox.dataset.emptyText = emptyText;
	combobox.setAttribute('role', 'combobox');
	combobox.setAttribute('aria-label', ariaLabel);
	combobox.setAttribute('aria-haspopup', 'listbox');
	combobox.setAttribute('aria-expanded', 'false');
	combobox._select = select;

	combobox.innerHTML = `
    <button class="combobox__trigger" type="button">
      <span class="combobox__text">${text}</span>
      <i class="icon-expand" aria-hidden="true"></i>
    </button>

    <div class="combobox__panel hidden" role="group" aria-label="${ariaLabelOpen}">
      <input
        class="combobox__input"
        type="text"
        role="searchbox"
        aria-label="${ariaLabelInput}"
        placeholder="${placeholder}" />
      <i class="icon-search combobox__icon" aria-hidden="true"></i>
      <div class="combobox__list"></div>
    </div>
  `;

	// Rellenar opciones
	buildOptions(select, combobox);

	// Insertar comobox después del select
	select.after(combobox);
}

// Funcion quue decide qué tipo de opciones construir (agrupadas o no)
function buildOptions(select, combobox) {
	const listContainer = combobox.querySelector('.combobox__list');
	const hasGroups = select.querySelector('optgroup');

	if (hasGroups) {
		listContainer.appendChild(buildGroupedOptions(select));
	} else {
		listContainer.appendChild(buildFlatOptions(select));
	}
}

// Funcion que construye opciones sin agrupar
function buildFlatOptions(select) {
	const list = document.createElement('ul');
	list.className = 'combobox__list-options';
	list.setAttribute('role', 'listbox');

	[...select.options].forEach(option => {
		const li = document.createElement('li');
		li.className = 'combobox__option';
		li.setAttribute('role', 'option');
		li.textContent = option.textContent;
		li.setAttribute('value', option.value);
		// li.dataset.value = option.value;
		li.dataset.index = option.index;

		list.appendChild(li);
	});

	return list;
}

// Funcion que construye opciones agrupadas
function buildGroupedOptions(select) {
	const groups = document.createElement('ul');
	groups.className = 'combobox__list-groups';
	groups.setAttribute('role', 'listbox');

	[...select.children].forEach(child => {
		if (child.tagName === 'OPTGROUP') {
			groups.appendChild(createGroup(child));
		}

		if (child.tagName === 'OPTION') {
			// opción suelta fuera de grupo
			const tempGroup = document.createElement('li');
			tempGroup.className = 'combobox__group';
			tempGroup.setAttribute('role', 'group');

			const list = document.createElement('ul');
			list.className = 'combobox__list-options';
			list.setAttribute('role', 'listbox');

			list.appendChild(createOption(child));
			tempGroup.appendChild(list);
			groups.appendChild(tempGroup);
		}
	});

	return groups;
}

// Funcion que crea un grupo de opciones reutilizable
function createGroup(optgroup) {
	const group = document.createElement('li');
	group.className = 'combobox__group';
	group.setAttribute('role', 'group');
	group.setAttribute('aria-label', optgroup.label);

	const label = document.createElement('label');
	label.className = 'combobox__group-label';
	label.textContent = optgroup.label;

	const list = document.createElement('ul');
	list.className = 'combobox__list-options';
	list.setAttribute('role', 'listbox');

	[...optgroup.children].forEach(option => {
		if (option.tagName === 'OPTION') {
			list.appendChild(createOption(option));
		}
	});

	group.append(label, list);
	return group;
}

// Funcion que crea una opción individual reutilizable
function createOption(option) {
	const li = document.createElement('li');
	li.className = 'combobox__option';
	li.setAttribute('role', 'option');
	li.textContent = option.textContent;
	li.setAttribute('value', option.value);
	// li.value = option.value;
	li.dataset.index = option.index;

	return li;
}

// Inicialización para creación de combobox desde selects con clase .combobox
document.querySelectorAll('select.combobox').forEach(FromSelectToCombobox);

// Función que crea combobox por ID
function createComboboxById(id) {
	const select = document.getElementById(id);
	if (!select) return;
	FromSelectToCombobox(select);
	new ComboBox(select.nextElementSibling);
}

// Funcion para disparar evento change si cambia el value del combobox
function setSelectValue(id, value) {
	const select = document.getElementById(id);
	if (!select) {
		return;
	}
	select.value = value;
	select.dispatchEvent(new Event('change', { bubbles: true }));
}

// Hacer que el combobox funcione
class ComboBox {
	constructor(root) {
		this.root = root;
		this.button = root.querySelector('.combobox__trigger');
		this.buttonText = root.querySelector('.combobox__text');
		this.panel = root.querySelector('.combobox__panel');
		this.input = root.querySelector('input');
		this.list = root.querySelector('[role=listbox]');
		this.groups = [...this.list.querySelectorAll('[role=group]')];
		this.options = [...this.list.querySelectorAll('[role=option]')];

		// Guardar texto original de cada opción para búsquedas
		this.options.forEach(option => {
			option.dataset.label = option.textContent;
		});

		// Texto de NO HAY RESULTADOS
		this.emptyText = root.dataset.emptyText || 'No results found';

		// Índice de la opción, ninguna al inicio
		this.activeIndex = -1;
		this.selectedIndex = -1;

		// Tomar selected del select original y sincronizar con combobox
		this.selectEl = root._select;
		this.selectedIndex = this.selectEl.selectedIndex;

		if (this.selectedIndex >= 0) {
			const option = this.options[this.selectedIndex];
			option.setAttribute('aria-selected', 'true');
			this.buttonText.textContent = option.textContent;
		}

		// Escuchar cambios en el select original
		this.selectEl.addEventListener('change', () => {
			const index = this.selectEl.selectedIndex;
			if (index < 0) return;

			this.options.forEach(opt =>
				opt.removeAttribute('aria-selected')
			);

			const option = this.options[index];
			option.setAttribute('aria-selected', 'true');
			this.buttonText.textContent = option.textContent;
			this.selectedIndex = index;
		});

		// Conecta todos los listeners de eventos
		this.bindEvents();
	}

	// Subrayar coincidencias de busqueda en el texto
	highlight(text, query) {
		if (!query) return text;
		const normalizedQuery = this.normalize(query);

		let result = '';
		let queryIndex = 0;

		for (let i = 0; i < text.length; i++) {
			const originalChar = text[i];
			const normalizedChar = this.normalize(originalChar);

			if (
				queryIndex < normalizedQuery.length &&
				normalizedChar === normalizedQuery[queryIndex]
			) {
				result += `<mark>${originalChar}</mark>`;
				queryIndex++;
			} else {
				result += originalChar;
			}
		}
		return result;
	}

	// Normaliza texto quitando tildes y pasando a minúsculas, para busquedas
	normalize(text) {
		return text
			.normalize('NFD')
			.replace(/[\u0300-\u036f]/g, '')
			.toLowerCase();
	}

	// Evidencia si el combobox está abierto
	isOpen() {
		return this.root.getAttribute('aria-expanded') === 'true';
	}

	// Abrir / cerrar panel
	open() {
		this.panel.classList.remove('hidden');
		this.root.setAttribute('aria-expanded', 'true');
		if (this.selectedIndex >= 0) {
			const selected = this.options[this.selectedIndex];
			selected.scrollIntoView({ block: 'nearest' });
		}
		this.input.focus();
	}
	close() {
		this.panel.classList.add('hidden');
		this.root.setAttribute('aria-expanded', 'false');
		this.input.value = '';
		this.filter('');
		this.clearActive();
	}

	// Mostrar / ocultar mensaje de NO HAY RESULTADOS
	showEmpty() {
		if (!this.list.querySelector('.empty')) {
			const li = document.createElement('li');
			li.className = 'empty';
			li.textContent = this.emptyText;
			this.list.appendChild(li);
		}
	}
	removeEmpty() {
		const empty = this.list.querySelector('.empty');
		if (empty) empty.remove();
	}

	// Limpia la opción activa y reestablece el índice
	clearActive() {
		this.options.forEach((opt, i) => {
			if (i !== this.selectedIndex) {
				opt.removeAttribute('aria-selected');
			}
		});
		this.activeIndex = -1;
	}

	// Agrega o remueve clase hidden segun filtro 
	filter(value) {
		const query = this.normalize(value);
		let totalVisibleOptions = 0;

		// Eliminar mensaje de NO HAY RESULTADOS
		this.removeEmpty();

		// Filtrar opciones
		this.options.forEach(option => {
			const label = option.dataset.label;
			const match = this.normalize(label).includes(query);
			option.classList.toggle('hidden', !match);

			if (match) {
				totalVisibleOptions++;
				// Subrayar coincidencias
				option.innerHTML = query ? this.highlight(label, query) : label;
			} else {
				// Restaurar texto original
				option.textContent = label;
			}
		});

		// Muestra / oculta grupos
		this.groups.forEach(group => {
			const groupOptions = [...group.querySelectorAll('[role=option]')];
			const hasVisible = groupOptions.some(
				opt => !opt.classList.contains('hidden')
			);

			group.classList.toggle('hidden', !hasVisible);
		});

		if (totalVisibleOptions === 0) {
			this.showEmpty();
		}

		this.clearActive();
	}

	// Obtiene solo las opciones visibles, no ocultas por filtro
	getVisibleOptions() {
		return this.options.filter(
			option =>
				!option.classList.contains('hidden') &&
				!option.closest('[role=group]')?.classList.contains('hidden')
		);
	}

	// Establece la opción activa por índice dentro de las opciones visibles [Necesito entenderlo mejor]
	setActive(index) {
		const visible = this.getVisibleOptions();
		if (!visible.length) return;

		this.clearActive();

		const option = visible[index];
		if (!option) return;

		option.setAttribute('aria-selected', 'true');
		option.scrollIntoView({ block: 'nearest' });
		this.activeIndex = index;
	}

	// Muestra la opción seleccionada en el botón y cierra el panel
	select(option) {
		// Limpiar selección anterior
		this.options.forEach(opt =>
			opt.removeAttribute('aria-selected')
		);

		// Guardar selección
		this.selectedIndex = this.options.indexOf(option);
		option.setAttribute('aria-selected', 'true');

		// Actualizar botón
		this.buttonText.textContent = option.textContent;

		// Si cambia combobox, cambie el select original
		this.selectEl.selectedIndex = this.selectedIndex;
		this.selectEl.dispatchEvent(new Event('change', { bubbles: true }));

		this.close();
		this.button.focus();
	}


	// Listeners de eventos 
	bindEvents() {
		// Toggle botón
		this.button.addEventListener('click', () => {
			this.isOpen() ? this.close() : this.open();
		});

		// Busqueda por input
		this.input.addEventListener('input', e => {
			this.filter(e.target.value);
		});

		// Navegación por teclado
		this.input.addEventListener('keydown', e => {
			const visible = this.getVisibleOptions();
			if (!visible.length) return;

			if (e.key === 'ArrowDown') {
				e.preventDefault();
				this.setActive((this.activeIndex + 1) % visible.length);
			}

			if (e.key === 'ArrowUp') {
				e.preventDefault();
				this.setActive(
					(this.activeIndex - 1 + visible.length) % visible.length
				);
			}

			if (e.key === 'Enter' && this.activeIndex >= 0) {
				e.preventDefault();
				this.select(visible[this.activeIndex]);
			}

			if (e.key === 'Escape') {
				this.close();
				this.button.focus();
			}
		});

		// Selección de opción
		this.options.forEach(option => {
			option.addEventListener('click', () => this.select(option));
		});

		// Cerrar al hacer click fuera
		document.addEventListener('click', e => {
			if (!this.root.contains(e.target)) {
				this.close();
			}
		});
	}
}

// Inicialización par funcionamiento de comboboxes
document.querySelectorAll('[data-combobox]').forEach(el => new ComboBox(el));
// Cerrar Alertas
const btnAlertClose = document.querySelectorAll(".alert__close");

// Funcion para cerrar el modal
function closeAlert(target) {

	let alert;

	if (target instanceof HTMLElement) {
		alert = target.closest('.alert');
	}
	else if (typeof target === 'string') {
		alert = document.querySelector(target);
	}

	if (alert) {
		alert.style.transform = 'translateX(calc(100% + 4rem))';
		setTimeout(() => {
			alert.remove();
		}, 200); // mismo tiempo que el transition en CSS
	}
}

// Cerrar Alerta
if (btnAlertClose) {
	btnAlertClose.forEach(element => {
		element.addEventListener('click', () => {
			closeAlert(element);
		});
	});
}
