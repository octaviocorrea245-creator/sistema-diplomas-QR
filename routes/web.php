<?php
use App\Http\Controllers\Supervisor\CursosController as SupervisorCursosController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AlumnoController;
use App\Http\Controllers\CursosController;
use App\Http\Controllers\DepartamentoController;
use App\Http\Controllers\ProfileController;
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
});

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

        Route::resource('alumnos', AlumnoController::class)->only([
            'index', 'show'
        ]);

        Route::resource('cursos', SupervisorCursosController::class)->only([
            'index', 'show'
        ]);

        Route::get('cursos/{curso}/alumnos', [SupervisorCursosController::class, 'alumnos'])
            ->name('cursos.alumnos');

        Route::resource('departamentos', DepartamentoController::class)->only([
            'index', 'create', 'store', 'edit', 'update', 'destroy'
        ])->parameters(['departamentos' => 'departamento']);
    });

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('cursos', CursosController::class);
});

require __DIR__ . '/auth.php';