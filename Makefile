.PHONY: help install start stop restart logs shell test build clean

help: ## Mostrar esta ajuda
	@echo "Comandos disponíveis:"
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-20s\033[0m %s\n", $$1, $$2}'

install: ## Instalar dependências
	@echo "Instalando dependências..."
	docker-compose exec app composer install
	docker-compose exec frontend npm install

start: ## Iniciar containers
	@echo "Iniciando containers..."
	./docker-dev.sh start

stop: ## Parar containers
	@echo "Parando containers..."
	./docker-dev.sh stop

restart: ## Reiniciar containers
	@echo "Reiniciando containers..."
	./docker-dev.sh restart

logs: ## Mostrar logs
	@echo "Mostrando logs..."
	./docker-dev.sh logs

shell: ## Acessar shell do container
	@echo "Acessando shell..."
	./docker-dev.sh shell

test: ## Executar testes
	@echo "Executando testes..."
	./docker-dev.sh test

test-coverage: ## Executar testes com coverage
	@echo "Executando testes com coverage..."
	docker-compose exec app php artisan test --coverage

build: ## Compilar assets
	@echo "Compilando assets..."
	docker-compose exec frontend npm run build

dev: ## Iniciar modo desenvolvimento
	@echo "Iniciando modo desenvolvimento..."
	docker-compose exec frontend npm run dev

migrate: ## Executar migrações
	@echo "Executando migrações..."
	./docker-dev.sh artisan migrate

migrate-fresh: ## Executar migrações do zero
	@echo "Executando migrações do zero..."
	./docker-dev.sh artisan migrate:fresh --seed

seed: ## Executar seeders
	@echo "Executando seeders..."
	./docker-dev.sh artisan db:seed

clean: ## Limpar containers e volumes
	@echo "Limpando containers e volumes..."
	docker-compose down -v
	docker system prune -f

lint: ## Executar linting
	@echo "Executando linting..."
	docker-compose exec app vendor/bin/pint
	docker-compose exec frontend npm run lint

format: ## Formatar código
	@echo "Formatando código..."
	docker-compose exec app vendor/bin/pint --fix
	docker-compose exec frontend npm run lint

setup: ## Configurar projeto completo
	@echo "Configurando projeto completo..."
	@make install
	@make start
	@make migrate
	@make seed
	@echo "Projeto configurado com sucesso!"
	@echo "Acesse: http://localhost:8000"
