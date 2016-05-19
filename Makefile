COMPONENT := layerphpsdk
CONTAINER := phpfarm
IMAGES ?= false
APP_ROOT := /app/layer-php-sdk

all: dev deps logs

dev:
	@docker-compose -p ${COMPONENT} -f ops/docker/docker-compose.yml up -d

enter:
	@docker exec -ti ${COMPONENT}_${CONTAINER}_1 /bin/bash

kill:
	@docker-compose -p ${COMPONENT} -f ops/docker/docker-compose.yml kill

nodev:
	@docker-compose -p ${COMPONENT} -f ops/docker/docker-compose.yml kill
	@docker-compose -p ${COMPONENT} -f ops/docker/docker-compose.yml rm -f
ifeq ($(IMAGES),true)
	@docker rmi ${COMPONENT}_${CONTAINER}
endif

deps: dependencies
dependencies:
	@docker exec -t $(shell docker-compose -p ${COMPONENT} -f ops/docker/docker-compose.yml ps -q ${CONTAINER}) \
	 ${APP_ROOT}/ops/scripts/dependencies.sh

test: unit
unit:
	make dev
	make deps
	@docker exec -t $(shell docker-compose -p ${COMPONENT} -f ops/docker/docker-compose.yml ps -q ${CONTAINER}) \
	 ${APP_ROOT}/ops/scripts/unit.sh

integration:
	make dev
	make deps
	cp composer.json composer.json.back
	cp composer.lock composer.lock.back
	@docker exec -t $(shell docker-compose -p ${COMPONENT} -f ops/docker/docker-compose.yml ps -q ${CONTAINER}) \
	 ${APP_ROOT}/ops/scripts/integration.sh
	mv composer.lock.back composer.lock
	mv composer.json.back composer.json

test-all: unit integration

ps: status
status:
	@docker-compose -p ${COMPONENT} -f ops/docker/docker-compose.yml ps

logs:
	@docker-compose -p ${COMPONENT} -f ops/docker/docker-compose.yml logs

tag: # List last tag for this repo
	@git tag -l | sort -r |head -1

restart: nodev dev logs
