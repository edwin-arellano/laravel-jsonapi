<?php

namespace Tests\Feature\Articles;

use Tests\TestCase;
use App\Models\Article;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteArticleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_delete_articles()
    {
        $article = Article::factory()->create();

        $response = $this->deleteJson(route('api.v1.articles.destroy', $article))
            ->assertNoContent();

        $this->assertDatabaseCount('articles', 0);
    }
}
