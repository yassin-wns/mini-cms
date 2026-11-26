<?php

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;

it('lists published posts with the API resource shape', function () {
    Post::factory()->published()->count(2)->create();
    Post::factory()->create(['title' => 'Brouillon cache']);

    $this->getJson('/api/posts')
        ->assertOk()
        ->assertJsonCount(2, 'data')
        ->assertJsonStructure([
            'data' => ['*' => ['id', 'title', 'slug', 'excerpt', 'published_at', 'author', 'category', 'url']],
            'links' => ['first', 'last', 'prev', 'next'],
            'meta' => ['current_page', 'last_page', 'per_page', 'total'],
        ]);
});

it('filters posts by category slug and retains pagination query parameters', function () {
    $category = Category::factory()->create();
    Post::factory()->published()->create(['category_id' => $category->id, 'title' => 'Dans la categorie']);
    Post::factory()->published()->create(['title' => 'Hors categorie']);

    $this->getJson('/api/posts?category='.$category->slug.'&per_page=1')
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.title', 'Dans la categorie')
        ->assertJsonPath('data.0.category.slug', $category->slug)
        ->assertJsonPath('meta.per_page', 1);
});

it('rejects an unknown category slug with a French validation message', function () {
    $this->getJson('/api/posts?category=inexistante')
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['category'])
        ->assertJsonPath('errors.category.0', 'La valeur sélectionnée pour le champ catégorie est invalide.');
});

it('filters posts by tag slug', function () {
    $tag = Tag::factory()->create();
    $taggedPost = Post::factory()->published()->create(['title' => 'Avec le tag']);
    $untaggedPost = Post::factory()->published()->create(['title' => 'Sans le tag']);
    $taggedPost->tags()->attach($tag);

    $this->getJson('/api/posts?tag='.$tag->slug)
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.id', $taggedPost->id)
        ->assertJsonPath('data.0.title', 'Avec le tag');

    expect($untaggedPost->id)->not->toBe($taggedPost->id);
});

it('lists categories and tags with counts of published posts only', function () {
    $category = Category::factory()->create();
    $tag = Tag::factory()->create();
    $published = Post::factory()->published()->count(2)->create(['category_id' => $category->id]);
    $draft = Post::factory()->create(['category_id' => $category->id]);
    $published->each(fn (Post $post) => $post->tags()->attach($tag));
    $draft->tags()->attach($tag);

    $this->getJson('/api/categories')
        ->assertOk()
        ->assertJsonPath('data.0.slug', $category->slug)
        ->assertJsonPath('data.0.posts_count', 2);

    $this->getJson('/api/tags')
        ->assertOk()
        ->assertJsonPath('data.0.slug', $tag->slug)
        ->assertJsonPath('data.0.posts_count', 2);
});

it('returns JSON 404 responses for unknown categories and tags', function () {
    $this->getJson('/api/categories/999')
        ->assertNotFound()
        ->assertJsonStructure(['message']);

    $this->getJson('/api/tags/999')
        ->assertNotFound()
        ->assertJsonStructure(['message']);
});
