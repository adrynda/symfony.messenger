build-dev:
	composer install
	composer dump-env dev
	php bin/console doctrine:database:drop --force --if-exists
	php bin/console doctrine:database:create
	php bin/console doctrine:migrations:migrate --no-interaction
	php bin/console doctrine:fixtures:load --no-interaction

build-front:
	php bin/console cache:clear
	php bin/console tailwind:build
	php bin/console asset-map compile
