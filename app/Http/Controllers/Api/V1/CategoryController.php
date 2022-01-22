<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Category;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CategoryController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $categories = Category::query()
            ->allowedFilters(['name', 'year', 'month'])
            ->allowedSorts(['name'])
            ->sparseFieldset()
            ->jsonPaginate();

        return CategoryResource::collection($categories);
    }

    public function show($category): JsonResource
    {
        $category = Category::where('slug', $category)
            ->sparseFieldset()
            ->firstOrFail();

        return CategoryResource::make($category);
    }
}
