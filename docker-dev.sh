#!/bin/bash

# Script para desenvolvimento com Docker
# Uso: ./docker-dev.sh [comando]

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

# Função para construir e iniciar os containers
start() {
    print_message "Iniciando containers..."

    # Copiar arquivo de ambiente se não existir
    if [ ! -f .env.docker ]; then
        cp env.docker.example .env.docker
        print_message "Arquivo .env.docker criado a partir do exemplo."
    fi

    # Construir e iniciar containers
    docker compose up -d --build

    print_message "Aguardando containers ficarem prontos..."
    sleep 10

    # Executar migrações
    docker compose exec app php artisan migrate --force

    # Gerar chave da aplicação
    docker compose exec app php artisan key:generate

    print_message "Aplicação disponível em: http://localhost:8000"
    print_message "PHPMyAdmin disponível em: http://localhost:8080"
    print_message "Frontend (Vite) disponível em: http://localhost:5173"
}

# Função para parar containers
stop() {
    print_message "Parando containers..."
    docker compose down
}

# Função para reiniciar containers
restart() {
    print_message "Reiniciando containers..."
    docker compose down
    docker compose up -d
}

# Função para logs
logs() {
    docker compose logs -f
}

# Função para acessar o container da aplicação
shell() {
    docker compose exec app bash
}

# Função para executar comandos artisan
artisan() {
    if [ -z "$1" ]; then
        print_error "Comando artisan não especificado."
        echo "Uso: $0 artisan [comando]"
        exit 1
    fi

    docker compose exec app php artisan "$@"
}

# Função para executar testes
test() {
    print_message "Executando testes..."
    docker compose exec app php artisan test
}

# Função para instalar dependências
install() {
    print_message "Instalando dependências PHP..."
    docker compose exec app composer install

    print_message "Instalando dependências Node.js..."
    docker compose exec frontend npm install
}

# Função para mostrar status
status() {
    print_message "Status dos containers:"
    docker compose ps
}

# Função para mostrar ajuda
help() {
    echo "Uso: $0 [comando]"
    echo ""
    echo "Comandos disponíveis:"
    echo "  start     - Iniciar containers"
    echo "  stop      - Parar containers"
    echo "  restart   - Reiniciar containers"
    echo "  logs      - Mostrar logs dos containers"
    echo "  shell     - Acessar shell do container da aplicação"
    echo "  artisan   - Executar comando artisan (ex: $0 artisan migrate)"
    echo "  test      - Executar testes"
    echo "  install   - Instalar dependências"
    echo "  status    - Mostrar status dos containers"
    echo "  help      - Mostrar esta ajuda"
}

# Verificar Docker
check_docker

# Processar comandos
case "${1:-help}" in
    start)
        start
        ;;
    stop)
        stop
        ;;
    restart)
        restart
        ;;
    logs)
        logs
        ;;
    shell)
        shell
        ;;
    artisan)
        shift
        artisan "$@"
        ;;
    test)
        test
        ;;
    install)
        install
        ;;
    status)
        status
        ;;
    help|--help|-h)
        help
        ;;
    test-db)
        echo "🛠️  Garantindo que o banco de teste existe..."
        docker compose exec db mysql -u root -padvbox -e "CREATE DATABASE IF NOT EXISTS todo_list_test;"
        echo "✅ Banco de teste criado!"
        echo "🔐 Configurando permissões para o usuário de teste..."
        docker compose exec db mysql -u root -padvbox -e "GRANT ALL PRIVILEGES ON todo_list_test.* TO 'todo_user'@'%';"
        docker compose exec db mysql -u root -padvbox -e "FLUSH PRIVILEGES;"
        echo "✅ Permissões configuradas!"
        echo "🔄 Executando migrations no banco de teste..."
        docker compose exec app bash -c "DB_DATABASE=todo_list_test php artisan migrate:fresh"
        echo "🌱 Populando banco de teste com seeders..."
        docker compose exec app bash -c "DB_DATABASE=todo_list_test php artisan db:seed"
        echo "✅ Banco de teste pronto para os testes!"
        ;;
    up)
        echo "🚀 Iniciando containers de desenvolvimento..."
        docker compose up -d
        echo "✅ Containers iniciados!"
        echo "📱 Aplicação: http://localhost:8000"
        echo "🎨 Frontend (Vite): http://localhost:5173"
        echo "🗄️  PHPMyAdmin: http://localhost:8080"
        echo "🔧 Preparando banco de testes..."
        "$0" test-db
        ;;
    install)
        echo "📦 Instalando dependências do backend (composer)..."
        docker compose exec app composer install
        echo "📦 Instalando dependências do frontend (npm)..."
        docker compose exec frontend npm install
        echo "🔧 Preparando banco de testes..."
        "$0" test-db
        ;;
    *)
        print_error "Comando desconhecido: $1"
        help
        exit 1
        ;;
esac
