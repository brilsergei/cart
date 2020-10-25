In order to launch the app:

1. Run next commands from the project root directory
  'cp docker-compose.example.yml docker-compose.yml'
  'cp .env.example .env'
  'docker-compose up -d'
  'docker-compose exec php composer install'
  'cp symfony-app/.env symfony-app/.env.local'
2. replace database credentials in file symfony-app/.env.local with below
  DATABASE_URL=mysql://root:password@mariadb:3306/cart?serverVersion=mariadb-10.5.5
3. Install database schema
  'docker-compose exec php bin/console doctrine:migrations:migrate'


A couple of notes for reviewer
1. There are a couple of places with code duplications.
2. There is an ability to add a few line items referencing same cart and product
3. All endpoints has to be covered with functional tests. I would use liip/functional-test-bundle and
  liip/test-fixtures-bundle (and even used it for an app based on symfony to test API endpoints). This libraries
  provides useful wrapper for symfony's functional tests base and ability to use real database connection and fixtures
  in tests.
4. All 3 above issues wasn't fixed due to limited time I could dedicate for the task (about 15 hours in total)
