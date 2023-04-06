arg=$(filter-out $@,$(MAKECMDGOALS))
start:
	sudo service php8.2-fpm start
	docker compose up $(arg)
stop:
	docker compose stop
	sudo service php8.2-fpm stop
migration:
	php8.2 bin/console make:migration
migrate:
	php8.2 bin/console doctrine:migrations:migrate $(arg)
clear-metadata:
	php8.2 bin/console doctrine:cache:clear-metadata
	php8.2 bin/console doctrine:migrations:sync-metadata-storage