<?php

use Illuminate\Support\Facades\Route;
use Salehye\Settings\Http\Controllers\SettingsController;

Route::prefix('settings')->group(function () {
    Route::get('/', [SettingsController::class, 'index'])->name('settings.index');
    Route::get('/{group}', [SettingsController::class, 'show'])->name('settings.show');
    Route::put('/', [SettingsController::class, 'update'])->name('settings.update');
    Route::get('/api/public', [SettingsController::class, 'public'])->name('settings.public');

    // Image upload routes
    Route::post('/{key}/image', [SettingsController::class, 'uploadImage'])->name('settings.upload-image');
    Route::post('/{key}/image/base64', [SettingsController::class, 'uploadBase64Image'])->name('settings.upload-base64');
    Route::delete('/{key}/image', [SettingsController::class, 'deleteImage'])->name('settings.delete-image');
    Route::get('/{key}/image-url', [SettingsController::class, 'getImageUrl'])->name('settings.image-url');
});
