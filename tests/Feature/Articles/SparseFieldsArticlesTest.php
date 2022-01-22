<?php

namespace Tests\Feature\Articles;

use Tests\TestCase;
use App\Models\Article;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SparseFieldsArticlesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function specific_fields_can_be_requested()
    {
        $article = Article::factory()->create();

        // articles?fields[articles]=title,slug
        $url = route('api.v1.articles.index', [
            'fields' => [
                'articles' => 'title,slug',
            ],
        ]);

        $this->getJson($url)->assertJsonFragment([
            'title' => $article->title,
            'slug' => $article->slug,
        ])->assertJsonMissing([
            'content' => $article->content,
        ])->assertJsonMissing([
            'content' => null,
        ]);
    }

    /** @test */
    public function route_key_must_be_added_automatically()
    {
        $article = Article::factory()->create();

        // articles?fields[articles]=title
        $url = route('api.v1.articles.index', [
            'fields' => [
                'articles' => 'title',
            ],
        ]);

        $this->getJson($url)->assertJsonFragment([
            'title' => $article->title,
        ])->assertJsonMissing([
            'slug' => $article->slug,
            'content' => $article->content,
        ]);
    }
}
