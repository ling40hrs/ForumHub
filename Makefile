.PHONY: setup dev-back dev-front lint check seed

setup:
	@bash bin/setup

dev-back:
	@cd api && php -S localhost:8000 -t public

dev-front:
	@cd frontend && npm run dev

lint:
	@cd api && ./vendor/bin/phpcs --standard=phpcs.xml controllers models config helpers 2>/dev/null || true
	@cd frontend && npm run lint

build:
	@cd frontend && npm run build

check: lint build

seed:
	@bash bin/seed
