<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ManyToManyTaskUserTest extends TestCase
{
    use RefreshDatabase;

   /** @test */
   public function it_can_associate_users_with_tasks()
    {
        // Criação de dois usuários
        $user1 = User::factory()->create(['name' => 'Usuário 1']);
        $user2 = User::factory()->create(['name' => 'Usuário 2']);

        // Criação de uma tarefa SEM user_id, pois será Many-to-Many
        $task = Task::factory()->create();

        // Associar os usuários à tarefa
        $task->users()->attach([$user1->id, $user2->id]);

        // Recarregar a relação para garantir que os dados foram sincronizados
        $task->load('users');

        // Verificações: A tarefa deve estar associada a ambos os usuários
        $this->assertTrue($task->users->contains($user1));
        $this->assertTrue($task->users->contains($user2));

        // Verificações: Os usuários devem ter a tarefa vinculada
        $this->assertTrue($user1->tasks->contains($task));
        $this->assertTrue($user2->tasks->contains($task));
    }

}
