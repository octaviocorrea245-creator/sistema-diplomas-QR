<?php

use App\Http\Controllers\Admin\AlumnoController as AdminAlumnoController;
use App\Http\Controllers\Admin\CursoAlumnoController as AdminCursoAlumnoController;
use App\Http\Controllers\Admin\DiplomaController as AdminDiplomaController;
use App\Http\Controllers\Admin\DisenadorController as AdminDisenadorController;
use App\Http\Controllers\Admin\PlantillaController as AdminPlantillaController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\CursosController;
use App\Http\Controllers\DepartamentoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Supervisor\AlumnoController as SupervisorAlumnoController;
use App\Http\Controllers\Supervisor\CursoAlumnoController as SupervisorCursoAlumnoController;
use App\Http\Controllers\Supervisor\CursosController as SupervisorCursosController;
use App\Http\Controllers\Supervisor\NotificacionController as SupervisorNotificacionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/avatar', [ProfileController::class, 'uploadAvatar'])->name('profile.avatar');
});

// ----------------------------------------------------------
// Supervisor
// ----------------------------------------------------------
Route::middleware(['auth', 'role:supervisor'])
    ->prefix('supervisor')
    ->name('supervisor.')
    ->group(function () {

        Route::get('/', function () {
            return view('supervisor.index');
        })->name('index');

        Route::resource('admins', AdminUserController::class)->only([
            'index', 'create', 'store', 'edit', 'update', 'destroy'
        ]);

        Route::get('alumnos',          [SupervisorAlumnoController::class, 'index'])->name('alumnos.index');
        Route::get('alumnos/{alumno}', [SupervisorAlumnoController::class, 'show'])->name('alumnos.show');
        Route::post('alumnos/{alumno}/avatar', [SupervisorAlumnoController::class, 'uploadAvatar'])->name('alumnos.avatar');

        Route::resource('cursos', SupervisorCursosController::class)->only([
            'index', 'show'
        ]);

        Route::get('cursos/{curso}/alumnos',          [SupervisorCursoAlumnoController::class, 'index'])->name('cursos.alumnos.index');
        Route::get('cursos/{curso}/alumnos/{alumno}', [SupervisorCursoAlumnoController::class, 'show'])->name('cursos.alumnos.show');

        Route::resource('departamentos', DepartamentoController::class)->only([
            'index', 'create', 'store', 'edit', 'update', 'destroy'
        ])->parameters(['departamentos' => 'departamento']);

        // Notifications
        Route::get('notificaciones',                 [SupervisorNotificacionController::class, 'index'])->name('notificaciones.index');
        Route::post('notificaciones/{id}/read',      [SupervisorNotificacionController::class, 'markAsRead'])->name('notificaciones.markRead');
        Route::post('notificaciones/mark-all-read',  [SupervisorNotificacionController::class, 'markAllAsRead'])->name('notificaciones.markAllRead');
    });
// ----------------------------------------------------------
// Admin (diseño de diplomas + ver cursos): admin y diseñador
// ----------------------------------------------------------
Route::middleware(['auth', 'role:admin|diseñador'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('cursos',         [CursosController::class, 'index'])->name('cursos.index');
        Route::get('cursos/{curso}', [CursosController::class, 'show'])->name('cursos.show')->whereNumber('curso');

        Route::resource('templates', App\Http\Controllers\Admin\TemplateController::class)->only([
            'index', 'create', 'store', 'show', 'edit', 'update', 'destroy'
        ]);
        Route::get('templates/{template}/editor', [App\Http\Controllers\Admin\TemplateController::class, 'editor'])
            ->name('templates.editor');
        Route::post('templates/{template}/upload-background', [App\Http\Controllers\Admin\TemplateController::class, 'uploadBackground'])
            ->name('templates.upload-background');
        Route::post('templates/{template}/remove-background', [App\Http\Controllers\Admin\TemplateController::class, 'removeBackground'])
            ->name('templates.remove-background');
        Route::post('templates/{template}/save-elements', [App\Http\Controllers\Admin\TemplateController::class, 'saveElements'])
            ->name('templates.save-elements');
        Route::post('templates/{template}/upload-image', [App\Http\Controllers\Admin\TemplateController::class, 'uploadImage'])
            ->name('templates.upload-image');
        Route::get('templates/crear-para-curso/{curso}', [App\Http\Controllers\Admin\TemplateController::class, 'createForCourse'])
            ->name('templates.create-for-course');
    });

// ----------------------------------------------------------
// Admin
// ----------------------------------------------------------
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/', [AdminController::class, 'index'])->name('index');

        Route::resource('cursos', CursosController::class)->only([
            'create', 'store', 'edit', 'update', 'destroy'
        ]);

        Route::get('alumnos/importar',      [AdminAlumnoController::class, 'importForm'])->name('alumnos.importar');
        Route::post('alumnos/importar',     [AdminAlumnoController::class, 'import'])->name('alumnos.importar.store');
        Route::get('alumnos/plantilla-csv', [AdminAlumnoController::class, 'downloadTemplate'])->name('alumnos.plantilla');
        Route::resource('alumnos', AdminAlumnoController::class)->parameters(['alumnos' => 'alumno']);

        Route::resource('plantillas', AdminPlantillaController::class)->only([
            'index', 'create', 'store', 'edit', 'update', 'destroy', 'show'
        ]);

        Route::prefix('cursos/{curso}/alumnos')->name('cursos.alumnos.')->group(function () {
            Route::get('/',              [AdminCursoAlumnoController::class, 'index'])          ->name('index');
            Route::get('/create',        [AdminCursoAlumnoController::class, 'create'])         ->name('create');
            Route::post('/',             [AdminCursoAlumnoController::class, 'store'])          ->name('store');
            Route::get('/importar',      [AdminCursoAlumnoController::class, 'importForm'])     ->name('importar');
            Route::post('/importar',     [AdminCursoAlumnoController::class, 'import'])         ->name('importar.store');
            Route::get('/plantilla-csv', [AdminCursoAlumnoController::class, 'downloadTemplate'])->name('plantilla');
            Route::get('/{alumno}',      [AdminCursoAlumnoController::class, 'show'])           ->name('show');
            Route::get('/{alumno}/edit', [AdminCursoAlumnoController::class, 'edit'])           ->name('edit');
            Route::put('/{alumno}',      [AdminCursoAlumnoController::class, 'update'])         ->name('update');
            Route::delete('/{alumno}',   [AdminCursoAlumnoController::class, 'destroy'])        ->name('destroy');
        });

        Route::resource('disenadores', AdminDisenadorController::class)->only([
            'index', 'create', 'store', 'edit', 'update', 'destroy'
        ]);

        Route::prefix('diplomas/masiva')->name('diplomas.mass.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\MassDiplomaController::class, 'create'])
                ->name('create');
            Route::post('/', [App\Http\Controllers\Admin\MassDiplomaController::class, 'store'])
                ->name('store');
            Route::get('curso/{curso}', [App\Http\Controllers\Admin\MassDiplomaController::class, 'show'])
                ->name('show');
            Route::get('descargar/{diploma}', [App\Http\Controllers\Admin\MassDiplomaController::class, 'download'])
                ->name('download');
            Route::get('descargar-todos/{curso}', [App\Http\Controllers\Admin\MassDiplomaController::class, 'downloadAll'])
                ->name('download-all');
            Route::get('descargar-combinado/{curso}', [App\Http\Controllers\Admin\MassDiplomaController::class, 'downloadCombined'])
                ->name('download-combined');
            Route::post('regenerar/{curso}', [App\Http\Controllers\Admin\MassDiplomaController::class, 'regenerate'])
                ->name('regenerate');
            Route::post('generar-individual/{curso}/{alumno}', [App\Http\Controllers\Admin\MassDiplomaController::class, 'generateIndividual'])
                ->name('generate-individual');
            Route::post('regenerar-individual/{diploma}', [App\Http\Controllers\Admin\MassDiplomaController::class, 'regenerateIndividual'])
                ->name('regenerate-individual');
            Route::post('generar-rapido/{curso}', [App\Http\Controllers\Admin\MassDiplomaController::class, 'generateQuick'])
                ->name('generate-quick');
        });

        Route::resource('diplomas', AdminDiplomaController::class)->only([
            'index', 'create', 'store', 'show'
        ]);

        // ── Firmantes (gestión de e.firma) ──────────────────────────────────
        Route::resource('firmantes', App\Http\Controllers\Admin\FirmanteController::class)->only([
            'index', 'create', 'store', 'show', 'edit', 'update', 'destroy'
        ]);
        Route::post('firmantes/{firmante}/toggle-activo',
            [App\Http\Controllers\Admin\FirmanteController::class, 'toggleActivo'])
            ->name('firmantes.toggle-activo');
        Route::get('firmantes/{firmante}/firmar/{diploma}',
            [App\Http\Controllers\Admin\FirmanteController::class, 'firmarForm'])
            ->name('firmantes.firmar.form');
        Route::post('firmantes/{firmante}/firmar/{diploma}',
            [App\Http\Controllers\Admin\FirmanteController::class, 'firmar'])
            ->name('firmantes.firmar');
        Route::post('firmantes/{firmante}/firmar-masivo',
            [App\Http\Controllers\Admin\FirmanteController::class, 'firmarMasivo'])
            ->name('firmantes.firmar-masivo');

        Route::get('alumnos-por-curso/{curso}', [AdminDiplomaController::class, 'alumnosPorCurso'])->name('alumnos-por-curso');
        Route::get('versiones-por-plantilla/{plantilla}', [AdminDiplomaController::class, 'versionesPorPlantilla'])->name('versiones-por-plantilla');
    });

// ----------------------------------------------------------
// Preview de plantilla (admin + supervisor)
// ----------------------------------------------------------
Route::middleware('auth')->get('/admin/templates/{template}/preview', [App\Http\Controllers\Admin\TemplateController::class, 'preview'])
    ->name('templates.preview');

// ----------------------------------------------------------
// Escáner QR público
// ----------------------------------------------------------
Route::get('/escanear', [App\Http\Controllers\EscanearController::class, 'index'])->name('escanear');

// ----------------------------------------------------------
// Verificación pública de diplomas (sin autenticación)
// ----------------------------------------------------------
Route::get('/verificar/{token}', [App\Http\Controllers\VerificarDiplomaController::class, 'show'])
    ->name('verificar');
Route::get('/verificar/{token}/imagen', [App\Http\Controllers\VerificarDiplomaController::class, 'imagen'])
    ->name('verificar.imagen');
Route::get('/verificar/{token}/pdf', [App\Http\Controllers\VerificarDiplomaController::class, 'pdf'])
    ->name('verificar.pdf');

require __DIR__ . '/auth.php';
