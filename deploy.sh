#!/bin/bash

# Script de deploy para produção
# Uso: ./deploy.sh [ambiente]

set -e

# Cores para output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Função para imprimir mensagens coloridas
print_message() {
    echo -e "${GREEN}[INFO]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# Verificar se o Docker está rodando
check_docker() {
    if ! docker info > /dev/null 2>&1; then
        print_error "Docker não está rodando. Por favor, inicie o Docker e tente novamente."
        exit 1
    fi
}

# Função para deploy de produção
deploy_production() {
    print_message "Iniciando deploy de produção..."

    # Construir imagem de produção
    print_message "Construindo imagem de produção..."
    docker build -f Dockerfile.prod -t todo-list:prod .

    # Parar containers existentes
    print_message "Parando containers existentes..."
    docker-compose -f docker-compose.prod.yml down || true

    # Iniciar containers de produção
    print_message "Iniciando containers de produção..."
    docker-compose -f docker-compose.prod.yml up -d

    # Executar migrações
    print_message "Executando migrações..."
    docker-compose -f docker-compose.prod.yml exec app php artisan migrate --force

    # Limpar cache
    print_message "Limpando cache..."
    docker-compose -f docker-compose.prod.yml exec app php artisan config:clear
    docker-compose -f docker-compose.prod.yml exec app php artisan route:clear
    docker-compose -f docker-compose.prod.yml exec app php artisan view:clear

    print_message "Deploy de produção concluído!"
    print_message "Aplicação disponível em: http://localhost"
}

# Função para deploy de staging
deploy_staging() {
    print_message "Iniciando deploy de staging..."

    # Construir imagem de staging
    print_message "Construindo imagem de staging..."
    docker build -f Dockerfile.prod -t todo-list:staging .

    # Parar containers existentes
    print_message "Parando containers existentes..."
    docker-compose -f docker-compose.staging.yml down || true

    # Iniciar containers de staging
    print_message "Iniciando containers de staging..."
    docker-compose -f docker-compose.staging.yml up -d

    # Executar migrações
    print_message "Executando migrações..."
    docker-compose -f docker-compose.staging.yml exec app php artisan migrate --force

    print_message "Deploy de staging concluído!"
    print_message "Aplicação disponível em: http://localhost:8081"
}

# Função para rollback
rollback() {
    print_message "Iniciando rollback..."

    # Parar containers atuais
    docker-compose -f docker-compose.prod.yml down || true

    # Voltar para versão anterior
    docker tag todo-list:prod-backup todo-list:prod || print_warning "Nenhuma versão de backup encontrada"

    # Reiniciar containers
    docker-compose -f docker-compose.prod.yml up -d

    print_message "Rollback concluído!"
}

# Função para backup
backup() {
    print_message "Criando backup..."

    # Fazer backup da imagem atual
    docker tag todo-list:prod todo-list:prod-backup || print_warning "Nenhuma imagem de produção encontrada"

    # Fazer backup do banco de dados
    docker-compose -f docker-compose.prod.yml exec db mysqldump -u root -p todo_list > backup_$(date +%Y%m%d_%H%M%S).sql

    print_message "Backup concluído!"
}

# Verificar Docker
check_docker

# Processar comandos
case "${1:-help}" in
    production|prod)
        deploy_production
        ;;
    staging|stage)
        deploy_staging
        ;;
    rollback)
        rollback
        ;;
    backup)
        backup
        ;;
    help|--help|-h)
        echo "Uso: $0 [comando]"
        echo ""
        echo "Comandos disponíveis:"
        echo "  production|prod - Deploy de produção"
        echo "  staging|stage   - Deploy de staging"
        echo "  rollback        - Fazer rollback"
        echo "  backup          - Criar backup"
        echo "  help            - Mostrar esta ajuda"
        ;;
    *)
        print_error "Comando desconhecido: $1"
        echo "Use '$0 help' para ver os comandos disponíveis"
        exit 1
        ;;
esac
