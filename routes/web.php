<?php

use App\Http\Controllers\Admin\EventCheckInController;
use App\Http\Controllers\Admin\EventReminderController;
use App\Http\Controllers\Admin\EventReportController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminInscripcionController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EventoController;
use App\Http\Controllers\InscripcionController;
use App\Http\Controllers\InscripcionQrController;
use App\Http\Controllers\PanelDashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }

    return view('welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/eventos', [EventoController::class, 'index'])->name('eventos.index');
    Route::get('/eventos/{evento}', [EventoController::class, 'show'])->name('eventos.show');
    Route::get('/inscripciones/{inscripcion}/qr.svg', [InscripcionQrController::class, 'show'])->name('inscripciones.qr');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('users', AdminUserController::class)->except('show');

    Route::get('/eventos/crear', [EventoController::class, 'create'])->name('eventos.create');
    Route::post('/eventos', [EventoController::class, 'store'])->name('eventos.store');
    Route::get('/eventos/{evento}/editar', [EventoController::class, 'edit'])->name('eventos.edit');
    Route::patch('/eventos/{evento}', [EventoController::class, 'update'])->name('eventos.update');
    Route::delete('/eventos/{evento}', [EventoController::class, 'destroy'])->name('eventos.destroy');
    Route::get('/eventos/{evento}/reporte', [EventReportController::class, 'show'])->name('eventos.report');
    Route::get('/eventos/{evento}/check-in', [EventCheckInController::class, 'show'])->name('eventos.checkin');
    Route::post('/eventos/{evento}/check-in/validar', [EventCheckInController::class, 'store'])->name('eventos.checkin.validate');
    Route::post('/eventos/{evento}/recordatorios', [EventReminderController::class, 'store'])->name('eventos.reminders.store');
    Route::get('/eventos', [EventoController::class, 'index'])->name('eventos.index');

    Route::get('/inscritos', [AdminInscripcionController::class, 'index'])->name('inscripciones.index');
});

Route::middleware(['auth', 'user.role'])->prefix('panel')->name('panel.')->group(function () {
    Route::get('/dashboard', [PanelDashboardController::class, 'index'])->name('dashboard');
    Route::post('/eventos/{evento}/inscribirse', [InscripcionController::class, 'store'])->name('inscripciones.store');
    Route::delete('/eventos/{evento}/cancelar-inscripcion', [InscripcionController::class, 'destroy'])->name('inscripciones.destroy');
});

require __DIR__.'/auth.php';
