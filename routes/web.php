<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AlumnoController;
use App\Http\Controllers\CursosController;
use App\Http\Controllers\DepartamentoController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

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

// ── GRUPO PRINCIPAL DEL SUPERVISOR ──────────────────────────────────────────
Route::middleware(['auth', 'role:supervisor'])
    ->prefix('supervisor')
    ->name('supervisor.')
    ->group(function () {

        // 1. Vista: resources/views/supervisor/index.blade.php
        Route::get('/', function () {
            return view('supervisor.index');
        })->name('index');

        // 2. Módulo: Admins
        Route::resource('admins', AdminUserController::class)->only([
            'index', 'create', 'store', 'edit', 'update', 'destroy'
        ]);

        // 3. Módulo: Alumnos
        Route::resource('alumnos', AlumnoController::class)->only([
            'index', 'show'
        ]);

        // 4. Módulo: Cursos
        Route::resource('cursos', CursosController::class)->only([
            'index', 'show'
        ]);

        Route::get('cursos/{curso}/alumnos', [CursosController::class, 'alumnos'])
            ->name('cursos.alumnos');

        // 5. Módulo: Departamentos
        Route::resource('departamentos', DepartamentoController::class)->only([
            'index', 'create', 'store', 'edit', 'update', 'destroy'
        ])->parameters([
            'departamentos' => 'departamento'
        ]);

    });

// ── GRUPO DE ADMINISTRADORES (USUARIO NORMAL) ────────────────────────────────
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::resource('cursos', CursosController::class);
    });

require __DIR__.'/auth.php';
