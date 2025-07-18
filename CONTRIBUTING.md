# 🤝 Guia de Contribuição - Todo List

Obrigado por considerar contribuir para o projeto Todo List! Este documento fornece diretrizes e informações importantes para contribuições.

## 📋 Índice

- [Como Contribuir](#como-contribuir)
- [Configuração do Ambiente](#configuração-do-ambiente)
- [Padrões de Código](#padrões-de-código)
- [Testes](#testes)
- [Documentação](#documentação)
- [Processo de Pull Request](#processo-de-pull-request)
- [Código de Conduta](#código-de-conduta)

## 🚀 Como Contribuir

### Tipos de Contribuições Aceitas

- ✅ **Correções de Bugs** - Problemas identificados e corrigidos
- ✅ **Melhorias de Performance** - Otimizações de código
- ✅ **Novas Funcionalidades** - Features bem planejadas
- ✅ **Melhorias de UX/UI** - Interface e experiência do usuário
- ✅ **Documentação** - Melhorias na documentação
- ✅ **Testes** - Aumento da cobertura de testes
- ✅ **Refatoração** - Melhorias na arquitetura

### Antes de Começar

1. **Verifique se já existe uma issue** relacionada ao que você quer fazer
2. **Discuta mudanças grandes** em uma issue antes de implementar
3. **Certifique-se de que entende** a arquitetura do projeto
4. **Leia a documentação** na pasta `docs/`

## 🛠️ Configuração do Ambiente

### Pré-requisitos
- Docker
- Docker Compose
- Git
- Conhecimento básico de Laravel e Vue.js

### Configuração Inicial

```bash
# 1. Clone o repositório
git clone <url-do-repositorio>
cd todo-list

# 2. Configure o ambiente
cp env.docker.example .env.docker

# 3. Inicie os containers
./docker-dev.sh start

# 4. Execute as migrações
./docker-dev.sh artisan migrate

# 5. Execute os seeders
./docker-dev.sh artisan db:seed

# 6. Execute os testes
./docker-dev.sh test
```

### Verificação do Ambiente

```bash
# Verificar se tudo está funcionando
./docker-dev.sh status

# Verificar logs
./docker-dev.sh logs

# Acessar shell do container
./docker-dev.sh shell
```

## 📝 Padrões de Código

### PHP (Laravel)

#### PSR-12 Compliance
```php
<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(): \Illuminate\View\View
    {
        $users = User::paginate(10);
        
        return view('users.index', compact('users'));
    }
}
```

#### Clean Architecture
```php
// ✅ Bom - Usar Services
class TaskController extends Controller
{
    public function __construct(
        private TaskServiceInterface $taskService
    ) {}
    
    public function store(Request $request): RedirectResponse
    {
        $taskDTO = new TaskDTO(
            $request->title,
            $request->description,
            $request->category_id
        );
        
        $task = $this->taskService->create($taskDTO);
        
        return redirect()->route('tasks.index');
    }
}

// ❌ Ruim - Lógica no Controller
class TaskController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $task = Task::create($request->all());
        // Lógica de negócio aqui...
    }
}
```

#### Nomenclatura
- **Controllers**: `TaskController`, `CategoryController`
- **Models**: `Task`, `Category`, `User`
- **Services**: `TaskService`, `CategoryService`
- **Repositories**: `TaskRepository`, `CategoryRepository`
- **DTOs**: `TaskDTO`, `CategoryDTO`
- **Enums**: `TaskStatus`, `Priority`
- **Rules**: `TaskOwnership`, `CategoryOwnership`

### JavaScript/Vue.js

#### Vue 3 Composition API
```vue
<template>
  <div class="task-list">
    <div v-for="task in tasks" :key="task.id" class="task-item">
      <h3>{{ task.title }}</h3>
      <p>{{ task.description }}</p>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { router } from '@inertiajs/vue3'

const tasks = ref([])

const fetchTasks = async () => {
  const response = await fetch('/api/tasks')
  tasks.value = await response.json()
}

onMounted(() => {
  fetchTasks()
})
</script>
```

#### Nomenclatura Vue
- **Components**: `TaskList.vue`, `CategoryForm.vue`
- **Pages**: `Tasks/Index.vue`, `Categories/Create.vue`
- **Composables**: `useTasks.js`, `useCategories.js`

### CSS/Tailwind

#### Classes Tailwind
```html
<!-- ✅ Bom - Classes organizadas -->
<div class="flex items-center justify-between p-4 bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow">
  <h2 class="text-xl font-semibold text-gray-900">Título</h2>
  <button class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
    Ação
  </button>
</div>

<!-- ❌ Ruim - Classes desorganizadas -->
<div class="bg-white p-4 rounded-lg shadow-md flex items-center justify-between hover:shadow-lg transition-shadow">
```

## 🧪 Testes

### Obrigatório para Todas as Contribuições

1. **Escreva testes** para novas funcionalidades
2. **Atualize testes** quando modificar funcionalidades existentes
3. **Mantenha cobertura** de pelo menos 50%
4. **Execute todos os testes** antes de submeter

### Executando Testes

```bash
# Executar todos os testes
./docker-dev.sh test

# Executar testes específicos
docker-compose exec app php artisan test --filter=TaskTest

# Executar com coverage
docker-compose exec app php artisan test --coverage

# Executar testes de um arquivo
docker-compose exec app php artisan test tests/Feature/TaskTest.php
```

### Escrevendo Testes

#### Teste de Feature
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

#### Teste Unitário
```php
test('task service can create task', function () {
    $user = User::factory()->create();
    $taskDTO = new TaskDTO('Nova Tarefa', 'Descrição', 1);
    
    $task = app(TaskServiceInterface::class)->create($taskDTO);
    
    expect($task->title)->toBe('Nova Tarefa');
    expect($task->user_id)->toBe($user->id);
});
```

### Cobertura de Testes

- **Mínimo**: 50% de cobertura
- **Ideal**: 80% de cobertura
- **Áreas críticas**: 100% de cobertura (Services, Models, Policies)

## 📚 Documentação

### Atualizando Documentação

1. **README.md** - Atualize para mudanças significativas
2. **CHANGELOG.md** - Adicione entradas para novas versões
3. **docs/** - Atualize documentação técnica relevante
4. **Comentários** - Adicione comentários em código complexo

### Padrões de Documentação

```php
/**
 * Cria uma nova tarefa para o usuário autenticado.
 *
 * @param TaskDTO $taskDTO Dados da tarefa
 * @return Task Tarefa criada
 * @throws \InvalidArgumentException Se dados inválidos
 */
public function create(TaskDTO $taskDTO): Task
{
    // Implementação...
}
```

## 🔄 Processo de Pull Request

### 1. Preparação

```bash
# 1. Fork o repositório
# 2. Clone seu fork
git clone https://github.com/seu-usuario/todo-list.git

# 3. Adicione o repositório original como upstream
git remote add upstream https://github.com/original/todo-list.git

# 4. Crie uma branch para sua feature
git checkout -b feature/nova-funcionalidade
```

### 2. Desenvolvimento

```bash
# 1. Faça suas mudanças
# 2. Adicione arquivos
git add .

# 3. Commit com mensagem descritiva
git commit -m "feat: adiciona filtro avançado de tarefas

- Implementa filtro por data de vencimento
- Adiciona filtro por prioridade
- Inclui testes para novos filtros
- Atualiza documentação"

# 4. Push para sua branch
git push origin feature/nova-funcionalidade
```

### 3. Pull Request

#### Checklist do PR
- [ ] **Código segue padrões** do projeto
- [ ] **Testes passam** (94/94)
- [ ] **Cobertura mantida** (≥50%)
- [ ] **Documentação atualizada**
- [ ] **CHANGELOG atualizado**
- [ ] **README atualizado** (se necessário)
- [ ] **Screenshots** (para mudanças de UI)

#### Template do PR
```markdown
## 📝 Descrição
Breve descrição das mudanças implementadas.

## 🎯 Tipo de Mudança
- [ ] Bug fix
- [ ] Nova funcionalidade
- [ ] Melhoria de performance
- [ ] Refatoração
- [ ] Documentação
- [ ] Testes

## 🧪 Testes
- [ ] Testes unitários adicionados/atualizados
- [ ] Testes de feature adicionados/atualizados
- [ ] Todos os testes passam
- [ ] Cobertura mantida

## 📚 Documentação
- [ ] README atualizado
- [ ] CHANGELOG atualizado
- [ ] Documentação técnica atualizada

## 🔍 Checklist
- [ ] Código segue padrões PSR-12
- [ ] Clean Architecture mantida
- [ ] Sem warnings ou erros
- [ ] Funciona em ambiente Docker
- [ ] Testado manualmente

## 📸 Screenshots (se aplicável)
Adicione screenshots para mudanças de UI.

## 🔗 Issues Relacionadas
Closes #123
```

### 4. Review Process

1. **Code Review** - Pelo menos um maintainer deve aprovar
2. **CI/CD** - Todos os testes devem passar
3. **Documentation Review** - Verificar se documentação está atualizada
4. **Final Approval** - Maintainer final aprova e merge

## 🚫 Código de Conduta

### Princípios

- **Respeito** - Trate todos com respeito
- **Inclusão** - Seja inclusivo e acolhedor
- **Colaboração** - Trabalhe em conjunto
- **Construtividade** - Seja construtivo nas críticas
- **Profissionalismo** - Mantenha comportamento profissional

### Comportamentos Inaceitáveis

- Comentários ofensivos ou discriminatórios
- Trolling ou comportamento disruptivo
- Spam ou conteúdo irrelevante
- Violação de privacidade
- Assédio de qualquer tipo

### Reportando Problemas

Se você encontrar comportamento inaceitável:

1. **Documente** o incidente
2. **Reporte** para maintainers
3. **Mantenha** confidencialidade
4. **Colabore** na resolução

## 🎯 Áreas de Foco

### Prioridades Atuais

1. **Aumentar cobertura de testes** para 80%
2. **Melhorar performance** da aplicação
3. **Adicionar testes de integração**
4. **Implementar CI/CD completo**
5. **Melhorar documentação**

### Tecnologias de Interesse

- **Laravel 12** - Framework principal
- **Vue 3** - Frontend moderno
- **Tailwind CSS** - Estilização
- **Pest PHP** - Testes
- **Docker** - Containerização
- **Clean Architecture** - Arquitetura

## 📞 Comunicação

### Canais

- **Issues** - Para bugs e feature requests
- **Discussions** - Para discussões gerais
- **Pull Requests** - Para contribuições
- **Email** - Para assuntos privados

### Respondendo a Issues

```markdown
## ✅ Resposta Padrão

Obrigado por reportar este problema! 

**Status:** Em análise
**Prioridade:** [Baixa/Média/Alta/Crítica]
**Responsável:** @username

### Próximos Passos
1. [ ] Reproduzir o problema
2. [ ] Investigar causa raiz
3. [ ] Implementar correção
4. [ ] Adicionar testes
5. [ ] Documentar mudanças

### Timeline
- **Análise:** 1-2 dias
- **Desenvolvimento:** 3-5 dias
- **Testes:** 1-2 dias
- **Review:** 1-2 dias

Mantenha-se atualizado através desta issue!
```

## 🙏 Agradecimentos

Obrigado por contribuir para o projeto Todo List! Suas contribuições ajudam a tornar o projeto melhor para todos.

### Reconhecimento

- **Contribuidores** serão listados no README
- **Pull Requests** serão creditados no CHANGELOG
- **Issues** serão agradecidas publicamente
- **Documentação** será creditada aos autores

---

**Juntos construímos um projeto melhor! 🚀** 
