up:
	@docker compose up -d

up-build:
	@docker compose up -d --build

down:
	@docker compose down

php:
	@docker compose exec php bash

node:
	@docker compose exec node bash
