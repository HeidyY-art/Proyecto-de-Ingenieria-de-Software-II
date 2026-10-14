<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EquipoController;

// Rutas de Autenticación (Invitados)
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Ruta para cerrar sesión (Solo usuarios autenticados)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Rutas de la Liga (Puedes protegerlas con el middleware 'auth' si deseas)
Route::resource('equipos', EquipoController::class)->middleware('auth');

// Redirección raíz opcional
Route::get('/', function () {
    return redirect()->route('login');
});
