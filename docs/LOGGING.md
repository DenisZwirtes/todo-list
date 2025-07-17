# Sistema de Logs - Todo List

Este documento descreve o sistema de logs implementado no projeto Todo List, baseado no projeto de referência sistema-educacional-joineed.

## Estrutura do Sistema

### 1. Tabela de Logs

**Migration:** `database/migrations/2025_07_17_014115_create_logs_table.php`

**Campos:**
- `id` - ID único do log
- `level` - Nível do log (error, warning, info, debug)
- `message` - Mensagem principal do log
- `error_message` - Mensagem de erro específica (opcional)
- `context` - Dados adicionais em JSON (opcional)
- `file` - Arquivo onde ocorreu o erro (opcional)
- `line` - Linha onde ocorreu o erro (opcional)
- `ip_address` - IP do usuário
- `user_agent` - User agent do navegador
- `user_id` - ID do usuário (opcional)
- `created_at` - Data/hora de criação
- `updated_at` - Data/hora de atualização

### 2. Modelo Log

**Arquivo:** `app/Models/Log.php`

**Funcionalidades:**
- Relacionamento com usuário
- Cast automático de context para array
- Fillable fields definidos

### 3. LogService

**Interface:** `app/Contracts/Services/LogServiceInterface.php`
**Implementação:** `app/Services/LogService.php`

**Métodos:**
- `error(string $message, array $context = [], ?\Throwable $exception = null)`
- `warning(string $message, array $context = [])`
- `info(string $message, array $context = [])`
- `debug(string $message, array $context = [])`

## Como Usar

### 1. Injeção de Dependência

```php
use App\Contracts\Services\LogServiceInterface;

class TaskController extends Controller
{
    public function __construct(LogServiceInterface $logService)
    {
        $this->logService = $logService;
    }
}
```

### 2. Logs Simples

```php
// Log informativo
$this->logService->info('Usuário fez login', [
    'user_id' => auth()->id(),
    'ip' => request()->ip()
]);

// Log de aviso
$this->logService->warning('Tentativa de acesso negado', [
    'ip' => request()->ip(),
    'user_agent' => request()->userAgent()
]);
```

### 3. Logs com Exceções

```php
try {
    // Código que pode gerar erro
    $task = $this->taskService->create($taskDTO);
    
    $this->logService->info('Tarefa criada com sucesso', [
        'task_id' => $task->id,
        'user_id' => auth()->id()
    ]);
} catch (\Exception $e) {
    $this->logService->error('Erro ao criar tarefa', [
        'request_data' => $request->all(),
        'user_id' => auth()->id()
    ], $e);
    
    throw $e;
}
```

### 4. Logs de Debug

```php
$this->logService->debug('Dados da requisição', [
    'method' => request()->method(),
    'url' => request()->fullUrl(),
    'params' => request()->all()
]);
```

## Interface Web

### 1. Listagem de Logs

**Rota:** `GET /logs`
**Controller:** `LogController@index`
**View:** `resources/views/logs/index.blade.php`

**Funcionalidades:**
- Listagem paginada de logs
- Filtros por nível, busca e usuário
- Estatísticas de logs
- Exportação para CSV
- Limpeza de logs antigos

### 2. Detalhes do Log

**Rota:** `GET /logs/{log}`
**Controller:** `LogController@show`
**View:** `resources/views/logs/show.blade.php`

**Funcionalidades:**
- Visualização completa do log
- Informações técnicas detalhadas
- Contexto em formato JSON
- Relacionamento com usuário

### 3. Exportação

**Rota:** `GET /logs/export`
**Controller:** `LogController@export`

**Funcionalidades:**
- Exportação para CSV
- Filtros aplicáveis
- Nome do arquivo com timestamp

### 4. Limpeza

**Rota:** `POST /logs/clear`
**Controller:** `LogController@clear`

**Funcionalidades:**
- Remove logs antigos
- Configurável por dias
- Confirmação antes da exclusão

## Comandos Artisan

### 1. Limpeza Automática

```bash
# Remove logs mais antigos que 30 dias (padrão)
php artisan logs:clean

# Remove logs mais antigos que 7 dias
php artisan logs:clean --days=7

# Remove logs mais antigos que 90 dias
php artisan logs:clean --days=90
```

### 2. Agendamento (Recomendado)

Adicione ao `app/Console/Kernel.php`:

```php
protected function schedule(Schedule $schedule): void
{
    // Limpa logs antigos diariamente às 2h da manhã
    $schedule->command('logs:clean --days=30')->dailyAt('02:00');
}
```

## Integração com Middleware

### 1. LogErrors Middleware

**Arquivo:** `app/Http/Middleware/LogErrors.php`

**Funcionalidades:**
- Log automático de erros HTTP
- Diferenciação por status code
- Informações de segurança
- Integração com LogService

### 2. Uso Automático

O middleware `LogErrors` é registrado globalmente e captura automaticamente:
- Acessos negados (403)
- Rate limit excedido (429)
- Erros do servidor (500+)
- Requisições com erro

## Configuração

### 1. Registro no AppServiceProvider

```php
// app/Providers/AppServiceProvider.php
$this->app->bind(LogServiceInterface::class, LogService::class);
```

### 2. Rotas

```php
// routes/web.php
Route::middleware('auth')->group(function () {
    Route::get('/logs', [LogController::class, 'index'])->name('logs.index');
    Route::get('/logs/{log}', [LogController::class, 'show'])->name('logs.show');
    Route::post('/logs/clear', [LogController::class, 'clear'])->name('logs.clear');
    Route::get('/logs/export', [LogController::class, 'export'])->name('logs.export');
});
```

## Benefícios

### 1. Rastreabilidade
- Logs detalhados de todas as operações
- Informações de contexto completas
- Rastreamento de usuários e IPs

### 2. Debugging
- Stack traces de exceções
- Informações de arquivo e linha
- Contexto em formato JSON

### 3. Monitoramento
- Interface web para visualização
- Filtros e busca avançada
- Estatísticas em tempo real

### 4. Manutenção
- Limpeza automática de logs antigos
- Exportação para análise externa
- Comandos artisan para administração

## Próximos Passos

1. **Implementar alertas** para logs críticos
2. **Configurar notificações** por email/Slack
3. **Adicionar métricas** de performance
4. **Implementar log rotation** automático
5. **Criar dashboards** de monitoramento
6. **Adicionar logs de auditoria** para operações sensíveis
7. **Implementar logs estruturados** (JSON)
8. **Configurar backup** dos logs

## Exemplos de Uso

### 1. Log de Autenticação

```php
// Login bem-sucedido
$this->logService->info('Login realizado', [
    'user_id' => $user->id,
    'email' => $user->email,
    'ip' => request()->ip()
]);

// Login falhou
$this->logService->warning('Tentativa de login falhou', [
    'email' => $request->email,
    'ip' => request()->ip(),
    'reason' => 'Credenciais inválidas'
]);
```

### 2. Log de Operações CRUD

```php
// Criação
$this->logService->info('Recurso criado', [
    'model' => 'Task',
    'id' => $task->id,
    'user_id' => auth()->id()
]);

// Atualização
$this->logService->info('Recurso atualizado', [
    'model' => 'Task',
    'id' => $task->id,
    'changes' => $task->getChanges(),
    'user_id' => auth()->id()
]);

// Exclusão
$this->logService->warning('Recurso excluído', [
    'model' => 'Task',
    'id' => $taskId,
    'user_id' => auth()->id()
]);
```

### 3. Log de Segurança

```php
// Tentativa de acesso não autorizado
$this->logService->warning('Acesso negado', [
    'route' => request()->route()->getName(),
    'user_id' => auth()->id(),
    'ip' => request()->ip()
]);

// Rate limit excedido
$this->logService->warning('Rate limit excedido', [
    'route' => request()->route()->getName(),
    'ip' => request()->ip(),
    'user_agent' => request()->userAgent()
]);
```

## Referências

- [Laravel Logging](https://laravel.com/docs/logging)
- [Eloquent Relationships](https://laravel.com/docs/eloquent-relationships)
- [Artisan Commands](https://laravel.com/docs/artisan)
- [Task Scheduling](https://laravel.com/docs/scheduling) 
