<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Article;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleResource;

class ArticleController extends Controller
{
    public function show(Article $article): ArticleResource
    {
        return ArticleResource::make($article);
    }
}
