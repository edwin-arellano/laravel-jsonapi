<?php

namespace App\JsonApi;

use Closure;
use Illuminate\Support\Str;
use PHPUnit\Framework\Assert as PHPUnit;
use PHPUnit\Framework\ExpectationFailedException;

class JsonApiTestResponse
{
    public function assertJsonApiValidationErrors(): Closure
    {
        return function ($attribute) {
            $pointer = "/data/attributes/{$attribute}";

            if (Str::of($attribute)->startsWith('data')) {
                $pointer = '/'.str_replace('.', '/', $attribute);
            } elseif (Str::of($attribute)->startsWith('relationships')) {
                $pointer = '/'.str_replace('.', '/', $attribute).'/data/id';
            }

            try {
                $this->assertJsonFragment([
                    'source' => ['pointer' => $pointer],
                ]);
            } catch (ExpectationFailedException $e) {
                PHPUnit::fail(
                    "Failed to find a JSON:API validation error for key: '{$attribute}'"
                    . PHP_EOL .
                    $e->getMessage()
                );
            }

            try {
                $this->assertJsonStructure([
                    'errors' => [
                        ['title', 'detail', 'source' => ['pointer']]
                    ]
                ]);
            } catch (ExpectationFailedException $e) {
                PHPUnit::fail(
                    "Failed to find a valid JSON:API error response"
                    . PHP_EOL .
                    $e->getMessage()
                );
            }

            $this->assertHeader(
                'content-type', 'application/vnd.api+json'
            );

            $this->assertStatus(422);
        };
    }

    public function assertJsonApiResource(): Closure
    {
        return function ($model, $attributes) {
            return $this->assertJson([
                'data' => [
                    'type' => $model->getResourceType(),
                    'id' => (string) $model->getRouteKey(),
                    'attributes' => $attributes,
                    'links' => [
                        'self' => route('api.v1.'.$model->getResourceType().'.show', $model)
                    ]
                ]
            ])->assertHeader(
                'Location',
                route('api.v1.'.$model->getResourceType().'.show', $model)
            );
        };
    }

    public function assertJsonApiRelationshipLinks(): Closure
    {
        return function($model, $relations) {
            foreach ($relations as $relation) {
                $this->assertJson([
                    'data' => [
                        'relationships' => [
                            'category' => [
                                'links' => [
                                    'self' => route("api.v1.{$model->getResourceType()}.relationships.{$relation}", $model),
                                    'related' => route("api.v1.{$model->getResourceType()}.{$relation}", $model),
                                ],
                            ],
                        ],
                    ],
                ]);
            }

            return $this;
        };
    }

    public function assertJsonApiResourceCollection(): Closure
    {
        return function ($models, $attributesKeys) {
            $this->assertJsonStructure([
                'data' => [
                    '*' => [
                        'attributes' => $attributesKeys,
                    ],
                ],
            ]);

            foreach ($models as $model) {
                $this->assertJsonFragment([
                    'type' => $model->getResourceType(),
                    'id' => (string) $model->getRouteKey(),
                    'links' => [
                        'self' => route('api.v1.'.$model->getResourceType().'.show', $model),
                    ],
                ]);
            }

            return $this;
        };
    }
}