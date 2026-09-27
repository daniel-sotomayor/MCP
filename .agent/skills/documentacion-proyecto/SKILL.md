---
name: documentacion-proyecto
description: "Skill para documentar proyectos de software y crear manuales de usuario (técnicos y finales) siguiendo estándares profesionales"
risk: "safe"
tags: ["documentation", "user-manual", "technical-writing", "project-docs"]
---

# Documentación de Proyectos y Manuales de Usuario

## Overview

Esta skill guía al agente en el proceso de documentar un proyecto de software desde cero, incluyendo:

- **Análisis del sistema**: estudiar la arquitectura, componentes y flujos de trabajo.
- **Documentación técnica**: manuales para desarrolladores y personal de soporte.
- **Manuales de usuario**: guías para usuarios finales con lenguaje claro y ejemplos prácticos.

El objetivo es generar documentación completa, actualizada y adaptada a cada audiencia, siguiendo las mejores prácticas de redacción técnica.

## When to Use This Skill

- Use cuando necesites documentar un proyecto nuevo o existente.
- Use cuando te pidan crear manuales de usuario para un sistema.
- Use cuando necesites generar documentación técnica para desarrolladores.
- Use cuando el proyecto carezca de documentación o esté desactualizada.
- Use cuando quieras estandarizar la documentación de un equipo.

## How It Works

### Paso 1: Análisis del Proyecto

Antes de documentar, el agente debe comprender el sistema:

1. **Explorar la estructura del código**:
   - Revisar la arquitectura general (carpetas, módulos, dependencias).
   - Identificar los componentes principales y sus responsabilidades.
   - Detectar puntos de entrada (APIs, interfaces de usuario, servicios).

2. **Entrevistar al equipo (si es posible)**:
   - Preguntar sobre el propósito del sistema.
   - Identificar los flujos de trabajo críticos.
   - Conocer las audiencias objetivo de la documentación.

3. **Revisar documentación existente**:
   - Leer README, comentarios en el código, wikis internas.
   - Identificar qué falta y qué está desactualizado.

### Paso 2: Definir la Estructura de Documentación

Crear un plan de documentos a generar:

| Documento | Audiencia | Propósito |
|-----------|-----------|-----------|
| **Manual de Usuario Final** | Usuarios no técnicos | Explicar cómo usar el sistema paso a paso |
| **Manual Técnico** | Desarrolladores y soporte | Describir arquitectura, APIs, configuración |
| **Guía de Inicio Rápido** | Todos los usuarios | Resumen de 5 pasos para empezar |
| **Preguntas Frecuentes (FAQ)** | Todos los usuarios | Solucionar problemas comunes |

### Paso 3: Crear el Manual de Usuario Final

Este es el documento más importante para los usuarios finales. Debe incluir:

1. **Introducción**:
   - ¿Qué hace el sistema?
   - ¿Para quién es?
   - ¿Qué problemas resuelve?

2. **Requisitos previos**:
   - Hardware/software necesario.
   - Permisos o credenciales requeridas.

3. **Instalación o acceso**:
   - Cómo acceder al sistema (URL, instalación, login).

4. **Funcionalidades principales**:
   - Explicar cada función con **pasos concretos**.
   - Incluir capturas de pantalla o diagramas (si es posible).
   - Usar **lenguaje sencillo**, evitar jerga técnica.

5. **Solución de problemas**:
   - Errores comunes y cómo resolverlos.
   - Dónde pedir ayuda.

**Formato recomendado**: Markdown o PDF, con índice y secciones numeradas.

### Paso 4: Crear el Manual Técnico

Este documento está dirigido a desarrolladores y personal de soporte:

1. **Arquitectura del sistema**:
   - Diagrama de componentes.
   - Tecnologías utilizadas (lenguajes, frameworks, bases de datos).

2. **APIs y endpoints**:
   - Listar todos los endpoints con métodos, parámetros y respuestas[reference:2].
   - Incluir ejemplos de solicitud/respuesta.

3. **Configuración y despliegue**:
   - Variables de entorno.
   - Pasos para levantar el entorno de desarrollo.
   - Pasos para desplegar en producción.

4. **Modelo de datos**:
   - Esquema de base de datos.
   - Relaciones entre entidades.

5. **Guía de contribución**:
   - Cómo reportar bugs.
   - Cómo proponer mejoras.
   - Estándares de código.

### Paso 5: Generar la Guía de Inicio Rápido

Un resumen de **5 pasos o menos** para que un usuario nuevo pueda empezar a usar el sistema inmediatamente:

1. Acceder al sistema.
2. Crear una cuenta o iniciar sesión.
3. Realizar la primera acción clave.
4. Ver resultado.
5. Dónde ir para más ayuda.

### Paso 6: Revisar y Validar

1. **Revisión técnica**: que un desarrollador valide la precisión técnica.
2. **Revisión de usuario**: que un usuario no técnico valide la claridad.
3. **Actualizar** la documentación con el feedback recibido.

### Paso 7: Entregar y Mantener

1. Entregar la documentación en el formato solicitado (Markdown, PDF, HTML, etc.).
2. Establecer un proceso de actualización (ej. revisar cada sprint).
3. Vincular la documentación al repositorio o sistema de gestión del conocimiento.

## Examples

### Ejemplo 1: Solicitud de documentación completa

**Usuario**: *"Documenta el proyecto completo y crea manuales para usuarios y técnicos"*

**Acción del agente**:
1. Ejecuta los Pasos 1-7.
2. Entrega: `manual-usuario.md`, `manual-tecnico.md`, `guia-inicio-rapido.md`.

### Ejemplo 2: Solo manual de usuario

**Usuario**: *"Necesito un manual de usuario para el módulo de facturación"*

**Acción del agente**:
1. Analiza el módulo de facturación (Paso 1).
2. Crea el manual de usuario enfocado en ese módulo (Paso 3).
3. Entrega: `manual-usuario-facturacion.md`.

### Ejemplo 3: Actualizar documentación técnica

**Usuario**: *"Actualiza la documentación técnica con los nuevos endpoints de la API"*

**Acción del agente**:
1. Revisa los cambios en el código (Paso 1).
2. Actualiza la sección de APIs en el manual técnico (Paso 4).
3. Entrega: `manual-tecnico.md` actualizado.

## Best Practices

- ✅ Usa **lenguaje claro y directo**. Evita tecnicismos innecesarios.
- ✅ Estructura la documentación con **jerarquía clara** (títulos, subtítulos).
- ✅ Incluye **ejemplos concretos** y casos de uso reales.
- ✅ Mantén la documentación **cerca del código** (en el repositorio).
- ✅ Revisa la documentación **periódicamente** para mantenerla actualizada.
- ✅ Usa **diagramas** (arquitectura, flujos) para facilitar la comprensión.
- ❌ No uses jerga sin explicar.
- ❌ No asumas que el usuario tiene conocimientos previos.
- ❌ No dejes la documentación sin actualizar después de cambios importantes.

## Common Pitfalls

- **Problema**: La documentación técnica es demasiado densa y los usuarios finales no la entienden.
  - **Solución**: Crear documentos separados para cada audiencia. El manual de usuario debe ser simple.

- **Problema**: La documentación se desactualiza rápidamente.
  - **Solución**: Integrar la generación de documentación en el pipeline de CI/CD.

- **Problema**: Faltan ejemplos prácticos.
  - **Solución**: Incluir siempre al menos 2-3 ejemplos de uso real en cada sección.

- **Problema**: No hay un proceso de revisión.
  - **Solución**: Asignar responsables de revisión técnica y de usabilidad.

## Related Skills

- `@api-documentation-generator` - Para documentación específica de APIs[reference:3].
- `@code-review` - Para revisar calidad del código antes de documentar[reference:4].
- `@project-setup` - Para inicializar proyectos con estructura de documentación.

---

## 📥 Cómo instalar y probar esta Skill en Antigravity

1. **Crear la carpeta de la skill**:
   ```bash
   mkdir -p ~/.gemini/antigravity/skills/documentacion-proyecto