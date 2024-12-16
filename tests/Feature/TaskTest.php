<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Task;
use Tests\TestCase;

class TaskTest extends TestCase
{
    private $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    protected function tearDown(): void
    {
        Task::query()->delete();
        User::query()->delete();

        parent::tearDown();
    }

    /** @test */
    public function it_displays_task_index_page()
    {
        Task::factory()->count(3)->create(['user_id' => $this->user->id]);

        $response = $this->get(route('tasks.index'));

        $response->assertStatus(200);
        $response->assertSee(__('messages.my_tasks'));
    }

   /** @test */
    public function it_creates_a_new_task()
    {
        // Dados da tarefa
        $data = [
            'title' => 'Nova Tarefa',
            'description' => 'Descrição da nova tarefa',
            'category_id' => null,
            'is_completed' => false,
        ];

        // Envia a requisição POST para criar a tarefa
        $response = $this->post(route('tasks.store'), $data);

        // Verifica se a tarefa foi criada no banco
        $this->assertDatabaseHas('tasks', [
            'title' => 'Nova Tarefa',
            'user_id' => $this->user->id, // Verifica se a tarefa pertence ao usuário autenticado
        ]);

        // Verifica se há redirecionamento para a página de tarefas
        $response->assertRedirect(route('tasks.index'));
    }


    /** @test */
    public function it_validates_task_creation()
    {
        $response = $this->post(route('tasks.store'), [
            'title' => '',
        ]);

        $response->assertSessionHasErrors('title');
    }

    /** @test */
    public function it_updates_a_task()
    {
        $task = Task::factory()->create(['user_id' => $this->user->id]);

        $data = ['title' => 'Tarefa Atualizada'];

        $response = $this->put(route('tasks.update', $task), $data);

        $this->assertDatabaseHas('tasks', ['id' => $task->id, 'title' => 'Tarefa Atualizada']);
        $response->assertRedirect(route('tasks.show', $task));
    }

    /** @test */
    public function it_deletes_a_task()
    {
        $task = Task::factory()->create(['user_id' => $this->user->id]);

        $response = $this->delete(route('tasks.destroy', $task));

        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
        $response->assertRedirect(route('tasks.index'));
    }
}
