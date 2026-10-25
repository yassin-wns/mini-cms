# Session 03 - Routes, contrôleurs et layout

## Défi

URL produite par route('about') :
http://localhost:8000/a-propos

URL produite par route('posts.show', 'routes-et-controleurs') :
http://localhost:8000/posts/routes-et-controleurs

URL produite par route('posts.archive', 2025) :
http://localhost:8000/posts/archive/2025

Pourquoi /posts/archive/abc et /posts/article-inconnu répondent toutes deux 404, et quelle ligne de code en est responsable dans chaque cas ?
- `/posts/archive/abc` : `whereNumber('year')` dans `routes/web.php` rejette la route car 'abc' n'est pas un nombre, donc aucune route ne correspond et Laravel renvoie une 404.
- `/posts/article-inconnu` : la route correspond bien au modèle `posts/{slug}`, mais dans la méthode `show()` de `PostController`, la clé n'existe pas dans le tableau et `abort(404)` est appelé.

Que se passe-t-il si une vue appelle route('posts.show') sans paramètre ?
Laravel déclenche une exception `Missing required parameter for [Route: posts.show] [URI: posts/{slug}]`.
