<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\ArticleController;
use App\Http\Controllers\Api\V1\CategoryController;

Route::apiResource('articles', ArticleController::class);
Route::apiResource('categories', CategoryController::class);

Route::get('articles/{article}/relationships/category', fn() => 'TODO')
    ->name('articles.relationships.category');

Route::get('articles/{article}/category', fn() => 'TODO')
    ->name('articles.category');