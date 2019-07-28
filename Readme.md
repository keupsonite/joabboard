# Readme
- Copier et modifier le fichier .env en .env.local avec vos informations de connexion
- Copier et modifier le fichier .env.test en .env.test.local avec vos informations de connexion
- Créer la BDD $ php bin/console doctrine:database:create 
- Migrer $ php bin/console doctrine:migrations:migrate
- Fixtures $ php bin/console doctrine:fixtures:load
- Tests $ ./bin/phpunit