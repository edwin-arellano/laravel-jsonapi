<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Article;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleResource;
use App\Http\Resources\ArticleCollection;

class ArticleController extends Controller
{
    public function index(): ArticleCollection
    {
        return ArticleCollection::make(Article::all());
    }

    public function show(Article $article): ArticleResource
    {
        return ArticleResource::make($article);
    }
}
