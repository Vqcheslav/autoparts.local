arg=$(filter-out $@,$(MAKECMDGOALS))
start:
	docker compose up -d
	yarn watch
stop:
	docker compose stop
restart:
	make stop
	make start
migrate:
	php8.3 bin/console doctrine:migrations:migrate $(arg)
install:
	php8.3 /usr/local/bin/composer install
update:
	php8.3 /usr/local/bin/composer update
dump-autoload:
	php8.3 /usr/local/bin/composer dump-autoload
clear-metadata:
	php8.3 bin/console doctrine:cache:clear-metadata
	php8.3 bin/console doctrine:migrations:sync-metadata-storage