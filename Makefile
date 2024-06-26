# Makefile variables
CONTAINER_PHP = app
CONTAINER_DB = db
CONTAINER_DB_TEST = db_test

.PHONY: up down restart logs shell db-shell db-test-shell migrate test

up:
	@docker-compose up -d

down:
	@docker-compose down

restart: down up

logs:
	@docker-compose logs -f

shell:
	@docker-compose exec $(CONTAINER_PHP) /bin/bash

db-shell:
	@docker-compose exec $(CONTAINER_DB) mysql -u root -p

db-test-shell:
	@docker-compose exec $(CONTAINER_DB_TEST) mysql -u root -p

migrate-create:
	@docker-compose exec $(CONTAINER_PHP) php artisan make:migration

migrate:
	@docker-compose exec $(CONTAINER_PHP) php artisan migrate

migrate-test:
	@echo "Running test migrations..."
	@docker-compose exec $(CONTAINER_PHP) php artisan --env=testing migrate

migrate-rollback:
	@docker-compose exec $(CONTAINER_PHP) php artisan migrate:rollback

migrate-test-rollback:
	@docker-compose exec $(CONTAINER_PHP) php artisan --env=testing migrate:rollback

test:
	@docker-compose exec $(CONTAINER_PHP) php artisan test
