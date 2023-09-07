
build:
	DOCKER_BUILDKIT=1 docker compose build --no-cache

start:
	docker compose up

stop:
	docker compose down

ssh:
	docker compose run -it cli sh

composer-ssh:
	docker compose run -it composer sh
