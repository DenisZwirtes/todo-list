# 📡 Documentação da API - Todo List

Este documento descreve a API REST do projeto Todo List, incluindo endpoints, parâmetros, respostas e exemplos de uso.

## 🔗 Base URL

```
http://localhost:8000/api
```

## 🔐 Autenticação

A API usa autenticação baseada em sessão do Laravel. Todas as requisições devem incluir o token CSRF e estar autenticadas.

### Headers Obrigatórios
```
X-CSRF-TOKEN: {csrf_token}
Content-Type: application/json
Accept: application/json
```

## 📋 Endpoints

### 👤 Usuários

#### GET /api/user
Retorna informações do usuário autenticado.

**Resposta:**
```json
{
  "id": 1,
  "name": "João Silva",
  "email": "joao@example.com",
  "email_verified_at": "2024-12-17T10:00:00.000000Z",
  "created_at": "2024-12-17T10:00:00.000000Z",
  "updated_at": "2024-12-17T10:00:00.000000Z"
}
```

### 📝 Tarefas

#### GET /api/tasks
Lista todas as tarefas do usuário autenticado.

**Parâmetros de Query:**
- `page` (int): Número da página (padrão: 1)
- `per_page` (int): Itens por página (padrão: 15)
- `category_id` (int): Filtrar por categoria
- `status` (string): Filtrar por status (pending, completed)
- `priority` (string): Filtrar por prioridade (low, medium, high, urgent)
- `search` (string): Buscar por título ou descrição

**Exemplo:**
```bash
GET /api/tasks?category_id=1&status=pending&priority=high&page=1&per_page=10
```

**Resposta:**
```json
{
  "data": [
    {
      "id": 1,
      "title": "Implementar API",
      "description": "Criar endpoints da API REST",
      "status": "pending",
      "priority": "high",
      "due_date": "2024-12-20",
      "is_completed": false,
      "completed_at": null,
      "category": {
        "id": 1,
        "name": "Desenvolvimento",
        "color": "blue"
      },
      "users": [
        {
          "id": 1,
          "name": "João Silva",
          "email": "joao@example.com"
        }
      ],
      "created_at": "2024-12-17T10:00:00.000000Z",
      "updated_at": "2024-12-17T10:00:00.000000Z"
    }
  ],
  "links": {
    "first": "http://localhost:8000/api/tasks?page=1",
    "last": "http://localhost:8000/api/tasks?page=1",
    "prev": null,
    "next": null
  },
  "meta": {
    "current_page": 1,
    "from": 1,
    "last_page": 1,
    "per_page": 15,
    "to": 1,
    "total": 1
  }
}
```

#### POST /api/tasks
Cria uma nova tarefa.

**Parâmetros:**
```json
{
  "title": "Nova Tarefa",
  "description": "Descrição da tarefa",
  "category_id": 1,
  "priority": "medium",
  "due_date": "2024-12-25",
  "user_ids": [1, 2]
}
```

**Resposta (201 Created):**
```json
{
  "id": 2,
  "title": "Nova Tarefa",
  "description": "Descrição da tarefa",
  "status": "pending",
  "priority": "medium",
  "due_date": "2024-12-25",
  "is_completed": false,
  "completed_at": null,
  "category": {
    "id": 1,
    "name": "Desenvolvimento",
    "color": "blue"
  },
  "users": [
    {
      "id": 1,
      "name": "João Silva",
      "email": "joao@example.com"
    },
    {
      "id": 2,
      "name": "Maria Santos",
      "email": "maria@example.com"
    }
  ],
  "created_at": "2024-12-17T10:00:00.000000Z",
  "updated_at": "2024-12-17T10:00:00.000000Z"
}
```

#### GET /api/tasks/{id}
Retorna uma tarefa específica.

**Resposta:**
```json
{
  "id": 1,
  "title": "Implementar API",
  "description": "Criar endpoints da API REST",
  "status": "pending",
  "priority": "high",
  "due_date": "2024-12-20",
  "is_completed": false,
  "completed_at": null,
  "category": {
    "id": 1,
    "name": "Desenvolvimento",
    "color": "blue"
  },
  "users": [
    {
      "id": 1,
      "name": "João Silva",
      "email": "joao@example.com"
    }
  ],
  "created_at": "2024-12-17T10:00:00.000000Z",
  "updated_at": "2024-12-17T10:00:00.000000Z"
}
```

#### PUT /api/tasks/{id}
Atualiza uma tarefa existente.

**Parâmetros:**
```json
{
  "title": "Tarefa Atualizada",
  "description": "Nova descrição",
  "category_id": 2,
  "priority": "urgent",
  "due_date": "2024-12-22",
  "user_ids": [1, 3]
}
```

**Resposta:**
```json
{
  "id": 1,
  "title": "Tarefa Atualizada",
  "description": "Nova descrição",
  "status": "pending",
  "priority": "urgent",
  "due_date": "2024-12-22",
  "is_completed": false,
  "completed_at": null,
  "category": {
    "id": 2,
    "name": "Design",
    "color": "green"
  },
  "users": [
    {
      "id": 1,
      "name": "João Silva",
      "email": "joao@example.com"
    },
    {
      "id": 3,
      "name": "Pedro Costa",
      "email": "pedro@example.com"
    }
  ],
  "created_at": "2024-12-17T10:00:00.000000Z",
  "updated_at": "2024-12-17T10:30:00.000000Z"
}
```

#### DELETE /api/tasks/{id}
Remove uma tarefa.

**Resposta (204 No Content):**
```json
{}
```

#### PUT /api/tasks/{id}/toggle
Alterna o status de conclusão da tarefa.

**Resposta:**
```json
{
  "id": 1,
  "title": "Implementar API",
  "description": "Criar endpoints da API REST",
  "status": "completed",
  "priority": "high",
  "due_date": "2024-12-20",
  "is_completed": true,
  "completed_at": "2024-12-17T10:30:00.000000Z",
  "category": {
    "id": 1,
    "name": "Desenvolvimento",
    "color": "blue"
  },
  "users": [
    {
      "id": 1,
      "name": "João Silva",
      "email": "joao@example.com"
    }
  ],
  "created_at": "2024-12-17T10:00:00.000000Z",
  "updated_at": "2024-12-17T10:30:00.000000Z"
}
```

### 📂 Categorias

#### GET /api/categories
Lista todas as categorias do usuário autenticado.

**Parâmetros de Query:**
- `page` (int): Número da página (padrão: 1)
- `per_page` (int): Itens por página (padrão: 15)
- `search` (string): Buscar por nome

**Resposta:**
```json
{
  "data": [
    {
      "id": 1,
      "name": "Desenvolvimento",
      "color": "blue",
      "task_count": 5,
      "created_at": "2024-12-17T10:00:00.000000Z",
      "updated_at": "2024-12-17T10:00:00.000000Z"
    }
  ],
  "links": {
    "first": "http://localhost:8000/api/categories?page=1",
    "last": "http://localhost:8000/api/categories?page=1",
    "prev": null,
    "next": null
  },
  "meta": {
    "current_page": 1,
    "from": 1,
    "last_page": 1,
    "per_page": 15,
    "to": 1,
    "total": 1
  }
}
```

#### POST /api/categories
Cria uma nova categoria.

**Parâmetros:**
```json
{
  "name": "Nova Categoria",
  "color": "red"
}
```

**Resposta (201 Created):**
```json
{
  "id": 2,
  "name": "Nova Categoria",
  "color": "red",
  "task_count": 0,
  "created_at": "2024-12-17T10:00:00.000000Z",
  "updated_at": "2024-12-17T10:00:00.000000Z"
}
```

#### GET /api/categories/{id}
Retorna uma categoria específica.

**Resposta:**
```json
{
  "id": 1,
  "name": "Desenvolvimento",
  "color": "blue",
  "task_count": 5,
  "tasks": [
    {
      "id": 1,
      "title": "Implementar API",
      "status": "pending",
      "priority": "high",
      "due_date": "2024-12-20"
    }
  ],
  "created_at": "2024-12-17T10:00:00.000000Z",
  "updated_at": "2024-12-17T10:00:00.000000Z"
}
```

#### PUT /api/categories/{id}
Atualiza uma categoria existente.

**Parâmetros:**
```json
{
  "name": "Categoria Atualizada",
  "color": "green"
}
```

**Resposta:**
```json
{
  "id": 1,
  "name": "Categoria Atualizada",
  "color": "green",
  "task_count": 5,
  "created_at": "2024-12-17T10:00:00.000000Z",
  "updated_at": "2024-12-17T10:30:00.000000Z"
}
```

#### DELETE /api/categories/{id}
Remove uma categoria.

**Resposta (204 No Content):**
```json
{}
```

### 📊 Logs

#### GET /api/logs
Lista logs do sistema (apenas para administradores).

**Parâmetros de Query:**
- `page` (int): Número da página (padrão: 1)
- `per_page` (int): Itens por página (padrão: 50)
- `level` (string): Filtrar por nível (error, warning, info, debug)
- `search` (string): Buscar por mensagem
- `user_id` (int): Filtrar por usuário
- `start_date` (string): Data inicial (YYYY-MM-DD)
- `end_date` (string): Data final (YYYY-MM-DD)

**Resposta:**
```json
{
  "data": [
    {
      "id": 1,
      "level": "info",
      "message": "Usuário fez login",
      "error_message": null,
      "context": {
        "user_id": 1,
        "ip": "192.168.1.1"
      },
      "file": null,
      "line": null,
      "ip_address": "192.168.1.1",
      "user_agent": "Mozilla/5.0...",
      "user": {
        "id": 1,
        "name": "João Silva",
        "email": "joao@example.com"
      },
      "created_at": "2024-12-17T10:00:00.000000Z",
      "updated_at": "2024-12-17T10:00:00.000000Z"
    }
  ],
  "meta": {
    "total": 100,
    "errors": 5,
    "warnings": 10,
    "info": 80,
    "debug": 5
  }
}
```

#### GET /api/logs/{id}
Retorna um log específico.

**Resposta:**
```json
{
  "id": 1,
  "level": "error",
  "message": "Erro ao criar tarefa",
  "error_message": "SQLSTATE[23000]: Integrity constraint violation",
  "context": {
    "request_data": {
      "title": "Nova Tarefa",
      "category_id": 999
    },
    "user_id": 1
  },
  "file": "/app/Http/Controllers/TaskController.php",
  "line": 45,
  "ip_address": "192.168.1.1",
  "user_agent": "Mozilla/5.0...",
  "user": {
    "id": 1,
    "name": "João Silva",
    "email": "joao@example.com"
  },
  "created_at": "2024-12-17T10:00:00.000000Z",
  "updated_at": "2024-12-17T10:00:00.000000Z"
}
```

#### GET /api/logs/export
Exporta logs para CSV.

**Parâmetros de Query:**
- `level` (string): Filtrar por nível
- `start_date` (string): Data inicial
- `end_date` (string): Data final

**Resposta:**
```
Content-Type: text/csv
Content-Disposition: attachment; filename="logs_2024-12-17_10-00-00.csv"

ID,Level,Message,Error Message,File,Line,IP Address,User Agent,User ID,Created At
1,info,Usuário fez login,,,,"192.168.1.1","Mozilla/5.0...",1,2024-12-17 10:00:00
```

### 📈 Estatísticas

#### GET /api/stats
Retorna estatísticas gerais do sistema.

**Resposta:**
```json
{
  "tasks": {
    "total": 25,
    "pending": 15,
    "completed": 10,
    "overdue": 3
  },
  "categories": {
    "total": 5,
    "with_tasks": 4
  },
  "users": {
    "total": 3,
    "active": 2
  },
  "logs": {
    "total": 100,
    "errors": 5,
    "warnings": 10,
    "info": 80,
    "debug": 5
  }
}
```

## ⚠️ Códigos de Erro

### 400 Bad Request
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "title": ["The title field is required."],
    "category_id": ["The selected category id is invalid."]
  }
}
```

### 401 Unauthorized
```json
{
  "message": "Unauthenticated."
}
```

### 403 Forbidden
```json
{
  "message": "This action is unauthorized."
}
```

### 404 Not Found
```json
{
  "message": "Task not found."
}
```

### 422 Unprocessable Entity
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "email": ["The email has already been taken."]
  }
}
```

### 500 Internal Server Error
```json
{
  "message": "Server Error"
}
```

## 🔧 Rate Limiting

A API implementa rate limiting para proteger contra abuso:

- **Autenticação**: 5 tentativas por 30 minutos por IP
- **Registro**: 3 tentativas por 60 minutos por IP
- **Reset de senha**: 3 tentativas por 60 minutos por IP
- **API geral**: 60 requisições por minuto por usuário

### Headers de Rate Limiting
```
X-RateLimit-Limit: 60
X-RateLimit-Remaining: 59
X-RateLimit-Reset: 1640000000
```

## 📝 Exemplos de Uso

### JavaScript (Fetch API)
```javascript
// Listar tarefas
const response = await fetch('/api/tasks', {
  headers: {
    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
    'Content-Type': 'application/json',
    'Accept': 'application/json'
  }
});

const tasks = await response.json();
console.log(tasks.data);

// Criar tarefa
const newTask = await fetch('/api/tasks', {
  method: 'POST',
  headers: {
    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
    'Content-Type': 'application/json',
    'Accept': 'application/json'
  },
  body: JSON.stringify({
    title: 'Nova Tarefa',
    description: 'Descrição da tarefa',
    category_id: 1,
    priority: 'medium',
    due_date: '2024-12-25'
  })
});

const task = await newTask.json();
console.log(task);
```

### PHP (Guzzle)
```php
use GuzzleHttp\Client;

$client = new Client([
    'base_uri' => 'http://localhost:8000/api',
    'headers' => [
        'X-CSRF-TOKEN' => $csrfToken,
        'Content-Type' => 'application/json',
        'Accept' => 'application/json'
    ]
]);

// Listar tarefas
$response = $client->get('/tasks');
$tasks = json_decode($response->getBody(), true);

// Criar tarefa
$response = $client->post('/tasks', [
    'json' => [
        'title' => 'Nova Tarefa',
        'description' => 'Descrição da tarefa',
        'category_id' => 1,
        'priority' => 'medium',
        'due_date' => '2024-12-25'
    ]
]);

$task = json_decode($response->getBody(), true);
```

### cURL
```bash
# Listar tarefas
curl -X GET "http://localhost:8000/api/tasks" \
  -H "X-CSRF-TOKEN: {csrf_token}" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json"

# Criar tarefa
curl -X POST "http://localhost:8000/api/tasks" \
  -H "X-CSRF-TOKEN: {csrf_token}" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "title": "Nova Tarefa",
    "description": "Descrição da tarefa",
    "category_id": 1,
    "priority": "medium",
    "due_date": "2024-12-25"
  }'
```

## 🔐 Segurança

### Autenticação
- Sessões Laravel
- Tokens CSRF obrigatórios
- Rate limiting por IP e usuário

### Validação
- Validação de entrada em todos os endpoints
- Sanitização de dados
- Verificação de propriedade (ownership)

### Logs
- Logs de todas as operações
- Logs de erros de segurança
- Logs de rate limiting

## 📚 Recursos Adicionais

- [Laravel API Resources](https://laravel.com/docs/api-resources)
- [Laravel Sanctum](https://laravel.com/docs/sanctum)
- [REST API Design](https://restfulapi.net/)
- [HTTP Status Codes](https://httpstatuses.com/)

---

**A API fornece acesso completo e seguro a todas as funcionalidades do sistema.** 
