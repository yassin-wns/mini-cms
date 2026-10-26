# Session 06 - Défi : publier et dépublier

La route posts.publication apparaît dans route:list avec le verbe PATCH : oui
Le bouton affiche « Publier » sur un brouillon et « Dépublier » sur un article publié : oui
published_at est rempli à la publication et vidé à la dépublication : oui
Le nouveau test passe et php artisan test est vert : oui

Pourquoi PATCH et pas PUT ni GET pour ce bouton ?
- GET ne doit jamais altérer l'état des données sur le serveur (principe d'idempotence et de sécurité vis-à-vis des robots ou préchargements).
- PUT remplace généralement l'intégralité de la ressource avec tous ses champs.
- PATCH est le verbe HTTP dédié à la modification partielle d'une ressource (ici, seulement l'état de publication `is_published` et son horodatage `published_at`).
