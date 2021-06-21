.PHONY: setup-docker inside-php clean-docker inside-mysql docker-start docker-stop install

setup-docker:
	cd app && cp .env.example .env
	docker-compose up --build -d
	@echo ""
	@printf " \033[33;5;7mYOU CAN RUN make install\033[0m "
	@echo ""

install:
	docker exec -it --user www-data interview_php bash -c 'composer install'
	docker exec -it --user www-data interview_php bash -c 'php artisan key:generate'
	docker exec -it --user www-data interview_php bash -c 'php artisan jwt:secret'
	docker exec -it --user www-data interview_php bash -c 'php artisan migrate'
	docker exec -it --user www-data interview_php bash -c 'php artisan db:seed'		

docker-start:
	docker-compose up --build

docker-stop:
	docker-compose stop

clean-docker:
	docker-compose down --rmi all

inside-php:
	docker-compose exec --user=www-data php bash

inside-mysql:
	docker-compose exec db bash
