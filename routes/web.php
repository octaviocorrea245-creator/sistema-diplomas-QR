<?php

use App\Http\Controllers\Admin\AlumnoController as AdminAlumnoController;
use App\Http\Controllers\Admin\CursoAlumnoController as AdminCursoAlumnoController;
use App\Http\Controllers\Admin\DiplomaController as AdminDiplomaController;
use App\Http\Controllers\Admin\PlantillaController as AdminPlantillaController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AlumnoController;
use App\Http\Controllers\CursosController;
use App\Http\Controllers\DepartamentoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Supervisor\AlumnoController as SupervisorAlumnoController;
use App\Http\Controllers\Supervisor\CursoAlumnoController as SupervisorCursoAlumnoController;
use App\Http\Controllers\Supervisor\CursosController as SupervisorCursosController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
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

        // Alumnos (módulo nuevo)
        Route::get('alumnos',          [SupervisorAlumnoController::class, 'index'])->name('alumnos.index');
        Route::get('alumnos/{alumno}', [SupervisorAlumnoController::class, 'show'])->name('alumnos.show');

        Route::resource('cursos', SupervisorCursosController::class)->only([
            'index', 'show'
        ]);

        // Alumnos por curso (módulo nuevo)
        Route::get('cursos/{curso}/alumnos',          [SupervisorCursoAlumnoController::class, 'index'])->name('cursos.alumnos.index');
        Route::get('cursos/{curso}/alumnos/{alumno}', [SupervisorCursoAlumnoController::class, 'show'])->name('cursos.alumnos.show');

        Route::resource('departamentos', DepartamentoController::class)->only([
            'index', 'create', 'store', 'edit', 'update', 'destroy'
        ])->parameters(['departamentos' => 'departamento']);
    });

// ----------------------------------------------------------
// Admin
// ----------------------------------------------------------
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/', [AdminController::class, 'index'])->name('index');

        Route::resource('cursos', CursosController::class);

        // Alumnos del departamento (CRUD completo)
        Route::resource('alumnos', AdminAlumnoController::class)->parameters(['alumnos' => 'alumno']);

        // Alumnos por curso: CRUD completo (módulo nuevo)
        Route::prefix('cursos/{curso}/alumnos')->name('cursos.alumnos.')->group(function () {
            Route::get('/',              [AdminCursoAlumnoController::class, 'index'])  ->name('index');
            Route::get('/create',        [AdminCursoAlumnoController::class, 'create']) ->name('create');
            Route::post('/',             [AdminCursoAlumnoController::class, 'store'])  ->name('store');
            Route::get('/{alumno}',      [AdminCursoAlumnoController::class, 'show'])   ->name('show');
            Route::get('/{alumno}/edit', [AdminCursoAlumnoController::class, 'edit'])   ->name('edit');
            Route::put('/{alumno}',      [AdminCursoAlumnoController::class, 'update']) ->name('update');
            Route::delete('/{alumno}',   [AdminCursoAlumnoController::class, 'destroy'])->name('destroy');
        });

        // Plantillas (sistema anterior)
        Route::resource('plantillas', AdminPlantillaController::class)->only([
            'index', 'create', 'store', 'edit', 'update', 'destroy', 'show'
        ]);

        // Plantillas de Diploma (nuevo sistema visual)
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

        // Diplomas
        Route::resource('diplomas', AdminDiplomaController::class)->only([
            'index', 'create', 'store', 'show'
        ]);

        // Generación masiva de diplomas con plantillas visuales
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
        });

        // JSON endpoints para formulario de diploma
        Route::get('alumnos-por-curso/{curso}', [AdminDiplomaController::class, 'alumnosPorCurso'])->name('alumnos-por-curso');
        Route::get('versiones-por-plantilla/{plantilla}', [AdminDiplomaController::class, 'versionesPorPlantilla'])->name('versiones-por-plantilla');
    });

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