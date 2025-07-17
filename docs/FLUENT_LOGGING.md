# Interface Fluente de Logging - Todo List

Este documento descreve a implementação da interface fluente de logging, baseada no projeto de referência sistema-educacional-joineed.

## Visão Geral

A interface fluente permite criar logs de forma mais legível e encadeável, facilitando o registro de eventos de forma consistente e organizada.

## Componentes

### 1. LogOperation Enum

**Arquivo:** `app/Enums/LogOperation.php`

**Operações Disponíveis:**
- `CREATE` - Criar
- `READ` - Listar
- `UPDATE` - Atualizar
- `DELETE` - Excluir
- `VALIDATE` - Validar

### 2. FluentLogger

**Arquivo:** `app/Support/Logging/FluentLogger.php`

**Funcionalidades:**
- Interface encadeável
- Métodos específicos para operações CRUD
- Suporte a exceções
- Contexto customizável
- Integração com request

### 3. LoggerHelper

**Arquivo:** `app/Support/Logging/LoggerHelper.php`

**Helpers Disponíveis:**
- `createModel()` - Log de criação
- `readModel()` - Log de leitura
- `updateModel()` - Log de atualização
- `deleteModel()` - Log de exclusão
- `validateModel()` - Log de validação
- `listModel()` - Log de listagem

### 4. HasFluentLogging Trait

**Arquivo:** `app/Support/Logging/HasFluentLogging.php`

**Métodos Disponíveis:**
- `logCreate()` - Log de criação
- `logRead()` - Log de leitura
- `logUpdate()` - Log de atualização
- `logDelete()` - Log de exclusão
- `logValidation()` - Log de validação
- `logListing()` - Log de listagem
- `logError()` - Log de erro com exceção
- `logErrorWithRequest()` - Log de erro com request

## Como Usar

### 1. Uso Direto do FluentLogger

```php
use App\Support\Logging\FluentLogger;
use App\Enums\LogOperation;

// Log básico
(new FluentLogger())
    ->crud(LogOperation::CREATE, 'Task', 123)
    ->log();

// Log com contexto
(new FluentLogger())
    ->crud(LogOperation::UPDATE, 'Task', 123)
    ->context(['changes' => $changes])
    ->log();

// Log com request
(new FluentLogger())
    ->crud(LogOperation::CREATE, 'Task', 123)
    ->request($request)
    ->log();

// Log com exceção
(new FluentLogger())
    ->crud(LogOperation::CREATE, 'Task', 123)
    ->exception($exception)
    ->log();
```

### 2. Uso com LoggerHelper

```php
use App\Support\Logging\LoggerHelper;
use App\Enums\LogOperation;

// Log de criação
LoggerHelper::createModel('Task', 123)
    ->context(['title' => 'Nova tarefa'])
    ->log();

// Log de atualização
LoggerHelper::updateModel('Task', 123)
    ->context(['changes' => $changes])
    ->log();

// Log de exclusão
LoggerHelper::deleteModel('Task', 123)
    ->context(['title' => $task->title])
    ->log();

// Log de validação
LoggerHelper::validateModel('Task', $errors)
    ->context(['validation_data' => $request->all()])
    ->log();
```

### 3. Uso com Trait em Controllers

```php
use App\Support\Logging\HasFluentLogging;
use App\Enums\LogOperation;

class TaskController extends Controller
{
    use HasFluentLogging;

    public function store(Request $request)
    {
        try {
            $task = $this->taskService->create($taskDTO);
            
            // Log de sucesso
            $this->logCreate('Task', $task->id, [
                'title' => $task->title,
                'status' => $task->status
            ]);

            return response()->json(['message' => 'Tarefa criada']);
        } catch (\Exception $e) {
            // Log de erro
            $this->logErrorWithRequest('Task', LogOperation::CREATE, $request, $e);
            throw $e;
        }
    }

    public function update(Request $request, Task $task)
    {
        try {
            $updatedTask = $this->taskService->update($task->id, $taskDTO);
            
            // Log de sucesso
            $this->logUpdate('Task', $task->id, [
                'changes' => $updatedTask->getChanges()
            ]);

            return response()->json(['message' => 'Tarefa atualizada']);
        } catch (\Exception $e) {
            // Log de erro
            $this->logErrorWithRequest('Task', LogOperation::UPDATE, $request, $e);
            throw $e;
        }
    }

    public function destroy(Task $task)
    {
        try {
            $taskId = $task->id;
            $taskTitle = $task->title;
            
            $this->taskService->delete($taskId);
            
            // Log de sucesso
            $this->logDelete('Task', $taskId, [
                'title' => $taskTitle
            ]);

            return response()->json(['message' => 'Tarefa excluída']);
        } catch (\Exception $e) {
            // Log de erro
            $this->logError('Task', LogOperation::DELETE, $e);
            throw $e;
        }
    }
}
```

## Exemplos de Uso

### 1. Log de Operação CRUD Completa

```php
// Criação
(new FluentLogger())
    ->crud(LogOperation::CREATE, 'Task', $task->id)
    ->request($request)
    ->context([
        'title' => $task->title,
        'status' => $task->status,
        'priority' => $task->priority
    ])
    ->log();

// Atualização
(new FluentLogger())
    ->crud(LogOperation::UPDATE, 'Task', $task->id)
    ->request($request)
    ->context([
        'changes' => $task->getChanges(),
        'original' => $task->getOriginal()
    ])
    ->log();

// Exclusão
(new FluentLogger())
    ->crud(LogOperation::DELETE, 'Task', $task->id)
    ->context([
        'title' => $task->title,
        'status' => $task->status
    ])
    ->log();
```

### 2. Log de Validação

```php
// Validação com erros
(new FluentLogger())
    ->validation('Task', $errors)
    ->request($request)
    ->context([
        'validation_data' => $request->all()
    ])
    ->log();
```

### 3. Log de Listagem

```php
// Listagem com filtros
(new FluentLogger())
    ->listing('Task')
    ->request($request)
    ->context([
        'filters' => $request->only(['status', 'priority']),
        'total_count' => $tasks->count()
    ])
    ->log();
```

### 4. Log de Erro com Exceção

```php
try {
    // Operação que pode falhar
    $task = $this->taskService->create($taskDTO);
} catch (\Exception $e) {
    (new FluentLogger())
        ->crud(LogOperation::CREATE, 'Task')
        ->request($request)
        ->exception($e)
        ->context([
            'validation_data' => $request->all(),
            'error_context' => 'task_creation'
        ])
        ->log();
    
    throw $e;
}
```

## Operações Encadeadas

### 1. Configuração Gradual

```php
$logger = new FluentLogger();

$logger->model('Task', 123)
       ->operation(LogOperation::UPDATE)
       ->request($request)
       ->context(['step' => 'preparation']);

// Adicionar mais contexto
$logger->context(['additional_data' => 'chained_example']);

// Executar o log
$logger->log();
```

### 2. Configuração Condicional

```php
$logger = new FluentLogger();
$logger->crud(LogOperation::CREATE, 'Task', $task->id);

if ($request->has('priority')) {
    $logger->context(['priority' => $request->priority]);
}

if ($exception) {
    $logger->exception($exception);
}

$logger->log();
```

## Integração com Middleware

### 1. Log Automático de Erros

```php
// No middleware LogErrors
protected function logSecurityEvent(Request $request, Response $response): void
{
    $logData = [
        'ip' => $request->ip(),
        'user_agent' => $request->userAgent(),
        'method' => $request->method(),
        'url' => $request->fullUrl(),
        'status_code' => $response->getStatusCode(),
        'user_id' => $request->user()?->id,
        'timestamp' => now()->toISOString(),
    ];

    if ($response->getStatusCode() === 403) {
        (new FluentLogger())
            ->crud(LogOperation::READ, 'Security')
            ->context($logData)
            ->log();
    }
}
```

## Benefícios

### 1. Legibilidade
- Código mais limpo e legível
- Intenção clara das operações
- Fácil manutenção

### 2. Consistência
- Padrão uniforme para todos os logs
- Estrutura consistente de dados
- Facilita análise e monitoramento

### 3. Flexibilidade
- Configuração gradual
- Contexto customizável
- Suporte a diferentes cenários

### 4. Reutilização
- Helpers para operações comuns
- Trait para uso em controllers
- Redução de código duplicado

## Estrutura dos Logs Gerados

### 1. Log de Sucesso
```json
{
    "level": "info",
    "message": "Tarefa criada com sucesso",
    "context": {
        "operation": "criar",
        "operation_label": "Criar",
        "model": "Task",
        "model_id": 123,
        "user_id": 1,
        "title": "Nova tarefa",
        "status": "pending",
        "priority": "medium"
    }
}
```

### 2. Log de Erro
```json
{
    "level": "error",
    "message": "Erro ao criar Task",
    "error_message": "Validation failed",
    "context": {
        "operation": "criar",
        "operation_label": "Criar",
        "model": "Task",
        "model_id": null,
        "user_id": 1,
        "validation_errors": {
            "title": ["O título é obrigatório"]
        },
        "request_data": {
            "title": "",
            "description": "Descrição"
        }
    },
    "file": "/app/Http/Controllers/TaskController.php",
    "line": 45
}
```

## Próximos Passos

1. **Implementar logs automáticos** para todas as operações CRUD
2. **Criar dashboards** específicos para cada tipo de operação
3. **Adicionar métricas** de performance por operação
4. **Implementar alertas** para operações críticas
5. **Criar relatórios** de uso por modelo
6. **Adicionar logs de auditoria** para operações sensíveis
7. **Implementar logs de performance** para operações lentas
8. **Criar filtros avançados** na interface web

## Referências

- [Fluent Interface Pattern](https://en.wikipedia.org/wiki/Fluent_interface)
- [Method Chaining](https://en.wikipedia.org/wiki/Method_chaining)
- [Laravel Logging](https://laravel.com/docs/logging)
- [PHP Enums](https://www.php.net/manual/en/language.enumerations.php) 
