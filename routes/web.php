<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MaterialSapController;
use App\Http\Controllers\CiclicoController;


Route::get('/', function () {
    return view('home.index');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('/login-planta', function () {
    return view('home.login_planta');
})->name('login.planta');

Route::get('/control-planta', function () {
    return view('home.control_planta');
})->name('control.planta');

Route::get('/login-general', function () {
    return view('home.login_general');
})->name('login.general');

// Rutas de Autenticación
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rutas de Administración
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/roles', [RoleController::class, 'index'])->name('roles');
    Route::post('/roles', [RoleController::class, 'store'])->name('roles.store');
    Route::delete('/roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');

    Route::get('/users', [UserController::class, 'index'])->name('users');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
});

// Rutas de Materiales SAP
Route::get('/materiales', [MaterialSapController::class, 'index'])->name('materiales.index');
Route::post('/materiales', [MaterialSapController::class, 'store'])->name('materiales.store');
Route::post('/materiales/import', [MaterialSapController::class, 'import'])->name('materiales.import');
Route::put('/materiales/{material}', [MaterialSapController::class, 'update'])->name('materiales.update');
Route::delete('/materiales/{material}', [MaterialSapController::class, 'destroy'])->name('materiales.destroy');
// Rutas de Inventario
Route::get('/inventario/ciclico', [CiclicoController::class, 'index'])->name('inventario.index');
Route::post('/inventario/store', [CiclicoController::class, 'store'])->name('inventario.store');
Route::get('/inventario/sesion/{ciclico}', [CiclicoController::class, 'show'])->name('inventario.show');
Route::post('/inventario/import/{ciclico}', [CiclicoController::class, 'import'])->name('inventario.import');
Route::post('/inventario/suggest/{ciclico}', [CiclicoController::class, 'suggestItems'])->name('inventario.suggest');
Route::post('/inventario/add-material/{ciclico}', [CiclicoController::class, 'addItem'])->name('inventario.add_item');
Route::post('/inventario/start-counting/{ciclico}', [CiclicoController::class, 'startCounting'])->name('inventario.start_counting');
Route::post('/inventario/next-attempt/{ciclico}', [CiclicoController::class, 'nextAttempt'])->name('inventario.next_attempt');
Route::post('/inventario/register-count/{ciclico}', [CiclicoController::class, 'registerCount'])->name('inventario.register_count');
Route::post('/inventario/finish-count/{ciclico}', [CiclicoController::class, 'finishCount'])->name('inventario.finish_count');
Route::post('/inventario/close/{ciclico}', [CiclicoController::class, 'close'])->name('inventario.close');
Route::delete('/inventario/{ciclico}', [CiclicoController::class, 'destroy'])->name('inventario.destroy');
