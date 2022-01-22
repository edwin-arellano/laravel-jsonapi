<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\ArticleController;
use App\Http\Controllers\Api\V1\CategoryController;

Route::apiResource('articles', ArticleController::class);
Route::apiResource('categories', CategoryController::class);
