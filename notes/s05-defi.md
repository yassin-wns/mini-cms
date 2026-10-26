# Session 05 - Défi : articles récents sur l'accueil

latestPosts contient au plus 3 articles : oui
Seuls les articles publiés sont transmis à la vue : oui
home.blade.php utilise x-post-card et @forelse : oui
Le nouveau test passe et php artisan test est vert : oui

Pourquoi get() et pas paginate(3) sur la page d'accueil ?
La page d'accueil affiche une sélection limitée et fixe des derniers articles publiés (un aperçu/vitrine) sans nécessiter de pagination ni de liens multi-pages. La méthode `take(3)->get()` exécute directement une requête SQL `LIMIT 3` légère sans générer d'objet paginateur ni de requêtes de comptage total supplémentaires.
