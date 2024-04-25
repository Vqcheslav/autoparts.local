arg=$(filter-out $@,$(MAKECMDGOALS))
start:
	sudo service nginx start
	sudo service docker start
	sudo service php8.2-fpm start
	docker compose up -d
	yarn watch
stop:
	docker compose stop
	sudo service php8.2-fpm stop
	sudo service docker stop
	sudo service nginx stop
restart:
	make stop
	make start
dc-start:
	docker compose up $(arg)
dc-stop:
	docker compose stop
migrate:
	php8.2 bin/console doctrine:migrations:migrate $(arg)
update:
	php8.2 /usr/local/bin/composer update
dump-autoload:
	php8.2 /usr/local/bin/composer dump-autoload
clear-metadata:
	php8.2 bin/console doctrine:cache:clear-metadata
	php8.2 bin/console doctrine:migrations:sync-metadata-storage