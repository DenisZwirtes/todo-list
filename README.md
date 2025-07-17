# Todo List - Laravel 12 + Vue 3 + Tailwind CSS

Uma aplicação moderna de lista de tarefas construída com Laravel 12, Vue 3, Tailwind CSS e FrankenPHP, totalmente containerizada com Docker.

## 🚀 Tecnologias

- **Backend**: Laravel 12 + PHP 8.4
- **Frontend**: Vue 3 + Inertia.js
- **CSS**: Tailwind CSS
- **Servidor**: FrankenPHP
- **Banco de Dados**: MySQL 8.0
- **Containerização**: Docker + Docker Compose
- **Testes**: Pest PHP
- **Ferramentas**: PHPMyAdmin, Node.js

## 📋 Pré-requisitos

- Docker
- Docker Compose
- Git

## 🛠️ Instalação e Configuração

### 1. Clone o repositório
```bash
git clone <url-do-repositorio>
cd todo-list
```

### 2. Configure o ambiente
```bash
# Copie o arquivo de ambiente
cp env.docker.example .env.docker

# Edite as configurações se necessário
nano .env.docker
```

### 3. Inicie os containers
```bash
# Use o script de desenvolvimento
./docker-dev.sh start

# Ou use docker-compose diretamente
docker-compose up -d --build
```

### 4. Acesse a aplicação
- **Aplicação**: http://localhost:8000
- **PHPMyAdmin**: http://localhost:8080
- **Frontend (Vite)**: http://localhost:5173

## 🐳 Comandos Docker

O projeto inclui um script de desenvolvimento que facilita o uso dos containers:

```bash
# Iniciar containers
./docker-dev.sh start

# Parar containers
./docker-dev.sh stop

# Reiniciar containers
./docker-dev.sh restart

# Ver logs
./docker-dev.sh logs

# Acessar shell do container da aplicação
./docker-dev.sh shell

# Executar comandos artisan
./docker-dev.sh artisan migrate
./docker-dev.sh artisan make:controller TaskController

# Executar testes
./docker-dev.sh test

# Instalar dependências
./docker-dev.sh install

# Ver status dos containers
./docker-dev.sh status

# Ver ajuda
./docker-dev.sh help
```

## 🧪 Testes

O projeto usa Pest PHP para testes:

```bash
# Executar todos os testes
./docker-dev.sh test

# Executar testes específicos
docker-compose exec app php artisan test --filter=TaskTest

# Executar testes com coverage
docker-compose exec app php artisan test --coverage
```

## 📁 Estrutura do Projeto

```
todo-list/
├── app/                    # Código da aplicação Laravel
├── docker/                 # Configurações Docker
│   ├── caddy/             # Configuração Caddy (FrankenPHP)
│   ├── php/               # Configuração PHP
│   └── mysql/             # Scripts MySQL
├── resources/
│   ├── js/                # Código JavaScript/Vue
│   ├── css/               # Estilos CSS
│   └── views/             # Views Blade
├── tests/                 # Testes Pest
├── docker-compose.yml     # Configuração Docker Compose
├── Dockerfile             # Dockerfile da aplicação
├── docker-dev.sh          # Script de desenvolvimento
└── README.md              # Este arquivo
```

## 🔧 Desenvolvimento

### Adicionando novas dependências PHP
```bash
docker-compose exec app composer require nome-do-pacote
```

### Adicionando novas dependências Node.js
```bash
docker-compose exec frontend npm install nome-do-pacote
```

### Executando migrações
```bash
./docker-dev.sh artisan migrate
```

### Criando seeders
```bash
./docker-dev.sh artisan make:seeder NomeSeeder
./docker-dev.sh artisan db:seed
```

### Compilando assets
```bash
docker-compose exec frontend npm run build
```

## 🐛 Troubleshooting

### Container não inicia
```bash
# Verificar logs
./docker-dev.sh logs

# Reconstruir containers
docker-compose down
docker-compose up -d --build
```

### Problemas de permissão
```bash
# Corrigir permissões
docker-compose exec app chown -R www-data:www-data storage bootstrap/cache
```

### Banco de dados não conecta
```bash
# Verificar se o MySQL está rodando
./docker-dev.sh status

# Reiniciar apenas o banco
docker-compose restart db
```

## 📦 Deploy

Para produção, use o Dockerfile.prod:

```bash
# Construir imagem de produção
docker build -f Dockerfile.prod -t todo-list:prod .

# Executar container de produção
docker run -d -p 80:80 todo-list:prod
```

## 🤝 Contribuindo

1. Fork o projeto
2. Crie uma branch para sua feature (`git checkout -b feature/AmazingFeature`)
3. Commit suas mudanças (`git commit -m 'Add some AmazingFeature'`)
4. Push para a branch (`git push origin feature/AmazingFeature`)
5. Abra um Pull Request

## 📄 Licença

Este projeto está sob a licença MIT. Veja o arquivo `LICENSE` para mais detalhes.

## 🙏 Agradecimentos

- Laravel Team
- Vue.js Team
- Tailwind CSS Team
- FrankenPHP Team
