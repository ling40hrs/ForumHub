.PHONY: setup dev dev-back

setup:
	@bash bin/setup

dev:
	@php -S localhost:8000 -t public router.php

dev-back:
	@cd backend && php -S localhost:8000 -t public
