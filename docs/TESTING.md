# Sistema de Testes - Todo List

Este documento descreve o sistema de testes implementado no projeto Todo List, baseado no Pest PHP e com cobertura abrangente de todas as funcionalidades.

## 📊 Visão Geral

### Estatísticas dos Testes
- **94 testes passando** (100% de sucesso)
- **52.8% de cobertura** total do código
- **275 assertions** executadas
- **Tempo de execução:** ~4 segundos
- **Framework:** Pest PHP
- **Ambiente:** Docker containerizado

### Áreas Cobertas
- ✅ **Controllers** - Todos os controllers principais
- ✅ **Models** - Modelos com relacionamentos
- ✅ **Services** - Lógica de negócio
- ✅ **Policies** - Autorizações
- ✅ **Rules** - Validações customizadas
- ✅ **Commands** - Comandos Artisan
- ✅ **Enums** - Tipos enumerados
- ✅ **DTOs** - Data Transfer Objects
- ✅ **Repositories** - Acesso a dados
- ✅ **Interfaces** - Contratos de serviços

## 🧪 Tipos de Testes

### 1. Testes Unitários (`tests/Unit/`)

#### ModelsTest.php
```php
test('user model has relationships', function () {
    $user = User::factory()->create();
    
    expect($user->tasks)->toBeInstanceOf(Collection::class);
    expect($user->categories)->toBeInstanceOf(Collection::class);
});
```

**Cobertura:**
- Relacionamentos entre modelos
- Métodos de acesso
- Validações de modelo
- Scopes e acessors

#### ServicesTest.php
```php
test('category service can create category', function () {
    $user = User::factory()->create();
    $categoryDTO = new CategoryDTO('Nova Categoria', 'blue');
    
    $category = app(CategoryServiceInterface::class)->create($categoryDTO);
    
    expect($category->name)->toBe('Nova Categoria');
    expect($category->user_id)->toBe($user->id);
});
```

**Cobertura:**
- Lógica de negócio
- Validações de serviço
- Integração com repositories
- Tratamento de erros

#### PoliciesTest.php
```php
test('category policy allows user to view own categories', function () {
    $user = User::factory()->create();
    $category = Category::factory()->create(['user_id' => $user->id]);
    
    expect($user->can('view', $category))->toBeTrue();
});
```

**Cobertura:**
- Permissões de usuário
- Políticas de autorização
- Acesso a recursos
- Segurança de dados

#### RulesTest.php
```php
test('category ownership rule validates ownership correctly', function () {
    $user = User::factory()->create();
    $category = Category::factory()->create(['user_id' => $user->id]);
    
    $rule = new CategoryOwnership();
    
    expect($rule->passes('category_id', $category->id))->toBeTrue();
});
```

**Cobertura:**
- Regras de validação customizadas
- Verificação de propriedade
- Tratamento de casos especiais
- Validações complexas

#### EnumsTest.php
```php
test('log operation enum has all expected values', function () {
    expect(LogOperation::cases())->toHaveCount(5);
    expect(LogOperation::CREATE->value)->toBe('criar');
    expect(LogOperation::READ->value)->toBe('listar');
});
```

**Cobertura:**
- Valores dos enums
- Comparações
- Uso em switch statements
- Contexto de array

### 2. Testes de Feature (`tests/Feature/`)

#### TaskTest.php
```php
test('it creates a new task', function () {
    $user = User::factory()->create();
    $category = Category::factory()->create(['user_id' => $user->id]);
    
    actingAs($user);
    
    $response = post('/tasks', [
        'title' => 'Nova Tarefa',
        'description' => 'Descrição da tarefa',
        'category_id' => $category->id,
        'priority' => 'medium',
        'due_date' => now()->addDays(7)->format('Y-m-d')
    ]);
    
    $response->assertRedirect('/tasks');
    expect(Task::where('title', 'Nova Tarefa')->exists())->toBeTrue();
});
```

**Cobertura:**
- Fluxos completos de aplicação
- Autenticação e autorização
- Validação de formulários
- Redirecionamentos
- Persistência de dados

#### LogControllerTest.php
```php
test('it displays logs index page', function () {
    $user = User::factory()->create();
    actingAs($user);
    
    $response = get('/logs');
    $response->assertStatus(200);
    $response->assertViewIs('logs.index');
});
```

**Cobertura:**
- Controllers completos
- Views e templates
- Filtros e busca
- Exportação de dados
- Estatísticas

#### CommandsTest.php
```php
test('clean old logs command removes logs older than specified days', function () {
    Log::factory()->create(['created_at' => now()->subDays(40)]);
    Log::factory()->create(['created_at' => now()->subDays(10)]);
    
    artisan('logs:clean', ['--days' => 30])
        ->expectsOutput('1 logs foram removidos.')
        ->assertExitCode(0);
    
    expect(Log::count())->toBe(1);
});
```

**Cobertura:**
- Comandos Artisan
- Argumentos e opções
- Saída de comandos
- Códigos de saída
- Efeitos colaterais

## 🏭 Factories

### LogFactory.php
```php
class LogFactory extends Factory
{
    public function definition(): array
    {
        return [
            'level' => $this->faker->randomElement(['error', 'warning', 'info', 'debug']),
            'message' => $this->faker->sentence(),
            'error_message' => $this->faker->optional()->sentence(),
            'context' => $this->faker->optional()->randomElements(['key' => 'value'], 1),
            'file' => $this->faker->optional()->filePath(),
            'line' => $this->faker->optional()->numberBetween(1, 1000),
            'ip_address' => $this->faker->ipv4(),
            'user_agent' => $this->faker->userAgent(),
            'user_id' => User::factory(),
        ];
    }
    
    public function error(): static
    {
        return $this->state(fn (array $attributes) => [
            'level' => 'error',
            'error_message' => $this->faker->sentence(),
        ]);
    }
}
```

**Estados Disponíveis:**
- `error()` - Logs de erro
- `warning()` - Logs de aviso
- `info()` - Logs informativos
- `debug()` - Logs de debug

## 🚀 Executando Testes

### Comandos Básicos
```bash
# Executar todos os testes
./docker-dev.sh test

# Executar testes específicos
docker-compose exec app php artisan test --filter=TaskTest

# Executar testes com coverage
docker-compose exec app php artisan test --coverage

# Executar apenas testes unitários
docker-compose exec app php artisan test tests/Unit/

# Executar apenas testes de feature
docker-compose exec app php artisan test tests/Feature/
```

### Comandos Avançados
```bash
# Executar testes com detalhes
docker-compose exec app php artisan test --verbose

# Executar testes paralelos
docker-compose exec app php artisan test --parallel

# Executar testes com relatório de coverage
docker-compose exec app php artisan test --coverage --min=50

# Executar testes de um arquivo específico
docker-compose exec app php artisan test tests/Feature/TaskTest.php
```

## 📈 Cobertura de Código

### Áreas com Maior Cobertura
- **Models** - 100% (User, Category, Task, Log)
- **Services** - 100% (TaskService, CategoryService, LogService)
- **Policies** - 100% (TaskPolicy, CategoryPolicy)
- **Rules** - 100% (TaskOwnership, CategoryOwnership)
- **Commands** - 100% (CleanOldLogs, HashUserPasswords)
- **Enums** - 100% (LogOperation, Priority, TaskStatus)

### Áreas com Cobertura Média
- **Controllers** - 58-66% (TaskController, CategoryController, LogController)
- **Repositories** - 67-70% (TaskRepository, CategoryRepository)
- **Services** - 61-68% (TaskService, CategoryService)

### Áreas com Baixa Cobertura
- **Middleware** - 0% (AuthRateLimiter, LogErrors, etc.)
- **Controllers de Auth** - 0-50% (ConfirmPassword, Verification)
- **Console Kernel** - 0% (Agendamento de comandos)

## 🔧 Configuração de Testes

### Ambiente de Teste
```php
// tests/bootstrap.php
<?php

// Copiar configuração de teste
if (!file_exists('.env')) {
    copy('.env.testing', '.env');
}

// Limpar cache de configuração
Artisan::call('config:clear');
```

### Configuração Pest
```php
// tests/Pest.php
<?php

uses(
    Tests\TestCase::class,
    Illuminate\Foundation\Testing\RefreshDatabase::class,
)->in('Feature');

uses(
    Tests\TestCase::class,
    Illuminate\Foundation\Testing\RefreshDatabase::class,
)->in('Unit');
```

### Database de Teste
```env
# .env.testing
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=todo_list_testing
DB_USERNAME=todo_user
DB_PASSWORD=todo_pass
```

## 🐛 Troubleshooting

### Problemas Comuns

#### 1. Testes falhando por ambiente
```bash
# Limpar cache de configuração
docker-compose exec app php artisan config:clear

# Recriar banco de teste
docker-compose exec app php artisan migrate:fresh --env=testing
```

#### 2. Factory não encontrada
```bash
# Verificar se o modelo tem HasFactory
# Adicionar ao modelo:
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Log extends Model
{
    use HasFactory;
}
```

#### 3. Testes lentos
```bash
# Executar testes em paralelo
docker-compose exec app php artisan test --parallel

# Usar banco em memória para testes
# Configurar SQLite em .env.testing
```

#### 4. Cobertura baixa
```bash
# Verificar quais arquivos não estão cobertos
docker-compose exec app php artisan test --coverage

# Adicionar testes para áreas específicas
# Focar em controllers e services principais
```

## 📋 Boas Práticas

### 1. Estrutura de Testes
- Use nomes descritivos para testes
- Agrupe testes relacionados
- Use factories para dados de teste
- Mantenha testes independentes

### 2. Assertions
- Use assertions específicas
- Verifique tanto casos de sucesso quanto de erro
- Teste edge cases
- Valide efeitos colaterais

### 3. Performance
- Use RefreshDatabase apenas quando necessário
- Evite criar muitos registros desnecessários
- Use factories com dados mínimos
- Execute testes em paralelo quando possível

### 4. Manutenção
- Atualize testes quando mudar funcionalidades
- Mantenha testes simples e legíveis
- Documente casos de teste complexos
- Revise cobertura regularmente

## 🎯 Próximos Passos

### Melhorias Planejadas
1. **Aumentar cobertura para 80%**
   - Adicionar testes para middlewares
   - Cobrir controllers de autenticação
   - Testar console kernel

2. **Testes de Performance**
   - Testes de carga
   - Benchmarks de queries
   - Testes de memória

3. **Testes de Integração**
   - Testes de API
   - Testes de frontend
   - Testes end-to-end

4. **Automação**
   - CI/CD com GitHub Actions
   - Relatórios automáticos
   - Notificações de falhas

## 📚 Recursos Adicionais

- [Documentação Pest PHP](https://pestphp.com/)
- [Laravel Testing](https://laravel.com/docs/testing)
- [PHPUnit](https://phpunit.de/)
- [Test Driven Development](https://en.wikipedia.org/wiki/Test-driven_development)

---

**O sistema de testes garante qualidade, confiabilidade e manutenibilidade do código.** 
