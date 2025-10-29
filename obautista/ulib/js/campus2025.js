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

// FUNCION PARA INICIALIZAR EL SELECTOR DE IDIOMAS
function initLanguageSelector() {
	// Opener language selector
	if (LANGUAGE_SELECTOR && LANGUAGE_SELECTOR_DROPDOWN) {
		closeLanguageSelector();
	}
	// Comprobar si el idioma ya está almacenado
	if (currentLangCampus && LANGUAGE_SELECTOR_DROPDOWN) {
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
			const initialTarget = event.target.parentElement ? event.target.parentElement : event.target;
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

function changeLanguage(idioma = "es") {
	xajax_iDefinirIdioma(idioma);
	setTimeout(() => {
		location.reload();
	}, 500);
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
const ABRIR_WIDGET = document.getElementById("open-widget-access");
const ABRIR_WIDGET_SIDEBAR = document.getElementById("open-widget-access--sidebar");
const CERRAR_WIDGET = document.getElementById("closed-widget-access");
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
	activeElements = WIDGET_ACCESS.querySelectorAll(".widget__content .active");
	if (activeElements.length === 1 && activeElements[0].id === "boton-claro" && document.documentElement.style.fontSize == VALOR_BASE + VALOR_UNIDAD) {
		WIDGET_ACCESS.classList.remove(ESTADO_ACTIVE);
	} else {
		WIDGET_ACCESS.classList.add(ESTADO_ACTIVE);
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
// *Abrir Widget de Accesibilidad
if (ABRIR_WIDGET) {
	ABRIR_WIDGET.addEventListener("click", (event) => {
		event.stopPropagation();
		WIDGET_ACCESS.classList.toggle("open");
	})
}
if (ABRIR_WIDGET_SIDEBAR) {
	ABRIR_WIDGET_SIDEBAR.addEventListener("click", (event) => {
		event.stopPropagation();
		WIDGET_ACCESS.classList.toggle("open");
	})
}
// *Cerrar Widget de Accesibilidad
if (CERRAR_WIDGET) {
	CERRAR_WIDGET.addEventListener("click", () => {
		WIDGET_ACCESS.classList.remove("open");
		estadoWidget();
	})
}
// *Cerrar con click por fuera, Widget de Accesibilidad
if (WIDGET_ACCESS) {
	document.addEventListener('click', function (event) {
		if (WIDGET_ACCESS.classList.contains("open")) {
			if (event.target !== WIDGET_ACCESS && !WIDGET_ACCESS.contains(event.target) && event.target !== ABRIR_WIDGET && event.target !== ABRIR_WIDGET_SIDEBAR) {
				WIDGET_ACCESS.classList.remove("open");
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
const sidebar = document.querySelector("nav > .sidebar");
const menu = document.querySelector("nav");
const menuMobile = document.querySelector('.menu-mobile');
const optionsMenu = document.querySelectorAll(".submenu__content a");

let menuGuardado = window.localStorage.getItem(JS_ORIGEN + "menuGuardado");


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

	const menuItem = document.querySelectorAll("nav.menu-mobile .menu-item");

	if (menuItem) {
		menuItem.forEach(function (menuItem) {
			const item = menuItem.querySelector(".item");
			if (item) {
				const submenu = menuItem.querySelector(".submenu > .submenu__content");
				if (submenu) {
					const toggleSubmenu = function (e) {
						e.preventDefault();
						showSubmenu(item, submenu);
					}

					// Mostrar con click
					item.addEventListener('click', function (event) {
						closeAllSubmenusExceptCurrent(event);
						toggleSubmenu(event);
					});
					// Mostrar con Enter
					item.addEventListener('keydown', function (event) {
						if (event.key === 'Enter') {
							closeAllSubmenusExceptCurrent(event);
							toggleSubmenu(event);
						}
					});

					// Cerrar todos los submenús abiertos excepto el actual
					const closeAllSubmenusExceptCurrent = function () {
						const allItems = document.querySelectorAll('.item');
						const allSubmenus = document.querySelectorAll('.submenu__content.active');
						allSubmenus.forEach(activeSubmenu => {
							if (activeSubmenu !== submenu) {
								const parentItem = activeSubmenu.parentElement.parentElement.querySelector('.item');
								showSubmenu(parentItem, activeSubmenu);
							}
						});
					};
				}
			}
		});
	}

	console.log(menuItem[1]);



	closeWhitoutTarget();
}
function closeMenuMobile() {
	menu.classList.remove("menu-mobile");
	MENU__TOGGLE.querySelector("i").className = "icon-menu";
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

// if(menu){
// closeMenuMobile();
// }


function showSubmenu(item, submenu) {
	const isOpen = submenu.classList.contains('active');
	submenu.classList.toggle('active');
	submenu.setAttribute('aria-hidden', isOpen ? 'true' : 'false');
	item.setAttribute('aria-expanded', isOpen ? 'false' : 'true');
}


// Mostrar Submenu en Menu Mobile
// if(menuItem){
//     menuItem.forEach(function(menuItem) {
//         const item = menuItem.querySelector(".item");
//         if(item){
//             const submenu = menuItem.querySelector(".submenu > .submenu__content");
//             if(submenu){
//                 const toggleSubmenu = function(e){
//                     e.preventDefault();
//                     showSubmenu(item, submenu);
//                 }

//                 // Mostrar con click
//                 item.addEventListener('click', function(event){
//                     closeAllSubmenusExceptCurrent(event);
//                     toggleSubmenu(event);
//                 });
//                 // Mostrar con Enter
//                 item.addEventListener('keydown', function(event){
//                     if (event.key === 'Enter'){
//                         closeAllSubmenusExceptCurrent(event);
//                         toggleSubmenu(event);
//                     }
//                 });

//                 // Cerrar todos los submenús abiertos excepto el actual
//                 const closeAllSubmenusExceptCurrent = function () {
//                     const allItems = document.querySelectorAll('.item');
//                     const allSubmenus = document.querySelectorAll('.submenu__content.active');
//                     allSubmenus.forEach(activeSubmenu => {
//                         if (activeSubmenu !== submenu) {
//                             const parentItem = activeSubmenu.parentElement.parentElement.querySelector('.item');
//                             showSubmenu(parentItem, activeSubmenu);
//                         }
//                     });
//                 };
//             }
//         }
//     });
// }
//Ocultar menu al hacer click en una opción
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
		if (!menu.contains(event.target) && !MENU__TOGGLE.contains(event.target)) {
			closeMenuMobile();
			document.removeEventListener("click", handleClickOutside);
		}
	}

	setTimeout(() => {
		document.addEventListener("click", handleClickOutside);
	}, 0);
}


window.addEventListener("resize", function () {
	if (menu) {
		if (menu.classList.contains("menu-mobile")) {
			closeMenuMobile();
		}
	}
});

// window.addEventListener("scroll", function () {
//     setTimeout(() => {
//         if (window.scrollY > 0) {
//             if(window.innerWidth >= 1025){
//                 banner.style.height = "6.5rem";
//                 sidebar.style.top = "calc(6.5rem + 6rem)";
//             }
//         } else {
//             if(window.innerWidth >= 1025){
//                 banner.style.height = "12.5rem";
//                 sidebar.style.top = "calc(12.5rem + 6rem)";
//             }
//         }
//     }, 200)
// });






// abrir menus flotantes
// Elemento que activa el selector de idiomas
const NAV_USER = document.querySelector('.nav__user__button');
// Contenedor del selector de idiomas
const NAV_USER_DROPDOWN = document.querySelector('.nav__user__dropdown');
let isNavUserExpanded = false;



// FUNCION PARA ABRIR EL SELECTOR DE IDIOMAS
function openNavUser() {
	isNavUserExpanded = true;
	console.log(NAV_USER);
	NAV_USER.setAttribute("aria-expanded", "true");
	console.log(NAV_USER);
	NAV_USER_DROPDOWN.classList.remove("nav__user__dropdown--hidden");
	console.log('Menu Usario abierto');
}
// FUNCION PARA CERRAR EL SELECTOR DE IDIOMAS
function closeNavUser() {
	isNavUserExpanded = false;
	NAV_USER.setAttribute("aria-expanded", "false");
	NAV_USER_DROPDOWN.classList.add("nav__user__dropdown--hidden");
}

// FUNCION PARA TOGGLE DEL SELECTOR DE IDIOMAS
function toggleNavUser() {
	console.log('Me están pulsando');
	// Comprobar si el selector está abierto o cerrado, Si está cerrado, abrirlo; si está abierto, cerrarlo
	if (!isNavUserExpanded) {
		console.log('Abriendo menu de usuario');
		openNavUser();
		// Cerrar el selector al hacer clic por fuera de él
		// document.addEventListener("click", (event) => {
		//     const initialTarget = event.target.parentElement ? event.target.parentElement : event.target;
		// 	closeOnClickOutside({
		// 		target: initialTarget,
		// 		sTrigger: NAV_USER,
		// 		sElement: NAV_USER_DROPDOWN,
		// 		bIsExpanded: true,
		// 		fParameters: () => {
		// 			closeNavUser();
		// 		}
		// 	});
		// });
	} else {
		closeNavUser();
	}
}

// FUNCION PARA INICIALIZAR EL SELECTOR DE IDIOMAS
function initNavUser() {
	// Opener language selector
	if (NAV_USER && NAV_USER_DROPDOWN) {
		closeNavUser();
	}
}

initNavUser();





// abrir menus flotantes
// Elemento que activa el selector de idiomas
const TOOLS = document.querySelector('.tools__button');
// Contenedor del selector de idiomas
const TOOLS_DROPDOWN = document.querySelector('.tools__dropdown');
let isToolsExpanded = false;



// FUNCION PARA ABRIR EL SELECTOR DE IDIOMAS
function openTools() {
	isNavUserExpanded = true;
	console.log(TOOLS);
	TOOLS.setAttribute("aria-expanded", "true");
	console.log(TOOLS);
	TOOLS_DROPDOWN.classList.remove("tools__dropdown--hidden");
	console.log('Caja de herramientas abierta');
}
// FUNCION PARA CERRAR EL SELECTOR DE IDIOMAS
function closeTools() {
	isToolsExpanded = false;
	TOOLS.setAttribute("aria-expanded", "false");
	TOOLS_DROPDOWN.classList.add("tools__dropdown--hidden");
}

// FUNCION PARA TOGGLE DEL SELECTOR DE IDIOMAS
function toggleTools() {
	console.log('Me están pulsando');
	// Comprobar si el selector está abierto o cerrado, Si está cerrado, abrirlo; si está abierto, cerrarlo
	if (!isToolsExpanded) {
		console.log('Abriendo caja de herramientas');
		openTools();
		// Cerrar el selector al hacer clic por fuera de él
		// document.addEventListener("click", (event) => {
		//     const initialTarget = event.target.parentElement ? event.target.parentElement : event.target;
		// 	closeOnClickOutside({
		// 		target: initialTarget,
		// 		sTrigger: TOOLS,
		// 		sElement: TOOLS_DROPDOWN,
		// 		bIsExpanded: true,
		// 		fParameters: () => {
		// 			closeNavUser();
		// 		}
		// 	});
		// });
	} else {
		console.log("Cerrando...");
		closeTools();
	}
}

// FUNCION PARA INICIALIZAR EL SELECTOR DE IDIOMAS
function initTools() {
	// Opener language selector
	if (TOOLS && TOOLS_DROPDOWN) {
		closeTools();
	}
}

initTools();
///////////////////////////////////////   VALORES INICIALES  \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
////////////////////////// Establece el estado inicial de algunos elementos \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\

// Establece SI el menu es ANCLADO.
if (sidebar) {
	if (menuGuardado) {
		sidebar.classList.add(menuGuardado);
	} else {
		// sidebar.classList.add("anchor-menu");
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
