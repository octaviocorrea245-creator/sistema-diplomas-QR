# Plan de Implementación: Sistema de Gestión y Emisión de Diplomas con Código QR

Este plan describe la arquitectura y las tareas necesarias para implementar el sistema basándose en el documento de requerimientos proporcionado.

---

## Revisión del Usuario Requerida

> [!IMPORTANT]
> **Generación de PDFs en el Servidor:** Utilizaremos el paquete `barryvdh/laravel-dompdf` (se instalará mediante `composer require barryvdh/laravel-dompdf`). Esto compilará la imagen de fondo, el código QR y los detalles del alumno posicionados dinámicamente en un archivo PDF final.
> 
> **Editor Visual de Plantillas:** El editor cargará **Fabric.js** (mediante CDN) en el frontend. El administrador subirá una imagen de fondo y podrá arrastrar, soltar y cambiar el tamaño de los placeholders (`[Nombre Alumno]`, `[Curso]`, `[Código QR]`). Al guardar la plantilla, esta estructura de coordenadas se guardará como un JSON (`fabric_json`) en la tabla `versiones_plantilla`.

---

## Preguntas Abiertas

> [!WARNING]
> - ¿Tienes imágenes de fondo o PDFs reales para las plantillas que desees usar, o creamos archivos de prueba genéricos?
> - **Bloqueo de versiones:** Una vez emitido un diploma con una versión de plantilla, esta versión quedará bloqueada para edición para asegurar la integridad de los documentos ya emitidos. Si se requiere algún cambio, el sistema generará automáticamente una nueva versión (ej. de Versión 1 a Versión 2). ¿Estás de acuerdo con este flujo?

---

## Cambios Propuestos

### Modelos y Base de Datos

#### 1. [NEW] [Plantilla.php](file:///C:/Users/DELL/Download/diplomasQR/app/Models/Plantilla.php)
- Representa una plantilla de diplomas creada por un departamento.
- Relaciones:
  - `departamento()` (pertenece a `Departamento`)
  - `versiones()` (tiene muchas `VersionPlantilla`)
  - `cursos()` (pertenece a muchos `Cursos` a través de la tabla pivote `plantilla_curso`)

#### 2. [NEW] [VersionPlantilla.php](file:///C:/Users/DELL/Download/diplomasQR/app/Models/VersionPlantilla.php)
- Versión específica de una plantilla que almacena el JSON de coordenadas del canvas y la ruta del PDF/imagen base.
- Relaciones:
  - `plantilla()` (pertenece a `Plantilla`)
  - `diplomas()` (tiene muchos `Diploma`)

#### 3. [NEW] [Diploma.php](file:///C:/Users/DELL/Download/diplomasQR/app/Models/Diploma.php)
- Representa un diploma emitido para un alumno.
- Relaciones:
  - `alumno()` (pertenece a `User` / `user_id`)
  - `curso()` (pertenece a `Cursos`)
  - `versionPlantilla()` (pertenece a `VersionPlantilla`)
  - `emisor()` (pertenece a `User` / `emitido_por`)
  - `reimpresiones()` (tiene muchas `Reimpresion`)

#### 4. [NEW] [Reimpresion.php](file:///C:/Users/DELL/Download/diplomasQR/app/Models/Reimpresion.php)
- Historial de reimpresión de un diploma por parte de un supervisor.
- Relaciones:
  - `diploma()` (pertenece a `Diploma`)
  - `supervisor()` (pertenece a `User` / `user_id`)

#### 5. [NEW] [Auditoria.php](file:///C:/Users/DELL/Download/diplomasQR/app/Models/Auditoria.php)
- Registro de auditoría para acciones críticas ("crear plantilla", "emitir diploma", "reimprimir").
- Relaciones:
  - `user()` (pertenece a `User`)

---

### Controladores

#### 1. [NEW] [PlantillaController.php](file:///C:/Users/DELL/Download/diplomasQR/app/Http/Controllers/PlantillaController.php)
- ABM (CRUD) de plantillas para administradores de departamento.
- Carga de imágenes/PDFs base y guardado del layout de Fabric.js.
- Control de versiones (crea una nueva versión si la actual ya emitió diplomas y está bloqueada).

#### 2. [NEW] [DiplomaController.php](file:///C:/Users/DELL/Download/diplomasQR/app/Http/Controllers/DiplomaController.php)
- Emisión masiva o individual de diplomas para un curso y sus alumnos inscritos.
- Generación de `token_qr` único, `folio` correlativo, renderizado y guardado del PDF físico.
- Ruta pública `/verificar/{token_qr}` para escanear y verificar la autenticidad del diploma.

#### 3. [NEW] [ReimpresionController.php](file:///C:/Users/DELL/Download/diplomasQR/app/Http/Controllers/ReimpresionController.php)
- Gestiona la descarga/reimpresión de diplomas existentes por parte del supervisor y su registro.

#### 4. [NEW] [SupervisorController.php](file:///C:/Users/DELL/Download/diplomasQR/app/Http/Controllers/SupervisorController.php)
- Dashboard global para el supervisor con estadísticas generales de alumnos, cursos, diplomas y logs de auditoría.

---

### Vistas

#### 1. [MODIFY] [dashboard.blade.php](file:///C:/Users/DELL/Download/diplomasQR/resources/views/dashboard.blade.php)
- Mostrar accesos rápidos según el rol (`supervisor`, `admin`, `beneficiario`).

#### 2. [NEW] Vistas de Plantillas (`admin/plantillas/`)
- `index.blade.php`: Lista de plantillas y sus versiones del departamento.
- `create.blade.php` / `edit.blade.php`: Interfaz con canvas drag-and-drop de Fabric.js.

#### 3. [NEW] Vistas de Diplomas (`admin/diplomas/`)
- `index.blade.php`: Listado de diplomas emitidos por el departamento.
- `create.blade.php`: Selección de curso y alumnos para emitir.

#### 4. [NEW] [supervisor/auditorias.blade.php](file:///C:/Users/DELL/Download/diplomasQR/resources/views/supervisor/auditorias.blade.php)
- Vista para que el supervisor consulte el log de auditoría.

#### 5. [NEW] [verificar.blade.php](file:///C:/Users/DELL/Download/diplomasQR/resources/views/verificar.blade.php)
- Página pública de verificación del diploma al escanear el código QR.

---

## Plan de Verificación

### Pruebas Automatizadas
- Seeders de prueba con departamentos, cursos y alumnos para simular un ambiente completo.
- Validación de accesos: Verificar que un admin no pueda editar plantillas de otro departamento, y que un usuario beneficiario solo tenga acceso a su dashboard y la página de verificación.

### Verificación Manual
- Subir una imagen de fondo y colocar campos de texto dinámicos en el editor.
- Generar un diploma y verificar que la versión de la plantilla se bloquee.
- Escanear el código QR generado y verificar que dirija correctamente a la pantalla pública de verificación.
