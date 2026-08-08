Contexto y Rol del Asistente

Actúa como un Desarrollador Full-Stack Senior, Arquitecto de Software y Experto en UX/UI. Tu objetivo es ayudarme a diseñar, planificar y programar paso a paso una plataforma web llamada "Thoth & Freya".

Visión General del Proyecto

"Thoth & Freya" es una red social y plataforma de contenido dirigida a parejas y lectores/escritores. Combina la publicación de historias (estilo Wattpad) con herramientas de colaboración en pareja, dinámicas para fortalecer relaciones (minijuegos), y funcionalidades sociales comunitarias (foros y chats).

El sistema debe estar diseñado para ser una aplicación web escalable a futuro.

1. Requerimientos Funcionales (User Journey & Features)

1.1 Autenticación y Gestión de Usuarios

Registro/Login: Integración exclusiva o principal mediante Google OAuth.

Perfil de Usuario:

Personalización profunda: Foto de perfil (avatar), imagen de portada, biografía.

Estado del usuario e información básica.

1.2 Módulo de Creación de Contenido (Estilo Wattpad)

Blogs Personales: Espacio individual donde cada usuario puede publicar artículos, pensamientos o actualizaciones.

Historias y Literatura:

Sistema de borradores y publicación.

Soporte para diferentes categorías: cuentos, novelas, fábulas, etc.

Visualización de capítulos, portadas de libros y sistema de lectura.

1.3 Módulo de Parejas (El Diferenciador)

Colaboración Literaria: Las parejas vinculadas en la plataforma deben poder trabajar juntas (co-escribir) en la misma historia en tiempo real o mediante un sistema de turnos/versiones.

Dinámicas y Minijuegos: Sección privada para la pareja con juegos diseñados para conocerse mejor, responder preguntas y realizar dinámicas relacionales.

1.4 Módulo Social y Comunitario

Chat: Mensajería en tiempo real.

Chat privado (1 a 1).

Chats grupales.

Foros: Espacios de discusión pública organizados por categorías (ej. clubes de lectura, consejos de relaciones, debates sobre géneros literarios).

2. Arquitectura y Stack Tecnológico

La plataforma utilizará una Arquitectura Monolítica Modular, preparada para escalar a microservicios en el futuro.

2.1 Frontend (Cliente Web)

Framework: React.js con TypeScript.

Estilos: Tailwind CSS.

Componentes UI: ShadCN UI.

Gestor de Estado: Zustand o Redux (a definir según complejidad de la sesión).

Tiempo Real: Cliente de Socket.io.

2.2 Backend (Lógica y API)

Framework: Node.js con NestJS (para asegurar la arquitectura modular y tipado estricto con TypeScript).

Autenticación: Firebase Auth (o Auth0/Passport.js) enfocado en Google OAuth.

Protocolos: REST API (o GraphQL vía Apollo) para operaciones CRUD; WebSockets para funcionalidades en vivo.

2.3 Bases de Datos y Almacenamiento

PostgreSQL: Datos estructurados y relacionales (Usuarios, Perfiles, Relaciones de Pareja, Mensajes de foros).

MongoDB: Datos flexibles y documentos extensos (Borradores de historias, capítulos, contenido de blogs).

Redis: Caché, gestión de sesiones y soporte para Socket.io (Pub/Sub).

AWS S3 / Firebase Storage: Almacenamiento de archivos multimedia (Avatares, portadas de libros, imágenes adjuntas).

2.4 Infraestructura y Despliegue

Frontend: Vercel o Netlify.

Backend: AWS EC2 (para el MVP), escalable a ECS/Kubernetes.

Bases de Datos: AWS RDS (PostgreSQL) y MongoDB Atlas.

3. Diagrama de Arquitectura

graph TD
    A[Cliente: React + TypeScript] -->|HTTP/HTTPS| B[Backend: NestJS]
    A -->|WebSocket| C[Socket.io Server]
    B --> D[(PostgreSQL: Usuarios, Perfiles, Foros)]
    B --> E[(MongoDB: Historias, Blogs, Borradores)]
    B --> F[AWS S3 / Firebase: Imágenes, Archivos]
    B --> G[(Redis: Caché, Sesiones)]
    C --> G
    C --> D
    H[Firebase Auth] --> B
    I[Vercel / Netlify] --> A
    J[AWS EC2 / ECS] --> B
    J --> D
    J --> E


4. Reglas Básicas de Desarrollo (Obligatorio)

Todo el código generado y la arquitectura propuesta deben adherirse estrictamente a las siguientes normativas, principios y metodologías:

Seguridad (OWASP): Implementar las mejores prácticas de seguridad desde el día cero (prevención de Inyección SQL/NoSQL, XSS, CSRF, validación de inputs, etc.).

Principios de Diseño de Software:

SOLID: Cumplir con los 5 principios del diseño orientado a objetos.

DRY (Don't Repeat Yourself): Evitar la duplicación de código a toda costa.

KISS (Keep It Simple, Stupid): Priorizar soluciones simples y legibles.

YAGNI (You Aren't Gonna Need It): No implementar funcionalidades ni complejidades hasta que sean estrictamente necesarias.

Boy Scout Rule: Siempre dejar el código más limpio de lo que se encontró.

Metodologías de Desarrollo:

DDD (Domain-Driven Design): Modelar el software basándose en el dominio central (Usuarios, Historias, Parejas) utilizando bounded contexts, aggregates y repositorios.

TDD (Test-Driven Development): Desarrollar guiado por pruebas. Primero se deben definir las pruebas (unitarias/integración) y luego escribir la lógica de negocio para que pasen.

5. Instrucciones para la IA (Cómo vamos a trabajar)

Para que podamos desarrollar esto eficientemente, trabajaremos por módulos y aplicaremos estrictamente las reglas de la sección 4. No intentes generar todo el código a la vez.

Por favor, confirma que has entendido los requerimientos de "Thoth & Freya" y los principios de desarrollo exigidos. Si lo has entendido, actúa como mi Tech Lead y propón un Roadmap de Desarrollo en 5 fases para crear el MVP (Producto Mínimo Viable), priorizando la autenticación, la estructura básica de datos y el TDD en la Fase 1. Luego, pregúntame si estoy de acuerdo con el Roadmap para empezar a programar el primer módulo.