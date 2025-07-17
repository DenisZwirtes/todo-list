# Segurança - Todo List

Este documento descreve as implementações de segurança implementadas no projeto Todo List, baseadas no projeto de referência sistema-educacional-joineed.

## Proteções Implementadas

### 1. Headers de Segurança (SecurityHeaders)

**Arquivo:** `app/Http/Middleware/SecurityHeaders.php`

**Proteções:**
- **XSS Protection:** Previne ataques Cross-Site Scripting
- **Clickjacking Protection:** Previne ataques de clickjacking com `X-Frame-Options: DENY`
- **HTTPS Enforcement:** Força HTTPS com HSTS
- **Content Security Policy (CSP):** Política de segurança de conteúdo
- **MIME Type Sniffing:** Previne sniffing de tipo MIME
- **Referrer Policy:** Controla informações de referência
- **Permissions Policy:** Restringe permissões de recursos
- **Cross-Origin Policies:** Políticas de origem cruzada

### 2. Sanitização de Entrada (SanitizeInput)

**Arquivo:** `app/Http/Middleware/SanitizeInput.php`

**Proteções:**
- **XSS Prevention:** Remove scripts maliciosos
- **HTML Sanitization:** Permite apenas tags HTML seguras
- **URL Validation:** Valida URLs em atributos href
- **Character Encoding:** Converte caracteres especiais
- **Input Normalization:** Normaliza espaços em branco

**Tags HTML Permitidas:**
```php
['p', 'br', 'strong', 'em', 'ul', 'ol', 'li', 'a',
 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'blockquote', 'code']
```

### 3. Rate Limiting (AuthRateLimiter)

**Arquivo:** `app/Http/Middleware/AuthRateLimiter.php`

**Proteções contra DDoS:**
- **Login:** 5 tentativas por 30 minutos
- **Register:** 3 tentativas por 60 minutos
- **Password Reset:** 3 tentativas por 60 minutos
- **Email Verification:** 3 tentativas por 60 minutos

**Configuração:** `config/rate_limiting.php`

### 4. Rate Limiter Service

**Arquivo:** `app/Services/RateLimiterService.php`
**Interface:** `app/Contracts/Services/RateLimiterServiceInterface.php`

**Funcionalidades:**
- Verificação de rate limiting
- Contagem de tentativas
- Formatação de mensagens de erro
- Respostas personalizadas para API e web

### 5. Log de Erros de Segurança (LogErrors)

**Arquivo:** `app/Http/Middleware/LogErrors.php`

**Logs:**
- Acessos negados (403)
- Rate limit excedido (429)
- Erros do servidor (500+)
- Informações de requisição com erro

**Dados Logados:**
- IP do usuário
- User Agent
- Método HTTP
- URL completa
- Status code
- ID do usuário (se autenticado)
- Timestamp

### 6. Prevenção de Comandos em Produção

**Arquivo:** `app/Http/Middleware/PreventCommandsInProduction.php`

**Comandos Bloqueados:**
- `php artisan migrate:fresh`
- `php artisan migrate:reset`
- `php artisan db:seed`
- `php artisan cache:clear`
- `php artisan config:clear`
- `php artisan route:clear`
- `php artisan view:clear`
- `php artisan optimize:clear`

### 7. Verificação de Hosts Confiáveis

**Arquivo:** `app/Http/Middleware/TrustHosts.php`

**Hosts Permitidos:**
- localhost
- 127.0.0.1
- ::1

**Configuração:** Adicione seus domínios de produção no array `$trustedHosts`

## Configuração

### 1. Rate Limiting

Edite `config/rate_limiting.php` para ajustar os limites:

```php
'auth' => [
    'enabled' => true,
    'max_attempts' => 10,
    'decay_minutes' => 5,
],
```

### 2. Hosts Confiáveis

Edite `app/Http/Middleware/TrustHosts.php` para adicionar seus domínios:

```php
protected array $trustedHosts = [
    'localhost',
    '127.0.0.1',
    '::1',
    'seudominio.com',
    'www.seudominio.com',
];
```

### 3. Content Security Policy

Edite `app/Http/Middleware/SecurityHeaders.php` para ajustar a CSP:

```php
$csp = [
    "default-src 'self'",
    "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net",
    "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com",
    // ... outras diretivas
];
```

## Middlewares Registrados

### Middlewares Globais
- `TrustProxies` - Confia em proxies
- `TrustHosts` - Verifica hosts confiáveis
- `HandleCors` - Gerencia CORS
- `PreventRequestsDuringMaintenance` - Bloqueia requisições durante manutenção
- `PreventCommandsInProduction` - Bloqueia comandos perigosos
- `ValidatePostSize` - Valida tamanho de POST
- `TrimStrings` - Remove espaços em branco
- `SanitizeInput` - Sanitiza entrada
- `ConvertEmptyStringsToNull` - Converte strings vazias
- `SecurityHeaders` - Adiciona headers de segurança
- `LogErrors` - Log de erros de segurança

### Middlewares Web
- `EncryptCookies` - Criptografa cookies
- `AddQueuedCookiesToResponse` - Adiciona cookies enfileirados
- `StartSession` - Inicia sessão
- `ShareErrorsFromSession` - Compartilha erros da sessão
- `VerifyCsrfToken` - Verifica token CSRF
- `AuthRateLimiter` - Rate limiting para autenticação
- `SubstituteBindings` - Substitui bindings

## Benefícios

### 1. Proteção contra Ataques
- **XSS:** Headers de segurança + sanitização
- **CSRF:** Tokens CSRF do Laravel
- **DDoS:** Rate limiting
- **Clickjacking:** Headers X-Frame-Options
- **SQL Injection:** Eloquent ORM + sanitização
- **Command Injection:** Bloqueio de comandos perigosos

### 2. Monitoramento
- Logs detalhados de eventos de segurança
- Rastreamento de tentativas de acesso
- Monitoramento de erros do servidor

### 3. Configurabilidade
- Rate limiting configurável
- Hosts confiáveis personalizáveis
- CSP ajustável
- Logs customizáveis

## Próximos Passos

1. **Configurar domínios de produção** no `TrustHosts`
2. **Ajustar CSP** para suas necessidades específicas
3. **Configurar logs de segurança** em produção
4. **Implementar monitoramento** de eventos de segurança
5. **Configurar alertas** para tentativas de ataque
6. **Implementar autenticação de dois fatores** (2FA)
7. **Configurar backup de segurança** dos logs

## Testes de Segurança

Execute os seguintes testes para verificar as proteções:

```bash
# Teste de rate limiting
curl -X POST http://localhost/login -d "email=test@example.com&password=wrong"

# Teste de XSS
curl -X POST http://localhost/tasks -d "title=<script>alert('xss')</script>"

# Teste de CSRF
curl -X POST http://localhost/tasks -H "Content-Type: application/json"

# Teste de host não autorizado
curl -H "Host: evil.com" http://localhost
```

## Referências

- [OWASP Top 10](https://owasp.org/www-project-top-ten/)
- [Laravel Security](https://laravel.com/docs/security)
- [Content Security Policy](https://developer.mozilla.org/en-US/docs/Web/HTTP/CSP)
- [HTTP Security Headers](https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers#security) 
