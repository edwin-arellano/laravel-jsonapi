<?php

namespace App\Http\Resources;

use App\JsonApi\Traits\JsonApiResource;
use App\Http\Resources\CategoryResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    use JsonApiResource;

    public function toJsonApi(): array
    {
        return [
            'title' => $this->resource->title,
            'slug' => $this->resource->slug,
            'content' => $this->resource->content,
        ];
    }

    public function getRelationshipLinks(): array
    {
        return ['category'];
    }

    public function getIncludes(): array
    {
        return [
            CategoryResource::make($this->whenLoaded('category')),
        ];
    }
}
