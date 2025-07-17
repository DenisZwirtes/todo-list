#!/bin/bash

# Aguarda o banco de dados estar pronto
echo "Aguardando banco de dados..."
while ! php -r "
    \$dsn = 'mysql:host=db;port=3306';
    try {
        \$pdo = new PDO(\$dsn, '${DB_USERNAME}', '${DB_PASSWORD}');
        echo 'Banco de dados pronto!' . PHP_EOL;
        exit(0);
    } catch (PDOException \$e) {
        exit(1);
    }
" 2>/dev/null; do
    sleep 2
done

# Instala dependências se necessário
if [ ! -f "vendor/autoload.php" ]; then
    echo "Instalando dependências do Composer..."
    composer install --no-interaction --optimize-autoloader
fi

# Gera chave da aplicação se não existir
if [ ! -f ".env" ]; then
    echo "Copiando arquivo .env..."
    cp .env.docker .env
fi

# Gera chave da aplicação se não existir
if ! grep -q "APP_KEY=base64:" .env; then
    echo "Gerando chave da aplicação..."
    php artisan key:generate
fi

# Executa migrations
echo "Executando migrations..."
php artisan migrate --force

# Executa migrations no banco de teste
if [ -f ".env.testing" ]; then
    echo "Executando migrations no banco de teste..."

    # Limpa cache e configuração
    php artisan config:clear --env=testing
    php artisan cache:clear --env=testing

    # Executa migrations no banco de teste usando variáveis de ambiente
    DB_DATABASE=todo_list_test DB_USERNAME=todo_user DB_PASSWORD=todo_password php artisan migrate --force
else
    echo "Arquivo .env.testing não encontrado, pulando migrations do banco de teste"
fi

# Executa seeders se necessário (opcional)
if [ "$RUN_SEEDERS" = "true" ]; then
    echo "Executando seeders..."
    php artisan db:seed --force
fi

echo "Aplicação inicializada com sucesso!"

# Inicia o FrankenPHP
exec frankenphp run --config /etc/caddy/Caddyfile
