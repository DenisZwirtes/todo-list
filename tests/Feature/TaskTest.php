<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Task;
use App\Models\Category;
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
        $tasks = Task::factory()->count(3)->create();

        foreach ($tasks as $task) {
            $task->users()->attach($this->user->id);
        }

        $response = $this->get(route('tasks.index'));

        $response->assertStatus(200);
        $response->assertSee(__('messages.my_tasks'));
    }


   /** @test */
    public function it_creates_a_new_task()
    {
        $category = Category::factory()->create(['user_id' => $this->user->id]);

        $data = [
            'title' => 'Nova Tarefa',
            'description' => 'Descrição da nova tarefa',
            'category_id' => $category->id,
            'is_completed' => false,
            'users' => [$this->user->id],
        ];

        $response = $this->post(route('tasks.store'), $data);

        $this->assertDatabaseHas('tasks', [
            'title' => 'Nova Tarefa',
            'category_id' => $category->id,
        ]);

        $this->assertDatabaseHas('task_user', [
            'user_id' => $this->user->id,
        ]);

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
        $category = Category::factory()->create(['user_id' => $this->user->id]);

        $task = Task::factory()->create([
            'category_id' => $category->id,
        ]);

        $task->users()->attach($this->user->id);

        $data = [
            'title' => 'Tarefa Atualizada',
            'description' => 'Descrição Atualizada',
            'category_id' => $category->id,
            'users' => [$this->user->id],
        ];

        $response = $this->put(route('tasks.update', $task), $data);

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => 'Tarefa Atualizada',
            'description' => 'Descrição Atualizada',
        ]);

        $this->assertDatabaseHas('task_user', [
            'task_id' => $task->id,
            'user_id' => $this->user->id,
        ]);

        $response->assertRedirect(route('tasks.index'));
    }


    /** @test */
    public function it_deletes_a_task()
    {
        $task = Task::factory()->create();

        $task->users()->attach($this->user->id);

        $response = $this->delete(route('tasks.destroy', $task));

        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);

        $this->assertDatabaseMissing('task_user', [
            'task_id' => $task->id,
            'user_id' => $this->user->id,
        ]);

        $response->assertRedirect(route('tasks.index'));
    }

}
