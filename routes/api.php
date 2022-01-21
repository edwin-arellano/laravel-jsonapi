<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\ArticleController;

Route::name('api.')->group(function () {
    Route::name('v1.')->prefix('v1')->group(function() {
        Route::apiResource('articles', ArticleController::class);
    });
});
