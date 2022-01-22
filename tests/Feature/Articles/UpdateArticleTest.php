<?php

namespace Tests\Feature\Articles;

use Tests\TestCase;
use App\Models\Article;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateArticleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_update_articles()
    {
        $article = Article::factory()->create();

        $response = $this->patchJson(route('api.v1.articles.update', $article), [
            'title' => 'Updated Article',
            'slug' => $article->slug,
            'content' => 'Updated content.'
        ])->assertOk();

        $response->assertHeader(
            'Location',
            route('api.v1.articles.show', $article)
        );

        $response->assertExactJson([
            'data' => [
                'type' => 'articles',
                'id' => (string) $article->getRouteKey(),
                'attributes' => [
                    'title' => 'Updated Article',
                    'slug' => $article->slug,
                    'content' => 'Updated content.'
                ],
                'links' => [
                    'self' => route('api.v1.articles.show', $article)
                ]
            ]
        ]);
    }

    /** @test */
    public function title_is_required()
    {
        $article = Article::factory()->create();

        $this->patchJson(route('api.v1.articles.update', $article), [
            'slug' => 'updated-article',
            'content' => 'Updated content.'
        ])->assertJsonApiValidationErrors('title');
    }

    /** @test */
    public function title_must_be_at_least_4_characters()
    {
        $article = Article::factory()->create();

        $this->patchJson(route('api.v1.articles.update', $article), [
            'title' => 'Upd',
            'slug' => 'updated-article',
            'content' => 'Updated content.'
        ])->assertJsonApiValidationErrors('title');
    }

    /** @test */
    public function slug_is_required()
    {
        $article = Article::factory()->create();

        $this->patchJson(route('api.v1.articles.update', $article), [
            'title' => 'Updated Article',
            'content' => 'Updated content.'
        ])->assertJsonApiValidationErrors('slug');
    }

    /** @test */
    public function slug_must_be_unique()
    {
        $article1 = Article::factory()->create();
        $article2 = Article::factory()->create();

        $this->patchJson(route('api.v1.articles.update', $article1), [
            'title' => 'Updated Article',
            'slug' => $article2->slug,
            'content' => 'Updated content.'
        ])->assertJsonApiValidationErrors('slug');
    }

    /** @test */
    public function slug_must_only_contain_letters_numbers_and_dashes()
    {
        $article = Article::factory()->create();

        $this->patchJson(route('api.v1.articles.update', $article), [
            'title' => 'Nuevo artículo',
            'slug' => '$%&()',
            'content' => 'Contenido del artículo.'
        ])->assertJsonApiValidationErrors('slug');
    }

    /** @test */
    public function slug_must_not_contain_underscores()
    {
        $article = Article::factory()->create();

        $this->patchJson(route('api.v1.articles.update', $article), [
            'title' => 'Nuevo artículo',
            'slug' => 'with_underscores',
            'content' => 'Contenido del artículo.'
        ])->assertSee(trans('validation.no_underscores', [
            'attribute' => 'data.attributes.slug'
        ]))->assertJsonApiValidationErrors('slug');
    }

    /** @test */
    public function slug_must_not_starts_with_dashes()
    {
        $article = Article::factory()->create();

        $this->patchJson(route('api.v1.articles.update', $article), [
            'title' => 'Nuevo artículo',
            'slug' => '-starts-with-dashes',
            'content' => 'Contenido del artículo.'
        ])->assertSee(trans('validation.no_starting_dashes', [
            'attribute' => 'data.attributes.slug'
        ]))->assertJsonApiValidationErrors('slug');
    }

    /** @test */
    public function slug_must_not_ends_with_dashes()
    {
        $article = Article::factory()->create();

        $this->patchJson(route('api.v1.articles.update', $article), [
            'title' => 'Nuevo artículo',
            'slug' => 'ends-with-dashes-',
            'content' => 'Contenido del artículo.'
        ])->assertSee(trans('validation.no_ending_dashes', [
            'attribute' => 'data.attributes.slug'
        ]))->assertJsonApiValidationErrors('slug');
    }

    /** @test */
    public function content_is_required()
    {
        $article = Article::factory()->create();

        $this->patchJson(route('api.v1.articles.update', $article), [
            'title' => 'Updated Article',
            'slug' => 'updated-article',
        ])->assertJsonApiValidationErrors('content');
    }
}
