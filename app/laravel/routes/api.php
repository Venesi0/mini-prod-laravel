<?php

use App\Http\Controllers\CollaboratorsController;
use Illuminate\Support\Facades\Route;

Route::post('/collaborators', [CollaboratorsController::class, 'storeApi'])->name('api.collaborators.storeApi');