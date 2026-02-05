# ANALYSE DE L'EXISTANT ET PREVISION DE COMMENT AMELIORER LES PERFORMANCES

Le temps d'éxecution total est de 2700 ms. :0

### Requêtes SQL et BDD
Dans le controller Profiler Symfony, on peut voir dans la partie "Doctrine" de la requête GET de la page /carousel qu'un nombre énorme de requêtes sont effectués, il y en a 164!! 
Dans les entités Galaxy.php et ModelesFiles.php, il y a un champ $modele et un champ $modeles_id qui ne font pas référence à l'entité Modeles.php via une clé étrangère ce qui fait que toute la table est traversé pour trouver les données.

### Images
Au niveau des images, on charge les image originale "file.filename_disk" avant de les mettre en miniature ce qui veut dire que le site charge les images avec leur taille de base plus lourdes qu'elles le devraient. Ces images sont TOUTES chargées au démarrage de la page ce qui entraine la page à prender du temps à démarrer.
Il y aussi des images avec un format trop lourd.

### Cache
Il n'y a pas de cache de la page carousel qui est effectué alors que cette page n'est pas modifié ou que peu souvent. Ce qui entraine le calcul de la page à chaque actualisation de celle-ci.

### Autre
Dans le symfony profiler, il est dit d'installer intl pour améliorer les performances.

## CORRECTIFS
Pour palier aux problèmes cités précédemment on devra:
- Remplacer les champs modeles pour créer de vrais relations d'entités Symfony.
- Récupérer toutes les informations voulus en une fois au lieu de faire je ne sais combien de requêtes.
- Redimensionner les miniatures côté serveur pour ne fournir que les images à la bonne taille au navigateur. Pour cela on peut utiliser le bundle "LiipImagineBundle" qui devrait permettre de gérer cela.
- Modifier le format des images pour qu'elles soient en webp
- Créer du cache pour ne pas recalculer la page à chaque fois
- Installer intl dans le Dockerfile