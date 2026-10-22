# Cycle de vie d'une requête Laravel

Pour une requête GET /heure, le navigateur envoie la requête vers
public/index.php.

public/index.php charge ensuite vendor/autoload.php pour charger les
classes Composer, puis bootstrap/app.php pour créer et configurer
l'application.

L'application charge notamment routes/web.php. Le routeur trouve ensuite
la route /heure et exécute sa closure.

La closure retourne la vue resources/views/heure.blade.php.
Blade compile la vue en HTML, puis Laravel renvoie la réponse au navigateur.