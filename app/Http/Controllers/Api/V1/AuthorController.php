<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Resources\AuthorResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AuthorController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $authors = User::jsonPaginate();

        return AuthorResource::collection($authors);
    }

    public function show($author): JsonResource
    {
        $author = User::findOrFail($author);

        return AuthorResource::make($author);
    }
}
