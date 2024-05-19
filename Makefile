start:
	@docker compose up -d

deploy: start
	@docker compose exec php-fpm dev_deployment/dev-deploy.sh

console:
	@docker compose exec php-fpm bash

stop:
	@docker compose stop

destroy: stop
	@docker compose down --rmi all
