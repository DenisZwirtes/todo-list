# Arquitetura do Projeto

## Visão Geral

Este projeto implementa uma arquitetura moderna de aplicação web usando as tecnologias mais recentes e melhores práticas de desenvolvimento.

## Stack Tecnológica

### Backend
- **Laravel 12**: Framework PHP moderno com as melhores práticas
- **PHP 8.4**: Versão mais recente do PHP com melhor performance
- **FrankenPHP**: Servidor web moderno que combina PHP e Go
- **MySQL 8.0**: Banco de dados relacional robusto

### Frontend
- **Vue 3**: Framework JavaScript progressivo
- **Inertia.js**: Biblioteca para SPA sem complexidade
- **Tailwind CSS**: Framework CSS utility-first
- **Vite**: Build tool moderno e rápido

### Containerização
- **Docker**: Containerização da aplicação
- **Docker Compose**: Orquestração de containers
- **FrankenPHP**: Servidor web otimizado

### Testes
- **Pest PHP**: Framework de testes moderno
- **PHPUnit**: Framework de testes unitários
- **Vitest**: Framework de testes para JavaScript

## Estrutura de Containers

### Desenvolvimento
```
┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐
│   Frontend      │    │   Aplicação     │    │   Banco de      │
│   (Node.js)     │    │   (FrankenPHP)  │    │   Dados         │
│   Porta: 5173   │    │   Porta: 8000   │    │   (MySQL)       │
└─────────────────┘    └─────────────────┘    └─────────────────┘
         │                       │                       │
         └───────────────────────┼───────────────────────┘
                                 │
                    ┌─────────────────┐
                    │   PHPMyAdmin    │
                    │   Porta: 8080   │
                    └─────────────────┘
```

### Produção
```
┌─────────────────┐    ┌─────────────────┐
│   Aplicação     │    │   Banco de      │
│   (FrankenPHP)  │    │   Dados         │
│   Porta: 80     │    │   (MySQL)       │
└─────────────────┘    └─────────────────┘
```

## Fluxo de Dados

### Requisição HTTP
1. **Cliente** → **FrankenPHP** (Porta 80/8000)
2. **FrankenPHP** → **Laravel Router**
3. **Laravel Router** → **Controller**
4. **Controller** → **Model** → **Database**
5. **Response** → **Inertia.js** → **Vue Component**

### Desenvolvimento Frontend
1. **Vite Dev Server** (Porta 5173)
2. **Hot Module Replacement** para Vue components
3. **Tailwind CSS** com JIT compilation

## Padrões Arquiteturais

### Backend (Laravel)
- **MVC**: Model-View-Controller
- **Repository Pattern**: Para acesso a dados
- **Service Layer**: Para lógica de negócio
- **Policy Pattern**: Para autorização
- **Resource Classes**: Para API responses

### Frontend (Vue + Inertia)
- **Component-Based Architecture**: Componentes reutilizáveis
- **Composition API**: API moderna do Vue 3
- **Inertia.js**: SPA sem complexidade de API
- **Tailwind CSS**: Utility-first CSS

### Testes
- **TDD**: Test-Driven Development
- **BDD**: Behavior-Driven Development
- **Feature Tests**: Testes de funcionalidade
- **Unit Tests**: Testes unitários
- **Browser Tests**: Testes de interface

## Configurações de Ambiente

### Desenvolvimento
- **APP_ENV**: local
- **APP_DEBUG**: true
- **DB_HOST**: db (container)
- **CACHE_DRIVER**: file
- **SESSION_DRIVER**: file

### Produção
- **APP_ENV**: production
- **APP_DEBUG**: false
- **DB_HOST**: db (container)
- **CACHE_DRIVER**: redis (recomendado)
- **SESSION_DRIVER**: redis (recomendado)

## Segurança

### Backend
- **CSRF Protection**: Proteção contra CSRF
- **XSS Protection**: Headers de segurança
- **SQL Injection**: Eloquent ORM
- **Authentication**: Laravel Sanctum
- **Authorization**: Laravel Policies

### Frontend
- **CSP**: Content Security Policy
- **HTTPS**: Forçado em produção
- **Input Validation**: Validação client-side
- **XSS Prevention**: Vue.js sanitization

## Performance

### Backend
- **FrankenPHP**: Servidor web otimizado
- **OPcache**: Cache de bytecode PHP
- **Database Indexing**: Índices otimizados
- **Query Optimization**: Eloquent eager loading
- **Caching**: Redis/Memcached

### Frontend
- **Vite**: Build tool rápido
- **Code Splitting**: Lazy loading
- **Tree Shaking**: Eliminação de código não usado
- **Image Optimization**: Otimização de imagens
- **CDN**: Content Delivery Network

## Monitoramento

### Logs
- **Laravel Logs**: storage/logs/laravel.log
- **Docker Logs**: docker-compose logs
- **Nginx/Apache Logs**: Logs do servidor web

### Métricas
- **Application Metrics**: Laravel Telescope
- **Database Metrics**: MySQL slow query log
- **Performance Metrics**: New Relic/DataDog

## Deploy

### Desenvolvimento
```bash
./docker-dev.sh start
```

### Staging
```bash
./deploy.sh staging
```

### Produção
```bash
./deploy.sh production
```

## Backup e Recuperação

### Banco de Dados
```bash
# Backup
docker-compose exec db mysqldump -u root -p todo_list > backup.sql

# Restore
docker-compose exec -T db mysql -u root -p todo_list < backup.sql
```

### Volumes
```bash
# Backup volumes
docker run --rm -v todo-list-db-data:/data -v $(pwd):/backup alpine tar czf /backup/db-backup.tar.gz -C /data .

# Restore volumes
docker run --rm -v todo-list-db-data:/data -v $(pwd):/backup alpine tar xzf /backup/db-backup.tar.gz -C /data
```

## Escalabilidade

### Horizontal Scaling
- **Load Balancer**: Nginx/Traefik
- **Multiple App Instances**: Docker Swarm/Kubernetes
- **Database Replication**: MySQL Master-Slave
- **Cache Clustering**: Redis Cluster

### Vertical Scaling
- **Resource Limits**: Docker resource limits
- **PHP-FPM Tuning**: Process management
- **MySQL Tuning**: Query optimization
- **Memory Optimization**: OPcache settings

## Manutenção

### Atualizações
- **Composer**: Atualizar dependências PHP
- **NPM**: Atualizar dependências Node.js
- **Docker Images**: Atualizar imagens base
- **Security Patches**: Atualizações de segurança

### Backup Strategy
- **Daily Backups**: Backup automático diário
- **Weekly Backups**: Backup semanal completo
- **Monthly Backups**: Backup mensal de longo prazo
- **Point-in-time Recovery**: Recuperação pontual 
