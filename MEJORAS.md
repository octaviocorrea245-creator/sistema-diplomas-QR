# Plan de Mejoras — Sistema de Diplomas QR (600 hrs)

## Estado actual
Proyecto Laravel 10 funcional al ~85%. Incluye: autenticación por username, RBAC (supervisor/admin/beneficiario), CRUD de departamentos/cursos/alumnos, editor visual de plantillas con Fabric.js, generación masiva de PDF con TCPDF, códigos QR, firma digital SAT e.firma, verificación pública por QR.

---

## FASE 1 — Funcionalidades faltantes del plan original (60 hrs)

| # | Mejora | Descripción | Estimado |
|---|--------|-------------|----------|
| 1 | **Modelo Auditoria** | Crear modelo `Auditoria.php` (la migration ya existe) e integrarlo en acciones críticas (emitir, firmar, reimprimir, crear/editar plantillas) | 6 hrs |
| 2 | **Dashboard de Supervisor** | Vista con estadísticas globales (alumnos totales, cursos por depto, diplomas emitidos, firmantes próximos a expirar, gráficas) | 12 hrs |
| 3 | **Dashboard de Beneficiario** | Vista para que el alumno vea sus diplomas emitidos, descargue PDF, vea estado de cursos | 10 hrs |
| 4 | **Revocación y Reemisión** | UI para revocar un diploma (cambiar estado a `revocado`) y reemitir uno nuevo con nuevo folio + token | 10 hrs |
| 5 | **Controlador de Reimpresión** | Crear `ReimpresionController` con ruta y vista para que supervisores registren reimpresiones | 8 hrs |
| 6 | **Seeders** | Crear seeders para roles, permisos, departamentos, cursos, alumnos, plantillas demo | 6 hrs |
| 7 | **Notificaciones por Email** | Enviar correo al alumno cuando se le emite un diploma con enlace de descarga y QR | 8 hrs |

## FASE 2 — Calidad y pruebas (70 hrs)

| # | Mejora | Descripción | Estimado |
|---|--------|-------------|----------|
| 8 | **Tests Unitarios** | Tests para Models (relaciones, scopes, accessors, helpers) y Services (QrGenerator, PdfGenerator, PdfSigner, DiplomaRenderer) | 20 hrs |
| 9 | **Tests de Feature** | Tests para cada controlador (CRUD, generación masiva, firma, verificación pública) con cobertura de autorización por roles | 25 hrs |
| 10 | **Laravel Dusk (Browser Tests)** | Tests de interfaz: login, editor de plantillas, flujo completo de emisión | 15 hrs |
| 11 | **PHPStan / Larastan** | Análisis estático de tipos, nivel máximo | 5 hrs |
| 12 | **Laravel Pint** | Estandarización de estilo de código ya integrada (configurar y ejecutar) | 2 hrs |
| 13 | **GitHub Actions CI/CD** | Pipeline de tests automáticos en cada push/PR con PHPStan, Pint, PHPUnit, Dusk | 3 hrs |

## FASE 3 — API e integraciones (60 hrs)

| # | Mejora | Descripción | Estimado |
|---|--------|-------------|----------|
| 14 | **API RESTful completa** | Endpoints para: CRUD de cursos/alumnos, emisión de diplomas, verificación, consulta de estado. Autenticación con Sanctum. Rate limiting | 25 hrs |
| 15 | **Documentación de API (Scribe/Swagger)** | Documentación interactiva de todos los endpoints | 6 hrs |
| 16 | **Webhooks** | Sistema de webhooks para notificar a sistemas externos cuando se emite, firma o revoca un diploma | 8 hrs |
| 17 | **Integración con sistema escolar** | Endpoints para importar/exportar datos desde sistemas externos (CSV, JSON, XML) | 10 hrs |
| 18 | **Validación de firma contra SAT** | Módulo para validar la e.firma contra los servicios web del SAT | 8 hrs |
| 19 | **OAuth2 / SSO** | Inicio de sesión con Google, Microsoft, o LDAP/AD | 10 hrs |

## FASE 4 — Reportes y analíticas (50 hrs)

| # | Mejora | Descripción | Estimado |
|---|--------|-------------|----------|
| 20 | **Reportes avanzados** | Reportes exportables (PDF/Excel/CSV): diplomas emitidos por periodo, por depto, por curso, firmados vs no firmados | 12 hrs |
| 21 | **Gráficas y dashboards** | Dashboard interactivo con Chart.js: tendencias de emisión, distribución por depto, tasa de firmado | 10 hrs |
| 22 | **Log de actividad global** | Interfaz de búsqueda y filtrado del log de auditoría con exportación | 8 hrs |
| 23 | **Reporte de firmantes** | Vista de certificados próximos a expirar, historial de firmas por firmante | 6 hrs |
| 24 | **Tracker de verificación QR** | Estadísticas de cuántas veces se verificó cada diploma, desde qué IPs, geolocalización | 8 hrs |
| 25 | **Exportación masiva** | Exportar lotes de diplomas, datos de alumnos, cursos en múltiples formatos | 6 hrs |

## FASE 5 — Experiencia de usuario y frontend (80 hrs)

| # | Mejora | Descripción | Estimado |
|---|--------|-------------|----------|
| 26 | **Editor de plantillas mejorado** | Multi-página (anverso/reverso), grid snapping, guías inteligentes, zoom, undo/redo, capas (z-index), alineación automática | 25 hrs |
| 27 | **Previsualización en tiempo real** | Vista previa del diploma en el editor mientras se arrastran elementos | 10 hrs |
| 28 | **Clonación de plantillas** | Duplicar plantillas entre departamentos o cursos con ajustes | 5 hrs |
| 29 | **Galería de fondos** | Biblioteca de imágenes de fondo prediseñadas para plantillas | 4 hrs |
| 30 | **Responsive design** | Asegurar que todas las vistas funcionen en tablets/móviles (actualmente solo desktop) | 10 hrs |
| 31 | **Modo oscuro** | Implementar tema oscuro en toda la interfaz | 6 hrs |
| 32 | **Carga masiva mejorada** | Importación de alumnos con preview, mapeo de columnas, validación en vivo, reporte de errores | 8 hrs |
| 33 | **Notificaciones en tiempo real** | Usar Laravel Echo + WebSockets para notificar al admin cuando termine generación masiva | 8 hrs |
| 34 | **Componentes Alpine.js reutilizables** | Refactorizar JS para usar componentes Alpine bien definidos en lugar de lógica inline | 4 hrs |

## FASE 6 — PDF, QR y documentos (50 hrs)

| # | Mejora | Descripción | Estimado |
|---|--------|-------------|----------|
| 35 | **Generación asíncrona con Colas** | Mover generación masiva de PDFs a Jobs en cola (Redis/Database) con progreso visible | 12 hrs |
| 36 | **Cola de firmado digital** | Firma masiva en segundo plano con notificación al completar | 6 hrs |
| 37 | **Estilos de QR personalizados** | QR con colores, logos centrales, patrones personalizados (manteniendo decodificabilidad) | 6 hrs |
| 38 | **PDF protegido** | Agregar contraseña de apertura, restricción de impresión/edición al PDF según configuración | 6 hrs |
| 39 | **Marca de agua** | Watermark digital en PDFs de verificación pública | 4 hrs |
| 40 | **Firma visible personalizada** | Imagen de firma escaneada + sello en el PDF (además de la firma digital invisible) | 6 hrs |
| 41 | **Batch de regeneración** | Regenerar PDFs masivamente cuando se actualiza una plantilla | 4 hrs |
| 42 | **Compresión de PDFs** | Optimizar tamaño de archivos PDF generados (imágenes, fuentes embebidas) | 6 hrs |

## FASE 7 — Seguridad y compliance (40 hrs)

| # | Mejora | Descripción | Estimado |
|---|--------|-------------|----------|
| 43 | **2FA (Autenticación de dos factores)** | Implementar Google Authenticator / TOTP para admins y supervisores | 10 hrs |
| 44 | **Auditoría de acceso** | Log de inicios de sesión exitosos y fallidos con IP, user-agent, ubicación | 6 hrs |
| 45 | **Límites de rate** | Rate limiting en rutas críticas (login, verificación QR, API) | 4 hrs |
| 46 | **Política de contraseñas** | Exigir complejidad, rotación, historial de contraseñas | 5 hrs |
| 47 | **GDPR / privacidad** | Funcionalidad de anonimización de datos de alumnos, exportación de datos personales, eliminación lógica | 8 hrs |
| 48 | **Firma de código** | Verificación de integridad de archivos generados (hash SHA-256 del PDF en BD) | 4 hrs |
| 49 | **Cifrado de respaldo** | Cifrado de backups de .cer/.key y PDFs en almacenamiento | 3 hrs |

## FASE 8 — DevOps, despliegue y escalabilidad (60 hrs)

| # | Mejora | Descripción | Estimado |
|---|--------|-------------|----------|
| 50 | **Docker** | Docker Compose para entorno local: app, nginx, mysql, redis, mailpit | 8 hrs |
| 51 | **Laravel Horizon** | Panel de monitoreo de colas con Horizon | 4 hrs |
| 52 | **Laravel Telescope** | Debugging y monitoreo en desarrollo (requests, exceptions, logs, queries) | 4 hrs |
| 53 | **Laravel Pulse** | Monitoreo de rendimiento en producción (slow queries, rutas lentas, uso de servidor) | 4 hrs |
| 54 | **Sentry / Error tracking** | Integración de monitoreo de errores en producción | 3 hrs |
| 55 | **Backup automático** | Script de backup de BD + storage (PDFs, certificados) a S3/Wasabi | 6 hrs |
| 56 | **Despliegue automatizado** | Deployer / Envoyer para despliegues zero-downtime | 6 hrs |
| 57 | **Caché y optimización** | Redis para caché de sesiones, consultas, vistas. Indexación de BD (análisis de queries lentas) | 8 hrs |
| 58 | **Almacenamiento externo** | Migrar PDFs a S3/Cloud con firmas de acceso temporales (presigned URLs) | 8 hrs |
| 59 | **Load testing** | Pruebas de carga con K6/Locust para generación masiva y verificación QR | 5 hrs |
| 60 | **Health checks** | Endpoint de health check con estado de servicios (BD, Redis, colas, almacenamiento) | 4 hrs |

## FASE 9 — Features avanzadas (80 hrs)

| # | Mejora | Descripción | Estimado |
|---|--------|-------------|----------|
| 61 | **Multi-institucion (multi-tenancy)** | Soporte para múltiples instituciones/organizaciones con datos aislados | 25 hrs |
| 62 | **White-labeling** | Personalización de marca por institución (logo, colores, dominio, email) | 10 hrs |
| 63 | **Plantillas multilingüe** | Diplomas en varios idiomas con detección automática o selección manual | 8 hrs |
| 64 | **Certificados digitales avanzados** | Soporte para firma múltiple (varios firmantes en un mismo diploma) | 10 hrs |
| 65 | **Calendario de cursos** | Vista de calendario con fechas de cursos, integración con Google Calendar / iCal | 6 hrs |
| 66 | **Generación de constancias** | Módulo separado para constancias de estudio, cartas de recomendación, etc. | 8 hrs |
| 67 | **Expediente del alumno** | Perfil completo del alumno con historial de diplomas, cursos, constancias | 6 hrs |
| 68 | **Código de barras / Datamatrix** | Además de QR, soporte para código de barras lineal y Datamatrix | 4 hrs |
| 69 | **API pública de verificación** | Endpoint abierto para que cualquier sistema verifique un diploma por token QR (sin autenticación) | 3 hrs |

## FASE 10 — Migración y refactorización (50 hrs)

| # | Mejora | Descripción | Estimado |
|---|--------|-------------|----------|
| 70 | **Unificar sistema de plantillas** | Migrar del legacy `Plantilla/VersionPlantilla` al moderno `DiplomaTemplate`; eliminar código duplicado | 12 hrs |
| 71 | **Refactorizar nomenclatura** | Unificar `department_id` vs `departamento_id` en toda la base de código | 6 hrs |
| 72 | **Eliminar dependencias no usadas** | Revisar y remover `barryvdh/laravel-dompdf` (config existe pero no se usa) y `simplesoftwareio/simple-qrcode` si no es necesario | 3 hrs |
| 73 | **DTOs / Action Classes** | Refactorizar lógica de controladores hacia Action classes/Service classes y DTOs | 10 hrs |
| 74 | **Repository Pattern** | Implementar patrón Repository para desacoplar lógica de BD | 8 hrs |
| 75 | **Mejorar manejo de errores** | Personalizar páginas de error (403, 404, 500), responses JSON consistentes en API | 5 hrs |
| 76 | **Migrations limpias** | Squash de migrations para producción (de 24 a ~10 archivos) | 6 hrs |

---

## Resumen por fase

| Fase | Área | Horas |
|------|------|-------|
| 1 | Funcionalidades faltantes | 60 |
| 2 | Calidad y pruebas | 70 |
| 3 | API e integraciones | 60 |
| 4 | Reportes y analíticas | 50 |
| 5 | UX y frontend | 80 |
| 6 | PDF, QR y documentos | 50 |
| 7 | Seguridad y compliance | 40 |
| 8 | DevOps y escalabilidad | 60 |
| 9 | Features avanzadas | 80 |
| 10 | Refactorización | 50 |
| **Total** | | **600 hrs** |

## Prioridades sugeridas

1. **Fase 1** — Completar lo planeado originalmente + Fase 7 (seguridad mínima)
2. **Fase 2** — Tests para no romper existente
3. **Fase 10** — Pagar deuda técnica antes de crecer
4. **Fase 6** — Escalar generación de PDFs
5. **Fase 5 + 4** — Mejorar UX y reportes
6. **Fase 3** — API para integraciones
7. **Fase 8** — Preparar para producción real
8. **Fase 9** — Features avanzadas para escalar a otras instituciones
