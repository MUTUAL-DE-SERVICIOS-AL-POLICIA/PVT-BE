# Atajos para operar los entornos. Cada target toma sus variables de
# env/<entorno>.compose.env, que ademas define COMPOSE_FILE (base, o base + dev).

ENVS := prod test dev dev-local

help:
	@echo "Entornos: $(ENVS)"
	@echo "  make up-<env>       levanta y reconstruye la imagen"
	@echo "  make build-<env>    solo construye"
	@echo "  make down-<env>     apaga (conserva los volumenes)"
	@echo "  make logs-<env>     sigue los logs"
	@echo "  make config-<env>   muestra la configuracion resuelta"
	@echo "  make ps-<env>       estado de los contenedores"

up-%:
	docker compose --env-file env/$*.compose.env up -d --build

build-%:
	docker compose --env-file env/$*.compose.env build

down-%:
	docker compose --env-file env/$*.compose.env down

logs-%:
	docker compose --env-file env/$*.compose.env logs -f

config-%:
	docker compose --env-file env/$*.compose.env config

ps-%:
	docker compose --env-file env/$*.compose.env ps
