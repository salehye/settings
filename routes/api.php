<?php

use Illuminate\Support\Facades\Route;
use Salehye\Settings\Http\Controllers\SettingsController;

Route::prefix('settings')->group(function () {
    Route::get('/', [SettingsController::class, 'index'])->name('settings.index');
    Route::get('/{group}', [SettingsController::class, 'show'])->name('settings.show');
    Route::put('/', [SettingsController::class, 'update'])->name('settings.update');
    Route::get('/api/public', [SettingsController::class, 'public'])->name('settings.public');
});
