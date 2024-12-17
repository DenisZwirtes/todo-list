<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskFilterTest extends TestCase
{
    use RefreshDatabase;

    private $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    /** @test */
    public function it_filters_tasks_by_category()
    {
        $category1 = Category::factory()->create(['user_id' => $this->user->id]);
        $category2 = Category::factory()->create(['user_id' => $this->user->id]);

        $task1 = Task::factory()->create([
            'user_id' => $this->user->id,
            'category_id' => $category1->id,
            'title' => 'Tarefa Categoria 1',
        ]);

        $task2 = Task::factory()->create([
            'user_id' => $this->user->id,
            'category_id' => $category2->id,
            'title' => 'Tarefa Categoria 2',
        ]);

        $response = $this->get(route('tasks.index', ['category_id' => $category1->id]));

        $response->assertStatus(200);

        $response->assertSee('Tarefa Categoria 1');
        $response->assertDontSee('Tarefa Categoria 2');
    }

    /** @test */
    public function it_filters_tasks_by_completion_status()
    {
        Task::factory()->create([
            'user_id' => $this->user->id,
            'is_completed' => true,
        ]);

        Task::factory()->create([
            'user_id' => $this->user->id,
            'is_completed' => false,
        ]);

        $response = $this->get(route('tasks.index', ['completed' => true]));

        $response->assertStatus(200);
        $response->assertSee('Sim');
        $response->assertDontSee('Não');
    }

    /** @test */
    public function it_filters_tasks_by_category_and_completion()
    {
        $category = Category::factory()->create(['user_id' => $this->user->id]);

        Task::factory()->create([
            'user_id' => $this->user->id,
            'category_id' => $category->id,
            'is_completed' => true,
        ]);

        Task::factory()->create([
            'user_id' => $this->user->id,
            'category_id' => $category->id,
            'is_completed' => false,
        ]);

        $response = $this->get(route('tasks.index', [
            'category_id' => $category->id,
            'completed' => true,
        ]));

        $response->assertStatus(200);
        $response->assertSee('Sim');
        $response->assertDontSee('Não');
    }
}
