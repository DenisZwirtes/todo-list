# Clean Architecture - Todo List

## Visão Geral

Este projeto implementa os princípios da Clean Architecture (Arquitetura Limpa) seguindo os padrões estabelecidos no projeto de referência `/home/denis/sistema-educacional-joineed`.

## Estrutura da Clean Architecture

### 📁 **Camadas da Arquitetura**

```
app/
├── Contracts/           # Interfaces (Contratos)
│   └── Services/       # Interfaces dos Services
├── DTOs/              # Data Transfer Objects
├── Enums/             # Tipos Enumerados
├── Http/              # Controllers (Camada de Apresentação)
├── Models/             # Entidades (Camada de Domínio)
├── Repositories/       # Acesso a Dados
│   └── Interfaces/     # Interfaces dos Repositories
├── Rules/             # Regras de Validação
├── Services/           # Lógica de Negócio
└── Support/           # Classes de Suporte
```

### 🎯 **Princípios Implementados**

1. **Separação de Responsabilidades**
2. **Inversão de Dependência**
3. **Injeção de Dependência**
4. **Single Responsibility Principle**
5. **Open/Closed Principle**

## 📋 **Componentes da Arquitetura**

### **DTOs (Data Transfer Objects)**

Responsáveis por transferir dados entre camadas e encapsular regras de validação.

```php
// app/DTOs/TaskDTO.php
class TaskDTO
{
    public function __construct(
        public string $title,
        public ?string $description = null,
        public bool $completed = false,
        // ...
    ) {}

    public static function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            // ...
        ];
    }
}
```

### **Enums (Tipos Enumerados)**

Definem tipos específicos do domínio com comportamentos associados.

```php
// app/Enums/TaskStatus.php
enum TaskStatus: string
{
    case PENDING = 'pending';
    case COMPLETED = 'completed';
    
    public function label(): string
    {
        return match($this) {
            self::PENDING => 'Pendente',
            self::COMPLETED => 'Concluída',
        };
    }
}
```

### **Repositories (Acesso a Dados)**

Encapsulam a lógica de acesso a dados e implementam interfaces.

```php
// app/Repositories/Interfaces/TaskRepositoryInterface.php
interface TaskRepositoryInterface
{
    public function listByUser(int $userId, array $filters = []): LengthAwarePaginator;
    public function create(array $data): Task;
    public function update(int $id, array $data): Task;
    // ...
}

// app/Repositories/TaskRepository.php
class TaskRepository implements TaskRepositoryInterface
{
    public function listByUser(int $userId, array $filters = []): LengthAwarePaginator
    {
        $query = Task::where('user_id', $userId);
        // Lógica de filtros e paginação
        return $query->paginate(15);
    }
}
```

### **Services (Lógica de Negócio)**

Contêm a lógica de negócio e orquestram as operações.

```php
// app/Contracts/Services/TaskServiceInterface.php
interface TaskServiceInterface
{
    public function listUserTasks(array $filters = []): LengthAwarePaginator;
    public function create(TaskDTO $taskDTO): Task;
    public function update(int $taskId, TaskDTO $taskDTO): Task;
    // ...
}

// app/Services/TaskService.php
class TaskService implements TaskServiceInterface
{
    protected $taskRepository;

    public function __construct(TaskRepositoryInterface $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    public function create(TaskDTO $taskDTO): Task
    {
        $user = Auth::user();
        $data = $taskDTO->toArray();
        $data['user_id'] = $user->id;
        
        return $this->taskRepository->create($data);
    }
}
```

### **Controllers (Camada de Apresentação)**

Responsáveis apenas por receber requisições e retornar respostas.

```php
// app/Http/Controllers/TaskController.php
class TaskController extends Controller
{
    protected $taskService;

    public function __construct(TaskServiceInterface $taskService)
    {
        $this->taskService = $taskService;
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate(TaskDTO::rules(), TaskDTO::messages());
        $taskDTO = TaskDTO::fromValidated($validated);
        $task = $this->taskService->create($taskDTO);

        return response()->json([
            'message' => 'Tarefa criada com sucesso!',
            'task' => $task
        ], 201);
    }
}
```

### **Rules (Regras de Validação)**

Regras de validação customizadas para o domínio.

```php
// app/Rules/TaskOwnership.php
class TaskOwnership implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $task = Task::find($value);
        
        if (!$task || $task->user_id !== auth()->id()) {
            $fail('Esta tarefa não pertence ao usuário autenticado.');
        }
    }
}
```

## 🔄 **Fluxo de Dados**

### **Criação de Tarefa**

```
1. Request → Controller
2. Controller → Validação (DTO)
3. Controller → Service
4. Service → Repository
5. Repository → Database
6. Response ← Controller
```

### **Listagem de Tarefas**

```
1. Request → Controller
2. Controller → Service
3. Service → Repository
4. Repository → Database
5. Repository → Service
6. Service → Controller
7. Controller → Response
```

## 🛠️ **Injeção de Dependência**

### **Service Provider**

```php
// app/Providers/AppServiceProvider.php
public function register(): void
{
    // Registrar Repositories
    $this->app->bind(TaskRepositoryInterface::class, TaskRepository::class);
    $this->app->bind(CategoryRepositoryInterface::class, CategoryRepository::class);

    // Registrar Services
    $this->app->bind(TaskServiceInterface::class, TaskService::class);
    $this->app->bind(CategoryServiceInterface::class, CategoryService::class);
}
```

### **Uso nos Controllers**

```php
class TaskController extends Controller
{
    protected $taskService;

    public function __construct(TaskServiceInterface $taskService)
    {
        $this->taskService = $taskService;
    }
}
```

## 🧪 **Testes com Clean Architecture**

### **Testes de Service**

```php
// tests/Unit/Services/TaskServiceTest.php
class TaskServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_task()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $taskDTO = new TaskDTO(
            title: 'Nova Tarefa',
            description: 'Descrição da tarefa'
        );

        $taskService = app(TaskServiceInterface::class);
        $task = $taskService->create($taskDTO);

        $this->assertInstanceOf(Task::class, $task);
        $this->assertEquals('Nova Tarefa', $task->title);
    }
}
```

### **Testes de Repository**

```php
// tests/Unit/Repositories/TaskRepositoryTest.php
class TaskRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_user_tasks()
    {
        $user = User::factory()->create();
        $tasks = Task::factory()->count(3)->create(['user_id' => $user->id]);

        $repository = app(TaskRepositoryInterface::class);
        $result = $repository->listByUser($user->id);

        $this->assertEquals(3, $result->count());
    }
}
```

## 📊 **Benefícios da Clean Architecture**

### **1. Testabilidade**
- Services e Repositories podem ser testados isoladamente
- Mocks e stubs são facilmente implementados
- Testes unitários mais focados

### **2. Manutenibilidade**
- Código organizado por responsabilidades
- Mudanças isoladas em camadas específicas
- Fácil localização de problemas

### **3. Escalabilidade**
- Novas funcionalidades não afetam código existente
- Interfaces permitem múltiplas implementações
- Arquitetura preparada para crescimento

### **4. Flexibilidade**
- Troca de implementações sem afetar outras camadas
- Fácil migração de tecnologias
- Adaptação a novos requisitos

## 🔧 **Padrões Utilizados**

### **Repository Pattern**
- Abstração do acesso a dados
- Facilita testes e troca de implementações
- Centraliza queries complexas

### **Service Pattern**
- Encapsula lógica de negócio
- Orquestra operações complexas
- Mantém controllers limpos

### **DTO Pattern**
- Transferência de dados tipada
- Validação centralizada
- Contratos claros entre camadas

### **Dependency Injection**
- Inversão de controle
- Facilita testes
- Reduz acoplamento

## 📈 **Comparação: Antes vs. Depois**

### **Antes (Arquitetura Tradicional)**
```php
class TaskController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $task = Task::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('tasks.index');
    }
}
```

### **Depois (Clean Architecture)**
```php
class TaskController extends Controller
{
    protected $taskService;

    public function __construct(TaskServiceInterface $taskService)
    {
        $this->taskService = $taskService;
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate(TaskDTO::rules(), TaskDTO::messages());
        $taskDTO = TaskDTO::fromValidated($validated);
        $task = $this->taskService->create($taskDTO);

        return response()->json([
            'message' => 'Tarefa criada com sucesso!',
            'task' => $task
        ], 201);
    }
}
```

## 🚀 **Próximos Passos**

1. **Implementar mais DTOs** para outras entidades
2. **Criar mais Enums** para tipos específicos
3. **Adicionar mais Services** para lógicas complexas
4. **Implementar testes** para todas as camadas
5. **Documentar APIs** com OpenAPI/Swagger
6. **Adicionar cache** nos repositories
7. **Implementar eventos** para operações importantes
8. **Criar middlewares** para validações específicas

## 📚 **Referências**

- [Clean Architecture - Robert C. Martin](https://blog.cleancoder.com/uncle-bob/2012/08/13/the-clean-architecture.html)
- [Laravel Best Practices](https://laravel.com/docs/best-practices)
- [SOLID Principles](https://en.wikipedia.org/wiki/SOLID)
- [Repository Pattern](https://martinfowler.com/eaaCatalog/repository.html) 
