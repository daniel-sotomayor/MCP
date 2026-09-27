# Plan de Implementación: Modo Oscuro Global

Este plan detalla los pasos para agregar soporte completo de Modo Oscuro en la aplicación interna de Bienes Nacionales.

## Resumen del Estado Actual

1. **Script de Inicialización en Header:** [header.php](file:///c:/xampp/htdocs/SBN_MCP/app/views/layout/header.php) ya lee el tema desde `localStorage` y define el atributo `data-theme` en la etiqueta `<html>` para prevenir parpadeos al cargar las páginas.
2. **Lógica de Conmutación en Footer:** [footer.php](file:///c:/xampp/htdocs/SBN_MCP/app/views/layout/footer.php) tiene el event listener en JavaScript preparado para manejar los clics de un botón con ID `#themeToggle`.
3. **Falta en el HTML:** Ninguna vista ni el sidebar contienen el botón con el ID `#themeToggle`, por lo que el usuario no tiene cómo alternar de tema.
4. **Falta en los Estilos de la App:** Aunque `main.css` tiene variables de modo oscuro, `app.css` (el CSS principal del panel administrativo) redefine variables en `:root` que ignoran el estado oscuro y fuerzan el tema claro.

---

## Cambios Propuestos

### 1. Panel de Control (Sidebar)
#### [MODIFY] [sidebar.php](file:///c:/xampp/htdocs/SBN_MCP/app/views/partials/sidebar.php)
* Agregar el botón `#themeToggle` en la sección del pie de página (`sidebar-footer`), justo arriba del botón de "Cerrar Sesión".
* Incluir los iconos SVG de Sol (para cambiar a modo claro) y Luna (para cambiar a modo oscuro) junto al texto indicativo.

### 2. Estilos del Sistema (CSS)
#### [MODIFY] [app.css](file:///c:/xampp/htdocs/SBN_MCP/public/css/app.css)
* Definir el bloque selector `[data-theme="dark"]` que sobrescribirá las variables de color del panel de administración (colores ejecutivos oscuros, fondos de tarjetas, inputs, tablas y textos claros).
* Asegurar legibilidad en componentes clave como tablas de datos (`.data-table`), formularios y tarjetas de métricas.

### 3. JavaScript Global (Footer)
#### [MODIFY] [footer.php](file:///c:/xampp/htdocs/SBN_MCP/app/views/layout/footer.php)
* Refinar la función `updateIcons` para que muestre el icono de Sol en modo oscuro (indica cambiar a claro) y el de Luna en modo claro (indica cambiar a oscuro).
* Actualizar dinámicamente el texto del botón de `Modo Oscuro` a `Modo Claro` según el tema activo.

---

## Detalle de Estilos Oscuros (app.css)
Definiremos los siguientes valores en el bloque oscuro:
```css
[data-theme="dark"] {
    --primary:        #0f172a;  /* Color del sidebar */
    --primary-light:  #1e293b;  /* Hover del sidebar */
    --accent:         #3b82f6;  /* Azul de realce */
    --accent-glow:    rgba(59, 130, 246, 0.15);

    --bg:             #0f172a;  /* Fondo de la app */
    --surface:        #1e293b;  /* Fondo de tarjetas, tablas, formularios */
    --border:         #334155;  /* Color de bordes */

    --text-main:      #f1f5f9;  /* Texto principal claro */
    --text-muted:     #94a3b8;  /* Texto secundario */
    --text-light:     #64748b;  /* Textos de aclaraciones */
}
```

---

## Plan de Verificación

### Verificación Manual
1. Abrir cualquier página del sistema autenticado (ej. `/dashboard`, `/bienes`).
2. Comprobar que en el pie del Sidebar aparece el botón **Modo Oscuro** (icono de Luna).
3. Hacer clic en el botón y verificar que:
   - Toda la interfaz cambia armoniosamente a tonos oscuros (fondos oscuros, textos claros legibles).
   - Se muestra un toast indicativo: *"Tema oscuro activado"*.
   - El botón cambia su icono a un Sol y el texto a **Modo Claro**.
4. Recargar la página para verificar que el tema oscuro se mantiene activo (lectura correcta de `localStorage`).
5. Volver a hacer clic para retornar al Modo Claro y validar la persistencia.
