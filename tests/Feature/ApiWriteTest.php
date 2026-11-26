<?php

use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Laravel\Sanctum\PersonalAccessToken;

function apiPostData(array $overrides = []): array
{
    return array_merge([
        'title' => 'Article envoye par API',
        'slug' => 'article-envoye-par-api',
        'excerpt' => 'Un article cree avec un jeton.',
        'body' => 'Le contenu complet de cet article, envoye en JSON.',
        'is_published' => true,
    ], $overrides);
}

it('refuses to create a post without a token', function () {
    $this->postJson('/api/posts', apiPostData())
        ->assertUnauthorized()
        ->assertJsonPath('message', 'Unauthenticated.');

    $this->assertDatabaseCount('posts', 0);
});

it('creates a post for the authenticated token owner and syncs its tags', function () {
    $author = User::factory()->create();
    $tag = Tag::factory()->create();
    Sanctum::actingAs($author);

    $this->postJson('/api/posts', apiPostData(['tags' => [$tag->id]]))
        ->assertCreated()
        ->assertJsonPath('data.slug', 'article-envoye-par-api')
        ->assertJsonPath('data.author.name', $author->name)
        ->assertJsonPath('data.tags.0.id', $tag->id);

    $this->assertDatabaseHas('posts', [
        'slug' => 'article-envoye-par-api',
        'user_id' => $author->id,
    ]);
    $this->assertDatabaseHas('post_tag', [
        'tag_id' => $tag->id,
        'post_id' => Post::whereSlug('article-envoye-par-api')->value('id'),
    ]);
});

it('returns French validation errors for an invalid post', function () {
    Sanctum::actingAs(User::factory()->create());

    $this->postJson('/api/posts', apiPostData(['title' => '']))
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['title'])
        ->assertJsonPath('errors.title.0', 'Le champ titre est obligatoire.');
});

it('forbids updating another authors post before validation', function () {
    $post = Post::factory()->create(['user_id' => User::factory()->create()->id]);
    Sanctum::actingAs(User::factory()->create());

    $this->putJson('/api/posts/'.$post->id, ['title' => ''])
        ->assertForbidden();
});

it('allows an author to update their own post', function () {
    $author = User::factory()->create();
    $post = Post::factory()->create(['user_id' => $author->id]);
    Sanctum::actingAs($author);

    $this->putJson('/api/posts/'.$post->id, apiPostData([
        'title' => 'Titre mis a jour',
        'slug' => 'titre-mis-a-jour',
    ]))
        ->assertOk()
        ->assertJsonPath('data.title', 'Titre mis a jour');

    $this->assertDatabaseHas('posts', ['id' => $post->id, 'title' => 'Titre mis a jour']);
});

it('lets the author delete their own post', function () {
    $author = User::factory()->create();
    $post = Post::factory()->create(['user_id' => $author->id]);
    Sanctum::actingAs($author);

    $this->deleteJson('/api/posts/'.$post->id)->assertNoContent();

    $this->assertDatabaseMissing('posts', ['id' => $post->id]);
});

it('issues a token that authenticates protected API routes', function () {
    $user = User::factory()->create();

    $token = $this->postJson('/api/login', [
        'email' => $user->email,
        'password' => 'password',
    ])
        ->assertCreated()
        ->assertJsonStructure(['token', 'token_type'])
        ->assertJsonPath('token_type', 'Bearer')
        ->json('token');

    $this->assertDatabaseCount('personal_access_tokens', 1);

    $this->withToken($token)
        ->getJson('/api/user')
        ->assertOk()
        ->assertJsonPath('email', $user->email);
});

it('rejects invalid login credentials with a neutral message', function () {
    $user = User::factory()->create();

    $this->postJson('/api/login', ['email' => $user->email, 'password' => 'incorrect'])
        ->assertUnprocessable()
        ->assertJsonPath('errors.email.0', 'Ces identifiants ne correspondent à aucun compte.');

    $this->assertDatabaseCount('personal_access_tokens', 0);
});

it('revokes only the current token on logout', function () {
    $user = User::factory()->create();
    $token = $user->createToken('api')->plainTextToken;
    $user->createToken('second-device');

    $this->withToken($token)
        ->postJson('/api/logout')
        ->assertNoContent();

    $this->assertDatabaseCount('personal_access_tokens', 1);
    expect(PersonalAccessToken::findToken($token))->toBeNull();

    $this->app['auth']->forgetGuards();
    $this->withToken($token)->getJson('/api/user')->assertUnauthorized();
});
