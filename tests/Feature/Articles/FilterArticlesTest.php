<?php

namespace Tests\Feature\Articles;

use Tests\TestCase;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FilterArticlesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_filter_articles_by_title()
    {
        Article::factory()->create([
            'title' => 'Laravel Json API',
        ]);

        Article::factory()->create([
            'title' => 'Other Article',
        ]);

        $url = route('api.v1.articles.index', [
            'filter' => [
                'title' => 'Laravel',
            ],
        ]);

        $this->getJson($url)
            ->assertJsonCount(1, 'data')
            ->assertSee('Laravel Json API')
            ->assertDontSee('Other Article');
    }

    /** @test */
    public function can_filter_articles_by_content()
    {
        Article::factory()->create([
            'content' => 'Laravel Json API',
        ]);

        Article::factory()->create([
            'content' => 'Other Article',
        ]);

        $url = route('api.v1.articles.index', [
            'filter' => [
                'content' => 'Laravel',
            ],
        ]);

        $this->getJson($url)
            ->assertJsonCount(1, 'data')
            ->assertSee('Laravel Json API')
            ->assertDontSee('Other Article');
    }

    /** @test */
    public function can_filter_articles_by_year()
    {
        Article::factory()->create([
            'title' => 'Article from 2021',
            'created_at' => now()->year(2021),
        ]);

        Article::factory()->create([
            'title' => 'Article from 2022',
            'created_at' => now()->year(2022),
        ]);

        $url = route('api.v1.articles.index', [
            'filter' => [
                'year' => '2021',
            ],
        ]);

        $this->getJson($url)
            ->assertJsonCount(1, 'data')
            ->assertSee('Article from 2021')
            ->assertDontSee('Article from 2022');
    }

    /** @test */
    public function can_filter_articles_by_month()
    {
        Article::factory()->create([
            'title' => 'Article from month 3',
            'created_at' => now()->month(3),
        ]);

        Article::factory()->create([
            'title' => 'Another Article from month 3',
            'created_at' => now()->month(3),
        ]);

        Article::factory()->create([
            'title' => 'Article from month 1',
            'created_at' => now()->month(1),
        ]);

        $url = route('api.v1.articles.index', [
            'filter' => [
                'month' => '3',
            ],
        ]);

        $this->getJson($url)
            ->assertJsonCount(2, 'data')
            ->assertSee('Article from month 3')
            ->assertSee('Another Article from month 3')
            ->assertDontSee('Article from month 1');
    }

    /** @test */
    public function can_filter_articles_by_category()
    {
        Article::factory()->count(2)->create();
        $category1 = Category::factory()->hasArticles(3)->create(['slug' => 'category-1']);
        $category2 = Category::factory()->hasArticles(1)->create(['slug' => 'category-2']);

        // articles?filter[categories]=category-1,category-2
        $url = route('api.v1.articles.index', [
            'filter' => [
                'categories' => 'category-1,category-2',
            ],
        ]);

        $this->getJson($url)
            ->assertJsonCount(4, 'data')
            ->assertSee($category1->articles[0]->title)
            ->assertSee($category1->articles[1]->title)
            ->assertSee($category1->articles[2]->title)
            ->assertSee($category2->articles[0]->title);
    }

    /** @test */
    public function cannot_filter_articles_by_unknown_filters()
    {
        Article::factory()->count(2)->create();

        $url = route('api.v1.articles.index', [
            'filter' => [
                'unknown' => 'filter',
            ],
        ]);

        $this->getJson($url)->assertStatus(400);
    }
}
