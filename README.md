# CMI test project

Il s'agit dun projet de création d'un service web de discussion et de commentaires d’articles.

## Installation du projet

1. composer install
2. npm install
3. docker-compose up -d
4. npm run build
5. php bin/console lexik:jwt:generate-keypair
6. php bin/console doctrine:schema:update --force
7. php bin/console --env=test doctrine:database:create
8. php bin/console --env=test doctrine:schema:create
9. php bin/console hautelook:fixtures:load --purge-with-truncate
10. php bin/console --env=test hautelook:fixtures:load --purge-with-truncate

## Les tests

Pour jouer les tests, il faut lancer la commande : php bin/phpunit


