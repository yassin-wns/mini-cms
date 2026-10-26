# Session 07 - Défi : articles de la même catégorie

La section « Dans la même catégorie » apparaît sous un article publié qui a une catégorie : oui
L'article affiché n'apparaît pas dans sa propre section : oui
Un brouillon de la même catégorie n'apparaît pas : oui
La page s'ouvre sans LazyLoadingViolationException : oui
Le nouveau test passe et php artisan test est vert : oui

Combien de requêtes SQL charge la section, et pourquoi pas une par carte ?
La section charge au total 4 requêtes SQL bien ciblées grâce au chargement hâtif (*eager loading*) :
1. Une requête SELECT sur `posts` avec `where category_id = ... and is_published = 1 and id != ... limit 3`.
2. Une requête sur `users` pour les auteurs (`where id in (...)`).
3. Une requête sur `categories` (`where id in (...)`).
4. Une requête sur la table pivot `post_tag` / `tags` pour les tags associés.
On n'a donc pas une requête par carte (problème N+1 évité) car `with('user', 'category', 'tags')` regroupe la récupération de toutes les relations nécessaires en requêtes uniques avec `IN (...)`.
