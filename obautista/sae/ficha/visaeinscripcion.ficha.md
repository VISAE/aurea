# Ficha técnica · Componente APP· Módulo 2940 "Familias presupuestales"

## 1. Identificación
- Componente: NomAPP (APP 000).
- Módulo: 2940 – 'ppto60familiappto'.
- Ruta de entrada: '_comp_/pptofamilia.php'.
- Lógica de negocio reutilizable: 'presupuesto/lib2940.php' ('f2940_*').
- Internacionalización: 'presupuesto/lg/lg_2940_{es|en|pt}.php'.
- Tabla principal: 'ppto60familiappto' (campos 'ppto60consec', 'ppto60id', 'ppto60activa', 'ppto60orden', 'ppto60nombre').
- Dependencias comunes: 'app.php', 'unad_sesion.php', 'unad_todas.php', 'clsdbadmin.php', 'unad_librerias.php', 'libdatos.php', 'libhtml.php', 'xajax', 'unad_forma_v2.php'.
- Parámetros relevantes de request:
  - 'paso' (0/10 = nuevo, 2 = edición).
  - 'debug', 'deb_doc', 'deb_tipodoc' (modo depuración / trabajo como otro usuario).
  - 'paginabusqueda', 'lppf560' (paginación de tabla).
- Versión del módulo: v1.0.0 (baseline 27/2/2026, Omar Augusto Bautista).

## 2. Propósito y alcance
Gestionar el catálogo de familias presupuestales: alta, edición, activación/inactivación y listado para usarse en otros procesos del componente Presupuesto. El módulo sólo mantiene la tabla maestra; no lleva lógica de cálculos financieros.

## 3. Flujo resumido
1. El navegador solicita 'pptofamilia.php'. El script verifica 'app.php', fuerza HTTPS cuando 'APP->https==2' y valida sesión.
2. Se cargan mensajes de idioma y se crea la conexión 'clsdbadmin'.
3. Se detecta si la petición es XAJAX ('$_POST['xjxfun']'). Para peticiones AJAX se ejecuta la función solicitada y se retorna sin renderizar HTML.
4. Para peticiones normales se arma el menú, se evalúan permisos ('seg_1707', 'seg_5', 'seg_6', 'seg_8'), se construyen los combos y el formulario.
5. Acciones de usuario:
   - Guardar/actualizar llaman a 'f2940_db_GuardarV2b'.
   - Eliminar llama a 'f2940_db_Eliminar'.
   - Tabla inferior se carga con 'f2940_TablaDetalleV2' vía XAJAX.
6. La página imprime 'forma_piedepagina()' y los scripts ('unad_todas.js', 'chosen', etc.).

## 4. Seguridad y roles
| Permiso | Descripción | Uso |
| --- | --- | --- |
| 'seg_1707' | Trabajar como otro usuario mediante 'deb_doc'. | Valida el documento y carga los datos del tercero. |
| 'seg_6' | Exportar/Imprimir. | Habilita botón de impresión/tablas. |
| 'seg_5' | Eliminación. | Destraba botón Eliminar cuando 'paso != 0'. |
| 'seg_8' | Cambio de consecutivo. | Protege la reasignación manual de 'ppto60consec'. |
| 'seg_2/3/4' | Crear, editar, borrar (validado dentro de 'f2940_db_GuardarV2b'/'f2940_db_Eliminar'). |

## 5. Formulario y campos
| Campo | Descripción | Fuente/Regla | Validaciones |
| --- | --- | --- | --- |
| 'visa40idconvocatoria' | Convocatoria. | Numerico. | Requerido en creación. |
| 'visa40idtercero' | Candidato. | Numerico. | Requerido en creación. |
| 'visa40id' | Ref. | Numérico opcional. | Entero positivo. |
| 'visa40estado' | Estado. | Numerico. | Requerido en creación. |
| 'visa40idperiodo' | Periodo. | Numerico. | Requerido en creación. |
| 'visa40idescuela' | Escuela. | Numerico. | Requerido en creación. |
| 'visa40idprograma' | Programa. | Numerico. | Requerido en creación. |
| 'visa40idzona' | Zona. | Numerico. | Requerido en creación. |
| 'visa40idcentro' | Centro. | Numerico. | Requerido en creación. |
| 'visa40fechainsc' | Fecha inscripción. | Numerico. | Requerido en creación. |
| 'visa40fechaadmision' | Fecha admisión. | Numerico. | Requerido en creación. |
| 'visa40numcupo' | Cupo número. | Numerico. | Requerido en creación. |
| 'visa40idtipologia' | Tipología. | Numerico. | Requerido en creación. |
| 'visa40idsubtipo' | Subtipología. | Numerico. | Requerido en creación. |
| 'visa40idminuta' | Idminuta. | Numerico. | Requerido en creación. |
| 'visa40idresolucion' | Idresolucion. | Numerico. | Requerido en creación. |
| 'deb_tipodoc', 'deb_doc' | Acceso delegado. | Sólo visibles con 'seg_1707'. | Validan existencia en 'unad11terceros'. |
| Ocultos ('paso', 'iscroll', 'boculta2940', 'debug', etc.) | Control de estado. | – | – |

## 6. Servicios XAJAX destacados
| Función | Archivo | Descripción |
| --- | --- | --- |
| 'f2940_TablaDetalleV2' | 'lib2940.php' | Construye tabla HTML filtrada/paginada, retorna 'div_f2940detalle'. |
| 'f2940_db_GuardarV2b' | 'lib2940.php' | Alta/edición con validaciones de permisos, largos y auditoría ('seg_auditar'). |
| 'f2940_db_Eliminar' | 'lib2940.php' | Elimina tras revisar bloqueos ('unad70bloqueoelimina'). |
| 'f2940_ExisteDato' | 'lib2940.php' | Verifica existencia por consecutivo y redirige formulario. |

## 7. Manejo de errores y logging
- '$sError' y '$iTipoError' se muestran mediante 'html_DivAlarmaV2'.
- Debug HTML se activa con 'debug=1' o 'deb_doc' y se acumula en '$sDebug'.
- Auditorías se registran con 'seg_auditar' para acciones 2 (crear), 3 (editar) y 4 (eliminar).

## 8. Dependencias externas clave
- Librerías UI ('css/criticalPath.css', 'unad_estilos2018.css', 'chosen.js').
- 'unad_forma_v2.php' para cabecera/pie estándar.
- 'unad_todas.js' para helpers (paginación, validaciones).
- Base de datos definida en 'presupuesto/app.php'.

## 9. Versionamiento e historial
| Versión | Fecha | Cambios | Responsable |
| --- | --- | --- | --- |
| 1.0.0 | 21-09-2023 | Creación del módulo, portado de Arquitectura Áurea v2.30.1b. | Omar Augusto Bautista. |

## 10. Pruebas sugeridas
1. Intentar guardar sin campos obligatorios .
2. Eliminar registro usado en tablas hijas (debe bloquearlo si aplica).
3. Exportar listado con usuario con/ sin 'seg_6'.

