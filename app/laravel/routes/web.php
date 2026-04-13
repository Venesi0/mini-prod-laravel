<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminControllers\DashboardController;
use App\Http\Controllers\AdminControllers\AdminClientsController;
use App\Http\Controllers\AdminControllers\AdminProfileController;
use App\Http\Controllers\AdminControllers\ProjectDetailsController;
use App\Http\Controllers\AdminControllers\ProjectsController;
use App\Http\Controllers\AdminControllers\SettingsController;
use App\Http\Controllers\AdminControllers\TicketsController;

use App\Http\Controllers\UserControllers\UserContactController;
use App\Http\Controllers\UserControllers\UserProfileController;
use App\Http\Controllers\UserControllers\UserProjectDetailsController;
use App\Http\Controllers\UserControllers\UserProjectsController;
use App\Http\Controllers\UserControllers\UserSettingsController;
use App\Http\Controllers\UserControllers\UserTicketsController;

use App\Http\Controllers\CollaboratorsController;

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Routes communes (ok pour admin + user)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Routes User
    Route::get('/user/contact', [UserContactController::class, 'index'])->name('user.contact');
    Route::get('/user/profile', [UserProfileController::class, 'index'])->name('user.profile');
    Route::get('/user/projects/{project}', [UserProjectDetailsController::class, 'index'])->name('user.projects.show');
    Route::get('/user/projects', [UserProjectsController::class, 'index'])->name('user.projects');
    Route::get('/user/tickets', [UserTicketsController::class, 'index'])->name('user.tickets');
    Route::get('/user/settings', [UserSettingsController::class, 'index'])->name('user.settings');
});

// Routes Admin (bloquées aux admins)
Route::middleware(['auth', 'isAdmin'])->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::get('/admin/clients', [AdminClientsController::class, 'index'])->name('admin.clients');
    Route::post('/admin/clients', [AdminClientsController::class, 'store'])->name('admin.clients.store');
    Route::put('/admin/clients/{client}', [AdminClientsController::class, 'update'])->name('admin.clients.update');
    Route::delete('/admin/clients/{client}', [AdminClientsController::class, 'destroy'])->name('admin.clients.destroy');

    Route::get('/admin/projects', [ProjectsController::class, 'index'])->name('admin.projects');
    Route::post('/admin/projects', [ProjectsController::class, 'store'])->name('admin.projects.store');
    Route::put('/admin/projects/{project}', [ProjectsController::class, 'update'])->name('admin.projects.update');
    Route::delete('/admin/projects/{project}', [ProjectsController::class, 'destroy'])->name('admin.projects.destroy');

    Route::get('/admin/profile', [AdminProfileController::class, 'index'])->name('admin.profile');
    Route::patch('/admin/profile', [AdminProfileController::class, 'update'])->name('admin.profile.update');
    Route::get('/admin/settings', [SettingsController::class, 'index'])->name('admin.settings');
    Route::put('/admin/settings/password', [SettingsController::class, 'updatePassword'])->name('admin.settings.password.update');

    Route::get('/admin/projects/{project}', [ProjectDetailsController::class, 'index'])->name('admin.projects.show');
    Route::post('/admin/projects/{project}/tickets', [ProjectDetailsController::class, 'storeTicket'])->name('admin.projects.tickets.store');

    Route::get('/admin/tickets', [TicketsController::class, 'index'])->name('admin.tickets');
    Route::post('/admin/tickets', [TicketsController::class, 'store'])->name('admin.tickets.store');
    Route::put('/admin/tickets/{ticket}', [TicketsController::class, 'update'])->name('admin.tickets.update');
    Route::delete('/admin/tickets/{ticket}', [TicketsController::class, 'destroy'])->name('admin.tickets.destroy');

    Route::get('/collaborators', [CollaboratorsController::class, 'index'])->name('collaborators');
    Route::post('/collaborators', [CollaboratorsController::class, 'store'])->name('collaborators.store');
    Route::put('/collaborators/{collaborator}', [CollaboratorsController::class, 'update'])->name('collaborators.update');
    Route::delete('/collaborators/{collaborator}', [CollaboratorsController::class, 'destroy'])->name('collaborators.destroy');

});

require __DIR__ . '/auth.php';
