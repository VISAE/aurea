/////////////////////////////////////////   ACCESIBILIDAD   \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
////////////////////// Esto hace funcionar los modos CLARO/OSCURO/CONTRASTE/FUENTE \\\\\\\\\\\\\\\\\\\\\\\\

const BOTON_TEMA_CLARO = document.getElementById("boton-claro");
const BOTON_TEMA_OSCURO = document.getElementById("boton-oscuro");
const BOTON_TEMA_CONTRASTE = document.getElementById("boton-contraste");
const BOTON_MAS = document.getElementById("boton-mas");
const BOTON_MENOS = document.getElementById("boton-menos");
const BOTON_REINICIO = document.getElementById("boton-reinicio");

const TEMA_CLARO = "light-theme";
const TEMA_OSCURO = "dark-theme";
const TEMA_CONTRASTE = "contrast-theme";

const BOTON_ANCLAR_MENU = document.getElementById("anchor-menu");
const OPCION_MENU = "anchor";

// *Tema Seleccionado
var temaGuardado = window.localStorage.getItem("temaGuardado");
// *Opción de menu seleccionado
var menuGuardado = window.localStorage.getItem("menuGuardado");
// *Tamaño Letra Seleccionado
var fuenteGuardada = window.localStorage.getItem("fuenteGuardada");

// *Activar Tema CLARO
if(BOTON_TEMA_CLARO){
    BOTON_TEMA_CLARO.addEventListener("click", () => {
        document.body.className = TEMA_CLARO;
        window.localStorage.setItem("temaGuardado", TEMA_CLARO);
    });
}

// *Activar Tema OSCURO
if(BOTON_TEMA_OSCURO){
    BOTON_TEMA_OSCURO.addEventListener("click", () =>{
        document.body.className = TEMA_OSCURO;
        window.localStorage.setItem("temaGuardado", TEMA_OSCURO);
    });
}

// *Activar Tema CONTRASTE
if(BOTON_TEMA_CONTRASTE){
    BOTON_TEMA_CONTRASTE.addEventListener("click", () =>{
        document.body.className = TEMA_CONTRASTE;
        window.localStorage.setItem("temaGuardado", TEMA_CONTRASTE);
    });
}

// *Aumentar fuenteGuardada
if(BOTON_MAS){
    BOTON_MAS.addEventListener("click", () => {
        if(fuenteGuardada !=10.5){
            fuenteGuardada = parseFloat(fuenteGuardada) + 0.5;
            document.documentElement.style.fontSize = fuenteGuardada + "px";
            window.localStorage.setItem("fuenteGuardada", fuenteGuardada);    
        };
    });
}

// *Disminuir fuenteGuardada
if(BOTON_MENOS){
    BOTON_MENOS.addEventListener("click", () => {
        if(fuenteGuardada != "5.5"){
            fuenteGuardada = parseFloat(fuenteGuardada) - 0.5;
            document.documentElement.style.fontSize = fuenteGuardada + "px";
            window.localStorage.setItem("fuenteGuardada", fuenteGuardada);
        };
    });
}

// *Reiniciar TEMA y FONT SIZE
if(BOTON_REINICIO){
    BOTON_REINICIO.addEventListener("click", () => {
        document.body.className = "light-theme";
        window.localStorage.setItem("temaGuardado", " ");
        window.localStorage.setItem("menuGuardado", "");
        document.documentElement.style.fontSize = "8px";
        window.localStorage.setItem("fuenteGuardada", "8");
        fuenteGuardada = 8;
    });
}

// *Activar MENU ANCLADO
if(BOTON_ANCLAR_MENU){
    BOTON_ANCLAR_MENU.addEventListener("click", () =>{
        const MENU_ANCLADO = document.querySelector("nav");
        MENU_ANCLADO.classList.toggle("anchor");
        var estadoMenu = MENU_ANCLADO.classList.contains("anchor");
    
        if(estadoMenu){
            menuGuardado = "anchor";
        }else{
            menuGuardado = "no-anchor";
        }
    
        window.localStorage.setItem("menuGuardado", menuGuardado);
    });
}

////////////////////////////////////////   BANNER Y NAVBAR   \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
///////////////// Con esto CAMBIO el TAMAÑO del BANNER y del NAVBAR cuando se hace SCROLL \\\\\\\\\\\\\\\\\

const routeMobile = document.querySelector('.route--mobile');
const backRouteMobile = document.querySelector('.back-route--mobile');
const breadcrumbMobile = document.querySelector('.breadcrumb--mobile');
const breadcrumbDesktop = document.querySelector('.breadcrumb--desktop');
const nav = document.querySelector('nav');
const main = document.querySelector("main");
const footer = document.querySelector("footer");

const optionsItems = document.querySelectorAll('.options__item');

window.addEventListener("scroll", function(){
        var banner = document.querySelector(".banner");
        banner.classList.toggle("banner--scroll",window.scrollY>0);

        var nav = document.querySelector(".nav");
        nav.classList.toggle("nav--scroll",window.scrollY>0);
})

if (window.innerWidth <= 767){

    // Alternar el breadcrumb mobile
    routeMobile.addEventListener('click', () => {
        breadcrumbMobile.style.left = "unset";
        breadcrumbDesktop.style.left = "unset";
        nav.style.left = "0";
    });

    // Alternar el breadcrumb mobile
    backRouteMobile.addEventListener('click', () => {
        breadcrumbMobile.style.left = "-100%";
        breadcrumbDesktop.style.left = "-100%";
        nav.style.left = "-23rem";
    });
}

// Desplegar menu MOBILE
if (window.innerWidth <= 767){
    optionsItems.forEach(function(item) {
        const optionLink = item.querySelector('.option');

        optionLink.addEventListener('click', function(e) {
            e.preventDefault();
            item.classList.toggle('open--mobile');
        });
    });
}

function valoresIniciales(){

    if (window.innerWidth <= 767){

        if (breadcrumbMobile.style.left == "unset"){
            nav.style.left = "0";
        } else{
            nav.style.left = "-23rem";
        }
        
    } else{
        breadcrumbMobile.style.left = "-100%";
        breadcrumbDesktop.style.left = "-100%";
        nav.style.left = "0";
    }
};


///////////////////////////////////////   AUTOMATIZACIONES   \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
//////////////////////////// Agrega contenido HTML en base a clases o ID \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\

//---------------------------------------------------------------------------------------------------------

// <stages-project>
// Del componente de etapas; Agrega información del estado del item
var stagesProject = document.querySelectorAll(".stages-project .stage .item");

if(stagesProject){
    stagesProject.forEach(element => {

        element.insertAdjacentHTML("afterbegin", "<i></i>")
        
        var estadoItem = element.querySelector(".item-title");
        var iconItem = element.querySelector("i");
        
        switch (element.className) {
            case 'item cola':
                estadoItem.insertAdjacentHTML("beforeend", '<b class="item-state">Estado: <span>En Cola</span></b>')
                break;

            case 'item borrador':
                estadoItem.insertAdjacentHTML("beforeend", '<b class="item-state">Estado: <span>Borrador</span></b>')
                break;

            case 'item disponible':
                estadoItem.insertAdjacentHTML("beforeend", '<b class="item-state">Estado: <span>Disponible</span></b>')
                break;

            case 'item proceso':
                estadoItem.insertAdjacentHTML("beforeend", '<b class="item-state">Estado: <span>En Proceso</span></b>')
                break;

            case 'item devuelta':
                estadoItem.insertAdjacentHTML("beforeend", '<b class="item-state">Estado: <span>Devuelta</span></b>')
                break;

            case 'item radicada':
                estadoItem.insertAdjacentHTML("beforeend", '<b class="item-state">Estado: <span>Radicada</span></b>')
                break;

            case 'item completed':
                iconItem.classList.add('iCheck');
                break;

            case 'item completo':
                iconItem.classList.add('iCheck');
                estadoItem.insertAdjacentHTML("beforeend", '<b class="item-state">Estado: <span>Completo</span></b>')
                
                break;

            case 'item descartada':
                iconItem.classList.add('iClosed');
                estadoItem.insertAdjacentHTML("beforeend", '<b class="item-state">Estado: <span>Descartada</span></b>')
                break;
            
            default:
                break;
        }
    });
}
// </stages-project>

//---------------------------------------------------------------------------------------------------------

// <i>
// Agrega un SVG con el icono, en base a su clase
const icons = document.querySelectorAll('i');

const iAdd = '<svg viewBox="0 -960 960 960"><path d="M427-428H168v-106h259v-259h106v259h259v106H533v259H427v-259Z"/></svg>';
const iArrowBack = '<svg viewBox="0 -960 960 960"><path d="m313-440 224 224-57 56-320-320 320-320 57 56-224 224h487v80H313Z"/></svg>';
const iAttach = '<svg viewBox="0 -960 960 960"><path d="M460.09-52Q357-52 284.5-123.055 212-194.109 212-296v-441q0-73.9 51.712-125.95Q315.425-915 389.062-915q74.637 0 126.288 52.05Q567-810.9 567-736v399q0 45-31 76.5T459.5-229q-45.5 0-76-33T353-342v-397h63v400q0 19.875 12.805 33.938Q441.611-291 460.105-291q18.495 0 31.195-13.562Q504-318.125 504-337v-400q0-48-33.5-81T389-851q-48 0-81.5 33T274-737v442.655q0 76.317 54.376 128.331Q382.752-114 459.876-114 538-114 592-166.514t54-129.831V-739h63v442q0 101.891-72.91 173.445Q563.179-52 460.09-52Z"/></svg>';
const iCancel = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><g id="SVGRepo_bgCarrier" stroke-width="0"/><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"/><g id="SVGRepo_iconCarrier"> <path d="M18.53 9L13 3.47C12.8595 3.32931 12.6688 3.25018 12.47 3.25H8C7.27065 3.25 6.57118 3.53973 6.05546 4.05546C5.53973 4.57118 5.25 5.27065 5.25 6V18C5.25 18.7293 5.53973 19.4288 6.05546 19.9445C6.57118 20.4603 7.27065 20.75 8 20.75H16C16.7293 20.75 17.4288 20.4603 17.9445 19.9445C18.4603 19.4288 18.75 18.7293 18.75 18V9.5C18.7421 9.3116 18.6636 9.13309 18.53 9ZM13.25 5.81L16.19 8.75H13.25V5.81ZM16 19.25H8C7.66848 19.25 7.35054 19.1183 7.11612 18.8839C6.8817 18.6495 6.75 18.3315 6.75 18V6C6.75 5.66848 6.8817 5.35054 7.11612 5.11612C7.35054 4.8817 7.66848 4.75 8 4.75H11.75V9.5C11.7526 9.69811 11.8324 9.88737 11.9725 10.0275C12.1126 10.1676 12.3019 10.2474 12.5 10.25H17.25V18C17.25 18.3315 17.1183 18.6495 16.8839 18.8839C16.6495 19.1183 16.3315 19.25 16 19.25Z" fill="#000000"/> <path d="M14.47 11.9101C14.312 11.7893 14.1134 11.7344 13.9158 11.7568C13.7183 11.7791 13.537 11.8771 13.41 12.0301L12 13.8001L10.59 12.0001C10.5243 11.9226 10.4441 11.8588 10.3537 11.8123C10.2634 11.7659 10.1648 11.7377 10.0636 11.7293C9.96242 11.721 9.86055 11.7326 9.76384 11.7636C9.66713 11.7946 9.57747 11.8444 9.49999 11.9101C9.42251 11.9757 9.35872 12.056 9.31227 12.1463C9.26581 12.2366 9.2376 12.3352 9.22925 12.4364C9.22089 12.5376 9.23255 12.6395 9.26356 12.7362C9.29457 12.8329 9.34433 12.9226 9.40999 13.0001L11 15.0001L9.40999 17.0001C9.28534 17.1565 9.22796 17.3561 9.25046 17.5549C9.27296 17.7537 9.37351 17.9354 9.52999 18.0601C9.68647 18.1847 9.88606 18.2421 10.0848 18.2196C10.2836 18.1971 10.4653 18.0965 10.59 17.9401L12 16.2001L13.41 18.0001C13.4818 18.0871 13.5719 18.1573 13.6738 18.2057C13.7758 18.2541 13.8871 18.2795 14 18.2801C14.1534 18.2928 14.3069 18.258 14.4398 18.1804C14.5728 18.1029 14.6786 17.9863 14.743 17.8466C14.8074 17.7068 14.8273 17.5506 14.7999 17.3992C14.7726 17.2478 14.6993 17.1084 14.59 17.0001L13 15.0001L14.63 13.0001C14.6922 12.9184 14.7375 12.8252 14.7632 12.7258C14.7889 12.6264 14.7944 12.5229 14.7795 12.4214C14.7646 12.3198 14.7295 12.2223 14.6764 12.1345C14.6232 12.0466 14.5531 11.9703 14.47 11.9101Z" fill="#000000"/> </g></svg>';
const iCheck = '<svg viewBox="0 -960 960 960"><path d="M382-218 130-470l75-75 177 177 375-375 75 75-450 450Z"/></svg>';
const iClosed = '<svg viewBox="0 -960 960 960"><path d="m252-176-74-76 227-228-227-230 74-76 229 230 227-230 74 76-227 230 227 228-74 76-227-230-229 230Z"/></svg>';
const iCoin = '<svg viewBox="0 -960 960 960"><path d="M444-200h70v-50q50-9 86-39t36-89q0-42-24-77t-96-61q-60-20-83-35t-23-41q0-26 18.5-41t53.5-15q32 0 50 15.5t26 38.5l64-26q-11-35-40.5-61T516-710v-50h-70v50q-50 11-78 44t-28 74q0 47 27.5 76t86.5 50q63 23 87.5 41t24.5 47q0 33-23.5 48.5T486-314q-33 0-58.5-20.5T390-396l-66 26q14 48 43.5 77.5T444-252v52Zm36 120q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Z"/></svg>';
const iContrastMode = '<svg viewBox="0 -960 960 960"><path d="M480-80q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm40-83q119-15 199.5-104.5T800-480q0-123-80.5-212.5T520-797v634Z"/></svg>';
const iCopy = '<svg viewBox="0 -960 960 960"><path d="M760-200H320q-33 0-56.5-23.5T240-280v-560q0-33 23.5-56.5T320-920h280l240 240v400q0 33-23.5 56.5T760-200ZM560-640v-200H320v560h440v-360H560ZM160-40q-33 0-56.5-23.5T80-120v-560h80v560h440v80H160Zm160-800v200-200 560-560Z"/></svg>';
const iDarkModeFill = '<svg viewBox="0 -960 960 960"><path d="M480-120q-150 0-255-105T120-480q0-150 105-255t255-105q14 0 27.5 1t26.5 3q-41 29-65.5 75.5T444-660q0 90 63 153t153 63q55 0 101-24.5t75-65.5q2 13 3 26.5t1 27.5q0 150-105 255T480-120Z"/></svg>';
const iDelete = '<svg viewBox="0 -960 960 960"><path d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM360-280h80v-360h-80v360Zm160 0h80v-360h-80v360ZM280-720v520-520Z"/></svg>';
const iDocument = '<svg viewBox="0 -960 960 960"><path d="M240-80q-33 0-56.5-23.5T160-160v-640q0-33 23.5-56.5T240-880h320l240 240v480q0 33-23.5 56.5T720-80H240Zm280-520v-200H240v640h480v-440H520ZM240-800v200-200 640-640Z"/></svg>';
const iDownload = '<svg viewBox="0 -960 960 960"><path d="M480-320 280-520l56-58 104 104v-326h80v326l104-104 56 58-200 200ZM240-160q-33 0-56.5-23.5T160-240v-120h80v120h480v-120h80v120q0 33-23.5 56.5T720-160H240Z"/></svg>';
const iEdit = '<svg viewBox="0 -960 960 960"><path d="M200-200h56l345-345-56-56-345 345v56Zm572-403L602-771l56-56q23-23 56.5-23t56.5 23l56 56q23 23 24 55.5T829-660l-57 57Zm-58 59L290-120H120v-170l424-424 170 170Zm-141-29-28-28 56 56-28-28Z"/></svg>';
const iEmail = '<svg viewBox="0 -960 960 960"><path d="M480-80q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480v58q0 59-40.5 100.5T740-280q-35 0-66-15t-52-43q-29 29-65.5 43.5T480-280q-83 0-141.5-58.5T280-480q0-83 58.5-141.5T480-680q83 0 141.5 58.5T680-480v58q0 26 17 44t43 18q26 0 43-18t17-44v-58q0-134-93-227t-227-93q-134 0-227 93t-93 227q0 134 93 227t227 93h200v80H480Zm0-280q50 0 85-35t35-85q0-50-35-85t-85-35q-50 0-85 35t-35 85q0 50 35 85t85 35Z"/></svg>';
const iEmpty = '<svg viewBox="0 -960 960 960"><path d="M200-94q-43.725 0-74.863-31.137Q94-156.275 94-200v-560q0-43.725 31.137-74.862Q156.275-866 200-866h560q43.725 0 74.862 31.138Q866-803.725 866-760v560q0 43.725-31.138 74.863Q803.725-94 760-94H200Zm0-106h560v-560H200v560Z"/></svg>';
const iExcel = '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" id="Capa_1" viewBox="0 0 550.801 550.801" xml:space="preserve"><g><path d="M488.432,197.019h-13.226v-63.816c0-0.398-0.063-0.799-0.111-1.205c-0.021-2.531-0.833-5.021-2.568-6.992L366.325,3.694   c-0.032-0.031-0.063-0.042-0.085-0.076c-0.633-0.707-1.371-1.295-2.151-1.804c-0.231-0.155-0.464-0.285-0.706-0.422   c-0.676-0.366-1.393-0.675-2.131-0.896c-0.2-0.053-0.38-0.135-0.58-0.188C359.87,0.119,359.037,0,358.193,0H97.2   c-11.918,0-21.6,9.693-21.6,21.601v175.413H62.377c-17.049,0-30.873,13.818-30.873,30.873v160.545   c0,17.038,13.824,30.87,30.873,30.87h13.224V529.2c0,11.907,9.682,21.601,21.6,21.601h356.4c11.907,0,21.6-9.693,21.6-21.601   V419.302h13.226c17.044,0,30.871-13.827,30.871-30.87v-160.54C519.297,210.832,505.48,197.019,488.432,197.019z M97.2,21.601   h250.193v110.51c0,5.967,4.841,10.8,10.8,10.8h95.407v54.108H97.2V21.601z M339.562,354.344v31.324H236.509V220.704h37.46v133.64   H339.562z M74.25,385.663l47.73-83.458l-46.019-81.501h42.833l14.439,30.099c4.899,10.03,8.572,18.116,12.49,27.414h0.483   c3.926-10.529,7.101-17.872,11.259-27.414l13.954-30.099h42.588l-46.509,80.525l48.961,84.438h-43.081l-14.929-29.858   c-6.115-11.507-10.036-20.07-14.684-29.615h-0.49c-3.431,9.55-7.583,18.119-12.722,29.615l-13.711,29.858H74.25V385.663z    M453.601,523.353H97.2V419.302h356.4V523.353z M401.963,388.125c-18.837,0-37.446-4.904-46.738-10.04l7.578-30.839   c10.04,5.136,25.46,10.283,41.375,10.283c17.139,0,26.188-7.099,26.188-17.867c0-10.283-7.831-16.157-27.659-23.256   c-27.411-9.55-45.282-24.722-45.282-48.718c0-28.15,23.498-49.684,62.427-49.684c18.594,0,32.305,3.927,42.093,8.322l-8.322,30.109   c-6.607-3.186-18.361-7.834-34.509-7.834c-16.152,0-23.983,7.338-23.983,15.913c0,10.525,9.291,15.18,30.591,23.258   c29.125,10.769,42.836,25.936,42.836,49.191C468.545,364.627,447.257,388.125,401.963,388.125z"/></g></svg>';
const iExpand = '<svg viewBox="0 -960 960 960"><path d="M480-345 240-585l56-56 184 184 184-184 56 56-240 240Z"/></svg>';
const iExpandLess = '<svg viewBox="0 -960 960 960"><path d="m296-345-56-56 240-240 240 240-56 56-184-184-184 184Z"/></svg>';
const iHelp = '<svg viewBox="0 -960 960 960"><path d="M424-343q0-80 16-120t64-76q45-34 63-58.5t18-69.5q0-38-25.5-63T488-755q-40 0-73.5 26T367-659l-121-51q27-80 91-127.5T488-885q109 0 168 66.5T715-667q0 63-20 104t-71 84q-45 38-57.5 66T554-343H424Zm64 289q-44 0-75-31t-31-75q0-44 31-75t75-31q44 0 75 31t31 75q0 44-31 75t-75 31Z"/></svg>';
const iHelpFill = '<svg viewBox="0 -960 960 960"><path d="M477.859-218Q503-218 520.5-235.359t17.5-42.5Q538-303 520.641-320.5t-42.5-17.5Q453-338 435.5-320.641t-17.5 42.5Q418-253 435.359-235.5t42.5 17.5ZM430-386h98q0-37.648 6.5-59.324Q541-467 582-506q26-24 42-49.919 16-25.919 16-62.316Q640-680 593.652-712T484-744q-66 0-106 34t-54 84l86 34q2-17 18.5-40.5T484-656q29 0 44.5 16t15.5 34q0 20-13 37.5T500-536q-50 44-60 64t-10 86Zm50 338q-89.64 0-168.48-34.02-78.84-34.02-137.16-92.34-58.32-58.32-92.34-137.16T48-480q0-89.896 34.079-168.961 34.079-79.066 92.5-137.552Q233-845 311.738-878.5 390.476-912 480-912q89.886 0 168.943 33.5Q728-845 786.5-786.5q58.5 58.5 92 137.583 33.5 79.084 33.5 169Q912-390 878.5-311.5t-91.987 136.921q-58.486 58.421-137.552 92.5Q569.896-48 480-48Z"/></svg>';
const iHoy = '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" id="Layer_1" viewBox="0 0 490 490" xml:space="preserve"><g id="SVGRepo_bgCarrier" stroke-width="0"/><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"/><g id="SVGRepo_iconCarrier"> <g> <g> <g> <g> <path d="M353.4,490H136.6C61.5,490,0,428.5,0,353.4V136.6C0,61.5,61.5,0,136.6,0h216.9C428.5,0,490,61.5,490,136.6v216.9 C490,428.5,428.5,490,353.4,490z M136.6,40.7c-53.2,0-95.9,42.7-95.9,95.9v216.9c0,53.2,42.7,95.9,95.9,95.9h216.9 c53.2,0,95.9-42.7,95.9-95.9V136.6c0-53.2-42.7-95.9-95.9-95.9L136.6,40.7z"/> </g> <g> <g> <path d="M142.8,367V123h41.7v106.3h122V123h41.7v245h-41.7V267.9h-122V368h-41.7V367z"/> </g> </g> </g> </g> </g> </g></svg>';
const iInfo = '<svg viewBox="0 -960 960 960"><path d="M425-265h110v-255H425v255Zm55-315q25.5 0 42.75-17.25T540-640q0-25.5-17.25-42.75T480-700q-25.5 0-42.75 17.25T420-640q0 25.5 17.25 42.75T480-580Zm0 534q-91 0-169.987-34.084-78.988-34.083-137.417-92.512T80.084-310.013Q46-389 46-480t34.084-169.987q34.083-78.988 92.512-137.417t137.417-92.512Q389-914 480-914t169.987 34.084q78.988 34.083 137.417 92.512t92.512 137.417Q914-571 914-480t-34.084 169.987q-34.083 78.988-92.512 137.417T649.987-80.084Q571-46 480-46Z"/></svg>';
const iLightModeFill = '<svg viewBox="0 -960 960 960"><path d="M480-267q-89 0-151-62t-62-151q0-89 62-151t151-62q89 0 151 62t62 151q0 89-62 151t-151 62ZM80-427q-22 0-37.5-15.5T27-480q0-22 15.5-37.5T80-533h80q22 0 37.5 15.5T213-480q0 22-15.5 37.5T160-427H80Zm720 0q-22 0-37.5-15.5T747-480q0-22 15.5-37.5T800-533h80q22 0 37.5 15.5T933-480q0 22-15.5 37.5T880-427h-80ZM480-747q-22 0-37.5-15.5T427-800v-80q0-22 15.5-37.5T480-933q22 0 37.5 15.5T533-880v80q0 22-15.5 37.5T480-747Zm0 720q-22 0-37.5-15.5T427-80v-80q0-22 15.5-37.5T480-213q22 0 37.5 15.5T533-160v80q0 22-15.5 37.5T480-27ZM217-669l-43-42q-16-16-15.5-37.5T174-786q16-16 37.5-16t37.5 16l42 43q15 16 15 37t-15 37q-14 16-36 15.5T217-669Zm494 495-42-43q-15-16-14.5-37t14.5-37q15-16 36.5-15.5T743-291l43 42q16 16 15.5 37.5T786-174q-16 16-37.5 16T711-174Zm-42-495q-16-14-15.5-36t15.5-38l42-43q16-16 37.5-15.5T786-786q16 16 16 37.5T786-711l-43 42q-16 15-37 15t-37-15ZM174-174q-16-16-16-37.5t16-37.5l43-42q16-15 37-14.5t37 14.5q16 15 15.5 36.5T291-217l-42 43q-16 16-37.5 15.5T174-174Z"/></svg>'; 
const iLogout = '<svg viewBox="0 -960 960 960"><path d="M194-88q-43.725 0-74.863-31.137Q88-150.275 88-194v-572q0-43.725 31.137-74.862Q150.275-872 194-872h286v106H194v572h286v106H194Zm438-152-76-78 110-110H354v-106h312L556-644l76-76 240 240-240 240Z"/></svg>';
const iMenu = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960"><path d="M120-240v-66.666h720V-240H120Zm0-206.667v-66.666h720v66.666H120Zm0-206.667V-720h720v66.666H120Z"/></svg>';
const iNavigateNext = '<svg viewBox="0 -960 960 960"><path d="M504-480 320-664l56-56 240 240-240 240-56-56 184-184Z"/></svg>';
const iNavigateBefore = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960"><path d="M560.667-240 320-480.667l240.667-240.666L608-674 414.666-480.667 608-287.333 560.667-240Z"/></svg>';
const iNull = '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24"><g><path d="M14.4,0H4.8A2.3,2.3,0,0,0,3.11.7,2.33,2.33,0,0,0,2.4,2.4V21.6a2.3,2.3,0,0,0,.71,1.69A2.3,2.3,0,0,0,4.8,24H9.88a3.16,3.16,0,0,1-.28-1.34V21.6H4.8V2.4h8.4v6h6v2.26h2.4V7.2Z"/><path d="M14.27,15.33v6H15.6v-6Zm2.67,0v6h1.33v-6Zm0,0v6h1.33v-6Zm-2.67,6H15.6v-6H14.27Zm4-8.67V12h-4v.66H10.94V14h.66v8.66A1.35,1.35,0,0,0,12.94,24H19.6a1.31,1.31,0,0,0,.95-.39l.05-.06a1.27,1.27,0,0,0,.34-.89V14h.66V12.66Zm-5.33,10V14H19.6v8.66Zm4-1.33h1.33v-6H16.94Zm-2.67,0H15.6v-6H14.27Zm0-6v6H15.6v-6Zm2.67,0v6h1.33v-6Zm-2.67,0v6H15.6v-6Zm2.67,0v6h1.33v-6Z"/></g></svg>';
const iOpen = '<svg viewBox="0 -960 960 960"><path d="M200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h280v80H200v560h560v-280h80v280q0 33-23.5 56.5T760-120H200Zm188-212-56-56 372-372H560v-80h280v280h-80v-144L388-332Z"/></svg>';
const iPdf = '<svg id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 550.801 550.801" xml:space="preserve"><g><path d="M160.381,282.225c0-14.832-10.299-23.684-28.474-23.684c-7.414,0-12.437,0.715-15.071,1.432V307.6c3.114,0.707,6.942,0.949,12.192,0.949C148.419,308.549,160.381,298.74,160.381,282.225z"/><path d="M272.875,259.019c-8.145,0-13.397,0.717-16.519,1.435v105.523c3.116,0.729,8.142,0.729,12.69,0.729c33.017,0.231,54.554-17.946,54.554-56.474C323.842,276.719,304.215,259.019,272.875,259.019z"/><path d="M488.426,197.019H475.2v-63.816c0-0.398-0.063-0.799-0.116-1.202c-0.021-2.534-0.827-5.023-2.562-6.995L366.325,3.694c-0.032-0.031-0.063-0.042-0.085-0.076c-0.633-0.707-1.371-1.295-2.151-1.804c-0.231-0.155-0.464-0.285-0.706-0.419c-0.676-0.369-1.393-0.675-2.131-0.896c-0.2-0.056-0.38-0.138-0.58-0.19C359.87,0.119,359.037,0,358.193,0H97.2c-11.918,0-21.6,9.693-21.6,21.601v175.413H62.377c-17.049,0-30.873,13.818-30.873,30.873v160.545c0,17.043,13.824,30.87,30.873,30.87h13.224V529.2c0,11.907,9.682,21.601,21.6,21.601h356.4c11.907,0,21.6-9.693,21.6-21.601V419.302h13.226c17.044,0,30.871-13.827,30.871-30.87v-160.54C519.297,210.838,505.47,197.019,488.426,197.019z M97.2,21.605h250.193v110.513c0,5.967,4.841,10.8,10.8,10.8h95.407v54.108H97.2V21.605z M362.359,309.023c0,30.876-11.243,52.165-26.82,65.333c-16.971,14.117-42.82,20.814-74.396,20.814c-18.9,0-32.297-1.197-41.401-2.389V234.365c13.399-2.149,30.878-3.346,49.304-3.346c30.612,0,50.478,5.508,66.039,17.226C351.828,260.69,362.359,280.547,362.359,309.023z M80.7,393.499V234.365c11.241-1.904,27.042-3.346,49.296-3.346c22.491,0,38.527,4.308,49.291,12.928c10.292,8.131,17.215,21.534,17.215,37.328c0,15.799-5.25,29.198-14.829,38.285c-12.442,11.728-30.865,16.996-52.407,16.996c-4.778,0-9.1-0.243-12.435-0.723v57.67H80.7V393.499z M453.601,523.353H97.2V419.302h356.4V523.353z M484.898,262.127h-61.989v36.851h57.913v29.674h-57.913v64.848h-36.593V232.216h98.582V262.127z"/></g></svg>';
const iPerson = '<svg viewBox="0 -960 960 960"><path d="M480-480q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47ZM160-160v-112q0-34 17.5-62.5T224-378q62-31 126-46.5T480-440q66 0 130 15.5T736-378q29 15 46.5 43.5T800-272v112H160Z"/></svg>';
const iPersonAdd = '<svg viewBox="0 -960 960 960"><path d="M720-400v-120H600v-80h120v-120h80v120h120v80H800v120h-80Zm-360-80q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47ZM40-160v-112q0-34 17.5-62.5T104-378q62-31 126-46.5T360-440q66 0 130 15.5T616-378q29 15 46.5 43.5T680-272v112H40Z"/></svg>';
const iPin = '<svg viewBox="0 -960 960 960"><path d="m640-480 80 80v80H520v240l-40 40-40-40v-240H240v-80l80-80v-280h-40v-80h400v80h-40v280Z"/></svg>';
const iPrint = '<svg viewBox="0 -960 960 960"><path d="M640-640v-120H320v120h-80v-200h480v200h-80Zm-480 80h640-640Zm560 100q17 0 28.5-11.5T760-500q0-17-11.5-28.5T720-540q-17 0-28.5 11.5T680-500q0 17 11.5 28.5T720-460Zm-80 260v-160H320v160h320Zm80 80H240v-160H80v-240q0-51 35-85.5t85-34.5h560q51 0 85.5 34.5T880-520v240H720v160Zm80-240v-160q0-17-11.5-28.5T760-560H200q-17 0-28.5 11.5T160-520v160h80v-80h480v80h80Z"/></svg>';
const iRemove = '<svg viewBox="0 -960 960 960"><path d="M168-428v-106h624v106H168Z"/></svg>';
const iRestart = '<svg viewBox="0 -960 960 960"><path d="M440-122q-121-15-200.5-105.5T160-440q0-66 26-126.5T260-672l57 57q-38 34-57.5 79T240-440q0 88 56 155.5T440-202v80Zm80 0v-80q87-16 143.5-83T720-440q0-100-70-170t-170-70h-3l44 44-56 56-140-140 140-140 56 56-44 44h3q134 0 227 93t93 227q0 121-79.5 211.5T520-122Z"/></svg>';
const iSaveFill = '<svg viewBox="0 -960 960 960"><path d="M872-694v500q0 43.725-31.138 74.863Q809.725-88 766-88H194q-43.725 0-74.863-31.137Q88-150.275 88-194v-572q0-43.725 31.137-74.862Q150.275-872 194-872h500l178 178ZM480.118-226Q536-226 575-265.118q39-39.117 39-95Q614-416 574.882-455q-39.117-39-95-39Q424-494 385-454.882q-39 39.117-39 95Q346-304 385.118-265q39.117 39 95 39ZM226-546h392v-188H226v188Z"/></svg>';
const iSearch = '<svg viewBox="0 -960 960 960"><path d="M784-120 532-372q-30 24-69 38t-83 14q-109 0-184.5-75.5T120-580q0-109 75.5-184.5T380-840q109 0 184.5 75.5T640-580q0 44-14 83t-38 69l252 252-56 56ZM380-400q75 0 127.5-52.5T560-580q0-75-52.5-127.5T380-760q-75 0-127.5 52.5T200-580q0 75 52.5 127.5T380-400Z"/></svg>';
const iSettings = '<svg viewBox="0 -960 960 960"><path d="m370-80-16-128q-13-5-24.5-12T307-235l-119 50L78-375l103-78q-1-7-1-13.5v-27q0-6.5 1-13.5L78-585l110-190 119 50q11-8 23-15t24-12l16-128h220l16 128q13 5 24.5 12t22.5 15l119-50 110 190-103 78q1 7 1 13.5v27q0 6.5-2 13.5l103 78-110 190-118-50q-11 8-23 15t-24 12L590-80H370Zm112-260q58 0 99-41t41-99q0-58-41-99t-99-41q-59 0-99.5 41T342-480q0 58 40.5 99t99.5 41Z"/></svg>';
const iSupport = '<svg viewBox="0 -960 960 960"><path d="M440-120v-80h320v-284q0-117-81.5-198.5T480-764q-117 0-198.5 81.5T200-484v244h-40q-33 0-56.5-23.5T80-320v-80q0-21 10.5-39.5T120-469l3-53q8-68 39.5-126t79-101q47.5-43 109-67T480-840q68 0 129 24t109 66.5Q766-707 797-649t40 126l3 52q19 9 29.5 27t10.5 38v92q0 20-10.5 38T840-249v49q0 33-23.5 56.5T760-120H440Zm-80-280q-17 0-28.5-11.5T320-440q0-17 11.5-28.5T360-480q17 0 28.5 11.5T400-440q0 17-11.5 28.5T360-400Zm240 0q-17 0-28.5-11.5T560-440q0-17 11.5-28.5T600-480q17 0 28.5 11.5T640-440q0 17-11.5 28.5T600-400Zm-359-62q-7-106 64-182t177-76q89 0 156.5 56.5T720-519q-91-1-167.5-49T435-698q-16 80-67.5 142.5T241-462Z"/></svg>';
const iSync = '<svg viewBox="0 -960 960 960"><path d="M169-145v-71h105l-5-5q-65-56-96.5-117.5T141-477q0-118 74.5-209.5T406-807v96q-76 23-123 88.5T236-477q0 59 22 102t60 75l24 15v-105h71v245H169Zm386-7v-97q77-23 123-88.5T724-483q0-44-22.5-90T645-655l-23-20v105h-72v-245h245v71H688l6 7q62 58 93.5 125T819-483q0 118-74 210T555-152Z"/></svg>';
const iTask = '<svg viewBox="0 -960 960 960"><path d="m438-240 226-226-58-58-169 169-84-84-57 57 142 142ZM240-80q-33 0-56.5-23.5T160-160v-640q0-33 23.5-56.5T240-880h320l240 240v480q0 33-23.5 56.5T720-80H240Zm280-520v-200H240v640h480v-440H520ZM240-800v200-200 640-640Z"/></svg>';
const iText = '<svg viewBox="0 -960 960 960"><path d="M320-240h320v-80H320v80Zm0-160h320v-80H320v80ZM240-80q-33 0-56.5-23.5T160-160v-640q0-33 23.5-56.5T240-880h320l240 240v480q0 33-23.5 56.5T720-80H240Zm280-520v-200H240v640h480v-440H520ZM240-800v200-200 640-640Z"/></svg>';
const iUpload = '<svg viewBox="0 -960 960 960"><path d="M440-320v-326L336-542l-56-58 200-200 200 200-56 58-104-104v326h-80ZM240-160q-33 0-56.5-23.5T160-240v-120h80v120h480v-120h80v120q0 33-23.5 56.5T720-160H240Z"/></svg>';
const iZip = '<svg viewBox="0 0 192 192" xmlns="http://www.w3.org/2000/svg" id="Layer_1"><defs><style>.cls-2,.cls-3{fill:none;stroke-width:8px;stroke:#000000;stroke-linecap:round}.cls-2{stroke-miterlimit:10}.cls-3{stroke-linejoin:round}</style></defs><path d="M0 0h192v192H0z" style="fill:none"/><path d="M21.68 31.74h148.64c.94 5.92 1.64 12.88 1.68 20.68.04 8.29-.69 15.66-1.68 21.86H21.68c-.98-6.2-1.71-13.57-1.68-21.86.03-7.8.74-14.76 1.68-20.68Z" class="cls-3"/><path d="M34.53 41.34v22.35" class="cls-2"/><path d="M71.38 116.78h-49.7c-.98-6.2-1.71-13.57-1.68-21.86.03-7.8.74-14.76 1.68-20.68h148.64c.94 5.92 1.64 12.88 1.68 20.68.04 8.29-.69 15.66-1.68 21.86H119" class="cls-3"/><path d="M34.53 83.85v22.36" class="cls-2"/><path d="M119.01 116.75h51.32c.94 5.92 1.64 12.88 1.68 20.68.04 8.29-.69 15.66-1.68 21.86H21.68c-.98-6.2-1.71-13.57-1.68-21.86.03-7.8.74-14.76 1.68-20.68h49.71" class="cls-3"/><path d="M34.53 127.37v22.35" class="cls-2"/><path d="M74.74 28.18h42.51v135.63H74.74z" style="stroke-width:12px;stroke-linejoin:round;stroke:#000000;stroke-linecap:round;fill:none"/><path d="M74.74 89h-5.06l-2.02 42.43h56.68L122.32 89h-5.06" class="cls-3"/><path d="m92.96 129-2.02-16.2c.51-2.39 2.65-4.1 5.06-4.05 2.34.05 4.36 1.73 4.85 4.05-.61 5.4-1.21 10.8-1.82 16.2h-6.07Z" style="stroke-width:6px;stroke-linejoin:round;stroke:#000000;stroke-linecap:round;fill:none"/></svg>';
const iZoomIn = '<svg viewBox="0 -960 960 960"><path d="M784-120 532-372q-30 24-69 38t-83 14q-109 0-184.5-75.5T120-580q0-109 75.5-184.5T380-840q109 0 184.5 75.5T640-580q0 44-14 83t-38 69l252 252-56 56ZM380-400q75 0 127.5-52.5T560-580q0-75-52.5-127.5T380-760q-75 0-127.5 52.5T200-580q0 75 52.5 127.5T380-400Zm-40-60v-80h-80v-80h80v-80h80v80h80v80h-80v80h-80Z"/></svg>';
const iZoomOut = '<svg viewBox="0 -960 960 960"><path d="M784-120 532-372q-30 24-69 38t-83 14q-109 0-184.5-75.5T120-580q0-109 75.5-184.5T380-840q109 0 184.5 75.5T640-580q0 44-14 83t-38 69l252 252-56 56ZM380-400q75 0 127.5-52.5T560-580q0-75-52.5-127.5T380-760q-75 0-127.5 52.5T200-580q0 75 52.5 127.5T380-400ZM280-540v-80h200v80H280Z"/></svg>';

function addSvgContentToElements(elements, svgContent) {
    elements.forEach(element => {
        element.innerHTML += svgContent;
    });
}

if(icons){
    icons.forEach(element => {
        switch (element.className) {
            case 'iSupport':
            addSvgContentToElements([element], iSupport);
            break;
            case 'iInfo':
            addSvgContentToElements([element], iInfo);
            break;
    
            case 'iAdd':
            addSvgContentToElements([element], iAdd);
            break;
            case 'iArrowBack':
            addSvgContentToElements([element], iArrowBack);
            break;
            case 'iAttach':
            addSvgContentToElements([element], iAttach);
            break;
            case 'iCancel':
            addSvgContentToElements([element], iCancel);
            break;
            case 'iCheck':
            addSvgContentToElements([element], iCheck);
            break;
            case 'iClosed':
            addSvgContentToElements([element], iClosed);
            break;
            case 'iCoin':
            addSvgContentToElements([element], iCoin);
            break;
            case 'iContrastMode':
            addSvgContentToElements([element], iContrastMode);
            break;
            case 'iCopy':
            addSvgContentToElements([element], iCopy);
            break;
            case 'iDarkModeFill':
            addSvgContentToElements([element], iDarkModeFill);
            break;
            case 'iDelete':
            addSvgContentToElements([element], iDelete);
            break;
            case 'iDocument':
            addSvgContentToElements([element], iDocument);
            break;
            case 'iDownload':
            addSvgContentToElements([element], iDownload);
            break;
            case 'iEdit':
            addSvgContentToElements([element], iEdit);
            break;
            case 'iEmail':
            addSvgContentToElements([element], iEmail);
            break;
            case 'iEmpty':
            addSvgContentToElements([element], iEmpty);
            break;
            case 'iExcel':
            addSvgContentToElements([element], iExcel);
            break;
            case 'iExpand':
            addSvgContentToElements([element], iExpand);
            break;
            case 'iExpandLess':
            addSvgContentToElements([element], iExpandLess);
            break;
            case 'iHelp':
            addSvgContentToElements([element], iHelp);
            break;
            case 'iHelpFill':
            addSvgContentToElements([element], iHelpFill);
            break;
            case 'iHoy':
            addSvgContentToElements([element], iHoy);
            break;
            case 'iLightModeFill':
            addSvgContentToElements([element], iLightModeFill);
            break;
            case 'iLogout':
            addSvgContentToElements([element], iLogout);
            break;
            case 'iMenu':
            addSvgContentToElements([element], iMenu);
            break;
            case 'iNavigateBefore':
            addSvgContentToElements([element], iNavigateBefore);
            break;
            case 'iNavigateNext':
            addSvgContentToElements([element], iNavigateNext);
            break;
            case 'iNull':
            addSvgContentToElements([element], iNull);
            break;
            case 'iOpen':
            addSvgContentToElements([element], iOpen);
            break;
            case 'iPdf':
            addSvgContentToElements([element], iPdf);
            break;
            case 'iPerson':
            addSvgContentToElements([element], iPerson);
            break;
            case 'iPersonAdd':
            addSvgContentToElements([element], iPersonAdd);
            break;
            case 'iPin':
            addSvgContentToElements([element], iPin);
            break;
            case 'iPrint':
            addSvgContentToElements([element], iPrint);
            break;
            case 'iRemove':
            addSvgContentToElements([element], iRemove);
            break;
            case 'iRestart':
            addSvgContentToElements([element], iRestart);
            break;
            case 'iSaveFill':
            addSvgContentToElements([element], iSaveFill);
            break;
            case 'iSearch':
            addSvgContentToElements([element], iSearch);
            break;
            case 'iSettings':
            addSvgContentToElements([element], iSettings);
            break;
            case 'iSync':
            addSvgContentToElements([element], iSync);
            break;
            case 'iTask':
            addSvgContentToElements([element], iTask);
            break;
            case 'iText':
            addSvgContentToElements([element], iText);
            break;
            case 'iUpload':
            addSvgContentToElements([element], iUpload);
            break;
            case 'iZip':
            addSvgContentToElements([element], iZip);
            break;
            case 'iZoomIn':
            addSvgContentToElements([element], iZoomIn);
            break;
            case 'iZoomOut':
            addSvgContentToElements([element], iZoomOut);
            break;
    
            default:
            break;
        }
    });
}
// </i>

//---------------------------------------------------------------------------------------------------------

// <details>
// Los acordeones inician abiertos y se les agrega un icono (abierto/cerrado)

const details = document.querySelectorAll('details');

if(details){
    details.forEach(element => {
        if(!element.hasAttribute("closed")){
            element.open = "true"
        }
    });

    const summary = document.querySelectorAll('summary');

    summary.forEach(element => {
        addSvgContentToElements([element], iExpand);
    });

}
// </details>
// <class="modal">
// Cerrar modal si se da click por fuera de la ventana

const modal = document.querySelector(".modal");

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}
// </class="modal">

//---------------------------------------------------------------------------------------------------------


///////////////////////////////////////   VALORES INICIALES  \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
////////////////////////// Establece el estado inicial de algunos elementos \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\

// *Establece el tema (CLARO, OSCURO o CONTRASTE).
if (temaGuardado){
    document.body.className = temaGuardado; 
}else{
    document.body.className = TEMA_CLARO;
}

// *Establece SI el menu es ANCLADO.
if (menuGuardado){
    document.querySelector("nav").classList.add(menuGuardado);
}else{
    document.querySelector("nav").classList.add("anchor");
}

// *Establece el valor de FONT SIZE para su uso en AUMENTAR o DISMINUIR fuenteGuardada
if(fuenteGuardada){
    document.documentElement.style.fontSize = fuenteGuardada + "px";
} else{
    document.documentElement.style.fontSize = "8px";
    fuenteGuardada = 8;
}


///////////////////////////////////////   EVENTO RESIZE  \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\

addEventListener("resize", (event) => {
    valoresIniciales();

    if (window.innerWidth <= 767){

        // Alternar el breadcrumb mobile
        routeMobile.addEventListener('click', () => {    
            breadcrumbMobile.style.left = "unset";
            breadcrumbDesktop.style.left = "unset";
            nav.style.left = "0";
        });
    
        // Alternar el breadcrumb mobile
        backRouteMobile.addEventListener('click', () => {
            breadcrumbMobile.style.left = "-100%";
            breadcrumbDesktop.style.left = "-100%";
            nav.style.left = "-23rem";
        });

        optionsItems.forEach(function(item) {
            const optionLink = item.querySelector('.option');
            optionLink.addEventListener('click', function(e) {
                e.preventDefault();
                item.classList.toggle('open--mobile');
            });
        });
    }
});




